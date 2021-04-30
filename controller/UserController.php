<?php
/**
 * ETML
 * Auteur : Arthur Wallef, Pierre Morand & Jeremiah Steiner
 * Date: 25.12.2020
 * Controler pour gérer les clients
 */

include_once("model/Database.php");

class UserController extends Controller 
{
    /**
     * Permet de choisir l'action à effectuer
     *
     * @return mixed
     */
    public function display() 
    {
        $action = "loginFormAction";

        $database = new Database();

        // gestion des erreurs de lien
        if (!array_key_exists("action", $_GET))
        {
            $action = "loginFormAction";
        }
        else
        {
            switch($_GET["action"])
            {
                case "loginForm":
                case "login":
                case "logout":
                case "registerForm":
                case "register":
                    $action = $_GET['action'] . "Action";
                    break;

                case "profile":
                    if (array_key_exists("idUser", $_GET) && $database->userExist($_GET["idUser"]))
                    {
                        $action = $_GET['action'] . "Action";
                    }
                    else 
                    {
                        $action = "loginFormAction";
                    }
                    break;

                default :
                    $action = "loginFormAction";
                    break;
            }
        }

        return call_user_func(array($this, $action));
    }

    /**
     * Affichage du formulaire du login
     *
     * @return string
     */
    private function loginFormAction() 
    {
        $view = file_get_contents('view/page/restrictedPages/loginRegister/loginForm.php');
        
        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Login utilisateur
     *
     */
    private function loginAction() 
    {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);

        $database = new Database();

        //Vérifie le connecteur
        $array = (array) $database;
        if($array["\0Database\0connector"] != NULL){
            $userArray = $database->getOneUser($username);
            $user = $userArray[0];
        }

        if(empty($user)){
            $_SESSION['errorLogin'] = true;
            $_SESSION['isConnected'] = false;
            header('location: index.php?controller=user&action=loginForm');
        }
        else if(password_verify($password, $user['usePassword'])){
            $_SESSION['errorLogin'] = false;
            $_SESSION['isConnected'] = true;
            $_SESSION['username'] = $user['usePseudo'];
            $_SESSION['idUser'] = $user['idUser'];
            $_SESSION['theme'] = $database->getProfileNameById($user['useProfilePref']);
            header('location: index.php');
        }
        else{
            $_SESSION['errorLogin'] = true;
            $_SESSION['isConnected'] = false;
            header('location: index.php?controller=user&action=loginForm');
        }

    }

    /**
     * gère la déconnexion
     *
     * @return void
     */
    private function logoutAction() 
    {
        session_destroy();
        header('location: index.php');
    }

    /**
     * Affichage du formulaire d'enregistrement
     *
     * @return string
     */
    private function registerFormAction() 
    {
        $view = file_get_contents('view/page/restrictedPages/loginRegister/registerForm.php');
        
        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Création d'un utilisateur
     *
     */
    private function registerAction()
    {
        $error = false;
        //Vérification de l'existence des champs
        if(key_exists("username", $_POST) && key_exists("firstName", $_POST) && key_exists("lastName", $_POST) && key_exists("password1", $_POST) && key_exists("password2", $_POST)){
            //Vérification des champs
            if ((htmlspecialchars($_POST['username']) == "" || !preg_match('/^[A-Za-z\d]*(-[A-Za-z\d]*)*$/',htmlspecialchars($_POST['username']))))
            {
                $error = true;
                echo 'Nom dutilisateur non conforme<br>';
            }
            if (($_POST['password1'] != $_POST['password2']))
            {
                $error = true;
                echo 'Les mots de passe ne sont pas identiques<br>';
            }

            if($error == false){
                //TODO : si le temps le permet : vérification
                $username = htmlspecialchars($_POST['username']);
                $firstName = htmlspecialchars($_POST['firstName']);
                $lastName = htmlspecialchars($_POST['lastName']);
                $password = htmlspecialchars($_POST['password1']);
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                include_once($this->databasePath);
                $database = new Database();

                //Vérifie le connecteur
                $array = (array) $database;

                if($array["\0Database\0connector"] != NULL && $database->userExist(htmlspecialchars($_POST['username']))){
                    
                    $database->insertUser($username, $firstName, $lastName, $hashed_password);
                    $_SESSION['isConnected'] = false;
                    //$_SESSION['username'] = $username;
                    //$_SESSION['idUser'] = $user['idUser']; // l'id n'existe pas puisque il n'y a pas de get du dernier user ajouter a la database
                    header('location: index.php'); // redirection vers l'index
                    //rediriger vers une page de confirmation/erreur
                }
                else
                {
                    $error = true;
                    header('location: index.php'); // TODO : ajouter un message d'erreur pour l'utilisateur (pseudo déjà prit) !!!
                }
            }
        }
    }

    /**
     * permet d'accèder à la page de profile de l'utilisateur
     *
     * @return string
     */
    private function profileAction()
    {
        $database = new Database();

        $userProfile = array();
        $user = array();
        $view = "";
        $selfPage = false;
        $modificationDone = false;

        // errors
        $passwordModifFailed = false;
        $imageEmpty = false;
        $errorPngFile = true;

        if (array_key_exists("idUser", $_GET) && $database->userExist(htmlspecialchars($_GET["idUser"])))
        {
            $appartements = $database->getAppartementsByUserId(htmlspecialchars($_GET["idUser"]));
            $userProfile = $database->getOneUserById(htmlspecialchars($_GET["idUser"]));
            $view = file_get_contents('view/page/restrictedPages/userPage.php');
            $size = "";

            if (array_key_exists("idUser", $_SESSION) && $_SESSION["idUser"] == $_GET["idUser"])
            {
                $selfPage = true;
                $profiles = $database->getAllProfiles();

                if (isset($_POST) && !empty($_POST))
                {
                    // TODO : si le temps le permet : vérification du form 
                    $user["idUser"] = $_SESSION["idUser"];
                    $errorPngFile = false;

                    if (array_key_exists("fileUpdate", $_POST)) // form pour update l'image
                    {
                        if (!empty($_FILES["image"]["name"]) && $_FILES["image"]["name"] != "" && $this->extensionOk($_FILES["image"]["name"])) // vérifie qu'il y a bien un fichier de séléctionné // TODO : si le temps le permet : gérer fichier vide (!= "" ne fonctionne pas)
                        {
                            $image = "";
                            $imgName = date("YmdHis") . "_" . $_FILES["image"]["name"];

                            $size = getimagesize($_FILES["image"]["tmp_name"]);

                            switch (pathinfo($imgName, PATHINFO_EXTENSION))
                            {
                                case "PNG": // TODO : régler le problème avec les png !!!
                                case "png":
                                    if ($size[0] * $size[1] < PHP_INT_MAX) // gestion des png trop volumineux
                                    {
                                        
                                        $image = imageCreateFromPng($_FILES["image"]["tmp_name"]); // prépare la compression
                                        $errorPngFile = false;
                                    }
                                    else
                                    {
                                        $errorPngFile = true; // TODO : comprendre pourquoi $errorPngFile n'est jamais set a true
                                    }
                                    break;
            
                                case "JPG":
                                case "jpg":
                                    $image = imagecreatefromjpeg($_FILES["image"]["tmp_name"]); // prépare la compression
                                    $errorPngFile = false;
                                    break;
                                
                                case "GIF":
                                case "gif":
                                    $image = imagecreatefromgif($_FILES["image"]["tmp_name"]); // prépare la compression
                                    $errorPngFile = false;
                                    break;
                                default:
                                    break;
                            }
                            
                            if (!$errorPngFile)
                            {
                                if ($userProfile["useImage"] != "defaultUserPicture.png" && file_exists("resources/image/Users/" . $userProfile["useImage"]))
                                {
                                    unlink("resources/image/Users/" . $userProfile["useImage"]); // suppression de l'ancienne image
                                }

                                imagejpeg($image, "resources/image/Users/" . $imgName, 75); // compression de l'image 

                                //move_uploaded_file($_FILES["image"]["tmp_name"], "resources/image/Users/" . $imgName);

                                $userProfile["useImage"] = $imgName;
                                $user["useImage"] = $imgName;
                            }
                        }
                        else 
                        {
                            $imageEmpty = true;
                            $errorPngFile = false;
                        }
                    }
                    else if (array_key_exists("modifPasswordForm", $_POST)) // gère la modification du mot de passe
                    {
                        $errorPngFile = false;

                        if (array_key_exists("usePassword", $_POST) && array_key_exists("confirmePassword", $_POST))
                        {
                            if ($_POST["usePassword"] === $_POST["confirmePassword"]) // TODO : si le temps le permet : ajouter des validation pour le mot de passe
                            {
                                $user["usePassword"] = password_hash($_POST["usePassword"], PASSWORD_DEFAULT);
                            }
                            else
                            {
                                $passwordModifFailed = true;
                            }
                        }
                        else
                        {
                            $passwordModifFailed = true;
                        }
                    }
                    else 
                    {
                        $errorPngFile = false;
                        // TODO : si le temps le permet : faire la vérification de champ (ptetre faire une méthode, étant donné que l'on doit aussi l'utiliser pour l'inscription)
                        
                        if (array_key_exists("pseudo", $_POST) && sizeof($_POST["pseudo"]) >= 3 && !$database->userExistByPseudo(htmlspecialchars($_POST["pseudo"]))) // vérifie que le pseudo est disponible
                        {
                            $user["usePseudo"] = htmlspecialchars($_POST["pseudo"]);
                        }
                        else
                        {
                            $user["usePseudo"] = $userProfile["usePseudo"];
                        }
                        
                        if (array_key_exists("useFirstname", $_POST) )// TODO : vérification de champ basique
                        {
                            $user["useFirstname"] = htmlspecialchars($_POST["useFirstname"]);
                        }
                        else
                        {
                            $user["useFirstname"] = $userProfile["useFirstname"];
                        }

                        if (array_key_exists("useName", $_POST) )// TODO : vérification de champ basique
                        {
                            $user["useName"] = htmlspecialchars($_POST["useName"]);
                        }
                        else
                        {
                            $user["useName"] = $userProfile["useName"];
                        }

                        if (array_key_exists("mail", $_POST) ) // TODO : vérification de champ basique + mail
                        {
                            $user["useMail"] = htmlspecialchars($_POST["mail"]);
                        }
                        else
                        {
                            $user["useMail"] = $userProfile["useMail"];
                        }

                        if (array_key_exists("phone", $_POST) ) // TODO : vérification de champ basique + téléphone
                        {
                            $user["usePhone"] = htmlspecialchars($_POST["phone"]);
                        }
                        else
                        {
                            $user["usePhone"] = $userProfile["usePhone"];
                        }

                        if (array_key_exists("profilePref", $_POST) && $_POST["profilePref"] > 0 && $database->profileExist(htmlspecialchars($_POST["profilePref"]))) // TODO : vérification de champ basique + vérifier que c'est un id dispo de t_profil
                        {
                            $user["useProfilePref"] = htmlspecialchars($_POST["profilePref"]);
                        }
                        else
                        {
                            $user["useProfilePref"] = $userProfile["useProfilePref"];
                        }
                    }

                    if (!$passwordModifFailed && !$imageEmpty && !$errorPngFile) // NOTE : (à vérifier à la fin du projet) ajouter les autre erreur ici afin que cela ne modifie pas la database s'il y a une erreur de form
                    {
                        $modificationDone = true;
                        $database->updateUser($user);
                        
                        $userProfile = $database->getOneUserById($_SESSION["idUser"]); // permet d'afficher directement les modifications
                        $_SESSION['theme'] = $database->getProfileNameById($userProfile['useProfilePref']);
                    }

                    $view = file_get_contents('view/page/restrictedPages/userPage.php');
                }
                else
                {
                    if (array_key_exists("pic", $_GET) && $_GET["pic"] == "true")
                    {
                        $errorPngFile = true;
                    }
                    else
                    {
                        $errorPngFile = false;
                    }
                    
                }
            }
        }
        else if (array_key_exists("idUser", $_SESSION))
        {
            $errorPngFile = false;

            $appartements = $database->getAppartementsByUserId($_SESSION["idUser"]);
            $userProfile = $database->getOneUserById($_SESSION["idUser"]);
            $view = file_get_contents('view/page/restrictedPages/userPage.php');
            $selfPage = false;

            if (array_key_exists("idUser", $_GET) && array_key_exists("idUser", $_SESSION) && $_GET["idUser"] == $_SESSION["idUser"])
            {
                $selfPage = true;
            }
        }
        else 
        {
            $userProfile = null;
            $errorPngFile = false;
            $view = file_get_contents('view/page/restrictedPages/loginRegister/loginForm.php');
        }

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

}
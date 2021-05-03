<?php

use function PHPSTORM_META\elementType;

/**
 * ETML
 * Auteur : Jeremiah Steiner
 * Date: 27.04.2021
 * Controler pour gérer les appartements
 */

include_once("model/Database.php");

class AppartementController extends Controller {

    /**
     * Permet de choisir l'action à effectuer
     *
     * @return mixed
     */
    public function display() {
        $action = "listAction";

        $database = new Database();

        // gestion des erreurs de lien
        if (!array_key_exists("action", $_GET))
        {
            $action = "listAction";
        }
        else 
        {   
            // TODO : vérifier que les conditions sont les bonnes pour les nouvelles page ajoutée
            switch($_GET["action"])
            {
                case "list":
                case "rate":
                case "unrate":
                    $action = $_GET["action"] . "Action";
                    break;
                    
                case "detail":
                    if (array_key_exists("id", $_GET) && $database->AppartementExist($_GET["id"]) && array_key_exists("isConnected", $_SESSION) && $_SESSION["isConnected"])
                    {
                        $action = $_GET["action"] . "Action";
                    }
                    else
                    {
                        $action = "listAction";
                    }
                    break;

                case "wishlist":
                case "addWish":
                case "removeWish":
                case "addAppartement":
                    if (array_key_exists("isConnected", $_SESSION) && $_SESSION["isConnected"])
                    {
                        $action = $_GET["action"] . "Action";
                    }
                    else
                    {
                        $action = "listAction";
                    }
                    break;

                case "deleteAppartement":
                case "editAppartement":
                    if (array_key_exists("id", $_GET) && $database->AppartementExist($_GET["id"]) && array_key_exists("isConnected", $_SESSION) && $_SESSION["isConnected"])
                    {
                        $action = $_GET["action"] . "Action";
                    }
                    else
                    {
                        $action = "listAction";
                    }
                    break;

                default:
                    $action = "listAction";
                    break;
            }
        }

        // Appelle une méthode dans cette classe (ici, ce sera le nom + action (ex: listAction, detailAction, ...))
        return call_user_func(array($this, $action));
    }

    /**
     * Rechercher les données et les passe à la vue (en liste)
     *
     * @return string
     */
    private function listAction() {
        // Instancie le modèle et va chercher les informations

        $database = new Database();

        $startIndex = 0;
        $lengthAppartement = 5; // UTIL : modifier si on veut pouvoir modifier le nombre d'appartement affiché
        $_SESSION["appartementsPerPage"] = $lengthAppartement;

        if (array_key_exists("start", $_GET) && $_GET["start"] > 0) // si le paramettre de start n'est pas négatif
        {
            $this->normalizeStartIndex($startIndex, $database, $lengthAppartement); // permet de trouver le startindex optimal
        }
        else
        {
            $_GET["start"] = 0;
        }

        $inWish = false;

        if (array_key_exists("id", $_GET) && $database->AppartementExist($_GET["id"]))
        {
            if (array_key_exists("idUser", $_SESSION) && $database->wishExtist($_SESSION["idUser"], $_GET["id"]))
            {
                $inWish = true;
            }
        }

        $appartements = $database->getAllAppartements($startIndex, $lengthAppartement);

        // Charge le fichier pour la vue
        $view = file_get_contents('view/page/appartement/list.php');


        // Pour que la vue puisse afficher les bonnes données, il est obligatoire que les variables de la vue puisse contenir les valeurs des données
        // ob_start est une méthode qui stoppe provisoirement le transfert des données (donc aucune donnée n'est envoyée).
        ob_start();
        // eval permet de prendre le fichier de vue et de le parcourir dans le but de remplacer les variables PHP par leur valeur (provenant du model)
        eval('?>' . $view);//*/
        // ob_get_clean permet de reprendre la lecture qui avait été stoppée (dans le but d'afficher la vue)
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Rechercher les données et les passe à la vue (en liste)
     *
     * @return string
     */
    private function wishlistAction() {
        // Instancie le modèle et va chercher les informations

        $database = new Database();

        $startIndex = 0;
        $lengthAppartement = 5; // UTIL : modifier si on veut pouvoir modifier le nombre d'appartement affiché
        $_SESSION["appartementsPerPage"] = $lengthAppartement;

        if (array_key_exists("start", $_GET) && $_GET["start"] > 0) // si le paramettre de start n'est pas négatif
        {
            $this->normalizeStartIndex($startIndex, $database, $lengthAppartement); // permet de trouver le startindex optimal
        }
        else
        {
            $_GET["start"] = 0;
        }

        $inWish = false;

        if (array_key_exists("id", $_GET) && $database->AppartementExist($_GET["id"]))
        {
            if ($database->wishExtist($_SESSION["idUser"], $_GET["id"]))
            {
                $inWish = true;
            }
        }

        $appartements = $database->getAllAppartements($startIndex, $lengthAppartement);
        $wishAppartements = $database->getAllWishAppartements($startIndex, $lengthAppartement, $_SESSION["idUser"]);

        // Charge le fichier pour la vue
        $view = file_get_contents('view/page/appartement/wishlist.php');


        // Pour que la vue puisse afficher les bonnes données, il est obligatoire que les variables de la vue puisse contenir les valeurs des données
        // ob_start est une méthode qui stoppe provisoirement le transfert des données (donc aucune donnée n'est envoyée).
        ob_start();
        // eval permet de prendre le fichier de vue et de le parcourir dans le but de remplacer les variables PHP par leur valeur (provenant du model)
        eval('?>' . $view);//*/
        // ob_get_clean permet de reprendre la lecture qui avait été stoppée (dans le but d'afficher la vue)
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Rechercher les données et les passe à la vue (en liste)
     *
     * @return string
     */
    private function addWishAction() {
        // Instancie le modèle et va chercher les informations

        $database = new Database();

        $startIndex = 0;
        $lengthAppartement = 5; // UTIL : modifier si on veut pouvoir modifier le nombre d'appartements affichés'
        $_SESSION["appartementsPerPage"] = $lengthAppartement;
        $inWish = false;

        if (array_key_exists("start", $_GET) && $_GET["start"] > 0) // si le paramettre de start n'est pas négatif
        {
            $this->normalizeStartIndex($startIndex, $database, $lengthAppartement); // permet de trouver le startindex optimal
        }
        else
        {
            $_GET["start"] = 0;
        }

        if (array_key_exists("id", $_GET) && $database->AppartementExist($_GET["id"]))
        {
            $database->insertWish($_SESSION["idUser"], $_GET["id"]);
            error_log("InsertWish, idUser : " . $_SESSION["idUser"] . ", appId : " . $_GET["id"] . " \t\t\t\t[jour-heure] " . $database->getDate()["currentTime"] . "\r", 3, "data/Logs/DataModifications/wishLists.log");
            
            if ($database->wishExtist($_SESSION["idUser"], $_GET["id"]))
            {
                $inWish = true;
            }
            else
            {
                $inWish = false;
                error_log("InsertWish, pseudo : " . htmlspecialchars($_SESSION["username"]) . ", appId : " . $_GET["id"] . " \t\t\t\t[jour-heure] " . $database->getDate()["currentTime"] . "\r", 3, "data/Logs/errors/errorLogTest.log");                
            }
        }

        $appartements = $database->getAllAppartements($startIndex, $lengthAppartement);

        // Charge le fichier pour la vue
        $view = file_get_contents('view/page/appartement/list.php');


        // Pour que la vue puisse afficher les bonnes données, il est obligatoire que les variables de la vue puisse contenir les valeurs des données
        // ob_start est une méthode qui stoppe provisoirement le transfert des données (donc aucune donnée n'est envoyée).
        ob_start();
        // eval permet de prendre le fichier de vue et de le parcourir dans le but de remplacer les variables PHP par leur valeur (provenant du model)
        eval('?>' . $view);//*/
        // ob_get_clean permet de reprendre la lecture qui avait été stoppée (dans le but d'afficher la vue)
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Rechercher les données et les passe à la vue (en liste)
     *
     * @return string
     */
    private function removeWishAction() {
        // Instancie le modèle et va chercher les informations
        $database = new Database();

        $startIndex = 0;
        $lengthAppartement = 5; // UTIL : modifier si on veut pouvoir modifier le nombre d'appartements affichés'
        $_SESSION["appartementsPerPage"] = $lengthAppartement;

        if (array_key_exists("start", $_GET) && $_GET["start"] > 0) // si le paramettre de start n'est pas négatif
        {
            $this->normalizeStartIndex($startIndex, $database, $lengthAppartement); // permet de trouver le startindex optimal
        }
        else
        {
            $_GET["start"] = 0;
        }

        if (array_key_exists("id", $_GET) && $database->AppartementExist($_GET["id"]))
        {
            $database->removeWish($_SESSION["idUser"], $_GET["id"]);
            error_log("RemoveWish, idUser : " . $_SESSION["idUser"] . ", appId : " . $_GET["id"] . " \t\t\t\t[jour-heure] " . $database->getDate()["currentTime"] . "\r", 3, "data/Logs/DataModifications/wishLists.log");
        }

        $inWish = false;

        if (array_key_exists("id", $_GET) && $database->AppartementExist($_GET["id"]))
        {
            if ($database->wishExtist($_SESSION["idUser"], $_GET["id"]))
            {
                $inWish = true;
            }
        }

        $appartements = $database->getAllAppartements($startIndex, $lengthAppartement);
        $wishAppartements = $database->getAllWishAppartements($startIndex, $lengthAppartement, $_SESSION["idUser"]);

        // Charge le fichier pour la vue
        if (array_key_exists("page", $_GET) && $_GET["page"] == "wishlist")
        {
            $view = file_get_contents('view/page/appartement/wishlist.php');
        }
        else
        {
            $view = file_get_contents('view/page/appartement/list.php');
        }


        // Pour que la vue puisse afficher les bonnes données, il est obligatoire que les variables de la vue puisse contenir les valeurs des données
        // ob_start est une méthode qui stoppe provisoirement le transfert des données (donc aucune donnée n'est envoyée).
        ob_start();
        // eval permet de prendre le fichier de vue et de le parcourir dans le but de remplacer les variables PHP par leur valeur (provenant du model)
        eval('?>' . $view);//*/
        // ob_get_clean permet de reprendre la lecture qui avait été stoppée (dans le but d'afficher la vue)
        $content = ob_get_clean();

        return $content;
    }

    /**
     * permet d'ajouter ou de modifier sa note pour un appartement
     *
     * @return string
     */
    private function rateAction() {
        // Instancie le modèle et va chercher les informations
        $database = new Database();

        $startIndex = 0;
        $lengthAppartement = 5; // UTIL : modifier si on veut pouvoir modifier le nombre d'appartements affichés
        $_SESSION["appartementsPerPage"] = $lengthAppartement;

        if (array_key_exists("start", $_GET) && $_GET["start"] > 0) // si le paramettre de start n'est pas négatif
        {
            $this->normalizeStartIndex($startIndex, $database, $lengthAppartement); // permet de trouver le startindex optimal
        }
        else
        {
            $_GET["start"] = 0;
        }

        if (array_key_exists("id", $_GET) && $database->AppartementExist($_GET["id"]) && !$database->isRateExist($_SESSION["idUser"], $_GET["id"]))
        {
            $database->insertRating($_SESSION["idUser"], $_GET["id"]);
            $database->updateWish($_SESSION["idUser"], $_GET["id"], $database->GetVisitedStateForWish($_SESSION["idUser"], $_GET["id"]), 1);
            $database->updateAppartementRate($_GET["id"]);

            error_log("RateAppartement, idUser : " . $_SESSION["idUser"] . ", appId : " . $_GET["id"] . " \t\t\t\t\t[jour-heure] " . $database->getDate()["currentTime"] . "\r", 3, "data/Logs/DataModifications/appartements.log");            
        }

        $appartements = $database->getAllAppartements($startIndex, $lengthAppartement);
        $wishAppartements = $database->getAllWishAppartements($startIndex, $lengthAppartement, $_SESSION["idUser"]);

        // Charge le fichier pour la vue
        $view = file_get_contents('view/page/appartement/wishlist.php');


        // Pour que la vue puisse afficher les bonnes données, il est obligatoire que les variables de la vue puisse contenir les valeurs des données
        // ob_start est une méthode qui stoppe provisoirement le transfert des données (donc aucune donnée n'est envoyée).
        ob_start();
        // eval permet de prendre le fichier de vue et de le parcourir dans le but de remplacer les variables PHP par leur valeur (provenant du model)
        eval('?>' . $view);//*/
        // ob_get_clean permet de reprendre la lecture qui avait été stoppée (dans le but d'afficher la vue)
        $content = ob_get_clean();

        return $content;
    }

    /**
     * permet d'ajouter ou de modifier sa note pour un appartement
     *
     * @return string
     */
    private function unrateAction() {
        // Instancie le modèle et va chercher les informations
        $database = new Database();

        $startIndex = 0;
        $lengthAppartement = 5; // UTIL : modifier si on veut pouvoir modifier le nombre d'appartements affichés
        $_SESSION["appartementsPerPage"] = $lengthAppartement;

        if (array_key_exists("start", $_GET) && $_GET["start"] > 0) // si le paramettre de start n'est pas négatif
        {
            $this->normalizeStartIndex($startIndex, $database, $lengthAppartement); // permet de trouver le startindex optimal
        }
        else
        {
            $_GET["start"] = 0;
        }

        if (array_key_exists("id", $_GET) && $database->AppartementExist($_GET["id"]) && $database->isRateExist($_SESSION["idUser"], $_GET["id"]))
        {
            $database->removeRating($_SESSION["idUser"], $_GET["id"]);
            $database->updateWish($_SESSION["idUser"], $_GET["id"], $database->GetVisitedStateForWish($_SESSION["idUser"], $_GET["id"]), 0);
            $database->updateAppartementRate($_GET["id"]);
            error_log("UnrateAppartement, idUser : " . $_SESSION["idUser"] . ", appId : " . $_GET["id"] . " \t\t\t\t[jour-heure] " . $database->getDate()["currentTime"] . "\r", 3, "data/Logs/DataModifications/appartements.log");            
        }

        $appartements = $database->getAllAppartements($startIndex, $lengthAppartement);
        $wishAppartements = $database->getAllWishAppartements($startIndex, $lengthAppartement, $_SESSION["idUser"]);

        // Charge le fichier pour la vue
        $view = file_get_contents('view/page/appartement/wishlist.php');


        // Pour que la vue puisse afficher les bonnes données, il est obligatoire que les variables de la vue puisse contenir les valeurs des données
        // ob_start est une méthode qui stoppe provisoirement le transfert des données (donc aucune donnée n'est envoyée).
        ob_start();
        // eval permet de prendre le fichier de vue et de le parcourir dans le but de remplacer les variables PHP par leur valeur (provenant du model)
        eval('?>' . $view);//*/
        // ob_get_clean permet de reprendre la lecture qui avait été stoppée (dans le but d'afficher la vue)
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Rechercher les données et les passe à la vue (en détail)
     *
     * @return string
     */
    private function detailAction() {
        $database = new Database();
        $alreadyRate = false;

        if (array_key_exists("rate", $_GET))
        {
            if ($_GET["rate"] == 0)
            {
                $database->insertRating($_SESSION["idUser"], $_GET["id"]);
                if ($database->wishExtist($_SESSION["idUser"], $_GET["id"]))
                {
                    $database->updateWish($_SESSION["idUser"], $_GET["id"], $database->GetVisitedStateForWish($_SESSION["idUser"], $_GET["id"]), 1);
                }
                $database->updateAppartementRate($_GET["id"]);
            }
            else
            {
                $database->removeRating($_SESSION["idUser"], $_GET["id"]);
                if ($database->wishExtist($_SESSION["idUser"], $_GET["id"]))
                {
                    $database->updateWish($_SESSION["idUser"], $_GET["id"], $database->GetVisitedStateForWish($_SESSION["idUser"], $_GET["id"]), 0);
                }
                $database->updateAppartementRate($_GET["id"]);
            }
        }
        
        $appartement = $database->getOneAppartement($_GET['id']);
        $appartementCreator = $database->getOneUserById($appartement["idUser"]);

        if ($database->isRateExist($_SESSION["idUser"], $_GET["id"]))
        {
            $alreadyRate = true;
        }
        

        $view = file_get_contents('view/page/appartement/detail.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * permet d'ajouter un appartement
     *
     * @return string
     */
    private function addAppartementAction() {
        $database = new Database();
        
        $formError = false;

        if (isset($_POST["appartementCreation1"])) // vérification de champ
        {
            $_SESSION["appartement"] = array();
            $_SESSION["appartement"]["appName"] = "";
            $_SESSION["appartement"]["appDescription"] = "";
            $_SESSION["appartement"]["appCategory"] = "";
            $_SESSION["appartement"]["appSurface"] = -1;
            $_SESSION["appartement"]["appPrix"] = -1;
            
            if (array_key_exists("appName", $_POST) && trim($_POST["appName"]) != "" && strlen($_POST["appName"]) <= 100 && strlen($_POST["appName"]) > 1) // 2 charactère minimum
            {
                $_SESSION["appartement"]["appName"] = $_POST["appName"];
            }
            else
            {
                $formError = true;
            }

            if (array_key_exists("appCategory", $_POST) && trim($_POST["appCategory"]) != "" && strlen($_POST["appCategory"]) <= 100 && strlen($_POST["appCategory"]) > 4) // 5 charactère minimum
            {
                $_SESSION["appartement"]["appCategory"] = $_POST["appCategory"];
            }
            else
            {
                $formError = true;
            }

            if (array_key_exists("appSurface", $_POST) && trim($_POST["appSurface"]) != "" && (int)$_POST["appSurface"] >= 10) // TODO : Définir une surface minimum plausible
            {
                $_SESSION["appartement"]["appSurface"] = $_POST["appSurface"];
            }
            else
            {
                $formError = true;
            }

            if (array_key_exists("appPrix", $_POST) && trim($_POST["appPrix"]) != "" && (int)$_POST["appPrix"] >= 1 && (int)$_POST["appPrix"] >= 10) // TODO : Définir un prix minimum plausible
            {
                $_SESSION["appartement"]["appPrix"] = $_POST["appPrix"];
            }
            else
            {
                $formError = true;
            }

            if (array_key_exists("appDescription", $_POST) && trim($_POST["appDescription"]) != "" && strlen($_POST["appDescription"]) <= 255 && strlen($_POST["appDescription"]) > 4) // 5 charactère minimum
            {
                $_SESSION["appartement"]["appDescription"] = $_POST["appDescription"];
            }
            else
            {
                $formError = true;
            }
        }
        
        if (isset($_POST["appartementCreation1"]) && !$formError)
        {
            $appartement = array();

            $appartement["idUser"] = $_SESSION["idUser"];
            $appartement["appName"] = $_SESSION["appartement"]["appName"];
            $appartement["appCategory"] = $_SESSION["appartement"]["appCategory"];
            $appartement["appSurface"] = (int)$_SESSION["appartement"]["appSurface"];
            $appartement["appPrix"] = (int)$_SESSION["appartement"]["appPrix"];
            $appartement["appDescription"] = $_SESSION["appartement"]["appDescription"];
            
            $date = $database->getDate();
            $appartement["appDate"] = $date["currentTime"];
            $appartement["appImage"] = "defaultAppartementPicture.jpg";
            $appartement["appRate"] = 0;

            $database->insertAppartement($appartement); // insertion de l'appartement dans la base de donnée
            $appartement = $database->getLastAppartement(); //get l'appartement pour la page edit (où l'on peut ajouter une image)

            error_log("AddAppartement, idUser : " . $_SESSION["idUser"] . ", appId : " . $appartement["idAppartement"] . " \t\t\t\t[jour-heure] " . $database->getDate()["currentTime"] . "\r", 3, "data/Logs/DataModifications/appartements.log");
            $view = file_get_contents('view/page/restrictedPages/manageAppartement/editAppartement.php');
        }
        else
        {
            $view = file_get_contents('view/page/restrictedPages/manageAppartement/addAppartement.php');
            
            if ($formError)
            {
                error_log("AddAppartement, idUser : " . $_SESSION["idUser"] . " \t\t\t\t[jour-heure] " . $database->getDate()["currentTime"] . "\r", 3, "data/Logs/errors/errorLogTest.log");
            }
        }
        

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * permet de modifier un appartement
     *
     * @return string
     */
    private function editAppartementAction() { // TODO : ajouter un retour pour l'utilisateur pour savoir que cela a bien été prit en compte par le site
        $database = new Database();

        $appartement = $database->getOneAppartement($_GET["id"]);

        $imageEmpty = false;
        $formError = false;

        if (array_key_exists("fileUpdate", $_POST)) // modification de l'image
        {
            if(!empty($_FILES["image"]["name"]) && $this->extensionOk($_FILES["image"]["name"])) // TODO : si le temps le permet : faire de meilleures vérifications (ex: nom de fichier trop long... )
            {
                if ($appartement["appImage"] != "defaultAppartementPicture.jpg" && file_exists("resources/image/Appartements/" . $appartement["appImage"]))
                {
                    unlink("resources/image/Appartements/" . $appartement["appImage"]); // suppression de l'ancienne image
                }
                
                $image = "";
                $imgName = date("YmdHis") . "_" . $_FILES["image"]["name"];

                switch (pathinfo($imgName, PATHINFO_EXTENSION))
                {
                    case "png":
                        $image = imagecreatefrompng($_FILES["image"]["tmp_name"]); // prépare la compression
                        break;

                    case "jpg":
                        $image = imagecreatefromjpeg($_FILES["image"]["tmp_name"]); // prépare la compression
                        break;
                        
                    case "gif":
                        $image = imagecreatefromgif($_FILES["image"]["tmp_name"]); // prépare la compression
                        break;
                    default:
                        break;
                }

                imagejpeg($image, "resources/image/Appartements/" . $imgName, 75); // compression de l'image
                //move_uploaded_file($_FILES["image"]["tmp_name"], "resources/image/Appartements/" . $imgName);
                $appartement["appImage"] = $imgName;
                
                $database->editAppartement($appartement); // modification du nom dans la database
                error_log("EditAppartement Image, idUser : " . $_SESSION["idUser"] . ", appId : " . $appartement["idAppartement"] . " \t\t\t\t[jour-heure] " . $database->getDate()["currentTime"] . "\r", 3, "data/Logs/DataModifications/appartements.log");
            }
            else
            {
                $imageEmpty = true;
                error_log("EditAppartement Image, idUser : " . $_SESSION["idUser"] . ", appId : " . $appartement["idAppartement"] . " \t\t\t\t[jour-heure] " . $database->getDate()["currentTime"] . "\r", 3, "data/Logs/errors/errorLogTest.log");
            }
        }

        if (isset($_POST["appartementEdit"]))
        {
            $_SESSION["appartement"] = array();
            $_SESSION["appartement"]["appName"] = "";
            $_SESSION["appartement"]["appDescription"] = "";
            $_SESSION["appartement"]["appCategory"] = "";
            $_SESSION["appartement"]["appSurface"] = -1;
            $_SESSION["appartement"]["appPrix"] = -1;
            
            if (array_key_exists("appName", $_POST) && trim($_POST["appName"]) != "" && strlen($_POST["appName"]) <= 100 && strlen($_POST["appName"]) > 1) // 2 charactère minimum
            {
                $_SESSION["appartement"]["appName"] = $_POST["appName"];
            }
            else
            {
                $formError = true;
            }

            if (array_key_exists("appCategory", $_POST) && trim($_POST["appCategory"]) != "" && strlen($_POST["appCategory"]) <= 100 && strlen($_POST["appCategory"]) > 4) // 5 charactère minimum
            {
                $_SESSION["appartement"]["appCategory"] = $_POST["appCategory"];
            }
            else
            {
                $formError = true;
            }

            if (array_key_exists("appSurface", $_POST) && trim($_POST["appSurface"]) != "" && (int)$_POST["appSurface"] >= 10) // TODO : Définir une surface minimum plausible
            {
                $_SESSION["appartement"]["appSurface"] = $_POST["appSurface"];
            }
            else
            {
                $formError = true;
            }

            if (array_key_exists("appPrix", $_POST) && trim($_POST["appPrix"]) != "" && (int)$_POST["appPrix"] >= 1 && (int)$_POST["appPrix"] >= 10) // TODO : Définir un prix minimum plausible
            {
                $_SESSION["appartement"]["appPrix"] = $_POST["appPrix"];
            }
            else
            {
                $formError = true;
            }

            if (array_key_exists("appDescription", $_POST) && trim($_POST["appDescription"]) != "" && strlen($_POST["appDescription"]) <= 255 && strlen($_POST["appDescription"]) > 4) // 5 charactère minimum
            {
                $_SESSION["appartement"]["appDescription"] = $_POST["appDescription"];
            }
            else
            {
                $formError = true;
            }

            if (!$formError)
            {
                $appartementTMP = array();

                $appartementTMP["idAppartement"] = $appartement["idAppartement"];

                $appartementTMP["idUser"] = $_SESSION["idUser"];
                $appartementTMP["appName"] = $_SESSION["appartement"]["appName"];
                $appartementTMP["appCategory"] = $_SESSION["appartement"]["appCategory"];
                $appartementTMP["appSurface"] = (int)$_SESSION["appartement"]["appSurface"];
                $appartementTMP["appPrix"] = (int)$_SESSION["appartement"]["appPrix"];
                $appartementTMP["appDescription"] = $_SESSION["appartement"]["appDescription"];
                
                $appartementTMP["appDate"] = $appartement["appDate"];
                $appartementTMP["appImage"] = $appartement["appImage"];
                $appartementTMP["appRate"] = 0;

                $database->editAppartement($appartementTMP); // modification de l'appartement dans la base de donnée
                error_log("AddAppartement Données, idUser : " . $_SESSION["idUser"] . ", appId : " . $appartement["idAppartement"] . " \t\t\t\t[jour-heure] " . $database->getDate()["currentTime"] . "\r", 3, "data/Logs/DataModifications/appartements.log");
                $appartement = $database->getOneAppartement($_GET["id"]);
            }
            else
            {
                error_log("AddAppartement Données, idUser : " . $_SESSION["idUser"] . ", appId : " . $appartement["idAppartement"] . " \t\t\t\t[jour-heure] " . $database->getDate()["currentTime"] . "\r", 3, "data/Logs/errors/erroLogTest.log");
            }
        }

        $view = file_get_contents('view/page/restrictedPages/manageAppartement/editAppartement.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * permet de supprimer un appartement
     *
     * @return string
     */
    private function deleteAppartementAction() {
        $database = new Database();

        if ($database->AppartementExist($_GET["id"]))
        {
            $appartement = $database->getOneAppartement($_GET["id"]);

            if ($appartement["recImage"] != "defaultAppartementPicture.jpg" && file_exists("resources/image/Appartements/" . $appartement["recImage"]))
            {
                unlink("resources/image/Appartements/" . $appartement["recImage"]); // suppression de l'ancienne image
            }

            if ($appartement["idUser"] == $_SESSION["idUser"])
            {
                $database->deleteAppartement($_GET["id"]); // suppression dans la base de donnée
                error_log("RemoveAppartement, idUser : " . $_SESSION["idUser"] . ", appId : " . $_GET["id"] . " \t\t\t\t[jour-heure] " . $database->getDate()["currentTime"] . "\r", 3, "data/Logs/DataModifications/appartements.log");
            }
        }

        // redirection vers la page d'accueil
        $lastAppartement = $database->getLastAppartement();

        //tester si la valeur existe si elle n'existe pas ou est incorect on la set a 0
        if (!array_key_exists('image', $_GET) || $_GET['image'] < 0 || $_GET['image'] > 2)
        {
            $_GET['image'] = 0;
        }

        $view = file_get_contents('view/page/home/carousel.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * vérifie le nombre du get
     *
     * @param int $get
     * @param int $lengthAppartement
     * @return int
     */
    private function normalize($get, $lengthAppartement) {
        //var_dump($get - $lengthAppartement);

        if ($get%$lengthAppartement != 0) // si le get n'est pas un nombre parfait au sens qu'il donne une page précise et pas une page entre-deux
        {
            return $get - $get%$lengthAppartement;
        }
        else
        {
            return $get;
        }
    }

    /**
     * permet de rendre l'index de départ rond par rapport au nombre de page et au nombre d'appartement par page <etc class=""></etc>
     *
     * @param int $startIndex 
     * @param [type] $database
     * @param int $lengthAppartement
     * @return void
     */
    private function normalizeStartIndex(&$startIndex, $database, $lengthAppartement) {
        $appartementNumber = $database->CountAppartements();
        $_SESSION["appartementsNumber"] = $appartementNumber;

        if ($appartementNumber > $_GET["start"]) // si le paramettre n'est ni trop grand ni trop petit
        {
            //$startIndex = $_GET["start"];
            $startIndex = $this->normalize($_GET["start"], $lengthAppartement);
        }
        else if ($_GET["start"] == $appartementNumber)
        {
            $startIndex = $_GET["start"] - $lengthAppartement;
        }
        else
        {
            //$startIndex = $appartementNumber - ($lengthAppartement - $appartementNumber%$lengthAppartement);
            if ($lengthAppartement == $appartementNumber)
            {
                $startIndex = 0;
            }
            else if ($lengthAppartement == 1)
            {
                $startIndex = $appartementNumber - $appartementNumber%$lengthAppartement - 1;
            }
            else if ($_GET["start"] == PHP_INT_MAX)
            {
                if ($appartementNumber%$lengthAppartement == 0) // s'il y a pil le meme nombre d'appartement
                {
                    $startIndex = $appartementNumber - ($lengthAppartement - $appartementNumber%$lengthAppartement);
                }
                else // s'il y a plus d'appartement
                {
                    $startIndex = $appartementNumber - $appartementNumber%$lengthAppartement;
                }
            }
            else
            {
                $startIndex = $appartementNumber - $appartementNumber%$lengthAppartement;
            }
        }

        $_GET["start"] = $startIndex;
    }
}
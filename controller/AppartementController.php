<?php

use function PHPSTORM_META\elementType;

/**
 * ETML
 * Auteur : Jeremiah Steiner
 * Date: 27.04.2021
 * Controler pour gérer les recettes
 */

//include_once 'model/RecetteRepository.php';
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
            switch($_GET["action"]) 
            {
                case "list":
                case "wishlist":
                case "rate":
                case "unrate":
                case "addWish":
                case "removeWish":
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
        $lengthAppartement = 5; // UTIL : modifier si on veut pouvoir modifier le nombre de recette affichée
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
        $lengthAppartement = 5; // UTIL : modifier si on veut pouvoir modifier le nombre de recette affichée
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
     * permet d'ajouter ou de modifier sa note pour une recette
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

        if (array_key_exists("id", $_GET) && $database->AppartementExist($_GET["id"]))
        {
            $database->insertRating($_SESSION["idUser"], $_GET["id"]);
            $database->updateWish($_SESSION["idUser"], $_GET["id"], $database->GetVisitedStateForWish($_SESSION["idUser"], $_GET["id"]), 1);
            $database->updateAppartementRate($_GET["id"]);
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
     * permet d'ajouter ou de modifier sa note pour une recette
     *
     * @return string
     */
    private function unrateAction() { // TODO : ne pas oublié d'update le wishlist et la list d'appartement en calculant le nouveau rating
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

        if (array_key_exists("id", $_GET) && $database->AppartementExist($_GET["id"]))
        {
            $database->removeRating($_SESSION["idUser"], $_GET["id"]);
            $database->updateWish($_SESSION["idUser"], $_GET["id"], $database->GetVisitedStateForWish($_SESSION["idUser"], $_GET["id"]), 0);
            $database->updateAppartementRate($_GET["id"]);
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
     * permet d'ajouter une recette
     *
     * @return string
     */
    private function addAppartementAction() {
        $database = new Database();
        
        $firstPart = true;

        if (isset($_POST["appartementCreation1"]))
        {
            $firstPart = false;
            $firstPartError = false;
            $_SESSION["appartement"] = array();
            $_SESSION["appartement"]["recName"] = "";
            $_SESSION["appartement"]["recCategory"] = "";
            $_SESSION["appartement"]["recPrepTime"] = -1;
            $_SESSION["appartement"]["recDifficulty"] = -1;
            $_SESSION["appartement"]["recDescription"] = "";
            
            
            if (array_key_exists("recName", $_POST) && trim($_POST["recName"]) != "" && strlen($_POST["recName"]) <= 100 && strlen($_POST["recName"]) > 1) // 2 charactère minimum
            {
                $_SESSION["appartement"]["recName"] = $_POST["recName"];
            }
            else
            {
                $firstPart = true;
                $firstPartError = true;
            }

            if (array_key_exists("recCategory", $_POST) && trim($_POST["recCategory"]) != "" && strlen($_POST["recCategory"]) <= 100 && strlen($_POST["recCategory"]) > 4) // 5 charactère minimum
            {
                $_SESSION["appartement"]["recCategory"] = $_POST["recCategory"];
            }
            else
            {
                $firstPart = true;
                $firstPartError = true;
            }

            if (array_key_exists("recPrepTime", $_POST) && trim($_POST["recPrepTime"]) != "" && (int)$_POST["recPrepTime"] >= 10)
            {
                $_SESSION["appartement"]["recPrepTime"] = $_POST["recPrepTime"];
            }
            else
            {
                $firstPart = true;
                $firstPartError = true;
            }

            if (array_key_exists("recDifficulty", $_POST) && trim($_POST["recDifficulty"]) != "" && (int)$_POST["recDifficulty"] >= 1 && (int)$_POST["recDifficulty"] <= 5)
            {
                $_SESSION["appartement"]["recDifficulty"] = $_POST["recDifficulty"];
            }
            else
            {
                $firstPart = true;
                $firstPartError = true;
            }

            if (array_key_exists("recDescription", $_POST) && trim($_POST["recDescription"]) != "" && strlen($_POST["recDescription"]) <= 255 && strlen($_POST["recDescription"]) > 4) // 5 charactère minimum
            {
                $_SESSION["appartement"]["recDescription"] = $_POST["recDescription"];
            }
            else
            {
                $firstPart = true;
                $firstPartError = true;
            }
            
            
        }

        if (isset($_POST["appartementCreation2"]))
        {
            $firstPart = false;
            $secondPartError = false;
            $appartement = array();

            $appartement["idUser"] = $_SESSION["idUser"];
            $appartement["recName"] = $_SESSION["appartement"]["recName"];
            $appartement["recCategory"] = $_SESSION["appartement"]["recCategory"];
            $appartement["recPrepTime"] = (int)$_SESSION["appartement"]["recPrepTime"];
            $appartement["recDifficulty"] = (int)$_SESSION["appartement"]["recDifficulty"];
            $appartement["recDescription"] = $_SESSION["appartement"]["recDescription"];

            $ingredients = "";

            if (isset($_POST["numberOfIngredients"]))
            {
                $numberOfIngredients = $_POST["numberOfIngredients"];

                for ($i = 0; $i < $numberOfIngredients; $i++)
                {
                    if (isset($_POST["nbrIngredient" . ($i + 1)]) && trim($_POST["nbrIngredient" . ($i + 1)]) != "" && isset($_POST["ingredient" . ($i + 1)]) && strlen(trim($_POST["ingredient" . ($i + 1)])) >= 1)
                    {
                        $ingredients .= $_POST["nbrIngredient" . ($i + 1)] . ' x '; // TODO : [pas obligatoire] on pourrait faire une modif ici (mais il faudrait ajouter un champ au form (unity) et faire un if pour savoir quelle unitée est séléctionnée et faire une autre table ? => bof....)
                    }
                    else
                    {
                        continue;
                    }

                    if (isset($_POST["ingredient" . ($i + 1)]) && trim($_POST["ingredient" . ($i + 1)]) != "")
                    {
                        $ingredients .= $_POST["ingredient" . ($i + 1)];

                        if ($i != $numberOfIngredients - 1)
                        {
                            $ingredients .= ',';
                        }
                    }
                }

                if (trim($ingredients) == "" || empty($ingredients) || strlen($ingredients) > 255 || strlen($ingredients) < 5) // "1 x n" 5 charactères minimum
                {
                    $secondPartError = true;
                }
            }
            
            $appartement["recIngredientList"] = $ingredients;


            $preparationStep = "";

            if (isset($_POST["numberOfstep"]))
            {
                $numberOfstep = $_POST["numberOfstep"];

                for ($i = 0; $i < $numberOfstep; $i++)
                {

                    if (isset($_POST["step" . ($i + 1)]))
                    {
                        $preparationStep .= $_POST["step" . ($i + 1)];

                        if ($i != $numberOfstep - 1)
                        {
                            $preparationStep .= ',';
                        }
                    }
                }

                if (trim($preparationStep) == "" || empty($preparationStep) || strlen($preparationStep) > 255 || strlen($preparationStep) < 5) // 5 aussi comme minimum (c'est arbitraire)
                {
                    $secondPartError = true;
                }
            }
            
            $appartement["recPreparation"] = $preparationStep;
            
            $date = $database->getDate();
            $appartement["recDate"] = $date["currentTime"];
            $appartement["recImage"] = "defaultAppartementPicture.jpg";

            if (!$secondPartError)
            {
                $database->insertAppartement($appartement); // insertion de la recette dans la base de donnée
            }

            $appartement = $database->getLastAppartement(); //get la recette pour la page edit (où l'on peut ajouter une image) (on a besoin de l'id)

            if (!$secondPartError)
            {
                $view = file_get_contents('view/page/restrictedPages/manageAppartement/editAppartement.php');
            }
            else
            {
                $view = file_get_contents('view/page/restrictedPages/manageAppartement/addAppartement.php');
            }
        }
        else
        {
            $view = file_get_contents('view/page/restrictedPages/manageAppartement/addAppartement.php');
        }
        

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * permet de modifier une recette
     *
     * @return string
     */
    private function editAppartementAction() {
        $database = new Database();

        $appartement = $database->getOneAppartement($_GET["id"]);

        $imageEmpty = false;

        if (array_key_exists("fileUpdate", $_POST)) // modification de l'image
        {
            if(!empty($_FILES["image"]["name"]) && $this->extensionOk($_FILES["image"]["name"])) // TODO : si le temps le permet : faire de meilleures vérifications (ex: nom de fichier trop long... )
            {
                if ($appartement["recImage"] != "defaultAppartementPicture.jpg" && file_exists("resources/image/Appartements/" . $appartement["recImage"]))
                {
                    unlink("resources/image/Appartements/" . $appartement["recImage"]); // suppression de l'ancienne image
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
                $appartement["recImage"] = $imgName;
                
                $database->editAppartement($appartement); // modification du nom dans la database
            }
            else
            {
                $imageEmpty = true;
            }
        }

        $view = file_get_contents('view/page/restrictedPages/manageAppartement/editAppartement.php');

        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        return $content;
    }

    /**
     * permet de supprimer une recette
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
     * permet de rendre l'index de départ rond par rapport au nombre de page et au nombre de recette par page <etc class=""></etc>
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
                if ($appartementNumber%$lengthAppartement == 0) // s'il y a pil le meme nombre de recette
                {
                    $startIndex = $appartementNumber - ($lengthAppartement - $appartementNumber%$lengthAppartement);
                }
                else // s'il y a plus de recette
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
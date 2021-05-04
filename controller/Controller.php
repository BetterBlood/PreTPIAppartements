<?php
/**
 * Auteur : Cindy Hardegger, Jeremiah Steiner
 * Date: 22.01.2019, 27.12.2020
 * Contrôleur principal
 */

abstract class Controller { // QUESTION : comment je protège les page de log ?

    protected $databasePath;

    /**
     * constructeur de Controller (initialise le $databasePath)
     */
    public function __construct() {
        $this->databasePath = "model/Database.php";
    }

    /**
     * Méthode permettant d'appeler l'action
     *
     * @return mixed
     */
    public function display() 
    {
        $page = $_GET['action'] . "Display";

        $this->$page();
    }

    /**
     * permet de vérifier que l'extension du fichier passé en param est une image (gif, png ou jpg)
     *
     * @param string $imageName
     * @return bool si $imageName a une extension correspondante
     */
    protected function extensionOk($imageName)
    {
        $extensionIsOk = false;
        $ext = pathinfo($imageName, PATHINFO_EXTENSION);

        switch ($ext)
        {
            case "PNG":
            case "png":
            case "JPG":
            case "jpg":
            case "GIF":
            case "gif":
                //error_log("extension ok " . $ext . ", MIME : " . mime_content_type("resources/image/Users/" . $imageName) . "\r", 3, "data/Logs/TMP/debug.log");
                $extensionIsOk = true; // remplacer en return true; ?
                break;
                
            default:
                //error_log("Fail extension " . $ext . ", MIME : " . mime_content_type($imageName) . "\r", 3, "data/Logs/TMP/debug.log");
                $extensionIsOk = false; // remplacer en return false; ?
                break;
        }

        return $extensionIsOk;
    }

    protected function mimeOk($path, $extension)
    {
        $ext = pathinfo($extension, PATHINFO_EXTENSION);
        

        $mimeType = preg_split("/\//", mime_content_type($_FILES["image"]["tmp_name"]))[1];
        //error_log("mime : " . $mimeType . "\r", 3, "data/Logs/TMP/debug.log"); // DEBUG
        $return = false;

        switch($mimeType)
        {
            case "png":
                if ($ext == "PNG" || $ext == "png")
                {
                    $return = true;
                }
                break;

            case "gif":
                if ($ext == "gif" || $ext == "GIF")
                {
                    $return = true;
                }
                break;

            case "jpeg":
                if ($ext == "jpg" || $ext == "JPG")
                {
                    $return = true;
                }
                break;

            default :
                break;
        }

        return $return;
    }
    
}


<?php
    if (!array_key_exists("isConnected", $_SESSION) || !$_SESSION["isConnected"])
    {
        header("Location: index.php?controller=user&action=loginForm");
    }
?>

<div class="text-white">
    <h2>Page de modification de recette</h2>

    <div class="bg-light">
        <?php
            //var_dump($_SESSION);
            //var_dump($_POST);
            //var_dump($appartement);
            //var_dump($date);
        ?>
    </div>

    <?php
        $imageAppartementLink = '"resources/image/Appartements/' . htmlspecialchars($appartement['recImage']) . '"';
        echo '<img style="width:50%;" src=' . $imageAppartementLink . ' alt="image de profile">';
        
        echo '<form action="index.php?controller=appartement&action=editAppartement&id=' . $appartement["idAppartement"] . '" method="post" enctype="multipart/form-data">';
            ?>
            <input type="text" id="fileUpdate" name="fileUpdate" style="display: none;" value="true">
            <div class="form-group">
                <p>
                    <label for="image">Fichier à télécharger</label>
                    <input type="file" name="image" id="image" />
                    <input class="btn btn-primary mb-2" type="submit" value="Modifier" />
                    <?php
                    if (isset($imageEmpty) && $imageEmpty)
                    {
                        echo 'l\'image séléctionnée n\'est pas valide';
                    }
                    ?>
                </p>
            </div>
        </form>
    
    

</div>
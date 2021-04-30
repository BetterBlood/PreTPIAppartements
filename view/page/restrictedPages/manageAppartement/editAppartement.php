

<?php
    if (!array_key_exists("isConnected", $_SESSION) || !$_SESSION["isConnected"])
    {
        header("Location: index.php?controller=user&action=loginForm");
    }
?>

<div class="text-white">
    <h2>Page de modification d'appartement</h2>

    <div class="bg-light">
        <?php
            //var_dump($_SESSION);
            //var_dump($_POST);
            //var_dump($appartement);
            //var_dump($date);
        ?>
    </div>

    <?php
        $imageAppartementLink = '"resources/image/Appartements/' . htmlspecialchars($appartement['appImage']) . '"';
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


        <form action="index.php?controller=appartement&action=editAppartement&id=<?php  echo $appartement["idAppartement"]; ?>" method="post">
        <!-- je pense qu'on va faire pointer dirrectement vers la page de modification en métant un champs spécifique dans le form pour dire qu'on vient en création -->

        <input type="text" id="appartementEdit" name="appartementEdit" style="display: none;" value="true">

        <div class="form-row">
            <div class="col-md-4 mb-3 pt-n2 pb-n2">
                <label for="appName">Nom de l'appartement</label>
                <?php
                    echo '<input type="text" class="form-control" name="appName" id="appName" placeholder="nom d\'appartement" value="' . $appartement["appName"] . '">';
                ?>
            </div>

            <div class="col-md-3 mb-3 pt-n2 pb-n2">
                <label for="appCategory">Catégorie</label>
                <?php
                    echo '<input type="text" class="form-control" name="appCategory" id="appCategory" placeholder="Appartement" value="' . $appartement["appCategory"] . '">';
                ?>
            </div>

            <div class="form-row col-md-4 mb-3 pt-n2 pb-n2">
                <div class="mx-auto col-md-9">
                    <label for="appSurface">Surface</label>
                    <?php
                        echo '<input type="number" class="form-control" style="width:fit-content;" name="appSurface" id="appSurface" placeholder="[mettre carré]" min="10" value="' . $appartement["appSurface"] . '">';
                    ?>
                </div>

                <div class="mx-auto col-md-3">
                    <label for="appPrix">Prix</label>
                    <?php
                        echo '<input type="number" class="form-control" style="width:fit-content;" name="appPrix" id="appPrix" placeholder="800" min="10" max="99999" value="' . $appartement["appPrix"] . '">';
                    ?>
                </div>
            </div>
        </div>
        
        <label for="appDescription">Description</label>
        <?php
            echo '<textarea type="text" class="form-control" name="appDescription" id="appDescription" rows="4" cols="50" placeholder="Un appartement est une unité d\'habitation, comportant un certain nombre de pièces et qui n\'occupe qu\'une partie d\'un immeuble, situé généralement dans une ville. Il est souvent à usage d\'habitation.">' . $appartement["appDescription"] . '</textarea>';
        ?>
        
        <div class="d-flex">
            <div class="mx-auto mt-3 mb-3">
                <input class="btn btn-primary" type="submit" value="Enregistrer les modifications">
            </div>
        </div>
    
    </form>
    
    

</div>
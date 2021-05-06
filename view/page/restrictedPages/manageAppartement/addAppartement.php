
<?php
    if (!array_key_exists("isConnected", $_SESSION) || !$_SESSION["isConnected"])
    {
        header("Location: index.php?controller=user&action=loginForm");
    }
    else if (!isset($appartement))
    {
        $appartement = array();
        $appartement["appName"] = "";
        $appartement["appCategory"] = "";
        $appartement["appSurface"] = "";
        $appartement["appPrix"] = "";
        $appartement["appDescription"] = "";
        $appartement["idUser"] = "";
    }

    // DEBUG
    //var_dump($appartement);
    //var_dump($_SESSION["appartement"])
?>

<div class="text-white">
    <h2>Page d'ajout d'appartement</h2>
    
    <?php
        if (isset($firstPartError) && $firstPartError)
        {
            echo '<p class="bg-danger rounded">il semblerait qu\'une erreur de remplissage soit arrivée, veuiller remplir à nouveau le formulaire</p>';
        }
    ?>

    <form action="index.php?controller=appartement&action=addAppartement" method="post">
        <!-- je pense qu'on va faire pointer dirrectement vers la page de modification en métant un champs spécifique dans le form pour dire qu'on vient en création -->

        <input type="text" id="appartementCreation1" name="appartementCreation1" style="display: none;" value="true">

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
                echo '<select id="appCategory" name="appCategory" class="form-control" >';
                    echo '';
                    
                    echo '<option value="-1" ';

                    if (array_key_exists("catName", $categories) && $categories["catName"] == "-1")
                    {
                        
                        echo 'selected';
                    }
                    
                    echo '>aucun</option>';

                    foreach ($categories as $category) 
                    {
                        echo '<option value="' . $category["idCategory"] . '"';

                        if (array_key_exists("catName", $categories) && $categories["catName"] == $category["idCategory"])
                        {
                            echo 'selected';
                        }
                        
                        echo '>' . $category["catName"] . '</option>';
                    }
                ?>
                </select>
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
                <input class="btn btn-primary" type="submit" value="valider cette étape">
            </div>
        </div>
    
    </form>
</div>
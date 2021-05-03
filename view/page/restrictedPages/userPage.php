<div class="container">

	<h2>
		<?php
			// redirection si l'utilisateur n'existe pas
			if (array_key_exists("isConnected", $_SESSION) && $_SESSION["isConnected"] && array_key_exists("usePseudo", $userProfile))
			{
                echo 'Page de profile de : ' . $userProfile['usePseudo'];
                $imageProfilLink = '"resources/image/Users/' . htmlspecialchars($userProfile['useImage']) . '"';
			}
			else 
			{
				header("Location: index.php?controller=user&action=loginForm");
			}
            
            
            //var_dump($_FILES);
            //var_dump($size);
		?>
    </h2>

    <?php
        if (array_key_exists("isConnected", $_SESSION) && $_SESSION["isConnected"] && array_key_exists("usePseudo", $userProfile) && isset($selfPage) && $selfPage) 
        {
            //echo '<p class="text-white">DEBUG accès en modification ! </p>'; // DEBUG

            if (isset($modificationDone) && $modificationDone)
            {
                echo '<p class="text-white">modifications efféctuées </p>'; // DEBUG
                //sleep (3); // mmmm bof ptetre faire une page à part si on veut que cela s'affiche qu'une foi
                //var_dump($_POST); // DEBUG
            }
        }
        
    ?>
    
    <div class="text-white">
        <?php
        echo '<img style="width:100px;" src=' . $imageProfilLink . ' alt="image de profile">';
        
        if (isset($selfPage) && $selfPage) // page de l'utilisateur en accès propriétaire 
        {

            echo '<form action="index.php?controller=user&action=profile&pic=true&idUser=' . $userProfile["idUser"] . '" method="post" enctype="multipart/form-data">';
                ?>
                <input type="text" id="fileUpdate" name="fileUpdate" style="display: none;" value="true">
                <div class="form-group">
                    <p>
                        <label for="image">Fichier à télécharger</label>
                        <input type="file" name="image" id="image" />
                        <input class="btn btn-primary mb-2" type="submit" value="Modifier" />
                        <?php
                        if ((isset($imageEmpty) && $imageEmpty) || $errorPngFile)
                        {
                            echo '<strong class="text-danger">l\'image séléctionnée n\'est pas valide, ou trop volumineuse.</strong>';
                        }

                        // DEBUG
                        // var_dump(isset($imageEmpty));
                        // var_dump($imageEmpty);
                        //var_dump($errorPngFile);
                        //var_dump($_POST);
                        ?>
                    </p>
                </div>
            </form>

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-info" style="height: fit-content" data-toggle="modal" data-target="#modifPassword">modifier le mot de passe</button>
            
            <?php 
                if (isset($passwordModifFailed) && $passwordModifFailed) // message d'erreur
                {
                    ?>
                        <p>un problème est surevenu lors de la modification du mot de passe</p>
                    <?php
                }
            ?>
            
            <!-- Modal de modification de mot de passe -->
            <div class="modal fade" id="modifPassword" tabindex="-1" role="dialog" aria-labelledby="modifPasswordForm" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content bg-secondary">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Modifier le Mot de Passe</h5>
                            
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <?php

                            echo '<form action="index.php?controller=user&action=profile&idUser=' . $userProfile["idUser"] . '" method="POST">';
                            ?>
                            <input type="text" id="modifPasswordForm" name="modifPasswordForm" style="display: none;" value="true">
                            <div class="modal-body">
                                
                                <div class="form-group col-md-4 mb-3 pt-n2 pb-n2">
                                    <label for="usePassword">mot de passe</label>
                                    <?php
                                        echo '<input type="password" class="form-control" name="usePassword" id="usePassword" placeholder="Amanda17" ' . 'value="" ';
                                        if (!$selfPage)
                                        {
                                            echo 'disabled="disabled"';
                                        }
                                        echo '>';
                                    ?>
                                </div>

                                <div class="form-group col-md-4 mb-3">
                                    <label for="confirmePassword">confirmation</label>
                                    <?php
                                        echo '<input type="password" class="form-control" name="confirmePassword" id="confirmePassword" placeholder="Amanda17" ' . 'value="" ';
                                        if (!$selfPage)
                                        {
                                            echo 'disabled="disabled"';
                                        }
                                        echo '>';
                                    ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Abandonner</button>
                                <button type="submit" class="btn btn-success">Enregistrer le mot de passe</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php
        }

        if (isset($selfPage) && $selfPage)
        {
            echo '<form action="index.php?controller=user&action=profile&idUser=' . $userProfile["idUser"] . '" method="post">';
        }

        ?>

        <div class="form-row" style="height: fit-content">
            <div class="form-group col-md-4 mb-3">
                <label for="pseudo">Pseudo</label>
                <?php
                    echo '<input type="text" class="form-control" name="pseudo" id="pseudo" placeholder="' . $userProfile["usePseudo"] . '" ' . 'value="' . $userProfile["usePseudo"] . '" ';
                    if (!$selfPage)
                    {
                        echo 'disabled="disabled"';
                    }
                    echo '>';
                ?>
            </div>

            <div class="form-group col-md-4 mb-3">
                <label for="useFirstname">Prénom</label>
                <?php
                    echo '<input type="text" class="form-control" name="useFirstname" id="useFirstname" placeholder="' . $userProfile["useFirstname"] . '" ' . 'value="' . $userProfile["useFirstname"] . '" ';
                    if (!$selfPage)
                    {
                        echo 'disabled="disabled"';
                    }
                    echo '>';
                ?>
            </div>

            <div class="form-group col-md-4 mb-3">
                <label for="useName">Nom</label>
                <?php
                    echo '<input type="text" class="form-control" name="useName" id="useName" placeholder="' . $userProfile["useName"] . '" ' . 'value="' . $userProfile["useName"] . '" ';
                    if (!$selfPage)
                    {
                        echo 'disabled="disabled"';
                    }
                    echo '>';
                ?>
            </div>
            
        </div>


        <div class="form-row" style="height: fit-content">
            
            <div class="form-group col-md-4 mb-3">
                <label for="mail">Mail</label>
                <?php
                    echo '<input type="email" class="form-control" name="mail" id="mail" placeholder="' . $userProfile["useMail"] . '" ' . 'value="' . $userProfile["useMail"] . '" ';
                    if (!$selfPage)
                    {
                        echo 'disabled="disabled"';
                    }
                    echo '>';

                ?>
            </div>

            <div class="form-group col-md-4 mb-3">
                <label for="phone">Téléphone</label>
                <?php
                    echo '<input type="tel" class="form-control" name="phone" id="phone" placeholder="' . $userProfile["usePhone"] . '" ' . 'value="' . $userProfile["usePhone"] . '" ';
                    if (!$selfPage)
                    {
                        echo 'disabled="disabled"';
                    }
                    echo '>';
                ?>
            </div>

            <div class="form-group col-md-4 mb-3">
                <label for="phone">Thème</label>
                <?php
                echo '<select id="profilePref" name="profilePref" class="form-control" ';
                    if (!$selfPage)
                    {
                        echo 'disabled="disabled"';
                    }
                    echo '>';
                    
                    echo '<option value="-1" ';

                    if (array_key_exists("useProfilePref", $userProfile) && $userProfile["useProfilePref"] == "-1")
                    {
                        
                        echo 'selected';
                    }
                    
                    echo '>aucun</option>';

                    foreach ($profiles as $profile) 
                    {
                        echo '<option value="' . $profile["idProfile"] . '"';

                        if (array_key_exists("useProfilePref", $userProfile) && $userProfile["useProfilePref"] == $profile["idProfile"])
                        {
                            echo 'selected';
                        }
                        
                        echo '>' . $profile["proName"] . '</option>';
                    }
                ?>
                </select>
            </div>

        </div>

        <?php
            if (isset($selfPage) && $selfPage)
            {
                ?>
                <div class="pull-right">
                    <button type="submit" class="btn btn-primary mb-2">Enregistrer les modifications</button>
                </div>

                <?php
                echo '</form>';
            }
        ?>
    </div>

    <div class="d-flex">
        <div>
            <h2>Liste des Appartements mis en vente par vous-même :</h2>
        </div>
        
        <div class="ml-auto mb-2">
            <?php
                if(isset($_SESSION['isConnected']) && $_SESSION["isConnected"] && isset($selfPage) && $selfPage)
                {
                    ?>
                        <a class="btn btn-success" href="index.php?controller=appartement&action=addAppartement">ajouter un appartement</a>
                    <?php
                }
            ?>
        </div>
    </div>

    <div class="row">
        <table class="table table-striped table-dark">
        <tr>
            <th>nom</th> 
            <th>catégorie</th>
		    <th>Surface</th>
            <th>Note</th>
            <th>Prix</th>
            <th class="text-center">auteur</th>
            <th colspan="4" class="text-center">détail</th>
        </tr>
        <?php
        // pour le tableau : "table table-striped"
            // Affichage de chaque client
            
            //var_dump($_SESSION);
            $startIndex = 0;
            if (array_key_exists("start", $_GET))
            {
                $startIndex = $_GET["start"];
            }

            foreach ($appartements as $appartement) 
            {
                $user = $database->getOneUserById($appartement["idUser"]);

                echo '<tr>';
                    echo '<td><a class="text-white" href="index.php?controller=appartement&action=detail&id=' . htmlspecialchars($appartement['idAppartement']) . '">' . htmlspecialchars($appartement['appName']) . '</a></td>';
                    echo '<td>' . htmlspecialchars($appartement['appCategory']) . '</td>';
                    echo '<td>' . htmlspecialchars($appartement['appSurface']) . ' m<sup>2</sup></td>';
                    
                    echo '<td>' . htmlspecialchars($appartement['appRate']) . '</td>';

                    echo '<td>' . htmlspecialchars($appartement['appPrix']) . ' CHF</td>';
                    echo '<td class="text-center">' . $user["usePseudo"] . '</td>';

                    if (array_key_exists("id", $_GET) && $_GET["id"] == $appartement["idAppartement"]) // affiche/masque les détails d'un appartement
                    {
                        echo '<td class="icon-column"><a href="index.php?controller=user&action=profile&idUser=' . $appartement["idUser"] . '&start=' . $startIndex . '"><div class="bg-iconLoupe-reverse"></div></a></td>';
                        echo '<td class="icon-column">';
                        if (isset($selfPage) && $selfPage)
                        {
                            echo '<a href="index.php?controller=appartement&action=editAppartement&id=' . htmlspecialchars($appartement['idAppartement']) . '"><div class="bg-iconPencil"></div></a>';
                        }
                        echo '</td>';

                        echo '<td class="icon-column">';
                        if (isset($selfPage) && $selfPage)
                        {
                            echo '<a onclick="return confirm(\'Voulez-vous vraiment supprimer définitivement cet appartement ?\')" href="index.php?controller=appartement&action=deleteAppartement&id=' . htmlspecialchars($appartement['idAppartement']) . '"><div class="bg-iconTrash"></div></a>';
                        }
                        echo '</td>';

                        echo '<td class="icon-column">';
                        if (isset($selfPage) && $selfPage)
                        {
                            if ($appartement["appVisibility"])
                            {
                                echo '<a href="index.php?controller=user&action=showHideAppartement&idUser=' . $_SESSION["idUser"] . '&id=' . htmlspecialchars($appartement['idAppartement']) . '&showHide=0"><img src="resources/image/icone/visibleIcon.png" alt="hide house icon"></a>';
                            }
                            else
                            {
                                echo '<a href="index.php?controller=user&action=showHideAppartement&idUser=' . $_SESSION["idUser"] . '&id=' . htmlspecialchars($appartement['idAppartement']) . '&showHide=1"><img src="resources/image/icone/invisibleIcon.png" alt="show house icon"></a>';
                            }
                        }
                        echo '</td>';
                    }
                    else 
                    {
                        echo '<td class="icon-column"><a href="index.php?controller=user&action=profile&idUser=' . $appartement["idUser"] . '&id=' . htmlspecialchars($appartement['idAppartement']) . '&start=' . $startIndex . '"><div class="bg-iconLoupe"></div></a></td>';
                        echo '<td class="icon-column">';
                        if (isset($selfPage) && $selfPage)
                        {
                            echo '<a href="index.php?controller=appartement&action=editAppartement&id=' . htmlspecialchars($appartement['idAppartement']) . '"><div class="bg-iconPencil"></div></a>';
                        }
                        echo '</td>';

                        echo '<td class="icon-column">';
                        if (isset($selfPage) && $selfPage)
                        {
                            echo '<a onclick="return confirm(\'Voulez-vous vraiment supprimer définitivement cet appartement ?\')" href="index.php?controller=appartement&action=deleteAppartement&id=' . htmlspecialchars($appartement['idAppartement']) . '"><div class="bg-iconTrash"></div></a>';
                        }
                        echo '</td>';

                        echo '<td class="icon-column">';
                        if (isset($selfPage) && $selfPage)
                        {
                            if ($appartement["appVisibility"])
                            {
                                echo '<a href="index.php?controller=user&action=showHideAppartement&idUser=' . $_SESSION["idUser"] . '&id=' . htmlspecialchars($appartement['idAppartement']) . '&showHide=0"><img src="resources/image/icone/visibleIcon.png" alt="hide house icon"></a>';
                            }
                            else
                            {
                                echo '<a href="index.php?controller=user&action=showHideAppartement&idUser=' . $_SESSION["idUser"] . '&id=' . htmlspecialchars($appartement['idAppartement']) . '&showHide=1"><img src="resources/image/icone/invisibleIcon.png" alt="show house icon"></a>';
                            }
                        }
                        echo '</td>';
                    }

                echo '</tr>';

                if (array_key_exists("id", $_GET) && htmlspecialchars($_GET["id"]) == htmlspecialchars($appartement['idAppartement'])) // les premiers détails de l'appartement sont divisé en 3 parties
                {
                    echo '<tr>';

                        // première partie : concerne l'appartement lui-même
                        $imageLink = '"resources/image/Appartements/' . htmlspecialchars($appartement['appImage']) . '"';
                        echo '<td COLSPAN="4">';
                            echo '<div class="card" style="width: 35rem;">';
                                echo '<img src=' . $imageLink . ' class="card-img-top d-block w-100" alt="image de profile du créateur de l\'appartement">';
                                echo '<div class="card-body" style="color:black">';
                                    echo '<h5 class="card-title">Description :</h5>';
                                    echo '<p class="card-text">' . $appartement["appDescription"] . '</p>';

                                    if (array_key_exists("isConnected", $_SESSION) && $_SESSION["isConnected"])
                                    {
                                        echo '<a href="index.php?controller=appartement&action=detail&id=' . htmlspecialchars($appartement['idAppartement']) . '" class="btn btn-primary">Voir l\'appartement</a>';
                                    }
                                    else
                                    {
                                        echo '<a href="index.php?controller=user&action=loginForm" class="btn btn-primary">Voir l\'appartement</a>';

                                    }

                                echo '</div>';
                            echo '</div>';
                        echo '</td>';

                        // seconde partie : les information secondaire de l'appartement (avec la note la difficultée et le temps de préparation)
                        echo '<td colspan="2">';
                            echo '<div class="card" style="width: 18rem;">';
                                echo '<div class="card-body" style="color:black">';
                                    echo '<h4 class="card-title">informations</h4>';

                                    if (array_key_exists("isConnected", $_SESSION) && $_SESSION["isConnected"])
                                    {
                                        echo '<a href="index.php?controller=appartement&action=detail&id=' . htmlspecialchars($appartement['idAppartement']) . '" class="btn btn-success">Noter l\'appartement</a>' . '</p>';
                                    }
                                    else
                                    {
                                        echo '<a href="index.php?controller=user&action=loginForm" class="btn btn-success">Login ?</a>';

                                    }

                                    
                                    echo '</p>';
                                echo '</div>';
                            echo '</div>';


                            //echo $user["usePseudo"];
                            //var_dump($user);
                        echo '</td>';


                        // troisième partie : contient les premières informations du l'utilisateur
                        $imageProfilLink = '"resources/image/Users/' . htmlspecialchars($user['useImage']) . '"';
                        //echo '<img class="d-block w-50" src=' . $imageProfilLink . ' alt="image de profile du créateur de l'appartement">';
                    
                        echo '<td colspan="4" style="width:100px">';
                            echo '<div class="card" style="width: 18rem;">';
                                echo '<img src=' . $imageProfilLink . ' class="card-img-top" alt="image de profile du créateur de l\'appartement">';
                                echo '<div class="card-body" style="color:black">';
                                    echo '<h5 class="card-title">' . $user["usePseudo"] . '</h5>';
                                    echo '<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>';
                                    if (array_key_exists("isConnected", $_SESSION) && $_SESSION["isConnected"])
                                    {
                                        echo '<a href="index.php?controller=user&action=profile&idUser=' . $user["idUser"] .  '" class="btn btn-warning">Voir l\'auteur</a>';
                                    }
                                    else 
                                    {
                                        echo '<a href="index.php?controller=user&action=loginForm" class="btn btn-warning">Login ?</a>';
                                    }
                                    
                                echo '</div>';
                            echo '</div>';


                            //echo $user["usePseudo"];
                            //var_dump($user);
                        echo '</td>';
                    echo '</tr>';
                }
            }

            $realStartIndex = 0;
            $lengthAppartement = 5;
            $appartementsNumber = 8;

            if (array_key_exists("appartementsPerPage", $_SESSION))
            {
                $lengthAppartement = $_SESSION["appartementsPerPage"];
            }

            if (array_key_exists("appartementsNumber", $_SESSION))
            {
                $appartementsNumber = $_SESSION["appartementsNumber"];
            }

            if ($startIndex - $lengthAppartement < 0)
            {
                $realStartIndex = 0;
            }
            else
            {
                $realStartIndex = $startIndex - $lengthAppartement;
            }
        ?>
        
        </table>

        
    </div>

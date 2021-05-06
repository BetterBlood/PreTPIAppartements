<div class="d-flex">
	<div>
		<h2>Appartements de la liste perso</h2>
	</div>
	
</div>
<div class="row">
	<table class="table table-striped table-dark table-hover">
	<tr>
		<th><a class="text-white" href="index.php?controller=appartement&action=wishlist&orderBy=nom&asc=<?php echo '' . $asc ? "false": "true"; ?>">Nom</a></th>
		<th><a class="text-white" href="index.php?controller=appartement&action=wishlist&orderBy=cat&asc=<?php echo '' . $asc ? "false": "true"; ?>">Catégorie</a></th>
		<th><a class="text-white" href="index.php?controller=appartement&action=wishlist&orderBy=sur&asc=<?php echo '' . $asc ? "false": "true"; ?>">Surface</a></th>
		<th><a class="text-white" href="index.php?controller=appartement&action=wishlist&orderBy=not&asc=<?php echo '' . $asc ? "false": "true"; ?>">Note</a></th>
		<th>Visité</th>
		<th><a class="text-white" href="index.php?controller=appartement&action=wishlist&orderBy=pri&asc=<?php echo '' . $asc ? "false": "true"; ?>">Prix</a></th>
		<th>Noté</th>
		<th class="text-center"><a class="text-white" href="index.php?controller=appartement&action=wishlist&orderBy=aut&asc=<?php echo '' . $asc ? "false": "true"; ?>">Auteur</a></th>
		<th colspan="2" class="text-center">Détail</th>
	</tr>
	<?php
		$startIndex = 0;
		if (array_key_exists("start", $_GET))
		{
			$startIndex = $_GET["start"];
		}

		foreach ($wishAppartements as $appartement) {
			$user = $database->getOneUserById($appartement["idUser"]);

			echo '<tr id="appList' . $appartement["idAppartement"] . '" ';
				if (!$appartement['appVisibility'])
				{
					echo 'class="text-danger"';
				}
				echo '>';

				echo '<td><a class="text-white" href="index.php?controller=appartement&action=detail&id=' . htmlspecialchars($appartement['idAppartement']) . '">' . htmlspecialchars($appartement['appName']) . '</a></td>';
				echo '<td>' . htmlspecialchars($appartement['catName']) . '</td>';
				echo '<td>' . htmlspecialchars($appartement['appSurface']) . ' m<sup>2</sup></td>';
				
				echo '<td>' . htmlspecialchars($appartement['appRate']) . '</td>';
				
				echo '<td>' . htmlspecialchars($appartement['appVisited']) . '</td>';
				echo '<td>' . htmlspecialchars($appartement['appPrix']) . ' CHF</td>';

                if (htmlspecialchars($appartement['appRated']) == 0)
                {
                    echo '<td><a class="btn btn-primary" href="index.php?controller=appartement&action=rate&id=' . htmlspecialchars($appartement['idAppartement']) . '">rate</a></td>';
                }
                else
                {
                    echo '<td><a class="btn btn-warning" href="index.php?controller=appartement&action=unrate&id=' . htmlspecialchars($appartement['idAppartement']) . '">unrate</a></td>';
                }
                

				echo '<td class="text-center">' . $user["usePseudo"] . '</td>';

				if (array_key_exists("id", $_GET) && $_GET["id"] == $appartement["idAppartement"]) // affiche/masque les détail d'un appartement
				{
					echo '<td><a data-toggle="tooltip" data-placement="top" title="Moins" href="index.php?controller=appartement&action=wishlist&start=' . $startIndex . '"><div class="bg-iconLoupe-reverse"></div></a></td>';
                    echo '<td><a onclick="return confirm(\'Voulez-vous vraiment retirer cet appartement de votre liste personnelle ?\')" href="index.php?controller=appartement&action=removeWish&page=wishlist&id=' . htmlspecialchars($appartement['idAppartement']) . '"><img src="resources/image/icone/removeHouse.png" alt="remove house icon"></a></td>';
				}
				else 
				{
					echo '<td><a data-toggle="tooltip" data-placement="top" title="Plus" href="index.php?controller=appartement&action=wishlist&id=' . htmlspecialchars($appartement['idAppartement']) . '&start=' . $startIndex . '#appList' . $appartement["idAppartement"] . '"><div class="bg-iconLoupe"></div></a></td>';
                    echo '<td><a onclick="return confirm(\'Voulez-vous vraiment retirer cet appartement de votre liste personnelle ?\')" href="index.php?controller=appartement&action=removeWish&page=wishlist&id=' . htmlspecialchars($appartement['idAppartement']) . '"><img src="resources/image/icone/removeHouse.png" alt="remove house icon"></a></td>';					
				}

			echo '</tr>';

			if (array_key_exists("id", $_GET) && htmlspecialchars($_GET["id"]) == htmlspecialchars($appartement['idAppartement'])) // les premiers détails de l'appartement sont divisé en 3 parties
			{
				echo '<tr>';

					// première partie : concerne l'appartement elle-même
					$imageLink = '"resources/image/Appartements/' . htmlspecialchars($appartement['appImage']) . '"';
					echo '<td COLSPAN="4">';
						echo '<div class="card" style="width: 35rem;">';
							echo '<img onload="reFocus()" src=' . $imageLink . ' class="card-img-top d-block w-100" alt="image de profile du créateur de l\'appartement">';
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
					//echo htmlspecialchars($appartement['recImage']);

					// seconde partie : les information secondaire de l\'appartement (avec la note la difficultée et le temps de préparation)
					echo '<td COLSPAN="3">';
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
				
					echo '<td COLSPAN="3" style="width:100px">';
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

	<div>
		<p class="text-danger">*ces appartements ne sont plus en vente/location</p>
	</div>

	<!-- cette div contient la pagination des appartements-->
	<div class="justify-content-right numPage" id="numPage"> 
		<ul class="pagination justify-content-center">
			<li class="page-item">
			<a class="page-link" href="index.php?controller=appartement&action=wishlist&start=0" aria-label="Previous">
				<span aria-hidden="true">&laquo;</span>
			</a>
			</li>
			<li><a class="page-link" <?php echo 'href="index.php?controller=appartement&action=wishlist&start=' . $realStartIndex . '"';?>><span aria-hidden="true"><</span></a></li>

			<?php
				if (array_key_exists("appartementsNumber", $_SESSION))
				{
					if ($appartementsNumber <= $lengthAppartement) // s'il n'y a qu'une seule page a afficher
					{
						echo '<li class="page-item active"><a class="page-link" href="#">' . '1' . '</a></li>';
					}
					else if ($appartementsNumber <= 2*$lengthAppartement)
					{
						if ($startIndex < $lengthAppartement)
						{
							echo '<li class="page-item active"><a class="page-link" href="#">' . '1' . '</a></li>';
							echo '<li class="page-item"><a class="page-link" href="index.php?controller=appartement&action=wishlist&start=' . ($startIndex + $lengthAppartement) . '">' . '2' . '</a></li>';
						}
						else
						{
							echo '<li class="page-item"><a class="page-link" href="index.php?controller=appartement&action=wishlist&start=0">' . '1' . '</a></li>';
							echo '<li class="page-item active"><a class="page-link" href="#">' . '2' . '</a></li>';
						}
					}
					else
					{
						if ($startIndex < $lengthAppartement) // première et deuxieme page
						{
							echo '<li class="page-item active"><a class="page-link" href="#">' . '1' . '</a></li>';
							echo '<li class="page-item"><a class="page-link" href="index.php?controller=appartement&action=wishlist&start=' . ($startIndex + $lengthAppartement) . '">' . '2' . '</a></li>';
						}
						else if ($startIndex >= $appartementsNumber - $lengthAppartement) // dernière et avant dernière page
						{
							echo '<li class="page-item"><a class="page-link" href="index.php?controller=appartement&action=wishlist&start=' . ($startIndex - $lengthAppartement) . '">' . (int)(($realStartIndex/$lengthAppartement) + 1) . '</a></li>';
							echo '<li class="page-item active"><a class="page-link" href="#">' . (int)(($realStartIndex/$lengthAppartement) + 2) . '</a></li>';
						}
						else
						{
							echo '<li class="page-item"><a class="page-link" href="index.php?controller=appartement&action=wishlist&start=' . $realStartIndex . '">' . ($realStartIndex/$lengthAppartement + 1) . '</a></li>';
							echo '<li class="page-item active"><a class="page-link" href="#">' . ($realStartIndex/$lengthAppartement + 2) . '</a></li>';
							echo '<li class="page-item"><a class="page-link" href="index.php?controller=appartement&action=wishlist&start=' . ($startIndex + $lengthAppartement) . '">' . ($realStartIndex/$lengthAppartement + 3) . '</a></li>';
						}
					}
				}
				else
				{
					if ($realStartIndex <= $lengthAppartement)
					{
						echo '<li class="page-item active"><a class="page-link" href="#">' . '1' . '</a></li>';
						if (sizeof($appartements) != 0)
						{
							echo '<li class="page-item"><a class="page-link" href="index.php?controller=appartement&action=wishlist&start=' . ($startIndex + $lengthAppartement) . '">' . '2' . '</a></li>';
						}
					}
					else
					{
						echo '<li class="page-item"><a class="page-link" href="index.php?controller=appartement&action=wishlist&start=' . $realStartIndex . '">' . ($realStartIndex/$lengthAppartement + 1) . '</a></li>';
						echo '<li class="page-item active"><a class="page-link" href="#">' . ($realStartIndex/$lengthAppartement + 2) . '</a></li>';
						echo '<li class="page-item"><a class="page-link" href="index.php?controller=appartement&action=wishlist&start=' . ($startIndex + $lengthAppartement) . '">' . ($realStartIndex/$lengthAppartement + 3) . '</a></li>';
					}
				}
			?>


			<li><a class="page-link" <?php echo 'href="index.php?controller=appartement&action=wishlist&start=' . ($startIndex + $lengthAppartement) . '"';?>><span aria-hidden="true">></span></a></li>
			<li class="page-item">
				<?php
				echo '<a class="page-link" href="index.php?controller=appartement&action=wishlist&start=' . PHP_INT_MAX . '" aria-label="Next">'; // (int)(($appartementsNumber / $lengthAppartement) + $lengthAppartement + 1) 
				?>
				<span aria-hidden="true">&raquo;</span>
			</a>
			</li>
		</ul>
	</div>
</div>

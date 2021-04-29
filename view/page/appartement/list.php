<div class="d-flex">
	<div>
		<h2>Liste des Appartements</h2>
	</div>
	
	<div class="ml-auto mb-2">
		<?php
			if(isset($_SESSION['isConnected']) && $_SESSION["isConnected"])
			{
				?>
					<a class="btn btn-success" href="index.php?controller=appartement&action=addAppartement">ajouter un appartement</a>
				<?php
				//var_dump($_SESSION);
			}
		?>
	</div>
</div>
<div class="row">
	<table class="table table-striped table-dark">
	<tr>
		<th>Nom</th>
		<th>Catégorie</th>
		<th>Surface</th>
		<th>Note</th>
		<th>Prix</th>
		<th class="text-center">Auteur</th>
		<th colspan="2" class="text-center">Détail</th>
	</tr>
	<?php
		$startIndex = 0;
		if (array_key_exists("start", $_GET))
		{
			$startIndex = $_GET["start"];
		}

		foreach ($appartements as $appartement) {
			$user = $database->getOneUserById($appartement["idUser"]);

			echo '<tr>';
				echo '<td><a class="text-white" href="index.php?controller=appartement&action=detail&id=' . htmlspecialchars($appartement['idAppartement']) . '">' . htmlspecialchars($appartement['appName']) . '</a></td>';
				echo '<td>' . htmlspecialchars($appartement['appCategory']) . '</td>';
				echo '<td>' . htmlspecialchars($appartement['appSurface']) . ' m<sup>2</sup></td>';
				if (isset($appartement["appRate"])) // TODO : modifier ça
				{
					echo '<td>' . htmlspecialchars($appartement['appRate']) . '</td>';
				}
				else
				{
					echo '<td>pas encore notée</td>';
				}
				echo '<td>' . htmlspecialchars($appartement['appPrix']) . ' CHF</td>';
				echo '<td class="text-center">' . $user["usePseudo"] . '</td>';

				if (array_key_exists("id", $_GET) && $_GET["id"] == $appartement["idAppartement"]) // affiche/masque les détail d'une recette
				{
					echo '<td><a href="index.php?controller=appartement&action=list&start=' . $startIndex . '"><div class="bg-iconLoupe-reverse"></div></a></td>';
					if (!$inWish)
					{
						echo '<td><a href="index.php?controller=appartement&action=addWish&id=' . $appartement["idAppartement"] .'&start=' . $startIndex . '"><img src="resources/image/icone/addIcon.png" alt="add-icon"></a></td>';
					}
					else
					{
						echo '<td><a onclick="return confirm(\'Voulez-vous vraiment retirer cet appartement de votre liste personnelle ?\')" href="index.php?controller=appartement&action=removeWish&id=' . $appartement["idAppartement"] .'&start=' . $startIndex . '"><img src="resources/image/icone/removeHouse.png" alt="add-icon"></a></td>';
					}
				}
				else 
				{
					echo '<td colspan="2"><a href="index.php?controller=appartement&action=list&id=' . htmlspecialchars($appartement['idAppartement']) . '&start=' . $startIndex . '"><div class="bg-iconLoupe"></div></a></td>';
				}

			echo '</tr>';

			if (array_key_exists("id", $_GET) && htmlspecialchars($_GET["id"]) == htmlspecialchars($appartement['idAppartement'])) // les premiers détails de la recette sont divisé en 3 parties
			{
				echo '<tr>';

					// première partie : concerne la recette elle-même
					$imageLink = '"resources/image/Appartements/' . htmlspecialchars($appartement['appImage']) . '"';
					echo '<td COLSPAN="4">';
						echo '<div class="card" style="width: 35rem;">';
							echo '<img src=' . $imageLink . ' class="card-img-top d-block w-100" alt="image de profile du créateur de la recette">';
							echo '<div class="card-body" style="color:black">';
								echo '<h5 class="card-title">Description :</h5>';
								echo '<p class="card-text">' . $appartement["appDescription"] . '</p>';

								if (array_key_exists("isConnected", $_SESSION) && $_SESSION["isConnected"])
								{
									echo '<a href="index.php?controller=appartement&action=detail&id=' . htmlspecialchars($appartement['idAppartement']) . '" class="btn btn-primary">Voir la recette</a>';
								}
								else
								{
									echo '<a href="index.php?controller=user&action=loginForm" class="btn btn-primary">Voir la recette</a>';

								}

							echo '</div>';
						echo '</div>';
					echo '</td>';
					//echo htmlspecialchars($appartement['recImage']);

					// seconde partie : les information secondaire de la recette (avec la note la difficultée et le temps de préparation)
					echo '<td COLSPAN="2">';
						echo '<div class="card" style="width: 18rem;">';
							echo '<div class="card-body" style="color:black">';
								echo '<h4 class="card-title">informations</h4>';

								if (array_key_exists("isConnected", $_SESSION) && $_SESSION["isConnected"])
								{
									echo '<a href="index.php?controller=appartement&action=detail&id=' . htmlspecialchars($appartement['idAppartement']) . '" class="btn btn-success">Noter la recette</a>' . '</p>';
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
					//echo '<img class="d-block w-50" src=' . $imageProfilLink . ' alt="image de profile du créateur de la recette">';
				
					echo '<td style="width:100px">';
						echo '<div class="card" style="width: 18rem;">';
							echo '<img src=' . $imageProfilLink . ' class="card-img-top" alt="image de profile du créateur de la recette">';
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
					echo '<td></td>';
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

	<!-- cette div contient la pagination des recettes-->
	<div class="justify-content-right numPage" id="numPage"> 
		<ul class="pagination justify-content-center">
			<li class="page-item">
			<a class="page-link" href="index.php?controller=appartement&action=list&start=0" aria-label="Previous">
				<span aria-hidden="true">&laquo;</span>
			</a>
			</li>
			<li><a class="page-link" <?php echo 'href="index.php?controller=appartement&action=list&start=' . $realStartIndex . '"';?>><span aria-hidden="true"><</span></a></li>

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
							echo '<li class="page-item"><a class="page-link" href="index.php?controller=appartement&action=list&start=' . ($startIndex + $lengthAppartement) . '">' . '2' . '</a></li>';
						}
						else
						{
							echo '<li class="page-item"><a class="page-link" href="index.php?controller=appartement&action=list&start=0">' . '1' . '</a></li>';
							echo '<li class="page-item active"><a class="page-link" href="#">' . '2' . '</a></li>';
						}
					}
					else
					{
						if ($startIndex < $lengthAppartement) // première et deuxieme page
						{
							echo '<li class="page-item active"><a class="page-link" href="#">' . '1' . '</a></li>';
							echo '<li class="page-item"><a class="page-link" href="index.php?controller=appartement&action=list&start=' . ($startIndex + $lengthAppartement) . '">' . '2' . '</a></li>';
						}
						else if ($startIndex >= $appartementsNumber - $lengthAppartement) // dernière et avant dernière page
						{
							echo '<li class="page-item"><a class="page-link" href="index.php?controller=appartement&action=list&start=' . ($startIndex - $lengthAppartement) . '">' . (int)(($realStartIndex/$lengthAppartement) + 1) . '</a></li>';
							echo '<li class="page-item active"><a class="page-link" href="#">' . (int)(($realStartIndex/$lengthAppartement) + 2) . '</a></li>';
						}
						else
						{
							echo '<li class="page-item"><a class="page-link" href="index.php?controller=appartement&action=list&start=' . $realStartIndex . '">' . ($realStartIndex/$lengthAppartement + 1) . '</a></li>';
							echo '<li class="page-item active"><a class="page-link" href="#">' . ($realStartIndex/$lengthAppartement + 2) . '</a></li>';
							echo '<li class="page-item"><a class="page-link" href="index.php?controller=appartement&action=list&start=' . ($startIndex + $lengthAppartement) . '">' . ($realStartIndex/$lengthAppartement + 3) . '</a></li>';
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
							echo '<li class="page-item"><a class="page-link" href="index.php?controller=appartement&action=list&start=' . ($startIndex + $lengthAppartement) . '">' . '2' . '</a></li>';
						}
					}
					else
					{
						echo '<li class="page-item"><a class="page-link" href="index.php?controller=appartement&action=list&start=' . $realStartIndex . '">' . ($realStartIndex/$lengthAppartement + 1) . '</a></li>';
						echo '<li class="page-item active"><a class="page-link" href="#">' . ($realStartIndex/$lengthAppartement + 2) . '</a></li>';
						echo '<li class="page-item"><a class="page-link" href="index.php?controller=appartement&action=list&start=' . ($startIndex + $lengthAppartement) . '">' . ($realStartIndex/$lengthAppartement + 3) . '</a></li>';
					}
				}
			?>


			<li><a class="page-link" <?php echo 'href="index.php?controller=appartement&action=list&start=' . ($startIndex + $lengthAppartement) . '"';?>><span aria-hidden="true">></span></a></li>
			<li class="page-item">
				<?php
				echo '<a class="page-link" href="index.php?controller=appartement&action=list&start=' . PHP_INT_MAX . '" aria-label="Next">'; // (int)(($appartementsNumber / $lengthAppartement) + $lengthAppartement + 1) 
				?>
				<span aria-hidden="true">&raquo;</span>
			</a>
			</li>
		</ul>
	</div>

	<div>
	<?php
		for($i = 0; $i < 9; $i++)
		{
			$outputTmp = "";
			
			for ($j = 0; $j < 9; $j++)
			{
				$outputTmp .= "&nbsp";
				$outputTmp .= "&nbsp";

				if ($i == $j || $i + $j == 8)
				{
					$outputTmp .= "x";
				}
				else 
				{
					$outputTmp .= "&nbsp";
					$outputTmp .= "&nbsp";
				}
			}
			echo $outputTmp;
			echo '<br>';
		}
	?>
	</div>
</div>

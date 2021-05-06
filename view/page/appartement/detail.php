<div class="container">

	<h2>
		<?php
			// redirection si l'appartement n'existe pas
			if (isset($appartement))
			{
				echo $appartement['appName'] . ', dans la catégorie : ' . $appartement["catName"];
			}
			else 
			{
				header("Location: index.php?controller=appartement&action=list");
			}
			
		?>
	</h2>

	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-white">
			<div class="pl-5" style="display:flex;">
				
			</div>
			<?php
				$imageLink = '"resources/image/Appartements/' . htmlspecialchars($appartement['appImage']) . '"';
				echo '<img class="d-block w-100" src=' . $imageLink . ' alt="image d\'illustration de l\'appartement">';
				echo '<p>Description : ' . $appartement['appDescription'] . '</p>';
			?>
			
		</div>

		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-white">
			<div>
				<div>
					<p>
						<?php
							echo 'nombre d\'évaluation positive : ' . $appartement["appRate"] . ' ';
							if ($alreadyRate)
							{
								echo '<a class="btn btn-warning" href="index.php?controller=appartement&action=detail&rate=1&id=' . htmlspecialchars($appartement["idAppartement"]) . '">unrate</a>';
							}
							else
							{
								echo '<a class="btn btn-primary" href="index.php?controller=appartement&action=detail&rate=0&id=' . htmlspecialchars($appartement["idAppartement"]) . '">rate</a>';
							}
						?>
					</p>
				</div>
				<div>
					<p>Surface : <?php echo htmlspecialchars($appartement["appSurface"]); ?> m<sup>2</sup></p>
				</div>
				<div>
					<p>Prix : <?php echo htmlspecialchars($appartement["appPrix"]); ?> CHF</p>
				</div>
			</div>
		</div>
	</div>

	<div class="bg-secondary badge-pill mt-5 mb-3 mx-auto " style="height:8px;width:50%;"></div>
	<div class="bg-secondary badge-pill mt-3 mb-3 mx-auto " style="height:8px;width:40%;"></div>
	<div class="bg-secondary badge-pill mt-3 mb-3 mx-auto " style="height:8px;width:25%;"></div>
	<div class="bg-secondary badge-pill mt-3 mb-5 mx-auto " style="height:8px;width:10%;"></div>
	
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-white">
			<p>Bien mis en vente/location par :</p>

			<?php
			$imageProfilLink = '"resources/image/Users/' . htmlspecialchars($appartementCreator['useImage']) . '"';
			echo '<div class="card" style="width: 18rem;">';
				echo '<img src=' . $imageProfilLink . ' class="card-img-top" alt="image de profile du créateur de l\'appartement">';
				echo '<div class="card-body" style="color:black">';
					echo '<h5 class="card-title">' . $appartementCreator["usePseudo"] . '</h5>';
					echo '<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card\'s content.</p>';
					if (array_key_exists("isConnected", $_SESSION) && $_SESSION["isConnected"])
					{
						echo '<a href="index.php?controller=user&action=profile&idUser=' . $appartementCreator["idUser"] . '" class="btn btn-warning">Voir l\'auteur</a>';
					}
					else 
					{
						echo '<a href="index.php?controller=user&action=loginForm" class="btn btn-warning">Login ?</a>';
					}
					
				echo '</div>';
			echo '</div>';

			?>
		</div>

		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-white">
			
		</div>
		<div class="text-white mb-5 pb-5">
			<a href="index.php?controller=appartement&action=list&id=<?php echo $appartement["idAppartement"]; //. '&start=';// ptetre retrouver le start index de l'image ?>">Retour à la liste des appartements</a>
		</div>
	</div>
	
</div>
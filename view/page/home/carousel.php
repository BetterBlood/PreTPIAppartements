<div class="container">

  <h2 class="h2">Home</h2>
    <div class="carousel-inner">
    <?php 
      echo "<div class='carousel-item ";
        if (array_key_exists('image',$_GET) && $_GET['image'] == 0)
        {
          echo "active";
        }
        echo "'>";

        echo '<span class="bg-success rounded-top d-block w-100 text-center mb-n3 pb-3"><h3>Dernier ajout !!!</h3></span>'; // bandeau vert en haut de l'image

        echo '<img src="resources//image//Appartements//' . $lastAppartement["appImage"] . '" class="d-block w-100" alt="image de la dernière recette ajoutée au site">'; // à modifier en fonction des dernières recettes etc
        
        echo '<div class="carousel-caption d-none d-md-block bg-dark rounded-pill">';
          echo '<a class="text-white" href="index.php?controller=appartement&action=detail&id=' . htmlspecialchars($lastAppartement['idAppartement']) . '"><h4>' . $lastAppartement["appName"] . '</h4></a>';
          echo '<p>ajouté le : [ ' . $lastAppartement["appDate"] . ' ]</p>';
        echo '</div>';
    ?>
      </div>

    <?php 
      echo "<div class='carousel-item ";
        if (array_key_exists('image',$_GET) && $_GET['image'] == 1)
        {
          echo "active";
        }
        echo "'>";

        echo '<span class="bg-danger rounded-top d-block w-100 text-center mb-n3 pb-3"><h3>Meilleure note !!!</h3></span>'; // bandeau rouge en haut de l'image
      
        echo '<img src="resources//image//Appartements//' . $bestAppartement["appImage"] . '" class="d-block w-100" alt="image de la recette avec la meilleure note">'; // <!-- à modifier en fonction des meilleures recettes etc -->
        echo '<div class="carousel-caption d-none d-md-block bg-dark rounded-pill">';
          echo '<a class="text-white" href="index.php?controller=appartement&action=detail&id=' . htmlspecialchars($bestAppartement['idAppartement']) . '"><h4>' . $bestAppartement["appName"] . '</h4></a>';
          echo '<p>ajouté le : [ ' . $bestAppartement["appDate"] . ' ]</p>';
        echo '</div>';
      
    ?>
      </div>

    <?php 
      echo "<div class='carousel-item ";
        if (array_key_exists('image',$_GET) && $_GET['image'] == 2)
        {
          echo "active";
        }
        echo "'>";

        echo '<span class="bg-warning rounded-top d-block w-100 text-center mb-n3 pb-3"><h3>Appartement le plus abordable</h3></span>'; // bandeau jaune en haut de l'image
        echo '<img src="resources//image//Appartements//' . $cheapestAppartement["appImage"] . '" class="d-block w-100" alt="image de l\'appartement le plus abordable">'; // <!-- à modifier en fonction des recettes les plus faciles etc -->
        
        echo '<div class="carousel-caption d-none d-md-block bg-dark rounded-pill">';
          echo '<div class="d-flex align-items-end flex-column" style="height: 40px;">';
            echo '<div class="p-2">';
              echo '<div style="position:absolute"><img  src="resources//image//icone//star.png" alt="étoile" width="150"></div>';
              echo '<div class="mt-5 pt-3 ml-4 pl-3 text-dark fit-content" style="position:relative"><strong>'. htmlspecialchars($cheapestAppartement['appPrix']) . ' CHF</strong></div>';
            echo '</div>';
          echo '</div>';

          echo '<a class="text-white" href="index.php?controller=appartement&action=detail&id=' . htmlspecialchars($cheapestAppartement['idAppartement']) . '"><h4>' . $cheapestAppartement["appName"] . '</h4></a>';
          echo '<p>ajouté le : [ ' . $cheapestAppartement["appDate"] . ' ]</p>';
          //echo '<div class="mt-auto"><img src="resources//image//icone//star.png" alt="étoile" width="150"></div>';

          

        echo '</div>';
        
    ?>
      </div>

    <?php 
      echo "<a class='carousel-control-prev' href='";
        if (array_key_exists('image',$_GET) && $_GET['image'] == 0)
        {
          echo "index.php?controller=home&action=index&image=2";
        }
        else if (array_key_exists('image',$_GET) && $_GET['image'] == 1)
        {
          echo "index.php?controller=home&action=index&image=0";
        }
        else if (array_key_exists('image',$_GET) && $_GET['image'] == 2)
        {
          echo "index.php?controller=home&action=index&image=1";
        }
        echo "' role='button' data-slide='prev'>";
    ?>
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      
      <?php 
        echo "<a class='carousel-control-next' href='";
          if (array_key_exists('image',$_GET) && $_GET['image'] == 0)
          {
            echo "index.php?controller=home&action=index&image=1";
          }
          else if (array_key_exists('image',$_GET) && $_GET['image'] == 1)
          {
            echo "index.php?controller=home&action=index&image=2";
          }
          else if (array_key_exists('image',$_GET) && $_GET['image'] == 2)
          {
            echo "index.php?controller=home&action=index&image=0";
          }
          echo "' role='button' data-slide='next'>";
      ?>
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>

        <ol class="carousel-indicators"> <!-- petit truc en bas -->
        <a href="index.php?controller=home&action=index&image=0">
          <?php 
            echo "<li "; // data-target='#carouselExampleCaptions' data-slide-to='0' 
            if (array_key_exists('image',$_GET) && $_GET['image'] == 0)
            {
              echo "class='active'";
            }
            echo "></li> ";
          ?>
        </a>
        <a href="index.php?controller=home&action=index&image=1">
          <?php 
            echo "<li "; // data-target='#carouselExampleCaptions' data-slide-to='1' 
            if (array_key_exists('image',$_GET) && $_GET['image'] == 1)
            {
              echo "class='active'";
            }
            echo "></li> ";
          ?>
        </a>
        <a href="index.php?controller=home&action=index&image=2">
          <?php 
            echo "<li "; // data-target='#carouselExampleCaptions' data-slide-to='2' 
            if (array_key_exists('image',$_GET) && $_GET['image'] == 2)
            {
              echo "class='active'";
            }
            echo "></li> ";
          ?>
        </a>
      </ol>
    </div>
  </div>
    <br><br>
    <div class="text-light">
      <h1>Bienvenue sur Swedish Tortilla</h1>
      <p>Ce site a pour but de recenser tous types de recettes. Nous vous invitions à toutes les essayer afin de les évaluer selon vos compétences en cuisine. Nous sommes tous des explorateurs des papilles gustatives.</p>
    </div>
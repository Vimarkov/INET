<!-- Partie serveur -->
<!-- graphiquespChart_indiscateurs.php -->
		<?php
			require("../../../Menu.php");
			require("../../Fonctions.php");
			
			include("../../../pChart/class/pDraw.class.php");
			include("../../../pChart/class/pImage.class.php");
			include("../../../pChart/class/pData.class.php");
			include("graphiquespChart_indicateurs.php");
		
//  			if (isset($_POST['Start']) && isset($_POST['End'])) {
//  				if ($_POST['Start'] && $_POST['End']) {
//  					//[195] - Graphique Productivit�
// 					echo "Il y a des valeurs pour les dates !! <br /> \n";
// 					echo "c'est du ".$_POST['Start']." au ".$_POST['End']."<br />\n";
					
// 					echo "Semaine date de d�part : ".getSemaine($_POST['Start'])."<br />\n";
// 					echo "Semaine date de fin : ".getSemaine($_POST['End'])."<br />\n";
//  				}
//  			}

 			//La page est g�n�r�e dans l'appel � la fonction graphique
			graphique_Productivite();
			
		?>

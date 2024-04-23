<!-- Partie serveur -->
<!-- graphiquespChart_indiscateurs.php -->
		<?php
		/**
		 *  Ce fichier réponds à l'appel de la page KPI/OTD
		 */
			require("../../../Menu.php");
			require("../../Fonctions.php");
			
			include("../../../pChart/class/pDraw.class.php");
			include("../../../pChart/class/pImage.class.php");
			include("../../../pChart/class/pData.class.php");
			include("graphiquespChart_indicateurs.php");
		
// 			if (isset($_POST['Cycle']))
// 				echo "isset cycles : YES<br />\n";
// 			else
// 				echo "isset cycles : NO<br />\n";
			
// 			if (isset($_POST['UniteDeTemps']))
// 				echo "isset Unité de temps : YES<br />\n";
// 			else
// 				echo "isset Unité de temps : NO<br />\n";
				
			
if (isset($_POST['client']) && isset($_POST['Cycle']) && isset($_POST['UniteDeTemps']) && isset($_POST['Mois']) && isset($_POST['Semaine']) && isset($_POST['Start']) && isset($_POST['End'])) {
				graphique_OTD(intval($_POST['client']), intval($_POST['Cycle']), intval($_POST['UniteDeTemps']), intval($_POST['Mois']), intval($_POST['Semaine']), $_POST['Start'], $_POST['End']);
 			}else {
 				//1 forcé pour la repésentation annuelle
 				graphique_OTD(0, intval('0'), 1, 0, 0, '', '');
 			}
			
		?>
		
<!-- Page Client au premier appel -->
<!DOCTYPE html>
<html>
	<head>
		<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
		<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
		<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
		<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">

		<script type="text/javascript" src="../../JS/jquery.min.js"></script>		
		<script src="../../JS/modernizr.js"></script>
		<script src="../../JS/js/jquery-1.4.3.min.js"></script>
		<script src="../../JS/js/jquery-ui-1.8.5.min.js"></script>
	</head>
	
	<body>
	<!-- 	 Ici le calcul est fait via php pour afficher le bon contenu dans la liste déroulante -->
	<?php
			//[194] - Préparation des requêtes SQL
			
			//[194] - Chargement de la liste des clients
			$req="SELECT DISTINCT sp_client.Id, sp_client.Libelle \n";
			$req.="FROM \n";
			$req.="sp_olwficheintervention, \n";
			$req.="sp_olwdossier, \n";
			$req.="sp_client \n"; 
			$req.="WHERE \n";
			$req.="sp_olwficheintervention.Id_Dossier = sp_olwdossier.Id \n";
			$req.="AND sp_olwdossier.Id_Client = sp_client.Id; \n";
			
			$clients=mysqli_query($bdd,$req);
			$nbClients=mysqli_num_rows($clients);
	
// 			echo "Client";
			echo "<form action=\"Indicateur_OTD.php\" method=\"POST\">";
// 			echo "<select name=\"client\">";
			
// 			while($row = mysqli_fetch_array($clients, mysqli_ASSOC))
// 				echo "<option value=\"{$row['Id']}\">{$row['Libelle']}</option>";
				
// 				echo "</select>";
				echo "<input type = \"submit\" value=\"Afficher\" />";
				echo "</form>";
	?>
	
	</body>
</html>
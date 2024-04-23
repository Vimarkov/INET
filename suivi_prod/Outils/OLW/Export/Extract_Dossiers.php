<!-- [197] - Extract des dossiers -->
<script type='text/javascript' src='../../../fonctions_javascripts.js'></script>
<!-- Partie serveur -->
		<?php
			require("../../../Menu.php");
			require("../../Fonctions.php");
			
			require "extracts.php";
		
			$_SESSION['filename'] = 'Extract_Dossiers';
			
			if (isset($_POST['Tout']) && $_POST['Tout'] == 'Extraire Tout') {
				extractDossiers();
?>
 			<script language='javascript''>
 				ouvre_popup("http://127.0.0.1/suivi_prod/Outils/AHDO/Export/Download_Extract.php");
			</script>
<?php
			}elseif (isset($_POST['client']) && $_POST['client'] > 0) {
					//[194] - Graphique OQD
					extractDossiers($_POST['client']);
 ?>
 			<script language='javascript''>
 					ouvre_popup("http://127.0.0.1/suivi_prod/Outils/AHDO/Export/Download_Extract.php");
			</script>
<?php 						
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
	
			echo "<form action=\"Extract_Dossiers.php\" method=\"POST\">";
			
			echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">";
			echo "<tr>";
			echo "<td width=\"4\"></td>";
			echo "<td class=\"TitrePage\">Extract dossiers</td>";
			echo "</tr>";
			echo "</table>";
			
			echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\" class=\"GeneralInfo\">";
			echo "<tr><td align='left'>";
			echo "&nbsp;&nbsp;&nbsp; <input class='Bouton' type = \"submit\" name=\"Tout\" value=\"Extraire Tout\" />";
			echo "<br />";
			echo "&nbsp;&nbsp;&nbsp; Client";
			echo "<select name=\"client\">";
			
			while($row = mysqli_fetch_array($clients, mysqli_ASSOC))
				echo "<option value=\"{$row['Id']}\">{$row['Libelle']}</option>";
				
				echo "</select>";
				echo "<input class='Bouton' type = \"submit\" value=\"Extraire\" />";
				echo "</td></tr>";
				echo "</table>";
				echo "</form>";
	?>
	
	</body>
</html>
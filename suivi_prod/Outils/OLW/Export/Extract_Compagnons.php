<!-- [197] - Extract des compagnons -->
<script type='text/javascript' src='../../../fonctions_javascripts.js'></script>
<!-- Partie serveur -->
<?php
		require "../../../Menu.php";
		require "../../Fonctions.php";
			
		require "extracts.php";
		
		$_SESSION['filename'] = 'Extract_Compagnons';
		extractCompagnons();
?>

 		<script language='javascript''>
 				ouvre_popup('http://127.0.0.1/suivi_prod/Outils/AHDO/Export/Download_Extract.php');
		</script>

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
		L'extract compagnons a été généré avec succès.
	</body>
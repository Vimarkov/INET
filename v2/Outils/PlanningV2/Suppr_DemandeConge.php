<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script language="javascript">
		function FermerEtRecharger(Menu,TDB,OngletTDB)
		{
			window.opener.location="Liste_DemandesSansAffectation.php?Menu="+Menu+"&TDB="+TDB+"&OngletTDB="+OngletTDB;
			window.close();
		}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
Ecrire_Code_JS_Init_Date();

	$requeteUpdate="UPDATE rh_personne_demandeabsence SET 
			Suppr=1,
			Id_Suppr=".$_SESSION['Id_Personne'].",
			DateSuppr='".date('Y-m-d')."'
			WHERE Id=".$_GET['Id']." ";

	$resultat=mysqli_query($bdd,$requeteUpdate);
	echo "<script>FermerEtRecharger('".$_GET['Menu']."',".$_GET['TDB'].",'".$_GET['OngletTDB']."');</script>";


?>
</body>
</html>
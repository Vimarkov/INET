<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="DemandeHS.js"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script language="javascript">
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(document.getElementById('commentaire').value==""){alert("Veuillez ajouter un commentaire.");return false;}
			}
			else{
				if(document.getElementById('commentaire').value==""){alert("Please add a comment.");return false;}

			}
			return true;
		}
		function FermerEtRecharger()
		{
			window.opener.location="Liste_Demande_Besoin.php";
			window.close();
		}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
Ecrire_Code_JS_Init_Date();

$bEnregistrement=false;

$requeteUpdate="UPDATE form_demandebesoin SET 
		Id_Valideur=".$_SESSION['Id_Personne'].",
		Date_Suppr='".date('Y-m-d')."',
		Suppr=1
		WHERE Id=".$_GET['Id']." ";

$resultat=mysqli_query($bdd,$requeteUpdate);
echo "<script>FermerEtRecharger();</script>";

?>

</body>
</html>
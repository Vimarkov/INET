<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");
?>

<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../CSS/Planning.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">
		function FermerEtRecharger()
		{
			opener.location.reload();
			opener.opener.opener.location='Tableau_De_Bord.php?Menu=15';
			window.close();
		}
	</script>
</head>

<?php
$req="UPDATE soda_question_exceptionprestation SET Suppr=1, Id_Suppr=".$_SESSION['Id_Personne'].", Date_Suppr='".date('Y-m-d')."' WHERE Id=".$_GET['Id']." ";
$resultUpd=mysqli_query($bdd,$req);

echo "<script>FermerEtRecharger();</script>";
?>
</body>
</html>
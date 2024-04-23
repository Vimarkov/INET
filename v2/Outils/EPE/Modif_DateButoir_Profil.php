<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
Ecrire_Code_JS_Init_Date();
?>

<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../JS/date.js"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script>
		function FermerEtRecharger(Page)
		{
			opener.location=Page;
			window.close();
		}
	</script>
</head>
<body>

<?php
if($_POST)
{
	if($_POST['dateButoir']<>""){
		$Req="UPDATE epe_personne_datebutoir SET DateButoir='".TrsfDate_($_POST['dateButoir'])."',DateReport='".TrsfDate_($_POST['dateReport'])."' WHERE Id=".$_POST['Id']." ";
		$Result=mysqli_query($bdd,$Req);
	}
	echo "<script>FermerEtRecharger('../Competences/Profil.php?Mode=Modif&Id_Personne=".$_POST['Id_Personne']."');</script>";
}

$req="SELECT Id, Id_Personne,DateButoir,DateReport,
TypeEntretien AS Type
FROM epe_personne_datebutoir
WHERE Id=".$_GET['Id']." ";
$result=mysqli_query($bdd,$req);

$row=mysqli_fetch_array($result);
?>
<form id="formulaire" method="POST" action="Modif_DateButoir_Profil.php">
	<table class="TableCompetences" style="width:50%; height:95%; align:center;">
		<tr>
			<td><input type="hidden" id="Id" name="Id" value="<?php echo $_GET['Id']; ?>" />
			<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
			</td>
			
		</tr>
		<tr>
			<td class="Libelle" style="width:30%;">
				<?php if($LangueAffichage=="FR"){echo "Date butoir";}else{echo "Deadline";}?> :
			</td>
			<td style="width:70%;" colspan="3">
				<td width="10%"><input type="date" style="text-align:center;width:130px;" id="dateButoir" name="dateButoir" value="<?php echo AfficheDateFR($row['DateButoir']);?>"/></td>
			</td>
		</tr>
		<tr>
			<td class="Libelle" style="width:30%;">
				<?php if($LangueAffichage=="FR"){echo "Date report";}else{echo "Postponement date";}?> :
			</td>
			<td style="width:70%;" colspan="3">
				<td width="10%"><input type="date" style="text-align:center;width:130px;" id="dateReport" name="dateReport" value="<?php echo AfficheDateFR($row['DateReport']);?>"/></td>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td colspan="2" align="center">
				<input class="Bouton" name="generer" type="submit" <?php if($LangueAffichage=="FR"){echo "value='Modifier'";}else{echo "value='Edit'";}?>>
			</td>
		</tr>
	</table>
</form>
<?php

mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>

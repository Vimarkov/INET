<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<!-- Feuille de style -->
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Planning.css">
	<script language="javascript" src="Modif_VacationPersonne.js"></script>
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script>
		function FermerEtRecharger(Menu)
		{
			opener.location.href="Liste_RelevesHeures.php?Menu="+Menu;
			window.close();
		}
	</script>
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
</head>

<?php
require("../Connexioni.php");
require("../Fonctions.php");
require_once("Fonctions_Planning.php");

if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

$mois=$_SESSION['FiltreRHRelevesHeures_Mois'];
if($mois<10){$mois="0".$mois;}

if(isset($_POST['submitEnregistrer'])){
	$req="SELECT Id 
		FROM rh_personne_plateforme_planning_export 
		WHERE Suppr=0 
		AND Id_Personne=".$_POST['Id_Personne']." 
		AND Mois=".$mois." 
		AND Annee=".$_SESSION['FiltreRHRelevesHeures_Annee']."
		AND Id_Plateforme=".$_SESSION['FiltreRHRelevesHeures_Plateforme']."
		AND Id_AgenceInterim=".$_POST['Id_Agence']." ";
	$resultExport=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($resultExport);
	if($nb==0){
		$req="INSERT INTO rh_personne_plateforme_planning_export (Id_Personne,Id_Plateforme,Mois,Annee,Id_AgenceInterim,Id_Creation,Divers) 
			VALUES (".$_POST['Id_Personne'].",".$_SESSION['FiltreRHRelevesHeures_Plateforme'].",".$mois.",".$_SESSION['FiltreRHRelevesHeures_Annee'].",".$_POST['Id_Agence'].",".$_SESSION['Id_Personne'].",'".addslashes($_POST['divers'])."') ";
		$resultAdd=mysqli_query($bdd,$req);
	}
	else{
		$req="UPDATE rh_personne_plateforme_planning_export
			SET Divers='".addslashes($_POST['divers'])."'
			WHERE Suppr=0 
			AND Id_Personne=".$_POST['Id_Personne']."
			AND Id_Plateforme=".$_SESSION['FiltreRHRelevesHeures_Plateforme']."
			AND Mois=".$mois."
			AND Annee=".$_SESSION['FiltreRHRelevesHeures_Annee']."
			AND Id_AgenceInterim=".$_POST['Id_Agence'];
		$resultAdd=mysqli_query($bdd,$req);
	}
	echo "<script>FermerEtRecharger(".$_POST['Menu'].");</script>";
 }
 
 $divers="";
 $req="SELECT Divers 
	FROM rh_personne_plateforme_planning_export 
	WHERE Suppr=0 
	AND Id_Personne=".$_GET['Id_Personne']." 
	AND Mois=".$mois." 
	AND Annee=".$_SESSION['FiltreRHRelevesHeures_Annee']."
	AND Id_AgenceInterim=0 ";
$resultExport=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($resultExport);
$divers="";
if($nb>0){
	$rowDivers=mysqli_fetch_array($resultExport);
	$divers=stripslashes($rowDivers['Divers']);
}
?>
 <form  id="formulaire" method="post" action="Modif_Divers.php" onsubmit=" return selectallVac();">
	<table class="TableCompetences" width="100%">
			<input type="hidden" id="boutonClick" name="boutonClick" value="">
			<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue'];?>">
			<input type="hidden" name="Id_Personne" size="11" value="<?php echo $_GET['Id_Personne']; ?>">
			<input type="hidden" name="Id_Agence" size="11" value="<?php echo $_GET['Id_Agence']; ?>">
			<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
			<tr>
				<td height="4"></td>
			</tr>
			<tr>
				<td class="Libelle">
					<?php if($_SESSION["Langue"]=="FR"){echo "Divers :";}else{echo "Diverse :";} ?>
				</td>
				<td colspan="3">
					<textarea name="divers" rows=3 cols=80 resize="none"><?php echo  $divers; ?></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="4" align="center">
					<input class="Bouton" name="submitEnregistrer" type="submit" onclick="document.getElementById('boutonClick').value='Ajout';" value='<?php if($_SESSION["Langue"]=="FR"){echo "Enregistrer";}else{echo "Save";}?>'>
				</td>
			</tr>
	</table>
	</form>
</body>
<?php
	echo "<script>VerifCongesHeures();</script>";
?>
</html>
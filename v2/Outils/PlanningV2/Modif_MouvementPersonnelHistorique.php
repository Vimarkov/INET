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
			window.opener.location="Liste_MouvementPersonnelHistorique.php?Menu="+Menu+"&TDB="+TDB+"&OngletTDB="+OngletTDB;
			window.close();
		}
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(document.getElementById('dateFin').value==""){alert("Veuillez ajouter une date de fin.");return false;}
			}
			else{
				if(document.getElementById('dateFin').value==""){alert("Please add a end date.");return false;}

			}
			return true;
		}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
Ecrire_Code_JS_Init_Date();
if(isset($_POST['submitValider'])){
		$requeteUpdate="UPDATE rh_personne_mouvement SET 
				DateFin='".TrsfDate_($_POST['dateFin'])."'
				WHERE Id=".$_POST['Id']." ";

		$resultat=mysqli_query($bdd,$requeteUpdate);
	echo "<script>FermerEtRecharger('".$_POST['Menu']."',".$_POST['TDB'].",'".$_POST['OngletTDB']."');</script>";
}
$req="SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
	DateDebut,DateFin,Id_Pole,
	(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Prestation,
	(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_Pole) AS Pole
	FROM rh_personne_mouvement
	WHERE Id=".$_GET['Id']."
	";
$result=mysqli_query($bdd,$req);
$row=mysqli_fetch_array($result);
?>
<form id="formulaire" method="post" action="Modif_MouvementPersonnelHistorique.php" onsubmit="return VerifChamps();">
	<table class="TableCompetences" width="100%">
		<tr style="display:none;">
			<td><input type="text" name="Id" size="11" value="<?php echo $_GET['Id']; ?>"></td>
			<td><input type="text" name="Menu" size="11" value="<?php echo $_GET['Menu']; ?>"></td>
			<td><input type="text" name="TDB" size="11" value="<?php echo $_GET['TDB']; ?>"></td>
			<td><input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" /></td>
			<td><input type="text" name="OngletTDB" size="11" value="<?php echo $_GET['OngletTDB']; ?>"></td>
		</tr>
		<tr>
			<td><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "People";} ?> :</td>
			<td>
				<?php echo $row['Personne']; ?>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?> : </td>
			<td colspan="3"><?php 
			$pole="";
			if($row['Id_Pole']>0){$pole=" - ".$row['Pole'];}
			echo $row['Prestation'].$pole; 
			?></td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td><?php if($_SESSION["Langue"]=="FR"){echo "Date de début";}else{echo "Start date";} ?> : </td>
			<td><?php echo AfficheDateJJ_MM_AAAA($row['DateDebut']); ?></td>
			<td><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin";}else{echo "End date";} ?> : </td>
			<td><input id="dateFin" name="dateFin" type="date" size="10" value="<?php echo AfficheDateFR($row['DateFin']);?>"></td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td colspan="4" align="center">
				<input class="Bouton" name="submitValider" type="submit" value='Valider'>
			</td>
		</tr>
	</table>
</form>
</body>
</html>
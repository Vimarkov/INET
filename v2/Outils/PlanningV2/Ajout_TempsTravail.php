<html>
<head>
	<title>Formations - Ajouter un client</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script type="text/javascript">
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
				else{return true;}
			}
			else{
				if(formulaire.Libelle.value==''){alert('You did not fill in the label.');return false;}
				else{return true;}
			}
		}
			
		function FermerEtRecharger(Menu)
		{
			window.opener.location="Liste_TempsTravail.php?Menu="+Menu;
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");

$SrcProblem="";
$Problem=0;
$FichierTransfert=0;

if($_POST)
{
	$nbHeures=0;
	if($_POST['nbHeuresMois']<>""){$nbHeures=$_POST['nbHeuresMois'];}
	$nbHeuresSemaine=0;
	if($_POST['nbHeuresSemaine']<>""){$nbHeuresSemaine=$_POST['nbHeuresSemaine'];}
	if($_POST['Mode']=="Ajout")
	{
		$requeteInsertUpdate="INSERT INTO rh_tempstravail (Libelle,EstUnTempsPlein,NbHeureMois,NbHeureSemaine)";
		$requeteInsertUpdate.=" VALUES (";
		$requeteInsertUpdate.="'".addslashes($_POST['Libelle'])."',";
		$requeteInsertUpdate.="".$_POST['tempsplein'].",";
		$requeteInsertUpdate.="".$nbHeures.",";
		$requeteInsertUpdate.="".$nbHeuresSemaine."";
		$requeteInsertUpdate.=")";
	}
	else
	{		
		$requeteInsertUpdate="UPDATE rh_tempstravail SET";
		$requeteInsertUpdate.=" Libelle='".addslashes($_POST['Libelle'])."', ";
		$requeteInsertUpdate.=" EstUnTempsPlein=".$_POST['tempsplein'].", ";
		$requeteInsertUpdate.=" NbHeureMois=".$nbHeures.", ";
		$requeteInsertUpdate.=" NbHeureSemaine=".$nbHeuresSemaine." ";
		$requeteInsertUpdate.=" WHERE Id=".$_POST['Id'];
	}
	echo $requeteInsertUpdate;
	$resultInsertUpdate=mysqli_query($bdd,$requeteInsertUpdate);
	echo "<script>FermerEtRecharger(".$_POST['Menu'].");</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Modif=false;
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0')
		{
			$Modif=True;
			$result=mysqli_query($bdd,"SELECT Id, Libelle, EstUnTempsPlein, NbHeureMois, NbHeureSemaine FROM rh_tempstravail WHERE Id=".$_GET['Id']." AND Suppr=0");
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_TempsTravail.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $row['Id'];}?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td colspan="3"><input name="Libelle" size="30" type="text" value="<?php if($Modif){echo stripslashes($row['Libelle']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nb heures / mois";}else{echo "Nb hours / month";}?> : </td>
				<td colspan="3"><input onKeyUp="nombre(this)" name="nbHeuresMois" size="8" type="text" value="<?php if($Modif){echo $row['NbHeureMois'];}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nb heures / semaine";}else{echo "Nb hours / week";}?> : </td>
				<td colspan="3"><input onKeyUp="nombre(this)" name="nbHeuresSemaine" size="8" type="text" value="<?php if($Modif){echo $row['NbHeureSemaine'];}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Est un temps plein";}else{echo "Is a full time";}?> : </td>
				<td colspan="3">
					<select name="tempsplein">
						<option value="0" <?php if($Modif){if($row['EstUnTempsPlein']==0){echo "selected";}} ?> ><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?></option>
						<option value="1" <?php if($Modif){if($row['EstUnTempsPlein']==1){echo "selected";}}else{echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input class="Bouton" type="submit" 
					<?php
						if($Modif){if($_SESSION["Langue"]=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}}
						else{if($_SESSION["Langue"]=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}}
					?>
					/>
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"UPDATE rh_tempstravail SET Suppr=1,Id_Suppr=".$_SESSION['Id_Personne'].", DateSuppr='".date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger(".$_GET['Menu'].");</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
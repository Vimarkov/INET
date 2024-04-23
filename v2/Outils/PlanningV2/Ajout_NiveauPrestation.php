<html>
<head>
	<title>Formations - Ajouter un client</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script type="text/javascript">		
		function FermerEtRecharger(Menu)
		{
			window.opener.location="Liste_Prestation.php?Menu="+Menu;
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
	$requeteInsertUpdate="UPDATE new_competences_prestation SET";
	$requeteInsertUpdate.=" NbNiveauValidationConges=".$_POST['nbNiveau'].", ";
	$requeteInsertUpdate.=" ChefEquipeNonBackup=".$_POST['chefEquipeBackup']." ";
	$requeteInsertUpdate.=" WHERE Id=".$_POST['Id'];

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
			$result=mysqli_query($bdd,"SELECT Id, Libelle, NbNiveauValidationConges, ChefEquipeNonBackup FROM new_competences_prestation WHERE Id=".$_GET['Id']."");
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_NiveauPrestation.php">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $row['Id'];}?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td colspan="3"><?php echo stripslashes($row['Libelle']); ?></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nombre de niveau de validation des congés";}else{echo "Number of leave validation levels";}?> : </td>
				<td colspan="3">
					<select name="nbNiveau" id="nbNiveau">
						<option value="1"  <?php if($Modif){if($row['NbNiveauValidationConges']==1){echo "selected";}} ?>>1</option>
						<option value="2" <?php if($Modif){if($row['NbNiveauValidationConges']==2 || $row['NbNiveauValidationConges']==0){echo "selected";}} ?>>2</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Niveau N+1<br>Backup";}else{echo "N+1 Level<br>Backup Team";}?> : </td>
				<td colspan="3">
					<select name="chefEquipeBackup" id="chefEquipeBackup">
						<option value="0"  <?php if($Modif){if($row['ChefEquipeNonBackup']==0){echo "selected";}} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";}?></option>
						<option value="1" <?php if($Modif){if($row['ChefEquipeNonBackup']==1){echo "selected";}} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";}?></option>
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
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
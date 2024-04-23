<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script type="text/javascript">	
		function FermerEtRecharger()
		{
			window.opener.location="Liste_QualifsObligatoires.php";
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
$LangueAffichage=$_SESSION['Langue'];
$SrcProblem="";
$Problem=0;
$FichierTransfert=0;

if($_POST)
{
	
	$requeteUpt="UPDATE form_formation SET
				Obligatoire='".$_POST['obligatoire']."'
				WHERE Id=".$_POST['Id'];
	$resultUpt=mysqli_query($bdd,$requeteUpt);

	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Modif=false;
	if($_GET['Id']!='0')
	{
		$Modif=True;
		$result=mysqli_query($bdd,"SELECT form_formation.Id, Obligatoire,
							(SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) AS Organisme,
							(SELECT Libelle
								FROM form_formation_langue_infos
								WHERE Id_Formation=form_formation.Id
								AND Id_Langue=form_formation_plateforme_parametres.Id_Langue
								AND Suppr=0 LIMIT 1) AS Libelle,
							(SELECT Libelle FROM form_typeformation WHERE Id=Id_TypeFormation) AS TypeFormation,
							(SELECT Libelle FROM new_competences_plateforme WHERE Id=form_formation_plateforme_parametres.Id_Plateforme) AS Plateforme FROM form_formation LEFT JOIN form_formation_plateforme_parametres
							ON form_formation_plateforme_parametres.Id_Formation=form_formation.Id WHERE form_formation_plateforme_parametres.Id=".$_GET['Id']." ");
		$row=mysqli_fetch_array($result);
	}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Modif_FormationQualipsoObligatoire.php">
		<input type="hidden" name="Id" value="<?php echo $row['Id'];?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
				<td colspan="3"><?php echo stripslashes($row['Plateforme']);?></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Formation";}else{echo "Training";}?> : </td>
				<td colspan="3"><?php echo stripslashes($row['Libelle']);?></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Organisme";}else{echo "Organism";}?> : </td>
				<td colspan="3"><?php echo stripslashes($row['Organisme']);?></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Obligatoire";}else{echo "Mandatory";}?> : </td>
				<td>
					<select name="obligatoire" id="obligatoire">
						<option value="0" <?php if($row['Obligatoire']==0){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";}?></option>
						<option value="1" <?php if($row['Obligatoire']==1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}?></option>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input class="Bouton" type="submit" 
					<?php
						if($_SESSION["Langue"]=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
					?>
					/>
				</td>
			</tr>
		</table>
		</form>
<?php
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
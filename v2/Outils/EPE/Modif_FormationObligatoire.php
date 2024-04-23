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
	
	$requeteUpt="UPDATE new_competences_formation SET
				Obligatoire='".$_POST['obligatoire']."'
				WHERE Id=".$_POST['Id'];
				echo $requeteUpt;
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
		$result=mysqli_query($bdd,"SELECT Id, Libelle,Obligatoire FROM new_competences_formation WHERE Id=".$_GET['Id']." ");
		$row=mysqli_fetch_array($result);
	}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Modif_FormationObligatoire.php">
		<input type="hidden" name="Id" value="<?php echo $row['Id'];?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Formation";}else{echo "Training";}?> : </td>
				<td colspan="3"><?php echo stripslashes($row['Libelle']);?></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Obligatoire";}else{echo "Mandatory";}?> : </td>
				<td>
					<select name="obligatoire" id="obligatoire">
						<option value="-1" <?php if($row['Obligatoire']==-1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";}?></option>
						<option value="1" <?php if($row['Obligatoire']==1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}?></option>
						<option value="-2" <?php if($row['Obligatoire']==-2){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "NA";}else{echo "NA";}?></option>
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
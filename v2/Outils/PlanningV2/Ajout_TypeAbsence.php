<html>
<head>
	<title>Formations - Ajouter un client</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script type="text/javascript">
	
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.codePlanning.value==''){alert('Vous n\'avez pas renseigné le code planning.');return false;}
			}
			else{
				if(formulaire.codePlanning.value==''){alert('You did not fill in the schedule code.');return false;}
			}
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
			}
			else{
				if(formulaire.Libelle.value==''){alert('You did not fill in the label.');return false;}
			}
			return true;
		}
		
		function FermerEtRecharger(Menu)
		{
			window.opener.location="Liste_TypeAbsence.php?Menu="+Menu;
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
	if($_POST['Mode']=="Ajout")
	{
		$nbJour=0;
		if($_POST['nbJourAutorise']<>""){$nbJour=$_POST['nbJourAutorise'];}
		$requeteInsertUpdate="INSERT INTO rh_typeabsence (Libelle, LibelleEN, CodePlanning, NbJourAutorise, Couleur, InformationSalarie,DispoPourSalarie,DispoPourInterimaire,Dispo,JourCalendaire,NecessiteJustif)";
		$requeteInsertUpdate.=" VALUES (";
		$requeteInsertUpdate.="'".addslashes($_POST['Libelle'])."',";
		$requeteInsertUpdate.="'".addslashes($_POST['LibelleEN'])."',";
		$requeteInsertUpdate.="'".addslashes($_POST['codePlanning'])."',";
		$requeteInsertUpdate.="".$nbJour.",";
		$requeteInsertUpdate.="'".addslashes($_POST['couleur'])."',";
		$requeteInsertUpdate.="'".addslashes($_POST['infoSalarie'])."',";
		$requeteInsertUpdate.="".$_POST['dispoSalarie'].",";
		$requeteInsertUpdate.="".$_POST['dispoInterimaire'].",";
		$requeteInsertUpdate.="".$_POST['jourCalendaire'].",1,";
		$requeteInsertUpdate.="".$_POST['necessiteJustif']."";
		$requeteInsertUpdate.=")";
	}
	else
	{		
		$nbJour=0;
		if($_POST['nbJourAutorise']<>""){$nbJour=$_POST['nbJourAutorise'];}
		$requeteInsertUpdate="UPDATE rh_typeabsence SET";
		$requeteInsertUpdate.=" Libelle='".addslashes($_POST['Libelle'])."', ";
		$requeteInsertUpdate.=" LibelleEN='".addslashes($_POST['LibelleEN'])."', ";
		$requeteInsertUpdate.=" CodePlanning='".addslashes($_POST['codePlanning'])."', ";
		$requeteInsertUpdate.=" NbJourAutorise=".$nbJour.", ";
		$requeteInsertUpdate.=" Couleur='".addslashes($_POST['couleur'])."', ";
		$requeteInsertUpdate.=" InformationSalarie='".addslashes($_POST['infoSalarie'])."', ";
		$requeteInsertUpdate.=" DispoPourSalarie=".$_POST['dispoSalarie'].", ";
		$requeteInsertUpdate.=" DispoPourInterimaire=".$_POST['dispoInterimaire'].", ";
		$requeteInsertUpdate.=" JourCalendaire=".$_POST['jourCalendaire'].", ";
		$requeteInsertUpdate.=" NecessiteJustif=".$_POST['necessiteJustif']." ";
		$requeteInsertUpdate.=" WHERE Id=".$_POST['Id'];
	}
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
			$result=mysqli_query($bdd,"SELECT Id, Libelle, LibelleEN, CodePlanning, NbJourAutorise, Couleur, InformationSalarie, PosableHeure,JourCalendaire,NecessiteJustif,
								SpecifierHeurePrevu,HeuresDeductibles,DispoPourSalarie, DispoPourInterimaire FROM rh_typeabsence WHERE Id=".$_GET['Id']." ");
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_TypeAbsence.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $row['Id'];}?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Code planning";}else{echo "Schedule code";}?> : </td>
				<td><input name="codePlanning" size="10" type="text" value="<?php if($Modif){echo stripslashes($row['CodePlanning']);}?>"></td>
				
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Couleur";}else{echo "Color";}?> : </td>
				<td><input name="couleur" type="color" id="color-picker" value="<?php if($Modif){echo stripslashes($row['Couleur']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td colspan="3"><input name="Libelle" size="30" type="text" value="<?php if($Modif){echo stripslashes($row['Libelle']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Libellé anglais";}else{echo "Wording english";}?> : </td>
				<td colspan="3"><input name="LibelleEN" size="30" type="text" value="<?php if($Modif){echo stripslashes($row['LibelleEN']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nombre de jour autorisé";}else{echo "Number of days allowed";}?> : </td>
				<td colspan="3"><input onKeyUp="nombre(this)" name="nbJourAutorise" size="8" type="text" value="<?php if($Modif){echo stripslashes($row['NbJourAutorise']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Jours calendaires";}else{echo "Calendar days";}?> : </td>
				<td colspan="3">
					<select name="jourCalendaire">
						<option value="0" <?php if($Modif){if($row['JourCalendaire']==0){echo "selected";}}else{echo "selected";} ?> ><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?></option>
						<option value="1" <?php if($Modif){if($row['JourCalendaire']==1){echo "selected";}} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Information pour le salarié";}else{echo "Information for the employee";}?> : </td>
				<td colspan="3"><textarea name="infoSalarie" cols="40" rows="4" style="resize:none;" type="text"><?php if($Modif){echo stripslashes($row['InformationSalarie']);}?></textarea>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Type d'absence posable par un salarié";}else{echo "Type of absence posable by an employee";}?> : </td>
				<td colspan="3">
					<select name="dispoSalarie">
						<option value="0" <?php if($Modif){if($row['DispoPourSalarie']==0){echo "selected";}} ?> ><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?></option>
						<option value="1" <?php if($Modif){if($row['DispoPourSalarie']==1){echo "selected";}}else{echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Type d'absence posable par un intérimaire";}else{echo "Type of absence posable by an interim";}?> : </td>
				<td colspan="3">
					<select name="dispoInterimaire">
						<option value="0" <?php if($Modif){if($row['DispoPourInterimaire']==0){echo "selected";}} ?> ><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?></option>
						<option value="1" <?php if($Modif){if($row['DispoPourInterimaire']==1){echo "selected";}}else{echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?></option>
					</select>
				</td>
			</tr>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Necessite un justificatif";}else{echo "Requires proof";}?> : </td>
				<td colspan="3">
					<select name="necessiteJustif">
						<option value="0" <?php if($Modif){if($row['NecessiteJustif']==0){echo "selected";}} ?> ><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?></option>
						<option value="1" <?php if($Modif){if($row['NecessiteJustif']==1){echo "selected";}}else{echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="3" align="center">
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
		$result=mysqli_query($bdd,"UPDATE rh_typeabsence SET Suppr=1,Id_Suppr=".$_SESSION['Id_Personne'].", DateSuppr='".date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger(".$_GET['Menu'].");</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
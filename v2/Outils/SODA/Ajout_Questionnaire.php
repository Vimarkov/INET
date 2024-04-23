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
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
				if(formulaire.theme.value=='0'){alert('Vous n\'avez pas renseigné le thème.');return false;}
				if(formulaire.specifique.value=='-1'){alert('Vous n\'avez pas renseigné le champ Générique/Spécifique.');return false;}
			}
			else{
				if(formulaire.Libelle.value==''){alert('You did not fill in the label.');return false;}
				if(formulaire.theme.value=='0'){alert('You have not entered the theme.');return false;}
				if(formulaire.specifique.value=='-1'){alert('You have not filled in the Generic/Specific field.');return false;}
			}
			return true;
		}
			
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
	</script>
</head>
<body>

<?php
$SrcProblem="";
$Problem=0;
$FichierTransfert=0;

$resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$IdPersonneConnectee);
$nbAccess=mysqli_num_rows($resAcc);

$resAccSuperAdmin=mysqli_query($bdd,"SELECT Id FROM soda_superadministrateur WHERE Id_Personne=".$IdPersonneConnectee);
$nbSuperAdmin=mysqli_num_rows($resAccSuperAdmin);

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
		$requeteInsertUpdate="INSERT INTO soda_questionnaire (Libelle,Id_Theme,Specifique,Id_Personne,DateMAJ)";
		$requeteInsertUpdate.=" VALUES (";
		$requeteInsertUpdate.="'".addslashes($_POST['Libelle'])."'";
		$requeteInsertUpdate.=",".$_POST['theme']."";
		$requeteInsertUpdate.=",".$_POST['specifique']."";
		$requeteInsertUpdate.=",".$IdPersonneConnectee."";
		$requeteInsertUpdate.=",'".date('Y-m-d')."'";
		$requeteInsertUpdate.=")";
	}
	else
	{		
		$requeteInsertUpdate="UPDATE soda_questionnaire SET";
		$requeteInsertUpdate.=" Libelle='".addslashes($_POST['Libelle'])."'";
		$requeteInsertUpdate.=",Id_Theme=".$_POST['theme']."";
		$requeteInsertUpdate.=",Actif=".$_POST['actif']."";
		$requeteInsertUpdate.=",Specifique=".$_POST['specifique']."";
		$requeteInsertUpdate.=",Id_Personne=".$IdPersonneConnectee."";
		$requeteInsertUpdate.=",DateMAJ='".date('Y-m-d')."'";
		$requeteInsertUpdate.=" WHERE Id=".$_POST['Id'];
	}
	$resultInsertUpdate=mysqli_query($bdd,$requeteInsertUpdate);
	echo "<script>FermerEtRecharger();</script>";
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
			$result=mysqli_query($bdd,"SELECT Id, Libelle, Id_Theme,Actif,Specifique,Id_Personne,DateMAJ FROM soda_questionnaire WHERE Id=".$_GET['Id']." AND Suppr=0");
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_Questionnaire.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $row['Id'];}?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
		<table class="TableCompetences" style="width:95%; height:95%; align:center;">
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td colspan="3"><input name="Libelle" size="50" type="text" value="<?php if($Modif){echo stripslashes($row['Libelle']);}?>"></td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Theme";}else{echo "Thème";} ?></td>
				<td>
					<select id="theme" name="theme">
					<?php
						echo"<option name='0' value='0'></option>";
						$req = "SELECT Id, Libelle
								FROM soda_theme
								WHERE Suppr=0 ";
						if($nbAccess==0 && $nbSuperAdmin==0){
							$req.="AND Id IN (SELECT Id FROM soda_theme WHERE Suppr=0 AND (Id_Gestionnaire=".$IdPersonneConnectee." OR Id_Backup1=".$IdPersonneConnectee." OR Id_Backup2=".$IdPersonneConnectee." OR Id_Backup3=".$IdPersonneConnectee.")) ";
						}
						$req.="ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowP=mysqli_fetch_array($result)){
								$selected="";
								if($Modif){
									if($rowP['Id']==$row['Id_Theme']){$selected="selected";}
								}
								echo "<option value='".$rowP['Id']."' ".$selected.">".$rowP['Libelle']."</option>";
							}
						}
					?>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Générique/Spécifique";}else{echo "Generic/Specific";} ?></td>
				<td>
					<select id="specifique" name="specifique">
						 <option value="-1" <?php if($Modif){if($row['Specifique']==-1){echo "selected";}}?>></option>
						<option value="0" <?php if($Modif){if($row['Specifique']==0){echo "selected";}}?>><?php if($_SESSION["Langue"]=="FR"){echo "Générique";}else{echo "Generic";}?></option>
						<option value="1" <?php if($Modif){if($row['Specifique']==1){echo "selected";}}?>><?php if($_SESSION["Langue"]=="FR"){echo "Spécifique";}else{echo "Specific";}?></option>
						</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers" <?php if($Modif==false){echo "style='display:none;'";} ?>>
				<td class="Libelle">
					&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "A/I";}else{echo "A/I";}?> :
				</td>
				<td>
					<select id="actif" name="actif">
						<option value="0" <?php if($Modif){if($row['Actif']==0){echo "selected";}}?>>Actif</option>
						<option value="1" <?php if($Modif){if($row['Actif']==1){echo "selected";}}?>>Inactif</option>
					</select>
				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td colspan="2" align="center">
					<input class="Bouton" type="submit" 
					<?php
						if($Modif){if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}}
						else{if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}}
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
		$result=mysqli_query($bdd,"UPDATE soda_questionnaire SET Suppr=1,DateSuppr='".date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger();</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
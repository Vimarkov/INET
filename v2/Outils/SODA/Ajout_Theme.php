<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Ajouter un client</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
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

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
		$requeteInsertUpdate="INSERT INTO soda_theme (Libelle,Id_Createur,Id_Gestionnaire,Id_Backup1,Id_Backup2,Id_Backup3,Id_Qualification)";
		$requeteInsertUpdate.=" VALUES (";
		$requeteInsertUpdate.="'".addslashes($_POST['Libelle'])."'";
		$requeteInsertUpdate.=",".$IdPersonneConnectee."";
		$requeteInsertUpdate.=",".$_POST['Id_Gestionnaire']."";
		$requeteInsertUpdate.=",".$_POST['Id_Backup1']."";
		$requeteInsertUpdate.=",".$_POST['Id_Backup2']."";
		$requeteInsertUpdate.=",".$_POST['Id_Backup3']."";
		$requeteInsertUpdate.=",".$_POST['Id_Qualification']."";
		$requeteInsertUpdate.=")";
	}
	else
	{		
		$requeteInsertUpdate="UPDATE soda_theme SET";
		$requeteInsertUpdate.=" Libelle='".addslashes($_POST['Libelle'])."'";
		$requeteInsertUpdate.=",Id_Gestionnaire=".$_POST['Id_Gestionnaire']."";
		$requeteInsertUpdate.=",Id_Backup1=".$_POST['Id_Backup1']."";
		$requeteInsertUpdate.=",Id_Backup2=".$_POST['Id_Backup2']."";
		$requeteInsertUpdate.=",Id_Backup3=".$_POST['Id_Backup3']."";
		$requeteInsertUpdate.=",Id_Qualification=".$_POST['Id_Qualification']."";
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
			$result=mysqli_query($bdd,"SELECT Id, Libelle, Id_Gestionnaire, Id_Backup1, Id_Backup2, Id_Backup3, Id_Qualification FROM soda_theme WHERE Id=".$_GET['Id']." AND Suppr=0");
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_Theme.php" onSubmit="return VerifChamps();">
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
				<td><?php if($_SESSION['Langue']=="EN"){echo "Related qualification to be a supervisor";}else{echo "Qualification liée pour être surveillant";} ?></td>
				<td>
					<select id="Id_Qualification" name="Id_Qualification">
					<?php
						echo"<option name='0' value='0'></option>";
						$req="SELECT Id, Libelle FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777 ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowP=mysqli_fetch_array($result)){
								$selected="";
								if($Modif){
									if($rowP['Id']==$row['Id_Qualification']){$selected="selected";}
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
				<td><?php if($_SESSION['Langue']=="EN"){echo "Administrator";}else{echo "Gestionnaire";} ?></td>
				<td>
					<select id="Id_Gestionnaire" name="Id_Gestionnaire">
					<?php
						echo"<option name='0' value='0'></option>";
						$req="SELECT Id, Nom, Prenom FROM new_rh_etatcivil ORDER BY Nom, Prenom;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowP=mysqli_fetch_array($result)){
								$selected="";
								if($Modif){
									if($rowP['Id']==$row['Id_Gestionnaire']){$selected="selected";}
								}
								echo "<option value='".$rowP['Id']."' ".$selected.">".$rowP['Nom']." ".$rowP['Prenom']."</option>";
							}
						}
					?>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "Backup 1";}else{echo "Backup 1";} ?></td>
				<td>
					<select id="Id_Backup1" name="Id_Backup1">
					<?php
						echo"<option name='0' value='0'></option>";
						$req="SELECT Id, Nom, Prenom FROM new_rh_etatcivil ORDER BY Nom, Prenom;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowP=mysqli_fetch_array($result)){
								$selected="";
								if($Modif){
									if($rowP['Id']==$row['Id_Backup1']){$selected="selected";}
								}
								echo "<option value='".$rowP['Id']."' ".$selected.">".$rowP['Nom']." ".$rowP['Prenom']."</option>";
							}
						}
					?>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "Backup 2";}else{echo "Backup 2";} ?></td>
				<td>
					<select id="Id_Backup2" name="Id_Backup2">
					<?php
						echo"<option name='0' value='0'></option>";
						$req="SELECT Id, Nom, Prenom FROM new_rh_etatcivil ORDER BY Nom, Prenom;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowP=mysqli_fetch_array($result)){
								$selected="";
								if($Modif){
									if($rowP['Id']==$row['Id_Backup2']){$selected="selected";}
								}
								echo "<option value='".$rowP['Id']."' ".$selected.">".$rowP['Nom']." ".$rowP['Prenom']."</option>";
							}
						}
					?>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "Backup 3";}else{echo "Backup 3";} ?></td>
				<td>
					<select id="Id_Backup3" name="Id_Backup3">
					<?php
						echo"<option name='0' value='0'></option>";
						$req="SELECT Id, Nom, Prenom FROM new_rh_etatcivil ORDER BY Nom, Prenom;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowP=mysqli_fetch_array($result)){
								$selected="";
								if($Modif){
									if($rowP['Id']==$row['Id_Backup3']){$selected="selected";}
								}
								echo "<option value='".$rowP['Id']."' ".$selected.">".$rowP['Nom']." ".$rowP['Prenom']."</option>";
							}
						}
					?>
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
		$result=mysqli_query($bdd,"UPDATE soda_theme SET Suppr=1,DateSuppr='".date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger();</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Ajouter un lieu</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
				else if(formulaire.Adresse.value==''){alert('Vous n\'avez pas renseigné l\'adresse.');return false;}
				else{return true;}
			}
			else{
				if(formulaire.Libelle.value==''){alert('You did not fill in the label.');return false;}
				else if(formulaire.Adresse.value==''){alert('You did not fill in the address.');return false;}
				else{return true;}
			}
		}
			
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}

		function CheckFichier(){if(formulaire.fichier.value!=''){formulaire.SupprFichier.checked=true;}}
	</script>
</head>
<body>

<?php
//RECUPERATION VARIABLES FICHIERS
if(isset($_POST['fichieractuel'])){$Fichier=$_POST['fichieractuel'];}
else{$Fichier="";}
$DirFichier="Outils/Formation/Docs/Lieu/";
$DirFichier2="Docs/Lieu/";

$SrcProblem="";
$Problem=0;
$FichierTransfert=0;

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
		$requeteVerificationExiste="SELECT Id FROM form_lieu WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Id_Plateforme=".$_POST['Id_Plateforme'];
		$requeteInsertUpdate="INSERT INTO form_lieu (Id_Plateforme, Libelle, Adresse, chemin_fichier, Fichier,Id_Personne_MAJ,Date_MAJ)";
		$requeteInsertUpdate.=" VALUES (";
		$requeteInsertUpdate.=$_POST['Id_Plateforme'];
		$requeteInsertUpdate.=",'".addslashes($_POST['Libelle'])."'";
		$requeteInsertUpdate.=",'".addslashes($_POST['Adresse'])."'";
		$requeteInsertUpdate.=",'##CHEMIN##'";
		$requeteInsertUpdate.=",'##FICHIER##'";
		$requeteInsertUpdate.=",".$IdPersonneConnectee;
		$requeteInsertUpdate.=",'".date('Y-m-d')."'";
		$requeteInsertUpdate.=")";
	}
	else
	{		
		$requeteVerificationExiste="SELECT Id FROM form_lieu WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Id_Plateforme=".$_POST['Id_Plateforme']." AND Id!=".$_POST['Id'];
		$requeteInsertUpdate="UPDATE form_lieu SET";
		$requeteInsertUpdate.=" Libelle='".addslashes($_POST['Libelle'])."'";
		$requeteInsertUpdate.=", Id_Plateforme=".$_POST['Id_Plateforme'];
		$requeteInsertUpdate.=", Adresse='".addslashes($_POST['Adresse'])."'";
		$requeteInsertUpdate.=", chemin_fichier='##CHEMIN##'";
		$requeteInsertUpdate.=", Fichier='##FICHIER##'";
		$requeteInsertUpdate.=", Id_Personne_MAJ=".$IdPersonneConnectee."";
		$requeteInsertUpdate.=", Date_MAJ='".date('Y-m-d')."'";
		$requeteInsertUpdate.=" WHERE Id=".$_POST['Id'];
	}
	
	$resultVerificationExiste=mysqli_query($bdd,$requeteVerificationExiste);
	if(mysqli_num_rows($resultVerificationExiste)==0)
	{
		if($_POST['Mode']=="Modif")
		{
			//S'il y avait une fichier
			if(isset($_POST['SupprFichier']))
			{
				if($_POST['SupprFichier'])
				{
					if(file_exists ($DirFichier2.$_POST['fichieractuel'])){
						if(!unlink($DirFichier2.$_POST['fichieractuel'])){$SrcProblem.="Impossible de supprimer le fichier.";$Problem=1;}
						elseif($FichierTransfert==0){$Fichier="";}
					}
					else{
						$Fichier="";
					}
				}
			}
		}
		
		if($Problem==1){echo "<script>alert('Il y a eu une erreur lors de la suppression de l ancien du fichier joint (".$SrcProblem.");</script>";}
		else
		{
			//****TRANSFERT FICHIER****
			if($_FILES['fichier']['name']!="")
			{
				$tmp_file=$_FILES['fichier']['tmp_name'];
				if(!is_uploaded_file($tmp_file)){$SrcProblem.="Le fichier est introuvable.";$Problem=1;}
				else
				{
					//On vérifie la taille du fichiher
					if(filesize($_FILES['fichier']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
					{$SrcProblem.="Le fichier est trop volumineux.";$Problem=1;}
					else
					{
						// on copie le fichier dans le dossier de destination
						$name_file=$_FILES['fichier']['name'];
						$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
						while(file_exists($DirFichier2.$name_file)){$name_file="le ".date('j-m-y')." a ".date('H-i-s')." ".$name_file;}
						if(!move_uploaded_file($tmp_file,$DirFichier2.$name_file))
						{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
						else{$Fichier=$name_file;$FichierTransfert=1;}
					}
				}
			}
			
			if($Problem==1){echo "<script>alert('Il y a eu une erreur lors de la copie du fichier joint (".$SrcProblem."). Veuillez vérifier si celui-ci est bien ajouté dans ce que vous venez de créer.');</script>";}
			else
			{
				$req = str_replace("##FICHIER##",$Fichier,$requeteInsertUpdate);
				$req = str_replace("##CHEMIN##",$DirFichier2,$req);
				$resultInsertUpdate=mysqli_query($bdd,$req);
				echo "<script>FermerEtRecharger();</script>";
			}
		}
	}
	else{echo "<font class='Erreur'>Ce libellé existe déjà.<br>Vous devez recommencer l'opération.</font>";}
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
			$result=mysqli_query($bdd,"SELECT Id, Id_Plateforme, Libelle, Adresse, Fichier FROM form_lieu WHERE Id=".$_GET['Id']." AND Suppr=0");
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_Lieu.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $row['Id'];}?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
		<table style="width:95%; height:95%; align:center; class:TableCompetences;">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
				<td>
					<select name="Id_Plateforme">
						<?php
						$resultPlateforme=mysqli_query($bdd,"SELECT DISTINCT Id_Plateforme, 
							(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle 
							FROM new_competences_personne_poste_plateforme 
							WHERE Id_Poste
							IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.") 
							AND Id_Personne=".$IdPersonneConnectee." 
							ORDER BY Libelle");
						while($rowplateforme=mysqli_fetch_array($resultPlateforme))
						{
							echo "<option value='".$rowplateforme['Id_Plateforme']."'";
							if($Modif){if($rowplateforme['Id_Plateforme']==$row['Id_Plateforme']){echo " selected";}}
							echo ">".$rowplateforme[1]."</option>\n";
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td colspan="3"><input name="Libelle" size="50" type="text" value="<?php if($Modif){echo $row['Libelle'];}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Adresse";}else{echo "Address";}?> : </td>
				<td><textarea name="Adresse" rows="4" cols="30" style="resize:none"><?php if($Modif){echo stripslashes($row['Adresse']);}?></textarea></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Fichier";}else{echo "File";}?> : </td>
				<td><input name="fichier" type="file" onChange="CheckFichier();"></td>
			</tr>
			<tr>
				<?php
				if($Modif && $row['Fichier']!="")
				{
				?>
				<td>
					<a class="Info" href="<?php echo $chemin."/".$DirFichier.$row['Fichier']; ?>" target="_blank"><?php if($LangueAffichage=="FR"){echo "Ouvrir";}else{echo "Open";}?></a>
					<input type="hidden" name="fichieractuel" value="<?php echo $row['Fichier'];?>">
				</td>
				<td class="PoliceModif"><input type="checkbox" name="SupprFichier" onClick="CheckFichier();"><?php if($LangueAffichage=="FR"){echo "Supprimer le fichier";}else{echo "Delete file";}?></td>
				<?php
				}
				?>
			</tr>
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
		$result=mysqli_query($bdd,"UPDATE form_lieu SET Suppr=1,Id_Personne_MAJ=".$IdPersonneConnectee.", Date_MAJ='".date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger();</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
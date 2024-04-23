<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Document - Langue - Question</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script type="text/javascript">
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
				return true;
			}
			else{
				if(formulaire.Libelle.value==''){alert('You did not fill in the label.');return false;}
				return true;
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
if($_POST){$DirFichier=$CheminFormation."Document/".$_POST['Id_Document']."/".$_POST['Id_Document_Langue']."/";}
else{$DirFichier=$CheminFormation."Document/".$_GET['Id_Document']."/".$_GET['Id_Document_Langue']."/";}
$SrcProblem="";
$Problem=0;
$FichierTransfert=0;


if(!file_exists($DirFichier))
{
	$res=mkdir_ftp($DirFichier,0773);
	if(!$res){echo 'Echec lors de la création des répertoires...';}
}
if($_POST)
{
	$requeteVerificationExiste="SELECT Id FROM form_document_langue_question WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Id_Document_Langue=".$_POST['Id_Document_Langue']." AND Suppr=0 AND Id!=".$_POST['Id'];
	$requeteInsert="INSERT INTO form_document_langue_question (Id_Origine, Id_Document_Langue, Libelle,TypeReponse, Fichier, Id_Personne_MAJ, Date_MAJ)";
	$requeteInsert.=" VALUES (";
	$requeteInsert.=$_POST['Id'];
	$requeteInsert.=",".$_POST['Id_Document_Langue'];
	$requeteInsert.=",'".addslashes($_POST['Libelle'])."'";
	$requeteInsert.=",'".$_POST['TypeReponse']."',";
	$requeteInsert.="'##FICHIER##'";
	$requeteInsert.=",".$_POST['Id_Personne_MAJ'];
	$requeteInsert.=",'".$_POST['Date_MAJ']."'";
	$requeteInsert.=");";
	
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
					if(!unlink($DirFichier.$_POST['fichieractuel'])){$SrcProblem.="Impossible de supprimer le fichier.";$Problem=1;}
					elseif($FichierTransfert==0){$Fichier="";}
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
						while(file_exists($DirFichier.$name_file)){$name_file="le_".date('j-m-y')."_a_".date('H-i-s')."_".$name_file;}
						if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
						{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
						else{$Fichier=$name_file;$FichierTransfert=1;}
					}
				}
			}
				
			if($Problem==1){echo "<script>alert('Il y a eu une erreur lors de la copie du fichier joint (".$SrcProblem."). Veuillez vérifier si celui-ci est bien ajouté dans ce que vous venez de créer.');</script>";}
			else
			{
				if($_POST['Mode']=="Modif")
				{
					$requeteDelete="UPDATE form_document_langue_question SET";
					$requeteDelete.=" Suppr=1";
					$requeteDelete.=" WHERE Id=".$_POST['Id'];
					$resultDelete=mysqli_query($bdd,$requeteDelete);
				}
				$resultInsert=mysqli_query($bdd,str_replace("##FICHIER##",$Fichier,$requeteInsert));
				$Id_New=mysqli_insert_id($bdd);
				$req="INSERT INTO form_document_langue_question_reponse (Id_Origine,Id_Document_Langue_Question,Libelle,TypeReponse,Id_Personne_MAJ,Date_MAJ) ";
				$req.="SELECT Id_Origine,".$Id_New.",Libelle,TypeReponse,Id_Personne_MAJ,Date_MAJ ";
				$req.="FROM form_document_langue_question_reponse ";
				$req.="WHERE Suppr=0 AND Id_Document_Langue_Question=".$_POST['Id']."";
				
				$resultInsert=mysqli_query($bdd,$req);
				
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
			$Modif=true;
			$result=mysqli_query($bdd,"SELECT Id, Id_Document_Langue, Libelle, Fichier,TypeReponse, Id_Personne_MAJ, Date_MAJ FROM form_document_langue_question WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}

?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_Document_Langue_Question.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php echo $_GET['Id'];?>">
		<input type="hidden" name="Id_Document_Langue" value="<?php echo $_GET['Id_Document_Langue'];?>">
		<input type="hidden" name="Id_Document" value="<?php echo $_GET['Id_Document'];?>">
		<input type="hidden" name="Id_Personne_MAJ" value="<?php echo $IdPersonneConnectee;?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
		<input type="hidden" name="Date_MAJ" value="<?php echo date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Question";}else{echo "Question";}?> : </td>
				<td>
					<textarea name="Libelle" rows="1" cols="65" style="resize:none;"><?php if($Modif){echo stripslashes($row['Libelle']);}?></textarea>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($LangueAffichage=="FR"){echo "Type de réponse";}else{echo "Type of answer";}?> : </td>
				<td>
					<select name="TypeReponse" style="width:10;">
						<option value="Note (1 à 6)" <?php if($_GET['Mode']=="M"){if($LigneQuestion['TypeReponse']=="Note (1 à 6)"){echo "selected";}} ?>>Note (1 à 6)</option>
						<option value="Oui/Non" <?php if($_GET['Mode']=="M"){if($LigneQuestion['TypeReponse']=="Oui/Non"){echo "selected";}} ?>>Oui/Non</option>
						<option value="Texte facultatif" <?php if($_GET['Mode']=="M"){if($LigneQuestion['TypeReponse']=="Texte facultatif"){echo "selected";}} ?>>Texte facultatif</option>
						<option value="Texte obligatoire" <?php if($_GET['Mode']=="M"){if($LigneQuestion['TypeReponse']=="Texte obligatoire"){echo "selected";}}else{ echo "selected";} ?>>Texte obligatoire</option>
					</select>
				</td>
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
					<?php 
						if($LangueAffichage=="FR"){
							echo "<a class=\"Info\" href=\"".$DirFichier."/".$row['Fichier']."\" target=\"_blank\">Ouvrir</a>";
						}
						else{
							echo "<a class=\"Info\" href=\"".$DirFichier."/".$row['Fichier']."\" target=\"_blank\">Open</a>";
						}
					?>
					<input type="hidden" name="fichieractuel" value="<?php echo $row['Fichier'];?>">
				</td>
				<td class="PoliceModif"><input type="checkbox" name="SupprFichier" onClick="CheckFichier();"><?php if($LangueAffichage=="FR"){echo "Supprimer le fichier";}else{echo "Delete file";}?></td>
				<?php
				}
				?>
			</tr>
			<tr>
				<td colspan=2 align="center">
					<input class="Bouton" type="submit" 
					<?php
						if($Modif)
						{
							if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
						}
						else
						{
							if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
						}
					?>
					>
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"UPDATE form_document_langue_question SET Suppr=1, Id_Personne_MAJ=".$IdPersonneConnectee.", Date_MAJ='".Date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger();</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
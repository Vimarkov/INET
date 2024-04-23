<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Document - Langue - Questionnaire complet</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
	</script>
</head>
<body>

<?php
if($_POST){$DirFichier=$CheminFormation."Document/".$_POST['Id_Document']."/".$_POST['Id_Document_Langue']."/";}
else{$DirFichier=$CheminFormation."Document/".$_GET['Id_Document']."/".$_GET['Id_Document_Langue']."/";}

if(!file_exists ($DirFichier))
{
	$res=mkdir_ftp($DirFichier,0773);
	if(!$res){
		if($LangueAffichage=="FR"){echo 'Echec lors de la création des répertoires...';}
		else{echo 'Failed to create directories...';}
	}
}

if($_POST)
{
	for($i=1;$i<=15;$i++)
	{
		if($_POST['Libelle_'.$i]!="")
		{
			$SrcProblem="";
			$Problem=0;
			$FichierTransfert=0;
			
			if(isset($_POST['fichieractuel_'.$i])){$Fichier=$_POST['fichieractuel_'.$i];}
			else{$Fichier="";}
			
			$requeteVerificationExiste="SELECT Id FROM form_document_langue_question WHERE Libelle='".addslashes($_POST['Libelle_'.$i])."' AND Id_Document_Langue=".$_POST['Id_Document_Langue']." AND Suppr=0";
			$requeteInsert="INSERT INTO form_document_langue_question (Id_Origine, Id_Document_Langue, Libelle,TypeReponse, Fichier, Id_Personne_MAJ, Date_MAJ)";
			$requeteInsert.=" VALUES (";
			$requeteInsert.="0";
			$requeteInsert.=",".$_POST['Id_Document_Langue'];
			$requeteInsert.=",'".addslashes($_POST['Libelle_'.$i])."'";
			$requeteInsert.=",'".$_POST['TypeReponse_'.$i]."',";
			$requeteInsert.="'##FICHIER##'";
			$requeteInsert.=",".$_POST['Id_Personne_MAJ'];
			$requeteInsert.=",'".$_POST['Date_MAJ']."'";
			$requeteInsert.=")";
			
			$resultVerificationExiste=mysqli_query($bdd,$requeteVerificationExiste);
			if(mysqli_num_rows($resultVerificationExiste)==0)
			{
				if($Problem==1){
					if($LangueAffichage=="FR"){echo "<script>alert('Il y a eu une erreur lors de la suppression de l ancien du fichier joint (".$SrcProblem.");</script>";}
					else{echo "<script>alert('There was an error deleting the old one from the attached file (".$SrcProblem.");</script>";}
				}
				else{
					//****TRANSFERT FICHIER****
					if($_FILES['fichier_'.$i]['name']!="")
					{
						$tmp_file=$_FILES['fichier_'.$i]['tmp_name'];
						if(!is_uploaded_file($tmp_file)){$SrcProblem.="Le fichier est introuvable.";$Problem=1;}
						else
						{
							//On vérifie la taille du fichiher
							if(filesize($_FILES['fichier_'.$i]['tmp_name'])>$_POST['MAX_FILE_SIZE'])
							{$SrcProblem.="Le fichier est trop volumineux.";$Problem=1;}
							else
							{
								// on copie le fichier dans le dossier de destination
								$name_file=$_FILES['fichier_'.$i]['name'];
								$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
								while(file_exists($DirFichier.$name_file)){$name_file="le_".date('j-m-y')."_a_".date('H-i-s')."_".$name_file;}
								if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
								{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
								else{$Fichier=$name_file;$FichierTransfert=1;}
							}
						}
					}	
					if($Problem==1){
						if($LangueAffichage=="FR"){echo "<script>alert('Il y a eu une erreur lors de la copie du fichier joint (".$SrcProblem."). Veuillez vérifier si celui-ci est bien ajouté dans ce que vous venez de créer.');</script>";}
						else{echo "<script>alert('There was an error deleting the old one from the attached file (".$SrcProblem."). Please check if it is added in what you have just created.');</script>";}
					}
					else
					{
						$resultInsert=mysqli_query($bdd,str_replace("##FICHIER##",$Fichier,$requeteInsert));
					}
				}
			}
			else{
				if($LangueAffichage=="FR"){
					echo "<font class='Erreur'>Ce libellé existe déjà.<br>Vous devez recommencer l'opération.</font>";
				}
				else{
					echo "<font class='Error'>This label already exists. <br> You must repeat the operation.</font>";
				}
			}
		}
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_Document_Langue_QuestionnaireComplet.php">
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
		<input type="hidden" name="Id_Document_Langue" value="<?php echo $_GET['Id_Document_Langue'];?>">
		<input type="hidden" name="Id_Document" value="<?php echo $_GET['Id_Document'];?>">
		<input type="hidden" name="Id_Personne_MAJ" value="<?php echo $IdPersonneConnectee;?>">
		<input type="hidden" name="Date_MAJ" value="<?php echo date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Question";}else{echo "Question";}?></td>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Type de réponse";}else{echo "Type of answer";}?> : </td>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Document";}else{echo "Document";}?></td>
			</tr>
			<?php 
			for($i=1;$i<=15;$i++){
			?>
			<tr class="TitreColsUsers">
				<td>
					<textarea name="Libelle_<?php echo $i;?>" rows="1" cols="90" style="resize:none;"></textarea>
				</td>
				<td>
					<select name="TypeReponse_<?php echo $i;?>" style="width:10;">
						<option value="Note (1 à 6)">Note (1 à 6)</option>
						<option value="Oui/Non">Oui/Non</option>
						<option value="Texte facultatif">Texte facultatif</option>
						<option value="Texte obligatoire" selected>Texte obligatoire</option>
					</select>
				</td>
				<td><input name="fichier_<?php echo $i;?>" type="file"></td>
			</tr>
			<?php 
			}
			?>
			<tr>
				<td colspan=10 align="center">
					<input class="Bouton" type="submit" <?php if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}?>>
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
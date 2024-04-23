<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		function FermerEtRecharger(Id,New){
			window.opener.location = "Checklist.php?Id="+Id+"&New="+New;
			window.close();
		}
		function VerifChamps(langue){
			if(langue=="EN"){
				if(formulaire.chapitre.value==''){alert('You didn\'t enter the chapter.');return false;}
				if(formulaire.ponderation.value=='0'){alert('You didn\'t enter the weighting.');return false;}
				if(formulaire.controle.value==''){alert('You didn\'t enter the control.');return false;}
			}
			else{
				if(formulaire.chapitre.value==''){alert('Vous n\'avez pas renseigné le chapitre.');return false;}
				if(formulaire.ponderation.value=='0'){alert('Vous n\'avez pas renseigné la pondération.');return false;}
				if(formulaire.controle.value==''){alert('Vous n\'avez pas renseigné le contrôle.');return false;}
			}
			return true;
		}
	</script>
</head>
<body>

<?php
session_start();
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
require("../Connexioni.php");
if($_POST){
	if($_POST['Mode']=="A"){
		if($_POST['new']==0){
			if($_POST['id_Version']>0){
				//Incrémentation de la version
				$requete="INSERT INTO trame_cl_version (NumVersion,Id_CL,DateCL,Id_Personne,Id_Prestation,Valide) ";
				$requete.="SELECT (NumVersion+1), Id_CL,'".$DateJour."',".$_SESSION['Id_PersonneTR'].",Id_Prestation,1 FROM trame_cl_version ";
				$requete.="WHERE Id=".$_POST['id_Version'];
				$result=mysqli_query($bdd,$requete);
				$IdCree = mysqli_insert_id($bdd);
				
				//Ajout du contenu de la version
				$requete="INSERT INTO trame_cl_version_contenu (Id_VersionCL,Chapitre,Ordre,Ponderation,Controle,Photo) ";
				$requete.="SELECT ".$IdCree.", Chapitre,Ordre,Ponderation,Controle,Photo FROM trame_cl_version_contenu ";
				$requete.="WHERE Id_VersionCL=".$_POST['id_Version'];
				$result=mysqli_query($bdd,$requete);
				
				//Mise à jour de l'ancienne version : valide=0
				$requete="UPDATE trame_cl_version SET Valide=0 WHERE Id=".$_POST['id_Version'];
				$result=mysqli_query($bdd,$requete);
				
				$requete="SELECT Id FROM trame_cl_version_contenu ";
				$requete.="WHERE Id_VersionCL=".$_POST['id_Version'];
				$result=mysqli_query($bdd,$requete);
				$nbResulta=mysqli_num_rows($result);
				$ordre=$nbResulta+1;
			}
			else{
				//Création d'une nouvelle version
				$requete="INSERT INTO trame_cl_version (NumVersion,Id_CL,DateCL,Id_Personne,Id_Prestation,Valide) ";
				$requete.="VALUES(1,".$_POST['id_CL'].",'".$DateJour."',".$_SESSION['Id_PersonneTR'].",".$_SESSION['Id_PrestationTR'].",1) ";
				$result=mysqli_query($bdd,$requete);
				$IdCree = mysqli_insert_id($bdd);
				$ordre=1;
			}
		}
		else{
			$IdCree=$_POST['id_Version'];
			$requete="SELECT Id FROM trame_cl_version_contenu ";
			$requete.="WHERE Id_VersionCL=".$_POST['id_Version'];
			$result=mysqli_query($bdd,$requete);
			$nbResulta=mysqli_num_rows($result);
			$ordre=$nbResulta+1;
		}
		
		$photo="";
		$type_file=strrchr($_FILES['fichier']['name'], '.');
		$DirFichier="ImagesChecklist/".$IdCree."_".$ordre.$type_file;
		if($_FILES['fichier']['name']!=""){
			$SrcProblem = "";
			$tmp_file=$_FILES['fichier']['tmp_name'];
			if(!is_uploaded_file($tmp_file)){$SrcProblem.="<br>Le fichier est introuvable";$Problem=1;$NomFichier="";}
			else{
				//On vérifie la taille du fichiher
				if(filesize($_FILES['fichier']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
					{$SrcProblem.="<br>Le fichier est trop volumineux";$Problem=1;$NomFichier="";}
				else{
					if(!unlink($DirFichier)){$SrcProblem.="<br>Impossible de supprimer le fichier.";$Problem=1;}
					if(!move_uploaded_file($tmp_file,$DirFichier))
						{$SrcProblem.="<br>Impossible de copier le fichier.";$Problem=1;$NomFichier="";}
					else{
						$photo=$IdCree."_".$ordre.$type_file;
					}
				}
			}
		}
		//Ajout du nouveau controle en dernière position
		$requete="INSERT INTO trame_cl_version_contenu (Id_VersionCL,Chapitre,Ordre,Ponderation,Controle,Photo) ";
		$requete.="VALUES(".$IdCree.",'".addslashes($_POST['chapitre'])."',".$ordre.",".$_POST['ponderation'].",'".addslashes($_POST['controle'])."','".$photo."') ";
		$result=mysqli_query($bdd,$requete);
		
		echo "<script>FermerEtRecharger(".$_POST['id_CL'].",1);</script>";
	}
	elseif($_POST['Mode']=="M"){
		$type_file=strrchr($_FILES['fichier']['name'], '.');
		$DirFichier="ImagesChecklist/".$_POST['id'].$type_file;
		if($_FILES['fichier']['name']!=""){
			$SrcProblem = "";
			$tmp_file=$_FILES['fichier']['tmp_name'];
			if(!is_uploaded_file($tmp_file)){$SrcProblem.="<br>Le fichier est introuvable";$Problem=1;$NomFichier="";}
			else{
				//On vérifie la taille du fichiher
				if(filesize($_FILES['fichier']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
					{$SrcProblem.="<br>Le fichier est trop volumineux";$Problem=1;$NomFichier="";}
				else{
					if(!unlink($DirFichier)){$SrcProblem.="<br>Impossible de supprimer le fichier.";$Problem=1;}
					if(!move_uploaded_file($tmp_file,$DirFichier))
						{$SrcProblem.="<br>Impossible de copier le fichier.";$Problem=1;$NomFichier="";};
				}
			}
		}
		//Si contrôle non modifié alors UPDATE
		if($_POST['oldControle']==$_POST['controle']){
			$requete="UPDATE trame_cl_version_contenu SET ";
			$requete.="Chapitre='".addslashes($_POST['chapitre'])."',";
			$requete.="Ponderation=".$_POST['ponderation']."";
			if($_FILES['fichier']['name']!=""){
				$requete.=",Photo='".$_POST['id'].$type_file."'";
			}
			$requete.=" WHERE Id=".$_POST['id']."";
			$result=mysqli_query($bdd,$requete);
			$new=0;
		}
		else{
			$new=1;
			//Sinon nouvelle version
			if($_POST['new']==0){
				//Incrémentation de la version
				$requete="INSERT INTO trame_cl_version (NumVersion,Id_CL,DateCL,Id_Personne,Id_Prestation,Valide) ";
				$requete.="SELECT (NumVersion+1), Id_CL,'".$DateJour."',".$_SESSION['Id_PersonneTR'].",Id_Prestation,1 FROM trame_cl_version ";
				$requete.="WHERE Id=".$_POST['id_Version'];
				$result=mysqli_query($bdd,$requete);
				$IdCree = mysqli_insert_id($bdd);
				
				//Ajout du contenu de la version sauf le contrôle à modifier
				$requete="INSERT INTO trame_cl_version_contenu (Id_VersionCL,Chapitre,Ordre,Ponderation,Controle,Photo) ";
				$requete.="SELECT ".$IdCree.", Chapitre,Ordre,Ponderation,Controle,Photo FROM trame_cl_version_contenu ";
				$requete.="WHERE Id<>".$_POST['id']." AND Id_VersionCL=".$_POST['id_Version'];
				$result=mysqli_query($bdd,$requete);
				
				//Ajout du controle modifié
				if($_FILES['fichier']['name']!=""){
					$requete="INSERT INTO trame_cl_version_contenu (Id_VersionCL,Chapitre,Ordre,Ponderation,Controle,Photo) ";
					$requete.="SELECT ".$IdCree.", '".addslashes($_POST['chapitre'])."',Ordre,".$_POST['ponderation'].",'".addslashes($_POST['controle'])."','".$_POST['id'].$type_file."' FROM trame_cl_version_contenu ";
					$requete.="WHERE Id=".$_POST['id']." AND Id_VersionCL=".$_POST['id_Version'];
					$result=mysqli_query($bdd,$requete);
				}
				else{
					$requete="INSERT INTO trame_cl_version_contenu (Id_VersionCL,Chapitre,Ordre,Ponderation,Controle,Photo) ";
					$requete.="SELECT ".$IdCree.", '".addslashes($_POST['chapitre'])."',Ordre,".$_POST['ponderation'].",'".addslashes($_POST['controle'])."',Photo FROM trame_cl_version_contenu ";
					$requete.="WHERE Id=".$_POST['id']." AND Id_VersionCL=".$_POST['id_Version'];
					$result=mysqli_query($bdd,$requete);
				}
				
				//Mise à jour de l'ancienne version : valide=0
				$requete="UPDATE trame_cl_version SET Valide=0 WHERE Id=".$_POST['id_Version'];
				$result=mysqli_query($bdd,$requete);
			}
			else{
				//Si déjà nouvelle version
				$requete="UPDATE trame_cl_version_contenu SET ";
				$requete.="Chapitre='".addslashes($_POST['chapitre'])."',";
				$requete.="Ponderation=".$_POST['ponderation'].",";
				$requete.="Controle='".addslashes($_POST['controle'])."'";
				if($_FILES['fichier']['name']!=""){
					$requete.=",Photo='".$_POST['id'].$type_file."'";
				}
				$requete.=" WHERE Id=".$_POST['id']."";
				$result=mysqli_query($bdd,$requete);
			}
		}
		echo "<script>FermerEtRecharger(".$_POST['id_CL'].",".$new.");</script>";
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Id=$_GET['Id'];
	$IdVersion=$_GET['IdVersion'];
	$Id_CL=$_GET['Id_CL'];
	$New=$_GET['New'];
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT Id,Chapitre,Ponderation,Controle,Photo,Ordre FROM trame_cl_version_contenu WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
		}
?>

		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_Checklist.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="id" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<input type="hidden" name="id_Version" value="<?php echo $IdVersion;?>">
		<input type="hidden" name="id_CL" value="<?php echo $Id_CL;?>">
		<input type="hidden" name="oldControle" value="<?php if($_GET['Mode']=="M"){ echo stripslashes($Ligne['Controle']);}?>">
		<input type="hidden" name="new" value="<?php echo $New;?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="30000000">
		<table width="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Chapter";}else{echo "Chapitre";} ?></td>
				<td>
					<input id="chapitre" name="chapitre" size="40px" value="<?php if($_GET['Mode']=="M"){ echo stripslashes($Ligne['Chapitre']);}?>" />
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Weighting";}else{echo "Pondération";} ?></td>
				<td>
					<select id="ponderation" name="ponderation" style="width:50px;">
						<?php
							echo"<option value='0'></option>";
							for($i=1;$i<=5;$i++){
								$selected="";
								if($_GET['Mode']=="M"){
									if($i==$Ligne['Ponderation']){$selected="selected";}
								}
								echo "<option value=".$i." ".$selected.">".$i."</option>";
							}
						?>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Control";}else{echo "Contrôle";} ?></td>
				<td colspan="6">
					<input id="controle" name="controle" size="100px" value="<?php if($_GET['Mode']=="M"){ echo stripslashes($Ligne['Controle']);}?>" />
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Picture";}else{echo "Image";} ?></td>
				<td colspan="6">
					<input name="fichier" type="file">
					<font color="#FF0000" size="-2"><?php if($_SESSION['Langue']=="EN"){echo "File size limit to 3MB.";}else{echo "Limite de taille du fichier à 3 Mo.";} ?></font>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td colspan="6" align="center">
					<input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="M"){if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}}else{if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}}?>">
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		if($New==0){
			//Incrémentation de la version
			$requete="INSERT INTO trame_cl_version (NumVersion,Id_CL,DateCL,Id_Personne,Id_Prestation,Valide) ";
			$requete.="SELECT (NumVersion+1), Id_CL,'".$DateJour."',".$_SESSION['Id_PersonneTR'].",Id_Prestation,1 FROM trame_cl_version ";
			$requete.="WHERE Id=".$IdVersion;
			$result=mysqli_query($bdd,$requete);
			$IdCree = mysqli_insert_id($bdd);
			
			//Ajout du contenu de la version sauf le contrôle à supprimer
			$requete="INSERT INTO trame_cl_version_contenu (Id_VersionCL,Chapitre,Ordre,Ponderation,Controle,Photo) ";
			$requete.="SELECT ".$IdCree.", Chapitre,Ordre,Ponderation,Controle,Photo FROM trame_cl_version_contenu ";
			$requete.="WHERE Id<>".$_GET['Id']." AND Id_VersionCL=".$IdVersion;
			$result=mysqli_query($bdd,$requete);
			
			//Mise à jour de l'ancienne version : valide=0
			$requete="UPDATE trame_cl_version SET Valide=0 WHERE Id=".$IdVersion;
			$result=mysqli_query($bdd,$requete);
		}
		else{
			//Suppression du controle
			$requete="DELETE FROM trame_cl_version_contenu WHERE Id=".$_GET['Id'];
			$result=mysqli_query($bdd,$requete);
		}
		
		echo "<script>FermerEtRecharger(".$Id_CL.",1);</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Ajouter un QCM - Langue - Question - Réponse</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script type="text/javascript">
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}

		function afficherIMG(img){
			var w=open("",'image','weigth=toolbar=no,scrollbars=no,resizable=yes, width=810, height=310');	
			w.document.write("<HTML><BODY onblur=\"window.close();\"><IMG src='"+img+"'>");
			w.document.write("</BODY></HTML>");
			w.focus();
			w.document.close();
		}
		function CheckFichier(){if(formulaire.fichier.value!=''){formulaire.SupprFichier.checked=true;}}
	</script>
</head>
<body>

<?php
if(isset($_POST['fichieractuel'])){$Fichier=$_POST['fichieractuel'];}
else{$Fichier="";}

if($_GET){
	$req="SELECT Id_QCM_Langue FROM form_qcm_langue_question WHERE Id=".$_GET['Id_QCM_Langue_Question'];
	$result=mysqli_query($bdd,$req);
	$row=mysqli_fetch_array($result);
	$Id_QCM_Langue = $row['Id_QCM_Langue'];

	$req="SELECT Id_QCM FROM form_qcm_langue WHERE Id=".$Id_QCM_Langue;
	$result=mysqli_query($bdd,$req);
	$row=mysqli_fetch_array($result);
	$Id_QCM = $row['Id_QCM'];
	$Problem=0;
}
if($_POST){$DirFichier=$CheminFormation."QCM/".$_POST['Id_QCM']."/".$_POST['Id_QCM_Langue']."/";}
else{$DirFichier=$CheminFormation."QCM/".$Id_QCM."/".$Id_QCM_Langue."/";}
if(!file_exists ($DirFichier))
{
	$res=mkdir_ftp($DirFichier,0773);
	if(!$res){echo 'Echec lors de la création des répertoires...';}
}
$RequeteActualiserPageMere="SELECT Id,Id_QCM_Langue_Question,Libelle,Valeur,Fichier,Num FROM form_qcm_langue_question_reponse WHERE Suppr=0 ORDER BY Num";

if($_POST)
{
	$requeteInsert="INSERT INTO form_qcm_langue_question_reponse (Id_Origine, Id_QCM_Langue_Question,Num, Libelle, Valeur, Id_Personne_MAJ, Date_MAJ,Fichier)";
	$requeteInsert.=" VALUES (";
	$requeteInsert.=$_POST['Id'];
	$requeteInsert.=",".$_POST['Id_QCM_Langue_Question'];
	$requeteInsert.=",".$_POST['num'];
	$requeteInsert.=",'".addslashes($_POST['Libelle'])."'";
	$requeteInsert.=",".$_POST['Valeur'];
	$requeteInsert.=",".$_POST['Id_Personne_MAJ'];
	$requeteInsert.=",'".$_POST['Date_MAJ']."',";
	$requeteInsert.="'##FICHIER##'";
	$requeteInsert.=")";
	
	$Problem=0;
	if($_POST['Mode']=="Modif")
	{
		//S'il y avait une fichier
		if(isset($_POST['SupprFichier']))
		{
			if($_POST['SupprFichier'])
			{
				if(!unlink($DirFichier.$_POST['fichieractuel'])){$SrcProblem.="Impossible de supprimer le fichier.";$Problem=1;}
				else{$Fichier="";}
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
					else{$Fichier=$name_file;}
				}
			}
		}
		if($Problem==1){echo "<script>alert('Il y a eu une erreur lors de la copie du fichier joint (".$SrcProblem."). Veuillez vérifier si celui-ci est bien ajouté dans ce que vous venez de créer.');</script>";}
		else{
			if($_POST['Mode']=="Modif")
			{
				$requeteDelete="UPDATE form_qcm_langue_question_reponse SET";
				$requeteDelete.=" Suppr=1";
				$requeteDelete.=" WHERE Id=".$_POST['Id'];
				$resultDelete=mysqli_query($bdd,$requeteDelete);
			}
			$resultInsert=mysqli_query($bdd,str_replace("##FICHIER##",$Fichier,$requeteInsert));
			
			//Modifier l'affichage de la page mère sans la recharger
			echo EcrireCodeRechargerPageMere($RequeteActualiserPageMere,"Liste_Reponses","formulaire");
		}
	}
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
			$result=mysqli_query($bdd,"SELECT Id, Id_QCM_Langue_Question, Libelle, Valeur, Id_Personne_MAJ, Date_MAJ,Fichier,Num  FROM form_qcm_langue_question_reponse WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_QCM_Langue_Question_Reponse.php" >
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
		<input type="hidden" name="Id" value="<?php echo $_GET['Id'];?>">
		<input type="hidden" name="Id_QCM_Langue" value="<?php echo $Id_QCM_Langue;?>">
		<input type="hidden" name="Id_QCM" value="<?php echo $Id_QCM;?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
		<input type="hidden" name="Id_QCM_Langue_Question" value="<?php echo $_GET['Id_QCM_Langue_Question'];?>">
		<input type="hidden" name="Id_Personne_MAJ" value="<?php echo $IdPersonneConnectee;?>">
		<input type="hidden" name="Date_MAJ" value="<?php echo date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));?>">
		<table style="width:95%; height:95%; align:center; class:TableCompetences;">
			<tr class="TitreColsUsers">
				<td><?php if($LangueAffichage=="FR"){echo "N°";}else{echo "N°";}?> : </td>
				<td colspan="3">
					<input onKeyUp="nombre(this)" name="num" id="num" style="width:40px" value="<?php if($Modif){echo $row['Num'];} ?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td colspan="3">
					<textarea name="Libelle" rows="6" cols="100" style="resize:none;"><?php if($Modif){echo stripslashes($row['Libelle']);}?></textarea>
				</td>
			</tr>
			<tr>
			<tr>
				<td><?php if($LangueAffichage=="FR"){echo "Valeur";}else{echo "Value";}?> : </td>
				<td>
					<select name="Valeur">
						<?php
						$Tableau=array('0','1');
						foreach($Tableau as $indice => $valeur)
						{
							echo "<option value='".$valeur."'";
							if($Modif){if($valeur==$row['Valeur']){echo " selected";}}
							echo ">".$valeur."</option>\n";
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="PoliceModif"><?php if($LangueAffichage=="FR"){echo "Fichier";}else{echo "File";}?> : </td>
				<td><input name="fichier" type="file" onChange="CheckFichier();"></td>
			</tr>
			<tr>
				<?php
				if($Modif && $row['Fichier']!="")
				{
				?>
				<td>
					<a class="Info" href="javascript:afficherIMG('<?php echo $DirFichier.$row['Fichier']; ?>')" ><?php if($LangueAffichage=="FR"){echo "Ouvrir";}else{echo "Open";}?></a>
					<input type="hidden" name="fichieractuel" value="<?php echo $row['Fichier'];?>">
				</td>
				<td class="PoliceModif"><input type="checkbox" name="SupprFichier" onClick="CheckFichier();"><?php if($LangueAffichage=="FR"){echo "Supprimer le fichier";}else{echo "Delete file";}?></td>
				<?php
				}
				?>
			</tr>
			<tr>
				<td colspan=2 align=center>
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
		$result=mysqli_query($bdd,"UPDATE form_qcm_langue_question_reponse SET Suppr=1, Id_Personne_MAJ=".$IdPersonneConnectee.", Date_MAJ='".Date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		//Modifier l'affichage de la page mère sans la recharger
		echo EcrireCodeRechargerPageMere($RequeteActualiserPageMere,"Liste_Reponses","formulaire");
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
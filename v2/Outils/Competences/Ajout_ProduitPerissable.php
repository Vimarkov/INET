<html>
<head>
	<title>Ajouter un produit périssable</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script type="text/javascript" src="<?php echo $HTTPServeur;?>Outils/Fonctions_Outils.js"></script>
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
			window.opener.location="Liste_ProduitPerissable.php";
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
$DirFichier="ProduitsPerissables/";

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
		$requeteInsertUpdate="INSERT INTO produit_perrissable (AIMS,Reference,TemperatureMini,TemperatureMaxi,Peremption,FDS)";
		$requeteInsertUpdate.=" VALUES (";
		$requeteInsertUpdate.="'".addslashes($_POST['AIMS'])."','".addslashes($_POST['Reference'])."','".addslashes($_POST['TemperatureMini'])."','".addslashes($_POST['TemperatureMaxi'])."','".addslashes($_POST['Peremption'])."','".addslashes($_POST['FDS'])."'";
		$requeteInsertUpdate.=")";
		
		$resultInsertUpdate=mysqli_query($bdd,$requeteInsertUpdate);
		$IdCree = mysqli_insert_id($bdd);
		
		if($IdCree>0){
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
				if($_FILES['fichier']['name']!=""){
					$requeteUpt="UPDATE produit_perrissable SET";
						$requeteUpt.=" Document='".$Fichier."'";
						$requeteUpt.=" WHERE Id=".$IdCree;
						$resultUpt=mysqli_query($bdd,$requeteUpt);
				}
			}
			
			//FTP
			if($_FILES['fichierFTP']['name']!="")
			{
				$tmp_file=$_FILES['fichierFTP']['tmp_name'];
				if(!is_uploaded_file($tmp_file)){$SrcProblem.="Le fichier est introuvable.";$Problem=1;}
				else
				{
					//On vérifie la taille du fichiher
					if(filesize($_FILES['fichierFTP']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
					{$SrcProblem.="Le fichier est trop volumineux.";$Problem=1;}
					else
					{
						// on copie le fichier dans le dossier de destination
						$name_file=$_FILES['fichierFTP']['name'];
						$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
						while(file_exists($DirFichier.$name_file)){$name_file="le_".date('j-m-y')."_a_".date('H-i-s')."_".$name_file;}
						if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
						{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
						else{$FichierFTP=$name_file;$FichierTransfert=1;}
					}
				}
			}
				
			if($Problem==1){echo "<script>alert('Il y a eu une erreur lors de la copie du fichier joint (".$SrcProblem."). Veuillez vérifier si celui-ci est bien ajouté dans ce que vous venez de créer.');</script>";}
			else
			{
				if($_FILES['fichierFTP']['name']!=""){
					$requeteUpt="UPDATE produit_perrissable SET";
						$requeteUpt.=" FTP='".$FichierFTP."'";
						$requeteUpt.=" WHERE Id=".$IdCree;
						$resultUpt=mysqli_query($bdd,$requeteUpt);
				}
			}
		}
	}
	else
	{	
		
		if(isset($_POST['SupprFichier']))
		{
			if($_POST['SupprFichier'])
			{
				if(!unlink($DirFichier.$_POST['fichieractuelFTP'])){$SrcProblem.="Impossible de supprimer le fichier.";$Problem=1;}
				elseif($FichierTransfert==0){$Fichier="";}
			}
		}
		
		if(isset($_POST['SupprFichierFTP']))
		{
			if($_POST['SupprFichierFTP'])
			{
				if(!unlink($DirFichier.$_POST['fichieractuelFTP'])){$SrcProblem.="Impossible de supprimer le fichier.";$Problem=1;}
				elseif($FichierTransfert==0){$FichierFTP="";}
			}
		}
		
		$requeteInsertUpdate="UPDATE produit_perrissable SET";
		$requeteInsertUpdate.=" AIMS='".addslashes($_POST['AIMS'])."', ";
		$requeteInsertUpdate.=" Reference='".addslashes($_POST['Reference'])."', ";
		$requeteInsertUpdate.=" TemperatureMini='".addslashes($_POST['TemperatureMini'])."', ";
		$requeteInsertUpdate.=" TemperatureMaxi='".addslashes($_POST['TemperatureMaxi'])."', ";
		$requeteInsertUpdate.=" Peremption='".addslashes($_POST['Peremption'])."', ";
		$requeteInsertUpdate.=" FDS='".addslashes($_POST['FDS'])."' ";
		$requeteInsertUpdate.=" WHERE Id=".$_POST['Id'];
		
		$resultInsertUpdate=mysqli_query($bdd,$requeteInsertUpdate);
		
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
			if($_FILES['fichier']['name']!="" || (isset($_POST['SupprFichier']) && $_POST['SupprFichier'])){
				$requeteUpt="UPDATE produit_perrissable SET";
					$requeteUpt.=" Document='".$Fichier."'";
					$requeteUpt.=" WHERE Id=".$_POST['Id'];
					$resultUpt=mysqli_query($bdd,$requeteUpt);
			}
		}
		
		if($_FILES['fichierFTP']['name']!="")
		{
			$tmp_file=$_FILES['fichierFTP']['tmp_name'];
			if(!is_uploaded_file($tmp_file)){$SrcProblem.="Le fichier est introuvable.";$Problem=1;}
			else
			{
				//On vérifie la taille du fichiher
				if(filesize($_FILES['fichierFTP']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
				{$SrcProblem.="Le fichier est trop volumineux.";$Problem=1;}
				else
				{
					// on copie le fichier dans le dossier de destination
					$name_file=$_FILES['fichierFTP']['name'];
					$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
					while(file_exists($DirFichier.$name_file)){$name_file="le_".date('j-m-y')."_a_".date('H-i-s')."_".$name_file;}
					if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
					{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
					else{$FichierFTP=$name_file;$FichierTransfert=1;}
				}
			}
		}
			
		if($Problem==1){echo "<script>alert('Il y a eu une erreur lors de la copie du fichier joint (".$SrcProblem."). Veuillez vérifier si celui-ci est bien ajouté dans ce que vous venez de créer.');</script>";}
		else
		{
			if($_FILES['fichierFTP']['name']!="" || (isset($_POST['SupprFichierFTP']) && $_POST['SupprFichierFTP'])){
				$requeteUpt="UPDATE produit_perrissable SET";
					$requeteUpt.=" FTP='".$FichierFTP."'";
					$requeteUpt.=" WHERE Id=".$_POST['Id'];
					$resultUpt=mysqli_query($bdd,$requeteUpt);
			}
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
			$result=mysqli_query($bdd,"SELECT Id, AIMS,Reference,TemperatureMini,TemperatureMaxi,Peremption,FDS,FTP,Document FROM produit_perrissable WHERE Id=".$_GET['Id']." AND Suppr=0");
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_ProduitPerissable.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $row['Id'];}?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Référence";}else{echo "Reference";}?> : </td>
				<td colspan="3"><input name="Reference" size="70" type="text" value="<?php if($Modif){echo stripslashes($row['Reference']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Famille de produits";}else{echo "Product family";}?> : </td>
				<td colspan="3"><input name="AIMS" size="40" type="text" value="<?php if($Modif){echo stripslashes($row['AIMS']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Température mini de stockage";}else{echo "Storage mini Temperature";}?> : </td>
				<td colspan="3"><input name="TemperatureMini" size="8" type="text" value="<?php if($Modif){echo stripslashes($row['TemperatureMini']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Température maxi de stockage";}else{echo "Storage maxi Temperature";}?> : </td>
				<td colspan="3"><input name="TemperatureMaxi" size="8" type="text" value="<?php if($Modif){echo stripslashes($row['TemperatureMaxi']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Péremption après ouverture";}else{echo "Validity after open";}?> : </td>
				<td colspan="3"><input name="Peremption" size="20" type="text" value="<?php if($Modif){echo stripslashes($row['Peremption']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Fiche de données sécurité (FDS)";}else{echo "Safety Data Sheet <br>(FDS in french)";}?> : </td>
				<td colspan="3"><input name="FDS" size="30" type="text" value="<?php if($Modif){echo stripslashes($row['FDS']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Fiche technique des produits";}else{echo "Technical data sheet <br>(only for Quality – « Update version not followed »)";}?> : </td>
				<td colspan="3">
					<input name="fichierFTP" type="file" onChange="CheckFichierFTP();">
					<?php
					if($Modif){
						if($row['FTP']!="")
						{
						?>
							<br>
							<?php 
								if($_SESSION["Langue"]=="FR"){
									echo "<a class=\"Info\" href=\"".$DirFichier."/".$row['FTP']."\" target=\"_blank\">Ouvrir</a>";
								}
								else{
									echo "<a class=\"Info\" href=\"".$DirFichier."/".$row['FTP']."\" target=\"_blank\">Open</a>";
								}
							?>
							<input type="hidden" name="fichieractuelFTP" value="<?php echo $row['FTP'];?>">
							<br>
							<input type="checkbox" name="SupprFichierFTP" onClick="CheckFichier();">
						<?php if($_SESSION["Langue"]=="FR"){echo "Supprimer le fichier";}else{echo "Delete file";}?>
						<?php
						}
					}
					?>
				</td>
			</tr>
			<tr>
				<td class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Document";}else{echo "Safety Data Sheet Document";} ?> : </td>
				<td width="20%">&nbsp;
					<input name="fichier" type="file" onChange="CheckFichier();">
					<?php
					if($Modif){
						if($row['Document']!="")
						{
						?>
							<br>
							<?php 
								if($_SESSION["Langue"]=="FR"){
									echo "<a class=\"Info\" href=\"".$DirFichier."/".$row['Document']."\" target=\"_blank\">Ouvrir</a>";
								}
								else{
									echo "<a class=\"Info\" href=\"".$DirFichier."/".$row['Document']."\" target=\"_blank\">Open</a>";
								}
							?>
							<input type="hidden" name="fichieractuel" value="<?php echo $row['Document'];?>">
							<br>
							<input type="checkbox" name="SupprFichier" onClick="CheckFichier();">
						<?php if($_SESSION["Langue"]=="FR"){echo "Supprimer le fichier";}else{echo "Delete file";}?>
						<?php
						}
					}
					?>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
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
		$result=mysqli_query($bdd,"UPDATE produit_perrissable SET Suppr=1,Id_Suppr=".$_SESSION['Id_Personne'].", DateSuppr='".date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger();</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
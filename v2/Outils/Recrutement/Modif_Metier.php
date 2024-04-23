<html>
<head>
	<title>Formations - Ajouter un client</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script type="text/javascript">	
		function FermerEtRecharger()
		{
			window.opener.location="Liste_Metier.php";
			window.close();
		}
		function CheckFichier(){if(formulaire.fichier.value!=''){formulaire.SupprFichier.checked=true;}}
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

//RECUPERATION VARIABLES FICHIERS
if(isset($_POST['fichieractuel'])){$Fichier=$_POST['fichieractuel'];}
else{$Fichier="";}
$DirFichier="Documents/";

if($_POST)
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

			$requeteUpt="UPDATE new_competences_metier SET";
			$requeteUpt.=" DocumentFiche='".$Fichier."'";
			$requeteUpt.=" WHERE Id=".$_POST['Id'];
			$resultUpt=mysqli_query($bdd,$requeteUpt);
		}
	}
		
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Modif=false;
	if($_GET['Id']!='0')
	{
		$Modif=True;
		$result=mysqli_query($bdd,"SELECT Id, Libelle,DocumentFiche FROM new_competences_metier WHERE Id=".$_GET['Id']." ");
		$row=mysqli_fetch_array($result);
	}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Modif_Metier.php">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php echo $row['Id'];?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td colspan="3"><?php if($Modif){echo stripslashes($row['Libelle']);}?></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Fichier";}else{echo "File";}?> : </td>
				<td><input name="fichier" type="file" onChange="CheckFichier();"></td>
			</tr>
			<tr>
				<?php
				if($row['DocumentFiche']!="")
				{
				?>
				<td>
					<?php 
						if($LangueAffichage=="FR"){
							echo "<a class=\"Info\" href=\"".$DirFichier."/".$row['DocumentFiche']."\" target=\"_blank\">Ouvrir</a>";
						}
						else{
							echo "<a class=\"Info\" href=\"".$DirFichier."/".$row['DocumentFiche']."\" target=\"_blank\">Open</a>";
						}
					?>
					<input type="hidden" name="fichieractuel" value="<?php echo $row['DocumentFiche'];?>">
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
						if($Modif){if($_SESSION["Langue"]=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}}
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
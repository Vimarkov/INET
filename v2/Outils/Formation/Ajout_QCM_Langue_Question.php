<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - QCM - Langue - Question</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script type="text/javascript">
    	function VerifChamps()
    	{
    		if(document.getElementById('Langue').value=="FR"){
    			if(formulaire.num.value==''){alert('Vous n\'avez pas renseigné le numéro de la question.');return false;}
    			return true;
    		}
    		else{
    			if(formulaire.num.value==''){alert('You did not fill in the question number.');return false;}
    			return true;
    		}
    	}
			
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
		function ModifierCoeffPossible(){
			if(formulaire.Type.value=='Facultatif'){
				document.getElementById('Coefficient').options.length = 0;
				document.getElementById('Coefficient').options[document.getElementById('Coefficient').options.length]=new Option('1','1');
			}
			else{
				document.getElementById('Coefficient').options.length = 0;
				document.getElementById('Coefficient').options[document.getElementById('Coefficient').options.length]=new Option('1','1');
				document.getElementById('Coefficient').options[document.getElementById('Coefficient').options.length]=new Option('2','2');
				document.getElementById('Coefficient').options[document.getElementById('Coefficient').options.length]=new Option('3','3');
			}
		}
	</script>
</head>
<body>

<?php
//RECUPERATION VARIABLES FICHIERS
if(isset($_POST['fichieractuel'])){$Fichier=$_POST['fichieractuel'];}
else{$Fichier="";}
if($_POST){$DirFichier=$CheminFormation."QCM/".$_POST['Id_QCM']."/".$_POST['Id_QCM_Langue']."/";}
else{$DirFichier=$CheminFormation."QCM/".$_GET['Id_QCM']."/".$_GET['Id_QCM_Langue']."/";}
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
	$requeteInsert="INSERT INTO form_qcm_langue_question (Id_Origine, Id_QCM_Langue, Coefficient, Type, Libelle, Fichier, Id_Personne_MAJ, Date_MAJ,Num)";
	$requeteInsert.=" VALUES (";
	$requeteInsert.=$_POST['Id'];
	$requeteInsert.=",".$_POST['Id_QCM_Langue'];
	$requeteInsert.=",".$_POST['Coefficient'];
	$requeteInsert.=",'".$_POST['Type']."'";
	$requeteInsert.=",'".addslashes($_POST['Libelle'])."',";
	$requeteInsert.="'##FICHIER##'";
	$requeteInsert.=",".$_POST['Id_Personne_MAJ'];
	$requeteInsert.=",'".$_POST['Date_MAJ']."'";
	$requeteInsert.=",".$_POST['num']."";
	$requeteInsert.=")";
	
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
				$requeteDelete="UPDATE form_qcm_langue_question SET";
				$requeteDelete.=" Suppr=1";
				$requeteDelete.=" WHERE Id=".$_POST['Id'];
				$resultDelete=mysqli_query($bdd,$requeteDelete);
			}
			$resultInsert=mysqli_query($bdd,str_replace("##FICHIER##",$Fichier,$requeteInsert));
			$Id_New=mysqli_insert_id($bdd);
			$req="INSERT INTO form_qcm_langue_question_reponse (Id_Origine,Id_QCM_Langue_Question,Libelle,Valeur,Id_Personne_MAJ,Date_MAJ) ";
			$req.="SELECT Id_Origine,".$Id_New.",Libelle,Valeur,Id_Personne_MAJ,Date_MAJ ";
			$req.="FROM form_qcm_langue_question_reponse ";
			$req.="WHERE Suppr=0 AND Id_QCM_Langue_Question=".$_POST['Id']."";
			$resultInsert=mysqli_query($bdd,$req);
			
			echo "<script>FermerEtRecharger();</script>";
		}
	}
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
			$result=mysqli_query($bdd,"SELECT Id, Id_QCM_Langue, Coefficient, Type, Libelle, Fichier, Id_Personne_MAJ, Date_MAJ, Num FROM form_qcm_langue_question WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}

?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_QCM_Langue_Question.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php echo $_GET['Id'];?>">
		<input type="hidden" name="Id_QCM_Langue" value="<?php echo $_GET['Id_QCM_Langue'];?>">
		<input type="hidden" name="Id_QCM" value="<?php echo $_GET['Id_QCM'];?>">
		<input type="hidden" name="Id_Personne_MAJ" value="<?php echo $IdPersonneConnectee;?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
		<input type="hidden" name="Date_MAJ" value="<?php echo date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));?>">
		<table style="width:95%; height:95%; align:center; class:TableCompetences;">
			<tr class="TitreColsUsers">
				<td width="75">N° : </td>
				<td>
					<input onKeyUp="nombre(this)" name="num" id="num" style="width:40px" value="<?php if($Modif){echo $row['Num'];} ?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td width="75">Coefficient : </td>
				<td>
					<select name="Coefficient" id="Coefficient">
						<?php
						for($i=1;$i<=3;$i++)
						{
							echo "<option value='".$i."'";
							if($Modif){if($i==$row['Coefficient']){echo " selected";}}
							echo ">".$i."</option>\n";
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Type : </td>
				<td>
					<select name="Type" id="Type" onchange="ModifierCoeffPossible()">
						<?php
						$Tableau=array('Obligatoire','Facultatif');
						foreach($Tableau as $indice => $valeur)
						{
							echo "<option value='".$valeur."'";
							if($Modif){if($valeur==$row['Type']){echo " selected";}}
							echo ">".$valeur."</option>\n";
						}
						?>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td>
					<textarea name="Libelle" rows="4" cols="65" style="resize:none;"><?php if($Modif){echo stripslashes($row['Libelle']);}?></textarea>
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
					<a class="Info" href="javascript:afficherIMG('<?php echo $DirFichier.$row['Fichier']; ?>')"><?php if($LangueAffichage=="FR"){echo "Ouvrir";}else{echo "Open";}?></a>
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
		$result=mysqli_query($bdd,"UPDATE form_qcm_langue_question SET Suppr=1, Id_Personne_MAJ=".$IdPersonneConnectee.", Date_MAJ='".Date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger();</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
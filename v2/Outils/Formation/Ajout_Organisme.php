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
		function VerifChamps(){
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseign� le libell�.');return false;}
				else{return true;}
			}
			else{
				if(formulaire.Libelle.value==''){alert('You did not fill in the label.');return false;}
				else{return true;}
			}
		}
			
		function FermerEtRecharger(){
			opener.location.reload();
			window.close();
		}
	</script>
</head>
<body>

<?php
//RECUPERATION VARIABLES FICHIERS
if(isset($_POST['fichieractuel'])){$Fichier=$_POST['fichieractuel'];}
else{$Fichier="";}
$SrcProblem="";
$Problem=0;
$FichierTransfert=0;

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
		$requeteVerificationExiste="SELECT Id FROM form_organisme WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Id_Plateforme=".$_POST['Id_Plateforme'];
		$requeteInsertUpdate="INSERT INTO form_organisme (Id_Plateforme, Libelle, Adresse,Telephone,Id_Personne_MAJ,Date_MAJ)";
		$requeteInsertUpdate.=" VALUES (";
		$requeteInsertUpdate.=$_POST['Id_Plateforme'];
		$requeteInsertUpdate.=",'".addslashes($_POST['Libelle'])."'";
		$requeteInsertUpdate.=",'".addslashes($_POST['Adresse'])."' ";
		$requeteInsertUpdate.=",'".addslashes($_POST['Telephone'])."' ";
		$requeteInsertUpdate.=", ".$IdPersonneConnectee." ";
		$requeteInsertUpdate.=", '".date('Y-m-d')."'";
		$requeteInsertUpdate.=")";
	}
	else
	{		
		$requeteVerificationExiste="SELECT Id FROM form_organisme WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Id_Plateforme=".$_POST['Id_Plateforme']." AND Id!=".$_POST['Id'];
		$requeteInsertUpdate="UPDATE form_organisme SET";
		$requeteInsertUpdate.=" Libelle='".addslashes($_POST['Libelle'])."'";
		$requeteInsertUpdate.=", Id_Plateforme=".$_POST['Id_Plateforme'];
		$requeteInsertUpdate.=", Adresse='".addslashes($_POST['Adresse'])."'";
		$requeteInsertUpdate.=", Telephone='".addslashes($_POST['Telephone'])."'";
		$requeteInsertUpdate.=", Id_Personne_MAJ=".$IdPersonneConnectee."";
		$requeteInsertUpdate.=", Date_MAJ='".date('Y-m-d')."'";
		$requeteInsertUpdate.=" WHERE Id=".$_POST['Id'];
	}
	
	$resultVerificationExiste=mysqli_query($bdd,$requeteVerificationExiste);
	if(mysqli_num_rows($resultVerificationExiste)==0){
		$resultInsertUpdate=mysqli_query($bdd,$requeteInsertUpdate);
		echo "<script>FermerEtRecharger();</script>";
	}
	else{echo "<font class='Erreur'>Ce libell� existe d�j�.<br>Vous devez recommencer l'op�ration.</font>";}
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
			$result=mysqli_query($bdd,"SELECT Id, Id_Plateforme, Libelle, Adresse,Telephone FROM form_organisme WHERE Id=".$_GET['Id']." AND Suppr=0");
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Organisme.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $row['Id'];}?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
		<table style="width:95%; height:95%; align:center; class:TableCompetences;">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Unit� d'exploitation";}else{echo "Operating unit";}?> : </td>
				<td>
					<select name="Id_Plateforme">
						<?php
						$resultPlateforme=mysqli_query($bdd,"SELECT DISTINCT Id_Plateforme, 
						(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle 
						FROM new_competences_personne_poste_plateforme 
						WHERE Id_Poste 
						IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.") 
						AND Id_Personne=".$IdPersonneConnectee." ORDER BY Libelle");
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
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Libell�";}else{echo "Wording";}?> : </td>
				<td colspan="3"><input name="Libelle" size="50" type="text" value="<?php if($Modif){echo $row['Libelle'];}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Adresse";}else{echo "Address";}?> : </td>
				<td><textarea name="Adresse" rows="4" cols="50" style="resize:none"><?php if($Modif){echo stripslashes($row['Adresse']);}?></textarea></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "T�l�phone";}else{echo "Phone";}?> : </td>
				<td colspan="3"><input name="Telephone" size="20" type="text" value="<?php if($Modif){echo $row['Telephone'];}?>"></td>
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
		$result=mysqli_query($bdd,"UPDATE form_organisme SET Suppr=1,Id_Personne_MAJ=".$IdPersonneConnectee.", Date_MAJ='".date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger();</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Lib�ration des r�sultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
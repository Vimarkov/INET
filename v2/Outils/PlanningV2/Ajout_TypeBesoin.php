<html>
<head>
	<title>Formations - Ajouter un client</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
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
			
		function FermerEtRecharger(Menu)
		{
			window.opener.location="Liste_TypeBesoin.php?Menu="+Menu;
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

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
		$requeteInsertUpdate="INSERT INTO rh_typebesoin (Libelle, LibelleEN, ServiceConcerne)";
		$requeteInsertUpdate.=" VALUES (";
		$requeteInsertUpdate.="'".addslashes($_POST['Libelle'])."',";
		$requeteInsertUpdate.="'".addslashes($_POST['LibelleEN'])."',";
		$requeteInsertUpdate.="'".$_POST['serviceConcerne']."'";
		$requeteInsertUpdate.=")";
	}
	else
	{		
		$requeteInsertUpdate="UPDATE rh_typebesoin SET";
		$requeteInsertUpdate.=" Libelle='".addslashes($_POST['Libelle'])."', ";
		$requeteInsertUpdate.=" LibelleEN='".addslashes($_POST['LibelleEN'])."', ";
		$requeteInsertUpdate.=" ServiceConcerne='".addslashes($_POST['serviceConcerne'])."' ";
		$requeteInsertUpdate.=" WHERE Id=".$_POST['Id'];
	}
	echo $requeteInsertUpdate;
	$resultInsertUpdate=mysqli_query($bdd,$requeteInsertUpdate);
	echo "<script>FermerEtRecharger(".$_POST['Menu'].");</script>";
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
			$result=mysqli_query($bdd,"SELECT Id, Libelle, LibelleEN, ServiceConcerne FROM rh_typebesoin WHERE Id=".$_GET['Id']." AND Suppr=0");
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_TypeBesoin.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $row['Id'];}?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td colspan="3"><input name="Libelle" size="40" type="text" value="<?php if($Modif){echo stripslashes($row['Libelle']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Libellé anglais";}else{echo "Wording english";}?> : </td>
				<td colspan="3"><input name="LibelleEN" size="40" type="text" value="<?php if($Modif){echo stripslashes($row['LibelleEN']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Service concerné";}else{echo "Service concerned";}?> : </td>
				<td colspan="3">
					<select name="serviceConcerne">
						<option value="Accueil" <?php if($Modif){if($row['ServiceConcerne']=='Accueil'){echo "selected";}}else{echo "selected";} ?> ><?php if($_SESSION["Langue"]=="FR"){echo "Accueil";}else{echo "Welcome";} ?></option>
						<option value="Moyens généraux" <?php if($Modif){if($row['ServiceConcerne']=='Moyens généraux'){echo "selected";}} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Moyens généraux";}else{echo "General options";} ?></option>
					</select>
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
		$result=mysqli_query($bdd,"UPDATE rh_typebesoin SET Suppr=1,Id_Suppr=".$_SESSION['Id_Personne'].", DateSuppr='".date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger(".$_GET['Menu'].");</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
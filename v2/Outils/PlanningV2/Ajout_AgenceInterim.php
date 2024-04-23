<html>
<head>
	<title>Formations - Ajouter un client</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script type="text/javascript">
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
				if(formulaire.coeffGestion.value==''){alert('Vous n\'avez pas renseigné le coeff de gestion.');return false;}
				if(formulaire.coeffDelegation.value==''){alert('Vous n\'avez pas renseigné le coeff de délégation.');return false;}
			}
			else{
				if(formulaire.Libelle.value==''){alert('You did not fill in the label.');return false;}
				if(formulaire.coeffGestion.value==''){alert('You did not fill in the coefficient of management.');return false;}
				if(formulaire.coeffDelegation.value==''){alert('You did not fill in the coefficient of delegation.');return false;}
			}
			return true;
		}
		
		function FermerEtRecharger(Menu)
		{
			window.opener.location="Liste_AgenceInterim.php?Menu="+Menu;
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
		$requeteInsertUpdate="INSERT INTO rh_agenceinterim (Libelle,CoeffGestion,CoeffDelegation)";
		$requeteInsertUpdate.=" VALUES (";
		$requeteInsertUpdate.="'".addslashes($_POST['Libelle'])."',";
		$requeteInsertUpdate.="".$_POST['coeffGestion'].",".$_POST['coeffDelegation']."";
		$requeteInsertUpdate.=")";
	}
	else
	{		
		$requeteInsertUpdate="UPDATE rh_agenceinterim SET";
		$requeteInsertUpdate.=" Libelle='".addslashes($_POST['Libelle'])."', ";
		$requeteInsertUpdate.=" CoeffGestion=".$_POST['coeffGestion'].", ";
		$requeteInsertUpdate.=" CoeffDelegation=".$_POST['coeffDelegation']." ";
		$requeteInsertUpdate.=" WHERE Id=".$_POST['Id'];
	}
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
			$result=mysqli_query($bdd,"SELECT Id, Libelle, CoeffGestion, CoeffDelegation FROM rh_agenceinterim WHERE Id=".$_GET['Id']." AND Suppr=0");
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_AgenceInterim.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $row['Id'];}?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td colspan="3"><input name="Libelle" size="30" type="text" value="<?php if($Modif){echo stripslashes($row['Libelle']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Coefficient de gestion";}else{echo "Coefficient of management";}?> : </td>
				<td colspan="3"><input onKeyUp="nombre(this)" name="coeffGestion" id="coeffGestion" size="8" type="text" value="<?php if($Modif){echo stripslashes($row['CoeffGestion']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Coefficient de délégation";}else{echo "Coefficient of delegation";}?> : </td>
				<td colspan="3"><input onKeyUp="nombre(this)" name="coeffDelegation" id="coeffDelegation" size="8" type="text" value="<?php if($Modif){echo stripslashes($row['CoeffDelegation']);}?>"></td>
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
		$result=mysqli_query($bdd,"UPDATE rh_agenceinterim SET Suppr=1,Id_Suppr=".$_SESSION['Id_Personne'].", DateSuppr='".date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger(".$_GET['Menu'].");</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
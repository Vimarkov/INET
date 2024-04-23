<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Tools - Lieu</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps()
		{
			if(formulaire.dateCloture.value==''){alert('Vous n\'avez pas renseigné la date de clôture.');return false;}
			else{return true;}
		}
		
		function FermerEtRecharger()
		{
			window.opener.location="Liste_ClotureCampagne.php";
			window.close();
		}
	</script>
</head>
<body>

<?php
require("../Connexioni.php");
require_once("../Fonctions.php");

Ecrire_Code_JS_Init_Date();

$TablePrincipale="epe_cloturecampagne";
$RequeteInsertUpdate="";

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
		$RequeteInsertUpdate="
			INSERT INTO "
				.$TablePrincipale."
			(
				Annee,
				DateCloture
			)
			VALUES
			(
				".$_POST['annee'].",
				'".TrsfDate_($_POST['dateCloture'])."'
			);";
	}
	elseif($_POST['Mode']=="Modif")
	{
		$RequeteInsertUpdate="
			UPDATE "
				.$TablePrincipale."
			SET
				DateCloture='".TrsfDate_($_POST['dateCloture'])."'
			WHERE
				Annee=".$_POST['annee'].";";
	}
	$ResultInsertUpdate=mysqli_query($bdd,$RequeteInsertUpdate);
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Modif=false;
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Annee']!='0')
		{
			$Modif=true;
			$Result=mysqli_query($bdd,"SELECT Id, DateCloture FROM ".$TablePrincipale." WHERE Annee=".$_GET['Annee'].";");
			$Row=mysqli_fetch_array($Result);
		}
?>
		<form id="formulaire" method="POST" action="" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="annee" value="<?php echo $_GET['Annee']; ?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td><?php if($LangueAffichage=="FR"){echo "Date clôture";}else{echo "Closing date";}?> : </td>
				<td><input name="dateCloture" size="15" type="date" value="<?php if($Modif){echo AfficheDateFR($Row['DateCloture']);}?>"></td>
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
		$Result=mysqli_query($bdd,"DELETE FROM ".$TablePrincipale." WHERE Annee=".$_GET['Annee']);
		echo "<script>FermerEtRecharger();</script>";
	}
	if($_GET['Annee']!='0'){mysqli_free_result($Result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
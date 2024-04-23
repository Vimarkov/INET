<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Tools - Lieu</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script>
		function VerifChamps()
		{
			if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
			else{return true;}
		}
		function FermerEtRecharger()
		{
			window.opener.location="Liste_Domaine.php";
			window.close();
		}
	</script>
</head>
<body>

<?php
require("../Connexioni.php");
require_once("../Fonctions.php");

$RequeteInsertUpdate="";

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{

		$RequeteInsertUpdate="
			INSERT INTO recrut_domaine
			(
				Libelle
			)
			VALUES
			(
				'".addslashes($_POST['Libelle'])."'
			);";
		  $ResultInsertUpdate=mysqli_query($bdd,$RequeteInsertUpdate);
	}
	elseif($_POST['Mode']=="Modif")
	{

		$RequeteInsertUpdate="
			UPDATE recrut_domaine
			SET
				Libelle='".addslashes($_POST['Libelle'])."'
			WHERE
				Id='".$_POST['Id']."';";

	    $ResultInsertUpdate=mysqli_query($bdd,$RequeteInsertUpdate);
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
			$Modif=true;
			$Result=mysqli_query($bdd,"SELECT Id, Libelle FROM recrut_domaine WHERE Id='".$_GET['Id']."';");
			$Row=mysqli_fetch_array($Result);
		}
?>
		<form id="formulaire" method="POST" action="" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $Row['Id'];}?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td><input name="Libelle" size="75" type="text" value="<?php if($Modif){echo $Row['Libelle'];}?>"></td>
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
		$Result=mysqli_query($bdd,"UPDATE recrut_domaine SET Suppr=1 WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger();</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($Result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
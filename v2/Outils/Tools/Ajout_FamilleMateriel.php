<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Tools - Famille de matériel</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script>
		function VerifChamps()
		{
			if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
			else{return true;}
		}
	</script>
</head>
<body>

<?php
require("../Connexioni.php");
require_once("../Fonctions.php");

$TablePrincipale="tools_famillemateriel";
$RequeteInsertUpdate="";

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
	    $Result=mysqli_query($bdd,"SELECT Id FROM ".$TablePrincipale." WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Id_TypeMateriel='".$_POST['Id_TypeMateriel']."'");
		if(mysqli_num_rows($Result)==0)
		{
		    $RequeteInsertUpdate="
				INSERT INTO "
					.$TablePrincipale."
				(
					Id_TypeMateriel,
					Libelle
				)
				VALUES
				(
					'".$_POST['Id_TypeMateriel']."',
					'".addslashes($_POST['Libelle'])."'
				);";
		}
	}
	elseif($_POST['Mode']=="Modif")
	{
	    $Result=mysqli_query($bdd,"SELECT Id FROM ".$TablePrincipale." WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Id_TypeMateriel='".$_POST['Id_TypeMateriel']."' AND Id!=".$_POST['Id']);
		if(mysqli_num_rows($Result)==0)
		{
		    $RequeteInsertUpdate="
				UPDATE "
					.$TablePrincipale."
				SET
					Id_TypeMateriel='".$_POST['Id_TypeMateriel']."',
					Libelle='".addslashes($_POST['Libelle'])."'
				WHERE
					Id='".$_POST['Id']."';";
		}
	}
	
	if($RequeteInsertUpdate != "")
	{
	    $ResultInsertUpdate=mysqli_query($bdd,$RequeteInsertUpdate);
	    echo "<script>FermerEtRecharger('FamilleMateriel');</script>";
	}
	else
	{
	    echo "<font class='Erreur'>Ce libellé existe déjà.<br>Vous devez recommencer l'opération.</font>";
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
			$Result=mysqli_query($bdd,"SELECT Id, Id_TypeMateriel, Libelle FROM ".$TablePrincipale." WHERE Id='".$_GET['Id']."';");
			$Row=mysqli_fetch_array($Result);
		}
?>
		<form id="formulaire" method="POST" action="" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $Row['Id'];}?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Type de matériel";}else{echo "Kind of material";}?> : </td>
				<td>
					<select name="Id_TypeMateriel">
					<?php
					$RequeteTypeMateriel="
						SELECT
							Id,
							Libelle
						FROM
							tools_typemateriel
						WHERE
							Suppr=0
						ORDER BY
							Libelle ASC";
					$ResultTypeMateriel=mysqli_query($bdd,$RequeteTypeMateriel);
					while($RowTypeMateriel=mysqli_fetch_array($ResultTypeMateriel))
					{
						echo "<option value='".$RowTypeMateriel['Id']."'";
						if($Modif){if($Row['Id_TypeMateriel']==$RowTypeMateriel['Id']){echo " selected";}}
						echo ">".$RowTypeMateriel['Libelle']."</option>";
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
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
		$Result=mysqli_query($bdd,"UPDATE ".$TablePrincipale." SET Suppr=1 WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger('FamilleMateriel');</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($Result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
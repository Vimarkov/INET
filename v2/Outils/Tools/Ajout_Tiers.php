<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Tools - Tiers</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script>
		function VerifChamps()
		{
			if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
			else{return true;}
		}
		function FermerEtRecharger2(Type){
			window.opener.location="Liste_Tiers.php?Type="+Type;
			window.close();
		}
	</script>
</head>
<body>

<?php
require("../Connexioni.php");
require_once("../Fonctions.php");

$TablePrincipale="tools_tiers";
$RequeteInsertUpdate="";

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
	    $Result=mysqli_query($bdd,"SELECT Id FROM ".$TablePrincipale." WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Type='".$_POST['Type']."'");
		if(mysqli_num_rows($Result)==0)
		{
		    $RequeteInsertUpdate="
				INSERT INTO "
					.$TablePrincipale."
				(
					Type,
					Libelle,
					Adresse,
					Contact,
					TelFixe,
					TelMobile,
					Fax,
					Email
				)
				VALUES
				(
					'".$_POST['Type']."',
					'".addslashes($_POST['Libelle'])."',
					'".addslashes($_POST['Adresse'])."',
					'".addslashes($_POST['Contact'])."',
					'".addslashes($_POST['TelFixe'])."',
					'".addslashes($_POST['TelMobile'])."',
					'".addslashes($_POST['Fax'])."',
					'".addslashes($_POST['Email'])."'
				);";
		}
	}
	elseif($_POST['Mode']=="Modif")
	{
	    $Result=mysqli_query($bdd,"SELECT Id FROM ".$TablePrincipale." WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Type='".$_POST['Type']."' AND Id!=".$_POST['Id']);
		if(mysqli_num_rows($Result)==0)
		{
		    $RequeteInsertUpdate="
				UPDATE "
					.$TablePrincipale."
				SET
					Type='".$_POST['Type']."',
					Libelle='".addslashes($_POST['Libelle'])."',
					Adresse='".addslashes($_POST['Adresse'])."',
					Contact='".addslashes($_POST['Contact'])."',
					TelFixe='".addslashes($_POST['TelFixe'])."',
					TelMobile='".addslashes($_POST['TelMobile'])."',
					Fax='".addslashes($_POST['Fax'])."',
					Email='".addslashes($_POST['Email'])."'
				WHERE
					Id='".$_POST['Id']."';";
		}
	}
	
	if($RequeteInsertUpdate != "")
	{
	    $ResultInsertUpdate=mysqli_query($bdd,$RequeteInsertUpdate);
	    echo "<script>FermerEtRecharger2('".$_POST['Type']."');</script>";
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
			$Requete="
				SELECT
					Id,
					Libelle,
					Adresse,
					Contact,
					TelFixe,
					TelMobile,
					Fax,
					Email
				FROM "
					.$TablePrincipale."
				WHERE
					Id='".$_GET['Id']."';";
			$Result=mysqli_query($bdd,$Requete);
			$Row=mysqli_fetch_array($Result);
		}
?>
		<form id="formulaire" method="POST" action="" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Type" value="<?php echo $_GET['Type']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $Row['Id'];}?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td><input name="Libelle" size="50" type="text" value="<?php if($Modif){echo stripslashes($Row['Libelle']);}?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Adresse";}else{echo "Address";}?> : </td>
				<td><textarea name="Adresse" rows="5" cols="50" style="resize: none;"><?php if($Modif){echo stripslashes($Row['Adresse']);}?></textarea></td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Contact (personne)";}else{echo "Contact (Name)";}?> : </td>
				<td><input name="Contact" size="50" type="text" value="<?php if($Modif){echo stripslashes($Row['Contact']);}?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Tél. fixe";}else{echo "Tel.";}?> : </td>
				<td><input name="TelFixe" size="50" type="text" value="<?php if($Modif){echo stripslashes($Row['TelFixe']);}?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Tél. mobile";}else{echo "Mobile";}?> : </td>
				<td><input name="TelMobile" size="50" type="text" value="<?php if($Modif){echo stripslashes($Row['TelMobile']);}?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Fax";}else{echo "Fax";}?> : </td>
				<td><input name="Fax" size="50" type="text" value="<?php if($Modif){echo stripslashes($Row['Fax']);}?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Courriel";}else{echo "Email";}?> : </td>
				<td><input name="Email" size="50" type="text" value="<?php if($Modif){echo stripslashes($Row['Email']);}?>"></td>
			</tr>
			<tr>
				<td colspan="2" align="center">
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
		echo "<script>FermerEtRecharger2('".$_GET['Type']."');</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($Result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
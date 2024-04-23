<html>
<head>
	<title>Extranet de la société Assistance Aéronautique et Aérospatiale</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps(){
			if(formulaire.ata.value==''){alert('Vous n\'avez pas renseigné l\'ATA.');return false;}
			else{
				if(formulaire.sousATA.value==''){alert('Vous n\'avez pas renseigné le sous-ATA.');return false;}
				else{
					return true;
				}
			}

		}
		function FermerEtRecharger(){
			opener.location.reload();
			window.close();
		}
		
		function nombre(champ){
			var chiffres = new RegExp("[0-9\.]"); /* Modifier pour : var chiffres = new RegExp("[0-9]"); */
			var verif;
			var points = 0; /* Supprimer cette ligne */

			for(x = 0; x < champ.value.length; x++)
			{
			verif = chiffres.test(champ.value.charAt(x));
			if(champ.value.charAt(x) == "."){points++;} /* Supprimer cette ligne */
			if(points > 1){verif = false; points = 1;} /* Supprimer cette ligne */
			if(verif == false){champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;}
			}
		}
	</script>
</head>
<body>

<?php
session_start();
require("../../Connexioni.php");
if($_POST){
	if($_POST['Mode']=="A"){
		$droit="";
		$requete="INSERT INTO sp_atasousata (ATA,SousATA) VALUES ('".addslashes($_POST['ata'])."','".addslashes($_POST['sousATA'])."')";
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE sp_atasousata SET ";
		$requete.="ATA='".addslashes($_POST['ata'])."',";
		$requete.="SousATA='".addslashes($_POST['sousATA'])."'";
		$requete.=" WHERE Id=".$_POST['IdATA'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT Id, ATA,SousATA FROM sp_atasousata WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
		}
?>

		<form id="formulaire" method="POST" action="Ajout_ATA.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="IdATA" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<table width="95%" height="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td>ATA </td>
				<td>
					<input onKeyUp="nombre(this)" type="texte" name="ata" id="ata" size="5" value="<?php if($_GET['Mode']=="M"){echo $Ligne['ATA'];}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td>Sous-ATA </td>
				<td>
					<input onKeyUp="nombre(this)" type="texte" name="sousATA" id="sousATA" size="5" value="<?php if($_GET['Mode']=="M"){echo $Ligne['SousATA'];}?>">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="M"){echo "Valider";}else{echo "Ajouter";}?>">
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$requete="DELETE FROM sp_atasousata WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
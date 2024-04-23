<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps(langue){
			if(langue=="EN"){
				if(formulaire.libelle.value==''){alert('You didn\'t enter the wording.');return false;}
			}
			else{
				if(formulaire.libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
			}
			return true;
		}
		function FermerEtRecharger(){
			opener.location.reload();
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
if($_POST){
	if($_POST['Mode']=="A"){
		$droit="";
		$requete="INSERT INTO trame_ponderation (Libelle,Description,Id_Prestation) VALUES ('".addslashes($_POST['libelle'])."','".addslashes($_POST['description'])."',".$_SESSION['Id_PrestationTR'].") ";
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE trame_ponderation SET ";
		$requete.="Libelle='".addslashes($_POST['libelle'])."',";
		$requete.="Description='".addslashes($_POST['description'])."'";
		$requete.=" WHERE Id=".$_POST['id'];
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
			$result=mysqli_query($bdd,"SELECT Id, Libelle, Description FROM trame_ponderation WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
		}
?>

		<form id="formulaire" method="POST" action="Ajout_Ponderation.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="id" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<table width="95%" height="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "Wording";}else{echo "Libellé";} ?></td>
				<td>
					<input type="texte" name="libelle" id="libelle" size="20" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Libelle'];}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "Description";}else{echo "Description";} ?></td>
				<td>
					<input type="texte" name="description" id="description" size="60" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Description'];}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td colspan="2" align="center">
					<input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="M"){if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}}else{if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}}?>">
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$requete="UPDATE trame_ponderation SET ";
		$requete.="Supprime=true ";
		$requete.=" WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
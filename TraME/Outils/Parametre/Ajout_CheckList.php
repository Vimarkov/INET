<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps(langue){
			if(langue=="EN"){
				if(formulaire.libelle.value==''){alert('You didn\'t enter the wording.');return false;}
				if(formulaire.numDQ.value==''){alert('You didn\'t enter the n� D-0833.');return false;}
			}
			else{
				if(formulaire.libelle.value==''){alert('Vous n\'avez pas renseign� le libell�.');return false;}
				if(formulaire.numDQ.value==''){alert('Vous n\'avez pas renseign� le n� D-0833.');return false;}
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
		$requete="INSERT INTO trame_checklist (Libelle,NumDQ,Id_Prestation) VALUES ('".addslashes($_POST['libelle'])."','".addslashes($_POST['numDQ'])."',".$_SESSION['Id_PrestationTR'].") ";
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE trame_checklist SET ";
		$requete.="Libelle='".addslashes($_POST['libelle'])."', ";
		$requete.="NumDQ='".addslashes($_POST['numDQ'])."' ";
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
			$result=mysqli_query($bdd,"SELECT Id, Libelle,NumDQ FROM trame_checklist WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
		}
?>

		<form id="formulaire" method="POST" action="Ajout_CheckList.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="id" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<table width="95%" height="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "Wording";}else{echo "Libell�";} ?> </td>
				<td>
					<input type="texte" name="libelle" id="libelle" size="60" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Libelle'];}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "N� D-0833";}else{echo "N� D-0833";} ?> </td>
				<td>
					<input type="texte" name="numDQ" id="numDQ" size="10" value="<?php if($_GET['Mode']=="M"){echo $Ligne['NumDQ'];}?>">
				</td>
			</tr>
			<tr>
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
		$requete="UPDATE trame_checklist SET ";
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
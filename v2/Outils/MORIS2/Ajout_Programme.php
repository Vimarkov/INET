<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script>
		function FermerEtRecharger(){
			opener.location.reload();
			window.close();
		}
	</script>
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/webforms2-0/webforms2-p.js"></script>	
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script src="../JS/colorpicker.js"></script>
</head>
<body>
<?php

session_start();
require("../Connexioni.php");
require("../Fonctions.php");

if($_POST){
	if($_POST['nomProgramme']<>""){
		if($_POST['Mode']=="A"){
			$requete="INSERT INTO moris_programme (Libelle) VALUES ('".addslashes($_POST['nomProgramme'])."') ";
			$result=mysqli_query($bdd,$requete);
		}
		elseif($_POST['Mode']=="M"){
			$requete="UPDATE moris_programme 
					SET Libelle='".addslashes($_POST['nomProgramme'])."'
					WHERE Id=".$_POST['id']." ";
			$result=mysqli_query($bdd,$requete);
		}
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT Id, Libelle FROM moris_programme WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
		}
?>

		<form id="formulaire" method="POST" action="Ajout_Programme.php" >
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="id" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<table width="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Name";}else{echo "Nom";} ?> </td>
				<td colspan="3">
					<input type="texte" name="nomProgramme" id="nomProgramme" size="30" value="<?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['Libelle']);}?>">
				</td>
			</tr>
			<tr><td height="5px"></td></tr>
			<tr class="TitreColsUsers">
				<td colspan="6" align="center">
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
		$requete="UPDATE moris_programme SET Suppr=1 WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
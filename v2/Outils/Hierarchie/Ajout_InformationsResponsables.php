<html>
<head>
	<title>Compétences - Poste</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>	
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();	//require("../VerifPage.php");
require("../Connexioni.php");

if($_POST)
{
	$result=mysqli_query($bdd,"UPDATE new_rh_etatcivil SET EmailPro='".addslashes($_POST['Libelle'])."' WHERE Id=".$_POST['Id']);
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	if($_GET['Mode']=="S")
	{
		$req="DELETE FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_GET['Id_Personne'];
		$result=mysqli_query($bdd,$req);
		
		$req="DELETE FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$_GET['Id_Personne'];
		$result=mysqli_query($bdd,$req);
		
		echo "<script>opener.location.reload();</script>";
		echo "<script>window.close();</script>";
	}
	else{
		if($_GET['Id_Personne']!='0')
		{
			$result=mysqli_query($bdd,"SELECT Id, Nom, Prenom, EmailPro FROM new_rh_etatcivil WHERE Id=".$_GET['Id_Personne']);
			$row=mysqli_fetch_array($result);
		}
	?>
		<form id="formulaire" method="POST" action="Ajout_InformationsResponsables.php">
		<input type="hidden" name="Id" value="<?php echo $row['Id'];?>">
		<table style="width:95%; align:center;">
			<tr class="TitreColsUsers">
				<td class="Libelle"8 colspan="2" ><?php echo $row['Nom']." ".$row['Prenom']; ?></td>
			</tr>
			<tr class="TitreColsUsers">
				<td>Email Pro : </td>
				<td><input name="Libelle" size="30" type="text" value="<?php echo $row['EmailPro'];?>"></td>
				<td><input class="Bouton" type="submit" value="<?php echo "Valider";?>"></td>
			</tr>
		</table>
		</form>
<?php
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
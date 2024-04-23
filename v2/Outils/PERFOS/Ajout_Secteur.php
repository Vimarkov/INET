<html>
<head>
	<title>SQCDPF - Secteurs</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps()
		{
			if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
			else{return true;}
		}
			
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
	if($_POST['Mode']=="Ajout")
	{
		$result=mysqli_query($bdd,"SELECT Id FROM new_secteur WHERE Libelle='".$_POST['Libelle']."' AND Id_Plateforme=1");
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"INSERT INTO new_secteur (Id_Plateforme, Libelle) VALUES (1,'".$_POST['Libelle']."')");
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Ce libellé existe déjà.<br>Vous devez recommencer l'opération.</font>";}
	}
	elseif($_POST['Mode']=="Modif")
	{
		$result=mysqli_query($bdd,"SELECT * FROM new_secteur WHERE Libelle='".$_POST['Libelle']."' AND Id!=".$_POST['Id']." AND Id_Plateforme=1");
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"UPDATE new_secteur SET Id_Plateforme=1, Libelle='".$_POST['Libelle']."' WHERE Id=".$_POST['Id']);
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Ce libellé existe déjà pour cette plateforme.<br>Vous devez recommencer l'opération.</font>";}
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT * FROM new_secteur WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Secteur.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="Modif"){echo $row['Id'];}?>">
		<table width="95%" height="95%" align="center">
			<tr class="TitreColsUsers">
				<td>Libellé : </td>
				<td><input name="Libelle" size="20" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Libelle'];}?>"></td>
				<td><input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="Modif"){echo "Valider";}else{echo "Ajouter";}?>"></td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_prestation WHERE Id_Secteur=".$_GET['Id']);
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"DELETE FROM new_secteur WHERE Id=".$_GET['Id']);
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Vous ne pouvez pas supprimer ce secteur car une ou plusieurs prestations y est rattachées.</font>";}
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
<html>
<head>
	<title>Comp�tences - Poste</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps()
		{
			if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseign� le libell�.');return false;}
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
	if($_POST['Mode']=="Ajout"){
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_poste WHERE Libelle='".$_POST['Libelle']."'");
		echo "SELECT * FROM new_competences_poste WHERE Libelle='".$_POST['Libelle']."'";
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"INSERT INTO new_competences_poste (Id_PosteResponsable, Libelle) VALUES (".$_POST['PosteResponsable'].",'".addslashes($_POST['Libelle'])."')");
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Ce libell� existe d�j�.<br>Vous devez recommencer l'op�ration.</font>";}
	}
	elseif($_POST['Mode']=="Modif"){
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_poste WHERE Libelle='".$_POST['Libelle']."' AND Id!=".$_POST['Id']);
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"UPDATE new_competences_poste SET Libelle='".addslashes($_POST['Libelle'])."' WHERE Id=".$_POST['Id']);
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Ce libell� existe d�j�.<br>Vous devez recommencer l'op�ration.</font>";}
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT * FROM new_competences_poste WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Poste.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="Modif"){echo $row['Id'];}?>">
		<table style="width:95%; height:95%; align:center;">
			<tr class="TitreColsUsers">
				<td>Libell� : </td>
				<td><input name="Libelle" size="30" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Libelle'];}?>"></td>
				<td><input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="Modif"){echo "Valider";}else{echo "Ajouter";}?>"></td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_personne_poste WHERE Id_Poste=".$_GET['Id']);
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"DELETE FROM new_competences_poste WHERE Id=".$_GET['Id']);
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Vous ne pouvez pas supprimer ce poste car une ou plusieurs personne y est rattach�es.</font>";}
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Lib�ration des r�sultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
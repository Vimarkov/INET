<html>
<head>
	<title>Compétences - Fonction</title><meta name="robots" content="noindex">
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
session_start();
require("../Connexioni.php");
require_once("../Fonctions.php");

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_fonction WHERE Libelle='".$_POST['Libelle']."'");
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"INSERT INTO new_competences_fonction (Libelle,Fiche) VALUES ('".$_POST['Libelle']."','".$_POST['Fiche']."')");
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Ce libellé existe déjà.<br>Vous devez recommencer l'opération.</font>";}
	}
	elseif($_POST['Mode']=="Modif")
	{
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_fonction WHERE Libelle='".$_POST['Libelle']."' AND Id!=".$_POST['Id']);
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"UPDATE new_competences_fonction SET Libelle='".$_POST['Libelle']."', Fiche='".$_POST['Fiche']."' WHERE Id=".$_POST['Id']);
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Ce libellé existe déjà.<br>Vous devez recommencer l'opération.</font>";}
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT * FROM new_competences_fonction WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Fonction.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="Modif"){echo $row['Id'];}?>">
		<table style="width:95%; height:95%; align:center;">
			<tr class="TitreColsUsers">
				<td><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td><input name="Libelle" size="80" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Libelle'];}?>"></td>
				<td>
					<input class="Bouton" type="submit"
						<?php
							if($_GET['Mode']=="Modif"){
								if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
							}
							else{
								if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
							}
						?>
					>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td>N° Fiche : </td>
				<td><input name="Fiche" size="15" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Fiche'];}?>"></td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_personne_fonction WHERE Id_fonction=".$_GET['Id']);
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"DELETE FROM new_competences_fonction WHERE Id=".$_GET['Id']);
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Vous ne pouvez pas supprimer cette fonction car une ou plusieurs personne y est rattachées.</font>";}
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
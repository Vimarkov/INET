<html>
<head>
	<title>Compétences - Projet</title><meta name="robots" content="noindex">
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
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_projet WHERE Libelle='".$_POST['Libelle']."' AND Id_Plateforme=".$_POST['Id_Plateforme']);
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"INSERT INTO new_competences_projet (Id_Plateforme, Libelle) VALUES (".$_POST['Id_Plateforme'].",'".$_POST['Libelle']."')");
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Ce libellé existe déjà pour ce projet.<br>Vous devez recommencer l'opération.</font>";}
	}
	elseif($_POST['Mode']=="Modif")
	{
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_projet WHERE Libelle='".$_POST['Libelle']."' AND Id!=".$_POST['Id']." AND Id_Plateforme=".$_POST['Id_Plateforme']);
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"UPDATE new_competences_projet SET Id_Plateforme=".$_POST['Id_Plateforme'].", Libelle='".$_POST['Libelle']."' WHERE Id=".$_POST['Id']);
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Ce libellé existe déjà pour ce projet.<br>Vous devez recommencer l'opération.</font>";}
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT * FROM new_competences_plateforme WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Projet.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="Modif"){echo $row['Id'];}?>">
		<table style="width:95%; height:95%; align:center;">
			<tr class="TitreColsUsers">
				<td><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
				<td>
					<select name="Id_Plateforme">
						<?php
						$result2=mysqli_query($bdd,"SELECT * FROM new_competences_plateforme ORDER BY Libelle ASC");
						while($row2=mysqli_fetch_array($result2))
						{
							echo "<option value='".$row2['Id']."'";
							if($_GET['Mode']=="Modif"){if($row['Id_Plateforme']==$row2['Id']){echo " selected";}}
							echo ">".$row2['Libelle']."</option>";
						}
						?>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td><input name="Libelle" size="20" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Libelle'];}?>"></td>
				<td><input class="Bouton" type="submit"
					<?php
						if($_GET['Mode']=="Modif"){
							if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
						}
						else{
							if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
						}
					?>
				></td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_prestation WHERE Id_Projet=".$_GET['Id']);
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"DELETE FROM new_competences_projet WHERE Id=".$_GET['Id']);
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Vous ne pouvez pas supprimer ce projet car une ou plusieurs prestations y est rattachées.</font>";}
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
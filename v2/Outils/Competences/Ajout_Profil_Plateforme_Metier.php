<html>
<head>
	<title>Compétences - Profil personne - Plateforme</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require_once("../Fonctions.php");

if($_POST)
{
	$result=mysqli_query($bdd,"INSERT INTO new_competences_personne_plateforme (Id_Personne, Id_Plateforme) VALUES (".$_POST['Id_Personne'].",".$_POST['Plateforme'].")");;
	$result=mysqli_query($bdd,"INSERT INTO new_competences_personne_metier (Id_Personne, Id_Metier) VALUES (".$_POST['Id_Personne'].",".$_POST['Metier'].")");
	echo "<script>opener.location.reload;window.close();</script>";
}
elseif($_GET)
{
?>
	<form id="formulaire" method="POST" action="Ajout_Profil_Plateforme_Metier.php">
	<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
	<table style="width:95%; height:95%; align:center;">
		<tr class="TitreColsUsers">
			<td><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
			<td>
				<select name="Plateforme">
				<?php
				$result=mysqli_query($bdd,"SELECT * FROM new_competences_plateforme ORDER BY Libelle ASC");
				while($row=mysqli_fetch_array($result))
				{
					echo "<option value='".$row['Id']."'>".$row['Libelle']."</option>";
				}
				?>
				</select>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td><?php if($LangueAffichage=="FR"){echo "Métier";}else{echo "Job";}?> : </td>
			<td>
				<select name="Metier">
				<?php
				$result=mysqli_query($bdd,"SELECT * FROM new_competences_metier ORDER BY Libelle ASC");
				while($row=mysqli_fetch_array($result))
				{
					echo "<option value='".$row['Id']."'>".$row['Libelle']."</option>";
				}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td><input class="Bouton" type="submit"
			<?php
				if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
			?>
			></td>
		</tr>
	</table>
	</form>
<?php
	}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
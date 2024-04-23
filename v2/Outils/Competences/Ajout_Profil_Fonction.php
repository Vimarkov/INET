<html>
<head>
	<title>Compétences - Profil personne - Fonction</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function FermerEtRecharger(Page)
		{
			opener.location=Page;
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
	if($_POST['Fonction']!="")
	{
		$result=mysqli_query($bdd,"INSERT INTO new_competences_personne_fonction (Id_Personne, Id_Fonction) VALUES (".$_POST['Id_Personne'].",".$_POST['Fonction'].")");
	}
	echo "<script>FermerEtRecharger('Profil.php?Mode=Modif&Id_Personne=".$_POST['Id_Personne']."');</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout")
	{
?>
	<form id="formulaire" method="POST" action="Ajout_Profil_Fonction.php" class="None">
	<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
	<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
	<table style="align:center;" class="TableCompetences">
		<tr class="TitreColsUsers">
			<td><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
			<td>
				<select name="Fonction">
				<?php
				$result=mysqli_query($bdd,"SELECT * FROM new_competences_fonction ORDER BY Libelle ASC");
				while($row=mysqli_fetch_array($result))
				{
					echo "<option value='".$row['Id']."'>".$row['Libelle']."</option>";
				}
				?>
				</select>
			</td>
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
	if($_GET['Id_Personne']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
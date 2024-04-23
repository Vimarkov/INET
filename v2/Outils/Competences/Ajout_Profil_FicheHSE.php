<!DOCTYPE html>
<html>
<head>
	<title>Compétences - Profil personne - Fiche HSE : exposition et pénibilité</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Feuille.css" type="text/css">
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- Webforms2 -->
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
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
require("../Fonctions.php");

if($_POST)
{
	if($_POST['Metier']!="")
	{
		if($_POST['Mode']=="Ajout"){$requete="INSERT INTO new_competences_personne_fichehse (Id_Personne, Date_Debut, Date_Fin, Metier) VALUES (".$_POST['Id_Personne'].",'".TrsfDate($_POST['Date_Debut'])."','".TrsfDate($_POST['Date_Fin'])."','".$_POST['Metier']."')";}
		else{$requete="UPDATE new_competences_personne_fichehse SET Date_Debut='".TrsfDate($_POST['Date_Debut'])."', Date_Fin='".TrsfDate($_POST['Date_Fin'])."', Metier='".$_POST['Metier']."' WHERE Id=".$_POST['Id'];}
		$result=mysqli_query($bdd,$requete);
	}
	echo "<script>FermerEtRecharger('Profil.php?Mode=Modif&Id_Personne=".$_POST['Id_Personne']."');</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Mode']=="Modif")
		{
			$FicheHSE=mysqli_query($bdd,"SELECT * FROM new_competences_personne_fichehse WHERE Id=".$_GET['Id']);
			$LigneFicheHSE=mysqli_fetch_array($FicheHSE);
		}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

	<form id="formulaire" method="POST" action="Ajout_Profil_FicheHSE.php" class="None">
	<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="Modif"){echo $_GET['Id'];} ?>">
	<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
	<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
	<table style="align:center;" class="TableCompetences">
		<tr class="TitreColsUsers">
			<td><?php if($LangueAffichage=="FR"){echo "Date début";}else{echo "Start date";}?> :</td>
			<td>
				<input type="date" name="Date_Debut" size="10" value="<?php if($_GET['Mode']=="Modif"){echo AfficheDateFR($LigneFicheHSE['Date_Debut']);} ?>">
			</td>
			<td><?php if($LangueAffichage=="FR"){echo "Date fin";}else{echo "End date";}?> :</td>
			<td>
				<input type="date" name="Date_Fin" size="10" value="<?php if($_GET['Mode']=="Modif"){echo AfficheDateFR($LigneFicheHSE['Date_Fin']);} ?>">
			</td>
			<td><?php if($LangueAffichage=="FR"){echo "Type de poste / Activité";}else{echo "Type of job by activity";}?> :
				<select name="Metier">
					<option value="Ajusteur_Monteur" <?php if($_GET['Mode']=="Modif"){if($LigneFicheHSE['Metier']=="Ajusteur_Monteur"){echo "selected";}}?>>Ajusteur monteur</option>
					<option value="Ajusteur_FAL" <?php if($_GET['Mode']=="Modif"){if($LigneFicheHSE['Metier']=="Ajusteur_FAL"){echo " selected";}}?>>Ajusteur FAL</option>
					<option value="Ajusteur_Poste" <?php if($_GET['Mode']=="Modif"){if($LigneFicheHSE['Metier']=="Ajusteur_Poste"){echo " selected";}}?>>Ajusteur posté</option>
					<option value="Cableur" <?php if($_GET['Mode']=="Modif"){if($LigneFicheHSE['Metier']=="Cableur"){echo " selected";}}?>>Câbleur</option>
					<option value="Controleur_Chaine" <?php if($_GET['Mode']=="Modif"){if($LigneFicheHSE['Metier']=="Controleur_Chaine"){echo " selected";}}?>>Contôleur chaîne</option>
					<option value="Controleur_Piste" <?php if($_GET['Mode']=="Modif"){if($LigneFicheHSE['Metier']=="Controleur_Piste"){echo " selected";}}?>>Contôleur piste</option>
					<option value="Mecanicien_Chaine" <?php if($_GET['Mode']=="Modif"){if($LigneFicheHSE['Metier']=="Mecanicien_Chaine"){echo " selected";}}?>>Mécanicien chaîne</option>
					<option value="Mecanicien_Piste" <?php if($_GET['Mode']=="Modif"){if($LigneFicheHSE['Metier']=="Mecanicien_Piste"){echo " selected";}}?>>Mécanicien piste</option>
					<option value="Peintre" <?php if($_GET['Mode']=="Modif"){if($LigneFicheHSE['Metier']=="Peintre"){echo " selected";}}?>>Peintre</option>
					<option value="Col_Blanc" <?php if($_GET['Mode']=="Modif"){if($LigneFicheHSE['Metier']=="Col_Blanc"){echo " selected";}}?>>Col blanc</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="6" align="center"><input class="Bouton" type="submit"
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
	if($_GET['Mode']=="Modif"){mysqli_free_result($FicheHSE);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
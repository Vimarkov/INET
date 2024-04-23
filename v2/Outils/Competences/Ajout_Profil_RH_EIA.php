<!DOCTYPE html>
<html>
<head>
	<title>Compétences - Profil personne - RH - Entretien individuel annuel</title><meta name="robots" content="noindex">
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
		function FuturDateEIA()
		{	
			alert("N'oubliez pas de renseigner la date de prévision du futur entretien.");
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
	if($_POST['Date_Prevue']!="" || $_POST['Date_Report']!="" || $_POST['Date_Reel']!="")
	{
		if($_POST['Mode']=="Ajout"){$requete="INSERT INTO new_competences_personne_rh_eia (Id_Personne, Date_Prevue, Date_Report, Date_Reel, Type) VALUES (".$_POST['Id_Personne'].",'".TrsfDate($_POST['Date_Prevue'])."','".TrsfDate($_POST['Date_Report'])."','".TrsfDate($_POST['Date_Reel'])."','".$_POST['Type']."')";}
		else{$requete="UPDATE new_competences_personne_rh_eia SET Date_Prevue='".TrsfDate($_POST['Date_Prevue'])."', Date_Report='".TrsfDate($_POST['Date_Report'])."', Date_Reel='".TrsfDate($_POST['Date_Reel'])."', Type='".$_POST['Type']."' WHERE Id=".$_POST['Id'];}
		$result=mysqli_query($bdd,$requete);
	}
	if($_POST['Date_Reel']!="" && TrsfDate($_POST['Date_Reel'])>"0001-01-01"){echo"<script>FuturDateEIA();</script>";}
	echo "<script>FermerEtRecharger('Profil.php?Mode=Modif&Id_Personne=".$_POST['Id_Personne']."');</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Mode']=="Modif")
		{
			$EIA=mysqli_query($bdd,"SELECT * FROM new_competences_personne_rh_eia WHERE Id=".$_GET['Id']);
			$LigneEIA=mysqli_fetch_array($EIA);
		}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

	<form id="formulaire" method="POST" action="Ajout_Profil_RH_EIA.php" class="None">
	<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="Modif"){echo $_GET['Id'];} ?>">
	<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
	<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
	<table style="align:center;" class="TableCompetences">
		<tr>
			<td>Type : </td>
			<td colspan="5">
				<select name="Type">
					<?php
					$Tableau=array('(EPE) Entretien Professionnel d\'Evaluation','(EPP) Entretien Professionnel Parcours');
					foreach($Tableau as $indice => $valeur)
					{
						echo "<option value='".substr($valeur,1,3);
						if($_GET['Mode']=="Modif" && $LigneEIA[5] == substr($valeur,1,3)){echo "' selected>";}
						else{echo "'>";}
						echo $valeur."</option>";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td><?php if($LangueAffichage=="FR"){echo "Date prévisionnelle";}else{echo "Previsional date";}?> :</td>
			<td>
				<input type="date" name="Date_Prevue" size="10" value="<?php if($_GET['Mode']=="Modif"){echo AfficheDateFR($LigneEIA['Date_Prevue']);} ?>">
			</td>
			<td><?php if($LangueAffichage=="FR"){echo "Date report";}else{echo "Report date";}?> :</td>
			<td>
				<input type="date" name="Date_Report" size="10" value="<?php if($_GET['Mode']=="Modif"){echo AfficheDateFR($LigneEIA['Date_Report']);} ?>">
			</td>
			<td><?php if($LangueAffichage=="FR"){echo "Date réalisé";}else{echo "Realized date";}?> :</td>
			<td>
				<input type="date" name="Date_Reel" size="10" value="<?php if($_GET['Mode']=="Modif"){echo AfficheDateFR($LigneEIA['Date_Reel']);} ?>">
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
	if($_GET['Mode']=="Modif"){mysqli_free_result($EIA);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
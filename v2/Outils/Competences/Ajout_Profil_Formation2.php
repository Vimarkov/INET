<!DOCTYPE html>
<html>
<head>
	<title>Compétences - Profil personne - Formation</title><meta name="robots" content="noindex">
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
	if($_POST['Formation']!="" && TrsfDate_($_POST['Date'])!= "0001-01-01")
	{
		if($_POST['Mode']=="Ajout"){
			$requete="INSERT INTO new_competences_personne_formation (Id_Personne, Id_Formation, Date, Type) VALUES (".$_POST['Id_Personne'].",".$_POST['Formation'].",'".TrsfDate_($_POST['Date'])."','".$_POST['Type']."')";}
		else{$requete="UPDATE new_competences_personne_formation SET Id_Formation=".$_POST['Formation'].", Date='".TrsfDate_($_POST['Date'])."', Type='".$_POST['Type']."' WHERE Id=".$_POST['Id'];}
		$result=mysqli_query($bdd,$requete);
	}
	if(($Droits=="Ecriture" && $Plateforme_Identique) || $Droits=="Administrateur"){
		echo "<script>FermerEtRecharger('Tableau_Formations.php?Type=".$_POST['Type2']."&Id=".$_POST['Id_Prestation']."');</script>";
	}
	else{
		if($DroitsModifPrestation==false){
			echo "<script>FermerEtRecharger('Tableau_Formations.php?Type=".$_POST['Type2']."&Id=".$_POST['Id_Prestation']."');</script>";
		}
		else{
			echo "<script>FermerEtRecharger('Tableau_Formations.php??Type=".$_POST['Type2']."&Id=".$_POST['Id_Prestation']."');</script>";
		}
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Mode']=="Modif")
		{
			$Formation=mysqli_query($bdd,"SELECT * FROM new_competences_personne_formation WHERE Id=".$_GET['Id']);
			$LigneFormation=mysqli_fetch_array($Formation);
		}
		
		$Personnes=mysqli_query($bdd,"SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE Id=".$_GET['Id_Personne']);
		$LignePersonne=mysqli_fetch_array($Personnes);
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

	<form id="formulaire" method="POST" action="Ajout_Profil_Formation2.php" class="None">
	<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="Modif"){echo $_GET['Id'];} ?>">
	<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
	<input type="hidden" name="Type2" value="<?php echo $_GET['Type'];?>">
	<input type="hidden" name="Id_Prestation" value="<?php echo $_GET['Id_Prestation'];?>">
	<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
	<table style="align:center;" class="TableCompetences">
		<tr class="TitreColsUsers">
			<td><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?> : </td>
			<td colspan="2">
				<?php
				echo $LignePersonne['Personne'];
				?>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
			<td colspan="2">
				<select name="Formation" style='width:400px'>
				<?php
				$result=mysqli_query($bdd,"SELECT * FROM new_competences_formation ORDER BY Libelle ASC");
				while($row=mysqli_fetch_array($result))
				{
					echo "<option value='".$row['Id']."'";
					if($_GET['Mode']=="Modif"){if($LigneFormation['Id_Formation']==$row['Id']){echo " selected";}}
					else{if($_GET['Id_Formation']==$row['Id']){echo " selected";}}
					echo ">".$row['Libelle']."</option>";
				}
				?>
				</select>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td>Date :</td>
			<td>
				<input type="date" name="Date" size="10" value="<?php if($_GET['Mode']=="Modif"){echo AfficheDateFR($LigneFormation['Date']);} ?>">
			</td>
			<td>Type :
				<select name="Type"
				title="Formation initiale : Formation réalisée avant la venue au sein de AAA
Formation professionnelle : réalisée durant la période de travail au sein de AAA">
					<option <?php if($_GET['Mode']=="Modif"){if($LigneFormation['Type']=="Initiale"){echo "selected";}}?>>Initiale</option>
					<option <?php if($_GET['Mode']=="Modif"){if($LigneFormation['Type']=="Professionnelle"){echo "selected";}}?>>Professionnelle</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="4" align="center"><input class="Bouton" type="submit"
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
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script>
		function VerifChamps(langue){
			if(langue=="EN"){
				if(formulaire.theme.value==''){alert('You didn\'t fill in the theme.');return false;}
				if(formulaire.pourcentage.value==''){alert('You did not enter the objective.');return false;}
				if(formulaire.dateDebut.value==''){alert('You have not entered the start date.');return false;}
			}
			else{
				if(formulaire.theme.value==''){alert('Vous n\'avez pas renseigné le thème.');return false;}
				if(formulaire.pourcentage.value==''){alert('Vous n\'avez pas renseigné l\'objetif.');return false;}
				if(formulaire.dateDebut.value==''){alert('Vous n\'avez pas renseigné la date de début.');return false;}
			}
			return true;

		}
		function FermerEtRecharger(){
			opener.location.reload();
			window.close();
		}
		function nombre(champ){
			var chiffres = new RegExp("[0-9\.]"); /* Modifier pour : var chiffres = new RegExp("[0-9]"); */
			var verif;
			var points = 0; /* Supprimer cette ligne */

			for(x = 0; x < champ.value.length; x++)
			{
			verif = chiffres.test(champ.value.charAt(x));
			if(champ.value.charAt(x) == "."){points++;} /* Supprimer cette ligne */
			if(points > 1){verif = false; points = 1;} /* Supprimer cette ligne */
			if(verif == false){champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;}
			}
		}
	</script>
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/webforms2-0/webforms2-p.js"></script>	
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script src="../JS/colorpicker.js"></script>
</head>
<body>
<?php

session_start();
require("../Connexioni.php");
require("../Fonctions.php");

if($_POST){
	if($_POST['Mode']=="A"){
		$requete="INSERT INTO moris_objectifglobal (Theme,Pourcentage,DateDebut,DateFin) VALUES ('".addslashes($_POST['theme'])."',".$_POST['pourcentage'].",'".TrsfDate_($_POST['dateDebut'])."','".TrsfDate_($_POST['dateFin'])."') ";
		echo $requete;
		$result=mysqli_query($bdd,$requete);
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE moris_objectifglobal 
				SET Theme='".addslashes($_POST['theme'])."',
				Pourcentage='".$_POST['pourcentage']."',
				DateDebut='".TrsfDate_($_POST['dateDebut'])."',
				DateFin='".TrsfDate_($_POST['dateFin'])."'
				WHERE Id=".$_POST['id']." ";
		$result=mysqli_query($bdd,$requete);
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT Id, Theme, Pourcentage, DateDebut,DateFin FROM moris_objectifglobal WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
		}
?>

		<form id="formulaire" method="POST" action="Ajout_Objectif.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="id" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<table width="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Theme";}else{echo "Thème";} ?></td>
				<td class="Libelle">
					<select id="theme" name="theme">
						<option value=""></option>
							<option value="OTD activité" <?php if($_GET['Mode']=="M"){if($Ligne['Theme']=="OTD activité"){echo "selected";}}?>>OTD activité</option>
							<option value="OTD livrable" <?php if($_GET['Mode']=="M"){if($Ligne['Theme']=="OTD livrable"){echo "selected";}}?>>OTD livrable</option>
							<option value="OQD activité" <?php if($_GET['Mode']=="M"){if($Ligne['Theme']=="OQD activité"){echo "selected";}}?>>OQD activité</option>
							<option value="OQD livrable" <?php if($_GET['Mode']=="M"){if($Ligne['Theme']=="OQD livrable"){echo "selected";}}?>>OQD livrable</option>
							<option value="Productivité corrigée" <?php if($_GET['Mode']=="M"){if($Ligne['Theme']=="Productivité corrigée"){echo "selected";}}?>>Productivité corrigée</option>
							<option value="Satisfaction client" <?php if($_GET['Mode']=="M"){if($Ligne['Theme']=="Satisfaction client"){echo "selected";}}?>>Satisfaction client</option>
							<option value="Taux de qualification" <?php if($_GET['Mode']=="M"){if($Ligne['Theme']=="Taux de qualification"){echo "selected";}}?>>Taux de qualification</option>
							<option value="Taux de polyvalence" <?php if($_GET['Mode']=="M"){if($Ligne['Theme']=="Taux de polyvalence"){echo "selected";}}?>>Taux de polyvalence</option>
							<option value="Plan de prévention" <?php if($_GET['Mode']=="M"){if($Ligne['Theme']=="Plan de prévention"){echo "selected";}}?>>Plan de prévention</option>
					</select>
				</td>

				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Objective";}else{echo "Objectif";} ?> </td>
				<td>
					<input onKeyUp="nombre(this)" type="texte" name="pourcentage" id="pourcentage" size="15" value="<?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['Pourcentage']);}?>">
				</td>
			</tr>
			<tr><td height="5px"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Start date";}else{echo "Date de début";} ?></td>
				<td class="Libelle">
					<input type="date" name="dateDebut" id="dateDebut" value="<?php if($_GET['Mode']=="M"){echo AfficheDateFR($Ligne['DateDebut']);}?>" />
				</td>

				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "End date";}else{echo "Date de fin";} ?> </td>
				<td>
					<input type="date" name="dateFin" id="dateFin" value="<?php if($_GET['Mode']=="M"){echo AfficheDateFR($Ligne['DateFin']);}?>" />
				</td>
			</tr>
			<tr><td height="5px"></td></tr>
			<tr class="TitreColsUsers">
				<td colspan="6" align="center">
					<input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="M"){if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}}else{if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}}?>">
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$requete="UPDATE moris_objectifglobal SET Suppr=1, Id_Suppr=".$_SESSION['Id_Personne'].", DateSuppr='".date('Y-m-d')."' WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
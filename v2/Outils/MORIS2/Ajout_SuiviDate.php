<html>
<head>
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script>
		function VerifChamps(langue){
			if(langue=="EN"){
				if(formulaire.dateDebutSuivi.value==''){alert('You did not enter the start date.');return false;}
			}
			else{
				if(formulaire.dateDebutSuivi.value==''){alert('Vous n\'avez pas renseigné la date de démarrage.');return false;}
			}
			return true;
		}
		function FermerEtRecharger(){
			opener.location.reload();
			opener.opener.location.reload();
			window.close();
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
		$req="INSERT INTO moris_datesuivi (Id_Prestation,DateDebut,DateFin) 
			VALUES (".$_POST['Id_Prestation'].",'".TrsfDate_($_POST['dateDebutSuivi'])."','".TrsfDate_($_POST['dateFinSuivi'])."') ";
		$result=mysqli_query($bdd,$req);

		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE moris_datesuivi 
				SET DateDebut='".TrsfDate_($_POST['dateDebutSuivi'])."',
				DateFin='".TrsfDate_($_POST['dateFinSuivi'])."' 
				WHERE Id=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){

		$result=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_prestation WHERE Id=".$_GET['Id_Prestation']);
		$Ligne=mysqli_fetch_array($result);
		
		$dateDebut="";
		$dateFin="";
		if($_GET['Id']!='0')
		{
			
			
			$req="SELECT Id,DateDebut,DateFin
			FROM moris_datesuivi
			WHERE Id=".$_GET['Id']." 
			";
			$result=mysqli_query($bdd,$req);
			$nbResultaMoisPresta=mysqli_num_rows($result);
			if($nbResultaMoisPresta>0){
				$LigneDateSuivi=mysqli_fetch_array($result);
				
				$dateDebut=AfficheDateFR($LigneDateSuivi['DateDebut']);
				$dateFin=AfficheDateFR($LigneDateSuivi['DateFin']);
			}
		}
?>

		<form id="formulaire" method="POST" action="Ajout_SuiviDate.php" onSubmit="<?php echo "return VerifChamps('".$_SESSION['Langue']."');";?>" >
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id_Prestation" value="<?php echo $Ligne['Id'];?>">
		<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="M"){echo $LigneDateSuivi['Id'];}?>">
		<table width="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Site";}else{echo "Prestation";} ?> </td>
				<td colspan="3">
					<?php 
						echo $Ligne['Libelle'];
					?>
				</td>
			</tr>
			<tr><td height="5px"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Follow-up start date";}else{echo "Date début du suivi";} ?> </td>
				<td>
					<input type="date" name="dateDebutSuivi" id="dateDebutSuivi" value="<?php echo $dateDebut;?>" />
				</td>
			</tr>
			<tr><td height="5px"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Follow-up end date";}else{echo "Date fin du suivi";} ?> </td>
				<td>
					<input type="date" name="dateFinSuivi" id="dateFinSuivi" value="<?php echo $DateFin;?>" />
				</td>
			</tr>
			<tr><td height="5px"></td></tr>
			<tr class="TitreColsUsers">
				<td colspan="4" align="center">
					<input class="Bouton" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}?>">
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$requete="UPDATE moris_datesuivi SET Suppr=1,DateSuppr='".date('Y-m-d')."',Id_Suppr=".$_SESSION['Id_Personne']." WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");
?>

<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">
		function valider(id)
		{
			var w=window.open("Ajout_Question_QA.php?Id="+id, "PageQuestion", "width=1200,height=700");
			w.focus();
		}
	</script>
</head>
<body>

<?php
if($_POST)
{
	$req = "SELECT soda_surveillance_question.Id,EtatQA,
			(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
			soda_surveillance_question.QuestionAdditionnelle,
			soda_surveillance_question.ReponseAdditionnelle,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveillant) AS Surveillant,
			DateSurveillance
			FROM soda_surveillance_question 
			LEFT JOIN soda_surveillance 
			ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
			WHERE soda_surveillance.Suppr=0
			AND soda_surveillance_question.Id_Question=0
			AND AutoSurveillance=0 
			AND EtatQA<=0
			";
			
	$result=mysqli_query($bdd,$req);
	$nbQA=mysqli_num_rows($result);
	
	if($nbQA){
		while($rowQA=mysqli_fetch_array($result)){
			if(isset($_POST['vu_'.$rowQA['Id']])){
				$req="UPDATE soda_surveillance_question SET EtatQA=-1, Id_QA=".$_SESSION['Id_Personne'].", Date_QA=".date('Y-m-d')." WHERE Id=".$rowQA['Id']." ";
				$resultUpdt=mysqli_query($bdd,$req);
			}
			else{
				$req="UPDATE soda_surveillance_question SET EtatQA=0, Id_QA=".$_SESSION['Id_Personne'].", Date_QA=".date('Y-m-d')." WHERE Id=".$rowQA['Id']." ";
				$resultUpdt=mysqli_query($bdd,$req);
			}
		}
	}

	echo "<script>opener.location='Tableau_De_Bord.php?Menu=15';</script>";
}
if($_POST){
	$Id=$_POST['Id'];
}
else{
	$Id=$_GET['Id'];
}
$req = "SELECT soda_surveillance_question.Id,EtatQA,
		(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
		soda_surveillance_question.QuestionAdditionnelle,
		soda_surveillance_question.ReponseAdditionnelle,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveillant) AS Surveillant,
		DateSurveillance
		FROM soda_surveillance_question 
		LEFT JOIN soda_surveillance 
		ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
		WHERE soda_surveillance.Suppr=0
		AND soda_surveillance_question.Id_Question=0
		AND AutoSurveillance=0 
		AND EtatQA<=0
		AND Id_Questionnaire=".$Id."
		";	
$result=mysqli_query($bdd,$req);
$nbQA=mysqli_num_rows($result);

?>
<form id="formulaire" method="POST" action="Questions_Additionnelles.php">
<input type="hidden" name="Id" value="<?php echo $Id; ?>">
<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>">
<table style="width:95%; height:95%; align:center;">
	<tr><td height="10"></td></tr>
	<tr>
		<td>
			<table class="TableCompetences" style="width:100%; align:center;background-color:#bac8ff;">
				<tr>
					<td width="40%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Question";}else{echo "Question";}?></td>
					<td width="40%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Réponse";}else{echo "Answer";}?></td>
					<td width="8%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Prestation";}else{echo "Site";}?></td>
					<td width="8%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Ajouté par";}else{echo "Added by";}?></td>
					<td width="8%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Date";}else{echo "Date";}?></td>
					<td width="5%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Vu";}else{echo "Got it";}?></td>
					<td width="5%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Ajouter";}else{echo "Add";}?></td>
				</tr>
	<?php 
	if($nbQA){
		$Couleur="#EEEEEE";
		while($rowQA=mysqli_fetch_array($result)){
	?>
		<tr bgcolor="<?php echo $Couleur;?>">
			<td><?php echo stripslashes($rowQA['QuestionAdditionnelle']);?></td>
			<td><?php echo stripslashes($rowQA['ReponseAdditionnelle']);?></td>
			<td><?php echo stripslashes($rowQA['Prestation']);?></td>
			<td><?php echo stripslashes($rowQA['Surveillant']);?></td>
			<td><?php echo AfficheDateJJ_MM_AAAA($rowQA['DateSurveillance']);?></td>
			<td>
			<?php 
				$check="";
				if($rowQA['EtatQA']==-1){$check="checked";}
			?>
			<input type="checkbox" <?php echo $check; ?> onchange="submit()" name="vu_<?php echo $rowQA['Id'];?>" value="<?php echo $rowQA['Id'];?>" />
			</td>
			<td><a href="javascript:valider(<?php echo $rowQA['Id'];?>)"><img width="20px" src="../../Images/Plus.png"></a></td>
		</tr>
	<?php 
			if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
			else{$Couleur="#EEEEEE";}
		}
	}
	?>
			</table>
		</td>
	</tr>
</table>
</form>
</body>
</html>
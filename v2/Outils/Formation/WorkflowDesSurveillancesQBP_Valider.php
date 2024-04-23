<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Extranet - Formation - Workflow des surveillances - Validation</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../CSS/Planning.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
	</script>
</head>

<?php
if($_POST)
{
	if(isset($_POST['generer'])){
		if($_POST['dateSensibilisation']<>"" && $_POST['numSurveillance']<>""){
			$reqQ="
				INSERT INTO new_competences_relation_surveillance (Id_Relation,DateSurveillance,NumSurveillanceSODA)
				SELECT
					new_competences_relation.Id,'".TrsfDate_($_POST['dateSensibilisation'])."' AS DateSurveillance,'".$_POST['numSurveillance']."'
				FROM
					new_competences_relation,
					new_competences_qualification
				WHERE
					new_competences_relation.Id_Qualification_Parrainage = new_competences_qualification.Id
					AND new_competences_relation.Suppr = 0
					AND (new_competences_relation.Date_Fin<='0001-01-01' OR new_competences_relation.Date_Fin>='".date('Y-m-d')."')
					AND new_competences_qualification.Id_Categorie_Qualification=147
					AND new_competences_qualification.Libelle LIKE 'WA - Basic%'
					AND new_competences_relation.Id_Personne=".$_POST['Id_Personne']." ";
			$resultQ=mysqli_query($bdd,$reqQ);
			
			$reqQ="
				UPDATE 
					new_competences_relation
				SET
					Date_Surveillance='".TrsfDate_($_POST['dateSensibilisation'])."',
					Date_PlanifSurveillance='0001-01-01',
					NumSurveillanceSODA='".$_POST['numSurveillance']."',
					IgnorerSurveillance=0
				WHERE
					new_competences_relation.Suppr = 0
					AND (new_competences_relation.Date_Fin<='0001-01-01' OR new_competences_relation.Date_Fin>='".date('Y-m-d')."')
					AND (SELECT Id_Categorie_Qualification FROM new_competences_qualification WHERE new_competences_relation.Id_Qualification_Parrainage = new_competences_qualification.Id)=147
					AND (SELECT Libelle FROM new_competences_qualification WHERE new_competences_relation.Id_Qualification_Parrainage = new_competences_qualification.Id) LIKE 'WA - Basic%'
					AND new_competences_relation.Id_Personne=".$_POST['Id_Personne']." ";
			$resultQ=mysqli_query($bdd,$reqQ);
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
if($_GET){
	$Id_Personne=$_GET['Id'];

?>
	<form id="formulaire" action="WorkflowDesSurveillancesQBP_Valider.php" method="post">
		<input type="hidden" id="Id_Personne" name="Id_Personne" value="<?php echo $Id_Personne; ?>">
		<table class="TableCompetences" style="width:95%; align:center;">
			<tr>
				<td class="Libelle">Date surveillance : </td>
				<td>
					<input type="date" name="dateSensibilisation" id="dateSensibilisation" size="10" value="">
				</td>
			</tr>
			<tr>
				<td class="Libelle">N° surveillance SODA : </td>
				<td>
					<input type="texte" name="numSurveillance" id="numSurveillance" size="10" value="">
				</td>
			</tr>
            <tr>
    			<td colspan="2" align="center">
    				<input class="Bouton" name="generer" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Enregistrer";}else{echo "Save";}?>">
    			</td>
			</tr>
		</table>
	</form>
<?php
}

?>
</body>
</html>
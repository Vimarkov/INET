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
		function FermerEtRecharger()
		{
			opener.location='Tableau_De_Bord.php';
			window.close();
		}
	</script>
</head>
<body>

<?php
$SrcProblem="";
$Problem=0;
$FichierTransfert=0;
$Id_Questionnaire=0;
$Id_SurveillanceMere=0;

if($_POST)
{
	if(isset($_POST['btnValider']))
	{
		$req="UPDATE soda_plannifmanuelle SET Id_Surveillant=".$_POST['Id_Surveillant']." WHERE Id IN (".$_POST['Id'].") ";
		$resultUpdt=mysqli_query($bdd,$req);
		
		echo "<script>FermerEtRecharger();</script>";
	}
	
}
if($_GET){
?>
<form id="formulaire" enctype="multipart/form-data" method="POST" action="ChoisirSurveillant.php">
<input type="hidden" name="Id" value="<?php echo $_GET['Id']; ?>">
<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<table class="TableCompetences" style="width:95%; height:95%; align:center;">
	<tr>
		<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Surveillant";}else{echo "Supervisor";}?> : </td>
		<td class="Libelle" width="10%">
			<select id="Id_Surveillant" name="Id_Surveillant" style="width:200px;">
				<option value="0"></option>
				<?php
				$Id_Surveille=0;
				$requetePersonne="
					SELECT DISTINCT Id_Personne AS Id, 
						(SELECT CONCAT(Nom, ' ', Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) as NomPrenom,
						(SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) 
								FROM new_competences_personne_prestation 
								WHERE Id_Personne=new_competences_relation.Id_Personne 
								AND Date_Debut<='".date('Y-m-d')."'
								AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01')
								ORDER BY Date_Debut DESC
								LIMIT 1
						) AS Presta
						FROM new_competences_relation 
						WHERE (Evaluation='L'
						OR
						(Evaluation='X'
						AND Date_Debut<='".date('Y-m-d')."'
						AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
						)
						)
						AND Suppr=0
						AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
					UNION
					SELECT DISTINCT
						new_rh_etatcivil.Id,
						CONCAT(Nom, ' ', Prenom) as NomPrenom,
						(SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) 
								FROM new_competences_personne_prestation 
								WHERE Id_Personne=new_rh_etatcivil.Id 
								AND Date_Debut<='".date('Y-m-d')."'
								AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01')
								ORDER BY Date_Debut DESC
								LIMIT 1
						) AS Presta
					FROM
						new_rh_etatcivil
					INNER JOIN soda_surveillant
						ON new_rh_etatcivil.Id=soda_surveillant.Id_Personne
						
					ORDER BY NomPrenom ASC";
					
				$result_Personne= mysqli_query($bdd,$requetePersonne);
				while ($row_Personne=mysqli_fetch_array($result_Personne))
				{
					$selected="";
					$afficher=1;
					$req="SELECT DISTINCT (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Id_Theme 
						FROM soda_plannifmanuelle
						WHERE Id IN (".$_GET['Id'].") ";
					$resultPlannif= mysqli_query($bdd,$req);	
					$nbPlanif=mysqli_num_rows($resultPlannif);
					if($nbPlanif>0){
						while($rowPlanif=mysqli_fetch_array($resultPlannif))
						{
							$req="SELECT Id FROM soda_surveillant_theme WHERE Id_Theme=".$rowPlanif['Id_Theme']." AND Id_Surveillant=".$row_Personne['Id']." ";
							$resultSurTheme= mysqli_query($bdd,$req);	
							$nbSurvTheme=mysqli_num_rows($resultSurTheme);
							
							$req="SELECT Id FROM soda_theme WHERE Id=".$rowPlanif['Id_Theme']." AND Id_Qualification IN (
								SELECT DISTINCT Id_Qualification_Parrainage 
								FROM new_competences_relation 
								WHERE (Evaluation='L'
								OR
								(Evaluation='X'
								AND Date_Debut<='".date('Y-m-d')."'
								AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
								)
								)
								AND Suppr=0
								AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
								AND Id_Personne=".$row_Personne['Id']."
							) ";
							$resultSurTheme= mysqli_query($bdd,$req);	
							$nbSurvTheme2=mysqli_num_rows($resultSurTheme);
							
							$requete="
								SELECT DISTINCT Id_Personne AS Id, 
								(SELECT CONCAT(Nom, ' ', Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) as NomPrenom
								FROM 
									new_competences_relation 
								WHERE 
								Evaluation='L'
								AND Id_Qualification_Parrainage IN (
									SELECT Id_Qualification
									FROM soda_theme 
									WHERE Id=".$rowPlanif['Id_Theme']."
									)
								AND Suppr=0
								AND new_competences_relation.Id_Personne=".$row_Personne['Id']." ";
							$resultV2QualifEnFormation=mysqli_query($bdd,$requete);
							$nbV2QualifEnFormation=mysqli_num_rows($resultV2QualifEnFormation);
							
							if($nbSurvTheme==0 && $nbSurvTheme2==0){$afficher=0;}
						}
					}
					
					if($afficher==1){
						echo "<option value='".$row_Personne['Id']."' ".$selected.">".$row_Personne['NomPrenom']." [".$row_Personne['Presta']."]";
						if($nbV2QualifEnFormation>0 && $nbSurvTheme==0){
							 if($_SESSION['Langue']=="FR"){echo "<i> [En formation] </i>";}
							 else{echo "<i> [In training] </i>";}
						}
						echo "</option>\n";
					}
				}
				?>
			</select>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td colspan="2" align="center">
			<input class="Bouton" name="btnValider" type="submit" <?php if($LangueAffichage=="FR"){echo "value='Modifier'";}else{echo "value='Edit'";}?> />
		</td>
	</tr>
</table>
</form>
<?php 
}
?>
</body>
</html>
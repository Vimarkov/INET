<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Extranet - Formation - Workflow des surveillances - Sansibilisation</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../CSS/Planning.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">
		Liste_QCM_Langues = new Array();
		function Recharge_Liste_Langues()
		{
			var sel="";
			sel ="<select size='1' name='Id_Langue'>";
			for(var i=0;i<Liste_QCM_Langues.length;i++)
			{
				if (Liste_QCM_Langues[i][1]==document.getElementById('Id_QCM').value)
				{
					sel= sel + "<option value="+Liste_QCM_Langues[i][2]+">"+Liste_QCM_Langues[i][4]+"</option>";
				}
			}
			sel =sel + "</select>";
			document.getElementById('Langue').innerHTML=sel;
		}

		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.formation.value=='0'){alert('Vous n\'avez pas renseigné la formation.');return false;}
				if(formulaire.lieu.value==''){alert('Vous n\'avez pas renseigné le lieu.');return false;}
				if(formulaire.dateSensibilisation.value==''){alert('Vous n\'avez pas renseigné la date de la surveillance.');return false;}
				if(formulaire.duree.value==''){alert('Vous n\'avez pas renseigné la durée de la formation.');return false;}
			}
			else{
				if(formulaire.formation.value=='0'){alert('You did not fill in the training.');return false;}
				if(formulaire.lieu.value==''){alert('You have not entered the location.');return false;}
				if(formulaire.dateSensibilisation.value==''){alert('You have not entered the date of the surveillance.');return false;}
				if(formulaire.duree.value==''){alert('You have not entered the duration of the training.');return false;}
			}
		}
	</script>
</head>

<?php
if($_POST)
{
	if(isset($_POST['generer'])){
		$req="UPDATE new_competences_relation 
			SET Sensibilisation=1, DateSensibilisation='".date('Y-m-d')."', Id_Sensibilisation=".$_SESSION['Id_Personne']." 
			, Id_PrestationSensibilisation=".$_POST['Id_Prestation']." 
			, Id_Formation=".$_POST['formation']." 
			, Lieu='".addslashes($_POST['lieu'])."'
			, Duree='".addslashes($_POST['duree'])."'
			, DateSensibilisation='".TrsfDate_($_POST['dateSensibilisation'])."'
			WHERE Id = ".$_POST['Id_Relation'];
		$result=mysqli_query($bdd,$req);
		echo "<script>FermerEtRecharger();</script>";
	}
}
if($_GET){
	$Id_Relation=$_GET['Id'];

	$ReqRelation="
		SELECT
			Id_Qualification_Parrainage,DateSensibilisation,Id_Formation,Lieu,Duree,
			(SELECT Id_Prestation FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') LIMIT 1) AS Id_Prestation,
			(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') LIMIT 1) AS Id_Plateforme,
			Id_Personne
		FROM
			new_competences_relation
		WHERE
		
			new_competences_relation.Id = ".$Id_Relation;
	$ResultRelation=mysqli_query($bdd, $ReqRelation);
	$RowRelation=mysqli_fetch_array($ResultRelation);

	?>
		<form id="formulaire" action="WorkflowDesSurveillances_Sensibilise.php" method="post" onSubmit="return VerifChamps();">
			<input type="hidden" id="Id_Relation" name="Id_Relation" value="<?php echo $Id_Relation; ?>">
			<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
			<input type="hidden" id="Id_Prestation" name="Id_Prestation" value="<?php echo $RowRelation['Id_Prestation']; ?>">
			<table class="TableCompetences" style="width:95%; align:center;">
				<tr>
					<td class="Libelle">Formation : </td>
					<td>
						<select class="form" id="formation" name="formation" style="width:250px;" >
							<option value="0"></option>
							<?php 
								$req="SELECT DISTINCT form_formation.Id, 
										form_formation.Reference,
										form_formation.Recyclage,
										(SELECT IF(form_formation.Recyclage=1,LibelleRecyclage,Libelle)
											FROM form_formation_langue_infos
											WHERE Id_Formation=form_formation.Id
											AND Id_Langue=
												(SELECT Id_Langue 
												FROM form_formation_plateforme_parametres 
												WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$RowRelation['Id_Prestation'].")
												AND Id_Formation=form_formation.Id
												AND Suppr=0 
												LIMIT 1)
											AND Suppr=0) AS Libelle,
										(SELECT (SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme)
											FROM form_formation_plateforme_parametres 
											WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$RowRelation['Id_Prestation'].")
											AND Id_Formation=form_formation.Id
											AND Suppr=0 
											LIMIT 1) AS Organisme
										FROM form_formation 
										LEFT JOIN form_formation_qualification 
										ON form_formation.Id=form_formation_qualification.Id_Formation 
										WHERE (form_formation.Id_Plateforme=0 OR form_formation.Id_Plateforme=".$RowRelation['Id_Plateforme'].") 
										AND form_formation.Suppr=0 
										AND form_formation.Id_TypeFormation<>1
										AND form_formation_qualification.Suppr=0 
										AND form_formation_qualification.Id_Qualification=".$RowRelation['Id_Qualification_Parrainage']." 
									ORDER BY Libelle ";
								$result=mysqli_query($bdd,$req);
								echo $req;
								$nbResult=mysqli_num_rows($result);
								if($nbResult>0){
									while($rowForm=mysqli_fetch_array($result)){
										$organisme="";
										if($rowForm['Organisme']<>""){
											$organisme=" (".$rowForm['Organisme'].") ";
										}
										$selected="";
										if($RowRelation['Id_Formation']==$rowForm['Id']){$selected="selected";}
										echo "<option value='".$rowForm['Id']."' ".$selected.">".stripslashes($rowForm['Libelle']).$organisme."</option>";
									}
								}
							?>
						</select>
					</td>
					<td class="Libelle" style="width:10%;">
						<?php if($LangueAffichage=="FR"){echo "Date surveillance";}else{echo "Monitoring date";}?> :
					</td>
					<td width="20%"><input type="date" name="dateSensibilisation" id="dateSensibilisation" size="10" value="<?php echo AfficheDateFR($RowRelation['DateSensibilisation']); ?>"></td>
				</tr>
				<tr>
					<td height="4"></td>
				</tr>
				<tr>
					<td class="Libelle" style="width:10%;">
						<?php if($LangueAffichage=="FR"){echo "Lieu";}else{echo "Place";}?> :
					</td>
					<td width="20%" colspan="3"><input type="text" name="lieu" id="lieu" size="120" value="<?php echo stripslashes($RowRelation['Lieu']); ?>"></td>
				</tr>
				<tr>
					<td height="4"></td>
				</tr>
				<tr>
					<td class="Libelle" style="width:10%;">
						<?php if($LangueAffichage=="FR"){echo "Durée";}else{echo "Duration";}?> :
					</td>
					<td width="20%" colspan="3"><input type="text" name="duree" id="duree" size="80" value="<?php echo stripslashes($RowRelation['Duree']); ?>"></td>
				</tr>
				<tr>
					<td height="4"></td>
				</tr>
			
				<tr>
					<td colspan="4" align="center">
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

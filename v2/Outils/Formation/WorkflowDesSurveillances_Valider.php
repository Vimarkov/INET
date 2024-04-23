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
	</script>
</head>

<?php
if($_POST)
{
	if(isset($_POST['generer'])){
		//Execute les requêtes de validation
		$Id_QCM=0;
		$Id_Langue=0;
		$Id_QCMLie=0;
		$Id_LangueQCMLie=0;
		if(isset($_POST['Id_QCM']) && $_POST['Id_Langue']){
			if(isset($_POST['Id_QCMLie'])){
				$Id_QCMLie=$_POST['Id_QCMLie'];
			}
			if(isset($_POST['Id_LangueQCMLie'])){
				$Id_LangueQCMLie=$_POST['Id_LangueQCMLie'];
			}
			Set_BesoinsDeSurveillance_Valider($_POST['Id_Relation'], $_POST['Id_QCM'], $_POST['Id_Langue'],$Id_QCMLie,$Id_LangueQCMLie);
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
if($_GET){$Id_Relation=$_GET['Id'];}
else{
	$Id_Relation=$_POST['Id_Relation'];
}
$ReqRelation="
	SELECT
		Id_Qualification_Parrainage,
		(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) FROM new_competences_personne_prestation WHERE new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') LIMIT 1) AS Id_Plateforme,
		Id_Personne
	FROM
		new_competences_relation
	WHERE
	
		new_competences_relation.Id = ".$Id_Relation;
$ResultRelation=mysqli_query($bdd, $ReqRelation);
$RowRelation=mysqli_fetch_array($ResultRelation);

$req="SELECT DISTINCT 
	form_formation_qualification_qcm.Id_QCM,
	form_formation_qualification_qcm.Id_Langue,
	form_qcm.Code AS QCM,
	form_qcm.Id_QCM_Lie,
	(SELECT form_qcm2.Code FROM form_qcm AS form_qcm2 WHERE form_qcm2.Id=form_qcm.Id_QCM_Lie) AS QCMLie
	FROM form_formation_qualification_qcm
	LEFT JOIN form_qcm
	ON form_formation_qualification_qcm.Id_QCM=form_qcm.Id
	LEFT JOIN form_formation_qualification
	ON form_formation_qualification_qcm.Id_Formation_Qualification=form_formation_qualification.Id
	WHERE form_formation_qualification.Id_Qualification=".$RowRelation['Id_Qualification_Parrainage']."
	AND form_formation_qualification_qcm.Suppr=0 
	AND form_qcm.Suppr=0 
	AND form_formation_qualification.Suppr=0";
$resultQCM=mysqli_query($bdd,$req);
$nbQCM=mysqli_num_rows($resultQCM);
$Id_QCM=0;
$Id_LangueQCM=0;
$Id_QCMLie=0;
$CodeQCMLie="";
if($_POST){$Id_QCM=$_POST['Id_QCM'];}
if($nbQCM>0){
	while($rowQCM=mysqli_fetch_array($resultQCM)){
		if($Id_QCM==0){
			$Id_QCM=$rowQCM['Id_QCM'];
			$Id_LangueQCM=$rowQCM['Id_Langue'];
			$Id_QCMLie=$rowQCM['Id_QCM_Lie'];
			$CodeQCMLie=$rowQCM['QCMLie'];
		}
		elseif($Id_QCM==$rowQCM['Id_QCM']){
			$Id_LangueQCM=$rowQCM['Id_Langue'];
			$Id_QCMLie=$rowQCM['Id_QCM_Lie'];
			$CodeQCMLie=$rowQCM['QCMLie'];
		}
	}

?>
	<form id="formulaire" action="WorkflowDesSurveillances_Valider.php" method="post">
		<input type="hidden" id="Id_Relation" name="Id_Relation" value="<?php echo $Id_Relation; ?>">
		<table class="TableCompetences" style="width:95%; align:center;">
			<tr>
				<td class="Libelle">QCM : </td>
				<td>
					<select name="Id_QCM" id="Id_QCM" onchange="submit()">
						<?php
							$req="SELECT DISTINCT 
								form_formation_qualification_qcm.Id_QCM,
								form_qcm.Code AS QCM
								FROM form_formation_qualification_qcm
								LEFT JOIN form_qcm
								ON form_formation_qualification_qcm.Id_QCM=form_qcm.Id
								LEFT JOIN form_formation_qualification
								ON form_formation_qualification_qcm.Id_Formation_Qualification=form_formation_qualification.Id
								WHERE form_formation_qualification.Id_Qualification=".$RowRelation['Id_Qualification_Parrainage']."
								AND form_formation_qualification_qcm.Suppr=0 
								AND form_qcm.Suppr=0
								AND form_formation_qualification.Suppr=0
								AND (SELECT COUNT(form_formation_plateforme_parametres.Id) 
								FROM form_formation_plateforme_parametres 
								WHERE form_formation_plateforme_parametres.Id_Formation=form_formation_qualification.Id_Formation
								AND form_formation_plateforme_parametres.Id_Plateforme=".$RowRelation['Id_Plateforme']."
								AND form_formation_plateforme_parametres.Suppr=0)>0
								";
							$resultQCM=mysqli_query($bdd,$req);
							$nbQCM=mysqli_num_rows($resultQCM);
							if($_POST){$Id_QCM=$_POST['Id_QCM'];}
							if($nbQCM>0){
								while($rowQCM=mysqli_fetch_array($resultQCM)){
									$selected="";
									if($Id_QCM==$rowQCM['Id_QCM']){$selected="selected";}
									echo "<option value='".$rowQCM['Id_QCM']."' ".$selected." >".stripslashes($rowQCM['QCM'])."</option>";
								}
							}
							
						?>
					</select>
				</td>
				<?php if($nbQCM>0){?>
				<td class="Libelle" style="width:10%;">
					<?php if($LangueAffichage=="FR"){echo "Langue";}else{echo "Language";}?> :
				</td>
				<td style="width:20%;">
					<select name="Id_Langue" id="Id_Langue">
						<?php
							$req="SELECT DISTINCT 
								form_qcm_langue.Id_Langue,
								form_langue.Libelle AS Langue
								FROM form_qcm_langue
								LEFT JOIN form_langue
								ON form_qcm_langue.Id_Langue=form_langue.Id
								WHERE form_qcm_langue.Id_QCM=".$Id_QCM."
								AND form_qcm_langue.Suppr=0 
								AND form_langue.Suppr=0 ";
							$resultLangue=mysqli_query($bdd,$req);
							$nbLangue=mysqli_num_rows($resultLangue);
							if($nbLangue>0){
								while($rowLangue=mysqli_fetch_array($resultLangue)){
									$selected="";
									if($Id_LangueQCM==$rowLangue['Id_Langue']){$selected="selected";}
									echo "<option value='".$rowLangue['Id_Langue']."' ".$selected." >".stripslashes($rowLangue['Langue'])."</option>";
								}
							}
							
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td height="4" colspan="3" ><?php if($nbQCM==0){echo "Pas de formations disponibles pour cette qualification sur cette plateforme";} ?></td>
			</tr>
			<tr>
				<td class="Libelle">
					<?php 
					if($Id_QCMLie>0){
						 if($LangueAffichage=="FR"){echo "QCM lié : ";}else{echo "Linked QCM";}
					}
					?>
				</td>
				<td>
					<?php 
					if($Id_QCMLie>0){
						echo stripslashes($CodeQCMLie);
					}
					?>
				
				</td>
				<td class="Libelle">
					<?php 
					if($Id_QCMLie>0){
						 if($LangueAffichage=="FR"){echo "Langue : ";}else{echo "Language : ";}
					}
					?>
				</td>
				<td>
					<?php if($Id_QCMLie>0){ ?>
					<input type="hidden" name="Id_QCMLie" value="<?php echo $Id_QCMLie; ?>" />
					<select name="Id_LangueQCMLie" id="Id_LangueQCMLie">
						<?php
							$req="SELECT DISTINCT 
								form_qcm_langue.Id_Langue,
								form_langue.Libelle AS Langue
								FROM form_qcm_langue
								LEFT JOIN form_langue
								ON form_qcm_langue.Id_Langue=form_langue.Id
								WHERE form_qcm_langue.Id_QCM=".$Id_QCMLie."
								AND form_qcm_langue.Suppr=0 
								AND form_langue.Suppr=0 ";
							$resultLangue=mysqli_query($bdd,$req);
							$nbLangue=mysqli_num_rows($resultLangue);
							if($nbLangue>0){
								while($rowLangue=mysqli_fetch_array($resultLangue)){
									$selected="";
									if($Id_LangueQCM==$rowLangue['Id_Langue']){$selected="selected";}
									echo "<option value='".$rowLangue['Id_Langue']."' ".$selected." >".stripslashes($rowLangue['Langue'])."</option>";
								}
							}
							
						?>
					</select>
					<?php } ?>
				</td>
			</tr>
            <tr>
    			<td colspan="2" align="center">
    				<input class="Bouton" name="generer" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Enregistrer";}else{echo "Save";}?>">
    			</td>
			</tr>
			<?php }?>
			<tr>
				<td height="4" colspan="3" class="Libelle"><?php if($nbQCM==0){echo "Pas de formations disponibles pour cette qualification sur cette plateforme";} ?></td>
			</tr>
		</table>
	</form>
<?php
}
else{
?>
	<table class="TableCompetences" style="width:95%; align:center;">
		<tr>
			<td class="Libelle" align="center"><?php if($LangueAffichage=="FR"){echo "Aucun QCM n'est relié à cette qualification<br>Merci de contacter le pôle formation interne";}else{echo "No multiple choice is linked to this qualification <br> Please contact the internal training department";}?></td>
		</tr>
	</table>
<?php
}
?>
</body>
</html>
<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Modifier QCM avec session de formation</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script type="text/javascript">
		function FermerEtRecharger()
		{
			if(window.opener.document.getElementById('formulaire')){
				window.opener.document.getElementById('formulaire').submit();
			}
			window.close();
		}
	</script>
</head>
<body>

<?php
$sansFormation = false;

if($_POST){    
	if(isset($_POST['generer'])){
		if(isset($_POST['Id_SessionPersonneQualification'])){
			$qcm_mere=$_POST['qcm_mere'];
			$langue_qcm_mere=$_POST['langue_qcm_mere'];
			$qcm_fille=0;
			$langue_qcm_fille=0;
			if(isset($_POST['qcmlie_fille'])){$qcm_fille=$_POST['qcmlie_fille'];}
			if(isset($_POST['langue_qcmlie_fille'])){$langue_qcm_fille=$_POST['langue_qcmlie_fille'];}
			maj_QCM_SessionPersonneQualification($_POST['Id_SessionPersonneQualification'],$qcm_mere,$qcm_fille,$langue_qcm_mere,$langue_qcm_fille);
			echo "<script>FermerEtRecharger();</script>";
		}
	}
}

if($_GET){
    $Id_SessionPersonneQualification=$_GET['Id'];
    if(isset($_GET['sansFormation']))
        $sansFormation = true;
}
else{$Id_SessionPersonneQualification=$_POST['Id_SessionPersonneQualification'];}
$requete="	SELECT DISTINCT
	form_typeformation.Libelle AS LIBELLE_TYPEFORMATION,
	form_formation.Reference AS REFERENCE_FORMATION,
	form_session.Id_Formation AS ID_FORMATION,
	form_session_personne_qualification.Id_Qualification,
	form_session_personne_qualification.Id_QCM,
	form_session_personne_qualification.Id_QCM_Lie,
	form_session_personne_qualification.Id_LangueQCM,
	form_session_personne_qualification.Id_LangueQCMLie,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS NOM_PRENOM,
	form_session.Recyclage,
	(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
		WHERE form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
		AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
		AND Suppr=0 LIMIT 1) AS Organisme,
	(SELECT IF(form_session.Recyclage=1,LibelleRecyclage,Libelle)
		FROM form_formation_langue_infos
		WHERE Id_Formation=form_session.Id_Formation
		AND Id_Langue=
			(SELECT Id_Langue 
			FROM form_formation_plateforme_parametres 
			WHERE Id_Plateforme=form_session.Id_Plateforme
			AND Id_Formation=form_session.Id_Formation
			AND Suppr=0 
			LIMIT 1)
		AND Suppr=0) AS Libelle										
FROM
	form_session_personne_qualification,
	form_session_personne,
	form_session,
	form_typeformation,
	form_formation
WHERE
	form_session_personne_qualification.Id=".$Id_SessionPersonneQualification."
	AND form_session_personne.Id=form_session_personne_qualification.Id_Session_Personne 
	AND form_session.Id=form_session_personne.Id_Session 
	AND form_formation.Id=form_session.Id_Formation
	AND form_formation.Id_TypeFormation=form_typeformation.Id";

if($sansFormation){
    //Le cas d'un QCM sans formation
    $row = getInformationsModificationQCMsansFormation($Id_SessionPersonneQualification);
}
else {
    $result=mysqli_query($bdd,$requete);
    $row=mysqli_fetch_array($result);
}
    
?>
<form id="formulaire" method="POST" action="ModifierQCMSession.php">
	<input type="hidden" name="Id_SessionPersonneQualification" value="<?php echo $Id_SessionPersonneQualification; ?>" />
	<input type="hidden" name="Id_Formation" value="<?php echo $row['ID_FORMATION']; ?>" />
	<table class="TableCompetences" style="width:95%; align:center;">
		<tr>
			<td class="Libelle" style="width:10%;">
				<?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?> :
			</td>
			<td style="width:20%;">
				<?php echo $row['NOM_PRENOM']; ?>
			</td>
		</tr>
		<tr>
			<td class="Libelle" style="width:10%;">
				<?php if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";}?> :
			</td>
			<td style="width:20%;" colspan="3">
				<?php echo $row['Libelle']; ?>
			</td>
		</tr>
		<tr>
			<td class="Libelle" style="width:10%;">
				<?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?> :
			</td>
			<td style="width:20%;">
				<?php echo $row['LIBELLE_TYPEFORMATION']; ?>
			</td>
			<td class="Libelle" style="width:10%;">
				<?php if($LangueAffichage=="FR"){echo "Initial / Recyclage";}else{echo "Initial / Recycling";}?> :
			</td>
			<td style="width:20%;">
				<?php 
				if($sansFormation)
				    echo "Sans formation";
				else
					if($row['Recyclage']==0){
						if($LangueAffichage=="FR"){echo "Initial";}
						else{echo "Initial";}
					}
					else{
						if($LangueAffichage=="FR"){echo "Recyclage";}
						else{echo "Recycling";}
					}
				?>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<?php
			//Liste des qualifications & QCM 
			$req="SELECT DISTINCT form_formation_qualification.Id, new_competences_qualification.Libelle 
					FROM form_formation_qualification
					LEFT JOIN new_competences_qualification 
					ON form_formation_qualification.Id_Qualification=new_competences_qualification.Id
					WHERE form_formation_qualification.Id_Formation=".$row['ID_FORMATION']." 
					AND form_formation_qualification.Suppr=0 
					AND form_formation_qualification.Masquer=0 
					AND new_competences_qualification.Id=".$row['Id_Qualification']."
					AND (SELECT COUNT(form_formation_qualification_qcm.Id) 
						FROM form_formation_qualification_qcm
						WHERE Id_Formation_Qualification=form_formation_qualification.Id 
						AND Suppr=0)>0 ";
			if($sansFormation)
			    $rowQualifForm=getListeQualificationsEtQCMsansFormation($Id_SessionPersonneQualification);
		    else {
		        $resultQualification=mysqli_query($bdd,$req);
		        $nbQualification=mysqli_num_rows($resultQualification);
		        if($nbQualification>0)
		            $rowQualifForm=mysqli_fetch_array($resultQualification);
		    }
		?>
		<tr>
			<td class="Libelle" colspan="2">&bull;
				<?php echo stripslashes($rowQualifForm['Libelle']); ?>
			</td>
		</tr>
		<tr>
			<td class="Libelle" style="width:10%;">
				<?php if($LangueAffichage=="FR"){echo "QCM";}else{echo "MCQ";}?> :
			</td>
			<td style="width:20%;">
				<select name="qcm_mere" id="qcm_mere" onchange="submit()">
					<?php
						$req="SELECT DISTINCT

 
							form_formation_qualification_qcm.Id_QCM,
							form_formation_qualification_qcm.Id_Langue,
							form_qcm.Code AS QCM,
							form_qcm.Id_QCM_Lie,
							(SELECT form_qcm2.Code FROM form_qcm AS form_qcm2 WHERE form_qcm2.Id=form_qcm.Id_QCM_Lie) AS QCMLie


							FROM form_formation_qualification_qcm
							LEFT JOIN form_qcm
							ON form_formation_qualification_qcm.Id_QCM=form_qcm.Id
							WHERE Id_Formation_Qualification=".$rowQualifForm['Id']."
							AND form_formation_qualification_qcm.Suppr=0 
							AND form_qcm.Suppr=0 ";
						$Id_QCM=0;
						$Id_LangueQCM=0;
						$Id_QCMLie=0;
						$CodeQCMLie="";
						if($_POST){$Id_QCM=$_POST['qcm_mere'];}
						else{$Id_QCM=$row['Id_QCM'];}
						
						// le cas nominal et le cas d'un QCM sans formation
						if($sansFormation)
						  $resultQCM=getInfosQCM($Id_SessionPersonneQualification);
						else
						  $resultQCM=mysqli_query($bdd,$req);
						
						$nbQCM=mysqli_num_rows($resultQCM);
						if($nbQCM>0){
							while($rowQCM=mysqli_fetch_array($resultQCM)){
								$selected="";
								if($Id_QCM==0){
									$Id_QCM=$rowQCM['Id_QCM'];
									$Id_LangueQCM=$rowQCM['Id_Langue'];
									$Id_QCMLie=$rowQCM['Id_QCM_Lie'];
									$CodeQCMLie=$rowQCM['QCMLie'];
									$selected="selected";
								}
								elseif($Id_QCM==$rowQCM['Id_QCM']){
									$Id_LangueQCM=$rowQCM['Id_Langue'];
									$Id_QCMLie=$rowQCM['Id_QCM_Lie'];
									$CodeQCMLie=$rowQCM['QCMLie'];
									$selected="selected";
								}
								echo "<option value='".$rowQCM['Id_QCM']."' ".$selected." >".stripslashes($rowQCM['QCM'])."</option>";
							}
						}
						if($_GET){
							if($row['Id_LangueQCM']>0){$Id_LangueQCM=$row['Id_LangueQCM'];}
						}
					?>
				</select>
			</td>
			<td class="Libelle" style="width:10%;">
				<?php if($LangueAffichage=="FR"){echo "Langue";}else{echo "Language";} ?> :
			</td>
			<td style="width:20%;">
				<select name="langue_qcm_mere" id="langue_qcm_mere">
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
			<td height="4"></td>
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
				<input type="hidden" name="qcmlie_fille" value="<?php echo $Id_QCMLie; ?>" />
				<select name="langue_qcmlie_fille" id="langue_qcmlie_fille">
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
			<td height="10"></td>
		</tr>
		<tr class="TitreColsUsers">
			<td colspan="4" align="center">
				<input class="Bouton" name="generer" type="submit" <?php if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}?>>
			</td>
		</tr>

	</table>
</form>
<?php
mysqli_close($bdd);			// Fermeture de la connexion

?>
</body>
</html>
<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Passer un QCM sans session de formation</title><meta name="robots" content="noindex">
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
if($_POST)
{
	if(isset($_POST['generer']))
	{
		if(isset($_POST['Id_Formation']) && isset($_POST['Id_Besoin']))
		{
			//Liste des qualifications
			$req="
                SELECT DISTINCT
                    form_formation_qualification.Id,
				    form_formation_qualification.Id_Qualification
				FROM
                    form_formation_qualification
				LEFT JOIN new_competences_qualification 
				    ON form_formation_qualification.Id_Qualification=new_competences_qualification.Id
				WHERE
                    form_formation_qualification.Id_Formation=".$_POST['Id_Formation']." 
    				AND form_formation_qualification.Suppr=0 
    				AND form_formation_qualification.Masquer=0 
    				AND 
                    (
                        SELECT
                            COUNT(form_formation_qualification_qcm.Id) 
                        FROM
                            form_formation_qualification_qcm
                        WHERE
                            Id_Formation_Qualification=form_formation_qualification.Id 
                            AND Suppr=0
                    )>0 ";
			$resultQualification=mysqli_query($bdd,$req);
			$nbQualification=mysqli_num_rows($resultQualification);
			if($nbQualification>0)
			{
				while($rowQualifForm=mysqli_fetch_array($resultQualification))
				{
					$Id_QCMLie=0;
					$Id_QCMLie_Langue=0;
					if(isset($_POST['qcmlie_'.$rowQualifForm['Id']])){$Id_QCMLie=$_POST['qcmlie_'.$rowQualifForm['Id']];}
					if(isset($_POST['langue_qcmlie_'.$rowQualifForm['Id']])){$Id_QCMLie_Langue=$_POST['langue_qcmlie_'.$rowQualifForm['Id']];}
					passageQualificationsSansSession($_POST['Id_Besoin'],$rowQualifForm['Id_Qualification'],$_POST['qcm_'.$rowQualifForm['Id']],$_POST['langue_qcm_'.$rowQualifForm['Id']],$Id_QCMLie,$Id_QCMLie_Langue);
				}
			}
			echo "<script>FermerEtRecharger();</script>";
		}
	}
}

if($_GET){$Id_Besoin=$_GET['Id_Besoin'];}
else{$Id_Besoin=$_POST['Id_Besoin'];}
$requete="
    SELECT
    	form_besoin.Id AS ID_BESOIN,
    	form_typeformation.Libelle AS LIBELLE_TYPEFORMATION,
    	form_besoin.Id_Formation AS ID_FORMATION,
    	form_formation.Reference AS REFERENCE_FORMATION,
    	form_formation_langue_infos.Libelle AS LIBELLE_FORMATION,
    	new_competences_prestation.Libelle AS LIBELLE_PRESTATION,
    	(SELECT CONCAT(' - ',Libelle) FROM new_competences_pole WHERE new_competences_pole.Id=form_besoin.Id_Pole) AS Pole,
    	CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS NOM_PRENOM,
    	form_besoin.Id_Personne,
    	form_besoin.Motif AS MOTIF_DEMANDE,
    	form_besoin.Date_Demande AS DATE_DEMANDE,
    	(
            SELECT
                (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme)
            FROM
                form_formation_plateforme_parametres 
    		WHERE
                form_formation_plateforme_parametres.Id_Formation=form_besoin.Id_Formation
                AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
                AND Suppr=0
            LIMIT 1
        ) AS Organisme,
    	IF(form_besoin.Motif='Renouvellement',1,0) AS Recyclage,
    	(
            SELECT
                IF(form_besoin.Motif='Renouvellement',LibelleRecyclage,Libelle)
    		FROM
                form_formation_langue_infos
    		WHERE
                Id_Formation=form_besoin.Id_Formation
                AND Id_Langue=
    			(
                    SELECT
                        Id_Langue 
                    FROM
                        form_formation_plateforme_parametres 
                    WHERE
                        Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
                        AND Id_Formation=form_besoin.Id_Formation
                        AND Suppr=0 
                    LIMIT 1
                )
                AND Suppr=0
        ) AS Libelle										
    FROM
    	form_besoin,
    	form_typeformation,
    	form_formation,
    	form_formation_langue_infos,
    	new_rh_etatcivil,
    	new_competences_prestation
    WHERE
    	form_besoin.Id=".$Id_Besoin."
    	AND form_formation.Id=form_besoin.Id_Formation
    	AND form_formation.Id_TypeFormation=form_typeformation.Id
    	AND form_besoin.Id_Prestation=new_competences_prestation.Id
    	AND form_besoin.Id_Personne=new_rh_etatcivil.Id";
$result=mysqli_query($bdd,$requete);
$row=mysqli_fetch_array($result);
?>
<form id="formulaire" method="POST" action="QCM_SansSession.php">
	<input type="hidden" name="Id_Besoin" value="<?php echo $Id_Besoin; ?>" />
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
				<?php if($LangueAffichage=="FR"){echo "Motif";}else{echo "Motif";}?> :
			</td>
			<td style="width:20%;">
				<?php echo $row['MOTIF_DEMANDE']; ?>
			</td>
		</tr>
		<tr>
			<td class="Libelle" style="width:10%;">
				<?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?> :
			</td>
			<td style="width:20%;">
				<?php echo $row['LIBELLE_PRESTATION'].$row['Pole']; ?>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<?php
			//Liste des qualifications & QCM 
			$req="
                SELECT DISTINCT
                    form_formation_qualification.Id,new_competences_qualification.Libelle 
				FROM
                    form_formation_qualification
				LEFT JOIN new_competences_qualification 
					ON form_formation_qualification.Id_Qualification=new_competences_qualification.Id
				WHERE
                    form_formation_qualification.Id_Formation=".$row['ID_FORMATION']." 
					AND form_formation_qualification.Suppr=0 
					AND form_formation_qualification.Masquer=0 
					AND
                    (
                        SELECT
                            COUNT(form_formation_qualification_qcm.Id) 
						FROM
                            form_formation_qualification_qcm
						WHERE
                            Id_Formation_Qualification=form_formation_qualification.Id 
                            AND Suppr=0
                    )>0 ";
			$resultQualification=mysqli_query($bdd,$req);
			$nbQualification=mysqli_num_rows($resultQualification);
			if($nbQualification>0)
			{
				while($rowQualifForm=mysqli_fetch_array($resultQualification))
				{
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
				<select name="qcm_<?php echo $rowQualifForm['Id']; ?>" id="qcm_<?php echo $rowQualifForm['Id']; ?>" onchange="submit()">
				<?php
					$req="
                        SELECT DISTINCT 
                            form_formation_qualification_qcm.Id_QCM,
                            form_formation_qualification_qcm.Id_Langue,
                            form_qcm.Code AS QCM,
                            form_qcm.Id_QCM_Lie,
                            (SELECT form_qcm2.Code FROM form_qcm AS form_qcm2 WHERE form_qcm2.Id=form_qcm.Id_QCM_Lie) AS QCMLie
						FROM
                            form_formation_qualification_qcm
						LEFT JOIN form_qcm
                            ON form_formation_qualification_qcm.Id_QCM=form_qcm.Id
						WHERE
                            Id_Formation_Qualification=".$rowQualifForm['Id']."
                            AND form_formation_qualification_qcm.Suppr=0 
                            AND form_qcm.Suppr=0 ";
					$Id_QCM=0;
					$Id_LangueQCM=0;
					$Id_QCMLie=0;
					$CodeQCMLie="";
					if($_POST){$Id_QCM=$_POST['qcm_'.$rowQualifForm['Id']];}
					$resultQCM=mysqli_query($bdd,$req);
					$nbQCM=mysqli_num_rows($resultQCM);
					if($nbQCM>0)
					{
						while($rowQCM=mysqli_fetch_array($resultQCM))
						{
							$selected="";
							if($Id_QCM==0)
							{
								$Id_QCM=$rowQCM['Id_QCM'];
								$Id_LangueQCM=$rowQCM['Id_Langue'];
								$Id_QCMLie=$rowQCM['Id_QCM_Lie'];
								$CodeQCMLie=$rowQCM['QCMLie'];
								$selected="selected";
							}
							elseif($Id_QCM==$rowQCM['Id_QCM'])
							{
								$Id_LangueQCM=$rowQCM['Id_Langue'];
								$Id_QCMLie=$rowQCM['Id_QCM_Lie'];
								$CodeQCMLie=$rowQCM['QCMLie'];
								$selected="selected";
							}
							echo "<option value='".$rowQCM['Id_QCM']."' ".$selected." >".stripslashes($rowQCM['QCM'])."</option>";
						}
					}
				?>
				</select>
			</td>
			<td class="Libelle" style="width:10%;">
				<?php if($LangueAffichage=="FR"){echo "Langue";}else{echo "Language";}?> :
			</td>
			<td style="width:20%;">
				<select name="langue_qcm_<?php echo $rowQualifForm['Id']; ?>" id="langue_qcm_<?php echo $rowQualifForm['Id']; ?>">
				<?php
					$req="
                        SELECT DISTINCT 
                            form_qcm_langue.Id_Langue,
                            form_langue.Libelle AS Langue
						FROM
                            form_qcm_langue
						LEFT JOIN form_langue
                            ON form_qcm_langue.Id_Langue=form_langue.Id
						WHERE
                            form_qcm_langue.Id_QCM=".$Id_QCM."
                            AND form_qcm_langue.Suppr=0 
                            AND form_langue.Suppr=0 ";
					//if($_POST){$Id_QCM=$_POST['qcm'];}
					$resultLangue=mysqli_query($bdd,$req);
					$nbLangue=mysqli_num_rows($resultLangue);
					if($nbLangue>0)
					{
						while($rowLangue=mysqli_fetch_array($resultLangue))
						{
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
				if($Id_QCMLie>0)
				{
					 if($LangueAffichage=="FR"){echo "QCM lié : ";}
					 else{echo "Linked QCM";}
				}
				?>
			</td>
			<td>
				<?php 
				if($Id_QCMLie>0){echo stripslashes($CodeQCMLie);}
				?>
			
			</td>
			<td class="Libelle">
				<?php 
				if($Id_QCMLie>0)
				{
					 if($LangueAffichage=="FR"){echo "Langue : ";}
					 else{echo "Language : ";}
				}
				?>
			</td>
			<td>
				<?php if($Id_QCMLie>0){ ?>
				<input type="hidden" name="qcmlie_<?php echo $rowQualifForm['Id']; ?>" value="<?php echo $Id_QCMLie; ?>" />
				<select name="langue_qcmlie_<?php echo $rowQualifForm['Id']; ?>" id="langue_qcmlie_<?php echo $rowQualifForm['Id']; ?>">
				<?php
					$req="
                        SELECT DISTINCT 
                            form_qcm_langue.Id_Langue,
                            form_langue.Libelle AS Langue
						FROM
                            form_qcm_langue
						LEFT JOIN form_langue
                            ON form_qcm_langue.Id_Langue=form_langue.Id
						WHERE
                            form_qcm_langue.Id_QCM=".$Id_QCMLie."
                            AND form_qcm_langue.Suppr=0 
                            AND form_langue.Suppr=0 ";
					$resultLangue=mysqli_query($bdd,$req);
					$nbLangue=mysqli_num_rows($resultLangue);
					if($nbLangue>0)
					{
						while($rowLangue=mysqli_fetch_array($resultLangue))
						{
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
		<?php 
			}
		}
		?>
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
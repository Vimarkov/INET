<html>
<head>
	<title>AAA</title><meta name="robots" content="noindex">
	<link href="../../v2/CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Dosis'><link rel="stylesheet" href="../style.css">
	<link href="../css/Switches.css" rel="stylesheet" type="text/css">
	<script src="Corriger_Questionnaire2.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/jquery-1.4.3.min.js"></script>
	<script src="../JS/jquery-ui-1.8.5.min.js"></script>
	<style>
		input:invalid {
		  border: 2px dashed red;
		}

		input:valid {
		  border: 1px solid black;
		}
		
		.actions:invalid {
		  border: 2px dashed red;
		}

		.actions:valid {
		  border: 1px solid black;
		}
	</style>
</head>
<body style="background-color:#cccccc;">

<?php
require("../Connexioni.php");
require("../../v2/Outils/Formation/Globales_Fonctions.php");
require("../../v2/Outils/Fonctions.php");
require("../Menu.php");


$AccesQualite=false;
if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_metier WHERE Id_Metier=85 AND Futur=0 AND Id_Personne=".$_SESSION['Id_Personne']))>0
|| DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation))
|| DroitsFormationPrestation(array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme)))
{
	$AccesQualite=true;
}

$dateDuJour = date("Y/m/d");
if($_POST)
{
	if(isset($_POST['btnEnregistrer']))
	{
		$numActionTraker=0;
		if($_POST["numActionTraker"]<>""){$numActionTraker=$_POST["numActionTraker"];}
		
		$signatureSurveillant=0;
		$signatureSurveille=0;
		if(isset($_POST['signatureSurveillant'])){$signatureSurveillant=1;} 
		if(isset($_POST['signatureSurveille'])){$signatureSurveille=1;} 
		
		//Mise � jour de la surveillance
		$reqUpdateSurv = "UPDATE new_surveillances_surveillance SET ";
		$reqUpdateSurv .= "ID_Prestation=".$_POST["Id_Prestation"].", ";
		$reqUpdateSurv .= "ID_Surveillant=".$_POST["Id_Surveillant"].", ";
		$reqUpdateSurv .= "ID_Surveille=".$_POST["Id_Surveille"].", ";
		$reqUpdateSurv .= "Commentaires='".addslashes($_POST["Commentaire"])."', ";
		$reqUpdateSurv .= "SignatureSurveillant=".$signatureSurveillant.", ";
		$reqUpdateSurv .= "SignatureSurveille=".$signatureSurveille.", ";
		$reqUpdateSurv .= "NumActionTracker=".$numActionTraker." ";
		if($_POST['DatePremierePlannif']<>TrsfDate($_POST['DatePlanif']) || $_POST['DatePremiereRePlannif']<>""){
				$reqUpdateSurv .= ",DateReplanif='".TrsfDate($_POST['DatePlanif'])."' ";
				if($_POST['Etat'] == "Planifi�"){
					$reqUpdateSurv .= ",Etat='Replanifi�' ";
				}
		}
		$reqUpdateSurv .= "WHERE ID=".$_POST['Id'];
		$result=mysqli_query($bdd,$reqUpdateSurv);
		
		if($_POST['Etat2'] == "Planifi�" || $_POST['Etat2'] == "Replanifi�")
		{
			$reqQuestion = "
				SELECT
					new_surveillances_question.ID,
					new_surveillances_question.Modifiable
				FROM
					new_surveillances_question
				LEFT JOIN new_surveillances_questionnaire
					ON new_surveillances_questionnaire.ID = new_surveillances_question.ID_Questionnaire
				WHERE
					new_surveillances_questionnaire.ID =".$_POST['ID_Questionnaire']."
					AND new_surveillances_question.Supprime =0
				ORDER BY
					new_surveillances_question.Numero ;";
			$resultQuest=mysqli_query($bdd,$reqQuestion);
			$nbQuest=mysqli_num_rows($resultQuest);

			$requeteInsert="
				INSERT INTO new_surveillances_surveillance_question
					(
					ID_Question,
					ID_Surveillance,
					QuestionModifiable,
					Etat,
					Commentaire,
					Action
					)
				VALUES ";
			
			$nbActionsTrackers=0;
			
			if($nbQuest>0)
			{
				while($rowQuest=mysqli_fetch_array($resultQuest))
				{
					$requeteInsert.="(";
					$requeteInsert.=$rowQuest['ID'].",";
					$requeteInsert.=$_POST['Id'].",";
					if($rowQuest['Modifiable'] == "0"){$requeteInsert.="'',";}
					else{$requeteInsert.="'".addslashes($_POST['question_'.$rowQuest['ID']])."',";}
					if ($_POST['radio_'.$rowQuest['ID']] == 'NC')
					{
						$requeteInsert.="'NC','";
						$requeteInsert.=addslashes($_POST['observation_'.$rowQuest['ID']])."','";
						$requeteInsert.=addslashes($_POST['action_'.$rowQuest['ID']])."'),";
						
						if($_POST['action_'.$rowQuest['ID']]=="Action imm�diate + Action Tracker" || $_POST['action_'.$rowQuest['ID']]=="Immediate action + Action Tracker"){$nbActionsTrackers++;}
					}
					elseif ($_POST['radio_'.$rowQuest['ID']] == 'C')
					{
						$requeteInsert.="'C','";
						$requeteInsert.=addslashes($_POST['observation_'.$rowQuest['ID']])."','";
						$requeteInsert.=addslashes($_POST['action_'.$rowQuest['ID']])."'),";
						if($_POST['action_'.$rowQuest['ID']]=="Action imm�diate + Action Tracker" || $_POST['action_'.$rowQuest['ID']]=="Immediate action + Action Tracker"){$nbActionsTrackers++;}
					}
					elseif ($_POST['radio_'.$rowQuest['ID']] == 'NA')
					{
						$requeteInsert.="'NA','";
						$requeteInsert.=addslashes($_POST['observation_'.$rowQuest['ID']])."','";
						$requeteInsert.="'),";
					}
				}
				$requeteInsert = substr($requeteInsert,0,-1);
			}
			$requeteUpdate="UPDATE new_surveillances_surveillance ";
			$nbAction=1;
			if($nbActionsTrackers>0 && $numActionTraker==0){$nbAction=0;}
			if (isset($_POST['signatureSurveillant']) && isset($_POST['signatureSurveille']) && $nbAction==1)
			{
				$requeteUpdate.="SET Etat='Cl�tur�', ";
				$requeteUpdate.="DateCloture='".$dateDuJour."' ";
			}
			else
			{
				$requeteUpdate.="SET Etat='R�alis�', ";
				$requeteUpdate.="DateCloture=0 ";
			}
			$requeteUpdate.="WHERE ID=".$_POST['Id'];
			
			$result=mysqli_query($bdd,$requeteInsert);
			$result=mysqli_query($bdd,$requeteUpdate);
		}
		else
		{
			$reqQuest = "
				SELECT
					new_surveillances_surveillance_question.ID,
					new_surveillances_question.Numero,
					new_surveillances_question.Question,
					new_surveillances_question.Question_EN,
					new_surveillances_question.Modifiable,
					new_surveillances_surveillance_question.QuestionModifiable,
					new_surveillances_surveillance_question.Etat,
					new_surveillances_surveillance_question.Commentaire,
					new_surveillances_surveillance_question.Action,
					new_surveillances_surveillance_question.Cloturee
				FROM
					new_surveillances_question
				LEFT JOIN new_surveillances_surveillance_question
					ON new_surveillances_question.ID = new_surveillances_surveillance_question.ID_Question
				WHERE
					new_surveillances_surveillance_question.ID_Surveillance =".$_POST['Id']."
				ORDER BY
					new_surveillances_question.Numero ;";
			
			
			$resultQuest=mysqli_query($bdd,$reqQuest);
			$nbQuest=mysqli_num_rows($resultQuest);
			$nbActionsTrackers=0;
			if ($nbQuest>0)
			{
				while($rowQuest=mysqli_fetch_array($resultQuest))
				{
					$reqUpdate = "UPDATE new_surveillances_surveillance_question SET ";
					if($rowQuest['Modifiable'] == "0"){$reqUpdate.="QuestionModifiable='', ";}
					else{$reqUpdate.="QuestionModifiable='".addslashes($_POST['question_'.$rowQuest['ID']])."', ";}
					if ($_POST['radio_'.$rowQuest['ID']] == 'NC')
					{
						$reqUpdate .= "Etat='NC', ";
						$reqUpdate .= "Commentaire='".addslashes($_POST['observation_'.$rowQuest['ID']])."', ";
						$reqUpdate .= "Action='".addslashes($_POST['action_'.$rowQuest['ID']])."' ";
						if($_POST['action_'.$rowQuest['ID']]=="Action imm�diate + Action Tracker" || $_POST['action_'.$rowQuest['ID']]=="Immediate action + Action Tracker"){$nbActionsTrackers++;}
					}
					elseif ($_POST['radio_'.$rowQuest['ID']] == 'C')
					{
						$reqUpdate .= "Etat='C', ";
						$reqUpdate .= "Commentaire='".addslashes($_POST['observation_'.$rowQuest['ID']])."', ";
						$reqUpdate .= "Action='".addslashes($_POST['action_'.$rowQuest['ID']])."' ";
						if($_POST['action_'.$rowQuest['ID']]=="Action imm�diate + Action Tracker" || $_POST['action_'.$rowQuest['ID']]=="Immediate action + Action Tracker"){$nbActionsTrackers++;}
					}
					elseif ($_POST['radio_'.$rowQuest['ID']] == 'NA')
					{
						$reqUpdate .= "Etat='NA', ";
						$reqUpdate .= "Commentaire='".addslashes($_POST['observation_'.$rowQuest['ID']])."', ";
						$reqUpdate .= "Action='' ";
					}
					$reqUpdate .= "WHERE ID=".$rowQuest['ID'].";";
					$result=mysqli_query($bdd,$reqUpdate);
				}
				$requeteUpdate2="UPDATE new_surveillances_surveillance ";
				$nbAction=1;
				if($nbActionsTrackers>0 && $numActionTraker==0){$nbAction=0;}
				if (isset($_POST['signatureSurveillant']) && isset($_POST['signatureSurveille']) && $nbAction==1)
				{
					$requeteUpdate2.="SET Etat='Cl�tur�', ";
					$requeteUpdate2.="DateCloture='".$dateDuJour."' ";
				}
				else
				{
					$requeteUpdate2.="SET Etat='R�alis�', ";
					$requeteUpdate2.="DateCloture=0 ";
				}
				$requeteUpdate2.="WHERE ID=".$_POST['Id'];
				$result=mysqli_query($bdd,$requeteUpdate2);
			}
		}
		echo "<script>Recharger2();</script>";
	}
}

$requeteSurveillance = "
    SELECT
        new_surveillances_surveillance.ID,
		new_surveillances_surveillance.Commentaires,
        (SELECT new_surveillances_theme.Nom FROM new_surveillances_theme WHERE new_surveillances_theme.ID = new_surveillances_questionnaire.ID_Theme) AS Theme,
        new_surveillances_questionnaire.Nom AS Questionnaire,
        new_surveillances_questionnaire.ID AS ID_Questionnaire,
		new_surveillances_questionnaire.ID_Theme,
        new_competences_prestation.Id_Plateforme AS Id_Plateforme,
        (SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.ID = new_competences_prestation.Id_Plateforme) AS Plateforme,
        new_competences_prestation.Libelle AS Prestation,
        new_competences_prestation.Id AS Id_Prestation,
        new_surveillances_surveillance.ID_Surveille AS ID_Surveille,
        new_surveillances_surveillance.ID_Surveillant AS ID_Surveillant,
        (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.ID = new_surveillances_surveillance.ID_Surveille) AS Surveille,
        (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.ID = new_surveillances_surveillance.ID_Surveillant) AS Surveillant,
        new_surveillances_surveillance.DatePlanif AS DatePlanif,
        new_surveillances_surveillance.DateReplanif AS DateReplanif,
        IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS DateSurveillance,
        IF(new_surveillances_surveillance.Etat='Cl�tur�' OR new_surveillances_surveillance.Etat='R�alis�','Cl�tur�','Planifi�') AS Etat,
		new_surveillances_surveillance.Etat AS Etat2,
		new_surveillances_surveillance.NumActionTracker,SignatureSurveillant,SignatureSurveille
    FROM
        (
            (
            new_surveillances_surveillance
            LEFT JOIN new_competences_prestation
                ON new_surveillances_surveillance.ID_Prestation = new_competences_prestation.Id
            )
        LEFT JOIN new_surveillances_questionnaire
            ON new_surveillances_surveillance.ID_Questionnaire = new_surveillances_questionnaire.Id
        ) ";
if($_GET){$requeteSurveillance.=" WHERE new_surveillances_surveillance.ID=".$_GET['Id'];}
else{$requeteSurveillance.=" WHERE new_surveillances_surveillance.ID=".$_POST['Id'];}
$resultSurveillance=mysqli_query($bdd,$requeteSurveillance);
$LigneSurveillance=mysqli_fetch_array($resultSurveillance);

//Questionnaire
if ($LigneSurveillance['Etat2'] == "Planifi�" || $LigneSurveillance['Etat2'] == "Replanifi�")
{
	$reqQuestion = "
        SELECT
            new_surveillances_question.ID,
            new_surveillances_question.Numero,
            new_surveillances_question.Question,
            new_surveillances_question.Question_EN,
			new_surveillances_question.Reponse,
			new_surveillances_question.Reponse_EN,
            new_surveillances_question.Modifiable
        FROM
            new_surveillances_question
        LEFT JOIN new_surveillances_questionnaire
            ON new_surveillances_questionnaire.ID = new_surveillances_question.ID_Questionnaire
        WHERE
            new_surveillances_questionnaire.ID =".$LigneSurveillance['ID_Questionnaire']."
            AND new_surveillances_question.Supprime =0
        ORDER BY
            new_surveillances_question.Numero ;";
	$resultQuestion=mysqli_query($bdd,$reqQuestion);
	$nbQuestion=mysqli_num_rows($resultQuestion);
}
else
{
	$reqQuestionSurveillance = "
        SELECT
            new_surveillances_surveillance_question.ID,
            new_surveillances_question.Numero,
            new_surveillances_question.Question,
            new_surveillances_question.Question_EN,
			new_surveillances_question.Reponse,
			new_surveillances_question.Reponse_EN,
            new_surveillances_question.Modifiable,
            new_surveillances_surveillance_question.QuestionModifiable,
            new_surveillances_surveillance_question.Etat,
            new_surveillances_surveillance_question.Commentaire,
            new_surveillances_surveillance_question.Action,
            new_surveillances_surveillance_question.Cloturee
        FROM
            new_surveillances_question
        LEFT JOIN new_surveillances_surveillance_question
            ON new_surveillances_question.ID = new_surveillances_surveillance_question.ID_Question
        WHERE
            new_surveillances_surveillance_question.ID_Surveillance =".$LigneSurveillance['ID']."
        ORDER BY
            new_surveillances_question.Numero ;";
	$resultQuestion=mysqli_query($bdd,$reqQuestionSurveillance);
	$nbQuestion=mysqli_num_rows($resultQuestion);
}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

	<form id="formulaire" method="POST" action="Modifier_Surveillance.php" onSubmit="return VerifChamps();">
	<input type="hidden" name="Id" value="<?php echo $LigneSurveillance['ID']?>">
	<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
	<input type="hidden" name="Etat" value="<?php echo $LigneSurveillance['Etat']?>">
	<input type="hidden" name="Etat2" value="<?php echo $LigneSurveillance['Etat2']?>">
	<input type="hidden" name="ID_Questionnaire" value="<?php echo $LigneSurveillance['ID_Questionnaire']?>">
	<input type="hidden" name="DatePremierePlannif" value="<?php echo $LigneSurveillance['DatePlanif']?>">
	<input type="hidden" name="DatePremiereRePlannif" value="<?php echo $LigneSurveillance['DateReplanif']?>">
	<input type="hidden" name="leSurveillant" value="<?php echo $LigneSurveillance['ID_Surveillant']?>">
	<table style="width:100%; align:center;">
		<tr bgcolor="#91dfff" >
			<td colspan="2" style="width:70px;height:30px;border-spacing:0;text-align:center;color:#00577c;valign:top;font-weight:bold;border-style:outset;">
				<span style="font-size:3em;">
				SODA v0.1 (Alpha)<br>
				</span>
				<span style="font-size:2.5em;">
				<?php if($LangueAffichage=="FR"){echo "Surveillance Op�rationnel Digital Adaptative";}else{echo "Digital Adaptive Operational Monitoring";}?>
				</span>
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
		<tr><td valign="top">
			<table class="TableCompetences" cellpadding="0" cellspacing="0" style="width:100%; align:center;">
				<tr>
					<td width="30%" style="font-size:2em;color:#00567c;"><?php if($_SESSION['Langue']=="FR"){echo "Entit� : ";}else{echo "Entity : ";}?></td>
					<td width="1%">&nbsp;</td>
					<td width="40%" style="font-size:2em;color:#00567c;"><?php if($_SESSION['Langue']=="FR"){echo "Prestation : ";}else{echo "Activity : ";}?></td>
					<td width="1%">&nbsp;</td>
					<td width="28%" style="font-size:2em;color:#00567c;"><?php if($_SESSION['Langue']=="FR"){echo "Surveill� : ";}else{echo "Supervised : ";}?></td>
				</tr>
				<tr>
					<td>
						<select style="font-size:3em;width:250px;" id="Id_Plateforme" name="Id_Plateforme" onchange="submit();" >
							<option value="0"></option>
							<?php
							$Id_Plateforme=0;
							if($_GET){$Id_Plateforme=$LigneSurveillance['Id_Plateforme'];$Id_Prestation=0;}
							elseif($_POST){$Id_Plateforme=$_POST['Id_Plateforme'];$Id_Prestation=0;}
							$reqPlat="SELECT Id, Libelle FROM new_competences_plateforme WHERE Id<>11 AND Id<>14 ";
							if(DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation))){
								
							}
							else{
								$reqPlat.="AND (Id IN (
									SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
									AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.")
								)
								OR 
								Id IN (
									SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) 
									FROM new_competences_personne_poste_prestation 
									WHERE Id_Personne=".$_SESSION['Id_Personne']."
									AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
								)
								)";
							}
							$reqPlat.=" ORDER BY Libelle ASC";
							$result2=mysqli_query($bdd,$reqPlat);
							while($row2=mysqli_fetch_array($result2))
							{
								$selected="";
								if($Id_Plateforme==$row2['Id']){$selected="selected";}
								echo "<option value='".$row2['Id']."' ".$selected.">".$row2['Libelle']."</option>";
							}
							?>
						</select>
					</td>
					<td>&nbsp;</td>
					<td>
						<select style="font-size:3em;width:230px;" id="Id_Prestation" name="Id_Prestation" onchange="submit();">
							<option value="0"></option>
							<?php
								$Id_Prestation=0;
								if($_GET){$Id_Prestation=$LigneSurveillance['Id_Prestation'];}
								elseif($_POST){if(isset($_POST['Id_Prestation'])){$Id_Prestation=$_POST['Id_Prestation'];}}
							
								$requete_Prestation="SELECT Id, Id_Plateforme, LEFT(Libelle,7) AS Libelle 
								FROM new_competences_prestation 
								WHERE Id_Plateforme=".$Id_Plateforme." ";
								if(DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation))){
									
								}
								else{
									$requete_Prestation.="AND (Id_Plateforme IN (
										SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
										AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.")
									)
									OR 
									Id IN (
										SELECT Id_Prestation 
										FROM new_competences_personne_poste_prestation 
										WHERE Id_Personne=".$_SESSION['Id_Personne']."
										AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
										)
									) ";
								}
								$requete_Prestation.="AND Active=0
								ORDER BY Libelle ASC";
								$result_Prestation= mysqli_query($bdd,$requete_Prestation);
								
								$trouve=0;
								while ($rowPrestation=mysqli_fetch_array($result_Prestation))
								{
									$selected="";
									if($Id_Prestation==$rowPrestation['Id']){$selected="selected";$trouve=1;}
									echo "<option value='".$rowPrestation['Id']."' ".$selected." >".$rowPrestation['Libelle']."</option>";
								}
								if($trouve==0){$Id_Prestation=0;}
							?>
						</select>
					</td>
					<td>&nbsp;</td>
					<td>
						<select style="font-size:2.5em;width:230px;" id="Id_Surveille" name="Id_Surveille">
							<option value="0"></option>
							<?php
							$Id_Surveille=0;
							if($_GET){$Id_Surveille=$LigneSurveillance['ID_Surveille'];}
							elseif($_POST){if(isset($_POST['Id_Surveille'])){$Id_Surveille=$_POST['Id_Surveille'];}}
							
							$requetePersonne="
                                SELECT DISTINCT
                                    new_rh_etatcivil.Id,
                                    CONCAT(Nom, ' ', Prenom) as NomPrenom
                                FROM
                                    new_rh_etatcivil
                                INNER JOIN new_competences_personne_prestation
                                    ON new_rh_etatcivil.Id=new_competences_personne_prestation.Id_Personne
                                WHERE
                                    (new_competences_personne_prestation.Id_Prestation=".$Id_Prestation." 
									AND Date_Debut<='".date('Y-m-d')."'
									AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01'))
									OR new_rh_etatcivil.Id=".$LigneSurveillance['ID_Surveille']."
								ORDER BY NomPrenom ASC";
							$result_Personne= mysqli_query($bdd,$requetePersonne);
							while ($row_Personne=mysqli_fetch_array($result_Personne))
							{
								$selected="";
								if($Id_Surveille==$row_Personne['Id']){$selected="selected";}
								echo "<option value='".$row_Personne['Id']."' ".$selected.">".$row_Personne['NomPrenom']."</option>\n";
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td height="5px;"></td>
				</tr>
				<tr>
					<td style="font-size:2em;color:#00567c;"><?php if($_SESSION['Langue']=="FR"){echo "Surveillant : ";}else{echo "Supervisor : ";}?></td>
					<td>&nbsp;</td>
					<td style="font-size:2em;color:#00567c;"><?php if($_SESSION['Langue']=="FR"){echo "Date surveillance : ";}else{echo "Monitoring date  : ";}?></td>
					<td>&nbsp;</td>
					<td style="font-size:2em;color:#00567c;"></td>
				</tr>
				<tr>
					<td>
						<select style="font-size:3em;width:250px;" id="Id_Surveillant" name="Id_Surveillant">
							<option value="0"></option>
							<?php
							$Id_Surveillant=0;
							if($_GET){$Id_Surveillant=$LigneSurveillance['ID_Surveillant'];}
							elseif($_POST){if(isset($_POST['Id_Surveillant'])){$Id_Surveillant=$_POST['Id_Surveillant'];}}
							$requetePersonne="
                                SELECT DISTINCT
                                    new_rh_etatcivil.Id,
                                    CONCAT(Nom, ' ', Prenom) as NomPrenom
                                FROM
                                    new_rh_etatcivil
                                INNER JOIN new_competences_personne_prestation
                                    ON new_rh_etatcivil.Id=new_competences_personne_prestation.Id_Personne
                                WHERE
                                    (new_competences_personne_prestation.Id_Prestation=".$Id_Prestation." 
									AND Date_Debut<='".date('Y-m-d')."'
									AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01'))
									OR new_rh_etatcivil.Id=".$_SESSION['Id_Personne']."
									OR new_rh_etatcivil.Id=".$LigneSurveillance['ID_Surveillant']."
								ORDER BY NomPrenom ASC";
							$result_Personne= mysqli_query($bdd,$requetePersonne);
							while ($row_Personne=mysqli_fetch_array($result_Personne))
							{
								$selected="";
								if($Id_Surveillant==$row_Personne['Id']){$selected="selected";}
								echo "<option value='".$row_Personne['Id']."' ".$selected.">".$row_Personne['NomPrenom']."</option>\n";
							}
							?>
						</select>
					</td>
					<td>&nbsp;</td>
					<?php
						if($_GET){$DatePlanif=AfficheDateFR($LigneSurveillance['DatePlanif']);}
						elseif($_POST){if(isset($_POST['DatePlanif'])){$DatePlanif=$_POST['DatePlanif'];}}
					?>
					<td colspan="3">
						<input style="font-size:3em;width:350px;" id="DatePlanif" type="date" id="DatePlanif" name="DatePlanif" value="<?php echo $DatePlanif;?>">
					</td>
				</tr>
				<tr>
					<td height="5px;"></td>
				</tr>
				<tr>
					<td style="font-size:2em;color:#00567c;"><?php if($_SESSION['Langue']=="FR"){echo "Th�matique : ";}else{echo "Theme : ";}?></td>
					<td>&nbsp;</td>
					<td style="font-size:2em;color:#00567c;"><?php if($_SESSION['Langue']=="FR"){echo "Questionnaire : ";}else{echo "Questionnaire : ";}?></td>
					<td>&nbsp;</td>
					<td style="font-size:2em;color:#00567c;"></td>
				</tr>
				<tr>
					<td>
						<select style="font-size:3em;width:250px;" name="Id_Theme_Questionnaire" id="Id_Theme_Questionnaire" onchange="submit();">
							<option value="0" selected></option>
						<?php
						$Id_Theme=0;
						if($_GET){$Id_Theme=$LigneSurveillance['ID_Theme'];}
						elseif($_POST){if(isset($_POST['Id_Theme_Questionnaire'])){$Id_Theme=$_POST['Id_Theme_Questionnaire'];}}
						$result2=mysqli_query($bdd,"SELECT ID, Nom FROM new_surveillances_theme ORDER BY Nom ASC");
						while($row2=mysqli_fetch_array($result2))
						{
							$selected="";
							if($Id_Theme==$row2['ID']){$selected="selected";}
							echo "<option value='".$row2['ID']."' ".$selected.">".$row2['Nom']."</option>";
						}
						?>
						</select>
					</td>
					<td>&nbsp;</td>
					<td colspan="3">
						<select style="font-size:3em;width:500px;" name="Id_Questionnaire" id="Id_Questionnaire" onchange="submit();">
							<option value="0" selected></option>
						<?php
						$Id_Questionnaire=0;
						if($_GET){$Id_Questionnaire=$LigneSurveillance['ID_Questionnaire'];}
						elseif($_POST){if(isset($_POST['Id_Questionnaire'])){$Id_Questionnaire=$_POST['Id_Questionnaire'];}}
						$requete_Questionnaire="SELECT ID, ID_Plateforme, ID_Theme, CONCAT(new_surveillances_questionnaire.Nom,' ',IF(Actif=0,'[Actif]','[Inactif]')) AS Nom 
							FROM new_surveillances_questionnaire
							WHERE Supprime=0 
							AND ID_Theme=".$Id_Theme."
							ORDER BY Actif, Nom ASC";
						$result2=mysqli_query($bdd,$requete_Questionnaire);
						while($row2=mysqli_fetch_array($result2))
						{
							$selected="";
							if($Id_Questionnaire==$row2['ID']){$selected="selected";}
							echo "<option value='".$row2['ID']."' ".$selected.">".$row2['Nom']."</option>";
						}
						?>
						</select>
					</td>
				</tr>
			</table>
		</td></tr>
		<tr><td height="10"></td></tr>
		<tr>
		<?php 
			$checkedcb="";
				if ($LigneSurveillance['Etat2'] <> "Planifi�" && $LigneSurveillance['Etat2'] <> "Replanifi�"){$checkedcb="checked";}
		?>
			<td align="right" style="font-size:2em;color:#00567c;">
				<input type="checkbox" style="height: 40px; width: 40px;" id="CB_NC" name="CB_NC" onchange="javascript:AfficherMasquerlesNC()" <?php echo $checkedcb;?> />
				<?php if($_SESSION['Langue']=="FR"){echo "R�capitulatif des Non-Conformit�s";}else{echo "Summary of Non-Conformities";}?>
			</td>
		</tr>
		<?php if($Id_Questionnaire>0){ ?>
		<tr><td valign="top">
			<table style="width:100%; align:center;" class="TableCompetences">
				<tr align="center">
					<td class="EnTeteTableauCompetences" style="font-size:2em;color:#00567c;" width="5%">N�</td>
					<td class="EnTeteTableauCompetences" style="font-size:2em;color:#00567c;" width="65%">Question</td>
					<td class="EnTeteTableauCompetences" style="font-size:2em;color:#00567c;" width="30%"><?php if($_SESSION['Langue']=="FR"){echo "Conformit�";}else{echo "Conformity";}?></td>
				</tr>
				<?php
					$total = 0;
					$C = 0;
					
					$idsQuestion="";
					if($nbQuestion > 0)
					{
						while($rowQuestion=mysqli_fetch_array($resultQuestion))
						{
							if($idsQuestion<>""){$idsQuestion.=";";}
							$idsQuestion.=$rowQuestion['ID'];
							
							echo "<tr id='tr1_".$rowQuestion['ID']."'>";
								echo "<td style='font-size:2em;'>".$rowQuestion['Numero']."</td>";
								echo "<td style='font-size:2em;'>";
								if($rowQuestion['Modifiable'] == "0"){
									if($_SESSION['Langue']=="FR"){
										echo "<b>Q</b> ".$rowQuestion['Question']."<br><br><span style='color:#0a7800;font-size:0.8em;'><b>R</b> ".$rowQuestion['Reponse']."</span>";
									}
									else{
										echo "<b>Q</b> ".$rowQuestion['Question_EN']."<br><br><span style='color:#0a7800;font-size:0.8em;'><b>R</b> ".$rowQuestion['Reponse_EN']."</span> ";
									}
								}
								else
								{
									$laQuestion = "";
									if($LigneSurveillance['Etat'] == "Planifi�" || $LigneSurveillance['Etat'] == "Replanifi�"){
										if($_SESSION['Langue']=="FR"){
											$laQuestion = $rowQuestion['Question'];
										}
										else{
											$laQuestion = $rowQuestion['Question_EN'];
										}
									}
									else{
										$laQuestion = $rowQuestion['QuestionModifiable'];
									}
								?>
									<input style="font-size:1em;" size="40" type="text" id="<?php echo "question_".$rowQuestion['ID'];?>" name="<?php echo "question_".$rowQuestion['ID'];?>" value="<?php echo $laQuestion;?>">
								<?php
								}
								echo "</td>";
							
							
							$checkC = "";
							$checkNC = "";
							$checkNA = "";
							$observation ="";
							$action = "";
							$cloture = "";
							
							if ($LigneSurveillance['Etat2'] <> "Planifi�" && $LigneSurveillance['Etat2'] <> "Replanifi�")
							{	
								if ($rowQuestion['Etat'] == "NC")
								{
									$checkNC = "checked";
									$total++;
								}
								elseif ($rowQuestion['Etat'] == "C")
								{
									$checkC = "checked";
									$total++;
									$C++;
								}
								elseif($rowQuestion['Etat'] == "NA"){
									$checkNA = "checked";
								}
								$observation = $rowQuestion['Commentaire'];
								$action = $rowQuestion['Action'];
								$cloture = $rowQuestion['Cloturee'];
							}
							else{
								if($rowQuestion['Modifiable'] <> "0"){
									$checkNA = "checked";
									$observation ="Non utilis�";
									$total++;
									$C++;
								}
							}
							
							if($_SESSION['Langue']=="FR"){
							echo "<td style='font-size:2.8em;'>
									<input style='height: 40px; width: 40px;' onchange=\"Change_Note('".$_SESSION['Langue']."')\" class='radioNote' type='radio' name='radio_".$rowQuestion['ID']."' value='C' ".$checkC.">Conforme<br><br>
									<input style='height: 40px; width: 40px;' onchange=\"Change_Note('".$_SESSION['Langue']."')\" class='radioNote' type='radio' name='radio_".$rowQuestion['ID']."' value='NC' ".$checkNC.">Non conforme<br><br>
									<input style='height: 40px; width: 40px;' onchange=\"Change_Note('".$_SESSION['Langue']."')\" class='radioNote' type='radio' name='radio_".$rowQuestion['ID']."' value='NA' ".$checkNA.">Non applicable
								</td>";
							}
							else{
							echo "<td style='font-size:2.8em;'>
									<input style='height: 40px; width: 40px;' onchange=\"Change_Note('".$_SESSION['Langue']."')\" class='radioNote' type='radio' name='radio_".$rowQuestion['ID']."' value='C' ".$checkC.">Compliant<br><br>
									<input style='height: 40px; width: 40px;' onchange=\"Change_Note('".$_SESSION['Langue']."')\" class='radioNote' type='radio' name='radio_".$rowQuestion['ID']."' value='NC' ".$checkNC.">Improper<br><br>
									<input style='height: 40px; width: 40px;' onchange=\"Change_Note('".$_SESSION['Langue']."')\" class='radioNote' type='radio' name='radio_".$rowQuestion['ID']."' value='NA' ".$checkNA.">Not applicable
								</td>";	
							}
							?>
						</tr>
						<tr height="5px" id="tr2_<?php echo $rowQuestion['ID']; ?>" style="display:none;">
						
						</tr>
						<tr id="tr_<?php echo $rowQuestion['ID']; ?>" style="display:none;">
							<td colspan="3">
							<?php
								if($_SESSION['Langue']=="FR"){$place= "Description de la NC / Cause de la non applicabilit�";}
								else{$place= "NC Description / Cause of non-applicability";}
							?>
								<input style="font-size:3em;width:570px;" placeholder="<?php echo $place;?>" type="text" id="<?php echo "observation_".$rowQuestion['ID'];?>" name="<?php echo "observation_".$rowQuestion['ID'];?>" value="<?php echo stripslashes($observation);?>">
								<br><br>
								<select style="font-size:3em;width:570px;" class="actions" id="<?php echo "action_".$rowQuestion['ID'];?>" name="<?php echo "action_".$rowQuestion['ID'];?>">
								<?php 
									if($_SESSION['Langue']=="FR"){
										if($action==""){echo "<option value='' selected>Action</option>";}
										else{
											if($action<>"Action imm�diate" && $action<>"Action imm�diate + Action Tracker"){echo "<option value='".$action."' selected>".$action."</option>";}
										}
										$selected="";
										if($action=="Action imm�diate"){$selected="selected";}
										echo "<option value='Action imm�diate' ".$selected.">Action imm�diate</option>";
										$selected="";
										if($action=="Action imm�diate + Action Tracker"){$selected="selected";}
										echo "<option value='Action imm�diate + Action Tracker' ".$selected.">Action imm�diate + Action Tracker</option>";
									}
									else{
										if($action==""){echo "<option value='' selected>Action</option>";}
										else{
											if($action<>"Immediate action" && $action<>"Immediate action + Action Tracker"){echo "<option value='".$action."' selected>".$action."</option>";}
										}
										$selected="";
										if($action=="Immediate action"){$selected="selected";}
										echo "<option value='Immediate action' ".$selected.">Immediate action</option>";
										$selected="";
										if($action=="Immediate action + Action Tracker"){$selected="selected";}
										echo "<option value='Immediate action + Action Tracker' ".$selected.">Immediate action + Action Tracker</option>";
									}
								?>
								</select>
							</td>
						</tr>
						<tr id="tr3_<?php echo $rowQuestion['ID']; ?>">
							<td style="border-bottom:1px #d9d9d7 solid;" colspan="3"></td>
						</tr>
							<?php
						}
					}
				?>
				<tr><td height="10"></td></tr>
				<tr>
					<td style="font-size:2em;color:#00567c;" colspan="3" align="left" class="Libelle">
						<?php if($_SESSION['Langue']=="FR"){echo "El�ments de preuve, commentaires & observations :";}else{echo "Evidence, Comments & Observations :";}?>
						<textarea name="Commentaire" rows="5" cols="60" style="font-size:1.5em;resize:none"><?php echo stripslashes($LigneSurveillance['Commentaires']);?></textarea>
					</td>
				</tr>
				<tr><td height="5"></td></tr>
				<tr>
					<td style="font-size:2em;color:#00567c;" colspan="3" align="left" class="Libelle">
						Note :
						<?php
							$note = "100";
						?>
						<input style="font-size:2em;" readonly id="note" type="text" value="<?php echo $note."%" ?>" size="5"/>
						
						<input id="score" name="score" type="hidden" value="<?php echo $note ?>" size="5"/>
					</td>
				</tr>
				<tr><td height="5"></td></tr>
				<tr>
					<td colspan="3" style="font-size:2em;color:#00567c;" align="left" class="Libelle">
						<?php if($_SESSION['Langue']=="FR"){echo "N� fiche Action Tracker :";}else{echo "Action Tracker form #:";}?>
						<input style="font-size:2em;" onKeyUp="nombre(this)" name="numActionTraker" id="numActionTraker" type="text" value="<?php if($LigneSurveillance['NumActionTracker']<>0){echo $LigneSurveillance['NumActionTracker'];} ?>" size="10"/>
					</td>
				</tr>
				<tr><td height="5"></td></tr>
				<tr>
					<td colspan="3" style="font-size:2em;" class="Libelle">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="height: 50px; width: 50px;" type="checkbox" class="signatures" name="signatureSurveillant" value="signatureSurveillant" <?php if($LigneSurveillance['SignatureSurveillant']==1){echo "checked";} ?>>
						&nbsp;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="FR"){echo "Le Surveillant confirme la r�alisation de la surveillance et les potentielles actions qui en d�coulent";}else{echo "The Supervisor confirms the completion of the surveillance and the potential actions arising from it";}?>
					</td>
				</tr>
				<tr><td height="15"></td></tr>
				<tr>
					<td colspan="3" style="font-size:2em;" class="Libelle">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="height: 50px; width: 50px;" type="checkbox" class="signatures" name="signatureSurveille" value="signatureSurveille" <?php if($LigneSurveillance['SignatureSurveille']==1){echo "checked";} ?>>
						&nbsp;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="FR"){echo "Le Surveill� accepte le constat r�alis� lors de cette surveillance";}else{echo "The Supervised person�s accepts the observation done during this surveillance";}?>
					</td>
				</tr>
				<tr><td height="20"></td></tr>
				<?php
					if($AccesQualite || $LigneSurveillance['ID_Surveillant']==$_SESSION['Id_Personne'])
					{
				?>
				<tr>
					<td colspan="7" align="center">
						<input style="font-size:2em;" class="Bouton" type="submit" name="btnEnregistrer" value="<?php if($_SESSION['Langue']=="FR"){echo "Enregistrer";}else{echo "Save";}?>">
					</td>
				</tr>
				<?php } ?>
				<tr><td height="50"></td></tr>
			</table>
		</td></tr>
		<?php } ?>
	</table>
	<input name="idsQuestion" id="idsQuestion" hidden value="<?php echo $idsQuestion;?>" />
	</form>
<?php
	echo "<script>Change_Note();</script>";
	echo "<script>AfficherMasquerlesNC();</script>";
?>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script><script  src="../script.js"></script>
</body>
</html>
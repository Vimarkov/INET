<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require_once '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require_once '../Fonctions.php';

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

//Ligne En-tete
if($_SESSION['Langue']=="FR"){
	$sheet->setCellValue('A1',utf8_encode('N°'));
	$sheet->setCellValue('B1',utf8_encode('Entité'));
	$sheet->setCellValue('C1',utf8_encode('Prestation'));
	$sheet->setCellValue('D1',utf8_encode('Thème'));
	$sheet->setCellValue('E1',utf8_encode('Questionnaire'));
	$sheet->setCellValue('F1',utf8_encode('Etat'));
	$sheet->setCellValue('G1',utf8_encode('Note'));
	$sheet->setCellValue('H1',utf8_encode('N° Action Tracker'));
	$sheet->setCellValue('I1',utf8_encode('Date'));
	$sheet->setCellValue('J1',utf8_encode('Surveillé'));
	$sheet->setCellValue('K1',utf8_encode('Surveillant'));
	$sheet->setCellValue('L1',utf8_encode('Question'));
	$sheet->setCellValue('M1',utf8_encode('Réponse'));
	$sheet->setCellValue('N1',utf8_encode('Conformité'));
	$sheet->setCellValue('O1',utf8_encode('Commentaire'));
	$sheet->setCellValue('P1',utf8_encode('Action'));
	$sheet->setCellValue('Q1',utf8_encode('Cause de la N/A'));
	$sheet->setCellValue('R1',utf8_encode('Pondération'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('N°'));
	$sheet->setCellValue('B1',utf8_encode('Entity'));
	$sheet->setCellValue('C1',utf8_encode('Activity'));
	$sheet->setCellValue('D1',utf8_encode('Theme'));
	$sheet->setCellValue('E1',utf8_encode('Questionnaire'));
	$sheet->setCellValue('F1',utf8_encode('State'));
	$sheet->setCellValue('G1',utf8_encode('Score'));
	$sheet->setCellValue('H1',utf8_encode('Action Tracker number'));
	$sheet->setCellValue('I1',utf8_encode('Date'));
	$sheet->setCellValue('J1',utf8_encode('Supervised'));
	$sheet->setCellValue('K1',utf8_encode('Supervisor'));	
	$sheet->setCellValue('L1',utf8_encode('Question'));
	$sheet->setCellValue('M1',utf8_encode('Answer'));
	$sheet->setCellValue('N1',utf8_encode('Compliance'));
	$sheet->setCellValue('O1',utf8_encode('Comment'));
	$sheet->setCellValue('P1',utf8_encode('Action'));
	$sheet->setCellValue('Q1',utf8_encode('Cause of N/A'));
	$sheet->setCellValue('R1',utf8_encode('Weighting'));
}
$sheet->getStyle('A1:R1')->getAlignment()->setWrapText(true);
$sheet->getStyle('A1:R1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);
$sheet->getColumnDimension('A')->setWidth(10);
$sheet->getColumnDimension('E')->setWidth(40);
$sheet->getColumnDimension('G')->setWidth(10);
$sheet->getColumnDimension('H')->setWidth(13);
$sheet->getColumnDimension('I')->setWidth(13);
$sheet->getColumnDimension('J')->setWidth(15);
$sheet->getColumnDimension('K')->setWidth(15);

//Liste des surveillances
$resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$_SESSION['Id_Personne']);
$nbAccess=mysqli_num_rows($resAcc);

$resAccSuperAdmin=mysqli_query($bdd,"SELECT Id FROM soda_superadministrateur WHERE Id_Personne=".$IdPersonneConnectee);
$nbSuperAdmin=mysqli_num_rows($resAccSuperAdmin);

$reqSurveillant = "SELECT Id FROM soda_surveillant WHERE Id_Personne=".$_SESSION['Id_Personne']." ";	
$nbSurveillant=mysqli_num_rows($resAcc=mysqli_query($bdd,$reqSurveillant));

$req="SELECT Id_Personne 
	FROM new_competences_relation 
	WHERE Evaluation='X'
	AND Suppr=0
	AND Date_Debut<='".date('Y-m-d')."'
	AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
	AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
	AND Id_Personne=".$_SESSION['Id_Personne']." ";
$resultSurQualifie=mysqli_query($bdd,$req);
$nbSurveillantQualifie=mysqli_num_rows($resultSurQualifie);

$req="SELECT Id_Personne 
	FROM new_competences_relation 
	WHERE Evaluation='L'
	AND Suppr=0
	AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
	AND Id_Personne=".$_SESSION['Id_Personne']." ";
$resultSurQualifie=mysqli_query($bdd,$req);
$nbSurveillantECQualif=mysqli_num_rows($resultSurQualifie);

$req2="	SELECT soda_surveillance.Id,
		soda_surveillance_question.Id_Question,
		(SELECT Question FROM soda_question WHERE Id=Id_Question) AS Question,
		(SELECT Reponse FROM soda_question WHERE Id=Id_Question) AS Reponse,
		(SELECT Question_EN FROM soda_question WHERE Id=Id_Question) AS Question_EN,
		(SELECT Reponse_EN FROM soda_question WHERE Id=Id_Question) AS Reponse_EN,
		soda_surveillance_question.QuestionAdditionnelle,
		soda_surveillance_question.ReponseAdditionnelle,
		soda_surveillance_question.Ponderation,
		soda_surveillance_question.Etat AS EtatQ,
		soda_surveillance_question.Commentaire,
		soda_surveillance_question.Action,
		soda_surveillance_question.TypeNA,
		(SELECT (SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) FROM soda_questionnaire WHERE Id=soda_surveillance.Id_Questionnaire) AS Theme,
		(SELECT Libelle FROM soda_questionnaire WHERE Id=soda_surveillance.Id_Questionnaire) AS Questionnaire,
		soda_surveillance.Id_Prestation,
		IF(soda_surveillance.Id_Prestation>0,(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=soda_surveillance.Id_Plateforme) FROM new_competences_prestation WHERE Id=soda_surveillance.Id_Prestation),(SELECT Libelle FROM new_competences_plateforme WHERE Id=soda_surveillance.Id_Plateforme)) AS Plateforme,
		(SELECT Libelle FROM new_competences_prestation WHERE Id=soda_surveillance.Id_Prestation) AS Prestation,EnFormation,AttestationSurveillance,
		YEAR(soda_surveillance.DateSurveillance) AS Annee,
		DATE_FORMAT(soda_surveillance.DateSurveillance,'%u') AS Semaine,
		soda_surveillance.DateSurveillance,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveille) AS Surveille,Id_Surveille,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=soda_surveillance.Id_Surveillant) AS Surveillant,Id_Surveillant,
		(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=soda_surveillance.Id_Questionnaire) AS SeuilReussite,
		soda_surveillance.Etat,
		soda_surveillance.NumActionTracker,
		ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100) AS Resultat ";
$req = "FROM soda_surveillance_question
		LEFT JOIN soda_surveillance 
		ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
		WHERE soda_surveillance.Suppr=0
		AND soda_surveillance.AutoSurveillance=0 
		AND soda_surveillance.Etat IN ('Clôturé','En cours - papier','Brouillon') ";
if($_SESSION['FiltreSODAConsult_NumSurveillance'] <> "")
{
	$req .= "AND soda_surveillance.Id =".$_SESSION['FiltreSODAConsult_NumSurveillance']." ";
}
else
{
	if ($_SESSION['FiltreSODAConsult_Plateforme'] <> 0){$req .= "AND ((SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=soda_surveillance.Id_Prestation)=".$_SESSION['FiltreSODAConsult_Plateforme']." OR soda_surveillance.Id_Plateforme=".$_SESSION['FiltreSODAConsult_Plateforme'].") ";}
	if($_SESSION['FiltreSODAConsult_PrestationA']=="1" && $_SESSION['FiltreSODAConsult_PrestationI']=="0"){$req.=" AND ((SELECT Active FROM new_competences_prestation WHERE Id=soda_surveillance.Id_Prestation)=0 OR soda_surveillance.Id_Plateforme>0) ";}
	elseif($_SESSION['FiltreSODAConsult_PrestationA']=="0" && $_SESSION['FiltreSODAConsult_PrestationI']=="1"){$req.=" AND ((SELECT Active FROM new_competences_prestation WHERE Id=soda_surveillance.Id_Prestation)=-1 OR soda_surveillance.Id_Plateforme>0) ";}
	elseif($_SESSION['FiltreSODAConsult_PrestationA']=="0" && $_SESSION['FiltreSODAConsult_PrestationI']=="0"){$req.=" AND ((SELECT Active FROM new_competences_prestation WHERE Id=soda_surveillance.Id_Prestation)=0 OR soda_surveillance.Id_Plateforme>0) ";}
	if ($_SESSION['FiltreSODAConsult_Prestation'] <> 0){$req .= "AND soda_surveillance.Id_Prestation =".$_SESSION['FiltreSODAConsult_Prestation']." ";}
	if ($_SESSION['FiltreSODAConsult_DateSurveillance'] <> ""){$req .= "AND soda_surveillance.DateSurveillance='".TrsfDate_($_SESSION['FiltreSODAConsult_DateSurveillance'])."' ";}
	if($_SESSION['FiltreSODAConsult_Annee'] <> ""){$req .= "AND YEAR(soda_surveillance.DateSurveillance) ='".$_SESSION['FiltreSODAConsult_Annee']."' ";}
	if($_SESSION['FiltreSODAConsult_Etat'] <> ""){
		if($_SESSION['FiltreSODAConsult_Etat'] == "A VALIDER"){
			$req .= "AND soda_surveillance.Etat ='Clôturé' AND EnFormation=1 AND AttestationSurveillance=0 ";
		}
		else{
			$req .= "AND soda_surveillance.Etat ='".$_SESSION['FiltreSODAConsult_Etat']."' ";
		}
		
	}
	if ($_SESSION['FiltreSODAConsult_Theme'] <> 0)
	{
		$req .= "AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=soda_surveillance.Id_Questionnaire) =".$_SESSION['FiltreSODAConsult_Theme']." ";
		if($_SESSION['FiltreSODAConsult_Questionnaire'] <> 0){$req .= "AND soda_surveillance.Id_Questionnaire =".$_SESSION['FiltreSODAConsult_Questionnaire']." ";}
	}
	if ($_SESSION['FiltreSODAConsult_Surveille'] <> 0){$req .= "AND soda_surveillance.Id_Surveille =".$_SESSION['FiltreSODAConsult_Surveille']." ";}
	if ($_SESSION['FiltreSODAConsult_Surveillant'] <> 0){$req .= "AND soda_surveillance.Id_Surveillant =".$_SESSION['FiltreSODAConsult_Surveillant']." ";}
	if($_SESSION['FiltreSODAConsult_ATNonRenseigne']<>""){$req .= "AND soda_surveillance.NumActionTracker ='' ";}
	if ($_SESSION['FiltreSODAConsult_NumAT'] <> ""){
		$req .= "AND soda_surveillance.NumActionTracker ='".$_SESSION['FiltreSODAConsult_NumAT']."' ";
	}
	elseif($_SESSION['FiltreSODAConsult_InfObjectif']<>""){
		$req .= "AND ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)<(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AND Etat='Clôturé' ";
	}
	if($_SESSION['FiltreSODAConsult_NCActionAT']<>""){
		$req .= "AND (SELECT COUNT(soda_surveillance_question.Id) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='NC' AND Action='Action immédiate + Action Tracker')>0 ";
	}
	if($_SESSION['FiltreSODAConsult_MonPerimetre']<>""){
		if($nbAccess>0 || $nbSuperAdmin>0 || $nbSurveillant>0 || $nbSurveillantQualifie>0 || $nbSurveillantECQualif>0 || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))){
			
		}
		else{
			$req.="AND ((SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=soda_surveillance.Id_Prestation) IN (
				SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
				AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteCoordinateurSecurite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")
			)
			OR 
			soda_surveillance.Id_Prestation IN (
				SELECT Id_Prestation
				FROM new_competences_personne_poste_prestation 
				WHERE Id_Personne=".$_SESSION['Id_Personne']."
				AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteResponsableProjet.",".$IdPosteCoordinateurProjet.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
				)
			)
			";
		}
	}
}
$reqOrder="";
if($_SESSION['TriConsultSODA_General']<>""){
	$reqOrder="ORDER BY ".substr($_SESSION['TriConsultSODA_General'],0,-1);
}

$resultSurveillance=mysqli_query($bdd,$req2.$req.$reqOrder);
$nbSurveillance=mysqli_num_rows($resultSurveillance);

if($nbSurveillance > 0){
	$Couleur="EEEEEE";
	$ligne = 2;
	while($rowSurveillance=mysqli_fetch_array($resultSurveillance)){
		if($Couleur=="EEEEEE"){$Couleur="FFFFFF";}
		else{$Couleur="EEEEEE";}

		$presta=substr($rowSurveillance['Prestation'],0,strpos($rowSurveillance['Prestation']," "));
		if($presta==""){$presta=$rowSurveillance['Prestation'];}
		
		$typeNA="";
		if($rowSurveillance['TypeNA']=="1"){
			if($_SESSION['Langue']=="FR"){$typeNA= "Liée aux circonstances de la surveillance (temporaire)";}
			else{$typeNA= "Related to circumstances of supervision (temporary)";}
		}
		elseif($rowSurveillance['TypeNA']=="2"){
			if($_SESSION['Langue']=="FR"){$typeNA= "Liée à la prestation (permanente)";}
			else{$typeNA= "Linked to the activity (permanent)";}
		}
		
		$question="";
		$reponse="";
		if($rowSurveillance['Id_Question']>0){
			if($_SESSION['Langue']=="FR"){
				$question=stripslashes($rowSurveillance['Question']);
				$reponse=stripslashes($rowSurveillance['Reponse']);
			}
			else{
				$question=stripslashes($rowSurveillance['Question_EN']);
				$reponse=stripslashes($rowSurveillance['Reponse_EN']);
			}
		}
		else{
			$question=stripslashes($rowSurveillance['QuestionAdditionnelle']);
			$reponse=stripslashes($rowSurveillance['ReponseAdditionnelle']);
		}
		
		$etat=$rowSurveillance['Etat'];
		if($_SESSION["Langue"]=="EN"){
			if($rowSurveillance['Etat']=="Clôturé"){
				$etat="Closed";
				if($rowSurveillance['EnFormation']==1 && $rowSurveillance['AttestationSurveillance']==0){
					$etat="Validate";
				}
			}
			if($rowSurveillance['Etat']=="En cours - papier"){$etat="In progress - paper";}
			if($rowSurveillance['Etat']=="Brouillon"){$etat="Draft";}
		}
		else{
			if($rowSurveillance['Etat']=="Clôturé"){
				if($rowSurveillance['EnFormation']==1 && $rowSurveillance['AttestationSurveillance']==0){
					$etat="A Valider";
				}
			}
		}
			
		$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode($rowSurveillance['Id']));
		$sheet->setCellValueByColumnAndRow(1,$ligne,utf8_encode($rowSurveillance['Plateforme']));
		$sheet->setCellValueByColumnAndRow(2,$ligne,utf8_encode($presta));
		$sheet->setCellValueByColumnAndRow(3,$ligne,utf8_encode($rowSurveillance['Theme']));
		$sheet->setCellValueByColumnAndRow(4,$ligne,utf8_encode($rowSurveillance['Questionnaire']));
		$sheet->setCellValueByColumnAndRow(5,$ligne,utf8_encode($etat));
		$sheet->setCellValueByColumnAndRow(6,$ligne,utf8_encode($rowSurveillance['Resultat']."%"));
		$sheet->setCellValueByColumnAndRow(7,$ligne,utf8_encode($rowSurveillance['NumActionTracker']));
		$sheet->setCellValueByColumnAndRow(8,$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($rowSurveillance['DateSurveillance'])));
		$sheet->setCellValueByColumnAndRow(9,$ligne,utf8_encode($rowSurveillance['Surveille']));
		$sheet->setCellValueByColumnAndRow(10,$ligne,utf8_encode($rowSurveillance['Surveillant']));
		$sheet->setCellValueByColumnAndRow(11,$ligne,utf8_encode($question));
		$sheet->setCellValueByColumnAndRow(12,$ligne,utf8_encode($reponse));
		$sheet->setCellValueByColumnAndRow(13,$ligne,utf8_encode($rowSurveillance['EtatQ']));
		$sheet->setCellValueByColumnAndRow(14,$ligne,utf8_encode(stripslashes($rowSurveillance['Commentaire'])));
		$sheet->setCellValueByColumnAndRow(15,$ligne,utf8_encode(stripslashes($rowSurveillance['Action'])));
		$sheet->setCellValueByColumnAndRow(16,$ligne,utf8_encode(stripslashes($typeNA)));
		$sheet->setCellValueByColumnAndRow(17,$ligne,utf8_encode($rowSurveillance['Ponderation']));
		$sheet->getStyle('A'.$ligne.':R'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$Couleur))));
		
		$ligne++;
	}
}
	
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export_Surveillance.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Export_Surveillance.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
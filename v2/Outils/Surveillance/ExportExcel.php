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
$sheet->setCellValue('A1',utf8_encode('N° surveillance Monitoring number'));
$sheet->setCellValue('B1',utf8_encode("Unité d'exploitation Operating unit"));
$sheet->setCellValue('C1',utf8_encode('Prestation Activity'));
$sheet->setCellValue('D1',utf8_encode('Date Surveillance'));
$sheet->setCellValue('E1',utf8_encode('Thème Theme'));
$sheet->setCellValue('F1',utf8_encode('Questionnaire Questionnaire'));
$sheet->setCellValue('G1',utf8_encode('Surveillé Supervised'));
$sheet->setCellValue('H1',utf8_encode('Surveillant Supervisor'));
$sheet->setCellValue('I1',utf8_encode('Etat Status'));
$sheet->setCellValue('J1',utf8_encode('Note Score'));
$sheet->getStyle('A1:J1')->getAlignment()->setWrapText(true);
$sheet->getStyle('A1:J1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);

//Liste des surveillances
$req2 = "SELECT new_surveillances_surveillance.ID, ";
$req2 .= "(SELECT new_surveillances_theme.Nom FROM new_surveillances_theme WHERE new_surveillances_theme.ID = new_surveillances_questionnaire.ID_Theme) AS Theme, ";
$req2 .= "new_surveillances_questionnaire.Nom AS Questionnaire, ";
$req2 .= "new_competences_prestation.Id_Plateforme AS Id_Plateforme, ";
$req2 .= "(SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.ID = new_competences_prestation.Id_Plateforme) AS Plateforme, ";
$req2 .= "new_competences_prestation.Libelle AS Prestation, ";
$req2 .= "(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.ID = new_surveillances_surveillance.ID_Surveille) AS Surveille, ";
$req2 .= "(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.ID = new_surveillances_surveillance.ID_Surveillant) AS Surveillant, ";
$req2 .= "new_surveillances_surveillance.DatePlanif AS DatePlanif, ";
$req2 .= "new_surveillances_surveillance.DateReplanif AS DateReplanif, ";
$req2 .= "IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS DateSurveillance, ";
$req2 .= "IF(new_surveillances_surveillance.Etat='Clôturé' OR new_surveillances_surveillance.Etat='Réalisé','Clôturé','Planifié') AS Etat ";

$req = "FROM ((new_surveillances_surveillance ";
$req .= "LEFT JOIN new_competences_prestation ";
$req .= "ON new_surveillances_surveillance.ID_Prestation = new_competences_prestation.Id) ";
$req .= "LEFT JOIN new_surveillances_questionnaire ";
$req .= "ON new_surveillances_surveillance.ID_Questionnaire = new_surveillances_questionnaire.Id) ";
if($_GET['NumSurveillance'] <> ""){
	$req .= "WHERE new_surveillances_surveillance.ID =".$_GET['NumSurveillance']." ";
	if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
		
	}
	else{
		$req.="AND (new_competences_prestation.Id_Plateforme IN (
			SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
			AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteResponsableHSE.",".$IdPosteCoordinateurSecurite.")
		)
		OR 
		new_surveillances_surveillance.ID_Prestation IN (
			SELECT Id_Prestation 
			FROM new_competences_personne_poste_prestation 
			WHERE Id_Personne=".$_SESSION['Id_Personne']."
			AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
			)
		) ";
	}
}
elseif ($_GET['Id_Plateforme'] <> 0 or $_GET['Id_Prestation'] <> 0 or $_GET['Theme'] <> 0 or $_GET['Id_Surveille'] <> 0 or $_GET['Id_Surveillant'] <> 0 or $_GET['DateSurveillance'] <> "" or $_GET['Etat'] <> "tous"){
		$req .= "WHERE ";
		if ($_GET['Id_Plateforme'] <> 0){
			$req .= "new_competences_prestation.Id_Plateforme =".$_GET['Id_Plateforme']." AND ";
		}
		if ($_GET['Id_Prestation'] <> 0){
			$req .= "new_surveillances_surveillance.ID_Prestation =".$_GET['Id_Prestation']." AND ";
		}
		if ($_GET['DateSurveillance'] <> ""){
			$req .= "IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) ='".TrsfDate_($_GET['DateSurveillance'])."' AND ";
		}
		if($_SESSION['FiltreSurveillance_Annee'] <> ""){
			$req .= "IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', YEAR(new_surveillances_surveillance.DateReplanif), YEAR(new_surveillances_surveillance.DatePlanif)) ='".$_SESSION['FiltreSurveillance_Annee']."' AND ";
		}
		if ($_GET['Theme'] <> 0){
			$req .= "new_surveillances_questionnaire.ID_Theme =".$_GET['Theme']." AND ";
			if ($_GET['TypeTheme'] == "Generique"){
				$req .= "new_surveillances_questionnaire.ID_Plateforme =0 AND ";
			}
			elseif ($_GET['TypeTheme'] == "Specifique"){
				$req .= "new_surveillances_questionnaire.ID_Plateforme <>0 AND ";
				if ($_GET['Id_PlateformeTheme'] <>0){
					$req .= "new_surveillances_questionnaire.ID_Plateforme =".$_GET['Id_PlateformeTheme']." AND ";
				}
				if ($_GET['Id_PlateformeQuestionnaire'] <> 0){
					$req .= "new_surveillances_questionnaire.ID =".$_GET['Id_PlateformeQuestionnaire']." AND ";
				}
			}
		}
		if ($_GET['Id_Surveille'] <> 0){
			$req .= "new_surveillances_surveillance.ID_Surveille =".$_GET['Id_Surveille']." AND ";
		}
		if ($_GET['Id_Surveillant'] <> 0){
			$req .= "new_surveillances_surveillance.ID_Surveillant =".$_GET['Id_Surveillant']." AND ";
		}
		if($_GET['Etat'] <> "tous" && $_GET['Etat'] <> ""){
			$req .= "IF(new_surveillances_surveillance.Etat='Clôturé' OR new_surveillances_surveillance.Etat='Réalisé','Clôturé','Planifié') ='".$_GET['Etat']."' AND ";
		}
		$req = substr($req,0,-4);
	if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
		
	}
	else{
		$req.="AND (new_competences_prestation.Id_Plateforme IN (
			SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
			AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteResponsableHSE.",".$IdPosteCoordinateurSecurite.")
		)
		OR 
		new_surveillances_surveillance.ID_Prestation IN (
			SELECT Id_Prestation 
			FROM new_competences_personne_poste_prestation 
			WHERE Id_Personne=".$_SESSION['Id_Personne']."
			AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
			)
		) ";
	}
}
else{
	if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
		
	}
	else{
		$req.="WHERE (new_competences_prestation.Id_Plateforme IN (
			SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
			AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteResponsableHSE.",".$IdPosteCoordinateurSecurite.")
		)
		OR 
		new_surveillances_surveillance.ID_Prestation IN (
			SELECT Id_Prestation 
			FROM new_competences_personne_poste_prestation 
			WHERE Id_Personne=".$_SESSION['Id_Personne']."
			AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
			)
		) ";
	}
}
$reqOrder = "ORDER BY DateSurveillance ASC ";

$resultSurveillance=mysqli_query($bdd,$req2.$req.$reqOrder);
$nbSurveillance=mysqli_num_rows($resultSurveillance);

if($nbSurveillance > 0){
	$Couleur="EEEEEE";
	$ligne = 2;
	while($rowSurveillance=mysqli_fetch_array($resultSurveillance)){
		if($Couleur=="EEEEEE"){$Couleur="FFFFFF";}
		else{$Couleur="EEEEEE";}
		
		$reqQuestionC = "SELECT ID FROM new_surveillances_surveillance_question WHERE ID_Surveillance=".$rowSurveillance['ID']." ";
		$reqQuestionC .= "AND Etat='C'";
		$resultC=mysqli_query($bdd,$reqQuestionC);
		$nbC=mysqli_num_rows($resultC);
		
		$reqQuestionTot = "SELECT ID FROM new_surveillances_surveillance_question WHERE ID_Surveillance=".$rowSurveillance['ID']." ";
		$reqQuestionTot .= "AND (Etat='C' OR Etat='NC')";
		$resultTot=mysqli_query($bdd,$reqQuestionTot);
		$nbTot=mysqli_num_rows($resultTot);
		
		$presta=substr($rowSurveillance['Prestation'],0,strpos($rowSurveillance['Prestation']," "));
		
		$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode($rowSurveillance['ID']));
		$sheet->setCellValueByColumnAndRow(1,$ligne,utf8_encode($rowSurveillance['Plateforme']));
		$sheet->setCellValueByColumnAndRow(2,$ligne,utf8_encode($presta));
		$sheet->setCellValueByColumnAndRow(3,$ligne,utf8_encode($rowSurveillance['DateSurveillance']));
		$sheet->setCellValueByColumnAndRow(4,$ligne,utf8_encode($rowSurveillance['Theme']));
		$sheet->setCellValueByColumnAndRow(5,$ligne,utf8_encode($rowSurveillance['Questionnaire']));
		$sheet->setCellValueByColumnAndRow(6,$ligne,utf8_encode($rowSurveillance['Surveille']));
		$sheet->setCellValueByColumnAndRow(7,$ligne,utf8_encode($rowSurveillance['Surveillant']));
		$sheet->setCellValueByColumnAndRow(8,$ligne,utf8_encode($rowSurveillance['Etat']));
		$note = 0;
		if ($nbTot == 0){
			$note = 100;
		}
		else{
			$note = round(($nbC / $nbTot)*100,0);
		}
		if ($rowSurveillance['Etat'] == "Planifié" || $rowSurveillance['Etat'] == "Replanifié"){
			$note = "";
		}
		$sheet->setCellValueByColumnAndRow(9,$ligne,utf8_encode($note."%"));
		$sheet->getStyle('A'.$ligne.':J'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$Couleur))));
		
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
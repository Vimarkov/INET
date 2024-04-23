<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

if($_SESSION['Langue']=="FR"){
	$sheet->setCellValue('A1',utf8_encode('Thème'));
	$sheet->setCellValue('B1',utf8_encode("Unité d'exploitation du questionnaire"));
	$sheet->setCellValue('C1',utf8_encode('Questionnaire'));
	$sheet->setCellValue('D1',utf8_encode("Unité d'exploitation surveillance"));
	$sheet->setCellValue('E1',utf8_encode('Prestation surveillance'));
	$sheet->setCellValue('F1',utf8_encode('N° question'));
	$sheet->setCellValue('G1',utf8_encode('Description de la NC / Preuves'));
	$sheet->setCellValue('H1',utf8_encode('Action'));
	$sheet->setCellValue('I1',utf8_encode('Date'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Theme'));
	$sheet->setCellValue('B1',utf8_encode('Questionnaire operating unit'));
	$sheet->setCellValue('C1',utf8_encode('Questionnaire'));
	$sheet->setCellValue('D1',utf8_encode('Monitoring operating unit'));
	$sheet->setCellValue('E1',utf8_encode('Monitoring activity'));
	$sheet->setCellValue('F1',utf8_encode('Question n°'));
	$sheet->setCellValue('G1',utf8_encode('NC Description / Evidences'));
	$sheet->setCellValue('H1',utf8_encode('Action'));
	$sheet->setCellValue('I1',utf8_encode('Date'));
}

$motCle = $_GET['motCle'];
$ThemeSelect = $_GET['theme'];
$QuestionnaireSelect = $_GET['Questionnaire'];
$numQuestion = $_GET['numQuestion'];
$annee = $_GET['annee'];

$req = "
SELECT
	new_surveillances_surveillance_question.ID,
	(SELECT new_surveillances_theme.Nom FROM new_surveillances_theme WHERE new_surveillances_questionnaire.ID_Theme = new_surveillances_theme.ID) AS Theme,
	(SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.ID = new_surveillances_questionnaire.ID_Plateforme) AS PlateformeQ,
	new_surveillances_questionnaire.Nom AS Questionnaire,
	(SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.ID = new_competences_prestation.Id_Plateforme) AS Plateforme,
	new_competences_prestation.Libelle AS Prestation,
	(SELECT new_surveillances_question.Numero FROM new_surveillances_question WHERE new_surveillances_question.ID = new_surveillances_surveillance_question.ID_Question) AS Question,
	new_surveillances_surveillance_question.Commentaire,
	new_surveillances_surveillance_question.Action,
	IF(new_surveillances_surveillance.DateReplanif >'0001-01-01',new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS DateSurveillance,
	new_surveillances_surveillance_question.Cloturee
FROM
	(
		(
			(
			new_surveillances_surveillance
			LEFT JOIN new_competences_prestation
				ON new_surveillances_surveillance.ID_Prestation = new_competences_prestation.Id
			)
			LEFT JOIN new_surveillances_questionnaire
				ON new_surveillances_surveillance.ID_Questionnaire = new_surveillances_questionnaire.Id
		)
		LEFT JOIN new_surveillances_surveillance_question
		ON new_surveillances_surveillance.ID = new_surveillances_surveillance_question.ID_Surveillance
	)
WHERE
	new_surveillances_surveillance_question.Etat='NC'
	AND ";
if($motCle <> ""){$req .= "(new_surveillances_surveillance_question.Commentaire LIKE '%".$motCle."%' OR new_surveillances_surveillance_question.Action LIKE '%".$motCle."%') AND ";}
if($numQuestion <> ""){$req .= "(SELECT new_surveillances_question.Numero FROM new_surveillances_question WHERE new_surveillances_question.ID = new_surveillances_surveillance_question.ID_Question) ='".$numQuestion."' AND ";}
if($ThemeSelect <> 0)
{
$req .= "new_surveillances_questionnaire.ID_Theme =".$ThemeSelect." AND ";
if($QuestionnaireSelect <> 0){$req .= "new_surveillances_questionnaire.ID =".$QuestionnaireSelect." AND ";}
}
if($annee<>""){$req .= "IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', YEAR(new_surveillances_surveillance.DateReplanif), YEAR(new_surveillances_surveillance.DatePlanif)) ='".$annee."' AND ";}
$req = substr($req,0,-4);
$req .= "
ORDER BY
	Theme,
	PlateformeQ,
	Questionnaire,
	Plateforme,
	Question,
	DateSurveillance DESC; ";
$resultQuestion=mysqli_query($bdd,$req);
$nbQuestion=mysqli_num_rows($resultQuestion);

if($nbQuestion > 0)
{
	$ligne=2;
	while($row=mysqli_fetch_array($resultQuestion))
	{
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row['Theme'])));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['PlateformeQ'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['Questionnaire'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['Plateforme'])));
		$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($row['Prestation'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row['Question'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($row['Commentaire'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($row['Action'])));
		$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($row['DateSurveillance'])));
		$ligne++;
	}
}
else{echo "Aucune personne ne correspond à ces critères.";}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="RechercheNC.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/RechercheNC.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
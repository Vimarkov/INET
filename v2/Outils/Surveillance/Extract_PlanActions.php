<?php
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('D-0601-GRP.xlsx');

$req="
	SELECT
		new_surveillances_surveillance_question.ID,
		DatePlanif,
		DateReplanif,
		new_surveillances_surveillance_question.Cloturee,
		(
		SELECT
			CONCAT(Nom, ' ', Prenom)
		FROM
			new_rh_etatcivil
		WHERE
			new_rh_etatcivil.Id = new_surveillances_surveillance.ID_Surveillant
		) AS Surveillant,
		new_competences_plateforme.Libelle AS Plateforme
	FROM
		new_surveillances_surveillance_question
	LEFT JOIN new_surveillances_surveillance ON new_surveillances_surveillance_question.ID_Surveillance = new_surveillances_surveillance.ID
	LEFT JOIN new_competences_prestation ON new_surveillances_surveillance.ID_Prestation=new_competences_prestation.Id
	LEFT JOIN new_competences_plateforme ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
	WHERE
		new_surveillances_surveillance_question.Etat = 'NC'
		AND new_surveillances_surveillance.Etat = 'Réalisé' ";
$result=mysqli_query($bdd,$req);
$row=mysqli_fetch_array($result);

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Plan_Action.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

$chemin = '../../tmp/Plan_Action.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
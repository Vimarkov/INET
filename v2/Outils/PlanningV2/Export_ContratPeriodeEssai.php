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

//Ligne En-tete
if($_SESSION['Langue']=="FR"){
	$sheet->setCellValue('A1',utf8_encode('Personne'));
	$sheet->setCellValue('B1',utf8_encode('Prestation'));
	$sheet->setCellValue('C1',utf8_encode('Métier'));
	$sheet->setCellValue('D1',utf8_encode('Type de document'));
	$sheet->setCellValue('E1',utf8_encode('Type de contrat'));
	$sheet->setCellValue('F1',utf8_encode("Agence d'intérim"));
	$sheet->setCellValue('G1',utf8_encode('Date de début'));
	$sheet->setCellValue('H1',utf8_encode('Date de fin'));
	$sheet->setCellValue('I1',utf8_encode("Date de fin période d'essai"));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Person'));
	$sheet->setCellValue('B1',utf8_encode('Person'));
	$sheet->setCellValue('C1',utf8_encode('Job'));
	$sheet->setCellValue('D1',utf8_encode('Document type'));
	$sheet->setCellValue('E1',utf8_encode('Contract type'));
	$sheet->setCellValue('F1',utf8_encode('Acting Agency'));
	$sheet->setCellValue('G1',utf8_encode('Start date'));
	$sheet->setCellValue('H1',utf8_encode('End date'));
	$sheet->setCellValue('I1',utf8_encode('End date trial period'));
}
$sheet->getStyle('A1:I1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);

$sheet->getColumnDimension('G')->setWidth(40);
$sheet->getColumnDimension('H')->setWidth(30);
$sheet->getColumnDimension('I')->setWidth(30);
$sheet->getColumnDimension('J')->setWidth(30);

if($_SESSION["Langue"]=="FR"){
$requete2="
	SELECT *
	FROM
	(
		SELECT *
		FROM 
			(SELECT Id,Id_Personne,Id_Metier,Id_TypeContrat,Id_TempsTravail,Titre,DateFinPeriodeEssai,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
			(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
			(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
			(SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS EstInterim,
			Coeff,DateDebut,DateFin,TypeDocument,TypeCoeff,SalaireBrut,TauxHoraire,
			(
				SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) AS Prestation
				FROM rh_personne_mouvement
				WHERE Suppr=0
				AND rh_personne_mouvement.Id_Personne=rh_personne_contrat.Id_Personne
				AND EtatValidation=1
				AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
				LIMIT 1
			) AS Prestation,
			(
				SELECT (SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=rh_personne_mouvement.Id_Pole) AS Pole
				FROM rh_personne_mouvement
				WHERE Suppr=0
				AND rh_personne_mouvement.Id_Personne=rh_personne_contrat.Id_Personne
				AND EtatValidation=1
				AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
				LIMIT 1
			) AS Pole,
			(SELECT Libelle FROM rh_agenceinterim WHERE rh_agenceinterim.Id=Id_AgenceInterim) AS AgenceInterim,
			(SELECT Libelle FROM rh_tempstravail WHERE rh_tempstravail.Id=Id_TempsTravail) AS TempsTravail,
			IF(DateSignatureSiege=0,1,
				IF(DateSignatureSalarie=0,2,
					IF(DateSignatureSalarie>'0001-01-01' AND DateRetourSigneAuSiege=0,3,
						IF(DateRetourSigneAuSiege>'0001-01-01',4,
						0
						)
					)
				)
			) AS Etat,(@row_number:=@row_number + 1) AS rnk
			FROM rh_personne_contrat
			WHERE Suppr=0
			AND DateDebut<='".date('Y-m-d')."'
			AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
			AND TypeDocument IN ('Nouveau','Avenant')
			ORDER BY Id_Personne, DateDebut DESC, Id DESC) AS table_contrat 
		GROUP BY Id_Personne
	) AS table_contrat2
	WHERE Personne<>'' 
	AND DateFinPeriodeEssai>='".date('Y-m-d')."'
	AND DATE_SUB(DateFinPeriodeEssai, INTERVAL 3 MONTH)<'".date('Y-m-d')."'
	";
}
else{
$requete2="
	SELECT *
	FROM
	(
		SELECT *
		FROM 
			(SELECT Id,Id_Personne,Id_Metier,Id_TypeContrat,Id_TempsTravail,Titre,DateFinPeriodeEssai,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
			(SELECT LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
			(SELECT LibelleEN FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
			(SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS EstInterim,
			Coeff,DateDebut,DateFin,TypeDocument,TypeCoeff,SalaireBrut,TauxHoraire,
			(SELECT Libelle FROM rh_agenceinterim WHERE rh_agenceinterim.Id=Id_AgenceInterim) AS AgenceInterim,
			(SELECT Libelle FROM rh_tempstravail WHERE rh_tempstravail.Id=Id_TempsTravail) AS TempsTravail,
			(
				SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) AS Prestation
				FROM rh_personne_mouvement
				WHERE Suppr=0
				AND rh_personne_mouvement.Id_Personne=rh_personne_contrat.Id_Personne
				AND EtatValidation=1
				AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
				LIMIT 1
			) AS Prestation,
			(
				SELECT (SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=rh_personne_mouvement.Id_Pole) AS Pole
				FROM rh_personne_mouvement
				WHERE Suppr=0
				AND rh_personne_mouvement.Id_Personne=rh_personne_contrat.Id_Personne
				AND EtatValidation=1
				AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
				LIMIT 1
			) AS Pole,
			IF(DateSignatureSiege=0,1,
				IF(DateSignatureSalarie=0,2,
					IF(DateSignatureSalarie>'0001-01-01' AND DateRetourSigneAuSiege=0,3,
						IF(DateRetourSigneAuSiege>'0001-01-01',4,
						0
						)
					)
				)
			) AS Etat,(@row_number:=@row_number + 1) AS rnk
			FROM rh_personne_contrat
			WHERE Suppr=0
			AND DateDebut<='".date('Y-m-d')."'
			AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
			AND TypeDocument IN ('Nouveau','Avenant')
			ORDER BY Id_Personne, DateDebut DESC) AS table_contrat 
		GROUP BY Id_Personne
	) AS table_contrat2
	WHERE Personne<>'' 
	AND DateFinPeriodeEssai>='".date('Y-m-d')."'
	AND DATE_SUB(DateFinPeriodeEssai, INTERVAL 3 MONTH)<'".date('Y-m-d')."'
	";
}

$requeteOrder="";
if($_SESSION['TriRHContratPeriodeEssai_General']<>""){
$requeteOrder="ORDER BY ".substr($_SESSION['TriRHContratPeriodeEssai_General'],0,-1);
}

$resultRapport=mysqli_query($bdd,$requete2.$requeteOrder);
$nbRapport=mysqli_num_rows($resultRapport);
if($nbRapport>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($resultRapport)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}
					
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Personne']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Prestation']." ".$row['Pole']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Metier']));
		if($row['TypeDocument']=="Nouveau"){
			if($_SESSION["Langue"]=="FR"){
				$sheet->setCellValue('D'.$ligne,utf8_encode("Nouveau"));
			}
			else{
				$sheet->setCellValue('D'.$ligne,utf8_encode("New"));
			}
		}
		elseif($row['TypeDocument']=="Avenant"){
			$sheet->setCellValue('D'.$ligne,utf8_encode("Avenant"));
		}
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['TypeContrat']));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row['AgenceInterim'])));
		if($row['DateDebut']>'0001-01-01'){
			$date = explode("-",$row['DateDebut']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('G'.$ligne,$time);
			$sheet->getStyle('G'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		if($row['DateFin']>'0001-01-01'){
			$date = explode("-",$row['DateFin']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('H'.$ligne,$time);
			$sheet->getStyle('H'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		if($row['DateFinPeriodeEssai']>'0001-01-01'){
			$date = explode("-",$row['DateFinPeriodeEssai']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('I'.$ligne,$time);
			$sheet->getStyle('I'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		
		$sheet->getStyle('A'.$ligne.':I'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export_ContratEnPeriodeEssai.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Export_ContratEnPeriodeEssai.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
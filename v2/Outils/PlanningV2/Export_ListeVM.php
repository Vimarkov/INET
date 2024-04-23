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
	$sheet->setCellValue('B1',utf8_encode('Métier'));
	$sheet->setCellValue('C1',utf8_encode('Type de contrat'));
	$sheet->setCellValue('D1',utf8_encode('Agence d\'intérim'));
	$sheet->setCellValue('E1',utf8_encode('Date de début'));
	$sheet->setCellValue('F1',utf8_encode('Date de fin'));
	$sheet->setCellValue('G1',utf8_encode('Date dernière visite'));
	$sheet->setCellValue('H1',utf8_encode('Type de visite'));
	$sheet->setCellValue('I1',utf8_encode('SMR'));
	$sheet->setCellValue('J1',utf8_encode('Restriction d\'aptitude'));
	$sheet->setCellValue('K1',utf8_encode('Date prochaine visite'));

}
else{
	$sheet->setCellValue('A1',utf8_encode('Person'));
	$sheet->setCellValue('B1',utf8_encode('Job'));
	$sheet->setCellValue('C1',utf8_encode('Contract type'));
	$sheet->setCellValue('D1',utf8_encode('Acting Agency'));
	$sheet->setCellValue('E1',utf8_encode('Start date'));
	$sheet->setCellValue('F1',utf8_encode('End date'));
	$sheet->setCellValue('G1',utf8_encode('Last visit date'));
	$sheet->setCellValue('H1',utf8_encode('Type of visit'));
	$sheet->setCellValue('I1',utf8_encode('SMR'));
	$sheet->setCellValue('J1',utf8_encode('Restriction of aptitude'));
	$sheet->setCellValue('K1',utf8_encode('Next visit date'));
}
$sheet->getStyle('A1:K1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);

if($_SESSION["Langue"]=="FR"){
$requete2="
	SELECT *,
		ADDDATE(DateDerniereVM, INTERVAL (SELECT IF(table_contrat2.SMR=0,Periodicite_VM,Periodicite_VM_AvecSMR) FROM new_competences_metier WHERE new_competences_metier.Id=table_contrat2.Id_Metier) MONTH) AS DateProchaineVM
		FROM
		(
			SELECT *
			FROM 
				(SELECT Id,Id_Personne,Id_Metier,Id_TypeContrat,DateDebut,DateFin,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
				(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
				(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
				(SELECT Libelle FROM rh_agenceinterim WHERE rh_agenceinterim.Id=Id_AgenceInterim) AS AgenceInterim,
				(SELECT Id FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
				ORDER BY DateVisite DESC LIMIT 1) AS Id_Personne_VM,
				(SELECT DateVisite FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
				ORDER BY DateVisite DESC LIMIT 1) AS DateDerniereVM,
				(SELECT (SELECT Libelle FROM rh_typevisitemedicale WHERE rh_typevisitemedicale.Id=rh_personne_visitemedicale.Id_TypeVisite) FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
				ORDER BY DateVisite DESC LIMIT 1) AS TypeVisite,
				(SELECT Id_TypeVisite FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
				ORDER BY DateVisite DESC LIMIT 1) AS Id_TypeVisite,
				(SELECT RestrictionAptitude FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
				ORDER BY DateVisite DESC LIMIT 1) AS Restriction,
				(SELECT (SELECT COUNT(Id) FROM rh_personne_vm_smr WHERE rh_personne_vm_smr.Id_Personne_VM=rh_personne_visitemedicale.Id ) FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
				ORDER BY DateVisite DESC LIMIT 1) AS SMR,(@row_number:=@row_number + 1) AS rnk
				FROM rh_personne_contrat 
				WHERE Suppr=0
				AND DateDebut<='".date('Y-m-d')."'
				AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
				AND TypeDocument IN ('Nouveau','Avenant')
				ORDER BY Id_Personne, DateDebut DESC, Id DESC) AS table_contrat 
			GROUP BY Id_Personne
		) AS table_contrat2
		WHERE Personne<>'' ";
}
else{
$requete2="
	SELECT *,
		ADDDATE(DateDerniereVM, INTERVAL (SELECT IF(table_contrat2.SMR=0,Periodicite_VM,Periodicite_VM_AvecSMR) FROM new_competences_metier WHERE new_competences_metier.Id=table_contrat2.Id_Metier) MONTH) AS DateProchaineVM
		FROM
		(
			SELECT *
			FROM 
				(SELECT Id,Id_Personne,Id_Metier,Id_TypeContrat,DateDebut,DateFin,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
				(SELECT LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
				(SELECT LibelleEN FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
				(SELECT Libelle FROM rh_agenceinterim WHERE rh_agenceinterim.Id=Id_AgenceInterim) AS AgenceInterim,
				(SELECT Id FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
				ORDER BY DateVisite DESC LIMIT 1) AS Id_Personne_VM,
				(SELECT DateVisite FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
				ORDER BY DateVisite DESC LIMIT 1) AS DateDerniereVM,
				(SELECT (SELECT Libelle FROM rh_typevisitemedicale WHERE rh_typevisitemedicale.Id=rh_personne_visitemedicale.Id_TypeVisite) FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
				ORDER BY DateVisite DESC LIMIT 1) AS TypeVisite,
				(SELECT Id_TypeVisite FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
				ORDER BY DateVisite DESC LIMIT 1) AS Id_TypeVisite,
				(SELECT RestrictionAptitude FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
				ORDER BY DateVisite DESC LIMIT 1) AS Restriction,
				(SELECT (SELECT COUNT(Id) FROM rh_personne_vm_smr WHERE rh_personne_vm_smr.Id_Personne_VM=rh_personne_visitemedicale.Id ) FROM rh_personne_visitemedicale WHERE Suppr=0 AND rh_personne_visitemedicale.Id_Personne=rh_personne_contrat.Id_Personne
				ORDER BY DateVisite DESC LIMIT 1) AS SMR,(@row_number:=@row_number + 1) AS rnk
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND DateDebut<='".date('Y-m-d')."'
				AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
				AND TypeDocument IN ('Nouveau','Avenant')
				ORDER BY Id_Personne, DateDebut DESC) AS table_contrat 
			GROUP BY Id_Personne
		) AS table_contrat2
		WHERE Personne<>'' 
		";
}
if($_SESSION['FiltreRHVMEC_Personne']<>""){
	$requete2.=" AND Personne LIKE \"%".$_SESSION['FiltreRHVMEC_Personne']."%\" ";
}

if($_SESSION['FiltreRHVMEC_Metier']<>"0"){
	$requete2.=" AND Id_Metier = ".$_SESSION['FiltreRHVMEC_Metier']." ";
}
if($_SESSION['FiltreRHVMEC_TypeContrat']<>"0"){
	$requete2.=" AND Id_TypeContrat = ".$_SESSION['FiltreRHVMEC_TypeContrat']." ";
}
if($_SESSION['FiltreRHVMEC_TypeVisite']<>"0"){
	$requete2.=" AND Id_TypeVisite = ".$_SESSION['FiltreRHVMEC_TypeVisite']." ";
}

if($_SESSION['FiltreRHVMEC_SMR']=="0"){
	$requete2.=" AND (SMR=0 OR SMR='') ";
}
elseif($_SESSION['FiltreRHVMEC_SMR']=="1"){
	$requete2.=" AND SMR>0 ";
}

if($_SESSION['FiltreRHVMEC_Restricition']=="0"){
	$requete2.=" AND Restriction=0 ";
}
elseif($_SESSION['FiltreRHVMEC_Restricition']=="1"){
	$requete2.=" AND Restriction=1 ";
}

if($_SESSION['FiltreRHVMEC_DateDerniereVM']<>""){
	$requete2.=" AND DateDerniereVM ".$_SESSION['FiltreRHVMEC_SigneDateDerniereVM']." '".TrsfDate_($_SESSION['FiltreRHVMEC_DateDerniereVM'])."' ";
}

if($_SESSION['FiltreRHVMEC_DateProchaineVM']<>""){
	$requete2.=" AND ADDDATE(DateDerniereVM, INTERVAL (SELECT IF(table_contrat2.SMR=0,Periodicite_VM,Periodicite_VM_AvecSMR) FROM new_competences_metier WHERE new_competences_metier.Id=table_contrat2.Id_Metier) MONTH) ".$_SESSION['FiltreRHVMEC_SigneDateProchaineVM']." '".TrsfDate_($_SESSION['FiltreRHVMEC_DateProchaineVM'])."' ";
}

$requeteOrder="";
if($_SESSION['TriRHVMEC_General']<>""){
	$requeteOrder="ORDER BY ".substr($_SESSION['TriRHVMEC_General'],0,-1);
}

$resultRapport=mysqli_query($bdd,$requete2.$requeteOrder);
$nbRapport=mysqli_num_rows($resultRapport);
if($nbRapport>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($resultRapport)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}
					
		$restriction="";
		$smr="";
		if($row['DateDerniereVM']<>''){
			if($row['Restriction']==0){if($_SESSION["Langue"]=="FR"){$restriction= "Non";}else{$restriction= "No";}}
			else{if($_SESSION["Langue"]=="FR"){$restriction= "Oui";}else{$restriction= "Yes";}}
		}
		if($row['DateDerniereVM']<>''){
			if($row['SMR']==0){if($_SESSION["Langue"]=="FR"){$smr= "Non";}else{$smr= "No";}}
			else{if($_SESSION["Langue"]=="FR"){$smr= "Oui";}else{$smr= "Yes";}}
		}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Personne']));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Metier'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['TypeContrat'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['AgenceInterim'])));
		$sheet->setCellValue('E'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateDebut'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateFin'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateDerniereVM'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($row['TypeVisite'])));
		$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($smr)));
		$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($restriction)));
		$sheet->setCellValue('K'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateProchaineVM'])));

		$sheet->getStyle('A'.$ligne.':K'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export_Contrat.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Export_VM.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
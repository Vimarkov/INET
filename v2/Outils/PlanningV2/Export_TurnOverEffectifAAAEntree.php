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
	$sheet->setCellValue('C1',utf8_encode('Date d\'entrée'));
	$sheet->setCellValue('D1',utf8_encode('Type de contrat'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Person'));
	$sheet->setCellValue('B1',utf8_encode('Person'));
	$sheet->setCellValue('C1',utf8_encode('Date of entry'));
	$sheet->setCellValue('D1',utf8_encode('Type of contract'));
}
$sheet->getStyle('A1:D1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);

$mois=$_SESSION['FiltreRHTurnOverAAA_Mois'];
$annee=$_SESSION['FiltreRHTurnOverAAA_Annee'];
$PlateformeSelect=$_SESSION['FiltreRHTurnOverAAA_Plateforme'];

$dateDebut=date('Y-m-d', mktime(0, 0, 0, $mois, 1 ,$annee));
$dateFin=date('Y-m-d', mktime(0, 0, 0, $mois+1, 0 ,$annee));

$dateDebutM_1=date('Y-m-d', mktime(0, 0, 0, $mois-1, 1 ,$annee));
$dateFinM_1=date('Y-m-d', mktime(0, 0, 0, $mois, 0 ,$annee));

$req="SELECT DISTINCT Id_Personne,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
	FROM rh_personne_contrat
	WHERE Suppr=0
	AND Id_TypeContrat IN (SELECT Id FROM rh_typecontrat WHERE Suppr=0 AND EstSalarie=1)
	AND DateDebut<='".$dateFin."'
	AND (
		(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation)=".$PlateformeSelect."
		OR 
		(
			rh_personne_contrat.Id_Prestation=0
			AND (SELECT COUNT(rh_personne_mouvement.Id)
				FROM rh_personne_mouvement
				WHERE rh_personne_mouvement.Suppr=0
				AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation)=".$PlateformeSelect."
				AND rh_personne_mouvement.Id_Personne=rh_personne_contrat.Id_Personne
				AND rh_personne_mouvement.EtatValidation=1
				AND rh_personne_mouvement.DateDebut<='".$dateFin."'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
			)>0
		)
	)
	AND (DateFin>='".$dateDebut."' OR DateFin<='0001-01-01' )
	AND TypeDocument IN ('Nouveau','Avenant')
	AND Id_Personne NOT IN (
		SELECT DISTINCT Id_Personne
		FROM rh_personne_contrat
		WHERE Suppr=0
		AND Id_TypeContrat IN (SELECT Id FROM rh_typecontrat WHERE Suppr=0 AND EstSalarie=1)
		AND (
			(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation)=".$PlateformeSelect."
			OR 
			(
				rh_personne_contrat.Id_Prestation=0
			)
		)
		AND DateDebut<='".$dateFinM_1."'
		AND (DateFin>='".$dateDebutM_1."' OR DateFin<='0001-01-01' )
		AND TypeDocument IN ('Nouveau','Avenant')
	)
	ORDER BY Personne ASC";
$resultEntree=mysqli_query($bdd,$req);
$nbEntree=mysqli_num_rows($resultEntree);
if($nbEntree>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($rowpersonne=mysqli_fetch_array($resultEntree)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}
		
		//Prestation et date d'entrée à cette date 
		$req="SELECT Id_Prestation, Id_Pole,
			(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
			(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole
			FROM rh_personne_mouvement
			WHERE Suppr=0
			AND Id_Personne=".$rowpersonne['Id_Personne']." 
			AND EtatValidation=1
			AND rh_personne_mouvement.DateDebut<='".$dateFin."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."') ";
		$result=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($result);
		$Prestation="";
		if($nb>0){
			$rowMouv=mysqli_fetch_array($result);
			$Prestation=substr($rowMouv['Prestation'],0,7);
			if($rowMouv['Id_Pole']>0){
				$Prestation.=" - ".$rowMouv['Pole'];
			}
		}
		
		$DateEntree="0001-01-01";
		$TypeContrat="";
		$req="SELECT DateDebut,
		(SELECT Code FROM rh_typecontrat WHERE Id=Id_TypeContrat) AS TypeContrat
		FROM rh_personne_contrat
		WHERE Suppr=0
		AND Id_TypeContrat IN (SELECT Id FROM rh_typecontrat WHERE Suppr=0 AND EstSalarie=1)
		AND Id_Personne=".$rowpersonne['Id_Personne']."
		AND DateDebut<='".$dateFin."'
		AND (DateFin>='".$dateDebut."' OR DateFin<='0001-01-01' )
		AND TypeDocument IN ('Nouveau','Avenant')
		ORDER BY DateDebut ASC";
		$resultDate=mysqli_query($bdd,$req);
		$nbDate=mysqli_num_rows($resultDate);
		if($nbDate>0){
			$rowDate=mysqli_fetch_array($resultDate);
			$DateEntree=$rowDate['DateDebut'];
			$TypeContrat=$rowDate['TypeContrat'];
		}
		
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($rowpersonne['Personne']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($Prestation));

		if($DateEntree>'0001-01-01'){
			$date = explode("-",$DateEntree);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('C'.$ligne,$time);
			$sheet->getStyle('C'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		
		$sheet->setCellValue('D'.$ligne,utf8_encode($TypeContrat));
		
		$sheet->getStyle('A'.$ligne.':D'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export_TurnOverEffectifAAAEntree.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Export_TurnOverEffectifAAAEntree.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
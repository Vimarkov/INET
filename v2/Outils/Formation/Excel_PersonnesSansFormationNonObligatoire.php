<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("Globales_Fonctions.php");
require_once("../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$vert="6fff55";
$orange="ffe915";
$rouge="ff151c";
$gris="aaaaaa";
$blanc="ffffff";

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
if($LangueAffichage=="FR"){
	$sheet->setTitle(utf8_encode("SansFormation"));
	
	$sheet->setCellValue('A1',utf8_encode("Personne"));
	$sheet->setCellValue('B1',utf8_encode("Prestation"));
}
else{
	$sheet->setTitle(utf8_encode("WithoutTraining"));
	$sheet->setCellValue('A1',utf8_encode("Person"));
	$sheet->setCellValue('B1',utf8_encode("Site"));
}

$sheet->getColumnDimension('A')->setWidth(30);
$sheet->getColumnDimension('B')->setWidth(20);

$sheet->getStyle('A1:B1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:B1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:B1')->getFont()->setBold(true);
$sheet->getStyle('A1:B1')->getFont()->getColor()->setRGB('1f49a6');

$Id_Presta=$_SESSION['FiltrePersSansFormNonO_Prestations'];
$Id_Plateforme=$_SESSION['FiltrePersSansFormNonO_Plateforme'];
$Id_RespProjet=$_SESSION['FiltrePersSansFormNonO_RespProjet'];
$DateDebut=$_SESSION['FiltrePersSansFormNonO_DateDebut'];
$DateFin=$_SESSION['FiltrePersSansFormNonO_DateFin'];


$qualification="";
$req="SELECT DISTINCT Id_Qualification
	FROM form_formation_qualification
	LEFT JOIN form_formation 
	ON form_formation_qualification.Id_Formation=form_formation.Id
	WHERE form_formation.Obligatoire=0
	AND form_formation.Suppr=0 
	AND form_formation_qualification.Suppr=0 ";
$resultFormE=mysqli_query($bdd,$req);
while($rowFormE=mysqli_fetch_array($resultFormE))
{
	if($qualification<>""){$qualification.=",";}
	$qualification.=$rowFormE['Id_Qualification'];
}

$req="SELECT DISTINCT Id_Personne, Id_Prestation, Id_Pole,
(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
new_competences_prestation.Libelle AS Prestation,
(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole
FROM new_competences_personne_prestation 
LEFT JOIN new_competences_prestation
ON new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
WHERE Date_Debut<='".date('Y-m-d')."'
AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01' )
AND (
	Id_Personne NOT IN (
	SELECT Id_Personne
	FROM new_competences_relation 
	WHERE Id_Qualification_Parrainage IN (".$qualification.")
	AND Date_QCM>='".$DateDebut."'
	AND Date_QCM<='".$DateFin."'
	AND Suppr=0) 
	
	AND 
	
	Id_Personne NOT IN
		(SELECT
			form_besoin.Id_Personne
		FROM
			form_besoin
		LEFT JOIN form_formation
		ON form_besoin.Id_Formation=form_formation.Id
		WHERE
			form_besoin.Suppr=0
			AND form_besoin.Valide=1
			AND form_besoin.Traite=4
			AND form_formation.Obligatoire=0
			AND form_besoin.Id IN
			(
			SELECT DISTINCT
				Id_Besoin
			FROM
				form_session_personne,
				form_session_date
			WHERE
				form_session_personne.Id_Session=form_session_date.Id_Session
				AND form_session_personne.Id NOT IN 
					(
					SELECT
						Id_Session_Personne
					FROM
						form_session_personne_qualification
					WHERE
						Suppr=0	
					)
				AND form_session_date.DateSession>='".$DateDebut."'
				AND form_session_date.DateSession<='".$DateFin."'
				AND form_session_personne.Suppr=0
				AND form_session_personne.Validation_Inscription=1
				AND form_session_personne.Presence=1
			)
		) 
	) ";
$req.="AND new_competences_prestation.Id_Plateforme=".$Id_Plateforme." ";
if($Id_Presta<>""){$req.="AND CONCAT(Id_Prestation,'_',Id_Pole) IN (".$Id_Presta.")";}
if($Id_RespProjet<>""){
	$req.="AND CONCAT(Id_Prestation,'_',Id_Pole) 
				IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
					FROM new_competences_personne_poste_prestation
					WHERE Id_Personne IN (".$Id_RespProjet.")
					AND Id_Poste IN (".$IdPosteResponsableProjet.")
				)
				";
}
$req.=" GROUP BY Id_Personne
ORDER BY Personne
";
$result=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($result);
if($nbenreg>0){
	$ligne=1;
	while($row=mysqli_fetch_array($result)){
		$ligne++;
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Personne']));
		$sheet->setCellValue('B'.$ligne,utf8_encode(substr($row['Prestation'],0,7)." ".$row['Pole']));
		
		$sheet->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A'.$ligne.':B'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	}
}
										
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="Extract.xlsx"');}
else{header('Content-Disposition: attachment;filename="Extract.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
$chemin = '../../tmp/Extract.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
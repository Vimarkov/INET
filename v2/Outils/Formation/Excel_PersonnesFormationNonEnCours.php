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
	$sheet->setCellValue('C1',utf8_encode("Métier"));
}
else{
	$sheet->setTitle(utf8_encode("WithoutTraining"));
	$sheet->setCellValue('A1',utf8_encode("Person"));
	$sheet->setCellValue('B1',utf8_encode("Site"));
	$sheet->setCellValue('C1',utf8_encode("Job"));
}

$sheet->getColumnDimension('A')->setWidth(30);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);

$sheet->getStyle('A1:C1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:C1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:C1')->getFont()->setBold(true);
$sheet->getStyle('A1:C1')->getFont()->getColor()->setRGB('1f49a6');

$Id_Plateforme=$_SESSION['FiltrePersonnesFormationNonEnCours_Plateforme'];
$formation=$_SESSION['FiltrePersonnesFormationNonEnCours_Formation'];


$tabQual=explode("_",$formation);
$qualification="";
if($formation<>"0_0" && $formation<>""){
	if($tabQual[1]==0){
		$req="SELECT DISTINCT Id_Qualification
			FROM form_formation_qualification
			WHERE Id_Formation=".$tabQual[0]."
			AND Suppr=0 ";
		$resultFormE=mysqli_query($bdd,$req);
		while($rowFormE=mysqli_fetch_array($resultFormE))
		{
			if($qualification<>""){$qualification.=",";}
			$qualification.=$rowFormE['Id_Qualification'];
		}
	}
	else{
		$req="SELECT DISTINCT Id_Qualification
			FROM form_formation_qualification
			WHERE Id_Formation IN 
			(SELECT Id_Formation 
			FROM form_formationequivalente_formationplateforme 
			WHERE Suppr=0 
			AND Id_FormationEquivalente=".$tabQual[0].") 
			AND Suppr=0 ";
		$resultFormE=mysqli_query($bdd,$req);
		while($rowFormE=mysqli_fetch_array($resultFormE))
		{
			if($qualification<>""){$qualification.=",";}
			$qualification.=$rowFormE['Id_Qualification'];
		}
			
	}
}

$req="SELECT DISTINCT Id_Personne, Id_Prestation, Id_Pole,
(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
(SELECT (SELECT Libelle FROM new_competences_metier WHERE Id=Id_Metier) FROM new_competences_personne_metier WHERE new_competences_personne_metier.Id_Personne=new_competences_personne_prestation.Id_Personne ORDER BY Futur DESC LIMIT 1) AS Metier,
new_competences_prestation.Libelle AS Prestation,
(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole
FROM new_competences_personne_prestation 
LEFT JOIN new_competences_prestation
ON new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
WHERE Date_Debut<='".date('Y-m-d')."'
AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01' )
AND 
";
if($qualification<>""){
$req.=" Id_Personne NOT IN (
SELECT Id_Personne
FROM new_competences_relation 
WHERE Id_Qualification_Parrainage IN (".$qualification.")
AND Evaluation IN ('L','Q','S','T','V','X')
AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01')
AND Suppr=0) ";
}
else{
$req.=" Id_Personne NOT IN
		(SELECT
			form_besoin.Id_Personne
		FROM
			form_besoin
		WHERE
			form_besoin.Suppr=0
			AND form_besoin.Valide=1
			AND form_besoin.Traite=4
			AND form_besoin.Id IN
			(
			SELECT
				Id_Besoin
			FROM
				form_session_personne
			WHERE
				form_session_personne.Id NOT IN 
					(
					SELECT
						Id_Session_Personne
					FROM
						form_session_personne_qualification
					WHERE
						Suppr=0	
					)
				AND Suppr=0
				AND form_session_personne.Validation_Inscription=1
				AND form_session_personne.Presence=1
			)
		) 
		AND Id_Personne NOT IN (
				SELECT Id_Personne
				FROM new_competences_personne_formation
				WHERE Id_Formation IN (
					SELECT DISTINCT Id_FormationCompetence
					FROM form_formation_formationcompetence
					WHERE Id_Formation=".$tabQual[0]."
					AND Suppr=0
				)
			)";
}
$req.="AND new_competences_prestation.Id_Plateforme=".$Id_Plateforme." ";
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
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Metier']));
		
		$sheet->getStyle('A'.$ligne.':C'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':C'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A'.$ligne.':C'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
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
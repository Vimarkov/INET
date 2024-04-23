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
	$sheet->setTitle(utf8_encode("Paramétrage"));
	
	$sheet->setCellValue('A1',utf8_encode("Prestation - Pôle"));
	$sheet->setCellValue('B1',utf8_encode("Métier"));
}
else{
	$sheet->setTitle(utf8_encode("Settings"));
	$sheet->setCellValue('A1',utf8_encode("Activity - Pole"));
	$sheet->setCellValue('B1',utf8_encode("Job"));
}

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(30);

$sheet->getStyle('A1:B1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:B1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:B1')->getFont()->setBold(true);
$sheet->getStyle('A1:B1')->getFont()->getColor()->setRGB('1f49a6');

$Id_Presta=$_SESSION['FiltreExtractParametrage_Prestations'];
$Plateforme=$_SESSION['FiltreExtractParametrage_Plateforme'];
$Id_Formation=$_SESSION['FiltreExtractParametrage_Formation'];
$Id_Type=$_SESSION['FiltreExtractParametrage_Type'];

$rqPrestation="SELECT Id AS Id_Prestation, 
	Id_Plateforme,
	Libelle,
	0 AS Id_Pole,
	'' AS Pole
	FROM new_competences_prestation 
	WHERE Id NOT IN (
		SELECT Id_Prestation
		FROM new_competences_pole
		WHERE Actif=0
	)
	AND new_competences_prestation.Active=0
	AND CONCAT(Id,'_','0') IN (".$Id_Presta.")
	UNION
	
	SELECT Id_Prestation,
	new_competences_prestation.Id_Plateforme,
	new_competences_prestation.Libelle,
	new_competences_pole.Id AS Id_Pole,
	CONCAT(' - ',new_competences_pole.Libelle) AS Pole
	FROM new_competences_pole
	INNER JOIN new_competences_prestation
	ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
	AND new_competences_pole.Actif=0
	AND new_competences_prestation.Active=0
	AND CONCAT(Id_Prestation,'_',new_competences_pole.Id) IN (".$Id_Presta.")
	ORDER BY Libelle, Pole";

$resultPrestation=mysqli_query($bdd,$rqPrestation);
$nbPresta=mysqli_num_rows($resultPrestation);

$ligne=2;
if($nbPresta>0){
	while($rowPrestation=mysqli_fetch_array($resultPrestation))
	{
		$Pole="";
		if($rowPrestation['Pole']<>""){$Pole=" - ".$rowPrestation['Pole'];}
		
		//Métiers de la prestation
		$requeteMetier="SELECT DISTINCT new_competences_personne_metier.Id_Metier,
				(SELECT Libelle FROM new_competences_metier WHERE Id=Id_Metier) AS Metier
				FROM new_competences_personne_prestation,
				new_competences_personne_metier
				WHERE Id_Prestation=".$rowPrestation['Id_Prestation']." AND Id_Pole=".$rowPrestation['Id_Pole']." 
				AND Date_Debut<='".date('Y-m-d')."' 
				AND (Date_Fin<='0001-01-01' OR Date_Fin>='".date('Y-m-d')."')
				AND new_competences_personne_prestation.Id_Personne=new_competences_personne_metier.Id_Personne
				";
		$resultMetier=mysqli_query($bdd,$requeteMetier);
		$nbMetier=mysqli_num_rows($resultMetier);
		if($nbMetier>0){
			while($rowMetier=mysqli_fetch_array($resultMetier))
			{
				$reqParam="SELECT Id FROM form_prestation_metier_formation WHERE Suppr=0 AND Id_Prestation=".$rowPrestation['Id_Prestation']." AND Id_Pole=".$rowPrestation['Id_Pole']." 
				AND Id_Metier=".$rowMetier['Id_Metier']." 
				AND (SELECT Id_TypeFormation FROM form_formation WHERE Id=Id_Formation) IN (".$Id_Type.")
				";
				if($Id_Formation<>"" && $Id_Formation<>"0_0"){
					$tabQual=explode("_",$Id_Formation);
					if($tabQual[1]==0){
						$reqParam.=" AND Id_Formation=".$tabQual[0]."";
					}
					else{
						$reqParam.=" AND Id_Formation IN (SELECT Id_Formation 
							FROM form_formationequivalente_formationplateforme 
							WHERE Suppr=0 
							AND Id_FormationEquivalente=".$tabQual[0].") ";
					}
				}
				$resultParam=mysqli_query($bdd,$reqParam);
				$nbParam=mysqli_num_rows($resultParam);
				
				if($nbParam==0){
					$sheet->setCellValue('A'.$ligne,utf8_encode(substr($rowPrestation['Libelle'],0,7).$Pole));
					$sheet->setCellValue('B'.$ligne,utf8_encode($rowMetier['Metier']));
			
					$sheet->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					$sheet->getStyle('A'.$ligne.':B'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$sheet->getStyle('A'.$ligne.':B'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
					
					$ligne++;
				}
			}
		}
		
		
	}
}												
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="MetiersNonParametrees.xlsx"');}
else{header('Content-Disposition: attachment;filename="MetiersNonParametrees.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
$chemin = '../../tmp/MetiersNonParametrees.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
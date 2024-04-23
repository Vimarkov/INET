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
	$sheet->setCellValue('B1',utf8_encode("Type"));
	$sheet->setCellValue('C1',utf8_encode("Formation / Organisme"));
}
else{
	$sheet->setTitle(utf8_encode("Settings"));
	$sheet->setCellValue('A1',utf8_encode("Activity - Pole"));
	$sheet->setCellValue('B1',utf8_encode("Type"));
	$sheet->setCellValue('C1',utf8_encode("Training / Organization"));
}

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(50);

$sheet->getStyle('A1:C1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:C1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:C1')->getFont()->setBold(true);
$sheet->getStyle('A1:C1')->getFont()->getColor()->setRGB('1f49a6');

$Id_Presta=$_SESSION['FiltreExtractParametrage_Prestations'];
$Plateforme=$_SESSION['FiltreExtractParametrage_Plateforme'];
$Id_Type=$_SESSION['FiltreExtractParametrage_Type'];
$Id_Formation=$_SESSION['FiltreExtractParametrage_Formation'];

//FORMATIONS SMQ + PLATEFORME
$requeteFormation="SELECT Id,
				(SELECT Libelle FROM form_typeformation WHERE Id=Id_TypeFormation) AS TypeFormation,
				(SELECT Libelle
				FROM form_formation_langue_infos
				WHERE Id_Formation=form_formation.Id
				AND Id_Langue=
					(SELECT Id_Langue 
					FROM form_formation_plateforme_parametres 
					WHERE Id_Plateforme=".$Plateforme."
					AND Id_Formation=form_formation.Id
					AND Suppr=0 
					LIMIT 1)
				AND Suppr=0
				LIMIT 1) AS Libelle,
				(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) 
					FROM form_formation_plateforme_parametres 
					WHERE Id_Plateforme=".$Plateforme."
					AND Id_Formation=form_formation.Id
					AND Suppr=0 
					LIMIT 1) AS Organisme
				FROM form_formation WHERE Suppr=0 
				AND (Id_Plateforme=0 OR Id_Plateforme=".$Plateforme.") 
				AND Id_TypeFormation IN (".$Id_Type.") ";
if($Id_Formation<>"" && $Id_Formation<>"0_0"){
	$tabQual=explode("_",$Id_Formation);
	if($tabQual[1]==0){
		$requeteFormation.=" AND form_formation.Id=".$tabQual[0]."";
	}
	else{
		$requeteFormation.=" AND form_formation.Id IN (SELECT Id_Formation 
			FROM form_formationequivalente_formationplateforme 
			WHERE Suppr=0 
			AND Id_FormationEquivalente=".$tabQual[0].") ";
	}
}
$requeteFormation.=" ORDER BY Libelle ";
$resultFormation=mysqli_query($bdd,$requeteFormation);
$nbFormation=mysqli_num_rows($resultFormation);

//PARAMETRE PLATEFORME
$requeteParam="SELECT Id,Id_Formation,Id_Langue,CoutSalarieAAA,CoutInterimaire,Duree,CoutSalarieAAARecyclage,CoutInterimaireRecyclage,DureeRecyclage,NbJour,NbJourRecyclage,Id_Organisme,";
$requeteParam.= "(SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS Organisme ";
$requeteParam.="FROM form_formation_plateforme_parametres WHERE Id_Plateforme=".$Plateforme." ";
$resultParam=mysqli_query($bdd,$requeteParam);
$nbParam=mysqli_num_rows($resultParam);


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
		
		mysqli_data_seek($resultFormation,0);
		if($nbFormation>0){
			while($rowForm=mysqli_fetch_array($resultFormation))
			{
				if($rowForm['Libelle']<>""){
					$reqParam="SELECT Id FROM form_prestation_metier_formation WHERE Suppr=0 AND Id_Prestation=".$rowPrestation['Id_Prestation']." AND Id_Pole=".$rowPrestation['Id_Pole']." AND Id_Formation=".$rowForm['Id']." ";
					$resultParam=mysqli_query($bdd,$reqParam);
					$nbParam=mysqli_num_rows($resultParam);
					
					if($nbParam==0){
						$Organisme="";
						if($rowForm['Organisme']<>""){$Organisme=" - ".$rowForm['Organisme'];}
						
						$sheet->setCellValue('A'.$ligne,utf8_encode(substr($rowPrestation['Libelle'],0,7).$Pole));
						$sheet->setCellValue('B'.$ligne,utf8_encode($rowForm['TypeFormation']));
						$sheet->setCellValue('C'.$ligne,utf8_encode($rowForm['Libelle'].$Organisme));
				
						$sheet->getStyle('A'.$ligne.':C'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						$sheet->getStyle('A'.$ligne.':C'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$sheet->getStyle('A'.$ligne.':C'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
						
						$ligne++;
					}
				}
			}
		}
		
		
	}
}												
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="FormationsNonParametrees.xlsx"');}
else{header('Content-Disposition: attachment;filename="FormationsNonParametrees.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
$chemin = '../../tmp/FormationsNonParametrees.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
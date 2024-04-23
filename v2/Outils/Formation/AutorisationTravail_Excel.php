<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require_once("Globales_Fonctions.php");
require_once("../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle(utf8_encode("Autorisation travail"));

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('logo');
$objDrawing->setDescription('PHPExcel logo');
$objDrawing->setPath('../../Images/Logos/Logo_AAA_FR.png');
$objDrawing->setHeight(48);
$objDrawing->setWidth(76);
$objDrawing->setCoordinates('A1');
$objDrawing->setOffsetX(20);
$objDrawing->setOffsetY(8);
$objDrawing->setWorksheet($sheet);

$sheet->getStyle('A:N')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffffff'))));

$col="A";
for($i=1;$i<=14;$i++){
	$sheet->getColumnDimension($col)->setWidth(6);
	$col++;
}

//Responsable plateforme
$Resp="";
$req="SELECT new_rh_etatcivil.Id, Nom, Prenom
		FROM new_competences_personne_poste_plateforme
		LEFT JOIN new_rh_etatcivil
		ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
		WHERE Id_Poste=9 
		AND Backup=0
		AND Id_Plateforme IN (
		SELECT Id_Plateforme 
		FROM new_competences_personne_plateforme
		WHERE Id_Personne=".$_GET['Id']."
		) ";
$resultResp=mysqli_query($bdd,$req);
$nbResp=mysqli_num_rows($resultResp);

if($nbResp>0){
	$rowResp=mysqli_fetch_array($resultResp);
	if($rowResp['Id']==10749){
		$Resp=substr($rowResp['Prenom'],0,1).". B. ".$rowResp['Nom'];
	}
	else{
		$Resp=substr($rowResp['Prenom'],0,1).". ".$rowResp['Nom'];
	}
}

$req="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$_GET['Id'];
$resultPers=mysqli_query($bdd,$req);
$rowPers=mysqli_fetch_array($resultPers);

if($LangueAffichage=="FR"){
	$sheet->setCellValue('A4',utf8_encode('AUTORISATION DE CONDUITE'));
	$sheet->setCellValue('H2',utf8_encode('Moyens'));
	$sheet->setCellValue('K2',utf8_encode('Catégories'));
	$sheet->setCellValue('M2',utf8_encode('Fin de validité'));
	
	$sheet->setCellValue('A6',utf8_encode('Nom :'));
	$sheet->setCellValue('A7',utf8_encode('Prénom :'));
	$sheet->setCellValue('A9',utf8_encode('Délivrée par : '.$Resp));
}
else{
	$sheet->setCellValue('A4',utf8_encode('AUTHORIZATION OF WORK'));
	$sheet->setCellValue('H2',utf8_encode('Means'));
	$sheet->setCellValue('K2',utf8_encode('Categories'));
	$sheet->setCellValue('M2',utf8_encode('End of validity'));
	
	$sheet->setCellValue('A6',utf8_encode('Last name :'));
	$sheet->setCellValue('A7',utf8_encode('First name :'));
	$sheet->setCellValue('A9',utf8_encode('Delivered by : '.$Resp));
}

$sheet->getRowDimension('1')->setRowHeight(14);

$sheet->setCellValue('B6',utf8_encode($rowPers['Nom']));
$sheet->setCellValue('C7',utf8_encode($rowPers['Prenom']));
$sheet->getStyle('B6')->getFont()->setBold(true);//Texte en gras
$sheet->getStyle('C7')->getFont()->setBold(true);//Texte en gras

$sheet->mergeCells('E6:G7');
$sheet->mergeCells('E9:G10');
$sheet->getStyle('E6:G7')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'eeece0'))));
$sheet->getStyle('E9:G10')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'eeece0'))));

$sheet->getStyle('A4')->getFont()->setBold(true);//Texte en gras
$sheet->getStyle('A4')->getFont()->setSize(14);//Taille du texte
$sheet->getStyle('A9')->getFont()->setSize(10);//Taille du texte
$sheet->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getRowDimension('4')->setRowHeight(14);
$sheet->mergeCells('A4:G4');
$sheet->mergeCells('H2:J2');
$sheet->mergeCells('K2:L2');
$sheet->mergeCells('M2:N2');
$sheet->getStyle('H2:N2')->getFont()->setBold(true);//Texte en gras
$sheet->getStyle('H2:N2')->getFont()->setSize(11);//Taille du texte
$sheet->getStyle('H2:N2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('H2:N2')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'00b050'))));
$sheet->getStyle('H2:N2')->getFont()->getColor()->setRGB('ffffff');

$sheet->mergeCells('B6:D6');
$sheet->mergeCells('C7:D7');
$sheet->getStyle('B6:D6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$sheet->getStyle('C7:D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

//Liste des autorisations de conduite

$reqAT="
SELECT *
FROM
(
SELECT DISTINCT new_competences_relation.Id_Qualification_Parrainage,new_competences_relation.Date_Fin,
(SELECT Libelle FROM new_competences_moyen_categorie 
WHERE new_competences_moyen_categorie.Id=new_competences_qualification_moyen.Id_Moyen_Categorie) AS Categorie,
(SELECT new_competences_moyen_categorie.Id_Moyen
FROM new_competences_moyen_categorie 
WHERE new_competences_moyen_categorie.Id=new_competences_qualification_moyen.Id_Moyen_Categorie) AS Id_Moyen,
(SELECT 
	(SELECT Libelle FROM new_competences_moyen 
	WHERE new_competences_moyen.Id=new_competences_moyen_categorie.Id_Moyen) 
FROM new_competences_moyen_categorie 
WHERE new_competences_moyen_categorie.Id=new_competences_qualification_moyen.Id_Moyen_Categorie) AS Moyen,(@row_number:=@row_number + 1) AS rnk  
FROM new_competences_relation 
LEFT JOIN new_competences_qualification_moyen
ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification_moyen.Id_Qualification 
LEFT JOIN new_competences_qualification
ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
WHERE new_competences_qualification_moyen.Suppr=0 
AND new_competences_relation.Suppr=0 
AND new_competences_qualification_moyen.Suppr=0 
AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
AND Date_Debut>'0001-01-01' 
AND new_competences_relation.Evaluation NOT IN ('B','')
AND new_competences_relation.Id_Personne=".$_GET['Id']." 
AND (
	new_competences_qualification_moyen.Id_Moyen_Categorie NOT IN (1,2)
	OR (
	new_competences_qualification_moyen.Id_Moyen_Categorie IN (1,2)
	AND 
	((SELECT COUNT(Id)
	FROM new_competences_relation
	WHERE Suppr=0
	AND Evaluation NOT IN ('B','')
	AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
	AND Date_Debut>'0001-01-01' 
	AND Id_Personne=".$_GET['Id']."
	AND Id_Qualification_Parrainage=75)>0
	
	AND (SELECT COUNT(Id)
	FROM new_competences_relation
	WHERE Suppr=0
	AND Evaluation NOT IN ('B','')
	AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
	AND Date_Debut>'0001-01-01' 
	AND Id_Personne=".$_GET['Id']."
	AND Id_Qualification_Parrainage=12)>0
	
	AND (SELECT COUNT(Id)
	FROM new_competences_relation
	WHERE Suppr=0
	AND Evaluation NOT IN ('B','')
	AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
	AND Date_Debut>'0001-01-01' 
	AND Id_Personne=".$_GET['Id']."
	AND Id_Qualification_Parrainage=13)>0
	
	AND (SELECT COUNT(Id)
	FROM new_competences_relation
	WHERE Suppr=0
	AND Evaluation NOT IN ('B','')
	AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
	AND Date_Debut>'0001-01-01' 
	AND Id_Personne=".$_GET['Id']."
	AND Id_Qualification_Parrainage=133)>0)
	OR
	(
		((SELECT COUNT(Tab2.Id)
		FROM new_competences_relation AS Tab2
		WHERE Tab2.Suppr=0
		AND Tab2.Evaluation NOT IN ('B','')
		AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
		AND Tab2.Date_Debut>'0001-01-01' 
		AND Tab2.Id_Personne=".$_GET['Id']."
		AND Tab2.Id_Qualification_Parrainage=75)=0
		
		AND 
		(SELECT COUNT(Tab2.Id)
		FROM new_competences_relation AS Tab2
		LEFT JOIN new_competences_qualification AS Tab3
		ON Tab2.Id_Qualification_Parrainage=Tab3.Id
		WHERE Tab2.Suppr=0
		AND Tab2.Evaluation NOT IN ('B','')
		AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
		AND Tab2.Date_Debut>'0001-01-01' 
		AND Tab2.Id_Personne=".$_GET['Id']."
		AND Tab2.Id_Qualification_Parrainage=12)=0
		
		AND 
		(SELECT COUNT(Tab2.Id)
		FROM new_competences_relation AS Tab2
		LEFT JOIN new_competences_qualification AS Tab3
		ON Tab2.Id_Qualification_Parrainage=Tab3.Id
		WHERE Tab2.Suppr=0
		AND Tab2.Evaluation NOT IN ('B','')
		AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
		AND Tab2.Date_Debut>'0001-01-01' 
		AND Tab2.Id_Personne=".$_GET['Id']."
		AND Tab2.Id_Qualification_Parrainage=13)=0
		
		AND 
		(SELECT COUNT(Tab2.Id)
		FROM new_competences_relation AS Tab2
		LEFT JOIN new_competences_qualification AS Tab3
		ON Tab2.Id_Qualification_Parrainage=Tab3.Id
		WHERE Tab2.Suppr=0
		AND Tab2.Evaluation NOT IN ('B','')
		AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
		AND Tab2.Date_Debut>'0001-01-01' 
		AND Tab2.Id_Personne=".$_GET['Id']."
		AND Tab2.Id_Qualification_Parrainage=133)=0)
	)
	OR 

		new_competences_relation.Id_Qualification_Parrainage IN (1606,1607,2130,1683,2490,2145)
	)
)

ORDER BY Moyen, Categorie, Date_Fin DESC
) AS TAB 
GROUP BY Moyen,Categorie
";
$resultAT=mysqli_query($bdd,$reqAT);
$nbAT=mysqli_num_rows($resultAT);

//Mise à jour de la date autorisations de conduite
$reqUpdateAT="UPDATE new_competences_relation 
SET new_competences_relation.DateEditionAutorisationTravail='".date('Y-m-d')."' 
WHERE (Date_Fin>='".date('Y-m-d')."' OR 
	(SELECT Duree_Validite 
	FROM new_competences_qualification
	WHERE new_competences_qualification.Id=new_competences_relation.Id_Qualification_Parrainage)=0)
AND Date_Debut>'0001-01-01' 
AND new_competences_relation.Suppr=0 
AND new_competences_relation.Evaluation NOT IN ('B','')
AND new_competences_relation.DateEditionAutorisationTravail<='0001-01-01'
AND new_competences_relation.Id_Personne=".$_GET['Id']." ";
$resultUpdateAT=mysqli_query($bdd,$reqUpdateAT);

$req="UPDATE new_rh_etatcivil SET DateEditionAutorisationTravail='".date('Y-m-d')."' WHERE Id=".$_GET['Id'];
$resultUpdtPers=mysqli_query($bdd,$req);

$i=3;
if($nbAT>0){
	while($rowAT=mysqli_fetch_array($resultAT)){
		$sheet->setCellValue('H'.$i,utf8_encode($rowAT['Moyen']));
		$sheet->setCellValue('K'.$i,utf8_encode($rowAT['Categorie']));
		$sheet->setCellValue('M'.$i,utf8_encode(AfficheDateJJ_MM_AAAA($rowAT['Date_Fin'])));
		$sheet->getStyle('H'.$i)->getFont()->setSize(7);//Taille du texte
		$sheet->mergeCells('H'.$i.':J'.$i);
		$sheet->mergeCells('K'.$i.':L'.$i);
		$sheet->mergeCells('M'.$i.':N'.$i);
		$i++;
	}
}
if($i<9){
	for($k=$i;$k<=9;$k++){
		$sheet->mergeCells('H'.$k.':J'.$k);
		$sheet->mergeCells('K'.$k.':L'.$k);
		$sheet->mergeCells('M'.$k.':N'.$k);
	}
	$i=10;
}
	
if($LangueAffichage=="FR"){
	$sheet->setCellValue('H'.$i,utf8_encode('Toute personne ne respectant pas les règles de sécurité se verra retirer son autorisation de conduite.'));
}
else{
	$sheet->setCellValue('H'.$i,utf8_encode('Anyone who does not respect the safety rules will be removed from his driving authorization.'));
}

$sheet->mergeCells('H'.$i.':N'.($i+1));
$sheet->getStyle('H'.$i.':N'.($i+1))->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ff0000'))));
$sheet->getStyle('H'.$i.':N'.($i+1))->getFont()->getColor()->setRGB('ffffff');
$sheet->getStyle('H'.$i.':N'.($i+1))->getFont()->setSize(8);//Taille du texte
$sheet->getStyle('H'.$i.':N'.($i+1))->getAlignment()->setWrapText(true);
$sheet->getStyle('H'.$i.':N'.($i+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('H'.$i.':N'.($i+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$sheet->getStyle('H2:N'.($i+1))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));

$sheet->getStyle('G1:G'.($i+1))->getBorders()->applyFromArray(array('right' => array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:A'.($i+1))->getBorders()->applyFromArray(array('left' => array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('N1:N'.($i+1))->getBorders()->applyFromArray(array('right' => array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000'))));

$sheet->getStyle('A1:N1')->getBorders()->applyFromArray(array('top' => array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A'.($i+1).':N'.($i+1))->getBorders()->applyFromArray(array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('H2:N'.($i+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//Correction des hauteurs de lignes
for($u = 1; $u <=11; $u++)
	$sheet->getRowDimension($u)->setRowHeight(14);

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){
	header('Content-Disposition: attachment;filename="AutorisationTravail.xlsx"');
}
else{
	header('Content-Disposition: attachment;filename="WorkAuthorization.xlsx"');
}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/AutorisationTravail.xlsx';

$writer->save($chemin);
readfile($chemin);
?>
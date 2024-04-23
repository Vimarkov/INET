<?php
session_start();
require("Globales_Fonctions.php");
require_once("../Fonctions.php");
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();

$tabIds=explode("_",$_GET['Ids']);

$ResultSession=get_session($_GET['Id_Session']);
$RowSession=mysqli_fetch_array($ResultSession);

$requeteForm="SELECT Id,Recyclage 
FROM form_formation 
WHERE Suppr=0 
AND Id=".$RowSession['ID_FORMATION']."  ";
$resultForm=mysqli_query($bdd,$requeteForm);
$RowForm=mysqli_fetch_array($resultForm);

$requeteParam="SELECT Id,NbJour,NbJourRecyclage,Duree,DureeRecyclage,Id_Langue,
(SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS Organisme,
(SELECT Adresse FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS Adresse,
(SELECT Telephone FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS Telephone   
FROM form_formation_plateforme_parametres 
WHERE Suppr=0 
AND Id_Plateforme=".$RowSession['ID_PLATEFORME']." 
AND Id_Formation=".$RowSession['ID_FORMATION']."  ";
$resultParam=mysqli_query($bdd,$requeteParam);
$RowParam=mysqli_fetch_array($resultParam);

$requeteInfos="SELECT Id,Libelle,LibelleRecyclage 
FROM form_formation_langue_infos WHERE Suppr=0
AND Id_Formation=".$RowSession['ID_FORMATION']."  
AND Id_Langue=".$RowParam['Id_Langue']."  ";
$resultInfos=mysqli_query($bdd,$requeteInfos);
$RowInfo=mysqli_fetch_array($resultInfos);

$Libelle="";
$NbJour="";
$NbHeure="";
if($RowSession['RECYCLAGE']==0){$Libelle=$RowInfo['Libelle'];$NbJour=$RowParam['NbJour'];$NbHeure=$RowParam['Duree'];}
else{
	if($RowForm['Recyclage']==0){$Libelle=$RowInfo['Libelle'];$NbJour=$RowParam['NbJour'];$NbHeure=$RowParam['Duree'];}
	else{$Libelle=$RowInfo['LibelleRecyclage'];$NbJour=$RowParam['NbJourRecyclage'];$NbHeure=$RowParam['DureeRecyclage'];}
}

$ResultSessionPersonnes=mysqli_query($bdd,getchaineSQL_sessionPersonne($_GET['Id_Session']));

$excel = $workbook->load('Template_DemandePriseEnCharge.xlsx');
$sheet = $excel->getSheetByName('demande de formation');

$sheet->setCellValue('G6',utf8_encode(": ".date('d/m/y')));
$sheet->setCellValue('I6',utf8_encode(": ".date('H:i')));

$sheet->setCellValue('B12',utf8_encode($RowParam['Organisme']));
$sheet->setCellValue('B13',utf8_encode($RowParam['Adresse']));
$sheet->getStyle('B13')->getAlignment()->setWrapText(true);
$sheet->setCellValue('B14',utf8_encode($RowParam['Telephone']));

$ligne=22;
while($RowPers=mysqli_fetch_array($ResultSessionPersonnes))
{
	$trouve=0;
	foreach($tabIds as $valeur)
	{
		if($valeur==$RowPers['ID']){$trouve=1;}
		
	}
	if($trouve==1){
		if($ligne>22){
			//$sheet->insertNewRowBefore($ligne+1, 5);
			$sheet->duplicateStyle($sheet->getStyle('A22:I22'),'A'.$ligne.':I'.$ligne);
			$sheet->mergeCells('C'.$ligne.':D'.$ligne);
		}
		$sheet ->getRowDimension($ligne)->setRowHeight(40);
		$sheet->setCellValue('A'.$ligne,utf8_encode($RowPers['STAGIAIRE_NOM']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($RowPers['STAGIAIRE_PRENOM']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($Libelle));
		$sheet->setCellValue('E'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($RowSession['DATE_DEBUT'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($RowSession['DATE_FIN'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode($NbHeure));
		$sheet->setCellValue('H'.$ligne,utf8_encode($NbJour));
		$sheet->setCellValue('I'.$ligne,utf8_encode($RowPers['COUT']));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="DemandePriseEnCharge.xlsx"');
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../tmp/DemandePriseEnCharge.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
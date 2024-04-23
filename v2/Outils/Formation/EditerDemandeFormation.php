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
$CoutPedagogique=0;
if($RowSession['RECYCLAGE']==0){$Libelle=$RowInfo['Libelle'];$NbJour=$RowParam['NbJour'];$NbHeure=$RowParam['Duree'];}
else{
	if($RowForm['Recyclage']==0){$Libelle=$RowInfo['Libelle'];$NbJour=$RowParam['NbJour'];$NbHeure=$RowParam['Duree'];}
	else{$Libelle=$RowInfo['LibelleRecyclage'];$NbJour=$RowParam['NbJourRecyclage'];$NbHeure=$RowParam['DureeRecyclage'];}
}

$ResultSessionPersonnes=mysqli_query($bdd,getchaineSQL_sessionPersonne($_GET['Id_Session']));


$excel = $workbook->load('Template_DemandeFormation.xlsx');

$sheet = $excel->getSheetByName('demande de formation');

$sheet->setCellValue('B26',utf8_encode($Libelle));
$sheet->setCellValue('B27',utf8_encode($RowParam['Organisme']));

$sheet->setCellValue('B34',utf8_encode($NbHeure));

if($RowSession['DATE_DEBUT']==$RowSession['DATE_FIN']){
	$sheet->setCellValue('B33',utf8_encode(AfficheDateJJ_MM_AAAA($RowSession['DATE_DEBUT'])));
}
else{
	$sheet->setCellValue('B33',utf8_encode(AfficheDateJJ_MM_AAAA($RowSession['DATE_DEBUT'])." - ".AfficheDateJJ_MM_AAAA($RowSession['DATE_FIN'])));
}

$ligne=13;
while($RowPers=mysqli_fetch_array($ResultSessionPersonnes))
{
	$trouve=0;
	foreach($tabIds as $valeur)
	{
		if($valeur==$RowPers['ID']){$trouve=1;}
		
	}
	if($trouve==1){
		$sheet->setCellValue('B'.$ligne,utf8_encode($RowPers['Code_Analytique']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($RowPers['STAGIAIRE_NOM']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($RowPers['STAGIAIRE_PRENOM']));
		$CoutPedagogique+=$RowPers['COUT'];
		$ligne++;
	}
}

$sheet->setCellValue('B38',utf8_encode($CoutPedagogique));

$sheet->setCellValue('B57',utf8_encode(date('d/m/y')));

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="DemandeFormation.xlsx"');
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../tmp/DemandeFormation.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
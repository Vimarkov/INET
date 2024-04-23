<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("../Formation/Globales_Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
require_once("../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$vert="6fff55";
$orange="ffe915";
$rouge="ff151c";
$gris="aaaaaa";
$blanc="ffffff";

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('D-0705-014-EPPBilan.xlsx');

$sheet = $excel->getSheetByName('Bilan');

$requete="SELECT new_rh_etatcivil.Id, Nom, Prenom,MatriculeAAA,DateAncienneteCDI,YEAR(IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir)) AS Annee,
			MetierPaie AS Metier
			FROM epe_personne_datebutoir
			LEFT JOIN new_rh_etatcivil
			ON epe_personne_datebutoir.Id_Personne=new_rh_etatcivil.Id
			WHERE epe_personne_datebutoir.Id=".$_GET['Id'];
$result=mysqli_query($bdd,$requete);
$rowEPE=mysqli_fetch_array($result);


$req="SELECT Id, Type,ModeBrouillon,Id_Personne,DateCreation,Id_Createur,Metier,DateAnciennete,DateEntretien,DateButoir,Id_Evaluateur,MetierManager,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
		(SELECT Nom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Nom,
		(SELECT Prenom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Prenom,
		(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MatriculeAAA,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS Manager,
		(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS MatriculeAAAManager,
		EPPBilan,EPPBilanRefuseSalarie,NbEntretienPro,ComNbEntretiensPro,ActionFormationOEPPBilan,ActionFormationNonOEPPBilan,CertifParFormation,EvolutionSalariale,EvolutionPro,
		IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01' ,'Réalisé','Signature manager'))) AS Etat
	FROM epe_personne 
	WHERE Suppr=0 
	AND Id_Personne=".$rowEPE['Id']."
	AND YEAR(DateButoir)='".$rowEPE['Annee']."'
	AND Type='EPP Bilan'
	ORDER BY Id DESC ";
$result=mysqli_query($bdd,$req);
$rowEPERempli=mysqli_fetch_array($result);

$Id_Prestation=0;
$Id_Pole=0;

$Plateforme="";
$req="SELECT Libelle FROM new_competences_plateforme WHERE Id=".$rowEPERempli['Id_Plateforme'];
$ResultPresta=mysqli_query($bdd,$req);
$NbPrest=mysqli_num_rows($ResultPresta);
if($NbPrest>0){
	$RowPresta=mysqli_fetch_array($ResultPresta);
	$Plateforme=$RowPresta['Libelle'];
}

$Manager=stripslashes($rowEPERempli['Manager']);
$MatriculeAAAManager=$rowEPERempli['MatriculeAAAManager'];
$MetierManager=stripslashes($rowEPERempli['MetierManager']);

$sheet->setCellValue('B3',utf8_encode(stripslashes($rowEPE['MatriculeAAA'])));
$sheet->setCellValue('B4',utf8_encode(stripslashes($rowEPE['Nom'])));
$sheet->setCellValue('B5',utf8_encode(stripslashes($rowEPE['Prenom'])));
$sheet->setCellValue('B6',utf8_encode(stripslashes($rowEPE['Metier'])));
$sheet->setCellValue('B7',utf8_encode(AfficheDateJJ_MM_AAAA($rowEPERempli['DateAnciennete'])));

$sheet->setCellValue('O3',utf8_encode(AfficheDateJJ_MM_AAAA($rowEPERempli['DateEntretien'])));
$sheet->setCellValue('O4',utf8_encode(stripslashes($Plateforme)));
$sheet->setCellValue('O5',utf8_encode(stripslashes($Manager)));
$sheet->setCellValue('O6',utf8_encode(stripslashes($MatriculeAAAManager)));
$sheet->setCellValue('O7',utf8_encode(stripslashes($MetierManager)));


$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
$objDrawingNonCoche->setName('case');
$objDrawingNonCoche->setDescription('PHPExcel case');
$objDrawingNonCoche->setPath("../../Images/CaseCoche.png");
$objDrawingNonCoche->setWidth(30);
$objDrawingNonCoche->setHeight(30);
$objDrawingNonCoche->setOffsetX(-40);
$objDrawingNonCoche->setOffsetY(10);
$objDrawingNonCoche->setCoordinates('B9');
$objDrawingNonCoche->setWorksheet($sheet);

$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
$objDrawingNonCoche->setName('case');
$objDrawingNonCoche->setDescription('PHPExcel case');
if($rowEPERempli['EPPBilanRefuseSalarie']==1){
	$objDrawingNonCoche->setPath("../../Images/CaseCoche.png");
}
else{
	$objDrawingNonCoche->setPath("../../Images/CaseNonCoche.png");
}
$objDrawingNonCoche->setWidth(30);
$objDrawingNonCoche->setHeight(30);
$objDrawingNonCoche->setOffsetX(-40);
$objDrawingNonCoche->setOffsetY(10);
$objDrawingNonCoche->setCoordinates('B11');
$objDrawingNonCoche->setWorksheet($sheet);


if($rowEPERempli['EPPBilanRefuseSalarie']==1){
	if($rowEPERempli['Etat']=="Signature manager"){
		$sheet->setCellValue('R11',utf8_encode(stripslashes($rowEPERempli['Nom']." ".$rowEPERempli['Prenom']." \n'signature électronique'")));
	}
}

$lesEntretiens=$rowEPERempli['NbEntretienPro'];
if($rowEPERempli['NbEntretienPro']>0){
$lesEntretiens.="\n".stripslashes($rowEPERempli['ComNbEntretiensPro']);	
}

$sheet->setCellValue('D13',utf8_encode(stripslashes($lesEntretiens)));
$sheet->getStyle('D13')->getAlignment()->setWrapText(true);

$sheet->setCellValue('D18',utf8_encode(stripslashes($rowEPERempli['ActionFormationOEPPBilan'])));
$sheet->getStyle('D18')->getAlignment()->setWrapText(true);

$sheet->setCellValue('D24',utf8_encode(stripslashes($rowEPERempli['ActionFormationNonOEPPBilan'])));
$sheet->getStyle('D24')->getAlignment()->setWrapText(true);

$sheet->setCellValue('D31',utf8_encode(stripslashes($rowEPERempli['CertifParFormation'])));
$sheet->getStyle('D31')->getAlignment()->setWrapText(true);

$sheet->setCellValue('D35',utf8_encode(stripslashes($rowEPERempli['EvolutionSalariale'])));
$sheet->getStyle('D35')->getAlignment()->setWrapText(true);

$sheet->setCellValue('D38',utf8_encode(stripslashes($rowEPERempli['EvolutionPro'])));
$sheet->getStyle('D38')->getAlignment()->setWrapText(true);

if($rowEPERempli['Etat']=="Signature salarié"){
	$sheet->setCellValue('D42',utf8_encode(stripslashes(AfficheDateJJ_MM_AAAA($rowEPERempli['DateEntretien']))));
	$sheet->setCellValue('R43',utf8_encode(stripslashes($rowEPERempli['Manager']." \n'signature électronique'")));
	$sheet->getStyle('R43')->getAlignment()->setWrapText(true);
}
elseif($rowEPERempli['Etat']=="Signature manager"){
	$sheet->setCellValue('D42',utf8_encode(stripslashes(AfficheDateJJ_MM_AAAA($rowEPERempli['DateEntretien']))));
	$sheet->setCellValue('R43',utf8_encode(stripslashes($rowEPERempli['Manager']." \n'signature électronique'")));
	$sheet->getStyle('R43')->getAlignment()->setWrapText(true);
	$sheet->setCellValue('D43',utf8_encode(stripslashes($rowEPERempli['Nom']." ".$rowEPERempli['Prenom']." \n'signature électronique'")));
	$sheet->getStyle('D43')->getAlignment()->setWrapText(true);
}



//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="D-0705-014.xlsx"');}
else{header('Content-Disposition: attachment;filename="D-0705-014.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../tmp/D-0705-014.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
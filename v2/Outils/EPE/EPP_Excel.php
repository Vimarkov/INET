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
$excel = $workbook->load('D-0705-013 EPP.xlsx');

$sheet = $excel->getSheetByName('D-0705-013_EPP');

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
		EPP2Ans,EPPReprise,EPPRefuseSalarie,SouhaitEvolutionON,SouhaitEvolution,SouhaitMobiliteON,SouhaitMobilite,FormationEvolutionON,FormationEvolution,ComEvaluateurEPP,
		ComSalarie,ComEvaluateur,DateEvaluateur,DateSalarie,
		IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01' ,'Réalisé','Signature manager'))) AS Etat
	FROM epe_personne 
	WHERE Suppr=0 
	AND Id_Personne=".$rowEPE['Id']."
	AND YEAR(DateButoir)='".$rowEPE['Annee']."'
	AND Type='EPP'
	ORDER BY Id DESC ";
$result=mysqli_query($bdd,$req);
$rowEPERempli=mysqli_fetch_array($result);


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
if($rowEPERempli['EPP2Ans']==1){
	$objDrawingNonCoche->setPath("../../Images/CaseCoche.png");
}
else{
	$objDrawingNonCoche->setPath("../../Images/CaseNonCoche.png");
}
$objDrawingNonCoche->setWidth(30);
$objDrawingNonCoche->setHeight(30);
$objDrawingNonCoche->setOffsetX(-60);
$objDrawingNonCoche->setOffsetY(10);
$objDrawingNonCoche->setCoordinates('B9');
$objDrawingNonCoche->setWorksheet($sheet);

$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
$objDrawingNonCoche->setName('case');
$objDrawingNonCoche->setDescription('PHPExcel case');
if($rowEPERempli['EPPReprise']==1){
	$objDrawingNonCoche->setPath("../../Images/CaseCoche.png");
}
else{
	$objDrawingNonCoche->setPath("../../Images/CaseNonCoche.png");
}
$objDrawingNonCoche->setWidth(30);
$objDrawingNonCoche->setHeight(30);
$objDrawingNonCoche->setOffsetX(-60);
$objDrawingNonCoche->setOffsetY(10);
$objDrawingNonCoche->setCoordinates('B10');
$objDrawingNonCoche->setWorksheet($sheet);

$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
$objDrawingNonCoche->setName('case');
$objDrawingNonCoche->setDescription('PHPExcel case');
if($rowEPERempli['EPPRefuseSalarie']==1){
	$objDrawingNonCoche->setPath("../../Images/CaseCoche.png");
}
else{
	$objDrawingNonCoche->setPath("../../Images/CaseNonCoche.png");
}
$objDrawingNonCoche->setWidth(30);
$objDrawingNonCoche->setHeight(30);
$objDrawingNonCoche->setOffsetX(-60);
$objDrawingNonCoche->setOffsetY(10);
$objDrawingNonCoche->setCoordinates('B12');
$objDrawingNonCoche->setWorksheet($sheet);


if($rowEPERempli['EPPRefuseSalarie']==1){
	if($rowEPERempli['Etat']=="Signature manager"){
		$sheet->setCellValue('R12',utf8_encode(stripslashes($rowEPERempli['Nom']." ".$rowEPERempli['Prenom']." \n'signature électronique'")));
	}
}


$req="SELECT DISTINCT Id_SouhaitEvolution, 
(SELECT Libelle FROM epe_typeevolution WHERE Id=Id_SouhaitEvolution) AS Evolution 
FROM epe_personne_souhaitevolution2 
WHERE Id_EPE=".$rowEPERempli['Id']." 
ORDER BY (SELECT Libelle FROM epe_typeevolution WHERE Id=Id_SouhaitEvolution)";
$resultE=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($resultE);

$evolution="";
if($nb>0){
	while($rowE=mysqli_fetch_array($resultE)){
		if($evolution<>""){$evolution.="\n";}
		$evolution.=$rowE['Evolution'];
	}
}
if($rowEPERempli['SouhaitEvolution']<>""){
	$evolution.="\n";
}

$sheet->setCellValue('D15',utf8_encode(stripslashes($evolution.$rowEPERempli['SouhaitEvolution'])));
$sheet->getStyle('D15')->getAlignment()->setWrapText(true);

$req="SELECT DISTINCT Id_SouhaitMobilite, 
(SELECT Libelle FROM epe_mobilite WHERE Id=Id_SouhaitMobilite) AS Mobilite 
FROM epe_personne_souhaitmobilite2 
WHERE Id_EPE=".$rowEPERempli['Id']." 
ORDER BY (SELECT Libelle FROM epe_mobilite WHERE Id=Id_SouhaitMobilite)";
$resultM=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($resultM);

$mobilite="";
if($nb>0){
	while($rowM=mysqli_fetch_array($resultM)){
		if($mobilite<>""){$mobilite.="\n";}
		$mobilite.=$rowM['Mobilite'];
	}
}
if($rowEPERempli['SouhaitMobilite']<>""){
	$mobilite.="\n";
}
$sheet->setCellValue('D21',utf8_encode(stripslashes($mobilite.$rowEPERempli['SouhaitMobilite'])));
$sheet->getStyle('D21')->getAlignment()->setWrapText(true);

$sheet->setCellValue('D31',utf8_encode(stripslashes($rowEPERempli['FormationEvolution'])));
$sheet->getStyle('D31')->getAlignment()->setWrapText(true);

$sheet->setCellValue('D43',utf8_encode(stripslashes($rowEPERempli['ComEvaluateurEPP'])));
$sheet->getStyle('D43')->getAlignment()->setWrapText(true);

if($rowEPERempli['Etat']=="Signature salarié"){
	$sheet->setCellValue('D46',utf8_encode(stripslashes(AfficheDateJJ_MM_AAAA($rowEPERempli['DateEntretien']))));
	$sheet->setCellValue('R47',utf8_encode(stripslashes($rowEPERempli['Manager']." \n'signature électronique'")));
	$sheet->getStyle('R47')->getAlignment()->setWrapText(true);
	
}
elseif($rowEPERempli['Etat']=="Signature manager"){
	$sheet->setCellValue('D46',utf8_encode(stripslashes(AfficheDateJJ_MM_AAAA($rowEPERempli['DateEntretien']))));
	$sheet->setCellValue('D47',utf8_encode(stripslashes($rowEPERempli['Nom']." ".$rowEPERempli['Prenom']." \n'signature électronique'")));
	$sheet->getStyle('D47')->getAlignment()->setWrapText(true);
	
	$sheet->setCellValue('R47',utf8_encode(stripslashes($rowEPERempli['Manager']." \n'signature électronique'")));
	$sheet->getStyle('R47')->getAlignment()->setWrapText(true);
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="D-0705-013.xlsx"');}
else{header('Content-Disposition: attachment;filename="D-0705-013.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../tmp/D-0705-013.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
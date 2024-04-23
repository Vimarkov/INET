<?php
session_start();
require_once("../Fonctions.php");
require_once("Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require_once("../Formation/Globales_Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();

if($LangueAffichage=="FR"){$excel = $workbook->load('D-0829-004-GRP.xlsx');}
else{$excel = $workbook->load('D-0829-004-GRP-en.xlsx');}


$sheet = $excel->getSheetByName('D-0829-004');

$req="SELECT Id,
	Type,
	Id_Materiel__Id_Caisse,
	PV_Date,
	PV_RefDeclaration,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=PV_Id_Declarant) AS Personne,
	(SELECT Matricule FROM new_rh_etatcivil WHERE Id=PV_Id_Declarant) AS Matricule,
	(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=PV_Id_Prestation) AS Prestation,
	(SELECT Libelle FROM new_competences_pole WHERE Id=PV_Id_Pole) AS Pole,
	PV_Poste,
	PV_TypeMSN,
	PV_MSN,
	PV_Zone,
	PV_Condition,
	PV_Action,
	PV_Type,
	PV_Lieu,
	PV_Remarque
	FROM tools_mouvement
	WHERE Suppr=0
	AND Id=".$_GET['Id']."
	ORDER BY PV_Date DESC, Id DESC
";
$Result=mysqli_query($bdd,$req);
$NbEnreg=mysqli_num_rows($Result);
$row=mysqli_fetch_array($Result);

$pole="";
if($row['Pole']<>""){$pole=" (".$row['Pole'].") ";}
$sheet->setCellValue('E10',utf8_encode($row['Prestation'].$pole." ".$row['PV_Lieu']));
$sheet->setCellValue('J10',utf8_encode($row['PV_Poste']));

$sheet->setCellValue('E13',utf8_encode(AfficheDateJJ_MM_AAAA($row['PV_Date'])));
$sheet->setCellValue('I13',utf8_encode($row['PV_TypeMSN']));
$sheet->setCellValue('M13',utf8_encode($row['PV_MSN']));

$sheet->setCellValue('F15',utf8_encode($row['Personne']));
$sheet->setCellValue('D17',utf8_encode($row['Matricule']));

$sheet->setCellValue('G21',utf8_encode("1"));

$sheet->setCellValue('B24',utf8_encode($row['PV_Zone']));
$sheet->setCellValue('B29',utf8_encode($row['PV_Condition']));
$sheet->setCellValue('B38',utf8_encode($row['PV_Action']));
$sheet->setCellValue('B47',utf8_encode($row['PV_Remarque']));

if($row['Type']==0){
	$Requete="
		SELECT
			NumAAA,
			(SELECT (SELECT Libelle FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE tools_modelemateriel.Id=Id_ModeleMateriel) AS Type,
			(SELECT Libelle FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) AS Designation,
			IF((SELECT tools_mouvement.Id_Caisse
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
				(
					SELECT (
						SELECT new_competences_prestation.Id_Plateforme
						FROM tools_mouvement
						LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
						WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
					)
					FROM tools_mouvement AS TAB_Mouvement
					LEFT JOIN tools_caisse ON TAB_Mouvement.Id_Caisse=tools_caisse.Id
					LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
			(
				SELECT new_competences_prestation.Id_Plateforme
				FROM tools_mouvement
				LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)) AS Id_Plateforme

		FROM
			tools_materiel
		WHERE
			Id=".$row['Id_Materiel__Id_Caisse']."";
}
else{
	$Requete="
		SELECT
			NumAAA,
			(SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType ) AS Type,
			(SELECT Libelle FROM tools_famillemateriel WHERE Id=Id_FamilleMateriel) AS Designation,
			(
				SELECT new_competences_prestation.Id_Plateforme
				FROM tools_mouvement
				LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			) AS Id_Plateforme
		FROM
			tools_caisse
		WHERE
			Id='".$row['Id_Materiel__Id_Caisse']."';";
}
$ResultMat=mysqli_query($bdd,$Requete);
$NbEnregMat=mysqli_num_rows($ResultMat);
$rowMat=mysqli_fetch_array($ResultMat);

$sheet->setCellValue('B21',utf8_encode($rowMat['Type']." - ".$rowMat['Designation']));
$sheet->setCellValue('I21',utf8_encode($rowMat['NumAAA']));

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('case');
$objDrawing->setDescription('PHPExcel case');

$objDrawing2 = new PHPExcel_Worksheet_Drawing();
$objDrawing2->setName('case');
$objDrawing2->setDescription('PHPExcel case');

if($row['PV_Type']==0){
	$objDrawing->setPath('../../Images/CaseCoche.png');
	$objDrawing2->setPath('../../Images/CaseNonCoche.png');
}
else{
	$objDrawing->setPath('../../Images/CaseNonCoche.png');
	$objDrawing2->setPath('../../Images/CaseCoche.png');
}

$objDrawing->setWidth(25);
$objDrawing->setHeight(25);
$objDrawing->setCoordinates('C7');
$objDrawing->setOffsetX(30);
$objDrawing->setOffsetY(5);
$objDrawing->setWorksheet($sheet);

$objDrawing2->setWidth(25);
$objDrawing2->setHeight(25);
$objDrawing2->setCoordinates('C8');
$objDrawing2->setOffsetX(30);
$objDrawing2->setOffsetY(5);
$objDrawing2->setWorksheet($sheet);


$Id_Plateforme=0;
if($Id_Plateforme==0){
	if($rowMat['Id_Plateforme']<>0 && $rowMat['Id_Plateforme']<>""){$Id_Plateforme=$rowMat['Id_Plateforme'];}
}

if($Id_Plateforme>0){
	$req="SELECT Libelle, Logo FROM new_competences_plateforme WHERE Id=".$Id_Plateforme;
	$ResultPlat=mysqli_query($bdd,$req);
	$rowPlat=mysqli_fetch_array($ResultPlat);
	
	$sheet->setCellValue('K6',utf8_encode($rowPlat['Libelle']));
	if($rowPlat['Logo']<>""){
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('logo');
		$objDrawing->setDescription('PHPExcel logo');
		$objDrawing->setPath('../../Images/Logos/'.$rowPlat['Logo']);
		$objDrawing->setHeight(80);
		$objDrawing->setWidth(150);
		$objDrawing->setCoordinates('K2');
		$objDrawing->setOffsetX(30);
		$objDrawing->setOffsetY(8);
		$objDrawing->setWorksheet($sheet);
	}
}


//Enregistrement du fichier excel
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="D-0829-004.xlsx"');}
else{header('Content-Disposition: attachment;filename="D-0829-004.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../tmp/D-0829-004.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
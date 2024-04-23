<?php
session_start();
require_once("../Fonctions.php");
require_once("Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();

//PARTIE CAISSE DE LA REQUETE
$Requete2Caisse="
	SELECT Id,
	'Caisse' AS TYPESELECT,
	NumAAA AS NumAAA,
	Num,
	(
		SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=new_competences_prestation.Id_Plateforme)
		FROM tools_mouvement
		LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
		WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
	) AS LIBELLE_PLATEFORME,
	(
		SELECT new_competences_prestation.Id_Plateforme
		FROM tools_mouvement
		LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
		WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
	) AS Id_Plateforme
	";
$RequeteCaisse="FROM
	tools_caisse
WHERE 
	tools_caisse.Suppr=0 ";
$RequeteCaisse.=" AND Id = ".$_GET['Id']." ";

$Result=mysqli_query($bdd,$Requete2Caisse.$RequeteCaisse);
$NbEnreg=mysqli_num_rows($Result);

$Row=mysqli_fetch_array($Result);
if($Row['Id_Plateforme']==19){
	$excel = $workbook->load('D-0829-003-SE.xlsx');
}
else{
	if($LangueAffichage=="FR"){$excel = $workbook->load('D-0829-003-GRP.xlsx');}
	else{$excel = $workbook->load('D-0829-003-GRP-en.xlsx');}
}

$sheet = $excel->getSheetByName('D-0829-003');
				
$sheet->setCellValue('B12',utf8_encode($Row['NumAAA']." (n°".$Row['Num'].")"));

$Id_Plateforme=0;
if($Row['Id_Plateforme']>0){$Id_Plateforme=$Row['Id_Plateforme'];}

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
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="D-0829-003-GRP.xlsx"');}
else{header('Content-Disposition: attachment;filename="D-0829-003-GRP-en.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../tmp/D-0829-003.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
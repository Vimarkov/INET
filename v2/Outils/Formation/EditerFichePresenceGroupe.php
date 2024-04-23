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

$ResultSession=get_session($_GET['Id']);
$RowSession=mysqli_fetch_array($ResultSession);

if($LangueAffichage=="FR"){$excel = $workbook->load('D-0725-GRP.xlsx');}
else{$excel = $workbook->load('D-0725-GRP-en.xlsx');}

$sheet = $excel->getSheetByName('D-0725');

//Plateforme
$req="SELECT Libelle,Logo FROM new_competences_plateforme WHERE Id=".$RowSession['ID_PLATEFORME'];
$result=mysqli_query($bdd,$req);
$rowPlat=mysqli_fetch_array($result);
$sheet->setCellValue('F3',utf8_encode($rowPlat['Libelle']));

if($rowPlat['Logo']<>""){
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('logo');
	$objDrawing->setDescription('PHPExcel logo');
	$objDrawing->setPath('../../Images/Logos/'.$rowPlat['Logo']);
	$objDrawing->setHeight(70);
	$objDrawing->setWidth(130);
	$objDrawing->setCoordinates('F1');
	$objDrawing->setOffsetX(20);
	$objDrawing->setOffsetY(8);
	$objDrawing->setWorksheet($sheet);
}

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('checked');
$objDrawing->setDescription('checked');
$objDrawing->setPath('../../Images/checked.png');
$objDrawing->setCoordinates('A5');
$objDrawing->setOffsetX(25);
$objDrawing->setOffsetY(5);
$objDrawing->setWorksheet($sheet);
	
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('checkednot');
$objDrawing->setDescription('checkednot');
$objDrawing->setPath('../../Images/checkednot.png');
$objDrawing->setCoordinates('C5');
$objDrawing->setOffsetX(30);
$objDrawing->setOffsetY(5);
$objDrawing->setWorksheet($sheet);
	
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('checkednot');
$objDrawing->setDescription('checkednot');
$objDrawing->setPath('../../Images/checkednot.png');
$objDrawing->setCoordinates('D5');
$objDrawing->setOffsetX(50);
$objDrawing->setOffsetY(5);
$objDrawing->setWorksheet($sheet);

//INFORMATION SUR LA FORMATION 
//INTITULE
$req="SELECT (SELECT Libelle FROM form_groupe_formation WHERE form_groupe_formation.Id=form_session_groupe.Id_GroupeFormation) AS Groupe
	FROM form_session
	LEFT JOIN form_session_groupe
	ON form_session.Id_GroupeSession=form_session_groupe.Id
	WHERE form_session.Id=".$_GET['Id'];
$result=mysqli_query($bdd,$req);
$rowGroupe=mysqli_fetch_array($result);

$sheet->setCellValue('B6',utf8_encode($rowGroupe['Groupe']));
$sheet->getStyle('B6')->getAlignment()->setWrapText(true);

//REFERENCE POUR TYPE DE FORMATION
$sheet->setCellValue('E6',utf8_encode('NA'));

if($RowSession['ID_TYPEFORMATION']==$IdTypeFormationInterne)
{
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('checked');
	$objDrawing->setDescription('checked');
	$objDrawing->setPath('../../Images/checked.png');
	$objDrawing->setCoordinates('F6');
	$objDrawing->setOffsetX(3);
	$objDrawing->setOffsetY(20);
	$objDrawing->setWorksheet($sheet);
	
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('checkednot');
	$objDrawing->setDescription('checkednot');
	$objDrawing->setPath('../../Images/checkednot.png');
	$objDrawing->setCoordinates('F6');
	$objDrawing->setOffsetX(70);
	$objDrawing->setOffsetY(20);
	$objDrawing->setWorksheet($sheet);
}
else
{
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('checkednot');
	$objDrawing->setDescription('checkednot');
	$objDrawing->setPath('../../Images/checkednot.png');
	$objDrawing->setCoordinates('F6');
	$objDrawing->setOffsetX(3);
	$objDrawing->setOffsetY(20);
	$objDrawing->setWorksheet($sheet);
	
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('checked');
	$objDrawing->setDescription('checked');
	$objDrawing->setPath('../../Images/checked.png');
	$objDrawing->setCoordinates('F6');
	$objDrawing->setOffsetX(70);
	$objDrawing->setOffsetY(20);
	$objDrawing->setWorksheet($sheet);
}

//LIEU
$sheet->setCellValue('B7',utf8_encode($RowSession['LIEU']));

//DUREE
$req="SELECT Duree, DureeRecyclage 
	FROM form_formation_plateforme_parametres 
	WHERE Suppr=0 AND Id_Plateforme=".$RowSession['ID_PLATEFORME']." AND Id_Formation=".$RowSession['ID_FORMATION'];
$result=mysqli_query($bdd,$req);
$rowDuree=mysqli_fetch_array($result);

$req="SELECT SUM((SELECT IF(form_session.Recyclage=0,Duree,DureeRecyclage) 
		FROM form_formation_plateforme_parametres
		WHERE form_formation_plateforme_parametres.Suppr=0
		AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme
		AND form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
		LIMIT 1)) AS Duree
	FROM form_session 
	WHERE form_session.Suppr=0 
	AND Id_GroupeSession=".$RowSession['ID_GROUPE_SESSION']." ";
$result=mysqli_query($bdd,$req);
$rowDuree=mysqli_fetch_array($result);


$req=" 
	SELECT DateSession AS DateDebut, Heure_Debut AS HEURE_DEBUT
	FROM form_session_date 
	LEFT JOIN form_session 
	ON form_session_date.Id_Session=form_session.Id
	WHERE form_session_date.Suppr=0
	AND form_session.Suppr=0
	AND Id_GroupeSession=".$RowSession['ID_GROUPE_SESSION']." 
	ORDER BY DateSession ASC, Heure_Debut ASC
	";
$ResultInfos=mysqli_query($bdd,$req);
$RowInfosD=mysqli_fetch_array($ResultInfos);

$req=" 
	SELECT DateSession AS DateFin, Heure_Fin AS HEURE_FIN
	FROM form_session_date 
	LEFT JOIN form_session 
	ON form_session_date.Id_Session=form_session.Id
	WHERE form_session_date.Suppr=0
	AND form_session.Suppr=0
	AND Id_GroupeSession=".$RowSession['ID_GROUPE_SESSION']." 
	AND Formation_Liee=1
	ORDER BY DateSession DESC,Heure_Fin DESC
	";
$ResultInfos=mysqli_query($bdd,$req);
$RowInfosF=mysqli_fetch_array($ResultInfos);

$heures=" (".$RowInfosD['HEURE_DEBUT']." - ".$RowInfosF['HEURE_FIN'].") ";

$duree=explode(".",$rowDuree['Duree']);
$reste=intval($duree[1]/60);
$lesheures=$duree[0]+$reste;
$minutes=fmod($duree[1],60);
if(strlen($minutes)==1){$minutes="0".$minutes;}
$laduree=$lesheures.":".$minutes;

$sheet->setCellValue('F7',utf8_encode($laduree.$heures));

//Date
$sheet->setCellValue('C9',utf8_encode(AfficheDateJJ_MM_AAAA($RowSession['DATE_DEBUT'])));
$sheet->setCellValue('F9',utf8_encode(AfficheDateJJ_MM_AAAA($RowSession['DATE_DEBUT'])));

//LISTE DES STAGIAIRES
$req="SELECT Id_Personne,
(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne 
FROM form_session_personne 
WHERE Suppr=0 AND Validation_Inscription=1 AND Id_Session=".$_GET['Id']. "
ORDER BY Personne";
$result=mysqli_query($bdd,$req);
$nbPersonne=mysqli_num_rows($result);
$ligne=11;
$col="A";
if($nbPersonne>0)
{
	while($row=mysqli_fetch_array($result))
	{
	    if($ligne==22){$ligne=11;$col++;$col++;$col++;}
		$sheet->setCellValue($col.$ligne,utf8_encode($row['Personne']));
		$ligne++;
	}
}

//NOM DE L'INTERVENANT
$sheet->setCellValue('C23',utf8_encode($RowSession['FORMATEUR_NOMPRENOM']));
$sheet->setCellValue('B24',utf8_encode('A.A.A'));

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="EN"){header('Content-Disposition: attachment;filename="D-0725-GRP-en.xlsx"');}
else{header('Content-Disposition: attachment;filename="D-0725-GRP.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../tmp/D-0725-GRP.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
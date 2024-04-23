<?php
session_start();
require("../../ConnexioniSansBody.php");
require_once("../Globales_Fonctions.php");
require_once("../../Fonctions.php");
include '../../../Excel/PHPExcel.php';
include '../../../Excel/PHPExcel/Writer/Excel2007.php';

$ReqFormSessionPersonneDoc="
    SELECT
        form_session_personne.Id_Personne,
        form_session_personne_document.DateHeureRepondeur,
		form_session_personne_document.Id_Document,
		form_session_personne_document.Id_LangueDocument,
        form_session_personne.Id_Session,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Repondeur) AS Repondeur,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Stagiaire
    FROM
        form_session_personne_document
    LEFT JOIN form_session_personne
        ON form_session_personne_document.Id_Session_Personne=form_session_personne.Id
    WHERE
        form_session_personne_document.Id=".$_GET['Id_Session_Personne_Document'];
$ResultFormSessionPersonneDoc=mysqli_query($bdd,$ReqFormSessionPersonneDoc);
$RowFormSessionPersonneDoc=mysqli_fetch_array($ResultFormSessionPersonneDoc);

$ReqDoc_Langue="
	SELECT
		Id,
		Id_Document,
		Id_Langue,
		Libelle
	FROM
		form_document_langue
	WHERE
		Suppr=0
		AND Id_Langue=".$RowFormSessionPersonneDoc['Id_LangueDocument']."
		AND Id_Document=".$RowFormSessionPersonneDoc['Id_Document'];
$ResultDoc_Langue=mysqli_query($bdd,$ReqDoc_Langue);
$RowDoc_Langue=mysqli_fetch_array($ResultDoc_Langue);

$ResultSession=get_session($RowFormSessionPersonneDoc['Id_Session']);
$RowSession=mysqli_fetch_array($ResultSession);

//TRAITEMENT DU DOCUMENT EXCEL
//----------------------------
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$workbook = new PHPExcel_Reader_Excel2007();
if($RowFormSessionPersonneDoc['Id_LangueDocument']==1){$excel = $workbook->load('INDIVIDUAL_CHART_V1_FR.xlsx');}
else{$excel = $workbook->load('INDIVIDUAL_CHART_V1_EN.xlsx');}

$sheet = $excel->getSheetByName('1');
$Doc_Q_R_RStagiaires=Generer_Document($RowDoc_Langue['Id'], $_GET['Id_Session_Personne_Document'], true);

$sheet->setCellValue('C14',utf8_encode(stripslashes($RowFormSessionPersonneDoc['Stagiaire'])));
$sheet->setCellValue('E14',utf8_encode($RowFormSessionPersonneDoc['DateHeureRepondeur']));

foreach($Doc_Q_R_RStagiaires as $Ligne_Q_R_RStagiaires)
{
    if($Ligne_Q_R_RStagiaires[4]==1)
    {
        if($RowFormSessionPersonneDoc['Id_LangueDocument']==1){$sheet->setCellValue('G14',utf8_encode("'Signature électronique'\n".stripslashes($RowFormSessionPersonneDoc['Stagiaire'])));}
        else{$sheet->setCellValue('G14',utf8_encode("'Electronical signature'\n".stripslashes($RowFormSessionPersonneDoc['Stagiaire'])));}
    }
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($RowFormSessionPersonneDoc['Id_LangueDocument']==1){header('Content-Disposition: attachment;filename="INDIVIDUAL_CHART_V1_FR.xlsx"');}
else{header('Content-Disposition: attachment;filename="INDIVIDUAL_CHART_V1_EN.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../../tmp/Document.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
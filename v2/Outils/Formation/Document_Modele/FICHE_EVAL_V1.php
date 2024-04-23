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

$Logo_Plateforme="";
$Libelle_Plateforme="";
$Requete_Logo="SELECT Libelle,Logo FROM new_competences_plateforme WHERE Id=".$RowSession['ID_PLATEFORME'];
$Result_Logo=mysqli_query($bdd,$Requete_Logo);
$Nb_Result_Logo=mysqli_num_rows($Result_Logo);
if($Nb_Result_Logo>0)
{
    $Row_Logo=mysqli_fetch_array($Result_Logo);
    $Logo_Plateforme=$Row_Logo["Logo"];
    $Libelle_Plateforme=$Row_Logo["Libelle"];
}

//TRAITEMENT DU DOCUMENT EXCEL
//----------------------------
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$workbook = new PHPExcel_Reader_Excel2007();
if($RowFormSessionPersonneDoc['Id_LangueDocument']==1){$excel = $workbook->load('FICHE_EVAL_V1_FR.xlsx');}
else{$excel = $workbook->load('FICHE_EVAL_V1_EN.xlsx');}

$sheet = $excel->getSheetByName('1');
if($Logo_Plateforme <> "")
{
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('logo');
    $objDrawing->setDescription('PHPExcel logo');
    $objDrawing->setPath('../../../Images/Logos/'.$Logo_Plateforme);
    $objDrawing->setHeight(70);
    $objDrawing->setWidth(130);
    $objDrawing->setCoordinates('N1');
    $objDrawing->setOffsetX(3);
    $objDrawing->setOffsetY(13);
    $objDrawing->setWorksheet($sheet);
    $sheet->setCellValue('N2',utf8_encode($Libelle_Plateforme));
}

$Doc_Q_R_RStagiaires=Generer_Document($RowDoc_Langue['Id'], $_GET['Id_Session_Personne_Document'], true);

$ReqLangue="
    SELECT
        Id_Langue
	FROM
        form_formation_plateforme_parametres
	WHERE
        Suppr=0
        AND Id_Plateforme=".$RowSession['ID_PLATEFORME']."
        AND Id_Formation=".$RowSession['ID_FORMATION'];
$ResultLangue=mysqli_query($bdd,$ReqLangue);
$RowLangue=mysqli_fetch_array($ResultLangue);

$ReqLibelle="
    SELECT
        Libelle,
        LibelleRecyclage
	FROM
        form_formation_langue_infos
	WHERE
        Suppr=0
        AND Id_Langue=".$RowLangue['Id_Langue']."
        AND Id_Formation=".$RowSession['ID_FORMATION'];
$ResultLibelle=mysqli_query($bdd,$ReqLibelle);
$RowLibelle=mysqli_fetch_array($ResultLibelle);

if($RowSession['RECYCLAGE']==0){$sheet->setCellValue('D4',utf8_encode(stripslashes($RowLibelle['Libelle'])));}
else{$sheet->setCellValue('D4',utf8_encode(stripslashes($RowLibelle['LibelleRecyclage'])));}
$sheet->setCellValue('D5',utf8_encode(stripslashes($RowSession['LIEU'])));
$sheet->setCellValue('B6',utf8_encode($RowFormSessionPersonneDoc['DateHeureRepondeur']));
if($RowSession['ID_TYPEFORMATION']==$IdTypeFormationInterne){$sheet->setCellValue('J6',utf8_encode('X'));}
else{$sheet->setCellValue('M6',utf8_encode('X'));}
$sheet->setCellValue('D7',utf8_encode(stripslashes($RowFormSessionPersonneDoc['Stagiaire'])));

$Ligne=10;
foreach($Doc_Q_R_RStagiaires as $Ligne_Q_R_RStagiaires)
{
    if($Ligne_Q_R_RStagiaires[3]=="Note (1 à 6)")
    {
        $sheet->setCellValue('A'.$Ligne,utf8_encode($Ligne_Q_R_RStagiaires[1]));
        if($Ligne_Q_R_RStagiaires[4]=="1"){$sheet->setCellValue('D'.$Ligne,utf8_encode("X"));}
        elseif($Ligne_Q_R_RStagiaires[4]=="2"){$sheet->setCellValue('E'.$Ligne,utf8_encode("X"));}
        elseif($Ligne_Q_R_RStagiaires[4]=="3"){$sheet->setCellValue('F'.$Ligne,utf8_encode("X"));}
        elseif($Ligne_Q_R_RStagiaires[4]=="4"){$sheet->setCellValue('G'.$Ligne,utf8_encode("X"));}
        elseif($Ligne_Q_R_RStagiaires[4]=="5"){$sheet->setCellValue('H'.$Ligne,utf8_encode("X"));}
        elseif($Ligne_Q_R_RStagiaires[4]=="6"){$sheet->setCellValue('I'.$Ligne,utf8_encode("X"));}
        $sheet->setCellValue('J'.$Ligne,utf8_encode(stripslashes($Ligne_Q_R_RStagiaires[5])));
        $Ligne++;
    }
    elseif($Ligne_Q_R_RStagiaires[3]=="Oui/Non")
    {
        $sheet->setCellValue('A19',utf8_encode($Ligne_Q_R_RStagiaires[1]));
        if($Ligne_Q_R_RStagiaires[4]==1){$sheet->setCellValue('J19',utf8_encode("X"));}
        else{$sheet->setCellValue('J20',utf8_encode("X"));}
    }
    elseif($Ligne_Q_R_RStagiaires[3]=="Texte facultatif" || $Ligne_Q_R_RStagiaires[3]=="Texte obligatoire")
    {
        $sheet->setCellValue('A23',utf8_encode(stripslashes($Ligne_Q_R_RStagiaires[5])));
    }
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($RowFormSessionPersonneDoc['Id_LangueDocument']==1){header('Content-Disposition: attachment;filename="FICHE_EVAL_V1_FR.xlsx"');}
else{header('Content-Disposition: attachment;filename="FICHE_EVAL_V1_EN.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../../tmp/Document.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
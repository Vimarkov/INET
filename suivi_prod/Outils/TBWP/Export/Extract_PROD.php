<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

function TrsfDate_($Date){
	if($Date==Null || $Date=='' || $Date<='01-01-0001'){$dateReq="0001-01-01";}
	else
	{
		//Verifier si Google CHROME (true) ou Autre (fale)
		if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
		else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
		else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
		if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
		else {$NavigOk = false;}

		if($NavigOk ==1)
		{
			$tabDateTransfert = explode('-', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateReq = date("Y-m-d", $timestampTransfert);
		}
		else
		{
			$tabDateTransfert = explode('/', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[0], $tabDateTransfert[2]);
			$dateReq = date("Y-m-d", $timestampTransfert);
		}
	}
	
	return $dateReq;
}

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Extract');

$sheet->setCellValue('A1',utf8_encode("MSN"));
$sheet->setCellValue('B1',utf8_encode("N° OF"));
$sheet->setCellValue('C1',utf8_encode("N° FI"));
$sheet->setCellValue('D1',utf8_encode("Tps total réalisés"));
$sheet->setCellValue('E1',utf8_encode("Commentaire PROD"));
$sheet->setCellValue('F1',utf8_encode("Titre"));
$sheet->setCellValue('G1',utf8_encode("Tps restant"));
$sheet->setCellValue('H1',utf8_encode("Travail à réaliser"));
$sheet->setCellValue('I1',utf8_encode("Date intervention"));
$sheet->setCellValue('J1',utf8_encode("Statut PROD"));
$sheet->setCellValue('K1',utf8_encode("Retour PROD"));
$sheet->setCellValue('L1',utf8_encode("Vacation"));
$sheet->setCellValue('M1',utf8_encode("Pôle"));

$sheet->getColumnDimension('A')->setWidth(8);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(30);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(10);
$sheet->getColumnDimension('H')->setWidth(25);
$sheet->getColumnDimension('I')->setWidth(12);
$sheet->getColumnDimension('J')->setWidth(12);
$sheet->getColumnDimension('K')->setWidth(20);
$sheet->getColumnDimension('L')->setWidth(20);
$sheet->getColumnDimension('M')->setWidth(20);


$sheet->getStyle('A1:M1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:M1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$req="SELECT sp_dossier.MSN,sp_dossier.Reference,sp_ficheintervention.Id_Dossier,sp_dossier.TAI_RestantACP, ";
$req.="sp_dossier.Titre,sp_ficheintervention.NumFI,sp_ficheintervention.Commentaire, ";
$req.="(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS RetourPROD,";
$req.="(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=sp_ficheintervention.Id_Pole) AS Pole,";
$req.="(SELECT SUM(TempsPasse) FROM sp_fi_travaileffectue WHERE sp_fi_travaileffectue.Id_FI=sp_ficheintervention.Id) AS TempsPasse, ";
$req.="sp_ficheintervention.Id_StatutPROD,sp_ficheintervention.DateIntervention,sp_ficheintervention.TravailRealise,sp_ficheintervention.CommentairePROD,sp_ficheintervention.Vacation ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
$req.="WHERE ";
if($_SESSION['Extract_MSN2']<>""){
	$tab = explode(";",$_SESSION['Extract_MSN2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_dossier.MSN=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Extract_Zone2']<>""){
	$tab = explode(";",$_SESSION['Extract_Zone2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_dossier.Id_ZoneDeTravail=".substr($valeur,1)." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Extract_Pole2']<>""){
	$tab = explode(";",$_SESSION['Extract_Pole2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_ficheintervention.Id_Pole=".substr($valeur,1)." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Extract_Vacation2']<>""){
	$tab = explode(";",$_SESSION['Extract_Vacation2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_ficheintervention.Vacation='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Extract_Urgence2']<>""){
	$tab = explode(";",$_SESSION['Extract_Urgence2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_dossier.Id_Urgence=".substr($valeur,1)." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Extract_Statut2']<>""){
	$tab = explode(";",$_SESSION['Extract_Statut2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			if($valeur=="(vide)"){$req.="sp_ficheintervention.Id_StatutPROD='' OR sp_ficheintervention.Id_StatutQUALITE='' OR ";}
			elseif($valeur=="TFS" || $valeur=="QARJ"){$req.="sp_ficheintervention.Id_StatutPROD='".$valeur."' OR ";}
			elseif($valeur=="TVS" || $valeur=="CERT"){$req.="sp_ficheintervention.Id_StatutQUALITE='".$valeur."' OR ";}
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Extract_SansDate2']=="oui"){
	$req.=" ( ";
	$req.="sp_ficheintervention.DateIntervention <= '0001-01-01' OR ";
}
if($_SESSION['Extract_Du2']<>"" || $_SESSION['Extract_Au2']<>""){
	$req.=" ( ";
	if($_SESSION['Extract_Du2']<>""){
		$req.="sp_ficheintervention.DateIntervention >= '". TrsfDate_($_SESSION['Extract_Du2'])."' ";
		$req.=" AND ";
	}
	if($_SESSION['Extract_Au2']<>""){
		$req.="sp_ficheintervention.DateIntervention <= '". TrsfDate_($_SESSION['Extract_Au2'])."' ";
		$req.=" ";
	}
	if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
	$req.=" ) ";
}
if(substr($req,strlen($req)-3)== "OR "){$req=substr($req,0,-3);}
if($_SESSION['Extract_SansDate2']=="oui"){
	$req.=" ) ";
}
if($_SESSION['Extract_SansDate2']=="oui" || $_SESSION['Extract_Du2']<>"" || $_SESSION['Extract_Au2']<>""){
	$req.=" AND ";
}
if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}

$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$ligne=2;
if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['MSN']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['NumFI']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['TempsPasse']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['CommentairePROD']));
		$sheet->getStyle('E'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('F'.$ligne,utf8_encode($row['Titre']));
		$sheet->getStyle('F'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('G'.$ligne,utf8_encode($row['TAI_RestantACP']));
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['TravailRealise']));
		$sheet->getStyle('H'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('I'.$ligne,utf8_encode($row['DateIntervention']));
		$sheet->setCellValue('J'.$ligne,utf8_encode($row['Id_StatutPROD']));
		$sheet->setCellValue('K'.$ligne,utf8_encode($row['RetourPROD']));
		$sheet->setCellValue('L'.$ligne,utf8_encode($row['Vacation']));
		$sheet->setCellValue('M'.$ligne,utf8_encode($row['Pole']));
		
		$sheet->getStyle('A'.$ligne.':M'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':M'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':M'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_PROD.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_PROD.xlsx';
$writer->save($chemin);
readfile($chemin);

?>
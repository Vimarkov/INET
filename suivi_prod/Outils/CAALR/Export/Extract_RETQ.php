<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

function TrsfDate_($Date)
{
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
function AfficheDateFR($Date)
{
	if($Date==Null || $Date=='' || $Date<='0001-01-01'){$dateReq="";}
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
			$tabDateTransfert = explode('-', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateReq = date("d/m/Y", $timestampTransfert);
		}
	}
	
	return $dateReq;
}

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Extract');

$sheet->setCellValue('A1',utf8_encode("N� point folio"));
$sheet->setCellValue('B1',utf8_encode("N� OF/OT/Para"));
$sheet->setCellValue('C1',utf8_encode("N� NC"));
$sheet->setCellValue('D1',utf8_encode("N� AM"));
$sheet->setCellValue('E1',utf8_encode("Date du RETQ"));
$sheet->setCellValue('F1',utf8_encode("Date intervention PROD"));
$sheet->setCellValue('G1',utf8_encode("Temps pass�"));
$sheet->setCellValue('H1',utf8_encode("Compagnons"));
$sheet->setCellValue('I1',utf8_encode("Qualiticiens"));
$sheet->setCellValue('J1',utf8_encode("Retour qualit�"));
$sheet->setCellValue('K1',utf8_encode("Commentaire qualit�"));

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(30);
$sheet->getColumnDimension('I')->setWidth(30);
$sheet->getColumnDimension('J')->setWidth(20);
$sheet->getColumnDimension('K')->setWidth(20);


$sheet->getStyle('A1:K1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:K1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$req="SELECT sp_olwficheintervention.Id,sp_olwficheintervention.NumFI,sp_olwdossier.Reference,sp_olwdossier.ReferenceAM,sp_olwdossier.ReferenceNC,sp_olwdossier.ReferencePF,";
$req.="sp_olwficheintervention.DateIntervention,sp_olwficheintervention.DateInterventionQ,sp_olwficheintervention.CommentaireQUALITE, ";
$req.="(SELECT Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourQUALITE) AS RetourQualite, ";
$req.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwficheintervention.Id_QUALITE) AS Controleur ";
$req.="FROM sp_olwficheintervention LEFT JOIN sp_olwdossier ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id ";
$req.="WHERE sp_olwdossier.Id_Prestation=418 AND sp_olwdossier.Id_Statut='TERC' AND (sp_olwficheintervention.Id_StatutQUALITE='RETQ PREPA' OR sp_olwficheintervention.Id_StatutQUALITE='RETQ PROD') ";
if(TrsfDate_($_GET['du'])>"0001-01-01"){
	$req.=" AND sp_olwficheintervention.DateInterventionQ >= '".TrsfDate_($_GET['du'])."' ";
}
if(TrsfDate_($_GET['au'])>"0001-01-01"){
	$req.=" AND sp_olwficheintervention.DateInterventionQ <= '".TrsfDate_($_GET['au'])."' ";
}
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$ligne=2;
if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['ReferencePF']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['ReferenceNC']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['ReferenceAM']));
		$sheet->setCellValue('E'.$ligne,utf8_encode(AfficheDateFR($row['DateInterventionQ'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(AfficheDateFR($row['DateIntervention'])));
		
		$req="SELECT sp_olwfi_travaileffectue.TempsPasse, ";
		$req.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwfi_travaileffectue.Id_Personne) AS Compagnon ";
		$req.="FROM sp_olwfi_travaileffectue ";
		$req.="WHERE sp_olwfi_travaileffectue.Id_FI=".$row['Id']." ";
		$result2=mysqli_query($bdd,$req);
		$nbResulta2=mysqli_num_rows($result2);
		$nb=0;
		$Compagnon="";
		$nb2=1;
		if($nbResulta2>0){
			while($row2=mysqli_fetch_array($result2)){
				$n="\n";
				if($nbResulta2==$nb2){$n="";}
				$nb=$nb+floatval($row2['TempsPasse']);
				$Compagnon=$Compagnon.$row2['Compagnon'].$n;
				$nb2++;
			}
		}

		$sheet->setCellValue('G'.$ligne,utf8_encode($nb));
		$sheet->setCellValue('H'.$ligne,utf8_encode($Compagnon));
		$sheet->getStyle('H'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('I'.$ligne,utf8_encode($row['Controleur']));
		$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($row['RetourQualite'])));
		$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($row['CommentaireQUALITE'])));
	
		$sheet->getStyle('A'.$ligne.':K'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':K'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':K'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_RETQ.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_RETQ.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
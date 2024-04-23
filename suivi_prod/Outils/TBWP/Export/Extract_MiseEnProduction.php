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

$du=$_GET['du'];
$au=$_GET['au'];

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Extract');

$sheet->setCellValue('A1',utf8_encode("Pôle"));
$sheet->setCellValue('B1',utf8_encode("MSN"));
$sheet->setCellValue('C1',utf8_encode("Reference"));
$sheet->setCellValue('D1',utf8_encode("TAI restant"));
$sheet->setCellValue('E1',utf8_encode("CreateurDossier"));
$sheet->setCellValue('F1',utf8_encode("Date mise en PROD"));
$sheet->setCellValue('G1',utf8_encode("Priorite"));
$sheet->setCellValue('H1',utf8_encode("Titre"));
$sheet->setCellValue('I1',utf8_encode("Vacation"));
$sheet->setCellValue('J1',utf8_encode("Date intervention"));
$sheet->setCellValue('K1',utf8_encode("Travail réalisé"));
$sheet->setCellValue('L1',utf8_encode("Statut PROD"));
$sheet->setCellValue('M1',utf8_encode("Retour PROD"));
$sheet->setCellValue('N1',utf8_encode("Statut QUALITE"));
$sheet->setCellValue('O1',utf8_encode("Retour QUALITE"));

$sheet->getColumnDimension('A')->setWidth(10);
$sheet->getColumnDimension('B')->setWidth(10);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(15);
$sheet->getColumnDimension('H')->setWidth(25);
$sheet->getColumnDimension('I')->setWidth(10);
$sheet->getColumnDimension('J')->setWidth(15);
$sheet->getColumnDimension('K')->setWidth(30);
$sheet->getColumnDimension('L')->setWidth(15);
$sheet->getColumnDimension('M')->setWidth(20);
$sheet->getColumnDimension('N')->setWidth(15);
$sheet->getColumnDimension('O')->setWidth(20);

$sheet->getStyle('A1:O1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:O1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$req="SELECT ";
$req.="(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=(SELECT sp_ficheintervention.Id_Pole FROM sp_ficheintervention WHERE sp_ficheintervention.Id=Tab.Id)) AS Pole, ";
$req.="(SELECT sp_dossier.MSN FROM sp_dossier WHERE sp_dossier.Id=Tab.Id_Dossier) AS MSN, ";
$req.="(SELECT sp_dossier.Reference FROM sp_dossier WHERE sp_dossier.Id=Tab.Id_Dossier) AS Reference, ";
$req.="(SELECT sp_dossier.TAI_RestantACP FROM sp_dossier WHERE sp_dossier.Id=Tab.Id_Dossier) AS TAI_RestantACP, ";
$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=(SELECT sp_dossier.Id_Personne FROM sp_dossier WHERE sp_dossier.Id=Tab.Id_Dossier)) AS CreateurDossier, "; 
$req.="(SELECT sp_dossier.DateCreation FROM sp_dossier WHERE sp_dossier.Id=Tab.Id_Dossier) AS DateMiseEnProduction, ";
$req.="(SELECT sp_dossier.Priorite FROM sp_dossier WHERE sp_dossier.Id=Tab.Id_Dossier) AS Priorite, ";
$req.="(SELECT sp_dossier.Titre FROM sp_dossier WHERE sp_dossier.Id=Tab.Id_Dossier) AS Titre, ";
$req.="(SELECT sp_ficheintervention.Vacation FROM sp_ficheintervention WHERE sp_ficheintervention.Id=Tab.Id) AS Vacation, ";  
$req.="(SELECT sp_ficheintervention.DateIntervention FROM sp_ficheintervention WHERE sp_ficheintervention.Id=Tab.Id) AS DateIntervention, "; 
$req.="(SELECT sp_ficheintervention.TravailRealise FROM sp_ficheintervention WHERE sp_ficheintervention.Id=Tab.Id) AS TravailRealise, "; 
$req.="(SELECT sp_ficheintervention.Id_StatutPROD FROM sp_ficheintervention WHERE sp_ficheintervention.Id=Tab.Id) AS StatutPROD, "; 
$req.="(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=(SELECT sp_ficheintervention.Id_RetourPROD FROM sp_ficheintervention WHERE sp_ficheintervention.Id=Tab.Id)) AS RetourPROD, "; 
$req.="(SELECT sp_ficheintervention.Id_StatutQUALITE FROM sp_ficheintervention WHERE sp_ficheintervention.Id=Tab.Id) AS StatutQUALITE, ";
$req.="(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=(SELECT sp_ficheintervention.Id_RetourQUALITE FROM sp_ficheintervention WHERE sp_ficheintervention.Id=Tab.Id)) AS RetourQUALITE  ";
$req.="FROM ";
$req.="(SELECT sp_dossier.Id AS Id_Dossier, min(sp_ficheintervention.Id) AS Id ";
$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id  ";
$req.="WHERE ( sp_dossier.DateCreation>= '".$du."' AND sp_dossier.DateCreation<= '".$au."' )  ";
$req.="GROUP BY sp_dossier.Id) AS Tab ";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$ligne=2;
if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Pole']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['MSN']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['TAI_RestantACP']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['CreateurDossier']));
		$sheet->setCellValue('F'.$ligne,utf8_encode($row['DateMiseEnProduction']));
		$Priorite="";
		if($row['Priorite']=="1"){$Priorite="Low";}
		elseif($row['Priorite']=="2"){$Priorite="Medium";}
		else{$Priorite="High";}
		$sheet->setCellValue('G'.$ligne,utf8_encode($Priorite));
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['Titre']));
		$sheet->getStyle('H'.$ligne)->getAlignment()->setWrapText(true);
		$Vacation="";
		if($row['Vacation']=="J"){$Vacation="Jour";}
		elseif($row['Vacation']=="S"){$Vacation="Soir";}
		if($row['Vacation']=="N"){$Vacation="Nuit";}
		if($row['Vacation']=="VSD"){$Vacation="VSD";}
		$sheet->setCellValue('I'.$ligne,utf8_encode($Vacation));
		$sheet->setCellValue('J'.$ligne,utf8_encode($row['DateIntervention']));
		$sheet->setCellValue('K'.$ligne,utf8_encode($row['TravailRealise']));
		$sheet->getStyle('K'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('L'.$ligne,utf8_encode($row['StatutPROD']));
		$sheet->setCellValue('M'.$ligne,utf8_encode($row['RetourPROD']));
		$sheet->setCellValue('N'.$ligne,utf8_encode($row['StatutQUALITE']));
		$sheet->setCellValue('O'.$ligne,utf8_encode($row['StatutQUALITE']));
		
		$sheet->getStyle('A'.$ligne.':O'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':O'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':O'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_MiseEnPROD.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_MiseEnPROD.xlsx';
$writer->save($chemin);
readfile($chemin);

?>
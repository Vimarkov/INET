<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("../../Fonctions.php");

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Extract');

$sheet->setCellValue('A1',utf8_encode("N° dossier"));
$sheet->setCellValue('B1',utf8_encode("TAI"));
$sheet->setCellValue('C1',utf8_encode("CT ayant lancé l'IC"));
$sheet->setCellValue('D1',utf8_encode("Date inter. PROD"));
$sheet->setCellValue('E1',utf8_encode("Vacation PROD"));
$sheet->setCellValue('F1',utf8_encode("Statut PROD"));
$sheet->setCellValue('G1',utf8_encode("Date TERA"));
$sheet->setCellValue('H1',utf8_encode("Retour PROD"));
$sheet->setCellValue('I1',utf8_encode("Commentaire PROD"));
$sheet->setCellValue('J1',utf8_encode("Date inter. QUALITE"));
$sheet->setCellValue('K1',utf8_encode("Vacation QUALITE"));
$sheet->setCellValue('L1',utf8_encode("Statut QUALITE"));
$sheet->setCellValue('M1',utf8_encode("Date TERC"));
$sheet->setCellValue('N1',utf8_encode("Retour QUALITE"));
$sheet->setCellValue('O1',utf8_encode("Commentaire QUALITE"));

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(15);
$sheet->getColumnDimension('I')->setWidth(40);
$sheet->getColumnDimension('J')->setWidth(15);
$sheet->getColumnDimension('M')->setWidth(15);
$sheet->getColumnDimension('O')->setWidth(40);

$sheet->getStyle('A1:O1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:O1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$req="SELECT sp_olwdossier.Reference,sp_olwficheintervention.DateIntervention,TAI_RestantACP,DateIntervention,
Vacation,CommentairePROD,Id_StatutPROD,Id_RetourPROD,DateTERA,
(SELECT sp_olwretour.Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourPROD) AS RetourPROD,
(SELECT sp_olwretour.Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourQUALITE) AS RetourQUALITE,
DateInterventionQ,VacationQ,Id_StatutQUALITE,Id_RetourQUALITE,DateTERC,CommentaireQUALITE,
(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwficheintervention.Id_Createur) AS CT 
FROM sp_olwficheintervention 
LEFT JOIN sp_olwdossier 
ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id 
WHERE sp_olwdossier.Id_Prestation=-16 
AND (
(sp_olwficheintervention.DateIntervention >= '".TrsfDate_($_GET['du'])."' ";

if(TrsfDate_($_GET['au'])>"0001-01-01"){
	$req.=" AND sp_olwficheintervention.DateIntervention <= '".TrsfDate_($_GET['au'])."' ";
}
$req.=") OR (sp_olwficheintervention.DateInterventionQ >= '".TrsfDate_($_GET['du'])."' ";
if(TrsfDate_($_GET['au'])>"0001-01-01"){
	$req.=" AND sp_olwficheintervention.DateInterventionQ <= '".TrsfDate_($_GET['au'])."' ";
}
$req.="
)
)";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
$ligne=2;
if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['TAI_RestantACP']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['CT']));
		$sheet->setCellValue('D'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateIntervention'])));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['Vacation']));
		$sheet->setCellValue('F'.$ligne,utf8_encode($row['Id_StatutPROD']));
		$sheet->setCellValue('G'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateTERA'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['RetourPROD']));
		$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($row['CommentairePROD'])));
		$sheet->setCellValue('J'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateInterventionQ'])));
		$sheet->setCellValue('K'.$ligne,utf8_encode($row['VacationQ']));
		$sheet->setCellValue('L'.$ligne,utf8_encode($row['Id_StatutQUALITE']));
		$sheet->setCellValue('M'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateTERC'])));
		$sheet->setCellValue('N'.$ligne,utf8_encode($row['RetourQUALITE']));
		$sheet->setCellValue('O'.$ligne,utf8_encode(stripslashes($row['CommentaireQUALITE'])));
	
		$sheet->getStyle('A'.$ligne.':O'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':O'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':O'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_CompteRendu.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_CompteRendu.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
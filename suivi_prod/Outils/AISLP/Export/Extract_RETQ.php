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
$sheet->setCellValue('B1',utf8_encode("Date du retour"));
$sheet->setCellValue('C1',utf8_encode("Date intervention PROD"));
$sheet->setCellValue('D1',utf8_encode("Compagnons"));
$sheet->setCellValue('E1',utf8_encode("Qualiticiens"));
$sheet->setCellValue('F1',utf8_encode("Statut Qualité"));
$sheet->setCellValue('G1',utf8_encode("Retour qualité"));
$sheet->setCellValue('H1',utf8_encode("Commentaire qualité"));

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(20);


$sheet->getStyle('A1:H1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:H1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$req="SELECT sp_olwficheintervention.Id,sp_olwficheintervention.NumFI,sp_olwdossier.Reference,Id_StatutQUALITE, ";
$req.="sp_olwficheintervention.DateIntervention,sp_olwficheintervention.DateInterventionQ,sp_olwficheintervention.CommentaireQUALITE, ";
$req.="(SELECT Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourQUALITE) AS RetourQualite, ";
$req.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwficheintervention.Id_QUALITE) AS Controleur ";
$req.="FROM sp_olwficheintervention LEFT JOIN sp_olwdossier ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id ";
$req.="WHERE sp_olwdossier.Id_Prestation=16 AND sp_olwdossier.Id_Statut='TERC' AND (sp_olwficheintervention.Id_StatutQUALITE='TVS' or sp_olwficheintervention.Id_StatutQUALITE='Retour Qualité') ";
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
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('B'.$ligne,utf8_encode(AfficheJJ_MM_AAAA($row['DateInterventionQ'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode(AfficheJJ_MM_AAAA($row['DateInterventionQ'])));
		
		$req="SELECT DISTINCT ";
		$req.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwfi_travaileffectue.Id_Personne) AS Compagnon ";
		$req.="FROM sp_olwfi_travaileffectue ";
		$req.="WHERE sp_olwfi_travaileffectue.Id_FI=".$row['Id']." ";
		$result2=mysqli_query($bdd,$req);
		$nbResulta2=mysqli_num_rows($result2);
		$Compagnon="";
		$nb2=1;
		if($nbResulta2>0){
			while($row2=mysqli_fetch_array($result2)){
				$n="\n";
				if($nbResulta2==$nb2){$n="";}
				$Compagnon=$Compagnon.$row2['Compagnon'].$n;
				$nb2++;
			}
		}

		$sheet->setCellValue('D'.$ligne,utf8_encode($Compagnon));
		$sheet->getStyle('D'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['Controleur']));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row['Id_StatutQUALITE'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($row['RetourQualite'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($row['CommentaireQUALITE'])));
	
		$sheet->getStyle('A'.$ligne.':H'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':H'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':H'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
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
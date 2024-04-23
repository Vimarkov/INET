<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("../../Fonctions.php");

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$Excel = $workbook->load('D-0833-047.xlsx');
$sheet = $Excel->getActiveSheet();

$sheet->setCellValue('J4',utf8_encode("PLATEFORME/SITE : TLS / TBWP"));

$ecme=$_GET['ecme'];
$du=$_GET['du'];
$au=$_GET['au'];
$req="SELECT DISTINCT sp_fi_ecme.Id_FI,";
$req.="(SELECT sp_dossier.MSN FROM sp_dossier WHERE sp_dossier.Id=sp_ficheintervention.Id_Dossier) AS MSN,";
$req.="(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=sp_ficheintervention.Id_Pole) AS Pole,";
$req.="(SELECT sp_dossier.Reference FROM sp_dossier WHERE sp_dossier.Id=sp_ficheintervention.Id_Dossier) AS Reference,";
$req.="(SELECT sp_dossier.Titre FROM sp_dossier WHERE sp_dossier.Id=sp_ficheintervention.Id_Dossier) AS Titre,";
$req.="(SELECT sp_zonedetravail.Libelle FROM sp_zonedetravail WHERE sp_zonedetravail.Id=(SELECT sp_dossier.Id_ZoneDeTravail FROM sp_dossier WHERE sp_dossier.Id=sp_ficheintervention.Id_Dossier)) AS Zone, ";
$req.="sp_ficheintervention.TravailRealise, sp_ficheintervention.DateIntervention ";
$req.="FROM sp_fi_ecme LEFT JOIN sp_ficheintervention ON sp_ficheintervention.Id=sp_fi_ecme.Id_FI ";
$req.="WHERE sp_fi_ecme.ECME='".$ecme."' AND sp_ficheintervention.DateIntervention>='".$du."' ";
if($au>'0001-01-01'){
	$req.=" AND sp_ficheintervention.DateIntervention<='".$au."' ";
}
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$sheet->setCellValue('F8',utf8_encode($ecme));

$ligne=11;
if ($nbResulta>0){
	if ($nbResulta>26){
		$nb = $nbResulta-26;
		$sheet->insertNewRowBefore(36, $nb);
	}
	while($row=mysqli_fetch_array($result)){
		$sheet->setCellValue('A'.$ligne,utf8_encode('A350 '.$row['Pole']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['DateIntervention']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['MSN']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['Zone']));
		$sheet->setCellValue('F'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['Titre'].' '.$row['TravailRealise'])); 
		$sheet->getStyle('H'.$ligne)->getAlignment()->setWrapText(true); 
		
		$req="SELECT ";
		$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_fi_travaileffectue.Id_Personne) AS Compagnon ";
		$req.="FROM sp_fi_travaileffectue LEFT JOIN sp_ficheintervention ON sp_ficheintervention.Id=sp_fi_travaileffectue.Id_FI ";
		$req.="WHERE sp_ficheintervention.Id=".$row['Id_FI'];
		$resultCompagnon=mysqli_query($bdd,$req);
		$nbCompagnon=mysqli_num_rows($resultCompagnon);
		$Compagnon="";
		if ($nbCompagnon>0){	
			while($rowCompagnon=mysqli_fetch_array($resultCompagnon)){
				$Compagnon.=$rowCompagnon['Compagnon']."  ";
			}
		}
		$sheet->setCellValue('C'.$ligne,utf8_encode($Compagnon));
		$sheet->getStyle('C'.$ligne)->getAlignment()->setWrapText(true); 
		$ligne++;
		
	}
}


//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="D-0833-047.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($Excel, 'Excel2007');

$chemin = '../../../tmp/D-0833-047.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
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

$sheet->setCellValue('J4',utf8_encode("PLATEFORME/SITE : TLS / AEWP"));

$ecme=$_GET['ecme'];
$du=$_GET['du'];
$au=$_GET['au'];
$req="SELECT DISTINCT sp_olwfi_ecme.Id_FI,sp_olwfi_ecme.ProdQualite,
	(SELECT sp_olwdossier.MSN FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier) AS MSN,
	(SELECT sp_olwdossier.Reference FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier) AS Reference,
	(SELECT sp_olwdossier.Titre FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier) AS Titre,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwficheintervention.Id_QUALITE) AS Controleur,
	sp_olwficheintervention.DateTERA,sp_olwficheintervention.DateTERC
	FROM sp_olwfi_ecme 
	LEFT JOIN sp_olwficheintervention 
	ON sp_olwficheintervention.Id=sp_olwfi_ecme.Id_FI 
	WHERE (SELECT Id_Prestation FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier)=1792 
	AND IF(sp_olwfi_ecme.Id_ECME>0,(SELECT Libelle FROM sp_atrecme WHERE sp_atrecme.Id=sp_olwfi_ecme.Id_ECME),sp_olwfi_ecme.ECME)='".$ecme."' 
	AND (sp_olwficheintervention.DateTERA>='".$du."' OR  sp_olwficheintervention.DateTERC>='".$du."') ";
if($au>'0001-01-01'){
	$req.=" AND (sp_olwficheintervention.DateTERA<='".$au."' OR  sp_olwficheintervention.DateTERC<='".$au."') ";
}
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$sheet->setCellValue('C8',utf8_encode($ecme));

$ligne=11;
if ($nbResulta>0){
	if ($nbResulta>26){
		$nb = $nbResulta-26;
		$sheet->insertNewRowBefore(36, $nb);
	}
	while($row=mysqli_fetch_array($result)){
		$sheet->setCellValue('A'.$ligne,utf8_encode('AEWP'));
		$sheet->setCellValue('B'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateTERA'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['MSN']));

		$sheet->setCellValue('F'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['Titre'])); 
		$sheet->getStyle('H'.$ligne)->getAlignment()->setWrapText(true); 
		
		$personne="";
		if($row['ProdQualite']==0){
			$req="SELECT 
				(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwfi_compagnon.Id_Personne) AS Compagnon ";
			$req.="FROM sp_olwfi_compagnon LEFT JOIN sp_olwfi ON sp_olwficheintervention.Id=sp_olwfi_compagnon.Id_FI ";
			$req.="WHERE sp_olwficheintervention.Id_Prestation=463 AND sp_olwficheintervention.Id=".$row['Id_FI'];
			$resultCompagnon=mysqli_query($bdd,$req);
			$nbCompagnon=mysqli_num_rows($resultCompagnon);
			if ($nbCompagnon>0){	
				while($rowCompagnon=mysqli_fetch_array($resultCompagnon)){
					$personne.=$rowCompagnon['Compagnon']."  ";
				}
			}
		}
		else{
			$personne.=$row['Controleur']."  ";
		}
		$sheet->setCellValue('C'.$ligne,utf8_encode($personne));
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
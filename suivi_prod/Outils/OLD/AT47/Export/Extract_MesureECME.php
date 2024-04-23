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

$sheet->setCellValue('J4',utf8_encode("PLATEFORME/SITE : TLS / AT47"));

$ecme=$_GET['ecme'];
$du=$_GET['du'];
$au=$_GET['au'];
$req="SELECT DISTINCT sp_atrot_ecme.Id_OT,sp_atrot_ecme.ProdQualite,
	sp_atrot.MSN,sp_atrot.OrdreMontage AS Reference,sp_atrot.Designation AS Titre,
	sp_atrot.DateTERA,sp_atrot.DateTERC
	FROM sp_atrot_ecme LEFT JOIN sp_atrot ON sp_atrot.Id=sp_atrot_ecme.Id_OT 
	WHERE sp_atrot.Id_Prestation=262
	AND IF(sp_atrot_ecme.Id_ECME>0,(SELECT Libelle FROM sp_atrecme WHERE sp_atrecme.Id=sp_atrot_ecme.Id_ECME),sp_atrot_ecme.Reference)='".$ecme."' 
	AND (sp_atrot.DateTERA>='".$du."' OR  sp_atrot.DateTERC>='".$du."') ";
if($au>'0001-01-01'){
	$req.=" AND (sp_atrot.DateTERA<='".$au."' OR  sp_atrot.DateTERC<='".$au."') ";
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
		$sheet->setCellValue('A'.$ligne,utf8_encode('AT47'));
		$sheet->setCellValue('B'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateTERA'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['MSN']));

		$sheet->setCellValue('F'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['Titre'])); 
		$sheet->getStyle('H'.$ligne)->getAlignment()->setWrapText(true); 
		
		$personne="";
		if($row['ProdQualite']==0){
			$req="SELECT 
				(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_atrot_compagnon.Id_Personne) AS Compagnon ";
			$req.="FROM sp_atrot_compagnon LEFT JOIN sp_atrot ON sp_atrot.Id=sp_atrot_compagnon.Id_OT ";
			$req.="WHERE sp_atrot.Id_Prestation=262 AND sp_atrot.Id=".$row['Id_OT'];
			$resultCompagnon=mysqli_query($bdd,$req);
			$nbCompagnon=mysqli_num_rows($resultCompagnon);
			if ($nbCompagnon>0){	
				while($rowCompagnon=mysqli_fetch_array($resultCompagnon)){
					$personne.=$rowCompagnon['Compagnon']."  ";
				}
			}
		}
		else{
			$req="SELECT 
				(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_atrot_controleur.Id_Personne) AS Controleur ";
			$req.="FROM sp_atrot_controleur LEFT JOIN sp_atrot ON sp_atrot.Id=sp_atrot_controleur.Id_OT ";
			$req.="WHERE sp_atrot.Id_Prestation=262 AND sp_atrot.Id=".$row['Id_OT'];
			$resultControleur=mysqli_query($bdd,$req);
			$nbConroleur=mysqli_num_rows($resultControleur);
			if ($nbConroleur>0){	
				while($rowControleur=mysqli_fetch_array($resultControleur)){
					$personne.=$rowControleur['Controleur']."  ";
				}
			}
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
<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require_once("Globales_Fonctions.php");
require_once("../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

if($LangueAffichage=="FR"){
	$sheet->setTitle(utf8_encode('Formations'));
	$sheet->setCellValue('A1',utf8_encode("Référence"));
	$sheet->setCellValue('B1',utf8_encode("Type"));
	$sheet->setCellValue('C1',utf8_encode("Recyclage différent"));
	$sheet->setCellValue('D1',utf8_encode("Intitulé"));
	$sheet->setCellValue('E1',utf8_encode("Qualifications aquises"));
	$sheet->setCellValue('F1',utf8_encode("Mis à jour le"));
	$sheet->setCellValue('G1',utf8_encode("Mis à jour par"));
}
else{
	$sheet->setTitle(utf8_encode('Training'));
	$sheet->setCellValue('A1',utf8_encode("Reference"));
	$sheet->setCellValue('B1',utf8_encode("Type"));
	$sheet->setCellValue('C1',utf8_encode("Different Recycling"));
	$sheet->setCellValue('D1',utf8_encode("Entitled"));
	$sheet->setCellValue('E1',utf8_encode("Qualifications acquired"));
	$sheet->setCellValue('F1',utf8_encode("Updated"));
	$sheet->setCellValue('G1',utf8_encode("Updated by"));
}
$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(10);
$sheet->getColumnDimension('D')->setWidth(80);
$sheet->getColumnDimension('E')->setWidth(60);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(20);


$sheet->getStyle('A1:G1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:G1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:G1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:G1')->getFont()->setBold(true);
$sheet->getStyle('A1:G1')->getFont()->getColor()->setRGB('1f49a6');

//FORMATIONS SMQ
$requeteFormation="SELECT Id, Id_Plateforme, Reference, Id_TypeFormation, ";
$requeteFormation.="(SELECT Libelle FROM form_typeformation WHERE Id=Id_TypeFormation) AS TypeFormation, ";
$requeteFormation.="Tuteur, Recyclage, Id_Personne_MAJ, ";
$requeteFormation.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne_MAJ) as Personne_MAJ, Date_MAJ ";
$requeteFormation.="FROM form_formation WHERE Suppr=0 AND Id_Plateforme=0 ";
$requeteFormation.="ORDER BY Reference ASC";
$resultFormation=mysqli_query($bdd,$requeteFormation);
$nbFormation=mysqli_num_rows($resultFormation);

//DOCUMENTS
$requeteDocuments="SELECT Id,Id_Formation,(SELECT Reference FROM form_document WHERE Id=Id_Document) AS Document FROM form_formation_document WHERE Suppr=0 ORDER BY Document ASC";
$resultDocuments=mysqli_query($bdd,$requeteDocuments);
$nbDocuments=mysqli_num_rows($resultDocuments);

//QUALIFICATIONS
$requeteQualifications="SELECT form_formation_qualification.Id,form_formation_qualification.Id_Formation,new_competences_categorie_qualification_maitre.Libelle AS QualifMaitre,new_competences_categorie_qualification.Libelle AS CategorieQualif,new_competences_qualification.libelle AS Qualif ";
$requeteQualifications.=" FROM form_formation_qualification, new_competences_qualification, new_competences_categorie_qualification, new_competences_categorie_qualification_maitre";
$requeteQualifications.=" WHERE ";
$requeteQualifications.=" form_formation_qualification.Id_Qualification=new_competences_qualification.Id";
$requeteQualifications.=" AND new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id";
$requeteQualifications.=" AND new_competences_categorie_qualification.Id_Categorie_Maitre=new_competences_categorie_qualification_maitre.Id";
$requeteQualifications.=" AND form_formation_qualification.Suppr=0 AND form_formation_qualification.Masquer=0 ";
$requeteQualifications.=" ORDER BY new_competences_categorie_qualification_maitre.Libelle ASC, new_competences_categorie_qualification.Libelle ASC,new_competences_qualification.Libelle ASC";
$resultQualifications=mysqli_query($bdd,$requeteQualifications);
$nbQualifs=mysqli_num_rows($resultQualifications);

//Liste des QCM 
$req="SELECT form_formation_qualification_qcm.Id_Formation_Qualification, form_formation_qualification_qcm.Id_QCM, 
	form_qcm.Code,form_formation_qualification_qcm.Id_Langue, ";
$req.="(SELECT Libelle FROM form_langue WHERE form_langue.Id=form_formation_qualification_qcm.Id_Langue) AS Langue ";
$req.="FROM form_formation_qualification_qcm LEFT JOIN form_qcm ";
$req.="ON form_formation_qualification_qcm.Id_QCM=form_qcm.Id ";
$req.="WHERE form_formation_qualification_qcm.Suppr=0 ORDER BY Code";
$resultQCM=mysqli_query($bdd,$req);
$nbResultaQCM=mysqli_num_rows($resultQCM);

$requeteInfos="SELECT Id,Id_Formation,Id_Langue,(SELECT Libelle FROM form_langue WHERE Id=Id_Langue) AS Langue,Libelle,Description,LibelleRecyclage,DescriptionRecyclage FROM form_formation_langue_infos WHERE Suppr=0 ORDER BY Langue";
$resultInfos=mysqli_query($bdd,$requeteInfos);
$nbInfos=mysqli_num_rows($resultInfos);

$i=2;
if ($nbFormation>0){
	while($row=mysqli_fetch_array($resultFormation)){
		$Documents="";
		if($nbDocuments>0){
			mysqli_data_seek($resultDocuments,0);
			while($rowDoc=mysqli_fetch_array($resultDocuments)){
				if($rowDoc['Id_Formation']==$row['Id']){
					if($Documents<>""){$Documents="\n";}
					$Documents.=stripslashes($rowDoc['Document']);
				}
			}
		}
		$Infos="";
		if($nbInfos>0){
			mysqli_data_seek($resultInfos,0);
			while($rowInfo=mysqli_fetch_array($resultInfos)){
				if($rowInfo['Id_Formation']==$row['Id']){
					if($Infos<>""){$Infos.="\n";}
					$Infos.=stripslashes($rowInfo['Libelle'])."(".stripslashes($rowInfo['Langue']).")";
				}
			}
		}
		
		$qualifications="";
		if($nbQualifs>0){
			mysqli_data_seek($resultQualifications,0);
			while($rowQualif=mysqli_fetch_array($resultQualifications)){
				if($rowQualif['Id_Formation']==$row['Id']){
					$qcm="";
					if($nbResultaQCM>0){
						mysqli_data_seek($resultQCM,0);
						while($rowQCM=mysqli_fetch_array($resultQCM)){
							if($rowQCM['Id_Formation_Qualification']==$rowQualif['Id']){
								$qcm.="\n         QCM : ".$rowQCM['Code']." (".$rowQCM['Langue'].")";
							}
						}
					}
					if($qualifications<>""){$qualifications.="\n";}
					$qualifications.="- ".stripslashes($rowQualif['Qualif'])." (".stripslashes($rowQualif['QualifMaitre'])." - ".stripslashes($rowQualif['CategorieQualif']).")".$qcm;
				}
			}
		}
		$btrouve=1;
		if($_GET['motcle']<>""){
			if(stripos($row['Reference'],$_GET['motcle'])===false && stripos($Documents,$_GET['motcle'])===false && stripos($Infos,$_GET['motcle'])===false && stripos($row['TypeFormation'],$_GET['motcle'])===false && stripos($qualifications,$_GET['motcle'])===false){
				$btrouve=0;
			}
			else{
				$btrouve=1;
			}
		}
		if($btrouve==1){
			if($LangueAffichage=="FR"){
				if($row['Recyclage']==1){$recyclage="Oui";}else{$recyclage="Non";};
			}
			else{
				if($row['Recyclage']==1){$recyclage="Yes";}else{$recyclage="No";};
			}
			$sheet->setCellValue('A'.$i,utf8_encode($row['Reference']));
			$sheet->setCellValue('B'.$i,utf8_encode($row['TypeFormation']));
			$sheet->setCellValue('C'.$i,utf8_encode($recyclage));
			$sheet->setCellValue('D'.$i,utf8_encode($Infos));
			$sheet->setCellValue('E'.$i,utf8_encode($qualifications));
			$sheet->setCellValue('F'.$i,utf8_encode(AfficheDateJJ_MM_AAAA($row['Date_MAJ'])));
			$sheet->setCellValue('G'.$i,utf8_encode($row['Personne_MAJ']));
			
			$sheet->getStyle('D'.$i)->getAlignment()->setWrapText(true);
			$sheet->getStyle('E'.$i)->getAlignment()->setWrapText(true);

			$sheet->getStyle('A'.$i.':G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$i.':G'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheet->getStyle('A'.$i.':G'.$i)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));

			$i++;
		}
	}
}			
$sheet->getSheetView()->setZoomScale(80);

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){
	header('Content-Disposition: attachment;filename="Formations_SMQ.xlsx"');
}
else{
	header('Content-Disposition: attachment;filename="Training_QMS.xlsx"');
}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/FormationsSMQ.xlsx';

$writer->save($chemin);
readfile($chemin);
?>
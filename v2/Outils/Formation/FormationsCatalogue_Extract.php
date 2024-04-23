<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require_once("Globales_Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

if($LangueAffichage=="FR"){
	$sheet->setTitle(utf8_encode('Formations'));
	$sheet->setCellValue('A1',utf8_encode("Référence"));
	$sheet->setCellValue('B1',utf8_encode("Type"));
	$sheet->setCellValue('C1',utf8_encode("Intitulé"));
	$sheet->setCellValue('D1',utf8_encode("Organisme"));
	$sheet->setCellValue('E1',utf8_encode("Qualifications aquises"));
	$sheet->setCellValue('F1',utf8_encode("Recyclage"));
	$sheet->setCellValue('G1',utf8_encode("Nb jours"));
	$sheet->setCellValue('H1',utf8_encode("Nb heures"));
	$sheet->setCellValue('I1',utf8_encode("Description"));
}
else{
	$sheet->setTitle(utf8_encode('Trainings'));
	$sheet->setCellValue('A1',utf8_encode("Reference"));
	$sheet->setCellValue('B1',utf8_encode("Type"));
	$sheet->setCellValue('C1',utf8_encode("Entitled"));
	$sheet->setCellValue('D1',utf8_encode("Organism"));
	$sheet->setCellValue('E1',utf8_encode("Qualifications acquired"));
	$sheet->setCellValue('F1',utf8_encode("Recycling"));
	$sheet->setCellValue('G1',utf8_encode("Number of days"));
	$sheet->setCellValue('H1',utf8_encode("Time (hours)"));
	$sheet->setCellValue('I1',utf8_encode("Description"));
}
$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(40);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(70);
$sheet->getColumnDimension('F')->setWidth(10);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(20);
$sheet->getColumnDimension('I')->setWidth(70);


$sheet->getStyle('A1:I1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:I1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:I1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:I1')->getFont()->setBold(true);
$sheet->getStyle('A1:I1')->getFont()->getColor()->setRGB('1f49a6');

//FORMATIONS SMQ + PLATEFORME
$requeteFormation="SELECT Id, Id_Plateforme, Reference, Id_TypeFormation, ";
$requeteFormation.="(SELECT Libelle FROM form_typeformation WHERE Id=Id_TypeFormation) AS TypeFormation, ";
$requeteFormation.="Tuteur, Recyclage, Id_Personne_MAJ, ";
$requeteFormation.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne_MAJ) as Personne_MAJ, Date_MAJ ";
$requeteFormation.="FROM form_formation WHERE Suppr=0 AND (Id_Plateforme=0 OR Id_Plateforme=".$_GET['Id_Plateforme'].")  ";
if($_GET['type']<>"0" && $_GET['type']<>""){
	$requeteFormation.="AND Id_TypeFormation=".$_GET['type']." ";
}
$requeteFormation.="ORDER BY Reference ASC";
$resultFormation=mysqli_query($bdd,$requeteFormation);
$nbFormation=mysqli_num_rows($resultFormation);

//QUALIFICATIONS
$requeteQualifications="SELECT form_formation_qualification.Id,form_formation_qualification.Id_Formation,new_competences_categorie_qualification_maitre.Libelle AS QualifMaitre,new_competences_categorie_qualification.Libelle AS CategorieQualif,new_competences_qualification.libelle AS Qualif,new_competences_qualification.Duree_Validite ";
$requeteQualifications.=" FROM form_formation_qualification, new_competences_qualification, new_competences_categorie_qualification, new_competences_categorie_qualification_maitre";
$requeteQualifications.=" WHERE ";
$requeteQualifications.=" form_formation_qualification.Id_Qualification=new_competences_qualification.Id";
$requeteQualifications.=" AND new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id";
$requeteQualifications.=" AND new_competences_categorie_qualification.Id_Categorie_Maitre=new_competences_categorie_qualification_maitre.Id";
$requeteQualifications.=" AND form_formation_qualification.Suppr=0 AND form_formation_qualification.Masquer=0 ";
$requeteQualifications.=" ORDER BY new_competences_categorie_qualification_maitre.Libelle ASC, new_competences_categorie_qualification.Libelle ASC,new_competences_qualification.Libelle ASC";
$resultQualifications=mysqli_query($bdd,$requeteQualifications);
$nbQualifs=mysqli_num_rows($resultQualifications);

$requeteInfos="SELECT Id,Id_Formation,Id_Langue,(SELECT Libelle FROM form_langue WHERE Id=Id_Langue) AS Langue,Libelle,Description,LibelleRecyclage,DescriptionRecyclage,Fichier,FichierRecyclage FROM form_formation_langue_infos WHERE Suppr=0 ORDER BY Langue";
$resultInfos=mysqli_query($bdd,$requeteInfos);
$nbInfos=mysqli_num_rows($resultInfos);

//PARAMETRE PLATEFORME
$requeteParam="SELECT Id,Id_Formation,Id_Langue,Duree,DureeRecyclage,NbJour,NbJourRecyclage, ";
$requeteParam.="(SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS Organisme ";
$requeteParam.= "FROM form_formation_plateforme_parametres WHERE Id_Plateforme=".$_GET['Id_Plateforme']." ";
$resultParam=mysqli_query($bdd,$requeteParam);
$nbParam=mysqli_num_rows($resultParam);
if ($nbFormation>0){
	$i=2;
	while($row=mysqli_fetch_array($resultFormation)){
		$Id_Langue=0;
		$Duree="";
		$DureeR="";
		$NbJour="";
		$NbJourR="";
		$Organisme="";
		if($nbParam>0){
			mysqli_data_seek($resultParam,0);
			while($rowParam=mysqli_fetch_array($resultParam)){
				if($rowParam['Id_Formation']==$row['Id']){
					$Id_Langue=$rowParam['Id_Langue'];
					$Duree=$rowParam['Duree'];
					$DureeR=$rowParam['DureeRecyclage'];
					$NbJour=$rowParam['NbJour'];
					$NbJourR=$rowParam['NbJourRecyclage'];
					$Organisme=$rowParam['Organisme'];
				}
			}
		}
		$Infos="";
		$Description="";
		$InfosRecyclage="";
		$DescriptionRecyclage="";
		if($nbInfos>0){
			mysqli_data_seek($resultInfos,0);
			while($rowInfo=mysqli_fetch_array($resultInfos)){
				if($rowInfo['Id_Formation']==$row['Id'] && $rowInfo['Id_Langue']==$Id_Langue){
					$Infos=stripslashes($rowInfo['Libelle'])."";
					$Description=nl2br(stripslashes($rowInfo['Description']))."";
					$InfosRecyclage=stripslashes($rowInfo['LibelleRecyclage'])."";
					$DescriptionRecyclage=stripslashes($rowInfo['DescriptionRecyclage'])."";
				}
			}
		}
		$qualifications="";
		$qualificationsRecyclage="";
		if($nbQualifs>0){
			mysqli_data_seek($resultQualifications,0);
			while($rowQualif=mysqli_fetch_array($resultQualifications)){
				if($rowQualif['Id_Formation']==$row['Id']){
					if($qualifications<>""){$qualifications.="\n";}
					$qualifications.="- ".stripslashes($rowQualif['Qualif'])." (".stripslashes($rowQualif['QualifMaitre'])." - ".stripslashes($rowQualif['CategorieQualif']).") Validité : ".$rowQualif['Duree_Validite']." mois";
					
					if($qualificationsRecyclage<>""){$qualificationsRecyclage.="\n";}
					$qualificationsRecyclage.="- ".stripslashes($rowQualif['Qualif'])." (".stripslashes($rowQualif['QualifMaitre'])." - ".stripslashes($rowQualif['CategorieQualif']).") Validité : ".$rowQualif['Duree_Validite']." mois";
				}
			}
		}
		$btrouve=1;
		if($_GET['motcle']<>""){
			if(stripos($row['Reference'],$_GET['motcle'])===false && stripos($Description,$_GET['motcle'])===false && stripos($Infos,$_GET['motcle'])===false && stripos($qualifications,$_GET['motcle'])===false){
				$btrouve=0;
			}
			else{
				$btrouve=1;
			}
		}
		if($btrouve==1 && ($_GET['recyclage']=="0" || $_GET['recyclage']=="") ){
			if($NbJour=="0"){$NbJour= "";}
			if($Duree=="0"){$Duree= "";}
			$sheet->setCellValue('A'.$i,utf8_encode($row['Reference']));
			$sheet->setCellValue('B'.$i,utf8_encode($row['TypeFormation']));
			$sheet->setCellValue('C'.$i,utf8_encode($Infos));
			$sheet->setCellValue('D'.$i,utf8_encode($Organisme));
			$sheet->setCellValue('E'.$i,utf8_encode($qualifications));
			$sheet->setCellValue('F'.$i,utf8_encode(""));
			$sheet->setCellValue('G'.$i,utf8_encode($NbJour));
			$sheet->setCellValue('H'.$i,utf8_encode($Duree));
			$sheet->setCellValue('I'.$i,utf8_encode($Description));
			
			$sheet->getStyle('E'.$i)->getAlignment()->setWrapText(true);
			$sheet->getStyle('I'.$i)->getAlignment()->setWrapText(true);
			
			$sheet->getStyle('A'.$i.':I'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$i.':I'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheet->getStyle('A'.$i.':I'.$i)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));

			$i++;
		}
		
		if($row['Recyclage']==1){
			$btrouve=1;
			if($_GET['motcle']<>""){
				if(stripos($row['Reference'],$_GET['motcle'])===false && stripos($DescriptionRecyclage,$_GET['motcle'])===false && stripos($InfosRecyclage,$_GET['motcle'])===false && stripos($row['TypeFormation'],$_GET['motcle'])===false && stripos($qualificationsRecyclage,$_GET['motcle'])===false && stripos($Organisme,$_GET['motcle'])===false){
					$btrouve=0;
				}
				else{
					$btrouve=1;
				}
			}
			if($btrouve==1 && ($_GET['recyclage']=="1" || $_GET['recyclage']=="")){
				if($NbJour=="0"){$NbJour= "";}
				if($Duree=="0"){$Duree= "";}
				$sheet->setCellValue('A'.$i,utf8_encode($row['Reference']));
				$sheet->setCellValue('B'.$i,utf8_encode($row['TypeFormation']));
				$sheet->setCellValue('C'.$i,utf8_encode($InfosRecyclage));
				$sheet->setCellValue('D'.$i,utf8_encode($Organisme));
				$sheet->setCellValue('E'.$i,utf8_encode($qualificationsRecyclage));
				$sheet->setCellValue('F'.$i,utf8_encode("X"));
				$sheet->setCellValue('G'.$i,utf8_encode($NbJourR));
				$sheet->setCellValue('H'.$i,utf8_encode($DureeR));
				$sheet->setCellValue('I'.$i,utf8_encode($DescriptionRecyclage));
				
				$sheet->getStyle('E'.$i)->getAlignment()->setWrapText(true);
				$sheet->getStyle('I'.$i)->getAlignment()->setWrapText(true);
				
				$sheet->getStyle('A'.$i.':I'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$sheet->getStyle('A'.$i.':I'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$sheet->getStyle('A'.$i.':I'.$i)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));

				$i++;
			}
		}
	}
}		
$sheet->getSheetView()->setZoomScale(80);

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){
	header('Content-Disposition: attachment;filename="Catalogue_Formation.xlsx"');
}
else{
	header('Content-Disposition: attachment;filename="Training_Catalog.xlsx"');
}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Catalogue_Formation.xlsx';

$writer->save($chemin);
readfile($chemin);
?>
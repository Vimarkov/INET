<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("../Formation/Globales_Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
require_once("../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$vert="6fff55";
$orange="ffe915";
$rouge="ff151c";
$gris="aaaaaa";
$blanc="ffffff";

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
if($LangueAffichage=="FR"){
	$sheet->setTitle(utf8_encode("Sans date butoir"));
	
	$sheet->setCellValue('A1',utf8_encode("Matricule"));
	$sheet->setCellValue('B1',utf8_encode("Personne"));
	$sheet->setCellValue('C1',utf8_encode("Date d'embauche"));
	$sheet->setCellValue('D1',utf8_encode("Unité d'exploitation"));
	$sheet->setCellValue('E1',utf8_encode("Prestation"));
	$sheet->setCellValue('F1',utf8_encode("Type"));
	$sheet->setCellValue('G1',utf8_encode("Responsable"));
}
else{
	$sheet->setTitle(utf8_encode("No deadline"));
	
	$sheet->setCellValue('A1',utf8_encode("Registration number"));
	$sheet->setCellValue('B1',utf8_encode("People"));
	$sheet->setCellValue('C1',utf8_encode("Hiring date"));
	$sheet->setCellValue('D1',utf8_encode("Operating unit"));
	$sheet->setCellValue('E1',utf8_encode("Site"));
	$sheet->setCellValue('F1',utf8_encode("Type"));
	$sheet->setCellValue('G1',utf8_encode("Responsible"));
}

$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(25);
$sheet->getColumnDimension('F')->setWidth(25);
$sheet->getColumnDimension('G')->setWidth(30);

$sheet->getStyle('A1:G1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:G1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:G1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:G1')->getFont()->setBold(true);
$sheet->getStyle('A1:G1')->getFont()->getColor()->setRGB('1f49a6');

$dateCloture="";
$req="SELECT DateCloture FROM epe_cloturecampagne WHERE Annee=".$_SESSION['FiltreEPEIndicateurs_Annee']." ";
$resultDateCloture=mysqli_query($bdd,$req);
$nbDateCloture=mysqli_num_rows($resultDateCloture);
if($nbDateCloture>0){
	$rowDateCloture=mysqli_fetch_array($resultDateCloture);
	$dateCloture=$rowDateCloture['DateCloture'];
}
					
$req="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,
	Cadre,DateAncienneteCDI
FROM new_rh_etatcivil
WHERE new_rh_etatcivil.Id NOT IN (1726,1739)
AND MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') 
AND MetierPaie<>'' AND Cadre IN (0,1) 
AND new_rh_etatcivil.Id<>1739
AND (SELECT COUNT(Id_Plateforme) FROM new_competences_personne_plateforme
WHERE new_rh_etatcivil.Id=Id_Personne AND Id_Plateforme NOT IN (11,14))>0
AND
	(
		SELECT COUNT(new_competences_personne_prestation.Id)
		FROM new_competences_personne_prestation
		LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
		WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
		AND new_competences_personne_prestation.Date_Debut<='".$dateCloture."'
		AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateCloture."')
		AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
		AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
		AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].")
	)>0
AND (SELECT COUNT(TAB.Id) FROM epe_personne_datebutoir AS TAB WHERE TAB.Id_Personne=new_rh_etatcivil.Id  
	AND YEAR(IF(DateReport>'0001-01-01',DateReport,DateButoir)) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." AND TAB.TypeEntretien = '".$_SESSION['FiltreEPEIndicateurs_TypeEPE']."')=0
AND (SELECT COUNT(epe_personne_na.Id) FROM epe_personne_na WHERE Id_Personne=new_rh_etatcivil.Id  
	AND Annee = ".$_SESSION['FiltreEPEIndicateurs_Annee']." AND TypeEntretien = '".$_SESSION['FiltreEPEIndicateurs_TypeEPE']."')=0
";
if($dateCloture<>"" && $dateCloture>"0001-01-01"){
	$req.=" AND DateAncienneteCDI<'".$dateCloture."' ";
}
if($_SESSION['FiltreEPEIndicateurs_TypeEPE']=="EPP Bilan"){
	$req.=" AND YEAR(DateAncienneteCDI)<='".date('Y',strtotime(date($_SESSION['FiltreEPEIndicateurs_Annee'].'-m-d')." -6 year"))."' ";
	
	if($_SESSION['FiltreEPEDateButoir_SansDate']<>""){
	$req.=" AND (SELECT COUNT(Id)
				FROM epe_personne 
				WHERE Suppr=0 
				AND epe_personne.Type='EPP Bilan' 
				AND epe_personne.Id_Personne=new_rh_etatcivil.Id 
				AND ModeBrouillon=0 
				AND YEAR(DateEntretien) >= ".date('Y',strtotime(date($_SESSION['FiltreEPEIndicateurs_Annee'].'-m-d')." -5 year"))."
			)=0
		";
	}
}
elseif($_SESSION['FiltreEPEIndicateurs_TypeEPE']=="EPP"){
	$req.=" AND YEAR(DateAncienneteCDI)<='".date('Y',strtotime(date($_SESSION['FiltreEPEIndicateurs_Annee'].'-m-d')." -2 year"))."' ";
	
	if($_SESSION['FiltreEPEDateButoir_SansDate']<>""){
	$req.=" AND (SELECT COUNT(Id)
				FROM epe_personne 
				WHERE Suppr=0 
				AND epe_personne.Type='EPP' 
				AND epe_personne.Id_Personne=new_rh_etatcivil.Id 
				AND ModeBrouillon=0 
				AND YEAR(DateEntretien) >= ".date('Y',strtotime(date($_SESSION['FiltreEPEIndicateurs_Annee'].'-m-d')." -1 year"))."
			)=0
		";
	}
}
if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
	
}
else{
	$req.="
		AND
		(
			SELECT COUNT(new_competences_personne_prestation.Id)
			FROM new_competences_personne_prestation
			LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
			WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
			AND new_competences_personne_prestation.Date_Debut<='".$dateCloture."'
			AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateCloture."')
			AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
			AND 
			((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN 
				(
					SELECT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$_SESSION['Id_Personne']." 
					AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
				)
			) 
		)>0
		";
}
$req.="ORDER BY Personne ";
$result=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($result);

if($nbenreg>0){
	$ligne=1;
	while($row=mysqli_fetch_array($result)){
		$ligne++;
		
		$Id_Prestation=0;
		$Id_Pole=0;

		$req="SELECT Id_Prestation,Id_Pole 
			FROM new_competences_personne_prestation
			WHERE Id_Personne=".$row['Id']." 
			AND new_competences_personne_prestation.Date_Debut<='".$dateCloture."'
			AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateCloture."') 
			ORDER BY Date_Fin DESC, Date_Debut DESC
			";
		$resultch=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($resultch);
		$Id_PrestationPole="0_0";
		if($nb>0){
			$rowMouv=mysqli_fetch_array($resultch);
			$Id_PrestationPole=$rowMouv['Id_Prestation']."_".$rowMouv['Id_Pole'];
		}

		$TableauPrestationPole=explode("_",$Id_PrestationPole);
		$Id_Prestation=$TableauPrestationPole[0];
		$Id_Pole=$TableauPrestationPole[1];

		
		$Plateforme="";
		$Presta="";
		$req="SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,LEFT(Libelle,7) AS Prestation FROM new_competences_prestation WHERE Id=".$Id_Prestation;
		$ResultPresta=mysqli_query($bdd,$req);
		$NbPrest=mysqli_num_rows($ResultPresta);
		if($NbPrest>0){
			$RowPresta=mysqli_fetch_array($ResultPresta);
			$Presta=$RowPresta['Prestation'];
			$Plateforme=$RowPresta['Plateforme'];
		}
		
		$Pole="";
		$req="SELECT Libelle FROM new_competences_pole WHERE Id=".$Id_Pole;
		$ResultPole=mysqli_query($bdd,$req);
		$NbPole=mysqli_num_rows($ResultPole);
		if($NbPole>0){
			$RowPole=mysqli_fetch_array($ResultPole);
			$Pole=$RowPole['Libelle'];
		}
		
		if($Pole<>""){$Presta.=" - ".$Pole;}
		
		$Manager="";
		$Id_Manager=0;
		$req="SELECT Id_Manager, (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Manager) AS Manager FROM epe_personne_prestation WHERE Id_Personne=".$row['Id']." AND Id_Manager<>0 AND Id_Prestation=".$Id_Prestation."  AND Id_Pole=".$Id_Pole." AND Suppr=0 AND Annee=".$_SESSION['FiltreEPEIndicateurs_Annee']." ";
		$ResultlaPresta=mysqli_query($bdd,$req);
		$NblaPresta=mysqli_num_rows($ResultlaPresta);
		if($NblaPresta>0){
			$RowlaPresta=mysqli_fetch_array($ResultlaPresta);
			$Id_Manager=$RowlaPresta['Id_Manager'];
			$Manager=$RowlaPresta['Manager'];
		}
		else{
			$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
					FROM new_competences_personne_poste_prestation 
					LEFT JOIN new_rh_etatcivil
					ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
					WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
					AND Id_Prestation=".$Id_Prestation."
					AND Id_Pole=".$Id_Pole."
					AND Id_Personne=".$row['Id']."
					ORDER BY Backup ";
			$ResultManager2=mysqli_query($bdd,$req);
			$NbManager2=mysqli_num_rows($ResultManager2);
			if($NbManager2>0){
				$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
					FROM new_competences_personne_poste_prestation 
					LEFT JOIN new_rh_etatcivil
					ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
					WHERE Id_Poste=".$IdPosteCoordinateurProjet."
					AND Id_Prestation=".$Id_Prestation."
					AND Id_Pole=".$Id_Pole."
					ORDER BY Backup ";
				$ResultManager=mysqli_query($bdd,$req);
				$NbManager=mysqli_num_rows($ResultManager);
				if($NbManager>0){
					$RowManager=mysqli_fetch_array($ResultManager);
					$Manager=$RowManager['Personne'];
					$Id_Manager=$RowManager['Id'];
				}
			}
			else{
				$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
					FROM new_competences_personne_poste_prestation 
					LEFT JOIN new_rh_etatcivil
					ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
					WHERE Id_Poste=".$IdPosteChefEquipe."
					AND Id_Prestation=".$Id_Prestation."
					AND Id_Pole=".$Id_Pole."
					AND Id_Personne=".$row['Id']."
					ORDER BY Backup ";
				$ResultManager2=mysqli_query($bdd,$req);
				$NbManager2=mysqli_num_rows($ResultManager2);
				if($NbManager2>0){
					$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
						FROM new_competences_personne_poste_prestation 
						LEFT JOIN new_rh_etatcivil
						ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
						WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
						AND Id_Prestation=".$Id_Prestation."
						AND Id_Pole=".$Id_Pole."
						ORDER BY Backup ";
					$ResultManager=mysqli_query($bdd,$req);
					$NbManager=mysqli_num_rows($ResultManager);
					if($NbManager>0){
						$RowManager=mysqli_fetch_array($ResultManager);
						$Manager=$RowManager['Personne'];
						$Id_Manager=$RowManager['Id'];
					}
				}
				else{
					$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
					FROM new_competences_personne_poste_prestation 
					LEFT JOIN new_rh_etatcivil
					ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
					WHERE Id_Poste=".$IdPosteChefEquipe."
					AND Id_Prestation=".$Id_Prestation."
					AND Id_Pole=".$Id_Pole."
					ORDER BY Backup ";
					$ResultManager=mysqli_query($bdd,$req);
					$NbManager=mysqli_num_rows($ResultManager);
					if($NbManager>0){
						$RowManager=mysqli_fetch_array($ResultManager);
						$Manager=$RowManager['Personne'];
						$Id_Manager=$RowManager['Id'];
					}
				}
			}
		}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['MatriculeAAA']));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Personne'])));
		if($row['DateAncienneteCDI']>'0001-01-01'){
			$date = explode("-",$row['DateAncienneteCDI']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('C'.$ligne,$time);
			$sheet->getStyle('C'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		
		$sheet->setCellValue('D'.$ligne,utf8_encode($Plateforme));
		$sheet->setCellValue('E'.$ligne,utf8_encode($Presta));
		$sheet->setCellValue('F'.$ligne,utf8_encode($_SESSION['FiltreEPEIndicateurs_TypeEPE']));
		$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($Manager)));
		
		$sheet->getStyle('A'.$ligne.':G'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':G'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A'.$ligne.':G'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	}
}
										
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="Extract.xlsx"');}
else{header('Content-Disposition: attachment;filename="Extract.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
$chemin = '../../tmp/Extract.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
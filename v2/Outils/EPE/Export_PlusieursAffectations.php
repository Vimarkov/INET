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

$sheet->setTitle(utf8_encode("Liste"));

$sheet->setCellValue('A1',utf8_encode("Matricule"));
$sheet->setCellValue('B1',utf8_encode("Personne"));
$sheet->setCellValue('C1',utf8_encode("Prestation"));
$sheet->setCellValue('D1',utf8_encode("Unité d'exploitation"));
$sheet->setCellValue('E1',utf8_encode("Manager"));
$sheet->setCellValue('F1',utf8_encode("Modifié par"));

$sheet->getColumnDimension('A')->setWidth(30);
$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(25);
$sheet->getColumnDimension('F')->setWidth(25);


$sheet->getStyle('A1:F1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:F1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:F1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:F1')->getFont()->setBold(true);
$sheet->getStyle('A1:F1')->getFont()->getColor()->setRGB('1f49a6');

$requete2="SELECT DISTINCT new_rh_etatcivil.Id,CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
MatriculeAAA,
(SELECT COUNT(Id) FROM epe_personne_prestation WHERE Suppr=0 AND epe_personne_prestation.Id_Manager=0 AND epe_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
AND epe_personne_prestation.Annee=".$_SESSION['FiltreEPEAffectation_Annee'].") AS NbPresta
";
$requete="FROM new_rh_etatcivil
	RIGHT JOIN epe_personne_datebutoir 
	ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne 
	WHERE MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') 
	AND MetierPaie<>'' AND Cadre IN (0,1) ";

//Vérifier si appartient à une prestation OPTEA ou compétence
$requete.="AND (
	(
		SELECT COUNT(new_competences_personne_prestation.Id)
		FROM new_competences_personne_prestation
		LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
		WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
		AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
		AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
		AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
	)>1) 
	AND (
	(
		SELECT COUNT(new_competences_personne_prestation.Id)
		FROM new_competences_personne_prestation
		LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
		WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
		AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
		AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
		AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)";
	if($_SESSION['FiltreEPEAffectation_Plateforme']<>"0"){$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) = ".$_SESSION['FiltreEPEAffectation_Plateforme']." ";}
	if($_SESSION['FiltreEPEAffectation_Prestation']<>"0"){$requete.="AND new_competences_personne_prestation.Id_Prestation = ".$_SESSION['FiltreEPEAffectation_Prestation']." ";}
	if($_SESSION['FiltreEPEAffectation_Pole']<>"0"){$requete.="AND new_competences_personne_prestation.Id_Pole = ".$_SESSION['FiltreEPEAffectation_Pole']." ";}
$requete.="
	)>0)
	
	";
if($_SESSION['FiltreEPEAffectation_Personne']<>"0"){
	$requete.="AND new_rh_etatcivil.Id =".$_SESSION['FiltreEPEAffectation_Personne']." ";
}
$requete.="AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPEAffectation_Annee']." ";
$requete.="AND IF((SELECT COUNT(Id)
FROM epe_personne 
WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEAffectation_Annee'].")>0,
(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
FROM epe_personne 
WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEAffectation_Annee']."),
'A faire') IN ('A faire') ";
if($_SESSION['FiltreEPEAffectation_SansAffectation']==1){
	$requete.="AND (SELECT COUNT(epe_personne_prestation.Id) FROM epe_personne_prestation WHERE epe_personne_prestation.Suppr=0 AND epe_personne_prestation.Id_Manager=0 AND epe_personne_prestation.Id_Personne=new_rh_etatcivil.Id AND epe_personne_prestation.Annee=".$_SESSION['FiltreEPEAffectation_Annee'].")=0 ";
}
elseif($_SESSION['FiltreEPEAffectation_SansAffectation']==2){
	$requete.="AND (SELECT COUNT(epe_personne_prestation.Id) FROM epe_personne_prestation WHERE epe_personne_prestation.Suppr=0 AND epe_personne_prestation.Id_Manager=0 AND epe_personne_prestation.Id_Personne=new_rh_etatcivil.Id AND epe_personne_prestation.Annee=".$_SESSION['FiltreEPEAffectation_Annee'].")>0 ";
}

$result=mysqli_query($bdd,$requete2.$requete);
$nbResulta=mysqli_num_rows($result);

if($nbResulta>0){
	$ligne=2;
	while($row=mysqli_fetch_array($result)){

		$req="SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation, Id_Prestation,
			(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
			(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,Id_Pole,
			(SELECT CONCAT(Id_Prestation,'_',Id_Pole) FROM epe_personne_prestation WHERE epe_personne_prestation.Id_Personne=new_competences_personne_prestation.Id_Personne
			AND epe_personne_prestation.Id_Prestation=new_competences_personne_prestation.Id_Prestation
			AND epe_personne_prestation.Id_Pole=new_competences_personne_prestation.Id_Pole
			AND epe_personne_prestation.Annee=".$_SESSION['FiltreEPEAffectation_Annee']."
			AND epe_personne_prestation.Id_Manager=0
			AND epe_personne_prestation.Suppr=0) AS PrestaPole,
			(SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_RH) FROM epe_personne_prestation WHERE epe_personne_prestation.Id_Personne=new_competences_personne_prestation.Id_Personne
			AND epe_personne_prestation.Id_Prestation=new_competences_personne_prestation.Id_Prestation
			AND epe_personne_prestation.Id_Pole=new_competences_personne_prestation.Id_Pole
			AND epe_personne_prestation.Annee=".$_SESSION['FiltreEPEAffectation_Annee']."
			AND epe_personne_prestation.Id_Manager=0
			AND epe_personne_prestation.Suppr=0) AS RH
			FROM new_competences_personne_prestation
			WHERE Id_Personne=".$row['Id']." 
			AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
			AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."') ";

		$result2=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($result2);
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['MatriculeAAA']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Personne']));
		
		
		if($nb>1){
			$sheet->mergeCells('A'.$ligne.':A'.($ligne+$nb-1));
			$sheet->mergeCells('B'.$ligne.':B'.($ligne+$nb-1));
		}
		
		if($nb>0){
			while($row2=mysqli_fetch_array($result2)){
				$Manager="";
				$Id_Prestation=$row2['Id_Prestation'];
				$Id_Pole=$row2['Id_Pole'];
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
						}
					}
				}
				
				
				$Pole="";
				If($row2['Pole']<>""){$Pole=" - ".$row2['Pole'];}
				
				$sheet->setCellValue('C'.$ligne,utf8_encode($row2['Prestation'].$Pole));
				$sheet->setCellValue('D'.$ligne,utf8_encode($row2['Plateforme']));
				$sheet->setCellValue('E'.$ligne,utf8_encode($Manager));
				$sheet->setCellValue('F'.$ligne,utf8_encode($row2['RH']));
				
				$sheet->getStyle('A'.$ligne.':F'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$sheet->getStyle('A'.$ligne.':F'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$sheet->getStyle('A'.$ligne.':F'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
				
				$ligne++;
			}
		}
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
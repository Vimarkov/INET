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

$dateDebut=date($_SESSION['FiltreEPEIndicateurs_Annee'].'-01-01');
$dateFin=date($_SESSION['FiltreEPEIndicateurs_Annee'].'-12-31');
	
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
if($LangueAffichage=="FR"){
	$sheet->setTitle(utf8_encode("Entretiens non signes"));
	
	$sheet->setCellValue('A1',utf8_encode("Matricule"));
	$sheet->setCellValue('B1',utf8_encode("Personne"));
	$sheet->setCellValue('C1',utf8_encode("Unité d'exploitation"));
	$sheet->setCellValue('D1',utf8_encode("Prestation"));
	$sheet->setCellValue('E1',utf8_encode("Type"));
	$sheet->setCellValue('F1',utf8_encode("Date butoir"));
	$sheet->setCellValue('G1',utf8_encode("Responsable"));
	$sheet->setCellValue('H1',utf8_encode("Date entretien"));
	$sheet->setCellValue('I1',utf8_encode("Etat"));
}
else{
	$sheet->setTitle(utf8_encode("Unsigned interviews"));
	
	$sheet->setCellValue('A1',utf8_encode("Registration number"));
	$sheet->setCellValue('B1',utf8_encode("People"));
	$sheet->setCellValue('C1',utf8_encode("Operating unit"));
	$sheet->setCellValue('D1',utf8_encode("Site"));
	$sheet->setCellValue('E1',utf8_encode("Type"));
	$sheet->setCellValue('F1',utf8_encode("Deadline"));
	$sheet->setCellValue('G1',utf8_encode("Responsible"));
	$sheet->setCellValue('H1',utf8_encode("Interview date"));
	$sheet->setCellValue('I1',utf8_encode("State"));
	
}

$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(25);
$sheet->getColumnDimension('F')->setWidth(25);
$sheet->getColumnDimension('G')->setWidth(30);
$sheet->getColumnDimension('H')->setWidth(25);
$sheet->getColumnDimension('I')->setWidth(35);

$sheet->getStyle('A1:I1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:I1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:I1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:I1')->getFont()->setBold(true);
$sheet->getStyle('A1:I1')->getFont()->getColor()->setRGB('1f49a6');

$req="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,
	TypeEntretien AS TypeE,
	IF(TypeEntretien='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),TypeEntretien) AS TypeEntretien,IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir) AS DateButoir,
	epe_personne_datebutoir.Id AS Id_EpePersonneDB,Cadre,
	IF((SELECT COUNT(Id)
	FROM epe_personne 
	WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1)>0,
	(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Attente signature salarié',IF(DateEvaluateur>'0001-01-01' ,'Réalisé','Attente signature manager')))
	FROM epe_personne 
	WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1),
	'A faire') AS Etat,
	IF((SELECT COUNT(Id)
	FROM epe_personne 
	WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1)>0,
	(SELECT DateEntretien
	FROM epe_personne 
	WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1)
	,
	'0001-01-01') AS DateEntretien,
	(SELECT Id_Evaluateur
	FROM epe_personne 
	WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1) AS Id_Manager,
	(SELECT Id_Prestation
	FROM epe_personne 
	WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1) AS Id_Prestation,
	(SELECT Id_Pole
	FROM epe_personne 
	WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1) AS Id_Pole
FROM new_rh_etatcivil
RIGHT JOIN epe_personne_datebutoir 
ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne 
WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01'  AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
OR 
	(SELECT COUNT(Id)
	FROM epe_personne 
	WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee'].")>0
) 
AND
(
	SELECT COUNT(new_competences_personne_prestation.Id)
	FROM new_competences_personne_prestation
	LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
	WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
	AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
	AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
	AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
	AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].")
)>0
AND YEAR(IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." 
AND IF((SELECT COUNT(Id)
	FROM epe_personne 
	WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1)>0,
	(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Soumis',IF(DateEvaluateur>'0001-01-01' ,'Réalisé','Signature manager')))
	FROM epe_personne 
	WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1),
	'A faire') IN ('Soumis','Signature manager')
";
if($_SESSION['FiltreEPEIndicateurs_Type']<>""){
	$req.="AND TypeEntretien IN (".$_SESSION['FiltreEPEIndicateurs_Type'].") ";
}
$req.="
	AND
	(
		SELECT COUNT(new_competences_personne_prestation.Id)
		FROM new_competences_personne_prestation
		LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
		WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
		AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
		AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
		AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) 
	)>0
	";
$req.="ORDER BY Personne ";
$result=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($result);

if($nbenreg>0){
	$ligne=1;
	while($row=mysqli_fetch_array($result)){
		$Id_Prestation=0;
		$Id_Pole=0;

		if($row['Id_Prestation']<>""){
			$Id_Prestation=$row['Id_Prestation'];
		}
		if($row['Id_Pole']<>""){
			$Id_Pole=$row['Id_Pole'];
		}
		
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
		if($row['Id_Manager']<>""){
			$Id_Manager=$row['Id_Manager'];
		}
		$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
				FROM new_rh_etatcivil
				WHERE Id=".$Id_Manager." ";
		$ResultManager=mysqli_query($bdd,$req);
		$NbManager=mysqli_num_rows($ResultManager);
		if($NbManager>0){
			$RowManager=mysqli_fetch_array($ResultManager);
			$Manager=$RowManager['Personne'];
		}
		$trouve=1;
		if($_SESSION['FiltreEPEIndicateurs_Responsable']<>"0"){
			if($_SESSION['FiltreEPEIndicateurs_Responsable']<>$Id_Manager){
				$trouve=0;
			}
		}
		
		$ReqDroits= "
			SELECT
				Id
			FROM
				new_competences_personne_poste_prestation
			WHERE
				Id_Personne=".$_SESSION['Id_Personne']."
				AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.")
				AND Id_Prestation = ".$Id_Prestation."
				AND Id_Pole = ".$Id_Pole." 
			UNION 
			SELECT Id 
			FROM new_competences_personne_poste_plateforme
			WHERE Id_Personne=".$_SESSION['Id_Personne']." 
			AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteResponsablePlateforme.")
			AND (Id_Plateforme IN (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation.")
			OR Id_Plateforme=17
			)
				";
		$ResultDroits=mysqli_query($bdd,$ReqDroits);
		$NbEnregDroits=mysqli_num_rows($ResultDroits);
		if($NbEnregDroits==0){$trouve=0;}
		
		if($trouve==1){
			$ligne++;
		
			$sheet->setCellValue('A'.$ligne,utf8_encode($row['MatriculeAAA']));
			$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Personne'])));
			$sheet->setCellValue('C'.$ligne,utf8_encode($Plateforme));
			$sheet->setCellValue('D'.$ligne,utf8_encode($Presta));
			$sheet->setCellValue('E'.$ligne,utf8_encode($row['TypeEntretien']));
			
			if($row['DateButoir']>'0001-01-01' ){
				$date = explode("-",$row['DateButoir']);
				$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
				$sheet->setCellValue('F'.$ligne,$time);
				$sheet->getStyle('F'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
			}
			$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($Manager)));
			
			if($row['DateEntretien']>'0001-01-01' ){
				$date = explode("-",$row['DateEntretien']);
				$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
				$sheet->setCellValue('H'.$ligne,$time);
				$sheet->getStyle('H'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
			}
			$sheet->setCellValue('I'.$ligne,utf8_encode($row['Etat']));
			
			$sheet->getStyle('A'.$ligne.':I'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligne.':I'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheet->getStyle('A'.$ligne.':I'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
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
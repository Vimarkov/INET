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

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('TemplateEPPBilan.xlsx');
$sheet = $excel->getSheetByName('Liste');

$requete2="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne ";
$requete="FROM new_rh_etatcivil
	RIGHT JOIN epe_personne_datebutoir 
	ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne 
	WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
	OR 
		(SELECT COUNT(Id)
		FROM epe_personne 
		WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0
	)
	AND TypeEntretien ='EPP Bilan' 
	AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." 
	AND IF((SELECT COUNT(Id)
	FROM epe_personne 
	WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
	(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
	FROM epe_personne 
	WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']."),
	'A faire') IN ('Réalisé') ";

if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
	//Vérifier si appartient à une prestation OPTEA ou compétence
		$requete.="AND 
			(
				SELECT COUNT(new_competences_personne_prestation.Id)
				FROM new_competences_personne_prestation
				LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
				WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
				AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
				AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
				AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)";
				if($_SESSION['FiltreEPE_Plateforme']<>"0"){$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) = ".$_SESSION['FiltreEPE_Plateforme']." ";}
				if($_SESSION['FiltreEPE_Prestation']<>"0"){$requete.="AND new_competences_personne_prestation.Id_Prestation = ".$_SESSION['FiltreEPE_Prestation']." ";}
				if($_SESSION['FiltreEPE_Pole']<>"0"){$requete.="AND new_competences_personne_prestation.Id_Pole = ".$_SESSION['FiltreEPE_Pole']." ";}
			$requete.="
			)>0 ";
}
else{
	//Vérifier si appartient à une prestation OPTEA ou compétence
$requete.="AND
			(
				SELECT COUNT(new_competences_personne_prestation.Id)
				FROM new_competences_personne_prestation
				LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
				WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
				AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
				AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
				AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)";
				if($_SESSION['FiltreEPE_Plateforme']<>"0"){$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) = ".$_SESSION['FiltreEPE_Plateforme']." ";}
				if($_SESSION['FiltreEPE_Prestation']<>"0"){$requete.="AND new_competences_personne_prestation.Id_Prestation = ".$_SESSION['FiltreEPE_Prestation']." ";}
				if($_SESSION['FiltreEPE_Pole']<>"0"){$requete.="AND new_competences_personne_prestation.Id_Pole = ".$_SESSION['FiltreEPE_Pole']." ";}
			$requete.="
				AND 
				((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN 
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
					)
					OR CONCAT(new_competences_personne_prestation.Id_Prestation,'_',new_competences_personne_prestation.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$_SESSION["Id_Personne"]."
					AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.") 
					AND Backup=0
					)
					OR new_rh_etatcivil.Id=".$_SESSION['Id_Personne']."
				)
			)>0 ";
}
if($_SESSION['FiltreEPE_Personne']<>"0"){
	$requete.="AND new_rh_etatcivil.Id =".$_SESSION['FiltreEPE_Personne']." ";
}
if($_SESSION['FiltreEPE_Manager']<>"0"){
	$requete.="AND (SELECT COUNT(Id)
	FROM epe_personne 
	WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." AND Id_Evaluateur=".$_SESSION['FiltreEPE_Manager'].")>0  ";
}

$requete.="ORDER BY Personne ";

$result=mysqli_query($bdd,$requete2.$requete);
$nbResulta=mysqli_num_rows($result);


if($nbResulta>0){
	$ligne=2;
	while($row=mysqli_fetch_array($result)){

		$reqNb="SELECT DISTINCT epe_personne.Id AS EpePersonne, new_rh_etatcivil.Id, 
		CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,DateAnciennete,
		Type AS TypeE,DateEntretien,
		IF(Cadre=0,'Non cadre','Cadre') AS TypeEntretien,DateButoir,
		Cadre,
		(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS Manager,
		(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
		(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,Metier,
		(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
		(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS MatriculeAAAManager,MetierManager,
		EPPBilan,EPPBilanRefuseSalarie,NbEntretienPro,ComNbEntretiensPro,ActionFormationOEPPBilan,ActionFormationNonOEPPBilan,CertifParFormation,EvolutionSalariale,EvolutionPro,
		DateEvaluateur,DateSalarie
		FROM new_rh_etatcivil
		LEFT JOIN epe_personne
		ON new_rh_etatcivil.Id=epe_personne.Id_Personne
		WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
		OR 
			(SELECT COUNT(Id)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0
		) 
		AND new_rh_etatcivil.Id=".$row['Id']."
		AND YEAR(DateButoir) = ".$_SESSION['FiltreEPE_Annee']." 
		AND IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager'))) IN ('Signature salarié','Signature manager','Réalisé') 
		AND Type ='EPP Bilan' ";
		$ResultNb=mysqli_query($bdd,$reqNb);
		$leNb=mysqli_num_rows($ResultNb);
		
		$rowNb=mysqli_fetch_array($ResultNb);
		
		$Presta=$rowNb['Prestation'];
		if($rowNb['Pole']<>""){
			$Presta.=" - ".$rowNb['Pole'];
		}

		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($rowNb['MatriculeAAA'])));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($rowNb['Personne'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($rowNb['Metier'])));
		if($rowNb['DateAnciennete']>'0001-01-01'){
			$date = explode("-",$rowNb['DateAnciennete']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('D'.$ligne,$time);
			$sheet->getStyle('D'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		if($rowNb['DateButoir']>'0001-01-01'){
			$date = explode("-",$rowNb['DateButoir']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('E'.$ligne,$time);
			$sheet->getStyle('E'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		$sheet->setCellValue('F'.$ligne,utf8_encode($rowNb['TypeEntretien']));
		if($rowNb['DateEntretien']>'0001-01-01'){
			$date = explode("-",$rowNb['DateEntretien']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('G'.$ligne,$time);
			$sheet->getStyle('G'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($rowNb['Plateforme'])));
		$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($rowNb['Manager'])));
		$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($rowNb['MatriculeAAAManager'])));
		$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($rowNb['MetierManager'])));
		
		if($rowNb['EPPBilan']==1){
			$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes("X")));
		}
		if($rowNb['EPPBilanRefuseSalarie']==1){
			$sheet->setCellValue('M'.$ligne,utf8_encode(stripslashes("X")));
		}
		$sheet->setCellValue('N'.$ligne,utf8_encode(stripslashes($rowNb['NbEntretienPro']."\n".stripslashes($rowNb['ComNbEntretiensPro']))));
		$sheet->setCellValue('O'.$ligne,utf8_encode(stripslashes($rowNb['ActionFormationOEPPBilan'])));
		$sheet->setCellValue('P'.$ligne,utf8_encode(stripslashes($rowNb['ActionFormationNonOEPPBilan'])));
		$sheet->setCellValue('Q'.$ligne,utf8_encode(stripslashes($rowNb['CertifParFormation'])));
		$sheet->setCellValue('R'.$ligne,utf8_encode(stripslashes($rowNb['EvolutionSalariale'])));
		$sheet->setCellValue('S'.$ligne,utf8_encode(stripslashes($rowNb['EvolutionPro'])));
		
		if($rowNb['DateEntretien']>'0001-01-01'){
			$date = explode("-",$rowNb['DateEntretien']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('T'.$ligne,$time);
			$sheet->getStyle('T'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		
		if($rowNb['DateEvaluateur']>'0001-01-01'){
			$date = explode("-",$rowNb['DateEvaluateur']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('U'.$ligne,$time);
			$sheet->getStyle('U'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		
		$sheet->getStyle('A'.$ligne.':U'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':U'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A'.$ligne.':U'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		
		$ligne++;
	}
}


//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="Extract.xlsx"');}
else{header('Content-Disposition: attachment;filename="Extract.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../tmp/Extract.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
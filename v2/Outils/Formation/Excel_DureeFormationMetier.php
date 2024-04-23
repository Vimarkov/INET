<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("Globales_Fonctions.php");
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
	$sheet->setTitle(utf8_encode("SansFormation"));
	
	$sheet->setCellValue('A1',utf8_encode("Prestation"));
	$sheet->setCellValue('B1',utf8_encode("Métier"));
	$sheet->setCellValue('C1',utf8_encode("Durée Obligatoire"));
	$sheet->setCellValue('D1',utf8_encode("Durée Facultative"));
	$sheet->setCellValue('E1',utf8_encode("Durée Totale"));
	$sheet->setCellValue('F1',utf8_encode("Coût salarié Obligatoire"));
	$sheet->setCellValue('G1',utf8_encode("Coût salarié Facultative"));
	$sheet->setCellValue('H1',utf8_encode("Coût salarié Totale"));
	$sheet->setCellValue('I1',utf8_encode("Coût intérimaire Obligatoire"));
	$sheet->setCellValue('J1',utf8_encode("Coût intérimaire Facultative"));
	$sheet->setCellValue('K1',utf8_encode("Coût intérimaire Totale"));
}
else{
	$sheet->setCellValue('A1',utf8_encode("Site"));
	$sheet->setCellValue('B1',utf8_encode("Job"));
	$sheet->setCellValue('C1',utf8_encode("Duration Mandatory"));
	$sheet->setCellValue('D1',utf8_encode("Duration Optional"));
	$sheet->setCellValue('E1',utf8_encode("Duration Totale"));
	$sheet->setCellValue('F1',utf8_encode("Employee Cost Mandatory"));
	$sheet->setCellValue('G1',utf8_encode("Employee Cost Optional"));
	$sheet->setCellValue('H1',utf8_encode("Employee Cost Totale"));
	$sheet->setCellValue('I1',utf8_encode("Interim cost Mandatory"));
	$sheet->setCellValue('J1',utf8_encode("Interim cost Optional"));
	$sheet->setCellValue('K1',utf8_encode("Interim cost Totale"));
}

$sheet->getColumnDimension('A')->setWidth(30);
$sheet->getColumnDimension('B')->setWidth(30);

$sheet->getStyle('A1:K1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:K1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:K1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:K1')->getFont()->setBold(true);
$sheet->getStyle('A1:K1')->getFont()->getColor()->setRGB('1f49a6');
					
$Id_Presta=$_SESSION['FiltreDureeFormMetier_Prestations'];
$Id_Plateforme=$_SESSION['FiltreDureeFormMetier_Plateforme'];
$Id_Type=$_SESSION['FiltreDureeFormMetier_Type'];
$Id_RespProjet=$_SESSION['FiltreDureeFormMetier_RespProjet'];


$req="SELECT DISTINCT Id_Metier, Id_Prestation, Id_Pole,
(SELECT Libelle FROM new_competences_metier WHERE Id=Id_Metier) AS Metier,
new_competences_prestation.Libelle AS Prestation,
(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,
(SELECT SUM(CoutSalarieAAA)
	FROM form_formation_plateforme_parametres
	WHERE form_formation_plateforme_parametres.Id_Plateforme=".$Id_Plateforme." 
	AND form_formation_plateforme_parametres.Suppr=0
	AND Id_Formation IN (
		SELECT Id_Formation 
		FROM form_prestation_metier_formation
		WHERE form_prestation_metier_formation.Suppr=0
		AND form_prestation_metier_formation.Id_Metier=new_competences_personne_metier.Id_Metier
		AND form_prestation_metier_formation.Id_Prestation=new_competences_personne_prestation.Id_Prestation
		AND form_prestation_metier_formation.Id_Pole=new_competences_personne_prestation.Id_Pole
		AND form_prestation_metier_formation.Obligatoire=1
	) ";
if($Id_Type<>""){$req.="AND (SELECT Id_TypeFormation FROM form_formation WHERE Id=Id_Formation) IN (".$Id_Type.") ";}
$req.=") AS CoutSalarieAAA_O,
(SELECT SUM(CoutSalarieAAA)
	FROM form_formation_plateforme_parametres
	WHERE form_formation_plateforme_parametres.Id_Plateforme=".$Id_Plateforme." 
	AND form_formation_plateforme_parametres.Suppr=0
	AND Id_Formation IN (
		SELECT Id_Formation 
		FROM form_prestation_metier_formation
		WHERE form_prestation_metier_formation.Suppr=0
		AND form_prestation_metier_formation.Id_Metier=new_competences_personne_metier.Id_Metier
		AND form_prestation_metier_formation.Id_Prestation=new_competences_personne_prestation.Id_Prestation
		AND form_prestation_metier_formation.Id_Pole=new_competences_personne_prestation.Id_Pole
		AND form_prestation_metier_formation.Obligatoire=0
	) ";
if($Id_Type<>""){$req.="AND (SELECT Id_TypeFormation FROM form_formation WHERE Id=Id_Formation) IN (".$Id_Type.") ";}
$req.=") AS CoutSalarieAAA_F,
(SELECT SUM(CoutInterimaire)
	FROM form_formation_plateforme_parametres
	WHERE form_formation_plateforme_parametres.Id_Plateforme=".$Id_Plateforme." 
	AND form_formation_plateforme_parametres.Suppr=0
	AND Id_Formation IN (
		SELECT Id_Formation 
		FROM form_prestation_metier_formation
		WHERE form_prestation_metier_formation.Suppr=0
		AND form_prestation_metier_formation.Id_Metier=new_competences_personne_metier.Id_Metier
		AND form_prestation_metier_formation.Id_Prestation=new_competences_personne_prestation.Id_Prestation
		AND form_prestation_metier_formation.Id_Pole=new_competences_personne_prestation.Id_Pole
		AND form_prestation_metier_formation.Obligatoire=1
	) ";
if($Id_Type<>""){$req.="AND (SELECT Id_TypeFormation FROM form_formation WHERE Id=Id_Formation) IN (".$Id_Type.") ";}
$req.=") AS CoutInterimaire_O,
(SELECT SUM(CoutInterimaire)
	FROM form_formation_plateforme_parametres
	WHERE form_formation_plateforme_parametres.Id_Plateforme=".$Id_Plateforme." 
	AND form_formation_plateforme_parametres.Suppr=0
	AND Id_Formation IN (
		SELECT Id_Formation 
		FROM form_prestation_metier_formation
		WHERE form_prestation_metier_formation.Suppr=0
		AND form_prestation_metier_formation.Id_Metier=new_competences_personne_metier.Id_Metier
		AND form_prestation_metier_formation.Id_Prestation=new_competences_personne_prestation.Id_Prestation
		AND form_prestation_metier_formation.Id_Pole=new_competences_personne_prestation.Id_Pole
		AND form_prestation_metier_formation.Obligatoire=0
	) ";
if($Id_Type<>""){$req.="AND (SELECT Id_TypeFormation FROM form_formation WHERE Id=Id_Formation) IN (".$Id_Type.") ";}
$req.=") AS CoutInterimaire_F,
(SELECT SUM(Duree)
	FROM form_formation_plateforme_parametres
	WHERE form_formation_plateforme_parametres.Id_Plateforme=".$Id_Plateforme." 
	AND form_formation_plateforme_parametres.Suppr=0
	AND Id_Formation IN (
		SELECT Id_Formation 
		FROM form_prestation_metier_formation
		WHERE form_prestation_metier_formation.Suppr=0
		AND form_prestation_metier_formation.Id_Metier=new_competences_personne_metier.Id_Metier
		AND form_prestation_metier_formation.Id_Prestation=new_competences_personne_prestation.Id_Prestation
		AND form_prestation_metier_formation.Id_Pole=new_competences_personne_prestation.Id_Pole
		AND form_prestation_metier_formation.Obligatoire=1
	) ";
if($Id_Type<>""){$req.="AND (SELECT Id_TypeFormation FROM form_formation WHERE Id=Id_Formation) IN (".$Id_Type.") ";}
$req.=") AS Duree_O,
(SELECT SUM(Duree)
	FROM form_formation_plateforme_parametres
	WHERE form_formation_plateforme_parametres.Id_Plateforme=".$Id_Plateforme." 
	AND form_formation_plateforme_parametres.Suppr=0
	AND Id_Formation IN (
		SELECT Id_Formation 
		FROM form_prestation_metier_formation
		WHERE form_prestation_metier_formation.Suppr=0
		AND form_prestation_metier_formation.Id_Metier=new_competences_personne_metier.Id_Metier
		AND form_prestation_metier_formation.Id_Prestation=new_competences_personne_prestation.Id_Prestation
		AND form_prestation_metier_formation.Id_Pole=new_competences_personne_prestation.Id_Pole
		AND form_prestation_metier_formation.Obligatoire=0
	) ";
if($Id_Type<>""){$req.="AND (SELECT Id_TypeFormation FROM form_formation WHERE Id=Id_Formation) IN (".$Id_Type.") ";}
$req.=") AS Duree_F,
(SELECT SUM(Duree)
	FROM form_formation_plateforme_parametres
	WHERE form_formation_plateforme_parametres.Id_Plateforme=".$Id_Plateforme." 
	AND form_formation_plateforme_parametres.Suppr=0
	AND Id_Formation IN (
		SELECT Id_Formation 
		FROM form_prestation_metier_formation
		WHERE form_prestation_metier_formation.Suppr=0
		AND form_prestation_metier_formation.Id_Metier=new_competences_personne_metier.Id_Metier
		AND form_prestation_metier_formation.Id_Prestation=new_competences_personne_prestation.Id_Prestation
		AND form_prestation_metier_formation.Id_Pole=new_competences_personne_prestation.Id_Pole
	) ";
if($Id_Type<>""){$req.="AND (SELECT Id_TypeFormation FROM form_formation WHERE Id=Id_Formation) IN (".$Id_Type.") ";}
$req.=") AS Duree
FROM new_competences_personne_prestation,
new_competences_prestation,
new_competences_personne_metier
WHERE new_competences_prestation.Id_Plateforme=".$Id_Plateforme." 
AND new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
AND new_competences_personne_metier.Id_Personne=new_competences_personne_prestation.Id_Personne
AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
AND (new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."' OR new_competences_personne_prestation.Date_Fin<='0001-01-01' ) 
";
if($Id_Presta<>""){$req.="AND CONCAT(Id_Prestation,'_',Id_Pole) IN (".$Id_Presta.")";}
if($Id_RespProjet<>""){
	$req.="AND CONCAT(Id_Prestation,'_',Id_Pole) 
				IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
					FROM new_competences_personne_poste_prestation
					WHERE Id_Personne IN (".$Id_RespProjet.")
					AND Id_Poste IN (".$IdPosteResponsableProjet.")
				)
				";
}
$req.=" GROUP BY Id_Metier, Id_Prestation, Id_Pole
ORDER BY Prestation, Pole, Metier ";
$result=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($result);

$result=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($result);
if($nbenreg>0){
	$ligne=1;
	while($row=mysqli_fetch_array($result)){
		$ligne++;
		
		$CoutSalarieAAA=$row['CoutSalarieAAA_O']+$row['CoutSalarieAAA_F'];
		$CoutInterimaire=$row['CoutInterimaire_O']+$row['CoutInterimaire_F'];
		$DureeO="";
		$DureeF="";
		$DureeT="";
		
		if($row['Duree_O']<>""){
			$tab=explode(".",$row['Duree_O']);
			$heure=$tab[0];
			$min=$tab[1];
			
			$nbHeures=$min/60;
			$restMin =$min % 60;
			
			$tab=explode(".",$nbHeures);
			$heure+=$tab[0];
			$DureeO=$heure.":".$restMin;
		}
		if($row['Duree_F']<>""){
			$tab=explode(".",$row['Duree_F']);
			$heure=$tab[0];
			$min=$tab[1];
			
			$nbHeures=$min/60;
			$restMin =$min % 60;
			
			$tab=explode(".",$nbHeures);
			$heure+=$tab[0];
			$DureeF=$heure.":".$restMin;
		}
		if($row['Duree']<>""){
			$tab=explode(".",$row['Duree']);
			$heure=$tab[0];
			$min=$tab[1];
			
			$nbHeures=$min/60;
			$restMin =$min % 60;
			
			$tab=explode(".",$nbHeures);
			$heure+=$tab[0];
			$DureeT=$heure.":".$restMin;
		}

		$sheet->setCellValue('A'.$ligne,utf8_encode(substr($row['Prestation'],0,7)." ".$row['Pole']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Metier']));
		
		$sheet->setCellValue('C'.$ligne,utf8_encode($DureeO));
		$sheet->setCellValue('D'.$ligne,utf8_encode($DureeF));
		$sheet->setCellValue('E'.$ligne,utf8_encode($DureeT));
		
		$sheet->setCellValue('F'.$ligne,utf8_encode($row['CoutSalarieAAA_O']));
		$sheet->setCellValue('G'.$ligne,utf8_encode($row['CoutSalarieAAA_F']));
		if($CoutSalarieAAA>0){$sheet->setCellValue('H'.$ligne,utf8_encode($CoutSalarieAAA));}
		
		$sheet->setCellValue('I'.$ligne,utf8_encode($row['CoutInterimaire_O']));
		$sheet->setCellValue('J'.$ligne,utf8_encode($row['CoutInterimaire_F']));
		if($CoutInterimaire>0){$sheet->setCellValue('K'.$ligne,utf8_encode($CoutInterimaire));}
		
		$sheet->getStyle('A'.$ligne.':K'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':K'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A'.$ligne.':K'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
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
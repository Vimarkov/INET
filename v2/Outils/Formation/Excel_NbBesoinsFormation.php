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
	$sheet->setTitle(utf8_encode("Besoins"));
	
	$sheet->setCellValue('A1',utf8_encode("Formation"));
	$sheet->setCellValue('B1',utf8_encode("Total"));
}
else{
	$sheet->setTitle(utf8_encode("Needs"));
	$sheet->setCellValue('A1',utf8_encode("Training"));
	$sheet->setCellValue('B1',utf8_encode("Total"));
}

$sheet->getColumnDimension('A')->setWidth(100);

$Id_Presta=$_SESSION['FiltreTabNbBesoins_Prestations'];
$Id_Plateforme=$_SESSION['FiltreTabNbBesoins_Plateforme'];
$Id_Type=$_SESSION['FiltreTabNbBesoins_Type'];
$MotifNouveau=$_SESSION['FiltreTabNbBesoins_MotifNouveau'];
$MotifRenouvellement=$_SESSION['FiltreTabNbBesoins_MotifRenouvellement'];
$Id_Formation=$_SESSION['FiltreTabNbBesoins_Formation'];

$Id_RespProjet=$_SESSION['FiltreTabNbBesoins_RespProjet'];

$requete="	SELECT DISTINCT
			new_competences_prestation.Libelle AS Prestation,
			(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=form_besoin.Id_Pole) AS Pole,
			form_besoin.Id_Prestation,
			form_besoin.Id_Pole
			FROM
			form_besoin,
			form_typeformation,
			form_formation,
			new_competences_prestation
		WHERE
			form_besoin.Id_Formation=form_formation.Id
			AND form_formation.Id_TypeFormation=form_typeformation.Id
			AND form_besoin.Id_Prestation=new_competences_prestation.Id
			AND form_besoin.Traite=0 
			AND form_besoin.Suppr=0 
			AND form_besoin.Valide IN (0,1) 
			AND new_competences_prestation.Id_Plateforme=".$Id_Plateforme."
			";
if($Id_Type<>""){$requete.="AND Id_TypeFormation IN (".$Id_Type.") ";}

if($MotifNouveau=="1" && $MotifRenouvellement=="1"){
}
elseif($MotifNouveau=="1"){
	$requete.="AND Motif <>'Renouvellement' ";
}
elseif($MotifRenouvellement=="1"){
	$requete.="AND Motif ='Renouvellement' ";
}
if($Id_Presta<>""){$requete.="AND CONCAT(form_besoin.Id_Prestation,'_',form_besoin.Id_Pole) IN (".$Id_Presta.")";}
if($Id_RespProjet<>""){
	$requete.="AND CONCAT(form_besoin.Id_Prestation,'_',form_besoin.Id_Pole) 
				IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
					FROM new_competences_personne_poste_prestation
					WHERE Id_Personne IN (".$Id_RespProjet.")
					AND Id_Poste IN (".$IdPosteResponsableProjet.")
				)
				";
}
if($Id_Formation<>"" && $Id_Formation<>"0_0"){
	$tabQual=explode("_",$Id_Formation);
	if($tabQual[1]==0){
		$requete.=" AND Id_Formation=".$tabQual[0]." ";
	}
	else{
		$requete.=" AND Id_Formation IN 
			(SELECT Id_Formation 
			FROM form_formationequivalente_formationplateforme 
			WHERE Id_FormationEquivalente=".$tabQual[0].") ";
	}
}
$requete.=" ORDER BY Prestation, Pole ";

$resultPresta=mysqli_query($bdd,$requete);
$nbenregPresta=mysqli_num_rows($resultPresta);
if($nbenregPresta>0){
	$col="B";
	while($row=mysqli_fetch_array($resultPresta)){
		$col++;
		$sheet->setCellValue($col.'1',utf8_encode(substr($row['Prestation'],0,7)." ".$row['Pole']));
	}
}
$sheet->getStyle('A1:'.$col.'1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:'.$col.'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:'.$col.'1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:'.$col.'1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:'.$col.'1')->getFont()->setBold(true);
$sheet->getStyle('A1:'.$col.'1')->getFont()->getColor()->setRGB('1f49a6');

$requete="	SELECT DISTINCT
			form_besoin.Id_Formation,
			(SELECT (SELECT CONCAT(' (',Libelle,')') FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
				WHERE form_formation_plateforme_parametres.Id_Formation=form_besoin.Id_Formation
				AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
				AND Suppr=0 LIMIT 1) AS Organisme,
			(SELECT IF(form_besoin.Motif='Renouvellement' AND form_formation.Recyclage=1,LibelleRecyclage,Libelle)
					FROM form_formation_langue_infos
					WHERE Id_Formation=form_besoin.Id_Formation
					AND Id_Langue=
						(SELECT Id_Langue 
						FROM form_formation_plateforme_parametres 
						WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
						AND Id_Formation=form_besoin.Id_Formation
						AND Suppr=0 
						LIMIT 1)
					AND Suppr=0) AS LibelleFormation,
			(SELECT IF(form_besoin.Motif='Renouvellement' AND form_formation.Recyclage=1,1,0)
					FROM form_formation_langue_infos
					WHERE Id_Formation=form_besoin.Id_Formation
					AND Id_Langue=
						(SELECT Id_Langue 
						FROM form_formation_plateforme_parametres 
						WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
						AND Id_Formation=form_besoin.Id_Formation
						AND Suppr=0 
						LIMIT 1)
					AND Suppr=0) AS Recyclage,
			form_formation.Recyclage AS RecyclageDifferent
			FROM
			form_besoin,
			form_typeformation,
			form_formation,
			new_competences_prestation
		WHERE
			form_besoin.Id_Formation=form_formation.Id
			AND form_formation.Id_TypeFormation=form_typeformation.Id
			AND form_besoin.Id_Prestation=new_competences_prestation.Id
			AND form_besoin.Traite=0 
			AND form_besoin.Suppr=0 
			AND form_besoin.Valide IN (0,1) 
			AND new_competences_prestation.Id_Plateforme=".$Id_Plateforme."
			";
if($Id_Type<>""){$requete.="AND Id_TypeFormation IN (".$Id_Type.") ";}

if($MotifNouveau=="1" && $MotifRenouvellement=="1"){
}
elseif($MotifNouveau=="1"){
	$requete.="AND Motif <>'Renouvellement' ";
}
elseif($MotifRenouvellement=="1"){
	$requete.="AND Motif ='Renouvellement' ";
}
if($Id_Presta<>""){$requete.="AND CONCAT(form_besoin.Id_Prestation,'_',form_besoin.Id_Pole) IN (".$Id_Presta.")";}
if($Id_RespProjet<>""){
	$requete.="AND CONCAT(form_besoin.Id_Prestation,'_',form_besoin.Id_Pole) 
				IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
					FROM new_competences_personne_poste_prestation
					WHERE Id_Personne IN (".$Id_RespProjet.")
					AND Id_Poste IN (".$IdPosteResponsableProjet.")
				)
				";
}
if($Id_Formation<>"" && $Id_Formation<>"0_0"){
	$tabQual=explode("_",$Id_Formation);
	if($tabQual[1]==0){
		$requete.=" AND Id_Formation=".$tabQual[0]." ";
	}
	else{
		$requete.=" AND Id_Formation IN 
			(SELECT Id_Formation 
			FROM form_formationequivalente_formationplateforme 
			WHERE Id_FormationEquivalente=".$tabQual[0].") ";
	}
}
$requete.=" ORDER BY LibelleFormation ";

$result=mysqli_query($bdd,$requete);
$nbenreg=mysqli_num_rows($result);
if($nbenreg>0){
	$ligne=1;
	while($row=mysqli_fetch_array($result)){
		$ligne++;
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['LibelleFormation']." ".$row['Organisme']));
		
		mysqli_data_seek($resultPresta,0);
		$Somme=0;
		if($nbenregPresta>0){
			$col="B";
			while($rowPresta=mysqli_fetch_array($resultPresta)){
				$col++;
				
				$requete="	SELECT form_besoin.Id
					FROM
					form_besoin,
					form_typeformation,
					form_formation,
					new_competences_prestation
				WHERE
					form_besoin.Id_Formation=form_formation.Id
					AND form_formation.Id_TypeFormation=form_typeformation.Id
					AND form_besoin.Id_Prestation=new_competences_prestation.Id
					AND form_besoin.Traite=0 
					AND form_besoin.Suppr=0 
					AND form_besoin.Valide IN (0,1) 
					AND form_besoin.Id_Formation=".$row['Id_Formation']."
					AND new_competences_prestation.Id_Plateforme=".$Id_Plateforme."
					AND form_besoin.Id_Prestation=".$rowPresta['Id_Prestation']."
					AND form_besoin.Id_Pole=".$rowPresta['Id_Pole']."
					";
				if($Id_Type<>""){$requete.="AND Id_TypeFormation IN (".$Id_Type.") ";}
				if($row['RecyclageDifferent']==1){
					if($row['Recyclage']==0){
						$requete.="AND Motif <>'Renouvellement' ";
					}
					else{
						$requete.="AND Motif ='Renouvellement' ";
					}
				}
				if($MotifNouveau=="1" && $MotifRenouvellement=="1"){
				}
				elseif($MotifNouveau=="1"){
					$requete.="AND Motif <>'Renouvellement' ";
				}
				elseif($MotifRenouvellement=="1"){
					$requete.="AND Motif ='Renouvellement' ";
				}
				$resultTotal=mysqli_query($bdd,$requete);
				$nbTotal=mysqli_num_rows($resultTotal);
				$Somme+=$nbTotal;
				if($nbTotal>0){$sheet->setCellValue($col.$ligne,utf8_encode($nbTotal));}
				
			}
		}
		if($Somme>0){$sheet->setCellValue('B'.$ligne,utf8_encode($Somme));}
		
		
		$sheet->getStyle('A'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
		$sheet->getStyle('A'.$ligne)->getFont()->setBold(true);
		$sheet->getStyle('A'.$ligne)->getFont()->getColor()->setRGB('1f49a6');

		$sheet->getStyle('A'.$ligne.':'.$col.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':'.$col.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A'.$ligne.':'.$col.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
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
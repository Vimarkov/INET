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
if($LangueAffichage=="FR"){$sheet->setTitle(utf8_encode("Tableau Formation par métier"));}
else{$sheet->setTitle(utf8_encode("Table training by profession"));}

$tabPresta=explode("_",$_GET['Id_Prestation']);
$rqMetier="SELECT DISTINCT new_competences_metier.Id,new_competences_metier.Libelle ";
$rqMetier.=" FROM form_prestation_metier_formation LEFT JOIN new_competences_metier ";
$rqMetier.=" ON form_prestation_metier_formation.Id_Metier = new_competences_metier.Id ";
$rqMetier.=" WHERE form_prestation_metier_formation.Suppr=0 AND form_prestation_metier_formation.Id_Prestation=".$tabPresta[0];
$rqMetier.=" AND form_prestation_metier_formation.Id_Pole=".$tabPresta[1]." ORDER BY Libelle ASC";
$resultMetier=mysqli_query($bdd,$rqMetier);
$nbResultaMetier=mysqli_num_rows($resultMetier);

if($nbResultaMetier>0){
	$colonne="B";
	while($rowMetier=mysqli_fetch_array($resultMetier)){
		$sheet->setCellValue($colonne.'1',utf8_encode($rowMetier['Libelle']));
		$sheet->getColumnDimension($colonne)->setWidth(20);
		$colonne++;
	}
}

$requeteFormation="SELECT DISTINCT ";
$requeteFormation.="		form_formation.Id, ";
$requeteFormation.="		form_formation.Reference, ";
$requeteFormation.="		form_formation.Id_Plateforme ";
$requeteFormation.="FROM ";
$requeteFormation.="		form_formation, ";
$requeteFormation.="		form_formation_langue_infos, ";
$requeteFormation.="		form_formation_plateforme_parametres,
							form_prestation_metier_formation ";
$requeteFormation.="WHERE ";
$requeteFormation.="		form_formation.Suppr=0 
					AND form_prestation_metier_formation.Suppr=0 
					AND  form_prestation_metier_formation.Id_Formation = form_formation.Id 
					AND form_prestation_metier_formation.Id_Prestation=".$tabPresta[0]." 
					AND form_prestation_metier_formation.Id_Pole=".$tabPresta[1]." ";
$requeteFormation.="AND form_formation_langue_infos.Id_Formation = form_formation.Id ";
$requeteFormation.="AND form_formation_langue_infos.Suppr = 0 ";
$requeteFormation.="AND form_formation_langue_infos.Id_Langue = form_formation_plateforme_parametres.Id_Langue ";
$requeteFormation.="AND form_formation_plateforme_parametres.Id_Formation = form_formation.Id ";
$requeteFormation.="AND form_formation_plateforme_parametres.Id_Plateforme = (
						SELECT Id_Plateforme 
						FROM new_competences_prestation
						WHERE new_competences_prestation.Id=".$tabPresta[0]." 
					) 
					ORDER BY form_formation_langue_infos.Libelle ASC";
$resultFormation=mysqli_query($bdd,$requeteFormation);
$nbResultaFormation=mysqli_num_rows($resultFormation);

$result=mysqli_query($bdd,"SELECT Libelle,Id_Plateforme FROM new_competences_prestation WHERE Id=".$tabPresta[0]);
$rowPrestation=mysqli_fetch_array($result);

$Pole="";
$result=mysqli_query($bdd,"SELECT Libelle FROM new_competences_pole WHERE Id=".$tabPresta[1]);
$nbPole=mysqli_num_rows($result);
if($nbPole>0){
	$rowPole=mysqli_fetch_array($result);
	$Pole=" - ".$rowPole['Libelle'];
}

$sheet->setCellValue('A1',utf8_encode($rowPrestation['Libelle'].$Pole));

$sheet->getColumnDimension('A')->setWidth(30);

$rqMetierFormation="SELECT Id_Metier, Id_Formation, Obligatoire ";
$rqMetierFormation.=" FROM form_prestation_metier_formation ";
$rqMetierFormation.=" WHERE Suppr=0 AND form_prestation_metier_formation.Id_Prestation=".$tabPresta[0]." 
						AND Id_Pole=".$tabPresta[1];
$resultMetierFormation=mysqli_query($bdd,$rqMetierFormation);
$nbResultaMetierFormation=mysqli_num_rows($resultMetierFormation);

$ReqInfosFormations="
	SELECT
		Id_Formation,
		Id_Langue,
		Libelle,
		(SELECT Libelle FROM form_langue WHERE Id=Id_Langue) AS LANGUE
	FROM
		form_formation_langue_infos
	WHERE
		Suppr=0
	ORDER BY
		LANGUE";
$ResultInfosFormations=mysqli_query($bdd,$ReqInfosFormations);
$NbInfosFormations=mysqli_num_rows($ResultInfosFormations);

$ReqParametresFormations="
	SELECT
		Id_Formation,
		Id_Langue,
		(SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS ORGANISME,
		Id_Plateforme
	FROM
		form_formation_plateforme_parametres";
$ResultParametresFormations=mysqli_query($bdd,$ReqParametresFormations);
$NbParametresFormations=mysqli_num_rows($ResultParametresFormations);
$nbCoeff=0;
$ligne=2;
$colonne="A";
if($nbResultaFormation>0){
	while($rowFormation=mysqli_fetch_array($resultFormation)){
		
		$Organisme="";
		$Id_Langue=1;
		if($NbParametresFormations>0)
		{
			mysqli_data_seek($ResultParametresFormations,0);
			while($RowParametresFormations=mysqli_fetch_array($ResultParametresFormations))
			{
				if($RowParametresFormations['Id_Formation']==$rowFormation['Id'] && $RowParametresFormations['Id_Plateforme']==$rowPrestation['Id_Plateforme'])
				{
					if($RowParametresFormations['ORGANISME']!=NULL)
					{
						$Organisme=" (".$RowParametresFormations['ORGANISME'].")";
					}
					$Id_Langue=$RowParametresFormations['Id_Langue'];
					break;
				}
			}
		}
		
		$LibelleFormation="";
		if($NbInfosFormations>0)
		{
			mysqli_data_seek($ResultInfosFormations,0);
			while($RowInfosFormations=mysqli_fetch_array($ResultInfosFormations))
			{
				if($RowInfosFormations['Id_Formation']==$rowFormation['Id'] && $RowInfosFormations['Id_Langue']==$Id_Langue)
				{
					$LibelleFormation=$RowInfosFormations['Libelle'];
					break;
				}
			}
		}
		if($Organisme<>""){$LibelleFormation.=" ".$Organisme;}
		$sheet->setCellValue('A'.$ligne,utf8_encode($LibelleFormation." (".$rowFormation['Reference'].")"));
		if($nbResultaMetier>0){
			mysqli_data_seek($resultMetier,0);
			$colonne="A";
			while($rowMetier=mysqli_fetch_array($resultMetier)){
				$colonne++;
				mysqli_data_seek($resultMetierFormation,0);
				if($nbResultaMetierFormation>0){
					while($rowMetierFormation=mysqli_fetch_array($resultMetierFormation)){
						if($rowMetierFormation['Id_Metier']==$rowMetier['Id'] && $rowMetierFormation['Id_Formation']==$rowFormation['Id']){
							$obligatoire="";
							if($rowMetierFormation['Obligatoire']==1){
								if($LangueAffichage=="FR"){
									$obligatoire="O";
								}
								else{
									$obligatoire="M";
								}
								$sheet->getStyle($colonne.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'b8ff65'))));
							}
							else{
								if($LangueAffichage=="FR"){
									$obligatoire="F";
								}
								else{
									$obligatoire="O";
								}
								$sheet->getStyle($colonne.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'6fb6f5'))));
							}
							$sheet->setCellValue($colonne.$ligne,utf8_encode($obligatoire));
						}
					}
				}
			}
		}
		$ligne++;
	}
}
$ligne--;
if($ligne>0){
	$sheet->getStyle('A1:'.$colonne.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$sheet->getStyle('B1:'.$colonne.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('B1:'.$colonne.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
}
$sheet->getStyle('A1:'.$colonne.'1')->getAlignment()->setWrapText(true);
$sheet->getStyle('A1:'.$colonne.'1')->getFont()->setBold(true);
if($ligne>1){
	$sheet->getStyle('A2:A'.$ligne)->getFont()->setBold(true);
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){
	header('Content-Disposition: attachment;filename="Tableau des formations par metier.xlsx"');
}
else{
	header('Content-Disposition: attachment;filename="Table of training by profession.xlsx"');
}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Tableau des formations par metier.xlsx';

$writer->save($chemin);
readfile($chemin);
?>
<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

//Ligne En-tete
if($_SESSION['Langue']=="FR"){
	$sheet->setCellValue('A1',utf8_encode('Actif/Inactif'));
	$sheet->setCellValue('B1',utf8_encode("Unité d'exploitation"));
	$sheet->setCellValue('C1',utf8_encode('Projet'));
	$sheet->setCellValue('D1',utf8_encode('Domaine'));
	$sheet->setCellValue('E1',utf8_encode('Prestation'));
	$sheet->setCellValue('F1',utf8_encode('Sous Surveillance'));
	$sheet->setCellValue('G1',utf8_encode('Famille R03'));
	$sheet->setCellValue('H1',utf8_encode('N+1'));
	$sheet->setCellValue('I1',utf8_encode('N+2'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Active / Inactive'));
	$sheet->setCellValue('B1',utf8_encode('Operating unit'));
	$sheet->setCellValue('C1',utf8_encode('Project'));
	$sheet->setCellValue('D1',utf8_encode('Domain'));
	$sheet->setCellValue('E1',utf8_encode('Site'));
	$sheet->setCellValue('F1',utf8_encode('Under Surveillance'));
	$sheet->setCellValue('G1',utf8_encode('R03 family'));
	$sheet->setCellValue('H1',utf8_encode('N+1'));
	$sheet->setCellValue('I1',utf8_encode('N+2'));
}
$sheet->getStyle('A1:I1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getColumnDimension('A')->setWidth(25);
$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(40);
$sheet->getColumnDimension('D')->setWidth(40);
$sheet->getColumnDimension('E')->setWidth(50);
$sheet->getColumnDimension('F')->setWidth(100);

$Id_Plateformes=0;
if(isset($_SESSION['Id_Plateformes'])){$Id_Plateformes=implode(",",$_SESSION['Id_Plateformes']);}
$requete2="
	SELECT
		new_competences_prestation.Id AS ID_PRESTATION,
		new_competences_prestation.Libelle AS LIBELLE_PRESTATION,
		new_competences_plateforme.Libelle AS LIBELLE_PLATEFORME,
		new_competences_projet.Libelle AS LIBELLE_PROJET,
		new_competences_prestation.Active AS PRESTATION_ACTIVE,
		(SELECT Libelle FROM rh_domaine WHERE rh_domaine.Id=new_competences_prestation.Id_Domaine) AS LIBELLE_DOMAINE,
		new_competences_prestation.SousSurveillance AS SOUSSURVEILLANCE,
		(SELECT CONCAT(Num,' - ',Libelle) FROM moris_famille_r03 WHERE moris_famille_r03.Id=new_competences_prestation.Id_FamilleR03) AS FamilleR03,
		(SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) FROM new_competences_personne_poste_prestation WHERE Id_Prestation=new_competences_prestation.Id AND Id_Poste=1 ORDER BY Backup LIMIT 1) AS N1,
		(SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) FROM new_competences_personne_poste_prestation WHERE Id_Prestation=new_competences_prestation.Id AND Id_Poste=2 ORDER BY Backup LIMIT 1) AS N2
	FROM
		new_competences_prestation
		LEFT JOIN new_competences_plateforme ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
		LEFT JOIN new_competences_projet ON new_competences_prestation.Id_Projet=new_competences_projet.Id
	WHERE
		new_competences_prestation.Id_Plateforme IN (".$Id_Plateformes.")
	ORDER BY
		new_competences_plateforme.Libelle ASC,
		new_competences_prestation.Active DESC,
		new_competences_prestation.Libelle ASC";
$result=mysqli_query($bdd,$requete2);
$nb=mysqli_num_rows($result);
if($nb>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($result))
	{
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}
		if($row['PRESTATION_ACTIVE']==0){$actif="A";}
		else{$actif="I";}
		$sheet->setCellValue('A'.$ligne,utf8_encode($actif));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['LIBELLE_PLATEFORME'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['LIBELLE_PROJET'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['LIBELLE_DOMAINE'])));
		$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($row['LIBELLE_PRESTATION'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row['SOUSSURVEILLANCE'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($row['FamilleR03'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($row['N1'])));
		$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($row['N2'])));
		
		$sheet->getStyle('A'.$ligne.':I'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Prestations.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Prestations.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
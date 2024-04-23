<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
require("Fonctions_Planning.php");

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

//Ligne En-tete
if($_SESSION['Langue']=="FR"){
	$sheet->setCellValue('A1',utf8_encode('Personne'));
	$sheet->setCellValue('B1',utf8_encode('Métier'));
	$sheet->setCellValue('C1',utf8_encode('Affaire'));
	$sheet->setCellValue('D1',utf8_encode('Heures'));
	$sheet->setCellValue('F1',utf8_encode("Unité d'exploitation : "));
	$sheet->setCellValue('F2',utf8_encode('Mois : '));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Person'));
	$sheet->setCellValue('B1',utf8_encode('Job'));
	$sheet->setCellValue('C1',utf8_encode('Site'));
	$sheet->setCellValue('D1',utf8_encode('Hours'));
	$sheet->setCellValue('F1',utf8_encode('Operating unit : '));
	$sheet->setCellValue('F2',utf8_encode('Month : '));
}
$sheet->getStyle('A1:F1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffc000'))));
$sheet->getStyle('F2')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffc000'))));
$sheet->getStyle('F1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffc000'))));
$sheet->getStyle('F2')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffc000'))));

$sheet->getDefaultColumnDimension()->setWidth(20);

$annee=$_SESSION['FiltreRHRepartitionAAA_Annee'];
$mois=$_SESSION['FiltreRHRepartitionAAA_Mois'];
$PrestationSelect=$_SESSION['FiltreRHRepartitionAAA_Prestation'];

$req="SELECT Libelle FROM new_competences_prestation WHERE Id=".$PrestationSelect;
$result2=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result2);


if($nbResulta>0){
	$row2=mysqli_fetch_array($result2);
	$sheet->setCellValue('G1',utf8_encode($row2['Libelle']));
}
$sheet->setCellValue('G2',utf8_encode($mois."/".$annee));
	

$dateDebut=date($annee."-".$mois."-01");;
$dateFin = $dateDebut;

$tabDateFin = explode('-', $dateFin);
$timestampFin = mktime(0, 0, 0, $tabDateFin[1]+1, $tabDateFin[2], $tabDateFin[0]);
$dateFin = date("Y-m-d", $timestampFin);

$req = "SELECT DISTINCT new_rh_etatcivil.Id, 
	CONCAT(Nom,' ',Prenom) AS Personne,
	(SELECT (SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=rh_personne_contrat.Id_Metier)
	FROM rh_personne_contrat
	WHERE rh_personne_contrat.Suppr=0
	AND rh_personne_contrat.DateDebut<='".$dateFin."'
	AND (rh_personne_contrat.DateFin>='".$dateDebut."' OR rh_personne_contrat.DateFin<='0001-01-01')
	AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
	AND rh_personne_contrat.Id_Personne=new_rh_etatcivil.Id
	ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS Metier,
	new_rh_etatcivil.MatriculeAAA
FROM new_rh_etatcivil
LEFT JOIN rh_personne_mouvement 
ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
WHERE rh_personne_mouvement.DateDebut<'".$dateFin."'
AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
AND rh_personne_mouvement.EtatValidation=1 
AND rh_personne_mouvement.Suppr=0
AND rh_personne_mouvement.Id_Prestation=".$PrestationSelect." ";		
$requeteOrder="ORDER BY Personne ASC";

$result=mysqli_query($bdd,$req.$requeteOrder);
$nbResulta=mysqli_num_rows($result);
		
if($nbResulta>0){
	$ligne=2;
	while($row=mysqli_fetch_array($result))
	{
		$req = "SELECT DISTINCT rh_personne_mouvement.Id_Prestation,
			(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation
		FROM rh_personne_mouvement 
		WHERE rh_personne_mouvement.DateDebut<'".$dateFin."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
		AND rh_personne_mouvement.EtatValidation=1 
		AND rh_personne_mouvement.Suppr=0
		AND rh_personne_mouvement.Id_Personne=".$row['Id']."
		AND rh_personne_mouvement.Id_Prestation=".$PrestationSelect." ";		
		$requeteOrder="ORDER BY Prestation ASC";

		$resultPresta=mysqli_query($bdd,$req.$requeteOrder);
		$nbResultaPresta=mysqli_num_rows($resultPresta);
		if($nbResultaPresta>0){
			while($rowPresta=mysqli_fetch_array($resultPresta))
			{
				$NbHeures=0;
				for($laDate=$dateDebut;$laDate<$dateFin;$laDate=date('Y-m-d',strtotime($laDate." +1 day"))){
					$NbHeures=$NbHeures+NombreHeuresTotalJourneeRepartition($row['Id'],$laDate,$rowPresta['Id_Prestation']);
				}

				$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row['Personne'])));
				$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Metier'])));
				$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($rowPresta['Prestation'])));
				$sheet->setCellValue('D'.$ligne,utf8_encode($NbHeures));

				$ligne++;
			}
		}
	}
}



//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Repartition AAA.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Repartition AAA.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
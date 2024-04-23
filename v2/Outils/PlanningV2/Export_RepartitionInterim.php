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
	$sheet->setCellValue('B1',utf8_encode('Centre de coût'));
	$sheet->setCellValue('C1',utf8_encode('Affaire'));
	$sheet->setCellValue('D1',utf8_encode('EOTP'));
	$sheet->setCellValue('E1',utf8_encode('Heures'));
	$sheet->setCellValue('F1',utf8_encode('Total heures'));
	$sheet->setCellValue('G1',utf8_encode('Matricule DSK'));

	$sheet->setCellValue('I1',utf8_encode("Unité d'exploitation : "));
	$sheet->setCellValue('K1',utf8_encode('Mois : '));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Person'));
	$sheet->setCellValue('B1',utf8_encode('Cost center'));
	$sheet->setCellValue('C1',utf8_encode('Site'));
	$sheet->setCellValue('D1',utf8_encode('EOTP'));
	$sheet->setCellValue('E1',utf8_encode('Hours'));
	$sheet->setCellValue('F1',utf8_encode('Total hours'));
	$sheet->setCellValue('G1',utf8_encode('DSK Number'));
	
	$sheet->setCellValue('I1',utf8_encode('Operating unit : '));
	$sheet->setCellValue('K1',utf8_encode('Month : '));
}
$sheet->getStyle('A1:G1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffc000'))));
$sheet->getStyle('I1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffc000'))));
$sheet->getStyle('K1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffc000'))));

$sheet->getDefaultColumnDimension()->setWidth(20);

$annee=$_SESSION['FiltreRHRepartitionAAA_Annee'];
$mois=$_SESSION['FiltreRHRepartitionAAA_Mois'];
$PlateformeSelect=$_SESSION['FiltreRHRepartitionAAA_Plateforme'];

$req="SELECT Libelle FROM new_competences_plateforme WHERE Id=".$PlateformeSelect;
$result2=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result2);


if($nbResulta>0){
	$row2=mysqli_fetch_array($result2);
	$sheet->setCellValue('J1',utf8_encode($row2['Libelle']));
}
$sheet->setCellValue('L1',utf8_encode($mois."/".$annee));
	

$dateDebut=date($annee."-".$mois."-01");;
$dateFin = $dateDebut;

$tabDateFin = explode('-', $dateFin);
$timestampFin = mktime(0, 0, 0, $tabDateFin[1]+1, $tabDateFin[2], $tabDateFin[0]);
$dateFin = date("Y-m-d", $timestampFin);

$req = "SELECT DISTINCT new_rh_etatcivil.Id, 
	CONCAT(Nom,' ',Prenom) AS Personne,
	new_rh_etatcivil.MatriculeDSK,MatriculeDaher,CentreDeCout
FROM new_rh_etatcivil
LEFT JOIN rh_personne_mouvement 
ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
WHERE rh_personne_mouvement.DateDebut<'".$dateFin."'
AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
AND rh_personne_mouvement.EtatValidation=1 
AND rh_personne_mouvement.Suppr=0
AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation)=".$PlateformeSelect." ";		
$requeteOrder="ORDER BY Personne ASC";

$result=mysqli_query($bdd,$req.$requeteOrder);
$nbResulta=mysqli_num_rows($result);
		
if($nbResulta>0){
	$ligne=2;
	while($row=mysqli_fetch_array($result))
	{
		$NbHeuresTotal=0;
		for($laDate=$dateDebut;$laDate<$dateFin;$laDate=date('Y-m-d',strtotime($laDate." +1 day"))){
			if(estInterim($laDate,$row['Id'])){
				$NbHeuresTotal+=NombreHeuresTotalJourneeRepartition($row['Id'],$laDate);
			}
		}
		
		$req = "SELECT DISTINCT rh_personne_mouvement.Id_Prestation,
			(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
			(SELECT EOTP FROM new_competences_prestation WHERE Id=Id_Prestation) AS EOTP
		FROM rh_personne_mouvement 
		WHERE rh_personne_mouvement.DateDebut<'".$dateFin."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
		AND rh_personne_mouvement.EtatValidation=1 
		AND rh_personne_mouvement.Suppr=0
		AND rh_personne_mouvement.Id_Personne=".$row['Id']."
		AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation)=".$PlateformeSelect." ";		
		$requeteOrder="ORDER BY Prestation ASC";

		$resultPresta=mysqli_query($bdd,$req.$requeteOrder);
		$nbResultaPresta=mysqli_num_rows($resultPresta);
		if($nbResultaPresta>0){
			while($rowPresta=mysqli_fetch_array($resultPresta))
			{
				$NbHeures=0;
				for($laDate=$dateDebut;$laDate<$dateFin;$laDate=date('Y-m-d',strtotime($laDate." +1 day"))){
					if(estInterim($laDate,$row['Id'])){
						$NbHeures+=NombreHeuresTotalJourneeRepartition($row['Id'],$laDate,$rowPresta['Id_Prestation']);
					}
				}
				
				$pourcentage=0;
				if($NbHeuresTotal>0){
					$pourcentage=round($NbHeures/$NbHeuresTotal,2);
					
					$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row['Personne'])));
					$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['CentreDeCout'])));
					$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($rowPresta['Prestation'])));
					$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($rowPresta['EOTP'])));
					$sheet->setCellValue('E'.$ligne,utf8_encode($NbHeures));
					$sheet->setCellValue('F'.$ligne,utf8_encode($NbHeuresTotal));
					
					$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($row['MatriculeDSK'])));
					
					$ligne++;
				}
			}
		}
	}
}



//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Repartition Interim.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Repartition Interim.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
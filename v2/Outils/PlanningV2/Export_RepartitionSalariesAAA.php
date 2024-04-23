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

$sheet2 = $workbook->createSheet(1);

//Ligne En-tete
if($_SESSION['Langue']=="FR"){
	
	$sheet->setCellValue('A1',utf8_encode('Matricule AAA'));
	$sheet->setCellValue('B1',utf8_encode('Matricule DSK'));
	$sheet->setCellValue('C1',utf8_encode('Matricule Daher'));
	$sheet->setCellValue('D1',utf8_encode('Personne'));
	$sheet->setCellValue('E1',utf8_encode('Affaire'));
	$sheet->setCellValue('F1',utf8_encode('Centre de coût'));
	$sheet->setCellValue('G1',utf8_encode('Type'));
	$sheet->setCellValue('H1',utf8_encode('EOTP'));
	$sheet->setCellValue('I1',utf8_encode('Texte de poste'));
	$sheet->setCellValue('J1',utf8_encode('Heures'));
	$sheet->setCellValue('K1',utf8_encode('Total heures'));
	

	$sheet->setCellValue('L1',utf8_encode('Site : '));
	$sheet->setCellValue('N1',utf8_encode('Mois : '));
}
else{
	$sheet->setCellValue('A1',utf8_encode('AAA Number'));
	$sheet->setCellValue('B1',utf8_encode('DSK Number'));
	$sheet->setCellValue('C1',utf8_encode('Daher Number'));
	$sheet->setCellValue('D1',utf8_encode('Person'));
	$sheet->setCellValue('E1',utf8_encode('Site'));
	$sheet->setCellValue('F1',utf8_encode('Cost center'));
	$sheet->setCellValue('G1',utf8_encode('Type'));
	$sheet->setCellValue('H1',utf8_encode('EOTP'));
	$sheet->setCellValue('I1',utf8_encode('Job text'));
	$sheet->setCellValue('J1',utf8_encode('Hours'));
	$sheet->setCellValue('K1',utf8_encode('Total hours'));
	
	
	
	$sheet->setCellValue('L1',utf8_encode('Site : '));
	$sheet->setCellValue('N1',utf8_encode('Month : '));
}
$sheet->getStyle('A1:K1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffc000'))));
$sheet->getStyle('L1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffc000'))));
$sheet->getStyle('N1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffc000'))));

$sheet->getDefaultColumnDimension()->setWidth(20);

$annee=$_SESSION['FiltreRHRepartitionAAA_Annee'];
$mois=$_SESSION['FiltreRHRepartitionAAA_Mois'];
$PlateformeSelect=$_SESSION['FiltreRHRepartitionAAA_Plateforme'];

$req="SELECT Libelle FROM new_competences_plateforme WHERE Id=".$PlateformeSelect;
$result2=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result2);


if($nbResulta>0){
	$row2=mysqli_fetch_array($result2);
	$sheet->setCellValue('M1',utf8_encode($row2['Libelle']));
}
$sheet->setCellValue('O1',utf8_encode($mois."/".$annee));
	

$dateDebut=date($annee."-".$mois."-01");;
$dateFin = $dateDebut;

$tabDateFin = explode('-', $dateFin);
$timestampFin = mktime(0, 0, 0, $tabDateFin[1]+1, $tabDateFin[2], $tabDateFin[0]);
$dateFin = date("Y-m-d", $timestampFin);

$req = "SELECT DISTINCT new_rh_etatcivil.Id, 
	CONCAT(Nom,' ',Prenom) AS Personne,
	new_rh_etatcivil.MatriculeAAA,new_rh_etatcivil.MatriculeDSK,
	MatriculeDaher,CentreDeCout
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
		$NbHeuresTotalSalarie=0;
		$NbHeuresTotalInterim=0;
		for($laDate=$dateDebut;$laDate<$dateFin;$laDate=date('Y-m-d',strtotime($laDate." +1 day"))){
			if(estSalarie($laDate,$row['Id']) || estInterne($laDate,$row['Id'])){
				$NbHeuresTotalSalarie=$NbHeuresTotalSalarie+NombreHeuresTotalJourneeRepartition($row['Id'],$laDate);
			}
			elseif(estInterim($laDate,$row['Id'])){
				$NbHeuresTotalInterim=$NbHeuresTotalInterim+NombreHeuresTotalJourneeRepartition($row['Id'],$laDate);
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
				$NbHeuresSalarie=0;
				$NbHeuresInterim=0;
				for($laDate=$dateDebut;$laDate<$dateFin;$laDate=date('Y-m-d',strtotime($laDate." +1 day"))){
					if(estSalarie($laDate,$row['Id']) || estInterne($laDate,$row['Id'])){
						$NbHeuresSalarie=$NbHeuresSalarie+NombreHeuresTotalJourneeRepartition($row['Id'],$laDate,$rowPresta['Id_Prestation']);
					}
					elseif(estInterim($laDate,$row['Id'])){
						$NbHeuresInterim=$NbHeuresInterim+NombreHeuresTotalJourneeRepartition($row['Id'],$laDate,$rowPresta['Id_Prestation']);
					}
				}

				if($NbHeuresTotalSalarie>0){
					$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row['MatriculeAAA'])));

					$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['MatriculeDaher'])));
					$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['Personne'])));
					$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($rowPresta['Prestation'])));
					$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row['CentreDeCout'])));
					$sheet->setCellValue('G'.$ligne,utf8_encode("A0220"));
					$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($rowPresta['EOTP'])));
					$sheet->setCellValue('I'.$ligne,utf8_encode(""));
					$sheet->setCellValue('J'.$ligne,utf8_encode($NbHeuresSalarie));
					$sheet->setCellValue('K'.$ligne,utf8_encode($NbHeuresTotalSalarie));
					$ligne++;
				}
				if($NbHeuresTotalInterim>0){
					$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['MatriculeDSK'])));

					$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['Personne'])));
					$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($rowPresta['Prestation'])));
					$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row['CentreDeCout'])));
					$sheet->setCellValue('G'.$ligne,utf8_encode("A0221"));
					$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($rowPresta['EOTP'])));
					$sheet->setCellValue('I'.$ligne,utf8_encode(""));
					$sheet->setCellValue('J'.$ligne,utf8_encode($NbHeuresInterim));
					$sheet->setCellValue('K'.$ligne,utf8_encode($NbHeuresTotalInterim));
					$ligne++;
				}
			}
		}
	}
}


//Liste des prestations de la plateforme avec les EOTP

//Ligne En-tete
if($_SESSION['Langue']=="FR"){
	
	$sheet2->setCellValue('A1',utf8_encode('Prestation'));
	$sheet2->setCellValue('B1',utf8_encode('EOTP'));
}
else{
	$sheet2->setCellValue('A1',utf8_encode('Site'));
	$sheet2->setCellValue('B1',utf8_encode('EOTP'));
}
$sheet2->getStyle('A1:B1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffc000'))));

$sheet2->getDefaultColumnDimension()->setWidth(20);

$req = "SELECT LEFT(Libelle,7) AS Libelle,EOTP
FROM new_competences_prestation
WHERE Active=0
AND Id_Plateforme=".$_SESSION['FiltreRHRepartitionAAA_Plateforme']."
ORDER BY Libelle ";		
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
		
if($nbResulta>0){
	$ligne=2;
	while($row=mysqli_fetch_array($result))
	{
		$sheet2->setCellValue('A'.$ligne,utf8_encode(stripslashes($row['Libelle'])));
		$sheet2->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['EOTP'])));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Ventilation.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Ventilation.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
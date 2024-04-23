<?php
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

$IdPlateformes = $_GET['Id_Plateformes'];
$tabPlateforme = explode(";",$IdPlateformes);

$resultPla=mysqli_query($bdd,"SELECT new_planning_vacationabsence.Id, new_planning_vacationabsence.Nom FROM new_planning_vacationabsence WHERE new_planning_vacationabsence.AbsenceVacation=1 ORDER BY new_planning_vacationabsence.Nom ASC");
$nbenreg=mysqli_num_rows($resultPla);
$colonne = "C";
if($nbenreg>0)
{
	while($row=mysqli_fetch_array($resultPla)){
		$colonneDebut = $colonne;
		$sheet->getColumnDimension($colonne)->setWidth(8);
		$sheet->setCellValue($colonne.'2',utf8_encode('L'));
		$colonne++;
		$sheet->getColumnDimension($colonne)->setWidth(8);
		$sheet->setCellValue($colonne.'2',utf8_encode('M'));
		$colonne++;
		$sheet->getColumnDimension($colonne)->setWidth(8);
		$sheet->setCellValue($colonne.'2',utf8_encode('M'));
		$colonne++;
		$sheet->getColumnDimension($colonne)->setWidth(8);
		$sheet->setCellValue($colonne.'2',utf8_encode('J'));
		$colonne++;
		$sheet->getColumnDimension($colonne)->setWidth(8);
		$sheet->setCellValue($colonne.'2',utf8_encode('V'));
		$colonne++;
		$sheet->getColumnDimension($colonne)->setWidth(8);
		$sheet->setCellValue($colonne.'2',utf8_encode('S'));
		$colonne++;
		$sheet->getColumnDimension($colonne)->setWidth(8);
		$sheet->setCellValue($colonne.'2',utf8_encode('D'));
		
		$sheet->setCellValue($colonneDebut.'1',utf8_encode($row['Nom']));
		$sheet->mergeCells($colonneDebut.'1:'.$colonne.'1');
		$sheet->getStyle($colonneDebut.'1:'.$colonne.'2')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'93c6fa'))));
		$colonne++;
	}
}

$req = "SELECT new_competences_prestation.Id, new_competences_prestation.Libelle AS NomPrestation, new_competences_plateforme.Libelle FROM new_competences_prestation INNER JOIN new_competences_plateforme ON new_competences_prestation.Id_Plateforme = new_competences_plateforme.Id WHERE ";
foreach ($tabPlateforme as $value) {
	if ($value <> ""){$req .= " new_competences_prestation.Id_Plateforme=".$value." OR";}
}
$req =  substr($req, 0, -2)."" ;
$req .= " ORDER BY new_competences_plateforme.Libelle ASC, new_competences_prestation.libelle ASC;";
$result=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($result);

$reqVac = "SELECT Id_Prestation, Id_Vacation, JourSemaine, NbHeureJour, NbHeureEquipeJour, NbHeureEquipeNuit, NbHeurePause FROM new_planning_prestation_vacation ";
$resultVac=mysqli_query($bdd,$reqVac);
$nbVac=mysqli_num_rows($resultVac);

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(10);

if ($nbenreg > 0){
	$ligne = 3;
	while($row=mysqli_fetch_array($result)){
		$ligneDebut = $ligne;
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['NomPrestation']));

		mysqli_data_seek($resultPla,0);
		$colonneVac = 2;
		while($rowPla=mysqli_fetch_array($resultPla)){
			mysqli_data_seek($resultVac,0);
			while($rowVac=mysqli_fetch_array($resultVac)){
				if($rowVac['Id_Vacation'] == $rowPla['Id'] && $rowVac['Id_Prestation'] == $row['Id'] ){
					if ($rowVac['JourSemaine'] == 0){
						$lacolonne = $colonneVac + 6;
					}
					else{
						$lacolonne = $colonneVac + $rowVac['JourSemaine'] - 1;
					}
					$sheet->setCellValueByColumnAndRow($lacolonne,$ligne,utf8_encode($rowVac['NbHeureJour']));
					$sheet->setCellValueByColumnAndRow($lacolonne,$ligne + 1,utf8_encode($rowVac['NbHeureEquipeJour']));
					$sheet->setCellValueByColumnAndRow($lacolonne,$ligne + 2,utf8_encode($rowVac['NbHeureEquipeNuit']));
					$sheet->setCellValueByColumnAndRow($lacolonne,$ligne + 3,utf8_encode($rowVac['NbHeurePause']));
				}
			}
			$colonneVac = $colonneVac + 7;
			
		}
		$sheet->setCellValue('B'.$ligne,utf8_encode('J'));
		$ligne++;
		$sheet->setCellValue('B'.$ligne,utf8_encode('EJ'));
		$ligne++;
		$sheet->setCellValue('B'.$ligne,utf8_encode('EN'));
		$ligne++;
		$sheet->setCellValue('B'.$ligne,utf8_encode('Pause'));
		//$sheet->mergeCells('A'.$ligneDebut.':A'.$ligne);
		//$sheet->getStyle('A'.$ligneDebut.':B'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'93c6fa'))));
		$ligne++;
	}
}

//$sheet->getStyle('A1:'.$colonne.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//$sheet->getStyle('A1:A'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
//$sheet->getStyle('A1:'.$colonne.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$ligne = $ligne - 1;
//$sheet->getStyle('A1:'.$colonne.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="VacationPrestation.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/VacationPrestation.xlsx';
$writer->save($chemin);
readfile($chemin);

mysqli_close($bdd);					// Fermeture de la connexion
?>
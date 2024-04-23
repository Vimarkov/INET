<?php
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

$req="SELECT Id, Libelle FROM new_competences_poste WHERE Id IN (6,9,11,13,14,15,16,17,18,19,21,23,25,26,27,28,30,31,32,33,34,36,37,38,39,40,41,42,43,44,45,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64) ORDER BY Libelle ";
$resultPoste=mysqli_query($bdd,$req);
$nbPoste=mysqli_num_rows($resultPoste);

$requete="SELECT Id_Poste,Id_Personne,Backup, ";
$requete.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=new_competences_personne_poste_plateforme.Id_Personne) AS Personne ";
$requete.="FROM new_competences_personne_poste_plateforme ";
$requete.="WHERE Id_Plateforme =".$_GET['Id_Plateforme']." ";
$requete.="ORDER BY Backup,Personne ";
$result=mysqli_query($bdd,$requete);
$nbenreg=mysqli_num_rows($result);

if($nbPoste>0){
	
	$sheet->getDefaultColumnDimension()->setWidth(20);
	$sheet->getColumnDimension('A')->setWidth(25);
	$sheet->setCellValue('A1','Poste');
	$sheet->setCellValue('B1','Responsable');
	$sheet->setCellValue('C1','Backup 1');
	$sheet->setCellValue('D1','Backup 2');
	$sheet->setCellValue('E1','Backup 3');
	$sheet->setCellValue('F1','Backup 4');
	$sheet->setCellValue('G1','Backup 5');
	$sheet->setCellValue('H1','Backup 6');
	$sheet->setCellValue('I1','Backup 7');
	$ligne=2;
	while($rowPoste=mysqli_fetch_array($resultPoste)){
		$sheet->setCellValue("A".$ligne,utf8_encode($rowPoste['Libelle']));
		$resp="";
		$backup1="";
		$backup2="";
		$backup3="";
		$backup4="";
		$backup5="";
		$backup6="";
		$backup7="";
		if($nbenreg>0){
			mysqli_data_seek($result,0);
			while($row=mysqli_fetch_array($result)){
				if($row['Id_Poste']==$rowPoste['Id']){
					switch($row['Backup']){
					case 0:
						$resp=$row['Personne'];
						break;
					case 1:
						$backup1=$row['Personne'];
						break;
					case 2:
						$backup2=$row['Personne'];
						break;
					case 3:
						$backup3=$row['Personne'];
						break;
					case 4:
						$backup4=$row['Personne'];
						break;
					case 5:
						$backup5=$row['Personne'];
						break;
					case 6:
						$backup6=$row['Personne'];
						break;
					case 7:
						$backup7=$row['Personne'];
						break;
					}
				}
			}
		}
		$sheet->setCellValue("B".$ligne,utf8_encode($resp));
		$sheet->setCellValue("C".$ligne,utf8_encode($backup1));
		$sheet->setCellValue("D".$ligne,utf8_encode($backup2));
		$sheet->setCellValue("E".$ligne,utf8_encode($backup3));
		$sheet->setCellValue("F".$ligne,utf8_encode($backup4));
		$sheet->setCellValue("G".$ligne,utf8_encode($backup5));
		$sheet->setCellValue("H".$ligne,utf8_encode($backup6));
		$sheet->setCellValue("I".$ligne,utf8_encode($backup7));
		$ligne++;
	}
}

$sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:I1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:I1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));
 
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="HierarchiePlateforme.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/HierarchiePlateforme.xlsx';
$writer->save($chemin);
readfile($chemin);

mysqli_free_result($result);	// Libération des résultats
mysqli_close($bdd);	// Fermeture de la connexion
?>
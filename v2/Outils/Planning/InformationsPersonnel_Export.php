<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require '../Fonctions.php';

$IdPrestation = $_GET['Id_Prestation'];
$IdPole = $_GET['Id_Pole'];

$reqPresta = "SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id ='".$IdPrestation."';";
$resultPresta=mysqli_query($bdd,$reqPresta);
$nbPresta=mysqli_num_rows($resultPresta);


//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

$presta = "";
if ($nbPresta > 0){
	$rowPresta=mysqli_fetch_array($resultPresta);
	$presta = "_".$rowPresta[0];
}

$reqPole = "SELECT new_competences_pole.Id, new_competences_pole.Libelle FROM new_competences_pole ";
$reqPole .= "WHERE new_competences_pole.Id_Prestation =".$IdPrestation.";";
$resultPole=mysqli_query($bdd,$reqPole);
$nbPole=mysqli_num_rows($resultPole);

//Ligne En-tete
$sheet->setCellValue('A1','Personne');
$sheet->setCellValue('B1',utf8_encode('Métier'));
$sheet->setCellValue('C1',utf8_encode('Date de naissance'));
$sheet->setCellValue('D1',utf8_encode('Contrat'));;
$sheet->setCellValue('E1',utf8_encode('Tel. pro fixe'));
$sheet->setCellValue('F1',utf8_encode('Tel. pro mobile'));
$sheet->setCellValue('G1',utf8_encode('Email'));
$sheet->setCellValue('H1',utf8_encode('N° badge'));
$sheet->setCellValue('I1',utf8_encode('NG/ST'));
if ($nbPole > 0){
	$sheet->setCellValue('J1',utf8_encode('Pôles'));
	$sheet->getStyle('A1:J1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));
}
else{
	$sheet->getStyle('A1:I1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));
}

$sheet->getDefaultColumnDimension()->setWidth(20);

//Personnes  présentent sur cette prestation à ce jour
$req = "SELECT DISTINCT(new_competences_personne_prestation.Id_Personne), ";
$req .= "CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne, ";
$req .= "(SELECT new_competences_metier.Libelle FROM new_competences_metier WHERE new_competences_metier.Id = new_competences_personne_metier.Id_Metier) AS Metier, ";
$req .= "(SELECT new_competences_metier.Code FROM new_competences_metier WHERE new_competences_metier.Id = new_competences_personne_metier.Id_Metier) AS CodeMetier, ";
$req .= "new_rh_etatcivil.TelephoneProFixe, ";
$req .= "new_rh_etatcivil.TelephoneProMobil, ";
$req .= "new_rh_etatcivil.EmailPro, ";
$req .= "new_rh_etatcivil.NumBadge, ";
$req .= "new_rh_etatcivil.Matricule, ";
$req .= "new_rh_etatcivil.Contrat, ";
$req .= "new_rh_etatcivil.Date_Naissance ";
$req .= "FROM (new_competences_personne_prestation RIGHT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id = new_competences_personne_prestation.Id_Personne) ";
$req .= "LEFT JOIN new_competences_personne_metier ON new_competences_personne_metier.Id_Personne = new_rh_etatcivil.Id ";
$req .= "WHERE new_competences_personne_prestation.Id_Prestation =".$IdPrestation." ";
if ($IdPole > 0){$req .= "AND new_competences_personne_prestation.Id_Pole =".$IdPole." ";}
$req .= "AND ((new_competences_personne_prestation.Date_Debut<='".$DateJour."' AND new_competences_personne_prestation.Date_Fin>='".$DateJour."') ";
$req .= "OR (new_competences_personne_prestation.Date_Debut<='".$DateJour."' AND new_competences_personne_prestation.Date_Fin>='".$DateJour."') ";
$req .= "OR (new_competences_personne_prestation.Date_Debut>='".$DateJour."' AND new_competences_personne_prestation.Date_Fin<='".$DateJour."')) ORDER BY Personne ASC;";
$resultPersonne=mysqli_query($bdd,$req);
$nbPersonne=mysqli_num_rows($resultPersonne);
	
if($nbPersonne>0){
	$Couleur="EEEEEE";
	$Plateforme=0;
	$idPrestation=0;
	$ligne = 2;
	while($row=mysqli_fetch_array($resultPersonne)){
		if($Couleur=="EEEEEE"){$Couleur="FFFFFF";}
		else{$Couleur="EEEEEE";}
		
		
		$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode($row['Personne'])); //Personne
		$sheet->setCellValueByColumnAndRow(1,$ligne,utf8_encode($row['CodeMetier'])); //Métier
		$sheet->setCellValueByColumnAndRow(2,$ligne,utf8_encode(AfficheDateFR($row['Date_Naissance']))); //Tel pro fixe
		$sheet->setCellValueByColumnAndRow(3,$ligne,utf8_encode($row['Contrat'])); //Contrat
		$sheet->setCellValueByColumnAndRow(4,$ligne,utf8_encode($row['TelephoneProFixe'])); //Tel pro fixe
		$sheet->setCellValueByColumnAndRow(5,$ligne,utf8_encode($row['TelephoneProMobil'])); //Tel pro mobile
		$sheet->setCellValueByColumnAndRow(6,$ligne,utf8_encode($row['EmailPro'])); //Email
		$sheet->setCellValueByColumnAndRow(7,$ligne,utf8_encode($row['NumBadge'])); //N° Badge
		$sheet->setCellValueByColumnAndRow(8,$ligne,utf8_encode($row['Matricule'])); //Matricule
		
		if ($nbPole > 0){
			//Recherche les pôles de la personne
			$reqPole = "SELECT DISTINCT new_competences_personne_prestation.Id_Pole, ";
			$reqPole .= "(SELECT Libelle FROM new_competences_pole WHERE Id=new_competences_personne_prestation.Id_Pole) AS Pole ";
			$reqPole .= "FROM new_competences_personne_prestation ";
			$reqPole .= "WHERE Id_Personne=".$row[0]." AND Id_Prestation=".$IdPrestation." ";
			$reqPole .= "AND ((new_competences_personne_prestation.Date_Debut<='".$DateDuJour."' AND new_competences_personne_prestation.Date_Fin>='".$DateDuJour."') ";
			$reqPole .= "OR (new_competences_personne_prestation.Date_Debut<='".$DateDuJour."' AND new_competences_personne_prestation.Date_Fin>='".$DateDuJour."') ";
			$reqPole .= "OR (new_competences_personne_prestation.Date_Debut>='".$DateDuJour."' AND new_competences_personne_prestation.Date_Fin<='".$DateDuJour."'));";
			
			$poleJour=mysqli_query($bdd,$reqPole);
			$nbPoleJour=mysqli_num_rows($poleJour);
			$poles="";
			while($rowPoles=mysqli_fetch_array($poleJour)){
				if($rowPoles['Id_Pole']<>0){
					$poles.=$rowPoles['Pole']."\n";
				}
			}
			if($poles<>""){$poles=substr($poles,0,-1);}
			$sheet->setCellValueByColumnAndRow(9,$ligne,utf8_encode($poles)); //Poles
			$sheet->getStyle('A'.$ligne.':J'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$Couleur))));
		}
		else{
			$sheet->getStyle('A'.$ligne.':I'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$Couleur))));
		}
			
		$ligne++;
	}
}	
	
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="InformationsPersonnel.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/InformationsPersonnel.xlsx';
$writer->save($chemin);
readfile($chemin);

mysqli_free_result($resultPersonne);	// Libération des résultats
mysqli_close($bdd);					// Fermeture de la connexion
?>
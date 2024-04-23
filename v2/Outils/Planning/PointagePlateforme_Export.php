<?php
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array('memoryCacheSize ' => '2048MB', 'cacheTime' => 12000);
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('heures');


$PlateformeSelect = 1;

$annee = date("Y", $_GET['lDate']);
$moisAffichage = date("m", $_GET['lDate']);
$DateCalcul = date("Y/m/1",$_GET['lDate']);
$tabDateCalcul = explode('/', $DateCalcul);
$timestampCalcul = mktime(0, 0, 0, $tabDateCalcul[1], $tabDateCalcul[2], $tabDateCalcul[0]);
$JourCalcul = date("w",$timestampCalcul);
$converJour = array(6, 0, 1, 2, 3, 4, 5);
$JourCalcul = 8 - $converJour[$JourCalcul];
$DateResult = date("Y/m/".$JourCalcul,$_GET['lDate']);
$tabDateResult = explode('/', $DateResult);
$timestampCalcul = mktime(0, 0, 0, $tabDateResult[1], $tabDateResult[2]-7, $tabDateResult[0]);
$DateResult = date("Y/m/d",$timestampCalcul);
$DateAffichageResult = date("d/m/Y",$timestampCalcul);

$dateDebut = $DateAffichageResult;
$dateFin = date("Y/m/1",$_GET['lDate']);
$tabDateFin = explode('/', $dateFin);
$timestampFin = mktime(0, 0, 0, $tabDateFin[1]+1, $tabDateFin[2], $tabDateFin[0]);
$dateFin = date("Y/m/d", $timestampFin);
$dateDeFin = date('d/m/Y', $timestampFin);
		
$tabDateDebut = explode('/', $dateDebut);
$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
$tmpDate = date("Y/m/d",$timestampDebut);

$tabDateFin = explode('/', $dateDeFin);
$timestampFin = mktime(0, 0, 0, $tabDateFin[1], $tabDateFin[0], $tabDateFin[2]);
$dateFin = date("Y/m/d", $timestampFin);

$tabDate = explode('/', $tmpDate);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
$tmpMois = date('n', $timestamp) . ' ' . date('Y', $timestamp);
$cptJour = 0;
$joursem = array("D", "L", "M", "M", "J", "V", "S");
$MoisLettre = array("JANVIER", "FEVRIER", "MARS", "AVRIL", "MAI", "JUIN", "JUILLET", "AOUT", "SEPTEMBRE", "OCTOBRE", "NOVEMBRE", "DECEMBRE");
// GESTION DES ENTETES DU TABLEAU (MOIS, SEMAINE ET JOUR)
$mois = 0;
$colonne=5;
$colonneL = "E";
$colDebutMois = "F";
$colFinMois = "F";
$colDernierMois = "E";
$colDebutSem = "F";
$colFinSem = "F";
$colDernierSem = "E";

while ($tmpDate < $dateFin) {
	$colDernierMois++;
	$colDernierSem++;
	$sheet->getColumnDimension($colDernierMois)->setWidth(5);
	$tabDate = explode('/', $tmpDate);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jour = date('w', $timestamp);
	$mois = $tabDate[1];
	$semaine = date('W', $timestamp);
	
	$sheet->setCellValueByColumnAndRow($colonne,3,utf8_encode($joursem[$jour]));
	$sheet->setCellValueByColumnAndRow($colonne,4,utf8_encode($tabDate[2]));

	//Jour suivant
	$tabDate = explode('/', $tmpDate);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
	$tmpDate = date("Y/m/d", $timestamp);
	
	if (date('m', $timestamp) <> $tabDate[1]){
		$sheet->mergeCells($colDebutMois.'1:'.$colFinMois.'1');
		$sheet->setCellValue($colDebutMois.'1',utf8_encode($MoisLettre[$mois-1]." ".$tabDate[0]));
		$colFinMois++;
		$colDebutMois = $colFinMois;
	}
	else{
		$colFinMois++;
	}
	if (date('W', $timestamp) <> $semaine){
		$sheet->mergeCells($colDebutSem.'2:'.$colFinSem.'2');
		$sheet->setCellValue($colDebutSem.'2',utf8_encode("S".$semaine.""));
		$colFinSem++;
		$colDebutSem = $colFinSem;
	}
	else{
		$colFinSem++;
	}
	$cptJour++;
	$colonne++;
	$colonneL++;
}

if (date('m', $timestamp) == $tabDate[1]){
	$sheet->mergeCells($colDebutMois.'1:'.$colDernierMois.'1');
	$sheet->setCellValue($colDebutMois.'1',utf8_encode($MoisLettre[$mois-1]." ".$tabDate[0]));
}

if ($joursem[$jour]<>"D"){
	$colFinSem++;
	$sheet->mergeCells($colDebutSem.'2:'.$colDernierSem.'2');
	$sheet->setCellValue($colDebutSem.'2',utf8_encode("S".$semaine.""));
}

$colonneL++;
$colonneDivers = 47;
$sheet->setCellValueByColumnAndRow($colonneDivers,1,"DIVERS");
$colonneL = "AV";
$sheet->mergeCells($colonneL.'1:'.$colonneL.'4');

$sheet->getColumnDimension('A')->setWidth(5);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(18);
$sheet->getColumnDimension('E')->setWidth(0);

$sheet->getStyle('F1:'.$colonneL.'4')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:'.$colonneL.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('F3:'.$colonneL.'4')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffcc99'))));

$sheet->getStyle('A1:E4')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'c0c0c0'))));

$sheet->setCellValue('B1',utf8_encode('ANNEE'));
$sheet->setCellValue('B2',utf8_encode('MOIS'));
$sheet->setCellValue('B3',utf8_encode('CODE SITE'));
$sheet->getStyle('B1:B3')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'cbffcb'))));

$sheet->setCellValue('C1',utf8_encode($annee));
$sheet->setCellValue('C2',utf8_encode($moisAffichage));
$sheet->getStyle('C1:C3')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffffff'))));

$sheet->setCellValue('E2','0');

$sheet->setCellValue('B4',utf8_encode("Nom"));
$sheet->setCellValue('C4',utf8_encode('Prénom'));
$sheet->setCellValue('E4',utf8_encode($DateAffichageResult));
// FIN GESTION DES ENTETES DU TABLEAU

//DEBUT CORPS DU TABLEAU
$tabDateDebut = explode('/', $dateDebut);
$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
$tmpDate = date("Y/m/d",$timestampDebut);

$tabDateFin = explode('/', $dateDeFin);
$timestampFin = mktime(0, 0, 0, $tabDateFin[1], $tabDateFin[0], $tabDateFin[2]);
$dateFin = date("Y/m/d", $timestampFin);

//Personnes  présentent sur cette plateforme à  ces dates
$req = "SELECT DISTINCT(new_competences_personne_prestation.Id_Personne), ";
$req .= "new_rh_etatcivil.Nom AS Nom, ";
$req .= "(SELECT new_competences_metier.Libelle FROM new_competences_metier WHERE new_competences_metier.Id = new_competences_personne_metier.Id_Metier) AS Metier, ";
$req .= "new_rh_etatcivil.Prenom AS Prenom ";
$req .= "FROM (new_competences_personne_prestation RIGHT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id = new_competences_personne_prestation.Id_Personne) ";
$req .= "LEFT JOIN new_competences_personne_metier ON new_competences_personne_metier.Id_Personne = new_rh_etatcivil.Id ";
$req .= "WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_prestation.Id_Prestation) =".$PlateformeSelect." 
		AND (SELECT COUNT(new_planning_personne_vacationabsence.Id) 
			FROM new_planning_personne_vacationabsence
			WHERE new_planning_personne_vacationabsence.Id_Personne=new_competences_personne_prestation.Id_Personne 
			AND new_planning_personne_vacationabsence.DatePlanning>='".$tmpDate."'
			AND new_planning_personne_vacationabsence.DatePlanning<='".$dateFin."'
			AND new_planning_personne_vacationabsence.ValidationResponsable = 1)>0 ";
$req .= "AND ((new_competences_personne_prestation.Date_Debut<='".$tmpDate."' AND new_competences_personne_prestation.Date_Fin>='".$tmpDate."') ";
$req .= "OR (new_competences_personne_prestation.Date_Debut<='".$dateFin."' AND new_competences_personne_prestation.Date_Fin>='".$dateFin."') ";
$req .= "OR (new_competences_personne_prestation.Date_Debut>='".$tmpDate."' AND new_competences_personne_prestation.Date_Fin<='".$dateFin."')) ORDER BY Nom, Prenom ASC;";
$resultPersonne=mysqli_query($bdd,$req);
$nbPersonne=mysqli_num_rows($resultPersonne);
$cptPersonne = 0;

$Debut = $tmpDate;
$Fin = $dateFin;

if ($nbPersonne > 0)
{
	$ligne = 5;
	while($row=mysqli_fetch_array($resultPersonne)){
		$Id_Personne = $row[0];
		$cptPersonne = $cptPersonne + 1;
		$divers = "";
		
		//Recherche si planning
		$reqPla = "SELECT new_planning_vacationabsence.Nom, new_planning_vacationabsence.Couleur, new_planning_vacationabsence.AbsenceVacation, new_planning_vacationabsence.Description, new_planning_personne_vacationabsence.Commentaire, new_planning_personne_vacationabsence.DatePlanning, new_planning_personne_vacationabsence.Id_Prestation, ";
		$reqPla .= "new_planning_personne_vacationabsence.NbHeureJour, new_planning_personne_vacationabsence.NbHeureEquipeJour, new_planning_personne_vacationabsence.NbHeureEquipeNuit, new_planning_personne_vacationabsence.NbHeurePause, new_planning_personne_vacationabsence.ValidationResponsable, new_planning_vacationabsence.Id, new_planning_personne_vacationabsence.NbHeureFormation, new_planning_personne_vacationabsence.Divers,(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=new_planning_personne_vacationabsence.Id_Prestation) AS Prestation ";
		$reqPla .= "FROM new_planning_personne_vacationabsence LEFT JOIN new_planning_vacationabsence ON new_planning_personne_vacationabsence.ID_VacationAbsence = new_planning_vacationabsence.Id ";
		$reqPla .= "WHERE new_planning_personne_vacationabsence.Id_Personne=".$Id_Personne." AND new_planning_personne_vacationabsence.ValidationResponsable = 1 ";
		$reqPla .= "AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_planning_personne_vacationabsence.Id_Prestation)=".$PlateformeSelect." ";
		$reqPla .= "AND new_planning_personne_vacationabsence.DatePlanning>='".$Debut."' AND new_planning_personne_vacationabsence.DatePlanning<='".$Fin."';";
		$vacationJour=mysqli_query($bdd,$reqPla);
		$nbVacationJour=mysqli_num_rows($vacationJour);
		
		//Heures absences
		$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode($cptPersonne));
		$sheet->setCellValueByColumnAndRow(1,$ligne,utf8_encode($row[1]));
		$sheet->setCellValueByColumnAndRow(2,$ligne,utf8_encode($row[3]));
		$sheet->setCellValueByColumnAndRow(3,$ligne,utf8_encode('Heures / Absences'));
		$sheet->setCellValueByColumnAndRow(4,$ligne,utf8_encode(''.$row[1].''.$row[3].'1'));
		//Formation
		$sheet->setCellValueByColumnAndRow(3,$ligne+1,utf8_encode('Formation'));
		$sheet->setCellValueByColumnAndRow(4,$ligne+1,utf8_encode(''.$row[1].''.$row[3].'2'));
		//Heures équipe jour
		$sheet->setCellValueByColumnAndRow(3,$ligne+2,utf8_encode('Heures équipe jour'));
		$sheet->setCellValueByColumnAndRow(4,$ligne+2,utf8_encode(''.$row[1].''.$row[3].'3'));
		//Heures équipe nuit
		$sheet->setCellValueByColumnAndRow(3,$ligne+3,utf8_encode('Heures équipe nuit'));
		$sheet->setCellValueByColumnAndRow(4,$ligne+3,utf8_encode(''.$row[1].''.$row[3].'4'));
		//Pause
		$sheet->setCellValueByColumnAndRow(3,$ligne+4,utf8_encode('Pause'));
		$sheet->setCellValueByColumnAndRow(4,$ligne+4,utf8_encode(''.$row[1].''.$row[3].'5'));
		//Site
		$sheet->setCellValueByColumnAndRow(3,$ligne+5,utf8_encode('Site'));
		$sheet->setCellValueByColumnAndRow(4,$ligne+5,utf8_encode(''.$row[1].''.$row[3].'6'));
		
		$sheet->getRowDimension($ligne+5)->setRowHeight(0);
		$ligneDebut = $ligne + 1;
		$ligneFin = $ligne + 1;
		$ligneDebut = $ligne + 2;
		$ligneFin = $ligne + 5;
		$tabDateDebut = explode('/', $dateDebut);
		$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
		$tmpDate = date("Y-m-d",$timestampDebut);
		$colonne = 5;
		$colonneLettre = "F";
		
		while ($tmpDate < $dateFin) {
			$tabDate = explode('-', $tmpDate);
			$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
			$dateAffichage = date("d/m/Y",$timestamp);
			$class="";
			
			//Recherche si planning pour ce jour-ci
			$NbForVac ="";
			$NbForHorsVac = "";
			$NbFor = "";
			$NbHeureJ = "";
			$NbHeureEJ = "";
			$NbHeureEN = "";
			$NbHeureP = "";
			$NbHeureSuppJ = 0;
			$NbHeureSuppN = 0;
			$Prestation= "";
			if ($nbVacationJour>0){
				mysqli_data_seek($vacationJour,0);
				while($rowPlanning=mysqli_fetch_array($vacationJour)) {
					if ($rowPlanning[5] == $tmpDate){
						$Prestation= $rowPlanning['Prestation'];
						$CelPlanning=$rowPlanning[0];
						if ($rowPlanning['Divers'] != ""){
							$divers = $divers.$rowPlanning[5]." : ".$rowPlanning['Divers']."\n ";
						}
						if ($rowPlanning[2] == 0){
							$NbFor ="";
							$NbHeureJ = $rowPlanning[0];
							$NbHeureEJ = "";
							$NbHeureEN = "";
							$NbHeureP = "";
						}
						else{
							$NbFor = $rowPlanning[13];
							$NbHeureJ = $rowPlanning[7];
							$NbHeureEJ = $rowPlanning[8];
							$NbHeureEN = $rowPlanning[9];
							$NbHeureP = $rowPlanning[10];
						}
						break;
					}
				}
			}
			
			//Cellule finale
			if ($NbFor == 0){$NbFor = "";}
			if ($NbHeureEJ == 0){$NbHeureEJ = "";}
			if ($NbHeureEN == 0){$NbHeureEN = "";}
			if ($NbHeureP == 0){$NbHeureP = "";}
			if ($NbHeureJ == 0 && is_numeric($NbHeureJ)){$NbHeureJ = "";}
			$sheet->setCellValueByColumnAndRow($colonne,$ligne,utf8_encode($NbHeureJ));
			$sheet->setCellValueByColumnAndRow($colonne,$ligne+1,utf8_encode($NbFor));
			$sheet->setCellValueByColumnAndRow($colonne,$ligne+2,utf8_encode($NbHeureEJ));
			$sheet->setCellValueByColumnAndRow($colonne,$ligne+3,utf8_encode($NbHeureEN));
			$sheet->setCellValueByColumnAndRow($colonne,$ligne+4,utf8_encode($NbHeureP));
			
			if ($NbHeureJ<>"" || ($NbHeureEJ > 0 && is_numeric($NbHeureEJ)) || ($NbHeureEN > 0 && is_numeric($NbHeureEN)) || ($NbHeureP > 0 && is_numeric($NbHeureP)) || ($NbFor > 0 && is_numeric($NbFor))){
				$sheet->setCellValueByColumnAndRow($colonne,$ligne+5,utf8_encode($Prestation));
			}
			
			//Jour suivant
			$tabDate = explode('-', $tmpDate);
			$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
			$tmpDate = date("Y-m-d", $timestamp);
			$colonne++;
			$colonneLettre++;
		}
		$sheet->setCellValueByColumnAndRow($colonneDivers,$ligne,utf8_encode($divers));
		$ligneDiversDebut = $ligne + 1;
		$ligneDiversFin = $ligne + 5;
		$ligne = $ligne + 6;
	}
	$ligne = $ligne - 1;
	
	for ($i=$colDebutSem;$i<='AU';$i++){
		$sheet->getColumnDimension($i)->setWidth(5);
	}
	
	$sheet->getColumnDimension($colonneL)->setWidth(30);
	$sheet->getStyle($colonneL.'1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	$ligne = 5;
	
	mysqli_data_seek($resultPersonne,0);
	while($row=mysqli_fetch_array($resultPersonne)){
		$ligne = $ligne + 6;
	}
	mysqli_free_result($resultPersonne);
	
 }

 $sheet->setCellValue('E1',$cptPersonne);


 $sheet->freezePane('F5');
 
//Enregistrement du fichier excel

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Pointage.xlsx"'); 
header('Cache-Control: max-age=0'); 
	
$writer = new PHPExcel_Writer_Excel2007($workbook);

$chemin = '../../tmp/Pointage.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
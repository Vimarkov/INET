<?php
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

$reqPlateforme = "SELECT Libelle FROM new_competences_plateforme WHERE Id='".$_GET['Id_Plateforme']."'";
$resultPlateforme=mysqli_query($bdd,$reqPlateforme);
$nbPlateforme=mysqli_num_rows($resultPlateforme);
$NomPlateforme = "";
if ($nbPlateforme>0){
	$row=mysqli_fetch_array($resultPlateforme);
	$NomPlateforme = $row[0];
}

$reqPrestation = "SELECT Libelle FROM new_competences_prestation WHERE Id='".$_GET['Id_Prestation']."'";
$resultPrestation=mysqli_query($bdd,$reqPrestation);
$nbPrestation=mysqli_num_rows($resultPrestation);
$NomPrestation = "";
if ($nbPrestation>0){
	$row=mysqli_fetch_array($resultPrestation);
	$NomPrestation = $row[0];
}

$dateDebut = date("d/m/Y", strtotime($_GET['lDate']." +0 month"));
$tabDateFin = explode('/', $dateDebut);
$timestampFin = mktime(0, 0, 0, $tabDateFin[1]+1, $tabDateFin[0], $tabDateFin[2]);

$dateFin = date("Y/m/d", strtotime($_GET['lDateFin']." +0 month"));
$dateDeFin = date('d/m/Y', strtotime($_GET['lDateFin']." +0 month"));
$PrestationSelect = $_GET['Id_Prestation'];
$PlateformeSelect = $_GET['Id_Plateforme'];

$tabDateDebut = explode('/', $dateDebut);
$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
$tmpDate = date("Y/m/d",$timestampDebut);

$tabDateFin = explode('/', $dateDeFin);
$timestampFin = mktime(0, 0, 0, $tabDateFin[1], $tabDateFin[0], $tabDateFin[2]);
$dateFin = date("Y/m/d", $timestampFin);

$tabDate = explode('/', $tmpDate);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
$tmpMois = date('n', $timestamp) . ' ' . date('Y', $timestamp);
$joursem = array("D/S", "L/M", "M/T", "M/W", "J/T", "V/F", "S/S");
$MoisLettre = array("Janvier/January", "Fevrier/February", "Mars/March", "Avril/April", "Mai/May", "Juin/June", "Juillet/July", "Aout/August", "Septembre/September", "Octobre/October", "Novembre/November", "Decembre/December");
// GESTION DES ENTETES DU TABLEAU (MOIS, SEMAINE ET JOUR)
$colonne=3;
$laColonne="D";
$colonneJour="D";
$colDebutMois = "D";
$colFinMois = "D";
$colDernierMois = "C";
$colDebutSem = "D";
$colFinSem = "D";
$colDernierSem = "C";
while ($tmpDate <= $dateFin) {	
	$colDernierMois++;$colDernierMois++;
	$colDernierSem++;$colDernierSem++;
	$tabDate = explode('/', $tmpDate);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$jour = date('w', $timestamp);
	$mois = $tabDate[1];
	$semaine = date('W', $timestamp);
	$sheet->setCellValueByColumnAndRow($colonne,3,utf8_encode($joursem[$jour]));
	$sheet->setCellValueByColumnAndRow($colonne,4,utf8_encode($tabDate[2]));
	
	$sheet->getColumnDimension($laColonne)->setWidth(5);
	$colEnPlus=$laColonne;
	$colEnPlus++;
	$sheet->getColumnDimension($colEnPlus)->setWidth(5);
	$sheet->mergeCells($laColonne.'3:'.$colEnPlus.'3');
	$sheet->mergeCells($laColonne.'4:'.$colEnPlus.'4');
	
	//Jour suivant
	$tabDate = explode('/', $tmpDate);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
	$tmpDate = date("Y/m/d", $timestamp);
	
	if (date('m', $timestamp) <> $tabDate[1]){
		$colFinMois++;
		$sheet->mergeCells($colDebutMois.'1:'.$colFinMois.'1');
		$sheet->setCellValue($colDebutMois.'1',utf8_encode($MoisLettre[$mois-1]." ".$tabDate[0]));
		$colFinMois++;
		$colDebutMois = $colFinMois;
	}
	else{
		$colFinMois++;$colFinMois++;
	}
	if (date('W', $timestamp) <> $semaine){
		$colFinSem++;
		$sheet->mergeCells($colDebutSem.'2:'.$colFinSem.'2');
		$sheet->setCellValue($colDebutSem.'2',utf8_encode("S".$semaine.""));
		$colFinSem++;
		$colDebutSem = $colFinSem;
	}
	else{
		$colFinSem++;$colFinSem++;
	}
	$colonne = $colonne+2;
	$laColonne++;$laColonne++;
}
if (date('m', $timestamp) == $tabDate[1]){
	$sheet->mergeCells($colDebutMois.'1:'.$colDernierMois.'1');
	$sheet->setCellValue($colDebutMois.'1',utf8_encode($MoisLettre[$mois-1]." ".$tabDate[0]));
}

if ($joursem[$jour]<>"D/S"){
	$colFinSem++;$colFinSem++;
	$sheet->mergeCells($colDebutSem.'2:'.$colDernierSem.'2');
	$sheet->setCellValue($colDebutSem.'2',utf8_encode("S".$semaine.""));
}
$sheet->mergeCells('A1:C2');
if ($NomPlateforme<>""){$sheet->setCellValue('A1',utf8_encode("Prestation : ".$NomPlateforme));}

$sheet->mergeCells('A3:C3');
if ($NomPrestation <> ""){$sheet->setCellValue('A3',utf8_encode("Pôle : ".$NomPrestation));}

$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->setCellValue('A4',utf8_encode("Theme"));
$sheet->setCellValue('B4',utf8_encode("Origine du QCM / MCQ source"));
$sheet->setCellValue('C4',utf8_encode("Questionnaire"));

$sheet->getStyle('A1:'.$colDernierMois.'4')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:'.$colDernierMois.'4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1:C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('D1:'.$colDernierMois.'4')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ededff'))));
// FIN GESTION DES ENTETES DU TABLEAU

//DEBUT CORPS DU TABLEAU
$tabDateDebut = explode('/', $dateDebut);
$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
$tmpDate = date("Y/m/d",$timestampDebut);

$tabDateFin = explode('/', $dateDeFin);
$timestampFin = mktime(0, 0, 0, $tabDateFin[1], $tabDateFin[0], $tabDateFin[2]);
$dateFin = date("Y/m/d", $timestampFin);
$ldateFin  = date("Y-m-d", $timestampFin);
//Liste des surveillances
$req = "SELECT new_surveillances_surveillance.ID, ";
$req .= "new_surveillances_surveillance.ID_Prestation, ";
$req .= "new_surveillances_surveillance.ID_Questionnaire, ";
$req .= "IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS DateSurveillance, ";
$req .= "new_surveillances_surveillance.DatePlanif, ";
$req .= "new_surveillances_surveillance.DateReplanif, ";
$req .= "new_surveillances_surveillance.DateCloture, ";
$req .= "IF(new_surveillances_surveillance.Etat='Clôturé' OR new_surveillances_surveillance.Etat='Réalisé','Clôturé','Planifié') AS Etat ";
$req .= "FROM new_surveillances_surveillance ";
$req .= "LEFT JOIN new_competences_prestation ";
$req .= "ON new_competences_prestation.Id = new_surveillances_surveillance.ID_Prestation ";
$req .= "WHERE IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) >='".$_GET['lDate']."' ";
$req .= "AND IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) <='".$ldateFin."'";
if ($PlateformeSelect > 0){
	$req .= "AND new_competences_prestation.ID_Plateforme =".$PlateformeSelect." ";
}
if ($PrestationSelect > 0){
	$req .= "AND new_competences_prestation.Id =".$PrestationSelect." ";
}
if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite))){
	
}
else{
	$req.="AND (new_competences_prestation.Id_Plateforme IN (
		SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$IdPersonneConnectee."
		AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteResponsableHSE.",".$IdPosteCoordinateurSecurite.")
	)
	OR 
	new_surveillances_surveillance.ID_Prestation IN (
		SELECT Id_Prestation 
		FROM new_competences_personne_poste_prestation 
		WHERE Id_Personne=".$IdPersonneConnectee."
		AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
		)
	) ";
}
$resultSurveillance=mysqli_query($bdd,$req);
$nbSurveillance=mysqli_num_rows($resultSurveillance);

//Liste des questionnaires
$req = "SELECT distinct new_surveillances_questionnaire.ID,new_surveillances_questionnaire.ID_Theme, ";
$req .= "new_surveillances_questionnaire.ID_Plateforme, ";
$req .= "(SELECT new_surveillances_theme.Nom FROM new_surveillances_theme WHERE new_surveillances_theme.ID = new_surveillances_questionnaire.ID_Theme) AS Theme, ";
$req .= "(SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id = new_surveillances_questionnaire.ID_Plateforme) AS Plateforme, ";
$req .= "CONCAT(new_surveillances_questionnaire.Nom,' ',IF(Actif=0,'[Actif]','[Inactif]')) AS Nom ";
$req .= "FROM ((new_surveillances_surveillance ";
$req .= "LEFT JOIN new_competences_prestation ";
$req .= "ON new_competences_prestation.Id = new_surveillances_surveillance.ID_Prestation) ";
$req .= "LEFT JOIN new_surveillances_questionnaire ";
$req .= "ON new_surveillances_questionnaire.ID = new_surveillances_surveillance.ID_Questionnaire) ";
$req .= "WHERE IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) >='".$_GET['lDate']."' ";
$req .= "AND IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) <='".$ldateFin."' ";
if ($PlateformeSelect > 0){
		$req .= "AND new_competences_prestation.ID_Plateforme =".$PlateformeSelect." ";
}
if ($PrestationSelect > 0){
	$req .= "AND new_competences_prestation.Id =".$PrestationSelect." ";
}
$req .= "ORDER BY Theme, Plateforme, new_surveillances_questionnaire.Nom ;";

$resultQuestionnaire=mysqli_query($bdd,$req);
$nbQuestionnaire=mysqli_num_rows($resultQuestionnaire);
$couleurExcel = "ffffff";
$Debut = $tmpDate;
$Fin = $dateFin;
$ligne = 5;
$colonne = 3;
$colonneLettre = "D";
if ($nbQuestionnaire > 0){
	$couleurQuestionnaire = "bgcolor=#548FFB";
	while($row=mysqli_fetch_array($resultQuestionnaire)){
		$Id_Questionnaire = $row['ID'];
		
		$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode($row['Theme']));
		$sheet->setCellValueByColumnAndRow(1,$ligne,utf8_encode($row['Plateforme']));
		$sheet->setCellValueByColumnAndRow(2,$ligne,utf8_encode($row['Nom']));
		
		$sheet->mergeCells('A'.$ligne.':A'.($ligne+1));
		$sheet->mergeCells('B'.$ligne.':B'.($ligne+1));
		$sheet->mergeCells('C'.$ligne.':C'.($ligne+1));
		
		$sheet->getStyle('A'.$ligne.':C'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ededff'))));
		$sheet->getStyle('A'.$ligne.':C'.$ligne.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$tabDateDebut = explode('/', $dateDebut);
		$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
		$tmpDate = date("Y/m/d",$timestampDebut);
		
		while ($tmpDate <= $dateFin) {
			$tabDate = explode('/', $tmpDate);
			$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
			$dateAffichage = date("d/m/Y",$timestamp);
			
			//Recherche si planning pour ce jour-ci
			$nbPlanif=0;
			$nbRePlanif=0;
			$nbRealise=0;
			$nbCloture=0;
			$nbCellule = 0;
			if ($nbSurveillance>0){
				mysqli_data_seek($resultSurveillance,0);
				while($rowSurveillance=mysqli_fetch_array($resultSurveillance)) {
					$tabDateVac = explode('-', $rowSurveillance['DateSurveillance']);
					$timestampVac = mktime(0, 0, 0, $tabDateVac[1], $tabDateVac[2], $tabDateVac[0]);
					$dateVac = date("Y/m/d", $timestampVac);
					if ($dateVac == $tmpDate && $rowSurveillance['ID_Questionnaire'] == $row['ID']){
						if($rowSurveillance['Etat'] == "Planifié"){
							if($nbPlanif == 0){$nbCellule++;}
							$nbPlanif++;
						}
						elseif($rowSurveillance['Etat'] == "Clôturé"){
							if($nbCloture == 0){$nbCellule++;}
							$nbCloture++;
						}
					}
					
					$col2 = $colonneLettre;
					$col2++;
					$ligne2 = $ligne;
					$ligne2++;

					if($nbPlanif>0){
						$sheet->setCellValueByColumnAndRow($colonne,$ligne,$nbPlanif);
						$sheet->getStyle($colonneLettre.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffff00'))));
					}
					if($nbRePlanif>0){
						$sheet->setCellValueByColumnAndRow(($colonne+1),$ligne,$nbRePlanif);
						$sheet->getStyle($col2.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ff0000'))));
						
					}
					if($nbRealise>0){
						$sheet->setCellValueByColumnAndRow($colonne,($ligne+1),$nbRealise);
						$sheet->getStyle($colonneLettre.$ligne2)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'0070c0'))));
						
					}
					if($nbCloture>0){
						$sheet->setCellValueByColumnAndRow(($colonne+1),($ligne+1),$nbCloture);
						$sheet->getStyle($col2.$ligne2)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'92d050'))));
						
					}
				}
			}
			
			if($nbPlanif==0 && $nbCloture==0){
				$sheet->mergeCells($colonneLettre.$ligne.':'.$col2.$ligne2);
			}
			if($nbPlanif>0){
				if($nbPlanif>0 && $nbCloture==0){
					$sheet->mergeCells($colonneLettre.$ligne.':'.$col2.$ligne2);
				}
			}
			if($nbCloture>0){
				if($nbCloture>0 && $nbPlanif==0){
					$sheet->mergeCells($colonneLettre.$ligne.':'.$col2.$ligne2);
					$sheet->setCellValue($colonneLettre.$ligne,utf8_encode($nbCloture));
					$sheet->getStyle($colonneLettre.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'92d050'))));
				}
			}
			
			//Jour suivant
			$tabDate = explode('/', $tmpDate);
			$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
			$tmpDate = date("Y/m/d", $timestamp);
			$colonne++;
			$colonne++;
			$colonneLettre++;
			$colonneLettre++;
		}
		
		$Couleur = "";
		$colonne = 3;
		$colonneLettre = "D";

		$ligne=$ligne+2;
	}
	$ligne = $ligne - 1;
	$sheet->getStyle('A1:'.$colDernierMois.$ligne.'')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$sheet->getStyle('D3:'.$colDernierMois.$ligne.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
 }

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="PlanningSurveillance.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/PlanningSurveillance.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
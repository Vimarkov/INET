<?php
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();


function jour_ferie($timestamp){
	 $EstFerie = 0;
	 // Initialisation de la date de début
	 $jour = intval(date("d", $timestamp));
	 $mois = intval(date("m", $timestamp));
	 $annee = intval(date("Y", $timestamp));
	 // Calul des samedis et dimanches
	 
	 $jour_semaine = date('w', $timestamp);
	 if($jour_semaine == 0 ||$jour_semaine == 6)
	 {
	 $EstFerie = 1;// Dimanche (0), Samedi (6)
	 }
	 
	if($jour_semaine == 1||$jour_semaine == 2||$jour_semaine == 3||$jour_semaine == 4||$jour_semaine == 5){
		 // Définition des dates fériées fixes
		 if($jour == 1 && $mois == 1) $EstFerie = 1; // 1er janvier
		 if($jour == 1 && $mois == 5) $EstFerie = 1; // 1er mai
		 if($jour == 8 && $mois == 5) $EstFerie = 1; // 8 mai
		 if($jour == 14 && $mois == 7) $EstFerie = 1; // 14 juillet
		 if($jour == 15 && $mois == 8 ) $EstFerie = 1; // 15 aout
		 if($jour == 1 && $mois == 11) $EstFerie = 1; // 1 novembre
		 if($jour == 11 && $mois == 11) $EstFerie = 1; // 11 novembre
		 if($jour == 25 && $mois == 12) $EstFerie = 1; // 25 décembre
		// Calcul du jour de pâques
		 //$date_paques = easter_date($annee);
		 
		$a = $annee % 4;
		$b = $annee % 7;
		$c = $annee % 19;
		$m = 24;
		$n = 5;
		$d = (19 * $c + $m ) % 30;
		$e = (2 * $a + 4 * $b + 6 * $d + $n) % 7;

		$easterdate = 22 + $d + $e;

		if ($easterdate > 31)
		{
				$jour_paques = $d + $e - 9;
				$mois_paques = 4;
		}
		else
		{
				$jour_paques = 22 + $d + $e;
				$mois_paques = 3;
		}

		if ($d == 29 && $e == 6)
		{
				$jour_paques = 10;
				$mois_paques = 04;
		}
		elseif ($d == 28 && $e == 6)
		{
				$jour_paques = 18;
				$mois_paques = 04;
		}
		 //$jour_paques = date("d", $date_paques) + 1 ;
		 //$mois_paques = date("m", $date_paques);
		 if($jour_paques == $jour && $mois_paques == $mois) $EstFerie = 1;
		 // Pâques
		 $date_paques = mktime(0, 0, 0, $mois_paques, $jour_paques, $annee);
		// Calcul du jour de l ascension (38 jours après Paques)
		 $date_ascension = mktime(date("H", $date_paques),
		 date("i", $date_paques),
		 date("s", $date_paques),
		 date("m", $date_paques),
		 date("d", $date_paques) + 39,
		 date("Y", $date_paques)
		 );
		 $jour_ascension = date("d", $date_ascension);
		 $mois_ascension = date("m", $date_ascension);
		 if($jour_ascension == $jour && $mois_ascension == $mois) $EstFerie = 1;
		 //Ascension
		 
		// Calcul de Pentecôte (11 jours après Paques)
		 $date_pentecote = mktime(date("H", $date_ascension),
		 date("i", $date_ascension),
		 date("s", $date_ascension),
		 date("m", $date_ascension),
		 date("d", $date_ascension) + 11,
		 date("Y", $date_ascension)
		 );
		 $jour_pentecote = date("d", $date_pentecote);
		 $mois_pentecote = date("m", $date_pentecote);
		 if($jour_pentecote == $jour && $mois_pentecote == $mois) $EstFerie = 1;
		 //Pentecote
	}
	 return $EstFerie;
	}//Fin de la fonction

$reqPrestation = "SELECT Libelle FROM new_competences_prestation WHERE Id='".$_GET['Id_Prestation']."'";
	$resultPrestation=mysqli_query($bdd,$reqPrestation);
	$nbPrestation=mysqli_num_rows($resultPrestation);
	$NomPrestation = "";
	if ($nbPrestation>0){
		$row=mysqli_fetch_array($resultPrestation);
		$NomPrestation = $row[0];
	}
	
	$reqPole = "SELECT Libelle FROM new_competences_pole WHERE Id='".$_GET['Id_Pole']."'";
	$resultPole=mysqli_query($bdd,$reqPole);
	$nbPole=mysqli_num_rows($resultPole);
	$NomPole = "";
	if ($nbPole>0){
		$row=mysqli_fetch_array($resultPole);
		$NomPole = $row[0];
	}
	
	$dateDebut = date("d/m/Y", $_GET['lDate']);
	$tabDateFin = explode('/', $dateDebut);
	$timestampFin = mktime(0, 0, 0, $tabDateFin[1]+1, $tabDateFin[0], $tabDateFin[2]);
	$dateFin = date("Y/m/d", $timestampFin);
	$dateDeFin = date('d/m/Y', $timestampFin);
	$PrestationSelect = $_GET['Id_Prestation'];
	$PoleSelect = $_GET['Id_Pole'];
	
	//Autres cas
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
	$MoisLettre = array("Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Decembre");
	// GESTION DES ENTETES DU TABLEAU (MOIS, SEMAINE ET JOUR)
	$colonne=3;
	$colDebutMois = "D";
	$colFinMois = "D";
	$colDernierMois = "C";
	$colDebutSem = "D";
	$colFinSem = "D";
	$colDernierSem = "C";
	while ($tmpDate <= $dateFin) 
	{
		$colDernierMois++;
		$colDernierSem++;
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
	$sheet->mergeCells('A1:C2');
	$sheet->setCellValue('A1',utf8_encode("Prestation : ".$NomPrestation));
	
	$sheet->mergeCells('A3:C3');
	if ($NomPole <> ""){
		$sheet->setCellValue('A3',utf8_encode("Pôle : ".$NomPole));
	}
	$sheet->getColumnDimension('A')->setWidth(20);
	$sheet->getColumnDimension('B')->setWidth(20);
	$sheet->setCellValue('A4',utf8_encode("Personne"));
	$sheet->setCellValue('B4',utf8_encode("Métier"));
	$sheet->setCellValue('C4',utf8_encode("Pôle"));
	
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
	
	//Personnes  présentent sur cette prestation à  ces dates
	$reqMilieu="";
	if ($PoleSelect > 0){$reqMilieu=" AND new_competences_personne_prestation.Id_Pole =".$PoleSelect." ";}
	$req = "SELECT
                DISTINCT new_competences_personne_prestation.Id_Personne,
			    CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
			    (SELECT new_competences_metier.Libelle FROM new_competences_metier WHERE new_competences_metier.Id = Tb_Personne_Metier.Id_Metier) AS Metier,
			    (SELECT new_competences_metier.Code FROM new_competences_metier WHERE new_competences_metier.Id = Tb_Personne_Metier.Id_Metier) AS CodeMetier
			FROM
                new_competences_personne_prestation
                RIGHT JOIN new_rh_etatcivil
                    ON new_rh_etatcivil.Id = new_competences_personne_prestation.Id_Personne
			    LEFT JOIN (SELECT Id_Personne, Id_Metier FROM new_competences_personne_metier WHERE Futur=0) AS Tb_Personne_Metier
                    ON Tb_Personne_Metier.Id_Personne = new_rh_etatcivil.Id 
			WHERE
                new_competences_personne_prestation.Id_Prestation =".$PrestationSelect.
                $reqMilieu."
				AND
                (
                    (
                        new_competences_personne_prestation.Date_Debut<='".$tmpDate."'
                        AND new_competences_personne_prestation.Date_Fin>='".$tmpDate."'
                    )
                    OR
                    (
                        new_competences_personne_prestation.Date_Debut<='".$dateFin."'
                        AND new_competences_personne_prestation.Date_Fin>='".$dateFin."'
                    )
                    OR
                    (
                        new_competences_personne_prestation.Date_Debut>='".$tmpDate."'
                        AND new_competences_personne_prestation.Date_Fin<='".$dateFin."'
                    )
                )
            ORDER BY
                Personne ASC;";
	$resultPersonne=mysqli_query($bdd,$req);
	$nbPersonne=mysqli_num_rows($resultPersonne);
	
	//Pôle des Personnes  présentent sur cette prestation à  ces dates
	$req = "SELECT DISTINCT new_competences_personne_prestation.Id_Personne, ";
	$req .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=new_competences_personne_prestation.Id_Pole) AS Pole ";
	$req .= "FROM new_competences_personne_prestation RIGHT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id = new_competences_personne_prestation.Id_Personne ";
	$req .= "LEFT JOIN new_competences_personne_metier ON new_competences_personne_metier.Id_Personne = new_rh_etatcivil.Id ";
	$req .= "WHERE new_competences_personne_prestation.Id_Prestation ='".$PrestationSelect."' ";
	if ($PoleSelect > 0){$req .= "AND new_competences_personne_prestation.Id_Pole =".$PoleSelect." ";}
	$req .= "AND ((new_competences_personne_prestation.Date_Debut<='".$tmpDate."' AND new_competences_personne_prestation.Date_Fin>='".$tmpDate."') ";
	$req .= "OR (new_competences_personne_prestation.Date_Debut<='".$dateFin."' AND new_competences_personne_prestation.Date_Fin>='".$dateFin."') ";
	$req .= "OR (new_competences_personne_prestation.Date_Debut>='".$tmpDate."' AND new_competences_personne_prestation.Date_Fin<='".$dateFin."'));";
	$resultPolesPresta=mysqli_query($bdd,$req);
	$nbPolesPresta=mysqli_num_rows($resultPolesPresta);
	
	$couleurExcel = "ffffff";
	$Debut = $tmpDate;
	$Fin = $dateFin;
	if ($nbPersonne > 0){
		$ligne=5;
		while($row=mysqli_fetch_array($resultPersonne))
		{
			$Id_Personne = $row[0];
			
			//Recherche prestations pour ce jour-ci
			$reqPresta = "SELECT DISTINCT(new_competences_personne_prestation.Id_Prestation), ";
			$reqPresta .= "(SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = new_competences_personne_prestation.Id_Prestation) AS Nom , ";
			$reqPresta .= "new_competences_personne_prestation.Date_Debut, new_competences_personne_prestation.Date_Fin ";
			$reqPresta .= "FROM new_competences_personne_prestation ";
			$reqPresta .= "WHERE new_competences_personne_prestation.Id_Personne =".$Id_Personne." ORDER BY Nom ASC;";
		
			$prestaJour=mysqli_query($bdd,$reqPresta);
			$nbprestaJour=mysqli_num_rows($prestaJour);
			
			//Recherche si ses formations
			$reqFor = "SELECT new_planning_personne_formation.NbHeureVacation, new_planning_personne_formation.NbHeureHorsVacation, new_planning_personne_formation.DateFormation ";
			$reqFor .= "FROM new_planning_personne_formation ";
			$reqFor .= "WHERE new_planning_personne_formation.Id_Personne =".$Id_Personne." ";
			$reqFor .= "AND new_planning_personne_formation.DateFormation>='".$Debut."' AND new_planning_personne_formation.DateFormation<='".$Fin."';";
			$formationJour=mysqli_query($bdd,$reqFor);
			$nbformationJour=mysqli_num_rows($formationJour);
			
			//Recherche si planning
			$reqPla = "SELECT new_planning_vacationabsence.Nom, new_planning_vacationabsence.Couleur, new_planning_vacationabsence.AbsenceVacation, new_planning_vacationabsence.Description, new_planning_personne_vacationabsence.Commentaire, new_planning_personne_vacationabsence.DatePlanning, new_planning_personne_vacationabsence.Id_Prestation ";
			$reqPla .= "FROM new_planning_personne_vacationabsence LEFT JOIN new_planning_vacationabsence ON new_planning_personne_vacationabsence.ID_VacationAbsence = new_planning_vacationabsence.Id ";
			$reqPla .= "WHERE new_planning_personne_vacationabsence.Id_Personne=".$Id_Personne." ";
			$reqPla .= "AND new_planning_personne_vacationabsence.Id_Prestation=".$PrestationSelect." ";
			$reqPla .= "AND new_planning_personne_vacationabsence.DatePlanning>='".$Debut."' AND new_planning_personne_vacationabsence.DatePlanning<='".$Fin."';";
			
			$vacationJour=mysqli_query($bdd,$reqPla);
			$nbVacationJour=mysqli_num_rows($vacationJour);
			
			$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode($row[1]));
			$sheet->setCellValueByColumnAndRow(1,$ligne,utf8_encode($row[2]));
			
			//Pôles
			if($nbPolesPresta>0){
				mysqli_data_seek($resultPolesPresta,0);
				$Poles="";
				while($rowPolePresta=mysqli_fetch_array($resultPolesPresta)){
					if($rowPolePresta['Id_Personne']==$row['Id_Personne']){
						$Poles.=$rowPolePresta['Pole']." ";
					}
				}
				$sheet->setCellValueByColumnAndRow(2,$ligne,utf8_encode($Poles));
				$sheet->getStyle('C'.$ligne)->getAlignment()->setWrapText(true);
			}

			$sheet->getStyle('A'.$ligne.':C'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ededff'))));
			$sheet->getStyle('A'.$ligne.':C'.$ligne.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$tabDateDebut = explode('/', $dateDebut);
			$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
			$tmpDate = date("Y/m/d",$timestampDebut);
			
			$Couleur = "";
			$colonne = 3;
			$colonneLettre = "D";
			while ($tmpDate <= $dateFin) {
				$tabDate = explode('/', $tmpDate);
				$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
				$dateAffichage = date("d/m/Y",$timestamp);
				if (jour_ferie($timestamp) == true){$Couleur = "e9e9e9";}
				else{$Couleur = "ffffff";}

				//Recherche si planning pour ce jour-ci
				$Absence="Absence : ";
				$Vacation="Vacation : ";
				$Commentaire = "";
				$CelPlanning= "";
				$FormationON = "Non";
				$NbForVac ="";
				$NbForHorsVac = "";
				$ClassDiv = "";
				$PrestaDuJour = "";
				if ($nbVacationJour>0){
					mysqli_data_seek($vacationJour,0);
					$PrestaDuJour = "";
					while($rowPlanning=mysqli_fetch_array($vacationJour)) {
						$tabDateVac = explode('-', $rowPlanning[5]);
						$timestampVac = mktime(0, 0, 0, $tabDateVac[1], $tabDateVac[2], $tabDateVac[0]);
						$dateVac = date("Y/m/d", $timestampVac);
						if ($dateVac == $tmpDate){
							$PrestaDuJour = $rowPlanning[6];
							$Couleur = "888888";
							if ($rowPlanning[6] == $PrestationSelect){
								
								$Couleur = substr($rowPlanning[1],1);
							}
							$Commentaire = $rowPlanning[4];
							$CelPlanning=$rowPlanning[0];
							$ClassDiv = "class='rempliSansFormation'";
							if ($rowPlanning[2] ==1){
								$Vacation .=$rowPlanning[3];
							}
							else{
								$Absence .=$rowPlanning[3];
							}
						}
					}
				}
				if ($Couleur == ""){
					$FormationON = "";
					if (jour_ferie($timestamp)){$Couleur = "e9e9e9";}
					else{$Couleur = "ffffff";}
				}

				//Recherche si appartient Ã  cette prestation ce jour -ci
				$onClick="";
				$Prestations="Prestation(s):<br/>";
				$contenu ="";
				if ($nbprestaJour>0){
					mysqli_data_seek($prestaJour,0);
					while($rowPrestaJour=mysqli_fetch_array($prestaJour)) {
						$tabDate2 = explode('-', $rowPrestaJour[2]);
						if ($tabDate2[0] > 2037){$tabDate2[0]=2037;}
						$timestamp2 = mktime(0, 0, 0, $tabDate2[1], $tabDate2[2], $tabDate2[0]);
						$dateDebutReq = date("Y/m/d", $timestamp2);
						$tabDate2 = explode('-', $rowPrestaJour[3]);
						if ($tabDate2[0] > 2037){$tabDate2[0]=2037;}
						$timestamp2 = mktime(0, 0, 0, $tabDate2[1], $tabDate2[2], $tabDate2[0]);
						$dateFinReq = date("Y/m/d", $timestamp2);
						if ($dateDebutReq <= $tmpDate && $dateFinReq >= $tmpDate){
							if ($PrestaDuJour == $rowPrestaJour[0]){
								$Prestations .="<u>".$rowPrestaJour[1]."</u><br/>";
							}
							else{
								$Prestations .=$rowPrestaJour[1]."<br/>";
							}
							if ($rowPrestaJour[0]==$PrestationSelect){
								$tabDateTransfert = explode('/', $tmpDate);
								$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
								$onClick="onclick='javascript:OuvreFenetreModifPlanning()'";
							}
						}
					}
				}
				if ($onClick==""){
					$tabDateTransfert = explode('/', $tmpDate);
					$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
					$contenu = "";
					$Couleur = "000000";
				}
				else{
					$contenu = $CelPlanning;
				}
				$Prestations =  substr($Prestations, 0, -5)."" ;

				//Recherche si a une formation ce jour-ci
				$Enformation = false;
				if ($nbformationJour>0){
					mysqli_data_seek($formationJour,0);
					while($rowFormationJour=mysqli_fetch_array($formationJour)) {
						$tabDate2 = explode('-', $rowFormationJour[2]);
						if ($tabDate2[0] > 2037){$tabDate2[0]=2037;}
						$timestamp2 = mktime(0, 0, 0, $tabDate2[1], $tabDate2[2], $tabDate2[0]);
						$dateDebutReq = date("Y/m/d", $timestamp2);
						if ($dateDebutReq == $tmpDate){
							$Enformation = true;
						}
					}
				}
				
				//Cellule finale
				$sheet->setCellValueByColumnAndRow($colonne,$ligne,utf8_encode($contenu));
				if($Enformation == false){
					$sheet->getStyle($colonneLettre.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$Couleur))));
				}
				else{
					$sheet->getStyle($colonneLettre.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_PATTERN_LIGHTUP,'color'=>array('argb'=>$Couleur))));
				}
				//Jour suivant
				$tabDate = explode('/', $tmpDate);
				$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
				$tmpDate = date("Y/m/d", $timestamp);
				$colonne++;
				$colonneLettre++;
			}
			$ligne++;
		}
		$ligne = $ligne - 1;
		$sheet->getStyle('A1:'.$colDernierMois.$ligne.'')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('D3:'.$colDernierMois.$ligne.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	 }
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Planning.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Planning.xlsx';
$writer->save($chemin);
readfile($chemin);
/*
$writer = new PHPExcel_Writer_Excel2007($workbook);
$writer->setOffice2003Compatibility(true);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=Planning.xlsx ');
$writer->save('php://output');
*/
?>
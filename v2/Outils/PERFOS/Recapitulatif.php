<?php
	include '../../Excel/PHPExcel.php';
	include '../../Excel/PHPExcel/Writer/Excel2007.php';
	require '../ConnexioniSansBody.php';

	$workbook;
	$sheet;
	$curseurLigne;
	$result_prestas;
	$result_sqcdpf;
	$nb_colonnes;
	$nomSecteur = "";
	$saveCurseur;
	$secteurs = array();
// 	$dateDebut = "2015-10-01";
// 	$dateFin = "2015-10-10";

	$dateDebut = $_GET['debut'];
 	$dateFin = $_GET['fin'];

	$gris = "CCCCCC";
	$blanc = "FFFFFF";
	$vert = "00B050";
	$rouge = "FA0000";

	global $workbook, $sheet, $curseurLigne, $result_prestas, $result_sqcdpf;
	global $gris, $blanc, $vert, $rouge;
	global $dateDebut, $dateFin;//, $testdateFin;
	global $nb_colonnes;
	global	$nomSecteur, $saveCurseur, $secteurs;
				
 			creerRecapitulatif($workbook, $sheet, $bdd);				
 				
 				
			function creerRecapitulatif($workbook, $sheet, $BDD) {
				global $workbook, $sheet;
				
				nouveauFichier($workbook, $sheet);
				init();
				getData($BDD);
				mySheetHeader($sheet);
				ecrireTableau($BDD);
				miseEnPageGlobale();
				enregitrer($workbook);
			}
				
				
			function nouveauFichier($workbook, $sheet) {
				global $workbook, $sheet;
			
				//Nouveau fichier
				$workbook = new PHPExcel;
				$sheet = $workbook->getActiveSheet();
			}
				
			function init() {
				global $curseurLigne;
				
				$curseurLigne = 3;
			}
			
			function mySheetHeader($sheet) {
				global $sheet;
				global $dateDebut, $dateFin;
				global $nb_colonnes;
							
			 	//Ligne En-tete
				$sheet->setCellValue('B2',utf8_encode('Secteur'));
				$sheet->setCellValue('C2',utf8_encode('Prestation'));
				$sheet->setCellValue('D2',utf8_encode('Pôle'));
				$sheet->setCellValue('E2',utf8_encode('Lettre'));
				
				$date = new DateTime($dateDebut);
				$dt_datefin = new DateTime($dateFin);				
				$nb_colonnes = round(($dt_datefin->format('U') - $date->format('U')) / (60*60*24));
				
			 	$colonne = "F";
			 	
			 	for ($nb = 0; $nb <= $nb_colonnes; $nb++ ){
			 		$sheet->setCellValue($colonne.'2', utf8_encode($date->format('d/m')));
			 		$colonne++;
			 		$date->modify('+1 day'); // Décallage d'une journée
			 		$interval = round(($dt_datefin->format('U') - $date->format('U')) / (60*60*24)); // Calcul de l'interval
			 	}
				
			 	$sheet->setCellValue($colonne.'2',utf8_encode('Commentaires (afficher les commentaires liés aux cases rouges)'));
			
				//Réglage de taille de colonnes
				$sheet->getDefaultColumnDimension()->setWidth(10);
				$sheet->getColumnDimensionByColumn(1)->setWidth(25);
				$sheet->getColumnDimensionByColumn(2)->setWidth(60);
				$sheet->getColumnDimensionByColumn(6+$nb_colonnes)->setWidth(80);
				
				//Mise en page
				$range = "B2:".$colonne."2";
				cellCenterText($range);
				cellBorders($range);
				cellBordersMedium($range);
			}
			
			function mySheetAddPrestation($IdSecteur, $nomPresta, $nomPole) {
				global $curseurLigne, $sheet, $result_sqcdpf;
				global $gris;
				global $dateDebut, $dateFin;
				global $nb_colonnes;
				global $saveCurseur;
			
				
				//Ajouter les calculs de colonnes
				if (mysqli_num_rows($result_sqcdpf) > 0 ){
				
						//Pour chaque jour
					//[215] - nom de prestation sur chacune des lignes
					$sheet->setCellValue('C'.$curseurLigne, utf8_encode($nomPresta));
					$sheet->setCellValue('C'.($curseurLigne+1), utf8_encode($nomPresta));
					$sheet->setCellValue('C'.($curseurLigne+2), utf8_encode($nomPresta));
					$sheet->setCellValue('C'.($curseurLigne+3), utf8_encode($nomPresta));
					$sheet->setCellValue('C'.($curseurLigne+4), utf8_encode($nomPresta));
					$sheet->setCellValue('C'.($curseurLigne+5), utf8_encode($nomPresta));
					
					//[215] - nom du pole sur chacune des lignes
			 	 	$sheet->setCellValue('D'.$curseurLigne, utf8_encode($nomPole));
			 	 	$sheet->setCellValue('D'.($curseurLigne+1),utf8_encode($nomPole));
			 	 	$sheet->setCellValue('D'.($curseurLigne+2),utf8_encode($nomPole));
			 	 	$sheet->setCellValue('D'.($curseurLigne+3),utf8_encode($nomPole));
			 	 	$sheet->setCellValue('D'.($curseurLigne+4),utf8_encode($nomPole));
			 	 	$sheet->setCellValue('D'.($curseurLigne+5),utf8_encode($nomPole));
			 	 	
					$sheet->setCellValue('E'.$curseurLigne, utf8_encode('S'));
					$sheet->setCellValue('E'.($curseurLigne+1), utf8_encode('Q'));
					$sheet->setCellValue('E'.($curseurLigne+2), utf8_encode('C'));
					$sheet->setCellValue('E'.($curseurLigne+3), utf8_encode('D'));
					$sheet->setCellValue('E'.($curseurLigne+4), utf8_encode('P'));
					$sheet->setCellValue('E'.($curseurLigne+5), utf8_encode('F'));
					
					 //Calcul de colonnes
					 $colonne = 'G'; //la colonne commentaires
					 
					 for ($nb = 1; $nb < $nb_colonnes; $nb++)
					 	$colonne++;
					 
					 //Mise en page
					 // Centrage		 
					 $range = "B".$curseurLigne.":".$colonne.($curseurLigne+5);
				 	 cellBorders($range);
				 	 cellBordersMedium($range);
			
				 	 $range = "B".$curseurLigne.":".$colonne.($curseurLigne+5);
				 	 cellCenterText($range);
				 	 cellVCenterText($range);
			
				 	 $colonne = 'G'; //la colonne commentaires
				 	 	
				 	 for ($nb = 0; $nb < $nb_colonnes-1; $nb++)
				 	 	$colonne++;
				 	 
				 	 //Couleur - Initialisation des cases....
			 	 	 rangeColor('F'.$curseurLigne.':'.$colonne.($curseurLigne+5), $gris);
			 	 	 $colonne++;
				
				 	 $commentairesS = "";
				 	 $commentairesQ = "";
				 	 $commentairesC = "";
				 	 $commentairesD= "";
				 	 $commentairesP = "";
				 	 $commentairesF = "";
				 	 $pole = "";
				 	 
				 	 //Insertion des données
				 	 $curseur_colonne = 5;
				 	 
				 	 // 	pour l'exemple
				 	 $date = new DateTime($dateDebut);
				 	 $dt_datefin = new DateTime($dateFin);
// 				 	 $interval = new DateTime('today');
// 				 	 $interval = round(($dt_datefin->format('U') - $date->format('U')) / (60*60*24));
				 	 
				 	 $startTimeStamp = $date->format('U');
				 	 $endTimeStamp = $dt_datefin->format('U');
				 	 
				 	 $timeDiff = abs($endTimeStamp - $startTimeStamp);
				 	 
				 	 $numberDays = $timeDiff/86400;  // 86400 seconds in one day
				 	 
				 	 // and you might want to convert to integer
				 	 $numberDays = intval($numberDays);
				 	 
				 	 
				 	 
				 	 while($row=mysqli_fetch_array($result_sqcdpf)  and $numberDays> 0) {	 	 	
			
			 	 	 		//Caller la bonne date sur la bonne colonne
			 	 	 		$daterow = new DateTime($row['DateSQCDPF']);
			 	 	 		$i = round(($daterow->format('U') - $date->format('U')) / (60*60*24));
			 	 	 		//-----------------------------------------------------------------------------
			 	 	 		
			 	 	 		$startTimeStamp = $daterow->format('U');
			 	 	 		$endTimeStamp = $date->format('U');
			 	 	 		
			 	 	 		$timeDiff = abs($endTimeStamp - $startTimeStamp);
			 	 	 		
			 	 	 		$numberDays = $timeDiff/86400;  // 86400 seconds in one day
			 	 	 		
			 	 	 		// and you might want to convert to integer
			 	 	 		$numberDays = intval($numberDays);
							
			 	 	 		//-----------------------------------------------------------------------------
			 	 	 		
			 	 	 		// Il est possible de faire ça grâce à l'ORDER BY
			 	 	 		if ($numberDays> 0) {
			 	 	 			$n = $numberDays;
			 	 	 			for ($curseur = 0; $curseur < $n; $curseur++) {
			 	 	 				$curseur_colonne++;
			 	 	 				$date->modify('+1 day'); // Décallage d'une journée
			 	 	 			} 
			 	 	 		} 
			 	 	 		
			 	 	 		if ($numberDays< 0)
			 	 	 			$i = round(($dt_date->format('U') - $daterow->format('U')) / (60*60*24));
	 	 	 				
			 				//if ($i->format("%a") == 0)
			 	 	 					 	 	
							$sheet->setCellValueByColumnAndRow($curseur_colonne, $curseurLigne, utf8_encode($row['S_J_1']));
					 	 	$sheet->setCellValueByColumnAndRow($curseur_colonne, ($curseurLigne+1), utf8_encode($row['Q_J_1']));
				 	 		$sheet->setCellValueByColumnAndRow($curseur_colonne, ($curseurLigne+2), utf8_encode($row['C_J_1']));
				 	 		$sheet->setCellValueByColumnAndRow($curseur_colonne, ($curseurLigne+3), utf8_encode($row['D_J_1']));
				 	 		$sheet->setCellValueByColumnAndRow($curseur_colonne, ($curseurLigne+4), utf8_encode($row['P_J_1']));
				 	 		$sheet->setCellValueByColumnAndRow($curseur_colonne, ($curseurLigne+5), utf8_encode($row['F_J_1']));
			
							// 				mise en couleurs
			 				cellSqcdpfColor($curseur_colonne, $curseurLigne, $row['S_J_1']);
			 		 	 	cellSqcdpfColor($curseur_colonne, ($curseurLigne+1), $row['Q_J_1']);
			 	 	 		cellSqcdpfColor($curseur_colonne, ($curseurLigne+2), $row['C_J_1']);
			 	 	 		cellSqcdpfColor($curseur_colonne, ($curseurLigne+3), $row['D_J_1']);
			 	 	 		cellSqcdpfColor($curseur_colonne, ($curseurLigne+4), $row['P_J_1']);
			 	 	 		cellSqcdpfColor($curseur_colonne, ($curseurLigne+5), $row['F_J_1']);
				 	 		
				 	 		if($row['CommentaireS_J_1'] <> "")
				 	 			$commentairesS .= $row['DateSQCDPF'].' - '.$row['CommentaireS_J_1'].chr(13).chr(10);
				 	 			
				 	 		if($row['CommentaireQ_J_1'] <> "")
				 	 			$commentairesQ .= $row['DateSQCDPF'].' - '.$row['CommentaireQ_J_1'].chr(13).chr(10);
				 	 			
							if($row['CommentaireC_J_1'] <> "")
				 				$commentairesC .= $row['DateSQCDPF'].' - '.$row['CommentaireC_J_1'].chr(13).chr(10);
				 	 				
							if($row['CommentaireD_J_1'] <> "")
								$commentairesD .= $row['DateSQCDPF'].' - '.$row['CommentaireD_J_1'].chr(13).chr(10);
					 					
					 		if($row['CommentaireP_J_1'] <> "")
					 			$commentairesP .= $row['DateSQCDPF'].' - '.$row['CommentaireP_J_1'].chr(13).chr(10);
					 						
				 			if($row['CommentaireF_J_1'] <> "")
				 				$commentairesF .= $row['DateSQCDPF'].' - '.$row['CommentaireF_J_1'].chr(13).chr(10);
			
				 	 		$curseur_colonne++;
				 	 		$date->modify('+1 day'); // Décallage d'une journée
				 	 		$interval = round(($dt_datefin->format('U') - $date->format('U')) / (60*60*24));
				 	 		
			 	 	 }
			
			 	 //Ecrire les commentaires
			 	 $sheet->setCellValue($colonne.$curseurLigne, utf8_encode($commentairesS));
			 	 $sheet->setCellValue($colonne.($curseurLigne+1), utf8_encode($commentairesQ));
			 	 $sheet->setCellValue($colonne.($curseurLigne+2), utf8_encode($commentairesC));
			 	 $sheet->setCellValue($colonne.($curseurLigne+3), utf8_encode($commentairesD));
			 	 $sheet->setCellValue($colonne.($curseurLigne+4), utf8_encode($commentairesP));
			 	 $sheet->setCellValue($colonne.($curseurLigne+5), utf8_encode($commentairesF)); 	 
			
			 	 //Formatter les commentaires
			 	 $range = $colonne.$curseurLigne.":".$colonne.($curseurLigne+5);
			 	 cellBorders($range);
			 	 cellBordersMedium($range);
			 	 $sheet->getStyle($colonne.$curseurLigne.':'.	$colonne.($curseurLigne+5))->getAlignment()->setWrapText(true);
			 	 
				 $curseurLigne+=6;	
				}
				$saveCurseur = $curseurLigne;
			}
			
			function getData($BDD) {
			 	global $result_prestas;
			 	global $dateDebut, $dateFin;
			 	global $test;
			 	
				// Executer les requêtes SQL pour récupérer les données
			 	$req_prestas = "SELECT DISTINCT ";
			 	$req_prestas .= "		Id_secteur, ";
			 	$req_prestas .= "		new_v2sqcdpf.Id_prestation, ";
			 	$req_prestas .= "		Id_Pole, ";
			 	$req_prestas .= "		new_competences_pole.Libelle AS LibellePole, ";
				$req_prestas .= "		new_competences_prestation.Libelle, ";
			 	$req_prestas .= "		new_secteur.Libelle AS LibelleSecteur ";
			 	$req_prestas .= "FROM ";
			 	$req_prestas .= "		new_v2sqcdpf LEFT JOIN new_competences_pole ON new_competences_pole.Id = new_v2sqcdpf.Id_Pole, ";
			 	$req_prestas .= "		new_competences_prestation LEFT JOIN new_secteur ON new_competences_prestation.Id_secteur  =  new_secteur.Id ";
			 	$req_prestas .= "WHERE ";
			 	$req_prestas .= "		new_v2sqcdpf.Id_Prestation = new_competences_prestation.Id ";
			 	$req_prestas .=	"		AND DateSQCDPF >= '".$dateDebut." 00:00:00' ";
 				$req_prestas .=	"		AND DateSQCDPF <= '".$dateFin." 23:59:59' ";
			 	$req_prestas .= "ORDER BY Id_secteur, new_v2sqcdpf.Id_prestation, Id_Pole; ";
			 	
			 	$result_prestas = mysqli_query($BDD,$req_prestas); 
			}
			
			function getDataSqcdpf($Id_Secteur, $Id_presta, $Id_pole, $BDD) {
				global $result_sqcdpf;
				global 	$dateDebut, 	$dateFin;
				
				// 	le contenu sqcdpf
				$req_sqcdpf ="SELECT ";
				$req_sqcdpf .="				Id_Secteur, "; 
				$req_sqcdpf .="				new_v2sqcdpf.Id_prestation, ";
				$req_sqcdpf .="				new_v2sqcdpf.Id_Pole, ";
				$req_sqcdpf .="				DateSQCDPF, ";
				$req_sqcdpf .="				new_competences_pole.Libelle AS LibellePole, ";
				$req_sqcdpf .="				S_J_1, Q_J_1, C_J_1, D_J_1, P_J_1, F_J_1, ";
				$req_sqcdpf .="				CommentaireS_J_1, 	CommentaireQ_J_1, 	CommentaireC_J_1, 	CommentaireD_J_1, 	CommentaireP_J_1, 	CommentaireF_J_1 ";
				$req_sqcdpf .="FROM ";
				$req_sqcdpf .="				new_v2sqcdpf ";
				$req_sqcdpf .="				LEFT JOIN new_competences_pole ON new_competences_pole.Id = ".$Id_pole." AND new_competences_pole.Id_Prestation = ".$Id_presta.", ";
				$req_sqcdpf .="				new_competences_prestation ";
				$req_sqcdpf .="WHERE ";
				$req_sqcdpf .="				new_v2sqcdpf.Id_Prestation = new_competences_prestation.Id ";
				$req_sqcdpf .="AND Id_secteur = ".$Id_Secteur." ";
				$req_sqcdpf .="AND new_v2sqcdpf.Id_Prestation = ".$Id_presta." ";
				$req_sqcdpf .="AND new_v2sqcdpf.Id_Pole = ".$Id_pole." ";
				$req_sqcdpf .="AND DateSQCDPF >= '".$dateDebut." 00:00:00' ";
				$req_sqcdpf .="AND DateSQCDPF <= '".$dateFin." 23:59:59'";
				$req_sqcdpf .="ORDER BY DateSQCDPF, Id_Secteur, new_v2sqcdpf.Id_Prestation, new_v2sqcdpf.Id_Pole;";
			
				$result_sqcdpf = mysqli_query($BDD,$req_sqcdpf);
			}
			
			function ecrireTableau($BDD) {
				global $result_prestas;
				//Ecrire le tableau dans le fichier Excel
				global	$nomSecteur, $saveCurseur, $secteurs;
				global $sheet;
				global $dateDebut, $dateFin;
			
					// 	Ici il faut faire une boucle sur les secteurs
					$idSect = "";
					$cursDebut = 3;
					$nomSect = "-------";
			
					//Ecrire les prestations
					while($row=mysqli_fetch_array($result_prestas)) {				
						$nomSect = $row['LibelleSecteur'];
						
						//[215] - Le nom du secteur pour chaque ligne
						//formattage des noms de secteurs
	 					$sheet->setCellValue("B".$cursDebut, utf8_encode($nomSect));
	 					$sheet->setCellValue("B".($cursDebut+1), utf8_encode($nomSect));
	 					$sheet->setCellValue("B".($cursDebut+2), utf8_encode($nomSect));
 						$sheet->setCellValue("B".($cursDebut+3), utf8_encode($nomSect));
 						$sheet->setCellValue("B".($cursDebut+4), utf8_encode($nomSect));
 						$sheet->setCellValue("B".($cursDebut+5), utf8_encode($nomSect));

 						$cursDebut +=6;

						getDataSqcdpf($row['Id_secteur'], $row['Id_prestation'], $row['Id_Pole'], $BDD);
						mySheetAddPrestation($row['Id_secteur'], $row['Libelle'], $row['LibellePole']);
					}
			}
			
			function miseEnPageGlobale() {
				global $curseurLigne;
				global $nb_colonnes;
				$curseurLigne--;
				
				//Secteurs
				cellBordersMedium("B2:B".$curseurLigne);
				// Prestations
				cellBordersMedium("C2:C".$curseurLigne);
				//Lettre
				cellBordersMedium("D2:D".$curseurLigne);
			
			}
			
			
			function enregitrer($workbook) {
				global $workbook;
				
				//Enregistrement du fichier excel
			 	$nomFichier = 'Recapitulatif_SQCDPF';
			 	$ext  = 'xlsx';
			 	$header = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
			 	$chemin = '../../tmp/'.$nomFichier.'.'.$ext;
				
				$writer = new PHPExcel_Writer_Excel2007($workbook);
				
			 	header('Content-type:'.$header);
			 	header('Content-Disposition:inline;filename='.$nomFichier.'.'.$ext);
			 	
				$writer->save($chemin);	
				readfile($chemin);
			}
			
			function free() {
			  	mysqli_free_result($resultnew_perfos);	// Libération des résultats
			  	mysqli_close($BDD);					// Fermeture de la connexion
			}
			
			//exemples d'utilisation
			// cellColor('B5', 'F28A8C');
			// cellColor('G5', 'F28A8C');
			// cellColor('A7:I7', 'F28A8C');
			// cellColor('A17:I17', 'F28A8C');
			// cellColor('A30:Z30', 'F28A8C');
			
			function rangeColor($range, $color){
				global $sheet;
			
				$sheet->getStyle($range)->getFill()->applyFromArray(array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array(
								'rgb' => $color
						)
				));
			}
			
			function cellColor($colonne, $ligne, $color){
				global $sheet;
			
				$sheet->getStyleByColumnAndRow($colonne, $ligne)->getFill()->applyFromArray(array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array(
								'rgb' => $color
						)
				));
			}
			
			function cellTextColor($colonne, $ligne, $color){
				global $sheet;
			
				$sheet->getStyleByColumnAndRow($colonne, $ligne)->getFont()->applyFromArray(array(
						'type' => PHPExcel_Style_Font::UNDERLINE_NONE,
						'color' => array(
								'rgb' => $color
						)
				));
			}
			
			
			function cellCenterText($cells) {
				global $sheet;
				
				$style = array(
						'alignment' => array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						)
				);
				
				$sheet->getStyle($cells)->applyFromArray($style);
			}
			
			function cellVCenterText($cells) {
				global $sheet;
				
				$style = array(
						'alignment' => array(
								'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
						)
				);
			
				$sheet->getStyle($cells)->applyFromArray($style);
			}
			
			function cellBorders($cells) {
			 	global $sheet;
				
				$sheet->getStyle($cells)->getBorders()->applyFromArray(
						array(
								'allborders' => array(
										'style' => PHPExcel_Style_Border::BORDER_THIN,
										'color' => array(
												'rgb' => '000000'
										)
								)
						)
						);	
			}
			
			function cellBordersMedium($cells) {
				global $sheet;
				
				$style = array(
						'borders'=>array(
								'top'=>array(
										'style'=>PHPExcel_Style_Border::BORDER_MEDIUM),
								'bottom'=>array(
										'style'=>PHPExcel_Style_Border::BORDER_MEDIUM),
								'left'=>array(
										'style'=>PHPExcel_Style_Border::BORDER_MEDIUM),
								'right'=>array(
										'style'=>PHPExcel_Style_Border::BORDER_MEDIUM)
						)
				);
				
				$sheet->getStyle($cells)->applyFromArray($style);
			}
			
			function cellSqcdpfColor($colonne, $ligne, $valeur) {
				global $blanc, $vert, $rouge;
				
				switch($valeur) {
					
					case 0: // pas rempli
						cellColor($colonne, $ligne, $blanc);
						cellTextColor($colonne, $ligne, $blanc);
						break;
						
					case 1: // Rempli OK
						cellColor($colonne, $ligne, $vert);
						cellTextColor($colonne, $ligne, $vert);
						break;
						
					case 2: // Rempli problème(s)
						cellColor($colonne, $ligne, $rouge);
						cellTextColor($colonne, $ligne, $rouge);
						break;
				}
			}

?>

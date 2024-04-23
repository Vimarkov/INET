<?php
require_once("MultiAutorisationTravail_Excel_requetes.php");
require_once("Globales_Fonctions.php");

/**
 * creerFichier
 *
 * Créer une instance de fichier Excel pour le manipuler
 *
 * @return PHPExcel Le workbook
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function creerFichier()
{
	//Creation du fichier Excel
	$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
	$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
	PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
	$workbook = new PHPExcel;
	$sheet = $workbook->getActiveSheet();
	$sheet->setTitle(utf8_encode("Autorisation travail"));
	
	return $workbook;
}

/**
 * enregistrerFichier
 *
 * Enregistre le fichier Excel
 *
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function enregistrerFichier($workbook)
{
	global $LangueAffichage;

	//Enregistrement du fichier excel
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	if($LangueAffichage=="FR")
		header('Content-Disposition: attachment;filename="AutorisationTravail.xlsx"');
		else
			header('Content-Disposition: attachment;filename="WorkAuthorization.xlsx"');
			header('Cache-Control: max-age=0');
			
			$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
			$chemin = '../../tmp/AutorisationTravail.xlsx';
			
			$writer->save($chemin);
			readfile($chemin);
}

/**
 * calculColonne
 *
 * @param int $offset le nombre de colonne a d\écaller
 * @param string $colonneParDefaut le caractère de la colonne par defaut (lorsqu'il n'y a qu'une seule autorisation)
 *
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function calculColonne($colonneParDefaut, $offset = 0)
{
	//Calcul de la colonne
	$lettre = ord($colonneParDefaut);
	$lettre += $offset;
	
	//modulo
	//65 = la lettre A
	$mod = ($lettre - 65) % 26;
	$disaine = (($lettre - 65)-$mod) / 26;
	
	if($disaine > 0)
		return chr($disaine+64).chr($mod+65);
		else
			return chr($mod+65);
}

/**
 * ecrireAutorisationDeTravail
 *
 * Ecrit une autorisation de conduite
 *
 * @param PHPExcel_Worksheet $sheet La feuille de calcul MSExcel
 *
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function ecrireAutorisationDeTravail($workbook, $Id, $offsetColonne = 0, $offsetLigne = 0,$nb)
{
	global $LangueAffichage;
	
	$sheet = $workbook->getActiveSheet();
	
	//Remplissage du fichier Excel
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('logo');
	$objDrawing->setDescription('PHPExcel logo');
	$objDrawing->setPath('../../Images/Logos/Logo_AAA_FR.png');
	$objDrawing->setHeight(48);
	$objDrawing->setWidth(76);
	$objDrawing->setCoordinates(calculColonne('A', $offsetColonne).strval(1+$offsetLigne));
	$objDrawing->setOffsetX(20);
	$objDrawing->setOffsetY(8);
	$objDrawing->setWorksheet($sheet);
		
	$sheet->getStyle(calculColonne('A', $offsetColonne).':'.calculColonne('N', $offsetColonne))->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffffff'))));
	
	$col=calculColonne('A', $offsetColonne);
	for($i=1;$i<=14;$i++){
		$sheet->getColumnDimension($col)->setWidth(6);
		$col++;
	}
	$sheet->getColumnDimension($col)->setWidth(3);
	
	//Responsable plateforme
	$Resp="";
	$req="SELECT new_rh_etatcivil.Id, Nom, Prenom
		FROM new_competences_personne_poste_plateforme
		LEFT JOIN new_rh_etatcivil
		ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
		WHERE Id_Poste=9 
		AND Backup=0
		AND Id_Plateforme IN (
		SELECT Id_Plateforme 
		FROM new_competences_personne_plateforme
		WHERE Id_Personne=".$Id."
		) ";
		$resultResp=getRessource($req);
		$nbResp=mysqli_num_rows($resultResp);

		if($nbResp>0){
			$rowResp=mysqli_fetch_array($resultResp);
			if($rowResp['Id']==10749){
				$Resp=substr($rowResp['Prenom'],0,1).". B. ".$rowResp['Nom'];
			}
			else{
				$Resp=substr($rowResp['Prenom'],0,1).". ".$rowResp['Nom'];
			}
		}
	
	$req="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$Id;
	$resultPers=getRessource($req);
	$rowPers=mysqli_fetch_array($resultPers);
	
	if($LangueAffichage=="FR"){
		$sheet->setCellValue(calculColonne('A', $offsetColonne).strval(4+$offsetLigne),utf8_encode('AUTORISATION DE CONDUITE'));
		$sheet->setCellValue(calculColonne('H', $offsetColonne).strval(2+$offsetLigne),utf8_encode('Moyens'));
		$sheet->setCellValue(calculColonne('K', $offsetColonne).strval(2+$offsetLigne),utf8_encode('Catégories'));
		$sheet->setCellValue(calculColonne('M', $offsetColonne).strval(2+$offsetLigne),utf8_encode('Fin de validité'));
		
		$sheet->setCellValue(calculColonne('A', $offsetColonne).strval(6+$offsetLigne),utf8_encode('Nom :'));
		$sheet->setCellValue(calculColonne('A', $offsetColonne).strval(7+$offsetLigne),utf8_encode('Prénom :'));
		$sheet->setCellValue(calculColonne('A', $offsetColonne).strval(9+$offsetLigne),utf8_encode('Délivrée par : '.$Resp));
	}
	else
	{
		$sheet->setCellValue(calculColonne('A', $offsetColonne).strval(4+$offsetLigne),utf8_encode('AUTHORIZATION OF WORK'));
		$sheet->setCellValue(calculColonne('H', $offsetColonne).strval(2+$offsetLigne),utf8_encode('Means'));
		$sheet->setCellValue(calculColonne('K', $offsetColonne).strval(2+$offsetLigne),utf8_encode('Categories'));
		$sheet->setCellValue(calculColonne('M', $offsetColonne).strval(2+$offsetLigne),utf8_encode('End of validity'));
		
		$sheet->setCellValue(calculColonne('A', $offsetColonne).strval(6+$offsetLigne),utf8_encode('Last name :'));
		$sheet->setCellValue(calculColonne('A', $offsetColonne).strval(7+$offsetLigne),utf8_encode('First name :'));
		$sheet->setCellValue(calculColonne('A', $offsetColonne).strval(9+$offsetLigne),utf8_encode('Delivered by : '.$Resp));
	}
	
	$sheet->getRowDimension($offsetLigne)->setRowHeight(14);

	$sheet->setCellValue(calculColonne('B', $offsetColonne).strval(6+$offsetLigne),utf8_encode($rowPers['Nom']));
	$sheet->setCellValue(calculColonne('C', $offsetColonne).strval(7+$offsetLigne),utf8_encode($rowPers['Prenom']));
	$sheet->getStyle(calculColonne('B', $offsetColonne).strval(6+$offsetLigne))->getFont()->setBold(true);//Texte en gras
	$sheet->getStyle(calculColonne('C', $offsetColonne).strval(7+$offsetLigne))->getFont()->setBold(true);//Texte en gras
	
	$sheet->mergeCells(calculColonne('E', $offsetColonne).strval(6+$offsetLigne).':'.calculColonne('G', $offsetColonne).strval(7+$offsetLigne));
	$sheet->mergeCells(calculColonne('E', $offsetColonne).strval(9+$offsetLigne).':'.calculColonne('G', $offsetColonne).strval(10+$offsetLigne));
	$sheet->getStyle(calculColonne('E', $offsetColonne).strval(6+$offsetLigne).':'.calculColonne('G', $offsetColonne).strval(7+$offsetLigne))->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'eeece0'))));
	$sheet->getStyle(calculColonne('E', $offsetColonne).strval(9+$offsetLigne).':'.calculColonne('G', $offsetColonne).strval(10+$offsetLigne))->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'eeece0'))));
	
	$sheet->getStyle(calculColonne('A', $offsetColonne).strval(4+$offsetLigne))->getFont()->setBold(true);//Texte en gras
	$sheet->getStyle(calculColonne('A', $offsetColonne).strval(4+$offsetLigne))->getFont()->setSize(14);//Taille du texte
	$sheet->getStyle(calculColonne('A', $offsetColonne).strval(9+$offsetLigne))->getFont()->setSize(10);//Taille du texte
	$sheet->getStyle(calculColonne('A', $offsetColonne).strval(4+$offsetLigne))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$sheet->getRowDimension(4+$offsetLigne)->setRowHeight(14);
	$sheet->mergeCells(calculColonne('A', $offsetColonne).strval(4+$offsetLigne).':'.calculColonne('G', $offsetColonne).strval(4+$offsetLigne));
	$sheet->mergeCells(calculColonne('H', $offsetColonne).strval(2+$offsetLigne).':'.calculColonne('J', $offsetColonne).strval(2+$offsetLigne));
	$sheet->mergeCells(calculColonne('K', $offsetColonne).strval(2+$offsetLigne).':'.calculColonne('L', $offsetColonne).strval(2+$offsetLigne));
	$sheet->mergeCells(calculColonne('M', $offsetColonne).strval(2+$offsetLigne).':'.calculColonne('N', $offsetColonne).strval(2+$offsetLigne));
	$sheet->getStyle(calculColonne('H', $offsetColonne).strval(2+$offsetLigne).':'.calculColonne('N', $offsetColonne).strval(2+$offsetLigne))->getFont()->setBold(true);//Texte en gras
	$sheet->getStyle(calculColonne('H', $offsetColonne).strval(2+$offsetLigne).':'.calculColonne('N', $offsetColonne).strval(2+$offsetLigne))->getFont()->setSize(11);//Taille du texte
	$sheet->getStyle(calculColonne('H', $offsetColonne).strval(2+$offsetLigne).':'.calculColonne('N', $offsetColonne).strval(2+$offsetLigne))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle(calculColonne('H', $offsetColonne).strval(2+$offsetLigne).':'.calculColonne('N', $offsetColonne).strval(2+$offsetLigne))->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'00b050'))));
	$sheet->getStyle(calculColonne('H', $offsetColonne).strval(2+$offsetLigne).':'.calculColonne('N', $offsetColonne).strval(2+$offsetLigne))->getFont()->getColor()->setRGB('ffffff');
	
	$sheet->mergeCells(calculColonne('B', $offsetColonne).strval(6+$offsetLigne).':'.calculColonne('D', $offsetColonne).strval(6+$offsetLigne));
	$sheet->mergeCells(calculColonne('C', $offsetColonne).strval(7+$offsetLigne).':'.calculColonne('D', $offsetColonne).strval(7+$offsetLigne));
	$sheet->getStyle(calculColonne('B', $offsetColonne).strval(6+$offsetLigne).':'.calculColonne('D', $offsetColonne).strval(6+$offsetLigne))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$sheet->getStyle(calculColonne('C', $offsetColonne).strval(7+$offsetLigne).':'.calculColonne('D', $offsetColonne).strval(7+$offsetLigne))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	//Liste des autorisations de conduite
	$resultAT=getRessource(getListeAutorisationsDeTravail($Id));
	$nbAT=mysqli_num_rows($resultAT);
	
	//Mise à jour de la date autorisations de conduite
	$resultUpdateAT=getRessource(getMiseAJourDesAutorisationsDeTravail($Id));
	
	$req="UPDATE new_rh_etatcivil SET DateEditionAutorisationTravail='".date('Y-m-d')."' WHERE Id=".$Id;
	$resultUpdtPers=getRessource($req);
	
	$i=3;
	if($nbAT>0)
		while($rowAT=mysqli_fetch_array($resultAT)){
			$sheet->setCellValue(calculColonne('H', $offsetColonne).($i+$offsetLigne),utf8_encode($rowAT['Moyen']));
			$sheet->setCellValue(calculColonne('K', $offsetColonne).($i+$offsetLigne),utf8_encode($rowAT['Categorie']));
			$sheet->setCellValue(calculColonne('M', $offsetColonne).($i+$offsetLigne),utf8_encode(AfficheDateJJ_MM_AAAA($rowAT['Date_Fin'])));
			$sheet->getStyle(calculColonne('H', $offsetColonne).($i+$offsetLigne))->getFont()->setSize(7);//Taille du texte
			$sheet->mergeCells(calculColonne('H', $offsetColonne).($i+$offsetLigne).':'.calculColonne('J', $offsetColonne).($i+$offsetLigne));
			$sheet->mergeCells(calculColonne('K', $offsetColonne).($i+$offsetLigne).':'.calculColonne('L', $offsetColonne).($i+$offsetLigne));
			$sheet->mergeCells(calculColonne('M', $offsetColonne).($i+$offsetLigne).':'.calculColonne('N', $offsetColonne).($i+$offsetLigne));
			$i++;
	}
	
	if($i<9){
		for($k=$i;$k<=9;$k++){
			$sheet->mergeCells(calculColonne('H', $offsetColonne).($k+$offsetLigne).':'.calculColonne('J', $offsetColonne).($k+$offsetLigne));
			$sheet->mergeCells(calculColonne('K', $offsetColonne).($k+$offsetLigne).':'.calculColonne('L', $offsetColonne).($k+$offsetLigne));
			$sheet->mergeCells(calculColonne('M', $offsetColonne).($k+$offsetLigne).':'.calculColonne('N', $offsetColonne).($k+$offsetLigne));
		}
		$i=10;
	}
	
	if($LangueAffichage=="FR")
		$sheet->setCellValue(calculColonne('H', $offsetColonne).($i+$offsetLigne),utf8_encode('Toute personne ne respectant pas les règles de sécurité se verra retirer son autorisation de conduite.'));
		else
			$sheet->setCellValue(calculColonne('H', $offsetColonne).($i+$offsetLigne),utf8_encode('Anyone who does not respect the safety rules will be removed from his driving authorization.'));
			
			$sheet->mergeCells(calculColonne('H', $offsetColonne).($i+$offsetLigne).':'.calculColonne('N', $offsetColonne).($i+$offsetLigne+1));
			$sheet->getStyle(calculColonne('H', $offsetColonne).($i+$offsetLigne).':'.calculColonne('N', $offsetColonne).($i+$offsetLigne+1))->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ff0000'))));
			$sheet->getStyle(calculColonne('H', $offsetColonne).($i+$offsetLigne).':'.calculColonne('N', $offsetColonne).($i+$offsetLigne+1))->getFont()->getColor()->setRGB('ffffff');
			$sheet->getStyle(calculColonne('H', $offsetColonne).($i+$offsetLigne).':'.calculColonne('N', $offsetColonne).($i+$offsetLigne+1))->getFont()->setSize(8);//Taille du texte
			$sheet->getStyle(calculColonne('H', $offsetColonne).($i+$offsetLigne).':'.calculColonne('N', $offsetColonne).($i+$offsetLigne+1))->getAlignment()->setWrapText(true);
			$sheet->getStyle(calculColonne('H', $offsetColonne).($i+$offsetLigne).':'.calculColonne('N', $offsetColonne).($i+$offsetLigne+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle(calculColonne('H', $offsetColonne).($i+$offsetLigne).':'.calculColonne('N', $offsetColonne).($i+$offsetLigne+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			$sheet->getStyle(calculColonne('H', $offsetColonne).strval(2+$offsetLigne).':'.calculColonne('N', $offsetColonne).($i+$offsetLigne+1))->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			
			$sheet->getStyle(calculColonne('G', $offsetColonne).strval(1+$offsetLigne).':'.calculColonne('G', $offsetColonne).($i+$offsetLigne+1))->getBorders()->applyFromArray(array('right' => array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000'))));
			$sheet->getStyle(calculColonne('A', $offsetColonne).strval(1+$offsetLigne).':'.calculColonne('A', $offsetColonne).($i+$offsetLigne+1))->getBorders()->applyFromArray(array('left' => array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000'))));
			$sheet->getStyle(calculColonne('N', $offsetColonne).strval(1+$offsetLigne).':'.calculColonne('N', $offsetColonne).($i+$offsetLigne+1))->getBorders()->applyFromArray(array('right' => array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000'))));
			
			$sheet->getStyle(calculColonne('A', $offsetColonne).strval(1+$offsetLigne).':'.calculColonne('N', $offsetColonne).strval(1+$offsetLigne))->getBorders()->applyFromArray(array('top' => array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000'))));
			$sheet->getStyle(calculColonne('A', $offsetColonne).($i+$offsetLigne+1).':'.calculColonne('N', $offsetColonne).($i+$offsetLigne+1))->getBorders()->applyFromArray(array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK ,'color' => array('rgb' => '#000000'))));
			$sheet->getStyle(calculColonne('H', $offsetColonne).strval(2+$offsetLigne).':'.calculColonne('N', $offsetColonne).($i+$offsetLigne+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			//Correction des hauteurs de lignes
			for($u = 0; $u <11; $u++)
				$sheet->getRowDimension($offsetLigne+$u)->setRowHeight(14);
			
	if($nb==0){
		$sheet->getRowDimension($offsetLigne)->setRowHeight(30);
	}
}
?>
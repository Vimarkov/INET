<?php
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

$IdPersonne = $_GET['Id_Personne'];
$IdPrestation = $_GET['Id_Prestation'];
$IdPole = $_GET['Id_Pole'];

$req = "SELECT new_action.Id, new_action.Id_ActionLiee ";
$req .= "FROM new_action ";
$req .= "WHERE ";
$req .= "Type='SQCDPF' OR ";
$req .= "Type='PERFOS' AND ";

$NomPrestation = "";
$NomPole = "";
if ($IdPrestation <> 0){
	$req .= "new_action.Id_Prestation =".$IdPrestation." AND ";
	
	$reqPrestation = "SELECT Libelle FROM new_competences_prestation WHERE Id='".$IdPrestation."'";
	$resultPrestation=mysqli_query($bdd,$reqPrestation);
	$nbPrestation=mysqli_num_rows($resultPrestation);
	if ($nbPrestation>0){
		$row=mysqli_fetch_array($resultPrestation);
		$NomPrestation = $row[0];
	}
	
	if ($IdPole <> 0){
	
		$reqPole = "SELECT Libelle FROM new_competences_pole WHERE Id='".$IdPole."'";
		$resultPole=mysqli_query($bdd,$reqPole);
		$nbPole=mysqli_num_rows($resultPole);
		if ($nbPole>0){
			$row=mysqli_fetch_array($resultPole);
			$NomPole = $row[0];
		}
		$req .= "new_action.Id_Pole =".$IdPole." AND ";
		
		$reqMin = "SELECT MIN(Id_Poste) AS Id_Poste FROM  new_competences_personne_poste_prestation WHERE Id_Personne=".$IdPersonne. " AND Id_Prestation=".$IdPrestation;
		$reqMin .= "  AND Id_Pole=".$IdPole;
		$reqMax = "SELECT MAX(Id_Poste) AS Id_Poste FROM  new_competences_personne_poste_prestation WHERE Id_Personne=".$IdPersonne. " AND Id_Prestation=".$IdPrestation;
		$reqMax .= "  AND Id_Pole=".$IdPole;
		$resultMin=mysqli_query($bdd,$reqMin);
		$rowMin=mysqli_fetch_array($resultMin);
		$resultMax=mysqli_query($bdd,$reqMax);
		$rowMax=mysqli_fetch_array($resultMax);
		$niveauMin=0;
		$niveauMax=0;
		if($rowMin['Id_Poste'] < 3){$niveauMin=1;}
		else if($rowMin['Id_Poste'] == 3 || $rowMin['Id_Poste'] == 5){$niveauMin=2;}
		else if($rowMin['Id_Poste'] == 4 || $rowMin['Id_Poste'] == 6 || $rowMin['Id_Poste'] == 7){$niveauMin=3;}
		else if($rowMin['Id_Poste'] == 8 || $rowMin['Id_Poste'] == 9){$niveauMin=4;}
		if($rowMax['Id_Poste'] < 3){$niveauMax=1;}
		else if($rowMax['Id_Poste'] == 3 || $rowMax['Id_Poste'] == 5){$niveauMax=2;}
		else if($rowMax['Id_Poste'] == 4 || $rowMax['Id_Poste'] == 6 || $rowMax['Id_Poste'] == 7){$niveauMax=3;}
		else if($rowMax['Id_Poste'] == 8 || $rowMax['Id_Poste'] == 9){$niveauMax=4;}
		$req .= "((new_action.Niveau >=".$niveauMin." AND new_action.Niveau <=".$niveauMax.") OR (new_action.NiveauCreateur >=".$niveauMin." AND new_action.NiveauCreateur <=".$niveauMax.")) AND ";
	}
	else{
		$reqPrestaPoste = "SELECT DISTINCT Id_Prestation, Id_Pole FROM new_competences_personne_poste_prestation ";
		$reqPrestaPoste .= "WHERE Id_Personne=".$IdPersonne." AND Id_Prestation=".$IdPrestation.";";
		$resultPrestaPoste=mysqli_query($bdd,$reqPrestaPoste);
		$nbPrestaPoste=mysqli_num_rows($resultPrestaPoste);
		if($nbPrestaPoste > 0){
			if($nbPrestaPoste > 1){$req .= "(";}
			while($rowPrestaPoste=mysqli_fetch_array($resultPrestaPoste)){
				$req .= "(new_action.Id_Pole =".$rowPrestaPoste['Id_Pole']." ";
				
				$reqMin = "SELECT MIN(Id_Poste) AS Id_Poste FROM  new_competences_personne_poste_prestation WHERE Id_Personne=".$IdPersonne. " AND Id_Prestation=".$IdPrestation;
				$reqMin .= "  AND Id_Pole=".$rowPrestaPoste['Id_Pole'];
				$reqMax = "SELECT MAX(Id_Poste) AS Id_Poste FROM  new_competences_personne_poste_prestation WHERE Id_Personne=".$IdPersonne. " AND Id_Prestation=".$IdPrestation;
				$reqMax .= "  AND Id_Pole=".$rowPrestaPoste['Id_Pole'];
				$resultMin=mysqli_query($bdd,$reqMin);
				$rowMin=mysqli_fetch_array($resultMin);
				$resultMax=mysqli_query($bdd,$reqMax);
				$rowMax=mysqli_fetch_array($resultMax);
				$niveauMin=0;
				$niveauMax=0;
				if($rowMin['Id_Poste'] < 3){$niveauMin=1;}
				else if($rowMin['Id_Poste'] == 3 || $rowMin['Id_Poste'] == 5){$niveauMin=2;}
				else if($rowMin['Id_Poste'] == 4 || $rowMin['Id_Poste'] == 6 || $rowMin['Id_Poste'] == 7){$niveauMin=3;}
				else if($rowMin['Id_Poste'] == 8 || $rowMin['Id_Poste'] == 9){$niveauMin=4;}
				if($rowMax['Id_Poste'] < 3){$niveauMax=1;}
				else if($rowMax['Id_Poste'] == 3 || $rowMax['Id_Poste'] == 5){$niveauMax=2;}
				else if($rowMax['Id_Poste'] == 4 || $rowMax['Id_Poste'] == 6 || $rowMax['Id_Poste'] == 7){$niveauMax=3;}
				else if($rowMax['Id_Poste'] == 8 || $rowMax['Id_Poste'] == 9){$niveauMax=4;}
				$req .= "AND ((new_action.Niveau >=".$niveauMin." AND new_action.Niveau <=".$niveauMax.") OR (new_action.NiveauCreateur >=".$niveauMin." AND new_action.NiveauCreateur <=".$niveauMax.")) ";
				$req .= ") OR ";
			}
			$req = substr($req,0,-3);
			if($nbPrestaPoste > 1){$req .= ")";}
			$req .= " AND ";
		}
	}
}
else{
	$reqPrestaPoste = "SELECT DISTINCT Id_Prestation, Id_Pole FROM new_competences_personne_poste_prestation ";
	$reqPrestaPoste .= "WHERE Id_Personne=".$IdPersonne.";";
	$resultPrestaPoste=mysqli_query($bdd,$reqPrestaPoste);
	$nbPrestaPoste=mysqli_num_rows($resultPrestaPoste);
	if($nbPrestaPoste > 0){
		while($rowPrestaPoste=mysqli_fetch_array($resultPrestaPoste)){
			$req .= "(new_action.Id_Prestation =".$rowPrestaPoste['Id_Prestation']." AND new_action.Id_Pole =".$rowPrestaPoste['Id_Pole']." ";
			
			$reqMin = "SELECT MIN(Id_Poste) AS Id_Poste FROM  new_competences_personne_poste_prestation WHERE Id_Personne=".$IdPersonne. " AND Id_Prestation=".$rowPrestaPoste['Id_Prestation'];
			$reqMin .= "  AND Id_Pole=".$rowPrestaPoste['Id_Pole'];
			$reqMax = "SELECT MAX(Id_Poste) AS Id_Poste FROM  new_competences_personne_poste_prestation WHERE Id_Personne=".$IdPersonne. " AND Id_Prestation=".$rowPrestaPoste['Id_Prestation'];
			$reqMax .= "  AND Id_Pole=".$rowPrestaPoste['Id_Pole'];
			$resultMin=mysqli_query($bdd,$reqMin);
			$rowMin=mysqli_fetch_array($resultMin);
			$resultMax=mysqli_query($bdd,$reqMax);
			$rowMax=mysqli_fetch_array($resultMax);
			$niveauMin=0;
			$niveauMax=0;
			if($rowMin['Id_Poste'] < 3){$niveauMin=1;}
			else if($rowMin['Id_Poste'] == 3 || $rowMin['Id_Poste'] == 5){$niveauMin=2;}
			else if($rowMin['Id_Poste'] == 4 || $rowMin['Id_Poste'] == 6 || $rowMin['Id_Poste'] == 7){$niveauMin=3;}
			else if($rowMin['Id_Poste'] == 8 || $rowMin['Id_Poste'] == 9){$niveauMin=4;}
			if($rowMax['Id_Poste'] < 3){$niveauMax=1;}
			else if($rowMax['Id_Poste'] == 3 || $rowMax['Id_Poste'] == 5){$niveauMax=2;}
			else if($rowMax['Id_Poste'] == 4 || $rowMax['Id_Poste'] == 6 || $rowMax['Id_Poste'] == 7){$niveauMax=3;}
			else if($rowMax['Id_Poste'] == 8 || $rowMax['Id_Poste'] == 9){$niveauMax=4;}
			$req .= "AND ((new_action.Niveau >=".$niveauMin." AND new_action.Niveau <=".$niveauMax.") OR (new_action.NiveauCreateur >=".$niveauMin." AND new_action.NiveauCreateur <=".$niveauMax.")) ";
			$req .= ") OR ";
		}
		$req = substr($req,0,-3);
		$req .= " AND ";
	}
}

if (substr($req,-4) =="AND "){
	$req = substr($req,0,-4);
}
$req .= "ORDER BY DateCreation DESC ";
$leNom = substr($NomPrestation,0,4)." ".$NomPole;

// EN TETE
$sheet->mergeCells('A1:B1');
// [216] - Image du groupe
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('logo Group');
$objDrawing->setDescription('PHPExcel logo Group');
$objDrawing->setPath('../../Images/Logos/AAA_Group.gif');
$objDrawing->setWidth(100);
$objDrawing->setHeight(60);
$objDrawing->setCoordinates('A1');
$objDrawing->setOffsetX(10);
$objDrawing->setOffsetY(2);
$objDrawing->setWorksheet($sheet);
// [216] - Image de AAA
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('logo');
$objDrawing->setDescription('PHPExcel logo');
$objDrawing->setPath('../../Images/Logos/Logo_AAA_FR.png');
$objDrawing->setWidth(100);
$objDrawing->setHeight(60);
$objDrawing->setCoordinates('E1');
$objDrawing->setOffsetX(80);
$objDrawing->setOffsetY(2);
$objDrawing->setWorksheet($sheet);

$sheet->mergeCells('C1:F1');
$sheet->setCellValue('C1',utf8_encode("PLAN D'ACTIONS SQCDPF"));
$sheet->getStyle('C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('C1')->getFont()->setSize(24);
$sheet->getStyle('C1')->getFont()->setBold(true);
$sheet->mergeCells('G1:H1');
$sheet->setCellValue('G1',utf8_encode("Prestation/Produit"));
$sheet->getStyle('G1:H1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffff00'))));
// $sheet->setCellValue('J1',utf8_encode($leNom));
$sheet->getStyle('G1')->getFont()->setSize(16);
$sheet->getStyle('G1')->getFont()->setBold(true);
$sheet->getStyle('G1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


$sheet->getStyle('A1:H1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//CORPS
// 				[216] - Corrections
$sheet->setCellValue('A3',utf8_encode("Lettres clés"));
$sheet->setCellValue('B3',utf8_encode("Dates d'ouverture"));
$sheet->setCellValue('C3',utf8_encode("Problèmes (description)"));
$sheet->setCellValue('D3',utf8_encode("Actions"));
$sheet->setCellValue('E3',utf8_encode("Acteurs"));
$sheet->setCellValue('F3',utf8_encode("Délais"));
$sheet->setCellValue('G3',utf8_encode("Avancement"));
$sheet->setCellValue('H3',utf8_encode("Date de solde"));

$sheet->getStyle('A3:H3')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'b8cce4'))));
$sheet->getStyle('A3:H3')->getAlignment()->setWrapText(true);

$sheet->getStyle('A3:H3')->getFont()->setSize(12);
$sheet->getStyle('A3:H3')->getFont()->setBold(true);

$resultAction=mysqli_query($bdd,$req);
$nbAction=mysqli_num_rows($resultAction);
$ligne=4;
$tabId = array() ;
if($nbAction > 0){
	while($row=mysqli_fetch_array($resultAction)){
	
		$reqLiee = "SELECT new_action.Id, new_action.Type, new_action.Lettre, new_action.DateCreation, new_action.Id_Createur, new_action.Probleme, new_action.Action, new_action.Id_Acteur, ";
		$reqLiee .= "new_action.Delais, new_action.Avancement, new_action.DateSolde, new_action.Niveau, new_action.ReprisDQ506, new_action.Id_ActionLiee, ";
		$reqLiee .= "(SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = new_action.Id_Prestation) AS Prestation, new_action.Id_Prestation, ";
		$reqLiee .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id =new_action.Id_Pole) AS Pole, new_action.Id_Pole, ";
		$reqLiee .= "(SELECT new_rh_etatcivil.Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id =new_action.Id_Createur) AS NomCreateur, ";
		$reqLiee .= "(SELECT new_rh_etatcivil.Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id =new_action.Id_Createur) AS PrenomCreateur, ";
		$reqLiee .= "(SELECT new_rh_etatcivil.Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id =new_action.Id_Acteur) AS NomActeur, ";
		$reqLiee .= "(SELECT new_rh_etatcivil.Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id =new_action.Id_Acteur) AS PrenomActeur ";
		$reqLiee .= "FROM new_action ";
		if($row['Id_ActionLiee'] == 0){
			$reqLiee .= "WHERE new_action.Id =".$row['Id']." OR new_action.Id_ActionLiee=".$row['Id']." ";
		}
		else{
			$reqLiee .= "WHERE new_action.Id =".$row['Id_ActionLiee']." OR new_action.Id_ActionLiee=".$row['Id_ActionLiee']." ";
		}
		$reqLiee .= "ORDER BY Id ";
		$resultAction2=mysqli_query($bdd,$reqLiee);
		$nbAction2=mysqli_num_rows($resultAction2);
		
		while($rowAction=mysqli_fetch_array($resultAction2)){
			$bTrouve = false;
			foreach ($tabId as $value){
				if($rowAction['Id'] == $value){
					$bTrouve = true;
				}
			}
			if($bTrouve == false){
				$tabId[] = $rowAction['Id'];
				$sheet->getRowDimension($ligne)->setRowHeight(40);
				$sheet->getStyle('E'.$ligne)->getAlignment()->setWrapText(true);
				$sheet->getStyle('F'.$ligne)->getAlignment()->setWrapText(true);
				// [216] - Corrections 
				$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode($rowAction['Lettre']));
				$sheet->setCellValueByColumnAndRow(1,$ligne,utf8_encode($rowAction['DateCreation']));
				$sheet->setCellValueByColumnAndRow(2,$ligne,utf8_encode($rowAction['Probleme']));
				$sheet->setCellValueByColumnAndRow(3,$ligne,utf8_encode($rowAction['Action']));
				$sheet->setCellValueByColumnAndRow(4,$ligne,utf8_encode($rowAction['NomActeur']." ".$rowAction['PrenomActeur']));
				$delais = "";
				if($rowAction['Delais'] > "0001-01-01"){
					$delais = $rowAction['Delais'];
				}
				$sheet->setCellValueByColumnAndRow(5,$ligne,utf8_encode($delais));
				
				$image = "../../Images/NonPrisEnCompte2.gif";
				if ($rowAction['Avancement'] == 0){
					$image = "../../Images/NonPrisEnCompte2.gif";
				}
				elseif ($rowAction['Avancement'] == 1){
					$image = "../../Images/EnCompte2.gif";
				}
				elseif ($rowAction['Avancement'] == 2){
					$image = "../../Images/EnCours2.gif";
				}
				elseif ($rowAction['Avancement'] == 3){
					$image = "../../Images/Solution2.gif";
				}
				elseif ($rowAction['Avancement'] >= 4){
					$image = "../../Images/Cloturee2.gif";
				}
				
				$objDrawing = new PHPExcel_Worksheet_Drawing();
				$objDrawing->setName('avancement');
				$objDrawing->setDescription('PHPExcel avancement');
				$objDrawing->setPath($image);
				$objDrawing->setWidth(45);
				$objDrawing->setHeight(45);
				$objDrawing->setCoordinates('G'.$ligne);
				$objDrawing->setOffsetX(35);
				$objDrawing->setOffsetY(4);
				$objDrawing->setWorksheet($sheet);
				
				$dateSolde = "";
				if($rowAction['DateSolde'] > "0001-01-01"){
					$dateSolde = $rowAction['DateSolde'];
				}
				$sheet->setCellValueByColumnAndRow(7,$ligne,utf8_encode($dateSolde));
				
				$ligne++;
			}
		}
	}
	
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('legende');
	$objDrawing->setDescription('PHPExcel legende');
	$objDrawing->setPath('../../Images/Legende_Avancement.gif');
	// [216] - Corrections
	$objDrawing->setCoordinates('A'.$ligne);
	$objDrawing->setOffsetX(100);
	$objDrawing->setOffsetY(20);
	$objDrawing->setWorksheet($sheet);
	$sheet->getRowDimension($ligne)->setRowHeight(100);
	$ligne2 = $ligne + 10;
}

$ligne--;
$sheet->getStyle('A3:H'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A3:H'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A3:H'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$sheet->getColumnDimension('A')->setWidth(13);
$sheet->getColumnDimension('B')->setWidth(13);
$sheet->getColumnDimension('C')->setWidth(50);
$sheet->getColumnDimension('D')->setWidth(50);
$sheet->getColumnDimension('E')->setWidth(21);
$sheet->getColumnDimension('F')->setWidth(13);
$sheet->getColumnDimension('G')->setWidth(13);
$sheet->getColumnDimension('H')->setWidth(13);

$sheet->getRowDimension('1')->setRowHeight(52);
$sheet->getRowDimension('2')->setRowHeight(8);
$sheet->getRowDimension('3')->setRowHeight(68);

//PIED DE PAGE
$r = chr(13); 
$sheet->getHeaderFooter()->setOddFooter('&L' .'Modifie le 02/07/14 par P.MARTIN' . '&C' .'Reproduction interdite sans autorisation ecrite de AAA' . '&R' . '&RPage &P / &N');

$sheet->getPageSetup()->setFitToPage(true);
$sheet->getPageSetup()->setFitToWidth(1);
$sheet->getPageSetup()->setFitToHeight(0);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3);

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="PA_SQCDPF.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/PA_SQCDPF.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
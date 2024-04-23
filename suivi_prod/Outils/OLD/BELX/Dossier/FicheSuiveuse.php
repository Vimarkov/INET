<?php
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../../ConnexioniSansBody.php';

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('Template_FicheSuiveuse.xlsx');

$Id = $_GET['Id_Dossier'];
$Id_FI=0;
if(isset($_GET['Id_FI'])){$Id_FI=$_GET['Id_FI'];}

$req="SELECT Id, MSN,TypeACP AS Type,SectionACP AS MCA,Priorite,Reference,NumOrigine,Titre,TAI_RestantACP AS TAI_Restant,Elec,Systeme,Structure,Oxygene,";
$req.="Hydraulique,Fuel,Metal,Origine,CaecACP AS Caec, ";
$req.="(SELECT sp_olwzonedetravail.Id_CritereZone FROM sp_olwzonedetravail WHERE sp_olwzonedetravail.Id=sp_olwdossier.Id_ZoneDeTravail) AS CritereZone, ";
$req.="(SELECT sp_olwurgence.Libelle FROM sp_olwurgence WHERE sp_olwurgence.Id=sp_olwdossier.Id_Urgence) AS Urgence, ";
$req.="(SELECT new_rh_etatcivil.Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwdossier.Id_Personne) AS Nom, ";
$req.="(SELECT new_rh_etatcivil.Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwdossier.Id_Personne) AS Prenom, ";
$req.="(SELECT new_rh_etatcivil.TelephoneProFixe FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwdossier.Id_Personne) AS Tel ";
$req.="FROM sp_olwdossier ";
$req.="WHERE Id=".$Id."";
$result=mysqli_query($bdd,$req);
$row=mysqli_fetch_array($result);


if($row['Origine']=="DA"){
	$sheet = $excel->getSheetByName('DA');
	$sheet->setCellValue('Q20',$row['Titre']);
	$sheet->setCellValue('T19',$row['NumOrigine']);
}
else{
	$sheet = $excel->getSheetByName('FOLIO');
	$sheet->setCellValue('Q19',$row['Titre']);
	if($row['Origine']=="NC"){
			$sheet->setCellValue('P10',$row['NumOrigine']);
	}
}
$sheet->setCellValue('P12',$row['Reference']);
$sheet->setCellValue('U9',$row['MSN']);
$sheet->setCellValue('P14',$row['TAI_Restant']);
if($row['Priorite']=="1"){
	$sheet->getStyle('Q15')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'92d050'))));
}
elseif($row['Priorite']=="2"){
	$sheet->getStyle('Q15')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffc000'))));
}
elseif($row['Priorite']=="3"){
	$sheet->getStyle('Q15')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ff0000'))));
}
$sheet->setCellValue('Q15',$row['Urgence']);


$sheet->setCellValue('V33',$row['Nom']." ".$row['Prenom']);
$sheet->setCellValue('Z33',$row['Tel']);

//Liste des ATA
$req="SELECT ATA,SousATA FROM sp_olwdossier_ata WHERE Id_Dossier=".$row['Id'];
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
$ata="";
if ($nbResulta>0){
	while($rowATA=mysqli_fetch_array($result)){
		$ata.=$rowATA['ATA']."_".$rowATA['SousATA']." , ";
	}
}
if(strlen($ata)>0){$ata=substr($ata,0,-2);}
$sheet->setCellValue('Q17',$ata);
$sheet->getStyle('Q17')->getAlignment()->setWrapText(true);

//Compétences
if($row['Elec']==1){$sheet->setCellValue('O33',"Elec \nX");}
else{$sheet->setCellValue('O33',"Elec \n");}
$sheet->getStyle('O33')->getAlignment()->setWrapText(true);
if($row['Systeme']==1){$sheet->setCellValue('P33',"Systeme \nX");}
else{$sheet->setCellValue('P33',"Systeme \n");}
$sheet->getStyle('P33')->getAlignment()->setWrapText(true);
if($row['Structure']==1){$sheet->setCellValue('Q33',"Structure \nX");}
else{$sheet->setCellValue('Q33',"Structure \n");}
$sheet->getStyle('Q33')->getAlignment()->setWrapText(true);
if($row['Oxygene']==1){$sheet->setCellValue('R33',"Oxygene \nX");}
else{$sheet->setCellValue('R33',"Oxygene \n");}
$sheet->getStyle('R33')->getAlignment()->setWrapText(true);
if($row['Hydraulique']==1){$sheet->setCellValue('S33',"Hydraulique \nX");}
else{$sheet->setCellValue('S33',"Fuel \n");}
$sheet->getStyle('S33')->getAlignment()->setWrapText(true);
if($row['Fuel']==1){$sheet->setCellValue('T33',"Fuel \nX");}
else{$sheet->setCellValue('T33',"Fuel \n");}
$sheet->getStyle('T33')->getAlignment()->setWrapText(true);
if($row['Metal']==1){$sheet->setCellValue('U33',"Metal \nX");}
else{$sheet->setCellValue('U33',"Metal \n");}
$sheet->getStyle('U33')->getAlignment()->setWrapText(true);

//Zone de travail
if(substr($row['MCA'],0,6)=="S11/12" || substr($row['MCA'],0,6)=="S13/14"){
	//PA
	if($row['CritereZone'] == 1){$sheet->setCellValue('P30',"X");}
	elseif($row['CritereZone'] == 2){$sheet->setCellValue('Q30',"X");}
	elseif($row['CritereZone'] == 3){$sheet->setCellValue('R30',"X");}
	elseif($row['CritereZone'] == 5){$sheet->setCellValue('S30',"X");}
	elseif($row['CritereZone'] == 6){$sheet->setCellValue('T30',"X");}
}
elseif(substr($row['MCA'],0,6)=="S15/21" || substr($row['MCA'],0,6)=="S16/19"){
	//TC
	if($row['CritereZone'] == 2){$sheet->setCellValue('V30',"X");}
	elseif($row['CritereZone'] == 8){$sheet->setCellValue('W30',"X");}
	elseif($row['CritereZone'] == 3){$sheet->setCellValue('X30',"X");}
	elseif($row['CritereZone'] == 4){$sheet->setCellValue('Y30',"X");}
	elseif($row['CritereZone'] == 7){$sheet->setCellValue('Z30',"X");}
	elseif($row['CritereZone'] == 8){$sheet->setCellValue('AA30',"X");}
}

//Piece à retirer au poste
if($Id_FI>0){$req="SELECT Id,PieceAuPoste,PosteAvionACP,TravailRealise,DeposeRepose FROM sp_olwficheintervention WHERE Id=".$Id_FI." ORDER BY Id DESC";}
else{$req="SELECT Id,PieceAuPoste,PosteAvionACP,TravailRealise,DeposeRepose FROM sp_olwficheintervention WHERE Id_Dossier=".$Id." ORDER BY Id DESC";}
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
if ($nbResulta>0){
	$rowFI=mysqli_fetch_array($result);
	
	$sheet->setCellValue('U12',utf8_encode($rowFI['PosteAvionACP']));
	
	if($rowFI['DeposeRepose']==0){
		if($row['Origine']=="DA"){$sheet->setCellValue('O22',utf8_encode($rowFI['TravailRealise']));$sheet->getStyle('O22')->getAlignment()->setWrapText(true);}
		else{$sheet->setCellValue('O21',utf8_encode($rowFI['TravailRealise']));$sheet->getStyle('O21')->getAlignment()->setWrapText(true);}
	}
	else{
		$sheet->setCellValue('U22',utf8_encode($rowFI['TravailRealise']));$sheet->getStyle('U22')->getAlignment()->setWrapText(true);
	}
		
	
	if($row['Origine']=="DA"){
		if($rowFI['PieceAuPoste']<>""){
			$sheet->setCellValue('Y11','X');
			if($rowFI['PieceAuPoste']=="Station livraison"){$sheet->setCellValue('AA14','X');}
			elseif($rowFI['PieceAuPoste']=="Chariot de DA"){$sheet->setCellValue('AA15','X');}
			elseif($rowFI['PieceAuPoste']=="K943"){$sheet->setCellValue('AA16','X');}
		}
		else{$sheet->setCellValue('AA11','X');}
	}
	else{
		if($rowFI['PieceAuPoste']<>""){
			$sheet->setCellValue('X11','OUI X');
			if($rowFI['PieceAuPoste']=="Station livraison"){$sheet->setCellValue('AA14','X');}
			elseif($rowFI['PieceAuPoste']=="Chariot de DA"){$sheet->setCellValue('AA15','X');}
			elseif($rowFI['PieceAuPoste']=="K943"){$sheet->setCellValue('AA16','X');}
		}
		else{$sheet->setCellValue('Z11','NON X');}
	}
}

//Liste des FI
$req="SELECT Id,NumFI,DateIntervention,Vacation,Id_StatutPROD,Id_StatutQUALITE,Commentaire FROM sp_olwficheintervention WHERE Id_Dossier=".$Id." ORDER BY Id DESC";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
$TempsNecessaire=0;
if ($nbResulta>0){
	$nb=0;
	if($row['Origine']=="DA"){
		$ligne=40;
	}
	else{
		$ligne=43;
	}
	while($rowFI=mysqli_fetch_array($result)){
		$TempsPasse=0;
		$Compagnons="";
		$req="SELECT TempsPasse,";
		$req.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwfi_travaileffectue.Id_Personne) AS Compagnon ";
		$req.="FROM sp_olwfi_travaileffectue ";
		$req.="WHERE Id_FI=".$rowFI['Id'];
		$resultC=mysqli_query($bdd,$req);
		$nbResultaFI=mysqli_num_rows($resultC);
		if ($nbResulta>0){
			while($rowCompagnon=mysqli_fetch_array($resultC)){
				$Compagnons.=$rowCompagnon['Compagnon']." , ";
				$TempsPasse+=$rowCompagnon['TempsPasse'];
			}
		}
		if(strlen($Compagnons)>0){$Compagnons=substr($Compagnons,0,-2);}
		$TempsNecessaire+=$TempsPasse;
		if($nb<9){
			$sheet->setCellValue('O'.$ligne,$rowFI['Commentaire']); //Temporaire
			if($rowFI['DateIntervention']>"0001-01-01"){
				$sheet->setCellValue('R'.$ligne,$rowFI['DateIntervention']);
			}
			if($rowFI['Vacation']=="J"){
				$sheet->setCellValue('T'.$ligne,"Jour");
			}
			elseif($rowFI['Vacation']=="S"){
				$sheet->setCellValue('T'.$ligne,"Soir");
			}
			elseif($rowFI['Vacation']=="N"){
				$sheet->setCellValue('T'.$ligne,"Nuit");
			}
			elseif($rowFI['Vacation']=="VSD"){
				$sheet->setCellValue('T'.$ligne,"VSD");
			}
			if($rowFI['Id_StatutQUALITE']<>""){
				$sheet->setCellValue('U'.$ligne,$rowFI['Id_StatutQUALITE']);
			}
			else{
				$sheet->setCellValue('U'.$ligne,$rowFI['Id_StatutPROD']);
			}
			$sheet->setCellValue('W'.$ligne,utf8_encode($Compagnons));
			$sheet->getStyle('W'.$ligne)->getAlignment()->setWrapText(true);
			$sheet->setCellValue('AA'.$ligne,$TempsPasse);
			$nb++;
			$ligne=$ligne+2;
		}
	}
}
if($row['Origine']=="DA"){
	$sheet->setCellValue('R37',$TempsNecessaire." heures");
}
else{
	$sheet->setCellValue('R40',$TempsNecessaire." heures");
}
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="FicheSuiveuse.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

$chemin = '../../../tmp/FicheSuiveuse.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
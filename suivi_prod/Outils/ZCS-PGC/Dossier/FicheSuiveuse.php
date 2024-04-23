<?php
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../../ConnexioniSansBody.php';

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();


$Id = $_GET['Id_Dossier'];
$Id_FI=0;
if(isset($_GET['Id_FI'])){$Id_FI=$_GET['Id_FI'];}

$req="SELECT Id, MSN,SectionACP AS MCA,Priorite,ControleEquipement,Reference,NumOrigine,Titre,DateCreation,TAI_RestantACP AS TAI_Restant,Mastic,Systeme,Structure,Peinture,
	(SELECT Libelle FROM sp_olwzonedetravail WHERE sp_olwzonedetravail.Id=sp_olwdossier.Id_ZoneDeTravail) AS Zone, ";
$req.="Metal,Id_Personne,Id_ZoneDeTravail,Origine,CaecACP AS Caec,CommentaireZICIA,DateCreationACP,NumSN,ReferenceNC, ";
$req.="(SELECT sp_client.Libelle FROM sp_client WHERE sp_client.Id=sp_olwdossier.Id_Client) AS Client, ";
$req.="(SELECT sp_olwzonedetravail.Id_CritereZone FROM sp_olwzonedetravail WHERE sp_olwzonedetravail.Id=sp_olwdossier.Id_ZoneDeTravail) AS CritereZone, ";
$req.="(SELECT new_rh_etatcivil.Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwdossier.Id_Personne) AS Nom, ";
$req.="(SELECT new_rh_etatcivil.Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwdossier.Id_Personne) AS Prenom, ";
$req.="(SELECT new_rh_etatcivil.TelephoneProFixe FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwdossier.Id_Personne) AS Tel ";
$req.="FROM sp_olwdossier ";
$req.="WHERE Id=".$Id."";
$result=mysqli_query($bdd,$req);
$row=mysqli_fetch_array($result);

if($row['ControleEquipement']==0){
	$excel = $workbook->load('Template_FicheSuiveuse.xlsx');
}
else{
	$excel = $workbook->load('Template_FicheSuiveuse2.xlsx');
}
if($row['Origine']=="DA"){
	$sheet = $excel->getSheetByName('DA');
	$sheet->setCellValue('Q20',$row['Titre']);
	$sheet->setCellValue('T19',$row['NumOrigine']);
}
else{
	$sheet = $excel->getSheetByName('FOLIO');
	$sheet->setCellValue('Q19',$row['Titre']);
}
/*
if($row['Client']=="SABCA"){
	$sheet->setCellValue('O10',utf8_encode("N° SN"));
	$sheet->setCellValue('P10',$row['NumSN']);
}*/
$sheet->setCellValue('X11',$row['Client']);
$sheet->setCellValue('Y13',$row['Zone']);
$sheet->setCellValue('P10',$row['Reference']);
$sheet->setCellValue('P12',$row['ReferenceNC']);
$sheet->setCellValue('U9',$row['MSN']);
$sheet->setCellValue('P14',$row['TAI_Restant']);
if($row['Priorite']=="1"){
	$sheet->setCellValue('Q15',"Low");
	$sheet->getStyle('Q15')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'92d050'))));
}
elseif($row['Priorite']=="2"){
	$sheet->setCellValue('Q15',"Medium");
	$sheet->getStyle('Q15')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ffc000'))));
}
elseif($row['Priorite']=="3"){
	$sheet->setCellValue('Q15',"High");
	$sheet->getStyle('Q15')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'ff0000'))));
}
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
if($row['Mastic']==1){$sheet->setCellValue('O33',"Mastic \nX");}
else{$sheet->setCellValue('O33',"Mastic \n");}
$sheet->getStyle('O33')->getAlignment()->setWrapText(true);
if($row['Systeme']==1){$sheet->setCellValue('P33',"Systeme \nX");}
else{$sheet->setCellValue('P33',"Systeme \n");}
$sheet->getStyle('P33')->getAlignment()->setWrapText(true);
if($row['Structure']==1){$sheet->setCellValue('Q33',"Structure \nX");}
else{$sheet->setCellValue('Q33',"Structure \n");}
$sheet->getStyle('Q33')->getAlignment()->setWrapText(true);
if($row['Peinture']==1){$sheet->setCellValue('R33',"Peinture \nX");}
else{$sheet->setCellValue('R33',"Peinture \n");}
$sheet->getStyle('R33')->getAlignment()->setWrapText(true);
if($row['Metal']==1){$sheet->setCellValue('S33',"Metal \nX");}
else{$sheet->setCellValue('S33',"Metal \n");}
$sheet->getStyle('S33')->getAlignment()->setWrapText(true);

//Zone de travail
if(substr($row['MCA'],0,6)=="S11/12" || substr($row['MCA'],0,6)=="S13/14"){
	//PA
	if($row['CritereZone'] == 1){$sheet->setCellValue('P30',"X");}
	elseif($row['CritereZone'] == 2){$sheet->setCellValue('Q30',"X");}
	elseif($row['CritereZone'] == 3){$sheet->setCellValue('R30',"X");}
	elseif($row['CritereZone'] == 5){$sheet->setCellValue('S30',"X");}
	elseif($row['CritereZone'] == 6){$sheet->setCellValue('T30',"X");}
}
elseif(substr($row['MCA'],0,6)=="S15/21" || substr($row['MCA'],0,6)=="S16/18"){
	//TC
	if($row['CritereZone'] == 2){$sheet->setCellValue('V30',"X");}
	elseif($row['CritereZone'] == 8){$sheet->setCellValue('W30',"X");}
	elseif($row['CritereZone'] == 3){$sheet->setCellValue('X30',"X");}
	elseif($row['CritereZone'] == 4){$sheet->setCellValue('Y30',"X");}
	elseif($row['CritereZone'] == 7){$sheet->setCellValue('Z30',"X");}
	elseif($row['CritereZone'] == 8){$sheet->setCellValue('AA30',"X");}
}

//Piece à retirer au poste
if($Id_FI>0){$req="SELECT Id,PieceAuPoste,PosteAvionACP,TravailRealise,DeposeRepose,Commentaire FROM sp_olwficheintervention WHERE Id=".$Id_FI." ORDER BY Id DESC";}
else{$req="SELECT Id,PieceAuPoste,PosteAvionACP,TravailRealise,DeposeRepose,Commentaire FROM sp_olwficheintervention WHERE Id_Dossier=".$Id." ORDER BY Id DESC";}
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
if ($nbResulta>0){
	$rowFI=mysqli_fetch_array($result);
	
	$sheet->setCellValue('U12',utf8_encode($rowFI['PosteAvionACP']));
	
	if($rowFI['DeposeRepose']==0){
		if($row['Origine']=="DA"){$sheet->setCellValue('O22',utf8_encode(stripslashes($rowFI['TravailRealise']).' '.stripslashes($rowFI['Commentaire'])));$sheet->getStyle('O22')->getAlignment()->setWrapText(true);}
		else{$sheet->setCellValue('O21',utf8_encode(stripslashes($rowFI['TravailRealise']).' '.stripslashes($rowFI['Commentaire'])));$sheet->getStyle('O21')->getAlignment()->setWrapText(true);}
	}
	else{
		$sheet->setCellValue('U22',utf8_encode($rowFI['TravailRealise']));$sheet->getStyle('U22')->getAlignment()->setWrapText(true);
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
			if($TempsPasse>0){
				$sheet->setCellValue('AA'.$ligne,$TempsPasse);
			}
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

// Set Orientation, size and scaling
$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3);
$sheet->getPageSetup()->setFitToPage(true);
$sheet->getPageSetup()->setFitToWidth(1);
$sheet->getPageSetup()->setFitToHeight(1);

if($row['ControleEquipement']==1){
	$sheet = $excel->getSheetByName('Controle');
	$sheet->setCellValue('D5',utf8_encode("Effectivité / MSN: ".$row['MSN']));
	$sheet->setCellValue('A7',utf8_encode("Numéro para/dossier: ".$row['Reference']));
	$sheet->setCellValue('D7',"Client: ".$row['Client']);
	
	$sheet->getPageSetup()->setFitToPage(true);
	$sheet->getPageSetup()->setFitToWidth(1);
	$sheet->getPageSetup()->setFitToHeight(1);
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
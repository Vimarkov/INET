<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");


$req="SELECT Id,Id_ContratInitial,Id_Personne,Id_TypeContrat,Id_AgenceInterim,Id_Metier,SalaireReference,TypeCoeff,CoeffFacturationAgence,SalaireBrut,
	(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS TypeContrat,
	TauxHoraire,DateDebut,DateFin,DateFinPeriodeEssai,Id_TempsTravail,Id_Prestation,Id_Pole,TypeDocument,DateCreation,Id_Createur,
	DateSouplessePositive,DateSouplesseNegative,Remarque,Id_LieuTravail,Id_Client,Motif,Niveau,Echelon,Coeff,
	(SELECT Libelle FROM rh_classificationmetier WHERE Id=Id_ClassificationMetier) AS ClassificationMetier,
	(SELECT Libelle FROM rh_lieutravail WHERE Id=Id_LieuTravail) AS LieuTravail,
	(SELECT Libelle FROM rh_client WHERE rh_client.Id=Id_Client) AS Client,
	(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
	(SELECT NbHeureMois FROM rh_tempstravail WHERE rh_tempstravail.Id=Id_TempsTravail) AS TempsTravail,
	(SELECT Libelle FROM rh_tempstravail WHERE rh_tempstravail.Id=Id_TempsTravail) AS NomTempsTravail,
	(SELECT LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS MetierEN,
	(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation) AS Prestation,
	(SELECT Code_Analytique FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation) AS CodeAnalytique,
	(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=new_competences_prestation.Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation) AS Plateforme,
	(SELECT Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne) AS Nom, 
	(SELECT DateDebut1erContratAAA FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne) AS DateDebut1erContratAAA, 
	(SELECT Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne) AS Prenom,
	(SELECT Nationalite FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne) AS Nationalite,
	(SELECT Date_Naissance FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne) AS Date_Naissance,
	(SELECT Ville_Naissance FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne) AS Ville_Naissance,
	(SELECT Adresse FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne) AS Adresse,
	(SELECT CONCAT(CP,' ',Ville) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne) AS AdresseSuite,
	(SELECT Num_SS FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne) AS Num_SS,
	DateFinPeriodeEssai
	FROM rh_personne_contrat 
	WHERE Id=".$_GET['Id']."";
$result=mysqli_query($bdd,$req);
$rowContrat=mysqli_fetch_array($result);

$req="SELECT Id,Id_ContratInitial,Id_Personne,Id_TypeContrat,Id_AgenceInterim,Id_Metier,SalaireReference,TypeCoeff,CoeffFacturationAgence,SalaireBrut,
	(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS TypeContrat,
	(SELECT Libelle FROM rh_classificationmetier WHERE Id=Id_ClassificationMetier) AS ClassificationMetier,
	TauxHoraire,DateDebut,DateFin,DateFinPeriodeEssai,Id_TempsTravail,Id_Prestation,Id_Pole,TypeDocument,DateCreation,Id_Createur,
	DateSouplessePositive,DateSouplesseNegative,Remarque,Id_LieuTravail,Id_Client,Motif,Niveau,Echelon,Coeff,
	(SELECT Libelle FROM rh_client WHERE rh_client.Id=Id_Client) AS Client,
	(SELECT Libelle FROM rh_lieutravail WHERE Id=Id_LieuTravail) AS LieuTravail,
	(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
	(SELECT NbHeureMois FROM rh_tempstravail WHERE rh_tempstravail.Id=Id_TempsTravail) AS TempsTravail,
	(SELECT LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS MetierEN,
	(SELECT Libelle FROM rh_tempstravail WHERE rh_tempstravail.Id=Id_TempsTravail) AS NomTempsTravail,
	(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation) AS Prestation,
	(SELECT Code_Analytique FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation) AS CodeAnalytique,
	(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=new_competences_prestation.Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation) AS Plateforme,
	(SELECT Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne) AS Nom, 
	(SELECT DateDebut1erContratAAA FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne) AS DateDebut1erContratAAA, 
	(SELECT Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne) AS Prenom
	FROM rh_personne_contrat 
	WHERE Suppr=0 
	AND TypeDocument IN ('Nouveau','Avenant')
	AND Id<>".$_GET['Id']."
	AND Id_Personne=".$rowContrat['Id_Personne']."
	AND Id<".$_GET['Id']."
	ORDER BY DateDebut DESC, Id DESC
	";
$result=mysqli_query($bdd,$req);
$rowContratInitial=mysqli_fetch_array($result);

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
if($rowContrat['Id_ContratInitial']==0){$excel = $workbook->load('Template_Contrat.xlsx');}
else{$excel = $workbook->load('Template_Avenant.xlsx');}
$sheet = $excel->getSheetByName('RH');

$sheet->setCellValue('C6',utf8_encode($rowContrat['Nom']));
$sheet->setCellValue('F6',utf8_encode($rowContrat['Prenom']));

if($rowContrat['Id_ContratInitial']==0){
	if($_SESSION['Langue']=="FR"){$sheet->setCellValue('C9',utf8_encode($rowContrat['Metier']));}
	else{$sheet->setCellValue('C9',utf8_encode($rowContrat['MetierEN']));}
	$sheet->setCellValue('F9',utf8_encode($rowContrat['Plateforme']));

	$sheet->setCellValue('C12',utf8_encode($rowContrat['SalaireBrut']));

	$objDrawingCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingCoche->setName('case');
	$objDrawingCoche->setDescription('PHPExcel case');
	$objDrawingCoche->setPath('../../Images/CaseCoche.png');
	$objDrawingCoche->setWidth(25);
	$objDrawingCoche->setHeight(25);
	$objDrawingCoche->setOffsetX(5);
	$objDrawingCoche->setOffsetY(8);

	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath('../../Images/CaseNonCoche.png');
	$objDrawingNonCoche->setWidth(25);
	$objDrawingNonCoche->setHeight(25);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(8);

	$objDrawingNonCoche2 = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche2->setName('case');
	$objDrawingNonCoche2->setDescription('PHPExcel case');
	$objDrawingNonCoche2->setPath('../../Images/CaseNonCoche.png');
	$objDrawingNonCoche2->setWidth(25);
	$objDrawingNonCoche2->setHeight(25);
	$objDrawingNonCoche2->setOffsetX(5);
	$objDrawingNonCoche2->setOffsetY(8);

	$objDrawingNonCoche3 = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche3->setName('case');
	$objDrawingNonCoche3->setDescription('PHPExcel case');
	$objDrawingNonCoche3->setPath('../../Images/CaseNonCoche.png');
	$objDrawingNonCoche3->setWidth(25);
	$objDrawingNonCoche3->setHeight(25);
	$objDrawingNonCoche3->setOffsetX(5);
	$objDrawingNonCoche3->setOffsetY(8);

	$objDrawingNonCoche4 = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche4->setName('case');
	$objDrawingNonCoche4->setDescription('PHPExcel case');
	$objDrawingNonCoche4->setPath('../../Images/CaseNonCoche.png');
	$objDrawingNonCoche4->setWidth(25);
	$objDrawingNonCoche4->setHeight(25);
	$objDrawingNonCoche4->setOffsetX(5);
	$objDrawingNonCoche4->setOffsetY(8);

	if($rowContrat['TypeContrat']=="CDI"){
		$objDrawingCoche->setCoordinates('F11');
		$objDrawingCoche->setWorksheet($sheet);
		
		$objDrawingNonCoche->setCoordinates('F12');
		$objDrawingNonCoche->setWorksheet($sheet);
		
		$objDrawingNonCoche2->setCoordinates('F13');
		$objDrawingNonCoche2->setWorksheet($sheet);
		
		$objDrawingNonCoche3->setCoordinates('F14');
		$objDrawingNonCoche3->setWorksheet($sheet);
		
		$objDrawingNonCoche4->setCoordinates('F15');
		$objDrawingNonCoche4->setWorksheet($sheet);
	}
	elseif($rowContrat['TypeContrat']=="CDD"){
		$objDrawingNonCoche->setCoordinates('F11');
		$objDrawingNonCoche->setWorksheet($sheet);
		
		$objDrawingCoche->setCoordinates('F12');
		$objDrawingCoche->setWorksheet($sheet);
		
		$objDrawingNonCoche2->setCoordinates('F13');
		$objDrawingNonCoche2->setWorksheet($sheet);
		
		$objDrawingNonCoche3->setCoordinates('F14');
		$objDrawingNonCoche3->setWorksheet($sheet);
		
		$objDrawingNonCoche4->setCoordinates('F15');
		$objDrawingNonCoche4->setWorksheet($sheet);
	}
	elseif($rowContrat['TypeContrat']=="CDIC"){
		$objDrawingNonCoche->setCoordinates('F11');
		$objDrawingNonCoche->setWorksheet($sheet);
		
		$objDrawingNonCoche2->setCoordinates('F12');
		$objDrawingNonCoche2->setWorksheet($sheet);
		
		$objDrawingCoche->setCoordinates('F13');
		$objDrawingCoche->setWorksheet($sheet);
		
		$objDrawingNonCoche3->setCoordinates('F14');
		$objDrawingNonCoche3->setWorksheet($sheet);
		
		$objDrawingNonCoche4->setCoordinates('F15');
		$objDrawingNonCoche4->setWorksheet($sheet);
	}
	elseif($rowContrat['TypeContrat']=="EXPATRE"){
		$objDrawingNonCoche->setCoordinates('F11');
		$objDrawingNonCoche->setWorksheet($sheet);
		
		$objDrawingNonCoche2->setCoordinates('F12');
		$objDrawingNonCoche2->setWorksheet($sheet);
		
		$objDrawingNonCoche3->setCoordinates('F13');
		$objDrawingNonCoche3->setWorksheet($sheet);
		
		$objDrawingCoche->setCoordinates('F14');
		$objDrawingCoche->setWorksheet($sheet);
		
		$objDrawingNonCoche4->setCoordinates('F15');
		$objDrawingNonCoche4->setWorksheet($sheet);
	}
	else{
		$objDrawingNonCoche->setCoordinates('F11');
		$objDrawingNonCoche->setWorksheet($sheet);
		
		$objDrawingNonCoche2->setCoordinates('F12');
		$objDrawingNonCoche2->setWorksheet($sheet);
		
		$objDrawingNonCoche3->setCoordinates('F13');
		$objDrawingNonCoche3->setWorksheet($sheet);
		
		$objDrawingNonCoche4->setCoordinates('F14');
		$objDrawingNonCoche4->setWorksheet($sheet);
		
		$objDrawingCoche->setCoordinates('F15');
		$objDrawingCoche->setWorksheet($sheet);	
		
		$sheet->setCellValue('G15',utf8_encode("Autres : ".$rowContrat['TypeContrat']));
	}
	
	$sheet->setCellValue('C17',utf8_encode($rowContrat['Client']));
	$sheet->setCellValue('C18',utf8_encode(stripslashes($rowContrat['LieuTravail'])."\nPrestation : ".stripslashes($rowContrat['Prestation'])));
	$sheet->getStyle('C18')->getAlignment()->setWrapText(true);
	
	$sheet->setCellValue('C20',utf8_encode(stripslashes($rowContrat['Motif'])));
	$sheet->getStyle('C20')->getAlignment()->setWrapText(true);
	
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath('../../Images/CaseNonCoche.png');
	$objDrawingNonCoche->setWidth(25);
	$objDrawingNonCoche->setHeight(25);
	$objDrawingNonCoche->setOffsetX(-30);
	$objDrawingNonCoche->setOffsetY(8);
	$objDrawingNonCoche->setCoordinates('C21');
	$objDrawingNonCoche->setWorksheet($sheet);

	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath('../../Images/CaseNonCoche.png');
	$objDrawingNonCoche->setWidth(25);
	$objDrawingNonCoche->setHeight(25);
	$objDrawingNonCoche->setOffsetX(55);
	$objDrawingNonCoche->setOffsetY(8);
	$objDrawingNonCoche->setCoordinates('C21');
	$objDrawingNonCoche->setWorksheet($sheet);

	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath('../../Images/CaseNonCoche.png');
	$objDrawingNonCoche->setWidth(25);
	$objDrawingNonCoche->setHeight(25);
	$objDrawingNonCoche->setOffsetX(-50);
	$objDrawingNonCoche->setOffsetY(8);
	$objDrawingNonCoche->setCoordinates('F22');
	$objDrawingNonCoche->setWorksheet($sheet);

	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath('../../Images/CaseNonCoche.png');
	$objDrawingNonCoche->setWidth(25);
	$objDrawingNonCoche->setHeight(25);
	$objDrawingNonCoche->setOffsetX(25);
	$objDrawingNonCoche->setOffsetY(8);
	$objDrawingNonCoche->setCoordinates('G22');
	$objDrawingNonCoche->setWorksheet($sheet);
	
	$sheet->setCellValue('B26',utf8_encode("Code analytique : ".$rowContrat['CodeAnalytique']));
	$sheet->setCellValue('B27',utf8_encode("Date d’embauche : ".AfficheDateJJ_MM_AAAA($rowContrat['DateDebut'])));
	$sheet->setCellValue('B28',utf8_encode("Nationalité : ".$rowContrat['Nationalite']));
	$sheet->setCellValue('E28',utf8_encode("Adresse : ".$rowContrat['Adresse']));
	$sheet->setCellValue('B29',utf8_encode("Date et lieu de naissance : ".AfficheDateJJ_MM_AAAA($rowContrat['Date_Naissance'])." ".$rowContrat['Ville_Naissance']));
	$sheet->setCellValue('E29',utf8_encode($rowContrat['AdresseSuite']));
	$sheet->setCellValue('B30',utf8_encode("Numéro de sécurité sociale : ".$rowContrat['Num_SS']));
	$sheet->setCellValue('B31',utf8_encode("Période d’essai : ".AfficheDateJJ_MM_AAAA($rowContrat['DateFinPeriodeEssai'])));


	$sheet->setCellValue('B33',utf8_encode("Catégorie socioprofessionnelle : ".$rowContrat['ClassificationMetier']));
	$sheet->setCellValue('B34',utf8_encode("Classification : ".$rowContrat['Niveau']." - ".$rowContrat['Echelon']." - ".$rowContrat['Coeff']));
	if($rowContrat['TempsTravail']<>0){
		$sheet->setCellValue('B35',utf8_encode("Temps de travail : ".$rowContrat['TempsTravail']."h"));
	}
	else{
		$sheet->setCellValue('B35',utf8_encode("Temps de travail : ".$rowContrat['NomTempsTravail']));
	}

	if($rowContrat['TypeContrat']=="CDD"){
		$sheet->setCellValue('B43',utf8_encode("Motif de recours au CDD : ".AfficheDateJJ_MM_AAAA($rowContrat['DateFin'])));
	}


}
else{
	if($_SESSION['Langue']=="FR"){$sheet->setCellValue('C9',utf8_encode($rowContratInitial['Metier']));}
	else{$sheet->setCellValue('C9',utf8_encode($rowContratInitial['MetierEN']));}
	$sheet->setCellValue('F9',utf8_encode($rowContratInitial['Plateforme']));

	$sheet->setCellValue('C12',utf8_encode($rowContratInitial['SalaireBrut']));

	$objDrawingCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingCoche->setName('case');
	$objDrawingCoche->setDescription('PHPExcel case');
	$objDrawingCoche->setPath('../../Images/CaseCoche.png');
	$objDrawingCoche->setWidth(25);
	$objDrawingCoche->setHeight(25);
	$objDrawingCoche->setOffsetX(5);
	$objDrawingCoche->setOffsetY(8);

	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath('../../Images/CaseNonCoche.png');
	$objDrawingNonCoche->setWidth(25);
	$objDrawingNonCoche->setHeight(25);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(8);

	$objDrawingNonCoche2 = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche2->setName('case');
	$objDrawingNonCoche2->setDescription('PHPExcel case');
	$objDrawingNonCoche2->setPath('../../Images/CaseNonCoche.png');
	$objDrawingNonCoche2->setWidth(25);
	$objDrawingNonCoche2->setHeight(25);
	$objDrawingNonCoche2->setOffsetX(5);
	$objDrawingNonCoche2->setOffsetY(8);

	$objDrawingNonCoche3 = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche3->setName('case');
	$objDrawingNonCoche3->setDescription('PHPExcel case');
	$objDrawingNonCoche3->setPath('../../Images/CaseNonCoche.png');
	$objDrawingNonCoche3->setWidth(25);
	$objDrawingNonCoche3->setHeight(25);
	$objDrawingNonCoche3->setOffsetX(5);
	$objDrawingNonCoche3->setOffsetY(8);

	$objDrawingNonCoche4 = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche4->setName('case');
	$objDrawingNonCoche4->setDescription('PHPExcel case');
	$objDrawingNonCoche4->setPath('../../Images/CaseNonCoche.png');
	$objDrawingNonCoche4->setWidth(25);
	$objDrawingNonCoche4->setHeight(25);
	$objDrawingNonCoche4->setOffsetX(5);
	$objDrawingNonCoche4->setOffsetY(8);

	if($rowContrat['TypeContrat']=="CDI"){
		$objDrawingCoche->setCoordinates('F10');
		$objDrawingCoche->setWorksheet($sheet);
		
		$objDrawingNonCoche->setCoordinates('F11');
		$objDrawingNonCoche->setWorksheet($sheet);
		
		$objDrawingNonCoche2->setCoordinates('F12');
		$objDrawingNonCoche2->setWorksheet($sheet);
		
		$objDrawingNonCoche3->setCoordinates('F13');
		$objDrawingNonCoche3->setWorksheet($sheet);
		
		$objDrawingNonCoche4->setCoordinates('F14');
		$objDrawingNonCoche4->setWorksheet($sheet);
	}
	elseif($rowContrat['TypeContrat']=="CDD"){
		$objDrawingNonCoche->setCoordinates('F10');
		$objDrawingNonCoche->setWorksheet($sheet);
		
		$objDrawingCoche->setCoordinates('F11');
		$objDrawingCoche->setWorksheet($sheet);
		
		$objDrawingNonCoche2->setCoordinates('F12');
		$objDrawingNonCoche2->setWorksheet($sheet);
		
		$objDrawingNonCoche3->setCoordinates('F13');
		$objDrawingNonCoche3->setWorksheet($sheet);
		
		$objDrawingNonCoche4->setCoordinates('F14');
		$objDrawingNonCoche4->setWorksheet($sheet);
	}
	elseif($rowContrat['TypeContrat']=="CDIC"){
		$objDrawingNonCoche->setCoordinates('F10');
		$objDrawingNonCoche->setWorksheet($sheet);
		
		$objDrawingNonCoche2->setCoordinates('F11');
		$objDrawingNonCoche2->setWorksheet($sheet);
		
		$objDrawingCoche->setCoordinates('F12');
		$objDrawingCoche->setWorksheet($sheet);
		
		$objDrawingNonCoche3->setCoordinates('F13');
		$objDrawingNonCoche3->setWorksheet($sheet);
		
		$objDrawingNonCoche4->setCoordinates('F14');
		$objDrawingNonCoche4->setWorksheet($sheet);
	}
	elseif($rowContrat['TypeContrat']=="EXPATRE"){
		$objDrawingNonCoche->setCoordinates('F10');
		$objDrawingNonCoche->setWorksheet($sheet);
		
		$objDrawingNonCoche2->setCoordinates('F11');
		$objDrawingNonCoche2->setWorksheet($sheet);
		
		$objDrawingNonCoche3->setCoordinates('F12');
		$objDrawingNonCoche3->setWorksheet($sheet);
		
		$objDrawingCoche->setCoordinates('F13');
		$objDrawingCoche->setWorksheet($sheet);
		
		$objDrawingNonCoche4->setCoordinates('F14');
		$objDrawingNonCoche4->setWorksheet($sheet);
	}
	else{
		$objDrawingNonCoche->setCoordinates('F10');
		$objDrawingNonCoche->setWorksheet($sheet);
		
		$objDrawingNonCoche2->setCoordinates('F11');
		$objDrawingNonCoche2->setWorksheet($sheet);
		
		$objDrawingNonCoche3->setCoordinates('F12');
		$objDrawingNonCoche3->setWorksheet($sheet);
		
		$objDrawingNonCoche4->setCoordinates('F13');
		$objDrawingNonCoche4->setWorksheet($sheet);
		
		$objDrawingCoche->setCoordinates('F14');
		$objDrawingCoche->setWorksheet($sheet);	
		
		$sheet->setCellValue('G14',utf8_encode("Autres : ".$rowContrat['TypeContrat']));
	}
	
	$sheet->setCellValue('D17',utf8_encode($rowContratInitial['Niveau']." - ".$rowContratInitial['Echelon']));
	$sheet->setCellValue('D18',utf8_encode($rowContratInitial['Coeff']));
	$sheet->setCellValue('D19',utf8_encode($rowContratInitial['SalaireReference']));
	
	$sheet->setCellValue('C20',utf8_encode($rowContrat['Client']));
	$sheet->setCellValue('C21',utf8_encode(stripslashes($rowContrat['LieuTravail'])."\nPrestation : ".stripslashes($rowContrat['Prestation'])));
	$sheet->getStyle('C21')->getAlignment()->setWrapText(true);
	$sheet->setCellValue('C23',utf8_encode(stripslashes($rowContrat['Motif'])));
	$sheet->getStyle('C23')->getAlignment()->setWrapText(true);
	
	$sheet->setCellValue('B27',utf8_encode("Code analytique : ".$rowContrat['CodeAnalytique']));
	$sheet->setCellValue('B28',utf8_encode("Date effet modification : ".AfficheDateJJ_MM_AAAA($rowContrat['DateDebut'])));
	
	if($rowContrat['Metier']<>$rowContratInitial['Metier']){
		$sheet->setCellValue('B31',utf8_encode("     Changement de poste : ".$rowContrat['Metier']));
		
		$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
		$objDrawingNonCoche->setName('case');
		$objDrawingNonCoche->setDescription('PHPExcel case');
		$objDrawingNonCoche->setPath('../../Images/CaseCoche.png');
		$objDrawingNonCoche->setWidth(25);
		$objDrawingNonCoche->setHeight(25);
		$objDrawingNonCoche->setOffsetX(5);
		$objDrawingNonCoche->setOffsetY(8);
		
		$objDrawingNonCoche->setCoordinates('B31');
		$objDrawingNonCoche->setWorksheet($sheet);
	
	}
	else{
		$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
		$objDrawingNonCoche->setName('case');
		$objDrawingNonCoche->setDescription('PHPExcel case');
		$objDrawingNonCoche->setPath('../../Images/CaseNonCoche.png');
		$objDrawingNonCoche->setWidth(25);
		$objDrawingNonCoche->setHeight(25);
		$objDrawingNonCoche->setOffsetX(5);
		$objDrawingNonCoche->setOffsetY(8);
		
		$objDrawingNonCoche->setCoordinates('B31');
		$objDrawingNonCoche->setWorksheet($sheet);
	}
	
	if($rowContrat['ClassificationMetier']<>$rowContratInitial['ClassificationMetier']){
		$sheet->setCellValue('B32',utf8_encode("     Changement de catégorie socioprofessionnelle : ".$rowContrat['ClassificationMetier']));
		
		$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
		$objDrawingNonCoche->setName('case');
		$objDrawingNonCoche->setDescription('PHPExcel case');
		$objDrawingNonCoche->setPath('../../Images/CaseCoche.png');
		$objDrawingNonCoche->setWidth(25);
		$objDrawingNonCoche->setHeight(25);
		$objDrawingNonCoche->setOffsetX(5);
		$objDrawingNonCoche->setOffsetY(8);
		
		$objDrawingNonCoche->setCoordinates('B32');
		$objDrawingNonCoche->setWorksheet($sheet);
	
	}
	else{
		$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
		$objDrawingNonCoche->setName('case');
		$objDrawingNonCoche->setDescription('PHPExcel case');
		$objDrawingNonCoche->setPath('../../Images/CaseNonCoche.png');
		$objDrawingNonCoche->setWidth(25);
		$objDrawingNonCoche->setHeight(25);
		$objDrawingNonCoche->setOffsetX(5);
		$objDrawingNonCoche->setOffsetY(8);
		
		$objDrawingNonCoche->setCoordinates('B32');
		$objDrawingNonCoche->setWorksheet($sheet);
	}
	
	if($rowContrat['Id_TypeContrat']<>$rowContratInitial['Id_TypeContrat']){
		$sheet->setCellValue('B33',utf8_encode("     Changement de type de contrat de travail : ".$rowContrat['TypeContrat']));
		
		$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
		$objDrawingNonCoche->setName('case');
		$objDrawingNonCoche->setDescription('PHPExcel case');
		$objDrawingNonCoche->setPath('../../Images/CaseCoche.png');
		$objDrawingNonCoche->setWidth(25);
		$objDrawingNonCoche->setHeight(25);
		$objDrawingNonCoche->setOffsetX(5);
		$objDrawingNonCoche->setOffsetY(8);
		
		$objDrawingNonCoche->setCoordinates('B33');
		$objDrawingNonCoche->setWorksheet($sheet);
	
	}
	else{
		$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
		$objDrawingNonCoche->setName('case');
		$objDrawingNonCoche->setDescription('PHPExcel case');
		$objDrawingNonCoche->setPath('../../Images/CaseNonCoche.png');
		$objDrawingNonCoche->setWidth(25);
		$objDrawingNonCoche->setHeight(25);
		$objDrawingNonCoche->setOffsetX(5);
		$objDrawingNonCoche->setOffsetY(8);
		
		$objDrawingNonCoche->setCoordinates('B33');
		$objDrawingNonCoche->setWorksheet($sheet);
	}
	
	if($rowContrat['Niveau']<>$rowContratInitial['Niveau'] || $rowContrat['Echelon']<>$rowContratInitial['Echelon'] || $rowContrat['Coeff']<>$rowContratInitial['Coeff']){
		$sheet->setCellValue('B34',utf8_encode("     Changement de classification : coef    ".$rowContrat['Coeff']."    niveau ".$rowContrat['Niveau']."     indice  ".$rowContrat['Echelon']));
		
		$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
		$objDrawingNonCoche->setName('case');
		$objDrawingNonCoche->setDescription('PHPExcel case');
		$objDrawingNonCoche->setPath('../../Images/CaseCoche.png');
		$objDrawingNonCoche->setWidth(25);
		$objDrawingNonCoche->setHeight(25);
		$objDrawingNonCoche->setOffsetX(5);
		$objDrawingNonCoche->setOffsetY(8);
		
		$objDrawingNonCoche->setCoordinates('B34');
		$objDrawingNonCoche->setWorksheet($sheet);
	
	}
	else{
		$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
		$objDrawingNonCoche->setName('case');
		$objDrawingNonCoche->setDescription('PHPExcel case');
		$objDrawingNonCoche->setPath('../../Images/CaseNonCoche.png');
		$objDrawingNonCoche->setWidth(25);
		$objDrawingNonCoche->setHeight(25);
		$objDrawingNonCoche->setOffsetX(5);
		$objDrawingNonCoche->setOffsetY(8);
		
		$objDrawingNonCoche->setCoordinates('B34');
		$objDrawingNonCoche->setWorksheet($sheet);
	}
	
	if($rowContrat['SalaireBrut']<>$rowContratInitial['SalaireBrut']){
		$sheet->setCellValue('B35',utf8_encode("     Revalorisation salariale : ".$rowContrat['SalaireBrut']));
		
		$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
		$objDrawingNonCoche->setName('case');
		$objDrawingNonCoche->setDescription('PHPExcel case');
		$objDrawingNonCoche->setPath('../../Images/CaseCoche.png');
		$objDrawingNonCoche->setWidth(25);
		$objDrawingNonCoche->setHeight(25);
		$objDrawingNonCoche->setOffsetX(5);
		$objDrawingNonCoche->setOffsetY(8);
		
		$objDrawingNonCoche->setCoordinates('B35');
		$objDrawingNonCoche->setWorksheet($sheet);
	
	}
	else{
		$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
		$objDrawingNonCoche->setName('case');
		$objDrawingNonCoche->setDescription('PHPExcel case');
		$objDrawingNonCoche->setPath('../../Images/CaseNonCoche.png');
		$objDrawingNonCoche->setWidth(25);
		$objDrawingNonCoche->setHeight(25);
		$objDrawingNonCoche->setOffsetX(5);
		$objDrawingNonCoche->setOffsetY(8);
		
		$objDrawingNonCoche->setCoordinates('B35');
		$objDrawingNonCoche->setWorksheet($sheet);
	}
	
	if($rowContrat['Plateforme']<>$rowContratInitial['Plateforme']){
		$sheet->setCellValue('B36',utf8_encode("     Mutation (indique nouvelle UER) : ".$rowContrat['Plateforme']));
		
		$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
		$objDrawingNonCoche->setName('case');
		$objDrawingNonCoche->setDescription('PHPExcel case');
		$objDrawingNonCoche->setPath('../../Images/CaseCoche.png');
		$objDrawingNonCoche->setWidth(25);
		$objDrawingNonCoche->setHeight(25);
		$objDrawingNonCoche->setOffsetX(5);
		$objDrawingNonCoche->setOffsetY(8);
		
		$objDrawingNonCoche->setCoordinates('B36');
		$objDrawingNonCoche->setWorksheet($sheet);
	
	}
	else{
		$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
		$objDrawingNonCoche->setName('case');
		$objDrawingNonCoche->setDescription('PHPExcel case');
		$objDrawingNonCoche->setPath('../../Images/CaseNonCoche.png');
		$objDrawingNonCoche->setWidth(25);
		$objDrawingNonCoche->setHeight(25);
		$objDrawingNonCoche->setOffsetX(5);
		$objDrawingNonCoche->setOffsetY(8);
		
		$objDrawingNonCoche->setCoordinates('B36');
		$objDrawingNonCoche->setWorksheet($sheet);
	}
	
	if($rowContrat['TempsTravail']<>$rowContratInitial['TempsTravail']){
		$sheet->setCellValue('B37',utf8_encode("     Changement temps de travail (indiquer motif, durée hebdo et horaires) : ".$rowContrat['Plateforme']));
		
		$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
		$objDrawingNonCoche->setName('case');
		$objDrawingNonCoche->setDescription('PHPExcel case');
		$objDrawingNonCoche->setPath('../../Images/CaseCoche.png');
		$objDrawingNonCoche->setWidth(25);
		$objDrawingNonCoche->setHeight(25);
		$objDrawingNonCoche->setOffsetX(5);
		$objDrawingNonCoche->setOffsetY(8);
		
		$objDrawingNonCoche->setCoordinates('B37');
		$objDrawingNonCoche->setWorksheet($sheet);
	
	}
	else{
		$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
		$objDrawingNonCoche->setName('case');
		$objDrawingNonCoche->setDescription('PHPExcel case');
		$objDrawingNonCoche->setPath('../../Images/CaseNonCoche.png');
		$objDrawingNonCoche->setWidth(25);
		$objDrawingNonCoche->setHeight(25);
		$objDrawingNonCoche->setOffsetX(5);
		$objDrawingNonCoche->setOffsetY(8);
		
		$objDrawingNonCoche->setCoordinates('B37');
		$objDrawingNonCoche->setWorksheet($sheet);
	}
}

$Titre="";
if($_SESSION['Langue']=="FR"){
	$Titre="DemandeContrat";
}
else{
	$Titre="RequestContract";
}
$Titre.="_".$rowContrat['Nom']."_".$rowContrat['Prenom']."_".$rowContrat['DateCreation'];
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="'.$Titre.'.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

$writer->save('php://output');
/*
$chemin = '../../tmp/Contrat.xlsx';
$writer->save($chemin);
readfile($chemin);*/
?>
<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

//Ligne En-tete
if($_SESSION['Langue']=="FR"){
	$sheet->setCellValue('A1',utf8_encode('Personne'));
	$sheet->setCellValue('B1',utf8_encode('Titre'));
	$sheet->setCellValue('C1',utf8_encode('Métier'));
	$sheet->setCellValue('D1',utf8_encode('Type de document'));
	$sheet->setCellValue('E1',utf8_encode('Type de contrat'));
	$sheet->setCellValue('F1',utf8_encode('Agence d\'interim'));
	$sheet->setCellValue('G1',utf8_encode('Date de début'));
	$sheet->setCellValue('H1',utf8_encode('Date de fin'));
	$sheet->setCellValue('I1',utf8_encode('Coeff'));
	$sheet->setCellValue('J1',utf8_encode('Type de coeff'));
	$sheet->setCellValue('K1',utf8_encode('Salaire'));
	$sheet->setCellValue('L1',utf8_encode('Taux horaire'));
	$sheet->setCellValue('M1',utf8_encode('Temps de travail'));
	$sheet->setCellValue('N1',utf8_encode('Etat'));
	$sheet->setCellValue('O1',utf8_encode("Unité d'exploitation"));

}
else{
	$sheet->setCellValue('A1',utf8_encode('Person'));
	$sheet->setCellValue('B1',utf8_encode('Title'));
	$sheet->setCellValue('C1',utf8_encode('Job'));
	$sheet->setCellValue('D1',utf8_encode('Contract type'));
	$sheet->setCellValue('E1',utf8_encode('Contract type'));
	$sheet->setCellValue('F1',utf8_encode('Acting Agency'));
	$sheet->setCellValue('G1',utf8_encode('Start date'));
	$sheet->setCellValue('H1',utf8_encode('End date'));
	$sheet->setCellValue('I1',utf8_encode('Coeff'));
	$sheet->setCellValue('J1',utf8_encode('Coeff type'));
	$sheet->setCellValue('K1',utf8_encode('Salary'));
	$sheet->setCellValue('L1',utf8_encode('Hourly rate'));
	$sheet->setCellValue('M1',utf8_encode('Work time)'));
	$sheet->setCellValue('N1',utf8_encode('State'));
	$sheet->setCellValue('O1',utf8_encode("Operating unit"));
}
$sheet->getStyle('A1:O1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);

if($_SESSION["Langue"]=="FR"){
$requete2="
	SELECT *
	FROM
	(
		SELECT *
		FROM 
			(SELECT Id,Id_Personne,Id_Metier,Id_TypeContrat,Id_TempsTravail,Titre,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
			(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
			(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
			(SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS EstInterim,
			Coeff,DateDebut,DateFin,TypeDocument,TypeCoeff,SalaireBrut,TauxHoraire,
			(SELECT Libelle FROM rh_agenceinterim WHERE rh_agenceinterim.Id=Id_AgenceInterim) AS AgenceInterim,
			(SELECT Libelle FROM rh_tempstravail WHERE rh_tempstravail.Id=Id_TempsTravail) AS TempsTravail,
			(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Id_Plateforme,
			(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Plateforme,
			IF(DateSignatureSiege=0,1,
				IF(DateSignatureSalarie=0,2,
					IF(DateSignatureSalarie>'0001-01-01' AND DateRetourSigneAuSiege=0,3,
						IF(DateRetourSigneAuSiege>'0001-01-01',4,
						0
						)
					)
				)
			) AS Etat,(@row_number:=@row_number + 1) AS rnk
			FROM rh_personne_contrat
			WHERE Suppr=0
			AND DateDebut<='".date('Y-m-d')."'
			AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
			AND TypeDocument IN ('Nouveau','Avenant')
			ORDER BY Id_Personne, DateDebut DESC, Id DESC) AS table_contrat 
		GROUP BY Id_Personne
	) AS table_contrat2
	WHERE Personne<>'' 
	";
}
else{
$requete2="
	SELECT *
	FROM
	(
		SELECT *
		FROM 
			(SELECT Id,Id_Personne,Id_Metier,Id_TypeContrat,Id_TempsTravail,Titre,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
			(SELECT LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
			(SELECT LibelleEN FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
			(SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS EstInterim,
			Coeff,DateDebut,DateFin,TypeDocument,TypeCoeff,SalaireBrut,TauxHoraire,
			(SELECT Libelle FROM rh_agenceinterim WHERE rh_agenceinterim.Id=Id_AgenceInterim) AS AgenceInterim,
			(SELECT Libelle FROM rh_tempstravail WHERE rh_tempstravail.Id=Id_TempsTravail) AS TempsTravail,
			(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Id_Plateforme,
			(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Plateforme,
			IF(DateSignatureSiege=0,1,
				IF(DateSignatureSalarie=0,2,
					IF(DateSignatureSalarie>'0001-01-01' AND DateRetourSigneAuSiege=0,3,
						IF(DateRetourSigneAuSiege>'0001-01-01',4,
						0
						)
					)
				)
			) AS Etat,(@row_number:=@row_number + 1) AS rnk
			FROM rh_personne_contrat
			WHERE Suppr=0
			AND DateDebut<='".date('Y-m-d')."'
			AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
			AND TypeDocument IN ('Nouveau','Avenant')
			ORDER BY Id_Personne, DateDebut DESC) AS table_contrat 
		GROUP BY Id_Personne
	) AS table_contrat2
	WHERE Personne<>'' 
	";
}
if($_SESSION['FiltreRHContratEC_Plateforme']<>""){
$requete2.=" AND Id_Plateforme = ".$_SESSION['FiltreRHContratEC_Plateforme']." ";
}
if($_SESSION['FiltreRHContratEC_Personne']<>""){
$requete2.=" AND Personne LIKE \"%".$_SESSION['FiltreRHContratEC_Personne']."%\" ";
}

if($_SESSION['FiltreRHContratEC_Metier']<>"0"){
$requete2.=" AND Id_Metier = ".$_SESSION['FiltreRHContratEC_Metier']." ";
}
if($_SESSION['FiltreRHContratEC_TypeContrat']<>"0"){
$requete2.=" AND Id_TypeContrat = ".$_SESSION['FiltreRHContratEC_TypeContrat']." ";
}
if($_SESSION['FiltreRHContratEC_Coeff']<>""){
$requete2.=" AND Coeff ".$_SESSION['FiltreRHContratEC_SigneCoeff']." ".$_SESSION['FiltreRHContratEC_Coeff']." ";
}
if($_SESSION['FiltreRHContratEC_DateDebut']<>""){
$requete2.=" AND DateDebut ".$_SESSION['FiltreRHContratEC_SigneDateDebut']." '".TrsfDate_($_SESSION['FiltreRHContratEC_DateDebut'])."' ";
}
if($_SESSION['FiltreRHContratEC_DateFin']<>""){
if($_SESSION['FiltreRHContratEC_SigneDateFin']=="<"){
	$requete2.=" AND DateFin ".$_SESSION['FiltreRHContratEC_SigneDateFin']." '".TrsfDate_($_SESSION['FiltreRHContratEC_DateFin'])."' 
	AND DateFin>'0001-01-01'
	";
}
elseif($_SESSION['FiltreRHContratEC_SigneDateDebut']==">"){
	$requete2.=" AND DateFin ".$_SESSION['FiltreRHContratEC_SigneDateDebut']." '".TrsfDate_($_SESSION['FiltreRHContratEC_DateFin'])."' 
	OR DateFin<='0001-01-01' 
	";
}
elseif($_SESSION['FiltreRHContratEC_SigneDateDebut']=="="){
	$requete2.=" AND DateFin ".$_SESSION['FiltreRHContratEC_SigneDateDebut']." '".TrsfDate_($_SESSION['FiltreRHContratEC_DateFin'])."' 
	";
}
}
if($_SESSION['FiltreRHContratEC_Salaire']<>""){
$requete2.=" AND SalaireBrut ".$_SESSION['FiltreRHContratEC_SigneSalaire']." '".$_SESSION['FiltreRHContratEC_Salaire']."' ";
}
if($_SESSION['FiltreRHContratEC_Etat']<>"0"){
$requete2.=" AND Etat = ".$_SESSION['FiltreRHContratEC_Etat']." ";
}
if($_SESSION['FiltreRHContratEC_TauxHoraire']<>""){
$requete2.=" AND SalaireBrut ".$_SESSION['FiltreRHContratEC_SigneTauxHoraire']." '".$_SESSION['FiltreRHContratEC_TauxHoraire']."' ";
}
if($_SESSION['FiltreRHContratEC_TempsTravail']<>"0" && $_SESSION['FiltreRHContratEC_TempsTravail']<>""){
$requete2.=" AND Id_TempsTravail = ".$_SESSION['FiltreRHContratEC_TempsTravail']." ";
}

$requeteOrder="";
if($_SESSION['TriRHContratEC_General']<>""){
$requeteOrder="ORDER BY ".substr($_SESSION['TriRHContratEC_General'],0,-1);
}
$resultRapport=mysqli_query($bdd,$requete2.$requeteOrder);
$nbRapport=mysqli_num_rows($resultRapport);
if($nbRapport>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($resultRapport)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}
					
		$Etat="";
		$CouleurEtat=$couleur;
		
		$TypeDoc="";
		if($row['TypeDocument']=="Nouveau"){
			if($_SESSION["Langue"]=="FR"){$TypeDoc= "Nouveau";}else{$TypeDoc= "New";}
		}
		elseif($row['TypeDocument']=="Avenant"){
			if($_SESSION["Langue"]=="FR"){$TypeDoc= "Avenant";}
		}
		
		$Etat="";
		if($row['Etat']==1){if($_SESSION["Langue"]=="FR"){$Etat= "Attente signature siège";}else{$Etat= "Waiting signature head office";}}
		elseif($row['Etat']==2){if($_SESSION["Langue"]=="FR"){$Etat= "Signature siège et attente signature salarié";}else{$Etat= "Signature head office and waiting signature employee";}}
		elseif($row['Etat']==3){if($_SESSION["Langue"]=="FR"){$Etat= "Signature salarié OK";}else{$Etat= "Employee Signature OK";}}
		elseif($row['Etat']==4){if($_SESSION["Langue"]=="FR"){$Etat= "Retour signé au siège (clôturé)";}else{$Etat= "Signed return to head office (closed)";}}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Personne']));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Titre'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Metier']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($TypeDoc));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['TypeContrat']));
		$sheet->setCellValue('F'.$ligne,utf8_encode($row['AgenceInterim']));
		$sheet->setCellValue('G'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateDebut'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateFin'])));
		$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($row['Coeff'])));
		if($row['EstInterim']==1){
			$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($row['TypeCoeff'])));
		}
		if($row['SalaireBrut']>0){
			$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($row['SalaireBrut'])));
		}
		if($row['TauxHoraire']>0){
			$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($row['TauxHoraire'])));
		}
		$sheet->setCellValue('M'.$ligne,utf8_encode(stripslashes($row['TempsTravail'])));
		$sheet->setCellValue('N'.$ligne,utf8_encode(stripslashes($Etat)));
		$sheet->setCellValue('O'.$ligne,utf8_encode($row['Plateforme']));
		$sheet->getStyle('A'.$ligne.':O'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export_Contrat.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Export_Contrat.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
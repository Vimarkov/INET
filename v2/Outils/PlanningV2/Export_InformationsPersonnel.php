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

$Menu=$_GET['Menu'];

//Ligne En-tete
if($_SESSION['Langue']=="FR"){
	$sheet->setCellValue('A1',utf8_encode('Personne'));
	$sheet->setCellValue('B1',utf8_encode('Prestation'));
	$sheet->setCellValue('C1',utf8_encode('Pôle'));
	$sheet->setCellValue('D1',utf8_encode('Date de naissance'));
	$sheet->setCellValue('E1',utf8_encode('Contrat'));
	$sheet->setCellValue('F1',utf8_encode('Date de fin'));
	$sheet->setCellValue('G1',utf8_encode('Tel. pro fixe'));
	$sheet->setCellValue('H1',utf8_encode('Tel. pro mobile'));
	$sheet->setCellValue('I1',utf8_encode('Email'));
	$sheet->setCellValue('J1',utf8_encode('N° badge'));
	$sheet->setCellValue('K1',utf8_encode('NG/ST'));
	$sheet->setCellValue('L1',utf8_encode('Login'));
	if($Menu==4){
		$sheet->setCellValue('M1',utf8_encode('Tel. perso'));
		$sheet->setCellValue('N1',utf8_encode('Adresse'));
		$sheet->setCellValue('O1',utf8_encode('CP'));
		$sheet->setCellValue('P1',utf8_encode('Ville'));
		$sheet->setCellValue('Q1',utf8_encode('Email perso'));
		$sheet->setCellValue('R1',utf8_encode('Matricule AAA'));
		$sheet->setCellValue('S1',utf8_encode('Matricule Daher'));
	}
}
else{
	$sheet->setCellValue('A1',utf8_encode('Person'));
	$sheet->setCellValue('B1',utf8_encode('Site'));
	$sheet->setCellValue('C1',utf8_encode('Pole'));
	$sheet->setCellValue('D1',utf8_encode('Birth date'));
	$sheet->setCellValue('E1',utf8_encode('Contract'));
	$sheet->setCellValue('F1',utf8_encode('End date'));
	$sheet->setCellValue('G1',utf8_encode('Fixed business phone'));
	$sheet->setCellValue('H1',utf8_encode('Mobile business phone'));
	$sheet->setCellValue('I1',utf8_encode('Email'));
	$sheet->setCellValue('J1',utf8_encode('Badge number'));
	$sheet->setCellValue('K1',utf8_encode('NG/ST'));
	$sheet->setCellValue('L1',utf8_encode('Login'));
	if($Menu==4){
		$sheet->setCellValue('M1',utf8_encode('Personal phone'));
		$sheet->setCellValue('N1',utf8_encode('Address'));
		$sheet->setCellValue('O1',utf8_encode('Zip code'));
		$sheet->setCellValue('P1',utf8_encode('City'));
		$sheet->setCellValue('Q1',utf8_encode('Personal Email'));
		$sheet->setCellValue('R1',utf8_encode('Matricule AAA'));
		$sheet->setCellValue('S1',utf8_encode('Matricule Daher'));
	}
}				
$sheet->getStyle('A1:S1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(25);



$requete2="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
	rh_personne_mouvement.Id_Pole,TelephoneProFixe,TelephoneProMobil,EmailPro,NumBadge,Matricule,TelephoneMobil,
	Id_Prestation,Id_Pole,Email,Adresse,CP,Ville,MatriculeAAA,MatriculeDaher,
	(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) As Prestation,
	(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) As Pole,
	Date_Naissance,Login ";
$requete="FROM new_rh_etatcivil
	LEFT JOIN rh_personne_mouvement 
	ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
	WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
	AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
	AND rh_personne_mouvement.EtatValidation=1
	AND rh_personne_mouvement.Suppr=0 AND ";
if($Menu==4){
	if(DroitsFormationPlateforme($TableauIdPostesRH)){
		$requete.="(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) IN 
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION['Id_Personne']." 
				AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
			)";
	}
}
elseif($Menu==3){
	$requete.="CONCAT(rh_personne_mouvement.Id_Prestation,'_',rh_personne_mouvement.Id_Pole) IN 
				(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
				FROM new_competences_personne_poste_prestation 
				WHERE Id_Personne=".$_SESSION["Id_Personne"]."
				AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
				)";
}
elseif($Menu==2){
	$requete.="rh_personne_mouvement.Id_Personne=".$_SESSION['Id_Personne']." ";
}

if($_SESSION['FiltreRHInfosPersonnel_Plateforme']<>0){
	$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=rh_personne_mouvement.Id_Prestation)=".$_SESSION['FiltreRHInfosPersonnel_Plateforme']." ";
}
if($_SESSION['FiltreRHInfosPersonnel_Prestation']<>0){
	$requete.=" AND rh_personne_mouvement.Id_Prestation=".$_SESSION['FiltreRHInfosPersonnel_Prestation']." ";
	if($_SESSION['FiltreRHInfosPersonnel_Pole']<>0){
		$requete.=" AND rh_personne_mouvement.Id_Pole=".$_SESSION['FiltreRHInfosPersonnel_Pole']." ";
	}
}

if($Menu<>2){
	if($_SESSION['FiltreRHInfosPersonnel_Personne']<>0){
		$requete.=" AND rh_personne_mouvement.Id_Personne=".$_SESSION['FiltreRHInfosPersonnel_Personne']." ";
	}
}

$requete.="ORDER BY Personne ";
$result=mysqli_query($bdd,$requete2.$requete);

$nbResulta=mysqli_num_rows($result);
if($nbResulta>0){		
	$couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($result)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}
		
		if($_SESSION["Langue"]=="FR"){
			$reqContrat="SELECT *
			FROM
			(
				SELECT *
				FROM 
					(SELECT Id_Personne,DateDebut,DateFin,
					(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,(@row_number:=@row_number + 1) AS rnk
					FROM rh_personne_contrat
					WHERE Suppr=0
					AND DateDebut<='".date('Y-m-d')."'
					AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
					AND TypeDocument IN ('Nouveau','Avenant')
					ORDER BY Id_Personne, DateDebut DESC) AS table_contrat 
				GROUP BY Id_Personne
			) AS table_contrat2
			WHERE Id_Personne=".$row['Id']."
			";
		}
		else{
			$reqContrat="SELECT *
			FROM
			(
				SELECT *
				FROM 
					(SELECT Id_Personne,DateDebut,DateFin,
					(SELECT LibelleEN FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,(@row_number:=@row_number + 1) AS rnk
					FROM rh_personne_contrat
					WHERE Suppr=0
					AND DateDebut<='".date('Y-m-d')."'
					AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
					AND TypeDocument IN ('Nouveau','Avenant')
					ORDER BY Id_Personne, DateDebut DESC) AS table_contrat 
				GROUP BY Id_Personne
			) AS table_contrat2
			WHERE Id_Personne=".$row['Id']."
			";
		}
		$resultContrat=mysqli_query($bdd,$reqContrat);
		$nbResultaContrat=mysqli_num_rows($resultContrat);
		
		$Contrat="";
		$Du="";
		$Au="";
		if($nbResultaContrat>0){
			$rowContat=mysqli_fetch_array($resultContrat);
			$Contrat=$rowContat['TypeContrat'];
			$Du=AfficheDateJJ_MM_AAAA($rowContat['DateDebut']);
			$Au=AfficheDateJJ_MM_AAAA($rowContat['DateFin']);
		}
			
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Personne']));
		$sheet->setCellValue('B'.$ligne,utf8_encode(substr(stripslashes($row['Prestation']),0,7)));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Pole']));
		if($row['Date_Naissance']>'0001-01-01'){
			$date = explode("-",$row['Date_Naissance']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('D'.$ligne,$time);
			$sheet->getStyle('D'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($Contrat)));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($Au)));
		$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($row['TelephoneProFixe'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($row['TelephoneProMobil'])));
		$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($row['EmailPro'])));
		$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($row['NumBadge'])));
		$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($row['Matricule'])));
		$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($row['Login'])));
		if($Menu==4){
			$sheet->setCellValue('M'.$ligne,utf8_encode(stripslashes($row['TelephoneMobil'])));
			$sheet->setCellValue('N'.$ligne,utf8_encode(stripslashes($row['Adresse'])));
			$sheet->setCellValue('O'.$ligne,utf8_encode(stripslashes($row['CP'])));
			$sheet->setCellValue('P'.$ligne,utf8_encode(stripslashes($row['Ville'])));
			$sheet->setCellValue('Q'.$ligne,utf8_encode(stripslashes($row['Email'])));
			$sheet->setCellValue('R'.$ligne,utf8_encode(stripslashes($row['MatriculeAAA'])));
			$sheet->setCellValue('S'.$ligne,utf8_encode(stripslashes($row['MatriculeDaher'])));
		}
		$sheet->getStyle('A'.$ligne.':S'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export_InformationsPersonnel.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Export_InformationsPersonnel.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
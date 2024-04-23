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
	$sheet->setCellValue('D1',utf8_encode('Type de contrat'));
	$sheet->setCellValue('E1',utf8_encode('Date de début'));
	$sheet->setCellValue('F1',utf8_encode('Date de fin'));
	$sheet->setCellValue('G1',utf8_encode('Mission'));
	$sheet->setCellValue('H1',utf8_encode('Indemnité déplacement'));
	$sheet->setCellValue('I1',utf8_encode('Indemnité repas'));
	$sheet->setCellValue('J1',utf8_encode('Indemnité de découcher + petit déjeuner'));
	$sheet->setCellValue('K1',utf8_encode('Indemnité repas (GD)'));
	$sheet->setCellValue('L1',utf8_encode('Frais réels'));
	$sheet->setCellValue('M1',utf8_encode('Prime de responsabilité'));
	$sheet->setCellValue('N1',utf8_encode('Prime d\'équipe'));
	$sheet->setCellValue('O1',utf8_encode('Indemnité outillage'));
	$sheet->setCellValue('P1',utf8_encode('Panier grande nuit'));
	$sheet->setCellValue('Q1',utf8_encode('Majoration VSD'));
	$sheet->setCellValue('R1',utf8_encode('Panier VSD'));
	$sheet->setCellValue('S1',utf8_encode('Moyens de déplacement'));
	$sheet->setCellValue('T1',utf8_encode("Unité d'exploitation"));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Person'));
	$sheet->setCellValue('B1',utf8_encode('Title'));
	$sheet->setCellValue('C1',utf8_encode('Job'));
	$sheet->setCellValue('D1',utf8_encode('Contract type'));
	$sheet->setCellValue('E1',utf8_encode('Start date'));
	$sheet->setCellValue('F1',utf8_encode('End date'));
	$sheet->setCellValue('G1',utf8_encode('Mission'));
	$sheet->setCellValue('H1',utf8_encode('Displacement allowance'));
	$sheet->setCellValue('I1',utf8_encode('Meal allowance'));
	$sheet->setCellValue('J1',utf8_encode('Allowance to leave + breakfast'));
	$sheet->setCellValue('K1',utf8_encode('Meal Allowance (Big Move)'));
	$sheet->setCellValue('L1',utf8_encode('Real costs'));
	$sheet->setCellValue('M1',utf8_encode('Liability premium'));
	$sheet->setCellValue('N1',utf8_encode('Team bonus'));
	$sheet->setCellValue('O1',utf8_encode('Tool allowance'));
	$sheet->setCellValue('P1',utf8_encode('Basket big night'));
	$sheet->setCellValue('Q1',utf8_encode('FSS enhancement'));
	$sheet->setCellValue('R1',utf8_encode('FSS basket'));
	$sheet->setCellValue('S1',utf8_encode('Means of displacement'));
	$sheet->setCellValue('T1',utf8_encode("Operating unit"));
}
$sheet->getStyle('A1:T1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);
$sheet->getColumnDimension('R')->setWidth(50);


if($_SESSION["Langue"]=="FR"){
	$requete2="
		SELECT *
		FROM
		(
			SELECT *
			FROM 
				(SELECT Id,Id_Personne,Id_Metier,Id_TypeContrat,Titre,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
				(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
				(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
				MontantIPD,MontantRepas,MontantIGD,MontantRepasGD,
				FraisReel,PrimeResponsabilite,IndemniteOutillage,MajorationVSD,PrimeEquipe,PanierGrandeNuit,PanierVSD,
				(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Id_Plateforme,
				DateDebut,DateFin,Motif,(@row_number:=@row_number + 1) AS rnk
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND DateDebut<='".date('Y-m-d')."'
				AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
				AND TypeDocument IN ('ODM')
				ORDER BY Id_Personne, DateDebut DESC) AS table_contrat 
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
				(SELECT Id,Id_Personne,Id_Metier,Id_TypeContrat,Titre,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
				(SELECT LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
				(SELECT LibelleEN FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
				MontantIPD,MontantRepas,MontantIGD,MontantRepasGD,
				FraisReel,PrimeResponsabilite,IndemniteOutillage,MajorationVSD,PrimeEquipe,PanierGrandeNuit,PanierVSD,
				(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Id_Plateforme,
				(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Plateforme,
				DateDebut,DateFin,Motif,(@row_number:=@row_number + 1) AS rnk
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND DateDebut<='".date('Y-m-d')."'
				AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
				AND TypeDocument IN ('ODM')
				ORDER BY Id_Personne, DateDebut DESC) AS table_contrat 
			GROUP BY Id_Personne
		) AS table_contrat2
		WHERE Personne<>'' 
		";
	}
	if($_SESSION['FiltreRHODM_Plateforme']<>""){
		$requete2.=" AND Id_Plateforme = ".$_SESSION['FiltreRHODM_Plateforme']." ";
	}
	if($_SESSION['FiltreRHODM_Personne']<>""){
	$requete2.=" AND Personne LIKE \"%".$_SESSION['FiltreRHODM_Personne']."%\" ";
	}

	if($_SESSION['FiltreRHODM_Metier']<>"0"){
	$requete2.=" AND Id_Metier = ".$_SESSION['FiltreRHODM_Metier']." ";
	}
	if($_SESSION['FiltreRHODM_TypeContrat']<>"0"){
	$requete2.=" AND Id_TypeContrat = ".$_SESSION['FiltreRHODM_TypeContrat']." ";
	}
	if($_SESSION['FiltreRHODM_DateDebut']<>""){
	$requete2.=" AND DateDebut ".$_SESSION['FiltreRHODM_SigneDateDebut']." '".TrsfDate_($_SESSION['FiltreRHODM_DateDebut'])."' ";
	}
	if($_SESSION['FiltreRHODM_DateFin']<>""){
	if($_SESSION['FiltreRHODM_SigneDateFin']=="<"){
		$requete2.=" AND DateFin ".$_SESSION['FiltreRHODM_SigneDateFin']." '".TrsfDate_($_SESSION['FiltreRHODM_DateFin'])."' 
		AND DateFin>'0001-01-01'
		";
	}
	elseif($_SESSION['FiltreRHODM_SigneDateDebut']==">"){
		$requete2.=" AND DateFin ".$_SESSION['FiltreRHODM_SigneDateDebut']." '".TrsfDate_($_SESSION['FiltreRHODM_DateFin'])."' 
		OR DateFin<='0001-01-01' 
		";
	}
	elseif($_SESSION['FiltreRHODM_SigneDateDebut']=="="){
		$requete2.=" AND DateFin ".$_SESSION['FiltreRHODM_SigneDateDebut']." '".TrsfDate_($_SESSION['FiltreRHODM_DateFin'])."' 
		";
	}
}

$requeteOrder="";
if($_SESSION['TriRHODM_General']<>""){
	$requeteOrder="ORDER BY ".substr($_SESSION['TriRHODM_General'],0,-1);
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
		
		$contenu="";
		$req="SELECT 
			(SELECT Libelle FROM rh_moyendeplacement WHERE Id=Id_MoyenDeplacement) AS Moyen,
			(SELECT Libelle FROM rh_moyendeplacement WHERE Id=Id_MoyenDeplacement) AS MoyenEN,
			Montant,Periodicite 
			FROM rh_personne_contrat_moyendeplacement 
			WHERE Suppr=0 
			AND Id_Personne_Contrat=".$row['Id'];
		$resultM=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($resultM);
		if ($nbResulta>0){
			$nb=0;
			while($rowM=mysqli_fetch_array($resultM)){
				if($nb>0){$contenu .="\n";}
				if($_SESSION["Langue"]=="FR"){
					$contenu .=$rowM['Moyen']." : ".$rowM['Montant']."euros ->Périodicité ".$rowM['Periodicite'];
				}
				else{
					$contenu .=$rowM['MoyenEN']." : ".$rowM['Montant']."euros ->Periodicity ".$rowM['Periodicite'];
				}
				$nb++;
			}
		}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Personne']));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Titre'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Metier']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['TypeContrat']));
		$sheet->setCellValue('E'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateDebut'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateFin'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($row['Motif'])));
		$sheet->getStyle('G'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['MontantIPD']));
		$sheet->setCellValue('I'.$ligne,utf8_encode($row['MontantRepas']));
		$sheet->setCellValue('J'.$ligne,utf8_encode($row['MontantIGD']));
		$sheet->setCellValue('K'.$ligne,utf8_encode($row['MontantRepasGD']));
		$sheet->setCellValue('L'.$ligne,utf8_encode($row['FraisReel']));
		$sheet->setCellValue('M'.$ligne,utf8_encode($row['PrimeResponsabilite']));
		$sheet->setCellValue('N'.$ligne,utf8_encode($row['PrimeEquipe']));
		$sheet->setCellValue('O'.$ligne,utf8_encode($row['IndemniteOutillage']));
		$sheet->setCellValue('P'.$ligne,utf8_encode($row['PanierGrandeNuit']));
		$sheet->setCellValue('Q'.$ligne,utf8_encode($row['MajorationVSD']));
		$sheet->setCellValue('R'.$ligne,utf8_encode($row['PanierVSD']));
		$sheet->setCellValue('S'.$ligne,utf8_encode(stripslashes($contenu)));
		$sheet->getStyle('S'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('T'.$ligne,utf8_encode($row['Plateforme']));
		$sheet->getStyle('A'.$ligne.':T'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export_ODM.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Export_ODM.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
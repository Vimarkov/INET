<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
						
//Ligne En-tete
if($_SESSION['Langue']=="FR"){
	$sheet->setCellValue('A1',utf8_encode('N° Offre'));
	$sheet->setCellValue('B1',utf8_encode('Ref'));
	$sheet->setCellValue('C1',utf8_encode('Date apparition offre'));
	$sheet->setCellValue('D1',utf8_encode('Demandeur'));
	$sheet->setCellValue('E1',utf8_encode('Nombre de poste'));
	$sheet->setCellValue('F1',utf8_encode('Type de poste'));
	$sheet->setCellValue('G1',utf8_encode('Métier'));
	$sheet->setCellValue('H1',utf8_encode('Statut'));
	$sheet->setCellValue('I1',utf8_encode("Unité d'exploitation"));
	$sheet->setCellValue('J1',utf8_encode('Lieu'));
	$sheet->setCellValue('K1',utf8_encode('Domaine'));
	$sheet->setCellValue('L1',utf8_encode('Date démarrage'));
	$sheet->setCellValue('M1',utf8_encode('Statut du poste'));
	$sheet->setCellValue('N1',utf8_encode('Nombre'));
	$sheet->setCellValue('O1',utf8_encode('Mise à jour annonce'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Offer No.'));
	$sheet->setCellValue('B1',utf8_encode('Ref'));
	$sheet->setCellValue('C1',utf8_encode('Offer appearance date'));
	$sheet->setCellValue('D1',utf8_encode('Requester'));
	$sheet->setCellValue('E1',utf8_encode('Number of post'));
	$sheet->setCellValue('F1',utf8_encode('Position type'));
	$sheet->setCellValue('G1',utf8_encode('Job'));
	$sheet->setCellValue('H1',utf8_encode('Status'));
	$sheet->setCellValue('I1',utf8_encode('Operating unit'));
	$sheet->setCellValue('J1',utf8_encode('Place'));
	$sheet->setCellValue('K1',utf8_encode('Domain'));
	$sheet->setCellValue('L1',utf8_encode('Start date'));
	$sheet->setCellValue('M1',utf8_encode('Job status'));
	$sheet->setCellValue('N1',utf8_encode('Number'));
	$sheet->setCellValue('O1',utf8_encode('Announcement update'));
	
}

$sheet->getStyle('A1:O1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);

if($_SESSION["Langue"]=="FR"){
	$reqSuite="IF(ValidationContratDG<>0,'OUI','NON') AS Etat,
			IF(ValidationContratDG=0,'BESOIN EN ATTENTE VALIDATION DG',
				IF(ValidationContratDG=-1,'BESOIN REFUSÉ PAR LA DG','OFFRE')
			) AS Statut,
			IF(ValidationContratDG=0,'',
					IF(ValidationContratDG=-1,'',
						IF(EtatPoste=0,'Poste ouvert',IF(EtatPoste=1,'Poste pourvu',IF(EtatPoste=2,'Poste non pourvu',IF(EtatPoste=3,'Poste pourvu partiellement',IF(EtatPoste=4,'Demande clôturée','Poste annulé')))))
					)
				) AS Statut2,";
}
else{
	$reqSuite="IF(ValidationContratDG<>0,'YES','NO') AS Etat,
				IF(ValidationContratDG=0,'NEED PENDING CEO VALIDATION',
					IF(ValidationContratDG=-1,'NEED REFUSED BY THE DG','OFFER')
				) AS Statut,
				IF(ValidationContratDG=0,'',
					IF(ValidationContratDG=-1,'',
						IF(EtatPoste=0,'Open post',IF(EtatPoste=1,'Position filled',IF(EtatPoste=2,'Position not filled',IF(EtatPoste=3,'Position partially filled',IF(EtatPoste=4,'Request closed','Position canceled')))))
					)
				) AS Statut2,	";
}

$requete2="SELECT Id,DateDemande,Id_Demandeur,Id_Prestation,Metier,Nombre AS NombrePoste,Lieu,Suppr,
			CONCAT(Metier,'-',
			Lieu,'-',
			Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',DATE_FORMAT(DateValidationDG,'%d%m%y')
			) AS Ref,IF(DateActualisation>'0001-01-01',DateActualisation,DateValidationDG) AS DateRecrutement,DateRecrutementMAJ,EtatPoste,ValidationContratDG,
			".$reqSuite."
			DateBesoin,Duree,PosteDefinitif,IF(DateActualisation>'0001-01-01',DateActualisation,DateValidationDG) AS DateButoire,DateRecrutementMAJ,CategorieProf,
			(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_PersonneAContacter) AS PersonneAContacter,
			(SELECT Libelle FROM talentboost_domaine WHERE Id=Id_Domaine) AS Domaine,
			(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
			Id_Plateforme,
			(SELECT COUNT(Id) FROM talentboost_candidature WHERE Id_Annonce=talentboost_annonce.Id AND talentboost_candidature.Suppr=0) AS Nombre,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,EtatValidation ";
$requete=" FROM talentboost_annonce
		WHERE Suppr=0  AND ValidationContratDG>0  ";
if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
}
else{
	$requete.="  AND (
					  talentboost_annonce.Id_Plateforme IN 
						(
							SELECT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION['Id_Personne']." 
							AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") 
						)
					OR 
						Id_Prestation IN 
						(SELECT Id_Prestation
						FROM new_competences_personne_poste_prestation 
						WHERE Id_Personne=".$_SESSION["Id_Personne"]."
						AND Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.") 
						)
					OR
						(talentboost_annonce.Id_Plateforme IN 
						(
							SELECT Id_Plateforme 
							FROM new_competences_personne_plateforme
							WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						)
						OR OuvertureAutresPlateformes=1
						)
			  ) ";
			  
}
if($_SESSION['FiltreRecrutAnnonce_Plateforme']<>0){
	$requete.=" AND Id_Plateforme=".$_SESSION['FiltreRecrutAnnonce_Plateforme']." ";
}
if($_SESSION['FiltreRecrutAnnonce_Metier']<>""){
	$requete.=" AND talentboost_annonce.Metier LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Metier']."%\" ";
}
if($_SESSION['FiltreRecrutAnnonce_Domaine']<>0){
	$requete.=" AND talentboost_annonce.Id_Domaine = ".$_SESSION['FiltreRecrutAnnonce_Domaine']." ";
}
if($_SESSION['FiltreRecrutAnnonce_Programme']<>"0"){
	$requete.=" AND talentboost_annonce.Programme LIKE \"".$_SESSION['FiltreRecrutAnnonce_Programme']."\" ";
}
if($_SESSION['FiltreRecrutAnnonce_Etat']<>-2){
	$requete.=" AND talentboost_annonce.EtatPoste=".$_SESSION['FiltreRecrutAnnonce_Etat']." ";
}
if($_SESSION['FiltreRecrutAnnonce_DateDemarrage']>"0001-01-01" && $_SESSION['FiltreRecrutAnnonce_DateDemarrage']<>""){
	$requete.=" AND talentboost_annonce.DateBesoin".$_SESSION['FiltreRecrutAnnonce_SigneDateDemarrage']." '".$_SESSION['FiltreRecrutAnnonce_DateDemarrage']."' ";
}
if($_SESSION['FiltreRecrutAnnonce_Information']<>""){
	$requete.=" AND (
		Lieu LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
		OR CategorieProf LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
		OR DescriptifPoste LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
		OR SavoirFaire LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
		OR SavoirEtre LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
		OR Prerequis LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
		OR Diplome LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
		OR Langue LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	) ";
}
if($_SESSION['FiltreRecrutAnnonce_MesCandidatures']=="1"){
	$requete.=" AND (
					SELECT COUNT(talentboost_candidature.Id) 
					FROM talentboost_candidature 
					WHERE talentboost_candidature.Suppr=0
					AND talentboost_candidature.Id_Personne=".$_SESSION['Id_Personne']."
					AND talentboost_candidature.Id_Annonce=talentboost_annonce.Id
					)>0 ";
}

$requeteOrder="";
if($_SESSION['TriRecrutAnnonce_General']<>""){
$requeteOrder="ORDER BY ".substr($_SESSION['TriRecrutAnnonce_General'],0,-1);
}

$resultRapport=mysqli_query($bdd,$requete2.$requete.$requeteOrder);
$nbRapport=mysqli_num_rows($resultRapport);

if($nbRapport>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($resultRapport)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Id']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Ref']));
		$sheet->setCellValue('C'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateRecrutement'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['Demandeur']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['NombrePoste']));
		
		if($row['PosteDefinitif']==1){
			$sheet->setCellValue('F'.$ligne,utf8_encode("Poste définitif"));
		}
		elseif($row['PosteDefinitif']==0){
			$sheet->setCellValue('F'.$ligne,utf8_encode("Mission"));
		}
		elseif($row['PosteDefinitif']==2){
			$sheet->setCellValue('F'.$ligne,utf8_encode("CDD 6 mois"));
		}
		elseif($row['PosteDefinitif']==3){
			$sheet->setCellValue('F'.$ligne,utf8_encode("CDD 2 mois"));
		}
		elseif($row['PosteDefinitif']==4){
			$sheet->setCellValue('F'.$ligne,utf8_encode("CDD"));
		}
		$sheet->setCellValue('G'.$ligne,utf8_encode($row['Metier']));
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['CategorieProf']));
		$sheet->setCellValue('I'.$ligne,utf8_encode($row['Plateforme']));
		$sheet->setCellValue('J'.$ligne,utf8_encode($row['Lieu']));
		$sheet->setCellValue('K'.$ligne,utf8_encode($row['Domaine']));
		$sheet->setCellValue('L'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateBesoin'])));
		$sheet->setCellValue('M'.$ligne,utf8_encode($row['Statut2']));
		$sheet->setCellValue('N'.$ligne,utf8_encode($row['Nombre']));
		$sheet->setCellValue('O'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateRecrutementMAJ'])));
	
		$sheet->getStyle('A'.$ligne.':O'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
				
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
$chemin = '../../tmp/Export.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
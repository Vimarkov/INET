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
	$sheet->setCellValue('F1',utf8_encode('Etat du poste'));
	$sheet->setCellValue('G1',utf8_encode('Type de poste'));
	$sheet->setCellValue('H1',utf8_encode('Demande PSE'));
	$sheet->setCellValue('I1',utf8_encode('Métier'));
	$sheet->setCellValue('J1',utf8_encode('Catégorie d’emploi, à titre indicatif'));
	$sheet->setCellValue('K1',utf8_encode('Statut'));
	$sheet->setCellValue('L1',utf8_encode('Salaire'));
	$sheet->setCellValue('M1',utf8_encode("Unité d'exploitation"));
	$sheet->setCellValue('N1',utf8_encode('Lieu'));
	$sheet->setCellValue('O1',utf8_encode('Domaine'));
	$sheet->setCellValue('P1',utf8_encode('Date démarrage'));
	$sheet->setCellValue('Q1',utf8_encode('Statut du poste'));
	$sheet->setCellValue('R1',utf8_encode('Nombre'));
	$sheet->setCellValue('S1',utf8_encode('Mise à jour annonce'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Offer No.'));
	$sheet->setCellValue('B1',utf8_encode('Ref'));
	$sheet->setCellValue('C1',utf8_encode('Offer appearance date'));
	$sheet->setCellValue('D1',utf8_encode('Requester'));
	$sheet->setCellValue('E1',utf8_encode('Number of post'));
	$sheet->setCellValue('F1',utf8_encode('Post status'));
	$sheet->setCellValue('G1',utf8_encode('Position type'));
	$sheet->setCellValue('H1',utf8_encode('PSE request'));
	$sheet->setCellValue('I1',utf8_encode('Job'));
	$sheet->setCellValue('J1',utf8_encode('Job category, for information only'));
	$sheet->setCellValue('K1',utf8_encode('Status'));
	$sheet->setCellValue('L1',utf8_encode('Salary'));
	$sheet->setCellValue('M1',utf8_encode('Operating unit'));
	$sheet->setCellValue('N1',utf8_encode('Place'));
	$sheet->setCellValue('O1',utf8_encode('Domain'));
	$sheet->setCellValue('P1',utf8_encode('Start date'));
	$sheet->setCellValue('Q1',utf8_encode('Job status'));
	$sheet->setCellValue('R1',utf8_encode('Number'));
	$sheet->setCellValue('S1',utf8_encode('Announcement update'));
	
}

$sheet->getStyle('A1:S1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);

if($_SESSION["Langue"]=="FR"){
$reqSuite="IF(EtatRecrutement<>0,'OFFRE',IF(EtatApprobation=1 && OuvertureAutresPlateformes=0,'OFFRE','BESOIN')) AS Etat, 
	IF(EtatValidation=0,'',
		IF(EtatValidation=-1,'',
			IF(EtatValidation=1 && EtatApprobation=0,'',
				IF(EtatValidation=1 && EtatApprobation=-1,'',
						IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=0,IF(EtatPoste=0,'Poste ouvert',IF(EtatPoste=1,'Poste pourvu',IF(EtatPoste=2,'Poste non pourvu',IF(EtatPoste=3,'Poste pourvu partiellement','Poste annulé')))),
						  IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=1,'',
							IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'',IF(EtatPoste=0,'Poste ouvert',IF(EtatPoste=1,'Poste pourvu',IF(EtatPoste=2,'Poste non pourvu',IF(EtatPoste=3,'Poste pourvu partiellement','Poste annulé')))))
							)
						)
					)
				)
			)
		) AS Statut2,
	IF(EtatValidation=0,'En attente validation',
		IF(EtatValidation=-1,'Refusé',
			IF(EtatValidation=1 && EtatApprobation=0,'En attente approbation',
				IF(EtatValidation=1 && EtatApprobation=-1,'Non approuvé',
						IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=0,'Validé en interne',
						  IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=1,'En attente validation offre',
							IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'Refusé','Passage en annonce')
							)
						)
					)
				)
			)
		) AS Statut, ";
}
else{
$reqSuite="IF(EtatRecrutement<>0,'OFFER',IF(EtatApprobation=1 && OuvertureAutresPlateformes=0,'OFFER','NEED')) AS Etat, 
	IF(EtatValidation=0,'',
		IF(EtatValidation=-1,'',
			IF(EtatValidation=1 && EtatApprobation=0,'',
				IF(EtatValidation=1 && EtatApprobation=-1,'',
						IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=0,IF(EtatPoste=0,'Open post',IF(EtatPoste=1,'Position filled',IF(EtatPoste=2,'Position not filled',IF(EtatPoste=3,'Position partially filled','Position canceled')))),
							IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=1,'',
								IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'',IF(EtatPoste=0,'Open post',IF(EtatPoste=1,'Position filled',IF(EtatPoste=2,'Position not filled',IF(EtatPoste=3,'Position partially filled','Position canceled')))))
							)
						)
					)
				)
			)
		) AS Statut2,
	IF(EtatValidation=0,'Pending validation',
		IF(EtatValidation=-1,'Refuse',
			IF(EtatValidation=1 && EtatApprobation=0,'Pending approval',
				IF(EtatValidation=1 && EtatApprobation=-1,'Not approved',
						IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=0,'Internally validated',
							IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=1,'Pending validation offer',
								IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'Refuse','Switch to announcement')
							)
						)
					)
				)
			)
		) AS Statut, ";
}

$requete2="SELECT Id,DateDemande,Id_Demandeur,Id_Prestation,Metier,Nombre,Lieu,RaisonRefus,Suppr,(SELECT Libelle FROM recrut_categorieprofessionnelle WHERE Id=Id_CategorieProfessionnelle) AS CategorieProfessionnelle,CreationPoste,
CONCAT(Metier,'-',
Lieu,'-',
Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',DATE_FORMAT(DateRecrutement,'%d%m%y')
) AS Ref,RaisonRefus,RaisonRefusRecrutement,RaisonRefusApprobation,CategorieProf,Salaire,
EtatApprobation,EtatRecrutement,DateRecrutement,EtatPoste,Nombre AS NombrePoste,DemandePSE,
".$reqSuite."
DateBesoin,Duree,PosteDefinitif,DateBesoin AS DateButoire,DateRecrutementMAJ,
(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
(SELECT Libelle FROM recrut_domaine WHERE Id=Id_Domaine) AS Domaine,
(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
(SELECT COUNT(Id) FROM recrut_candidature WHERE Id_Annonce=recrut_annonce.Id AND recrut_candidature.Suppr=0) AS Nombre,
(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,EtatValidation ";
$requete=" FROM recrut_annonce
		WHERE Suppr=0  AND EtatRecrutement=1 AND OuvertureAutresPlateformes=1 ";
if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
}
else{
$requete.="  AND (
				  (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=recrut_annonce.Id_Prestation) IN 
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
					((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=recrut_annonce.Id_Prestation) IN 
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
$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=".$_SESSION['FiltreRecrutAnnonce_Plateforme']." ";
}
if($_SESSION['FiltreRecrutAnnonce_Metier']<>0){
$requete.=" AND recrut_annonce.Metier LIKE '%".$_SESSION['FiltreRecrutAnnonce_Metier']."%' ";
}
if($_SESSION['FiltreRecrutAnnonce_Domaine']<>0){
$requete.=" AND recrut_annonce.Id_Domaine=".$_SESSION['FiltreRecrutAnnonce_Domaine']." ";
}
if($_SESSION['FiltreRecrutAnnonce_Programme']<>0){
$requete.=" AND recrut_annonce.Programme=".$_SESSION['FiltreRecrutAnnonce_Programme']." ";
}
if($_SESSION['FiltreRecrutAnnonce_Etat']<>-2){
$requete.=" AND recrut_annonce.EtatPoste=".$_SESSION['FiltreRecrutAnnonce_Etat']." ";
}
if($_SESSION['FiltreRecrutAnnonce_DateDemarrage']>"0001-01-01" && $_SESSION['FiltreRecrutAnnonce_DateDemarrage']<>""){
$requete.=" AND recrut_annonce.DateBesoin".$_SESSION['FiltreRecrutAnnonce_SigneDateDemarrage']." '".$_SESSION['FiltreRecrutAnnonce_DateDemarrage']."' ";
}
if($_SESSION['FiltreRecrutAnnonce_Information']<>""){
$requete.=" AND (
	(SELECT Libelle FROM recrut_typehoraire WHERE Id=Id_TypeHoraire) LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	OR Horaire LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	OR Salaire LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	OR IGD LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	OR Lieu LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	OR CategorieProf LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	OR DescriptifPoste LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	OR SavoirFaire LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	OR SavoirEtre LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	OR Prerequis LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
	OR Langue LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
) ";
}
if($_SESSION['FiltreRecrutAnnonce_MesCandidatures']=="1"){
$requete.=" AND (
				SELECT COUNT(recrut_candidature.Id) 
				FROM recrut_candidature 
				WHERE recrut_candidature.Suppr=0
				AND recrut_candidature.Id_Personne=".$_SESSION['Id_Personne']."
				AND recrut_candidature.Id_Annonce=recrut_annonce.Id
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
		if($row['CreationPoste']==0){
			$sheet->setCellValue('F'.$ligne,utf8_encode("Création du poste"));
		}
		else{
			$sheet->setCellValue('F'.$ligne,utf8_encode("Poste vacant"));
		}
		
		if($row['PosteDefinitif']==1){
			$sheet->setCellValue('G'.$ligne,utf8_encode("Poste définitif"));
		}
		elseif($row['PosteDefinitif']==0){
			$sheet->setCellValue('G'.$ligne,utf8_encode("Mission"));
		}
		elseif($row['PosteDefinitif']==2){
			$sheet->setCellValue('G'.$ligne,utf8_encode("CDD 6 mois"));
		}
		elseif($row['PosteDefinitif']==3){
			$sheet->setCellValue('G'.$ligne,utf8_encode("CDD 2 mois"));
		}
		elseif($row['PosteDefinitif']==4){
			$sheet->setCellValue('G'.$ligne,utf8_encode("CDD"));
		}
		
		if($row['DemandePSE']==0){
			$sheet->setCellValue('H'.$ligne,utf8_encode("Oui"));
		}
		else{
			$sheet->setCellValue('H'.$ligne,utf8_encode("Non"));
		}
		$sheet->setCellValue('I'.$ligne,utf8_encode($row['Metier']));
		$sheet->setCellValue('J'.$ligne,utf8_encode($row['CategorieProfessionnelle']));
		$sheet->setCellValue('K'.$ligne,utf8_encode($row['CategorieProf']));
		$sheet->setCellValue('L'.$ligne,utf8_encode($row['Salaire']));
		$sheet->setCellValue('M'.$ligne,utf8_encode($row['Plateforme']));
		$sheet->setCellValue('N'.$ligne,utf8_encode($row['Lieu']));
		$sheet->setCellValue('O'.$ligne,utf8_encode($row['Domaine']));
		$sheet->setCellValue('P'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateBesoin'])));
		$sheet->setCellValue('Q'.$ligne,utf8_encode($row['Statut2']));
		$sheet->setCellValue('R'.$ligne,utf8_encode($row['Nombre']));
		$sheet->setCellValue('S'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateRecrutementMAJ'])));
	
		$sheet->getStyle('A'.$ligne.':S'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
				
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
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
	$sheet->setCellValue('A1',utf8_encode('N° candidature'));
	$sheet->setCellValue('B1',utf8_encode('Matricule'));
	$sheet->setCellValue('C1',utf8_encode('Nom du salarié'));
	$sheet->setCellValue('D1',utf8_encode('Métier actuel du salarié'));
	$sheet->setCellValue('E1',utf8_encode("Ptf d’origine du salarié"));
	$sheet->setCellValue('F1',utf8_encode('N° Tel du salarié'));
	$sheet->setCellValue('G1',utf8_encode('Adresse mail salarié'));
	$sheet->setCellValue('H1',utf8_encode('Prestation du salarié'));
	$sheet->setCellValue('I1',utf8_encode('Réf offre'));
	$sheet->setCellValue('J1',utf8_encode('Date création formulaire'));
	$sheet->setCellValue('K1',utf8_encode('Annulation salarié'));
	$sheet->setCellValue('L1',utf8_encode("Métier de l’offre"));
	$sheet->setCellValue('M1',utf8_encode("Type de poste"));
	$sheet->setCellValue('N1',utf8_encode("Ptf d’acccueil"));
	$sheet->setCellValue('O1',utf8_encode('Lieu du poste'));
	$sheet->setCellValue('P1',utf8_encode('Salarié retenu'));
	$sheet->setCellValue('Q1',utf8_encode('Commentaires'));
	$sheet->setCellValue('R1',utf8_encode('Nbre de candidature sur ce poste'));
	$sheet->setCellValue('S1',utf8_encode('Nbre de candidature différente de la personne'));
	$sheet->setCellValue('T1',utf8_encode('Statut du poste'));
	$sheet->setCellValue('U1',utf8_encode('Suppression offre'));
	$sheet->setCellValue('V1',utf8_encode('Modification offre'));
	$sheet->setCellValue('W1',utf8_encode('Commentaire formulaire'));
	$sheet->setCellValue('X1',utf8_encode('CV'));
	$sheet->setCellValue('Y1',utf8_encode('Date démarrage'));
	$sheet->setCellValue('Z1',utf8_encode('Date butoir pour postuler'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Application number'));
	$sheet->setCellValue('B1',utf8_encode('Registration number'));
	$sheet->setCellValue('C1',utf8_encode("Employee's name"));
	$sheet->setCellValue('D1',utf8_encode("Employee's current occupation"));
	$sheet->setCellValue('E1',utf8_encode('Origin operating unit of the employee'));
	$sheet->setCellValue('F1',utf8_encode("Employee's phone number"));
	$sheet->setCellValue('G1',utf8_encode('Employee email address'));
	$sheet->setCellValue('H1',utf8_encode('Employee site'));
	$sheet->setCellValue('I1',utf8_encode('Offer ref'));
	$sheet->setCellValue('J1',utf8_encode('Form creation date'));
	$sheet->setCellValue('K1',utf8_encode('Employee cancellation'));
	$sheet->setCellValue('L1',utf8_encode('Job offer'));
	$sheet->setCellValue('M1',utf8_encode("Position type"));
	$sheet->setCellValue('N1',utf8_encode('Reception operating unit'));
	$sheet->setCellValue('O1',utf8_encode('Job location'));
	$sheet->setCellValue('P1',utf8_encode('Retained employee'));
	$sheet->setCellValue('Q1',utf8_encode('Comments'));
	$sheet->setCellValue('R1',utf8_encode('Number of applications for this position'));
	$sheet->setCellValue('S1',utf8_encode('Number of application different from the person'));
	$sheet->setCellValue('T1',utf8_encode('Job status'));
	$sheet->setCellValue('U1',utf8_encode('Offer deletion'));
	$sheet->setCellValue('V1',utf8_encode('Offer modification'));
	$sheet->setCellValue('W1',utf8_encode('Comment form'));
	$sheet->setCellValue('X1',utf8_encode('CV'));
	$sheet->setCellValue('Y1',utf8_encode('Start date'));
	$sheet->setCellValue('Z1',utf8_encode('Deadline for applying'));
}

$sheet->getStyle('A1:Z1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

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

$requete2="SELECT talentboost_candidature.Id AS Id_Candidature, talentboost_annonce.Id,DateDemande,Id_Demandeur,talentboost_annonce.Id_Prestation,Metier,Nombre,Lieu,RaisonRefus,talentboost_annonce.Suppr,
CONCAT(Metier,'-',
Lieu,'-',
Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',DATE_FORMAT(DateValidationDG,'%d%m%y')
) AS Ref,
CONCAT(Metier,'-',
Division,'-',
Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',DATE_FORMAT(DateValidationDG,'%d%m%y')
) AS RefOffre,
RaisonRefus,RaisonRefusRecrutement,RaisonRefusApprobation,
EtatApprobation,EtatRecrutement,IF(DateActualisation>'0001-01-01',DateActualisation,DateValidationDG) AS DateRecrutement,EtatPoste,talentboost_annonce.Nombre AS NombrePoste,
".$reqSuite."
DateBesoin,Duree,PosteDefinitif,IF(DateActualisation>'0001-01-01',DateActualisation,DateValidationDG) AS DateButoire,DateRecrutementMAJ,PosteOccupe,
(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=talentboost_annonce.Id_Prestation) AS Prestation,
(SELECT Libelle FROM talentboost_domaine WHERE Id=Id_Domaine) AS Domaine,Tel,Mail,
(SELECT Libelle FROM new_competences_plateforme WHERE Id=talentboost_annonce.Id_Plateforme) AS Plateforme,
talentboost_annonce.Id_Plateforme,
(SELECT COUNT(Id) FROM talentboost_candidature WHERE Id_Annonce=talentboost_annonce.Id AND talentboost_candidature.Suppr=0) AS Nombre,
(SELECT COUNT(TAB.Id) FROM talentboost_candidature AS TAB WHERE TAB.Id_Personne=talentboost_candidature.Id_Personne AND TAB.Suppr=0) AS NombreCandidature,
(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,EtatValidation,
(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,CandidatRetenu,
(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Matricule,CV,Motivation,
(SELECT Libelle FROM new_competences_plateforme WHERE Id=talentboost_annonce.Id_Plateforme) AS PlateformePersonne,
(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=talentboost_candidature.Id_Prestation) AS PrestationPersonne,
DateCreation, LEFT(HeureCreation,8)	AS HeureCreation,talentboost_candidature.Suppr AS SupprCandidature,talentboost_candidature.DateSuppr,DateMAJ,
DateRDV,LEFT(HeureRDV,5) AS HeureRDV, IF(Priorite=0,'',Priorite) AS Priorite,Commentaire
 ";
$requete=" FROM talentboost_candidature
		LEFT JOIN talentboost_annonce ON talentboost_candidature.Id_Annonce=talentboost_annonce.Id
		WHERE talentboost_annonce.Suppr=0  AND ValidationContratDG=1 ";
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
					talentboost_annonce.Id_Prestation IN 
					(SELECT new_competences_personne_poste_prestation.Id_Prestation
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
$requete.=" AND talentboost_annonce.Id_Plateforme=".$_SESSION['FiltreRecrutAnnonce_Plateforme']." ";
}
if($_SESSION['FiltreRecrutAnnonce_Metier']<>0){
$requete.=" AND talentboost_annonce.Metier LIKE '%".$_SESSION['FiltreRecrutAnnonce_Metier']."%' ";
}
if($_SESSION['FiltreRecrutAnnonce_Domaine']<>0){
$requete.=" AND talentboost_annonce.Id_Domaine=".$_SESSION['FiltreRecrutAnnonce_Domaine']." ";
}
if($_SESSION['FiltreRecrutAnnonce_Programme']<>0){
$requete.=" AND talentboost_annonce.Programme=".$_SESSION['FiltreRecrutAnnonce_Programme']." ";
}
if($_SESSION['FiltreRecrutAnnonce_Etat']<>-2){
$requete.=" AND talentboost_annonce.EtatPoste=".$_SESSION['FiltreRecrutAnnonce_Etat']." ";
}
if($_SESSION['FiltreRecrutAnnonce_DateDemarrage']>"0001-01-01" && $_SESSION['FiltreRecrutAnnonce_DateDemarrage']<>""){
$requete.=" AND talentboost_annonce.DateBesoin".$_SESSION['FiltreRecrutAnnonce_SigneDateDemarrage']." '".$_SESSION['FiltreRecrutAnnonce_DateDemarrage']."' ";
}
if($_SESSION['FiltreRecrutAnnonce_Information']<>""){
$requete.=" AND (
	(SELECT Libelle FROM talentboost_typehoraire WHERE Id=Id_TypeHoraire) LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
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
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Id_Candidature']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Matricule']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Personne']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['PosteOccupe']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['PlateformePersonne']));
		$sheet->setCellValue('F'.$ligne,utf8_encode($row['Tel']));
		$sheet->setCellValue('G'.$ligne,utf8_encode($row['Mail']));
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['PrestationPersonne']));
		$sheet->setCellValue('I'.$ligne,utf8_encode($row['Ref']));
		$sheet->setCellValue('J'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateCreation'])));
		if($row['SupprCandidature']==1){
			$sheet->setCellValue('K'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateSuppr'])));
		}
		$sheet->setCellValue('L'.$ligne,utf8_encode($row['Metier']));
		if($row['PosteDefinitif']==1){
			$sheet->setCellValue('N'.$ligne,utf8_encode("Poste définitif"));
		}
		elseif($row['PosteDefinitif']==0){
			$sheet->setCellValue('N'.$ligne,utf8_encode("Mission"));
		}
		elseif($row['PosteDefinitif']==2){
			$sheet->setCellValue('N'.$ligne,utf8_encode("CDD 6 mois"));
		}
		elseif($row['PosteDefinitif']==3){
			$sheet->setCellValue('N'.$ligne,utf8_encode("CDD 2 mois"));
		}
		elseif($row['PosteDefinitif']==4){
			$sheet->setCellValue('N'.$ligne,utf8_encode("CDD"));
		}
		
		$sheet->setCellValue('O'.$ligne,utf8_encode($row['Plateforme']));
		$sheet->setCellValue('P'.$ligne,utf8_encode($row['Lieu']));
		if($row['CandidatRetenu']==1){
			$sheet->setCellValue('Q'.$ligne,utf8_encode("X"));
		}
		
		
		$sheet->setCellValue('R'.$ligne,utf8_encode($row['Commentaire']));
		$sheet->setCellValue('S'.$ligne,utf8_encode($row['Nombre']."/".$row['NombrePoste']));
		$sheet->setCellValue('T'.$ligne,utf8_encode($row['NombreCandidature']));
		$sheet->setCellValue('U'.$ligne,utf8_encode($row['Statut2']));
		
		if($row['EtatPoste']==-1){
			$sheet->setCellValue('V'.$ligne,utf8_encode("X"));
		}
		
		if($row['DateRecrutementMAJ']>'0001-01-01'){
			$sheet->setCellValue('W'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateRecrutementMAJ'])));
		}
		$sheet->setCellValue('X'.$ligne,utf8_encode($row['Motivation']));
		if($row['CV']<>""){
			$sheet->setCellValue('Y'.$ligne,utf8_encode("X"));
		}

		$sheet->setCellValue('Z'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateBesoin'])));
		
		$dateButoir=date("d/m/Y",strtotime($row['DateButoire']." +22 day"));
		$JourdateButoir=date("w",strtotime($row['DateButoire']." +22 day"));
		if($JourdateButoir==6){$dateButoir=date("d/m/Y",strtotime($row['DateButoire']." +24 day"));}
		if($JourdateButoir==0){$dateButoir=date("d/m/Y",strtotime($row['DateButoire']." +23 day"));}
		$sheet->setCellValue('AA'.$ligne,utf8_encode($dateButoir));
		
		$sheet->getStyle('A'.$ligne.':AA'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		
		if($row['CandidatRetenu']==1){
			$sheet->getStyle('A'.$ligne.':AA'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'69bf3c'))));
		}
		if($row['SupprCandidature']==1){
			$sheet->getStyle('A'.$ligne.':AA'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'7d8185'))));
		}
		
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
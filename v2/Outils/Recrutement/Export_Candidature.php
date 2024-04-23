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
	$sheet->setCellValue('M1',utf8_encode("Catégorie d’emploi, à titre indicatif"));
	$sheet->setCellValue('N1',utf8_encode("Type de poste"));
	$sheet->setCellValue('O1',utf8_encode("Ptf d’acccueil"));
	$sheet->setCellValue('P1',utf8_encode('Lieu du poste'));
	$sheet->setCellValue('Q1',utf8_encode('Salarié retenu'));
	$sheet->setCellValue('R1',utf8_encode('Commentaires'));
	$sheet->setCellValue('S1',utf8_encode('Nbre de candidature sur ce poste'));
	$sheet->setCellValue('T1',utf8_encode('Nbre de candidature différente de la personne'));
	$sheet->setCellValue('U1',utf8_encode('Statut du poste'));
	$sheet->setCellValue('V1',utf8_encode('Suppression offre'));
	$sheet->setCellValue('W1',utf8_encode('Modification offre'));
	$sheet->setCellValue('X1',utf8_encode('Commentaire formulaire'));
	$sheet->setCellValue('Y1',utf8_encode('CV'));
	$sheet->setCellValue('Z1',utf8_encode('Date démarrage'));
	$sheet->setCellValue('AA1',utf8_encode('Date butoir pour postuler'));
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
	$sheet->setCellValue('M1',utf8_encode("Job category, for information only"));
	$sheet->setCellValue('N1',utf8_encode("Position type"));
	$sheet->setCellValue('O1',utf8_encode('Reception operating unit'));
	$sheet->setCellValue('P1',utf8_encode('Job location'));
	$sheet->setCellValue('Q1',utf8_encode('Retained employee'));
	$sheet->setCellValue('R1',utf8_encode('Comments'));
	$sheet->setCellValue('S1',utf8_encode('Number of applications for this position'));
	$sheet->setCellValue('T1',utf8_encode('Number of application different from the person'));
	$sheet->setCellValue('U1',utf8_encode('Job status'));
	$sheet->setCellValue('V1',utf8_encode('Offer deletion'));
	$sheet->setCellValue('W1',utf8_encode('Offer modification'));
	$sheet->setCellValue('X1',utf8_encode('Comment form'));
	$sheet->setCellValue('Y1',utf8_encode('CV'));
	$sheet->setCellValue('Z1',utf8_encode('Start date'));
	$sheet->setCellValue('AA1',utf8_encode('Deadline for applying'));
}

$sheet->getStyle('A1:AA1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

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

$requete2="SELECT recrut_candidature.Id AS Id_Candidature, recrut_annonce.Id,DateDemande,Id_Demandeur,recrut_annonce.Id_Prestation,Metier,Nombre,Lieu,RaisonRefus,recrut_annonce.Suppr,
CONCAT(Metier,'-',
Lieu,'-',
Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',DATE_FORMAT(DateRecrutement,'%d%m%y')
) AS Ref,
CONCAT(Metier,'-',
Division,'-',
Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',DATE_FORMAT(DateRecrutement,'%d%m%y')
) AS RefOffre,
RaisonRefus,RaisonRefusRecrutement,RaisonRefusApprobation,
EtatApprobation,EtatRecrutement,DateRecrutement,EtatPoste,recrut_annonce.Nombre AS NombrePoste,
".$reqSuite."
DateBesoin,Duree,PosteDefinitif,DateRecrutement AS DateButoire,DateRecrutementMAJ,PosteOccupe,
(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=recrut_annonce.Id_Prestation) AS Prestation,
(SELECT Libelle FROM recrut_categorieprofessionnelle WHERE Id=Id_CategorieProfessionnelle) AS CategorieProf,
(SELECT Libelle FROM recrut_domaine WHERE Id=Id_Domaine) AS Domaine,Tel,Mail,
(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=recrut_annonce.Id_Prestation) AS Plateforme,
(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=recrut_annonce.Id_Prestation) AS Id_Plateforme,
(SELECT COUNT(Id) FROM recrut_candidature WHERE Id_Annonce=recrut_annonce.Id AND recrut_candidature.Suppr=0) AS Nombre,
(SELECT COUNT(TAB.Id) FROM recrut_candidature AS TAB WHERE TAB.Id_Personne=recrut_candidature.Id_Personne AND TAB.Suppr=0) AS NombreCandidature,
(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,EtatValidation,
(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,CandidatRetenu,
(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Matricule,CV,Motivation,
(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS PlateformePersonne,
(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=recrut_candidature.Id_Prestation) AS PrestationPersonne,
DateCreation, LEFT(HeureCreation,8)	AS HeureCreation,recrut_candidature.Suppr AS SupprCandidature,recrut_candidature.DateSuppr,DateMAJ,
DateRDV,LEFT(HeureRDV,5) AS HeureRDV, IF(Priorite=0,'',Priorite) AS Priorite,Commentaire
 ";
$requete=" FROM recrut_candidature
		LEFT JOIN recrut_annonce ON recrut_candidature.Id_Annonce=recrut_annonce.Id
		WHERE recrut_annonce.Suppr=0  AND EtatRecrutement=1 AND OuvertureAutresPlateformes=1 ";
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
					recrut_annonce.Id_Prestation IN 
					(SELECT new_competences_personne_poste_prestation.Id_Prestation
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
$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=recrut_annonce.Id_Prestation)=".$_SESSION['FiltreRecrutAnnonce_Plateforme']." ";
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
		$sheet->setCellValue('M'.$ligne,utf8_encode($row['CategorieProf']));
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
		
		$dateButoir=date("d/m/Y",strtotime($row['DateButoire']." +15 day"));
		$JourdateButoir=date("w",strtotime($row['DateButoire']." +15 day"));
		if($JourdateButoir==6){$dateButoir=date("d/m/Y",strtotime($row['DateButoire']." +17 day"));}
		if($JourdateButoir==0){$dateButoir=date("d/m/Y",strtotime($row['DateButoire']." +16 day"));}
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
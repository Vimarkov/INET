<?php
session_start();
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("../Formation/Globales_Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
require_once("../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$vert="6fff55";
$orange="ffe915";
$rouge="ff151c";
$gris="aaaaaa";
$blanc="ffffff";

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('TemplateEPE.xlsx');
$sheet = $excel->getSheetByName('Liste');

$requete2="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne ";
$requete="FROM new_rh_etatcivil
	RIGHT JOIN epe_personne_datebutoir 
	ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne 
	WHERE MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') 
	AND MetierPaie<>'' AND Cadre IN (0,1) 
	AND TypeEntretien ='EPE' 
	AND YEAR(DateButoir) = ".$_SESSION['FiltreEPE_Annee']." 
	AND IF((SELECT COUNT(Id)
	FROM epe_personne 
	WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
	(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
	FROM epe_personne 
	WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']."),
	'A faire') IN ('Réalisé') ";

if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
	//Vérifier si appartient à une prestation OPTEA ou compétence
		$requete.="AND 
			(
				SELECT COUNT(new_competences_personne_prestation.Id)
				FROM new_competences_personne_prestation
				LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
				WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
				AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
				AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
				AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)";
				if($_SESSION['FiltreEPE_Plateforme']<>"0"){$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) = ".$_SESSION['FiltreEPE_Plateforme']." ";}
				if($_SESSION['FiltreEPE_Prestation']<>"0"){$requete.="AND new_competences_personne_prestation.Id_Prestation = ".$_SESSION['FiltreEPE_Prestation']." ";}
				if($_SESSION['FiltreEPE_Pole']<>"0"){$requete.="AND new_competences_personne_prestation.Id_Pole = ".$_SESSION['FiltreEPE_Pole']." ";}
			$requete.="
			)>0 ";
}
else{
	//Vérifier si appartient à une prestation OPTEA ou compétence
$requete.="AND
			(
				SELECT COUNT(new_competences_personne_prestation.Id)
				FROM new_competences_personne_prestation
				LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
				WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
				AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
				AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
				AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)";
				if($_SESSION['FiltreEPE_Plateforme']<>"0"){$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) = ".$_SESSION['FiltreEPE_Plateforme']." ";}
				if($_SESSION['FiltreEPE_Prestation']<>"0"){$requete.="AND new_competences_personne_prestation.Id_Prestation = ".$_SESSION['FiltreEPE_Prestation']." ";}
				if($_SESSION['FiltreEPE_Pole']<>"0"){$requete.="AND new_competences_personne_prestation.Id_Pole = ".$_SESSION['FiltreEPE_Pole']." ";}
			$requete.="
				AND 
				((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN 
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
					)
					OR CONCAT(new_competences_personne_prestation.Id_Prestation,'_',new_competences_personne_prestation.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$_SESSION["Id_Personne"]."
					AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.") 
					AND Backup=0
					)
					OR new_rh_etatcivil.Id=".$_SESSION['Id_Personne']."
				)
			)>0 ";
}
if($_SESSION['FiltreEPE_Personne']<>"0"){
	$requete.="AND new_rh_etatcivil.Id =".$_SESSION['FiltreEPE_Personne']." ";
}
if($_SESSION['FiltreEPE_Manager']<>"0"){
	$requete.="AND (SELECT COUNT(Id)
	FROM epe_personne 
	WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." AND Id_Evaluateur=".$_SESSION['FiltreEPE_Manager'].")>0  ";
}

	
$requete.="ORDER BY Personne ";

$result=mysqli_query($bdd,$requete2.$requete);
$nbResulta=mysqli_num_rows($result);


if($nbResulta>0){
	$ligne=3;
	while($row=mysqli_fetch_array($result)){

		$reqNb="SELECT DISTINCT epe_personne.Id AS EpePersonne, new_rh_etatcivil.Id, 
		CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,DateAnciennete,
		Type AS TypeE,DateEntretien,
		IF(Cadre=0,'Non cadre','Cadre') AS TypeEntretien,DateButoir,
		Cadre,
		(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS Manager,
		(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
		(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,Metier,
		(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
		(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS MatriculeAAAManager,MetierManager,
		ConnaissanceMetier,ComConnaissanceMetier,UtilisationDoc,ComUtilisationDoc,Productivite,ComProductivite,Organisation,ComOrganisation,CapaciteManager,ComCapaciteManager,
		RespectObjectif,ComRespectObjectif,AnglaisTech,ComAnglaisTech,CapaciteTuteur,ComCapaciteTuteur,Reporting,ComReporting,PlanAction,ComPlanAction,RespectBudget,ComRespectBudget,
		RepresentationEntreprise,ComRepresentationEntreprise,SouciSatisfaction,ComSouciSatisfaction,Ecoute,ComEcoute,TraitementInsatisfaction,ComTraitementInsatisfaction,ExplicationSolution,ComExplicationSolution,
		ComprehensionInsatisfaction,ComComprehensionInsatisfaction,ConnaissanceManagement,ComConnaissanceManagement,ConnaissanceMetierEquipe,ComConnaissanceMetierEquipe,CapaciteFixerObjectif,ComCapaciteFixerObjectif,
		Delegation,ComDelegation,AnimationEquipe,ComAnimationEquipe,RespectQSE,ComRespectQSE,ContributionNC,ComContributionNC,RespectRegles,ComRespectRegles,PortTenues,ComPortTenues,
		PortEPI,ComPortEPI,RespectOutils,ComRespectOutils,Assiduite,ComAssiduite,EspritEntreprise,ComEspritEntreprise,TravailEquipe,ComTravailEquipe,Dispo,ComDispo,Autonomie,ComAutonomie,Initiative,ComInitiative,
		Communication,ComCommunication,OrganisationCharge,ComSOrganisationCharge,ComEOrganisationCharge,AmplitudeJournee,ComSAmplitudeJournee,ComEAmplitudeJournee,OrganisationTravail,
		ComSOrganisationTravail,ComEOrganisationTravail,ArticulationActiviteProPerso,ComSArticulationActiviteProPerso,ComEArticulationActiviteProPerso,Remuneration,ComSRemuneration,
		ComERemuneration,Stress,ComSStress,ComEStress,EntretienRH,EntretienMedecienTravail,EntretienLumanisy,EntretienSoutienPsycho,EntretienHSE,EntretienAutre,FormationOrganisationTravail,FormationStress,
		FormationSophrologie,FormationAutre,ComEntretienRH,ComEntretienMedecienTravail,ComEntretienLumanisy,ComEntretienSoutienPsycho,ComEntretienHSE,ComEntretienAutre,ComEEntretienAutre,
		ComFormationOrganisationTravail,ComFormationStress,ComFormationSophrologie,ComFormationAutre,ComEFormationAutre,CommentaireLibreS,CommentaireLibreE,
		PointFort,PointFaible,ObjectifProgression,ComSalarie,ComEvaluateur,DateEvaluateur
		FROM new_rh_etatcivil
		LEFT JOIN epe_personne
		ON new_rh_etatcivil.Id=epe_personne.Id_Personne
		WHERE MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') 
		AND MetierPaie<>'' AND Cadre IN (0,1) 
		AND new_rh_etatcivil.Id=".$row['Id']."
		AND YEAR(DateButoir) = ".$_SESSION['FiltreEPE_Annee']." 
		AND IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager'))) IN ('Signature salarié','Signature manager','Réalisé') 
		AND Type ='EPE' ";
		$ResultNb=mysqli_query($bdd,$reqNb);
		$leNb=mysqli_num_rows($ResultNb);
		
		$rowNb=mysqli_fetch_array($ResultNb);
		
		$Presta=$rowNb['Prestation'];
		if($rowNb['Pole']<>""){
			$Presta.=" - ".$rowNb['Pole'];
		}

		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($rowNb['MatriculeAAA'])));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($rowNb['Personne'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($rowNb['Metier'])));
		if($rowNb['DateAnciennete']>'0001-01-01'){
			$date = explode("-",$rowNb['DateAnciennete']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('D'.$ligne,$time);
			$sheet->getStyle('D'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		if($rowNb['DateButoir']>'0001-01-01'){
			$date = explode("-",$rowNb['DateButoir']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('E'.$ligne,$time);
			$sheet->getStyle('E'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		$sheet->setCellValue('F'.$ligne,utf8_encode($rowNb['TypeEntretien']));
		if($rowNb['DateEntretien']>'0001-01-01'){
			$date = explode("-",$rowNb['DateEntretien']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('G'.$ligne,$time);
			$sheet->getStyle('G'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($rowNb['Plateforme'])));
		$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($rowNb['Manager'])));
		$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($rowNb['MatriculeAAAManager'])));
		$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($rowNb['MetierManager'])));
		
		
		$req="SELECT Id, Evaluation, Note, Commentaire
		FROM epe_personne_objectifanneeprecedente 
		WHERE Suppr=0 AND Id_epepersonne=".$rowNb['EpePersonne']." ";

		$resultAnneePrec=mysqli_query($bdd,$req);
		$NbAnneePrec=mysqli_num_rows($resultAnneePrec);
		if($NbAnneePrec>0){
			$AnneePrec="";
			while($rowAnneePrec=mysqli_fetch_array($resultAnneePrec)){
				if($AnneePrec<>""){$AnneePrec.="\n";}
				$AnneePrec=$rowAnneePrec['Evaluation']." - ".$rowAnneePrec['Note'];
			}
			$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($AnneePrec)));
		}
		$tab=array('ConnaissanceMetier','UtilisationDoc','Productivite','Organisation','CapaciteManager','RespectObjectif','AnglaisTech','CapaciteTuteur','Reporting','PlanAction','RespectBudget','RepresentationEntreprise','SouciSatisfaction','Ecoute','TraitementInsatisfaction','ExplicationSolution','ComprehensionInsatisfaction','ConnaissanceManagement','ConnaissanceMetierEquipe','CapaciteFixerObjectif','Delegation','AnimationEquipe','RespectQSE','ContributionNC','RespectRegles','PortTenues','PortEPI','RespectOutils','Assiduite','EspritEntreprise','TravailEquipe','Dispo','Autonomie','Initiative','Communication'); 
		$col="M";
		for($i=0;$i<sizeof($tab);$i++){
			if($rowNb[$tab[$i]]==-1){$sheet->setCellValue($col.$ligne,utf8_encode(stripslashes("NA")));}
			else{$sheet->setCellValue($col.$ligne,utf8_encode(stripslashes($rowNb[$tab[$i]])));}
			$col++;
		}
		
		$req="SELECT Id, Objectif, Indicateur, MoyensAssocies, Commentaire
		FROM epe_personne_objectifannee 
		WHERE Suppr=0 AND  Id_epepersonne=".$rowNb['EpePersonne']." ";
		$result2=mysqli_query($bdd,$req);
		$Nb2=mysqli_num_rows($result2);
		if($Nb2>0){
			$Objectifs="";
			while($row2=mysqli_fetch_array($result2)){
				if($Objectifs<>""){$Objectifs.="\n";}
				$Objectifs=$row2['Objectif']." - ".$row2['Indicateur']." - ".$row2['MoyensAssocies'];
			}
			$sheet->setCellValue('AV'.$ligne,utf8_encode(stripslashes($Objectifs)));
		}
		
		$req="SELECT Formation, DateDebut, DateFin, EvaluationAFroid, Commentaire 
			FROM epe_personne_bilanformation 
			WHERE Suppr=0 AND Id_epepersonne=".$rowNb['EpePersonne']." ";
		$result2=mysqli_query($bdd,$req);
		$Nb2=mysqli_num_rows($result2);
		if($Nb2>0){
			$Formations="";
			while($row2=mysqli_fetch_array($result2)){
				if($Formations<>""){$Formations.="\n";}
				$Formations=$row2['Formation']." - ".AfficheDateJJ_MM_AAAA($row2['DateDebut'])." - ".AfficheDateJJ_MM_AAAA($row2['DateFin'])." - ".$row2['EvaluationAFroid'];
			}
			$sheet->setCellValue('AW'.$ligne,utf8_encode(stripslashes($Formations)));
		}
		
		$req="SELECT Id, Formation, DateDebut, DateFin, Commentaire 
			FROM epe_personne_besoinformation 
			WHERE Suppr=0 AND Id_epepersonne=".$rowNb['EpePersonne']." ";
		$result2=mysqli_query($bdd,$req);
		$Nb2=mysqli_num_rows($result2);
		if($Nb2>0){
			$Formations="";
			while($row2=mysqli_fetch_array($result2)){
				if($Formations<>""){$Formations.="\n";}
				$Formations=$row2['Formation']." - ".AfficheDateJJ_MM_AAAA($row2['DateDebut'])." - ".AfficheDateJJ_MM_AAAA($row2['DateFin']);
			}
			$sheet->setCellValue('AX'.$ligne,utf8_encode(stripslashes($Formations)));
		}
		
		$req="SELECT Id, Formation, Favorable,Priorite, Commentaire 
			FROM epe_personne_souhaitformation 
			WHERE Suppr=0 AND Id_epepersonne=".$rowNb['EpePersonne']." ";
		$result2=mysqli_query($bdd,$req);
		$Nb2=mysqli_num_rows($result2);
		if($Nb2>0){
			$Formations="";
			while($row2=mysqli_fetch_array($result2)){
				if($Formations<>""){$Formations.="\n";}
				$favorable="Défavorable";
				if($row2['Favorable']==1){$favorable="Favorable";}
				$Formations=$row2['Formation']." - ".$favorable." - ".$row2['Priorite'];
			}
			$sheet->setCellValue('AY'.$ligne,utf8_encode(stripslashes($Formations)));
		}
		
		$tab=array('OrganisationCharge','AmplitudeJournee','OrganisationTravail','ArticulationActiviteProPerso','Remuneration'); 
		$col="AZ";
		for($i=0;$i<sizeof($tab);$i++){
			$sheet->setCellValue($col.$ligne,utf8_encode(stripslashes($rowNb['ComS'.$tab[$i]])));
			$col++;
		}
		
		$tab=array('Stress'); 
		$col="BE";
		for($i=0;$i<sizeof($tab);$i++){
			if($rowNb[$tab[$i]]==0){$sheet->setCellValue($col.$ligne,utf8_encode(stripslashes("NA")));}
			else{$sheet->setCellValue($col.$ligne,utf8_encode(stripslashes($rowNb[$tab[$i]])));}
			$col++;
		}
		
		$BesoinStress="";
		if($rowNb['EntretienRH']==1){$BesoinStress="Entretien RH";}
		if($rowNb['EntretienMedecienTravail']==1){
			if($BesoinStress<>""){$BesoinStress=", ";}
			$BesoinStress="Entretien avec la médecine du travail";
		}
		if($rowNb['EntretienLumanisy']==1){
			if($BesoinStress<>""){$BesoinStress=", ";}
			$BesoinStress="Entretien avec le service social du travail";
		}
		if($rowNb['EntretienSoutienPsycho']==1){
			if($BesoinStress<>""){$BesoinStress=", ";}
			$BesoinStress="Soutien psychologique";
		}
		if($rowNb['EntretienHSE']==1){
			if($BesoinStress<>""){$BesoinStress=", ";}
			$BesoinStress="Soutien psychologique";
		}
		if($rowNb['EntretienAutre']==1){
			if($BesoinStress<>""){$BesoinStress=", ";}
			$BesoinStress="Entretien avec service HSE";
		}
		if($rowNb['FormationOrganisationTravail']==1){
			if($BesoinStress<>""){$BesoinStress=", ";}
			$BesoinStress="Formation Organisation du travail, gestion du temps et des priorités";
		}
		if($rowNb['FormationStress']==1){
			if($BesoinStress<>""){$BesoinStress=", ";}
			$BesoinStress="Formation Gestion du stress";
		}
		if($rowNb['FormationAutre']==1){
			if($BesoinStress<>""){$BesoinStress=", ";}
			$BesoinStress="Formation Autre";
		}
		$sheet->setCellValue('BF'.$ligne,utf8_encode(stripslashes($BesoinStress)));
		$sheet->setCellValue('BG'.$ligne,utf8_encode(stripslashes($rowNb['CommentaireLibreS'])));
		$sheet->setCellValue('BH'.$ligne,utf8_encode(stripslashes($rowNb['PointFort'])));
		$sheet->setCellValue('BI'.$ligne,utf8_encode(stripslashes($rowNb['PointFaible'])));
		$sheet->setCellValue('BJ'.$ligne,utf8_encode(stripslashes($rowNb['ObjectifProgression'])));
		$sheet->setCellValue('BK'.$ligne,utf8_encode(stripslashes($rowNb['ComSalarie'])));
		$sheet->setCellValue('BL'.$ligne,utf8_encode(stripslashes($rowNb['ComEvaluateur'])));
		
		if($rowNb['DateEntretien']>'0001-01-01'){
			$date = explode("-",$rowNb['DateEntretien']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('BM'.$ligne,$time);
			$sheet->getStyle('BM'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		
		if($rowNb['DateEvaluateur']>'0001-01-01'){
			$date = explode("-",$rowNb['DateEvaluateur']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('BN'.$ligne,$time);
			$sheet->getStyle('BN'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		
		$sheet->getStyle('A'.$ligne.':BN'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':BN'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A'.$ligne.':BN'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		
		$ligne++;
	}
}


//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="Extract.xlsx"');}
else{header('Content-Disposition: attachment;filename="Extract.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../tmp/Extract.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
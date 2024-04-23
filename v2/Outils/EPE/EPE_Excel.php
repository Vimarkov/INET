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
if($_GET['Cadre']==0){
	$excel = $workbook->load('D-0705-012-EPE_NonCadre.xlsx');
	$sheet = $excel->getSheetByName('NON CADRES');
}
else{
	$excel = $workbook->load('D-0705-012-EPE_Cadre.xlsx');
	$sheet = $excel->getSheetByName('CADRES');
}

$requete="SELECT new_rh_etatcivil.Id, Nom, Prenom,MatriculeAAA,DateAncienneteCDI,YEAR(IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir)) AS Annee,
			MetierPaie AS Metier
			FROM epe_personne_datebutoir
			LEFT JOIN new_rh_etatcivil
			ON epe_personne_datebutoir.Id_Personne=new_rh_etatcivil.Id
			WHERE epe_personne_datebutoir.Id=".$_GET['Id'];
$result=mysqli_query($bdd,$requete);
$rowEPE=mysqli_fetch_array($result);

$req="SELECT Id, Type,ModeBrouillon,Id_Personne,DateCreation,Id_Createur,Metier,DateAnciennete,DateEntretien,DateButoir,Id_Evaluateur,MetierManager,
		(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
		(SELECT Nom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Nom,
		(SELECT Prenom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Prenom,
		IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01' ,'Réalisé','Signature manager'))) AS Etat,
		(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MatriculeAAA,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS Manager,
		(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS MatriculeAAAManager,
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
		PointFort,PointFaible,ObjectifProgression,ComSalarie,ComEvaluateur,DateEvaluateur,DateSalarie,SalarieRefuseSignature
	FROM epe_personne 
	WHERE Suppr=0 
	AND Id_Personne=".$rowEPE['Id']."
	AND YEAR(DateButoir)='".$rowEPE['Annee']."'
	AND Type='EPE'
	ORDER BY Id DESC ";
$result=mysqli_query($bdd,$req);
$rowEPERempli=mysqli_fetch_array($result);

$Plateforme="";
$req="SELECT Libelle FROM new_competences_plateforme WHERE Id=".$rowEPERempli['Id_Plateforme'];
$ResultPresta=mysqli_query($bdd,$req);
$NbPrest=mysqli_num_rows($ResultPresta);
if($NbPrest>0){
	$RowPresta=mysqli_fetch_array($ResultPresta);
	$Plateforme=$RowPresta['Libelle'];
}

$sheet->setCellValue('B3',utf8_encode(stripslashes($rowEPERempli['MatriculeAAA'])));
$sheet->setCellValue('B4',utf8_encode(stripslashes($rowEPERempli['Nom'])));
$sheet->setCellValue('B5',utf8_encode(stripslashes($rowEPERempli['Prenom'])));
$sheet->setCellValue('B6',utf8_encode(stripslashes($rowEPERempli['Metier'])));
$sheet->setCellValue('B7',utf8_encode(AfficheDateJJ_MM_AAAA($rowEPERempli['DateAnciennete'])));

$sheet->setCellValue('Q3',utf8_encode(AfficheDateJJ_MM_AAAA($rowEPERempli['DateEntretien'])));
$sheet->setCellValue('Q4',utf8_encode(stripslashes($Plateforme)));
$sheet->setCellValue('Q5',utf8_encode(stripslashes($rowEPERempli['Manager'])));
$sheet->setCellValue('Q6',utf8_encode(stripslashes($rowEPERempli['MatriculeAAAManager'])));
$sheet->setCellValue('Q7',utf8_encode(stripslashes($rowEPERempli['MetierManager'])));

$req="SELECT Id, Evaluation, Note, Commentaire
FROM epe_personne_objectifanneeprecedente 
WHERE Suppr=0 AND Id_epepersonne=".$rowEPERempli['Id']." ";
$resultAnneePrec=mysqli_query($bdd,$req);
$NbAnneePrec=mysqli_num_rows($resultAnneePrec);

if($NbAnneePrec>0){
	$ligne=11;
	while($rowAnneePrec=mysqli_fetch_array($resultAnneePrec)){
		$AnneePrec=$rowAnneePrec['Evaluation']." - ".$rowAnneePrec['Note'];
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($rowAnneePrec['Evaluation'])));
		if($rowAnneePrec['Note']==-1){$sheet->setCellValue("D".$ligne,utf8_encode("X"));}
		elseif($rowAnneePrec['Note']==1){$sheet->setCellValue("E".$ligne,utf8_encode("X"));}
		elseif($rowAnneePrec['Note']==2){$sheet->setCellValue("F".$ligne,utf8_encode("X"));}
		elseif($rowAnneePrec['Note']==3){$sheet->setCellValue("G".$ligne,utf8_encode("X"));}
		elseif($rowAnneePrec['Note']==4){$sheet->setCellValue("H".$ligne,utf8_encode("X"));}
		$sheet->setCellValue("I".$ligne,utf8_encode(stripslashes($rowAnneePrec['Commentaire'])));
		$ligne++;
	}
	
}

$tab=array('ConnaissanceMetier','UtilisationDoc','Productivite','Organisation','CapaciteManager','RespectObjectif','AnglaisTech','CapaciteTuteur','Reporting','PlanAction','RespectBudget'); 
$ligne=24;
for($i=0;$i<sizeof($tab);$i++){
	if($rowEPERempli[$tab[$i]]==-1){$sheet->setCellValue("D".$ligne,utf8_encode("X"));}
	elseif($rowEPERempli[$tab[$i]]==1){$sheet->setCellValue("E".$ligne,utf8_encode("X"));}
	elseif($rowEPERempli[$tab[$i]]==2){$sheet->setCellValue("F".$ligne,utf8_encode("X"));}
	elseif($rowEPERempli[$tab[$i]]==3){$sheet->setCellValue("G".$ligne,utf8_encode("X"));}
	elseif($rowEPERempli[$tab[$i]]==4){$sheet->setCellValue("H".$ligne,utf8_encode("X"));}
	$sheet->setCellValue("I".$ligne,utf8_encode(stripslashes($rowEPERempli['Com'.$tab[$i]])));
	$ligne++;
}

$tab=array('RepresentationEntreprise','SouciSatisfaction','Ecoute','TraitementInsatisfaction','ExplicationSolution','ComprehensionInsatisfaction'); 
$ligne=36;
for($i=0;$i<sizeof($tab);$i++){
	if($rowEPERempli[$tab[$i]]==-1){$sheet->setCellValue("D".$ligne,utf8_encode("X"));}
	elseif($rowEPERempli[$tab[$i]]==1){$sheet->setCellValue("E".$ligne,utf8_encode("X"));}
	elseif($rowEPERempli[$tab[$i]]==2){$sheet->setCellValue("F".$ligne,utf8_encode("X"));}
	elseif($rowEPERempli[$tab[$i]]==3){$sheet->setCellValue("G".$ligne,utf8_encode("X"));}
	elseif($rowEPERempli[$tab[$i]]==4){$sheet->setCellValue("H".$ligne,utf8_encode("X"));}
	$sheet->setCellValue("I".$ligne,utf8_encode(stripslashes($rowEPERempli['Com'.$tab[$i]])));
	$ligne++;
}

$tab=array('ConnaissanceManagement','ConnaissanceMetierEquipe','CapaciteFixerObjectif','Delegation','AnimationEquipe'); 
$ligne=43;
for($i=0;$i<sizeof($tab);$i++){
	if($rowEPERempli[$tab[$i]]==-1){$sheet->setCellValue("D".$ligne,utf8_encode("X"));}
	elseif($rowEPERempli[$tab[$i]]==1){$sheet->setCellValue("E".$ligne,utf8_encode("X"));}
	elseif($rowEPERempli[$tab[$i]]==2){$sheet->setCellValue("F".$ligne,utf8_encode("X"));}
	elseif($rowEPERempli[$tab[$i]]==3){$sheet->setCellValue("G".$ligne,utf8_encode("X"));}
	elseif($rowEPERempli[$tab[$i]]==4){$sheet->setCellValue("H".$ligne,utf8_encode("X"));}
	$sheet->setCellValue("I".$ligne,utf8_encode(stripslashes($rowEPERempli['Com'.$tab[$i]])));
	$ligne++;
}

$tab=array('RespectQSE','ContributionNC','RespectRegles','PortTenues','PortEPI','RespectOutils'); 
$ligne=49;
for($i=0;$i<sizeof($tab);$i++){
	if($rowEPERempli[$tab[$i]]==-1){$sheet->setCellValue("D".$ligne,utf8_encode("X"));}
	elseif($rowEPERempli[$tab[$i]]==1){$sheet->setCellValue("E".$ligne,utf8_encode("X"));}
	elseif($rowEPERempli[$tab[$i]]==2){$sheet->setCellValue("F".$ligne,utf8_encode("X"));}
	elseif($rowEPERempli[$tab[$i]]==3){$sheet->setCellValue("G".$ligne,utf8_encode("X"));}
	elseif($rowEPERempli[$tab[$i]]==4){$sheet->setCellValue("H".$ligne,utf8_encode("X"));}
	$sheet->setCellValue("I".$ligne,utf8_encode(stripslashes($rowEPERempli['Com'.$tab[$i]])));
	$ligne++;
}


$tab=array('Assiduite','EspritEntreprise','TravailEquipe','Dispo','Autonomie','Initiative','Communication'); 
$ligne=57;
for($i=0;$i<sizeof($tab);$i++){
	if($rowEPERempli[$tab[$i]]==-1){$sheet->setCellValue("D".$ligne,utf8_encode("X"));}
	elseif($rowEPERempli[$tab[$i]]==1){$sheet->setCellValue("E".$ligne,utf8_encode("X"));}
	elseif($rowEPERempli[$tab[$i]]==2){$sheet->setCellValue("F".$ligne,utf8_encode("X"));}
	elseif($rowEPERempli[$tab[$i]]==3){$sheet->setCellValue("G".$ligne,utf8_encode("X"));}
	elseif($rowEPERempli[$tab[$i]]==4){$sheet->setCellValue("H".$ligne,utf8_encode("X"));}
	$sheet->setCellValue("I".$ligne,utf8_encode(stripslashes($rowEPERempli['Com'.$tab[$i]])));
	$ligne++;
}

$req="SELECT Id, Objectif, Indicateur, MoyensAssocies, Commentaire
FROM epe_personne_objectifannee 
WHERE Suppr=0 AND  Id_epepersonne=".$rowEPERempli['Id']." ";
$result2=mysqli_query($bdd,$req);
$Nb2=mysqli_num_rows($result2);
$ligne=66;
if($Nb2>0){
	while($row2=mysqli_fetch_array($result2)){
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row2['Objectif'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row2['Indicateur'])));
		$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($row2['MoyensAssocies'])));
		$sheet->setCellValue('P'.$ligne,utf8_encode(stripslashes($row2['Commentaire'])));
		$ligne++;
	}
}

$req="SELECT Formation, DateDebut, DateFin, EvaluationAFroid, Commentaire 
	FROM epe_personne_bilanformation 
	WHERE Suppr=0 AND Id_epepersonne=".$rowEPERempli['Id']." ";
$result2=mysqli_query($bdd,$req);
$Nb2=mysqli_num_rows($result2);
$ligne=84;
if($Nb2>0){
	$Formations="";
	while($row2=mysqli_fetch_array($result2)){
		if($ligne<=98){
			$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row2['Formation'])));
			$sheet->setCellValue('G'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row2['DateDebut'])));
			$sheet->setCellValue('J'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row2['DateFin'])));
			if($row2['EvaluationAFroid']==1){$sheet->setCellValue('L'.$ligne,utf8_encode("X"));}
			elseif($row2['EvaluationAFroid']==2){$sheet->setCellValue('M'.$ligne,utf8_encode("X"));}
			elseif($row2['EvaluationAFroid']==3){$sheet->setCellValue('N'.$ligne,utf8_encode("X"));}
			elseif($row2['EvaluationAFroid']==4){$sheet->setCellValue('O'.$ligne,utf8_encode("X"));}
			$sheet->setCellValue('P'.$ligne,utf8_encode(stripslashes($row2['Commentaire'])));
			$ligne++;
		}
	}
}

$req="SELECT Id, Formation, DateDebut, DateFin, Commentaire 
	FROM epe_personne_besoinformation 
	WHERE Suppr=0 AND Id_epepersonne=".$rowEPERempli['Id']." ";
$result2=mysqli_query($bdd,$req);
$Nb2=mysqli_num_rows($result2);
$ligne=100;
if($Nb2>0){
	$Formations="";
	while($row2=mysqli_fetch_array($result2)){
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row2['Formation'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row2['DateDebut'])." - ".AfficheDateJJ_MM_AAAA($row2['DateFin'])));
		$sheet->setCellValue('P'.$ligne,utf8_encode(stripslashes($row2['Commentaire'])));
		$ligne++;
	}
}


$req="SELECT Id, Formation, Favorable,Priorite, Commentaire 
	FROM epe_personne_souhaitformation 
	WHERE Suppr=0 AND Id_epepersonne=".$rowEPERempli['Id']." ";
$result2=mysqli_query($bdd,$req);
$Nb2=mysqli_num_rows($result2);
$ligne=111;
if($Nb2>0){
	$Formations="";
	while($row2=mysqli_fetch_array($result2)){
		$sheet->setCellValue('A'.$ligne,utf8_encode(stripslashes($row2['Formation'])));
		
		$imgF="../../Images/CaseNonCoche.png";
		$imgD="../../Images/CaseCoche.png";
		if($row2['Favorable']==1){$imgF="../../Images/CaseCoche.png";$imgD="../../Images/CaseNonCoche.png";}
		
		$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
		$objDrawingNonCoche->setName('case');
		$objDrawingNonCoche->setDescription('PHPExcel case');
		$objDrawingNonCoche->setPath($imgF);
		$objDrawingNonCoche->setWidth(30);
		$objDrawingNonCoche->setHeight(30);
		$objDrawingNonCoche->setOffsetX(5);
		$objDrawingNonCoche->setOffsetY(8);
		
		$objDrawingNonCoche->setCoordinates('H'.$ligne);
		$objDrawingNonCoche->setWorksheet($sheet);
		
		$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
		$objDrawingNonCoche->setName('case');
		$objDrawingNonCoche->setDescription('PHPExcel case');
		$objDrawingNonCoche->setPath($imgD);
		$objDrawingNonCoche->setWidth(30);
		$objDrawingNonCoche->setHeight(30);
		$objDrawingNonCoche->setOffsetX(5);
		$objDrawingNonCoche->setOffsetY(5);
		
		$objDrawingNonCoche->setCoordinates('M'.$ligne);
		$objDrawingNonCoche->setWorksheet($sheet);
		
		$sheet->setCellValue('P'.$ligne,utf8_encode(stripslashes($row2['Priorite'])));
		$sheet->setCellValue('Q'.$ligne,utf8_encode(stripslashes($row2['Commentaire'])));
		$ligne++;
	}
}

while($ligne<=120){
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath("../../Images/CaseNonCoche.png");
	$objDrawingNonCoche->setWidth(30);
	$objDrawingNonCoche->setHeight(30);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(8);
	$objDrawingNonCoche->setCoordinates('H'.$ligne);
	$objDrawingNonCoche->setWorksheet($sheet);
	
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath("../../Images/CaseNonCoche.png");
	$objDrawingNonCoche->setWidth(30);
	$objDrawingNonCoche->setHeight(30);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(5);
	$objDrawingNonCoche->setCoordinates('M'.$ligne);
	$objDrawingNonCoche->setWorksheet($sheet);
	
	$ligne++;
}


if($_GET['Cadre']==0){
	$tab=array('OrganisationCharge','ArticulationActiviteProPerso'); 
	$ligne=123;
	for($i=0;$i<sizeof($tab);$i++){
		$sheet->setCellValue("E".$ligne,utf8_encode(stripslashes($rowEPERempli['ComS'.$tab[$i]])));
		$sheet->setCellValue("Q".$ligne,utf8_encode(stripslashes($rowEPERempli['ComE'.$tab[$i]])));
		$ligne++;
	}
	
	$tab=array('Stress'); 
	$ligne=128;
	for($i=0;$i<sizeof($tab);$i++){
		if($rowEPERempli[$tab[$i]]==1){$sheet->setCellValue("E".$ligne,utf8_encode("X"));}
		elseif($rowEPERempli[$tab[$i]]==2){$sheet->setCellValue("F".$ligne,utf8_encode("X"));}
		elseif($rowEPERempli[$tab[$i]]==3){$sheet->setCellValue("G".$ligne,utf8_encode("X"));}
		$sheet->setCellValue("H".$ligne,utf8_encode(stripslashes($rowEPERempli['ComS'.$tab[$i]])));
		$sheet->setCellValue("Q".$ligne,utf8_encode(stripslashes($rowEPERempli['ComE'.$tab[$i]])));
		$ligne++;
	}
	
	$ligne=129;
	if($rowEPERempli['EntretienRH']==0){$img="../../Images/CaseNonCoche.png";}
	else{$img="../../Images/CaseCoche.png";}
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath($img);
	$objDrawingNonCoche->setWidth(30);
	$objDrawingNonCoche->setHeight(30);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(15);
	$objDrawingNonCoche->setCoordinates('E'.$ligne);
	$objDrawingNonCoche->setWorksheet($sheet);
	
	if($rowEPERempli['EntretienMedecienTravail']==0){$img="../../Images/CaseNonCoche.png";}
	else{$img="../../Images/CaseCoche.png";}
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath($img);
	$objDrawingNonCoche->setWidth(30);
	$objDrawingNonCoche->setHeight(30);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(15);
	$objDrawingNonCoche->setCoordinates('K'.$ligne);
	$objDrawingNonCoche->setWorksheet($sheet);
	
	$ligne++;
	if($rowEPERempli['EntretienLumanisy']==0){$img="../../Images/CaseNonCoche.png";}
	else{$img="../../Images/CaseCoche.png";}
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath($img);
	$objDrawingNonCoche->setWidth(30);
	$objDrawingNonCoche->setHeight(30);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(15);
	$objDrawingNonCoche->setCoordinates('E'.$ligne);
	$objDrawingNonCoche->setWorksheet($sheet);
	
	$ligne++;
	if($rowEPERempli['EntretienSoutienPsycho']==0){$img="../../Images/CaseNonCoche.png";}
	else{$img="../../Images/CaseCoche.png";}
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath($img);
	$objDrawingNonCoche->setWidth(30);
	$objDrawingNonCoche->setHeight(30);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(15);
	$objDrawingNonCoche->setCoordinates('E'.$ligne);
	$objDrawingNonCoche->setWorksheet($sheet);
	
	$ligne++;
	if($rowEPERempli['EntretienHSE']==0){$img="../../Images/CaseNonCoche.png";}
	else{$img="../../Images/CaseCoche.png";}
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath($img);
	$objDrawingNonCoche->setWidth(30);
	$objDrawingNonCoche->setHeight(30);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(15);
	$objDrawingNonCoche->setCoordinates('E'.$ligne);
	$objDrawingNonCoche->setWorksheet($sheet);
	
	if($rowEPERempli['EntretienAutre']==0){$img="../../Images/CaseNonCoche.png";}
	else{$img="../../Images/CaseCoche.png";}
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath($img);
	$objDrawingNonCoche->setWidth(30);
	$objDrawingNonCoche->setHeight(30);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(15);
	$objDrawingNonCoche->setCoordinates('M'.$ligne);
	$objDrawingNonCoche->setWorksheet($sheet);
	$sheet->setCellValue('P'.$ligne,utf8_encode(stripslashes($rowEPERempli['ComEntretienAutre'])));
	
	$ligne++;
	if($rowEPERempli['FormationOrganisationTravail']==0){$img="../../Images/CaseNonCoche.png";}
	else{$img="../../Images/CaseCoche.png";}
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath($img);
	$objDrawingNonCoche->setWidth(30);
	$objDrawingNonCoche->setHeight(30);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(15);
	$objDrawingNonCoche->setCoordinates('E'.$ligne);
	$objDrawingNonCoche->setWorksheet($sheet);
	
	$ligne++;
	if($rowEPERempli['FormationStress']==0){$img="../../Images/CaseNonCoche.png";}
	else{$img="../../Images/CaseCoche.png";}
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath($img);
	$objDrawingNonCoche->setWidth(30);
	$objDrawingNonCoche->setHeight(30);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(15);
	$objDrawingNonCoche->setCoordinates('E'.$ligne);
	$objDrawingNonCoche->setWorksheet($sheet);
	
	$ligne++;
	if($rowEPERempli['FormationAutre']==0){$img="../../Images/CaseNonCoche.png";}
	else{$img="../../Images/CaseCoche.png";}
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath($img);
	$objDrawingNonCoche->setWidth(30);
	$objDrawingNonCoche->setHeight(30);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(15);
	$objDrawingNonCoche->setCoordinates('E'.$ligne);
	$objDrawingNonCoche->setWorksheet($sheet);
	$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($rowEPERempli['ComFormationAutre'])));
	
	
	$listeCommentaires=stripslashes($rowEPERempli['ComEntretienRH']);
	$listeCommentaires.=stripslashes("\n".$rowEPERempli['ComEntretienMedecienTravail']);
	$listeCommentaires.=stripslashes("\n".$rowEPERempli['ComEntretienSoutienPsycho']);
	$listeCommentaires.=stripslashes("\n".$rowEPERempli['ComEntretienMedecienTravail']);
	$listeCommentaires.=stripslashes("\n".$rowEPERempli['ComEntretienHSE']);
	$listeCommentaires.=stripslashes("\n".$rowEPERempli['ComEEntretienAutre']);
	$listeCommentaires.=stripslashes("\n\n\n".$rowEPERempli['ComFormationOrganisationTravail']);
	$listeCommentaires.=stripslashes("\n".$rowEPERempli['ComFormationStress']);
	$listeCommentaires.=stripslashes("\n".$rowEPERempli['ComEFormationAutre']);
	$sheet->setCellValue('Q129',utf8_encode($listeCommentaires));
	
	$sheet->setCellValue('E139',utf8_encode(stripslashes($rowEPERempli['CommentaireLibreS'])));
	$sheet->setCellValue('Q139',utf8_encode(stripslashes($rowEPERempli['CommentaireLibreE'])));
	
	$sheet->setCellValue('E146',utf8_encode(stripslashes($rowEPERempli['PointFort'])));
	$sheet->setCellValue('E147',utf8_encode(stripslashes($rowEPERempli['PointFaible'])));
	$sheet->setCellValue('E148',utf8_encode(stripslashes($rowEPERempli['ObjectifProgression'])));
	
	$sheet->setCellValue('E150',utf8_encode(stripslashes($rowEPERempli['ComSalarie'])));
	$sheet->setCellValue('E151',utf8_encode(stripslashes($rowEPERempli['ComEvaluateur'])));
	
	if($rowEPERempli['Etat']=="Signature salarié"){
		$sheet->setCellValue('E152',utf8_encode(stripslashes(AfficheDateJJ_MM_AAAA($rowEPERempli['DateEntretien']))));
		$sheet->setCellValue('R153',utf8_encode(stripslashes($rowEPERempli['Manager']." \n'signature électronique'")));
		$sheet->getStyle('R153')->getAlignment()->setWrapText(true);
	}
	elseif($rowEPERempli['Etat']=="Signature manager"){
		$sheet->setCellValue('E152',utf8_encode(stripslashes(AfficheDateJJ_MM_AAAA($rowEPERempli['DateEntretien']))));
		$sheet->setCellValue('R153',utf8_encode(stripslashes($rowEPERempli['Manager']." \n'signature électronique'")));
		$sheet->getStyle('R153')->getAlignment()->setWrapText(true);
		if($rowEPERempli['SalarieRefuseSignature']==1){
			$sheet->setCellValue('E153',utf8_encode(stripslashes($rowEPERempli['Nom']." ".$rowEPERempli['Prenom']." refuse de signer son entretien")));
		}
		else{
			$sheet->setCellValue('E153',utf8_encode(stripslashes($rowEPERempli['Nom']." ".$rowEPERempli['Prenom']." \n'signature électronique'")));
		}
		$sheet->getStyle('E153')->getAlignment()->setWrapText(true);
	}

}
else{
	$tab=array('OrganisationCharge','AmplitudeJournee','OrganisationTravail','ArticulationActiviteProPerso','Remuneration'); 
	$ligne=109;
	for($i=0;$i<sizeof($tab);$i++){
		$sheet->setCellValue("E".$ligne,utf8_encode(stripslashes($rowEPERempli['ComS'.$tab[$i]])));
		$sheet->setCellValue("Q".$ligne,utf8_encode(stripslashes($rowEPERempli['ComE'.$tab[$i]])));
		$ligne++;
	}
	
	$tab=array('Stress'); 
	$ligne=131;
	for($i=0;$i<sizeof($tab);$i++){
		if($rowEPERempli[$tab[$i]]==1){$sheet->setCellValue("E".$ligne,utf8_encode("X"));}
		elseif($rowEPERempli[$tab[$i]]==2){$sheet->setCellValue("F".$ligne,utf8_encode("X"));}
		elseif($rowEPERempli[$tab[$i]]==3){$sheet->setCellValue("G".$ligne,utf8_encode("X"));}
		$sheet->setCellValue("H".$ligne,utf8_encode(stripslashes($rowEPERempli['ComS'.$tab[$i]])));
		$sheet->setCellValue("Q".$ligne,utf8_encode(stripslashes($rowEPERempli['ComE'.$tab[$i]])));
		$ligne++;
	}
	
	$ligne=132;
	if($rowEPERempli['EntretienRH']==0){$img="../../Images/CaseNonCoche.png";}
	else{$img="../../Images/CaseCoche.png";}
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath($img);
	$objDrawingNonCoche->setWidth(30);
	$objDrawingNonCoche->setHeight(30);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(15);
	$objDrawingNonCoche->setCoordinates('E'.$ligne);
	$objDrawingNonCoche->setWorksheet($sheet);
	
	if($rowEPERempli['EntretienMedecienTravail']==0){$img="../../Images/CaseNonCoche.png";}
	else{$img="../../Images/CaseCoche.png";}
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath($img);
	$objDrawingNonCoche->setWidth(30);
	$objDrawingNonCoche->setHeight(30);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(15);
	$objDrawingNonCoche->setCoordinates('K'.$ligne);
	$objDrawingNonCoche->setWorksheet($sheet);
	
	$ligne++;
	if($rowEPERempli['EntretienLumanisy']==0){$img="../../Images/CaseNonCoche.png";}
	else{$img="../../Images/CaseCoche.png";}
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath($img);
	$objDrawingNonCoche->setWidth(30);
	$objDrawingNonCoche->setHeight(30);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(15);
	$objDrawingNonCoche->setCoordinates('E'.$ligne);
	$objDrawingNonCoche->setWorksheet($sheet);
	
	$ligne++;
	if($rowEPERempli['EntretienSoutienPsycho']==0){$img="../../Images/CaseNonCoche.png";}
	else{$img="../../Images/CaseCoche.png";}
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath($img);
	$objDrawingNonCoche->setWidth(30);
	$objDrawingNonCoche->setHeight(30);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(15);
	$objDrawingNonCoche->setCoordinates('E'.$ligne);
	$objDrawingNonCoche->setWorksheet($sheet);
	
	$ligne++;
	if($rowEPERempli['EntretienHSE']==0){$img="../../Images/CaseNonCoche.png";}
	else{$img="../../Images/CaseCoche.png";}
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath($img);
	$objDrawingNonCoche->setWidth(30);
	$objDrawingNonCoche->setHeight(30);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(15);
	$objDrawingNonCoche->setCoordinates('E'.$ligne);
	$objDrawingNonCoche->setWorksheet($sheet);
	
	if($rowEPERempli['EntretienAutre']==0){$img="../../Images/CaseNonCoche.png";}
	else{$img="../../Images/CaseCoche.png";}
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath($img);
	$objDrawingNonCoche->setWidth(30);
	$objDrawingNonCoche->setHeight(30);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(15);
	$objDrawingNonCoche->setCoordinates('M'.$ligne);
	$objDrawingNonCoche->setWorksheet($sheet);
	$sheet->setCellValue('P'.$ligne,utf8_encode(stripslashes($rowEPERempli['ComEntretienAutre'])));
	
	$ligne++;
	if($rowEPERempli['FormationOrganisationTravail']==0){$img="../../Images/CaseNonCoche.png";}
	else{$img="../../Images/CaseCoche.png";}
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath($img);
	$objDrawingNonCoche->setWidth(30);
	$objDrawingNonCoche->setHeight(30);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(15);
	$objDrawingNonCoche->setCoordinates('E'.$ligne);
	$objDrawingNonCoche->setWorksheet($sheet);
	
	$ligne++;
	if($rowEPERempli['FormationStress']==0){$img="../../Images/CaseNonCoche.png";}
	else{$img="../../Images/CaseCoche.png";}
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath($img);
	$objDrawingNonCoche->setWidth(30);
	$objDrawingNonCoche->setHeight(30);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(15);
	$objDrawingNonCoche->setCoordinates('E'.$ligne);
	$objDrawingNonCoche->setWorksheet($sheet);
	
	$ligne++;
	if($rowEPERempli['FormationAutre']==0){$img="../../Images/CaseNonCoche.png";}
	else{$img="../../Images/CaseCoche.png";}
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
	$objDrawingNonCoche->setName('case');
	$objDrawingNonCoche->setDescription('PHPExcel case');
	$objDrawingNonCoche->setPath($img);
	$objDrawingNonCoche->setWidth(30);
	$objDrawingNonCoche->setHeight(30);
	$objDrawingNonCoche->setOffsetX(5);
	$objDrawingNonCoche->setOffsetY(15);
	$objDrawingNonCoche->setCoordinates('E'.$ligne);
	$objDrawingNonCoche->setWorksheet($sheet);
	$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($rowEPERempli['ComFormationAutre'])));
	
	$listeCommentaires=stripslashes($rowEPERempli['ComEntretienRH']);
	$listeCommentaires.=stripslashes("\n".$rowEPERempli['ComEntretienMedecienTravail']);
	$listeCommentaires.=stripslashes("\n".$rowEPERempli['ComEntretienSoutienPsycho']);
	$listeCommentaires.=stripslashes("\n".$rowEPERempli['ComEntretienMedecienTravail']);
	$listeCommentaires.=stripslashes("\n".$rowEPERempli['ComEntretienHSE']);
	$listeCommentaires.=stripslashes("\n".$rowEPERempli['ComEEntretienAutre']);
	$listeCommentaires.=stripslashes("\n\n\n".$rowEPERempli['ComFormationOrganisationTravail']);
	$listeCommentaires.=stripslashes("\n".$rowEPERempli['ComFormationStress']);
	$listeCommentaires.=stripslashes("\n".$rowEPERempli['ComEFormationAutre']);
	$sheet->setCellValue('Q132',utf8_encode($listeCommentaires));
	
	$sheet->setCellValue('E142',utf8_encode(stripslashes($rowEPERempli['CommentaireLibreS'])));
	$sheet->setCellValue('Q142',utf8_encode(stripslashes($rowEPERempli['CommentaireLibreE'])));
	
	$sheet->setCellValue('E149',utf8_encode(stripslashes($rowEPERempli['PointFort'])));
	$sheet->setCellValue('E150',utf8_encode(stripslashes($rowEPERempli['PointFaible'])));
	$sheet->setCellValue('E151',utf8_encode(stripslashes($rowEPERempli['ObjectifProgression'])));
	
	$sheet->setCellValue('E153',utf8_encode(stripslashes($rowEPERempli['ComSalarie'])));
	$sheet->setCellValue('E154',utf8_encode(stripslashes($rowEPERempli['ComEvaluateur'])));
	
	if($rowEPERempli['Etat']=="Signature salarié"){
		$sheet->setCellValue('E155',utf8_encode(stripslashes(AfficheDateJJ_MM_AAAA($rowEPERempli['DateEntretien']))));
		$sheet->setCellValue('R156',utf8_encode(stripslashes($rowEPERempli['Manager']." \n'signature électronique'")));
		$sheet->getStyle('R156')->getAlignment()->setWrapText(true);
	}
	elseif($rowEPERempli['Etat']=="Signature manager"){
		$sheet->setCellValue('E155',utf8_encode(stripslashes(AfficheDateJJ_MM_AAAA($rowEPERempli['DateEntretien']))));
		$sheet->setCellValue('R156',utf8_encode(stripslashes($rowEPERempli['Manager']." \n'signature électronique'")));
		$sheet->getStyle('R156')->getAlignment()->setWrapText(true);
		if($rowEPERempli['SalarieRefuseSignature']==1){
			$sheet->setCellValue('E156',utf8_encode(stripslashes($rowEPERempli['Nom']." ".$rowEPERempli['Prenom']." refuse de signer son entretien")));
		}
		else{
			$sheet->setCellValue('E156',utf8_encode(stripslashes($rowEPERempli['Nom']." ".$rowEPERempli['Prenom']." \n'signature électronique'")));
		}
		$sheet->getStyle('E156')->getAlignment()->setWrapText(true);
	}
}



//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="D-0705-012.xlsx"');}
else{header('Content-Disposition: attachment;filename="D-0705-012.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../tmp/D-0705-012.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
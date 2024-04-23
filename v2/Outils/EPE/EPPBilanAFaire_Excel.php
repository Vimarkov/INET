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
$excel = $workbook->load('D-0705-014-EPPBilan.xlsx');

$sheet = $excel->getSheetByName('Bilan');

$requete="SELECT new_rh_etatcivil.Id, Nom, Prenom,MatriculeAAA,DateAncienneteCDI,YEAR(IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir)) AS Annee,
			MetierPaie AS Metier
			FROM epe_personne_datebutoir
			LEFT JOIN new_rh_etatcivil
			ON epe_personne_datebutoir.Id_Personne=new_rh_etatcivil.Id
			WHERE epe_personne_datebutoir.Id=".$_GET['Id'];
$result=mysqli_query($bdd,$requete);
$rowEPE=mysqli_fetch_array($result);

$Id_Prestation=0;
$Id_Pole=0;

$req="SELECT Id_Prestation,Id_Pole 
	FROM new_competences_personne_prestation
	WHERE Id_Personne=".$rowEPE['Id']." 
	AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
	AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."') ";
$resultch=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($resultch);
$Id_PrestationPole="0_0";
if($nb>0){
	$rowMouv=mysqli_fetch_array($resultch);
	$Id_Prestation=$rowMouv['Id_Prestation'];
	$Id_Pole=$rowMouv['Id_Pole'];
}


$Presta="";
$Plateforme="";
$req="SELECT LEFT(Libelle,7) AS Prestation,(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation;
$ResultPresta=mysqli_query($bdd,$req);
$NbPrest=mysqli_num_rows($ResultPresta);
if($NbPrest>0){
	$RowPresta=mysqli_fetch_array($ResultPresta);
	$Presta=$RowPresta['Prestation'];
	$Plateforme=$RowPresta['Plateforme'];
}

$Pole="";
$req="SELECT Libelle FROM new_competences_pole WHERE Id=".$Id_Pole;
$ResultPole=mysqli_query($bdd,$req);
$NbPole=mysqli_num_rows($ResultPole);
if($NbPole>0){
	$RowPole=mysqli_fetch_array($ResultPole);
	$Pole=$RowPole['Libelle'];
}

if($Pole<>""){$Presta.=" - ".$Pole;}

$Manager="";
$MatriculeAAAManager="";
$MetierManager="";
$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne, MatriculeAAA, MetierPaie AS Metier
		FROM new_rh_etatcivil
		WHERE Id=".$_GET['Id_Manager'];
$ResultManager=mysqli_query($bdd,$req);
$NbManager=mysqli_num_rows($ResultManager);
if($NbManager>0){
	$RowManager=mysqli_fetch_array($ResultManager);
	$Manager=$RowManager['Personne'];
	$MatriculeAAAManager=$RowManager['MatriculeAAA'];
	$MetierManager=$RowManager['Metier'];
}

$dateFin=date('Y-12-31');
$dateDebut=date('Y-01-01',strtotime(date('Y-m-d')." -6 year"));

$AnneeFin=date('Y');
$AnneeDebut=date('Y',strtotime(date('Y-m-d')." -6 year"));

$req="SELECT Date_Reel 
FROM new_competences_personne_rh_eia 
WHERE Type='EPP' AND Id_Personne=".$rowEPE['Id']." 
AND Date_Reel>'0001-01-01' 
AND Date_Reel>='".$dateDebut."'
AND Date_Reel<='".$dateFin."'
UNION
SELECT DateEntretien AS Date_Reel
FROM epe_personne 
WHERE Suppr=0 
AND Type='EPP' 
AND Id_Personne=".$rowEPE['Id']." 
AND DateEntretien>='".$dateDebut."'
AND DateEntretien<='".$dateFin."'
ORDER BY Date_Reel DESC
";

$result2=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($result2);

$NbEntretien=$nbenreg;
$Entretiens="";
if($nbenreg>0){
	while($rowEntretiens=mysqli_fetch_array($result2)){
		if($Entretiens<>""){$Entretiens.=", ";}
		$Entretiens.=AfficheDateJJ_MM_AAAA($rowEntretiens['Date_Reel']);
	}
}


$Obligatoire="";
$NonObligatoire="";

//Liste des formations OBLIGATOIRES
$req="
	SELECT DateSession,Libelle,Organisme,Type
	FROM
	(
	SELECT
	form_besoin.Id AS Id_Besoin,
	0 AS Id_PersonneFormation,
	(
		SELECT
			form_session_date.DateSession
		FROM
			form_session_personne
		LEFT JOIN 
			form_session_date 
		ON 
			form_session_personne.Id_Session=form_session_date.Id_Session
		WHERE
			form_session_personne.Id_Besoin=form_besoin.Id
			AND form_session_personne.Id NOT IN 
				(
				SELECT
					Id_Session_Personne
				FROM
					form_session_personne_qualification
				WHERE
					Suppr=0	
				)
			AND form_session_personne.Suppr=0
			AND form_session_personne.Validation_Inscription=1
			AND form_session_personne.Presence=1
			AND form_session_date.Suppr=0
		ORDER BY DateSession DESC
		LIMIT 1
	) AS DateSession,
	(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
		WHERE form_formation_plateforme_parametres.Id_Formation=form_besoin.Id_Formation
		AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
		AND Suppr=0 LIMIT 1) AS Organisme,
	(SELECT IF(form_besoin.Motif='Renouvellement',LibelleRecyclage,Libelle)
		FROM form_formation_langue_infos
		WHERE Id_Formation=form_besoin.Id_Formation
		AND Id_Langue=
			(SELECT Id_Langue 
			FROM form_formation_plateforme_parametres 
			WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
			AND Id_Formation=form_besoin.Id_Formation
			AND Suppr=0 
			LIMIT 1)
		AND Suppr=0) AS Libelle,
'Professionnelle' AS Type
FROM
	form_besoin,
	new_competences_prestation
WHERE
	form_besoin.Id_Personne=".$rowEPE['Id']."
	AND form_besoin.Id_Prestation=new_competences_prestation.Id
	AND (SELECT Obligatoire FROM form_formation WHERE Id=form_besoin.Id_Formation)=1
	AND form_besoin.Suppr=0
	AND form_besoin.Valide=1
	AND form_besoin.Traite=4
	AND form_besoin.Id IN
	(
	SELECT
		Id_Besoin
	FROM
		form_session_personne
	WHERE
		form_session_personne.Id NOT IN 
			(
			SELECT
				Id_Session_Personne
			FROM
				form_session_personne_qualification
			WHERE
				Suppr=0	
			)
		AND Suppr=0
		AND form_session_personne.Validation_Inscription=1
		AND form_session_personne.Presence=1
	)
	AND (
		SELECT
			form_session_date.DateSession
		FROM
			form_session_personne
		LEFT JOIN 
			form_session_date 
		ON 
			form_session_personne.Id_Session=form_session_date.Id_Session
		WHERE
			form_session_personne.Id_Besoin=form_besoin.Id
			AND form_session_personne.Id NOT IN 
				(
				SELECT
					Id_Session_Personne
				FROM
					form_session_personne_qualification
				WHERE
					Suppr=0	
				)
			AND form_session_personne.Suppr=0
			AND form_session_personne.Validation_Inscription=1
			AND form_session_personne.Presence=1
			AND form_session_date.Suppr=0
		ORDER BY DateSession DESC
		LIMIT 1
	)>='".$dateDebut."'
	AND (
		SELECT
			form_session_date.DateSession
		FROM
			form_session_personne
		LEFT JOIN 
			form_session_date 
		ON 
			form_session_personne.Id_Session=form_session_date.Id_Session
		WHERE
			form_session_personne.Id_Besoin=form_besoin.Id
			AND form_session_personne.Id NOT IN 
				(
				SELECT
					Id_Session_Personne
				FROM
					form_session_personne_qualification
				WHERE
					Suppr=0	
				)
			AND form_session_personne.Suppr=0
			AND form_session_personne.Validation_Inscription=1
			AND form_session_personne.Presence=1
			AND form_session_date.Suppr=0
		ORDER BY DateSession DESC
		LIMIT 1
	)<='".$dateFin."'
	) AS TAB 
	UNION 
	SELECT 
	new_competences_personne_formation.Date AS DateSession,
	(SELECT Libelle FROM new_competences_formation WHERE new_competences_personne_formation.Id_Formation=new_competences_formation.Id) AS Libelle ,
	'' AS Organisme,
	new_competences_personne_formation.Type 
	FROM new_competences_personne_formation
	WHERE new_competences_personne_formation.Id_Personne=".$rowEPE['Id']." 
	AND new_competences_personne_formation.Date>='".$dateDebut."'
	AND new_competences_personne_formation.Date<='".$dateFin."'
	AND (SELECT Obligatoire FROM new_competences_formation WHERE new_competences_personne_formation.Id_Formation=new_competences_formation.Id)=1
	ORDER BY DateSession DESC, Type ASC, Libelle ASC ";

$result2=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($result2);
if($nbenreg>0){
	while($row2=mysqli_fetch_array($result2)){
		if($Obligatoire<>""){$Obligatoire.="\n";}
		$organisme="";
		if($row2['Organisme']<>""){$organisme=" - ".$row2['Organisme'];}
		$Obligatoire.=AfficheDateJJ_MM_AAAA($row2['DateSession'])." : ".$row2['Libelle'].$organisme." (".$row2['Type'].") ";
	}
}

$Requete_Qualif="
	SELECT
		new_competences_qualification.Id,
		new_competences_qualification.Id_Categorie_Qualification,
		new_competences_qualification.Libelle AS Qualif,
		new_competences_qualification.Periodicite_Surveillance,
		new_competences_categorie_qualification.Libelle,
		new_competences_relation.Sans_Fin,
		new_competences_relation.Evaluation,
		new_competences_relation.Date_QCM,
		new_competences_relation.QCM_Surveillance,
		new_competences_relation.Date_Surveillance,
		new_competences_relation.Id AS Id_Relation,
		new_competences_relation.Visible,
		new_competences_relation.Date_Debut,
		new_competences_relation.Date_Fin,
		new_competences_relation.Resultat_QCM,
		new_competences_relation.Id_Besoin,
		new_competences_relation.Id_Session_Personne_Qualification
	FROM
		new_competences_relation,
		new_competences_qualification,
		new_competences_categorie_qualification
	WHERE
		new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
		AND new_competences_qualification.Id=new_competences_relation.Id_Qualification_Parrainage
		AND new_competences_relation.Id_Personne=".$rowEPE['Id']."
		AND new_competences_relation.Type='Qualification'
		AND new_competences_relation.Date_Debut>='".$dateDebut."'
		AND new_competences_relation.Date_Debut<='".$dateFin."'
		AND new_competences_relation.Suppr=0
		AND new_competences_qualification.Obligatoire=1
		AND Evaluation NOT IN ('','B','Bi')
		AND new_competences_qualification.Id NOT IN (1643,1644)
	ORDER BY
		new_competences_categorie_qualification.Libelle ASC,
		new_competences_qualification.Libelle ASC,
		new_competences_relation.Date_Debut DESC,
		new_competences_relation.Date_QCM DESC";
$ListeQualification=mysqli_query($bdd,$Requete_Qualif);
$nbenreg=mysqli_num_rows($ListeQualification);
if($nbenreg>0){
	while($row2=mysqli_fetch_array($ListeQualification)){
		if($Obligatoire<>""){$Obligatoire.="\n";}
		$Obligatoire.=AfficheDateJJ_MM_AAAA($row2['Date_Debut'])." : ".$row2['Qualif']." (".$row2['Libelle'].") ";
	}
}

//Liste des formations NON OBLIGATOIRES
$req="
	SELECT DateSession,Libelle,Organisme,Type
	FROM
	(
	SELECT
	form_besoin.Id AS Id_Besoin,
	0 AS Id_PersonneFormation,
	(
		SELECT
			form_session_date.DateSession
		FROM
			form_session_personne
		LEFT JOIN 
			form_session_date 
		ON 
			form_session_personne.Id_Session=form_session_date.Id_Session
		WHERE
			form_session_personne.Id_Besoin=form_besoin.Id
			AND form_session_personne.Id NOT IN 
				(
				SELECT
					Id_Session_Personne
				FROM
					form_session_personne_qualification
				WHERE
					Suppr=0	
				)
			AND form_session_personne.Suppr=0
			AND form_session_personne.Validation_Inscription=1
			AND form_session_personne.Presence=1
			AND form_session_date.Suppr=0
		ORDER BY DateSession DESC
		LIMIT 1
	) AS DateSession,
	(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
		WHERE form_formation_plateforme_parametres.Id_Formation=form_besoin.Id_Formation
		AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
		AND Suppr=0 LIMIT 1) AS Organisme,
	(SELECT IF(form_besoin.Motif='Renouvellement',LibelleRecyclage,Libelle)
		FROM form_formation_langue_infos
		WHERE Id_Formation=form_besoin.Id_Formation
		AND Id_Langue=
			(SELECT Id_Langue 
			FROM form_formation_plateforme_parametres 
			WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
			AND Id_Formation=form_besoin.Id_Formation
			AND Suppr=0 
			LIMIT 1)
		AND Suppr=0) AS Libelle,
'Professionnelle' AS Type
FROM
	form_besoin,
	new_competences_prestation
WHERE
	form_besoin.Id_Personne=".$rowEPE['Id']."
	AND form_besoin.Id_Prestation=new_competences_prestation.Id
	AND (SELECT Obligatoire FROM form_formation WHERE Id=form_besoin.Id_Formation)=0
	AND form_besoin.Suppr=0
	AND form_besoin.Valide=1
	AND form_besoin.Traite=4
	AND form_besoin.Id IN
	(
	SELECT
		Id_Besoin
	FROM
		form_session_personne
	WHERE
		form_session_personne.Id NOT IN 
			(
			SELECT
				Id_Session_Personne
			FROM
				form_session_personne_qualification
			WHERE
				Suppr=0	
			)
		AND Suppr=0
		AND form_session_personne.Validation_Inscription=1
		AND form_session_personne.Presence=1
	)
	AND (
		SELECT
			form_session_date.DateSession
		FROM
			form_session_personne
		LEFT JOIN 
			form_session_date 
		ON 
			form_session_personne.Id_Session=form_session_date.Id_Session
		WHERE
			form_session_personne.Id_Besoin=form_besoin.Id
			AND form_session_personne.Id NOT IN 
				(
				SELECT
					Id_Session_Personne
				FROM
					form_session_personne_qualification
				WHERE
					Suppr=0	
				)
			AND form_session_personne.Suppr=0
			AND form_session_personne.Validation_Inscription=1
			AND form_session_personne.Presence=1
			AND form_session_date.Suppr=0
		ORDER BY DateSession DESC
		LIMIT 1
	)>='".$dateDebut."'
	AND (
		SELECT
			form_session_date.DateSession
		FROM
			form_session_personne
		LEFT JOIN 
			form_session_date 
		ON 
			form_session_personne.Id_Session=form_session_date.Id_Session
		WHERE
			form_session_personne.Id_Besoin=form_besoin.Id
			AND form_session_personne.Id NOT IN 
				(
				SELECT
					Id_Session_Personne
				FROM
					form_session_personne_qualification
				WHERE
					Suppr=0	
				)
			AND form_session_personne.Suppr=0
			AND form_session_personne.Validation_Inscription=1
			AND form_session_personne.Presence=1
			AND form_session_date.Suppr=0
		ORDER BY DateSession DESC
		LIMIT 1
	)<='".$dateFin."'
	) AS TAB 
	UNION 
	SELECT 
	new_competences_personne_formation.Date AS DateSession,
	(SELECT Libelle FROM new_competences_formation WHERE new_competences_personne_formation.Id_Formation=new_competences_formation.Id) AS Libelle ,
	'' AS Organisme,
	new_competences_personne_formation.Type 
	FROM new_competences_personne_formation
	WHERE new_competences_personne_formation.Id_Personne=".$rowEPE['Id']." 
	AND new_competences_personne_formation.Date>='".$dateDebut."'
	AND new_competences_personne_formation.Date<='".$dateFin."'
	AND (SELECT Obligatoire FROM new_competences_formation WHERE new_competences_personne_formation.Id_Formation=new_competences_formation.Id)=-1
	ORDER BY DateSession DESC, Type ASC, Libelle ASC ";
$result2=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($result2);
if($nbenreg>0){
	while($row2=mysqli_fetch_array($result2)){
		if($NonObligatoire<>""){$NonObligatoire.="\n";}
		$organisme="";
		if($row2['Organisme']<>""){$organisme=" - ".$row2['Organisme'];}
		$NonObligatoire.=AfficheDateJJ_MM_AAAA($row2['DateSession'])." : ".$row2['Libelle'].$organisme." (".$row2['Type'].") ";
	}
}

$Requete_Qualif="
	SELECT
		new_competences_qualification.Id,
		new_competences_qualification.Id_Categorie_Qualification,
		new_competences_qualification.Libelle AS Qualif,
		new_competences_qualification.Periodicite_Surveillance,
		new_competences_categorie_qualification.Libelle,
		new_competences_relation.Sans_Fin,
		new_competences_relation.Evaluation,
		new_competences_relation.Date_QCM,
		new_competences_relation.QCM_Surveillance,
		new_competences_relation.Date_Surveillance,
		new_competences_relation.Id AS Id_Relation,
		new_competences_relation.Visible,
		new_competences_relation.Date_Debut,
		new_competences_relation.Date_Fin,
		new_competences_relation.Resultat_QCM,
		new_competences_relation.Id_Besoin,
		new_competences_relation.Id_Session_Personne_Qualification
	FROM
		new_competences_relation,
		new_competences_qualification,
		new_competences_categorie_qualification
	WHERE
		new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
		AND new_competences_qualification.Id=new_competences_relation.Id_Qualification_Parrainage
		AND new_competences_relation.Id_Personne=".$rowEPE['Id']."
		AND new_competences_relation.Type='Qualification'
		AND new_competences_relation.Date_Debut>='".$dateDebut."'
		AND new_competences_relation.Date_Debut<='".$dateFin."'
		AND new_competences_relation.Suppr=0
		AND new_competences_qualification.Obligatoire=-1
		AND Evaluation NOT IN ('','B','Bi')
		AND new_competences_qualification.Id NOT IN (1643,1644)
	ORDER BY
		new_competences_categorie_qualification.Libelle ASC,
		new_competences_qualification.Libelle ASC,
		new_competences_relation.Date_Debut DESC,
		new_competences_relation.Date_QCM DESC";

$ListeQualification=mysqli_query($bdd,$Requete_Qualif);
$nbenreg=mysqli_num_rows($ListeQualification);
if($nbenreg>0){
	while($row2=mysqli_fetch_array($ListeQualification)){
		if($NonObligatoire<>""){$NonObligatoire.="\n";}
		$NonObligatoire.=AfficheDateJJ_MM_AAAA($row2['Date_Debut'])." : ".$row2['Qualif']." (".$row2['Libelle'].") ";
	}
}


//Evolutions salariale
$req="SELECT Annee, Type, Valeur
	FROM epe_personne_evolution
	WHERE epe_personne_evolution.Id_Personne=".$rowEPE['Id']." 
	AND Annee>='".$AnneeDebut."'
	AND Annee<='".$AnneeFin."'
	AND Type IN ('AG','AI')
	AND Suppr=0
	ORDER BY Annee, Type ";
$result2=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($result2);
$evolutionSalariale="";
if($nbenreg>0){
	while($row2=mysqli_fetch_array($result2)){
		if($evolutionSalariale<>""){$evolutionSalariale.=", ";}
		$evolutionSalariale.=$row2['Type']." (".$row2['Annee'].") ";
	}
}

//Evolutions pro
$req="SELECT Annee, Type, Valeur
	FROM epe_personne_evolution
	WHERE epe_personne_evolution.Id_Personne=".$rowEPE['Id']." 
	AND Annee>='".$AnneeDebut."'
	AND Annee<='".$AnneeFin."'
	AND Type NOT IN ('AG','AI')
	AND Suppr=0
	ORDER BY Annee, Type ";
$result2=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($result2);
$evolutionPro="";
if($nbenreg>0){
	while($row2=mysqli_fetch_array($result2)){
		if($evolutionPro<>""){$evolutionPro.=", ";}
		$evolutionPro.=$row2['Type']." - ".$row2['Valeur']." (".$row2['Annee'].") ";
	}
}


$sheet->setCellValue('B3',utf8_encode(stripslashes($rowEPE['MatriculeAAA'])));
$sheet->setCellValue('B4',utf8_encode(stripslashes($rowEPE['Nom'])));
$sheet->setCellValue('B5',utf8_encode(stripslashes($rowEPE['Prenom'])));
$sheet->setCellValue('B6',utf8_encode(stripslashes($rowEPE['Metier'])));
$sheet->setCellValue('B7',utf8_encode(AfficheDateJJ_MM_AAAA($rowEPE['DateAncienneteCDI'])));

$sheet->setCellValue('O3',utf8_encode(date('d/m/Y')));
$sheet->setCellValue('O4',utf8_encode(stripslashes($Plateforme)));
$sheet->setCellValue('O5',utf8_encode(stripslashes($Manager)));
$sheet->setCellValue('O6',utf8_encode(stripslashes($MatriculeAAAManager)));
$sheet->setCellValue('O7',utf8_encode(stripslashes($MetierManager)));


$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
$objDrawingNonCoche->setName('case');
$objDrawingNonCoche->setDescription('PHPExcel case');
$objDrawingNonCoche->setPath("../../Images/CaseNonCoche.png");
$objDrawingNonCoche->setWidth(30);
$objDrawingNonCoche->setHeight(30);
$objDrawingNonCoche->setOffsetX(-40);
$objDrawingNonCoche->setOffsetY(10);
$objDrawingNonCoche->setCoordinates('B9');
$objDrawingNonCoche->setWorksheet($sheet);

$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
$objDrawingNonCoche = new PHPExcel_Worksheet_Drawing();
$objDrawingNonCoche->setName('case');
$objDrawingNonCoche->setDescription('PHPExcel case');
$objDrawingNonCoche->setPath("../../Images/CaseNonCoche.png");
$objDrawingNonCoche->setWidth(30);
$objDrawingNonCoche->setHeight(30);
$objDrawingNonCoche->setOffsetX(-40);
$objDrawingNonCoche->setOffsetY(10);
$objDrawingNonCoche->setCoordinates('B11');
$objDrawingNonCoche->setWorksheet($sheet);


$lesEntretiens=$NbEntretien;
if($NbEntretien>0){
$lesEntretiens.="\n".$Entretiens;	
}

$sheet->setCellValue('D13',utf8_encode(stripslashes($lesEntretiens)));
$sheet->getStyle('D13')->getAlignment()->setWrapText(true);

$sheet->setCellValue('D18',utf8_encode(stripslashes($Obligatoire)));
$sheet->getStyle('D18')->getAlignment()->setWrapText(true);

$sheet->setCellValue('D24',utf8_encode(stripslashes($NonObligatoire)));
$sheet->getStyle('D24')->getAlignment()->setWrapText(true);

$sheet->setCellValue('D35',utf8_encode(stripslashes($evolutionSalariale)));
$sheet->getStyle('D35')->getAlignment()->setWrapText(true);

$sheet->setCellValue('D38',utf8_encode(stripslashes($evolutionPro)));
$sheet->getStyle('D38')->getAlignment()->setWrapText(true);

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="D-0705-014.xlsx"');}
else{header('Content-Disposition: attachment;filename="D-0705-014.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../tmp/D-0705-014.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
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


$annee=$_SESSION['FiltreEPEIndicateurs_Annee'];
$dateFin=date($annee.'-12-31');
$ladateDebut=date($annee.'-01-01');
$dateDebut=date('Y-01-01',strtotime(date($annee.'-m-d')." -6 year"));

$AnneeFin=$annee;
$AnneeDebut=date('Y',strtotime(date($annee.'-m-d')." -6 year"));

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
if($LangueAffichage=="FR"){
	$sheet->setTitle(utf8_encode("Entretiens realises"));
	
	$sheet->setCellValue('A1',utf8_encode("Matricule"));
	$sheet->setCellValue('B1',utf8_encode("Personne"));
	$sheet->setCellValue('C1',utf8_encode("Date d'embauche"));
	$sheet->setCellValue('D1',utf8_encode("Unité d'exploitation"));
	if($annee<=2021){
		$sheet->setCellValue('E1',utf8_encode("Moins d'1 EPP en 6 ans"));
	}
	else{
		$sheet->setCellValue('E1',utf8_encode("Moins de 2 EPP en 6 ans"));
	}
	$sheet->setCellValue('F1',utf8_encode("Pas d'action de formations"));
	$sheet->setCellValue('G1',utf8_encode("Mis en attente"));
}
else{
	$sheet->setTitle(utf8_encode("Interviews carried out"));
	
	$sheet->setCellValue('A1',utf8_encode("Registration number"));
	$sheet->setCellValue('B1',utf8_encode("People"));
	$sheet->setCellValue('C1',utf8_encode("Hiring date"));
	$sheet->setCellValue('D1',utf8_encode("Operating unit"));
	if($annee<=2021){
		$sheet->setCellValue('E1',utf8_encode("Less than 1 EPP in 6 years"));
	}
	else{
		$sheet->setCellValue('E1',utf8_encode("Less than 2 EPP in 6 years"));
	}
	$sheet->setCellValue('F1',utf8_encode("No training action"));
	$sheet->setCellValue('G1',utf8_encode("Put on hold"));
}

$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(35);
$sheet->getColumnDimension('F')->setWidth(35);

$sheet->getStyle('A1:G1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:G1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:G1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:G1')->getFont()->setBold(true);
$sheet->getStyle('A1:G1')->getFont()->getColor()->setRGB('1f49a6');


$req="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,DateAncienneteCDI,
(SELECT COUNT(Id) FROM epe_personne_attente WHERE Id_Personne=new_rh_etatcivil.Id AND Annee=".$annee." AND epe_personne_attente.TypeEntretien='EPP Bilan') AS EnAttente
FROM new_rh_etatcivil
WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01'  AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
OR 
	(SELECT COUNT(Id)
	FROM epe_personne 
	WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$AnneeDebut.")>0
)  
AND YEAR(DateAncienneteCDI)<='".$AnneeDebut."'
AND
(
	SELECT COUNT(new_competences_personne_prestation.Id)
	FROM new_competences_personne_prestation
	LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
	WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
	AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
	AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".$ladateDebut."')
	AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
	AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
	AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].")
)>0

AND 
(
	SELECT Id_Prestation
	FROM new_competences_personne_prestation
	LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
	WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
	AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
	AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".$ladateDebut."')
	AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
	ORDER BY Date_Fin DESC, Date_Debut DESC
	LIMIT 1
) NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
";
if($_SESSION['FiltreEPEIndicateurs_Personne']<>"0"){
	$req.="AND new_rh_etatcivil.Id=".$_SESSION['FiltreEPEIndicateurs_Personne']." ";
}
$req.="ORDER BY Personne ";
$result=mysqli_query($bdd,$req);
$nbenreg=mysqli_num_rows($result);

if($nbenreg>0){
	$ligne=1;
	while($row=mysqli_fetch_array($result)){
		$Id_Prestation=0;
		$Id_Pole=0;

		$PrestaPole=PrestationPoleCompetence_Personne(date('Y-m-d'),$row['Id']);
		$TableauPrestationPole=explode("_",$PrestaPole);
		if($PrestaPole<>0){
			$Id_Prestation=$TableauPrestationPole[0];
			$Id_Pole=$TableauPrestationPole[1];
		}

		
		$Plateforme="";
		$Presta="";
		$req="SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,LEFT(Libelle,7) AS Prestation FROM new_competences_prestation WHERE Id=".$Id_Prestation;
		$ResultPresta=mysqli_query($bdd,$req);
		$NbPrest=mysqli_num_rows($ResultPresta);
		if($NbPrest>0){
			$RowPresta=mysqli_fetch_array($ResultPresta);
			$Presta=$RowPresta['Prestation'];
			$Plateforme=$RowPresta['Plateforme'];
		}
		
		//Nb EPP en 6 ans 
		
		$req="SELECT Date_Reel 
		FROM new_competences_personne_rh_eia 
		WHERE Type='EPP' AND Id_Personne=".$row['Id']." 
		AND Date_Reel>'0001-01-01' 
		AND Date_Reel>='".$dateDebut."'
		AND Date_Reel<='".$dateFin."'
		UNION
		SELECT DateEntretien AS Date_Reel
		FROM epe_personne 
		WHERE Suppr=0 
		AND Type='EPP' 
		AND Id_Personne=".$row['Id']." 
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
		
		//Formations Obligatoires / Non obligatoires 
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
			form_besoin.Id_Personne=".$row['Id']."
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
			WHERE new_competences_personne_formation.Id_Personne=".$row['Id']." 
			AND new_competences_personne_formation.Date>='".$dateDebut."'
			AND new_competences_personne_formation.Date<='".$dateFin."'
			AND (SELECT Obligatoire FROM new_competences_formation WHERE new_competences_personne_formation.Id_Formation=new_competences_formation.Id)=1
			ORDER BY DateSession DESC, Type ASC, Libelle ASC ";

		$result2=mysqli_query($bdd,$req);
		$nbenreg=mysqli_num_rows($result2);
		if($nbenreg>0){
			$Obligatoire=$nbenreg;
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
				AND new_competences_relation.Id_Personne=".$row['Id']."
				AND new_competences_relation.Type='Qualification'
				AND IF(new_competences_relation.Date_Debut>'0001-01-01' ,new_competences_relation.Date_Debut,new_competences_relation.Date_QCM)>='".$dateDebut."'
				AND IF(new_competences_relation.Date_Debut>'0001-01-01' ,new_competences_relation.Date_Debut,new_competences_relation.Date_QCM)<='".$dateFin."'
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
			if($Obligatoire<>""){
				$Obligatoire+=$nbenreg;
			}
			else{
				$Obligatoire=$nbenreg;
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
			form_besoin.Id_Personne=".$row['Id']."
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
			WHERE new_competences_personne_formation.Id_Personne=".$row['Id']." 
			AND new_competences_personne_formation.Date>='".$dateDebut."'
			AND new_competences_personne_formation.Date<='".$dateFin."'
			AND (SELECT Obligatoire FROM new_competences_formation WHERE new_competences_personne_formation.Id_Formation=new_competences_formation.Id)=-1
			ORDER BY DateSession DESC, Type ASC, Libelle ASC ";
		$result2=mysqli_query($bdd,$req);
		$nbenreg=mysqli_num_rows($result2);
		if($nbenreg>0){
			$NonObligatoire=$nbenreg;
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
				AND new_competences_relation.Id_Personne=".$row['Id']."
				AND new_competences_relation.Type='Qualification'
				AND IF(new_competences_relation.Date_Debut>'0001-01-01' ,new_competences_relation.Date_Debut,new_competences_relation.Date_QCM)>='".$dateDebut."'
				AND IF(new_competences_relation.Date_Debut>'0001-01-01' ,new_competences_relation.Date_Debut,new_competences_relation.Date_QCM)<='".$dateFin."'
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
			if($NonObligatoire<>""){
				$NonObligatoire+=$nbenreg;
			}
			else{
				$NonObligatoire=$nbenreg;
			}
		}
		
		//Nb EPP en 6 ans 
		$req="SELECT DateEntretien AS Date_Reel
		FROM epe_personne 
		WHERE Suppr=0 
		AND Type='EPP Bilan' 
		AND Id_Personne=".$row['Id']." 
		AND DateEntretien>='".$dateDebut."'
		AND DateEntretien<'".$ladateDebut."'
		ORDER BY Date_Reel DESC
		";

		$resultEPPB=mysqli_query($bdd,$req);
		$nbEPPB=mysqli_num_rows($resultEPPB);
		
		if($nbEPPB==0 && (($annee<=2021 && ($NbEntretien==0 || ($Obligatoire=="" && $NonObligatoire==""))) || ($annee>2021 && ($NbEntretien<=1 || ($NonObligatoire==""))))){
			$ligne++;
			
			$sheet->setCellValue('A'.$ligne,utf8_encode($row['MatriculeAAA']));
			$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['Personne'])));
			if($row['DateAncienneteCDI']>'0001-01-01' ){
				$date = explode("-",$row['DateAncienneteCDI']);
				$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
				$sheet->setCellValue('C'.$ligne,$time);
				$sheet->getStyle('C'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
			}
			$sheet->setCellValue('D'.$ligne,utf8_encode($Plateforme));
			
			$lesEntretiens="";
			if($NbEntretien>0){
				$lesEntretiens.= "\n";
				$lesEntretiens.= $Entretiens;
			}
			$sheet->setCellValue('E'.$ligne,utf8_encode($NbEntretien.$lesEntretiens));
			$sheet->getStyle('E'.$ligne)->getAlignment()->setWrapText(true);
			
			if($annee<=2021){
				$lesONO="";
				if($Obligatoire<>""){
					$lesONO.= "Obligatoires : ";
					$lesONO.= $Obligatoire."\n";
				}
				if($NonObligatoire<>""){
					$lesONO.= "Non obligatoires : ";
					$lesONO.= $NonObligatoire;
				}
			}
			else{
				$lesONO="";
				if($NonObligatoire<>""){
					$lesONO.= "Non obligatoires : ";
					$lesONO.= $NonObligatoire;
				}

			}
			$sheet->setCellValue('F'.$ligne,utf8_encode($lesONO));
			$sheet->getStyle('F'.$ligne)->getAlignment()->setWrapText(true);
			if($row['EnAttente']>0){
				$sheet->setCellValue('G'.$ligne,utf8_encode("X"));
			}
			
			
			$sheet->getStyle('A'.$ligne.':G'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligne.':G'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheet->getStyle('A'.$ligne.':G'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			
		}
	}
}
										
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="Extract.xlsx"');}
else{header('Content-Disposition: attachment;filename="Extract.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
$chemin = '../../tmp/Extract.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
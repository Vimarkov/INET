<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script type="text/javascript" src="../JS/mask.js"></script>
	<script type="text/javascript" src="../JS/js/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript" src="../JS/bootstrap.min.js"></script>
    <script type="text/javascript" src="../JS/prettify.js"></script>
    <script type="text/javascript" src="../JS/bootstrap-timepicker.js"></script>
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script type="text/javascript">
	function VerifChamps(){
		if(document.getElementById('RefusSalarie').checked == false){
			if(document.getElementById("NbEntretien").value==''){alert("Veuillez renseigner le nombre d'entretiens professionnels réalisés");return false;}
		}
		var Confirm=false;
		Confirm=window.confirm('Attention, aucune modification ne sera possible. Etes-vous sur de vouloir valider ? ');
		if(Confirm==false){
			return false;
		}
	}
	function FermerEtRecharger()
	{
		window.opener.location="Liste_EPE.php";
		window.close();
	}
	</script>
</head>


<?php
require_once("../Connexioni.php");
require("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

Ecrire_Code_JS_Init_Date();

$DateJour=date("Y-m-d");
$bEnregistrement=false;
if($_POST){
	if(isset($_POST['btnEnregistrer'])){
		
		$requete="SELECT new_rh_etatcivil.Id, Nom, Prenom,MatriculeAAA,DateAncienneteCDI,IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir) AS DateButoir,
			MetierPaie AS Metier
			FROM epe_personne_datebutoir
			LEFT JOIN new_rh_etatcivil
			ON epe_personne_datebutoir.Id_Personne=new_rh_etatcivil.Id
			WHERE epe_personne_datebutoir.Id=".$_POST['Id'];
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

		$req="SELECT Id_Prestation, Id_Pole FROM epe_personne_prestation WHERE Id_Personne=".$rowEPE['Id']." AND Suppr=0 AND Annee=".$_SESSION['FiltreEPE_Annee']." ";
		$ResultlaPresta=mysqli_query($bdd,$req);
		$NblaPresta=mysqli_num_rows($ResultlaPresta);
		if($NblaPresta>0){
			$RowlaPresta=mysqli_fetch_array($ResultlaPresta);
			$Id_Prestation=$RowlaPresta['Id_Prestation'];
			$Id_Pole=$RowlaPresta['Id_Pole'];
		}
		
		$Id_Plateforme=0;
		$req="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation;
		$ResultPresta=mysqli_query($bdd,$req);
		$NbPrest=mysqli_num_rows($ResultPresta);
		if($NbPrest>0){
			$RowPresta=mysqli_fetch_array($ResultPresta);
			$Id_Plateforme=$RowPresta['Id_Plateforme'];
		}

		$MetierManager="";
		$req="SELECT MetierPaie AS Metier
				FROM new_rh_etatcivil
				WHERE Id=".$_SESSION['Id_Personne'];
		$ResultManager=mysqli_query($bdd,$req);
		$NbManager=mysqli_num_rows($ResultManager);
		if($NbManager>0){
			$RowManager=mysqli_fetch_array($ResultManager);
			$MetierManager=$RowManager['Metier'];
		}
		
		$RefusSalarie=0;
		if(isset($_POST['RefusSalarie'])){$RefusSalarie=1;}
		
		$NbEntretiens=0;
		if($_POST['leNbEntretien']<>""){
			$NbEntretiens=$_POST['leNbEntretien'];
		}
		//Création d'un EPP Bilan
		$req="INSERT INTO epe_personne (Type,Id_Personne,Id_Prestation,Id_Pole,DateCreation,Id_Createur,Metier,DateAnciennete,DateEntretien,DateButoir,Id_Evaluateur,MetierManager,Id_Plateforme,
			EPPBilan,EPPBilanRefuseSalarie,NbEntretienPro,ComNbEntretiensPro,ActionFormationOEPPBilan,ActionFormationNonOEPPBilan,CertifParFormation,EvolutionSalariale,EvolutionPro) 
			VALUES 
				('EPP Bilan',".$rowEPE['Id'].",".$Id_Prestation.",".$Id_Pole.",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].",'".addslashes($rowEPE['Metier'])."','".$rowEPE['DateAncienneteCDI']."','".date('Y-m-d')."','".$rowEPE['DateButoir']."',
				".$_SESSION['Id_Personne'].",'".addslashes($MetierManager)."',".$Id_Plateforme.",1,".$RefusSalarie.",".$NbEntretiens.",'".addslashes($_POST['NbEntretien'])."','".addslashes($_POST['actionFormationO'])."',
				'".addslashes($_POST['actionFormationNO'])."','".addslashes($_POST['VAE'])."','".addslashes($_POST['salaire'])."','".addslashes($_POST['evolutionPro'])."')";
		
		$resultAjout=mysqli_query($bdd,$req);
		$IdCree = mysqli_insert_id($bdd);
		
		echo "<script>FermerEtRecharger();</script>";
	}
}

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
$Id_Plateforme=0;
$req="SELECT LEFT(Libelle,7) AS Prestation,(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,Id_Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation;
$ResultPresta=mysqli_query($bdd,$req);
$NbPrest=mysqli_num_rows($ResultPresta);
if($NbPrest>0){
	$RowPresta=mysqli_fetch_array($ResultPresta);
	$Presta=$RowPresta['Prestation'];
	$Plateforme=$RowPresta['Plateforme'];
	$Id_Plateforme=$RowPresta['Id_Plateforme'];
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
		AND IF(new_competences_relation.Date_Debut>'0001-01-01' ,new_competences_relation.Date_Debut,new_competences_relation.Date_QCM) >='".$dateDebut."'
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

$requete="SELECT Id,IF((SELECT COUNT(Id)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir) <= ".($rowEPE['Annee']-1)."  ORDER BY DateCreation DESC)>0,
			(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01' ,'Réalisé','Signature manager')))
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir) <= ".($rowEPE['Annee']-1)."  ORDER BY DateCreation DESC),
			'A faire')
			AS Etat,
			IF((SELECT COUNT(Id)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir) <= ".($rowEPE['Annee']-1)."  ORDER BY DateCreation DESC)>0,
			(SELECT YEAR(epe_personne.DateButoir)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir) <= ".($rowEPE['Annee']-1)."  ORDER BY DateCreation DESC),
			'A faire')
			AS Annee
			FROM epe_personne_datebutoir
			WHERE Id_Personne=".$rowEPE['Id']."
			AND TypeEntretien='EPP Bilan'
			AND YEAR(IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir))<=".($rowEPE['Annee']-1)." 
		ORDER BY IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir) DESC
		";
$result_1=mysqli_query($bdd,$requete);
$Nb_1=mysqli_num_rows($result_1);
?>

<form id="formulaire" class="test" action="Ajout_EPPBilan.php" method="post" onsubmit=" return VerifChamps();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
	<?php 
		if($Nb_1>0){
			$rowEPE_1=mysqli_fetch_array($result_1);
			if($rowEPE_1['Etat']=="Réalisé"){
				echo "<tr><td class='Libelle' align='right'>EPP Bilan ".$rowEPE_1['Annee']." :";
	?>
		<a class="Modif" href="javascript:EPPBilan_PDF(<?php echo $rowEPE_1['Id']; ?>);">
			<img src='../../Images/pdf.png' border='0' alt='PDF' width='14'>
		</a>&nbsp;&nbsp;&nbsp;&nbsp;
	<?php
				echo "</td></tr>";
			}
		}
	?>
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing:0; background-color:#1a0078;">
				<tr>
					<td class="TitrePage" align="center" style="color:#ffffff;">
						Etat des lieux récapitulatif du parcours professionnel<br>Bilan à 6 ans
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule";}else{echo "Registration number";} ?></td>
							<td width="30%"><?php echo $rowEPE['MatriculeAAA']; ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Date de l'entretien";}else{echo "Interview date";} ?></td>
							<td width="30%"><?php echo date('d/m/Y'); ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Nom";}else{echo "Name";} ?></td>
							<td width="30%"><?php echo $rowEPE['Nom']; ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";} ?></td>
							<td width="30%"><?php echo $Plateforme; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Prénom";}else{echo "First name";} ?></td>
							<td width="30%"><?php echo $rowEPE['Prenom']; ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Evaluateur";}else{echo "Evaluator";} ?></td>
							<td width="30%"><?php echo $Manager; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Fonction/métier";}else{echo "Function / profession";} ?></td>
							<td width="30%"><?php echo $rowEPE['Metier']; ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule";}else{echo "Registration number";} ?></td>
							<td width="30%"><?php echo $MatriculeAAAManager; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Date d'embauche";}else{echo "Hiring date";} ?></td>
							<td width="30%"><?php echo AfficheDateJJ_MM_AAAA($rowEPE['DateAncienneteCDI']); ?></td>
							<td width="20%" class="Libelle2" bgcolor="#d8d8d4"><?php if($_SESSION["Langue"]=="FR"){echo "Fonction /métier";}else{echo "Function / profession";} ?></td>
							<td width="30%"><?php echo $MetierManager; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#00a431" style="color:#ffffff;" align="center">
			1.Bilan - Cadre de l'entretien
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr>
							<td width="5%" class="Libelle2"><input type='checkbox' class="RefusSalarie" name="RefusSalarie" id="RefusSalarie" value="1">Le salarié ne souhaite pas bénéficier de l'entretien professionnel proposé</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#00a431" style="color:#ffffff;" align="center">
			2 .Bilan des EPP
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td width="25%" class="Libelle2">NOMBRE D'ENTRETIENS PROFESSIONNELS PERIODIQUES REALISES <span style="font-size:12px;"><br>(date) au cours des 6 dernières années y compris celui réalisé en même temps que le bilan</span></td>
							<td width="3%" class="Libelle2" align="center" valign="center"><input size="6" onKeyUp="nombre(this)" name="leNbEntretien" id="leNbEntretien" value="<?php echo $NbEntretien; ?>" /></td>
							<td width="60%" class="Libelle2" align="center" valign="center"><textarea name="NbEntretien" id="NbEntretien" cols="100" rows="3" noresize="noresize"><?php echo $Entretiens; ?></textarea></td>
						</tr>
						<tr><td height="4"></td></tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#00a431" style="color:#ffffff;" align="center">
			3 .Bilan des Formations
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr bgcolor="#1a0078">
							<td class="Libelle2" colspan="4" align="center" style="color:#ffffff;" >ACTIONS DE FORMATION REALISEES</td>
						</tr>
						<tr>
							<td width="20%" class="Libelle2">ACTIONS DE FORMATION OBLIGATOIRES REALISEES<span style="font-size:12px;"><br>(Date et intitulé)<br>C’est-à-dire qui conditionne l’exercice d’une activité ou d’une fonction en application d’une convention internationale ou de dispositions légales et réglementaires</span></td>
							<td width="30%" class="Libelle2" align="center"><textarea name="actionFormationO" id="actionFormationO" cols="120" rows="6" noresize="noresize"><?php echo stripslashes($Obligatoire); ?></textarea></td>
						</tr>
						<tr>
							<td height="15px"></td>
						</tr>
						<tr>
							<td width="20%" class="Libelle2">ACTIONS DE FORMATION NON OBLIGATOIRES REALISEES<span style="font-size:12px;"><br>(Date et intitulé)<br>C’est-à-dire autre qu’une action de formation qui conditionne l’exercice d’une activité ou d’une fonction en application d’une convention internationale ou de dispositions légales et réglementaires</span></td>
							<td width="30%" class="Libelle2" align="center"><textarea name="actionFormationNO" id="actionFormationNO" cols="120" rows="6" noresize="noresize"><?php echo stripslashes($NonObligatoire); ?></textarea></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr bgcolor="#1a0078">
							<td class="Libelle2" colspan="4" align="center" style="color:#ffffff;" >ELEMENTS DE CERTIFICATION OBTENUS</td>
						</tr>
						<tr>
							<td width="20%" class="Libelle2">PAR LA FORMATION  ou la VAE<span style="font-size:12px;"><br>(Date et intitulé)</span></td>
							<td width="30%" class="Libelle2" align="center"><textarea name="VAE" id="VAE" cols="120" rows="3" noresize="noresize"></textarea></td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td bgcolor="#00a431" style="color:#ffffff;" align="center">
			4 .Bilan - Progression Salariale ou Professionnelle
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td width="20%" class="Libelle2">Evolution salariale (année)<span style="font-size:12px;"><br>(Date et intitulé)<br>Augmentation individuelle ou Générale</span></td>
							<td width="30%" class="Libelle2" align="center"><textarea name="salaire" id="salaire" cols="120" rows="3" noresize="noresize"><?php echo stripslashes($evolutionSalariale); ?></textarea></td>
						</tr>
						<tr>
							<td height="15px"></td>
						</tr>
						<tr>
							<td width="20%" class="Libelle2">Evolution professionnelle (année)<span style="font-size:12px;"><br>Changement de métier, progression en terme de responsabilités, changement de classification, etc.</span></td>
							<td width="30%" class="Libelle2" align="center"><textarea name="evolutionPro" id="evolutionPro" cols="120" rows="3" noresize="noresize"><?php echo stripslashes($evolutionPro); ?></textarea></td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td colspan="6" align="center">
			<?php if($_GET['Id_Manager']==$_SESSION['Id_Personne'] || $_SESSION['FiltreEPE_AffichageBackup']<>"" || DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH)) || DroitsFormation1Plateforme($Id_Plateforme,array($IdPosteAssistantRH.",".$IdPosteResponsableRH))){ ?>
			<input class="Bouton" name="btnEnregistrer" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Valider";}else{echo "Validate";} ?>"/>
			<?php } ?>
		</td>
	</tr>
	</tr>
</table>
</form>
</body>
</html>
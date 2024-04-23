<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Fonctions.php");
require_once("Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
						
//Ligne En-tete
if($_SESSION['Langue']=="FR"){
	$sheet->setCellValue('A1',utf8_encode('N° AAA'));
	$sheet->setCellValue('B1',utf8_encode('S/N'));
	$sheet->setCellValue('C1',utf8_encode('Type'));
	$sheet->setCellValue('D1',utf8_encode('Famille'));
	$sheet->setCellValue('E1',utf8_encode('Modèle'));
	$sheet->setCellValue('F1',utf8_encode('N°'));
	$sheet->setCellValue('G1',utf8_encode('Réception'));
	$sheet->setCellValue('H1',utf8_encode('Fournisseur'));
	$sheet->setCellValue('I1',utf8_encode('Fabricant'));
	$sheet->setCellValue('J1',utf8_encode('Prestation'));
	$sheet->setCellValue('K1',utf8_encode('Lieu'));
	$sheet->setCellValue('L1',utf8_encode('Caisse'));
	$sheet->setCellValue('M1',utf8_encode('Personne'));
	$sheet->setCellValue('N1',utf8_encode('Date d\'affectation'));
	$sheet->setCellValue('O1',utf8_encode('Remarque'));
	$sheet->setCellValue('P1',utf8_encode('Date vérification'));
	$sheet->setCellValue('Q1',utf8_encode('Périodicité'));
	$sheet->setCellValue('R1',utf8_encode('Date fin validité'));
	$sheet->setCellValue('S1',utf8_encode('Prix'));
	$sheet->setCellValue('T1',utf8_encode('Désignation'));
	$sheet->setCellValue('U1',utf8_encode('PN'));
	$sheet->setCellValue('V1',utf8_encode('Type ECME'));
	
	if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
		$sheet->setCellValue('W1',utf8_encode('Affectation OPTEA'));
	}
}
else{
	$sheet->setCellValue('A1',utf8_encode('N° AAA'));
	$sheet->setCellValue('B1',utf8_encode('S/N'));
	$sheet->setCellValue('C1',utf8_encode('Type'));
	$sheet->setCellValue('D1',utf8_encode('Family'));
	$sheet->setCellValue('E1',utf8_encode('Material'));
	$sheet->setCellValue('F1',utf8_encode('N°'));
	$sheet->setCellValue('G1',utf8_encode('Reception'));
	$sheet->setCellValue('H1',utf8_encode('Provider'));
	$sheet->setCellValue('I1',utf8_encode('Manufacturer'));
	$sheet->setCellValue('J1',utf8_encode('Site'));
	$sheet->setCellValue('K1',utf8_encode('Place'));
	$sheet->setCellValue('L1',utf8_encode('Toolbox'));
	$sheet->setCellValue('M1',utf8_encode('Person'));
	$sheet->setCellValue('N1',utf8_encode('Date of assignment'));
	$sheet->setCellValue('O1',utf8_encode('Note'));
	$sheet->setCellValue('P1',utf8_encode('Check date'));
	$sheet->setCellValue('Q1',utf8_encode('Periodicity'));
	$sheet->setCellValue('R1',utf8_encode('End of validity date'));
	$sheet->setCellValue('S1',utf8_encode('Price'));
	$sheet->setCellValue('T1',utf8_encode('Designation'));
	$sheet->setCellValue('U1',utf8_encode('PN'));
	$sheet->setCellValue('V1',utf8_encode('ECME type'));
	if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
		$sheet->setCellValue('W1',utf8_encode('Assignment in OPTEA'));
	}
}
if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
	$sheet->getStyle('A1:W1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));
}
else{
	$sheet->getStyle('A1:V1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));
}
$sheet->getDefaultColumnDimension()->setWidth(20);

$requeteSite="SELECT CONCAT(Id_Prestation,'_',Id_Pole) AS Id,
	(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme
	FROM new_competences_personne_poste_prestation 
	WHERE Id_Personne=".$_SESSION["Id_Personne"]."
	AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.") ";
if($_SESSION['FiltreToolsSuivi_Plateforme']>0){
	$requeteSite.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=".$_SESSION['FiltreToolsSuivi_Plateforme']." ";
}
	$requeteSite.=" UNION 
	SELECT CONCAT(Id_Prestation,'_',Id_Pole) AS Id,
	(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme
	FROM new_competences_personne_prestation
	WHERE new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
	AND Id_Personne=".$_SESSION["Id_Personne"]."
	AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
	AND Id_Personne IN (
		SELECT new_competences_personne_metier.Id_Personne 
		FROM new_competences_personne_metier
		WHERE Futur=0
		AND Id_Personne=".$_SESSION["Id_Personne"]."
		AND Id_Metier=85)
";
if($_SESSION['FiltreToolsSuivi_Plateforme']>0){
	$requeteSite.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=".$_SESSION['FiltreToolsSuivi_Plateforme']." ";
}

$resultPrestation=mysqli_query($bdd,$requeteSite);
$nbPrestation=mysqli_num_rows($resultPrestation);

$PrestationSelect = 0;
$Selected = "";

$listePrestaPole="('-1_-1')";
$nbPrestation=mysqli_num_rows($resultPrestation);
if($nbPrestation){
	$listePrestaPole="(";
	while($RowListe=mysqli_fetch_array($resultPrestation)){
		if($listePrestaPole<>"("){$listePrestaPole.=",";}
		$listePrestaPole.="'".$RowListe['Id']."'";
	}
	$listePrestaPole.=")";
}

$req="(SELECT Id_Plateforme
		FROM new_competences_personne_poste_plateforme
		WHERE Id_Personne=".$_SESSION["Id_Personne"]."
		AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableQualite.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteResponsablePlateforme.",".$IdPosteControleGestion.",".$IdPosteDirection.") 
	) ";
$Result=mysqli_query($bdd,$req);

$listePlateforme="(-1)";
$NbEnreg=mysqli_num_rows($Result);
if($NbEnreg){
	$listePlateforme="(";
	while($RowListe=mysqli_fetch_array($Result)){
		if($listePlateforme<>"("){$listePlateforme.=",";}
		$listePlateforme.="".$RowListe['Id_Plateforme']."";
	}
	$listePlateforme.=")";
}

$req="
	(SELECT CONCAT(Id_Prestation,'_',Id_Pole) AS Id
	FROM new_competences_personne_prestation
	WHERE new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
	AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
	AND Id_Personne IN (
		SELECT new_competences_personne_metier.Id_Personne 
		FROM new_competences_personne_metier
		WHERE Futur=0 
		AND Id_Metier=85)
	)
";
$ResultIQ=mysqli_query($bdd,$req);
$NbIQ=mysqli_num_rows($ResultIQ);

	//PARTIE OUTILS DE LA REQUETE
	$Requete2="

		SELECT 
			TAB_MATERIEL.ID,
			TAB_MATERIEL.TYPESELECT,
			TAB_MATERIEL.NumAAA,
			TAB_MATERIEL.NumFicheImmo,
			TAB_MATERIEL.SN,
			TAB_MATERIEL.PN,
			TAB_MATERIEL.TypeECME,
			TAB_MATERIEL.Num,
			TAB_MATERIEL.Designation,
			TAB_MATERIEL.ID_TYPEMATERIEL,
			TAB_MATERIEL.TYPEMATERIEL,
			TAB_MATERIEL.FAMILLEMATERIEL,
			TAB_MATERIEL.LIBELLE_MODELEMATERIEL,
			TAB_MATERIEL.LIBELLE_FOURNISSEUR,
			TAB_MATERIEL.LIBELLE_FABRICANT,
			TAB_MATERIEL.DateDerniereVerification,
			TAB_MATERIEL.PeriodiciteVerification,
			TAB_MATERIEL.Prix,
			DATE_ADD(TAB_MATERIEL.DateDerniereVerification, INTERVAL TAB_MATERIEL.PeriodiciteVerification MONTH) AS DateFinValidite,
			(SELECT TAB_Mouvement.DateReception 
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB_MATERIEL.ID
				ORDER BY TAB_Mouvement.DateReception ASC, TAB_Mouvement.Id ASC LIMIT 1
			) AS DateReception,
			SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.8.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Remarque,
			SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5)) AS DateDerniereAffectation,
			SUBSTRING(TAB_MATERIEL.AffectationMouvement,1,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)-1) AS TransfertEC,
			(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5))) AS LIBELLE_PLATEFORME,
			(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5))) AS Id_Plateforme,
			(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_PRESTATION,
			SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Prestation,
			(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_POLE,
			SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Pole,
			(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_LIEU,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5))) AS NOMPRENOM_PERSONNE,
			SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Personne,
			SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Caisse,
			(SELECT (SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caissetype WHERE tools_caisse.Id_CaisseType=tools_caissetype.Id)
			FROM tools_caisse WHERE Id=SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)+5))) AS LIBELLE_CAISSETYPE,
			(SELECT (SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation)
				FROM rh_personne_mouvement
				WHERE Suppr=0
				AND rh_personne_mouvement.Id_Personne=SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5)) 
				AND rh_personne_mouvement.EtatValidation IN (0,1)
				AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS LIBELLE_NOUVELLEPRESTATION,
			(SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole)
				FROM rh_personne_mouvement
				WHERE Suppr=0
				AND rh_personne_mouvement.Id_Personne=SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5))  
				AND rh_personne_mouvement.EtatValidation IN (0,1)
				AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS LIBELLE_NOUVEAUPOLE

		FROM 
		(
		SELECT
			tools_materiel.Id AS ID,
			'Outils' AS TYPESELECT,
			NumAAA,
			NumFicheImmo,
			SN,
			PN,
			TypeECME,
			IF(tools_famillemateriel.Id_TypeMateriel=".$TypeTelephone.",NumTelephone,
				IF(tools_famillemateriel.Id_TypeMateriel=".$TypeClef.",NumClef,
					IF(tools_famillemateriel.Id_TypeMateriel=".$TypeMaqueDeControle.",NumMC,
						IF(tools_famillemateriel.Id_TypeMateriel=".$TypeInformatique.",NumPC,
							IF(tools_famillemateriel.Id_TypeMateriel=".$TypeVehicule.",Immatriculation,
								IF(tools_famillemateriel.Id_TypeMateriel=".$TypeMacaron.",ImmatriculationAssociee,'')
							)
						)
					)
				)
			) AS Num,
			DateDerniereVerification,
			PeriodiciteVerification,
			Designation,
			Prix,
			tools_famillemateriel.Id_TypeMateriel AS ID_TYPEMATERIEL,
			(SELECT Libelle FROM tools_typemateriel WHERE tools_typemateriel.Id=tools_famillemateriel.Id_TypeMateriel) AS TYPEMATERIEL,
			tools_famillemateriel.Libelle AS FAMILLEMATERIEL,
			tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
			(SELECT IF(TAB_Mouvement.Id_Caisse=0,
						CONCAT(TAB_Mouvement.EtatValidation,'|.1.|',TAB_Mouvement.Id_Prestation,'|.2.|',TAB_Mouvement.Id_Pole,'|.3.|',TAB_Mouvement.Id_Lieu,'|.4.|',TAB_Mouvement.Id_Personne,'|.5.|',TAB_Mouvement.Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|'),
						(
						SELECT CONCAT(EtatValidation,'|.1.|',Id_Prestation,'|.2.|',Id_Pole,'|.3.|',Id_Lieu,'|.4.|',Id_Personne,'|.5.|',Id_Materiel__Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|')
						FROM tools_mouvement
						WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						)
				)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
			) AS AffectationMouvement,
			tools_materiel.Remarques,
			(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fabricant AND Type=1) AS LIBELLE_FABRICANT,
			(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fournisseur AND Type=2) AS LIBELLE_FOURNISSEUR,
			NumSIM,
			NumIMEI
			";
	$Requete="FROM
				tools_materiel
			LEFT JOIN
				tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
			LEFT JOIN
				tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id 
			WHERE tools_materiel.Suppr=0
				";
	
	if($_SESSION['FiltreToolsSuivi_TypeMateriel']<>"0"){$Requete.=" AND (SELECT (SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = ".$_SESSION['FiltreToolsSuivi_TypeMateriel']." ";}
	if($_SESSION['FiltreToolsSuivi_FamilleMateriel']<>"0"){$Requete.=" AND (SELECT Id_FamilleMateriel FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = ".$_SESSION['FiltreToolsSuivi_FamilleMateriel']." ";}
	if($_SESSION['FiltreToolsSuivi_TypeMateriel']<>"-1"){if($_SESSION['FiltreToolsSuivi_ModeleMateriel']<>"0"){$Requete.=" AND Id_ModeleMateriel = ".$_SESSION['FiltreToolsSuivi_ModeleMateriel']." ";}}
	else{if($_SESSION['FiltreToolsSuivi_ModeleMateriel']<>"0"){$Requete.=" AND Id_ModeleMateriel = 0 ";}}
	if($_SESSION['FiltreToolsSuivi_NumAAA']<>""){$Requete.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsSuivi_NumAAA']."%' ";}
	if($_SESSION['FiltreToolsSuivi_NumFicheImmo']<>""){$Requete.=" AND NumFicheImmo LIKE '%".$_SESSION['FiltreToolsSuivi_NumFicheImmo']."%' ";}
	if($_SESSION['FiltreToolsSuivi_Designation']<>""){$Requete.=" AND Designation LIKE '%".$_SESSION['FiltreToolsSuivi_Designation']."%' ";}
	if($_SESSION['FiltreToolsSuivi_BonCommande']<>""){$Requete.=" AND BonCommande LIKE '%".$_SESSION['FiltreToolsSuivi_BonCommande']."%' ";}
		
	$Requete.="  ) AS TAB_MATERIEL 
	
	WHERE TAB_MATERIEL.ID>0 
			 ";
		
		if($_SESSION['FiltreToolsSuivi_Num']<>""){
			$Requete.=" AND (Num
				LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%' 
				OR 
				SN LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%' 
				)";
		}

		if($_SESSION['FiltreToolsSuivi_Caisse']<>"0"){$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)+5)) = ".$_SESSION['FiltreToolsSuivi_Caisse']." ";}
		if($_SESSION['FiltreToolsSuivi_Personne']<>"0"){$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5) = ".$_SESSION['FiltreToolsSuivi_Personne']." ";
		}
		if($_SESSION['FiltreToolsSuivi_Plateforme']<>"0")
		{
			$Requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) = ".$_SESSION['FiltreToolsSuivi_Plateforme']." ";
		}
		if($_SESSION['FiltreToolsSuivi_Prestation']<>"0")
		{
			$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) = ".$_SESSION['FiltreToolsSuivi_Prestation']." ";
			if($_SESSION['FiltreToolsSuivi_Pole']<>"0"){$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) = ".$_SESSION['FiltreToolsSuivi_Pole']." ";}
			if($_SESSION['FiltreToolsSuivi_Lieu']<>"0"){$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5) = ".$_SESSION['FiltreToolsSuivi_Lieu']." ";}
		}

		if($_SESSION['FiltreToolsSuivi_DateAffectation']<>""){$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5)) ".$_SESSION['FiltreToolsSuivi_TypeDateAffectation']." '".$_SESSION['FiltreToolsSuivi_DateAffectation']."' ";}
		
		if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableQualite,$IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteResponsablePlateforme,$IdPosteControleGestion,$IdPosteDirection)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation) || DroitsFormationPrestation(array($IdPosteMagasinier)) || DroitsFormationPrestation(array($IdPosteReferentQualiteProduit)) || $NbIQ>0){
		
		}
		else{
			$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5) = ".$IdPersonneConnectee." ";
		}
		if($_SESSION['FiltreToolsSuivi_Remarque']<>""){$Requete.=" AND (SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.8.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)+5)) LIKE \"%".$_SESSION['FiltreToolsSuivi_Remarque']."%\" 
		OR TAB_MATERIEL.Remarques LIKE \"%".$_SESSION['FiltreToolsSuivi_Remarque']."%\"
		OR TAB_MATERIEL.NumSIM LIKE \"%".$_SESSION['FiltreToolsSuivi_Remarque']."%\"
		OR TAB_MATERIEL.NumIMEI LIKE \"%".$_SESSION['FiltreToolsSuivi_Remarque']."%\"
		)";}
		
		//PARTIE CAISSE DE LA REQUETE
		$Requete2Caisse="UNION ALL
			SELECT 
				TAB_MATERIEL.ID,
				TAB_MATERIEL.TYPESELECT,
				TAB_MATERIEL.NumAAA,
				TAB_MATERIEL.NumFicheImmo,
				TAB_MATERIEL.SN,
				TAB_MATERIEL.PN,
				TAB_MATERIEL.TypeECME,
				TAB_MATERIEL.Num,
				TAB_MATERIEL.Designation,
				TAB_MATERIEL.ID_TYPEMATERIEL,
				TAB_MATERIEL.TYPEMATERIEL,
				TAB_MATERIEL.FAMILLEMATERIEL,
				TAB_MATERIEL.LIBELLE_MODELEMATERIEL,
				TAB_MATERIEL.LIBELLE_FOURNISSEUR,
				TAB_MATERIEL.LIBELLE_FABRICANT,
				TAB_MATERIEL.DateDerniereVerification,
				TAB_MATERIEL.PeriodiciteVerification,
				TAB_MATERIEL.Prix,
				DATE_ADD(TAB_MATERIEL.DateDerniereVerification, INTERVAL TAB_MATERIEL.PeriodiciteVerification MONTH) AS DateFinValidite,
				(SELECT TAB_Mouvement.DateReception 
					FROM tools_mouvement AS TAB_Mouvement
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=TAB_MATERIEL.ID
					ORDER BY TAB_Mouvement.DateReception ASC, TAB_Mouvement.Id ASC LIMIT 1
				) AS DateReception,
				SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.8.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Remarque,
				SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5)) AS DateDerniereAffectation,
				SUBSTRING(TAB_MATERIEL.AffectationMouvement,1,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)-1) AS TransfertEC,
				(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_PLATEFORME,
				(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS Id_Plateforme,
				(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_PRESTATION,
				SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Prestation,
				(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_POLE,
				SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Pole,
				(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_LIEU,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5))) AS NOMPRENOM_PERSONNE,
				SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Personne,
				TAB_MATERIEL.ID AS Id_Caisse,
				(SELECT (SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caissetype WHERE tools_caisse.Id_CaisseType=tools_caissetype.Id)
				FROM tools_caisse WHERE Id=TAB_MATERIEL.ID) AS LIBELLE_CAISSETYPE,
				(SELECT (SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation)
					FROM rh_personne_mouvement
					WHERE Suppr=0
					AND rh_personne_mouvement.Id_Personne=SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5)) 
					AND rh_personne_mouvement.EtatValidation IN (0,1)
					AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
					AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS LIBELLE_NOUVELLEPRESTATION,
				(SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole)
					FROM rh_personne_mouvement
					WHERE Suppr=0
					AND rh_personne_mouvement.Id_Personne=SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5))  
					AND rh_personne_mouvement.EtatValidation IN (0,1)
					AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
					AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS LIBELLE_NOUVEAUPOLE
			FROM (
				SELECT Id AS ID,
				'Caisse' AS TYPESELECT,
				NumAAA AS NumAAA,
				NumFicheImmo,
				SN AS SN,
				'' AS PN,
				'' AS TypeECME,
				Num AS Num,
				Prix,
				'0001-01-01' AS DateDerniereVerification,
				0 AS PeriodiciteVerification,
				'' AS Designation,
				-1 AS Id_TYPEMATERIEL,
				'Caisse' AS TYPEMATERIEL,
				(SELECT Libelle FROM tools_famillemateriel WHERE Id=Id_FamilleMateriel) AS FAMILLEMATERIEL,
				(SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType) AS LIBELLE_MODELEMATERIEL,
				(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fabricant AND Type=1) AS LIBELLE_FABRICANT,
				(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fournisseur AND Type=2) AS LIBELLE_FOURNISSEUR,
				(
					SELECT CONCAT(EtatValidation,'|.1.|',Id_Prestation,'|.2.|',Id_Pole,'|.3.|',Id_Lieu,'|.4.|',Id_Personne,'|.5.|',Id_Materiel__Id_Caisse,'|.6.|',DateReception,'|.7.|',Commentaire,'|.8.|') 
					FROM tools_mouvement 
					WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id 
					ORDER BY DateReception DESC, tools_mouvement.Id DESC LIMIT 1
				) AS AffectationMouvement
			";
		$RequeteCaisse="FROM
			tools_caisse
		WHERE 
			tools_caisse.Suppr=0 ";
		if($_SESSION['FiltreToolsSuivi_TypeMateriel']>0){$RequeteCaisse.=" AND Id=0 ";}
		if($_SESSION['FiltreToolsSuivi_FamilleMateriel']<>"0"){$RequeteCaisse.=" AND Id_FamilleMateriel=".$_SESSION['FiltreToolsSuivi_FamilleMateriel']." ";}
		if($_SESSION['FiltreToolsSuivi_TypeMateriel']<>"-1"){if($_SESSION['FiltreToolsSuivi_ModeleMateriel']<>"0"){$RequeteCaisse.=" AND Id=0 ";}}
		else{if($_SESSION['FiltreToolsSuivi_ModeleMateriel']<>"0"){$RequeteCaisse.=" AND Id_CaisseType=".$_SESSION['FiltreToolsSuivi_ModeleMateriel']." ";}}
		if($_SESSION['FiltreToolsSuivi_NumAAA']<>""){$RequeteCaisse.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsSuivi_NumAAA']."%' ";}
		if($_SESSION['FiltreToolsSuivi_Designation']<>""){$RequeteCaisse.=" AND Id=0 ";}
		if($_SESSION['FiltreToolsSuivi_NumFicheImmo']<>""){$RequeteCaisse.=" AND NumFicheImmo LIKE '%".$_SESSION['FiltreToolsSuivi_NumFicheImmo']."%' ";}
		if($_SESSION['FiltreToolsSuivi_BonCommande']<>""){$RequeteCaisse.=" AND BonCommande LIKE '%".$_SESSION['FiltreToolsSuivi_BonCommande']."%' ";}
		$RequeteCaisse.="  ) AS TAB_MATERIEL 
		WHERE TAB_MATERIEL.ID>0 ";
		
		if($_SESSION['FiltreToolsSuivi_Num']<>""){$RequeteCaisse.=" AND (SN LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%' OR Num LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%')";}
		if($_SESSION['FiltreToolsSuivi_Plateforme']<>"0"){$RequeteCaisse.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) = ".$_SESSION['FiltreToolsSuivi_Plateforme']." ";}
		if($_SESSION['FiltreToolsSuivi_Prestation']<>"0")
		{
			$RequeteCaisse.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) = ".$_SESSION['FiltreToolsSuivi_Prestation']." ";
			if($_SESSION['FiltreToolsSuivi_Pole']<>"0"){$RequeteCaisse.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) = ".$_SESSION['FiltreToolsSuivi_Pole']." ";}
			if($_SESSION['FiltreToolsSuivi_Lieu']<>"0"){$RequeteCaisse.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5) = ".$_SESSION['FiltreToolsSuivi_Lieu']." ";}
		}
		if($_SESSION['FiltreToolsSuivi_Caisse']<>"0"){$RequeteCaisse.=" AND ID = ".$_SESSION['FiltreToolsSuivi_Caisse']." ";}
		if($_SESSION['FiltreToolsSuivi_Personne']<>"0"){$RequeteCaisse.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5) = ".$_SESSION['FiltreToolsSuivi_Personne']." ";}
		if($_SESSION['FiltreToolsSuivi_DateAffectation']<>""){$RequeteCaisse.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5)) ".$_SESSION['FiltreToolsSuivi_TypeDateAffectation']." '".$_SESSION['FiltreToolsSuivi_DateAffectation']."' ";}

		if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableQualite,$IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteResponsablePlateforme,$IdPosteControleGestion,$IdPosteDirection)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation) || DroitsFormationPrestation(array($IdPosteMagasinier)) || DroitsFormationPrestation(array($IdPosteReferentQualiteProduit)) || $NbIQ>0){
		}
		else{
			$RequeteCaisse.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5) = ".$IdPersonneConnectee." ";
		}
		if($_SESSION['FiltreToolsSuivi_Remarque']<>""){$RequeteCaisse.=" AND (SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.8.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)+5)) LIKE \"%".$_SESSION['FiltreToolsSuivi_Remarque']."%\" 
		)";}

		$requeteOrder="";
		if($_SESSION['TriToolsSuivi_General']<>""){$requeteOrder="ORDER BY ".substr($_SESSION['TriToolsSuivi_General'],0,-1);}

$resultRapport=mysqli_query($bdd,$Requete2.$Requete.$Requete2Caisse.$RequeteCaisse.$requeteOrder);
$nbRapport=mysqli_num_rows($resultRapport);
if($nbRapport>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($resultRapport)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}
		
		$LIBELLE_POLE="";
		if($row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$row['LIBELLE_POLE'];}
		
		if($row['TYPESELECT']=="Outils"){
			$leType=0;
			$leId=$row['ID'];
			if($row['Id_Caisse']>0){
				$leId=$row['Id_Caisse'];
				$leType=1;
			}
		}
		else{
			$leType=1;
			$leId=$row['ID'];
		}
		
		$req="SELECT 
			tools_mouvement.DateReception,
			(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=tools_mouvement.Id_Prestation) AS Plateforme,
			(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=tools_mouvement.Id_Prestation) AS Prestation,
			(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=tools_mouvement.Id_Pole) AS Pole,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=tools_mouvement.Id_Personne) AS Personne,
			(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id=tools_mouvement.Id_Lieu) AS Lieu,
			(SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caisse LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id WHERE tools_caisse.Id=tools_mouvement.Id_Caisse) AS NumCaisse
			FROM tools_mouvement
			WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation=0 AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=".$leType." AND tools_mouvement.Id_Materiel__Id_Caisse=".$leId."
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1";

		$ResultTransfertEC=mysqli_query($bdd,$req);
		$NbEnregTransfertEC=mysqli_num_rows($ResultTransfertEC);
		
		$transfert="";
		if($NbEnregTransfertEC>0)
		{
			$RowTransfertEC=mysqli_fetch_array($ResultTransfertEC);
			
			$LIBELLE_POLE_Transfert="";
			if($RowTransfertEC['Pole']<>""){$LIBELLE_POLE_Transfert=" - ".$RowTransfertEC['Pole'];}
		
			$transfert= "<span><b>Transfert en cours</b>
			</span>";
		}
		$LIBELLE_NOUVEAUPOLE="";
		if($row['LIBELLE_NOUVEAUPOLE']<>""){$LIBELLE_NOUVEAUPOLE=" - ".$row['LIBELLE_NOUVEAUPOLE'];}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['NumAAA']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['SN']));
		$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['TYPEMATERIEL'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($row['FAMILLEMATERIEL'])));
		$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($row['LIBELLE_MODELEMATERIEL'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode($row['Num']));
		$sheet->setCellValue('G'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateReception'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['LIBELLE_FOURNISSEUR']));
		
		$sheet->setCellValue('I'.$ligne,utf8_encode($row['LIBELLE_FABRICANT']));
		$sheet->setCellValue('J'.$ligne,utf8_encode(substr($row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE));
		$sheet->setCellValue('K'.$ligne,utf8_encode($row['LIBELLE_LIEU']));
		$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($row['LIBELLE_CAISSETYPE'])));
		$sheet->setCellValue('M'.$ligne,utf8_encode(stripslashes($row['NOMPRENOM_PERSONNE'])));
		$sheet->setCellValue('N'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateDerniereAffectation'])));
		$sheet->setCellValue('O'.$ligne,utf8_encode(stripslashes($row['Remarque'])));
		$sheet->setCellValue('P'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateDerniereVerification'])));
		$sheet->setCellValue('Q'.$ligne,utf8_encode($row['PeriodiciteVerification']));
		$sheet->setCellValue('R'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateFinValidite'])));
		$sheet->setCellValue('S'.$ligne,utf8_encode($row['Prix']));
		$sheet->setCellValue('T'.$ligne,utf8_encode(stripslashes($row['Designation'])));
		$sheet->setCellValue('U'.$ligne,utf8_encode(stripslashes($row['PN'])));
		$sheet->setCellValue('V'.$ligne,utf8_encode(stripslashes($row['TypeECME'])));
		$sheet->setCellValue('W'.$ligne,utf8_encode(stripslashes(substr($row['LIBELLE_NOUVELLEPRESTATION'],0,7).$LIBELLE_NOUVEAUPOLE)));
		
		if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
			$sheet->getStyle('A'.$ligne.':W'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		}
		else{
		
			$sheet->getStyle('A'.$ligne.':V'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		}
		
		if($transfert<>""){$sheet->getStyle('A'.$ligne.':A'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'22b63d'))));}
		
		$LIBELLE_POLE="";
								
								
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
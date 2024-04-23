<?php
session_start();
require_once("../Fonctions.php");
require_once("Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require_once("../Formation/Globales_Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();

if($LangueAffichage=="FR"){$excel = $workbook->load('D-0714-FR.xlsx');}
else{$excel = $workbook->load('D-0714-FR-en.xlsx');}

$sheet = $excel->getSheetByName('D-0714');


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
	AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
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
	AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
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
			tools_materiel.Id AS ID,
			'Outils' AS TYPESELECT,
			NumAAA,
			BonCommande,
			Prix,
			PeriodiciteVerification,
			NumFicheImmo,
			SN,
			DisqueDur,
			Processeur,
			Memoire,
			InfosTechnique,
			tools_modelemateriel.Id_FamilleMateriel,
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
			tools_typemateriel.Id AS ID_TYPEMATERIEL,
			tools_typemateriel.Libelle AS TYPEMATERIEL,
			tools_famillemateriel.Libelle AS FAMILLEMATERIEL,
			tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
			(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fournisseur) AS LIBELLE_FOURNISSEUR,
			(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fabricant) AS LIBELLE_FABRICANT,
			(SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception ASC LIMIT 1) AS DateReception,
			(SELECT Commentaire FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC LIMIT 1) AS Remarque,
			(SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) AS DateDerniereAffectation,
			IF((SELECT tools_mouvement.Id_Caisse
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
				(
					SELECT (
						SELECT EtatValidation
						FROM tools_mouvement
						LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
						WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
					)
					FROM tools_mouvement AS TAB_Mouvement
					LEFT JOIN tools_caisse ON TAB_Mouvement.Id_Caisse=tools_caisse.Id
					LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
			(
				SELECT EtatValidation
				FROM tools_mouvement
				LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)) AS TransfertEC,
			IF((SELECT tools_mouvement.Id_Caisse
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
				(
					SELECT (
						SELECT new_competences_prestation.Id_Plateforme
						FROM tools_mouvement
						LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
						WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
					)
					FROM tools_mouvement AS TAB_Mouvement
					LEFT JOIN tools_caisse ON TAB_Mouvement.Id_Caisse=tools_caisse.Id
					LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
			(
				SELECT new_competences_prestation.Id_Plateforme
				FROM tools_mouvement
				LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)) AS Id_Plateforme,
			IF((SELECT tools_mouvement.Id_Caisse
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
				(
					SELECT (
						SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=new_competences_prestation.Id_Plateforme)
						FROM tools_mouvement
						LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
						WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
					)
					FROM tools_mouvement AS TAB_Mouvement
					LEFT JOIN tools_caisse ON TAB_Mouvement.Id_Caisse=tools_caisse.Id
					LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
			(
				SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=new_competences_prestation.Id_Plateforme)
				FROM tools_mouvement
				LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)) AS LIBELLE_PLATEFORME,
			IF((SELECT tools_mouvement.Id_Caisse
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
				(
					SELECT (
						SELECT new_competences_prestation.Libelle
						FROM tools_mouvement
						LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
						WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
					)
					FROM tools_mouvement AS TAB_Mouvement
					LEFT JOIN tools_caisse ON TAB_Mouvement.Id_Caisse=tools_caisse.Id
					LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
			(
				SELECT new_competences_prestation.Libelle
				FROM tools_mouvement
				LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)) AS LIBELLE_PRESTATION,
			IF((SELECT tools_mouvement.Id_Caisse
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
				(
					SELECT (
						SELECT new_competences_prestation.Id
						FROM tools_mouvement
						LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
						WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
					)
					FROM tools_mouvement AS TAB_Mouvement
					LEFT JOIN tools_caisse ON TAB_Mouvement.Id_Caisse=tools_caisse.Id
					LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
			(
				SELECT new_competences_prestation.Id
				FROM tools_mouvement
				LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)) AS Id_Prestation,
			IF((SELECT tools_mouvement.Id_Caisse
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
				(
					SELECT (
						SELECT new_competences_pole.Libelle
						FROM tools_mouvement
						LEFT JOIN new_competences_pole ON tools_mouvement.Id_Pole=new_competences_pole.Id
						WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
					)
					FROM tools_mouvement AS TAB_Mouvement
					LEFT JOIN tools_caisse ON TAB_Mouvement.Id_Caisse=tools_caisse.Id
					LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
			(
				SELECT new_competences_pole.Libelle
				FROM tools_mouvement
				LEFT JOIN new_competences_pole ON tools_mouvement.Id_Pole=new_competences_pole.Id
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)) AS LIBELLE_POLE,
			IF((SELECT tools_mouvement.Id_Caisse
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
				(
					SELECT (
						SELECT tools_mouvement.Id_Pole
						FROM tools_mouvement
						WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
					)
					FROM tools_mouvement AS TAB_Mouvement
					LEFT JOIN tools_caisse ON TAB_Mouvement.Id_Caisse=tools_caisse.Id
					LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
			(
				SELECT tools_mouvement.Id_Pole
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)) AS Id_Pole,
			IF((SELECT tools_mouvement.Id_Caisse
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
				(
					SELECT (
						SELECT tools_lieu.Libelle
						FROM tools_mouvement
						LEFT JOIN tools_lieu ON tools_mouvement.Id_Lieu=tools_lieu.Id
						WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
					)
					FROM tools_mouvement AS TAB_Mouvement
					LEFT JOIN tools_caisse ON TAB_Mouvement.Id_Caisse=tools_caisse.Id
					LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
			(
				SELECT tools_lieu.Libelle
				FROM tools_mouvement
				LEFT JOIN tools_lieu ON tools_mouvement.Id_Lieu=tools_lieu.Id
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)) AS LIBELLE_LIEU,
			(
				SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num)
				FROM tools_mouvement
				LEFT JOIN tools_caisse ON tools_mouvement.Id_Caisse=tools_caisse.Id
				LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			) AS LIBELLE_CAISSETYPE,
			(
				SELECT tools_mouvement.Id_Caisse
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			) AS Id_Caisse,
			IF((SELECT tools_mouvement.Id_Caisse
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
				(
					SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom)
					FROM tools_mouvement
					LEFT JOIN new_rh_etatcivil ON tools_mouvement.Id_Personne=new_rh_etatcivil.Id
					WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
					ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)
					FROM tools_mouvement AS TAB_Mouvement
					LEFT JOIN tools_caisse ON TAB_Mouvement.Id_Caisse=tools_caisse.Id
					LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				)
				,
				(
					SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom)
					FROM tools_mouvement
					LEFT JOIN new_rh_etatcivil ON tools_mouvement.Id_Personne=new_rh_etatcivil.Id
					WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
				)) AS NOMPRENOM_PERSONNE,
			IF((SELECT tools_mouvement.Id_Caisse
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
				(
					SELECT (SELECT new_rh_etatcivil.Id
					FROM tools_mouvement
					LEFT JOIN new_rh_etatcivil ON tools_mouvement.Id_Personne=new_rh_etatcivil.Id
					WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
					ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)
					FROM tools_mouvement AS TAB_Mouvement
					LEFT JOIN tools_caisse ON TAB_Mouvement.Id_Caisse=tools_caisse.Id
					LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
			(
				SELECT new_rh_etatcivil.Id
				FROM tools_mouvement
				LEFT JOIN new_rh_etatcivil ON tools_mouvement.Id_Personne=new_rh_etatcivil.Id
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)) AS Id_Personne
			";
	$Requete="FROM
				tools_materiel
			LEFT JOIN
				tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
			LEFT JOIN
				tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
			LEFT JOIN
				tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
			WHERE
				tools_materiel.Suppr=0 
				AND tools_modelemateriel.Id_FamilleMateriel IN (165,405,452,271,453,451)
				";
		
		if($_SESSION['FiltreToolsSuivi_NumAAA']<>""){$Requete.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsSuivi_NumAAA']."%' ";}
		if($_SESSION['FiltreToolsSuivi_NumFicheImmo']<>""){$Requete.=" AND NumFicheImmo LIKE '%".$_SESSION['FiltreToolsSuivi_NumFicheImmo']."%' ";}
		
		if($_SESSION['FiltreToolsSuivi_Num']<>""){
			$Requete.=" AND (IF(tools_famillemateriel.Id_TypeMateriel=".$TypeTelephone.",NumTelephone,
					IF(tools_famillemateriel.Id_TypeMateriel=".$TypeClef.",NumClef,
						IF(tools_famillemateriel.Id_TypeMateriel=".$TypeMaqueDeControle.",NumMC,
							IF(tools_famillemateriel.Id_TypeMateriel=".$TypeInformatique.",NumPC,
								IF(tools_famillemateriel.Id_TypeMateriel=".$TypeVehicule.",Immatriculation,
									IF(tools_famillemateriel.Id_TypeMateriel=".$TypeMacaron.",ImmatriculationAssociee,'')
								)
							)
						)
					)
				)
				LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%' 
				OR 
				SN LIKE '%".$_SESSION['FiltreToolsSuivi_Num']."%' 
				)";
		}
		if($_SESSION['FiltreToolsSuivi_Plateforme']<>"0")
		{
			$Requete.=" AND IF((SELECT tools_mouvement.Id_Caisse
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
				(
					SELECT (
						SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=tools_mouvement.Id_Prestation)
						FROM tools_mouvement
						WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
					)
					FROM tools_mouvement AS TAB_Mouvement
					LEFT JOIN tools_caisse ON TAB_Mouvement.Id_Caisse=tools_caisse.Id
					LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
			(
				SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=tools_mouvement.Id_Prestation)
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)) = ".$_SESSION['FiltreToolsSuivi_Plateforme']." ";
		}
		if($_SESSION['FiltreToolsSuivi_Prestation']<>"0")
		{
			$Requete.=" AND IF((SELECT tools_mouvement.Id_Caisse
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
				(
					SELECT (
						SELECT tools_mouvement.Id_Prestation
						FROM tools_mouvement
						WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
					)
					FROM tools_mouvement AS TAB_Mouvement
					LEFT JOIN tools_caisse ON TAB_Mouvement.Id_Caisse=tools_caisse.Id
					LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
			(
				SELECT tools_mouvement.Id_Prestation
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)) = ".$_SESSION['FiltreToolsSuivi_Prestation']." ";
			if($_SESSION['FiltreToolsSuivi_Pole']<>"0"){$Requete.=" AND IF((SELECT tools_mouvement.Id_Caisse
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
				(
					SELECT (
						SELECT tools_mouvement.Id_Pole
						FROM tools_mouvement
						WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
					)
					FROM tools_mouvement AS TAB_Mouvement
					LEFT JOIN tools_caisse ON TAB_Mouvement.Id_Caisse=tools_caisse.Id
					LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
			(
				SELECT tools_mouvement.Id_Pole
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)) = ".$_SESSION['FiltreToolsSuivi_Pole']." ";}
			if($_SESSION['FiltreToolsSuivi_Lieu']<>"0"){$Requete.=" AND IF((SELECT tools_mouvement.Id_Caisse
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
				(
					SELECT (
						SELECT tools_lieu.Id
						FROM tools_mouvement
						LEFT JOIN tools_lieu ON tools_mouvement.Id_Lieu=tools_lieu.Id
						WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
					)
					FROM tools_mouvement AS TAB_Mouvement
					LEFT JOIN tools_caisse ON TAB_Mouvement.Id_Caisse=tools_caisse.Id
					LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
			(
				SELECT tools_lieu.Id
				FROM tools_mouvement
				LEFT JOIN tools_lieu ON tools_mouvement.Id_Lieu=tools_lieu.Id
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)) = ".$_SESSION['FiltreToolsSuivi_Lieu']." ";}
		}
		else
		{
			$Requete.=" AND IF((SELECT tools_mouvement.Id_Caisse
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
				(
					SELECT (
						SELECT tools_mouvement.Id_Prestation
						FROM tools_mouvement
						WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
					)
					FROM tools_mouvement AS TAB_Mouvement
					LEFT JOIN tools_caisse ON TAB_Mouvement.Id_Caisse=tools_caisse.Id
					LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
			(
				SELECT CONCAT(tools_mouvement.Id_Prestation,'_',tools_mouvement.Id_Pole)
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)) IN ".$listePrestaPole." ";
		}
		
		if($_SESSION['FiltreToolsSuivi_Caisse']<>"0"){$Requete.=" AND (SELECT Id_Caisse FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) = ".$_SESSION['FiltreToolsSuivi_Caisse']." ";}
		if($_SESSION['FiltreToolsSuivi_Personne']<>"0"){$Requete.=" AND IF((SELECT tools_mouvement.Id_Caisse
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
				(
					SELECT (SELECT new_rh_etatcivil.Id
					FROM tools_mouvement
					LEFT JOIN new_rh_etatcivil ON tools_mouvement.Id_Personne=new_rh_etatcivil.Id
					WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
					ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)
					FROM tools_mouvement AS TAB_Mouvement
					LEFT JOIN tools_caisse ON TAB_Mouvement.Id_Caisse=tools_caisse.Id
					LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
			(
				SELECT new_rh_etatcivil.Id
				FROM tools_mouvement
				LEFT JOIN new_rh_etatcivil ON tools_mouvement.Id_Personne=new_rh_etatcivil.Id
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)) = ".$_SESSION['FiltreToolsSuivi_Personne']." ";
		}
		if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableQualite,$IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteResponsablePlateforme,$IdPosteControleGestion)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation) || DroitsFormationPrestation(array($IdPosteMagasinier)) || DroitsFormationPrestation(array($IdPosteReferentQualiteProduit)) || $NbIQ>0){
		}
		else{
			$Requete.=" AND IF((SELECT tools_mouvement.Id_Caisse
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
				(
					SELECT (SELECT new_rh_etatcivil.Id
					FROM tools_mouvement
					LEFT JOIN new_rh_etatcivil ON tools_mouvement.Id_Personne=new_rh_etatcivil.Id
					WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
					ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)
					FROM tools_mouvement AS TAB_Mouvement
					LEFT JOIN tools_caisse ON TAB_Mouvement.Id_Caisse=tools_caisse.Id
					LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
					WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
					ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
				),
			(
				SELECT new_rh_etatcivil.Id
				FROM tools_mouvement
				LEFT JOIN new_rh_etatcivil ON tools_mouvement.Id_Personne=new_rh_etatcivil.Id
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)) = ".$IdPersonneConnectee." ";
		}
		if($_SESSION['FiltreToolsSuivi_TypeMateriel']<>"0"){$Requete.=" AND (SELECT (SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = ".$_SESSION['FiltreToolsSuivi_TypeMateriel']." ";}
		if($_SESSION['FiltreToolsSuivi_FamilleMateriel']<>"0"){$Requete.=" AND (SELECT Id_FamilleMateriel FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = ".$_SESSION['FiltreToolsSuivi_FamilleMateriel']." ";}
		if($_SESSION['FiltreToolsSuivi_ModeleMateriel']<>"0"){$Requete.=" AND Id_ModeleMateriel = ".$_SESSION['FiltreToolsSuivi_ModeleMateriel']." ";}
		
		if($_SESSION['FiltreToolsSuivi_DateAffectation']<>""){$Requete.=" AND (SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) ".$_SESSION['FiltreToolsSuivi_TypeDateAffectation']." '".$_SESSION['FiltreToolsSuivi_DateAffectation']."' ";}
		if($_SESSION['FiltreToolsSuivi_DateReception']<>""){$Requete.=" AND (SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception ASC LIMIT 1) ".$_SESSION['FiltreToolsSuivi_TypeDateReception']." '".$_SESSION['FiltreToolsSuivi_DateReception']."' ";}
		if($_SESSION['FiltreToolsSuivi_Remarque']<>"0"){$Requete.=" AND Remarques LIKE \"%".$_SESSION['FiltreToolsSuivi_Remarque']."%\" ";}
		if($_SESSION['FiltreToolsSuivi_BonCommande']<>""){$Requete.=" AND BonCommande LIKE '%".$_SESSION['FiltreToolsSuivi_BonCommande']."%' ";}
		$requeteOrder="";
		if($_SESSION['TriToolsSuivi_General']<>""){$requeteOrder="ORDER BY ".substr($_SESSION['TriToolsSuivi_General'],0,-1);}

$resultRapport=mysqli_query($bdd,$Requete2.$Requete.$requeteOrder);
$nbRapport=mysqli_num_rows($resultRapport);

$valeur2=33;
if($nbRapport>19){
	$valeur=$nbRapport-19;
	$sheet->insertNewRowBefore(11, $valeur);
	$valeur2=33+$nbRapport-19;
}



if($nbRapport>0){
	$ligne = 10;
	while($row=mysqli_fetch_array($resultRapport)){
		
		
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
		
		$num="";
		if($row['Num']<>""){$num=" (".$row['Num'].") ";}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode(substr($row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['NumAAA']));
		$sheet->setCellValue('C'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateReception'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['LIBELLE_FOURNISSEUR']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['LIBELLE_FABRICANT']));
		if($row['Id_FamilleMateriel']==165){
			$sheet->setCellValue('G'.$ligne,utf8_encode($row['LIBELLE_MODELEMATERIEL']));
			$sheet->setCellValue('I'.$ligne,utf8_encode($row['SN']));
		}
		else{
			$sheet->setCellValue('F'.$ligne,utf8_encode($row['LIBELLE_MODELEMATERIEL']));
			$sheet->setCellValue('H'.$ligne,utf8_encode($row['SN'].$num));
			
			$sheet->setCellValue('J'.$ligne,utf8_encode($row['Processeur']));
			$sheet->setCellValue('K'.$ligne,utf8_encode($row['Memoire']));
			$sheet->setCellValue('L'.$ligne,utf8_encode($row['DisqueDur']));
		}
		
		$sheet->setCellValue('M'.$ligne,utf8_encode($row['InfosTechnique']));
		
		$LIBELLE_POLE="";
												
		$ligne++;
		
	}
}

//Enregistrement du fichier excel
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="D-0714-FR.xlsx"');}
else{header('Content-Disposition: attachment;filename="D-0714-FR-en.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../tmp/Export.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
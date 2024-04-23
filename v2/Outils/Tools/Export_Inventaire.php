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
	$sheet->setCellValue('F1',utf8_encode('Désignation'));
	$sheet->setCellValue('G1',utf8_encode('N°'));
	$sheet->setCellValue('H1',utf8_encode('Prestation'));
	$sheet->setCellValue('I1',utf8_encode('Lieu'));
	$sheet->setCellValue('J1',utf8_encode('Caisse'));
	$sheet->setCellValue('K1',utf8_encode('Personne'));
	$sheet->setCellValue('L1',utf8_encode('Date d\'affectation'));

}
else{
	$sheet->setCellValue('A1',utf8_encode('N° AAA'));
	$sheet->setCellValue('B1',utf8_encode('S/N'));
	$sheet->setCellValue('C1',utf8_encode('Type'));
	$sheet->setCellValue('D1',utf8_encode('Family'));
	$sheet->setCellValue('E1',utf8_encode('Material'));
	$sheet->setCellValue('F1',utf8_encode('Designation'));
	$sheet->setCellValue('G1',utf8_encode('N°'));
	$sheet->setCellValue('H1',utf8_encode('Site'));
	$sheet->setCellValue('I1',utf8_encode('Place'));
	$sheet->setCellValue('J1',utf8_encode('Toolbox'));
	$sheet->setCellValue('K1',utf8_encode('Person'));
	$sheet->setCellValue('L1',utf8_encode('Date of assignment'));

}
$sheet->getStyle('A1:L1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);

if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableQualite,$IdPosteResponsableMGX,$IdPosteInformatique))){
	$requeteSite="SELECT Id, Libelle
		FROM new_competences_prestation
		WHERE Id_Plateforme IN 
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION['Id_Personne']." 
				AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableQualite.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
			)
		AND Active=0
		ORDER BY Libelle ASC";
}
elseif(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
	$requeteSite="SELECT Id, Libelle
		FROM new_competences_prestation
		WHERE Id_Plateforme IN 
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION['Id_Personne']." 
				AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
			)
		AND Active=0
		ORDER BY Libelle ASC";
}
else{
	$requeteSite="SELECT Id, Libelle
		FROM new_competences_prestation
		WHERE Id IN 
			(SELECT Id_Prestation 
			FROM new_competences_personne_poste_prestation 
			WHERE Id_Personne=".$_SESSION["Id_Personne"]."
			AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
			)
		AND Active=0
		ORDER BY Libelle ASC";
	
}

$resultPrestation=mysqli_query($bdd,$requeteSite);
$nbPrestation=mysqli_num_rows($resultPrestation);

$PrestationSelect = 0;
$Selected = "";

$PrestationSelect=$_SESSION['FiltreToolsSuivi_Prestation'];
if($_POST){$PrestationSelect=$_POST['prestations'];}
$_SESSION['FiltreToolsSuivi_Prestation']=$PrestationSelect;	

$PrestationAAfficher=array();
if(DroitsFormationPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableQualite,$IdPosteResponsableMGX,$IdPosteInformatique))){
	array_push($PrestationAAfficher,0);
}
if ($nbPrestation > 0)
{
	while($row=mysqli_fetch_array($resultPrestation))
	{
		array_push($PrestationAAfficher,$row['Id']);
	}
 }
 

$Requete2="
	SELECT
		tools_materiel.Id AS Id,
		'Outils' AS TypeSelect,
		NumAAA,
		NumFicheImmo,
		SN,
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
		Designation,
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
			AND (SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) < '".date('Y-m-d',strtotime(date('Y-m-d')."- 6 month"))."'
			AND (IF((SELECT tools_mouvement.Id_Caisse
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
		)) NOT IN ('Perdu','Perdu officiellement','Recyclage','Volé','Détruit','Réformé','Echange (SAV)')
		OR 
		IF((SELECT tools_mouvement.Id_Caisse
			FROM tools_mouvement
			WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
			(
				SELECT (
					SELECT tools_mouvement.Id_Lieu
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
			SELECT tools_mouvement.Id_Lieu
			FROM tools_mouvement
			WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		))=0
		)
			
			";
	
	if($_SESSION['FiltreToolsInventaire_NumAAA']<>""){$Requete.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsInventaire_NumAAA']."%' ";}

	if($_SESSION['FiltreToolsInventaire_Prestation']<>"0")
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
		)) = ".$_SESSION['FiltreToolsInventaire_Prestation']." ";
		if($_SESSION['FiltreToolsInventaire_Pole']<>"0"){$Requete.=" AND IF((SELECT tools_mouvement.Id_Caisse
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
		)) = ".$_SESSION['FiltreToolsInventaire_Pole']." ";}
	}

	if($_SESSION['FiltreToolsInventaire_Personne']<>"0"){$Requete.=" AND IF((SELECT tools_mouvement.Id_Caisse
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
		)) = ".$_SESSION['FiltreToolsInventaire_Personne']." ";
	}
	
	if($_SESSION['FiltreToolsInventaire_TypeMateriel']<>"0"){$Requete.=" AND (SELECT (SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = ".$_SESSION['FiltreToolsInventaire_TypeMateriel']." ";}
	if($_SESSION['FiltreToolsInventaire_FamilleMateriel']<>"0"){$Requete.=" AND (SELECT Id_FamilleMateriel FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = ".$_SESSION['FiltreToolsInventaire_FamilleMateriel']." ";}
	if($_SESSION['FiltreToolsInventaire_ModeleMateriel']<>"0"){$Requete.=" AND Id_ModeleMateriel = ".$_SESSION['FiltreToolsInventaire_ModeleMateriel']." ";}

	$Requete.=" AND (IF((SELECT tools_mouvement.Id_Caisse
			FROM tools_mouvement
			WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
			(
				SELECT (
					SELECT CONCAT(Id_Prestation,'_',Id_Pole)
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
			SELECT CONCAT(Id_Prestation,'_',Id_Pole)
			FROM tools_mouvement
			WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		)) IN 
		(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
			FROM new_competences_personne_poste_prestation 
			WHERE Id_Personne=".$_SESSION["Id_Personne"]."
			AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
		)
		
		OR 
		
		IF((SELECT tools_mouvement.Id_Caisse
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
	)) IN 
	
	(SELECT Id_Plateforme
		FROM new_competences_personne_poste_plateforme
		WHERE Id_Personne=".$_SESSION["Id_Personne"]."
		AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableQualite.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteResponsablePlateforme.",".$IdPosteControleGestion.") 
	)
	
	OR 
		
		IF((SELECT tools_mouvement.Id_Caisse
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
		(
			SELECT (
				SELECT Id_Personne
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
		SELECT Id_Personne
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
	)) = ".$IdPersonneConnectee."
	
	)  ";

	//PARTIE CAISSE DE LA REQUETE
	$Requete2Caisse=" UNION ALL
		SELECT Id,
		'Caisse' AS TypeSelect,
		NumAAA AS NumAAA,
		NumFicheImmo,
		SN AS SN,
		Num AS Num,
		'' AS Designation,
		-1 AS Id_TYPEMATERIEL,
		'Caisse' AS TYPEMATERIEL,
		(SELECT Libelle FROM tools_famillemateriel WHERE Id=Id_FamilleMateriel) AS FAMILLEMATERIEL,
		(SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType) AS LIBELLE_MODELEMATERIEL,
		(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fournisseur) AS LIBELLE_FOURNISSEUR,
		(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fabricant) AS LIBELLE_FABRICANT,
		(SELECT DateReception FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id AND tools_mouvement.EtatValidation IN (0,1) AND Suppr=0 AND Type=1 ORDER BY DateReception ASC LIMIT 1) AS DateReception,
		(SELECT Commentaire FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC LIMIT 1) AS Remarque,
		(SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id AND Suppr=0 AND Type=1 ORDER BY DateReception DESC, Id DESC LIMIT 1) AS DateDerniereAffectation,
		(
			SELECT EtatValidation
			FROM tools_mouvement
			LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		) AS TransfertEC,
		(
			SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=new_competences_prestation.Id_Plateforme)
			FROM tools_mouvement
			LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		) AS LIBELLE_PLATEFORME,
		(
			SELECT new_competences_prestation.Libelle
			FROM tools_mouvement
			LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		) AS LIBELLE_PRESTATION,
		(
			SELECT tools_mouvement.Id_Prestation
			FROM tools_mouvement
			WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		) AS Id_Prestation,
		(
			SELECT new_competences_pole.Libelle
			FROM tools_mouvement
			LEFT JOIN new_competences_pole ON tools_mouvement.Id_Pole=new_competences_pole.Id
			WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		) AS LIBELLE_POLE,
		(
			SELECT tools_mouvement.Id_Pole
			FROM tools_mouvement
			WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		) AS Id_Pole,
		(
			SELECT tools_lieu.Libelle
			FROM tools_mouvement
			LEFT JOIN tools_lieu ON tools_mouvement.Id_Lieu=tools_lieu.Id
			WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		) AS LIBELLE_LIEU,
		(
			SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num)
			FROM tools_mouvement
			LEFT JOIN tools_caisse ON tools_mouvement.Id_Caisse=tools_caisse.Id
			LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
			WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		) AS LIBELLE_CAISSETYPE,
		tools_caisse.Id AS Id_Caisse,
		(
			SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom)
			FROM tools_mouvement
			LEFT JOIN new_rh_etatcivil ON tools_mouvement.Id_Personne=new_rh_etatcivil.Id
			WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		) AS NOMPRENOM_PERSONNE,
		(
			SELECT new_rh_etatcivil.Id
			FROM tools_mouvement
			LEFT JOIN new_rh_etatcivil ON tools_mouvement.Id_Personne=new_rh_etatcivil.Id
			WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		) AS Id_Personne
		";
	$RequeteCaisse="FROM
		tools_caisse
	WHERE 
		tools_caisse.Suppr=0
		AND (SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id AND Suppr=0 AND Type=1 ORDER BY DateReception DESC, Id DESC LIMIT 1) < '".date('Y-m-d',strtotime(date('Y-m-d')."- 6 month"))."'
		AND ((
			SELECT tools_lieu.Libelle
			FROM tools_mouvement
			LEFT JOIN tools_lieu ON tools_mouvement.Id_Lieu=tools_lieu.Id
			WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		) NOT IN ('Perdu','Perdu officiellement','Recyclage','Volé','Détruit','Réformé','Echange (SAV)')
		OR
		(
			SELECT tools_mouvement.Id_Lieu
			FROM tools_mouvement
			WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		)=0
		)
		";
	if($_SESSION['FiltreToolsInventaire_NumAAA']<>""){$RequeteCaisse.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsInventaire_NumAAA']."%' ";}

	if($_SESSION['FiltreToolsInventaire_Prestation']<>"0")
	{
		$RequeteCaisse.=" AND (SELECT Id_Prestation FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) = ".$_SESSION['FiltreToolsInventaire_Prestation']." ";
		if($_SESSION['FiltreToolsInventaire_Pole']<>"0"){$RequeteCaisse.=" AND (SELECT Id_Pole FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND Suppr=0  AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) = ".$_SESSION['FiltreToolsInventaire_Pole']." ";}
	}

	if($_SESSION['FiltreToolsInventaire_Personne']<>"0"){$RequeteCaisse.=" AND (SELECT Id_Personne FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND Suppr=0  AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) = ".$_SESSION['FiltreToolsInventaire_Personne']." ";}
	if($_SESSION['FiltreToolsInventaire_TypeMateriel']>0){$RequeteCaisse.=" AND Id=0 ";}
	if($_SESSION['FiltreToolsInventaire_FamilleMateriel']<>"0"){$RequeteCaisse.=" AND Id_FamilleMateriel=".$_SESSION['FiltreToolsInventaire_FamilleMateriel']." ";}
	if($_SESSION['FiltreToolsInventaire_ModeleMateriel']<>"0"){$RequeteCaisse.=" AND Id=0 ";}

	$RequeteCaisse.=" AND ((SELECT CONCAT(Id_Prestation,'_',Id_Pole) FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) IN 
		(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
			FROM new_competences_personne_poste_prestation 
			WHERE Id_Personne=".$_SESSION["Id_Personne"]."
			AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
		)
		
		OR 
		
		(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC, Id DESC LIMIT 1)
		IN 
	
		(SELECT Id_Plateforme
			FROM new_competences_personne_poste_plateforme
			WHERE Id_Personne=".$_SESSION["Id_Personne"]."
			AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableQualite.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteResponsablePlateforme.",".$IdPosteControleGestion.") 
		)
		
		OR (SELECT Id_Personne FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND Suppr=0  AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) = ".$IdPersonneConnectee."
	
	)  ";


	$requeteOrder="";
	if($_SESSION['TriToolsInventaire_General']<>""){$requeteOrder="ORDER BY ".substr($_SESSION['TriToolsInventaire_General'],0,-1);}

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
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['NumAAA']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['SN']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['TYPEMATERIEL']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['FAMILLEMATERIEL']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['LIBELLE_MODELEMATERIEL']));
		$sheet->setCellValue('F'.$ligne,utf8_encode($row['Designation']));
		$sheet->setCellValue('G'.$ligne,utf8_encode($row['Num']));
		$sheet->setCellValue('H'.$ligne,utf8_encode(substr($row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE));
		$sheet->setCellValue('I'.$ligne,utf8_encode($row['LIBELLE_LIEU']));
		$sheet->setCellValue('J'.$ligne,utf8_encode($row['LIBELLE_CAISSETYPE']));
		$sheet->setCellValue('K'.$ligne,utf8_encode($row['NOMPRENOM_PERSONNE']));
		$sheet->setCellValue('L'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateDerniereAffectation'])));

		$sheet->getStyle('A'.$ligne.':L'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
						
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
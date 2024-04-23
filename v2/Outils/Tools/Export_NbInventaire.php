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
	$sheet->setCellValue('A1',utf8_encode('Prestation'));
	$sheet->setCellValue('B1',utf8_encode('Nb à traiter'));

}
else{
	$sheet->setCellValue('A1',utf8_encode('Site'));
	$sheet->setCellValue('B1',utf8_encode('Nb to process'));

}
$sheet->getStyle('A1:B1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

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
	SELECT LIBELLE_PRESTATION, LIBELLE_POLE, COUNT(LIBELLE_PRESTATION) AS NB
	FROM
	(
	SELECT
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
		)) AS LIBELLE_POLE
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
		SELECT 
		(
			SELECT new_competences_prestation.Libelle
			FROM tools_mouvement
			LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
			WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		) AS LIBELLE_PRESTATION,
		(
			SELECT new_competences_pole.Libelle
			FROM tools_mouvement
			LEFT JOIN new_competences_pole ON tools_mouvement.Id_Pole=new_competences_pole.Id
			WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		) AS LIBELLE_POLE
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
	
	)  
	) AS TAB_TOTAL
	GROUP BY LIBELLE_PRESTATION, LIBELLE_POLE
	";
	$requeteOrder="ORDER BY NB DESC";

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
		
		$sheet->setCellValue('A'.$ligne,utf8_encode(substr($row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['NB']));

		$sheet->getStyle('A'.$ligne.':B'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
						
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
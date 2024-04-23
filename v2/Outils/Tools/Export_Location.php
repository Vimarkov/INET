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
	$sheet->setCellValue('G1',utf8_encode('Début location'));
	$sheet->setCellValue('H1',utf8_encode('Fin location'));
	$sheet->setCellValue('I1',utf8_encode('Prestation'));
	$sheet->setCellValue('J1',utf8_encode('Lieu'));
	$sheet->setCellValue('K1',utf8_encode('Caisse'));
	$sheet->setCellValue('L1',utf8_encode('Personne'));
	$sheet->setCellValue('M1',utf8_encode('Date d\'affectation'));

}
else{
	$sheet->setCellValue('A1',utf8_encode('N° AAA'));
	$sheet->setCellValue('B1',utf8_encode('S/N'));
	$sheet->setCellValue('C1',utf8_encode('Type'));
	$sheet->setCellValue('D1',utf8_encode('Material'));
	$sheet->setCellValue('E1',utf8_encode('Family'));
	$sheet->setCellValue('F1',utf8_encode('N°'));
	$sheet->setCellValue('G1',utf8_encode('Rental start'));
	$sheet->setCellValue('H1',utf8_encode('Rental end'));
	$sheet->setCellValue('I1',utf8_encode('Site'));
	$sheet->setCellValue('J1',utf8_encode('Place'));
	$sheet->setCellValue('K1',utf8_encode('Toolbox'));
	$sheet->setCellValue('L1',utf8_encode('Person'));
	$sheet->setCellValue('M1',utf8_encode('Date of assignment'));

}
$sheet->getStyle('A1:M1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);

$req="SELECT CONCAT(Id_Prestation,'_',Id_Pole) Id
	FROM new_competences_personne_poste_prestation 
	WHERE Id_Personne=".$_SESSION["Id_Personne"]."
	AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") ";
$Result=mysqli_query($bdd,$req);

$listePrestaPole="('-1_-1')";
$NbEnreg=mysqli_num_rows($Result);
if($NbEnreg){
	$listePrestaPole="(";
	while($RowListe=mysqli_fetch_array($Result)){
		if($listePrestaPole<>"("){$listePrestaPole.=",";}
		$listePrestaPole.="'".$RowListe['Id']."'";
	}
	$listePrestaPole.=")";
}

$req="(SELECT Id_Plateforme
		FROM new_competences_personne_poste_plateforme
		WHERE Id_Personne=".$_SESSION["Id_Personne"]."
		AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteResponsablePlateforme.",".$IdPosteControleGestion.",".$IdPosteDirection.") 
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

//PARTIE OUTILS DE LA REQUETE
$Requete2="

	SELECT 
		TAB_MATERIEL.ID,
		TAB_MATERIEL.TYPESELECT,
		TAB_MATERIEL.NumAAA,
		TAB_MATERIEL.NumFicheImmo,
		TAB_MATERIEL.SN,
		TAB_MATERIEL.Num,
		TAB_MATERIEL.DateDebutLocation,
		TAB_MATERIEL.DateFinLocation,
		TAB_MATERIEL.Designation,
		TAB_MATERIEL.ID_TYPEMATERIEL,
		TAB_MATERIEL.TYPEMATERIEL,
		TAB_MATERIEL.FAMILLEMATERIEL,
		TAB_MATERIEL.LIBELLE_MODELEMATERIEL,
		SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.8.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Remarque,
		SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5)) AS DateDerniereAffectation,
		SUBSTRING(TAB_MATERIEL.AffectationMouvement,1,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)-1) AS TransfertEC,
		(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5))) AS LIBELLE_PLATEFORME,
		(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_PRESTATION,
		SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Prestation,
		(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_POLE,
		SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Pole,
		(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_LIEU,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5))) AS NOMPRENOM_PERSONNE,
		SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Personne,
		SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Caisse,
		(SELECT (SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caissetype WHERE tools_caisse.Id_CaisseType=tools_caissetype.Id)
		FROM tools_caisse WHERE Id=SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)+5))) AS LIBELLE_CAISSETYPE

	FROM 
	(
	SELECT
		tools_materiel.Id AS ID,
		'Outils' AS TYPESELECT,
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
		DateDebutLocation AS DateDebutLocation,
		DateFinContratLocation AS DateFinLocation,
		Designation,
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
		) AS AffectationMouvement
		";
$Requete="FROM
			tools_materiel
		LEFT JOIN
			tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
		LEFT JOIN
			tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id 
		WHERE tools_materiel.Suppr=0
			AND tools_materiel.Location=1 
			";

if($_SESSION['FiltreToolsLocation_TypeMateriel']<>"0"){$Requete.=" AND (SELECT (SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE tools_famillemateriel.Id=tools_modelemateriel.Id_FamilleMateriel) FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = ".$_SESSION['FiltreToolsLocation_TypeMateriel']." ";}
if($_SESSION['FiltreToolsLocation_FamilleMateriel']<>"0"){$Requete.=" AND (SELECT Id_FamilleMateriel FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) = ".$_SESSION['FiltreToolsLocation_FamilleMateriel']." ";}
if($_SESSION['FiltreToolsLocation_ModeleMateriel']<>"0"){$Requete.=" AND Id_ModeleMateriel = ".$_SESSION['FiltreToolsLocation_ModeleMateriel']." ";}
if($_SESSION['FiltreToolsLocation_NumAAA']<>""){$Requete.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsLocation_NumAAA']."%' ";}
if($_SESSION['FiltreToolsLocation_NumFicheImmo']<>""){$Requete.=" AND NumFicheImmo LIKE '%".$_SESSION['FiltreToolsLocation_NumFicheImmo']."%' ";}
if($_SESSION['FiltreToolsLocation_Designation']<>""){$Requete.=" AND Designation LIKE '%".$_SESSION['FiltreToolsLocation_Designation']."%' ";}
if($_SESSION['FiltreToolsLocation_DateFinLocation']<>""){$Requete.=" AND DateFinContratLocation ".$_SESSION['FiltreToolsLocation_TypeDateFinLocation']." '".$_SESSION['FiltreToolsLocation_DateFinLocation']."' ";}

	
$Requete.="  ) AS TAB_MATERIEL 

WHERE 
		 ";
	if($_SESSION['FiltreToolsLocation_PrestationA']=="1" && $_SESSION['FiltreToolsLocation_PrestationI']=="0"){
		$Requete.=" ((SELECT Active FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) IN (0) 
		AND IF(SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5))>0,(SELECT Actif FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5) ),0) IN (0) ) ";
	}
	elseif($_SESSION['FiltreToolsLocation_PrestationA']=="0" && $_SESSION['FiltreToolsLocation_PrestationI']=="1"){
		$Requete.=" ((SELECT Active FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) IN (-1) 
		AND IF(SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5))>0,(SELECT Actif FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5) ),1) IN (1) ) ";
	}
	else{
		$Requete.=" ((SELECT Active FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) IN (0,-1) 
		AND IF(SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5))>0,(SELECT Actif FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5) ),0) IN (0,1) ) ";
	}
	
	if($_SESSION['FiltreToolsLocation_Num']<>""){
		$Requete.=" AND (Num
			LIKE '%".$_SESSION['FiltreToolsLocation_Num']."%' 
			OR 
			SN LIKE '%".$_SESSION['FiltreToolsLocation_Num']."%' 
			)";
	}
	if($_SESSION['FiltreToolsLocation_MaterielEquipe']=="1"){
		$Requete.=" AND (CONCAT(SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)),'_',SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5))) IN ".$listePrestaPole."
					OR 
				(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5))) IN ".$listePlateforme."
				)  ";
	}
	if($_SESSION['FiltreToolsLocation_Caisse']<>"0"){$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5)) = ".$_SESSION['FiltreToolsLocation_Caisse']." ";}
	if($_SESSION['FiltreToolsLocation_Personne']<>"0"){$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5) = ".$_SESSION['FiltreToolsLocation_Personne']." ";
	}
	if($_SESSION['FiltreToolsLocation_Plateforme']<>"0")
	{
		$Requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) = ".$_SESSION['FiltreToolsLocation_Plateforme']." ";
	}
	if($_SESSION['FiltreToolsLocation_Prestation']<>"0")
	{
		$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) = ".$_SESSION['FiltreToolsLocation_Prestation']." ";
		if($_SESSION['FiltreToolsLocation_Pole']<>"0"){$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) = ".$_SESSION['FiltreToolsLocation_Pole']." ";}
		if($_SESSION['FiltreToolsLocation_Lieu']<>"0"){$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5) = ".$_SESSION['FiltreToolsLocation_Lieu']." ";}
	}

	if($_SESSION['FiltreToolsLocation_DateAffectation']<>""){$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5)) ".$_SESSION['FiltreToolsLocation_TypeDateAffectation']." '".$_SESSION['FiltreToolsLocation_DateAffectation']."' ";}
	
	if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteResponsablePlateforme,$IdPosteControleGestion,$IdPosteDirection)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation) || DroitsFormationPrestation(array($IdPosteMagasinier))){
	
	}
	else{
		$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5) = ".$IdPersonneConnectee." ";
	}
	if($_SESSION['FiltreToolsLocation_Remarque']<>""){$Requete.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.8.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)+5)) LIKE \"%".$_SESSION['FiltreToolsLocation_Remarque']."%\" ";}

	//PARTIE CAISSE DE LA REQUETE
	$Requete2Caisse="UNION ALL
		SELECT 
			TAB_MATERIEL.ID,
			TAB_MATERIEL.TYPESELECT,
			TAB_MATERIEL.NumAAA,
			TAB_MATERIEL.NumFicheImmo,
			TAB_MATERIEL.SN,
			TAB_MATERIEL.Num,
			TAB_MATERIEL.DateDebutLocation,
			TAB_MATERIEL.DateFinLocation,
			TAB_MATERIEL.Designation,
			TAB_MATERIEL.ID_TYPEMATERIEL,
			TAB_MATERIEL.TYPEMATERIEL,
			TAB_MATERIEL.FAMILLEMATERIEL,
			TAB_MATERIEL.LIBELLE_MODELEMATERIEL,
			SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.8.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Remarque,
			SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5)) AS DateDerniereAffectation,
			SUBSTRING(TAB_MATERIEL.AffectationMouvement,1,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)-1) AS TransfertEC,
			(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme)
				FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_PLATEFORME,
			(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_PRESTATION,
			SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Prestation,
			(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_POLE,
			SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Pole,
			(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5)) ) AS LIBELLE_LIEU,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5))) AS NOMPRENOM_PERSONNE,
			SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5)) AS Id_Personne,
			TAB_MATERIEL.ID AS Id_Caisse,
			(SELECT (SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num) FROM tools_caissetype WHERE tools_caisse.Id_CaisseType=tools_caissetype.Id)
			FROM tools_caisse WHERE Id=TAB_MATERIEL.ID) AS LIBELLE_CAISSETYPE
		FROM (
			SELECT Id AS ID,
			'Caisse' AS TYPESELECT,
			NumAAA AS NumAAA,
			NumFicheImmo,
			SN AS SN,
			Num AS Num,
			DateDebutLocation AS DateDebutLocation,
			DateFinContratLocation AS DateFinLocation,
			'' AS Designation,
			-1 AS Id_TYPEMATERIEL,
			'Caisse' AS TYPEMATERIEL,
			(SELECT Libelle FROM tools_famillemateriel WHERE Id=Id_FamilleMateriel) AS FAMILLEMATERIEL,
			(SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType) AS LIBELLE_MODELEMATERIEL,
			
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
		tools_caisse.Suppr=0 
		AND tools_caisse.Location=1 ";
	if($_SESSION['FiltreToolsLocation_TypeMateriel']>0){$RequeteCaisse.=" AND Id=0 ";}
	if($_SESSION['FiltreToolsLocation_FamilleMateriel']<>"0"){$RequeteCaisse.=" AND Id_FamilleMateriel=".$_SESSION['FiltreToolsLocation_FamilleMateriel']." ";}
	if($_SESSION['FiltreToolsLocation_ModeleMateriel']<>"0"){$RequeteCaisse.=" AND Id=0 ";}
	if($_SESSION['FiltreToolsLocation_NumAAA']<>""){$RequeteCaisse.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsLocation_NumAAA']."%' ";}
	if($_SESSION['FiltreToolsLocation_Designation']<>""){$RequeteCaisse.=" AND Id=0 ";}
	if($_SESSION['FiltreToolsLocation_NumFicheImmo']<>""){$RequeteCaisse.=" AND NumFicheImmo LIKE '%".$_SESSION['FiltreToolsLocation_NumFicheImmo']."%' ";}
	if($_SESSION['FiltreToolsLocation_DateFinLocation']<>""){$RequeteCaisse.=" AND DateFinContratLocation ".$_SESSION['FiltreToolsLocation_TypeDateFinLocation']." '".$_SESSION['FiltreToolsLocation_DateFinLocation']."' ";}
	
	$RequeteCaisse.="  ) AS TAB_MATERIEL 
	WHERE ";
	
	if($_SESSION['FiltreToolsLocation_PrestationA']=="1" && $_SESSION['FiltreToolsLocation_PrestationI']=="0"){
		$RequeteCaisse.=" ((SELECT Active FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) IN (0) 
		AND IF(SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5))>0,(SELECT Actif FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5) ),0) IN (0) ) ";
	}
	elseif($_SESSION['FiltreToolsLocation_PrestationA']=="0" && $_SESSION['FiltreToolsLocation_PrestationI']=="1"){
		$RequeteCaisse.=" ((SELECT Active FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) IN (-1) 
		AND IF(SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5))>0,(SELECT Actif FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5) ),1) IN (1) ) ";
	}
	else{
		$RequeteCaisse.=" ((SELECT Active FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) IN (0,-1) 
		AND IF(SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5))>0,(SELECT Actif FROM new_competences_pole WHERE new_competences_pole.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5) ),0) IN (0,1) ) ";
	}
	
	if($_SESSION['FiltreToolsLocation_Num']<>""){$RequeteCaisse.=" AND (SN LIKE '%".$_SESSION['FiltreToolsLocation_Num']."%' OR Num LIKE '%".$_SESSION['FiltreToolsLocation_Num']."%')";}
	if($_SESSION['FiltreToolsLocation_Plateforme']<>"0"){$RequeteCaisse.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) ) = ".$_SESSION['FiltreToolsLocation_Plateforme']." ";}
	if($_SESSION['FiltreToolsLocation_Prestation']<>"0")
	{
		$RequeteCaisse.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)) = ".$_SESSION['FiltreToolsLocation_Prestation']." ";
		if($_SESSION['FiltreToolsLocation_Pole']<>"0"){$RequeteCaisse.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5)) = ".$_SESSION['FiltreToolsLocation_Pole']." ";}
		if($_SESSION['FiltreToolsLocation_Lieu']<>"0"){$RequeteCaisse.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5) = ".$_SESSION['FiltreToolsLocation_Lieu']." ";}
	}
	if($_SESSION['FiltreToolsLocation_Caisse']<>"0"){$RequeteCaisse.=" AND ID = ".$_SESSION['FiltreToolsLocation_Caisse']." ";}
	if($_SESSION['FiltreToolsLocation_Personne']<>"0"){$RequeteCaisse.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5) = ".$_SESSION['FiltreToolsLocation_Personne']." ";}
	if($_SESSION['FiltreToolsLocation_DateAffectation']<>""){$RequeteCaisse.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5)) ".$_SESSION['FiltreToolsLocation_TypeDateAffectation']." '".$_SESSION['FiltreToolsLocation_DateAffectation']."' ";}

	if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteResponsablePlateforme,$IdPosteControleGestion,$IdPosteDirection)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation) || DroitsFormationPrestation(array($IdPosteMagasinier))){
	}
	else{
		$RequeteCaisse.=" AND SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.5.|',TAB_MATERIEL.AffectationMouvement)-LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)+5) = ".$IdPersonneConnectee." ";
	}
	if($_SESSION['FiltreToolsLocation_MaterielEquipe']=="1"){
		$RequeteCaisse.=" AND (CONCAT(SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)),'_',SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5))) IN ".$listePrestaPole."
					OR 
				(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5))) IN ".$listePlateforme."
				)  ";
	}

	$requeteOrder="";
	if($_SESSION['TriToolsLocation_General']<>""){$requeteOrder="ORDER BY ".substr($_SESSION['TriToolsLocation_General'],0,-1);}
	

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
		$sheet->setCellValue('F'.$ligne,utf8_encode($row['Num']));
		$sheet->setCellValue('G'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateDebutLocation'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateFinLocation'])));
		$sheet->setCellValue('I'.$ligne,utf8_encode(substr($row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE));
		$sheet->setCellValue('J'.$ligne,utf8_encode($row['LIBELLE_LIEU']));
		$sheet->setCellValue('K'.$ligne,utf8_encode($row['LIBELLE_CAISSETYPE']));
		$sheet->setCellValue('L'.$ligne,utf8_encode($row['NOMPRENOM_PERSONNE']));
		$sheet->setCellValue('M'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateDerniereAffectation'])));

		$sheet->getStyle('A'.$ligne.':M'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		

		if($row['DateFinLocation']>'0001-01-01' ){
			if($row['DateFinLocation']<=date('Y-m-d')){$sheet->getStyle('H'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f82035'))));}
			elseif($row['DateFinLocation']<=date('Y-m-d', strtotime(date('Y-m-d')." +3 month"))){$sheet->getStyle('H'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'edaf2b'))));}
			elseif($row['DateFinLocation']<=date('Y-m-d', strtotime(date('Y-m-d')." +6 month"))){$sheet->getStyle('H'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'192aff'))));}
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
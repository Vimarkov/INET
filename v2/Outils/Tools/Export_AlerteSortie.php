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
	$sheet->setCellValue('C1',utf8_encode('Modèle'));
	$sheet->setCellValue('D1',utf8_encode('N°'));
	$sheet->setCellValue('E1',utf8_encode('Personne'));
	$sheet->setCellValue('F1',utf8_encode('Prestation'));
	$sheet->setCellValue('G1',utf8_encode('Date d\'affectation'));
	$sheet->setCellValue('H1',utf8_encode('Nouvelle prestation'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('N° AAA'));
	$sheet->setCellValue('B1',utf8_encode('S/N'));
	$sheet->setCellValue('C1',utf8_encode('Material'));
	$sheet->setCellValue('D1',utf8_encode('N°'));
	$sheet->setCellValue('E1',utf8_encode('Person'));
	$sheet->setCellValue('F1',utf8_encode('Site'));
	$sheet->setCellValue('G1',utf8_encode('Date of assignment'));
	$sheet->setCellValue('H1',utf8_encode('New site'));
}
$sheet->getStyle('A1:H1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);

if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme,$IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
	$req="SELECT CONCAT(Id,'_0') AS Id
		FROM new_competences_prestation
		WHERE Id_Plateforme IN 
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION['Id_Personne']." 
				AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
			)
		AND Id NOT IN (
				SELECT Id_Prestation
				FROM new_competences_pole    
				WHERE Actif=0
			)
			
		UNION 
		
		SELECT DISTINCT CONCAT(new_competences_pole.Id_Prestation,'_',new_competences_pole.Id) AS Id
			FROM new_competences_pole
			INNER JOIN new_competences_prestation
			ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
			AND Actif=0
			AND Id_Plateforme IN 
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION['Id_Personne']." 
				AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
			)
		";
}
else{
	$req="SELECT CONCAT(Id_Prestation,'_',Id_Pole) AS Id
		FROM new_competences_personne_poste_prestation 
		WHERE Id_Personne=".$_SESSION["Id_Personne"]."
		AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
	";
	
}
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
 

//PARTIE OUTILS DE LA REQUETE
$Requete="
		SELECT 
			tools_materiel.Id,
			'Outils' AS TypeSelect,
			NumAAA,
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
			EtatValidationT AS TransfertEC,
			tools_famillemateriel.Id_TypeMateriel,
			tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
			DateReceptionT AS DateDerniereAffectation,
			Id_PrestationT AS Id_Prestation,
			Id_PoleT AS Id_Pole,
			(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_PrestationT) AS LIBELLE_PRESTATION,
			(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_PoleT) AS LIBELLE_POLE,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_PersonneT) AS NOMPRENOM_PERSONNE,
			Id_PersonneT AS Id_Personne,
			(
				IF((SELECT Active FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_PrestationT)=-1,'FIN PRESTATION',
					IF((SELECT Actif FROM new_competences_pole WHERE new_competences_pole.Id=Id_PoleT)=1,'FIN PÔLE',
						IF((SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
							FROM new_competences_personne_plateforme
							WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
							AND new_competences_personne_plateforme.Id_Plateforme=14)>0,'SORTIE','')
					)
				)
			) AS NouvelleAffectation
		FROM 
			tools_materiel
		LEFT JOIN
			tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
		LEFT JOIN
			tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
		LEFT JOIN
			tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
		WHERE Id_PersonneT>0
		AND tools_materiel.Suppr=0 
		AND EtatValidationT IN (0,1)
		AND 
		(
			((SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
			FROM new_competences_personne_plateforme
			WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
			AND new_competences_personne_plateforme.Id_Plateforme=14)>0
			AND
			(SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
			FROM new_competences_personne_plateforme
			WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT)=1)
			OR 
			(
				SELECT Active 
				FROM new_competences_prestation 
				WHERE new_competences_prestation.Id=Id_PrestationT
			)=-1
			OR 
			(
				SELECT Actif
				FROM new_competences_pole
				WHERE new_competences_pole.Id=Id_PoleT
			)=1
			OR 
			((SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
			FROM new_competences_personne_plateforme
			WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
			AND new_competences_personne_plateforme.Id_Plateforme<>(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_PrestationT))>0
			AND
			(SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
			FROM new_competences_personne_plateforme
			WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT)=1)
		)
		
";

if($_SESSION['FiltreToolsAlerteSortie_NumAAA']<>""){
	$Requete.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsAlerteSortie_NumAAA']."%' ";
}
if($_SESSION['FiltreToolsAlerteSortie_Prestation']<>"0"){
	$Requete.=" AND Id_PrestationT = ".$_SESSION['FiltreToolsAlerteSortie_Prestation']." ";
	if($_SESSION['FiltreToolsAlerteSortie_Pole']<>"0"){
		$Requete.=" AND Id_PoleT = ".$_SESSION['FiltreToolsAlerteSortie_Pole']." ";
	}
}
else
{
	$Requete.=" AND CONCAT(Id_PrestationT,'_',Id_PoleT) IN ".$listePrestaPole." ";
}
if($_SESSION['FiltreToolsAlerteSortie_Personne']<>"0"){
	$Requete.=" AND Id_PersonneT = ".$_SESSION['FiltreToolsAlerteSortie_Personne']." ";
}
if($_SESSION['FiltreToolsAlerteSortie_TypeMateriel']<>"0"){
	$Requete.=" AND Id_TypeMateriel = ".$_SESSION['FiltreToolsAlerteSortie_TypeMateriel']." ";
}
if($_SESSION['FiltreToolsAlerteSortie_FamilleMateriel']<>"0"){
	$Requete.=" AND Id_FamilleMateriel = ".$_SESSION['FiltreToolsAlerteSortie_FamilleMateriel']." ";
}
if($_SESSION['FiltreToolsAlerteSortie_ModeleMateriel']<>"0"){
	$Requete.=" AND Id_ModeleMateriel = ".$_SESSION['FiltreToolsAlerteSortie_ModeleMateriel']." ";
}

//PARTIE CAISSE DE LA REQUETE
$Requete.="
		UNION ALL
		SELECT 
			tools_caisse.Id,
			'Caisse' AS TypeSelect,
			Num AS NumAAA,
			'' AS SN,
			'' AS Num,
			EtatValidationT AS TransfertEC,
			-1 AS Id_TypeMateriel,
			(SELECT Libelle FROM tools_caissetype WHERE Id=tools_caisse.Id_CaisseType) AS LIBELLE_MODELEMATERIEL,
			DateReceptionT AS DateDerniereAffectation,
			Id_PrestationT AS Id_Prestation,
			Id_PoleT AS Id_Pole,
			(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_PrestationT) AS LIBELLE_PRESTATION,
			(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_PoleT) AS LIBELLE_POLE,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_PersonneT) AS NOMPRENOM_PERSONNE,
			Id_PersonneT AS Id_Personne,
			(
				IF((SELECT Active FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_PrestationT)=-1,'FIN PRESTATION',
					IF((SELECT Actif FROM new_competences_pole WHERE new_competences_pole.Id=Id_PoleT)=1,'FIN PÔLE',
						IF((SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
							FROM new_competences_personne_plateforme
							WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
							AND new_competences_personne_plateforme.Id_Plateforme=14)>0,'SORTIE',
								IF((SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
								FROM new_competences_personne_plateforme
								WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
								AND new_competences_personne_plateforme.Id_Plateforme<>(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_PrestationT))>0,(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=new_competences_personne_plateforme.Id_Plateforme)
									FROM new_competences_personne_plateforme
									WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
									AND new_competences_personne_plateforme.Id_Plateforme<>(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_PrestationT)),''))
					)
				)
			) AS NouvelleAffectation
		FROM 
			tools_caisse
		WHERE Id_PersonneT>0
		AND tools_caisse.Suppr=0
		AND EtatValidationT IN (0,1)
		AND 
			(
				((SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
				FROM new_competences_personne_plateforme
				WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
				AND new_competences_personne_plateforme.Id_Plateforme=14)>0
				AND
				(SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
				FROM new_competences_personne_plateforme
				WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT)=1)
				OR 
				(
					SELECT Active 
					FROM new_competences_prestation 
					WHERE new_competences_prestation.Id=Id_PrestationT
				)=-1
				OR 
				(
					SELECT Actif
					FROM new_competences_pole
					WHERE new_competences_pole.Id=Id_PoleT
				)=1
				OR 
				((SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
				FROM new_competences_personne_plateforme
				WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
				AND new_competences_personne_plateforme.Id_Plateforme<>(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_PrestationT))>0
				AND
				(SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
				FROM new_competences_personne_plateforme
				WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT)=1)
			)

		
";

if($_SESSION['FiltreToolsAlerteSortie_NumAAA']<>""){
	$Requete.=" AND Num LIKE '%".$_SESSION['FiltreToolsAlerteSortie_NumAAA']."%' ";
}
if($_SESSION['FiltreToolsAlerteSortie_Prestation']<>"0"){
	$Requete.=" AND Id_PrestationT = ".$_SESSION['FiltreToolsAlerteSortie_Prestation']." ";
	if($_SESSION['FiltreToolsAlerteSortie_Pole']<>"0"){
		$Requete.=" AND Id_PoleT = ".$_SESSION['FiltreToolsAlerteSortie_Pole']." ";
	}
}
else
{
	$Requete.=" AND CONCAT(Id_PrestationT,'_',Id_PoleT) IN ".$listePrestaPole." ";
}

if($_SESSION['FiltreToolsAlerteSortie_Personne']<>"0"){
	$Requete.=" AND Id_PersonneT = ".$_SESSION['FiltreToolsAlerteSortie_Personne']." ";
}
if($_SESSION['FiltreToolsAlerteSortie_TypeMateriel']<>"0"){
	$Requete.=" AND Id=0 ";
}
if($_SESSION['FiltreToolsAlerteSortie_FamilleMateriel']<>"0"){
	$Requete.=" AND Id=0 ";
}
if($_SESSION['FiltreToolsAlerteSortie_ModeleMateriel']<>"0"){
	$Requete.=" AND Id=0 ";
}


$requeteOrder="";
if($_SESSION['TriToolsAlerteSortie_General']<>""){
	$requeteOrder="ORDER BY ".substr($_SESSION['TriToolsAlerteSortie_General'],0,-1);
}

$resultRapport=mysqli_query($bdd,$Requete.$requeteOrder);
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
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['LIBELLE_MODELEMATERIEL']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['Num']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['NOMPRENOM_PERSONNE']));
		$sheet->setCellValue('F'.$ligne,utf8_encode(substr($row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE));
		$sheet->setCellValue('G'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateDerniereAffectation'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['NouvelleAffectation']));
		
		$sheet->getStyle('A'.$ligne.':H'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
						
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
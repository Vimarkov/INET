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
	$sheet->setCellValue('I1',utf8_encode('Date d\'affectation'));
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
	$sheet->setCellValue('I1',utf8_encode('New date of assignment'));
}
$sheet->getStyle('A1:I1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

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
		AND Id_Plateforme=1
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
			AND new_competences_prestation.Id_Plateforme=1
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
if($NbEnreg>0){
	$listePrestaPole="(";
	while($RowListe=mysqli_fetch_array($Result)){
		if($listePrestaPole<>"("){$listePrestaPole.=",";}
		$listePrestaPole.="'".$RowListe['Id']."'";
	}
	$listePrestaPole.=")";
}

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
			(SELECT COUNT(Id)
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND DateDebut<='".date('Y-m-d')."'
				AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
				AND TypeDocument IN ('Nouveau','Avenant')
				AND rh_personne_contrat.Id_Personne=Id_PersonneT
			) AS NbContrat,
			(SELECT DateDebut
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND TypeDocument IN ('Nouveau','Avenant')
				AND rh_personne_contrat.Id_Personne=Id_PersonneT
				ORDER BY DateDebut DESC, Id DESC
				LIMIT 1
			) AS DateDebutContrat,
			(SELECT DateFin
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND TypeDocument IN ('Nouveau','Avenant')
				AND rh_personne_contrat.Id_Personne=Id_PersonneT  
				ORDER BY DateDebut DESC, Id DESC
				LIMIT 1
			) AS DateFinContrat,
			tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
			DateReceptionT AS DateDerniereAffectation,
			Id_PrestationT AS Id_Prestation,
			Id_PoleT AS Id_Pole,
			(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_PrestationT) AS LIBELLE_PRESTATION,
			(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_PoleT) AS LIBELLE_POLE,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_PersonneT) AS NOMPRENOM_PERSONNE,
			Id_PersonneT AS Id_Personne,
			(SELECT rh_personne_mouvement.Id_Prestation
			FROM rh_personne_mouvement
			WHERE Suppr=0
			AND rh_personne_mouvement.Id_Personne=Id_PersonneT 
			AND rh_personne_mouvement.EtatValidation IN (0,1)
			AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS Id_PrestationNew,
			(SELECT rh_personne_mouvement.Id_Pole
			FROM rh_personne_mouvement
			WHERE Suppr=0
			AND rh_personne_mouvement.Id_Personne=Id_PersonneT  
			AND rh_personne_mouvement.EtatValidation IN (0,1)
			AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS Id_PoleNew,
		(SELECT (SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation)
			FROM rh_personne_mouvement
			WHERE Suppr=0
			AND rh_personne_mouvement.Id_Personne=Id_PersonneT 
			AND rh_personne_mouvement.EtatValidation IN (0,1)
			AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS LIBELLE_NOUVELLEPRESTATION,
		(SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole)
			FROM rh_personne_mouvement
			WHERE Suppr=0
			AND rh_personne_mouvement.Id_Personne=Id_PersonneT  
			AND rh_personne_mouvement.EtatValidation IN (0,1)
			AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS LIBELLE_NOUVEAUPOLE,
		(SELECT DateDebut
			FROM rh_personne_mouvement
			WHERE Suppr=0
			AND rh_personne_mouvement.Id_Personne=Id_PersonneT  
			AND rh_personne_mouvement.EtatValidation IN (0,1)
			AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS DateMouvementPrestation
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
			(SELECT CONCAT(rh_personne_mouvement.Id_Prestation,'_',rh_personne_mouvement.Id_Pole)
			FROM rh_personne_mouvement
			WHERE Suppr=0
			AND rh_personne_mouvement.Id_Personne=Id_PersonneT
			AND rh_personne_mouvement.EtatValidation IN (0,1)
			AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1)
			<> CONCAT(Id_PrestationT,'_',Id_PoleT)
			OR 
			(SELECT COUNT(Id)
			FROM rh_personne_contrat
			WHERE Suppr=0
			AND DateDebut<='".date('Y-m-d')."'
			AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
			AND TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=Id_PersonneT
			)=0
		)
				
		";

		if($_SESSION['FiltreToolsChangement_NumAAA']<>""){
			$Requete.=" AND NumAAA LIKE '%".$_SESSION['FiltreToolsChangement_NumAAA']."%' ";
		}
		if($_SESSION['FiltreToolsChangement_Prestation']<>"0"){
			$Requete.=" AND Id_PrestationT = ".$_SESSION['FiltreToolsChangement_Prestation']." ";
			if($_SESSION['FiltreToolsChangement_Pole']<>"0"){
				$Requete.=" AND Id_PoleT = ".$_SESSION['FiltreToolsChangement_Pole']." ";
			}
		}
		else
		{
			$Requete.=" AND CONCAT(Id_PrestationT,'_',Id_PoleT) IN ".$listePrestaPole." ";
		}
		$Requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_PrestationT) = 1 ";
		
		if($_SESSION['FiltreToolsChangement_Personne']<>"0"){
			$Requete.=" AND Id_PersonneT = ".$_SESSION['FiltreToolsChangement_Personne']." ";
		}
		if($_SESSION['FiltreToolsChangement_TypeMateriel']<>"0"){
			$Requete.=" AND Id_TypeMateriel = ".$_SESSION['FiltreToolsChangement_TypeMateriel']." ";
		}
		if($_SESSION['FiltreToolsChangement_FamilleMateriel']<>"0"){
			$Requete.=" AND Id_FamilleMateriel = ".$_SESSION['FiltreToolsChangement_FamilleMateriel']." ";
		}
		if($_SESSION['FiltreToolsChangement_ModeleMateriel']<>"0"){
			$Requete.=" AND Id_ModeleMateriel = ".$_SESSION['FiltreToolsChangement_ModeleMateriel']." ";
		}

		//PARTIE CAISSE DE LA REQUETE
		$Requete.="
				UNION ALL
				SELECT 
					tools_caisse.Id,
					'Caisse' AS TypeSelect,
					NumAAA AS NumAAA,
					SN AS SN,
					Num AS Num,
					EtatValidationT AS TransfertEC,
					-1 AS Id_TypeMateriel,
					(SELECT COUNT(Id)
						FROM rh_personne_contrat
						WHERE Suppr=0
						AND DateDebut<='".date('Y-m-d')."'
						AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
						AND TypeDocument IN ('Nouveau','Avenant')
						AND rh_personne_contrat.Id_Personne=Id_PersonneT  
					) AS NbContrat,
					(SELECT DateDebut
						FROM rh_personne_contrat
						WHERE Suppr=0
						AND TypeDocument IN ('Nouveau','Avenant')
						AND rh_personne_contrat.Id_Personne=Id_PersonneT  
						ORDER BY DateDebut DESC, Id DESC
						LIMIT 1
					) AS DateDebutContrat,
					(SELECT DateFin
						FROM rh_personne_contrat
						WHERE Suppr=0
						AND TypeDocument IN ('Nouveau','Avenant')
						AND rh_personne_contrat.Id_Personne=Id_PersonneT  
						ORDER BY DateDebut DESC, Id DESC
						LIMIT 1
					) AS DateFinContrat,
					(SELECT Libelle FROM tools_caissetype WHERE Id=tools_caisse.Id_CaisseType) AS LIBELLE_MODELEMATERIEL,
					DateReceptionT AS DateDerniereAffectation,
					Id_PrestationT AS Id_Prestation,
					Id_PoleT AS Id_Pole,
					(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_PrestationT) AS LIBELLE_PRESTATION,
					(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_PoleT) AS LIBELLE_POLE,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_PersonneT) AS NOMPRENOM_PERSONNE,
					Id_PersonneT AS Id_Personne,
					(SELECT rh_personne_mouvement.Id_Prestation
					FROM rh_personne_mouvement
					WHERE Suppr=0
					AND rh_personne_mouvement.Id_Personne=Id_PersonneT 
					AND rh_personne_mouvement.EtatValidation IN (0,1)
					AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
					AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS Id_PrestationNew,
					(SELECT rh_personne_mouvement.Id_Pole
					FROM rh_personne_mouvement
					WHERE Suppr=0
					AND rh_personne_mouvement.Id_Personne=Id_PersonneT  
					AND rh_personne_mouvement.EtatValidation IN (0,1)
					AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
					AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS Id_PoleNew,
				(SELECT (SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation)
					FROM rh_personne_mouvement
					WHERE Suppr=0
					AND rh_personne_mouvement.Id_Personne=Id_PersonneT 
					AND rh_personne_mouvement.EtatValidation IN (0,1)
					AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
					AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS LIBELLE_NOUVELLEPRESTATION,
				(SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole)
					FROM rh_personne_mouvement
					WHERE Suppr=0
					AND rh_personne_mouvement.Id_Personne=Id_PersonneT  
					AND rh_personne_mouvement.EtatValidation IN (0,1)
					AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
					AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS LIBELLE_NOUVEAUPOLE,
				(SELECT DateDebut
					FROM rh_personne_mouvement
					WHERE Suppr=0
					AND rh_personne_mouvement.Id_Personne=Id_PersonneT  
					AND rh_personne_mouvement.EtatValidation IN (0,1)
					AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
					AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1) AS DateMouvementPrestation
				FROM 
					tools_caisse
				WHERE Id_PersonneT>0
				AND tools_caisse.Suppr=0 
				AND EtatValidationT IN (0,1)
				AND 
				(
					(SELECT CONCAT(rh_personne_mouvement.Id_Prestation,'_',rh_personne_mouvement.Id_Pole)
					FROM rh_personne_mouvement
					WHERE Suppr=0
					AND rh_personne_mouvement.Id_Personne=Id_PersonneT
					AND rh_personne_mouvement.EtatValidation IN (0,1)
					AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
					AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1)
					<> CONCAT(Id_PrestationT,'_',Id_PoleT)
					OR 
					(SELECT COUNT(Id)
					FROM rh_personne_contrat
					WHERE Suppr=0
					AND DateDebut<='".date('Y-m-d')."'
					AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
					AND TypeDocument IN ('Nouveau','Avenant')
					AND rh_personne_contrat.Id_Personne=Id_PersonneT
					)=0
				)
				
		";

		if($_SESSION['FiltreToolsChangement_NumAAA']<>""){
			$Requete.=" AND Num LIKE '%".$_SESSION['FiltreToolsChangement_NumAAA']."%' ";
		}
		if($_SESSION['FiltreToolsChangement_Prestation']<>"0"){
			$Requete.=" AND Id_PrestationT = ".$_SESSION['FiltreToolsChangement_Prestation']." ";
			if($_SESSION['FiltreToolsChangement_Pole']<>"0"){
				$Requete.=" AND Id_PoleT = ".$_SESSION['FiltreToolsChangement_Pole']." ";
			}
		}
		else
		{
			$Requete.=" AND CONCAT(Id_PrestationT,'_',Id_PoleT) IN ".$listePrestaPole." ";
		}
		$Requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_PrestationT) = 1 ";
		
		if($_SESSION['FiltreToolsChangement_Personne']<>"0"){
			$Requete.=" AND Id_PersonneT = ".$_SESSION['FiltreToolsChangement_Personne']." ";
		}
		if($_SESSION['FiltreToolsChangement_TypeMateriel']<>"0"){
			$Requete.=" AND Id=0 ";
		}
		if($_SESSION['FiltreToolsChangement_FamilleMateriel']<>"0"){
			$Requete.=" AND Id=0 ";
		}
		if($_SESSION['FiltreToolsChangement_ModeleMateriel']<>"0"){
			$Requete.=" AND Id=0 ";
		}
		

		$requeteOrder="";
		if($_SESSION['TriToolsChangement_General']<>""){
			$requeteOrder=" ORDER BY ".substr($_SESSION['TriToolsChangement_General'],0,-1);
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
		
		$LIBELLE_NOUVEAUPOLE="";
		if($row['LIBELLE_NOUVEAUPOLE']<>""){$LIBELLE_NOUVEAUPOLE=" - ".$row['LIBELLE_NOUVEAUPOLE'];}
					
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['NumAAA']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['SN']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['LIBELLE_MODELEMATERIEL']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['Num']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['NOMPRENOM_PERSONNE']));
		$sheet->setCellValue('F'.$ligne,utf8_encode(substr($row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE));
		$sheet->setCellValue('G'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateDerniereAffectation'])));
		if($row['NbContrat']>0){
			$sheet->setCellValue('H'.$ligne,utf8_encode(substr($row['LIBELLE_NOUVELLEPRESTATION'],0,7).$LIBELLE_NOUVEAUPOLE));
			$sheet->setCellValue('I'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateMouvementPrestation'])));
		}
		else{
			if($_SESSION["Langue"]=="FR"){
				$sheet->setCellValue('H'.$ligne,utf8_encode("Sans contrat"));
			}
			else{
				$sheet->setCellValue('H'.$ligne,utf8_encode("Without a contract"));
			}
			$sheet->setCellValue('I'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateFinContrat'])));
		}
		
		$sheet->getStyle('A'.$ligne.':I'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
						
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
<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require_once '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require_once '../Fonctions.php';

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

//Ligne En-tete
if($_SESSION['Langue']=="FR"){
	$sheet->setCellValue('A1',utf8_encode('N°'));
	$sheet->setCellValue('B1',utf8_encode('Entité'));
	$sheet->setCellValue('C1',utf8_encode('Prestation'));
	$sheet->setCellValue('D1',utf8_encode('Thème'));
	$sheet->setCellValue('E1',utf8_encode('Questionnaire'));
	$sheet->setCellValue('F1',utf8_encode('Etat'));
	$sheet->setCellValue('G1',utf8_encode('Note'));
	$sheet->setCellValue('H1',utf8_encode('N° Action Tracker'));
	$sheet->setCellValue('I1',utf8_encode('Date'));
	$sheet->setCellValue('J1',utf8_encode('Surveillé'));
	$sheet->setCellValue('K1',utf8_encode('Surveillant'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('N°'));
	$sheet->setCellValue('B1',utf8_encode('Entity'));
	$sheet->setCellValue('C1',utf8_encode('Activity'));
	$sheet->setCellValue('D1',utf8_encode('Theme'));
	$sheet->setCellValue('E1',utf8_encode('Questionnaire'));
	$sheet->setCellValue('F1',utf8_encode('State'));
	$sheet->setCellValue('G1',utf8_encode('Score'));
	$sheet->setCellValue('H1',utf8_encode('Action Tracker number'));
	$sheet->setCellValue('I1',utf8_encode('Date'));
	$sheet->setCellValue('J1',utf8_encode('Supervised'));
	$sheet->setCellValue('K1',utf8_encode('Supervisor'));	
}
$sheet->getStyle('A1:J1')->getAlignment()->setWrapText(true);
$sheet->getStyle('A1:J1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);
$sheet->getColumnDimension('A')->setWidth(10);
$sheet->getColumnDimension('E')->setWidth(40);
$sheet->getColumnDimension('G')->setWidth(10);
$sheet->getColumnDimension('H')->setWidth(13);
$sheet->getColumnDimension('I')->setWidth(13);
$sheet->getColumnDimension('J')->setWidth(15);
$sheet->getColumnDimension('K')->setWidth(15);

//Liste des surveillances
$resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$_SESSION['Id_Personne']);
$nbAccess=mysqli_num_rows($resAcc);

$resAccSuperAdmin=mysqli_query($bdd,"SELECT Id FROM soda_superadministrateur WHERE Id_Personne=".$IdPersonneConnectee);
$nbSuperAdmin=mysqli_num_rows($resAccSuperAdmin);

$reqSurveillant = "SELECT Id FROM soda_surveillant WHERE Id_Personne=".$_SESSION['Id_Personne']." ";	
$nbSurveillant=mysqli_num_rows($resAcc=mysqli_query($bdd,$reqSurveillant));

$req="SELECT Id_Personne 
	FROM new_competences_relation 
	WHERE Evaluation='X'
	AND Suppr=0
	AND Date_Debut<='".date('Y-m-d')."'
	AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
	AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
	AND Id_Personne=".$_SESSION['Id_Personne']." ";
$resultSurQualifie=mysqli_query($bdd,$req);
$nbSurveillantQualifie=mysqli_num_rows($resultSurQualifie);

$req="SELECT Id_Personne 
	FROM new_competences_relation 
	WHERE Evaluation='L'
	AND Suppr=0
	AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
	AND Id_Personne=".$_SESSION['Id_Personne']." ";
$resultSurQualifie=mysqli_query($bdd,$req);
$nbSurveillantECQualif=mysqli_num_rows($resultSurQualifie);
		
//Liste des surveillances planifiées
$req="SELECT Id,'PLANNIF' AS Type,
		(SELECT (SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Theme,
		(SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Id_Theme,
		(SELECT Libelle FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Questionnaire,
		Id_Questionnaire,
		(SELECT Actif FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS SupprQuestionnaire,
		IF(Id_Prestation>0,(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation),(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme)) AS Plateforme,
		IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) AS Id_Plateforme,
		(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,Id_Prestation,
		(SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) FROM new_competences_personne_poste_prestation WHERE Id_Prestation=soda_plannifmanuelle.Id_Prestation AND Id_Poste=1 ORDER BY Backup LIMIT 1) AS N1,
		(SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) FROM new_competences_personne_poste_prestation WHERE Id_Prestation=soda_plannifmanuelle.Id_Prestation AND Id_Poste=2 ORDER BY Backup LIMIT 1) AS N2,
		Volume-(SELECT COUNT(soda_surveillance.Id) FROM soda_surveillance WHERE soda_surveillance.Suppr=0 AND Etat IN ('Clôturé','En cours - papier','Brouillon') AND AutoSurveillance=0 AND Id_PlannifManuelle=soda_plannifmanuelle.Id ) AS Volume,
		Annee,Semaine,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Surveillant) AS Surveillant
		FROM soda_plannifmanuelle 
		WHERE Suppr=0
		AND Annee=".$_SESSION['FiltreSODA_Annee']."
		AND (SELECT COUNT(soda_surveillance.Id) FROM soda_surveillance WHERE soda_surveillance.Suppr=0 AND Etat IN ('Clôturé','En cours - papier','Brouillon') AND AutoSurveillance=0 
			AND Id_PlannifManuelle=soda_plannifmanuelle.Id) < soda_plannifmanuelle.Volume
		";
		
if($nbAccess>0 || $nbSuperAdmin>0 || $nbSurveillant>0 || $nbSurveillantQualifie>0 || $nbSurveillantECQualif>0 || DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableHSE,$IdPosteCoordinateurSecurite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))){
	
}
else{
	$req.="AND (IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (
		SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']."
		AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteResponsableQualite.",".$IdPosteChargeMissionOperation.",".$IdPosteDirectionOperation.",".$IdPosteChargeMissionOperation.",".$IdPosteCoordinateurSecurite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteReferentSurveillance.")
	)
	OR 
	Id_Prestation IN (
		SELECT Id_Prestation
		FROM new_competences_personne_poste_prestation 
		WHERE Id_Personne=".$_SESSION['Id_Personne']."
		AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",".$IdPosteResponsableProjet.",".$IdPosteCoordinateurProjet.",".$IdPosteCoordinateurEquipe.",".$IdPosteChefEquipe.")
		)
	)
	";
}
if ($_SESSION['FiltreSODAAccueil_Plateforme'] <> 0 && $_SESSION['FiltreSODAAccueil_Plateforme'] <> -1){$req .= "AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme)=". $_SESSION['FiltreSODAAccueil_Plateforme']." ";}
if ($_SESSION['FiltreSODAAccueil_Prestation'] <> 0){$req .= "AND Id_Prestation =".$_SESSION['FiltreSODAAccueil_Prestation']." ";}
if ($_SESSION['FiltreSODAAccueil_Surveillant'] <> -1){$req .= "AND Id_Surveillant =".$_SESSION['FiltreSODAAccueil_Surveillant']." ";}
if ($_SESSION['FiltreSODAAccueil_Theme'] <> 0)
{
	$req .= "AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) =".$_SESSION['FiltreSODAAccueil_Theme']." ";
	if($_SESSION['FiltreSODAAccueil_Questionnaire'] <> 0){$req .= "AND Id_Questionnaire =".$_SESSION['FiltreSODAAccueil_Questionnaire']." ";}
}
$req.=" ORDER BY Semaine, Theme, Questionnaire, Prestation";

$resultSurveillance=mysqli_query($bdd,$req);
$nbSurveillance=mysqli_num_rows($resultSurveillance);
$semaine=date('Y')."S";
if(date('W')<10){$semaine.="0".date('W');}
else{$semaine.=date('W');}

if($nbSurveillance > 0){
	$Couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($resultSurveillance)){
		$volume=$row['Volume'];
		
		if($volume>0){
			for($i=1;$i<=$volume;$i++){
				if($Couleur=="EEEEEE"){$Couleur="FFFFFF";}
				else{$Couleur="EEEEEE";}
				
				if($_SESSION["Langue"]=="FR"){$etat="A faire";}else{$etat= "To do";}
				$lasemaine=$row['Annee']."S";
				$couleurTexte="";
				if($row['Semaine']<10){$lasemaine.="0".$row['Semaine'];}
				else{$lasemaine.=$row['Semaine'];}
				if($semaine>$lasemaine){
					$couleurTexte="style='color:#f31515;'";
					if($_SESSION["Langue"]=="FR"){$etat="En retard";}else{$etat= "Late";}
				}
				
				$presta=substr($row['Prestation'],0,strpos($row['Prestation']," "));
				if($presta==""){$presta=$row['Prestation'];}
				
				if($row['Semaine']<10){$laSemaine= $_SESSION['FiltreSODA_Annee']."S0".$row['Semaine'];}
				else{$laSemaine= $_SESSION['FiltreSODA_Annee']."S".$row['Semaine'];}
				$sheet->setCellValueByColumnAndRow(0,$ligne,utf8_encode(''));
				$sheet->setCellValueByColumnAndRow(1,$ligne,utf8_encode(stripslashes($row['Plateforme'])));
				$sheet->setCellValueByColumnAndRow(2,$ligne,utf8_encode($presta));
				$sheet->setCellValueByColumnAndRow(3,$ligne,utf8_encode(stripslashes($row['Theme'])));
				$sheet->setCellValueByColumnAndRow(4,$ligne,utf8_encode(stripslashes($row['Questionnaire'])));
				$sheet->setCellValueByColumnAndRow(5,$ligne,utf8_encode($etat));
				$sheet->setCellValueByColumnAndRow(6,$ligne,utf8_encode(''));
				$sheet->setCellValueByColumnAndRow(7,$ligne,utf8_encode(''));
				$sheet->setCellValueByColumnAndRow(8,$ligne,utf8_encode($laSemaine));
				$sheet->setCellValueByColumnAndRow(9,$ligne,utf8_encode(''));
				$sheet->setCellValueByColumnAndRow(10,$ligne,utf8_encode(stripslashes($row['Surveillant'])));
				$sheet->getStyle('A'.$ligne.':K'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$Couleur))));
				
				$ligne++;
			}
		}
	}
}
	
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export_SurveillancePlannifiee.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Export_SurveillancePlannifiee.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

//Ligne En-tete
if($_SESSION['Langue']=="FR"){
	$sheet->setCellValue('A1',utf8_encode('N° AT'));
	$sheet->setCellValue('B1',utf8_encode('Personne'));
	$sheet->setCellValue('C1',utf8_encode('Date AT'));
	$sheet->setCellValue('D1',utf8_encode('Heure AT'));
	$sheet->setCellValue('E1',utf8_encode('Contrat'));
	$sheet->setCellValue('F1',utf8_encode('Métier'));
	$sheet->setCellValue('G1',utf8_encode('Lieu exact de l\'accident'));
	$sheet->setCellValue('H1',utf8_encode('SIRET Client'));
	$sheet->setCellValue('I1',utf8_encode('Prestation'));
	$sheet->setCellValue('J1',utf8_encode('Etait-ce'));
	$sheet->setCellValue('K1',utf8_encode('Objet(s) dont le contact a blessé'));
	$sheet->setCellValue('L1',utf8_encode('Activité de la victime'));
	$sheet->setCellValue('M1',utf8_encode('Nature de l\'accident'));
	$sheet->setCellValue('N1',utf8_encode('Siège(s) des lésions'));
	$sheet->setCellValue('O1',utf8_encode('Nature des lésions'));
	$sheet->setCellValue('P1',utf8_encode('Arrêt de travail'));
	$sheet->setCellValue('Q1',utf8_encode('Date connaissance AT'));
	$sheet->setCellValue('R1',utf8_encode('Heure connaissance AT'));
	$sheet->setCellValue('S1',utf8_encode('Doutes, réserves'));
	$sheet->setCellValue('T1',utf8_encode('Autres informations'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Accident no'));
	$sheet->setCellValue('B1',utf8_encode('Person'));
	$sheet->setCellValue('C1',utf8_encode('Date of the accident'));
	$sheet->setCellValue('D1',utf8_encode('Time of the accident'));
	$sheet->setCellValue('E1',utf8_encode('Contract'));
	$sheet->setCellValue('F1',utf8_encode('Job'));
	$sheet->setCellValue('G1',utf8_encode('Accurate location of the accident'));
	$sheet->setCellValue('H1',utf8_encode('SIRET of the client'));
	$sheet->setCellValue('I1',utf8_encode('Site'));
	$sheet->setCellValue('J1',utf8_encode('Was it'));
	$sheet->setCellValue('K1',utf8_encode('Object whose contact hurt'));
	$sheet->setCellValue('L1',utf8_encode('Activity of the victim'));
	$sheet->setCellValue('M1',utf8_encode('Nature of the accident '));
	$sheet->setCellValue('N1',utf8_encode('Seat(s) of lesions'));
	$sheet->setCellValue('O1',utf8_encode('Nature of lesions'));
	$sheet->setCellValue('P1',utf8_encode('Work stopping'));
	$sheet->setCellValue('Q1',utf8_encode('Date of knowledge of the accident'));
	$sheet->setCellValue('R1',utf8_encode('Date of knowledge of the accident'));
	$sheet->setCellValue('S1',utf8_encode('Doubts, reservations'));
	$sheet->setCellValue('T1',utf8_encode('Other information'));
}
$sheet->getStyle('A1:T1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(25);

$Menu=$_GET['Menu'];

$requete2="SELECT Id,Id_Personne,Id_Createur,Id_Prestation,Id_Pole,Id_Metier,Id_Lieu_AT,
	DateCreation,DateAT,HeureAT,Id_TypeContrat,DoutesCirconstances As Doutes,
	LieuAccident,SIRETClient,Activite,CommentaireNature,ArretDeTravail,
	DateConnaissanceAT,HeureConnaissanceAT,DoutesCirconstances,AutresInformations,
	(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
	(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
	(SELECT LibelleEN FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContratEN,
	(SELECT Libelle FROM rh_lieu_at WHERE rh_lieu_at.Id=Id_Lieu_AT) AS LieuAT,
	(SELECT LibelleEN FROM rh_lieu_at WHERE rh_lieu_at.Id=Id_Lieu_AT) AS LieuATEN,
	(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Prestation,
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_at.Id_Personne) AS Personne, 
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_at.Id_Createur) AS Demandeur ";
$requete=" FROM rh_personne_at
	WHERE Suppr=0 ";
if($Menu<>1){
	if($Menu==4){
		if(DroitsFormationPlateforme($TableauIdPostesRH) || DroitsFormationPlateforme(array($IdPosteResponsableHSE))){
			$requete.=" AND ((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_at.Id_Prestation) IN 
				(
					SELECT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$_SESSION['Id_Personne']." 
					AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.",".$IdPosteResponsableHSE.")
				)
				OR rh_personne_at.Id_Prestation=0
				)";
		}
	}
	elseif($Menu==14){
		$requete.=" AND ((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_at.Id_Prestation) IN 
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION['Id_Personne']." 
				AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.",".$IdPosteResponsableHSE.")
			)
			OR rh_personne_at.Id_Prestation=0
			)";
	}
	elseif($Menu==3){
		if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
			$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_at.Id_Prestation) IN 
				(
					SELECT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$_SESSION['Id_Personne']." 
					AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
				)";
		}
		else{
			$requete.=" AND CONCAT(rh_personne_at.Id_Prestation,'_',rh_personne_at.Id_Pole) IN 
						(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
						FROM new_competences_personne_poste_prestation 
						WHERE Id_Personne=".$_SESSION["Id_Personne"]."
						AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
						)";
		}
	}
	elseif($Menu==2){
		$requete.=" AND rh_personne_at.Id_Personne=".$_SESSION['Id_Personne']." ";
	}
	if($Menu<>2){
		if($_SESSION['FiltreRHAT_Personne']<>0){
			$requete.=" AND rh_personne_at.Id_Personne=".$_SESSION['FiltreRHAT_Personne']." ";
		}
	}
	if($Menu==4){
		if($_SESSION['FiltreRHAT_RespProjet']<>""){
			$requete.="AND CONCAT(rh_personne_at.Id_Prestation,'_',rh_personne_at.Id_Pole) 
						IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
							FROM new_competences_personne_poste_prestation
							WHERE Id_Personne IN (".$_SESSION['FiltreRHAT_RespProjet'].")
							AND Id_Poste IN (".$IdPosteResponsableProjet.")
						)
						";
		}
	}
	if($_SESSION['FiltreRHAT_Mois']<>0){
		if($_SESSION['FiltreRHAT_MoisCumules']<>""){
			$requete.=" AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))>='".$_SESSION['FiltreRHAT_Annee'].'_'.$_SESSION['FiltreRHAT_Mois']."' 
						AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))<='".$_SESSION['FiltreRHAT_Annee']."_12' ";
		}
		else{
			$requete.=" AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))='".$_SESSION['FiltreRHAT_Annee'].'_'.$_SESSION['FiltreRHAT_Mois']."' ";
		}
	}
	else{
		$requete.=" AND YEAR(DateAT)='".$_SESSION['FiltreRHAT_Annee']."' ";
	}

	if($_SESSION['FiltreRHAT_ArretTravail']==1){
		$requete.=" AND  ArretDeTravail=1 ";
	}
	elseif($_SESSION['FiltreRHAT_ArretTravail']==-1){
		$requete.=" AND  ArretDeTravail=0 ";
	}
}
else{
	if(DroitsFormation1Plateforme("17",array($IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableQualite))){
		
	}
	else{
		$requete.="AND
				(
					(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_at.Id_Prestation) IN (
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteResponsablePlateforme.",".$IdPosteOperateurSaisieRH.",".$IdPosteResponsableHSE.",".$IdPosteCoordinateurSecurite.")
					)
					
					OR 
					(
						CONCAT(rh_personne_at.Id_Prestation,'_',rh_personne_at.Id_Pole) IN 
						(
							SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
						)
					)
				) ";
				
	}
	if($_SESSION['FiltreAccidentT_Mois']<>0){
		if($_SESSION['FiltreAccidentT_MoisCumules']<>""){
			$requete.=" AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))>='".$_SESSION['FiltreAccidentT_Annee'].'_'.$_SESSION['FiltreAccidentT_Mois']."' 
						AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))<='".$_SESSION['FiltreAccidentT_Annee']."_12' ";
		}
		else{
			$requete.=" AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))='".$_SESSION['FiltreAccidentT_Annee'].'_'.$_SESSION['FiltreAccidentT_Mois']."' ";
		}
	}
	else{
		$requete.=" AND YEAR(DateAT)='".$_SESSION['FiltreAccidentT_Annee']."' ";
	}
	
	if($_SESSION['FiltreAccidentT_ArretTravail']==1){
		$requete.=" AND  ArretDeTravail=1 ";
	}
	elseif($_SESSION['FiltreAccidentT_ArretTravail']==-1){
		$requete.=" AND  ArretDeTravail=0 ";
	}
	if($_SESSION['FiltreAccidentT_UER']<>0){
		$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_at.Id_Prestation)=".$_SESSION['FiltreAccidentT_UER']." ";
	}
	if($_SESSION['FiltreAccidentT_Personne']<>0){
		$requete.="AND rh_personne_at.Id_Personne=".$_SESSION['FiltreAccidentT_Personne']." ";
	}
}

$requeteOrder="";
if($_SESSION['TriRHAT_General']<>""){
	$requeteOrder="ORDER BY ".substr($_SESSION['TriRHAT_General'],0,-1);
}
$resultRapport=mysqli_query($bdd,$requete2.$requete.$requeteOrder);
$nbRapport=mysqli_num_rows($resultRapport);
if($nbRapport>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($rowAT=mysqli_fetch_array($resultRapport)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}
		
		if($_SESSION["Langue"]=="FR"){$Lieu=$rowAT['LieuAT'];}
		else{$Lieu=$rowAT['LieuATEN'];}
		
		$objet="";
		$req="SELECT Objet,
		(SELECT Libelle FROM rh_typeobjet_at WHERE Id=Id_TypeObjet) AS TypeObjet,
		(SELECT LibelleEN FROM rh_typeobjet_at WHERE Id=Id_TypeObjet) AS TypeObjetEN		
		FROM rh_personne_at_objet 
		WHERE Suppr=0 
		AND Id_Personne_AT=".$rowAT['Id']."
		ORDER BY TypeObjet
		";
		$result=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($result);
		if($nb>0){
			while($row=mysqli_fetch_array($result)){
				if($objet<>""){$objet.="\n";}
				if($_SESSION['Langue']=="FR"){
					$objet.=str_replace("°","",$row['TypeObjet'])." : ".$row['Objet'];
				}
				else{
					$objet.=str_replace("°","",$row['TypeObjetEN'])." : ".$row['Objet'];
				}
			}
		}
		$siege="";
		$req="SELECT Id_SiegeLesion,AutreSiege,Gauche,Droite,
		(SELECT Libelle FROM rh_siege_lesion_at WHERE Id=Id_SiegeLesion) AS SiegeLesion,
		(SELECT LibelleEN FROM rh_siege_lesion_at WHERE Id=Id_SiegeLesion) AS SiegeLesionEN		
		FROM rh_personne_at_siegelesion 
		WHERE Suppr=0 
		AND Id_Personne_AT=".$rowAT['Id']."
		ORDER BY SiegeLesion
		";
		$result=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($result);
		if($nb>0){
			while($row=mysqli_fetch_array($result)){
				if($siege<>""){$siege.="\n";}
				if($_SESSION['Langue']=="FR"){
					$siege.=str_replace("°","",$row['SiegeLesion']);
					
					if($row['Gauche']==1){$siege.=" G ";}
					if($row['Droite']==1){$siege.=" D ";}
				}
				else{
					$siege.=str_replace("°","",$row['SiegeLesionEN']);
					if($row['Gauche']==1){$siege.=" L ";}
					if($row['Droite']==1){$siege.=" R ";}
				}
				if($row['AutreSiege']<>""){$siege.=" : ".$row['AutreSiege'];}
				
			}
		}
		$nature="";
		$req="SELECT AutreNature,
		(SELECT Libelle FROM rh_nature_lesion WHERE Id=Id_NatureLesion) AS Nature,
		(SELECT LibelleEN FROM rh_nature_lesion WHERE Id=Id_NatureLesion) AS NatureEN		
		FROM rh_personne_at_nature_lesion
		WHERE Suppr=0 
		AND Id_PersonneAT=".$rowAT['Id']."
		ORDER BY Nature
		";
		$result=mysqli_query($bdd,$req);
		$nb=mysqli_num_rows($result);
		if($nb>0){
			while($row=mysqli_fetch_array($result)){
				if($nature<>""){$nature.="\n";}
				if($_SESSION['Langue']=="FR"){
					$nature.=str_replace("°","",$row['Nature']);
				}
				else{
					$nature.=str_replace("°","",$row['NatureEN']);
				}
				if($row['AutreNature']<>""){$nature.=" : ".$row['AutreNature'];}
			}
		}
		$arret="";
		if($row['ArretDeTravail']==1){$arret="X";}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($rowAT['Id']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($rowAT['Personne']));
		if($rowAT['DateAT']>'0001-01-01'){
			$date = explode("-",$rowAT['DateAT']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('C'.$ligne,$time);
			$sheet->getStyle('C'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		$sheet->setCellValue('D'.$ligne,utf8_encode($rowAT['HeureAT']));
		$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($rowAT['TypeContrat'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($rowAT['Metier'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($rowAT['LieuAccident'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($rowAT['SIRETClient'])));
		$sheet->setCellValue('I'.$ligne,utf8_encode(substr(stripslashes($rowAT['Prestation']),0,7)));
		$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($Lieu)));
		$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($objet)));
		$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$rowAT['Activite']))));
		$sheet->setCellValue('M'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$rowAT['CommentaireNature']))));
		$sheet->setCellValue('N'.$ligne,utf8_encode(stripslashes($siege)));
		$sheet->setCellValue('O'.$ligne,utf8_encode(stripslashes($nature)));
		$sheet->setCellValue('P'.$ligne,utf8_encode(stripslashes($arret)));
		if($rowAT['DateConnaissanceAT']>'0001-01-01'){
			$date = explode("-",$rowAT['DateConnaissanceAT']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('Q'.$ligne,$time);
			$sheet->getStyle('Q'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		$sheet->setCellValue('R'.$ligne,utf8_encode($rowAT['HeureConnaissanceAT']));
		$sheet->setCellValue('S'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$rowAT['Doutes']))));
		$sheet->setCellValue('T'.$ligne,utf8_encode(stripslashes(str_replace("\\","",$rowAT['AutresInformations']))));
		
		$sheet->getStyle('G'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->getStyle('J'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->getStyle('K'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->getStyle('L'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->getStyle('M'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->getStyle('N'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->getStyle('O'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->getStyle('P'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->getStyle('S'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->getStyle('T'.$ligne)->getAlignment()->setWrapText(true);
		
		$sheet->getStyle('A'.$ligne.':T'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export_AT.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Export_AT.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
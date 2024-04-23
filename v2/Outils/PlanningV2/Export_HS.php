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
	$sheet->setCellValue('A1',utf8_encode('N° demande'));
	$sheet->setCellValue('B1',utf8_encode('Personne'));
	$sheet->setCellValue('C1',utf8_encode('Prestation'));
	$sheet->setCellValue('D1',utf8_encode('Pôle'));
	$sheet->setCellValue('E1',utf8_encode('Date création'));
	$sheet->setCellValue('F1',utf8_encode('Demandeur'));
	$sheet->setCellValue('G1',utf8_encode('Date heure supp.'));
	$sheet->setCellValue('H1',utf8_encode('Nb h. jour'));
	$sheet->setCellValue('I1',utf8_encode('Nb h. nuit'));
	$sheet->setCellValue('J1',utf8_encode('Motif'));
	$sheet->setCellValue('K1',utf8_encode('Etat de la demande'));
	$sheet->setCellValue('L1',utf8_encode('Date de prise en compte (paie)'));
	$sheet->setCellValue('M1',utf8_encode('Raison du refus'));
	$sheet->setCellValue('N1',utf8_encode('Commentaire refus'));
	$sheet->setCellValue('O1',utf8_encode('Temps de travail'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Request number'));
	$sheet->setCellValue('B1',utf8_encode('Person'));
	$sheet->setCellValue('C1',utf8_encode('Site'));
	$sheet->setCellValue('D1',utf8_encode('Pole'));
	$sheet->setCellValue('E1',utf8_encode('Creation Date'));
	$sheet->setCellValue('F1',utf8_encode('Applicant'));
	$sheet->setCellValue('G1',utf8_encode('Date extra hour'));
	$sheet->setCellValue('H1',utf8_encode('Nb h. day'));
	$sheet->setCellValue('I1',utf8_encode('Nb h. night'));
	$sheet->setCellValue('J1',utf8_encode('Reason'));
	$sheet->setCellValue('K1',utf8_encode('Request status'));
	$sheet->setCellValue('L1',utf8_encode('Date of consideration (payroll)'));
	$sheet->setCellValue('M1',utf8_encode('Reason for refusal'));
	$sheet->setCellValue('N1',utf8_encode('Refusal Comment'));
	$sheet->setCellValue('O1',utf8_encode('Time work'));
}
$sheet->getStyle('A1:O1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(25);

$Menu=$_GET['Menu'];

$RH="";
if($Menu==4){
	$RH="RH";	
}

//Liste heures supp.
$requete2="SELECT rh_personne_hs.Id, rh_personne_hs.Nb_Heures_Jour,rh_personne_hs.Nb_Heures_Nuit,DateHS,rh_personne_hs.Etat2,rh_personne_hs.Etat3,
	rh_personne_hs.Etat4,DatePriseEnCompteRH,rh_personne_hs.DateRH,rh_personne_hs.Date1,rh_personne_hs.Id_Prestation,rh_personne_hs.Id_Pole,
	IF(
		rh_personne_hs.Etat4=0 AND rh_personne_hs.Etat3<>-1 AND rh_personne_hs.Etat2<>-1,
		1,
		IF(
			rh_personne_hs.DatePriseEnCompteRH<='0001-01-01' AND rh_personne_hs.Etat4=1 AND rh_personne_hs.Etat3=1 AND rh_personne_hs.Etat2=1,
			2,
			IF(
				rh_personne_hs.Etat4=1 AND rh_personne_hs.DatePriseEnCompteRH>'0001-01-01',
				3,
				IF(
					rh_personne_hs.Etat4=-1 OR rh_personne_hs.Etat3=-1 OR rh_personne_hs.Etat2=-1,
					4,
					5
				)
			)
		)
	)
	AS Etat,Commentaire2,Commentaire3,Commentaire4,
	(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN2) AS RaisonRefus2,
	(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN3) AS RaisonRefus3,
	(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN4) AS RaisonRefus4,Motif,
	(SELECT (SELECT Libelle FROM rh_tempstravail WHERE rh_tempstravail.Id=Id_TempsTravail)
	FROM rh_personne_contrat
	WHERE rh_personne_contrat.Suppr=0
	AND rh_personne_contrat.DateDebut<=rh_personne_hs.Date1
	AND (rh_personne_contrat.DateFin>=rh_personne_hs.Date1 OR rh_personne_contrat.DateFin<='0001-01-01')
	AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
	AND rh_personne_contrat.Id_Personne=rh_personne_hs.Id_Personne
	ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS TempsTravail,
	(SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_hs.Id_Prestation) AS Prestation, 
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_hs.Id_Personne) AS Personne, 
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_hs.Id_Responsable1) AS Demandeur, 
	(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=rh_personne_hs.Id_Pole) AS Pole ";
$requete=" FROM rh_personne_hs
			WHERE (Suppr=0 OR Suppr=1) AND ";
if($Menu==4){
	if(DroitsFormationPlateforme($TableauIdPostesRH)){
		$requete.="(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_hs.Id_Prestation) IN 
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION['Id_Personne']." 
				AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
			)";
	}
}
elseif($Menu==3){
	$requete.="CONCAT(rh_personne_hs.Id_Prestation,'_',rh_personne_hs.Id_Pole) IN 
				(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
				FROM new_competences_personne_poste_prestation 
				WHERE Id_Personne=".$_SESSION["Id_Personne"]."
				AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
				)";
}
elseif($Menu==2){
	$requete.="rh_personne_hs.Id_Personne=".$_SESSION['Id_Personne']." ";
}
if($Menu==4){
	if($_SESSION['FiltreRHHS_RespProjet']<>""){
		$requete.="AND CONCAT(rh_personne_hs.Id_Prestation,'_',rh_personne_hs.Id_Pole) 
					IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
						FROM new_competences_personne_poste_prestation
						WHERE Id_Personne IN (".$_SESSION['FiltreRHHS_RespProjet'].")
						AND Id_Poste IN (".$IdPosteResponsableProjet.")
					)
					";
	}
}
if($_SESSION['FiltreRHHS_Prestation']<>0){
	$requete.=" AND rh_personne_hs.Id_Prestation=".$_SESSION['FiltreRHHS_Prestation']." ";
	if($_SESSION['FiltreRHHS_Pole']<>0){
		$requete.=" AND rh_personne_hs.Id_Pole=".$_SESSION['FiltreRHHS_Pole']." ";
	}
}
if($Menu<>2){
	if($_SESSION['FiltreRHHS_Personne']<>0){
		$requete.=" AND rh_personne_hs.Id_Personne=".$_SESSION['FiltreRHHS_Personne']." ";
	}
}
$requete.="AND YEAR(rh_personne_hs.DateHS)='".$_SESSION['FiltreRHHS_Annee']."' ";
if($_SESSION['FiltreRHHS_Mois']<>0){
	if($_SESSION['FiltreRHHS_MoisCumules']<>""){
		$requete.="AND MONTH(rh_personne_hs.DateHS)>='".$_SESSION['FiltreRHHS_Mois']."' ";
	}
	else{
		$requete.="AND MONTH(rh_personne_hs.DateHS)='".$_SESSION['FiltreRHHS_Mois']."' ";
	}
}

if($_SESSION['FiltreRHHS'.$RH.'_EtatEnCours']<>"" || $_SESSION['FiltreRHHS'.$RH.'_EtatTransmiRH']<>"" || $_SESSION['FiltreRHHS'.$RH.'_EtatValide']<>"" || $_SESSION['FiltreRHHS'.$RH.'_EtatRefuse']<>""){
	$requete.=" AND ( ";
	if($_SESSION['FiltreRHHS'.$RH.'_EtatEnCours']<>""){
		$requete.=" (rh_personne_hs.Etat4=0 AND rh_personne_hs.Etat3<>-1 AND rh_personne_hs.Etat2<>-1) OR ";
	}
	if($_SESSION['FiltreRHHS'.$RH.'_EtatTransmiRH']<>""){
		$requete.=" (rh_personne_hs.DatePriseEnCompteRH<='0001-01-01' AND rh_personne_hs.Etat4=1) OR ";
	}
	if($_SESSION['FiltreRHHS'.$RH.'_EtatValide']<>""){
		$requete.=" (rh_personne_hs.Etat4=1 AND rh_personne_hs.DatePriseEnCompteRH>'0001-01-01') OR ";
	}
	if($_SESSION['FiltreRHHS'.$RH.'_EtatRefuse']<>""){
		$requete.=" (rh_personne_hs.Etat4=-1 OR rh_personne_hs.Etat3=-1 OR rh_personne_hs.Etat2=-1) OR ";
	}
	if($_SESSION['FiltreRHHS'.$RH.'_EtatSupprime']<>""){
		$requete.=" (Suppr=1) OR ";
	}
	$requete=substr($requete,0,-3);
	$requete.=" ) ";
	
	if($_SESSION['FiltreRHHS'.$RH.'_EtatSupprime']<>""){
		//$requete.=" AND (Suppr=0 OR Suppr=1) ";
	}
	else{
		$requete.=" AND Suppr=0 ";
	}
}
else{
	$requete.=" AND ( ";
	$requete.=" (rh_personne_hs.Etat4=0 AND rh_personne_hs.Etat3<>-1 AND rh_personne_hs.Etat2<>-1) ";
	$requete.=" ) ";
	
	if($_SESSION['FiltreRHHS'.$RH.'_EtatSupprime']<>""){
		$requete.=" AND (Suppr=1) ";
	}
	else{
		$requete.=" AND Suppr=0 ";
	}
}
$requeteOrder="";
if($_SESSION['TriRHHS_General']<>""){
	$requeteOrder="ORDER BY ".substr($_SESSION['TriRHHS_General'],0,-1);
}

$resultRapport=mysqli_query($bdd,$requete2.$requete.$requeteOrder);
$nbRapport=mysqli_num_rows($resultRapport);
if($nbRapport>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($rowHS=mysqli_fetch_array($resultRapport)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}
					
		$Etat="";
		$CouleurEtat=$couleur;
		$RaisonRefus="";
		$CommentaireRefus="";
		if($rowHS['Etat4']==1 && $rowHS['DatePriseEnCompteRH']>'0001-01-01'){
			if($_SESSION["Langue"]=="FR"){
				$Etat="Validée et pris en compte sur la paie";}
			else{
				$Etat="Validated and taken into account on payroll";}
			$CouleurEtat="4ba2f1";
		}
		elseif($rowHS['Etat4']==-1 || $rowHS['Etat3']==-1 || $rowHS['Etat2']==-1){
			if($_SESSION["Langue"]=="FR"){
				$Etat="Refusée";}
			else{
				$Etat="Refused";}
			if($rowHS['Etat2']==-1){
				$RaisonRefus=stripslashes($rowHS['RaisonRefus2']);
				$CommentaireRefus=stripslashes($rowHS['Commentaire2']);
			}
			elseif($rowHS['Etat3']==-1){
				$RaisonRefus=stripslashes($rowHS['RaisonRefus3']);
				$CommentaireRefus=stripslashes($rowHS['Commentaire3']);
			}
			elseif($rowHS['Etat4']==-1){
				$RaisonRefus=stripslashes($rowHS['RaisonRefus4']);
				$CommentaireRefus=stripslashes($rowHS['Commentaire4']);
			}
			$CouleurEtat="ff3d3d";
		}
		elseif($rowHS['Etat4']==1 && $rowHS['DatePriseEnCompteRH']<='0001-01-01'){
			if($_SESSION["Langue"]=="FR"){
				$Etat="Transmis aux RH";}
			else{
				$Etat="Submitted to HR";}
			$CouleurEtat="92fb3f";
		}
		elseif($rowHS['Etat4']==0 && $rowHS['Etat3']<>-1 && $rowHS['Etat2']<>-1){
			$n=1;
			if($rowHS['Etat2']==0){$n=2;}
			elseif($rowHS['Etat3']==0){$n=3;}
			elseif($rowHS['Etat4']==0){$n=4;}
			
			if($_SESSION["Langue"]=="FR"){
				$Etat="En attente de pré validation (".$n."/4)";}
			else{
				$Etat="Waiting for pre-validation (".$n."/ 4)";}
			$CouleurEtat="f7f844";
		}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($rowHS['Id']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($rowHS['Personne']));
		$sheet->setCellValue('C'.$ligne,utf8_encode(substr(stripslashes($rowHS['Prestation']),0,7)));
		$sheet->setCellValue('D'.$ligne,utf8_encode($rowHS['Pole']));
		$sheet->setCellValue('E'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($rowHS['Date1'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($rowHS['Demandeur'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($rowHS['DateHS'])));
		$nbHeure="";
		if($rowHS['Nb_Heures_Jour']<>0){$nbHeure=$rowHS['Nb_Heures_Jour'];}
		$sheet->setCellValue('H'.$ligne,utf8_encode($nbHeure));
		$nbHeure="";
		if($rowHS['Nb_Heures_Nuit']<>0){$nbHeure=$rowHS['Nb_Heures_Nuit'];}
		$sheet->setCellValue('I'.$ligne,utf8_encode($nbHeure));
		$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($rowHS['Motif'])));
		$sheet->setCellValue('K'.$ligne,utf8_encode($Etat));
		$sheet->setCellValue('L'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($rowHS['DateRH'])));
		$sheet->setCellValue('M'.$ligne,utf8_encode($RaisonRefus));
		$sheet->setCellValue('N'.$ligne,utf8_encode($CommentaireRefus));
		$sheet->getStyle('N'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('O'.$ligne,utf8_encode(stripslashes($rowHS['TempsTravail'])));
		
		$sheet->getStyle('A'.$ligne.':O'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$sheet->getStyle('K'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$CouleurEtat))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export_HS.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Export_HS.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
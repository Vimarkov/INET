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
	$sheet->setCellValue('G1',utf8_encode('Date astreinte'));
	$sheet->setCellValue('H1',utf8_encode('Intervention'));
	$sheet->setCellValue('I1',utf8_encode('Date de prise en compte (paie)'));
	$sheet->setCellValue('J1',utf8_encode('Etat de la demande'));
	$sheet->setCellValue('K1',utf8_encode('Raison du refus'));
	$sheet->setCellValue('L1',utf8_encode('Commentaire refus'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Request number'));
	$sheet->setCellValue('B1',utf8_encode('Person'));
	$sheet->setCellValue('C1',utf8_encode('Site'));
	$sheet->setCellValue('D1',utf8_encode('Pole'));
	$sheet->setCellValue('E1',utf8_encode('Creation Date'));
	$sheet->setCellValue('F1',utf8_encode('Applicant'));
	$sheet->setCellValue('G1',utf8_encode('Due date'));
	$sheet->setCellValue('H1',utf8_encode('Intervention'));
	$sheet->setCellValue('I1',utf8_encode('Date of consideration (payroll)'));
	$sheet->setCellValue('J1',utf8_encode('Request status'));
	$sheet->setCellValue('K1',utf8_encode('Reason for refusal'));
	$sheet->setCellValue('L1',utf8_encode('Refusal Comment'));
}
$sheet->getStyle('A1:L1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(25);

$Menu=$_GET['Menu'];

$RH="";
if($Menu==4){
	$RH="RH";	
}

$requete2="SELECT rh_personne_rapportastreinte.Id,DateCreation,DateValidationRH,
	rh_personne_rapportastreinte.EtatN2,rh_personne_rapportastreinte.EtatN1,rh_personne_rapportastreinte.DateValidationN1,
	rh_personne_rapportastreinte.DateValidationN2,
	rh_personne_rapportastreinte.Id_Prestation,rh_personne_rapportastreinte.Id_Pole,
	IF(
		rh_personne_rapportastreinte.EtatN2=0 AND rh_personne_rapportastreinte.EtatN1<>-1,
		1,
		IF(
			rh_personne_rapportastreinte.DateValidationRH<='0001-01-01' AND rh_personne_rapportastreinte.EtatN2=1 AND rh_personne_rapportastreinte.EtatN1=1,
			2,
			IF(
				rh_personne_rapportastreinte.EtatN2=1 AND rh_personne_rapportastreinte.DateValidationRH>'0001-01-01',
				3,
				IF(
					rh_personne_rapportastreinte.EtatN2=-1 OR rh_personne_rapportastreinte.EtatN1=-1,
					4,
					5
				)
			)
		)
	)
	AS Etat,RaisonRefusN1 AS Commentaire1,RaisonRefusN2 AS Commentaire2,DateAstreinte,
	TIMEDIFF(HeureFin1,HeureDebut1) AS DiffHeures1,
	TIMEDIFF(HeureFin2,HeureDebut2) AS DiffHeures2,
	TIMEDIFF(HeureFin3,HeureDebut3) AS DiffHeures3,
	Intervention,HeureDebut1,HeureFin1,HeureDebut2,HeureFin2,HeureDebut3,HeureFin3,DatePriseEnCompte,
	(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN1) AS RaisonRefus1,
	(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN2) AS RaisonRefus2,
	(SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_rapportastreinte.Id_Prestation) AS Prestation, 
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_rapportastreinte.Id_Personne) AS Personne, 
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_rapportastreinte.Id_Createur) AS Demandeur, 
	(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=rh_personne_rapportastreinte.Id_Pole) AS Pole ";
$requete=" FROM rh_personne_rapportastreinte
			WHERE rh_personne_rapportastreinte.Suppr=0 AND ";
if($Menu==4){
	if(DroitsFormationPlateforme($TableauIdPostesRH)){
		$requete.="(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_rapportastreinte.Id_Prestation) IN 
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION['Id_Personne']." 
				AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
			)";
	}
}
elseif($Menu==3){
	if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
		$requete.="(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_rapportastreinte.Id_Prestation) IN 
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION['Id_Personne']." 
				AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
			)";
	}
	else{
		$requete.="CONCAT(rh_personne_rapportastreinte.Id_Prestation,'_',rh_personne_rapportastreinte.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$_SESSION["Id_Personne"]."
					AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
					)";
	}
}
elseif($Menu==2){
	$requete.="rh_personne_rapportastreinte.Id_Personne=".$_SESSION['Id_Personne']." ";
}

if($_SESSION['FiltreRHAstreinte_Prestation']<>0){
	$requete.=" AND rh_personne_rapportastreinte.Id_Prestation=".$_SESSION['FiltreRHAstreinte_Prestation']." ";
	if($_SESSION['FiltreRHAstreinte_Pole']<>0){
		$requete.=" AND rh_personne_rapportastreinte.Id_Pole=".$_SESSION['FiltreRHAstreinte_Pole']." ";
	}
}
if($Menu<>2){
	if($_SESSION['FiltreRHAstreinte_Personne']<>0){
		$requete.=" AND rh_personne_rapportastreinte.Id_Personne=".$_SESSION['FiltreRHAstreinte_Personne']." ";
	}
}
if($_SESSION['FiltreRHAstreinte_Mois']<>0){
	$requete.="AND  CONCAT(YEAR(DateAstreinte),'_',IF(MONTH(DateAstreinte)<10,CONCAT('0',MONTH(DateAstreinte)),MONTH(DateAstreinte)))='".$_SESSION['FiltreRHAstreinte_Annee'].'_'.$_SESSION['FiltreRHAstreinte_Mois']."' ";
}
else{
	$requete.="AND YEAR(DateAstreinte)='".$_SESSION['FiltreRHAstreinte_Annee']."' ";
}
if($Menu==4){
	if($_SESSION['FiltreRHAstreinte_RespProjet']<>""){
		$requete.="AND CONCAT(rh_personne_rapportastreinte.Id_Prestation,'_',rh_personne_rapportastreinte.Id_Pole) 
					IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
						FROM new_competences_personne_poste_prestation
						WHERE Id_Personne IN (".$_SESSION['FiltreRHAstreinte_RespProjet'].")
						AND Id_Poste IN (".$IdPosteResponsableProjet.")
					)
					";
	}
}
if($_SESSION['FiltreRHAstreinte'.$RH.'_EtatEnCours']<>"" || $_SESSION['FiltreRHAstreinte'.$RH.'_EtatTransmiRH']<>"" || $_SESSION['FiltreRHAstreinte'.$RH.'_EtatValide']<>"" || $_SESSION['FiltreRHAstreinte'.$RH.'_EtatRefuse']<>""){
	$requete.=" AND ( ";
	if($_SESSION['FiltreRHAstreinte'.$RH.'_EtatEnCours']<>""){
		$requete.=" (rh_personne_rapportastreinte.EtatN2=0 AND rh_personne_rapportastreinte.EtatN1<>-1) OR ";
	}
	if($_SESSION['FiltreRHAstreinte'.$RH.'_EtatTransmiRH']<>""){
		$requete.=" (rh_personne_rapportastreinte.DateValidationRH<='0001-01-01' AND rh_personne_rapportastreinte.EtatN2=1 AND rh_personne_rapportastreinte.EtatN1=1) OR ";
	}
	if($_SESSION['FiltreRHAstreinte'.$RH.'_EtatValide']<>""){
		$requete.=" (rh_personne_rapportastreinte.EtatN2=1 AND rh_personne_rapportastreinte.DateValidationRH>'0001-01-01') OR ";
	}
	if($_SESSION['FiltreRHAstreinte'.$RH.'_EtatRefuse']<>""){
		$requete.=" (rh_personne_rapportastreinte.EtatN2=-1 OR rh_personne_rapportastreinte.EtatN1=-1) OR ";
	}
	$requete=substr($requete,0,-3);
	$requete.=" ) ";
}
else{
	$requete.=" AND ( ";
	$requete.=" (rh_personne_rapportastreinte.EtatN2=0 AND rh_personne_rapportastreinte.EtatN1<>-1) ";
	$requete.=" ) ";
}
$requeteOrder="";
if($_SESSION['TriRHAstreinte_General']<>""){
	$requeteOrder="ORDER BY ".substr($_SESSION['TriRHAstreinte_General'],0,-1);
}

$result=mysqli_query($bdd,$requete2.$requete.$requeteOrder);
$nbDemande=mysqli_num_rows($result);
if($nbDemande>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($rowAst=mysqli_fetch_array($result)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}
		
		$Etat="";
		$CouleurEtat=$couleur;
		$RaisonRefus="";
		$CommentaireRefus="";
		if($rowAst['EtatN2']==1 && $rowAst['DateValidationRH']>'0001-01-01'){
			if($_SESSION["Langue"]=="FR"){
				$Etat="Validée et pris en compte sur la paie";}
			else{
				$Etat="Validated and taken into account on payroll";}
			$CouleurEtat="4ba2f1";
		}
		elseif($rowAst['EtatN2']==-1 || $rowAst['EtatN1']==-1){
			if($_SESSION["Langue"]=="FR"){
				$Etat="Refusée";}
			else{
				$Etat="Refused";}
			if($rowAst['EtatN1']==-1){
				$RaisonRefus=stripslashes($rowAst['RaisonRefus1']);
				$CommentaireRefus=stripslashes($rowAst['Commentaire1']);
			}
			elseif($rowAst['EtatN2']==-1){
				$RaisonRefus=stripslashes($rowAst['RaisonRefus2']);
				$CommentaireRefus=stripslashes($rowAst['Commentaire2']);
			}
			$CouleurEtat="ff3d3d";
		}
		elseif($rowAst['EtatN2']==1 && $rowAst['DateValidationRH']<='0001-01-01'){
			if($_SESSION["Langue"]=="FR"){
				$Etat="Transmis aux RH";}
			else{
				$Etat="Submitted to HR";}
			$CouleurEtat="92fb3f";
		}
		elseif($rowAst['EtatN2']==0 && $rowAst['EtatN1']<>-1){
			$n=1;
			if($rowAst['EtatN1']==0){$n=1;}
			elseif($rowAst['EtatN2']==0){$n=2;}
			if($_SESSION["Langue"]=="FR"){
				$Etat="En attente de pré validation (".$n."/2)";}
			else{
				$Etat="Waiting for pre-validation (".$n."/2)";}
			$CouleurEtat="f7f844";
		}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($rowAst['Id']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($rowAst['Personne']));
		$sheet->setCellValue('C'.$ligne,utf8_encode(substr(stripslashes($rowAst['Prestation']),0,7)));
		$sheet->setCellValue('D'.$ligne,utf8_encode(stripslashes($rowAst['Pole'])));
		if($rowAst['DateCreation']>'0001-01-01'){
			$date = explode("-",$rowAst['DateCreation']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('E'.$ligne,$time);
			$sheet->getStyle('E'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($rowAst['Demandeur'])));
		if($rowAst['DateAstreinte']>'0001-01-01'){
			$date = explode("-",$rowAst['DateAstreinte']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('G'.$ligne,$time);
			$sheet->getStyle('G'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		if($rowAst['Intervention']==1){
			$nbHeureTotal="";
			if($rowAst['DiffHeures1']>0){$nbHeureTotal.=$rowAst['DiffHeures1']." H";}
			if($rowAst['DiffHeures2']>0){if($nbHeureTotal<>""){$nbHeureTotal.="\n";}$nbHeureTotal.=$rowAst['DiffHeures2']." H";}
			if($rowAst['DiffHeures3']>0){if($nbHeureTotal<>""){$nbHeureTotal.="\n";}$nbHeureTotal.=$rowAst['DiffHeures3']." H";}
			
			$sheet->setCellValue('H'.$ligne,utf8_encode($nbHeureTotal));
			
			$sheet->getStyle('H'.$ligne)->getAlignment()->setWrapText(true);
		}
		if($rowAst['DatePriseEnCompte']>'0001-01-01'){
			$date = explode("-",$rowAst['DatePriseEnCompte']);
			$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[0], $date[1], $date[2]);
			$sheet->setCellValue('I'.$ligne,$time);
			$sheet->getStyle('I'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		}
		$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($Etat)));
		$sheet->setCellValue('K'.$ligne,utf8_encode($RaisonRefus));
		$sheet->setCellValue('L'.$ligne,utf8_encode($CommentaireRefus));
		$sheet->getStyle('L'.$ligne)->getAlignment()->setWrapText(true);
		
		$sheet->getStyle('A'.$ligne.':L'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$sheet->getStyle('J'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$CouleurEtat))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export_Astreinte.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Export_Astreinte.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
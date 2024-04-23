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
	$sheet->setCellValue('G1',utf8_encode('Contenu'));
	$sheet->setCellValue('H1',utf8_encode('Etat de la demande'));
	$sheet->setCellValue('I1',utf8_encode('Raison du refus'));
	$sheet->setCellValue('J1',utf8_encode('Commentaire refus'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Request number'));
	$sheet->setCellValue('B1',utf8_encode('Person'));
	$sheet->setCellValue('C1',utf8_encode('Site'));
	$sheet->setCellValue('D1',utf8_encode('Pole'));
	$sheet->setCellValue('E1',utf8_encode('Creation Date'));
	$sheet->setCellValue('F1',utf8_encode('Applicant'));
	$sheet->setCellValue('G1',utf8_encode('Contents'));
	$sheet->setCellValue('H1',utf8_encode('Request status'));
	$sheet->setCellValue('I1',utf8_encode('Reason for refusal'));
	$sheet->setCellValue('J1',utf8_encode('Refusal Comment'));
}
$sheet->getStyle('A1:J1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);

$sheet->getColumnDimension('G')->setWidth(40);
$sheet->getColumnDimension('H')->setWidth(30);
$sheet->getColumnDimension('I')->setWidth(30);
$sheet->getColumnDimension('J')->setWidth(30);

$Menu=$_GET['Menu'];

$RH="";
if($Menu==4){
	$RH="RH";	
}

//Liste
$requete2="SELECT rh_personne_demandeabsence.Id,rh_personne_demandeabsence.DateCreation,rh_personne_demandeabsence.EtatN1,rh_personne_demandeabsence.EtatN2,
	DateValidationRH,rh_personne_demandeabsence.EtatRH,rh_personne_demandeabsence.DateValidationN1,rh_personne_demandeabsence.DateValidationN2,
	rh_personne_demandeabsence.Id_Pole,rh_personne_demandeabsence.Id_Prestation,Commentaire1,Commentaire2,
	(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN1) AS RaisonRefus1,
	(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN2) AS RaisonRefus2,
	(SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_demandeabsence.Id_Prestation) AS Prestation,
	(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=rh_personne_demandeabsence.Id_Pole) AS Pole,
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Personne) AS Personne, 
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Createur) AS Demandeur ";
$requete=" FROM rh_personne_demandeabsence
			WHERE Suppr=0 AND Conge=1 AND ";
if($Menu==4){
	if(DroitsFormationPlateforme($TableauIdPostesRH)){
		$requete.="(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_demandeabsence.Id_Prestation) IN 
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
		$requete.="(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_demandeabsence.Id_Prestation) IN 
			(
				SELECT Id_Plateforme 
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION['Id_Personne']." 
				AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
			)";
	}
	else{
		$requete.="CONCAT(rh_personne_demandeabsence.Id_Prestation,'_',rh_personne_demandeabsence.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$_SESSION["Id_Personne"]."
					AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
					)";
	}
}
elseif($Menu==2){
	$requete.="rh_personne_demandeabsence.Id_Personne=".$_SESSION['Id_Personne']." ";
}

if($_SESSION['FiltreRHConges_Prestation']<>0){
	$requete.=" AND rh_personne_demandeabsence.Id_Prestation=".$_SESSION['FiltreRHConges_Prestation']." ";
	if($_SESSION['FiltreRHConges_Pole']<>0){
		$requete.=" AND rh_personne_demandeabsence.Id_Pole=".$_SESSION['FiltreRHConges_Pole']." ";
	}
}
if($Menu<>2){
	if($_SESSION['FiltreRHConges_Personne']<>0){
		$requete.=" AND rh_personne_demandeabsence.Id_Personne=".$_SESSION['FiltreRHConges_Personne']." ";
	}
}
if($_SESSION['FiltreRHConges_Mois']<>0){
	if($_SESSION['FiltreRHConges_MoisCumules']<>""){
		$requete.="AND (
			SELECT COUNT(rh_absence.Id)
			FROM rh_absence
			WHERE CONCAT(YEAR(rh_absence.DateDebut),'_',IF(MONTH(rh_absence.DateDebut)<10,CONCAT('0',MONTH(rh_absence.DateDebut)),MONTH(rh_absence.DateDebut)))>='".$_SESSION['FiltreRHConges_Annee'].'_'.$_SESSION['FiltreRHConges_Mois']."' 
			AND CONCAT(YEAR(rh_absence.DateFin),'_',IF(MONTH(rh_absence.DateFin)<10,CONCAT('0',MONTH(rh_absence.DateFin)),MONTH(rh_absence.DateFin)))<='".$_SESSION['FiltreRHConges_Annee']."_12'
			AND rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id
		)>0 ";
	}
	else{
	$requete.="AND (
			SELECT COUNT(rh_absence.Id)
			FROM rh_absence
			WHERE CONCAT(YEAR(rh_absence.DateDebut),'_',IF(MONTH(rh_absence.DateDebut)<10,CONCAT('0',MONTH(rh_absence.DateDebut)),MONTH(rh_absence.DateDebut)))<='".$_SESSION['FiltreRHConges_Annee'].'_'.$_SESSION['FiltreRHConges_Mois']."' 
			AND CONCAT(YEAR(rh_absence.DateFin),'_',IF(MONTH(rh_absence.DateFin)<10,CONCAT('0',MONTH(rh_absence.DateFin)),MONTH(rh_absence.DateFin)))>='".$_SESSION['FiltreRHConges_Annee'].'_'.$_SESSION['FiltreRHConges_Mois']."'
			AND rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id
		)>0 ";
	}
}
else{
	$requete.="AND (
					SELECT COUNT(rh_absence.Id)
					FROM rh_absence
					WHERE YEAR(rh_absence.DateDebut)<='".$_SESSION['FiltreRHConges_Annee']."' 
					AND YEAR(rh_absence.DateFin)>='".$_SESSION['FiltreRHConges_Annee']."'
					AND rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id
				)>0 ";
}
if($Menu==4){
	if($_SESSION['FiltreRHConges_RespProjet']<>""){
		$requete.="AND CONCAT(rh_personne_demandeabsence.Id_Prestation,'_',rh_personne_demandeabsence.Id_Pole) 
					IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
						FROM new_competences_personne_poste_prestation
						WHERE Id_Personne IN (".$_SESSION['FiltreRHConges_RespProjet'].")
						AND Id_Poste IN (".$IdPosteResponsableProjet.")
					)
					";
	}
}
if($_SESSION['FiltreRHConges'.$RH.'_EtatEnCours']<>"" || $_SESSION['FiltreRHConges'.$RH.'_EtatTransmiRH']<>"" || $_SESSION['FiltreRHConges'.$RH.'_EtatValide']<>"" || $_SESSION['FiltreRHConges'.$RH.'_EtatRefuse']<>""){
	$requete.=" AND ( ";
	if($_SESSION['FiltreRHConges'.$RH.'_EtatEnCours']<>""){
		$requete.=" (rh_personne_demandeabsence.EtatN2=0 AND rh_personne_demandeabsence.EtatN1<>-1 AND rh_personne_demandeabsence.EtatRH=0) OR ";
	}
	if($_SESSION['FiltreRHConges'.$RH.'_EtatTransmiRH']<>""){
		$requete.=" (rh_personne_demandeabsence.EtatN2=1 AND rh_personne_demandeabsence.EtatN1<>-1 AND rh_personne_demandeabsence.EtatRH=0) OR ";
	}
	if($_SESSION['FiltreRHConges'.$RH.'_EtatValide']<>""){
		$requete.=" (rh_personne_demandeabsence.EtatN2=1 AND rh_personne_demandeabsence.EtatN1=1 AND rh_personne_demandeabsence.EtatRH=1) OR ";
	}
	if($_SESSION['FiltreRHConges'.$RH.'_EtatRefuse']<>""){
		$requete.=" (rh_personne_demandeabsence.EtatN2=-1 OR rh_personne_demandeabsence.EtatN1=-1) OR ";
	}
	$requete=substr($requete,0,-3);
	$requete.=" ) ";
	
	if($_SESSION['FiltreRHConges'.$RH.'_EtatSupprime']<>""){
		$requete.=" AND (Suppr=0 OR Suppr=1) ";
	}
	else{
		$requete.=" AND Suppr=0 ";
	}
}
else{
	if($_SESSION['FiltreRHConges'.$RH.'_EtatSupprime']<>""){
		$requete.=" AND (Suppr=1) ";
	}
	else{
		$requete.=" AND Suppr=0 ";
	}
}

$requeteOrder="";
if($_SESSION['TriRHConges_General']<>""){
	$requeteOrder="ORDER BY ".substr($_SESSION['TriRHConges_General'],0,-1);
}

$resultRapport=mysqli_query($bdd,$requete2.$requete.$requeteOrder);
$nbRapport=mysqli_num_rows($resultRapport);
if($nbRapport>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($resultRapport)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}
					
		$Etat="";
		$CouleurEtat=$couleur;
		$RaisonRefus="";
		$CommentaireRefus="";
		if($row['EtatN2']==1){
			$req="SELECT Id 
				FROM rh_absence 
				WHERE Suppr=0 
				AND Id_Personne_DA=".$row['Id']." 
				AND Id_TypeAbsenceDefinitif>0
				AND Id_TypeAbsenceDefinitif<>Id_TypeAbsenceInitial ";
			$resultAbs=mysqli_query($bdd,$req);
			$nbAbs=mysqli_num_rows($resultAbs);
			if($nbAbs>0){
				if($_SESSION["Langue"]=="FR"){
					$Etat="Modifiées par RH";}
				else{
					$Etat="Modified by HR";}
				$CouleurEtat="ff53ab";
			}
			else{
				if($_SESSION["Langue"]=="FR"){
					$Etat="Validée";}
				else{
					$Etat="Validated";}
				$CouleurEtat="4ba2f1";
			}
		}
		elseif($row['EtatN2']==-1 || $row['EtatN1']==-1){
			if($_SESSION["Langue"]=="FR"){
				$Etat="Refusée";}
			else{
				$Etat="Refused";}
			if($row['EtatN1']==-1){
				$RaisonRefus=stripslashes($row['RaisonRefus1']);
				$CommentaireRefus=stripslashes($row['Commentaire1']);
			}
			elseif($row['EtatN2']==-1){
				$RaisonRefus=stripslashes($row['RaisonRefus2']);
				$CommentaireRefus=stripslashes($row['Commentaire2']);
			}
			$CouleurEtat="ff3d3d";
		}
		elseif($row['EtatN2']==0 && $row['EtatN1']<>-1){
			$n=1;
			if($row['EtatN1']==0){$n=1;}
			elseif($row['EtatN2']==0){$n=2;}
		
			if($_SESSION["Langue"]=="FR"){
				$Etat="En attente de pré validation (".$n."/2)";}
			else{
				$Etat="Waiting for pre-validation (".$n."/2)";}
				$CouleurEtat="f7f844";
		}
		
		$contenu="";
		$req="SELECT DateDebut,DateFin,Id_TypeAbsenceInitial,Id_TypeAbsenceDefinitif,
				(SELECT CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=Id_TypeAbsenceInitial) AS TypeAbsenceIni,
				(SELECT CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=Id_TypeAbsenceDefinitif) AS TypeAbsenceDef,
				NbJour
				FROM rh_absence 
				WHERE Suppr=0 
				AND Id_Personne_DA=".$row['Id']." 
				ORDER BY DateDebut ASC ";
		$resultAbs=mysqli_query($bdd,$req);
		$nbAbs=mysqli_num_rows($resultAbs);
		if($nbAbs>0){
			while($rowAbs=mysqli_fetch_array($resultAbs)){
				if($_SESSION['Langue']=="FR"){
					$contenu.=AfficheDateJJ_MM_AAAA($rowAbs['DateDebut'])." - ".AfficheDateJJ_MM_AAAA($rowAbs['DateFin']);
					if($rowAbs['Id_TypeAbsenceDefinitif']>0 && $rowAbs['Id_TypeAbsenceDefinitif']<>$rowAbs['Id_TypeAbsenceInitial']){
						$contenu.=" (".$rowAbs['NbJour']."";
						$contenu.="-".$rowAbs['TypeAbsenceIni']."-";
						$contenu.=" ".$rowAbs['TypeAbsenceDef'].")";
					}
					else{
						$contenu.=" (".$rowAbs['NbJour']." ".$rowAbs['TypeAbsenceIni'].")";
					}
					$contenu.="\n";
				}
				else{
					$contenu.=AfficheDateJJ_MM_AAAA($rowAbs['DateDebut'])." - ".AfficheDateJJ_MM_AAAA($rowAbs['DateFin']);
					if($rowAbs['Id_TypeAbsenceDefinitif']>0 && $rowAbs['Id_TypeAbsenceDefinitif']<>$rowAbs['Id_TypeAbsenceInitial']){
						$contenu.=" (".$rowAbs['NbJour']."";
						$contenu.="-".$rowAbs['TypeAbsenceIni']."-";
						// $contenu.=" ".$rowAbs['NbJour']." ".$rowAbs['TypeAbsenceDef'].")";
					}
					else{
						$contenu.=" (".$rowAbs['NbJour']." ".$rowAbs['TypeAbsenceIni'].")";
					}
					$contenu.="\n";
				}
			}
		}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Id']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Personne']));
		$sheet->setCellValue('C'.$ligne,utf8_encode(substr(stripslashes($row['Prestation']),0,7)));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['Pole']));
		$sheet->setCellValue('E'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateCreation'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row['Demandeur'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($contenu)));
		$sheet->getStyle('G'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('H'.$ligne,utf8_encode($Etat));
		$sheet->setCellValue('I'.$ligne,utf8_encode($RaisonRefus));
		$sheet->setCellValue('J'.$ligne,utf8_encode($CommentaireRefus));
		$sheet->getStyle('J'.$ligne)->getAlignment()->setWrapText(true);
		
		$sheet->getStyle('A'.$ligne.':J'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$sheet->getStyle('H'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$CouleurEtat))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export_Conges.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Export_Conges.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
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
	$sheet->setCellValue('A1',utf8_encode('N° déclaration'));
	$sheet->setCellValue('B1',utf8_encode('Personne'));
	$sheet->setCellValue('C1',utf8_encode('Prestation'));
	$sheet->setCellValue('D1',utf8_encode('Pôle'));
	$sheet->setCellValue('E1',utf8_encode('Date création'));
	$sheet->setCellValue('F1',utf8_encode('Demandeur'));
	$sheet->setCellValue('G1',utf8_encode('Date début'));
	$sheet->setCellValue('H1',utf8_encode('Date fin'));
	$sheet->setCellValue('I1',utf8_encode('Type'));
	$sheet->setCellValue('J1',utf8_encode('Prévenue'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Declaration number'));
	$sheet->setCellValue('B1',utf8_encode('Person'));
	$sheet->setCellValue('C1',utf8_encode('Site'));
	$sheet->setCellValue('D1',utf8_encode('Pole'));
	$sheet->setCellValue('E1',utf8_encode('Creation Date'));
	$sheet->setCellValue('F1',utf8_encode('Applicant'));
	$sheet->setCellValue('G1',utf8_encode('Start date'));
	$sheet->setCellValue('H1',utf8_encode('End date'));
	$sheet->setCellValue('I1',utf8_encode('Type'));
	$sheet->setCellValue('J1',utf8_encode('Warned'));
}
$sheet->getStyle('A1:J1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);

$Menu=$_GET['Menu'];

//Liste
$requete2="SELECT rh_personne_demandeabsence.Id,rh_personne_demandeabsence.DateCreation,rh_personne_demandeabsence.RealiseParRH,
	rh_personne_demandeabsence.DatePriseEnCompteN1,rh_personne_demandeabsence.DatePriseEnCompteRH,Prevue,
	rh_personne_demandeabsence.Id_Pole,rh_personne_demandeabsence.Id_Prestation,
	(SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_demandeabsence.Id_Prestation) AS Prestation,
	(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=rh_personne_demandeabsence.Id_Pole) AS Pole,
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Personne) AS Personne, 
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Createur) AS Demandeur ";
$requete=" FROM rh_personne_demandeabsence
			WHERE Conge=0
			AND 
			";
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
	
	if($_SESSION['FiltreRHAbsences_EtatPrisEnCompte']<>"" || $_SESSION['FiltreRHAbsences_EtatNonPrisEnCompte']<>""){
		$requete.=" AND ( ";
		if($_SESSION['FiltreRHAbsences_EtatPrisEnCompte']<>""){
			$requete.=" rh_personne_demandeabsence.DatePriseEnCompteRH>'0001-01-01' OR ";
		}
		if($_SESSION['FiltreRHAbsences_EtatNonPrisEnCompte']<>""){
			$requete.=" rh_personne_demandeabsence.DatePriseEnCompteRH<='0001-01-01' OR ";
		}
		$requete=substr($requete,0,-3);
		$requete.=" ) ";
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
	if($_SESSION['FiltreRHAbsences_EtatPrisEnCompte']<>"" || $_SESSION['FiltreRHAbsences_EtatNonPrisEnCompte']<>""){
		$requete.=" AND ( ";
		if($_SESSION['FiltreRHAbsences_EtatPrisEnCompte']<>""){
			$requete.=" rh_personne_demandeabsence.DatePriseEnCompteN1>'0001-01-01' OR ";
		}
		if($_SESSION['FiltreRHAbsences_EtatNonPrisEnCompte']<>""){
			$requete.=" rh_personne_demandeabsence.DatePriseEnCompteN1<='0001-01-01' OR ";
		}
		$requete=substr($requete,0,-3);
		$requete.=" ) ";
	}
}
elseif($Menu==2){
	$requete.="rh_personne_demandeabsence.Id_Personne=".$_SESSION['Id_Personne']." ";
}
if($Menu==4){
	if($_SESSION['FiltreRHAbsences_RespProjet']<>""){
		$requete.="AND CONCAT(rh_personne_demandeabsence.Id_Prestation,'_',rh_personne_demandeabsence.Id_Pole) 
					IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
						FROM new_competences_personne_poste_prestation
						WHERE Id_Personne IN (".$_SESSION['FiltreRHAbsences_RespProjet'].")
						AND Id_Poste IN (".$IdPosteResponsableProjet.")
					)
					";
	}
}
if($_SESSION['FiltreRHAbsences_Prestation']<>0){
	$requete.=" AND rh_personne_demandeabsence.Id_Prestation=".$_SESSION['FiltreRHAbsences_Prestation']." ";
	if($_SESSION['FiltreRHAbsences_Pole']<>0){
		$requete.=" AND rh_personne_demandeabsence.Id_Pole=".$_SESSION['FiltreRHAbsences_Pole']." ";
	}
}
if($Menu<>2){
	if($_SESSION['FiltreRHAbsences_Personne']<>0){
		$requete.=" AND rh_personne_demandeabsence.Id_Personne=".$_SESSION['FiltreRHAbsences_Personne']." ";
	}
}
if($_SESSION['FiltreRHAbsences_Supprime']<>""){
	$requete.=" AND rh_personne_demandeabsence.Suppr=1 ";
}
else{
	$requete.=" AND rh_personne_demandeabsence.Suppr=0 ";
}
if($_SESSION['FiltreRHAbsences_Mois']<>0){
	$requete.="AND (
					SELECT COUNT(rh_absence.Id)
					FROM rh_absence
					WHERE CONCAT(YEAR(rh_absence.DateDebut),'_',IF(MONTH(rh_absence.DateDebut)<10,CONCAT('0',MONTH(rh_absence.DateDebut)),MONTH(rh_absence.DateDebut)))<='".$_SESSION['FiltreRHAbsences_Annee'].'_'.$_SESSION['FiltreRHAbsences_Mois']."' 
					AND CONCAT(YEAR(rh_absence.DateFin),'_',IF(MONTH(rh_absence.DateFin)<10,CONCAT('0',MONTH(rh_absence.DateFin)),MONTH(rh_absence.DateFin)))>='".$_SESSION['FiltreRHAbsences_Annee'].'_'.$_SESSION['FiltreRHAbsences_Mois']."'
					AND rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id
				)>0 ";
}
else{
	$requete.="AND (
					SELECT COUNT(rh_absence.Id)
					FROM rh_absence
					WHERE YEAR(rh_absence.DateDebut)<='".$_SESSION['FiltreRHAbsences_Annee']."' 
					AND YEAR(rh_absence.DateFin)>='".$_SESSION['FiltreRHAbsences_Annee']."'
					AND rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id
				)>0 ";
}

if($_SESSION['FiltreRHAbsences_Prevue']<>"" || $_SESSION['FiltreRHAbsences_NonPrevue']<>""){
	$requete.=" AND ( ";
	if($_SESSION['FiltreRHAbsences_Prevue']<>""){
		$requete.=" rh_personne_demandeabsence.Prevue=1 OR ";
	}
	if($_SESSION['FiltreRHAbsences_NonPrevue']<>""){
		$requete.=" rh_personne_demandeabsence.Prevue=0 OR ";
	}
	$requete=substr($requete,0,-3);
	$requete.=" ) ";
}

$requeteOrder="";
if($_SESSION['TriRHAbsences_General']<>""){
	$requeteOrder="ORDER BY ".substr($_SESSION['TriRHAbsences_General'],0,-1);
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
		
		if($row['Prevue']==0){$CouleurEtat="f51919";$Prevue="";}
		else{$CouleurEtat="60ea34";$Prevue="X";}
		
		$dateDebut="";
		$dateFin="";
		$contenu="";
		$req="SELECT DateDebut,DateFin,Id_TypeAbsenceInitial,Id_TypeAbsenceDefinitif,
				(SELECT CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=Id_TypeAbsenceInitial) AS TypeAbsenceIni,
				(SELECT CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=Id_TypeAbsenceDefinitif) AS TypeAbsenceDef,
				NbJour, HeureDepart, HeureArrivee, NbHeureAbsJour, NbHeureAbsNuit
				FROM rh_absence 
				WHERE Suppr=0 
				AND Id_Personne_DA=".$row['Id']." 
				ORDER BY DateDebut ASC ";
		$resultAbs=mysqli_query($bdd,$req);
		$nbAbs=mysqli_num_rows($resultAbs);
		if($nbAbs>0){
			while($rowAbs=mysqli_fetch_array($resultAbs)){
				$dateDebut="";
				$dateFin="";
				$contenu="";
				if($contenu<>""){$contenu.="\n";}
				if($_SESSION['Langue']=="FR"){
					$dateDebut.=AfficheDateJJ_MM_AAAA($rowAbs['DateDebut']);
					$dateFin.=AfficheDateJJ_MM_AAAA($rowAbs['DateFin']);
					if($rowAbs['Id_TypeAbsenceDefinitif']>0 && $rowAbs['Id_TypeAbsenceDefinitif']<>$rowAbs['Id_TypeAbsenceInitial']){
						$contenu.=" (".$rowAbs['NbJour']."";
						$contenu.=" -".$rowAbs['TypeAbsenceIni']."- ";
						$contenu.=" ".$rowAbs['TypeAbsenceDef'].")";
					}
					else{
						if($rowAbs['Id_TypeAbsenceInitial']>0){$contenu.=" (".$rowAbs['NbJour']." ".$rowAbs['TypeAbsenceIni'].")";}
						else{$contenu.=" (".$rowAbs['NbJour']." ABS)";}
					}
					if($rowAbs['HeureDepart']<>'00:00:00'){$contenu.="\n Heure départ : ".$rowAbs['HeureDepart']." ";}
					elseif($rowAbs['HeureArrivee']<>'00:00:00'){$contenu.="\n Heure arrivée : ".$rowAbs['HeureArrivee']." ";}
					$nbheures=$rowAbs['NbHeureAbsJour']+$rowAbs['NbHeureAbsNuit'];
					if($nbheures>0){
						$contenu.="\n Nb heures absence : ".$nbheures." ";
					}
				}
				else{
					$contenu.=AfficheDateJJ_MM_AAAA($rowAbs['DateDebut'])." - ".AfficheDateJJ_MM_AAAA($rowAbs['DateFin']);
					if($rowAbs['Id_TypeAbsenceDefinitif']>0 && $rowAbs['Id_TypeAbsenceDefinitif']<>$rowAbs['Id_TypeAbsenceInitial']){
						$contenu.=" (".$rowAbs['NbJour']."";
						$contenu.=" -".$rowAbs['TypeAbsenceIni']."- ";
						$contenu.=" ".$rowAbs['NbJour']." ".$rowAbs['TypeAbsenceDef'].")";
					}
					else{
						if($rowAbs['Id_TypeAbsenceInitial']>0){$contenu.=" (".$rowAbs['NbJour']." ".$rowAbs['TypeAbsenceIni'].")";}
						else{$contenu.=" (".$rowAbs['NbJour']." ABS)";}
					}
					if($rowAbs['HeureDepart']<>'00:00:00'){$contenu.="\n Departure time : ".$rowAbs['HeureDepart']." ";}
					elseif($rowAbs['HeureArrivee']<>'00:00:00'){$contenu.="\n Arrival time : ".$rowAbs['HeureArrivee']." ";}
					$nbheures=$rowAbs['NbHeureAbsJour']+$rowAbs['NbHeureAbsNuit'];
					if($nbheures>0){
						$contenu.="\n Number of hours absence : ".$nbheures." ";
					}
				}
				
				$sheet->setCellValue('A'.$ligne,utf8_encode($row['Id']));
				$sheet->setCellValue('B'.$ligne,utf8_encode($row['Personne']));
				$sheet->setCellValue('C'.$ligne,utf8_encode(substr(stripslashes($row['Prestation']),0,7)));
				$sheet->setCellValue('D'.$ligne,utf8_encode($row['Pole']));
				$sheet->setCellValue('E'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateCreation'])));
				$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row['Demandeur'])));
				$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($dateDebut)));
				$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($dateFin)));
				$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($contenu)));
				$sheet->getStyle('I'.$ligne)->getAlignment()->setWrapText(true);
				$sheet->setCellValue('J'.$ligne,utf8_encode($Prevue));

				$sheet->getStyle('A'.$ligne.':J'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
				$sheet->getStyle('I'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$CouleurEtat))));
				$ligne++;
			}
		}
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export_AbsencesInjustifiees.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
$chemin = '../../tmp/Export_AbsencesInjustifiees.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
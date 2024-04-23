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
	$sheet->setCellValue('A1',utf8_encode('Matricule'));
	$sheet->setCellValue('B1',utf8_encode('Contrat'));
	$sheet->setCellValue('C1',utf8_encode('Prestation'));
	$sheet->setCellValue('D1',utf8_encode('Personne'));
	$sheet->setCellValue('E1',utf8_encode('Métier'));
	$sheet->setCellValue('F1',utf8_encode('Type d\'absence'));
	$sheet->setCellValue('G1',utf8_encode('1er jour absence'));
	$sheet->setCellValue('H1',utf8_encode('Dernier jour absence'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Registration number'));
	$sheet->setCellValue('B1',utf8_encode('Contract'));
	$sheet->setCellValue('C1',utf8_encode('Site'));
	$sheet->setCellValue('D1',utf8_encode('Person'));
	$sheet->setCellValue('E1',utf8_encode('Job'));
	$sheet->setCellValue('F1',utf8_encode('Type of absence'));
	$sheet->setCellValue('G1',utf8_encode('1st day away'));
	$sheet->setCellValue('H1',utf8_encode('Last day away'));
}
$sheet->getStyle('A1:H1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);

//Liste
$requete2="SELECT rh_personne_demandeabsence.Id,
	(SELECT LEFT(new_competences_prestation.Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_demandeabsence.Id_Prestation) AS Prestation,
	(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=rh_personne_demandeabsence.Id_Pole) AS Pole,
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Personne) AS Personne,
	(SELECT (SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
			FROM rh_personne_contrat
			WHERE rh_personne_contrat.Suppr=0
			AND rh_personne_contrat.DateDebut<=rh_personne_demandeabsence.DateCreation
			AND (rh_personne_contrat.DateFin>=rh_personne_demandeabsence.DateCreation OR rh_personne_contrat.DateFin<='0001-01-01')
			AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=rh_personne_demandeabsence.Id_Personne
			ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS Contrat,
	(SELECT (SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=rh_personne_contrat.Id_Metier)
			FROM rh_personne_contrat
			WHERE rh_personne_contrat.Suppr=0
			AND rh_personne_contrat.DateDebut<=rh_personne_demandeabsence.DateCreation
			AND (rh_personne_contrat.DateFin>=rh_personne_demandeabsence.DateCreation OR rh_personne_contrat.DateFin<='0001-01-01')
			AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=rh_personne_demandeabsence.Id_Personne
			ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS Metier,
(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Personne) AS MatriculeAAA	";
$requete=" FROM rh_personne_demandeabsence
			WHERE Conge IN (0,1)
			AND 
			";
if(DroitsFormationPlateforme($TableauIdPostesRH)){
	$requete.="(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_demandeabsence.Id_Prestation) IN 
		(
			SELECT Id_Plateforme 
			FROM new_competences_personne_poste_plateforme
			WHERE Id_Personne=".$_SESSION['Id_Personne']." 
			AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
		)";
}

if($_SESSION['FiltreRHSuiviAbsence_EtatPrisEnCompte']<>"" || $_SESSION['FiltreRHSuiviAbsence_EtatNonPrisEnCompte']<>""){
	$requete.=" AND ( ";
	if($_SESSION['FiltreRHSuiviAbsence_EtatPrisEnCompte']<>""){
		$requete.=" ((Conge=0 AND rh_personne_demandeabsence.DatePriseEnCompteRH>'0001-01-01')
		OR (Conge=1 AND EtatN1<>-1 AND EtatN2<>-1 AND EtatRH=1)
		) OR ";
	}
	if($_SESSION['FiltreRHSuiviAbsence_EtatNonPrisEnCompte']<>""){
		$requete.=" ((Conge=0 AND rh_personne_demandeabsence.DatePriseEnCompteRH<='0001-01-01')
		OR (Conge=1 AND EtatN1<>-1 AND EtatN2<>-1 AND EtatRH=0)
		) OR ";
	}
	$requete=substr($requete,0,-3);
	$requete.=" ) ";
}
if($_SESSION['FiltreRHSuiviAbsence_RespProjet']<>""){
	$requete.="AND CONCAT(rh_personne_demandeabsence.Id_Prestation,'_',rh_personne_demandeabsence.Id_Pole) 
		IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
			FROM new_competences_personne_poste_prestation
			WHERE Id_Personne IN (".$_SESSION['FiltreRHSuiviAbsence_RespProjet'].")
			AND Id_Poste IN (".$IdPosteResponsableProjet.")
		)
		";
}
if($_SESSION['FiltreRHSuiviAbsence_Prestation']<>""){
	$requete.=" AND rh_personne_demandeabsence.Id_Prestation IN (".$_SESSION['FiltreRHSuiviAbsence_Prestation'].") ";
}
if($_SESSION['FiltreRHSuiviAbsence_Personne']<>""){
	$requete.=" AND rh_personne_demandeabsence.Id_Personne IN (".$_SESSION['FiltreRHSuiviAbsence_Personne'].") ";
}

if($_SESSION['FiltreRHSuiviAbsence_Supprime']<>""){
	$requete.=" AND rh_personne_demandeabsence.Suppr=1 ";
}
else{
	$requete.=" AND rh_personne_demandeabsence.Suppr=0 ";
}
$requete.="AND (
	SELECT COUNT(rh_absence.Id)
	FROM rh_absence
	";
if($_SESSION['FiltreRHSuiviAbsence_DateFin']>"0001-01-01"){
	$requete.="WHERE rh_absence.DateDebut<='".$_SESSION['FiltreRHSuiviAbsence_DateFin']."' 
	AND rh_absence.DateFin>='".$_SESSION['FiltreRHSuiviAbsence_DateDebut']."' ";
}
else{
	$requete.="WHERE rh_absence.DateFin>='".$_SESSION['FiltreRHSuiviAbsence_DateDebut']."' ";
}
$requete.="AND rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id
AND rh_absence.Suppr=0
)>0 ";

if($_SESSION['FiltreRHSuiviAbsence_Prevue']<>"" || $_SESSION['FiltreRHSuiviAbsence_NonPrevue']<>""){
	$requete.=" AND ( ";
	if($_SESSION['FiltreRHSuiviAbsence_Prevue']<>""){
		$requete.=" rh_personne_demandeabsence.Prevue=1 OR ";
	}
	if($_SESSION['FiltreRHSuiviAbsence_NonPrevue']<>""){
		$requete.=" rh_personne_demandeabsence.Prevue=0 OR ";
	}
	$requete=substr($requete,0,-3);
	$requete.=" ) ";
}

if($_SESSION['FiltreRHSuiviAbsence_TypeAbs']<>""){
	$requete.="AND (
					SELECT COUNT(rh_absence.Id)
					FROM rh_absence
					WHERE IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) IN (".$_SESSION['FiltreRHSuiviAbsence_TypeAbs'].") 
					AND rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id
					AND rh_absence.Suppr=0
				)>0 ";
}

if($_SESSION['FiltreRHSuiviAbsence_Metier']<>""){
	$requete.="AND (SELECT rh_personne_contrat.Id_Metier
		FROM rh_personne_contrat
		WHERE rh_personne_contrat.Suppr=0
		AND rh_personne_contrat.DateDebut<=rh_personne_demandeabsence.DateCreation
		AND (rh_personne_contrat.DateFin>=rh_personne_demandeabsence.DateCreation OR rh_personne_contrat.DateFin<='0001-01-01')
		AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
		AND rh_personne_contrat.Id_Personne=rh_personne_demandeabsence.Id_Personne
		ORDER BY DateDebut DESC, Id DESC LIMIT 1) IN (".$_SESSION['FiltreRHSuiviAbsence_Metier'].")  ";
}

$requeteOrder="ORDER BY Personne";
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
				
				$pole="";
				if($row['Pole']<>""){
					$pole=" - ".$row['Pole'];
				}
				$sheet->setCellValue('A'.$ligne,utf8_encode($row['MatriculeAAA']));
				$sheet->setCellValue('B'.$ligne,utf8_encode($row['Contrat']));
				$sheet->setCellValue('C'.$ligne,utf8_encode($row['Prestation'].$pole));
				$sheet->setCellValue('D'.$ligne,utf8_encode($row['Personne']));
				$sheet->setCellValue('E'.$ligne,utf8_encode($row['Metier']));
				$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($contenu)));
				$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($dateDebut)));
				$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($dateFin)));
				$sheet->getStyle('F'.$ligne)->getAlignment()->setWrapText(true);

				$sheet->getStyle('A'.$ligne.':H'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
				$sheet->getStyle('H'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$CouleurEtat))));
				$ligne++;
			}
		}
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export_Absences.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
$chemin = '../../tmp/Export_Absences.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
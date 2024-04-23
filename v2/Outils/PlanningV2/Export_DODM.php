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
	$sheet->setCellValue('B1',utf8_encode('Prestation'));
	$sheet->setCellValue('C1',utf8_encode('Prestation destination'));
	$sheet->setCellValue('D1',utf8_encode('Personne'));
	$sheet->setCellValue('E1',utf8_encode('Demandeur'));
	$sheet->setCellValue('F1',utf8_encode('Date début'));
	$sheet->setCellValue('G1',utf8_encode('Date fin'));
	$sheet->setCellValue('H1',utf8_encode('Lieu'));
	$sheet->setCellValue('I1',utf8_encode('Frais'));
	$sheet->setCellValue('J1',utf8_encode('Besoins de réservation'));
	$sheet->setCellValue('K1',utf8_encode('Demande avance'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Request number'));
	$sheet->setCellValue('B1',utf8_encode('Site'));
	$sheet->setCellValue('C1',utf8_encode('Destination site'));
	$sheet->setCellValue('D1',utf8_encode('Person'));
	$sheet->setCellValue('E1',utf8_encode('Applicant'));
	$sheet->setCellValue('F1',utf8_encode('Start date'));
	$sheet->setCellValue('G1',utf8_encode('End date'));
	$sheet->setCellValue('H1',utf8_encode('Place'));
	$sheet->setCellValue('I1',utf8_encode('Expenses'));
	$sheet->setCellValue('J1',utf8_encode('Booking needs'));
	$sheet->setCellValue('K1',utf8_encode('Advance application'));
}
$sheet->getStyle('A1:K1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);
$sheet->getColumnDimension('J')->setWidth(40);


$Menu=$_GET['Menu'];

//Liste
$requete2="SELECT rh_personne_petitdeplacement.Id, rh_personne_petitdeplacement.Id_Personne,rh_personne_petitdeplacement.Id_Prestation,rh_personne_petitdeplacement.Id_Pole,
	rh_personne_petitdeplacement.Id_PrestationDeplacement,rh_personne_petitdeplacement.Id_PoleDeplacement,rh_personne_petitdeplacement.DateCreation,rh_personne_petitdeplacement.Id_Createur,
	rh_personne_petitdeplacement.Id_Metier,rh_personne_petitdeplacement.Montant,rh_personne_petitdeplacement.AvancePonctuelle,rh_personne_petitdeplacement.Periode,
	rh_personne_petitdeplacement.DatePriseEnCompteRH,rh_personne_petitdeplacement.DateDebut,rh_personne_petitdeplacement.DateFin,
	CONCAT((SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation),
		IF(Id_Pole>0,' - ','') ,
		IF(Id_Pole>0,(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_Pole),'')
	) AS PrestationDepart,
	CONCAT((SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_PrestationDeplacement),
		IF(Id_PoleDeplacement>0,' - ','') ,
		IF(Id_PoleDeplacement>0,(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_PoleDeplacement),'')
	) AS PrestationDestination,rh_personne_petitdeplacement.FraisReel,rh_personne_petitdeplacement.Lieu,
	IF(Montant>0,1,0) AS DemandeAvance,
	(SELECT new_competences_metier.LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=rh_personne_petitdeplacement.Id_Metier) AS MetierEN,
	(SELECT new_competences_metier.Libelle FROM new_competences_metier WHERE new_competences_metier.Id=rh_personne_petitdeplacement.Id_Metier) AS Metier,
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_petitdeplacement.Id_Createur) AS Demandeur,
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_petitdeplacement.Id_Personne) AS Personne ";
$requete=" FROM rh_personne_petitdeplacement
			WHERE Suppr=0 
			";
if($Menu==4){
	$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_petitdeplacement.Id_Prestation) IN 
		(
			SELECT Id_Plateforme 
			FROM new_competences_personne_poste_plateforme
			WHERE Id_Personne=".$_SESSION['Id_Personne']." 
			AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
		) ";
	
	if($_SESSION['FiltreRHDODM_EtatPrisEnCompte']<>"" || $_SESSION['FiltreRHDODM_EtatNonPrisEnCompte']<>""){
		$requete.=" AND ( ";
		if($_SESSION['FiltreRHDODM_EtatPrisEnCompte']<>""){
			$requete.=" rh_personne_petitdeplacement.DatePriseEnCompteRH>'0001-01-01' OR ";
		}
		if($_SESSION['FiltreRHDODM_EtatNonPrisEnCompte']<>""){
			$requete.=" rh_personne_petitdeplacement.DatePriseEnCompteRH<='0001-01-01' OR ";
		}
		$requete=substr($requete,0,-3);
		$requete.=" ) ";
	}
	else{
		$requete.=" AND ( ";
		$requete.=" rh_personne_petitdeplacement.DatePriseEnCompteRH<='0001-01-01' OR ";
		$requete.=" ) ";
	}
}
elseif($Menu==7){
	$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_petitdeplacement.Id_Prestation) IN 
		(
			SELECT Id_Plateforme 
			FROM new_competences_personne_poste_plateforme
			WHERE Id_Personne=".$_SESSION['Id_Personne']." 
			AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.")
		) ";
		
	if($_SESSION['FiltreRHDODM_EtatPrisEnCompte']<>"" || $_SESSION['FiltreRHDODM_EtatNonPrisEnCompte']<>""){
		$requete.=" AND ( ";
		if($_SESSION['FiltreRHDODM_EtatPrisEnCompte']<>""){
			$requete.=" (SELECT COUNT(rh_personne_petitdeplacement_typebesoin.Id) 
						FROM rh_personne_petitdeplacement_typebesoin
						WHERE rh_personne_petitdeplacement_typebesoin.Suppr=0 
						AND rh_personne_petitdeplacement_typebesoin.ValidationService=0
						AND rh_personne_petitdeplacement_typebesoin.Id_Personne_PetitDeplacement=rh_personne_petitdeplacement.Id
						AND (SELECT ServiceConcerne 
							FROM rh_typebesoin 
							WHERE rh_typebesoin.Id=rh_personne_petitdeplacement_typebesoin.Id_TypeBesoin)='Moyens généraux')=0 OR ";
		}
		if($_SESSION['FiltreRHDODM_EtatNonPrisEnCompte']<>""){
			$requete.=" (SELECT COUNT(rh_personne_petitdeplacement_typebesoin.Id) 
						FROM rh_personne_petitdeplacement_typebesoin
						WHERE rh_personne_petitdeplacement_typebesoin.Suppr=0 
						AND rh_personne_petitdeplacement_typebesoin.ValidationService=0
						AND rh_personne_petitdeplacement_typebesoin.Id_Personne_PetitDeplacement=rh_personne_petitdeplacement.Id
						AND (SELECT ServiceConcerne FROM rh_typebesoin WHERE rh_typebesoin.Id=rh_personne_petitdeplacement_typebesoin.Id_TypeBesoin)='Moyens généraux')>0 OR ";
		}
		$requete=substr($requete,0,-3);
		$requete.=" ) ";
	}
	else{
		$requete.=" AND (SELECT COUNT(rh_personne_petitdeplacement_typebesoin.Id) 
						FROM rh_personne_petitdeplacement_typebesoin
						WHERE rh_personne_petitdeplacement_typebesoin.Suppr=0 
						AND rh_personne_petitdeplacement_typebesoin.ValidationService=0
						AND rh_personne_petitdeplacement_typebesoin.Id_Personne_PetitDeplacement=rh_personne_petitdeplacement.Id
						AND (SELECT ServiceConcerne FROM rh_typebesoin WHERE rh_typebesoin.Id=rh_personne_petitdeplacement_typebesoin.Id_TypeBesoin)='Moyens généraux')>0 ";
		
	}
}
elseif($Menu==8){
	$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_petitdeplacement.Id_Prestation) IN 
		(
			SELECT Id_Plateforme 
			FROM new_competences_personne_poste_plateforme
			WHERE Id_Personne=".$_SESSION['Id_Personne']." 
			AND Id_Poste IN (".$IdPosteAssistantAdministratif.")
		) ";
		
		if($_SESSION['FiltreRHDODM_EtatPrisEnCompte']<>"" || $_SESSION['FiltreRHDODM_EtatNonPrisEnCompte']<>""){
			$requete.=" AND ( ";
			if($_SESSION['FiltreRHDODM_EtatPrisEnCompte']<>""){
				$requete.=" ((SELECT COUNT(rh_personne_petitdeplacement_typebesoin.Id) 
							FROM rh_personne_petitdeplacement_typebesoin
							WHERE rh_personne_petitdeplacement_typebesoin.Suppr=0 
							AND rh_personne_petitdeplacement_typebesoin.ValidationService=0
							AND rh_personne_petitdeplacement_typebesoin.Id_Personne_PetitDeplacement=rh_personne_petitdeplacement.Id
							AND (SELECT ServiceConcerne 
								FROM rh_typebesoin 
								WHERE rh_typebesoin.Id=rh_personne_petitdeplacement_typebesoin.Id_TypeBesoin)='Accueil')=0
							AND (Montant=0 OR (Montant>0 AND DatePriseEnCompteAvance>'0001-01-01'))
							) OR ";
			}
			if($_SESSION['FiltreRHDODM_EtatNonPrisEnCompte']<>""){
				$requete.=" ((SELECT COUNT(rh_personne_petitdeplacement_typebesoin.Id) 
							FROM rh_personne_petitdeplacement_typebesoin
							WHERE rh_personne_petitdeplacement_typebesoin.Suppr=0 
							AND rh_personne_petitdeplacement_typebesoin.ValidationService=0
							AND rh_personne_petitdeplacement_typebesoin.Id_Personne_PetitDeplacement=rh_personne_petitdeplacement.Id
							AND (SELECT ServiceConcerne FROM rh_typebesoin WHERE rh_typebesoin.Id=rh_personne_petitdeplacement_typebesoin.Id_TypeBesoin)='Accueil')>0 
							OR (Montant>0 AND DatePriseEnCompteAvance<='0001-01-01')
							) OR ";
			}
			$requete=substr($requete,0,-3);
			$requete.=" ) ";
		}
		else{
			$requete.=" AND ((SELECT COUNT(rh_personne_petitdeplacement_typebesoin.Id) 
							FROM rh_personne_petitdeplacement_typebesoin
							WHERE rh_personne_petitdeplacement_typebesoin.Suppr=0 
							AND rh_personne_petitdeplacement_typebesoin.ValidationService=0
							AND rh_personne_petitdeplacement_typebesoin.Id_Personne_PetitDeplacement=rh_personne_petitdeplacement.Id
							AND (SELECT ServiceConcerne FROM rh_typebesoin WHERE rh_typebesoin.Id=rh_personne_petitdeplacement_typebesoin.Id_TypeBesoin)='Accueil')>0 
							OR (Montant>0 AND DatePriseEnCompteAvance<='0001-01-01')
							) ";
		}
}
elseif($Menu==3){
	if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
		$requete.=" AND (SELECT COUNT(rh_personne_petitdeplacement.Id) 
				FROM rh_personne_petitdeplacement
				WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_petitdeplacement.Id_Prestation) IN 
				(
					SELECT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$_SESSION['Id_Personne']." 
					AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
				))>0 ";
	}
	else{
		$requete.=" AND (SELECT COUNT(rh_personne_petitdeplacement.Id)
			FROM rh_personne_petitdeplacement
			WHERE CONCAT(rh_personne_petitdeplacement.Id_Prestation,'_',rh_personne_petitdeplacement.Id_Pole) IN 
				(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
				FROM new_competences_personne_poste_prestation 
				WHERE Id_Personne=".$_SESSION["Id_Personne"]."
				AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
				)
			)>0 ";
	}
	if($_SESSION['FiltreRHDODM_EtatPrisEnCompte']<>"" || $_SESSION['FiltreRHDODM_EtatNonPrisEnCompte']<>""){					
		$requete.=" AND ( ";
		if($_SESSION['FiltreRHDODM_EtatPrisEnCompte']<>""){
			$requete.=" rh_personne_petitdeplacement.DatePriseEnCompteN1>'0001-01-01' OR ";
		}
		if($_SESSION['FiltreRHDODM_EtatNonPrisEnCompte']<>""){
			$requete.=" rh_personne_petitdeplacement.DatePriseEnCompteN1<='0001-01-01' OR ";
		}
		$requete=substr($requete,0,-3);
		$requete.=" ) ";
	}
	else{
		$requete.=" AND ( ";
		$requete.=" rh_personne_petitdeplacement.DatePriseEnCompteN1<='0001-01-01' ";
		$requete.=" ) ";
	}
}
if($_SESSION['FiltreRHDODM_PrestationDep']<>0){
	$requete.=" AND CONCAT(rh_personne_petitdeplacement.Id_Prestation,'_',rh_personne_petitdeplacement.Id_Pole)='".$_SESSION['FiltreRHDODM_PrestationDep']."' ";
}
if($_SESSION['FiltreRHDODM_PrestationDes']<>0){
	$requete.=" AND CONCAT(rh_personne_petitdeplacement.Id_PrestationDeplacement,'_',rh_personne_petitdeplacement.Id_PoleDeplacement)='".$_SESSION['FiltreRHDODM_PrestationDes']."' ";
}
if($_SESSION['FiltreRHDODM_Personne']<>0){
	$requete.=" AND rh_personne_petitdeplacement.Id_Personne=".$_SESSION['FiltreRHDODM_Personne']." ";
}
if($Menu==4){
	if($_SESSION['FiltreRHDODM_RespProjet']<>""){
		$requete.="AND CONCAT(rh_personne_petitdeplacement.Id_Prestation,'_',rh_personne_petitdeplacement.Id_Pole) 
					IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
						FROM new_competences_personne_poste_prestation
						WHERE Id_Personne IN (".$_SESSION['FiltreRHDODM_RespProjet'].")
						AND Id_Poste IN (".$IdPosteResponsableProjet.")
					)
					";
	}
}
if($_SESSION['FiltreRHDODM_Mois']<>0){
	if($_SESSION['FiltreRHDODM_MoisCumules']<>""){
		$requete.="AND CONCAT(YEAR(rh_personne_petitdeplacement.DateDebut),'_',IF(MONTH(rh_personne_petitdeplacement.DateDebut)<10,CONCAT('0',MONTH(rh_personne_petitdeplacement.DateDebut)),MONTH(rh_personne_petitdeplacement.DateDebut)))>='".$_SESSION['FiltreRHDODM_Annee'].'_'.$_SESSION['FiltreRHDODM_Mois']."' 
			AND CONCAT(YEAR(rh_personne_petitdeplacement.DateFin),'_',IF(MONTH(rh_personne_petitdeplacement.DateFin)<10,CONCAT('0',MONTH(rh_personne_petitdeplacement.DateFin)),MONTH(rh_personne_petitdeplacement.DateFin)))<='".$_SESSION['FiltreRHDODM_Annee']."_12'
		";
	}
	else{
		$requete.="AND CONCAT(YEAR(rh_personne_petitdeplacement.DateDebut),'_',IF(MONTH(rh_personne_petitdeplacement.DateDebut)<10,CONCAT('0',MONTH(rh_personne_petitdeplacement.DateDebut)),MONTH(rh_personne_petitdeplacement.DateDebut)))>='".$_SESSION['FiltreRHDODM_Annee'].'_'.$_SESSION['FiltreRHDODM_Mois']."' 
			AND CONCAT(YEAR(rh_personne_petitdeplacement.DateFin),'_',IF(MONTH(rh_personne_petitdeplacement.DateFin)<10,CONCAT('0',MONTH(rh_personne_petitdeplacement.DateFin)),MONTH(rh_personne_petitdeplacement.DateFin)))<='".$_SESSION['FiltreRHDODM_Annee'].'_'.$_SESSION['FiltreRHDODM_Mois']."'
		";
	}
}
else{
	$requete.="AND  YEAR(rh_personne_petitdeplacement.DateDebut)<='".$_SESSION['FiltreRHDODM_Annee']."' 
			AND YEAR(rh_personne_petitdeplacement.DateFin)>='".$_SESSION['FiltreRHDODM_Annee']."' ";
}
$requeteOrder="";
if($_SESSION['TriRHDODM_General']<>""){
	$requeteOrder="ORDER BY ".substr($_SESSION['TriRHDODM_General'],0,-1);
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
		
		$Etat="";
		if($row['DatePriseEnCompteRH']>'0001-01-01'){
			$Etat="X";
		}
		
		if($_SESSION["Langue"]=="FR"){$frais="Calendaires";}else{$frais= "Calendar";}
		if($row['FraisReel']==1){
			if($_SESSION["Langue"]=="FR"){$frais="Réels";}else{$frais= "Real";}
		}
		
		$demandeAvance="";
		if($row['Montant']>0){
			if($_SESSION["Langue"]=="FR"){$demandeAvance="Oui";}else{$demandeAvance="Yes";}
		}
		
		$besoinReservation="";
		if($_SESSION["Langue"]=="FR"){
			$req="SELECT 
				(SELECT Libelle FROM rh_typebesoin WHERE rh_typebesoin.Id=Id_TypeBesoin) AS TypeBesoin,
				(SELECT ServiceConcerne FROM rh_typebesoin WHERE rh_typebesoin.Id=Id_TypeBesoin) AS ServiceConcerne,
				ValidationService
				FROM rh_personne_petitdeplacement_typebesoin 
				WHERE Suppr=0 
				AND Id_Personne_PetitDeplacement=".$row['Id'];
		}
		else{
			$req="SELECT 
				(SELECT LibelleEN FROM rh_typebesoin WHERE rh_typebesoin.Id=Id_TypeBesoin) AS TypeBesoin,
				(SELECT ServiceConcerne FROM rh_typebesoin WHERE rh_typebesoin.Id=Id_TypeBesoin) AS ServiceConcerne,
				ValidationService
				FROM rh_personne_petitdeplacement_typebesoin 
				WHERE Suppr=0 
				AND Id_Personne_PetitDeplacement=".$row['Id'];
		}
		$resultBesoins=mysqli_query($bdd,$req);
		$nbBesoins=mysqli_num_rows($resultBesoins);
		
		$img="X";
		if($nbBesoins>0){
			$besoinReservation.="";
			while($rowBesoins=mysqli_fetch_array($resultBesoins)){
				$fait="";
				if($rowBesoins['ValidationService']==1){$fait=$img;}
				$besoinReservation.="".$rowBesoins['TypeBesoin']." ".$fait."\n";
			}
		}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Id']));
		$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($row['PrestationDepart'])));
		$sheet->setCellValue('C'.$ligne,utf8_encode(stripslashes($row['PrestationDestination'])));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['Personne']));
		$sheet->setCellValue('E'.$ligne,utf8_encode(stripslashes($row['Demandeur'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateDebut'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateFin'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode(stripslashes($row['Lieu'])));
		$sheet->setCellValue('I'.$ligne,utf8_encode(stripslashes($frais)));
		$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($besoinReservation)));
		$sheet->getStyle('J'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($demandeAvance)));

		$sheet->getStyle('A'.$ligne.':K'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export_DODM.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Export_DODM.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
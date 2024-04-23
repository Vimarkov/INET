<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
						
//Ligne En-tete
if($_SESSION['Langue']=="FR"){
	$sheet->setCellValue('A1',utf8_encode('N� Offre'));
	$sheet->setCellValue('B1',utf8_encode('Ref'));
	$sheet->setCellValue('C1',utf8_encode('Date apparition offre'));
	$sheet->setCellValue('D1',utf8_encode('Demandeur'));
	$sheet->setCellValue('E1',utf8_encode('Nombre de poste'));
	$sheet->setCellValue('F1',utf8_encode('Validation DG'));
	$sheet->setCellValue('G1',utf8_encode('Type de poste'));
	$sheet->setCellValue('H1',utf8_encode('M�tier'));
	$sheet->setCellValue('I1',utf8_encode('Statut'));
	$sheet->setCellValue('J1',utf8_encode("Unit� d'exploitation"));
	$sheet->setCellValue('K1',utf8_encode('Lieu'));
	$sheet->setCellValue('L1',utf8_encode('Domaine'));
	$sheet->setCellValue('M1',utf8_encode('Date d�marrage'));
	$sheet->setCellValue('N1',utf8_encode('Statut du poste'));
	$sheet->setCellValue('O1',utf8_encode('Nombre'));
	$sheet->setCellValue('P1',utf8_encode('Mise � jour annonce'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Offer No.'));
	$sheet->setCellValue('B1',utf8_encode('Ref'));
	$sheet->setCellValue('C1',utf8_encode('Offer appearance date'));
	$sheet->setCellValue('D1',utf8_encode('Requester'));
	$sheet->setCellValue('E1',utf8_encode('Number of post'));
	$sheet->setCellValue('F1',utf8_encode('DG Validation'));
	$sheet->setCellValue('G1',utf8_encode('Position type'));
	$sheet->setCellValue('H1',utf8_encode('Job'));
	$sheet->setCellValue('I1',utf8_encode('Status'));
	$sheet->setCellValue('J1',utf8_encode('Operating unit'));
	$sheet->setCellValue('K1',utf8_encode('Place'));
	$sheet->setCellValue('L1',utf8_encode('Domain'));
	$sheet->setCellValue('M1',utf8_encode('Start date'));
	$sheet->setCellValue('N1',utf8_encode('Job status'));
	$sheet->setCellValue('O1',utf8_encode('Number'));
	$sheet->setCellValue('P1',utf8_encode('Announcement update'));
	
}

$sheet->getStyle('A1:P1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

$sheet->getDefaultColumnDimension()->setWidth(20);

if($_SESSION["Langue"]=="FR"){
	$reqSuite="IF(ValidationContratDG>0,'OUI',IF(ValidationContratDG=0,'','NON')) AS Etat, 
		IF(ValidationContratDG=0,'',
			IF(ValidationContratDG=-1,'',
				IF(EtatPoste=0,'Poste ouvert',IF(EtatPoste=1,'Poste pourvu',IF(EtatPoste=2,'Poste non pourvu',IF(EtatPoste=3,'Poste pourvu partiellement',IF(EtatPoste=4,'Demande cl�tur�e','Poste annul�')))))
			)
		) AS Statut2, ";
}
else{
	$reqSuite="IF(ValidationContratDG>0,'YES',IF(ValidationContratDG=0,'','NO')) AS Etat, 
		IF(ValidationContratDG=0,'',
			IF(ValidationContratDG=-1,'',
				IF(EtatPoste=0,'Open post',IF(EtatPoste=1,'Position filled',IF(EtatPoste=2,'Position not filled',IF(EtatPoste=3,'Position partially filled',IF(EtatPoste=4,'Request closed','Position canceled')))))
			)
		) AS Statut2, ";
}
$requeteAnalyse="SELECT Id ";
$requete2="SELECT Id,DateDemande,Id_Demandeur,Id_Prestation,Metier,Lieu,RaisonRefus,Suppr,Division,Nombre AS NombrePoste,
	CONCAT(Metier,'-',
	Lieu,'-',
	Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',IF(DateRecrutement=0,DATE_FORMAT(DateDemande,'%d%m%y'),DATE_FORMAT(DateRecrutement,'%d%m%y'))
	) AS Ref,RaisonRefus,RaisonRefusRecrutement,RaisonRefusApprobation,
	EtatApprobation,ValidationContratDG,DateRecrutementMAJ,EtatPoste,DateRecrutement,IF(DateActualisation>'0001-01-01',DateActualisation,DateValidationDG) AS DateValidationDG,
	".$reqSuite."
	DateBesoin,Duree,PosteDefinitif,OuvertureAutresPlateformes,CategorieProf,
	(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
	(SELECT Libelle FROM talentboost_domaine WHERE Id=Id_Domaine) AS Domaine,
	(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
	Id_Plateforme,
	(SELECT COUNT(Id) FROM talentboost_candidature WHERE Id_Annonce=talentboost_annonce.Id AND talentboost_candidature.Suppr=0) AS Nombre,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,EtatValidation ";
$requete=" FROM talentboost_annonce
			WHERE Suppr=0  ";
if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
	$requete.="  AND OuvertureAutresPlateformes=1 ";
}
else{
	$requete.="  AND (
					  talentboost_annonce.Id_Plateforme IN 
						(
							SELECT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION['Id_Personne']." 
							AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") 
						)
					OR 
						Id_Prestation IN 
						(SELECT Id_Prestation
						FROM new_competences_personne_poste_prestation 
						WHERE Id_Personne=".$_SESSION["Id_Personne"]."
						AND Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.") 
						)
			  ) ";
			  
}
if($_SESSION['FiltreRecrutBesoin_Plateforme']<>0){
	$requete.=" AND Id_Plateforme=".$_SESSION['FiltreRecrutBesoin_Plateforme']." ";
}
if($_SESSION['FiltreRecrutBesoin_Prestation']<>0){
	$requete.=" AND talentboost_annonce.Id_Prestation=".$_SESSION['FiltreRecrutBesoin_Prestation']." ";
}
if($_SESSION['FiltreRecrutBesoin_Metier']<>""){
	$requete.=" AND talentboost_annonce.Metier LIKE \"%".$_SESSION['FiltreRecrutBesoin_Metier']."%\" ";
}
if($_SESSION['FiltreRecrutBesoin_Demandeur']<>0){
	$requete.=" AND talentboost_annonce.Id_Demandeur=".$_SESSION['FiltreRecrutBesoin_Demandeur']." ";
}
if($_SESSION['FiltreRecrutBesoin_Domaine']<>0){
	$requete.=" AND talentboost_annonce.Id_Domaine=".$_SESSION['FiltreRecrutBesoin_Domaine']." ";
}
if($_SESSION['FiltreRecrutBesoin_Programme']<> "0"){
	$requete.=" AND talentboost_annonce.Programme= \"".$_SESSION['FiltreRecrutBesoin_Programme']."\" ";
}
if($_SESSION['FiltreRecrutBesoin_Etat']<>""){
	if($_SESSION["Langue"]=="FR"){
		$requete.=" AND ValidationContratDG=".$_SESSION['FiltreRecrutBesoin_Etat']." ";
	}
	else{
		$requete.=" AND ValidationContratDG=".$_SESSION['FiltreRecrutBesoin_Etat']." ";
	}
}
if($_SESSION['FiltreRecrutBesoin_Statut']<>-2 && $_SESSION['FiltreRecrutBesoin_Statut']<>-3){
	$requete.=" AND IF(EtatValidation=0,-2,
			IF(EtatValidation=-1,-2,
				IF(EtatValidation=1 && EtatApprobation=0,-2,
					IF(EtatValidation=1 && EtatApprobation=-1,-2,
							IF(EtatValidation=1 && EtatApprobation=1 && ValidationContratDG=0 && OuvertureAutresPlateformes=0,IF(EtatPoste=0,0,IF(EtatPoste=1,1,IF(EtatPoste=2,2,IF(EtatPoste=3,3,-1)))),
							  IF(EtatValidation=1 && EtatApprobation=1 && ValidationContratDG=0 && OuvertureAutresPlateformes=1,-2,
								IF(EtatValidation=1 && EtatApprobation=1 && ValidationContratDG=-1,'',IF(EtatPoste=0,0,IF(EtatPoste=1,1,IF(EtatPoste=2,2,IF(EtatPoste=3,3,-1)))))
								)
							)
						)
					)
				)
			)=".$_SESSION['FiltreRecrutBesoin_Statut']." ";
}
elseif($_SESSION['FiltreRecrutBesoin_Statut']==-3){
	$requete.=" AND IF(EtatValidation=0,-2,
			IF(EtatValidation=-1,-2,
				IF(EtatValidation=1 && EtatApprobation=0,-2,
					IF(EtatValidation=1 && EtatApprobation=-1,-2,
							IF(EtatValidation=1 && EtatApprobation=1 && ValidationContratDG=0 && OuvertureAutresPlateformes=0,IF(EtatPoste=0,0,IF(EtatPoste=1,1,IF(EtatPoste=2,2,IF(EtatPoste=3,3,-1)))),
							  IF(EtatValidation=1 && EtatApprobation=1 && ValidationContratDG=0 && OuvertureAutresPlateformes=1,-2,
								IF(EtatValidation=1 && EtatApprobation=1 && ValidationContratDG=-1,'',IF(EtatPoste=0,0,IF(EtatPoste=1,1,IF(EtatPoste=2,2,IF(EtatPoste=3,3,-1)))))
								)
							)
						)
					)
				)
			) IN (2,3) ";
}
if($_SESSION['FiltreRecrutBesoin_DateDemarrage']>"0001-01-01" && $_SESSION['FiltreRecrutBesoin_DateDemarrage']<>""){
	$requete.=" AND talentboost_annonce.DateBesoin".$_SESSION['FiltreRecrutBesoin_SigneDateDemarrage']." '".$_SESSION['FiltreRecrutBesoin_DateDemarrage']."' ";
}
if($_SESSION['FiltreRecrutBesoin_Information']<>""){
	$requete.=" AND (
		Lieu LIKE \"%".$_SESSION['FiltreRecrutBesoin_Information']."%\"
		OR CategorieProf LIKE \"%".$_SESSION['FiltreRecrutBesoin_Information']."%\"
		OR DescriptifPoste LIKE \"%".$_SESSION['FiltreRecrutBesoin_Information']."%\"
		OR SavoirFaire LIKE \"%".$_SESSION['FiltreRecrutBesoin_Information']."%\"
		OR SavoirEtre LIKE \"%".$_SESSION['FiltreRecrutBesoin_Information']."%\"
		OR Prerequis LIKE \"%".$_SESSION['FiltreRecrutBesoin_Information']."%\"
		OR Diplome LIKE \"%".$_SESSION['FiltreRecrutBesoin_Information']."%\"
		OR Langue LIKE \"%".$_SESSION['FiltreRecrutBesoin_Information']."%\"
	) ";
}
$requeteOrder="";
if($_SESSION['TriRecrutBesoin_General']<>""){
	$requeteOrder="ORDER BY ".substr($_SESSION['TriRecrutBesoin_General'],0,-1);
}

$resultRapport=mysqli_query($bdd,$requete2.$requete.$requeteOrder);
$nbRapport=mysqli_num_rows($resultRapport);

if($nbRapport>0){
	$couleur="EEEEEE";
	$ligne = 2;
	while($row=mysqli_fetch_array($resultRapport)){
		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Id']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Ref']));
		if($row['ValidationContratDG']>0){
			$sheet->setCellValue('C'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateValidationDG'])));
		}
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['Demandeur']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['NombrePoste']));	
		$sheet->setCellValue('F'.$ligne,utf8_encode($row['Etat']));		
		if($row['PosteDefinitif']==1){
			$sheet->setCellValue('G'.$ligne,utf8_encode("Poste d�finitif"));
		}
		elseif($row['PosteDefinitif']==0){
			$sheet->setCellValue('G'.$ligne,utf8_encode("Mission"));
		}
		elseif($row['PosteDefinitif']==2){
			$sheet->setCellValue('G'.$ligne,utf8_encode("CDD 6 mois"));
		}
		elseif($row['PosteDefinitif']==3){
			$sheet->setCellValue('G'.$ligne,utf8_encode("CDD 2 mois"));
		}
		elseif($row['PosteDefinitif']==4){
			$sheet->setCellValue('G'.$ligne,utf8_encode("CDD"));
		}
		$sheet->setCellValue('H'.$ligne,utf8_encode($row['Metier']));
		$sheet->setCellValue('I'.$ligne,utf8_encode($row['CategorieProf']));
		$sheet->setCellValue('J'.$ligne,utf8_encode($row['Plateforme']));
		$sheet->setCellValue('K'.$ligne,utf8_encode($row['Lieu']));
		$sheet->setCellValue('L'.$ligne,utf8_encode($row['Domaine']));
		$sheet->setCellValue('M'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateBesoin'])));
		$sheet->setCellValue('N'.$ligne,utf8_encode($row['Statut2']));
		$sheet->setCellValue('O'.$ligne,utf8_encode($row['Nombre']));
		$sheet->setCellValue('P'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row['DateRecrutementMAJ'])));
	
		$sheet->getStyle('A'.$ligne.':P'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
				
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
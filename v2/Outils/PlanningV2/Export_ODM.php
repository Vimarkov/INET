<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('Template_ODM.xlsx');
$sheet = $excel->getSheetByName('Feuil1');


$req="SELECT Id,Id_ContratInitial,Id_Personne,Id_TypeContrat,Id_AgenceInterim,Id_Metier,SalaireReference,TypeCoeff,CoeffFacturationAgence,SalaireBrut,ChampsModifie,
	(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS TypeContrat,Id_Responsable,
	(SELECT LibelleEN FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS TypeContratEN,
	TauxHoraire,DateDebut,DateFin,DateFinPeriodeEssai,Id_TempsTravail,Id_Prestation,Id_Pole,TypeDocument,DateCreation,Id_Createur,
	DateSouplessePositive,DateSouplesseNegative,Remarque,Id_LieuTravail,Id_Client,
	Id_Responsable,MontantIPD,MontantRepas,MontantIGD,MontantRepasGD,
	FraisReel,PrimeResponsabilite,IndemniteOutillage,MajorationVSD,PrimeEquipe,PanierGrandeNuit,PanierVSD,Motif,
	(SELECT Libelle FROM rh_client WHERE rh_client.Id=Id_Client) AS Client,
	(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
	(SELECT LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS MetierEN,
	(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation) AS Id_Plateforme,
	(SELECT Code_Analytique FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation) AS CodeAnalytique,
	(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation) AS Prestation,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Responsable) AS ResponsableAAA,
	(SELECT (SELECT NumTel FROM new_competences_plateforme WHERE new_competences_plateforme.Id=new_competences_prestation.Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation) AS TelehonePlateforme, 
	(SELECT (SELECT Adresse FROM new_competences_plateforme WHERE new_competences_plateforme.Id=new_competences_prestation.Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation) AS AdressePlateforme, 
	(SELECT Adresse FROM new_competences_pole WHERE new_competences_pole.Id=rh_personne_contrat.Id_Pole) AS AdressePole,
	(SELECT Adresse FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation) AS AdressePrestation,
	(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=new_competences_prestation.Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation) AS Plateforme,
	(SELECT Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne) AS Nom, 
	(SELECT Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne) AS Prenom
	FROM rh_personne_contrat 
	WHERE Id=".$_GET['Id']."";
$result=mysqli_query($bdd,$req);
$rowODM=mysqli_fetch_array($result);


$tab=explode(";",$rowODM['ChampsModifie']);
foreach($tab as $valeur){
	if($valeur<>"_"){
		$cellule="";
		if($valeur=="Id_Metier_"){$cellule="D21";}
		elseif($valeur=="Id_TypeContrat_"){$cellule="K19";}
		elseif($valeur=="DateDebut_"){$cellule="L27";}
		elseif($valeur=="DateFin_"){$cellule="L29";}
		elseif($valeur=="Id_Client_"){$cellule="D27";}
		elseif($valeur=="Id_Responsable_"){$cellule="D30";}
		elseif($valeur=="MontantIPD_"){$cellule="L36";}
		elseif($valeur=="MontantRepas_"){$cellule="L37";}
		elseif($valeur=="MontantIGD_"){$cellule="L38";}
		elseif($valeur=="MontantRepasGD_"){$cellule="L39";}
		elseif($valeur=="FraisReel_"){$cellule="D40";}
		elseif($valeur=="IndemniteOutillage_"){$cellule="D44";}
		elseif($valeur=="PanierGrandeNuit_"){$cellule="L44";}
		elseif($valeur=="MajorationVSD_"){$cellule="D45";}
		elseif($valeur=="PanierVSD_"){$cellule="L45";}
		
		if($cellule<>""){
			$sheet->getStyle($cellule)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'5cca29'))));
		}
	}
}

$sheet->setCellValue('A9',utf8_encode("Code Analytique : ".$rowODM['CodeAnalytique']));
$sheet->setCellValue('A10',utf8_encode("Code Affaire Cegid : ".$rowODM['Prestation']));

$sheet->setCellValue('B18',utf8_encode($rowODM['Nom']));
$sheet->setCellValue('H18',utf8_encode($rowODM['Prenom']));
if($rowODM['TypeContrat']=="CDI"){$sheet->setCellValue('K19',utf8_encode("CDI X     CDD      CDIC       EXPATRIE "));}
elseif($rowODM['TypeContrat']=="CDD"){$sheet->setCellValue('K19',utf8_encode("CDI       CDD X     CDIC       EXPATRIE "));}
elseif($rowODM['TypeContrat']=="CDIC"){$sheet->setCellValue('K19',utf8_encode("CDI      CDD      CDIC X      EXPATRIE "));}
elseif($rowODM['TypeContrat']=="EXPATRE"){$sheet->setCellValue('K19',utf8_encode("CDI X     CDD      CDIC       EXPATRIE X"));}
else{$sheet->setCellValue('K20',utf8_encode("AUTRE       :  ".$rowODM['TypeContrat']." X"));}

if($_SESSION['Langue']=="FR"){$sheet->setCellValue('D21',utf8_encode($rowODM['Metier']));}
else{$sheet->setCellValue('D21',utf8_encode($rowODM['MetierEN']));}
$sheet->setCellValue('L21',utf8_encode($rowODM['Plateforme']));

//$sheet->setCellValue('A19',utf8_encode($rowODM['Motif']));

$sheet->setCellValue('D27',utf8_encode($rowODM['Client']));
if($rowODM['AdressePole']<>""){
	$sheet->setCellValue('D28',utf8_encode($rowODM['AdressePole']));
}
elseif($rowODM['AdressePrestation']<>""){
	$sheet->setCellValue('D28',utf8_encode($rowODM['AdressePrestation']));
}
else{
	$sheet->setCellValue('D28',utf8_encode($rowODM['AdressePlateforme']));
}
$sheet->setCellValue('D30',utf8_encode($rowODM['ResponsableAAA']));
$sheet->setCellValue('D31',utf8_encode($rowODM['TelehonePlateforme']));


$sheet->setCellValue('L27',utf8_encode(AfficheDateJJ_MM_AAAA($rowODM['DateDebut'])));
if($rowODM['DateFin']>'0001-01-01'){$sheet->setCellValue('L29',utf8_encode(AfficheDateJJ_MM_AAAA($rowODM['DateFin'])));}

if($rowODM['MontantIPD']>0){$sheet->setCellValue('L36',utf8_encode($rowODM['MontantIPD']));}
if($rowODM['MontantRepas']>0){$sheet->setCellValue('L37',utf8_encode($rowODM['MontantRepas']));}
if($rowODM['MontantIGD']>0){$sheet->setCellValue('L38',utf8_encode($rowODM['MontantIGD']));}
if($rowODM['MontantRepasGD']>0){$sheet->setCellValue('L39',utf8_encode($rowODM['MontantRepasGD']));}
if($rowODM['FraisReel']>0){$sheet->setCellValue('D40',utf8_encode($rowODM['FraisReel']));}

if($rowODM['PrimeResponsabilite']>0){$sheet->setCellValue('D43',utf8_encode($rowODM['PrimeResponsabilite']));}
if($rowODM['PrimeEquipe']>0){$sheet->setCellValue('L43',utf8_encode($rowODM['PrimeEquipe']));}
if($rowODM['IndemniteOutillage']>0){$sheet->setCellValue('D44',utf8_encode($rowODM['IndemniteOutillage']));}
if($rowODM['PanierGrandeNuit']>0){$sheet->setCellValue('L44',utf8_encode($rowODM['PanierGrandeNuit']));}
if($rowODM['MajorationVSD']>0){$sheet->setCellValue('D45',utf8_encode($rowODM['MajorationVSD']." %"));}
if($rowODM['PanierVSD']>0){$sheet->setCellValue('H45',utf8_encode("Autre : Panier VSD"));}
if($rowODM['PanierVSD']>0){$sheet->setCellValue('L45',utf8_encode($rowODM['PanierVSD']));}

$sheet->getStyle('L36:L39') ->getNumberFormat() ->setFormatCode('[$EUR ]#,##0.00_-' ); 

$sheet->getStyle('D40') ->getNumberFormat() ->setFormatCode('[$EUR ]#,##0.00_-' ); 
$sheet->getStyle('H45') ->getNumberFormat() ->setFormatCode('[$EUR ]#,##0.00_-' ); 
$sheet->getStyle('D43:D44') ->getNumberFormat() ->setFormatCode('[$EUR ]#,##0.00_-' ); 
$sheet->getStyle('L43:L45') ->getNumberFormat() ->setFormatCode('[$EUR ]#,##0.00_-' ); 
	

if($_SESSION['Langue']=="FR"){
$req="SELECT Id, Libelle 
	FROM rh_moyendeplacement 
	WHERE Id IN ( 
		SELECT rh_moyendeplacement.Id AS Id_MoyenDeplacement 
		FROM rh_moyendeplacement 
		WHERE rh_moyendeplacement.Suppr=0 
		
		UNION 
		
		SELECT rh_personne_contrat_moyendeplacement.Id_MoyenDeplacement 
		FROM rh_personne_contrat_moyendeplacement 
		WHERE rh_personne_contrat_moyendeplacement.Suppr=0 
		AND rh_personne_contrat_moyendeplacement.Id_Personne_Contrat=".$_GET['Id'].")
	";
}
else{
$req="SELECT Id, LibelleEN AS Libelle 
	FROM rh_moyendeplacement 
	WHERE Id IN ( 
		SELECT rh_moyendeplacement.Id AS Id_MoyenDeplacement 
		FROM rh_moyendeplacement 
		WHERE rh_moyendeplacement.Suppr=0 
		
		UNION 
		
		SELECT rh_personne_contrat_moyendeplacement.Id_MoyenDeplacement 
		FROM rh_personne_contrat_moyendeplacement 
		WHERE rh_personne_contrat_moyendeplacement.Suppr=0 
		AND rh_personne_contrat_moyendeplacement.Id_Personne_Contrat=".$_GET['Id'].")
	";	
}
$result=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($result);
$Ligne=50;
$nbLigne=0;

//Inserer une ligne
$nbLignePlus=$nb;
$leNombre=0;
for($i=0;$i<=$nbLignePlus;$i++){
	if($i>7){
	$leNombre++;
	}
}
if($leNombre>0){
	$sheet->insertNewRowBefore($Ligne+1, $leNombre);
}

if($nb>0){
	$nbLigne=0;
	while($row=mysqli_fetch_array($result)){
		if($row['Libelle']=="Autres" || $row['Libelle']=="Autre" || $row['Libelle']=="Others" || $row['Libelle']=="Other"|| 
		$row['Libelle']=="°Autres" || $row['Libelle']=="°Autre" || $row['Libelle']=="°Others" || $row['Libelle']=="°Other"){
			$sheet->setCellValue('A'.$Ligne,utf8_encode(substr($row['Libelle'],1)));
		}
		else{
			$sheet->setCellValue('A'.$Ligne,utf8_encode($row['Libelle']));
		}
		
		$Montant="";
		$Periodicite="";
		$Reference="";
		$checked="";
		$req="SELECT 
			Montant,Periodicite,Reference 
			FROM rh_personne_contrat_moyendeplacement 
			WHERE Suppr=0 
			AND Id_MoyenDeplacement=".$row['Id']."
			AND Id_Personne_Contrat=".$_GET['Id'];
		$resultM=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($resultM);
		if ($nbResulta>0){
			$checked="X";
			$rowM=mysqli_fetch_array($resultM);
			$Montant=$rowM['Montant'];
			$Periodicite=$rowM['Periodicite'];
			$Reference=$rowM['Reference'];
		}
		$sheet->setCellValue('E'.$Ligne,utf8_encode($checked));
		$sheet->setCellValue('F'.$Ligne,utf8_encode($Montant));
		$sheet->setCellValue('I'.$Ligne,utf8_encode($Periodicite));
		$sheet->setCellValue('K'.$Ligne,utf8_encode($Reference));
		$Ligne++;
	}
}

$Titre="";
if($_SESSION['Langue']=="FR"){$Titre="DemandeODM";}
else{$Titre="RequestODM";}
$Titre.="_".$rowODM['Nom']."_".$rowODM['Prenom']."_".$rowODM['DateCreation'];

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="'.$Titre.'.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

$writer->save('php://output');
?>
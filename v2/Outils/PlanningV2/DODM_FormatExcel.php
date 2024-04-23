<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('D-0709-GRP ordre de mission deplacement.xlsx');
$sheet = $excel->getSheetByName('D-0709');

$Id=$_GET['Id'];

$requete2="SELECT rh_personne_petitdeplacement.Id, rh_personne_petitdeplacement.Id_Personne,rh_personne_petitdeplacement.Id_Prestation,rh_personne_petitdeplacement.Id_Pole,
rh_personne_petitdeplacement.Id_PrestationDeplacement,rh_personne_petitdeplacement.Id_PoleDeplacement,rh_personne_petitdeplacement.DateCreation,rh_personne_petitdeplacement.Id_Createur,
rh_personne_petitdeplacement.Id_Metier,rh_personne_petitdeplacement.Montant,rh_personne_petitdeplacement.AvancePonctuelle,rh_personne_petitdeplacement.Periode,
rh_personne_petitdeplacement.DatePriseEnCompteRH,rh_personne_petitdeplacement.DateDebut,rh_personne_petitdeplacement.DateFin,ObjetDeplacement,HeureDebut,HeureFin,
(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_petitdeplacement.Id_Prestation) AS Prestation,Pays,
(SELECT Code_Analytique FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_petitdeplacement.Id_Prestation) AS Code_Analytique,
(SELECT (SELECT Logo FROM new_competences_plateforme WHERE new_competences_plateforme.Id=new_competences_prestation.Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_petitdeplacement.Id_Prestation) AS Logo,
(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=new_competences_prestation.Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_petitdeplacement.Id_Prestation) AS Plateforme,
CONCAT((SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation),
	IF(Id_Pole>0,' - ','') ,
	IF(Id_Pole>0,(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_Pole),'')
) AS PrestationDepart,DatePriseEnCompteAvance,
CONCAT((SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_PrestationDeplacement),
	IF(Id_PoleDeplacement>0,' - ','') ,
	IF(Id_PoleDeplacement>0,(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_PoleDeplacement),'')
) AS PrestationDestination,rh_personne_petitdeplacement.FraisReel,rh_personne_petitdeplacement.Lieu,
IF(Montant>0,1,0) AS DemandeAvance,
(SELECT new_competences_metier.LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=rh_personne_petitdeplacement.Id_Metier) AS MetierEN,
(SELECT new_competences_metier.Libelle FROM new_competences_metier WHERE new_competences_metier.Id=rh_personne_petitdeplacement.Id_Metier) AS Metier,
(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_petitdeplacement.Id_Createur) AS Demandeur,
(SELECT CONCAT(LEFT(new_rh_etatcivil.Prenom, 1),LEFT(new_rh_etatcivil.Nom, 1),RIGHT(new_rh_etatcivil.Nom, 1)) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_petitdeplacement.Id_Createur) AS SigleDemandeur,
(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_petitdeplacement.Id_Personne) AS Personne
FROM rh_personne_petitdeplacement
WHERE Id=".$_GET['Id'];
$result=mysqli_query($bdd,$requete2);
$rowDODM=mysqli_fetch_array($result);

if($rowDODM['Logo']<>""){
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('logo');
	$objDrawing->setDescription('PHPExcel logo');
	$objDrawing->setPath('../../Images/Logos/'.$rowDODM['Logo']);
	$objDrawing->setHeight(70);
	$objDrawing->setWidth(130);
	$objDrawing->setCoordinates('L1');
	$objDrawing->setOffsetX(90);
	$objDrawing->setOffsetY(8);
	$objDrawing->setWorksheet($sheet);
}
$sheet->setCellValue('L5',utf8_encode($rowDODM['Plateforme']));

$sheet->setCellValue('C7',utf8_encode($rowDODM['Personne']));
$sheet->setCellValue('I7',utf8_encode($rowDODM['Prestation']));
$sheet->setCellValue('N7',utf8_encode($rowDODM['Code_Analytique']));

$sheet->setCellValue('A11',utf8_encode(stripslashes($rowDODM['ObjetDeplacement'])));
$sheet->setCellValue('B14',utf8_encode(stripslashes($rowDODM['Pays'])));
$sheet->setCellValue('H14',utf8_encode(stripslashes($rowDODM['Lieu'])));

$heureDebut="";
$heureFin="";
if($rowDODM['HeureDebut']<>"00:00:00"){$heureDebut=" - ".substr($rowDODM['HeureDebut'],0,5);}
if($rowDODM['HeureFin']<>"00:00:00"){$heureFin=" - ".substr($rowDODM['HeureFin'],0,5);}
$sheet->setCellValue('D16',utf8_encode(AfficheDateJJ_MM_AAAA($rowDODM['DateDebut']).$heureDebut));
$sheet->setCellValue('H16',utf8_encode(AfficheDateJJ_MM_AAAA($rowDODM['DateFin']).$heureFin));

$besoinReservation="";
if($_SESSION["Langue"]=="FR"){
	$req="SELECT 
		(SELECT Libelle FROM rh_typebesoin WHERE rh_typebesoin.Id=Id_TypeBesoin) AS TypeBesoin,Id_TypeBesoin,
		TypeTrajet,LieuDepartAller,LieuArriveeAller,DateDepartAller,HeureDepartAller,HeureArriveeAller,LieuDepartRetour,LieuArriveeRetour,DateDepartRetour,HeureDepartRetour,HeureArriveeRetour,
		VehiculeAAA,DateDebutVehiculeAAA,DateFinVehiculeAAA,HeureDebutVehiculeAAA,HeureFinVehiculeAAA,
		ConducteurLocationVoiture,LieuDebutLocationVoiture,DateDebutLocationVoiture,HeureDebutLocationVoiture,LieuFinLocationVoiture,DateFinLocationVoiture,HeureFinLocationVoiture,
		NbNuitHotel,LieuHotel,DateArriveeHotel,DateDepartHotel,
		Commentaire,
		ValidationService
		FROM rh_personne_petitdeplacement_typebesoin 
		WHERE Suppr=0 
		AND Id_Personne_PetitDeplacement=".$rowDODM['Id'];
}
else{
	$req="SELECT 
		(SELECT LibelleEN FROM rh_typebesoin WHERE rh_typebesoin.Id=Id_TypeBesoin) AS TypeBesoin,Id_TypeBesoin,
		TypeTrajet,LieuDepartAller,LieuArriveeAller,DateDepartAller,HeureDepartAller,HeureArriveeAller,LieuDepartRetour,LieuArriveeRetour,DateDepartRetour,HeureDepartRetour,HeureArriveeRetour,
		VehiculeAAA,DateDebutVehiculeAAA,DateFinVehiculeAAA,HeureDebutVehiculeAAA,HeureFinVehiculeAAA,
		ConducteurLocationVoiture,LieuDebutLocationVoiture,DateDebutLocationVoiture,HeureDebutLocationVoiture,LieuFinLocationVoiture,DateFinLocationVoiture,HeureFinLocationVoiture,
		NbNuitHotel,LieuHotel,DateArriveeHotel,DateDepartHotel,
		Commentaire,
		ValidationService
		FROM rh_personne_petitdeplacement_typebesoin 
		WHERE Suppr=0 
		AND Id_Personne_PetitDeplacement=".$rowDODM['Id'];
}
$resultBesoins=mysqli_query($bdd,$req);
$nbBesoins=mysqli_num_rows($resultBesoins);

if($nbBesoins>0){
	while($rowBesoins=mysqli_fetch_array($resultBesoins)){
		if($rowBesoins['Commentaire']<>"" || $rowDODM['Id']<=21){$besoinReservation.="".$rowBesoins['TypeBesoin']." : ".stripslashes($rowBesoins['Commentaire'])."\n";}
		
		if($rowBesoins['Id_TypeBesoin']==2){
			$sheet->setCellValue('D48',utf8_encode($rowBesoins['NbNuitHotel']));
			$sheet->setCellValue('G48',utf8_encode($rowBesoins['LieuHotel']));
			$sheet->setCellValue('D50',utf8_encode(AfficheDateJJ_MM_AAAA($rowBesoins['DateArriveeHotel'])));
			$sheet->setCellValue('H50',utf8_encode(AfficheDateJJ_MM_AAAA($rowBesoins['DateDepartHotel'])));
		}
		elseif($rowBesoins['Id_TypeBesoin']==3){
			$sheet->setCellValue('D22',utf8_encode($rowBesoins['LieuDepartAller']));
			$sheet->setCellValue('H22',utf8_encode($rowBesoins['LieuArriveeAller']));
			$sheet->setCellValue('D23',utf8_encode(AfficheDateJJ_MM_AAAA($rowBesoins['DateDepartAller'])));
			$sheet->setCellValue('D24',utf8_encode($rowBesoins['HeureDepartAller']));
			$sheet->setCellValue('H24',utf8_encode($rowBesoins['HeureArriveeAller']));
			
			$sheet->setCellValue('D27',utf8_encode($rowBesoins['LieuDepartRetour']));
			$sheet->setCellValue('H27',utf8_encode($rowBesoins['LieuArriveeRetour']));
			$sheet->setCellValue('D28',utf8_encode(AfficheDateJJ_MM_AAAA($rowBesoins['DateDepartRetour'])));
			$sheet->setCellValue('D29',utf8_encode($rowBesoins['HeureDepartRetour']));
			$sheet->setCellValue('H29',utf8_encode($rowBesoins['HeureArriveeRetour']));
		}
		elseif($rowBesoins['Id_TypeBesoin']==4){
			$sheet->setCellValue('D38',utf8_encode($rowBesoins['ConducteurLocationVoiture']));
			$sheet->setCellValue('D40',utf8_encode($rowBesoins['LieuArriveeAller']));
			$sheet->setCellValue('D41',utf8_encode(AfficheDateJJ_MM_AAAA($rowBesoins['DateDebutLocationVoiture'])));
			$sheet->setCellValue('H41',utf8_encode($rowBesoins['HeureDebutLocationVoiture']));
			
			$sheet->setCellValue('D43',utf8_encode($rowBesoins['LieuDepartRetour']));
			$sheet->setCellValue('D44',utf8_encode(AfficheDateJJ_MM_AAAA($rowBesoins['DateFinLocationVoiture'])));
			$sheet->setCellValue('H44',utf8_encode($rowBesoins['HeureFinLocationVoiture']));
		}
		elseif($rowBesoins['Id_TypeBesoin']==5){
			$sheet->setCellValue('C32',utf8_encode($rowBesoins['VehiculeAAA']));
			$sheet->setCellValue('D34',utf8_encode(AfficheDateJJ_MM_AAAA($rowBesoins['DateDebutVehiculeAAA'])));
			$sheet->setCellValue('D35',utf8_encode(AfficheDateJJ_MM_AAAA($rowBesoins['DateFinVehiculeAAA'])));
			$sheet->setCellValue('H34',utf8_encode($rowBesoins['HeureDebutVehiculeAAA']));
			$sheet->setCellValue('H35',utf8_encode($rowBesoins['HeureFinVehiculeAAA']));
			
		}
	}
}
$sheet->setCellValue('A53',utf8_encode($besoinReservation));

$sheet->setCellValue('C59',utf8_encode($rowDODM['Demandeur']));
$sheet->setCellValue('C60',utf8_encode(AfficheDateJJ_MM_AAAA($rowDODM['DateCreation'])));
if($_SESSION["Langue"]=="FR"){
	$sheet->setCellValue('C61',utf8_encode($rowDODM['SigleDemandeur']."\n 'signature électronique'"));
}
else{
	$sheet->setCellValue('C61',utf8_encode($rowDODM['SigleDemandeur']."\n 'electronic signature'"));
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="D-0709-GRP ordre de mission deplacement.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

$chemin = '../../tmp/D-0709-GRP ordre de mission deplacement.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
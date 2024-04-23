<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('Template_AT2.xlsx');
$sheet = $excel->getSheetByName('Informations AT');

$Id=$_GET['Id'];

$requete2="SELECT Id,Id_Personne,Id_Createur,Id_Prestation,Id_Pole,Id_Metier,Id_Lieu_AT,
	DateCreation,DateAT,HeureAT,Id_TypeContrat,DoutesCirconstances As Doutes,EvacuationVers,
	AutreVictime,TiersResponsable,Temoin,CoordonneesTemoins,1erePersonneAvertie,
	Adresse,CP,Ville,NumSecurite,DateNaissance,Anciennete,HeureDebutAM,HeureFinAM,HeureDebutPM,HeureFinPM,Id_TypeVehicule,
	ConditionClim,MauvaisEtatInfra,TrajetAller,HoraireTravail,ProblemeTechnique,CommentaireCirconstance,CommentaireCirconstance2,
	LieuAccident,SIRETClient,Activite,CommentaireNature,ArretDeTravail,
	DateConnaissanceAT,HeureConnaissanceAT,DoutesCirconstances,AutresInformations,
	(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
	(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
	(SELECT LibelleEN FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContratEN,
	(SELECT Libelle FROM rh_lieu_at WHERE rh_lieu_at.Id=Id_Lieu_AT) AS LieuAT,
	(SELECT LibelleEN FROM rh_lieu_at WHERE rh_lieu_at.Id=Id_Lieu_AT) AS LieuATEN,
	(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Prestation,
	(SELECT new_rh_etatcivil.Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_at.Id_Personne) AS NomPersonne, 
	(SELECT new_rh_etatcivil.Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_at.Id_Personne) AS PrenomPersonne, 
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_at.Id_Createur) AS Demandeur,
	(SELECT CONCAT(LEFT(new_rh_etatcivil.Prenom, 1),LEFT(new_rh_etatcivil.Nom, 1),RIGHT(new_rh_etatcivil.Nom, 1)) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_at.Id_Createur) AS SigleDemandeur 
	FROM rh_personne_at
	WHERE Id=".$Id;
$result=mysqli_query($bdd,$requete2);
$rowAT=mysqli_fetch_array($result);

$sheet->setCellValue('C9',utf8_encode(AfficheDateJJ_MM_AAAA($rowAT['DateAT'])));
$sheet->setCellValue('J9',utf8_encode($rowAT['HeureAT']));
$sheet->setCellValue('O9',utf8_encode($rowAT['TypeContrat']));

$sheet->setCellValue('C11',utf8_encode($rowAT['NomPersonne']));
$sheet->setCellValue('J11',utf8_encode($rowAT['PrenomPersonne']));

$sheet->setCellValue('C13',utf8_encode($rowAT['Adresse']));
$sheet->setCellValue('K13',utf8_encode($rowAT['CP']));
$sheet->setCellValue('M13',utf8_encode($rowAT['Ville']));

$sheet->setCellValue('C15',utf8_encode($rowAT['NumSecurite']));
$sheet->setCellValue('J15',utf8_encode(AfficheDateJJ_MM_AAAA($rowAT['DateNaissance'])));
$sheet->setCellValue('N15',utf8_encode($rowAT['Anciennete']));

$sheet->setCellValue('B17',utf8_encode($rowAT['Metier']));

if($_SESSION['Langue']=="FR"){$sheet->setCellValue('I17',utf8_encode("de ".str_replace(":","h",substr($rowAT['HeureDebutAM'],0,5))." à ".str_replace(":","h",substr($rowAT['HeureFinAM'],0,5))."  et  ".str_replace(":","h",substr($rowAT['HeureDebutPM'],0,5))." à ".str_replace(":","h",substr($rowAT['HeureFinPM'],0,5)).""));}
else{$sheet->setCellValue('I17',utf8_encode("de ".str_replace(":","h",substr($rowAT['HeureDebutAM'],0,5))." à ".str_replace(":","h",substr($rowAT['HeureFinAM'],0,5))."  et  ".str_replace(":","h",substr($rowAT['HeureDebutPM'],0,5))." à ".str_replace(":","h",substr($rowAT['HeureFinPM'],0,5)).""));}

$sheet->setCellValue('A19',utf8_encode($rowAT['LieuAccident']));
$sheet->getStyle('A19')->getAlignment()->setWrapText(true);
$sheet->setCellValue('L19',utf8_encode($rowAT['SIRETClient']));

$sheet->setCellValue('D20',utf8_encode($rowAT['Prestation']));

if($_SESSION['Langue']=="FR"){
$req="SELECT Id,Libelle	
	FROM rh_lieu_at
	WHERE Suppr=0 OR Id=".$rowAT['Id_Lieu_AT']."
	ORDER BY Libelle
	";
}
else{
$req="SELECT Id,LibelleEN AS Libelle	
	FROM rh_lieu_at
	WHERE Suppr=0 OR Id=".$rowAT['Id_Lieu_AT']."
	ORDER BY LibelleEN
	";	
}
$result=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($result);
$Ligne=22;
$nbLigne=0;

//Inserer une ligne
$nbLignePlus=$nb/2;
$leNombre=0;
for($i=0;$i<=$nbLignePlus;$i++){
	if($i>3){
	$leNombre++;
	}
}
if($leNombre>0){
	$sheet->insertNewRowBefore($Ligne+1, $leNombre);
}
if($nb>0){
	$nbLigne=0;
	$Col="A";
	$ColX="G";
	while($row=mysqli_fetch_array($result)){
		$sheet->setCellValue($Col.$Ligne,utf8_encode($row['Libelle']));
		if($row['Id']==$rowAT['Id_Lieu_AT']){
			$objDrawing = new PHPExcel_Worksheet_Drawing();
			$objDrawing->setName('Coche1');
			$objDrawing->setDescription('PHPExcel Coche1');
			$objDrawing->setPath('../../Images/CaseCoche.png');
			$objDrawing->setWidth(15);
			$objDrawing->setHeight(15);
			$objDrawing->setCoordinates($ColX.$Ligne);
			$objDrawing->setOffsetX(0);
			$objDrawing->setOffsetY(0);
			$objDrawing->setWorksheet($sheet);
		}
		else{
			$objDrawing = new PHPExcel_Worksheet_Drawing();
			$objDrawing->setName('Coche1');
			$objDrawing->setDescription('PHPExcel Coche1');
			$objDrawing->setPath('../../Images/CaseNonCoche.png');
			$objDrawing->setWidth(15);
			$objDrawing->setHeight(15);
			$objDrawing->setCoordinates($ColX.$Ligne);
			$objDrawing->setOffsetX(0);
			$objDrawing->setOffsetY(0);
			$objDrawing->setWorksheet($sheet);
		}
		if($Col=="A"){$Col="H";$ColX="O";}
		else{
			$Col="A";
			$ColX="G";
			$Ligne++;
			$nbLigne++;
		}
	}
}

$Ligne=$Ligne+1;
$sheet->setCellValue('A'.$Ligne,utf8_encode($rowAT['Activite']));

$Ligne=$Ligne+3;
//Type de véhicule
$req="SELECT Id,Libelle	
	FROM rh_typevehicule
	WHERE Suppr=0
	ORDER BY Libelle
	";
$result=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($result);
$typeVehicule="";
if($nb>0){
	$laCol="F";
	while($row=mysqli_fetch_array($result)){
		if($rowAT['Id_TypeVehicule']==$row['Id']){
			$objDrawing = new PHPExcel_Worksheet_Drawing();
			$objDrawing->setName('Coche1');
			$objDrawing->setDescription('PHPExcel Coche1');
			$objDrawing->setPath('../../Images/CaseCoche.png');
			$objDrawing->setWidth(15);
			$objDrawing->setHeight(15);
			$objDrawing->setCoordinates($laCol.$Ligne);
			$objDrawing->setOffsetX(-17);
			$objDrawing->setOffsetY(0);
			$objDrawing->setWorksheet($sheet);
		}
		else{
			$objDrawing = new PHPExcel_Worksheet_Drawing();
			$objDrawing->setName('Coche1');
			$objDrawing->setDescription('PHPExcel Coche1');
			$objDrawing->setPath('../../Images/CaseNonCoche.png');
			$objDrawing->setWidth(15);
			$objDrawing->setHeight(15);
			$objDrawing->setCoordinates($laCol.$Ligne);
			$objDrawing->setOffsetX(-17);
			$objDrawing->setOffsetY(0);
			$objDrawing->setWorksheet($sheet);
		}
		$sheet->setCellValue($laCol.$Ligne,utf8_encode($row['Libelle']));
		if(substr_count($row['Libelle'], ' ')>0){
			$col2=$laCol;
			$col2++;
			$sheet->mergeCells($laCol.$Ligne.':'.$col2.$Ligne);
			$laCol++;
			
		}
		$laCol++;
	}
}

$Ligne=$Ligne+1;
if($rowAT['ConditionClim']==1){
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Coche1');
	$objDrawing->setDescription('PHPExcel Coche1');
	$objDrawing->setPath('../../Images/CaseCoche.png');
	$objDrawing->setWidth(15);
	$objDrawing->setHeight(15);
	$objDrawing->setCoordinates('F'.$Ligne);
	$objDrawing->setOffsetX(-20);
	$objDrawing->setOffsetY(0);
	$objDrawing->setWorksheet($sheet);
}
else{
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Coche1');
	$objDrawing->setDescription('PHPExcel Coche1');
	$objDrawing->setPath('../../Images/CaseNonCoche.png');
	$objDrawing->setWidth(15);
	$objDrawing->setHeight(15);
	$objDrawing->setCoordinates('F'.$Ligne);
	$objDrawing->setOffsetX(-20);
	$objDrawing->setOffsetY(0);
	$objDrawing->setWorksheet($sheet);
}
$sheet->setCellValue('F'.$Ligne,utf8_encode("Conditions climatiques particulières"));

$sheet->setCellValue('J'.$Ligne,utf8_encode($rowAT['CommentaireCirconstance']));

$Ligne=$Ligne+1;
if($rowAT['MauvaisEtatInfra']==1){
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Coche1');
	$objDrawing->setDescription('PHPExcel Coche1');
	$objDrawing->setPath('../../Images/CaseCoche.png');
	$objDrawing->setWidth(15);
	$objDrawing->setHeight(15);
	$objDrawing->setCoordinates('F'.$Ligne);
	$objDrawing->setOffsetX(-20);
	$objDrawing->setOffsetY(0);
	$objDrawing->setWorksheet($sheet);
}
else{
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Coche1');
	$objDrawing->setDescription('PHPExcel Coche1');
	$objDrawing->setPath('../../Images/CaseNonCoche.png');
	$objDrawing->setWidth(15);
	$objDrawing->setHeight(15);
	$objDrawing->setCoordinates('F'.$Ligne);
	$objDrawing->setOffsetX(-20);
	$objDrawing->setOffsetY(0);
	$objDrawing->setWorksheet($sheet);
}
$sheet->setCellValue('F'.$Ligne,utf8_encode("Mauvais état des infrastructures"));
$Ligne=$Ligne+1;

$Ligne=$Ligne+1;
if($rowAT['HoraireTravail']==1){
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Coche1');
	$objDrawing->setDescription('PHPExcel Coche1');
	$objDrawing->setPath('../../Images/CaseCoche.png');
	$objDrawing->setWidth(15);
	$objDrawing->setHeight(15);
	$objDrawing->setCoordinates('F'.$Ligne);
	$objDrawing->setOffsetX(-20);
	$objDrawing->setOffsetY(0);
	$objDrawing->setWorksheet($sheet);
}
else{
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Coche1');
	$objDrawing->setDescription('PHPExcel Coche1');
	$objDrawing->setPath('../../Images/CaseNonCoche.png');
	$objDrawing->setWidth(15);
	$objDrawing->setHeight(15);
	$objDrawing->setCoordinates('F'.$Ligne);
	$objDrawing->setOffsetX(-20);
	$objDrawing->setOffsetY(0);
	$objDrawing->setWorksheet($sheet);
}
$sheet->setCellValue('F'.$Ligne,utf8_encode("Horaires de travail spécifiques"));
$sheet->setCellValue('J'.$Ligne,utf8_encode($rowAT['CommentaireCirconstance2']));

$Ligne=$Ligne+1;
if($rowAT['ProblemeTechnique']==1){
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Coche1');
	$objDrawing->setDescription('PHPExcel Coche1');
	$objDrawing->setPath('../../Images/CaseCoche.png');
	$objDrawing->setWidth(15);
	$objDrawing->setHeight(15);
	$objDrawing->setCoordinates('F'.$Ligne);
	$objDrawing->setOffsetX(-20);
	$objDrawing->setOffsetY(0);
	$objDrawing->setWorksheet($sheet);
}
else{
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Coche1');
	$objDrawing->setDescription('PHPExcel Coche1');
	$objDrawing->setPath('../../Images/CaseNonCoche.png');
	$objDrawing->setWidth(15);
	$objDrawing->setHeight(15);
	$objDrawing->setCoordinates('F'.$Ligne);
	$objDrawing->setOffsetX(-20);
	$objDrawing->setOffsetY(0);
	$objDrawing->setWorksheet($sheet);
}
$sheet->setCellValue('F'.$Ligne,utf8_encode("Problème technique du véhicule accidenté"));
$Ligne=$Ligne+2;
$sheet->setCellValue('A'.$Ligne,utf8_encode($rowAT['CommentaireNature']));

if($_SESSION['Langue']=="FR"){
$req="SELECT Id,Libelle	
	FROM rh_typeobjet_at
	WHERE Suppr=0
	ORDER BY Libelle
	";
}
else{
$req="SELECT Id,LibelleEN AS Libelle	
	FROM rh_typeobjet_at
	WHERE Suppr=0
	ORDER BY LibelleEN
	";	
}
$result=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($result);

$req="SELECT Objet,Id_TypeObjet	
	FROM rh_personne_at_objet 
	WHERE Suppr=0 
	AND Id_Personne_AT=".$rowAT['Id']." ";
$resultObj=mysqli_query($bdd,$req);
$nbObj=mysqli_num_rows($resultObj);

$Ligne=$Ligne+3;
$nbLigne=0;

//Inserer une ligne
$nbLignePlus=$nb/2;
$leNombre=0;
for($i=0;$i<=$nbLignePlus;$i++){
	if($i>3){
	$leNombre++;
	}
}

if($leNombre>0){
	$sheet->insertNewRowBefore($Ligne+1, $leNombre);
}
if($nb>0){
	$nbLigne=0;
	$Col="A";
	$ColX="C";
	while($row=mysqli_fetch_array($result)){
		$sheet->setCellValue($Col.$Ligne,utf8_encode(str_replace("°","",$row['Libelle'])));
		if($nbObj>0){
			mysqli_data_seek($resultObj,0);
			while($rowObj=mysqli_fetch_array($resultObj)){
				if($rowObj['Id_TypeObjet']==$row['Id']){
					$sheet->setCellValue($ColX.$Ligne,utf8_encode($rowObj['Objet']));
				}
			}
		}
		if($Col=="A"){$Col="I";$ColX="K";}
		else{
			$Col="A";
			$ColX="C";
			$Ligne++;
			$nbLigne++;
		}
	}
}

$Ligne=$Ligne+1;

if($_SESSION['Langue']=="FR"){
$req="SELECT Id,Libelle,CoteGD	
	FROM rh_siege_lesion_at
	WHERE Suppr=0
	ORDER BY Libelle
	";
}
else{
$req="SELECT Id,LibelleEN AS Libelle,CoteGD	
	FROM rh_siege_lesion_at
	WHERE Suppr=0
	ORDER BY LibelleEN
	";	
}
$result=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($result);

$req="SELECT Id_SiegeLesion,AutreSiege,Gauche,Droite
		FROM rh_personne_at_siegelesion 
		WHERE Suppr=0 
		AND Id_Personne_AT=".$rowAT['Id']."
		";
$resultSiege=mysqli_query($bdd,$req);
$nbSiege=mysqli_num_rows($resultSiege);

//Inserer une ligne
$nbLignePlus=$nb/6;
$leNombre=0;
for($i=0;$i<=$nbLignePlus;$i++){
	if($i>3){
	$leNombre++;
	}
}

if($leNombre>0){
	$sheet->insertNewRowBefore($Ligne+1, $leNombre);
}
if($nb>0){
	$nbLigne=0;
	$Col="A";
	$ColX="B";
	while($row=mysqli_fetch_array($result)){
		$sheet->setCellValue($Col.$Ligne,utf8_encode(str_replace("°","",$row['Libelle'])));
		if($row['Libelle']=="Autres" || $row['Libelle']=="Autre" || $row['Libelle']=="Others" || $row['Libelle']=="Other" || 
		$row['Libelle']=="°Autres" || $row['Libelle']=="°Autre" || $row['Libelle']=="°Others" || $row['Libelle']=="°Other"){
			$sheet->getStyle($ColX.$Ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e7e6e6'))));
			$sheet->getStyle($ColX.$Ligne)->applyFromArray(array('borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')))));
		}
		$Trouve=0;
		if($nbSiege>0){
			mysqli_data_seek($resultSiege,0);
			$Trouve=0;
			while($rowSiege=mysqli_fetch_array($resultSiege)){
				if($rowSiege['Id_SiegeLesion']==$row['Id']){
					$Trouve=1;
					if($rowSiege['AutreSiege']<>""){
						$sheet->setCellValue($ColX.$Ligne,utf8_encode($rowSiege['AutreSiege']));
					}
					else{
						if($row['CoteGD']==1){
							$siege="";
							$laCaseG="";
							$laCaseD="";
							if($rowSiege['Gauche']==1){
								$laCaseG="../../Images/CaseCoche.png";
							}else{
								$laCaseG="../../Images/CaseNonCoche.png";
							}
							if($rowSiege['Droite']==1){
								$laCaseD="../../Images/CaseCoche.png";
							}else{
								$laCaseD="../../Images/CaseNonCoche.png";
							}
							if($_SESSION['Langue']=="FR"){
								$siege.="  G       D ";
							}
							else{
								$siege.="  L       R ";
							}
							$objDrawing = new PHPExcel_Worksheet_Drawing();
							$objDrawing->setName('Coche1');
							$objDrawing->setDescription('PHPExcel Coche1');
							$objDrawing->setPath($laCaseG);
							$objDrawing->setWidth(15);
							$objDrawing->setHeight(15);
							$objDrawing->setCoordinates($ColX.$Ligne);
							$objDrawing->setOffsetX(-10);
							$objDrawing->setOffsetY(0);
							$objDrawing->setWorksheet($sheet);
							
							$objDrawing = new PHPExcel_Worksheet_Drawing();
							$objDrawing->setName('Coche1');
							$objDrawing->setDescription('PHPExcel Coche1');
							$objDrawing->setPath($laCaseD);
							$objDrawing->setWidth(15);
							$objDrawing->setHeight(15);
							$objDrawing->setCoordinates($ColX.$Ligne);
							$objDrawing->setOffsetX(20);
							$objDrawing->setOffsetY(0);
							$objDrawing->setWorksheet($sheet);
							$sheet->setCellValue($ColX.$Ligne,utf8_encode($siege));
						}
						else{
							$objDrawing = new PHPExcel_Worksheet_Drawing();
							$objDrawing->setName('Coche1');
							$objDrawing->setDescription('PHPExcel Coche1');
							$objDrawing->setPath("../../Images/CaseCoche.png");
							$objDrawing->setWidth(15);
							$objDrawing->setHeight(15);
							$objDrawing->setCoordinates($ColX.$Ligne);
							$objDrawing->setOffsetX(0);
							$objDrawing->setOffsetY(0);
							$objDrawing->setWorksheet($sheet);
						}
					}
				}
			}
		}
		if($Trouve==0){
			if($row['CoteGD']==1){
				$laCaseG="../../Images/CaseNonCoche.png";
				$laCaseD="../../Images/CaseNonCoche.png";
				
				$objDrawing = new PHPExcel_Worksheet_Drawing();
				$objDrawing->setName('Coche1');
				$objDrawing->setDescription('PHPExcel Coche1');
				$objDrawing->setPath($laCaseG);
				$objDrawing->setWidth(15);
				$objDrawing->setHeight(15);
				$objDrawing->setCoordinates($ColX.$Ligne);
				$objDrawing->setOffsetX(-10);
				$objDrawing->setOffsetY(0);
				$objDrawing->setWorksheet($sheet);
				
				$objDrawing = new PHPExcel_Worksheet_Drawing();
				$objDrawing->setName('Coche1');
				$objDrawing->setDescription('PHPExcel Coche1');
				$objDrawing->setPath($laCaseD);
				$objDrawing->setWidth(15);
				$objDrawing->setHeight(15);
				$objDrawing->setCoordinates($ColX.$Ligne);
				$objDrawing->setOffsetX(20);
				$objDrawing->setOffsetY(0);
				$objDrawing->setWorksheet($sheet);
				if($_SESSION['Langue']=="FR"){
					$sheet->setCellValue($ColX.$Ligne,utf8_encode("  G       D "));
				}
				else{
					$sheet->setCellValue($ColX.$Ligne,utf8_encode("  L       R "));
				}
			}
			else{
				if($row['Libelle']=="Autres" || $row['Libelle']=="Autre" || $row['Libelle']=="Others" || $row['Libelle']=="Other" || 
				$row['Libelle']=="°Autres" || $row['Libelle']=="°Autre" || $row['Libelle']=="°Others" || $row['Libelle']=="°Other"){
				}
				else{
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setName('Coche1');
					$objDrawing->setDescription('PHPExcel Coche1');
					$objDrawing->setPath("../../Images/CaseNonCoche.png");
					$objDrawing->setWidth(15);
					$objDrawing->setHeight(15);
					$objDrawing->setCoordinates($ColX.$Ligne);
					$objDrawing->setOffsetX(0);
					$objDrawing->setOffsetY(0);
					$objDrawing->setWorksheet($sheet);
				}
			}
		}
		if($Col=="A"){$Col="C";$ColX="D";}
		elseif($Col=="C"){$Col="E";$ColX="F";}
		elseif($Col=="E"){$Col="G";$ColX="H";}
		elseif($Col=="G"){$Col="I";$ColX="J";}
		elseif($Col=="I"){$Col="K";$ColX="L";}
		else{
			$Col="A";
			$ColX="B";
			$Ligne++;
			$nbLigne++;
		}
	}
}

$Ligne=$Ligne+1;

if($_SESSION['Langue']=="FR"){
$req="SELECT Id,Libelle	
	FROM rh_nature_lesion
	WHERE Suppr=0
	ORDER BY Libelle
	";
}
else{
$req="SELECT Id,LibelleEN AS Libelle	
	FROM rh_nature_lesion
	WHERE Suppr=0
	ORDER BY LibelleEN
	";	
}
$result=mysqli_query($bdd,$req);
$nb=mysqli_num_rows($result);

$req="SELECT Id_NatureLesion,AutreNature
		FROM rh_personne_at_nature_lesion 
		WHERE Suppr=0 
		AND Id_PersonneAT=".$rowAT['Id']."
		";
$resultNature=mysqli_query($bdd,$req);
$nbNature=mysqli_num_rows($resultNature);

//Inserer une ligne
$nbLignePlus=$nb/5;
$leNombre=0;
for($i=0;$i<=$nbLignePlus;$i++){
	if($i>2){
	$leNombre++;
	}
}

if($leNombre>0){
	$sheet->insertNewRowBefore($Ligne+1, $leNombre);
}
if($nb>0){
	$nbLigne=0;
	$Col="A";
	$ColX="C";
	while($row=mysqli_fetch_array($result)){
		$sheet->setCellValue($Col.$Ligne,utf8_encode(str_replace("°","",$row['Libelle'])));
		if($row['Libelle']=="Autres" || $row['Libelle']=="Autre" || $row['Libelle']=="Others" || $row['Libelle']=="Other" || 
		$row['Libelle']=="°Autres" || $row['Libelle']=="°Autre" || $row['Libelle']=="°Others" || $row['Libelle']=="°Other"){
			$sheet->getStyle($ColX.$Ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'e7e6e6'))));
			$sheet->getStyle($ColX.$Ligne)->applyFromArray(array('borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')))));
		}
		
		$Trouve=0;
		if($nbSiege>0){
			mysqli_data_seek($resultNature,0);
			while($rowNature=mysqli_fetch_array($resultNature)){
				if($rowNature['Id_NatureLesion']==$row['Id']){
					$Trouve=1;
					if($rowNature['AutreNature']<>""){
						$sheet->setCellValue($ColX.$Ligne,utf8_encode($rowNature['AutreNature']));
					}
					else{
						$objDrawing = new PHPExcel_Worksheet_Drawing();
						$objDrawing->setName('Coche1');
						$objDrawing->setDescription('PHPExcel Coche1');
						$objDrawing->setPath("../../Images/CaseCoche.png");
						$objDrawing->setWidth(15);
						$objDrawing->setHeight(15);
						$objDrawing->setCoordinates($ColX.$Ligne);
						$objDrawing->setOffsetX(0);
						$objDrawing->setOffsetY(0);
						$objDrawing->setWorksheet($sheet);
					}
				}
			}
		}
		if($Trouve==0){
			if($row['Libelle']=="Autres" || $row['Libelle']=="Autre" || $row['Libelle']=="Others" || $row['Libelle']=="Other" || 
			$row['Libelle']=="°Autres" || $row['Libelle']=="°Autre" || $row['Libelle']=="°Others" || $row['Libelle']=="°Other"){
			}
			else{
				$objDrawing = new PHPExcel_Worksheet_Drawing();
				$objDrawing->setName('Coche1');
				$objDrawing->setDescription('PHPExcel Coche1');
				$objDrawing->setPath("../../Images/CaseNonCoche.png");
				$objDrawing->setWidth(15);
				$objDrawing->setHeight(15);
				$objDrawing->setCoordinates($ColX.$Ligne);
				$objDrawing->setOffsetX(0);
				$objDrawing->setOffsetY(0);
				$objDrawing->setWorksheet($sheet);
			}
		}
		if($Col=="A"){$Col="D";$ColX="F";}
		elseif($Col=="D"){$Col="G";$ColX="I";}
		elseif($Col=="G"){$Col="J";$ColX="L";}
		elseif($Col=="J"){$Col="M";$ColX="O";}
		else{
			$Col="A";
			$ColX="C";
			$Ligne++;
			$nbLigne++;
		}
	}
}

$Ligne=$Ligne+1;
if($rowAT['ArretDeTravail']==0){
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Coche1');
	$objDrawing->setDescription('PHPExcel Coche1');
	$objDrawing->setPath('../../Images/CaseCoche.png');
	$objDrawing->setWidth(15);
	$objDrawing->setHeight(15);
	$objDrawing->setCoordinates('G'.$Ligne);
	$objDrawing->setOffsetX(0);
	$objDrawing->setOffsetY(0);
	$objDrawing->setWorksheet($sheet);

	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Coche1');
	$objDrawing->setDescription('PHPExcel Coche1');
	$objDrawing->setPath('../../Images/CaseNonCoche.png');
	$objDrawing->setWidth(15);
	$objDrawing->setHeight(15);
	$objDrawing->setCoordinates('C'.$Ligne);
	$objDrawing->setOffsetX(0);
	$objDrawing->setOffsetY(0);
	$objDrawing->setWorksheet($sheet);
}
else{
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Coche1');
	$objDrawing->setDescription('PHPExcel Coche1');
	$objDrawing->setPath('../../Images/CaseNonCoche.png');
	$objDrawing->setWidth(15);
	$objDrawing->setHeight(15);
	$objDrawing->setCoordinates('G'.$Ligne);
	$objDrawing->setOffsetX(0);
	$objDrawing->setOffsetY(0);
	$objDrawing->setWorksheet($sheet);

	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Coche1');
	$objDrawing->setDescription('PHPExcel Coche1');
	$objDrawing->setPath('../../Images/CaseCoche.png');
	$objDrawing->setWidth(15);
	$objDrawing->setHeight(15);
	$objDrawing->setCoordinates('C'.$Ligne);
	$objDrawing->setOffsetX(0);
	$objDrawing->setOffsetY(0);
	$objDrawing->setWorksheet($sheet);
}
$sheet->setCellValue('J'.$Ligne,utf8_encode($rowAT['EvacuationVers']));

$Ligne=$Ligne+1;
$sheet->setCellValue('C'.$Ligne,utf8_encode($rowAT['AutreVictime']));
$sheet->setCellValue('J'.$Ligne,utf8_encode($rowAT['TiersResponsable']));

$Ligne=$Ligne+1;
$sheet->setCellValue('C'.$Ligne,utf8_encode($rowAT['Temoin']));
$sheet->setCellValue('J'.$Ligne,utf8_encode($rowAT['CoordonneesTemoins']));

$Ligne=$Ligne+1;
$sheet->setCellValue('D'.$Ligne,utf8_encode($rowAT['1erePersonneAvertie']));

$Ligne=$Ligne+1;
$sheet->setCellValue('E'.$Ligne,utf8_encode(AfficheDateJJ_MM_AAAA($rowAT['DateConnaissanceAT'])));
$sheet->setCellValue('L'.$Ligne,utf8_encode($rowAT['HeureConnaissanceAT']));

$Ligne=$Ligne+2;
$sheet->setCellValue('A'.$Ligne,utf8_encode($rowAT['Doutes']));
$sheet->getStyle('A'.$Ligne)->getAlignment()->setWrapText(true);

$Ligne=$Ligne+4;
$sheet->setCellValue('A'.$Ligne,utf8_encode($rowAT['AutresInformations']));
$sheet->getStyle('A'.$Ligne)->getAlignment()->setWrapText(true);

$Ligne=$Ligne+3;
$req="SELECT new_competences_plateforme.Libelle,new_competences_plateforme.Logo 
	FROM new_competences_prestation 
	LEFT JOIN new_competences_plateforme
	ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id 
	WHERE new_competences_prestation.Id=".$rowAT['Id_Prestation'];
$resultPresta=mysqli_query($bdd,$req);
$nbPresta=mysqli_num_rows($resultPresta);
if($nbPresta>0){
	$row=mysqli_fetch_array($resultPresta);
	$sheet->setCellValue('F'.$Ligne,utf8_encode($row['Libelle']));
	$sheet->setCellValue('L1',utf8_encode("Plateforme / Site :\n".$row['Libelle']));
	$sheet->getStyle('L1')->getAlignment()->setWrapText(true);
	
	
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('logo');
	$objDrawing->setDescription('PHPExcel logo');
	$objDrawing->setPath('../../Images/Logos/Logo Daher_posi.png');
	$objDrawing->setWidth(90);
	$objDrawing->setHeight(50);
	$objDrawing->setCoordinates('A1');
	$objDrawing->setOffsetX(10);
	$objDrawing->setOffsetY(8);
	$objDrawing->setWorksheet($sheet);
	
}
$sheet->setCellValue('L'.$Ligne,utf8_encode($rowAT['SigleDemandeur']));

$Ligne=$Ligne+1;
$sheet->setCellValue('A'.$Ligne,utf8_encode(AfficheDateJJ_MM_AAAA($rowAT['DateCreation'])));
$sheet->setCellValue('F'.$Ligne,utf8_encode($rowAT['Demandeur']));


//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="D-0250-3 formulaire de declaration des at.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

$chemin = '../../tmp/D-0250-3 formulaire de declaration des at.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
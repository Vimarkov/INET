<?php
session_start();
require_once("../Fonctions.php");
require_once("Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
require("../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();

if($LangueAffichage=="FR"){$excel = $workbook->load('D-0829-001-GRP.xlsx');}
else{$excel = $workbook->load('D-0829-001-GRP-en.xlsx');}

$sheet = $excel->getSheetByName('D-0829-001');


//PARTIE CAISSE DE LA REQUETE
$Requete2Caisse="
	SELECT Id,
	'Caisse' AS TYPESELECT,
	NumAAA AS NumAAA,
	SN AS SN,
	Num AS Num,
	Prix,
	-1 AS Id_TYPEMATERIEL,
	'Caisse' AS TYPEMATERIEL,
	(SELECT Libelle FROM tools_famillemateriel WHERE Id=Id_FamilleMateriel) AS FAMILLEMATERIEL,
	(SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType) AS LIBELLE_MODELEMATERIEL,
	(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fournisseur) AS LIBELLE_FOURNISSEUR,
	(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fabricant) AS LIBELLE_FABRICANT,
	(SELECT DateReception FROM tools_mouvement WHERE tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id AND tools_mouvement.EtatValidation IN (0,1) AND Suppr=0 AND Type=1 ORDER BY DateReception ASC LIMIT 1) AS DateReception,
	(SELECT Commentaire FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC LIMIT 1) AS Remarque,
	(SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id AND Suppr=0 AND Type=1 ORDER BY DateReception DESC, Id DESC LIMIT 1) AS DateDerniereAffectation,
	(
		SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=new_competences_prestation.Id_Plateforme)
		FROM tools_mouvement
		LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
		WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
	) AS LIBELLE_PLATEFORME,
	(
		SELECT new_competences_prestation.Id_Plateforme
		FROM tools_mouvement
		LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
		WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
	) AS Id_Plateforme,
	(
		SELECT new_competences_prestation.Libelle
		FROM tools_mouvement
		LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
		WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
	) AS LIBELLE_PRESTATION,
	(
		SELECT new_competences_pole.Libelle
		FROM tools_mouvement
		LEFT JOIN new_competences_pole ON tools_mouvement.Id_Pole=new_competences_pole.Id
		WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
	) AS LIBELLE_POLE,
	(
		SELECT tools_lieu.Libelle
		FROM tools_mouvement
		LEFT JOIN tools_lieu ON tools_mouvement.Id_Lieu=tools_lieu.Id
		WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
	) AS LIBELLE_LIEU,
	(
		SELECT CONCAT(tools_caissetype.Libelle,' n° ',tools_caisse.Num)
		FROM tools_mouvement
		LEFT JOIN tools_caisse ON tools_mouvement.Id_Caisse=tools_caisse.Id
		LEFT JOIN tools_caissetype ON tools_caisse.Id_CaisseType=tools_caissetype.Id
		WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
	) AS LIBELLE_CAISSETYPE,
	(
		SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom)
		FROM tools_mouvement
		LEFT JOIN new_rh_etatcivil ON tools_mouvement.Id_Personne=new_rh_etatcivil.Id
		WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0  AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
	) AS NOMPRENOM_PERSONNE
	";
$RequeteCaisse="FROM
	tools_caisse
WHERE 
	tools_caisse.Suppr=0 ";
$RequeteCaisse.=" AND Id = ".$_GET['Id']." ";


$Result=mysqli_query($bdd,$Requete2Caisse.$RequeteCaisse);
$NbEnreg=mysqli_num_rows($Result);

$Row=mysqli_fetch_array($Result);

$LIBELLE_POLE="";
if($Row['LIBELLE_POLE']<>""){$LIBELLE_POLE=" - ".$Row['LIBELLE_POLE'];}
								
$sheet->setCellValue('A11',utf8_encode($Row['NumAAA']." (n°".$Row['Num'].")"));
$lieu="";
if($Row['NOMPRENOM_PERSONNE']<>""){
	$lieu=$Row['NOMPRENOM_PERSONNE']." (".substr($Row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE." ".$Row['LIBELLE_PLATEFORME'].")";
}
elseif($Row['LIBELLE_CAISSETYPE']<>""){
	$lieu=$Row['LIBELLE_CAISSETYPE']." (".substr($Row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE." ".$Row['LIBELLE_PLATEFORME'].")";
}
elseif($Row['LIBELLE_LIEU']<>""){
	$lieu=$Row['LIBELLE_LIEU']." (".substr($Row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE." ".$Row['LIBELLE_PLATEFORME'].")";
}
elseif($Row['LIBELLE_LIEU']<>""){
	$lieu=$Row['LIBELLE_LIEU']." (".substr($Row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE." ".$Row['LIBELLE_PLATEFORME'].")";
}
else{
	$lieu=substr($Row['LIBELLE_PRESTATION'],0,7).$LIBELLE_POLE." ".$Row['LIBELLE_PLATEFORME']."";
}
$sheet->setCellValue('B11',utf8_encode($lieu));
$sheet->setCellValue('C11',utf8_encode(AfficheDateJJ_MM_AAAA($Row['DateDerniereAffectation'])));


$Id_Plateforme=0;
if($Row['Id_Plateforme']>0){$Id_Plateforme=$Row['Id_Plateforme'];}

if($Id_Plateforme>0){
	$req="SELECT Libelle, Logo FROM new_competences_plateforme WHERE Id=".$Id_Plateforme;
	$ResultPlat=mysqli_query($bdd,$req);
	$rowPlat=mysqli_fetch_array($ResultPlat);
	
	$sheet->setCellValue('F5',utf8_encode($rowPlat['Libelle']));
	if($rowPlat['Logo']<>""){
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('logo');
		$objDrawing->setDescription('PHPExcel logo');
		$objDrawing->setPath('../../Images/Logos/'.$rowPlat['Logo']);
		$objDrawing->setHeight(80);
		$objDrawing->setWidth(150);
		$objDrawing->setCoordinates('F1');
		$objDrawing->setOffsetX(30);
		$objDrawing->setOffsetY(8);
		$objDrawing->setWorksheet($sheet);
	}
}

//PARTIE OUTILS DE LA REQUETE
$Requete2="
	SELECT
		tools_materiel.Id AS ID,
		'Outils' AS TYPESELECT,
		NumAAA,
		SN,
		IF(tools_famillemateriel.Id_TypeMateriel=".$TypeTelephone.",NumTelephone,
			IF(tools_famillemateriel.Id_TypeMateriel=".$TypeClef.",NumClef,
				IF(tools_famillemateriel.Id_TypeMateriel=".$TypeMaqueDeControle.",NumMC,
					IF(tools_famillemateriel.Id_TypeMateriel=".$TypeInformatique.",NumPC,
						IF(tools_famillemateriel.Id_TypeMateriel=".$TypeVehicule.",Immatriculation,
							IF(tools_famillemateriel.Id_TypeMateriel=".$TypeMacaron.",ImmatriculationAssociee,'')
						)
					)
				)
			)
		) AS Num,
		Prix,
		tools_typemateriel.Id AS ID_TYPEMATERIEL,
		tools_typemateriel.Libelle AS TYPEMATERIEL,
		tools_famillemateriel.Libelle AS FAMILLEMATERIEL,
		tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
		(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fournisseur) AS LIBELLE_FOURNISSEUR,
		(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fabricant) AS LIBELLE_FABRICANT,
		(SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception ASC LIMIT 1) AS DateReception,
		(SELECT Commentaire FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC LIMIT 1) AS Remarque,
		(SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) AS DateDerniereAffectation
		";
$Requete="FROM
			tools_materiel
		LEFT JOIN
			tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
		LEFT JOIN
			tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
		LEFT JOIN
			tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
		WHERE
			tools_materiel.Suppr=0 ";

$Requete.=" AND (SELECT Id_Caisse FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) = ".$_GET['Id']." 
	ORDER BY LIBELLE_MODELEMATERIEL ";

$Result=mysqli_query($bdd,$Requete2.$Requete);
$NbEnreg=mysqli_num_rows($Result);

if($NbEnreg>41){
	$nb=59+($NbEnreg-41);

	$sheet->fromArray($sheet->rangeToArray('A59:H65'), null, 'A'.$nb);
	$sheet->removeRow(59, 65);
	
	$sheet->getStyle('C'.$nb.':H'.($nb+4).'')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000')))); 

	$sheet->insertNewRowBefore(20, $NbEnreg-41);
	
	$sheet->getStyle('A17'.':H'.($NbEnreg+16).'')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '000000'))));
	
	$nblignePlus=$NbEnreg-41;
	if($LangueAffichage=="FR"){
		$sheet->setCellValue('C'.(59+$nblignePlus),utf8_encode("Visa de l'utilisateur"));
		$sheet->setCellValue('C'.(60+$nblignePlus),utf8_encode("Inventaire Entrée"));
		$sheet->setCellValue('C'.(61+$nblignePlus),utf8_encode("Inventaire Intermédiaire"));
		$sheet->setCellValue('C'.(62+$nblignePlus),utf8_encode("Inventaire Sortie"));
		$sheet->setCellValue('C'.(63+$nblignePlus),utf8_encode("Visa de la hiérarchie"));
		
		$sheet->setCellValue('A'.(65+$nblignePlus),utf8_encode("* Engagement:"));
		$sheet->setCellValue('B'.(65+$nblignePlus),utf8_encode("Par sa signature, l'utilisateur confirme avoir vérifié que les outillages de ce contenant sont identifiés, marqués, correspondent à l'inventaire et qu'une vérification visuelle du bon état de chaque outillage a été réalisée."));
	}
	else{
		$sheet->setCellValue('C'.(59+$nblignePlus),utf8_encode("User's signature"));
		$sheet->setCellValue('C'.(60+$nblignePlus),utf8_encode("Input inventory"));
		$sheet->setCellValue('C'.(61+$nblignePlus),utf8_encode("Intermediate inventory"));
		$sheet->setCellValue('C'.(62+$nblignePlus),utf8_encode("Output inventory"));
		$sheet->setCellValue('C'.(63+$nblignePlus),utf8_encode("Hierarchy's signature"));
		
		$sheet->setCellValue('A'.(65+$nblignePlus),utf8_encode("* Engagement:"));
		$sheet->setCellValue('B'.(65+$nblignePlus),utf8_encode("By signing, the user confirms having checked that the tools in this container are identified, marked and match the inventory and that a visual check of their good condition has been carried out."));
	}
	
	$sheet->getStyle('B'.(65+$nblignePlus))->getAlignment()->setWrapText(true);
	$sheet->getRowDimension((65+$nblignePlus))->setRowHeight(30);
}

if($NbEnreg>0)
{
	$ligne=17;
	while($Row=mysqli_fetch_array($Result))
	{
		$num="";
		if($Row['Num']<>""){$num=" (".$Row['Num'].") ";}
		$sheet->setCellValue('A'.$ligne,utf8_encode($Row['LIBELLE_MODELEMATERIEL'].$num));
		$sheet->setCellValue('C'.$ligne,utf8_encode($Row['NumAAA']));
		$ligne++;
	}
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="D-0829-001-GRP.xlsx"');
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../tmp/D-0829-001-GRP.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
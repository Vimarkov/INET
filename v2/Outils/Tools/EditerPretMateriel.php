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

if($_SESSION['Langue']=="FR"){$excel = $workbook->load('D-0711-GRP-FR.xlsx');}
else{$excel = $workbook->load('D-0711-GRP-EN.xlsx');}

$sheet = $excel->getSheetByName('D-0711');

$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne, Trigramme FROM new_rh_etatcivil WHERE Id=".$_SESSION['Id_Personne']." ";
$ResultPers=mysqli_query($bdd,$req);
$RowPers=mysqli_fetch_array($ResultPers);

if($_SESSION["Langue"]=="FR"){
	$sheet->setCellValue('A5',utf8_encode('MIS A JOUR LE : '.AfficheDateJJ_MM_AAAA(date('Y-m-d'))));
	$sheet->setCellValue('C5',utf8_encode('NOM : '.$RowPers['Personne']));
	$sheet->setCellValue('E5',utf8_encode('VISA : '.$RowPers['Trigramme']));
}
else{
	$sheet->setCellValue('A5',utf8_encode('UPDATED ON : '.AfficheDateJJ_MM_AAAA(date('Y-m-d'))));
	$sheet->setCellValue('C5',utf8_encode('NAME : '.$RowPers['Personne']));
	$sheet->setCellValue('E5',utf8_encode('SIGNATURE : '.$RowPers['Trigramme']));
}

$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE Id=".$_GET['Id']." ";
$ResultPers=mysqli_query($bdd,$req);
$RowPers=mysqli_fetch_array($ResultPers);

$sheet->setCellValue('B9',utf8_encode($RowPers['Personne']));

//PARTIE OUTILS DE LA REQUETE
$Requete2="
SELECT
	tools_materiel.Id AS ID,
	'Outils' AS TYPESELECT,
	NumAAA,
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
	tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
	(SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) AS DateDerniereAffectation
	";
$Requete="FROM
		tools_materiel
	LEFT JOIN
		tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
	LEFT JOIN
		tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
	WHERE
		tools_materiel.Suppr=0 
		AND IF((SELECT tools_mouvement.Id_Caisse
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)>0,
		(
			SELECT (SELECT tools_mouvement.Id_Personne
			FROM tools_mouvement
			WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1)
			FROM tools_mouvement AS TAB_Mouvement
			WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation IN (0,1) AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
			ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
		),
	(
		SELECT tools_mouvement.Id_Personne
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation IN (0,1) AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
	)) = ".$_GET['Id']." ";

//PARTIE CAISSE DE LA REQUETE
$Requete2Caisse=" UNION ALL
	SELECT Id,
	'Caisse' AS TYPESELECT,
	NumAAA AS NumAAA,
	Num AS Num,
	(SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType) AS LIBELLE_MODELEMATERIEL,
	(SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (0,1) AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id AND Suppr=0 AND Type=1 ORDER BY DateReception DESC, Id DESC LIMIT 1) AS DateDerniereAffectation
	";
$RequeteCaisse="FROM
	tools_caisse
WHERE 
	tools_caisse.Suppr=0 ";
$RequeteCaisse.=" AND (SELECT Id_Personne 
			FROM tools_mouvement 
			WHERE TypeMouvement=0 
			AND tools_mouvement.Suppr=0
			AND tools_mouvement.Type=1 
			AND tools_mouvement.EtatValidation IN (0,1) 
			AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id 
			ORDER BY DateReception DESC, Id DESC LIMIT 1) = ".$_GET['Id']." ";

$Result=mysqli_query($bdd,$Requete2.$Requete.$Requete2Caisse.$RequeteCaisse);
$NbEnreg=mysqli_num_rows($Result);

if($NbEnreg>0)
{
	$ligne=14;
	while($Row=mysqli_fetch_array($Result))
	{
		if(isset($_GET['laDate'])){
			if($_GET['laDate']==$Row['DateDerniereAffectation']){
				$num="";
				if($Row['Num']<>""){$num=" (".$Row['Num'].") ";}
				$sheet->setCellValue('A'.$ligne,utf8_encode($Row['LIBELLE_MODELEMATERIEL'].$num));
				$sheet->setCellValue('B'.$ligne,utf8_encode($Row['NumAAA']));
				$sheet->setCellValue('C'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($Row['DateDerniereAffectation'])));
				$ligne++;
			}
		}
		else{
			$num="";
			if($Row['Num']<>""){$num=" (".$Row['Num'].") ";}
			$sheet->setCellValue('A'.$ligne,utf8_encode($Row['LIBELLE_MODELEMATERIEL'].$num));
			$sheet->setCellValue('B'.$ligne,utf8_encode($Row['NumAAA']));
			$sheet->setCellValue('C'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($Row['DateDerniereAffectation'])));
			$ligne++;
		}
	}
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="D-0711-Pret de materiel.xlsx"');
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../tmp/D-0711.xlsx';
$writer->save($chemin);
readfile($chemin);

?>
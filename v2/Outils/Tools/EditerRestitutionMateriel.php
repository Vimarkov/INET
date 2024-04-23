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

$excel = $workbook->load('RestitutionMateriel.xlsx');

$sheet = $excel->getSheetByName('Feuil1');

$req="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$_GET['Id']." ";
$ResultPers=mysqli_query($bdd,$req);
$RowPers=mysqli_fetch_array($ResultPers);

$sheet->setCellValue('C3',utf8_encode('Nom: '.$RowPers['Nom']));
$sheet->setCellValue('E3',utf8_encode('Prénom: '.$RowPers['Prenom']));

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
	tools_typemateriel.Id AS ID_TYPEMATERIEL,
	tools_typemateriel.Libelle AS TYPEMATERIEL,
	tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL
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

$Requete.=" AND Id_PersonneT = ".$_GET['Id']." ";

//PARTIE CAISSE DE LA REQUETE
$Requete2Caisse=" UNION ALL
	SELECT Id,
	'Caisse' AS TYPESELECT,
	NumAAA AS NumAAA,
	SN AS SN,
	Num AS Num,
	-1 AS Id_TYPEMATERIEL,
	'Caisse' AS TYPEMATERIEL,
	(SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType) AS LIBELLE_MODELEMATERIEL
	";
$RequeteCaisse="FROM
	tools_caisse
WHERE 
	tools_caisse.Suppr=0 ";
$RequeteCaisse.=" AND Id_PersonneT = ".$_GET['Id']." ";
$RequeteOrder=" ORDER BY TYPEMATERIEL, LIBELLE_MODELEMATERIEL";

$Result=mysqli_query($bdd,$Requete2.$Requete.$Requete2Caisse.$RequeteCaisse.$RequeteOrder);
$NbEnreg=mysqli_num_rows($Result);

if($NbEnreg>0)
{
	$ligne=12;
	while($Row=mysqli_fetch_array($Result))
	{
		$num="";
		if($Row['Num']<>""){$num=" (".$Row['Num'].") ";}
		$sheet->setCellValue('A'.$ligne,utf8_encode($Row['NumAAA']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($Row['TYPEMATERIEL']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($Row['LIBELLE_MODELEMATERIEL']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($Row['SN'].$num));
		$ligne++;
	}
}


//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
if($LangueAffichage=="FR"){header('Content-Disposition: attachment;filename="RestitutionMateriel.xlsx"');}
else{header('Content-Disposition: attachment;filename="D-0711-Loan of equipment.xlsx"');}
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../tmp/RestitutionMateriel.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Fonctions.php");
require_once("Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('Template_Fiche de vie.xlsx');
$sheet = $excel->getSheetByName('Page 1');

$Id=$_GET['Id'];

$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne, Trigramme FROM new_rh_etatcivil WHERE Id=".$_SESSION['Id_Personne'];
$Result=mysqli_query($bdd,$req);
$Row=mysqli_fetch_array($Result);
$sheet->setCellValue('A3',utf8_encode("Mise à jour le : ".AfficheDateJJ_MM_AAAA(date('Y-m-d'))));
$sheet->setCellValue('F3',utf8_encode($Row['Personne']));
$sheet->setCellValue('I3',utf8_encode($Row['Trigramme']));

$Requete="
SELECT
	tools_materiel.Id,
	(SELECT Id_FamilleMateriel FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) AS ID_FAMILLEMATERIEL,
	(SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE Id=(SELECT Id_FamilleMateriel FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel)) AS ID_TYPEMATERIEL,
	(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fournisseur) AS LIBELLE_FOURNISSEUR,
	(SELECT Libelle FROM tools_tiers WHERE Id=Id_Fabricant) AS LIBELLE_FABRICANT,
	(SELECT DateReception FROM tools_mouvement WHERE TypeMouvement=0 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id ORDER BY DateReception ASC LIMIT 1) AS DateReception,
	tools_modelemateriel.Libelle AS LIBELLE_MODELEMATERIEL,
	(
		SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme)
		FROM tools_mouvement
		LEFT JOIN new_competences_prestation ON tools_mouvement.Id_Prestation=new_competences_prestation.Id
		WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=0 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
	) AS Plateforme,
	NumAAA,
	SN,
	TypeECME,
	ClassePrecision,
	PeriodiciteVerification,
	Remarques,
	InfosTechnique
FROM
	tools_materiel
LEFT JOIN
	tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
LEFT JOIN
	tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
LEFT JOIN
	tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
WHERE
	tools_materiel.Id='".$Id."';";
$Result=mysqli_query($bdd,$Requete);
$Row=mysqli_fetch_array($Result);

$sheet->setCellValue('H2',utf8_encode($Row['Plateforme']));

$sheet->setCellValue('B5',utf8_encode($Row['NumAAA']));
$sheet->setCellValue('B6',utf8_encode($Row['LIBELLE_MODELEMATERIEL']));
$sheet->setCellValue('B7',utf8_encode($Row['LIBELLE_FABRICANT']));
$sheet->setCellValue('B8',utf8_encode($Row['LIBELLE_FOURNISSEUR']));
$sheet->setCellValue('B9',utf8_encode($Row['TypeECME']));
$sheet->setCellValue('B10',utf8_encode($Row['SN']));

$sheet->setCellValue('B12',utf8_encode($Row['ClassePrecision']));

$sheet->setCellValue('B15',utf8_encode(AfficheDateJJ_MM_AAAA($Row['DateReception'])));
$sheet->setCellValue('B16',utf8_encode(AfficheDateJJ_MM_AAAA($Row['DateReception'])));

$sheet->setCellValue('D18',utf8_encode($Row['PeriodiciteVerification']." Mois"));

			

$req="SELECT Id,
(SELECT Libelle FROM tools_tiers WHERE Id=FV_Id_Laboratoire) AS Organisme,
FV_DateEtalonnage,FV_Conformite,FV_NumPV,FV_BonCommande,FV_Prix,FV_Remarques,
(SELECT Libelle FROM tools_decision WHERE Id=FV_Id_Decision) AS Decision
FROM tools_mouvement
WHERE Suppr=0
AND Id_Materiel__Id_Caisse=".$Id."
AND TypeMouvement=1
AND Type=0
ORDER BY FV_DateEtalonnage ASC, Id ASC
";

$Result=mysqli_query($bdd,$req);
$NbEnreg=mysqli_num_rows($Result);

$laboratoire="";
$reparation=0;
$declassement=0;
$reforme=0;
if($NbEnreg>0)
{
	$ligne=6;
	while($Row=mysqli_fetch_array($Result))
	{
		if($Row['FV_Conformite']==1){$conforme="OUI";}
		else{$conforme="NON";}
			
		$sheet->setCellValue('F'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($Row['FV_DateEtalonnage'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode($Row['Organisme']));
		$sheet->setCellValue('H'.$ligne,utf8_encode($Row['FV_NumPV']));
		$sheet->setCellValue('I'.$ligne,utf8_encode($conforme));
		
		$laboratoire=$Row['Organisme'];
		
		if($Row['Decision']=="Déclassement"){
			$reparation=0;
			$declassement=1;
			$reforme=0;
		}
		elseif($Row['Decision']=="Réparation"){
			$reparation=1;
			$declassement=0;
			$reforme=0;
		}
		elseif($Row['Decision']=="Réforme"){
			$reparation=0;
			$declassement=0;
			$reforme=1;
		}
		$ligne++;
	}
}

$sheet->setCellValue('C19',utf8_encode($laboratoire));

if($reparation==1){
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('checked');
	$objDrawing->setDescription('checked');
	$objDrawing->setPath('../../Images/checked.png');
	$objDrawing->setCoordinates('A21');
	$objDrawing->setOffsetX(5);
	$objDrawing->setOffsetY(5);
	$objDrawing->setWorksheet($sheet);
}
else{
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('checkednot');
	$objDrawing->setDescription('checkednot');
	$objDrawing->setPath('../../Images/checkednot.png');
	$objDrawing->setCoordinates('A21');
	$objDrawing->setOffsetX(5);
	$objDrawing->setOffsetY(5);
	$objDrawing->setWorksheet($sheet);
}

if($declassement==1){
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('checked');
	$objDrawing->setDescription('checked');
	$objDrawing->setPath('../../Images/checked.png');
	$objDrawing->setCoordinates('A22');
	$objDrawing->setOffsetX(5);
	$objDrawing->setOffsetY(5);
	$objDrawing->setWorksheet($sheet);
}
else{
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('checkednot');
	$objDrawing->setDescription('checkednot');
	$objDrawing->setPath('../../Images/checkednot.png');
	$objDrawing->setCoordinates('A22');
	$objDrawing->setOffsetX(5);
	$objDrawing->setOffsetY(5);
	$objDrawing->setWorksheet($sheet);
}

if($reforme==1){
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('checked');
	$objDrawing->setDescription('checked');
	$objDrawing->setPath('../../Images/checked.png');
	$objDrawing->setCoordinates('A23');
	$objDrawing->setOffsetX(5);
	$objDrawing->setOffsetY(5);
	$objDrawing->setWorksheet($sheet);
}
else{
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('checkednot');
	$objDrawing->setDescription('checkednot');
	$objDrawing->setPath('../../Images/checkednot.png');
	$objDrawing->setCoordinates('A23');
	$objDrawing->setOffsetX(5);
	$objDrawing->setOffsetY(5);
	$objDrawing->setWorksheet($sheet);
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Fiche de vie.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$chemin = '../../tmp/Fiche de vie.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
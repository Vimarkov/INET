<?php
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../../ConnexioniSansBody.php';

//Supprimer le contenu de la table donnée ACP
$result=mysqli_query($bdd,"DELETE FROM sp_donneeacp;");

$XLSXDocument = new PHPExcel_Reader_Excel2007();
$Excel = $XLSXDocument->load('Extract ACP/Extract ACP.xlsx');

/**
* récupération de la première feuille du fichier Excel
* @var PHPExcel_Worksheet $sheet
*/
$sheet = $Excel->getSheet(0);
 
// On boucle sur les lignes
echo "<table>";
$NumLigne=1;
foreach ($sheet->getRowIterator() as $lig => $row){
	if($NumLigne>5){
		echo "<tr style='border:1px solid;'>";
		$req="INSERT INTO sp_donneeacp(ACP_Id,MSN,MCA,Type,Reference,LienHierarchique,Caec,ATASubATA,StatutUtilisateur,TAI_Restant,";
		$req.="DateCreation,DateFinRevisee,RespInterne,RespN_1,Macro_Cible,Cible,Sous_Cible) VALUES (";
		
	   // On boucle sur les cellule de la ligne
	   
	   $cellIterator = $row->getCellIterator();
	   $cellIterator->setIterateOnlyExistingCells(false);
		
		$NumCol=1;
	   foreach ($cellIterator as $col => $cell) {
			if($NumCol==11 || $NumCol==12){
				$InvDate= $cell->getValue();
				$InvDate = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($InvDate)); 
				echo "<td style='border:1px solid;'>". $InvDate."</td>";
				$req.= "'".$InvDate."',";
			}
			else{
				$req.= "'".addslashes($cell->getValue())."',";
				echo "<td style='border:1px solid;'>".$cell->getValue()."</td>";
			}
		  
		  $NumCol++;
	   }
		$req=substr($req,0,-1);
		$req.=");";
		$result=mysqli_query($bdd,$req);
		echo "</tr>";
	}
	$NumLigne++;
	//echo "<script>window.close();</script>";
}
echo "</table>";

?>
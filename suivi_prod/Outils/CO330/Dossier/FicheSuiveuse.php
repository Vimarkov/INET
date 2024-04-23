<?php
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../../ConnexioniSansBody.php';

function AfficheDateFR($Date)
{
	if($Date==Null || $Date=='' || $Date<='0001-01-01'){$dateReq="";}
	else
	{
		//Verifier si Google CHROME (true) ou Autre (fale)
		if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
		else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
		else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
		if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
		else {$NavigOk = false;}

		if($NavigOk ==1)
		{
			$tabDateTransfert = explode('-', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateReq = date("Y-m-d", $timestampTransfert);
		}
		else
		{
			$tabDateTransfert = explode('-', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateReq = date("d/m/Y", $timestampTransfert);
		}
	}
	
	return $dateReq;
}

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
$excel = $workbook->load('Template_FicheSuiveuse2.xlsx');

$sheet = $excel->getSheetByName('FOLIO');
$sheetAR = $excel->getSheetByName('AR');

$Id = $_GET['Id_Dossier'];
$Id_FI=0;
if(isset($_GET['Id_FI'])){$Id_FI=$_GET['Id_FI'];}

$req="SELECT MSN,TypeACP AS Type,SectionACP AS Section,Priorite,Reference,ReferenceAM,ReferenceNC,ReferencePF,NumDERO,
	(SELECT Libelle FROM sp_poste WHERE Id=Id_Poste) AS Poste,TAI_RestantACP,";
$req.="Id_Urgence,CaecACP AS Caec,sp_olwdossier.DateCreation,CommentaireZICIA,Titre,sp_olwficheintervention.NumDA, ";
$req.="(SELECT sp_olwzonedetravail.Libelle FROM sp_olwzonedetravail WHERE sp_olwzonedetravail.Id=sp_olwdossier.Id_ZoneDeTravail) AS Zone, ";
$req.="(SELECT sp_client.Libelle FROM sp_client WHERE sp_client.Id=sp_olwdossier.Id_Client) AS Client, ";
$req.="(SELECT new_rh_etatcivil.Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwdossier.Id_Personne) AS Nom, ";
$req.="(SELECT new_rh_etatcivil.Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwdossier.Id_Personne) AS Prenom ";
$req.="FROM sp_olwficheintervention LEFT JOIN sp_olwdossier ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id ";
$req.="WHERE sp_olwficheintervention.Id=".$Id_FI."";

$result=mysqli_query($bdd,$req);
$row=mysqli_fetch_array($result);

$sheet->setCellValue('O7',date('d/m/Y'));
$sheet->setCellValue('Z20',date('d/m/Y'));

$sheet->setCellValue('U9',$row['MSN']);
$sheet->setCellValue('P10',$row['Reference']);
$sheet->setCellValue('U12',$row['Poste']);
$sheet->setCellValue('V33',$row['Prenom']." ".$row['Nom']);
$sheet->setCellValue('Q19',utf8_encode(stripslashes($row['Titre'])));
$sheet->setCellValue('P14',$row['TAI_RestantACP']);

$sheetAR->setCellValue('S7',date('d/m/Y'));
$sheetAR->setCellValue('AA18',date('d/m/Y'));

$sheetAR->setCellValue('V9',$row['MSN']);
$sheetAR->setCellValue('Q12',$row['Reference']);
$sheetAR->setCellValue('V12',$row['Poste']);
$sheetAR->setCellValue('W33',$row['Prenom']." ".$row['Nom']);
$sheetAR->setCellValue('R20',utf8_encode(stripslashes($row['Titre'])));
$sheetAR->setCellValue('U19',$row['NumDA']);
$sheetAR->setCellValue('Q14',$row['TAI_RestantACP']);


/*
$sheet->setCellValue('S6',AfficheDateFR($row['DateCreation']));



$sheet->setCellValue('N10',$row['Caec']);

$sheet->setCellValue('T11',$row['NumDERO']);


$sheet->setCellValue('O22',utf8_encode(stripslashes($row['CommentaireZICIA'])));
*/

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="FicheSuiveuse.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

$chemin = '../../../tmp/FicheSuiveuse.xlsx';
$writer->save($chemin);
readfile($chemin);

?>
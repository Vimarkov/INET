<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
$workbook = new PHPExcel;

function TrsfDate_($Date)
{
	if($Date==Null || $Date=='' || $Date<='01-01-0001'){$dateReq="0001-01-01";}
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
			$tabDateTransfert = explode('/', $Date);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[0], $tabDateTransfert[2]);
			$dateReq = date("Y-m-d", $timestampTransfert);
		}
	}
	
	return $dateReq;
}
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

$reqAnalyse="SELECT DISTINCT sp_olwdossier.MSN ";
$req2="SELECT sp_olwficheintervention.Id,sp_olwficheintervention.Id_Dossier,sp_olwdossier.MSN,sp_olwdossier.Reference,sp_olwdossier.ReferenceNC,";
$req2.="sp_olwdossier.Titre,sp_olwficheintervention.NumFI,sp_olwdossier.Priorite,sp_olwficheintervention.DateIntervention,";
$req2.="sp_olwficheintervention.DateInterventionQ,sp_olwficheintervention.Vacation,sp_olwdossier.TAI_RestantACP,";
$req2.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwficheintervention.Id_Createur) AS CreateurIC, ";
$req2.="(SELECT sp_olwzonedetravail.Libelle FROM sp_olwzonedetravail WHERE sp_olwzonedetravail.Id=sp_olwdossier.Id_ZoneDeTravail) AS Zone, ";
$req2.="(SELECT sp_client.Libelle FROM sp_client WHERE sp_client.Id=sp_olwdossier.Id_Client) AS Client,sp_olwdossier.SectionACP, ";
$req2.="sp_olwficheintervention.PosteAvionACP,sp_olwficheintervention.TravailRealise,sp_olwficheintervention.Id_StatutPROD,sp_olwficheintervention.Id_StatutQUALITE ";
$req="FROM sp_olwficheintervention LEFT JOIN sp_olwdossier ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id ";
$req.="WHERE sp_olwdossier.Id_Prestation=379 AND ";
if($_SESSION['MSN2']<>""){
	$tab = explode(";",$_SESSION['MSN2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwdossier.MSN=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['NumDossier2']<>""){
	$tab = explode(";",$_SESSION['NumDossier2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwdossier.Reference='".addslashes($valeur)."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Titre2']<>""){
	$tab = explode(";",$_SESSION['Titre2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwdossier.Titre LIKE '%".addslashes($valeur)."%' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Section2']<>""){
	$tab = explode(";",$_SESSION['Section2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwdossier.SectionACP='".addslashes($valeur)."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Zone2']<>""){
	$tab = explode(";",$_SESSION['Zone2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwdossier.Id_ZoneDeTravail=".substr($valeur,1)." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Priorite2']<>""){
	$tab = explode(";",$_SESSION['Priorite2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwdossier.Priorite=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['CreateurDossier2']<>""){
	$tab = explode(";",$_SESSION['CreateurDossier2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwdossier.Id_Personne=".substr($valeur,1)." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Client2']<>""){
	$tab = explode(";",$_SESSION['Client2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwdossier.Id_Client='".substr($valeur,1)."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['TravailRealise2']<>""){
	$tab = explode(";",$_SESSION['TravailRealise2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwficheintervention.TravailRealise LIKE '%".addslashes($valeur)."%' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Vacation2']<>""){
	$tab = explode(";",$_SESSION['Vacation2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwficheintervention.Vacation='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['CE2']<>""){
	$tab = explode(";",$_SESSION['CE2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwficheintervention.Id_PROD=".substr($valeur,1)." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['SansDate2']=="oui"){
	$req.=" ( ";
	$req.="sp_olwficheintervention.DateIntervention <= '0001-01-01' OR ";
}
if($_SESSION['DateDebut2']<>"" || $_SESSION['DateFin2']<>""){
	$req.=" ( ";
	if($_SESSION['DateDebut2']<>""){
		$req.="sp_olwficheintervention.DateIntervention >= '". TrsfDate_($_SESSION['DateDebut2'])."' ";
		$req.=" AND ";
	}
	if($_SESSION['DateFin2']<>""){
		$req.="sp_olwficheintervention.DateIntervention <= '". TrsfDate_($_SESSION['DateFin2'])."' ";
		$req.=" ";
	}
	if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
	$req.=" ) ";
}
if(substr($req,strlen($req)-3)== "OR "){$req=substr($req,0,-3);}
if($_SESSION['SansDate2']=="oui"){
	$req.=" ) ";
}
if($_SESSION['SansDate2']=="oui" || $_SESSION['DateDebut2']<>"" || $_SESSION['DateFin2']<>""){
	$req.=" AND ";
}
if($_SESSION['VacationQUALITE2']<>""){
	$tab = explode(";",$_SESSION['VacationQUALITE2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwficheintervention.VacationQ='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['IQ2']<>""){
	$tab = explode(";",$_SESSION['IQ2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwficheintervention.Id_QUALITE=".substr($valeur,1)." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Stamp2']<>""){
	$tab = explode(";",$_SESSION['Stamp2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$reqIQ="SELECT Id_Personne FROM new_competences_personne_stamp WHERE Num_Stamp='".$valeur."'";
			$resultIQ=mysqli_query($bdd,$reqIQ);
			$nbResultaIQ=mysqli_num_rows($resultIQ);
			if($nbResultaIQ>0){
				while($rowIQ=mysqli_fetch_array($resultIQ)){
					$req.="sp_olwficheintervention.Id_QUALITE=".$rowIQ['Id_Personne']." OR ";
				}
			}
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['SansDateQUALITE2']=="oui"){
	$req.=" ( ";
	$req.="sp_olwficheintervention.DateInterventionQ <= '0001-01-01' OR ";
}
if($_SESSION['DateDebutQUALITE2']<>"" || $_SESSION['DateFinQUALITE2']<>""){
	$req.=" ( ";
	if($_SESSION['DateDebutQUALITE2']<>""){
		$req.="sp_olwficheintervention.DateInterventionQ >= '". TrsfDate_($_SESSION['DateDebutQUALITE2'])."' ";
		$req.=" AND ";
	}
	if($_SESSION['DateFinQUALITE2']<>""){
		$req.="sp_olwficheintervention.DateInterventionQ <= '". TrsfDate_($_SESSION['DateFinQUALITE2'])."' ";
		$req.=" ";
	}
	if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
	$req.=" ) ";
}
if(substr($req,strlen($req)-3)== "OR "){$req=substr($req,0,-3);}
if($_SESSION['SansDateQUALITE2']=="oui"){
	$req.=" ) ";
}
if($_SESSION['SansDateQUALITE2']=="oui" || $_SESSION['DateDebutQUALITE2']<>"" || $_SESSION['DateFinQUALITE2']<>""){
	$req.=" AND ";
}
if($_SESSION['NumIC2']<>""){
	$tab = explode(";",$_SESSION['NumIC2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwficheintervention.NumFI='".addslashes($valeur)."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Poste2']<>""){
	$tab = explode(";",$_SESSION['Poste2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwficheintervention.PosteAvionACP='".addslashes($valeur)."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['CreateurIC2']<>""){
	$tab = explode(";",$_SESSION['CreateurIC2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_olwficheintervention.Id_Createur=".substr($valeur,1)." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['StatutIC2']<>""){
	$tab = explode(";",$_SESSION['StatutIC2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			if($valeur=="(vide)"){$req.="sp_olwficheintervention.Id_StatutPROD='' OR sp_olwficheintervention.Id_StatutQUALITE='' OR ";}
			elseif($valeur=="TFS" || $valeur=="QARJ"){$req.="sp_olwficheintervention.Id_StatutPROD='".$valeur."' OR ";}
			elseif($valeur=="TVS" || $valeur=="CERT"){$req.="sp_olwficheintervention.Id_StatutQUALITE='".$valeur."' OR ";}
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}

if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}

$result=mysqli_query($bdd,$reqAnalyse.$req." ORDER BY MSN");
$nbResulta=mysqli_num_rows($result);

$reqFin="";
if($_SESSION['TriGeneral']<>""){
	$reqFin=" ORDER BY ".substr($_SESSION['TriGeneral'],0,-1);
}
$result2=mysqli_query($bdd,$req2.$req.$reqFin);
$nbResulta2=mysqli_num_rows($result2);

$PremierMSN=true;
if($nbResulta>0){
	while($row=mysqli_fetch_array($result)){
		//Création de l'onglet
		if($PremierMSN==true){
			$sheet = $workbook->getActiveSheet();
			$PremierMSN=false;
		}
		else{
			$sheet = $workbook->createSheet();
		}
		$sheet->setTitle($row['MSN']);
		
		$sheet->setCellValue('A1',utf8_encode("MSN"));
		$sheet->setCellValue('B1',utf8_encode("N° OF"));
		$sheet->setCellValue('C1',utf8_encode("Client"));
		$sheet->setCellValue('D1',utf8_encode("Zone"));
		$sheet->setCellValue('E1',utf8_encode("Priorité"));
		$sheet->setCellValue('F1',utf8_encode("TAI restant"));
		$sheet->setCellValue('G1',utf8_encode("Titre"));
		$sheet->setCellValue('H1',utf8_encode("Date d'intervention"));
		$sheet->setCellValue('I1',utf8_encode("Vacation"));
		$sheet->setCellValue('J1',utf8_encode("N° FI"));
		$sheet->setCellValue('K1',utf8_encode("Travail à réaliser"));
		$sheet->setCellValue('L1',utf8_encode("Poste d'intervention"));
		$sheet->setCellValue('M1',utf8_encode("Statut PROD"));
		$sheet->setCellValue('N1',utf8_encode("Statut QUALITE"));
		$sheet->setCellValue('O1',utf8_encode("Création IC"));
		$sheet->setCellValue('P1',utf8_encode("Section"));
		
		$sheet->getStyle('A1:P1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A1:P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A1:P1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheet->getStyle('A1:P1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
		$sheet->getStyle('A1:P1')->getFont()->setBold(true);
		$sheet->getStyle('A1:P1')->getFont()->getColor()->setRGB('1f49a6');
		
		$sheet->getColumnDimension('A')->setWidth(8);
		$sheet->getColumnDimension('B')->setWidth(12);
		$sheet->getColumnDimension('C')->setWidth(12);
		$sheet->getColumnDimension('D')->setWidth(30);
		$sheet->getColumnDimension('E')->setWidth(30);
		$sheet->getColumnDimension('F')->setWidth(12);
		$sheet->getColumnDimension('G')->setWidth(25);
		$sheet->getColumnDimension('H')->setWidth(18);
		$sheet->getColumnDimension('I')->setWidth(15);
		$sheet->getColumnDimension('J')->setWidth(15);
		$sheet->getColumnDimension('K')->setWidth(25);
		$sheet->getColumnDimension('L')->setWidth(15);
		$sheet->getColumnDimension('M')->setWidth(15);
		$sheet->getColumnDimension('N')->setWidth(15);
		$sheet->getColumnDimension('O')->setWidth(15);
		$sheet->getColumnDimension('P')->setWidth(15);
		
		
		$ligne=2;
		mysqli_data_seek($result2,0);
		while($row2=mysqli_fetch_array($result2)){
			if($row2['MSN']==$row['MSN']){
				
				$Priorite="";
				if($row2['Priorite']==1){$Priorite="Low";}
				elseif($row2['Priorite']==2){$Priorite="Medium";}
				else{$Priorite="High";}

				$Vacation="";
				if($row2['Vacation']=="J"){$vacation="Jour";}
				elseif($row2['Vacation']=="S"){$vacation="Soir";}
				elseif($row2['Vacation']=="N"){$vacation="Nuit";}
				elseif($row2['Vacation']=="VSD Jour"){$vacation="VSD Jour";}
				elseif($row2['Vacation']=="VSD Nuit"){$vacation="VSD Nuit";}

				$sheet->setCellValue('A'.$ligne,utf8_encode($row2['MSN']));
				$sheet->setCellValue('B'.$ligne,utf8_encode($row2['Reference']));
				$sheet->setCellValue('C'.$ligne,utf8_encode($row2['Client']));
				$sheet->setCellValue('D'.$ligne,utf8_encode($row2['Zone']));
				$sheet->setCellValue('E'.$ligne,utf8_encode($Priorite));
				$sheet->setCellValue('F'.$ligne,utf8_encode($row2['TAI_RestantACP']));
				$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($row2['Titre'])));
				$sheet->setCellValue('H'.$ligne,utf8_encode(AfficheDateFR($row2['DateIntervention'])));
				$sheet->setCellValue('I'.$ligne,utf8_encode($Vacation));
				$sheet->setCellValue('J'.$ligne,utf8_encode($row2['NumFI']));
				$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($row2['TravailRealise'])));
				$sheet->setCellValue('L'.$ligne,utf8_encode($row2['PosteAvionACP']));
				$sheet->setCellValue('M'.$ligne,utf8_encode($row2['Id_StatutPROD']));
				$sheet->setCellValue('N'.$ligne,utf8_encode($row2['Id_StatutQUALITE']));
				$sheet->setCellValue('O'.$ligne,utf8_encode($row2['CreateurIC']));
				$sheet->setCellValue('P'.$ligne,utf8_encode($row2['SectionACP']));
				
				$sheet->getStyle('A'.$ligne.':P'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
				$ligne++;
			}
		}
	}
}
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_Dossier.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_Dossier.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
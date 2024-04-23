<?php
session_start();
require("../../ConnexioniSansBody.php");
require("../../Fonctions.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
$workbook = new PHPExcel;

$req2="SELECT sp_olwficheintervention.Id,sp_olwficheintervention.Id_Dossier,sp_olwdossier.MSN,sp_olwdossier.Reference,sp_olwdossier.ReferenceNC,sp_olwdossier.TypeACP,
		(SELECT sp_olwzonedetravail.Libelle FROM sp_olwzonedetravail WHERE sp_olwzonedetravail.Id=sp_olwdossier.Id_ZoneDeTravail) AS Zone,";
$req2.="sp_olwdossier.Titre,sp_olwficheintervention.NumFI,sp_olwdossier.Priorite,sp_olwficheintervention.DateIntervention,sp_olwficheintervention.DateCreation,";
$req2.="sp_olwficheintervention.DateInterventionQ,sp_olwficheintervention.Vacation,sp_olwdossier.TAI_RestantACP,sp_olwficheintervention.TempsQUALITE,";
$req2.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwficheintervention.Id_Createur) AS CreateurIC,TempsST, ";
$req2.="(SELECT sp_olwzonedetravail.Libelle FROM sp_olwzonedetravail WHERE sp_olwzonedetravail.Id=sp_olwdossier.Id_ZoneDeTravail) AS Zone, 
		(SELECT Libelle FROM sp_pole WHERE sp_pole.Id=sp_olwficheintervention.Id_Pole) AS Pole, ";
$req2.="(SELECT sp_client.Libelle FROM sp_client WHERE sp_client.Id=sp_olwdossier.Id_Client) AS Client,sp_olwdossier.SectionACP, ";
$req2.="sp_olwficheintervention.PosteAvionACP,sp_olwficheintervention.TravailRealise,sp_olwficheintervention.Id_StatutPROD,sp_olwficheintervention.Id_StatutQUALITE ";
$req="FROM sp_olwficheintervention LEFT JOIN sp_olwdossier ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id ";
$req.="WHERE sp_olwdossier.Id_Prestation=1539 AND ";
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
			elseif($valeur=="TFS" || $valeur=="TERA" || $valeur=="RETP" || $valeur=="A RELANCER"){$req.="sp_olwficheintervention.Id_StatutPROD='".$valeur."' OR ";}
			elseif($valeur=="TVS" || $valeur=="TERC" || $valeur=="RETQ"){$req.="sp_olwficheintervention.Id_StatutQUALITE='".$valeur."' OR ";}
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}

if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}

$reqFin="";
if($_SESSION['TriGeneral']<>""){
	$reqFin=" ORDER BY ".substr($_SESSION['TriGeneral'],0,-1);
}

$result2=mysqli_query($bdd,$req2.$req.$reqFin);
$nbResulta2=mysqli_num_rows($result2);

$sheet = $workbook->getActiveSheet();
		
$sheet->setCellValue('A1',utf8_encode("MSN"));
$sheet->setCellValue('B1',utf8_encode("N° OF"));
$sheet->setCellValue('C1',utf8_encode("N° AM/NC"));
$sheet->setCellValue('D1',utf8_encode("Type dossier"));
$sheet->setCellValue('E1',utf8_encode("Client"));
$sheet->setCellValue('F1',utf8_encode("Zone"));
$sheet->setCellValue('G1',utf8_encode("Titre"));
$sheet->setCellValue('H1',utf8_encode("Date prépa"));
$sheet->setCellValue('I1',utf8_encode("Temps support technique"));
$sheet->setCellValue('J1',utf8_encode("Date d'intervention"));
$sheet->setCellValue('K1',utf8_encode("N° FI"));
$sheet->setCellValue('L1',utf8_encode("Statut PROD"));
$sheet->setCellValue('M1',utf8_encode("Statut QUALITE"));
$sheet->setCellValue('N1',utf8_encode("Création IC"));
$sheet->setCellValue('O1',utf8_encode("Nb heures intervention PROD"));
$sheet->setCellValue('P1',utf8_encode("Nb heures travail PROD"));
$sheet->setCellValue('Q1',utf8_encode("Date intervention QUALITE"));
$sheet->setCellValue('R1',utf8_encode("Temps de contrôle"));


$sheet->getStyle('A1:R1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:R1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:R1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:R1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:R1')->getFont()->setBold(true);
$sheet->getStyle('A1:R1')->getFont()->getColor()->setRGB('1f49a6');

$sheet->getColumnDimension('A')->setWidth(8);
$sheet->getColumnDimension('B')->setWidth(12);
$sheet->getColumnDimension('C')->setWidth(12);
$sheet->getColumnDimension('D')->setWidth(12);
$sheet->getColumnDimension('E')->setWidth(25);
$sheet->getColumnDimension('F')->setWidth(18);
$sheet->getColumnDimension('G')->setWidth(15);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('I')->setWidth(25);
$sheet->getColumnDimension('J')->setWidth(15);
$sheet->getColumnDimension('K')->setWidth(15);
$sheet->getColumnDimension('L')->setWidth(15);
$sheet->getColumnDimension('M')->setWidth(15);
$sheet->getColumnDimension('N')->setWidth(15);
$sheet->getColumnDimension('O')->setWidth(20);
$sheet->getColumnDimension('P')->setWidth(20);
$sheet->getColumnDimension('Q')->setWidth(15);
		
$ligne=2;
mysqli_data_seek($result2,0);
while($row2=mysqli_fetch_array($result2)){
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
	
	$TempsPasseTotalFI=0;
	$TempsTravailTotalFI=0;
	$req="SELECT TempsPasse,TempsTravail 
	FROM sp_olwfi_travaileffectue 
	WHERE Id_FI=".$row2['Id']." ";
	$resultTP=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($resultTP);
	if ($nbResulta>0){
		while($rowCompagnon=mysqli_fetch_array($resultTP)){
			$TempsPasseTotalFI+=$rowCompagnon['TempsPasse'];
			$TempsTravailTotalFI+=$rowCompagnon['TempsTravail'];
		}
	}
	$sheet->setCellValue('A'.$ligne,utf8_encode($row2['MSN']));
	$sheet->setCellValue('B'.$ligne,utf8_encode($row2['Reference']));
	$sheet->setCellValue('C'.$ligne,utf8_encode($row2['ReferenceNC']));
	$sheet->setCellValue('D'.$ligne,utf8_encode($row2['TypeACP']));
	$sheet->setCellValue('E'.$ligne,utf8_encode($row2['Client']));
	$sheet->setCellValue('F'.$ligne,utf8_encode($row2['Zone']));
	$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($row2['Titre'])));
	if(AfficheDateJJ_MM_AAAA($row2['DateCreation'])<>""){
		$date = explode("/",AfficheDateJJ_MM_AAAA($row2['DateCreation']));
		$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[2], $date[1], $date[0]);
		$sheet->setCellValue('H'.$ligne,$time);
		$sheet->getStyle('H'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	}
	$sheet->setCellValue('I'.$ligne,utf8_encode($row2['TempsST']));
	if(AfficheDateJJ_MM_AAAA($row2['DateIntervention'])<>""){
		$date = explode("/",AfficheDateJJ_MM_AAAA($row2['DateIntervention']));
		$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[2], $date[1], $date[0]);
		$sheet->setCellValue('J'.$ligne,$time);
		$sheet->getStyle('J'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	}
	$sheet->setCellValue('K'.$ligne,utf8_encode($row2['NumFI']));
	$sheet->setCellValue('L'.$ligne,utf8_encode($row2['Id_StatutPROD']));
	$sheet->setCellValue('M'.$ligne,utf8_encode($row2['Id_StatutQUALITE']));
	$sheet->setCellValue('N'.$ligne,utf8_encode($row2['CreateurIC']));
	$sheet->setCellValue('O'.$ligne,utf8_encode($TempsPasseTotalFI));
	$sheet->setCellValue('P'.$ligne,utf8_encode($TempsTravailTotalFI));
	if(AfficheDateJJ_MM_AAAA($row2['DateInterventionQ'])<>""){
		$date = explode("/",AfficheDateJJ_MM_AAAA($row2['DateInterventionQ']));
		$time = PHPExcel_Shared_Date::FormattedPHPToExcel($date[2], $date[1], $date[0]);
		$sheet->setCellValue('Q'.$ligne,$time);
		$sheet->getStyle('Q'.$ligne)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	}
	$sheet->setCellValue('R'.$ligne,utf8_encode($row2['TempsQUALITE']));
	
	$sheet->getStyle('A'.$ligne.':R'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$ligne++;
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
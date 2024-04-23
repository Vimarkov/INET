<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("../../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
$workbook = new PHPExcel;

$req2="SELECT sp_olwficheintervention.Id,
sp_olwficheintervention.Id_Dossier,
sp_olwdossier.MSN,
sp_olwdossier.Titre,
sp_olwdossier.TypeACP AS TypeDossier,
CONCAT(IF(Ajusteur=1,'Ajustage<br>',''),IF(Elec=1,'Elec<br>',''),IF(Meca=1,'Meca','')) AS TypeTravail,
sp_olwdossier.DateCreation,
sp_olwdossier.Reference,
sp_olwficheintervention.CommentairePROD,
sp_olwficheintervention.CommentaireQUALITE,
sp_olwficheintervention.DateTERA,
sp_olwficheintervention.DateTERC,
sp_olwficheintervention.TempsProd,
sp_olwdossier.Id_StatutPREPA,
(SELECT sp_localisation.Libelle FROM sp_localisation WHERE sp_localisation.Id=sp_olwdossier.Id_ZoneDeTravail) AS Localisation,
(SELECT sp_poste.Libelle FROM sp_poste WHERE sp_poste.Id=sp_olwdossier.Id_Poste) AS Poste,
sp_olwdossier.DatePrevisionnelleIntervention,sp_olwficheintervention.Vacation AS Vacation2,
	IF(sp_olwficheintervention.Vacation='',0,
		IF(sp_olwficheintervention.Vacation='J',1,
			IF(sp_olwficheintervention.Vacation='S',2,
				IF(sp_olwficheintervention.Vacation='N',3,
					IF(sp_olwficheintervention.Vacation='VSD',4,0)
				)
			)
		)
	) AS Vacation,sp_olwficheintervention.DateIntervention,
(SELECT sp_olwretour.Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourPROD) AS RetourPROD,
(SELECT sp_olwretour.Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourQUALITE) AS RetourQUALITE,
sp_olwficheintervention.Id_StatutPROD AS StatutPROD,sp_olwficheintervention.Id_StatutQUALITE AS StatutQUALITE ";

$req="FROM sp_olwficheintervention 
LEFT JOIN sp_olwdossier 
ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id

WHERE sp_olwdossier.Id_Prestation=1792 AND ";

if($_SESSION['FiltreMSNPage']<>""){
	$req.="sp_olwdossier.MSN=".$_SESSION['FiltreMSNPage']." AND ";
}
if($_SESSION['FiltreProgrammePage']<>""){
	$req.="sp_olwdossier.Programme='".$_SESSION['FiltreProgrammePage']."' AND ";
}
if($_SESSION['FiltreReferencePage']<>""){
	$req.="sp_olwdossier.Reference='".$_SESSION['FiltreReferencePage']."' AND ";
}
if($_SESSION['FiltreDateInterventionPage']<>""){
	$req.="sp_olwficheintervention.DateIntervention='".TrsfDate_($_SESSION['FiltreDateInterventionPage'])."' AND ";
}
if($_SESSION['FiltreVacationPage']<>""){
	$req.="sp_olwficheintervention.Vacation='".$_SESSION['FiltreVacationPage']."' AND ";
}

$tab = array("Programme","MSN","Reference","Client","TypeDossier","Priorite","Caec","Section","StatutPREPA","Titre","Poste","Localisation","StatutDossier","DatePrevisionnelleIntervention","DateInterventionDebut","DateInterventionFin","SansDateIntervention","VacationPROD","NumIC","DateInterventionQDebut","DateInterventionQFin","SansDateInterventionQ","VacationQUALITE","IQ");
foreach($tab as $filtre){
	if($_SESSION['Filtre'.$filtre.'2']<>""){
		$tab = explode(";",$_SESSION['Filtre'.$filtre.'2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>""){
				 if($filtre=="Programme"){$req.="sp_olwdossier.Programme='".$valeur."' OR ";}
				 if($filtre=="MSN"){$req.="sp_olwdossier.MSN=".$valeur." OR ";}
				 if($filtre=="Reference"){$req.="sp_olwdossier.Reference='".$valeur."' OR ";}
				 if($filtre=="Client"){$req.="sp_olwdossier.Id_Client=".str_replace("_","",$valeur)." OR ";}
				 if($filtre=="TypeDossier"){$req.="sp_olwdossier.TypeACP='".$valeur."' OR ";}
				 if($filtre=="Priorite"){$req.="sp_olwdossier.Priorite='".$valeur."' OR ";}
				 if($filtre=="Caec"){$req.="sp_olwdossier.CaecACP='".$valeur."' OR ";}
				 if($filtre=="Section"){$req.="sp_olwdossier.SectionACP='".$valeur."' OR ";}
				 if($filtre=="StatutPREPA"){$req.="sp_olwdossier.Id_StatutPREPA='".str_replace("_","",$valeur)."' OR ";}
				 if($filtre=="Titre"){$req.="sp_olwdossier.Titre='".addslashes($valeur)."' OR ";}
				 if($filtre=="Poste"){$req.="sp_olwdossier.Id_Poste=".str_replace("_","",$valeur)." OR ";}
				 if($filtre=="Localisation"){$req.="sp_olwdossier.Id_ZoneDeTravail=".str_replace("_","",$valeur)." OR ";}
				 if($filtre=="StatutDossier"){$req.="sp_olwdossier.Id_Statut='".$valeur."' OR ";}
				 if($filtre=="DatePrevisionnelleIntervention"){$req.="sp_olwdossier.DatePrevisionnelleIntervention='".TrsfDate_($valeur)."' OR ";}
				 if($filtre=="DateInterventionDebut"){$req.="sp_olwficheintervention.DateIntervention>='".TrsfDate_($valeur)."' OR ";}
				 if($filtre=="DateInterventionFin"){$req.="sp_olwficheintervention.DateIntervention<='".TrsfDate_($valeur)."' OR ";}
				 if($filtre=="SansDateIntervention"){$req.="sp_olwficheintervention.DateIntervention<='0001-01-01' OR ";}
				 if($filtre=="VacationPROD"){$req.="sp_olwficheintervention.Vacation='".addslashes(str_replace("_","",$valeur))."' OR ";}
				 if($filtre=="NumIC"){$req.="sp_olwficheintervention.NumFI='".addslashes($valeur)."' OR ";}
				 if($filtre=="DateInterventionQDebut"){$req.="sp_olwficheintervention.DateInterventionQ>='".TrsfDate_($valeur)."' OR ";}
				 if($filtre=="DateInterventionQFin"){$req.="sp_olwficheintervention.DateInterventionQ<='".TrsfDate_($valeur)."' OR ";}
				 if($filtre=="SansDateInterventionQ"){$req.="sp_olwficheintervention.DateInterventionQ<='0001-01-01' OR ";}
				 if($filtre=="VacationQUALITE"){$req.="sp_olwficheintervention.VacationQ='".addslashes(str_replace("_","",$valeur))."' OR ";}
				 if($filtre=="IQ"){$req.="sp_olwficheintervention.Id_QUALITE=".str_replace("_","",$valeur)." OR ";}
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
}

$tab = array("StatutPROD","StatutQUALITE");
foreach($tab as $filtre){
	if($_SESSION['Filtre'.$filtre.'2']<>""){
		$tab = explode(";",$_SESSION['Filtre'.$filtre.'2']);
		$req.="(";
		foreach($tab as $valeur){
			 if($valeur<>"0"){
				 if($filtre=="StatutPROD"){$req.="sp_olwficheintervention.Id_StatutPROD='".str_replace("_","",$valeur)."' OR ";}
				 if($filtre=="StatutQUALITE"){$req.="sp_olwficheintervention.Id_StatutQUALITE='".str_replace("_","",$valeur)."' OR ";}
			 }
		}
		$req=substr($req,0,-3);
		$req.=") AND ";
	}
}

if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}

if($_SESSION['TriGeneral']<>""){
$req.="ORDER BY ".substr($_SESSION['TriGeneral'],0,-1);
}
$result2=mysqli_query($bdd,$req2.$req);
$nbResulta2=mysqli_num_rows($result2);


$sheet = $workbook->getActiveSheet();
$sheet->setCellValue('A1',utf8_encode("MSN"));
$sheet->setCellValue('B1',utf8_encode("Vacation"));
$sheet->setCellValue('C1',utf8_encode("Type dossier"));
$sheet->setCellValue('D1',utf8_encode("N° dossier"));
$sheet->setCellValue('E1',utf8_encode("Date création"));
$sheet->setCellValue('F1',utf8_encode("Localisation"));
$sheet->setCellValue('G1',utf8_encode("Titre"));
$sheet->setCellValue('H1',utf8_encode("Statut PREPA"));
$sheet->setCellValue('I1',utf8_encode("Poste"));
$sheet->setCellValue('J1',utf8_encode("Date intervention"));
$sheet->setCellValue('K1',utf8_encode("Statut PROD"));
$sheet->setCellValue('L1',utf8_encode("Retour PROD"));
$sheet->setCellValue('M1',utf8_encode("Date TERA"));
$sheet->setCellValue('N1',utf8_encode("Statut QUALITE"));
$sheet->setCellValue('O1',utf8_encode("Retour QUALITE"));
$sheet->setCellValue('P1',utf8_encode("Date TERC"));
$sheet->setCellValue('Q1',utf8_encode("Temps passé"));

$sheet->getStyle('A1:Q1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:Q1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:Q1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:Q1')->getFont()->setBold(true);
$sheet->getStyle('A1:Q1')->getFont()->getColor()->setRGB('1f49a6');

$sheet->getColumnDimension('A')->setWidth(8);
$sheet->getColumnDimension('B')->setWidth(12);
$sheet->getColumnDimension('C')->setWidth(12);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(30);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('I')->setWidth(15);
$sheet->getColumnDimension('J')->setWidth(20);
$sheet->getColumnDimension('K')->setWidth(20);
$sheet->getColumnDimension('L')->setWidth(20);
$sheet->getColumnDimension('M')->setWidth(20);
$sheet->getColumnDimension('N')->setWidth(20);
$sheet->getColumnDimension('O')->setWidth(20);
$sheet->getColumnDimension('P')->setWidth(20);
$sheet->getColumnDimension('Q')->setWidth(20);
if($nbResulta2>0){
	$ligne=2;
	while($row2=mysqli_fetch_array($result2)){
		
		$vacation="";
		if($row2['Vacation2']=="J"){$vacation="Jour";}
		elseif($row2['Vacation2']=="S"){$vacation="Soir";}
		elseif($row2['Vacation2']=="N"){$vacation="Nuit";}
		elseif($row2['Vacation2']=="VSD"){$vacation="Weekend";}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row2['MSN']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($vacation));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row2['TypeDossier']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row2['Reference']));
		$sheet->setCellValue('E'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row2['DateCreation'])));
		$sheet->setCellValue('F'.$ligne,utf8_encode(stripslashes($row2['Localisation'])));
		$sheet->setCellValue('G'.$ligne,utf8_encode(stripslashes($row2['Titre'])));
		$sheet->setCellValue('H'.$ligne,utf8_encode($row2['Id_StatutPREPA']));
		$sheet->setCellValue('I'.$ligne,utf8_encode($row2['Poste']));
		$sheet->setCellValue('J'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row2['DateIntervention'])));
		$sheet->setCellValue('K'.$ligne,utf8_encode($row2['StatutPROD']));
		$sheet->setCellValue('L'.$ligne,utf8_encode($row2['RetourPROD']));
		$sheet->setCellValue('M'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row2['DateTERA'])));
		$sheet->setCellValue('N'.$ligne,utf8_encode($row2['StatutQUALITE']));
		$sheet->setCellValue('O'.$ligne,utf8_encode($row2['RetourQUALITE']));
		$sheet->setCellValue('P'.$ligne,utf8_encode(AfficheDateJJ_MM_AAAA($row2['DateTERC'])));
		$sheet->setCellValue('Q'.$ligne,utf8_encode($row2['TempsProd']));
		$sheet->getStyle('A'.$ligne.':Q'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$ligne++;
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
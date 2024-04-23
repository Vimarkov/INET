<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Travail');

$sheet2 = $workbook->createSheet();
$sheet2->setTitle('Criteres');
$sheet2->setCellValue('A1',utf8_encode("Critères de recherche"));

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

$req2="SELECT sp_ficheintervention.Id,sp_dossier.MSN,sp_dossier.Reference,sp_dossier.SectionACP,sp_dossier.CommentaireZICIA,sp_ficheintervention.PowerOffPartielCIA,PosteAvionACP,";
$req2.="(SELECT sp_zonedetravail.Libelle FROM sp_zonedetravail WHERE sp_zonedetravail.Id=sp_dossier.Id_ZoneDeTravail) AS Zone, sp_ficheintervention.Commentaire, ";
$req2.="(SELECT sp_zonedetravail.Id_CritereZone FROM sp_zonedetravail WHERE sp_zonedetravail.Id=sp_dossier.Id_ZoneDeTravail) AS CritereZone, 
		sp_dossier.Elec,sp_dossier.Systeme,sp_dossier.Structure,sp_dossier.Oxygene,sp_dossier.Hydraulique,sp_dossier.Fuel,sp_dossier.Metal,";
$req2.="(SELECT sp_urgence.Libelle FROM sp_urgence WHERE sp_urgence.Id=sp_dossier.Id_Urgence) AS Urgence,sp_dossier.TAI_RestantACP, ";
$req2.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_dossier.Id_Personne) AS CreateurDossier, ";
$req2.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_ficheintervention.Id_Createur) AS CreateurFI, ";
$req2.="sp_dossier.Priorite,sp_dossier.Titre,sp_ficheintervention.NumFI, sp_ficheintervention.Vacation,sp_ficheintervention.ResponsableZone, ";
$req2.="sp_ficheintervention.DateIntervention,sp_ficheintervention.TravailRealise,sp_ficheintervention.Id_StatutPROD,sp_ficheintervention.Id_StatutQUALITE,sp_ficheintervention.EtatICCIA ";
$req="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
$req.="WHERE ";
$ligneC=2;
if($_SESSION['MSN2']<>""){
	$sheet2->setCellValue('A'.$ligneC,utf8_encode("MSN : "));
	$sheet2->setCellValue('B'.$ligneC,utf8_encode($_SESSION['MSN2']));
	$ligneC++;
	$tab = explode(";",$_SESSION['MSN2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_dossier.MSN=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['NumDossier2']<>""){
	$sheet2->setCellValue('A'.$ligneC,utf8_encode("N° dossier : "));
	$sheet2->setCellValue('B'.$ligneC,utf8_encode($_SESSION['NumDossier2']));
	$ligneC++;
	$tab = explode(";",$_SESSION['NumDossier2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_dossier.Reference='".addslashes($valeur)."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['NumIC2']<>""){
	$sheet2->setCellValue('A'.$ligneC,utf8_encode("N° IC : "));
	$sheet2->setCellValue('B'.$ligneC,utf8_encode($_SESSION['NumIC2']));
	$ligneC++;
	$tab = explode(";",$_SESSION['NumIC2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			// A MODIFIER LORSQUE FICHIER EXCEL PAR NumFI
			$req.="sp_ficheintervention.Commentaire='".addslashes($valeur)."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Section2']<>""){
	$sheet2->setCellValue('A'.$ligneC,utf8_encode("Section : "));
	$sheet2->setCellValue('B'.$ligneC,utf8_encode($_SESSION['Section2']));
	$ligneC++;
	$tab = explode(";",$_SESSION['Section2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_dossier.SectionACP='".addslashes($valeur)."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Zone2']<>""){
	$valeurExtract=$_SESSION['Zone'];
	$tab = explode(";",$_SESSION['Zone2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_dossier.Id_ZoneDeTravail=".substr($valeur,1)." OR ";
			$valeurExtract=str_replace("<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('zone','_".substr($valeur,1)."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>",";",$valeurExtract);
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
	$sheet2->setCellValue('A'.$ligneC,utf8_encode("Zone : "));
	$sheet2->setCellValue('B'.$ligneC,utf8_encode($valeurExtract));
	$ligneC++;
}
if($_SESSION['CreateurDossier2']<>""){
	$valeurExtract=$_SESSION['CreateurDossier'];
	$tab = explode(";",$_SESSION['CreateurDossier2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_dossier.Id_Personne=".substr($valeur,1)." OR ";
			$valeurExtract=str_replace("<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('createurDossier','_".substr($valeur,1)."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>",";",$valeurExtract);
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
	$sheet2->setCellValue('A'.$ligneC,utf8_encode("Créateur dossier : "));
	$sheet2->setCellValue('B'.$ligneC,utf8_encode($valeurExtract));
	$ligneC++;
}
if($_SESSION['Pole_FI2']<>""){
	$valeurExtract=$_SESSION['Pole_FI'];
	$tab = explode(";",$_SESSION['Pole_FI2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_ficheintervention.Id_Pole=".substr($valeur,1)." OR ";
			$valeurExtract=str_replace("<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('pole','_".substr($valeur,1)."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>",";",$valeurExtract);
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
	$sheet2->setCellValue('A'.$ligneC,utf8_encode("Pôle : "));
	$sheet2->setCellValue('B'.$ligneC,utf8_encode($valeurExtract));
	$ligneC++;
}
if($_SESSION['CreateurIC2']<>""){
	$valeurExtract=$_SESSION['CreateurIC'];
	$tab = explode(";",$_SESSION['CreateurIC2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_ficheintervention.Id_Createur=".substr($valeur,1)." OR ";
			$valeurExtract=str_replace("<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('createurIC','_".substr($valeur,1)."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>",";",$valeurExtract);
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
	$sheet2->setCellValue('A'.$ligneC,utf8_encode("Créateur IC : "));
	$sheet2->setCellValue('B'.$ligneC,utf8_encode($valeurExtract));
	$ligneC++;
}
if($_SESSION['CE2']<>""){
	$valeurExtract=$_SESSION['CE'];
	$tab = explode(";",$_SESSION['CE2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_ficheintervention.Id_PROD=".substr($valeur,1)." OR ";
			$valeurExtract=str_replace("<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('CE','_".substr($valeur,1)."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>",";",$valeurExtract);
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
	$sheet2->setCellValue('A'.$ligneC,utf8_encode("Chef d'équipe : "));
	$sheet2->setCellValue('B'.$ligneC,utf8_encode($valeurExtract));
	$ligneC++;
}
if($_SESSION['IQ2']<>""){
	$valeurExtract=$_SESSION['IQ'];
	$tab = explode(";",$_SESSION['IQ2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_ficheintervention.Id_QUALITE=".substr($valeur,1)." OR ";
			$valeurExtract=str_replace("<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('IQ','_".substr($valeur,1)."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>",";",$valeurExtract);
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
	$sheet2->setCellValue('A'.$ligneC,utf8_encode("Inspecteur qualité : "));
	$sheet2->setCellValue('B'.$ligneC,utf8_encode($valeurExtract));
	$ligneC++;
}
if($_SESSION['Vacation2']<>""){
	$valeurExtract=$_SESSION['Vacation'];
	$tab = explode(";",$_SESSION['Vacation2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_ficheintervention.Vacation='".$valeur."' OR ";
			$valeurExtract=str_replace("<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('vacation','".$valeur."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>",";",$valeurExtract);
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
	$sheet2->setCellValue('A'.$ligneC,utf8_encode("Vacation : "));
	$sheet2->setCellValue('B'.$ligneC,utf8_encode($valeurExtract));
	$ligneC++;
}
if($_SESSION['EtatIC2']<>""){
	$sheet2->setCellValue('A'.$ligneC,utf8_encode("Etat IC : "));
	$sheet2->setCellValue('B'.$ligneC,utf8_encode($_SESSION['EtatIC2']));
	$ligneC++;
	$tab = explode(";",$_SESSION['EtatIC2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_ficheintervention.EtatICCIA='".$valeur."' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['Urgence2']<>""){
	$valeurExtract=$_SESSION['Urgence'];
	$tab = explode(";",$_SESSION['Urgence2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_dossier.Id_Urgence=".substr($valeur,1)." OR ";
			$valeurExtract=str_replace("<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('urgence','_".substr($valeur,1)."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>",";",$valeurExtract);
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
	$sheet2->setCellValue('A'.$ligneC,utf8_encode("Urgence : "));
	$sheet2->setCellValue('B'.$ligneC,utf8_encode($valeurExtract));
	$ligneC++;
}
if($_SESSION['Titre2']<>""){
	$sheet2->setCellValue('A'.$ligneC,utf8_encode("Titre : "));
	$sheet2->setCellValue('B'.$ligneC,utf8_encode($_SESSION['Titre2']));
	$ligneC++;
	$tab = explode(";",$_SESSION['Titre2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="sp_dossier.Titre LIKE '%".addslashes($valeur)."%' OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
}
if($_SESSION['StatutIC2']<>""){
	$valeurExtract=$_SESSION['StatutIC'];
	$tab = explode(";",$_SESSION['StatutIC2']);
	$req.="(";
	foreach($tab as $valeur){
		 if($valeur<>""){
			if($valeur=="(vide)"){$req.="sp_ficheintervention.Id_StatutPROD='' OR sp_ficheintervention.Id_StatutQUALITE='' OR ";}
			elseif($valeur=="TFS" || $valeur=="QARJ"){$req.="sp_ficheintervention.Id_StatutPROD='".$valeur."' OR ";}
			elseif($valeur=="TVS" || $valeur=="CERT"){$req.="sp_ficheintervention.Id_StatutQUALITE='".$valeur."' OR ";}
			$valeurExtract=str_replace("<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutIC','".$valeur."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>",";",$valeurExtract);
		 }
	}
	$req=substr($req,0,-3);
	$req.=") AND ";
	$sheet2->setCellValue('A'.$ligneC,utf8_encode("Statut IC : "));
	$sheet2->setCellValue('B'.$ligneC,utf8_encode($valeurExtract));
	$ligneC++;
}
if($_SESSION['SansDate2']=="oui"){
	$sheet2->setCellValue('A'.$ligneC,utf8_encode("Sans date : "));
	$sheet2->setCellValue('B'.$ligneC,utf8_encode("Oui"));
	$ligneC++;
	$req.=" ( ";
	$req.="sp_ficheintervention.DateIntervention <= '0001-01-01' OR ";
}
if($_SESSION['DateDebut2']<>"" || $_SESSION['DateFin2']<>""){
	$req.=" ( ";
	if($_SESSION['DateDebut2']<>""){
		$sheet2->setCellValue('A'.$ligneC,utf8_encode("Date de début : "));
		$sheet2->setCellValue('B'.$ligneC,utf8_encode($_SESSION['DateDebut2']));
		$ligneC++;
		$req.="sp_ficheintervention.DateIntervention >= '". TrsfDate_($_SESSION['DateDebut2'])."' ";
		$req.=" AND ";
	}
	if($_SESSION['DateFin2']<>""){
		$sheet2->setCellValue('A'.$ligneC,utf8_encode("Date de fin : "));
		$sheet2->setCellValue('B'.$ligneC,utf8_encode($_SESSION['DateFin2']));
		$ligneC++;
		$req.="sp_ficheintervention.DateIntervention <= '". TrsfDate_($_SESSION['DateFin2'])."' ";
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
if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}

if($_SESSION['TriGeneral']<>""){
	$req.="ORDER BY ".substr($_SESSION['TriGeneral'],0,-1);
}

$ligneC--;
$sheet2->getStyle('A1:B'.$ligneC)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet2->getColumnDimension('A')->setWidth(20);
$sheet2->getColumnDimension('B')->setWidth(150);
$sheet2->getStyle('A2:A'.$ligneC)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet2->getStyle('A2:A'.$ligneC)->getFont()->setBold(true);
$sheet2->getStyle('A2:A'.$ligneC)->getFont()->getColor()->setRGB('1f49a6');

$sheet2->mergeCells('A1:B1');
$sheet2->getStyle('A1:B1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet2->getStyle('A1:B1')->getFont()->setBold(true);
$sheet2->getStyle('A1:B1')->getFont()->getColor()->setRGB('1f49a6');

$result=mysqli_query($bdd,$req2.$req);
$nbResulta=mysqli_num_rows($result);
$sheet->setCellValue('A1',utf8_encode("MSN"));
$sheet->setCellValue('B1',utf8_encode("Poste avion"));
$sheet->setCellValue('C1',utf8_encode("N° OF"));
$sheet->setCellValue('D1',utf8_encode("Zone"));
$sheet->setCellValue('E1',utf8_encode("Responsable de zone"));
$sheet->setCellValue('F1',utf8_encode("Priorité"));
$sheet->setCellValue('G1',utf8_encode("Titre"));
$sheet->setCellValue('H1',utf8_encode("Date intervention"));
$sheet->setCellValue('I1',utf8_encode("Vacation"));
$sheet->setCellValue('J1',utf8_encode("N° FI"));
$sheet->setCellValue('K1',utf8_encode("Travail à réaliser"));
$sheet->setCellValue('L1',utf8_encode("Créateur FI"));
$sheet->setCellValue('M1',utf8_encode("Power ON/OFF"));
$sheet->setCellValue('N1',utf8_encode("Zone de travail"));
$sheet->setCellValue('O1',utf8_encode("Compétences"));
$sheet->setCellValue('P1',utf8_encode("Compagnons"));
$sheet->setCellValue('Q1',utf8_encode("Procédés spéciaux appelés"));

$sheet->getStyle('A1:Q1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:Q1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:Q1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:Q1')->getFont()->setBold(true);
$sheet->getStyle('A1:Q1')->getFont()->getColor()->setRGB('1f49a6');

$sheet->getColumnDimension('A')->setWidth(8);
$sheet->getColumnDimension('B')->setWidth(10);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(10);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(8);
$sheet->getColumnDimension('G')->setWidth(25);
$sheet->getColumnDimension('H')->setWidth(13);
$sheet->getStyle('H1')->getAlignment()->setWrapText(true);
$sheet->getColumnDimension('I')->setWidth(10);
$sheet->getColumnDimension('J')->setWidth(10);
$sheet->getColumnDimension('K')->setWidth(18);
$sheet->getColumnDimension('L')->setWidth(12);
$sheet->getColumnDimension('M')->setWidth(8);
$sheet->getStyle('M1')->getAlignment()->setWrapText(true);
$sheet->getColumnDimension('N')->setWidth(10);
$sheet->getStyle('N1')->getAlignment()->setWrapText(true);
$sheet->getColumnDimension('O')->setWidth(20);
$sheet->getColumnDimension('P')->setWidth(20);
$sheet->getColumnDimension('Q')->setWidth(45);

if ($nbResulta>0){

	$ligne=2;
	while($row=mysqli_fetch_array($result)){
		
		$priorite="";
		if($row['Priorite']==1){$priorite="Low";}
		elseif($row['Priorite']==2){$priorite="Medium";}
		elseif($row['Priorite']==3){$priorite="High";}
		
		$vacation="";
		if($row['Vacation']=="J"){$vacation="Jour";}
		elseif($row['Vacation']=="S"){$vacation="Soir";}
		elseif($row['Vacation']=="N"){$vacation="Nuit";}
		elseif($row['Vacation']=="VSD Jour"){$vacation="VSD Jour";}
		elseif($row['Vacation']=="VSD Nuit"){$vacation="VSD Nuit";}
		
		$dateIntervention = "";
		if($row['DateIntervention']>"0001-01-01"){$dateIntervention = $row['DateIntervention'];}
		
		$competences="";
		if($row['Elec']==1){$competences.="ELEC ";}
		if($row['Systeme']==1){$competences.="SYSTEME ";}
		if($row['Structure']==1){$competences.="STRUCTURE ";}
		if($row['Oxygene']==1){$competences.="OXYGENE ";}
		if($row['Hydraulique']==1){$competences.="HYDRAULIQUE ";}
		if($row['Fuel']==1){$competences.="FUEL ";}
		if($row['Metal']==1){$competences.="METAL ";}
		
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['MSN']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['PosteAvionACP']));
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['Reference']));
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['Zone']));
		$sheet->setCellValue('E'.$ligne,utf8_encode($row['ResponsableZone']));
		$sheet->setCellValue('F'.$ligne,utf8_encode($priorite));
		$sheet->setCellValue('G'.$ligne,utf8_encode($row['Titre']));
		$sheet->getStyle('G'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('H'.$ligne,utf8_encode($dateIntervention));
		$sheet->setCellValue('I'.$ligne,utf8_encode($vacation));
		$NumFI=$row['NumFI'];
		if($NumFI==""){$NumFI=$row['Commentaire'];}
		$sheet->setCellValue('J'.$ligne,utf8_encode($NumFI));
		$sheet->setCellValue('K'.$ligne,utf8_encode($row['TravailRealise']));
		$sheet->getStyle('K'.$ligne)->getAlignment()->setWrapText(true);
		$sheet->setCellValue('L'.$ligne,utf8_encode($row['CreateurFI']));
		$sheet->getStyle('L'.$ligne)->getAlignment()->setWrapText(true);
		$power="Non";
		if($row['PowerOffPartielCIA']==1){$power="Oui";}
		$sheet->setCellValue('M'.$ligne,utf8_encode($power));
		if($row['CritereZone']=="1" || $row['CritereZone']=="2"){$sheet->setCellValue('N'.$ligne,utf8_encode("V38"));}
		else{$sheet->setCellValue('N'.$ligne,utf8_encode("V30"));}
		$sheet->setCellValue('O'.$ligne,utf8_encode($competences));
		$sheet->getStyle('O'.$ligne)->getAlignment()->setWrapText(true);
		
		$compagnon="";
		$req="SELECT Id, Id_Personne, TempsPasse,";
		$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_fi_travaileffectue.Id_Personne) AS NomPrenom ";
		$req.="FROM sp_fi_travaileffectue WHERE Id_FI=".$row['Id']." ORDER BY NomPrenom;";
		$result2=mysqli_query($bdd,$req);
		$nbResulta2=mysqli_num_rows($result2);
		if ($nbResulta2>0){
			while($rowCompagnon=mysqli_fetch_array($result2)){
				if($compagnon<>""){$compagnon.="\n";}
				$compagnon.=stripslashes($rowCompagnon['NomPrenom']);
			}
		}
		$sheet->setCellValue('P'.$ligne,utf8_encode($compagnon));
		$sheet->getStyle('P'.$ligne)->getAlignment()->setWrapText(true);
		
		$ps="";
		$req="SELECT Id_Qualification,Qualification, ";
		$req.="IF(Id_Qualification>0,(SELECT new_competences_qualification.Libelle ";
		$req.="FROM new_competences_qualification WHERE new_competences_qualification.Id=sp_fi_aipi.Id_Qualification),Qualification) AS Libelle ";
		$req.="FROM sp_fi_aipi WHERE Id_FI=".$row['Id']." ORDER BY Libelle;";
		
		$result2=mysqli_query($bdd,$req);
		$nbResulta2=mysqli_num_rows($result2);
		if ($nbResulta2>0){
			while($rowAIPI=mysqli_fetch_array($result2)){
				if($ps<>""){$ps.="\n";}
				$ps.=stripslashes($rowAIPI['Libelle']);
			}
		}
		$sheet->setCellValue('Q'.$ligne,utf8_encode($ps));
		$sheet->getStyle('Q'.$ligne)->getAlignment()->setWrapText(true);
		$ligne++;
	}
}

//AFFICHAGE
$sheet->getSheetView()->setZoomScale(90);
$sheet->getPageSetup()->setPrintArea('A1:O'.($ligne));
$sheet->getPageSetup()->setOrientation('landscape'); //orientation paysage
$sheet->getPageSetup()->setFitToWidth(1);
$sheet->getPageSetup()->setFitToHeight(0);

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_Dossier.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/Extract_Dossier.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
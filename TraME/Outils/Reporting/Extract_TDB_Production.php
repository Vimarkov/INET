<?php
session_start();
require("../ConnexioniSansBody.php");
include '../Excel/PHPExcel.php';
include '../Excel/PHPExcel/Writer/Excel2007.php';
require("../Fonctions.php");

$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1024MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$workbook = new PHPExcel;

$req2="SELECT trame_wp.Id, trame_wp.Libelle AS Workpackage ";
$req="FROM trame_wp WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Supprime=0 ";
if($_SESSION['TDB_WP2']<>""){
	$tab = explode(";",$_SESSION['TDB_WP2']);
	$req.="AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req.="Id=".$valeur." OR ";
		 }
	}
	$req=substr($req,0,-3);
	$req.=") ";
}
$req.="ORDER BY Workpackage";
$result2=mysqli_query($bdd,$req2.$req);
$nbResulta2=mysqli_num_rows($result2);


$sheet = $workbook->getActiveSheet();
$sheet->setTitle("Workpackage");

if($_SESSION['Langue']=="EN"){
	$sheet->setCellValue('A1',utf8_encode("Workpackage"));
	$sheet->setCellValue('B1',utf8_encode("VALIDATED"));
	$sheet->setCellValue('C1',utf8_encode("TO BE VALIDATED"));
	$sheet->setCellValue('D1',utf8_encode("IN PROGRESS"));
	$sheet->setCellValue('E1',utf8_encode("RETURN"));
	$sheet->setCellValue('F1',utf8_encode("TIME SPENT (Min)"));
	$sheet->setCellValue('G1',utf8_encode("RATIO=(Time Validated + Time to Validate) / Time spent"));
}
else{
	$sheet->setCellValue('A1',utf8_encode("Workpackage"));
	$sheet->setCellValue('B1',utf8_encode("VALIDE"));
	$sheet->setCellValue('C1',utf8_encode("A VALIDER"));
	$sheet->setCellValue('D1',utf8_encode("EN COURS"));
	$sheet->setCellValue('E1',utf8_encode("RETOURNE"));
	$sheet->setCellValue('F1',utf8_encode("TEMPS PASSE (Min)"));
	$sheet->setCellValue('G1',utf8_encode("RATIO=(Temps Validé + Temps à Valider) / Temps Passé"));
}

$sheet->getStyle('A1:G1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A1:G1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$sheet->getStyle('A1:G1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
$sheet->getStyle('A1:G1')->getFont()->setBold(true);
$sheet->getStyle('A1:G1')->getFont()->getColor()->setRGB('1f49a6');

$sheet->getColumnDimension('A')->setWidth(25);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(15);

$ligne=2;

while($row2=mysqli_fetch_array($result2)){
	$req2="SELECT SUM(trame_travaileffectue_uo.TempsAlloue) AS TempsAlloue FROM trame_travaileffectue_uo ";
	$req2.="LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
	$req2.="WHERE trame_travaileffectue_uo.TravailFait=1 AND trame_travaileffectue.Id_WP=".$row2['Id']." AND trame_travaileffectue.Statut='VALIDE' ";
	if($_SESSION['TDB_Preparateur2']<>""){
		$tab = explode(";",$_SESSION['TDB_Preparateur2']);
		$req2.="AND (";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req2.="trame_travaileffectue.Id_Preparateur=".$valeur." OR ";
			 }
		}
		$req2=substr($req2,0,-3);
		$req2.=") ";
	}
	if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0'){
		$req2.=" AND trame_travaileffectue.Id_Preparateur=".$_SESSION['Id_PersonneTR']." ";
	}
	if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
		$req2.=" AND ( ";
		if($_SESSION['TDB_DateDebut2']<>""){
			$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
			$req2.=" AND ";
		}
		if($_SESSION['TDB_DateFin2']<>""){
			$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
			$req2.=" ";
		}
		if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
		$req2.=" ) ";
	}
	$result=mysqli_query($bdd,$req2);
	$nbResultaV=mysqli_num_rows($result);
	$sommeV=0;
	if($nbResultaV<>0){
		$row=mysqli_fetch_array($result);
		$sommeV=floatval($row['TempsAlloue']);
	}
	
	$req2="SELECT SUM(trame_travaileffectue_uo.TempsAlloue) AS TempsAlloue FROM trame_travaileffectue_uo ";
	$req2.="LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
	$req2.="WHERE trame_travaileffectue_uo.TravailFait=1 AND trame_travaileffectue.Id_WP=".$row2['Id']." AND trame_travaileffectue.Statut='A VALIDER' ";
	if($_SESSION['TDB_Preparateur2']<>""){
		$tab = explode(";",$_SESSION['TDB_Preparateur2']);
		$req2.="AND (";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req2.="trame_travaileffectue.Id_Preparateur=".$valeur." OR ";
			 }
		}
		$req2=substr($req2,0,-3);
		$req2.=") ";
	}
	if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0'){
		$req2.=" AND trame_travaileffectue.Id_Preparateur=".$_SESSION['Id_PersonneTR']." ";
	}
	if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
		$req2.=" AND ( ";
		if($_SESSION['TDB_DateDebut2']<>""){
			$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
			$req2.=" AND ";
		}
		if($_SESSION['TDB_DateFin2']<>""){
			$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
			$req2.=" ";
		}
		if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
		$req2.=" ) ";
	}
	$result=mysqli_query($bdd,$req2);
	$nbResultaAV=mysqli_num_rows($result);
	$sommeAV=0;
	if($nbResultaAV<>0){$row=mysqli_fetch_array($result);$sommeAV=floatval($row['TempsAlloue']);}
	

	$req2="SELECT SUM(trame_travaileffectue_uo.TempsAlloue) AS TempsAlloue FROM trame_travaileffectue_uo ";
	$req2.="LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
	$req2.="WHERE trame_travaileffectue_uo.TravailFait=1 AND trame_travaileffectue.Id_WP=".$row2['Id']." AND (trame_travaileffectue.Statut='EN COURS' OR trame_travaileffectue.Statut='AC' OR trame_travaileffectue.Statut='REC' OR trame_travaileffectue.Statut='CONTROLE') ";
	if($_SESSION['TDB_Preparateur2']<>""){
		$tab = explode(";",$_SESSION['TDB_Preparateur2']);
		$req2.="AND (";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req2.="trame_travaileffectue.Id_Preparateur=".$valeur." OR ";
			 }
		}
		$req2=substr($req2,0,-3);
		$req2.=") ";
	}
	if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0'){
		$req2.=" AND trame_travaileffectue.Id_Preparateur=".$_SESSION['Id_PersonneTR']." ";
	}
	if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
		$req2.=" AND ( ";
		if($_SESSION['TDB_DateDebut2']<>""){
			$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
			$req2.=" AND ";
		}
		if($_SESSION['TDB_DateFin2']<>""){
			$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
			$req2.=" ";
		}
		if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
		$req2.=" ) ";
	}
	$result=mysqli_query($bdd,$req2);
	$nbResultaEC=mysqli_num_rows($result);
	$sommeEC=0;
	if($nbResultaEC<>0){$row=mysqli_fetch_array($result);$sommeEC=floatval($row['TempsAlloue']);}
	
	$req2="SELECT SUM(trame_travaileffectue_uo.TempsAlloue) AS TempsAlloue FROM trame_travaileffectue_uo ";
	$req2.="LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
	$req2.="WHERE trame_travaileffectue_uo.TravailFait=1 AND trame_travaileffectue.Id_WP=".$row2['Id']." AND trame_travaileffectue.Statut='REFUSE' ";
	if($_SESSION['TDB_Preparateur2']<>""){
		$tab = explode(";",$_SESSION['TDB_Preparateur2']);
		$req2.="AND (";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req2.="trame_travaileffectue.Id_Preparateur=".$valeur." OR ";
			 }
		}
		$req2=substr($req2,0,-3);
		$req2.=") ";
	}
	if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0'){
		$req2.=" AND trame_travaileffectue.Id_Preparateur=".$_SESSION['Id_PersonneTR']." ";
	}
	if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
		$req2.=" AND ( ";
		if($_SESSION['TDB_DateDebut2']<>""){
			$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
			$req2.=" AND ";
		}
		if($_SESSION['TDB_DateFin2']<>""){
			$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
			$req2.=" ";
		}
		if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
		$req2.=" ) ";
	}
	$result=mysqli_query($bdd,$req2);
	$nbResultaR=mysqli_num_rows($result);
	$sommeR=0;
	if($nbResultaR<>0){$row=mysqli_fetch_array($result);$sommeR=floatval($row['TempsAlloue']);}

	$req2="SELECT SUM(((HOUR(HeureFin)*60)+ MINUTE(HeureFin)) - ((HOUR(HeureDebut)*60)+ MINUTE(HeureDebut))) AS NbHeure FROM trame_planning ";
	$req2.="WHERE trame_planning.Id_WP=".$row2['Id']." ";
	if($_SESSION['TDB_Preparateur2']<>""){
		$tab = explode(";",$_SESSION['TDB_Preparateur2']);
		$req2.="AND (";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req2.="trame_planning.Id_Preparateur=".$valeur." OR ";
			 }
		}
		$req2=substr($req2,0,-3);
		$req2.=") ";
	}
	if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0'){
		$req2.=" AND trame_planning.Id_Preparateur=".$_SESSION['Id_PersonneTR']." ";
	}
	if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
		$req2.=" AND ( ";
		if($_SESSION['TDB_DateDebut2']<>""){
			$req2.="trame_planning.DateDebut >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
			$req2.=" AND ";
		}
		if($_SESSION['TDB_DateFin2']<>""){
			$req2.="trame_planning.DateDebut <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
			$req2.=" ";
		}
		if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
		$req2.=" ) ";
	}
	$result=mysqli_query($bdd,$req2);
	$nbResultaTP=mysqli_num_rows($result);
	$sommeTP=0;
	if($nbResultaTP<>0){$row=mysqli_fetch_array($result);$sommeTP=floatval($row['NbHeure']);}
	if($sommeTP>0){$productivite= round(($sommeV+$sommeAV)/$sommeTP,2);}else{ $productivite= 0;}
	
	
	if($sommeV>0 || $sommeAV>0 || $sommeEC>0 || $sommeR>0 || $sommeTP>0){
		$sheet->setCellValue('A'.$ligne,utf8_encode($row2['Workpackage']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($sommeV));
		$sheet->setCellValue('C'.$ligne,utf8_encode($sommeAV));
		$sheet->setCellValue('D'.$ligne,utf8_encode($sommeEC));
		$sheet->setCellValue('E'.$ligne,utf8_encode($sommeR));
		$sheet->setCellValue('F'.$ligne,utf8_encode($sommeTP));
		$sheet->setCellValue('G'.$ligne,utf8_encode($productivite));
		$sheet->getStyle('A'.$ligne.':G'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$ligne++;
	}
}

if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1'){
	
	//ONGLET 2
	$sheet = $workbook->createSheet();
	if($_SESSION['Langue']=="EN"){
		$sheet->setTitle("Manufacturing engineer");
	}
	else{
		$sheet->setTitle(utf8_encode("Préparateur"));
	}

	if($_SESSION['Langue']=="EN"){
		$sheet->setCellValue('A1',utf8_encode("Manufacturing engineer"));
		$sheet->setCellValue('B1',utf8_encode("VALIDATED"));
		$sheet->setCellValue('C1',utf8_encode("TO BE VALIDATED"));
		$sheet->setCellValue('D1',utf8_encode("IN PROGRESS"));
		$sheet->setCellValue('E1',utf8_encode("RETURN"));
		$sheet->setCellValue('F1',utf8_encode("TIME SPENT (Min)"));
		$sheet->setCellValue('G1',utf8_encode("RATIO=(Time Validated + Time to Validate) / Time spent"));
	}
	else{
		$sheet->setCellValue('A1',utf8_encode("Préparateur"));
		$sheet->setCellValue('B1',utf8_encode("VALIDE"));
		$sheet->setCellValue('C1',utf8_encode("A VALIDER"));
		$sheet->setCellValue('D1',utf8_encode("EN COURS"));
		$sheet->setCellValue('E1',utf8_encode("RETOURNE"));
		$sheet->setCellValue('F1',utf8_encode("TEMPS PASSE (Min)"));
		$sheet->setCellValue('G1',utf8_encode("RATIO=(Temps Validé + Temps à Valider) / Temps Passé"));
	}
	$sheet->getStyle('A1:G1')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
	$sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$sheet->getStyle('A1:G1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$sheet->getStyle('A1:G1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'f2f2f2'))));
	$sheet->getStyle('A1:G1')->getFont()->setBold(true);
	$sheet->getStyle('A1:G1')->getFont()->getColor()->setRGB('1f49a6');

	$sheet->getColumnDimension('A')->setWidth(25);
	$sheet->getColumnDimension('B')->setWidth(15);
	$sheet->getColumnDimension('C')->setWidth(20);
	$sheet->getColumnDimension('D')->setWidth(15);
	$sheet->getColumnDimension('E')->setWidth(15);
	$sheet->getColumnDimension('F')->setWidth(20);
	$sheet->getColumnDimension('G')->setWidth(15);
	
	$req2="SELECT DISTINCT new_rh_etatcivil.Id,CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne ";
	$req="FROM trame_travaileffectue INNER JOIN new_rh_etatcivil ON trame_travaileffectue.Id_Preparateur=new_rh_etatcivil.Id ";
	$req.="WHERE trame_travaileffectue.Id_Preparateur>0 AND trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
	if($_SESSION['TDB_Preparateur2']<>""){
		$tab = explode(";",$_SESSION['TDB_Preparateur2']);
		$req.="AND (";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req.="new_rh_etatcivil.Id=".$valeur." OR ";
			 }
		}
		$req=substr($req,0,-3);
		$req.=") ";
	}
	$req.="ORDER BY Personne ";
	$result2=mysqli_query($bdd,$req2.$req);
	$nbResulta=mysqli_num_rows($result2);

	$ligne=2;
	while($row2=mysqli_fetch_array($result2)){
		
		$req2="SELECT SUM(trame_travaileffectue_uo.TempsAlloue) AS TempsAlloue FROM trame_travaileffectue_uo ";
		$req2.="LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
		$req2.="WHERE trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." AND trame_travaileffectue_uo.TravailFait=1 AND trame_travaileffectue.Id_Preparateur=".$row2['Id']." AND trame_travaileffectue.Statut='VALIDE' ";
		if($_SESSION['TDB_WP2']<>""){
			$tab = explode(";",$_SESSION['TDB_WP2']);
			$req2.="AND (";
			foreach($tab as $valeur){
				 if($valeur<>""){
					$req2.="trame_travaileffectue.Id_WP=".$valeur." OR ";
				 }
			}
			$req2=substr($req2,0,-3);
			$req2.=") ";
		}
		if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
			$req2.=" AND ( ";
			if($_SESSION['TDB_DateDebut2']<>""){
				$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
				$req2.=" AND ";
			}
			if($_SESSION['TDB_DateFin2']<>""){
				$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
				$req2.=" ";
			}
			if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
			$req2.=" ) ";
		}
		$result=mysqli_query($bdd,$req2);
		$nbResultaV=mysqli_num_rows($result);
		$sommeV=0;
		if($nbResultaV<>0){$row=mysqli_fetch_array($result);$sommeV=floatval($row['TempsAlloue']);}
		$req2="SELECT SUM(trame_travaileffectue_uo.TempsAlloue) AS TempsAlloue FROM trame_travaileffectue_uo ";
		$req2.="LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
		$req2.="WHERE trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." AND trame_travaileffectue_uo.TravailFait=1 AND trame_travaileffectue.Id_Preparateur=".$row2['Id']." AND trame_travaileffectue.Statut='A VALIDER' ";
		if($_SESSION['TDB_WP2']<>""){
			$tab = explode(";",$_SESSION['TDB_WP2']);
			$req2.="AND (";
			foreach($tab as $valeur){
				 if($valeur<>""){
					$req2.="trame_travaileffectue.Id_WP=".$valeur." OR ";
				 }
			}
			$req2=substr($req2,0,-3);
			$req2.=") ";
		}
		if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
			$req2.=" AND ( ";
			if($_SESSION['TDB_DateDebut2']<>""){
				$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
				$req2.=" AND ";
			}
			if($_SESSION['TDB_DateFin2']<>""){
				$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
				$req2.=" ";
			}
			if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
			$req2.=" ) ";
		}
		$result=mysqli_query($bdd,$req2);
		$nbResultaAV=mysqli_num_rows($result);
		$sommeAV=0;
		if($nbResultaAV<>0){$row=mysqli_fetch_array($result);$sommeAV=floatval($row['TempsAlloue']);}
		$req2="SELECT SUM(trame_travaileffectue_uo.TempsAlloue) AS TempsAlloue FROM trame_travaileffectue_uo ";
		$req2.="LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
		$req2.="WHERE trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." AND trame_travaileffectue_uo.TravailFait=1 AND trame_travaileffectue.Id_Preparateur=".$row2['Id']." AND (trame_travaileffectue.Statut='EN COURS' OR trame_travaileffectue.Statut='AC' OR trame_travaileffectue.Statut='REC' OR trame_travaileffectue.Statut='CONTROLE') ";
		if($_SESSION['TDB_WP2']<>""){
			$tab = explode(";",$_SESSION['TDB_WP2']);
			$req2.="AND (";
			foreach($tab as $valeur){
				 if($valeur<>""){
					$req2.="trame_travaileffectue.Id_WP=".$valeur." OR ";
				 }
			}
			$req2=substr($req2,0,-3);
			$req2.=") ";
		}
		if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
			$req2.=" AND ( ";
			if($_SESSION['TDB_DateDebut2']<>""){
				$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
				$req2.=" AND ";
			}
			if($_SESSION['TDB_DateFin2']<>""){
				$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
				$req2.=" ";
			}
			if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
			$req2.=" ) ";
		}
		$result=mysqli_query($bdd,$req2);
		$nbResultaEC=mysqli_num_rows($result);
		$sommeEC=0;
		if($nbResultaEC<>0){$row=mysqli_fetch_array($result);$sommeEC=floatval($row['TempsAlloue']);}
		$req2="SELECT SUM(trame_travaileffectue_uo.TempsAlloue) AS TempsAlloue FROM trame_travaileffectue_uo ";
		$req2.="LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
		$req2.="WHERE trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." AND trame_travaileffectue_uo.TravailFait=1 AND trame_travaileffectue.Id_Preparateur=".$row2['Id']." AND trame_travaileffectue.Statut='REFUSE' ";
		if($_SESSION['TDB_WP2']<>""){
			$tab = explode(";",$_SESSION['TDB_WP2']);
			$req2.="AND (";
			foreach($tab as $valeur){
				 if($valeur<>""){
					$req2.="trame_travaileffectue.Id_WP=".$valeur." OR ";
				 }
			}
			$req2=substr($req2,0,-3);
			$req2.=") ";
		}
		if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
			$req2.=" AND ( ";
			if($_SESSION['TDB_DateDebut2']<>""){
				$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
				$req2.=" AND ";
			}
			if($_SESSION['TDB_DateFin2']<>""){
				$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
				$req2.=" ";
			}
			if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
			$req2.=" ) ";
		}
		$result=mysqli_query($bdd,$req2);
		$nbResultaR=mysqli_num_rows($result);
		$sommeR=0;
		if($nbResultaR<>0){$row=mysqli_fetch_array($result);$sommeR=floatval($row['TempsAlloue']);}
		
		$req2="SELECT SUM(((HOUR(HeureFin)*60)+ MINUTE(HeureFin)) - ((HOUR(HeureDebut)*60)+ MINUTE(HeureDebut))) AS NbHeure FROM trame_planning ";
		$req2.="WHERE trame_planning.Id_Prestation=".$_SESSION['Id_PrestationTR']." AND trame_planning.Id_Preparateur=".$row2['Id']." ";
		if($_SESSION['TDB_WP2']<>""){
			$tab = explode(";",$_SESSION['TDB_WP2']);
			$req2.="AND (";
			foreach($tab as $valeur){
				 if($valeur<>""){
					$req2.="trame_planning.Id_WP=".$valeur." OR ";
				 }
			}
			$req2=substr($req2,0,-3);
			$req2.=") ";
		}
		if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0'){
			$req2.=" AND trame_planning.Id_Preparateur=".$_SESSION['Id_PersonneTR']." ";
		}
		if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
			$req2.=" AND ( ";
			if($_SESSION['TDB_DateDebut2']<>""){
				$req2.="trame_planning.DateDebut >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
				$req2.=" AND ";
			}
			if($_SESSION['TDB_DateFin2']<>""){
				$req2.="trame_planning.DateDebut <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
				$req2.=" ";
			}
			if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
			$req2.=" ) ";
		}
		$result=mysqli_query($bdd,$req2);
		$nbResultaTP=mysqli_num_rows($result);
		$sommeTP=0;
		if($nbResultaTP<>0){$row=mysqli_fetch_array($result);$sommeR=floatval($row['NbHeure']);}
		
		
		if($sommeV>0 || $sommeAV>0 || $sommeEC>0 || $sommeR>0 || $sommeTP>0){
			$sheet->setCellValue('A'.$ligne,utf8_encode($row2['Personne']));
			$sheet->setCellValue('B'.$ligne,utf8_encode($sommeV));
			$sheet->setCellValue('C'.$ligne,utf8_encode($sommeAV));
			$sheet->setCellValue('D'.$ligne,utf8_encode($sommeEC));
			$sheet->setCellValue('E'.$ligne,utf8_encode($sommeR));
			$sheet->setCellValue('F'.$ligne,utf8_encode($sommeTP));
			if($sommeTP>0){$productivite= round(($sommeV+$sommeAV)/$sommeTP,2);}else{ $productivite= 0;}
			$sheet->setCellValue('G'.$ligne,utf8_encode(round($productivite,2)));
			$sheet->getStyle('A'.$ligne.':G'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			$ligne++;
		}
	}
}
//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_TDB.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../tmp/Extract_TDB.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
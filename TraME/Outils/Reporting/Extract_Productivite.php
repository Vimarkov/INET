<?php
session_start();
include '../Excel/PHPExcel.php';
include '../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';

//Ouvrir fichier
$workbook = new PHPExcel_Reader_Excel2007();
if($_SESSION['Langue']=="EN"){
	$excel = $workbook->load('Template_productivite_EN.xlsx');
}
else{
	$excel = $workbook->load('Template_productivite.xlsx');
}

$sheet = $excel->getSheetByName('Indicateurs');
$sheetDATA = $excel->getSheetByName('DATA');

$req="SELECT Libelle FROM trame_prestation WHERE Id=".$_SESSION['Id_PrestationTR'];
$resultP=mysqli_query($bdd,$req);
$nbResultaP=mysqli_num_rows($resultP);
$prestation="";
if($nbResultaP>0){
	$rowP=mysqli_fetch_array($resultP);
	$prestation=$rowP['Libelle'];
}

$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne, CONCAT(LEFT(Prenom,1),LEFT(Nom,1),RIGHT(Nom,1)) AS Visa FROM new_rh_etatcivil 
WHERE Id=".$_SESSION['Id_PersonneTR'];
$resultP=mysqli_query($bdd,$req);
$nbResultaP=mysqli_num_rows($resultP);
$personne="";
$visa="";
if($nbResultaP>0){
	$rowP=mysqli_fetch_array($resultP);
	$personne=$rowP['Personne'];
	$visa=$rowP['Visa'];
}

$tabFR=array('janvier','février','mars','avril','mai','juin','juillet','ao&#251;t','septembre','octobre','novembre','décembre');
$tabEN=array('january','february','march','april','may','june','july','august','september','october','november','december');

$ladate= date("Y-m-d",strtotime(date(substr($_SESSION['PRODUCTIVITE_Mois'],3)."-".substr($_SESSION['PRODUCTIVITE_Mois'],0,2)."-1")." +0 month"));

$moisAnnee=$ladate;
$tabMoisAnnee=explode("-",$moisAnnee);
$mois=$tabMoisAnnee[1];
$annee=$tabMoisAnnee[0];

$date_11= date("Y-m-d",strtotime(date(substr($_SESSION['PRODUCTIVITE_Mois'],3)."-".substr($_SESSION['PRODUCTIVITE_Mois'],0,2)."-1")." -11 month"));

if($_SESSION['Langue']=="EN"){
	$sheet->setCellValue('AF1','Site : '.$prestation);
	$sheet->setCellValue('A2',utf8_encode('Updated : '.date('d/m/Y')));
	$sheet->setCellValue('F2',utf8_encode('By : '.$personne));
	$sheet->setCellValue('F3',utf8_encode('Productivity to the '.$_SESSION['PRODUCTIVITE_Par']));
}
else{
	$sheet->setCellValue('AF1','Prestation : '.$prestation);
	$sheet->setCellValue('A2',utf8_encode('Mis à jour le : '.date('d/m/Y')));
	$sheet->setCellValue('F2',utf8_encode('Par : '.$personne));
	$sheet->setCellValue('F3',utf8_encode('Productivité au '.$_SESSION['PRODUCTIVITE_Par']));
}
$sheet->setCellValue('AI2',utf8_encode($visa));
$sheet->setCellValue('A32',utf8_encode($_SESSION['PRODUCTIVITE_Par']));


if($_SESSION['PRODUCTIVITE_Par2']==1){$req2="SELECT trame_travaileffectue.Id_WP AS Id, ";}
elseif($_SESSION['PRODUCTIVITE_Par2']==2){$req2="SELECT trame_travaileffectue.Id_Tache AS Id, ";}
elseif($_SESSION['PRODUCTIVITE_Par2']==3){$req2="SELECT trame_travaileffectue_uo.Id_UO AS Id, ";}
elseif($_SESSION['PRODUCTIVITE_Par2']==4){$req2="SELECT trame_travaileffectue.Id_Preparateur AS Id, ";} 

$req2.="DatePreparateur, SUM(trame_travaileffectue_uo.TempsAlloue) AS TempsAlloue FROM trame_travaileffectue_uo ";
$req2.="LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
$req2.="WHERE trame_travaileffectue_uo.TravailFait=1 AND (trame_travaileffectue.Statut='VALIDE' OR trame_travaileffectue.Statut='A VALIDER') ";
$req2.="AND trame_travaileffectue.DatePreparateur>='".$date_11."' 
		AND trame_travaileffectue.DatePreparateur<='".date("Y-m-d",strtotime($date_11." +12 month"))."' 
		AND trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
if($_SESSION['PRODUCTIVITE_WP2']<>""){
	$tab = explode(";",$_SESSION['PRODUCTIVITE_WP2']);
	$req2.=" AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req2.="trame_travaileffectue.Id_WP=".$valeur." OR ";
		 }
	}
	$req2=substr($req2,0,-3);
	$req2.=") ";
}
if($_SESSION['PRODUCTIVITE_Tache2']<>""){
	$tab = explode(";",$_SESSION['PRODUCTIVITE_Tache2']);
	$req2.=" AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req2.="trame_travaileffectue.Id_Tache=".$valeur." OR ";
		 }
	}
	$req2=substr($req2,0,-3);
	$req2.=") ";
}
if($_SESSION['PRODUCTIVITE_UO2']<>""){
	$tab = explode(";",$_SESSION['PRODUCTIVITE_UO2']);
	$req2.=" AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req2.="trame_travaileffectue_uo.Id_UO=".$valeur." OR ";
		 }
	}
	$req2=substr($req2,0,-3);
	$req2.=") ";
}
if($_SESSION['PRODUCTIVITE_Collaborateur2']<>""){
	$tab = explode(";",$_SESSION['PRODUCTIVITE_Collaborateur2']);
	$req2.=" AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req2.="trame_travaileffectue.Id_Preparateur=".$valeur." OR ";
		 }
	}
	$req2=substr($req2,0,-3);
	$req2.=") ";
}
if($_SESSION['PRODUCTIVITE_Par2']==1){$req2.="GROUP BY trame_travaileffectue.Id_WP, DatePreparateur ";}
elseif($_SESSION['PRODUCTIVITE_Par2']==2){$req2.="GROUP BY trame_travaileffectue.Id_Tache, DatePreparateur ";}
elseif($_SESSION['PRODUCTIVITE_Par2']==3){$req2.="GROUP BY trame_travaileffectue_uo.Id_UO, DatePreparateur ";}
elseif($_SESSION['PRODUCTIVITE_Par2']==4){$req2.="GROUP BY trame_travaileffectue.Id_Preparateur, DatePreparateur ";} 

$resultV=mysqli_query($bdd,$req2);
$nbResultaV=mysqli_num_rows($resultV);
						
$reqPlanning="SELECT Id FROM trame_prestation WHERE Planning=1 AND Id=".$_SESSION['Id_PrestationTR'];
$resultPlanning=mysqli_query($bdd,$reqPlanning);
$nbResultaPlanning=mysqli_num_rows($resultPlanning);
if($nbResultaPlanning>0){
	
	if($_SESSION['PRODUCTIVITE_Par2']==1){$req2="SELECT trame_travaileffectue.Id_WP AS Id, ";}
	elseif($_SESSION['PRODUCTIVITE_Par2']==2){$req2="SELECT trame_travaileffectue.Id_Tache AS Id, ";}
	elseif($_SESSION['PRODUCTIVITE_Par2']==3){$req2="SELECT trame_travaileffectue_uo.Id_UO AS Id, ";}
	elseif($_SESSION['PRODUCTIVITE_Par2']==4){$req2="SELECT trame_travaileffectue.Id_Preparateur AS Id, ";} 

	$req2.="DatePreparateur, SUM(TempsPasse) AS NbHeure FROM trame_travaileffectue_uo ";
	$req2.="LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
	$req2.="WHERE trame_travaileffectue.DatePreparateur>='".$date_11."' 
			AND trame_travaileffectue.DatePreparateur<='".date("Y-m-d",strtotime($date_11." +12 month"))."' 
			AND trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
	if($_SESSION['PRODUCTIVITE_WP2']<>""){
		$tab = explode(";",$_SESSION['PRODUCTIVITE_WP2']);
		$req2.=" AND (";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req2.="trame_travaileffectue.Id_WP=".$valeur." OR ";
			 }
		}
		$req2=substr($req2,0,-3);
		$req2.=") ";
	}
	if($_SESSION['PRODUCTIVITE_Tache2']<>""){
		$tab = explode(";",$_SESSION['PRODUCTIVITE_Tache2']);
		$req2.=" AND (";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req2.="trame_travaileffectue.Id_Tache=".$valeur." OR ";
			 }
		}
		$req2=substr($req2,0,-3);
		$req2.=") ";
	}
	if($_SESSION['PRODUCTIVITE_UO2']<>""){
		$tab = explode(";",$_SESSION['PRODUCTIVITE_UO2']);
		$req2.=" AND (";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req2.="trame_travaileffectue_uo.Id_UO=".$valeur." OR ";
			 }
		}
		$req2=substr($req2,0,-3);
		$req2.=") ";
	}
	if($_SESSION['PRODUCTIVITE_Collaborateur2']<>""){
		$tab = explode(";",$_SESSION['PRODUCTIVITE_Collaborateur2']);
		$req2.=" AND (";
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req2.="trame_travaileffectue.Id_Preparateur=".$valeur." OR ";
			 }
		}
		$req2=substr($req2,0,-3);
		$req2.=") ";
	}
	if($_SESSION['PRODUCTIVITE_Par2']==1){$req2.="GROUP BY trame_travaileffectue.Id_WP, DatePreparateur ";}
	elseif($_SESSION['PRODUCTIVITE_Par2']==2){$req2.="GROUP BY trame_travaileffectue.Id_Tache, DatePreparateur ";}
	elseif($_SESSION['PRODUCTIVITE_Par2']==3){$req2.="GROUP BY trame_travaileffectue_uo.Id_UO, DatePreparateur ";}
	elseif($_SESSION['PRODUCTIVITE_Par2']==4){$req2.="GROUP BY trame_travaileffectue.Id_Preparateur, DatePreparateur ";}
	$resultTP=mysqli_query($bdd,$req2);
	$nbResultaTP=mysqli_num_rows($resultTP);
}
else{
	if($_SESSION['PRODUCTIVITE_Par2']==1){$req2="SELECT trame_planning.Id_WP AS Id, ";}
	elseif($_SESSION['PRODUCTIVITE_Par2']==2){$req2="SELECT trame_planning.Id_Tache AS Id, ";}
	elseif($_SESSION['PRODUCTIVITE_Par2']==3){$req2="SELECT trame_planning.Id_UO AS Id, ";}
	elseif($_SESSION['PRODUCTIVITE_Par2']==4){$req2="SELECT trame_planning.Id_Preparateur AS Id, ";} 
	
	$req2.="DateDebut AS DatePreparateur, SUM(((HOUR(HeureFin)*60)+ MINUTE(HeureFin)) - ((HOUR(HeureDebut)*60)+ MINUTE(HeureDebut))) AS NbHeure FROM trame_planning ";
	
	if($_SESSION['PRODUCTIVITE_Par2']==1){$req2.="WHERE trame_planning.Id_WP<>0 AND ";}
	elseif($_SESSION['PRODUCTIVITE_Par2']==2){$req2.="WHERE trame_planning.Id_Tache<>0 AND ";}
	elseif($_SESSION['PRODUCTIVITE_Par2']==3){$req2.="WHERE trame_planning.Id_UO<>0 AND ";}
	elseif($_SESSION['PRODUCTIVITE_Par2']==4){$req2.="WHERE trame_planning.Id_Preparateur<>0 AND ";}
	$req2.="trame_planning.DateDebut>='".$date_11."' 
			AND trame_planning.DateDebut<='".date("Y-m-d",strtotime($date_11." +12 month"))."' 
			AND trame_planning.Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
	
	if($_SESSION['PRODUCTIVITE_Par2']==1){$req2.="GROUP BY trame_planning.Id_WP, DatePreparateur ";}
	elseif($_SESSION['PRODUCTIVITE_Par2']==2){$req2.="GROUP BY trame_planning.Id_Tache, DatePreparateur ";}
	elseif($_SESSION['PRODUCTIVITE_Par2']==3){$req2.="GROUP BY trame_planning.Id_UO, DatePreparateur ";}
	elseif($_SESSION['PRODUCTIVITE_Par2']==4){$req2.="GROUP BY trame_planning.Id_Preparateur, DatePreparateur ";}

	$resultTP=mysqli_query($bdd,$req2);
	$nbResultaTP=mysqli_num_rows($resultTP);
}


if($_SESSION['PRODUCTIVITE_Par2']==1){$req2="SELECT DISTINCT trame_travaileffectue.Id_WP AS Id,(SELECT trame_wp.Libelle FROM trame_wp WHERE trame_wp.Id=trame_travaileffectue.Id_WP) AS Libelle ";}
elseif($_SESSION['PRODUCTIVITE_Par2']==2){$req2="SELECT DISTINCT trame_travaileffectue.Id_Tache AS Id,(SELECT trame_tache.Libelle FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS Libelle ";}
elseif($_SESSION['PRODUCTIVITE_Par2']==3){$req2="SELECT DISTINCT trame_travaileffectue_uo.Id_UO AS Id,(SELECT trame_uo.Libelle FROM trame_uo WHERE trame_uo.Id=trame_travaileffectue_uo.Id_UO) AS Libelle ";}
elseif($_SESSION['PRODUCTIVITE_Par2']==4){$req2="SELECT DISTINCT trame_travaileffectue.Id_Preparateur AS Id,(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=trame_travaileffectue.Id_Preparateur) AS Libelle ";} 

$req2.="FROM trame_travaileffectue_uo ";
$req2.="LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
$req2.="WHERE trame_travaileffectue_uo.TravailFait=1 AND (trame_travaileffectue.Statut='VALIDE' OR trame_travaileffectue.Statut='A VALIDER') ";
$req2.="AND trame_travaileffectue.DatePreparateur>='".$date_11."' 
		AND trame_travaileffectue.DatePreparateur<='".date("Y-m-d",strtotime($date_11." +12 month"))."' 
		AND trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
if($_SESSION['PRODUCTIVITE_WP2']<>""){
	$tab = explode(";",$_SESSION['PRODUCTIVITE_WP2']);
	$req2.=" AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req2.="trame_travaileffectue.Id_WP=".$valeur." OR ";
		 }
	}
	$req2=substr($req2,0,-3);
	$req2.=") ";
}
if($_SESSION['PRODUCTIVITE_Tache2']<>""){
	$tab = explode(";",$_SESSION['PRODUCTIVITE_Tache2']);
	$req2.=" AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req2.="trame_travaileffectue.Id_Tache=".$valeur." OR ";
		 }
	}
	$req2=substr($req2,0,-3);
	$req2.=") ";
}
if($_SESSION['PRODUCTIVITE_UO2']<>""){
	$tab = explode(";",$_SESSION['PRODUCTIVITE_UO2']);
	$req2.=" AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req2.="trame_travaileffectue_uo.Id_UO=".$valeur." OR ";
		 }
	}
	$req2=substr($req2,0,-3);
	$req2.=") ";
}
if($_SESSION['PRODUCTIVITE_Collaborateur2']<>""){
	$tab = explode(";",$_SESSION['PRODUCTIVITE_Collaborateur2']);
	$req2.=" AND (";
	foreach($tab as $valeur){
		 if($valeur<>""){
			$req2.="trame_travaileffectue.Id_Preparateur=".$valeur." OR ";
		 }
	}
	$req2=substr($req2,0,-3);
	$req2.=") ";
}
$req2.="ORDER BY Libelle ";

$result=mysqli_query($bdd,$req2);
$nbResulta=mysqli_num_rows($result);

if($nbResulta>0){
	mysqli_data_seek($result,0);
	$ligne=34;
	$ligneDATA=2;
	while($row=mysqli_fetch_array($result)){
		$sheet->setCellValue("A".$ligne,utf8_encode($row['Libelle']));
		$sheetDATA->setCellValue("A".$ligneDATA,utf8_encode($row['Libelle']));

		$sheet->getStyle('A'.$ligne.':AK'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$ligne++;
		$ligneDATA++;
	}
}

$date_11= date("Y-m-d",strtotime(date(substr($_SESSION['PRODUCTIVITE_Mois'],3)."-".substr($_SESSION['PRODUCTIVITE_Mois'],0,2)."-1")." -11 month"));

$col="B";
$colDATA="B";
for($ligne=1;$ligne<=12;$ligne++){
	$sheet->setCellValue($col."32",date("m/Y",strtotime($date_11." +0 month")));
	$sheetDATA->setCellValue($colDATA."1",date("m/Y",strtotime($date_11." +0 month")));
	
	$col++;
	$col++;
	$col++;
	$colDATA++;
	$date_11= date("Y-m-d",strtotime($date_11." +1 month"));
}

$date_11= date("Y-m-d",strtotime(date(substr($_SESSION['PRODUCTIVITE_Mois'],3)."-".substr($_SESSION['PRODUCTIVITE_Mois'],0,2)."-1")." -11 month"));
$col="B";
$colDATA="B";
$ligneDATA=2;
for($ligne=1;$ligne<=12;$ligne++){
	if($nbResulta>0){
		mysqli_data_seek($result,0);
		$valide=0;
		$TempsPasse=0;
		$ligneDATA=2;
		$ligneINDIC=34;
		while($row=mysqli_fetch_array($result)){
			$sommeV=0;
			if($nbResultaV<>0){
				mysqli_data_seek($resultV,0);
				while($row2=mysqli_fetch_array($resultV)){
					if(date("m/Y",strtotime($row2['DatePreparateur']." +0 month"))==date("m/Y",strtotime($date_11." +0 month"))){
						if($row2['Id']==$row['Id']){
							$valide=$valide+floatval($row2['TempsAlloue']);
							$sommeV=$sommeV+floatval($row2['TempsAlloue']);
						}
					}
				}
			}
			
			$sommeTP=0;
			if($nbResultaTP<>0){
				mysqli_data_seek($resultTP,0);
				while($row2=mysqli_fetch_array($resultTP)){
					if(date("m/Y",strtotime($row2['DatePreparateur']." +0 month"))==date("m/Y",strtotime($date_11." +0 month"))){
						if($row2['Id']==$row['Id']){
							$TempsPasse=$TempsPasse+floatval($row2['NbHeure']);
							$sommeTP=$sommeTP+floatval($row2['NbHeure']);
						}
					}
				}
			}
			if($nbResultaPlanning==0){
				$sommeTP = $sommeTP/60;
			}
			$colRatio=$col;
			$colTA=$col;
			$colTA++;
			$colTP=$col;
			$colTP++;
			$colTP++;
			
			//echo $row['Id']." ".date("m/Y",strtotime($date_11." +0 month"))." ".$sommeTP."<br>";
			
			if($sommeV>0){$sheet->setCellValue($colTA.$ligneINDIC,utf8_encode($sommeV));}
			if($sommeTP>0){$sheet->setCellValue($colTP.$ligneINDIC,utf8_encode($sommeTP));}
			
			if($sommeTP>0 && $sommeV>0){
				$sheet->setCellValue($colRatio.$ligneINDIC,utf8_encode(round(($sommeV)/$sommeTP,2)));
				$sheetDATA->setCellValue($colDATA.$ligneDATA,utf8_encode(round(($sommeV)/$sommeTP,2)));
				//$sheetDATA->setCellValue($colDATA.$ligneDATA,utf8_encode("0"));
			}
			else{
				$sheetDATA->setCellValue($colDATA.$ligneDATA,utf8_encode("0"));
			}
			$ligneDATA++;
			$ligneINDIC++;
		}
	}
	$colDATA++;
	$col++;
	$col++;
	$col++;
	$date_11= date("Y-m-d",strtotime($date_11." +1 month"));
}

if($ligneDATA>2){$ligneDATA--;}
if($ligneDATA>2){
	$tab = array();
	$tabDATA= array();
	$k=0;
	for($i=2;$i<=$ligneDATA;$i++){
		$tab[$k]=new PHPExcel_Chart_DataSeriesValues('String', 'DATA!$A$'.$i, NULL, 1);
		$tabDATA[$k]=new PHPExcel_Chart_DataSeriesValues('Number', 'DATA!$B$'.$i.':$M$'.$i.'', NULL, 4);
		$k++;
	}
	$dataseriesLabels = $tab;
	$dataSeriesValues = $tabDATA;
}
else{
	$dataseriesLabels = array(
		new PHPExcel_Chart_DataSeriesValues('String', 'DATA!$A$2', NULL, 1),
	);

	$dataSeriesValues = array(
		new PHPExcel_Chart_DataSeriesValues('Number', 'DATA!$B$2:$M$2', NULL, 4),
	);
}
	
$xAxisTickValues = array(
	new PHPExcel_Chart_DataSeriesValues('String', 'DATA!$B$1:$M$1', NULL, 4),
);

//	Build the dataseries
$series1 = new PHPExcel_Chart_DataSeries(
	PHPExcel_Chart_DataSeries::TYPE_LINECHART,		// plotType
	PHPExcel_Chart_DataSeries::GROUPING_STANDARD,	// plotGrouping
	range(0, count($dataSeriesValues)-1),			// plotOrder
	$dataseriesLabels,								// plotLabel
	$xAxisTickValues,								// plotCategory
	$dataSeriesValues								// plotValues
);
	
//	Set additional dataseries parameters
//		Make it a horizontal bar rather than a vertical column graph
$series1->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

//	Set the chart legend 
$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_BOTTOM, NULL, false);

//	Set the series in the plot area
$layout = new PHPExcel_Chart_Layout();
$layout->setShowVal(FALSE);
$plotarea = new PHPExcel_Chart_PlotArea($layout, array($series1));
if($_SESSION['Langue']=="EN"){
	$title = new PHPExcel_Chart_Title(utf8_encode('Rate'));
}
else{
	$title = new PHPExcel_Chart_Title(utf8_encode('Ratio'));
}


//	Create the chart
$chart = new PHPExcel_Chart(
	'chart1',		// name
	$title,			// title
	$legend,		// legend
	$plotarea,		// plotArea
	true,			// plotVisibleOnly
	0,				// displayBlanksAs
	NULL,			// xAxisLabel
	NULL		// yAxisLabel
);

//	Set the position where the chart should appear in the worksheet
$chart->setTopLeftPosition('A5');
$chart->setBottomRightPosition('AL23');

//	Add the chart to the worksheet
$sheet->addChart($chart);

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Extract_Productivite.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->setIncludeCharts(TRUE);
$chemin = '../../tmp/Extract_Productivite.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
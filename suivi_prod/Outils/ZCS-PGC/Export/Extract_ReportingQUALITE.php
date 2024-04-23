<?php
session_start();
require("../../ConnexioniSansBody.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require("../../Fonctions.php");


$vacation = $_GET['vacation'];
$dateReporting = $_GET['date'];
$leJour=TrsfDate_($dateReporting);
$tabDate = explode('-', $leJour);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
$laVeille = date("Y-m-d", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
$leLendemain= date("Y-m-d", $timestamp);
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
$NumJour = date("N", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+5-$NumJour, $tabDate[0]);
$leVendredi= date("Y-m-d", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+6-$NumJour, $tabDate[0]);
$leSamedi= date("Y-m-d", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+7-$NumJour, $tabDate[0]);
$leDimanche= date("Y-m-d", $timestamp);

$laVacation="";
if($vacation=="J"){$laVacation="Jour";}
elseif($vacation=="S"){$laVacation="Soir";}
if($vacation=="N"){$laVacation="Nuit";}
if($vacation=="VSD"){$laVacation="VSD";}

$req="SELECT ";

$req.="sp_olwficheintervention.Id_StatutPROD,(SELECT sp_olwretour.Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourPROD) AS RetourPROD,";
$req.="sp_olwficheintervention.Id_StatutQUALITE,(SELECT sp_olwretour.Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourQUALITE) AS RetourQUALITE,";

$req.="sp_client.Libelle AS Client, ";
$req.="sp_olwficheintervention.PosteAvionACP AS Poste, ";
$req.="sp_olwdossier.MSN, ";
$req.="sp_olwdossier.Reference AS NoDossier, ";
$req.="'COMPETENCES' AS Competences, ";
$req.="sp_olwdossier.Titre, ";
$req.="sp_olwficheintervention.TravailRealise, ";
$req.="CONCAT(new_rh_etatcivil.Nom, ' ', new_rh_etatcivil.Prenom) AS Inspecteur, ";
$req.="sp_olwficheintervention.Id_StatutQUALITE, ";
$req.="sp_olwficheintervention.Id_RetourQUALITE, ";
$req.="sp_olwficheintervention.CommentaireQUALITE, ";
$req.="sp_olwdossier.Elec, ";
$req.="sp_olwdossier.Systeme, ";
$req.="sp_olwdossier.Structure, ";
$req.="sp_olwdossier.Oxygene, ";
$req.="sp_olwdossier.Hydraulique, ";
$req.="sp_olwdossier.Fuel, ";
$req.="sp_olwdossier.Metal ";

$req.="FROM ";
$req.="sp_olwdossier, ";
$req.="sp_olwficheintervention, ";
$req.="sp_client, ";
$req.="new_rh_etatcivil ";

$req.="WHERE ";
$req.="sp_olwficheintervention.Id_Dossier = sp_olwdossier.Id ";
$req.="AND sp_olwdossier.Id_Client = sp_client.Id ";
$req.="AND sp_olwficheintervention.Id_QUALITE = new_rh_etatcivil.Id ";
if($laVacation=="VSD"){
	$req.="AND ( ";
	$req.="(sp_olwficheintervention.DateInterventionQ='".$leVendredi."' AND sp_olwficheintervention.VacationQ='VSD Nuit') OR ";
	$req.="(sp_olwficheintervention.DateInterventionQ='".$leSamedi."' AND sp_olwficheintervention.VacationQ='VSD Jour') OR ";
	$req.="(sp_olwficheintervention.DateInterventionQ='".$leSamedi."' AND sp_olwficheintervention.VacationQ='VSD Nuit') OR ";
	$req.="(sp_olwficheintervention.DateInterventionQ='".$leDimanche."' AND sp_olwficheintervention.VacationQ='VSD Jour')) ";
}
else{
	$req.="AND sp_olwficheintervention.DateInterventionQ='".$leJour."' AND sp_olwficheintervention.VacationQ='".$vacation."' ";
}
$req.="ORDER BY sp_olwdossier.MSN ASC, sp_olwdossier.Reference ASC ";

$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$req="SELECT sp_olwficheintervention.Id ";
$req.="FROM sp_olwficheintervention LEFT JOIN sp_olwdossier ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id ";
$req.="WHERE sp_olwficheintervention.DateCreation>='2016-05-23' AND sp_olwficheintervention.DateIntervention<='".$leJour."' AND (sp_olwficheintervention.Id_StatutPROD='QARJ' OR sp_olwficheintervention.Id_StatutPROD='REWORK') AND ( sp_olwficheintervention.Id_StatutQUALITE='' OR ( ";
if($laVacation=="VSD"){
	$req.="sp_olwficheintervention.Id_StatutQUALITE<>'' AND ( ";
	$req.="(sp_olwficheintervention.DateInterventionQ='".$leVendredi."' AND sp_olwficheintervention.VacationQ='VSD Nuit') OR ";
	$req.="(sp_olwficheintervention.DateInterventionQ='".$leSamedi."' AND sp_olwficheintervention.VacationQ='VSD Jour') OR ";
	$req.="(sp_olwficheintervention.DateInterventionQ='".$leSamedi."' AND sp_olwficheintervention.VacationQ='VSD Nuit') OR ";
	$req.="(sp_olwficheintervention.DateInterventionQ='".$leDimanche."' AND sp_olwficheintervention.VacationQ='VSD Jour')))) ";
}
else{
	$req.=" sp_olwficheintervention.DateInterventionQ='".$leJour."' AND sp_olwficheintervention.Id_StatutQUALITE<>'' AND sp_olwficheintervention.VacationQ='".$vacation."')) ";
}

$resultDispo=mysqli_query($bdd,$req);
$nbDispo=mysqli_num_rows($resultDispo);

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Reporting QUALITE');

$sheet->setCellValue('A1',utf8_encode("1. Bilan de la vacation : "));
$sheet->setCellValue('A3',utf8_encode("Date"));
$sheet->setCellValue('A4',utf8_encode("Vacation"));
$sheet->setCellValue('A5',utf8_encode("Gamme TERA dispo"));
$sheet->setCellValue('A6',utf8_encode("Gamme TERC"));
$sheet->setCellValue('A7',utf8_encode("Gamme RETQ"));
$sheet->setCellValue('A8',utf8_encode("Gamme RETQext"));


$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(10);
$sheet->getColumnDimension('C')->setWidth(10);
$sheet->getColumnDimension('D')->setWidth(18);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(25);
$sheet->getColumnDimension('H')->setWidth(20);
$sheet->getColumnDimension('I')->setWidth(15);
$sheet->getColumnDimension('J')->setWidth(15);
$sheet->getColumnDimension('K')->setWidth(20);

$sheet->getStyle('A3:B8')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A3:B8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A3:B8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$sheet->setCellValue('A10',utf8_encode("Commentaires : "));

$sheet->setCellValue('A13',utf8_encode("2. Détail activité vacation : "));

$sheet->setCellValue('A15',utf8_encode("Client"));
$sheet->setCellValue('B15',utf8_encode("Poste"));
$sheet->setCellValue('C15',utf8_encode("N°MSN"));
$sheet->setCellValue('D15',utf8_encode("N°Dossier"));
$sheet->setCellValue('E15',utf8_encode("Compétence"));
$sheet->setCellValue('F15',utf8_encode("Titre"));
$sheet->setCellValue('G15',utf8_encode("Travail à réaliser"));
$sheet->setCellValue('H15',utf8_encode("Inspecteur"));
$sheet->setCellValue('I15',utf8_encode("Statut Qualité"));
$sheet->setCellValue('J15',utf8_encode("Retour Qualité"));
$sheet->setCellValue('K15',utf8_encode("Commentaire Qualité"));

$sheet->getStyle('A15:K15')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A15:K15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A15:K15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$Dispo=0;
$Cert=0;
$CertPNE=0;
$CertOnly=0;
$Avancee=0;
$Retc=0;
$RETQ=0;
$RETQext=0;
$ligne=16;

if ($nbResulta>0){
	while($row=mysqli_fetch_array($result)){
		// 		$Priorite="";
		// 		if($row['Priorite']=="1"){$Priorite="Low";}
		// 		elseif($row['Priorite']=="2"){$Priorite="Medium";}
		// 		else{$Priorite="High";}
		
		$statut=$row['Id_StatutQUALITE'];
		$retour=$row['RetourQUALITE'];
		
		// 		$couleur="#ffffff";
		// 		if($statut=="CERT"){$couleur="#00b050";}
		// 		elseif($statut=="TVS"){$couleur="#ffc000";}
		
		if(($row['Id_StatutPROD']=="TERA" && $statut=="")){
			$Dispo++;
		}
		if($statut=="TERC"){
			$Cert++;
		}		
			// 		elseif($statut=="TVS"){
			// 			if($row['Id_RetourQUALITE']==5){$Avancee++;}
			// 			else{$Retc++;}
			// 		}
			// 		$couleurPrio="#ffffff";
			// 		if($row['Priorite']=="1"){$couleurPrio="#a7da4e";}
			// 		elseif($row['Priorite']=="2"){$couleurPrio="#ffc20e";}
			// 		else{$couleurPrio="#ed1c24";}
			
		if($statut=="RETQ"){
			$RETQ++;
		}
		
		if($statut=="RETQext"){
			$RETQext++;
		}
		
			// Concaténation des compétences
			$competences="";
			if($row['Elec']=="1")
				$competences.=" ELEC ";
				
			if($row['Systeme']=="1")
				$competences.=" SYSTEME ";
					
			if($row['Structure']=="1")
				$competences.=" STRUCTURE ";
						
			if($row['Oxygene']=="1")
				$competences.=" OXYGENE ";
							
			if($row['Hydraulique']=="1")
				$competences.=" HYDRAULIQUE ";
								
			if($row['Fuel']=="1")
				$competences.=" FUEL ";
									
			if($row['Metal']=="1")
				$competences.=" METAL ";
										
			$sheet->setCellValue('A'.$ligne,utf8_encode($row['Client']));
			$sheet->setCellValue('B'.$ligne,utf8_encode($row['Poste']));
			$sheet->setCellValue('C'.$ligne,utf8_encode($row['MSN']));
			$sheet->setCellValue('D'.$ligne,utf8_encode($row['NoDossier']));
			$sheet->setCellValue('E'.$ligne,utf8_encode($competences));
			$sheet->setCellValue('F'.$ligne,utf8_encode($row['Titre']));
			$sheet->setCellValue('G'.$ligne,utf8_encode($row['TravailRealise']));
			$sheet->setCellValue('H'.$ligne,utf8_encode($row['Inspecteur']));
			$sheet->setCellValue('I'.$ligne,utf8_encode($statut));
			$sheet->setCellValue('J'.$ligne,utf8_encode($retour));
			$sheet->setCellValue('K'.$ligne,utf8_encode($row['CommentaireQUALITE']));
			
			$sheet->getStyle('A'.$ligne.':K'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
			$sheet->getStyle('A'.$ligne.':K'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle('A'.$ligne.':K'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$ligne++;
		}
	}
	
	if($laVacation=="VSD"){
		$sheet->setCellValue('B3',utf8_encode($leVendredi." | ".$leSamedi." | ".$leDimanche));
	}
	else{
		$sheet->setCellValue('B3',utf8_encode($dateReporting));
	}
	$Dispo+=$nbDispo;
	$sheet->setCellValue('B4',utf8_encode($laVacation));
	$sheet->setCellValue('B5',utf8_encode($Dispo));
	$sheet->setCellValue('B6',utf8_encode($Cert));
	$sheet->setCellValue('B7',utf8_encode($RETQ));
	$sheet->setCellValue('B8',utf8_encode($RETQext));
	
	$sheet->setCellValue('A'.($ligne+2),utf8_encode("3. Points chauds : "));
	
// 	Enregistrement du fichier excel
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="ReportingQUALITE.xlsx"');
	header('Cache-Control: max-age=0');
	
	$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
	
	$chemin = '../../../tmp/ReportingQUALITE.xlsx';
	$writer->save($chemin);
	readfile($chemin);
	?>
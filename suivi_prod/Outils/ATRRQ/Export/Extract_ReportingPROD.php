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
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
$NumJour = date("N", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
$laVeille = date("Y-m-d", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
$leLendemain= date("Y-m-d", $timestamp);		
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
	
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+5-$NumJour, $tabDate[0]);
$leVendredi= date("Y-m-d", $timestamp);
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+6-$NumJour, $tabDate[0]);
$leSamedi= date("Y-m-d", $timestamp);	
$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+7-$NumJour, $tabDate[0]);
$leDimanche= date("Y-m-d", $timestamp);	

$destinataire="";
$req="SELECT EmailPro FROM new_rh_etatcivil WHERE Id=".$_SESSION['Id_PersonneSP'];
$resulEmail=mysqli_query($bdd,$req);
$nbEmail=mysqli_num_rows($resulEmail);
if ($nbEmail>0){
	$row=mysqli_fetch_array($resulEmail);
	$destinataire=$row['EmailPro'];
}

$laVacation="";
if($vacation=="J"){$laVacation="Jour";}
elseif($vacation=="S"){$laVacation="Soir";}
if($vacation=="N"){$laVacation="Nuit";}
if($vacation=="VSD"){$laVacation="VSD";}


$req="SELECT sp_olwficheintervention.Id,";
$req.="sp_olwficheintervention.Id_StatutPROD,(SELECT sp_olwretour.Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourPROD) AS RetourPROD,";
$req.="sp_olwficheintervention.Id_StatutQUALITE,(SELECT sp_olwretour.Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourQUALITE) AS RetourQUALITE,";
$req.="sp_client.Libelle AS Client, ";
$req.="sp_olwficheintervention.PosteAvionACP AS Poste, ";
$req.="sp_olwdossier.MSN, ";
$req.="sp_olwdossier.Reference AS NoDossier, ";
$req.="'COMPETENCES' AS Competences, ";
$req.="sp_olwdossier.Titre, ";
$req.="sp_olwficheintervention.TravailRealise, ";
$req.="sp_olwficheintervention.Id_StatutPROD, ";
$req.="sp_olwficheintervention.Id_RetourPROD, ";
$req.="sp_olwficheintervention.CommentairePROD,";
$req.="sp_olwdossier.Elec,";
$req.="sp_olwdossier.Systeme,";
$req.="sp_olwdossier.Structure,";
$req.="sp_olwdossier.Oxygene,";
$req.="sp_olwdossier.Hydraulique,";
$req.="sp_olwdossier.Fuel,";
$req.="sp_olwdossier.Metal ";

$req.="FROM ";
$req.="sp_olwdossier, ";
$req.="sp_olwficheintervention, ";
$req.="sp_client ";

$req.="WHERE ";
$req.="sp_olwficheintervention.Id_Dossier = sp_olwdossier.Id ";
$req.="AND sp_olwdossier.Id_Client = sp_client.Id ";

if($laVacation=="VSD"){
 	$req.="AND ( ";
	$req.="(sp_olwficheintervention.DateIntervention='".$leVendredi."' AND sp_olwficheintervention.Vacation='VSD Nuit') OR ";
	$req.="(sp_olwficheintervention.DateIntervention='".$leSamedi."' AND sp_olwficheintervention.Vacation='VSD Jour') OR ";
	$req.="(sp_olwficheintervention.DateIntervention='".$leSamedi."' AND sp_olwficheintervention.Vacation='VSD Nuit') OR ";
	$req.="(sp_olwficheintervention.DateIntervention='".$leDimanche."' AND sp_olwficheintervention.Vacation='VSD Jour')) ";
}
else{
	$req.="AND sp_olwficheintervention.DateIntervention='".$leJour."' AND sp_olwficheintervention.Vacation='".$vacation."' ";		
}
$req.="ORDER BY sp_olwdossier.MSN ASC, sp_olwdossier.Reference ASC ";

$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();
$sheet->setTitle('Reporting PROD');

//Entete
$sheet->setCellValue('A1',utf8_encode("    1. Bilan de la vacation : "));
$sheet->setCellValue('A2',utf8_encode("Date"));
$sheet->setCellValue('A4',utf8_encode("Vacation"));
$sheet->setCellValue('A5',utf8_encode("Gammes Disponibles/planifiées"));
$sheet->setCellValue('A6',utf8_encode("Gammes TERA"));
$sheet->setCellValue('A7',utf8_encode("Gammes RETP"));
$sheet->setCellValue('A8',utf8_encode("Gammes TFS"));
$sheet->setCellValue('A9',utf8_encode("Gammes à relancer"));
$sheet->setCellValue('A10',utf8_encode("Nombre de BC présents"));
$sheet->setCellValue('A11',utf8_encode("Nombre d'abscences"));
$sheet->setCellValue('A12',utf8_encode("Heures prod dispo"));
$sheet->setCellValue('A13',utf8_encode("Heures prod effective"));

$sheet->getColumnDimension('A')->setWidth(30);
$sheet->getColumnDimension('B')->setWidth(10);
$sheet->getColumnDimension('C')->setWidth(7);
$sheet->getColumnDimension('D')->setWidth(19);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(35);
$sheet->getColumnDimension('G')->setWidth(15);
$sheet->getColumnDimension('H')->setWidth(25);
$sheet->getColumnDimension('I')->setWidth(10);
$sheet->getColumnDimension('J')->setWidth(10);
$sheet->getColumnDimension('K')->setWidth(10);
$sheet->getColumnDimension('L')->setWidth(35);

$sheet->getStyle('A2:B13')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A2:B13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A2:B13')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


$messageSuite="";
$Dispo=0;
$Qarj=0;
$Ec=0;
$Retp=0;
$Relancee=0;
$TFS=0;
$ligne=20;
$sommeTP=0;
$operateurs="";

if ($nbResulta>0){	
	while($row=mysqli_fetch_array($result)){		
		$statut="";
		$retour="";

		if($row['Id_StatutQUALITE']<>0){$statut=$row['Id_StatutQUALITE'];}
		else{$statut=$row['Id_StatutPROD'];}
		if($row['Id_RetourPROD']<>0){$retour=$row['RetourPROD'];}
		
		
		$couleur="#ffffff";
		if($statut=="TERA" || $statut=="REWORK"){$couleur="#92d050";}
		elseif($statut=="TFS"){$couleur="#538dd5";}
		
		$Dispo++;
		if($statut=="TERA" || $statut=="REWORK"){$Qarj++;}
		elseif($statut=="TFS"){
			$TFS++;
			if($row['Id_RetourPROD']==15 || $row['Id_RetourPROD']==16 || $row['Id_RetourPROD']==38 || $row['Id_RetourPROD']==39){$Ec++;}
			elseif($row['Id_RetourPROD']==6 || $row['Id_RetourPROD']==14 || $row['Id_RetourPROD']==35){$Relancee++;}
			else{$Retp++;}
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
					
		$req="SELECT ";
		$req.="	SUM(sp_olwfi_travaileffectue.TempsPasse) AS TotalTempsPasse ";
		$req.="FROM ";
		$req.="	sp_olwfi_travaileffectue ";
		$req.="WHERE ";
		$req.="	sp_olwfi_travaileffectue.Id_FI = ".$row['Id']." ";

		$resultTP=mysqli_query($bdd,$req); 
		$rowTP=mysqli_fetch_array($resultTP);
		$sommeTP+=$rowTP['TotalTempsPasse'];
		
		$req="SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Operateur 
			FROM sp_olwfi_travaileffectue 
			WHERE Id_FI = ".$row['Id']." ";

		$resultOperateur=mysqli_query($bdd,$req); 
		$nbResultaOP=mysqli_num_rows($resultOperateur);
		$operateurs="";
		if ($nbResultaOP>0){	
			while($rowOP=mysqli_fetch_array($resultOperateur)){
				if($operateurs<>""){$operateurs.="\n";}
				$operateurs.=$rowOP['Operateur']."";
			}
		}
		
 		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Client'])); 																	//client		
		$sheet->setCellValue('B'.$ligne,utf8_encode($row['Poste'])); 																	//poste
		$sheet->setCellValue('C'.$ligne,utf8_encode($row['MSN'])); 																		//MSN
		$sheet->setCellValue('D'.$ligne,utf8_encode($row['NoDossier'])); 													// Dossier
		$sheet->setCellValue('E'.$ligne,utf8_encode($competences)); 																// compétences
		$sheet->setCellValue('F'.$ligne,utf8_encode($row['Titre'])); 																		// Titre
		$sheet->setCellValue('G'.$ligne,utf8_encode($row['TravailRealise'])); 										//Travail realisé
		$sheet->setCellValue('H'.$ligne,utf8_encode($operateurs)); 		
		$sheet->getStyle('H'.$ligne)->getAlignment()->setWrapText(true); 		//Opérateur
		$sheet->setCellValue('I'.$ligne,utf8_encode($rowTP['TotalTempsPasse'])); 												// temps passé
		$sheet->setCellValue('J'.$ligne,utf8_encode($statut)); 																							// statut prod
		$sheet->setCellValue('K'.$ligne,utf8_encode($retour)); 																						// retour prod
		$sheet->setCellValue('L'.$ligne,utf8_encode($row['CommentairePROD'])); 					// Commentaire prod
		
		$sheet->getStyle('A'.$ligne.':L'.$ligne)->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
		$sheet->getStyle('A'.$ligne.':L'.$ligne)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$sheet->getStyle('A'.$ligne.':L'.$ligne)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$ligne++;
	}
}

if($laVacation=="VSD"){
	$sheet->setCellValue('B2',utf8_encode($leVendredi." | ".$leSamedi." | ".$leDimanche));
}
else{
	$sheet->setCellValue('B2',utf8_encode($dateReporting));
}
$sheet->setCellValue('B4',utf8_encode($laVacation));
$sheet->setCellValue('B5',utf8_encode($Dispo));
$sheet->setCellValue('B6',utf8_encode($Qarj)); // QARJ = TERA
$sheet->setCellValue('B7',utf8_encode($Retp));
$sheet->setCellValue('B8',utf8_encode($TFS));
$sheet->setCellValue('B9',utf8_encode($Relancee));
$sheet->setCellValue('B13',utf8_encode($sommeTP));



$sheet->setCellValue('A15',utf8_encode("Commentaires : "));

$sheet->setCellValue('A17',utf8_encode("    2. Détail activité vacation : "));

$sheet->setCellValue('A19',utf8_encode("Client"));
$sheet->setCellValue('B19',utf8_encode("Poste"));
$sheet->setCellValue('C19',utf8_encode("N° MSN"));
$sheet->setCellValue('D19',utf8_encode("N° Dossier"));
$sheet->setCellValue('E19',utf8_encode("Compétence"));
$sheet->setCellValue('F19',utf8_encode("Titre"));
$sheet->setCellValue('G19',utf8_encode("Travail à réaliser"));
$sheet->setCellValue('H19',utf8_encode("Opérateur"));
$sheet->setCellValue('I19',utf8_encode("Temps passé"));
$sheet->setCellValue('J19',utf8_encode("Statut production"));
$sheet->setCellValue('K19',utf8_encode("Retour Production"));
$sheet->setCellValue('L19',utf8_encode("Commentaire Production"));

$sheet->getStyle('A19:L19')->getBorders()->applyFromArray(array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN ,'color' => array('rgb' => '#000000'))));
$sheet->getStyle('A19:L19')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('A19:L19')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$sheet->setCellValue('A'.($ligne+2),utf8_encode("    3. Points chauds : "));


//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="ReportingPROD.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');

$chemin = '../../../tmp/ReportingPROD.xlsx';
$writer->save($chemin);
readfile($chemin);

 ?>
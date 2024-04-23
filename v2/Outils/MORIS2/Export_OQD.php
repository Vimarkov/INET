<?php
session_start();
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';
require '../ConnexioniSansBody.php';
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

if($_SESSION["Langue"]=="FR")
{
	$MoisLettre = array("Jan", "Fev", "Mar", "Avr", "Mai", "Jui", "Juil", "Aou", "Sep", "Oct", "Nov", "Dec");
	$MoisLettre2 = array("J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D");
}
else
{
	$MoisLettre = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
	$MoisLettre2 = array("J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D");
}

//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

$objectifClient=0;
$req="SELECT Objectif FROM moris_objectifclient ";
$result=mysqli_query($bdd,$req);
$nbObj=mysqli_num_rows($result);
if($nbObj>0){
	$Ligne=mysqli_fetch_array($result);
	$objectifClient=$Ligne['Objectif'];
}

if($_SESSION['FiltreRECORD_Vision']==1){
	//Ligne En-tete
	if($_SESSION['Langue']=="FR"){
		$sheet->setCellValue('A1',utf8_encode('Mois'));
		$sheet->setCellValue('B1',utf8_encode('Prestation'));
		$sheet->setCellValue('C1',utf8_encode('UER/Dept/Filiale'));
		$sheet->setCellValue('D1',utf8_encode('Objectif du client %'));
		$sheet->setCellValue('E1',utf8_encode('Objectif client global %'));
		$sheet->setCellValue('F1',utf8_encode('Livrables conformes'));
		$sheet->setCellValue('G1',utf8_encode('Livrables dans la tolérance'));
		$sheet->setCellValue('H1',utf8_encode('Livrables non-conformes'));
		$sheet->setCellValue('I1',utf8_encode('Réalisé %'));
		$sheet->setCellValue('J1',utf8_encode('Principales causes identifiées'));
		$sheet->setCellValue('K1',utf8_encode('Actions'));
		$sheet->setCellValue('L1',utf8_encode('Mode de calcul'));
	}
	else{
		$sheet->setCellValue('A1',utf8_encode('Month'));
		$sheet->setCellValue('B1',utf8_encode('Site'));
		$sheet->setCellValue('C1',utf8_encode('UER/Department/Subsidiary'));
		$sheet->setCellValue('D1',utf8_encode('Customer Objectives %'));
		$sheet->setCellValue('E1',utf8_encode('Global customer objective %'));
		$sheet->setCellValue('F1',utf8_encode('Compliant deliverables'));
		$sheet->setCellValue('G1',utf8_encode('Deliverable within tolerance'));
		$sheet->setCellValue('H1',utf8_encode('Non-compliant deliverables'));
		$sheet->setCellValue('I1',utf8_encode('Realised %'));
		$sheet->setCellValue('J1',utf8_encode('Main root causes identified'));
		$sheet->setCellValue('K1',utf8_encode('Actions'));
		$sheet->setCellValue('L1',utf8_encode('calculation method'));
	}
	$sheet->getStyle('A1:L1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

	$sheet->getDefaultColumnDimension()->setWidth(20);

	$sheet->getColumnDimension('J')->setWidth(60);
	$sheet->getColumnDimension('K')->setWidth(60);
	$sheet->getColumnDimension('L')->setWidth(60);

	//Liste des prestations concernées + récupérer le nombre
	$req="SELECT new_competences_prestation.Id 
			FROM new_competences_prestation
			LEFT JOIN new_competences_plateforme
			ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
			WHERE new_competences_prestation.UtiliseMORIS>0 ";
	if($_SESSION['FiltreRECORD_Prestation']<>""){
		$req.="AND new_competences_prestation.Id IN(".$_SESSION['FiltreRECORD_Prestation'].") ";
	}

	$resultPrestation2=mysqli_query($bdd,$req);
	$nbPrestation2=mysqli_num_rows($resultPrestation2);

	$listePrestation2="-1";
	if ($nbPrestation2 > 0)
	{
		mysqli_data_seek($resultPrestation2,0);
		while($row=mysqli_fetch_array($resultPrestation2))
		{
			if($listePrestation2<>""){$listePrestation2.=",";}
			$listePrestation2.=$row['Id'];
		}
	}

	$moisEC=date($_SESSION['MORIS_Annee']."-".$_SESSION['MORIS_Mois']."-1");
	$date_11Mois = date("Y-m-d",strtotime($moisEC." -11 month"));

	$laDate=$date_11Mois;

	$couleur="EEEEEE";
	$ligne = 2;
		
	for($nbMois=1;$nbMois<=12;$nbMois++){
		$anneeEC=date("Y",strtotime($laDate." +0 month"));
		$moisEC=date("m",strtotime($laDate." +0 month"));
		
		$mois_6Mois=date("m",strtotime($laDate." -6 month"));
		$annee_6Mois=date("Y",strtotime($laDate." -6 month"));

		$req="SELECT
		(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
		(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS UER,
		ObjectifClientOTD,NbRetourClientOTD,ObjectifClientOQD,NbRetourClientOQD,
		IF(Annee<2022,NbLivrableOTD-NbRetourClientOTD,NbLivrableConformeOTD) AS NbLivrableConformeOTD,
		IF(Annee<2022,NbLivrableOQD-NbRetourClientOQD,NbLivrableConformeOQD) AS NbLivrableConformeOQD,
		NbLivrableToleranceOTD,NbLivrableToleranceOQD,ModeCalculOTD,ModeCalculOQD,
		CauseOTD,ActionOTD,CauseOQD,ActionOQD
		FROM moris_moisprestation
		WHERE Annee=".$anneeEC." 
		AND Mois=".$moisEC."
		AND Suppr=0 	
		AND PasOQD=0	
		AND PasActivite=0
		";
		if($listePrestation2<>""){
			$req.="AND moris_moisprestation.Id_Prestation IN (".$listePrestation2.") ";
		}
		$resultEC=mysqli_query($bdd,$req);
		$nbResultaMoisPrestaEC=mysqli_num_rows($resultEC);
		
		if($nbResultaMoisPrestaEC>0){
			while($LigneMoisPrestationEC=mysqli_fetch_array($resultEC)){
				if($couleur=="FFFFFF"){$couleur="EEEEEE";}
				else{$couleur="FFFFFF";}
				
				$realise=0;
				if(($LigneMoisPrestationEC['NbLivrableConformeOQD']+$LigneMoisPrestationEC['NbLivrableToleranceOQD']+$LigneMoisPrestationEC['NbRetourClientOQD'])>0){
					$realise=round(($LigneMoisPrestationEC['NbLivrableConformeOQD']/($LigneMoisPrestationEC['NbLivrableConformeOQD']+$LigneMoisPrestationEC['NbLivrableToleranceOQD']+$LigneMoisPrestationEC['NbRetourClientOQD']))*100,1);
				}
				
				$sheet->setCellValue('A'.$ligne,utf8_encode($MoisLettre[$moisEC-1]." ".$anneeEC));
				$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['Prestation'])));
				$sheet->setCellValue('C'.$ligne,utf8_encode($LigneMoisPrestationEC['UER']));
				$sheet->setCellValue('D'.$ligne,utf8_encode($LigneMoisPrestationEC['ObjectifClientOQD']));
				$sheet->setCellValue('E'.$ligne,utf8_encode($objectifClient));
				$sheet->setCellValue('F'.$ligne,utf8_encode($LigneMoisPrestationEC['NbLivrableConformeOQD']));
				$sheet->setCellValue('G'.$ligne,utf8_encode($LigneMoisPrestationEC['NbLivrableToleranceOQD']));
				$sheet->setCellValue('H'.$ligne,utf8_encode($LigneMoisPrestationEC['NbRetourClientOQD']));
				$sheet->setCellValue('I'.$ligne,utf8_encode($realise));
				$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['CauseOQD'])));
				$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['ActionOQD'])));
				$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['ModeCalculOQD'])));
				$sheet->getStyle('J'.$ligne)->getAlignment()->setWrapText(true);
				$sheet->getStyle('K'.$ligne)->getAlignment()->setWrapText(true);
				$sheet->getStyle('L'.$ligne)->getAlignment()->setWrapText(true);
				
				$sheet->getStyle('A'.$ligne.':L'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
				$ligne++;
			}
		}
		$laDate=date("Y-m-d",strtotime($laDate." +1 month"));
	}
}
else{
	//Ligne En-tete
	if($_SESSION['Langue']=="FR"){
		$sheet->setCellValue('A1',utf8_encode('Mois'));
		$sheet->setCellValue('B1',utf8_encode('Prestation'));
		$sheet->setCellValue('C1',utf8_encode('UER/Dept/Filiale'));
		$sheet->setCellValue('D1',utf8_encode('Objectif du client %'));
		$sheet->setCellValue('E1',utf8_encode('Objectif client global %'));
		$sheet->setCellValue('F1',utf8_encode('Livrables conformes'));
		$sheet->setCellValue('G1',utf8_encode('Livrables dans la tolérance'));
		$sheet->setCellValue('H1',utf8_encode('Livrables non-conformes'));
		$sheet->setCellValue('I1',utf8_encode('Réalisé %'));
		$sheet->setCellValue('J1',utf8_encode('Principales causes identifiées'));
		$sheet->setCellValue('K1',utf8_encode('Actions'));
		$sheet->setCellValue('L1',utf8_encode('Mode de calcul'));
	}
	else{
		$sheet->setCellValue('A1',utf8_encode('Month'));
		$sheet->setCellValue('B1',utf8_encode('Site'));
		$sheet->setCellValue('C1',utf8_encode('UER/Department/Subsidiary'));
		$sheet->setCellValue('D1',utf8_encode('Customer Objectives %'));
		$sheet->setCellValue('E1',utf8_encode('Global customer objective %'));
		$sheet->setCellValue('F1',utf8_encode('Compliant deliverables'));
		$sheet->setCellValue('G1',utf8_encode('Deliverable within tolerance'));
		$sheet->setCellValue('H1',utf8_encode('Non-compliant deliverables'));
		$sheet->setCellValue('I1',utf8_encode('Realised %'));
		$sheet->setCellValue('J1',utf8_encode('Main root causes identified'));
		$sheet->setCellValue('K1',utf8_encode('Actions'));
		$sheet->setCellValue('L1',utf8_encode('calculation method'));
	}
	$sheet->getStyle('A1:L1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

	$sheet->getDefaultColumnDimension()->setWidth(20);

	$sheet->getColumnDimension('J')->setWidth(60);
	$sheet->getColumnDimension('K')->setWidth(60);
	$sheet->getColumnDimension('L')->setWidth(60);

	//Liste des plateformes sélectionnées 
	$req="SELECT DISTINCT new_competences_prestation.Id,
			new_competences_prestation.Libelle
			FROM new_competences_prestation
			LEFT JOIN new_competences_plateforme
			ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
			WHERE new_competences_prestation.UtiliseMORIS>0 ";
	if($_SESSION['FiltreRECORD_Prestation']<>""){
		$req.="AND new_competences_prestation.Id IN(".$_SESSION['FiltreRECORD_Prestation'].") ";
	}
	$req.="ORDER BY new_competences_prestation.Libelle";
	$resultPresta=mysqli_query($bdd,$req);
	$nbPresta=mysqli_num_rows($resultPresta);
	
	$anneeEC=$_SESSION['MORIS_Annee'];
	$moisEC=$_SESSION['MORIS_Mois'];
	
	$couleur="EEEEEE";
	$ligne = 2;
		
	if($nbPresta>0){
		while($rowPresta=mysqli_fetch_array($resultPresta)){

			$req="SELECT
			(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
			(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS UER,
			ObjectifClientOTD,NbRetourClientOTD,ObjectifClientOQD,NbRetourClientOQD,
			IF(Annee<2022,NbLivrableOTD-NbRetourClientOTD,NbLivrableConformeOTD) AS NbLivrableConformeOTD,
			IF(Annee<2022,NbLivrableOQD-NbRetourClientOQD,NbLivrableConformeOQD) AS NbLivrableConformeOQD,
			NbLivrableToleranceOTD,NbLivrableToleranceOQD,ModeCalculOTD,ModeCalculOQD,
			CauseOTD,ActionOTD,CauseOQD,ActionOQD
			FROM moris_moisprestation
			WHERE Annee=".$anneeEC." 
			AND Mois=".$moisEC."
			AND Suppr=0 
			AND PasOQD=0
			AND PasActivite=0
			AND Id_Prestation = ".$rowPresta['Id']." ";
			$resultEC=mysqli_query($bdd,$req);
			$nbResultaMoisPrestaEC=mysqli_num_rows($resultEC);
			
			if($nbResultaMoisPrestaEC>0){
				while($LigneMoisPrestationEC=mysqli_fetch_array($resultEC)){
					if($couleur=="FFFFFF"){$couleur="EEEEEE";}
					else{$couleur="FFFFFF";}
					
					$realise=0;
					if(($LigneMoisPrestationEC['NbLivrableConformeOQD']+$LigneMoisPrestationEC['NbLivrableToleranceOQD']+$LigneMoisPrestationEC['NbRetourClientOQD'])>0){
						$realise=round(($LigneMoisPrestationEC['NbLivrableConformeOQD']/($LigneMoisPrestationEC['NbLivrableConformeOQD']+$LigneMoisPrestationEC['NbLivrableToleranceOQD']+$LigneMoisPrestationEC['NbRetourClientOQD']))*100,1);
					
					
						$sheet->setCellValue('A'.$ligne,utf8_encode($MoisLettre[$moisEC-1]." ".$anneeEC));
						$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['Prestation'])));
						$sheet->setCellValue('C'.$ligne,utf8_encode($LigneMoisPrestationEC['UER']));
						$sheet->setCellValue('D'.$ligne,utf8_encode($LigneMoisPrestationEC['ObjectifClientOQD']));
						$sheet->setCellValue('E'.$ligne,utf8_encode($objectifClient));
						$sheet->setCellValue('F'.$ligne,utf8_encode($LigneMoisPrestationEC['NbLivrableConformeOQD']));
						$sheet->setCellValue('G'.$ligne,utf8_encode($LigneMoisPrestationEC['NbLivrableToleranceOQD']));
						$sheet->setCellValue('H'.$ligne,utf8_encode($LigneMoisPrestationEC['NbRetourClientOQD']));
						$sheet->setCellValue('I'.$ligne,utf8_encode($realise));
						$sheet->setCellValue('J'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['CauseOQD'])));
						$sheet->setCellValue('K'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['ActionOQD'])));
						$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['ModeCalculOQD'])));
						$sheet->getStyle('J'.$ligne)->getAlignment()->setWrapText(true);
						$sheet->getStyle('K'.$ligne)->getAlignment()->setWrapText(true);
						$sheet->getStyle('L'.$ligne)->getAlignment()->setWrapText(true);
						
						$sheet->getStyle('A'.$ligne.':L'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
						$ligne++;
					}
				}
			}
		}
	}
}

//Enregistrement du fichier excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
header('Content-Disposition: attachment;filename="Export.xlsx"'); 
header('Cache-Control: max-age=0'); 

$writer = PHPExcel_IOFactory::createWriter($workbook, 'Excel2007');
$chemin = '../../tmp/Export.xlsx';
$writer->save($chemin);
readfile($chemin);
?>
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

if($_SESSION['FiltreRECORD_Vision']==1){
	//Ligne En-tete
	if($_SESSION['Langue']=="FR"){
		$sheet->setCellValue('A1',utf8_encode('Mois'));
		$sheet->setCellValue('B1',utf8_encode('Prestation'));
		$sheet->setCellValue('C1',utf8_encode('UER/Dept/Filiale'));
		$sheet->setCellValue('D1',utf8_encode('Charge/Capa'));
		$sheet->setCellValue('E1',utf8_encode('M'));
		$sheet->setCellValue('F1',utf8_encode('M+1'));
		$sheet->setCellValue('G1',utf8_encode('M+2'));
		$sheet->setCellValue('H1',utf8_encode('M+3'));
		$sheet->setCellValue('I1',utf8_encode('M+4'));
		$sheet->setCellValue('J1',utf8_encode('M+5'));
		$sheet->setCellValue('K1',utf8_encode('M+6'));
		$sheet->setCellValue('L1',utf8_encode('Besoins effectif - sureffectif'));
	}
	else{
		$sheet->setCellValue('A1',utf8_encode('Month'));
		$sheet->setCellValue('B1',utf8_encode('Site'));
		$sheet->setCellValue('C1',utf8_encode('UER/Department/Subsidiary'));
		$sheet->setCellValue('D1',utf8_encode('Charge/Capa'));
		$sheet->setCellValue('E1',utf8_encode('M'));
		$sheet->setCellValue('F1',utf8_encode('M+1'));
		$sheet->setCellValue('G1',utf8_encode('M+2'));
		$sheet->setCellValue('H1',utf8_encode('M+3'));
		$sheet->setCellValue('I1',utf8_encode('M+4'));
		$sheet->setCellValue('J1',utf8_encode('M+5'));
		$sheet->setCellValue('K1',utf8_encode('M+6'));
		$sheet->setCellValue('L1',utf8_encode('Needs workforce - overstaffing'));
	}
	$sheet->getStyle('A1:L1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

	$sheet->getDefaultColumnDimension()->setWidth(20);

	$sheet->getColumnDimension('L')->setWidth(60);

	//Liste des prestations concernées + récupérer le nombre
	$req="SELECT new_competences_prestation.Id 
			FROM new_competences_prestation
			LEFT JOIN new_competences_plateforme
			ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
			WHERE new_competences_prestation.UtiliseMORIS>0  
			AND new_competences_prestation.ChargeADesactive=0
			";
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
	$date_11Mois = date("Y-m-d",strtotime($moisEC." -2 month"));

	$laDate=$date_11Mois;

	$couleur="EEEEEE";
	$ligne = 2;
		
	for($nbMois=1;$nbMois<=12;$nbMois++){
		$anneeEC=date("Y",strtotime($laDate." +0 month"));
		$moisEC=date("m",strtotime($laDate." +0 month"));
		
		$req="SELECT
		(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
		(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS UER,
		PermanentCurrent+TemporyCurrent+InterneCurrent+COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0) AS InterneCurrent,
		SubContractorCurrent+COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0) AS SubContractorCurrent,
		M1+COALESCE((SELECT SUM(M1) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0) AS InterneM1,
		M2+COALESCE((SELECT SUM(M2) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0) AS InterneM2,
		M3+COALESCE((SELECT SUM(M3) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0) AS InterneM3,
		M4+COALESCE((SELECT SUM(M4) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0) AS InterneM4,
		M5+COALESCE((SELECT SUM(M5) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0) AS InterneM5,
		M6+COALESCE((SELECT SUM(M6) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0) AS InterneM6,
		
		COALESCE((SELECT SUM(M1) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0) AS ExterneM1,
		COALESCE((SELECT SUM(M2) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0) AS ExterneM2,
		COALESCE((SELECT SUM(M3) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0) AS ExterneM3,
		COALESCE((SELECT SUM(M4) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0) AS ExterneM4,
		COALESCE((SELECT SUM(M5) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0) AS ExterneM5,
		COALESCE((SELECT SUM(M6) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0) AS ExterneM6,
		
		IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',PermanentCurrent+TemporyCurrent+InterneCurrent,COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0)) AS CapaInterneM,
		IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',SubContractorCurrent,COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0)) AS CapaExterneM,
		IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',PermanentCurrent+TemporyCurrent+InterneCurrent,COALESCE((SELECT SUM(CapaM1) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0)) AS CapaInterneM1,
		IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',SubContractorCurrent,COALESCE((SELECT SUM(CapaM1) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0)) AS CapaExterneM1,
		IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',PermanentCurrent+TemporyCurrent+InterneCurrent,COALESCE((SELECT SUM(CapaM2) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0)) AS CapaInterneM2,
		IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',SubContractorCurrent,COALESCE((SELECT SUM(CapaM2) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0)) AS CapaExterneM2,
		IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',PermanentCurrent+TemporyCurrent+InterneCurrent,COALESCE((SELECT SUM(CapaM3) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0)) AS CapaInterneM3,
		IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',SubContractorCurrent,COALESCE((SELECT SUM(CapaM3) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0)) AS CapaExterneM3,
		IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',PermanentCurrent+TemporyCurrent+InterneCurrent,COALESCE((SELECT SUM(CapaM4) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0)) AS CapaInterneM4,
		IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',SubContractorCurrent,COALESCE((SELECT SUM(CapaM4) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0)) AS CapaExterneM4,
		IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',PermanentCurrent+TemporyCurrent+InterneCurrent,COALESCE((SELECT SUM(CapaM5) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0)) AS CapaInterneM5,
		IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',SubContractorCurrent,COALESCE((SELECT SUM(CapaM5) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0)) AS CapaExterneM5,
		IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',PermanentCurrent+TemporyCurrent+InterneCurrent,COALESCE((SELECT SUM(CapaM6) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0)) AS CapaInterneM6,
		IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',SubContractorCurrent,COALESCE((SELECT SUM(CapaM6) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0)) AS CapaExterneM6,
		BesoinEffectif
		FROM moris_moisprestation
		WHERE Annee=".$anneeEC." 
		AND Mois=".$moisEC."
		AND Suppr=0 											
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
			
				$sheet->setCellValue('A'.$ligne,utf8_encode($MoisLettre[$moisEC-1]." ".$anneeEC));
				$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['Prestation'])));
				$sheet->setCellValue('C'.$ligne,utf8_encode($LigneMoisPrestationEC['UER']));
				$sheet->setCellValue('D'.$ligne,utf8_encode('Charge Interne'));
				$sheet->setCellValue('E'.$ligne,utf8_encode($LigneMoisPrestationEC['InterneCurrent']));
				$sheet->setCellValue('F'.$ligne,utf8_encode($LigneMoisPrestationEC['InterneM1']));
				$sheet->setCellValue('G'.$ligne,utf8_encode($LigneMoisPrestationEC['InterneM2']));
				$sheet->setCellValue('H'.$ligne,utf8_encode($LigneMoisPrestationEC['InterneM3']));
				$sheet->setCellValue('I'.$ligne,utf8_encode($LigneMoisPrestationEC['InterneM4']));
				$sheet->setCellValue('J'.$ligne,utf8_encode($LigneMoisPrestationEC['InterneM5']));
				$sheet->setCellValue('K'.$ligne,utf8_encode($LigneMoisPrestationEC['InterneM6']));
				$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['BesoinEffectif'])));
				$sheet->getStyle('L'.$ligne)->getAlignment()->setWrapText(true);
				
				$sheet->getStyle('A'.$ligne.':L'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
				$ligne++;
				
				$sheet->setCellValue('A'.$ligne,utf8_encode($MoisLettre[$moisEC-1]." ".$anneeEC));
				$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['Prestation'])));
				$sheet->setCellValue('C'.$ligne,utf8_encode($LigneMoisPrestationEC['UER']));
				$sheet->setCellValue('D'.$ligne,utf8_encode('Charge Externe'));
				$sheet->setCellValue('E'.$ligne,utf8_encode($LigneMoisPrestationEC['SubContractorCurrent']));
				$sheet->setCellValue('F'.$ligne,utf8_encode($LigneMoisPrestationEC['ExterneM1']));
				$sheet->setCellValue('G'.$ligne,utf8_encode($LigneMoisPrestationEC['ExterneM2']));
				$sheet->setCellValue('H'.$ligne,utf8_encode($LigneMoisPrestationEC['ExterneM3']));
				$sheet->setCellValue('I'.$ligne,utf8_encode($LigneMoisPrestationEC['ExterneM4']));
				$sheet->setCellValue('J'.$ligne,utf8_encode($LigneMoisPrestationEC['ExterneM5']));
				$sheet->setCellValue('K'.$ligne,utf8_encode($LigneMoisPrestationEC['ExterneM6']));
				$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['BesoinEffectif'])));
				$sheet->getStyle('L'.$ligne)->getAlignment()->setWrapText(true);
				
				$sheet->getStyle('A'.$ligne.':L'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
				$ligne++;
				
				$sheet->setCellValue('A'.$ligne,utf8_encode($MoisLettre[$moisEC-1]." ".$anneeEC));
				$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['Prestation'])));
				$sheet->setCellValue('C'.$ligne,utf8_encode($LigneMoisPrestationEC['UER']));
				$sheet->setCellValue('D'.$ligne,utf8_encode('Capa Interne'));
				$sheet->setCellValue('E'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaInterneM']));
				$sheet->setCellValue('F'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaInterneM1']));
				$sheet->setCellValue('G'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaInterneM2']));
				$sheet->setCellValue('H'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaInterneM3']));
				$sheet->setCellValue('I'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaInterneM4']));
				$sheet->setCellValue('J'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaInterneM5']));
				$sheet->setCellValue('K'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaInterneM6']));
				$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['BesoinEffectif'])));
				$sheet->getStyle('L'.$ligne)->getAlignment()->setWrapText(true);
				
				$sheet->getStyle('A'.$ligne.':L'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
				$ligne++;
				
				$sheet->setCellValue('A'.$ligne,utf8_encode($MoisLettre[$moisEC-1]." ".$anneeEC));
				$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['Prestation'])));
				$sheet->setCellValue('C'.$ligne,utf8_encode($LigneMoisPrestationEC['UER']));
				$sheet->setCellValue('D'.$ligne,utf8_encode('Capa Externe'));
				$sheet->setCellValue('E'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaExterneM']));
				$sheet->setCellValue('F'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaExterneM1']));
				$sheet->setCellValue('G'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaExterneM2']));
				$sheet->setCellValue('H'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaExterneM3']));
				$sheet->setCellValue('I'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaExterneM4']));
				$sheet->setCellValue('J'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaExterneM5']));
				$sheet->setCellValue('K'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaExterneM6']));
				$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['BesoinEffectif'])));
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
		$sheet->setCellValue('D1',utf8_encode('Charge/Capa'));
		$sheet->setCellValue('E1',utf8_encode('M'));
		$sheet->setCellValue('F1',utf8_encode('M+1'));
		$sheet->setCellValue('G1',utf8_encode('M+2'));
		$sheet->setCellValue('H1',utf8_encode('M+3'));
		$sheet->setCellValue('I1',utf8_encode('M+4'));
		$sheet->setCellValue('J1',utf8_encode('M+5'));
		$sheet->setCellValue('K1',utf8_encode('M+6'));
		$sheet->setCellValue('L1',utf8_encode('Besoins effectif - sureffectif'));
	}
	else{
		$sheet->setCellValue('A1',utf8_encode('Month'));
		$sheet->setCellValue('B1',utf8_encode('Site'));
		$sheet->setCellValue('C1',utf8_encode('UER/Department/Subsidiary'));
		$sheet->setCellValue('D1',utf8_encode('Charge/Capa'));
		$sheet->setCellValue('E1',utf8_encode('M'));
		$sheet->setCellValue('F1',utf8_encode('M+1'));
		$sheet->setCellValue('G1',utf8_encode('M+2'));
		$sheet->setCellValue('H1',utf8_encode('M+3'));
		$sheet->setCellValue('I1',utf8_encode('M+4'));
		$sheet->setCellValue('J1',utf8_encode('M+5'));
		$sheet->setCellValue('K1',utf8_encode('M+6'));
		$sheet->setCellValue('L1',utf8_encode('Needs workforce - overstaffing'));
	}
	$sheet->getStyle('A1:L1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

	$sheet->getDefaultColumnDimension()->setWidth(20);

	$sheet->getColumnDimension('L')->setWidth(60);

	//Liste des plateformes sélectionnées 
	$req="SELECT DISTINCT new_competences_prestation.Id,
			new_competences_prestation.Libelle
			FROM new_competences_prestation
			LEFT JOIN new_competences_plateforme
			ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
			WHERE new_competences_prestation.UtiliseMORIS>0  
			AND new_competences_prestation.ChargeADesactive=0 ";
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
			PermanentCurrent+TemporyCurrent+InterneCurrent+COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0) AS InterneCurrent,
			SubContractorCurrent+COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0) AS SubContractorCurrent,
			M1+COALESCE((SELECT SUM(M1) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0) AS InterneM1,
			M2+COALESCE((SELECT SUM(M2) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0) AS InterneM2,
			M3+COALESCE((SELECT SUM(M3) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0) AS InterneM3,
			M4+COALESCE((SELECT SUM(M4) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0) AS InterneM4,
			M5+COALESCE((SELECT SUM(M5) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0) AS InterneM5,
			M6+COALESCE((SELECT SUM(M6) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0) AS InterneM6,
			
			COALESCE((SELECT SUM(M1) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0) AS ExterneM1,
			COALESCE((SELECT SUM(M2) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0) AS ExterneM2,
			COALESCE((SELECT SUM(M3) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0) AS ExterneM3,
			COALESCE((SELECT SUM(M4) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0) AS ExterneM4,
			COALESCE((SELECT SUM(M5) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0) AS ExterneM5,
			COALESCE((SELECT SUM(M6) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0) AS ExterneM6,
			
			IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',PermanentCurrent+TemporyCurrent+InterneCurrent,COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0)) AS CapaInterneM,
			IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',SubContractorCurrent,COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0)) AS CapaExterneM,
			IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',PermanentCurrent+TemporyCurrent+InterneCurrent,COALESCE((SELECT SUM(CapaM1) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0)) AS CapaInterneM1,
			IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',SubContractorCurrent,COALESCE((SELECT SUM(CapaM1) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0)) AS CapaExterneM1,
			IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',PermanentCurrent+TemporyCurrent+InterneCurrent,COALESCE((SELECT SUM(CapaM2) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0)) AS CapaInterneM2,
			IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',SubContractorCurrent,COALESCE((SELECT SUM(CapaM2) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0)) AS CapaExterneM2,
			IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',PermanentCurrent+TemporyCurrent+InterneCurrent,COALESCE((SELECT SUM(CapaM3) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0)) AS CapaInterneM3,
			IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',SubContractorCurrent,COALESCE((SELECT SUM(CapaM3) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0)) AS CapaExterneM3,
			IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',PermanentCurrent+TemporyCurrent+InterneCurrent,COALESCE((SELECT SUM(CapaM4) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0)) AS CapaInterneM4,
			IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',SubContractorCurrent,COALESCE((SELECT SUM(CapaM4) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0)) AS CapaExterneM4,
			IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',PermanentCurrent+TemporyCurrent+InterneCurrent,COALESCE((SELECT SUM(CapaM5) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0)) AS CapaInterneM5,
			IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',SubContractorCurrent,COALESCE((SELECT SUM(CapaM5) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0)) AS CapaExterneM5,
			IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',PermanentCurrent+TemporyCurrent+InterneCurrent,COALESCE((SELECT SUM(CapaM6) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0),0)) AS CapaInterneM6,
			IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',SubContractorCurrent,COALESCE((SELECT SUM(CapaM6) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1),0)) AS CapaExterneM6,
			
			BesoinEffectif
			FROM moris_moisprestation
			WHERE Annee=".$anneeEC." 
			AND Mois=".$moisEC."
			AND Suppr=0 											
			AND Id_Prestation = ".$rowPresta['Id']." ";
			$resultEC=mysqli_query($bdd,$req);
			$nbResultaMoisPrestaEC=mysqli_num_rows($resultEC);
			
			if($nbResultaMoisPrestaEC>0){
				while($LigneMoisPrestationEC=mysqli_fetch_array($resultEC)){
					if($couleur=="FFFFFF"){$couleur="EEEEEE";}
					else{$couleur="FFFFFF";}
					
					$sheet->setCellValue('A'.$ligne,utf8_encode($MoisLettre[$moisEC-1]." ".$anneeEC));
					$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['Prestation'])));
					$sheet->setCellValue('C'.$ligne,utf8_encode($LigneMoisPrestationEC['UER']));
					$sheet->setCellValue('D'.$ligne,utf8_encode('Charge Interne'));
					$sheet->setCellValue('E'.$ligne,utf8_encode($LigneMoisPrestationEC['InterneCurrent']));
					$sheet->setCellValue('F'.$ligne,utf8_encode($LigneMoisPrestationEC['InterneM1']));
					$sheet->setCellValue('G'.$ligne,utf8_encode($LigneMoisPrestationEC['InterneM2']));
					$sheet->setCellValue('H'.$ligne,utf8_encode($LigneMoisPrestationEC['InterneM3']));
					$sheet->setCellValue('I'.$ligne,utf8_encode($LigneMoisPrestationEC['InterneM4']));
					$sheet->setCellValue('J'.$ligne,utf8_encode($LigneMoisPrestationEC['InterneM5']));
					$sheet->setCellValue('K'.$ligne,utf8_encode($LigneMoisPrestationEC['InterneM6']));
					$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['BesoinEffectif'])));
					$sheet->getStyle('L'.$ligne)->getAlignment()->setWrapText(true);
					
					$sheet->getStyle('A'.$ligne.':L'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
					$ligne++;
					
					$sheet->setCellValue('A'.$ligne,utf8_encode($MoisLettre[$moisEC-1]." ".$anneeEC));
					$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['Prestation'])));
					$sheet->setCellValue('C'.$ligne,utf8_encode($LigneMoisPrestationEC['UER']));
					$sheet->setCellValue('D'.$ligne,utf8_encode('Charge Externe'));
					$sheet->setCellValue('E'.$ligne,utf8_encode($LigneMoisPrestationEC['SubContractorCurrent']));
					$sheet->setCellValue('F'.$ligne,utf8_encode($LigneMoisPrestationEC['ExterneM1']));
					$sheet->setCellValue('G'.$ligne,utf8_encode($LigneMoisPrestationEC['ExterneM2']));
					$sheet->setCellValue('H'.$ligne,utf8_encode($LigneMoisPrestationEC['ExterneM3']));
					$sheet->setCellValue('I'.$ligne,utf8_encode($LigneMoisPrestationEC['ExterneM4']));
					$sheet->setCellValue('J'.$ligne,utf8_encode($LigneMoisPrestationEC['ExterneM5']));
					$sheet->setCellValue('K'.$ligne,utf8_encode($LigneMoisPrestationEC['ExterneM6']));
					$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['BesoinEffectif'])));
					$sheet->getStyle('L'.$ligne)->getAlignment()->setWrapText(true);
					
					$sheet->getStyle('A'.$ligne.':L'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
					$ligne++;
					
					$sheet->setCellValue('A'.$ligne,utf8_encode($MoisLettre[$moisEC-1]." ".$anneeEC));
					$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['Prestation'])));
					$sheet->setCellValue('C'.$ligne,utf8_encode($LigneMoisPrestationEC['UER']));
					$sheet->setCellValue('D'.$ligne,utf8_encode('Capa Interne'));
					$sheet->setCellValue('E'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaInterneM']));
					$sheet->setCellValue('F'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaInterneM1']));
					$sheet->setCellValue('G'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaInterneM2']));
					$sheet->setCellValue('H'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaInterneM3']));
					$sheet->setCellValue('I'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaInterneM4']));
					$sheet->setCellValue('J'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaInterneM5']));
					$sheet->setCellValue('K'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaInterneM6']));
					$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['BesoinEffectif'])));
					$sheet->getStyle('L'.$ligne)->getAlignment()->setWrapText(true);
					
					$sheet->getStyle('A'.$ligne.':L'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
					$ligne++;
					
					$sheet->setCellValue('A'.$ligne,utf8_encode($MoisLettre[$moisEC-1]." ".$anneeEC));
					$sheet->setCellValue('B'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['Prestation'])));
					$sheet->setCellValue('C'.$ligne,utf8_encode($LigneMoisPrestationEC['UER']));
					$sheet->setCellValue('D'.$ligne,utf8_encode('Capa Externe'));
					$sheet->setCellValue('E'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaExterneM']));
					$sheet->setCellValue('F'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaExterneM1']));
					$sheet->setCellValue('G'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaExterneM2']));
					$sheet->setCellValue('H'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaExterneM3']));
					$sheet->setCellValue('I'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaExterneM4']));
					$sheet->setCellValue('J'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaExterneM5']));
					$sheet->setCellValue('K'.$ligne,utf8_encode($LigneMoisPrestationEC['CapaExterneM6']));
					$sheet->setCellValue('L'.$ligne,utf8_encode(stripslashes($LigneMoisPrestationEC['BesoinEffectif'])));
					$sheet->getStyle('L'.$ligne)->getAlignment()->setWrapText(true);
					
					$sheet->getStyle('A'.$ligne.':L'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
					$ligne++;
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
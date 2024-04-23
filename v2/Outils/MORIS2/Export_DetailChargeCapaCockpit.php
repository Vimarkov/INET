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

function unNombreSinon0($leNombre){
	$nb=0;
	if($leNombre<>""){$nb=(double)$leNombre;}
	return $nb;
}


//Nouveau fichier
$workbook = new PHPExcel;
$sheet = $workbook->getActiveSheet();

//Ligne En-tete
if($_SESSION['Langue']=="FR"){
	$sheet->setCellValue('A1',utf8_encode('Famille'));
	$sheet->setCellValue('B1',utf8_encode('Ressource'));
	$sheet->setCellValue('C1',utf8_encode('M'));
	$sheet->setCellValue('F1',utf8_encode('M+1'));
	$sheet->setCellValue('I1',utf8_encode('M+2'));
	$sheet->setCellValue('L1',utf8_encode('M+3'));
	$sheet->setCellValue('O1',utf8_encode('M+4'));
	$sheet->setCellValue('R1',utf8_encode('M+5'));
	$sheet->setCellValue('U1',utf8_encode('M+6'));
	
	$sheet->setCellValue('C2',utf8_encode('Delta'));
	$sheet->setCellValue('D2',utf8_encode('Volume Capa'));
	$sheet->setCellValue('E2',utf8_encode('Volume Charge'));
	$sheet->setCellValue('F2',utf8_encode('Delta'));
	$sheet->setCellValue('G2',utf8_encode('Volume Capa'));
	$sheet->setCellValue('H2',utf8_encode('Volume Charge'));
	$sheet->setCellValue('I2',utf8_encode('Delta'));
	$sheet->setCellValue('J2',utf8_encode('Volume Capa'));
	$sheet->setCellValue('K2',utf8_encode('Volume Charge'));
	$sheet->setCellValue('L2',utf8_encode('Delta'));
	$sheet->setCellValue('M2',utf8_encode('Volume Capa'));
	$sheet->setCellValue('N2',utf8_encode('Volume Charge'));
	$sheet->setCellValue('O2',utf8_encode('Delta'));
	$sheet->setCellValue('P2',utf8_encode('Volume Capa'));
	$sheet->setCellValue('Q2',utf8_encode('Volume Charge'));
	$sheet->setCellValue('R2',utf8_encode('Delta'));
	$sheet->setCellValue('S2',utf8_encode('Volume Capa'));
	$sheet->setCellValue('T2',utf8_encode('Volume Charge'));
	$sheet->setCellValue('U2',utf8_encode('Delta'));
	$sheet->setCellValue('V2',utf8_encode('Volume Capa'));
	$sheet->setCellValue('W2',utf8_encode('Volume Charge'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Family'));
	$sheet->setCellValue('B1',utf8_encode('Resource'));
	$sheet->setCellValue('C1',utf8_encode('M'));
	$sheet->setCellValue('F1',utf8_encode('M+1'));
	$sheet->setCellValue('I1',utf8_encode('M+2'));
	$sheet->setCellValue('L1',utf8_encode('M+3'));
	$sheet->setCellValue('O1',utf8_encode('M+4'));
	$sheet->setCellValue('R1',utf8_encode('M+5'));
	$sheet->setCellValue('U1',utf8_encode('M+6'));
	
	$sheet->setCellValue('C2',utf8_encode('Delta'));
	$sheet->setCellValue('D2',utf8_encode('Capacity volume'));
	$sheet->setCellValue('E2',utf8_encode('Load volume'));
	$sheet->setCellValue('F2',utf8_encode('Delta'));
	$sheet->setCellValue('G2',utf8_encode('Capacity volume'));
	$sheet->setCellValue('H2',utf8_encode('Load volume'));
	$sheet->setCellValue('I2',utf8_encode('Delta'));
	$sheet->setCellValue('J2',utf8_encode('Capacity volume'));
	$sheet->setCellValue('K2',utf8_encode('Load volume'));
	$sheet->setCellValue('L2',utf8_encode('Delta'));
	$sheet->setCellValue('M2',utf8_encode('Capacity volume'));
	$sheet->setCellValue('N2',utf8_encode('Load volume'));
	$sheet->setCellValue('O2',utf8_encode('Delta'));
	$sheet->setCellValue('P2',utf8_encode('Capacity volume'));
	$sheet->setCellValue('Q2',utf8_encode('Load volume'));
	$sheet->setCellValue('R2',utf8_encode('Delta'));
	$sheet->setCellValue('S2',utf8_encode('Capacity volume'));
	$sheet->setCellValue('T2',utf8_encode('Load volume'));
	$sheet->setCellValue('U2',utf8_encode('Delta'));
	$sheet->setCellValue('V2',utf8_encode('Capacity volume'));
	$sheet->setCellValue('W2',utf8_encode('Load volume'));
}
$sheet->mergeCells('A1:A2');
$sheet->mergeCells('B1:B2');

$sheet->getDefaultColumnDimension()->setWidth(8);
$sheet->getColumnDimension('A')->setWidth(30);
$sheet->getColumnDimension('B')->setWidth(20);

$sheet->mergeCells('C1:E1');
$sheet->mergeCells('F1:H1');
$sheet->mergeCells('I1:K1');
$sheet->mergeCells('L1:N1');
$sheet->mergeCells('O1:Q1');
$sheet->mergeCells('R1:T1');
$sheet->mergeCells('U1:W1');

$sommeM=0;
$sommeM1=0;
$sommeM2=0;
$sommeM3=0;
$sommeM4=0;
$sommeM5=0;
$sommeM6=0;

$sommeCapaM=0;
$sommeCapaM1=0;
$sommeCapaM2=0;
$sommeCapaM3=0;
$sommeCapaM4=0;
$sommeCapaM5=0;
$sommeCapaM6=0;

$couleurVert="a5cb9b";
$couleurRouge="e6bcb3";
$couleurBleu="b3d6e6";

$req="CREATE TEMPORARY TABLE liste_famille (Id INT ,Libelle VARCHAR(255));";
$resultFamille=mysqli_query($bdd,$req);

$req="INSERT INTO liste_famille (Id,Libelle) VALUES (0,'Indéfini');";
$resultFamille=mysqli_query($bdd,$req);

$req="
	INSERT INTO liste_famille
	SELECT DISTINCT Id_Famille AS Id,
	(SELECT Libelle FROM moris_famille WHERE Id=Id_Famille) AS Libelle
	FROM moris_moisprestation_famille
	LEFT JOIN moris_moisprestation
	ON moris_moisprestation_famille.Id_MoisPrestation=moris_moisprestation.Id
	WHERE Id_Famille>0
	AND Id_Prestation=".$_SESSION['MORIS_Prestation']." 
    AND moris_moisprestation.Suppr=0 ";
	if($annee3Mois.'_'.$mois3Mois>$anneeDuJourReel.'_'.$moisDuJourReel){
		$req.="AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))>='".$anneeDuJourReel.'_'.$moisDuJourReel."' ";
	}
	else{
		$req.="AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))>='".$annee3Mois.'_'.$mois3Mois."' ";
	}
		
	$req.="AND Id_Famille IN (".$_SESSION['MORIS_ListeFamilleIndefini'].")
	AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))<='".$annee6Mois.'_'.$mois6Mois."'
	ORDER BY Libelle";

$resultFamille=mysqli_query($bdd,$req);
			
$moisEC=date($_SESSION['MORIS_Annee']."-".$_SESSION['MORIS_Mois']."-1");			
if($_SESSION['FiltreRECORD_NbMois']==12){
	$nbMois9=9;
	$nbMois15=15;
	$nbMois17=17;
	$date_11Mois = date("Y-m-d",strtotime($moisEC." -11 month"));
}
elseif($_SESSION['FiltreRECORD_NbMois']==6){
	$nbMois9=3;
	$nbMois15=8;
	$nbMois17=10;
	$date_11Mois = date("Y-m-d",strtotime($moisEC." -5 month"));
}
elseif($_SESSION['FiltreRECORD_NbMois']==3){
	$nbMois9=0;
	$nbMois15=3;
	$nbMois17=5;
	$date_11Mois = date("Y-m-d",strtotime($moisEC." -2 month"));
}

$annee3Mois=date("Y",strtotime($date_11Mois." +".$nbMois9." month"));
$mois3Mois=date("m",strtotime($date_11Mois." +".$nbMois9." month"));

$annee6Mois=date("Y",strtotime($date_11Mois." +".$nbMois15." month"));
$mois6Mois=date("m",strtotime($date_11Mois." +".$nbMois15." month"));

$annee8Mois=date("Y",strtotime($date_11Mois." +".$nbMois17." month"));
$mois8Mois=date("m",strtotime($date_11Mois." +".$nbMois17." month"));


$anneeDuJour_1=date("Y",strtotime(date('Y-m-1')." -2 month"));
$moisDuJour_1=date("m",strtotime(date('Y-m-1')." -2 month"));

$anneeDuJour=date("Y",strtotime(date('Y-m-1')." -1 month"));
$moisDuJour=date("m",strtotime(date('Y-m-1')." -1 month"));

$anneeDuJour1=date("Y",strtotime(date('Y-m-1')." 0 month"));
$moisDuJour1=date("m",strtotime(date('Y-m-1')." 0 month"));
$anneeDuJour2=date("Y",strtotime(date('Y-m-1')." 1 month"));
$moisDuJour2=date("m",strtotime(date('Y-m-1')." 1 month"));
$anneeDuJour3=date("Y",strtotime(date('Y-m-1')." 2 month"));
$moisDuJour3=date("m",strtotime(date('Y-m-1')." 2 month"));
$anneeDuJour4=date("Y",strtotime(date('Y-m-1')." 3 month"));
$moisDuJour4=date("m",strtotime(date('Y-m-1')." 3 month"));
$anneeDuJour5=date("Y",strtotime(date('Y-m-1')." 4 month"));
$moisDuJour5=date("m",strtotime(date('Y-m-1')." 4 month"));
$anneeDuJour6=date("Y",strtotime(date('Y-m-1')." 5 month"));
$moisDuJour6=date("m",strtotime(date('Y-m-1')." 5 month"));

$req="
INSERT INTO liste_famille
SELECT DISTINCT Id_Famille AS Id,
(SELECT Libelle FROM moris_famille WHERE Id=Id_Famille) AS Libelle
FROM moris_moisprestation_famille
LEFT JOIN moris_moisprestation
ON moris_moisprestation_famille.Id_MoisPrestation=moris_moisprestation.Id
WHERE Id_Famille>0
AND moris_moisprestation.Suppr=0
AND Id_Prestation=".$_SESSION['MORIS_Prestation']." ";
if($annee3Mois.'_'.$mois3Mois>$anneeDuJour_1.'_'.$moisDuJour_1){
	$req.="AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))>='".$anneeDuJour_1.'_'.$moisDuJour_1."' ";
}
else{
	$req.="AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))>='".$annee3Mois.'_'.$mois3Mois."' ";
}
	
$req.="AND Id_Famille IN (".$_SESSION['MORIS_ListeFamilleIndefini'].")
AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))<='".$annee8Mois.'_'.$mois8Mois."'
ORDER BY Libelle";
$resultFamille=mysqli_query($bdd,$req);
	
$req="
	SELECT Id, Libelle 
	FROM liste_famille";											
$resultFamille=mysqli_query($bdd,$req);
$nbFamille=mysqli_num_rows($resultFamille);

$tabPrestation=explode(",",$listePrestation2);

$ligne=3;
if($nbFamille>0){
	while($rowFamille=mysqli_fetch_array($resultFamille)){
		$M=0;
		$M1=0;
		$M2=0;
		$M3=0;
		$M4=0;
		$M5=0;
		$M6=0;
		
		$eM=0;
		$eM1=0;
		$eM2=0;
		$eM3=0;
		$eM4=0;
		$eM5=0;
		$eM6=0;
		
		$CapaM=0;
		$CapaM1=0;
		$CapaM2=0;
		$CapaM3=0;
		$CapaM4=0;
		$CapaM5=0;
		$CapaM6=0;
		
		$CapaeM=0;
		$CapaeM1=0;
		$CapaeM2=0;
		$CapaeM3=0;
		$CapaeM4=0;
		$CapaeM5=0;
		$CapaeM6=0;
		
		$visibleInterne=0;
		$visibleExterne=0;
		if($_SESSION['MORIS_Annee']."_".$_SESSION['MORIS_Mois']>"2022_09"){
			foreach($tabPrestation as $presta){
				if($presta<>-1){
					$laDate=date($_SESSION['MORIS_Annee']."-".$_SESSION['MORIS_Mois']."-01");
					for($i=0;$i<=6;$i++){
						$req2="";
						$anneeEC2=date("Y",strtotime($laDate." +0 month"));
						$moisEC2=date("m",strtotime($laDate." +0 month"));

						$req="SELECT Id,CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) AS AnneeMois
						FROM moris_moisprestation
						WHERE moris_moisprestation.Id_Prestation = ".$presta."
						AND Annee=".$anneeEC2." 
						AND Mois=".$moisEC2."
						AND Suppr=0 	
						AND (
							COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id ),0)>0
							OR 
							COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0)>0
						)
						";
						$result=mysqli_query($bdd,$req);
						$nbResultaMoisPresta=mysqli_num_rows($result);
						if($nbResultaMoisPresta>0){
							$LigneMoisPrestation=mysqli_fetch_array($result);
						}
						else{
							$nbResultaMoisPrestaM1=0;
							if($anneeEC2."_".$moisEC2>=$anneeDuJour."_".$moisDuJour && $anneeEC2."_".$moisEC2<=$anneeDuJour6."_".$moisDuJour6){
								$laDate2=date("Y-m-d",strtotime($laDate." +0 month"));
								$anneeEC3=date("Y",strtotime($laDate2." +0 month"));
								$moisEC3=date("m",strtotime($laDate2." +0 month"));
								
								$annee_1=date("Y",strtotime($laDate2." -1 month"));
								$mois_1=date("m",strtotime($laDate2." -1 month"));
								$annee_2=date("Y",strtotime($laDate2." -2 month"));
								$mois_2=date("m",strtotime($laDate2." -2 month"));
								$annee_3=date("Y",strtotime($laDate2." -3 month"));
								$mois_3=date("m",strtotime($laDate2." -3 month"));
								$annee_4=date("Y",strtotime($laDate2." -4 month"));
								$mois_4=date("m",strtotime($laDate2." -4 month"));
								$annee_5=date("Y",strtotime($laDate2." -5 month"));
								$mois_5=date("m",strtotime($laDate2." -5 month"));
								$annee_6=date("Y",strtotime($laDate2." -6 month"));
								$mois_6=date("m",strtotime($laDate2." -6 month"));
								$annee_7=date("Y",strtotime($laDate2." -7 month"));
								$mois_7=date("m",strtotime($laDate2." -7 month"));
								
								$req="SELECT Id,CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) AS AnneeMois
								FROM moris_moisprestation
								WHERE moris_moisprestation.Id_Prestation = ".$presta."
								AND (
									COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id ),0)>0
									OR 
									COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0)>0
								)
								AND Suppr=0 ";

								if($anneeEC2."_".$moisEC2==$anneeDuJour."_".$moisDuJour){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."') ";}
								elseif($anneeEC2."_".$moisEC2==$anneeDuJour1."_".$moisDuJour1){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."') ";}
								elseif($anneeEC2."_".$moisEC2==$anneeDuJour2."_".$moisDuJour2){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."') ";}
								elseif($anneeEC2."_".$moisEC2==$anneeDuJour3."_".$moisDuJour3){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."') ";}
								elseif($anneeEC2."_".$moisEC2==$anneeDuJour4."_".$moisDuJour4){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."') ";}
								elseif($anneeEC2."_".$moisEC2==$anneeDuJour5."_".$moisDuJour5){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."') ";}
								elseif($anneeEC2."_".$moisEC2==$anneeDuJour6."_".$moisDuJour6){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."','".$annee_7."_".$mois_7."') ";}
								$req.="ORDER BY Annee DESC, Mois DESC ";
								
								$req2=$req;
								$resultM1=mysqli_query($bdd,$req);
								$nbResultaMoisPrestaM1=mysqli_num_rows($resultM1);
								if($nbResultaMoisPrestaM1>0){$LigneMoisPrestation=mysqli_fetch_array($resultM1);}
							}
						}

						$leMoisCharge="-1";
						
						
						if($nbResultaMoisPresta>0){
							$leMoisCharge="";
						}
						elseif($nbResultaMoisPrestaM1>0){
							if($LigneMoisPrestation['AnneeMois']==$annee_1."_".$mois_1){$leMoisCharge="1";}
							elseif($LigneMoisPrestation['AnneeMois']==$annee_2."_".$mois_2){$leMoisCharge="2";}
							elseif($LigneMoisPrestation['AnneeMois']==$annee_3."_".$mois_3){$leMoisCharge="3";}
							elseif($LigneMoisPrestation['AnneeMois']==$annee_4."_".$mois_4){$leMoisCharge="4";}
							elseif($LigneMoisPrestation['AnneeMois']==$annee_5."_".$mois_5){$leMoisCharge="5";}
							elseif($LigneMoisPrestation['AnneeMois']==$annee_6."_".$mois_6){$leMoisCharge="6";}
						}
						if($leMoisCharge<>"-1"){
							//INTERNE
							$req="SELECT M".$leMoisCharge." AS leM, CapaM".$leMoisCharge." AS leCapaM 
								FROM moris_moisprestation_famille 
								WHERE Externe=0 
								AND Id_Famille=".$rowFamille['Id']." 
								AND Id_MoisPrestation=".$LigneMoisPrestation['Id']." ";
							$resultFamilleMois=mysqli_query($bdd,$req);
							$nbFamilleMois=mysqli_num_rows($resultFamilleMois);
							if($nbFamilleMois>0){
								$LigneFamilleMois=mysqli_fetch_array($resultFamilleMois);
								
								if($i==0){$M+=$LigneFamilleMois['leM'];$CapaM+=$LigneFamilleMois['leCapaM'];}
								elseif($i==1){$M1+=$LigneFamilleMois['leM'];$CapaM1+=$LigneFamilleMois['leCapaM'];}
								elseif($i==2){$M2+=$LigneFamilleMois['leM'];$CapaM2+=$LigneFamilleMois['leCapaM'];}
								elseif($i==3){$M3+=$LigneFamilleMois['leM'];$CapaM3+=$LigneFamilleMois['leCapaM'];}
								elseif($i==4){$M4+=$LigneFamilleMois['leM'];$CapaM4+=$LigneFamilleMois['leCapaM'];}
								elseif($i==5){$M5+=$LigneFamilleMois['leM'];$CapaM5+=$LigneFamilleMois['leCapaM'];}
								elseif($i==6){$M6+=$LigneFamilleMois['leM'];$CapaM6+=$LigneFamilleMois['leCapaM'];}

							}

							//EXTERNE
							$req="SELECT M".$leMoisCharge." AS leM, CapaM".$leMoisCharge." AS leCapaM 
								FROM moris_moisprestation_famille 
								WHERE Externe=1
								AND Id_Famille=".$rowFamille['Id']."
								AND Id_MoisPrestation=".$LigneMoisPrestation['Id']." ";
							
							$resultFamilleMois=mysqli_query($bdd,$req);
							$nbFamilleMois=mysqli_num_rows($resultFamilleMois);
							if($nbFamilleMois>0){
								$LigneFamilleMois=mysqli_fetch_array($resultFamilleMois);

								if($i==0){$eM+=$LigneFamilleMois['leM'];$CapaeM+=$LigneFamilleMois['leCapaM'];}
								elseif($i==1){$eM1+=$LigneFamilleMois['leM'];$CapaeM1+=$LigneFamilleMois['leCapaM'];}
								elseif($i==2){$eM2+=$LigneFamilleMois['leM'];$CapaeM2+=$LigneFamilleMois['leCapaM'];}
								elseif($i==3){$eM3+=$LigneFamilleMois['leM'];$CapaeM3+=$LigneFamilleMois['leCapaM'];}
								elseif($i==4){$eM4+=$LigneFamilleMois['leM'];$CapaeM4+=$LigneFamilleMois['leCapaM'];}
								elseif($i==5){$eM5+=$LigneFamilleMois['leM'];$CapaeM5+=$LigneFamilleMois['leCapaM'];}
								elseif($i==6){$eM6+=$LigneFamilleMois['leM'];$CapaeM6+=$LigneFamilleMois['leCapaM'];}
							}
						}
						$laDate=date("Y-m-d",strtotime($laDate." +1 month"));
					}
				}
			}
		}
		
		$sommeM+=unNombreSinon0($M);
		$sommeM1+=unNombreSinon0($M1);
		$sommeM2+=unNombreSinon0($M2);
		$sommeM3+=unNombreSinon0($M3);
		$sommeM4+=unNombreSinon0($M4);
		$sommeM5+=unNombreSinon0($M5);
		$sommeM6+=unNombreSinon0($M6);
		
		$sommeM+=unNombreSinon0($eM);
		$sommeM1+=unNombreSinon0($eM1);
		$sommeM2+=unNombreSinon0($eM2);
		$sommeM3+=unNombreSinon0($eM3);
		$sommeM4+=unNombreSinon0($eM4);
		$sommeM5+=unNombreSinon0($eM5);
		$sommeM6+=unNombreSinon0($eM6);
		
		$sommeCapaM+=unNombreSinon0($CapaM);
		$sommeCapaM1+=unNombreSinon0($CapaM1);
		$sommeCapaM2+=unNombreSinon0($CapaM2);
		$sommeCapaM3+=unNombreSinon0($CapaM3);
		$sommeCapaM4+=unNombreSinon0($CapaM4);
		$sommeCapaM5+=unNombreSinon0($CapaM5);
		$sommeCapaM6+=unNombreSinon0($CapaM6);
		
		$sommeCapaM+=unNombreSinon0($CapaeM);
		$sommeCapaM1+=unNombreSinon0($CapaeM1);
		$sommeCapaM2+=unNombreSinon0($CapaeM2);
		$sommeCapaM3+=unNombreSinon0($CapaeM3);
		$sommeCapaM4+=unNombreSinon0($CapaeM4);
		$sommeCapaM5+=unNombreSinon0($CapaeM5);
		$sommeCapaM6+=unNombreSinon0($CapaeM6);
		
		if((unNombreSinon0($M)+unNombreSinon0($M1)+unNombreSinon0($M2)+unNombreSinon0($M3)+unNombreSinon0($M4)+unNombreSinon0($M5)+unNombreSinon0($M6))>0
		|| (unNombreSinon0($CapaM)+unNombreSinon0($CapaM1)+unNombreSinon0($CapaM2)+unNombreSinon0($CapaM3)+unNombreSinon0($CapaM4)+unNombreSinon0($CapaM5)+unNombreSinon0($CapaM6))>0){
				$visibleInterne=1;
		}
		if((unNombreSinon0($eM)+unNombreSinon0($eM1)+unNombreSinon0($eM2)+unNombreSinon0($eM3)+unNombreSinon0($eM4)+unNombreSinon0($eM5)+unNombreSinon0($eM6))>0
		|| (unNombreSinon0($CapaeM)+unNombreSinon0($CapaeM1)+unNombreSinon0($CapaeM2)+unNombreSinon0($CapaeM3)+unNombreSinon0($CapaeM4)+unNombreSinon0($CapaeM5)+unNombreSinon0($CapaeM6))>0){
				$visibleExterne=1;
		}
		if($visibleInterne==1){
			$sheet->setCellValue('A'.$ligne,utf8_encode($rowFamille['Libelle']));
			if($_SESSION['Langue']=="EN"){
				$sheet->setCellValue('B'.$ligne,utf8_encode('Internal'));
			}
			else{
				$sheet->setCellValue('B'.$ligne,utf8_encode('Interne'));
			}
			$sheet->setCellValue('C'.$ligne,utf8_encode($CapaM-$M));
			$sheet->setCellValue('D'.$ligne,utf8_encode($CapaM));
			$sheet->setCellValue('E'.$ligne,utf8_encode($M));
			if(($M-$CapaM)<0){
				$sheet->getStyle('C'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
			}
			elseif(($M-$CapaM)>0){
				$sheet->getStyle('C'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
			}
			else{
				$sheet->getStyle('C'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
			}
			
			$sheet->setCellValue('F'.$ligne,utf8_encode($CapaM1-$M1));
			$sheet->setCellValue('G'.$ligne,utf8_encode($CapaM1));
			$sheet->setCellValue('H'.$ligne,utf8_encode($M1));
			if(($M1-$CapaM1)<0){
				$sheet->getStyle('F'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
			}
			elseif(($M1-$CapaM1)>0){
				$sheet->getStyle('F'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
			}
			else{
				$sheet->getStyle('F'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
			}
			
			$sheet->setCellValue('I'.$ligne,utf8_encode($CapaM2-$M2));
			$sheet->setCellValue('J'.$ligne,utf8_encode($CapaM2));
			$sheet->setCellValue('K'.$ligne,utf8_encode($M2));
			if(($M2-$CapaM2)<0){
				$sheet->getStyle('I'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
			}
			elseif(($M2-$CapaM2)>0){
				$sheet->getStyle('I'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
			}
			else{
				$sheet->getStyle('I'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
			}
			
			$sheet->setCellValue('L'.$ligne,utf8_encode($CapaM3-$M3));
			$sheet->setCellValue('M'.$ligne,utf8_encode($CapaM3));
			$sheet->setCellValue('N'.$ligne,utf8_encode($M3));
			if(($M3-$CapaM3)<0){
				$sheet->getStyle('L'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
			}
			elseif(($M3-$CapaM3)>0){
				$sheet->getStyle('L'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
			}
			else{
				$sheet->getStyle('L'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
			}
			
			$sheet->setCellValue('O'.$ligne,utf8_encode($CapaM4-$M4));
			$sheet->setCellValue('P'.$ligne,utf8_encode($CapaM4));
			$sheet->setCellValue('Q'.$ligne,utf8_encode($M4));
			if(($M4-$CapaM4)<0){
				$sheet->getStyle('O'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
			}
			elseif(($M4-$CapaM4)>0){
				$sheet->getStyle('O'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
			}
			else{
				$sheet->getStyle('O'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
			}
			
			$sheet->setCellValue('R'.$ligne,utf8_encode($CapaM5-$M5));
			$sheet->setCellValue('S'.$ligne,utf8_encode($CapaM5));
			$sheet->setCellValue('T'.$ligne,utf8_encode($M5));
			if(($M5-$CapaM5)<0){
				$sheet->getStyle('R'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
			}
			elseif(($M5-$CapaM5)>0){
				$sheet->getStyle('R'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
			}
			else{
				$sheet->getStyle('R'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
			}
			
			$sheet->setCellValue('U'.$ligne,utf8_encode($CapaM6-$M6));
			$sheet->setCellValue('V'.$ligne,utf8_encode($CapaM6));
			$sheet->setCellValue('W'.$ligne,utf8_encode($M6));
			if(($M6-$CapaM6)<0){
				$sheet->getStyle('U'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
			}
			elseif(($M6-$CapaM6)>0){
				$sheet->getStyle('U'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
			}
			else{
				$sheet->getStyle('U'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
			}
			$ligne++;
		}

		if($visibleExterne==1){
			$sheet->setCellValue('A'.$ligne,utf8_encode($rowFamille['Libelle']));
			if($_SESSION['Langue']=="EN"){
				$sheet->setCellValue('B'.$ligne,utf8_encode('External'));
			}
			else{
				$sheet->setCellValue('B'.$ligne,utf8_encode('Externe'));
			}
			$sheet->setCellValue('C'.$ligne,utf8_encode($CapaeM-$eM));
			$sheet->setCellValue('D'.$ligne,utf8_encode($CapaeM));
			$sheet->setCellValue('E'.$ligne,utf8_encode($eM));
			if(($eM-$CapaeM)<0){
				$sheet->getStyle('C'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
			}
			elseif(($eM-$CapaeM)>0){
				$sheet->getStyle('C'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
			}
			else{
				$sheet->getStyle('C'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
			}
			
			$sheet->setCellValue('F'.$ligne,utf8_encode($CapaeM1-$eM1));
			$sheet->setCellValue('G'.$ligne,utf8_encode($CapaeM1));
			$sheet->setCellValue('H'.$ligne,utf8_encode($eM1));
			if(($eM1-$CapaeM1)<0){
				$sheet->getStyle('F'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
			}
			elseif(($eM1-$CapaeM1)>0){
				$sheet->getStyle('F'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
			}
			else{
				$sheet->getStyle('F'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
			}
			
			$sheet->setCellValue('I'.$ligne,utf8_encode($CapaeM2-$eM2));
			$sheet->setCellValue('J'.$ligne,utf8_encode($CapaeM2));
			$sheet->setCellValue('K'.$ligne,utf8_encode($eM2));
			if(($eM2-$CapaeM2)<0){
				$sheet->getStyle('I'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
			}
			elseif(($eM2-$CapaeM2)>0){
				$sheet->getStyle('I'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
			}
			else{
				$sheet->getStyle('I'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
			}
			
			$sheet->setCellValue('L'.$ligne,utf8_encode($CapaeM3-$eM3));
			$sheet->setCellValue('M'.$ligne,utf8_encode($CapaeM3));
			$sheet->setCellValue('N'.$ligne,utf8_encode($eM3));
			if(($eM3-$CapaeM3)<0){
				$sheet->getStyle('L'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
			}
			elseif(($eM3-$CapaeM3)>0){
				$sheet->getStyle('L'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
			}
			else{
				$sheet->getStyle('L'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
			}
			
			$sheet->setCellValue('O'.$ligne,utf8_encode($CapaeM4-$eM4));
			$sheet->setCellValue('P'.$ligne,utf8_encode($CapaeM4));
			$sheet->setCellValue('Q'.$ligne,utf8_encode($eM4));
			if(($eM4-$CapaeM4)<0){
				$sheet->getStyle('O'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
			}
			elseif(($eM4-$CapaeM4)>0){
				$sheet->getStyle('O'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
			}
			else{
				$sheet->getStyle('O'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
			}
			
			$sheet->setCellValue('R'.$ligne,utf8_encode($CapaeM5-$eM5));
			$sheet->setCellValue('S'.$ligne,utf8_encode($CapaeM5));
			$sheet->setCellValue('T'.$ligne,utf8_encode($eM5));
			if(($eM5-$CapaeM5)<0){
				$sheet->getStyle('R'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
			}
			elseif(($eM5-$CapaeM5)>0){
				$sheet->getStyle('R'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
			}
			else{
				$sheet->getStyle('R'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
			}
			
			$sheet->setCellValue('U'.$ligne,utf8_encode($CapaeM6-$eM6));
			$sheet->setCellValue('V'.$ligne,utf8_encode($CapaeM6));
			$sheet->setCellValue('W'.$ligne,utf8_encode($eM6));
			if(($eM6-$CapaeM6)<0){
				$sheet->getStyle('U'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
			}
			elseif(($eM6-$CapaeM6)>0){
				$sheet->getStyle('U'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
			}
			else{
				$sheet->getStyle('U'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
			}
			$ligne++;
		}
	}
}

$sheet->setCellValue('A'.$ligne,utf8_encode('Total'));

$sheet->setCellValue('C'.$ligne,utf8_encode($sommeCapaM-$sommeM));
$sheet->setCellValue('D'.$ligne,utf8_encode($sommeCapaM));
$sheet->setCellValue('E'.$ligne,utf8_encode($sommeM));
if(($sommeM-$sommeCapaM)<0){
	$sheet->getStyle('C'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
}
elseif(($sommeM-$sommeCapaM)>0){
	$sheet->getStyle('C'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
}
else{
	$sheet->getStyle('C'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
}

$sheet->setCellValue('F'.$ligne,utf8_encode($sommeCapaM1-$sommeM1));
$sheet->setCellValue('G'.$ligne,utf8_encode($sommeCapaM1));
$sheet->setCellValue('H'.$ligne,utf8_encode($sommeM1));
if(($sommeM1-$sommeCapaM1)<0){
	$sheet->getStyle('F'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
}
elseif(($sommeM1-$sommeCapaM1)>0){
	$sheet->getStyle('F'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
}
else{
	$sheet->getStyle('F'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
}

$sheet->setCellValue('I'.$ligne,utf8_encode($sommeCapaM2-$sommeM2));
$sheet->setCellValue('J'.$ligne,utf8_encode($sommeCapaM2));
$sheet->setCellValue('K'.$ligne,utf8_encode($sommeM2));
if(($sommeM2-$sommeCapaM2)<0){
	$sheet->getStyle('I'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
}
elseif(($sommeM2-$sommeCapaM2)>0){
	$sheet->getStyle('I'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
}
else{
	$sheet->getStyle('I'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
}

$sheet->setCellValue('L'.$ligne,utf8_encode($sommeCapaM3-$sommeM3));
$sheet->setCellValue('M'.$ligne,utf8_encode($sommeCapaM3));
$sheet->setCellValue('N'.$ligne,utf8_encode($sommeM3));
if(($sommeM3-$sommeCapaM3)<0){
	$sheet->getStyle('L'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
}
elseif(($sommeM3-$sommeCapaM3)>0){
	$sheet->getStyle('L'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
}
else{
	$sheet->getStyle('L'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
}

$sheet->setCellValue('O'.$ligne,utf8_encode($sommeCapaM4-$sommeM4));
$sheet->setCellValue('P'.$ligne,utf8_encode($sommeCapaM4));
$sheet->setCellValue('Q'.$ligne,utf8_encode($sommeM4));
if(($sommeM4-$sommeCapaM4)<0){
	$sheet->getStyle('O'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
}
elseif(($sommeM4-$sommeCapaM4)>0){
	$sheet->getStyle('O'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
}
else{
	$sheet->getStyle('O'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
}

$sheet->setCellValue('R'.$ligne,utf8_encode($sommeCapaM5-$sommeM5));
$sheet->setCellValue('S'.$ligne,utf8_encode($sommeCapaM5));
$sheet->setCellValue('T'.$ligne,utf8_encode($sommeM5));
if(($sommeM5-$sommeCapaM5)<0){
	$sheet->getStyle('R'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
}
elseif(($sommeM5-$sommeCapaM5)>0){
	$sheet->getStyle('R'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
}
else{
	$sheet->getStyle('R'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
}

$sheet->setCellValue('U'.$ligne,utf8_encode($sommeCapaM6-$sommeM6));
$sheet->setCellValue('V'.$ligne,utf8_encode($sommeCapaM6));
$sheet->setCellValue('W'.$ligne,utf8_encode($sommeM6));
if(($sommeM6-$sommeCapaM6)<0){
	$sheet->getStyle('U'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurBleu))));
}
elseif(($sommeM6-$sommeCapaM6)>0){
	$sheet->getStyle('U'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurRouge))));
}
else{
	$sheet->getStyle('U'.$ligne)->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleurVert))));
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
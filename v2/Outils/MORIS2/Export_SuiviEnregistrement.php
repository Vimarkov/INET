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

//Ligne En-tete
if($_SESSION['Langue']=="FR"){
	$sheet->setCellValue('A1',utf8_encode("Unité d'exploitation"));
	$sheet->setCellValue('B1',utf8_encode('Prestation'));
	$sheet->setCellValue('C1',utf8_encode('Données enregistrées'));
	$sheet->setCellValue('D1',utf8_encode('Données verrouillées'));
}
else{
	$sheet->setCellValue('A1',utf8_encode('Operating unit'));
	$sheet->setCellValue('B1',utf8_encode('Site'));
	$sheet->setCellValue('C1',utf8_encode('Recorded data'));
	$sheet->setCellValue('D1',utf8_encode('Locked data'));
}
$sheet->setCellValue('E1',utf8_encode('CHARGE / CAPA'));
$sheet->setCellValue('F1',utf8_encode('PRODUCTIVITE'));
$sheet->setCellValue('G1',utf8_encode('OTD'));
$sheet->setCellValue('H1',utf8_encode('OQD'));
$sheet->setCellValue('I1',utf8_encode('MANAGEMENT'));
$sheet->setCellValue('J1',utf8_encode('COMPETENCES (POLYV)'));
$sheet->setCellValue('K1',utf8_encode('COMPETENCES (QUALIF)'));
$sheet->setCellValue('L1',utf8_encode('SECURITE (PDV)'));
$sheet->setCellValue('M1',utf8_encode('SECURITE (AT)'));
$sheet->setCellValue('N1',utf8_encode('PRM & SATIS (PRM)'));
$sheet->setCellValue('O1',utf8_encode('PRM & SATIS (SATIS)'));
$sheet->setCellValue('P1',utf8_encode('NC/RC'));
	
	$sheet->getStyle('A1:P1')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>'EEEEEE'))));

	$sheet->getDefaultColumnDimension()->setWidth(20);

$req="SELECT Id
		FROM new_competences_personne_poste_plateforme
		WHERE Id_Personne=".$_SESSION['Id_Personne']."
		AND Id_Poste IN (9)";
$resultRespSG=mysqli_query($bdd,$req);
$nbRespSG=mysqli_num_rows($resultRespSG);

$req="SELECT DISTINCT new_competences_personne_poste_prestation.Id_Personne,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
	FROM new_competences_personne_poste_prestation
	LEFT JOIN new_competences_prestation
	ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
	WHERE new_competences_prestation.UtiliseMORIS=1
	AND (SELECT COUNT(Id) 
	FROM new_competences_personne_poste_prestation 
	WHERE Id_Personne=".$_SESSION['Id_Personne']."
	AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id 
	AND Id_Poste IN (2,3,4)
	)>0
	";
if($_SESSION['FiltreRECORD_Prestation']<>""){
	$req.="AND new_competences_prestation.Id IN (".$_SESSION['FiltreRECORD_Prestation'].") ";
}
$req.="ORDER BY (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne);";
$resultRP=mysqli_query($bdd,$req);
$nbRP=mysqli_num_rows($resultRP);

$req="SELECT Id,Libelle,Id_Plateforme,PlanPreventionADesactivite,ChargeADesactive,ProductiviteADesactive,PolyvalenceADesactive,
	OTDOQDADesactive,ManagementADesactive,CompetenceADesactive,SecuriteADesactive,PRMADesactive,NCADesactive,
	(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
	(SELECT Id FROM moris_moisprestation WHERE moris_moisprestation.Id_Prestation=new_competences_prestation.Id 
		AND Annee=".$_SESSION['MORIS_Annee2']." 
		AND Mois=".$_SESSION['MORIS_Mois2']."
		AND Suppr=0 LIMIT 1) AS Enregistre,
	(SELECT Verouillage
		FROM moris_moisprestation
		WHERE moris_moisprestation.Id_Prestation=new_competences_prestation.Id 
		AND Annee=".$_SESSION['MORIS_Annee2']." 
		AND Mois=".$_SESSION['MORIS_Mois2']."
		AND Suppr=0  LIMIT 1) AS Verouillage
	FROM new_competences_prestation
	WHERE new_competences_prestation.UtiliseMORIS=1
	AND (
		SELECT COUNT(DateDebut) 
		FROM moris_datesuivi 
		WHERE Id_Prestation=new_competences_prestation.Id
		AND Suppr=0 
		AND CONCAT(YEAR(DateDebut),'_',IF(MONTH(DateDebut)<10,CONCAT('0',MONTH(DateDebut)),MONTH(DateDebut)))<='".date($_SESSION['MORIS_Annee2'].'_'.$_SESSION['MORIS_Mois2'])."'
		AND (CONCAT(YEAR(DateFin),'_',IF(MONTH(DateFin)<10,CONCAT('0',MONTH(DateFin)),MONTH(DateFin)))>='".date($_SESSION['MORIS_Annee2'].'_'.$_SESSION['MORIS_Mois2'])."' OR DateFin<='0001-01-01')
	)>0
	";
if($_SESSION['FiltreRHRepartitionAAA_Plateforme']<>"0"){
	$req.="AND Id_Plateforme=".$_SESSION['FiltreRHRepartitionAAA_Plateforme']." ";
}
if($_SESSION['FiltreRECORD_VoirTout']=="" && ($nbRP>0 || $nbRespSG>0)){
	$req.="AND ((SELECT COUNT(Id) 
		FROM new_competences_personne_poste_prestation 
		WHERE Id_Personne=".$_SESSION['Id_Personne']."
		AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id 
		AND Id_Poste IN (2,3,4)
		)>0 
		OR 
		(SELECT COUNT(Id)
		FROM new_competences_personne_poste_plateforme
		WHERE Id_Personne=".$_SESSION['Id_Personne']."
		AND new_competences_personne_poste_plateforme.Id_Plateforme=new_competences_prestation.Id_Plateforme
		AND Id_Poste IN (9))>0
		)";
}
$req.="ORDER BY Plateforme,Libelle;";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);

$rouge="f91532";
$vert="68bade";
$orange="faa04c";

if ($nbResulta>0){
	$couleur="EEEEEE";
	$ligne=2;
	while($row=mysqli_fetch_array($result)){
		$enregistre="X";
		$verrouille="X";
		$charge="";
		$productivite="";
		$otd="";
		$oqd="";
		$management="";
		$polyv="";
		$qualif="";
		$pdv="";
		$at="";
		$prm="";
		$satis="";
		$nc="";
		
		if($row['Enregistre']>0){
			$enregistre="V";
		}
		else{
			$charge="X";
			$productivite="X";
			$otd="X";
			$oqd="X";
			$management="X";
			$polyv="X";
			$qualif="X";
			$pdv="X";
			$at="X";
			$prm="X";
			$satis="X";
			$nc="N/A";
		}
		if($row['Verouillage']==1){
			$verrouille="V";
		}
		
		if($row['ChargeADesactive']==1){
			$charge="N/A";
		}
		if($row['ProductiviteADesactive']==1){
			$productivite="N/A";
		}
		if($row['OTDOQDADesactive']==1){
			$otd="N/A";
			$oqd="N/A";
		}
		if($row['ManagementADesactive']==1){
			$management="N/A";
		}
		if($row['CompetenceADesactive']==1 || $row['PolyvalenceADesactive']==1){
			$polyv="N/A";
		}
		if($row['CompetenceADesactive']==1){
			$qualif="N/A";
		}	
		if($row['PRMADesactive']==1){
			$prm="N/A";
			$satis="N/A";
		}		
		if($row['SecuriteADesactive']==1 || ($row['SecuriteADesactive']==0 && $_SESSION['MORIS_Annee2'].'_'.$_SESSION['MORIS_Mois2']>"2023_06")){
			$at="N/A";
		}
		if($row['NCADesactive']==1){
			$nc="N/A";
		}
		if($row['PlanPreventionADesactivite']>0){
			$pdv="N/A";
		}
		
		if($row['Enregistre']>0 || $row['Verouillage']==1){
			$charge="X";
			$productivite="X";
			$otd="X";
			$oqd="X";
			$management="X";
			$polyv="X";
			$qualif="X";
			$pdv="X";
			$at="X";
			$prm="X";
			$satis="X";
			$nc="N/A";
			
			if($row['PlanPreventionADesactivite']==0){
				$req="SELECT RefPdp,DateValidite 
					FROM moris_pdp 
					WHERE moris_pdp.Id_Prestation=".$row['Id']."
					ORDER BY Annee DESC, Mois DESC
					";
				$result2=mysqli_query($bdd,$req);
				$nbResulta2=mysqli_num_rows($result2);
				if($nbResulta2>0){
					$row2=mysqli_fetch_array($result2);
					
					if($row2['RefPdp']<>"" && $row2['DateValidite']>"0001-01-01"){$pdv="V";}
					elseif($row2['RefPdp']=="" && $row2['DateValidite']>"0001-01-01"){$pdv="P";}
					elseif($row2['RefPdp']<>"" && $row2['DateValidite']<="0001-01-01"){$pdv="P";}
				}
			}
			else{
				$pdv="N/A";
			}
			
			$req="SELECT Id,
				InterneCurrent+COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 ),0) AS InterneCurrent,
				SubContractorCurrent+COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 ),0) AS SubContractorCurrent,
				M1,M2,M3,M4,M5,M6,BesoinEffectif,
				TempsAlloue,TempsPasse,TempsObjectif,CommentaireProductivite,ChargeDesactive,ProductiviteDesactive,
				ObjectifClientOTD,NbLivrableConformeOTD,NbLivrableToleranceOTD,NbRetourClientOTD,CauseOTD,ActionOTD,
				ObjectifClientOQD,NbLivrableConformeOQD,NbLivrableToleranceOQD,NbRetourClientOQD,CauseOQD,ActionOQD,
				ModeCalculOTD,ModeCalculOQD,
				TendanceManagement,EvenementManagement,PasAT,PasNC,PasOTD,PasOQD,
				NbXTableauPolyvalence,NbLTableauPolyvalence,NbMonoCompetence,TauxQualif,CommentairePlanActionFormation,
				DerniereDatePRM,DerniereDateEvaluation,PeriodicitePRM,DateEnvoiDemandeSatisfaction,
				FormatAT,
				EvaluationQualite,EvaluationDelais,EvaluationCompetencePersonnel,EvaluationAutonomie,EvaluationAnticipation,EvaluationCommunication,
				PieceJointeSQCDPF,PieceJointeDernierePRM,PieceJointeSatisfactionPRM,PasActivite 
				FROM moris_moisprestation 
				WHERE moris_moisprestation.Id_Prestation=".$row['Id']."
					AND Annee=".$_SESSION['MORIS_Annee2']." 
					AND Mois=".$_SESSION['MORIS_Mois2']."
					AND Suppr=0 LIMIT 1
				";
			$result2=mysqli_query($bdd,$req);
			$nbResulta2=mysqli_num_rows($result2);
			if($nbResulta2>0){
				$row2=mysqli_fetch_array($result2);
				
				if($row['ChargeADesactive']==1){
					$charge="N/A";
				}
				else{
					if($row2['InterneCurrent']>0 || $row2['SubContractorCurrent']>0 || $row2['PasActivite']==1){$charge="V";}
				}
				
				if($row['ProductiviteADesactive']==1){
					$productivite="N/A";
				}
				else{
					if($row2['TempsAlloue']>0 || $row2['TempsPasse']>0 || $row2['TempsObjectif']>0 || $row2['PasActivite']==1){$productivite="V";}
				}
				
				if($row['OTDOQDADesactive']==1){
					$otd="N/A";
				}
				else{
					if($row2['PasOTD']==1){
						$otd="V";
					}
					else{
						if($row2['PasActivite']==1){
							$otd="V";
						}
						else{
							if((($row2['NbLivrableConformeOTD']>0 || $row2['NbLivrableToleranceOTD']>0 || $row2['NbRetourClientOTD']>0) && $row2['ObjectifClientOTD']>0)){
								$ratio=round(($row2['NbLivrableConformeOTD']/($row2['NbLivrableConformeOTD']+$row2['NbLivrableToleranceOTD']+$row2['NbRetourClientOTD']))*100,2);
								if(($ratio>=$row2['ObjectifClientOTD']) || ($ratio<$row2['ObjectifClientOTD'] && $row2['CauseOTD']<>"" && $row2['ActionOTD']<>"")){
									$otd="V";
								}
								else{
									$otd="P";
								}
							}
						}
					}
				}
				
				if($row['OTDOQDADesactive']==1){
					$oqd="N/A";
				}
				else{
					if($row2['PasOQD']==1){
						$oqd="V";
					}
					else{
						if($row2['PasActivite']==1){
							$oqd="V";
						}
						else{
							if((($row2['NbLivrableConformeOQD']>0 || $row2['NbLivrableToleranceOQD']>0 || $row2['NbRetourClientOQD']>0) && $row2['ObjectifClientOQD']>0)){
								$ratio=round(($row2['NbLivrableConformeOQD']/($row2['NbLivrableConformeOQD']+$row2['NbLivrableToleranceOQD']+$row2['NbRetourClientOQD']))*100,2);
								if(($ratio>=$row2['ObjectifClientOQD']) || ($ratio<$row2['ObjectifClientOQD'] && $row2['CauseOQD']<>"" && $row2['ActionOQD']<>"")){
									$oqd="V";
								}
								else{
									$oqd="P";
								}
							}
						}
					}
				}
				
				if($row['ManagementADesactive']==1){
					$management="N/A";
				}
				else{
					if($row2['TendanceManagement']==0 || ($row2['EvenementManagement']<>"" && $row2['TendanceManagement']>0) || $row2['PasActivite']==1){
						$management="V";
					}
				}
				
				if($row['CompetenceADesactive']==1 || $row['PolyvalenceADesactive']==1){
					$polyv="N/A";
				}
				else{
					if($row2['NbXTableauPolyvalence']>0 || $row2['NbLTableauPolyvalence']>0 || $row2['PasActivite']==1){
						$polyv="V";
					}
				}
				
				if($row['CompetenceADesactive']==1){
					$qualif="N/A";
				}
				else{
					if($row2['TauxQualif']>0 || $row2['PasActivite']==1){
						$qualif="V";
					}
				}
				
				if($row['PRMADesactive']==1){
					$prm="N/A";
					$satis="N/A";
				}
				else{
					if($row2['PeriodicitePRM']=="Pas de PRM" || $row2['PasActivite']==1){
						$prm="V";
					}
					elseif($row2['DerniereDatePRM']>0 && $row2['PeriodicitePRM']<>""){
						$prm="V";
					}
					
					if($row2['DateEnvoiDemandeSatisfaction']>"0001-01-01" || $row2['PasActivite']==1){
						$satis="V";
					}
				}
				
				if($row['SecuriteADesactive']==1 || ($row['SecuriteADesactive']==0 && $_SESSION['MORIS_Annee2'].'_'.$_SESSION['MORIS_Mois2']>"2023_06")){
					$at="N/A";
				}
				else{
					if($row2['PasAT']==1 || $row2['PasActivite']==1){
						$at="V";
					}
					else{
						$req="SELECT Id
							FROM moris_moisprestation_securite 
							WHERE Suppr=0 
							AND Id_MoisPrestation=".$row2['Id']." ";
						$resultAT=mysqli_query($bdd,$req);
						$nbResultaAT=mysqli_num_rows($resultAT);
						if($nbResultaAT>0){
							$at="V";
						}
					}
				}
				
				if($row['NCADesactive']==1){
					$nc="N/A";
				}
				else{
					if($row2['PasNC']==1 || $row2['PasActivite']==1){
						$nc="V";
					}
					else{
						$req="SELECT Id
							FROM moris_moisprestation_ncdac 
							WHERE Suppr=0 
							AND NC_DAC<>'DAC'
							AND Id_MoisPrestation=".$row2['Id']." ";
						$resultNC=mysqli_query($bdd,$req);
						$nbResultaNC=mysqli_num_rows($resultNC);
						if($nbResultaNC>0){
							$nc="V";
						}
					}
				}
				
				if($row2['PasActivite']==1){
					$pdv="V";
				}
			}
		}
		
		$presta=substr($row['Libelle'],0,strpos($row['Libelle']," "));
	
		$sheet->setCellValue('A'.$ligne,utf8_encode($row['Plateforme']));
		$sheet->setCellValue('B'.$ligne,utf8_encode($presta));
		$sheet->setCellValue('C'.$ligne,utf8_encode($enregistre));
		if($enregistre=="X"){$sheet->getStyle('C'.$ligne)->getFont()->getColor()->setRGB($rouge);}
		elseif($enregistre=="V"){$sheet->getStyle('C'.$ligne)->getFont()->getColor()->setRGB($vert);}
		
		$sheet->setCellValue('D'.$ligne,utf8_encode($verrouille));
		if($verrouille=="X"){$sheet->getStyle('D'.$ligne)->getFont()->getColor()->setRGB($rouge);}
		elseif($verrouille=="V"){$sheet->getStyle('D'.$ligne)->getFont()->getColor()->setRGB($vert);}
		
		$sheet->setCellValue('E'.$ligne,utf8_encode($charge));
		if($charge=="X"){$sheet->getStyle('E'.$ligne)->getFont()->getColor()->setRGB($rouge);}
		elseif($charge=="V"){$sheet->getStyle('E'.$ligne)->getFont()->getColor()->setRGB($vert);}
		
		$sheet->setCellValue('F'.$ligne,utf8_encode($productivite));
		if($productivite=="X"){$sheet->getStyle('F'.$ligne)->getFont()->getColor()->setRGB($rouge);}
		elseif($productivite=="V"){$sheet->getStyle('F'.$ligne)->getFont()->getColor()->setRGB($vert);}
		
		$sheet->setCellValue('G'.$ligne,utf8_encode($otd));
		if($otd=="X"){$sheet->getStyle('G'.$ligne)->getFont()->getColor()->setRGB($rouge);}
		elseif($otd=="V"){$sheet->getStyle('G'.$ligne)->getFont()->getColor()->setRGB($vert);}
		elseif($otd=="P"){$sheet->getStyle('G'.$ligne)->getFont()->getColor()->setRGB($orange);}
		
		$sheet->setCellValue('H'.$ligne,utf8_encode($oqd));
		if($oqd=="X"){$sheet->getStyle('H'.$ligne)->getFont()->getColor()->setRGB($rouge);}
		elseif($oqd=="V"){$sheet->getStyle('H'.$ligne)->getFont()->getColor()->setRGB($vert);}
		elseif($oqd=="P"){$sheet->getStyle('H'.$ligne)->getFont()->getColor()->setRGB($orange);}
		
		$sheet->setCellValue('I'.$ligne,utf8_encode($management));
		if($management=="X"){$sheet->getStyle('I'.$ligne)->getFont()->getColor()->setRGB($rouge);}
		elseif($management=="V"){$sheet->getStyle('I'.$ligne)->getFont()->getColor()->setRGB($vert);}
		
		$sheet->setCellValue('J'.$ligne,utf8_encode($polyv));
		if($polyv=="X"){$sheet->getStyle('J'.$ligne)->getFont()->getColor()->setRGB($rouge);}
		elseif($polyv=="V"){$sheet->getStyle('J'.$ligne)->getFont()->getColor()->setRGB($vert);}
		
		$sheet->setCellValue('K'.$ligne,utf8_encode($qualif));
		if($qualif=="X"){$sheet->getStyle('K'.$ligne)->getFont()->getColor()->setRGB($rouge);}
		elseif($qualif=="V"){$sheet->getStyle('K'.$ligne)->getFont()->getColor()->setRGB($vert);}
		
		$sheet->setCellValue('L'.$ligne,utf8_encode($pdv));
		if($pdv=="X"){$sheet->getStyle('L'.$ligne)->getFont()->getColor()->setRGB($rouge);}
		elseif($pdv=="V"){$sheet->getStyle('L'.$ligne)->getFont()->getColor()->setRGB($vert);}
		elseif($pdv=="P"){$sheet->getStyle('L'.$ligne)->getFont()->getColor()->setRGB($orange);}
		
		$sheet->setCellValue('M'.$ligne,utf8_encode($at));
		if($at=="X"){$sheet->getStyle('M'.$ligne)->getFont()->getColor()->setRGB($rouge);}
		elseif($at=="V"){$sheet->getStyle('M'.$ligne)->getFont()->getColor()->setRGB($vert);}
		
		$sheet->setCellValue('N'.$ligne,utf8_encode($prm));
		if($prm=="X"){$sheet->getStyle('N'.$ligne)->getFont()->getColor()->setRGB($rouge);}
		elseif($prm=="V"){$sheet->getStyle('N'.$ligne)->getFont()->getColor()->setRGB($vert);}
		elseif($prm=="P"){$sheet->getStyle('N'.$ligne)->getFont()->getColor()->setRGB($orange);}
		
		$sheet->setCellValue('O'.$ligne,utf8_encode($satis));
		if($satis=="X"){$sheet->getStyle('O'.$ligne)->getFont()->getColor()->setRGB($rouge);}
		elseif($satis=="V"){$sheet->getStyle('O'.$ligne)->getFont()->getColor()->setRGB($vert);}
		
		$sheet->setCellValue('P'.$ligne,utf8_encode($nc));
		if($nc=="X"){$sheet->getStyle('P'.$ligne)->getFont()->getColor()->setRGB($rouge);}
		elseif($nc=="V"){$sheet->getStyle('P'.$ligne)->getFont()->getColor()->setRGB($vert);}
		
		$sheet->getStyle('A'.$ligne.':P'.$ligne.'')->applyFromArray(array('fill'=>array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'color'=>array('argb'=>$couleur))));
		$ligne++;

		if($couleur=="FFFFFF"){$couleur="EEEEEE";}
		else{$couleur="FFFFFF";}
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
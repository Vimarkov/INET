<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

$envoi=$_GET['Parametre'];

if($_SESSION["Langue"]=="FR"){
	$MoisLettre = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
}
else
{
	$MoisLettre = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
}
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
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
$listePresta="";
$tabPresta = array();
$i=0;
if ($nbResulta>0){
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
			
			$req="SELECT Id,
				InterneCurrent+COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 ),0) AS InterneCurrent,
				SubContractorCurrent+COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 ),0) AS SubContractorCurrent,
				M1,M2,M3,M4,M5,M6,BesoinEffectif,PasActivite,
				TempsAlloue,TempsPasse,TempsObjectif,CommentaireProductivite,ChargeDesactive,ProductiviteDesactive,
				ObjectifClientOTD,NbLivrableConformeOTD,NbLivrableToleranceOTD,NbRetourClientOTD,CauseOTD,ActionOTD,
				ObjectifClientOQD,NbLivrableConformeOQD,NbLivrableToleranceOQD,NbRetourClientOQD,CauseOQD,ActionOQD,
				ModeCalculOTD,ModeCalculOQD,
				TendanceManagement,EvenementManagement,PasAT,PasNC,PasOTD,PasOQD,
				NbXTableauPolyvalence,NbLTableauPolyvalence,NbMonoCompetence,TauxQualif,CommentairePlanActionFormation,
				DerniereDatePRM,DerniereDateEvaluation,PeriodicitePRM,DateEnvoiDemandeSatisfaction,
				FormatAT,
				EvaluationQualite,EvaluationDelais,EvaluationCompetencePersonnel,EvaluationAutonomie,EvaluationAnticipation,EvaluationCommunication,
				PieceJointeSQCDPF,PieceJointeDernierePRM,PieceJointeSatisfactionPRM 
				FROM moris_moisprestation 
				WHERE moris_moisprestation.Id_Prestation=".$row['Id']."
					AND Annee=".$_SESSION['MORIS_Annee2']." 
					AND Mois=".$_SESSION['MORIS_Mois2']."
					AND Suppr=0 LIMIT 1
				";
			$result2=mysqli_query($bdd,$req);
			$nbResulta2=mysqli_num_rows($result2);
			
			$annee_M_1=$_SESSION['MORIS_Annee2'];
			$mois_M_1=$_SESSION['MORIS_Mois2']-1;
			if($mois_M_1==0){
				$annee_M_1=$annee-1;
				$mois_M_1=12;
			}

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
					if($row2['TendanceManagement']>0 && $row2['EvenementManagement']==""){
						$management="P";
					}
					elseif($row2['TendanceManagement']==0 || ($row2['TendanceManagement']>0 && $row2['EvenementManagement']<>"") || $row2['PasActivite']==1){
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
					if($row2['PasNC']==1  || $row2['PasActivite']==1){
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
			}
			
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
			
			if($row2['PasActivite']==1){
				$pdv="V";
			}
		}
		
		$presta=substr($row['Libelle'],0,strpos($row['Libelle']," "));
		if(($envoi=="N2" && ($enregistre=="X" || $charge=="X" || $productivite=="X" || $otd=="X" || $oqd=="X" || $management=="X" || $polyv=="X" || $qualif=="X" || $pdv=="X" || $at=="X" || $prm=="X" || $satis=="X" || $nc=="X"))
		|| ($envoi=="RespProjet" && ($enregistre=="X" || $verrouille=="X" || $charge=="X" || $productivite=="X" || $otd=="X" || $oqd=="X" || $management=="X" || $polyv=="X" || $qualif=="X" || $pdv=="X" || $at=="X" || $prm=="X" || $satis=="X" || $nc=="X"))
		){
			if($listePresta<>""){$listePresta.=",";}
			$listePresta.= $row['Id'];
			if($enregistre=="V"){$enregistre="";}
			if($verrouille=="V"){$verrouille="";}
			if($charge=="V" || $charge=="N/A"){$charge="";}
			if($productivite=="V" || $productivite=="N/A"){$productivite="";}
			if($otd=="V" || $otd=="N/A"){$otd="";}
			if($oqd=="V" || $oqd=="N/A"){$oqd="";}
			if($management=="V" || $management=="N/A"){$management="";}
			if($polyv=="V" || $polyv=="N/A"){$polyv="";}
			if($qualif=="V" || $qualif=="N/A"){$qualif="";}
			if($pdv=="V" || $pdv=="N/A"){$pdv="";}
			if($at=="V" || $at=="N/A"){$at="";}
			if($prm=="V" || $prm=="N/A"){$prm="";}
			if($satis=="V" || $satis=="N/A"){$satis="";}
			if($nc=="V" || $nc=="N/A"){$nc="";}
			
			$reqM1="SELECT Id,
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
				PieceJointeSQCDPF,PieceJointeDernierePRM,PieceJointeSatisfactionPRM 
				FROM moris_moisprestation 
				WHERE moris_moisprestation.Id_Prestation=".$row['Id']."
					AND Annee=".$annee_M_1." 
					AND Mois=".$mois_M_1."
					AND Suppr=0 LIMIT 1
				";
			$resultM1=mysqli_query($bdd,$reqM1);
			$nbResultaMoisPrestaM1=mysqli_num_rows($resultM1);
			
			if($nbResultaMoisPrestaM1>0){
				$rowM1=mysqli_fetch_array($resultM1);
				$charge2="X";
				$productivite2="X";
				$otd2="X";
				$oqd2="X";
				$management2="X";
				$polyv2="X";
				$qualif2="X";
				$pdv2="X";
				$at2="X";
				$prm2="X";
				$satis2="X";
				$nc2="X";
				
				if($row['ChargeADesactive']==1){
					$charge2="N/A";
				}
				else{
					if($rowM1['InterneCurrent']>0 || $rowM1['SubContractorCurrent']>0){$charge2="V";}
				}
				
				if($row['ProductiviteADesactive']==1){
					$productivite2="N/A";
				}
				else{
					if($rowM1['TempsAlloue']>0 || $rowM1['TempsPasse']>0 || $rowM1['TempsObjectif']>0){$productivite2="V";}
				}
				
				if($row['OTDOQDADesactive']==1){
					$otd2="N/A";
				}
				else{
					if($rowM1['PasOTD']==1){
						$otd2="V";
					}
					else{
						if((($rowM1['NbLivrableConformeOTD']>0 || $rowM1['NbLivrableToleranceOTD']>0 || $rowM1['NbRetourClientOTD']>0) && $rowM1['ObjectifClientOTD']>0)){
							$ratio=round(($rowM1['NbLivrableConformeOTD']/($rowM1['NbLivrableConformeOTD']+$rowM1['NbLivrableToleranceOTD']+$rowM1['NbRetourClientOTD']))*100,2);
							if(($ratio>=$rowM1['ObjectifClientOTD']) || ($ratio<$rowM1['ObjectifClientOTD'] && $rowM1['CauseOTD']<>"" && $rowM1['ActionOTD']<>"")){
								$otd2="V";
							}
							else{
								$otd2="P";
							}
						}
					}
				}
				
				if($row['OTDOQDADesactive']==1){
					$oqd2="N/A";
				}
				else{
					if($rowM1['PasOQD']==1){
						$oqd2="V";
					}
					else{
						if((($rowM1['NbLivrableConformeOQD']>0 || $rowM1['NbLivrableToleranceOQD']>0 || $rowM1['NbRetourClientOQD']>0) && $rowM1['ObjectifClientOQD']>0)){
							$ratio=round(($rowM1['NbLivrableConformeOQD']/($rowM1['NbLivrableConformeOQD']+$rowM1['NbLivrableToleranceOQD']+$rowM1['NbRetourClientOQD']))*100,2);
							if(($ratio>=$rowM1['ObjectifClientOQD']) || ($ratio<$rowM1['ObjectifClientOQD'] && $rowM1['CauseOQD']<>"" && $rowM1['ActionOQD']<>"")){
								$oqd2="V";
							}
							else{
								$oqd2="P";
							}
						}
					}
				}
				
				if($row['ManagementADesactive']==1){
					$management2="N/A";
				}
				else{
					if($rowM1['TendanceManagement']>0 && $rowM1['EvenementManagement']==""){
						$management2="P";
					}
					elseif($rowM1['TendanceManagement']==0 || ($rowM1['TendanceManagement']>0 && $rowM1['EvenementManagement']<>"")){
						$management2="V";
					}
				}
					
				
				if($row['CompetenceADesactive']==1 || $row['PolyvalenceADesactive']==1){
					$polyv2="N/A";
				}
				else{
					if($rowM1['NbXTableauPolyvalence']>0 || $rowM1['NbLTableauPolyvalence']>0){
						$polyv2="V";
					}
				}
				
				if($row['CompetenceADesactive']==1){
					$qualif2="N/A";
				}
				else{
					if($rowM1['TauxQualif']>0){
						$qualif2="V";
					}
				}
				
				if($row['PRMADesactive']==1){
					$prm2="N/A";
					$satis2="N/A";
				}
				else{
					if($rowM1['PeriodicitePRM']=="Pas de PRM"){
						$prm2="V";
					}
					elseif($rowM1['DerniereDatePRM']>0 && $rowM1['PeriodicitePRM']<>""){
						$prm2="V";
					}
					
					if($rowM1['DateEnvoiDemandeSatisfaction']>"0001-01-01"){
						$satis2="V";
					}
				}
				
				if($row['SecuriteADesactive']==1){
					$at2="N/A";
				}
				else{
					if($rowM1['PasAT']==1){
						$at2="V";
					}
					else{
						$req="SELECT Id
							FROM moris_moisprestation_securite 
							WHERE Suppr=0 
							AND Id_MoisPrestation=".$rowM1['Id']." ";
						$resultAT=mysqli_query($bdd,$req);
						$nbResultaAT=mysqli_num_rows($resultAT);
						if($nbResultaAT>0){
							$at2="V";
						}
					}
				}
				
				if($row['NCADesactive']==1){
					$nc2="N/A";
				}
				else{
					if($rowM1['PasNC']==1){
						$nc2="V";
					}
					else{
						$req="SELECT Id
							FROM moris_moisprestation_ncdac 
							WHERE Suppr=0 
							AND NC_DAC<>'DAC'
							AND Id_MoisPrestation=".$rowM1['Id']." ";
						$resultNC=mysqli_query($bdd,$req);
						$nbResultaNC=mysqli_num_rows($resultNC);
						if($nbResultaNC>0){
							$nc2="V";
						}
					}
				}
			}
			else{
				
			}
			
			if($charge=="X" && $charge2=="X"){$charge="<span style='color:red'>X</span>";}
			if($productivite=="X" && $productivite2=="X"){$productivite="<span style='color:red'>X</span>";}
			if($otd=="X" && $otd2=="X"){$otd="<span style='color:red'>X</span>";}
			if($oqd=="X" && $oqd2=="X"){$oqd="<span style='color:red'>X</span>";}
			if($management=="X" && $management2=="X"){$management="<span style='color:red'>X</span>";}
			if($polyv=="X" && $polyv2=="X"){$polyv="<span style='color:red'>X</span>";}
			if($qualif=="X" && $qualif2=="X"){$qualif="<span style='color:red'>X</span>";}
			if($at=="X" && $at2=="X"){$at="<span style='color:red'>X</span>";}
			if($prm=="X" && $prm2=="X"){$prm="<span style='color:red'>X</span>";}
			if($satis=="X" && $satis2=="X"){$satis="<span style='color:red'>X</span>";}
			if($nc=="X" && $nc2=="X"){$nc="<span style='color:red'>X</span>";}
			
			$tabPresta[$i] = array($row['Id'],$row['Plateforme'],$presta,$enregistre,$verrouille,$charge,$productivite,$otd,$oqd,$management,$polyv,$qualif,$pdv,$at,$prm,$satis,$nc);
			$i++;
		}
	}
}

$Headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";

if($envoi=="N2"){
	$Id_Poste=$IdPosteCoordinateurEquipe;
	$ligne="";
}
else{
	$Id_Poste=$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet;
	if($_SESSION["Langue"]=="FR"){
		$ligne="<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >Données verrouillées</td>";
	}
	else{
		$ligne="<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >Locked data</td>";
	}
}	
if($listePresta<>""){
	if($Id_Poste==$IdPosteCoordinateurEquipe){
		$req="SELECT DISTINCT Id_Personne, 
		(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne) AS EmailPro
		FROM new_competences_personne_poste_prestation
		WHERE Id_Prestation IN (".$listePresta.")
		AND (
			(
			(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) <> 19 
			AND Id_Poste=".$Id_Poste."
			AND Id_Personne>0 
			AND Backup=0
			AND(SELECT COUNT(TAB.Id_Poste)
				FROM new_competences_personne_poste_prestation AS TAB
				WHERE TAB.Id_Prestation=new_competences_personne_poste_prestation.Id_Prestation
				AND TAB.Id_Personne=new_competences_personne_poste_prestation.Id_Personne
				AND TAB.Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.",".$IdPosteResponsablePlateforme.") )=0
			AND(SELECT COUNT(Id_Poste)
				FROM new_competences_personne_poste_plateforme
				WHERE new_competences_personne_poste_plateforme.Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_poste_prestation.Id_Prestation)
				AND new_competences_personne_poste_plateforme.Id_Personne=new_competences_personne_poste_prestation.Id_Personne
				AND new_competences_personne_poste_plateforme.Id_Poste IN (".$IdPosteResponsablePlateforme.") )=0
			AND (SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne)<>''
			)
			OR 
			(
			(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = 19 
			AND Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.")
			AND Id_Personne>0 
			AND Backup=0
			AND(SELECT COUNT(TAB.Id_Poste)
				FROM new_competences_personne_poste_prestation AS TAB
				WHERE TAB.Id_Prestation=new_competences_personne_poste_prestation.Id_Prestation
				AND TAB.Id_Personne=new_competences_personne_poste_prestation.Id_Personne
				AND TAB.Id_Poste IN (".$IdPosteResponsableOperation.",".$IdPosteResponsablePlateforme.") )=0
			AND(SELECT COUNT(Id_Poste)
				FROM new_competences_personne_poste_plateforme
				WHERE new_competences_personne_poste_plateforme.Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_poste_prestation.Id_Prestation)
				AND new_competences_personne_poste_plateforme.Id_Personne=new_competences_personne_poste_prestation.Id_Personne
				AND new_competences_personne_poste_plateforme.Id_Poste IN (".$IdPosteResponsablePlateforme.") )=0
			AND (SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne)<>''
			)
			)";
	}
	elseif($Id_Poste==$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet){
		$req="SELECT DISTINCT Id_Personne, 
		(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne) AS EmailPro
		FROM new_competences_personne_poste_prestation
		WHERE Id_Prestation IN (".$listePresta.") 
		AND Id_Poste IN (".$Id_Poste.")
		AND Id_Personne>0 
		AND Backup=0
		AND(SELECT COUNT(TAB.Id_Poste)
			FROM new_competences_personne_poste_prestation AS TAB
			WHERE TAB.Id_Prestation=new_competences_personne_poste_prestation.Id_Prestation
			AND TAB.Id_Personne=new_competences_personne_poste_prestation.Id_Personne
			AND TAB.Id_Poste IN (".$IdPosteResponsableOperation.",".$IdPosteResponsablePlateforme.") )=0
		AND(SELECT COUNT(Id_Poste)
			FROM new_competences_personne_poste_plateforme
			WHERE new_competences_personne_poste_plateforme.Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_poste_prestation.Id_Prestation)
			AND new_competences_personne_poste_plateforme.Id_Personne=new_competences_personne_poste_prestation.Id_Personne
			AND new_competences_personne_poste_plateforme.Id_Poste IN (".$IdPosteResponsablePlateforme.") )=0
		AND (SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne)<>'' ";
	}
	$result2=mysqli_query($bdd,$req);
	$nbResulta2=mysqli_num_rows($result2);
	if($nbResulta2>0){
		while($row=mysqli_fetch_array($result2)){
			if($row['EmailPro']<>""){
				if($Id_Poste==$IdPosteCoordinateurEquipe){
					$req="SELECT DISTINCT Id_Prestation
					FROM new_competences_personne_poste_prestation
					LEFT JOIN new_competences_prestation
					ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
					WHERE Id_Prestation IN (".$listePresta.") 
					AND (
						((SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) <> 19 AND Id_Poste IN (".$Id_Poste."))
						OR 
						((SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = 19 AND Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet."))
						)
					AND Backup=0
					AND Id_Personne=".$row['Id_Personne']." 
					ORDER BY new_competences_prestation.Libelle ";
				}
				else{
					$req="SELECT DISTINCT Id_Prestation
					FROM new_competences_personne_poste_prestation
					LEFT JOIN new_competences_prestation
					ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
					WHERE Id_Prestation IN (".$listePresta.") 
					AND Id_Poste IN (".$Id_Poste.")
					AND Backup=0
					AND Id_Personne=".$row['Id_Personne']." 
					ORDER BY new_competences_prestation.Libelle ";
				}
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if($_SESSION["Langue"]=="FR"){
					$table="<table style='border:1px solid black;border-spacing:0;border-collapse : collapse;'>
						<tr>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >UER</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >ACTIVITE</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >Données enregistrées</td>
							".$ligne."
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >CHARGE / CAPA</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >PRODUCTIVITE</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >OTD</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >OQD</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >MANAGEMENT</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >POLYVALENCE</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >QUALIFICATION</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >PDP</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >AT</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >PRM</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >SATISFACTION CLIENT</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >NC/RC</td>
						</tr>";
				}
				else{
					$table="<table style='border:1px solid black;border-spacing:0;border-collapse : collapse;'>
						<tr>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >UER</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >ACTIVITY</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >Recorded data</td>
							".$ligne."
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >CHARGE / CAPA</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >PRODUCTIVITY</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >OTD</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >OQD</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >MANAGEMENT</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >VERSATILITY</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >QUALIFICATION</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >PDP</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >AT</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >PRM</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >CUSTOMER SATISFACTION</td>
							<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;' >NC/RC</td>
						</tr>";
				}
				if($nbResulta>0){
					while($row2=mysqli_fetch_array($result)){
						foreach($tabPresta as $laPresta){
							if($laPresta[0]==$row2['Id_Prestation']){
								$table.="<tr>
									<td style='border:1px solid black;text-align:center;'>".$laPresta[1]."</td>
									<td style='border:1px solid black;text-align:center;'>".$laPresta[2]."</td>
									<td style='border:1px solid black;text-align:center;'><b>".$laPresta[3]."</b></td>";
								if($envoi=="RespProjet"){
									$table.="<td style='border:1px solid black;text-align:center;'><b>".$laPresta[4]."</b></td>";
								}
								$table.="<td style='border:1px solid black;text-align:center;'><b>".$laPresta[5]."</b></td>
									<td style='border:1px solid black;text-align:center;'><b>".$laPresta[6]."</b></td>
									<td style='border:1px solid black;text-align:center;'><b>".$laPresta[7]."</b></td>
									<td style='border:1px solid black;text-align:center;'><b>".$laPresta[8]."</b></td>
									<td style='border:1px solid black;text-align:center;'><b>".$laPresta[9]."</b></td>
									<td style='border:1px solid black;text-align:center;'><b>".$laPresta[10]."</b></td>
									<td style='border:1px solid black;text-align:center;'><b>".$laPresta[11]."</b></td>
									<td style='border:1px solid black;text-align:center;'><b>".$laPresta[12]."</b></td>
									<td style='border:1px solid black;text-align:center;'><b>".$laPresta[13]."</b></td>
									<td style='border:1px solid black;text-align:center;'><b>".$laPresta[14]."</b></td>
									<td style='border:1px solid black;text-align:center;'><b>".$laPresta[15]."</b></td>
									<td style='border:1px solid black;text-align:center;'><b>".$laPresta[16]."</b></td>
								</tr>";
							}
						}
					}
				}
				$table.="</table>";
				
				if($_SESSION['Langue']=="FR"){
					$sujet="Alerte RECORD - Absence information - ".$MoisLettre[$_SESSION['MORIS_Mois2']-1]." ".$_SESSION['MORIS_Annee2'];
					$message_html="	<html>
						<head><title>".$sujet."</title></head>
						<body>
							Bonjour,
							<br>
							Les activités suivantes ont des paramètres non saisis pour le mois de ".$MoisLettre[$_SESSION['MORIS_Mois2']-1]." ".$_SESSION['MORIS_Annee2']."
							<br><br>
							".$table."
							<br>
							".$row['EmailPro']."
							<br>
							<b>X</b>&rarr;donnée absente<br>
							<b><span style='color:red'>X</span></b>&rarr;donnée absente récurrente<br>
							<b>P</b>&rarr;donnée partielle<br><br>
							Bonne journée,<br>
							RECORD – La Direction des Opérations 
						</body>
					</html>";
				}
				else{
					$sujet="RECORD Alert - No information - ".$MoisLettre[$_SESSION['MORIS_Mois2']-1]." ".$_SESSION['MORIS_Annee2'];
					$message_html="	<html>
						<head><title>".$sujet."</title></head>
						<body>
							Bonjour,
							<br>
							The following activities have parameters not entered for the month of ".$MoisLettre[$_SESSION['MORIS_Mois2']-1]." ".$_SESSION['MORIS_Annee2']."
							<br><br>
							".$table."
							<br>
							".$row['EmailPro']."
							<br>
							<b>X</b>&rarr;missing data<br>
							<b><span style='color:red'>X</span></b>&rarr;recurring missing data<br>
							<b>P</b>&rarr;partial data<br><br>
							Have a good day,<br>
							RECORD – La Direction des Opérations 
						</body>
					</html>";
				}
				$Emails=$row['EmailPro'];
				mail($Emails,$sujet,$message_html,$Headers,'-f extranet@aaa-aero.com');
			}
		}
	}
}

echo "<script>window.close();</script>";
?>
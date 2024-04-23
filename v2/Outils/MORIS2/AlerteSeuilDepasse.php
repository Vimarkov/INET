<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

$MoisLettre = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

$anneeM_1=$_SESSION['MORIS_Annee2'];
$moisM_1=$_SESSION['MORIS_Mois2']-1;
if($moisM_1==0){
	$moisM_1=12;
	$anneeM_1=$anneeM_1-1;
}

$req="SELECT Id,Libelle,Id_Plateforme,PlanPreventionADesactivite,ChargeADesactive,ProductiviteADesactive,PolyvalenceADesactive,
	OTDOQDADesactive,ManagementADesactive,CompetenceADesactive,SecuriteADesactive,PRMADesactive,NCADesactive,
	(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme
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
	AND (SELECT Id FROM moris_moisprestation WHERE moris_moisprestation.Id_Prestation=new_competences_prestation.Id 
		AND Annee=".$_SESSION['MORIS_Annee2']." 
		AND Mois=".$_SESSION['MORIS_Mois2']."
		AND Suppr=0 LIMIT 1) >0
	ORDER BY Libelle
	";
$result=mysqli_query($bdd,$req);
$nbResulta=mysqli_num_rows($result);
$listePresta="";
$tabPresta = array();
$i=0;
if ($nbResulta>0){
	while($row=mysqli_fetch_array($result)){
		$charge="X";
		$productivite="X";
		$management="X";
		$otd="X";
		$oqd="X";
		$polyv="X";
		$qualif="X";
		$at="X";
		$pdv="X";
		$satis="X";
		$nc="X";
		
		$Seuilcharge="";
		$Seuilproductivite1="";
		$Seuilproductivite09="";
		$Seuilproductivite08="";
		$Seuilmanagement1="";
		$Seuilmanagement2="";
		$SeuilotdL="";
		$SeuilotdA="";
		$SeuiloqdL="";
		$SeuiloqdA="";
		$SeuilmonoCompetence="";
		$Seuilpolyv50="";
		$Seuilqualif80="";	
		$Seuilqualif50="";
		$SeuilatSansArret="";
		$SeuilatAvecArret="";
		$Seuilpdv="";
		$Seuilsatis3="";
		$Seuilsatis2="";
		$Seuilnc2="";
		$Seuilnc3="";
		
		$req="SELECT Id,
			InterneCurrent+COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 ),0) AS InterneCurrent,
			SubContractorCurrent+COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 ),0) AS SubContractorCurrent,
			M1+COALESCE((SELECT SUM(M1) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0) AS M1,
			BesoinEffectif,
			TempsAlloue,TempsPasse,TempsObjectif,ChargeDesactive,ProductiviteDesactive,
			ObjectifClientOTD,NbLivrableConformeOTD,NbLivrableToleranceOTD,NbRetourClientOTD,CauseOTD,ActionOTD,
			ObjectifClientOQD,NbLivrableConformeOQD,NbLivrableToleranceOQD,NbRetourClientOQD,CauseOQD,ActionOQD,
			ModeCalculOTD,ModeCalculOQD,
			TendanceManagement,EvenementManagement,PasAT,PasNC,PasOTD,PasOQD,
			NbXTableauPolyvalence,NbLTableauPolyvalence,NbMonoCompetence,TauxQualif,
			DerniereDatePRM,DerniereDateEvaluation,PeriodicitePRM,DateEnvoiDemandeSatisfaction,
			EvaluationQualite,EvaluationDelais,EvaluationCompetencePersonnel,EvaluationAutonomie,EvaluationAnticipation,EvaluationCommunication 
			FROM moris_moisprestation 
			WHERE moris_moisprestation.Id_Prestation=".$row['Id']."
				AND Annee=".$_SESSION['MORIS_Annee2']." 
				AND Mois=".$_SESSION['MORIS_Mois2']."
				AND Suppr=0 LIMIT 1
			";
		$result2=mysqli_query($bdd,$req);
		$nbResulta2=mysqli_num_rows($result2);
		
		$req="SELECT Id,TendanceManagement
			FROM moris_moisprestation 
			WHERE moris_moisprestation.Id_Prestation=".$row['Id']."
				AND Annee=".$anneeM_1." 
				AND Mois=".$moisM_1."
				AND Suppr=0 LIMIT 1
			";
		$resultM_1=mysqli_query($bdd,$req);
		$nbResultaM_1=mysqli_num_rows($resultM_1);
		if($nbResultaM_1>0){
			$rowM_1=mysqli_fetch_array($resultM_1);
		}
		
		if($nbResulta2>0){
			$row2=mysqli_fetch_array($result2);
			
			if($row['ChargeADesactive']==1){
				$charge="N/A";
			}
			else{
				if($row2['InterneCurrent']>0 || $row2['SubContractorCurrent']>0){$charge="V";}
			}
			
			if($row['ProductiviteADesactive']==1){
				$productivite="N/A";
			}
			else{
				if($row2['TempsAlloue']>0 || $row2['TempsPasse']>0){$productivite="V";}
			}
			
			if($row['OTDOQDADesactive']==1){
				$otd="N/A";
			}
			else{
				if($row2['PasOTD']==1){
					$otd="N/A";
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
			
			if($row['OTDOQDADesactive']==1){
				$oqd="N/A";
			}
			else{
				if($row2['PasOQD']==1){
					$oqd="N/A";
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
			
			if($row['ManagementADesactive']==1){
				$management="N/A";
			}
			else{
				if($row2['TendanceManagement']==0 || ($row2['EvenementManagement']<>"" && $row2['TendanceManagement']>0)){
					$management="V";
				}
			}
			
			if($row['CompetenceADesactive']==1 || $row['PolyvalenceADesactive']==1){
				$polyv="N/A";
			}
			else{
				if($row['PolyvalenceADesactive']==1){
					$polyv="N/A";
				}
				else{
					if($row2['NbXTableauPolyvalence']>0 || $row2['NbLTableauPolyvalence']>0){
						$polyv="V";
					}
				}
			}
			
			if($row['CompetenceADesactive']==1){
				$qualif="N/A";
			}
			else{
				if($row2['TauxQualif']>0){
					$qualif="V";
				}
			}
			
			if($row['PRMADesactive']==1){
				$satis="N/A";
			}
			else{
				if($row2['DateEnvoiDemandeSatisfaction']>"0001-01-01"){
					$satis="V";
				}
			}
			
			if($row['SecuriteADesactive']==1 || ($row['SecuriteADesactive']==0 && $_SESSION['MORIS_Annee2'].'_'.$_SESSION['MORIS_Mois2']>"2023_06")){
				$at="N/A";
			}
			else{
				if($row2['PasAT']==1){
					$at="N/A";
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
				if($row2['PasNC']==1){
					$nc="N/A";
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
			
			//-------CALCUL DES SEUILS-------//
			if($charge=="V"){
				if(($row2['InterneCurrent']+$row2['SubContractorCurrent'])<$row2['M1']){
					$total=$row2['InterneCurrent']+$row2['SubContractorCurrent'];
					$Seuilcharge="M : ".$total."<br>M+1 : ".$row2['M1'];
				}
			}
			
			if($productivite=="V"){
				$laProd=round($row2['TempsAlloue']/$row2['TempsPasse'],2);
				if($laProd<1){$Seuilproductivite1=$laProd;}
				if($laProd<0.9){$Seuilproductivite09=$laProd;}
				if($laProd<0.8){$Seuilproductivite08=$laProd;}
			}
			
			if($management=="V"){
				if($row2['TendanceManagement']==1){
					$Seuilmanagement1="O";
				}
				elseif($row2['TendanceManagement']==2){
					$Seuilmanagement1="R";
					if($nbResultaM_1>0){
						if($row2['TendanceManagement']==2 && $rowM_1['TendanceManagement']==2){
							$Seuilmanagement2="X";
						}
					}
				}
			}
			
			if($otd=="V"){
				$ratio=round(($row2['NbLivrableConformeOTD']/($row2['NbLivrableConformeOTD']+$row2['NbLivrableToleranceOTD']+$row2['NbRetourClientOTD']))*100,2);
				if($ratio<$row2['ObjectifClientOTD']){
					$SeuilotdA="<span style='color:red;'>".$ratio."%</span> < ".$row2['ObjectifClientOTD']."%";
				}
				$req="SELECT Libelle,ObjectifClient,NbLivrableConforme,NbLivrableTolerance,NbRetourClient,ObjectifTolerance
					FROM moris_moisprestation_otdoqd
					WHERE Id_MoisPrestation=".$row2['Id']." 
					AND bOQD=0 
					AND PasLivrable=0 ";
				$resultOTDOQD=mysqli_query($bdd,$req);
				$nbResultOTDOQD=mysqli_num_rows($resultOTDOQD);
				if($nbResultOTDOQD>0){
					while($rowOTDOQD=mysqli_fetch_array($resultOTDOQD)){
						if(($rowOTDOQD['NbLivrableConforme']>0 || $rowOTDOQD['NbLivrableTolerance']>0 || $rowOTDOQD['NbRetourClient']>0) && $rowOTDOQD['ObjectifClient']>0){
							$ratio=round(($rowOTDOQD['NbLivrableConforme']/($rowOTDOQD['NbLivrableConforme']+$rowOTDOQD['NbLivrableTolerance']+$rowOTDOQD['NbRetourClient']))*100,2);
							if($ratio<$rowOTDOQD['ObjectifClient']){
								if($SeuilotdL<>""){$SeuilotdL.="<br>";}
								$SeuilotdL.=stripslashes($rowOTDOQD['Libelle'])." : <span style='color:red;'>".$ratio."%</span> < ".$rowOTDOQD['ObjectifClient']."%";
							}
						}
					}
				}
				else{
					if($ratio<$row2['ObjectifClientOTD']){
						$SeuilotdL="<span style='color:red;'>".$ratio."%</span> < ".$row2['ObjectifClientOTD']."%";
					}
				}
			}
			
			if($oqd=="V"){
				$ratio=round(($row2['NbLivrableConformeOQD']/($row2['NbLivrableConformeOQD']+$row2['NbLivrableToleranceOQD']+$row2['NbRetourClientOQD']))*100,2);
				if($ratio<$row2['ObjectifClientOQD']){
					$SeuiloqdA="<span style='color:red;'>".$ratio."%</span> < ".$row2['ObjectifClientOQD']."%";
				}
				$req="SELECT Libelle,ObjectifClient,NbLivrableConforme,NbLivrableTolerance,NbRetourClient,ObjectifTolerance
					FROM moris_moisprestation_otdoqd
					WHERE Id_MoisPrestation=".$row2['Id']." 
					AND bOQD=1 
					AND PasLivrable=0 ";
				$resultOTDOQD=mysqli_query($bdd,$req);
				$nbResultOTDOQD=mysqli_num_rows($resultOTDOQD);
				if($nbResultOTDOQD>0){
					while($rowOTDOQD=mysqli_fetch_array($resultOTDOQD)){
						if(($rowOTDOQD['NbLivrableConforme']>0 || $rowOTDOQD['NbLivrableTolerance']>0 || $rowOTDOQD['NbRetourClient']>0) && $rowOTDOQD['ObjectifClient']>0){
							$ratio=round(($rowOTDOQD['NbLivrableConforme']/($rowOTDOQD['NbLivrableConforme']+$rowOTDOQD['NbLivrableTolerance']+$rowOTDOQD['NbRetourClient']))*100,2);
							if($ratio<$rowOTDOQD['ObjectifClient']){
								if($SeuiloqdL<>""){$SeuiloqdL.="<br>";}
								$SeuiloqdL.=stripslashes($rowOTDOQD['Libelle'])." : <span style='color:red;'>".$ratio."%</span> < ".$rowOTDOQD['ObjectifClient']."%";
							}
						}
					}
				}
				else{
					if($ratio<$row2['ObjectifClientOQD']){
						$SeuiloqdL="<span style='color:red;'>".$ratio."%</span> < ".$row2['ObjectifClientOQD']."%";
					}
				}
			}
			
			if($polyv=="V"){
				if($row2['NbMonoCompetence']>0){
					$SeuilmonoCompetence=$row2['NbMonoCompetence'];
					$SeuilmonoCompetence.=" mono compétence(s)";
						
				}
				$ratio=round(($row2['NbXTableauPolyvalence']/($row2['NbXTableauPolyvalence']+$row2['NbLTableauPolyvalence']))*100,2);
				if($ratio<50){$Seuilpolyv50="Taux de polyvalence : ".$ratio."%";}
			}
			if($qualif=="V"){
				if($row2['TauxQualif']<80){$Seuilqualif80="Taux de qualification : ".$row2['TauxQualif']."%";}
				if($row2['TauxQualif']<50){$Seuilqualif50="Taux de qualification : ".$row2['TauxQualif']."%";}
			}
			
			if($at=="V"){
				$req="SELECT Id
					FROM moris_moisprestation_securite 
					WHERE Suppr=0 
					AND AvecArret=0
					AND Id_MoisPrestation=".$row2['Id']." ";
				$resultAT=mysqli_query($bdd,$req);
				$nbResultaAT=mysqli_num_rows($resultAT);
				if($nbResultaAT>0){$SeuilatSansArret=$nbResultaAT." AT sans arrêt";}	
				
				$req="SELECT Id
					FROM moris_moisprestation_securite 
					WHERE Suppr=0 
					AND AvecArret=1
					AND Id_MoisPrestation=".$row2['Id']." ";
				$resultAT=mysqli_query($bdd,$req);
				$nbResultaAT=mysqli_num_rows($resultAT);
				if($nbResultaAT>0){$SeuilatAvecArret=$nbResultaAT." AT avec arrêt";}	
			}
			
			if($satis=="V"){
				
				$moyenne="";
				$total=0;
				$nbEval=0;
				if($row2['EvaluationQualite']>-1){
					$total+=$row2['EvaluationQualite'];
					$nbEval++;
				}
				if($row2['EvaluationDelais']>-1){
					$total+=$row2['EvaluationDelais'];
					$nbEval++;
				}
				if($row2['EvaluationCompetencePersonnel']>-1){
					$total+=$row2['EvaluationCompetencePersonnel'];
					$nbEval++;
				}
				if($row2['EvaluationAutonomie']>-1){
					$total+=$row2['EvaluationAutonomie'];
					$nbEval++;
				}
				if($row2['EvaluationAnticipation']>-1){
					$total+=$row2['EvaluationAnticipation'];
					$nbEval++;
				}
				if($row2['EvaluationCommunication']>-1){
					$total+=$row2['EvaluationCommunication'];
					$nbEval++;
				}
				if($nbEval>0){
					$moyenne=round($total/$nbEval,2);
				}
				
				if($nbEval>0 && $total>0){
					$note=round($total/$nbEval,2);
					if($note<3){$Seuilsatis3="Satisfaction client : ".$note;}
					if($note<2){$Seuilsatis2="Satisfaction client : ".$note;}
				}
			}
			
			if($nc=="V"){
				$req="SELECT Id
					FROM moris_moisprestation_ncdac
					WHERE Suppr=0 
					AND Progression=0
					AND NC_DAC IN ('NC Niv 2','NC Niv 3')
					AND Id_MoisPrestation=".$row2['Id']." ";
				$resultNC=mysqli_query($bdd,$req);
				$nbResultaNC=mysqli_num_rows($resultNC);
				if($nbResultaNC>0){$Seuilnc2=$nbResultaNC." NC Niv 2/3";}
				
				$req="SELECT Id
					FROM moris_moisprestation_ncdac
					WHERE Suppr=0 
					AND Progression=0
					AND NC_DAC IN ('NC Niv 3')
					AND Id_MoisPrestation=".$row2['Id']." ";
				$resultNC=mysqli_query($bdd,$req);
				$nbResultaNC=mysqli_num_rows($resultNC);
				if($nbResultaNC>0){$Seuilnc3=$nbResultaNC." NC Niv 3";}
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
				
				if($row2['RefPdp']<>"" && $row2['DateValidite']>"0001-01-01"){
					if($row2['DateValidite']<date('Y-m-d',strtotime(date('Y-m-d')." +2 month"))){
						$Seuilpdv="PDP : ".AfficheDateJJ_MM_AAAA($row2['DateValidite']);
					}
				}
			}
		}
		
		$presta=substr($row['Libelle'],0,strpos($row['Libelle']," "));
		
		if($Seuilcharge<>"" || $Seuilproductivite1<>"" || $Seuilproductivite09<>"" || $Seuilproductivite08<>"" || $Seuilmanagement1<>"" || $Seuilmanagement2<>"" 
		|| $SeuilotdL<>"" || $SeuilotdA<>"" || $SeuiloqdL<>"" || $SeuiloqdA<>"" || $SeuilmonoCompetence<>"" || $Seuilpolyv50<>"" || $Seuilqualif80<>"" || $Seuilqualif50<>"" 
		|| $SeuilatSansArret<>"" || $SeuilatAvecArret<>"" || $Seuilpdv<>""  || $Seuilsatis3<>"" || $Seuilsatis2<>"" || $Seuilnc2<>"" || $Seuilnc3<>""){
			if($listePresta<>""){$listePresta.=",";}
			$listePresta.= $row['Id'];
			
			$tabPresta[$i] = array($row['Id'],$row['Plateforme'],$presta,$Seuilcharge,$Seuilproductivite1,$Seuilproductivite09,$Seuilproductivite08,$Seuilmanagement1,$Seuilmanagement2,$SeuilotdL,$SeuilotdA,$SeuiloqdL,$SeuiloqdA,$SeuilmonoCompetence,$Seuilpolyv50,$Seuilqualif80,$Seuilqualif50,$SeuilatSansArret,$SeuilatAvecArret,$Seuilpdv,$Seuilsatis3,$Seuilsatis2,$Seuilnc2,$Seuilnc3,$row['Id_Plateforme']);
			$i++;
		}
	}
}

$Headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";

$table="<table style='border:1px solid black;border-spacing:0;border-collapse : collapse;'>
	<tr>
		<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;'>UER</td>
		<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;'>ACTIVITE</td>
		<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;'>CHARGE / CAPA</td>
		<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;'>PRODUCTIVITE</td>
		<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;'>MANAGEMENT</td>
		<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;'>OTD < Objectif</td>
		<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;'>OQD < Objectif</td>
		<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;'>COMPETENCES</td>
		<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;'>SECURITE</td>
		<td style='border:1px solid black;text-align:center;background-color:#d9f5ff;width:120px;'>QUALITE</td>
	</tr>";

if($listePresta<>""){
	$req="SELECT DISTINCT Id_Personne, 
	(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne) AS EmailPro
	FROM new_competences_personne_poste_prestation
	WHERE  Id_Prestation IN (".$listePresta.") 
	AND Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.") 
	AND Id_Personne>0 
	AND Backup=0
	AND (SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne)<>''
	UNION 
	SELECT DISTINCT Id_Personne, 
	(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne) AS EmailPro
	FROM new_competences_personne_poste_plateforme
	WHERE Id_Plateforme IN (SELECT Id FROM new_competences_prestation WHERE Id IN (".$listePresta."))
	AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteResponsableQualite.",".$IdPosteResponsableHSE.",".$IdPosteDivision.")
	AND Id_Personne>0
	AND Backup=0
	AND (SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne)<>''
	UNION 
	SELECT DISTINCT Id_Personne, 
	(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne) AS EmailPro
	FROM new_competences_personne_poste_plateforme
	WHERE Id_Plateforme IN (SELECT Id FROM new_competences_prestation WHERE Id IN (".$listePresta."))
	AND Id_Poste IN (".$IdPosteDirectionOperation.")
	AND Id_Personne>0
	AND (SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne)<>''
	UNION 
	SELECT DISTINCT Id_Personne, 
	(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne) AS EmailPro
	FROM new_competences_personne_poste_plateforme
	WHERE Id_Plateforme = 17
	AND Id_Poste IN (".$IdPosteResponsableQualite.")
	AND Id_Personne>0
	AND (SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne)<>''
	";
	$result2=mysqli_query($bdd,$req);
	$nbResulta2=mysqli_num_rows($result2);
	if($nbResulta2>0){
		while($row=mysqli_fetch_array($result2)){
			if($row['EmailPro']<>""){
				$table2="";
				foreach($tabPresta as $laPresta){
					//Vérifier les différents postes 
					$CoordProjet=0;
					$RespProjet=0;
					$CQP=0;
					$RespUER=0;
					$RespQualite=0;
					$HSE=0;
					$DirOperation=0;
					$Division=0;
					$RespQualiteFR=0;
					$req="SELECT DISTINCT Id_Poste
					FROM new_competences_personne_poste_prestation
					WHERE  Id_Prestation = ".$laPresta[0]." 
					AND Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.") 
					AND Id_Personne=".$row['Id_Personne']."
					AND Backup=0
					UNION 
					SELECT DISTINCT Id_Poste
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Plateforme = ".$laPresta[24]."
					AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteResponsableQualite.",".$IdPosteResponsableHSE.",".$IdPosteDivision.")
					AND Id_Personne=".$row['Id_Personne']."
					AND Backup=0
					UNION 
					SELECT DISTINCT Id_Poste
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Plateforme = ".$laPresta[24]."
					AND Id_Poste IN (".$IdPosteDirectionOperation.")
					AND Id_Personne=".$row['Id_Personne']."
					";
					$resultPoste=mysqli_query($bdd,$req);
					$nbResultaPoste=mysqli_num_rows($resultPoste);
					if($nbResultaPoste>0){
						while($rowPoste=mysqli_fetch_array($resultPoste)){
							if($rowPoste['Id_Poste']==$IdPosteCoordinateurProjet){$CoordProjet=1;}
							if($rowPoste['Id_Poste']==$IdPosteResponsableProjet){$RespProjet=1;}
							if($rowPoste['Id_Poste']==$IdPosteReferentQualiteProduit){$CQP=1;}
							if($rowPoste['Id_Poste']==$IdPosteResponsablePlateforme){$RespUER=1;}
							if($rowPoste['Id_Poste']==$IdPosteResponsableQualite){$RespQualite=1;}
							if($rowPoste['Id_Poste']==$IdPosteResponsableHSE){$HSE=1;}
							if($rowPoste['Id_Poste']==$IdPosteDirectionOperation){$DirOperation=1;}
							if($rowPoste['Id_Poste']==$IdPosteDivision){$Division=1;}
						}
					}
					
					$req="SELECT DISTINCT Id_Poste
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Plateforme = 17
					AND Id_Poste IN (".$IdPosteResponsableQualite.")
					AND Id_Personne=".$row['Id_Personne']."
					";
					$resultPoste=mysqli_query($bdd,$req);
					$nbResultaPoste=mysqli_num_rows($resultPoste);
					if($nbResultaPoste>0){
						$RespQualiteFR=1;
					}
					
					$Seuilcharge=$laPresta[3];
					$Seuilproductivite1=$laPresta[4];
					$Seuilproductivite09=$laPresta[5];
					$Seuilproductivite08=$laPresta[6];
					$Seuilmanagement1=$laPresta[7];
					$Seuilmanagement2=$laPresta[8];
					$SeuilotdL=$laPresta[9];
					$SeuilotdA=$laPresta[10];
					$SeuiloqdL=$laPresta[11];
					$SeuiloqdA=$laPresta[12];
					$SeuilmonoCompetence=$laPresta[13];
					$Seuilpolyv50=$laPresta[14];
					$Seuilqualif80=$laPresta[15];	
					$Seuilqualif50=$laPresta[16];
					$SeuilatSansArret=$laPresta[17];
					$SeuilatAvecArret=$laPresta[18];
					$Seuilpdv=$laPresta[19];
					$Seuilsatis3=$laPresta[20];
					$Seuilsatis2=$laPresta[21];
					$Seuilnc2=$laPresta[22];
					$Seuilnc3=$laPresta[23];
					$Id_Plateforme=$laPresta[24];
					
					if($Seuilcharge<>"")
					{
						if($CoordProjet==1 || $RespProjet==1){
							if($RespUER==1 || $DirOperation==1 || $RespQualiteFR==1 || $Division==1){$Seuilcharge="";}
						}
						else{
							$Seuilcharge="";
						}
					}
					
					if($Seuilproductivite1<>""){
						if($CoordProjet==1 || $RespProjet==1){
							if($RespUER==1 || $DirOperation==1 || $RespQualiteFR==1 || $Division==1){
								$Seuilproductivite1="";
							}
						}
						else{
							$Seuilproductivite1="";
						}
					}
					if($Seuilproductivite09<>""){
						if($RespUER==1){
							if($DirOperation==1 || $RespQualiteFR==1 || $Division==1){
								$Seuilproductivite09="";
							}
						}
						else{
							$Seuilproductivite09="";
						}
					}
					if($Seuilproductivite08<>""){
						if($DirOperation==0 && $RespQualiteFR==0 && $Division==0){
							$Seuilproductivite08="";
						}
					}
					$Seuilproductivite="";
					if($Seuilproductivite08<>""){$Seuilproductivite=$Seuilproductivite08;}
					elseif($Seuilproductivite09<>""){$Seuilproductivite=$Seuilproductivite09;}
					elseif($Seuilproductivite1<>""){$Seuilproductivite=$Seuilproductivite1;}
					
					if($Seuilmanagement1<>""){
						if($CoordProjet==1 || $RespProjet==1 || $RespUER==1){
							if($DirOperation==1 || $RespQualiteFR==1 || $Division==1){$Seuilmanagement1="";}
						}
						else{
							$Seuilmanagement1="";
						}
					}
					if($Seuilmanagement2<>""){
						if($DirOperation==0 && $RespQualiteFR==0 && $Division==0){
							$Seuilmanagement2="";
						}
					}
					
					$Seuilmanagement="";
					if($Seuilmanagement1<>""){$Seuilmanagement=$Seuilmanagement1;}
					if($Seuilmanagement2<>""){$Seuilmanagement=$Seuilmanagement2;}
					
					if($SeuilotdA<>""){
						if($RespQualite==0 && $RespUER==0 && $DirOperation==0 && $RespQualiteFR==0 && $Division==0){
							$SeuilotdA="";
						}
					}
					if($SeuilotdL<>""){
						if($CoordProjet==1 || $RespProjet==1 || $RespUER==1){
							if($DirOperation==1 || $RespQualiteFR==1 || $Division==1){$SeuilotdL="";}
						}
						elseif($CQP==1){
							if($RespQualite==1){$SeuilotdL="";}
						}
						else{
							$SeuilotdL="";
						}
					}
					$Seuilotd="";
					if($SeuilotdA<>""){$Seuilotd=$SeuilotdA;}
					elseif($SeuilotdL<>""){$Seuilotd=$SeuilotdL;}
					
					if($SeuiloqdA<>""){
						if($RespQualite==0 && $RespUER==0 && $DirOperation==0 && $RespQualiteFR==0 && $Division==0){
							$SeuiloqdA="";
						}
					}
					if($SeuiloqdL<>""){
						if($CoordProjet==1 || $RespProjet==1 || $RespUER==1){
							if($DirOperation==1 || $RespQualiteFR==1 || $Division==1){$SeuiloqdL="";}
						}
						elseif($CQP==1){
							if($RespQualite==1){$SeuiloqdL="";}
						}
						else{
							$SeuiloqdL="";
						}
					}
					$Seuiloqd="";
					if($SeuiloqdA<>""){$Seuiloqd=$SeuiloqdA;}
					elseif($SeuiloqdL<>""){$Seuiloqd=$SeuiloqdL;}
					
					if($SeuilmonoCompetence<>""){
						if($CoordProjet==1 || $RespProjet==1 || $RespUER==1){
							if($DirOperation==1 || $RespQualiteFR==1 || $Division==1){$SeuilmonoCompetence="";}
						}
						elseif($CQP==1){
							if($RespQualite==1){$SeuilmonoCompetence="";}
						}
						else{
							$SeuilmonoCompetence="";
						}
					}
					
					if($Seuilqualif80<>""){
						if($CoordProjet==1 || $RespProjet==1 || $RespUER==1){
							if($DirOperation==1 || $RespQualiteFR==1 || $Division==1){$Seuilqualif80="";}
						}
						elseif($CQP==1){
							if($RespQualite==1){$Seuilqualif80="";}
						}
						else{
							$Seuilqualif80="";
						}
					}
					
					if($Seuilqualif50<>""){
						if($RespQualite==0 && $DirOperation==0 && $RespQualiteFR==0 && $Division==0){
							$Seuilqualif50="";
						}
					}
					if($Seuilpolyv50<>""){
						if($RespQualite==0 && $DirOperation==0 && $RespQualiteFR==0 && $Division==0){
							$Seuilpolyv50="";
						}
					}
					
					$Seuilcompetence=$SeuilmonoCompetence;
					if($Seuilqualif80<>""){
						if($Seuilcompetence<>""){$Seuilcompetence.="<br>";}
						$Seuilcompetence.=$Seuilqualif80;
					}
					if($Seuilqualif50<>""){
						if($Seuilcompetence<>""){$Seuilcompetence.="<br>";}
						$Seuilcompetence.=$Seuilqualif50;
					}
					if($Seuilpolyv50<>""){
						if($Seuilcompetence<>""){$Seuilcompetence.="<br>";}
						$Seuilcompetence.=$Seuilpolyv50;
					}
					
					if($SeuilatSansArret<>""){
						if($CoordProjet==1 || $RespProjet==1 || $RespUER==1){
							if($DirOperation==1 || $RespQualiteFR==1 || $Division==1){$SeuilatSansArret="";}
						}
						elseif($HSE==1){
						}
						else{
							$SeuilatSansArret="";
						}
					}
					
					if($SeuilatAvecArret<>""){
						if($HSE==0 && $DirOperation==0 && $RespQualiteFR==0 && $Division==0){
							$SeuilatAvecArret="";
						}
					}
					
					if($Seuilpdv<>""){
						if($CoordProjet==1 || $RespProjet==1 || $RespUER==1){
							if($DirOperation==1 || $RespQualiteFR==1 || $Division==1){$Seuilpdv="";}
						}
						elseif($HSE==1){
						}
						else{
							$Seuilpdv="";
						}
					}
					
					$Seuilsecurite=$SeuilatSansArret;
					if($SeuilatAvecArret<>""){
						if($Seuilsecurite<>""){$Seuilsecurite.="<br>";}
						$Seuilsecurite.=$SeuilatAvecArret;
					}
					if($Seuilpdv<>""){
						if($Seuilsecurite<>""){$Seuilsecurite.="<br>";}
						$Seuilsecurite.=$Seuilpdv;
					}
					
					if($Seuilsatis3<>""){
						if($CoordProjet==1 || $RespProjet==1 || $RespUER==1){
							if($DirOperation==1 || $RespQualiteFR==1 || $Division==1){$Seuilsatis3="";}
						}
						elseif($CQP==1){
							if($RespQualite==1){$Seuilsatis3="";}
						}
						else{
							$Seuilsatis3="";
						}
					}
					
					if($Seuilsatis2<>""){
						if($RespQualite==0 && $DirOperation==0 && $RespQualiteFR==0 && $Division==0){
							$Seuilsatis2="";
						}
					}
					
					if($Seuilnc2<>""){
						if($CoordProjet==1 || $RespProjet==1 || $RespUER==1){
							if($DirOperation==1 || $RespQualiteFR==1 || $Division==1){$Seuilnc2="";}
						}
						elseif($CQP==1){
							if($RespQualite==1){$Seuilnc2="";}
						}
						else{
							$Seuilnc2="";
						}
					}
					
					if($Seuilnc3<>""){
						if($RespQualite==0 && $DirOperation==0 && $RespQualiteFR==0 && $Division==0){
							$Seuilnc3="";
						}
					}
					
					$Seuilnc=$Seuilsatis3;
					if($Seuilsatis2<>""){
						if($Seuilnc<>""){$Seuilnc.="<br>";}
						$Seuilnc.=$Seuilsatis2;
					}
					if($Seuilnc2<>""){
						if($Seuilnc<>""){$Seuilnc.="<br>";}
						$Seuilnc.=$Seuilnc2;
					}
					if($Seuilnc3<>""){
						if($Seuilnc<>""){$Seuilnc.="<br>";}
						$Seuilnc.=$Seuilnc3;
					}
					
					if($Seuilcharge<>"" || $Seuilproductivite<>"" || $Seuilmanagement<>"" || $Seuilotd<>"" || $Seuiloqd<>""  || $Seuilcompetence<>"" || $Seuilsecurite<>"" || $Seuilnc<>""){
						$table2.="<tr>
							<td style='border:1px solid black;text-align:center;'>".$laPresta[1]."</td>
							<td style='border:1px solid black;text-align:center;'>".$laPresta[2]."</td>
							<td style='border:1px solid black;text-align:center;font-weight:bold;'>".$Seuilcharge."</td>
							<td style='border:1px solid black;text-align:center;font-weight:bold;'>".$Seuilproductivite."</td>";
						if($Seuilmanagement=="O"){$table2.="<td style='border:1px solid black;background-color:#ebbd7b;text-align:center;font-weight:bold;'></td>";}
						elseif($Seuilmanagement=="R"){$table2.="<td style='border:1px solid black;background-color:#db2020;text-align:center;font-weight:bold;'></td>";}
						elseif($Seuilmanagement=="X"){$table2.="<td style='border:1px solid black;background-color:#db2020;text-align:center;font-weight:bold;'>2 consécutifs</td>";}
						else{$table2.="<td style='border:1px solid black;text-align:center;'></td>";}
						$table2.="
							<td style='border:1px solid black;text-align:center;font-weight:bold;'>".$Seuilotd."</td>
							<td style='border:1px solid black;text-align:center;font-weight:bold;'>".$Seuiloqd."</td>
							<td style='border:1px solid black;text-align:center;font-weight:bold;'>".$Seuilcompetence."</td>
							<td style='border:1px solid black;text-align:center;font-weight:bold;'>".$Seuilsecurite."</td>
							<td style='border:1px solid black;text-align:center;font-weight:bold;'>".$Seuilnc."</td>
						</tr>";
					}
				}
				$table3="</table>";
				
				
				
				if($table2<>""){
					$sujet="Alerte RECORD - Seuil dépassé - ".$MoisLettre[$_SESSION['MORIS_Mois2']-1]." ".$_SESSION['MORIS_Annee2'];
					$message_html="	<html>
						<head><title>".$sujet."</title></head>
						<body>
							Bonjour,
							<br>
							Les activités suivantes ont au moins un seuil dépassé pour le mois de ".$MoisLettre[$_SESSION['MORIS_Mois2']-1]." ".$_SESSION['MORIS_Annee2']."
							<br><br>
							".$table.$table2.$table3."
							<br>
							Bonne journée,<br>
							L'Extranet Daher industriel services DIS.
						</body>
					</html>";
					$Emails=$row['EmailPro'];
					mail($Emails,$sujet,$message_html,$Headers,'-f extranet@aaa-aero.com');
				}
			}
		}
	}
}


echo "<script>window.close();</script>";

?>
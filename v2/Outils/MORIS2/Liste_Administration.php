<script language="javascript">
	function OuvreFenetreModif(Id)
		{
			var w= window.open("Ajout_Prestation.php?Mode=M&Id="+Id,"PageLieu","status=no,menubar=no,width=800,height=250");
			w.focus();
		}
	function OuvreFenetreExcel()
			{window.open("Export_SuiviEnregistrement.php","PageExcel","status=no,menubar=no,width=900,height=450");}
	function AnneeExcel()
			{window.open("Export_SuiviEnregistrementAnnee.php","PageExcel","status=no,menubar=no,width=900,height=450");}
</script>
<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
<table align="center" width="100%" cellpadding="0" cellspacing="0">
<?php 
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
		AND (SELECT COUNT(DateDebut) 
			FROM moris_datesuivi 
			WHERE Id_Prestation=new_competences_prestation.Id
			AND Suppr=0 
			AND DateDebut<='".date('Y-m-d')."'
			AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01')
			)>0
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
?>
	<tr><td height="10"></td></tr>
	<?php if($_SESSION['Id_Personne']==1351 || $_SESSION['Id_Personne']==2526){ ?>
	<tr>
		<td align="right">
			<?php if($_SESSION['Id_Personne']==1351 || $_SESSION['Id_Personne']==2526){ ?>
			&bull; <a href="javascript:OuvreFenetre2('AlerteAbsenceInformation','N2');" style="color:black;"><?php if($LangueAffichage=="FR"){echo "Alerte - absence informations - Coor. Equipe";}else{echo "Alert - lack of information - Team coordinator";} ?></a>&nbsp;&nbsp;&nbsp;<br>
			&bull; <a href="javascript:OuvreFenetre2('AlerteAbsenceInformation','RespProjet');" style="color:black;"><?php if($LangueAffichage=="FR"){echo "Alerte - absence informations - Resp. projet";}else{echo "Alert - lack of information - Project manager";} ?></a>&nbsp;&nbsp;&nbsp;<br>
			&bull; <a href="javascript:OuvreFenetre('AlerteSeuilDepasse');" style="color:black;"><?php if($LangueAffichage=="FR"){echo "Alerte - seuil dépassé";}else{echo "Alert - threshold exceeded";} ?></a>&nbsp;&nbsp;&nbsp;
			<?php }
			?>
		</td>
	</tr>
	<?php } ?>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:90%;">
			<tr>
				<td class="Libelle" width="25%">
					&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Year";}else{echo "Année";} ?>&nbsp;&nbsp;
					<select id="annee" name="annee" onchange="submit();">
						<?php
							$annee=$_SESSION['MORIS_Annee2'];
							if($_POST){$annee=$_POST['annee'];}
							$_SESSION['MORIS_Annee2']=$annee;
						?>
						<option value="<?php echo date('Y')-1; ?>" <?php if($annee==date('Y')-1){echo "selected";} ?>><?php echo date('Y')-1; ?></option>
						<option value="<?php echo date('Y'); ?>" <?php if($annee==date('Y')){echo "selected";} ?>><?php echo date('Y'); ?></option>
						<option value="<?php echo date('Y')+1; ?>" <?php if($annee==date('Y')+1){echo "selected";} ?>><?php echo date('Y')+1; ?></option>
					</select>
				</td>
				<td class="Libelle" width="25%">
					&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Month";}else{echo "Mois";} ?>&nbsp;&nbsp;
					<select id="mois" name="mois" onchange="submit();">
						<?php
							if($_SESSION["Langue"]=="EN"){
								$arrayMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
								
							}
							else{
								$arrayMois=array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
							}
							$mois=$_SESSION['MORIS_Mois2'];
							if($_POST){$mois=$_POST['mois'];}
							$_SESSION['MORIS_Mois2']=$mois;
							
							for($i=0;$i<=11;$i++){
								$numMois=$i+1;
								if($numMois<10){$numMois="0".$numMois;}
								echo "<option value='".$numMois."'";
								if($mois== ($i+1)){echo " selected ";}
								echo ">".$arrayMois[$i]."</option>\n";
							}
						?>
					</select>
				</td>
				<td width="10%" class="Libelle" <?php if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne=".$IdPersonneConnectee))==0){echo "style='display:none;'";} ?>>
					<?php 
						$voirTout=$_SESSION['FiltreRECORD_VoirTout'];
						if($_POST){if(isset($_POST['voirTout'])){$voirTout="1";}else{$voirTout="";}}
						$_SESSION['FiltreRECORD_VoirTout']=$voirTout;
					?>
					<input type="checkbox" name="voirTout" id="voirTout" onclick="submit()" <?php if($_SESSION['FiltreRECORD_VoirTout']<>""){echo "checked";} ?> />
					&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Voir tout";}else{echo "See everything";} ?>
				</td>
				<td width="25%" class="Libelle">
					&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "UER/Dept/Filiale :";}else{echo "UER/Department/Subsidiary :";} ?>
					<select class="plateforme" style="width:200px;" name="plateforme" onchange="submit();">
						<option value="0" selected></option>
					<?php
					$req="SELECT DISTINCT new_competences_plateforme.Id,
						new_competences_plateforme.Libelle
						FROM new_competences_prestation
						LEFT JOIN new_competences_plateforme
						ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
						WHERE new_competences_prestation.UtiliseMORIS=1
						AND new_competences_prestation.Id_Plateforme>0 ";
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
					$req.="ORDER BY new_competences_plateforme.Libelle;";
					$resultPlat=mysqli_query($bdd,$req);
					$nbPlat=mysqli_num_rows($resultPlat);
					
					$PlateformeSelect = 0;
					$Selected = "";
					
					$PlateformeSelect=$_SESSION['FiltreRHRepartitionAAA_Plateforme'];
					if($_POST){$PlateformeSelect=$_POST['plateforme'];}
					$_SESSION['FiltreRHRepartitionAAA_Plateforme']=$PlateformeSelect;	
					
					if ($nbPlat > 0)
					{
						while($row=mysqli_fetch_array($resultPlat))
						{
							$selected="";
							if($PlateformeSelect==$row['Id']){$selected="selected";}
							echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
						}
					 }
					 ?>
					</select>
				</td>
				<td width="15%">
					&nbsp;&nbsp;&nbsp;
					<a href="javascript:OuvreFenetreExcel()">
					<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
					</a>
					&nbsp;&nbsp;&nbsp;
					<a class="Bouton" href="javascript:AnneeExcel()">
					Excel <?php if($_SESSION['Langue']=="EN"){echo "Year";}else{echo "Année";} ?>
					</a>
				</td>
			</tr>
		</table>
	</td></tr>
	<tr><td height="5"></td></tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:90%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="8%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Operating unit";}else{echo "Unité d'exploitation";} ?></td>
				<td class="EnTeteTableauCompetences" width="8%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Site";}else{echo "Prestation";} ?></td>
				<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;" ><?php if($_SESSION['Langue']=="EN"){echo "Recorded<br>data";}else{echo "Données<br>enregistrées";} ?></td>
				<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;"><?php if($_SESSION['Langue']=="EN"){echo "Locked<br>data";}else{echo "Données<br>verrouillées";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;">CHARGE / CAPA</td>
				<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;">PROD</td>
				<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;">OTD</td>
				<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;">OQD</td>
				<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;">MANAGEMENT</td>
				<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;">COMPETENCES (POLYV)</td>
				<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;">COMPETENCES (QUALIF)</td>
				<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;">SECURITE (PDV)</td>
				<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;">SECURITE (AT)</td>
				<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;">PRM & SATIS (PRM)</td>
				<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;">PRM & SATIS (SATIS)</td>
				<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;">NC/RC</td>
				<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;"></td>
			</tr>
		</table>
		<div style="width:100%;height:400px;overflow:auto;">
		<table  align="center" width="90%">
			<?php
				$moisEC=date($_SESSION['MORIS_Annee2']."-".$_SESSION['MORIS_Mois2']."-1");
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
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						$enregistre="<img width='15px' src='../../Images/delete.png' border='0' />";
						$verrouille="<img width='15px' src='../../Images/delete.png' border='0' />";
						$envoyer="<img width='25px' src='../../Images/email.png' border='0' />";
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
							$enregistre="<img width='15px' src='../../Images/tick.png' border='0' />";
						}
						else{
							$charge="<img width='15px' src='../../Images/delete.png' border='0' />";
							$productivite="<img width='15px' src='../../Images/delete.png' border='0' />";
							$otd="<img width='15px' src='../../Images/delete.png' border='0' />";
							$oqd="<img width='15px' src='../../Images/delete.png' border='0' />";
							$management="<img width='15px' src='../../Images/delete.png' border='0' />";
							$polyv="<img width='15px' src='../../Images/delete.png' border='0' />";
							$qualif="<img width='15px' src='../../Images/delete.png' border='0' />";
							$pdv="<img width='15px' src='../../Images/delete.png' border='0' />";
							$at="<img width='15px' src='../../Images/delete.png' border='0' />";
							$prm="<img width='15px' src='../../Images/delete.png' border='0' />";
							$satis="<img width='15px' src='../../Images/delete.png' border='0' />";
							$nc="N/A";
						}
						if($row['Verouillage']==1){
							$verrouille="<img width='15px' src='../../Images/tick.png' border='0' />";
							$envoyer="";
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
							$charge="<img width='15px' src='../../Images/delete.png' border='0' />";
							$productivite="<img width='15px' src='../../Images/delete.png' border='0' />";
							$otd="<img width='15px' src='../../Images/delete.png' border='0' />";
							$oqd="<img width='15px' src='../../Images/delete.png' border='0' />";
							$management="<img width='15px' src='../../Images/delete.png' border='0' />";
							$polyv="<img width='15px' src='../../Images/delete.png' border='0' />";
							$qualif="<img width='15px' src='../../Images/delete.png' border='0' />";
							$pdv="<img width='15px' src='../../Images/delete.png' border='0' />";
							$at="<img width='15px' src='../../Images/delete.png' border='0' />";
							$prm="<img width='15px' src='../../Images/delete.png' border='0' />";
							$satis="<img width='15px' src='../../Images/delete.png' border='0' />";
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
									
									if($row2['RefPdp']<>"" && $row2['DateValidite']>"0001-01-01"){$pdv="<img width='15px' src='../../Images/tick.png' border='0' />";}
									elseif($row2['RefPdp']=="" && $row2['DateValidite']>"0001-01-01"){$pdv="<span style='background-color:#faa04c;'>&nbsp;&nbsp;&nbsp;&nbsp;P&nbsp;&nbsp;&nbsp;&nbsp;</span>";}
									elseif($row2['RefPdp']<>"" && $row2['DateValidite']<="0001-01-01"){$pdv="<span style='background-color:#faa04c;'>&nbsp;&nbsp;&nbsp;&nbsp;P&nbsp;&nbsp;&nbsp;&nbsp;</span>";}
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
									if($row2['InterneCurrent']>0 || $row2['SubContractorCurrent']>0 || $row2['PasActivite']==1){$charge="<img width='15px' src='../../Images/tick.png' border='0' />";}
								}
								
								if($row['ProductiviteADesactive']==1){
									$productivite="N/A";
								}
								else{
									if($row2['TempsAlloue']>0 || $row2['TempsPasse']>0 || $row2['TempsObjectif']>0 || $row2['PasActivite']==1){$productivite="<img width='15px' src='../../Images/tick.png' border='0' />";}
								}
								
								if($row['OTDOQDADesactive']==1){
									$otd="N/A";
								}
								else{
									if($row2['PasOTD']==1){
										$otd="<img width='15px' src='../../Images/tick.png' border='0' />";
									}
									else{
										if($row2['PasActivite']==1){
											$otd="<img width='15px' src='../../Images/tick.png' border='0' />";
										}
										else{
											if((($row2['NbLivrableConformeOTD']>0 || $row2['NbLivrableToleranceOTD']>0 || $row2['NbRetourClientOTD']>0) && $row2['ObjectifClientOTD']>0)){
												$ratio=round(($row2['NbLivrableConformeOTD']/($row2['NbLivrableConformeOTD']+$row2['NbLivrableToleranceOTD']+$row2['NbRetourClientOTD']))*100,2);
												if(($ratio>=$row2['ObjectifClientOTD']) || ($ratio<$row2['ObjectifClientOTD'] && $row2['CauseOTD']<>"" && $row2['ActionOTD']<>"")){
													$otd="<img width='15px' src='../../Images/tick.png' border='0' />";
												}
												else{
													$otd="<span style='background-color:#faa04c;'>&nbsp;&nbsp;&nbsp;&nbsp;P&nbsp;&nbsp;&nbsp;&nbsp;</span>";
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
										$oqd="<img width='15px' src='../../Images/tick.png' border='0' />";
									}
									else{
										if($row2['PasActivite']==1){
											$oqd="<img width='15px' src='../../Images/tick.png' border='0' />";
										}
										else{
											if((($row2['NbLivrableConformeOQD']>0 || $row2['NbLivrableToleranceOQD']>0 || $row2['NbRetourClientOQD']>0) && $row2['ObjectifClientOQD']>0)){
												$ratio=round(($row2['NbLivrableConformeOQD']/($row2['NbLivrableConformeOQD']+$row2['NbLivrableToleranceOQD']+$row2['NbRetourClientOQD']))*100,2);
												if(($ratio>=$row2['ObjectifClientOQD']) || ($ratio<$row2['ObjectifClientOQD'] && $row2['CauseOQD']<>"" && $row2['ActionOQD']<>"")){
													$oqd="<img width='15px' src='../../Images/tick.png' border='0' />";
												}
												else{
													$oqd="<span style='background-color:#faa04c;'>&nbsp;&nbsp;&nbsp;&nbsp;P&nbsp;&nbsp;&nbsp;&nbsp;</span>";
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
										$management="<img width='15px' src='../../Images/tick.png' border='0' />";
									}
								}
								
								if($row['CompetenceADesactive']==1 || $row['PolyvalenceADesactive']==1){
									$polyv="N/A";
								}
								else{
									if($row2['NbXTableauPolyvalence']>0 || $row2['NbLTableauPolyvalence']>0 || $row2['PasActivite']==1){
										$polyv="<img width='15px' src='../../Images/tick.png' border='0' />";
									}
								}
								
								if($row['CompetenceADesactive']==1){
									$qualif="N/A";
								}
								else{
									if($row2['TauxQualif']>0 || $row2['PasActivite']==1){
										$qualif="<img width='15px' src='../../Images/tick.png' border='0' />";
									}
								}
								
								if($row['PRMADesactive']==1){
									$prm="N/A";
									$satis="N/A";
								}
								else{
									if($row2['PeriodicitePRM']=="Pas de PRM" || $row2['PasActivite']==1){
										$prm="<img width='15px' src='../../Images/tick.png' border='0' />";
									}
									elseif($row2['DerniereDatePRM']>0 && $row2['PeriodicitePRM']<>""){
										$prm="<img width='15px' src='../../Images/tick.png' border='0' />";
									}
									
									if($row2['DateEnvoiDemandeSatisfaction']>"0001-01-01" || $row2['PasActivite']==1){
										$satis="<img width='15px' src='../../Images/tick.png' border='0' />";
									}
								}
								
								if($row['SecuriteADesactive']==1 || ($row['SecuriteADesactive']==0 && $_SESSION['MORIS_Annee2'].'_'.$_SESSION['MORIS_Mois2']>"2023_06")){
									$at="N/A";
								}
								else{
									if($row2['PasAT']==1 || $row2['PasActivite']==1){
										$at="<img width='15px' src='../../Images/tick.png' border='0' />";
									}
									else{
										$req="SELECT Id
											FROM moris_moisprestation_securite 
											WHERE Suppr=0 
											AND Id_MoisPrestation=".$row2['Id']." ";
										$resultAT=mysqli_query($bdd,$req);
										$nbResultaAT=mysqli_num_rows($resultAT);
										if($nbResultaAT>0){
											$at="<img width='15px' src='../../Images/tick.png' border='0' />";
										}
									}
								}
								
								if($row['NCADesactive']==1){
									$nc="N/A";
								}
								else{
									if($row2['PasNC']==1 || $row2['PasActivite']==1){
										$nc="<img width='15px' src='../../Images/tick.png' border='0' />";
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
											$nc="<img width='15px' src='../../Images/tick.png' border='0' />";
										}
									}
								}
								
								if($row2['PasActivite']==1){
									$pdv="<img width='15px' src='../../Images/tick.png' border='0' />";
								}
							}
						}
						
						$presta=substr($row['Libelle'],0,strpos($row['Libelle']," "));
						?>
						<tr bgcolor="<?php echo $couleur;?>">
							<td width="8%">&nbsp;<?php echo $row['Plateforme'];?></td>
							<td width="8%"><?php echo $presta; ?></td>
							<td style="text-align:center;" width="6%"><?php echo $enregistre;?></td>
							<td style="text-align:center;" width="6%"><?php echo $verrouille;?></td>
							<td style="text-align:center;" width="5%"><?php echo $charge;?></td>
							<td style="text-align:center;" width="5%"><?php echo $productivite;?></td>
							<td style="text-align:center;" width="5%"><?php echo $otd;?></td>
							<td style="text-align:center;" width="5%"><?php echo $oqd;?></td>
							<td style="text-align:center;" width="5%"><?php echo $management;?></td>
							<td style="text-align:center;" width="5%"><?php echo $polyv;?></td>
							<td style="text-align:center;" width="5%"><?php echo $qualif;?></td>
							<td style="text-align:center;" width="5%"><?php echo $pdv;?></td>
							<td style="text-align:center;" width="5%"><?php echo $at;?></td>
							<td style="text-align:center;" width="5%"><?php echo $prm;?></td>
							<td style="text-align:center;" width="5%"><?php echo $satis;?></td>
							<td style="text-align:center;" width="5%"><?php echo $nc;?></td>
						</tr>
						<?php
						if($couleur=="#ffffff"){$couleur="#a3e4ff";}
						else{$couleur="#ffffff";}
					}
				}
			?>
		</table>
		</div>
	</td></tr>
</table>
<script>
	function Affiche_Masque()
{
	var SourceImage = document.getElementById('Image_PlusMoins').src;
	var result = SourceImage.substring(SourceImage.length -6, SourceImage.length);
	
	if(result == "us.gif")
	{
		document.getElementById('Image_PlusMoins').src="../../Images/Moins.gif";
		document.getElementById('Table_ChargeCapa').style.display = "";
	}
	else
	{
		document.getElementById('Image_PlusMoins').src="../../Images/Plus.gif";
		document.getElementById('Table_ChargeCapa').style.display = "none";
	}
}
</script>
<?php $valAfficher="";?>
<table align="center" width="100%" cellpadding="0" cellspacing="0">
<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
	<tr><td height="5"></td></tr>
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<table bgcolor="#ffffff" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" style="border:1px solid black;" align="center">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Year : ";}else{echo "Année : ";} ?>&nbsp;&nbsp;</td>
								<td style="border:1px solid black;">
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
							</tr>
						</table>
					</td>
					<td>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					<td>
						<table bgcolor="#ffffff" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" style="border:1px solid black;" align="center">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Month : ";}else{echo "Mois : ";} ?>&nbsp;&nbsp;</td>
								<td style="border:1px solid black;">
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
							</tr>
						</table>
					</td>
					<td>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					<td>
						<table bgcolor="#ffffff" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" style="border:1px solid black;" align="center">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Site : ";}else{echo "Prestation : ";} ?>&nbsp;&nbsp;</td>
								<td style="border:1px solid black;">
									<select class="prestation" id="prestation" style="width:300px;" name="prestation" onchange="submit();">
									<?php 
										$req="SELECT Id
												FROM new_competences_personne_poste_plateforme
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND Id_Plateforme=17
												AND Id_Poste IN (9,15,27,41,44)";
										$resultRespSG=mysqli_query($bdd,$req);
										$nbRespSG=mysqli_num_rows($resultRespSG);
										
										if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM moris_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0 || $nbRespSG){
											$req="SELECT Id,Libelle,Active
											FROM new_competences_prestation
											WHERE new_competences_prestation.UtiliseMORIS=1
											ORDER BY Libelle;";
										}
										else{
											$req="SELECT Id,Libelle,Active
											FROM new_competences_prestation
											WHERE new_competences_prestation.UtiliseMORIS=1
											AND ((SELECT COUNT(Id) 
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND Id_Prestation=new_competences_prestation.Id 
												AND Id_Poste IN (2,3,4,46)
												)>0
											OR 	
												(SELECT COUNT(Id) 
												FROM new_competences_personne_poste_plateforme
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND Id_Plateforme=new_competences_prestation.Id_Plateforme 
												AND Id_Poste IN (6,27)
												)>0
											OR 
												(SELECT COUNT(Id) 
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION['Id_Personne']."
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=new_competences_prestation.Id_Plateforme 
												AND Id_Poste IN (5)
												)>0
											)
											ORDER BY Libelle;";
										}
										$resultPrestation=mysqli_query($bdd,$req);
										$nbPrestation=mysqli_num_rows($resultPrestation);
										
										$PrestationSelect = 0;
										$Selected = "";
										
										$PrestationSelect=$_SESSION['MORIS_Prestation'];
										if($_POST){$PrestationSelect=$_POST['prestation'];}
										$_SESSION['MORIS_Prestation']=$PrestationSelect;	
										$laPresta="";
										echo "<option value='0' selected></option>\n";
										if ($nbPrestation > 0)
										{
											while($row=mysqli_fetch_array($resultPrestation))
											{
												$selected="";
												if($PrestationSelect<>""){
													if($PrestationSelect==$row['Id']){
														$selected="selected";
														$laPresta.=stripslashes($row['Libelle']);
													}
												}
												$active="";
												if($row['Active']<>0){$active=" [INACTIVE]";}
												echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle']).$active."</option>\n";
											}
										 }
									?>
									</select>
								</td>
							</tr>
						</table>
					</td>
					<td>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					</td>
					<td>
						<table bgcolor="#ffffff" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" style="border:1px solid black;" align="center">
									&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Information ";}else{echo "Informations ";} ?>&nbsp;&nbsp;
								</td>
								<td class="Libelle" style="border:1px solid black;" align="center" id="leHover2">
									<img  style="cursor:pointer;" src='../../Images/FlecheSelect.png' border='0' />
										<span>
										<table width="100%" cellpadding="0" cellspacing="0" align="center" style="border:1px solid black;">
											<?php 
												$req="SELECT Id,Libelle,DateDebut,DateFin,Sigle,ToleranceOTDOQD,
													(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
													(SELECT CONCAT(Num,' - ',Libelle) FROM moris_famille_r03 WHERE Id=Id_FamilleR03) AS FamilleR03,
													(SELECT Libelle FROM moris_client WHERE Id=Id_Client) AS Client,
													Code_Analytique
												FROM new_competences_prestation
												WHERE new_competences_prestation.Id=".$PrestationSelect." ";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												if($nbResulta>0){
												$Ligne=mysqli_fetch_array($result);
												
												$req="SELECT Id,RefCDC,IntituleCDC,AcheteurClient,DonneurOrdre,DateDebut,DateFin,Sigle,
													(SELECT Libelle FROM moris_contrat WHERE Id=Id_Contrat) AS NomContrat,
													(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_CoorEquipe) AS CoorEquipe,
													(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_RespProjet) AS RespProjet,
													PieceJointeSQCDPF,MailAcheteur,MailDO,
													(SELECT Libelle FROM moris_entiteachat WHERE Id=Id_EntiteAchat) AS EntiteAchat,
													(SELECT Libelle FROM moris_contrat WHERE Id=Id_Contrat) AS Contrat,
													Verouillage
												FROM moris_moisprestation
												WHERE moris_moisprestation.Id_Prestation=".$PrestationSelect." 
												AND Annee=".$annee." 
												AND Mois=".$mois."
												AND Suppr=0 											
												";
												$result=mysqli_query($bdd,$req);
												$nbResultaMoisPresta=mysqli_num_rows($result);
												if($nbResultaMoisPresta>0){$LigneMoisPrestation=mysqli_fetch_array($result);}
												
												$moisEC=date($annee."-".$mois."-1");
												$date_11Mois = date("Y-m-d",strtotime($moisEC." -11 month"));
												
												//Productivité
												$objectifProductivite=array();
												$productivite=array();
												$arrayMois=array();
												
												//Management 
												$arrayMoisLettre=array();
												$arrayAnnee=array();
												$arrayManagement=array();
												
												//Compétences 
												$arrayCompetences=array();
												
												//OTD 
												$arrayOTD=array();
												$arrayOTD2=array();

												//OQD 
												$arrayOQD=array();
												$arrayOQD2=array();
												
												//Securite 
												$arraySecurite=array();
												
												//NC / RC
												$arrayNbNC=array();
												$arrayLegendeNC=array("NC Niv 1","NC Niv 2","NC Niv 3","RC");
												
												//PRM												
												$arrayNewPRM=array();
												
												$i=0;
												$laDate=$date_11Mois;

												//Besoin staffing 
												$arrayBesoin=array();
												
												$causesIdentifieesOTD="";
												$actionsLanceesOTD="";
												$causesIdentifieesOQD="";
												$actionsLanceesOQD="";
												$evenements="";
												$accidents="";
												$listeNCDACTotal="";
												
												$anneeDuJour=$_SESSION['MORIS_Annee2'];
												$moisDuJour=$_SESSION['MORIS_Mois2'];
												
												$anneeDuJourReel=date("Y",strtotime(date('Y-m-1')." -1 month"));
												$moisDuJourReel=date("m",strtotime(date('Y-m-1')." -1 month"));
												
												
												$anneeDuJour1=date("Y",strtotime(date('Y-m-1')." 0 month"));
												$moisDuJour1=date("m",strtotime(date('Y-m-1')." 0 month"));
												$anneeDuJour2=date("Y",strtotime(date('Y-m-1')." +1 month"));
												$moisDuJour2=date("m",strtotime(date('Y-m-1')." +1 month"));
												$anneeDuJour3=date("Y",strtotime(date('Y-m-1')." +2 month"));
												$moisDuJour3=date("m",strtotime(date('Y-m-1')." +2 month"));
												$anneeDuJour4=date("Y",strtotime(date('Y-m-1')." +3 month"));
												$moisDuJour4=date("m",strtotime(date('Y-m-1')." +3 month"));
												$anneeDuJour5=date("Y",strtotime(date('Y-m-1')." +4 month"));
												$moisDuJour5=date("m",strtotime(date('Y-m-1')." +4 month"));
												$anneeDuJour6=date("Y",strtotime(date('Y-m-1')." +5 month"));
												$moisDuJour6=date("m",strtotime(date('Y-m-1')." +5 month"));
												$anneeDuJour7=date("Y",strtotime(date('Y-m-1')." 6 month"));
												$moisDuJour7=date("m",strtotime(date('Y-m-1')." 6 month"));

												$bFamilleIndefini=0;
												$listeFamilleIndefini="";
												$listeFamilleIndefini2="";
												if($_POST && (isset($_POST['btn_actualiserFam']) || isset($_POST['btn_actualiserOTD']) || isset($_POST['btn_actualiserOQD']))){
													if(isset($_POST['Famille_0'])){$bFamilleIndefini=1;}
												}
												else{
													$bFamilleIndefini=1;
												}

												$annee3Mois=date("Y",strtotime($date_11Mois." +9 month"));
												$mois3Mois=date("m",strtotime($date_11Mois." +9 month"));
												
												$annee6Mois=date("Y",strtotime($date_11Mois." +15 month"));
												$mois6Mois=date("m",strtotime($date_11Mois." +15 month"));
												$req="SELECT DISTINCT Id_Famille
													FROM moris_moisprestation_famille
													LEFT JOIN moris_moisprestation
													ON moris_moisprestation_famille.Id_MoisPrestation=moris_moisprestation.Id
													WHERE moris_moisprestation.Suppr=0
													AND Id_Prestation=".$PrestationSelect." ";
													if($annee3Mois.'_'.$mois3Mois>$anneeDuJourReel.'_'.$moisDuJourReel){
														$req.="AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))>='".$anneeDuJourReel.'_'.$moisDuJourReel."' ";
													}
													else{
														$req.="AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))>='".$annee3Mois.'_'.$mois3Mois."' ";
													}
														
													$req.="AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))<='".$annee6Mois.'_'.$mois6Mois."'
													";
												$resultFamille=mysqli_query($bdd,$req);
												$nbResultaFamille=mysqli_num_rows($resultFamille);
												
												if($nbResultaFamille>0){
													while($rowFamille=mysqli_fetch_array($resultFamille)){
														if($listeFamilleIndefini2<>""){$listeFamilleIndefini2.=",";}
														$listeFamilleIndefini2.=$rowFamille['Id_Famille'];
														
														if($_POST && 
															(
															isset($_POST['btn_actualiserFam']) 
															|| isset($_POST['btn_actualiserOTD']) 
															|| isset($_POST['btn_actualiserOQD'])
															)
														){
															if(isset($_POST['Famille_'.$rowFamille['Id_Famille']])){
																if($listeFamilleIndefini<>""){$listeFamilleIndefini.=",";}
																$listeFamilleIndefini.=$rowFamille['Id_Famille'];
															}
														}
														else{
															if($listeFamilleIndefini<>""){$listeFamilleIndefini.=",";}
															$listeFamilleIndefini.=$rowFamille['Id_Famille'];
														}
													}
												}
												
												if($bFamilleIndefini==0 && $listeFamilleIndefini==""){
													$bFamilleIndefini=1;
													$listeFamilleIndefini=$listeFamilleIndefini2;
													if($listeFamilleIndefini==""){$listeFamilleIndefini="0";}
												}
												if($listeFamilleIndefini==""){$listeFamilleIndefini="0";}
												$_SESSION['MORIS_ListeFamilleIndefini']=$listeFamilleIndefini;
												
												for($nbMois=1;$nbMois<=12;$nbMois++){
													$anneeEC=date("Y",strtotime($laDate." +0 month"));
													$moisEC=date("m",strtotime($laDate." +0 month"));
													
													$arrayMois[$i]=$MoisLettre[$moisEC-1]."<br>".date("y",strtotime($laDate." +0 month"));
													$arrayMoisLettre[$i]=$MoisLettre2[$moisEC-1];
													$arrayAnnee[$i]=$anneeEC;
													
													$mois_6Mois=date("m",strtotime($laDate." -6 month"));
													$annee_6Mois=date("Y",strtotime($laDate." -6 month"));
													
													$req="SELECT Id,";
													if($bFamilleIndefini==1){$req.="PermanentCurrent+TemporyCurrent+InterneCurrent+";}
													$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.") ),0) AS InterneCurrent,";
													if($bFamilleIndefini==1){$req.="SubContractorCurrent+";}
													$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS SubContractorCurrent,";
													$req.="
													IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',
														PermanentCurrent+TemporyCurrent+InterneCurrent,
														COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.") ),0)) AS CapaInterne,
													IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',
														SubContractorCurrent,
														COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.") ),0)) AS CapaExterne,
													IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',
														PermanentCurrent+TemporyCurrent+InterneCurrent+SubContractorCurrent,
														COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0)) AS CapaTotal,
													TempsAlloue,TempsPasse,TempsObjectif,
													ObjectifClientOTD,NbLivrableOTD,NbRetourClientOTD,NbTotalLivrableOTD,CauseOTD,ActionOTD,OTD,ModeCalculOTD,
													ObjectifClientOQD,NbLivrableOQD,NbRetourClientOQD,NbTotalLivrableOQD,CauseOQD,ActionOQD,OQD,ModeCalculOQD,
													TendanceManagement,EvenementManagement,NbLivrableToleranceOTD,NbLivrableToleranceOQD,
													IF(Annee<2022,NbLivrableOTD-NbRetourClientOTD,NbLivrableConformeOTD) AS NbLivrableConformeOTD,
													IF(Annee<2022,NbLivrableOQD-NbRetourClientOQD,NbLivrableConformeOQD) AS NbLivrableConformeOQD,
													PieceJointeSQCDPF,ObjectifToleranceOTD,ObjectifToleranceOQD,
													(SELECT ProductiviteADesactive FROM new_competences_prestation WHERE Id=Id_Prestation) AS ProductiviteADesactive,
													PasOTD,PasOQD,PasActivite,
													NbXTableauPolyvalence,NbLTableauPolyvalence,TauxQualif,NbMonoCompetence,
													DerniereDatePRM,DerniereDateEvaluation,ProchaineDatePRM,PeriodicitePRM,PieceJointeSatisfactionPRM,PieceJointeDernierePRM,
													EvaluationQualite,EvaluationDelais,EvaluationCompetencePersonnel,EvaluationAutonomie,EvaluationAnticipation,EvaluationCommunication,
													Verouillage
													FROM moris_moisprestation
													WHERE moris_moisprestation.Id_Prestation=".$PrestationSelect." 
													AND Annee=".$anneeEC." 
													AND Mois=".$moisEC."
													AND Suppr=0 											
													";
													$productiviteBrut=0;
													$productiviteCorrigee=0;
													$objectif=1;
													$resultEC=mysqli_query($bdd,$req);
												
													$nbResultaMoisPrestaEC=mysqli_num_rows($resultEC);
													
													if($nbResultaMoisPrestaEC>0){
														$LigneMoisPrestationEC=mysqli_fetch_array($resultEC);
														if($LigneMoisPrestationEC['ProductiviteADesactive']==0 && $LigneMoisPrestationEC['PasActivite']==0){
															if($LigneMoisPrestationEC['TempsPasse']>0  && $LigneMoisPrestationEC['TempsAlloue']>0 && $LigneMoisPrestationEC['TempsObjectif']>0){
																$productiviteCorrigee=round($LigneMoisPrestationEC['TempsAlloue']/$LigneMoisPrestationEC['TempsPasse'],2);
																$productiviteBrut=round($LigneMoisPrestationEC['TempsObjectif']/$LigneMoisPrestationEC['TempsPasse'],2);
															}
															else{
																$productiviteCorrigee=null;
																$productiviteBrut=null;
															}
														}
														$objectif=1;
														if($LigneMoisPrestationEC['PasActivite']==0){
															$arrayManagement[$i]=$LigneMoisPrestationEC['TendanceManagement'];
														}
														else{
															$arrayManagement[$i]=-1;
														}
														
														if($LigneMoisPrestationEC['NbXTableauPolyvalence']>0 || $LigneMoisPrestationEC['NbLTableauPolyvalence']>0){
															$arrayCompetences[$i]= array("Mois" => "".$MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month"))."","Competences" => round(($LigneMoisPrestationEC['NbXTableauPolyvalence']/($LigneMoisPrestationEC['NbXTableauPolyvalence']+$LigneMoisPrestationEC['NbLTableauPolyvalence']))*100,2),"TauxQualif" =>$LigneMoisPrestationEC['TauxQualif'],"NbMonoCompeteneces" =>$LigneMoisPrestationEC['NbMonoCompetence']);
														}else{
															$arrayCompetences[$i]=array("Mois" => "".$MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month"))."","Competences" => 0,"TauxQualif" =>$LigneMoisPrestationEC['TauxQualif'],"NbMonoCompeteneces" =>$LigneMoisPrestationEC['NbMonoCompetence']);
														}
														
														$Conforme=0;
														$ConformeT=0;
														$NonConforme=0;
														$Tolerance=0;
														$Conforme3=0;
														$NonConforme3=0;
														$Tolerance3=0;
														$causesIdentifieesOTD="";
														$actionsLanceesOTD="";
														if($LigneMoisPrestationEC['PasOTD']==0 && $LigneMoisPrestationEC['PasActivite']==0){
															if(($LigneMoisPrestationEC['NbLivrableConformeOTD']+$LigneMoisPrestationEC['NbLivrableToleranceOTD']+$LigneMoisPrestationEC['NbRetourClientOTD'])>0){
																//Vérifier si seulement quelques livrables à prendre en compte ou tous
																$req="SELECT Libelle, NbLivrableConforme,NbLivrableTolerance,NbRetourClient
																	FROM moris_moisprestation_otdoqd
																	LEFT JOIN moris_moisprestation
																	ON moris_moisprestation_otdoqd.Id_MoisPrestation=moris_moisprestation.Id
																	WHERE bOQD=0 
																	AND PasLivrable=0
																	AND moris_moisprestation.Suppr=0
																	AND (NbLivrableConforme+NbLivrableTolerance+NbRetourClient)>0
																	AND Id_Prestation=".$PrestationSelect." 
																	AND Annee=".$anneeEC." 
																	AND Mois=".$moisEC."
																	";
																$resultOTDLibelle=mysqli_query($bdd,$req);
																$nbResultaOTDLibelle=mysqli_num_rows($resultOTDLibelle);
																if($nbResultaOTDLibelle>0){
																	$Conforme2=0;
																	$NonConforme2=0;
																	$Tolerance2=0;
																	while($rowOTD=mysqli_fetch_array($resultOTDLibelle)){
																		$bCoche=0;
																		if($_POST && (isset($_POST['btn_actualiserFam']) || isset($_POST['btn_actualiserOTD']) || isset($_POST['btn_actualiserOQD']))){
																			if(isset($_POST['OTD_'])){
																				foreach($_POST['OTD_'] as $checkbox)
																				{
																					if($checkbox==stripslashes($rowOTD['Libelle'])){
																						$bCoche=1;
																					}
																				}
																			}
																		}
																		else{
																			$bCoche=1;
																		}
			
																		if($bCoche==1){
																			$Conforme2+=$rowOTD['NbLivrableConforme'];
																			$NonConforme2+=$rowOTD['NbRetourClient'];
																			$Tolerance2+=$rowOTD['NbLivrableTolerance'];
																		}
																	}
																	if(($Conforme2+$Tolerance2+$NonConforme2)>0){
																		$Conforme3=$Conforme2;
																		$Tolerance3=$Tolerance2;
																		$NonConforme3=$NonConforme2;
																		$Conforme=round(($Conforme2/($Conforme2+$Tolerance2+$NonConforme2))*100,1);
																		$ConformeT=round((($Conforme2+$Tolerance2)/($Conforme2+$Tolerance2+$NonConforme2))*100,1);
																		$NonConforme=round(($NonConforme2/($Conforme2+$Tolerance2+$NonConforme2))*100,1);
																		$Tolerance=round(($Tolerance2/($Conforme2+$Tolerance2+$NonConforme2))*100,1);
																	}
																	else{
																		$Conforme3=$LigneMoisPrestationEC['NbLivrableConformeOTD'];
																		$Tolerance3=$LigneMoisPrestationEC['NbLivrableToleranceOTD'];
																		$NonConforme3=$LigneMoisPrestationEC['NbRetourClientOTD'];
																		$Conforme=round(($LigneMoisPrestationEC['NbLivrableConformeOTD']/($LigneMoisPrestationEC['NbLivrableConformeOTD']+$LigneMoisPrestationEC['NbLivrableToleranceOTD']+$LigneMoisPrestationEC['NbRetourClientOTD']))*100,1);
																		$ConformeT=round((($LigneMoisPrestationEC['NbLivrableConformeOTD']+$LigneMoisPrestationEC['NbLivrableToleranceOTD'])/($LigneMoisPrestationEC['NbLivrableConformeOTD']+$LigneMoisPrestationEC['NbLivrableToleranceOTD']+$LigneMoisPrestationEC['NbRetourClientOTD']))*100,1);
																		$NonConforme=round(($LigneMoisPrestationEC['NbRetourClientOTD']/($LigneMoisPrestationEC['NbLivrableConformeOTD']+$LigneMoisPrestationEC['NbLivrableToleranceOTD']+$LigneMoisPrestationEC['NbRetourClientOTD']))*100,1);
																		$Tolerance=round(($LigneMoisPrestationEC['NbLivrableToleranceOTD']/($LigneMoisPrestationEC['NbLivrableConformeOTD']+$LigneMoisPrestationEC['NbLivrableToleranceOTD']+$LigneMoisPrestationEC['NbRetourClientOTD']))*100,1);
																	}
																}
																else{
																	$Conforme3=$LigneMoisPrestationEC['NbLivrableConformeOTD'];
																	$Tolerance3=$LigneMoisPrestationEC['NbLivrableToleranceOTD'];
																	$NonConforme3=$LigneMoisPrestationEC['NbRetourClientOTD'];
																	$Conforme=round(($LigneMoisPrestationEC['NbLivrableConformeOTD']/($LigneMoisPrestationEC['NbLivrableConformeOTD']+$LigneMoisPrestationEC['NbLivrableToleranceOTD']+$LigneMoisPrestationEC['NbRetourClientOTD']))*100,1);
																	$ConformeT=round((($LigneMoisPrestationEC['NbLivrableConformeOTD']+$LigneMoisPrestationEC['NbLivrableToleranceOTD'])/($LigneMoisPrestationEC['NbLivrableConformeOTD']+$LigneMoisPrestationEC['NbLivrableToleranceOTD']+$LigneMoisPrestationEC['NbRetourClientOTD']))*100,1);
																	$NonConforme=round(($LigneMoisPrestationEC['NbRetourClientOTD']/($LigneMoisPrestationEC['NbLivrableConformeOTD']+$LigneMoisPrestationEC['NbLivrableToleranceOTD']+$LigneMoisPrestationEC['NbRetourClientOTD']))*100,1);
																	$Tolerance=round(($LigneMoisPrestationEC['NbLivrableToleranceOTD']/($LigneMoisPrestationEC['NbLivrableConformeOTD']+$LigneMoisPrestationEC['NbLivrableToleranceOTD']+$LigneMoisPrestationEC['NbRetourClientOTD']))*100,1);
																}
															}
															if($LigneMoisPrestationEC['CauseOTD']<>"" && $LigneMoisPrestationEC['CauseOTD']<>"RAS" && $LigneMoisPrestationEC['CauseOTD']<>"NA"){
																$causesIdentifieesOTD=stripslashes($LigneMoisPrestationEC['CauseOTD']);
															}
															if($LigneMoisPrestationEC['ActionOTD']<>"" && $LigneMoisPrestationEC['ActionOTD']<>"RAS" && $LigneMoisPrestationEC['ActionOTD']<>"NA"){
																$actionsLanceesOTD=stripslashes($LigneMoisPrestationEC['ActionOTD']);
															}
														}
														if($Conforme==0){$Conforme=null;}
														if($ConformeT==0){$ConformeT=null;}
														if($Tolerance==0){$Tolerance=null;}
														if($NonConforme==0){$NonConforme=null;}
														$objectifClient=$LigneMoisPrestationEC['ObjectifClientOTD'];
														if($objectifClient==0){$objectifClient=null;}
														
														$arrayOTD[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"NbConforme" => $Conforme,"NbTolerance" => $Tolerance,"NbRetour" => $NonConforme,"Objectif" => $objectifClient);
														$arrayOTD2[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"NbConforme" => $Conforme3,"NbTolerance" => $Tolerance3,"NbRetour" => $NonConforme3,"Objectif" => $LigneMoisPrestationEC['ObjectifClientOTD'],"Cause" => $causesIdentifieesOTD,"Action" => $actionsLanceesOTD,"Conforme" => $Conforme,"ObjectifT" => $LigneMoisPrestationEC['ObjectifToleranceOTD'],"ConformeT" => $ConformeT);
														
														$Conforme=0;
														$NonConforme=0;
														$Tolerance=0;
														$Conforme3=0;
														$NonConforme3=0;
														$Tolerance3=0;
														$causesIdentifieesOQD="";
														$actionsLanceesOQD="";
														if($LigneMoisPrestationEC['PasOQD']==0 && $LigneMoisPrestationEC['PasActivite']==0){
															if(($LigneMoisPrestationEC['NbLivrableConformeOQD']+$LigneMoisPrestationEC['NbLivrableToleranceOQD']+$LigneMoisPrestationEC['NbRetourClientOQD'])>0){
																//Vérifier si seulement quelques livrables à prendre en compte ou tous
																$req="SELECT Libelle, NbLivrableConforme,NbLivrableTolerance,NbRetourClient
																	FROM moris_moisprestation_otdoqd
																	LEFT JOIN moris_moisprestation
																	ON moris_moisprestation_otdoqd.Id_MoisPrestation=moris_moisprestation.Id
																	WHERE bOQD=1 
																	AND PasLivrable=0
																	AND moris_moisprestation.Suppr=0
																	AND (NbLivrableConforme+NbLivrableTolerance+NbRetourClient)>0
																	AND Id_Prestation=".$PrestationSelect." 
																	AND Annee=".$anneeEC." 
																	AND Mois=".$moisEC."
																	";
																$resultOQDLibelle=mysqli_query($bdd,$req);
																$nbResultaOQDLibelle=mysqli_num_rows($resultOQDLibelle);
																if($nbResultaOQDLibelle>0){
																	$Conforme2=0;
																	$NonConforme2=0;
																	$Tolerance2=0;
																	while($rowOQD=mysqli_fetch_array($resultOQDLibelle)){
																		$bCoche=0;
																		if($_POST && (isset($_POST['btn_actualiserFam']) || isset($_POST['btn_actualiserOTD']) || isset($_POST['btn_actualiserOQD']))){
																			if(isset($_POST['OQD_'])){
																				foreach($_POST['OQD_'] as $checkbox)
																				{
																					if($checkbox==stripslashes($rowOQD['Libelle'])){
																						$bCoche=1;
																					}
																				}
																			}
																		}
																		else{
																			$bCoche=1;
																		}
																		if($bCoche==1){
																			$Conforme2+=$rowOQD['NbLivrableConforme'];
																			$NonConforme2+=$rowOQD['NbRetourClient'];
																			$Tolerance2+=$rowOQD['NbLivrableTolerance'];
																		}
																	}
																	if(($Conforme2+$Tolerance2+$NonConforme2)>0){
																		$Conforme3=$Conforme2;
																		$Tolerance3=$Tolerance2;
																		$NonConforme3=$NonConforme2;
																		$Conforme=round(($Conforme2/($Conforme2+$Tolerance2+$NonConforme2))*100,1);
																		$NonConforme=round(($NonConforme2/($Conforme2+$Tolerance2+$NonConforme2))*100,1);
																		$Tolerance=round(($Tolerance2/($Conforme2+$Tolerance2+$NonConforme2))*100,1);
																	}
																	else{
																		$Conforme3=$LigneMoisPrestationEC['NbLivrableConformeOQD'];
																		$Tolerance3=$LigneMoisPrestationEC['NbLivrableToleranceOQD'];
																		$NonConforme3=$LigneMoisPrestationEC['NbRetourClientOQD'];
																		$Conforme=round(($LigneMoisPrestationEC['NbLivrableConformeOQD']/($LigneMoisPrestationEC['NbLivrableConformeOQD']+$LigneMoisPrestationEC['NbLivrableToleranceOQD']+$LigneMoisPrestationEC['NbRetourClientOQD']))*100,1);
																		$NonConforme=round(($LigneMoisPrestationEC['NbRetourClientOQD']/($LigneMoisPrestationEC['NbLivrableConformeOQD']+$LigneMoisPrestationEC['NbLivrableToleranceOQD']+$LigneMoisPrestationEC['NbRetourClientOQD']))*100,1);
																		$Tolerance=round(($LigneMoisPrestationEC['NbLivrableToleranceOQD']/($LigneMoisPrestationEC['NbLivrableConformeOQD']+$LigneMoisPrestationEC['NbLivrableToleranceOQD']+$LigneMoisPrestationEC['NbRetourClientOQD']))*100,1);
																	}
																}
																else{
																	$Conforme3=$LigneMoisPrestationEC['NbLivrableConformeOQD'];
																	$Tolerance3=$LigneMoisPrestationEC['NbLivrableToleranceOQD'];
																	$NonConforme3=$LigneMoisPrestationEC['NbRetourClientOQD'];
																	$Conforme=round(($LigneMoisPrestationEC['NbLivrableConformeOQD']/($LigneMoisPrestationEC['NbLivrableConformeOQD']+$LigneMoisPrestationEC['NbLivrableToleranceOQD']+$LigneMoisPrestationEC['NbRetourClientOQD']))*100,1);
																	$NonConforme=round(($LigneMoisPrestationEC['NbRetourClientOQD']/($LigneMoisPrestationEC['NbLivrableConformeOQD']+$LigneMoisPrestationEC['NbLivrableToleranceOQD']+$LigneMoisPrestationEC['NbRetourClientOQD']))*100,1);
																	$Tolerance=round(($LigneMoisPrestationEC['NbLivrableToleranceOQD']/($LigneMoisPrestationEC['NbLivrableConformeOQD']+$LigneMoisPrestationEC['NbLivrableToleranceOQD']+$LigneMoisPrestationEC['NbRetourClientOQD']))*100,1);
																}
															}
															if($LigneMoisPrestationEC['CauseOQD']<>"" && $LigneMoisPrestationEC['CauseOQD']<>"RAS" && $LigneMoisPrestationEC['CauseOQD']<>"NA"){
																$causesIdentifieesOQD=stripslashes($LigneMoisPrestationEC['CauseOQD']);
															}
															if($LigneMoisPrestationEC['ActionOQD']<>"" && $LigneMoisPrestationEC['ActionOQD']<>"RAS" && $LigneMoisPrestationEC['ActionOQD']<>"NA"){
																$actionsLanceesOQD=stripslashes($LigneMoisPrestationEC['ActionOQD']);
															}
														}
														if($Conforme==0){$Conforme=null;}
														if($Tolerance==0){$Tolerance=null;}
														if($NonConforme==0){$NonConforme=null;}
														$objectifClient=$LigneMoisPrestationEC['ObjectifClientOQD'];
														if($objectifClient==0){$objectifClient=null;}
														$arrayOQD[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"NbConforme" => $Conforme,"NbTolerance" => $Tolerance,"NbRetour" => $NonConforme,"Objectif" => $objectifClient);
														$arrayOQD2[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"NbConforme" => $Conforme3,"NbTolerance" => $Tolerance3,"NbRetour" => $NonConforme3,"Objectif" => $LigneMoisPrestationEC['ObjectifClientOQD'],"Cause" => $causesIdentifieesOQD,"Action" => $actionsLanceesOQD,"Conforme" => $Conforme,"ObjectifT" => $LigneMoisPrestationEC['ObjectifToleranceOQD']);
														

														if($LigneMoisPrestationEC['EvenementManagement']<>""){
															$evenements.="<td valign='top' align='center'>".stripslashes($LigneMoisPrestationEC['EvenementManagement'])."</td>";
														}
														else{
															$evenements.="<td></td>";
														}
														 
														$req="SELECT Id, Description FROM moris_moisprestation_securite 
															WHERE Suppr=0 
															AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." 
															AND AvecArret=1	
															AND AccidentTrajet=0 
															UNION
															SELECT Id, CommentaireNature AS Description
															FROM rh_personne_at 
															WHERE rh_personne_at.Suppr=0 
															AND rh_personne_at.ArretDeTravail=1
															AND IF(Id_Lieu_AT=4 OR Id_Lieu_AT=6,1,0)=0
															AND rh_personne_at.Id_Prestation=".$PrestationSelect."
															AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))>'2023_06'
															AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))='".$anneeEC."_".$moisEC."'
															";
														$resultSecurite=mysqli_query($bdd,$req);
														$nbAvecArret=mysqli_num_rows($resultSecurite);
														
														if($nbAvecArret>0){
															while($rowAT=mysqli_fetch_array($resultSecurite)){
																$accidents.=$MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month"))." &#x2794; <span style='background-color:#dbb637'>[ATJ]</span> ".stripslashes($rowAT['Description'])."<br>";
															}
														}
														
														$req="SELECT Id,Description FROM moris_moisprestation_securite 
															WHERE Suppr=0 
															AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." 
															AND AvecArret=0
															AND AccidentTrajet=0 
															UNION
															SELECT Id, CommentaireNature AS Description
															FROM rh_personne_at 
															WHERE rh_personne_at.Suppr=0 
															AND rh_personne_at.ArretDeTravail=0
															AND IF(Id_Lieu_AT=4 OR Id_Lieu_AT=6,1,0)=0
															AND rh_personne_at.Id_Prestation=".$PrestationSelect."
															AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))>'2023_06'
															AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))='".$anneeEC."_".$moisEC."'";
														$resultSecurite=mysqli_query($bdd,$req);
														$nbSansArret=mysqli_num_rows($resultSecurite);
														if($nbSansArret>0){
															while($rowAT=mysqli_fetch_array($resultSecurite)){
																$accidents.=$MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month"))." &#x2794; <span style='background-color:#9fb1c5'>[ATAM]</span> ".stripslashes($rowAT['Description'])."<br>";
															}
														}
														
														
														$req="SELECT Id,Description FROM moris_moisprestation_securite 
															WHERE Suppr=0 
															AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." 
															AND AccidentTrajet=1 
															UNION
															SELECT Id, CommentaireNature AS Description
															FROM rh_personne_at 
															WHERE rh_personne_at.Suppr=0 
															AND IF(Id_Lieu_AT=4 OR Id_Lieu_AT=6,1,0)>0
															AND rh_personne_at.Id_Prestation=".$PrestationSelect."
															AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))>'2023_06'
															AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))='".$anneeEC."_".$moisEC."'
															";
														$resultSecurite=mysqli_query($bdd,$req);
														$nbAccidentTrajet=mysqli_num_rows($resultSecurite);
														if($nbAccidentTrajet>0){
															while($rowAT=mysqli_fetch_array($resultSecurite)){
																$accidents.=$MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month"))." &#x2794; <span style='background-color:#3d7ad5'>[AT]</span> ".stripslashes($rowAT['Description'])."<br>";
															}
														}
														
														
														if($nbAccidentTrajet>0 || $nbAvecArret>0 || $nbSansArret>0){
															if($nbAccidentTrajet==0){$nbAccidentTrajet=null;}
															if($nbAvecArret==0){$nbAvecArret=null;}
															if($nbSansArret==0){$nbSansArret=null;}
														}
														else{
															$nbAccidentTrajet=0;
															$nbAvecArret=null;
															$nbSansArret=null;
														}
														
														
														$arraySecurite[$i]=array("Mois" => "".$MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month"))."","NbTrajet" => $nbAccidentTrajet,"NbNonTrajetAvecArret" => $nbAvecArret,"NbNonTrajetSansArret" => $nbSansArret);

														$req="SELECT Ref, Commentaire FROM moris_moisprestation_ncdac
															WHERE Suppr=0 
															AND NC_DAC='NC'
															AND Progression=0
															AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." ";
														$resultNC=mysqli_query($bdd,$req);
														$nbNC=mysqli_num_rows($resultNC);
														$listeNC="";
														if($nbNC>0){
															while($rowNCDAC=mysqli_fetch_array($resultNC)){
																$listeNCDACTotal.=$MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month"))." &#x2794; <span style='color:#3d7ad5'>[NC Niv 1]</span> ".$rowNCDAC['Ref']." : ".stripslashes($rowNCDAC['Commentaire'])."<br>";
																$listeNC.=$rowNCDAC['Ref']."\n";
															}
														}
														
														$req="SELECT Ref, Commentaire FROM moris_moisprestation_ncdac
															WHERE Suppr=0 
															AND NC_DAC='NC Niv 2'
															AND Progression=0
															AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." ";
														$resultDAC=mysqli_query($bdd,$req);
														$nbNC2=mysqli_num_rows($resultDAC);
														$listeNC2="";
														if($nbNC2>0){
															while($rowNCDAC=mysqli_fetch_array($resultDAC)){
																$listeNCDACTotal.=$MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month"))." &#x2794; <span style='color:#3d7ad5'>[NC Niv 2]</span> ".$rowNCDAC['Ref']." : ".stripslashes($rowNCDAC['Commentaire'])."<br>";
																$listeNC2.=$rowNCDAC['Ref']."\n";
															}
														}
														
														$req="SELECT Ref, Commentaire FROM moris_moisprestation_ncdac
															WHERE Suppr=0 
															AND NC_DAC='NC Niv 3'
															AND Progression=0
															AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." ";
														$resultDAC=mysqli_query($bdd,$req);
														$nbNC3=mysqli_num_rows($resultDAC);
														$listeNC3="";
														if($nbNC3>0){
															while($rowNCDAC=mysqli_fetch_array($resultDAC)){
																$listeNCDACTotal.=$MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month"))." &#x2794; <span style='color:#3d7ad5'>[NC Niv 3]</span> ".$rowNCDAC['Ref']." : ".stripslashes($rowNCDAC['Commentaire'])."<br>";
																$listeNC3.=$rowNCDAC['Ref']."\n";
															}
														}
														
														$req="SELECT Ref, Commentaire FROM moris_moisprestation_ncdac
															WHERE Suppr=0 
															AND NC_DAC='RC'
															AND Progression=0
															AND Id_MoisPrestation=".$LigneMoisPrestationEC['Id']." ";
														$resultDAC=mysqli_query($bdd,$req);
														$nbRC=mysqli_num_rows($resultDAC);
														$listeNC3="";
														if($nbRC>0){
															while($rowNCDAC=mysqli_fetch_array($resultDAC)){
																$listeNCDACTotal.=$MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month"))." &#x2794; <span style='color:#3d7ad5'>[RC]</span> ".$rowNCDAC['Ref']." : ".stripslashes($rowNCDAC['Commentaire'])."<br>";
																$listeNC3.=$rowNCDAC['Ref']."\n";
															}
														}
														
														if($nbNC>0 || $nbNC2>0 || $nbNC3>0 || $nbRC>0){
															if($nbNC==0){$nbNC=null;}
															if($nbNC2==0){$nbNC2=null;}
															if($nbNC3==0){$nbNC3=null;}
															if($nbRC==0){$nbRC=null;}
														}
														else{
															$nbNC=0;
															$nbNC2=null;
															$nbNC3=null;
															$nbRC=null;
														}
														
														$arrayNbNC[$i]=array("Mois" => "".$MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month"))."","NC1" => $nbNC,"NC2" => $nbNC2,"NC3" => $nbNC3,"RC" => $nbRC);
													}
													else{
														$arrayCompetences[$i]=array("Mois" => "".$MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month"))."","Competences" => 0,"TauxQualif" =>0,"NbMonoCompeteneces" =>0);
														$arraySecurite[$i]=array("Mois" => "".$MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month"))."","NbTrajet" => null,"NbNonTrajetAvecArret" => null,"NbNonTrajetSansArret" => null);
														$arrayManagement[$i]=-1;
														if($nbMois>9){
															$arrayBesoin[$i-9]=array("Mois" => $MoisLettre[$moisEC-1]." ".$anneeEC,"Interne" => 0,"SubContractor" => 0,"Prevision" => 0, "InternePrevi" => 0, "ExternePrevi" => 0);
														}
														$arrayNbNC[$i]=array("Mois" => "".$MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month"))."","NC1" => null,"NC2" => null,"NC3" => null,"RC" => null);
														$arrayOTD[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"NbConforme" => null,"NbTolerance" => null,"NbRetour" => null,"Objectif" => null);
														$arrayOTD2[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"NbConforme" => 0,"NbTolerance" => 0,"NbRetour" => 0,"Objectif" => 0,"Cause" => "","Action" => "","Conforme" => 0,"ObjectifT" => 0,"ConformeT" => 0);
														
														$arrayOQD[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"NbConforme" => null,"NbTolerance" => null,"NbRetour" => null,"Objectif" => null);
														$arrayOQD2[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"NbConforme" => 0,"NbTolerance" => 0,"NbRetour" => 0,"Objectif" => 0,"Cause" => "","Action" => "","Conforme" => 0,"ObjectifT" => 0);
														
													}
													
													if($nbMois>9){
														$CapaInterne=0;
														$CapaExterne=0;
														$ChargeTotal=0;
														$CapaTotal=0;
														$CapaInternePrev=null;
														$CapaExternePrev=null;
														
														
														if($nbResultaMoisPrestaEC>0){
															$CapaInterne=$LigneMoisPrestationEC['CapaInterne'];
															$CapaExterne=$LigneMoisPrestationEC['CapaExterne'];
															$CapaTotal=$LigneMoisPrestationEC['CapaTotal'];
															$ChargeTotal=$LigneMoisPrestationEC['InterneCurrent']+$LigneMoisPrestationEC['SubContractorCurrent'];
														}
														if($CapaTotal==0){
															if($anneeEC."_".$moisEC>=$anneeDuJourReel."_".$moisDuJourReel && $anneeEC."_".$moisEC<=$anneeDuJour7."_".$moisDuJour7){
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
																
																//Rechercher la prévision sur le mois précédent uniquement
																$req="SELECT Id,CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) AS AnneeMois
																FROM moris_moisprestation
																WHERE CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ";
																	if($anneeEC3."_".$moisEC3==$anneeDuJourReel."_".$moisDuJourReel){$req.="('".$annee_1."_".$mois_1."')";}
																	if($anneeEC3."_".$moisEC3==$anneeDuJour1."_".$moisDuJour1){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."')";}
																	if($anneeEC3."_".$moisEC3==$anneeDuJour2."_".$moisDuJour2){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."')";}
																	if($anneeEC3."_".$moisEC3==$anneeDuJour3."_".$moisDuJour3){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."')";}
																	if($anneeEC3."_".$moisEC3==$anneeDuJour4."_".$moisDuJour4){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."')";}
																	if($anneeEC3."_".$moisEC3==$anneeDuJour5."_".$moisDuJour5){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."')";}
																	if($anneeEC3."_".$moisEC3==$anneeDuJour6."_".$moisDuJour6){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."')";}
																	if($anneeEC3."_".$moisEC3==$anneeDuJour7."_".$moisDuJour7){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."')";}
																$req.="AND Suppr=0 
																AND (SELECT ChargeADesactive FROM new_competences_prestation WHERE Id=Id_Prestation)=0
																AND moris_moisprestation.Id_Prestation IN (".$PrestationSelect.")
																AND ((";
																if($bFamilleIndefini==1){$req.="PermanentCurrent+TemporyCurrent+InterneCurrent+SubContractorCurrent+";}
																$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0))>0
																	OR 
																	COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0)>0
																	)
																	ORDER BY Annee DESC, Mois DESC
																	";
																
																
																$resultEC3=mysqli_query($bdd,$req);
																$nbResultaMoisPrestaEC3=mysqli_num_rows($resultEC3);
																if($nbResultaMoisPrestaEC3>0){
																	$LigneMoisPrestationEC3=mysqli_fetch_array($resultEC3);
																	$leMoisCharge="";
																	if($LigneMoisPrestationEC3['AnneeMois']==$annee_1."_".$mois_1){$leMoisCharge="1";}
																	elseif($LigneMoisPrestationEC3['AnneeMois']==$annee_2."_".$mois_2){$leMoisCharge="2";}
																	elseif($LigneMoisPrestationEC3['AnneeMois']==$annee_3."_".$mois_3){$leMoisCharge="3";}
																	elseif($LigneMoisPrestationEC3['AnneeMois']==$annee_4."_".$mois_4){$leMoisCharge="4";}
																	elseif($LigneMoisPrestationEC3['AnneeMois']==$annee_5."_".$mois_5){$leMoisCharge="5";}
																	elseif($LigneMoisPrestationEC3['AnneeMois']==$annee_6."_".$mois_6){$leMoisCharge="6";}
																	elseif($LigneMoisPrestationEC3['AnneeMois']==$annee_7."_".$mois_7){$leMoisCharge="6";}
																	if($leMoisCharge<>""){
																		//Rechercher la prévision sur l'un des mois précédent
																		$req="SELECT ";
																		if($bFamilleIndefini==1){$req.="M".$leMoisCharge."+";}
																		$req.="COALESCE((SELECT SUM(M".$leMoisCharge.") FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS M,";
																		if($bFamilleIndefini==1){$req.="PermanentCurrent+TemporyCurrent+InterneCurrent+";}
																		$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS MInterne,";			
																		if($bFamilleIndefini==1){$req.="SubContractorCurrent+";}
																		$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS MExterne,
																		COALESCE((SELECT SUM(CapaM".$leMoisCharge.") FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS CapaInterne,
																		COALESCE((SELECT SUM(CapaM".$leMoisCharge.") FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS CapaExterne
																		FROM moris_moisprestation
																		WHERE Id=".$LigneMoisPrestationEC3['Id']." ";
																		$resultECF2=mysqli_query($bdd,$req);
																		$nbResultaMoisPrestaECF2=mysqli_num_rows($resultECF2);
																		if($nbResultaMoisPrestaECF2>0){
																			$LigneMoisPrestationECF2=mysqli_fetch_array($resultECF2);
																			$ChargeTotal=$LigneMoisPrestationECF2['M'];
																			$CapaInternePrev=$LigneMoisPrestationECF2['CapaInterne'];
																			$CapaExternePrev=$LigneMoisPrestationECF2['CapaExterne'];
																		}
																	}
																}
															}
														}
														
														if($CapaInterne==0){$CapaInterne=null;}
														if($CapaExterne==0){$CapaExterne=null;}
														if($ChargeTotal==0){$ChargeTotal=null;}
														if($CapaInternePrev==0){$CapaInternePrev=null;}
														if($CapaExternePrev==0){$CapaExternePrev=null;}
														
														$arrayBesoin[$i-9]=array("Mois" => $MoisLettre[$moisEC-1]." ".$anneeEC,"Interne" => $CapaInterne,"SubContractor" => $CapaExterne,"Prevision" => $ChargeTotal, "InternePrevi" => $CapaInternePrev, "ExternePrevi" => $CapaExternePrev);
													}
													$productivite[$i]=array("Mois" => $MoisLettre[$moisEC-1]." ".date("y",strtotime($laDate." +0 month")),"ProductiviteBrut" => $productiviteBrut,"Objectif" => $objectif,"ProductiviteCorrigee" => $productiviteCorrigee);
													$i++;
													$laDate=date("Y-m-d",strtotime($laDate." +1 month"));
												}
												$lemois=$mois;
												$req="SELECT *
												FROM
												(SELECT Id,Annee,Mois,
													EvaluationQualite,EvaluationDelais,EvaluationCompetencePersonnel,EvaluationAutonomie,EvaluationAnticipation,EvaluationCommunication,
													Verouillage
												FROM moris_moisprestation
												WHERE moris_moisprestation.Id_Prestation=".$PrestationSelect." 
												AND CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<='".$annee."_".$lemois."'
												AND Suppr=0
												AND (EvaluationQualite+EvaluationDelais+EvaluationCompetencePersonnel+EvaluationAutonomie+EvaluationAnticipation+EvaluationCommunication)<>0
												ORDER BY CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois)) DESC
												LIMIT 4) AS TAB 
												ORDER BY CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois)) ASC
												";

												$result=mysqli_query($bdd,$req);
												$nbResultaMoisPresta2=mysqli_num_rows($result);
												if($nbResultaMoisPresta2>0){
													$i2=0;
													while($rowPRM=mysqli_fetch_array($result)){
														$total=0;
														$nbEval=0;
														if($rowPRM['EvaluationQualite']>-1){
															$total+=$rowPRM['EvaluationQualite'];
															$nbEval++;
														}
														if($rowPRM['EvaluationDelais']>-1){
															$total+=$rowPRM['EvaluationDelais'];
															$nbEval++;
														}
														if($rowPRM['EvaluationCompetencePersonnel']>-1){
															$total+=$rowPRM['EvaluationCompetencePersonnel'];
															$nbEval++;
														}
														if($rowPRM['EvaluationAutonomie']>-1){
															$total+=$rowPRM['EvaluationAutonomie'];
															$nbEval++;
														}
														if($rowPRM['EvaluationAnticipation']>-1){
															$total+=$rowPRM['EvaluationAnticipation'];
															$nbEval++;
														}
														if($rowPRM['EvaluationCommunication']>-1){
															$total+=$rowPRM['EvaluationCommunication'];
															$nbEval++;
														}
														if($nbEval>0){
															$note=round($total/$nbEval,1);
															$arrayNewPRM[$i2]=array("Mois" => "".$MoisLettre[$rowPRM['Mois']-1]." ".$rowPRM['Annee']."","Note" => $note,"Objectif" => 3);
															$i2++;
														}
													}
												}
												
												$req="SELECT *
												FROM
												(SELECT Id,Annee,Mois,
													PieceJointeDernierePRM,PieceJointeSatisfactionPRM
												FROM moris_moisprestation
												WHERE moris_moisprestation.Id_Prestation=".$PrestationSelect." 
												AND CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<='".$annee."_".$lemois."'
												AND Suppr=0
												ORDER BY CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois)) DESC
												LIMIT 4) AS TAB 
												ORDER BY CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois)) ASC
												";
												$tabPRM1="";
												$tabPRM2="";
												$tabPRM3="";
												$result=mysqli_query($bdd,$req);
												$nbResultaMoisPresta2=mysqli_num_rows($result);
												if($nbResultaMoisPresta2>0){
													while($rowPRM=mysqli_fetch_array($result)){
														$tabPRM1.= "<td style='border:1px solid black;text-align:center;'>".$MoisLettre[$rowPRM['Mois']-1]." ".$rowPRM['Annee']."</td>";
														
														$prm="";
														if($rowPRM['PieceJointeDernierePRM']<>"")
														{
															$prm= '<a class="Info" href="'.$chemin."/".$DirFichierPRM.$rowPRM['PieceJointeDernierePRM'].'" target="_blank"><img width="20px" src="../../Images/Trombone.png" border="0" /></a>';
														}
														$tabPRM2.= "<td style='border:1px solid black;text-align:center;'>".$prm."</td>";
														
														$satisf="";
														if($rowPRM['PieceJointeSatisfactionPRM']<>"")
														{
															$satisf= '<a class="Info" href="'.$chemin."/".$DirFichierSatisfactionClient.$rowPRM['PieceJointeSatisfactionPRM'].'" target="_blank"><img width="20px" src="../../Images/Trombone.png" border="0" /></a>';
														}
														$tabPRM3.= "<td style='border:1px solid black;text-align:center;'>".$satisf."</td>";
													}
												}
												
												$tabPRM="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
													$tabPRM.= "<tr>";
														$tabPRM.= "<td style='border:1px solid black'></td>";
														$tabPRM.= $tabPRM1;
													$tabPRM.= "</tr>";
													$tabPRM.= "<tr>";
														$tabPRM.= "<td style='border:1px solid black'>PRM</td>";
														$tabPRM.= $tabPRM2;
													$tabPRM.= "</tr>";
													$tabPRM.= "<tr>";
														$tabPRM.= "<td style='border:1px solid black'>Satisfaction client</td>";
														$tabPRM.= $tabPRM3;
													$tabPRM.= "</tr>";
												$tabPRM.="</table>";
												
		
												$tabOTD="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
												$tabOTD.= "<tr>";
													$tabOTD.= "<td style='border:1px solid black'></td>";
												foreach($arrayOTD2 as $otd){
													$tabOTD.= "<td style='border:1px solid black;text-align:center;'>".$otd['Mois']."</td>";
												}
												$tabOTD.= "</tr>";
												$tabOTD.= "<tr>";
													$tabOTD.= "<td style='border:1px solid black'>C</td>";
												foreach($arrayOTD2 as $otd){
													$tabOTD.= "<td style='border:1px solid black;text-align:center;'>".$otd['NbConforme']."</td>";
												}
												$tabOTD.= "</tr>";
												if($Ligne['ToleranceOTDOQD']==1){
													$tabOTD.= "<tr>";
														$tabOTD.= "<td style='border:1px solid black'>Tolerance</td>";
													foreach($arrayOTD2 as $otd){
														$tabOTD.= "<td style='border:1px solid black;text-align:center;'>".$otd['NbTolerance']."</td>";
													}
													$tabOTD.= "</tr>";
												}
												$tabOTD.= "<tr>";
													$tabOTD.= "<td style='border:1px solid black'>NC</td>";
												foreach($arrayOTD2 as $otd){
													$tabOTD.= "<td style='border:1px solid black;text-align:center;'>".$otd['NbRetour']."</td>";
												}
												$tabOTD.= "</tr>";
												$tabOTD.= "<tr>";
													$tabOTD.= "<td style='border:1px solid black'>OTD C %</td>";
												
												$objectif=$otd['Objectif'];
												$objectifT=0;
												if($Ligne['ToleranceOTDOQD']==1){
													$objectifT=$otd['ObjectifT'];
												}
												foreach($arrayOTD2 as $otd){
													$couleur="#8dd7cf";
													if($otd['Conforme']<$otd['Objectif']){
														$couleur="#e9a1ac";
														if($Ligne['ToleranceOTDOQD']==1){
															if($otd['Conforme']>=$otd['ObjectifT']){$couleur="#f3c19d";}
														}
													}
													if($otd['Conforme']>0 || $otd['NbRetour']>0){
														$span="";
														$leHover="";
														if($otd['Cause']<>"" || $otd['Action']<>""){
															$leHover="id='leHover2'";
															$span="<img src='../../Images/etoile.png' border='0' /><span>Cause : ".$otd['Cause']."<br>Action : ".$otd['Action']."</span>";
														}
														$lepourcentage=0;
														if($otd['Conforme']>0){$lepourcentage=$otd['Conforme'];}
														$tabOTD.= "<td style='border:1px solid black;background-color:".$couleur.";text-align:center;' ".$leHover.">".$span." ".$lepourcentage."%</td>";
													}
													else{
														$tabOTD.= "<td style='border:1px solid black;text-align:center;'></td>";
													}
												}
												$tabOTD.= "</tr>";
												if($Ligne['ToleranceOTDOQD']==1){
													$tabOTD.= "<tr>";
														$tabOTD.= "<td style='border:1px solid black'>OTD Tolerance %</td>";
													foreach($arrayOTD2 as $otd){
														$couleur="#8dd7cf";
														if($otd['ConformeT']<$otd['Objectif']){$couleur="#e9a1ac";}
														if($otd['ConformeT']>0 || $otd['NbRetour']>0){
															$span="";
															$leHover="";
															if($otd['Cause']<>"" || $otd['Action']<>""){
																$leHover="id='leHover2'";
																$span="<img src='../../Images/etoile.png' border='0' /><span>Cause : ".$otd['Cause']."<br>Action : ".$otd['Action']."</span>";
															}
														$tabOTD.= "<td style='border:1px solid black;background-color:".$couleur.";text-align:center;' ".$leHover.">".$span." ".$otd['ConformeT']."%</td>";
														}
														else{
															$tabOTD.= "<td style='border:1px solid black;text-align:center;'></td>";
														}
													}
													$tabOTD.= "</tr>";
												}
												$tabOTD.="</table>";
												
												
												$tabOQD="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
												$tabOQD.= "<tr>";
													$tabOQD.= "<td style='border:1px solid black'></td>";
												foreach($arrayOQD2 as $oqd){
													$tabOQD.= "<td style='border:1px solid black;text-align:center;'>".$oqd['Mois']."</td>";
												}
												$tabOQD.= "</tr>";
												$tabOQD.= "<tr>";
													$tabOQD.= "<td style='border:1px solid black'>C</td>";
												foreach($arrayOQD2 as $oqd){
													$tabOQD.= "<td style='border:1px solid black;text-align:center;'>".$oqd['NbConforme']."</td>";
												}
												$tabOQD.= "</tr>";
												if($Ligne['ToleranceOTDOQD']==1){
													$tabOQD.= "<tr>";
														$tabOQD.= "<td style='border:1px solid black'>Tolerance</td>";
													foreach($arrayOQD2 as $oqd){
														$tabOQD.= "<td style='border:1px solid black;text-align:center;'>".$oqd['NbTolerance']."</td>";
													}
													$tabOQD.= "</tr>";
												}
												$tabOQD.= "<tr>";
													$tabOQD.= "<td style='border:1px solid black'>NC</td>";
												foreach($arrayOQD2 as $oqd){
													$tabOQD.= "<td style='border:1px solid black;text-align:center;'>".$oqd['NbRetour']."</td>";
												}
												$tabOQD.= "</tr>";
												$tabOQD.= "<tr>";
													$tabOQD.= "<td style='border:1px solid black'>OQD %</td>";
												foreach($arrayOQD2 as $oqd){
													$couleur="#8dd7cf";
													if($oqd['Conforme']<$oqd['Objectif']){$couleur="#e9a1ac";}
													if($oqd['Conforme']>0 || $oqd['NbRetour']>0){
														$span="";
														$leHover="";
														if($oqd['Cause']<>"" || $oqd['Action']<>""){
															$leHover="id='leHover2'";
															$span="<img src='../../Images/etoile.png' border='0' /><span>Cause : ".$oqd['Cause']."<br>Action : ".$oqd['Action']."</span>";
														}
														$lepourcentage=0;
														if($oqd['Conforme']>0){$lepourcentage=$oqd['Conforme'];}
														$tabOQD.= "<td style='border:1px solid black;background-color:".$couleur.";text-align:center;' ".$leHover.">".$span." ".$lepourcentage."%</td>";
													}
													else{
														$tabOQD.= "<td style='border:1px solid black;text-align:center;'></td>";
													}
												}
												$tabOQD.= "</tr>";
												$tabOQD.="</table>";
												
												$laDate=date($annee."-".$mois."-1");
												$laDate=date("Y-m-d",strtotime($laDate." +1 month"));
					
												for($nbMois=1;$nbMois<=6;$nbMois++){
													$anneeEC=date("Y",strtotime($laDate." +0 month"));
													$moisEC=date("m",strtotime($laDate." +0 month"));
													
													$CapaInterne=0;
													$CapaExterne=0;
													$ChargeTotal=0;
													$CapaInternePrev=0;
													$CapaExternePrev=0;
													
													$req="SELECT ";
													if($bFamilleIndefini==1){$req.="PermanentCurrent+TemporyCurrent+InterneCurrent+";}
													$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS InterneCurrent,";
													if($bFamilleIndefini==1){$req.="SubContractorCurrent+";}
													$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS SubContractorCurrent, ";
													$req.="
													IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',
														PermanentCurrent+TemporyCurrent+InterneCurrent,
														COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.") ),0)) AS CapaInterne,
													IF(CONCAT(Annee,'_',IF(Mois<10,CONCAT('0',Mois),Mois))<'2022_10',
														SubContractorCurrent,
														COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.") ),0)) AS CapaExterne
													FROM moris_moisprestation
													WHERE Annee=".$anneeEC." 
													AND Mois=".$moisEC."
													AND Suppr=0 
													AND (SELECT ChargeADesactive FROM new_competences_prestation WHERE Id=Id_Prestation)=0
													AND moris_moisprestation.Id_Prestation =".$PrestationSelect."
													AND (";
													if($bFamilleIndefini==1){$req.="PermanentCurrent+TemporyCurrent+InterneCurrent+SubContractorCurrent+";}
													$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0))>0 ";
													$resultEC=mysqli_query($bdd,$req);
													$nbResultaMoisPrestaEC=mysqli_num_rows($resultEC);
													
													if($nbResultaMoisPrestaEC>0){
														$LigneMoisPrestationEC=mysqli_fetch_array($resultEC);	
														$CapaInterne=$LigneMoisPrestationEC['CapaInterne'];
														$CapaExterne=$LigneMoisPrestationEC['CapaExterne'];
														$ChargeTotal=$LigneMoisPrestationEC['InterneCurrent']+$LigneMoisPrestationEC['SubContractorCurrent'];
													}
													//if($CapaInterne==0 && $CapaExterne==0 && $ChargeTotal==0){
													if($nbResultaMoisPrestaEC==0){
														if($anneeEC."_".$moisEC>=$anneeDuJourReel."_".$moisDuJourReel && $anneeEC."_".$moisEC<=$anneeDuJour7."_".$moisDuJour7){
															
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

															//Rechercher la prévision sur le mois précédent uniquement
															$req="SELECT Id,CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) AS AnneeMois
															FROM moris_moisprestation
															WHERE CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ";
																if($anneeEC3."_".$moisEC3==$anneeDuJourReel."_".$moisDuJourReel){$req.="('".$annee_1."_".$mois_1."')";}
																if($anneeEC3."_".$moisEC3==$anneeDuJour1."_".$moisDuJour1){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."')";}
																if($anneeEC3."_".$moisEC3==$anneeDuJour2."_".$moisDuJour2){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."')";}
																if($anneeEC3."_".$moisEC3==$anneeDuJour3."_".$moisDuJour3){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."')";}
																if($anneeEC3."_".$moisEC3==$anneeDuJour4."_".$moisDuJour4){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."')";}
																if($anneeEC3."_".$moisEC3==$anneeDuJour5."_".$moisDuJour5){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."')";}
																if($anneeEC3."_".$moisEC3==$anneeDuJour6."_".$moisDuJour6){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."')";}
																if($anneeEC3."_".$moisEC3==$anneeDuJour7."_".$moisDuJour7){$req.="('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."')";}
															$req.="AND Suppr=0 
															AND (SELECT ChargeADesactive FROM new_competences_prestation WHERE Id=Id_Prestation)=0
															AND moris_moisprestation.Id_Prestation IN (".$PrestationSelect.")
															AND (";
															if($bFamilleIndefini==1){$req.="PermanentCurrent+TemporyCurrent+InterneCurrent+SubContractorCurrent+";}
															$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0))>0
																ORDER BY Annee DESC, Mois DESC
																";
															$resultEC3=mysqli_query($bdd,$req);
															$nbResultaMoisPrestaEC3=mysqli_num_rows($resultEC3);
															if($nbResultaMoisPrestaEC3>0){
																$LigneMoisPrestationEC3=mysqli_fetch_array($resultEC3);
																$leMoisCharge="";
																if($LigneMoisPrestationEC3['AnneeMois']==$annee_1."_".$mois_1){$leMoisCharge="1";}
																elseif($LigneMoisPrestationEC3['AnneeMois']==$annee_2."_".$mois_2){$leMoisCharge="2";}
																elseif($LigneMoisPrestationEC3['AnneeMois']==$annee_3."_".$mois_3){$leMoisCharge="3";}
																elseif($LigneMoisPrestationEC3['AnneeMois']==$annee_4."_".$mois_4){$leMoisCharge="4";}
																elseif($LigneMoisPrestationEC3['AnneeMois']==$annee_5."_".$mois_5){$leMoisCharge="5";}
																elseif($LigneMoisPrestationEC3['AnneeMois']==$annee_6."_".$mois_6){$leMoisCharge="6";}
																elseif($LigneMoisPrestationEC3['AnneeMois']==$annee_7."_".$mois_7){$leMoisCharge="6";}
																if($leMoisCharge<>""){
																	//Rechercher la prévision sur l'un des mois précédent
																	$req="SELECT ";
																	if($bFamilleIndefini==1){$req.="M".$leMoisCharge."+";}
																	$req.="COALESCE((SELECT SUM(M".$leMoisCharge.") FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS M,";
																	if($bFamilleIndefini==1){$req.="PermanentCurrent+TemporyCurrent+InterneCurrent+";}
																	$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS MInterne,";			
																	if($bFamilleIndefini==1){$req.="SubContractorCurrent+";}
																	$req.="COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS MExterne,
																	COALESCE((SELECT SUM(CapaM".$leMoisCharge.") FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=0 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS CapaInterne,
																	COALESCE((SELECT SUM(CapaM".$leMoisCharge.") FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id AND Externe=1 AND Id_Famille IN (".$listeFamilleIndefini.")),0) AS CapaExterne
																	FROM moris_moisprestation
																	WHERE Id=".$LigneMoisPrestationEC3['Id']." ";
																	
																	$resultECF2=mysqli_query($bdd,$req);
																	$nbResultaMoisPrestaECF2=mysqli_num_rows($resultECF2);
																	if($nbResultaMoisPrestaECF2>0){
																		$LigneMoisPrestationECF2=mysqli_fetch_array($resultECF2);
																		$ChargeTotal=$LigneMoisPrestationECF2['M'];
																		$CapaInternePrev=$LigneMoisPrestationECF2['CapaInterne'];
																		$CapaExternePrev=$LigneMoisPrestationECF2['CapaExterne'];
																	}
																}
															}
														}
													}
													
													if($CapaInterne==0){$CapaInterne=null;}
													if($CapaExterne==0){$CapaExterne=null;}
													if($ChargeTotal==0){$ChargeTotal=null;}
													if($CapaInternePrev==0){$CapaInternePrev=null;}
													if($CapaExternePrev==0){$CapaExternePrev=null;}
													$arrayBesoin[$i-9]=array("Mois" => $MoisLettre[$moisEC-1]." ".$anneeEC,"Interne" => $CapaInterne,"SubContractor" => $CapaExterne,"Prevision" => $ChargeTotal, "InternePrevi" => $CapaInternePrev, "ExternePrevi" => $CapaExternePrev);
													$laDate=date("Y-m-d",strtotime($laDate." +1 month"));
													$i++;
												}
											?>
											<input type="hidden" name="Id_MoisPrestation" value="<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['Id'];}?>" />
											<tr>
												<td class="Libelle" style="border:1px solid black;width:30%;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "UER/Department/Subsidiary";}else{echo "UER/Dept/Filiale";} ?></td>
												<td style="border:1px solid black;width:70%;">&nbsp;<?php echo $Ligne['Plateforme']; ?></td>
											</tr>
											<tr>
												<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Client";}else{echo "Client";} ?></td>
												<td style="border:1px solid black;" width="60px">&nbsp;<?php echo $Ligne['Client']; ?></td>
											</tr>
											<tr>
												<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Family R03";}else{echo "Famille R03";} ?></td>
												<td style="border:1px solid black;" width="60px">&nbsp;<?php echo $Ligne['FamilleR03']; ?></td>
											</tr>
											<tr>
												<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Siglum";}else{echo "Sigle";} ?></td>
												<td style="border:1px solid black;" width="60px">&nbsp;<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['Sigle'];} ?></td>
											</tr>
											<tr>
												<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Reference project";}else{echo "Ref CDC";} ?></td>
												<td style="border:1px solid black;">
													&nbsp;<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['RefCDC'];}?>
												</td>
											</tr>
											<tr>
												<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Contract name";}else{echo "Nom du contrat";} ?></td>
												<td style="border:1px solid black;">
													&nbsp;<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['Contrat'];}?>
												</td>
											</tr>
											<tr>
												<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Purchasing entity";}else{echo "Entité achat";} ?></td>
												<td style="border:1px solid black;">
													&nbsp;<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['EntiteAchat'];}?>
												</td>
											</tr>
											<tr>
												<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "EGP Buyer in charge of contract";}else{echo "Acheteur client";}  ?></td>
												<td style="border:1px solid black;">
													&nbsp;<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['AcheteurClient'];}?>
												</td>
											</tr>
											<tr>
												<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Buyer mail";}else{echo "Mail acheteur";}  ?></td>
												<td style="border:1px solid black;">
													&nbsp;<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['MailAcheteur'];}?>
												</td>
											</tr>
											<tr>
												<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Customer";}else{echo "Donneur d'ordre";} ?></td>
												<td style="border:1px solid black;">
													&nbsp;<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['DonneurOrdre'];}?>
												</td>
											</tr>
											<tr>
												<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Customer mail";}else{echo "Mail donneur d'ordre";}  ?></td>
												<td style="border:1px solid black;">
													&nbsp;<?php if($nbResultaMoisPresta>0){echo $LigneMoisPrestation['MailDO'];}?>
												</td>
											</tr>
											<tr>
												<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Activity label";}else{echo "Libellé activité";} ?></td>
												<td style="border:1px solid black;">
													&nbsp;<?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['IntituleCDC']);}?>
												</td>
											</tr>
											<tr>
												<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Project manager";}else{echo "Nom du Resp. Projet";} ?></td>
												<td style="border:1px solid black;">
													&nbsp;<?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['RespProjet']);}?>
												</td>
											</tr>
											<tr>
												<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Team coordinator";}else{echo "Nom du Coor. d'équipe";} ?></td>
												<td style="border:1px solid black;">
													&nbsp;<?php if($nbResultaMoisPresta>0){echo stripslashes($LigneMoisPrestation['CoorEquipe']);}?>
												</td>
											</tr>
											<?php }?>
										</table>
									</span>
								</td>
							</tr>
						</table>
					</td>
					<?php if($nbResulta>0){?>
					<td>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php 
					
						$leManagement="<table width='99%'>
										<tr>";
										

									$leManagement.="</tr>
									<tr>";
										$i=0;
										for($nbMois=1;$nbMois<=12;$nbMois++){
											if($arrayManagement[$i]==0){$leManagement.="<td class='Libelle' align='center'><img width='35px' src='../../Images/VisageContent.png' border='0' /></td>";}
											elseif($arrayManagement[$i]==1){$leManagement.="<td class='Libelle' align='center'><img width='35px' src='../../Images/VisageMoyen.png' border='0' /></td>";}	
											elseif($arrayManagement[$i]==2){$leManagement.="<td class='Libelle' align='center'><img width='35px' src='../../Images/VisagePasContent.png' border='0' /></td>";}
											else{$leManagement.="<td class='Libelle' align='center'></td>";}
											$i++;
										}

									$leManagement.="</tr>
									<tr>
										".$evenements."
									</tr>
									</table>
								</td>
							</tr>";
							
							if($_SESSION['Id_Personne']==1351){echo $valAfficher;}
					?>
					</td>
					<td align="right" bgcolor="#ffffff">
						<a href="javascript:CockpitPDF();"><img src="../../Images/pdf.png" border="0" alt="PDF" width="25"></a>
						<script>
							function savePDF(charge,productivite,management,otd,oqd,competence,prm,securite,nc){
								Promise.all([
									chart.exporting.pdfmake,
									chart.exporting.getImage("png"),
									chart1.exporting.getImage("png"),
									chart2.exporting.getImage("png"),
									chart3.exporting.getImage("png"),
									chart4.exporting.getImage("png"),
									chart5.exporting.getImage("png"),
									chart6.exporting.getImage("png"),
									chart7.exporting.getImage("png")
								  ]).then(function(res) { 
									
									

									var pdfMake = res[0];
									
									// pdfmake is ready
									// Create document template
									var doc = {
									  pageSize: "A3",
									  pageOrientation: "landscape",
									  pageMargins: [10, 10, 10, 10],
									  content: []
									};
									
									doc.content.push({
									  table: {
										headerRows: 1,
										widths: [ "25%", "50%", "25%"],
										body: [
										  [
											{ image: "data:image/png;base64,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", width:70,alignment: "center"},
											{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("COCKPIT SITE - ".utf8_encode($MoisLettre3[$_SESSION['MORIS_Mois2']-1])." ".$_SESSION['MORIS_Annee2']);}else{echo json_encode("COCKPIT PRESTATION - ".utf8_encode($MoisLettre3[$_SESSION['MORIS_Mois2']-1])." ".$_SESSION['MORIS_Annee2']);} ?>, bold: true, fontSizefontSize: 18,alignment: "center" },
											{ text: <?php echo json_encode($laPresta); ?>, bold: true, alignment: "center" }
										  ],
										]
									  }
									});

									if(charge==1){
										doc.content.push({
										  text: "",
										  fontSize: 12,
										  bold: true,
										  margin: [0, 5, 0, 5]
										});
										doc.content.push({
										unbreakable: true,
										  table: {
											widths: [ "100%"],
											body: [
											  [
												{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("CHARGE / CAPACITY");}else{echo json_encode("CHARGE / CAPACITE");} ?>, bold: true, alignment: "center"}
											  ],
											  [
												{ image: res[1], alignment: "center", width: 800}
											  ],
											]
										  }
										});
									}
									if(productivite==1){
										doc.content.push({
										  text: "",
										  fontSize: 12,
										  bold: true,
										  margin: [0, 5, 0, 5]
										});
										doc.content.push({
										unbreakable: true,
										  table: {
											widths: [ "100%"],
											body: [
											  [
												{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("PRODUCTIVITY");}else{echo json_encode("PRODUCTIVITE");} ?>, bold: true, alignment: "center" }
											  ],
											  [
												{ image: res[2], alignment: "center", width: 800}
											  ],
											]
										  }
										});
									}
									if(management==1){
										doc.content.push({
										  text: "",
										  fontSize: 12,
										  bold: true,
										  margin: [0, 5, 0, 5]
										});
										doc.content.push({
										unbreakable: true,
										  table: {
											widths: [ "100%"],
											body: [
											  [
												{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("MANAGEMENT");}else{echo json_encode("MANAGEMENT");} ?>, bold: true, alignment: "center" }
											  ],
											  [
												{ 
													table: {
														widths: ["8%","8%","8%","8%","8%","8%","8%","8%","8%","8%","8%","8%"],
														body: [
														  [
															<?php 
															$i=0;
															for($nbMois=1;$nbMois<=12;$nbMois++){
																if($nbMois<12){
															?>
																{ text: <?php echo json_encode($arrayMoisLettre[$i]); ?>, bold: true, alignment: "center" },
															<?php
																}
																else{
															?>
																{ text: <?php echo json_encode($arrayMoisLettre[$i]); ?>, bold: true, alignment: "center" }
															<?php
																}
																$i++;
															}
															?>
														  ],
														  [
															<?php 
															$i=0;
															for($nbMois=1;$nbMois<=12;$nbMois++){
																
																	if($arrayManagement[$i]==0){
																	?>
																	{image: "data:image/png;base64,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",width:30,alignment: "center"}
																	<?php
																	}
																	elseif($arrayManagement[$i]==1){
																	?>
																	{image: "data:image/png;base64,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",width:30,alignment: "center"}
																	<?php
																	}	
																	elseif($arrayManagement[$i]==2){
																	?>
																	{image: "data:image/png;base64,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",width:30,alignment: "center"}
																	<?php
																	}
																	else{
																	?>
																	""
																	<?php
																	}
																if($nbMois<12){
															?>
																,
															<?php
																}
																$i++;
															}
															?>
														  ],
														]
													  }
												}
											  ],
											]
										  }
										});
									}
									if(otd==1){
										doc.content.push({
										  text: "",
										  fontSize: 12,
										  bold: true,
										  margin: [0, 5, 0, 5]
										});
										doc.content.push({
										unbreakable: true,
										  table: {
											widths: [ "100%"],
											body: [
											  [
												{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("On Time Delivery (OTD)");}else{echo json_encode("On Time Delivery (OTD)");} ?>, bold: true, alignment: "center"}
												],
											  [
												{ image: res[3], alignment: "center", width: 800}
											  ],
											]
										  }
										});
									}
									if(oqd==1){
										doc.content.push({
										  text: "",
										  fontSize: 12,
										  bold: true,
										  margin: [0, 5, 0, 5]
										});
										doc.content.push({
										unbreakable: true,
										  table: {
											widths: [ "100%"],
											body: [
											  [
												{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("On Quality Delivery (OQD) ");}else{echo json_encode("On Quality Delivery (OQD) ");} ?>, bold: true, alignment: "center" }
												],
											  [
												{ image: res[4], alignment: "center", width: 800}
											  ],
											]
										  }
										});
									}
									if(competence==1){
										doc.content.push({
										  text: "",
										  fontSize: 12,
										  bold: true,
										  margin: [0, 5, 0, 5]
										});
										doc.content.push({
										unbreakable: true,
										  table: {
											widths: [ "100%"],
											body: [
											  [
												{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("COMPETENCES");}else{echo json_encode("COMPETENCES");} ?>, bold: true, alignment: "center"},
												],
											  [
												{ image: res[5], alignment: "center", width: 800}
											  ],
											]
										  }
										});
									}
									if(prm==1){
										doc.content.push({
										  text: "",
										  fontSize: 12,
										  bold: true,
										  margin: [0, 5, 0, 5]
										});
										doc.content.push({
										unbreakable: true,
										  table: {
											widths: [ "100%"],
											body: [
											  [
												{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("SATISFACTION CLIENTS");}else{echo json_encode("SATISFACTION CLIENTS");} ?>, bold: true, alignment: "center" },
												],
											  [
												{ image: res[6], alignment: "center", width: 800}
											  ],
											]
										  }
										});
									}
									if(securite==1){
										doc.content.push({
										  text: "",
										  fontSize: 12,
										  bold: true,
										  margin: [0, 5, 0, 5]
										});
										doc.content.push({
										unbreakable: true,
										  table: {
											widths: [ "100%"],
											body: [
											  [
												{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("SECURITY");}else{echo json_encode("SECURITE");} ?>, bold: true, alignment: "center"},
												],
											  [
												{ image: res[7], alignment: "center", width: 800}
											  ],
											]
										  }
										});
									}
									if(nc==1){
										doc.content.push({
										  text: "",
										  fontSize: 12,
										  bold: true,
										  margin: [0, 5, 0, 5]
										});
										doc.content.push({
										unbreakable: true,
										  table: {
											widths: [ "100%"],
											body: [
											  [
												{ text: <?php if($_SESSION['Langue']=="EN"){echo json_encode("NC & RC NEWS");}else{echo json_encode("NOUVELLES NC & RC");} ?>, bold: true, alignment: "center" },
												],
											  [
												{ image: res[8], alignment: "center", width: 800}
											  ],
											]
										  }
										});
									}
								
									pdfMake.createPdf(doc).download("RECORD.pdf");
									
								  });
							}
						</script>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="5"></td></tr>
	<tr>
		<td align="center">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%" valign="top" height="350px;">
						<?php 
							$req="SELECT FR,EN
							FROM moris_aideparagraphe
							WHERE moris_aideparagraphe.NomParagraphe='BESOIN STAFFING' ";
							$resultAide=mysqli_query($bdd,$req);
							$LigneAide=mysqli_fetch_array($resultAide);
						?>
						<table class="TableCompetences" width="99%" style="height:100%;" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;">
								<input type="checkbox" id="checkChargeCapa" name="checkChargeCapa" checked>
								<?php if($_SESSION['Langue']=="EN"){echo "CHARGE / CAPACITY";}else{echo "CHARGE / CAPACITÉ";} ?></td>
								<td style="cursor:pointer;" align="right"><div onclick="AfficheAide('Besoins')"><img id="Besoins" width="30px" src='../../Images/Aide2.png' border='0' /></div></td>
							</tr>
							<tr class="Besoins" style="display:none;"><td height="4"></td></tr>
							<tr class="Besoins" style="display:none;"><td bgcolor="#d5f2ff" style="border-top:2px dotted #0b6acb;" colspan="2" height="4"></td></tr>
							<tr class="Besoins" style="display:none;">
								<td colspan="2" bgcolor="#d5f2ff" style="color:#000000;">
								<?php if($_SESSION['Langue']=="EN"){echo nl2br(str_replace("\\","",stripslashes($LigneAide['EN'])));}else{echo nl2br(str_replace("\\","",stripslashes($LigneAide['FR'])));} ?>
								</td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="80%" valign="top">
									<div id="chart_Besoin" style="width:100%;height:280px;"></div>
									<script>
										var chart = am4core.create("chart_Besoin", am4charts.XYChart);

										// Add data
										chart.data = <?php echo json_encode($arrayBesoin); ?>;

										// Create axes
										var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
										categoryAxis.dataFields.category = "Mois";
										categoryAxis.renderer.grid.template.location = 0;
										categoryAxis.renderer.minGridDistance = 15;
										categoryAxis.renderer.labels.template.horizontalCenter = "right";
										categoryAxis.renderer.labels.template.verticalCenter = "middle";
										categoryAxis.renderer.labels.template.rotation = 270;
										categoryAxis.tooltip.disabled = true;
										categoryAxis.renderer.minHeight = 0;

										var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
										valueAxis.renderer.minWidth = 0;
										valueAxis.min= 0;
										valueAxis.title.text = "Staffing (nbr)";

										// Create series
										var series1 = chart.series.push(new am4charts.ColumnSeries());
										series1.columns.template.width = am4core.percent(50);
										series1.tooltipText = "{name}: {valueY.value}";
										series1.dataFields.categoryX = "Mois";
										series1.dataFields.valueY = "Interne";
										series1.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Capa Interne");}else{echo json_encode("Capa Interne");} ?>;
										series1.stacked = true;
										series1.stroke  = "#66b6dc";
										series1.fill  = "#66b6dc";
										
										var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;
										
										var series3 = chart.series.push(new am4charts.ColumnSeries());
										series3.columns.template.width = am4core.percent(50);
										series3.tooltipText = "{name}: {valueY.value}";
										series3.dataFields.categoryX = "Mois";
										series3.dataFields.valueY = "SubContractor";
										series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Capa External");}else{echo json_encode("Capa Externe");} ?>;
										series3.stacked = true;
										series3.stroke  = "#1ab559";
										series3.fill  = "#1ab559";
										
										var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
										bullet3.label.text = "{valueY}";
										bullet3.locationY = 0.5;
										bullet3.label.fill = am4core.color("#ffffff");
										bullet3.interactionsEnabled = false;
										
										var series2 = chart.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(50);
										series2.tooltipText = "{name}: {valueY.value}";
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "InternePrevi";
										series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Capa Interne Forecast");}else{echo json_encode("Capa Interne Previ.");} ?>;
										series2.stacked = true;
										series2.stroke  = "#8a88d7";
										series2.strokeDasharray = "8,4,2,4";
										series2.strokeWidth=3;
										series2.fill  = "#8ec9e5";
										
										var bullet2 = series2.bullets.push(new am4charts.LabelBullet());
										bullet2.label.text = "{valueY}";
										bullet2.locationY = 0.5;
										bullet2.label.fill = am4core.color("#ffffff");
										bullet2.interactionsEnabled = false;
	
										
										var series5 = chart.series.push(new am4charts.ColumnSeries());
										series5.columns.template.width = am4core.percent(50);
										series5.tooltipText = "{name}: {valueY.value}";
										series5.dataFields.categoryX = "Mois";
										series5.dataFields.valueY = "ExternePrevi";
										series5.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Capa External Forecast");}else{echo json_encode("Capa Externe Previ.");} ?>;
										series5.stacked = true;
										series5.stroke  = "#36339c";
										series5.strokeDasharray = "8,4,2,4";
										series5.strokeWidth=2;
										series5.fill  = "#86edaf";
										
										var bullet5 = series5.bullets.push(new am4charts.LabelBullet());
										bullet5.label.text = "{valueY}";
										bullet5.locationY = 0.5;
										bullet5.label.fill = am4core.color("#ffffff");
										bullet5.interactionsEnabled = false;
										
										var series4 = chart.series.push(new am4charts.LineSeries());
										series4.dataFields.valueY = "Prevision";
										series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Total load\n(Int + Ext)");}else{echo json_encode("Charge totale\n(Int + Ext)");} ?>;
										series4.dataFields.categoryX = "Mois";
										series4.tooltipText = "Charge totale: {valueY.value}";
										series4.strokeWidth = 2;
										series4.stroke  = "#f7e802";
										series4.fill  = "#f7e802";
										
										var bullet = series4.bullets.push(new am4charts.CircleBullet());
										bullet.circle.radius = 3;
										bullet.circle.fill = am4core.color("#f7e802");
										bullet.circle.strokeWidth = 1;
										
										// Cursor
										chart.cursor = new am4charts.XYCursor();
										chart.cursor.behavior = "panX";
										chart.cursor.lineX.opacity = 0;
										chart.cursor.lineY.opacity = 0;
										
										/* Add legend */
										chart.legend = new am4charts.Legend();
										
										chart.exporting.menu = new am4core.ExportMenu();
										
										chart.exporting.menu.items =
										[
										  {
											"label": "...",
											"menu": [
											  {
												"label": "Image",
												"menu": [
												  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 } },
												  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 } },
												  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 } }
												]
											  }, {
												"label": "Data",
												"menu": [
												  { "type": "csv", "label": "CSV" },
												  { "type": "xlsx", "label": "XLSX" },
												  { "type": "html", "label": "HTML" }
												]
											  }
											]
										  }
										];							
									</script>
								</td>
								<td width="20%" valign="top" align="right">
									<?php 
										$annee3Mois=date("Y",strtotime($date_11Mois." +9 month"));
										$mois3Mois=date("m",strtotime($date_11Mois." +9 month"));
										
										$annee6Mois=date("Y",strtotime($date_11Mois." +17 month"));
										$mois6Mois=date("m",strtotime($date_11Mois." +17 month"));
										$req="SELECT DISTINCT Id_Famille,
											(SELECT Libelle FROM moris_famille WHERE Id=Id_Famille) AS Famille
											FROM moris_moisprestation_famille
											LEFT JOIN moris_moisprestation
											ON moris_moisprestation_famille.Id_MoisPrestation=moris_moisprestation.Id
											WHERE Id_Famille>0
											AND moris_moisprestation.Suppr=0
											AND Id_Prestation=".$PrestationSelect." ";
										if($annee3Mois.'_'.$mois3Mois>$anneeDuJourReel.'_'.$moisDuJourReel){
											$req.="AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))>='".$anneeDuJourReel.'_'.$moisDuJourReel."' ";
										}
										else{
											$req.="AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))>='".$annee3Mois.'_'.$mois3Mois."' ";
										}
											
										$req.="AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))<='".$annee6Mois.'_'.$mois6Mois."'
											ORDER BY Famille
											";
										$resultFamille=mysqli_query($bdd,$req);
										$nbResultaFamille=mysqli_num_rows($resultFamille);

										if($nbResultaFamille>0){
											if($_SESSION['Langue']=="EN"){
												echo "<input type='submit' class='Bouton' name='btn_actualiserFam' id='btn_actualiserFam' value='Refresh' /><br>";
											}
											else{
												echo "<input type='submit' class='Bouton' name='btn_actualiserFam' id='btn_actualiserFam' value='Actualiser' /><br>";
											}
											echo "
												<div id='Div_Famille' style='height:250px;overflow:auto;'>
												<table width='99%' cellpadding='0' cellspacing='0'>";
												if($_SESSION['Langue']=="EN"){
													echo "<tr><td class='Libelle'>&nbsp;Families</td></tr>";
												}
												else{
													echo "<tr><td class='Libelle'>&nbsp;Familles</td></tr>";
												}
											?>
											<tr>
												<td class="Libelle">
													<input type="checkbox" name="selectAllFamille" id="selectAllFamille" onclick="SelectionnerTout2('Famille')" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
												</td>
											</tr>
											<?php
											$checked="checked";
											if($_POST && (isset($_POST['btn_actualiserFam']) || isset($_POST['btn_actualiserOTD']) || isset($_POST['btn_actualiserOQD']))){
												if(isset($_POST['Famille_0'])){$checked="checked";}
												else{$checked="";}
											}
											if($_SESSION['Langue']=="EN"){
												echo "<tr><td class='Libelle'><input type='checkbox' class='checkFamille' name='Famille_0' id='Famille_0' ".$checked." />&nbsp;Indefinite</td></tr>";
											}
											else{
												echo "<tr><td class='Libelle'><input type='checkbox' class='checkFamille' name='Famille_0' id='Famille_0' ".$checked." />&nbsp;Indéfini</td></tr>";
											}
											while($rowFamille=mysqli_fetch_array($resultFamille)){
												$checked="checked";
												if($_POST && (isset($_POST['btn_actualiserFam']) || isset($_POST['btn_actualiserOTD']) || isset($_POST['btn_actualiserOQD']))){
													if(isset($_POST['Famille_'.stripslashes($rowFamille['Id_Famille'])])){$checked="checked";}
													else{$checked="";}
												}
												echo "<tr><td class='Libelle'><input type='checkbox' class='checkFamille' name='Famille_".stripslashes($rowFamille['Id_Famille'])."' id='Famille_".stripslashes($rowFamille['Id_Famille'])."' ".$checked." />&nbsp;".stripslashes($rowFamille['Famille'])."</td></tr>";
											}
											
											echo "</table>
											</div>
											";
											
										}
									?>
								</td>
							</tr>
							<?php if($annee."_".$mois>"2022_09"){?>
								<tr>
									<td class="Libelle" align="left"><?php if($_SESSION['Langue']=="EN"){echo "Detail";}else{echo "Détail de la charge / Capa";} ?>
										<?php 
											echo '<img id="Image_PlusMoins" src="../../Images/Plus.gif" width="15px" style="cursor:pointer;" onclick="javascript:Affiche_Masque();">';
										?>
									</td>
								</tr>
							<?php } ?>
							<tr>
								<td height="95%" colspan="2" valign="top">
									<table width="90%" cellpadding="0" id="Table_ChargeCapa" cellspacing="0" align="center" style="display:none;" >
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Family";}else{echo "Famille";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "Resource";}else{echo "Ressource";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M";}else{echo "M";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M+1";}else{echo "M+1";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M+2";}else{echo "M+2";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M+3";}else{echo "M+3";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M+4";}else{echo "M+4";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M+5";}else{echo "M+5";} ?></td>
											<td class="Libelle" style="border:1px solid black;" align="center"><?php if($_SESSION['Langue']=="EN"){echo "M+6";}else{echo "M+6";} ?></td>
										</tr>
										<?php
										
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
											
											$couleurVert="background-color:#a5cb9b;";
											$couleurRouge="background-color:#e6bcb3;";
											$couleurBleu="background-color:#b3d6e6;";

											$req="
												CREATE TEMPORARY TABLE liste_famille (Id INT ,Libelle VARCHAR(255))";
											$resultFamille=mysqli_query($bdd,$req);
											
											$req="INSERT INTO liste_famille (Id,Libelle) VALUES (0,'Indéfini')";
											$resultFamille=mysqli_query($bdd,$req);
											
											$req="
												INSERT INTO liste_famille
												SELECT DISTINCT Id_Famille AS Id,
												(SELECT Libelle FROM moris_famille WHERE Id=Id_Famille) AS Libelle
												FROM moris_moisprestation_famille
												LEFT JOIN moris_moisprestation
												ON moris_moisprestation_famille.Id_MoisPrestation=moris_moisprestation.Id
												WHERE Id_Famille>0
												AND moris_moisprestation.Suppr=0
												AND Id_Prestation=".$PrestationSelect." ";
												if($annee3Mois.'_'.$mois3Mois>$anneeDuJourReel.'_'.$moisDuJourReel){
													$req.="AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))>='".$anneeDuJourReel.'_'.$moisDuJourReel."' ";
												}
												else{
													$req.="AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))>='".$annee3Mois.'_'.$mois3Mois."' ";
												}
													
												$req.="AND Id_Famille IN (".$listeFamilleIndefini.")
												AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))<='".$annee6Mois.'_'.$mois6Mois."'
												ORDER BY Libelle";
											
											$resultFamille=mysqli_query($bdd,$req);

											$req="
												SELECT Id, Libelle 
												FROM liste_famille";											
											$resultFamille=mysqli_query($bdd,$req);
											$nbFamille=mysqli_num_rows($resultFamille);

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
													
													$visibleInterne="style='display:none'";
													$visibleExterne="style='display:none'";
													
													if($annee."_".$mois>"2022_09"){
														$laDate=date($annee."-".$mois."-01");
														for($i=0;$i<=6;$i++){
															
															$anneeEC2=date("Y",strtotime($laDate." +0 month"));
															$moisEC2=date("m",strtotime($laDate." +0 month"));

															$req="SELECT Id,CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) AS AnneeMois
															FROM moris_moisprestation
															WHERE moris_moisprestation.Id_Prestation=".$PrestationSelect." 
															AND Annee=".$anneeEC2." 
															AND Mois=".$moisEC2."
															AND (
																COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id ),0)>0
																OR 
																COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0)>0
															)
															AND Suppr=0 											
															";
															$result=mysqli_query($bdd,$req);
															$nbResultaMoisPresta=mysqli_num_rows($result);
															if($nbResultaMoisPresta>0){
																$LigneMoisPrestation=mysqli_fetch_array($result);
															}
															else{
																$nbResultaMoisPrestaM1=0;
																if($anneeEC2."_".$moisEC2>=$anneeDuJourReel."_".$moisDuJourReel && $anneeEC2."_".$moisEC2<=$anneeDuJour6."_".$moisDuJour6){
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
																	WHERE moris_moisprestation.Id_Prestation=".$PrestationSelect." 
																	AND (
																		COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id ),0)>0
																		OR 
																		COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0)>0
																	)

																	AND Suppr=0 ";
						
																	if($anneeEC2."_".$moisEC2==$anneeDuJourReel."_".$moisDuJourReel){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."') ";}
																	elseif($anneeEC2."_".$moisEC2==$anneeDuJour1."_".$moisDuJour1){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."') ";}
																	elseif($anneeEC2."_".$moisEC2==$anneeDuJour2."_".$moisDuJour2){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."') ";}
																	elseif($anneeEC2."_".$moisEC2==$anneeDuJour3."_".$moisDuJour3){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."') ";}
																	elseif($anneeEC2."_".$moisEC2==$anneeDuJour4."_".$moisDuJour4){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."') ";}
																	elseif($anneeEC2."_".$moisEC2==$anneeDuJour5."_".$moisDuJour5){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."') ";}
																	elseif($anneeEC2."_".$moisEC2==$anneeDuJour6."_".$moisDuJour6){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."','".$annee_7."_".$mois_7."') ";}
																	$req.="ORDER BY Annee DESC, Mois DESC ";
																	
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
																	
																	if($i==0){$M=$LigneFamilleMois['leM'];$CapaM=$LigneFamilleMois['leCapaM'];}
																	elseif($i==1){$M1=$LigneFamilleMois['leM'];$CapaM1=$LigneFamilleMois['leCapaM'];}
																	elseif($i==2){$M2=$LigneFamilleMois['leM'];$CapaM2=$LigneFamilleMois['leCapaM'];}
																	elseif($i==3){$M3=$LigneFamilleMois['leM'];$CapaM3=$LigneFamilleMois['leCapaM'];}
																	elseif($i==4){$M4=$LigneFamilleMois['leM'];$CapaM4=$LigneFamilleMois['leCapaM'];}
																	elseif($i==5){$M5=$LigneFamilleMois['leM'];$CapaM5=$LigneFamilleMois['leCapaM'];}
																	elseif($i==6){$M6=$LigneFamilleMois['leM'];$CapaM6=$LigneFamilleMois['leCapaM'];}

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

																	if($i==0){$eM=$LigneFamilleMois['leM'];$CapaeM=$LigneFamilleMois['leCapaM'];}
																	elseif($i==1){$eM1=$LigneFamilleMois['leM'];$CapaeM1=$LigneFamilleMois['leCapaM'];}
																	elseif($i==2){$eM2=$LigneFamilleMois['leM'];$CapaeM2=$LigneFamilleMois['leCapaM'];}
																	elseif($i==3){$eM3=$LigneFamilleMois['leM'];$CapaeM3=$LigneFamilleMois['leCapaM'];}
																	elseif($i==4){$eM4=$LigneFamilleMois['leM'];$CapaeM4=$LigneFamilleMois['leCapaM'];}
																	elseif($i==5){$eM5=$LigneFamilleMois['leM'];$CapaeM5=$LigneFamilleMois['leCapaM'];}
																	elseif($i==6){$eM6=$LigneFamilleMois['leM'];$CapaeM6=$LigneFamilleMois['leCapaM'];}
																}
															}
															$laDate=date("Y-m-d",strtotime($laDate." +1 month"));
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
													if((unNombreSinon0($M)+unNombreSinon0($M1)+unNombreSinon0($M2)+unNombreSinon0($M3)+unNombreSinon0($M4)+unNombreSinon0($M5)+unNombreSinon0($M6))>0){
															$visibleInterne="";
													}
													if((unNombreSinon0($eM)+unNombreSinon0($eM1)+unNombreSinon0($eM2)+unNombreSinon0($eM3)+unNombreSinon0($eM4)+unNombreSinon0($eM5)+unNombreSinon0($eM6))>0){
															$visibleExterne="";
													}
										?>
										<tr id="interne<?php echo $rowFamille['Id'];?>" class="interneExterne" <?php echo $visibleInterne; ?>>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php echo $rowFamille['Libelle'];?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "Internal";}else{echo "Interne";} ?>
											</td>
											<td style="border:1px solid black;<?php if(($M-$CapaM)<0){echo $couleurBleu;}elseif(($M-$CapaM)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo $CapaM-$M;?>
											</td>
											<td style="border:1px solid black;<?php if(($M1-$CapaM1)<0){echo $couleurBleu;}elseif(($M1-$CapaM1)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo ($CapaM1-$M1);?>
											</td>
											<td style="border:1px solid black;<?php if(($M2-$CapaM2)<0){echo $couleurBleu;}elseif(($M2-$CapaM2)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo ($CapaM2-$M2);?>
											</td>
											<td style="border:1px solid black;<?php if(($M3-$CapaM3)<0){echo $couleurBleu;}elseif(($M3-$CapaM3)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo ($CapaM3-$M3);?>
											</td>
											<td style="border:1px solid black;<?php if(($M4-$CapaM4)<0){echo $couleurBleu;}elseif(($M4-$CapaM4)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo ($CapaM4-$M4);?>
											</td>
											<td style="border:1px solid black;<?php if(($M5-$CapaM5)<0){echo $couleurBleu;}elseif(($M5-$CapaM5)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo ($CapaM5-$M5);?>
											</td>
											<td style="border:1px solid black;<?php if(($M6-$CapaM6)<0){echo $couleurBleu;}elseif(($M6-$CapaM6)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo ($CapaM6-$M6);?>
											</td>
										</tr>
										<tr id="externe<?php echo $rowFamille['Id'];?>" class="interneExterne" <?php echo $visibleExterne; ?>>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php echo $rowFamille['Libelle'];?>
											</td>
											<td class="Libelle" style="border:1px solid black;" align="center">
												<?php if($_SESSION['Langue']=="EN"){echo "External";}else{echo "Externe";} ?>
											</td>
											<td style="border:1px solid black;<?php if(($eM-$CapaeM)<0){echo $couleurBleu;}elseif(($eM-$CapaeM)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo $CapaeM-$eM;?>
											</td>
											<td style="border:1px solid black;<?php if(($eM1-$CapaeM1)<0){echo $couleurBleu;}elseif(($eM1-$CapaeM1)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo ($CapaeM1-$eM1);?>
											</td>
											<td style="border:1px solid black;<?php if(($eM2-$CapaeM2)<0){echo $couleurBleu;}elseif(($eM2-$CapaeM2)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo ($CapaeM2-$eM2);?>
											</td>
											<td style="border:1px solid black;<?php if(($eM3-$CapaeM3)<0){echo $couleurBleu;}elseif(($eM3-$CapaeM3)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo ($CapaeM3-$eM3);?>
											</td>
											<td style="border:1px solid black;<?php if(($eM4-$CapaeM4)<0){echo $couleurBleu;}elseif(($eM4-$CapaeM4)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo ($CapaeM4-$eM4);?>
											</td>
											<td style="border:1px solid black;<?php if(($eM5-$CapaeM5)<0){echo $couleurBleu;}elseif(($eM5-$CapaeM5)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo ($CapaeM5-$eM5);?>
											</td>
											<td style="border:1px solid black;<?php if(($eM6-$CapaeM6)<0){echo $couleurBleu;}elseif(($eM6-$CapaeM6)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo ($CapaeM6-$eM6);?>
											</td>
										</tr>
										<?php
												}
											}
										?>
										<tr>
											<td class="Libelle" style="border:1px solid black;" align="center" colspan="2">
												<?php if($_SESSION['Langue']=="EN"){echo "Total";}else{echo "Total";} ?>
											</td>
											<td style="border:1px solid black;<?php if(($sommeM-$sommeCapaM)<0){echo $couleurBleu;}elseif(($sommeM-$sommeCapaM)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo $sommeCapaM-$sommeM;?>
											</td>
											<td style="border:1px solid black;<?php if(($sommeM1-$sommeCapaM1)<0){echo $couleurBleu;}elseif(($sommeM1-$sommeCapaM1)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo $sommeCapaM1-$sommeM1;?>
											</td>
											<td style="border:1px solid black;<?php if(($sommeM2-$sommeCapaM2)<0){echo $couleurBleu;}elseif(($sommeM2-$sommeCapaM2)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo $sommeCapaM2-$sommeM2;?>
											</td>
											<td style="border:1px solid black;<?php if(($sommeM3-$sommeCapaM3)<0){echo $couleurBleu;}elseif(($sommeM3-$sommeCapaM3)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo $sommeCapaM3-$sommeM3;?>
											</td>
											<td style="border:1px solid black;<?php if(($sommeM4-$sommeCapaM4)<0){echo $couleurBleu;}elseif(($sommeM4-$sommeCapaM4)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo $sommeCapaM4-$sommeM4;?>
											</td>
											<td style="border:1px solid black;<?php if(($sommeM5-$sommeCapaM5)<0){echo $couleurBleu;}elseif(($sommeM5-$sommeCapaM5)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo $sommeCapaM5-$sommeM5;?>
											</td>
											<td style="border:1px solid black;<?php if(($sommeM6-$sommeCapaM6)<0){echo $couleurBleu;}elseif(($sommeM6-$sommeCapaM6)>0){echo $couleurRouge;}else{echo $couleurVert;}?>" align="center">
												<?php echo $sommeCapaM6-$sommeM6;?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
						</table>
					</td>
					<td width="50%" valign="top" height="350px;">
						<?php 
							$req="SELECT FR,EN
							FROM moris_aideparagraphe
							WHERE moris_aideparagraphe.NomParagraphe='PRODUCTIVITE' ";
							$resultAide=mysqli_query($bdd,$req);
							$LigneAide=mysqli_fetch_array($resultAide);
						?>
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;">
								<input type="checkbox" id="checkProductivite" name="checkProductivite" checked>
								<?php if($_SESSION['Langue']=="EN"){echo "PRODUCTIVITY";}else{echo "PRODUCTIVITE";} ?></td>
								<td style="cursor:pointer;" align="right"><div onclick="AfficheAide('Productivite')"><img id="Productivite" width="30px" src='../../Images/Aide2.png' border='0' /></div></td>
							</tr>
							<tr class="Productivite" style="display:none;"><td height="4"></td></tr>
							<tr class="Productivite" style="display:none;"><td bgcolor="#d5f2ff" style="border-top:2px dotted #0b6acb;" colspan="2" height="4"></td></tr>
							<tr class="Productivite" style="display:none;">
								<td colspan="2" bgcolor="#d5f2ff" style="color:#000000;">
								<?php if($_SESSION['Langue']=="EN"){echo nl2br(str_replace("\\","",stripslashes($LigneAide['EN'])));}else{echo nl2br(str_replace("\\","",stripslashes($LigneAide['FR'])));} ?>
								</td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td valign="center" colspan="2">
									<div id="chart_Productivite" style="width:100%;height:300px;"></div>
									<script>
										// Create chart1 instance
										var chart1 = am4core.create("chart_Productivite", am4charts.XYChart);

										// Add data
										chart1.data = <?php echo json_encode($productivite); ?>;

										// Create axes
										var categoryAxis = chart1.xAxes.push(new am4charts.CategoryAxis());
										categoryAxis.dataFields.category = "Mois";
										categoryAxis.renderer.grid.template.location = 0;
										categoryAxis.renderer.minGridDistance = 15;
										categoryAxis.renderer.labels.template.horizontalCenter = "middle";
										categoryAxis.renderer.labels.template.verticalCenter = "middle";
										categoryAxis.renderer.labels.template.rotation = 270;
										categoryAxis.tooltip.disabled = true;
										categoryAxis.renderer.minHeight = 0;
										categoryAxis.renderer.cellStartLocation = 0.1;
										categoryAxis.renderer.cellEndLocation = 0.9;

										var valueAxis = chart1.yAxes.push(new am4charts.ValueAxis());
										valueAxis.renderer.minWidth = 0;
										valueAxis.min= 0;
										valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Productivity");}else{echo json_encode(utf8_encode("Productivité"));} ?>;
										
										// Create series
										var series = chart1.series.push(new am4charts.ColumnSeries());
										series.dataFields.valueY = "ProductiviteBrut";
										series.dataFields.categoryX = "Mois";
										series.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Gross Productivity");}else{echo json_encode(utf8_encode("Productivité Brute"));} ?>;
										series.tooltipText = "[{Mois}: bold]{valueY}[/]";
										
										var columnTemplate = series.columns.template;
										columnTemplate.strokeWidth = 1;
										columnTemplate.strokeOpacity = 1;
										columnTemplate.stroke = series.fill;

										var series = chart1.series.push(new am4charts.ColumnSeries());
										series.dataFields.valueY = "ProductiviteCorrigee";
										series.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Corrected Productivity");}else{echo json_encode(utf8_encode("Producitivité Corrigée"));} ?>;
										series.dataFields.categoryX = "Mois";
										series.tooltipText = "[{Mois}: bold]{valueY}[/]";
										series.strokeWidth = 1;
										series.stroke  = "#3ad000";
										series.fill  = "#3ad000";
										
										var columnTemplate = series.columns.template;
										columnTemplate.strokeWidth = 1;
										columnTemplate.strokeOpacity = 1;
										columnTemplate.stroke = series.fill;
										
										var series2 = chart1.series.push(new am4charts.LineSeries());
										series2.dataFields.valueY = "Objectif";
										series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Objectif");}else{echo json_encode("Objectif");} ?>;
										series2.dataFields.categoryX = "Mois";
										series2.tooltipText = "[{Mois}: bold]{valueY}[/]";
										series2.strokeWidth = 2;
										series2.stroke  = "#d00000";
										series2.fill  = "#d00000";
										
										/* Add legend */
										chart1.legend = new am4charts.Legend();

										// Cursor
										chart1.cursor = new am4charts.XYCursor();
										chart1.cursor.behavior = "panX";
										chart1.cursor.lineX.opacity = 0;
										chart1.cursor.lineY.opacity = 0;
										
										chart1.exporting.menu = new am4core.ExportMenu();
										
										chart1.exporting.menu.items =
										[
										  {
											"label": "...",
											"menu": [
											  {
												"label": "Image",
												"menu": [
												  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 }  },
												  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 }  },
												  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 }  }
												]
											  }, {
												"label": "Data",
												"menu": [
												  { "type": "csv", "label": "CSV" },
												  { "type": "xlsx", "label": "XLSX" },
												  { "type": "html", "label": "HTML" }
												]
											  }
											]
										  }
										];

										
									</script>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%" valign="top" colspan="2">
						<?php 
							$req="SELECT FR,EN
							FROM moris_aideparagraphe
							WHERE moris_aideparagraphe.NomParagraphe='MANAGEMENT' ";
							$resultAide=mysqli_query($bdd,$req);
							$LigneAide=mysqli_fetch_array($resultAide);
						?>
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" colspan="2" style="font-size:15px;">
								<input type="checkbox" id="checkManagement" name="checkManagement" checked>
								<?php if($_SESSION['Langue']=="EN"){echo "MANAGEMENT";}else{echo "MANAGEMENT";} ?></td>
								<td style="cursor:pointer;" align="right"><div onclick="AfficheAide('Management')"><img id="Management" width="30px" src='../../Images/Aide2.png' border='0' /></div></td>
							</tr>
							<tr class="Management" style="display:none;"><td height="4"></td></tr>
							<tr class="Management" style="display:none;"><td bgcolor="#d5f2ff" style="border-top:2px dotted #0b6acb;" colspan="4" height="4"></td></tr>
							<tr class="Management" style="display:none;">
								<td colspan="4" bgcolor="#d5f2ff" style="color:#000000;">
								<?php if($_SESSION['Langue']=="EN"){echo nl2br(str_replace("\\","",stripslashes($LigneAide['EN'])));}else{echo nl2br(str_replace("\\","",stripslashes($LigneAide['FR'])));} ?>
								</td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="4" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="100%" colspan="4">
									<table width="99%">
										<tr>
										<?php
											$i=0;
											for($nbMois=1;$nbMois<=12;$nbMois++){
												echo "<td width='8.3%' class='Libelle' align='center'>".$arrayMoisLettre[$i]."<br>".$arrayAnnee[$i]."</td>";	
												$i++;
											}
										?>
										</tr>
										<tr>
										<?php
											$i=0;
											for($nbMois=1;$nbMois<=12;$nbMois++){
												if($arrayManagement[$i]==0){echo "<td class='Libelle' align='center'><img width='35px' src='../../Images/VisageContent.png' border='0' /></td>";}
												elseif($arrayManagement[$i]==1){echo "<td class='Libelle' align='center'><img width='35px' src='../../Images/VisageMoyen.png' border='0' /></td>";}	
												elseif($arrayManagement[$i]==2){echo "<td class='Libelle' align='center'><img width='35px' src='../../Images/VisagePasContent.png' border='0' /></td>";}
												else{echo "<td class='Libelle' align='center'></td>";}
												$i++;
											}
										?>
										</tr>
										<tr>
												<?php echo $evenements; ?>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%" valign="top" height="350px;">
						<?php 
							$req="SELECT FR,EN
							FROM moris_aideparagraphe
							WHERE moris_aideparagraphe.NomParagraphe='QUALITE' ";
							$resultAide=mysqli_query($bdd,$req);
							$LigneAide=mysqli_fetch_array($resultAide);
						?>
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;" colspan="2">
								<input type="checkbox" id="checkOTD" name="checkOTD" checked>
								<?php if($_SESSION['Langue']=="EN"){echo "On Time Delivery (OTD)";}else{echo "On Time Delivery (OTD)";} ?></td>
								<td style="cursor:pointer;" align="right"><div onclick="AfficheAide('Qualite')"><img id="Qualite" width="30px" src='../../Images/Aide2.png' border='0' /></div></td>
							</tr>
							<tr class="Qualite" style="display:none;"><td height="4"></td></tr>
							<tr class="Qualite" style="display:none;"><td bgcolor="#d5f2ff" style="border-top:2px dotted #0b6acb;" colspan="4" height="4"></td></tr>
							<tr class="Qualite" style="display:none;">
								<td colspan="4" bgcolor="#d5f2ff" style="color:#000000;">
								<?php if($_SESSION['Langue']=="EN"){echo nl2br(str_replace("\\","",stripslashes($LigneAide['EN'])));}else{echo nl2br(str_replace("\\","",stripslashes($LigneAide['FR'])));} ?>
								</td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="4" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="85%" valign="top">
									<div id="chart_OTD" style="width:100%;height:300px"></div>
									<script>
										// Create chart instance
										var chart2 = am4core.create("chart_OTD", am4charts.XYChart);

										// Add data
										chart2.data = <?php echo json_encode($arrayOTD); ?>;
										chart2.numberFormatter.numberFormat = "#' %'";

										// Create axes
										var categoryAxis = chart2.xAxes.push(new am4charts.CategoryAxis());
										categoryAxis.dataFields.category = "Mois";
										categoryAxis.renderer.grid.template.location = 0;
										categoryAxis.renderer.minGridDistance = 30;
										categoryAxis.renderer.labels.template.horizontalCenter = "right";
										categoryAxis.renderer.labels.template.verticalCenter = "middle";
										categoryAxis.renderer.labels.template.rotation = 270;
										categoryAxis.tooltip.disabled = true;
										categoryAxis.renderer.minHeight = 0;
										
										var valueAxis = chart2.yAxes.push(new am4charts.ValueAxis());
										valueAxis.tooltip.disabled = true;
										valueAxis.renderer.axisFills.template.disabled = true;
										valueAxis.renderer.ticks.template.disabled = true;
										valueAxis.renderer.minWidth = 0;
										valueAxis.min= 0;
										valueAxis.max= 100;
										valueAxis.strictMinMax= true;
										valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("OTD %");}else{echo json_encode(utf8_encode("OTD %"));} ?>;
										

										// Create series
										var series1 = chart2.series.push(new am4charts.ColumnSeries());
										series1.columns.template.width = am4core.percent(80);
										series1.tooltipText = "{name}: {valueY}";
										series1.dataFields.categoryX = "Mois";
										series1.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% Conform");}else{echo json_encode("% Conforme");} ?>;
										series1.dataFields.valueY = "NbConforme";
										series1.stacked = true;
										series1.stroke  = "#8dd7cf";
										series1.fill  = "#8dd7cf";
										series1.sequencedInterpolation = true;
										
										var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY.value}";
										bullet1.label.fontSize = 9;
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;
										
										<?php if($Ligne['ToleranceOTDOQD']==1){?>
										var series3 = chart2.series.push(new am4charts.ColumnSeries());
										series3.columns.template.width = am4core.percent(80);
										series3.tooltipText = "{name}: {valueY}";
										series3.dataFields.categoryX = "Mois";
										series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% Tolerance");}else{echo json_encode("% Tolerance");} ?>;
										series3.dataFields.valueY = "NbTolerance";
										series3.stacked = true;
										series3.stroke  = "#f3c19d";
										series3.fill  = "#f3c19d";
										series3.sequencedInterpolation = true;
										
										var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
										bullet3.label.text = "{valueY.value}";
										bullet3.label.fontSize = 9;
										bullet3.locationY = 0.5;
										bullet3.label.fill = am4core.color("#ffffff");
										bullet3.interactionsEnabled = false;
										<?php } ?>

										var series4 = chart2.series.push(new am4charts.ColumnSeries());
										series4.columns.template.width = am4core.percent(80);
										series4.tooltipText = "{name}: {valueY}";
										series4.dataFields.categoryX = "Mois";
										series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% Improper");}else{echo json_encode("% Non-Conforme");} ?>;
										series4.dataFields.valueY = "NbRetour";
										series4.stacked = true;
										series4.stroke  = "#e9a1ac";
										series4.fill  = "#e9a1ac";
										series4.sequencedInterpolation = true;
										
										var bullet4 = series4.bullets.push(new am4charts.LabelBullet());
										bullet4.label.text = "{valueY.value}";
										bullet4.label.fontSize = 9;
										bullet4.locationY = 0.5;
										bullet4.label.fill = am4core.color("#ffffff");
										bullet4.interactionsEnabled = false;

										var series2 = chart2.series.push(new am4charts.LineSeries());
										series2.dataFields.valueY = "Objectif";
										series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Objective");}else{echo json_encode("Objectif");} ?>;
										series2.dataFields.categoryX = "Mois";
										series2.tooltipText = "[{categoryX}: bold]{valueY}[/]";
										series2.strokeWidth = 2;
										series2.minBulletDistance = 10;
										series2.stroke  = "#788896";
										series2.fill  = "#788896";
										series2.sequencedInterpolation = true;
										
										/* Add legend */
										chart2.legend = new am4charts.Legend();
										
										// Cursor
										chart2.cursor = new am4charts.XYCursor();
										chart2.cursor.behavior = "panX";
										chart2.cursor.lineX.opacity = 0;
										chart2.cursor.lineY.opacity = 0;
										
										chart2.exporting.menu = new am4core.ExportMenu();
										
										chart2.exporting.menu.items =
										[
										  {
											"label": "...",
											"menu": [
											  {
												"label": "Image",
												"menu": [
												  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 }  },
												  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 }  },
												  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 }  }
												]
											  }, {
												"label": "Data",
												"menu": [
												  { "type": "csv", "label": "CSV" },
												  { "type": "xlsx", "label": "XLSX" },
												  { "type": "html", "label": "HTML" }
												]
											  }
											]
										  }
										];

	
									</script>
								</td>
								<td width="15%" valign="top" align="right">
									<?php 
										$annee11Mois=date("Y",strtotime($date_11Mois." +0 month"));
										$mois11Mois=date("m",strtotime($date_11Mois." +0 month"));
										
										$req="SELECT DISTINCT Libelle 
											FROM moris_moisprestation_otdoqd
											LEFT JOIN moris_moisprestation
											ON moris_moisprestation_otdoqd.Id_MoisPrestation=moris_moisprestation.Id
											WHERE bOQD=0 
											AND moris_moisprestation.Suppr=0
											AND Id_Prestation=".$PrestationSelect." 
											AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))>='".$annee11Mois.'_'.$mois11Mois."'
											AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))<='".$annee.'_'.$mois."'
											";
										$resultOTDLibelle=mysqli_query($bdd,$req);
										$nbResultaOTDLibelle=mysqli_num_rows($resultOTDLibelle);
										if($nbResultaOTDLibelle>0){
											if($_SESSION['Langue']=="EN"){
												echo "<input type='submit' class='Bouton' name='btn_actualiserOTD' id='btn_actualiserOTD' value='Refresh' /><br>";
											}
											else{
												echo "<input type='submit' class='Bouton' name='btn_actualiserOTD' id='btn_actualiserOTD' value='Actualiser' /><br>";
											}
											echo "
												<div id='Div_wOTD' style='height:300px;overflow:auto;'>
												<table width='99%' cellpadding='0' cellspacing='0'>";
												if($_SESSION['Langue']=="EN"){
													echo "<tr><td class='Libelle'>&nbsp;OTD deliverables</td></tr>";
												}
												else{
													echo "<tr><td class='Libelle'>&nbsp;Livrables OTD</td></tr>";
												}
											?>
											<tr>
												<td class="Libelle">
													<input type="checkbox" name="selectAllOTD" id="selectAllOTD" onclick="SelectionnerTout2('OTD')" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
												</td>
											</tr>
											<?php
											while($rowOTDLibelle=mysqli_fetch_array($resultOTDLibelle)){
												$checked="checked";
												if($_POST && (isset($_POST['btn_actualiserFam']) || isset($_POST['btn_actualiserOTD']) || isset($_POST['btn_actualiserOQD']))){
													$checked="";
													if (isset ($_POST['OTD_']))
													{
														foreach($_POST['OTD_'] as $checkbox)
														{
															if($checkbox==stripslashes($rowOTDLibelle['Libelle'])){
																$checked="checked";
															}
														}
													}
												}
												echo "<tr><td class='Libelle'><input type='checkbox' class='checkOTD' name='OTD_[]' id='OTD_' ".$checked." value='".stripslashes($rowOTDLibelle['Libelle'])."' />&nbsp;".stripslashes($rowOTDLibelle['Libelle'])."</td></tr>";
											}
											
											echo "</table>
											</div>
											";
											
										}
									?>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td align="center" colspan="2">
								<?php echo $tabOTD; ?>
								</td>
							</tr>
						</table>
					</td>
					<td width="50%" valign="top" height="350px;">
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" colspan="2" style="border-bottom:2px solid #0b6acb;font-size:15px;">
								<input type="checkbox" id="checkOQD" name="checkOQD" checked>
								<?php if($_SESSION['Langue']=="EN"){echo "On Quality Delivery (OQD)";}else{echo "On Quality Delivery (OQD)";} ?>&nbsp;</td>
								<td style="cursor:pointer;" align="right">&nbsp;<br><br><br></td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="85%" valign="top">
									<div id="chart_OQD" style="width:100%;height:300px"></div>
									<script>
										// Create chart instance
										var chart3 = am4core.create("chart_OQD", am4charts.XYChart);

										// Add data
										chart3.data = <?php echo json_encode($arrayOQD); ?>;
										chart3.numberFormatter.numberFormat = "#' %'";

										// Create axes
										var categoryAxis = chart3.xAxes.push(new am4charts.CategoryAxis());
										categoryAxis.dataFields.category = "Mois";
										categoryAxis.renderer.grid.template.location = 0;
										categoryAxis.renderer.minGridDistance = 30;
										categoryAxis.renderer.labels.template.horizontalCenter = "right";
										categoryAxis.renderer.labels.template.verticalCenter = "middle";
										categoryAxis.renderer.labels.template.rotation = 270;
										categoryAxis.tooltip.disabled = true;
										categoryAxis.renderer.minHeight = 0;
										
										var valueAxis = chart3.yAxes.push(new am4charts.ValueAxis());
										valueAxis.tooltip.disabled = true;
										valueAxis.renderer.axisFills.template.disabled = true;
										valueAxis.renderer.ticks.template.disabled = true;
										valueAxis.renderer.minWidth = 0;
										valueAxis.min= 0;
										valueAxis.max= 100;
										valueAxis.strictMinMax= true;
										valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("OQD %");}else{echo json_encode(utf8_encode("OQD %"));} ?>;

										// Create series
										var series1 = chart3.series.push(new am4charts.ColumnSeries());
										series1.columns.template.width = am4core.percent(80);
										series1.tooltipText = "{name}: {valueY}";
										series1.dataFields.categoryX = "Mois";
										series1.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% Conform");}else{echo json_encode("% Conforme");} ?>;
										series1.dataFields.valueY = "NbConforme";
										series1.stacked = true;
										series1.stroke  = "#8dd7cf";
										series1.fill  = "#8dd7cf";
										series1.sequencedInterpolation = true;
										
										var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY.value}";
										bullet1.label.fontSize = 9;
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#ffffff");
										bullet1.interactionsEnabled = false;
										
										<?php if($Ligne['ToleranceOTDOQD']==1){?>
										var series3 = chart3.series.push(new am4charts.ColumnSeries());
										series3.columns.template.width = am4core.percent(80);
										series3.tooltipText = "{name}: {valueY}";
										series3.dataFields.categoryX = "Mois";
										series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% Tolerance");}else{echo json_encode("% Tolerance");} ?>;
										series3.dataFields.valueY = "NbTolerance";
										series3.stacked = true;
										series3.stroke  = "#f3c19d";
										series3.fill  = "#f3c19d";
										series3.sequencedInterpolation = true;
										
										var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
										bullet3.label.text = "{valueY.value}";
										bullet3.label.fontSize = 9;
										bullet3.locationY = 0.5;
										bullet3.label.fill = am4core.color("#ffffff");
										bullet3.interactionsEnabled = false;
										<?php } ?>

										var series4 = chart3.series.push(new am4charts.ColumnSeries());
										series4.columns.template.width = am4core.percent(80);
										series4.tooltipText = "{name}: {valueY}";
										series4.dataFields.categoryX = "Mois";
										series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% Improper");}else{echo json_encode("% Non-Conforme");} ?>;
										series4.dataFields.valueY = "NbRetour";
										series4.stacked = true;
										series4.stroke  = "#e9a1ac";
										series4.fill  = "#e9a1ac";
										series4.sequencedInterpolation = true;
										
										var bullet4 = series4.bullets.push(new am4charts.LabelBullet());
										bullet4.label.text = "{valueY.value}";
										bullet4.label.fontSize = 9;
										bullet4.locationY = 0.5;
										bullet4.label.fill = am4core.color("#ffffff");
										bullet4.interactionsEnabled = false;

										var series2 = chart3.series.push(new am4charts.LineSeries());
										series2.dataFields.valueY = "Objectif";
										series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Objective");}else{echo json_encode("Objectif");} ?>;
										series2.dataFields.categoryX = "Mois";
										series2.tooltipText = "[{categoryX}: bold]{valueY}[/]";
										series2.strokeWidth = 2;
										series2.minBulletDistance = 9;
										series2.stroke  = "#788896";
										series2.fill  = "#788896";
										series2.sequencedInterpolation = true;										
										
										/* Add legend */
										chart3.legend = new am4charts.Legend();
										
										// Cursor
										chart3.cursor = new am4charts.XYCursor();
										chart3.cursor.behavior = "panX";
										chart3.cursor.lineX.opacity = 0;
										chart3.cursor.lineY.opacity = 0;
										
										chart3.exporting.menu = new am4core.ExportMenu();
										
										chart3.exporting.menu.items =
										[
										  {
											"label": "...",
											"menu": [
											  {
												"label": "Image",
												"menu": [
												  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 }  },
												  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 }  },
												  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 }  }
												]
											  }, {
												"label": "Data",
												"menu": [
												  { "type": "csv", "label": "CSV" },
												  { "type": "xlsx", "label": "XLSX" },
												  { "type": "html", "label": "HTML" }
												]
											  }
											]
										  }
										];

	
									</script>
								</td>
								<td width="15%" valign="top" align="right">
									<?php 
										$req="SELECT DISTINCT Libelle 
											FROM moris_moisprestation_otdoqd
											LEFT JOIN moris_moisprestation
											ON moris_moisprestation_otdoqd.Id_MoisPrestation=moris_moisprestation.Id
											WHERE bOQD=1 
											AND moris_moisprestation.Suppr=0
											AND Id_Prestation=".$PrestationSelect." 
											AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))>='".$annee11Mois.'_'.$mois11Mois."'
											AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))<='".$annee.'_'.$mois."'
											";
										$resultOQDLibelle=mysqli_query($bdd,$req);
										$nbResultaOQDLibelle=mysqli_num_rows($resultOQDLibelle);
										if($nbResultaOQDLibelle>0){
											if($_SESSION['Langue']=="EN"){
												echo "<input type='submit' class='Bouton' name='btn_actualiserOQD' id='btn_actualiserOQD' value='Refresh' /><br>";
											}
											else{
												echo "<input type='submit' class='Bouton' name='btn_actualiserOQD' id='btn_actualiserOQD' value='Actualiser' /><br>";
											}
											echo "
											<div id='Div_OQD' style='height:300px;overflow:auto;'>
											<table width='99%' cellpadding='0' cellspacing='0'>";
												if($_SESSION['Langue']=="EN"){
													echo "<tr><td class='Libelle'>&nbsp;OTD deliverables</td></tr>";
												}
												else{
													echo "<tr><td class='Libelle'>&nbsp;Livrables OTD</td></tr>";
												}
											?>
											<tr>
												<td class="Libelle">
													<input type="checkbox" name="selectAllOQD" id="selectAllOQD" onclick="SelectionnerTout2('OQD')" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
												</td>
											</tr>
											<?php
											while($rowOTDLibelle=mysqli_fetch_array($resultOQDLibelle)){
												$checked="checked";
												if($_POST && (isset($_POST['btn_actualiserFam']) || isset($_POST['btn_actualiserOTD']) || isset($_POST['btn_actualiserOQD']))){
													$checked="";
													if (isset ($_POST['OQD_']))
													{
														foreach($_POST['OQD_'] as $checkbox)
														{
															if($checkbox==stripslashes($rowOTDLibelle['Libelle'])){
																$checked="checked";
															}
														}
													}
												}
												echo "<tr><td class='Libelle'><input type='checkbox' class='checkOQD' name='OQD_[]' id='OQD_' value='".stripslashes($rowOTDLibelle['Libelle'])."' ".$checked." />&nbsp;".stripslashes($rowOTDLibelle['Libelle'])."</td></tr>";
											}
											
											echo "</table>
											</div>
											";
										}
									?>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td colspan="2" align="center">
								<?php echo $tabOQD; ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center">
			<table width="100%" cellpadding="0" cellspacing="0">			
				<tr>
					
					<td width="50%" valign="top" height="350px;">
						<?php 
							$req="SELECT FR,EN
							FROM moris_aideparagraphe
							WHERE moris_aideparagraphe.NomParagraphe='COMPETENCES' ";
							$resultAide=mysqli_query($bdd,$req);
							$LigneAide=mysqli_fetch_array($resultAide);
						?>
						<table class="TableCompetences" height="100%" width="99%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;">
								<input type="checkbox" id="checkCompetence" name="checkCompetence" checked>
								<?php if($_SESSION['Langue']=="EN"){echo "SKILLS";}else{echo "COMPETENCES";} ?></td>
								<td style="cursor:pointer;" align="right"><div onclick="AfficheAide('Competences')"><img id="Competences" width="30px" src='../../Images/Aide2.png' border='0' /></div></td>
							</tr>
							<tr class="Competences" style="display:none;"><td height="4"></td></tr>
							<tr class="Competences" style="display:none;"><td bgcolor="#d5f2ff" style="border-top:2px dotted #0b6acb;" colspan="2" height="4"></td></tr>
							<tr class="Competences" style="display:none;">
								<td colspan="2" bgcolor="#d5f2ff" style="color:#000000;">
								<?php if($_SESSION['Langue']=="EN"){echo nl2br(str_replace("\\","",stripslashes($LigneAide['EN'])));}else{echo nl2br(str_replace("\\","",stripslashes($LigneAide['FR'])));} ?>
								</td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td valign="top" colspan="2">
									<div id="chart_Competences" style="width:100%;height:300px"></div>
									<script>
										am4core.useTheme(am4themes_animated);
										var interfaceColors = new am4core.InterfaceColorSet();
																
										// Create chart instance
										var chart4 = am4core.create("chart_Competences", am4charts.XYChart);

										// Add data
										chart4.data = <?php echo json_encode($arrayCompetences); ?>;

										// Create axes
										var categoryAxis = chart4.xAxes.push(new am4charts.CategoryAxis());
										categoryAxis.dataFields.category = "Mois";
										categoryAxis.renderer.grid.template.location = 0;
										categoryAxis.renderer.minGridDistance = 30;
										categoryAxis.renderer.labels.template.horizontalCenter = "right";
										categoryAxis.renderer.labels.template.verticalCenter = "middle";
										categoryAxis.renderer.labels.template.rotation = 270;
										categoryAxis.tooltip.disabled = true;
										categoryAxis.renderer.minHeight = 0;
										
										var valueAxis = chart4.yAxes.push(new am4charts.ValueAxis());
										valueAxis.renderer.minWidth = 0;
										valueAxis.extraMin=0.2;
										valueAxis.max=105;
										valueAxis.strictMinMax=true;
										valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Rate (%)");}else{echo json_encode(utf8_encode("Taux (%)"));} ?>;
										
										valueAxis.renderer.gridContainer.background.fill = interfaceColors.getFor("alternativeBackground");
										valueAxis.renderer.gridContainer.background.fillOpacity = 0.02;

										// Create series
										var series = chart4.series.push(new am4charts.LineSeries());
										series.dataFields.valueY = "Competences";
										series.dataFields.categoryX = "Mois";
										series.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Versatility rate");}else{echo json_encode("Taux de polyvalence");} ?>;
										series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
										series.yAxis = valueAxis;
										series.strokeWidth = 2;
										series.minBulletDistance = 10;

										var bullet = series.bullets.push(new am4charts.CircleBullet());
										bullet.circle.radius = 6;
										bullet.circle.fill = am4core.color("#fff");
										bullet.circle.strokeWidth = 3;
										
										var bullet1 = series.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.label.dy = -5;
										bullet1.label.fill = am4core.color("#000a84");
										bullet1.interactionsEnabled = false;
										
										// Create series
										var series2 = chart4.series.push(new am4charts.LineSeries());
										series2.dataFields.valueY = "TauxQualif";
										series2.dataFields.categoryX = "Mois";
										series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Qualification rate");}else{echo json_encode("Taux de qualification");} ?>;
										series2.tooltipText = "[{categoryX}: bold]{valueY}[/]";
										series2.stroke  = "#a53bd3";
										series2.fill  = "#a53bd3";
										series2.strokeWidth = 2;
										series2.yAxis = valueAxis;
										series2.minBulletDistance = 10;

										var bullet2 = series2.bullets.push(new am4charts.CircleBullet());
										bullet2.circle.radius = 6;
										bullet2.circle.fill = am4core.color("#fff");
										bullet2.circle.strokeWidth = 3;
										
										var bullet3 = series2.bullets.push(new am4charts.LabelBullet());
										bullet3.label.text = "{valueY}";
										bullet3.label.dy = 10;
										bullet3.label.fill = am4core.color("#560071");
										bullet3.interactionsEnabled = false;
										
										var valueAxis2 = chart4.yAxes.push(new am4charts.ValueAxis());
										valueAxis2.renderer.minWidth = 0;
										valueAxis2.min= 0;
										valueAxis2.extraMax=0.2;
										valueAxis2.strictMinMax = true;
										valueAxis2.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Nbr");}else{echo json_encode(utf8_encode("Nbr"));} ?>;
										valueAxis2.marginTop = 30;
										
										valueAxis2.renderer.gridContainer.background.fill = interfaceColors.getFor("alternativeBackground");
										valueAxis2.renderer.gridContainer.background.fillOpacity = 0.02;
										
										var series4 = chart4.series.push(new am4charts.ColumnSeries());												
										series4.tooltipText = "{name}: {valueY.value}";
										series4.dataFields.categoryX = "Mois";
										
										series4.dataFields.valueY = "NbMonoCompeteneces";
										series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Nb single-skilled");}else{echo json_encode(utf8_encode("Nb mono compétence"));} ?>;
										
										series4.strokeWidth = 1;
										series4.stroke  = "#f3b479";
										series4.fill  = "#f3b479";
										series4.yAxis = valueAxis2;

										var bullet3 = series4.bullets.push(new am4charts.LabelBullet());
										bullet3.label.text = "{valueY}";
										bullet3.locationY = -0.1;
										bullet3.label.fill = am4core.color("#000000");
										bullet3.interactionsEnabled = false;
										bullet3.fontSize = 10;
										
										chart4.leftAxesContainer.layout = "vertical";
										
										/* Add legend */
										chart4.legend = new am4charts.Legend();
										
										// Cursor
										chart4.cursor = new am4charts.XYCursor();
										chart4.cursor.behavior = "panX";
										chart4.cursor.lineX.opacity = 0;
										chart4.cursor.lineY.opacity = 0;
										
										chart4.exporting.menu = new am4core.ExportMenu();
										
										chart4.exporting.menu.items =
										[
										  {
											"label": "...",
											"menu": [
											  {
												"label": "Image",
												"menu": [
												  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 }  },
												  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 }  },
												  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 }  }
												]
											  }, {
												"label": "Data",
												"menu": [
												  { "type": "csv", "label": "CSV" },
												  { "type": "xlsx", "label": "XLSX" },
												  { "type": "html", "label": "HTML" }
												]
											  }
											]
										  }
										];


									</script>
								</td>
							</tr>
						</table>
					</td>
					<td width="50%" height="350px;">
						<?php 
							$req="SELECT FR,EN
							FROM moris_aideparagraphe
							WHERE moris_aideparagraphe.NomParagraphe='PRM' ";
							$resultAide=mysqli_query($bdd,$req);
							$LigneAide=mysqli_fetch_array($resultAide);
						?>
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" colspan="2" style="font-size:15px;">
								<input type="checkbox" id="checkPRM" name="checkPRM" checked>
								<?php if($_SESSION['Langue']=="EN"){echo "SATISFACTION CLIENTS";}else{echo "SATISFACTION CLIENTS";} ?></td>
								<td style="cursor:pointer;" align="right"><div onclick="AfficheAide('Prm')"><img id="Prm" width="30px" src='../../Images/Aide2.png' border='0' /></div></td>
							</tr>
							<tr class="Prm" style="display:none;"><td height="4"></td></tr>
							<tr class="Prm" style="display:none;"><td bgcolor="#d5f2ff" style="border-top:2px dotted #0b6acb;" colspan="2" height="4"></td></tr>
							<tr class="Prm" style="display:none;">
								<td colspan="2" bgcolor="#d5f2ff" style="color:#000000;">
								<?php if($_SESSION['Langue']=="EN"){echo nl2br(str_replace("\\","",stripslashes($LigneAide['EN'])));}else{echo nl2br(str_replace("\\","",stripslashes($LigneAide['FR'])));} ?>
								</td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="4" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="100%" valign="top">
									<div id="chart_PRM" style="width:100%;height:300px"></div>
									<script>
										// Create chart5 instance
										var chart5 = am4core.create("chart_PRM", am4charts.XYChart);

										// Add data
										chart5.data = <?php echo json_encode($arrayNewPRM); ?>;

										// Create axes
										var categoryAxis = chart5.xAxes.push(new am4charts.CategoryAxis());
										categoryAxis.dataFields.category = "Mois";
										categoryAxis.renderer.grid.template.location = 0;
										categoryAxis.renderer.minGridDistance = 30;
										categoryAxis.renderer.labels.template.horizontalCenter = "middle";
										categoryAxis.renderer.labels.template.verticalCenter = "middle";
										categoryAxis.renderer.labels.template.rotation = 0;
										categoryAxis.tooltip.disabled = true;
										categoryAxis.renderer.minHeight = 0;
										categoryAxis.renderer.cellStartLocation = 0.1;
										categoryAxis.renderer.cellEndLocation = 0.9;

										var valueAxis = chart5.yAxes.push(new am4charts.ValueAxis());
										valueAxis.renderer.minWidth = 0;
										valueAxis.min= 0;
										valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Score");}else{echo json_encode(utf8_encode("Note"));} ?>;

										// Create series
										var series = chart5.series.push(new am4charts.ColumnSeries());
										series.dataFields.valueY = "Note";
										series.dataFields.categoryX = "Mois";
										series.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Average score value");}else{echo json_encode(utf8_encode("Valeur moyenne des notes"));} ?>;
										series.tooltipText = "[{Mois}: bold]{valueY}[/]";
										series.stroke  = "#19ae9f";
										series.fill  = "#8dd7cf";
										
										var columnTemplate = series.columns.template;
										columnTemplate.strokeWidth = 2;
										columnTemplate.strokeOpacity = 1;
										columnTemplate.stroke = series.fill;
										
										var bullet1 = series.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.label.fill = am4core.color("#1c1c1c");
										bullet1.interactionsEnabled = false;
										
										var series2 = chart5.series.push(new am4charts.LineSeries());
										series2.dataFields.valueY = "Objectif";
										series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Objectif");}else{echo json_encode("Objectif");} ?>;
										series2.dataFields.categoryX = "Mois";
										series2.tooltipText = "[{Mois}: bold]{valueY}[/]";
										series2.strokeWidth = 2;
										series2.stroke  = "#788896";
										series2.fill  = "#788896";
										
										/* Add legend */
										chart5.legend = new am4charts.Legend();

										// Cursor
										chart5.cursor = new am4charts.XYCursor();
										chart5.cursor.behavior = "panX";
										chart5.cursor.lineX.opacity = 0;
										chart5.cursor.lineY.opacity = 0;
										
										chart5.exporting.menu = new am4core.ExportMenu();
										
										chart5.exporting.menu.items =
										[
										  {
											"label": "...",
											"menu": [
											  {
												"label": "Image",
												"menu": [
												  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 }  },
												  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 }  },
												  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 }  }
												]
											  }, {
												"label": "Data",
												"menu": [
												  { "type": "csv", "label": "CSV" },
												  { "type": "xlsx", "label": "XLSX" },
												  { "type": "html", "label": "HTML" }
												]
											  }
											]
										  }
										];

									</script>
								</td>
							</tr>
							<tr>
								<td valign="center" align="center">
									<table style="border:1px dotted black" width="60%">
										<tr>
											<td>&nbsp;1 : <?php if($_SESSION['Langue']=="EN"){echo "Insufficient";}else{echo "Insuffisant";} ?></td>

											<td>&nbsp;2 : <?php if($_SESSION['Langue']=="EN"){echo "Average";}else{echo "Moyen";} ?></td>

											<td>&nbsp;3 : <?php if($_SESSION['Langue']=="EN"){echo "Satisfactory";}else{echo "Satisfaisant";} ?></td>
	
											<td>&nbsp;4 : <?php if($_SESSION['Langue']=="EN"){echo "Very satisfactory";}else{echo "Très satisfaisant";} ?></td>
										</tr>
									</table>
								</tD>
							</tr>
							<tr><td height="4"></td></tr>
							<tr colspan="4">
								<td align="center">
								<?php echo $tabPRM; ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%" valign="top" height="350px;">
						<?php 
							$req="SELECT FR,EN
							FROM moris_aideparagraphe
							WHERE moris_aideparagraphe.NomParagraphe='SECURITE' ";
							$resultAide=mysqli_query($bdd,$req);
							$LigneAide=mysqli_fetch_array($resultAide);
						?>
						<table class="TableCompetences" height="100%" width="99%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;">
								<input type="checkbox" id="checkSecurite" name="checkSecurite" checked>
								<?php if($_SESSION['Langue']=="EN"){echo "SECURITY";}else{echo "SECURITE";} ?></td>
								<td style="cursor:pointer;" align="right"><div onclick="AfficheAide('Securite')"><img id="Securite" width="30px" src='../../Images/Aide2.png' border='0' /></div></td>
							</tr>
							<tr class="Securite" style="display:none;"><td height="4"></td></tr>
							<tr class="Securite" style="display:none;"><td bgcolor="#d5f2ff" style="border-top:2px dotted #0b6acb;" colspan="2" height="4"></td></tr>
							<tr class="Securite" style="display:none;">
								<td colspan="2" bgcolor="#d5f2ff" style="color:#000000;">
								<?php if($_SESSION['Langue']=="EN"){echo nl2br(str_replace("\\","",stripslashes($LigneAide['EN'])));}else{echo nl2br(str_replace("\\","",stripslashes($LigneAide['FR'])));} ?>
								</td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td colspan="2">
									<div id="chart_Securite" style="width:100%;height:300px"></div>
									<script>
										// Create chart6 instance
										var chart6 = am4core.create("chart_Securite", am4charts.XYChart);

										// Add data
										chart6.data = <?php echo json_encode($arraySecurite); ?>;

										// Create axes
										var categoryAxis = chart6.xAxes.push(new am4charts.CategoryAxis());
										categoryAxis.dataFields.category = "Mois";
										categoryAxis.renderer.grid.template.location = 0;
										categoryAxis.renderer.minGridDistance = 30;
										categoryAxis.renderer.labels.template.horizontalCenter = "right";
										categoryAxis.renderer.labels.template.verticalCenter = "middle";
										categoryAxis.renderer.labels.template.rotation = 270;
										categoryAxis.tooltip.disabled = true;
										categoryAxis.renderer.minHeight = 0;

										var valueAxis = chart6.yAxes.push(new am4charts.ValueAxis());
										valueAxis.renderer.minWidth = 0;
										valueAxis.min= 0;
										valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of accidents");}else{echo json_encode(utf8_encode("Nombre d'accidents"));} ?>;

										// Create series
										var series1 = chart6.series.push(new am4charts.ColumnSeries());
										series1.columns.template.width = am4core.percent(80);
										series1.tooltipText = "{categoryX}: {valueY.value}";
										series1.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of commuting accidents");}else{echo json_encode(utf8_encode("Nombre d'accident de trajet"));}?>;
										series1.dataFields.categoryX = "Mois";
										series1.dataFields.valueY = "NbTrajet";
										series1.stacked = true;
										series1.stroke  = "#3d7ad5";
										series1.fill  = "#3d7ad5";
										
										var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#000000");
										bullet1.interactionsEnabled = false;


										var series2 = chart6.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{categoryX}: {valueY.value}";
										series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of accidents with lost time");}else{echo json_encode(utf8_encode("Nombre d'accident avec arrêt de travail"));}?>;
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "NbNonTrajetAvecArret";
										series2.stacked = true;
										series2.stroke  = "#dbb637";
										series2.fill  = "#dbb637";
										
										var bullet2 = series2.bullets.push(new am4charts.LabelBullet());
										bullet2.label.text = "{valueY}";
										bullet2.locationY = 0.5;
										bullet2.label.fill = am4core.color("#000000");
										bullet2.interactionsEnabled = false;
										
										var series3 = chart6.series.push(new am4charts.ColumnSeries());
										series3.columns.template.width = am4core.percent(80);
										series3.tooltipText = "{categoryX}: {valueY.value}";
										series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of accidents without work stoppage");}else{echo json_encode(utf8_encode("Nombre d'accident sans arrêt de travail"));}?>;
										series3.dataFields.categoryX = "Mois";
										series3.dataFields.valueY = "NbNonTrajetSansArret";
										series3.stacked = true;
										series3.stroke  = "#9fb1c5";
										series3.fill  = "#9fb1c5";
										
										var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
										bullet3.label.text = "{valueY}";
										bullet3.locationY = 0.5;
										bullet3.label.fill = am4core.color("#000000");
										bullet3.interactionsEnabled = false;
										
										chart6.legend = new am4charts.Legend();
										
										
										// Cursor
										chart6.cursor = new am4charts.XYCursor();
										chart6.cursor.behavior = "panX";
										chart6.cursor.lineX.opacity = 0;
										chart6.cursor.lineY.opacity = 0;
										
										chart6.exporting.menu = new am4core.ExportMenu();
										
										chart6.exporting.menu.items =
										[
										  {
											"label": "...",
											"menu": [
											  {
												"label": "Image",
												"menu": [
												  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 }  },
												  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 }  },
												  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 }  }
												]
											  }, {
												"label": "Data",
												"menu": [
												  { "type": "csv", "label": "CSV" },
												  { "type": "xlsx", "label": "XLSX" },
												  { "type": "html", "label": "HTML" }
												]
											  }
											]
										  }
										];


									</script>
								</td>
							</tr>
							<tr>
								<td width="100%" colspan="4">
									<?php echo $accidents; ?>
								</td>
							</tr>
						</table>
					</td>
					<td width="50%" height="350px;">
						<?php 
							$req="SELECT FR,EN
							FROM moris_aideparagraphe
							WHERE moris_aideparagraphe.NomParagraphe='NC' ";
							$resultAide=mysqli_query($bdd,$req);
							$LigneAide=mysqli_fetch_array($resultAide);
						?>
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;">
								<input type="checkbox" id="checkNC" name="checkNC" checked>
								<?php if($_SESSION['Langue']=="EN"){echo "NC & RC NEWS";}else{echo "NOUVELLES NC & RC";} ?></td>
								<td style="cursor:pointer;" align="right"><div onclick="AfficheAide('Nc')"><img id="Nc" width="30px" src='../../Images/Aide2.png' border='0' /></div></td>
							</tr>
							<tr class="Nc" style="display:none;"><td height="4"></td></tr>
							<tr class="Nc" style="display:none;"><td bgcolor="#d5f2ff" style="border-top:2px dotted #0b6acb;" colspan="2" height="4"></td></tr>
							<tr class="Nc" style="display:none;">
								<td colspan="2" bgcolor="#d5f2ff" style="color:#000000;">
								<?php if($_SESSION['Langue']=="EN"){echo nl2br(str_replace("\\","",stripslashes($LigneAide['EN'])));}else{echo nl2br(str_replace("\\","",stripslashes($LigneAide['FR'])));} ?>
								</td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td valign="top" colspan="2">
									<div id="chart_NC" style="width:100%;height:300px"></div>
									<script>
										// Create chart7 instance
										var chart7 = am4core.create("chart_NC", am4charts.XYChart);

										// Add data
										chart7.data = <?php echo json_encode($arrayNbNC); ?>;

										// Create axes
										var categoryAxis = chart7.xAxes.push(new am4charts.CategoryAxis());
										categoryAxis.dataFields.category = "Mois";
										categoryAxis.renderer.grid.template.location = 0;
										categoryAxis.renderer.minGridDistance = 30;
										categoryAxis.renderer.labels.template.horizontalCenter = "right";
										categoryAxis.renderer.labels.template.verticalCenter = "middle";
										categoryAxis.renderer.labels.template.rotation = 270;
										categoryAxis.tooltip.disabled = true;
										categoryAxis.renderer.minHeight = 0;

										var valueAxis = chart7.yAxes.push(new am4charts.ValueAxis());
										valueAxis.renderer.minWidth = 0;
										valueAxis.min= 0;
										valueAxis.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of NC / RC");}else{echo json_encode(utf8_encode("Nombre de NC/RC"));} ?>;
										
										// Create series
										var series1 = chart7.series.push(new am4charts.ColumnSeries());
										series1.columns.template.width = am4core.percent(80);
										
										series1.tooltipText = "{categoryX}: {valueY.value}";
										series1.dataFields.categoryX = "Mois";
										series1.dataFields.valueY = "NC1";
										series1.name = <?php echo json_encode($arrayLegendeNC[0]); ?>;
										series1.stacked = true;
										series1.stroke  = "#3d7ad5";
										series1.fill  = "#3d7ad5";
										
										var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#000000");
										bullet1.interactionsEnabled = false;
										
										// Create series
										var series2 = chart7.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{categoryX}: {valueY.value}";
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "NC2";
										series2.name = <?php echo json_encode($arrayLegendeNC[1]); ?>;
										series2.stacked = true;
										series2.stroke  = "#29dae9";
										series2.fill  = "#29dae9";
										
										var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#000000");
										bullet1.interactionsEnabled = false;
										
										var series2 = chart7.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{categoryX}: {valueY.value}";
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "NC3";
										series2.name = <?php echo json_encode($arrayLegendeNC[2]); ?>;
										series2.stacked = true;
										series2.stroke  = "#f8ff6d";
										series2.fill  = "#f8ff6d";
										
										var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#000000");
										bullet1.interactionsEnabled = false;
										
										var series2 = chart7.series.push(new am4charts.ColumnSeries());
										series2.columns.template.width = am4core.percent(80);
										series2.tooltipText = "{categoryX}: {valueY.value}";
										series2.dataFields.categoryX = "Mois";
										series2.dataFields.valueY = "RC";
										series2.name = <?php echo json_encode($arrayLegendeNC[3]); ?>;
										series2.stacked = true;
										series2.stroke  = "#f3b479";
										series2.fill  = "#f3b479";
										
										var bullet1 = series2.bullets.push(new am4charts.LabelBullet());
										bullet1.label.text = "{valueY}";
										bullet1.locationY = 0.5;
										bullet1.label.fill = am4core.color("#000000");
										bullet1.interactionsEnabled = false;
										
										// Cursor
										chart7.cursor = new am4charts.XYCursor();
										chart7.cursor.behavior = "panX";
										chart7.cursor.lineX.opacity = 0;
										chart7.cursor.lineY.opacity = 0;
										
										chart7.exporting.menu = new am4core.ExportMenu();
										
										chart7.legend = new am4charts.Legend();
										
										chart7.exporting.menu.items =
										[
										  {
											"label": "...",
											"menu": [
											  {
												"label": "Image",
												"menu": [
												  { "type": "png", "label": "PNG", "options": { "minWidth": 2000,"minHeight": 2000 }  },
												  { "type": "jpg", "label": "JPG", "options": { "minWidth": 2000,"minHeight": 2000 }  },
												  { "type": "pdf", "label": "PDF", "options": { "minWidth": 2000,"minHeight": 2000 }  }
												]
											  }, {
												"label": "Data",
												"menu": [
												  { "type": "csv", "label": "CSV" },
												  { "type": "xlsx", "label": "XLSX" },
												  { "type": "html", "label": "HTML" }
												]
											  }
											]
										  }
										];

										
									</script>
								</td>
							</tr>
							<tr>
								<td valign="top" colspan="2">
									<?php echo $listeNCDACTotal; ?>
								</td>
							</tr>
						</table>
					</td>
					<?php }?>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center">
			<table width="100%" cellpadding="0" cellspacing="0">			
				<tr>
					
					
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
</table>
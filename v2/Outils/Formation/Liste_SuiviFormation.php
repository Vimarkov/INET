<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function Excel(){
		var w=window.open("Excel_SuiviFormation.php","PageExcel","status=no,menubar=no,scrollbars=yes,width=90,height=90");
		w.focus();
	}
</script>
<?php
if($_POST)
{
	$_SESSION['FiltreSuiviFormation_Plateforme']=$_POST['Plateforme'];
	$_SESSION['FiltreSuiviFormation_Personne']=$_POST['Stagiaire'];
	$_SESSION['FiltreSuiviFormation_DateDebut']=$_POST['DateDebut'];
	$_SESSION['FiltreSuiviFormation_DateFin']=$_POST['DateFin'];
	$_SESSION['FiltreSuiviFormation_DateFinContrat']=$_POST['DateFinContrat'];
	$_SESSION['FiltreSuiviFormation_Formation']=$_POST['Formation'];
	$_SESSION['FiltreSuiviFormation_Organisme']=$_POST['Organisme'];
	$_SESSION['FiltreSuiviFormation_TypeFormation']=$_POST['TypeFormation'];
	
	$_SESSION['FiltreSuiviFormation_DdePriseEnvoyee']=$_POST['DdePriseEnvoyee'];
	$_SESSION['FiltreSuiviFormation_AccordPriseEnCharge']=$_POST['AccordPriseEnCharge'];
	$_SESSION['FiltreSuiviFormation_TraitementConvention']=$_POST['TraitementConvention'];
	$_SESSION['FiltreSuiviFormation_Motif']=$_POST['Motif'];
	$_SESSION['FiltreSuiviFormation_FeuillePresence']=$_POST['FeuillePresence'];
	$_SESSION['FiltreSuiviFormation_AttestationFormation']=$_POST['AttestationFormation'];
	$_SESSION['FiltreSuiviFormation_EvaluationAChaud']=$_POST['EvaluationAChaud'];
	$_SESSION['FiltreSuiviFormation_RemplissageExtranet']=$_POST['RemplissageExtranet'];
	$_SESSION['FiltreSuiviFormation_HabilitationConduite']=$_POST['HabilitationConduite'];
}
if(isset($_GET['Tri']))
{
	$tab = array("Id","Reference","Hrbp","Responsable","CodeAnalytique","Matricule","Personne","Contrat","ETT","DateFinContrat","CSP","Sexe","Age","SalaireHoraireCharge","Formation","Type","Organisme","TypeCours","Categorie","InterIntra","DateDebut","DateFin","NbHeures","NbJours","Cout","CoutSalarial","DdePriseEnChargeEnvoyee","AccordPriseEnCharge","TraitementConvention","PresentAbsent","MotifAbs","FeuillePresence","AttestationFormation","EvaluationAChaud","RemplissageExtranet","HabilitationExtranet");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriSuiviFormation_General']= str_replace($tri." ASC,","",$_SESSION['TriSuiviFormation_General']);
			$_SESSION['TriSuiviFormation_General']= str_replace($tri." DESC,","",$_SESSION['TriSuiviFormation_General']);
			$_SESSION['TriSuiviFormation_General']= str_replace($tri." ASC","",$_SESSION['TriSuiviFormation_General']);
			$_SESSION['TriSuiviFormation_General']= str_replace($tri." DESC","",$_SESSION['TriSuiviFormation_General']);
			if($_SESSION['TriSuiviFormation_'.$tri]==""){$_SESSION['TriSuiviFormation_'.$tri]="ASC";$_SESSION['TriSuiviFormation_General'].= $tri." ".$_SESSION['TriSuiviFormation_'.$tri].",";}
			elseif($_SESSION['TriSuiviFormation_'.$tri]=="ASC"){$_SESSION['TriSuiviFormation_'.$tri]="DESC";$_SESSION['TriSuiviFormation_General'].= $tri." ".$_SESSION['TriSuiviFormation_'.$tri].",";}
			else{$_SESSION['TriSuiviFormation_'.$tri]="";}
		}
	}
}
Ecrire_Code_JS_Init_Date(); 

?>
<form id="formulaire" width="100%" action="Liste_SuiviFormation.php" method="post">
	<div>
		<table style="width:100%">
			<tr>
				<td>
					<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#ea4877;">
						<tr>
							<td class="TitrePage">
							<?php
							echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
							if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
							else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
							echo "</a>";
							echo "&nbsp;&nbsp;&nbsp;";
								
							if($LangueAffichage=="FR"){echo "Suivi des formations";}else{echo "Training follow-up";}
							?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="100%">
					<table style="width:100%; align:center; border-spacing:0;" class="GeneralInfo">
						<tr><td height="4"></td>
							<td>
								<table width="100%">
										<tr>
											<td class="Libelle" width="8%">&nbsp;<?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
											<td width="8%">
												<select id="Plateforme" name="Plateforme" onchange="submit()">
													<?php
													$Plateforme=0;
													$reqPla="SELECT DISTINCT Id_Plateforme, 
														(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle 
														FROM new_competences_personne_poste_plateforme 
														WHERE Id_Poste 
															IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.",".$IdPosteResponsableRH.") 
														AND Id_Personne=".$IdPersonneConnectee." 
														ORDER BY Libelle";
													$resultPlateforme=mysqli_query($bdd,$reqPla);
													$nbFormation=mysqli_num_rows($resultPlateforme);
													if($nbFormation>0)
													{
														$selected="";
														if(isset($_POST['Id_Plateforme']))
														{
															if($_POST['Id_Plateforme']==0){$selected="selected";}
														}
														if(isset($_GET['Id_Plateforme']))
														{
															if($_GET['Id_Plateforme']==0){$selected="selected";}
														}
														while($rowplateforme=mysqli_fetch_array($resultPlateforme))
														{
															$selected="";
															if(isset($_POST['Id_Plateforme']))
															{
																if($_POST['Id_Plateforme']==$rowplateforme['Id_Plateforme']){$selected="selected";}
															}
															if(isset($_GET['Id_Plateforme']))
															{
																if($_GET['Id_Plateforme']==$rowplateforme['Id_Plateforme']){$selected="selected";}
															}
															echo "<option value='".$rowplateforme['Id_Plateforme']."' ".$selected.">".$rowplateforme['Libelle']."</option>\n";
															if($Plateforme==0){$Plateforme=$rowplateforme['Id_Plateforme'];}
														}
													}
													if(isset($_POST['Id_Plateforme'])){$Plateforme=$_POST['Id_Plateforme'];}
													if(isset($_GET['Id_Plateforme'])){$Plateforme=$_GET['Id_Plateforme'];}
													?>
												</select>
											</td>
											<td class="Libelle" width="8%">&nbsp;Type :</td>
											<td width="10%">
												<select name="TypeFormation" id="TypeFormation" onchange="submit()">
													<option value="0"></option>
													<?php
													$TypeForm=$_SESSION['FiltreSuiviFormation_TypeFormation'];
													$resultTypeFormation=mysqli_query($bdd,"SELECT Id, Libelle FROM form_typeformation WHERE Suppr=0 ORDER BY Libelle ASC");
													while($rowTypeFormation=mysqli_fetch_array($resultTypeFormation)){
														$selected="";
														if($TypeForm<>"")
														{
															if($TypeForm==$rowTypeFormation['Id']){$selected="selected";}
														}
														echo '<option value="'.$rowTypeFormation['Id'].'" '.$selected.'>'.stripslashes($rowTypeFormation['Libelle']).'</option>';
													}
													?>
												</select>
											</td>
											<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Date de début";}else{echo "Start date";}?> :</td>
											<?php 
												$dateD="";
												$dateD=$_SESSION['FiltreSuiviFormation_DateDebut'];
											?>
											<td>
												<input type="date" id="DateDebut" name="DateDebut" style="width:110px;" value="<?php echo $dateD; ?>">
											</td>
											<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Date de fin";}else{echo "End date";}?> :</td>	
											<?php 
												$dateF="";
												$dateF=$_SESSION['FiltreSuiviFormation_DateFin'];
											?>
											<td>
												<input type="date" id="DateFin" name="DateFin" style="width:110px;" value="<?php echo $dateF; ?>">
											</td>
											<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Date de fin :<br>de contrat";}else{echo "Date of end :<br>of contract";}?></td>	
											<?php 
												$dateFContrat="";
												$dateFContrat=$_SESSION['FiltreSuiviFormation_DateFinContrat'];
											?>
											<td>
												<input type="date" id="DateFinContrat" name="DateFinContrat" style="width:110px;" value="<?php echo $dateFContrat; ?>">
											</td>
											<td>
											<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
											<div id="filtrer"></div>
										</tr>
										<tr>
											<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";}?> :</td>	
											<?php 
												$formation="";
												$formation=$_SESSION['FiltreSuiviFormation_Formation'];
											?>
											<td><input style="width:200px" id="Formation" name="Formation" value="<?php echo $formation; ?>"></td>
											<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Organisme";}else{echo "Organism";}?> :</td>
											<td align="left">
												<?php
													$organisme=$_SESSION['FiltreSuiviFormation_Organisme'];
												?>
												<select name="Organisme" id="Organisme" onchange="submit()">
													<option value="0" selected></option>
												<?php
													$resultOrganisme=mysqli_query($bdd,"SELECT Id, Libelle FROM form_organisme WHERE Id_Plateforme=".$Plateforme." AND Suppr=0 ORDER BY Libelle ASC");
													$nbOrganisme=mysqli_num_rows($resultOrganisme);
													if($nbOrganisme>0)
													{
														while($rowOrganisme=mysqli_fetch_array($resultOrganisme))
														{
															$selected="";
															if($organisme==$rowOrganisme['Id']){$selected="selected";}
															echo "<option value='".$rowOrganisme['Id']."' ".$selected.">".$rowOrganisme['Libelle']."</option>\n";
														}
													}
												?>
												</select>
											</td>
											<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?> :</td>
											<?php 
												$stagiaire="";
												$stagiaire=$_SESSION['FiltreSuiviFormation_Personne'];
											?>
											<td><input id="Stagiaire" name="Stagiaire" value="<?php echo $stagiaire; ?>"></td>
											<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Motif d'absence";}else{echo "Reason for absence";}?> :</td>	
											<?php 
												$motifAbsence="";
												$motifAbsence=$_SESSION['FiltreSuiviFormation_Motif'];
											?>
											<td colspan="3"><input style="width:200px" id="Motif" name="Motif" value="<?php echo $motifAbsence; ?>"></td>
											<td><img src="..\..\Images\excel.gif" style="cursor : pointer;" onclick="Excel()"></td>
										</tr>
										<tr>
											<td colspan="11">
												<table width="100%">
													<tr>
														<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Dde prise en :<br>charge envoyée";}else{echo "Support request :<br>sent";}?></td>
														<td>
															<select name="DdePriseEnvoyee" id="DdePriseEnvoyee" onchange="submit()">
																<option value="0" selected> </option>
																<?php
																$DdePriseEnChargeEnvoyee=$_SESSION['FiltreSuiviFormation_DdePriseEnvoyee'];
																$selectedVide="";
																$selectedTiret="";
																$selectedX="";
																$selectedAutre="";
																
																if($DdePriseEnChargeEnvoyee=="(vide)"){$selectedVide="selected";}
																elseif($DdePriseEnChargeEnvoyee=="-"){$selectedTiret="selected";}
																elseif($DdePriseEnChargeEnvoyee=="X"){$selectedX="selected";}
																elseif($DdePriseEnChargeEnvoyee=="Autre"){$selectedAutre="selected";}
																
																echo "<option value='(vide)' ".$selectedVide.">(vide)</option>";
																echo "<option value='-' ".$selectedTiret.">-</option>";
																echo "<option value='X' ".$selectedX.">X</option>";
																echo "<option value='Autre' ".$selectedAutre.">Autre</option>";
																?>
															</select>
														</td>
														<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Accord de prise :<br>en charge";}else{echo "Support :<br>agreement";}?></td>
														<td>
															<select name="AccordPriseEnCharge" id="AccordPriseEnCharge" onchange="submit()">
																<option value="0" selected></option>
																<?php
																$AccordPriseEnCharge=$_SESSION['FiltreSuiviFormation_AccordPriseEnCharge'];
																$selectedVide="";
																if($AccordPriseEnCharge=="(vide)"){$selectedVide="selected";}
																echo "<option value='(vide)' ".$selectedVide.">(vide)</option>";
																
																$AccordPriseEnCharge=$_SESSION['FiltreSuiviFormation_AccordPriseEnCharge'];
																$selectedTiret="";
																if($AccordPriseEnCharge=="-"){$selectedTiret="selected";}
																echo "<option value='-' ".$selectedTiret.">-</option>";
																
																$selectedX="";
																if($AccordPriseEnCharge=="X"){$selectedX="selected";}
																echo "<option value='X' ".$selectedX.">X</option>";
																
																$selectedAutre="";
																if($AccordPriseEnCharge=="Autre"){$selectedAutre="selected";}
																echo "<option value='Autre' ".$selectedAutre.">Autre</option>";
																?>
															</select>
														</td>
														<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Traitement :<br>convention";}else{echo "Convention :<br>processing";}?></td>
														<td>
															<select name="TraitementConvention" id="TraitementConvention" onchange="submit()">
																<option value="0" selected></option>
																<?php
																$TraitementConvention=$_SESSION['FiltreSuiviFormation_TraitementConvention'];
																$selectedVide="";
																if($TraitementConvention=="(vide)"){$selectedVide="selected";}
																echo "<option value='(vide)' ".$selectedVide.">(vide)</option>";
																
																$TraitementConvention=$_SESSION['FiltreSuiviFormation_TraitementConvention'];
																$selectedTiret="";
																if($TraitementConvention=="-"){$selectedTiret="selected";}
																echo "<option value='-' ".$selectedTiret.">-</option>";
																
																$selectedX="";
																if($TraitementConvention=="X"){$selectedX="selected";}
																echo "<option value='X' ".$selectedX.">X</option>";
																
																$selectedAutre="";
																if($TraitementConvention=="Autre"){$selectedAutre="selected";}
																echo "<option value='Autre' ".$selectedAutre.">Autre</option>";
																?>
															</select>
														</td>
														<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Feuille de :<br>présence";}else{echo "Timesheet :";}?></td>
														<td>
															<select name="FeuillePresence" id="FeuillePresence" onchange="submit()">
																<option value="0" selected></option>
																<?php
																$FeuillePresence=$_SESSION['FiltreSuiviFormation_FeuillePresence'];
																$selectedVide="";
																if($FeuillePresence=="(vide)"){$selectedVide="selected";}
																echo "<option value='(vide)' ".$selectedVide.">(vide)</option>";
																
																$FeuillePresence=$_SESSION['FiltreSuiviFormation_FeuillePresence'];
																$selectedTiret="";
																if($FeuillePresence=="-"){$selectedTiret="selected";}
																echo "<option value='-' ".$selectedTiret.">-</option>";
																
																$selectedX="";
																if($FeuillePresence=="X"){$selectedX="selected";}
																echo "<option value='X' ".$selectedX.">X</option>";
																
																$selectedAutre="";
																if($FeuillePresence=="Autre"){$selectedAutre="selected";}
																echo "<option value='Autre' ".$selectedAutre.">Autre</option>";
																?>
															</select>
														</td>
														<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Attestation de :<br>formation";}else{echo "Training :<br>certificate";}?></td>
														<td>
															<select name="AttestationFormation" id="AttestationFormation" onchange="submit()">
																<option value="0" selected></option>
																<?php
																$AttestationFormation=$_SESSION['FiltreSuiviFormation_AttestationFormation'];
																$selectedVide="";
																if($AttestationFormation=="(vide)"){$selectedVide="selected";}
																echo "<option value='(vide)' ".$selectedVide.">(vide)</option>";
																
																$AttestationFormation=$_SESSION['FiltreSuiviFormation_AttestationFormation'];
																$selectedTiret="";
																if($AttestationFormation=="-"){$selectedTiret="selected";}
																echo "<option value='-' ".$selectedTiret.">-</option>";
																
																$selectedX="";
																if($AttestationFormation=="X"){$selectedX="selected";}
																echo "<option value='X' ".$selectedX.">X</option>";
																
																$selectedAutre="";
																if($AttestationFormation=="Autre"){$selectedAutre="selected";}
																echo "<option value='Autre' ".$selectedAutre.">Autre</option>";
																?>
															</select>
														</td>
														<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Evaluation :<br>à chaud";}else{echo "Hot :<br>evaluation";}?></td>
														<td>
															<select name="EvaluationAChaud" id="EvaluationAChaud" onchange="submit()">
																<option value="0" selected></option>
																<?php
																$EvaluationAChaud=$_SESSION['FiltreSuiviFormation_EvaluationAChaud'];
																$selectedVide="";
																if($EvaluationAChaud=="(vide)"){$selectedVide="selected";}
																echo "<option value='(vide)' ".$selectedVide.">(vide)</option>";
																
																$EvaluationAChaud=$_SESSION['FiltreSuiviFormation_EvaluationAChaud'];
																$selectedTiret="";
																if($EvaluationAChaud=="-"){$selectedTiret="selected";}
																echo "<option value='-' ".$selectedTiret.">-</option>";
																
																$selectedX="";
																if($EvaluationAChaud=="X"){$selectedX="selected";}
																echo "<option value='X' ".$selectedX.">X</option>";
																
																$selectedAutre="";
																if($EvaluationAChaud=="Autre"){$selectedAutre="selected";}
																echo "<option value='Autre' ".$selectedAutre.">Autre</option>";
																?>
															</select>
														</td>
														<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Remplissage :<br>EXTRANET";}else{echo "EXTRANET :<br>filling";}?></td>
														<td>
															<select name="RemplissageExtranet" id="RemplissageExtranet" onchange="submit()">
																<option value="0" selected></option>
																<?php
																$RemplissageExtranet=$_SESSION['FiltreSuiviFormation_RemplissageExtranet'];
																$selectedVide="";
																if($RemplissageExtranet=="(vide)"){$selectedVide="selected";}
																echo "<option value='(vide)' ".$selectedVide.">(vide)</option>";
																
																$RemplissageExtranet=$_SESSION['FiltreSuiviFormation_RemplissageExtranet'];
																$selectedTiret="";
																if($RemplissageExtranet=="-"){$selectedTiret="selected";}
																echo "<option value='-' ".$selectedTiret.">-</option>";
																
																$selectedX="";
																if($RemplissageExtranet=="X"){$selectedX="selected";}
																echo "<option value='X' ".$selectedX.">X</option>";
																
																$selectedAutre="";
																if($RemplissageExtranet=="Autre"){$selectedAutre="selected";}
																echo "<option value='Autre' ".$selectedAutre.">Autre</option>";
																?>
															</select>
														</td>
														<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Habilitat° :<br>à la conduite";}else{echo "Driving :<br>licenses";}?></td>
														<td>
															<select name="HabilitationConduite" id="HabilitationConduite" onchange="submit()">
																<option value="0" selected></option>
																<?php
																$HabilitationConduite=$_SESSION['FiltreSuiviFormation_HabilitationConduite'];
																$selectedVide="";
																if($HabilitationConduite=="(vide)"){$selectedVide="selected";}
																echo "<option value='(vide)' ".$selectedVide.">(vide)</option>";
																
																$HabilitationConduite=$_SESSION['FiltreSuiviFormation_HabilitationConduite'];
																$selectedTiret="";
																if($HabilitationConduite=="-"){$selectedTiret="selected";}
																echo "<option value='-' ".$selectedTiret.">-</option>";
																
																$selectedX="";
																if($HabilitationConduite=="X"){$selectedX="selected";}
																echo "<option value='X' ".$selectedX.">X</option>";
																
																$selectedAutre="";
																if($HabilitationConduite=="Autre"){$selectedAutre="selected";}
																echo "<option value='Autre' ".$selectedAutre.">Autre</option>";
																?>
															</select>
														</td>
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
		</table>
	</div>
	<div style="width:100%;">
		<div style="width:200%;overflow-y:scroll;">
			<table style="width:100%;border-spacing:0; align:center;" class="GeneralInfo">
				<tr bgcolor="#2c8bb4">
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;" width="3%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=Reference"><?php if($LangueAffichage=="FR"){echo "Référence";}else{echo "Reference";} ?><?php if($_SESSION['TriSuiviFormation_Reference']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_Reference']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#fe344e;" width="3%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=Responsable"><?php if($LangueAffichage=="FR"){echo "Responsable";}else{echo "Responsible";} ?><?php if($_SESSION['TriSuiviFormation_Responsable']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_Responsable']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#9caaae;" width="3%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=CodeAnalytique"><?php if($LangueAffichage=="FR"){echo "Code analy<br>tique";}else{echo "Analy<br>tical code";} ?><?php if($_SESSION['TriSuiviFormation_CodeAnalytique']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_CodeAnalytique']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#9caaae;" width="3%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=Matricule"><?php if($LangueAffichage=="FR"){echo "Matricule";}else{echo "Registration number";} ?><?php if($_SESSION['TriSuiviFormation_Matricule']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_Matricule']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#9caaae;" width="4%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=Personne"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriSuiviFormation_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_Personne']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#9caaae;" width="2%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=Contrat"><?php if($LangueAffichage=="FR"){echo "Contrat";}else{echo "Contract";} ?><?php if($_SESSION['TriSuiviFormation_Contrat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_Contrat']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#fe344e;" width="4%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=ETT"><?php if($LangueAffichage=="FR"){echo "ETT";}else{echo "ETT";} ?><?php if($_SESSION['TriSuiviFormation_ETT']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_ETT']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#fe344e;" width="3%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=DateFinContrat"><?php if($LangueAffichage=="FR"){echo "Date fin contrat";}else{echo "Contract end date";} ?><?php if($_SESSION['TriSuiviFormation_DateFinContrat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_DateFinContrat']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#9caaae;" width="4%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=CSP"><?php if($LangueAffichage=="FR"){echo "CSP";}else{echo "CSP";} ?><?php if($_SESSION['TriSuiviFormation_CSP']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_CSP']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#9caaae;" width="2%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=Sexe"><?php if($LangueAffichage=="FR"){echo "Sexe";}else{echo "Gender";} ?><?php if($_SESSION['TriSuiviFormation_Sexe']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_Sexe']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#9caaae;" width="1%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=Age"><?php if($LangueAffichage=="FR"){echo "Âge";}else{echo "Age";} ?><?php if($_SESSION['TriSuiviFormation_Age']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_Age']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;" width="2%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=SalaireHoraireCharge"><?php if($LangueAffichage=="FR"){echo "Salaire horaire<br>chargé";}else{echo "Hourly rate<br>charged";} ?><?php if($_SESSION['TriSuiviFormation_SalaireHoraireCharge']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_SalaireHoraireCharge']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#09b800;" width="7%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=Formation"><?php if($LangueAffichage=="FR"){echo "Intitulé";}else{echo "Entitled";} ?><?php if($_SESSION['TriSuiviFormation_Formation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_Formation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#09b800;" width="2%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=Type"><?php if($LangueAffichage=="FR"){echo "Interne<br>Externe";}else{echo "Internal<br>External";} ?><?php if($_SESSION['TriSuiviFormation_Type']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_Type']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#09b800;" width="3%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=Organisme"><?php if($LangueAffichage=="FR"){echo "Organisme";}else{echo "Organization";} ?><?php if($_SESSION['TriSuiviFormation_Organisme']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_Organisme']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#09b800;" width="2%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=TypeCours"><?php if($LangueAffichage=="FR"){echo "Type de cours";}else{echo "Type of course";} ?><?php if($_SESSION['TriSuiviFormation_TypeCours']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_TypeCours']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#09b800;" width="4%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=Categorie"><?php if($LangueAffichage=="FR"){echo "Catégorie";}else{echo "Category";} ?><?php if($_SESSION['TriSuiviFormation_Categorie']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_Categorie']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#09b800;" width="2%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=InterIntra"><?php if($LangueAffichage=="FR"){echo "Inter<br>Intra";}else{echo "Inter<br>Intra";} ?><?php if($_SESSION['TriSuiviFormation_InterIntra']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_InterIntra']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#09b800;" width="3%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=DateDebut"><?php if($LangueAffichage=="FR"){echo "Date début";}else{echo "Start date";} ?><?php if($_SESSION['TriSuiviFormation_DateDebut']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_DateDebut']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#09b800;" width="3%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=DateFin"><?php if($LangueAffichage=="FR"){echo "Date fin";}else{echo "End date";} ?><?php if($_SESSION['TriSuiviFormation_DateFin']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_DateFin']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#09b800;" width="2%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=NbHeures"><?php if($LangueAffichage=="FR"){echo "Nb heures";}else{echo "Nb hours";} ?><?php if($_SESSION['TriSuiviFormation_NbHeures']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_NbHeures']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#09b800;" width="2%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=NbJours"><?php if($LangueAffichage=="FR"){echo "Nb jours";}else{echo "Nb of days";} ?><?php if($_SESSION['TriSuiviFormation_NbJours']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_NbJours']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#09b800;" width="2%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=Cout"><?php if($LangueAffichage=="FR"){echo "Coût<br>pédago<br>gique";}else{echo "Educational<br>cost";} ?><?php if($_SESSION['TriSuiviFormation_Cout']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_Cout']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#09b800;" width="2%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=CoutSalarial"><?php if($LangueAffichage=="FR"){echo "Coût<br>salarial";}else{echo "Cost of<br>salary";} ?><?php if($_SESSION['TriSuiviFormation_CoutSalarial']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_CoutSalarial']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#09b800;text-align:center;" width="3%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=DdePriseEnChargeEnvoyee"><?php if($LangueAffichage=="FR"){echo "Dde prise en<br>charge envoyée";}else{echo "Support request<br>sent";} ?><?php if($_SESSION['TriSuiviFormation_DdePriseEnChargeEnvoyee']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_DdePriseEnChargeEnvoyee']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#fe344e;text-align:center;" width="3%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=AccordPriseEnCharge"><?php if($LangueAffichage=="FR"){echo "Accord de prise<br>en charge";}else{echo "Support agreement";} ?><?php if($_SESSION['TriSuiviFormation_AccordPriseEnCharge']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_AccordPriseEnCharge']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#09b800;text-align:center;" width="3%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=TraitementConvention"><?php if($LangueAffichage=="FR"){echo "Traitement<br>convention";}else{echo "Convention<br>processing";} ?><?php if($_SESSION['TriSuiviFormation_TraitementConvention']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_TraitementConvention']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#09b800;text-align:center;" width="3%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=PresentAbsent"><?php if($LangueAffichage=="FR"){echo "Présent (P)<br>Absent (A)";}else{echo "Present (P)<br>Absent (A)";} ?><?php if($_SESSION['TriSuiviFormation_PresentAbsent']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_PresentAbsent']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#fe344e;text-align:center;" width="3%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=MotifAbs"><?php if($LangueAffichage=="FR"){echo "Motif absence";}else{echo "Reason<br>for absence";} ?><?php if($_SESSION['TriSuiviFormation_MotifAbs']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_MotifAbs']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#09b800;text-align:center;" width="3%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=FeuillePresence"><?php if($LangueAffichage=="FR"){echo "Feuille de<br>présence";}else{echo "Timesheet";} ?><?php if($_SESSION['TriSuiviFormation_FeuillePresence']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_FeuillePresence']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#09b800;text-align:center;" width="3%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=AttestationFormation"><?php if($LangueAffichage=="FR"){echo "Attestation de<br>formation";}else{echo "Training<br>certificate";} ?><?php if($_SESSION['TriSuiviFormation_AttestationFormation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_AttestationFormation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#09b800;text-align:center;" width="3%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=EvaluationAChaud"><?php if($LangueAffichage=="FR"){echo "Evaluation<br>à chaud";}else{echo "Hot<br>evaluation";} ?><?php if($_SESSION['TriSuiviFormation_EvaluationAChaud']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_EvaluationAChaud']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#09b800;text-align:center;" width="3%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=RemplissageExtranet"><?php if($LangueAffichage=="FR"){echo "Remplissage<br>EXTRANET";}else{echo "EXTRANET<br>filling";} ?><?php if($_SESSION['TriSuiviFormation_RemplissageExtranet']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_RemplissageExtranet']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;background-color:#09b800;text-align:center;" width="3%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_SuiviFormation.php?Tri=HabilitationExtranet"><?php if($LangueAffichage=="FR"){echo "Habilitat° à<br>la conduite";}else{echo "Driving<br>licenses";} ?><?php if($_SESSION['TriSuiviFormation_HabilitationExtranet']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriSuiviFormation_HabilitationExtranet']=="ASC"){echo "&darr;";}?></a></td>
				</tr>
			</table>
		</div>
		<div style="width:200%;height:800px;overflow-y:scroll;">
		<table style="width:100%;border-spacing:0; align:center;" class="GeneralInfo">
			<?php
					$req="
						SELECT
							IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,
							IF(
							(SELECT Id_TypeContrat FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
							AND (DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) OR DateFin<='0001-01-01' )
							AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
							ORDER BY DateDebut DESC, Id DESC LIMIT 1) IN (1,2,4,11)
							,
							CONCAT((SELECT YEAR(DateSession) FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1),'-',(SELECT LEFT(UCASE(Libelle),2) FROM new_competences_plateforme WHERE Id=form_session.Id_Plateforme),form_session_personne.Id)
							,'-'
							)
							,'-') AS Reference,
							form_session_personne.Id,
							form_session_personne.Validation_Inscription AS Validation_Inscription,
							form_session_personne.SemiPresence,
							(SELECT (SELECT Libelle FROM rh_typecontrat WHERE Id=Id_TypeContrat) FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
							AND (DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) OR DateFin<='0001-01-01' )
							AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
							ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS Contrat,
							(SELECT (SELECT Libelle FROM rh_agenceinterim WHERE Id=Id_AgenceInterim) FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
							AND (DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) OR DateFin<='0001-01-01' )
							AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
							ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS ETT,
							(SELECT IF((SELECT EstInterim FROM rh_typecontrat WHERE Id=Id_TypeContrat)=1,TauxHoraire*1.48,(SalaireBrut/(SELECT NbHeureMois FROM rh_tempstravail WHERE Id=Id_TempsTravail))*1.48) FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
							AND (DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) OR DateFin<='0001-01-01' )
							AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
							ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS SalaireHoraireCharge,
							(SELECT (SELECT (SELECT Libelle FROM rh_classificationmetier WHERE Id=Id_Classification) FROM new_competences_metier WHERE Id=Id_Metier) FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
							AND (DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) OR DateFin<='0001-01-01' )
							AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
							ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS CSP,
							IF((SELECT DateFin FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
							AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
							ORDER BY DateDebut DESC, Id DESC LIMIT 1)<(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1),
							(SELECT DateFin FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
							AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
							ORDER BY DateDebut DESC, Id DESC LIMIT 1),'0001-01-01') AS DateFinContrat,
							(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) AS DateDebut,
							(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession DESC LIMIT 1) AS DateFin,
							(SELECT (SELECT Code_Analytique FROM new_competences_prestation WHERE Id=Id_Prestation)
							FROM rh_personne_mouvement
							WHERE rh_personne_mouvement.Suppr=0
							AND rh_personne_mouvement.Id_Personne=form_session_personne.Id_Personne
							AND EtatValidation=1
							AND rh_personne_mouvement.DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)) LIMIT 1) AS CodeAnalytique,
							(SELECT (SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne)
								FROM new_competences_personne_poste_prestation
								WHERE Id_Poste IN (".$IdPosteCoordinateurEquipe.")
								AND new_competences_personne_poste_prestation.Id_Prestation=rh_personne_mouvement.Id_Prestation
								AND new_competences_personne_poste_prestation.Id_Pole=rh_personne_mouvement.Id_Pole
								AND Backup=0
								LIMIT 1
							)
							FROM rh_personne_mouvement
							WHERE rh_personne_mouvement.Suppr=0
							AND rh_personne_mouvement.Id_Personne=form_session_personne.Id_Personne
							AND EtatValidation=1
							AND rh_personne_mouvement.DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)) LIMIT 1) AS Responsable,
							
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Personne,
							(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Matricule,
							(SELECT Sexe FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Sexe,
							(SELECT if(Date_Naissance<='0001-01-01','',(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(Date_Naissance)), '%Y')+0)) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Age,
							form_session.Id_Formation AS Id_Formation,
							form_session.Recyclage AS Recyclage,
							form_session.Id_Plateforme AS Id_Plateforme,
							(SELECT (SELECT Libelle FROM form_typeformation WHERE Id=Id_TypeFormation) FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) AS Type,
							(SELECT Categorie FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) AS Categorie,
							(SELECT IF(Elearning=0,'Présentiel','E-learning') FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) AS TypeCours,
							
							(SELECT IF(form_session.Recyclage=0,Libelle,LibelleRecyclage)
							FROM form_formation_langue_infos 
							WHERE form_formation_langue_infos.Suppr=0 
							AND form_formation_langue_infos.Id_Langue=(
								SELECT
									Id_Langue
								FROM
								form_formation_plateforme_parametres 
								WHERE
									form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
									AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
									AND Suppr=0 LIMIT 1
							)
							AND form_formation_langue_infos.Id_Formation=form_session.Id_Formation
							LIMIT 1
							) AS Formation,
							(
								SELECT
								(
									SELECT
										Libelle
									FROM
										form_organisme
									WHERE
										Id=Id_Organisme
								)
								FROM
									form_formation_plateforme_parametres 
								WHERE
									form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
								AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
								AND Suppr=0 LIMIT 1
							) AS Organisme ,
							IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,0,
							IF(form_session_personne.Presence<0,0,(SELECT
								IF(form_session.Recyclage=0,Duree,DureeRecyclage)
								FROM
									form_formation_plateforme_parametres 
								WHERE
									form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
								AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
								AND Suppr=0 LIMIT 1
							))) AS NbHeures,
							IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,0,
							IF(form_session_personne.Presence<0,0,(SELECT IF((SELECT EstInterim FROM rh_typecontrat WHERE Id=Id_TypeContrat)=1,TauxHoraire*1.48*(	SELECT
								IF(form_session.Recyclage=0,Duree,DureeRecyclage)
								FROM
									form_formation_plateforme_parametres 
								WHERE
									form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
								AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
								AND Suppr=0 LIMIT 1
							),(SalaireBrut/(SELECT NbHeureMois FROM rh_tempstravail WHERE Id=Id_TempsTravail))*1.48*(	SELECT
								IF(form_session.Recyclage=0,Duree,DureeRecyclage)
								FROM
									form_formation_plateforme_parametres 
								WHERE
									form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
								AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
								AND Suppr=0 LIMIT 1
							)) FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
							AND (DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) OR DateFin<='0001-01-01' )
							AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
							ORDER BY DateDebut DESC, Id DESC LIMIT 1))) AS CoutSalarial,
							IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,0,
							IF(form_session_personne.Presence<0,0,(SELECT
								IF(form_session.Recyclage=0,NbJour,NbJourRecyclage)
								FROM
									form_formation_plateforme_parametres 
								WHERE
									form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
								AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
								AND Suppr=0 LIMIT 1
							))) AS NbJours,
							form_session.InterIntra,
							IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
							IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,DdePriseEnChargeEnvoyee,'-')) AS DdePriseEnChargeEnvoyee,
							IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
							IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,AccordPriseEnCharge,'-')) AS AccordPriseEnCharge,
							IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
							IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,TraitementConvention,'-')) AS TraitementConvention,
							IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
							IF(form_session_personne.Presence=1,'-',MotifAbsence)) AS MotifAbs,
							IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
							IF(form_session_personne.Presence<0,'-',IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,form_session_personne.FeuillePresence,
							IF(form_session_personne.Presence<>0,'X','-')))) AS FeuillePresence,
							form_session_personne.Cout,
							IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
							IF(form_session_personne.Presence=0,'-',IF(form_session_personne.Presence=1,'P','A'))) AS PresentAbsent,
							IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
							IF(form_session_personne.Presence<0,'-',IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,
							IF(AttestationFormation<>'','X','-'),
							IF((SELECT COUNT(form_session_personne_qualification.Id) 
							FROM form_session_personne_qualification 
							WHERE form_session_personne_qualification.Suppr=0 
							AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id)>0,
							IF((SELECT COUNT(form_session_personne_qualification.Id) 
							FROM form_session_personne_qualification 
							WHERE form_session_personne_qualification.Suppr=0 
							AND Etat=0
							AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id),'','X')
							,
							IF((SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession DESC LIMIT 1)<'".date('Y-m-d')."','X','')
							)))) AS AttestationFormation,
							IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
							IF(form_session_personne.Presence<0,'-',IF((SELECT COUNT(form_session_personne_qualification.Id) 
							FROM form_session_personne_qualification 
							WHERE form_session_personne_qualification.Suppr=0 
							AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id)>0,
							IF((SELECT COUNT(form_session_personne_qualification.Id) 
							FROM form_session_personne_qualification 
							WHERE form_session_personne_qualification.Suppr=0 
							AND Etat=0
							AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id),'','X')
							,
							IF((SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession DESC LIMIT 1)<'".date('Y-m-d')."','X','')
							))) AS RemplissageExtranet,
							IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
							IF(form_session_personne.Presence<0,'-',IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,EvaluationAChaud,
							IF((SELECT COUNT(form_session_personne_document.Id) 
							FROM form_session_personne_document 
							WHERE form_session_personne_document.Suppr=0 
							AND form_session_personne_document.Id_Session_Personne=form_session_personne.Id)>0,
							IF((SELECT COUNT(form_session_personne_document.Id) 
							FROM form_session_personne_document 
							WHERE form_session_personne_document.Suppr=0 
							AND Id_Document=6
							AND DateHeureRepondeur=0
							AND form_session_personne_document.Id_Session_Personne=form_session_personne.Id),'','X')
							,
							'-'
							)
							))) AS EvaluationAChaud,
							IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
							IF(form_session_personne.Presence<0,'-',IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,
							IF((SELECT COUNT(form_session_personne_qualification.Id) 
							FROM form_session_personne_qualification 
							WHERE form_session_personne_qualification.Suppr=0 
							AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id
							AND (SELECT COUNT(Id)
							FROM new_competences_qualification_moyen
							WHERE new_competences_qualification_moyen.Id_Qualification=form_session_personne_qualification.Id_Qualification
							AND Suppr=0)>0
							)>0,
							IF((SELECT DateEditionAutorisationTravail FROM new_rh_etatcivil WHERE Id=form_session_personne.Id_Personne)<='0001-01-01','','X')
							,
							'-'
							),
							'-'
							))) AS HabilitationExtranet
						FROM
							form_session_personne 
						LEFT JOIN
							form_session
						ON
							form_session_personne.Id_Session=form_session.Id
						WHERE
							form_session.Suppr=0
							AND form_session.Annule=0
							AND ((form_session_personne.Suppr=0 AND Validation_Inscription=1) OR 
							 (
								(
									(form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0)
									OR form_session_personne.Validation_Inscription=-1
								)
								AND AComptabiliser=1
							 )
							) ";
	
					if($_SESSION['FiltreSuiviFormation_Plateforme']<>0 && $_SESSION['FiltreSuiviFormation_Plateforme']<>""){$req.=" AND form_session.Id_Plateforme=".$_SESSION['FiltreSuiviFormation_Plateforme']." ";}
					if($_SESSION['FiltreSuiviFormation_Personne']<>""){$req.="AND (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) LIKE '%".$_SESSION['FiltreSuiviFormation_Personne']."%' ";}
					if($_SESSION['FiltreSuiviFormation_TypeFormation']>0 && $_SESSION['FiltreSuiviFormation_TypeFormation']<>""){
						$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=".$_SESSION['FiltreSuiviFormation_TypeFormation']." ";
					}
					if($_SESSION['FiltreSuiviFormation_DateDebut']<>"")
					{
						$req.="AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) >= '".TrsfDate_($_SESSION['FiltreSuiviFormation_DateDebut'])."' ";
					}
					if($_SESSION['FiltreSuiviFormation_DateFin']<>"")
					{
						$req.="AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession DESC LIMIT 1) <= '".TrsfDate_($_SESSION['FiltreSuiviFormation_DateFin'])."' ";
					}
					if($_SESSION['FiltreSuiviFormation_DateFinContrat']<>"")
					{
						$req.="AND IF((SELECT DateFin FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
							AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
							ORDER BY DateDebut DESC, Id DESC LIMIT 1)<(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1),
							(SELECT DateFin FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
							AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
							ORDER BY DateDebut DESC, Id DESC LIMIT 1),'0001-01-01') <= '".TrsfDate_($_SESSION['FiltreSuiviFormation_DateFinContrat'])."' ";
					}
					if($_SESSION['FiltreSuiviFormation_Formation']<>""){
						$req.=" AND (SELECT IF(form_session.Recyclage=0,Libelle,LibelleRecyclage)
							FROM form_formation_langue_infos 
							WHERE form_formation_langue_infos.Suppr=0 
							AND form_formation_langue_infos.Id_Langue=(
								SELECT
									Id_Langue
								FROM
								form_formation_plateforme_parametres 
								WHERE
									form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
									AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
									AND Suppr=0 LIMIT 1
							)
							AND form_formation_langue_infos.Id_Formation=form_session.Id_Formation
							LIMIT 1
							) LIKE '%".$_SESSION['FiltreSuiviFormation_Formation']."%' ";
					}
					if($_SESSION['FiltreSuiviFormation_Organisme']>0 && $_SESSION['FiltreSuiviFormation_Organisme']<>""){
						$req.=" AND (
								SELECT
									Id_Organisme
								FROM
									form_formation_plateforme_parametres 
								WHERE
									form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
								AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
								AND Suppr=0 LIMIT 1
							)=".$_SESSION['FiltreSuiviFormation_Organisme']." ";
					}
					if($_SESSION['FiltreSuiviFormation_Motif']<>""){
						$req.="AND IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
							IF(form_session_personne.Presence=1,'-',MotifAbsence)) LIKE '%".$_SESSION['FiltreSuiviFormation_Motif']."%' ";
					}
					if($_SESSION['FiltreSuiviFormation_DdePriseEnvoyee']<>"0"){
						$valeur="='' ";
						if($_SESSION['FiltreSuiviFormation_DdePriseEnvoyee']=="(vide)"){$valeur="='' ";}
						elseif($_SESSION['FiltreSuiviFormation_DdePriseEnvoyee']=="-"){$valeur="='-' ";}
						elseif($_SESSION['FiltreSuiviFormation_DdePriseEnvoyee']=="X"){$valeur="='X' ";}
						elseif($_SESSION['FiltreSuiviFormation_DdePriseEnvoyee']=="Autre"){$valeur=" NOT IN ('','-','X') ";}
						
						$req.="AND IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
							IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,DdePriseEnChargeEnvoyee,'-')) ".$valeur." ";
					}
					if($_SESSION['FiltreSuiviFormation_AccordPriseEnCharge']<>"0"){
						$valeur="='' ";
						if($_SESSION['FiltreSuiviFormation_AccordPriseEnCharge']=="(vide)"){$valeur="='' ";}
						elseif($_SESSION['FiltreSuiviFormation_AccordPriseEnCharge']=="-"){$valeur="='-' ";}
						elseif($_SESSION['FiltreSuiviFormation_AccordPriseEnCharge']=="X"){$valeur="='X' ";}
						elseif($_SESSION['FiltreSuiviFormation_AccordPriseEnCharge']=="Autre"){$valeur=" NOT IN ('','-','X') ";}
						
						$req.="AND IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
							IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,AccordPriseEnCharge,'-')) ".$valeur." ";
					}
					if($_SESSION['FiltreSuiviFormation_TraitementConvention']<>"0"){
						$valeur="='' ";
						if($_SESSION['FiltreSuiviFormation_TraitementConvention']=="(vide)"){$valeur="='' ";}
						elseif($_SESSION['FiltreSuiviFormation_TraitementConvention']=="-"){$valeur="='-' ";}
						elseif($_SESSION['FiltreSuiviFormation_TraitementConvention']=="X"){$valeur="='X' ";}
						elseif($_SESSION['FiltreSuiviFormation_TraitementConvention']=="Autre"){$valeur=" NOT IN ('','-','X') ";}
						
						$req.="AND IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
							IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,TraitementConvention,'-')) ".$valeur." ";
					}
					if($_SESSION['FiltreSuiviFormation_FeuillePresence']<>"0"){
						$valeur="='' ";
						if($_SESSION['FiltreSuiviFormation_FeuillePresence']=="(vide)"){$valeur="='' ";}
						elseif($_SESSION['FiltreSuiviFormation_FeuillePresence']=="-"){$valeur="='-' ";}
						elseif($_SESSION['FiltreSuiviFormation_FeuillePresence']=="X"){$valeur="='X' ";}
						elseif($_SESSION['FiltreSuiviFormation_FeuillePresence']=="Autre"){$valeur=" NOT IN ('','-','X') ";}
						
						$req.="AND IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
							IF(form_session_personne.Presence<0,'-',IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,form_session_personne.FeuillePresence,
							IF(form_session_personne.Presence<>0,'X','-')))) ".$valeur." ";
					}
					if($_SESSION['FiltreSuiviFormation_AttestationFormation']<>"0"){
						$valeur="='' ";
						if($_SESSION['FiltreSuiviFormation_AttestationFormation']=="(vide)"){$valeur="='' ";}
						elseif($_SESSION['FiltreSuiviFormation_AttestationFormation']=="-"){$valeur="='-' ";}
						elseif($_SESSION['FiltreSuiviFormation_AttestationFormation']=="X"){$valeur="='X' ";}
						elseif($_SESSION['FiltreSuiviFormation_AttestationFormation']=="Autre"){$valeur=" NOT IN ('','-','X') ";}
						
						$req.="AND IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
							IF(form_session_personne.Presence<0,'-',IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,
							IF(AttestationFormation<>'','X','-'),
							IF((SELECT COUNT(form_session_personne_qualification.Id) 
							FROM form_session_personne_qualification 
							WHERE form_session_personne_qualification.Suppr=0 
							AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id)>0,
							IF((SELECT COUNT(form_session_personne_qualification.Id) 
							FROM form_session_personne_qualification 
							WHERE form_session_personne_qualification.Suppr=0 
							AND Etat=0
							AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id),'','X')
							,
							IF((SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession DESC LIMIT 1)<'".date('Y-m-d')."','X','')
							)))) ".$valeur." ";
					}
					if($_SESSION['FiltreSuiviFormation_EvaluationAChaud']<>"0"){
						$valeur="='' ";
						if($_SESSION['FiltreSuiviFormation_EvaluationAChaud']=="(vide)"){$valeur="='' ";}
						elseif($_SESSION['FiltreSuiviFormation_EvaluationAChaud']=="-"){$valeur="='-' ";}
						elseif($_SESSION['FiltreSuiviFormation_EvaluationAChaud']=="X"){$valeur="='X' ";}
						elseif($_SESSION['FiltreSuiviFormation_EvaluationAChaud']=="Autre"){$valeur=" NOT IN ('','-','X') ";}
						
						$req.="AND IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
							IF(form_session_personne.Presence<0,'-',IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,EvaluationAChaud,
							IF((SELECT COUNT(form_session_personne_document.Id) 
							FROM form_session_personne_document 
							WHERE form_session_personne_document.Suppr=0 
							AND form_session_personne_document.Id_Session_Personne=form_session_personne.Id)>0,
							IF((SELECT COUNT(form_session_personne_document.Id) 
							FROM form_session_personne_document 
							WHERE form_session_personne_document.Suppr=0 
							AND Id_Document=6
							AND DateHeureRepondeur=0
							AND form_session_personne_document.Id_Session_Personne=form_session_personne.Id),'','X')
							,
							'-'
							)
							))) ".$valeur." ";
					}
					if($_SESSION['FiltreSuiviFormation_RemplissageExtranet']<>"0"){
						$valeur="='' ";
						if($_SESSION['FiltreSuiviFormation_RemplissageExtranet']=="(vide)"){$valeur="='' ";}
						elseif($_SESSION['FiltreSuiviFormation_RemplissageExtranet']=="-"){$valeur="='-' ";}
						elseif($_SESSION['FiltreSuiviFormation_RemplissageExtranet']=="X"){$valeur="='X' ";}
						elseif($_SESSION['FiltreSuiviFormation_RemplissageExtranet']=="Autre"){$valeur=" NOT IN ('','-','X') ";}
						
						$req.="AND IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
							IF(form_session_personne.Presence<0,'-',IF((SELECT COUNT(form_session_personne_qualification.Id) 
							FROM form_session_personne_qualification 
							WHERE form_session_personne_qualification.Suppr=0 
							AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id)>0,
							IF((SELECT COUNT(form_session_personne_qualification.Id) 
							FROM form_session_personne_qualification 
							WHERE form_session_personne_qualification.Suppr=0 
							AND Etat=0
							AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id),'','X')
							,
							IF((SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession DESC LIMIT 1)<'".date('Y-m-d')."','X','')
							))) ".$valeur." ";
					}
					
					if($_SESSION['FiltreSuiviFormation_HabilitationConduite']<>"0"){
						$valeur="='' ";
						if($_SESSION['FiltreSuiviFormation_HabilitationConduite']=="(vide)"){$valeur="='' ";}
						elseif($_SESSION['FiltreSuiviFormation_HabilitationConduite']=="-"){$valeur="='-' ";}
						elseif($_SESSION['FiltreSuiviFormation_HabilitationConduite']=="X"){$valeur="='X' ";}
						elseif($_SESSION['FiltreSuiviFormation_HabilitationConduite']=="Autre"){$valeur=" NOT IN ('','-','X') ";}
						
						$req.="AND IF(((form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0) OR form_session_personne.Validation_Inscription=-1) AND AComptabiliser=1,MotifDesinscription,
							IF(form_session_personne.Presence<0,'-',IF((SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=4,
							IF((SELECT COUNT(form_session_personne_qualification.Id) 
							FROM form_session_personne_qualification 
							WHERE form_session_personne_qualification.Suppr=0 
							AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id
							AND (SELECT COUNT(Id)
							FROM new_competences_qualification_moyen
							WHERE new_competences_qualification_moyen.Id_Qualification=form_session_personne_qualification.Id_Qualification
							AND Suppr=0)>0
							)>0,
							IF((SELECT DateEditionAutorisationTravail FROM new_rh_etatcivil WHERE Id=form_session_personne.Id_Personne)<='0001-01-01','','X')
							,
							'-'
							),
							'-'
							))) ".$valeur." ";
					}
					if($_SESSION['TriSuiviFormation_General']<>""){$req.=" ORDER BY ".substr($_SESSION['TriSuiviFormation_General'],0,-1);}

					$ResultSessions=mysqli_query($bdd,$req);
					$NbSessions=mysqli_num_rows($ResultSessions);
					
					if($NbSessions>0)
					{
						$couleur="#FFFFFF";
						while($row=mysqli_fetch_array($ResultSessions))
						{
							if($couleur=="#FFFFFF"){$couleur="#d9d9d9";}
							else{$couleur="#FFFFFF";}
							
							$DdePriseEnChargeEnvoyee=$row['DdePriseEnChargeEnvoyee'];
							if($row['DdePriseEnChargeEnvoyee']=="-"){$DdePriseEnChargeEnvoyee= "<img src='../../Images/subtract-sign.png' style='border:0;'>";}
							elseif($row['DdePriseEnChargeEnvoyee']=="X"){$DdePriseEnChargeEnvoyee= "<img src='../../Images/tick.png' style='border:0;'>";}
							
							$AccordPriseEnCharge=$row['AccordPriseEnCharge'];
							if($row['AccordPriseEnCharge']=="-"){$AccordPriseEnCharge= "<img src='../../Images/subtract-sign.png' style='border:0;'>";}
							elseif($row['AccordPriseEnCharge']=="X"){$AccordPriseEnCharge= "<img src='../../Images/tick.png' style='border:0;'>";}
							
							$TraitementConvention=$row['TraitementConvention'];
							if($row['TraitementConvention']=="-"){$TraitementConvention= "<img src='../../Images/subtract-sign.png' style='border:0;'>";}
							elseif($row['TraitementConvention']=="X"){$TraitementConvention= "<img src='../../Images/tick.png' style='border:0;'>";}
							
							$FeuillePresence=$row['FeuillePresence'];
							if($row['FeuillePresence']=="-"){$FeuillePresence= "<img src='../../Images/subtract-sign.png' style='border:0;'>";}
							elseif($row['FeuillePresence']=="X"){$FeuillePresence= "<img src='../../Images/tick.png' style='border:0;'>";}
							
							$EvaluationAChaud=$row['EvaluationAChaud'];
							if($row['EvaluationAChaud']=="-"){$EvaluationAChaud= "<img src='../../Images/subtract-sign.png' style='border:0;'>";}
							elseif($row['EvaluationAChaud']=="X"){$EvaluationAChaud= "<img src='../../Images/tick.png' style='border:0;'>";}
							
							$AttestationFormation=$row['AttestationFormation'];
							if($row['AttestationFormation']=="-"){$AttestationFormation= "<img src='../../Images/subtract-sign.png' style='border:0;'>";}
							elseif($row['AttestationFormation']=="X"){$AttestationFormation= "<img src='../../Images/tick.png' style='border:0;'>";}
							
							$RemplissageExtranet=$row['RemplissageExtranet'];
							if($row['RemplissageExtranet']=="-"){$RemplissageExtranet= "<img src='../../Images/subtract-sign.png' style='border:0;'>";}
							elseif($row['RemplissageExtranet']=="X"){$RemplissageExtranet= "<img src='../../Images/tick.png' style='border:0;'>";}
							
							$HabilitationExtranet=$row['HabilitationExtranet'];
							if($row['HabilitationExtranet']=="-"){$HabilitationExtranet= "<img src='../../Images/subtract-sign.png' style='border:0;'>";}
							elseif($row['HabilitationExtranet']=="X"){$HabilitationExtranet= "<img src='../../Images/tick.png' style='border:0;'>";}
							
						?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="3%" style="border-bottom:1px dottom black;"><?php echo stripslashes($row['Reference']); ?></td>
								<td width="3%" style="border-bottom:1px dottom black;"><?php if($row['Responsable']==""){echo "-";}else{echo stripslashes($row['Responsable']);} ?></td>
								<td width="3%" style="border-bottom:1px dottom black;"><?php if($row['CodeAnalytique']==""){echo "-";}else{echo stripslashes($row['CodeAnalytique']);} ?></td>
								<td width="3%" style="border-bottom:1px dottom black;"><?php if($row['Matricule']==""){echo "-";}else{echo stripslashes($row['Matricule']);} ?></td>
								<td width="4%" style="border-bottom:1px dottom black;"><?php echo stripslashes($row['Personne']); ?></td>
								<td width="2%" style="border-bottom:1px dottom black;"><?php if($row['Contrat']==""){echo "-";}else{echo stripslashes($row['Contrat']);} ?></td>
								<td width="4%" style="border-bottom:1px dottom black;"><?php if($row['ETT']==""){echo "-";}else{echo stripslashes($row['ETT']);} ?></td>
								<td width="3%" style="border-bottom:1px dottom black;"><?php if($row['DateFinContrat']=="" || $row['DateFinContrat']=="-" || $row['DateFinContrat']<="0001-01-01"){echo "-";}else{echo AfficheDateJJ_MM_AAAA($row['DateFinContrat']);} ?></td>
								<td width="4%" style="border-bottom:1px dottom black;"><?php if($row['CSP']==""){echo "-";}else{echo stripslashes($row['CSP']);} ?></td>
								<td width="2%" style="border-bottom:1px dottom black;"><?php if($row['Sexe']==""){echo "-";}else{echo stripslashes($row['Sexe']);} ?></td>
								<td width="1%" style="border-bottom:1px dottom black;"><?php if($row['Age']==""){echo "-";}else{echo stripslashes($row['Age']);} ?></td>
								<td width="2%" style="border-bottom:1px dottom black;"><?php if($row['SalaireHoraireCharge']==""){echo "-";}else{echo stripslashes(Round($row['SalaireHoraireCharge'],2));} ?></td>
								<td width="7%" style="border-bottom:1px dottom black;"><?php if($row['Formation']==""){echo "-";}else{echo stripslashes($row['Formation']);} ?></td>
								<td width="2%" style="border-bottom:1px dottom black;"><?php if($row['Type']==""){echo "-";}else{echo stripslashes($row['Type']);} ?></td>
								<td width="3%" style="border-bottom:1px dottom black;"><?php if($row['Organisme']==""){echo "-";}else{echo stripslashes($row['Organisme']);} ?></td>
								<td width="2%" style="border-bottom:1px dottom black;"><?php if($row['TypeCours']==""){echo "-";}else{echo stripslashes($row['TypeCours']);} ?></td>
								<td width="4%" style="border-bottom:1px dottom black;"><?php if($row['Categorie']==""){echo "-";}else{echo stripslashes($row['Categorie']);} ?></td>
								<td width="2%" style="border-bottom:1px dottom black;"><?php if($row['InterIntra']==""){echo "-";}else{echo stripslashes($row['InterIntra']);} ?></td>
								<td width="3%" style="border-bottom:1px dottom black;"><?php echo stripslashes(AfficheDateJJ_MM_AAAA($row['DateDebut'])); ?></td>
								<td width="3%" style="border-bottom:1px dottom black;"><?php echo stripslashes(AfficheDateJJ_MM_AAAA($row['DateFin'])); ?></td>
								<td width="2%" style="border-bottom:1px dottom black;"><?php echo stripslashes($row['NbHeures']); ?></td>
								<td width="2%" style="border-bottom:1px dottom black;"><?php echo stripslashes($row['NbJours']); ?></td>
								<td width="2%" style="border-bottom:1px dottom black;"><?php echo stripslashes($row['Cout']); ?></td>
								<td width="2%" style="border-bottom:1px dottom black;"><?php if($row['CoutSalarial']==""){echo "-";}else{echo stripslashes(Round($row['CoutSalarial'],2));} ?></td>
								<td width="3%" style="border-bottom:1px dottom black;" align="center"><?php echo stripslashes($DdePriseEnChargeEnvoyee); ?></td>
								<td width="3%" style="border-bottom:1px dottom black;" align="center"><?php echo stripslashes($AccordPriseEnCharge); ?></td>
								<td width="3%" style="border-bottom:1px dottom black;" align="center"><?php echo stripslashes($TraitementConvention); ?></td>
								<td width="3%" style="border-bottom:1px dottom black;" align="center"><?php echo stripslashes($row['PresentAbsent']); ?></td>
								<td width="3%" style="border-bottom:1px dottom black;" align="center"><?php echo stripslashes($row['MotifAbs']); ?></td>
								<td width="3%" style="border-bottom:1px dottom black;" align="center"><?php echo stripslashes($FeuillePresence); ?></td>
								<td width="3%" style="border-bottom:1px dottom black;" align="center"><?php echo stripslashes($AttestationFormation); ?></td>
								<td width="3%" style="border-bottom:1px dottom black;" align="center"><?php echo stripslashes($EvaluationAChaud); ?></td>
								<td width="3%" style="border-bottom:1px dottom black;" align="center"><?php echo stripslashes($RemplissageExtranet); ?></td>
								<td width="3%" style="border-bottom:1px dottom black;" align="center"><?php echo stripslashes($HabilitationExtranet); ?></td>
							</tr>
						<?php
						}
					}
			?>
		</table>
		</div>
	</div>
</form>
</html>
	
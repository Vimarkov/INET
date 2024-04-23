<?php
require("Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Mode,Page,Dossier1,Dossier2,Id)
		{window.open("Outils/Modif.php?Mode="+Mode+"&Page="+Page+"&Dossier1="+Dossier1+"&Dossier2="+Dossier2+"&Id="+Id,"PageFichier","status=no,menubar=no,width=600,height=700");}
	function OuvrirImage(Page,Dossier1,Dossier2,Img)
		{window.open("Outils/OuvreImage.php?Page="+Page+"&Dossier1="+Dossier1+"&Dossier2="+Dossier2+"&Image="+Img,"PageImage","status=no,menubar=no,width=600,height=350,resizable=yes");}
	function OuvrirFichier(Page,Dossier1,Dossier2,Fic)
		{window.open("../Upload/Fichiers/"+Page+"/"+Dossier1+"/"+Dossier2+"/"+Fic,"PageFichier","status=no,menubar=no,width="+screen.width-10+",height="+screen.height-10+",resizable=yes");}
	function AfficheTelecharge(){window.status="Faites clic droit pour enregistrer le fichier";}
</script>
<?php
//Réception des infos et mise enb variables
if(isset($_GET['Dossier2'])){$Dossier2=$_GET['Dossier2'];}
else{$Dossier2="";}
$Dossier1=$_GET['Dossier1'];
$Page=$_GET['Page'];

$Droits=Droits_PersonneConnectee_PageExtranet($Page,$Dossier1,$Dossier2);
?>
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing:0;">
				<tr>
					<td class="TitrePage">
						<?php
							switch($Page)
							{
								case "qualite":
									if($LangueAffichage=="FR"){echo "Système Qualité";}else{echo "Quality System";}
									switch($Dossier1)
									{
										case "Filiale": if($LangueAffichage=="FR"){echo " - Filiales";}else{echo " - Subsidiary company";}break;
										case "Certificats": if($LangueAffichage=="FR"){echo " - Certificats";}else{echo " - Certificats";}break;
										case "Normes": if($LangueAffichage=="FR"){echo " - Normes";}else{echo " - Standards";}break;
										case "Docs_Normalises_Airbus": if($LangueAffichage=="FR"){echo " - Docs normalisés Airbus 02/10";}else{echo " - Standardized docs Airbus 02/10";}break;
										case "Normes": if($LangueAffichage=="FR"){echo " - Normes";}else{echo " - Standards";}break;
										case "multiplateforme": if($LangueAffichage=="FR"){echo " - Documents multi-plateformes";}else{echo " - Multiplatform documents";}break;
										case "Fournisseurs": if($LangueAffichage=="FR"){echo " - Fournisseurs";}else{echo " - Suppliers";}break;
										case "MatricesAAA": if($LangueAffichage=="FR"){echo " - Matrices AAA";}else{echo " - AAA Matrix";}break;
										default: echo " - ".$Dossier1;break;
									}
									break;
								case "rh":
									if($LangueAffichage=="FR"){echo "Ressources Humaines";}else{echo "Human ressources";}
									switch($Dossier1)
									{
										case "GestionEffectifs": if($LangueAffichage=="FR"){echo " - Gestion des effectifs";}else{echo " - Workforce management";}break;
										case "AccordEntreprise": if($LangueAffichage=="FR"){echo " - Accords entreprise";}else{echo " - Company agreements";}break;
										case "ConventionCollective": if($LangueAffichage=="FR"){echo " - Convention collective";}else{echo " - Collective agreement";}break;
										case "ConventionsCollectives": if($LangueAffichage=="FR"){echo " - Conventions collectives";}else{echo " - Collective agreements";}break;
										case "PEE": if($LangueAffichage=="FR"){echo " - Plan d'Epargne Entreprise";}else{echo " - Company saving plan";}break;
										case "ReglementInterieur": if($LangueAffichage=="FR"){echo " - Réglement intérieur";}else{echo " - Internal regulations";}break;
									}
									break;
								case "hse":
									if($LangueAffichage=="FR"){echo "Hygiène, Sécurité et Environnement";}else{echo "Health, Safety and Environment";}
									switch($Dossier1)
									{
										case "ManuelSecurite2015": if($LangueAffichage=="FR"){echo " - Manuel Sécurité 2015 (en cours)";}else{echo " - Security Manual 2015 (in progress)";}break;
										case "DocumentsUniques": if($LangueAffichage=="FR"){echo " - Document unique";}else{echo " - Single document";}break;
										case "PlanAction": if($LangueAffichage=="FR"){echo " - Plan d'actions";}else{echo " - Action plan";}break;
										case "LiensCHSCT": if($LangueAffichage=="FR"){echo " - Liens CHSCT";}else{echo " - CHSCT Links";}break;
										default: echo " - ".$Dossier1;break;
									}
									break;
								case "cedpchsct":
									if($Dossier1=="CSE"){
										if($LangueAffichage=="FR"){echo "CSE";}else{echo "CSE";}
									}
									else{
										if($LangueAffichage=="FR"){echo "Instances représentatives du personnel";}else{echo "Representative authority for the staff";}
									}
									
									switch($Dossier1)
									{
										case "CE": if($LangueAffichage=="FR"){echo " - Comité d'entreprise";}else{echo " - Works council";}break;
										case "DP": if($LangueAffichage=="FR"){echo " - Délégués du personnel";}else{echo " - Staff representatives";}break;
										case "ExpressionSyndicale": if($LangueAffichage=="FR"){echo " - Expression syndicale";}else{echo " - Union expression";}break;
										case "CHSCT": echo " - CHSCT Paris";break;
										case "CHSCTTarbes": echo " - CHSCT Tarbes";break;
										case "Activites": if($LangueAffichage=="FR"){echo " - Activités sociales et culturelles";}else{echo " - Entertainment activities";}break;
										default: echo " - ".$Dossier1;break;
									}
									break;
								case "insitu":
									if($LangueAffichage=="FR"){echo "Prestations in-situ";}else{echo "In-Situ activities";}
									switch($Dossier1)
									{
										case "AAATC": echo " - AAA TC";break;
										case "HabilitationElec": echo " - Formation Habilitation Electrique";break;
										case "CommTLSOuest": if($LangueAffichage=="FR"){echo " - Communication interne AAA TLS <-> AAA Ouest";}else{echo " - Internal Communication AAA TLS <-> AAA West ";}break;
										case "CommBureauSites": if($LangueAffichage=="FR"){echo " - AAA-TLS Communication Bureau vers prestations";}else{echo " - AAA-TLS Communication from office to activity ";}break;
										case "Qualite": if($LangueAffichage=="FR"){echo " - AAA-TLS Qualité";}else{echo " - AAA-TLS Quality ";}break;
										default: echo " - ".$Dossier1;break;
									}
									break;
								case "canada":
									if($LangueAffichage=="FR"){echo "Prestations AAA Canada";}else{echo "AAA Canada activities";}
									switch($Dossier1)
									{
										case "Operations": if($LangueAffichage=="FR"){echo " - Opérations et analyses de risques";}else{echo " - Operations and risk analyzes";}break;
										case "Training": if($LangueAffichage=="FR"){echo " - Formations";}else{echo " - Trainings";}break;
										case "QUALITE_OPEX": if($LangueAffichage=="FR"){echo " - Qualité OPEX";}else{echo " - OPEX Quality";}break;
										case "EXPERIENCE_EMPLOYE": if($LangueAffichage=="FR"){echo " - Expérience employé";}else{echo " - Experience employee";}break;
										case "TALENT_ACQUISITION": if($LangueAffichage=="FR"){echo " - Acquisition de talent";}else{echo " - Talent acquisition";}break;
										case "BU_Ontario": if($LangueAffichage=="FR"){echo " - BU-Ontario (Qualité)";}else{echo " - BU-Ontario (Quality)";}break;
										case "GestionDocumentaire": if($LangueAffichage=="FR"){echo " - *GESTION DOCUMENTAIRE AAA CANADA*";}else{echo " - * AAA CANADA DOCUMENT MANAGEMENT *";}break;
										case "ECMECalibration": if($LangueAffichage=="FR"){echo " - ECME – Calibration";}else{echo " - ECME – Calibration";}break;
										default: echo " - ".$Dossier1;break;
									}
									break;
								case "communication":
									echo "Communication";
									switch($Dossier1)
									{
										case "FormationInterne": if($LangueAffichage=="FR"){echo " - Formation interne";}else{echo " - Internal training";}break;
										case "InnoLab-MarketplaceImpression3D": if($LangueAffichage=="FR"){echo " - InnoLab-Marketplace Impression 3D";}else{echo " - InnoLab-Marketplace Impression 3D";}break;
										case "TrainingModulesDRAFTSexchange": if($LangueAffichage=="FR"){echo " - Training modules DRAFTS exchange";}else{echo " - Training modules DRAFTS exchange";}break;
										default: echo " - ".$Dossier1;break;
									}
									break;
								case "missionhandicap":
									echo "Mission Handicap";
									switch($Dossier1)
									{
										case "missionhandicap": echo "";break;
										default: echo " - ".$Dossier1;break;
									}
									break;
								case "performanceindustrielle":
									echo "Performance industrielle";
									switch($Dossier1)
									{
										case "GestionKPI": echo " - Gestion KPI";break;
										default: echo " - ".$Dossier1;break;
									}
									break;
							}
							if($Dossier2!="")
							{
								switch($Dossier2)
								{
									//CE DP CHSCT
									case "ListeElus": 
											if($Dossier1=="CSE"){if($LangueAffichage=="FR"){echo " - Liste des élus";}else{echo " - Lists of elected";}}
											else{if($LangueAffichage=="FR"){echo " - Liste des élus et règlement intérieur";}else{echo " - Lists of elected";}}
											break;
									case "ReglementInterieur": if($LangueAffichage=="FR"){echo " - Réglement intérieur";}else{echo " - Rules of procedure";}break;
									case "ComptesRendus": if($LangueAffichage=="FR"){echo " - Comptes rendus des réunions";}else{echo " - Meeting reports";}break;
									case "Syntheses": if($LangueAffichage=="FR"){echo " - Synthèses des visites";}else{echo " - Summary of visits";}break;
									case "ActivitesSocialesCulturelles": if($LangueAffichage=="FR"){echo " - Activités sociales & Culturelles";}else{echo " - Social & cultural activities";}break;
									case "CommissionEconomique": if($LangueAffichage=="FR"){echo " - Commission Economique";}else{echo " - Economic Commission";}break;
									case "CommissionFEL": if($LangueAffichage=="FR"){echo " - Commission Formation-Egalité-Logement";}else{echo " - Training-Equality-Housing Commission";}break;
									case "CommissionHandicap": if($LangueAffichage=="FR"){echo " - Commission Handicap";}else{echo " - Handicap Commission";}break;
									case "CommissionCSSCT": if($LangueAffichage=="FR"){echo " - Commission Hygiène et Sécurité (CSSCT)";}else{echo " - Health and Safety Commission (CSSCT)";}break;
									
									
									//IN-SITU
									case "GestionDocumentaire" : if($LangueAffichage=="FR"){echo " - Gestion documentaire";}else{echo " - Document management";}break;
									case "FlashManagement" : if($LangueAffichage=="FR"){echo " - FLASH MANAGEMENT";}else{echo " - FLASH MANAGEMENT";}break;
									case "KitNouveauGestionnaire" : if($LangueAffichage=="FR"){echo " - Kit nouveau gestionnaire";}else{echo " - New manager kit";}break;
									case "FichePresence" : if($LangueAffichage=="FR"){echo " - Fiche de présence";}else{echo " - Presence sheet";}break;
									case "MGX" : if($LangueAffichage=="FR"){echo " - AAA MGX TLS";}else{echo " - AAA MGX TLS";}break;
									case "Pdp" : if($LangueAffichage=="FR"){echo " - Plan de prévention";}else{echo " - Plan de prévention";}break;
									case "Qualite" : if($LangueAffichage=="FR"){echo " - Qualité";}else{echo " - Qualité";}break;
									case "PlanningFormation" : if($LangueAffichage=="FR"){echo " - Planning Formation";}else{echo " - Planning Formation";}break;
									
									//HSE
									case "ConsignesSecurite": if($LangueAffichage=="FR"){echo " - Consignes de sécurité - Fiches de risques";}else{echo " - Security rules";}break;
									case "AccueilFormation" : if($LangueAffichage=="FR"){echo " - Accueil et formation";}else{echo " - Welcoming and training";}break;
									case "Organisation" : if($LangueAffichage=="FR"){echo " - Organisation du Manuel";}else{echo " - Manual organization";}break;
									case "Management" :
										if($Dossier1=="AAA-CANADA"){echo " - Management";break;}
										else{
											if($LangueAffichage=="FR"){echo " - Management HSE";}else{echo " - HSE Management";}
											break;
										}
									case "EvaluationRisques" : if($LangueAffichage=="FR"){echo " - Evaluation des risques professionnels";}else{echo " - Professional risks assesment";}break;
									case "Rappels" : if($LangueAffichage=="FR"){echo " - Rappels réglementaires";}else{echo " - Regulatory reminders";}break;
									case "GestionAT" : if($LangueAffichage=="FR"){echo " - Gestion des AT";}else{echo " - AT Management";}break;
									case "GestionEntreprisesExt" : if($LangueAffichage=="FR"){echo " - Gestion des entreprises extérieures";}else{echo " - Professional risks assessment";}break;
									case "Communication" : if($LangueAffichage=="FR"){echo " - Communication et affichage HSE";}else{echo " - HSE communication and display";}break;

									//Canada
									case "PolyvalenceTables" : echo " - Polyvalence Tables";break;
									case "OTD_OQD" : echo " - OTD/OQD";break;
									case "CustomerSatisfaction" : echo " - Customer Satisfaction";break;
									case "QualityPCS" : echo " - Quality PCS";break;

									case "RegionOuest": echo " - Région Ouest";break;
									case "RegionNord": echo " - Région Nord";break;
									case "Nautique": echo " - Activités nautiques";break;
									case "General": echo " - Général";break;
									case "SiegeSocial": echo " - Siège social";break;
									case "Allemagne": if($LangueAffichage=="FR"){echo " - Allemagne";}else{echo " - Germany";}break;
									case "Latecis" : echo " - Latecoere";break;
									case "DQ506" : echo " - D-0601-Plan d'actions";break;
									case "DQXXX" : echo " - DQXXX forms Germany";break;
									case "DOCS" : echo " - Docs Management";break;
									case "Polyvalence" : echo " - Polyvalence Template";break;
									case "OperatingMode" : echo " - Operating Mode";break;
									case "AAA-Ouest" : echo " - OSW Nantes";break;
									
									case "AnalyseRisque" : if($LangueAffichage=="FR"){echo " - Liste des analyses de risques";}else{echo " - List of risk analyzes";}break;
									
									case "M01" : echo " - M01 indicator (Direction)";break;
									case "M02" : echo " - M02 indicator (Quality)";break;
									case "R01" : echo " - R01 indicator (Commercial)";break;
									case "R03" : echo " - R03 indicator (Operation)";break;
									case "R04" : echo " - R04 indicator (Financial)";break;
									case "S02" : echo " - S02 Indicator (HR)";break;
									case "S03" : echo " - S03 indicator (Procurement)";break;
									
									case "FicheVie" : echo " - Fiche de vie";break;
									case "NormeCalibration" : echo " - Norme de calibration";break;
									case "SuiviExpiration" : echo " - Suivi expiration - Assignation";break;
									case "RapportEtalonnage" : echo " - Rapport d’étalonnage - Certificat";break;
									case "RapportNonConformites" : echo " - Rapport des Non-Conformités";break;
									
									
									//COMMUNICATION
									case "PresentationsCorporateAAA" : echo " - Présentations corporate AAA";break;
									case "OffresServiceAAA" : echo " - Offres de service AAA";break;
									case "FichesProjet" : echo " - Fiches projet";break;
									case "ModelesPresentations" : echo " - Modèles de présentations";break;
									case "ModelesOffresTechniquesCommerciales" : echo " - Modèles d'offres techniques et commerciales";break;
									case "BanqueDonnees" : echo " - Présentations corporate AAA";break;
									case "BanqueImages" : echo " - Banque d'images";break;
									case "ExemplesOffresTechniques" : echo " - Exemples d'offres techniques";break;
									
									//RH
									case "Notes" : echo " - Notes RH";break;
									case "ElectionsProfessionnelles" : echo " - Elections professionnelles";break;
									
									default: echo " - ".$Dossier2;break;
								}
							}
						?>
					</td>
				<?php
					if($Droits=="Administrateur" || $Droits=="Ecriture")
					{
				?>
					<td width="25">
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','<?php echo $Page; ?>','<?php echo $Dossier1; ?>','<?php echo $Dossier2 ?>','0');"><img src="Images/Ajout.gif" border="0" alt="Ajouter une information"></a>
					</td>
				<?php
					}
				?>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="12"></td></tr>

	<?php
		//Tri spécifique pour Presta in-situ et PlanHanicap : par titre
		if($Page=='insitu' || $Page=='canada' || $Dossier1=='PlanHandicap')
		{
			$requete="SELECT * FROM new_".$Page." WHERE Dossier1='".$Dossier1."' AND Dossier2='".$Dossier2."' ORDER BY Titre ASC";
		}
		elseif($Dossier1=='InnoLab-MarketplaceImpression3D')
		{
			$requete="SELECT * FROM new_".$Page." WHERE Dossier1='".$Dossier1."' AND Dossier2='".$Dossier2."' ORDER BY IF(Titre='NOTICE - AAA Marketplace Impression 3D','2050-01-01',date) DESC";
		}
		else
		{
			$requete="SELECT * FROM new_".$Page." WHERE Dossier1='".$Dossier1."' AND Dossier2='".$Dossier2."' ORDER BY date DESC";
		}
		$result=mysqli_query($bdd,$requete);
		$nbenreg=mysqli_num_rows($result);
		if($nbenreg>0)
		{
			while($row=mysqli_fetch_array($result))
			{
	?>
	<tr>
		<td>
			<table class="GeneralInfo" style="border-spacing:0; align:center; width:100%;">
				<tr>
					<td>
						<table class="TitreInfo" style="border-spacing:0;">
							<tr>
								<td class="TitreInfo">
									<a id="<?php echo $row['Id']; ?>"><?php echo stripslashes($row['Titre']); ?></a>
								</td>
								<td class="DateInfo"><?php echo " le ".$row['Date'];?>
								<?php
								    if($Droits=="Ecriture" || $Droits=="Administrateur")
									{
								?>
									&nbsp;&nbsp;
									<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $Page; ?>','<?php echo $Dossier1; ?>','<?php echo $Dossier2 ?>','<?php echo $row['Id']; ?>');">
										<img src="Images/Modif.gif" border="0" alt="Modification">
									</a>
								<?php
                                        if(($LoginPersonneConnectee==$row['Auteur'] && $Droits=="Ecriture") || $Droits=="Administrateur")
                                        {
								?>	
									<input type="image" src="Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){OuvreFenetreModif('Suppr','<?php echo $Page; ?>','<?php echo $Dossier1; ?>','<?php echo $Dossier2 ?>','<?php echo $row['Id']; ?>');}">
								<?php
                                        }
									}
								?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
				  		<table style="width:100%;">
							<tr>
							<?php
								if($row['Image']!='')
								{
							?>
								<td width="100">
									<table style="width:95%; height:95%; border-spacing:0;">
										<tr>
											<td align="center">
												<a href="javascript:OuvrirImage('<?php echo $Page."','".$Dossier1."','".$Dossier2."','".$row['Image']; ?>');">

													<?php
														if($Dossier2!=""){$CheminImage=$CheminOuvrirUpload."Images/".$Page."/".$Dossier1."/".$Dossier2."/".$row['Image'];}
														else{$CheminImage=$CheminOuvrirUpload."Images/".$Page."/".$Dossier1."/".$row['Image'];}
													?>
													<img src="<?php echo $CheminImage; ?>"height="90" width="90" border="0">
												</a>
											</td>
										</tr>
										<tr>
											<td class="NomImageInfo"><?php echo stripslashes($row['NomImage']); ?></td>
										</tr>
									</table>
								</td>
							<?php
							  	}
						  	?>
								<td>
									<table style="width:100%; height:95%; border-spacing:0;">
										<tr>
											<td class="ContenuInfo">
												<?php echo stripslashes(nl2br($row['Contenu'])); ?>
											</td>
										</tr>
									    <?php
											if($row['Fichier']!='')
											{
									    ?>
										<tr>
											<td>
												<table>
													<tr>
														<td class="FichierJointInfo">Télécharger le fichier joint :&nbsp;</td>
														<td class="FichierJointInfo" rowspan="2">
															<a class="Info"
																<?php
																	if($Dossier2!=""){$CheminFichier=$CheminOuvrirUpload."Fichiers/".$Page."/".$Dossier1."/".$Dossier2."/".$row['Fichier'];}
																	else{$CheminFichier=$CheminOuvrirUpload."Fichiers/".$Page."/".$Dossier1."/".$row['Fichier'];}

																	if(substr($row['Fichier'],0,2)=="DQ")
																	{
																?>
																	target="_blank" href="<?php echo $CheminFichier; ?>">
																<?php
																	}
																	else
																	{
																?>
																		href="javascript:OuvrirFichier('<?php echo $Page."','".$Dossier1."','".$Dossier2."','".$row['Fichier']; ?>');" onMouseOver="javascript:AfficheTelecharge();">
																<?php
																	}
																?>
																<img src="Images/Fichier_Joint.gif" border="0" alt="<?php echo stripslashes($row['NomFichier']); ?>">
															</a>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									  <?php
										  }
									  ?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr height='15'><td></td></tr>
<?php
			}	//Fin boucle
		}		//Fin If
		mysqli_free_result($result);	// Libération des résultats
?>
	<tr height='500'><td></td></tr>
</table>

<?php
mysqli_close($bdd);					// Fermeture de la connexion
?>

</body>
</html>

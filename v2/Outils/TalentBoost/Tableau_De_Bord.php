<?php
require("../../Menu.php");

function Titre($Libelle,$Lien)
{
	global $HTTPServeur;
	echo "<tr><td>&nbsp;&bull;&nbsp;<a style=\"color:#002060;text-decoration: none;\" onmouseover=\"this.style.color='#002060';\" onmouseout=\"this.style.color='#002060';\" href='".$HTTPServeur.$Lien."' >".$Libelle."</a></td></tr>\n";
}

function Widget($Libelle,$Lien,$Image,$Couleur,$InfosSupp="")
{
	global $HTTPServeur;
	
	echo "
		<table style='border-spacing: 10px;display:inline-table;' >
			<tr>
				<td style=\"width:160px;height:130px;border-style:outset; border-radius: 15px;border-color:".$Couleur.";border-spacing:0;text-align:center;color:#002060;valign:top;font-weight:bold;\" bgcolor='".$Couleur."'>
					<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:center;color:#002060;font-weight:bold;\" onmouseover=\"this.style.color='#002060';\" onmouseout=\"this.style.color='#002060';\" href='".$HTTPServeur.$Lien."' >
						<img width='50px' src='../../Images/".$Image."' border='0' /><br>
						".$Libelle."
					</a>
				</td>
			</tr>";
	
	$css="";
	
	if($InfosSupp<>""){$css="bgcolor='".$Couleur."' width='250px'";}
	
	echo "
		<tr>
			<td ".$css.">
				".$InfosSupp."
			</tD>
		</tr>
	";
	echo "</table>";
}

function WidgetTDB($Libelle,$Image,$Couleur,$CouleurLogo,$nb,$Libelle2,$Lien){
	$couleurNombre="";
	if($nb<>"0" && $nb<>"0/0"){$couleurNombre="color:#de0006;";}
	echo "
		<table style='border-spacing: 10px;display:inline-table;' >
			<tr>
				<td style=\"width:200px;height:110px;border-style:outset;border-color:".$Couleur.";border-spacing:0;text-align:center;color:#002060;valign:top;font-weight:bold;\" bgcolor='".$Couleur."'>
					<table width='100%' height='100%'>
						<tr>
							<td style=\"width:35%;height:100%;text-align:center;color:#002060;valign:top;font-weight:bold;\" rowspan='2' bgcolor='".$CouleurLogo."'>
								<a style=\"text-decoration:none;\" onmouseover=\"this.style.color='#002060';\" onmouseout=\"this.style.color='#002060';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >
								<img width='40px' src='../../Images/".$Image."' border='0' />
								</a>
							</td>
							<td width='65%' style='font-size:32px;".$couleurNombre."'>
								".$nb."
							</td>
						</tr>
						<tr>
							<td>
								".$Libelle."
							</td>
						</tr>
						<tr>
							<td colspan='2' style='color:red;'>
								".$Libelle2."
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>";
}

$reqPrestaPoste = "SELECT Id_Prestation 
FROM new_competences_personne_poste_prestation 
WHERE Id_Personne =".$IdPersonneConnectee."  
AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) 
AND Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.")
";	
$nbPoste=mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPrestaPoste));
?>
<body style="background-color:#deebf7;">
<table style="width:100%;height:100% ;border-spacing:0; align:center;">
	<tr>
		<td  height="20px" valign="center" align="center" style="font-weight:bold;font-size:15px;">
			<table style="align:center;">
				<tr>
					<td style="height:50px;text-align:center;color:#103b63;valign:top;font-weight:bold;font-size:15px;border-radius: 15px;" bgcolor="#ffffff">&nbsp;&nbsp;
						<?php
							if($LangueAffichage=="FR"){echo "Vous venez de rejoindre <b>TalentBoost</b>, vous pouvez postuler sur \"offre mobilité interne\" et prendre connaissance du guide utilisateur : ";}else{echo "You have just joined the \"Stock Exchange Job\", you can apply for \"internal mobility offer\" and read the user guide : ";}
							if(DroitsFormationPlateforme(array($IdPosteAssistantRH.",".$IdPosteResponsableRH)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH)))
							{
								echo "<a target='_blank' href='OffreEmplois_RH.pdf'><img src='../../Images/pdf.png' border='0' width='24px'></a>";
							}
							else{
								echo "<a target='_blank' href='OffreEmplois_Salaries.pdf'><img src='../../Images/pdf.png' border='0' width='24px'></a>";
							}
							if(DroitsFormationPlateforme(array($IdPosteAssistantRH.",".$IdPosteResponsableRH)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH)))
							{
								echo "<br>";
								if($LangueAffichage=="FR"){echo "Guide salarié : ";}else{echo "Employee guide : ";}
								echo "<a target='_blank' href='OffreEmplois_Salaries.pdf'><img src='../../Images/pdf.png' border='0' width='24px'></a>";
							}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5px"></td>
	</tr>
	<tr bgcolor="#ffffff" >
		<td colspan="2" style="width:70px;height:30px;border-spacing:0;text-align:center;color:#103b63;valign:top;font-weight:bold;font-size:15px;">
			<?php if($LangueAffichage=="FR"){echo "TalentBoost";}else{echo "TalentBoost";}?>
		</td>
	</tr>
	<tr>
		<td width="100%">
			<table style="width:100%; border-spacing:0; align:center;">
				<tr>
					<?php
					if(DroitsFormationPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH,$IdPosteResponsableRecrutement,$IdPosteRecrutement)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH)))
					{
					?>
					<td align="center" style="width:20%" valign="top">
						<table>
							<tr>
								<td>
								<?php
								
								$nb=0;
								
								$requete2="SELECT Id,EtatValidation,EtatApprobation,EtatRecrutement,OuvertureAutresPlateformes,Id_Prestation,EtatPoste,Id_Plateforme,ValidationContratDG ";
								$requete=" FROM talentboost_annonce
											WHERE Suppr=0  
											AND ValidationContratDG>-1 ";
								if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
									$requete.=" ";
								}
								else{
									$requete.="  AND (
													  talentboost_annonce.Id_Plateforme IN 
														(
															SELECT Id_Plateforme 
															FROM new_competences_personne_poste_plateforme
															WHERE Id_Personne=".$_SESSION['Id_Personne']." 
															AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") 
														)
													OR 
														Id_Prestation IN 
														(SELECT Id_Prestation
														FROM new_competences_personne_poste_prestation 
														WHERE Id_Personne=".$_SESSION["Id_Personne"]."
														AND Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.") 
														)
											  ) ";
											  
								}
								$result=mysqli_query($bdd,$requete2.$requete);
								$nbResulta=mysqli_num_rows($result);
								if($nbResulta>0){
									$ActionAFaire=0;
									while($row=mysqli_fetch_array($result))
									{
										$reqPrestaPoste = "SELECT Id_Prestation 
											FROM new_competences_personne_poste_prestation 
											WHERE Id_Personne =".$IdPersonneConnectee."  
											AND ".$row['Id_Prestation']."
											AND Id_Poste IN (".$IdPosteResponsableOperation.")
											";	
										$nbPoste2=mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPrestaPoste));
										
										
										if($row['ValidationContratDG']>0 && $row['EtatPoste']==0){
											if(DroitsFormation1Plateforme($row['Id_Plateforme'],array($IdPosteResponsablePlateforme)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){$ActionAFaire++;}
										}											
									}
									$nb=$ActionAFaire;
								}
								$libelle7="";
								if($_SESSION["Langue"]=="FR"){$libelle="Besoins";}else{$libelle="Needs";}
								WidgetTDB($libelle,"recrutement.png","#ededed","#adadad",$nb,$libelle7,"Outils/TalentBoost/Besoins.php");
								
								
								?>
								</td>
							</tr>
						</table>
					</td>
					<?php
					}
					?>
					<td align="center" style="width:60%" valign="top">
						<table>
							<tr>
								<td>
								<?php
								if(DroitsFormationPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH,$IdPosteResponsableRecrutement,$IdPosteRecrutement)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH)))
								{
									if($LangueAffichage=="FR"){$libelle="<br>Déclarer un besoin";}else{$libelle="<br>Declare a need";}
									Widget($libelle,"Outils/TalentBoost/DeclarerBesoin.php","CV.png","#aec7dc");
								}
								
								if($LangueAffichage=="FR"){$libelle="<br>Offre mobilité interne";}else{$libelle="<br>Internal mobility offer";}
								Widget($libelle,"Outils/TalentBoost/Annonces.php","annonce.png","#d9d9d9");
									
								if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
									if($LangueAffichage=="FR"){$libelle="<br>Indicateurs";}else{$libelle="<br>Indicators";}
									Widget($libelle,"Outils/TalentBoost/TDB_Indicateurs.php","Formation/Graphique.png","#f3bad3");
								}
								
								?>
								</td>
							</tr>
						</table>
					</td>
					<?php
					if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
					?>
					<td align="center" width="20%" valign="top">
						<table style='border-spacing:15px;display:inline-table;' >
							<tr>
								<td style="width:300px;border-style:outset; border-radius: 15px;height:90px;border-style:outset;border-color:#faf78c;border-spacing:0;color:black;valign:top;font-weight:bold;" bgcolor='#f5f74b'>
									<table width='100%' height='100%'>	
										<tr>
											<td style="width:30%;height:100%;text-align:center;color:black;valign:top;font-weight:bold;">
												<img width='40px' src='../../Images/Formation/Parametrage.png' border='0' /><br>
											</td>
										</tr>
										<tr>
											<td>
												<table style="width:100%; align:left; valign:top;">
												<?php
													if($LangueAffichage=="FR"){Titre("Domaines","Outils/TalentBoost/Liste_Domaine.php");}
													else{Titre("Domain","Outils/TalentBoost/Liste_Domaine.php");}
													
													if($LangueAffichage=="FR"){Titre("Unités d'exploitation (Documents)","Outils/TalentBoost/Liste_Plateforme.php");}
													else{Titre("Operating units (Documents)","Outils/TalentBoost/Liste_Plateforme.php");}
													
													if($LangueAffichage=="FR"){Titre("Prérequis","Outils/TalentBoost/Liste_Prerequis.php");}
													else{Titre("Prerequisites","Outils/TalentBoost/Liste_Prerequis.php");}
													
													if($LangueAffichage=="FR"){Titre("Savoir-être","Outils/TalentBoost/Liste_SavoirEtre.php");}
													else{Titre("Know-how","Outils/TalentBoost/Liste_SavoirEtre.php");}
												?>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<?php
					}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center">
			<table style="width:30%;background-color:#ffffff;display:inline-table;border-style:outset;border-spacing:0;">
				<tr>
					<td style="color:#626262;font-size:18px;font-weight:bold italic;">
						<img src="../../Images/recrutement.png" style="width:30px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?php 
						echo "Nouvelles offres disponibles !";
						?>
					</td>
				</tr>
				<tr>
					<td align="right" style="font-weight:bold;color:#626262;">
					</td>
				</tr>
				<tr>
					<td align="left" >
						<div id='Div_Type' style="height:150px;overflow:auto;">
							<table style="width:100%; border-spacing:0;" align="center">
								<?php 
									$requete=" SELECT Metier,
												Lieu,
												IF(DateActualisation>'0001-01-01',CONCAT('S',DATE_FORMAT(DateActualisation,'%u')),CONCAT('S',DATE_FORMAT(DateValidationDG,'%u'))) AS Semaine
												FROM talentboost_annonce
												WHERE Suppr=0 AND ValidationContratDG=1 AND EtatPoste=0 AND IF(DateActualisation>'0001-01-01',DateActualisation,DateValidationDG)>='".date('Y-m-d',strtotime(date('Y-m-d')." -28 day"))."' ";
									if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
									}
									else{
										$requete.="  AND (
														  talentboost_annonce.Id_Plateforme IN 
															(
																SELECT Id_Plateforme 
																FROM new_competences_personne_poste_plateforme
																WHERE Id_Personne=".$_SESSION['Id_Personne']." 
																AND Id_Poste IN (".$IdPosteResponsableRecrutement.",".$IdPosteRecrutement.",".$IdPosteResponsablePlateforme.",".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") 
															)
														OR 
															Id_Prestation IN 
															(SELECT Id_Prestation
															FROM new_competences_personne_poste_prestation 
															WHERE Id_Personne=".$_SESSION["Id_Personne"]."
															AND Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.") 
															)
														OR
															(talentboost_annonce.Id_Plateforme IN 
															(
																SELECT Id_Plateforme 
																FROM new_competences_personne_plateforme
																WHERE Id_Personne=".$_SESSION['Id_Personne']." 
															)
															OR OuvertureAutresPlateformes=1
															)
												  ) ";
												  
									}
									$requete.=" ORDER BY IF(DateActualisation>'0001-01-01',CONCAT('S',DATE_FORMAT(DateActualisation,'%u')),CONCAT('S',DATE_FORMAT(DateValidationDG,'%u'))) DESC, Metier ";
									$result=mysqli_query($bdd,$requete);
									$nbResulta=mysqli_num_rows($result);
									if($nbResulta>0){
										$semaine="";
										echo "<tr bgcolor='#aec7dc'>";
												echo "<td width='20%' style='color:#002060;'><b>Semaine</b></td>";
												echo "<td width='60%' style='color:#002060;'><b>Métier</b></td>";
												echo "<td width='20%' style='color:#002060;'><b>Lieu</b></td>";
											echo "</tr>";
										while($row=mysqli_fetch_array($result))
										{
											if($semaine<>$row['Semaine'])
											echo "<tr>";
												if($semaine<>$row['Semaine']){echo "<td width='20%' style='border-bottom:2px dotted #d6d6d6'>".$row['Semaine']."</td>";}
												else{echo "<td width='20%' style='border-bottom:2px dotted #d6d6d6'></td>";}
												
												echo "<td width='60%' style='border-bottom:2px dotted #d6d6d6'>".stripslashes($row['Metier'])."</td>";
												echo "<td width='20%' style='border-bottom:2px dotted #d6d6d6'>".stripslashes($row['Lieu'])."</td>";
											echo "</tr>";
											$semaine=$row['Semaine'];
										}
									}
								?>
							</table>
							</div>
						</td>
					</tr>
				</td>
		</table>
	</tr>
	<tr>
		<td height="150px"></td>
	</tr>
</table>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>
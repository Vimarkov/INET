<?php
require("../../Menu.php");
require("Fonction_Recrutement.php");

function Titre($Libelle,$Lien)
{
	global $HTTPServeur;
	echo "<tr><td>&nbsp;&bull;&nbsp;<a style=\"color:black;text-decoration: none;\" onmouseover=\"this.style.color='#0d8df1';\" onmouseout=\"this.style.color='#000000';\" href='".$HTTPServeur.$Lien."' >".$Libelle."</a></td></tr>\n";
}

function Widget($Libelle,$Lien,$Image,$Couleur,$InfosSupp="")
{
	global $HTTPServeur;
	
	echo "
		<table style='border-spacing: 10px;display:inline-table;' >
			<tr>
				<td style=\"width:130px;height:110px;border-style:outset; border-radius: 15px;border-color:".$Couleur.";border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='".$Couleur."'>
					<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:center;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" href='".$HTTPServeur.$Lien."' >
						<img width='40px' src='../../Images/".$Image."' border='0' /><br>
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
				<td style=\"width:190px;height:90px;border-style:outset;border-color:".$Couleur.";border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='".$Couleur."'>
					<table width='100%' height='100%'>
						<tr>
							<td style=\"width:35%;height:100%;text-align:center;color:black;valign:top;font-weight:bold;\" rowspan='2' bgcolor='".$CouleurLogo."'>
								<a style=\"text-decoration:none;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >
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
AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (1,3,4,5,9,10,13,17,19,23,24,27) 
AND Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.")
";	
$nbPoste=mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPrestaPoste));
?>

<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td  height="20px" valign="center" align="center" style="font-weight:bold;font-size:15px;">
			<table style="align:center;">
				<tr>
					<td style="height:50px;text-align:center;color:#00577c;valign:top;font-weight:bold;font-size:15px;border-radius: 15px;" bgcolor="#c1edff">&nbsp;&nbsp;
						<?php
							if($LangueAffichage=="FR"){echo "Vous venez de rejoindre la BOURSE EMPLOI, vous pouvez postuler sur les offres de reclassements internes et prendre connaissance du guide utilisateur : ";}else{echo "You have just joined the \"Stock Exchange Job\", you can apply for internal reclassification offers and read the user guide : ";}
							if(DroitsFormationPlateforme(array($IdPosteAssistantRH.",".$IdPosteResponsableRH)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH)))
							{
								echo "<a target='_blank' href='OffreEmplois_RH.pdf'><img src='../../Images/pdf.png' border='0' width='24px'></a>";
							}
							/*elseif(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme)) || $nbPoste>0){
								echo "<a target='_blank' href='OffreEmplois_Resp.pdf'><img src='../../Images/pdf.png' border='0' width='24px'></a>";
							}*/
							else{
								echo "<a target='_blank' href='OffreEmplois_Salaries.pdf'><img src='../../Images/pdf.png' border='0' width='24px'></a>";
							}
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							echo "<br>";
							echo "<br>";
							echo "Veuillez trouver la note de communication relative au reclassement interne et aux offres de reclassement : ";
							echo "<a target='_blank' href='Note de communication_Reclassement interne - Offres de reclassement.pdf'><img src='../../Images/pdf.png' border='0' width='24px'></a>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5px"></td>
	</tr>
	<tr bgcolor="#6EB4CD" >
		<td colspan="2" style="width:70px;height:30px;border-spacing:0;text-align:center;color:#00577c;valign:top;font-weight:bold;font-size:15px;">
			<?php if($LangueAffichage=="FR"){echo "BOURSE EMPLOI";}else{echo "STOCK EXCHANGE JOB";}?>
		</td>
	</tr>
	<tr>
		<td width="100%">
			<table style="width:100%; border-spacing:0; align:center;">
				<tr>
					<?php
					//if(DroitsFormationPlateforme(array($IdPosteDirection.",".$IdPosteResponsablePlateforme.",".$IdPosteAssistantRH.",".$IdPosteResponsableRH)) || $nbPoste>0 || DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH)))
					if(DroitsFormationPlateforme(array($IdPosteAssistantRH.",".$IdPosteResponsableRH)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH)))
					{
					?>
					<td align="center" style="width:20%" valign="top">
						<table>
							<tr>
								<td>
								<?php
								
								$nb=0;
								
								$requete2="SELECT Id,EtatValidation,EtatApprobation,EtatRecrutement,OuvertureAutresPlateformes,Id_Prestation,
									(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme ";
								$requete=" FROM recrut_annonce
											WHERE Suppr=0  ";
								if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
									$requete.="  AND OuvertureAutresPlateformes=1 ";
								}
								else{
									$requete.="  AND (
													  (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=recrut_annonce.Id_Prestation) IN 
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
										
										
										if($row['EtatValidation']==0 && $nbPoste2>0){$ActionAFaire++;}
										elseif($row['EtatValidation']>0 && $row['EtatApprobation']==0){
											if(DroitsFormation1Plateforme($row['Id_Plateforme'],array($IdPosteResponsablePlateforme))){$ActionAFaire++;}
										}
										elseif($row['EtatValidation']>0 && $row['EtatApprobation']>0 && $row['EtatRecrutement']==0){
											if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){$ActionAFaire++;}
										}			
									}
									$nb=$ActionAFaire;
								}
								
								$libelle7="";
								if($_SESSION["Langue"]=="FR"){$libelle="Besoins";}else{$libelle="Needs";}
								WidgetTDB($libelle,"recrutement.png","#c4b1d5","#8863ab",$nb,$libelle7,"Outils/Recrutement/Besoins.php");
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
								//if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme.",".$IdPosteAssistantRH.",".$IdPosteResponsableRH)) || $nbPoste>0 || DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH)))
									if(DroitsFormationPlateforme(array($IdPosteAssistantRH.",".$IdPosteResponsableRH)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH)))
								{
									if($LangueAffichage=="FR"){$libelle="<br>Déclarer un besoin";}else{$libelle="<br>Declare a need";}
									Widget($libelle,"Outils/Recrutement/DeclarerBesoin.php","CV.png","#42d3d6");
								}
								
								if($LangueAffichage=="FR"){$libelle="<br>Offre reclassement interne";}else{$libelle="<br>Internal reclassification offer";}
								Widget($libelle,"Outils/Recrutement/Annonces.php","annonce.png","#ddd7fb");
									
								if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
									if($_SESSION['Id_Personne']==1132 || $_SESSION['Id_Personne']==13887 || $_SESSION['Id_Personne']==3724)
									if($LangueAffichage=="FR"){$libelle="<br>Indicateurs";}else{$libelle="<br>Indicators";}
									Widget($libelle,"Outils/Recrutement/TDB_Indicateurs.php","Formation/Graphique.png","#e779a4");
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
								<td style="width:300px;border-style:outset; border-radius: 15px;height:90px;border-style:outset;border-color:#f5f74b;border-spacing:0;color:black;valign:top;font-weight:bold;" bgcolor='#f5f74b'>
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
													if($LangueAffichage=="FR"){Titre("Catégorie d’emploi, à titre indicatif","Outils/Recrutement/Liste_Statut.php");}
													else{Titre("Job category, for information only","Outils/Recrutement/Liste_Statut.php");}
													
													if($LangueAffichage=="FR"){Titre("Domaines","Outils/Recrutement/Liste_Domaine.php");}
													else{Titre("Domain","Outils/Recrutement/Liste_Domaine.php");}
													
													if($LangueAffichage=="FR"){Titre("Unités d'exploitation (Documents)","Outils/Recrutement/Liste_Plateforme.php");}
													else{Titre("Operating units (Documents)","Outils/Recrutement/Liste_Plateforme.php");}
													
													if($LangueAffichage=="FR"){Titre("Prestations","Outils/Recrutement/Liste_Prestation.php");}
													else{Titre("Sites","Outils/Recrutement/Liste_Prestation.php");}
													
													if($LangueAffichage=="FR"){Titre("Savoir-être","Outils/Recrutement/Liste_SavoirEtre.php");}
													else{Titre("Know-how","Outils/Recrutement/Liste_SavoirEtre.php");}
													
													if($LangueAffichage=="FR"){Titre("Types horaires","Outils/Recrutement/Liste_TypeHoraire.php");}
													else{Titre("Hourly types","Outils/Recrutement/Liste_TypeHoraire.php");}
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
												(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
												CONCAT('S',DATE_FORMAT(DateRecrutement,'%u')) AS Semaine
												FROM recrut_annonce
												WHERE Suppr=0  AND EtatRecrutement=1 AND OuvertureAutresPlateformes=1 AND DateRecrutement>='".date('Y-m-d',strtotime(date('Y-m-d')." -28 day"))."' ";
									if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
									}
									else{
										$requete.="  AND (
														  (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=recrut_annonce.Id_Prestation) IN 
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
															((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=recrut_annonce.Id_Prestation) IN 
															(
																SELECT Id_Plateforme 
																FROM new_competences_personne_plateforme
																WHERE Id_Personne=".$_SESSION['Id_Personne']." 
															)
															OR OuvertureAutresPlateformes=1
															)
												  ) ";
												  
									}
									$requete.=" ORDER BY CONCAT('S',DATE_FORMAT(DateRecrutement,'%u')) DESC, Metier ";
									$result=mysqli_query($bdd,$requete);
									$nbResulta=mysqli_num_rows($result);
									if($nbResulta>0){
										$semaine="";
										echo "<tr bgcolor='#cfe6fd'>";
												echo "<td width='20%'><b>Semaine</b></td>";
												echo "<td width='60%'><b>Métier</b></td>";
												echo "<td width='20%'><b>Plateforme</b></td>";
											echo "</tr>";
										while($row=mysqli_fetch_array($result))
										{
											if($semaine<>$row['Semaine'])
											echo "<tr>";
												if($semaine<>$row['Semaine']){echo "<td width='20%' style='border-bottom:2px dotted #d6d6d6'>".$row['Semaine']."</td>";}
												else{echo "<td width='20%' style='border-bottom:2px dotted #d6d6d6'></td>";}
												
												echo "<td width='60%' style='border-bottom:2px dotted #d6d6d6'>".stripslashes($row['Metier'])."</td>";
												echo "<td width='20%' style='border-bottom:2px dotted #d6d6d6'>".stripslashes($row['Plateforme'])."</td>";
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
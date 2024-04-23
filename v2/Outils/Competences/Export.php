<?php
require("../../Menu.php");

$Droits="Aucun";
if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))	
)
{
	$Droits="Administrateur";
}
elseif(DroitsPlateforme(array($IdPosteResponsableQualite,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteReferentQualiteSysteme,$IdPosteResponsableHSE))
|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
)
{
	$Droits="Ecriture";
}
?>

<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing:0;">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php 
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Competences/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
					if($LangueAffichage=="FR"){echo "Gestion des compétences # Fichiers d'exportation ";}else{echo "Competencies management # Export files";}?></td>
				</tr>
			</table>
		</td>
	</tr>

	<tr><td height="5"></td></tr>
	
	<tr>
		<td>
			<table class="TableCompetences" style="width:98%; align:center;">
				<tr>
					<td>
						<ul>
							<li>
								<a href="Trouver_Proche_Surveillance.php" target="_blank">
									<?php
										if($LangueAffichage=="FR"){echo "Plannification des surveillances ou requalifications aux procédés spéciaux";}
										else{echo "Supervision planning or requalification of special processes";}
									?>
								</a>
							</li>
							<?php
							if($_GET['Toutes'] == 'Non')
							{
							?>
							<li>
								<a href="Personnes_Trouvees.php?Toutes=Oui">
									<?php
										if($LangueAffichage=="FR"){echo "Lister les personnes de toutes les plateformes (+ personnes sans prestation)";}
										else{echo "List people from all platforms (+ people without activity)";}
									?>
								</a>
							</li>
							<?php
							}
							else
							{
							?>
							<li>
								<a href="Personnes_Trouvees.php?Toutes=Non">
									<?php
										if($LangueAffichage=="FR"){echo "Lister les personnes de l'unité d'exploitation qui ont une prestation (sinon les rechercher dans toutes les unités d'exploitations)";}
										else{echo "List people on the operating unit who have a service (if not look for them on all operating units)";}
									?>
								</a>
							</li>
							<?php
							}
							?>
							<li>
								<a href="Toutes_Qualifs_Toutlemonde.php" target="_blank">
									<?php
										if($LangueAffichage=="FR"){echo "Exporter toutes les données des qualifications de tout le monde";}
										else{echo "Export all qualifications data from everyone";}
									?>
								</a>
							</li>
							<li>
								<a href="Toutes_Qualifs_Toutlemondev2.php" target="_blank">
									<?php
										if($LangueAffichage=="FR"){echo "Exporter toutes les données des qualifications de tout le monde v2";}
										else{echo "Export all qualifications data from everyone v2";}
									?>
								</a>
							</li>
							<li>
								<a href="Toutes_Formations_Toutlemonde.php" target="_blank">
									<?php
										if($LangueAffichage=="FR"){echo "Exporter toutes les données des formations de tout le monde";}
										else{echo "Export all trainings data from everyone";}
									?>
								</a>
							</li>
							<li>
								<a href="EIA_Export.php" target="_blank">
									<?php
										if($LangueAffichage=="FR"){echo "Liste des entretiens professionnels";}
										else{echo "Career interviews list";}
									?>
								</a>
							</li>
							<li>
								<a href="Tout_Resultat_Export.php" target="_blank">
									<?php
										if($LangueAffichage=="FR"){echo "Liste complète des enregistrements";}
										else{echo "Complete record list";}
									?>
								</a>
							</li>
							<?php 
							if($Droits=="Ecriture" || $Droits=="Administrateur")
							{
							?>
							<li>
								<a href="Enregistrement_Multi_Personne_Qualification.php" target="_blank">
									<?php
										if($LangueAffichage=="FR"){echo "Enregistrement de plusieurs qualifications pour plusieurs personnes";}
										else{echo "Registration of multiple qualifications for multiple persons";}
									?>
								</a>
							</li>
							<li>
								<a href="Enregistrement_Multi_Personne_Formation.php" target="_blank">
									<?php
										if($LangueAffichage=="FR"){echo "Enregistrement de plusieurs formations pour plusieurs personnes";}
										else{echo "Registration of multiple trainings for multiple persons";}
									?>
								</a>
							</li>
							<?php 
							}
							?>
						</ul>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
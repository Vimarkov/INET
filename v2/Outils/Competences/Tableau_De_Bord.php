<?php
require("../../Menu.php");

function Titre($Libelle,$Lien)
{
	global $HTTPServeur;
	echo "<tr><td>&nbsp;&bull;&nbsp;<a style=\"color:black;text-decoration: none;\" onmouseover=\"this.style.color='#0d8df1';\" onmouseout=\"this.style.color='#000000';\" href='".$HTTPServeur.$Lien."' >".$Libelle."</a></td></tr>\n";
}
function Titre2($Libelle,$Lien)
{
	global $HTTPServeur;
	echo "<tr><td>&nbsp;&bull;&nbsp;<a style=\"color:black;text-decoration: none;\" onmouseover=\"this.style.color='#0d8df1';\" onmouseout=\"this.style.color='#000000';\" href='".$HTTPServeur.$Lien."' target='_blank'>".$Libelle."</a></td></tr>\n";
}

function Widget($Libelle,$Lien,$Image,$Couleur,$InfosSupp="")
{
	global $HTTPServeur;
	
	echo "
		<table style='border-spacing: 15px;display:inline-table;' >
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
?>

<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td height="5px"></td>
	</tr>
	<tr bgcolor="#6EB4CD" >
		<td colspan="2" style="width:70px;height:30px;border-spacing:0;text-align:center;color:#00577c;valign:top;font-weight:bold;font-size:15px;">
			<?php if($LangueAffichage=="FR"){echo "GESTION DES COMPETENCES";}else{echo "COMPETENCIES MANAGEMENT";}?>
		</td>
	</tr>
	<tr>
		<td height="5px"></td>
	</tr>
	<tr>
		<td width="100%">
			<table style="width:100%; border-spacing:0; align:center;">
				<tr>
					<td align="center" style="width:50%" valign="top">
						<table>
							<tr>
								<td style="font-size:30px">
								<?php	
								if($LangueAffichage=="FR"){$libelle="<br>Recherche";}else{$libelle="<br>Search";}
								Widget($libelle,"Outils/Competences/Recherche.php","Formation/Personnes.png","#5f80ff");
								echo "<br>";
								if($LangueAffichage=="FR"){$libelle="<br>Fichiers Export";}else{$libelle="<br>Export Excel";}
								Widget($libelle,"Outils/Competences/Export.php?Toutes=Non","telechargement.png","#5f80ff");
								
								?>
								</td>
							</tr>
						</table>
					</td>
					<td align="center" width="25%" valign="top">
						<table style='border-spacing:15px;display:inline-table;' >
							<tr>
								<td style="width:300px;border-style:outset; border-radius: 15px;height:90px;border-style:outset;border-color:#67cff1;border-spacing:0;color:black;valign:top;font-weight:bold;" bgcolor='#67cff1'>
									<table width='100%' height='100%'>	
										<tr>
											<td style="width:30%;height:100%;text-align:center;color:black;valign:top;font-weight:bold;">
												<img width='40px' src='../../Images/RH/Parametrage.png' border='0' /><br>
											</td>
										</tr>
										<tr>
											<td>
												<table style="width:100%; align:left; valign:top;">
												<?php
												
												if(DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne,$IdPosteResponsableFormation)))
												{
													if($LangueAffichage=="FR"){Titre("Catégorie d'autorisation","Outils/Competences/Liste_Categorie_Autorisation.php");}
													else{Titre("Authorization categories","Outils/Competences/Liste_Categorie_Autorisation.php");}
												}
												if($LangueAffichage=="FR"){Titre("Catégorie Diplôme","Outils/Competences/Liste_Categorie_Diplome.php");}
												else{Titre("Group diploma","Outils/Competences/Liste_Categorie_Diplome.php");}
												if($LangueAffichage=="FR"){Titre("Catégorie qualification","Outils/Competences/Liste_Categorie_Qualification.php");}
												else{Titre("Qualification group","Outils/Competences/Liste_Categorie_Qualification.php");}
												if($LangueAffichage=="FR"){Titre("Client","Outils/Competences/Liste_Client.php");}
												else{Titre("Client","Outils/Competences/Liste_Client.php");}
												if($LangueAffichage=="FR"){Titre("Conglomeration","Outils/Competences/Liste_Conglomeration.php");}
												else{Titre("Conglomeration","Outils/Competences/Liste_Conglomeration.php");}
												if($LangueAffichage=="FR"){Titre("Diplôme","Outils/Competences/Liste_Diplome.php");}
												else{Titre("Diploma","Outils/Competences/Liste_Diplome.php");}
												if($LangueAffichage=="FR"){Titre("Division","Outils/Competences/Liste_Division.php");}
												else{Titre("Division","Outils/Competences/Liste_Division.php");}
												if($LangueAffichage=="FR"){Titre("Domaine","Outils/Competences/Liste_Domaine.php");}
												else{Titre("Domaine","Outils/Competences/Liste_Domaine.php");}
												if($LangueAffichage=="FR"){Titre("Formation","Outils/Competences/Liste_Formation.php");}
												else{Titre("Training","Outils/Competences/Liste_Formation.php");}
												if($LangueAffichage=="FR"){Titre("Métier","Outils/Competences/Liste_Metier.php");}
												else{Titre("Job","Outils/Competences/Liste_Metier.php");}
												if(DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne,$IdPosteResponsableFormation)))
												{
													if($LangueAffichage=="FR"){Titre("Moyen","Outils/Competences/Liste_Moyen.php");}
													else{Titre("Mean","Outils/Competences/Liste_Moyen.php");}
												}
												if($LangueAffichage=="FR"){Titre("Niveau Diplôme","Outils/Competences/Liste_Niveau_Diplome.php");}
												else{Titre("Grade diploma","Outils/Competences/Liste_Niveau_Diplome.php");}
												if($LangueAffichage=="FR"){Titre("Personne","Outils/Competences/Personnes_Trouvees.php?Toutes=Non");}
												else{Titre("Person","Outils/Competences/Personnes_Trouvees.php?Toutes=Non");}
												if($LangueAffichage=="FR"){Titre("Pôle","Outils/Competences/Liste_Pole.php");}
												else{Titre("Pole","Outils/Competences/Liste_Pole.php");}
												if($LangueAffichage=="FR"){Titre("Prestation","Outils/Competences/Liste_Prestation.php");}
												else{Titre("Activity","Outils/Competences/Liste_Prestation.php");}
												if($LangueAffichage=="FR"){Titre("Projet","Outils/Competences/Liste_Projet.php");}
												else{Titre("Project","Outils/Competences/Liste_Projet.php");}
												if($LangueAffichage=="FR"){Titre("Qualification","Outils/Competences/Liste_Qualification.php");}
												else{Titre("Qualification","Outils/Competences/Liste_Qualification.php");}
												if($LangueAffichage=="FR"){Titre("Unité d'exploitation","Outils/Competences/Liste_Plateforme.php");}
												else{Titre("Operating unit","Outils/Competences/Liste_Plateforme.php");}
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
						if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme))
						)
						{
						?>
					<td align="center" width="25%" valign="top">
						<table style='border-spacing:15px;display:inline-table;' >
							<tr>
								<td style="width:300px;border-style:outset; border-radius: 15px;height:90px;border-style:outset;border-color:#67cff1;border-spacing:0;color:black;valign:top;font-weight:bold;" bgcolor='#67cff1'>
									<table width='100%' height='100%'>	
										<tr>
											<td style="width:30%;height:100%;text-align:center;color:black;valign:top;font-weight:bold;">
												<?php 
													if($LangueAffichage=="FR"){echo "Module de formation";}
													else{echo "Training module";}
												?>
											</td>
										</tr>
										<tr>
											<td>
												<table style="width:100%; align:left; valign:top;">
												<?php
												if($LangueAffichage=="FR"){Titre("Catégorie formation","Outils/Competences/Liste_Categorie_Formation.php");}
												else{Titre("Training category","Outils/Competences/Liste_Categorie_Formation.php");}
												if($LangueAffichage=="FR"){Titre("Modules","Outils/Competences/Liste_ModuleFormation.php");}
												else{Titre("Modules","Outils/Competences/Liste_ModuleFormation.php");}
												if($LangueAffichage=="FR"){Titre2("Modules de formation (D-0738) / Training modules (D-0738) V2","Outils/Competences/D-0738_Modules_de_Formation.php");}
												else{Titre2("Modules de formation (D-0738) / Training modules (D-0738) V2","Outils/Competences/D-0738_Modules_de_Formation.php");}
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
</table>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>
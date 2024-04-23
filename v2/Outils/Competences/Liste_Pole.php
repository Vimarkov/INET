<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Mode,Id)
		{var w=window.open("Ajout_Pole.php?Mode="+Mode+"&Id="+Id,"PageFichier","status=no,menubar=no,width=800,height=500,resizable=yes,scrollbars=yes");w.focus();}
	function OuvreFenetreCompetences(Id)
		{var w=window.open("Tableau_Competences.php?Type=Pole&Id="+Id,"PageTableauCompetences","status=no,menubar=no,resizable=yes,scrollbars=yes,width=1020,height=600");w.focus();}
		function OuvreFenetreCompetencesAllege(Id)
		{var w=window.open("Tableau_CompetencesAllege.php?Type=Pole&Id="+Id,"PageTableauCompetences","status=no,menubar=no,resizable=yes,scrollbars=yes,width=1020,height=600");w.focus();}
	function OuvreFenetreFormations(Id)
		{var w=window.open("Tableau_Formations.php?Type=Pole&Id="+Id,"PageTableauFormation","status=no,menubar=no,resizable=yes,scrollbars=yes,width=1020,height=600");w.focus();}
	function OuvreFenetreCompetencesExport(Id)
		{var w=window.open("Tableau_Competences_Export.php?Type=Pole&Id="+Id,"PageTableauCompetencesExport","status=no,menubar=no,resizable=yes,scrollbars=yes,width=100,height=100");w.focus();}
	function OuvreFenetreCompetencesExportAllege(Id)
		{var w=window.open("Tableau_CompetencesAllege_Export.php?Type=Pole&Id="+Id,"PageTableauCompetencesExport","status=no,menubar=no,resizable=yes,scrollbars=yes,width=100,height=100");w.focus();}
</script>
<?php
$Droits="Aucun";
if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))	
|| DroitsPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme))
)
{
	$Droits="Administrateur";
}
elseif(DroitsFormationPrestation(array($IdPosteReferentQualiteProduit)))
{
	$Droits="Ecriture";
}
?>

<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing:0;">
				<tr>
					<td class="TitrePage"><?php 
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Competences/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
					if($LangueAffichage=="FR"){echo "Gestion des compétences # Gestion des pôles";}else{echo "Competencies management # Poles management";}?></td>
					<?php
					if($Droits=="Administrateur")
					{
					?>
					<td width="25">
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');">
							<img src="../../Images/Ajout.gif" border="0" alt="Ajouter un pole">
						</a>
					</td>
					<?php
						}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table>
				<tr>
					<td width="10"></td>
					<td>
						<table class="TableCompetences" style="width:1000px;">
						<?php
							$requete="SELECT new_competences_plateforme.Libelle, new_competences_prestation.Libelle, new_competences_pole.Id, new_competences_pole.Libelle,new_competences_pole.Actif";
							$requete.=" FROM new_competences_plateforme";
							$requete.=" INNER JOIN new_competences_prestation ON new_competences_plateforme.Id=new_competences_prestation.Id_Plateforme";
							$requete.=" INNER JOIN new_competences_pole ON new_competences_prestation.Id=new_competences_pole.Id_Prestation";
							$requete.=" ORDER BY new_competences_pole.Actif ASC, new_competences_plateforme.Libelle ASC, new_competences_prestation.Libelle ASC, new_competences_pole.Libelle ASC";
							$result=mysqli_query($bdd,$requete);
							$nbenreg=mysqli_num_rows($result);
							if($nbenreg>0)
							{
						?>
							<tr>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Pôle";}else{echo "Pole";}?></td>
								<td colspan="5" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Actif";}else{echo "Active";}?></td>
							</tr>
						<?php
							$Couleur="#EEEEEE";
							$Plateforme=0;
							while($row=mysqli_fetch_array($result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
								if($Plateforme!=$row[0]){echo "<tr height='1' bgcolor='#66AACC'><td colspan='8'></td></tr>";}
								$Plateforme=$row[0];
						?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td width=150><?php echo $Plateforme;?></td>
								<td width=450><?php echo $row[1];?></td>
								<td width=450><?php echo $row['Libelle'];?></td>
								<td width=450><?php if($row['Actif']==0){echo "Oui";}else{echo "Non";}?></td>
							<?php
								if($Droits=="Administrateur")
								{
							?>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row[2]; ?>');">
										<img src="../../Images/Modif.gif" border="0" alt="Modification">
									</a>
								</td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreCompetences('<?php echo $row[2]; ?>');">
										<img src="../../Images/Competences.gif" border="0" alt="Tableau des compétences" title="Tableau des compétences">
									</a>
								</td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreCompetencesExport('<?php echo $row[2]; ?>');">
										<img src="../../Images/excel.gif" border="0" alt="Tableau des compétences Excel" title="Tableau des compétences Excel">
									</a>
								</td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreCompetencesAllege('<?php echo $row[2]; ?>');">
										<img src="../../Images/etoileBleu.png" style="width:15px" border="0" alt="Tableau des compétences <?php echo substr($row[1],0,7)."-".$row['Libelle'];?>" title="Tableau des compétences <?php echo substr($row[1],0,7)."-".$row['Libelle'];?>">
									</a>
								</td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreCompetencesExportAllege('<?php echo $row[2]; ?>');">
										<img src="../../Images/excel.gif" border="0" alt="Tableau des compétences <?php echo substr($row[1],0,7)."-".$row['Libelle'];?> Excel" title="Tableau des compétences Excel <?php echo substr($row[1],0,7)."-".$row['Libelle'];?>">
									</a>
								</td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreFormations('<?php echo $row[2]; ?>');">
										<img src="../../Images/DroitsUtilisateurs.gif" border="0" alt="Tableau des formations" title="Tableau des formations">
									</a>
								</td>
								<td width="20">
									<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){OuvreFenetreModif('Suppr','<?php echo $row[2]; ?>');}">
								</td>
							<?php
								}
								elseif($Droits=="Ecriture")
								{
							?>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row[2]; ?>');">
										<img src="../../Images/Modif.gif" border="0" alt="Modification">
									</a>
								</td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreCompetences('<?php echo $row[2]; ?>');">
										<img src="../../Images/Competences.gif" border="0" alt="Tableau des compétences" title="Tableau des compétences">
									</a>
								</td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreCompetencesExport('<?php echo $row[2]; ?>');">
										<img src="../../Images/excel.gif" border="0" alt="Tableau des compétences Excel" title="Tableau des compétences Excel">
									</a>
								</td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreCompetencesAllege('<?php echo $row[2]; ?>');">
										<img src="../../Images/etoileBleu.png" style="width:15px" border="0" alt="Tableau des compétences <?php echo substr($row[1],0,7)."-".$row['Libelle'];?>" title="Tableau des compétences <?php echo substr($row[1],0,7)."-".$row['Libelle'];?>">
									</a>
								</td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreCompetencesExportAllege('<?php echo $row[2]; ?>');">
										<img src="../../Images/excel.gif" border="0" alt="Tableau des compétences <?php echo substr($row[1],0,7)."-".$row['Libelle'];?> Excel" title="Tableau des compétences Excel <?php echo substr($row[1],0,7)."-".$row['Libelle'];?>">
									</a>
								</td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreFormations('<?php echo $row[2]; ?>');">
										<img src="../../Images/DroitsUtilisateurs.gif" border="0" alt="Tableau des formations" title="Tableau des formations">
									</a>
								</td>
								<td width="20"></td>
							<?php
								}
								else
								{
							?>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreCompetences('<?php echo $row[2]; ?>');">
										<img src="../../Images/Competences.gif" border="0" alt="Tableau des compétences" title="Tableau des compétences">
									</a>
								</td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreCompetencesExport('<?php echo $row[2]; ?>');">
										<img src="../../Images/excel.gif" border="0" alt="Tableau des compétences Excel" title="Tableau des compétences Excel">
									</a>
								</td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreCompetencesAllege('<?php echo $row[2]; ?>');">
										<img src="../../Images/etoileBleu.png" style="width:15px" border="0" alt="Tableau des compétences <?php echo substr($row[1],0,7)."-".$row['Libelle'];?>" title="Tableau des compétences <?php echo substr($row[1],0,7)."-".$row['Libelle'];?>">
									</a>
								</td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreCompetencesExportAllege('<?php echo $row[2]; ?>');">
										<img src="../../Images/excel.gif" border="0" alt="Tableau des compétences <?php echo substr($row[1],0,7)."-".$row['Libelle'];?> Excel" title="Tableau des compétences Excel <?php echo substr($row[1],0,7)."-".$row['Libelle'];?>">
									</a>
								</td>
								<td colspan="2"></td>
							<?php
								}
							?>
							</tr>
						<?php
									}	//Fin boucle
								}		//Fin If
								mysqli_free_result($result);	// Libération des résultats
						?>
						</table>
					</td>
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
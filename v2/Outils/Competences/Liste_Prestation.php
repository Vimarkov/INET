<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Mode,Id)
		{window.open("Ajout_Prestation.php?Mode="+Mode+"&Id="+Id,"PageFichier","status=no,menubar=no,width=1240,height=810,resizable=yes,scrollbars=yes");}
	function OuvreFenetreCompetences(Id)
		{window.open("Tableau_Competences.php?Type=Prestation&Id="+Id,"PageTableauCompetences","status=no,menubar=no,resizable=yes,scrollbars=yes,width=1020,height=600");}
	function OuvreFenetreFormations(Id)
		{window.open("Tableau_Formations.php?Type=Prestation&Id="+Id,"PageTableauFormation","status=no,menubar=no,resizable=yes,scrollbars=yes,width=1020,height=600");}
	function OuvreFenetreEtatPersonnel(Id)
		{window.open("Etat_Personnel.php?Id="+Id,"PageEtatPersonnel","status=no,menubar=no,resizable=yes,scrollbars=yes,width=1020,height=600");}
	function OuvreFenetreIndicateur(Id)
		{window.open("Indicateur_Competences.php?Type=Prestation&Id="+Id,"PageIndicateur","status=no,menubar=no,resizable=yes,scrollbars=yes,width=1020,height=600");}
	function OuvreFenetreCompetencesExport(Id)
		{window.open("Tableau_Competences_Export.php?Type=Prestation&Id="+Id,"PageTableauCompetencesExport","status=no,menubar=no,resizable=yes,scrollbars=yes,width=100,height=100");}
	function OuvreFenetreExcel()
		{window.open("Liste_Prestation_Export.php","PagePrestation","status=no,menubar=no,resizable=yes,scrollbars=yes,width=100,height=100");}
	function OuvreFenetreIndicateurs(){
		Id="";
		var checkPresta = document.getElementsByName("checkPrest");
	   for(var i=0, n=checkPresta.length; i<n; i++) {
		  if(checkPresta[i].checked){
			if(Id==""){Id=checkPresta[i].value;}
			else{Id=Id+","+checkPresta[i].value;}
		  }
	   }
		if(Id!=""){
			window.open("Indicateur_Competences.php?Type=Prestation&Id="+Id,"PageIndicateur","status=no,menubar=no,resizable=yes,scrollbars=yes,width=1020,height=600");
		}
	}
</script>
<?php
$Droits="Aucun";
if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))	
)
{
	$Droits="Administrateur";
}
elseif(DroitsPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme)))
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
					if($LangueAffichage=="FR"){echo "Gestion des compétences # Gestion des prestations";}else{echo "Competencies management # Activities management";}?></td>
					<?php if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme))){ ?>
					<td width="50">
						<?php if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme))){ ?>
						&nbsp;&nbsp;&nbsp;
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');">
							<img src="../../Images/Ajout.gif" border="0" alt="Ajouter une Prestation">
						</a>
						<?php } ?>
					</td>
					<?php
						}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="right"><a href="javascript:OuvreFenetreExcel()">
		<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
		</a></td>
	</tr>
	<tr>
		<td>
			<table>
				<tr>
					<td width="10"></td>
					<td>
						<table class="TableCompetences">
						<?php
							$Id_Plateformes=0;
							if(isset($_SESSION['Id_Plateformes'])){$Id_Plateformes=implode(",",$_SESSION['Id_Plateformes']);}
							$req="
								SELECT
									new_competences_prestation.Id,
									new_competences_prestation.Libelle,
									new_competences_plateforme.Libelle,
									new_competences_projet.Libelle,
									new_competences_prestation.Active,
									(SELECT Libelle FROM rh_domaine WHERE rh_domaine.Id=new_competences_prestation.Id_Domaine) AS Domaine
								FROM
									new_competences_prestation
									LEFT JOIN new_competences_plateforme ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
									LEFT JOIN new_competences_projet ON new_competences_prestation.Id_Projet=new_competences_projet.Id
								WHERE
									new_competences_prestation.Id_Plateforme IN (".$Id_Plateformes.")
								ORDER BY
									new_competences_prestation.Active DESC,
									new_competences_plateforme.Libelle ASC,
									new_competences_prestation.Libelle ASC";
							$result=mysqli_query($bdd,$req);
							$nbenreg=mysqli_num_rows($result);
							if($nbenreg>0)
							{
						?>
							<tr>
								<td class="EnTeteTableauCompetences" title="Active/Inactive">A/I</td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Projet";}else{echo "Project";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Domaine";}else{echo "Domain";}?></td>
								<td colspan="6" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
								<td >
									<a class="Modif" href="javascript:OuvreFenetreIndicateurs();">
										<img src="../../Images/TableauIndicateur.png" border="0" alt="Ind" title="Ind">
									</a>
								</td>
								<td></td>
							</tr>
						<?php
							$Couleur="#EEEEEE";
							while($row=mysqli_fetch_array($result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
						?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td width=30><?php if($row[4]==-1){echo "I";} else{ echo "A";}?></td>
								<td width=150><?php echo $row[2];?></td>
								<td width=100><?php echo $row[3];?></td>
								<td width=100><?php echo $row['Domaine'];?></td>
								<td width=450><?php echo $row[1];?></td>
								<?php if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme))){ ?>
									<td width="20">
										<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row['0']; ?>');">
											<img src="../../Images/Modif.gif" border="0" alt="Modification">
										</a>
									</td>
								<?php
									}
									else{
								?>
									<td width="20"></td>
								<?php
									}
								?>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreCompetences('<?php echo $row['0']; ?>');">
										<img src="../../Images/Competences.gif" border="0" alt="Tableau des compétences" title="Tableau des compétences">
									</a>
								</td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreCompetencesExport('<?php echo $row['0']; ?>');">
										<img src="../../Images/excel.gif" border="0" alt="Tableau des compétences Excel" title="Tableau des compétences Excel">
									</a>
								</td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreFormations('<?php echo $row['0']; ?>');">
										<img src="../../Images/DroitsUtilisateurs.gif" border="0" alt="Tableau des formations" title="Tableau des formations">
									</a>
								</td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreEtatPersonnel('<?php echo $row['0']; ?>');">
										<img src="../../Images/Tableau.gif" border="0" alt="EtatPersonnel" title="EtatPersonnel">
									</a>
								</td>
								<td width=10><input type="checkbox" name="checkPrest" value="<?php echo $row['Id']; ?>" ></td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreIndicateur('<?php echo $row['0']; ?>');">
										<img src="../../Images/TableauIndicateur.png" border="0" alt="Indicateur" title="Indicateur">
									</a>
								</td>
								<?php if(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme))){ ?>
								<td width="20">
									<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){OuvreFenetreModif('Suppr','<?php echo $row['0']; ?>');}">
								</td>
								<?php
									}
									else{
								?>
									<td width="20"></td>
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
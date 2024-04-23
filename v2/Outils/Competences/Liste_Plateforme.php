<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Mode,Id)
		{window.open("Ajout_Plateforme.php?Mode="+Mode+"&Id="+Id,"PageFichier","status=no,menubar=no,width=800,height=500");}
	function OuvreFenetreCompetences(Id)
		{window.open("Tableau_Competences.php?Type=Plateforme&Id="+Id,"PageTableauCompetences","status=no,menubar=no,resizable=yes,scrollbars=yes,width=1020,height=600");}
	function OuvreFenetreIndicateur(Id)
		{window.open("Indicateur_CompetencesUER.php?Type=Plateforme&Id="+Id,"PageIndicateur","status=no,menubar=no,resizable=yes,scrollbars=yes,width=1020,height=600");}
	function OuvreFenetreIndicateurDetaille(Id)
		{window.open("Indicateur_CompetencesDetaille.php?Type=Plateforme&Id="+Id,"PageIndicateur2","status=no,menubar=no,resizable=yes,scrollbars=yes,width=1520,height=800");}
	function OuvreFenetreIndicateurs(){
		Id="";
		var checkPlatforme = document.getElementsByName("checkPlat");
	   for(var i=0, n=checkPlatforme.length; i<n; i++) {
		  if(checkPlatforme[i].checked){
			if(Id==""){Id=checkPlatforme[i].value;}
			else{Id=Id+","+checkPlatforme[i].value;}
		  }
	   }
		if(Id!=""){
			window.open("Indicateur_Competences.php?Type=Plateforme&Id="+Id,"PageIndicateur","status=no,menubar=no,resizable=yes,scrollbars=yes,width=1020,height=600");
		}
	}
	function OuvreFenetreCompetencesExport(Id)
		{window.open("Tableau_Competences_Export.php?Type=Plateforme&Id="+Id,"PageTableauCompetencesExport","status=no,menubar=no,resizable=yes,scrollbars=yes,width=100,height=100");}
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
					if($LangueAffichage=="FR"){echo "Gestion des compétences # Gestion des unités d'exploitations";}else{echo "Competencies management # Operating unit management";}?></td>
					<?php
					if($Droits=="Administrateur")
					{
					?>
					<td width="25">
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');">
							<img src="../../Images/Ajout.gif" border="0" alt="Ajouter une plateforme">
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
			<table class="TableCompetences" style="width:90%;">
			<?php
				$result=mysqli_query($bdd,"SELECT *, (SELECT Libelle FROM new_competences_division2 WHERE Id=Id_Division) AS Division FROM new_competences_plateforme WHERE Inactif=0 AND Id<>11 AND Id<>14 ORDER BY Libelle ASC");
				$nbenreg=mysqli_num_rows($result);
				if($nbenreg>0)
				{
			?>
				<tr>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Division";}else{echo "Division";}?></td>
					<td class="EnTeteTableauCompetences" width="20%"><?php if($LangueAffichage=="FR"){echo "Adresse";}else{echo "Address";}?></td>
					<td class="EnTeteTableauCompetences" width="20%"><?php if($LangueAffichage=="FR"){echo "Entreprise";}else{echo "Company";}?></td>
					<td class="EnTeteTableauCompetences" width="20%"><?php if($LangueAffichage=="FR"){echo "Adresse entreprise";}else{echo "Company address";}?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "ARP ID";}else{echo "ARP ID";}?></td>
					<td class="EnTeteTableauCompetences" colspan="3">
					</td>
					<td class="EnTeteTableauCompetences">
						<a class="Modif" href="javascript:OuvreFenetreIndicateurs();">
							<img src="../../Images/TableauIndicateur.png" border="0" alt="Ind" title="Ind">
						</a>
					</td>
					<td class="EnTeteTableauCompetences" colspan="3">
					</td>
				</tr>
			<?php
				$Couleur="#EEEEEE";
				while($row=mysqli_fetch_array($result))
				{
					if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
					else{$Couleur="#EEEEEE";}
			?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td><?php echo $row['Libelle'];?></td>
					<td><?php echo $row['Division'];?></td>
					<td><?php echo $row['Adresse'];?></td>
					<td><?php echo $row['Company'];?></td>
					<td><?php echo $row['CompanyAdresse'];?></td>
					<td><?php echo $row['ARP_Id'];?></td>
				<?php
					if($Droits=="Administrateur")
					{
				?>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row['Id']; ?>');">
							<img src="../../Images/Modif.gif" border="0" alt="Modification">
						</a>
					</td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreCompetences('<?php echo $row['Id']; ?>');">
							<img src="../../Images/Competences.gif" border="0" alt="Tableau des compétences" title="Tableau des compétences">
						</a>
					</td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreCompetencesExport('<?php echo $row['Id']; ?>');">
							<img src="../../Images/excel.gif" border="0" alt="Tableau des compétences Excel" title="Tableau des compétences Excel">
						</a>
					</td>
					<td><input type="checkbox" name="checkPlat" value="<?php echo $row['Id']; ?>" ></td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreIndicateur('<?php echo $row['Id']; ?>');">
							<img src="../../Images/TableauIndicateur.png" border="0" alt="Ind" title="Ind">
						</a>
					</td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreIndicateurDetaille('<?php echo $row['Id']; ?>');">
							<img src="../../Images/Tableau.gif" border="0" alt="Ind" title="Ind">
						</a>
					</td>
					<td>
						<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){OuvreFenetreModif('Suppr','<?php echo $row['Id']; ?>');}">
					</td>
				<?php
					}
					elseif($Droits=="Ecriture")
					{
				?>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row['Id']; ?>');">
							<img src="../../Images/Modif.gif" border="0" alt="Modification">
						</a>
					</td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreCompetences('<?php echo $row['Id']; ?>');">
							<img src="../../Images/Competences.gif" border="0" alt="Tableau des compétences" title="Tableau des compétences">
						</a>
					</td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreCompetencesExport('<?php echo $row['Id']; ?>');">
							<img src="../../Images/excel.gif" border="0" alt="Tableau des compétences Excel" title="Tableau des compétences Excel">
						</a>
					</td>
					<td><input type="checkbox" name="checkPlat" value="<?php echo $row['Id']; ?>" ></td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreIndicateur('<?php echo $row['Id']; ?>');">
							<img src="../../Images/TableauIndicateur.png" border="0" alt="Ind" title="Ind">
						</a>
					</td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreIndicateurDetaille('<?php echo $row['Id']; ?>');">
							<img src="../../Images/Tableau.gif" border="0" alt="Ind" title="Ind">
						</a>
					</td>
					<td></td>
				<?php
					}
					else
					{
				?>
					<td>
					</td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreCompetences('<?php echo $row['Id']; ?>');">
							<img src="../../Images/Competences.gif" border="0" alt="Tableau des compétences" title="Tableau des compétences">
						</a>
					</td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreCompetencesExport('<?php echo $row['Id']; ?>');">
							<img src="../../Images/excel.gif" border="0" alt="Tableau des compétences Excel" title="Tableau des compétences Excel">
						</a>
					</td>
					<td><input type="checkbox" name="checkPlat" value="<?php echo $row['Id']; ?>" ></td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreIndicateur('<?php echo $row['Id']; ?>');">
							<img src="../../Images/TableauIndicateur.png" border="0" alt="Ind" title="Ind">
						</a>
					</td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreIndicateurDetaille('<?php echo $row['Id']; ?>');">
							<img src="../../Images/Tableau.gif" border="0" alt="Ind" title="Ind">
						</a>
					</td>
					<td></td>
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

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
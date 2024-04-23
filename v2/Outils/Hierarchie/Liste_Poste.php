<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Mode,Id)
		{window.open("Ajout_Poste.php?Mode="+Mode+"&Id="+Id,"PageFichier","status=no,menubar=no,width=600,height=50");}
</script>

<table style="width:100%; border-pacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#6EB4CD;">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Hiérarchie du personnel # Postes</td>
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
						<table class="TableCompetences" style="width:450;">
						<?php
							$requetePoste="SELECT * ";
							$requetePoste.=" FROM new_competences_poste";
							$requetePoste.=" ORDER BY new_competences_poste.Libelle ASC";
							$resultPoste=mysqli_query($bdd,$requetePoste);
							$nbenregPoste=mysqli_num_rows($resultPoste);
							if($nbenregPoste>0)
							{
						?>
							<tr>
								<td class="EnTeteTableauCompetences">Poste</td>
								<td colspan="3" class="EnTeteTableauCompetences">Poste responsable</td>
							</tr>
						<?php
							$Couleur="#EEEEEE";
							while($rowPoste=mysqli_fetch_array($resultPoste))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
								$PosteResponsable="Aucun";
								if($rowPoste[1]>0)
								{
									$requetePosteResponsable="SELECT Id, Libelle FROM new_competences_poste WHERE Id=".$rowPoste[1];
									$resultPosteResponsable=mysqli_query($bdd,$requetePosteResponsable);
									$rowPosteResponsable=mysqli_fetch_array($resultPosteResponsable);
									$PosteResponsable=$rowPosteResponsable[1];
									mysqli_free_result($resultPosteResponsable);	// Libération des résultats
								}
						?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td width="200"><?php echo $rowPoste[2];?></td>
								<td width="200"><?php echo $PosteResponsable;?></td>
							<?php
								if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur)))
								{
							?>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $rowPoste[0]; ?>');">
										<img src="../../Images/Modif.gif" border="0" alt="Modification" title="Modifier le nom du poste">
									</a>
								</td>
							<?php
								}
								else{
							?>
								<td colspan="2"></td>
							<?php
								}
							?>
							</tr>
						<?php
								}	//Fin boucle
							}		//Fin If
							mysqli_free_result($resultPoste);	// Libération des résultats
						?>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
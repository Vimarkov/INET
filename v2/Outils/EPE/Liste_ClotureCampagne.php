<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Mode,Annee)
		{window.open("Ajout_ClotureCampagne.php?Mode="+Mode+"&Annee="+Annee,"PageClotureCampagne","status=no,menubar=no,width=675,height=150");}
</script>
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#f5f74b;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Clôture campagne";}else{echo "Campaign closing";}
					?>
					</td>
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
						<table class="TableCompetences" style="width:350px;">
							<tr>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Année";}else{echo "Year";}?></td>
								<td class="EnTeteTableauCompetences" colspan="2"><?php if($LangueAffichage=="FR"){echo "Date clôture";}else{echo "Closing date";}?></td>
							</tr>
						<?php
                            $Requete="
                                SELECT DISTINCT
                                    YEAR(DateButoir) AS Annee
                                FROM
                                    epe_personne_datebutoir
								ORDER BY
									YEAR(DateButoir)";
                            $Result=mysqli_query($bdd,$Requete);
							$NbEnreg=mysqli_num_rows($Result);
							if($NbEnreg>0)
							{
							$Couleur="#EEEEEE";
							while($Row=mysqli_fetch_array($Result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
								
								$Requete="
									SELECT DateCloture
									FROM
										epe_cloturecampagne
									WHERE Annee=".$Row['Annee']." ";
								$Result2=mysqli_query($bdd,$Requete);
								$NbEnreg2=mysqli_num_rows($Result2);
								
								$dateCloture="";
								if($NbEnreg2>0){
									$Row2=mysqli_fetch_array($Result2);
									$dateCloture=AfficheDateJJ_MM_AAAA($Row2['DateCloture']);
								}
								
						?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td><?php echo stripslashes($Row['Annee']);?></td>
								<td><?php echo $dateCloture;?></td>
							<?php
								if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH))){
							?>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreModif('<?php if($NbEnreg2>0){echo "Modif";}else{echo "Ajout";}?>','<?php echo $Row['Annee']; ?>');">
										<img src="../../Images/Modif.gif" border="0" alt="Modification">
									</a>
								</td>
								<td width="20">
									<?php if($dateCloture<>""){?>
									<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){OuvreFenetreModif('Suppr','<?php echo $Row['Annee']; ?>');}">
									<?php }?>
								</td>
							<?php
								}
								else
								{
							?>
								<td colspan="2"></td>
							<?php
								}
							?>
							</tr>
						<?php
							}	//Fin boucle
						}		//Fin If
						mysqli_free_result($Result);	// Libération des résultats
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
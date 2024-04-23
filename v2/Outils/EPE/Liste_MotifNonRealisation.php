<?php
require("../../Menu.php");

?>
<script type="text/javascript">
	function OuvreFenetreModif(Mode,Id)
		{window.open("Ajout_MotifNonRealisation.php?Mode="+Mode+"&Id="+Id,"PageTypeEvolution","status=no,menubar=no,width=675,height=150");}
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
						
					if($LangueAffichage=="FR"){echo "Motif de non réalisation";}else{echo "Reason for non-achievement";}
					?>
					</td>
					<?php
						if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH))){
					?>
					<td width="25">
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');">
							<img src="../../Images/Ajout.gif" border="0" alt="Ajouter">
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
						<table class="TableCompetences" style="width:750px;">
							<tr>
								<td class="EnTeteTableauCompetences" colspan="3"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
							</tr>
						<?php
                            $Requete="
                                SELECT
                                    Id,
                                    Libelle
                                FROM
                                    epe_motifnonrealisation
                                WHERE
                                    Suppr=0
								ORDER BY
									Libelle";
                            $Result=mysqli_query($bdd,$Requete);
							$NbEnreg=mysqli_num_rows($Result);
							if($NbEnreg>0)
							{
							$Couleur="#EEEEEE";
							while($Row=mysqli_fetch_array($Result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
						?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td><?php echo stripslashes($Row['Libelle']);?></td>
							<?php
								if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH))){
							?>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $Row['Id']; ?>');">
										<img src="../../Images/Modif.gif" border="0" alt="Modification">
									</a>
								</td>
								<td width="20">
									<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){OuvreFenetreModif('Suppr','<?php echo $Row['Id']; ?>');}">
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
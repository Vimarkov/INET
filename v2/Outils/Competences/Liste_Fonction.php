<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModifF(Mode,Id)
		{window.open("Ajout_Fonction.php?Mode="+Mode+"&Id="+Id,"PageFichier","status=no,menubar=no,width=600,height=50");}
</script>
<?php
$Droits=Droits_PersonneConnectee_PageExtranet("RH","GestionCompetences","");
?>

<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing:0;">
				<tr>
					<td class="TitrePage"><?php 
					if($LangueAffichage=="FR"){echo "Gestion des compétences # Gestion des fonctions";}else{echo "Competencies management # Functions management";}?></td>
					<?php
					if($Droits=="Administrateur")
					{
					?>
					<td width="25">
						<a class="Modif" href="javascript:OuvreFenetreModifF('Ajout','0');">
							<img src="../../Images/Ajout.gif" border="0" alt="Ajouter une fonction">
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
						<table class="TableCompetences" style="width:450px;">
						<?php
							$result=mysqli_query($bdd,"SELECT * FROM new_competences_fonction ORDER BY Libelle ASC");
							$nbenreg=mysqli_num_rows($result);
							if($nbenreg>0)
							{
						?>
							<tr>
								<td colspan="3" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
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
							<?php
								if($Droits=="Administrateur")
								{
							?>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreModifF('Modif','<?php echo $row['Id']; ?>');">
										<img src="../../Images/Modif.gif" border="0" alt="Modification">
									</a>
								</td>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreModifF('Suppr','<?php echo $row['Id']; ?>');">
										<img src="../../Images/Suppression.gif" border="0" alt="Suppression">
									</a>
								</td>
							<?php
								}
								elseif($Droits=="Ecriture")
								{
							?>
								<td width="20">
									<a class="Modif" href="javascript:OuvreFenetreModifF('Modif','<?php echo $row['Id']; ?>');">
										<img src="../../Images/Modif.gif" border="0" alt="Modification">
									</a>
								</td>
								<td width="20"></td>
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
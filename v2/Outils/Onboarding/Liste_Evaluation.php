<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr><td height="10"></td></tr>
	<tr>
		<td align="right" colspan="8">
			<a style="text-decoration:none;" href="javascript:OuvreFenetreExport();">
				<img src="../../Images/excel.gif" border="0" alt="Excel" title="Export Excel">
			</a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr><td colspan="8">
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:70%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="12%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Date";}else{echo "Date";} ?></td>
				<td class="EnTeteTableauCompetences" width="12%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Person";}else{echo "Personne";} ?></td>
				<td class="EnTeteTableauCompetences" width="20%" >&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Temps passé vs. utilité des informations présentées";}else{echo "Time spent vs. usefulness of the information presented";} ?></td>
				<td class="EnTeteTableauCompetences" width="20%" >&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Facilité de navigation dans l’espace accueil";}else{echo "Ease of navigation in the home page";} ?></td>
				<td class="EnTeteTableauCompetences" width="35%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Comment";}else{echo "Commentaire";} ?></td>
			</tr>
			<?php
				$req="SELECT 
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
					NoteTemps,NoteFacilite,Commentaire,DateFeedback FROM onboarding_feedback ORDER BY DateFeedback DESC ";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						?>
						<tr>
							<td style='border-bottom:1px dotted #001dcf'><?php echo AfficheDateJJ_MM_AAAA($row['DateFeedback']);?></td>
							<td style='border-bottom:1px dotted #001dcf'><?php echo $row['Personne'];?></td>
							<td style='border-bottom:1px dotted #001dcf'>
								<?php 
									$value1=$row['NoteTemps']*20;
								?>
								<!--div optionnelle pour contenir le tout-->
								<div style="float:left;width:100px;"> 

								<!--div en arrière-plan qui s'allongera en fonction de la valeur de $value1-->
								  <div style="height:20px; width: <?php echo $value1;?>px; background:#E0E001;">

								<!--div qui contient les étoiles-->
									<div id="glob" >
									  <img id="tde_1" src="../../Images/star.png" class="tde"/>
									  <img id="tde_2" src="../../Images/star.png" class="tde"/>
									  <img id="tde_3" src="../../Images/star.png" class="tde"/>
									  <img id="tde_4" src="../../Images/star.png" class="tde"/>
									  <img id="tde_5" src="../../Images/star.png" class="tde"/>    
									</div>
								  </div>
								</div>
							</td>
							<td style='border-bottom:1px dotted #001dcf'>
								<?php 
									$value2=$row['NoteFacilite']*20;
								?>
								<!--div optionnelle pour contenir le tout-->
								<div style="float:left;width:100px;"> 

								<!--div en arrière-plan qui s'allongera en fonction de la valeur de $value1-->
								   <div style="height:20px; width: <?php echo $value2;?>px; background:#E0E001;">

								<!--div qui contient les étoiles-->
									<div id="glob2" >
									  <img id="tdf_1" src="../../Images/star.png" class="tdf"/>
									  <img id="tdf_2" src="../../Images/star.png" class="tdf"/>
									  <img id="tdf_3" src="../../Images/star.png" class="tdf"/>
									  <img id="tdf_4" src="../../Images/star.png" class="tdf"/>
									  <img id="tdf_5" src="../../Images/star.png" class="tdf"/>    
									</div>
								  </div>
								</div>
							</td>
							<td style='border-bottom:1px dotted #001dcf'><?php echo nl2br(stripslashes($row['Commentaire']));?></td>
							
						</tr>
						<?php
					}
					$req="SELECT 
						AVG(NoteTemps) AS NoteTemps,AVG(NoteFacilite) AS NoteFacilite
						FROM onboarding_feedback ";
					$result2=mysqli_query($bdd,$req);
					$row2=mysqli_fetch_array($result2);
					?>
					<tr>
						<td colspan="2" class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Average";}else{echo "Moyenne";} ?></td>
						<td style='border-bottom:1px dotted #001dcf'>
							<?php 
								$value1=$row2['NoteTemps']*20;
							?>
							<!--div optionnelle pour contenir le tout-->
							<div style="float:left;width:100px;"> 

							<!--div en arrière-plan qui s'allongera en fonction de la valeur de $value1-->
							  <div style="height:20px; width: <?php echo $value1;?>px; background:#E0E001;">

							<!--div qui contient les étoiles-->
								<div id="glob" >
								  <img id="tde_1" src="../../Images/star.png" class="tde"/>
								  <img id="tde_2" src="../../Images/star.png" class="tde"/>
								  <img id="tde_3" src="../../Images/star.png" class="tde"/>
								  <img id="tde_4" src="../../Images/star.png" class="tde"/>
								  <img id="tde_5" src="../../Images/star.png" class="tde"/>    
								</div>
							  </div>
							</div>
						</td>
						<td style='border-bottom:1px dotted #001dcf'>
							<?php 
								$value2=$row2['NoteFacilite']*20;
							?>
							<!--div optionnelle pour contenir le tout-->
							<div style="float:left;width:100px;"> 

							<!--div en arrière-plan qui s'allongera en fonction de la valeur de $value1-->
							   <div style="height:20px; width: <?php echo $value2;?>px; background:#E0E001;">

							<!--div qui contient les étoiles-->
								<div id="glob2" >
								  <img id="tdf_1" src="../../Images/star.png" class="tdf"/>
								  <img id="tdf_2" src="../../Images/star.png" class="tdf"/>
								  <img id="tdf_3" src="../../Images/star.png" class="tdf"/>
								  <img id="tdf_4" src="../../Images/star.png" class="tdf"/>
								  <img id="tdf_5" src="../../Images/star.png" class="tdf"/>    
								</div>
							  </div>
							</div>
						</td>
						<td style='border-bottom:1px dotted #001dcf'></td>
					</tr>
					<?php
				}
			?>
		</table>
	</td></tr>
</table>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
<style>
	/*code CSS */
	.tde {height:20px;width:20px;cursor:pointer;}
	.tdf {height:20px;width:20px;cursor:pointer;}
	#glob {display: flex;}
	#glob2 {display: flex;}
</style>
</body>
</html>
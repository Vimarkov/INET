<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr><td height="10"></td></tr>
	<tr>
		<td align="center" colspan="8">
			<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout("Ajout_Surveillant.php")'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add supervisor";}else{echo "Ajouter un surveillant";} ?>&nbsp;</a>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr><td colspan="8">
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:100%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="8%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "UER";}else{echo "UER";} ?></td>
				<td class="EnTeteTableauCompetences" width="8%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Name";}else{echo "Personne";} ?></td>
				<?php 
					$req="SELECT Id,Libelle
						FROM soda_theme
						WHERE Suppr=0 
						ORDER BY Libelle ";
					$resultT=mysqli_query($bdd,$req);
					$nbTheme=mysqli_num_rows($resultT);
					if ($nbTheme > 0)
					{
						while($rowT=mysqli_fetch_array($resultT))
						{
				?>
						<td width="5%" class="EnTeteTableauCompetences" style="text-align:center"><?php echo $rowT['Libelle'];?></td>
				<?php
						}
					}
				?>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
			</tr>
			<?php
				$req="SELECT new_rh_etatcivil.Id,new_rh_etatcivil.Nom,new_rh_etatcivil.Prenom
					FROM soda_surveillant 
					LEFT JOIN new_rh_etatcivil 
					ON soda_surveillant.Id_Personne=new_rh_etatcivil.Id 
					ORDER BY (SELECT DISTINCT (SELECT Libelle FROM new_competences_plateforme WHERE Id=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)) AS UER
							FROM new_competences_personne_prestation
							WHERE Date_Debut<='".date('Y-m-d')."'
							AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01')
							AND Id_Personne=new_rh_etatcivil.Id LIMIT 1),Nom, Prenom;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						?>
						<tr bgcolor="<?php echo $couleur;?>">
							<td width="8%">&nbsp;
							<?php 
								$uer="";
								
								$req="SELECT DISTINCT (SELECT Libelle FROM new_competences_plateforme WHERE Id=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)) AS UER
									FROM new_competences_personne_prestation
									WHERE Date_Debut<='".date('Y-m-d')."'
									AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01')
									AND Id_Personne=".$row['Id']."
									ORDER BY UER ";
								$resultUER=mysqli_query($bdd,$req);
								while($rowUER=mysqli_fetch_array($resultUER))
								{
									if($uer<>""){$uer.="<br>";}
									$uer.=$rowUER['UER'];
								}
								echo $uer;
							?>
							</td>
							<td width="16%">&nbsp;<?php echo $row['Nom']." ".$row['Prenom'];?></td>
							<?php 
							$req="SELECT Id,Libelle
								FROM soda_theme
								WHERE Suppr=0 
								ORDER BY Libelle ";
							$resultT=mysqli_query($bdd,$req);
							while($rowTheme=mysqli_fetch_array($resultT))
									{
										$surveillable="<img width='15px' src='../../Images/delete.png' border='0' />";
										$req="SELECT Id 
											FROM soda_surveillant_theme 
											WHERE Id_Surveillant=".$row['Id']."
											AND Id_Theme=".$rowTheme['Id']." ";
										$resultSurveillable=mysqli_query($bdd,$req);
										$nbSurveillable=mysqli_num_rows($resultSurveillable);
										if ($nbSurveillable > 0)
										{
											$surveillable="<img width='15px' src='../../Images/tick.png' border='0' />";
										}
							?>
										<td align="center"><?php echo $surveillable;?></td>
							<?php			
									}
							?>
							<td width="2%" align="center">
								<a href="javascript:OuvreFenetreModif('Ajout_Surveillant.php','Modif',<?php echo $row['Id']; ?>)">
									<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
								</a>
							</td>
							<td width="2%" align="center">
								<a href="javascript:OuvreFenetreSuppr('Ajout_Surveillant.php',<?php echo $row['Id']; ?>)">
									<img src='../../Images/Suppression.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>'>
								</a>
							</td>
						</tr>
						<?php
						if($couleur=="#ffffff"){$couleur="#a3e4ff";}
						else{$couleur="#ffffff";}
					}
				}
			?>
		</table>
	</td></tr>
</table>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
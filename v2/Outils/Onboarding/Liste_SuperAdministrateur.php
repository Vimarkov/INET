<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr><td height="10"></td></tr>
	<tr>
		<td align="center" colspan="8">
			<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout("Ajout_SuperAdministrateur.php")'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add a Super Admin";}else{echo "Ajouter un super administrateur";} ?>&nbsp;</a>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr><td colspan="8">
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:40%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="16%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Name";}else{echo "Personne";} ?></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
			</tr>
			<?php
				$req="SELECT new_rh_etatcivil.Id,new_rh_etatcivil.Nom,new_rh_etatcivil.Prenom 
					FROM onboarding_superadministrateur 
					LEFT JOIN new_rh_etatcivil ON onboarding_superadministrateur.Id_Personne=new_rh_etatcivil.Id 
					ORDER BY Nom, Prenom;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						?>
						<tr bgcolor="<?php echo $couleur;?>">
							<td width="16%">&nbsp;<?php echo $row['Nom']." ".$row['Prenom'];?></td>
							<td width="2%" align="center">
								<a href="javascript:OuvreFenetreSuppr('Ajout_SuperAdministrateur.php',<?php echo $row['Id']; ?>)">
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
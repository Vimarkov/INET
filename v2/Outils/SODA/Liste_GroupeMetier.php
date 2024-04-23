<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<input type="hidden" id="Menu" name="Menu" value="<?php echo $Menu; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr><td height="10"></td></tr>
	<tr>
		<td width="60%" valign="top">
		</td>
		<td width="40%" valign="top" align="center">
			<a style='text-decoration:none;' class='Bouton' href="javascript:OuvreFenetreModif('Ajout_GroupeMetier.php','Ajout','0')">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add a business group";}else{echo "Ajouter un groupe métier";} ?>&nbsp;</a>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr>
		<td width="60%" valign="top">
			<table align="center" class="TableCompetences" style="width:95%;">
				<tr>
					<td width="20%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Métier";}else{echo "Job";}?></td>
					<td width="20%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Groupe métier";}else{echo "Business groups";}?></td>
					<td width="2%" class="EnTeteTableauCompetences"></td>
				</tr>
			<?php
				$req="SELECT Id, 
					Libelle, 
					(SELECT Libelle FROM soda_groupemetier WHERE Id=Id_GroupeMetierSODA) AS GroupeMetier
					FROM new_competences_metier 
					WHERE Suppr=0 
					ORDER BY Libelle ";
				$result=mysqli_query($bdd,$req);
				$nbenreg=mysqli_num_rows($result);
				if($nbenreg>0)
				{
					$Couleur="#EEEEEE";
					while($row=mysqli_fetch_array($result))
					{
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
			?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td><?php echo $row['Libelle'];?></td>
					<td><?php echo $row['GroupeMetier'];?></td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout_Metier.php','Modif','<?php echo $row['Id']; ?>');">
							<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
						</a>
					</td>
				</tr>
			<?php
					}	//Fin boucle
				}		//Fin If
				mysqli_free_result($result);	// Libération des résultats
			?>
			</table>
		</td>
		<td width="40%" valign="top">
			<table align="center" class="TableCompetences" style="width:100%;">
				<tr>
					<td width="20%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Groupe métier";}else{echo "Business groups";}?></td>
					<td width="2%" class="EnTeteTableauCompetences"></td>
					<td width="2%" class="EnTeteTableauCompetences"></td>
				</tr>
			<?php
				$req="SELECT Id, 
					Libelle
					FROM soda_groupemetier 
					WHERE Suppr=0 
					ORDER BY Libelle ";
				$result=mysqli_query($bdd,$req);
				$nbenreg=mysqli_num_rows($result);
				if($nbenreg>0)
				{
					$Couleur="#EEEEEE";
					while($row=mysqli_fetch_array($result))
					{
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
			?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td><?php echo $row['Libelle'];?></td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout_GroupeMetier.php','Modif','<?php echo $row['Id']; ?>');">
							<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
						</a>
					</td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout_GroupeMetier.php','Suppr','<?php echo $row['Id']; ?>');">
							<img src="../../Images/Suppression.gif" style="border:0;" alt="Suppression">
						</a>
					</td>
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
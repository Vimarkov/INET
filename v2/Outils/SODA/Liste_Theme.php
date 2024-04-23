<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<input type="hidden" id="Menu" name="Menu" value="<?php echo $Menu; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td align="center" colspan="8">
			<a style='text-decoration:none;' class='Bouton' href="javascript:OuvreFenetreModif('Ajout_Theme.php','Ajout','0')">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add theme";}else{echo "Ajouter un thème";} ?>&nbsp;</a>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr>
		<td>
			<table align="center" class="TableCompetences" style="width:60%;">
				<tr>
					<td width="20%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Thème";}else{echo "Theme";}?></td>
					<td width="20%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Qualification liée pour être surveillant";}else{echo "Related qualification to be a supervisor";}?></td>
					<td width="20%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Gestionnaire";}else{echo "Administrator";}?></td>
					<td width="20%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Backup(s)";}else{echo "Backup";}?></td>
					<td width="2%" class="EnTeteTableauCompetences"></td>
					<td width="2%" class="EnTeteTableauCompetences"></td>
				</tr>
			<?php
				$req="SELECT Id, 
					Libelle, 
					(SELECT Libelle FROM new_competences_qualification WHERE new_competences_qualification.Id=Id_Qualification) AS Qualification,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Gestionnaire) AS Gestionnaire,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Backup1) AS Backup1,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Backup2) AS Backup2,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Backup3) AS Backup3
					FROM soda_theme 
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
						$backup="";
						if($row['Backup1']<>""){$backup.=$row['Backup1'];}
						if($row['Backup2']<>""){
							if($backup<>""){$backup.="<br>";}
							$backup.=$row['Backup2'];
						}
						if($row['Backup3']<>""){
							if($backup<>""){$backup.="<br>";}
							$backup.=$row['Backup3'];
						}
			?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td><?php echo $row['Libelle'];?></td>
					<td><?php echo stripslashes($row['Qualification']);?></td>
					<td><?php echo $row['Gestionnaire'];?></td>
					<td><?php echo $backup;?></td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout_Theme.php','Modif','<?php echo $row['Id']; ?>');">
							<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
						</a>
					</td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout_Theme.php','Suppr','<?php echo $row['Id']; ?>');">
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
<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<input type="hidden" id="Menu" name="Menu" value="<?php echo $Menu; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td>
			<table align="center" style="width:100%; border-spacing:0; align:center;width:55%;" class="GeneralInfo">
				<tr><td height="4"></td></tr>
				<tr>
					<td width="8%" class="Libelle">
						&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Thème";}else{echo "Theme";}?> :
					</td>
					<td width="12%">
						<select id="theme" name="theme" onchange="submit();">
							<option value="0"></option>
						<?php
						$resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$IdPersonneConnectee);
						$nbAccess=mysqli_num_rows($resAcc);
						
						$resAccSuperAdmin=mysqli_query($bdd,"SELECT Id FROM soda_superadministrateur WHERE Id_Personne=".$IdPersonneConnectee);
						$nbSuperAdmin=mysqli_num_rows($resAccSuperAdmin);
	
						$req = "SELECT Id, Libelle
								FROM soda_theme
								WHERE Suppr=0 ";
						if($nbAccess==0 && $nbSuperAdmin==0){
							$req.="AND Id IN (SELECT Id FROM soda_theme WHERE Suppr=0 AND (Id_Gestionnaire=".$IdPersonneConnectee." OR Id_Backup1=".$IdPersonneConnectee." OR Id_Backup2=".$IdPersonneConnectee." OR Id_Backup3=".$IdPersonneConnectee.")) ";
						}
						$req.="ORDER BY Libelle;";
						
						$resultTheme=mysqli_query($bdd,$req);
						$nbTheme=mysqli_num_rows($resultTheme);
						
						$theme=$_SESSION['FiltreSODA_Theme'];
						if($_POST){$theme=$_POST['theme'];}
						$_SESSION['FiltreSODA_Theme']=$theme;
						if ($nbTheme > 0)
						{
							while($row=mysqli_fetch_array($resultTheme))
							{
								if ($row['Id'] == $_SESSION['FiltreSODA_Theme']){$Selected = "Selected";}
								echo "<option value='".$row['Id']."' ".$Selected.">".$row['Libelle']."</option>";
								$Selected = "";
							}
						 }
						 ?>
						</select>
					</td>
					<td width="8%" class="Libelle">
						&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Générique/Spécifique";}else{echo "Generic/Specific";}?> :
					</td>
					<td width="10%">
						<select id="specifique" name="specifique" onchange="submit();">
							
						<?php
						$specifique=$_SESSION['FiltreSODA_Specifique'];
						if($_POST){$specifique=$_POST['specifique'];}
						$_SESSION['FiltreSODA_Specifique']=$specifique;

						 ?>
						 <option value="-1" <?php if($specifique==-1){echo "selected";}?>></option>
						<option value="0" <?php if($specifique==0){echo "selected";}?>><?php if($_SESSION["Langue"]=="FR"){echo "Générique";}else{echo "Generic";}?></option>
						<option value="1" <?php if($specifique==1){echo "selected";}?>><?php if($_SESSION["Langue"]=="FR"){echo "Spécifique";}else{echo "Specific";}?></option>
						</select>
					</td>
					<td width="8%" class="Libelle">
						&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "A/I";}else{echo "A/I";}?> :
					</td>
					<td width="30%">
						<select id="actif" name="actif" onchange="submit();">
							
						<?php
						$actif=$_SESSION['FiltreSODA_Actif'];
						if($_POST){$actif=$_POST['actif'];}
						$_SESSION['FiltreSODA_Actif']=$actif;

						 ?>
						 <option value="-1" <?php if($actif==-1){echo "selected";}?>></option>
						<option value="0" <?php if($actif==0){echo "selected";}?>>Actif</option>
						<option value="1" <?php if($actif==1){echo "selected";}?>>Inactif</option>
						</select>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td align="center" colspan="8">
			<a style='text-decoration:none;' class='Bouton' href="javascript:OuvreFenetreModif('Ajout_Questionnaire.php','Ajout','0')">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add questionnaire";}else{echo "Ajouter un questionnaire";} ?>&nbsp;</a>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr>
		<td>
			<table align="center" class="TableCompetences" style="width:55%;">
				<tr>
					<td width="20%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Thème";}else{echo "Theme";}?></td>
					<td width="35%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Questionnaire";}else{echo "Questionnaire";}?></td>
					<td width="10%" class="EnTeteTableauCompetences"><?php if($_SESSION["Langue"]=="FR"){echo "Générique/Spécifique";}else{echo "Generic/Specific";}?></td>
					<td width="5%" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "A/I";}else{echo "A/I";}?></td>
					<td width="2%"></td>
					<td width="2%"></td>
				</tr>
			<?php
				$req="SELECT Id,Libelle,Actif,
					(SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) AS Theme,
					Specifique
					FROM soda_questionnaire 
					WHERE Suppr=0 ";
				if($nbAccess==0 && $nbSuperAdmin==0){
					$req.="AND Id_Theme IN (SELECT Id FROM soda_theme WHERE Suppr=0 AND (Id_Gestionnaire=".$IdPersonneConnectee." OR Id_Backup1=".$IdPersonneConnectee." OR Id_Backup2=".$IdPersonneConnectee." OR Id_Backup3=".$IdPersonneConnectee.")) ";
				}
				if($_SESSION['FiltreSODA_Theme']<>0){
					$req.="AND Id_Theme=".$_SESSION['FiltreSODA_Theme']." ";
				}
				if($_SESSION['FiltreSODA_Actif']<>-1){
					$req.="AND Actif=".$_SESSION['FiltreSODA_Actif']." ";
				}
				if($_SESSION['FiltreSODA_Specifique']<>-1){
					$req.="AND Specifique=".$_SESSION['FiltreSODA_Specifique']." ";
				}
				$req.="ORDER BY Actif,Theme,Libelle ";
				$result=mysqli_query($bdd,$req);
				$nbenreg=mysqli_num_rows($result);
				if($nbenreg>0)
				{
					$Couleur="#EEEEEE";
					while($row=mysqli_fetch_array($result))
					{
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
						$actif="A";
						if($row['Actif']==1){$actif="I";}
						
						if($_SESSION["Langue"]=="FR"){$specifique= "Générique";}else{$specifique= "Generic";}
						if($row['Specifique']==1){
							if($_SESSION["Langue"]=="FR"){$specifique= "Spécifique";}else{$specifique= "Specific";}
						}

			?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td><?php echo $row['Theme'];?></td>
					<td><?php echo $row['Libelle'];?></td>
					<td><?php echo $specifique;?></td>
					<td><?php echo $actif; ?></td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout_Questionnaire.php','Modif','<?php echo $row['Id']; ?>');">
							<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
						</a>
					</td>
					<td>
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout_Questionnaire.php','Suppr','<?php echo $row['Id']; ?>');">
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
</body>
</html>
<?php 
	if($_GET){
		$Menu=$_GET['Menu'];
	}
	else{
		$Menu=$_POST['Menu'];
	}
?>
<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<input type="hidden" id="Menu" name="Menu" value="<?php echo $Menu; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr><td height="10"></td></tr>
	<tr><td width="100%" colspan="3">
		<table width="100%" cellpadding="0" cellspacing="0" align="center" class="TableCompetences">
			<tr><td height="4"></td></tr>
			<tr>
				<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Thème : ";}else{echo "Theme  : ";}?></td>
				<td width="15%" class="Libelle">
					<?php
						$theme=$_SESSION['FiltreOnboarding_Theme'];
						if($_POST){
							if(isset($_POST['theme'])){
								$theme=$_POST['theme'];
							}
						}
						$_SESSION['FiltreOnboarding_Theme']=$theme;
					 ?>
					<select id="theme" name="theme" onchange="submit()">
						<option value=""></option>
						<?php 
							$req="SELECT Id FROM onboarding_superadministrateur WHERE Id_Personne=".$_SESSION['Id_Personne']." ";
							$resultAdm=mysqli_query($bdd,$req);
							$nbAdm=mysqli_num_rows($resultAdm);
							if($nbAdm>0){
								$tabTheme = array("Achats","Bienvenue chez AAA","Excellence opérationnelle","Formation interne","Informatique","Innovation","Qualité","Ressources humaines","Sécurité et environnement","Vie quotidienne");
								
								foreach($tabTheme as $letheme){
									echo "<option value='".$letheme."'";
									if($letheme==$theme){echo "selected";}
									echo ">".$letheme."</option>";
								} 
							}
							else{
								$req="SELECT Rubrique FROM onboarding_administrateur WHERE Id_Personne=".$_SESSION['Id_Personne']." ORDER BY Rubrique ";
								$resultAdm=mysqli_query($bdd,$req);
								$nbAdm=mysqli_num_rows($resultAdm);
								if($nbAdm>0){
									while($row=mysqli_fetch_array($resultAdm)){
										$selected="";
										if($row['Rubrique']==$theme){echo "selected";}
										echo "<option value='".$row['Rubrique']."' ".$selected.">".stripslashes($row['Rubrique'])."</option>";
									}
								}
							}
						?>
					</select>
				</td>
				<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Mot clé : ";}else{echo "Keyword  : ";}?></td>
				<td width="35%" class="Libelle">
					<?php
						$motcle=$_SESSION['FiltreOnboarding_MotsCles'];
						if($_POST){
							if(isset($_POST['motcle'])){
								$motcle=$_POST['motcle'];
							}
						}
						$_SESSION['FiltreOnboarding_MotsCles']=$motcle;
					 ?>
					<input id="motcle" name="motcle" value="<?php echo $motcle;?>" />
					</select>
				</td>
				<td width="5%">
					<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
					<div id="filtrer"></div>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td align="center" colspan="8">
			<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout("Ajout_Rubrique.php")'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add a section";}else{echo "Ajouter une rubique";} ?>&nbsp;</a>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr><td colspan="8">
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:98%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="12%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Theme";}else{echo "Thème";} ?></td>
				<td class="EnTeteTableauCompetences" width="20%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Rubric";}else{echo "Rubrique";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Document";}else{echo "Document";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Image";}else{echo "Image";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Description";}else{echo "Description";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "UER";}else{echo "UER";} ?></td>
				<td class="EnTeteTableauCompetences" width="5%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Creation Date";}else{echo "Date création";} ?></td>
				<td class="EnTeteTableauCompetences" width="9%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Creator";}else{echo "Créateur";} ?></td>
				<td class="EnTeteTableauCompetences" width="9%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "State";}else{echo "Etat";} ?></td>
				<td class="EnTeteTableauCompetences" width="9%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Number of views";}else{echo "Nbr de vue";} ?></td>
				<td class="EnTeteTableauCompetences" width="9%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Visible only to employees";}else{echo "Visible uniquement aux salariés";} ?></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
			</tr>
			<?php
				$req="SELECT Id,
					Rubrique,
					Libelle,Document,DateCreation,Valide,TypeDocument,Image,Description,VisibleUniquementSalarie,
					(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS UER,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Createur) AS Createur,
					(SELECT COUNT(Id) FROM onboarding_contenu_lu WHERE onboarding_contenu.Id=Id_Contenu) AS NbVue
					FROM onboarding_contenu
					WHERE Suppr=0
					AND (
						(SELECT COUNT(Id_Personne) FROM onboarding_superadministrateur WHERE Id_Personne=".$_SESSION['Id_Personne'].")>0
						OR 	
						(SELECT COUNT(Id_Personne) FROM onboarding_administrateur WHERE onboarding_administrateur.Rubrique=onboarding_contenu.Rubrique AND Id_Personne=".$_SESSION['Id_Personne'].")>0
					) ";
				if($theme<>""){
					$req.=" AND Rubrique='".$theme."'";
				}
				if($motcle<>""){
					$req.=" AND (Libelle LIKE '%".$motcle."%' OR Description LIKE '%".$motcle."%') ";
				}
				$req.=" ORDER BY DateCreation DESC;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						?>
						<tr bgcolor="<?php echo $couleur;?>">
							<td><?php echo $row['Rubrique'];?></td>
							<td><?php echo $row['Libelle'];?></td>
							<td>
								<?php
									if($row['Document']<>""){
										if($row['TypeDocument']=="A télécharger"){
											echo "<a class=\"Info\" href=\"".$DirFichier.$row['Document']."\" onclick=\"window.open('".$DirFichier.$row['Document']."');\" target=\"_blank\">";
											echo "<img width='20px' src='../../Images/Telechargement2.png' border='0'>";
											echo "</a>";
										}
										else{
											echo "<a class=\"Info\" href=\"".$DirFichier.$row['Document']."\" onclick=\"window.open('".$DirFichier.$row['Document']."');\" target=\"_blank\">";
											echo "<img width='20px' src='../../Images/Video.png' border='0'>";
											echo "</a>";
										}
									}
								?>
							</td>
							<td>
								<?php
									if($row['Image']<>""){
										echo "<img width='50px' src='".$DirFichier.$row['Image']."' border='0'>";
									}
								?>
							</td>
							<td><?php 
								if($row['Description']<>""){
									echo substr($row['Description'],0,20);
									echo "...";
								}
							?></td>
							<td><?php echo $row['UER'];?></td>
							<td><?php echo AfficheDateJJ_MM_AAAA($row['DateCreation']);?></td>
							<td><?php echo $row['Createur'];?></td>
							<td>
								<?php
									if($nbAdm>0 || $row['Valide']==0 || $row['Valide']==2 || $row['Valide']==-1){
								?>
								<select id="etat_<?php echo $row['Id']; ?>" name="etat_<?php echo $row['Id']; ?>" onchange="OuvreFenetreModifRubrique('Ajout_Rubrique.php','<?php echo $row['Id']; ?>')">
									<option value="0" <?php if($row['Valide']==0){echo "selected";}?> >En attente validation</option>
									<option value="2" <?php if($row['Valide']==2){echo "selected";}?> >Test affichage</option>
									<?php
										if($nbAdm>0){
									?>
									<option value="1" <?php if($row['Valide']==1){echo "selected";}?> >Validé</option>
									<?php 
										}
									?>
									<option value="-1" <?php if($row['Valide']==-1){echo "selected";}?>>Ne pas afficher</option>
								</select>
								<?php 
									}
									else{
										if($row['Valide']==0){echo "En attente validation";}
										elseif($row['Valide']==0){echo "En attente validation";}
										elseif($row['Valide']==1){echo "Validé";}
										elseif($row['Valide']==2){echo "Test affichage";}
										elseif($row['Valide']==-1){echo "Ne pas afficher";}
									}
								?>
							</td>
							<td><?php echo $row['NbVue'];?></td>
							<td>
								<?php 
								if($row['VisibleUniquementSalarie']==1){echo "Oui";}
								else{echo "Non";}
								?>
							</td>
							<td>
								<?php
									if($nbAdm>0 || $row['Valide']==0 || $row['Valide']==2 || $row['Valide']==-1){
								?>
								<a class="Modif" href="javascript:OuvreFenetreModif('Ajout_Rubrique.php','M','<?php echo $row['Id']; ?>');">
									<img src='../../Images/Modif.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>'>
								</a>
								<?php 
									}
								?>
							</td>
							<td>
								<?php
									if($nbAdm>0 || $row['Valide']==0 || $row['Valide']==2 || $row['Valide']==-1){
								?>
								<a href="javascript:OuvreFenetreSuppr('Ajout_Rubrique.php',<?php echo $row['Id']; ?>)">
									<img src='../../Images/Suppression.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>'>
								</a>
								<?php 
									}
								?>
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
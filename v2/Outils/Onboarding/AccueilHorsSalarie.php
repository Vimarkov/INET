<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<input type="hidden" id="Menu" name="Menu" value="<?php echo $Menu; ?>">
<table style="width:100%; border-spacing:0; align:center;background-color:#ffffff;">
	<tr><td>
		<div class="conteneurTDB">
			<?php
				$DirFichier=$CheminOnBoarding;

				$req="SELECT Id,Libelle,Document,TypeDocument,Id_Plateforme,DateCreation,Description,Image,Rubrique,
					(SELECT COUNT(Id) FROM onboarding_contenu_lu WHERE onboarding_contenu.Id=onboarding_contenu_lu.Id_Contenu AND onboarding_contenu_lu.Id_Personne=".$_SESSION['Id_Personne'].") AS Lu,
					(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS UER
					FROM onboarding_contenu
					WHERE Suppr=0
					AND Valide>=1 
					AND VisibleUniquementSalarie=0 
					ORDER BY DateCreation DESC ";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					while($row=mysqli_fetch_array($result)){
					?>
					<div class="BlocThemeTDB">
						<div class="ImageTDBActu">
							<?php 
								if($row['Image']<>""){
									if($row['Libelle']=="Enquête Formation Interne"){
										echo "<a class=\"Info\" href=\"https://docs.google.com/forms/d/e/1FAIpQLSfaa2_-f1hmn2MOdmmaxSQWx6HQGeIOFrin8BcgBI42_8hBVw/viewform\" target=\"_blank\">";
									}
									elseif($row['Libelle']=="HSE – Journée mondiale de la sécurité et de la santé au travail 2023"){
										echo "<a class=\"Info\" href=\"".$DirFichier.$row['Document']."\" onclick=\"window.open('".$DirFichier.$row['Document']."');\" target=\"_blank\">";
									}
									elseif($row['Libelle']=="Conférence sur l'intelligence artificielle 15/06/2023"){
										echo "<a class=\"Info\" href=\"https://docs.google.com/forms/d/e/1FAIpQLSdmaWerCRGz6zqzexySduiDLYr-4QG8VRbZfhMNgYUUrVM8xA/viewform\" onclick=\"mettreEnVu(".$row['Id'].")\" target=\"_blank\">";
									}
							?>
							<img class="imageAccueil" src="<?php echo $DirFichier.$row['Image'];?>" />
							<?php 
								if($row['Libelle']=="Enquête Formation Interne"
								|| $row['Libelle']=="HSE – Journée mondiale de la sécurité et de la santé au travail 2023"
								|| $row['Libelle']=="Conférence sur l'intelligence artificielle 15/06/2023"
								){
									echo "</a>";
								}
							}?>
							<div class="GrandTitreActu"><?php echo stripslashes($row['Rubrique']);?></div>
						</div>
						<div class="DateTDB">
							<?php
							echo AfficheDateJJ_MM_AAAA($row['DateCreation']);
							?>
						</div>
						<div class="blocUER">
							<?php
							if($row['Id_Plateforme']>0){
									echo "&nbsp;";
									echo "<span class='baliseUER'>";
									echo stripslashes($row['UER']);
									echo "</span>";
								}
							?>
						</div>
						<div class="TitreTDB"><?php echo stripslashes($row['Libelle']); ?></div>
						<div class="TexteTDB">
						<?php 
							echo nl2br(stripslashes($row['Description']));
						?>
						</div>
						<div class="ATelechargerTDB">
						<?php 
							if($row['Id']==32){
								echo "<a class=\"Info\" href=\"".$DirFichier."Présentation cadres-20230509_100717-Enregistrement de la réunion.mp4\" target=\"_blank\">";
								if($_SESSION['Langue']=="FR"){echo "Lire la vidéo : Cadres";}else{echo "Play the video : Executives";}
								echo "</a>";
								echo "<br>";
								echo "<a class=\"Info\" href=\"".$DirFichier."Présentation non-cadre-20230510_110809-Enregistrement de la réunion.mp4\" target=\"_blank\">";
								if($_SESSION['Langue']=="FR"){echo "Lire la vidéo : Non-cadres";}else{echo "Play the video : Non-executives";}
								echo "</a>";
							}
							elseif($row['Document']<>""){
								echo "<a class=\"Info\" href=\"".$DirFichier.$row['Document']."\" onclick=\"window.open('".$DirFichier.$row['Document']."');\" target=\"_blank\">";
								if($row['TypeDocument']=="A télécharger"){
									if($_SESSION['Langue']=="FR"){echo "Lire la suite";}else{echo "Read more";}
								}
								else{
									if($_SESSION['Langue']=="FR"){echo "Lire la vidéo";}else{echo "Play the video";}
								}
								echo "</a>";
							}
						?>
						</div>
					</div>
					<?php } ?>
					<div class="blocTheme"></div>
				<?php
				}
			?>
			
			
		</div>
	</td></tr>
</table>
</body>
</html>
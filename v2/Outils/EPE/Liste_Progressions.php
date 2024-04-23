<?php
require("../../Menu.php");
?>
<form id="formulaire" class="test" enctype="multipart/form-data" action="Liste_Progressions.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#f5f74b;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Progressions salariale et professionnelles";}else{echo "Salary and professional progressions";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td width="1000%" valign="top">
			<table width="100%">
				<tr>
					<td width="100%">
						<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
							<tr>
								<td height="5"></td>
							</tr>
							<tr>
								<td width="20%" class="Libelle">
									&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?>
									<select style="width:100px;" name="plateforme" onchange="submit();">
									<?php
									$requetePlateforme="
										SELECT Id, Libelle
										FROM new_competences_plateforme
										WHERE Id IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
										ORDER BY Libelle ASC";
									$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
									$nbPlateforme=mysqli_num_rows($resultPlateforme);
									
									$Plateforme=$_SESSION['FiltreEPEProgression_Plateforme'];
									if($_POST){$Plateforme=$_POST['plateforme'];}
									$_SESSION['FiltreEPEProgression_Plateforme']=$Plateforme;	
									
									echo "<option name='0' value='0' Selected></option>";
									if ($nbPlateforme > 0)
									{
										while($row=mysqli_fetch_array($resultPlateforme))
										{
											$selected="";
											if($Plateforme<>""){if($Plateforme==$row['Id']){$selected="selected";}}
											echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
										}
									 }
									 ?>
									</select>
								</td>
								<td width="12%" class="Libelle">
									&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?>
									<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
									<?php
									$requeteSite="
										SELECT Id, Libelle
										FROM new_competences_prestation
										WHERE Active=0
										AND Id_Plateforme=".$Plateforme."
										ORDER BY Libelle ASC";
									$resultPrestation=mysqli_query($bdd,$requeteSite);
									$nbPrestation=mysqli_num_rows($resultPrestation);
									
									$Prestation=$_SESSION['FiltreEPEProgression_Prestation'];
									if($_POST){$Prestation=$_POST['prestations'];}
									$_SESSION['FiltreEPEProgression_Prestation']=$Prestation;	
									
									echo "<option name='0' value='0' Selected></option>";
									if ($nbPrestation > 0)
									{
										while($row=mysqli_fetch_array($resultPrestation))
										{
											$selected="";
											if($Prestation<>""){if($Prestation==$row['Id']){$selected="selected";}}
											echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
										}
									 }
									 ?>
									</select>
								</td>
								<td width="15%" class="Libelle">
									&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?>
									<select class="pole" style="width:100px;" name="pole" onchange="submit();">
									<?php
									$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
										FROM new_competences_pole
										LEFT JOIN new_competences_prestation
										ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
										WHERE Actif=0
										AND new_competences_pole.Id_Prestation=".$Prestation."
										ORDER BY new_competences_pole.Libelle ASC";
									$resultPole=mysqli_query($bdd,$requetePole);
									$nbPole=mysqli_num_rows($resultPole);
									
									$Pole=$_SESSION['FiltreEPEProgression_Pole'];
									if($_POST){$Pole=$_POST['pole'];}
									$_SESSION['FiltreEPEProgression_Pole']=$Pole;
									
									$Selected = "";
									echo "<option name='0' value='0' Selected></option>";
									if ($nbPole > 0)
									{
										while($row=mysqli_fetch_array($resultPole))
										{
											$selected="";
											if($Pole<>"")
											{if($Pole==$row['Id']){$selected="selected";}}
											echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
										}
									 }
									 ?>
									</select>
								</td>
								<?php
								$personne=$_SESSION['FiltreEPEProgression_Personne'];
								if($_POST){$personne=$_POST['personne'];}
								$_SESSION['FiltreEPEProgression_Personne']=$personne;
								?>
								<td valign="top" width="15%" class="Libelle">
									&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?>
									<select id="personne" style="width:100px;" name="personne" onchange="submit();">
										<option value='0'></option>
										<?php

											$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
													CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
													FROM new_rh_etatcivil
														LEFT JOIN epe_personne_evolution 
														ON new_rh_etatcivil.Id=epe_personne_evolution.Id_Personne 
														WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
														OR 
															(SELECT COUNT(Id)
															FROM epe_personne 
															WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEProgression_Annee'].")>0
														)  
														AND new_rh_etatcivil.Id<>1739						
														AND (SELECT COUNT(Id_Plateforme) FROM new_competences_personne_plateforme
															WHERE new_rh_etatcivil.Id=Id_Personne AND Id_Plateforme NOT IN (11,14)
															)>0  ";
													if($_SESSION['FiltreEPEProgression_Annee']<>""){
														$requetePersonne.="AND epe_personne_evolution.Annee = ".$_SESSION['FiltreEPEProgression_Annee']." ";
													}
													$requetePersonne.="
															AND (
															(
																SELECT COUNT(new_competences_personne_prestation.Id)
																FROM new_competences_personne_prestation
																LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
																WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
																AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
																AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
																AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
																AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)";
																if($_SESSION['FiltreEPEProgression_Plateforme']<>"0"){$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) = ".$_SESSION['FiltreEPEProgression_Plateforme']." ";}
																if($_SESSION['FiltreEPEProgression_Prestation']<>"0"){$requete.="AND new_competences_personne_prestation.Id_Prestation = ".$_SESSION['FiltreEPEProgression_Prestation']." ";}
																if($_SESSION['FiltreEPEProgression_Pole']<>"0"){$requete.="AND new_competences_personne_prestation.Id_Pole = ".$_SESSION['FiltreEPEProgression_Pole']." ";}
												$requetePersonne.="
															)>0) ";
											$requetePersonne.="ORDER BY Personne ASC";
											$resultPersonne=mysqli_query($bdd,$requetePersonne);
											$NbPersonne=mysqli_num_rows($resultPersonne);
											
											$personne=$_SESSION['FiltreEPEProgression_Personne'];
											if($_POST){$personne=$_POST['personne'];}
											$_SESSION['FiltreEPEProgression_Personne']= $personne;
											
											while($rowPersonne=mysqli_fetch_array($resultPersonne))
											{
												echo "<option value='".$rowPersonne['Id']."'";
												if ($personne == $rowPersonne['Id']){echo " selected ";}
												echo ">".$rowPersonne['Personne']."</option>\n";
											}
										?>
									</select>
								</td>
								<td width="5%">
									<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
									<div id="filtrer"></div>
								</td>
							</tr>
							<tr>
								<td height="5"></td>
							</tr>
							<tr>
								<td width="20%" class="Libelle">
									&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Type :";}else{echo "Type :";} ?>
									<select class="type" name="type" onchange="submit();">
									<?php
										
										$type=$_SESSION['FiltreEPEProgression_Type'];
										if($_POST){$type=$_POST['type'];}
										$_SESSION['FiltreEPEProgression_Type']= $type;
									?>
										<option value="" <?php if($type==""){echo "selected";} ?>></option>
										<option value="AG" <?php if($type=="AG"){echo "selected";} ?>>AG</option>
										<option value="AI" <?php if($type=="AI"){echo "selected";} ?>>AI</option>
										<option value="Classification" <?php if($type=="Classification"){echo "selected";} ?>>Classification</option>
										<option value="Métier" <?php if($type=="Métier"){echo "selected";} ?>>Métier</option>
									</select>
								</td>
								<?php
								$annee=$_SESSION['FiltreEPEProgression_Annee'];
								if($_POST){$annee=$_POST['annee'];}
								$_SESSION['FiltreEPEProgression_Annee']=$annee;
								?>
								<td width="10%" class="Libelle">
									&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Année :";}else{echo "Year :";} ?>
									<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
								</td>
							</tr>
							<tr>
								<td height="5"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<?php

					$requeteAnalyse="SELECT DISTINCT new_rh_etatcivil.Id,epe_personne_evolution.Id ";
					$requete2="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,DateAncienneteCDI,
					epe_personne_evolution.Annee,epe_personne_evolution.Type,epe_personne_evolution.Valeur ";
					$requete="FROM new_rh_etatcivil
						LEFT JOIN epe_personne_evolution 
						ON new_rh_etatcivil.Id=epe_personne_evolution.Id_Personne 
						WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
						OR 
							(SELECT COUNT(Id)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEProgression_Annee'].")>0
						) 
						AND new_rh_etatcivil.Id<>1739						
						AND (SELECT COUNT(Id_Plateforme) FROM new_competences_personne_plateforme
					WHERE new_rh_etatcivil.Id=Id_Personne AND Id_Plateforme NOT IN (11,14))>0 
						";
					if($_SESSION['FiltreEPEProgression_Annee']<>""){
						$requete.="AND epe_personne_evolution.Annee = ".$_SESSION['FiltreEPEProgression_Annee']." ";
					}
					//Vérifier si appartient à une prestation OPTEA ou compétence
					$requete.="
						AND (
						(
							SELECT COUNT(new_competences_personne_prestation.Id)
							FROM new_competences_personne_prestation
							LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
							WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
							AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
							AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
							AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
							AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)";
							if($_SESSION['FiltreEPEProgression_Plateforme']<>"0"){$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) = ".$_SESSION['FiltreEPEProgression_Plateforme']." ";}
							if($_SESSION['FiltreEPEProgression_Prestation']<>"0"){$requete.="AND new_competences_personne_prestation.Id_Prestation = ".$_SESSION['FiltreEPEProgression_Prestation']." ";}
							if($_SESSION['FiltreEPEProgression_Pole']<>"0"){$requete.="AND new_competences_personne_prestation.Id_Pole = ".$_SESSION['FiltreEPEProgression_Pole']." ";}
			$requete.="
						)>0) ";
					
					if($_SESSION['FiltreEPEProgression_Personne']<>"0"){
						$requete.="AND new_rh_etatcivil.Id =".$_SESSION['FiltreEPEProgression_Personne']." ";
					}
					if($_SESSION['FiltreEPEProgression_Type']<>""){
						$requete.="AND epe_personne_evolution.Type ='".$_SESSION['FiltreEPEProgression_Type']."' ";
					}

					$result=mysqli_query($bdd,$requeteAnalyse.$requete);
					$nbResulta=mysqli_num_rows($result);
					$requete.="ORDER BY Personne, Annee, Type ";
					
					if(isset($_GET['Page'])){$page=$_GET['Page'];}
					else{$page=0;}
					$requete3=" LIMIT ".($page*40).",40";
					
					$result=mysqli_query($bdd,$requete2.$requete.$requete3);
					$nombreDePages=ceil($nbResulta/40);
					$couleur="#FFFFFF";

					?>
					<tr>
						<td align="center" style="font-size:14px;">
							<?php
								$nbPage=0;
								if($page>1){echo "<b> <a style='color:#00599f;' href='Liste_Progressions.php?debut=1&Page=0'><<</a> </b>";}
								$valeurDepart=1;
								if($page<=5){
									$valeurDepart=1;
								}
								elseif($page>=($nombreDePages-6)){
									$valeurDepart=$nombreDePages-6;
								}
								else{
									$valeurDepart=$page-5;
								}
								for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
									if($i<=$nombreDePages){
										if($i==($page+1)){
											echo "<b> [ ".$i." ] </b>"; 
										}	
										else{
											echo "<b> <a style='color:#00599f;' href='Liste_Progressions.php?debut=1&Page=".($i-1)."'>".$i."</a> </b>";
										}
									}
								}
								if($page<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_Progressions.php?debut=1&Page=".($nombreDePages-1)."'>>></a> </b>";}
							?>
						</td>
					</tr>
					<tr>
						<td>
							<table class="TableCompetences" align="center" width="80%">
								<tr>
									<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule";}else{echo "Registration number";} ?></td>
									<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "People";} ?></td>
									<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Date d'embauche";}else{echo "Hiring date";} ?></td>
									<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Année";}else{echo "Year";} ?></td>
									<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Type";}else{echo "Type";} ?></td>
									<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Valeur";}else{echo "Value";} ?></td>
								</tr>
					<?php			
							if($nbResulta>0){
								while($row=mysqli_fetch_array($result))
								{
									if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
									else{$couleur="#FFFFFF";}
								?>
									<tr bgcolor="<?php echo $couleur;?>">
										<td><?php echo stripslashes($row['MatriculeAAA']);?></td>
										<td><?php echo stripslashes($row['Personne']);?></td>
										<td><?php echo AfficheDateJJ_MM_AAAA($row['DateAncienneteCDI']);?></td>
										<td><?php echo stripslashes($row['Annee']); ?></td>
										<td><?php echo stripslashes($row['Type']); ?></td>
										<td><?php echo stripslashes($row['Valeur']); ?></td>
									</tr>
								<?php 
								}
							}
								?>
							</table>
						</td>
					</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
</table>
</form>
<?php
mysqli_close($bdd);					// Fermeture de la connexion

?>
	
</body>
</html>
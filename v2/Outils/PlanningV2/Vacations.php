<?php
require("../../Menu.php");
?>
<script>
	function OuvreFenetreModifPlanning(Menu,Id_Personne,DateVacation)
		{var w=window.open("Modif_VacationPersonne.php?Menu="+Menu+"&Id_Personne="+Id_Personne+"&DateVacation="+DateVacation,"PagePlanning","status=no,menubar=no,scrollbars=1,width=1100,height=600");
		w.focus();
		}
	function OuvreFenetrePlanningExport(Id_Prestation,lDate,Id_Pole)
		{window.open("Planning_Export.php?Id_Prestation="+Id_Prestation+"&lDate="+lDate+"&Id_Pole="+Id_Pole,"PagePlanningExport","status=no,menubar=no,scrollbars=1,width=900,height=400");}
</script>
<?php
$Gris="#dddddd";
$Automatique="#3d9538";
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
?>
	
<form action="Vacations.php" method="post">
	<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<tr>
		<td colspan="10">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#ffffff;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Vacations";}else{echo "Vacations";}
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
			<td colspan="10">
				<table style="width:100%; cellpadding:0; cellspacing:0; align:center;" class="GeneralInfo">
					<tr>
						<td width="10%" class="Libelle">
							&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?>
							<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
							<?php
							if($Menu==4){
								if(DroitsFormationPlateforme($TableauIdPostesRH)){
									$requeteSite="SELECT Id, Libelle
										FROM new_competences_prestation
										WHERE Id_Plateforme IN 
											(
												SELECT Id_Plateforme 
												FROM new_competences_personne_poste_plateforme
												WHERE Id_Personne=".$_SESSION['Id_Personne']." 
												AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
											)
										AND Active=0
										ORDER BY Libelle ASC";
								}
							}
							elseif($Menu==3){
								if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
									$requeteSite="SELECT Id, Libelle
										FROM new_competences_prestation
										WHERE Id_Plateforme IN 
											(
												SELECT Id_Plateforme 
												FROM new_competences_personne_poste_plateforme
												WHERE Id_Personne=".$_SESSION['Id_Personne']." 
												AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
											)
										AND Active=0
										ORDER BY Libelle ASC";
								}
								else{
									$requeteSite="SELECT Id, Libelle
										FROM new_competences_prestation
										WHERE Id IN 
											(SELECT Id_Prestation 
											FROM new_competences_personne_poste_prestation 
											WHERE Id_Personne=".$_SESSION["Id_Personne"]."
											AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
											)
										AND Active=0
										ORDER BY Libelle ASC";
									
								}
							}
							elseif($Menu==2){
								$requeteSite="SELECT DISTINCT new_competences_prestation.Id, 
										new_competences_prestation.Libelle
										FROM rh_personne_rapportastreinte
										LEFT JOIN new_competences_prestation
										ON new_competences_prestation.Id=rh_personne_rapportastreinte.Id_Prestation
										WHERE rh_personne_rapportastreinte.Id_Personne=".$_SESSION['Id_Personne']."
										ORDER BY Libelle ASC";
							}
							$resultPrestation=mysqli_query($bdd,$requeteSite);
							$nbPrestation=mysqli_num_rows($resultPrestation);
							
							$PrestationSelect = 0;
							$Selected = "";
							
							$PrestationSelect=$_SESSION['FiltreRHVacation_Prestation'];
							$estDifferent=0;
							if($_POST){
								if($PrestationSelect<>$_POST['prestations']){$estDifferent=1;}
								$PrestationSelect=$_POST['prestations'];
							}
							$_SESSION['FiltreRHVacation_Prestation']=$PrestationSelect;	
							if ($nbPrestation > 0)
							{
								while($row=mysqli_fetch_array($resultPrestation))
								{
									$selected="";
									if($PrestationSelect<>"0")
										{if($PrestationSelect==$row['Id']){$selected="selected";}}
									else{
										$PrestationSelect=$row['Id'];
										$selected="selected";
									}
									echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
								}
							 }
							 else{
								 echo "<option name='0' value='0' Selected></option>";
							 }
							 ?>
							</select>
							<?php 
								$_SESSION['FiltreRHVacation_Prestation']=$PrestationSelect;
							?>
						</td>
						<td width="10%" class="Libelle">
							&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?>
							<select class="pole" style="width:100px;" name="pole" onchange="submit();">
							<?php

							if($Menu==4){
								if(DroitsFormationPlateforme($TableauIdPostesRH)){
									$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
											FROM new_competences_pole
											LEFT JOIN new_competences_prestation
											ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
											WHERE Id_Plateforme IN 
											(
												SELECT Id_Plateforme 
												FROM new_competences_personne_poste_plateforme
												WHERE Id_Personne=".$_SESSION['Id_Personne']." 
												AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
											)
											AND Actif=0
											AND new_competences_pole.Id_Prestation=".$PrestationSelect."
											ORDER BY new_competences_pole.Libelle ASC";
								}
							}
							elseif($Menu==3){
								if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
									$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
											FROM new_competences_pole
											LEFT JOIN new_competences_prestation
											ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
											WHERE Id_Plateforme IN 
											(
												SELECT Id_Plateforme 
												FROM new_competences_personne_poste_plateforme
												WHERE Id_Personne=".$_SESSION['Id_Personne']." 
												AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
											)
											AND Actif=0
											AND new_competences_pole.Id_Prestation=".$PrestationSelect."
											ORDER BY new_competences_pole.Libelle ASC";
								}
								else{
									$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
										FROM new_competences_pole
										LEFT JOIN new_competences_prestation
										ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
										WHERE new_competences_pole.Id IN 
											(SELECT Id_Pole 
											FROM new_competences_personne_poste_prestation 
											WHERE Id_Personne=".$_SESSION["Id_Personne"]."
											AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
											)
										AND Actif=0
										AND new_competences_pole.Id_Prestation=".$PrestationSelect."
										ORDER BY new_competences_pole.Libelle ASC";
								}
							}
							elseif($Menu==2){
								$requetePole="SELECT DISTINCT new_competences_pole.Id, 
										new_competences_pole.Libelle
										FROM rh_personne_rapportastreinte
										LEFT JOIN new_competences_pole
										ON new_competences_pole.Id=rh_personne_rapportastreinte.Id_Pole
										WHERE rh_personne_rapportastreinte.Id_Personne=".$_SESSION['Id_Personne']."
										AND new_competences_pole.Id_Prestation=".$PrestationSelect."
										ORDER BY Libelle ASC";
							}
							$resultPole=mysqli_query($bdd,$requetePole);
							$nbPole=mysqli_num_rows($resultPole);
							
							$PoleSelect=$_SESSION['FiltreRHVacation_Pole'];
							
							if($estDifferent==1){$PoleSelect=0;}
							elseif($_POST){$PoleSelect=$_POST['pole'];}
							$_SESSION['FiltreRHVacation_Pole']=$PoleSelect;
							
							$Selected = "";
							if ($nbPole > 0)
							{
								while($row=mysqli_fetch_array($resultPole))
								{
									$selected="";
									if($PoleSelect<>0){
										if($PoleSelect==$row['Id']){$selected="selected";}
									}
									else{
										$PoleSelect=$row['Id'];
										$selected="selected";
										
									}
								
									echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
								}
							 }
							 else{
								 echo "<option name='0' value='0' Selected></option>";
							 }
							 ?>
							</select>
							<?php 
								$_SESSION['FiltreRHVacation_Pole']=$PoleSelect;
							?>
						</td>
						<td width="10%" class="Libelle" <?php if($Menu==2){echo "style='display:none;'";} ?>>
							&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?>
							<select id="personne" style="width:100px;" name="personne" onchange="submit();">
								<option value='0'></option>
								<?php
								
									$dateDebut=$_SESSION['FiltreRHVacation_DateDebut'];
									$dateDeFin=$_SESSION['FiltreRHVacation_DateFin'];
									$MoisPrecedent=date("Y-m-d",strtotime($dateDebut." - 1 month"));
									$MoisSuivant=date("Y-m-d",strtotime($dateDebut." + 1 month"));
									
									if(isset($_GET['DateDeDebut']))
									{
										$dateDebut=TrsfDate_($_GET['DateDeDebut']);
										$_SESSION['FiltreRHVacation_DateDebut']=$dateDebut;
										
										if($dateDebut>$dateDeFin){
											$dateDeFin=$dateDebut;
											$_SESSION['FiltreRHVacation_DateFin']=$dateDeFin;
										}
										$MoisPrecedent=$dateDebut;
										$MoisSuivant=$dateDeFin;
									}
									elseif(isset($_POST['DateDeDebut']))
									{
										$dateDebut=TrsfDate_($_POST['DateDeDebut']);
										$_SESSION['FiltreRHVacation_DateDebut']=$dateDebut;
										
										if($dateDebut>$dateDeFin){
											$dateDeFin=$dateDebut;
											$_SESSION['FiltreRHVacation_DateFin']=$dateDeFin;
										}
										$MoisPrecedent=$dateDebut;
										$MoisSuivant=$dateDeFin;
									}
									
									if(isset($_POST['DateDeFin']))
									{
										$dateDeFin=TrsfDate_($_POST['DateDeFin']);
										$_SESSION['FiltreRHVacation_DateFin']=$dateDeFin;
										
										if($dateDebut>$dateDeFin){
											$dateDebut=$dateDeFin;
											$_SESSION['FiltreRHVacation_DateDebut']=$dateDebut;
										}
									}
									if(isset($_POST['MoisPrecedent']))
									{
										$dateDebut=date("Y-m-d",strtotime($dateDebut." - 1 month"));
										$dateDeFin=date("Y-m-d",strtotime($dateDeFin." - 1 month"));
										
										$_SESSION['FiltreRHVacation_DateDebut']=$dateDebut;
										$_SESSION['FiltreRHVacation_DateFin']=$dateDeFin;
										
										$MoisPrecedent=$dateDebut;
										$MoisSuivant=$dateDeFin;
										
										$MoisPrecedent=date("Y-m-d",strtotime($MoisPrecedent." - 1 month"));
										$MoisSuivant=date("Y-m-d",strtotime($MoisSuivant." - 1 month"));
									}
									elseif(isset($_POST['MoisSuivant']))
									{
										$dateDebut=date("Y-m-d",strtotime($dateDebut." + 1 month"));
										$dateDeFin=date("Y-m-d",strtotime($dateDeFin." + 1 month"));
										
										$_SESSION['FiltreRHVacation_DateDebut']=$dateDebut;
										$_SESSION['FiltreRHVacation_DateFin']=$dateDeFin;
										
										$MoisPrecedent=date("Y-m-d",strtotime($MoisPrecedent." + 1 month"));
										$MoisSuivant=date("Y-m-d",strtotime($MoisSuivant." + 1 month"));
									}
									
									$requetePersonne = "SELECT DISTINCT new_rh_etatcivil.Id, 
										CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
									FROM new_rh_etatcivil
									LEFT JOIN rh_personne_mouvement 
									ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
									WHERE rh_personne_mouvement.DateDebut<='".$_SESSION['FiltreRHVacation_DateDebut']."'
									AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$_SESSION['FiltreRHVacation_DateFin']."')
									AND rh_personne_mouvement.EtatValidation=1 
									AND rh_personne_mouvement.Id_Prestation=".$PrestationSelect."
									AND rh_personne_mouvement.Id_Pole=".$PoleSelect."
									ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
								
									$resultPersonne=mysqli_query($bdd,$requetePersonne);
									$NbPersonne=mysqli_num_rows($resultPersonne);
									
									$personne=$_SESSION['FiltreRHVacation_Personne'];
									if($_POST){$personne=$_POST['personne'];}
									$_SESSION['FiltreRHVacation_Personne']= $personne;
									
									while($rowPersonne=mysqli_fetch_array($resultPersonne))
									{
										echo "<option value='".$rowPersonne['Id']."'";
										if ($personne == $rowPersonne['Id']){echo " selected ";}
										echo ">".$rowPersonne['Personne']."</option>\n";
									}
								?>
							</select>
						</td>
						<td width="10%" class="Libelle">
							&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date début :";}else{echo "Start date :";} 
							
							?>
							<input type="date" style="text-align:center;" name="DateDeDebut" size="10" value="<?php echo AfficheDateFR($dateDebut); ?>">
						</td>
						<td width="10%" class="Libelle">
							&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date fin :";}else{echo "End date :";} ?>
							<input type="date" style="text-align:center;" name="DateDeFin"  size="10" value="<?php echo AfficheDateFR($dateDeFin); ?>">
							&nbsp;
							<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
							<div id="filtrer"></div>
						</td>
						<td width="10%">
							<input class="Bouton" name="MoisPrecedent" size="10" type="submit" value="<< <?php echo AfficheDateJJ_MM_AAAA($MoisPrecedent); ?>">
							<input class="Bouton" name="MoisSuivant" size="10" type="submit" value="<?php echo AfficheDateJJ_MM_AAAA($MoisSuivant); ?> >>">
						</td>
						<td width="5%">
							<?php
							echo "&nbsp;";
							echo "<a style='text-decoration:none;' href='javascript:OuvreFenetrePlanningExport();'>";
							echo "<img src='../../Images/excel.gif' border='0' alt='Excel' title='Export Excel'>";
							echo "</a>";
							echo "&nbsp;";
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
			<td>
				<table style="margin-bottom:300px;margin-right:270px;" class="GeneralInfo">
					<?php
					$EnTeteMois = "<td ";
					$EnTeteSemaine = "<td ";
					$EnTeteJourSemaine = "";
					$EnTeteJour = "";
					
					$tmpDate=$_SESSION['FiltreRHVacation_DateDebut'];
					$dateFin=$_SESSION['FiltreRHVacation_DateFin'];
					
					$cptMois = 0;
					$cptSemaine = 0;
					$cptJour = 0;
					
					if($_SESSION["Langue"]=="FR"){
						$joursem = array("D", "L", "M", "M", "J", "V", "S");
						$MoisLettre = array("Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Decembre");
					}
					else{
						$joursem = array("M", "T", "W", "T", "F", "S", "S");
						$MoisLettre = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
					}
					
					// GESTION DES ENTETES DU TABLEAU (MOIS, SEMAINE ET JOUR)
					while ($tmpDate <= $dateFin) 
					{
						$tabDate = explode('-', $tmpDate);
						$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
						$jour = date('w', $timestamp);
						$mois = $tabDate[1];
						$semaine = date('W', $timestamp);
						$cptMois++;
						$cptSemaine++;
						
						if ($dateDuJour == $tmpDate){
							$EnTeteJourSemaine .= "<td class='EnTetePlanningJour' >".$joursem[$jour]."</td>";
							$EnTeteJour .= "<td class='EnTetePlanningJour'>".$tabDate[2]."</td>";
						}
						else{
							$EnTeteJourSemaine .= "<td class='EnTetePlanning' >".$joursem[$jour]."</td>";
							$EnTeteJour .= "<td class='EnTetePlanning'>".$tabDate[2]."</td>";
						}
						
						//Jour suivant
						$tabDate = explode('-', $tmpDate);
						$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
						$tmpDate = date("Y-m-d", $timestamp);
						if (date('m', $timestamp) <> $tabDate[1])
						{
							$EnTeteMois .= " class='EnTeteMois' colspan=".$cptMois.">".$MoisLettre[$mois-1]." ".$tabDate[0]."</td><td ";
							$cptMois = 0;
						}
						if (date('W', $timestamp) <> $semaine)
						{
							$EnTeteSemaine .= " class='EnTeteSemaine' colspan=".$cptSemaine.">S".$semaine."</td><td ";
							$cptSemaine = 0;
						}
						$cptJour++;
					}
					if (date('m', $timestamp) == $tabDate[1]){
						$EnTeteMois .= " class='EnTeteMois' colspan=".$cptMois.">".$MoisLettre[$mois-1]." ".$tabDate[0]."</td>";
					}
					else{
						$EnTeteMois =substr($EnTeteMois, 0, -5)."" ;
					}
					
					if ($joursem[$jour]<>"D"){
						$EnTeteSemaine .= " class='EnTeteSemaine' colspan=".$cptSemaine.">S".$semaine."</td>";
					}
					else{
						$EnTeteSemaine =substr($EnTeteSemaine, 0, -4)."" ;
					}
					
					?>
					<tr align="center">
						<td colspan="2" rowspan ="3" align="center" valign="middle">
						</td>
						<?php echo $EnTeteMois ;?>
					</tr>
					<tr align="center">
						<?php echo $EnTeteSemaine ;?>
					</tr>
					<tr align="center">
						<?php echo $EnTeteJourSemaine ;?>
					</tr>
					<tr align="center">
						<td class="EnTeteSemaine" style="font-size:12px;"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
						<td class="EnTeteSemaine" style="font-size:12px;"><?php if($_SESSION["Langue"]=="FR"){echo "Métier";}else{echo "Job";} ?></td>
						<?php echo $EnTeteJour ;?>
					</tr>
					<?php
					// FIN GESTION DES ENTETES DU TABLEAU
					
					//DEBUT CORPS DU TABLEAU
					$tmpDate=$_SESSION['FiltreRHVacation_DateDebut'];
					$dateFin=$_SESSION['FiltreRHVacation_DateFin'];
					
					//Personnes présentes sur cette prestation à ces dates
					$req = "SELECT DISTINCT new_rh_etatcivil.Id, 
								CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
								rh_personne_mouvement.Id_Prestation, 
								rh_personne_mouvement.Id_Pole
							FROM new_rh_etatcivil
							LEFT JOIN rh_personne_mouvement 
							ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
							WHERE rh_personne_mouvement.DateDebut<='".$_SESSION['FiltreRHPlanning_DateFin']."'
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$_SESSION['FiltreRHPlanning_DateDebut']."')
							AND rh_personne_mouvement.EtatValidation=1 
							AND rh_personne_mouvement.Id_Prestation=".$PrestationSelect."
							AND rh_personne_mouvement.Id_Pole=".$PoleSelect."
							ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
					$resultPersonne=mysqli_query($bdd,$req);
					$nbPersonne=mysqli_num_rows($resultPersonne);
					
					if ($nbPersonne > 0){
						$couleurLigne="PersonnePlanning";
						$couleurLigneMetier="MetierPlanning";
						
						while($row=mysqli_fetch_array($resultPersonne)){
							if($couleurLigne=="PersonnePlanning"){$couleurLigne="PersonnePlanning2";$couleurLigneMetier="MetierPlanning2";}
							else{$couleurLigne="PersonnePlanning";$couleurLigneMetier="MetierPlanning";}
						
							//Récupération du métier actuel
							if($_SESSION["Langue"]=="FR"){
								$reqContrat="SELECT Id_Personne,DateDebut,DateFin,
										(SELECT Code FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS CodeMetier,
										(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier
										FROM rh_personne_contrat
										WHERE Suppr=0
										AND DateDebut<='".date('Y-m-d')."'
										AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
										AND TypeDocument IN ('Nouveau','Avenant')
										AND Id_Personne=".$row['Id']."
										ORDER BY Id_Personne, DateDebut DESC";
							}
							else{
								$reqContrat="SELECT Id_Personne,DateDebut,DateFin,
										(SELECT Code FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS CodeMetier,
										(SELECT LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier
										FROM rh_personne_contrat
										WHERE Suppr=0
										AND DateDebut<='".date('Y-m-d')."'
										AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
										AND TypeDocument IN ('Nouveau','Avenant')
										AND Id_Personne=".$row['Id']."
										ORDER BY Id_Personne, DateDebut DESC";
							}
							$resultContrat=mysqli_query($bdd,$reqContrat);
							$nbResultaContrat=mysqli_num_rows($resultContrat);
							
							$resultContrat=mysqli_query($bdd,$reqContrat);
							$nbResultaContrat=mysqli_num_rows($resultContrat);
							
							$Metier="";
							$Code="";
							if($nbResultaContrat>0)
							{
								$rowContat=mysqli_fetch_array($resultContrat);
								$Code=$rowContat['CodeMetier'];
								if($_SESSION["Langue"]=="FR"){
									$Metier="Métier : ".$rowContat['Metier'];
								}
								else{
									$Metier="Job : ".$rowContat['Metier'];
								}
							}
							
							echo "<tr>";
							echo "<td class='".$couleurLigne."'>".$row['Personne']."</td>";
							echo "<td id='leHoverPersonne' class='".$couleurLigneMetier."'>".$Code."<span>".$Metier."</span></td>";
							
							$tmpDate=$_SESSION['FiltreRHVacation_DateDebut'];
							$dateFin=$_SESSION['FiltreRHVacation_DateFin'];
					
							while ($tmpDate <= $dateFin) {
								//Recherche si planning pour ce jour-ci
								$Couleur = "";
								$CelPlanning= "";
								$ClassDiv = "";
								$contenu="";
								$Couleur=TravailCeJourDeSemaine($tmpDate,$row['Id']);
								$leHover="";
								$Divers="";
								
								//Vérifier si la personne appartient à cette prestation ce jour là 
								if(appartientPrestation($tmpDate,$row['Id'],$PrestationSelect,$PoleSelect)==1){
									if ($Couleur == ""){
										$tabDateMois = explode('-', $tmpDate);
										$timestampMois = mktime(0, 0, 0, $tabDateMois[1], $tabDateMois[2], $tabDateMois[0]);
										if(estWE($timestampMois)){
											$Couleur="style='background-color:".$Gris.";cursor:pointer;'";
											$ClassDiv ="class='weekFerie'";
										}
										else{
											$ClassDiv ="class='semaine'";
										}
									}
									else{
										//Vérifier si la personne est en VSD ce jour là
										$Id_Contenu=IdVacationCeJourDeSemaine($tmpDate,$row['Id']);
										if($Id_Contenu==1){
											if($_SESSION["Langue"]=="FR"){$contenu="J";}
											else{$contenu="D";}
										}
										elseif($Id_Contenu==15){
											if($_SESSION["Langue"]=="FR"){$contenu="SDL";}
											else{$contenu="SDL";}
										}
										elseif($Id_Contenu==18){
											if($_SESSION["Langue"]=="FR"){$contenu="SD";}
											else{$contenu="SD";}
										}
										else{
											if($_SESSION["Langue"]=="FR"){$contenu="VSD";}
											else{$contenu="VSD";}
										}
										
										$Couleur="style='background-color:".$Couleur.";cursor:pointer;'";

										$jourFixe=estJour_Fixe($tmpDate,$row['Id']);
										if($jourFixe<>""){
											$Couleur="style='background-color:".$Automatique.";cursor:pointer;'";
											$contenu=$jourFixe;
										}

										//Vérifier si la personne n'a pas une vacation particulière ce jour là 
										$Id_Vacation=VacationPersonne($tmpDate,$row['Id'],$PrestationSelect,$PoleSelect);
										if($Id_Vacation>0){
											$req="SELECT Nom, Couleur FROM rh_vacation WHERE Id=".$Id_Vacation." ";
											$resultVac=mysqli_query($bdd,$req);
											$nbVac=mysqli_num_rows($resultVac);
											if($nbVac>0){
												$rowVac=mysqli_fetch_array($resultVac);
												$Couleur="style='background-color:".$rowVac['Couleur'].";cursor:pointer;'";
												$contenu=$rowVac['Nom'];
											}
											$ClassDiv ="class=''";
											$RH="";
											$ClassComment="";
											if(VacationPersonneEmisParRH($tmpDate,$row['Id'],$PrestationSelect,$PoleSelect)==1){
												if($_SESSION['Langue']=="FR"){$RH ="RH";}
												else{$RH ="HR";}
											}
											//Vérifier si une case divers de remplie
											$Divers=VacationPersonneDivers($tmpDate,$row['Id'],$PrestationSelect,$PoleSelect);
											if($Divers<>""){
												if($RH==""){
													$ClassComment="Comment";
												}
												else{
													if($_SESSION['Langue']=="FR"){$ClassComment ="CommentRH";}
													else{$ClassComment ="CommentHR";}
													$RH="";
												}
												$Divers="<span>".$Divers."</span>";
												$leHover="Id='leHover'";
											}
											$ClassDiv ="class='".$RH." ".$ClassComment."'";
										}
									}
									$onClick="onclick=\"javascript:OuvreFenetreModifPlanning(".$Menu.",".$row['Id'].",'".$tmpDate."')\"";
									
									//Cellule finale
									echo "<td ".$ClassDiv." ".$Couleur." ".$onClick." ".$leHover." align='center'>".$contenu.$Divers."</td>\n";
								}
								else{
									//Cellule finale
									echo "<td id='leHover' class='".$ClassDiv."' align='center'>
											<div class='planning'></div>
											<div class='pointage' style='display:none;'></div>
											<span></span>
										</td>\n";
								}
								//Jour suivant
								$tabDate = explode('-', $tmpDate);
								$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
								$tmpDate = date("Y-m-d", $timestamp);
							}
							echo "</tr>";
						}
					 }
					?>
				</table>
			</td>
		</tr>
	</table>
</form>
</body>
</html>
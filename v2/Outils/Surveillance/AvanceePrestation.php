<?php
require("../../Menu.php");
?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="AvanceePrestation.php">
	<tr>
		<td colspan=3>
			<table class="GeneralPage" width="100%" cellpadding="0" cellspacing="0" style="background-color:#c2465e;">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">
						<?php
						echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Surveillance/Tableau_De_Bord.php'>";
						if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
						else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
						echo "</a>";
						echo "&nbsp;&nbsp;&nbsp;";
						?>
						<?php if($_SESSION["Langue"]=="FR"){echo "Gestion des surveillances # Avancée par prestation";}
						else{echo "Monitoring Management # Activity progression";}?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	
	<tr><td colspan=3>
		<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
			<tr>
				<td colspan="6">
					<b>
						&nbsp;
						<?php
							if($_SESSION['Langue']=="FR"){echo "Critères de recherche :";}
							else{echo "Search options :";}
						?>
					</b>
				</td>
			</tr>
			<tr>
				<td width=10%>
					&nbsp;
					<?php
						if($_SESSION['Langue']=="FR"){echo "Entité :";}
						else{echo "Entity :";}
					?>
				</td>
				<td width=10%>
					<select name="plateforme" onchange="submit();">
					<?php
					$req = "SELECT new_competences_plateforme.Id, new_competences_plateforme.Libelle ";
					$req .= "FROM new_competences_plateforme ";
					$req .= "WHERE Id<> 11 AND Id<>14 ";
					$req .= "ORDER BY new_competences_plateforme.Libelle;";
					
					$resultPlateforme=mysqli_query($bdd,$req);
					$nbPlateforme=mysqli_num_rows($resultPlateforme);
					
					$PlateformeSelect = 0;
					$Selected = "";
					if ($nbPlateforme > 0)
					{
						if (!empty($_POST['plateforme'])){
							echo "<option name='0' value='0' Selected></option>";
							if ($PlateformeSelect == 0){$PlateformeSelect = $_POST['plateforme'];}
							while($row=mysqli_fetch_array($resultPlateforme))
							{
								if ($row[0] == $_POST['plateforme']){
									$Selected = "Selected";
								}
								echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
								$Selected = "";
							}
						}
						else{
							echo "<option name='0' value='0' Selected></option>";
							$PlateformeSelect == 0;
							while($row=mysqli_fetch_array($resultPlateforme))
							{
								echo "<option name='".$row[0]."' value='".$row[0]."'>".$row[1]."</option>";
							}
						}
					 }
					 ?>
					</select>
				</td>
				<td width=8%>
					&nbsp;
					<?php
						if($_SESSION['Langue']=="FR"){echo "Prestation :";}
						else{echo "Activity :";}
					?>
				</td>
				<td width=30%>
					<select class="prestation" name="prestations" style="width:350px">
					<?php
					$req = "SELECT new_competences_prestation.Id, CONCAT(new_competences_prestation.Libelle,' ',IF(Active=0,'[Actif]','[Inactif]')) AS Libelle ";
					$req .= "FROM new_competences_prestation ";
					$req .= "WHERE new_competences_prestation.Id_Plateforme=".$PlateformeSelect." ";
					$req .= "ORDER BY Active DESC, new_competences_prestation.Libelle;";
					
					$resultPrestation=mysqli_query($bdd,$req);
					$nbPrestation=mysqli_num_rows($resultPrestation);
					
					$PrestationSelect = 0;
					$Selected = "";
					if ($nbPrestation > 0)
					{
						if (!empty($_POST['prestations'])){
							echo "<option name='0' value='0' Selected></option>";
							if ($PrestationSelect == 0){$PrestationSelect = $_POST['prestations'];}
							while($row=mysqli_fetch_array($resultPrestation))
							{
								if ($row[0] == $_POST['prestations']){
									$Selected = "Selected";
								}
								echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
								$Selected = "";
							}
						}
						else{
							echo "<option name='0' value='0' Selected></option>";
							$PrestationSelect == 0;
							while($row=mysqli_fetch_array($resultPrestation))
							{
								echo "<option name='".$row[0]."' value='".$row[0]."'>".$row[1]."</option>";
							}
						}
					 }
					 ?>
					</select>
				</td>
				<td width=10%>
					&nbsp; Date :
				</td>
				<td width=15%>
					<?php
						$dateRequete = date("Y-m-d");
						if (!empty($_POST['DateSurveillance'])){
							if  ($_POST['DateSurveillance'] <> ""){
								if ($NavigOk ==1){
									$dateDebut = $_POST['DateSurveillance'];
									$tabDateDebut = explode('-', $dateDebut);
									$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[2], $tabDateDebut[0]);
									$dateEnvoi = $timestampDebut;
									$dateRequete = date("Y-m-d",$timestampDebut);
								}
								else{
									$dateDebut = $_POST['DateSurveillance'];
									$tabDateDebut = explode('/', $dateDebut);
									$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
									$dateEnvoi = $timestampDebut;
									$dateRequete = date("Y-m-d",$timestampDebut);
								}
							}
							else{
								$dateDebut = "";
							}
						}
						else{
							$dateDebut = "";
						}
					?>
					<input type="date" style="text-align:center;" name="DateSurveillance" size="10" value="<?php echo $dateDebut; ?>">
				</td>
				<td align="center" colspan="6"><input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Rechercher";}else{echo "Search";}?>"></td>
			</tr>
			<tr><td height="4"></td></tr>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td width="30%"></td>
		<td width="40%">
			<table align="center" width="100%" class="GeneralInfo">
				<tr>
					<td>
						<?php
							if($_SESSION['Langue']=="FR"){echo "Responsable prestation :";}
							else{echo "Activity manager :";}
						?>
					</td>
					<td>
					<?php
						$req_resp = "SELECT new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
						$req_resp .= "FROM new_rh_etatcivil LEFT JOIN new_competences_personne_poste_prestation ";
						$req_resp .= "ON new_rh_etatcivil.Id = new_competences_personne_poste_prestation.Id_Personne ";
						$req_resp .= "WHERE new_competences_personne_poste_prestation.Id_Prestation = ".$PrestationSelect ;
						$req_resp .= " AND Backup=0 AND new_competences_personne_poste_prestation.Id_Poste=2" ;
						$resultResp=mysqli_query($bdd,$req_resp);
						$nbResp=mysqli_num_rows($resultResp);
						$rowResp=mysqli_fetch_array($resultResp);
						if ($nbResp > 0){echo $rowResp['Nom']." ".$rowResp['Prenom'];}
						
					?>
					</td>
				</tr>
				<tr>
					<td>
						<?php
							if($_SESSION['Langue']=="FR"){echo "Effectif total :";}
							else{echo "Total workforce :";}
						?>
					</td>
					<td>
					<?php
						$req_eff = "SELECT COUNT(Tab.Id_Personne) AS Nb FROM(";
						$req_eff .= "SELECT DISTINCT new_competences_personne_prestation.Id_Personne AS Id_Personne ";
						$req_eff .= "FROM new_competences_personne_prestation ";
						$req_eff .= "WHERE new_competences_personne_prestation.Id_Prestation = ".$PrestationSelect." " ;
						$req_eff .= "AND new_competences_personne_prestation.Date_Debut <='".$dateRequete."' " ;
						$req_eff .= "AND new_competences_personne_prestation.Date_Fin >='".$dateRequete."' " ;
						$req_eff .= ") AS Tab " ;
						$resultEff=mysqli_query($bdd,$req_eff);
						$nbEff=mysqli_num_rows($resultEff);
						$rowEff=mysqli_fetch_array($resultEff);
						$nbEffTotal = 0;
						if ($nbEff > 0){
							echo $rowEff['Nb'];
							$nbEffTotal = $rowEff['Nb'];
						}
						else{
							echo "0";
						}
						
					?>
					</td>
				</tr>
				<tr>
					<td>
						<?php
							if($_SESSION['Langue']=="FR"){echo "Effectif surveillé :";}
							else{echo "Workforced monitored :";}
						?>
					</td>
					<td>
						<?php
							$tabDateDebut = explode('-', $dateRequete);
							$timestampDebut = mktime(0, 0, 0, 1, 1, $tabDateDebut[0]);
							$dateEnvoi = $timestampDebut;
							$dateDebutAnnee = date("Y-m-d",$timestampDebut);
							if ($dateDebut <> ""){
								if ($NavigOk ==1){
									$tabDateDebut = explode('-', $dateDebut);
									$timestampDebut = mktime(0, 0, 0, 1, 1, $tabDateDebut[0]);
									$dateEnvoi = $timestampDebut;
									$dateDebutAnnee = date("Y-m-d",$timestampDebut);
								}
								else{
									$tabDateDebut = explode('/', $dateDebut);
									$timestampDebut = mktime(0, 0, 0, 1, 1, $tabDateDebut[2]);
									$dateEnvoi = $timestampDebut;
									$dateDebutAnnee = date("Y-m-d",$timestampDebut);
								}
							}
							$req_eff = "SELECT COUNT(Tab.ID_Surveille) AS Nb FROM(";
							$req_eff .= "SELECT DISTINCT new_surveillances_surveillance.ID_Surveille AS ID_Surveille ";
							$req_eff .= "FROM new_surveillances_surveillance ";
							$req_eff .= "WHERE new_surveillances_surveillance.ID_Prestation = ".$PrestationSelect." " ;
							$req_eff .= "AND (new_surveillances_surveillance.Etat='Réalisé' OR new_surveillances_surveillance.Etat='Clôturé') " ;
							$req_eff .= "AND IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) >='".$dateDebutAnnee."' " ;
							$req_eff .= "AND IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) <='".$dateRequete."' " ;
							$req_eff .= ") AS Tab " ;
							$resultEff=mysqli_query($bdd,$req_eff);
							$nbEff=mysqli_num_rows($resultEff);
							$rowEff=mysqli_fetch_array($resultEff);
							if ($nbEff > 0){
								echo $rowEff['Nb'];
							}
							else{
								echo "0";
							}
						?>
					</td>
				</tr>
				<?php
					$total=0;
					$nbPlanifie=0;
					$nbReplanifie=0;
					$nbRealise=0;
					$nbCloture=0;
					
					$req_Pla = "SELECT COUNT(Tab.ID_Surveille) AS Nb FROM(";
					$req_Pla .= "SELECT new_surveillances_surveillance.ID_Surveille AS ID_Surveille, ";
					$req_Pla .= "IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS DateSurveillance ";
					$req_Pla .= "FROM new_surveillances_surveillance ";
					$req_Pla .= "WHERE new_surveillances_surveillance.ID_Prestation = ".$PrestationSelect." " ;
					$req_Pla .= "AND new_surveillances_surveillance.Etat='Planifié' " ;
					$req_Pla .= "AND IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) >='".$dateDebutAnnee."' " ;
					$req_Pla .= "AND IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) <='".$dateRequete."' " ;
					$req_Pla .= ") AS Tab " ;
					$resultPla=mysqli_query($bdd,$req_Pla);
					$nbPla=mysqli_num_rows($resultPla);
					$rowPla=mysqli_fetch_array($resultPla);
					if ($nbPla > 0){
						$total += $rowPla['Nb'];
						$nbPlanifie = $rowPla['Nb'];
					}
					
					$req_RePla = "SELECT COUNT(Tab.ID_Surveille) AS Nb FROM(";
					$req_RePla .= "SELECT new_surveillances_surveillance.ID_Surveille AS ID_Surveille, ";
					$req_RePla .= "IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS DateSurveillance ";
					$req_RePla .= "FROM new_surveillances_surveillance ";
					$req_RePla .= "WHERE new_surveillances_surveillance.ID_Prestation = ".$PrestationSelect." " ;
					$req_RePla .= "AND new_surveillances_surveillance.Etat='Replanifié' " ;
					$req_RePla .= "AND IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) >='".$dateDebutAnnee."' " ;
					$req_RePla .= "AND IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) <='".$dateRequete."' " ;
					$req_RePla .= ") AS Tab " ;
					$resultRePla=mysqli_query($bdd,$req_RePla);
					$nbRePla=mysqli_num_rows($resultRePla);
					$rowRePla=mysqli_fetch_array($resultRePla);
					if ($nbRePla > 0){
						$total += $rowRePla['Nb'];
						$nbReplanifie = $rowRePla['Nb'];
					}
					
					$req_Real = "SELECT COUNT(Tab.ID_Surveille) AS Nb FROM(";
					$req_Real .= "SELECT new_surveillances_surveillance.ID_Surveille AS ID_Surveille, ";
					$req_Real .= "IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS DateSurveillance ";
					$req_Real .= "FROM new_surveillances_surveillance ";
					$req_Real .= "WHERE new_surveillances_surveillance.ID_Prestation = ".$PrestationSelect." " ;
					$req_Real .= "AND new_surveillances_surveillance.Etat='Réalisé' " ;
					$req_Real .= "AND IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) >='".$dateDebutAnnee."' " ;
					$req_Real .= "AND IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) <='".$dateRequete."' " ;
					$req_Real .= ") AS Tab " ;
					$resultReal=mysqli_query($bdd,$req_Real);
					$nbReal=mysqli_num_rows($resultReal);
					$rowReal=mysqli_fetch_array($resultReal);
					if ($nbReal > 0){
						$total += $rowReal['Nb'];
						$nbRealise = $rowReal['Nb'];
					}
					
					$req_Clo = "SELECT COUNT(Tab.ID_Surveille) AS Nb FROM(";
					$req_Clo .= "SELECT new_surveillances_surveillance.ID_Surveille AS ID_Surveille, ";
					$req_Clo .= "IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS DateSurveillance ";
					$req_Clo .= "FROM new_surveillances_surveillance ";
					$req_Clo .= "WHERE new_surveillances_surveillance.ID_Prestation = ".$PrestationSelect." " ;
					$req_Clo .= "AND new_surveillances_surveillance.Etat='Clôturé' " ;
					$req_Clo .= "AND IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) >='".$dateDebutAnnee."' " ;
					$req_Clo .= "AND IF(new_surveillances_surveillance.DateReplanif >'0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) <='".$dateRequete."' " ;
					$req_Clo .= ") AS Tab " ;
					$resultClo=mysqli_query($bdd,$req_Clo);
					$nbClo=mysqli_num_rows($resultClo);
					$rowClo=mysqli_fetch_array($resultClo);
					if ($nbClo > 0){
						$total += $rowClo['Nb'];
						$nbCloture = $rowClo['Nb'];
					}
					
					$nbEffTotal = $total;
				?>
				<tr>
					<td>
						<?php
							if($_SESSION['Langue']=="FR"){echo "% surveillances planifiées :";}
							else{echo "% Monitorings planned :";}
						?>
					</td>
					<td>
						<?php 
							if($nbEffTotal>0){
								echo round((($nbPlanifie+$nbRealise+$nbReplanifie)/$nbEffTotal)*100,0)."%";
							}
							else{
								echo "0%";
							}
						?>
					</td>
				</tr>
				<tr>
					<td>
						<?php
							if($_SESSION['Langue']=="FR"){echo "% surveillances clôturées :";}
							else{echo "% Monitorings closed :";}
						?>
					</td>
					<td>
						<?php 
							if($nbEffTotal>0){
								echo round(($nbCloture/$nbEffTotal)*100,0)."%";
							}
							else{
								echo "0%";
							}
						?>
					</td>
				</tr>
				<!--
				<tr>
					<td>
						% minimum à surveiller/Monitoring to monitore : 
						<?php
							if($_SESSION['Langue']=="FR"){echo "% minimum à surveiller :";}
							else{echo "% Minimum to monitore :";}
						?>
					</td>
					<td>
					<?php
						$Annee = "";
						if ($dateDebut <> ""){
							if ($NavigOk ==1){
								$tabDateDebut = explode('-', $dateDebut);
								$Annee = $tabDateDebut[0];

							}
							else{
								$tabDateDebut = explode('/', $dateDebut);
								$Annee = $tabDateDebut[2];
							}
						}
					?>
					</td>
				</tr>
				-->
			</table>
		</td>
		<td></td>
	</tr>
</form>
</table>

<?php
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
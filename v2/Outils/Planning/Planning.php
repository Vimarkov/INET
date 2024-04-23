<?php
require("../../Menu.php");
$dateDuJour = date("Y/m/d");
?>

<script>
	function FermerEtRecharger()
	{
		opener.location.reload();
		window.close();
	}
	function OuvreFenetreProfil(Mode,Id)
		{var w=window.open("../Competences/Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1040,height=800");
		w.focus();
		}
	function OuvreFenetreModifPlanning(Id_Prestation,Id_Personne,lDate,lDateEnvoi,Id_Pole,Tri)
		{var w=window.open("PlanningVacation.php?Id_Prestation="+Id_Prestation+"&Id_Personne="+Id_Personne+"&lDate="+lDate+"&lDateEnvoi="+lDateEnvoi+"&Id_Pole="+Id_Pole+"&Tri="+Tri,"PagePlanning","status=no,menubar=no,scrollbars=1,width=1100,height=600");
		w.focus();
		}
	
	function OuvreFenetrePlanningExport(Id_Prestation,lDate,Id_Pole)
		{window.open("Planning_Export.php?Id_Prestation="+Id_Prestation+"&lDate="+lDate+"&Id_Pole="+Id_Pole,"PagePlanningExport","status=no,menubar=no,scrollbars=1,width=900,height=400");}
	function OuvreFenetreAidePlanning()
		{var w=window.open("AidePlanning.php","PageAidePlanning","status=no,menubar=no,width=900,height=450");
		w.focus();
		}
</script>
	
<form action="Planning.php" method="post">
	<table style="width:100%; cellpadding:0; cellspacing:0; align:center;">
		<tr>
			<td colspan="5">
				<table class="GeneralPage" style="width:100%; cellpadding:0; cellspacing:0;">
					<tr>
						<td class="TitrePage">Gestion du planning # Planning</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<br/>
			</td>
		</tr>
		<tr>
			<td>
				<table style="width:100%; cellpadding:0; cellspacing:0; align:center;" class="GeneralInfo">
					<tr>
						<td width=20%>
							&nbsp; Prestation :
							<select class="prestation" name="prestations" onchange="submit();">
							<?php
							if(DroitsFormationPlateforme($TableauIdPostesRH)){
								$req = "SELECT
											DISTINCT new_competences_prestation.Id,
											new_competences_prestation.Libelle AS NomPrestation
										FROM
											new_competences_prestation
										WHERE new_competences_prestation.Id_Plateforme IN (
										SELECT Id_Plateforme FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']." 
										AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
										)
										ORDER BY
											new_competences_prestation.Libelle;";
								
							}
							else{
								$req = "SELECT
											DISTINCT new_competences_prestation.Id,
											new_competences_prestation.Libelle AS NomPrestation
										FROM
											new_competences_prestation
											LEFT JOIN new_competences_personne_poste_prestation
												ON new_competences_personne_poste_prestation.Id_Prestation = new_competences_prestation.Id
											LEFT JOIN new_competences_personne_prestation
												ON new_competences_personne_prestation.Id_Prestation = new_competences_prestation.Id
										WHERE
											new_competences_personne_poste_prestation.Id_Personne=".$_SESSION['Id_Personne']."
											OR
											(
												new_competences_personne_prestation.Id_Personne =".$_SESSION['Id_Personne']."
												AND new_competences_personne_prestation.Date_Debut <='".$dateDuJour ."'
												AND new_competences_personne_prestation.Date_Fin >='".$dateDuJour ."'
											)
										ORDER BY
											new_competences_prestation.Libelle;";
							}

							$resultPrestation=mysqli_query($bdd,$req);
							$nbPrestation=mysqli_num_rows($resultPrestation);
							
							$PrestationSelect = 0;
							$Selected = "";
							if ($nbPrestation > 0)
							{
								if (!empty($_GET['Id_Prestation'])){
									if ($PrestationSelect == 0){$PrestationSelect = $_GET['Id_Prestation'];}
									while($row=mysqli_fetch_array($resultPrestation))
									{
										if ($row[0] == $_GET['Id_Prestation']){
											$Selected = "Selected";
										}
										echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
										$Selected = "";
									}
								}
								elseif (!empty($_POST['prestations'])){
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
									while($row=mysqli_fetch_array($resultPrestation))
									{
										if ($PrestationSelect == 0){
											$PrestationSelect = $row[0];
										}
										echo "<option name='".$row[0]."' value='".$row[0]."'>".$row[1]."</option>";
									}
								}
							 }
							 ?>
							</select>
						</td>
						<td width=15%>
							&nbsp; Pôle :
							<select class="pole" name="pole" onchange="submit();">
							<?php
							$reqPole = "SELECT new_competences_pole.Id, new_competences_pole.Libelle FROM new_competences_pole ";
							$reqPole .= "WHERE new_competences_pole.Id_Prestation =".$PrestationSelect." AND Actif=0 ;";
							
							$resultPole=mysqli_query($bdd,$reqPole);
							$nbPole=mysqli_num_rows($resultPole);
							
							$PoleSelect = 0;
							$Selected = "";
							if ($nbPole > 0)
							{
								echo "<option name='0' value='0' Selected></option>";
								if (!empty($_GET['Id_Pole'])){
									if ($PoleSelect == 0){$PoleSelect = $_GET['Id_Pole'];}
									while($row=mysqli_fetch_array($resultPole))
									{
										if ($row[0] == $_GET['Id_Pole']){$Selected = "Selected";}
										echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
										$Selected = "";
									}
								}
								elseif (!empty($_POST['pole'])){
									if ($PoleSelect == 0){$PoleSelect = $_POST['pole'];}
									while($row=mysqli_fetch_array($resultPole))
									{
										if ($row[0] == $_POST['pole']){$Selected = "Selected";}
										echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
										$Selected = "";
									}
								}
								else{
									while($row=mysqli_fetch_array($resultPole))
									{
										if ($PoleSelect == 0){$PoleSelect = 0;}
										echo "<option name='".$row[0]."' value='".$row[0]."'>".$row[1]."</option>";
									}
								}
							 }
							 ?>
							</select>
						</td>
						<td width=40%>
							&nbsp;
							Début :
							<?php
								$dateEnvoi ="";
								$ltri = "Personne";
								if (!empty($_GET['uneDate'])){
									$dateEnvoi = $_GET['uneDate'];
									$ltri = "Personne";
									if ($NavigOk ==1){
										$dateDebut = date("Y-m-d",$_GET['uneDate']);
										
										$tabDateDebut = explode('-', $dateDebut);
										$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1]+1, 0, $tabDateDebut[0]);
										$dateDeFin = date('d/m/Y', $timestampDebut);
										$timestampMoisP = mktime(0, 0, 0, $tabDateDebut[1]-1, $tabDateDebut[2], $tabDateDebut[0]);
										$MoisPrecedent = date('d/m/Y', $timestampMoisP);
										$timestampMoisS = mktime(0, 0, 0, $tabDateDebut[1]+1, $tabDateDebut[2], $tabDateDebut[0]);
										$MoisSuivant = date('d/m/Y', $timestampMoisS);
									}
									else{
										$dateDebut = date("d/m/Y",$_GET['uneDate']);
										
										$tabDateDebut = explode('/', $dateDebut);
										$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1]+1, 0, $tabDateDebut[2]);
										$dateDeFin = date('d/m/Y', $timestampDebut);
										$timestampMoisP = mktime(0, 0, 0, $tabDateDebut[1]-1, $tabDateDebut[0], $tabDateDebut[2]);
										$MoisPrecedent = date('d/m/Y', $timestampMoisP);
										$timestampMoisS = mktime(0, 0, 0, $tabDateDebut[1]+1, $tabDateDebut[0], $tabDateDebut[2]);
										$MoisSuivant = date('d/m/Y', $timestampMoisS);
									}
								}
								else{
									if (isset($_POST['BtnTriPersonne'])){
										$ltri = "Personne";
									}
									elseif (isset($_POST['BtnTriMetier'])){
										$ltri = "CodeMetier";
									}
									else{
										if(!empty($_POST['leTri'])){
											$ltri = $_POST['leTri'];
										}
										else{
											$ltri = "Personne";
										}
									}
									if (!empty($_POST['DateDeDebut'])){
										if ($NavigOk ==1){
											$dateDebut = $_POST['DateDeDebut'];
											$tabDateDebut = explode('-', $dateDebut);
											$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[2], $tabDateDebut[0]);
											$dateEnvoi = $timestampDebut;
										}
										else{
											$dateDebut = $_POST['DateDeDebut'];
											$tabDateDebut = explode('/', $dateDebut);
											$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
											$dateEnvoi = $timestampDebut;
										}
									}
									else{
										if ($NavigOk ==1){
											$dateDebut = date("Y-m-01");
											$tabDateDebut = explode('-', $dateDebut);
											$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[2], $tabDateDebut[0]);
											$dateEnvoi = $timestampDebut;

										}
										else{
											$dateDebut = date("01/m/Y");
											$tabDateDebut = explode('/', $dateDebut);
											$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
											$dateEnvoi = $timestampDebut;
										}
									}
									//Cas Google CHROME
									if ($NavigOk ==1){
										if (!empty($_POST['MoisPrecedent'])){
											$dateDebut = substr($_POST['MoisPrecedent'], 3);
											$tabDateDebut = explode('/', $dateDebut);
											$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
											$dateDebut = date('Y-m-d', $timestampDebut);
											$dateEnvoi =  $timestampDebut;
										}
										if (!empty($_POST['MoisSuivant'])){
											$dateDebut = substr($_POST['MoisSuivant'], 0,-3);
											$tabDateDebut = explode('/', $dateDebut);
											$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
											$dateDebut = date('Y-m-d', $timestampDebut);
											$dateEnvoi =  $timestampDebut;
										}
										$tabDateDebut = explode('-', $dateDebut);
										$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1]+1, $tabDateDebut[2], $tabDateDebut[0]);
										$dateDeFin = date('d/m/Y', $timestampDebut);
										$timestampMoisP = mktime(0, 0, 0, $tabDateDebut[1]-1, $tabDateDebut[2], $tabDateDebut[0]);
										$MoisPrecedent = date('d/m/Y', $timestampMoisP);
										$timestampMoisS = mktime(0, 0, 0, $tabDateDebut[1]+1, $tabDateDebut[2], $tabDateDebut[0]);
										$MoisSuivant = date('d/m/Y', $timestampMoisS);
									}
									else{
										//Autres cas
																	
										if (!empty($_POST['MoisPrecedent'])){
											$dateDebut = substr($_POST['MoisPrecedent'], 3);
										}
										if (!empty($_POST['MoisSuivant'])){
											$dateDebut = substr($_POST['MoisSuivant'], 0,-3);
										}
										$tabDateDebut = explode('/', $dateDebut);
										$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
										$dateEnvoi =  $timestampDebut;
										$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1]+1, $tabDateDebut[0], $tabDateDebut[2]);
										$dateDeFin = date('d/m/Y', $timestampDebut);
										$timestampMoisP = mktime(0, 0, 0, $tabDateDebut[1]-1, $tabDateDebut[0], $tabDateDebut[2]);
										$MoisPrecedent = date('d/m/Y', $timestampMoisP);
										$timestampMoisS = mktime(0, 0, 0, $tabDateDebut[1]+1, $tabDateDebut[0], $tabDateDebut[2]);
										$MoisSuivant = date('d/m/Y', $timestampMoisS);
									}
								}
								if (!empty($_GET['Tri'])){
									$ltri = $_GET['Tri'];
								}
							?>
							
							<input type="date" style="text-align:center;" name="DateDeDebut" size="10" value="<?php echo $dateDebut; ?>">
							<input class="Bouton" name="BtnDateDebut" size="10" type="submit" value="Valider">
							&nbsp;
							Date fin :
							<input type="text" readonly=readonly style="text-align:center;" name="DateDeFin"  size="10" value="<?php echo $dateDeFin; ?>">
							&nbsp;
							<input class="Bouton" name="MoisPrecedent" size="10" type="submit" alt="Mois précédent" value="<< <?php echo $MoisPrecedent; ?>">
							<input class="Bouton" name="MoisSuivant" size="10" type="submit" alt="Mois suivant" value="<?php echo $MoisSuivant; ?> >>">
						</td>
						<td style="display:none;"><input type="text" name="leTri" size="11" value="<?php echo $ltri; ?>"></td>
						<td width=15%>
							<input class="Bouton" name="BtnTriPersonne" size="10" type="submit" value="Trier(Personne)">
							<input class="Bouton" name="BtnTriMetier" size="10" type="submit" value="Trier(Métier)">
						</td>
						<td width=5%>
							<?php
							if ($NavigOk ==1){
								$tabDateTransfert = explode('-', $dateDebut);
								$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
							}
							else{
								$tabDateTransfert = explode('/', $dateDebut);
								$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[0], $tabDateTransfert[2]);
							}
							
							echo "&nbsp;";
							echo "<a style='text-decoration:none;' href='javascript:OuvreFenetrePlanningExport(".$PrestationSelect.",".$timestampTransfert.",".$PoleSelect.");'>";
							echo "<img src='../../Images/excel.gif' border='0' alt='Excel' title='Export Excel'>";
							echo "</a>";
							echo "&nbsp;";
							?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<table style="margin-bottom:300px;margin-right:270px;">
	<?php
	$EnTeteMois = "<td ";
	$EnTeteSemaine = "<td ";
	$EnTeteJourSemaine = "";
	$EnTeteJour = "";
	
	//Cas Google CHROME
	if ($NavigOk ==1){
		$tabDateDebut = explode('-', $dateDebut);
		$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[2], $tabDateDebut[0]);
		$tmpDate = date("Y/m/d",$timestampDebut);
	}
	else{
		//Autres cas
		$tabDateDebut = explode('/', $dateDebut);
		$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
		$tmpDate = date("Y/m/d",$timestampDebut);
	}
	$tabDateFin = explode('/', $dateDeFin);
	$timestampFin = mktime(0, 0, 0, $tabDateFin[1], $tabDateFin[0], $tabDateFin[2]);
	$dateFin = date("Y/m/d", $timestampFin);
	
	$tabDate = explode('/', $tmpDate);
	$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
	$tmpMois = date('n', $timestamp) . ' ' . date('Y', $timestamp);
	$cptMois = 0;
	$cptSemaine = 0;
	$cptJour = 0;
	$joursem = array("D", "L", "M", "M", "J", "V", "S");
	$MoisLettre = array("Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Decembre");
	// GESTION DES ENTETES DU TABLEAU (MOIS, SEMAINE ET JOUR)
	while ($tmpDate <= $dateFin) 
	{
		$tabDate = explode('/', $tmpDate);
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
		$tabDate = explode('/', $tmpDate);
		$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
		$tmpDate = date("Y/m/d", $timestamp);
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
	
	$resultTri = "Personne";
	$nomETPersonne = "Personne &darr;";
	$nomETMetier = "Métier";
	if ($ltri <> ""){
		$resultTri = $ltri;
	}
	if ($resultTri <> "Personne"){
		$nomETPersonne = "Personne";
		$nomETMetier = "Métier &darr;";
	}
	?>
	<tr align="center">
		<td colspan=2 rowspan ="3" align="center" valign="middle">
			<a style="text-decoration:none;" href='javascript:OuvreFenetreAidePlanning()'>
				<img src='../../Images/aide.gif' border='0' alt='Aide' title='Aide'>
			</a>&nbsp;
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
		<td class="EnTeteSemaine" style="font-size:12px;"><?php echo $nomETPersonne; ?></td>
		<td class="EnTeteSemaine" style="font-size:12px;"><?php echo $nomETMetier; ?></td>
		<?php echo $EnTeteJour ;?>
	</tr>
	<?php
	// FIN GESTION DES ENTETES DU TABLEAU
	
	//DEBUT CORPS DU TABLEAU
	//Cas Google CHROME
	if ($NavigOk ==1){
		$tabDateDebut = explode('-', $dateDebut);
		$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[2], $tabDateDebut[0]);
		$tmpDate = date("Y/m/d",$timestampDebut);
	}
	else{
		//Autres cas
		$tabDateDebut = explode('/', $dateDebut);
		$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
		$tmpDate = date("Y/m/d",$timestampDebut);
	}
	$tabDateFin = explode('/', $dateDeFin);
	$timestampFin = mktime(0, 0, 0, $tabDateFin[1], $tabDateFin[0], $tabDateFin[2]);
	$dateFin = date("Y/m/d", $timestampFin);
	
	//Personnes présentes sur cette prestation à ces dates
	$reqMilieu="";
	if ($PoleSelect > 0){$reqMilieu=" AND new_competences_personne_prestation.Id_Pole =".$PoleSelect." ";}
	$req = "SELECT
				DISTINCT new_competences_personne_prestation.Id_Personne, 
				CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne, 
				(SELECT new_competences_metier.Libelle FROM new_competences_metier WHERE new_competences_metier.Id = (SELECT Id_Metier FROM new_competences_personne_metier WHERE new_competences_personne_metier.Id_Personne=new_competences_personne_prestation.Id_Personne AND Futur=0 ORDER BY Id DESC LIMIT 1)) AS Metier, 
				(SELECT new_competences_metier.Code FROM new_competences_metier WHERE new_competences_metier.Id = (SELECT Id_Metier FROM new_competences_personne_metier WHERE new_competences_personne_metier.Id_Personne=new_competences_personne_prestation.Id_Personne AND Futur=0 ORDER BY Id DESC LIMIT 1)) AS CodeMetier 
			FROM
				new_competences_personne_prestation
				RIGHT JOIN new_rh_etatcivil
					ON new_rh_etatcivil.Id = new_competences_personne_prestation.Id_Personne
			WHERE
				new_competences_personne_prestation.Id_Prestation =".$PrestationSelect.
				$reqMilieu."
				AND 
				(
					(
						new_competences_personne_prestation.Date_Debut<='".$tmpDate."'
						AND new_competences_personne_prestation.Date_Fin>='".$tmpDate."'
					)
					OR
					(
						new_competences_personne_prestation.Date_Debut<='".$dateFin."'
						AND new_competences_personne_prestation.Date_Fin>='".$dateFin."'
					)
					OR
					(
						new_competences_personne_prestation.Date_Debut>='".$tmpDate."'
						AND new_competences_personne_prestation.Date_Fin<='".$dateFin."'
					)
				)
			ORDER BY ".
				$resultTri." ASC,
				Personne ASC;";
	$resultPersonne=mysqli_query($bdd,$req);
	$nbPersonne=mysqli_num_rows($resultPersonne);
	
	//Accès de la personne connectée
	$PoleSelect2 = $PoleSelect;
	if ($PoleSelect2 == ""){$PoleSelect2=0;}
	$b_acces = 0;
	$reqnew_acces = "
		SELECT
			new_competences_personne_poste_prestation.Id_Pole,
			new_competences_personne_poste_prestation.Id_Poste
		FROM
			new_competences_personne_poste_prestation
		WHERE
			new_competences_personne_poste_prestation.Id_Personne=".$_SESSION['Id_Personne']."
			AND
			(
				new_competences_personne_poste_prestation.Id_Poste=1
				OR new_competences_personne_poste_prestation.Id_Poste=2
			)
			AND new_competences_personne_poste_prestation.Id_Prestation=".$PrestationSelect.";";
	$personnenew_acces=mysqli_query($bdd,$reqnew_acces);
	$nbPersonnenew_acces=mysqli_num_rows($personnenew_acces);
	
	if ($nbPersonnenew_acces > 0){
		while($rowPersonnenew_acces=mysqli_fetch_array($personnenew_acces))
		{
			if($b_acces == 0){
				if ($rowPersonnenew_acces[0] == $PoleSelect2 ){
						$b_acces = 1;
				}
			}
		}
	}
	if ($nbPersonne > 0){
		$couleurPersonne = 1;
		$couleurMetier = 1;
		$metierdernier = "";
		while($row=mysqli_fetch_array($resultPersonne)){
			$Id_Personne = $row[0];

			$onClickPersonne = "";
			$title = "";
			if ($b_acces == 0){
				$onClickPersonne = "";
				$title = "";
			}
			else{
				$onClickPersonne="onclick='javascript:OuvreFenetreProfil(\"Lecture\",".$Id_Personne.")'";
				$title = "title='Cliquer pour modifier le profil'";
			}
			echo "<tr>";
			if ($ltri == "Personne"){
				if ($couleurPersonne == 1){
					echo "<td class='PersonnePlanning'>".$row[1]."</td>";
					echo "<td id='leHoverPersonne' class='MetierPlanning'>".$row[3]."<span>Métier : ".$row[2]."</span></td>";
					$couleurPersonne =2;
				}
				else{
					echo "<td class='PersonnePlanning2'>".$row[1]."</td>";
					echo "<td id='leHoverPersonne' class='MetierPlanning2'>".$row[3]."<span>Métier : ".$row[2]."</span></td>";
					$couleurPersonne =1;
				}
			}
			else{
				if ($metierdernier==""){
					$metierdernier = $row[2];
				}
				
				if($metierdernier == $row[2]){
					if ($couleurPersonne == 1){
						echo "<td class='PersonnePlanning'>".$row[1]."</td>";
						echo "<td id='leHoverPersonne' class='MetierPlanning'>".$row[3]."<span>Métier : ".$row[2]."</span></td>";
					}
					else{
						echo "<td class='PersonnePlanning2'>".$row[1]."</td>";
						echo "<td id='leHoverPersonne' class='MetierPlanning2'>".$row[3]."<span>Métier : ".$row[2]."</span></td>";
					}
				}
				else{
					$metierdernier = $row[2];
					if ($couleurPersonne == 1){
						echo "<td class='PersonnePlanning2'>".$row[1]."</td>";
						echo "<td id='leHoverPersonne' class='MetierPlanning2'>".$row[3]."<span>Métier : ".$row[2]."</span></td>";
						$couleurPersonne =2;
					}
					else{
						echo "<td class='PersonnePlanning'>".$row[1]."</td>";
						echo "<td id='leHoverPersonne' class='MetierPlanning'>".$row[3]."<span>Métier : ".$row[2]."</span></td>";
						$couleurPersonne =1;
					}
				}
			}
			if ($NavigOk ==1){
				$tabDateDebut = explode('-', $dateDebut);
				$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[2], $tabDateDebut[0]);
				$tmpDate = date("Y/m/d",$timestampDebut);
			}
			else{
				//Autres cas
				$tabDateDebut = explode('/', $dateDebut);
				$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
				$tmpDate = date("Y/m/d",$timestampDebut);
			}
			
			/**************REQUETES DE LA PERSONNE**************/
			//Recherche pour ce jour-ci
			$reqPresta = "  
				SELECT
					DISTINCT new_competences_personne_prestation.Id_Prestation,
					(SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = new_competences_personne_prestation.Id_Prestation) AS Nom ,
					new_competences_personne_prestation.Date_Debut,
					new_competences_personne_prestation.Date_Fin,
					(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id = new_competences_personne_prestation.Id_Pole) AS NomPole,new_competences_personne_prestation.Id_Pole
				FROM
					new_competences_personne_prestation
				WHERE
					new_competences_personne_prestation.Id_Personne =".$Id_Personne."
					AND new_competences_personne_prestation.Id_Prestation=".$PrestationSelect."
					AND
					(
						(
							new_competences_personne_prestation.Date_Debut<='".$tmpDate."'
							AND new_competences_personne_prestation.Date_Fin>='".$tmpDate."'
						)
						OR
						(
							new_competences_personne_prestation.Date_Debut<='".$dateFin."'
							AND new_competences_personne_prestation.Date_Fin>='".$dateFin."'
						)
						OR
						(
							new_competences_personne_prestation.Date_Debut>='".$tmpDate."'
							AND new_competences_personne_prestation.Date_Fin<='".$dateFin."'
						)
					) ;";
			$prestaJour=mysqli_query($bdd,$reqPresta);
			$nbprestaJour=mysqli_num_rows($prestaJour);
			
			//Recherche si ses formations
			$reqFor = " SELECT
							new_planning_personne_formation.NbHeureVacation,
							new_planning_personne_formation.NbHeureHorsVacation,
							new_planning_personne_formation.DateFormation,
							new_planning_personne_formation.NomFormation
						FROM
							new_planning_personne_formation
						WHERE
							new_planning_personne_formation.Id_Personne =".$Id_Personne."
							AND new_planning_personne_formation.DateFormation>='".$tmpDate."'
							AND new_planning_personne_formation.DateFormation<='".$dateFin."';";
			$formationJour=mysqli_query($bdd,$reqFor);
			$nbformationJour=mysqli_num_rows($formationJour);
			
			//Recherche si planning
			$reqPla = " SELECT
							new_planning_vacationabsence.Nom,
							new_planning_vacationabsence.Couleur,
							new_planning_vacationabsence.AbsenceVacation,
							new_planning_vacationabsence.Description,
							new_planning_personne_vacationabsence.Commentaire,
							new_planning_personne_vacationabsence.DatePlanning,
							new_planning_personne_vacationabsence.Id_Prestation,
							new_planning_personne_vacationabsence.PassageInfirmerieSansArret,
							new_planning_personne_vacationabsence.Divers,
							(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=new_planning_personne_vacationabsence.Id_Prestation) AS Prestation
						FROM
							new_planning_personne_vacationabsence
							LEFT JOIN new_planning_vacationabsence 
								ON new_planning_personne_vacationabsence.ID_VacationAbsence = new_planning_vacationabsence.Id
						WHERE
							new_planning_personne_vacationabsence.Id_Personne=".$Id_Personne."
							AND new_planning_personne_vacationabsence.DatePlanning>='".$tmpDate."'
							AND new_planning_personne_vacationabsence.DatePlanning<='".$dateFin."';";
			$vacationJour=mysqli_query($bdd,$reqPla);
			$nbVacationJour=mysqli_num_rows($vacationJour);
			
			//Formation dans l'outil formation 
			$req="  SELECT
						DISTINCT form_session_date.DateSession
					FROM
						form_session_date 
						LEFT JOIN form_session 
							ON form_session_date.Id_Session=form_session.Id
					WHERE
						form_session_date.Suppr=0 
						AND form_session.Suppr=0
						AND form_session.Annule=0 
						AND form_session_date.DateSession>='".$tmpDate."'
						AND form_session_date.DateSession<='".$dateFin."'
						AND
						(
							SELECT
								COUNT(form_session_personne.Id) 
							FROM
								form_session_personne
							WHERE
								form_session_personne.Suppr=0
								AND form_session_personne.Id_Personne=".$Id_Personne." 
								AND form_session_personne.Validation_Inscription IN (0,1)
								AND form_session_personne.Id_Session=form_session.Id
								AND Presence IN (0,1,-2)
					   )>0 ";
			$resultSession=mysqli_query($bdd,$req);
			$nbSession=mysqli_num_rows($resultSession);
			
			while ($tmpDate <= $dateFin) {
				$tabDate = explode('/', $tmpDate);
				$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
				$dateAffichage = date("d/m/Y",$timestamp);
				$class="";
				if (jour_ferie($timestamp) == true){
					$class = "weekFerie";
				}
				else{
					$class = "semaine";
				}

				//Recherche si planning pour ce jour-ci
				$Absence="Absence : ";
				$Vacation="Vacation : ";
				$Commentaire = "";
				$Divers = "";
				$Couleur = "";
				$CelPlanning= "";
				$FormationON = "Non";
				$Formation = "";
				$NbForVac ="";
				$NbForHorsVac = "";
				$ClassDiv = "";
				$PrestaDuJour = "";
				$Presta = "";
				$PassageInfirmerieSansArret = "Non";
				$Form=0;
				if ($nbVacationJour>0){
					$PrestaDuJour = "";
					$Presta = "";
					mysqli_data_seek($vacationJour,0);
					while($rowPlanning=mysqli_fetch_array($vacationJour)) {
						$tabDateVac = explode('-', $rowPlanning[5]);
						$timestampVac = mktime(0, 0, 0, $tabDateVac[1], $tabDateVac[2], $tabDateVac[0]);
						$dateVac = date("Y/m/d", $timestampVac);
						if ($dateVac == $tmpDate){
							$PrestaDuJour = $rowPlanning[6];
							$Couleur = " style=\"background-color:#888888;\"";
							if ($rowPlanning[6] == $PrestationSelect){
								if ($rowPlanning[1] != ""){$Couleur = " style=\"background-color:".$rowPlanning[1].";\"";}
							}
							$Commentaire = $rowPlanning[4];
							$Presta = substr($rowPlanning['Prestation'],0,7);
							$ClassDiv = "class='rempliSansFormation'";
							$Divers = $rowPlanning['Divers'];
							$CelPlanning=$rowPlanning[0];
							if ($rowPlanning[2] ==1){
								$Vacation .=$rowPlanning[3];
							}
							else{
								$Absence .=$rowPlanning[3];
							}
							if ($rowPlanning['PassageInfirmerieSansArret'] == 1){
								$PassageInfirmerieSansArret = "Oui";
							}
							break;
						}
					}
				}
				if ($Couleur == ""){
					$FormationON = "";
					if (jour_ferie($timestamp)){
						$ClassDiv ="class='weekFerie'";
					}
					else{
						$ClassDiv ="class='semaine'";
					}
				}
				
				//Recherche si en formtion ce jour-ci
				if ($nbformationJour>0){
					mysqli_data_seek($formationJour,0);
					while($rowFormation=mysqli_fetch_array($formationJour)) {
						$tabDateForm = explode('-', $rowFormation[2]);
						$timestampForm = mktime(0, 0, 0, $tabDateForm[1], $tabDateForm[2], $tabDateForm[0]);
						$dateForm = date("Y/m/d", $timestampForm);
						if ($dateForm == $tmpDate){
							$FormationON="Oui";
							$NbForVac =$rowFormation[0];
							$NbForHorsVac =$rowFormation[1];
							$Formation = $rowFormation['NomFormation'];
							$ClassDiv ="class='rempliAvecFormation'";
							
							break;
						}
					}
				}
				
				//Recherche si en formation dans l'outil
				if ($nbSession>0){
					mysqli_data_seek($resultSession,0);
					while($rowFormation=mysqli_fetch_array($resultSession)) {
						$tabDateForm = explode('-', $rowFormation['DateSession']);
						$timestampForm = mktime(0, 0, 0, $tabDateForm[1], $tabDateForm[2], $tabDateForm[0]);
						$dateForm = date("Y/m/d", $timestampForm);
						if ($dateForm == $tmpDate){
							$Form=1;
							$ClassDiv ="class='rempliAvecFormation'";
							break;
						}
					}
				}
				
				//Recherche si appartient à cette prestation ce jour -ci
				$onClick="";
				$contenu ="";
				if ($nbprestaJour>0){
					mysqli_data_seek($prestaJour,0);
					while($rowPrestaJour=mysqli_fetch_array($prestaJour)) {
						$tabDate2 = explode('-', $rowPrestaJour[2]);
						if ($tabDate2[0] > 2037){$tabDate2[0]=2037;}
						$timestamp2 = mktime(0, 0, 0, $tabDate2[1], $tabDate2[2], $tabDate2[0]);
						$dateDebutReq = date("Y/m/d", $timestamp2);
						$tabDate2 = explode('-', $rowPrestaJour[3]);
						if ($tabDate2[0] > 2037){$tabDate2[0]=2037;}
						$timestamp2 = mktime(0, 0, 0, $tabDate2[1], $tabDate2[2], $tabDate2[0]);
						$dateFinReq = date("Y/m/d", $timestamp2);
						if ($dateDebutReq <= $tmpDate && $dateFinReq >= $tmpDate){
							$tabDateTransfert = explode('/', $tmpDate);
							$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
							$onClick="onclick='javascript:OuvreFenetreModifPlanning(".$PrestationSelect.",".$Id_Personne.",".$timestampTransfert.",".$dateEnvoi.",".$PoleSelect.",\"".$ltri."\")'";
							break;
						}
					}
				}
				if ($onClick==""){
					$tabDateTransfert = explode('/', $tmpDate);
					$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
					//$onClick="onclick='javascript:OuvreFenetreModifPlanning(".$PrestationSelect.",".$Id_Personne.",".$timestampTransfert.",".$dateEnvoi.",".$PoleSelect.")'";
					$contenu = "";
					$Couleur = " style='background-color:#000000;' ";
				}
				else{
					$contenu = $CelPlanning;
				}
				
				//Accès en lecture ou écriture en fonction du poste
				$info = "";
				$id = "";
				if($Form == 1){$ClassDiv ="class='rempliAvecFormation'";}
				if ($b_acces == 0){
					$onClick = "";
					$id = "id='leHover'";
					$info = "\n<span>Personne : ".$row[1]."<br/>\nDate : ".$dateAffichage."<br/>\nPrestation : ".$Presta."<br/>\n----------------------------------<br/>\nDivers : ".$Divers."<br/>\n----------------------------------<br/>\nFormation : ".$Formation."<br/>\nNombre d'heures pendant la vacation : ".$NbForVac."<br/>\nNombre d'heures hors vacation : ".$NbForHorsVac."<br/>\n----------------------------------<br/>\nPassage à l'infirmerie sans arrêt : ".$PassageInfirmerieSansArret."</span>\n";
				}
				else{
					if($Commentaire != "")
					{
						if($FormationON == "Oui"){$ClassDiv ="class='rempliAvecFormationEtCommentaire'";}
						else{$ClassDiv ="class='rempliAvecCommentaire'";}
					}
					$id = "id='leHover'";
					$info = "\n<span>Personne : ".$row[1]."<br/>\nDate : ".$dateAffichage."<br/>\nPrestation : ".$Presta."<br/>\n----------------------------------<br/>\nCommentaire : ".$Commentaire."<br/>\n----------------------------------<br/>\nDivers : ".$Divers."<br/>\n----------------------------------<br/>\nFormation : ".$Formation."<br/>\nNombre d'heures pendant la vacation : ".$NbForVac."<br/>\nNombre d'heures hors vacation : ".$NbForHorsVac."<br/>\n----------------------------------<br/>\nPassage à l'infirmerie sans arrêt : ".$PassageInfirmerieSansArret."</span>\n";
				}
				//Cellule finale
				echo "<td ".$id." ".$ClassDiv." ".$Couleur." ".$onClick.">".$contenu."".$info."</td>\n";
				//Jour suivant
				$tabDate = explode('/', $tmpDate);
				$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
				$tmpDate = date("Y/m/d", $timestamp);
			}
			echo "</tr>";
		}
	 }
	?>
</table>
<?php
//if($_SESSION['Id_Personne']==767){echo date('H:i:s')."<br>";}
?>
</body>
</html>
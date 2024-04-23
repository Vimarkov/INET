<?php
require("../../Menu.php");
$dateDuJour = date("Y/m/d");
?>

<script>
	function OuvreFenetreModifPointage(Id_Prestation,Id_Personne,lDate,lDateEnvoi,Id_Pole,Tri)
		{var w=window.open("ModifierPointage.php?Id_Prestation="+Id_Prestation+"&Id_Personne="+Id_Personne+"&lDate="+lDate+"&lDateEnvoi="+lDateEnvoi+"&Id_Pole="+Id_Pole+"&Tri="+Tri,"PagePlanning","status=no,menubar=no,scrollbars=1,width=600");
		w.focus();
		}
	function OuvreFenetrePointageExport(Id_Prestation,lDate,Id_Pole)
		{window.open("Pointage_Export.php?Id_Prestation="+Id_Prestation+"&lDate="+lDate+"&Id_Pole="+Id_Pole,"PagePointageExport","status=no,menubar=no,scrollbars=1,width=900,height=400");}
	function OuvreFenetrePointagePlateformeExport(lDate)
		{window.open("PointagePlateforme_Export.php?lDate="+lDate,"PagePointageExport","status=no,menubar=no,scrollbars=1,width=900,height=400");}
	function OuvreFenetreAidePointage()
		{var w=window.open("AidePointage.php","PageAidePlanning","status=no,menubar=no,width=900,height=500");
		w.focus();
		}
	function OuvreFenetrePointageIndividuelExport(Id_Prestation,lDate,Id_Pole,Id_Personne)
		{window.open("PointageIndividuel_Export.php?Id_Prestation="+Id_Prestation+"&lDate="+lDate+"&Id_Pole="+Id_Pole+"&Id_Personne="+Id_Personne,"PagePointageExport","status=no,menubar=no,scrollbars=1,width=900,height=400");}
	function OuvreFenetreTempsPartiel(Id_Personne,Acces)
		{var w=window.open("TempsPartiel.php?Id_Personne="+Id_Personne+"&Acces="+Acces,"PageAidePlanning","status=no,scrollbars=1,width=470,height=500");
		w.focus();
		}
	function OuvreFenetreNbHeures(Id_Prestation)
		{var w=window.open("HeuresVacation.php?Id_Prestation="+Id_Prestation,"PageHeures","status=no,scrollbars=1,width=470,height=620");
		w.focus();
		}	
	function Cocher_Tout()
	{
		table = document.getElementsByTagName('input')
		for (l=0;l<table.length;l++)
		{
			if (table[l].type == 'checkbox'){
				table[l].checked = true;
			}
		}
	}
	function Decocher_Tout()
	{
		table = document.getElementsByTagName('input')
		for (l=0;l<table.length;l++)
		{
			if (table[l].type == 'checkbox'){
				table[l].checked = false;
			}
		}
	}
</script>

<form class="test" action="Pointage.php" method="post">
<table style="width:100%; cellpadding:0; cellspacing:0; align:center;">
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; cellpadding:0; cellspacing:0;">
				<tr>
					<td class="TitrePage">Gestion du planning # Pointage</td>
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
											NomPrestation;";
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
							$reqPole .= "WHERE new_competences_pole.Id_Prestation =".$PrestationSelect.";";
							
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
										$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1]+1, 0, $tabDateDebut[0]);
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
										$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1]+1, 0, $tabDateDebut[2]);
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
							<input class="Bouton"  name="BtnDateDebut" size="10" type="submit" value="Valider">
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
							
							$timestampTransfertPI = $timestampTransfert;
							echo "&nbsp;";
							echo "<a style='text-decoration:none;' href='javascript:OuvreFenetrePointageExport(".$PrestationSelect.",".$timestampTransfert.",".$PoleSelect.");'>";
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
			<td>
				<br/>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="4">
				<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreNbHeures(<?php echo $PrestationSelect; ?>)">&nbsp;Nombre d'heures prévues par vacation&nbsp;</a>
				<?php
				if($_SESSION['Id_Personne']==1351 || $_SESSION['Id_Personne']==376 || $_SESSION['Id_Personne']==387 || $_SESSION['Id_Personne']==3403){
				?>
					&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetrePointagePlateformeExport(' <?php echo $timestampTransfert; ?>');">&nbsp;Pointage plateforme&nbsp;</a>
				<?php
				}
				?>
			</td>
		</tr>
</table>
<table style="margin-bottom:300px;margin-right:30px;">
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
		$EnTeteMois =substr($EnTeteMois, 0, -4)."" ;
	}
	
	if ($joursem[$jour]<>"D"){
		$EnTeteSemaine .= " class='EnTeteSemaine' colspan=".$cptSemaine.">S".$semaine."</td>";
	}
	else{
		$EnTeteSemaine =substr($EnTeteSemaine, 0, -4)."" ;
	}
	
	//Accès de la personne connectée
	$b_acces = 0;
	$reqnew_acces = "   SELECT
							new_competences_personne_poste_prestation.Id_Pole
						FROM
							new_competences_personne_poste_prestation
						WHERE
							new_competences_personne_poste_prestation.Id_Personne=".$_SESSION['Id_Personne']."
							AND new_competences_personne_poste_prestation.Id_Poste=2
							AND new_competences_personne_poste_prestation.Id_Prestation=".$PrestationSelect.";";
	$personnenew_acces=mysqli_query($bdd,$reqnew_acces);
	$nbPersonnenew_acces=mysqli_num_rows($personnenew_acces);
	if ($nbPersonnenew_acces > 0){
		while($rowPersonnenew_acces=mysqli_fetch_array($personnenew_acces))
		{
			if($b_acces == 0){
				if ($rowPersonnenew_acces[0] == $PoleSelect){
						$b_acces = 1;
				}
			}
		}
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
		<td align="center" valign="middle">
			<a style="text-decoration:none;" href='javascript:OuvreFenetreAidePointage()'>
				<img src='../../Images/aide.gif' border='0' alt='Aide' title='Aide'>
			</a>&nbsp;
		</td>
		<td></td>
		<?php echo $EnTeteMois ;
			echo "<td rowspan ='3' align='center' valign='center' id='leHoverPersonne'>";
			if ($b_acces == 1){
				echo "<a style='text-decoration:none;' class='Bouton' href='javascript:Cocher_Tout()'>&nbsp;Tout cocher&nbsp;</a>&nbsp;";
				echo "<br>";
				echo "<a style='text-decoration:none;' class='Bouton' href='javascript:Decocher_Tout()'>&nbsp;Tout décocher&nbsp;</a>&nbsp;";
			}
			echo "</td>";
		?>
	</tr>
	<tr align="center">
		<td style="background-color:#ff4723;">A Valider</td>
		<td></td>
		<?php echo $EnTeteSemaine ;?>
	</tr>
	<tr align="center">
		<td style="background-color:#00ef02;">Validé</td>
		<td></td>
		<?php echo $EnTeteJourSemaine ;
		?>
	</tr>
	<tr align="center">
		<td class="EnTeteSemaine" style="font-size:12px;"><?php echo $nomETPersonne; ?></td>
		<td class="EnTeteSemaine" style="font-size:12px;"><?php echo $nomETMetier; ?></td>
		<?php echo $EnTeteJour ;
		
		if ($b_acces == 1){
			echo "<td class='checkbox2'><input type='submit' name='validerSelection' value='Valider sélection'></td>";
		}
		?>
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
	
	$laDateDebut = $tmpDate;
	$laDateFin = $dateFin;
	//Personnes  présentent sur cette prestation à ces dates
	$reqMilieu="";
	if($PoleSelect > 0){$reqMilieu=" AND new_competences_personne_prestation.Id_Pole =".$PoleSelect." ";}
	$req = "SELECT
				DISTINCT new_competences_personne_prestation.Id_Personne,
				CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
				(SELECT new_competences_metier.Libelle FROM new_competences_metier WHERE new_competences_metier.Id = (SELECT Id_Metier FROM new_competences_personne_metier WHERE new_competences_personne_metier.Id_Personne=new_competences_personne_prestation.Id_Personne AND Futur=0 ORDER BY Id DESC LIMIT 1)) AS Metier,
				(SELECT new_competences_metier.Code FROM new_competences_metier WHERE new_competences_metier.Id = (SELECT Id_Metier FROM new_competences_personne_metier WHERE new_competences_personne_metier.Id_Personne=new_competences_personne_prestation.Id_Personne AND Futur=0 ORDER BY Id DESC LIMIT 1)) AS CodeMetier,
				new_rh_etatcivil.TempsPartiel AS TempsPartiel
			FROM
				new_competences_personne_prestation
				RIGHT JOIN new_rh_etatcivil
					ON new_rh_etatcivil.Id = new_competences_personne_prestation.Id_Personne
			WHERE
				new_competences_personne_prestation.Id_Prestation =".$PrestationSelect
				.$reqMilieu."
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
			ORDER BY
				".$resultTri." ASC,
				Personne ASC;";

	$resultPersonne=mysqli_query($bdd,$req);
	$nbPersonne=mysqli_num_rows($resultPersonne);
	
	//Heures prévues par prestations
	$reqHeuresPrevues = "   SELECT
								new_planning_prestation_vacation.Id_Prestation,
								new_planning_prestation_vacation.ID_Vacation,
								new_planning_prestation_vacation.JourSemaine,
								new_planning_prestation_vacation.NbHeureJour,
								new_planning_prestation_vacation.NbHeureEquipeJour,
								new_planning_prestation_vacation.NbHeureEquipeNuit,
								new_planning_prestation_vacation.NbHeurePause
							FROM
								new_planning_prestation_vacation
							WHERE
								new_planning_prestation_vacation.Id_Prestation =".$PrestationSelect." ";
	$HeuresPrevues=mysqli_query($bdd,$reqHeuresPrevues);
	$nbHeuresPrevues=mysqli_num_rows($HeuresPrevues);
	if(isset($_POST['validerSelection'])){
		//Valider les personnes cochées
		if ($NavigOk ==1){
			$tabDateDebut = explode('-', $dateDebut);
			$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[2], $tabDateDebut[0]);
			$tmpDateV = date("Y-m-d",$timestampDebut);
		}
		else{
			//Autres cas
			$tabDateDebut = explode('/', $dateDebut);
			$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
			$tmpDateV = date("Y-m-d",$timestampDebut);
		}
		if ($nbPersonne > 0)
		{
			mysqli_data_seek($resultPersonne,0);
			while($row=mysqli_fetch_array($resultPersonne)){
				$Id_Personne = $row[0];
				if (isset($_POST['check_'.$row[0].''])){
					if ($NavigOk ==1){
						$tabDateDebut = explode('-', $dateDebut);
						$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[2], $tabDateDebut[0]);
						$tmpDateV = date("Y-m-d",$timestampDebut);
					}
					else{
						//Autres cas
						$tabDateDebut = explode('/', $dateDebut);
						$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
						$tmpDateV = date("Y-m-d",$timestampDebut);
					}
					
					//Temps partiel
					$reqTempsPartiel = "SELECT new_planning_personne_vacation_tp.ID_Personne, new_planning_personne_vacation_tp.ID_Vacation, new_planning_personne_vacation_tp.JourSemaine , new_planning_personne_vacation_tp.NbHeureJour, ";
					$reqTempsPartiel .= "new_planning_personne_vacation_tp.NbHeureEquipeJour, new_planning_personne_vacation_tp.NbHeureEquipeNuit, ";
					$reqTempsPartiel .= "new_planning_personne_vacation_tp.NbHeurePause ";
					$reqTempsPartiel .= "FROM new_planning_personne_vacation_tp ";
					$reqTempsPartiel .= "WHERE new_planning_personne_vacation_tp.ID_Personne=".$Id_Personne."";
					$TempsPartiel=mysqli_query($bdd,$reqTempsPartiel);
					$nbTempsPartiel=mysqli_num_rows($TempsPartiel);
					
					//Recherche ses formations
					$reqFor = "SELECT new_planning_personne_formation.NbHeureVacation, new_planning_personne_formation.NbHeureHorsVacation, new_planning_personne_formation.DateFormation ";
					$reqFor .= "FROM new_planning_personne_formation ";
					$reqFor .= "WHERE new_planning_personne_formation.Id_Personne =".$row[0]." ";
					$reqFor .= "AND new_planning_personne_formation.DateFormation>='".$tmpDateV."' AND new_planning_personne_formation.DateFormation<='".$dateFin."';";
					$formationJour=mysqli_query($bdd,$reqFor);
					$nbformationJour=mysqli_num_rows($formationJour);
					
					//Recherche ses heures supp
					$reqHS = "SELECT new_rh_heures_supp.Date, new_rh_heures_supp.Nb_Heures_Jour, new_rh_heures_supp.Nb_Heures_Nuit ";
					$reqHS .= "FROM new_rh_heures_supp ";
					$reqHS .= "WHERE new_rh_heures_supp.Id_Personne =".$row[0]." AND new_rh_heures_supp.Etat4='Validée' ";
					$reqHS .= "AND new_rh_heures_supp.Date>='".$tmpDateV."' AND new_rh_heures_supp.Date<='".$dateFin."';";
					$heureSupp=mysqli_query($bdd,$reqHS);
					$nbHeureSupp=mysqli_num_rows($heureSupp);
					
					//Recherche si planning
					$reqPla = "SELECT new_planning_vacationabsence.Id, new_planning_personne_vacationabsence.DatePlanning ";
					$reqPla .= "FROM new_planning_personne_vacationabsence LEFT JOIN new_planning_vacationabsence ON new_planning_personne_vacationabsence.ID_VacationAbsence = new_planning_vacationabsence.Id ";
					$reqPla .= "WHERE new_planning_personne_vacationabsence.Id_Personne=".$row[0]." ";
					$reqPla .= "AND new_planning_personne_vacationabsence.DatePlanning>='".$tmpDateV."' AND new_planning_personne_vacationabsence.DatePlanning<='".$dateFin."';";
					$vacationJour=mysqli_query($bdd,$reqPla);
					$nbVacationJour=mysqli_num_rows($vacationJour);
						
					while ($tmpDateV <= $dateFin) 
					{
						$tabDate = explode('-', $tmpDateV);
						$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
						$jour = date('w', $timestamp);
						
						//Recherche si en heure supp ce jour-ci
						$NbHeureSuppJ =0;
						$NbHeureSuppN =0;
						if ($nbHeureSupp>0){
							mysqli_data_seek($heureSupp,0);
							while($rowHS=mysqli_fetch_array($heureSupp)) {
								$tabDateHS = explode('-', $rowHS[0]);
								$timestampHS = mktime(0, 0, 0, $tabDateHS[1], $tabDateHS[2], $tabDateHS[0]);
								$dateHS = date("Y-m-d", $timestampHS);
								if ($dateHS == $tmpDateV){
									$NbHeureSuppJ += $rowHS[1];
									$NbHeureSuppN += $rowHS[2];
								}
							}
						}
						
						//Recherche si en formation ce jour-ci
						$NbForVac =0;
						$NbForHorsVac =0;
						$NbFor =0;
						if ($nbformationJour>0){
							mysqli_data_seek($formationJour,0);
							while($rowFormation=mysqli_fetch_array($formationJour)) {
								$tabDateForm = explode('-', $rowFormation[2]);
								$timestampForm = mktime(0, 0, 0, $tabDateForm[1], $tabDateForm[2], $tabDateForm[0]);
								$dateForm = date("Y-m-d", $timestampForm);
								if ($dateForm == $tmpDateV){
									$NbForVac =$rowFormation[0];
									$NbForHorsVac =$rowFormation[1];
									$NbFor = $NbForVac;
									break;
								}
							}
						}
						$resJ = 0;
						$resEJ = 0;
						$resEN = 0;
						$resFor = $NbFor;
						$resP = 0;
						if ($nbVacationJour > 0){
							mysqli_data_seek($vacationJour,0);
							while($rowPlanning=mysqli_fetch_array($vacationJour)){
								if ($rowPlanning['DatePlanning'] == $tmpDateV){
								
									if ($row['TempsPartiel'] == 1){
										if ($nbTempsPartiel > 0){
											mysqli_data_seek($TempsPartiel,0);
											while($rowTempsPartiel=mysqli_fetch_array($TempsPartiel)) {
												//Récupérer NbHeures prévu pour cette personne, cette vacation, ce jour de la semaine en temps partiel
												if($rowTempsPartiel[0] == $Id_Personne && $rowTempsPartiel[1] == $rowPlanning[0] && $rowTempsPartiel[2] == $jour){
													$resJ = $rowTempsPartiel[3];
													$resEJ = $rowTempsPartiel[4];
													$resEN = $rowTempsPartiel[5];
													$resP = $rowTempsPartiel[6];
													if ($resJ > 0){
														$resJ = $resJ - $NbForVac;
													}
													elseif ($resEJ > 0){
														$resEJ = $resEJ - $NbForVac;
													}
													if($NbFor>=7){$resJ=0;}
													$resJ += $NbHeureSuppJ;
													$resEN += $NbHeureSuppN;
												}
											}
										}
									}
									else{
										if ($nbHeuresPrevues > 0){
											mysqli_data_seek($HeuresPrevues,0);
											while($rowHeuresPrevues=mysqli_fetch_array($HeuresPrevues)) {
												//Récupérer NbHeures prévu pour cette prestation, cette vacation, ce jour de la semaine
												if($rowHeuresPrevues[0] == $PrestationSelect && $rowHeuresPrevues[1] == $rowPlanning[0] && $rowHeuresPrevues[2] == $jour){
													$resJ = $rowHeuresPrevues[3];
													$resEJ = $rowHeuresPrevues[4];
													$resEN = $rowHeuresPrevues[5];
													$resP = $rowHeuresPrevues[6];
													if ($resJ > 0){
														$resJ = $resJ - $NbForVac;
													}
													elseif ($resEJ > 0){
														$resEJ = $resEJ - $NbForVac;
													}
													if($NbFor>=7){$resJ=0;}
													$resJ += $NbHeureSuppJ;
													$resEN += $NbHeureSuppN;
												}
											}
										}
									}
									$reqUpdt = "UPDATE new_planning_personne_vacationabsence ";
									$reqUpdt .= "SET new_planning_personne_vacationabsence.NbHeureJour=".$resJ.", ";
									$reqUpdt .= "new_planning_personne_vacationabsence.NbHeureEquipeJour=".$resEJ.", ";
									$reqUpdt .= "new_planning_personne_vacationabsence.NbHeureEquipeNuit=".$resEN.", ";
									$reqUpdt .= "new_planning_personne_vacationabsence.NbHeurePause=".$resP.", ";
									$reqUpdt .= "new_planning_personne_vacationabsence.NbHeureFormation=".$resFor.", ";
									$reqUpdt .= "new_planning_personne_vacationabsence.ValidationResponsable=1 ";
									$reqUpdt .= "WHERE new_planning_personne_vacationabsence.Id_Personne=".$row[0]." ";
									$reqUpdt .= "AND new_planning_personne_vacationabsence.DatePlanning='".$tmpDateV."' ";
									$reqUpdt .= "AND new_planning_personne_vacationabsence.ValidationResponsable=0 ";
									$reqUpdt .= "AND new_planning_personne_vacationabsence.Id_Prestation=".$PrestationSelect.";";
									$resultUpdate=mysqli_query($bdd,$reqUpdt);
									break;
								}
							}
						}
						//Jour suivant
						$tabDate = explode('-', $tmpDateV);
						$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
						$tmpDateV = date("Y-m-d", $timestamp);
					}
				}
			}
		}
	}
	
	if ($nbPersonne > 0)
	{
		$couleurPersonne = 1;
		$couleurMetier = 1;
		$metierdernier = "";
		mysqli_data_seek($resultPersonne,0);
		while($row=mysqli_fetch_array($resultPersonne)){
			$Id_Personne = $row[0];
			
			//Temps partiel
			$reqTempsPartiel = "SELECT new_planning_personne_vacation_tp.ID_Personne, new_planning_personne_vacation_tp.ID_Vacation, new_planning_personne_vacation_tp.JourSemaine , new_planning_personne_vacation_tp.NbHeureJour, ";
			$reqTempsPartiel .= "new_planning_personne_vacation_tp.NbHeureEquipeJour, new_planning_personne_vacation_tp.NbHeureEquipeNuit, ";
			$reqTempsPartiel .= "new_planning_personne_vacation_tp.NbHeurePause ";
			$reqTempsPartiel .= "FROM new_planning_personne_vacation_tp ";
			$reqTempsPartiel .= "WHERE new_planning_personne_vacation_tp.ID_Personne=".$Id_Personne."";
			$TempsPartiel=mysqli_query($bdd,$reqTempsPartiel);
			$nbTempsPartiel=mysqli_num_rows($TempsPartiel);
			
			//Recherche prestations pour ce jour-ci
			$reqPresta = "SELECT DISTINCT(new_competences_personne_prestation.Id_Prestation), ";
			$reqPresta .= "(SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id = new_competences_personne_prestation.Id_Prestation) AS Nom , ";
			$reqPresta .= "new_competences_personne_prestation.Date_Debut, new_competences_personne_prestation.Date_Fin, ";
			$reqPresta .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id = new_competences_personne_prestation.Id_Pole) AS NomPole ";
			$reqPresta .= "FROM new_competences_personne_prestation ";
			$reqPresta .= "WHERE new_competences_personne_prestation.Id_Personne =".$Id_Personne." ";
			$reqPresta .= "AND ((new_competences_personne_prestation.Date_Debut<='".$laDateDebut."' AND new_competences_personne_prestation.Date_Fin>='".$laDateDebut."') ";
			$reqPresta .= "OR (new_competences_personne_prestation.Date_Debut<='".$laDateFin."' AND new_competences_personne_prestation.Date_Fin>='".$laDateFin."') ";
			$reqPresta .= "OR (new_competences_personne_prestation.Date_Debut>='".$laDateDebut."' AND new_competences_personne_prestation.Date_Fin<='".$laDateFin."')) ORDER BY Nom ASC;";

			$prestaJour=mysqli_query($bdd,$reqPresta);
			$nbprestaJour=mysqli_num_rows($prestaJour);
			
			//Recherche ses formations
			$reqFor = "SELECT new_planning_personne_formation.NbHeureVacation, new_planning_personne_formation.NbHeureHorsVacation, new_planning_personne_formation.DateFormation ";
			$reqFor .= "FROM new_planning_personne_formation ";
			$reqFor .= "WHERE new_planning_personne_formation.Id_Personne =".$Id_Personne." ";
			$reqFor .= "AND new_planning_personne_formation.DateFormation>='".$laDateDebut."' AND new_planning_personne_formation.DateFormation<='".$laDateFin."';";
			$formationJour=mysqli_query($bdd,$reqFor);
			$nbformationJour=mysqli_num_rows($formationJour);
			
			//Recherche si planning
			$reqPla = "SELECT new_planning_vacationabsence.Nom, new_planning_vacationabsence.Couleur, new_planning_vacationabsence.AbsenceVacation, new_planning_vacationabsence.Description, new_planning_personne_vacationabsence.Commentaire, new_planning_personne_vacationabsence.DatePlanning, new_planning_personne_vacationabsence.Id_Prestation, ";
			$reqPla .= "new_planning_personne_vacationabsence.NbHeureJour, new_planning_personne_vacationabsence.NbHeureEquipeJour, new_planning_personne_vacationabsence.NbHeureEquipeNuit, new_planning_personne_vacationabsence.NbHeurePause, new_planning_personne_vacationabsence.ValidationResponsable, new_planning_vacationabsence.Id, new_planning_personne_vacationabsence.NbHeureFormation ";
			$reqPla .= "FROM new_planning_personne_vacationabsence LEFT JOIN new_planning_vacationabsence ON new_planning_personne_vacationabsence.ID_VacationAbsence = new_planning_vacationabsence.Id ";
			$reqPla .= "WHERE new_planning_personne_vacationabsence.Id_Personne=".$Id_Personne." ";
			$reqPla .= "AND new_planning_personne_vacationabsence.DatePlanning>='".$laDateDebut."' AND new_planning_personne_vacationabsence.DatePlanning<='".$laDateFin."';";
			$vacationJour=mysqli_query($bdd,$reqPla);
			$nbVacationJour=mysqli_num_rows($vacationJour);
			
			//Recherche ses heures supp
			$reqHS = "SELECT new_rh_heures_supp.Date, new_rh_heures_supp.Nb_Heures_Jour, new_rh_heures_supp.Nb_Heures_Nuit ";
			$reqHS .= "FROM new_rh_heures_supp ";
			$reqHS .= "WHERE new_rh_heures_supp.Id_Personne =".$Id_Personne." AND new_rh_heures_supp.Etat4='Validée' ";
			$reqHS .= "AND new_rh_heures_supp.Date>='".$laDateDebut."' AND new_rh_heures_supp.Date<='".$laDateFin."';";
			$heureSupp=mysqli_query($bdd,$reqHS);
			$nbHeureSupp=mysqli_num_rows($heureSupp);
			
			//Recherche ses heures supp en cours de validation
			$reqHSenCours = "SELECT new_rh_heures_supp.Date, new_rh_heures_supp.Nb_Heures_Jour, new_rh_heures_supp.Nb_Heures_Nuit ";
			$reqHSenCours .= "FROM new_rh_heures_supp ";
			$reqHSenCours .= "WHERE new_rh_heures_supp.Id_Personne =".$Id_Personne." AND new_rh_heures_supp.Etat4='' AND new_rh_heures_supp.Etat3<>'Refusée' AND new_rh_heures_supp.Etat2<>'Refusée' ";
			$reqHSenCours .= "AND new_rh_heures_supp.Date>='".$laDateDebut."' AND new_rh_heures_supp.Date<='".$laDateFin."';";
			$heureSuppEC=mysqli_query($bdd,$reqHSenCours);
			$nbHeureSuppEC=mysqli_num_rows($heureSuppEC);
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
			
			while ($tmpDate <= $dateFin) {
				$tabDate = explode('/', $tmpDate);
				$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
				$dateAffichage = date("d/m/Y",$timestamp);
				$jour = date('w', $timestamp);
				$class="";
				if (jour_ferie($timestamp) == true){
					$class = "weekFerie";
				}
				else{
					$class = "semaine";
				}

				//Recherche si planning pour ce jour-ci
				$Absence="";
				$Vacation="";
				$Commentaire = "";
				$Couleur = "";
				$CelPlanning= "";
				$FormationON = "Non";
				$NbForVac =0;
				$NbForHorsVac = "";
				$ClassDiv = "";
				$PrestaDuJour = "";
				$NbFor = "";
				$NbHeureJ = "";
				$NbHeureEJ = "";
				$NbHeureEN = "";
				$NbHeureP = "";
				$NbHeureSuppJ = 0;
				$NbHeureSuppN = 0;
				$Etat = "Statut : <br/>";
				$bValide = 0;
				//Recherche si en heure supp ce jour-ci
				if ($nbHeureSupp>0){
					mysqli_data_seek($heureSupp,0);
					while($rowHS=mysqli_fetch_array($heureSupp)) {
						$tabDateHS = explode('-', $rowHS[0]);
						$timestampHS = mktime(0, 0, 0, $tabDateHS[1], $tabDateHS[2], $tabDateHS[0]);
						$dateHS = date("Y/m/d", $timestampHS);
						if ($dateHS == $tmpDate){
							$NbHeureSuppJ += $rowHS[1];
							$NbHeureSuppN += $rowHS[2];
						}
					}
				}
				
				//Recherche si en heure supp en cours ce jour-ci
				$NbHeureSuppECJ =0;
				$NbHeureSuppECN =0;
				if ($nbHeureSuppEC>0){
					mysqli_data_seek($heureSuppEC,0);
					while($rowHSEC=mysqli_fetch_array($heureSuppEC)) {
						$tabDateHSEC = explode('-', $rowHSEC[0]);
						$timestampHSEC = mktime(0, 0, 0, $tabDateHSEC[1], $tabDateHSEC[2], $tabDateHSEC[0]);
						$dateHSEC = date("Y/m/d", $timestampHSEC);
						if ($dateHSEC == $tmpDate){
							$NbHeureSuppECJ += $rowHSEC[1];
							$NbHeureSuppECN += $rowHSEC[2];
						}
					}
				}
				
				//Recherche si en formation ce jour-ci
				if ($nbformationJour>0){
					mysqli_data_seek($formationJour,0);
					while($rowFormation=mysqli_fetch_array($formationJour)){
						$tabDateForm = explode('-', $rowFormation[2]);
						$timestampForm = mktime(0, 0, 0, $tabDateForm[1], $tabDateForm[2], $tabDateForm[0]);
						$dateForm = date("Y/m/d", $timestampForm);
						if ($dateForm == $tmpDate){
							$FormationON="Oui";
							$NbForVac =$rowFormation[0];
							$NbForHorsVac =$rowFormation[1];
							$NbFor = $NbForVac;
							$ClassDiv ="class='rempliAvecFormation'";
							break;
						}
					}
				}
				if ($FormationON=="Non"){$NbFor =0;}
				
				
				if ($nbVacationJour>0){
					mysqli_data_seek($vacationJour,0);
					$PrestaDuJour = "";
					while($rowPlanning=mysqli_fetch_array($vacationJour)){
						$tabDateVac = explode('-', $rowPlanning[5]);
						$timestampVac = mktime(0, 0, 0, $tabDateVac[1], $tabDateVac[2], $tabDateVac[0]);
						$dateVac = date("Y/m/d", $timestampVac);
						if ($dateVac == $tmpDate){
							$bValide = 1;
							$PrestaDuJour = $rowPlanning[6];
							$Couleur = " style=\"background-color:#888888;\"";
							if ($rowPlanning[6] == $PrestationSelect){
								if ($rowPlanning[11] == 0){$Couleur = " style=\"background-color:#ff4723;\"";}
								else{$Couleur = " style=\"background-color:#00ef02;\"";}
							}
							$Commentaire = $rowPlanning[4];
							$CelPlanning=$rowPlanning[0];
							$ClassDiv = "class='rempliSansFormation'";
							if ($rowPlanning[2] ==1){
								$Vacation .=$rowPlanning[3];
								if ($rowPlanning[11] == 0){
									if ($row['TempsPartiel'] == 1){
										if ($nbTempsPartiel > 0){
											mysqli_data_seek($TempsPartiel,0);
											while($rowTempsPartiel=mysqli_fetch_array($TempsPartiel)) {
												//Récupérer NbHeures prévu pour cette personne, cette vacation, ce jour de la semaine en temps partiel
												if($rowTempsPartiel[1] == $rowPlanning[12] && $rowTempsPartiel[2] == $jour){
													$NbHeureJ = $rowTempsPartiel[3];
													$NbHeureEJ = $rowTempsPartiel[4];
													$NbHeureEN = $rowTempsPartiel[5];
													$NbHeureP = $rowTempsPartiel[6];
													if ($NbHeureJ > 0){
														$NbHeureJ = $NbHeureJ - $NbForVac;
													}
													elseif ($NbHeureEJ > 0){
														$NbHeureEJ = $NbHeureEJ - $NbForVac;
													}
													if($NbFor>=7){$NbHeureJ=0;}
													$NbHeureJ += $NbHeureSuppJ;
													$NbHeureEN += $NbHeureSuppN;
												}
											}
										}
									}
									else{
										if ($nbHeuresPrevues > 0){
											mysqli_data_seek($HeuresPrevues,0);
											while($rowHeuresPrevues=mysqli_fetch_array($HeuresPrevues)) {
												//Récupérer NbHeures prévu pour cette prestation, cette vacation, ce jour de la semaine
												if($rowHeuresPrevues[0] == $PrestaDuJour && $rowHeuresPrevues[1] == $rowPlanning[12] && $rowHeuresPrevues[2] == $jour){
													$NbHeureJ = $rowHeuresPrevues[3];
													$NbHeureEJ = $rowHeuresPrevues[4];
													$NbHeureEN = $rowHeuresPrevues[5];
													$NbHeureP = $rowHeuresPrevues[6];
													if ($NbHeureJ > 0){
														$NbHeureJ = $NbHeureJ - $NbForVac;
													}
													elseif ($NbHeureEJ > 0){
														$NbHeureEJ = $NbHeureEJ - $NbForVac;
													}
													if($NbFor>=7){$NbHeureJ=0;}
													$NbHeureJ += $NbHeureSuppJ;
													$NbHeureEN += $NbHeureSuppN;
												}
											}
										}
									}
								}
							}
							else{
								$Absence .=$rowPlanning[3];
								if ($rowPlanning[0] <> ""){$NbFor =0;}
								$NbHeureJ = 0;
								$NbHeureEJ = 0;
								$NbHeureEN = 0;
								$NbHeureP = 0;
							}
							if ($rowPlanning[11] == 0){
								$Etat = "Statut : A valider <br/>";
								$bValide = 1;
							}
							else{
								$NbFor = $rowPlanning[13];
								$NbHeureJ = $rowPlanning[7];
								$NbHeureEJ = $rowPlanning[8];
								$NbHeureEN = $rowPlanning[9];
								$NbHeureP = $rowPlanning[10];
								$Etat = "Statut : Validé <br/>";
								$bValide = 1;
							}
							break;
						}
					}
				}
				if ($Couleur == ""){
					$FormationON = "";
					$NbFor ="";
					if (jour_ferie($timestamp)){
						$ClassDiv ="class='weekFerie'";
					}
					else{
						$ClassDiv ="class='semaine'";
					}
				}

				$NbHeures = "Nombre d'heures réalisés : <br/>";
				$NbHeures .= "<table>";
				$NbHeures .= "<tr><td style='border:1px solid black;' width='40 px'>J</td><td style='border:1px solid black;' width='40 px'>For</td><td width='40 px'>EJ</td><td style='border:1px solid black;' width='40 px'>EN</td><td style='border:1px solid black;' width='40 px'>Pause</td></tr>";
				$NbHeures .= "<tr><td style='border:1px solid black;' width='40 px'>".$NbHeureJ."</td><td style='border:1px solid black;' width='40 px'>".$NbFor."</td><td style='border:1px solid black;' width='40 px'>".$NbHeureEJ."";
				$NbHeures .= "</td><td style='border:1px solid black;' width='40 px'>".$NbHeureEN."</td><td style='border:1px solid black;' width='40 px'>".$NbHeureP."</td></tr></table>";
				
				//Recherche si appartient à cette prestation ce jour -ci
				$onClick="";
				$Prestations="Prestation(s):<br/>";
				$contenu ="";
				if ($nbprestaJour>0){
					mysqli_data_seek($prestaJour,0);
					while($rowPrestaJour=mysqli_fetch_array($prestaJour)){
						$tabDate2 = explode('-', $rowPrestaJour[2]);
						if ($tabDate2[0] > 2037){$tabDate2[0]=2037;}
						$timestamp2 = mktime(0, 0, 0, $tabDate2[1], $tabDate2[2], $tabDate2[0]);
						$dateDebutReq = date("Y/m/d", $timestamp2);
						$tabDate2 = explode('-', $rowPrestaJour[3]);
						if ($tabDate2[0] > 2037){$tabDate2[0]=2037;}
						$timestamp2 = mktime(0, 0, 0, $tabDate2[1], $tabDate2[2], $tabDate2[0]);
						$dateFinReq = date("Y/m/d", $timestamp2);
						if ($dateDebutReq <= $tmpDate && $dateFinReq >= $tmpDate){
							if ($rowPrestaJour[0]==$PrestationSelect){
								$tabDateTransfert = explode('/', $tmpDate);
								$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
								$onClick="onclick='javascript:OuvreFenetreModifPointage(".$PrestationSelect.",".$Id_Personne.",".$timestampTransfert.",".$dateEnvoi.",".$PoleSelect.",\"".$ltri."\")'";
							}
						}
					}
				}
				if ($onClick==""){
					$tabDateTransfert = explode('/', $tmpDate);
					$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
					$onClick="onclick='javascript:OuvreFenetreModifPointage(".$PrestationSelect.",".$Id_Personne.",".$timestampTransfert.",".$dateEnvoi.",".$PoleSelect.")'";
					$contenu = "";
					$Couleur = " style=\"background-color:black\"";
				}
				else{
					$contenu = $CelPlanning;
				}
				//Accès en lecture ou écriture en fonction du poste
				$info = "";
				$id = "";
				if ($b_acces == 0){
					$onClick = "";
				}
				else{
					if (($contenu == "" && $NbFor == 0 && $bValide ==0) || ($PrestaDuJour <> $PrestationSelect)){
						$onClick = "";
						$NbHeures = "";
					}
						
				}
				$info = "<span>Personne : ".$row[1]."<br/>Date : ".$dateAffichage."<br/>----------------------------------<br/>Absence/Vacation : ".$Vacation.$Absence."<br/>";
				$info .= $NbHeures.$Etat."";
				if ($b_acces > 0){
					$info .= "----------------------------------<br/>Commentaire : ".$Commentaire."";
				}
				if($NbHeureSuppECJ > 0 || $NbHeureSuppECN > 0){
					$nbH = $NbHeureSuppECJ + $NbHeureSuppECN;
					$info .= "<br/>----------------------------------<br/>Heures supp. J en cours de validation : ".$NbHeureSuppECJ."h<br/>Heures supp. N en cours de validation : ".$NbHeureSuppECN."h";
					
					$Couleur=substr($Couleur,0,-1);
					$emplacement = "'../../Images/InfosHS.gif'";
					$Couleur.="background-image:url(".$emplacement.");\"";
				
				}
				$info .= "</span>";
				$id = "id='leHover'";
							
				//Cellule finale
				echo "<td ".$id." ".$ClassDiv." ".$Couleur." ".$onClick.">".$contenu."".$info."</td>";
				//Jour suivant
				$tabDate = explode('/', $tmpDate);
				$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
				$tmpDate = date("Y/m/d", $timestamp);
			}

			//Case à cocher
			if ($b_acces == 1){
				echo "<td class='checkbox2'><input type='checkbox' name='check_".$Id_Personne."' value=''></td>";
			}
			//Fichier Excel
			echo "<td>";
			echo "<a href='javascript:OuvreFenetrePointageIndividuelExport(".$PrestationSelect.",".$timestampTransfertPI.",".$PoleSelect.",".$Id_Personne.");'>";
			echo "<img src='../../Images/excel2.gif' border='0' alt='Excel' title='Pointage individuel'>";
			echo "</a>";
			echo "</td>";
			//Temps partiel
			echo "<td width='300px'>";
			$couleur="black";
			if ($row['TempsPartiel'] == 1){$couleur="#11d818";}
			echo "<a style='text-decoration:none;color:".$couleur.";' class='Bouton' href='javascript:OuvreFenetreTempsPartiel(".$Id_Personne.",".$b_acces.")'>&nbsp;Tps partiel ou forfait&nbsp;</a>";
			echo "</a>";
			echo "</td>";
			echo "</tr>";
		}
	 }
	?>
</table>
</form>
</body>
</html>
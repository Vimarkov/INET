<?php
require("../../Menu.php");
?>
<script type="text/javascript" src="Liste_NewPERFOS.js"></script>
<?php
//Vérification des droits de lecture, écriture, administration
$DroitAjout=false;
$resultDroits=mysqli_query($bdd,"SELECT MIN(Id_Poste) FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne']);
$nbDroits=mysqli_num_rows($resultDroits);
$rowDroits=mysqli_fetch_array($resultDroits);
if($rowDroits[0]<3 || $rowDroits[0]==35){$DroitAjout=true;}

$resultDroitsPresta=mysqli_query($bdd,"SELECT MIN(Id_Poste), Id_Prestation, Id_Pole FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne']." GROUP BY Id_Prestation, Id_Pole");
$nbDroitsPresta=mysqli_num_rows($resultDroitsPresta);

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
?>

<?php Ecrire_Code_JS_Init_Date(); ?>
<form class="test" method="POST" action="Liste_NewPERFOS.php">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td colspan="3">
			<table class="GeneralPage" style="width:100%; border-spacing:0;background-color:#42d3d6;">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">
						<?php
							echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PERFOS/Tableau_De_Bord.php'>";
							if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
							else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
							echo "</a>";
							echo "&nbsp;&nbsp;&nbsp;";
							if($_SESSION["Langue"]=="FR"){echo "SQCDPF # Rapports";}
							else{echo "SQCDPF # Reports";}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"/></tr>
	
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td width=30%>				
				&nbsp; Prestation :
				<select class="prestation" name="prestations" onchange="submit();">
				<?php
				if($IdPersonneConnectee == 1351 || $IdPersonneConnectee == 2526 || DroitsFormation1Plateforme(1,array($IdPosteCoordinateurSecurite))){
					$req = "SELECT DISTINCT Id_Prestation, 
							(SELECT LEFT(Libelle,7) FROM new_competences_prestation ";
					$req .= "WHERE new_competences_prestation.Id = new_competences_personne_poste_prestation.Id_Prestation) AS NomPrestation
									FROM new_competences_personne_poste_prestation 
									LEFT JOIN new_competences_prestation 
									ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id 
									WHERE new_competences_prestation.Id_Plateforme=1 
							ORDER BY NomPrestation ";
				}
				else{
				$req = "SELECT distinct(new_competences_personne_poste_prestation.Id_Prestation), (SELECT LEFT(Libelle,7) FROM new_competences_prestation ";
				$req .= "WHERE new_competences_prestation.Id = new_competences_personne_poste_prestation.Id_Prestation) AS NomPrestation FROM new_competences_personne_poste_prestation WHERE ";
				$req .= "new_competences_personne_poste_prestation.Id_Personne=".$_SESSION['Id_Personne']." ORDER BY NomPrestation;";
				}
				$resultPrestation=mysqli_query($bdd,$req);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationSelect = 0;
				$Selected = "";
				if ($nbPrestation > 0)
				{
					if (!empty($_GET['IdPrestationSelect'])){
						echo "<option value='0' Selected></option>";
						if ($PrestationSelect == 0){$PrestationSelect = $_GET['IdPrestationSelect'];}
						while($row=mysqli_fetch_array($resultPrestation))
						{
							if ($row[0] == $_GET['IdPrestationSelect']){
								$Selected = "Selected";
							}
							echo "<option value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
							$Selected = "";
						}
					}
					elseif (!empty($_POST['prestations'])){
						echo "<option value='0' Selected></option>";
						if ($PrestationSelect == 0){$PrestationSelect = $_POST['prestations'];}
						while($row=mysqli_fetch_array($resultPrestation))
						{
							if ($row[0] == $_POST['prestations']){
								$Selected = "Selected";
							}
							echo "<option value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
							$Selected = "";
						}
					}
					else{
						echo "<option value='0' Selected></option>";
						$PrestationSelect == 0;
						while($row=mysqli_fetch_array($resultPrestation))
						{
							echo "<option value='".$row[0]."'>".$row[1]."</option>";
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
				if($IdPersonneConnectee == 1351 || $IdPersonneConnectee == 2526 || DroitsFormation1Plateforme(1,array($IdPosteCoordinateurSecurite))){
					$reqPole = "SELECT DISTINCT new_competences_personne_poste_prestation.Id_Pole, ";
					$reqPole .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id = new_competences_personne_poste_prestation.Id_Pole) AS LibellePole ";
					$reqPole .= "FROM new_competences_personne_poste_prestation WHERE ";
					$reqPole .= "new_competences_personne_poste_prestation.Id_Prestation=".$PrestationSelect." ORDER BY LibellePole;";
				}
				else{
					$reqPole = "SELECT DISTINCT new_competences_personne_poste_prestation.Id_Pole, ";
					$reqPole .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id = new_competences_personne_poste_prestation.Id_Pole) AS LibellePole ";
					$reqPole .= "FROM new_competences_personne_poste_prestation WHERE ";
					$reqPole .= "new_competences_personne_poste_prestation.Id_Personne=".$_SESSION['Id_Personne']." AND new_competences_personne_poste_prestation.Id_Poste ";
					$reqPole .= "AND new_competences_personne_poste_prestation.Id_Prestation=".$PrestationSelect." ORDER BY LibellePole;";
				}
				$resultPole=mysqli_query($bdd,$reqPole);
				$nbPole=mysqli_num_rows($resultPole);
				
				$PoleSelect = 0;
				$Selected = "";
				if ($nbPole > 0)
				{
					echo "<option value='0' Selected></option>";
					if (!empty($_GET['Id_Pole'])){
						if ($PoleSelect == 0){$PoleSelect = $_GET['Id_Pole'];}
						while($row=mysqli_fetch_array($resultPole))
						{
							if ($row[0] == $_GET['Id_Pole']){$Selected = "Selected";}
							echo "<option value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
							$Selected = "";
						}
					}
					elseif (!empty($_POST['pole'])){
						if ($PoleSelect == 0){$PoleSelect = $_POST['pole'];}
						while($row=mysqli_fetch_array($resultPole))
						{
							if ($row[0] == $_POST['pole']){$Selected = "Selected";}
							echo "<option value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
							$Selected = "";
						}
					}
					else{
						while($row=mysqli_fetch_array($resultPole))
						{
							if ($PoleSelect == 0){$PoleSelect = 0;}
							echo "<option value='".$row[0]."'>".$row[1]."</option>";
						}
					}
				 }
				 ?>
				</select>
			</td>
			<td width=25%>
				&nbsp;
				Date :
				<?php
					$dateEnvoi =0;
					$dateRequete = "";
					if (!empty($_GET['DateSelect'])){
						$dateEnvoi = $_GET['DateSelect'];
						if  ($dateEnvoi <> ""){
							if ($NavigOk ==1){
								$dateDebut = date("Y-m-d",$_GET['DateSelect']);
								
								$tabDateDebut = explode('-', $dateDebut);
								$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[2], $tabDateDebut[0]);
							}
							else{
								$dateDebut = date("d/m/Y",$_GET['DateSelect']);
								
								$tabDateDebut = explode('/', $dateDebut);
								$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
							}
							$dateRequete = date("Y-m-d",$timestampDebut);
						}
						else{
							$dateDebut = "";
							$dateRequete = "";
						}
					}
					else{
						if (!empty($_POST['DateDeDebut'])){
							if  ($_POST['DateDeDebut'] <> ""){
								if ($NavigOk ==1){
									$dateDebut = $_POST['DateDeDebut'];
									$tabDateDebut = explode('-', $dateDebut);
									$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[2], $tabDateDebut[0]);
									$dateEnvoi = $timestampDebut;
									$dateRequete = date("Y-m-d",$timestampDebut);
								}
								else{
									$dateDebut = $_POST['DateDeDebut'];
									$tabDateDebut = explode('/', $dateDebut);
									$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
									$dateEnvoi = $timestampDebut;
									$dateRequete = date("Y-m-d",$timestampDebut);
								}
							}
							else{
								$dateDebut = "";
								$dateEnvoi = 0;
								$dateRequete = "";
							}
						}
						else{
							$dateDebut = "";
							$dateEnvoi = 0;
							$dateRequete = "";
						}
					}
				?>
				
				<input type="date" style="text-align:center;" name="DateDeDebut" size="10" value="<?php echo $dateDebut; ?>">
				<input class="Bouton" name="BtnDateDebut" size="10" type="submit" value="Valider">
			</td>
		</tr>
	</table>
	
	<!-- 	 Récapitulatif -->
	<tr>
		<td>
			<table  width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr>
					<td width=30%>
						<label>Date début : </label>						
						<input id="dateDebut" type="date" />
						<label>Date de fin : </label>
						<input id="dateFin" type="date" /> 
<!-- 						date de début et date de fin à allimenter pour la fonction -->
  					<a style='text-decoration:none;' class='Bouton' href='javascript:RecapitulatifExcel()'>&nbsp;Récapitulatif&nbsp;</a>
					</td>
				</tr>
				</table>
			</td>
	</tr>
		
	<tr><td height="4"/></tr>
	<tr>
		<td align="center">
			<?php if ($DroitAjout){
				echo "<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjoutperfos(".$_SESSION['Id_Personne'].",".$PrestationSelect.",".$PoleSelect.",".$dateEnvoi.")'>&nbsp;Nouveau SQCDPF&nbsp;</a>";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";				
				echo "<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreDestinataireperfos(".$PrestationSelect.",".$PoleSelect.",".$_SESSION['Id_Personne'].")'>&nbsp;Destinataires Mail&nbsp;</a>";
			}
				$req = "SELECT distinct(new_competences_personne_poste_prestation.Id_Prestation), (SELECT new_competences_prestation.Libelle FROM new_competences_prestation ";
				$req .= "WHERE new_competences_prestation.Id = new_competences_personne_poste_prestation.Id_Prestation) AS NomPrestation FROM new_competences_personne_poste_prestation WHERE ";
				$req .= "new_competences_personne_poste_prestation.Id_Personne=".$_SESSION['Id_Personne']." AND new_competences_personne_poste_prestation.Id_Poste IN (2,35) ORDER BY NomPrestation;";
				
				$resultCoorE=mysqli_query($bdd,$req);
				$nbCoorE=mysqli_num_rows($resultCoorE);
				
				if($nbCoorE>0){
					echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					echo "<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreFrequence(".$_SESSION['Id_Personne'].")'>&nbsp;Configurer&nbsp;</a>";
				}
			?>
			
		</td>
	</tr>
	<tr><td height="4"/></tr>
	<?php
	if($nbDroits>0)
	{
	?>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="70%">
				<tr align="center">
					<td class="EnTeteTableauCompetences" width="10%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date</td>
					<td class="EnTeteTableauCompetences" width="10%">Vacation</td>
					<td class="EnTeteTableauCompetences" width="10%">Prestation</td>
					<td class="EnTeteTableauCompetences" width="10%">Pôle</td>
					<td class="EnTeteTableauCompetences" width="15%">Créateur</td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="6%">Nbr. points chauds</td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="3%">S</td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="3%">Q</td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="3%">C</td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="3%">D</td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="3%">P</td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="3%">F</td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="6%"></td>
				</tr>
				<?php
					$req = "SELECT new_v2sqcdpf.Id, new_v2sqcdpf.DateSQCDPF,Vacation, new_v2sqcdpf.Id_Prestation, new_v2sqcdpf.Id_Pole, new_v2sqcdpf.Id_Personne1, new_v2sqcdpf.Id_Personne2, new_v2sqcdpf.Id_Personne3, new_v2sqcdpf.Id_Personne4, ";
					$req .= "(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id = new_v2sqcdpf.Id_Prestation) AS Prestation, ";
					$req .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id =new_v2sqcdpf.Id_Pole) AS Pole, ";
					$req .= "new_v2sqcdpf.S_J_1, new_v2sqcdpf.Q_J_1, new_v2sqcdpf.C_J_1, new_v2sqcdpf.D_J_1, new_v2sqcdpf.P_J_1, new_v2sqcdpf.F_J_1 ";
					$req .= "FROM new_v2sqcdpf ";
					$req .= "WHERE ";
					if ($PrestationSelect <> 0){
						$req .= "new_v2sqcdpf.Id_Prestation =".$PrestationSelect." AND ";
						if ($PoleSelect <> 0){
							$req .= "new_v2sqcdpf.Id_Pole =".$PoleSelect." AND ";
						}
						else{
							$reqPrestaPoste = "SELECT DISTINCT Id_Prestation, Id_Pole FROM new_competences_personne_poste_prestation ";
							$reqPrestaPoste .= "WHERE Id_Personne=".$_SESSION['Id_Personne']." AND Id_Prestation=".$PrestationSelect.";";
							$resultPrestaPoste=mysqli_query($bdd,$reqPrestaPoste);
							$nbPrestaPoste=mysqli_num_rows($resultPrestaPoste);
							if($nbPrestaPoste > 0){
								if($nbPrestaPoste > 1){$req .= "(";}
								while($rowPrestaPoste=mysqli_fetch_array($resultPrestaPoste)){
									$req .= "new_v2sqcdpf.Id_Pole =".$rowPrestaPoste['Id_Pole']." OR ";
								}
								$req = substr($req,0,-3);
								if($nbPrestaPoste > 1){$req .= ")";}
								$req .= "AND ";
							}
						}
					}
					else{
						if($IdPersonneConnectee == 1351 || $IdPersonneConnectee == 2526 || DroitsFormation1Plateforme(1,array($IdPosteCoordinateurSecurite))){
							$reqPrestaPoste = "SELECT DISTINCT Id_Prestation, Id_Pole 
											FROM new_competences_personne_poste_prestation 
											LEFT JOIN new_competences_prestation 
											ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id 
											WHERE new_competences_prestation.Id_Plateforme=1 ";
							$resultPrestaPoste=mysqli_query($bdd,$reqPrestaPoste);
							$nbPrestaPoste=mysqli_num_rows($resultPrestaPoste);
							if($nbPrestaPoste > 0){
								if($nbPrestaPoste > 1){$req .= "(";}
								while($rowPrestaPoste=mysqli_fetch_array($resultPrestaPoste)){
									$req .= "(new_v2sqcdpf.Id_Prestation =".$rowPrestaPoste['Id_Prestation']." AND new_v2sqcdpf.Id_Pole =".$rowPrestaPoste['Id_Pole'].") OR ";
								}
								$req = substr($req,0,-3);
								if($nbPrestaPoste > 1){$req .= ")";}
								$req .= "AND ";
							}
						}
						else{
							$reqPrestaPoste = "SELECT DISTINCT Id_Prestation, Id_Pole FROM new_competences_personne_poste_prestation ";
							$reqPrestaPoste .= "WHERE Id_Personne=".$_SESSION['Id_Personne'].";";
							$resultPrestaPoste=mysqli_query($bdd,$reqPrestaPoste);
							$nbPrestaPoste=mysqli_num_rows($resultPrestaPoste);
							if($nbPrestaPoste > 0){
								if($nbPrestaPoste > 1){$req .= "(";}
								while($rowPrestaPoste=mysqli_fetch_array($resultPrestaPoste)){
									$req .= "(new_v2sqcdpf.Id_Prestation =".$rowPrestaPoste['Id_Prestation']." AND new_v2sqcdpf.Id_Pole =".$rowPrestaPoste['Id_Pole'].") OR ";
								}
								$req = substr($req,0,-3);
								if($nbPrestaPoste > 1){$req .= ")";}
								$req .= "AND ";
							}
						}
					}
					if ($dateRequete <> ""){
						$req .= "new_v2sqcdpf.dateSQCDPF ='".$dateRequete."' ";
					}
					
					if (substr($req,-4) =="AND "){
						$req = substr($req,0,-4);
					}
					$req .= "ORDER BY datesqcdpf DESC ";
					$req .= "LIMIT 0,100;";

					$resultnew_perfos=mysqli_query($bdd,$req);
					$nbnew_perfos=mysqli_num_rows($resultnew_perfos);
					
					$rouge = "#fa0000";
					$vert = "#00b050";

					if($nbnew_perfos > 0){
						while($rownew_perfos=mysqli_fetch_array($resultnew_perfos)){
							$bAffiche = 0;
							$couleurS = "#ffffff";
							$couleurQ = "#ffffff";
							$couleurC = "#ffffff";
							$couleurD = "#ffffff";
							$couleurP = "#ffffff";
							$couleurF = "#ffffff";
							if($rownew_perfos['S_J_1'] == "1"){$couleurS = $vert;}
							elseif($rownew_perfos['S_J_1'] == "2"){$couleurS = $rouge;}
							if($rownew_perfos['Q_J_1'] == "1"){$couleurQ = $vert;}
							elseif($rownew_perfos['Q_J_1'] == "2"){$couleurQ = $rouge;}
							if($rownew_perfos['C_J_1'] == "1"){$couleurC = $vert;}
							elseif($rownew_perfos['C_J_1'] == "2"){$couleurC = $rouge;}
							if($rownew_perfos['D_J_1'] == "1"){$couleurD = $vert;}
							elseif($rownew_perfos['D_J_1'] == "2"){$couleurD = $rouge;}
							if($rownew_perfos['P_J_1'] == "1"){$couleurP = $vert;}
							elseif($rownew_perfos['P_J_1'] == "2"){$couleurP = $rouge;}
							if($rownew_perfos['F_J_1'] == "1"){$couleurF = $vert;}
							elseif($rownew_perfos['F_J_1'] == "2"){$couleurF = $rouge;}
							
							$NomPrenom = "";
							$reqPersonne = "SELECT new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=".$rownew_perfos['Id_Personne1']; 
							$resulPersonne=mysqli_query($bdd,$reqPersonne);
							$nbPersonne=mysqli_num_rows($resulPersonne);
							if($nbPersonne>0){
								$rowPersonne=mysqli_fetch_array($resulPersonne);
								$NomPrenom = $rowPersonne['Nom']." ".$rowPersonne['Prenom'];
							}
							echo "<tr>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;'>";
							echo "<a href='javascript:OuvreFenetreConsultperfos(".$rownew_perfos['Id'].");'>";
							echo "<img src='../../Images/Loupe.gif' border='0' alt='Consulter' title='Consulter'>";
							echo "</a>&nbsp;&nbsp;&nbsp;&nbsp;";
							echo AfficheDateJJ_MM_AAAA($rownew_perfos['DateSQCDPF'])."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;'>".$rownew_perfos['Vacation']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;'>".$rownew_perfos['Prestation']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;'>".$rownew_perfos['Pole']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;'>".$NomPrenom."</td>";
							
							$reqPointChaud = "SELECT Id FROM new_action WHERE Id_Prestation=".$rownew_perfos['Id_Prestation']." AND Id_Pole=".$rownew_perfos['Id_Pole']." AND DateCreation='".$rownew_perfos['DateSQCDPF']."'";
							$resulPointChaud=mysqli_query($bdd,$reqPointChaud);
							$nbPointChaud=mysqli_num_rows($resulPointChaud);
							if($nbPointChaud > 0){
								echo "<td style='border-bottom:1px #d9d9d7 solid;' align='center'>".$nbPointChaud."</td>";
							}
							else{
								echo "<td style='border-bottom:1px #d9d9d7 solid;' align='center'></td>";
							}
							echo "<td style='border:1px #d9d9d7 solid;color:".$couleurS.";' align='center' bgcolor='".$couleurS."'>".$rownew_perfos['S_J_1']."</td>";
							echo "<td style='border:1px #d9d9d7 solid;color:".$couleurQ.";' align='center' bgcolor='".$couleurQ."'>".$rownew_perfos['Q_J_1']."</td>";
							echo "<td style='border:1px #d9d9d7 solid;color:".$couleurC.";' align='center' bgcolor='".$couleurC."'>".$rownew_perfos['C_J_1']."</td>";
							echo "<td style='border:1px #d9d9d7 solid;color:".$couleurD.";' align='center' bgcolor='".$couleurD."'>".$rownew_perfos['D_J_1']."</td>";
							echo "<td style='border:1px #d9d9d7 solid;color:".$couleurP.";' align='center' bgcolor='".$couleurP."'>".$rownew_perfos['P_J_1']."</td>";
							echo "<td style='border:1px #d9d9d7 solid;color:".$couleurF.";' align='center' bgcolor='".$couleurF."'>".$rownew_perfos['F_J_1']."</td>";
							echo "<td style='border-bottom:1px #d9d9d7 solid;'>";
							$DroitMail = false;
							if ($nbDroitsPresta > 0){
								mysqli_data_seek($resultDroitsPresta,0);
								while($rowDroitsPresta=mysqli_fetch_array($resultDroitsPresta))
								{
									if (($rowDroitsPresta[0] < 3 || $rowDroitsPresta[0] == 35) && $rowDroitsPresta['Id_Prestation'] == $rownew_perfos['Id_Prestation'] && $rowDroitsPresta['Id_Pole'] == $rownew_perfos['Id_Pole']){
										$DroitMail = true;
									}
								}
							}
							if ($DroitMail){
								echo "&nbsp;&nbsp;<a href='javascript:OuvreFenetreModifperfos(".$rownew_perfos['Id'].",".$_SESSION['Id_Personne'].",".$PrestationSelect.",".$PoleSelect.",".$dateEnvoi.")'>";
								echo "<img src='../../Images/Modif.gif' border='0' alt='Modifier' title='Modifier'>";
								echo "</a>&nbsp;";
								echo "<a href='javascript:OuvreFenetreSupprperfos(".$rownew_perfos['Id'].",".$_SESSION['Id_Personne'].",".$PrestationSelect.",".$PoleSelect.",".$dateEnvoi.")'>";
								echo "<img src='../../Images/Suppression.gif' border='0' alt='Supprimer' title='Supprimer'>";
								echo "</a>&nbsp;";
								echo "<a href='javascript:EnvoyerMailperfos(".$rownew_perfos['Id'].",".$_SESSION['Id_Personne'].")'>";
								echo "<img src='../../Images/email.gif' border='0' alt='Envoyer par Email' title='Envoyer par Email'>";
								echo "</a>";
							}
							echo "</td>";
							
						}
					}
				?>
			</table>
		</td>
	</tr>
	<tr height='15'><td>Seuls les 100 premiers SQCDPF vous concernant sont affichés.</td></tr>
<?php
	}			//Fin vérification des droits
	else
	{
?>
		<tr><td class="Erreur">Vous n'avez pas les droits pour afficher le contenu de ce dossier.</td></tr>
<?php
	}
?>
</table>
</form>

<?php
	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
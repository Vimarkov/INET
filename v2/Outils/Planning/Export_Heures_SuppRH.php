<?php
require("../../Menu.php");
?>
<script language="javascript">
	function ExportHS(Du,Au, Pole,Prestation,Id_Personne)
		{window.open("ExportHS.php?Prestation="+Prestation+"&Pole="+Pole+"&Id_Personne="+Id_Personne+"&Du="+Du+"&Au="+Au,"PageExcel","status=no,menubar=no,width=900,height=450");}
</script>
<?php
//Vérification des droits de lecture, écriture, administration
$DroitAjout=false;
$resultDroits=mysqli_query($bdd,"SELECT MIN(Id_Poste) FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne']);
$nbDroits=mysqli_num_rows($resultDroits);
$rowDroits=mysqli_fetch_array($resultDroits);
if($rowDroits[0]<3){$DroitAjout=true;}
?>

<form class="test" action="Export_Heures_SuppRH.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>
			<table class="GeneralPage" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="TitrePage">Ressources Humaines # Export Heures supplémentaires</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
	<table width="50%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td width="5%">
				&nbsp; Prestation :
			</td>
			<td width="10%">
				<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
				<?php
				$req = "SELECT Id,Libelle FROM new_competences_prestation ";
				$req .= "WHERE new_competences_prestation.Id_Plateforme = 1 ORDER BY Libelle;";
				
				$resultPrestation=mysqli_query($bdd,$req);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationSelect = 0;
				$Selected = "";
				if ($nbPrestation > 0){
					if (!empty($_GET['IdPrestationSelect'])){
						echo "<option name='0' value='0' Selected></option>";
						if ($PrestationSelect == 0){$PrestationSelect = $_GET['IdPrestationSelect'];}
						while($row=mysqli_fetch_array($resultPrestation))
						{
							if ($row['Id'] == $_GET['IdPrestationSelect']){
								$Selected = "Selected";
							}
							echo "<option name='".$row['Id']."' value='".$row['Id']."' ".$Selected.">".$row['Libelle']."</option>";
							$Selected = "";
						}
					}
					elseif (!empty($_POST['prestations'])){
						echo "<option name='0' value='0' Selected></option>";
						if ($PrestationSelect == 0){$PrestationSelect = $_POST['prestations'];}
						while($row=mysqli_fetch_array($resultPrestation))
						{
							if ($row['Id'] == $_POST['prestations']){
								$Selected = "Selected";
							}
							echo "<option name='".$row['Id']."' value='".$row['Id']."' ".$Selected.">".$row['Libelle']."</option>";
							$Selected = "";
						}
					}
					else{
						echo "<option name='0' value='0' Selected></option>";
						$PrestationSelect == 0;
						while($row=mysqli_fetch_array($resultPrestation))
						{
							echo "<option name='".$row['Id']."' value='".$row['Id']."'>".$row['Libelle']."</option>";
						}
					}
				 }
				 ?>
				</select>
			</td>
			<td width="5%">
				&nbsp; Pôle :
			</td>
			<td width="10%">
				<select class="pole" style="width:100px;" name="pole" onchange="submit();">
				<?php

				$reqPole = "SELECT DISTINCT new_competences_personne_poste_prestation.Id_Pole, ";
				$reqPole .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id = new_competences_personne_poste_prestation.Id_Pole) AS LibellePole ";
				$reqPole .= "FROM new_competences_personne_poste_prestation WHERE ";
				$reqPole .= " new_competences_personne_poste_prestation.Id_Prestation=".$PrestationSelect." ORDER BY LibellePole;";
				
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
				 else{
					echo "<option name='0' value='0' Selected></option>";
				 }
				 ?>
				</select>
			</td>
			<td width="5%">
				&nbsp; Personne :
			</td>
			<td width="10%">
				<select id="personne" style="width:100px;" name="personne" onchange="submit();">
					<option value='0'></option>
					<?php
						$personne="";
						if(isset($_POST['personne'])){$personne = $_POST['personne'];}
						$requetePersonne="SELECT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
						$requetePersonne.="FROM new_rh_etatcivil ";
						$requetePersonne.="ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
						$resultPersonne=mysqli_query($bdd,$requetePersonne);
						$NbPersonne=mysqli_num_rows($resultPersonne);
						while($rowPersonne=mysqli_fetch_array($resultPersonne))
						{
							echo "<option value='".$rowPersonne[0]."'";
							if ($personne == $rowPersonne[0]){echo " selected ";}
							echo ">".$rowPersonne[1]." ".$rowPersonne[2]."</option>\n";
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width="5%">
				&nbsp;
				Du :
			</td>
			<td width="10%">
				<?php
					$dateEnvoi =0;
					$dateRequete = "";
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
				?>
				<input type="date" style="text-align:center;" name="DateDeDebut" size="10" value="<?php echo $dateDebut; ?>">
			</td>
			<td width="5%">
				&nbsp; au :
			</td>
			<td width="10%">
				<?php
					$dateEnvoiFin =0;
					$dateRequeteFin = "";
					if (!empty($_POST['DateDeFin'])){
						if  ($_POST['DateDeFin'] <> ""){
							if ($NavigOk ==1){
								$dateFin = $_POST['DateDeFin'];
								$tabdateFin = explode('-', $dateFin);
								$timestampDebut = mktime(0, 0, 0, $tabdateFin[1], $tabdateFin[2], $tabdateFin[0]);
								$dateEnvoiFin = $timestampDebut;
								$dateRequeteFin = date("Y-m-d",$timestampDebut);
							}
							else{
								$dateFin = $_POST['DateDeFin'];
								$tabdateFin = explode('/', $dateFin);
								$timestampDebut = mktime(0, 0, 0, $tabdateFin[1], $tabdateFin[0], $tabdateFin[2]);
								$dateEnvoiFin = $timestampDebut;
								$dateRequeteFin = date("Y-m-d",$timestampDebut);
							}
						}
						else{
							$dateFin = "";
							$dateEnvoiFin = 0;
							$dateRequeteFin = "";
						}
					}
					else{
						$dateFin = "";
						$dateEnvoiFin = 0;
						$dateRequeteFin = "";
					}
				?>
				<input type="date" style="text-align:center;" name="DateDeFin" size="10" value="<?php echo $dateFin; ?>">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<?php
			if($_POST){
				if(isset($_POST['BtnExport'])){
					echo "<script>ExportHS('".$_POST['DateDeDebut']."','".$_POST['DateDeFin']."','".$_POST['pole']."','".$_POST['prestations']."','".$_POST['personne']."');</script>";
				}
			}
		?>
			<td colspan="6" align="center">
				<input class="Bouton" name="BtnExport" size="10" type="submit" value="Exporter">
			</td>
		</tr>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
</table>
</form>
<?php
	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
<?php
require("../../Menu.php");
?>
 <script>
jQuery(document).ready(function() {
	$("#org").jOrgChart({
		chartElement : '#chart',
		dragAndDrop  : true
	});
});
</script>
<form class="test" method="POST" action="Organigramme.php">

<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#64a8f2;">
	<tr>
		<td width="4"></td>
		<td class="TitrePage">Hiérarchie du personnel # Organigrammes</td>
	</tr>
</table>
<table style="width:100%; align:center;">
<tr><td height="4"></td></tr>
</table>
<table style="width:100%; border-pacing:0; align:center;" class="GeneralInfo">
	<tr>
		<td>
			<?php
			?>
			&nbsp; Plateforme :
			<select class="plateforme" name="plateforme" onchange="submit();">
			<?php
			$req = "SELECT Id, Libelle FROM new_competences_plateforme WHERE Id<>11 AND Id<>14 ORDER BY Libelle ;";
			
			$resultPlateforme=mysqli_query($bdd,$req);
			$nbPlateforme=mysqli_num_rows($resultPlateforme);
			
			$PlateformeSelect = 0;
			$Selected = "";
			if ($nbPlateforme > 0)
			{
				if (!empty($_POST['plateforme'])){
					if ($PlateformeSelect == 0){$PlateformeSelect = $_POST['plateforme'];}
					while($row=mysqli_fetch_array($resultPlateforme))
					{
						if ($row[0] == $_POST['plateforme']){
							$Selected = "Selected";
						}
						echo "<option name='".$row['Id']."' value='".$row['Id']."' ".$Selected.">".$row['Libelle']."</option>";
						$Selected = "";
					}
				}
				else{
					while($row=mysqli_fetch_array($resultPlateforme))
					{
						$Selected = "";
						if ($PlateformeSelect == 0){$PlateformeSelect = $row['Id'];}
						if ($row[0] == $PlateformeSelect){
							$Selected = "Selected";
						}
						echo "<option name='".$row['Id']."' value='".$row['Id']."' ".$Selected.">".$row['Libelle']."</option>";
					}
				}
			 }
			 ?>
			</select>
		</td>
		<td>
			<?php
			?>
			&nbsp; Prestation :
			<select class="prestation" name="prestations" onchange="submit();">
			<?php
			$req = "SELECT Id, Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id_Plateforme=".$PlateformeSelect." AND Active=0 ORDER BY Libelle;";
			
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
						echo "<option name='".$row['Id']."' value='".$row['Id']."' ".$Selected.">".$row['Libelle']."</option>";
						$Selected = "";
					}
				}
				else{
					echo "<option name='0' value='0'></option>";
					$PrestationSelect = 0;
					$Selected = "";
					while($row=mysqli_fetch_array($resultPrestation))
					{
						if ($PrestationSelect == 0){
							$PrestationSelect = $row['Id'];
							$Selected = "Selected";
						}
						echo "<option name='".$row['Id']."' value='".$row['Id']."' ".$Selected.">".$row['Libelle']."</option>";
						$Selected = "";
					}
				}
			 }
			 ?>
			</select>
		</td>
		<td>
			&nbsp; Pôle :
			<select class="pole" name="pole" onchange="submit();">
			<?php

			$reqPole = "SELECT Id, Libelle FROM new_competences_pole WHERE ";
			$reqPole .= "new_competences_pole.Id_Prestation=".$PrestationSelect." ORDER BY Libelle;";
			
			$resultPole=mysqli_query($bdd,$reqPole);
			$nbPole=mysqli_num_rows($resultPole);
			
			$PoleSelect = 0;
			$Selected = "";
			if ($nbPole > 0)
			{
				if (!empty($_POST['pole'])){
					if ($PoleSelect == 0){$PoleSelect = $_POST['pole'];}
					echo "<option name='0' value='0' Selected></option>";
					while($row=mysqli_fetch_array($resultPole))
					{
						if ($row[0] == $_POST['pole']){$Selected = "Selected";}
						echo "<option name='".$row['Id']."' value='".$row['Id']."' ".$Selected.">".$row['Libelle']."</option>";
						$Selected = "";
					}
				}
				else{
					echo "<option name='0' value='0'></option>";
					$PoleSelect = 0;
					while($row=mysqli_fetch_array($resultPole))
					{
						echo "<option name='".$row['Id']."' value='".$row['Id']."' ".$Selected.">".$row['Libelle']."</option>";
					}
				}
			 }
			 ?>
			</select>
		</td>
	</tr>
</table>
<table style="width:100%; align:center;">
	<tr align="center">
		<td align="center">
			<?php
				if ($PlateformeSelect > 0){
					//Récupérer le responsable Plateforme --> Il ne doit y avoir qu'un seul responsable au niveau 1
					$req = "SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom 
							FROM new_competences_personne_poste_plateforme
							LEFT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id = new_competences_personne_poste_plateforme.Id_Personne 
							WHERE new_competences_personne_poste_plateforme.Id_Poste = 9 AND new_competences_personne_poste_plateforme.Backup = 0 
							AND new_competences_personne_poste_plateforme.Id_Plateforme =".$PlateformeSelect." 
							AND new_rh_etatcivil.Id > 0 ";
					$resultPlateforme=mysqli_query($bdd,$req);
					$nbRespPlateforme=mysqli_num_rows($resultPlateforme);
					
					if ( $nbRespPlateforme > 0){
						$rowPlateforme = mysqli_fetch_array($resultPlateforme);
						echo "<ul id='org' style='display:none'>";
						echo "<li><font color=blue><i>UER</i></font><br/>".$rowPlateforme['Nom']." ".$rowPlateforme['Prenom'];
							//Récupérer le responsable Production et Qualité -->
							$req = "SELECT distinct(new_rh_etatcivil.Id) AS Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom, new_competences_personne_poste_prestation.Id_Poste ";
							$req .= "FROM new_competences_personne_poste_prestation ";
							$req .= "LEFT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id = new_competences_personne_poste_prestation.Id_Personne ";
							$req .= "LEFT JOIN new_competences_prestation ON new_competences_prestation.Id = new_competences_personne_poste_prestation.Id_Prestation ";
							$req .= "WHERE (new_competences_personne_poste_prestation.Id_Poste = 7 OR new_competences_personne_poste_prestation.Id_Poste = 8) AND new_competences_personne_poste_prestation.Backup = 0 ";
							$req .= "AND new_competences_prestation.Id_Plateforme =".$PlateformeSelect." ";
							$req .= "AND new_rh_etatcivil.Id > 0 ";
							if ($PrestationSelect > 0){$req .= "AND new_competences_personne_poste_prestation.Id_Prestation =".$PrestationSelect." ";}
							if ($PoleSelect > 0){$req .= "AND new_competences_personne_poste_prestation.Id_Pole =".$PoleSelect."";}
							$req .= ";";
							
							$resultRespProdQualite=mysqli_query($bdd,$req);
							$nbRespProdQualite=mysqli_num_rows($resultRespProdQualite);
							
							if ( $nbRespProdQualite > 0){
								echo "<ul>";
								mysqli_data_seek($resultRespProdQualite,0);
								while($rowRespProdQualite=mysqli_fetch_array($resultRespProdQualite)){
									$poste = "";
									if ($rowRespProdQualite['Id_Poste'] == '7'){$poste = "Responsable production";}
									else {$poste = "Responsable qualité plateforme";}
									echo "<li><font color=blue><i>".$poste."</i></font><br/>".$rowRespProdQualite['Nom']." ".$rowRespProdQualite['Prenom'];
											if ($rowRespProdQualite['Id_Poste'] == '7'){
												//Récupérer les responsables affaires
												$req = "SELECT distinct(new_rh_etatcivil.Id) AS Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom, new_competences_personne_poste_prestation.Id_Poste ";
												$req .= "FROM new_competences_personne_poste_prestation ";
												$req .= "LEFT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id = new_competences_personne_poste_prestation.Id_Personne ";
												$req .= "LEFT JOIN new_competences_prestation ON new_competences_prestation.Id = new_competences_personne_poste_prestation.Id_Prestation ";
												$req .= "WHERE new_competences_personne_poste_prestation.Id_Poste = 4  AND new_competences_personne_poste_prestation.Backup = 0 ";
												$req .= "AND new_competences_prestation.Id_Plateforme =".$PlateformeSelect." ";
												$req .= "AND new_rh_etatcivil.Id > 0 
														AND CONCAT(Id_Prestation,'_',Id_Pole) IN (SELECT CONCAT(Id_Prestation,'_',Id_Pole) FROM new_competences_personne_poste_prestation
														WHERE new_competences_personne_poste_prestation.Id_Poste = 7 AND new_competences_personne_poste_prestation.Backup = 0
														AND new_competences_personne_poste_prestation.Id_Personne=".$rowRespProdQualite['Id']."
														";
															if ($PrestationSelect > 0){$req .= "AND new_competences_personne_poste_prestation.Id_Prestation =".$PrestationSelect." ";}
															if ($PoleSelect > 0){$req .= "AND new_competences_personne_poste_prestation.Id_Pole =".$PoleSelect."";}
														$req.="
														)
												" ;
												if ($PrestationSelect > 0){$req .= "AND new_competences_personne_poste_prestation.Id_Prestation =".$PrestationSelect." ";}
												if ($PoleSelect > 0){$req .= "AND new_competences_personne_poste_prestation.Id_Pole =".$PoleSelect."";}
												$req .= ";";
												
												$resultRespAffaire=mysqli_query($bdd,$req);
												$nbRespAffaire=mysqli_num_rows($resultRespAffaire);
												
												if ( $nbRespAffaire > 0){
													echo "<ul>";
													mysqli_data_seek($resultRespAffaire,0);
													while($rowRespAffaire=mysqli_fetch_array($resultRespAffaire)){
														$poste = "Responsable projet";
														echo "<li><font color=blue><i>".$poste."</i></font><br/>".$rowRespAffaire['Nom']." ".$rowRespAffaire['Prenom'];
															//Récupérer les coordinateurs projet
															$req = "SELECT distinct(new_rh_etatcivil.Id), new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom, new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Id_Pole as Pole, ";
															$req .= "(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id = new_competences_personne_poste_prestation.Id_Pole) as NomPole ";
															$req .= "FROM new_competences_personne_poste_prestation ";
															$req .= "LEFT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id = new_competences_personne_poste_prestation.Id_Personne ";
															$req .= "LEFT JOIN new_competences_prestation ON new_competences_prestation.Id = new_competences_personne_poste_prestation.Id_Prestation ";
															$req .= "WHERE new_competences_personne_poste_prestation.Id_Poste = 3  AND new_competences_personne_poste_prestation.Backup = 0 ";
															$req .= "AND new_competences_prestation.Id_Plateforme =".$PlateformeSelect." ";
															$req .= "AND new_rh_etatcivil.Id > 0
																	AND CONCAT(Id_Prestation,'_',Id_Pole) IN (SELECT CONCAT(Id_Prestation,'_',Id_Pole) FROM new_competences_personne_poste_prestation
																	WHERE new_competences_personne_poste_prestation.Id_Poste = 4 AND new_competences_personne_poste_prestation.Backup = 0
																	AND new_competences_personne_poste_prestation.Id_Personne=".$rowRespAffaire['Id']."
																	";
																		if ($PrestationSelect > 0){$req .= "AND new_competences_personne_poste_prestation.Id_Prestation =".$PrestationSelect." ";}
																		if ($PoleSelect > 0){$req .= "AND new_competences_personne_poste_prestation.Id_Pole =".$PoleSelect."";}
																	$req.="
																	) 
																	AND CONCAT(Id_Prestation,'_',Id_Pole) IN (SELECT CONCAT(Id_Prestation,'_',Id_Pole) FROM new_competences_personne_poste_prestation
																		WHERE new_competences_personne_poste_prestation.Id_Poste = 7 AND new_competences_personne_poste_prestation.Backup = 0
																		AND new_competences_personne_poste_prestation.Id_Personne=".$rowRespProdQualite['Id']."
																		";
																			if ($PrestationSelect > 0){$req .= "AND new_competences_personne_poste_prestation.Id_Prestation =".$PrestationSelect." ";}
																			if ($PoleSelect > 0){$req .= "AND new_competences_personne_poste_prestation.Id_Pole =".$PoleSelect."";}
																		$req.="
																	)
																	";
															if ($PrestationSelect > 0){$req .= "AND new_competences_personne_poste_prestation.Id_Prestation =".$PrestationSelect." ";}
															else{
																//Uniquement sur les prestations du responsable
																$reqPresta = "SELECT distinct(new_competences_personne_poste_prestation.Id_Prestation) ";
																$reqPresta .= "FROM new_competences_personne_poste_prestation ";
																$reqPresta .= "LEFT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id = new_competences_personne_poste_prestation.Id_Personne ";
																$reqPresta .= "LEFT JOIN new_competences_prestation ON new_competences_prestation.Id = new_competences_personne_poste_prestation.Id_Prestation ";
																$reqPresta .= "WHERE new_competences_personne_poste_prestation.Id_Poste = 4  AND new_competences_personne_poste_prestation.Backup = 0 ";
																$reqPresta .= "AND new_competences_prestation.Id_Plateforme =".$PlateformeSelect." ";
																$reqPresta .= "AND new_rh_etatcivil.Id = ".$rowRespAffaire['Id']." ";
																$reqPresta .= ";";
																$resultPrestaRespAffaire=mysqli_query($bdd,$reqPresta);
																$nbPrestaRespAffaire=mysqli_num_rows($resultPrestaRespAffaire);
																if ($nbPrestaRespAffaire > 0){
																	mysqli_data_seek($resultPrestaRespAffaire,0);
																	$req .= "AND ( ";
																	while($rowPresta=mysqli_fetch_array($resultPrestaRespAffaire)){
																		$req .= "new_competences_personne_poste_prestation.Id_Prestation =".$rowPresta['Id_Prestation']." OR ";
																	}
																	$req = substr($req,0, -3);
																	$req .= ") ";
																}
															}
															if ($PoleSelect > 0){$req .= "AND new_competences_personne_poste_prestation.Id_Pole =".$PoleSelect."";}
															$req .= ";";
															
															$resultCoorPro=mysqli_query($bdd,$req);
															$nbCoorPro=mysqli_num_rows($resultCoorPro);
															
															if ( $nbCoorPro > 0){
																echo "<ul>";
																mysqli_data_seek($resultCoorPro,0);
																while($rowCoorPro=mysqli_fetch_array($resultCoorPro)){
																	$poste = "N+3";
																	echo "<li><font color=blue><i>".$poste."</i></font><br/>".$rowCoorPro['Nom']." ".$rowCoorPro['Prenom']."<br/><font color=red>".$rowCoorPro['NomPole']."</font>";
																		//Récupérer les coordinateurs d'équipe
																		$req = "SELECT distinct(new_rh_etatcivil.Id), new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom, new_competences_personne_poste_prestation.Id_Poste, ";
																		$req .= "(SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id= new_competences_personne_poste_prestation.Id_Prestation) AS Prestation, ";
																		$req .= "new_competences_personne_poste_prestation.Id_Pole AS Pole ";
																		$req .= "FROM new_competences_personne_poste_prestation ";
																		$req .= "LEFT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id = new_competences_personne_poste_prestation.Id_Personne ";
																		$req .= "LEFT JOIN new_competences_prestation ON new_competences_prestation.Id = new_competences_personne_poste_prestation.Id_Prestation ";
																		$req .= "WHERE new_competences_personne_poste_prestation.Id_Poste = 2  AND new_competences_personne_poste_prestation.Backup = 0 ";
																		$req .= "AND new_competences_prestation.Id_Plateforme =".$PlateformeSelect." ";
																		$req .= "AND new_rh_etatcivil.Id > 0 ";
																		if ($PrestationSelect > 0){
																			$req .= "AND new_competences_personne_poste_prestation.Id_Prestation =".$PrestationSelect." ";
																		}
																		else{
																			//Uniquement sur les prestations du responsable
																			$reqPresta = "SELECT distinct(new_competences_personne_poste_prestation.Id_Prestation) ";
																			$reqPresta .= "FROM new_competences_personne_poste_prestation ";
																			$reqPresta .= "LEFT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id = new_competences_personne_poste_prestation.Id_Personne ";
																			$reqPresta .= "LEFT JOIN new_competences_prestation ON new_competences_prestation.Id = new_competences_personne_poste_prestation.Id_Prestation ";
																			$reqPresta .= "WHERE new_competences_personne_poste_prestation.Id_Poste = 3  AND new_competences_personne_poste_prestation.Backup = 0 ";
																			$reqPresta .= "AND new_competences_prestation.Id_Plateforme =".$PlateformeSelect." ";
																			$reqPresta .= "AND new_rh_etatcivil.Id = ".$rowCoorPro['Id']." ";
																			$reqPresta .= ";";
																			$resultPrestaCoorPro=mysqli_query($bdd,$reqPresta);
																			$nbPrestaCoorPro=mysqli_num_rows($resultPrestaCoorPro);
																			if ($nbPrestaCoorPro > 0){
																				mysqli_data_seek($resultPrestaCoorPro,0);
																				$req .= "AND ( ";
																				while($rowPresta=mysqli_fetch_array($resultPrestaCoorPro)){
																					$req .= "new_competences_personne_poste_prestation.Id_Prestation =".$rowPresta['Id_Prestation']." OR ";
																				}
																				$req = substr($req,0, -3);
																				$req .= ") ";
																			}
																		}
																		if ($PoleSelect > 0){$req .= "AND new_competences_personne_poste_prestation.Id_Pole =".$PoleSelect." ";}
																		$req .= "AND new_competences_personne_poste_prestation.Id_Pole =".$rowCoorPro['Pole']." ";
																		$req .= ";";

																		$resultCoorEqu=mysqli_query($bdd,$req);
																		$nbCoorEqu=mysqli_num_rows($resultCoorEqu);
																		
																		if ( $nbCoorEqu > 0){
																			echo "<ul>";
																			mysqli_data_seek($resultCoorEqu,0);
																			while($rowCoorEqu=mysqli_fetch_array($resultCoorEqu)){
																				$poste = "N+2";
																				echo "<li><font color=blue><i>".$poste."</i></font><br/>".$rowCoorEqu['Nom']." ".$rowCoorEqu['Prenom'];
																					//Récupérer les chefs d'équipe
																					$req = "SELECT distinct(new_rh_etatcivil.Id), new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom, new_competences_personne_poste_prestation.Id_Poste ";
																					$req .= "FROM new_competences_personne_poste_prestation ";
																					$req .= "LEFT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id = new_competences_personne_poste_prestation.Id_Personne ";
																					$req .= "LEFT JOIN new_competences_prestation ON new_competences_prestation.Id = new_competences_personne_poste_prestation.Id_Prestation ";
																					$req .= "WHERE new_competences_personne_poste_prestation.Id_Poste = 1  AND new_competences_personne_poste_prestation.Backup = 0 ";
																					$req .= "AND new_competences_prestation.Id_Plateforme =".$PlateformeSelect." ";
																					$req .= "AND new_rh_etatcivil.Id > 0 ";
																					if ($PrestationSelect > 0){
																						$req .= "AND new_competences_personne_poste_prestation.Id_Prestation =".$PrestationSelect." ";
																					}
																					else{
																						//Uniquement sur les prestations du responsable
																						$reqPresta = "SELECT distinct(new_competences_personne_poste_prestation.Id_Prestation) ";
																						$reqPresta .= "FROM new_competences_personne_poste_prestation ";
																						$reqPresta .= "LEFT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id = new_competences_personne_poste_prestation.Id_Personne ";
																						$reqPresta .= "LEFT JOIN new_competences_prestation ON new_competences_prestation.Id = new_competences_personne_poste_prestation.Id_Prestation ";
																						$reqPresta .= "WHERE new_competences_personne_poste_prestation.Id_Poste = 2  AND new_competences_personne_poste_prestation.Backup = 0 ";
																						$reqPresta .= "AND new_competences_prestation.Id_Plateforme =".$PlateformeSelect." ";
																						$reqPresta .= "AND new_rh_etatcivil.Id = ".$rowCoorEqu['Id']." ";
																						$reqPresta .= ";";
																						$resultPrestaCoorEqu=mysqli_query($bdd,$reqPresta);
																						$nbPrestaCoorEqu=mysqli_num_rows($resultPrestaCoorEqu);
																						if ($nbPrestaCoorEqu > 0){
																							mysqli_data_seek($resultPrestaCoorEqu,0);
																							$req .= "AND ( ";
																							while($rowPresta=mysqli_fetch_array($resultPrestaCoorEqu)){
																								$req .= "new_competences_personne_poste_prestation.Id_Prestation =".$rowPresta['Id_Prestation']." OR ";
																							}
																							$req = substr($req,0, -3);
																							$req .= ") ";
																						}
																					}
																					if ($PoleSelect > 0){$req .= "AND new_competences_personne_poste_prestation.Id_Pole =".$PoleSelect." ";}
																					$req .= "AND new_competences_personne_poste_prestation.Id_Pole =".$rowCoorEqu['Pole']." ";
																					$req .= ";";
									
																					$resultChefEqu=mysqli_query($bdd,$req);
																					$nbChefEqu=mysqli_num_rows($resultChefEqu);
																					
																					if ( $nbChefEqu > 0){
																						echo "<ul>";
																						mysqli_data_seek($resultChefEqu,0);
																						while($rowChefEqu=mysqli_fetch_array($resultChefEqu)){
																							$poste = "N+1";
																							echo "<li><font color=blue><i>".$poste."</i></font><br/>".$rowChefEqu['Nom']." ".$rowChefEqu['Prenom'];
																							echo "</li>";
																						}
																						echo "</ul>";
																					}
																				echo "</li>";
																			}
																			echo "</ul>";
																		}
																	echo "</li>";
																}
																echo "</ul>";
															}
														echo "</li>";
													}
													echo "</ul>";
												}
											}
											elseif ($rowRespProdQualite['Id_Poste'] == '8'){
												//Récupérer les responsables qualité produit et systeme
												$req = "SELECT distinct(new_rh_etatcivil.Id), new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom, new_competences_personne_poste_prestation.Id_Poste ";
												$req .= "FROM new_competences_personne_poste_prestation ";
												$req .= "LEFT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id = new_competences_personne_poste_prestation.Id_Personne ";
												$req .= "LEFT JOIN new_competences_prestation ON new_competences_prestation.Id = new_competences_personne_poste_prestation.Id_Prestation ";
												$req .= "WHERE (new_competences_personne_poste_prestation.Id_Poste = 5 OR new_competences_personne_poste_prestation.Id_Poste = 6)  AND new_competences_personne_poste_prestation.Backup = 0 ";
												$req .= "AND new_competences_prestation.Id_Plateforme =".$PlateformeSelect." ";
												$req .= "AND new_rh_etatcivil.Id > 0 ";
												if ($PrestationSelect > 0){$req .= "AND new_competences_personne_poste_prestation.Id_Prestation =".$PrestationSelect." ";}
												if ($PoleSelect > 0){$req .= "AND new_competences_personne_poste_prestation.Id_Pole =".$PoleSelect."";}
												$req .= ";";

												$resultRespQualProSys=mysqli_query($bdd,$req);
												$nbRespQualProSys=mysqli_num_rows($resultRespQualProSys);
												
												if ( $nbRespQualProSys > 0){
													echo "<ul>";
													mysqli_data_seek($resultRespQualProSys,0);
													while($rowRespQualProSys=mysqli_fetch_array($resultRespQualProSys)){
														$poste = "";
														if ($rowRespQualProSys['Id_Poste'] == '5'){$poste = "Référent qualité produit";}
														else {$poste = "Référent qualité système";}
														echo "<li><font color=blue><i>".$poste."</i></font><br/>".$rowRespQualProSys['Nom']." ".$rowRespQualProSys['Prenom'];
														echo "</li>";
													}
													echo "</ul>";
												}
											}
									echo "</li>";
								}
								echo "</ul>";
							}
						echo "</li>";
						echo "</ul>";
					}
				}
			?>
			<div id="chart" class="orgChart"></div>
		</td>
	</tr>
</table>
</form>

    
    <script>
        jQuery(document).ready(function() {
            
            /* Custom jQuery for the example */
            $("#show-list").click(function(e){
                e.preventDefault();
                
                $('#list-html').toggle('fast', function(){
                    if($(this).is(':visible')){
                        $('#show-list').text('Hide underlying list.');
                        $(".topbar").fadeTo('fast',0.9);
                    }else{
                        $('#show-list').text('Show underlying list.');
                        $(".topbar").fadeTo('fast',1);                  
                    }
                });
            });
            
            $('#list-html').text($('#org').html());
            
            $("#org").bind("DOMSubtreeModified", function() {
                $('#list-html').text('');
                
                $('#list-html').text($('#org').html());
                
                prettyPrint();                
            });
        });
    </script>

</body>
</html>
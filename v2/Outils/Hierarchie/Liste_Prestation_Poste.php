<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetrePoste()
		{var w=window.open("Liste_Poste.php?","PagePoste","status=no,menubar=no,width=500,height=300,resizable=yes,scrollbars=yes");}
	function OuvreFenetreModifPostePrestation(Mode,Id_Prestation,Id_Pole)
		{var w=window.open("Ajout_Prestation_Poste.php?Mode="+Mode+"&Id_Prestation="+Id_Prestation+"&Id_Pole="+Id_Pole+"&Id_Plateforme="+document.getElementById('plateforme').value,"PageFichier","status=no,menubar=no,width=1500,height=750,resizable=yes,scrollbars=yes");
		w.focus();
		}
	function OuvreFenetreModifQualitePrestation(Mode,Id_Prestation,Id_Pole)
		{var w=window.open("Ajout_Prestation_PosteQualite.php?Mode="+Mode+"&Id_Prestation="+Id_Prestation+"&Id_Pole="+Id_Pole+"&Id_Plateforme="+document.getElementById('plateforme').value,"PageFichier","status=no,menubar=no,width=1500,height=750,resizable=yes,scrollbars=yes");
		w.focus();
		}
	function OuvreFenetreExcel(Etat, Id_Personne, Id_Plateforme)
		{window.open("Liste_Prestation_Poste_Export.php?Etat="+Etat+"&Id_Personne="+Id_Personne+"&Id_Plateforme="+Id_Plateforme,"PageExcel","status=no,menubar=no,width=900,height=450");}
</script>
<?php
$vide = "";
$active = "selected";
$nonactive = "";
$etat = "vide";
$personne = "";
if ($_POST){
	switch( $_POST['active']){
	case "vide" :
		$vide = "selected";
		$active = "";
		$nonactive = "";
		$etat = "vide";
		break;
	case "0" :
		$vide = "";
		$active = "selected";
		$nonactive = "";
		$etat = "0";
		break;
	case "-1" :
		$vide = "";
		$active = "";
		$nonactive = "selected";
		$etat = "-1";
		break;
	}
	if(isset($_POST['personne'])){
		$personne = $_POST['personne'];
	}
}

$requetePersonne="SELECT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
$requetePersonne.="FROM new_rh_etatcivil ";
$requetePersonne.="ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
$resultPersonne=mysqli_query($bdd,$requetePersonne);
$NbPersonne=mysqli_num_rows($resultPersonne);
?>

<form method="POST" action="Liste_Prestation_Poste.php">
	<table style="width:100%; border-pacing:0; align:center;">
		<tr>
			<td>
				<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#6EB4CD;">
					<tr>
						<td width="4"></td>
						<td class="TitrePage"><?php if($_SESSION["Langue"]=="FR"){ echo "Hiérarchie du personnel # Responsables / Prestation";}else{echo "Staff hierarchy # Responsible / Activities";}?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td align="right">
			<?php if($_SESSION["Langue"]=="FR"){
				echo "Les modifications de la hiérarchie sont réalisées par le service RH, le responsable d'unité ou le responsable qualité (pour les Filiales).<br>
			La liste des CQP est modifiable par les CQS.";
			}
			else{
				echo "Changes to the hierarchy are made by the HR department, the unit manager or the quality manager (for Subsidiaries).<br>
				The CQP list is modified by the CQS or the quality manager.";
			}
			?>
			</td>
		</tr>
		<td height="4"></td>
		</tr>
		<tr><td>
		<table style="width:100%; border-pacing:0; align:center;" class="GeneralInfo">
			<tr>
				<td width="20%" class="Libelle">
					&nbsp; <?php if($_SESSION["Langue"]=="FR"){ echo "UER / Filiales";}else{echo "RBU / Subsidiaries";}?> :
					<select class="plateforme" id="plateforme" name="plateforme" onchange="submit();">
					<?php
					
					if(
						DroitsFormation1Plateforme(17,$TableauIdPostesRH)
						|| DroitsFormation1Plateforme(17,array($IdPosteInformatique))
						|| DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite))
					)
					{
						$req = "SELECT Id AS Id_Plateforme,
								Libelle 
								FROM new_competences_plateforme
								WHERE Id NOT IN (11,14)
								ORDER BY Libelle;";
					}
					else{
						$req = "SELECT distinct new_competences_prestation.Id_Plateforme, ";
						$req .= "(SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id=new_competences_prestation.Id_Plateforme) AS Libelle ";
						$req .= "FROM ((new_competences_prestation ";
						$req .= "LEFT JOIN new_competences_personne_poste_prestation ON new_competences_personne_poste_prestation.Id_Prestation = new_competences_prestation.Id) ";
						$req .= "LEFT JOIN new_competences_personne_plateforme ON new_competences_personne_plateforme.Id_Plateforme = new_competences_prestation.Id_Plateforme) ";
						$req .= "WHERE new_competences_personne_poste_prestation.Id_Personne=".$_SESSION['Id_Personne']." ";
						$req .= "OR new_competences_personne_plateforme.Id_Personne =".$_SESSION['Id_Personne']." ";
						$req .= "ORDER BY Libelle;";
					}

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
								echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
								$Selected = "";
							}
						}
						elseif (!empty($_GET['plateforme'])){
							if ($PlateformeSelect == 0){$PlateformeSelect = $_GET['plateforme'];}
							while($row=mysqli_fetch_array($resultPlateforme))
							{
								if ($row[0] == $_GET['plateforme']){
									$Selected = "Selected";
								}
								echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
								$Selected = "";
							}
						}
						else{
							while($row=mysqli_fetch_array($resultPlateforme))
							{
								if ($PlateformeSelect == 0){
									$PlateformeSelect = $row[0];
								}
								echo "<option name='".$row[0]."' value='".$row[0]."'>".$row[1]."</option>";
							}
						}
					 }
					 ?>
					</select>
				</td>
				<td width="20%" class="Libelle">
					&nbsp; <?php if($_SESSION["Langue"]=="FR"){ echo "Etat des prestations";}else{echo "Activities status";}?> :
					<select name="active" onchange="submit();">
						<option value="vide" <?php echo $vide; ?> ></option>
						<option value="0" <?php echo $active; ?> >Active</option>
						<option value="-1" <?php echo $nonactive; ?> >Non active</option>
					</select>
				</td>
				<td class="Libelle">
					&nbsp; <?php if($_SESSION["Langue"]=="FR"){ echo "Personne";}else{echo "Collaborators";}?> :
					<select id="personne" name="personne" onchange="submit();">
						<option value='0'></option>
						<?php
							while($rowPersonne=mysqli_fetch_array($resultPersonne))
							{
								echo "<option value='".$rowPersonne[0]."'";
								if ($personne == $rowPersonne[0]){echo " selected ";}
								echo ">".$rowPersonne[1]." ".$rowPersonne[2]."</option>\n";
							}
						?>
					</select>
				</td>
				<td>
					&nbsp;
					<a style="text-decoration:none;" href="javascript:OuvreFenetreExcel('<?php echo $etat; ?>','<?php echo $_SESSION['Id_Personne']; ?>','<?php echo $PlateformeSelect; ?>');">
						<img src="../../Images/excel.gif" border="0" alt="Excel" title="Export Excel">
					</a>
					&nbsp;
				</td>
			</tr>
		</table>
		</td></tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td>
				<table >
					<tr>
						<td width="10"></td>
						<td>
							<table class="TableCompetences" style="width:98%;">
							<?php
							
								$requete="SELECT Id AS IDPrestation, 
									Id_Plateforme,
									Libelle AS LibellePrestation,
									0 AS IdPole,
									'' AS LibellePole,
									Active
									FROM new_competences_prestation 
									WHERE Id NOT IN (
										SELECT Id_Prestation
										FROM new_competences_pole  
										WHERE new_competences_pole.Actif=0
									)
									AND Id_Plateforme=".$PlateformeSelect." ";
								if(isset($_POST['active']))
								{
									if($_POST['active'] <> "vide"){$requete.=" AND new_competences_prestation.Active =".$_POST['active']." ";}
								}
								else{$requete.=" AND new_competences_prestation.Active = 0 ";}
								if($personne <>"" & $personne <>"0"){
									$requete.=" AND (SELECT COUNT(new_competences_personne_poste_prestation.Id) 
													FROM new_competences_personne_poste_prestation
													WHERE new_competences_personne_poste_prestation.Id_Personne =".$personne."
													AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id)>0 ";
								}
								
								$requete.="UNION
									
									SELECT Id_Prestation AS IDPrestation,
									new_competences_prestation.Id_Plateforme,
									new_competences_prestation.Libelle AS LibellePrestation,
									new_competences_pole.Id AS IdPole,
									new_competences_pole.Libelle AS LibellePole,
									Active
									FROM new_competences_pole
									INNER JOIN new_competences_prestation
									ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
									AND new_competences_pole.Actif=0
									AND new_competences_prestation.Id_Plateforme=".$PlateformeSelect." ";
								if(isset($_POST['active']))
								{
									if($_POST['active'] <> "vide"){$requete.=" AND new_competences_prestation.Active =".$_POST['active']." ";}
								}
								else{$requete.=" AND new_competences_prestation.Active = 0 ";}
								if($personne <>"" & $personne <>"0"){
									$requete.=" AND (SELECT COUNT(new_competences_personne_poste_prestation.Id) 
													FROM new_competences_personne_poste_prestation
													WHERE new_competences_personne_poste_prestation.Id_Personne =".$personne."
													AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id)>0 ";
								}
								$requete.="ORDER BY LibellePrestation, LibellePole";
								
								$result=mysqli_query($bdd,$requete);
								$nbenreg=mysqli_num_rows($result);
								if($nbenreg>0)
								{
									$resultPoste=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_poste WHERE Id<=5 OR Id=22 ORDER BY Id ASC");
									$NbLignePoste=mysqli_num_rows($resultPoste);
							?>
								<tr>
									<td class="EnTeteTableauCompetences"><?php if($_SESSION["Langue"]=="FR"){ echo "Etat";}else{echo "Status";}?></td>
									<td class="EnTeteTableauCompetences"><?php if($_SESSION["Langue"]=="FR"){ echo "Prestations";}else{echo "Activities";}?></td>
									<td class="EnTeteTableauCompetences"><?php if($_SESSION["Langue"]=="FR"){ echo "Pôle";}else{echo "Division";}?></td>
									<?php
										while($rowPoste=mysqli_fetch_array($resultPoste)){{echo "<td class='EnTeteTableauCompetences'>".$rowPoste[1]."</td>";}}
									?>
									<td></td>
								</tr>
							<?php
								$Couleur="#EEEEEE";
								while($row=mysqli_fetch_array($result))
								{
									if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
									else{$Couleur="#EEEEEE";}
							?>
								<tr bgcolor="<?php echo $Couleur;?>">
									<?php
										if ($row['Active'] == "0"){
											echo "<td width=30>A</td>";
										}
										else{
											echo "<td width=30>NA</td>";
										}
									?>
									<td width=550><?php echo $row['LibellePrestation'];?></td>
									<td width=150><?php echo $row['LibellePole'];?></td>
								<?php
									$requetePersonnePoste2="SELECT new_competences_personne_poste_prestation.Id_Poste, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom";
									$requetePersonnePoste2.=" FROM new_competences_personne_poste_prestation, new_rh_etatcivil";
									$requetePersonnePoste2.=" WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id";
									$requetePersonnePoste2.=" AND new_competences_personne_poste_prestation.Id_Prestation=".$row[0];
									if($row['IdPole']>0){$requetePersonnePoste2.=" AND new_competences_personne_poste_prestation.Id_Pole=".$row['IdPole'];}
									$requetePersonnePoste2.=" AND new_competences_personne_poste_prestation.Backup<>0";
									$resultPersonnePoste2=mysqli_query($bdd,$requetePersonnePoste2);
									$NbLignePersonnePoste2=mysqli_num_rows($resultPersonnePoste2);
									
									$requetePersonnePoste="SELECT new_competences_personne_poste_prestation.Id_Poste, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom";
									$requetePersonnePoste.=" FROM new_competences_personne_poste_prestation, new_rh_etatcivil";
									$requetePersonnePoste.=" WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id";
									$requetePersonnePoste.=" AND new_competences_personne_poste_prestation.Id_Prestation=".$row[0];
									if($row['IdPole']>0){$requetePersonnePoste.=" AND new_competences_personne_poste_prestation.Id_Pole=".$row['IdPole'];}
									$requetePersonnePoste.=" ORDER BY new_competences_personne_poste_prestation.Backup ASC ";
									$resultPersonnePoste=mysqli_query($bdd,$requetePersonnePoste);
									$NbLignePersonnePoste=mysqli_num_rows($resultPersonnePoste);
									mysqli_data_seek($resultPoste,0);
									while($rowPoste=mysqli_fetch_array($resultPoste))
									{
										if($rowPoste[0]==1){$titre = $rowPoste[1]." : \n";}
										else{$titre = "Backup ".$rowPoste[1]." : \n";}
										if($NbLignePersonnePoste2>0)
										{
											while($rowPersonnePoste2=mysqli_fetch_array($resultPersonnePoste2))
											{
												if($rowPersonnePoste2[0]==$rowPoste[0]){$titre .= $rowPersonnePoste2[1]." ".$rowPersonnePoste2[2]."\n";}
											}
											mysqli_data_seek($resultPersonnePoste2,0);
										}
										
										echo "<td width='205' alt='".$rowPoste[1]."' title='".$titre."'>";
										if($NbLignePersonnePoste>0)
										{
											while($rowPersonnePoste=mysqli_fetch_array($resultPersonnePoste))
											{
												if($rowPersonnePoste[0]==$rowPoste[0]){echo $rowPersonnePoste[1]." ".$rowPersonnePoste[2];break;}
											}
											mysqli_data_seek($resultPersonnePoste,0);
										}
										echo "</td>";
									}
								?>
									<td width="20">
								<?php 
								
									if(
										DroitsFormation1Plateforme($PlateformeSelect,$TableauIdPostesRH)
										|| DroitsFormation1Plateforme($PlateformeSelect,array($IdPosteResponsablePlateforme))
										|| DroitsFormation1Plateforme(17,$TableauIdPostesRH)
										|| DroitsFormation1Plateforme(17,array($IdPosteInformatique))
										|| (
											($PlateformeSelect==12 || $PlateformeSelect==16 || $PlateformeSelect==18
											|| $PlateformeSelect==20 || $PlateformeSelect==22 || $PlateformeSelect==26 
											|| $PlateformeSelect==30) 
											&& DroitsFormation1Plateforme($PlateformeSelect,array($IdPosteResponsableQualite))
											)
									)
									{
								?>
										<a class="Modif" href="javascript:OuvreFenetreModifPostePrestation('Modif','<?php echo $row['IDPrestation']; ?>','<?php echo $row['IdPole']; ?>');">	<!--Remplacer le 0 par ID_POLE-->
											<img src="../../Images/Modif.gif" border="0" alt="Modification" title="Modifier">
										</a>
								<?php
									}
									elseif(
										DroitsFormation1Plateforme($PlateformeSelect,array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme))
										|| DroitsFormation1Plateforme(17,array($IdPosteResponsableQualite))
									)
									{
								?>
									<a class="Modif" href="javascript:OuvreFenetreModifQualitePrestation('Modif','<?php echo $row['IDPrestation']; ?>','<?php echo $row['IdPole']; ?>');">	<!--Remplacer le 0 par ID_POLE-->
										<img src="../../Images/Modif.gif" border="0" alt="Modification" title="Modifier">
									</a>
								<?php 
									}
								?>
									</td>
								</tr>
							<?php
								}	//Fin boucle
							}		//Fin If
							mysqli_free_result($result);	// Libération des résultats
							?>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>
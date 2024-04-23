<?php
if($_POST){
	$Id_Type="";
	if(isset($_POST['Id_Type'])){
		if (is_array($_POST['Id_Type'])) {
			foreach($_POST['Id_Type'] as $value){
				if($Id_Type<>''){$Id_Type.=",";}
			  $Id_Type.="'".$value."'";
			}
		} else {
			$value = $_POST['Id_Type'];
			$Id_Type = $value;
		}
	}
	
	$Id_Plateforme="";
	if(isset($_POST['Id_Plateforme'])){
		if (is_array($_POST['Id_Plateforme'])) {
			foreach($_POST['Id_Plateforme'] as $value){
				if($Id_Plateforme<>''){$Id_Plateforme.=",";}
			  $Id_Plateforme.=$value;
			}
		} else {
			$value = $_POST['Id_Plateforme'];
			$Id_Plateforme = $value;
		}
	}
	
	if($_POST){$annee=$_POST['annee'];}
	if($annee==""){$annee=date("Y");}
	
	$_SESSION['FiltreEPEIndicateurs_Plateforme']=$Id_Plateforme;
	$_SESSION['FiltreEPEIndicateurs_Annee']=$annee;
	$_SESSION['FiltreEPEIndicateurs_Type']=$Id_Type;
	$_SESSION['FiltreEPEIndicateurs_Responsable']=$_POST['manager'];
	
	$dateDebut=date($annee.'-01-01');
	$dateFin=date($annee.'-12-31');
} 
?>
<table style="width:100%; border-spacing:0; align:center;">
<tr><td height="4"></td>
<tr>
	<td align="center" valign="top" width="75%">
		<table class="GeneralInfo">
			<?php if($_POST){ 
				if($_POST['annee']<>""){
					$requete="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA 
						FROM new_rh_etatcivil
						RIGHT JOIN epe_personne_datebutoir 
						ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne 
						WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
						OR 
							(SELECT COUNT(Id)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee'].")>0
						) 
						AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." 
						";
					//Vérifier si appartient à une prestation OPTEA ou compétence
					$requete.="AND 
						(
							SELECT COUNT(new_competences_personne_prestation.Id)
							FROM new_competences_personne_prestation
							LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
							WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
							AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
							AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
							AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
							AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
							AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].")
						)>0 
						AND 
						(
							SELECT Id_Prestation
							FROM new_competences_personne_prestation
							LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
							WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
							AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
							AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
							AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
							ORDER BY Date_Fin DESC, Date_Debut DESC
							LIMIT 1
						) NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
						
						";
					if($_SESSION['FiltreEPEIndicateurs_Type']<>""){
						$requete.="AND TypeEntretien IN (".$_SESSION['FiltreEPEIndicateurs_Type'].") ";
					}
					$result=mysqli_query($bdd,$requete);
					$nbResulta=mysqli_num_rows($result);
					
					$AFaire=0;
					$Brouillon=0;
					$SignatureS=0;
					$SignatureE=0;
					$Realise=0;

					if($nbResulta>0){
						while($row=mysqli_fetch_array($result))
						{
							$reqNb="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,
							TypeEntretien AS TypeE,
							IF(TypeEntretien='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),TypeEntretien) AS TypeEntretien,DateButoir,
							epe_personne_datebutoir.Id AS Id_EpePersonneDB,Cadre,
							IF((SELECT COUNT(Id)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee'].")>0,
							(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1),
							'A faire')
							AS Etat,
							(SELECT CONCAT(epe_personne.Id_Prestation,'_',epe_personne.Id_Pole)
										FROM epe_personne 
										WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1) AS PrestaPole,
							(SELECT Id_Evaluateur
										FROM epe_personne 
										WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1) AS Id_Manager,
							(SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur)
										FROM epe_personne 
										WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1) AS Manager
							FROM new_rh_etatcivil
							RIGHT JOIN epe_personne_datebutoir 
							ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne
							WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
							OR 
								(SELECT COUNT(Id)
								FROM epe_personne 
								WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee'].")>0
							)  
							AND new_rh_etatcivil.Id=".$row['Id']."
							AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." ";
							if($_SESSION['FiltreEPEIndicateurs_Type']<>""){
								$reqNb.="AND TypeEntretien IN (".$_SESSION['FiltreEPEIndicateurs_Type'].") ";
							}
							$ResultNb=mysqli_query($bdd,$reqNb);
							$leNb=mysqli_num_rows($ResultNb);
							
							if($leNb>0){
								while($rowNb=mysqli_fetch_array($ResultNb))
								{
									$Id_Prestation=0;
									$Id_Pole=0;
									
									$dateCloture="";
									$laDate=date('Y-m-d');
									$req="SELECT DateCloture FROM epe_cloturecampagne WHERE Annee=".$_SESSION['FiltreEPEIndicateurs_Annee']." ";
									$resultDateCloture=mysqli_query($bdd,$req);
									$nbDateCloture=mysqli_num_rows($resultDateCloture);
									if($nbDateCloture>0){
										$rowDateCloture=mysqli_fetch_array($resultDateCloture);
										$dateCloture=$rowDateCloture['DateCloture'];
										$laDate=$rowDateCloture['DateCloture'];
									}
									
									$req="SELECT Id_Prestation,Id_Pole 
									FROM new_competences_personne_prestation
									WHERE Id_Personne=".$row['Id']." 
									AND new_competences_personne_prestation.Date_Debut<='".$laDate."'
									AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$laDate."') 
									ORDER BY Date_Fin DESC, Date_Debut DESC
									";
									$resultch=mysqli_query($bdd,$req);
									$nb=mysqli_num_rows($resultch);
									$Id_PrestationPole="0_0";
									if($nb>0){
										$rowMouv=mysqli_fetch_array($resultch);
										$Id_PrestationPole=$rowMouv['Id_Prestation']."_".$rowMouv['Id_Pole'];
									}

									$TableauPrestationPole=explode("_",$Id_PrestationPole);
									$Id_Prestation=$TableauPrestationPole[0];
									$Id_Pole=$TableauPrestationPole[1];

									if($rowNb['Etat']=="A faire"){
										if($nb>1){
											$req="SELECT Id_Prestation, Id_Pole, (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme FROM epe_personne_prestation WHERE Id_Personne=".$row['Id']." AND Id_Manager=0 AND Suppr=0 AND Annee=".$_SESSION['FiltreEPEIndicateurs_Annee']." ";
											$ResultlaPresta=mysqli_query($bdd,$req);
											$NblaPresta=mysqli_num_rows($ResultlaPresta);
											if($NblaPresta>0){
												$RowlaPresta=mysqli_fetch_array($ResultlaPresta);
												$Id_Prestation=$RowlaPresta['Id_Prestation'];
												$Id_Pole=$RowlaPresta['Id_Pole'];
											}
										}
									}
									else{
										$tab = explode("_",$rowNb['PrestaPole']);
										$Id_Prestation=$tab[0];
										$Id_Pole=$tab[1];
									}
									
									$Id_Plateforme="";
									$req="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation;
									$ResultPresta=mysqli_query($bdd,$req);
									$NbPrest=mysqli_num_rows($ResultPresta);
									if($NbPrest>0){
										$RowPresta=mysqli_fetch_array($ResultPresta);
										$Id_Plateforme=$RowPresta['Id_Plateforme'];
									}
									
									$Manager="";
									$Id_Manager=0;
									if($rowNb['Etat']=="A faire"){
										$req="SELECT Id_Manager, (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Manager) AS Manager FROM epe_personne_prestation WHERE Id_Personne=".$row['Id']." AND Id_Manager<>0 AND Id_Prestation=".$Id_Prestation."  AND Id_Pole=".$Id_Pole." AND Suppr=0 AND Annee=".$_SESSION['FiltreEPEIndicateurs_Annee']." ";
										$ResultlaPresta=mysqli_query($bdd,$req);
										$NblaPresta=mysqli_num_rows($ResultlaPresta);
										if($NblaPresta>0){
											$RowlaPresta=mysqli_fetch_array($ResultlaPresta);
											$Id_Manager=$RowlaPresta['Id_Manager'];
											$Manager=$RowlaPresta['Manager'];
										}
										else{
											$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
													FROM new_competences_personne_poste_prestation 
													LEFT JOIN new_rh_etatcivil
													ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
													WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
													AND Id_Prestation=".$Id_Prestation."
													AND Id_Pole=".$Id_Pole."
													AND Id_Personne=".$row['Id']."
													ORDER BY Backup ";
											$ResultManager2=mysqli_query($bdd,$req);
											$NbManager2=mysqli_num_rows($ResultManager2);
											if($NbManager2>0){
												$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
													FROM new_competences_personne_poste_prestation 
													LEFT JOIN new_rh_etatcivil
													ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
													WHERE Id_Poste=".$IdPosteCoordinateurProjet."
													AND Id_Prestation=".$Id_Prestation."
													AND Id_Pole=".$Id_Pole."
													ORDER BY Backup ";
												$ResultManager=mysqli_query($bdd,$req);
												$NbManager=mysqli_num_rows($ResultManager);
												if($NbManager>0){
													$RowManager=mysqli_fetch_array($ResultManager);
													$Manager=$RowManager['Personne'];
													$Id_Manager=$RowManager['Id'];
												}
											}
											else{
												$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
													FROM new_competences_personne_poste_prestation 
													LEFT JOIN new_rh_etatcivil
													ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
													WHERE Id_Poste=".$IdPosteChefEquipe."
													AND Id_Prestation=".$Id_Prestation."
													AND Id_Pole=".$Id_Pole."
													AND Id_Personne=".$row['Id']."
													ORDER BY Backup ";
												$ResultManager2=mysqli_query($bdd,$req);
												$NbManager2=mysqli_num_rows($ResultManager2);
												if($NbManager2>0){
													$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
														FROM new_competences_personne_poste_prestation 
														LEFT JOIN new_rh_etatcivil
														ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
														WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
														AND Id_Prestation=".$Id_Prestation."
														AND Id_Pole=".$Id_Pole."
														ORDER BY Backup ";
													$ResultManager=mysqli_query($bdd,$req);
													$NbManager=mysqli_num_rows($ResultManager);
													if($NbManager>0){
														$RowManager=mysqli_fetch_array($ResultManager);
														$Manager=$RowManager['Personne'];
														$Id_Manager=$RowManager['Id'];
													}
												}
												else{
													$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
													FROM new_competences_personne_poste_prestation 
													LEFT JOIN new_rh_etatcivil
													ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
													WHERE Id_Poste=".$IdPosteChefEquipe."
													AND Id_Prestation=".$Id_Prestation."
													AND Id_Pole=".$Id_Pole."
													ORDER BY Backup ";
													$ResultManager=mysqli_query($bdd,$req);
													$NbManager=mysqli_num_rows($ResultManager);
													if($NbManager>0){
														$RowManager=mysqli_fetch_array($ResultManager);
														$Manager=$RowManager['Personne'];
														$Id_Manager=$RowManager['Id'];
													}
												}
											}
										}
									}
									else{
										$Manager=$rowNb['Manager'];
										$Id_Manager=$rowNb['Id_Manager'];
									}
									
									$trouve=1;
									if($_SESSION['FiltreEPEIndicateurs_Responsable']<>"0"){
										if($_SESSION['FiltreEPEIndicateurs_Responsable']<>$Id_Manager){
											$trouve=0;
										}
									}
									$existe=0;
									if(isset($_POST['Id_Plateforme'])){
										if (is_array($_POST['Id_Plateforme'])) {
											foreach($_POST['Id_Plateforme'] as $value){
												if($value==$Id_Plateforme){$existe=1;}
											}
										} else {
											if($_POST['Id_Plateforme']==$Id_Plateforme){$existe=1;}

										}
										if($existe==0){$trouve=0;}
									}

									if($trouve==1){
										if($rowNb['Etat']=="A faire"){$AFaire++;}
										elseif($rowNb['Etat']=="Brouillon"){$Brouillon++;}
										elseif($rowNb['Etat']=="Signature salarié"){$SignatureS++;}
										elseif($rowNb['Etat']=="Signature manager"){$SignatureE++;}
										elseif($rowNb['Etat']=="Réalisé"){$Realise++;}
									}
								}
							}
							
						}
					}
					
					$AFaire = $AFaire + $Brouillon;
					$Realise = $SignatureS + $SignatureE + $Realise;
					$array[0]=array("Abscisse" => utf8_encode("A faire"),"Nombre" => $AFaire);
					$array[1]=array("Abscisse" => utf8_encode("Réalisé"),"Nombre" => $Realise);
			?>
			<tr>
				<td height="400px;">
					<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "TAUX DE RÉALISATION";}else{echo "COMPLETION RATE";} ?></td>
							<td style="cursor:pointer;" align="right"></td>
						</tr>
						<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td valign="top">
								<div id="chart_taux" style="width:100%;height:400px"></div>
								<script>
									// Create chart instance
									var chart = am4core.create("chart_taux", am4charts.PieChart);

								// Add data
								chart.data = <?php echo json_encode($array); ?>;
								
								var pieSeries = chart.series.push(new am4charts.PieSeries());
								pieSeries.dataFields.category = "Abscisse";
								pieSeries.dataFields.value = "Nombre";
								pieSeries.slices.template.stroke = am4core.color("#fff");
								pieSeries.slices.template.strokeWidth = 2;
								pieSeries.slices.template.strokeOpacity = 1;

								// This creates initial animation
								pieSeries.hiddenState.properties.opacity = 1;
								pieSeries.hiddenState.properties.endAngle = -90;
								pieSeries.hiddenState.properties.startAngle = -90;
								
								var level1ColumnTemplate = pieSeries.columns.template;

								var bullet1 = level1ColumnTemplate.bullets.push(new am4charts.LabelBullet());
								bullet1.label.text = "{name}: {valueY.value}";


								chart.exporting.menu = new am4core.ExportMenu();
								</script>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		<?php	
			}
		}
		?>
		</table>
	</td>
	<td align="right" valign="top" width="25%">
		<table class="GeneralInfo" style="border-spacing:0; width:70%; align:center;box-shadow: 0 8px 10px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Année";}else{echo "Year";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $_SESSION['FiltreEPEIndicateurs_Annee']; ?>" size="5"/></td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?> : </td>
			</tr>
			<tr>
				<td>
					<table width='100%'>
						<?php
							$tab=array("EPE","EPP","EPP Bilan");
							foreach($tab as $val)
							{
								$checked="";
								if($_POST){
									$checkboxes = isset($_POST['Id_Type']) ? $_POST['Id_Type'] : array();
									foreach($checkboxes as $value) {
										if($val==$value){$checked="checked";}
									}
								}
								else{
									$checked="checked";	
								}
								echo "<tr><td>";
								echo "<input type='checkbox' class='checkType' name='Id_Type[]' Id='Id_Type[]' value='".$val."' ".$checked." >".$val;
								echo "</td></tr>";
							}
						?>
					</table>
				</td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Manager";}else{echo "Manager";}?> : </td>
			</tr>
			<tr>
				<td>
					<select id="manager" style="width:150px;" name="manager" onchange="submit();">
						<option value='0'></option>
						<?php
							$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
									CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
									FROM new_competences_personne_poste_prestation
									LEFT JOIN new_rh_etatcivil
									ON new_rh_etatcivil.Id=new_competences_personne_poste_prestation.Id_Personne
									WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.")
									AND new_rh_etatcivil.Id>0
									AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) ";
							$requetePersonne.="ORDER BY Personne ASC";
							$resultPersonne=mysqli_query($bdd,$requetePersonne);
							$NbPersonne=mysqli_num_rows($resultPersonne);

							while($rowPersonne=mysqli_fetch_array($resultPersonne))
							{
								echo "<option value='".$rowPersonne['Id']."'";
								if($_POST){
									if ($_POST['manager'] == $rowPersonne['Id']){echo " selected ";}
								}
								echo ">".$rowPersonne['Personne']."</option>\n";
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
			</tr>
			<tr>
				<td>
					<input type="checkbox" name="selectAll" id="selectAll" onclick="SelectionnerTout()" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php 
					$requetePlateforme="SELECT Id, Libelle
					FROM new_competences_plateforme
					WHERE Id IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
					ORDER BY Libelle";
					$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
					
					while($LigPlateforme=mysqli_fetch_array($resultPlateforme)){
						$checked="";
						if($_POST){
							$checkboxes = isset($_POST['Id_Plateforme']) ? $_POST['Id_Plateforme'] : array();
							foreach($checkboxes as $value) {
								if($LigPlateforme['Id']==$value){$checked="checked";}
							}
						}
						else{
							$checked="checked";	
						}
						echo "<tr><td>";
						echo "<input type='checkbox' class='checkPlateforme' name='Id_Plateforme[]' Id='Id_Plateforme[]' value='".$LigPlateforme['Id']."' ".$checked." >".$LigPlateforme['Libelle'];
						echo "</td></tr>";
					}
					
					?>
				</td>
			</tr>
			<tr>
				<td align="center">
					<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
					<div id="filtrer"></div>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
		</table>
	</td>
</tr>
<tr><td height="4"></td>
</table>	
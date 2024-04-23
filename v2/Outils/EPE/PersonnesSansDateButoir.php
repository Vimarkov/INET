<?php
if($_POST){
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
	$_SESSION['FiltreEPEIndicateurs_TypeEPE']=$_POST['Id_Type'];
} 
?>
<table style="width:100%; border-spacing:0; align:center;">
<tr><td height="4"></td>
<tr>
	<td align="center" valign="top" width="75%">
		<table class="GeneralInfo">
			<?php if($_POST){ 
				if($_POST['annee']<>""){
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
					
					$req="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,
						Cadre,DateAncienneteCDI
					FROM new_rh_etatcivil
					WHERE new_rh_etatcivil.Id NOT IN (1726,1739)
					AND MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') 
					AND MetierPaie<>'' AND Cadre IN (0,1) 
					AND new_rh_etatcivil.Id<>1739
					AND (SELECT COUNT(Id_Plateforme) FROM new_competences_personne_plateforme
					WHERE new_rh_etatcivil.Id=Id_Personne AND Id_Plateforme NOT IN (11,14))>0
					AND
						(
							SELECT COUNT(new_competences_personne_prestation.Id)
							FROM new_competences_personne_prestation
							LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
							WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
							AND new_competences_personne_prestation.Date_Debut<='".$laDate."'
							AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$laDate."')
							AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
							AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
							AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].")
						)>0
					AND (SELECT COUNT(TAB.Id) FROM epe_personne_datebutoir AS TAB WHERE TAB.Id_Personne=new_rh_etatcivil.Id  
						AND YEAR(IF(DateReport>'0001-01-01',DateReport,DateButoir)) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." AND TAB.TypeEntretien = '".$_SESSION['FiltreEPEIndicateurs_TypeEPE']."')=0
					AND (SELECT COUNT(epe_personne_na.Id) FROM epe_personne_na WHERE Id_Personne=new_rh_etatcivil.Id  
						AND Annee = ".$_SESSION['FiltreEPEIndicateurs_Annee']." AND TypeEntretien = '".$_SESSION['FiltreEPEIndicateurs_TypeEPE']."')=0
					";
					if($dateCloture<>"" && $dateCloture>"0001-01-01"){
						$req.=" AND DateAncienneteCDI<'".$dateCloture."' ";
					}
					if($_SESSION['FiltreEPEIndicateurs_TypeEPE']=="EPP Bilan"){
						$req.=" AND YEAR(DateAncienneteCDI)<='".date('Y',strtotime(date($_SESSION['FiltreEPEIndicateurs_Annee'].'-m-d')." -6 year"))."' ";
						
						if($_SESSION['FiltreEPEDateButoir_SansDate']<>""){
						$req.=" AND (SELECT COUNT(Id)
									FROM epe_personne 
									WHERE Suppr=0 
									AND epe_personne.Type='EPP Bilan' 
									AND epe_personne.Id_Personne=new_rh_etatcivil.Id 
									AND ModeBrouillon=0 
									AND YEAR(DateEntretien) >= ".date('Y',strtotime(date($_SESSION['FiltreEPEIndicateurs_Annee'].'-m-d')." -5 year"))."
								)=0
							";
						}
					}
					elseif($_SESSION['FiltreEPEIndicateurs_TypeEPE']=="EPP"){
						$req.=" AND YEAR(DateAncienneteCDI)<='".date('Y',strtotime(date($_SESSION['FiltreEPEIndicateurs_Annee'].'-m-d')." -2 year"))."' ";
						
						if($_SESSION['FiltreEPEDateButoir_SansDate']<>""){
						$req.=" AND (SELECT COUNT(Id)
									FROM epe_personne 
									WHERE Suppr=0 
									AND epe_personne.Type='EPP' 
									AND epe_personne.Id_Personne=new_rh_etatcivil.Id 
									AND ModeBrouillon=0 
									AND YEAR(DateEntretien) >= ".date('Y',strtotime(date($_SESSION['FiltreEPEIndicateurs_Annee'].'-m-d')." -1 year"))."
								)=0
							";
						}
					}
					if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
						
					}
					else{
						$req.="
							AND
							(
								SELECT COUNT(new_competences_personne_prestation.Id)
								FROM new_competences_personne_prestation
								LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
								WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
								AND new_competences_personne_prestation.Date_Debut<='".$laDate."'
								AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$laDate."')
								AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
								AND 
								((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN 
									(
										SELECT Id_Plateforme 
										FROM new_competences_personne_poste_plateforme
										WHERE Id_Personne=".$_SESSION['Id_Personne']." 
										AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
									)
								) 
							)>0
							";
					}
					$req.="ORDER BY Personne ";
					
					$result=mysqli_query($bdd,$req);
					$nbenreg=mysqli_num_rows($result);
						
				?>
					<tr >
						<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule";}else{echo "Registration number";} ?></td>
						<td class="EnTeteTableauCompetences" width="12%"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "People";} ?></td>
						<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Date d'embauche";}else{echo "Hiring date";} ?></td>
						<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";} ?></td>
						<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
						<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Type";}else{echo "Type";} ?></td>
						<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Responsable";}else{echo "Responsible";} ?></td>
						<td class="EnTeteTableauCompetences" width="3%">
						&nbsp;<a style="text-decoration:none;" href="javascript:Excel_PersonnesSansDateButoir();">
							<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
						</a>&nbsp;
						</td>
					</tr>
				<?php
					if($nbenreg>0){
						$total=0;
						$couleur="#d6d9dc";
						
						while($row=mysqli_fetch_array($result)){
							
							$Id_Prestation=0;
							$Id_Pole=0;

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

							
							$Plateforme="";
							$Presta="";
							$req="SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,LEFT(Libelle,7) AS Prestation FROM new_competences_prestation WHERE Id=".$Id_Prestation;
							$ResultPresta=mysqli_query($bdd,$req);
							$NbPrest=mysqli_num_rows($ResultPresta);
							if($NbPrest>0){
								$RowPresta=mysqli_fetch_array($ResultPresta);
								$Presta=$RowPresta['Prestation'];
								$Plateforme=$RowPresta['Plateforme'];
							}
							
							$Pole="";
							$req="SELECT Libelle FROM new_competences_pole WHERE Id=".$Id_Pole;
							$ResultPole=mysqli_query($bdd,$req);
							$NbPole=mysqli_num_rows($ResultPole);
							if($NbPole>0){
								$RowPole=mysqli_fetch_array($ResultPole);
								$Pole=$RowPole['Libelle'];
							}
							
							if($Pole<>""){$Presta.=" - ".$Pole;}
							
							$Manager="";
							$Id_Manager=0;
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
				?>
					<tr bgcolor="<?php echo $couleur; ?>">
						<td><?php echo stripslashes($row['MatriculeAAA']);?></td>
						<td><?php echo stripslashes($row['Personne']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateAncienneteCDI']);?></td>
						<td><?php echo stripslashes($Plateforme);?></td>
						<td><?php echo stripslashes($Presta);?></td>
						<td><?php echo $_SESSION['FiltreEPEIndicateurs_TypeEPE'];?></td>
						<td colspan="2"><?php echo stripslashes($Manager);?></td>
					</tr>
				<?php 
							if($couleur=="#d6d9dc"){$couleur="#ffffff";}
							else{$couleur="#d6d9dc";}
						} 
					}
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
						<tr>
							<td>
								<?php
									echo "<select name='Id_Type' id='Id_Type' OnChange='submit()' >";
									$Id_Type=$_SESSION['FiltreEPEIndicateurs_TypeEPE'];
									if($_POST)
									{
										if(isset($_POST['Id_Type']))
										{
											$Id_Type=$_POST['Id_Type'];
										}
									}
									$tab=array("EPE","EPP","EPP Bilan");
									foreach($tab as $val)
									{
										$selected="";
										if($_POST){
											if($_POST['Id_Type']==$val){
												$selected="selected";
												$Id_Type=$val;
											}
										}
										else{
											if($Id_Type==$val){
												$selected="selected";
											}
										}
										echo "<option value='".$val."' ".$selected.">".$val."</option>";
									}
									echo "</select>";
								?>
							</td>
						</tr>
					</table>
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
					WHERE Id IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) ";
					if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
						
					}
					else{
						$requetePlateforme.="
						AND Id IN 
							(
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteAssistantRH.",".$IdPosteResponsableRH.")
							)
							";
					}
					$requetePlateforme.="ORDER BY Libelle";
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
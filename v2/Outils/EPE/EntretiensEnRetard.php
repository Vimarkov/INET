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
					$req="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,DatePrevisionnelle,
						TypeEntretien AS TypeE,
						IF(TypeEntretien='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),TypeEntretien) AS TypeEntretien,IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir) AS DateButoir,
						epe_personne_datebutoir.Id AS Id_EpePersonneDB,Cadre
					FROM new_rh_etatcivil
					RIGHT JOIN epe_personne_datebutoir 
					ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne 
					WHERE MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01'  AND Contrat IN ('CDI','CDD','CDIC','CDIE') 
					AND MetierPaie<>'' AND Cadre IN (0,1) 
					AND
					(
						SELECT COUNT(new_competences_personne_prestation.Id)
						FROM new_competences_personne_prestation
						LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
						WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
						AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
						AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
						AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
						AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].")
					)>0 
					AND YEAR(IF(DateReport>'0001-01-01' ,DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." 
					AND IF((SELECT COUNT(Id)
						FROM epe_personne 
						WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1)>0,
						(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01' ,'Réalisé','Signature manager')))
						FROM epe_personne 
						WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEIndicateurs_Annee']." LIMIT 1),
						'A faire') IN ('A faire','Brouillon')
					AND epe_personne_datebutoir.DatePrevisionnelle>'0001-01-01' 
					AND epe_personne_datebutoir.DatePrevisionnelle<'".date('Y-m-d')."'
					";
					if($_SESSION['FiltreEPEIndicateurs_Type']<>""){
						$req.="AND TypeEntretien IN (".$_SESSION['FiltreEPEIndicateurs_Type'].") ";
					}
					$req.="
					AND
					(
						SELECT COUNT(new_competences_personne_prestation.Id)
						FROM new_competences_personne_prestation
						LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
						WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
						AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
						AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
						AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) 
					)>0
					
					AND 
					(
						SELECT Id_Prestation
						FROM new_competences_personne_prestation
						LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
						WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
						AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
						AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
						AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
						ORDER BY Date_Fin DESC, Date_Debut DESC
						LIMIT 1
					) NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
					";
					$req.="ORDER BY Personne ";
					$result=mysqli_query($bdd,$req);
					$nbenreg=mysqli_num_rows($result);
						
				?>
					<tr >
						<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule";}else{echo "Registration number";} ?></td>
						<td class="EnTeteTableauCompetences" width="12%"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "People";} ?></td>
						<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";} ?></td>
						<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
						<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Type";}else{echo "Type";} ?></td>
						<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Date butoir";}else{echo "Deadline";} ?></td>
						<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Date prévisionnelle";}else{echo "Expected date";} ?></td>
						<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Responsable";}else{echo "Responsible";} ?></td>
						<td class="EnTeteTableauCompetences" width="3%">
						&nbsp;<a style="text-decoration:none;" href="javascript:Excel_EntretiensEnRetard();">
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
								AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
								AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".$dateDebut."') 
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
							$Id_Plateforme=0;
							$req="SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,Id_Plateforme,LEFT(Libelle,7) AS Prestation FROM new_competences_prestation WHERE Id=".$Id_Prestation;
							$ResultPresta=mysqli_query($bdd,$req);
							$NbPrest=mysqli_num_rows($ResultPresta);
							if($NbPrest>0){
								$RowPresta=mysqli_fetch_array($ResultPresta);
								$Presta=$RowPresta['Prestation'];
								$Plateforme=$RowPresta['Plateforme'];
								$Id_Plateforme=$RowPresta['Id_Plateforme'];
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
							
							$ReqDroits= "
								SELECT
									Id
								FROM
									new_competences_personne_poste_prestation
								WHERE
									Id_Personne=".$_SESSION['Id_Personne']."
									AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.")
									AND Id_Prestation = ".$Id_Prestation."
									AND Id_Pole = ".$Id_Pole." 
								UNION 
								SELECT Id 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteResponsablePlateforme.")
								AND (Id_Plateforme IN (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation.")
								OR Id_Plateforme=17
								)
									";
							$ResultDroits=mysqli_query($bdd,$ReqDroits);
							$NbEnregDroits=mysqli_num_rows($ResultDroits);
							if($NbEnregDroits==0){$trouve=0;}
							
							if($trouve==1){
					?>
						<tr bgcolor="<?php echo $couleur; ?>">
							<td><?php echo stripslashes($row['MatriculeAAA']);?></td>
							<td><?php echo stripslashes($row['Personne']);?></td>
							<td><?php echo stripslashes($Plateforme);?></td>
							<td><?php echo stripslashes($Presta);?></td>
							<td><?php echo stripslashes($row['TypeEntretien']);?></td>
							<td><?php echo AfficheDateJJ_MM_AAAA($row['DateButoir']);?></td>
							<td><?php echo AfficheDateJJ_MM_AAAA($row['DatePrevisionnelle']);?></td>
							<td colspan="2"><?php echo stripslashes($Manager);?></td>
						</tr>
					<?php 
								if($couleur=="#d6d9dc"){$couleur="#ffffff";}
								else{$couleur="#d6d9dc";}
							}
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
									WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.")
									AND new_rh_etatcivil.Id>0
									AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) ";
							if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
								
							}
							else{
								$requetePersonne.="
									AND
									(
										SELECT COUNT(new_competences_personne_prestation.Id)
										FROM new_competences_personne_prestation
										LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
										WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
										AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
										AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
										AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
										AND 
										((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN 
											(
												SELECT Id_Plateforme 
												FROM new_competences_personne_poste_plateforme
												WHERE Id_Personne=".$_SESSION['Id_Personne']." 
												AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteResponsablePlateforme.")
											)
											OR CONCAT(new_competences_personne_prestation.Id_Prestation,'_',new_competences_personne_prestation.Id_Pole) IN 
											(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
											FROM new_competences_personne_poste_prestation 
											WHERE Id_Personne=".$_SESSION["Id_Personne"]."
											AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
											)
										) 
									)>0
									";
							}
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
					WHERE Id IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) ";
					if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
						
					}
					else{
						$requetePlateforme.="
						AND
						( Id IN 
							(
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteResponsablePlateforme.")
							)
							OR Id IN 
							(SELECT new_competences_prestation.Id_Plateforme
							FROM new_competences_personne_poste_prestation 
							LEFT JOIN new_competences_prestation
							ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
							)
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
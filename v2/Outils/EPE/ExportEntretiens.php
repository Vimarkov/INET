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
			<tr>
				<td>
			<?php if($_POST){ 
				if($_POST['annee']<>""){
					if($_POST['Id_Type']=="EPE"){
						$requete="SELECT DISTINCT 
							Id_Personne,
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
							IF(TypeCadre=1,0,1) AS Cadre,
							(SELECT MatriculeDaher FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MatriculeDaher
							FROM epe_personne 
							WHERE Type = 'EPE' 
							AND Suppr=0 
							AND YEAR(DateButoir) = ".$_POST['annee']."
							AND IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager'))) IN ('Signature salarié','Signature manager','Réalisé')
							AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].")
							";

						$result=mysqli_query($bdd,$requete);
						$nbResulta=mysqli_num_rows($result);
						
						$tabEPE = array();
						$nb=0;
						if($nbResulta>0){
							while($row=mysqli_fetch_array($result))
							{
								if($row['MatriculeDaher']<>""){
									$tabEPE[$nb] = array($row['Id_Personne'],$row['Cadre'],$_POST['annee']);
									$nb++;
								}
								else{
									echo $row['Personne']." : matricule daher manquant<br>";
								}
							}
						}
						ExporterEPEPDFs($tabEPE);
					}
					elseif($_POST['Id_Type']=="EPP"){
						$requete="SELECT DISTINCT 
							Id_Personne,
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
							IF(TypeCadre=1,0,1) AS Cadre,
							(SELECT MatriculeDaher FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MatriculeDaher
							FROM epe_personne 
							WHERE Type = 'EPP' 
							AND Suppr=0 
							AND YEAR(DateButoir) = ".$_POST['annee']."
							AND IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager'))) IN ('Signature salarié','Signature manager','Réalisé')
							AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].")
							";

						$result=mysqli_query($bdd,$requete);
						$nbResulta=mysqli_num_rows($result);
						
						$tabEPE = array();
						$nb=0;
						if($nbResulta>0){
							while($row=mysqli_fetch_array($result))
							{
								if($row['MatriculeDaher']<>""){
									$tabEPE[$nb] = array($row['Id_Personne'],$row['Cadre'],$_POST['annee']);
									$nb++;
								}
								else{
									echo $row['Personne']." : matricule daher manquant<br>";
								}
							}
						}
						ExporterEPPPDFs($tabEPE);
					}
					elseif($_POST['Id_Type']=="EPP Bilan"){
						$requete="SELECT DISTINCT 
							Id_Personne,
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
							IF(TypeCadre=1,0,1) AS Cadre,
							(SELECT MatriculeDaher FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MatriculeDaher
							FROM epe_personne 
							WHERE Type = 'EPP Bilan' 
							AND Suppr=0 
							AND YEAR(DateButoir) = ".$_POST['annee']."
							AND IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager'))) IN ('Signature salarié','Signature manager','Réalisé')
							AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].")
							";
						$result=mysqli_query($bdd,$requete);
						$nbResulta=mysqli_num_rows($result);
						$tabEPE = array();
						$nb=0;
						if($nbResulta>0){
							while($row=mysqli_fetch_array($result))
							{
								if($row['MatriculeDaher']<>""){
									$tabEPE[$nb] = array($row['Id_Personne'],$row['Cadre'],$_POST['annee']);
									$nb++;
								}
								else{
									echo $row['Personne']." : matricule daher manquant<br>";
								}
							}
						}
						ExporterEPPBilanPDFs($tabEPE);
					}
				}
			}
			?>
				</td>
			</tr>
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
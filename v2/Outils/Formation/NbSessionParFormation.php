<?php
if($_POST){
	$Id_Presta="";
	if(isset($_POST['Id_Presta'])){
		if (is_array($_POST['Id_Presta'])) {
			foreach($_POST['Id_Presta'] as $value){
				if($Id_Presta<>''){$Id_Presta.=",";}
			  $Id_Presta.="'".$value."'";
			}
		} else {
			$value = $_POST['Id_Presta'];
			$Id_Presta = "'".$value."'";
		}
	}
	
	$Id_RespProjet="";
	if(isset($_POST['Id_RespProjet'])){
		if (is_array($_POST['Id_RespProjet'])) {
			foreach($_POST['Id_RespProjet'] as $value){
				if($Id_RespProjet<>''){$Id_RespProjet.=",";}
			  $Id_RespProjet.=$value;
			}
		} else {
			$value = $_POST['Id_RespProjet'];
			$Id_RespProjet = $value;
		}
	}
	
	$Id_Formateur="";
	if(isset($_POST['Id_Formateur'])){
		if (is_array($_POST['Id_Formateur'])) {
			foreach($_POST['Id_Formateur'] as $value){
				if($Id_Formateur<>''){$Id_Formateur.=",";}
			  $Id_Formateur.=$value;
			}
		} else {
			$value = $_POST['Id_Formateur'];
			$Id_Formateur = $value;
		}
	}
	
	$Id_Type="";
	if(isset($_POST['Id_Type'])){
		if (is_array($_POST['Id_Type'])) {
			foreach($_POST['Id_Type'] as $value){
				if($Id_Type<>''){$Id_Type.=",";}
			  $Id_Type.=$value;
			}
		} else {
			$value = $_POST['Id_Type'];
			$Id_Type = $value;
		}
	}
	
	$_SESSION['FiltreNbSessionParFormation_Prestations']=$Id_Presta;
	$_SESSION['FiltreNbSessionParFormation_Plateforme']=$_POST['Id_Plateforme'];
	$_SESSION['FiltreNbSessionParFormation_RespProjet']=$Id_RespProjet;
	$_SESSION['FiltreNbSessionParFormation_DateDebut']=$_POST['DateDebut'];
	$_SESSION['FiltreNbSessionParFormation_DateFin']=$_POST['DateFin'];
	$_SESSION['FiltreNbSessionParFormation_Type']=$Id_Type;
	//$_SESSION['FiltreNbSessionParFormation_Formation']=$_POST['Formation'];
	$_SESSION['FiltreNbSessionParFormation_Formateur']=$Id_Formateur;
} 
?>
<table style="width:100%; border-spacing:0; align:center;">
<tr><td height="4"></td>
<tr>
	<td align="center" valign="top" width="75%">
		<table class="GeneralInfo">
			<?php if($_POST){ 
				if($_POST['DateDebut']<>"" && $_POST['DateFin']<>""){
					$req="
						SELECT
							(
								SELECT IF(form_session.Recyclage=1,LibelleRecyclage,Libelle)
								FROM form_formation_langue_infos
								WHERE Id_Formation=form_session.Id_Formation
								AND Id_Langue=
									(SELECT Id_Langue 
									FROM form_formation_plateforme_parametres 
									WHERE Id_Plateforme=form_session.Id_Plateforme
									AND Id_Formation=form_session.Id_Formation
									AND form_formation_plateforme_parametres.Suppr=0 
									LIMIT 1)
								AND Suppr=0
							) AS Formation,
							COUNT(form_session.Id) AS NbFormation
							
						FROM
							form_session_date
						LEFT JOIN form_session
							ON form_session_date.Id_Session = form_session.Id
						WHERE
							form_session_date.Suppr=0
							AND form_session.Suppr=0
							AND form_session.Id_Plateforme
							 IN (
								SELECT
									Id_Plateforme 
								FROM
									new_competences_personne_poste_plateforme
								WHERE
									Id_Personne=".$IdPersonneConnectee."
									AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS).")
							)
							AND Annule=0 
							AND form_session_date.DateSession>='".TrsfDate_($_SESSION['FiltreNbSessionParFormation_DateDebut'])."'
							AND form_session_date.DateSession<='".TrsfDate_($_SESSION['FiltreNbSessionParFormation_DateFin'])."'
							AND Id_Plateforme=".$_SESSION['FiltreNbSessionParFormation_Plateforme']."
							";
						if($_SESSION['FiltreNbSessionParFormation_Formateur']<>""){
							$req.=" AND form_session.Id_Formateur IN (".$_SESSION['FiltreNbSessionParFormation_Formateur'].") ";
						}
						
						if($_SESSION['FiltreNbSessionParFormation_Type']<>""){
							$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE Id=form_session.Id_Formation) IN (".$_SESSION['FiltreNbSessionParFormation_Type'].") ";
						}
						
						$req.=" GROUP BY form_session.Id_Formation
							ORDER BY Formation ASC";
						$result=mysqli_query($bdd,$req);
						$nbenreg=mysqli_num_rows($result);
				?>
					<tr >
						<td class="EnTeteTableauCompetences" width="50%"><?php if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";}?></td>
						<td class="EnTeteTableauCompetences" width="47%"><?php if($LangueAffichage=="FR"){echo "Nombre";}else{echo "Nb";}?></td>
						<td class="EnTeteTableauCompetences" width="3%">
						&nbsp;<a style="text-decoration:none;" href="javascript:Excel_NbSessionParFormation();">
							<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
						</a>&nbsp;
						</td>
					</tr>
				<?php
					if($nbenreg>0){
						$total=0;
						$couleur="#d6d9dc";
				
						while($row=mysqli_fetch_array($result)){
				?>
					<tr bgcolor="<?php echo $couleur; ?>">
						<td ><?php echo $row['Formation'] ; ?></td>
						<td colspan="2"><?php echo $row['NbFormation'] ; ?></td>
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
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Date début";}else{echo "Start date";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateDebut" name="DateDebut" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltreNbSessionParFormation_DateDebut']); ?>"></td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Date fin";}else{echo "End date";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateFin" name="DateFin" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltreNbSessionParFormation_DateFin']); ?>"></td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Type de formation";}else{echo "Type of training";}?> : </td>
			</tr>
			<tr>
				<td>
					<table width='100%'>
						<?php
							$rqType="SELECT Id, Libelle FROM form_typeformation WHERE Suppr=0 ORDER BY Libelle";
							
							$resultType=mysqli_query($bdd,$rqType);
							while($rowType=mysqli_fetch_array($resultType))
							{
								$checked="";
								if($_POST){
									$checkboxes = isset($_POST['Id_Type']) ? $_POST['Id_Type'] : array();
									foreach($checkboxes as $value) {
										if($rowType['Id']==$value){$checked="checked";}
									}
								}
								else{
									$checked="checked";	
								}
								echo "<tr><td>";
								echo "<input type='checkbox' class='checkType' name='Id_Type[]' Id='Id_Type[]' value='".$rowType['Id']."' ".$checked." >".$rowType['Libelle'];
								echo "</td></tr>";
							}
						?>
					</table>
				</td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
			</tr>
			<tr>
				<td>
					<?php 
					$requetePlateforme="SELECT DISTINCT Id_Plateforme AS Id, 
					(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle 
					FROM new_competences_personne_poste_plateforme 
					WHERE Id_Poste 
						IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
					AND Id_Personne=".$IdPersonneConnectee." 
					UNION
					SELECT DISTINCT 
					(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id, 
						(SELECT (SELECT Libelle FROM new_competences_plateforme 
						WHERE new_competences_plateforme.Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Libelle 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Poste 
						IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
					AND Id_Personne=".$IdPersonneConnectee." 
					ORDER BY Libelle";
					$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
					echo "<select name='Id_Plateforme' id='Id_Plateforme' OnChange='submit()' >";
					
					$Id_Plateforme=$_SESSION['FiltrePersFormeesPresta_Plateforme'];
					if($_POST)
					{
						if(isset($_POST['Id_Plateforme']))
						{
							$Id_Plateforme=$_POST['Id_Plateforme'];
						}
					}

					while($LigPlateforme=mysqli_fetch_array($resultPlateforme)){
						$selected="";
						if($_POST){
							if($_POST['Id_Plateforme']==$LigPlateforme['Id']){
								$selected="selected";
								$Id_Plateforme=$LigPlateforme['Id'];
							}
						}
						else{
							if($Id_Plateforme==0){
								$Id_Plateforme=$LigPlateforme['Id'];
								$selected="selected";
							}
						}
						echo "<option value='".$LigPlateforme['Id']."' ".$selected.">".$LigPlateforme['Libelle']."</option>";
					}
					$_SESSION['FiltrePersFormeesPresta_Plateforme']=$Id_Plateforme;
					
					echo "</select>";
					?>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Formateur";}else{echo "Former";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="checkbox" name="selectAllFormateur" id="selectAllFormateur" onclick="SelectionnerToutFormateur()" /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?></td>
			</tr>
			
			<tr>
				<td>
					<div id='Div_Formateur' style="height:100px;overflow:auto;">
						<table width='100%'>
							<?php
								$rqFormateur="SELECT DISTINCT Id_Personne,
								(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Poste IN (".$IdPosteFormateur.")
								AND Id_Plateforme=".$Id_Plateforme."
								AND Id_Personne<>0
								ORDER BY Personne";
								
								$resultFormateur=mysqli_query($bdd,$rqFormateur);
								$Id_Formateur=0;
								
								$checked="";
								if($_POST){
									$checkboxes = isset($_POST['Id_Formateur']) ? $_POST['Id_Formateur'] : array();
										foreach($checkboxes as $value) {
											if(0==$value){$checked="checked";}
										}
								}
								else{
									$checked="checked";	
								}
								echo "<tr><td>";
								echo "<input type='checkbox' class='checkFormateur' name='Id_Formateur[]' Id='Id_Formateur[]' value='0' ".$checked.">";
								echo "</td></tr>";
								while($rowFormateur=mysqli_fetch_array($resultFormateur))
								{
									$checked="";
									if($_POST){
										$checkboxes = isset($_POST['Id_Formateur']) ? $_POST['Id_Formateur'] : array();
										foreach($checkboxes as $value) {
											if($rowFormateur['Id_Personne']==$value){$checked="checked";}
										}
									}
									else{
										$checked="checked";	
									}
									
									echo "<tr><td>";
									echo "<input type='checkbox' class='checkFormateur' name='Id_Formateur[]' Id='Id_Formateur[]' value='".$rowFormateur['Id_Personne']."' ".$checked.">".$rowFormateur['Personne'];
									echo "</td></tr>";
								}
							?>
						</table>
					</div>
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
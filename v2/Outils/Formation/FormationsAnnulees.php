<?php
if($_POST){
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
					
	$_SESSION['FiltreFormAnnulees_Plateforme']=$_POST['Id_Plateforme'];
	$_SESSION['FiltreFormAnnulees_DateDebut']=$_POST['DateDebut'];
	$_SESSION['FiltreFormAnnulees_DateFin']=$_POST['DateFin'];
	$_SESSION['FiltreFormAnnulees_Type']=$Id_Type;
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
							(SELECT Libelle FROM form_typeformation WHERE Id=(SELECT Id_TypeFormation FROM form_formation WHERE Id=form_session.Id_Formation)) AS TypeFormation,
							Recyclage,
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
							(	SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) 
								FROM form_formation_plateforme_parametres 
								WHERE Id_Plateforme=form_session.Id_Plateforme
								AND Id_Formation=form_session.Id_Formation
								AND form_formation_plateforme_parametres.Suppr=0 
								LIMIT 1
							) AS Organisme,
							(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session.Id ORDER BY DateSession ASC LIMIT 1) AS DateDebut,
							(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session.Id ORDER BY DateSession DESC LIMIT 1) AS DateFin
						FROM form_session
						WHERE form_session.Suppr=0
							AND form_session.Id_Plateforme=".$_SESSION['FiltreFormAnnulees_Plateforme']."
							AND Annule=1 
							AND (SELECT COUNT(form_session_date.Id)	 
							FROM form_session_date 
							WHERE form_session_date.Suppr=0 
							AND form_session_date.Id_Session=form_session.Id 
							AND DateSession>='".$_SESSION['FiltreFormAnnulees_DateDebut']."'
							AND DateSession<='".$_SESSION['FiltreFormAnnulees_DateFin']."'
							)>0
							AND Id_Plateforme=".$_SESSION['FiltreFormAnnulees_Plateforme']."
							";
						if($_SESSION['FiltreFormAnnulees_Type']<>""){
							$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE Id=form_session.Id_Formation) IN (".$_SESSION['FiltreFormAnnulees_Type'].") ";
						}
						$req.=" ORDER BY DateDebut ASC";
						$result=mysqli_query($bdd,$req);
						$nbenreg=mysqli_num_rows($result);
						
				?>
					<tr >
						<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Type de formation";}else{echo "Type of training";}?></td>
						<td class="EnTeteTableauCompetences" width="8%"><?php if($LangueAffichage=="FR"){echo "Initial/Recyclage";}else{echo "Initial / Recycling";}?></td>
						<td class="EnTeteTableauCompetences" width="60%"><?php if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";}?></td>
						<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Date début";}else{echo "Start date";}?></td>
						<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Date fin";}else{echo "End date";}?></td>
						<td class="EnTeteTableauCompetences" width="3%">
						&nbsp;<a style="text-decoration:none;" href="javascript:Excel_SessionAnnulees();">
							<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
						</a>&nbsp;
						</td>
					</tr>
				<?php
					if($nbenreg>0){
						$total=0;
						$couleur="#d6d9dc";
				
						while($row=mysqli_fetch_array($result)){
							$organisme="";
							if($row['Organisme']<>""){
								$organisme=" (".$row['Organisme'].")";
							}
							if($row['Recyclage']==1){
								if($LangueAffichage=="FR"){$iniRecy= "Recyclage";}else{$iniRecy= "Recycling";}
							}
							else{
								if($LangueAffichage=="FR"){$iniRecy= "Initial";}else{$iniRecy= "Initial";}
							}
				?>
					<tr bgcolor="<?php echo $couleur; ?>">
						<td ><?php echo $row['TypeFormation'] ; ?></td>
						<td ><?php echo $iniRecy ; ?></td>
						<td ><?php echo $row['Formation'].$organisme ; ?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateDebut']) ; ?></td>
						<td colspan="2"><?php echo AfficheDateJJ_MM_AAAA($row['DateFin']) ; ?></td>
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
				<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateDebut" name="DateDebut" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltreFormAnnulees_DateDebut']); ?>"></td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Date fin";}else{echo "End date";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateFin" name="DateFin" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltreFormAnnulees_DateFin']); ?>"></td>
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
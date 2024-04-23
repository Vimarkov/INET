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
	
	$_SESSION['FiltreBesoinsSansSession_Plateforme']=$_POST['Id_Plateforme'];
	$_SESSION['FiltreBesoinsSansSession_Periode']=$_POST['Periode'];
	$_SESSION['FiltreBesoinsSansSession_Type']=$Id_Type;
} 
?>
<table style="width:100%; border-spacing:0; align:center;">
<tr><td height="4"></td>
<tr>
	<td align="center" valign="top" width="75%">
		<table class="GeneralInfo">
			<?php if($_POST){ 
			
					$requetePersonnes="
						SELECT
							Id_Personne
						FROM
							new_competences_personne_prestation
						WHERE
							Date_Fin>='".date('Y-m-d')."' 
							AND Id_Prestation IN (SELECT Id_Prestation FROM new_competences_prestation WHERE Id_Plateforme=".$_SESSION['FiltreBesoinsSansSession_Plateforme']." )
							";
					$resultPersResp=mysqli_query($bdd,$requetePersonnes);
					$nbPersResp=mysqli_num_rows($resultPersResp);
					$listeRespPers=0;
					if($nbPersResp>0)
					{
						$listeRespPers="";
						while($rowPersResp=mysqli_fetch_array($resultPersResp)){$listeRespPers.=$rowPersResp['Id_Personne'].",";}
						$listeRespPers=substr($listeRespPers,0,-1);
					}

					$requete="	SELECT 
						form_typeformation.Libelle AS LIBELLE_TYPEFORMATION,
						form_besoin.Id_Formation,
						form_formation.Reference AS REFERENCE_FORMATION,
						(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
							WHERE form_formation_plateforme_parametres.Id_Formation=form_besoin.Id_Formation
							AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
							AND Suppr=0 LIMIT 1) AS Organisme,
						 IF(form_besoin.Motif='Renouvellement' AND form_formation.Recyclage=1,1,0) AS Recyclage,
						(SELECT IF(form_besoin.Motif='Renouvellement',
								IF(LibelleRecyclage='',Libelle,LibelleRecyclage),
								Libelle
								)
							FROM form_formation_langue_infos
							WHERE form_formation_langue_infos.Id_Formation=form_besoin.Id_Formation
							AND form_formation_langue_infos.Id_Langue=
								(SELECT Id_Langue 
								FROM form_formation_plateforme_parametres 
								WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
								AND Id_Formation=form_besoin.Id_Formation
								AND Suppr=0 
								LIMIT 1)
							AND Suppr=0) AS Libelle,
						COUNT(form_besoin.Id) AS NombreFormation
					FROM
						form_besoin,
						form_typeformation,
						form_formation,
						new_rh_etatcivil,
						new_competences_prestation
					WHERE
						form_besoin.Id_Formation=form_formation.Id
						AND form_formation.Id_TypeFormation=form_typeformation.Id
						AND form_besoin.Id_Prestation=new_competences_prestation.Id
						AND form_besoin.Id_Personne=new_rh_etatcivil.Id
						AND form_besoin.Traite=0
						AND form_besoin.Suppr=0
						AND form_besoin.Valide=1 ";
					if($Id_Type<>""){
						$requete.=" AND form_formation.Id_TypeFormation IN(".$Id_Type.") ";
					}
					$requete.=" AND form_besoin.Id_Personne IN
						(".$listeRespPers.")
						GROUP BY Libelle, Organisme
						ORDER BY Libelle, Organisme
						 ";
					$result=mysqli_query($bdd,$requete);
					$nbenreg=mysqli_num_rows($result);
			?>
				<tr >
					<td class="EnTeteTableauCompetences" width="60%"><?php if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";}?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?></td>
					<td class="EnTeteTableauCompetences" width="5%"><?php if($LangueAffichage=="FR"){echo "Nombre";}else{echo "Number";}?></td>
					<td class="EnTeteTableauCompetences" width="27%"><?php if($LangueAffichage=="FR"){echo "Date dernière session";}else{echo "Date last session";}?></td>
					<td class="EnTeteTableauCompetences" width="3%">
					&nbsp;<a style="text-decoration:none;" href="javascript:Excel_BesoinsSansSession();">
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
							$organisme=" ".$row['Organisme'];
						}
						
						//Date dernière session de formation ou formation similaire 
						$reqF="
						SELECT
							form_session_date.DateSession
						FROM
							form_session_date
						LEFT JOIN form_session
							ON form_session_date.Id_Session=form_session.Id
						WHERE
							form_session_date.Suppr=0
							
							AND form_session.Suppr=0
							AND form_session.Annule=0
							AND form_session.Diffusion_Creneau=1
							AND (
								(SELECT form_formation.Recyclage FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=0
								OR
								((SELECT form_formation.Recyclage FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=1
								AND form_session.Recyclage=".$row['Recyclage']."
								)
								)
							AND (form_session.Id_Formation=".$row['Id_Formation']."
									OR form_session.Id_Formation IN  (SELECT Id_Formation 
								FROM form_formationequivalente_formationplateforme 
								WHERE Id_FormationEquivalente IN (SELECT Id_FormationEquivalente 
								FROM form_formationequivalente_formationplateforme 
								WHERE Id_Formation=".$row['Id_Formation']."))
							)
						ORDER BY
							form_session_date.DateSession DESC ";
					$resultSession=mysqli_query($bdd,$reqF);
					$nbSession=mysqli_num_rows($resultSession);
					$dateSession="";
					$dateSession2=date('0001-01-01');
					if($nbSession>0){
						$rowSession=mysqli_fetch_array($resultSession);
						$dateSession=AfficheDateJJ_MM_AAAA($rowSession['DateSession']);
						$dateSession2=$rowSession['DateSession'];
					}
					
					
					if($_SESSION['FiltreBesoinsSansSession_Periode']=="1 mois"){
						$periode=date('Y-m-d', strtotime(date('Y-m-d')." -1 month"));
					}
					elseif($_SESSION['FiltreBesoinsSansSession_Periode']=="3 mois"){
						$periode=date('Y-m-d', strtotime(date('Y-m-d')." -3 month"));
					}
					elseif($_SESSION['FiltreBesoinsSansSession_Periode']=="6 mois"){
						$periode=date('Y-m-d', strtotime(date('Y-m-d')." -6 month"));
					}
					elseif($_SESSION['FiltreBesoinsSansSession_Periode']=="1 an"){
						$periode=date('Y-m-d', strtotime(date('Y-m-d')." -1 year"));
					}
					
					$ok=0;
					if($dateSession2<$periode){$ok=1;}
					
					if($ok==1){
			?>
				<tr bgcolor="<?php echo $couleur; ?>">
					<td ><?php echo $row['Libelle'].$organisme ; ?></td>
					<td ><?php echo $row['LIBELLE_TYPEFORMATION'] ; ?></td>
					<td ><?php echo $row['NombreFormation'] ; ?></td>
					<td colspan="2"><?php echo $dateSession ; ?></td>
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
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Période";}else{echo "Period";}?> : </td>
			</tr>
			<tr>
				<td>
					<?php 
					echo "<select name='Periode' id='Periode'>";
					
					$selected="";
					if($_SESSION['FiltreBesoinsSansSession_Periode']=='1 mois'){$selected="selected";}
					if($LangueAffichage=="FR"){echo "<option value='1 mois' ".$selected.">1 mois</option>";}
					else{echo "<option value='1 mois' ".$selected.">1 month</option>";}
					
					$selected="";
					if($_SESSION['FiltreBesoinsSansSession_Periode']=='3 mois'){$selected="selected";}
					if($LangueAffichage=="FR"){echo "<option value='3 mois' ".$selected.">3 mois</option>";}
					else{echo "<option value='3 mois' ".$selected.">3 month</option>";}
					
					$selected="";
					if($_SESSION['FiltreBesoinsSansSession_Periode']=='6 mois'){$selected="selected";}
					if($LangueAffichage=="FR"){echo "<option value='6 mois' ".$selected.">6 mois</option>";}
					else{echo "<option value='6 mois' ".$selected.">6 month</option>";}
					
					$selected="";
					if($_SESSION['FiltreBesoinsSansSession_Periode']=='1 an'){$selected="selected";}
					if($LangueAffichage=="FR"){echo "<option value='1 an' ".$selected.">1 an</option>";}
					else{echo "<option value='1 an' ".$selected.">1 year</option>";}
					
					echo "</select>";
					?>
				</td>
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
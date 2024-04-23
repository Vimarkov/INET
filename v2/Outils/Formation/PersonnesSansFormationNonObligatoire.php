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
} 
?>
<table style="width:100%; border-spacing:0; align:center;">
<tr><td height="4"></td>
<tr>
	<td align="center" valign="top" width="75%">
		<table class="GeneralInfo">
			<?php if($_POST){ 
				if($_POST['DateDebut']<>"" && $_POST['DateFin']<>""){
					$qualification="";
					$req="SELECT DISTINCT Id_Qualification
						FROM form_formation_qualification
						LEFT JOIN form_formation 
						ON form_formation_qualification.Id_Formation=form_formation.Id
						WHERE form_formation.Obligatoire=0
						AND form_formation.Suppr=0 
						AND form_formation_qualification.Suppr=0 ";
					$resultFormE=mysqli_query($bdd,$req);
					while($rowFormE=mysqli_fetch_array($resultFormE))
					{
						if($qualification<>""){$qualification.=",";}
						$qualification.=$rowFormE['Id_Qualification'];
					}
					
					$req="SELECT DISTINCT Id_Personne, Id_Prestation, Id_Pole,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
					new_competences_prestation.Libelle AS Prestation,
					(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole
					FROM new_competences_personne_prestation 
					LEFT JOIN new_competences_prestation
					ON new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
					WHERE Date_Debut<='".date('Y-m-d')."'
					AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01' )
					AND (
						Id_Personne NOT IN (
						SELECT Id_Personne
						FROM new_competences_relation 
						WHERE Id_Qualification_Parrainage IN (".$qualification.")
						AND Date_QCM>='".TrsfDate_($_POST['DateDebut'])."'
						AND Date_QCM<='".TrsfDate_($_POST['DateFin'])."'
						AND Suppr=0) 
						
						AND 
						
						Id_Personne NOT IN
							(SELECT
								form_besoin.Id_Personne
							FROM
								form_besoin
							LEFT JOIN form_formation
							ON form_besoin.Id_Formation=form_formation.Id
							WHERE
								form_besoin.Suppr=0
								AND form_besoin.Valide=1
								AND form_besoin.Traite=4
								AND form_formation.Obligatoire=0
								AND form_besoin.Id IN
								(
								SELECT DISTINCT
									Id_Besoin
								FROM
									form_session_personne,
									form_session_date
								WHERE
									form_session_personne.Id_Session=form_session_date.Id_Session
									AND form_session_personne.Id NOT IN 
										(
										SELECT
											Id_Session_Personne
										FROM
											form_session_personne_qualification
										WHERE
											Suppr=0	
										)
									AND form_session_date.DateSession>='".TrsfDate_($_POST['DateDebut'])."'
									AND form_session_date.DateSession<='".TrsfDate_($_POST['DateFin'])."'
									AND form_session_personne.Suppr=0
									AND form_session_personne.Validation_Inscription=1
									AND form_session_personne.Presence=1
								)
							) 
						) ";
					$req.="AND new_competences_prestation.Id_Plateforme=".$_POST['Id_Plateforme']." ";
					if($Id_Presta<>""){$req.="AND CONCAT(Id_Prestation,'_',Id_Pole) IN (".$Id_Presta.")";}
					if($Id_RespProjet<>""){
						$req.="AND CONCAT(Id_Prestation,'_',Id_Pole) 
									IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
										FROM new_competences_personne_poste_prestation
										WHERE Id_Personne IN (".$Id_RespProjet.")
										AND Id_Poste IN (".$IdPosteResponsableProjet.")
									)
									";
					}
					$req.=" GROUP BY Id_Personne
					ORDER BY Personne
					";
					$result=mysqli_query($bdd,$req);
					$nbenreg=mysqli_num_rows($result);
					
					$_SESSION['FiltrePersSansFormNonO_Prestations']=$Id_Presta;
					$_SESSION['FiltrePersSansFormNonO_Plateforme']=$_POST['Id_Plateforme'];
					$_SESSION['FiltrePersSansFormNonO_RespProjet']=$Id_RespProjet;
					$_SESSION['FiltrePersSansFormNonO_DateDebut']=TrsfDate_($_POST['DateDebut']);
					$_SESSION['FiltrePersSansFormNonO_DateFin']=TrsfDate_($_POST['DateFin']);

					if($nbenreg>0){
						$total=0;
						$couleur="#d6d9dc";
				?>
					<tr >
						<td class="EnTeteTableauCompetences" width="50%"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?></td>
						<td class="EnTeteTableauCompetences" width="47%"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}?></td>
						<td class="EnTeteTableauCompetences" width="3%">
						&nbsp;<a style="text-decoration:none;" href="javascript:Excel_PersonnesSansFormationNonObligatoire();">
							<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
						</a>&nbsp;
						</td>
					</tr>
				<?php
						while($row=mysqli_fetch_array($result)){
				?>
					<tr bgcolor="<?php echo $couleur; ?>">
						<td><?php echo "<a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$row['Id_Personne']."\");'>".$row['Personne']."</a>" ; ?></td>
						<td colspan="2"><?php echo substr($row['Prestation'],0,7)." ".$row['Pole'] ; ?></td>
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
				<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateDebut" name="DateDebut" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltrePersSansFormNonO_DateDebut']); ?>"></td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Date fin";}else{echo "End date";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateFin" name="DateFin" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltrePersSansFormNonO_DateFin']); ?>"></td>
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
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Responsable Projet";}else{echo "Project manager";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="checkbox" name="selectAllRespProjet" id="selectAllRespProjet" onclick="SelectionnerToutRespProjet()" /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?></td>
			</tr>
			
			<tr>
				<td>
					<div id='Div_RespProjet' style="height:100px;overflow:auto;">
						<table width='100%'>
							<?php
								$rqRespProjet="SELECT DISTINCT Id_Personne,
								(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
								FROM new_competences_personne_poste_prestation 
								LEFT JOIN new_competences_prestation
								ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
								WHERE Id_Poste IN (".$IdPosteResponsableProjet.")
								AND Id_Plateforme=".$Id_Plateforme."
								AND Id_Personne<>0
								ORDER BY Personne";
								
								$resultRespProjet=mysqli_query($bdd,$rqRespProjet);
								$Id_RespProjet=0;
								while($rowRespProjet=mysqli_fetch_array($resultRespProjet))
								{
									$checked="";
									if($_POST){
										$checkboxes = isset($_POST['Id_RespProjet']) ? $_POST['Id_RespProjet'] : array();
										foreach($checkboxes as $value) {
											if($rowRespProjet['Id_Personne']==$value){$checked="checked";}
										}
									}
									else{
										$checked="checked";	
									}
									
									echo "<tr><td>";
									echo "<input type='checkbox' class='checkRespProjet' name='Id_RespProjet[]' Id='Id_RespProjet[]' value='".$rowRespProjet['Id_Personne']."' ".$checked.">".$rowRespProjet['Personne'];
									echo "</td></tr>";
								}
							?>
						</table>
					</div>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="checkbox" name="selectAll" id="selectAll" onclick="SelectionnerTout()" /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?></td>
			</tr>
			
			<tr>
				<td>
					<div id='Div_Prestations' style="height:200px;overflow:auto;">
						<table width='100%'>
							<?php
								if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS)){
									$rqPrestation="SELECT Id AS Id_Prestation, 
										Id_Plateforme,
										Libelle,
										0 AS Id_Pole,
										'' AS Pole
										FROM new_competences_prestation 
										WHERE Id NOT IN (
											SELECT Id_Prestation
											FROM new_competences_pole
											WHERE Actif=0
										)
										AND new_competences_prestation.Active=0
										AND Id_Plateforme=".$Id_Plateforme."
										
										UNION
										
										SELECT Id_Prestation,
										new_competences_prestation.Id_Plateforme,
										new_competences_prestation.Libelle,
										new_competences_pole.Id AS Id_Pole,
										CONCAT(' - ',new_competences_pole.Libelle) AS Pole
										FROM new_competences_pole
										INNER JOIN new_competences_prestation
										ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
										AND new_competences_pole.Actif=0
										AND new_competences_prestation.Active=0
										AND new_competences_prestation.Id_Plateforme=".$Id_Plateforme."
										ORDER BY Libelle, Pole";
								}
								else{
									$rqPrestation="SELECT Id AS Id_Prestation, 
										Id_Plateforme,
										Libelle,
										0 AS Id_Pole,
										'' AS Pole
										FROM new_competences_prestation 
										WHERE Id NOT IN (
											SELECT Id_Prestation
											FROM new_competences_pole 
											WHERE Actif=0   
										)
										AND (SELECT COUNT(Id)
											FROM new_competences_personne_poste_prestation
											WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")
											AND Id_Personne=".$IdPersonneConnectee." 
											AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id)>0
										AND new_competences_prestation.Active=0
										AND Id_Plateforme=".$Id_Plateforme."
										AND Active=0
										
										UNION
										
										SELECT Id_Prestation,
										new_competences_prestation.Id_Plateforme,
										new_competences_prestation.Libelle,
										new_competences_pole.Id AS Id_Pole,
										CONCAT(' - ',new_competences_pole.Libelle) AS Pole
										FROM new_competences_pole
										INNER JOIN new_competences_prestation
										ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
										WHERE (SELECT COUNT(Id)
											FROM new_competences_personne_poste_prestation
											WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")
											AND Id_Personne=".$IdPersonneConnectee." 
											AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
											AND new_competences_personne_poste_prestation.Id_Pole=new_competences_pole.Id)>0
										AND new_competences_pole.Actif=0
										AND new_competences_prestation.Active=0
										AND Id_Plateforme=".$Id_Plateforme."
										AND Active=0
										AND Actif=0
										ORDER BY Libelle, Pole";
								}
								$resultPrestation=mysqli_query($bdd,$rqPrestation);
								$Id_PrestationPole=0;
								while($rowPrestation=mysqli_fetch_array($resultPrestation))
								{
									$checked="";
									if($_POST){
										$checkboxes = isset($_POST['Id_Presta']) ? $_POST['Id_Presta'] : array();
										foreach($checkboxes as $value) {
											if($rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole']==$value){$checked="checked";}
										}
									}
									else{
										$checked="checked";	
									}
									
									echo "<tr><td>";
									echo "<input type='checkbox' class='checkPresta' name='Id_Presta[]' Id='Id_Presta[]' value='".$rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole']."' ".$checked.">".stripslashes(substr($rowPrestation['Libelle'],0,7).$rowPrestation['Pole']);
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
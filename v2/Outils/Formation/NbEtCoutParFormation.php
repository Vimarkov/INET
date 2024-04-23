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
	
	$_SESSION['FiltreNbEtCoutParFormation_Plateforme']=$_POST['Id_Plateforme'];
	$_SESSION['FiltreNbEtCoutParFormation_DateDebut']=$_POST['DateDebut'];
	$_SESSION['FiltreNbEtCoutParFormation_DateFin']=$_POST['DateFin'];
	$_SESSION['FiltreNbEtCoutParFormation_Type']=$Id_Type;
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
							COUNT(form_session.Id) AS NbFormation,
							(SELECT COUNT(Id_Personne) 
								FROM form_session_personne
								LEFT JOIN form_session AS Tab_Session
								ON form_session_personne.Id_Session=Tab_Session.Id
								WHERE form_session_personne.Suppr=0 
								AND Presence=1
								AND Validation_Inscription=1
								AND Tab_Session.Id_Formation=form_session.Id_Formation
								AND Tab_Session.Annule=0 
								AND Tab_Session.Suppr=0 
								AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) >='".TrsfDate_($_SESSION['FiltreNbEtCoutParFormation_DateDebut'])."'
								AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) <='".TrsfDate_($_SESSION['FiltreNbEtCoutParFormation_DateFin'])."'
							) AS NbStagiaire,
							(SELECT
								IF(form_session.Recyclage=0,Duree,DureeRecyclage)
								FROM
									form_formation_plateforme_parametres 
								WHERE
									form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
								AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
								AND Suppr=0 LIMIT 1
							) AS Duree,
							(SELECT SUM(form_session_personne.Cout) 
								FROM form_session_personne
								LEFT JOIN form_session AS Tab_Session
								ON form_session_personne.Id_Session=Tab_Session.Id
								WHERE Tab_Session.Id_Formation=form_session.Id_Formation
								AND Tab_Session.Annule=0 
								AND Tab_Session.Suppr=0 
								AND ((form_session_personne.Suppr=0 AND Validation_Inscription=1) OR 
								 (
									(
										(form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0)
										OR form_session_personne.Validation_Inscription=-1
									)
									AND AComptabiliser=1
								 )
								)
								AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) >='".TrsfDate_($_SESSION['FiltreNbEtCoutParFormation_DateDebut'])."'
								AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) <='".TrsfDate_($_SESSION['FiltreNbEtCoutParFormation_DateFin'])."'
							) AS CoutPedagogique,
							(SELECT SUM(
									(SELECT IF((SELECT EstInterim FROM rh_typecontrat WHERE Id=Id_TypeContrat)=1,TauxHoraire*1.48*(SELECT
											IF(form_session.Recyclage=0,Duree,DureeRecyclage)
											FROM
												form_formation_plateforme_parametres 
											WHERE
												form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
											AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
											AND Suppr=0 LIMIT 1
										),(SalaireBrut/(SELECT NbHeureMois FROM rh_tempstravail WHERE Id=Id_TempsTravail))*1.48*(SELECT
										IF(form_session.Recyclage=0,Duree,DureeRecyclage)
										FROM
											form_formation_plateforme_parametres 
										WHERE
											form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
										AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
										AND Suppr=0 LIMIT 1
									)) FROM rh_personne_contrat WHERE rh_personne_contrat.Suppr=0 AND rh_personne_contrat.DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session.Id ORDER BY DateSession ASC LIMIT 1)
									AND (rh_personne_contrat.DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session.Id ORDER BY DateSession ASC LIMIT 1) OR rh_personne_contrat.DateFin<='0001-01-01')
									AND TypeDocument IN ('Nouveau','Avenant') AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
									ORDER BY DateDebut DESC, Id DESC LIMIT 1)
								)
								FROM form_session_personne
								LEFT JOIN form_session AS Tab_Session
								ON form_session_personne.Id_Session=Tab_Session.Id
								WHERE form_session_personne.Suppr=0 
								AND Presence=1
								AND Validation_Inscription=1
								AND Tab_Session.Annule=0 
								AND Tab_Session.Suppr=0 
								AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) >='".TrsfDate_($_SESSION['FiltreNbEtCoutParFormation_DateDebut'])."'
								AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) <='".TrsfDate_($_SESSION['FiltreNbEtCoutParFormation_DateFin'])."'
								
							) AS CoutSalarial
							
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
							AND form_session_date.DateSession>='".TrsfDate_($_SESSION['FiltreNbEtCoutParFormation_DateDebut'])."'
							AND form_session_date.DateSession<='".TrsfDate_($_SESSION['FiltreNbEtCoutParFormation_DateFin'])."'
							AND Id_Plateforme=".$_SESSION['FiltreNbEtCoutParFormation_Plateforme']."
							";
						if($_SESSION['FiltreNbEtCoutParFormation_Type']<>""){
							$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE Id=form_session.Id_Formation) IN (".$_SESSION['FiltreNbEtCoutParFormation_Type'].") ";
						}
						
						$req.=" GROUP BY form_session.Id_Formation, form_session.Recyclage
							ORDER BY Formation ASC";

						$result=mysqli_query($bdd,$req);
						$nbenreg=mysqli_num_rows($result);
				?>
					<tr >
						<td class="EnTeteTableauCompetences" width="40%"><?php if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";}?></td>
						<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Nbr de stagiaires présents";}else{echo "Number of trainees present";}?></td>
						<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Nbr d'heures totales";}else{echo "Total hours";}?></td>
						<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Coût pédagogique total";}else{echo "Total educational cost";}?></td>
						<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Coût salarial total";}else{echo "Total salary cost";}?></td>
						<td class="EnTeteTableauCompetences" width="3%">
						&nbsp;<a style="text-decoration:none;" href="javascript:Excel_NbEtCoutParFormation();">
							<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
						</a>&nbsp;
						</td>
					</tr>
				<?php
					if($nbenreg>0){
						$total=0;
						$couleur="#d6d9dc";
				
						while($row=mysqli_fetch_array($result)){
							$tab=explode(".",$row['Duree']);
							$heure=$tab[0];
							$minute=$tab[1]*(50/30);
							$duree=doubleval($heure.".".$minute);
							$duree=$duree*$row['NbStagiaire'];
				?>
					<tr bgcolor="<?php echo $couleur; ?>">
						<td ><?php echo $row['Formation'] ; ?></td>
						<td ><?php echo $row['NbStagiaire'] ; ?></td>
						<td ><?php echo $duree ; ?></td>
						<td ><?php echo $row['CoutPedagogique'] ; ?></td>
						<td colspan="2"><?php echo round($row['CoutSalarial'],2) ; ?></td>
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
				<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateDebut" name="DateDebut" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltreNbEtCoutParFormation_DateDebut']); ?>"></td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Date fin";}else{echo "End date";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateFin" name="DateFin" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltreNbEtCoutParFormation_DateFin']); ?>"></td>
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
					
					$Id_Plateforme=$_SESSION['FiltreNbEtCoutParFormation_Plateforme'];
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
					$_SESSION['FiltreNbEtCoutParFormation_Plateforme']=$Id_Plateforme;
					
					echo "</select>";
					?>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
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
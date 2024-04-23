<?php
if($_POST){
	$_SESSION['FiltrePersonnesFormationNonEnCours_Plateforme']=$_POST['Id_Plateforme'];
	$_SESSION['FiltrePersonnesFormationNonEnCours_Formation']=$_POST['Id_Formation'];
} 
?>
<table style="width:100%; border-spacing:0; align:center;">
<tr><td height="4"></td>
<tr>
	<td align="center" valign="top" width="75%">
		<table class="GeneralInfo">
			<?php if($_POST){ ?>
					<?php
						$tabQual=explode("_",$_POST['Id_Formation']);
						$qualification="";
						if($_POST['Id_Formation']<>"0_0" && $_POST['Id_Formation']<>""){
							if($tabQual[1]==0){
								$req="SELECT DISTINCT Id_Qualification
									FROM form_formation_qualification
									WHERE Id_Formation=".$tabQual[0]."
									AND Suppr=0 ";
								$resultFormE=mysqli_query($bdd,$req);
								while($rowFormE=mysqli_fetch_array($resultFormE))
								{
									if($qualification<>""){$qualification.=",";}
									$qualification.=$rowFormE['Id_Qualification'];
								}
							}
							else{
								$req="SELECT DISTINCT Id_Qualification
									FROM form_formation_qualification
									WHERE Id_Formation IN 
									(SELECT Id_Formation 
									FROM form_formationequivalente_formationplateforme 
									WHERE Suppr=0 
									AND Id_FormationEquivalente=".$tabQual[0].") 
									AND Suppr=0 ";
								$resultFormE=mysqli_query($bdd,$req);
								while($rowFormE=mysqli_fetch_array($resultFormE))
								{
									if($qualification<>""){$qualification.=",";}
									$qualification.=$rowFormE['Id_Qualification'];
								}
									
							}
						}
						
						if($_POST['Id_Formation']<>"0_0" && $_POST['Id_Formation']<>""){
						$req="SELECT DISTINCT Id_Personne, Id_Prestation, Id_Pole,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
						new_competences_prestation.Libelle AS Prestation,
						(SELECT (SELECT Libelle FROM new_competences_metier WHERE Id=Id_Metier) FROM new_competences_personne_metier WHERE new_competences_personne_metier.Id_Personne=new_competences_personne_prestation.Id_Personne ORDER BY Futur DESC LIMIT 1) AS Metier,
						(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole
						FROM new_competences_personne_prestation 
						LEFT JOIN new_competences_prestation
						ON new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
						WHERE Date_Debut<='".date('Y-m-d')."'
						AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01' )
						AND 
						 ";
						if($qualification<>""){
							$req.=" Id_Personne NOT IN (
							SELECT Id_Personne
							FROM new_competences_relation 
							WHERE Id_Qualification_Parrainage IN (".$qualification.")
							AND Evaluation IN ('L','Q','S','T','V','X')
							AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01')
							AND Suppr=0)
							";
						}
						else{
							$req.=" Id_Personne NOT IN
									(SELECT
										form_besoin.Id_Personne
									FROM
										form_besoin
									WHERE
										form_besoin.Suppr=0
										AND form_besoin.Valide=1
										AND form_besoin.Traite=4
										AND form_besoin.Id IN
										(
										SELECT
											Id_Besoin
										FROM
											form_session_personne
										WHERE
											form_session_personne.Id NOT IN 
												(
												SELECT
													Id_Session_Personne
												FROM
													form_session_personne_qualification
												WHERE
													Suppr=0	
												)
											AND Suppr=0
											AND form_session_personne.Validation_Inscription=1
											AND form_session_personne.Presence=1
										)
									) 
									AND Id_Personne NOT IN (
										SELECT Id_Personne
										FROM new_competences_personne_formation
										WHERE Id_Formation IN (
											SELECT DISTINCT Id_FormationCompetence
											FROM form_formation_formationcompetence
											WHERE Id_Formation=".$tabQual[0]."
											AND Suppr=0
										)
									)";
						}
						$req.="AND new_competences_prestation.Id_Plateforme=".$_POST['Id_Plateforme']." ";
						$req.=" GROUP BY Id_Personne
						ORDER BY Personne
						";
						$result=mysqli_query($bdd,$req);
						$nbenreg=mysqli_num_rows($result);
						
						if($nbenreg>0){
							$total=0;
							$couleur="#d6d9dc";
					?>
						<tr >
							<td class="EnTeteTableauCompetences" width="30%"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?></td>
							<td class="EnTeteTableauCompetences" width="20%"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}?></td>
							<td class="EnTeteTableauCompetences" width="40%"><?php if($LangueAffichage=="FR"){echo "Métier";}else{echo "Job";}?></td>
							<td class="EnTeteTableauCompetences" width="3%">
							&nbsp;<a style="text-decoration:none;" href="javascript:Excel_PersonnesFormationNonEnCours();">
								<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
							</a>&nbsp;
							</td>
						</tr>
					<?php
							while($row=mysqli_fetch_array($result)){
					?>
						<tr bgcolor="<?php echo $couleur; ?>">
							<td><?php echo "<a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$row['Id_Personne']."\");'>".$row['Personne']."</a>" ; ?></td>
							<td><?php echo substr($row['Prestation'],0,7)." ".$row['Pole'] ; ?></td>
							<td colspan="2"><?php echo $row['Metier'] ; ?></td>
						</tr>
					<?php 
								if($couleur=="#d6d9dc"){$couleur="#ffffff";}
								else{$couleur="#d6d9dc";}
							} 
						}
						}
						?>
				</td>
			</tr>
			<?php } ?>
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
			<tr>
				<td class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Formation ";}else{echo "Training ";}?></td>
			</tr>
			<tr>
				<td>
					<?php
						
					?>
					<select name="Id_Formation" id="Id_Formation" style="width:200px">
						<option value="0_0"></option>
						<?php
						$laformation=$_SESSION['FiltrePersonnesFormationNonEnCours_Formation'];

						$requete="
								SELECT 
									IF(Id_FormationEquivalente>0,Id_FormationEquivalente,Id_Formation) AS Id_Formation,
									IF(Id_FormationEquivalente>0,FormationEquivalente,Libelle) AS Formation,
									IF(Id_FormationEquivalente>0,1,0) AS FormEquivalence
								FROM 
								(SELECT DISTINCT
									form_formation.Id AS Id_Formation,
									(SELECT 
									(SELECT Libelle FROM form_formationequivalente WHERE form_formationequivalente.Id=form_formationequivalente_formationplateforme.Id_FormationEquivalente)
									 FROM form_formationequivalente_formationplateforme
									 WHERE form_formationequivalente_formationplateforme.Id_Formation=form_formation.Id
									 AND form_formationequivalente_formationplateforme.Recyclage=0
									 LIMIT 1) AS FormationEquivalente,
									 (SELECT form_formationequivalente_formationplateforme.Id_FormationEquivalente
									 FROM form_formationequivalente_formationplateforme
									 WHERE form_formationequivalente_formationplateforme.Id_Formation=form_formation.Id 
									 AND form_formationequivalente_formationplateforme.Recyclage=0
									LIMIT 1) AS Id_FormationEquivalente,
									(SELECT Libelle
										FROM form_formation_langue_infos
										WHERE Id_Formation=form_formation.Id
										AND Id_Langue=
											(SELECT Id_Langue 
											FROM form_formation_plateforme_parametres 
											WHERE Id_Plateforme=".$Id_Plateforme."
											AND Id_Formation=form_formation.Id
											AND Suppr=0 
											LIMIT 1)
										AND Suppr=0) AS Libelle,(@row_number:=@row_number + 1) AS rnk
								FROM
									form_formation
								WHERE 
									form_formation.Suppr=0 
									AND 
									(SELECT COUNT(Id)
										FROM form_formation_langue_infos
										WHERE Id_Formation=form_formation.Id
										AND Id_Langue=
											(SELECT Id_Langue 
											FROM form_formation_plateforme_parametres 
											WHERE Id_Plateforme=".$Id_Plateforme."
											AND Id_Formation=form_formation.Id
											AND Suppr=0)
										AND Suppr=0)>0
								GROUP BY
									form_formation.Id
								ORDER BY
									Libelle) AS TAB
								GROUP BY 
									IF(Id_FormationEquivalente>0,Id_FormationEquivalente,Id_Formation),IF(Id_FormationEquivalente>0,1,0)
								ORDER BY 
									Formation
									";
						$resultForm=mysqli_query($bdd,$requete);
						while($rowForm=mysqli_fetch_array($resultForm))
						{
							$selected="";
							if($laformation<>"")
							{
								if($laformation==$rowForm['Id_Formation']."_".$rowForm['FormEquivalence']){$selected="selected";}
							}
							echo "<option value='".$rowForm['Id_Formation']."_".$rowForm['FormEquivalence']."' ".$selected.">";
							echo stripslashes($rowForm['Formation']);
							echo "</option>\n";
						}
						?>
					</select>
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
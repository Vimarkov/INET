<input type="hidden" id="Menu" name="Menu" value="<?php echo $Menu; ?>">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td height="10"></td>
	</tr>
	<tr><td width="100%" colspan="3">
		<table width="100%" cellpadding="0" cellspacing="0" align="center" class="TableCompetences">
			<tr><td height="4"></td></tr>
			<tr>
				<td width="15%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Entité : ";}else{echo "Entity : ";}?></td>
				<td width="15%">
					<select name="plateforme" style="width:150px;" onchange="submit();">
					<?php
					$req="SELECT DISTINCT 
						Id_Plateforme,
						(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS UER
						FROM new_competences_relation 
						RIGHT JOIN new_competences_personne_plateforme 
						ON new_competences_relation.Id_Personne=new_competences_personne_plateforme.Id_Personne
						WHERE new_competences_relation.Suppr=0
						AND (Evaluation='L'
						OR 
						(Evaluation='X'
						AND new_competences_relation.Date_Debut<='".date('Y-m-d')."'
						AND (new_competences_relation.Date_Fin>='".date('Y-m-d')."' OR new_competences_relation.Date_Fin<='0001-01-01'))
						)
						AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777) ";
						
						if($nbAccess>0 || $nbSuperAdmin>0){
							
						}
						else{
							$req.="AND (
								(
									SELECT COUNT(Id_Plateforme)
									FROM new_competences_personne_poste_plateforme 
									WHERE Id_Personne=".$_SESSION['Id_Personne']."
									AND Id_Poste IN (".$IdPosteReferentQualiteSysteme.",".$IdPosteResponsableQualite.")
									AND new_competences_personne_poste_plateforme.Id_Plateforme =new_competences_personne_plateforme.Id_Plateforme
								)>0
								OR
								(
									SELECT COUNT(Id_Prestation)
									FROM new_competences_personne_poste_prestation
									WHERE Id_Personne=".$_SESSION['Id_Personne']."
									AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",8)
									AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_poste_prestation.Id_Prestation) =new_competences_personne_plateforme.Id_Plateforme
								)>0
								OR Id_Qualification_Parrainage IN (
									SELECT Id_Qualification_Parrainage 
									FROM new_competences_relation 
									WHERE new_competences_relation.Suppr=0
									AND Evaluation='X'
									AND new_competences_relation.Date_Debut<='".date('Y-m-d')."'
									AND (new_competences_relation.Date_Fin>='".date('Y-m-d')."' OR new_competences_relation.Date_Fin<='0001-01-01') 
									AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
									AND Id_Personne=".$_SESSION['Id_Personne']."
								)
							)
							";
						}
					
					$req.="ORDER BY UER";
				
					$resultPlateforme=mysqli_query($bdd,$req);
					$nbPlateforme=mysqli_num_rows($resultPlateforme);
					
					$PlateformeSelect = $_SESSION['FiltreSODAFormationPratique_UER'];
					if($_POST)
					{
						$PlateformeSelect=$_POST['plateforme'];
					}
					$_SESSION['FiltreSODAFormationPratique_UER']=$PlateformeSelect;

					$Selected = "";
					echo "<option name='0' value='0' Selected></option>";
					if ($nbPlateforme > 0)
					{
						while($row=mysqli_fetch_array($resultPlateforme))
						{
							$selected="";
							if($PlateformeSelect<>"0")
								{if($PlateformeSelect==$row['Id_Plateforme']){$selected="selected";}}
							echo "<option value='".$row['Id_Plateforme']."' ".$selected.">".stripslashes($row['UER'])."</option>\n";
						}
					 }
					 ?>
					</select>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Qualification : ";}else{echo "Qualification : ";}?></td>
				<td colspan="3" >
					<select name="qualification" style="width:300px;" onchange="submit();">
					<?php
					$req="SELECT DISTINCT 
						Id_Qualification_Parrainage,
						(SELECT Libelle FROM new_competences_qualification WHERE Id=Id_Qualification_Parrainage) AS Qualif
						FROM new_competences_relation 
						LEFT JOIN new_rh_etatcivil 
						ON new_competences_relation.Id_Personne=new_rh_etatcivil.Id 
						WHERE new_competences_relation.Suppr=0
						AND (Evaluation='L'
						OR 
						(Evaluation='X'
						AND Date_Debut<='".date('Y-m-d')."'
						AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01'))
						)
						AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777) ";
						
						if($nbAccess>0 || $nbSuperAdmin>0){
							
						}
						else{
							$req.="AND (
								(
									SELECT COUNT(Id_Plateforme)
									FROM new_competences_personne_poste_plateforme 
									WHERE Id_Personne=".$_SESSION['Id_Personne']."
									AND Id_Poste IN (".$IdPosteReferentQualiteSysteme.",".$IdPosteResponsableQualite.")
									AND new_competences_personne_poste_plateforme.Id_Plateforme IN 
										(SELECT new_competences_personne_plateforme.Id_Plateforme 
										FROM new_competences_personne_plateforme 
										WHERE new_competences_personne_plateforme.Id_Personne=new_rh_etatcivil.Id)
								)>0
								OR
								(
									SELECT COUNT(Id_Prestation)
									FROM new_competences_personne_poste_prestation
									WHERE Id_Personne=".$_SESSION['Id_Personne']."
									AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",8)
									AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_poste_prestation.Id_Prestation) IN 
										(SELECT new_competences_personne_plateforme.Id_Plateforme 
										FROM new_competences_personne_plateforme 
										WHERE new_competences_personne_plateforme.Id_Personne=new_rh_etatcivil.Id)
								)>0
								OR Id_Qualification_Parrainage IN (
									SELECT Id_Qualification_Parrainage 
									FROM new_competences_relation 
									WHERE Suppr=0
									AND Evaluation='X'
									AND Date_Debut<='".date('Y-m-d')."'
									AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
									AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
									AND Id_Personne=".$_SESSION['Id_Personne']."
								)
							)
							";
						}
					
					$req.="ORDER BY Qualif";
					$resultQuestionnaire=mysqli_query($bdd,$req);
					$nbQuestionnaire=mysqli_num_rows($resultQuestionnaire);
					
					$QualificationSelect = $_SESSION['FiltreSODAFormationPratique_Qualification'];
					if($_POST){$QualificationSelect=$_POST['qualification'];}
					$_SESSION['FiltreSODAFormationPratique_Qualification']=$QualificationSelect;
					
					$Selected = "";
					echo "<option name='0' value='0' Selected></option>";
					if ($nbQuestionnaire > 0)
					{
						while($row=mysqli_fetch_array($resultQuestionnaire))
						{
							$selected="";
							if($QualificationSelect==$row['Id_Qualification_Parrainage']){$selected="selected";}
							echo "<option value='".$row['Id_Qualification_Parrainage']."' ".$selected.">".stripslashes($row['Qualif'])."</option>\n";
						}
					 }
					 ?>
					</select>
				</td>
				<td width="15%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Personne : ";}else{echo "Person : ";}?></td>
				<td width="20%">
					<select class="personne" name="personne" style="width:150px;" onchange="submit();">
						<?php
						$req="SELECT DISTINCT 
							Id_Personne,
							CONCAT(Nom,' ',Prenom) AS Personne
							FROM new_competences_relation 
							LEFT JOIN new_rh_etatcivil 
							ON new_competences_relation.Id_Personne=new_rh_etatcivil.Id 
							WHERE new_competences_relation.Suppr=0
							AND (Evaluation='L'
							OR 
							(Evaluation='X'
							AND Date_Debut<='".date('Y-m-d')."'
							AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01'))
							)
							AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777) ";
							
							if($nbAccess>0 || $nbSuperAdmin>0){
								
							}
							else{
								$req.="AND (
									(
										SELECT COUNT(Id_Plateforme)
										FROM new_competences_personne_poste_plateforme 
										WHERE Id_Personne=".$_SESSION['Id_Personne']."
										AND Id_Poste IN (".$IdPosteReferentQualiteSysteme.",".$IdPosteResponsableQualite.")
										AND new_competences_personne_poste_plateforme.Id_Plateforme IN 
											(SELECT new_competences_personne_plateforme.Id_Plateforme 
											FROM new_competences_personne_plateforme 
											WHERE new_competences_personne_plateforme.Id_Personne=new_rh_etatcivil.Id)
									)>0
									OR
									(
										SELECT COUNT(Id_Prestation)
										FROM new_competences_personne_poste_prestation
										WHERE Id_Personne=".$_SESSION['Id_Personne']."
										AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",8)
										AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_poste_prestation.Id_Prestation) IN 
											(SELECT new_competences_personne_plateforme.Id_Plateforme 
											FROM new_competences_personne_plateforme 
											WHERE new_competences_personne_plateforme.Id_Personne=new_rh_etatcivil.Id)
									)>0
									OR Id_Qualification_Parrainage IN (
										SELECT Id_Qualification_Parrainage 
										FROM new_competences_relation 
										WHERE Suppr=0
										AND Evaluation='X'
										AND Date_Debut<='".date('Y-m-d')."'
										AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
										AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
										AND Id_Personne=".$_SESSION['Id_Personne']."
									)
								)
								";
							}
						
						$req.="ORDER BY Personne";
						$resultSurveillant=mysqli_query($bdd,$req);
						$nbSurveillant=mysqli_num_rows($resultSurveillant);
						
						$SurveillantSelect = $_SESSION['FiltreSODAFormationPratique_Personne'];
						if($_POST){$SurveillantSelect=$_POST['personne'];}
						 $_SESSION['FiltreSODAFormationPratique_Personne']=$SurveillantSelect;
						 

						echo "<option value='0' ".$selected." ></option>";
						if ($nbSurveillant > 0)
						{
							while($row=mysqli_fetch_array($resultSurveillant))
							{
								$selected="";
								if($SurveillantSelect==$row['Id_Personne']){$selected="selected";}
								echo "<option value='".$row['Id_Personne']."' ".$selected.">".$row['Personne']."</option>\n";
							}
						 }
						
						 ?>
					</select>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr><td colspan="8">
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:100%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="10%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Entity";}else{echo "Entité";} ?></td>
				<td class="EnTeteTableauCompetences" width="13%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Person with theoretical training";}else{echo "Personne avec formation théorique";} ?></td>
				<td class="EnTeteTableauCompetences" width="30%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Qualification";}else{echo "Qualification";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-align:center;" width="6%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Evaluation";}else{echo "Evaluation";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-align:center;" width="20%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Surveys carried out";}else{echo "Surveillances réalisées";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-align:center;" width="6%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Validate qualification";}else{echo "Valider qualification";} ?></td>
			</tr>
			<?php
				$req="SELECT Id_Personne 
					FROM new_competences_relation 
					WHERE Evaluation='X'
					AND Suppr=0
					AND Date_Debut<='".date('Y-m-d')."'
					AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
					AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
					AND Id_Personne=".$_SESSION['Id_Personne']." ";
				$resultSurQualifieV2=mysqli_query($bdd,$req);
				$nbSurveillantQualifieV2=mysqli_num_rows($resultSurQualifieV2);

				$req="SELECT DISTINCT 
					new_rh_etatcivil.Id,
					CONCAT(Nom,' ',Prenom) AS Personne,
					new_rh_etatcivil.Prenom,
					(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_personne_plateforme WHERE new_competences_personne_plateforme.Id_Personne=new_rh_etatcivil.Id LIMIT 1) AS UER
					FROM new_competences_relation 
					LEFT JOIN new_rh_etatcivil 
					ON new_competences_relation.Id_Personne=new_rh_etatcivil.Id 
					WHERE new_competences_relation.Suppr=0
					AND Evaluation='X'
					AND Date_Debut<='".date('Y-m-d')."'
					AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
					AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id=3777) ";
				if($nbAccess>0 || $nbSuperAdmin>0){
					
				}
				else{
					$req.="AND (
						(
							SELECT COUNT(Id_Plateforme)
							FROM new_competences_personne_poste_plateforme 
							WHERE Id_Personne=".$_SESSION['Id_Personne']."
							AND Id_Poste IN (".$IdPosteReferentQualiteSysteme.",".$IdPosteResponsableQualite.")
							AND new_competences_personne_poste_plateforme.Id_Plateforme IN 
								(SELECT new_competences_personne_plateforme.Id_Plateforme 
								FROM new_competences_personne_plateforme 
								WHERE new_competences_personne_plateforme.Id_Personne=new_rh_etatcivil.Id)
						)>0
						OR
						(
							SELECT COUNT(Id_Prestation)
							FROM new_competences_personne_poste_prestation
							WHERE Id_Personne=".$_SESSION['Id_Personne']."
							AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",8)
							AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_poste_prestation.Id_Prestation) IN 
								(SELECT new_competences_personne_plateforme.Id_Plateforme 
								FROM new_competences_personne_plateforme 
								WHERE new_competences_personne_plateforme.Id_Personne=new_rh_etatcivil.Id)
						)>0
						OR 
						(
							SELECT COUNT(TAB_R.Id_Qualification_Parrainage)
							FROM new_competences_relation AS TAB_R
							WHERE TAB_R.Suppr=0
							AND (TAB_R.Evaluation='L'
							OR 
							(TAB_R.Evaluation='X'
							AND TAB_R.Date_Debut<='".date('Y-m-d')."'
							AND (TAB_R.Date_Fin>='".date('Y-m-d')."' OR TAB_R.Date_Fin<='0001-01-01'))
							)
							AND TAB_R.Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
							AND TAB_R.Id_Personne=new_competences_relation.Id_Personne
							AND TAB_R.Id_Qualification_Parrainage IN 
							(
								SELECT TAB_R2.Id_Qualification_Parrainage 
								FROM new_competences_relation AS TAB_R2 
								WHERE TAB_R2.Suppr=0
								AND TAB_R2.Evaluation='X'
								AND TAB_R2.Date_Debut<='".date('Y-m-d')."'
								AND (TAB_R2.Date_Fin>='".date('Y-m-d')."' OR TAB_R2.Date_Fin<='0001-01-01') 
								AND TAB_R2.Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
								AND TAB_R2.Id_Personne=".$_SESSION['Id_Personne']."
							)
						)>0 
					)
					";
				}
				if($_SESSION['FiltreSODAFormationPratique_UER']<>"0"){
					$req.="AND (
							SELECT COUNT(Id_Plateforme)
							FROM new_competences_personne_plateforme 
							WHERE Id_Personne=new_rh_etatcivil.Id
							AND Id_Plateforme=".$_SESSION['FiltreSODAFormationPratique_UER']."
						)>0 ";
						
				}
				if($_SESSION['FiltreSODAFormationPratique_Qualification']<>"0"){
					$req.="AND (
							SELECT COUNT(TAB_R.Id_Qualification_Parrainage)
							FROM new_competences_relation AS TAB_R
							WHERE TAB_R.Suppr=0
							AND (TAB_R.Evaluation='L'
							OR 
							(TAB_R.Evaluation 'X'
							AND TAB_R.Date_Debut<='".date('Y-m-d')."'
							AND (TAB_R.Date_Fin>='".date('Y-m-d')."' OR TAB_R.Date_Fin<='0001-01-01'))
							)
							AND TAB_R.Id_Qualification_Parrainage =".$_SESSION['FiltreSODAFormationPratique_Qualification']."
							AND TAB_R.Id_Personne=new_competences_relation.Id_Personne
						)>0 ";
				}
				if($_SESSION['FiltreSODAFormationPratique_Personne']<>"0"){
					$req.="AND new_rh_etatcivil.Id=".$_SESSION['FiltreSODAFormationPratique_Personne']." ";
						
				}
				$req.=" ORDER BY UER,Personne;";

				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
							
							$req="SELECT Id_Personne 
								FROM new_competences_relation 
								WHERE Evaluation='X'
								AND Suppr=0
								AND Date_Debut<='".date('Y-m-d')."'
								AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
								AND Id_Qualification_Parrainage=3777
								AND Id_Personne=".$row['Id']." ";
							$resultSurQualifieTheorie=mysqli_query($bdd,$req);
							$nbSurveillantQualifieTheorie=mysqli_num_rows($resultSurQualifieTheorie);

							$req="SELECT new_competences_relation.Id, new_competences_qualification.Libelle,
								new_competences_qualification.Id AS Id_Qualification,
								new_competences_relation.Evaluation								
								FROM new_competences_relation 
								LEFT JOIN new_competences_qualification
								ON new_competences_relation.Id_Qualification_Parrainage = new_competences_qualification.Id
								WHERE new_competences_relation.Suppr=0
								AND (Evaluation='L'
								OR 
								(Evaluation='X'
								AND Date_Debut<='".date('Y-m-d')."'
								AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01'))
								)
								AND Id_Categorie_Qualification=151 
								AND new_competences_qualification.Id<>3777
								AND Id_Personne=".$row['Id']." 
								";
							if($nbAccess>0 || $nbSuperAdmin>0){
								
							}
							else{
								$req.="AND (
									(
										SELECT COUNT(Id_Plateforme)
										FROM new_competences_personne_poste_plateforme 
										WHERE Id_Personne=".$_SESSION['Id_Personne']."
										AND Id_Poste IN (".$IdPosteReferentQualiteSysteme.",".$IdPosteResponsableQualite.")
										AND new_competences_personne_poste_plateforme.Id_Plateforme IN 
											(SELECT new_competences_personne_plateforme.Id_Plateforme 
											FROM new_competences_personne_plateforme 
											WHERE new_competences_personne_plateforme.Id_Personne=new_competences_relation.Id_Personne)
									)
									OR
									(
										SELECT COUNT(Id_Prestation)
										FROM new_competences_personne_poste_prestation
										WHERE Id_Personne=".$_SESSION['Id_Personne']."
										AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.",8)
										AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_poste_prestation.Id_Prestation) IN 
											(SELECT new_competences_personne_plateforme.Id_Plateforme 
											FROM new_competences_personne_plateforme 
											WHERE new_competences_personne_plateforme.Id_Personne=new_competences_relation.Id_Personne)
									)
									OR Id_Qualification_Parrainage IN (
										SELECT Id_Qualification_Parrainage 
										FROM new_competences_relation 
										WHERE Suppr=0
										AND Evaluation='X'
										AND Date_Debut<='".date('Y-m-d')."'
										AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
										AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
										AND Id_Personne=".$_SESSION['Id_Personne']."
									)
								)
								";
							}
							if($_SESSION['FiltreSODAFormationPratique_Qualification']<>"0"){
								$req.="AND new_competences_qualification.Id =".$_SESSION['FiltreSODAFormationPratique_Qualification']." ";
							}
							$req.=" ORDER BY Libelle";
							$resultSurQualifie=mysqli_query($bdd,$req);
							$nbSurveillantQualifie=mysqli_num_rows($resultSurQualifie);
							
							$nb=$nbSurveillantQualifie+2;
						?>
						<tr bgcolor="<?php echo $couleur;?>">
							<td <?php if($nb>1){echo "rowspan='".$nb."'";}?>>&nbsp;<?php echo $row['UER'];?></td>
							<td <?php if($nb>1){echo "rowspan='".$nb."'";}?>><?php echo $row['Personne'];?></td>
						</tr>
						<?php 
						if($nbSurveillantQualifie>0){
							while($rowSurQualifie=mysqli_fetch_array($resultSurQualifie)){
								$surveillances="";
								$autonome=0;
								$req="	SELECT Id,
										AttestationSurveillance,
										Autonome,
										(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_AttestationSurveillance) PersonneAttestationSurveillance,
										DateAttestation
										FROM soda_surveillance 
										WHERE Suppr=0 
										AND AutoSurveillance=0 
										AND Etat='Clôturé'
										AND EnFormation=1
										AND Id_Surveillant=".$row['Id']."
										AND (SELECT COUNT(Id_Theme) 
											FROM soda_questionnaire 
											WHERE Id=Id_Questionnaire
											AND (SELECT Id_Qualification FROM soda_theme WHERE Id=Id_Theme)=".$rowSurQualifie['Id_Qualification']."
											)>0
									";
								$resultSurveillance=mysqli_query($bdd,$req);
								$nbSurveillance=mysqli_num_rows($resultSurveillance);
								if($nbSurveillance>0){
									while($rowSurveillance=mysqli_fetch_array($resultSurveillance))
									{
										if($surveillances<>""){$surveillances.="<br>";}
										$surveillances.='<a style="color:#3e65fa;" href="javascript:OuvreFenetreModif(\'Modif_Surveillance.php\',\'V\','.$rowSurveillance['Id'].');">';
										$surveillances.=$rowSurveillance['Id'];
										if($rowSurveillance['AttestationSurveillance']==0){
											$surveillances.=" [A valider]";
										}
										else{
											$surveillances.=" [validé le ".AfficheDateJJ_MM_AAAA($rowSurveillance['DateAttestation'])." par ".$rowSurveillance['PersonneAttestationSurveillance']."]";
										}
										$surveillances.= '</a>';
										
										if($rowSurveillance['Autonome']==1){$autonome=1;}
									}
								}
								
						?>
								<tr bgcolor="<?php echo $couleur;?>">
									<td style="border-bottom:2px dotted black;"><?php echo $rowSurQualifie['Libelle']; ?></td>
									<td style="border-bottom:2px dotted black;" align="center"><?php echo $rowSurQualifie['Evaluation']; ?></td>
									<td style="border-bottom:2px dotted black;" align="center"><?php echo $surveillances;?></td>
									<td style="border-bottom:2px dotted black;" align="center" height='25px'>
									<?php 
										if($autonome==1 && $rowSurQualifie['Evaluation']<>'X' && $row['Id']<>$_SESSION['Id_Personne']
										&& ($nbAccess>0 || $nbSuperAdmin>0
										|| DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))
										|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,8))))
										{
									?>
										<a href="javascript:ValiderQualifSurv('<?php echo $rowSurQualifie['Id'];?>');"><img src='../../Images/Valider.png' border='0' alt='Valider' width='15'></a>
									<?php
										}
									?>
									</td>
								</tr>
						<?php 
							}
						}
						?>
						<tr bgcolor="<?php echo $couleur;?>">
							<td height='25px'>
							<?php 
								if($row['Id']<>$_SESSION['Id_Personne'] && $nbSurveillantQualifieTheorie>0 && $nbSurveillantQualifieV2>0){
							?>
								<a href="javascript:AjouterQualifSurv('<?php echo $row['Id'];?>');"><img src='../../Images/Plus2.png' border='0' alt='Add' width='14'></a>
							</td>
							<?php 
								}
							?>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<?php
						if($couleur=="#ffffff"){$couleur="#a3e4ff";}
						else{$couleur="#ffffff";}
					}
				}
			?>
		</table>
	</td></tr>
</table>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
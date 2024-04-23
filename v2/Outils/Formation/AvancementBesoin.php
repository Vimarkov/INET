<?php
if($_POST){
	$Id_TypeContrat="";
	$Interim=0;
	$Salarie=0;
	$NC=0;
	if(isset($_POST['Id_TypeContrat'])){
		if (is_array($_POST['Id_TypeContrat'])) {
			foreach($_POST['Id_TypeContrat'] as $value){
				if($Id_TypeContrat<>''){$Id_TypeContrat.=",";}
			  $Id_TypeContrat.=$value;
			  if($value==0){$Interim=1;}
				if($value==1){$Salarie=1;}
				if($value=="NULL"){$NC=1;}
			}
		} else {
			$value = $_POST['Id_TypeContrat'];
			$Id_TypeContrat = $value;
			if($value==0){$Interim=1;}
			if($value==1){$Salarie=1;}
			if($value=="NULL"){$NC=1;}
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
		
	$Categorie="";
	if(isset($_POST['Categorie'])){
		if (is_array($_POST['Categorie'])) {
			foreach($_POST['Categorie'] as $value){
				if($Categorie<>''){$Categorie.=",";}
			  $Categorie.="\"".$value."\"";
			}
		} else {
			$value = $_POST['Categorie'];
			$Categorie = "\"".$value."\"";
		}
	}
	
	$_SESSION['FiltreAvancementBesoin_Plateforme']=$_POST['Id_Plateforme'];
	$_SESSION['FiltreAvancementBesoin_Date']=$_POST['DateDebut'];
	$_SESSION['FiltreAvancementBesoin_Type']=$Id_Type;
	$_SESSION['FiltreAvancementBesoin_Formation']=$_POST['formation'];
	$_SESSION['FiltreAvancementBesoin_Categorie']=$Categorie;
	$_SESSION['FiltreAvancementBesoin_TypeContrat']=$Id_TypeContrat;
} 
?>
<table style="width:100%; border-spacing:0; align:center;">
<tr><td height="4"></td>
<tr>
	<td align="center" valign="top" width="75%">
		<table class="GeneralInfo" width="99%">
			<?php if($_POST){ 
				if($_POST['DateDebut']<>""){
					$dateDebut=TrsfDate_($_POST['DateDebut']);

					$req="
						SELECT 
							TAB.Libelle,
							TAB.Organisme,
							TAB.TypeFormation,
							TAB.Id_Formation,
							TAB.Recyclage,
							TAB.NombreFormation,
							TAB.NbTraite,
							ROUND((TAB.NbTraite/TAB.NombreFormation)*100) AS Avancement
							
						FROM 
						(
							SELECT TAB_BESOINSDEBUT.Libelle,
							TAB_BESOINSDEBUT.Organisme,
							TAB_BESOINSDEBUT.TypeFormation,
							TAB_BESOINSDEBUT.Id_Formation,
							TAB_BESOINSDEBUT.Recyclage,
							TAB_BESOINSDEBUT.NombreFormation,
							(SELECT COUNT(form_besoin.Id)
						FROM
							form_besoin,
							form_formation
						WHERE
							form_besoin.Id_Formation=form_formation.Id
							AND form_besoin.Id_Formation=TAB_BESOINSDEBUT.Id_Formation
							AND IF(form_besoin.Motif='Renouvellement' AND form_formation.Recyclage=1,1,0)=TAB_BESOINSDEBUT.Recyclage
							AND form_besoin.Suppr=0
							AND form_besoin.Valide=1
							AND form_besoin.Date_Demande<='".$dateDebut."'
							AND (Traite>0 
								AND (
									SELECT COUNT(form_session_date.Id)
									FROM form_session_date,
									form_session_personne
									WHERE form_session_personne.Suppr=0
									AND form_session_personne.Id_Besoin=form_besoin.Id 
									AND form_session_personne.Id_Session=form_session_date.Id_Session
									AND form_session_personne.Validation_Inscription<>-1
									AND form_session_date.DateSession>='".$dateDebut."'
								)>0
								AND
								(
									SELECT COUNT(new_competences_relation.Id)
									FROM new_competences_relation
									WHERE new_competences_relation.Suppr=0
									AND new_competences_relation.Id_Besoin=form_besoin.Id 
									AND new_competences_relation.Date_QCM>='".$dateDebut."'
									AND new_competences_relation.Evaluation NOT IN ('B','')
								)>0
							) ";
						if($_SESSION['FiltreAvancementBesoin_TypeContrat']<>""){
							$req.=" AND IF((SELECT (SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
									FROM rh_personne_contrat
									WHERE rh_personne_contrat.Suppr=0
									AND rh_personne_contrat.DateDebut<=form_besoin.Date_Demande
									AND (DateFin>=form_besoin.Date_Demande OR DateFin<='0001-01-01' )
									AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
									AND rh_personne_contrat.Id_Personne=form_besoin.Id_Personne
									ORDER BY DateDebut DESC, Id DESC LIMIT 1) IS NULL,'NULL',(SELECT (SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
									FROM rh_personne_contrat
									WHERE rh_personne_contrat.Suppr=0
									AND rh_personne_contrat.DateDebut<=form_besoin.Date_Demande
									AND (DateFin>=form_besoin.Date_Demande OR DateFin<='0001-01-01' )
									AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
									AND rh_personne_contrat.Id_Personne=form_besoin.Id_Personne
									ORDER BY DateDebut DESC, Id DESC LIMIT 1)) IN (".$_SESSION['FiltreAvancementBesoin_TypeContrat'].") ";
						}
							$req.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation) IN (".$_SESSION['FiltreAvancementBesoin_Plateforme'].")) AS NbTraite
							FROM
							(SELECT 
							form_typeformation.Libelle AS TypeFormation,
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
							new_competences_prestation
						WHERE
							form_besoin.Id_Formation=form_formation.Id
							AND form_formation.Id_TypeFormation=form_typeformation.Id
							AND form_besoin.Id_Prestation=new_competences_prestation.Id
							AND form_besoin.Suppr=0
							AND form_besoin.Valide=1
							AND form_besoin.Date_Demande<='".$dateDebut."'
							AND (form_besoin.Traite=0
							OR (
								Traite>0 
								AND (
									SELECT COUNT(form_session_date.Id)
									FROM form_session_date,
									form_session_personne
									WHERE form_session_personne.Suppr=0
									AND form_session_personne.Id_Besoin=form_besoin.Id 
									AND form_session_personne.Id_Session=form_session_date.Id_Session
									AND form_session_personne.Validation_Inscription<>-1
									AND form_session_date.DateSession>='".$dateDebut."' 
								)>0
								AND
								(
									SELECT COUNT(new_competences_relation.Id)
									FROM new_competences_relation
									WHERE new_competences_relation.Suppr=0
									AND new_competences_relation.Id_Besoin=form_besoin.Id 
									AND new_competences_relation.Date_QCM>='".$dateDebut."'
									AND new_competences_relation.Evaluation NOT IN ('B','') 
								)>0
								
								)
							)
							AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation) IN (".$_SESSION['FiltreAvancementBesoin_Plateforme'].")
							 ";
						if($_SESSION['FiltreAvancementBesoin_Type']<>""){
							$req.=" AND form_formation.Id_TypeFormation IN (".$_SESSION['FiltreAvancementBesoin_Type'].") ";
						}

						if($_SESSION['FiltreAvancementBesoin_Categorie']<>""){
							$req.=" AND form_formation.Categorie IN (".$_SESSION['FiltreAvancementBesoin_Categorie'].") ";
						}
						if($_SESSION['FiltreAvancementBesoin_Formation']<>"" && $_SESSION['FiltreAvancementBesoin_Formation']<>"0_0"){
							$tabQual=explode("_",$_SESSION['FiltreAvancementBesoin_Formation']);
							if($tabQual[1]==0){
								$req.=" AND form_besoin.Id_Formation=".$tabQual[0]." ";
							}
							else{
								$req.=" AND form_besoin.Id_Formation IN 
									(SELECT Id_Formation 
									FROM form_formationequivalente_formationplateforme 
									WHERE Id_FormationEquivalente=".$tabQual[0].") ";
							}
						}
						if($_SESSION['FiltreAvancementBesoin_TypeContrat']<>""){
							$req.=" AND IF((SELECT (SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
									FROM rh_personne_contrat
									WHERE rh_personne_contrat.Suppr=0
									AND rh_personne_contrat.DateDebut<=form_besoin.Date_Demande
									AND (DateFin>=form_besoin.Date_Demande OR DateFin<='0001-01-01' )
									AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
									AND rh_personne_contrat.Id_Personne=form_besoin.Id_Personne
									ORDER BY DateDebut DESC, Id DESC LIMIT 1) IS NULL,'NULL',(SELECT (SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
									FROM rh_personne_contrat
									WHERE rh_personne_contrat.Suppr=0
									AND rh_personne_contrat.DateDebut<=form_besoin.Date_Demande
									AND (DateFin>=form_besoin.Date_Demande OR DateFin<='0001-01-01' )
									AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
									AND rh_personne_contrat.Id_Personne=form_besoin.Id_Personne
									ORDER BY DateDebut DESC, Id DESC LIMIT 1)) IN (".$_SESSION['FiltreAvancementBesoin_TypeContrat'].") ";
						}						
						$req.="GROUP BY Libelle, Organisme)
							AS TAB_BESOINSDEBUT) AS TAB
						ORDER BY Avancement DESC ";
					
					$result=mysqli_query($bdd,$req);
					$nbenreg=mysqli_num_rows($result);
					
					?>
					<tr >
						<td class="EnTeteTableauCompetences" width="60%"><?php if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";}?></td>
						<td class="EnTeteTableauCompetences" width="10%"><?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($LangueAffichage=="FR"){echo "Nombre de besoins au ".AfficheDateJJ_MM_AAAA($dateDebut);}else{echo "Number of requirements as of ".AfficheDateJJ_MM_AAAA($dateDebut);}?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($LangueAffichage=="FR"){echo "Nombre de besoins traités";}else{echo "Number of needs processed";}?></td>
						<td class="EnTeteTableauCompetences" width="5%"><?php if($LangueAffichage=="FR"){echo "% avancement";}else{echo "% advancement";}?></td>
						<td class="EnTeteTableauCompetences" width="3%">
						&nbsp;<a style="text-decoration:none;" href="javascript:Excel_AvancementBesoin();">
							<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
						</a>&nbsp;
						</td>
					</tr>
				<?php
				
					if($nbenreg>0){
						$couleur="#d6d9dc";
						while($row=mysqli_fetch_array($result)){
							$organisme="";
							if($row['Organisme']<>""){
								$organisme=" ".$row['Organisme'];
							}
							$abscisse=utf8_encode($row['Libelle'].$organisme);
							$Avancement=$row['Avancement'];

							?>
								<tr bgcolor="<?php echo $couleur; ?>">
									<td ><?php echo $row['Libelle'].$organisme ; ?></td>
									<td ><?php echo $row['TypeFormation'] ; ?></td>
									<td ><?php echo $row['NombreFormation'] ; ?></td>
									<td ><?php echo $row['NbTraite'] ; ?></td>
									<td colspan=""><?php echo $row['Avancement']."%" ; ?></td>
								</tr>
							<?php 
							if($couleur=="#d6d9dc"){$couleur="#ffffff";}
							else{$couleur="#d6d9dc";}
						}
					}
					

				?>
					
				<?php 
				}
			}
			?>
		</table>
	</td>
	<td align="right" valign="top" width="25%">
		<table class="GeneralInfo" style="border-spacing:0; width:70%; align:center;box-shadow: 0 8px 10px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Date ";}else{echo "Date";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateDebut" name="DateDebut" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltreAvancementBesoin_Date']); ?>"></td>
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
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Type de contrat";}else{echo "Type of Contract";}?> : </td>
			</tr>
			<tr>
				<td>
					<table width='100%'>
						<?php
							$checked="";
							if($_POST){
								$checkboxes = isset($_POST['Id_TypeContrat']) ? $_POST['Id_TypeContrat'] : array();
								foreach($checkboxes as $value) {
									if($value=="'0'"){$checked="checked";}
								}
							}
							else{
								$checked="checked";	
							}
							echo "<tr><td>";
							echo "<input type='checkbox' class='checkTypeContrat' name='Id_TypeContrat[]' Id='Id_TypeContrat[]' value=\"'0'\" ".$checked.">";
							if($LangueAffichage=="FR"){echo "Intérim";}else{echo "Interim";}
							echo "</td></tr>";
							
							$checked="";
							if($_POST){
								$checkboxes = isset($_POST['Id_TypeContrat']) ? $_POST['Id_TypeContrat'] : array();
								foreach($checkboxes as $value) {
									if("'1'"==$value){$checked="checked";}
								}
							}
							else{
								$checked="checked";	
							}
							
							echo "<tr><td>";
							echo "<input type='checkbox' class='checkTypeContrat' name='Id_TypeContrat[]' Id='Id_TypeContrat[]' value=\"'1'\" ".$checked.">";
							if($LangueAffichage=="FR"){echo "Salarié";}else{echo "Employee";}
							echo "</td></tr>";
							
							$checked="";
							if($_POST){
								$checkboxes = isset($_POST['Id_TypeContrat']) ? $_POST['Id_TypeContrat'] : array();
								foreach($checkboxes as $value) {
									if("'NULL'"==$value){$checked="checked";}
								}
							}
							else{
								$checked="checked";	
							}
							
							echo "<tr><td>";
							echo "<input type='checkbox' class='checkTypeContrat' name='Id_TypeContrat[]' Id='Id_TypeContrat[]' value=\"'NULL'\" ".$checked.">";
							if($LangueAffichage=="FR"){echo "NC";}else{echo "NC";}
							echo "</td></tr>";
						?>
					</table>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Catégorie";}else{echo "Category";}?> : </td>
			</tr>
			<tr>
				<td class="Libelle" width="10%"><input type="checkbox" name="selectAllCategorie" id="selectAllCategorie" onclick="SelectionnerToutCategorie()" /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?></td>
			</tr>
			
			<tr>
				<td>
					<div id='Div_Categorie' style="height:100px;overflow:auto;">
						<table width='100%'>
							<?php
								$rqCategorie="SELECT DISTINCT Categorie
								FROM form_formation_plateforme_parametres
								LEFT JOIN form_formation 
								ON form_formation_plateforme_parametres.Id_Formation=form_formation.Id
								WHERE form_formation_plateforme_parametres.Id_Plateforme=".$Id_Plateforme."
								AND form_formation_plateforme_parametres.Suppr=0
								AND form_formation.Suppr=0
								ORDER BY Categorie";
								
								$resultCategorie=mysqli_query($bdd,$rqCategorie);
								$Categorie=0;
								while($rowCategorie=mysqli_fetch_array($resultCategorie))
								{
									$checked="";
									if($_POST){
										$checkboxes = isset($_POST['Categorie']) ? $_POST['Categorie'] : array();
										foreach($checkboxes as $value) {
											if($rowCategorie['Categorie']==$value){$checked="checked";}
										}
									}
									else{
										$checked="checked";	
									}
									
									echo "<tr><td>";
									echo "<input type='checkbox' class='checkCategorie' name='Categorie[]' Id='Categorie[]' value=\"".$rowCategorie['Categorie']."\" ".$checked.">".$rowCategorie['Categorie'];
									echo "</td></tr>";
								}
							?>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Formation ";}else{echo "Training ";}?></td>
			</tr>
			<tr>
				<td>
					<?php
						
					?>
					<select name="formation" id="formation" style="width:200px">
						<option value="0_0"></option>
						<?php
						$laformation=$_SESSION['FiltreAvancementBesoin_Formation'];

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
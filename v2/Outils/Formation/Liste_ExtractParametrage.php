<?php
require("../../Menu.php");
?>
<script language="javascript">
	function SelectionnerTout()
	{
		var elements = document.getElementsByClassName("checkPresta");
		if (formulaire.selectAll.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
		}
	}
	function Excel_FormationNonParametrees(){
		var w=window.open("Excel_FormationNonParametrees.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_QualifsNonParametrees(){
		var w=window.open("Excel_QualifsNonParametrees.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_MetiersNonParametrees(){
		var w=window.open("Excel_MetiersNonParametrees.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
	function Excel_QualifsParamatreesNonTBC(){
		var w=window.open("Excel_QualifsParamatreesNonTBC.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
</script>
<?php
if($_POST){
	$Id_Presta="";
	if (is_array($_POST['Id_Presta'])) {
		foreach($_POST['Id_Presta'] as $value){
			if($Id_Presta<>''){$Id_Presta.=",";}
		  $Id_Presta.="'".$value."'";
		}
	} else {
		$value = $_POST['Id_Presta'];
		$Id_Presta = "'".$value."'";
	}
	
	$Id_Type="";
	if (is_array($_POST['Id_Type'])) {
		foreach($_POST['Id_Type'] as $value){
			if($Id_Type<>''){$Id_Type.=",";}
		  $Id_Type.=$value;
		}
	} else {
		$value = $_POST['Id_Type'];
		$Id_Type = $value;
	}

	$_SESSION['FiltreExtractParametrage_Prestations']=$Id_Presta;
	$_SESSION['FiltreExtractParametrage_Plateforme']=$_POST['Id_Plateforme'];
	$_SESSION['FiltreExtractParametrage_Type']=$Id_Type;
	$_SESSION['FiltreExtractParametrage_Formation']=$_POST['formationR'];
	
	
	if(isset($_POST['Excel_FormationNonParametrees'])){
		echo "<script>Excel_FormationNonParametrees();</script>";
	}
	elseif(isset($_POST['Excel_QualifsNonParametrees'])){
		echo "<script>Excel_QualifsNonParametrees();</script>";
	}
	elseif(isset($_POST['Excel_MetiersNonParametrees'])){
		echo "<script>Excel_MetiersNonParametrees();</script>";
	}
	elseif(isset($_POST['Excel_QualifsParamatreesNonTBC'])){
		echo "<script>Excel_QualifsParamatreesNonTBC();</script>";
	}
}
?>
<form id="formulaire" action="Liste_ExtractParametrage.php" method="post">
	<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td colspan="2">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#67cff1;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Paramétrage - Extracts";}else{echo "Settings - Extracts)";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td>
	<tr>
		<td align="left" width="40%">
			<table class="GeneralInfo" style="border-spacing:0; width:70%; align:center;box-shadow: 0 8px 10px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
				<tr><td height="4px"></td></tr>
				<tr>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Type de formation";}else{echo "Type of training";}?> : </td>
				</tr>
				<tr>
					<td>
						<div id='Div_Type' style="height:100px;overflow:auto;">
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
						</div>
					</td>
				</tr>
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
				<td class="Libelle" width="8%" colspan="2">&nbsp;<?php if($LangueAffichage=="FR"){echo "Formation ";}else{echo "Training ";}?>&nbsp;

					<select name="formationR" id="formationR" style="width:400px" onchange="submit()">
						<option value="0_0"></option>
						<?php
						$formation=0;
						if($_POST)
						{
							if(isset($_POST['formationR']))
							{
								$formation=$_POST['formationR'];
							}
						}
						
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
							if($formation<>"")
							{
								if($formation==$rowForm['Id_Formation']."_".$rowForm['FormEquivalence']){$selected="selected";}
							}
							echo "<option value='".$rowForm['Id_Formation']."_".$rowForm['FormEquivalence']."' ".$selected.">";
							echo stripslashes($rowForm['Formation']);
							echo "</option>\n";
						}
					?>
					</select>
				</td>
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
				<tr><td height="4px"></td></tr>
			</table>
		</td>
		
	

		<td align="left" width="60%">
			<table class="GeneralInfo" style="border-spacing:0; width:70%; align:center;box-shadow: 0 8px 10px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
				<tr><td height="20px"></td></tr>
				<tr>
					<td><input name="Excel_FormationNonParametrees" style="text-decoration:none;border:none;cursor:pointer;text-decoration: none;text-align: left;color:#0000ee;background-color:#ffffff;" type="submit" value="&#x2794;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "List of unconfigured formations";}else{echo "Liste des formations non paramétrées";}?>&nbsp;" /></td>
				</tr>
				<tr><td height="20px"></td></tr>
				<tr>
					<td><input name="Excel_QualifsNonParametrees" style="text-decoration:none;border:none;cursor:pointer;text-decoration: none;text-align: left;color:#0000ee;background-color:#ffffff;" type="submit" value="&#x2794;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "List of qualifications present in the skills tables but not set";}else{echo "Liste des qualifications présentes aux tableaux des compétences mais non paramétrées";}?>&nbsp;" /></td>
				</tr>
				<tr><td height="20px"></td></tr>
				<tr>
					<td><input name="Excel_QualifsParamatreesNonTBC" style="text-decoration:none;border:none;cursor:pointer;text-decoration: none;text-align: left;color:#0000ee;background-color:#ffffff;" type="submit" value="&#x2794;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "List of qualifications set but not present in the skill tables";}else{echo "Liste des qualifications paramétrées mais non présentes aux tableaux des compétences";}?>&nbsp;" /></td>
				</tr>
				<tr><td height="20px"></td></tr>
				<tr>
					<td><input name="Excel_MetiersNonParametrees" style="text-decoration:none;border:none;cursor:pointer;text-decoration: none;text-align: left;color:#0000ee;background-color:#ffffff;" type="submit" value="&#x2794;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "List of non-parametric trades but present on the site";}else{echo "Liste des métiers non paramétrés mais présents sur la prestation";}?>&nbsp;" /></td>
				</tr>
				<tr><td height="20px"></td></tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td>
	</table>
</form>
</html>
	
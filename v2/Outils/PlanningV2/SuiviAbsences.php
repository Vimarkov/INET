<?php
require("../../Menu.php");
?>
<script>
	function SelectionnerTout(Champ)
	{
		var elements = document.getElementsByClassName("check"+Champ);
		if (document.getElementById('selectAll'+Champ).checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
		}
	}
	function Excel_SuiviAbsences(){
		var w=window.open("Export_SuiviAbsences.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
		w.focus();
	}
</script>
<?php
if($_POST){
	$TypeAbsence="";
	if(isset($_POST['TypeAbsence'])){
		if (is_array($_POST['TypeAbsence'])) {
			foreach($_POST['TypeAbsence'] as $value){
				if($TypeAbsence<>''){$TypeAbsence.=",";}
			  $TypeAbsence.="'".$value."'";
			}
		} else {
			$value = $_POST['TypeAbsence'];
			$TypeAbsence = $value;
		}
	}

	$Id_Prestation="";
	if(isset($_POST['prestation'])){
		if (is_array($_POST['prestation'])) {
			foreach($_POST['prestation'] as $value){
				if($Id_Prestation<>''){$Id_Prestation.=",";}
			  $Id_Prestation.=$value;
			}
		} else {
			$value = $_POST['prestation'];
			$Id_Prestation = $value;
		}
	}
	
	$Id_Personne="";
	if(isset($_POST['personne'])){
		if (is_array($_POST['personne'])) {
			foreach($_POST['personne'] as $value){
				if($Id_Personne<>''){$Id_Personne.=",";}
			  $Id_Personne.=$value;
			}
		} else {
			$value = $_POST['personne'];
			$Id_Personne = $value;
		}
	}
	
	$Id_RespProjet="";
	if(isset($_POST['RespProjet'])){
		if (is_array($_POST['RespProjet'])) {
			foreach($_POST['RespProjet'] as $value){
				if($Id_RespProjet<>''){$Id_RespProjet.=",";}
			  $Id_RespProjet.=$value;
			}
		} else {
			$value = $_POST['RespProjet'];
			$Id_RespProjet = $value;
		}
	}
	
	$Id_Metier="";
	if(isset($_POST['metier'])){
		if (is_array($_POST['metier'])) {
			foreach($_POST['metier'] as $value){
				if($Id_Metier<>''){$Id_Metier.=",";}
			  $Id_Metier.=$value;
			}
		} else {
			$value = $_POST['metier'];
			$Id_Metier = $value;
		}
	}
	
	if($_POST){$dateDebut=$_POST['dateDebut'];}
	if($dateDebut==""){$dateDebut=date('Y-m-1');}
	
	if(isset($_POST['PrisEnCompte'])){$PrisEnCompte="checked";}else{$PrisEnCompte="";}
	if(isset($_POST['NonPrisEnCompte'])){$NonPrisEnCompte="checked";}else{$NonPrisEnCompte="";}
	if(isset($_POST['Supprime'])){$Supprime="checked";}else{$Supprime="";}
	
	if(isset($_POST['Prevue'])){$Prevue="checked";}else{$Prevue="";}
	if(isset($_POST['NonPrevue'])){$NonPrevue="checked";}else{$NonPrevue="";}

	$_SESSION['FiltreRHSuiviAbsence_EtatPrisEnCompte']=$PrisEnCompte;
	$_SESSION['FiltreRHSuiviAbsence_EtatNonPrisEnCompte']=$NonPrisEnCompte;
	$_SESSION['FiltreRHSuiviAbsence_Supprime']=$Supprime;
	$_SESSION['FiltreRHSuiviAbsence_DateDebut']=TrsfDate_($dateDebut);
	$_SESSION['FiltreRHSuiviAbsence_DateFin']=TrsfDate_($_POST['dateFin']);
	$_SESSION['FiltreRHSuiviAbsence_Prevue']=$Prevue;
	$_SESSION['FiltreRHSuiviAbsence_NonPrevue']=$NonPrevue;
	$_SESSION['FiltreRHSuiviAbsence_Prestation']=$Id_Prestation;
	$_SESSION['FiltreRHSuiviAbsence_TypeAbs']=$TypeAbsence;
	$_SESSION['FiltreRHSuiviAbsence_Personne']=$Id_Personne;
	$_SESSION['FiltreRHSuiviAbsence_RespProjet']=$Id_RespProjet;
	$_SESSION['FiltreRHSuiviAbsence_Metier']=$Id_Metier;
}

if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
?>
	
<form action="SuiviAbsences.php" method="post">
	<table width="100%" cellpadding="0" cellspacing="0" align="center">
		<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
		<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
		<tr>
			<td colspan="10">
				<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#f561a4;">
					<tr>
						<td class="TitrePage">
						<?php
						echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
						if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
						else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
						echo "</a>";
						echo "&nbsp;&nbsp;&nbsp;";
							
						if($LangueAffichage=="FR"){echo "Suivi des absences";}else{echo "Absence tracking";}
						?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td align="left" valign="top" width="25%">
				<table class="GeneralInfo" style="border-spacing:0; width:70%; align:left;box-shadow: 0 8px 10px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
					<tr><td height="4px"></td></tr>
					<tr>
						<td class="Libelle" style="background-color:#B2AE9F;" width="10%"><?php if($LangueAffichage=="FR"){echo "Date début";}else{echo "Start date";}?> : </td>
					</tr>
					<tr>
						<td class="Libelle" width="10%"><input id="dateDebut" name="dateDebut" type="date" value="<?php echo AfficheDateFR($_SESSION['FiltreRHSuiviAbsence_DateDebut']); ?>" size="5"/></td>
					</tr>
					<tr>
						<td class="Libelle" style="background-color:#B2AE9F;" width="10%"><?php if($LangueAffichage=="FR"){echo "Date fin";}else{echo "End date";}?> : </td>
					</tr>
					<tr>
						<td class="Libelle" width="10%"><input id="dateFin" name="dateFin" type="date" value="<?php echo AfficheDateFR($_SESSION['FiltreRHSuiviAbsence_DateFin']); ?>" size="5"/></td>
					</tr>
					<tr>
						<td class="Libelle" style="background-color:#B2AE9F;" width="10%"><?php if($LangueAffichage=="FR"){echo "Etat";}else{echo "State";}?> : </td>
					</tr>
					<tr>
						<td>
							<table width='100%'>
								<tr><td><input type="checkbox" id="NonPrisEnCompte" name="NonPrisEnCompte" value="NonPrisEnCompte" <?php echo $_SESSION['FiltreRHSuiviAbsence_EtatNonPrisEnCompte']; ?>><?php if($_SESSION["Langue"]=="FR"){echo "NON PRIS EN COMPTE";}else{echo "NOT TAKEN INTO ACCOUNT";} ?></td></tr>
								<tr><td><input type="checkbox" id="PrisEnCompte" name="PrisEnCompte" value="PrisEnCompte" <?php echo $_SESSION['FiltreRHSuiviAbsence_EtatPrisEnCompte']; ?>><?php if($_SESSION["Langue"]=="FR"){echo "PRIS EN COMPTE";}else{echo "TAKEN INTO ACCOUNT";} ?></td></tr>
								<tr><td><input type="checkbox" id="Supprime" name="Supprime" value="Supprime" <?php echo $_SESSION['FiltreRHSuiviAbsence_Supprime']; ?>><?php if($_SESSION["Langue"]=="FR"){echo "SUPPRIME";}else{echo "DELETED";} ?></td></tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class="Libelle" style="background-color:#B2AE9F;" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Prévenue";}else{echo "Warned";} ?> : </td>
					</tr>
					<tr>
						<td>
							<table width='100%'>
								<tr><td><input type="checkbox" id="Prevue" name="Prevue" value="Prevue" <?php echo $_SESSION['FiltreRHSuiviAbsence_Prevue']; ?>><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?></td></tr>
								<tr><td><input type="checkbox" id="NonPrevue" name="NonPrevue" value="NonPrevue" <?php echo $_SESSION['FiltreRHSuiviAbsence_NonPrevue']; ?>><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?></td></tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Site";}else{echo "Prestation";} ?>&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="selectAllPrestation" id="selectAllPrestation" onclick="SelectionnerTout('Prestation')" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
						</td>
					</tr>
					<tr>
						<td>
							<div id='Div_Prestation' style='height:150px;width:200px;overflow:auto;'>
								<table>
							<?php 
								$req="SELECT Id, LEFT(Libelle,7) AS Libelle
								FROM new_competences_prestation
								WHERE Id_Plateforme IN 
									(
										SELECT Id_Plateforme 
										FROM new_competences_personne_poste_plateforme
										WHERE Id_Personne=".$_SESSION['Id_Personne']." 
										AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
									)
								AND Active=0
								ORDER BY Libelle ASC";
								$resultPrestation=mysqli_query($bdd,$req);
								$nbPrestation=mysqli_num_rows($resultPrestation);
								
								if ($nbPrestation > 0)
								{
									while($row=mysqli_fetch_array($resultPrestation))
									{
										$checked="";
										if($_POST){
											$checkboxes = isset($_POST['prestation']) ? $_POST['prestation'] : array();
											foreach($checkboxes as $value) {
												if($row['Id']==$value){$checked="checked";}
											}
										}
										else{
											$checked="checked";	
										}
										echo "<tr><td><input class='checkPrestation' type='checkbox' ".$checked." value='".$row['Id']."' name='prestation[]'>".$row['Libelle']."</td></tr>";
									}
								}
							?>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Type d'absence :";}else{echo "Type of absence :";} ?>&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="selectAllTypeAbsence" id="selectAllTypeAbsence" onclick="SelectionnerTout('TypeAbsence')" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
						</td>
					</tr>
					<tr>
						<td>
							<div id='Div_TypeAbsence' style='height:150px;width:200px;overflow:auto;'>
								<table>
							<?php 
								$checked="";
								if($_POST){
									$checkboxes = isset($_POST['TypeAbsence']) ? $_POST['TypeAbsence'] : array();
									foreach($checkboxes as $value) {
										if($value==0){$checked="checked";}
									}
								}
								else{
									$checked="checked";	
								}
								if($_SESSION["Langue"]=="FR"){$abs= "ABS : Absences injustifiées";}else{$abs= "ABS : Unjustified absences";}
								echo "<tr><td><input class='checkTypeAbsence' type='checkbox' ".$checked." value='0' name='TypeAbsence[]'>".$abs."</td></tr>";
								
								$req="SELECT Id, CodePlanning, Libelle FROM rh_typeabsence WHERE Suppr=0 ORDER BY CodePlanning ";
								$resultTypeAbsence=mysqli_query($bdd,$req);
								$NbTypeAbsence=mysqli_num_rows($resultTypeAbsence);
								
								if ($NbTypeAbsence > 0)
								{
									while($rowTypeAbsence=mysqli_fetch_array($resultTypeAbsence))
									{
										$checked="";
										if($_POST){
											$checkboxes = isset($_POST['TypeAbsence']) ? $_POST['TypeAbsence'] : array();
											foreach($checkboxes as $value) {
												if($rowTypeAbsence['Id']==$value){$checked="checked";}
											}
										}
										else{
											$checked="checked";	
										}
										
										echo "<tr><td><input class='checkTypeAbsence' type='checkbox' ".$checked." value='".$rowTypeAbsence['Id']."' name='TypeAbsence[]'>".$rowTypeAbsence['CodePlanning']." : ".$rowTypeAbsence['Libelle']."</td></tr>";
									}
								}
							?>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "People";}else{echo "Personne";} ?>&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="selectAllPersonne" id="selectAllPersonne" onclick="SelectionnerTout('Personne')" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
						</td>
					</tr>
					<tr>
						<td>
							<div id='Div_Personne' style='height:150px;width:200px;overflow:auto;'>
								<table>
							<?php 
								$req="SELECT DISTINCT new_rh_etatcivil.Id, 
										CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
										FROM rh_personne_demandeabsence
										LEFT JOIN new_rh_etatcivil
										ON new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Personne
										WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_demandeabsence.Id_Prestation) IN 
										(
											SELECT Id_Plateforme 
											FROM new_competences_personne_poste_plateforme
											WHERE Id_Personne=".$_SESSION['Id_Personne']." 
											AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
										)
										AND new_rh_etatcivil.Id>0
										ORDER BY Personne ASC";
								$resultPersonne=mysqli_query($bdd,$req);
								$nbPersonne=mysqli_num_rows($resultPersonne);

								if ($nbPersonne > 0)
								{
									while($row=mysqli_fetch_array($resultPersonne))
									{
										$checked="";
										if($_POST){
											$checkboxes = isset($_POST['personne']) ? $_POST['personne'] : array();
											foreach($checkboxes as $value) {
												if($row['Id']==$value){$checked="checked";}
											}
										}
										else{
											$checked="checked";	
										}
										echo "<tr><td><input class='checkPersonne' type='checkbox' ".$checked." value='".$row['Id']."' name='personne[]'>".$row['Personne']."</td></tr>";
									}
								}
							?>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Project manager";}else{echo "Responsable Projet";} ?>&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="selectAllRespProjet" id="selectAllRespProjet" onclick="SelectionnerTout('RespProjet')" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
						</td>
					</tr>
					<tr>
						<td>
							<div id='Div_RespProjet' style='height:150px;width:200px;overflow:auto;'>
								<table>
							<?php 
								$req="SELECT DISTINCT Id_Personne AS Id,
								(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
								FROM new_competences_personne_poste_prestation 
								LEFT JOIN new_competences_prestation
								ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
								WHERE Id_Poste IN (".$IdPosteResponsableProjet.")
								AND Id_Plateforme IN (
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
								)
								AND Id_Personne<>0
								ORDER BY Personne";
								$resultResp=mysqli_query($bdd,$req);
								$nbRespProjet=mysqli_num_rows($resultResp);
								
								if ($nbRespProjet > 0)
								{
									while($rowResp=mysqli_fetch_array($resultResp))
									{
										$checked="";
										if($_POST){
											$checkboxes = isset($_POST['RespProjet']) ? $_POST['RespProjet'] : array();
											foreach($checkboxes as $value) {
												if($rowResp['Id']==$value){$checked="checked";}
											}
										}
										else{
											$checked="checked";	
										}
										echo "<tr><td><input class='checkRespProjet' type='checkbox' ".$checked." value='".$rowResp['Id']."' name='RespProjet[]'>".$rowResp['Personne']."</td></tr>";
									}
								}
							?>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Job";}else{echo "Métier";} ?>&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td>
							<input type="checkbox" name="selectAllMetier" id="selectAllMetier" onclick="SelectionnerTout('Metier')" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
						</td>
					</tr>
					<tr>
						<td>
							<div id='Div_Metier' style='height:150px;width:200px;overflow:auto;'>
								<table>
							<?php 
								$req="SELECT DISTINCT (SELECT rh_personne_contrat.Id_Metier
										FROM rh_personne_contrat
										WHERE rh_personne_contrat.Suppr=0
										AND rh_personne_contrat.DateDebut<=rh_personne_demandeabsence.DateCreation
										AND (rh_personne_contrat.DateFin>=rh_personne_demandeabsence.DateCreation OR rh_personne_contrat.DateFin<='0001-01-01')
										AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
										AND rh_personne_contrat.Id_Personne=rh_personne_demandeabsence.Id_Personne
										ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS Id_Metier, 
										(SELECT (SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=rh_personne_contrat.Id_Metier)
										FROM rh_personne_contrat
										WHERE rh_personne_contrat.Suppr=0
										AND rh_personne_contrat.DateDebut<=rh_personne_demandeabsence.DateCreation
										AND (rh_personne_contrat.DateFin>=rh_personne_demandeabsence.DateCreation OR rh_personne_contrat.DateFin<='0001-01-01')
										AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
										AND rh_personne_contrat.Id_Personne=rh_personne_demandeabsence.Id_Personne
										ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS Metier
										FROM rh_personne_demandeabsence
										LEFT JOIN new_rh_etatcivil
										ON new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Personne
										WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_demandeabsence.Id_Prestation) IN 
										(
											SELECT Id_Plateforme 
											FROM new_competences_personne_poste_plateforme
											WHERE Id_Personne=".$_SESSION['Id_Personne']." 
											AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
										)
										AND (SELECT rh_personne_contrat.Id_Metier
										FROM rh_personne_contrat
										WHERE rh_personne_contrat.Suppr=0
										AND rh_personne_contrat.DateDebut<=rh_personne_demandeabsence.DateCreation
										AND (rh_personne_contrat.DateFin>=rh_personne_demandeabsence.DateCreation OR rh_personne_contrat.DateFin<='0001-01-01')
										AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
										AND rh_personne_contrat.Id_Personne=rh_personne_demandeabsence.Id_Personne
										ORDER BY DateDebut DESC, Id DESC LIMIT 1)>0
										ORDER BY (SELECT (SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=rh_personne_contrat.Id_Metier)
										FROM rh_personne_contrat
										WHERE rh_personne_contrat.Suppr=0
										AND rh_personne_contrat.DateDebut<=rh_personne_demandeabsence.DateCreation
										AND (rh_personne_contrat.DateFin>=rh_personne_demandeabsence.DateCreation OR rh_personne_contrat.DateFin<='0001-01-01')
										AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
										AND rh_personne_contrat.Id_Personne=rh_personne_demandeabsence.Id_Personne
										ORDER BY DateDebut DESC, Id DESC LIMIT 1) ASC";
								$resultMetier=mysqli_query($bdd,$req);
								$nbMetier=mysqli_num_rows($resultMetier);

								if ($nbMetier > 0)
								{
									while($row=mysqli_fetch_array($resultMetier))
									{
										$checked="";
										if($_POST){
											$checkboxes = isset($_POST['metier']) ? $_POST['metier'] : array();
											foreach($checkboxes as $value) {
												if($row['Id_Metier']==$value){$checked="checked";}
											}
										}
										else{
											$checked="checked";	
										}
										echo "<tr><td><input class='checkMetier' type='checkbox' ".$checked." value='".$row['Id_Metier']."' name='metier[]'>".$row['Metier']."</td></tr>";
									}
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
			<td align="right" valign="top" width="75%">
				<table class="GeneralInfo" width="50%">
					<tr>
						<td width="3%">
						&#x2794;&nbsp;&nbsp;<a style="text-decoration:none;" href="javascript:Excel_SuiviAbsences();">
							Suivi des absences
						</a>&nbsp;
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
</body>
</html>

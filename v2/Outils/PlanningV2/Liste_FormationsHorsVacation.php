<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModif(Menu,Id,Id_SessionDate)
		{var w=window.open("Modif_FormationsHorsVacation.php?Mode=M&Id="+Id+"&Id_SessionDate="+Id_SessionDate+"&Menu="+Menu+"&TDB="+document.getElementById('TDB').value+"&OngletTDB="+document.getElementById('OngletTDB').value,"PageForm","status=no,menubar=no,width=1000,height=550");
		w.focus();
		}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

$TDB=0;
if($_GET){
	if(isset($_GET['TDB'])){
		$TDB=$_GET['TDB'];
	}
}
else{
	$TDB=$_POST['TDB'];
}
$OngletTDB="";
if($_GET){
	if(isset($_GET['OngletTDB'])){
		$OngletTDB=$_GET['OngletTDB'];
	}
}
else{
	$OngletTDB=$_POST['OngletTDB'];
}
if(isset($_GET['Tri'])){
	$tab = array("Id","Personne","Contrat","PrestationReelle","PoleReel","Prestation","Pole","DateSession","Formation","DatePriseEnCompteRH","Heure_Debut","Heure_Fin");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriRHForm_General']= str_replace($tri." ASC,","",$_SESSION['TriRHForm_General']);
			$_SESSION['TriRHForm_General']= str_replace($tri." DESC,","",$_SESSION['TriRHForm_General']);
			$_SESSION['TriRHForm_General']= str_replace($tri." ASC","",$_SESSION['TriRHForm_General']);
			$_SESSION['TriRHForm_General']= str_replace($tri." DESC","",$_SESSION['TriRHForm_General']);
			if($_SESSION['TriRHForm_'.$tri]==""){$_SESSION['TriRHForm_'.$tri]="ASC";$_SESSION['TriRHForm_General'].= $tri." ".$_SESSION['TriRHForm_'.$tri].",";}
			elseif($_SESSION['TriRHForm_'.$tri]=="ASC"){$_SESSION['TriRHForm_'.$tri]="DESC";$_SESSION['TriRHForm_General'].= $tri." ".$_SESSION['TriRHForm_'.$tri].",";}
			else{$_SESSION['TriRHForm_'.$tri]="";}
		}
	}
}
?>

<form class="test" action="Liste_FormationsHorsVacation.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $TDB; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $OngletTDB; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#e7aded;">
				<tr>
					<td class="TitrePage">
					<?php
					$leMenu=$Menu;
					if($TDB>0){$leMenu=$TDB;}
					if($OngletTDB<>""){$leMenu.="&OngletTDB=".$OngletTDB;}
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$leMenu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Liste des formations hors vacation";}else{echo "List of training outside working hours";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td width="15%" class="Libelle" <?php if($Menu=="2"){echo "style='display:none;'";} ?>>
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?>
				<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
				<?php
				if($Menu==4){
					if(DroitsFormationPlateforme($TableauIdPostesRH)){
						$requeteSite="SELECT Id, Libelle
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
					}
				}
				elseif($Menu==3){
					if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
						$requeteSite="SELECT Id, Libelle
							FROM new_competences_prestation
							WHERE Id_Plateforme IN 
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
								)
							AND Active=0
							ORDER BY Libelle ASC";
					}
					else{
						$requeteSite="SELECT Id, Libelle
							FROM new_competences_prestation
							WHERE Id IN 
								(SELECT Id_Prestation 
								FROM new_competences_personne_poste_prestation 
								WHERE Id_Personne=".$_SESSION["Id_Personne"]."
								AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
								)
							AND Active=0
							ORDER BY Libelle ASC";
						
					}
				}
				$resultPrestation=mysqli_query($bdd,$requeteSite);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationSelect = 0;
				$Selected = "";
				
				$PrestationSelect=$_SESSION['FiltreRHForm_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestations'];}
				$_SESSION['FiltreRHForm_Prestation']=$PrestationSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				if ($nbPrestation > 0)
				{
					while($row=mysqli_fetch_array($resultPrestation))
					{
						$selected="";
						if($PrestationSelect<>"")
							{if($PrestationSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle" <?php if($Menu=="2"){echo "style='display:none;'";} ?>>
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?>
				<select class="pole" style="width:100px;" name="pole" onchange="submit();">
				<?php

				if($Menu==4){
					if(DroitsFormationPlateforme($TableauIdPostesRH)){
						$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
								FROM new_competences_pole
								LEFT JOIN new_competences_prestation
								ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
								WHERE Id_Plateforme IN 
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
								)
								AND Actif=0
								AND new_competences_pole.Id_Prestation=".$PrestationSelect."
								ORDER BY new_competences_pole.Libelle ASC";
					}
				}
				elseif($Menu==3){
					if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
						$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
								FROM new_competences_pole
								LEFT JOIN new_competences_prestation
								ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
								WHERE Id_Plateforme IN 
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
								)
								AND Actif=0
								AND new_competences_pole.Id_Prestation=".$PrestationSelect."
								ORDER BY new_competences_pole.Libelle ASC";
					}
					else{
						$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
							FROM new_competences_pole
							LEFT JOIN new_competences_prestation
							ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
							WHERE new_competences_pole.Id IN 
								(SELECT Id_Pole 
								FROM new_competences_personne_poste_prestation 
								WHERE Id_Personne=".$_SESSION["Id_Personne"]."
								AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
								)
							AND Actif=0
							AND new_competences_pole.Id_Prestation=".$PrestationSelect."
							ORDER BY new_competences_pole.Libelle ASC";
					}
				}
				$resultPole=mysqli_query($bdd,$requetePole);
				$nbPole=mysqli_num_rows($resultPole);
				
				$PoleSelect=$_SESSION['FiltreRHForm_Pole'];
				if($_POST){$PoleSelect=$_POST['pole'];}
				$_SESSION['FiltreRHForm_Pole']=$PoleSelect;
				
				$Selected = "";
				echo "<option name='0' value='0' Selected></option>";
				if ($nbPole > 0)
				{
					while($row=mysqli_fetch_array($resultPole))
					{
						$selected="";
						if($PoleSelect<>"")
						{if($PoleSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle" <?php if($Menu==2){echo "style='display:none;'";} ?>>
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?>
				<select id="personne" style="width:100px;" name="personne" onchange="submit();">
					<option value='0'></option>
					<?php
						$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
								CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
							FROM new_rh_etatcivil
							LEFT JOIN rh_personne_mouvement 
							ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
							WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
							AND rh_personne_mouvement.EtatValidation=1 
							AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) IN
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
								)
							ORDER BY Personne ASC";
					
						$resultPersonne=mysqli_query($bdd,$requetePersonne);
						$NbPersonne=mysqli_num_rows($resultPersonne);
						
						$personne=$_SESSION['FiltreRHForm_Personne'];
						if($_POST){$personne=$_POST['personne'];}
						$_SESSION['FiltreRHForm_Personne']= $personne;
						
						while($rowPersonne=mysqli_fetch_array($resultPersonne))
						{
							echo "<option value='".$rowPersonne['Id']."'";
							if ($personne == $rowPersonne['Id']){echo " selected ";}
							echo ">".$rowPersonne['Personne']."</option>\n";
						}
					?>
				</select>
			</td>
			<td width="20%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Mois :";}else{echo "Month :";} ?>
				<select id="mois" name="mois" onchange="submit();">
					<?php
						if($_SESSION["Langue"]=="FR"){
							$arrayMois=array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
						}
						else{
							$arrayMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
						}
						$mois=$_SESSION['FiltreRHForm_Mois'];
						if($_POST){$mois=$_POST['mois'];}
						$_SESSION['FiltreRHForm_Mois']=$mois;
						
						for($i=0;$i<=11;$i++){
							$numMois=$i+1;
							if($numMois<10){$numMois="0".$numMois;}
							echo "<option value='".$numMois."'";
							if($mois== ($i+1)){echo " selected ";}
							echo ">".$arrayMois[$i]."</option>\n";
						}
						
						$annee=$_SESSION['FiltreRHForm_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						if($annee==""){$annee=date("Y");}
						$_SESSION['FiltreRHForm_Annee']=$annee;
					?>
				</select>
				<?php
					$MoisCumules=$_SESSION['FiltreRHForm_MoisCumules'];
					if($_POST){
						if(isset($_POST['MoisCumules'])){$MoisCumules="checked";}else{$MoisCumules="";}				
					}
					$_SESSION['FiltreRHForm_MoisCumules']=$MoisCumules;
				?>
				<input type="checkbox" id="MoisCumules" name="MoisCumules" value="MoisCumules" <?php echo $MoisCumules; ?>><?php if($_SESSION["Langue"]=="FR"){echo "Jusqu'à la fin de l'année";}else{echo "Until the end of the year";} ?> &nbsp;&nbsp;
			</td>
			<td width="8%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Année :";}else{echo "Year :";} ?>
				<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
			</td>
			<td width="5%">
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td width="20%" colspan="3" class="Libelle" <?php if($Menu==2){echo "style='display:none;'";} ?>>
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Etat :";}else{echo "State :";} ?>
					<?php
						$PrisEnCompte=$_SESSION['FiltreRHForm_EtatPrisEnCompte'];
						$NonPrisEnCompte=$_SESSION['FiltreRHForm_EtatNonPrisEnCompte'];
						if($_POST){
							if(isset($_POST['PrisEnCompte'])){$PrisEnCompte="checked";}else{$PrisEnCompte="";}
							if(isset($_POST['NonPrisEnCompte'])){$NonPrisEnCompte="checked";}else{$NonPrisEnCompte="";}
						}
						$_SESSION['FiltreRHForm_EtatPrisEnCompte']=$PrisEnCompte;
						$_SESSION['FiltreRHForm_EtatNonPrisEnCompte']=$NonPrisEnCompte;
					?>
					<input type="checkbox" id="NonPrisEnCompte" name="NonPrisEnCompte" value="NonPrisEnCompte" <?php echo $NonPrisEnCompte; ?>><?php if($_SESSION["Langue"]=="FR"){echo "NON PRIS EN COMPTE";}else{echo "NOT TAKEN INTO ACCOUNT";} ?> &nbsp;&nbsp;
					<input type="checkbox" id="PrisEnCompte" name="PrisEnCompte" value="PrisEnCompte" <?php echo $PrisEnCompte; ?>><?php if($_SESSION["Langue"]=="FR"){echo "PRIS EN COMPTE";}else{echo "TAKEN INTO ACCOUNT";} ?> &nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td valign="top" colspan="8" class="Libelle" <?php if($Menu<>4){echo "style='display:none;'";} ?>>
				<?php if($LangueAffichage=="FR"){echo "Responsable Projet";}else{echo "Project manager";}?> :<br>
						<?php
						
							$Id_RespProjet=$_SESSION['FiltreRHForm_RespProjet'];
							if($_POST){
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
							$_SESSION['FiltreRHForm_RespProjet']=$Id_RespProjet;
	
							$rqRespProjet="SELECT DISTINCT Id_Personne,
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
									$checkboxes = explode(',',$_SESSION['FiltreRHForm_RespProjet']);
									foreach($checkboxes as $value) {
										if($rowRespProjet['Id_Personne']==$value){$checked="checked";}
									}
								}
								echo "<input type='checkbox' class='checkRespProjet' name='Id_RespProjet[]' Id='Id_RespProjet[]' value='".$rowRespProjet['Id_Personne']."' ".$checked.">".$rowRespProjet['Personne'];
							}
						?>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<?php
	
	$dernierJourMois=date('Y-m-d',strtotime(date("Y-m-d",mktime(0,0,0,$mois,1,$annee))." last day of this month"));
	
	$reqSupp=" ";
	if($MoisCumules==""){
		$reqSupp=" AND form_session_date.DateSession<='".$dernierJourMois."' ";
	}
	//Formation dans l'outil formation  // Affichage en francais						   
		$requeteAnalyse="SELECT form_session_personne.Id ";
		$requete2="
			SELECT
				form_session_personne.Id,
				form_session_date.Id AS Id_SessionDate,
				form_session_date.DateSession,
				Id_Personne,DatePriseEnCompteRH,
				AttenteRetourSite,
				Heure_Debut,
				Heure_Fin,
				PauseRepas,
				HeureDebutPause,
				HeureFinPause,
				(
					SELECT (SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
					FROM rh_personne_contrat
					WHERE
						rh_personne_contrat.Suppr=0
						AND rh_personne_contrat.DateDebut<=form_session_date.DateSession
						AND (rh_personne_contrat.DateFin>=form_session_date.DateSession OR rh_personne_contrat.DateFin<='0001-01-01')
						AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
						AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
					ORDER BY
						DateDebut DESC,
						Id DESC
					LIMIT 1
				) AS Contrat,
				(
					SELECT SUM(Nb_Heures_Jour+Nb_Heures_Nuit) AS Nb 
					FROM rh_personne_hs 
					WHERE
						rh_personne_hs.Suppr=0 
						AND rh_personne_hs.Id_Personne=form_session_personne.Id_Personne
						AND DateHS=DateSession
				) AS NbHeuresSupp,
				(SELECT (SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Prestation,
				(SELECT (SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_Pole) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Pole,
				(
					SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=rh_personne_mouvement.Id_Prestation)
					FROM rh_personne_mouvement
					WHERE
						rh_personne_mouvement.Suppr=0
						AND rh_personne_mouvement.Id_Personne=form_session_personne.Id_Personne 
						AND rh_personne_mouvement.EtatValidation=1
						AND rh_personne_mouvement.DateDebut<=form_session_date.DateSession
						AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>=form_session_date.DateSession)
					ORDER BY rh_personne_mouvement.DateFin DESC
					LIMIT 1
				) AS PrestationReelle,
				(
					SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=rh_personne_mouvement.Id_Pole)
					FROM rh_personne_mouvement
					WHERE
						rh_personne_mouvement.Suppr=0
						AND rh_personne_mouvement.Id_Personne=form_session_personne.Id_Personne 
						AND rh_personne_mouvement.EtatValidation=1
						AND rh_personne_mouvement.DateDebut<=form_session_date.DateSession
						AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>=form_session_date.DateSession)
					ORDER BY rh_personne_mouvement.DateFin DESC
					LIMIT 1
				) AS PoleReel,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Personne,
				(
					SELECT IF(form_session.Recyclage=1,LibelleRecyclage,Libelle)
					FROM form_formation_langue_infos
					WHERE
						form_formation_langue_infos.Id_Formation=form_session.Id_Formation
						AND form_formation_langue_infos.Id_Langue=
						(
							SELECT Id_Langue 
							FROM form_formation_plateforme_parametres 
							WHERE
								Id_Plateforme=1
								AND Id_Formation=form_session.Id_Formation
								AND Suppr=0 
							LIMIT 1
						)
						AND Suppr=0
				) AS Formation ";
		$requete=" FROM
				form_session_date,
				form_session,
				form_session_personne
			WHERE
				form_session_date.Id_Session=form_session.Id
				AND form_session_date.Id_Session=form_session_personne.Id_Session
				AND form_session_date.Suppr=0 
				AND form_session.Suppr=0
				AND form_session.Annule=0 
				AND form_session_personne.Suppr=0
				AND form_session_date.DateSession>='".$annee."-".$mois."-01'
				AND YEAR(form_session_date.DateSession)='".$annee."'
				".$reqSupp."
				AND form_session_personne.Id_Personne IN
				(
					SELECT DISTINCT rh_personne_mouvement.Id_Personne
					FROM rh_personne_mouvement 
					WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
					AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
					AND rh_personne_mouvement.EtatValidation=1 
					AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) IN
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
					)
				) 
				AND form_session_personne.Validation_Inscription=1
				AND form_session_personne.Id_Session=form_session.Id
				AND Presence IN (0,1)
			  ";
		
		if($_SESSION['FiltreRHForm_Prestation']<>0){
			$requete.=" AND (SELECT Id_Prestation FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin)=".$_SESSION['FiltreRHForm_Prestation']." ";
			if($_SESSION['FiltreRHForm_Pole']<>0){
				$requete.=" AND (SELECT Id_Pole FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin)=".$_SESSION['FiltreRHForm_Pole']." ";
			}
		}
		
		if($_SESSION['FiltreRHForm_RespProjet']<>""){
			$requete.="AND (SELECT CONCAT(Id_Prestation,'_',Id_Pole) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin)
						IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
							FROM new_competences_personne_poste_prestation
							WHERE Id_Personne IN (".$_SESSION['FiltreRHForm_RespProjet'].")
							AND Id_Poste IN (".$IdPosteResponsableProjet.")
						)
						";
		}
		
		
		if($_SESSION['FiltreRHForm_Personne']<>0){
			$requete.=" AND Id_Personne=".$_SESSION['FiltreRHForm_Personne']." ";
		}
		if($_SESSION['FiltreRHForm_EtatPrisEnCompte']<>"" || $_SESSION['FiltreRHForm_EtatNonPrisEnCompte']){
			$requete.=" AND ( ";
			if($_SESSION['FiltreRHForm_EtatPrisEnCompte']<>""){
				$requete.=" DatePriseEnCompteRH>'0001-01-01' OR ";
			}
			if($_SESSION['FiltreRHForm_EtatNonPrisEnCompte']<>""){
				$requete.=" DatePriseEnCompteRH<='0001-01-01' OR ";
			}
			$requete=substr($requete,0,-3);
			$requete.=" ) ";
		}
		
		$requeteOrder="";
		if($_SESSION['TriRHForm_General']<>""){
			$requeteOrder="ORDER BY ".substr($_SESSION['TriRHForm_General'],0,-1);
		}

		$result=mysqli_query($bdd,$requeteAnalyse.$requete);
		
		$nbResulta=mysqli_num_rows($result);

		$result=mysqli_query($bdd,$requete2.$requete.$requeteOrder);
		$couleur="#FFFFFF";

	?>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_FormationsHorsVacation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Personne"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriRHForm_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHForm_Personne']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_FormationsHorsVacation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Contrat"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?><?php if($_SESSION['TriRHForm_Contrat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHForm_Contrat']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_FormationsHorsVacation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=PrestationReelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?><?php if($_SESSION['TriRHForm_PrestationReelle']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHForm_PrestationReelle']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_FormationsHorsVacation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=PoleReel"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle";}else{echo "Pole";} ?><?php if($_SESSION['TriRHForm_PoleReel']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHForm_PoleReel']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_FormationsHorsVacation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Prestation"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation<br>(inscription)";}else{echo "Site<br>(registration)";} ?><?php if($_SESSION['TriRHForm_Prestation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHForm_Prestation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_FormationsHorsVacation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Pole"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle<br>(inscription)";}else{echo "Pole<br>(inscription)";} ?><?php if($_SESSION['TriRHForm_Pole']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHForm_Pole']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_FormationsHorsVacation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=DateSession"><?php if($_SESSION["Langue"]=="FR"){echo "Date formation";}else{echo "Training date";} ?><?php if($_SESSION['TriRHForm_DateSession']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHForm_DateSession']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_FormationsHorsVacation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Heure_Debut"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?><?php if($_SESSION['TriRHForm_Heure_Debut']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHForm_Heure_Debut']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_FormationsHorsVacation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Heure_Fin"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?><?php if($_SESSION['TriRHForm_Heure_Fin']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHForm_Heure_Fin']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="20%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_FormationsHorsVacation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Formation"><?php if($_SESSION["Langue"]=="FR"){echo "Formation";}else{echo "Training";} ?><?php if($_SESSION['TriRHForm_Formation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHForm_Formation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;"><?php if($_SESSION["Langue"]=="FR"){echo "Nb heures hors vacation";}else{echo "Number of hours excluding vacation";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;"><?php if($_SESSION["Langue"]=="FR"){echo "Vacations";}else{echo "Vacations";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Heures supp. déclarées";}else{echo "Overtime declared";} ?></td>
					<td class="EnTeteTableauCompetences" width="20%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_FormationsHorsVacation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=DatePriseEnCompteRH"><?php if($_SESSION["Langue"]=="FR"){echo "Pris en compte";}else{echo "Taken into account";} ?><?php if($_SESSION['TriRHForm_DatePriseEnCompteRH']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHForm_DatePriseEnCompteRH']=="ASC"){echo "&darr;";}?></a></td>
					<?php
						if($Menu==4){
					?>
					<td class='EnTeteTableauCompetences' width="15%" align="center">
						<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Prendre en compte";}else{echo "Take into account";}?>" onclick="if(!confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir valider la sélection ?";}else{echo "Are you sure you want to validate the selection?";} ?>')) return false;" name="validerSelectionRH" value="<?php if($_SESSION["Langue"]=="FR"){echo "Valider la date de prise en compte";}else{echo "Validate the date of taking into account";} ?>">&nbsp;
					</td>
					<td class='EnTeteTableauCompetences' width="15%" align="center">
						<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Attendre retour site";}else{echo "Waiting back site";}?>" onclick="if(!confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir valider la sélection ?";}else{echo "Are you sure you want to validate the selection?";} ?>')) return false;" name="AttendreRetourSite" value="<?php if($_SESSION["Langue"]=="FR"){echo "Attendre retour site";}else{echo "Waiting back site";} ?>">&nbsp;
					</td>
					<?php 
						}
					?>
				</tr>
	<?php
			
			if(isset($_POST['validerSelectionRH'])){
				while($row=mysqli_fetch_array($result)){
					if (isset($_POST['checkRH_'.$row['Id'].''])){
						$requeteUpdate="UPDATE form_session_personne SET 
								Id_RH=".$_SESSION['Id_Personne'].",
								DatePriseEnCompteRH='".date('Y-m-d')."'
								WHERE Id=".$row['Id']." ";
						$resultat=mysqli_query($bdd,$requeteUpdate);
					}
				}
				$result=mysqli_query($bdd,$requete2.$requete.$requeteOrder);
			}
			
			if(isset($_POST['AttendreRetourSite'])){
				while($row=mysqli_fetch_array($result)){
					$attente=0;
					if (isset($_POST['checkAttendre_'.$row['Id'].''])){$attente=1;}
					$requeteUpdate="UPDATE form_session_personne SET 
							AttenteRetourSite=".$attente."
							WHERE Id=".$row['Id']." ";
					$resultat=mysqli_query($bdd,$requeteUpdate);
				}
				$result=mysqli_query($bdd,$requete2.$requete.$requeteOrder);
			}
			
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					$Etat="";
					
					
					$Travail=0;
					$bgcolor="";
					$type="";
					$laCouleur=TravailCeJourDeSemaine($row['DateSession'],$row['Id_Personne']);
					if($laCouleur<>""){
						$Travail=1;
						$bgcolor="bgcolor='".$laCouleur."'";
						
						$Id_Contenu=IdVacationCeJourDeSemaine($row['DateSession'],$row['Id_Personne']);
						if($Id_Contenu==1){
							$type="J";
						}
						elseif($Id_Contenu==15){
							$type="SDL";
						}
						elseif($Id_Contenu==18){
							$type="SD";
						}
						else{
							$type="VSD";
						}
						
					}
					//Vacation particulière
					$VacParticuliere=0;
					$Id_PrestationPole=PrestationPole_Personne($row['DateSession'],$row['Id_Personne']);
					if($Id_PrestationPole<>0){
						$tabPresta=explode("_",$Id_PrestationPole);
						$Id_Presta=$tabPresta[0];
						$Id_Pole=$tabPresta[1];
						
						$req="SELECT Id_Vacation,Id_Prestation,Id_Pole,DateVacation,
							rh_vacation.Nom,rh_vacation.Couleur
							FROM rh_personne_vacation 
							LEFT JOIN rh_vacation
							ON rh_personne_vacation.Id_Vacation=rh_vacation.Id
							WHERE rh_personne_vacation.Suppr=0
							AND rh_personne_vacation.Id_Vacation>0
							AND rh_personne_vacation.Id_Personne=".$row['Id_Personne']."
							AND rh_personne_vacation.DateVacation>='".$row['DateSession']."' 
							AND rh_personne_vacation.DateVacation<='".$row['DateSession']."' 
							";
	
						$resultVac=mysqli_query($bdd,$req);
						$nbVac=mysqli_num_rows($resultVac);
						if($nbVac>0){
							mysqli_data_seek($resultVac,0);
							while($rowVac=mysqli_fetch_array($resultVac)){
								if($rowVac['Id_Prestation']==$Id_Presta && $rowVac['Id_Pole']==$Id_Pole && $rowVac['DateVacation']==$row['DateSession']){
									$type=$rowVac['Nom'];
									$bgcolor="bgcolor='".$rowVac['Couleur']."'";
									$VacParticuliere=1;
									break;
								}
							}
						}
					}
					
					//Absences
					if($Travail==1){
						$reqAbs="SELECT rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial,
							(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
							(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef
							FROM rh_absence 
							LEFT JOIN rh_personne_demandeabsence 
							ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
							WHERE rh_personne_demandeabsence.Id_Personne=".$row['Id_Personne']." 
							AND rh_absence.DateFin>='".$row['DateSession']."' 
							AND rh_absence.DateDebut<='".$row['DateSession']."' 
							AND rh_personne_demandeabsence.Suppr=0 
							AND rh_absence.Suppr=0  
							AND rh_personne_demandeabsence.Conge=0 
							AND rh_personne_demandeabsence.EtatN1<>-1 
							AND rh_personne_demandeabsence.EtatN2<>-1
							ORDER BY rh_absence.Id DESC, rh_absence.Id_Personne_DA DESC ";
					$resultAbs=mysqli_query($bdd,$reqAbs);
					$nbAbs=mysqli_num_rows($resultAbs);
						if($nbAbs>0){
							mysqli_data_seek($resultAbs,0);
							while($rowAbs=mysqli_fetch_array($resultAbs)){
								if($rowAbs['DateDebut']<=$row['DateSession'] && $rowAbs['DateFin']>=$row['DateSession']){
									$bEtat="validee";
									if($rowAbs['TypeAbsenceDef']<>""){
										$type=$rowAbs['TypeAbsenceDef'];
										if($rowAbs['Id_TypeAbsenceDefinitif']==0){
											$bEtat="absInjustifiee";
											$type="ABS";
										}
									}
									else{
										$type=$rowAbs['TypeAbsenceIni'];
										if($rowAbs['Id_TypeAbsenceInitial']==0){$bEtat="absInjustifiee";$type="ABS";}
									}
									break;
								}
							}
						}
					}
					
					//Congés
					$reqConges="SELECT rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,
							rh_personne_demandeabsence.EtatN1,rh_personne_demandeabsence.EtatN2,rh_personne_demandeabsence.EtatRH,
							(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
							(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef
							FROM rh_absence 
							LEFT JOIN rh_personne_demandeabsence 
							ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
							WHERE rh_personne_demandeabsence.Id_Personne=".$row['Id_Personne']." 
							AND rh_absence.DateFin>='".$row['DateSession']."' 
							AND rh_absence.DateDebut<='".$row['DateSession']."' 
							AND rh_personne_demandeabsence.Suppr=0 
							AND rh_absence.Suppr=0 
							AND rh_personne_demandeabsence.Annulation=0 
							AND rh_personne_demandeabsence.Conge=1 
							AND rh_personne_demandeabsence.EtatN1<>-1 
							AND rh_personne_demandeabsence.EtatN2<>-1
							ORDER BY rh_absence.Id DESC, rh_absence.Id_Personne_DA DESC ";
				$resultConges=mysqli_query($bdd,$reqConges);
				$nbConges=mysqli_num_rows($resultConges);
					if($nbConges>0){
						mysqli_data_seek($resultConges,0);
						while($rowConges=mysqli_fetch_array($resultConges)){
							if($rowConges['DateDebut']<=$row['DateSession'] && $rowConges['DateFin']>=$row['DateSession']){
								if($rowConges['TypeAbsenceDef']<>""){$type=$rowConges['TypeAbsenceDef'];}
								else{$type=$rowConges['TypeAbsenceIni'];}
								$bEtat="attenteValidation";
								if($rowConges['EtatN2']==1 && $rowConges['EtatRH']==1){$bEtat="validee";}
								break;
							}
						}
					}
					if($VacParticuliere==0){
						$jourFixe=estJour_Fixe($row['DateSession'],$_SESSION['Id_Personne']);
						if($jourFixe<>""){
							$type=$jourFixe;
						}
					}
					
					$prisEnCompte="";
					if($row['DatePriseEnCompteRH']>"0001-01-01"){
						$prisEnCompte="<img src=\"../../Images/tick.png\" border=\"0\">";
					}
					
					//Horaires de la personne
					$HeureDebutTravail="00:00:00";
					$HeureFinTravail="00:00:00";
					$tab=HorairesJournee($row['Id_Personne'],$row['DateSession']);
					if(sizeof($tab)>0){
						$HeureDebutTravail=$tab[0];
						$HeureFinTravail=$tab[1];
					}
					
					$nbHeureFormationHorsVac=date('H:i',strtotime($row['DateSession'].' 00:00:00'));
					$nbHeureFormation=date('H:i',strtotime($row['DateSession'].' 00:00:00'));
					
					if($HeureFinTravail<"03:00:00"){$HeureFinTravail="23:59:00";};
					
					if($HeureDebutTravail<>"00:00:00" && $HeureFinTravail<>"00:00:00"){

						//Nombre total d'heure de formation
						$hF=strtotime($row['Heure_Fin']);
						$hD=strtotime($row['Heure_Debut']);
						
						$hFP=strtotime($row['HeureFinPause']);
						$hDP=strtotime($row['HeureDebutPause']);
							
						$hFTravail=strtotime($HeureFinTravail);
						$hDTravail=strtotime($HeureDebutTravail);
						
						$valDebut=gmdate("H:i",$hD-$hD);
						$valHPause=gmdate("H:i",$hD-$hD);
						$valFin=gmdate("H:i",$hF-$hF);
						
						//Nombre d'heure hors début vacation 
						if($hFTravail<=$hD || $hDTravail>=$hF){
							$valDebut=gmdate("H:i",$hF-$hD);
							if($row['PauseRepas']==1 && $hDP>$hD && $hFP<$hF){
								if($hDP<$hF && $hFP>$hD){
									if($hFP>$hF){$hFP=$hF;}
									if($hDP<$hD){$hDP=$hD;}
									$valPause=gmdate("H:i",$hFP-$hDP);
									$valDebut=gmdate("H:i",strtotime($valDebut)-strtotime($valPause));
								}
							}
							
						}
						else{
							if($hD<$hDTravail){
								if($hDP<$hDTravail && $row['PauseRepas']==1 && $hDP>$hD && $hFP<$hF){
									$valDebut=gmdate("H:i",$hDP-$hD);
									if($hFP<$hDTravail){
										$valHPause=gmdate("H:i",$hDTravail-$hFP);
									}
								}
								else{
									$valDebut=gmdate("H:i",$hDTravail-$hD);
									
								}
								
							}
							if($hF>$hFTravail){
								if($hFP>$hFTravail && $row['PauseRepas']==1 && $hDP>$hD && $hFP<$hF){
									$valDebut=gmdate("H:i",$hF-$hFP);
									if($hDP>$hFTravail){
										$valHPause=gmdate("H:i",$hDP-$hFTravail);
									}
								}
								else{
									$valFin=gmdate("H:i",$hF-$hFTravail);
								}
								
							}
							
						}
						$nbHeureFormHorsVacDebut=intval(date('H',strtotime($valDebut." + 0 hour"))).".".substr((date('i',strtotime($valDebut." + 0 hour"))/0.6),0,2);
						$nbHeureFormHorsVacAvantPause=intval(date('H',strtotime($valHPause." + 0 hour"))).".".substr((date('i',strtotime($valHPause." + 0 hour"))/0.6),0,2);
						$nbHeureFormHorsVacFin=intval(date('H',strtotime($valFin." + 0 hour"))).".".substr((date('i',strtotime($valFin." + 0 hour"))/0.6),0,2);
						$nbHeureFormationHorsVac=$nbHeureFormHorsVacDebut+$nbHeureFormHorsVacAvantPause+$nbHeureFormHorsVacFin;
					}
					if($nbHeureFormationHorsVac<>"00:00"){
						if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
						else{$couleur="#FFFFFF";}
		?>
						<tr bgcolor="<?php echo $couleur;?>">
							<td align="center"><a style="color:#3e65fa;" href="javascript:OuvreFenetreModif(<?php echo $Menu; ?>,<?php echo $row['Id']; ?>,<?php echo $row['Id_SessionDate']; ?>)"><?php echo stripslashes($row['Personne']);?></a></td>
							<td><?php echo $row['Contrat'];?></td>
							<td><?php echo stripslashes($row['PrestationReelle']);?></td>
							<td><?php echo stripslashes($row['PoleReel']);?></td>
							<td><?php echo substr(stripslashes($row['Prestation']),0,7);?></td>
							<td><?php echo stripslashes($row['Pole']);?></td>
							<td><?php echo AfficheDateJJ_MM_AAAA($row['DateSession']);?></td>
							<td><?php echo $row['Heure_Debut'];?></td>
							<td><?php echo $row['Heure_Fin'];?></td>
							<td><?php echo stripslashes($row['Formation']);?></td>
							<td align="center"><?php echo $nbHeureFormationHorsVac;?></td>
							<td align="center"><?php echo $type;?></td>
							<td><?php echo $row['NbHeuresSupp'];?></td>
							<td><?php echo $prisEnCompte; ?></td>
							<?php
								if($Menu==4){
							?>
							<td align="center">
							<?php
								if($row['DatePriseEnCompteRH']<="0001-01-01"){
									echo "<input class='check' type='checkbox' name='checkRH_".$row['Id']."' value=''>";
								}
							?>
							</td>
							
							<td align="center">
							<?php
								$check="";
								if($row['AttenteRetourSite']==1){$check="checked";}
								echo "<input class='check' type='checkbox' name='checkAttendre_".$row['Id']."' ".$check." value=''>";
							?>
							</td>
							<?php
								}
							?>
						</tr>
					<?php
					}
				}
			}
			?>
			</table>
		</td>
	</tr>

</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
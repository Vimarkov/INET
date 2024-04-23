<?php
require("../../Menu.php");

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
	$tab = array("Id","Personne","Contrat","PrestationReelle","PoleReel","Prestation","Pole","DateSession","Formation");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriRHFormAnomalie_General']= str_replace($tri." ASC,","",$_SESSION['TriRHFormAnomalie_General']);
			$_SESSION['TriRHFormAnomalie_General']= str_replace($tri." DESC,","",$_SESSION['TriRHFormAnomalie_General']);
			$_SESSION['TriRHFormAnomalie_General']= str_replace($tri." ASC","",$_SESSION['TriRHFormAnomalie_General']);
			$_SESSION['TriRHFormAnomalie_General']= str_replace($tri." DESC","",$_SESSION['TriRHFormAnomalie_General']);
			if($_SESSION['TriRHFormAnomalie_'.$tri]==""){$_SESSION['TriRHFormAnomalie_'.$tri]="ASC";$_SESSION['TriRHFormAnomalie_General'].= $tri." ".$_SESSION['TriRHFormAnomalie_'.$tri].",";}
			elseif($_SESSION['TriRHFormAnomalie_'.$tri]=="ASC"){$_SESSION['TriRHFormAnomalie_'.$tri]="DESC";$_SESSION['TriRHFormAnomalie_General'].= $tri." ".$_SESSION['TriRHFormAnomalie_'.$tri].",";}
			else{$_SESSION['TriRHFormAnomalie_'.$tri]="";}
		}
	}
}
?>

<form class="test" action="Liste_FormationsAnomalies.php" method="post">
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
						
					if($LangueAffichage=="FR"){echo "Liste des formations en anomalie (horaires de travail non configurés)";}else{echo "List of formations in anomaly (unconfigured work hours)";}
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
				
				$PrestationSelect=$_SESSION['FiltreRHFormAnomalie_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestations'];}
				$_SESSION['FiltreRHFormAnomalie_Prestation']=$PrestationSelect;	
				
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
				
				$PoleSelect=$_SESSION['FiltreRHFormAnomalie_Pole'];
				if($_POST){$PoleSelect=$_POST['pole'];}
				$_SESSION['FiltreRHFormAnomalie_Pole']=$PoleSelect;
				
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
						
						$personne=$_SESSION['FiltreRHFormAnomalie_Personne'];
						if($_POST){$personne=$_POST['personne'];}
						$_SESSION['FiltreRHFormAnomalie_Personne']= $personne;
						
						while($rowPersonne=mysqli_fetch_array($resultPersonne))
						{
							echo "<option value='".$rowPersonne['Id']."'";
							if ($personne == $rowPersonne['Id']){echo " selected ";}
							echo ">".$rowPersonne['Personne']."</option>\n";
						}
					?>
				</select>
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Mois :";}else{echo "Month :";} ?>
				<select id="mois" name="mois" onchange="submit();">
					<option value=""></option>
					<?php
						if($_SESSION["Langue"]=="FR"){
							$arrayMois=array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
						}
						else{
							$arrayMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
						}
						$mois=$_SESSION['FiltreRHFormAnomalie_Mois'];
						if($_POST){$mois=$_POST['mois'];}
						$_SESSION['FiltreRHFormAnomalie_Mois']=$mois;
						
						for($i=0;$i<=11;$i++){
							$numMois=$i+1;
							if($numMois<10){$numMois="0".$numMois;}
							echo "<option value='".$numMois."'";
							if($mois== ($i+1)){echo " selected ";}
							echo ">".$arrayMois[$i]."</option>\n";
						}
						
						$annee=$_SESSION['FiltreRHFormAnomalie_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						if($annee==""){$annee=date("Y");}
						$_SESSION['FiltreRHFormAnomalie_Annee']=$annee;
					?>
				</select>
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
			<td valign="top" colspan="8" class="Libelle" <?php if($Menu<>4){echo "style='display:none;'";} ?>>
				<?php if($LangueAffichage=="FR"){echo "Responsable Projet";}else{echo "Project manager";}?> :<br>
						<?php
						
							$Id_RespProjet=$_SESSION['FiltreRHFormAnomalie_RespProjet'];
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
							$_SESSION['FiltreRHFormAnomalie_RespProjet']=$Id_RespProjet;
	
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
									$checkboxes = explode(',',$_SESSION['FiltreRHFormAnomalie_RespProjet']);
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
	
	//Formation dans l'outil formation  // Affichage en francais						   
		$requete2="SELECT DISTINCT 
				form_session_date.DateSession,
				Id_Personne,
				(SELECT (SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
				FROM rh_personne_contrat
				WHERE rh_personne_contrat.Suppr=0
				AND rh_personne_contrat.DateDebut<=form_session_date.DateSession
				AND (rh_personne_contrat.DateFin>=form_session_date.DateSession OR rh_personne_contrat.DateFin<='0001-01-01')
				AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
				AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
				ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS Contrat,
				(SELECT (SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Prestation,
				(SELECT (SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_Pole) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Pole,
				(SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=rh_personne_mouvement.Id_Prestation)
					FROM rh_personne_mouvement
					WHERE rh_personne_mouvement.Suppr=0
					AND rh_personne_mouvement.Id_Personne=form_session_personne.Id_Personne 
					AND rh_personne_mouvement.EtatValidation=1
					AND rh_personne_mouvement.DateDebut<=form_session_date.DateSession
					AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>=form_session_date.DateSession)
					LIMIT 1
				) AS PrestationReelle,
				(SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=rh_personne_mouvement.Id_Pole)
					FROM rh_personne_mouvement
					WHERE rh_personne_mouvement.Suppr=0
					AND rh_personne_mouvement.Id_Personne=form_session_personne.Id_Personne 
					AND rh_personne_mouvement.EtatValidation=1
					AND rh_personne_mouvement.DateDebut<=form_session_date.DateSession
					AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>=form_session_date.DateSession)
					LIMIT 1
				) AS PoleReel,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Personne ";
		$requete=" FROM
				form_session_date,
				form_session,
				form_session_personne
			WHERE
				form_session_date.Id_Session=form_session.Id
				AND form_session_date.Id_Session=form_session_personne.Id_Session
				AND form_session_date.Suppr=0 
				AND form_session.Suppr=0
				AND form_session.Annule=0 ";
		if($mois<>""){
			$dernierJourMois=date('Y-m-d',strtotime(date("Y-m-d",mktime(0,0,0,$mois,1,$annee))." last day of this month"));
			$requete.="AND form_session_date.DateSession>='".$annee."-".$mois."-01'
				AND form_session_date.DateSession<='".$dernierJourMois."' ";
		}
		else{
			$requete.="AND YEAR(form_session_date.DateSession)='".$annee."' ";
		}
		$requete.="AND form_session_personne.Suppr=0
				AND form_session_personne.Id_Personne IN (
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
		
		if($_SESSION['FiltreRHFormAnomalie_Prestation']<>0){
			$requete.=" AND (SELECT Id_Prestation FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin)=".$_SESSION['FiltreRHFormAnomalie_Prestation']." ";
			if($_SESSION['FiltreRHFormAnomalie_Pole']<>0){
				$requete.=" AND (SELECT Id_Pole FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin)=".$_SESSION['FiltreRHFormAnomalie_Pole']." ";
			}
		}

		if($_SESSION['FiltreRHFormAnomalie_Personne']<>0){
			$requete.=" AND Id_Personne=".$_SESSION['FiltreRHFormAnomalie_Personne']." ";
		}
		if($_SESSION['FiltreRHFormAnomalie_RespProjet']<>""){
			$requete.="AND (SELECT CONCAT(Id_Prestation,'_',Id_Pole) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin)
						IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
							FROM new_competences_personne_poste_prestation
							WHERE Id_Personne IN (".$_SESSION['FiltreRHFormAnomalie_RespProjet'].")
							AND Id_Poste IN (".$IdPosteResponsableProjet.")
						)
						";
		}
		
		$requeteOrder="";
		if($_SESSION['TriRHFormAnomalie_General']<>""){
			$requeteOrder="ORDER BY ".substr($_SESSION['TriRHFormAnomalie_General'],0,-1);
		}

		$result=mysqli_query($bdd,$requete2.$requete.$requeteOrder);
		$nbResulta=mysqli_num_rows($result);
		
		$couleur="#FFFFFF";

	?>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="80%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_FormationsAnomalies.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Personne"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriRHFormAnomalie_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHFormAnomalie_Personne']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_FormationsAnomalies.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Contrat"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?><?php if($_SESSION['TriRHFormAnomalie_Contrat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHFormAnomalie_Contrat']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_FormationsHorsVacation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=PrestationReelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?><?php if($_SESSION['TriRHForm_PrestationReelle']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHForm_PrestationReelle']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_FormationsHorsVacation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=PoleReel"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle";}else{echo "Pole";} ?><?php if($_SESSION['TriRHForm_PoleReel']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHForm_PoleReel']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_FormationsAnomalies.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Prestation"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation<br>(inscription)";}else{echo "Site<br>(inscription)";} ?><?php if($_SESSION['TriRHFormAnomalie_Prestation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHFormAnomalie_Prestation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_FormationsAnomalies.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Pole"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle<br>(inscription)";}else{echo "Pole<br>(inscription)";} ?><?php if($_SESSION['TriRHFormAnomalie_Pole']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHFormAnomalie_Pole']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_FormationsAnomalies.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=DateSession"><?php if($_SESSION["Langue"]=="FR"){echo "Date formation";}else{echo "Training date";} ?><?php if($_SESSION['TriRHFormAnomalie_DateSession']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHFormAnomalie_DateSession']=="ASC"){echo "&darr;";}?></a></td>
				</tr>
	<?php
			
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					$Etat="";
					$Travail=0;
					$bgcolor="";
					$type="";
					$laCouleur=TravailCeJourDeSemaine($row['DateSession'],$row['Id_Personne']);
					if($laCouleur<>""){
						//Horaires de la personne
						$HeureDebutTravail="00:00:00";
						$HeureFinTravail="00:00:00";
						$tab=HorairesJournee($row['Id_Personne'],$row['DateSession']);
						if(sizeof($tab)>0){
							$HeureDebutTravail=$tab[0];
							$HeureFinTravail=$tab[1];
						}

						$reqContrat="SELECT Id,Id_Personne,DateDebut,DateFin,Id_TempsTravail,
									(SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS EstSalarie
									FROM rh_personne_contrat
									WHERE Suppr=0
									AND DateDebut<='".$row['DateSession']."'
									AND (DateFin>='".$row['DateSession']."' OR DateFin<='0001-01-01' )
									AND Id_Personne=".$row['Id_Personne']."
									AND TypeDocument IN ('Nouveau','Avenant')
									ORDER BY DateDebut DESC, Id DESC
									";

						$resultC=mysqli_query($bdd,$reqContrat);
						$nb=mysqli_num_rows($resultC);	
						$Id_TypeContrat=0;
						if($nb>0){
							$rowContrat=mysqli_fetch_array($resultC);
							$Id_TypeContrat=$rowContrat['Id_TempsTravail'];
						}
						//Uniquement si non cadre
						if($Id_TypeContrat<>10){
							if($HeureDebutTravail=="00:00:00" && $HeureFinTravail=="00:00:00"){
								if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
								else{$couleur="#FFFFFF";}
								?>
									<tr bgcolor="<?php echo $couleur;?>">
										<td><?php echo stripslashes($row['Personne']);?></td>
										<td><?php echo stripslashes($row['Contrat']);?></td>
										<td><?php echo stripslashes($row['PrestationReelle']);?></td>
										<td><?php echo stripslashes($row['PoleReel']);?></td>
										<td><?php echo substr(stripslashes($row['Prestation']),0,7);?></td>
										<td><?php echo stripslashes($row['Pole']);?></td>
										<td><?php echo AfficheDateJJ_MM_AAAA($row['DateSession']);?></td>
									</tr>
								<?php
							}
						}
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
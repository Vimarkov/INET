<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Modifier un groupe de formation</title><meta name="robots" content="noindex">
	<link href="../JS/styleCalendrier.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" charset="utf-8" src="SessionGroupe.js?t=<?php echo time(); ?>"></script>
	<script type="text/javascript" src="../JS/date.js"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>
	<script>
		function datepick() {
			if (!Modernizr.inputtypes['date']) {
				$('input[type=date]').datepicker({
					dateFormat: 'dd/mm/yy'
				});
			}
		}
	</script>
</head>
<body>

<?php
Ecrire_Code_JS_Init_Date();

if($_POST)
{
	$requete="";
	if(isset($_POST['sauvegarde'])){
		if($_POST['Mode']=="M"){

			//Parcours des différentes formations du groupe de formation
			$tab = explode(";",$_POST['IdChampsSessions']);
			foreach($tab as $valeur){
				 if($valeur<>""){
					//Mise à jour de la formation
					$stagMax=0;
					$stagMin=0;
					$diffuser=0;
					if($_POST['formationsLiees']==0){
						if($_POST['stagiaireMin_'.$valeur]<>""){$stagMin=$_POST['stagiaireMin_'.$valeur];}
						if($_POST['stagiaireMax_'.$valeur]<>""){$stagMax=$_POST['stagiaireMax_'.$valeur];}
						$diffuser=$_POST['diffuser_'.$valeur];
					}
					else{
						if($_POST['stagiaireMin']<>""){$stagMin=$_POST['stagiaireMin'];}
						if($_POST['stagiaireMax']<>""){$stagMax=$_POST['stagiaireMax'];}
						$diffuser=$_POST['diffuser'];
					}
					
					$requete="UPDATE form_session SET";
					$requete.=" Id_Lieu=".$_POST['lieu_'.$valeur]."";
					$requete.=", Id_Formateur=".$_POST['formateur_'.$valeur];
					$requete.=", Nb_Stagiaire_Mini=".$stagMin;
					$requete.=", Nb_Stagiaire_Maxi=".$stagMax;
					$requete.=", Formation_Liee=".$_POST['formationsLiees'];
					$requete.=", Diffusion_Creneau=".$diffuser;
					$requete.=", Id_Personne_MAJ=".$IdPersonneConnectee."";
					$requete.=", Date_MAJ='".date('Y-m-d')."' ";
					$requete.=", MessageConvocation='".addslashes($_POST['message_'.$valeur])."'";
					$requete.=", MessageInscription='".addslashes($_POST['messageInscription_'.$valeur])."'";
					$requete.=" WHERE Id=".$valeur;
					$resultM=mysqli_query($bdd,$requete);
					
					//Mise à jour des dates des formations
					
					$req="DELETE FROM form_session_date WHERE Id_Session=".$valeur;
					$resultS=mysqli_query($bdd,$req);
					$dates="";
					for($i=1;$i<=$_POST['nbJours_'.$valeur];$i++){
						$requete="INSERT INTO form_session_date (Id_Session,DateSession,Heure_Debut,Heure_Fin,PauseRepas,HeureDebutPause,HeureFinPause) ";
						$requete.="VALUES (".$valeur.",'".TrsfDate_($_POST['dateDebut_'.$valeur.'_'.$i])."','".$_POST['heureDebut_'.$valeur.'_'.$i]."','".$_POST['heureFin_'.$valeur.'_'.$i]."',";
						$requete.="".$_POST['pauseRepas_'.$valeur.'_'.$i].",'".$_POST['heureDebutPause_'.$valeur.'_'.$i]."','".$_POST['heureFinPause_'.$valeur.'_'.$i]."')";
						$resultA=mysqli_query($bdd,$requete);
						$dates.=AfficheDateJJ_MM_AAAA(TrsfDate_($_POST['dateDebut_'.$valeur.'_'.$i]))."<br>";
					}
					
					//Suppression des prestations
					$requete="DELETE FROM form_session_prestation WHERE Id_Session=".$valeur." ";
					$resultD=mysqli_query($bdd,$requete);
					
					
					//Pour chaque formation vérifier si celle-ci n'a pas une formation équivalente
					$reqSimil="SELECT Id_Formation 
								FROM form_formationequivalente_formationplateforme 
								WHERE Id_FormationEquivalente IN (SELECT Id_FormationEquivalente 
								FROM form_formationequivalente_formationplateforme 
								LEFT JOIN form_formationequivalente 
								ON form_formationequivalente_formationplateforme.Id_FormationEquivalente=form_formationequivalente.Id 
								WHERE form_formationequivalente.Id_Plateforme=".$_POST['Id_Plateforme']." 
								AND form_formationequivalente_formationplateforme.Id_Formation=".$_POST['formation_'.$valeur]."
								AND form_formationequivalente_formationplateforme.Recyclage=".$_POST['recyclage_'.$valeur].")";
					$resultSimil=mysqli_query($bdd,$reqSimil);
					$nbSimil=mysqli_num_rows($resultSimil);
					$formSimil="";
					if($nbSimil>0){
						while($rowSimil=mysqli_fetch_array($resultSimil)){
							$formSimil.=" OR Id_Formation=".$rowSimil['Id_Formation'];
						}
					}
					if($_POST['recyclage_'.$valeur]==0){$Motif="Motif<>'Renouvellement'";}
					else{{$Motif="Motif='Renouvellement'";}}
					
					$reqEmail="SELECT DISTINCT new_rh_etatcivil.EmailPro 
							FROM new_competences_personne_poste_prestation 
							LEFT JOIN new_rh_etatcivil 
							ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
							WHERE new_competences_personne_poste_prestation.Id_Poste IN (".implode(",",$TableauIdPostesCHE_COOE).") 
							AND new_competences_personne_poste_prestation.Id_Prestation IN (
								SELECT DISTINCT Id_Prestation
								FROM form_besoin
								WHERE Traite=0
								AND Valide=1
								AND Suppr=0
								AND (Id_Formation=".$valeur.$formSimil.")
								AND ".$Motif."
							)
							AND new_competences_personne_poste_prestation.Id_Prestation IN (";

					$req="SELECT Id FROM new_competences_prestation ";
					$req.="WHERE Id_Plateforme=".$_POST['Id_Plateforme']." ORDER BY Libelle ASC";
					$resultPresta=mysqli_query($bdd,$req);
					while($rowPresta=mysqli_fetch_array($resultPresta))
					{
						if(isset($_POST['Presta_'.$rowPresta['Id']])){
							$requete="INSERT INTO form_session_prestation (Id_Session,Id_Prestation) VALUES (".$valeur.",".$rowPresta['Id'].")";
							$resultA=mysqli_query($bdd,$requete);
							$reqEmail.=$rowPresta['Id'].",";
						}
					}
				 }
			}
		}
	}
	elseif(isset($_POST['annuler'])){
		//Parcours des différentes formations du groupe de formation
		$tab = explode(";",$_POST['IdChampsSessions']);
		foreach($tab as $valeur){
			echo $valeur.",";
			if($valeur<>""){
				annulationSession($valeur,$IdPersonneConnectee,$_POST['Id_Plateforme']);
			}
		}
	}
	elseif(isset($_POST['supprimer'])){
		//Parcours des différentes formations du groupe de formation
		$tab = explode(";",$_POST['IdChampsSessions']);
		foreach($tab as $valeur){
			echo $valeur.",";
			if($valeur<>""){
				suppressionSession($valeur,$IdPersonneConnectee,$_POST['Id_Plateforme']);
			}
		}
	}
	echo "<script>FermerEtRecharger(\"".$_POST['getPlanning']."\")</script>";
}
elseif($_GET)
{
	//Mode modification
	$ModifAssistantFor=0;
	$modifiable="readonly";
	$disabled="disabled";
	$typeDate="text";
	if(DroitsFormationPlateforme($TableauIdPostesAF_RF)){
		$ModifAssistantFor=1;
		$modifiable="";
		$disabled="";
		$typeDate="date";
	}
	$Modif=false;
	if($_GET['Mode']=="M"){
		if($_GET['Id']!='0'){
			//Groupe de formation
			$req="SELECT (SELECT Libelle FROM form_groupe_formation WHERE Id=Id_GroupeFormation) AS Libelle, ";
			$req.="(SELECT Diffusion_Creneau FROM form_session WHERE Id_GroupeSession=".$_GET['Id']." LIMIT 1) AS Diffusion_Creneau, ";
			$req.="(SELECT Nb_Stagiaire_Mini FROM form_session WHERE Id_GroupeSession=".$_GET['Id']." LIMIT 1) AS Nb_Stagiaire_Mini, ";
			$req.="(SELECT Nb_Stagiaire_Maxi FROM form_session WHERE Id_GroupeSession=".$_GET['Id']." LIMIT 1) AS Nb_Stagiaire_Maxi ";
			$req.="FROM form_session_groupe ";
			$req.="WHERE Id=".$_GET['Id']." ";
			$result=mysqli_query($bdd,$req);
			$LigneGroupe=mysqli_fetch_array($result);
			
			$req="SELECT form_session.Id, form_session.Id_Formation,form_session.Id_Lieu,form_session.Id_Formateur, Formation_Liee, ";
			$req.="form_session.Diffusion_Creneau, form_session.Recyclage,form_session.Nb_Stagiaire_Mini,form_session.Nb_Stagiaire_Maxi,MessageConvocation,MessageInscription ";
			$req.="FROM form_session ";
			$req.="WHERE form_session.Id_GroupeSession=".$_GET['Id'];
			$resultForm=mysqli_query($bdd,$req);
			$nbFormation=mysqli_num_rows($resultForm);
			
			//LIEUX
			$resultLieu=mysqli_query($bdd,"SELECT Id, Libelle FROM form_lieu WHERE Id_Plateforme=".$_GET['Id_Plateforme']." AND Suppr=0 ORDER BY Libelle ASC");
			$nbLieux=mysqli_num_rows($resultLieu);
			
			//FORMATEURS
			$req="SELECT DISTINCT Id, CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil ";
			$req.="WHERE Id IN (SELECT Id_Personne FROM new_competences_personne_poste_plateforme WHERE Id_Poste=21 AND Id_Plateforme=".$_GET['Id_Plateforme'].") ORDER BY Personne ASC";
			$resultFormateur=mysqli_query($bdd,$req);
			$nbFormateurs=mysqli_num_rows($resultFormateur);

		}
?>
		<form id="formulaire" method="POST" action="Modif_SessionGroupe.php" onSubmit="return VerifChampsModeModif();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php echo $_GET['Id'];?>">
		<input type="hidden" name="OldDiffusion" value="<?php echo $LigneGroupe['Diffusion_Creneau']; ?>">
		<input type="hidden" name="Id_Plateforme" value="<?php echo $_GET['Id_Plateforme'];?>">
		<input type="hidden" name="getPlanning" id="getPlanning" value="<?php echo "Id_Plateforme=".$_GET['Id_Plateforme']."&DateDeDebut=".$_GET['date']."&formateur=".$_GET['formateur']."&lieu=".$_GET['lieu']."&horaires=".$_GET['horaires']."&formation=".$_GET['formation']."&typeAffichage=".$_GET['typeAffichage']."&etatAffichage=".$_GET['etatAffichage'];?>">
		<table style="width:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle" width="15%">&nbsp;<?php if($LangueAffichage=="FR"){echo "Groupe de formation";}else{echo "Training group";}?> : </td>
				<td width="20%">
					&nbsp;<?php echo $LigneGroupe['Libelle']; ?>
				</td>
				<td class="Libelle" width="15%"><?php if($LangueAffichage=="FR"){echo "Formations liées";}else{echo "Related trainings";}?> : </td>
				<td width="15%">
					<select name="formationsLiees" <?php echo $disabled; ?> id="formationsLiees" onchange="AfficherFormationNonLiees()">
						<?php
						$Tableau=array('Oui|1','Non|0');
						foreach($Tableau as $indice => $valeur)
						{
							$valeur=explode("|",$valeur);
							echo "<option value='".$valeur[1]."' ";
							if($valeur[1]==1){echo "selected ";}
							echo ">".$valeur[0]."</option>\n";
						}
						?>
					</select>
				</td>
				<td width="15%"></td>
				<td width="15%"></td>
			</tr>
			<tr id="displayLiee">
				<td style='font-weight:bold;'><?php if($LangueAffichage=="FR"){echo "Diffuser";}else{echo "Spread";} ?> : </td><td align='left'>
					<select name="diffuser" id="diffuser" <?php echo $disabled; ?>>
						<option value='0' <?php if($LigneGroupe['Diffusion_Creneau']==0){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";} ?></option>
						<option value='1' <?php if($LigneGroupe['Diffusion_Creneau']==1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";} ?></option>
					</select>
				</td>
				<td style='font-weight:bold;'><?php if($LangueAffichage=="FR"){echo "Nb stagiaires mini";}else{echo "Number of trainees minimum";}?> : </td>
				<td align='left'>
					<input onKeyUp='nombre(this)' <?php echo $modifiable; ?> name='stagiaireMin' id='stagiaireMin' style='width:40px;' type='text' value='<?php echo $LigneGroupe['Nb_Stagiaire_Mini']; ?>'>
				</td>
				<td style='font-weight:bold;'><?php if($LangueAffichage=="FR"){echo "Nb stagiaires max";}else{echo "Number of trainees maximum";}?> : </td>
				<td align='left'>
					<input onKeyUp='nombre(this)' name='stagiaireMax' <?php echo $modifiable; ?> id='stagiaireMax' style='width:40px;' type='text' value='<?php echo $LigneGroupe['Nb_Stagiaire_Maxi']; ?>'>
				</td>
			</tr>
			<tr><td colspan='6' style='border-bottom:1px dotted #1a23f0'></td></tr>
			<?php
				$IdSession=0;
				$Id_Sessions="";
				$bSessionEC=0;
				if($nbFormation>0){
					while($rowSession=mysqli_fetch_array($resultForm)){
						$IdSession=$rowSession['Id'];
						$Id_Sessions.=";".$rowSession['Id'];
						//Information sur la formation
						$req="SELECT form_formation.Id,Duree,DureeRecyclage,NbJour,NbJourRecyclage, ";
						$req.="(SELECT Libelle FROM form_typeformation WHERE Id=form_formation.Id_TypeFormation) AS TypeFormation, ";
						$req.="form_formation.Id_TypeFormation,(SELECT Libelle FROM form_formation_langue_infos ";
							$req.="WHERE form_formation_langue_infos.Id_Langue=form_formation_plateforme_parametres.Id_Langue ";
							$req.="AND Id_Formation=form_formation.Id AND Suppr=0) AS Libelle, ";
						$req.="(SELECT LibelleRecyclage FROM form_formation_langue_infos ";
							$req.="WHERE form_formation_langue_infos.Id_Langue=form_formation_plateforme_parametres.Id_Langue ";
							$req.="AND Id_Formation=form_formation.Id AND Suppr=0) AS LibelleRecyclage ";
						$req.="FROM form_formation_plateforme_parametres LEFT JOIN form_formation ";
						$req.="ON form_formation_plateforme_parametres.Id_Formation=form_formation.Id ";
						$req.="WHERE form_formation_plateforme_parametres.Id_Plateforme=".$_GET['Id_Plateforme']." ";
						$req.="AND form_formation.Id=".$rowSession['Id_Formation']." AND form_formation_plateforme_parametres.Suppr=0 AND form_formation.Suppr=0 ";
						$resultlaFormation=mysqli_query($bdd,$req);
						$LignelaFormation=mysqli_fetch_array($resultlaFormation);
						
						$diffNon="";
						$diffOui="";
						if($LigneGroupe['Diffusion_Creneau']==0){$diffNon="selected";}
						else{$diffOui="selected";}
						echo "<tr class='formsNonLiees' style='display:none;'>";
						echo "<td style='font-weight:bold;'>Diffuser : </td><td align='left'>";
						echo "<select name=\"diffuser_".$rowSession['Id']."\" id=\"diffuser_".$rowSession['Id']."\" ".$disabled.">";
						echo "<option value='0' ".$diffNon.">Non</option>";
						echo "<option value='1' ".$diffOui.">Oui</option>";
						echo "</select>";
						echo "</td>";
						echo "</tr>";
						
						//Date des sessions
						$req="SELECT Id,DateSession,Heure_Debut,Heure_Fin,PauseRepas,HeureDebutPause,HeureFinPause ";
						$req.="FROM form_session_date ";
						$req.="WHERE form_session_date.Id_Session=".$rowSession['Id']." ORDER BY DateSession";
						$resultDate=mysqli_query($bdd,$req);
						$nbDates=mysqli_num_rows($resultDate);
						
						//Session E/C
						$req="SELECT Id ";
						$req.="FROM form_session_date ";
						$req.="WHERE form_session_date.Id_Session=".$rowSession['Id']." AND DateSession>='".date('Y-m-d')."' ";
						$resultDateEC=mysqli_query($bdd,$req);
						$nbDatesEC=mysqli_num_rows($resultDateEC);
						if($nbDatesEC>0){$bSessionEC=1;}
						
						echo "<tr><td style='font-weight:bold;' width='10%'>Type : </td><td width='15%' align='left'>".$LignelaFormation['TypeFormation']."</td>";
						echo "</td></tr>";
						
						if($rowSession['Recyclage']==0){
							$tabDuree=explode(".",$LignelaFormation['Duree']);
							if($LangueAffichage=="FR"){
								echo "<tr><td width='15%' style='font-weight:bold;' valign='top'>Initiale / Recyclage : </td><td valign='top' width='10%' align='left'>Initiale</td>";
								echo "<td width='10%' style='font-weight:bold;' valign='top'>Formation : </td><td width='40%' valign='top' align='left' colspan='4'>".$LignelaFormation['Libelle']."<br/>";
							}
							else{
								echo "<tr><td width='15%' style='font-weight:bold;' valign='top'>Initial / Recycling : </td><td valign='top' width='10%' align='left'>Initial</td>";
								echo "<td width='10%' style='font-weight:bold;' valign='top'>Training : </td><td width='40%' valign='top' align='left' colspan='4'>".$LignelaFormation['Libelle']."<br/>";
							}
							echo "<div id='compteur_".$rowSession['Id']."' style='display: inline'>";
							if($nbDates>1){echo "Nbr d'heures : ".$tabDuree[0].":".$tabDuree[1];}
							echo "</div></td></tr>";
						}
						else{
							$tabDuree=explode(".",$LignelaFormation['DureeRecyclage']);
							if($LangueAffichage=="FR"){
								echo "<tr><td width='15%' style='font-weight:bold;' valign='top'>Initiale / Recyclage : </td><td valign='top' width='10%' align='left'>Recyclage</td>";
								echo "<td width='10%' style='font-weight:bold;' valign='top'>Formation : </td><td width='40%' valign='top' align='left' colspan='4'>".$LignelaFormation['LibelleRecyclage']."<br/>";
							}
							else{
								echo "<tr><td width='15%' style='font-weight:bold;' valign='top'>Initial / Recycling : </td><td valign='top' width='10%' align='left'>Recycling</td>";
								echo "<td width='10%' style='font-weight:bold;' valign='top'>Training : </td><td width='40%' valign='top' align='left' colspan='4'>".$LignelaFormation['LibelleRecyclage']."<br/>";
							}
							echo "<div id='compteur_".$rowSession['Id']."' style='display: inline'>";
							if($LangueAffichage=="FR"){
								if($nbDates>1){echo "Nbr d'heures : ".$tabDuree[0].":".$tabDuree[1];}
							}
							else{
								if($nbDates>1){echo "Number of hours : ".$tabDuree[0].":".$tabDuree[1];}
							}
							echo "</div></td></tr>";
						}
						echo "<input type='hidden' id='formation_".$rowSession['Id']."' name='formation_".$rowSession['Id']."' value='".$rowSession['Id_Formation']."'>";
						echo "<input type='hidden' id='recyclage_".$rowSession['Id']."' name='recyclage_".$rowSession['Id']."' value='".$rowSession['Recyclage']."'>";
						//Configurer ces données
						echo "<tr style='display:none;'><td colspan='6'>";
						echo "<input type='hidden' id='heurePlus_".$rowSession['Id']."' name='heurePlus_".$rowSession['Id']."' value='".$tabDuree[0]."'>";
						echo "<input type='hidden' id='minPlus_".$rowSession['Id']."' name='minPlus_".$rowSession['Id']."' value='".$tabDuree[1]."'>";
						echo "<input type='hidden' id='nbJours_".$rowSession['Id']."' name='nbJours_".$rowSession['Id']."' value='".$nbDates."'>";
						echo "<input type='hidden' id='heuresRestantes_".$rowSession['Id']."' name='heuresRestantes_".$rowSession['Id']."' value='0'>";
						echo "<input type='hidden' id='minRestantes_".$rowSession['Id']."' name='minRestantes_".$rowSession['Id']."' value='0'>";
						echo "<input type='hidden' id='recyclage_".$rowSession['Id']."' name='recyclage_".$rowSession['Id']."' value='".$rowSession['Recyclage']."'>";
						echo "</tr>";
						if($nbDates>0){
							$compteurDate=1;
							while($rowDate=mysqli_fetch_array($resultDate)){
								echo "<tr>";
								echo "<td class=\"Libelle\" width=\"15%\">Date : </td>\n";
								
								echo "<td width=\"20%\"><input type=\"".$typeDate."\" ".$modifiable." onmousedown=\"datepick();\" name=\"dateDebut_".$rowSession['Id']."_".$compteurDate."\" id=\"dateDebut_".$rowSession['Id']."_".$compteurDate."\" size=\"10\" value=\"".AfficheDateFR($rowDate['DateSession'])."\"></td>\n";
								if($LangueAffichage=="FR"){echo "<td class=\"Libelle\" width=\"15%\">Heure de début</td>\n";}
								else{echo "<td class=\"Libelle\" width=\"15%\">Start time</td>\n";}
								echo "<td width=\"15%\">\n";
								$onchange2="";
								if($nbDates==1){$onchange2="onchange=\"ModifierHeureFinModeModif(".$compteurDate.",".$rowSession['Id'].",".$rowSession['Recyclage'].")\"";}
								else{$onchange2="onchange=\"calculNbHeuresRestantes('D',".$compteurDate.",".$rowSession['Id'].",".$rowSession['Recyclage'].")\"";}
								echo "<select name=\"heureDebut_".$rowSession['Id']."_".$compteurDate."\" id=\"heureDebut_".$rowSession['Id']."_".$compteurDate."\" ".$onchange2." ".$disabled." >\n";
								echo "<option value=\"0\"></option>\n";
								$heure=7;
								$min=0;
								for($h=1;$h<=53;$h++){
									if($min==0){$minAffiche="0";}
									else{$minAffiche=$min;}
									$selected="";
									if($rowDate['Heure_Debut']==sprintf('%02d', $heure).":".sprintf('%02d', $min).":00"){$selected="selected";}
									echo "<option value='".sprintf('%02d',$heure).":".sprintf('%02d',$min)."' ".$selected.">".sprintf('%02d',$heure)."h".sprintf('%02d',$minAffiche)."</option>\n";
									if($min==0){$min=15;}
									else if($min==15){$min=30;}
									else if($min==30){$min=45;}
									else{$min=0;$heure++;}
								}
								echo "</select>\n";
								echo "</td>\n";
								
								if($LangueAffichage=="FR"){echo "<td class=\"Libelle\" width=\"10%\">Heure de fin</td>";}
								else{echo "<td class=\"Libelle\" width=\"10%\">End time</td>";}
								echo "<td width=\"15%\">";
								if($nbDates==1){
									$heureDeFin="";
									if($rowDate['Heure_Fin']<>"00:00:00"){$heureDeFin=substr($rowDate['Heure_Fin'],0,-3);}
									echo "<input name=\"heureFin_".$rowSession['Id']."_".$compteurDate."\" id=\"heureFin_".$rowSession['Id']."_".$compteurDate."\" size=\"10\" type=\"text\" value=\"".$heureDeFin."\" readonly=\"readonly\">";
								}
								else{
									echo "<select name=\"heureFin_".$rowSession['Id']."_".$compteurDate."\" id=\"heureFin_".$rowSession['Id']."_".$compteurDate."\" onchange=\"calculNbHeuresRestantes('F',".$compteurDate.",".$rowSession['Id'].")\" ".$disabled.">";
									echo "<option value=\"0\"></option>";
									$heure=7;
									$min=0;
									for($h=1;$h<=53;$h++){
										if($min==0){$minAffiche="0";}
										else{$minAffiche=$min;}
										$selected="";
										if($rowDate['Heure_Fin']==sprintf('%02d', $heure).":".sprintf('%02d', $min).":00"){$selected="selected";}
										echo "<option value='".sprintf('%02d',$heure).":".sprintf('%02d',$min)."' ".$selected.">".sprintf('%02d',$heure)."h".sprintf('%02d',$minAffiche)."</option>\n";
										if($min==0){$min=15;}
										else if($min==15){$min=30;}
										else if($min==30){$min=45;}
										else{$min=0;$heure++;}
									}
									echo "</select>";
								}
								echo "</td>";
								echo "</tr>";
								echo "<tr class=\"TitreColsUsers\">";
								echo "<td></td>";
								echo "<td></td>";
								if($LangueAffichage=="FR"){echo "<td  class=\"Libelle\">Pause repas : </td>";}
								else{echo "<td  class=\"Libelle\">Lunch break : </td>";}
								echo "<td>";
								echo "<select id=\"pauseRepas_".$rowSession['Id']."_".$compteurDate."\" name=\"pauseRepas_".$rowSession['Id']."_".$compteurDate."\" onchange=\"VerifHeuresPause(".$nbDates.",'D',".$compteurDate.",".$rowSession['Id'].",".$rowSession['Recyclage'].",1)\" ".$disabled." >";
								foreach($Tableau as $indice => $valeur){
									$valeur=explode("|",$valeur);
									echo "<option value='".$valeur[1]."' ";
										if($rowDate['PauseRepas']==$valeur[1]){echo "selected";}
									echo ">".$valeur[0]."</option>\n";
								}
								
								echo "</select>";
								echo "</td>";
								if($LangueAffichage=="FR"){echo "<td id=\"td_heurepause".$compteurDate."\" class=\"Libelle\" width=\"10%\">De&nbsp;</td>";}
								else{echo "<td id=\"td_heurepause".$compteurDate."\" class=\"Libelle\" width=\"10%\">From&nbsp;</td>";}
								echo "<td id=\"td_heurepause2".$compteurDate."\" class=\"Libelle\" width=\"15%\">";
								echo "<select name=\"heureDebutPause_".$rowSession['Id']."_".$compteurDate."\" id=\"heureDebutPause_".$rowSession['Id']."_".$compteurDate."\" onchange=\"VerifHeuresPause(".$nbDates.",'D',".$compteurDate.",".$rowSession['Id'].",".$rowSession['Recyclage'].",1)\" ".$disabled.">";
								$heure=7;
								$min=0;
								for($h=1;$h<=53;$h++){
									if($min==0){$minAffiche="0";}
									else{$minAffiche=$min;}
									$selected="";
									if($rowDate['HeureDebutPause']==sprintf('%02d', $heure).":".sprintf('%02d', $min).":00"){$selected="selected";}
									echo "<option value='".sprintf('%02d',$heure).":".sprintf('%02d',$min)."' ".$selected.">".sprintf('%02d',$heure)."h".sprintf('%02d',$minAffiche)."</option>\n";
									if($min==0){$min=15;}
									else if($min==15){$min=30;}
									else if($min==30){$min=45;}
									else{$min=0;$heure++;}
								}
								echo "</select>";
								if($LangueAffichage=="FR"){echo "&nbsp;à&nbsp;";}
								else{echo "&nbsp;to&nbsp;";}
								echo "<select name=\"heureFinPause_".$rowSession['Id']."_".$compteurDate."\" id=\"heureFinPause_".$rowSession['Id']."_".$compteurDate."\" onchange=\"VerifHeuresPause(".$nbDates.",'D',".$compteurDate.",".$rowSession['Id'].",".$rowSession['Recyclage'].",1)\" ".$disabled.">";
								$heure=7;
								$min=0;
								for($h=1;$h<=53;$h++){
									if($min==0){$minAffiche="0";}
									else{$minAffiche=$min;}
									$selected="";
									if($rowDate['HeureFinPause']==sprintf('%02d', $heure).":".sprintf('%02d', $min).":00"){$selected="selected";}
									echo "<option value='".sprintf('%02d',$heure).":".sprintf('%02d',$min)."' ".$selected.">".sprintf('%02d',$heure)."h".sprintf('%02d',$minAffiche)."</option>\n";
									if($min==0){$min=15;}
									else if($min==15){$min=30;}
									else if($min==30){$min=45;}
									else{$min=0;$heure++;}
								}
								echo "</select>";
								echo "</td>";
								echo "</tr>";
								$compteurDate++;
							}
						}
			
						//Lieux et formateurs
						echo "<tr>";
						if($LangueAffichage=="FR"){echo "<td  class='Libelle'>Lieu : </td>";}
						else{echo "<td  class='Libelle'>Place : </td>";}
						echo "<td>";
						echo "<select name='lieu_".$rowSession['Id']."' id='lieu_".$rowSession['Id']."'>";
						echo "<option value='0'></option>";
						if($nbLieux>0){
							mysqli_data_seek($resultLieu,0);
							while($rowLieu=mysqli_fetch_array($resultLieu)){
								$selected="";
								if($rowSession['Id_Lieu']==$rowLieu['Id']){$selected="selected";}
								echo "<option value='".$rowLieu['Id']."' ".$selected.">".$rowLieu['Libelle']."</option>\n";
							}
						}
						echo "</select>";
						echo "</td>";
						if($LangueAffichage=="FR"){echo "<td  class='Libelle'>Formateur : </td>";}
						else{echo "<td  class='Libelle'>Former : </td>";}
						echo "<td>";
						echo "<select name='formateur_".$rowSession['Id']."' id='formateur_".$rowSession['Id']."' ".$disabled.">";
						echo "<option value='0'></option>";
						if($nbFormateurs>0){
							mysqli_data_seek($resultFormateur,0);
							while($rowFormateur=mysqli_fetch_array($resultFormateur)){
								$selected="";
								if($rowSession['Id_Formateur']==$rowFormateur['Id']){$selected="selected";}
								echo "<option value='".$rowFormateur['Id']."' ".$selected.">".$rowFormateur['Personne']."</option>\n";
							}
						}
						echo "</select>";
						echo "</td>";
						echo "</tr>";
						
						echo "<tr>";
						if($LangueAffichage=="FR"){echo "<td  class='Libelle' colspan='6'>Message à l'attention des stagiaires (convocation) : </td>";}
						else{echo "<td  class='Libelle' colspan='6'>Message for trainees (convocation) : </td>";}
						echo "</tr>";
						echo "<tr>";
						echo "<td  colspan='6'>";
						echo "<textarea name='message_".$rowSession['Id']."' rows='3' cols='140' style='resize:none' ".$modifiable.">".stripslashes($rowSession['MessageConvocation'])."</textarea>";
						echo "</td>";
						echo "</tr>";
						
						echo "<tr>";
						if($LangueAffichage=="FR"){echo "<td  class='Libelle' colspan='6'>Message lors des inscriptions : </td>";}
						else{echo "<td  class='Libelle' colspan='6'>Registration message : </td>";}
						echo "</tr>";
						echo "<tr>";
						echo "<td  colspan='6'>";
						echo "<textarea name='messageInscription_".$rowSession['Id']."' rows='3' cols='140' style='resize:none' ".$modifiable.">".stripslashes($rowSession['MessageInscription'])."</textarea>";
						echo "</td>";
						echo "</tr>";
						
						echo "<tr class='formsNonLiees' style='display:none;'>";
						echo "<td style='font-weight:bold;'>Nb stagiaires mini : </td>";
						echo "<td align='left'>";
						echo "<input onKeyUp='nombre(this)' name='stagiaireMin_".$rowSession['Id']."' ".$modifiable." id='stagiaireMin_".$rowSession['Id']."' style='width:40px;' type='text' value='".$LigneGroupe['Nb_Stagiaire_Mini']."'>";
						echo "</td>";
						echo "<td style='font-weight:bold;'>Nb stagiaires maxi : </td>";
						echo "<td align='left'>";
						echo "<input onKeyUp='nombre(this)' name='stagiaireMax_".$rowSession['Id']."' ".$modifiable." id='stagiaireMax_".$rowSession['Id']."' style='width:40px;' type='text' value='".$LigneGroupe['Nb_Stagiaire_Maxi']."'>";
						echo "</td>";
						echo "</tr>";
						echo "<tr><td colspan='6' style='border-bottom:1px dotted #1a23f0'></td></tr>";
					}
				}
			?>
			<tr>
				<td colspan="6">
					<div id="div_Formations"></div>
				</td>
			</tr>
			<tr>
				<td colspan="6" class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Prestations";}else{echo "Activities";}?></td>
			</tr>
			<tr>
				<td colspan="6" style="height:200px;" bgcolor='#e1f1f5'>
					<div id="listePresta" style="height:200px;overflow:auto;">
					<div>
					<input type='checkbox' name="selectAll" id="selectAll" onclick="SelectionnerTout()" <?php echo $disabled; ?>>
					<?php if($LangueAffichage=="FR"){echo "Toutes";}else{echo "All";}?>
					</div>
			<?php
				if($_GET['Mode']=="M"){
					$req="SELECT Id_Prestation FROM form_session_prestation ";
					$req.="WHERE Id_Session=".$IdSession." ";
					$resultSessionPresta=mysqli_query($bdd,$req);
					$nbSessionPresta=mysqli_num_rows($resultSessionPresta);
				}
				
				$req="SELECT Id, Libelle FROM new_competences_prestation ";
				$req.="WHERE Id_Plateforme=".$_GET['Id_Plateforme']." ORDER BY Libelle ASC";
				$resultPresta=mysqli_query($bdd,$req);
				while($rowPresta=mysqli_fetch_array($resultPresta)){
					$checked="";
					if($_GET['Mode']=="M"){
						if($nbSessionPresta>0){
							mysqli_data_seek($resultSessionPresta,0);
							while($rowSessionPresta=mysqli_fetch_array($resultSessionPresta)){
								if($rowSessionPresta['Id_Prestation']==$rowPresta['Id']){$checked="checked";}
							}
						}
					}
					echo "<div>";
					echo "<input class='check' ".$checked." ".$disabled." type='checkbox' onclick='SelectionnerTout2()' id='Presta_".$rowPresta['Id']."' name='Presta_".$rowPresta['Id']."'>&nbsp;";
					echo stripslashes($rowPresta['Libelle']);
					echo "</div>\n";
				}
			?>
					</div>
				</td>
			</tr>
			<tr style="display:none;">
				<td>
					<input type="hidden" name="IdChampsSessions" id="IdChampsSessions" value="<?php echo $Id_Sessions; ?>" />
				</td>
			</tr>
			<tr>
				<td colspan="6" align="center">
			<?php
				if($ModifAssistantFor==1){
					if($bSessionEC>0){
			?>
					<input class="Bouton" name="sauvegarde" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Modifier";}else{echo "Modify";} ?>">
			<?php
					}
				}
				if($_GET['Mode']=="M" && $ModifAssistantFor==1){
			?>
					<input class="Bouton" name="annuler" type="submit" onclick=" return window.confirm('Etes-vous sûr de vouloir annuler ?')" value="<?php if($LangueAffichage=="FR"){echo "Annuler";}else{echo "Cancel";} ?>">

			<?php
					$reqInscrit="SELECT form_session_personne.Id 
					FROM form_session_personne 
					LEFT JOIN form_session
					ON form_session_personne.Id_Session=form_session.Id
					WHERE form_session_personne.Validation_Inscription IN (0,1) 
					AND form_session_personne.Suppr=0 
					AND form_session.Suppr=0 
					AND form_session.Id_GroupeSession=".$_GET['Id'];
				    $resultNbInscrit=mysqli_query($bdd,$reqInscrit);
				    $nbInscrit=mysqli_num_rows($resultNbInscrit);
					
					if($nbInscrit==0){
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class='Bouton' name='supprimer' type='submit' onclick=' return window.confirm(\"Etes-vous sûr de vouloir supprimer ?\")' value='";
						if($LangueAffichage=="FR"){echo "Supprimer";}
						else{echo "Delete";}
						echo "'>";
					}
				}
			?>	
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
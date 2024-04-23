<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Ajouter un groupe de formation</title><meta name="robots" content="noindex">
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
	if($_POST['Mode']=="A"){
		//Création de la session du groupe
		$requete="INSERT INTO form_session_groupe (Id_GroupeFormation) VALUES (".$_POST['groupeFormation'].")";
		$resultA=mysqli_query($bdd,$requete);
		$IdGroupeSession = mysqli_insert_id($bdd);
		
		if($IdGroupeSession>0){
			//Parcours des différentes formations du groupe de formation
			$tab = explode(";",$_POST['Id_Formations']);
			foreach($tab as $valeur){
				 if($valeur<>""){
					//Ajout de la formation
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
					
					$requete="INSERT INTO form_session (Id_GroupeSession,Id_Formation,Id_Lieu,Id_Formateur,Formation_Liee,";
					$requete.="Nb_Stagiaire_Mini,Nb_Stagiaire_Maxi,Diffusion_Creneau,Recyclage,Id_Plateforme,Id_Personne_MAJ,Date_MAJ,MessageConvocation,MessageInscription) ";
					$requete.="VALUES (".$IdGroupeSession.",".$valeur.",".$_POST['lieu_'.$valeur].",".$_POST['formateur_'.$valeur].",".$_POST['formationsLiees'].",";
					$requete.="".$stagMin.",".$stagMax.",".$diffuser.",".$_POST['recyclage_'.$valeur].",".$_POST['Id_Plateforme'].",".$IdPersonneConnectee.",'".date('Y-m-d')."','".addslashes($_POST['message_'.$valeur])."','".addslashes($_POST['messageInscription_'.$valeur])."')";
					$resultA=mysqli_query($bdd,$requete);
					$IdSession = mysqli_insert_id($bdd);
					
					if($IdSession>0){
						//Création des dates
						$dates="";
						for($i=1;$i<=$_POST['nbJours_'.$valeur];$i++){
							$requete="INSERT INTO form_session_date (Id_Session,DateSession,Heure_Debut,Heure_Fin,PauseRepas,HeureDebutPause,HeureFinPause) ";
							$requete.="VALUES (".$IdSession.",'".TrsfDate_($_POST['dateDebut_'.$valeur.'_'.$i])."','".$_POST['heureDebut_'.$valeur.'_'.$i]."','".$_POST['heureFin_'.$valeur.'_'.$i]."',";
							$requete.="".$_POST['pauseRepas_'.$valeur.'_'.$i].",'".$_POST['heureDebutPause_'.$valeur.'_'.$i]."','".$_POST['heureFinPause_'.$valeur.'_'.$i]."')";
							$resultA=mysqli_query($bdd,$requete);
							$dates.=AfficheDateJJ_MM_AAAA(TrsfDate_($_POST['dateDebut_'.$valeur.'_'.$i]))."<br>";
						}
						
						$reqSimil="SELECT Id_Formation 
							FROM form_formationequivalente_formationplateforme 
							WHERE Id_FormationEquivalente IN (SELECT Id_FormationEquivalente 
							FROM form_formationequivalente_formationplateforme 
							LEFT JOIN form_formationequivalente 
							ON form_formationequivalente_formationplateforme.Id_FormationEquivalente=form_formationequivalente.Id 
							WHERE form_formationequivalente.Id_Plateforme=".$_POST['Id_Plateforme']." 
							AND form_formationequivalente_formationplateforme.Id_Formation=".$valeur."
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
								$requete="INSERT INTO form_session_prestation (Id_Session,Id_Prestation) VALUES (".$IdSession.",".$rowPresta['Id'].")";
								$resultA=mysqli_query($bdd,$requete);
								$reqEmail.=$rowPresta['Id'].",";
							}
						}
					}
				 }
			}
		}
	}
	echo "<script>FermerEtRecharger(\"".$_POST['getPlanning']."\")</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
		if($_GET['Id']!='0'){
			$req="SELECT form_session.Id, form_session.Id_Formation,form_session.Id_Lieu,form_session.Id_Formateur, ";
			$req.="form_session.Diffusion_Creneau, form_session.Recyclage,form_session.Nb_Stagiaire_Mini,form_session.Nb_Stagiaire_Maxi, ";
			$req.="(SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) AS Id_TypeFormation ";
			$req.="FROM form_session ";
			$req.="WHERE form_session.Id=".$_GET['Id'];
			$result=mysqli_query($bdd,$req);
			$Ligne=mysqli_fetch_array($result);
			
			$req="SELECT Id,DateSession,Heure_Debut,Heure_Fin,PauseRepas,HeureDebutPause,HeureFinPause ";
			$req.="FROM form_session_date ";
			$req.="WHERE form_session_date.Id_Session=".$_GET['Id']." ORDER BY form_session_date.DateSession ";
			$resultDate=mysqli_query($bdd,$req);
			$nbDates=mysqli_num_rows($resultDate);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_SessionGroupe.php" onSubmit="return VerifChamps();">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php echo $_GET['Id'];?>">
		<input type="hidden" name="Id_Formations" id="Id_Formations" value="">
		<input type="hidden" name="Id_Plateforme" value="<?php echo $_GET['Id_Plateforme'];?>">
		<input type="hidden" name="getPlanning" id="getPlanning" value="<?php echo "Id_Plateforme=".$_GET['Id_Plateforme']."&DateDeDebut=".$_GET['date']."&formateur=".$_GET['formateur']."&lieu=".$_GET['lieu']."&horaires=".$_GET['horaires']."&formation=".$_GET['formation']."&typeAffichage=".$_GET['typeAffichage']."&etatAffichage=".$_GET['etatAffichage'];?>">
		<table style="width:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle" width="15%">&nbsp;<?php if($LangueAffichage=="FR"){echo "Groupe de formation";}else{echo "Training group";}?> : </td>
				<td class="Libelle" width="20%">
					&nbsp;<select name="groupeFormation" id="groupeFormation" onchange="AfficherFormation('<?php echo $LangueAffichage; ?>');">
						<option value="0"></option>
						<?php
							$req="SELECT Id, Libelle FROM form_groupe_formation ";
							$req.="WHERE Id_Plateforme=".$_GET['Id_Plateforme']." ";
							$req.="AND Suppr=0 ORDER BY Libelle";
							$resultGroupeFormation=mysqli_query($bdd,$req);
							while($rowGF=mysqli_fetch_array($resultGroupeFormation)){
									echo "<option value='".$rowGF['Id']."'>".$rowGF['Libelle']."</option>";
							}
						?>
					</select>
				</td>
				<td class="Libelle" width="15%"><?php if($LangueAffichage=="FR"){echo "Formations liées";}else{echo "Related trainings";}?> : </td>
				<td width="15%">
					<select name="formationsLiees" id="formationsLiees" onchange="AfficherFormation('<?php echo $LangueAffichage; ?>')">
						<?php
						if($LangueAffichage=="FR"){
							$Tableau=array('Oui|1','Non|0');
						}
						else{
							$Tableau=array('Yes|1','No|0');
						}
						foreach($Tableau as $indice => $valeur)
						{
							$valeur=explode("|",$valeur);
							echo "<option value='".$valeur[1]."'";
							echo ">".$valeur[0]."</option>\n";
						}
						?>
					</select>
				</td>
				<td width="15%"></td>
				<td width="15%"></td>
			</tr>
			<?php
				//FORMATIONS DES GROUPES DE FORMATIONS
				$req="SELECT form_groupe_formation_formation.Id,form_groupe_formation_formation.Id_Groupe_Formation,";
				$req.="form_groupe_formation_formation.Id_Formation,form_groupe_formation_formation.Recyclage ";
				$req.="FROM form_groupe_formation_formation LEFT JOIN form_groupe_formation ";
				$req.="ON form_groupe_formation_formation.Id_Groupe_Formation= form_groupe_formation.Id ";
				$req.="WHERE form_groupe_formation.Id_Plateforme=".$_GET['Id_Plateforme']." ";
				$req.="AND form_groupe_formation.Suppr=0 AND form_groupe_formation_formation.Suppr=0 ";
				$resultGroupeFormation=mysqli_query($bdd,$req);
				$nbGroupe=mysqli_num_rows($resultGroupeFormation);
				$i=0;
				if($nbGroupe>0){
					while($rowGF=mysqli_fetch_array($resultGroupeFormation)){
						echo "<script>Liste_GroupeFormation[".$i."]= Array(\"".$rowGF['Id']."\",\"".$rowGF['Id_Groupe_Formation']."\",\"".$rowGF['Id_Formation']."\",\"".$rowGF['Recyclage']."\")</script>\n";
						$i++;
					}
				}
				//FORMATIONS + TYPE DE FORMATION + INITIALE / RECYCLAGE
				$req="SELECT form_formation.Id, form_formation.Recyclage,Duree,DureeRecyclage,NbJour,NbJourRecyclage, ";
				$req.="(SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS Organisme, ";
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
				$req.="AND form_formation_plateforme_parametres.Suppr=0 AND form_formation.Suppr=0 ";
				$req.="ORDER BY Libelle";
				$resultGroupeFormation=mysqli_query($bdd,$req);
				$nbFormation=mysqli_num_rows($resultGroupeFormation);
				$i=0;
				if($nbFormation>0){
					while($rowGF=mysqli_fetch_array($resultGroupeFormation)){
						$organisme="";
						if($rowGF['Organisme']<>""){$organisme=" (".stripslashes($rowGF['Organisme']).")";}
						echo "<script>Liste_Formation[".$i."]= Array(\"".$rowGF['Id']."\",\"".$rowGF['Id_TypeFormation']."\",\"".$rowGF['Duree']."\",\"".$rowGF['DureeRecyclage']."\",\"".str_replace('"','',stripslashes($rowGF['Libelle']).$organisme)."\",\"".str_replace('"','',stripslashes($rowGF['LibelleRecyclage']).$organisme)."\",\"".$rowGF['Recyclage']."\",\"".$rowGF['NbJour']."\",\"".$rowGF['NbJourRecyclage']."\",\"".stripslashes($rowGF['TypeFormation'])."\")</script>\n";
						$i++;
					}
				}
				//LIEUX
				$resultLieu=mysqli_query($bdd,"SELECT Id, Libelle FROM form_lieu WHERE Id_Plateforme=".$_GET['Id_Plateforme']." AND Suppr=0 ORDER BY Libelle ASC");
				$nbLieux=mysqli_num_rows($resultLieu);
				$i=0;
				if($nbLieux>0){
					while($rowLieu=mysqli_fetch_array($resultLieu)){
						echo "<script>Liste_Lieu[".$i."]= Array(\"".$rowLieu['Id']."\",\"".$rowLieu['Libelle']."\")</script>\n";
						$i++;
					}
				}
				//FORMATEURS
				$req="SELECT DISTINCT Id, CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil ";
				$req.="WHERE Id IN (SELECT Id_Personne FROM new_competences_personne_poste_plateforme WHERE Id_Poste=21 AND Id_Plateforme=".$_GET['Id_Plateforme'].") ORDER BY Personne ASC";
				$resultFormateur=mysqli_query($bdd,$req);
				$nbFormateurs=mysqli_num_rows($resultFormateur);
				$i=0;
				if($nbFormateurs>0){
					while($rowFormateur=mysqli_fetch_array($resultFormateur)){
						echo "<script>Liste_Formateur[".$i."]= Array(\"".$rowFormateur['Id']."\",\"".$rowFormateur['Personne']."\")</script>\n";
						$i++;
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
					<input type='checkbox' name="selectAll" id="selectAll" onclick="SelectionnerTout()">
					<?php if($LangueAffichage=="FR"){echo "Toutes";}else{echo "All";}?>
					</div>
			<?php
				if($_GET['Mode']=="M"){
					$req="SELECT Id_Prestation FROM form_session_prestation ";
					$req.="WHERE Id_Session=".$Ligne['Id']." ";
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
					echo "<input class='check' ".$checked." type='checkbox' onclick='SelectionnerTout2()' id='Presta_".$rowPresta['Id']."' name='Presta_".$rowPresta['Id']."'>&nbsp;";
					echo stripslashes($rowPresta['Libelle']);
					echo "</div>\n";
				}
			?>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="6" align="center">
					<input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="A"){if($LangueAffichage=="FR"){echo "Ajouter";}else{echo "Add";}}else{if($LangueAffichage=="FR"){echo "Modifier";}else{echo "Modify";}} ?>">
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
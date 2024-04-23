<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Ajouter une session</title><meta name="robots" content="noindex">
	<link href="../JS/styleCalendrier.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" charset="utf-8" src="Session.js"></script>
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
	if(isset($_POST['sauvegarde']))
	{
		if($_POST['Mode']=="A")
		{
			$stagMax=0;
			$stagMin=0;
			if($_POST['stagiaireMin']<>""){$stagMin=$_POST['stagiaireMin'];}
			if($_POST['stagiaireMax']<>""){$stagMax=$_POST['stagiaireMax'];}
			
			$requete="INSERT INTO form_session (Id_Formation,Id_Lieu,Id_Formateur,";
			$requete.="Nb_Stagiaire_Mini,Nb_Stagiaire_Maxi,Diffusion_Creneau,MultiPlateforme,InterIntra,TarifGroupe,Recyclage,Id_Plateforme,Id_Personne_MAJ,Date_MAJ,MessageConvocation,MessageInscription) ";
			$requete.="VALUES (".$_POST['formation'].",".$_POST['lieu'].",".$_POST['formateur'].",";
			$requete.="".$stagMin.",".$stagMax.",".$_POST['diffuser'].",".$_POST['multiplateforme'].",'".$_POST['interIntra']."',".$_POST['tarifGroupe'].",".$_POST['formationR'].",".$_POST['Id_Plateforme'].",".$IdPersonneConnectee.",'".date('Y-m-d')."','".addslashes($_POST['message'])."','".addslashes($_POST['messageInscription'])."')";
			$resultA=mysqli_query($bdd,$requete);
			$IdSession = mysqli_insert_id($bdd);
			
			if($IdSession>0)
			{
				//Création des dates
				$dates="";
				for($i=1;$i<=$_POST['nbJours'];$i++)
				{
					$requete="INSERT INTO form_session_date (Id_Session,DateSession,Heure_Debut,Heure_Fin,PauseRepas,HeureDebutPause,HeureFinPause) ";
					$requete.="VALUES (".$IdSession.",'".TrsfDate_($_POST['dateDebut'.$i])."','".$_POST['heureDebut'.$i]."','".$_POST['heureFin'.$i]."',";
					$requete.="".$_POST['pauseRepas'.$i].",'".$_POST['heureDebutPause'.$i]."','".$_POST['heureFinPause'.$i]."')";
					$resultA=mysqli_query($bdd,$requete);
					$dates.=AfficheDateJJ_MM_AAAA(TrsfDate_($_POST['dateDebut'.$i]))."<br>";
				}
				$req="SELECT Id FROM new_competences_prestation ";
				$req.="WHERE Id_Plateforme=".$_POST['Id_Plateforme']." ORDER BY Libelle ASC";
				$resultPresta=mysqli_query($bdd,$req);
				
				//Pour chaque formation vérifier si celle-ci n'a pas une formation équivalente
				$reqSimil="SELECT Id_Formation 
							FROM form_formationequivalente_formationplateforme 
							WHERE Id_FormationEquivalente IN (SELECT Id_FormationEquivalente 
							FROM form_formationequivalente_formationplateforme 
							LEFT JOIN form_formationequivalente 
							ON form_formationequivalente_formationplateforme.Id_FormationEquivalente=form_formationequivalente.Id 
							WHERE form_formationequivalente.Id_Plateforme=".$_POST['Id_Plateforme']." 
							AND form_formationequivalente_formationplateforme.Id_Formation=".$_POST['formation']."
							AND form_formationequivalente_formationplateforme.Recyclage=".$_POST['formationR'].")";
				$resultSimil=mysqli_query($bdd,$reqSimil);
				$nbSimil=mysqli_num_rows($resultSimil);
				$formSimil="";
				if($nbSimil>0)
				{
					while($rowSimil=mysqli_fetch_array($resultSimil))
					{
						$formSimil.=" OR Id_Formation=".$rowSimil['Id_Formation'];
					}
				}
				if($_POST['formationR']==0){$Motif="Motif<>'Renouvellement'";}
				else{{$Motif="Motif='Renouvellement'";}}
				
				//Liste des Coordinateurs d'équipes + chefs d'équipes 
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
								AND (Id_Formation=".$_POST['formation'].$formSimil.")
								AND ".$Motif."
							)
							AND new_competences_personne_poste_prestation.Id_Prestation IN (";
				while($rowPresta=mysqli_fetch_array($resultPresta))
				{
					if(isset($_POST['Presta_'.$rowPresta['Id']]))
					{
						$requete="INSERT INTO form_session_prestation (Id_Session,Id_Prestation) VALUES (".$IdSession.",".$rowPresta['Id'].")";
						$resultA=mysqli_query($bdd,$requete);
						$reqEmail.=$rowPresta['Id'].",";
					}
				}
			}
		}
		elseif($_POST['Mode']=="M")
		{
			//Vérifier si la convocation doit être renvoyée 
			$bConvocation=0;
			$req="SELECT Id_Lieu, MessageConvocation FROM form_session WHERE Id=".$_POST['Id'];
			$resultS=mysqli_query($bdd,$req);
			$rowS=mysqli_fetch_array($resultS);
			if($rowS['Id_Lieu']<>$_POST['lieu'] || stripslashes($rowS['MessageConvocation'])<>$_POST['message'])
			{
				//Convocation à renvoyer
				$bConvocation=1;
			}
			else
			{
				for($i=1;$i<=$_POST['nbJours'];$i++)
				{
					if($bConvocation==0)
					{
						$req="SELECT Id FROM 
							form_session_date 
							WHERE DateSession='".TrsfDate_($_POST['dateDebut'.$i])."' 
							AND Heure_Debut='".$_POST['heureDebut'.$i]."' 
							AND Heure_Fin='".$_POST['heureFin'.$i]."' ";
						$resultS=mysqli_query($bdd,$req);
						$nbS=mysqli_num_rows($resultS);
						if($nbS==0)
						{
							//Convocation à renvoyer
							$bConvocation=1;
						}
					}
				}
			}
			if($bConvocation==1){DeconvoquerPersonnes($_POST['Id']);}
			
			$stagMax=0;
			$stagMin=0;
			if($_POST['stagiaireMin']<>""){$stagMin=$_POST['stagiaireMin'];}
			if($_POST['stagiaireMax']<>""){$stagMax=$_POST['stagiaireMax'];}
			
			$requete="UPDATE form_session SET";
			$requete.=" Id_Lieu=".$_POST['lieu']."";
			$requete.=", Id_Formateur=".$_POST['formateur'];
			$requete.=", Nb_Stagiaire_Mini=".$stagMin;
			$requete.=", Nb_Stagiaire_Maxi=".$stagMax;
			$requete.=", Diffusion_Creneau=".$_POST['diffuser'];
			$requete.=", MultiPlateforme=".$_POST['multiplateforme'];
			$requete.=", InterIntra='".$_POST['interIntra']."'";
			$requete.=", TarifGroupe=".$_POST['tarifGroupe'];
			$requete.=", Id_Personne_MAJ=".$IdPersonneConnectee."";
			$requete.=", Date_MAJ='".date('Y-m-d')."' ";
			$requete.=", MessageConvocation='".addslashes($_POST['message'])."' ";
			$requete.=", MessageInscription='".addslashes($_POST['messageInscription'])."' ";
			$requete.=" WHERE Id=".$_POST['Id'];
			$resultM=mysqli_query($bdd,$requete);
			
			$req="DELETE FROM form_session_date WHERE Id_Session=".$_POST['Id'];
			$resultS=mysqli_query($bdd,$req);
			
			//Création des dates
			$dates="";
			for($i=1;$i<=$_POST['nbJours'];$i++)
			{
				$requete="INSERT INTO form_session_date (Id_Session,DateSession,Heure_Debut,Heure_Fin,PauseRepas,HeureDebutPause,HeureFinPause) ";
				$requete.="VALUES (".$_POST['Id'].",'".TrsfDate_($_POST['dateDebut'.$i])."','".$_POST['heureDebut'.$i]."','".$_POST['heureFin'.$i]."',";
				$requete.="".$_POST['pauseRepas'.$i].",'".$_POST['heureDebutPause'.$i]."','".$_POST['heureFinPause'.$i]."')";
				$resultA=mysqli_query($bdd,$requete);
				$dates.=$_POST['dateDebut'.$i]."<br>";
			}
			
			//Suppression des prestations
			$requete="DELETE FROM form_session_prestation WHERE Id_Session=".$_POST['Id']." ";
			$resultD=mysqli_query($bdd,$requete);
			
			//Pour chaque formation vérifier si celle-ci n'a pas une formation équivalente
			$reqSimil="
                SELECT Id_Formation 
				FROM form_formationequivalente_formationplateforme 
				WHERE Id_FormationEquivalente IN (SELECT Id_FormationEquivalente 
					FROM form_formationequivalente_formationplateforme 
					LEFT JOIN form_formationequivalente 
					ON form_formationequivalente_formationplateforme.Id_FormationEquivalente=form_formationequivalente.Id 
					WHERE form_formationequivalente.Id_Plateforme=".$_POST['Id_Plateforme']." 
					AND form_formationequivalente_formationplateforme.Id_Formation=".$_POST['OldId_Formation']."
					AND form_formationequivalente_formationplateforme.Recyclage=".$_POST['OldRecyclage'].")";
			$resultSimil=mysqli_query($bdd,$reqSimil);
			$nbSimil=mysqli_num_rows($resultSimil);
			$formSimil="";
			if($nbSimil>0)
			{
				while($rowSimil=mysqli_fetch_array($resultSimil))
				{
					$formSimil.=" OR Id_Formation=".$rowSimil['Id_Formation'];
				}
			}
			if($_POST['OldRecyclage']==0){$Motif="Motif<>'Renouvellement'";}
			else{{$Motif="Motif='Renouvellement'";}}
			//Ajout des prestations
			$reqEmail="
                SELECT DISTINCT new_rh_etatcivil.EmailPro 
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
					AND (Id_Formation=".$_POST['OldId_Formation'].$formSimil.")
					AND ".$Motif."
				)
				AND new_competences_personne_poste_prestation.Id_Prestation IN (";
							
			$req="SELECT Id FROM new_competences_prestation ";
			$req.="WHERE Id_Plateforme=".$_POST['Id_Plateforme']." ORDER BY Libelle ASC";
			$resultPresta=mysqli_query($bdd,$req);
			while($rowPresta=mysqli_fetch_array($resultPresta))
			{
				if(isset($_POST['Presta_'.$rowPresta['Id']]))
				{
					$requete="INSERT INTO form_session_prestation (Id_Session,Id_Prestation) VALUES (".$_POST['Id'].",".$rowPresta['Id'].")";
					$resultA=mysqli_query($bdd,$requete);
					$reqEmail.=$rowPresta['Id'].",";
				}
			}
		}
	}
	elseif(isset($_POST['annuler']))
	{
		annulationSession($_POST['Id'],$IdPersonneConnectee,$_POST['Id_Plateforme']);
	}
	elseif(isset($_POST['supprimer']))
	{
	    suppressionSession($_POST['Id']);
	}
	echo "<script>FermerEtRecharger(\"".$_POST['getPlanning']."\")</script>";
}
elseif($_GET)
{
	$ModifAssistantFor=0;
	$modifiable="readonly";
	$disabled="disabled";
	$typeDate="text";
	if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH) || DroitsFormationPlateforme(array($IdPosteFormateur)))
	{
		$ModifAssistantFor=1;
		$modifiable="";
		$disabled="";
		$typeDate="date";
	}
	if($_GET['Mode']=="M")
	{
		$disabled="disabled";
	}
	//Mode ajout ou modification
	$nbDatesEC=0;
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
		if($_GET['Id']!='0'){
			$req="
                SELECT
                    form_session.Id,
                    form_session.Id_Formation,
                    form_session.Id_Lieu,
                    form_session.Id_Formateur,
                    MessageConvocation,
                    form_session.Diffusion_Creneau,
					form_session.MultiPlateforme,
					form_session.InterIntra,
                    form_session.Recyclage,
                    form_session.Nb_Stagiaire_Mini,
                    form_session.Nb_Stagiaire_Maxi,
                    form_session.Annule,
					form_session.TarifGroupe,
					form_session.MessageInscription,
                    (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) AS Id_TypeFormation
                FROM
                    form_session
                WHERE
                    form_session.Id=".$_GET['Id'];
			$result=mysqli_query($bdd,$req);
			$Ligne=mysqli_fetch_array($result);
			
			$req="
                SELECT
                    Id,
                    DateSession,
                    Heure_Debut,
                    Heure_Fin,
                    PauseRepas,
                    HeureDebutPause,
                    HeureFinPause
                FROM
                    form_session_date
                WHERE
                    form_session_date.Id_Session=".$_GET['Id']."
                ORDER BY
                    form_session_date.DateSession" ;
			$resultDate=mysqli_query($bdd,$req);
			$nbDates=mysqli_num_rows($resultDate);
			
			//Session passée
			$req="
                SELECT
                    Id
                FROM
                    form_session_date
                WHERE
                    form_session_date.Id_Session=".$_GET['Id']."
                    AND DateSession>='".date('Y-m-d')."'" ;
			$resultDateEC=mysqli_query($bdd,$req);
			$nbDatesEC=mysqli_num_rows($resultDateEC);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Session.php" onSubmit="return VerifChamps();">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php echo $_GET['Id'];?>">
		<input type="hidden" name="OldDiffusion" value="<?php if( $_GET['Mode']=="M"){echo $Ligne['Diffusion_Creneau'];} ?>">
		<input type="hidden" name="OldTarifGroupe" value="<?php if( $_GET['Mode']=="M"){echo $Ligne['TarifGroupe'];} ?>">
		<input type="hidden" name="OldId_Formation" value="<?php if( $_GET['Mode']=="M"){echo $Ligne['Id_Formation'];} ?>">
		<input type="hidden" name="OldRecyclage" value="<?php if( $_GET['Mode']=="M"){echo $Ligne['Recyclage'];} ?>">
		<input type="hidden" name="heurePlus" id="heurePlus" value="0">
		<input type="hidden" name="minPlus" id="minPlus" value="0">
		<input type="hidden" name="heuresRestantes" id="heuresRestantes" value="0">
		<input type="hidden" name="minRestantes" id="minRestantes" value="0">
		<input type="hidden" name="nbJours" id="nbJours" value="<?php if( $_GET['Mode']=="M"){echo $nbDates;}else{echo "1";} ?>">
		<input type="hidden" name="Id_Plateforme" value="<?php echo $_GET['Id_Plateforme'];?>">
		<input type="hidden" name="getPlanning" id="getPlanning" value="<?php echo "Id_Plateforme=".$_GET['Id_Plateforme']."&DateDeDebut=".$_GET['date']."&formateur=".$_GET['formateur']."&lieu=".$_GET['lieu']."&horaires=".$_GET['horaires']."&formation=".$_GET['formation']."&typeAffichage=".$_GET['typeAffichage']."&etatAffichage=".$_GET['etatAffichage'];?>">
		<input type="hidden" name="ModifAssistantFor" value="<?php echo $ModifAssistantFor;?>">
		<table style="width:100%; align:center;" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Type de formation";}else{echo "Type of training";} ?> : </td>
				<td>
					&nbsp;<select name="Id_TypeFormation" <?php echo $disabled; ?> id="Id_TypeFormation" onchange="ModifierListeFormation('<?php echo $LangueAffichage; ?>')">
						<?php
						$resultTypeFormation=mysqli_query($bdd,"SELECT Id, Libelle FROM form_typeformation WHERE Suppr=0 ORDER BY Libelle ASC");
						while($rowTypeFormation=mysqli_fetch_array($resultTypeFormation))
						{
							echo "<option value='".$rowTypeFormation['Id']."'";
							if($_GET['Mode']=="M"){
								if($Ligne['Id_TypeFormation']==$rowTypeFormation['Id']){echo "selected";}
							}
							echo ">".stripslashes($rowTypeFormation['Libelle'])."</option>\n";
						}
						?>
					</select>
				</td>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Diffuser";}else{echo "Spread";} ?> : </td>
				<td>
					<select name="diffuser" id="diffuser">
						<?php
						if($LangueAffichage=="FR"){
							$Tableau=array('Non|0','Oui|1');
						}
						else{
							$Tableau=array('No|0','Yes|1');
						}
						foreach($Tableau as $indice => $valeur)
						{
							$valeur=explode("|",$valeur);
							echo "<option value='".$valeur[1]."' ";
							if($_GET['Mode']=="M"){
								if($Ligne['Diffusion_Creneau']==$valeur[1]){echo "selected";}
							}
							echo ">".$valeur[0]."</option>\n";
						}
						?>
					</select>
				</td>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Tarif groupe";}else{echo "Group rate";} ?> : </td>
				<td>
					<select name="tarifGroupe" id="tarifGroupe">
						<?php
						if($LangueAffichage=="FR"){
							$Tableau=array('Non|0','Oui|1');
						}
						else{
							$Tableau=array('No|0','Yes|1');
						}
						foreach($Tableau as $indice => $valeur)
						{
							$valeur=explode("|",$valeur);
							echo "<option value='".$valeur[1]."' ";
							if($_GET['Mode']=="M"){
								if($Ligne['TarifGroupe']==$valeur[1]){echo "selected";}
							}
							echo ">".$valeur[0]."</option>\n";
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Multi plateforme";}else{echo "Multi-platform";} ?> : </td>
				<td>
					<select name="multiplateforme" id="multiplateforme">
						<?php
						if($LangueAffichage=="FR"){
							$Tableau=array('Non|0','Oui|1');
						}
						else{
							$Tableau=array('No|0','Yes|1');
						}
						foreach($Tableau as $indice => $valeur)
						{
							$valeur=explode("|",$valeur);
							echo "<option value='".$valeur[1]."' ";
							if($_GET['Mode']=="M"){
								if($Ligne['MultiPlateforme']==$valeur[1]){echo "selected";}
							}
							echo ">".$valeur[0]."</option>\n";
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td  class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Initiale / Recyclage";}else{echo "Initial / Recycling";} ?> : </td>
				<td class="Libelle">
					&nbsp;<select name="formationR" <?php echo $disabled; ?> id="formationR" onchange="ModifierListeFormation('<?php echo $LangueAffichage; ?>')">
						<?php
						if($LangueAffichage=="FR"){
							$Tableau=array('Initiale|0','Recyclage|1');
						}
						else{
							$Tableau=array('Initial|0','Recycling|1');
						}
						foreach($Tableau as $indice => $valeur)
						{
							$valeur=explode("|",$valeur);
							echo "<option value='".$valeur[1]."' ";
							if($_GET['Mode']=="M"){
								if($Ligne['Recyclage']==$valeur[1]){echo "selected";}
							}
							echo ">".$valeur[0]."</option>\n";
						}
						?>
					</select>
				</td>
				<td  class="Libelle"><?php if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";} ?> : </td>
				<td class="Libelle" colspan="3">
					<div id="divFormation" style="display: inline">
						<select name="formation" id="formation" <?php echo $disabled; ?> style="width:250px;" onchange="ModifierDates('<?php echo $LangueAffichage; ?>')">
							<?php 
								if($_GET['Mode']=="A"){
									echo "<option value='0'></option>";
								}
								$duree="";
								$req="SELECT form_formation.Id, form_formation.Recyclage,Duree,DureeRecyclage,NbJour,NbJourRecyclage, ";
								$req.="(SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS Organisme, ";
								$req.="form_formation.Id_TypeFormation,(SELECT Libelle FROM form_formation_langue_infos ";
									$req.="WHERE form_formation_langue_infos.Id_Langue=form_formation_plateforme_parametres.Id_Langue ";
									$req.="AND Id_Formation=form_formation.Id AND Suppr=0) AS Libelle, ";
								$req.="(SELECT LibelleRecyclage FROM form_formation_langue_infos ";
									$req.="WHERE form_formation_langue_infos.Id_Langue=form_formation_plateforme_parametres.Id_Langue ";
									$req.="AND Id_Formation=form_formation.Id AND Suppr=0) AS LibelleRecyclage ";
								$req.="FROM form_formation_plateforme_parametres LEFT JOIN form_formation ";
								$req.="ON form_formation_plateforme_parametres.Id_Formation=form_formation.Id ";
								$req.="WHERE form_formation_plateforme_parametres.Id_Plateforme=".$_GET['Id_Plateforme']." ";
								$req.="AND form_formation_plateforme_parametres.Suppr=0 AND form_formation.Suppr=0 
										AND form_formation_plateforme_parametres.NbJour>0
										AND form_formation_plateforme_parametres.Duree>0 
										AND (form_formation.Recyclage=0 OR (form_formation.Recyclage=1
										AND form_formation_plateforme_parametres.NbJourRecyclage>0
										AND form_formation_plateforme_parametres.DureeRecyclage>0)) ";
								$req.="ORDER BY Libelle";
								
								$resultGroupeFormation=mysqli_query($bdd,$req);
								$i=0;
								$nb=0;
								while($rowGF=mysqli_fetch_array($resultGroupeFormation)){
									$organisme="";
									if($rowGF['Organisme']<>""){$organisme=" (".stripslashes($rowGF['Organisme']).")";}
									echo "<script>Liste_Formation[".$i."]= Array(\"".$rowGF['Id']."\",\"".$rowGF['Id_TypeFormation']."\",\"".$rowGF['Duree']."\",\"".$rowGF['DureeRecyclage']."\",\"".str_replace('"','',stripslashes($rowGF['Libelle']).$organisme)."\",\"".str_replace('"','',stripslashes($rowGF['LibelleRecyclage']).$organisme)."\",\"".$rowGF['Recyclage']."\",\"".$rowGF['NbJour']."\",\"".$rowGF['NbJourRecyclage']."\")</script>\n";
									$i++;
									if($_GET['Mode']=="M"){
										if($rowGF['Id_TypeFormation']==$Ligne['Id_TypeFormation']){
											if($Ligne['Recyclage']==0){
												echo "<option value='".$rowGF['Id']."' ";
												if($rowGF['Id']==$Ligne['Id_Formation']){echo "selected";$duree=str_replace(".",":",$rowGF['Duree']);}
												echo ">".stripslashes($rowGF['Libelle']).$organisme."</option>";
												$nb++;
											}
											else{
												if($rowGF['Recyclage']=="1"){
													echo "<option value='".$rowGF['Id']."' ";
													if($rowGF['Id']==$Ligne['Id_Formation']){echo "selected";$duree=str_replace(".",":",$rowGF['DureeRecyclage']);}
													echo ">".stripslashes($rowGF['LibelleRecyclage']).$organisme."</option>";
												}
												else{
													echo "<option value='".$rowGF['Id']."' ";
													if($rowGF['Id']==$Ligne['Id_Formation']){echo "selected";$duree=str_replace(".",":",$rowGF['Duree']);}
													echo ">".stripslashes($rowGF['Libelle']).$organisme."</option>";
												}
												$nb++;												
											}
										}
									}
								}
								if($nb==0){
									echo "<option value='0'></option>";
								}
							?>
						</select>
					</div>
					<div id="compteur" style="display: inline"></div>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td width="100%" colspan="6">
					<div id="divSession">
						<?php
							if($_GET['Mode']=="M"){
						?>
						<table style="width:100%;" id="tab_session" style="border:1px  dotted black;">
							<?php
								if($nbDates>0){
									$nb=1;
									while($rowDates=mysqli_fetch_array($resultDate)){
							?>
							<tr>
								<td class="Libelle" width="13%"><?php if($LangueAffichage=="FR"){echo "Date";}else{echo "Date";}?> : </td>
								<td width="17%"><input type="<?php echo $typeDate; ?>" <?php echo $modifiable; ?> name="dateDebut<?php echo $nb; ?>" id="dateDebut<?php echo $nb; ?>" size="10" value="<?php if($_GET['Mode']=="M"){echo AfficheDateFR($rowDates['DateSession']);} ?>"></td>
								<td class="Libelle" width="14%"><?php if($LangueAffichage=="FR"){echo "Heure de début";}else{echo "Start time";}?> : </td>
								<td width="15%">
									<?php
									if($nbDates==1){$onchange="onchange=\"ModifierHeureFin(".$nb.")\"";}
									else{$onchange="onchange=\"calculNbHeuresRestantes('D',".$nb.")\"";}
									?>
									<select name="heureDebut<?php echo $nb; ?>" id="heureDebut<?php echo $nb; ?>" <?php echo $onchange; ?>>
										<option value="0"></option>
										<?php
										$heure=5;
										$min=0;
										for($i=1;$i<=61;$i++){
											if($min==0){$minAffiche="";}
											else{$minAffiche=$min;}
											$selected="";
											if($rowDates['Heure_Debut']==sprintf('%02d', $heure).":".sprintf('%02d', $min).":00"){$selected="selected";}
											echo "<option value='".sprintf('%02d',$heure).":".sprintf('%02d',$min)."' ".$selected.">".sprintf('%02d',$heure)."h".sprintf('%02d',$minAffiche)."</option>";
											if($min==0){$min=15;}
											elseif($min==15){$min=30;}
											elseif($min==30){$min=45;}
											else{$min=0;$heure++;}
										}
										?>
									</select>
								</td>
								<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Heure de fin";}else{echo "End time";}?> : </td>
								<td width="15%">
									<?php if($nbDates==1){ ?>
										<input name="heureFin<?php echo $nb; ?>" <?php echo $modifiable; ?> id="heureFin<?php echo $nb; ?>" size="10" type="text" value="<?php if($rowDates['Heure_Fin']<>"00:00:00"){echo substr($rowDates['Heure_Fin'],0,-3);} ?>" readonly='readonly'>
									<?php }else{ ?>
										<select name="heureFin<?php echo $nb; ?>" id="heureFin<?php echo $nb; ?>" onchange="calculNbHeuresRestantes('F',<?php echo $nb; ?>)">
										<option value="0"></option>
										<?php
										$heure=5;
										$min=0;
										for($i=1;$i<=61;$i++){
											if($min==0){$minAffiche="0";}
											else{$minAffiche=$min;}
											$selected="";
											if($rowDates['Heure_Fin']==sprintf('%02d', $heure).":".sprintf('%02d', $min).":00"){$selected="selected";}
											echo "<option value='".sprintf('%02d',$heure).":".sprintf('%02d',$min)."' ".$selected.">".sprintf('%02d',$heure)."h".sprintf('%02d',$minAffiche)."</option>";
											if($min==0){$min=15;}
											else if($min==15){$min=30;}
											else if($min==30){$min=45;}
											else{$min=0;$heure++;}
										}
										?>
										</select>
									<?php } ?>
								</td>
							</tr>
							<tr class="TitreColsUsers">
								<td></td>
								<td></td>
								<td  class="Libelle"><?php if($LangueAffichage=="FR"){echo "Pause repas";}else{echo "Lunch break";}?> : </td>
								<td>
									<select id="pauseRepas<?php echo $nb; ?>" name="pauseRepas<?php echo $nb; ?>" onchange="VerifHeuresPause(<?php echo $nbDates; ?>,'D',<?php echo $nb; ?>)">
										<?php
										$Tableau=array('Oui|1','Non|0');
										foreach($Tableau as $indice => $valeur)
										{
											$valeur=explode("|",$valeur);
											echo "<option value='".$valeur[1]."' ";
												if($rowDates['PauseRepas']==$valeur[1]){echo "selected";}
											echo ">".$valeur[0]."</option>\n";
										}
										?>
									</select>
								</td>
								<td id="td_heurepause<?php echo $nb; ?>" class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "De";}else{echo "From";}?>&nbsp;</td>
								<td id="td_heurepause2<?php echo $nb; ?>" class="Libelle" width="15%">
									<select name="heureDebutPause<?php echo $nb; ?>" id="heureDebutPause<?php echo $nb; ?>" onchange="VerifHeuresPause(<?php echo $nbDates; ?>,'D',<?php echo $nb; ?>)" >
									<?php
									$heure=5;
									$min=0;
									for($i=1;$i<=61;$i++){
										if($min==0){$minAffiche="0";}
										else{$minAffiche=$min;}
										$selected="";
										if($rowDates['HeureDebutPause']==sprintf('%02d', $heure).":".sprintf('%02d', $min).":00"){$selected="selected";}
										echo "<option value=\"".sprintf('%02d', $heure).":".sprintf('%02d',$min)."\" ".$selected.">".sprintf('%02d', $heure)."h".sprintf('%02d',$min)."</option>";
										if($min==0){$min=15;}
										else if($min==15){$min=30;}
										else if($min==30){$min=45;}
										else{$min=0;$heure++;}
									}
									?>
									</select>
									<?php if($LangueAffichage=="FR"){echo "&nbsp;à&nbsp;";}else{echo "&nbsp;to&nbsp;";}?>
									<select name="heureFinPause<?php echo $nb; ?>" id="heureFinPause<?php echo $nb; ?>" onchange="VerifHeuresPause(<?php echo $nbDates; ?>,'D',<?php echo $nb; ?>)" >
									<?php 
										$heure=5;
									$min=0;
									for($i=1;$i<=61;$i++){
										if($min==0){$minAffiche="0";}
										else{$minAffiche=$min;}
										$selected="";
										if($rowDates['HeureFinPause']==sprintf('%02d', $heure).":".sprintf('%02d', $min).":00"){$selected="selected";}
										echo "<option value=\"".sprintf('%02d', $heure).":".sprintf('%02d',$min)."\" ".$selected.">".sprintf('%02d', $heure)."h".sprintf('%02d',$min)."</option>";
										if($min==0){$min=15;}
										else if($min==15){$min=30;}
										else if($min==30){$min=45;}
										else{$min=0;$heure++;}
										}
									?>
									</select>
								</td>
							</tr>
							<?php
								$nb++;
									}
								}
							?>
						</table>
						<?php
							echo "<script>MiseAJourCompteur('".$LangueAffichage."');</script>";
							}
						?>
					</div>
				</td>
			</tr>
			<tr>
				<td  class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Lieu";}else{echo "Place";}?> : </td>
				<td>
					&nbsp;<select name="lieu" id="lieu">
						<option value="0"></option>
						<?php
						$resultLieu=mysqli_query($bdd,"SELECT Id, Libelle FROM form_lieu WHERE Id_Plateforme=".$_GET['Id_Plateforme']." AND Suppr=0 ORDER BY Libelle ASC");
						while($rowLieu=mysqli_fetch_array($resultLieu))
						{
							echo "<option value='".$rowLieu['Id']."'";
							if($_GET['Mode']=="M"){
								if($Ligne['Id_Lieu']==$rowLieu['Id']){echo "selected";}
							}
							echo ">".stripslashes($rowLieu['Libelle'])."</option>\n";
						}
						?>
					</select>
				</td>
				<td  class="Libelle"><?php if($LangueAffichage=="FR"){echo "Formateur";}else{echo "Former";}?> : </td>
				<td>
					<select name="formateur" id="formateur">
						<option value="0"></option>
						<?php
						$req="SELECT DISTINCT Id, CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil ";
						$req.="WHERE Id IN (SELECT Id_Personne FROM new_competences_personne_poste_plateforme WHERE Id_Poste=21 AND Id_Plateforme=".$_GET['Id_Plateforme'].") ORDER BY Personne ASC";
						$resultFormateur=mysqli_query($bdd,$req);
						while($rowFormateur=mysqli_fetch_array($resultFormateur))
						{
							echo "<option value='".$rowFormateur['Id']."'";
							if($_GET['Mode']=="M"){
								if($Ligne['Id_Formateur']==$rowFormateur['Id']){echo "selected";}
							}
							echo ">".stripslashes($rowFormateur['Personne'])."</option>\n";
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td  class="Libelle" colspan="6"><?php if($LangueAffichage=="FR"){echo "Message à l'attention des stagiaires (convocation)";}else{echo "Message for trainees (convocation)";}?> : </td>
			</tr>
			<tr>
				<td  colspan="6">
					<textarea name="message" rows="3" cols="140" <?php echo $modifiable; ?> style="resize:none"><?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['MessageConvocation']);}?></textarea>
				</td>
			</tr>
			<tr>
				<td  class="Libelle" colspan="6"><?php if($LangueAffichage=="FR"){echo "Message lors des inscriptions";}else{echo "Registration message";}?> : </td>
			</tr>
			<tr>
				<td  colspan="6">
					<textarea name="messageInscription" rows="3" cols="140" <?php echo $modifiable; ?> style="resize:none"><?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['MessageInscription']);}?></textarea>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Nb stagiaires mini";}else{echo "Number of trainees minimum";}?> : </td>
				<td>&nbsp;<input onKeyUp="nombre(this)" <?php echo $modifiable; ?> name="stagiaireMin" id="stagiaireMin" size="8" type="text" value="<?php if($_GET['Mode']=="M"){if($Ligne['Nb_Stagiaire_Mini']<>0){echo $Ligne['Nb_Stagiaire_Mini'];}} ?>"></td>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Nb stagiaires max";}else{echo "Number of trainees maximum";}?> : </td>
				<td><input onKeyUp="nombre(this)" <?php echo $modifiable; ?> name="stagiaireMax" id="stagiaireMax" size="8" type="text" value="<?php if($_GET['Mode']=="M"){if($Ligne['Nb_Stagiaire_Maxi']<>0){echo $Ligne['Nb_Stagiaire_Maxi'];}} ?>"></td>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Inter / Intra";}else{echo "Inter / Intra";} ?> : </td>
				<td>
					<select name="interIntra" id="interIntra">
						<?php
						$Tableau=array('Intra','Inter');
						foreach($Tableau as $indice => $valeur)
						{
							echo "<option value='".$valeur."' ";
							if($_GET['Mode']=="M"){
								if($Ligne['InterIntra']==$valeur || ($Ligne['InterIntra']=="" && $valeur=="Intra")){echo "selected";}
							}
							echo ">".$valeur."</option>\n";
						}
						?>
					</select>
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
					echo "</div>";
				}
			?>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="6" align="center">
			<?php
				if($ModifAssistantFor==1){
					if($nbDatesEC>0 || $_GET['Mode']=="A"){
			?>
					<input class="Bouton" name="sauvegarde" type="submit" value="<?php if($_GET['Mode']=="A"){if($LangueAffichage=="FR"){echo "Ajouter";}else{echo "Add";}}else{if($LangueAffichage=="FR"){echo "Modifier";}else{echo "Modify";}} ?>">
			<?php
					}
				}
			?>
				</td>
			</tr>
			<tr>
				<td colspan="6" align="right">
			<?Php
				if($_GET['Mode']=="M" && $ModifAssistantFor==1)
				{
				    $reqInscrit="SELECT Id FROM form_session_personne WHERE Validation_Inscription IN (0,1) AND Suppr=0 AND Id_Session=".$_GET['Id'];
				    $resultNbInscrit=mysqli_query($bdd,$reqInscrit);
				    $nbInscrit=mysqli_num_rows($resultNbInscrit);
			?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="Bouton" name="annuler" type="submit" onclick=" return window.confirm('Etes-vous sûr de vouloir annuler ?')" value="<?php if($LangueAffichage=="FR"){echo "Annuler";}else{echo "Cancel";} ?>">
			<?php
			        if($nbInscrit==0 || $Ligne['Annule']==1)
			        {
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
		if($_GET['Mode']=="A"){
			echo "<script>ModifierListeFormation('".$LangueAffichage."');</script>";
		}
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
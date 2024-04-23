<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
?>

<html>
<head>
	<title>Formations - Liste des personnes d'une session</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script type="text/javascript">
		function EditerDemandeFormation(Id_Session,Ids)
		{
			window.open("EditerDemandeFormation.php?Id_Session="+Id_Session+"&Ids="+Ids,"Demande_Formation","status=no,menubar=no,width=20,height=20");
		}
		function EditerDemandePriseEnCharge(Id_Session,Ids)
		{
			window.open("EditerDemandePriseEnCharge.php?Id_Session="+Id_Session+"&Ids="+Ids,"Demande_Formation","status=no,menubar=no,width=20,height=20");
		}
		function Envoyer_Commande(Action){
			document.getElementById('Action').value=Action;
			formulaire_liste_personnes.submit();
		}
		function OuvreFenetreProfil(Mode,Id)
			{ var w = window.open("../Competences/Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1040,height=800");
				w.focus();
			}
	</script>
</head>
<body>

<?php
Ecrire_Code_JS_Init_Date();

//RECUPERATION DE CERTAINES DONNEES EN FONCTION DE LA SESSION AVANT N'IMPORTE QUEL TRAITEMENT
//--------------------------------------------------------------------------------------------
if($_POST){$ID=$_POST['Id'];$ID_PLATEFORME=$_POST['Id_Plateforme'];}
else{$ID=$_GET['Id'];$ID_PLATEFORME=$_GET['Id_Plateforme'];}

if($_POST){$ancre=$_POST['ancre'];$ancre=$_POST['ancre'];}
else{$ancre=$_GET['ancre'];$ancre=$_GET['ancre'];}

$ReqSessionInfoMinime="
	SELECT
		Id_Formation,
		Id_Plateforme,
		Recyclage,
		TarifGroupe
	FROM
		form_session
	WHERE
		form_session.Id=".$ID;
$ResultSessionInfoMinime=mysqli_query($bdd,$ReqSessionInfoMinime);
$RowSessionInfoMinime=mysqli_fetch_array($ResultSessionInfoMinime);

//RECUPERATION DES QCM EN FONCTION DES QUALIFICATIONS DE LA FORMATION POUR LA SESSION EN COURS
$ReqSessionQualificationQCM="
	SELECT
		TABLE_TEMP.*
	FROM
		(
		SELECT
			form_session_personne_qualification.Id AS ID_SESSION_PERSONNE_QUALIFICATION,
			form_session_personne_qualification.Id_Qualification AS ID_QUALIFICATION,
			form_session_personne.Id_Personne AS ID_PERSONNE,
			form_session_personne_qualification.Etat AS ETAT_SESSION_QUALIFICATION,
			form_session_personne_qualification.Resultat AS RESULTAT_SESSION_QUALIFICATION,
			new_competences_qualification.Libelle AS LIBELLE_QUALIFICATION,
			form_session_personne_qualification.Id_QCM AS ID_QCM,
			form_session_personne_qualification.Id_QCM_Lie AS ID_QCM_LIE,
			form_session_personne_qualification.Resultat AS RESULTAT_QCM,
			IF(ISNULL(form_qcm.Suppr),0,form_qcm.Suppr) AS SUPPR_QCM,
			IF(ISNULL(form_qcm_langue.Libelle),0,form_qcm_langue.Libelle) AS LIBELLE_QCM,
			IF(ISNULL(form_qcm_langue.Id_Langue),0,form_qcm_langue.Id_Langue) AS ID_QCM_LANGUE,
			IF(ISNULL(form_qcm_langue.Suppr),0,form_qcm_langue.Suppr) AS SUPPR_QCM_LANGUE,
			IF(ISNULL(form_langue.Libelle),0,form_langue.Libelle) AS LIBELLE_LANGUE,
			IF(ISNULL(form_langue.Suppr),0,form_langue.Suppr) AS SUPPR_LANGUE,
			IF(ISNULL(form_qcm.Id),0,IF(form_qcm.Id_QCM_Lie=0,form_qcm.Id,CONCAT(form_qcm.Id_QCM_Lie,'|',form_qcm.Id))) AS ID_QCM_ID_QCM_LIE,
            form_session_personne.Date_Inscription AS DATE_INSCRIPTION_PERSONNE
		FROM
			form_session_personne_qualification
			LEFT JOIN new_competences_qualification ON new_competences_qualification.Id=form_session_personne_qualification.Id_Qualification
			LEFT JOIN form_session_personne ON form_session_personne.Id=form_session_personne_qualification.Id_Session_Personne
			LEFT JOIN form_qcm ON form_session_personne_qualification.Id_QCM=form_qcm.Id
			LEFT JOIN form_qcm_langue ON form_qcm_langue.Id_QCM=form_qcm.Id
			LEFT JOIN form_langue ON form_langue.Id=form_qcm_langue.Id_Langue
		WHERE
			form_session_personne_qualification.Suppr=0
            AND form_session_personne.Validation_Inscription=1
			AND form_session_personne.Id_Session=".$ID."
			AND form_session_personne.Suppr=0
		ORDER BY
            DATE_INSCRIPTION_PERSONNE,
			ID_SESSION_PERSONNE,
			ID_QUALIFICATION,
			ID_QCM
		) AS TABLE_TEMP
	WHERE
		TABLE_TEMP.SUPPR_QCM=0
		AND TABLE_TEMP.SUPPR_QCM_LANGUE=0
		AND TABLE_TEMP.SUPPR_LANGUE=0
";

$ResultSessionQualificationQCM=mysqli_query($bdd,$ReqSessionQualificationQCM);
$NBResultSessionQualificationQCM=mysqli_num_rows($ResultSessionQualificationQCM);

//TRAITEMENT DES DONNEES EN POST
//------------------------------
if($_POST)
{	
	//CAS DE LA VALIDATION DE LA PRESENCE OU DE L'INSCRIPTION D'UNE PERSONNE OU DE L'ETAT DES QUALIFICATIONS
	//------------------------------------------------------------------------------------------------------
	if(isset($_POST['Action']))
	{

		if($_POST['Action']=="Validation_PriseEnCharge")
		{
			$TableauValeurs=explode("|",$_POST['Liste_IDSessionPersonne']);
			foreach($TableauValeurs as $Valeur)
			{
				$Req="UPDATE form_session_personne 
					SET 
					DdePriseEnChargeEnvoyee='".$_POST['demandePriseEnChargeEnvoyee'.$Valeur]."',
					AccordPriseEnCharge='".$_POST['accordPriseEnCharge'.$Valeur]."',
					TraitementConvention='".$_POST['traitementConvention'.$Valeur]."',
					MotifAbsence='".addslashes($_POST['motifAbsence'.$Valeur])."',
					FeuillePresence='".$_POST['feuillePresence'.$Valeur]."',
					EvaluationAChaud='".$_POST['evaluationAChaud'.$Valeur]."'
					WHERE Id=".$Valeur;

				$Result=mysqli_query($bdd,$Req);
			}
		}
		elseif($_POST['Action']=="Ouvrir_DemandeFormation")
		{
			if(isset($_POST['DemandeFormation']))
			{
				$Ids="";
				foreach($_POST['DemandeFormation'] as $valeur)
				{
					if($Ids<>""){$Ids.="_";}
					$Ids.=$valeur;
					
				}
				if($Ids<>""){echo "<script>EditerDemandeFormation('".$ID."','".$Ids."');</script>";}
				
			}
		}
		elseif($_POST['Action']=="Ouvrir_DemandePriseEnCharge")
		{
			if(isset($_POST['DemandePriseEnCharge']))
			{
				$Ids="";
				foreach($_POST['DemandePriseEnCharge'] as $valeur)
				{
					if($Ids<>""){$Ids.="_";}
					$Ids.=$valeur;
					
				}
				if($Ids<>""){echo "<script>EditerDemandePriseEnCharge('".$ID."','".$Ids."');</script>";}
				
			}
		}
	}
}
$ResultSession=get_session($ID);
$RowSession=mysqli_fetch_array($ResultSession);

$ResultSessionPersonnes=getRessource(getchaineSQL_sessionPersonne($ID));

$ResultSessionQualificationQCM=mysqli_query($bdd,$ReqSessionQualificationQCM);
$NBResultSessionQualificationQCM=mysqli_num_rows($ResultSessionQualificationQCM);

?>	

	<table class="TableCompetences" style="width:100%; align:center;">
		<tr class="TitreColsUsers">
			<td class="TitrePage">
				<?php
				if($LangueAffichage=="FR")
				{
					echo "Formation # ".$RowSession['FORMATION_REFERENCE']." #";
					echo " du ".AfficheDateJJ_MM_AAAA($RowSession['DATE_DEBUT'])." à ".substr($RowSession['HEURE_DEBUT'],0,-3)." au ".AfficheDateJJ_MM_AAAA($RowSession['DATE_FIN'])." à ".substr($RowSession['HEURE_FIN'],0,-3);
					echo " située à ".$RowSession['LIEU'];
				}
				else
				{
					echo "Training # ".$RowSession['FORMATION_REFERENCE']." #";
					echo " from ".AfficheDateJJ_MM_AAAA($RowSession['DATE_DEBUT'])." at ".substr($RowSession['HEURE_DEBUT'],0,-3)." to ".AfficheDateJJ_MM_AAAA($RowSession['DATE_FIN'])." at ".substr($RowSession['HEURE_FIN'],0,-3);
					echo " located in ".$RowSession['LIEU'];
				}
				?>
			</td>
		</tr>
		</table>

<!--  AFFICHAGE DE LA LISTE DES PERSONNES POUR LA SESSION DE FORMATION  -->
<form id="formulaire_liste_personnes" enctype="multipart/form-data" method="POST" action="Contenu2_Session.php">
	<input type="hidden" name="Id" value="<?php echo $ID;?>">
	<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage;?>">
	<input type="hidden" name="Id_Plateforme" id="Id_Plateforme" value="<?php echo $ID_PLATEFORME;?>">
	<input type="hidden" name="Id_TypeFormation" value="<?php echo $RowSession['ID_TYPEFORMATION'];?>">
	<input type="hidden" name="Nb_Stagiaire_Maxi" value="<?php echo $RowSession['NB_STAGIAIRE_MAXI'];?>">
	<input type="hidden" name="Id_GroupeSession" value="<?php echo $RowSession['ID_GROUPE_SESSION'];?>">
	<input type="hidden" name="FormationLiee" value="<?php echo $RowSession['FORMATION_LIEE'];?>">
	<input type="hidden" id="ancre" name="ancre" value="<?php echo $ancre;?>">
	<input type="hidden" name="Action" Id="Action" value="">
	<table style="width:100%; align:center;">
		<tr class="TitreColsUsers">
			<td>
				<table class="TableCompetences" style="width:100%;">
					<tr>
						<?php if($RowSession['ID_TYPEFORMATION']<>3){?><td width="3%" valign="middle" class="Libelle"></td><?php } ?>
						<td width="8%" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Stagiaires";}else{echo "Trainees";}?><br></td>
						<td width="7%" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?></td>
						<td width="6%" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Contrat";}else{echo "Contract";}?></td>
						<?php if($RowSession['ID_TYPEFORMATION']<>3){?><td width="3%" valign="middle" class="Libelle"></td><?php } ?>
						<td width="4%" style='border-left:1px solid #6fa3fd;' valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Prés.";}else{echo "Present";}?><br>ok</td>
						<td width="4%" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Motif absence";}else{echo "Reason for absence";}?></td>
						<td width="4%" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Dde prise en charge envoyée";}else{echo "Support request sent";}?></td>
						<td width="4%" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Accord prise en charge";}else{echo "Support agreement";}?></td>
						<td width="4%" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Traitement convention";}else{echo "Convention processing";}?></td>
						<td width="4%" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Feuille de prés.";}else{echo "Timesheet";}?></td>
						<td width="4%" style='border-right:1px solid #6fa3fd;' valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Eval. à chaud";}else{echo "Hot evaluation";}?></td>
						<?php
						if	(
							DroitsFormationPlateforme($TableauIdPostesAssistantFormation)
							|| DroitsFormationPlateforme(array_merge($TableauIdPostesAssistantFormation,array($IdPosteResponsableFormation)))
							)
						{
						?>
						<td width="15%" valign="middle" align='center' class="Libelle"><?php if($LangueAffichage=="FR"){echo "Motif absence";}else{echo "Reason for absence";}?></td>
						<td width="7%" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Dde prise en charge envoyée";}else{echo "Support request sent";}?></td>
						<td width="7%" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Accord prise en charge";}else{echo "Support agreement";}?></td>
						<td width="7%" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Traitement convention";}else{echo "Convention processing";}?></td>
						<td width="7%" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Feuille de prés.";}else{echo "Timesheet";}?></td>
						<td width="7%" style='border-right:1px solid #6fa3fd;' valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Eval. à chaud";}else{echo "Hot evaluation";}?></td>
						<?php 
						}
						?>
					</tr>
					<tr height='1' bgcolor='#66AACC'><td colspan='18'></td></tr>
					<tr><td colspan='18' bgcolor="#DDDDDD"><b><?php if($LangueAffichage=="FR"){echo "Inscrits";}else{echo "Registered";}?></b></td></tr>
					<tr height='1' bgcolor='#66AACC'><td colspan='18'></td></tr>
					<?php
					$IndiceCaseACocher=-1;
					$Liste_IDSessionPersonneQualification="";
					$Liste_IDSessionPersonne="";
					$Couleur="#fed4d4";
					while($RowSessionPersonnes=mysqli_fetch_array($ResultSessionPersonnes))
					{
						if($Couleur=="#fed4d4"){$Couleur="#FFFFFF";}
						else{$Couleur="#fed4d4";}
						$Liste_IDSessionPersonne.=$RowSessionPersonnes['ID']."|";
						$IndiceCaseACocher++;

						$req_getIdPrestation = "
                            SELECT
                                new_competences_prestation.Libelle
                            FROM
                                new_competences_personne_prestation,
                                new_competences_prestation
                            WHERE
                                Id_Personne = ".$IdPersonneConnectee."
                                AND new_competences_prestation.Id = new_competences_personne_prestation.Id_Prestation
                                AND Date_Debut < NOW()
                                AND NOW() < Date_Fin";
						$ressource = getRessource($req_getIdPrestation);
						$Row_presta=mysqli_fetch_array($ressource);
						
						$IdContrat=IdContrat($RowSessionPersonnes['ID_PERSONNE'],$RowSessionPersonnes['DATE_INSCRIPTION']);
						$Contrat="";
						$leHover="";
						$span="";
						$Interim=0;
						if($IdContrat>0){
							if(TypeContrat2($IdContrat)<>10){
								$Contrat=TypeContrat($IdContrat);
								
								$req="SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=".$RowSessionPersonnes['ID_PERSONNE'];
								$result=mysqli_query($bdd,$req);
								$nb=mysqli_num_rows($result);
								if($nb>0){
									$row=mysqli_fetch_array($result);
									$leHover="id='leHover'";
									$span="<span>Matricule Paris : ".$row['MatriculeAAA']."</span>";
								}
							}
							else{
								$Interim=1;
								$tab=AgenceInterimContrat($IdContrat);
								if($tab<>0){
									$Contrat=$tab[0];
									if(DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne))==1){
										$leHover="id='leHover'";
										$span="<span>Coeff agence : ".$tab[2]."<br>";
										
										if($LangueAffichage=="FR"){$span.="Taux horaire : ".$tab[4]."<br>";}
										else{$span.="Hourly rate : ".$tab[4]."<br>";}
										
										$req="SELECT DateFin
											FROM rh_personne_contrat
											WHERE Suppr=0
											AND TypeDocument IN ('Nouveau','Avenant')
											AND Id_Personne=".$RowSessionPersonnes['ID_PERSONNE']."
											ORDER BY DateDebut DESC, Id DESC";
											
										$result=mysqli_query($bdd,$req);
										$nb=mysqli_num_rows($result);
										if($nb>0){
											$row=mysqli_fetch_array($result);
											if($LangueAffichage=="FR"){$span.="Date fin contrat : ".AfficheDateJJ_MM_AAAA($row['DateFin'])."<br>";}
											else{$span.="End date contract : ".AfficheDateJJ_MM_AAAA($row['DateFin'])."<br>";}
										}
										
										$span.="</span>";
									}
								}
							}
						}
						
						echo "<tr bgcolor='".$Couleur."'>";
						
						if($RowSession['ID_TYPEFORMATION']<>3){
							if($Interim==0){
								echo "<td><input type='checkbox' name='DemandeFormation[]' value='".$RowSessionPersonnes['ID']."'></td>";
							}
							else{
								echo "<td></td>";
							}
						}
						
						if($RowSessionPersonnes['PRESTATION'] == $Row_presta['Libelle'])
						{
							echo "<td><mark><a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$RowSessionPersonnes['ID_PERSONNE']."\");'>".$RowSessionPersonnes['STAGIAIRE_NOMPRENOM']."</a></mark></td>\n";
						}
						else
						{
							echo "<td><a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$RowSessionPersonnes['ID_PERSONNE']."\");'>".$RowSessionPersonnes['STAGIAIRE_NOMPRENOM']."</a></td>\n";
						}
						echo "<td>".substr($RowSessionPersonnes['PRESTATION'],0,7).$RowSessionPersonnes['POLE']."<br>(".$RowSessionPersonnes['Code_Analytique'].")</td>\n";
						
						echo "<td ".$leHover.">".$Contrat.$span."</td>\n";
						
						if($RowSession['ID_TYPEFORMATION']<>3){
							if($Interim==1){
								echo "<td><input type='checkbox' name='DemandePriseEnCharge[]' value='".$RowSessionPersonnes['ID']."'></td>";
							}
							else{
								echo "<td></td>";
							}
						}
						
						echo "<td style='border-left:1px solid #6fa3fd;' >";
						if($RowSessionPersonnes['PRESENCE']==1 && $RowSessionPersonnes['VALIDATION_INSCRIPTION']==1){echo "<img src='../../Images/tick.png' style='border:0;' title='Present'>";}
						elseif($RowSessionPersonnes['PRESENCE']==-1 && $RowSessionPersonnes['VALIDATION_INSCRIPTION']==1){echo "<img src='../../Images/Refuser.gif' style='border:0;' title='Absent'>";}
						elseif($RowSessionPersonnes['PRESENCE']==-2 && $RowSessionPersonnes['VALIDATION_INSCRIPTION']==1){echo substr($RowSessionPersonnes['SEMI_PRESENCE'],0,5);}
						echo "</td>";
						
						echo "<td ";
						if($RowSessionPersonnes['PRESENCE']==1){
							echo "><img src='../../Images/subtract-sign.png' style='border:0;'>";
						}
						else{
							if($RowSessionPersonnes['MotifAbsence']<>""){
								echo "id='leHover'> <img src='../../Images/3points.png' style='border:0;width:10px;'><span>".stripslashes($RowSessionPersonnes['MotifAbsence'])."</span>";
							}
							else{
								echo ">";
							}
						}
						echo "</td>";
						
						echo "<td >";
						if($RowSession['ID_TYPEFORMATION']==3){
							echo "<img src='../../Images/subtract-sign.png' style='border:0;'>";
						}
						else{
							if($RowSessionPersonnes['DdePriseEnChargeEnvoyee']=="-"){echo "<img src='../../Images/subtract-sign.png' style='border:0;'>";}
							elseif($RowSessionPersonnes['DdePriseEnChargeEnvoyee']=="X"){echo "<img src='../../Images/tick.png' style='border:0;'>";}
						}
						echo "</td>";
						
						echo "<td >";
						if($RowSession['ID_TYPEFORMATION']==3){
							echo "<img src='../../Images/subtract-sign.png' style='border:0;'>";
						}
						else{
							if($RowSessionPersonnes['AccordPriseEnCharge']=="-"){echo "<img src='../../Images/subtract-sign.png' style='border:0;'>";}
							elseif($RowSessionPersonnes['AccordPriseEnCharge']=="X"){echo "<img src='../../Images/tick.png' style='border:0;'>";}
						}
						echo "</td>";
						
						echo "<td >";
						if($RowSession['ID_TYPEFORMATION']==3){
							echo "<img src='../../Images/subtract-sign.png' style='border:0;'>";
						}
						else{
							if($RowSessionPersonnes['TraitementConvention']=="-"){echo "<img src='../../Images/subtract-sign.png' style='border:0;'>";}
							elseif($RowSessionPersonnes['TraitementConvention']=="X"){echo "<img src='../../Images/tick.png' style='border:0;'>";}
						}
						echo "</td>";
						
						echo "<td >";
						if($RowSession['ID_TYPEFORMATION']==3){
							if($RowSessionPersonnes['PRESENCE']==-1 || $RowSessionPersonnes['PRESENCE']==-2 || $RowSessionPersonnes['FeuillePresence']=="-"){echo "<img src='../../Images/subtract-sign.png' style='border:0;'>";}
							elseif($RowSessionPersonnes['PRESENCE']==1 || $RowSessionPersonnes['FeuillePresence']=="X"){echo "<img src='../../Images/tick.png' style='border:0;'>";}
						}
						else{
							if($RowSessionPersonnes['PRESENCE']==-1 || $RowSessionPersonnes['PRESENCE']==-2 || $RowSessionPersonnes['FeuillePresence']=="-"){echo "<img src='../../Images/subtract-sign.png' style='border:0;'>";}
							elseif($RowSessionPersonnes['FeuillePresence']=="X"){echo "<img src='../../Images/tick.png' style='border:0;'>";}
						}
						echo "</td>";
						
						echo "<td style='border-right:1px solid #6fa3fd;'>";
						if($RowSession['ID_TYPEFORMATION']==3){
							$req="SELECT Id 
								FROM form_session_personne_document
								WHERE Suppr=0 
								AND Id_Document=6
								AND DateHeureRepondeur>'0001-01-01'
								AND Id_Session_Personne=".$RowSessionPersonnes['ID']." ";
							$ResultSessionPersDoc=mysqli_query($bdd,$req);
							$NbSessionPersDoc=mysqli_num_rows($ResultSessionPersDoc);
							if($RowSessionPersonnes['PRESENCE']==-1 || $RowSessionPersonnes['PRESENCE']==-2 || $RowSessionPersonnes['EvaluationAChaud']=="-"){echo "<img src='../../Images/subtract-sign.png' style='border:0;'>";}
							elseif($NbSessionPersDoc>0 || $RowSessionPersonnes['EvaluationAChaud']=="X"){echo "<img src='../../Images/tick.png' style='border:0;'>";}
						}
						else{
							if($RowSessionPersonnes['PRESENCE']==-1 || $RowSessionPersonnes['PRESENCE']==-2 || $RowSessionPersonnes['EvaluationAChaud']=="-"){echo "<img src='../../Images/subtract-sign.png' style='border:0;'>";}
							elseif($RowSessionPersonnes['EvaluationAChaud']=="X"){echo "<img src='../../Images/tick.png' style='border:0;'>";}
						}
						echo "</td>";
						
						
						if(DroitsFormationPlateforme($TableauIdPostesAssistantFormation)
						|| DroitsFormationPlateforme(array_merge($TableauIdPostesAssistantFormation,array($IdPosteResponsableFormation)))
						)
						{
							//Motif d'absence
							echo "<td>";
							if($RowSessionPersonnes['PRESENCE']==-1 || $RowSessionPersonnes['PRESENCE']==-2){
								echo "<input id='motifAbsence".$RowSessionPersonnes['ID']."' size='30px' name='motifAbsence".$RowSessionPersonnes['ID']."' value=\"".stripslashes($RowSessionPersonnes['MotifAbsence']). "\" />";
							}
							else{
								echo "<input style='display:none;' id='motifAbsence".$RowSessionPersonnes['ID']."' size='30px' name='motifAbsence".$RowSessionPersonnes['ID']."' value='' />";
							}
							echo "</td>\n";
							
							//demande de prise en charge envoyée
							echo "<td>";
							echo "<select id='demandePriseEnChargeEnvoyee".$RowSessionPersonnes['ID']."' name='demandePriseEnChargeEnvoyee".$RowSessionPersonnes['ID']."' ";
							if($RowSession['ID_TYPEFORMATION']==3){
								echo "style='display:none;'";
							}
							echo " >";
							if($RowSession['ID_TYPEFORMATION']<>3){
								echo "<option value=''></option>";
							}
							echo "<option value='-' ";
							if($RowSession['ID_TYPEFORMATION']==3 || $RowSessionPersonnes['DdePriseEnChargeEnvoyee']=="-"){echo "selected";}
							echo ">-</option>";
							if($RowSession['ID_TYPEFORMATION']<>3){
								echo "<option value='X' ";
								if($RowSessionPersonnes['DdePriseEnChargeEnvoyee']=="X"){echo "selected";}
								echo ">X</option>";
							}
							echo "</select";
							echo "</td>\n";
							
							//accord de prise en charge
							echo "<td>";
							echo "<select id='accordPriseEnCharge".$RowSessionPersonnes['ID']."' name='accordPriseEnCharge".$RowSessionPersonnes['ID']."' ";
							if($RowSession['ID_TYPEFORMATION']==3){
								echo "style='display:none;'";
							}
							echo " >";
							if($RowSession['ID_TYPEFORMATION']<>3){
								echo "<option value=''></option>";
							}
							echo "<option value='-' ";
							if($RowSession['ID_TYPEFORMATION']==3 || $RowSessionPersonnes['AccordPriseEnCharge']=="-"){echo "selected";}
							echo ">-</option>";
							if($RowSession['ID_TYPEFORMATION']<>3){
								echo "<option value='X' ";
								if($RowSessionPersonnes['AccordPriseEnCharge']=="X"){echo "selected";}
								echo ">X</option>";
							}
							echo "</select";
							echo "</td>\n";
							
							//traitement convention
							echo "<td>";
							echo "<select id='traitementConvention".$RowSessionPersonnes['ID']."' name='traitementConvention".$RowSessionPersonnes['ID']."' ";
							if($RowSession['ID_TYPEFORMATION']==3){
								echo "style='display:none;'";
							}
							echo " >";
							if($RowSession['ID_TYPEFORMATION']<>3){
								echo "<option value=''></option>";
							}
							echo "<option value='-' ";
							if($RowSession['ID_TYPEFORMATION']==3 || $RowSessionPersonnes['TraitementConvention']=="-"){echo "selected";}
							echo ">-</option>";
							if($RowSession['ID_TYPEFORMATION']<>3){
								echo "<option value='X' ";
								if($RowSessionPersonnes['TraitementConvention']=="X"){echo "selected";}
								echo ">X</option>";
							}
							echo "</select";
							echo "</td>\n";
							
							//feuille de présence
							echo "<td>";
							echo "<select id='feuillePresence".$RowSessionPersonnes['ID']."' name='feuillePresence".$RowSessionPersonnes['ID']."' ";
							if($RowSession['ID_TYPEFORMATION']==3){
								echo "style='display:none;'";
							}
							echo " >";
							if($RowSessionPersonnes['PRESENCE']<>-1 && $RowSessionPersonnes['PRESENCE']<>-2){
								echo "<option value=''></option>";
							}
							echo "<option value='-' ";
							if($RowSessionPersonnes['PRESENCE']==-1 || $RowSessionPersonnes['PRESENCE']==-2 || $RowSessionPersonnes['FeuillePresence']=="-"){echo "selected";}
							echo ">-</option>";
							if($RowSessionPersonnes['PRESENCE']<>-1 && $RowSessionPersonnes['PRESENCE']<>-2){
								echo "<option value='X' ";
								if($RowSessionPersonnes['FeuillePresence']=="X"){echo "selected";}
								echo ">X</option>";
							}
							echo "</select";
							echo "</td>\n";
							
							//Evaluation à chaud
							echo "<td>";
							echo "<select id='evaluationAChaud".$RowSessionPersonnes['ID']."' name='evaluationAChaud".$RowSessionPersonnes['ID']."' ";
							if($RowSession['ID_TYPEFORMATION']==3 && $NbSessionPersDoc>0){
								echo "style='display:none;'";
							}
							echo " >";
							if($RowSessionPersonnes['PRESENCE']<>-1 && $RowSessionPersonnes['PRESENCE']<>-2){
								echo "<option value=''></option>";
							}
							echo "<option value='-' ";
							if($RowSessionPersonnes['PRESENCE']==-1 || $RowSessionPersonnes['PRESENCE']==-2 || $RowSessionPersonnes['EvaluationAChaud']=="-"){echo "selected";}
							echo ">-</option>";
							if($RowSessionPersonnes['PRESENCE']<>-1 && $RowSessionPersonnes['PRESENCE']<>-2){
								echo "<option value='X' ";
								if($RowSessionPersonnes['EvaluationAChaud']=="X"){echo "selected";}
								echo ">X</option>";
							}
							echo "</select";
							echo "</td>\n";
						}
						echo "</tr>\n";
					}
					$Liste_IDSessionPersonne=substr($Liste_IDSessionPersonne,0,strlen($Liste_IDSessionPersonne)-1);
					echo "<input type='hidden' name='Liste_IDSessionPersonne' value='".$Liste_IDSessionPersonne."'>\n";
					?>
					<tr>
						<td colspan=3>
						<?php 
							if($RowSession['ID_TYPEFORMATION']<>3){
								if($LangueAffichage=="FR"){echo "<input class='Bouton' type='submit' value='Demande de formation' onclick='javascript:Envoyer_Commande(\"Ouvrir_DemandeFormation\");'>";}
								else{echo "<input class='Bouton' type='submit' value='Training Application' onclick='javascript:Envoyer_Commande(\"Ouvrir_DemandeFormation\");'>";}
							}
						?>
						</td>
						<td colspan=9>
						<?php 
							if($RowSession['ID_TYPEFORMATION']<>3){
								if($LangueAffichage=="FR"){echo "<input class='Bouton' type='submit' value='Demande de prise en charge' onclick='javascript:Envoyer_Commande(\"Ouvrir_DemandePriseEnCharge\");'>";}
								else{echo "<input class='Bouton' type='submit' value='Application support' onclick='javascript:Envoyer_Commande(\"Ouvrir_DemandePriseEnCharge\");'>";}
							}
						?>
						</td>
						<?php
						if	(
							DroitsFormationPlateforme($TableauIdPostesAssistantFormation)
							|| DroitsFormationPlateforme(array_merge($TableauIdPostesAssistantFormation,array($IdPosteResponsableFormation)))
							)
						{
							if($LangueAffichage=="FR"){echo "<td colspan='6' align='center'><input class='Bouton' type='submit' value='Valider' onclick='javascript:Envoyer_Commande(\"Validation_PriseEnCharge\");'></td>\n";}
							else{echo "<td colspan='6' align='center'><input class='Bouton' type='submit' value='Validate' onclick='javascript:Envoyer_Commande(\"Validation_PriseEnCharge\");'></td>\n";}
						}
						?>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr class="TitreColsUsers">
			<td class="Libelle">
				<?php if($LangueAffichage=="FR"){echo "Désinscriptions comptabilisées: ";}else{echo "Unregistered entries : ";} ?>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td>
				<table class="TableCompetences" style="width:100%;">
					<tr>
						<td width="8%" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Stagiaires";}else{echo "Trainees";}?><br></td>
						<td width="7%" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?></td>
						<td width="6%" style='border-right:1px solid #6fa3fd;' valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Contrat";}else{echo "Contract";}?></td>
						<td width="10%" align='center' class="Libelle"><?php if($LangueAffichage=="FR"){echo "Coût";}else{echo "Cost";}?></td>
						<td width="69%" align='center' valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Motif désinscription";}else{echo "Reason for unsubscription";}?></td>
					</tr>
					<tr height='1' bgcolor='#66AACC'><td colspan='16'></td></tr>
					<?php
					$IndiceCaseACocher=-1;
					$Liste_IDSessionPersonneQualification="";
					$Liste_IDSessionPersonne="";
					$Couleur="#fed4d4";
					
					$req = "
						SELECT
							form_session_personne.Id AS ID,
							form_session_personne.Id_Personne AS ID_PERSONNE,
							(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS STAGIAIRE_NOMPRENOM,
							IF(form_session_personne.Suppr=1,form_session_personne.Date_Desinscription,form_session_personne.Date_Valideur) AS Date_Desinscription,
							form_session_personne.Cout AS COUT,
							new_competences_prestation.Libelle AS PRESTATION,
							(SELECT CONCAT(' - ',Libelle) FROM new_competences_pole WHERE new_competences_pole.Id=form_besoin.Id_Pole) AS POLE,
							AComptabiliser,MotifDesinscription,
							new_competences_prestation.Code_Analytique,
							form_session_personne.Date_Inscription
						FROM
							form_session_personne,
							form_besoin,
							new_competences_prestation
						WHERE
							form_besoin.Id=form_session_personne.Id_Besoin
							AND new_competences_prestation.Id=form_besoin.Id_Prestation
							AND (
								(form_session_personne.Suppr=1 AND form_session_personne.Validation_Inscription<>0)
								OR form_session_personne.Validation_Inscription=-1
								)
							AND form_session_personne.Id_Session=".$ID."
							AND AComptabiliser=1
						ORDER BY
							STAGIAIRE_NOMPRENOM ASC,
							form_session_personne.Date_Inscription ASC;";
					$ResultSessionPersonnes=getRessource($req);
					while($RowSessionPersonnes=mysqli_fetch_array($ResultSessionPersonnes))
					{
						if($Couleur=="#fed4d4"){$Couleur="#FFFFFF";}
						else{$Couleur="#fed4d4";}
						$Liste_IDSessionPersonne.=$RowSessionPersonnes['ID']."|";
						$IndiceCaseACocher++;

						$req_getIdPrestation = "
                            SELECT
                                new_competences_prestation.Libelle
                            FROM
                                new_competences_personne_prestation,
                                new_competences_prestation
                            WHERE
                                Id_Personne = ".$IdPersonneConnectee."
                                AND new_competences_prestation.Id = new_competences_personne_prestation.Id_Prestation
                                AND Date_Debut < NOW()
                                AND NOW() < Date_Fin";
						$ressource = getRessource($req_getIdPrestation);
						$Row_presta=mysqli_fetch_array($ressource);
						
						echo "<tr bgcolor='".$Couleur."'>";
						if($RowSessionPersonnes['PRESTATION'] == $Row_presta['Libelle'])
						{
							echo "<td><mark><a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$RowSessionPersonnes['ID_PERSONNE']."\");'>".$RowSessionPersonnes['STAGIAIRE_NOMPRENOM']."</a></mark></td>\n";
						}
						else
						{
							echo "<td><a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$RowSessionPersonnes['ID_PERSONNE']."\");'>".$RowSessionPersonnes['STAGIAIRE_NOMPRENOM']."</a></td>\n";
						}
						echo "<td>".substr($RowSessionPersonnes['PRESTATION'],0,7).$RowSessionPersonnes['POLE']."<br>(".$RowSessionPersonnes['Code_Analytique'].")</td>\n";
						
						$IdContrat=IdContrat($RowSessionPersonnes['ID_PERSONNE'],$RowSessionPersonnes['Date_Inscription']);
						$Contrat="";
						$leHover="";
						$span="";
						if($IdContrat>0){
							if(TypeContrat2($IdContrat)<>10){
								$Contrat=TypeContrat($IdContrat);
							}
							else{
								$tab=AgenceInterimContrat($IdContrat);
								if($tab<>0){
									$Contrat=$tab[0];
									if(DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne))==1){
										$leHover="id='leHover'";
										$span="<span>Coeff agence : ".$tab[2]."<br>";
										
										if($LangueAffichage=="FR"){$span.="Coeff : ".$tab[3]."<br>";}
										else{$span.="Coeff : ".$tab[3]."<br>";}
										
										if($LangueAffichage=="FR"){$span.="Taux horaire : ".$tab[4]."<br>";}
										else{$span.="Hourly rate : ".$tab[4]."<br>";}

										$span.="</span>";
									}
								}
							}
						}
						echo "<td style='border-right:1px solid #6fa3fd;' ".$leHover.">".$Contrat.$span."</td>\n";
						
						//Coûts
						echo "<td align='center'>";
						echo $RowSessionPersonnes['COUT'];
						echo "</td>";
						
						//Motif
						echo "<td align='center' style='border-right:1px solid #6fa3fd;'>";
						echo stripslashes($RowSessionPersonnes['MotifDesinscription']);
						echo "</td>";
						
						
						
						echo "</tr>\n";
					}
					?>
				</table>
			</td>
		</tr>
	</table>
</form>
</body>
</html>
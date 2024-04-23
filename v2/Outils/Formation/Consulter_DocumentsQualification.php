<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
require("Dictionnaire.php");

?>

<html>
<head>
	<title>Formations - Consulter les documents d'une qualification</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script type="text/javascript" src="Interface_Utilisateurs.js"></script>
	<script src="../Formation/QCM.js"></script>
	<script type="text/javascript">
	function EditerFichePresenceSignee(Id)
	{
		window.open("EditerFichePresenceSignee.php?Id="+Id,"Fiche_Presence","status=no,menubar=no,width=1200,height=800");
	}
	function EditerFichePresenceSigneeQualif(Id)
	{
		window.open("EditerFichePresenceSigneeQualif.php?Id="+Id,"Fiche_Presence","status=no,menubar=no,width=1200,height=800");
	}
	function EditerFichePresenceSensibilisation(Id)
	{
		window.open("EditerFichePresenceSensibilisation.php?Id="+Id,"Fiche_Presence","status=no,menubar=no,width=1200,height=800");
	}
	function genererAttestation(Id){
		var w=window.open("Generer_Attestation.php?Id="+Id,"PageAttestation","status=no,menubar=no,scrollbars=yes,width=90,height=90");
		w.focus();
	}
	function genererAttestationQualif(Id){
		var w=window.open("Generer_AttestationQualif.php?Id="+Id,"PageAttestation","status=no,menubar=no,scrollbars=yes,width=90,height=90");
		w.focus();
	}
	function QCM_Web(Id)
	{
		var w= window.open("QCM_Web_v3.php?Page=Gestion_SessionFormation&Id_Session_Personne_Qualification="+Id,"PageQCMWeb","status=no,menubar=no,scrollbars=yes,width=1200,height=800");
		w.focus();
	}
	function QCM_WebHistorique(Id,NbLigne=-1,Num=-1)
	{
		var w= window.open("QCM_WebHistorique.php?Page=Gestion_SessionFormation&Id_Session_Personne_Qualification="+Id+"&NbLigne="+NbLigne+"&Num="+Num,"PageQCMWeb","status=no,menubar=no,scrollbars=yes,width=1200,height=800");
		w.focus();
	}
	function OuvreDocument(NomDocumentPHP,Id_Session_Personne_Document){
		var w=window.open("Document_Modele/"+NomDocumentPHP+"?Id_Session_Personne_Document="+Id_Session_Personne_Document,"PageDocumentExcel","status=no,menubar=no,width=50,height=50");
		w.focus();
	}
	</script>
</head>
<body>

<?php
$Id_Relation=$_GET['Id_Relation'];

$req="SELECT 
	new_competences_relation.Id,
	new_competences_relation.Id_Personne, 
	new_competences_relation.Id_Besoin,
	new_competences_relation.Id_Qualification_Parrainage,
	new_competences_qualification.Libelle AS Qualification,
	new_competences_relation.Id_Session_Personne_Qualification,  
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=new_competences_relation.Id_Personne) AS Personne,
	new_competences_relation.Date_Debut, 
	new_competences_relation.Date_Fin, 
	new_competences_relation.Resultat_QCM, 
	new_competences_relation.Evaluation, 
	new_competences_relation.Date_QCM,
	new_competences_relation.Sans_Fin, 
	new_competences_relation.Date_Surveillance, 
	new_competences_relation.QCM_Surveillance,
	new_competences_relation.Sensibilisation,
	new_competences_relation.NumSurveillanceSODA,
	new_competences_categorie_qualification.Libelle AS Categorie_Qualif,
	(SELECT Libelle FROM new_competences_categorie_qualification_maitre WHERE Id=new_competences_categorie_qualification.Id_Categorie_Maitre) AS Categorie_Maitre,
	new_competences_relation.AttestationFormation
	FROM new_competences_relation,
	new_competences_qualification,
	new_competences_categorie_qualification 
	
	WHERE 
	new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
	AND new_competences_qualification.Id=new_competences_relation.Id_Qualification_Parrainage
	AND new_competences_relation.Id=".$Id_Relation;
$Resultat_Qualif=mysqli_query($bdd,$req);
$LigneQualification=mysqli_fetch_array($Resultat_Qualif);
?>

	<table style="width:100%;">
		<tr>
			<td>
				<table style="width:100%;" class="TableCompetences">
					<tr>
						<td class="TitrePage">
							<?php if($LangueAffichage=="FR"){echo "Dossier formation - ".$LigneQualification['Personne'];}else{echo "Training file - ".$LigneQualification['Personne'];}?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td height="4" ></td></tr>
		<tr>
			<td>
				<table style="width:100%; align:center;" class="TableCompetences">
					<tr>
						<td class="Libelle">
							<?php if($LangueAffichage=="FR"){echo "Catégorie maitre";}else{echo "Category master";}?>
						</td>
						<td colspan="3">
							<?php echo $LigneQualification['Categorie_Maitre']; ?>
						</td>
					</tr>
					<tr><td height="4" ></td></tr>
					<tr>
						<td class="Libelle">
							<?php if($LangueAffichage=="FR"){echo "Catégorie";}else{echo "Category";}?>
						</td>
						<td colspan="3">
							<?php echo $LigneQualification['Categorie_Qualif']; ?>
						</td>
					</tr>
					<tr><td height="4" ></td></tr>
					<tr>
						<td class="Libelle">
							<?php if($LangueAffichage=="FR"){echo "Qualification";}else{echo "Qualification";}?>
						</td>
						<td colspan="3">
							<?php echo $LigneQualification['Qualification']; ?>
						</td>
					</tr>
					<tr><td height="4" ></td></tr>
					<tr>
						<td class="Libelle" width="25%">
							<?php if($LangueAffichage=="FR"){echo "Date de début";}else{echo "Start date";}?>
						</td>
						<td width="10%">
							<?php echo AfficheDateJJ_MM_AAAA($LigneQualification['Date_Debut']); ?>
						</td>
						<td class="Libelle" width="25%">
							<?php if($LangueAffichage=="FR"){echo "Date de fin";}else{echo "End date";}?>
						</td>
						<td width="20">
							<?php
								if($LigneQualification['Sans_Fin']=='Non'){
									echo AfficheDateJJ_MM_AAAA($LigneQualification['Date_Fin']); 
								}
								?>
						</td>
						<td width="20%">
						</td>
					</tr>
					<tr><td height="4" ></td></tr>
					<tr>
						<td class="Libelle">
							<?php if($LangueAffichage=="FR"){echo "Evaluation";}else{echo "Evaluation";}?>
						</td>
						<td>
							<?php echo $LigneQualification['Evaluation']; ?>
						</td>
						<td class="Libelle">
							<?php if($LangueAffichage=="FR"){echo "Date session";}else{echo "Date session";}?>
						</td>
						<td>
							<?php if($LigneQualification['Evaluation']<>"B"){
								$requete="
								SELECT
									(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) AS DateDebut,
									(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession DESC LIMIT 1) AS DateFin,
									(SELECT Heure_Debut FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) AS HeureDebut,
									(SELECT Heure_Fin FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession DESC LIMIT 1) AS HeureFin
									
								FROM
									form_session_personne 
								WHERE
									form_session_personne.Suppr=0
								AND form_session_personne.Id_Besoin=".$LigneQualification['Id_Besoin']."
								AND form_session_personne.Validation_Inscription<>-1
								";
								
					
								$ResultSessions=mysqli_query($bdd,$requete);
								$NbSessions=mysqli_num_rows($ResultSessions);
								if($NbSessions>0){
									$rowSession=mysqli_fetch_array($ResultSessions);
									if($rowSession['DateDebut']<>$rowSession['DateFin']){
										echo stripslashes(AfficheDateJJ_MM_AAAA($rowSession['DateDebut']))." ( ".stripslashes(substr($rowSession['HeureDebut'],0,5)).") - ".stripslashes(AfficheDateJJ_MM_AAAA($rowSession['DateFin']))." (".stripslashes(substr($rowSession['HeureFin'],0,5))." )";
									}
									else{
										echo stripslashes(AfficheDateJJ_MM_AAAA($rowSession['DateDebut']))." ( ".stripslashes(substr($rowSession['HeureDebut'],0,5))." - ".stripslashes(substr($rowSession['HeureFin'],0,5))." )";
									}
								}
							}  ?>
						</td>
					</tr>
					<tr><td height="4" ></td></tr>
					<tr>
						<td class="Libelle">
							<?php if($LangueAffichage=="FR"){echo "Date QCM";}else{echo "MCQ Date";}?>
						</td>
						<td>
							<?php echo AfficheDateJJ_MM_AAAA($LigneQualification['Date_QCM']); ?>
						</td>
						<td class="Libelle">
							<?php if($LangueAffichage=="FR"){echo "Note";}else{echo "Note";}?>
						</td>
						<td>
							<?php echo $LigneQualification['Resultat_QCM']; 
							if($LigneQualification['Resultat_QCM']<>""){echo " %";}
							?>
						</td>
					</tr>
					<tr><td height="4" ></td></tr>
					<tr>
						<td class="Libelle">
							<?php if($LangueAffichage=="FR"){echo "Date de surveillance";}else{echo "Monitoring date";}?>
						</td>
						<td>
							<?php echo AfficheDateJJ_MM_AAAA($LigneQualification['Date_Surveillance']); ?>
						</td>
						<td class="Libelle">
							<?php if($LangueAffichage=="FR"){echo "Note surveillance";}else{echo "Note monitoring";}?>
						</td>
						<td>
							<?php echo $LigneQualification['QCM_Surveillance']; 
							if($LigneQualification['QCM_Surveillance']<>""){echo " %";}
							?>
						</td>
						<td class="Libelle">
							<?php if($LangueAffichage=="FR"){echo "N° surveillance SODA";}else{echo "Surveillance number SODA";}?>
						</td>
						<td>
							<?php echo $LigneQualification['NumSurveillanceSODA']; 
							?>
						</td>
						<td class="Libelle">
							<?php 
								if($LigneQualification['Sensibilisation']==1){
							?>
									<a href="javascript:EditerFichePresenceSensibilisation('<?php echo $LigneQualification['Id'];?>');"><?php if($LangueAffichage=="FR"){echo "Feuille de présence";}else{echo "Timesheet";} ?></a>
							<?php
								}
							?>
						</td>
					</tr>
					<tr><td height="4" ></td></tr>
				</table>
			</td>
		</tr>
		<tr><td height="4" ></td></tr>
		<?php 
			$QCM="";
			$QCMLie="";
			$checked="";
			$Id_SessionPersonneQualification=0;
			$resultat="";
			$resultatMere="";
			$repondu=0;
			$Etat="";
			$Id_QCM=0;
			$InfoQCM="";
			$Id_SessionPersonneQualificationHistorique=0;
			if($LigneQualification['Id_Besoin']>0){
				//Rechercher si besoin avec session ou sans session
				$req="SELECT Traite,Id_Formation,
				(SELECT (SELECT CONCAT(' (',Libelle,')') FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
					WHERE form_formation_plateforme_parametres.Id_Formation=form_besoin.Id_Formation
					AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
					AND Suppr=0 LIMIT 1) AS Organisme,
				(SELECT IF(form_besoin.Motif='Renouvellement' AND form_formation.Recyclage=1,LibelleRecyclage,Libelle)
						FROM form_formation_langue_infos
						WHERE Id_Formation=form_besoin.Id_Formation
						AND Id_Langue=
							(SELECT Id_Langue 
							FROM form_formation_plateforme_parametres 
							WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
							AND Id_Formation=form_besoin.Id_Formation
							AND Suppr=0 
							LIMIT 1)
						AND Suppr=0) AS LibelleFormation,
					form_formation.Id_TypeFormation
					FROM form_besoin,
					form_formation,
					new_competences_prestation
					WHERE 
					form_besoin.Id_Formation=form_formation.Id
					AND form_besoin.Id_Prestation=new_competences_prestation.Id
					AND form_besoin.Id=".$LigneQualification['Id_Besoin'];
				$Resultat_Besoin=mysqli_query($bdd,$req);
				$LigneBesoin=mysqli_fetch_array($Resultat_Besoin);
				
				if($LigneBesoin['Traite']<5){
					//Avec session
					$formation=$LigneBesoin['LibelleFormation'].$LigneBesoin['Organisme'];
					
					$req="SELECT form_session_personne_qualification.Id,Id_Session_Personne, Id_Qualification,
						Resultat, ResultatMere, Etat,
						Id_QCM, Id_LangueQCM,
						(SELECT Code FROM form_qcm WHERE form_qcm.Id=form_session_personne_qualification.Id_QCM) AS CodeQCM,
						(SELECT CONCAT('(',Libelle,')') FROM form_langue WHERE form_langue.Id=form_session_personne_qualification.Id_LangueQCM) AS Langue,
						Id_QCM_Lie, Id_LangueQCMLie,
						(SELECT Code FROM form_qcm WHERE form_qcm.Id=form_session_personne_qualification.Id_QCM_Lie) AS CodeQCMLie,
						(SELECT CONCAT('(',Libelle,')') FROM form_langue WHERE form_langue.Id=form_session_personne_qualification.Id_LangueQCMLie) AS LangueLie,
						Id_Repondeur, DateHeureRepondeur
						Id_Ouvreur, DateHeureOuverture, DateHeureFermeture,
						form_session_personne.Id_Session,
						form_session_personne.Presence,
						form_session_personne.AttestationFormation
					FROM form_session_personne
					LEFT JOIN form_session_personne_qualification 
					ON form_session_personne.Id=form_session_personne_qualification.Id_Session_Personne
					WHERE form_session_personne.Suppr=0 
					AND form_session_personne_qualification.Suppr=0 
					AND form_session_personne_qualification.Id_Qualification=".$LigneQualification['Id_Qualification_Parrainage']." 
					AND form_session_personne.Id_Besoin=".$LigneQualification['Id_Besoin'];
			
					$resultSessionsPersonne=mysqli_query($bdd,$req);
					$nbSessionPersonne=mysqli_num_rows($resultSessionsPersonne);
					
					$QCM="";
					$QCMLie="";
					$checked="";
					$Id_SessionPersonneQualification=0;
					$resultat="";
					$resultatMere="";
					$repondu=0;
					$Etat="";
					$Presence=0;
					$Id_Session=0;
					$attestation="";
					$Id_Session_Personne=0;
					$Id_QCM=0;
					$InfoQCM="";
					$attestationFormation=1;
					if($nbSessionPersonne>0){
						mysqli_data_seek($resultSessionsPersonne,0);
						while($rowSessionPersonne=mysqli_fetch_array($resultSessionsPersonne)){
							$QCM=$rowSessionPersonne['CodeQCM']." ".$rowSessionPersonne['Langue']."";
							$Id_QCM=$rowSessionPersonne['Id_QCM'];
							$QCMLie=$rowSessionPersonne['CodeQCMLie']." ".$rowSessionPersonne['LangueLie']."";
							$InfoQCM=$QCM."<br>".$QCMLie;
							$Presence=$rowSessionPersonne['Presence'];
							$Id_Session=$rowSessionPersonne['Id_Session'];
							$Id_Session_Personne=$rowSessionPersonne['Id_Session_Personne'];
							$attestation=$rowSessionPersonne['AttestationFormation'];
							if($rowSessionPersonne['DateHeureOuverture']>"0001-01-01" && $rowSessionPersonne['DateHeureFermeture']<="0001-01-01"){
								$checked="checked";
							}
							$Id_SessionPersonneQualification=$rowSessionPersonne['Id'];
							if($rowSessionPersonne['Id_Repondeur']>0){
								$repondu=1;
								if($rowSessionPersonne['CodeQCMLie']<>""){
									if($LangueAffichage=="FR"){$resultatMere="QCM mère : ".$rowSessionPersonne['ResultatMere']."<br>";}
									else{$resultatMere="MCQ mother : ".$rowSessionPersonne['ResultatMere']."<br>";}
									if($LangueAffichage=="FR"){$resultat="Note finale : ";}
								else{$resultat="Final note : ";}
								}
								$resultat.=$rowSessionPersonne['Resultat'];
								if($LangueAffichage=="FR"){
									if($rowSessionPersonne['Etat']==1){$Etat="<br><font color='#2dbe29'>Réussite</font>";}
									elseif($rowSessionPersonne['Etat']==-1){$Etat="<br><font color='#e80000'>Echec</font>";}
								}
								else{
									if($rowSessionPersonne['Etat']==1){$Etat="<br><font color='#2dbe29'>Success</font>";}
									elseif($rowSessionPersonne['Etat']==-1){$Etat="<br><font color='#e80000'>Failure</font>";}
								}
							}
						}
					}
				}
				else{
					$attestationFormation=0;
					$Id_Session_Personne=0;
					$Id_Session=0;
					$req="SELECT form_session_personne_qualification.Id,Id_Session_Personne, Id_Qualification,
						Resultat, ResultatMere, Etat,
						Id_QCM, Id_LangueQCM,
						(SELECT Code FROM form_qcm WHERE form_qcm.Id=form_session_personne_qualification.Id_QCM) AS CodeQCM,
						(SELECT CONCAT('(',Libelle,')') FROM form_langue WHERE form_langue.Id=form_session_personne_qualification.Id_LangueQCM) AS Langue,
						Id_QCM_Lie, Id_LangueQCMLie,
						(SELECT Code FROM form_qcm WHERE form_qcm.Id=form_session_personne_qualification.Id_QCM_Lie) AS CodeQCMLie,
						(SELECT CONCAT('(',Libelle,')') FROM form_langue WHERE form_langue.Id=form_session_personne_qualification.Id_LangueQCMLie) AS LangueLie,
						Id_Repondeur, DateHeureRepondeur,
						Id_Ouvreur, DateHeureOuverture, DateHeureFermeture, SessionRealise
					FROM form_session_personne_qualification 
					WHERE form_session_personne_qualification.Suppr=0 
					AND form_session_personne_qualification.Id_Qualification=".$LigneQualification['Id_Qualification_Parrainage']." 
					AND form_session_personne_qualification.Id_Besoin=".$LigneQualification['Id_Besoin'];
					$resultSessionsPersonne=mysqli_query($bdd,$req);
					$nbSessionPersonne=mysqli_num_rows($resultSessionsPersonne);
					
					if($nbSessionPersonne>0){
						mysqli_data_seek($resultSessionsPersonne,0);
						while($rowSessionPersonne=mysqli_fetch_array($resultSessionsPersonne)){
							$QCM=$rowSessionPersonne['CodeQCM']." ".$rowSessionPersonne['Langue']."";
								$Id_QCM=$rowSessionPersonne['Id_QCM'];
								$QCMLie=$rowSessionPersonne['CodeQCMLie']." ".$rowSessionPersonne['LangueLie']."";
								$InfoQCM=$QCM."<br>".$QCMLie;
								if($rowSessionPersonne['DateHeureOuverture']>"0001-01-01" && $rowSessionPersonne['DateHeureFermeture']<="0001-01-01"){
									$checked="checked";
								}
								$Id_SessionPersonneQualification=$rowSessionPersonne['Id'];
								if($rowSessionPersonne['Id_Repondeur']>0){
									$repondu=1;
									if($rowSessionPersonne['CodeQCMLie']<>""){
										if($LangueAffichage=="FR"){$resultatMere="QCM mère : ".$rowSessionPersonne['ResultatMere']."<br>";}
										else{$resultatMere="MCQ mother : ".$rowSessionPersonne['ResultatMere']."<br>";}
										if($LangueAffichage=="FR"){$resultat="Note finale : ";}
									else{$resultat="Final note : ";}
									}
									$resultat.=$rowSessionPersonne['Resultat'];
									if($LangueAffichage=="FR"){
										if($rowSessionPersonne['Etat']==1){$Etat="<br><font color='#2dbe29'>Réussite</font>";}
										elseif($rowSessionPersonne['Etat']==-1){$Etat="<br><font color='#e80000'>Echec</font>";}
									}
									else{
										if($rowSessionPersonne['Etat']==1){$Etat="<br><font color='#2dbe29'>Success</font>";}
										elseif($rowSessionPersonne['Etat']==-1){$Etat="<br><font color='#e80000'>Failure</font>";}
									}
								}
								
							if($rowSessionPersonne['SessionRealise']==0){
								$attestationFormation=0;
								$Presence=0;
								if($LangueAffichage=="FR"){
									$formation = $LigneBesoin['LibelleFormation'].$LigneBesoin['Organisme']." [Passage sans session de formation]";
								}
								else{
									$formation = $LigneBesoin['LibelleFormation'].$LigneBesoin['Organisme']." [Passage without training session]";
								}	
							}
							else{
								$attestationFormation=1;
								$Presence=1;
								if($LangueAffichage=="FR"){
									$formation = $LigneBesoin['LibelleFormation'].$LigneBesoin['Organisme']."";
								}
								else{
									$formation = $LigneBesoin['LibelleFormation'].$LigneBesoin['Organisme']."";
								}	
							}
						}
					}
				}
			}
		?>
		<tr>
			<td>
				<table style="width:100%; align:center;" class="TableCompetences">
					<?php
					if($LigneQualification['Id_Besoin']>0){
					?>
					<tr>
						<td class="Libelle" width="25%">
							<?php if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";}?>
						</td>
						<td>
							<?php echo $formation; ?>
						</td>
					</tr>
					<?php
						if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS) || DroitsFormationPrestation($TableauIdPostesCQ)){
					?>
					<tr><td height="4" ></td></tr>
					<tr>
						<td class="Libelle" valign="top">
							<?php if($LangueAffichage=="FR"){echo "QCM";}else{echo "MCQ";}?>
						</td>
						<?php 
							if($repondu==1){
								echo "<td><a href='javascript:QCM_Web(\"".$Id_SessionPersonneQualification."\");'>".$QCM."<br>".$QCMLie."</a></td>";
								$Id_SessionPersonneQualificationHistorique=$Id_SessionPersonneQualification;
							}
							else{
								if($Id_QCM==0){echo "<td>NA</td>";;}
							}
						?>
					</tr>
					<?php
						}
					?>
					<tr><td height="4" ></td></tr>
					<tr>
						<td class="Libelle">
							<?php if($LangueAffichage=="FR"){echo "Attestation de formation";}else{echo "Training certificate";}?>
						</td>
						<td>
							<?php
							if($LigneBesoin['Traite']<5 || $attestationFormation==1){
								if($LangueAffichage=="FR"){
									if($LigneBesoin['Id_TypeFormation']==2 || $LigneBesoin['Id_TypeFormation']==4){
										if($attestation<>""){echo "<a class=\"Info\" href=\"../Formation/Docs/AttestationsFormations/".$attestation."\"><img src='../../Images/certificat.jpg' style='border:0;width:25px;' title='Attestation'></a>";}
									}
									else{
										if($Presence==1){
											if($Id_Session_Personne>0){
												echo "<a class=\"Info\" href=\"javascript:genererAttestation(".$Id_Session_Personne.");\"><img src='../../Images/certificat.jpg' style='border:0;width:25px;' title='Attestation'></a>";
											}
											else{
												echo "<a class=\"Info\" href=\"javascript:genererAttestationQualif(".$Id_SessionPersonneQualification.");\"><img src='../../Images/certificat.jpg' style='border:0;width:25px;' title='Attestation'></a>";
											}
										}
									}
								}
								else{
									if($LigneBesoin['Id_TypeFormation']==2 || $LigneBesoin['Id_TypeFormation']==4){
										if($attestation<>""){echo "<a class=\"Info\" href=\"../Formation/Docs/AttestationsFormations/".$attestation."\"><img src='../../Images/certificat.jpg' style='border:0;width:25px;' title='Certificate'>></a>";}
									}
									else{
										if($Presence==1){
											if($Id_Session_Personne>0){
												echo "<a class=\"Info\" href=\"javascript:genererAttestation(".$Id_Session_Personne.");\"><img src='../../Images/certificat.jpg' style='border:0;width:25px;' title='Certificate'></a>";
											}
											else{
												echo "<a class=\"Info\" href=\"javascript:genererAttestationQualif(".$Id_SessionPersonneQualification.");\"><img src='../../Images/certificat.jpg' style='border:0;width:25px;' title='Certificate'></a>";
											}
										}
									}
								}
							}
							?>
						</td>
					</tr>
					<tr><td height="4" ></td></tr>
					<tr>
						<td class="Libelle">
							<?php if($LangueAffichage=="FR"){echo "Feuille de présence";}else{echo "Timesheet";}?>
						</td>
						<td>
							<?php
								if($LigneBesoin['Traite']<5 || $attestationFormation==1){
									if($Presence<>0 && $Id_Session>0){
								?>
									<a href="javascript:EditerFichePresenceSignee('<?php echo $Id_Session;?>');"><?php if($LangueAffichage=="FR"){echo "Ouvrir";}else{echo "Open";} ?></a>
								<?php
									}
									elseif($attestationFormation==1){
								?>
									<a href="javascript:EditerFichePresenceSigneeQualif('<?php echo $Id_SessionPersonneQualification;?>');"><?php if($LangueAffichage=="FR"){echo "Ouvrir";}else{echo "Open";} ?></a>
								<?php		
									}
								}
							?>
						</td>
					</tr>
					<tr><td height="4" ></td></tr>
					<tr>
						<td class="Libelle">
							<?php if($LangueAffichage=="FR"){echo "Documents complémentaires";}else{echo "Additional documents";}?>
						</td>
					</tr>
					<?php
						if($LigneBesoin['Traite']<6){
							//Liste des documents complémentaires pour cette formation
							$reqDoc="
								SELECT DISTINCT Id_Document,
								(SELECT Reference FROM form_document WHERE form_document.Id=Id_Document) AS Document 
								FROM form_formation_document
								WHERE Id_Formation=".$LigneBesoin['Id_Formation']." 
								AND Suppr=0 ";
							$resultDoc=mysqli_query($bdd,$reqDoc);
							$NbDoc=mysqli_num_rows($resultDoc);
							
							if($Id_Session>0){
								$req="SELECT form_session_personne_document.Id,Id_Session_Personne, Id_Document,Id_LangueDocument,
									(SELECT Libelle FROM form_langue WHERE form_langue.Id=form_session_personne_document.Id_LangueDocument) AS Langue,
									(SELECT Fichier_PHP FROM form_document WHERE form_document.Id=form_session_personne_document.Id_Document) AS Fichier_PHP,
									Id_Repondeur, DateHeureRepondeur
									Id_Ouvreur, DateHeureOuverture, DateHeureFermeture 
								FROM form_session_personne
								LEFT JOIN form_session_personne_document 
								ON form_session_personne.Id=form_session_personne_document.Id_Session_Personne
								WHERE form_session_personne.Suppr=0 
								AND form_session_personne_document.Suppr=0 
								AND form_session_personne.Id=".$Id_Session_Personne;
								$resultSessionsPersonneDoc=mysqli_query($bdd,$req);
								$nbSessionPersonneDoc=mysqli_num_rows($resultSessionsPersonneDoc);
							}
							else{
								$req="SELECT form_session_personne_document.Id,
									form_session_personne_document.Id_Session_Personne, 
									form_session_personne_document.Id_Document,
									form_session_personne_document.Id_LangueDocument,
									(SELECT Libelle FROM form_langue WHERE form_langue.Id=form_session_personne_document.Id_LangueDocument) AS Langue,
									(SELECT Fichier_PHP FROM form_document WHERE form_document.Id=form_session_personne_document.Id_Document) AS Fichier_PHP,
									form_session_personne_document.Id_Repondeur, 
									form_session_personne_document.DateHeureRepondeur,
									form_session_personne_document.Id_Ouvreur, 
									form_session_personne_document.DateHeureOuverture, 
									form_session_personne_document.DateHeureFermeture 
								FROM form_session_personne_qualification
								LEFT JOIN form_session_personne_document 
								ON form_session_personne_qualification.Id=form_session_personne_document.Id_SessionPersonneQualification
								WHERE form_session_personne_qualification.Suppr=0 
								AND form_session_personne_document.Suppr=0 
								AND form_session_personne_qualification.Id=".$Id_SessionPersonneQualification;
								$resultSessionsPersonneDoc=mysqli_query($bdd,$req);
								$nbSessionPersonneDoc=mysqli_num_rows($resultSessionsPersonneDoc);
							}
							if($NbDoc>0){
								mysqli_data_seek($resultDoc,0);
								while($RowDoc=mysqli_fetch_array($resultDoc)){
									$Langue="";
									$checked="";
									$repondu="";
									$Id_SessionPersonneDoc=0;
									$Fichier_PHP="";
									if($nbSessionPersonne>0){
										mysqli_data_seek($resultSessionsPersonneDoc,0);
										while($rowSessionPersonneDoc=mysqli_fetch_array($resultSessionsPersonneDoc)){
											if($rowSessionPersonneDoc['Id_Document']==$RowDoc['Id_Document']){
												$Langue=$rowSessionPersonneDoc['Langue']."";
												if($rowSessionPersonneDoc['DateHeureOuverture']>"0001-01-01" && $rowSessionPersonneDoc['DateHeureFermeture']<="0001-01-01"){
													$checked="checked";
												}
												if($rowSessionPersonneDoc['Id_Repondeur']>0){
													$repondu="V";
												}
												$Id_SessionPersonneDoc=$rowSessionPersonneDoc['Id'];
												$Fichier_PHP=$rowSessionPersonneDoc['Fichier_PHP'];
											}
										}
									}
									if($repondu==""){
										echo "<tr>";
										echo "<td>".$RowDoc['Document']." (".$Langue.")</td>";
										echo "</tr>";
									}
									else{
										echo "<tr>";
										echo "<td><a href='javascript:OuvreDocument(\"".$Fichier_PHP."\",\"".$Id_SessionPersonneDoc."\");'>".$RowDoc['Document']." (".$Langue.")</a></td>";
										echo "</tr>";
									}
								 }
							}
						}
					}
					
					if($LigneQualification['AttestationFormation']<>"" && $LigneQualification['Id_Besoin']==0){
					?>	
					<tr>
						<td>
							<table>
								<tr><td height="4" ></td></tr>
								<tr>
									<td class="Libelle">
										<?php if($LangueAffichage=="FR"){echo "Attestation de formation";}else{echo "Training certificate";}?>
									</td>
									<td>
										<?php
											if($LangueAffichage=="FR"){
												echo "<a class=\"Info\" href=\"../Competences/AttestationsFormations/".$LigneQualification['AttestationFormation']."\"><img src='../../Images/certificat.jpg' style='border:0;width:25px;' title='Attestation'></a>";
											}
											else{
												echo "<a class=\"Info\" href=\"../Competences/AttestationsFormations/".$LigneQualification['AttestationFormation']."\"><img src='../../Images/certificat.jpg' style='border:0;width:25px;' title='Certificate'>></a>";
											}
										?>
									</td>
								</tr>
								<tr><td height="4" ></td></tr>
							</table>
						</td>
					</tr>
					<?php
					}
					if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS) || DroitsFormationPrestation($TableauIdPostesCQ)){
					?>
					<tr><td height="4" ></td></tr>
					<tr>
						<td class="Libelle" valign="top">
							<?php if($LangueAffichage=="FR"){echo "QCM surveillance";}else{echo "MCQ oversight";}?>
						</td>
						<?php 
							$req="SELECT form_session_personne_qualification.Id,Id_Session_Personne, Id_Qualification,
								Resultat, ResultatMere, Etat,
								Id_QCM, Id_LangueQCM,
								(SELECT Code FROM form_qcm WHERE form_qcm.Id=form_session_personne_qualification.Id_QCM) AS CodeQCM,
								(SELECT CONCAT('(',Libelle,')') FROM form_langue WHERE form_langue.Id=form_session_personne_qualification.Id_LangueQCM) AS Langue,
								Id_QCM_Lie, Id_LangueQCMLie,
								(SELECT Code FROM form_qcm WHERE form_qcm.Id=form_session_personne_qualification.Id_QCM_Lie) AS CodeQCMLie,
								(SELECT CONCAT('(',Libelle,')') FROM form_langue WHERE form_langue.Id=form_session_personne_qualification.Id_LangueQCMLie) AS LangueLie,
								Id_Repondeur, DateHeureRepondeur
								Id_Ouvreur, DateHeureOuverture, DateHeureFermeture 
							FROM form_session_personne_qualification 
							WHERE form_session_personne_qualification.Suppr=0 
							AND form_session_personne_qualification.Id_Relation=".$LigneQualification['Id'];
							$resultSessionsPersonne=mysqli_query($bdd,$req);
							$nbSessionPersonne=mysqli_num_rows($resultSessionsPersonne);
							
							$QCM="";
							$QCMLie="";
							$QCMSurveillance="";
							$checked="";
							$Id_SessionPersonneQualification=0;
							$resultat="";
							$resultatMere="";
							$repondu=0;
							$Etat="";
							$QCMExistant=0;
							$Id_QCMSurveillance=0;
							if($nbSessionPersonne>0){
								mysqli_data_seek($resultSessionsPersonne,0);
								while($rowSessionPersonne=mysqli_fetch_array($resultSessionsPersonne)){
									$QCM=$rowSessionPersonne['CodeQCM']." ".$rowSessionPersonne['Langue']."";
									$QCMLie=$rowSessionPersonne['CodeQCMLie']." ".$rowSessionPersonne['LangueLie']."";
									$Id_QCMSurveillance=$rowSessionPersonne['Id_QCM'];
									$QCMSurveillance=$QCM."<br>".$QCMLie;
									if($rowSessionPersonne['DateHeureOuverture']>"0001-01-01" && $rowSessionPersonne['DateHeureFermeture']<="0001-01-01"){
										$checked="checked";
									}
									
									$Id_SessionPersonneQualification=$rowSessionPersonne['Id'];
									if($rowSessionPersonne['Id_Repondeur']>0){
										$repondu=1;
										if($rowSessionPersonne['CodeQCMLie']<>""){
											if($LangueAffichage=="FR"){$resultatMere="QCM mère : ".$rowSessionPersonne['ResultatMere']."<br>";}
											else{$resultatMere="MCQ mother : ".$rowSessionPersonne['ResultatMere']."<br>";}
											if($LangueAffichage=="FR"){$resultat="Note finale : ";}
										else{$resultat="Final note : ";}
										}
										$resultat.=$rowSessionPersonne['Resultat'];
										if($LangueAffichage=="FR"){
											if($rowSessionPersonne['Etat']==1){$Etat="<br><font color='#2dbe29'>Réussite</font>";}
											elseif($rowSessionPersonne['Etat']==-1){$Etat="<br><font color='#e80000'>Echec</font>";}
										}
										else{
											if($rowSessionPersonne['Etat']==1){$Etat="<br><font color='#2dbe29'>Success</font>";}
											elseif($rowSessionPersonne['Etat']==-1){$Etat="<br><font color='#e80000'>Failure</font>";}
										}
									}
								}
							}
							if($repondu==1){
								echo "<td><a href='javascript:QCM_Web(\"".$Id_SessionPersonneQualification."\");'>".$QCM."<br>".$QCMLie."</a></td>";
							}
						?>
					</tr>
					<?php
						}
					?>
					<tr>
						<td colspan="4">
							<table>
								<tr><td height="4" ></td></tr>
								<tr>
									<td class="Libelle">
										<?php if($LangueAffichage=="FR"){echo "Qualifications associées";}else{echo "Related qualifications";}?>
									</td>
									<td>
										<?php
											$req="SELECT (SELECT Libelle FROM new_competences_qualification WHERE Id=Id_Qualification_Parrainage) AS Qualif
											FROM new_competences_relation 
											WHERE new_competences_relation.Suppr=0 
											AND Id<>".$Id_Relation."
											AND new_competences_relation.Id_Besoin=".$LigneQualification['Id_Besoin'];
											$resultQualif=mysqli_query($bdd,$req);
											$nbQualif=mysqli_num_rows($resultQualif);
											
											if($nbQualif>0){

												while($rowQualif=mysqli_fetch_array($resultQualif)){
													echo "- ".$rowQualif['Qualif']."<br>";
												}
											}
										?>
									</td>
								</tr>
								<tr><td height="4" ></td></tr>
							</table>
						</td>
					</tr>
					<tr><td height="4" ></td></tr>
					
				</table>
			</td>
		</tr>
		<?php
			if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS) || DroitsFormationPrestation($TableauIdPostesCQ)){
				$req="SELECT Id, Id_QCM
					FROM form_session_personne_qualification
					WHERE Id_Relation=".$LigneQualification['Id']." ";
					$resultSessionsPersonneQ=mysqli_query($bdd,$req);
					$nbSessionPersonneQ=mysqli_num_rows($resultSessionsPersonneQ);
					
					if($nbSessionPersonneQ>0){
		?>
		<tr>
			<td>
				<table style="width:100%; align:center;" class="TableCompetences">
					<tr><td height="4" ></td></tr>
					<tr>
						<td class="Libelle" valign="top" width="50%">
							<?php if($LangueAffichage=="FR"){echo "Historique des QCM de surveillance";}else{echo "History of surveillance MCQs";}?>
						</td>
						<td class="Libelle" valign="top" width="50%">
							<?php if($LangueAffichage=="FR"){echo "Note";}else{echo "Score";}?>
						</td>
					</tr>
					<?php 
							while($rowSessionPersonneQ=mysqli_fetch_array($resultSessionsPersonneQ)){
								$req="
								SELECT form_session_personne_qualification_question_reponse.Id
								FROM
								(SELECT Id 
								FROM form_session_personne_qualification_question 
								WHERE Id_QCM=".$rowSessionPersonneQ['Id_QCM']." 
								AND Id_Session_Personne_Qualification=".$rowSessionPersonneQ['Id'].") AS TAB 
								LEFT JOIN form_session_personne_qualification_question_reponse
								ON form_session_personne_qualification_question_reponse.Id_Session_Personne_Qualification_Question=TAB.Id
								WHERE Valeur=1 ";
								$resultSessionsPersonneQR=mysqli_query($bdd,$req);
								$nbSessionPersonneQR=mysqli_num_rows($resultSessionsPersonneQR);
								if($nbSessionPersonneQR>0){
									echo "<tr>
											<td><a href='javascript:QCM_WebHistorique(".$rowSessionPersonneQ['Id'].");'>".$QCMSurveillance."</a></td>
											<td>".calculResultatQCMsEchoue($rowSessionPersonneQ['Id'])." %</td>
										</tr>";
								}
							}
					?>
					<tr><td height="4" ></td></tr>
				</table>
			</td>
		</tr>
		<?php
					}
			}
		?>
		
		<?php
		$req="SELECT DISTINCT DateSurveillance,NumSurveillanceSODA
			FROM new_competences_relation_surveillance
			WHERE Id_Relation=".$LigneQualification['Id']." 
			ORDER BY DateSurveillance DESC";
			$resultSessionsPersonneQ=mysqli_query($bdd,$req);
			$nbSessionPersonneQ=mysqli_num_rows($resultSessionsPersonneQ);
			
			if($nbSessionPersonneQ>0){
		?>
		<tr>
			<td>
				<table style="width:100%; align:center;" class="TableCompetences">
					<tr><td height="4" ></td></tr>
					<tr>
						<td class="Libelle" valign="top" width="50%">
							<?php if($LangueAffichage=="FR"){echo "Historique des surveillances";}else{echo "History of surveillances";}?>
						</td>
					</tr>
					<?php 
							while($rowSessionPersonneQ=mysqli_fetch_array($resultSessionsPersonneQ)){
								echo "<tr>
										<td>".AfficheDateJJ_MM_AAAA($rowSessionPersonneQ['DateSurveillance'])."</td>
										<td>".$rowSessionPersonneQ['NumSurveillanceSODA']."</td>
									</tr>";
							}
					?>
					<tr><td height="4" ></td></tr>
				</table>
			</td>
		</tr>
		<?php
			}
		?>
	</table>
</body>
</html>
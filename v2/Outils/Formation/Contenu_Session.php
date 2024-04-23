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
	<title>Formations - Lister les personnes d'une session</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script type="text/javascript">
		function EmailConvocation(Id,champs)
		{
			Confirm=true;
			if(champs != "")
			{
    			if(document.getElementById('Langue').value=="FR"){Confirm=window.confirm('Etes-vous sûr de vouloir envoyer la convocation sans les informations suivantes de renseignées : '+champs+' ?');}
    			else{Confirm=window.confirm('Are you sure you want to send the invitation without the following information : '+champs+' ?');}
			}
			if(Confirm==true){window.open("Convocation_Formation.php?ancre="+document.getElementById('ancre').value+"&Page=Contenu_Session&Id="+Id+"&Id_Plateforme="+document.getElementById('Id_Plateforme').value,"Convocation_Formation","status=no,menubar=no,width=420,height=250,scrollbars=yes");}
		}
		function EditerFichePresence(Id)
		{
			window.open("EditerFichePresence.php?Id="+Id,"Fiche_Presence","status=no,menubar=no,width=20,height=20");
		}
		function EditerFichePresenceGroupe(Id)
		{
			window.open("EditerFichePresenceGroupe.php?Id="+Id,"Fiche_Presence","status=no,menubar=no,width=20,height=20");
		}
		function EditerFichePresenceSignee(Id)
		{
			window.open("EditerFichePresenceSignee.php?Id="+Id,"Fiche_Presence","status=no,menubar=no,width=20,height=20");
		}
		function Cocher(CaseACocher,Valeur,Indice)
		{
			var ValeurContraire=0;
			var Tableau_Valeurs;
			var CheckBox=document.getElementsByName(CaseACocher+"[]");
			
			if(Valeur==0){ValeurContraire=1;}
			if(CaseACocher!="Suppr")
			{
				document.getElementsByName(CaseACocher+"_Toutes_"+ValeurContraire)[0].checked=false;
				if((Valeur==0) && Indice!=-1){document.getElementsByName(CaseACocher+"_Toutes_"+Valeur)[0].checked=false;}
			}
			for(var i=0;i<CheckBox.length;i++)
			{
				Tableau_Valeurs=CheckBox[i].value.split("|");
				if(Indice==-1)
				{
					if(Tableau_Valeurs[0]==Valeur){CheckBox[i].checked=true;}
					else{CheckBox[i].checked=false;}
				}
				else
				{
					if(Tableau_Valeurs[4]==Indice && (Tableau_Valeurs[0]==ValeurContraire || Valeur==-1 ||
					Tableau_Valeurs[0]==-1)){CheckBox[i].checked=false;}
					if(Valeur==-1 && Tableau_Valeurs[0]==Valeur && Tableau_Valeurs[4]==Indice){CheckBox[i].checked=true;}
				}
			}
			if(CaseACocher=="Presence"){
				if(Valeur==0 || Valeur==1){
					document.getElementById('semipresence'+Indice).value="";
				}
				else if(Valeur==-1){
					document.getElementsByName(CaseACocher+"_Toutes_0")[0].checked=false;
					document.getElementsByName(CaseACocher+"_Toutes_1")[0].checked=false;
				}
			}
		}
		function Envoyer_Commande(Action){
			document.getElementById('Action').value=Action;
			formulaire_liste_personnes.submit();
		}
		function SupprimerDoc() {
			document.getElementById('SupprDoc').value=1;
			formulaire_liste_personnes.submit();
		}

		function OuvreFenetreProfil(Mode,Id)
			{ var w = window.open("../Competences/Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1040,height=800");
				w.focus();
			}
		function genererAttestation(Id){
			var w=window.open("Generer_Attestation.php?Id="+Id,"PageAttestation","status=no,menubar=no,scrollbars=yes,width=90,height=90");
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
            AND form_session_personne.Validation_Inscription>-1
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
		if($_POST['Action']!="MAJ_Notes_QCM" && $_POST['Action']!="MAJ_Couts" && $_POST['Action']!="Validation_CoutDesincription"  && $_POST['Action']!="Validation_Motif"  && isset($_POST[$_POST['Action']]))
		{
			foreach($_POST[$_POST['Action']] as $valeur)
			{
				//Mise en variables des tableaux
				$TableauValeurs=explode("|",$valeur);
				$TableauValeur_Valeur=$TableauValeurs[0];
				$TableauValeur_IdSessionPersonne=$TableauValeurs[1];
				$TableauValeur_IdBesoin=$TableauValeurs[2];
				$TableauValeur_IdPersonne=$TableauValeurs[3];
				if(count($TableauValeurs)==5){
					$TableauValeur_IdSessionPersonneQualification=$TableauValeurs[4];
				}
				
				switch($_POST['Action'])
				{
					case "Suppr":
						desinscrire_candidat($TableauValeur_IdBesoin, $TableauValeur_IdSessionPersonne);
						break;
					case "Presence":
						$val=$TableauValeur_Valeur;
						if($TableauValeur_Valeur==-1)
						{
							//Semi présence
							//Vérifier si le champs de semi-présence est renseigné
							if($_POST['semipresence'.$TableauValeur_IdSessionPersonneQualification]<>"")
							{
								$tabHeure = explode(":",$_POST['semipresence'.$TableauValeur_IdSessionPersonneQualification]);
								$minutes=0;
								$heures=$tabHeure[0];
								if(sizeof($tabHeure)>1){$minutes=$tabHeure[1];}
								$heurePresence = date("H:i:s",mktime(intval($heures), intval($minutes), 0, 0, 0, 0));
								$ReqActionSessionPersonne="UPDATE form_session_personne SET ".$_POST['Action']."=-2,SemiPresence='".$heurePresence."', Id_ValPresence=".$IdPersonneConnectee.", Date_ValPresence='".date('Y-m-d')."' WHERE Id=".$TableauValeur_IdSessionPersonne;
								$ResultActionSessionPersonne=mysqli_query($bdd,$ReqActionSessionPersonne);
							}
						}
						else
						{
							//1 validation | -1 refus
							if($TableauValeur_Valeur==0){$val=-1;}
							$ReqActionSessionPersonne="UPDATE form_session_personne SET ".$_POST['Action']."=".$val.", Id_ValPresence=".$IdPersonneConnectee.", Date_ValPresence='".date('Y-m-d')."' WHERE Id=".$TableauValeur_IdSessionPersonne;
							$ResultActionSessionPersonne=mysqli_query($bdd,$ReqActionSessionPersonne);
						}
						//Si absence
						if($TableauValeur_Valeur==0 || $TableauValeur_Valeur==-1)
						{
							//Mise à jour de la table besoin
							$ReqBesoinMAJ="UPDATE form_besoin SET Traite=3 WHERE Id=".$TableauValeur_IdBesoin;
							$ResultBesoinMAJ=mysqli_query($bdd,$ReqBesoinMAJ);
							$bEvaluation=0;
							
							$req="SELECT Id FROM form_besoin WHERE Motif='Renouvellement' AND Id=".$TableauValeur_IdBesoin;
							$resultB=mysqli_query($bdd,$req);
							$nbB=mysqli_num_rows($resultB);
	
							if($NBResultSessionQualificationQCM)
							{
								mysqli_data_seek($ResultSessionQualificationQCM,0);
								$QualificationPrecedente="";
								while($RowSessionQualificationQCM=mysqli_fetch_array($ResultSessionQualificationQCM))
								{
									//Affichage des qualifications et des QCM associés à la personne pour la formation de cette session
									if($RowSessionQualificationQCM['ID_PERSONNE']==$TableauValeur_IdPersonne)
									{
										//Qualifications
										//Etant donné qu'il peut y avoir plusieurs QCM alors on vérifie pour ne prendre en compte qu'une seule qualification
										if($QualificationPrecedente!=$RowSessionQualificationQCM['ID_QUALIFICATION'])
										{
											$leMotif="Suite à absence";
											if($nbB>0){
												$leMotif="Renouvellement";
											}
											Set_EvaluationNote($TableauValeur_IdBesoin, $TableauValeur_IdPersonne, $RowSessionQualificationQCM['ID_QUALIFICATION'], $RowSessionQualificationQCM['ID_SESSION_PERSONNE_QUALIFICATION'], 0, 0, $_POST['Id_TypeFormation'],$leMotif,true);
										}
									}
								}
							}
							
							$leMotif="Suite à absence";
							if($nbB>0){
								$leMotif="Renouvellement";
							}
							Creer_BesoinsFormations_PersonnePrestationMetier($TableauValeur_IdPersonne, 0, 0, 0, $leMotif, $TableauValeur_IdBesoin);	
							
							//ENVOYER MAIL POUR PREVENIR DE L'ABSENCE
							$reqSessionFormation = "
								SELECT
										form_formation.Reference, form_session_date.DateSession
								FROM
										form_session,
										form_formation,
										form_session_date
								WHERE
										form_session.Id_Formation = form_formation.Id
										AND form_session.Id = form_session_date.Id_Session
										AND form_session.Id = ".$ID."
								ORDER BY 
										form_session_date.DateSession ;";
							
							$resultSessionFormation = getRessource($reqSessionFormation);
								$dates1="(";
								while($rowSessionFormation=mysqli_fetch_array($resultSessionFormation)){
									$dates1.=AfficheDateJJ_MM_AAAA($rowSessionFormation['DateSession'])." - ";
								}
								$dates1=substr($dates1,0,-3);
								$dates1.=")";
									
							$reqPersonne = "
								SELECT
									Nom, Prenom, EmailPro
								FROM
									new_rh_etatcivil
								WHERE
									Id = ".$TableauValeur_IdPersonne.";";
							
							$rowPersonne = mysqli_fetch_array(getRessource($reqPersonne));
							
							$postes = array($IdPosteAssistantFormationExterne);
							$personnes = array($TableauValeur_IdPersonne);
							
							$ressource = getRessource(getChaineSQL_getMailsResponsables($personnes, $TableauIdPostesCHE_COOE));
							$destinataires = getMailsDestinataires($ressource);
							
							//Ajout de l'adresse mail de la personne concernée
							if($rowPersonne['EmailPro']<>""){
								$destinataires.=",".$rowPersonne['EmailPro'];
							}
							
							$Formation="";
							$Organisme="";
							$Recyclage=0;
							$req=Get_SQL_InformationsPourFormation($RowSessionInfoMinime['Id_Plateforme'], $RowSessionInfoMinime['Id_Formation']);
							$resultFormation=mysqli_query($bdd,$req);
							$nbFormation=mysqli_num_rows($resultFormation);
							if($nbFormation>0)
							{
								$rowForm=mysqli_fetch_array($resultFormation);
								if($rowForm['Organisme']<>""){$Organisme=" (".stripslashes($rowForm['Organisme']).")";}
								if($Recyclage==0){$Formation=$rowForm['Libelle'];}
								else{$Formation=$rowForm['LibelleRecyclage'];}
							}
							
							$InfosFormationLiee="";
							if($_POST['FormationLiee']==1 && $_POST['Id_GroupeSession']>0)
							{
								$req="SELECT form_session.Id,
									form_session.Id_Formation,form_session.Recyclage  ";
								$req.="FROM form_session_groupe ";
								$req.="LEFT JOIN form_session ON form_session_groupe.Id=form_session.Id_GroupeSession ";
								$req.="WHERE form_session.Id<>".$ID." 
									AND form_session.Suppr=0 
									AND form_session.Id_GroupeSession=".$_POST['Id_GroupeSession'];
								$resultLiee=getRessource($req);
								while($rowLiee=mysqli_fetch_array($resultLiee)){
									$Formation2="";
									$Organisme2="";
									$req=Get_SQL_InformationsPourFormation($RowSessionInfoMinime['Id_Plateforme'], $rowLiee['Id_Formation']);
									$resultFormation=mysqli_query($bdd,$req);
									$nbFormation=mysqli_num_rows($resultFormation);
									if($nbFormation>0)
									{
										$rowForm=mysqli_fetch_array($resultFormation);
										if($rowForm['Organisme']<>""){$Organisme2=" (".stripslashes($rowForm['Organisme']).")";}
										if($rowLiee['Recyclage']==0){$Formation2=$rowForm['Libelle'];}
										else{$Formation2=$rowForm['LibelleRecyclage'];}
									}
									$reqSessionFormation = "
										SELECT
												form_session_date.DateSession
										FROM
												form_session,
												form_formation,
												form_session_date
										WHERE
												form_session.Id_Formation = form_formation.Id
												AND form_session.Id = form_session_date.Id_Session
												AND form_session.Id = ".$rowLiee['Id']."
										ORDER BY form_session_date.DateSession;";
									$resultLiee2=getRessource($reqSessionFormation);
									$dates="(";
									while($rowLiee2=mysqli_fetch_array($resultLiee2)){
										$dates.=AfficheDateJJ_MM_AAAA($rowLiee2['DateSession'])." - ";
									}
									$dates=substr($dates,0,-3);
									$dates.=")";
									$InfosFormationLiee.="<br>- ".$Formation2.$Organisme2." - ".$dates ;
								}
							}
							
							//Elaboration du mail
							$Headers='From: "QUALIPSO"<qualipso@aaa-aero.com>'."\n";
							$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
							if($TableauValeur_Valeur == 0)
							{ //Refus
								if($LangueAffichage=="FR")
								{
									$ObjetConvocation = "Absence en formation ".$Formation.$Organisme;
									$Message="	<html>
										<head><title>Absence en formation ".$Formation.$Organisme."</title></head>
										<body>
											Bonjour,
											<br><br>
											<i>Cette boîte mail est une boîte mail générique</i>
											<br><br>
													".$rowPersonne['Prenom']." ".$rowPersonne['Nom']." a été absent lors des formations suivantes : 
													<br>- ".$Formation.$Organisme." - ".$dates1."
													".$InfosFormationLiee."
											<br>
											<br>
											<br>
											Bonne journée.<br>
											Formation Extranet Daher industriel services DIS.
										</body>
									</html>";
								}
								else
								{
									$ObjetConvocation = "Absence in training ".$Formation.$Organisme;
									$Message="	<html>
										<head><title>Absence in training ".$Formation.$Organisme."</title></head>
										<body>
											Hello,
											<br><br>
											<i>This mailbox is a generic mailbox</i>
											<br><br>
														The registration of M. ".$rowPersonne['Prenom']." ".$rowPersonne['Nom']." was absent during the following training sessions : 
														<br>- ".$Formation.$Organisme." - ".$dates1."
														".$InfosFormationLiee."
											<br>
											<br>
											<br>
											Have a nice day.<br>
											Training Extranet Daher industriel services DIS.
										</body>
									</html>";
								}
								
								if($destinataires<>"")
								{
									if(mail($destinataires,$ObjetConvocation,$Message,$Headers,'-f qualipso@aaa-aero.com')){echo "";}
								}
							}
						
						}
						else
						{
							//Mise à jour de la table besoin
							$ReqBesoinMAJ="UPDATE form_besoin SET Traite=4 WHERE Id=".$TableauValeur_IdBesoin;
							$ResultBesoinMAJ=mysqli_query($bdd,$ReqBesoinMAJ);
							
							//On réaffiche dans son profil les qualifications 
							$ReqSuppBCompetences="
								UPDATE
									new_competences_relation
								SET
									Suppr=0,
									Evaluation='',
									Resultat_QCM='',
									Date_QCM='0001-01-01',
									Id_Modificateur=".$IdPersonneConnectee.",
									Date_Modification='".date('Y-m-d')."',
									Date_Debut='0001-01-01',
									Date_Fin='0001-01-01',
									Evaluation='Bi'
								WHERE
									Id_Besoin = ".$TableauValeur_IdBesoin." ";
							$ResultSuppBCompetences=mysqli_query($bdd, $ReqSuppBCompetences);
	
							//On remet les qualifications de la session personne 
							if($NBResultSessionQualificationQCM)
							{
								mysqli_data_seek($ResultSessionQualificationQCM,0);
								$QualificationPrecedente="";
								while($RowSessionQualificationQCM=mysqli_fetch_array($ResultSessionQualificationQCM))
								{
									//Affichage des qualifications et des QCM associés à la personne pour la formation de cette session
									if($RowSessionQualificationQCM['ID_PERSONNE']==$TableauValeur_IdPersonne)
									{
										//Qualifications
										//Etant donné qu'il peut y avoir plusieurs QCM alors on vérifie pour ne prendre en compte qu'une seule qualification
										if($QualificationPrecedente!=$RowSessionQualificationQCM['ID_QUALIFICATION'])
										{
											$ReqSessionPersonneQualificationMAJ="
												UPDATE
													form_session_personne_qualification
												SET
													Resultat='',
													Etat=0
												WHERE
													Id=".$RowSessionQualificationQCM['ID_SESSION_PERSONNE_QUALIFICATION'];
											$ResultSessionPersonneQualificationMAJ=mysqli_query($bdd,$ReqSessionPersonneQualificationMAJ);
										
										}
									}
								}
							}
							
							
							$ReqBesoin="
								SELECT
									Id_Demandeur,
									Id_Prestation,
									Id_Pole,
									Id_Formation,
									Id_Personne,
									Commentaire,
									Obligatoire,
									Id_Valideur,
									Motif
								FROM
									form_besoin
								WHERE
									Id=".$TableauValeur_IdBesoin;
							$ResultBesoin=mysqli_query($bdd,$ReqBesoin);
							$RowBesoin=mysqli_fetch_array($ResultBesoin);

							//Suppression des B
							$ReqSuppBCompetences="
								UPDATE
									new_competences_relation
								SET
									Suppr=1
								WHERE
									Id_Besoin IN
									(
										SELECT
											Id
										FROM
											form_besoin
										WHERE
												Id<>".$TableauValeur_IdBesoin." 
											AND Id_Prestation=".$RowBesoin['Id_Prestation']." 
											AND Id_Pole=".$RowBesoin['Id_Pole']." 
											AND Id_Formation=".$RowBesoin['Id_Formation']." 
											AND Obligatoire=".$RowBesoin['Obligatoire']." 
											AND Id_Personne=".$TableauValeur_IdPersonne."
											AND Suppr=0
											AND Traite=0
									)";
							$ResultSuppBCompetences=mysqli_query($bdd, $ReqSuppBCompetences);
							
							//Supprimer besoin créé suite à absence 
							$ReqSuppB="UPDATE form_besoin
								SET Suppr=1,
								Motif_Suppr='Remise en présent'
								WHERE 
									Id<>".$TableauValeur_IdBesoin." 
								AND Id_Prestation=".$RowBesoin['Id_Prestation']." 
								AND Id_Pole=".$RowBesoin['Id_Pole']." 
								AND Id_Formation=".$RowBesoin['Id_Formation']." 
								AND Obligatoire=".$RowBesoin['Obligatoire']." 
								AND Id_Personne=".$TableauValeur_IdPersonne."
								AND Suppr=0
								AND Traite=0
								";
							$ResultSuppB=mysqli_query($bdd, $ReqSuppB);
						}
						
						//Mise à jour du coût s'il est égal à -1
						//Récupération du type de contrat de la personne
						$Contrat="";
						$IdContrat=IdContrat($TableauValeur_IdPersonne,date('Y-m-d'));
						if($IdContrat>0){
							$Contrat=TypeContrat($IdContrat);
						}
						$Cout=-1;
						$CoutTarifGroupe=-1;
						$req="SELECT CoutSalarieAAA, CoutSalarieAAARecyclage, CoutInterimaire, CoutInterimaireRecyclage, CoutTarifGroupe, CoutTarifGroupeRecyclage
						FROM form_formation_plateforme_parametres
						WHERE Id_Formation=".$RowSessionInfoMinime['Id_Formation']." AND Id_Plateforme=".$RowSessionInfoMinime['Id_Plateforme'];
						$resultCout=mysqli_query($bdd,$req);
						$nbCout=mysqli_num_rows($resultCout);
						if($nbCout>0){
							$rowCout=mysqli_fetch_array($resultCout);
							if($RowSessionInfoMinime['Recyclage']==1){
								if($Contrat<>""){
									if($Contrat=="Intérimaire" || $Contrat=="Intérim" || $Contrat=="Alternant intérimaire" || $Contrat==""){$Cout=$rowCout['CoutInterimaireRecyclage'];}
									else{$Cout=$rowCout['CoutSalarieAAARecyclage'];}
									$CoutTarifGroupe=$rowCout['CoutTarifGroupeRecyclage'];
								}
							}
							else{
								if($Contrat<>""){
									if($Contrat=="Intérimaire" || $Contrat=="Intérim" || $Contrat=="Alternant intérimaire" || $Contrat==""){$Cout=$rowCout['CoutInterimaire'];}
									else{$Cout=$rowCout['CoutSalarieAAA'];}
									$CoutTarifGroupe=$rowCout['CoutTarifGroupe'];
								}
							}
						}
						if($Contrat<>""){
							$req="UPDATE form_session_personne SET Cout=".$Cout." WHERE Cout=-1 AND Id=".$TableauValeur_IdSessionPersonne." ";
							$resultUpdt=mysqli_query($bdd,$req);
						}
						
						//Si la session est en tarif de groupe alors partage du cout 
						if($RowSessionInfoMinime['TarifGroupe']==1){
							$req="SELECT Id 
								FROM form_session_personne 
								WHERE Suppr=0 
								AND Validation_Inscription<>-1
								AND Presence<>-1
								AND Id_Session=".$ID." ";
							$resultPersDejaInscrit=mysqli_query($bdd,$req);
							$nbPersDejaInscrit=mysqli_num_rows($resultPersDejaInscrit);
							if($CoutTarifGroupe<>-1){
								$CoutTarifGroupe=$CoutTarifGroupe/$nbPersDejaInscrit;
							}
							
							$req="UPDATE form_session_personne 
							SET Cout=".$CoutTarifGroupe." 
							WHERE Id_Session=".$ID." 
							AND Suppr=0
							AND Validation_Inscription<>-1 
							AND Presence<>-1 ";
							$resultUpdate=mysqli_query($bdd,$req);
							
							$req="UPDATE form_session_personne 
							SET Cout=0
							WHERE Id_Session=".$ID." 
							AND (Validation_Inscription=-1 OR Presence=-1 OR Suppr=1) ";
							$resultUpdate=mysqli_query($bdd,$req);
						}
							
						break;
					case "Validation_Inscription":
						//Vérifier si il reste des places
						$reqPlace="SELECT Id FROM form_session_personne WHERE Id_Session=".$_POST['Id']." AND Validation_Inscription=1 AND Suppr=0";
						$ResultPlace=mysqli_query($bdd,$reqPlace);
						$NBPlace=mysqli_num_rows($ResultPlace);

						if($NBPlace<$_POST['Nb_Stagiaire_Maxi'] || $TableauValeur_Valeur==0)
						{
							$InfosFormationLiee="";
							//Vérifier si la session n'est pas une session liée
							if($_POST['FormationLiee']==1 && $_POST['Id_GroupeSession']>0)
							{
								//Liste des Id_SessionPersonne
								$reqPersSession="SELECT form_session_personne.Id, form_session_personne.Id_Besoin 
									FROM form_session_personne 
									LEFT JOIN form_session
									ON form_session_personne.Id_Session=form_session.Id
									WHERE form_session_personne.Suppr=0 
									AND form_session_personne.Id_Personne=".$TableauValeur_IdPersonne." 
									AND form_session.Id_GroupeSession=".$_POST['Id_GroupeSession']."
									AND form_session_personne.Validation_Inscription<>-1
									AND form_session.Suppr=0 ";
								$ResultIdPersSession=mysqli_query($bdd,$reqPersSession);
								$NBPersSession=mysqli_num_rows($ResultIdPersSession);
								$IdPersSession="";	
								$IdPersBesoin="";
								if($NBPersSession>0){
									while($RowSessionPersonnes=mysqli_fetch_array($ResultIdPersSession))
									{
										$IdPersSession.=$RowSessionPersonnes['Id'].",";
										$IdPersBesoin.=$RowSessionPersonnes['Id_Besoin'].",";
									}
									$IdPersSession=substr($IdPersSession,0,-1);
									$IdPersBesoin=substr($IdPersBesoin,0,-1);
								}
								if($IdPersSession<>"")
								{
									//1 validation | -1 refus
									$val=$TableauValeur_Valeur;
									$traite=2;
									if($TableauValeur_Valeur==0){$val=-1;$traite=0;}
									$ReqActionSessionPersonne="UPDATE form_session_personne 
													SET ".$_POST['Action']."=".$val.", 
													Presence=0,
													Id_Valideur=".$IdPersonneConnectee.", 
													Date_Valideur='".date('Y-m-d')."' ";
									
									//UPDATE DE L'INDICE DU "B" DANS LES QUALIFICATIONS (POUR LE TABLEAU DES COMPETENCES)
									$ReqRelationMAJ="UPDATE new_competences_relation 
												SET Evaluation='',
												Resultat_QCM='',
												Date_QCM='0001-01-01',
												Id_Modificateur=".$IdPersonneConnectee.",
												Date_Modification='".date('Y-m-d')."',
												Date_Debut='0001-01-01',
												Date_Fin='0001-01-01',
												Evaluation='B";
									
									$ReqBesoinMAJ="UPDATE form_besoin SET Traite=".$traite." WHERE Id IN (".$IdPersBesoin.") ";
									if($TableauValeur_Valeur==1){$ReqRelationMAJ.="i";}
									$ResultBesoinMAJ=mysqli_query($bdd,$ReqBesoinMAJ);
									
									//UPDATE DE L'INDICE DU "B" DANS LES QUALIFICATIONS (POUR LE TABLEAU DES COMPETENCES)
									$ReqRelationMAJ.="' WHERE Id_Besoin IN (".$IdPersBesoin.") ";
									$ResultRelationMAJ=mysqli_query($bdd,$ReqRelationMAJ);
									
									//Fin de la requête pour la mise à jour de FORM_SESSION_PERSONNE
									$ReqActionSessionPersonne.=" WHERE Id IN (".$IdPersSession.")" ;
									$ResultActionSessionPersonne=mysqli_query($bdd,$ReqActionSessionPersonne);
									
									$reqActionSessionPersonneQualif="UPDATE form_session_personne_qualification
																	SET Resultat='',
																	ResultatMere='',
																	Etat=0 
																	WHERE Id_Session_Personne IN (".$IdPersSession.") ";
									$ResultActionSessionPersonneQualif=mysqli_query($bdd,$reqActionSessionPersonneQualif);
									
								}
							}
							else
							{
								//1 validation | -1 refus
								$val=$TableauValeur_Valeur;
								$traite=2;
								if($TableauValeur_Valeur==0){$val=-1;$traite=0;}
								$ReqActionSessionPersonne="UPDATE form_session_personne SET ".$_POST['Action']."=".$val.", Id_Valideur=".$IdPersonneConnectee.", Date_Valideur='".date('Y-m-d')."' ";
								//UPDATE DE L'INDICE DU "B" DANS LES QUALIFICATIONS (POUR LE TABLEAU DES COMPETENCES)
								$ReqRelationMAJ="UPDATE new_competences_relation SET Date_Debut='0001-01-01',
												Date_Fin='0001-01-01',
												Resultat_QCM='',
												Date_QCM='0001-01-01', Evaluation='B";
								
								$ReqBesoinMAJ="UPDATE form_besoin SET Traite=".$traite." WHERE Id=".$TableauValeur_IdBesoin;
								if($TableauValeur_Valeur==1){$ReqRelationMAJ.="i";}
								$ResultBesoinMAJ=mysqli_query($bdd,$ReqBesoinMAJ);
								
								//UPDATE DE L'INDICE DU "B" DANS LES QUALIFICATIONS (POUR LE TABLEAU DES COMPETENCES)
								$ReqRelationMAJ.="' WHERE Id_Besoin=".$TableauValeur_IdBesoin;
								$ResultRelationMAJ=mysqli_query($bdd,$ReqRelationMAJ);
								
								//Fin de la requête pour la mise à jour de FORM_SESSION_PERSONNE
								$ReqActionSessionPersonne.=" WHERE Id=".$TableauValeur_IdSessionPersonne;
								$ResultActionSessionPersonne=mysqli_query($bdd,$ReqActionSessionPersonne);
							}
							
							$reqSessionFormation = "
								SELECT
										form_formation.Reference, form_session_date.DateSession
								FROM
										form_session,
										form_formation,
										form_session_date
								WHERE
										form_session.Id_Formation = form_formation.Id
										AND form_session.Id = form_session_date.Id_Session
										AND form_session.Id = ".$ID."
								ORDER BY 
										form_session_date.DateSession ;";
							
							$resultSessionFormation = getRessource($reqSessionFormation);
								$dates1="(";
								while($rowSessionFormation=mysqli_fetch_array($resultSessionFormation)){
									$dates1.=AfficheDateJJ_MM_AAAA($rowSessionFormation['DateSession'])." - ";
								}
								$dates1=substr($dates1,0,-3);
								$dates1.=")";
									
							$reqPersonne = "
								SELECT
									Nom, Prenom, EmailPro
								FROM
									new_rh_etatcivil
								WHERE
									Id = ".$TableauValeur_IdPersonne.";";
							
							$rowPersonne = mysqli_fetch_array(getRessource($reqPersonne));
							
							$postes = array($IdPosteAssistantFormationExterne);
							$personnes = array($TableauValeur_IdPersonne);
							
							$ressource = getRessource(getChaineSQL_getMailsResponsables($personnes, $TableauIdPostesCHE_COOE));
							$destinataires = getMailsDestinataires($ressource);
							
							//Ajout de l'adresse mail de la personne concernée
							if($rowPersonne['EmailPro']<>""){
								$destinataires.=",".$rowPersonne['EmailPro'];
							}
							
							$Formation="";
							$Organisme="";
							$Recyclage=0;
							$req=Get_SQL_InformationsPourFormation($RowSessionInfoMinime['Id_Plateforme'], $RowSessionInfoMinime['Id_Formation']);
							$resultFormation=mysqli_query($bdd,$req);
							$nbFormation=mysqli_num_rows($resultFormation);
							if($nbFormation>0)
							{
								$rowForm=mysqli_fetch_array($resultFormation);
								if($rowForm['Organisme']<>""){$Organisme=" (".stripslashes($rowForm['Organisme']).")";}
								if($Recyclage==0){$Formation=$rowForm['Libelle'];}
								else{$Formation=$rowForm['LibelleRecyclage'];}
							}
							
							$InfosFormationLiee="";
							if($_POST['FormationLiee']==1 && $_POST['Id_GroupeSession']>0)
							{
								$req="SELECT form_session.Id,
									form_session.Id_Formation,form_session.Recyclage  ";
								$req.="FROM form_session_groupe ";
								$req.="LEFT JOIN form_session ON form_session_groupe.Id=form_session.Id_GroupeSession ";
								$req.="WHERE form_session.Id<>".$ID." 
									AND form_session.Suppr=0 
									AND form_session.Id_GroupeSession=".$_POST['Id_GroupeSession'];
								$resultLiee=getRessource($req);
								while($rowLiee=mysqli_fetch_array($resultLiee)){
									$Formation2="";
									$Organisme2="";
									$req=Get_SQL_InformationsPourFormation($RowSessionInfoMinime['Id_Plateforme'], $rowLiee['Id_Formation']);
									$resultFormation=mysqli_query($bdd,$req);
									$nbFormation=mysqli_num_rows($resultFormation);
									if($nbFormation>0)
									{
										$rowForm=mysqli_fetch_array($resultFormation);
										if($rowForm['Organisme']<>""){$Organisme2=" (".stripslashes($rowForm['Organisme']).")";}
										if($rowLiee['Recyclage']==0){$Formation2=$rowForm['Libelle'];}
										else{$Formation2=$rowForm['LibelleRecyclage'];}
									}
									$reqSessionFormation = "
										SELECT
												form_session_date.DateSession
										FROM
												form_session,
												form_formation,
												form_session_date
										WHERE
												form_session.Id_Formation = form_formation.Id
												AND form_session.Id = form_session_date.Id_Session
												AND form_session.Id = ".$rowLiee['Id']."
										ORDER BY form_session_date.DateSession;";
									$resultLiee2=getRessource($reqSessionFormation);
									$dates="(";
									while($rowLiee2=mysqli_fetch_array($resultLiee2)){
										$dates.=AfficheDateJJ_MM_AAAA($rowLiee2['DateSession'])." - ";
									}
									$dates=substr($dates,0,-3);
									$dates.=")";
									$InfosFormationLiee.="<br>- ".$Formation2.$Organisme2." - ".$dates ;
								}
							}
							
							//Elaboration du mail
							$Headers='From: "QUALIPSO"<qualipso@aaa-aero.com>'."\n";
							$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
							if($TableauValeur_Valeur == 0)
							{ //Refus
								if($LangueAffichage=="FR")
								{
									$ObjetConvocation = "Inscription refusée ".$Formation.$Organisme;
									$Message="	<html>
										<head><title>Inscription refusée ".$Formation.$Organisme."</title></head>
										<body>
											Bonjour,
											<br><br>
											<i>Cette boîte mail est une boîte mail générique</i>
											<br><br>
													L'inscription de M. ".$rowPersonne['Prenom']." ".$rowPersonne['Nom']." a été refusée pour les formations suivantes : 
													<br>- ".$Formation.$Organisme." - ".$dates1."
													".$InfosFormationLiee."
											<br>
											<br>
											<br>
											Bonne journée.<br>
											Formation Extranet Daher industriel services DIS.
										</body>
									</html>";
								}
								else
								{
									$ObjetConvocation = "Registration refused ".$Formation.$Organisme;
									$Message="	<html>
										<head><title>Registration refused ".$Formation.$Organisme."</title></head>
										<body>
											Hello,
											<br><br>
											<i>This mailbox is a generic mailbox</i>
											<br><br>
														The registration of M. ".$rowPersonne['Prenom']." ".$rowPersonne['Nom']." has been refused for the following courses : 
														<br>- ".$Formation.$Organisme." - ".$dates1."
														".$InfosFormationLiee."
											<br>
											<br>
											<br>
											Have a nice day.<br>
											Training Extranet Daher industriel services DIS.
										</body>
									</html>";
								}
								
								if($destinataires<>"")
								{
									if(mail($destinataires,$ObjetConvocation,$Message,$Headers,'-f qualipso@aaa-aero.com')){echo "";}
								}
							}
							
							//Si la session est en tarif de groupe alors partage du cout 
							if($_POST['FormationLiee']==1 && $_POST['Id_GroupeSession']>0)
							{
								//Liste des Id_SessionPersonne
								$reqPersSession="SELECT form_session_personne.Id
									FROM form_session_personne 
									LEFT JOIN form_session
									ON form_session_personne.Id_Session=form_session.Id
									WHERE form_session_personne.Suppr=0 
									AND form_session.Id_GroupeSession=".$_POST['Id_GroupeSession']."
									AND form_session_personne.Validation_Inscription<>-1
									AND form_session.Suppr=0 ";
								$ResultIdPersSession=mysqli_query($bdd,$reqPersSession);
								$NBPersSession=mysqli_num_rows($ResultIdPersSession);
								if($NBPersSession>0){
									while($RowSessionPersonnes=mysqli_fetch_array($ResultIdPersSession))
									{
										$req="SELECT form_session.Id,form_session.Id,form_session.Id_GroupeSession,form_session.Formation_Liee,form_session.Nb_Stagiaire_Maxi,form_session.Id_Formation,  ";
										$req.="form_session.Recyclage,form_session.TarifGroupe,Id_Plateforme ";
										$req.="FROM form_session 
											LEFT JOIN form_session_personne
											ON form_session_personne.Id_Session=form_session.Id 
											WHERE form_session_personne.Id =".$RowSessionPersonnes['Id']." ";
										$resultSessionDate=mysqli_query($bdd,$req);
										$rowSessionForm=mysqli_fetch_array($resultSessionDate);
											
										$req="SELECT CoutSalarieAAA, CoutSalarieAAARecyclage, CoutInterimaire, CoutInterimaireRecyclage , CoutTarifGroupe, CoutTarifGroupeRecyclage
										FROM form_formation_plateforme_parametres
										WHERE Id_Formation=".$rowSessionForm['Id_Formation']." AND Id_Plateforme=".$rowSessionForm['Id_Plateforme'];
										$resultCout=mysqli_query($bdd,$req);
										$nbCout=mysqli_num_rows($resultCout);
										if($nbCout>0){
											$rowCout=mysqli_fetch_array($resultCout);
											if($rowSessionForm['Recyclage']==1){
												$CoutTarifGroupe=$rowCout['CoutTarifGroupeRecyclage'];
											}
											else{
												$CoutTarifGroupe=$rowCout['CoutTarifGroupe'];
											}
										}
										
										//Si la session est en tarif de groupe alors partage du cout 
										if($rowSessionForm['TarifGroupe']==1){
											$req="SELECT Id 
												FROM form_session_personne 
												WHERE Suppr=0 
												AND Validation_Inscription<>-1
												AND Id_Session=".$rowSessionForm['Id']." ";
											$resultPersDejaInscrit=mysqli_query($bdd,$req);
											$nbPersDejaInscrit=mysqli_num_rows($resultPersDejaInscrit);
											if($CoutTarifGroupe<>-1){
												$CoutTarifGroupe=$CoutTarifGroupe/$nbPersDejaInscrit;
											}
											
											$req="UPDATE form_session_personne 
											SET Cout=".$CoutTarifGroupe." 
											WHERE Id_Session=".$rowSessionForm['Id']." 
											AND Suppr=0
											AND Validation_Inscription<>-1 ";
											$resultUpdate=mysqli_query($bdd,$req);
											
											$req="UPDATE form_session_personne 
											SET Cout=0
											WHERE Id_Session=".$rowSessionForm['Id']." 
											AND (Validation_Inscription=-1 OR Presence=-1 OR Suppr=1) ";
											$resultUpdate=mysqli_query($bdd,$req);
										}
									}

								}
							}
							else{
								$req="SELECT form_session.Id,form_session.Id,form_session.Id_GroupeSession,form_session.Formation_Liee,form_session.Nb_Stagiaire_Maxi,form_session.Id_Formation,  ";
								$req.="form_session.Recyclage,form_session.TarifGroupe,Id_Plateforme ";
								$req.="FROM form_session 
									LEFT JOIN form_session_personne
									ON form_session_personne.Id_Session=form_session.Id 
									WHERE form_session_personne.Id =".$TableauValeur_IdSessionPersonne." ";
								$resultSessionDate=mysqli_query($bdd,$req);
								$rowSessionForm=mysqli_fetch_array($resultSessionDate);
									
								$req="SELECT CoutSalarieAAA, CoutSalarieAAARecyclage, CoutInterimaire, CoutInterimaireRecyclage , CoutTarifGroupe, CoutTarifGroupeRecyclage
								FROM form_formation_plateforme_parametres
								WHERE Id_Formation=".$rowSessionForm['Id_Formation']." AND Id_Plateforme=".$rowSessionForm['Id_Plateforme'];
								$resultCout=mysqli_query($bdd,$req);
								$nbCout=mysqli_num_rows($resultCout);
								if($nbCout>0){
									$rowCout=mysqli_fetch_array($resultCout);
									if($rowSessionForm['Recyclage']==1){
										$CoutTarifGroupe=$rowCout['CoutTarifGroupeRecyclage'];
									}
									else{
										$CoutTarifGroupe=$rowCout['CoutTarifGroupe'];
									}
								}
								
								//Si la session est en tarif de groupe alors partage du cout 
								if($rowSessionForm['TarifGroupe']==1){
									$req="SELECT Id 
										FROM form_session_personne 
										WHERE Suppr=0 
										AND Validation_Inscription<>-1
										AND Id_Session=".$rowSessionForm['Id']." ";
									$resultPersDejaInscrit=mysqli_query($bdd,$req);
									$nbPersDejaInscrit=mysqli_num_rows($resultPersDejaInscrit);
									if($CoutTarifGroupe<>-1){
										$CoutTarifGroupe=$CoutTarifGroupe/$nbPersDejaInscrit;
									}
									
									$req="UPDATE form_session_personne 
									SET Cout=".$CoutTarifGroupe." 
									WHERE Id_Session=".$rowSessionForm['Id']." 
									AND Suppr=0
									AND Validation_Inscription<>-1 ";
									$resultUpdate=mysqli_query($bdd,$req);
								}
							}
						}
						
						
						
						
						break;
					case "Etat_Qualification":
						//Note des qualifications
						if($TableauValeur_Valeur==""){$TableauValeur_Valeur=0;}
						
						$TableauValeurs=explode("|",$_POST['Liste_IDSessionPersonneQualification']);
						foreach($TableauValeurs as $ValeursCroisees)
						{
							$valeur=explode("#",$ValeursCroisees);
							//En fonction de la personne sur laquelle on fait le mise à jour au dessus
							if($TableauValeur_IdSessionPersonneQualification==$valeur[0])
							{
								Set_EvaluationNote($valeur[1], $valeur[3], $valeur[2], $valeur[0], $_POST['Resultat_Qualification_'.$valeur[0]], $TableauValeur_Valeur, $_POST['Id_TypeFormation'],'Suite à échec',true);
							}
						}
						break;
					case "Validation_Comptabilisation":
						//1 validation | -1 refus
						$val=$TableauValeur_Valeur;
						if($TableauValeur_Valeur==0){$val=-1;}
						$ReqActionSessionPersonne="UPDATE form_session_personne SET AComptabiliser=".$val." WHERE Id=".$TableauValeur_IdSessionPersonne;
						$ResultActionSessionPersonne=mysqli_query($bdd,$ReqActionSessionPersonne);
						break;
				}
			}
			echo "<script>window.opener.location='Planning_v2.php'</script>";
		}
		elseif($_POST['Action']=="MAJ_Couts")
		{
			$TableauValeurs=explode("|",$_POST['Liste_IDSessionPersonne']);
			foreach($TableauValeurs as $Valeur)
			{
				$ReqCoutSessionPersonne="UPDATE form_session_personne SET Cout=".$_POST['Cout_Session_Personne_'.$Valeur]." WHERE Id=".$Valeur;
				$ResultCoutSessionPersonne=mysqli_query($bdd,$ReqCoutSessionPersonne);
			}
		}
		elseif($_POST['Action']=="Validation_Motif")
		{
			$TableauValeurs=explode("|",$_POST['Liste_IDSessionPersonneComptabilisation']);
			foreach($TableauValeurs as $Valeur)
			{
				$ReqMotifSessionPersonne="UPDATE form_session_personne SET MotifDesinscription=\"".addslashes($_POST['Motif_Session_Personne_'.$Valeur])."\" WHERE Id=".$Valeur;
				$ResultMotifSessionPersonne=mysqli_query($bdd,$ReqMotifSessionPersonne);
			}
		}
		elseif($_POST['Action']=="Validation_CoutDesincription")
		{
			$TableauValeurs=explode("|",$_POST['Liste_IDSessionPersonneComptabilisation']);
			foreach($TableauValeurs as $Valeur)
			{
				$ReqMotifSessionPersonne="UPDATE form_session_personne SET Cout=\"".addslashes($_POST['CoutDesinscription_Session_Personne_'.$Valeur])."\" WHERE Id=".$Valeur;
				$ResultMotifSessionPersonne=mysqli_query($bdd,$ReqMotifSessionPersonne);
			}
		}
	}
}
$ResultSession=get_session($ID);
$RowSession=mysqli_fetch_array($ResultSession);

$ResultSessionPersonnes=getRessource(getchaineSQL_sessionPersonne($ID));

$ResultSessionQualificationQCM=mysqli_query($bdd,$ReqSessionQualificationQCM);
$NBResultSessionQualificationQCM=mysqli_num_rows($ResultSessionQualificationQCM);

//Recuperation du nombre de personnes inscrites
$ResultNombreInscritSession=getRessource(getchaineSQL_NbInscritSession($ID));
$RowNombreInscritSession=mysqli_fetch_array($ResultNombreInscritSession);
if($_POST)
{
	$nomfichier="";
	
	if(isset($_POST['SauvegarderConvocation']) || isset($_POST['SupprDoc'])){
		if(!empty($_FILES['uploaded_file']))
		{
			if(!empty($_POST['SupprDoc'])){
				if($_FILES['uploaded_file']['name'] == "" && isset($_POST['SupprDoc']))
				{
					$ressource = getRessource(getChaineSQL_getInfosDocument($ID));
					$row = mysqli_fetch_array($ressource);
					
					if( strlen($row['nom_fichier']) > 0)
					{
						if(file_exists ($row['chemin_fichier'].$row['nom_fichier'])){
							if(!unlink($row['chemin_fichier'].$row['nom_fichier']))
							{
								if($LangueAffichage=="FR"){echo "Impossible de supprimer le fichier.";}
								else{echo "Can not delete the file.";}
							}
						}
					}
					$ressource = getRessource(getChaineSQL_setInfosDocument($ID, "", ""));
					DeconvoquerPersonnes($ID);
				}
			}
			if(!empty($_POST['SauvegarderConvocation'])){
				if($_FILES['uploaded_file']['name'] <> ""){
					$ressource = getRessource(getChaineSQL_setInfosDocument($ID, "", ""));
					$nomfichier = transferer_fichier($_FILES['uploaded_file']['name'], $_FILES['uploaded_file']['tmp_name'], "Docs/convocations/");
					$ressource = getRessource(getChaineSQL_setInfosDocument($ID, "Docs/convocations/", $nomfichier));
					DeconvoquerPersonnes($ID);
				}
			}
		}
	}
	if(isset($_POST['stagiairesPresents']))
	{
		if($_POST['stagiairesPresents']<>"0"){
			if(isset($_POST['SauvegarderAttestation']) || isset($_POST['SupprimerAttestation'])){
				//Vérifier si document existe déjà 
				$reqAttest="SELECT AttestationFormation FROM form_session_personne WHERE AttestationFormation<>'' AND Id=".$_POST['stagiairesPresents'];
				$resultAttest=mysqli_query($bdd,$reqAttest);
				$nbAttest=mysqli_num_rows($resultAttest);
				if($nbAttest>0){
					$rowAttest=mysqli_fetch_array($resultAttest);
					if(file_exists ("Docs/AttestationsFormations/".$rowAttest['AttestationFormation'])){
						//Supprimer le document
						unlink("Docs/AttestationsFormations/".$rowAttest['AttestationFormation']);
						
						$reqUpdateAttestation="UPDATE form_session_personne SET AttestationFormation='' WHERE Id=".$_POST['stagiairesPresents'];
						$resultUpdateAttestation=mysqli_query($bdd,$reqUpdateAttestation);
					}
					else{
						$reqUpdateAttestation="UPDATE form_session_personne SET AttestationFormation='' WHERE Id=".$_POST['stagiairesPresents'];
						$resultUpdateAttestation=mysqli_query($bdd,$reqUpdateAttestation);
					}
				}
				if(isset($_POST['SauvegarderAttestation']) && !empty($_FILES['uploaded_fileAttestation']))
				{
					if($_FILES['uploaded_fileAttestation']['name'] <> ""){
						$nomfichier = transferer_fichier($_FILES['uploaded_fileAttestation']['name'], $_FILES['uploaded_fileAttestation']['tmp_name'], "Docs/AttestationsFormations/");
					}
					$reqUpdateAttestation="UPDATE form_session_personne SET AttestationFormation='".$nomfichier."' WHERE Id=".$_POST['stagiairesPresents'];
					$resultUpdateAttestation=mysqli_query($bdd,$reqUpdateAttestation);
				}
			}
		}
	}
	
	if(isset($_POST['stagiairesPresentsConvoc']))
	{
		if($_POST['stagiairesPresentsConvoc']<>"0"){
			if(isset($_POST['SauvegarderConvocationIndividuelle']) || isset($_POST['SupprimerConvocationIndividuelle'])){
				//Vérifier si document existe déjà 
				$reqAttest="SELECT Convocation FROM form_session_personne WHERE Convocation<>'' AND Id=".$_POST['stagiairesPresentsConvoc'];
				$resultAttest=mysqli_query($bdd,$reqAttest);
				$nbAttest=mysqli_num_rows($resultAttest);
				if($nbAttest>0){
					$rowAttest=mysqli_fetch_array($resultAttest);
					if(file_exists ("Docs/convocations/".$rowAttest['Convocation'])){
						//Supprimer le document
						unlink("Docs/convocations/".$rowAttest['Convocation']);
						
						$reqUpdateAttestation="UPDATE form_session_personne SET Convocation='' WHERE Id=".$_POST['stagiairesPresentsConvoc'];
						$resultUpdateAttestation=mysqli_query($bdd,$reqUpdateAttestation);
						DeconvoquerPersonnes($ID,$_POST['stagiairesPresentsConvoc']);
					}
					else{
						$reqUpdateAttestation="UPDATE form_session_personne SET Convocation='' WHERE Id=".$_POST['stagiairesPresentsConvoc'];
						$resultUpdateAttestation=mysqli_query($bdd,$reqUpdateAttestation);
						DeconvoquerPersonnes($ID,$_POST['stagiairesPresentsConvoc']);
					}
				}
				if(isset($_POST['SauvegarderConvocationIndividuelle']) && !empty($_FILES['uploaded_fileConvocationIndividuelle']))
				{
					if($_FILES['uploaded_fileConvocationIndividuelle']['name'] <> ""){
						$nomfichier = transferer_fichier($_FILES['uploaded_fileConvocationIndividuelle']['name'], $_FILES['uploaded_fileConvocationIndividuelle']['tmp_name'], "Docs/convocations/");
					}
					if($nomfichier<>""){
						$reqUpdateAttestation="UPDATE form_session_personne SET Convocation='".$nomfichier."' WHERE Id=".$_POST['stagiairesPresentsConvoc'];
						$resultUpdateAttestation=mysqli_query($bdd,$reqUpdateAttestation);
						DeconvoquerPersonnes($ID,$_POST['stagiairesPresentsConvoc']);
					}
				}
			}
		}
	}
}
$ResultSessionPersonnes=getRessource(getchaineSQL_sessionPersonne($ID));
?>	

	<table style="width:100%; align:center;">
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
<form id="formulaire_liste_personnes" enctype="multipart/form-data" method="POST" action="Contenu_Session.php">
	<input type="hidden" name="Id" value="<?php echo $ID;?>">
	<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage;?>">
	<input type="hidden" name="Id_Plateforme" id="Id_Plateforme" value="<?php echo $ID_PLATEFORME;?>">
	<input type="hidden" name="Id_TypeFormation" value="<?php echo $RowSession['ID_TYPEFORMATION'];?>">
	<input type="hidden" name="Nb_Stagiaire_Maxi" value="<?php echo $RowSession['NB_STAGIAIRE_MAXI'];?>">
	<input type="hidden" name="Nb_Inscrits" value="<?php echo $RowNombreInscritSession['NOMBRE'];?>">
	<input type="hidden" name="Id_GroupeSession" value="<?php echo $RowSession['ID_GROUPE_SESSION'];?>">
	<input type="hidden" name="FormationLiee" value="<?php echo $RowSession['FORMATION_LIEE'];?>">
	<input type="hidden" id="ancre" name="ancre" value="<?php echo $ancre;?>">
	<input type="hidden" name="Action" Id="Action" value="">

	<table class="TableCompetences" >
		<tr><td><b><?php if($LangueAffichage=="FR"){echo "Convocation à joindre";}else{echo "Invitation to join";}?> :</b></td></tr>
		<?php
			//Lecture
			$ressource = getRessource(getChaineSQL_getInfosDocument($ID));
			$row = mysqli_fetch_array($ressource);
						
			if($row['nom_fichier'] <> "" && !is_null($row['nom_fichier']))
			{
				echo "<tr><td><a class=\"Info\" href=\"".$row['chemin_fichier'].$row['nom_fichier']."\" target=\"_blank\">".$row['nom_fichier']."</a>
						<a href=\"javascript:SupprimerDoc();\" style=\"text-decoration:none;\">
							<img src='../../Images/Refuser.gif' name='suprimerDocument' style='border:0;cursor:pointer;' title='";
				if($LangueAffichage=="FR"){echo "Supprimer document";}else{echo "Delete document";}
				echo "'>
					 	</a>
					 </td></tr>";
			}
		?>
		<tr>
			<td>
				<input type="hidden" name="SupprDoc" id="SupprDoc" value="">
				<input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
				<input type="file" name="uploaded_file" />
				<input class="Bouton" type="submit" name="SauvegarderConvocation" style='cursor:pointer;' value="<?php if($LangueAffichage=="FR"){echo "Sauvegarder";}else{echo "Save";}?>" />
			</td>
		</tr>
	</table>
	<?php
		if($RowSession['ID_TYPEFORMATION']==$IdTypeFormationTC || $RowSession['ID_TYPEFORMATION']==$IdTypeFormationExterne){
	?>
	<br>
	<table class="TableCompetences" style="width:100%;">
		<tr><td><b><?php if($LangueAffichage=="FR"){echo "Attestation de formation à joindre";}else{echo "Attestation of formation to join";}?> :</b></td></tr>
		<tr>
			<td>
				<b><?php if($LangueAffichage=="FR"){echo "Stagiaires présents";}else{echo "Interns present";}?> :</b>
				<select name="stagiairesPresents" id="stagiairesPresents" >
					<option value="0"></option>
					<?php 
						$reqPersonnePres="SELECT Id, 
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Personne
							FROM form_session_personne 
							WHERE Validation_Inscription=1 
							AND Presence=1 
							AND Suppr=0 
							AND Id_Session=".$ID;
						$resultPersonnePres=mysqli_query($bdd,$reqPersonnePres);
						$nbPersonnePres=mysqli_num_rows($resultPersonnePres);
						if($nbPersonnePres>0)
						{
							while($RowPersonnePres=mysqli_fetch_array($resultPersonnePres))
							{
								echo "<option value=\"".$RowPersonnePres['Id']."\">".$RowPersonnePres['Personne']."</option>";
							}
						}
					?>
				</select>
				<input type="file" name="uploaded_fileAttestation" />
				<input class="Bouton" type="submit" name="SauvegarderAttestation" style='cursor:pointer;' value="<?php if($LangueAffichage=="FR"){echo "Sauvegarder";}else{echo "Save";}?>" />
				&nbsp;&nbsp;&nbsp;<input class="Bouton" type="submit" name="SupprimerAttestation" style='cursor:pointer;' value="<?php if($LangueAffichage=="FR"){echo "Supprimer attestation";}else{echo "Delete certificate";}?>" />
			</td>
		</tr>
	</table>
	<br>
	<table class="TableCompetences" style="width:100%;">
		<tr><td><b><?php if($LangueAffichage=="FR"){echo "Convocations individuelles à joindre";}else{echo "Convocations individuelles à joindre";}?> :</b></td></tr>
		<tr>
			<td>
				<b><?php if($LangueAffichage=="FR"){echo "Stagiaires présents";}else{echo "Interns present";}?> :</b>
				<select name="stagiairesPresentsConvoc" id="stagiairesPresentsConvoc" >
					<option value="0"></option>
					<?php 
						$reqPersonnePres="SELECT Id, 
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Personne
							FROM form_session_personne 
							WHERE Validation_Inscription=1 
							AND Suppr=0 
							AND Id_Session=".$ID;
						$resultPersonnePres=mysqli_query($bdd,$reqPersonnePres);
						$nbPersonnePres=mysqli_num_rows($resultPersonnePres);
						if($nbPersonnePres>0)
						{
							while($RowPersonnePres=mysqli_fetch_array($resultPersonnePres))
							{
								echo "<option value=\"".$RowPersonnePres['Id']."\">".$RowPersonnePres['Personne']."</option>";
							}
						}
					?>
				</select>
				<input type="file" name="uploaded_fileConvocationIndividuelle" />
				<input class="Bouton" type="submit" name="SauvegarderConvocationIndividuelle" style='cursor:pointer;' value="<?php if($LangueAffichage=="FR"){echo "Sauvegarder";}else{echo "Save";}?>" />
				&nbsp;&nbsp;&nbsp;<input class="Bouton" type="submit" name="SupprimerConvocationIndividuelle" style='cursor:pointer;' value="<?php if($LangueAffichage=="FR"){echo "Supprimer convocation";}else{echo "Delete convocation";}?>" />
			</td>
		</tr>
	</table>	
	<?php
		}
	?>
	<table style="width:100%; align:center;">
		<tr class="TitreColsUsers">
			<td align="center">
				<?php 
					$champs="";
					if($RowSession['ID_FORMATEUR']==0)
					{
						if($LangueAffichage=="FR"){$champs.="formateur ";}
						else{$champs.="trainer ";}
					}
					if($RowSession['ID_LIEU']==0 && $RowSession['nom_fichier']=="")
					{
						if($LangueAffichage=="FR"){$champs.="lieu ";}
						else{$champs.="place ";}
					}
				?>
				<input class="Bouton" type="button" value="<?php if($LangueAffichage=="FR"){echo "Envoyer convocation";}else{echo "Send convocation";}?>" onclick="EmailConvocation('<?php echo $ID;?>','<?php echo $champs; ?>');">
			</td>
		</tr>
		<?php if($RowSession['ID_GROUPE_SESSION']>0 && $RowSession['FORMATION_LIEE']==1){ ?>
		<tr class="TitreColsUsers">
			<td>
				&bull; <a href="javascript:EditerFichePresenceGroupe('<?php echo $ID;?>');"><?php if($LangueAffichage=="FR"){echo "Fiche de présence à signer (Groupe de formation)";}else{echo "Attendance form to be signed (Training group)";} ?></a>
			</td>
		</tr>
		<?php } ?>
		<tr class="TitreColsUsers">
			<td>
				&bull; <a href="javascript:EditerFichePresence('<?php echo $ID;?>');"><?php if($LangueAffichage=="FR"){echo "Fiche de présence à signer";}else{echo "Attendance form to be signed";} ?></a>
			</td>
		</tr>
		<?php
			//Personnes de la session 
				$reqSPersonne="SELECT Id FROM form_session_personne WHERE (Validation_Inscription=0 OR (Validation_Inscription=1 AND Presence=0)) AND Suppr=0 AND Id_Session=".$ID;
				$resultNbSPersonne=mysqli_query($bdd,$reqSPersonne);
				$nbSPersonne=mysqli_num_rows($resultNbSPersonne);
				if($nbSPersonne==0)
				{
		?>
		<tr class="TitreColsUsers">
			<td>
				&bull; <a href="javascript:EditerFichePresenceSignee('<?php echo $ID;?>');"><?php if($LangueAffichage=="FR"){echo "Fiche de présence signée";}else{echo "Signed attendance form";} ?></a>
			</td>
		</tr>
		<?php
                }
		?>
		<tr class="TitreColsUsers">
			<td>
				<table class="TableCompetences" style="width:100%;">
					<tr>
						<td rowspan="2" valign="middle" class="Libelle">
							<?php if($LangueAffichage=="FR"){echo "Stagiaires";}else{echo "Trainees";}?><br>
							<?php
							echo "Mini : ".$RowSession['NB_STAGIAIRE_MINI']."<br>"."Maxi : ".$RowSession['NB_STAGIAIRE_MAXI'];
							?>
						</td>
						<td rowspan="2" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Prestation - Pôle";}else{echo "Activity - Pole";}?></td>
						<td rowspan="2" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Contrat";}else{echo "Contract";}?></td>
						<td rowspan="2" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Inscrit par";}else{echo "Registered by";}?></td>
						<td rowspan="2" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Inscrit le";}else{echo "Join the";}?></td>
						<td rowspan="2" valign="middle" class="Libelle" style='border-right:1px solid #6fa3fd;'><?php if($LangueAffichage=="FR"){echo "Convocation";}else{echo "Convocation";}?></td>
						<td rowspan="2" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Inscr.";}else{echo "Registration";}?><br>ok</td>
						<td rowspan="2" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Conv.";}else{echo "Convocation";}?><br>ok</td>
						<td rowspan="2" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Prés.";}else{echo "Present";}?><br>ok</td>
						<td rowspan="2" valign="middle" class="Libelle" style='border-right:1px solid #6fa3fd;'><?php if($LangueAffichage=="FR"){echo "Attest.";}else{echo "Certificate";}?><br>&nbsp;</td>
						<?php
						if	(
							DroitsFormationPlateforme($TableauIdPostesAssistantFormation)
							||
								(
								$DateJour < $RowSession['DATE_DEBUT']
								&&
								DroitsFormationPlateforme(array_merge($TableauIdPostesAssistantFormation,array($IdPosteResponsableFormation,$IdPosteFormateur)))
								)
							)
						{
						?>
						<td colspan="2" valign="middle" class="Libelle" style='border-right:1px solid #6fa3fd;'>
							&nbsp;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Valide inscription";}else{echo "Valid registration";}?><br>
						</td>
						<?php 
						}
						if(DroitsFormationPlateforme(array_merge($TableauIdPostesAssistantFormation,array($IdPosteResponsableFormation,$IdPosteFormateur))))
						{
							if($DateJour >= $RowSession['DATE_DEBUT'])
							{
						?>
							<td colspan="3" align="center" class="Libelle" valign="middle" style='border-right:1px solid #6fa3fd;'>
								&nbsp;&nbsp;&nbsp;Valid.<br>&nbsp;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "prés.";}else{echo "present";}?><br>
							</td>
							<td rowspan="2"  valign="middle" class="Libelle" align="center" style='border-right:1px solid #6fa3fd;'>
								<?php if($LangueAffichage=="FR"){echo "Qualifications et % réussite";}else{echo "Qualifications and % success";}?>
							</td>
						<?php
						}
						?>
						<td rowspan="2" align="center" class="Libelle" valign="middle" style='border-right:1px solid #6fa3fd;'>
							<?php if($LangueAffichage=="FR"){echo "Couts";}else{echo "Costs";}?>
						</td>
						<!--<td rowspan="2" align="center" class="Libelle" valign="middle">
							<?php if($LangueAffichage=="FR"){echo "Suppr";}else{echo "Delete";}?><br><br>
							<input type="checkbox" name="Suppr_Toutes_1" onclick="javascript:Cocher('Suppr','1','-1');">
						</td>-->
						<?php 
						}
						?>
					</tr>
					<tr>
						<?php
						if	(
							DroitsFormationPlateforme($TableauIdPostesAssistantFormation)
							||
								(
								$DateJour < $RowSession['DATE_DEBUT']
								&&
								DroitsFormationPlateforme(array_merge($TableauIdPostesAssistantFormation,array($IdPosteResponsableFormation,$IdPosteFormateur)))
								)
							)
						{
						?>
						<td align="center" valign="middle" class="Libelle" width="5px">
							<?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}?><br>
							<input type="checkbox" name="Validation_Inscription_Toutes_1" onclick="javascript:Cocher('Validation_Inscription','1','-1');">
						</td>
						<td align="center" valign="middle" class="Libelle" width="5px" style='border-right:1px solid #6fa3fd;'>
							<?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";}?><br>
							<input type="checkbox" name="Validation_Inscription_Toutes_0" onclick="javascript:Cocher('Validation_Inscription','0','-1');">
						</td>
						<?php 
						}
						if(DroitsFormationPlateforme(array_merge($TableauIdPostesAssistantFormation,array($IdPosteResponsableFormation,$IdPosteFormateur))))
						{
							if($DateJour >= $RowSession['DATE_DEBUT'])
							{
						?>
							<td align="center" valign="middle" width="5px" class="Libelle">
								<?php if($LangueAffichage=="FR"){echo "Présent";}else{echo "Present";}?><br>
								<input type="checkbox" name="Presence_Toutes_1" onclick="javascript:Cocher('Presence','1','-1');">
							</td>
							<td align="center" valign="middle" width="5px" class="Libelle">
								<?php if($LangueAffichage=="FR"){echo "Absent";}else{echo "Absent";}?><br>
								<input type="checkbox" name="Presence_Toutes_0" onclick="javascript:Cocher('Presence','0','-1');">
							</td>
							<td align="center" valign="middle" width="5px" class="Libelle" style='border-right:1px solid #6fa3fd;'>
								<?php if($LangueAffichage=="FR"){echo "Présent";}else{echo "Semi present";}?> (00:00)
							</td>
						<?php
							}
						}
						?>
					</tr>
					<tr height='1' bgcolor='#66AACC'><td colspan='16'></td></tr>
					<tr><td colspan='16' bgcolor="#DDDDDD"><b><?php if($LangueAffichage=="FR"){echo "Inscrits";}else{echo "Registered";}?></b></td></tr>
					<tr height='1' bgcolor='#66AACC'><td colspan='16'></td></tr>
					<?php
					$IndiceCaseACocher=-1;
					//$Liste_IDSessionPersonneQualificationQCM="";
					$Liste_IDSessionPersonneQualification="";
					$Liste_IDSessionPersonne="";
					$Couleur="#aac9fe";
					while($RowSessionPersonnes=mysqli_fetch_array($ResultSessionPersonnes))
					{
						if($Couleur=="#aac9fe"){$Couleur="#FFFFFF";}
						else{$Couleur="#aac9fe";}
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
						echo "<td>".substr($RowSessionPersonnes['PRESTATION'],0,7).$RowSessionPersonnes['POLE']." (".$RowSessionPersonnes['Code_Analytique'].")</td>\n";
						
						$IdContrat=IdContrat($RowSessionPersonnes['ID_PERSONNE'],$RowSessionPersonnes['DATE_INSCRIPTION']);
						$Contrat="";
						$leHover="";
						$span="";
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
						echo "<td ".$leHover.">".$Contrat.$span."</td>\n";
						echo "<td>".$RowSessionPersonnes['INSCRIPTEUR_NOMPRENOM']."</td>\n";
						echo "<td >".AfficheDateJJ_MM_AAAA($RowSessionPersonnes['DATE_INSCRIPTION'])."</td>\n";
						echo "<td align='center' style='border-right:1px solid #6fa3fd;'>";
						if($RowSessionPersonnes['Convocation']<>""){echo "<a class=\"Info\" target=\"_blank\" href=\"Docs/convocations/".$RowSessionPersonnes['Convocation']."\"><img src='../../Images/doc.png' style='border:0;width:25px;' title='Convocation'></a>";}
						echo "</td>";
						echo "<td align='center'>";
						if($LangueAffichage=="FR")
						{
							if($RowSessionPersonnes['VALIDATION_INSCRIPTION']==1){echo "<img src='../../Images/tick.png' style='border:0;' title='Inscription validée'>";}
							elseif($RowSessionPersonnes['VALIDATION_INSCRIPTION']==-1){echo "<img src='../../Images/Refuser.gif' style='border:0;' title='Inscription refusée'>";}
						}
						else
						{
							if($RowSessionPersonnes['VALIDATION_INSCRIPTION']==1){echo "<img src='../../Images/tick.png' style='border:0;' title='Registration validated'>";}
							elseif($RowSessionPersonnes['VALIDATION_INSCRIPTION']==-1){echo "<img src='../../Images/Refuser.gif' style='border:0;' title='Registration refused'>";}
						}
						echo "</td>";
						
						echo "<td align='center'>";
						$LibelleConvocationEnvoyee="Convocation sent";
						if($LangueAffichage=="FR"){$LibelleConvocationEnvoyee="Convocation envoyée";}
						if($RowSessionPersonnes['CONVOCATION_ENVOYEE']==1){echo "<img src='../../Images/tick.png' style='border:0;' title='".$LibelleConvocationEnvoyee."'>";}
						echo "</td>";
						
						echo "<td align='center'>";
						if($RowSessionPersonnes['PRESENCE']==1 && $RowSessionPersonnes['VALIDATION_INSCRIPTION']==1){echo "<img src='../../Images/tick.png' style='border:0;' title='Present'>";}
						elseif($RowSessionPersonnes['PRESENCE']==-1 && $RowSessionPersonnes['VALIDATION_INSCRIPTION']==1){echo "<img src='../../Images/Refuser.gif' style='border:0;' title='Absent'>";}
						elseif($RowSessionPersonnes['PRESENCE']==-2 && $RowSessionPersonnes['VALIDATION_INSCRIPTION']==1){echo substr($RowSessionPersonnes['SEMI_PRESENCE'],0,5);}
						echo "</td>";
						
						echo "<td align='center' style='border-right:1px solid #6fa3fd;'>";
						$LibelleCertificat="Certificate";
						if($LangueAffichage=="FR"){$LibelleCertificat="Attestation";}
						if($RowSession['ID_TYPEFORMATION']==2 || $RowSession['ID_TYPEFORMATION']==4){
							if($RowSessionPersonnes['AttestationFormation']<>""){echo "<a class=\"Info\" target=\"_blank\" href=\"Docs/AttestationsFormations/".$RowSessionPersonnes['AttestationFormation']."\"><img src='../../Images/certificat.jpg' style='border:0;width:25px;' title='".$LibelleCertificat."'></a>";}
						}
						else
						{
							if(isset($_SESSION['PartieFormation']))
							{
								if($_SESSION['PartieFormation']>1)
								{
									if($RowSessionPersonnes['PRESENCE']==1)
									{
										echo "<a class=\"Info\" href=\"javascript:genererAttestation(".$RowSessionPersonnes['ID'].");\"><img src='../../Images/certificat.jpg' style='border:0;width:25px;' title='".$LibelleCertificat."'></a>";
									}
								}
							}
						}
						echo "</td>";
						
						if	(
							DroitsFormationPlateforme($TableauIdPostesAssistantFormation)
							||
								(
								$DateJour < $RowSession['DATE_DEBUT']
								&&
								DroitsFormationPlateforme(array_merge($TableauIdPostesAssistantFormation,array($IdPosteResponsableFormation,$IdPosteFormateur)))
								)
							)
						{
							echo "<td align='center'>";
							echo "<input type='checkbox' name='Validation_Inscription[]' value='1|".$RowSessionPersonnes['ID']."|".$RowSessionPersonnes['ID_BESOIN']."|".$RowSessionPersonnes['ID_PERSONNE']."|".$IndiceCaseACocher."' onclick='javascript:Cocher(\"Validation_Inscription\",\"1\",\"".$IndiceCaseACocher."\");'>";
							echo "</td>\n";
							echo "<td align='center' style='border-right:1px solid #6fa3fd;'>";
							echo "<input type='checkbox' name='Validation_Inscription[]' value='0|".$RowSessionPersonnes['ID']."|".$RowSessionPersonnes['ID_BESOIN']."|".$RowSessionPersonnes['ID_PERSONNE']."|".$IndiceCaseACocher."' onclick='javascript:Cocher(\"Validation_Inscription\",\"0\",\"".$IndiceCaseACocher."\");'>";
							echo "</td>\n";
						}
						if(DroitsFormationPlateforme(array_merge($TableauIdPostesAssistantFormation,array($IdPosteResponsableFormation,$IdPosteFormateur))))
						{
							if($DateJour >= $RowSession['DATE_DEBUT'])
							{
								echo "<td align='center'>";
								echo "<input type='checkbox' name='Presence[]' value='1|".$RowSessionPersonnes['ID']."|".$RowSessionPersonnes['ID_BESOIN']."|".$RowSessionPersonnes['ID_PERSONNE']."|".$IndiceCaseACocher."' onclick='javascript:Cocher(\"Presence\",\"1\",\"".$IndiceCaseACocher."\");'>";
								echo "</td>\n";
								echo "<td align='center'>";
								echo "<input type='checkbox' name='Presence[]' value='0|".$RowSessionPersonnes['ID']."|".$RowSessionPersonnes['ID_BESOIN']."|".$RowSessionPersonnes['ID_PERSONNE']."|".$IndiceCaseACocher."' onclick='javascript:Cocher(\"Presence\",\"0\",\"".$IndiceCaseACocher."\");'>";
								echo "</td>\n";
								echo "<td align='center' style='border-right:1px solid #6fa3fd;'>";
								echo "<input style='display:none;' type='checkbox' name='Presence[]' value='-1|".$RowSessionPersonnes['ID']."|".$RowSessionPersonnes['ID_BESOIN']."|".$RowSessionPersonnes['ID_PERSONNE']."|".$IndiceCaseACocher."'>";
								echo "<input id='semipresence".$IndiceCaseACocher."' name='semipresence".$IndiceCaseACocher."' onKeyUp='heure(this)'  value='' size='5' onclick='javascript:Cocher(\"Presence\",\"-1\",\"".$IndiceCaseACocher."\");'>";
								echo "</td>\n";
								echo "<td style='border-right:1px solid #6fa3fd;'>";
								echo "<table>";
								//$Liste_IDSessionPersonneQualificationQCM="";
								//A REVOIR EN PARTIE 2
								if($NBResultSessionQualificationQCM && $RowSessionPersonnes['PRESENCE']==1)
								{
									mysqli_data_seek($ResultSessionQualificationQCM,0);
									$QualificationPrecedente="";
									while($RowSessionQualificationQCM=mysqli_fetch_array($ResultSessionQualificationQCM))
									{
										//Affichage des qualifications et des QCM associés à la personne pour la formation de cette session
										if($RowSessionQualificationQCM['ID_PERSONNE']==$RowSessionPersonnes['ID_PERSONNE'])
										{
											//Affichage des qualifications
											//Etant donné qu'il peut y avoir plusieurs QCM alors on vérifie pour n'afficher qu'une seule ligne
											if($QualificationPrecedente!=$RowSessionQualificationQCM['ID_QUALIFICATION'])
											{
												$QualificationPrecedente=$RowSessionQualificationQCM['ID_QUALIFICATION'];
												if($LangueAffichage=="FR"){$Tab=array('0' => 'Non passée','1' => 'Validée','-1' => 'Échouée');}
												else{$Tab=array('0' => 'Not passed','1' => 'Validated','-1' => 'Failed');}
												echo "<tr>\n";
												echo "<td><b>".$RowSessionQualificationQCM['LIBELLE_QUALIFICATION']."</b> - ".$Tab[$RowSessionQualificationQCM['ETAT_SESSION_QUALIFICATION']]." (".$RowSessionQualificationQCM['RESULTAT_SESSION_QUALIFICATION'].") </td>\n";
												echo "<td>\n";
												echo "<select name='Etat_Qualification[]'>";
												if($LangueAffichage=="FR"){$Tableau=array('0|Non passée','1|Validée','-1|Échouée');}
												else{$Tableau=array('0|Not passed','1|Validated','-1|Failed');}
												foreach($Tableau as $indice => $valeur)
												{
													$valeur=explode("|",$valeur);
													echo "<option value='".$valeur[0]."|".$RowSessionPersonnes['ID']."|".$RowSessionPersonnes['ID_BESOIN']."|".$RowSessionPersonnes['ID_PERSONNE']."|".$RowSessionQualificationQCM['ID_SESSION_PERSONNE_QUALIFICATION']."'";
													if($valeur[0]==$RowSessionQualificationQCM['ETAT_SESSION_QUALIFICATION']){echo " selected";}
													echo ">".$valeur[1]."</option>\n";
												}
												echo "</select>\n";
												echo "</td>\n";
												
												echo "<td><input size='5' name='Resultat_Qualification_".$RowSessionQualificationQCM['ID_SESSION_PERSONNE_QUALIFICATION']."' value='".$RowSessionQualificationQCM['RESULTAT_SESSION_QUALIFICATION']."'></td>\n";
												$Liste_IDSessionPersonneQualification.=$RowSessionQualificationQCM['ID_SESSION_PERSONNE_QUALIFICATION']."#".$RowSessionPersonnes['ID_BESOIN']."#".$RowSessionQualificationQCM['ID_QUALIFICATION']."#".$RowSessionPersonnes['ID_PERSONNE']."|";
											}
											else{echo "<tr><td colspan='2'></td>\n";}
											
											//Affichage des QCM
											//A remettre en place quand partie QCM traitée
											//if($RowSessionQualificationQCM['ID_QCM']!=0)
											//{
											//	echo "<td>".$RowSessionQualificationQCM['LIBELLE_QCM']." ID BESOIN=".$RowSessionPersonnes['ID_BESOIN']."</td>\n";
											//	echo "<td><input size='1' name='Resultat_QCM_".$RowSessionQualificationQCM['ID_SESSION_PERSONNE_QUALIFICATION_QCM']."' value='".$RowSessionQualificationQCM['RESULTAT_QCM']."'></td>\n";
											//	$Liste_IDSessionPersonneQualificationQCM.=$RowSessionQualificationQCM['ID_SESSION_PERSONNE_QUALIFICATION_QCM']."#".$RowSessionPersonnes['ID_BESOIN']."#".$RowSessionQualificationQCM['ID_QUALIFICATION']."#".$RowSessionPersonnes['ID_PERSONNE']."|";
											//}
											//else
											//{
                                                echo "<td colspan='2'></td>\n";
											//}
											
											echo "</tr>\n";
										}
									}
								}
								echo "</table>";
								echo "</td>\n";
							}
							
							//Coûts
							echo "<td align='center' style='border-right:1px solid #6fa3fd;'>";
							echo "<input size='4px' name='Cout_Session_Personne_".$RowSessionPersonnes['ID']."' value='".$RowSessionPersonnes['COUT']."'>";
							echo "</td>";
						}
						echo "</tr>\n";
					}
					//$Liste_IDSessionPersonneQualificationQCM=substr($Liste_IDSessionPersonneQualificationQCM,0,strlen($Liste_IDSessionPersonneQualificationQCM)-1);
					//echo "<input type='hidden' name='Liste_IDSessionPersonneQualificationQCM' value='".$Liste_IDSessionPersonneQualificationQCM."'>\n";
					$Liste_IDSessionPersonneQualification=substr($Liste_IDSessionPersonneQualification,0,strlen($Liste_IDSessionPersonneQualification)-1);
					echo "<input type='hidden' name='Liste_IDSessionPersonneQualification' value='".$Liste_IDSessionPersonneQualification."'>\n";
					$Liste_IDSessionPersonne=substr($Liste_IDSessionPersonne,0,strlen($Liste_IDSessionPersonne)-1);
					echo "<input type='hidden' name='Liste_IDSessionPersonne' value='".$Liste_IDSessionPersonne."'>\n";
					?>
					<tr>
						<td colspan=10></td>
						<?php
						if	(
							DroitsFormationPlateforme($TableauIdPostesAssistantFormation)
							||
								(
								$DateJour < $RowSession['DATE_DEBUT']
								&&
								DroitsFormationPlateforme(array_merge($TableauIdPostesAssistantFormation,array($IdPosteResponsableFormation,$IdPosteFormateur)))
								)
							)
						{
							if($LangueAffichage=="FR"){echo "<td colspan='2' align='center'><input class='Bouton' type='submit' value='Valider' onclick='javascript:Envoyer_Commande(\"Validation_Inscription\");'></td>\n";}
							else{echo "<td colspan='2' align='center'><input class='Bouton' type='submit' value='Validate' onclick='javascript:Envoyer_Commande(\"Validation_Inscription\");'></td>\n";}
						}
						if($DateJour >= $RowSession['DATE_DEBUT'] && DroitsFormationPlateforme(array_merge($TableauIdPostesAssistantFormation,array($IdPosteResponsableFormation,$IdPosteFormateur))))
						{
							echo "<td colspan='3' align='center'>\n";
							if($LangueAffichage=="FR"){echo "<input class='Bouton' type='submit' value='Valider' onclick='javascript:Envoyer_Commande(\"Presence\");'>\n";}
							else{echo "<input class='Bouton' type='submit' value='Validate' onclick='javascript:Envoyer_Commande(\"Presence\");'>\n";}
							echo "</td>\n";
							echo "<td align='center'>\n";
							if($LangueAffichage=="FR"){echo "<input class='Bouton' type='submit' value='Valider Qualification' onclick='javascript:Envoyer_Commande(\"Etat_Qualification\");'>\n";}
							else{echo "<input class='Bouton' type='submit' value='Validate Qualification' onclick='javascript:Envoyer_Commande(\"Etat_Qualification\");'>\n";}
							//echo str_repeat("&nbsp;",24)."\n";
							//echo "<input class='Bouton' type='submit' value='Valider QCM' onclick='javascript:Envoyer_Commande(\"MAJ_Notes_QCM\");'>\n";
							echo "</td>\n";
							echo "<td align='center'>\n";
							if($LangueAffichage=="FR"){echo "<input class='Bouton' type='submit' value='MAJ couts' onclick='javascript:Envoyer_Commande(\"MAJ_Couts\");'>\n";}
							else{echo "<input class='Bouton' type='submit' value='Update cost' onclick='javascript:Envoyer_Commande(\"MAJ_Couts\");'>\n";}
							echo "</td>\n";
						}
						else
						{
							echo "<td>\n";
							echo "</td>\n";
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
				<?php if($LangueAffichage=="FR"){echo "Désinscriptions : ";}else{echo "Unsubscriptions : ";} ?>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td>
				<table class="TableCompetences" style="width:100%;">
					<tr>
						<td rowspan="2" valign="middle" class="Libelle">
							<?php if($LangueAffichage=="FR"){echo "Stagiaires";}else{echo "Trainees";}?><br>
						</td>
						<td rowspan="2" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Prestation - Pôle";}else{echo "Activity - Pole";}?></td>
						<td rowspan="2" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Contrat";}else{echo "Contract";}?></td>
						<td rowspan="2" valign="middle" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Date désinscription";}else{echo "Unsubscription date";}?></td>
						<td rowspan="2" valign="middle" class="Libelle" style='border-right:1px solid #6fa3fd;'><?php if($LangueAffichage=="FR"){echo "A comptabiliser";}else{echo "To count";}?></td>
						<td rowspan="2" valign="middle" class="Libelle" style='border-right:1px solid #6fa3fd;'><?php if($LangueAffichage=="FR"){echo "Motif";}else{echo "Reason";}?></td>
						<td rowspan="2" valign="middle" class="Libelle" style='border-right:1px solid #6fa3fd;'><?php if($LangueAffichage=="FR"){echo "Coût";}else{echo "Cost";}?></td>
						<td colspan="2" valign="middle" align="center" class="Libelle" style='border-right:1px solid #6fa3fd;'><?php if($LangueAffichage=="FR"){echo "A comptabiliser";}else{echo "To count";}?></td>
					</tr>
					<tr>
						<td align="center" valign="middle" class="Libelle" width="5px">
							<?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}?><br>
							<input type="checkbox" name="Validation_Comptabilisation_Toutes_1" onclick="javascript:Cocher('Validation_Comptabilisation','1','-1');">
						</td>
						<td align="center" valign="middle" class="Libelle" width="5px" style='border-right:1px solid #6fa3fd;'>
							<?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";}?><br>
							<input type="checkbox" name="Validation_Comptabilisation_Toutes_0" onclick="javascript:Cocher('Validation_Comptabilisation','0','-1');">
						</td>
					</tr>
					<tr height='1' bgcolor='#66AACC'><td colspan='16'></td></tr>
					<?php
					$IndiceCaseACocher=-1;
					$Liste_IDSessionPersonneQualification="";
					$Liste_IDSessionPersonne="";
					$Couleur="#aac9fe";
					
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
						ORDER BY
							STAGIAIRE_NOMPRENOM ASC,
							form_session_personne.Date_Inscription ASC;";
					$ResultSessionPersonnes=getRessource($req);
					while($RowSessionPersonnes=mysqli_fetch_array($ResultSessionPersonnes))
					{
						if($Couleur=="#aac9fe"){$Couleur="#FFFFFF";}
						else{$Couleur="#aac9fe";}
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
						echo "<td>".substr($RowSessionPersonnes['PRESTATION'],0,7).$RowSessionPersonnes['POLE']." (".$RowSessionPersonnes['Code_Analytique'].")</td>\n";
						
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
						echo "<td ".$leHover.">".$Contrat.$span."</td>\n";
						echo "<td>".AfficheDateJJ_MM_AAAA($RowSessionPersonnes['Date_Desinscription'])."</td>\n";
						echo "<td align='center' style='border-right:1px solid #6fa3fd;'>";
						if($LangueAffichage=="FR")
						{
							if($RowSessionPersonnes['AComptabiliser']==1){echo "<img src='../../Images/tick.png' style='border:0;' title='Comptabiliser'>";}
							elseif($RowSessionPersonnes['AComptabiliser']==-1){echo "<img src='../../Images/Refuser.gif' style='border:0;' title='Non comptabiliser'>";}
						}
						else
						{
							if($RowSessionPersonnes['AComptabiliser']==1){echo "<img src='../../Images/tick.png' style='border:0;' title='Count'>";}
							elseif($RowSessionPersonnes['AComptabiliser']==-1){echo "<img src='../../Images/Refuser.gif' style='border:0;' title='No count'>";}
						}
						echo "</td>";
						
						//Motif
						echo "<td align='center' style='border-right:1px solid #6fa3fd;'>";
						echo "<input size='60px' name='Motif_Session_Personne_".$RowSessionPersonnes['ID']."' value=\"".stripslashes($RowSessionPersonnes['MotifDesinscription'])."\">";
						echo "</td>";
						
						//Coûts
						echo "<td align='center' style='border-right:1px solid #6fa3fd;'>";
						echo "<input size='4px' name='CoutDesinscription_Session_Personne_".$RowSessionPersonnes['ID']."' value='".$RowSessionPersonnes['COUT']."'>";
						echo "</td>";
						
						echo "<td align='center'>";
							echo "<input type='checkbox' name='Validation_Comptabilisation[]' value='1|".$RowSessionPersonnes['ID']."|".$RowSessionPersonnes['ID_PERSONNE']."|".$IndiceCaseACocher."' onclick='javascript:Cocher(\"Validation_Comptabilisation\",\"1\",\"".$IndiceCaseACocher."\");'>";
							echo "</td>\n";
							echo "<td align='center' style='border-right:1px solid #6fa3fd;'>";
							echo "<input type='checkbox' name='Validation_Comptabilisation[]' value='0|".$RowSessionPersonnes['ID']."|".$RowSessionPersonnes['ID_PERSONNE']."|".$IndiceCaseACocher."' onclick='javascript:Cocher(\"Validation_Comptabilisation\",\"0\",\"".$IndiceCaseACocher."\");'>";
						echo "</td>\n";
		
						echo "</tr>\n";
					}
					$Liste_IDSessionPersonne=substr($Liste_IDSessionPersonne,0,strlen($Liste_IDSessionPersonne)-1);
					echo "<input type='hidden' name='Liste_IDSessionPersonneComptabilisation' value='".$Liste_IDSessionPersonne."'>\n";
					?>
					<tr>
						<td colspan=5></td>
						<?php
						if	(
							DroitsFormationPlateforme($TableauIdPostesAssistantFormation)
							)
						{
							if($LangueAffichage=="FR"){echo "<td align='center'><input class='Bouton' type='submit' value='Valider' onclick='javascript:Envoyer_Commande(\"Validation_Motif\");'></td>\n";}
							else{echo "<td align='center'><input class='Bouton' type='submit' value='Validate' onclick='javascript:Envoyer_Commande(\"Validation_Motif\");'></td>\n";}
							
							if($LangueAffichage=="FR"){echo "<td align='center'><input class='Bouton' type='submit' value='Valider' onclick='javascript:Envoyer_Commande(\"Validation_CoutDesincription\");'></td>\n";}
							else{echo "<td align='center'><input class='Bouton' type='submit' value='Validate' onclick='javascript:Envoyer_Commande(\"Validation_CoutDesincription\");'></td>\n";}
							
							if($LangueAffichage=="FR"){echo "<td colspan='2' align='center'><input class='Bouton' type='submit' value='Valider' onclick='javascript:Envoyer_Commande(\"Validation_Comptabilisation\");'></td>\n";}
							else{echo "<td colspan='2' align='center'><input class='Bouton' type='submit' value='Validate' onclick='javascript:Envoyer_Commande(\"Validation_Comptabilisation\");'></td>\n";}
						}
						else
						{
							echo "<td>\n";
							echo "</td>\n";
						}
						?>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
</body>
</html>
<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	$(function() {
	  $("table").stickyTableHeaders();
	});
	function QCM_Web(Id)
	{
		var w= window.open("QCM_Web_v3.php?Page=Gestion_SessionFormation&Id_Session_Personne_Qualification="+Id,"PageQCMWeb","status=no,menubar=no,scrollbars=yes,width=1200,height=800");
		w.focus();
	}
	function ModifierQCMGlobal(Id_Session,Id_Qualification)
	{
		var w= window.open("ModifierQCMSessionGlobal.php?Id_Session="+Id_Session+"&Id_Qualification="+Id_Qualification,"PageModifQCMSession","status=no,menubar=no,scrollbars=yes,width=700,height=250");
		w.focus();
	}
	
	function RecupererQCM(Id_SessionPersonneQualification)
	{
		var w= window.open("RecupererQCMSupprime.php?Id="+Id_SessionPersonneQualification,"PageModifQCMSession","status=no,menubar=no,scrollbars=yes,width=700,height=250");
		w.focus();
	}
	
	function ModifierDocGlobal(Id_Session,Id_Doc)
	{
		var w= window.open("ModifierDocSessionGlobal.php?Id_Session="+Id_Session+"&Id_Doc="+Id_Doc,"PageModifDocSession","status=no,menubar=no,scrollbars=yes,width=700,height=250");
		w.focus();
	}
	function CheckAbs(Id,checked)
	{
		formulaire.OuvertureAbs.value=Id;
		formulaire.BOuvertureAbs.value=checked;
		formulaire.submit();
	}
	function Voir_TR(Name)
	{
		table = document.getElementById('TABLE_FORM').getElementsByTagName('TR')
		for (l=0;l<table.length+1;l++)
		{
			if(table[l].getAttribute("name")==Name)
			{
				for (m=l+1;table.length+1;m++)
				{
					if(table[m].getAttribute("name")!=null){break;}
					if(table[m].style.display == ''){table[m].style.display = 'none';}
					else{table[m].style.display = '';}
				}
			}
		}
	}
	
	function Masquer_Tout()
	{
		table = document.getElementById('TABLE_FORM').getElementsByTagName('TR')
		for (l=5;l<table.length+1;l++)
		{	
			if(table[l].getAttribute("name")==null){table[l].style.display = 'none';}
			else{table[l].style.display = '';}
		}
	}
</script>
<?php

if($_POST)
{
	if($_POST['Ouverture']<>"")
	{
		if($_POST['BOuverture']=="checked"){fermerAccesQCM($_POST['Ouverture']);}
		else{ouvrirAccesQCM($_POST['Ouverture']);}
	}
	if($_POST['OuvertureAbs']<>"")
	{
		$req="SELECT Id_Besoin,Id_Personne,Id_Session,
			(SELECT (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) FROM form_session WHERE form_session.Id=form_session_personne.Id_Session) AS Id_TypeFormation
		FROM form_session_personne WHERE Id=".$_POST['OuvertureAbs'];
		$resultB=mysqli_query($bdd,$req);
		$nbB=mysqli_num_rows($resultB);
		$Id_Besoin=0;
		$Id_PersonneConcernee=0;
		$Id_TypeFormation=0;
		$ID=0;
		if($nbB>0){
			$RowB=mysqli_fetch_array($resultB);
			$Id_Besoin=$RowB['Id_Besoin'];
			$Id_PersonneConcernee=$RowB['Id_Personne'];
			$Id_TypeFormation=$RowB['Id_TypeFormation'];
			$ID=$RowB['Id_Session'];
		}			
		if($_POST['BOuvertureAbs']==""){$TableauValeur_Valeur=0;}
		else{$TableauValeur_Valeur=1;}
		
		$val=$TableauValeur_Valeur;

		//1 validation | -1 refus
		if($TableauValeur_Valeur==0){$val=-1;}
		$ReqActionSessionPersonne="UPDATE form_session_personne SET Presence=".$val.", Id_ValPresence=".$IdPersonneConnectee.", Date_ValPresence='".date('Y-m-d')."' WHERE Id=".$_POST['OuvertureAbs'];

		$ResultActionSessionPersonne=mysqli_query($bdd,$ReqActionSessionPersonne);
		
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
					AND form_session_personne.Id=".$_POST['OuvertureAbs']."
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
		
		//Si absence
		if($TableauValeur_Valeur==0 || $TableauValeur_Valeur==-1)
		{
			//Mise à jour de la table besoin
			$ReqBesoinMAJ="UPDATE form_besoin SET Traite=3 WHERE Id=".$Id_Besoin;
			$ResultBesoinMAJ=mysqli_query($bdd,$ReqBesoinMAJ);
			$bEvaluation=0;
			
			$req="SELECT Id FROM form_besoin WHERE Motif='Renouvellement' AND Id=".$Id_Besoin;
			$resultB=mysqli_query($bdd,$req);
			$nbB=mysqli_num_rows($resultB);
			
			if($NBResultSessionQualificationQCM)
			{
				mysqli_data_seek($ResultSessionQualificationQCM,0);
				$QualificationPrecedente="";
				while($RowSessionQualificationQCM=mysqli_fetch_array($ResultSessionQualificationQCM))
				{
					//Affichage des qualifications et des QCM associés à la personne pour la formation de cette session
					if($RowSessionQualificationQCM['ID_PERSONNE']==$Id_PersonneConcernee)
					{
						//Qualifications
						//Etant donné qu'il peut y avoir plusieurs QCM alors on vérifie pour ne prendre en compte qu'une seule qualification
						if($QualificationPrecedente!=$RowSessionQualificationQCM['ID_QUALIFICATION'])
						{
							$leMotif="Suite à absence";
							if($nbB>0){
								$leMotif="Renouvellement";
							}
							Set_EvaluationNote($Id_Besoin, $Id_PersonneConcernee, $RowSessionQualificationQCM['ID_QUALIFICATION'], $RowSessionQualificationQCM['ID_SESSION_PERSONNE_QUALIFICATION'], 0, 0, $Id_TypeFormation,$leMotif,true);
						}
					}
				}
			}
			
			$leMotif="Suite à absence";
			if($nbB>0){
				$leMotif="Renouvellement";
			}
			Creer_BesoinsFormations_PersonnePrestationMetier($Id_PersonneConcernee, 0, 0, 0, $leMotif, $Id_Besoin);	
			
			//ENVOYER MAIL POUR PREVENIR DE L'ABSENCE
			
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
					Id = ".$Id_PersonneConcernee.";";
			
			$rowPersonne = mysqli_fetch_array(getRessource($reqPersonne));
			
			$postes = array($IdPosteAssistantFormationExterne);
			$personnes = array($Id_PersonneConcernee);
			
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
			$ReqBesoinMAJ="UPDATE form_besoin SET Traite=4 WHERE Id=".$Id_Besoin;
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
					Id_Besoin = ".$Id_Besoin." ";
			$ResultSuppBCompetences=mysqli_query($bdd, $ReqSuppBCompetences);

			//On remet les qualifications de la session personne 
			if($NBResultSessionQualificationQCM)
			{
				mysqli_data_seek($ResultSessionQualificationQCM,0);
				$QualificationPrecedente="";
				while($RowSessionQualificationQCM=mysqli_fetch_array($ResultSessionQualificationQCM))
				{
					//Affichage des qualifications et des QCM associés à la personne pour la formation de cette session
					if($RowSessionQualificationQCM['ID_PERSONNE']==$Id_PersonneConcernee)
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
					Id=".$Id_Besoin;
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
								Id<>".$Id_Besoin." 
							AND Id_Prestation=".$RowBesoin['Id_Prestation']." 
							AND Id_Pole=".$RowBesoin['Id_Pole']." 
							AND Id_Formation=".$RowBesoin['Id_Formation']." 
							AND Obligatoire=".$RowBesoin['Obligatoire']." 
							AND Id_Personne=".$Id_PersonneConcernee."
							AND Suppr=0
							AND Traite=0
					)";
			$ResultSuppBCompetences=mysqli_query($bdd, $ReqSuppBCompetences);
			
			//Supprimer besoin créé suite à absence 
			$ReqSuppB="UPDATE form_besoin
				SET Suppr=1,
				Motif_Suppr='Remise en présent'
				WHERE 
					Id<>".$Id_Besoin." 
				AND Id_Prestation=".$RowBesoin['Id_Prestation']." 
				AND Id_Pole=".$RowBesoin['Id_Pole']." 
				AND Id_Formation=".$RowBesoin['Id_Formation']." 
				AND Obligatoire=".$RowBesoin['Obligatoire']." 
				AND Id_Personne=".$Id_PersonneConcernee."
				AND Suppr=0
				AND Traite=0
				";
			$ResultSuppB=mysqli_query($bdd, $ReqSuppB);
		}
	}
	if($_POST['Id_SessionG']<>"" && $_POST['Id_QualificationG']<>"")
	{
		$req="
            SELECT
                form_session_personne_qualification.Id 
			FROM
                form_session_personne_qualification
			LEFT JOIN form_session_personne
			    ON form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id
			WHERE
                form_session_personne_qualification.Suppr=0 
				AND form_session_personne.Suppr=0 
				AND form_session_personne.Validation_Inscription=1
				AND form_session_personne_qualification.Id_Qualification=".$_POST['Id_QualificationG']."
				AND form_session_personne.Id_Session=".$_POST['Id_SessionG'];
		$resultSessionPersQualif=mysqli_query($bdd,$req);
		$NbSessionPersQualif=mysqli_num_rows($resultSessionPersQualif);
		if($NbSessionPersQualif>0)
		{
			while($rowSessionPersonneQualif=mysqli_fetch_array($resultSessionPersQualif))
			{
				if($_POST['BOuvertureG']=="checked"){fermerAccesQCM($rowSessionPersonneQualif['Id']);}
				else{ouvrirAccesQCM($rowSessionPersonneQualif['Id']);}
			}
		}
	}
	
	if($_POST['OuvertureDoc']<>"")
	{
		if($_POST['BOuvertureDoc']=="checked"){fermerAccesDocument($_POST['OuvertureDoc']);}
		else{ouvrirAccesDocument($_POST['OuvertureDoc']);}
	}
	if($_POST['Id_SessionG']<>"" && $_POST['Id_DocumentG']<>"")
	{
		$req="
            SELECT
                form_session_personne_document.Id 
			FROM
                form_session_personne_document
			LEFT JOIN form_session_personne
			    ON form_session_personne_document.Id_Session_Personne=form_session_personne.Id
			WHERE
                form_session_personne_document.Suppr=0 
				AND form_session_personne.Suppr=0 
				AND form_session_personne.Validation_Inscription=1
				AND form_session_personne_document.Id_Document=".$_POST['Id_DocumentG']."
				AND form_session_personne.Id_Session=".$_POST['Id_SessionG'];
		$resultSessionPersDoc=mysqli_query($bdd,$req);
		$NbSessionPersDoc=mysqli_num_rows($resultSessionPersDoc);
		if($NbSessionPersDoc>0)
		{
			while($rowSessionPersonneDoc=mysqli_fetch_array($resultSessionPersDoc))
			{
				if($_POST['BOuvertureGDoc']=="checked"){fermerAccesDocument($rowSessionPersonneDoc['Id']);}
				else{ouvrirAccesDocument($rowSessionPersonneDoc['Id']);}
			}
		}
	}
}
?>
<div id="TABLE_FORM">
<form id="formulaire" action="Gestion_SessionFormation.php" method="post">
<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<input type="hidden" name="Ouverture" id="Ouverture" value="">
<input type="hidden" name="BOuverture" id="BOuverture" value="">
<input type="hidden" name="Id_SessionG" id="Id_SessionG" value="">
<input type="hidden" name="Id_QualificationG" id="Id_QualificationG" value="">
<input type="hidden" name="BOuvertureG" id="BOuvertureG" value="">
<input type="hidden" name="OuvertureDoc" id="OuvertureDoc" value="">
<input type="hidden" name="BOuvertureDoc" id="BOuvertureDoc" value="">
<input type="hidden" name="Id_DocumentG" id="Id_DocumentG" value="">
<input type="hidden" name="BOuvertureGDoc" id="BOuvertureGDoc" value="">
<input type="hidden" name="OuvertureAbs" id="OuvertureAbs" value="">
<input type="hidden" name="BOuvertureAbs" id="BOuvertureAbs" value="">
<table style="width:100%; border-spacing:0; align:center;">
	<thead>
		<tr name="entete1">
			<th>
				<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#6fb543;">
					<tr >
						<td class="TitrePage">
						<?php
							echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
							if($LangueAffichage=="FR"){echo "<img width='20px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
							else{echo "<img width='20px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
							echo "</a></th>";
						?>
						<?php if($LangueAffichage=="FR"){echo "Sessions de formation";}else{echo "Training session";}?></td>
					</tr>
				</table>
			</th>
		</tr>
		<tr name="entete2"><th height="8px"></th></tr>
		<tr name="entete3">
			<th>
				<table style="width:100%; border-spacing:0; align:center;" class="GeneralInfo">
					<tr name="entete3">
						<th>
							<table>
								<tr name="entete3"><th height="4px"></th></tr>
								<tr name="entete3">
									<th class="Libelle" width="8%">&nbsp;<?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </th>
									<th width="20%">
										<select id="Id_Plateforme" name="Id_Plateforme" onchange="submit()">
										<?php
										$Plateforme=0;
										$reqPla="
											SELECT DISTINCT
												Id_Plateforme, 
												(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle 
											FROM
												new_competences_personne_poste_plateforme 
											WHERE
												Id_Poste IN
													(".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.",".$IdPosteFormateur.",".$IdPosteResponsableQualite.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
												AND Id_Personne=".$IdPersonneConnectee." 
											ORDER BY
												Libelle";
										$resultPlateforme=mysqli_query($bdd,$reqPla);
										$nbFormation=mysqli_num_rows($resultPlateforme);
										if($nbFormation>0)
										{
											$selected="";
											if(isset($_POST['Id_Plateforme']))
											{
												if($_POST['Id_Plateforme']==0){$selected="selected";}
											}
											if(isset($_GET['Id_Plateforme']))
											{
												if($_GET['Id_Plateforme']==0){$selected="selected";}
											}
											while($rowplateforme=mysqli_fetch_array($resultPlateforme))
											{
												$selected="";
												if(isset($_POST['Id_Plateforme']))
												{
													if($_POST['Id_Plateforme']==$rowplateforme['Id_Plateforme']){$selected="selected";}
												}
												if(isset($_GET['Id_Plateforme']))
												{
													if($_GET['Id_Plateforme']==$rowplateforme['Id_Plateforme']){$selected="selected";}
												}
												echo "<option value='".$rowplateforme['Id_Plateforme']."' ".$selected.">".$rowplateforme['Libelle']."</option>\n";
												if($Plateforme==0){$Plateforme=$rowplateforme['Id_Plateforme'];}
											}
										}
										if(isset($_POST['Id_Plateforme'])){$Plateforme=$_POST['Id_Plateforme'];}
										if(isset($_GET['Id_Plateforme'])){$Plateforme=$_GET['Id_Plateforme'];}
										?>
										</select>
									</th>
									<th width="28%" class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Formateur";}else{echo "Former";}?> :
										<?php
											$formateur=0;
											
											$formateur=$_SESSION['FiltreFormSessionForm_Formateur'];
											if($_POST){$formateur=$_POST['formateur'];}
											$_SESSION['FiltreFormSessionForm_Formateur']= $formateur;
											
										?>
										<select name="formateur" id="formateur" onchange="submit()">
											<option value="0" selected></option>
										<?php
											$req="SELECT DISTINCT Id, CONCAT(Nom,' ',Prenom) AS Personne ";
											$req.="FROM new_rh_etatcivil ";
											$req.="WHERE Id IN (SELECT Id_Personne FROM new_competences_personne_poste_plateforme WHERE Id_Poste=21 AND Id_Plateforme=".$Plateforme.") ORDER BY Personne ASC";
											$resultFormateur=mysqli_query($bdd,$req);
											$nbFormateurs=mysqli_num_rows($resultFormateur);
											if($nbFormateurs>0)
											{
												while($rowFormateur=mysqli_fetch_array($resultFormateur))
												{
													$selected="";
													if($formateur==$rowFormateur['Id']){$selected="selected";}
													echo "<option value='".$rowFormateur['Id']."' ".$selected.">".$rowFormateur['Personne']."</option>\n";
												}
											}
										?>
										</select>
									</th>
									<th class="Libelle" width="20%">
										&nbsp;
										<?php if($LangueAffichage=="FR"){echo "Date formation";}else{echo "Training date";}?> :
										<?php
											$dateDebut=$_SESSION['FiltreFormSessionForm_Date'];
											if($_POST){$dateDebut=TrsfDate_($_POST['DateDeDebut']);}
											$_SESSION['FiltreFormSessionForm_Date']= $dateDebut;
											
										?>
										<input type="date" style="text-align:center;" id="DateDeDebut" name="DateDeDebut" size="10" value="<?php echo AfficheDateFR($dateDebut); ?>">
									</th>
									<th class="Libelle" width="35%">
										<input class="Bouton" name="BtnDateDebut" size="10" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Actualiser";}else{echo "Actualize";}?>">
									</th>
								</tr>
								<tr name="entete3"><th height="4px"></th></tr>
							</table>
						</th>
					</tr>
				</table>
			</th>
		</tr>
		<tr name="entete4"><th height="4px"></th></tr>
	</thead>
	<tbody>
	<?php
		//Liste des sessions de formation ce jour 
		$req="
            SELECT
                form_session.Id,
                form_session.Id_Formation,
                form_session.Id_Lieu,
                form_session.Id_Formateur,
                form_session_date.Id AS Id_SessionDate,
                form_session.nom_fichier, 
                form_session_date.DateSession,
                form_session_date.Heure_Debut,
                form_session_date.Heure_Fin, 
                form_session.Recyclage,
                form_session_date.PauseRepas,
                form_session_date.HeureDebutPause,
                form_session_date.HeureFinPause, 
                (SELECT Libelle FROM form_lieu WHERE form_lieu.Id=form_session.Id_Lieu) AS Lieu,
                form_session.Id_GroupeSession,form_session.Formation_Liee, 
                (
                    SELECT
                    (
                        SELECT Libelle
                        FROM form_groupe_formation
                        WHERE form_groupe_formation.Id=form_session_groupe.Id_GroupeFormation
                    )
                    FROM form_session_groupe
                    WHERE form_session_groupe.Id=form_session.Id_GroupeSession
                ) AS Groupe, 
                (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session.Id_Formateur) AS Formateur,
                (
                    SELECT Id_Langue
                    FROM form_formation_plateforme_parametres 
				    WHERE form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
				        AND form_formation_plateforme_parametres.Id_Plateforme=".$Plateforme." 
				        AND Suppr=0 LIMIT 1
                ) AS Id_Langue,
                (
                    SELECT
                    (
                        SELECT Libelle
                        FROM form_organisme
                        WHERE Id=Id_Organisme
                    )
                    FROM form_formation_plateforme_parametres 
				    WHERE form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
        				AND form_formation_plateforme_parametres.Id_Plateforme=".$Plateforme." 
        				AND Suppr=0
                    LIMIT 1
                ) AS Organisme,
                (
                    SELECT IF(form_session.Recyclage=1,LibelleRecyclage,Libelle)
					FROM form_formation_langue_infos
					WHERE Id_Formation=form_session.Id_Formation
					   AND Id_Langue=
					   (
                        SELECT Id_Langue 
						FROM form_formation_plateforme_parametres 
						WHERE Id_Plateforme=".$Plateforme."
    						AND Id_Formation=form_session.Id_Formation
    						AND Suppr=0 
						LIMIT 1
                        )
					   AND Suppr=0
                ) AS Libelle						
			FROM
                form_session_date
            LEFT JOIN form_session
                ON form_session_date.Id_Session = form_session.Id 
			WHERE
                form_session_date.Suppr=0 
    			AND form_session.Suppr=0 
    			AND form_session.Annule=0 
    			AND form_session.Diffusion_Creneau=1 
    			AND form_session.Id_Plateforme=".$Plateforme." 
    			AND
				(
					(
						SELECT COUNT(form_formation_qualification.Id) 
						FROM form_formation_qualification_qcm
						LEFT JOIN form_formation_qualification 
						ON form_formation_qualification_qcm.Id_Formation_Qualification=form_formation_qualification.Id
						WHERE form_formation_qualification.Suppr=0 
						AND form_formation_qualification.Id_Formation=form_session.Id_Formation 
						AND form_formation_qualification_qcm.Suppr=0
					)>0
					OR 
					(
						(
							SELECT COUNT(form_formation_document.Id)
							FROM form_formation_document
							WHERE form_formation_document.Suppr=0 
							AND form_formation_document.Id_Document<>6
							AND form_formation_document.Id_Formation=form_session.Id_Formation
						)>0
					)
				)
                AND form_session_date.DateSession='".$dateDebut."'";
			if($formateur>0){$req.=" AND form_session.Id_Formateur=".$formateur." ";}
			
            $req.="ORDER BY form_session_date.Heure_Debut ";
		$resultSessions=mysqli_query($bdd,$req);
		$nbSession=mysqli_num_rows($resultSessions);
		if($nbSession>0)
		{
			mysqli_data_seek($resultSessions,0);
			while($rowSession=mysqli_fetch_array($resultSessions))
			{
				$req="
                    SELECT
                        form_session_personne_qualification.Id,
                        Id_Session_Personne,
                        Id_Qualification,
						Resultat,
                        ResultatMere,
                        Etat,
						Id_QCM,
                        Id_LangueQCM,
						(SELECT Code FROM form_qcm WHERE form_qcm.Id=form_session_personne_qualification.Id_QCM) AS CodeQCM,
						(SELECT CONCAT('(',Libelle,')') FROM form_langue WHERE form_langue.Id=form_session_personne_qualification.Id_LangueQCM) AS Langue,
						Id_QCM_Lie,
                        Id_LangueQCMLie,
						(SELECT Code FROM form_qcm WHERE form_qcm.Id=form_session_personne_qualification.Id_QCM_Lie) AS CodeQCMLie,
						(SELECT CONCAT('(',Libelle,')') FROM form_langue WHERE form_langue.Id=form_session_personne_qualification.Id_LangueQCMLie) AS LangueLie,
						Id_Repondeur,
                        DateHeureRepondeur,
						Id_Ouvreur,
                        DateHeureOuverture,
                        DateHeureFermeture 
					FROM
                        form_session_personne
					LEFT JOIN form_session_personne_qualification 
					   ON form_session_personne.Id=form_session_personne_qualification.Id_Session_Personne
					WHERE
                        form_session_personne.Suppr=0 
    					AND form_session_personne_qualification.Suppr=0 
    					AND form_session_personne.Id_Session=".$rowSession['Id'];

				$resultSessionsPersonne=mysqli_query($bdd,$req);
				$nbSessionPersonne=mysqli_num_rows($resultSessionsPersonne);
				
				$req="
                    SELECT
                        form_session_personne_document.Id,
                        Id_Session_Personne,
                        Id_Document,
                        Id_LangueDocument,
                        (SELECT Fichier_PHP FROM form_document WHERE form_document.Id=form_session_personne_document.Id_Document) AS Fichier_PHP,
						(SELECT Libelle FROM form_langue WHERE form_langue.Id=form_session_personne_document.Id_LangueDocument) AS Langue,
						Id_Repondeur,
                        DateHeureRepondeur,
						Id_Ouvreur,
                        DateHeureOuverture,
                        DateHeureFermeture 
					FROM
                        form_session_personne
					LEFT JOIN form_session_personne_document 
					  ON form_session_personne.Id=form_session_personne_document.Id_Session_Personne
					WHERE
                        form_session_personne.Suppr=0 
						AND form_session_personne_document.Suppr=0 
						AND form_session_personne.Id_Session=".$rowSession['Id'];
				$resultSessionsPersonneDoc=mysqli_query($bdd,$req);
				$nbSessionPersonneDoc=mysqli_num_rows($resultSessionsPersonneDoc);
			?>
				<tr name="nepasmasquer">
					<td>
						<table class="TableCompetences" style="width:100%;">
							<tr name="Session_<?php echo $rowSession['Id']; ?>" >
								<td class="EnTeteTableauCompetences" style="color:#0026e0;height:25px;">
									&nbsp;&nbsp;&nbsp;<a href="javascript:onclick=Voir_TR('Session_<?php echo $rowSession['Id']; ?>')">
									<?php echo strtoupper($rowSession['Libelle']." ".$rowSession['Organisme'])."&nbsp;&nbsp;&nbsp; - &nbsp;&nbsp;&nbsp;".AfficheDateJJ_MM_AAAA($rowSession['DateSession'])."&nbsp;&nbsp; - &nbsp;&nbsp;".substr($rowSession['Heure_Debut'],0,5)." / ".substr($rowSession['Heure_Fin'],0,5); ?>
									</a>
								</td>
							</tr>
							<tr><td height="4px"></td></tr>
							<tr>
								<td>
								<?php 
									if($LangueAffichage=="FR"){echo "<u>Lieu de la formation </u>: ";}else{echo "<u>Place of training </u>: ";}
									echo $rowSession['Lieu'];
								?>
								</td>
							</tr>
							<tr><td height="4px"></td></tr>
							<tr>
								<td width="95%" align="center">
									<table style="width:100%;">
									<?php
									//Liste des personnes inscrites à la session 
									$ResultPersonnes=getRessource(getchaineSQL_sessionPersonne($rowSession['Id']));
									$NbPersonne=mysqli_num_rows($ResultPersonnes);
									
									//Liste des qualifications acquises pour cette formation
									$reqQualif="
										SELECT DISTINCT form_formation_qualification.Id, Id_Qualification,
										(SELECT Libelle FROM new_competences_qualification WHERE new_competences_qualification.Id=Id_Qualification) AS Qualif 
										FROM form_formation_qualification 
										WHERE Id_Formation=".$rowSession['Id_Formation']." 
										AND Suppr=0
										AND Masquer=0 ";
									$resultQualif=mysqli_query($bdd,$reqQualif);
									$NbQualif=mysqli_num_rows($resultQualif);
									
									//Liste des documents complémentaires pour cette formation
									$reqDoc="
										SELECT DISTINCT Id_Document,
										(SELECT Reference FROM form_document WHERE form_document.Id=Id_Document) AS Document 
										FROM form_formation_document
										WHERE Id_Formation=".$rowSession['Id_Formation']." 
										AND Suppr=0 ";
									$resultDoc=mysqli_query($bdd,$reqDoc);
									$NbDoc=mysqli_num_rows($resultDoc);
									
									echo "<tr>
										<td width='20%'></td>
										<td width='5%' bgcolor='#1611a9' style='border:1px solid #5e5e5e;color:#ffffff;' rowspan='3' align='center'>";
									if($LangueAffichage=="FR"){echo " Absent ";}
											else{echo " Absent ";};
									echo "</td>";
									if($NbQualif>0)
									{
										while($RowQualif=mysqli_fetch_array($resultQualif))
										{
										    echo "<td width='80%' bgcolor='#cccccc' style='border:1px solid #5e5e5e;' colspan='3'>".$RowQualif['Qualif'];
											echo "</td>";
										}
									}
									if($NbDoc>0)
									{
										while($RowDoc=mysqli_fetch_array($resultDoc))
										{
										    echo "<td width='80%' bgcolor='#cccccc' style='border:1px solid #5e5e5e;' colspan='3' rowspan='2'>".$RowDoc['Document']."</td>";
										}
									}
									echo "  </tr>
                                            <tr>
                                                <td width='20%'></td>";
									if($NbQualif>0)
									{
										mysqli_data_seek($resultQualif,0);
										while($RowQualif=mysqli_fetch_array($resultQualif))
										{
											echo '<td bgcolor="#cccccc" style="border:1px solid #5e5e5e;width:10%;" colspan="3" class="Libelle">';
											if($LangueAffichage=="FR"){echo "QCM : ";}
											else{echo "MCQ : ";};
											echo '<select name="qcm_mere_'.$rowSession['Id'].'_'.$rowSession['Id_Formateur'].'_'.$RowQualif['Id'].'" id="qcm_mere_'.$rowSession['Id'].'_'.$rowSession['Id_Formateur'].'_'.$RowQualif['Id'].'" onchange="submit()">';
											$req="SELECT DISTINCT 
												form_formation_qualification_qcm.Id_QCM,
												form_formation_qualification_qcm.Id_Langue,
												form_qcm.Code AS QCM,
												form_qcm.Id_QCM_Lie,
												(SELECT form_qcm2.Code FROM form_qcm AS form_qcm2 WHERE form_qcm2.Id=form_qcm.Id_QCM_Lie) AS QCMLie
												FROM form_formation_qualification_qcm
												LEFT JOIN form_qcm
												ON form_formation_qualification_qcm.Id_QCM=form_qcm.Id
												WHERE Id_Formation_Qualification=".$RowQualif['Id']."
												AND form_formation_qualification_qcm.Suppr=0 
												AND form_qcm.Suppr=0 ";
											$Id_QCM=0;
											$Id_LangueQCM=0;
											$Id_QCMLie=0;
											$CodeQCMLie="";
											if(isset($_POST['qcm_mere_'.$rowSession['Id'].'_'.$rowSession['Id_Formateur'].'_'.$RowQualif['Id']]))
											{
											    $Id_QCM=$_POST['qcm_mere_'.$rowSession['Id'].'_'.$rowSession['Id_Formateur'].'_'.$RowQualif['Id']];
											}
											$resultQCM=mysqli_query($bdd,$req);
											$nbQCM=mysqli_num_rows($resultQCM);
											if($nbQCM>0)
											{
												while($rowQCM=mysqli_fetch_array($resultQCM))
												{
													$selected="";
													if($Id_QCM==0)
													{
														$Id_QCM=$rowQCM['Id_QCM'];
														$Id_LangueQCM=$rowQCM['Id_Langue'];
														$Id_QCMLie=$rowQCM['Id_QCM_Lie'];
														$CodeQCMLie=$rowQCM['QCMLie'];
														$selected="selected";
													}
													elseif($Id_QCM==$rowQCM['Id_QCM'])
													{
														$Id_LangueQCM=$rowQCM['Id_Langue'];
														$Id_QCMLie=$rowQCM['Id_QCM_Lie'];
														$CodeQCMLie=$rowQCM['QCMLie'];
														$selected="selected";
													}
													echo "<option value='".$rowQCM['Id_QCM']."' ".$selected." >".stripslashes($rowQCM['QCM'])."</option>";
												}
											}
											echo '</select>';
											if($LangueAffichage=="FR"){echo "&nbsp;&nbsp;&nbsp;&nbsp;Langue : ";}
											else{echo "&nbsp;&nbsp;&nbsp;&nbsp;Language : ";}
											echo '<select name="langue_qcm_mere_'.$rowSession['Id'].'_'.$rowSession['Id_Formateur'].'_'.$RowQualif['Id'].'" id="langue_qcm_mere_'.$rowSession['Id'].'_'.$rowSession['Id_Formateur'].'_'.$RowQualif['Id'].'">';
											$req="SELECT DISTINCT 
												form_qcm_langue.Id,
												form_qcm_langue.Id_Langue,
												form_langue.Libelle AS Langue
												FROM form_qcm_langue
												LEFT JOIN form_langue
												ON form_qcm_langue.Id_Langue=form_langue.Id
												WHERE form_qcm_langue.Id_QCM=".$Id_QCM."
												AND form_qcm_langue.Suppr=0 
												AND form_langue.Suppr=0 ";
											$resultLangue=mysqli_query($bdd,$req);
											$nbLangue=mysqli_num_rows($resultLangue);
											if($nbLangue>0)
											{
												while($rowLangue=mysqli_fetch_array($resultLangue))
												{
													$selected="";
													if($Id_LangueQCM==$rowLangue['Id_Langue']){$selected="selected";}
													echo "<option value='".$rowLangue['Id']."' ".$selected." >".stripslashes($rowLangue['Langue'])."</option>";
												}
											}
											echo '</select>';
											//Récupération du QCM Langue
											echo "&nbsp;&nbsp;<a class='Modif' href=\"javascript:OuvreExcel('langue_qcm_mere_".$rowSession['Id']."_".$rowSession['Id_Formateur']."_".$RowQualif['Id']."');\">
											<img src='../../Images/excel.gif' style='border:0;' alt='QCM'>&nbsp;&nbsp;&nbsp;&nbsp;
											</a>";
											if($Id_QCMLie>0)
											{
												echo "<br>";
												 if($LangueAffichage=="FR"){echo "QCM lié : ";}else{echo "Linked QCM : ";}
												echo stripslashes($CodeQCMLie);
												if($LangueAffichage=="FR"){echo "&nbsp;&nbsp;&nbsp;&nbsp;Langue : ";}
												else{echo "&nbsp;&nbsp;&nbsp;&nbsp;Language : ";}
												echo '<input type="hidden" name="qcmlie_fille_'.$rowSession['Id'].'_'.$rowSession['Id_Formateur'].'_'.$RowQualif['Id'].'" value="'.$Id_QCMLie.'" />';
												echo '<select name="langue_qcmlie_fille_'.$rowSession['Id'].'_'.$rowSession['Id_Formateur'].'_'.$RowQualif['Id'].'" id="langue_qcmlie_fille_'.$rowSession['Id'].'_'.$rowSession['Id_Formateur'].'_'.$RowQualif['Id'].'">';
												$req="SELECT DISTINCT 
													form_qcm_langue.Id,
													form_qcm_langue.Id_Langue,
													form_langue.Libelle AS Langue
													FROM form_qcm_langue
													LEFT JOIN form_langue
													ON form_qcm_langue.Id_Langue=form_langue.Id
													WHERE form_qcm_langue.Id_QCM=".$Id_QCMLie."
													AND form_qcm_langue.Suppr=0 
													AND form_langue.Suppr=0 ";
												$resultLangue=mysqli_query($bdd,$req);
												$nbLangue=mysqli_num_rows($resultLangue);
												if($nbLangue>0)
												{
													while($rowLangue=mysqli_fetch_array($resultLangue))
													{
														$selected="";
														if($Id_LangueQCM==$rowLangue['Id_Langue']){$selected="selected";}
														echo "<option value='".$rowLangue['Id']."' ".$selected." >".stripslashes($rowLangue['Langue'])."</option>";
													}
												}
												echo '</select>';
												//Récupération du QCM Langue
												echo "&nbsp;&nbsp;<a class='Modif' href=\"javascript:OuvreExcel('langue_qcmlie_fille_".$rowSession['Id']."_".$rowSession['Id_Formateur']."_".$RowQualif['Id']."');\">
												<img src='../../Images/excel.gif' style='border:0;' alt='QCM'>&nbsp;&nbsp;&nbsp;&nbsp;
												</a>";
											}
											echo "</td>";
										}
									}
									echo "</tr>";
									echo "<tr>";
										echo "<td width='20%'></td>";
									if($NbQualif>0)
									{
										mysqli_data_seek($resultQualif,0);
										while($RowQualif=mysqli_fetch_array($resultQualif))
										{
											$req="SELECT form_session_personne_qualification.Id 
												FROM form_session_personne_qualification
												LEFT JOIN form_session_personne
												ON form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id
												WHERE form_session_personne_qualification.Suppr=0 
												AND form_session_personne.Suppr=0 
												AND form_session_personne.Validation_Inscription=1
												AND form_session_personne_qualification.Id_Qualification=".$RowQualif['Id_Qualification']."
												AND form_session_personne.Id_Session=".$rowSession['Id'];
											$resultSessionPersQualif=mysqli_query($bdd,$req);
											$NbSessionPersQualif=mysqli_num_rows($resultSessionPersQualif);
											
											$req="SELECT form_session_personne_qualification.Id 
												FROM form_session_personne_qualification
												LEFT JOIN form_session_personne
												ON form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id
												WHERE form_session_personne_qualification.Suppr=0 
												AND form_session_personne.Suppr=0 
												AND form_session_personne.Validation_Inscription=1
												AND form_session_personne_qualification.DateHeureOuverture>0
												AND form_session_personne_qualification.DateHeureFermeture=0
												AND form_session_personne_qualification.Id_Qualification=".$RowQualif['Id_Qualification']."
												AND form_session_personne.Id_Session=".$rowSession['Id'];
											$resultSessionPersQualifOuver=mysqli_query($bdd,$req);
											$NbSessionPersQualifOuvert=mysqli_num_rows($resultSessionPersQualifOuver);
											$checkedGlobal="";
											if($NbSessionPersQualif==$NbSessionPersQualifOuvert){$checkedGlobal="checked";}
											
											echo "<td bgcolor='#1612a9' style='border:1px solid #5e5e5e;text-align:center;color:#ffffff;'>";
											if($LangueAffichage=="FR"){echo "QCM";}
											else{echo "MCQ";}
											if($NbSessionPersQualifOuvert==0){
												echo "&nbsp;&nbsp;&nbsp;<input class='Bouton' name='ModifQCM' size='10' type='Button' style='cursor:pointer;' value='M' onclick='javascript:ModifierQCMGlobal(".$rowSession['Id'].",".$RowQualif['Id_Qualification'].")'>&nbsp;";
											}
											echo "</td>";
											echo "<td bgcolor='#1612a9' style='border:1px solid #5e5e5e;text-align:center;color:#ffffff;'>";
											if($LangueAffichage=="FR"){echo "Ouverture";}
											else{echo "Opening";}
											echo "&nbsp;
                                                <label class='switch'>
												  <input type='checkbox' id='CBGlobal_".$RowQualif['Id_Qualification']."_".$rowSession['Id']."' name='CBGlobal_".$RowQualif['Id_Qualification']."_".$rowSession['Id']."' ".$checkedGlobal." onchange='javascript:OuvrirFermerAccesGlobal(".$rowSession['Id'].",".$RowQualif['Id_Qualification'].",\"".$checkedGlobal."\");'>                          
												  <span class='slider round'></span>
										        </label>";
											echo "</td>";
											echo "<td bgcolor='#1612a9' style='border:1px solid #5e5e5e;text-align:center;color:#ffffff;'>";
											if($LangueAffichage=="FR"){echo "Résultat";}
											else{echo "Result";}
											echo "</td>";
										}
									}
									if($NbDoc>0)
									{
										mysqli_data_seek($resultDoc,0);
										while($RowDoc=mysqli_fetch_array($resultDoc))
										{
											$req="SELECT form_session_personne_document.Id 
												FROM form_session_personne_document
												LEFT JOIN form_session_personne
												ON form_session_personne_document.Id_Session_Personne=form_session_personne.Id
												WHERE form_session_personne_document.Suppr=0 
												AND form_session_personne.Suppr=0 
												AND form_session_personne.Validation_Inscription=1
												AND form_session_personne_document.Id_Document=".$RowDoc['Id_Document']."
												AND form_session_personne.Id_Session=".$rowSession['Id'];
											$resultSessionPersDoc=mysqli_query($bdd,$req);
											$NbSessionPersDoc=mysqli_num_rows($resultSessionPersDoc);
											
											$req="SELECT form_session_personne_document.Id 
												FROM form_session_personne_document
												LEFT JOIN form_session_personne
												ON form_session_personne_document.Id_Session_Personne=form_session_personne.Id
												WHERE form_session_personne_document.Suppr=0 
												AND form_session_personne.Suppr=0 
												AND form_session_personne.Validation_Inscription=1
												AND form_session_personne_document.DateHeureOuverture>0
												AND form_session_personne_document.DateHeureFermeture=0
												AND form_session_personne_document.Id_Document=".$RowDoc['Id_Document']."
												AND form_session_personne.Id_Session=".$rowSession['Id'];
											$resultSessionPersDocOuver=mysqli_query($bdd,$req);
											$NbSessionPersDocOuvert=mysqli_num_rows($resultSessionPersDocOuver);
											$checkedGlobal="";
											if($NbSessionPersDoc==$NbSessionPersDocOuvert && $NbSessionPersDoc>0){$checkedGlobal="checked";}
											echo "<td bgcolor='#1612a9' style='border:1px solid #5e5e5e;text-align:center;color:#ffffff;'>";
											if($LangueAffichage=="FR"){echo "Langue";}
											else{echo "Language";}
											if($NbSessionPersDocOuvert==0){
												echo "&nbsp;&nbsp;&nbsp;<input class='Bouton' name='ModifDoc' size='10' type='Button' style='cursor:pointer;' value='M' onclick='javascript:ModifierDocGlobal(".$rowSession['Id'].",".$RowDoc['Id_Document'].")'>&nbsp;";
											}
											echo "</td>";
											echo "<td bgcolor='#1612a9' style='border:1px solid #5e5e5e;text-align:center;color:#ffffff;'>";
											if($LangueAffichage=="FR"){echo "Ouverture";}
											else{echo "Opening";}
											echo "&nbsp;
                                                <label class='switch'>
												  <input type='checkbox' id='CBDocGlobal_".$RowDoc['Id_Document']."_".$rowSession['Id']."' name='CBDocGlobal_".$RowDoc['Id_Document']."_".$rowSession['Id']."' ".$checkedGlobal." onchange='javascript:OuvrirFermerAccesGlobalDoc(".$rowSession['Id'].",".$RowDoc['Id_Document'].",\"".$checkedGlobal."\");'>
												  <span class='slider round'></span>
										        </label>";
											echo "</td>";
											echo "<td bgcolor='#1612a9' style='border:1px solid #5e5e5e;text-align:center;color:#ffffff;'>";
											if($LangueAffichage=="FR"){echo "Répondu";}
											else{echo "Answered";}
											echo "</td>";
										}
									}
									echo "</tr>";
									if($NbPersonne>0)
									{
										$couleur="#eeeeee";
										while($rowPersonne=mysqli_fetch_array($ResultPersonnes))
										{
											$checkedAbs="";
											if($rowPersonne['PRESENCE']==-1){$checkedAbs="checked";}
											echo "<tr bgcolor='".$couleur."'>";
												echo "<td style='border:1px solid #5e5e5e;'>";
												echo "<input class='Bouton' name='ModifProfil' size='10' type='Button' style='cursor:pointer;' value='P' onclick='javascript:OuvreFenetreProfil(\"Lecture\",\"".$rowPersonne['ID_PERSONNE']."\")'>&nbsp;";
												echo "<a class='TableCompetences' href='javascript:OuvreFenetreIdentifiants(\"".$rowPersonne['ID_PERSONNE']."\");'>".$rowPersonne['STAGIAIRE_NOMPRENOM']."</a></td>";
												echo "<td style='border:1px solid #5e5e5e;'>";
												echo " <label class=\"switch\">
													  <input type=\"checkbox\" id=\"CAbs_".$rowPersonne['ID']."\" name=\"CAbs_".$rowPersonne['ID']."\" ".$checkedAbs." onchange='javascript:CheckAbs(".$rowPersonne['ID'].",\"".$checkedAbs."\")'>                          
													  <span class=\"slider round\" ></span>
													</label>";
												echo "</td>";
											if($NbQualif>0)
											{
												mysqli_data_seek($resultQualif,0);
												while($RowQualif=mysqli_fetch_array($resultQualif))
												{
													$QCM="";
													$QCMLie="";
													$checked="";
													$Id_SessionPersonneQualification=0;
													$resultat="";
													$resultatMere="";
													$repondu="";
													$Etat="";
													$suppr=0;
													if($nbSessionPersonne>0)
													{
														mysqli_data_seek($resultSessionsPersonne,0);
														while($rowSessionPersonne=mysqli_fetch_array($resultSessionsPersonne))
														{
															if($rowSessionPersonne['Id_Session_Personne']==$rowPersonne['ID'] && $rowSessionPersonne['Id_Qualification']==$RowQualif['Id_Qualification'])
															{

																$QCM=$rowSessionPersonne['CodeQCM']." ".$rowSessionPersonne['Langue']."";
																$QCMLie=$rowSessionPersonne['CodeQCMLie']." ".$rowSessionPersonne['LangueLie']."";
																if($rowSessionPersonne['DateHeureOuverture']>"0001-01-01" && $rowSessionPersonne['DateHeureFermeture']<="0001-01-01"){$checked="checked";}
																$Id_SessionPersonneQualification=$rowSessionPersonne['Id'];
																if($rowSessionPersonne['DateHeureRepondeur']>"0001-01-01")
																{

																	if($rowSessionPersonne['CodeQCMLie']<>"")
																	{
																		if($LangueAffichage=="FR"){$resultatMere="QCM mère : ".$rowSessionPersonne['ResultatMere']."<br>";}
																		else{$resultatMere="MCQ mother : ".$rowSessionPersonne['ResultatMere']."<br>";}
																		if($LangueAffichage=="FR"){$resultat="Note finale : ";}
																	    else{$resultat="Final note : ";}
																	}
																	$resultat.=$rowSessionPersonne['Resultat'];
																	if($LangueAffichage=="FR")
																	{
																		if($rowSessionPersonne['Etat']==1){$Etat="<br><font color='#2dbe29'>Réussite</font>";}
																		elseif($rowSessionPersonne['Etat']==-1){$Etat="<br><font color='#e80000'>Echec</font>";}
																	}
																	else
																	{
																		if($rowSessionPersonne['Etat']==1){$Etat="<br><font color='#2dbe29'>Success</font>";}
																		elseif($rowSessionPersonne['Etat']==-1){$Etat="<br><font color='#e80000'>Failure</font>";}
																	}
																}
																if($rowSessionPersonne['Resultat']=="" && $rowSessionPersonne['ResultatMere']<>"" && $rowSessionPersonne['DateHeureRepondeur']>'0001-01-01'){
																	$suppr=1;
																}
															}
														}
													}
	
													echo "<td style='border:1px solid #5e5e5e;'>";
													echo "<table>";
													echo "<tr>";
													if($checked=="" && $checkedAbs=="")
													{
														
														if($resultat<>""){
															if($LangueAffichage=="FR"){
																echo "<td><input class='Bouton' name='ModifQCM' size='10' type='Button' style='cursor:pointer;' value='M' onclick='if(window.confirm(\"Attention le QCM existant sera supprimé. Voulez-vous continuer ?\")){ModifierQCM(".$Id_SessionPersonneQualification.");}'>&nbsp;</td>";
															}
															else{
																echo "<td><input class='Bouton' name='ModifQCM' size='10' type='Button' style='cursor:pointer;' value='M' onclick='if(window.confirm(\"Attention the existing QCM will be deleted. Do you want to continue ?\")){ModifierQCM(".$Id_SessionPersonneQualification.");}'>&nbsp;</td>";
															}
														}
														else{
															echo "<td><input class='Bouton' name='ModifQCM' size='10' type='Button' style='cursor:pointer;' value='M' onclick='javascript:ModifierQCM(".$Id_SessionPersonneQualification.")'>&nbsp;</td>";
														}
													}
													if($checkedAbs=="")
													{
														echo "<td><a href='javascript:QCM_Web(\"".$Id_SessionPersonneQualification."\");'>".$QCM."<br>".$QCMLie."</a></td>";
													}
													else{
														echo "<td></td>";
													}
													echo "</tr>";
													echo "</table>";
													echo"</td>";
													echo "<td style='border:1px solid #5e5e5e;' align='center'>";
													if($checkedAbs=="")
													{
														echo " <label class=\"switch\">
														  <input type=\"checkbox\" id=\"CB_".$Id_SessionPersonneQualification."\" name=\"CB_".$Id_SessionPersonneQualification."\" ".$checked." onchange='javascript:OuvrirFermerAcces(".$Id_SessionPersonneQualification.",\"".$checked."\")'>                          
														  <span class=\"slider round\" ></span>
														</label>";
													}
													echo"</td>";
													echo "<td style='border:1px solid #5e5e5e;' align='center'>";
													if($checkedAbs=="")
													{
														echo $resultatMere.$resultat.$Etat;
														if($Etat==""){
															$req="
																SELECT
																	Id
																FROM
																	form_session_personne_qualification_question
																WHERE
																	form_session_personne_qualification_question.Suppr=1 
																	AND form_session_personne_qualification_question.Id_Session_Personne_Qualification=".$Id_SessionPersonneQualification."
																ORDER BY 
																	form_session_personne_qualification_question.Id DESC
																	";
															$resultSessionsPersonneOLD=mysqli_query($bdd,$req);
															$nbSessionPersonneOLD=mysqli_num_rows($resultSessionsPersonneOLD);
															if($nbSessionPersonneOLD>0 || $suppr==1){
																echo "<input class='Bouton' name='RecupererQCMSuppr' size='10' type='Button' style='cursor:pointer;' value='R' onclick='javascript:RecupererQCM(".$Id_SessionPersonneQualification.")'>";
															}
														}
													}
													echo"</td>";
												}
											}
											if($NbDoc>0)
											{
												mysqli_data_seek($resultDoc,0);
												while($RowDoc=mysqli_fetch_array($resultDoc))
												{
													$Langue="";
													$checked="";
													$repondu="";
													$Id_SessionPersonneDoc=0;
													if($nbSessionPersonneDoc>0){
														mysqli_data_seek($resultSessionsPersonneDoc,0);
														while($rowSessionPersonneDoc=mysqli_fetch_array($resultSessionsPersonneDoc))
														{
															if($rowSessionPersonneDoc['Id_Session_Personne']==$rowPersonne['ID'] && $rowSessionPersonneDoc['Id_Document']==$RowDoc['Id_Document'])
															{
																$Langue=$rowSessionPersonneDoc['Langue']."";
																if($rowSessionPersonneDoc['DateHeureOuverture']>"0001-01-01" && $rowSessionPersonneDoc['DateHeureFermeture']<="0001-01-01"){$checked="checked";}
																if($rowSessionPersonneDoc['DateHeureRepondeur']>"0001-01-01")
																{
																	$repondu="
                                                                        V
                                                                        &nbsp;&nbsp;
                                                                        <a class='Modif' href=\"javascript:OuvreDocument('".$rowSessionPersonneDoc['Fichier_PHP']."',".$rowSessionPersonneDoc['Id'].");\">
											                               <img width='20px' src='../../Images/pdf.png' style='border:0;' alt='Document'>&nbsp;&nbsp;&nbsp;&nbsp;
											                            </a>";
																}
																$Id_SessionPersonneDoc=$rowSessionPersonneDoc['Id'];
															}
														}
													}
													echo "<td style='border:1px solid #5e5e5e;'>";
													echo "<table>";
													echo "<tr>";
													if($checked=="" && $checkedAbs==""){
														echo "<td><input class='Bouton' name='ModifDoc' size='10' type='Button' style='cursor:pointer;' value='M' onclick='javascript:ModifierDoc(".$Id_SessionPersonneDoc.")'>&nbsp;</td>";
													}
													if($checkedAbs=="")
													{
														echo "<td><a href='javascript:Doc_Web(\"".$Id_SessionPersonneDoc."\");'>".$Langue."</a></td>";
													}
													else{
														echo "<td></td>";
													}
													
													echo "</tr>";
													echo "</table>";
													echo"</td>";
													echo "<td style='border:1px solid #5e5e5e;' align='center'>";
													if($checkedAbs=="")
													{
														echo " <label class=\"switch\">
														  <input type=\"checkbox\" id=\"CB_Doc_".$Id_SessionPersonneDoc."\" name=\"CB_Doc_".$Id_SessionPersonneDoc."\" ".$checked." onchange='javascript:OuvrirFermerAccesDoc(".$Id_SessionPersonneDoc.",\"".$checked."\")'>                          
														  <span class=\"slider round\" ></span>
														</label>";
													}
													echo"</td>";
													echo "<td style='border:1px solid #5e5e5e;' align='center'>";
													if($checkedAbs=="")
													{
														echo $repondu;
													}
													echo"</td>";
												}
											}
											echo "</tr>";
											if($couleur=="#eeeeee"){$couleur="#ffffff";}
											else{$couleur="#eeeeee";}
										}
									}
									?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="20px"></td></tr>
			<?php
			}
		}
	?>
	<tr><td height="500px"></td></tr>
	</tbody>
</table>
</form>
</div>
<script language="javascript">
<!--
Masquer_Tout();
-->
</script>
</body>
</html>
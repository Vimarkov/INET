<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Lister les personnes d'une session</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script type="text/javascript">
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
if($_POST){$ID=$_POST['Id'];}
else{$ID=$_GET['Id'];}

function getchaineSQL_SessionInfoMinime($id)
{
	$req=
		"SELECT
			Id_Formation
		FROM
			form_session
		WHERE
			form_session.Id=".$id;
	return $req;
}

function getChaineSQL_SessionQualificationQCM ($id_session)
{
	$req="
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
				form_session_personne_qualification_question.Id_QCM AS ID_QCM,
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
				LEFT JOIN form_session_personne_qualification_question ON form_session_personne_qualification_question.Id_Session_Personne_Qualification=form_session_personne_qualification.Id
				LEFT JOIN new_competences_qualification ON new_competences_qualification.Id=form_session_personne_qualification.Id_Qualification
				LEFT JOIN form_session_personne ON form_session_personne.Id=form_session_personne_qualification.Id_Session_Personne
				LEFT JOIN form_qcm ON form_session_personne_qualification_question.Id_QCM=form_qcm.Id
				LEFT JOIN form_qcm_langue ON form_qcm_langue.Id_QCM=form_qcm.Id
				LEFT JOIN form_langue ON form_langue.Id=form_qcm_langue.Id_Langue
			WHERE
				form_session_personne_qualification.Suppr=0
				AND form_session_personne_qualification.Etat<>0
				AND form_session_personne.Id_Session=".$id_session."
			ORDER BY
				DATE_INSCRIPTION_PERSONNE,
                ID_SESSION_PERSONNE,
				ID_QUALIFICATION,
				ID_QCM
		) AS TABLE_TEMP
		WHERE
			TABLE_TEMP.SUPPR_QCM=0 
			AND TABLE_TEMP.SUPPR_QCM_LANGUE=0
			AND TABLE_TEMP.SUPPR_LANGUE=0 ";
	return $req;
}

/**
 * getChainesSQL_resultatEvaluation
 * 
 * Recupere le résultat et l'evaluation d'une personne a une qualification
 * 
 * @param int $Id_Besoin Identifiant du besoin
 * @param int $Id_Qualification Identifiant de la qualification
 * 
 * @return string La requete SQL
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function getChainesSQL_resultatEvaluation($Id_Besoin, $Id_Qualification)
{
	$req="
		SELECT
			IF(Resultat_QCM<>'',CONCAT(Resultat_QCM,' %'),Resultat_QCM) AS Resultat_QCM,
			Evaluation
		FROM
			new_competences_relation
		WHERE
			Id_Besoin=".$Id_Besoin."
			AND Id_Qualification_Parrainage = ".$Id_Qualification.";";
	return $req;
}

//Execution des requetes
$ResultSessionInfoMinime=getRessource(getChaineSQL_SessionInfoMinime($ID));
$RowSessionInfoMinime=mysqli_fetch_array($ResultSessionInfoMinime);

//RECUPERATION DES QCM EN FONCTION DES QUALIFICATIONS DE LA FORMATION POUR LA SESSION EN COURS
$ReqSessionQualificationQCM="
	SELECT
		TABLE_TEMP.*
	FROM
		(
		SELECT DISTINCT
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
			IF(ISNULL(form_qcm.Id),0,IF(form_qcm.Id_QCM_Lie=0,form_qcm.Id,CONCAT(form_qcm.Id_QCM_Lie,'|',form_qcm.Id))) AS ID_QCM_ID_QCM_LIE
		FROM
			form_session_personne_qualification
			LEFT JOIN form_session_personne_qualification_question ON form_session_personne_qualification_question.Id_Session_Personne_Qualification=form_session_personne_qualification.Id
			LEFT JOIN new_competences_qualification ON new_competences_qualification.Id=form_session_personne_qualification.Id_Qualification
			LEFT JOIN form_session_personne ON form_session_personne.Id=form_session_personne_qualification.Id_Session_Personne
			LEFT JOIN form_qcm ON form_session_personne_qualification_question.Id_QCM=form_qcm.Id
			LEFT JOIN form_qcm_langue ON form_qcm_langue.Id_QCM=form_qcm.Id
			LEFT JOIN form_langue ON form_langue.Id=form_qcm_langue.Id_Langue
		WHERE
			form_session_personne_qualification.Suppr=0
			AND form_session_personne_qualification.Etat<>0
			AND form_session_personne.Id_Session=".$ID."
		ORDER BY
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

$ResultSession=get_session($ID);
$RowSession=mysqli_fetch_array($ResultSession);

$ResultSessionPersonnes=getRessource(getchaineSQL_sessionPersonne($ID));

//Recuperation du nombre de personnes inscrites
$ResultNombreInscritSession=getRessource(getchaineSQL_NbInscritSession($ID));
$RowNombreInscritSession=mysqli_fetch_array($ResultNombreInscritSession);
?>
<!--  AFFICHAGE DE LA LISTE DES PERSONNES POUR LA SESSION DE FORMATION  -->
<form id="formulaire_liste_personnes" method="POST" action="Contenu_Session.php">
	<input type="hidden" name="Id" value="<?php echo $ID;?>">
	<input type="hidden" name="Id_TypeFormation" value="<?php echo $RowSession['ID_TYPEFORMATION'];?>">
	<input type="hidden" name="Nb_Stagiaire_Maxi" value="<?php echo $RowSession['NB_STAGIAIRE_MAXI'];?>">
	<input type="hidden" name="Nb_Inscrits" value="<?php echo $RowNombreInscritSession['NOMBRE'];?>">
	<input type="hidden" name="Action" Id="Action" value="">
	<table style="width:95%; align:center;">
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
					echo " From ".AfficheDateJJ_MM_AAAA($RowSession['DATE_DEBUT'])." at ".substr($RowSession['HEURE_DEBUT'],0,-3)." to ".AfficheDateJJ_MM_AAAA($RowSession['DATE_FIN'])." at ".substr($RowSession['HEURE_FIN'],0,-3);
					echo " situated at ".$RowSession['LIEU'];
				}
			?>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td>
				<table class="TableCompetences" style="width:100%;">
					<tr><td><b><?php if($LangueAffichage=="FR"){echo "Convocation";}else{echo "Invitation";}?> :</b></td></tr>
					<?php
						//Lecture
						$ressource = getRessource(getChaineSQL_getInfosDocument($ID));
						$rowConvoc = mysqli_fetch_array($ressource);
									
						if($rowConvoc['nom_fichier'] <> "" && !is_null($rowConvoc['nom_fichier']))
						{
							echo "<tr><td><a class=\"Info\" href=\"".$rowConvoc['chemin_fichier'].$rowConvoc['nom_fichier']."\" target=\"_blank\">".$rowConvoc['nom_fichier']."</a>";
							echo "</td></tr>";
						}
					?>
				</table>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td>
				<table class="TableCompetences" style="width:100%;">
					<tr>
						<td rowspan="2" class="Libelle">
							<?php
							if($LangueAffichage=="FR"){echo "Stagiaire";}else{echo "Trainee";}
							echo "<br>";
							echo "Mini : ".$RowSession['NB_STAGIAIRE_MINI']."<br>"."Maxi : ".$RowSession['NB_STAGIAIRE_MAXI'];
							?>
						</td>
						<td rowspan="2" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?></td>
						<td rowspan="2" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Inscrit par";}else{echo "Registered by";}?></td>
						<td rowspan="2" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Inscrit le";}else{echo "Join on";}?></td>
						<td rowspan="2" valign="middle" class="Libelle" style='border-right:1px solid #6fa3fd;'><?php if($LangueAffichage=="FR"){echo "Convocation";}else{echo "Convocation";}?></td>
						<td rowspan="2" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Inscr.";}else{echo "Registered";}?><br>ok</td>
						<td rowspan="2" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Conv.";}else{echo "Convocation";}?><br>ok</td>
						<td rowspan="2" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Prés";}else{echo "Present";}?>.<br>ok</td>
						<td rowspan="2" valign="middle" class="Libelle" style='border-right:1px solid #6fa3fd;'><?php if($LangueAffichage=="FR"){echo "Attest.";}else{echo "Certificate";}?><br>&nbsp;</td>
						<?php 
						if(DroitsFormationPlateforme(array_merge($TableauIdPostesAssistantFormation,array($IdPosteResponsableFormation))))
						{
							if($DateJour >= $RowSession['DATE_FIN'])
							{
						?>
							<td rowspan="2" class="Libelle" align="center">
								<?php if($LangueAffichage=="FR"){echo "Qualifications (résultats) - Lettre ";}else{echo "Qualifications (results) - Letter";}?>
							</td>
						<?php
							}
						}
						?>
					</tr>
					<tr>
					</tr>
					<tr height='1' bgcolor='#66AACC'><td colspan='15'></td></tr>
					<tr><td colspan='15' bgcolor="#DDDDDD"><b><?php if($LangueAffichage=="FR"){echo "Inscrits";}else{echo "Registered";}?></b></td></tr>
					<tr height='1' bgcolor='#66AACC'><td colspan='15'></td></tr>
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
						echo "<tr bgcolor='".$Couleur."'>
								<td>".$RowSessionPersonnes['STAGIAIRE_NOMPRENOM']."</td>\n
								<td>".AfficheCodePrestation($RowSessionPersonnes['PRESTATION']).$RowSessionPersonnes['POLE']."</td>\n
								<td>".$RowSessionPersonnes['INSCRIPTEUR_NOMPRENOM']."</td>\n
								<td>".AfficheDateJJ_MM_AAAA($RowSessionPersonnes['DATE_INSCRIPTION'])."</td>\n";
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
						echo "	</td>
								<td align='center'>";
						if($LangueAffichage=="FR")
						{
							if($RowSessionPersonnes['CONVOCATION_ENVOYEE']==1){echo "<img src='../../Images/tick.png' style='border:0;' title='Convocation envoyée'>";}
						}
						else
						{
							if($RowSessionPersonnes['CONVOCATION_ENVOYEE']==1){echo "<img src='../../Images/tick.png' style='border:0;' title='Convocation sent'>";}
						}
						echo "	</td>
								<td align='center'>";
						if($LangueAffichage=="FR")
						{
							if($RowSessionPersonnes['PRESENCE']==1){echo "<img src='../../Images/tick.png' style='border:0;' title='Présent'>";}
							elseif($RowSessionPersonnes['PRESENCE']==-1){echo "<img src='../../Images/Refuser.gif' style='border:0;'Absent'>";}
							elseif($RowSessionPersonnes['PRESENCE']==-2){echo substr($RowSessionPersonnes['SEMI_PRESENCE'],0,5);}
						}
						else
						{
							if($RowSessionPersonnes['PRESENCE']==1){echo "<img src='../../Images/tick.png' style='border:0;' title='Present'>";}
							elseif($RowSessionPersonnes['PRESENCE']==-1){echo "<img src='../../Images/Refuser.gif' style='border:0;' title='Absent'>";}
							elseif($RowSessionPersonnes['PRESENCE']==-2){echo substr($RowSessionPersonnes['SEMI_PRESENCE'],0,5);}
						}
						echo "	</td>";
						echo "<td align='center' style='border-right:1px solid #6fa3fd;'>";
						if($LangueAffichage=="FR")
						{
							if($RowSessionPersonnes['AttestationFormation']<>""){echo "<a class=\"Info\" target=\"_blank\" href=\"Docs/AttestationsFormations/".$RowSessionPersonnes['AttestationFormation']."\"><img src='../../Images/certificat.jpg' style='border:0;width:25px;' title='Attestation'></a>";}
							else
							{
								if($_SESSION['PartieFormation']>1)
								{
									if($RowSessionPersonnes['PRESENCE']==1)
									{
										echo "<a class=\"Info\" href=\"javascript:genererAttestation(".$RowSessionPersonnes['ID'].");\"><img src='../../Images/certificat.jpg' style='border:0;width:25px;' title='Attestation'></a>";
									}
								}
							}
						}
						else
						{
							if($RowSessionPersonnes['AttestationFormation']<>""){echo "<a class=\"Info\" target=\"_blank\" href=\"Docs/AttestationsFormations/".$RowSessionPersonnes['AttestationFormation']."\"><img src='../../Images/certificat.jpg' style='border:0;width:25px;' title='Certificate'>></a>";}
							else
							{
								if($_SESSION['PartieFormation']>1)
								{
									if($RowSessionPersonnes['PRESENCE']==1)
									{
										echo "<a class=\"Info\" href=\"javascript:genererAttestation(".$RowSessionPersonnes['ID'].");\"><img src='../../Images/certificat.jpg' style='border:0;width:25px;' title='Certificate'></a>";
									}
								}
							}
						}
						echo "</td>";
						if($DateJour >= $RowSession['DATE_FIN'])
						{
							echo "<td>";
							echo "<table>";
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
											echo "<tr>\n";
											echo "<td>".$RowSessionQualificationQCM['LIBELLE_QUALIFICATION']."</td>\n";
																							
											//Lecture puis affichage du résultat et de l'évaluation													
											$res = getRessource(getChainesSQL_resultatEvaluation($RowSessionPersonnes['ID_BESOIN'], $RowSessionQualificationQCM['ID_QUALIFICATION']));
											$row = mysqli_fetch_array($res);

											echo "<td>(".$row['Resultat_QCM'].")</td>\n";
											echo "<td> - ".$row['Evaluation']."</td>\n";
										}
										else{echo "<tr><td colspan='2'></td>\n";}
										echo "<td colspan='2'></td>\n";											
										echo "</tr>\n";
									}
								}
							}
							echo "</table>";
							echo "</td>\n";
						}								
						echo "</tr>\n";
					}
					$Liste_IDSessionPersonneQualification=substr($Liste_IDSessionPersonneQualification,0,strlen($Liste_IDSessionPersonneQualification)-1);
					echo "<input type='hidden' name='Liste_IDSessionPersonneQualification' value='".$Liste_IDSessionPersonneQualification."'>\n";
					$Liste_IDSessionPersonne=substr($Liste_IDSessionPersonne,0,strlen($Liste_IDSessionPersonne)-1);
					echo "<input type='hidden' name='Liste_IDSessionPersonne' value='".$Liste_IDSessionPersonne."'>\n";
					?>
					<tr>
						<td colspan=7></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
</body>
</html>
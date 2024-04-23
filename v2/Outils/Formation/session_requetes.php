<?php
/**
 * session_requetes
 * 
 * Ce fichier regroupe les requetes SQL qui concerne les sessions de formation.
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 * @package Formation\Session
 */

/**
 * getchaineSQL_NbInscritSession
 * 
 * requete qui calcule le nombre de personnes inscrites a une session de formation
 * 
 * @param int $session_Id L identifiant de session de formation
 * @return string La requete SQL generee
 * 
 * @author Remy Parran <rparran@aaa-aero.com>  
 */
function getchaineSQL_NbInscritSession($session_Id)
{
	$req = " SELECT
				COUNT(Id) AS NOMBRE
			FROM
				form_session_personne
			WHERE
				Suppr=0
				AND Validation_Inscription=1
				AND Id_Session=".$session_Id."; ";
	
	return $req;
}

/**
 * getchaineSQL_infosSession
 * 
 * Recupere les informations concernant une session de formation.
 * 
 * @param int $Id_Session Identifiant de session
 * @return string La chaine SQL
 * 
 * @author Remy Parran <rparran@aaa-aero.com> 
 * @author Anthony Schricke <aschricke@aaa-aero.com> 
 */
function getchaineSQL_infosSession($Id_Session)
{
	$req = " SELECT
				form_session_date.DateSession,
				form_session_date.Heure_Debut,
				form_session_date.Heure_Fin
			FROM
				form_session_date
			WHERE
				form_session_date.Id_Session=".$Id_Session."
				AND form_session_date.Suppr=0 
			ORDER BY DateSession ASC ";
	
	return $req;
}

/**
 * getchaineSQL_session
 * 
 * Recupere les informations de session de formation
 * 
 * @param int $Id_Session Identifiant de session de formation
 * @param \DateTime $dateDebut Date de debut de la formation
 * @param \DateTime $dateFin Date de fin de la formation
 * @param \DateTime $HeureDebut Heure de debut de session
 * @param \DateTime $HeureFin Heure d fin de session
 * 
 * @return string La chaine SQL
* @author Remy Parran <rparran@aaa-aero.com> 
* @author Anthony Schricke <aschricke@aaa-aero.com>  
 */
function getchaineSQL_session($Id_Session, $dateDebut, $dateFin, $HeureDebut, $HeureFin)
{
	$req = "SELECT
				form_session.Id_Formation AS ID_FORMATION,
				form_formation.Reference AS FORMATION_REFERENCE,
				form_formation.Id_TypeFormation AS ID_TYPEFORMATION,
				form_lieu.Libelle AS LIEU,
                form_lieu.Adresse AS LIEU_ADRESSE,
				new_rh_etatcivil.Id AS Id_Personne,
				CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS FORMATEUR_NOMPRENOM,
				'".$dateDebut."' AS DATE_DEBUT,
				'".$dateFin."' AS DATE_FIN,
				'".$HeureDebut."' AS HEURE_DEBUT,
				'".$HeureFin."' AS HEURE_FIN,
				form_session.Nb_Stagiaire_Mini AS NB_STAGIAIRE_MINI,
				form_session.Nb_Stagiaire_Maxi AS NB_STAGIAIRE_MAXI,
				form_session.Recyclage AS RECYCLAGE,
				form_session.Id_Plateforme AS ID_PLATEFORME,
				form_session.Id_GroupeSession AS ID_GROUPE_SESSION,
				form_session.Formation_Liee AS FORMATION_LIEE,
				form_session.Id_Lieu AS ID_LIEU,
				form_session.nom_fichier,
				form_session.Id_Formateur AS ID_FORMATEUR,
				form_session.MessageConvocation AS MessageConvocation
			FROM
				form_formation,
				form_session
				LEFT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id=form_session.Id_Formateur
				LEFT JOIN form_lieu ON form_lieu.Id=form_session.Id_Lieu
			WHERE
				form_formation.Id=form_session.Id_Formation
				AND form_session.Id = ".$Id_Session."; ";

	return $req;
}

/**
 * getchaineSQL_sessionPersonne
 * 
 * Recuperer les informations de session de formation avec les personnes 
 * 
 * @param int $Id_Session Identifiant de session de formation
 * @return string La requete SQL
 * 
 * @author Remy Parran <rparran@aaa-aero.com> 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function getchaineSQL_sessionPersonne($Id_Session)
{
	$req = "
        SELECT
			form_session_personne.Id AS ID,
			form_besoin.Id AS ID_BESOIN,
			form_session_personne.Id_Personne AS ID_PERSONNE,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS STAGIAIRE_NOMPRENOM,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Inscripteur) AS INSCRIPTEUR_NOMPRENOM,
			form_session_personne.Date_Inscription AS DATE_INSCRIPTION,
			form_session_personne.Validation_Inscription AS VALIDATION_INSCRIPTION,
			form_session_personne.Presence as PRESENCE,
			form_session_personne.Convocation_Envoyee AS CONVOCATION_ENVOYEE,
			form_session_personne.Cout AS COUT,
			new_competences_prestation.Libelle AS PRESTATION,
			(SELECT CONCAT(' - ',Libelle) FROM new_competences_pole WHERE new_competences_pole.Id=form_besoin.Id_Pole) AS POLE,
			form_besoin.Id_Prestation AS ID_PRESTATION,
			form_besoin.Id_Pole AS ID_POLE,
			form_session_personne.SemiPresence as SEMI_PRESENCE,
			form_session_personne.AttestationFormation,
			form_session_personne.Convocation,
			(SELECT EmailPro FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS EmailPro,
			new_competences_prestation.Code_Analytique,
			form_session_personne.DdePriseEnChargeEnvoyee,
			form_session_personne.AccordPriseEnCharge,
			form_session_personne.TraitementConvention,
			form_session_personne.MotifAbsence,
			form_session_personne.FeuillePresence,
			form_session_personne.EvaluationAChaud,
			(SELECT new_rh_etatcivil.Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS STAGIAIRE_NOM,
			(SELECT new_rh_etatcivil.Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS STAGIAIRE_PRENOM
		FROM
			form_session_personne,
			form_besoin,
			new_competences_prestation
		WHERE
			form_besoin.Id=form_session_personne.Id_Besoin
			AND new_competences_prestation.Id=form_besoin.Id_Prestation
			AND form_session_personne.Suppr=0
            AND form_session_personne.Validation_Inscription>-1
			AND form_session_personne.Id_Session=".$Id_Session."
		ORDER BY
			STAGIAIRE_NOMPRENOM ASC,
			form_session_personne.Validation_Inscription DESC,
			form_session_personne.Date_Inscription ASC;";
	return $req;
}

/**
 * getchaineSQL_desinscrireCandidat_MAJBesoin
 * 
 * construit la requete qui meta jour le besoin pour desinscrire un candidat
 * 
 * @param int $Id_besoin Identifiant du besoin
 * @return string La requete SQL
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function getchaineSQL_desinscrireCandidat_MAJBesoin($Id_besoin)
{
	$req = 'UPDATE form_besoin ';
	$req .= ' SET Traite = 0 ';
	$req .= 'WHERE ';
	$req .= '		Id = '.$Id_besoin.';';

	return $req;
}

/**
 * getchaineSQL_desinscrireCandidat_MAJSessionPersonne
 *
 * construit la requete qui met à jour la table session_personne pour desinscrire un candidat
 *
 * @param int $Id_Session_Personne Identifiant de Id_Session_Personne
 * @return string La requete SQL
 * @author Rémy PARRAN <rparran@aaa-aero.com>
 */
function getchaineSQL_desinscrireCandidat_MAJSessionPersonne($Id_Session_Personne)
{
	global $IdPersonneConnectee;
	
	$req="
		UPDATE
			form_session_personne
		SET
			Suppr=1,
			Id_Desinscripteur=".$IdPersonneConnectee.",
			Date_Desinscription='".date('Y-m-d')."' 
		WHERE Id=".$Id_Session_Personne;
	return $req;
}

/**
 * getchaineSQL_desinscrireCandidat_MAJSessionPersonneQualification
 * 
 * construit la requete qui met a jour la qualification pour desinscrire une personne
 * 
 * @param int $Id_session_personne Identifinat de la session de la personne
 * @return string La requete SQL
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function getchaineSQL_desinscrireCandidat_MAJSessionPersonneQualification($Id_session_personne)
{
	$req = 'UPDATE form_session_personne_qualification ';
	$req .= '		SET Suppr=1 ';
	$req .= 'WHERE ';
	$req .= '		Id_Session_Personne = '.$Id_session_personne.';';
	
	return $req;
}

/**
 * getchaineSQL_desinscrireCandidat_MAJSessionPersonneQualificationQCM
 * 
 * contruit la requete qui met a jour la qualification QCM pour desinscrire une personne
 * 
 * @param int $Id_session_personne Identifiant de la session de la personne
 * @return string La requete SQL
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function getchaineSQL_desinscrireCandidat_MAJSessionPersonneQualificationQCM($Id_session_personne)
{
	$req = 'UPDATE form_session_personne_qualification_question ';
	$req .= '		SET Suppr=1 ';
	$req .= 'WHERE ';
	$req .= '		Id_Session_Personne_Qualification IN (SELECT Id FROM form_session_personne_qualification WHERE Id_Session_Personne = '.$Id_session_personne.');';
	
	return $req;
}

/**
 * getchaineSQL_desinscrireCandidat_MAJCompetencesRelation
 *
 * contruit la requete qui met a jour la qualification dans la base de données des compétences si la lettre d'évaluation avait été modifiée en Bi
 *
 * @param int $Id_Besoin Identifiant du besoin
 * @return string La requete SQL
 * @author Rémy PARRAN <rparran@aaa-aero.com>
 */
function getchaineSQL_desinscrireCandidat_MAJCompetencesRelation($Id_Besoin)
{
	$req = "UPDATE
				new_competences_relation
			SET
				Evaluation='B',
				Date_Debut='0001-01-01',
				Date_Fin='0001-01-01',
				Resultat_QCM='',
				Date_QCM='0001-01-01'
			WHERE
				Id_Besoin=".$Id_Besoin."
				AND Evaluation='Bi'"; 
	return $req;
}

/**
 * getChaineSQL_getInfosDocument
 * 
 * Lit les information du document lie a une session
 * 
 * @param int $Id_Session Identifiant de session
 * @return string La requete SQL
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function getChaineSQL_getInfosDocument($Id_Session)
{
	return "SELECT chemin_fichier, nom_fichier FROM `form_session` WHERE Id = ".$Id_Session.";";	
}

/**
 * getChaineSQL_setInfosDocument
 * 
 * Mets a jour les informations du document lie a la session
 * 
 * @param int $Id_Session Identifiant de la sesison de formation
 * @param string $chemin  Le chemin d'acces du fichier
 * @param string $nomfichier Le nom du fichier
 * @return string La requete SQL
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function getChaineSQL_setInfosDocument($Id_Session, $chemin, $nomfichier)
{
	return "UPDATE `form_session` SET chemin_fichier = '".$chemin."', nom_fichier = '".$nomfichier."' WHERE Id = ".$Id_Session.";";
}

/**
 * getchaineSQL_getprestationsActives
 * 
 * Recupere les identifiants des prestations actives d'une personne 
 * 
 * @param int $Id_Personne Identifiant de la personne
 * @return string La requete SQL
 */
function getchaineSQL_getprestationsActives($Id_Personne)
{
	return "SELECT Id_Prestation, Id_Pole FROM `new_competences_personne_prestation` WHERE `Id_Personne` = ".$Id_Personne." AND Date_Fin >= NOW();";	
}

/**
 * getChaineSQL_getInfosDocumentLieu
 * 
 * Recupere les informations du document du lieu pour les joindre au mail de convocation par exemple
 * 
 * @param int $Id_Session Identifiant de la session
 * @return string La requete SQL
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function getChaineSQL_getInfosDocumentLieu($Id_Session)
{
	$req = "SELECT form_lieu.chemin_fichier, form_lieu.Fichier ";
	$req .= "FROM ";
	$req .= "		form_session, ";
	$req .= "		form_lieu ";
	$req .= "WHERE ";
	$req .= "		form_session.Id_lieu = form_lieu.Id ";
	$req .= "AND form_session.Id = ".$Id_Session.";";
	
	return $req;
}

/**
 * getChaineSQL_getMailsResponsables
 * 
 * Recupere les emails des responsables des personnes passee en parametres
 * 
 * @param array $personnes Le tableau des identifiants des personnes
 * @param array $Postes Le tableau des postes de responsables concernes
 * @return string La requete SQL
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function getChaineSQL_getMailsResponsables($personnes, $Postes)
{
	$strPostes = implode(",", $Postes);
	$strIdPersonnes = implode(",", $personnes);
	
	//Récupération de l'ensemble des responsables de chaque personne
	$reqResponsables="SELECT DISTINCT Id_Personne,
		(SELECT EmailPro FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS EmailPro
		FROM new_competences_personne_poste_prestation
		WHERE Id_Poste IN (".$strPostes.")
		AND CONCAT(Id_Prestation,'_',Id_Pole) IN
			(SELECT DISTINCT CONCAT(Id_Prestation,'_',Id_Pole)
			FROM new_competences_personne_prestation
			WHERE Date_Debut<='".date("Y-m-d")."'
			AND (Date_Fin>='".date("Y-m-d")."' OR Date_Fin<='0001-01-01')
			AND Id_Personne IN (".$strIdPersonnes.")
			)
		AND (SELECT EmailPro FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne)<>''
		";
	return $reqResponsables;
}
?>
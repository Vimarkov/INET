<?php
/**
 * Set_BesoinsDeSurveillance_Valider()
 *
 * Construit la chaine de caracteres SQL pour valider un besoin
 * 18/10/2017 - Modifications de la requete, suppression du code QCM. 
 * Les QCM ne sont pas développés pour la première version
 *
 * @author	Anthony Schricke <aschricke@aaa-aero.com>
 *
 *	@param int $Id_relation Numero d'identification pour le besoin
 *
 * @return string La requete SQL
 */
function Set_BesoinsDeSurveillance_Valider($Id_Relation, $Id_QCM=0, $Id_Langue=0, $Id_QCMLie=0, $Id_Langue_QCMLie=0)
{
    global $bdd;
    global $IdPersonneConnectee;
    global $DateJour;
    
	
	$ReqQualification="
		SELECT
			Duree_Validite
		FROM
			new_competences_relation
		LEFT JOIN
			new_competences_qualification
		ON 
			new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
		WHERE
			new_competences_relation.Id=".$Id_Relation;
	$ResultQualification=mysqli_query($bdd,$ReqQualification);
	$RowQualification=mysqli_fetch_array($ResultQualification);
	
    $ReqMAJRelation="
        UPDATE
            new_competences_relation
        SET
			Date_Surveillance='0001-01-01',
			QCM_Surveillance='',
			Date_Fin=IF(Statut_Surveillance='REFUSE',DATE_ADD(Date_Debut, INTERVAL ".$RowQualification['Duree_Validite']." MONTH),Date_Fin),
			Statut_Surveillance='VALIDE',
			IgnorerSurveillance=0, 
			Date_Ignore='0001-01-01', 
			Id_Ignore=0,
            Id_Modificateur=".$IdPersonneConnectee.",
            Date_Modification='".$DateJour."'
        WHERE
            Id=".$Id_Relation;
    $ResultMAJRelation=mysqli_query($bdd, $ReqMAJRelation);

	//Suppression des anciennes données si existantes dans form_session_personne_qualification avant d'en remettre
	if($Id_Relation>0){
		$req="UPDATE form_session_personne_qualification 
			SET Suppr=1		
			WHERE Id_Relation=".$Id_Relation;
		$resultUpdate=mysqli_query($bdd,$req);
	}
    if($Id_QCM>0)
    {
        $ReqInsertQCMQualif="
            INSERT INTO
                form_session_personne_qualification
            (
                Id_Session_Personne,
                Id_Qualification,
                Etat,
                Id_QCM,
                Id_LangueQCM,
                Id_QCM_Lie,
                Id_LangueQCMLie,
                Id_Createur,
                DateHeureCreation,
                Id_Relation,
                TypePassageQCM
            )
            VALUES
            (
                0,
                (SELECT Id_Qualification_Parrainage FROM new_competences_relation WHERE Id=".$Id_Relation."),
                0,
                ".$Id_QCM.",
                ".$Id_Langue.",
                ".$Id_QCMLie.",
                ".$Id_Langue_QCMLie.",
                '".$IdPersonneConnectee."',
                '".date("Y-m-d H:i:s")."',
                ".$Id_Relation.",
                2
            )";
        $ResultInsertQCMQualif=mysqli_query($bdd, $ReqInsertQCMQualif);
		$Id_SessionPersonneQualification = mysqli_insert_id($bdd);
		maj_QCM_SessionPersonneQualification($Id_SessionPersonneQualification,$Id_QCM,$Id_QCMLie,$Id_Langue,$Id_Langue_QCMLie);
    }
}

/**
 * getchaineSQL_BesoinsDeSurveillance_Refuser()
 *
 * Construit la chaine de caracteres SQL pour refuser un besoin
 *
 * @author	Anthony Schricke <aschricke@aaa-aero.com>
 *
 *	@param int $Id_relation Numero d'identification pour le besoin
 *
 * @return string La requete SQL
 */
function getchaineSQL_BesoinsDeSurveillance_Refuser($Id_relation,$dateQualification)
{
    global $IdPersonneConnectee;
    global $DateJour;
    
	return "
        UPDATE
            new_competences_relation
        SET
            Statut_Surveillance='REFUSE',
            Date_Fin='".$dateQualification."',
            Id_Modificateur=".$IdPersonneConnectee.",
            Date_Modification='".$DateJour."'
        WHERE
            Id=".$Id_relation;
}

/**
 * getchaineSQL_ListeTableauDeBord
 * 
 * construit la chaine SQL pour obtenir la liste du tableau de bord
 * 
 * @param int $Id_personne L identifiant de la personne connectee
 * @return string La chaine SQL
 */
function getchaineSQL_ListeTableauDeBord($Id_personne)
{
	$req="
        SELECT
            new_competences_qualification.Libelle AS Qualification,
            new_competences_relation.QCM_Surveillance AS QCM
        FROM
            new_competences_relation,
            new_competences_qualification
        WHERE
            new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
            AND new_competences_relation.Statut_Surveillance='VALIDE'
            AND Id_Personne=".$Id_personne;
	
	return $req;
}

/**
 * getChaineSQL_enregistrerNote
 * 
 * Construit la requete pour enregistrer la note et la date de la surveillance
 * 
 * @param int $Id_relation Identifiant de la relation
 * @param float $note						Note de la surveillance
 * @param \DateTime $datenote		Date de la note de la surveillance
 * 
 * @return string La requete SQL
 * @author Anthony Schricke <aschricke@aaa-aero.com> 
  */
function getChaineSQL_enregistrerNote($Id_relation, $note, $datenote)
{
    global $IdPersonneConnectee;
    global $DateJour;
    
	$req="
        UPDATE
            new_competences_relation
        SET
            Resultat_QCM='".$note."',
            Date_Surveillance='".$datenote."',
            Id_Modificateur=".$IdPersonneConnectee.",
            Date_Modification='".$DateJour."'
        WHERE
            Id=".$Id_relation;
	
	return $req;
}
?>
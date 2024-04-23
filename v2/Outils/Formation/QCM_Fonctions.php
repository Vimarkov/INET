<?php
/**
 * QCM_Fonctions.php
 * 
 * Ce fichier regroupe les fonctionnalites pour les QCM de l\'extranet.
 */

/**
 * Generer_QCM
 *
 * Cette fonction permet de generer un QCM
 *
 * @param 	int 	$Id_QCM_Langue	                     Identifiant du QCM pour une langue
 * @param 	int 	$Id_Session_Personne_Qualification 	 Identifiant de qualification pour une personne d\'une session (0 sinon)
 * @param 	int 	$ModeReponse 	 					1 = Mode reponse, 0 = Mode question uniquement
 * @return 	array                                        Tableau des questions et reponses (Num, Question, Coeff_Question, Img_Question, Nb_Reponses, Id_Reponse, Libelle_Reponse, Img_Reponse, ReponseVraiFaux, Resultat_Reponse, Note_Question)
 *
 * @author Rémy PARRAN <rparran@aaa-aero.com>
 */
function Generer_QCM($Id_QCM_Langue, $Id_Session_Personne_Qualification = 0,$ModeReponse = 0,$Assitant = 0)
{
	global $bdd;
	$Tableau_Questions_Reponses_Resultat=array();

	if($Id_Session_Personne_Qualification==0)
	{
	    //Liste des questions
	    $ReqQuestionsQCM="
            SELECT
                Id,
                Coefficient,
                Type,
                Libelle,
                Fichier,
                Id_QCM_Langue,
                Num
            FROM
                form_qcm_langue_question
            WHERE
                Suppr=0
                AND Id_QCM_Langue=".$Id_QCM_Langue."
            ORDER BY
                Num";
	    $ResultQuestionsQCM=mysqli_query($bdd,$ReqQuestionsQCM);
	    $NbResultQuestionsQCM=mysqli_num_rows($ResultQuestionsQCM);
	    
	    //Liste des réponses
	    $ReqReponsesQCM="
            SELECT
                form_qcm_langue_question_reponse.Id,
                form_qcm_langue_question_reponse.Libelle,
                form_qcm_langue_question_reponse.Valeur,
                form_qcm_langue_question_reponse.Fichier,
                form_qcm_langue_question_reponse.Id_QCM_Langue_Question
            FROM
                form_qcm_langue_question_reponse
            LEFT JOIN form_qcm_langue_question
                ON form_qcm_langue_question_reponse.Id_QCM_Langue_Question = form_qcm_langue_question.Id
            WHERE
                form_qcm_langue_question_reponse.Suppr=0
                AND form_qcm_langue_question.Id_QCM_Langue=".$Id_QCM_Langue."
			ORDER BY
				form_qcm_langue_question_reponse.Num
				";
	    $ResultReponsesQCM=mysqli_query($bdd,$ReqReponsesQCM);
	    $NbResultReponsesQCM=mysqli_num_rows($ResultReponsesQCM);
	    
	    if($NbResultQuestionsQCM>0 && $NbResultReponsesQCM>0)
    	{
    	    while($RowQuestionsQCM=mysqli_fetch_array($ResultQuestionsQCM))
    	    {
    	        //Comptage du nombre de réponses à la question
    	        mysqli_data_seek($ResultReponsesQCM,0);
    	        $Nb_Reponses=0;
    	        while($RowReponsesQCM=mysqli_fetch_array($ResultReponsesQCM))
    	        {
    	            if($RowReponsesQCM['Id_QCM_Langue_Question']==$RowQuestionsQCM['Id']){$Nb_Reponses++;}
    	        }
    	        
        	    //Parcours des réponses
    	        mysqli_data_seek($ResultReponsesQCM,0);
    	        while($RowReponsesQCM=mysqli_fetch_array($ResultReponsesQCM))
    	        {
    	            if($RowReponsesQCM['Id_QCM_Langue_Question']==$RowQuestionsQCM['Id'])
    	            {
    	                //Mise en tableau des éléments du QCM
    	                $Tableau_Questions_Reponses_Resultat[]=array
    	                   (
        	                    $RowQuestionsQCM['Num'],
    	                       stripslashes($RowQuestionsQCM['Libelle']),
        	                    $RowQuestionsQCM['Coefficient'],
        	                    $RowQuestionsQCM['Fichier'],
        	                    $Nb_Reponses,
    	                        $RowReponsesQCM['Id'],
    	                       stripslashes($RowReponsesQCM['Libelle']),
        	                    $RowReponsesQCM['Fichier'],
        	                    $RowReponsesQCM['Valeur'],
        	                    -1,
        	                    -1,
    	                       
    	                   );
    	            }
    	        }
    	    }
    	}
	}
	else
	{
		if($ModeReponse==1)
		{
			//Cas d'une QCM rattaché à une session de formation
			//Liste des résultats de la personne
			$ReqReponsesStagiaireQCM="
				SELECT
					form_session_personne_qualification_question.Id AS ID_SESSION_PERSONNE_QUALIFICATION_QUESTION,
					form_session_personne_qualification_question.Id_QCM AS ID_QCM,
					form_session_personne_qualification_question.Id_QCM_Langue_Question AS ID_QCM_LANGUE,
					form_session_personne_qualification_question.NoteQuestion AS NOTE_QUESTION_STAGIAIRE,
					form_session_personne_qualification_question_reponse.Valeur AS VALEUR_REPONSE_STAGIAIRE,
					form_qcm_langue_question_reponse.Id AS ID_REPONSE,
					form_qcm_langue_question_reponse.Libelle AS LIBELLE_REPONSE,
					form_qcm_langue_question_reponse.Valeur AS VALEUR_REPONSE,
					form_qcm_langue_question_reponse.Fichier AS FICHIER_REPONSE,
					form_qcm_langue_question.Coefficient AS COEFFICIENT_QUESTION,
					form_qcm_langue_question.Libelle AS LIBELLE_QUESTION,
					form_qcm_langue_question.Fichier AS FICHIER_QUESTION,
					form_qcm_langue_question.Num AS NUM_QUESTION,
					form_qcm_langue_question.Id AS ID_QUESTION,
					(SELECT COUNT(qcm_reponse.Id) 
					FROM form_qcm_langue_question_reponse AS qcm_reponse 
					WHERE qcm_reponse.Valeur=1 AND qcm_reponse.Suppr=0 AND qcm_reponse.Suppr=0 AND qcm_reponse.Id_QCM_Langue_Question=form_session_personne_qualification_question.Id_QCM_Langue_Question) AS NB_BONNES_REPONSES,
					(SELECT COUNT(qcm_bonneReponse.Id) 
						FROM form_session_personne_qualification_question_reponse AS qcm_bonneReponse
						WHERE qcm_bonneReponse.Suppr=0
						AND qcm_bonneReponse.Valeur=1
						AND qcm_bonneReponse.Id_Session_Personne_Qualification_Question=form_session_personne_qualification_question.Id
						AND qcm_bonneReponse.Id_QCM_Langue_Question_Reponse IN (SELECT qcm_reponse.Id 
						FROM form_qcm_langue_question_reponse AS qcm_reponse 
						WHERE qcm_reponse.Valeur=1 AND qcm_reponse.Id_QCM_Langue_Question=form_session_personne_qualification_question.Id_QCM_Langue_Question)
					) AS NB_REPONSES_CORRECT,
					(SELECT COUNT(qcm_bonneReponse.Id) 
						FROM form_session_personne_qualification_question_reponse AS qcm_bonneReponse
						WHERE qcm_bonneReponse.Suppr=0
						AND qcm_bonneReponse.Valeur=1
						AND qcm_bonneReponse.Id_Session_Personne_Qualification_Question=form_session_personne_qualification_question.Id
						AND qcm_bonneReponse.Id_QCM_Langue_Question_Reponse IN (SELECT qcm_reponse.Id 
						FROM form_qcm_langue_question_reponse AS qcm_reponse 
						WHERE qcm_reponse.Valeur=0 AND qcm_reponse.Suppr=0 AND qcm_reponse.Id_QCM_Langue_Question=form_session_personne_qualification_question.Id_QCM_Langue_Question)
					) AS NB_REPONSES_FAUSSES
				FROM
					form_session_personne_qualification_question_reponse
				LEFT JOIN form_session_personne_qualification_question
					ON form_session_personne_qualification_question_reponse.Id_Session_Personne_Qualification_Question = form_session_personne_qualification_question.Id
				LEFT JOIN form_qcm_langue_question_reponse
					ON form_session_personne_qualification_question_reponse.Id_QCM_Langue_Question_Reponse = form_qcm_langue_question_reponse.Id
				LEFT JOIN form_qcm_langue_question
					ON form_session_personne_qualification_question.Id_QCM_Langue_Question = form_qcm_langue_question.Id
				WHERE
					form_session_personne_qualification_question.Suppr=0
					AND form_session_personne_qualification_question_reponse.Suppr=0
					AND form_session_personne_qualification_question.Id_Session_Personne_Qualification=".$Id_Session_Personne_Qualification."
					AND form_qcm_langue_question.Id_QCM_Langue=".$Id_QCM_Langue." ";
			if($Assitant<>0){
				$ReqReponsesStagiaireQCM.=" ORDER BY form_qcm_langue_question.Num ASC, form_qcm_langue_question_reponse.Num ASC";
			}

			$ResultReponsesStagiaireQCM=mysqli_query($bdd,$ReqReponsesStagiaireQCM);
			$ResultReponsesStagiaireQCMPourCompterReponses=mysqli_query($bdd,$ReqReponsesStagiaireQCM);
			$NbResultReponsesStagiaireQCM=mysqli_num_rows($ResultReponsesStagiaireQCM);

			if($NbResultReponsesStagiaireQCM > 0)
			{
				//Parcours des réponses du stagiaire
				//Mise en tableau des variables
				while($RowReponsesStagiaireQCM=mysqli_fetch_array($ResultReponsesStagiaireQCM))
				{
					//Comptage du nombre de réponses à la question
				    mysqli_data_seek($ResultReponsesStagiaireQCMPourCompterReponses,0);
					$Nb_Reponses=0;
					while($RowReponsesStagiaireQCMPourCompterReponses=mysqli_fetch_array($ResultReponsesStagiaireQCMPourCompterReponses))
					{
						if($RowReponsesStagiaireQCMPourCompterReponses['ID_QUESTION']==$RowReponsesStagiaireQCM['ID_QUESTION']){$Nb_Reponses++;}
					}
					
					//Mise en tableau des éléments du QCM
					$Tableau_Questions_Reponses_Resultat[]=array
						(
						    $RowReponsesStagiaireQCM['NUM_QUESTION'],
						    stripslashes($RowReponsesStagiaireQCM['LIBELLE_QUESTION']),
						    $RowReponsesStagiaireQCM['COEFFICIENT_QUESTION'],
						    $RowReponsesStagiaireQCM['FICHIER_QUESTION'],
							$Nb_Reponses,
						    $RowReponsesStagiaireQCM['ID_REPONSE'],
						    stripslashes($RowReponsesStagiaireQCM['LIBELLE_REPONSE']),
						    $RowReponsesStagiaireQCM['FICHIER_REPONSE'],
						    $RowReponsesStagiaireQCM['VALEUR_REPONSE'],
							$RowReponsesStagiaireQCM['VALEUR_REPONSE_STAGIAIRE'],
							$RowReponsesStagiaireQCM['NOTE_QUESTION_STAGIAIRE'],
						    $RowReponsesStagiaireQCM['ID_SESSION_PERSONNE_QUALIFICATION_QUESTION'],
							$RowReponsesStagiaireQCM['NB_BONNES_REPONSES'],
							$RowReponsesStagiaireQCM['NB_REPONSES_CORRECT'],
							$RowReponsesStagiaireQCM['NB_REPONSES_FAUSSES']
						);
				}
			}
		}
		else
		{
			if($Assitant==0){
				$reqForm="form_session_personne_qualification_question.Id ASC";
			}
			else{
				$reqForm="form_qcm_langue_question.Num ASC, form_qcm_langue_question_reponse.Num ASC ";
			}
			
			//Cas d'une QCM rattaché à une session de formation
			$ReqQuestionStagiaireQCM="
				SELECT
					form_session_personne_qualification_question.Id AS ID_SESSION_PERSONNE_QUALIFICATION_QUESTION,
					form_session_personne_qualification_question.Id_QCM AS ID_QCM,
					form_session_personne_qualification_question.Id_QCM_Langue_Question AS ID_QCM_LANGUE,
					form_session_personne_qualification_question.NoteQuestion AS NOTE_QUESTION_STAGIAIRE,
                    form_qcm_langue_question_reponse.Id AS ID_REPONSE,
					form_qcm_langue_question_reponse.Libelle AS LIBELLE_REPONSE,
					form_qcm_langue_question_reponse.Valeur AS VALEUR_REPONSE,
					form_qcm_langue_question_reponse.Fichier AS FICHIER_REPONSE,
					form_qcm_langue_question.Coefficient AS COEFFICIENT_QUESTION,
					form_qcm_langue_question.Libelle AS LIBELLE_QUESTION,
					form_qcm_langue_question.Fichier AS FICHIER_QUESTION,
					form_qcm_langue_question.Num AS NUM_QUESTION,
					form_qcm_langue_question.Id AS ID_QUESTION
				FROM
					form_qcm_langue_question_reponse
				LEFT JOIN form_session_personne_qualification_question
					ON form_session_personne_qualification_question.Id_QCM_Langue_Question = form_qcm_langue_question_reponse.Id_QCM_Langue_Question
				LEFT JOIN form_qcm_langue_question
					ON form_qcm_langue_question_reponse.Id_QCM_Langue_Question = form_qcm_langue_question.Id
				WHERE
					form_session_personne_qualification_question.Suppr=0
					AND form_qcm_langue_question_reponse.Suppr=0
					AND form_qcm_langue_question.Suppr=0 
					AND form_session_personne_qualification_question.Id_Session_Personne_Qualification=".$Id_Session_Personne_Qualification."
					AND form_qcm_langue_question.Id_QCM_Langue=".$Id_QCM_Langue."
				ORDER BY
					".$reqForm."";

			$ResultQuestionStagiaireQCM=mysqli_query($bdd,$ReqQuestionStagiaireQCM);
			$ResultQuestionStagiaireQCMPourCompterReponses=mysqli_query($bdd,$ReqQuestionStagiaireQCM);
			$NbResultQuestionStagiaireQCM=mysqli_num_rows($ResultQuestionStagiaireQCM);
			
			if($NbResultQuestionStagiaireQCM > 0)
			{
				//Parcours des réponses du stagiaire
				//Mise en tableau des variables
				while($RowQuestionStagiaireQCM=mysqli_fetch_array($ResultQuestionStagiaireQCM))
				{
					//Comptage du nombre de réponses à la question
					mysqli_data_seek($ResultQuestionStagiaireQCMPourCompterReponses,0);
					$Nb_Reponses=0;
					while($RowQuestionStagiaireQCMPourCompterReponses=mysqli_fetch_array($ResultQuestionStagiaireQCMPourCompterReponses))
					{
					    if($RowQuestionStagiaireQCMPourCompterReponses['ID_QUESTION']==$RowQuestionStagiaireQCM['ID_QUESTION']){$Nb_Reponses++;}
					}
					
					//Mise en tableau des éléments du QCM
					$Tableau_Questions_Reponses_Resultat[]=array
						(
							$RowQuestionStagiaireQCM['NUM_QUESTION'],
						    stripslashes($RowQuestionStagiaireQCM['LIBELLE_QUESTION']),
							$RowQuestionStagiaireQCM['COEFFICIENT_QUESTION'],
							$RowQuestionStagiaireQCM['FICHIER_QUESTION'],
							$Nb_Reponses,
						    $RowQuestionStagiaireQCM['ID_REPONSE'],
						    stripslashes($RowQuestionStagiaireQCM['LIBELLE_REPONSE']),
							$RowQuestionStagiaireQCM['FICHIER_REPONSE'],
							$RowQuestionStagiaireQCM['VALEUR_REPONSE'],
							-1,
							-1,
						    $RowQuestionStagiaireQCM['ID_SESSION_PERSONNE_QUALIFICATION_QUESTION']
						);
				}
			}
		}
	}
	
	return $Tableau_Questions_Reponses_Resultat;
}

/**
 * get_nomCandidat
 *
 * Recupere le nom du candidat
 *
 * @param int $Id_Personne Identifiant de la personne
 * @return string Le nom du candidat
 * 
 * @author	Anthony Schricke <aschricke@aaa-aero.com>
 */
function get_nomCandidat($Id_Personne)
{
    $str_nomCandidat = "";
    
    $arr = mysqli_fetch_array(getRessource("SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id = ".$Id_Personne.";"));
    $str_nomCandidat = $arr[0]." ".$arr[1];
    
    return $str_nomCandidat;
}

/**
 * rechercher_candidats
 *
 * Recherche les identifiants des candidats aux QCM sans formation
 *
 * @return array La liste des identifiants
 * @author	Anthony Schricke <aschricke@aaa-aero.com>
 */
function rechercher_candidats($Id_Plateforme,$Traite,$Stagiaire)
{
	if($Traite==1){
		$req ="
			SELECT DISTINCT
				form_besoin.Id_Personne
			FROM
				form_session_personne_qualification,
				form_besoin,
				new_competences_prestation
			WHERE
				form_besoin.Id = form_session_personne_qualification.Id_Besoin
				AND form_besoin.Id_Prestation=new_competences_prestation.Id
				AND form_besoin.Suppr=0
				AND new_competences_prestation.Id_Plateforme=".$Id_Plateforme." 
				AND form_session_personne_qualification.Suppr=0
				AND form_session_personne_qualification.Resultat<>''
				AND form_session_personne_qualification.DateHeureFermeture>'0001-01-01'
				AND	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=form_besoin.Id_Personne) LIKE '%".$Stagiaire."%'		
				AND TypePassageQCM = 1
			ORDER BY (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=form_besoin.Id_Personne);";
	}
	else{
		$req ="
			SELECT DISTINCT
				form_besoin.Id_Personne
			FROM
				form_session_personne_qualification,
				form_besoin,
				new_competences_prestation
			WHERE
				form_besoin.Id = form_session_personne_qualification.Id_Besoin
				AND form_besoin.Id_Prestation=new_competences_prestation.Id
				AND form_besoin.Suppr=0
				AND new_competences_prestation.Id_Plateforme=".$Id_Plateforme." 
				AND form_session_personne_qualification.Suppr=0
				AND (form_session_personne_qualification.Resultat=''
				OR (form_session_personne_qualification.Resultat<>'' 
				AND form_session_personne_qualification.DateHeureFermeture<='0001-01-01')
				)
				AND	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=form_besoin.Id_Personne) LIKE '%".$Stagiaire."%'		
				AND TypePassageQCM = 1
			ORDER BY (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=form_besoin.Id_Personne);";
		
	}
    return getArrayFromRessource(getRessource($req));
}

/**
 * rechercher_qualifications
 *
 * Recherche les qualifications qu'un candidat doit passer
 * dans le cadre des QCM sans formation.
 *
 * @param int $Id_Personne Identifiant de la personne
 * @param int $Traite Pour savoir si on recherche sur les QCM passés ou en cours
 * @return array
 * @author	Anthony Schricke <aschricke@aaa-aero.com>
 */
function rechercher_qualifications($Id_Personne,$Traite)
{
    if($Traite==1){
		$req ="
        SELECT
            new_competences_qualification.Id,
            new_competences_qualification.Libelle,
            form_session_personne_qualification.Id_Besoin,
            form_session_personne_qualification.Id,
			form_session_personne_qualification.SessionRealise,
			form_session_personne_qualification.Lieu
        FROM
            form_session_personne_qualification,
            form_besoin,
            new_competences_qualification
        WHERE
            form_besoin.Id = form_session_personne_qualification.Id_Besoin
            AND new_competences_qualification.Id = form_session_personne_qualification.Id_Qualification
            AND form_besoin.Suppr=0
            AND form_session_personne_qualification.Resultat<>'' 
			AND form_session_personne_qualification.DateHeureFermeture>'0001-01-01'
            AND form_session_personne_qualification.Suppr=0
            AND form_besoin.Id_Personne = ".$Id_Personne." ";
		
	}
	else{
		$req ="
        SELECT
            new_competences_qualification.Id,
            new_competences_qualification.Libelle,
            form_session_personne_qualification.Id_Besoin,
            form_session_personne_qualification.Id,
			form_session_personne_qualification.SessionRealise,
			form_session_personne_qualification.Lieu
        FROM
            form_session_personne_qualification,
            form_besoin,
            new_competences_qualification
        WHERE
            form_besoin.Id = form_session_personne_qualification.Id_Besoin
            AND new_competences_qualification.Id = form_session_personne_qualification.Id_Qualification
            AND form_besoin.Suppr=0
            AND (
				form_session_personne_qualification.Resultat=''
				OR (form_session_personne_qualification.Resultat<>'' AND form_session_personne_qualification.DateHeureFermeture<='0001-01-01')
			)
            AND form_session_personne_qualification.Suppr=0
            AND form_besoin.Id_Personne = ".$Id_Personne." ";
	}
    return getArrayFromRessource(getRessource($req));
}

/**
 * rechercher_QCM
 *
 * Recherche les QCM pour une personne et une qualification
 *
 * @param int $Id_Personne Identifiant de la personne
 * @param int $Id_Qualification Identifiant de la qualification
 * @return array Liste des identifiants des QCM
 * 
 * @author	Anthony Schricke <aschricke@aaa-aero.com>
 */
function rechercher_QCM($Id_Personne, $Id_Qualification,$Id_Besoin)
{
    //requete pour QCM principaux Terminés
    $req = "
        SELECT
            form_qcm.Id,
            form_qcm.Code,
            'TERMINE' AS Status,
			DateHeureFermeture,
			DateHeureOuverture
        FROM
            form_session_personne_qualification,
            form_besoin,
            form_qcm
        WHERE
            form_besoin.Id = form_session_personne_qualification.Id_Besoin
            AND form_besoin.Id_Personne = ".$Id_Personne."
            AND form_session_personne_qualification.Id_Qualification = ".$Id_Qualification."
			AND form_session_personne_qualification.Id_Besoin = ".$Id_Besoin."
            AND form_session_personne_qualification.ResultatMere <> 0
			AND form_session_personne_qualification.Suppr=0
            AND form_qcm.Id = form_session_personne_qualification.Id_QCM ;";
    $arr = getArrayFromRessource(getRessource($req));
    
    //requete pour QCM principaux a passer
    $req = "
        SELECT
            form_qcm.Id, form_qcm.Code,
            '' AS Status,
			DateHeureFermeture,
			DateHeureOuverture
        FROM
            form_session_personne_qualification,
            form_besoin,
            form_qcm
        WHERE
            form_besoin.Id = form_session_personne_qualification.Id_Besoin
            AND form_besoin.Id_Personne = ".$Id_Personne."
            AND form_session_personne_qualification.Id_Qualification = ".$Id_Qualification."
            AND form_session_personne_qualification.ResultatMere = 0
			AND form_session_personne_qualification.Id_Besoin = ".$Id_Besoin."
			AND form_session_personne_qualification.Suppr=0
            AND form_qcm.Id = form_session_personne_qualification.Id_QCM ;";
    $arr_lie = getArrayFromRessource(getRessource($req));
    if(count($arr_lie) > 0)
        array_push($arr, $arr_lie[0]);
        
    //requete pour QCM annexes Terminés
    $req = "
        SELECT
            form_qcm.Id,
            form_qcm.Code,
            'TERMINE' AS Status,
			DateHeureFermeture,
			DateHeureOuverture
        FROM
            form_session_personne_qualification,
            form_besoin,
            form_qcm
        WHERE
            form_besoin.Id = form_session_personne_qualification.Id_Besoin
            AND form_besoin.Id_Personne = ".$Id_Personne."
            AND form_session_personne_qualification.Id_Qualification = ".$Id_Qualification."
            AND form_session_personne_qualification.Id_QCM_Lie > 0
            AND form_session_personne_qualification.Resultat <> 0
			AND form_session_personne_qualification.Id_Besoin = ".$Id_Besoin."
			AND form_session_personne_qualification.Suppr=0
            AND form_qcm.Id = form_session_personne_qualification.Id_QCM_Lie ;";
    $arr_lie = getArrayFromRessource(getRessource($req));
        
    if(count($arr_lie) > 0)
        array_push($arr, $arr_lie[0]);
        
        //requete pour QCM annexes a passer
    $req = "
        SELECT
            form_qcm.Id,
            form_qcm.Code,
            '' AS Status,
			DateHeureFermeture,
			DateHeureOuverture
        FROM
            form_session_personne_qualification,
            form_besoin,
            form_qcm
        WHERE
            form_besoin.Id = form_session_personne_qualification.Id_Besoin
            AND form_besoin.Id_Personne = ".$Id_Personne."
            AND form_session_personne_qualification.Id_Qualification = ".$Id_Qualification."
            AND form_session_personne_qualification.Id_QCM_Lie > 0
            AND form_session_personne_qualification.Resultat = 0
			AND form_session_personne_qualification.Suppr=0
			AND form_session_personne_qualification.Id_Besoin = ".$Id_Besoin."
            AND form_qcm.Id = form_session_personne_qualification.Id_QCM_Lie ;";
        $arr_lie = getArrayFromRessource(getRessource($req));
            
            if(count($arr_lie) > 0)
                array_push($arr, $arr_lie[0]);
                
    return $arr;
}

/**
 * ouvrirAccesQCM
 * 
 * Ouvre l\'acces au QCM
 * 
 * @param int $Id_session_personne_qualification identifiant de la table session_personne_qualification
 * 
 * @author	Anthony Schricke <aschricke@aaa-aero.com>
 */
function ouvrirAccesQCM($Id_session_personne_qualification)
{
    global $IdPersonneConnectee;
	
	$req = "
        UPDATE
            `form_session_personne_qualification`
        SET
            Id_Ouvreur = ".$IdPersonneConnectee.",
            DateHeureOuverture = NOW(), DateHeureFermeture=0
        WHERE
            Id = ".$Id_session_personne_qualification.";";
    getRessource($req);
}

/**
 * fermerAccesQCM
 * 
 * Fermer l\'acces au QCM
 * @param int $Id_session_personne_qualification identifiant de la table session_personne_qualification
 * 
 * @author	Anthony Schricke <aschricke@aaa-aero.com>
 */
function fermerAccesQCM($Id_session_personne_qualification)
{
    $req = "
        UPDATE
            `form_session_personne_qualification`
        SET
            DateHeureFermeture = NOW()
        WHERE
            Id = ".$Id_session_personne_qualification.";";
    getRessource($req);
}

/**
 * ouvrirAttesteQCM
 * 
 * Attestation de QCM = 1
 * 
 * @param int $Id_session_personne_qualification identifiant de la table session_personne_qualification
 */
function ouvrirAttesteQCM($Id_session_personne_qualification,$Lieu)
{
    global $IdPersonneConnectee;
	
	$req = "
        UPDATE
            form_session_personne_qualification
        SET
            SessionRealise = 1,
			Lieu='".addslashes($Lieu)."'
        WHERE
            Id = ".$Id_session_personne_qualification.";";
    getRessource($req);
}

/**
 * fermerAttesteQCM
 * 
 * Attestation de QCM = 0
 * @param int $Id_session_personne_qualification identifiant de la table session_personne_qualification
 * 
 */
function fermerAttesteQCM($Id_session_personne_qualification,$Lieu)
{
    $req = "
        UPDATE
            form_session_personne_qualification
        SET
            SessionRealise = 0,
			Lieu='".addslashes($Lieu)."'
        WHERE
            Id = ".$Id_session_personne_qualification.";";
    getRessource($req);
}

/**
 * rechercher_IdSessionPersonneQualification
 * 
 * Recherche les Id session Personne Qualification en fonction du candidat
 * 
 * @param int $Id_Personne Identifiant de la personne
 * @return mixed Tableau
 * 
 * @author	Anthony Schricke <aschricke@aaa-aero.com>
 */
function rechercher_IdSessionPersonneQualification($Id_Personne,$Traite)
{
	 if($Traite==1){
		 $req ="
        SELECT DISTINCT
            form_session_personne_qualification.Id
        FROM
            form_session_personne_qualification,
            form_besoin
        WHERE
            form_besoin.Id = form_session_personne_qualification.Id_Besoin
			AND form_besoin.Suppr=0
            AND form_session_personne_qualification.Resultat<>'' 
			AND form_session_personne_qualification.DateHeureFermeture>'0001-01-01'
            AND form_session_personne_qualification.Suppr=0
            AND form_besoin.Id_Personne = ".$Id_Personne.";";
	}
	else{
		$req ="
        SELECT DISTINCT
            form_session_personne_qualification.Id
        FROM
            form_session_personne_qualification,
            form_besoin
        WHERE
            form_besoin.Id = form_session_personne_qualification.Id_Besoin
			AND form_besoin.Suppr=0
            AND (
				form_session_personne_qualification.Resultat=''
				OR (form_session_personne_qualification.Resultat<>'' AND form_session_personne_qualification.DateHeureFermeture<='0001-01-01')
			)
            AND form_session_personne_qualification.Suppr=0
            AND form_besoin.Id_Personne = ".$Id_Personne.";";
	}
	
    
    return getArrayFromRessource(getRessource($req));
}

function rechercher_IdSessionPersonneDocument($Id_Personne,$Traite)
{
	 if($Traite==1){
		 $req ="
        SELECT DISTINCT
            form_session_personne_document.Id
        FROM
			form_session_personne_document,
            form_session_personne_qualification,
            form_besoin
        WHERE
			form_session_personne_document.Id_SessionPersonneQualification=form_session_personne_qualification.Id
            AND form_besoin.Id = form_session_personne_qualification.Id_Besoin
			AND form_besoin.Suppr=0
			AND form_session_personne_document.Suppr=0
			AND form_session_personne_qualification.Suppr=0
            AND form_session_personne_qualification.Resultat<>'' 
			AND form_session_personne_qualification.DateHeureFermeture>'0001-01-01'
            AND form_session_personne_qualification.Suppr=0
            AND form_besoin.Id_Personne = ".$Id_Personne.";";
	}
	else{
		$req ="
        SELECT DISTINCT
            form_session_personne_document.Id
        FROM
			form_session_personne_document,
            form_session_personne_qualification,
            form_besoin
        WHERE
			form_session_personne_document.Id_SessionPersonneQualification=form_session_personne_qualification.Id
            AND form_besoin.Id = form_session_personne_qualification.Id_Besoin
			AND form_besoin.Suppr=0
			AND form_session_personne_document.Suppr=0
			AND form_session_personne_qualification.Suppr=0
            AND (
				form_session_personne_qualification.Resultat=''
				OR (form_session_personne_qualification.Resultat<>'' AND form_session_personne_qualification.DateHeureFermeture<='0001-01-01')
			)
            AND form_session_personne_qualification.Suppr=0
            AND form_besoin.Id_Personne = ".$Id_Personne.";";
	}
	
    
    return getArrayFromRessource(getRessource($req));
}

/**
 * QCMestOuvert
 * 
 * Verifie si un QCM est ouvert.
 * 
 * @param int $Id_session_personne_qualification Identifiant de la personne
 * @return boolean
 * 
 * @author	Anthony Schricke <aschricke@aaa-aero.com>
 */
function QCMestOuvert($Id_session_personne_qualification)
{
    $req = "
        SELECT
            Id
        FROM
            form_session_personne_qualification
        WHERE
            DateHeureFermeture = 0
            AND DateHeureOuverture > 0
            AND DateHeureOuverture <= '".date("Y-m-d H:i:s")."'
            AND Id = ".$Id_session_personne_qualification.";";

    if(mysqli_num_rows(getRessource($req)) > 0)
        return true;
    else
        return false;
}

/**
 * AttestationSession
 * 
 * Verifie si un QCM a une attestation de session.
 * 
 * @param int $Id_session_personne_qualification Identifiant de la personne
 * @return boolean
 * 
 */
function QCMestAtteste($Id_session_personne_qualification)
{
    $req = "
        SELECT
            Id
        FROM
            form_session_personne_qualification
        WHERE
            SessionRealise = 1
            AND Id = ".$Id_session_personne_qualification.";";

    if(mysqli_num_rows(getRessource($req)) > 0)
        return true;
    else
        return false;
}

/**
 * ouvrirAccesDocument
 * 
 * Ouvre l\'acces au document
 * 
 * @param int $Id_session_personne_document identifiant de la table session_personne_document
 * 
 * @author	Pauline FAUGE <pfauge@aaa-aero.com>
 */
function ouvrirAccesDocument($Id_session_personne_document)
{
    global $IdPersonneConnectee;
	
	$req = "
        UPDATE
            `form_session_personne_document`
        SET
            Id_Ouvreur = ".$IdPersonneConnectee.",
            DateHeureOuverture = NOW(), DateHeureFermeture=0
        WHERE
            Id = ".$Id_session_personne_document.";";
    getRessource($req);
}

/**
 * fermerAccesDocument
 * 
 * Fermer l\'acces au document
 * @param int $Id_session_personne_document identifiant de la table session_personne_document
 * 
 * @author	Pauline FAUGE <pfauge@aaa-aero.com>
 */
function fermerAccesDocument($Id_session_personne_document)
{
    $req = "
        UPDATE
            `form_session_personne_document`
        SET
            DateHeureFermeture = NOW()
        WHERE
            Id = ".$Id_session_personne_document." ;";
    getRessource($req);
}

/**
 * Generer_Document
 *
 * Cette fonction permet de generer un document complementaire
 *
 * @param 	int 	$Id_Document_Langue	             	Identifiant du document pour une langue
 * @param 	int 	$Id_Session_Personne_Document 	 	Identifiant de document pour une personne d\'une session (0 sinon)
 * @return 	array                                       Tableau des questions et reponses (Id_Question,Question, Img_Question, Type_Reponse, Valeur_Reponse,Texte_Reponse)
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function Generer_Document($Id_Document_Langue, $Id_Session_Personne_Document = 0)
{
	global $bdd;
	$Tableau_Questions_Reponses_Resultat=array();
	
	if($Id_Session_Personne_Document==0)
	{
	    //Liste des questions
	    $ReqQuestionsDoc="
            SELECT
                Id,
                TypeReponse,
                Libelle,
                Fichier,
                Id_Document_Langue
            FROM
                form_document_langue_question
            WHERE
                Suppr=0
                AND Id_Document_Langue=".$Id_Document_Langue."
            ORDER BY
                Id";
	    $ResultQuestionsDoc=mysqli_query($bdd,$ReqQuestionsDoc);
	    $NbResultQuestionsDoc=mysqli_num_rows($ResultQuestionsDoc);
	    
	    if($NbResultQuestionsDoc>0)
    	{
			//Parcours des questions
    	    while($RowQuestionsDoc=mysqli_fetch_array($ResultQuestionsDoc))
    	    {   	        
				//Mise en tableau des éléments du document
				$Tableau_Questions_Reponses_Resultat[]=array
				   (
						$RowQuestionsDoc['Id'],
						stripslashes($RowQuestionsDoc['Libelle']),
						$RowQuestionsDoc['Fichier'],
						$RowQuestionsDoc['TypeReponse'],
						-1,
						-1
				   );
    	    }
    	}
	}
	else
	{
		//Cas d'une document rattaché à une session de formation
		//Liste des résultats de la personne
		$ReqReponsesStagiaireDoc="
			SELECT
				form_session_personne_document_question_reponse.Id,
				form_session_personne_document.Id_Document AS ID_DOCUMENT,
				form_session_personne_document_question_reponse.Id_Document_Langue_Question AS ID_DOCUMENT_LANGUE_QUESTION,
				form_session_personne_document_question_reponse.Valeur_Reponse AS VALEUR_REPONSE_STAGIAIRE,
				form_session_personne_document_question_reponse.Texte_Reponse AS TEXTE_REPONSE_STAGIAIRE,
				form_document_langue_question.Libelle AS LIBELLE_QUESTION,
				form_document_langue_question.Fichier AS FICHIER_QUESTION,
				form_document_langue_question.Id AS Id_DocumentLangueQuestion,
				form_document_langue_question.TypeReponse AS TYPE_REPONSE
			FROM
				form_session_personne_document_question_reponse
			LEFT JOIN form_session_personne_document
				ON form_session_personne_document_question_reponse.Id_Session_Personne_Document = form_session_personne_document.Id
			LEFT JOIN form_document_langue_question
				ON form_session_personne_document_question_reponse.Id_Document_Langue_Question = form_document_langue_question.Id
			WHERE
				form_session_personne_document.Suppr=0
				AND form_session_personne_document_question_reponse.Suppr=0
				AND form_session_personne_document_question_reponse.Id_Session_Personne_Document=".$Id_Session_Personne_Document."
				AND form_document_langue_question.Id_Document_Langue=".$Id_Document_Langue." 
			ORDER BY form_document_langue_question.Id ";
		$ResultReponsesStagiaireDoc=mysqli_query($bdd,$ReqReponsesStagiaireDoc);
		$NbResultReponsesStagiaireDoc=mysqli_num_rows($ResultReponsesStagiaireDoc);
		
		if($NbResultReponsesStagiaireDoc > 0)
		{
			//Parcours des réponses du stagiaire
			//Mise en tableau des variables
			while($RowReponsesStagiaireDoc=mysqli_fetch_array($ResultReponsesStagiaireDoc))
			{
				//Mise en tableau des éléments du document
				$Tableau_Questions_Reponses_Resultat[]=array
					(
						$RowReponsesStagiaireDoc['Id'],
						stripslashes($RowReponsesStagiaireDoc['LIBELLE_QUESTION']),
						$RowReponsesStagiaireDoc['FICHIER_QUESTION'],
						$RowReponsesStagiaireDoc['TYPE_REPONSE'],
						$RowReponsesStagiaireDoc['VALEUR_REPONSE_STAGIAIRE'],
						$RowReponsesStagiaireDoc['TEXTE_REPONSE_STAGIAIRE']
					);
			}
		}
	}
	
	return $Tableau_Questions_Reponses_Resultat;
}

/**
 * DocestOuvert
 * 
 * Verifie si un document est ouvert.
 * 
 * @param int $Id_session_personne_document Identifiant de la personne
 * @return boolean
 * 
 * @author	Pauline FAUGE <pfauge@aaa-aero.com>
 */
function DocestOuvert($Id_session_personne_document)
{
    $req = "
        SELECT
            Id
        FROM
            form_session_personne_document
        WHERE
            DateHeureFermeture <= '0001-01-01 00:00:00'
            AND DateHeureOuverture > '0001-01-01 00:00:00'
            AND DateHeureOuverture <= '".date("Y-m-d H:i:s")."'
            AND Id = ".$Id_session_personne_document." ;";
    
    if(mysqli_num_rows(getRessource($req)) > 0)
        return true;
    else
        return false;
}

/**
 * rechercherResultats
 * 
 * Recherche les résultats d'un QCM
 * 
 * @param int $Id_session_Personne_Qualification Identifiant unique
 * @return array La liste des resultats
 * 
 * @author	Anthony Schricke <aschricke@aaa-aero.com>
 */
function rechercherResultats($Id_session_personne_qualification)
{
    $req = "
        SELECT 
            Resultat, 
            ResultatMere,
			Etat
        FROM
            form_session_personne_qualification
        WHERE
            Id = ".$Id_session_personne_qualification." ;";
    
    return getArrayFromRessource(getRessource($req));
}

function rechercherEvaluation($Id_session_personne_qualification)
{
    $req = "
        SELECT 
			Id,
			Id_LangueDocument,
			(SELECT Libelle FROM form_langue WHERE Id=Id_LangueDocument) AS Langue,
            DateHeureRepondeur, 
            DateHeureOuverture,
			DateHeureFermeture,
			(SELECT Fichier_PHP FROM form_document WHERE form_document.Id=form_session_personne_document.Id_Document) AS Fichier_PHP
        FROM
            form_session_personne_document
        WHERE
            Id_SessionPersonneQualification = ".$Id_session_personne_qualification." 
			AND Suppr=0;";
    return getArrayFromRessource(getRessource($req));
}

/**
 * getNombreQCMOuverts
 * 
 * Recupere le nombre de QCM ouverts
 * 
 * @param int $Id_personne Identifiant de la personne
 * @return int Le nombre de QCM ouverts
 * 
 * @author	Anthony Schricke <aschricke@aaa-aero.com>
 */
function getNombreQCMOuverts($id_personne,$Traite)
{
	if($Traite==1){
		$req ="
        SELECT
            COUNT(form_session_personne_qualification.Id) AS NB 
        FROM
            form_session_personne_qualification,
            form_besoin,
            new_competences_qualification
        WHERE
            form_besoin.Id = form_session_personne_qualification.Id_Besoin
            AND new_competences_qualification.Id = form_session_personne_qualification.Id_Qualification
            AND form_besoin.Id_Personne = ".$id_personne."
			AND form_besoin.Suppr=0
			AND form_session_personne_qualification.Suppr=0
			AND form_session_personne_qualification.Resultat<>''
			AND form_session_personne_qualification.DateHeureFermeture>'0001-01-01'
        	AND form_session_personne_qualification.DateHeureOuverture>0
        	AND form_session_personne_qualification.DateHeureFermeture=0 ;";
	}
	else{
		$req ="
        SELECT
            COUNT(form_session_personne_qualification.Id) AS NB 
        FROM
            form_session_personne_qualification,
            form_besoin,
            new_competences_qualification
        WHERE
            form_besoin.Id = form_session_personne_qualification.Id_Besoin
            AND new_competences_qualification.Id = form_session_personne_qualification.Id_Qualification
            AND form_besoin.Id_Personne = ".$id_personne."
			AND form_besoin.Suppr=0
			AND form_session_personne_qualification.Suppr=0
			AND (form_session_personne_qualification.Resultat=''
				OR (form_session_personne_qualification.Resultat<>'' 
				AND form_session_personne_qualification.DateHeureFermeture<='0001-01-01')
				)
        	AND form_session_personne_qualification.DateHeureFermeture=0 ;";
	}
    $arr = getArrayFromRessource(getRessource($req));
    
    return $arr[0][0];
}

function getNombreEvalOuverts($id_personne,$Traite)
{
	if($Traite==1){
		$req ="
        SELECT
            COUNT(form_session_personne_document.Id) AS NB 
        FROM
			form_session_personne_document,
            form_session_personne_qualification,
            form_besoin,
            new_competences_qualification
        WHERE
			form_session_personne_document.Id_SessionPersonneQualification=form_session_personne_qualification.Id
            AND form_besoin.Id = form_session_personne_qualification.Id_Besoin
            AND new_competences_qualification.Id = form_session_personne_qualification.Id_Qualification
            AND form_besoin.Id_Personne = ".$id_personne."
			AND form_besoin.Suppr=0
			AND form_session_personne_document.Suppr=0
			AND form_session_personne_qualification.Suppr=0
			AND form_session_personne_qualification.Suppr=0
			AND form_session_personne_qualification.Resultat<>''
			AND form_session_personne_qualification.DateHeureFermeture>'0001-01-01'
        	AND form_session_personne_qualification.DateHeureOuverture>0
			AND form_session_personne_document.DateHeureOuverture>0;";
	}
	else{
		$req ="
        SELECT
            COUNT(form_session_personne_qualification.Id) AS NB 
        FROM
			form_session_personne_document,
            form_session_personne_qualification,
            form_besoin,
            new_competences_qualification
        WHERE
            form_session_personne_document.Id_SessionPersonneQualification=form_session_personne_qualification.Id
            AND form_besoin.Id = form_session_personne_qualification.Id_Besoin
            AND new_competences_qualification.Id = form_session_personne_qualification.Id_Qualification
            AND form_besoin.Id_Personne = ".$id_personne."
			AND form_besoin.Suppr=0
			AND form_session_personne_document.Suppr=0
			AND form_session_personne_qualification.Suppr=0
			AND form_session_personne_qualification.Suppr=0
			AND (form_session_personne_qualification.Resultat=''
				OR (form_session_personne_qualification.Resultat<>'' 
				AND form_session_personne_qualification.DateHeureFermeture<='0001-01-01')
				)
        	AND form_session_personne_qualification.DateHeureFermeture=0 
			AND form_session_personne_document.DateHeureOuverture>0 ;";
	}
    $arr = getArrayFromRessource(getRessource($req));
    
    return $arr[0][0];
}

/**
 * getNombreQCM
 * 
 * Recupere le nombre de QCM ouverts
 * 
 * @param int $Id_personne Identifiant de la personne
 * @return int Le nombre de QCM ouverts
 * 
 * @author	Anthony Schricke <aschricke@aaa-aero.com>
 */
function getNombreQCM($id_personne,$Traite)
{
	if($Traite==1){
		$req ="
        SELECT
            COUNT(form_session_personne_qualification.Id) AS NB 
        FROM
            form_session_personne_qualification,
            form_besoin,
            new_competences_qualification
        WHERE
            form_besoin.Id = form_session_personne_qualification.Id_Besoin
            AND new_competences_qualification.Id = form_session_personne_qualification.Id_Qualification
            AND form_besoin.Id_Personne = ".$id_personne."
			AND form_besoin.Suppr=0
			AND form_session_personne_qualification.Suppr=0
			AND form_session_personne_qualification.Resultat<>''
			AND form_session_personne_qualification.DateHeureFermeture>'0001-01-01'
        	AND form_session_personne_qualification.DateHeureOuverture>0;";
	}
	else{
		$req ="
        SELECT
            COUNT(form_session_personne_qualification.Id) AS NB 
        FROM
            form_session_personne_qualification,
            form_besoin,
            new_competences_qualification
        WHERE
            form_besoin.Id = form_session_personne_qualification.Id_Besoin
            AND new_competences_qualification.Id = form_session_personne_qualification.Id_Qualification
            AND form_besoin.Id_Personne = ".$id_personne."
			AND form_besoin.Suppr=0
			AND form_session_personne_qualification.Suppr=0
			AND (form_session_personne_qualification.Resultat=''
				OR (form_session_personne_qualification.Resultat<>'' 
				AND form_session_personne_qualification.DateHeureFermeture<='0001-01-01')
				);";
	}
    $arr = getArrayFromRessource(getRessource($req));
    
    return $arr[0][0];
}

function getNombreEval($id_personne,$Traite)
{
	if($Traite==1){
		$req ="
        SELECT
            COUNT(form_session_personne_document.Id) AS NB 
        FROM
			form_session_personne_document,
            form_session_personne_qualification,
            form_besoin,
            new_competences_qualification
        WHERE
			form_session_personne_document.Id_SessionPersonneQualification=form_session_personne_qualification.Id
            AND form_besoin.Id = form_session_personne_qualification.Id_Besoin
            AND new_competences_qualification.Id = form_session_personne_qualification.Id_Qualification
            AND form_besoin.Id_Personne = ".$id_personne."
			AND form_besoin.Suppr=0
			AND form_session_personne_document.Suppr=0
			AND form_session_personne_qualification.Suppr=0
			AND form_session_personne_qualification.Suppr=0
			AND form_session_personne_qualification.Resultat<>''
			AND form_session_personne_qualification.DateHeureFermeture>'0001-01-01'
        	AND form_session_personne_qualification.DateHeureOuverture>0;";
	}
	else{
		$req ="
        SELECT
            COUNT(form_session_personne_document.Id) AS NB 
        FROM
			form_session_personne_document,
            form_session_personne_qualification,
            form_besoin,
            new_competences_qualification
        WHERE
            form_session_personne_document.Id_SessionPersonneQualification=form_session_personne_qualification.Id
            AND form_besoin.Id = form_session_personne_qualification.Id_Besoin
            AND new_competences_qualification.Id = form_session_personne_qualification.Id_Qualification
            AND form_besoin.Id_Personne = ".$id_personne."
			AND form_besoin.Suppr=0
			AND form_session_personne_document.Suppr=0
			AND form_session_personne_qualification.Suppr=0
			AND form_session_personne_qualification.Suppr=0
			AND (form_session_personne_qualification.Resultat=''
				OR (form_session_personne_qualification.Resultat<>'' 
				AND form_session_personne_qualification.DateHeureFermeture<='0001-01-01')
				);";
	}
    $arr = getArrayFromRessource(getRessource($req));
    
    return $arr[0][0];
}

/**
 * calculResultatQCMs
 * 
 * Calculer le resultat au QCM mere et total
 * 
 * @param int $Id_Session_Personne_Qualification Identifiant de la session personne qualification

 * @author	Pauline FAUGE <pfauge@aaa-aero.com>
 */
function calculResultatQCMs($Id_Session_Personne_Qualification)
{
	global $bdd;
	global $TableauIdPostesCQ;
	global $LangueAffichage;
	global $IdPosteReferentQualiteProduit;
	
    $ReqFormSessionPersonneQualification="
        SELECT
			form_session_personne_qualification.Id_QCM,
			form_session_personne_qualification.Id_LangueQCM,
			form_session_personne_qualification.Id_QCM_Lie,
			form_session_personne_qualification.Id_LangueQCMLie
        FROM
            form_session_personne_qualification
        WHERE
            form_session_personne_qualification.Id=".$Id_Session_Personne_Qualification;
    $ResultFormSessionPersonneQualification=mysqli_query($bdd,$ReqFormSessionPersonneQualification);
    $RowFormSessionPersonneQualification=mysqli_fetch_array($ResultFormSessionPersonneQualification);
	
	$tabQCM=array();
	$tabQCM[]=$RowFormSessionPersonneQualification['Id_QCM']."_".$RowFormSessionPersonneQualification['Id_LangueQCM'];
	if($RowFormSessionPersonneQualification['Id_QCM_Lie']>0)
	{
		$tabQCM[]=$RowFormSessionPersonneQualification['Id_QCM_Lie']."_".$RowFormSessionPersonneQualification['Id_LangueQCMLie'];
	}
	
	$sommeCoeff=0;
	$sommeNote=0;
	$nb=0;
	foreach($tabQCM AS $QCM)
	{
		$nb++;
		$sommeCoeffQCM=0;
		$sommeNoteQCM=0;
		
		$tabLeQCM = explode("_",$QCM);
		$ReqQCM_Langue="
			SELECT
				Id,
				Id_QCM,
				Id_Langue,
				Libelle,
				Date_MAJ,
				Id_Personne_MAJ,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne_MAJ) AS Personne
			FROM
				form_qcm_langue
			WHERE
				Suppr=0
				AND Id_Langue=".$tabLeQCM[1]." 
				AND Id_QCM=".$tabLeQCM[0];
		$ResultQCM_Langue=mysqli_query($bdd,$ReqQCM_Langue);
		$RowQCM_Langue=mysqli_fetch_array($ResultQCM_Langue);
		
		$ReqQCM="
			SELECT
				Id,
				Code,
				(SELECT Libelle FROM form_client WHERE form_client.Id=form_qcm.Id_Client) AS Client,
				Nb_Question,
				Id_QCM_Lie
			FROM
				form_qcm
			WHERE
				Id=".$RowQCM_Langue['Id_QCM'];
		$ResultQCM=mysqli_query($bdd,$ReqQCM);
		$RowQCM=mysqli_fetch_array($ResultQCM);

		$QCM_Q_R_RStagiaires=Generer_QCM($RowQCM_Langue['Id'], $Id_Session_Personne_Qualification, 1);
		
		$QuestionPrecedente="";
		$nbCoeff=0;
		$num=1;
		foreach($QCM_Q_R_RStagiaires as $Ligne_Q_R_RStagiaires)
		{
			//Réponses
			//--------
			$Nb_BonnesReponses=$Ligne_Q_R_RStagiaires[12];
			$Nb_Reponses=$Ligne_Q_R_RStagiaires[4];
			$Nb_ReponsesCorrects=$Ligne_Q_R_RStagiaires[13];
			$Nb_ReponsesFausses=$Ligne_Q_R_RStagiaires[14];
				
			if($Ligne_Q_R_RStagiaires[8]==$Ligne_Q_R_RStagiaires[9] && $Ligne_Q_R_RStagiaires[9]==1){$NoteReponse=round(1/$Nb_BonnesReponses,2);}
			else{$NoteReponse=0;}
			if($Nb_ReponsesFausses>0){$NoteReponse=0;}
			//-------

			if($Ligne_Q_R_RStagiaires[1] <> $QuestionPrecedente)
			{
				$QuestionPrecedente=$Ligne_Q_R_RStagiaires[1];
				$nbCoeff+=$Ligne_Q_R_RStagiaires[2];
				$sommeCoeff+=$Ligne_Q_R_RStagiaires[2];
				$sommeCoeffQCM+=$Ligne_Q_R_RStagiaires[2];

				$Nb_BonnesReponses=$Ligne_Q_R_RStagiaires[12];
				$Nb_Reponses=$Ligne_Q_R_RStagiaires[4];
				$Nb_ReponsesCorrects=$Ligne_Q_R_RStagiaires[13];
				$Nb_ReponsesFausses=$Ligne_Q_R_RStagiaires[14];

				$Note=0;
				if($Nb_ReponsesFausses==0 || $Nb_ReponsesFausses=="")
				{
					$Note=round($Nb_ReponsesCorrects/$Nb_BonnesReponses,2);
					$sommeNote+=$Note*$Ligne_Q_R_RStagiaires[2];
					$sommeNoteQCM+=$Note*$Ligne_Q_R_RStagiaires[2];
				}
			}
			$num++;
		}
		if($nb==1)
		{
			$resultat=0;
			if($sommeCoeffQCM>0){$resultat=round($sommeNoteQCM/$sommeCoeffQCM*100,2);}
			//Ajout du résultat au QCM Mère
			$ReqUpdateFormSessionPersonneQualification="
				UPDATE
					form_session_personne_qualification
				SET
					ResultatMere=".$resultat."
				WHERE
					Id=". $Id_Session_Personne_Qualification;
			$ResultUpdateFormSessionPersonneQualification=mysqli_query($bdd,$ReqUpdateFormSessionPersonneQualification);
		}
	}
	
	//Resultat total 
	//=Somme Annexe & Mère (si annexe)
	//Sinon Mère 
	$resultat=0;
	if($sommeCoeff>0){$resultat=round(($sommeNote/$sommeCoeff)*100,2);}
	//Ajout du résultat au QCM Mère
	$ReqUpdateFormSessionPersonneQualification="
		UPDATE
			form_session_personne_qualification
		SET
			Resultat='".$resultat."'
		WHERE
			Id=". $Id_Session_Personne_Qualification;
	$ResultUpdateFormSessionPersonneQualification=mysqli_query($bdd,$ReqUpdateFormSessionPersonneQualification);
	
	//Enregistrement si réussite au QCM + Stockage de la note dans la qualification
	$req="
        SELECT
            Id_Besoin,
            Id_Qualification,
            TypePassageQCM,
            Id_Relation,
			(SELECT Id_Personne FROM form_besoin WHERE form_besoin.Id=form_session_personne_qualification.Id_Besoin) AS Id_PersonneBesoin,
            (SELECT Id_Personne FROM new_competences_relation WHERE new_competences_relation.Id=form_session_personne_qualification.Id_Relation) AS Id_PersonneRelation,
			(SELECT Id_Besoin FROM form_session_personne WHERE form_session_personne.Id=form_session_personne_qualification.Id_Session_Personne) AS Id_BesoinSession,
			(SELECT (SELECT Id_Personne FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) FROM form_session_personne WHERE form_session_personne.Id=form_session_personne_qualification.Id_Session_Personne) AS Id_PersonneSession,
			(SELECT Code FROM form_qcm WHERE form_qcm.Id=form_session_personne_qualification.Id_QCM) AS QCM
		FROM
            form_session_personne_qualification
		WHERE
            Id=". $Id_Session_Personne_Qualification;
	$ResultSessionPersQual=mysqli_query($bdd,$req);
	$RowSessionPersQual=mysqli_fetch_array($ResultSessionPersQual);
	
	$Id_Besoin=0;
	$Id_Personne=0;
	if($RowSessionPersQual['TypePassageQCM']==0)
	{
		$Id_Besoin=$RowSessionPersQual['Id_BesoinSession'];
		$Id_Personne=$RowSessionPersQual['Id_PersonneSession'];
	}
	elseif($RowSessionPersQual['TypePassageQCM']==1)
	{
		$Id_Besoin=$RowSessionPersQual['Id_Besoin'];
		$Id_Personne=$RowSessionPersQual['Id_PersonneBesoin'];
	}
	else 
	{
	    $Id_Personne=$RowSessionPersQual['Id_PersonneRelation'];
	}
	
	if($RowSessionPersQual['TypePassageQCM']==0 || $RowSessionPersQual['TypePassageQCM']==1)
	{
		if($RowSessionPersQual['TypePassageQCM']==0){
			$ReqBesoinMAJ="UPDATE form_besoin SET Traite=4 WHERE Id=".$Id_Besoin;
		}
		elseif($RowSessionPersQual['TypePassageQCM']==1){
			$ReqBesoinMAJ="UPDATE form_besoin SET Traite=5 WHERE Id=".$Id_Besoin;
		}
		$ResultBesoinMAJ=mysqli_query($bdd,$ReqBesoinMAJ);
		Set_ReussiteQCM($Id_Besoin, $Id_Personne, $RowSessionPersQual['Id_Qualification'], $Id_Session_Personne_Qualification, $resultat);
	}
	else
	{
	    //Cas d'un QCM de surveillance
	    //----------------------------
		$req="
            SELECT 
                new_competences_relation.Id_Personne,
                (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=new_competences_relation.Id_Personne) AS Personne,
                (SELECT Libelle FROM new_competences_qualification WHERE new_competences_qualification.Id=new_competences_relation.Id_Qualification_Parrainage) AS Qualification 
            FROM
                new_competences_relation
            WHERE
                Id=". $RowSessionPersQual['Id_Relation'];
		$ResultRelation=mysqli_query($bdd,$req);
		$RowRelation=mysqli_fetch_array($ResultRelation);
		
		$LettreEvaluationNoteTheoriqueETReussite=Get_EvaluationReussiteETNoteTheorique($RowRelation['Id_Personne'], $RowSessionPersQual['Id_Qualification'], $resultat);
		$Etat=$LettreEvaluationNoteTheoriqueETReussite[1];
		
		//Mise à jour de la table new_competences_relation
		$ReqDateFinQualification="";
		if($Etat==-1){
			$ReqDateFinQualification=",Statut_Surveillance='ECHEC' ";
		}
		else{
			$ReqUpdateCompetences="
				UPDATE
					new_competences_relation
				SET
					Date_Surveillance='".date('Y-m-d')."',
					QCM_Surveillance='".$resultat."'".
					$ReqDateFinQualification."
				WHERE
					Id=".$RowSessionPersQual['Id_Relation'];
			$ResultUpdateCompetences=mysqli_query($bdd,$ReqUpdateCompetences);
		}
		
		//Mise à jour de l'état dans la table form_session_personnne_qualification
		$ReqUpdateQualifQCM="
            UPDATE
                form_session_personne_qualification
            SET
                Etat=".$Etat."
            WHERE
                Id=".$Id_Session_Personne_Qualification;
		$ResultUpdateQualifQCM=mysqli_query($bdd,$ReqUpdateQualifQCM);
		
		if($Etat==-1)
		{
			$Headers='From: "QUALIPSO"<qualipso@aaa-aero.com>'."\n";
			$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";

			//Si echec 
			//Envoyer un mail aux CQP pour informer que la qualif est en echec
			if($LangueAffichage=="FR")
			{
				$Objet="Echec QCM de surveillance - ".$RowRelation['Personne'];
				
				$MessageMail="
                    <html>
						<head><title>Echec QCM de surveillance ".$RowRelation['Personne']." </title></head>
						<body>
							Bonjour,
							<br><br>
							<i>Cette boîte mail est une boîte mail générique</i>
							<br><br>
							".$RowRelation['Personne']." a échoué au QCM de surveillance ".$RowSessionPersQual['QCM']." pour la qualification ".$RowRelation['Qualification']." <br>
							Note : ".$resultat."% <br>
							Pensez à générer un besoin de formation si nécessaire
							<br>
							Bonne journée.<br>
							Formation Extranet Daher industriel services DIS.
						</body>
					</html>";
			}
			else
			{
				$Objet="Monitoring MCQ failure - ".$RowRelation['Personne'];
			
				$MessageMail="
                    <html>
						<head><title>Monitoring MCQ failure ".$RowRelation['Personne']." </title></head>
						<body>
							Hello,
							<br><br>
							<i>This mailbox is a generic mailbox</i>
							<br><br>
							".$RowRelation['Personne']." failed MCQ monitoring ".$RowSessionPersQual['QCM']." for qualification ".$RowRelation['Qualification']." <br>
							Note : ".$resultat."% <br>
							Think of generating a need for training if necessary
							<br>
							<br>
							Have a nice day.<br>
							Training Extranet Daher industriel services DIS.
						</body>
					</html>";
			}
			$Emails="";
				
			//Liste des CQP
			$reqCQ="
				SELECT DISTINCT
                    EmailPro 
				FROM
                    new_competences_personne_poste_prestation
				LEFT JOIN new_rh_etatcivil
				    ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
				WHERE
                    new_competences_personne_poste_prestation.Id_Poste IN (".implode(",",array($IdPosteReferentQualiteProduit)).") 
                    AND CONCAT(Id_Prestation,'_',Id_Pole) IN 
    				(
    					SELECT
    						CONCAT(Id_Prestation,'_',Id_Pole)
    					FROM
    						new_competences_personne_prestation
    					WHERE
    						Date_Fin>='".date('Y-m-d')."'
    						AND Id_Personne=".$RowRelation['Id_Personne']."
    				)";
			$ResultCQ=mysqli_query($bdd,$reqCQ);
			$NbCQ=mysqli_num_rows($ResultCQ);
			if($NbCQ>0)
			{
				while($RowCQ=mysqli_fetch_array($ResultCQ))
				{
					if($RowCQ['EmailPro']<>""){$Emails.=$RowCQ['EmailPro'].",";}
				}
			}
			if($Emails<>""){$Emails=substr($Emails,0,-1);}
			//$Emails="pfauge@aaa-aero.com";
			if($Emails<>"")
			{
				if(mail($Emails,$Objet,$MessageMail,$Headers,'-f qualipso@aaa-aero.com')){echo "";}
			}
		}
	}
}

/**
 * getInformationsModificationQCM
 * 
 * Recupere les informations principales pour la modification d un QCM sans formation.
 *
 * @param int $Id_session_personne_qualification Identifiant du QCM sans formation 
 * @return array le resultat de la requete
 * 
 * @author	Anthony Schricke <aschricke@aaa-aero.com>
 */
function getInformationsModificationQCMsansFormation($Id_session_personne_qualification)
{
    $req = "
        SELECT DISTINCT
        	\"Sans formation\" AS LIBELLE_TYPEFORMATION,
        	\"N/A\" AS REFERENCE_FORMATION,
        	\"\" AS ID_FORMATION,
        	form_session_personne_qualification.Id_Qualification,
        	form_session_personne_qualification.Id_QCM,
        	form_session_personne_qualification.Id_QCM_Lie,
        	form_session_personne_qualification.Id_LangueQCM,
        	form_session_personne_qualification.Id_LangueQCMLie,
        	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_besoin.Id_Personne) AS NOM_PRENOM,
        
        	\"Sans formation\" AS Libelle										
        FROM
        	form_session_personne_qualification,
        	form_typeformation,
        	form_formation,
        	form_besoin
        WHERE
        	form_session_personne_qualification.Id = ".$Id_session_personne_qualification."
        	AND form_formation.Id_TypeFormation = form_typeformation.Id
        	AND form_session_personne_qualification.Id_Besoin = form_besoin.Id;";
    return mysqli_fetch_array(getRessource($req));
}

/**
 * getListeQualificationsEtQCMsansFormation
 * 
 * Donne la liste des libelles des qualifications
 *   
 * @param int $Id_sesssion_personne_qualification Identifiant de qualification
 * @return array
 * 
 * @author	Anthony Schricke <aschricke@aaa-aero.com>
 */
function getListeQualificationsEtQCMsansFormation($Id_sesssion_personne_qualification)
{
    $req = "
        SELECT DISTINCT
        	Libelle
        FROM
        	new_competences_qualification,
        	form_session_personne_qualification
        WHERE
        	form_session_personne_qualification.Id_Qualification = new_competences_qualification.ID
        	AND form_session_personne_qualification.Id = ".$Id_sesssion_personne_qualification.";";
    return mysqli_fetch_array(getRessource($req));
}

/**
 * getInfosQCM
 * 
 * Recupere les informations de QCM pour un QCM sans session
 * 
 * @param int $Id_session_personne_qualification Identifiant de session_personne_qualification
 * @return resource La ressource de la reponse de la base de donnees
 */
function getInfosQCM($Id_session_personne_qualification)
{
    $req = "
        SELECT DISTINCT
        	form_session_personne_qualification.Id_QCM,
        	form_session_personne_qualification.Id_LangueQCM,
        
        	form_qcm.Code AS QCM,
        	form_qcm.Id_QCM_Lie,
        
        	(SELECT form_qcm2.Code FROM form_qcm AS form_qcm2 WHERE form_qcm2.Id=form_qcm.Id_QCM_Lie) AS QCMLie
        
        FROM
        	form_session_personne_qualification,
        	form_qcm
        
        WHERE
        	    form_session_personne_qualification.Id_QCM = form_qcm.Id	
        	AND form_qcm.Suppr=0
        	AND form_session_personne_qualification.Suppr = 0
        	AND form_session_personne_qualification.Id = ".$Id_session_personne_qualification.";";
    return getRessource($req);
}

/**
 * calculResultatQCMs
 * 
 * Calculer le resultat au QCM mere et total
 * 
 * @param int $Id_Session_Personne_Qualification Identifiant de la session personne qualification

 * @author	Pauline FAUGE <pfauge@aaa-aero.com>
 */
function calculResultatQCMsEchoue($Id_Session_Personne_Qualification,$nbLigne=-1,$num=-1)
{
	global $bdd;
	global $TableauIdPostesCQ;
	global $LangueAffichage;
	global $IdPosteReferentQualiteProduit;
	
    $ReqFormSessionPersonneQualification="
        SELECT
			form_session_personne_qualification.Id_QCM,
			form_session_personne_qualification.Id_LangueQCM,
			form_session_personne_qualification.Id_QCM_Lie,
			IF(Id_QCM_Lie>0,IF(Id_LangueQCMLie>0,Id_LangueQCMLie,Id_LangueQCM),0) AS Id_LangueQCMLie
        FROM
            form_session_personne_qualification
        WHERE
            form_session_personne_qualification.Id=".$Id_Session_Personne_Qualification;
    $ResultFormSessionPersonneQualification=mysqli_query($bdd,$ReqFormSessionPersonneQualification);
    $RowFormSessionPersonneQualification=mysqli_fetch_array($ResultFormSessionPersonneQualification);

	$tabQCM=array();
	$tabQCM[]=$RowFormSessionPersonneQualification['Id_QCM']."_".$RowFormSessionPersonneQualification['Id_LangueQCM'];
	if($RowFormSessionPersonneQualification['Id_QCM_Lie']>0)
	{
		$tabQCM[]=$RowFormSessionPersonneQualification['Id_QCM_Lie']."_".$RowFormSessionPersonneQualification['Id_LangueQCMLie'];
	}
	
	$sommeCoeff=0;
	$sommeNote=0;
	$nb=0;
	foreach($tabQCM AS $QCM)
	{
		$nb++;
		$sommeCoeffQCM=0;
		$sommeNoteQCM=0;
		
		$tabLeQCM = explode("_",$QCM);
		$ReqQCM_Langue="
			SELECT
				Id,
				Id_QCM,
				Id_Langue,
				Libelle,
				Date_MAJ,
				Id_Personne_MAJ,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne_MAJ) AS Personne
			FROM
				form_qcm_langue
			WHERE
				Suppr=0
				AND Id_Langue=".$tabLeQCM[1]." 
				AND Id_QCM=".$tabLeQCM[0];
		$ResultQCM_Langue=mysqli_query($bdd,$ReqQCM_Langue);
		$RowQCM_Langue=mysqli_fetch_array($ResultQCM_Langue);
		
		$QCM_Q_R_RStagiaires=Generer_QCMHistorique($RowQCM_Langue['Id'], $Id_Session_Personne_Qualification);
		
		$QuestionPrecedente="";
		$nbCoeff=0;
		$num=1;
		foreach($QCM_Q_R_RStagiaires as $Ligne_Q_R_RStagiaires)
		{
			//Réponses
			//--------
			$Nb_BonnesReponses=$Ligne_Q_R_RStagiaires[12];
			$Nb_Reponses=$Ligne_Q_R_RStagiaires[4];
			$Nb_ReponsesCorrects=$Ligne_Q_R_RStagiaires[13];
			$Nb_ReponsesFausses=$Ligne_Q_R_RStagiaires[14];
				
			if($Ligne_Q_R_RStagiaires[8]==$Ligne_Q_R_RStagiaires[9] && $Ligne_Q_R_RStagiaires[9]==1){$NoteReponse=round(1/$Nb_BonnesReponses,2);}
			else{$NoteReponse=0;}
			if($Nb_ReponsesFausses>0){$NoteReponse=0;}
			//-------

			if($Ligne_Q_R_RStagiaires[1] <> $QuestionPrecedente)
			{
				$QuestionPrecedente=$Ligne_Q_R_RStagiaires[1];
				$nbCoeff+=$Ligne_Q_R_RStagiaires[2];
				$sommeCoeff+=$Ligne_Q_R_RStagiaires[2];
				$sommeCoeffQCM+=$Ligne_Q_R_RStagiaires[2];

				$Nb_BonnesReponses=$Ligne_Q_R_RStagiaires[12];
				$Nb_Reponses=$Ligne_Q_R_RStagiaires[4];
				$Nb_ReponsesCorrects=$Ligne_Q_R_RStagiaires[13];
				$Nb_ReponsesFausses=$Ligne_Q_R_RStagiaires[14];

				$Note=0;
				if($Nb_ReponsesFausses==0 || $Nb_ReponsesFausses=="")
				{
					$Note=round($Nb_ReponsesCorrects/$Nb_BonnesReponses,2);
					$sommeNote+=$Note*$Ligne_Q_R_RStagiaires[2];
					$sommeNoteQCM+=$Note*$Ligne_Q_R_RStagiaires[2];
				}
			}
			$num++;
		}
	}
	
	//Resultat total 
	//=Somme Annexe & Mère (si annexe)
	//Sinon Mère 
	$resultat=0;
	if($sommeCoeff>0){$resultat=round($sommeNote/$sommeCoeff*100,2);}
	return $resultat;
}

function Generer_QCMHistorique($Id_QCM_Langue, $Id_Session_Personne_Qualification,$nbLigne=-1,$num=-1)
{
	global $bdd;
	$Tableau_Questions_Reponses_Resultat=array();
	
	//Liste des résultats de la personne
	$ReqReponsesStagiaireQCM="
		SELECT
			TAB.Id AS ID_SESSION_PERSONNE_QUALIFICATION_QUESTION,
			TAB.Id_QCM AS ID_QCM,
			TAB.Id_QCM_Langue_Question AS ID_QCM_LANGUE,
			TAB.NoteQuestion AS NOTE_QUESTION_STAGIAIRE,
			form_session_personne_qualification_question_reponse.Valeur AS VALEUR_REPONSE_STAGIAIRE,
			form_qcm_langue_question_reponse.Id AS ID_REPONSE,
			form_qcm_langue_question_reponse.Libelle AS LIBELLE_REPONSE,
			form_qcm_langue_question_reponse.Valeur AS VALEUR_REPONSE,
			form_qcm_langue_question_reponse.Fichier AS FICHIER_REPONSE,
			TAB.Coefficient AS COEFFICIENT_QUESTION,
			TAB.Libelle AS LIBELLE_QUESTION,
			TAB.Fichier AS FICHIER_QUESTION,
			TAB.Num AS NUM_QUESTION,
			TAB.ID_QUESTION,
			(SELECT COUNT(qcm_reponse.Id) 
			FROM form_qcm_langue_question_reponse AS qcm_reponse 
			WHERE qcm_reponse.Valeur=1 AND qcm_reponse.Suppr=0 AND qcm_reponse.Id_QCM_Langue_Question=TAB.Id_QCM_Langue_Question) AS NB_BONNES_REPONSES,
			(SELECT COUNT(qcm_bonneReponse.Id) 
				FROM form_session_personne_qualification_question_reponse AS qcm_bonneReponse
				WHERE qcm_bonneReponse.Suppr=0
				AND qcm_bonneReponse.Valeur=1
				AND qcm_bonneReponse.Id_Session_Personne_Qualification_Question=TAB.Id
				AND qcm_bonneReponse.Id_QCM_Langue_Question_Reponse IN (SELECT qcm_reponse.Id 
				FROM form_qcm_langue_question_reponse AS qcm_reponse 
				WHERE qcm_reponse.Valeur=1 AND qcm_reponse.Suppr=0 AND qcm_reponse.Id_QCM_Langue_Question=TAB.Id_QCM_Langue_Question)
			) AS NB_REPONSES_CORRECT,
			(SELECT COUNT(qcm_bonneReponse.Id) 
				FROM form_session_personne_qualification_question_reponse AS qcm_bonneReponse
				WHERE qcm_bonneReponse.Suppr=0
				AND qcm_bonneReponse.Valeur=1
				AND qcm_bonneReponse.Id_Session_Personne_Qualification_Question=TAB.Id
				AND qcm_bonneReponse.Id_QCM_Langue_Question_Reponse IN (SELECT qcm_reponse.Id 
				FROM form_qcm_langue_question_reponse AS qcm_reponse 
				WHERE qcm_reponse.Valeur=0 AND qcm_reponse.Suppr=0 AND qcm_reponse.Id_QCM_Langue_Question=TAB.Id_QCM_Langue_Question)
			) AS NB_REPONSES_FAUSSES
		FROM
			(SELECT 
			form_session_personne_qualification_question.Id,
			form_session_personne_qualification_question.Id_QCM,
			form_session_personne_qualification_question.Id_QCM_Langue_Question,
			form_session_personne_qualification_question.NoteQuestion,
			form_qcm_langue_question.Coefficient,
			form_qcm_langue_question.Libelle,
			form_qcm_langue_question.Fichier,
			form_qcm_langue_question.Num,
			form_qcm_langue_question.Id AS ID_QUESTION
			FROM form_session_personne_qualification_question
			LEFT JOIN form_qcm_langue_question
			ON form_session_personne_qualification_question.Id_QCM_Langue_Question = form_qcm_langue_question.Id
			WHERE form_session_personne_qualification_question.Id_Session_Personne_Qualification=".$Id_Session_Personne_Qualification."
			AND form_qcm_langue_question.Id_QCM_Langue=".$Id_QCM_Langue." ";
		if($nbLigne<>-1){
			$ReqReponsesStagiaireQCM.="LIMIT ".($num*$nbLigne).",".$nbLigne." ";
		}
	$ReqReponsesStagiaireQCM.=") AS TAB,
			form_session_personne_qualification_question_reponse,
			form_qcm_langue_question_reponse
		WHERE
			form_session_personne_qualification_question_reponse.Id_Session_Personne_Qualification_Question = TAB.Id
		AND form_session_personne_qualification_question_reponse.Id_QCM_Langue_Question_Reponse = form_qcm_langue_question_reponse.Id
		ORDER BY TAB.Num ASC, form_qcm_langue_question_reponse.Num ASC
		";
	$ResultReponsesStagiaireQCM=mysqli_query($bdd,$ReqReponsesStagiaireQCM);
	$ResultReponsesStagiaireQCMPourCompterReponses=mysqli_query($bdd,$ReqReponsesStagiaireQCM);
	$NbResultReponsesStagiaireQCM=mysqli_num_rows($ResultReponsesStagiaireQCM);

	if($NbResultReponsesStagiaireQCM > 0)
	{
		//Parcours des réponses du stagiaire
		//Mise en tableau des variables
		while($RowReponsesStagiaireQCM=mysqli_fetch_array($ResultReponsesStagiaireQCM))
		{
			//Comptage du nombre de réponses à la question
			mysqli_data_seek($ResultReponsesStagiaireQCMPourCompterReponses,0);
			$Nb_Reponses=0;
			while($RowReponsesStagiaireQCMPourCompterReponses=mysqli_fetch_array($ResultReponsesStagiaireQCMPourCompterReponses))
			{
				if($RowReponsesStagiaireQCMPourCompterReponses['ID_QUESTION']==$RowReponsesStagiaireQCM['ID_QUESTION']){$Nb_Reponses++;}
			}
			
			//Mise en tableau des éléments du QCM
			$Tableau_Questions_Reponses_Resultat[]=array
				(
					$RowReponsesStagiaireQCM['NUM_QUESTION'],
					stripslashes($RowReponsesStagiaireQCM['LIBELLE_QUESTION']),
					$RowReponsesStagiaireQCM['COEFFICIENT_QUESTION'],
					$RowReponsesStagiaireQCM['FICHIER_QUESTION'],
					$Nb_Reponses,
					$RowReponsesStagiaireQCM['ID_REPONSE'],
					stripslashes($RowReponsesStagiaireQCM['LIBELLE_REPONSE']),
					$RowReponsesStagiaireQCM['FICHIER_REPONSE'],
					$RowReponsesStagiaireQCM['VALEUR_REPONSE'],
					$RowReponsesStagiaireQCM['VALEUR_REPONSE_STAGIAIRE'],
					$RowReponsesStagiaireQCM['NOTE_QUESTION_STAGIAIRE'],
					$RowReponsesStagiaireQCM['ID_SESSION_PERSONNE_QUALIFICATION_QUESTION'],
					$RowReponsesStagiaireQCM['NB_BONNES_REPONSES'],
					$RowReponsesStagiaireQCM['NB_REPONSES_CORRECT'],
					$RowReponsesStagiaireQCM['NB_REPONSES_FAUSSES']
				);
		}
	}

	return $Tableau_Questions_Reponses_Resultat;
}
?>
<?php


function NbSessionsV2($Id_Plateforme,$Id_TypeFormations,$Id_Formateurs,$Categories,$Id_Formation,$DateDebut,$DateFin,$Annule)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $TableauIdPostesAF_RF_RQ_RH_CQS;
	
	$req="
		SELECT
			form_session.Id
		FROM
			form_session_date
		LEFT JOIN form_session
			ON form_session_date.Id_Session = form_session.Id
		WHERE
			form_session_date.Suppr=0
			AND form_session.Suppr=0
			AND form_session.Id_Plateforme IN (".$Id_Plateforme.")
			AND Annule=".$Annule."
			AND form_session_date.DateSession>='".$DateDebut."'
			AND form_session_date.DateSession<='".$DateFin."' ";
	if($Id_TypeFormations<>""){
		$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) IN (".$Id_TypeFormations.") ";
	}
	if($Id_Formateurs<>""){
		$req.=" AND form_session.Id_Formateur IN (".$Id_Formateurs.") ";
	}
	if($Categories<>""){
		$req.=" AND (SELECT form_formation.Categorie FROM form_formation WHERE Id=Id_Formation) IN (".$Categories.") ";
	}
	if($Id_Formation<>"" && $Id_Formation<>"0_0"){
		$tabQual=explode("_",$Id_Formation);
		if($tabQual[1]==0){
			$req.=" AND Id_Formation=".$tabQual[0]." ";
		}
		else{
			$req.=" AND Id_Formation IN 
				(SELECT Id_Formation 
				FROM form_formationequivalente_formationplateforme 
				WHERE Id_FormationEquivalente=".$tabQual[0].") ";
		}
	}

	$Result=mysqli_query($bdd,$req);
	$NbResult=mysqli_num_rows($Result);
	return $NbResult;
}

function NbEvaluation($Id_Plateforme,$Id_TypeFormations,$Id_Formateurs,$Categories,$Id_Formation,$DateDebut,$DateFin)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $TableauIdPostesAF_RF_RQ_RH_CQS;

	$req="
			SELECT
				form_session_personne_document.Id,
				(SELECT AVG(form_session_personne_document_question_reponse.Valeur_Reponse)
				FROM form_session_personne_document_question_reponse
				LEFT JOIN form_document_langue_question ON form_document_langue_question.Id=Id_Document_Langue_Question
				WHERE form_session_personne_document_question_reponse.Suppr=0
				AND form_document_langue_question.TypeReponse='Note (1 à 6)' 
				AND form_session_personne_document_question_reponse.Id_Session_Personne_Document=form_session_personne_document.Id) AS NoteMoyenne
			FROM form_session_personne_document
			LEFT JOIN form_session_personne ON form_session_personne_document.Id_Session_Personne=form_session_personne.Id
			LEFT JOIN form_session ON form_session_personne.Id_Session=form_session.Id
			WHERE
				form_session_personne.Suppr=0
				AND form_session.Annule=0
				AND form_session.Suppr=0
				AND form_session.Id_Plateforme IN (".$Id_Plateforme.")
				AND form_session_personne.Presence=1
				AND form_session_personne.Validation_Inscription=1
				AND form_session_personne_document.Suppr=0 
				AND form_session_personne_document.DateHeureRepondeur>'0001-01-01'
				AND form_session_personne_document.Id_Document=6
				AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)>='".$DateDebut."'
				AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)<='".$DateFin."' ";
		
	if($Id_TypeFormations<>""){
		$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) IN (".$Id_TypeFormations.") ";
	}
	if($Id_Formateurs<>""){
		$req.=" AND form_session.Id_Formateur IN (".$Id_Formateurs.") ";
	}
	if($Categories<>""){
		$req.=" AND (SELECT form_formation.Categorie FROM form_formation WHERE Id=Id_Formation) IN (".$Categories.") ";
	}
	if($Id_Formation<>"" && $Id_Formation<>"0_0"){
		$tabQual=explode("_",$Id_Formation);
		if($tabQual[1]==0){
			$req.=" AND Id_Formation=".$tabQual[0]." ";
		}
		else{
			$req.=" AND Id_Formation IN 
				(SELECT Id_Formation 
				FROM form_formationequivalente_formationplateforme 
				WHERE Id_FormationEquivalente=".$tabQual[0].") ";
		}
	}
	
	$req.="UNION
			SELECT
				form_session_personne_document.Id,
				(SELECT AVG(form_session_personne_document_question_reponse.Valeur_Reponse)
				FROM form_session_personne_document_question_reponse
				LEFT JOIN form_document_langue_question ON form_document_langue_question.Id=Id_Document_Langue_Question
				WHERE form_session_personne_document_question_reponse.Suppr=0
				AND form_document_langue_question.TypeReponse='Note (1 à 6)' 
				AND form_session_personne_document_question_reponse.Id_Session_Personne_Document=form_session_personne_document.Id) AS NoteMoyenne
			FROM form_session_personne_document
			LEFT JOIN form_session_personne_qualification ON form_session_personne_document.Id_SessionPersonneQualification=form_session_personne_qualification.Id
			LEFT JOIN form_besoin ON form_session_personne_qualification.Id_Besoin=form_besoin.Id
			WHERE
				form_session_personne_qualification.Suppr=0
				AND form_besoin.Suppr=0
				AND form_session_personne_document.Suppr=0
				AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation) IN (".$Id_Plateforme.")
				AND form_session_personne_document.DateHeureRepondeur>'0001-01-01'
				AND form_session_personne_document.Id_Document=6
				AND LEFT(form_session_personne_document.DateHeureRepondeur,10)>='".$DateDebut."'
				AND LEFT(form_session_personne_document.DateHeureRepondeur,10)<='".$DateFin."' ";
		
	if($Id_TypeFormations<>""){
		$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_besoin.Id_Formation) IN (".$Id_TypeFormations.") ";
	}
	if($Id_Formateurs<>""){
		$req.=" AND form_session_personne_qualification.Id_Ouvreur IN (".$Id_Formateurs.") ";
	}
	if($Categories<>""){
		$req.=" AND (SELECT form_formation.Categorie FROM form_formation WHERE Id=form_besoin.Id_Formation) IN (".$Categories.") ";
	}
	if($Id_Formation<>"" && $Id_Formation<>"0_0"){
		$tabQual=explode("_",$Id_Formation);
		if($tabQual[1]==0){
			$req.=" AND form_besoin.Id_Formation=".$tabQual[0]." ";
		}
		else{
			$req.=" AND AND form_besoin.Id_Formation IN 
				(SELECT Id_Formation 
				FROM form_formationequivalente_formationplateforme 
				WHERE Id_FormationEquivalente=".$tabQual[0].") ";
		}
	}
	$Result=mysqli_query($bdd,$req);
	$NbSessions=mysqli_num_rows($Result);

	$NbEval3=0;
	$NoteMoyenne=0;
	if($NbSessions>0){
		
		while($row=mysqli_fetch_array($Result))
		{
			$NoteMoyenne+=$row['NoteMoyenne'];
			$req="
			SELECT form_session_personne_document_question_reponse.Valeur_Reponse
			FROM form_session_personne_document_question_reponse
			LEFT JOIN form_document_langue_question ON form_document_langue_question.Id=Id_Document_Langue_Question
			WHERE form_session_personne_document_question_reponse.Suppr=0
			AND form_document_langue_question.TypeReponse='Note (1 à 6)' 
			AND form_session_personne_document_question_reponse.Id_Session_Personne_Document=".$row['Id']." 
			AND form_session_personne_document_question_reponse.Valeur_Reponse<=3
			";
			$ResultNote2=mysqli_query($bdd,$req);
			$NbNote2=mysqli_num_rows($ResultNote2);
			if($NbNote2>0){$NbEval3++;}
		}
		$NbResult=$NbEval3;
		$NoteMoyenne=round($NoteMoyenne/$NbSessions,1);
	}
		
	$tab = array();

	$tab[0]=$NbSessions;
	$tab[1]=$NbEval3;
	$tab[2]=$NoteMoyenne;
	return $tab;
}

function TauxRemplissageSession($Id_Plateforme,$Id_TypeFormations,$Id_Formateurs,$Categories,$Id_Formation,$DateDebut,$DateFin)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $TableauIdPostesAF_RF_RQ_RH_CQS;
	
	$req="
		SELECT  ROUND(AVG(Remplissage)) AS TauxRemplissage
		
		FROM (
			SELECT
				IF(Nb_Stagiaire_Maxi>0,((SELECT COUNT(Id) FROM form_session_personne WHERE Suppr=0 AND Validation_Inscription=1 AND form_session_personne.Id_Session=form_session.Id)/Nb_Stagiaire_Maxi)*100,100) AS Remplissage
			FROM
				form_session_date
			LEFT JOIN form_session
				ON form_session_date.Id_Session = form_session.Id
			WHERE
				form_session_date.Suppr=0
				AND form_session.Suppr=0
				AND form_session.Id_Plateforme IN (".$Id_Plateforme.")
				AND Annule=0
				AND form_session_date.DateSession>='".$DateDebut."'
				AND form_session_date.DateSession<='".$DateFin."' ";
		if($Id_TypeFormations<>""){
			$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) IN (".$Id_TypeFormations.") ";
		}
		if($Id_Formateurs<>""){
			$req.=" AND form_session.Id_Formateur IN (".$Id_Formateurs.") ";
		}
		if($Categories<>""){
			$req.=" AND (SELECT form_formation.Categorie FROM form_formation WHERE Id=Id_Formation) IN (".$Categories.") ";
		}
		if($Id_Formation<>"" && $Id_Formation<>"0_0"){
			$tabQual=explode("_",$Id_Formation);
			if($tabQual[1]==0){
				$req.=" AND Id_Formation=".$tabQual[0]." ";
			}
			else{
				$req.=" AND Id_Formation IN 
					(SELECT Id_Formation 
					FROM form_formationequivalente_formationplateforme 
					WHERE Id_FormationEquivalente=".$tabQual[0].") ";
			}
		}
	$req.=") AS TAB  ";
	$Result=mysqli_query($bdd,$req);
	$NbResult=mysqli_num_rows($Result);
	$taux=0;
	if($NbResult>0){
		$rowSession=mysqli_fetch_array($Result);
		$taux=$rowSession['TauxRemplissage'];
	}
	return $taux;
}

function TauxReussiteSession($Id_Plateforme,$Id_TypeFormations,$Id_Formateurs,$Categories,$Id_Formation,$DateDebut,$DateFin,$ColBleu)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $TableauIdPostesAF_RF_RQ_RH_CQS;
	
	$req="
		SELECT  ROUND(AVG(Remplissage)) AS TauxRemplissage
		
		FROM (
			SELECT
				IF((SELECT COUNT(Id) FROM form_session_personne_qualification WHERE Suppr=0 AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id)>0,
				((SELECT COUNT(Id) FROM form_session_personne_qualification WHERE Suppr=0 AND Etat=1 AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id)/
				(SELECT COUNT(Id) FROM form_session_personne_qualification WHERE Suppr=0 AND form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id))*100,100) AS Remplissage
			FROM 
				form_session_personne
			LEFT JOIN form_session
				ON form_session_personne.Id_Session = form_session.Id
			WHERE
				form_session.Suppr=0
				AND form_session.Id_Plateforme IN (".$Id_Plateforme.")
				AND Annule=0
				AND Validation_Inscription=1
				AND Presence=1
				AND ColBleu=".$ColBleu."
				AND (SELECT COUNT(Id)
				FROM form_session_date
				WHERE form_session_date.Suppr=0
				AND form_session_date.DateSession>='".$DateDebut."'
				AND form_session_date.DateSession<='".$DateFin."' 
				AND form_session_date.Id_Session = form_session.Id ) >0 ";
		if($Id_TypeFormations<>""){
			$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) IN (".$Id_TypeFormations.") ";
		}
		if($Id_Formateurs<>""){
			$req.=" AND form_session.Id_Formateur IN (".$Id_Formateurs.") ";
		}
		if($Categories<>""){
			$req.=" AND (SELECT form_formation.Categorie FROM form_formation WHERE Id=Id_Formation) IN (".$Categories.") ";
		}
		if($Id_Formation<>"" && $Id_Formation<>"0_0"){
			$tabQual=explode("_",$Id_Formation);
			if($tabQual[1]==0){
				$req.=" AND Id_Formation=".$tabQual[0]." ";
			}
			else{
				$req.=" AND Id_Formation IN 
					(SELECT Id_Formation 
					FROM form_formationequivalente_formationplateforme 
					WHERE Id_FormationEquivalente=".$tabQual[0].") ";
			}
		}
	$req.=") AS TAB  ";

	$Result=mysqli_query($bdd,$req);
	$NbResult=mysqli_num_rows($Result);
	$taux=0;
	if($NbResult>0){
		$rowSession=mysqli_fetch_array($Result);
		$taux=$rowSession['TauxRemplissage'];
	}
	return $taux;
}

function NbPersonnesInscritesV2($Id_Plateforme,$Id_TypeFormations,$Categories,$Id_Formation,$DateDebut,$DateFin,$Id_TypeContrat)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $TableauIdPostesAF_RF_RQ_RH_CQS;
	
	$req="
		SELECT
			form_session.Id
		FROM
			form_session_personne
		LEFT JOIN form_session ON form_session_personne.Id_Session = form_session.Id
		WHERE
			form_session_personne.Suppr=0
			AND form_session_personne.Validation_Inscription=1
			AND form_session_personne.Suppr=0
			AND form_session.Suppr=0
			AND form_session.Id_Plateforme IN (".$Id_Plateforme.")
			AND Annule=0
			AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)>='".$DateDebut."'
			AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)<='".$DateFin."' ";
			

			$req.=" AND IF((SELECT (SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
			FROM rh_personne_contrat
			WHERE rh_personne_contrat.Suppr=0
			AND rh_personne_contrat.DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
			AND (DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) OR DateFin<='0001-01-01' )
			AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
			ORDER BY DateDebut DESC, Id DESC LIMIT 1) IS NULL,'NULL',(SELECT (SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
			FROM rh_personne_contrat
			WHERE rh_personne_contrat.Suppr=0
			AND rh_personne_contrat.DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
			AND (DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) OR DateFin<='0001-01-01' )
			AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
			ORDER BY DateDebut DESC, Id DESC LIMIT 1)) IN (".$Id_TypeContrat.") ";

			if($Id_TypeFormations<>""){
				$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) IN (".$Id_TypeFormations.") ";
			}
			if($Categories<>""){
				$req.=" AND (SELECT form_formation.Categorie FROM form_formation WHERE Id=Id_Formation) IN (".$Categories.") ";
			}
			if($Id_Formation<>"" && $Id_Formation<>"0_0"){
				$tabQual=explode("_",$Id_Formation);
				if($tabQual[1]==0){
					$req.=" AND Id_Formation=".$tabQual[0]." ";
				}
				else{
					$req.=" AND Id_Formation IN 
						(SELECT Id_Formation 
						FROM form_formationequivalente_formationplateforme 
						WHERE Id_FormationEquivalente=".$tabQual[0].") ";
				}
			}

		$Result=mysqli_query($bdd,$req);
	    $NbResult=mysqli_num_rows($Result);
	return $NbResult;
}

function NbHeuresFormation($Id_Plateforme,$Id_TypeFormations,$Categories,$Id_Formateurs,$Id_Formation,$DateDebut,$DateFin,$Id_TypeContrat)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $TableauIdPostesAF_RF_RQ_RH_CQS;
	
	$req="
		SELECT
			form_session_personne.Id,
			form_session_date.DateSession,
			form_session_date.Heure_Debut,
			form_session_date.Heure_Fin,
			form_session_date.PauseRepas,
			form_session_date.HeureDebutPause,
			form_session_date.HeureFinPause
		FROM
			form_session_personne,form_session_date,form_session
		WHERE
			form_session_personne.Id_Session = form_session.Id
			AND form_session_date.Id_Session = form_session.Id
			AND form_session_personne.Suppr=0
			AND form_session_personne.Validation_Inscription=1
			AND form_session_personne.Presence NOT IN (-1,-2)
			AND form_session.Suppr=0
			AND form_session.Id_Plateforme IN (".$Id_Plateforme.")
			AND Annule=0
			AND DateSession >='".$DateDebut."'
			AND DateSession <='".$DateFin."' 
			AND form_session_date.Suppr=0
			";


			$req.=" AND IF((SELECT (SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
			FROM rh_personne_contrat
			WHERE rh_personne_contrat.Suppr=0
			AND rh_personne_contrat.DateDebut<=form_session_date.DateSession
			AND (DateFin>=form_session_date.DateSession OR DateFin<='0001-01-01' )
			AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
			ORDER BY DateDebut DESC, Id DESC LIMIT 1) IS NULL,'NULL',(SELECT (SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
			FROM rh_personne_contrat
			WHERE rh_personne_contrat.Suppr=0
			AND rh_personne_contrat.DateDebut<=form_session_date.DateSession
			AND (DateFin>=form_session_date.DateSession OR DateFin<='0001-01-01' )
			AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
			ORDER BY DateDebut DESC, Id DESC LIMIT 1)) IN (".$Id_TypeContrat.") ";

			if($Id_TypeFormations<>""){
				$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) IN (".$Id_TypeFormations.") ";
			}
			if($Categories<>""){
				$req.=" AND (SELECT form_formation.Categorie FROM form_formation WHERE Id=Id_Formation) IN (".$Categories.") ";
			}
			if($Id_Formateurs<>""){
				$req.=" AND form_session.Id_Formateur IN (".$Id_Formateurs.") ";
			}
			if($Id_Formation<>"" && $Id_Formation<>"0_0"){
				$tabQual=explode("_",$Id_Formation);
				if($tabQual[1]==0){
					$req.=" AND Id_Formation=".$tabQual[0]." ";
				}
				else{
					$req.=" AND Id_Formation IN 
						(SELECT Id_Formation 
						FROM form_formationequivalente_formationplateforme 
						WHERE Id_FormationEquivalente=".$tabQual[0].") ";
				}
			}
		$req.=" ";
		
		$Result=mysqli_query($bdd,$req);
	    $NbResult=mysqli_num_rows($Result);

		$nbHeureFormation=0;
		if($NbResult>0){
			while($rowForm=mysqli_fetch_array($Result)){
				//Nombre total d'heure de formation
				$hF=strtotime($rowForm['Heure_Fin']);
				$hD=strtotime($rowForm['Heure_Debut']);
				$val=gmdate("H:i",$hF-$hD);
				$bTrouve=1;
				if($rowForm['PauseRepas']==1){
					$hFP=strtotime($rowForm['HeureFinPause']);
					$hDP=strtotime($rowForm['HeureDebutPause']);
					if($hDP<$hF && $hFP>$hD){
						if($hFP>$hF){$hFP=$hF;}
						if($hDP<$hD){$hDP=$hD;}
						$valPause=gmdate("H:i",$hFP-$hDP);
						$val=gmdate("H:i",strtotime($val)-strtotime($valPause));
					}
				}
				$nbHeureForm=intval(date('H',strtotime($val." + 0 hour"))).".".substr((date('i',strtotime($val." + 0 hour"))/0.6),0,2);
				
				$nbHeureFormation+=$nbHeureForm;
			}
		}
	return $nbHeureFormation;
}

function NbPersonnesPresentesV2($Id_Plateforme,$Id_TypeFormations,$Categories,$Id_Formation,$DateDebut,$DateFin,$Id_TypeContrat,$Present)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $TableauIdPostesAF_RF_RQ_RH_CQS;
	
	if($Present==1){$laPresence="1";}
	else{$laPresence="-1,-2";}
	$req="
		SELECT 
			form_session_personne.Id
		FROM
			form_session_personne
		LEFT JOIN form_session ON form_session_personne.Id_Session = form_session.Id
		WHERE
			form_session_personne.Suppr=0
			AND form_session_personne.Validation_Inscription=1
			AND form_session_personne.Suppr=0
			AND form_session.Suppr=0
			AND Presence IN (".$laPresence.")
			AND form_session.Id_Plateforme IN (".$Id_Plateforme.")
			AND Annule=0
			AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)>='".$DateDebut."'
			AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)<='".$DateFin."' ";
			if($Id_TypeContrat<>""){
				$req.=" AND IF((SELECT (SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
				FROM rh_personne_contrat
				WHERE rh_personne_contrat.Suppr=0
				AND rh_personne_contrat.DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
				AND (DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) OR DateFin<='0001-01-01' )
				AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
				AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
				ORDER BY DateDebut DESC, Id DESC LIMIT 1) IS NULL,'NULL',(SELECT (SELECT EstSalarie FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
				FROM rh_personne_contrat
				WHERE rh_personne_contrat.Suppr=0
				AND rh_personne_contrat.DateDebut<=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)
				AND (DateFin>=(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) OR DateFin<='0001-01-01' )
				AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
				AND rh_personne_contrat.Id_Personne=form_session_personne.Id_Personne
				ORDER BY DateDebut DESC, Id DESC LIMIT 1)) IN (".$Id_TypeContrat.") ";
			}
			if($Id_TypeFormations<>""){
				$req.=" AND (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) IN (".$Id_TypeFormations.") ";
			}
			if($Categories<>""){
				$req.=" AND (SELECT form_formation.Categorie FROM form_formation WHERE Id=Id_Formation) IN (".$Categories.") ";
			}
			if($Id_Formation<>"" && $Id_Formation<>"0_0"){
				$tabQual=explode("_",$Id_Formation);
				if($tabQual[1]==0){
					$req.=" AND Id_Formation=".$tabQual[0]." ";
				}
				else{
					$req.=" AND Id_Formation IN 
						(SELECT Id_Formation 
						FROM form_formationequivalente_formationplateforme 
						WHERE Id_FormationEquivalente=".$tabQual[0].") ";
				}
			}
		$Result=mysqli_query($bdd,$req);
	    $NbResult=mysqli_num_rows($Result);
	return $NbResult;
}

function NbSessions($Id_TypeFormation, $Annee,$Mois,$Prestation,$formation,$Annule = 0)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $TableauIdPostesAF_RF_RQ_RH_CQS;
	
	$req="
		SELECT
			form_session.Id
		FROM
			form_session_date
		LEFT JOIN form_session
			ON form_session_date.Id_Session = form_session.Id
		WHERE
			form_session_date.Suppr=0
			AND form_session.Suppr=0
			AND  (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=".$Id_TypeFormation."
			AND form_session.Id_Plateforme
			 IN (
				SELECT
					Id_Plateforme 
				FROM
					new_competences_personne_poste_plateforme
				WHERE
					Id_Personne=".$IdPersonneConnectee."
					AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS).")
			)
			AND Annule=".$Annule."
			AND YEAR(form_session_date.DateSession)='".$Annee."'
			AND MONTH(form_session_date.DateSession)='".$Mois."' ";
			if($Prestation<>"")
			{
				$req.="
					AND 
					
					( SELECT (SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=form_session_Prestation.Id_Prestation)
					FROM form_session_Prestation
					WHERE form_session_Prestation.Suppr=0
					AND form_session_Prestation.Id_Session=form_session.Id
					) 
					LIKE '%".$Prestation."%' 
					";
			}
			if($formation<>""){$req.="AND (
					SELECT IF(form_session.Recyclage=1,LibelleRecyclage,Libelle)
						FROM form_formation_langue_infos
						WHERE Id_Formation=form_session.Id_Formation
						AND Id_Langue=
							(SELECT Id_Langue 
							FROM form_formation_plateforme_parametres 
							WHERE Id_Plateforme=form_session.Id_Plateforme
							AND Id_Formation=form_session.Id_Formation
							AND form_formation_plateforme_parametres.Suppr=0 
							LIMIT 1)
						AND Suppr=0
					) LIKE '%".$formation."%' ";}
		$Result=mysqli_query($bdd,$req);
	    $NbResult=mysqli_num_rows($Result);
	return $NbResult;
}

function NbPersonnesInscrites($Type, $Annee,$Mois,$Prestation,$formation,$TypeContrat)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $TableauIdPostesAF_RF_RQ_RH_CQS;
	
	if($Type=="INTERNE"){$leType="1,3";}
	else{$leType="2,4";}
	
	if($TypeContrat=="Salarié"){$leTypeContrat=" NOT LIKE '%Intérim%'";}
	else{$leTypeContrat=" LIKE '%Intérim%'";}
	$req="
		SELECT
			form_session.Id
		FROM
			form_session_personne
		LEFT JOIN form_session
			ON form_session_personne.Id_Session = form_session.Id
		WHERE
			form_session_personne.Suppr=0
			AND form_session_personne.Validation_Inscription=1
			AND form_session_personne.Suppr=0
			AND form_session.Suppr=0
			AND Contrat".$leTypeContrat."
			AND  (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) IN (".$leType.")
			AND form_session.Id_Plateforme
			 IN (
				SELECT
					Id_Plateforme 
				FROM
					new_competences_personne_poste_plateforme
				WHERE
					Id_Personne=".$IdPersonneConnectee."
					AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS).")
			)
			AND form_session.Annule=0
			AND (SELECT YEAR(DateSession) FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) = '".$Annee."'
			AND (SELECT MONTH(DateSession) FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) = '".$Mois."' ";
			if($Prestation<>"")
			{
				$req.="
					AND 
					
					( SELECT (SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=form_session_Prestation.Id_Prestation)
					FROM form_session_Prestation
					WHERE form_session_Prestation.Suppr=0
					AND form_session_Prestation.Id_Session=form_session.Id
					) 
					LIKE '%".$Prestation."%' 
					";
			}
			if($formation<>""){$req.="AND (
					SELECT IF(form_session.Recyclage=1,LibelleRecyclage,Libelle)
						FROM form_formation_langue_infos
						WHERE Id_Formation=form_session.Id_Formation
						AND Id_Langue=
							(SELECT Id_Langue 
							FROM form_formation_plateforme_parametres 
							WHERE Id_Plateforme=form_session.Id_Plateforme
							AND Id_Formation=form_session.Id_Formation
							AND form_formation_plateforme_parametres.Suppr=0 
							LIMIT 1)
						AND Suppr=0
					) LIKE '%".$formation."%' ";}

		$Result=mysqli_query($bdd,$req);
	    $NbResult=mysqli_num_rows($Result);
	return $NbResult;
}

function NbPersonnesPresentes($Type, $Annee,$Mois,$Prestation,$formation,$TypeContrat,$Present)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $TableauIdPostesAF_RF_RQ_RH_CQS;
	
	if($Type=="INTERNE"){$leType="1,3";}
	else{$leType="2,4";}
	
	if($TypeContrat=="Salarié"){$leTypeContrat=" NOT LIKE '%Intérim%'";}
	else{$leTypeContrat=" LIKE '%Intérim%'";}
	
	if($Present==1){$laPresence="1";}
	else{$laPresence="-1,-2";}
	$req="
		SELECT
			form_session.Id
		FROM
			form_session_personne
		LEFT JOIN form_session
			ON form_session_personne.Id_Session = form_session.Id
		WHERE
			form_session_personne.Suppr=0
			AND form_session_personne.Validation_Inscription=1
			AND form_session_personne.Suppr=0
			AND form_session.Suppr=0
			AND Presence IN (".$laPresence.")
			AND Contrat".$leTypeContrat."
			AND  (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) IN (".$leType.")
			AND form_session.Id_Plateforme
			 IN (
				SELECT
					Id_Plateforme 
				FROM
					new_competences_personne_poste_plateforme
				WHERE
					Id_Personne=".$IdPersonneConnectee."
					AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS).")
			)
			AND form_session.Annule=0
			AND (SELECT YEAR(DateSession) FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) = '".$Annee."'
			AND (SELECT MONTH(DateSession) FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) = '".$Mois."' ";
			if($Prestation<>"")
			{
				$req.="
					AND 
					
					( SELECT (SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=form_session_Prestation.Id_Prestation)
					FROM form_session_Prestation
					WHERE form_session_Prestation.Suppr=0
					AND form_session_Prestation.Id_Session=form_session.Id
					) 
					LIKE '%".$Prestation."%' 
					";
			}
			if($formation<>""){$req.="AND (
					SELECT IF(form_session.Recyclage=1,LibelleRecyclage,Libelle)
						FROM form_formation_langue_infos
						WHERE Id_Formation=form_session.Id_Formation
						AND Id_Langue=
							(SELECT Id_Langue 
							FROM form_formation_plateforme_parametres 
							WHERE Id_Plateforme=form_session.Id_Plateforme
							AND Id_Formation=form_session.Id_Formation
							AND form_formation_plateforme_parametres.Suppr=0 
							LIMIT 1)
						AND Suppr=0
					) LIKE '%".$formation."%' ";}

		$Result=mysqli_query($bdd,$req);
	    $NbResult=mysqli_num_rows($Result);
	return $NbResult;
}

function ReqInscriptionsParPrestation($Annee,$Mois,$Prestation,$formation)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $TableauIdPostesAF_RF_RQ_RH_CQS;
	
	$req="
		SELECT
			(SELECT Id_Prestation FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Id_Prestation,
			(SELECT Id_Pole FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Id_Pole,
			(SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Prestation,
			(SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Pole,
			COUNT(form_session_personne.Id) AS NbInscrit
		FROM
			form_session_personne
		LEFT JOIN form_session
			ON form_session_personne.Id_Session = form_session.Id
		WHERE
			form_session_personne.Suppr=0
			AND form_session_personne.Validation_Inscription=1
			AND form_session_personne.Suppr=0
			AND form_session.Suppr=0
			AND form_session.Id_Plateforme
			 IN (
				SELECT
					Id_Plateforme 
				FROM
					new_competences_personne_poste_plateforme
				WHERE
					Id_Personne=".$IdPersonneConnectee."
					AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS).")
			)
			AND form_session.Annule=0
			AND (SELECT YEAR(DateSession) FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) = '".$Annee."'
			AND (SELECT MONTH(DateSession) FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) = '".$Mois."' ";
			if($Prestation<>"")
			{
				$req.="
					AND 
					(
					(SELECT (SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) 
					LIKE '%".$Prestation."%' 
					)
					OR 
					(
					(SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) 
					LIKE '%".$Prestation."%' 
					)
					)
					";
			}
			if($formation<>""){$req.="AND (
					SELECT IF(form_session.Recyclage=1,LibelleRecyclage,Libelle)
						FROM form_formation_langue_infos
						WHERE Id_Formation=form_session.Id_Formation
						AND Id_Langue=
							(SELECT Id_Langue 
							FROM form_formation_plateforme_parametres 
							WHERE Id_Plateforme=form_session.Id_Plateforme
							AND Id_Formation=form_session.Id_Formation
							AND form_formation_plateforme_parametres.Suppr=0 
							LIMIT 1)
						AND Suppr=0
					) LIKE '%".$formation."%' ";}
		$req.="GROUP BY Id_Prestation, Id_Pole 
		ORDER BY NbInscrit DESC
		";
	return $req;
}

function ReqInscriptionsAbsentParPrestation($Annee,$Mois,$Prestation,$formation)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $TableauIdPostesAF_RF_RQ_RH_CQS;
	
	$req="
		SELECT
			(SELECT Id_Prestation FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Id_Prestation,
			(SELECT Id_Pole FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Id_Pole,
			(SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Prestation,
			(SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Pole,
			COUNT(form_session_personne.Id) AS NbAbsent
		FROM
			form_session_personne
		LEFT JOIN form_session
			ON form_session_personne.Id_Session = form_session.Id
		WHERE
			form_session_personne.Suppr=0
			AND form_session_personne.Validation_Inscription=1
			AND form_session_personne.Suppr=0
			AND form_session.Suppr=0
			AND Presence IN (-1,-2)
			AND form_session.Id_Plateforme
			 IN (
				SELECT
					Id_Plateforme 
				FROM
					new_competences_personne_poste_plateforme
				WHERE
					Id_Personne=".$IdPersonneConnectee."
					AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS).")
			)
			AND form_session.Annule=0
			AND (SELECT YEAR(DateSession) FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) = '".$Annee."'
			AND (SELECT MONTH(DateSession) FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) = '".$Mois."' ";
			if($Prestation<>"")
			{
				$req.="
					AND 
					(
					(SELECT (SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) 
					LIKE '%".$Prestation."%' 
					)
					OR 
					(
					(SELECT (SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) 
					LIKE '%".$Prestation."%' 
					)
					)
					";
			}
			if($formation<>""){$req.="AND (
					SELECT IF(form_session.Recyclage=1,LibelleRecyclage,Libelle)
						FROM form_formation_langue_infos
						WHERE Id_Formation=form_session.Id_Formation
						AND Id_Langue=
							(SELECT Id_Langue 
							FROM form_formation_plateforme_parametres 
							WHERE Id_Plateforme=form_session.Id_Plateforme
							AND Id_Formation=form_session.Id_Formation
							AND form_formation_plateforme_parametres.Suppr=0 
							LIMIT 1)
						AND Suppr=0
					) LIKE '%".$formation."%' ";}
		$req.="GROUP BY Id_Prestation, Id_Pole 
		ORDER BY NbAbsent DESC
		";
	return $req;
}

function ReqInscriptionsAbsent($Annee,$Mois,$Id_Prestation,$Id_Pole)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $TableauIdPostesAF_RF_RQ_RH_CQS;
	
	$req="
		SELECT
			form_session_personne.Id
		FROM
			form_session_personne
		LEFT JOIN form_session
			ON form_session_personne.Id_Session = form_session.Id
		WHERE
			form_session_personne.Suppr=0
			AND form_session_personne.Validation_Inscription=1
			AND form_session_personne.Suppr=0
			AND form_session.Suppr=0
			AND Presence IN (-1,-2)
			AND form_session.Id_Plateforme
			 IN (
				SELECT
					Id_Plateforme 
				FROM
					new_competences_personne_poste_plateforme
				WHERE
					Id_Personne=".$IdPersonneConnectee."
					AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS).")
			)
			AND form_session.Annule=0
			AND (SELECT Id_Prestation FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin)=".$Id_Prestation."
			AND (SELECT Id_Pole FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin)=".$Id_Pole."
			AND (SELECT YEAR(DateSession) FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) = '".$Annee."'
			AND (SELECT MONTH(DateSession) FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) = '".$Mois."' ";
	return $req;
}
?>
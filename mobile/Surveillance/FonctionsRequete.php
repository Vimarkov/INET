<?php
function getJours($datedeb,$datefin){
    $nb_jours=0;
    $dated=explode('-',$datedeb);
    $datef=explode('-',$datefin);
    $timestampcurr=mktime(0,0,0,$dated[1],$dated[2],$dated[0]);
    $timestampf=mktime(0,0,0,$datef[1],$datef[2],$datef[0]);
	$resulta = $timestampcurr - $timestampf;
	if($timestampcurr<=$timestampf){
		while($timestampcurr<$timestampf){
			if((date('w',$timestampcurr)!=0)&&(date('w',$timestampcurr)!=6)){
			$nb_jours++;
			}
			$timestampcurr=mktime(0,0,0,date('m',$timestampcurr),(date('d',$timestampcurr)+1)   ,date('Y',$timestampcurr));
		}
	}
	else{
		while($timestampf<$timestampcurr){
			if((date('w',$timestampf)!=0)&&(date('w',$timestampf)!=6)){
			$nb_jours--;
			}
			$timestampf=mktime(0,0,0,date('m',$timestampf),(date('d',$timestampf)+1)   ,date('Y',$timestampf));
		}
	}
	return $nb_jours;
}

/* ---------------Nb surveillances---------------*/
/* Nb surveillances / Thematique */
function reqNbSurveillanceThematique($prestations,$annee,$generiques,$specifiques){
	$req = "SELECT COUNT(new_surveillances_surveillance.ID) AS Nb, ";
	$req .= "IF(new_surveillances_surveillance.Etat='Replanifié','Planifié',IF(new_surveillances_surveillance.Etat='Réalisé','Clôturé',new_surveillances_surveillance.Etat)) AS Etat, ";
	$req .= "new_surveillances_surveillance.ID_Questionnaire, ";
	$req .= "new_surveillances_questionnaire.Nom, ";
	$req .= "new_surveillances_questionnaire.ID_Theme, ";
	$req .= "new_surveillances_questionnaire.ID_Plateforme  ";
	$req .= "FROM new_surveillances_surveillance LEFT JOIN new_surveillances_questionnaire  ";
	$req .= "ON new_surveillances_surveillance.ID_Questionnaire = new_surveillances_questionnaire.ID ";
	$req .= "WHERE ";
	$req .= "YEAR(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)) =".$annee." AND (";
	foreach($prestations as $prestation){
		$req .= "new_surveillances_surveillance.ID_Prestation=".substr($prestation,strrpos($prestation,"_")+1)." OR ";
	}
	$req = substr($req,0,-3);
	$req .= ") AND ( ";
	if ($generiques <> ""){
		foreach($generiques as $generique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($generique,strrpos($generique,"_")+1)." OR ";
		}
	}
	if ($specifiques <> ""){
		foreach($specifiques as $specifique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($specifique,strrpos($specifique,"_")+1)." OR ";
		}
	}
	$req = substr($req,0,-3);
	$req .= ") ";
	$req .= "GROUP BY IF(new_surveillances_surveillance.Etat='Replanifié','Planifié',IF(new_surveillances_surveillance.Etat='Réalisé','Clôturé',new_surveillances_surveillance.Etat)), new_surveillances_surveillance.ID_Questionnaire ";
	return $req;
}

/* Nb surveillances / Mois */
function reqNbSurveillanceMois($prestations,$annee,$generiques,$specifiques){
	$req = "SELECT COUNT(new_surveillances_surveillance.ID) AS Nb, 
		IF(new_surveillances_surveillance.Etat='Replanifié','Planifié',IF(new_surveillances_surveillance.Etat='Réalisé','Clôturé',new_surveillances_surveillance.Etat)) AS Etat, ";
	$req .="CONCAT( ";
    $req .="YEAR(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)), ";
    $req .="'_', ";
    $req .="IF(MONTH(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif))>=10, ";
    $req .="   MONTH(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)), ";
    $req .="   CONCAT('0',MONTH(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif))) ";
    $req .="   ) ";
    $req .=") AS leMois ";
    $req .="FROM new_surveillances_surveillance ";
	$req .= "WHERE ";
	$req .= "YEAR(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)) =".$annee." AND (";
	foreach($prestations as $prestation){
		$req .= "new_surveillances_surveillance.ID_Prestation=".substr($prestation,strrpos($prestation,"_")+1)." OR ";
	}
	$req = substr($req,0,-3);
	$req .= ") AND ( ";
	if ($generiques <> ""){
		foreach($generiques as $generique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($generique,strrpos($generique,"_")+1)." OR ";
		}
	}
	if ($specifiques <> ""){
		foreach($specifiques as $specifique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($specifique,strrpos($specifique,"_")+1)." OR ";
		}
	}
	$req = substr($req,0,-3);
	$req .= ") ";
    $req .="GROUP BY IF(new_surveillances_surveillance.Etat='Replanifié','Planifié',IF(new_surveillances_surveillance.Etat='Réalisé','Clôturé',new_surveillances_surveillance.Etat)), CONCAT( ";
    $req .="    YEAR(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)),  ";
    $req .="    '_', ";
    $req .="    IF(MONTH(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif))>=10, ";
    $req .="       MONTH(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)), ";
    $req .="       CONCAT('0',MONTH(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif))) ";
    $req .="       ) ";
    $req .="    ) ";
	return $req;
}

/* Nb surveillances / Plateforme */
function reqNbSurveillancePlateforme($prestations,$annee,$generiques,$specifiques){
	$req = "SELECT COUNT(new_surveillances_surveillance.ID) AS Nb, 
	IF(new_surveillances_surveillance.Etat='Replanifié','Planifié',IF(new_surveillances_surveillance.Etat='Réalisé','Clôturé',new_surveillances_surveillance.Etat)) AS Etat,   ";
	$req .="new_competences_prestation.Id_Plateforme,  ";
	$req .="(SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id = new_competences_prestation.Id_Plateforme) AS Plateforme  ";
	$req .="FROM new_surveillances_surveillance LEFT JOIN new_competences_prestation  ";
	$req .="ON new_surveillances_surveillance.ID_Prestation = new_competences_prestation.Id  ";
	$req .= "WHERE ";
	$req .= "YEAR(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)) =".$annee." AND (";
	foreach($prestations as $prestation){
		$req .= "new_surveillances_surveillance.ID_Prestation=".substr($prestation,strrpos($prestation,"_")+1)." OR ";
	}
	$req = substr($req,0,-3);
	$req .= ") AND ( ";
	if ($generiques <> ""){
		foreach($generiques as $generique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($generique,strrpos($generique,"_")+1)." OR ";
		}
	}
	if ($specifiques <> ""){
		foreach($specifiques as $specifique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($specifique,strrpos($specifique,"_")+1)." OR ";
		}
	}
	$req = substr($req,0,-3);
	$req .= ") ";
	$req .="GROUP BY IF(new_surveillances_surveillance.Etat='Replanifié','Planifié',IF(new_surveillances_surveillance.Etat='Réalisé','Clôturé',new_surveillances_surveillance.Etat)), new_competences_prestation.ID_Plateforme  ";
	return $req;
}

/* Nb surveillances / Prestation */
function reqNbSurveillancePrestation($prestations,$annee,$generiques,$specifiques){
	$req = "SELECT COUNT(new_surveillances_surveillance.ID) AS Nb, 
	IF(new_surveillances_surveillance.Etat='Replanifié','Planifié',IF(new_surveillances_surveillance.Etat='Réalisé','Clôturé',new_surveillances_surveillance.Etat)) AS Etat,  "; 
	$req .="new_surveillances_surveillance.Id_Prestation,  ";
	$req .="new_competences_prestation.Libelle,  ";
	$req .="new_competences_prestation.Id_Plateforme ";
	$req .="FROM new_surveillances_surveillance LEFT JOIN new_competences_prestation  ";
	$req .="ON new_surveillances_surveillance.ID_Prestation = new_competences_prestation.Id  ";
	$req .= "WHERE ";
	$req .= "YEAR(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)) =".$annee." AND (";
	foreach($prestations as $prestation){
		$req .= "new_surveillances_surveillance.ID_Prestation=".substr($prestation,strrpos($prestation,"_")+1)." OR ";
	}
	$req = substr($req,0,-3);
	$req .= ") AND ( ";
	if ($generiques <> ""){
		foreach($generiques as $generique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($generique,strrpos($generique,"_")+1)." OR ";
		}
	}
	if ($specifiques <> ""){
		foreach($specifiques as $specifique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($specifique,strrpos($specifique,"_")+1)." OR ";
		}
	}
	$req = substr($req,0,-3);
	$req .= ") ";
	$req .="GROUP BY IF(new_surveillances_surveillance.Etat='Replanifié','Planifié',IF(new_surveillances_surveillance.Etat='Réalisé','Clôturé',new_surveillances_surveillance.Etat)), new_surveillances_surveillance.ID_Prestation  ";
	return $req;
}

/* ---------------Moyenne des notes ---------------*/
/* Moyenne note / Mois */
function reqMoyenneNoteMois($prestations,$annee,$generiques,$specifiques){
	$req = "SELECT   ";
	$req .="tab.MoisAnnee, ROUND(AVG(note)*100,1) AS Note ";
	$req .="FROM(SELECT new_surveillances_surveillance.ID, ";
	$req .="CONCAT( ";
	$req .="    YEAR(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)), "; 
	$req .="    '_', ";
	$req .="    IF(MONTH(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif))>=10, ";
	$req .="       MONTH(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)), ";
	$req .="       CONCAT('0',MONTH(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif))) ";
	$req .="       ) ";
	$req .="    ) AS MoisAnnee, ";
	$req .="((SELECT COUNT(new_surveillances_surveillance_question.ID)  ";
 	$req .="FROM new_surveillances_surveillance_question  ";
 	$req .="WHERE new_surveillances_surveillance_question.ID_Surveillance =  ";
 	$req .="new_surveillances_surveillance.ID ";
 	$req .="AND new_surveillances_surveillance_question.Etat = 'C') / ";
 	$req .="(SELECT COUNT(new_surveillances_surveillance_question.ID)  ";
 	$req .="FROM new_surveillances_surveillance_question  ";
 	$req .="WHERE new_surveillances_surveillance_question.ID_Surveillance = "; 
 	$req .="new_surveillances_surveillance.ID ";
 	$req .="AND (new_surveillances_surveillance_question.Etat = 'C' ";
	$req .="     OR new_surveillances_surveillance_question.Etat = 'NC'))) AS note ";
	$req .="FROM new_surveillances_surveillance ";
	$req .= "WHERE ";
	$req .= "YEAR(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)) =".$annee." AND (";
	foreach($prestations as $prestation){
		$req .= "new_surveillances_surveillance.ID_Prestation=".substr($prestation,strrpos($prestation,"_")+1)." OR ";
	}
	$req = substr($req,0,-3);
	$req .= ") AND ( ";
	if ($generiques <> ""){
		foreach($generiques as $generique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($generique,strrpos($generique,"_")+1)." OR ";
		}
	}
	if ($specifiques <> ""){
		foreach($specifiques as $specifique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($specifique,strrpos($specifique,"_")+1)." OR ";
		}
	}
	$req = substr($req,0,-3);
	$req .= ") ";
	$req .=") AS tab ";
	$req .="GROUP BY tab.MoisAnnee ";
	return $req;
}

/* Moyenne note / Plateforme */
function reqMoyenneNotePlateforme($prestations,$annee,$generiques,$specifiques){
 	$req ="SELECT   ";
 	$req .="tab.Id_Plateforme, ROUND(AVG(note)*100,1) AS Note  ";
 	$req .="FROM(SELECT new_surveillances_surveillance.ID,  ";
 	$req .="new_competences_prestation.Id_Plateforme,  ";
 	$req .="(SELECT COUNT(new_surveillances_surveillance_question.ID)  ";
 	$req .=" FROM new_surveillances_surveillance_question  ";
 	$req .=" WHERE new_surveillances_surveillance_question.ID_Surveillance =  ";
 	$req .=" new_surveillances_surveillance.ID  ";
 	$req .=" AND new_surveillances_surveillance_question.Etat = 'C') /  ";
 	$req .=" (SELECT COUNT(new_surveillances_surveillance_question.ID)   ";
 	$req .=" FROM new_surveillances_surveillance_question   ";
 	$req .=" WHERE new_surveillances_surveillance_question.ID_Surveillance =   ";
 	$req .=" new_surveillances_surveillance.ID  ";
 	$req .=" AND (new_surveillances_surveillance_question.Etat = 'C'  ";
 	$req .="     OR new_surveillances_surveillance_question.Etat = 'NC')) AS note  ";
 	$req .="FROM new_surveillances_surveillance LEFT JOIN new_competences_prestation  ";
 	$req .="ON new_surveillances_surveillance.ID_Prestation = new_competences_prestation.Id ";
	$req .= "WHERE ";
	$req .= "YEAR(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)) =".$annee." AND (";
	foreach($prestations as $prestation){
		$req .= "new_surveillances_surveillance.ID_Prestation=".substr($prestation,strrpos($prestation,"_")+1)." OR ";
	}
	$req = substr($req,0,-3);
	$req .= ") AND ( ";
	if ($generiques <> ""){
		foreach($generiques as $generique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($generique,strrpos($generique,"_")+1)." OR ";
		}
	}
	if ($specifiques <> ""){
		foreach($specifiques as $specifique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($specifique,strrpos($specifique,"_")+1)." OR ";
		}
	}
	$req = substr($req,0,-3);
	$req .= ") ";
	$req .=") AS tab  ";
 	$req .="GROUP BY tab.Id_Plateforme ";
	return $req;
}

/* Moyenne note / Prestation */
function reqMoyenneNotePrestation($prestations,$annee,$generiques,$specifiques){
	$req = "SELECT "; 
	$req .= "tab.Id_Prestation, ROUND(AVG(note)*100,1) AS Note ";
	$req .= "FROM(SELECT new_surveillances_surveillance.ID, ";
	$req .= "new_surveillances_surveillance.Id_Prestation, ";
	$req .= "new_competences_prestation.Id_Plateforme,  ";
	$req .= "(SELECT COUNT(new_surveillances_surveillance_question.ID)  ";
	$req .= " FROM new_surveillances_surveillance_question "; 
	$req .= " WHERE new_surveillances_surveillance_question.ID_Surveillance = "; 
	$req .= " new_surveillances_surveillance.ID ";
	$req .= " AND new_surveillances_surveillance_question.Etat = 'C') / ";
	$req .= " (SELECT COUNT(new_surveillances_surveillance_question.ID) "; 
	$req .= " FROM new_surveillances_surveillance_question  ";
	$req .= " WHERE new_surveillances_surveillance_question.ID_Surveillance =  ";
	$req .= " new_surveillances_surveillance.ID ";
	$req .= " AND (new_surveillances_surveillance_question.Etat = 'C' ";
	$req .= "     OR new_surveillances_surveillance_question.Etat = 'NC')) AS note ";
	$req .= "FROM new_surveillances_surveillance LEFT JOIN new_competences_prestation ";
	$req .= "ON new_surveillances_surveillance.ID_Prestation = new_competences_prestation.Id ";
	$req .= "WHERE ";
	$req .= "YEAR(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)) =".$annee." AND (";
	foreach($prestations as $prestation){
		$req .= "new_surveillances_surveillance.ID_Prestation=".substr($prestation,strrpos($prestation,"_")+1)." OR ";
	}
	$req = substr($req,0,-3);
	$req .= ") AND ( ";
	if ($generiques <> ""){
		foreach($generiques as $generique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($generique,strrpos($generique,"_")+1)." OR ";
		}
	}
	if ($specifiques <> ""){
		foreach($specifiques as $specifique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($specifique,strrpos($specifique,"_")+1)." OR ";
		}
	}
	$req = substr($req,0,-3);
	$req .= ") ";
	$req .= ") AS tab ";
	$req .= "GROUP BY tab.Id_Prestation ";
	return $req;
}

/* Moyenne note / Thematique */
function reqMoyenneNoteThematique($prestations,$annee,$generiques,$specifiques){
	$req ="SELECT ";
	$req .= "tab.ID_Questionnaire, ROUND(AVG(note)*100,1) AS Note,tab.Nom ";
	$req .= "FROM(SELECT new_surveillances_surveillance.ID, ";
	$req .= "new_surveillances_questionnaire.Nom, ";
	$req .= "new_surveillances_questionnaire.ID_Theme, ";
	$req .= "new_surveillances_surveillance.ID_Questionnaire, ";
	$req .= "(SELECT COUNT(new_surveillances_surveillance_question.ID)  ";
	$req .= " FROM new_surveillances_surveillance_question  ";
	$req .= " WHERE new_surveillances_surveillance_question.ID_Surveillance =  ";
	$req .= " new_surveillances_surveillance.ID ";
	$req .= " AND new_surveillances_surveillance_question.Etat = 'C') / ";
	$req .= " (SELECT COUNT(new_surveillances_surveillance_question.ID)  ";
	$req .= " FROM new_surveillances_surveillance_question  ";
	$req .= " WHERE new_surveillances_surveillance_question.ID_Surveillance =  ";
	$req .= " new_surveillances_surveillance.ID ";
	$req .= " AND (new_surveillances_surveillance_question.Etat = 'C' ";
	$req .= "     OR new_surveillances_surveillance_question.Etat = 'NC')) AS note ";
	$req .= "FROM new_surveillances_surveillance LEFT JOIN new_surveillances_questionnaire  ";
	$req .= "ON new_surveillances_surveillance.ID_Questionnaire = new_surveillances_questionnaire.ID ";
	$req .= "WHERE ";
	$req .= "YEAR(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)) =".$annee." AND (";
	foreach($prestations as $prestation){
		$req .= "new_surveillances_surveillance.ID_Prestation=".substr($prestation,strrpos($prestation,"_")+1)." OR ";
	}
	$req = substr($req,0,-3);
	$req .= ") AND ( ";
	if ($generiques <> ""){
		foreach($generiques as $generique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($generique,strrpos($generique,"_")+1)." OR ";
		}
	}
	if ($specifiques <> ""){
		foreach($specifiques as $specifique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($specifique,strrpos($specifique,"_")+1)." OR ";
		}
	}
	$req = substr($req,0,-3);
	$req .= ") ";
	$req .= ") AS tab ";
	$req .= "GROUP BY tab.ID_Questionnaire ";
	
	return $req;
}

/* ---------------N° des questions ---------------*/
/* N° des questions NC/NA / Mois */
function reqNumQuestion($prestations,$annee,$reponse,$generiques,$specifiques){
	$req ="SELECT ";
	$req .="new_surveillances_question.ID_Questionnaire,
			new_surveillances_question.Numero, ";
	$req .="(SELECT new_surveillances_questionnaire.Nom FROM new_surveillances_questionnaire WHERE new_surveillances_questionnaire.ID=new_surveillances_question.ID_Questionnaire) AS Questionnaire, ";
	$req .="(SELECT (SELECT new_surveillances_theme.Nom FROM new_surveillances_theme WHERE new_surveillances_theme.ID =new_surveillances_questionnaire.ID_Theme) AS Theme FROM new_surveillances_questionnaire WHERE new_surveillances_questionnaire.ID=new_surveillances_question.ID_Questionnaire) AS Theme, ";
	$req .="COUNT(new_surveillances_surveillance_question.ID) AS NbQuestion ";
	$req .="FROM ((new_surveillances_surveillance_question ";
	$req .="LEFT JOIN new_surveillances_question ";  
	$req .="ON new_surveillances_question.ID = new_surveillances_surveillance_question.ID_Question) ";
	$req .="LEFT JOIN new_surveillances_surveillance ";
	$req .="ON new_surveillances_surveillance_question.ID_Surveillance = new_surveillances_surveillance.ID) ";
	$req .="WHERE new_surveillances_surveillance_question.Etat = '".$reponse."' AND ";
	$req .= "YEAR(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)) =".$annee." AND (";
	foreach($prestations as $prestation){
		$req .= "new_surveillances_surveillance.ID_Prestation=".substr($prestation,strrpos($prestation,"_")+1)." OR ";
	}
	$req = substr($req,0,-3);
	$req .= ") AND ( ";
	if ($generiques <> ""){
		foreach($generiques as $generique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($generique,strrpos($generique,"_")+1)." OR ";
		}
	}
	if ($specifiques <> ""){
		foreach($specifiques as $specifique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($specifique,strrpos($specifique,"_")+1)." OR ";
		}
	}
	$req = substr($req,0,-3);
	$req .= ") ";
	$req .="GROUP BY new_surveillances_surveillance.ID_Questionnaire,new_surveillances_question.Numero ";
	$req .="ORDER BY NbQuestion DESC, new_surveillances_question.Numero ASC ";
	return $req;
}

/* ---------------DELTA DATE PLANIFIEE - REALISEE ---------------*/
/* DELTA DATE PLANIFIEE - REALISEE / Mois */
function reqDELTADatePlaReaMois($prestations,$annee,$generiques,$specifiques){
	$req = "SELECT new_surveillances_surveillance.ID, ";
	$req .="CONCAT( ";
	$req .="YEAR(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)), "; 
	$req .="'_', ";
	$req .="IF(MONTH(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif))>=10, ";
	$req .="MONTH(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)), ";
	$req .="CONCAT('0',MONTH(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif))) ";
	$req .=") ";
	$req .=") AS MoisAnnee, ";
	$req .="IF(new_surveillances_surveillance.DatePlanif <='0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS Date_Debut, ";
	$req .="IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS Date_Fin ";
	$req .="FROM new_surveillances_surveillance ";
	$req .="WHERE (new_surveillances_surveillance.Etat = 'Réalisé' OR new_surveillances_surveillance.Etat = 'Clôturé') AND ";
	$req .= "YEAR(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)) =".$annee." AND (";
	foreach($prestations as $prestation){
		$req .= "new_surveillances_surveillance.ID_Prestation=".substr($prestation,strrpos($prestation,"_")+1)." OR ";
	}
	$req = substr($req,0,-3);
	$req .= ") AND ( ";
	if ($generiques <> ""){
		foreach($generiques as $generique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($generique,strrpos($generique,"_")+1)." OR ";
		}
	}
	if ($specifiques <> ""){
		foreach($specifiques as $specifique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($specifique,strrpos($specifique,"_")+1)." OR ";
		}
	}
	$req = substr($req,0,-3);
	$req .= ") ";
	$req .= "ORDER BY MoisAnnee ASC ";
	return $req;
}
/* DELTA DATE PLANIFIEE - REALISEE / Thematique */
function reqDELTADatePlaReaTheme($prestations,$annee,$generiques,$specifiques){
	$req = "SELECT new_surveillances_surveillance.ID, ";
	$req .= "new_surveillances_questionnaire.Nom, ";
	$req .= "new_surveillances_surveillance.ID_Questionnaire, ";
	$req .="IF(new_surveillances_surveillance.DatePlanif <='0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS Date_Debut, ";
	$req .= "IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS Date_Fin ";
	$req .= "FROM new_surveillances_surveillance LEFT JOIN new_surveillances_questionnaire "; 
	$req .= "ON new_surveillances_surveillance.ID_Questionnaire = new_surveillances_questionnaire.ID ";
	$req .= "WHERE (new_surveillances_surveillance.Etat = 'Réalisé' OR new_surveillances_surveillance.Etat = 'Clôturé') AND ";
	$req .= "YEAR(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)) =".$annee." AND (";
	foreach($prestations as $prestation){
		$req .= "new_surveillances_surveillance.ID_Prestation=".substr($prestation,strrpos($prestation,"_")+1)." OR ";
	}
	$req = substr($req,0,-3);
	$req .= ") AND ( ";
	if ($generiques <> ""){
		foreach($generiques as $generique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($generique,strrpos($generique,"_")+1)." OR ";
		}
	}
	if ($specifiques <> ""){
		foreach($specifiques as $specifique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($specifique,strrpos($specifique,"_")+1)." OR ";
		}
	}
	$req = substr($req,0,-3);
	$req .= ") ";
	return $req;
}

/* DELTA DATE PLANIFIEE - REALISEE / Plateforme */
function reqDELTADatePlaReaPlateforme($prestations,$annee,$generiques,$specifiques){
	$req = "SELECT new_surveillances_surveillance.ID, ";
	$req .= "new_competences_prestation.Id_Plateforme, ";
	$req .= "(SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id = new_competences_prestation.Id_Plateforme) AS Plateforme, "; 
	$req .="IF(new_surveillances_surveillance.DatePlanif <='0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS Date_Debut, ";
	$req .= "IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS Date_Fin ";
	$req .= "FROM new_surveillances_surveillance LEFT JOIN new_competences_prestation ";
	$req .= "ON new_surveillances_surveillance.ID_Prestation = new_competences_prestation.Id ";
	$req .= "WHERE (new_surveillances_surveillance.Etat = 'Réalisé' OR new_surveillances_surveillance.Etat = 'Clôturé') AND ";
	$req .= "YEAR(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)) =".$annee." AND (";
	foreach($prestations as $prestation){
		$req .= "new_surveillances_surveillance.ID_Prestation=".substr($prestation,strrpos($prestation,"_")+1)." OR ";
	}
	$req = substr($req,0,-3);
	$req .= ") AND ( ";
	if ($generiques <> ""){
		foreach($generiques as $generique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($generique,strrpos($generique,"_")+1)." OR ";
		}
	}
	if ($specifiques <> ""){
		foreach($specifiques as $specifique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($specifique,strrpos($specifique,"_")+1)." OR ";
		}
	}
	$req = substr($req,0,-3);
	$req .= ") ";
	return $req;
}

/* DELTA DATE PLANIFIEE - REALISEE / Prestation */
function reqDELTADatePlaReaPrestation($prestations,$annee,$generiques,$specifiques){
	$req = "SELECT new_surveillances_surveillance.ID, ";
	$req .= "new_surveillances_surveillance.Id_Prestation, ";
	$req .= "new_competences_prestation.Libelle, ";
	$req .= "new_competences_prestation.Id_Plateforme, "; 
	$req .="IF(new_surveillances_surveillance.DatePlanif <='0001-01-01', new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS Date_Debut, ";
	$req .= "IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS Date_Fin ";
	$req .= "FROM new_surveillances_surveillance LEFT JOIN new_competences_prestation ";
	$req .= "ON new_surveillances_surveillance.ID_Prestation = new_competences_prestation.Id ";
	$req .= "WHERE (new_surveillances_surveillance.Etat = 'Réalisé' OR new_surveillances_surveillance.Etat = 'Clôturé') AND ";
	$req .= "YEAR(IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif)) =".$annee." AND (";
	foreach($prestations as $prestation){
		$req .= "new_surveillances_surveillance.ID_Prestation=".substr($prestation,strrpos($prestation,"_")+1)." OR ";
	}
	$req = substr($req,0,-3);
	$req .= ") AND ( ";
	if ($generiques <> ""){
		foreach($generiques as $generique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($generique,strrpos($generique,"_")+1)." OR ";
		}
	}
	if ($specifiques <> ""){
		foreach($specifiques as $specifique){
			$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($specifique,strrpos($specifique,"_")+1)." OR ";
		}
	}
	$req = substr($req,0,-3);
	$req .= ") ";
	return $req;
}

/* ---------------DELTA DATE PLANIFIEE - CLOTUREE ---------------*/
/* DELTA DATE PLANIFIEE - CLOTUREE / Mois */
function reqDELTADateReaCloMois($prestations,$annee,$generiques,$specifiques)
{
	$req = "SELECT new_surveillances_surveillance.ID, ";
	$req .="CONCAT( ";
	$req .="YEAR(new_surveillances_surveillance.DateCloture), "; 
	$req .="'_', ";
	$req .="IF(MONTH(new_surveillances_surveillance.DateCloture)>=10, ";
	$req .="MONTH(new_surveillances_surveillance.DateCloture), ";
	$req .="CONCAT('0',MONTH(new_surveillances_surveillance.DateCloture)) ";
	$req .=") ";
	$req .=") AS MoisAnnee, ";
	$req .="IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS Date_Debut, ";
	$req .="new_surveillances_surveillance.DateCloture AS Date_Fin ";
	$req .="FROM new_surveillances_surveillance ";
	$req .="WHERE new_surveillances_surveillance.Etat = 'Clôturé' AND ";
	$req .= "YEAR(new_surveillances_surveillance.DateCloture) =".$annee." AND (";
	foreach($prestations as $prestation){$req .= "new_surveillances_surveillance.ID_Prestation=".substr($prestation,strrpos($prestation,"_")+1)." OR ";}
	$req = substr($req,0,-3);
	$req .= ") AND ( ";
	if ($generiques <> "")
	{
		foreach($generiques as $generique){$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($generique,strrpos($generique,"_")+1)." OR ";}
	}
	if ($specifiques <> "")
	{
		foreach($specifiques as $specifique){$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($specifique,strrpos($specifique,"_")+1)." OR ";}
	}
	$req = substr($req,0,-3);
	$req .= ") ";

	return $req;
}

/* DELTA DATE PLANIFIEE - CLOTUREE / Plateforme */
function reqDELTADateReaCloPlateforme($prestations,$annee,$generiques,$specifiques)
{
	$req = "SELECT new_surveillances_surveillance.ID, ";
	$req .="new_competences_prestation.Id_Plateforme, ";
	$req .="IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS Date_Debut, ";
	$req .="new_surveillances_surveillance.DateCloture AS Date_Fin ";
	$req .="FROM new_surveillances_surveillance LEFT JOIN new_competences_prestation ";
	$req .="ON new_surveillances_surveillance.ID_Prestation = new_competences_prestation.Id ";
	$req .="WHERE new_surveillances_surveillance.Etat = 'Clôturé' AND ";
	$req .= "YEAR(new_surveillances_surveillance.DateCloture) =".$annee." AND (";
	foreach($prestations as $prestation){$req .= "new_surveillances_surveillance.ID_Prestation=".substr($prestation,strrpos($prestation,"_")+1)." OR ";}
	$req = substr($req,0,-3);
	$req .= ") AND ( ";
	if ($generiques <> "")
	{
		foreach($generiques as $generique){$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($generique,strrpos($generique,"_")+1)." OR ";}
	}
	if ($specifiques <> "")
	{
		foreach($specifiques as $specifique){$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($specifique,strrpos($specifique,"_")+1)." OR ";}
	}
	$req = substr($req,0,-3);
	$req .= ") ";

	return $req;
}

/* DELTA DATE PLANIFIEE - CLOTUREE / Prestation */
function reqDELTADateReaCloPrestation($prestations,$annee,$generiques,$specifiques)
{
	$req = "SELECT new_surveillances_surveillance.ID, ";
	$req .="new_surveillances_surveillance.ID_Prestation, ";
	$req .="IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS Date_Debut, ";
	$req .="new_surveillances_surveillance.DateCloture AS Date_Fin ";
	$req .="FROM new_surveillances_surveillance ";
	$req .="WHERE new_surveillances_surveillance.Etat = 'Clôturé' AND ";
	$req .= "YEAR(new_surveillances_surveillance.DateCloture) =".$annee." AND (";
	foreach($prestations as $prestation){$req .= "new_surveillances_surveillance.ID_Prestation=".substr($prestation,strrpos($prestation,"_")+1)." OR ";}
	$req = substr($req,0,-3);
	$req .= ") AND ( ";
	if ($generiques <> "")
	{
		foreach($generiques as $generique){$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($generique,strrpos($generique,"_")+1)." OR ";}
	}
	if ($specifiques <> "")
	{
		foreach($specifiques as $specifique){$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($specifique,strrpos($specifique,"_")+1)." OR ";}
	}
	$req = substr($req,0,-3);
	$req .= ") ";

	return $req;
}

/* DELTA DATE PLANIFIEE - CLOTUREE / Theme */
function reqDELTADateReaCloTheme($prestations,$annee,$generiques,$specifiques)
{
	$req = "SELECT new_surveillances_surveillance.ID, ";
	$req .="new_surveillances_surveillance.ID_Questionnaire, ";
	$req .="IF(new_surveillances_surveillance.DateReplanif >0, new_surveillances_surveillance.DateReplanif, new_surveillances_surveillance.DatePlanif) AS Date_Debut, ";
	$req .="new_surveillances_surveillance.DateCloture AS Date_Fin ";
	$req .="FROM new_surveillances_surveillance ";
	$req .="WHERE new_surveillances_surveillance.Etat = 'Clôturé' AND ";
	$req .= "YEAR(new_surveillances_surveillance.DateCloture) =".$annee." AND (";
	foreach($prestations as $prestation){$req .= "new_surveillances_surveillance.ID_Prestation=".substr($prestation,strrpos($prestation,"_")+1)." OR ";}
	$req = substr($req,0,-3);
	$req .= ") AND ( ";
	if ($generiques <> "")
	{
		foreach($generiques as $generique){$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($generique,strrpos($generique,"_")+1)." OR ";}
	}
	if ($specifiques <> "")
	{
		foreach($specifiques as $specifique){$req .= "new_surveillances_surveillance.ID_Questionnaire=".substr($specifique,strrpos($specifique,"_")+1)." OR ";}
	}
	$req = substr($req,0,-3);
	$req .= ") ";

	return $req;
}
?>
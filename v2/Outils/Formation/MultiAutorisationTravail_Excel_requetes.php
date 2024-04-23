<?php
function getResponsablePlateforme($Id)
{
	return "SELECT new_rh_etatcivil.Id, Nom, Prenom
	FROM new_competences_personne_poste_plateforme
	LEFT JOIN new_rh_etatcivil
	ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
	WHERE Id_Poste=25
	AND Backup=0
	AND Id_Plateforme IN (
	SELECT Id_Plateforme
	FROM new_competences_personne_plateforme
	WHERE Id_Personne=".$Id."
	) ";
}

function getListeAutorisationsDeTravail($Id)
{
	return "SELECT *
		FROM (
		SELECT DISTINCT new_competences_relation.Id_Qualification_Parrainage,new_competences_relation.Date_Fin,
		(SELECT Libelle FROM new_competences_moyen_categorie
		WHERE new_competences_moyen_categorie.Id=new_competences_qualification_moyen.Id_Moyen_Categorie) AS Categorie,
		(SELECT new_competences_moyen_categorie.Id_Moyen
		FROM new_competences_moyen_categorie
		WHERE new_competences_moyen_categorie.Id=new_competences_qualification_moyen.Id_Moyen_Categorie) AS Id_Moyen,
		(SELECT
			(SELECT Libelle FROM new_competences_moyen
			WHERE new_competences_moyen.Id=new_competences_moyen_categorie.Id_Moyen)
		FROM new_competences_moyen_categorie
		WHERE new_competences_moyen_categorie.Id=new_competences_qualification_moyen.Id_Moyen_Categorie) AS Moyen,(@row_number:=@row_number + 1) AS rnk
		FROM new_competences_relation
		LEFT JOIN new_competences_qualification_moyen
		ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification_moyen.Id_Qualification
		LEFT JOIN new_competences_qualification
		ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
		WHERE new_competences_qualification_moyen.Suppr=0
		AND new_competences_qualification_moyen.Suppr=0
		AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
		AND Date_Debut>'0001-01-01'
		AND new_competences_relation.Evaluation NOT IN ('B','')
		AND new_competences_relation.Id_Personne=".$Id." 
		AND (
			new_competences_qualification_moyen.Id_Moyen_Categorie NOT IN (1,2)
			OR (
			new_competences_qualification_moyen.Id_Moyen_Categorie IN (1,2)
			AND 
			((SELECT COUNT(Id)
			FROM new_competences_relation
			WHERE Suppr=0
			AND Evaluation NOT IN ('B','')
			AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
			AND Date_Debut>'0001-01-01' 
			AND Id_Personne=".$Id."
			AND Id_Qualification_Parrainage=75)>0
			
			AND (SELECT COUNT(Id)
			FROM new_competences_relation
			WHERE Suppr=0
			AND Evaluation NOT IN ('B','')
			AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
			AND Date_Debut>'0001-01-01' 
			AND Id_Personne=".$Id."
			AND Id_Qualification_Parrainage=12)>0
			
			AND (SELECT COUNT(Id)
			FROM new_competences_relation
			WHERE Suppr=0
			AND Evaluation NOT IN ('B','')
			AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
			AND Date_Debut>'0001-01-01' 
			AND Id_Personne=".$Id."
			AND Id_Qualification_Parrainage=13)>0
			
			AND (SELECT COUNT(Id)
			FROM new_competences_relation
			WHERE Suppr=0
			AND Evaluation NOT IN ('B','')
			AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
			AND Date_Debut>'0001-01-01' 
			AND Id_Personne=".$Id."
			AND Id_Qualification_Parrainage=133)>0)
			OR
			(
				((SELECT COUNT(Tab2.Id)
				FROM new_competences_relation AS Tab2
				WHERE Tab2.Suppr=0
				AND Tab2.Evaluation NOT IN ('B','')
				AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
				AND Tab2.Date_Debut>'0001-01-01' 
				AND Tab2.Id_Personne=".$Id."
				AND Tab2.Id_Qualification_Parrainage=75)=0
				
				AND 
				(SELECT COUNT(Tab2.Id)
				FROM new_competences_relation AS Tab2
				LEFT JOIN new_competences_qualification AS Tab3
				ON Tab2.Id_Qualification_Parrainage=Tab3.Id
				WHERE Tab2.Suppr=0
				AND Tab2.Evaluation NOT IN ('B','')
				AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
				AND Tab2.Date_Debut>'0001-01-01' 
				AND Tab2.Id_Personne=".$Id."
				AND Tab2.Id_Qualification_Parrainage=12)=0
				
				AND 
				(SELECT COUNT(Tab2.Id)
				FROM new_competences_relation AS Tab2
				LEFT JOIN new_competences_qualification AS Tab3
				ON Tab2.Id_Qualification_Parrainage=Tab3.Id
				WHERE Tab2.Suppr=0
				AND Tab2.Evaluation NOT IN ('B','')
				AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
				AND Tab2.Date_Debut>'0001-01-01' 
				AND Tab2.Id_Personne=".$Id."
				AND Tab2.Id_Qualification_Parrainage=13)=0
				
				AND 
				(SELECT COUNT(Tab2.Id)
				FROM new_competences_relation AS Tab2
				LEFT JOIN new_competences_qualification AS Tab3
				ON Tab2.Id_Qualification_Parrainage=Tab3.Id
				WHERE Tab2.Suppr=0
				AND Tab2.Evaluation NOT IN ('B','')
				AND (Tab2.Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite FROM new_competences_qualification WHERE new_competences_qualification.Id=Tab2.Id_Qualification_Parrainage LIMIT 1)=0)
				AND Tab2.Date_Debut>'0001-01-01' 
				AND Tab2.Id_Personne=".$Id."
				AND Tab2.Id_Qualification_Parrainage=133)=0)
			)
			OR 

				new_competences_relation.Id_Qualification_Parrainage IN (1606,1607,2130,1683,2490,2145)
			)
		)

		ORDER BY Moyen, Categorie, Date_Fin DESC
		) AS TAB 
		GROUP BY Moyen,Categorie
		
		";
}

function getMiseAJourDesAutorisationsDeTravail($Id)
{
	return "UPDATE new_competences_relation
									SET new_competences_relation.DateEditionAutorisationTravail='".date('Y-m-d')."'
									WHERE (Date_Fin>='".date('Y-m-d')."' OR (SELECT Duree_Validite 
													FROM new_competences_qualification 
													WHERE new_competences_qualification.Id=new_competences_relation.Id_Qualification_Parrainage)=0)
									AND Date_Debut>'0001-01-01'
									AND new_competences_relation.Evaluation NOT IN ('B','')
									AND new_competences_relation.DateEditionAutorisationTravail<='0001-01-01'
									AND new_competences_relation.Id_Personne=".$Id." ";
}
?>
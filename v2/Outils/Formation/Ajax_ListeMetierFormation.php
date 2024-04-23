<?php
session_start();
require("../Connexioni.php");
require_once("Globales_Fonctions.php");
require("../Fonctions.php");

//Recherche du libellé de la formation au lieu de la référence
//############################################################
$ReqParametresFormations="
	SELECT
		Id_Formation,
		Id_Langue,
		(SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS ORGANISME,
		Id_Plateforme
	FROM
		form_formation_plateforme_parametres";
$ResultParametresFormations=mysqli_query($bdd,$ReqParametresFormations);
$NbParametresFormations=mysqli_num_rows($ResultParametresFormations);

$ReqInfosFormations="
	SELECT
		Id_Formation,
		Id_Langue,
		Libelle,
		(SELECT Libelle FROM form_langue WHERE Id=Id_Langue) AS LANGUE
	FROM
		form_formation_langue_infos
	WHERE
		Suppr=0
	ORDER BY
		LANGUE";
$ResultInfosFormations=mysqli_query($bdd,$ReqInfosFormations);
$NbInfosFormations=mysqli_num_rows($ResultInfosFormations);

$rqMetierFormation="SELECT
		form_prestation_metier_formation.Id,
		form_prestation_metier_formation.Id_Prestation,
		form_prestation_metier_formation.Id_Pole,
		(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_prestation_metier_formation.Id_Prestation) AS Id_Plateforme,
		(SELECT Libelle FROM new_competences_metier WHERE Id=form_prestation_metier_formation.Id_Metier) AS Metier,
		(SELECT Reference FROM form_formation WHERE Id=form_prestation_metier_formation.Id_Formation LIMIT 1) AS REFERENCE_FORMATION,
		form_prestation_metier_formation.Obligatoire,
		form_prestation_metier_formation.Id_Metier,
		form_prestation_metier_formation.Id_Formation,
		(
		SELECT form_formation_plateforme_parametres.BesoinParametrableUniquementAF
		FROM form_formation_plateforme_parametres
		WHERE form_formation_plateforme_parametres.Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_prestation_metier_formation.Id_Prestation)
		AND form_formation_plateforme_parametres.Id_Formation=form_prestation_metier_formation.Id_Formation
		AND form_formation_plateforme_parametres.Suppr=0 LIMIT 1) BesoinParametrableUniquementAF,
		(SELECT COUNT(form_session_date.Id)
			FROM form_session_date,
			form_session
			WHERE form_session_date.Id_Session=form_session.Id
			AND form_session.Id_Formation=form_prestation_metier_formation.Id_Formation
			AND form_session_date.Suppr=0
			AND form_session_date.Id_Session IN (
				SELECT form_session_prestation.Id_Session
				FROM form_session_prestation
				WHERE form_session_prestation.Suppr=0 
				AND form_session_prestation.Id_Prestation=form_prestation_metier_formation.Id_Prestation
			)
			AND form_session.Suppr=0
			AND form_session.Annule=0
			AND (form_session_date.DateSession>='".date('Y-m-d')."'
			AND (SELECT COUNT(form_session_personne.Id)
				FROM form_session_personne
				LEFT JOIN form_besoin
				ON form_session_personne.Id_Besoin=form_besoin.Id
				WHERE form_session_personne.Suppr=0 
				AND form_session_personne.Id_Session=form_session.Id 
				AND form_session_personne.Validation_Inscription<>-1
				AND (SELECT COUNT(new_competences_personne_metier.Id) 
					FROM new_competences_personne_metier
					WHERE Id_Personne=form_besoin.Id_Personne
					AND Id_Metier=form_prestation_metier_formation.Id_Metier)>0
				AND form_besoin.Suppr=0                                                        
				AND form_besoin.Traite<3
				AND form_besoin.Id_Prestation=form_prestation_metier_formation.Id_Prestation
				AND form_besoin.Id_Pole=form_prestation_metier_formation.Id_Pole
				)>0
			)
		) AS NbSession
	FROM form_prestation_metier_formation,form_formation_langue_infos
	WHERE form_prestation_metier_formation.Suppr=0 
	AND form_formation_langue_infos.Id_Formation = form_prestation_metier_formation.Id_Formation
	AND CONCAT(form_prestation_metier_formation.Id_Prestation,'_',form_prestation_metier_formation.Id_Pole)='".$_GET['Id_PrestationPole']."'
	AND form_formation_langue_infos.Suppr = 0 
	AND form_formation_langue_infos.Id_Langue = (
		SELECT form_formation_plateforme_parametres.Id_Langue
		FROM form_formation_plateforme_parametres
		WHERE form_formation_plateforme_parametres.Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_prestation_metier_formation.Id_Prestation)
		AND form_formation_plateforme_parametres.Id_Formation=form_prestation_metier_formation.Id_Formation
		AND form_formation_plateforme_parametres.Suppr=0)
	ORDER BY Metier ASC, form_formation_langue_infos.Libelle ASC";
$resultMetierFormation=mysqli_query($bdd,$rqMetierFormation);

$i=0;

$valeur= "<tableau>";

while($rowMetierFormation=mysqli_fetch_array($resultMetierFormation)){
$Organisme="";
$Id_Langue=1;
if($NbParametresFormations>0)
{
mysqli_data_seek($ResultParametresFormations,0);
while($RowParametresFormations=mysqli_fetch_array($ResultParametresFormations))
{
if($RowParametresFormations['Id_Formation']==$rowMetierFormation['Id_Formation'] && $RowParametresFormations['Id_Plateforme']==$rowMetierFormation['Id_Plateforme'])
{
if($RowParametresFormations['ORGANISME']!=NULL)
{
	$Organisme=" (".addslashes($RowParametresFormations['ORGANISME']).")";
}
$Id_Langue=$RowParametresFormations['Id_Langue'];
break;
}
}
}


$LibelleFormation="";
if($NbInfosFormations>0)
{
mysqli_data_seek($ResultInfosFormations,0);
while($RowInfosFormations=mysqli_fetch_array($ResultInfosFormations))
{
if($RowInfosFormations['Id_Formation']==$rowMetierFormation['Id_Formation'] && $RowInfosFormations['Id_Langue']==$Id_Langue)
{
$LibelleFormation=$RowInfosFormations['Libelle'];
break;
}
}
}
if($Organisme<>""){$LibelleFormation.=" ".$Organisme;}

$nbSession=$rowMetierFormation['NbSession'];
$valeur.= '<tab>'.$rowMetierFormation['Id'].'<separe>"'.$rowMetierFormation['Id_Prestation'].'<separe>"'.$rowMetierFormation['Id_Pole'].'"<separe>"'.stripslashes($rowMetierFormation['Metier']).'"<separe>"'.addslashes($rowMetierFormation['REFERENCE_FORMATION']).'"<separe>'.$rowMetierFormation['Obligatoire'].'<separe>'.$rowMetierFormation['Id_Metier'].'<separe>'.$rowMetierFormation['Id_Formation'].'<separe>"'.addslashes($LibelleFormation).$Organisme.'"<separe>"'.$nbSession.'"<separe>"'.$rowMetierFormation['BesoinParametrableUniquementAF'].'"';

$i++;
}

$valeur.= "</tableau>";
echo $valeur;
?>
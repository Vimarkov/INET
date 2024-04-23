<?php
/**
 * WorkflowDesSurveillances_fonction.php
 * 
 * Ce fichier centralise les fonctions php necessaires au bon 
 * fonctionnement du workflow des surveillances.
 */

require_once("WorkflowDesSurveillances_requetes.php");

/**
 * TableauDeBord_construireListe
 * 
 * Construit le contenu du tableau de bord pour la personne connectee
 * 
 * @param int $Id_Personne L identifiant de la personne connectee
 * @return string Le code HTML du tableau
 */
function TableauDeBord_construireListe($Id_Personne)
{
	$ressource = getRessource(getchaineSQL_ListeTableauDeBord($Id_Personne));
	
	$codeHTML= TableauDeBord_construireEntete();
	while($row = mysqli_fetch_array($ressource))
 		$codeHTML .= TableauDeBord_construireLigne($row['Qualification'], $row['QCM']);
	
	return $codeHTML;
}

/**
 * TableauDeBord_construireLigne
 * 
 * Construit une ligne du tableau de bord
 * 
 * @param string $libelleQualification Le libelle de la qualification
 * @param string $CodeQCM Le code du QCM que la personne va devoir passer
 * @return string Le code html
 */
function TableauDeBord_construireLigne($libelleQualification, $CodeQCM)
{
	$codeHTML =" 	<tr>\n";
	$codeHTML .="			<td>".$libelleQualification."</td>\n";
	$codeHTML .="			<td>".$CodeQCM."</td>\n";
	$codeHTML .="			<td><a href='Consult_QCM.php?popup=0'><img src=\"../../Images/qcm.gif\"></a></td>\n";
	$codeHTML .=" 	</tr>\n";

	return $codeHTML;
}
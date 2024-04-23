<?php
/**
 * Database_fonctions
 * 
 * Regroupe les fonctions d acces aux base de donnees
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */

/**
 * getRessource
 *
 * Execute la requete SQL et retourne l'objet ressource
 *
 * @param string $chaineSQL Requete SQL a executer
 * @return resource L'objet ressource contenant le resultat
 * 
 * @author	Anthony Schricke <aschricke@aaa-aero.com>
 */
function getRessource($chaineSQL) {
	global $bdd;
	$result = mysqli_query($bdd,$chaineSQL);
	if (!$result)
	   die('Requête invalide : '.mysqli_error($bdd));
		
	return $result;
}

/**
 * getArrayFromRessource
 * 
 * Tranforme une ressource mysql en tableau php
 * 
 * @param resource $ressource La ressource SQL
 * @return array
 * 
 * @author	Anthony Schricke <aschricke@aaa-aero.com>
 */
function getArrayFromRessource($ressource) {
    $array = array();    
    while($r = mysqli_fetch_array($ressource))
        array_push($array, $r);

    return $array;
}
?>
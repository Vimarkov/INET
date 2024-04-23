<?php
/**
 *	Interface_WorkflowDesSurveillances.php
 *
 *	Ce fichier est une interface qui permets de g�rer les �changes 
 *	avec les machines clientes.
 *	Le param�tre Action d�termine l'action a faire (cf. le switch 
 *	pour plus de d�tails) et le param�tre Id est l'identifiant du besoin
 *
 *	@package	WorkflowDesSurveillances\Interfaces
 *	@author	Anthony Schricke <aschricke@aaa-aero.com>
 */
namespace WorkflowDesSurveillances\Interfaces;

require("../WorkflowDesSurveillances_requetes.php");
require("../../Connexioni.php");

use \WorkflowDesSurveillances\Bibliotheque as lib;

switch($_GET['Action']) {
	case 1: //Action refuser un besoin
		$maRessource = lib\getRessource(lib\getchaineSQL_BesoinsDeSurveillance_Refuser($_GET['Id']));
		break;
	case 2: //Action valider un besoin
		$maRessource = lib\getRessource(lib\getchaineSQL_BesoinsDeSurveillance_Valider($_GET['Id'], $_GET['CodeQCM']));
		break;
}

?>
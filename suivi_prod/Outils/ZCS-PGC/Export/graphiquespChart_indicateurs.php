<?php
/**
 *  Ce fichier sert a gerer les indicateurs du suivi prod pour AHDO.
 *
 *  C'est ici que l'on doit ecrire la description du fichier
 *  
 *  @global	array $resultsMois
 *  @global array $clients
 *  @global array	$Mois
 *  
 *  @author	Anthony Schricke <aschricke@aaa-aero.com>
 *  @package	OTD
 *  @category	OTD
 */

	$resultsMois;
	$clients;
	$Mois = array("Janv", "Fevr", "Mars", "Avril", "Mai", "Juin", "Juil", "Aout", "Sept", "Oct", "Nov", "Déc");
	$Semaine = array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");
// 	$mode_execution = 'verbose';
   	$mode_execution = 'run';
//	$mode_execution = 'dev';

	/**
	 *  Genere  le graphique pour l'OQD
	 */
	
function graphique_OQD() {
	global $resultsMois;
	
	requetes_OQD();
		
		//[194] - Dessiner le graphique
		$mesDonnees = new pData();
		$mesDonnees->addPoints($resultsMois);
		$mesDonnees->addPoints($Mois, "Mois");
		$mesDonnees->setAbscissa("Mois");
		
		$myPicture = new pImage(700, 460, $mesDonnees);
		$myPicture->setFontProperties(array("FontName"=>"../../../pChart/fonts/Forgotte.ttf", "FontSize"=>11));
		
		$myPicture->setGraphArea(60, 40, 670, 380);
		$AxisBoundaries = array(0=>array("Min"=>0,"Max"=>100));
		
		$scalechart_settings = array(
				"Floating" => false,
				"GridR" => 255,
				"GridG" => 255,
				"GridB" => 255,
				"DrawSubTicks" => true,
				"CycleBackground" => true,
				"Mode" => SCALE_MODE_MANUAL,
				"ManualScale" => $AxisBoundaries
		);
		
		$myPicture->drawScale($scalechart_settings);
		
		//Exercice de modification des couleurs de la série de données
		$rouge = array("R"=>200,"G"=>64,"B"=>64,"Alpha"=>100);
		$orange = array("R"=>200,"G"=>128,"B"=>0,"Alpha"=>100);
		$vert = array("R"=>64,"G"=>200,"B"=>64,"Alpha"=>100);
		
		$empty = array(); //Pour le remplissage automatique du tableau Palette
		$Palette = array_pad($empty, 12, $vert); //On remplit en Vert
		
		
		//Modification du tableau de Palette
		foreach($resultsMois as $key => $value) {
			if($value < 90)
				$Palette[$key-1] = $rouge;
				if($value >= 90 && $value < 95)
					$Palette[$key-1] = $orange;
		}
		
		$myPicture->drawBarChart(array("DisplayPos"=>LABEL_POS_INSIDE,"DisplayValues"=>TRUE,"Rounded"=>TRUE,"Surrounding"=>30,"OverrideColors"=>$Palette));
		
		$myPicture->drawThresholdArea(90, 95, array("R"=>226,"G"=>0,"B"=>0,"Alpha"=>20));
		$myPicture->drawThreshold(90, array("Alpha"=>70));
		$myPicture->drawThreshold(95, array("Alpha"=>70,"R"=>0,"G"=>0,"B"=>0));
		$myPicture->Render("../../../tmp/OQD.png");
		
		genererPageClient_OQD();		
		exit();
}

/**
 * Genere  le graphique pour l'OTD
 * 
 * @param int $cycle	Le cycle de FI que l'utilisateur souhaite observer
 * @param int $mode	mode d'affichage du graphique
 */
function graphique_OTD($IdClient, $cycle, $uniteDeTemps, $numMois, $numSemaine, $dateDebut, $dateFin) {
	global $mode_execution;
	global $resultsMois;
	global $Mois;
	
	$Semaine = array();

	requetes_OTD();
	
	if ($mode_execution == 'dev') {
		echo "nomMois : ".$numMois."<br />\n";
		echo "numSemaine : ".$numSemaine."<br />\n";
	}
	
	switch ($uniteDeTemps) {
		case 1:
			$param= "";
			//Récupère les Id des dossiers concernés
			$date_debut = date('Y')."-01-01";
			$date_fin = date('Y')."-12-31";
			$IdsDossier = requete_OTD_getIdDossierDeLaPeriodeChoisie($date_debut, $date_fin, intval($IdClient));
			break;
			
		case 2:
			$param= $numMois;
			//Récupère les Id des dossiers concernés
			$date_debut = date('Y')."-".$param."-01";
			$date_fin = date('Y')."-".$param."-31";
			$IdsDossier = requete_OTD_getIdDossierDeLaPeriodeChoisie($date_debut, $date_fin, intval($IdClient));
			break;
			
		case 3:
			$param= $numSemaine;
			
			$jourCurseur = new DateTime();
			$jourCurseur->setISOdate(date('Y'), $numSemaine);
			
			//Récupère les Id des dossiers concernés
			$date_debut = $jourCurseur->format('Y-m-d');
			
			for($i = 1; $i <= 7; $i++) {
				$Semaine[$i] = $jourCurseur->format('Y-m-d');
				$jourCurseur->add(new DateInterval('P1D'));
			}
			
			$date_fin = $jourCurseur->format('Y-m-d');
			
			$IdsDossier = requete_OTD_getIdDossierDeLaPeriodeChoisie($date_debut, $date_fin, intval($IdClient));
			break;
			
		case 4: //Vue personalisée
			$date_debut = $dateDebut;
			$date_fin = $dateFin;
			
			$datetimedebut = date_create($date_debut);
			$datetimefin = date_create($date_fin);
			
			//$interval = date_diff($datetimedebut, $datetimefin);
			$interval = date_difference($datetimedebut, $datetimefin);
			$param=  $interval->format("%a"); //nb de jours d'écarts
			
			$IdsDossier = requete_OTD_getIdDossierDeLaPeriodeChoisie($dateDebut, $dateFin, intval($IdClient));
			break;
	}
	
	if ($mode_execution == 'dev') {
		echo "graphique_OTD(".$cycle." ,".$uniteDeTemps." ) <br /> \n";
		echo "params : ".$param."<br />\n";
	}
	
	//Lance les requêtes en fonction du cycle choisit
	switch($cycle) {
		case 1:
			$resultsMois = requete_OTD_mode_ReceptionDossier_LancementPROD($IdsDossier, $uniteDeTemps, $param, $date_debut, $date_fin);
			$TitreGraphique = "OTD : Lead Time Reception dossier - launching PROD";
			break;
			
		case 2:
			$resultsMois = requete_OTD_mode_LancementPROD_TERA($IdsDossier, $uniteDeTemps, $param);
			$TitreGraphique = "OTD : Lead Time launching PROD - TERA";
			break;
			
		case 3:
			$resultsMois = requete_OTD_mode_TERA_TERC($IdsDossier, $uniteDeTemps, $param);
			$TitreGraphique = "OTD : Lead Time TERA - TERC";
			break;
			
		case 4:
			$resultsMois = requete_OTD_mode_LancementPROD_TERC($IdsDossier, $uniteDeTemps, $param);
			$TitreGraphique = "OTD : Lead Time launching PROD - TERC";
			break;
			
		case 5:
			$resultsMois = requete_OTD_mode_ReceptionDossier_TERC($IdsDossier, $uniteDeTemps, $param);
			$TitreGraphique = "OTD : Lead Time Reception dossier - TERC";
			break;
			
		default:
			$TitreGraphique = "Unknown";
			break;
	}
	
	if ($mode_execution == 'dev') {
		echo "Resultat de la série : <br /> \n";
		print_r($resultsMois);
	}
	
	
	//requetes_OTD();
	
	//[194] - Dessiner le graphique
	$mesDonnees = new pData();
	if (count($resultsMois) > 0 )
 		$mesDonnees->addPoints($resultsMois, "My Line");
	else
		echo "Aucun résultat disponible<br />";
	
	$mesDonnees->setPalette("My Line",array("R"=>255,"G"=>0,"B"=>0,"Alpha"=>100));
	
	
	switch ($uniteDeTemps) {
		case 1: // Vue de l'année
			$mesDonnees->addPoints($Mois, "Mois");
			$mesDonnees->setAbscissa("Mois");
			$mesDonnees->setAbscissaName("Mois");
			break;
			
		case 2: //Vue du mois
			$numJour = date("t", strtotime(date('Y')."-".$param."-01"));
			
			$Jours = array();
			for ($i = 1; $i <= $numJour; $i++) {
				$Jours[$i] = $i; 
			}
			
			if ($mode_execution == 'dev') {
				echo "<br /> Parametre : ".$param." <br />\n";
				echo "Nb de jours dans le mois ".$numJour."<br />\n";
				echo "Les abscisses en jours : <br />\n";
				print_r($Jours);
			}
			
			$mesDonnees->addPoints($Jours, "Jours");
			$mesDonnees->setAbscissa("Jours");
			$mesDonnees->setAbscissaName("Jours du Mois (".$param."/".date('Y').")");
			break;
			
		case 3: //Vue de la semaine
			$mesDonnees->addPoints($Semaine, "Jours");
			$mesDonnees->setAbscissa("Jours");
			$mesDonnees->setAbscissaName("Jours de la semaine");
			break;
			
		case 4: //Vue personalisée

			$differenceFormat = "%a";
			
			$datetimedebut = date_create($date_debut);
			$datetimefin = date_create($date_fin);
			
			//$interval = date_diff($datetimedebut, $datetimefin);
			$interval = date_difference($datetimedebut, $datetimefin);
			
			$nbJours =  $interval->format($differenceFormat);
			$numJourDebut = $datetimedebut->format($differenceFormat);
			
			//Récupère les Id des dossiers concernés
			$jourCurseur = $datetimedebut;
			$jourFinal = $datetimefin;
			$jourFinal->add(new DateInterval('P1D'));
			
			$Jours = array();
			for($i = 1; $jourCurseur != $jourFinal; $i++) {
				$Jours[$i] = $jourCurseur->format('d/m');
				$jourCurseur->add(new DateInterval('P1D'));
			}
			
			
			$mesDonnees->addPoints($Jours, "Jours");
			$mesDonnees->setAbscissa("Jours");
			$mesDonnees->setAbscissaName("Jours personalisés du ".$date_debut." au ".$date_fin);
			break;
	}
	
	
	$mesDonnees->setAbscissa("Nb Jours");
	$mesDonnees->setAxisName(0,"Nb Jours");
	
	$myPicture = new pImage(700, 460, $mesDonnees);
	$myPicture->setFontProperties(array("FontName"=>"../../../pChart/fonts/Forgotte.ttf", "FontSize"=>11));
	
	$myPicture->drawText(400, 55, $TitreGraphique, array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));
	
	$myPicture->setGraphArea(60, 40, 670, 380);
	
	if (count($resultsMois) > 0 )
		$AxisBoundaries = array(0=>array("Min"=>0,"Max"=>max($resultsMois)));
	else
		$AxisBoundaries = array(0=>array("Min"=>0,"Max"=>100));
	
	$scalechart_settings = array(
			"Floating" => false,
			"GridR" => 255,
			"GridG" => 255,
			"GridB" => 255,
			"DrawSubTicks" => true,
			"CycleBackground" => true,
			"Mode" => SCALE_MODE_MANUAL,
			"ManualScale" => $AxisBoundaries
	);
	
	$myPicture->drawScale($scalechart_settings);
	
	//Exercice de modification des couleurs de la série de données
	$rouge = array("R"=>200,"G"=>64,"B"=>64,"Alpha"=>100);
	
	$empty = array(); //Pour le remplissage automatique du tableau Palette
	$Palette = array_pad($empty, 12, $rouge); //On remplit en rouge
	
	$SplineSettings = array("R"=>255,"G"=>255,"B"=>255,"ShowControl"=>TRUE);
	
	$myPicture->drawLineChart(array("DisplayColor"=>DISPLAY_MANUAL));
	$myPicture->Render("../../../tmp/OTD.png");
	
	genererPageClient_OTD();
	exit();
}

/**
 *  Genere  le graphique pour l'indicateur productivite
 */

function graphique_Productivite() {
	global $resultsMois;
	$nbSem = 8;
	
	requetes_Productivite();
	
	//[194] - Dessiner le graphique
	$mesDonnees = new pData();
	$mesDonnees->addPoints($resultsMois, "% heures travaillés");
	$mesDonnees->setPalette("% heures travaillés",array("R"=>255,"G"=>0,"B"=>0,"Alpha"=>100));
	$mesDonnees->setSeriePicture("% heures travaillés","..\..\..\Images\bar_chart4.png");
	
	//Vérifie si de dates ont été saisies
	if (isset($_POST['Start']) && isset($_POST['End'])) {
		if ($_POST['Start'] > 0 && $_POST['End'] > 0) {
			$nbSem = (getSemaine($_POST['End'])-getSemaine($_POST['Start']));
			$date_reference = $_POST['End'];
			$semaineEnCours = getSemaine($_POST['End']);	
			
			if ($nbSem > 0)
				$a = array_fill(0, $nbSem, "");
			
			for($i=$nbSem; $i>=1; $i--)
				$a[($nbSem-$i)] = "S".($semaineEnCours-$i);

			$mesDonnees->addPoints($a, "Mois");
		}else {
			$semaineEnCours = date("W");
			$a = array("S".($semaineEnCours-8), "S".($semaineEnCours-7), "S".($semaineEnCours-6), "S".($semaineEnCours-5), "S".($semaineEnCours-4), "S".($semaineEnCours-3), "S".($semaineEnCours-2), "S".($semaineEnCours-1));
			$mesDonnees->addPoints($a, "Mois");
		}	
	}
	
	$mesDonnees->setAbscissa("Mois");
	$mesDonnees->setAbscissa("Nb Heures");
	
	$myPicture = new pImage(900, 460, $mesDonnees);
	$myPicture->setFontProperties(array("FontName"=>"../../../pChart/fonts/Forgotte.ttf", "FontSize"=>11));
	
	$myPicture->setGraphArea(60, 40, 670, 380);
	$AxisBoundaries = array(0=>array("Min"=>0,"Max"=>100));
	
	/* La légende */
	$myPicture->setShadow(TRUE, array("X"=>1, "Y"=>1, "R"=>0, "G"=>0, "B"=>0, "Alpha"=>10));
	$myPicture->drawLegend(700,60, array("Style"=>LEGEND_BOX,"Mode"=>LEGEND_HORIZONTAL, "BoxWidth"=>30,"Family"=>LEGEND_FAMILY_LINE));
	
	$scalechart_settings = array(
			"Floating" => false,
			"GridR" => 255,
			"GridG" => 255,
			"GridB" => 255,
			"DrawSubTicks" => true,
			"CycleBackground" => true,
			"Mode" => SCALE_MODE_MANUAL,
			"ManualScale" => $AxisBoundaries
	);
	
	$myPicture->drawScale($scalechart_settings);
	
	//Exercice de modification des couleurs de la série de données
	$rouge = array("R"=>200,"G"=>64,"B"=>64,"Alpha"=>100);
	$vert = array("R"=>64,"G"=>200,"B"=>64,"Alpha"=>100);
	
	$empty = array(); //Pour le remplissage automatique du tableau Palette
	$Palette = array_pad($empty, $nbSem, $rouge); //On remplit en Vert
	
	//Modification du tableau de Palette
	foreach($resultsMois as $key => $value)
		if($value > 80)
			$Palette[$key] = $vert;
	
	$myPicture->drawBarChart(array("OverrideColors"=>$Palette));
	$myPicture->drawThreshold(80, array("Alpha"=>70,"R"=>0,"G"=>0,"B"=>0));
	$myPicture->Render("../../../tmp/Productivite.png");
	
	genererPageClient_Productivite();
	exit();
}

/**
 *
 *  Execute les requetes
 *
 */

function requetes_OTD() {
	// [193] - requêtes OTD
	global $resultsMois;
	global $clients;
	
	$resultsMois = array();
	
	for ($i = 1; $i <= 12; $i++) {
		$mois=$i;
		$mois1=$i+1;
		
		$req = "SELECT AVG(DATEDIFF(DateCreationQUALITE, DateCreationPROD)) AS OTD ";
		$req .= "FROM `sp_olwficheintervention` "; 
		$req .= "WHERE ";
		$req .= "((Id_StatutQUALITE = 'TERC' AND Id_StatutPROD = 'TERA') ";
		$req .= "OR (Id_StatutQUALITE = 'TERC' AND Id_StatutPROD = 'REWORK')) ";
		$req.="AND DateCreationQUALITE >= '2017-{$mois}-01' ";
		$req.="AND DateCreationQUALITE < '2017-{$mois1}-01'; ";
	
 		$result1=mysqli_query($bdd,$req);
 		
 		if ($result1 == false)
 			echo "Une erreur s'est produite dans la requête SQL !! <br />".$req;

 		while ($line = mysqli_fetch_array($result1, mysqli_ASSOC))
 			$resultsMois[$i] = $line['OTD'];
	}
	
	
	//[193] - Chargement de la liste des clients
	
	$req="SELECT DISTINCT sp_client.Id, sp_client.Libelle \n";
	$req.="FROM \n";
	$req.="sp_olwficheintervention, \n";
	$req.="sp_olwdossier, \n";
	$req.="sp_client \n";
	$req.="WHERE \n";
	$req.="sp_olwficheintervention.Id_Dossier = sp_olwdossier.Id \n";
	$req.="AND sp_olwdossier.Id_Client = sp_client.Id; \n";
	
	$clients=mysqli_query($bdd,$req);
	
	
}

/**
 * Requetes OQD
 */

function requetes_OQD() {
	//[194] - Préparation des requêtes SQL
	
	// [194] - Première requête récupère les dossiers TERC
	global $resultsMois, $clients;
	$resultsMois =  array();
	
	for ($i = 1; $i <= 12; $i++) {
		$mois=$i;
		$mois1=$i+1;
		
		$req="SELECT Id_Dossier ";
		$req.="FROM ";
		$req.="    sp_olwficheintervention, sp_olwdossier ";
		$req.="WHERE ";
		$req.="    Id_StatutQUALITE = 'TERC' ";
		$req.="AND sp_olwdossier.Id = sp_olwficheintervention.Id_Dossier ";
		$req.="AND sp_olwdossier.Id_Client = {$_POST['client']} ";
		$req.="AND DateCreationQUALITE >= '2017-{$mois}-01' ";
		$req.="AND DateCreationQUALITE < '2017-{$mois1}-01'; ";
		
		$result1=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result1);
		
		// [194] - Deuxiemme requête récupère les dossiers RETQ parmis les dossiers TERC
		if($nbResulta > 0) {
			$req="SELECT Id ";
			$req.="FROM ";
			$req.="    sp_olwficheintervention ";
			
			$req.="WHERE ";
			$req.="    Id_Dossier IN (";
			$cpt=1;
			while($row = mysqli_fetch_array($result1, mysqli_ASSOC)) {
				if($cpt < $nbResulta)
					$req.=$row["Id_Dossier"].", ";
					else
						$req.=$row["Id_Dossier"].") ";
						$cpt+=$cpt;
			}
			$req.="AND Id_StatutQUALITE = 'RETQ';";
			
			$result=mysqli_query($bdd,$req);
			$nbResultb=mysqli_num_rows($result);
		}
		else
			$nbResultb=0;
			
			if ($nbResulta> 0)
				$resultsMois[$i] =(($nbResulta-$nbResultb)*100/$nbResulta);
				else
					$resultsMois[$i] =0;
	}
	
	//Test unitaire pour les couleurs
// 				$resultsMois[6] = 98;
// 				$resultsMois[7] = 93;
// 				$resultsMois[8] = 75;
	
	//[194] - Chargement de la liste des clients
	
	$req="SELECT DISTINCT sp_client.Id, sp_client.Libelle \n";
	$req.="FROM \n";
	$req.="sp_olwficheintervention, \n";
	$req.="sp_olwdossier, \n";
	$req.="sp_client \n";
	$req.="WHERE \n";
	$req.="sp_olwficheintervention.Id_Dossier = sp_olwdossier.Id \n";
	$req.="AND sp_olwdossier.Id_Client = sp_client.Id; \n";
	
	$clients=mysqli_query($bdd,$req);
	$nbClients=mysqli_num_rows($clients);
}

/**
 * Requetes pour la productivite
 */

function requetes_Productivite() {
	global $resultsMois;
	
	$date_reference = date('y-m-d');
	$nbSem = 8;
	$semainecourrante = date('W');
	
	//Vérifie si de dates ont été saisies
	if (isset($_POST['Start']) && isset($_POST['End'])) {
		if ($_POST['Start'] > 0 && $_POST['End'] > 0 ) {
			$nbSem = (getSemaine($_POST['End'])-getSemaine($_POST['Start']));
			$date_reference = $_POST['End'];
			$semainecourrante = getSemaine($_POST['End']);
		}
	}

	//Réservation de l'espace dans le tableau
	$resultsMois =  array_fill(0, $nbSem, 0);
	
	// [195] - requêtes pour la productivité TERA-TERC
	for($i = $nbSem; $i >=1 ; $i--) {
		// 		Les fiches d'interventions dans la semaine en cours
		$req = "SELECT Id ";
		$req .= "FROM ";
		$req .= "		sp_olwficheintervention ";
		$req .= "WHERE ";
		$req .= "		WEEK(DateIntervention) =  WEEK('".$date_reference."') -1 - ".$i." ";
		$req .= "		AND YEAR(DateIntervention) =  YEAR('".$date_reference."'); ";

		$ids_fi=mysqli_query($bdd,$req);

		if ($ids_fi) {
			$nbResulta=mysqli_num_rows($ids_fi);

			if($nbResulta > 0) {
				$req = "SELECT ";
				$req .= "		SUM(TempsPasse) AS Effectue ";
				$req .= "FROM ";
				$req .= "		sp_olwfi_travaileffectue ";
				$req .= "WHERE ";
				$req .= "		Id_FI IN (";
				
				for($id_param = 1; $id_param< $nbResulta; $id_param++) {
					$row = mysqli_fetch_array($ids_fi, mysqli_ASSOC);
					$req .= $row['Id'].", ";
				}
				
				$row = mysqli_fetch_array($ids_fi, mysqli_ASSOC);
				$req .= $row['Id']."); ";
				
				$effectues=mysqli_query($bdd,$req);
				$row = mysqli_fetch_array($effectues, mysqli_ASSOC);
				
				$nbHeuresEffectuees = $row['Effectue'];
				
				//Récupération des heures de dispo.				
				$req = "SELECT ";
				$req .= "WEEK(DatePlanning) AS W, ";
				$req .= "SUM(NbHeureEquipeJour + NbHeureEquipeNuit) AS Dispo ";
				$req .= "FROM ";
				$req .= "new_planning_personne_vacationabsence, ";
				$req .= "new_planning_vacationabsence ";
				$req .= "WHERE ";
				$req .= "new_planning_personne_vacationabsence.ID_VacationAbsence = new_planning_vacationabsence.Id ";
				$req .= "AND ID_Prestation = 1539 ";
				$req .= "AND WEEK(DatePlanning) = WEEK('".$date_reference."') - 1 - ".$i." ";
				$req .= "AND YEAR(DatePlanning) = YEAR('".$date_reference."') ";
				$req .= "GROUP BY ";
				$req .= "WEEK(DatePlanning); ";
				
				
				$dispo=mysqli_query($bdd,$req);
				
				if ($dispo) {
					$row = mysqli_fetch_array($dispo, mysqli_ASSOC);
	
					if ($row['Dispo'] > 0) {
						$resultsMois[($nbSem-$i)] = $nbHeuresEffectuees / $row['Dispo'] * 100;
					}
					else 
						$resultsMois[$nbSem-$i] = 0; 		// Si le dispo = 0 (pour éviter la division par zéro
				}else 
					$resultsMois[$nbSem-$i] = 0; 			// Si il n'y a pas de disponible
			}else 
				$resultsMois[$nbSem-$i] = 0;					//
		} else
			echo "Un problème avec la requête 'Les fiches d'interventions dans la semaine en cours' est survenue ( i = ".$i." ) <br />";
	}
}

/**
 * genere la page client OQD
 */

function genererPageClient_OQD() {
	global $clients;
	// Creation de la page à fournir au client après le clic
	echo "<title>SUIVI PROD AAA</title>\n";
	echo "<link rel=\"stylesheet\" href=\"../../JS/styleCalendrier.css\">\n";
	echo "<link href=\"../../../CSS/Feuille.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<link href=\"../../../CSS/New_Menu.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	
	echo "<script type=\"text/javascript\" src=\"../../JS/jquery.min.js\"></script>\n";
	echo "<script src=\"../../JS/modernizr.js\"></script>\n";
	echo "<script src=\"../../JS/js/jquery-1.4.3.min.js\"></script>\n";
	echo "<script src=\"../../JS/js/jquery-ui-1.8.5.min.js\"></script>\n";
	
	echo "Client\n";
	echo "<form action=\"Indicateur_OQD.php\" method=\"POST\">\n";
	echo "<select name=\"client\">\n";
	
	while($row = mysqli_fetch_array($clients, mysqli_ASSOC))
		if ($_POST['client'] == $row['Id'])
			echo "	<option value=\"{$row['Id']}\" selected>{$row['Libelle']}</option>\n";
		else
			echo "	<option value=\"{$row['Id']}\">{$row['Libelle']}</option>\n";
		
		echo "</select>\n";
		echo "<input type = \"submit\" value=\"Afficher\"/>\n";
		echo "</form>\n";
		echo "<br />\n";
		
		echo "<img alt=\"\" src=\"../../../tmp/OQD.png\" />\n";
}

/**
 * genere la page client OTD
 */

function genererPageClient_OTD() {
	global $clients;
	global $Mois;
	// Creation de la page à fournir au client après le clic
	
	echo "<title>SUIVI PROD AAA</title>\n";
	echo "<link rel=\"stylesheet\" href=\"../../JS/styleCalendrier.css\">\n";
	echo "<link href=\"../../../CSS/Feuille.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<link href=\"../../../CSS/New_Menu.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	
	echo "<script type=\"text/javascript\" src=\"../../JS/jquery.min.js\"></script>\n";
	echo "<script src=\"../../JS/modernizr.js\"></script>\n";
	echo "<script src=\"../../JS/js/jquery-1.4.3.min.js\"></script>\n";
	echo "<script src=\"../../JS/js/jquery-ui-1.8.5.min.js\"></script>\n";
	
	//Le javascript de la page
	echo "<script>\n";
	echo "	$( function() {\n";
	echo "		$( \"#datepickerStart\" ).datepicker({ dateFormat: 'yy-mm-dd', firstDay: 1 });\n";
	echo "		$( \"#datepickerEnd\" ).datepicker({ dateFormat: 'yy-mm-dd', firstDay: 1 });\n";
	echo "	} );\n";
	echo "</script>\n";
	
	echo "<script>\n";
	echo "function afficherSousUniteDeTemps() {\n";
	
	echo "	switch(parseInt(document.getElementById('UniteDeTemps').value)) {\n";
	echo "		case 1:\n";
	echo "			document.getElementById('Mois').style.display='none';\n";
	echo "			document.getElementById('Semaine').style.display = 'none';\n";
	echo "			document.getElementById('datespersonalises').style.display = 'none';\n";
	echo "			break;\n";
	
	echo "		case 2:\n";
	echo "			document.getElementById('Mois').style.display = '';\n";
	echo "			document.getElementById('Semaine').style.display = 'none';\n";
	echo "			document.getElementById('datespersonalises').style.display = 'none';\n";
	echo "			break;\n";
	
	echo "		case 3:\n";
	echo "			document.getElementById('Mois').style.display = 'none';\n";
	echo "			document.getElementById('Semaine').style.display = '';\n";
	echo "			document.getElementById('datespersonalises').style.display = 'none';\n";
	echo "			break;\n";
	
	echo "		case 4:\n";
	echo "			document.getElementById('Mois').style.display='none';\n";
	echo "			document.getElementById('Semaine').style.display = 'none';\n";
	echo "			document.getElementById('datespersonalises').style.display = '';\n";
	echo "			break;\n";
	echo "	}\n";
	echo "}\n";
	echo "</script>\n";
	
	
	echo "		<form action=\"Indicateur_OTD.php\" method=\"POST\">\n";
	
	//Tableau pour l'habillage et faire la page jolie :)
	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	
	//Tableau pour l'habillage et faire joli :) et avec un titre s'il vous plait ^^
	echo "			<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"TitrePage\">Planifier un nouveau dossier <font color=\"red\"></font></td> \n";
	echo "				</tr>\n";
	echo "			</table>\n";

	echo "	</td>\n";
	echo "	</tr>\n";
	//Petit espace entre les deux tableaux
	echo "<tr><td height=\"4\"></td></tr>\n";
	echo "<tr><td>\n";
	
	//Tableau pour l'habillage et faire joli :)
	echo "		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\" class=\"GeneralInfo\">\n";
	echo "				<tr>\n";
	echo "						<td>\n";
	
	echo "Client : <select name=\"client\">\n";
	echo "<option value=\"-1\">Tous</option>\n";
	while($row = mysqli_fetch_array($clients, mysqli_ASSOC))
		if ($_POST['client'] == $row['Id'])
			echo "<option value=\"{$row['Id']}\" selected>{$row['Libelle']}</option>\n";
		else
			echo "<option value=\"{$row['Id']}\">{$row['Libelle']}</option>\n";
	echo "</select>\n";

	echo "						</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "						<td>\n";
	
	echo "Cycles : <select name=\"Cycle\">\n";
	echo "			<option value=\"1\">Réception dossier - Lancement prod</option>\n";
	echo "			<option value=\"2\">Lancement prod - TERA</option>\n";
	echo "			<option value=\"3\">TERA - TERC</option>\n";
	echo "			<option value=\"4\">Lancement prod - TERC</option>\n";
	echo "			<option value=\"5\">Réception dossier - TERC</option>\n";
	echo "</select>\n";

	echo "						</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "						<td>\n";
	
	echo "Unité de temps : <select name=\"UniteDeTemps\" id=\"UniteDeTemps\" onchange=\"afficherSousUniteDeTemps()\">\n";
	echo "			<option value=\"1\">Année</option>\n";
	echo "			<option value=\"2\">Mois</option>\n";
	echo "			<option value=\"3\">Semaine</option>\n";
	echo "			<option value=\"4\">Personalisé</option>\n";
	echo "</select>\n";
	
	echo "						</td>\n";
	echo "				</tr>\n";
	echo "				<tr id=\"Mois\">\n";
	echo "						<td>\n";
	
	echo "Mois : <select name=\"Mois\">\n";
	foreach($Mois as $key => $value) 
		echo "		<option value=\"".($key+1)."\">".$value."</option>\n";
	echo "</select>\n";
	
	echo "						</td>\n";
	echo "				</tr>\n";
	echo "				<tr id=\"Semaine\">\n";
	echo "						<td>\n";

	echo "Semaine : <select name=\"Semaine\" >\n";
	for($i = 1; $i <=52; $i++)
		echo "		<option value=\"".$i."\">".$i."</option>\n";
	echo "</select>\n";
		
	echo "						</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "						<td>\n";
	
	echo "<div id=\"datespersonalises\">Du <input type=\"text\" id=\"datepickerStart\" name=\"Start\"> au <input type=\"text\" id=\"datepickerEnd\" name=\"End\"></div>\n";
	
	echo "						</td>\n";
	echo "				</tr>\n";
	
	echo "				<tr>\n";
	echo "						<td align=\"center\">\n";
	echo "<br />\n";
	echo "<input type = \"submit\" value=\"Afficher\"/>\n";
	echo "<br />\n";
	echo "<br />\n";
	
	//Tableau pour l'habillage et faire joli :)
	echo "					</tr>\n";
	echo "			</table>\n";
	echo "			</tr>\n";

	echo "	<tr>\n";
	echo "		<td align = \"center\">\n";
	echo "			<img alt=\"\" src=\"../../../tmp/OTD.png\" />\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	
	echo "	</table>\n";
	echo "</form>\n";
	
	echo "<br />\n";
	
	echo "<script>afficherSousUniteDeTemps()</script>\n";
}

/**
 * Genere la page client Productivite
 */

function genererPageClient_Productivite() {
	global $clients;
	// Creation de la page à fournir au client après le clic
	echo "<link href=\"../../JS/styleCalendrier.css\" rel=\"stylesheet\">\n";
	echo "<link href=\"../../../CSS/Feuille.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<link href=\"../../../CSS/New_Menu.css\" rel=\"stylesheet\" type=\"text/css\">\n";
	echo "<link href=\"../../JS/Wickedpicker/stylesheets/wickedpicker.css\" rel=\"stylesheet\" type=\"text/css\">\n";

	echo "<script type=\"text/javascript\" src=\"../../JS/jquery.min.js\"></script>\n";
	echo "<script src=\"../../JS/modernizr.js\"></script>\n";
	echo "<script src=\"../../JS/js/jquery-1.4.3.min.js\"></script>\n";
	echo "<script src=\"../../JS/js/jquery-ui-1.8.5.min.js\"></script>\n";
	echo "<script src=\"../../JS/Wickedpicker/src/wickedpicker.js\"></script>\n";

	echo "<form action=\"Indicateur_Productivite.php\" method=\"POST\">\n";
	echo "<p>Du <input type=\"text\" id=\"datepickerStart\" name=\"Start\"> au <input type=\"text\" id=\"datepickerEnd\" name=\"End\"></p>\n";
	echo "<input type = \"submit\" value=\"Afficher\"/>\n";
	echo "</form>\n";
	
	echo "<br />\n";
	echo "<br />\n";
	echo "<br />\n";
	
	echo "<script>\n";
	echo "		$( function() {\n";
	echo "				$( \"#datepickerStart\" ).datepicker({ dateFormat: 'yy-mm-dd', firstDay: 1 });\n";
	echo "				$( \"#datepickerEnd\" ).datepicker({ dateFormat: 'yy-mm-dd', firstDay: 1 });\n";
	echo "		} );\n";
	echo "	</script>\n";
	
	echo "<img alt=\"\" src=\"../../../tmp/Productivite.png\" />\n";
	echo "</body>\n";
	echo "</html>\n";
}

/**
 * Retourne le numero de la semaine
 * 
 * @param string $maDate La date a partir de laquelle on veux le numero de la semaine
 * @return string
 */

function getSemaine($maDate) {
	$good_format=strtotime ($maDate);
	return date('W',$good_format);
}

/**
 * Recupere les Id des dossiers sur la periode observee.
 * 
 * @param date $dateDebut
 * @param date $dateFin
 * @return array
 */

function requete_OTD_getIdDossierDeLaPeriodeChoisie($dateDebut, $dateFin, $IdClient) {
	global $mode_execution;
	
	$req = "SELECT sp_olwficheintervention.Id \n";
	$req .= "FROM \n";
	$req .= "	sp_olwficheintervention, \n";
	$req .= "	sp_olwdossier \n";
	$req .= "WHERE \n";
	$req .= "	sp_olwficheintervention.Id_Dossier = sp_olwdossier.Id \n";
	$req .= "AND Id_StatutQUALITE = 'TERC' \n";
	$req .= "AND Id_Client = ".$IdClient." \n";
	$req .= "AND DateInterventionQ >= '".$dateDebut."' \n";
	$req .= "AND DateInterventionQ <= '".$dateFin."'; \n";
	
	if ($mode_execution == 'verbose')
		echo $req."<br />\n";
	
	//Executer la requête
	$sql_ressource = mysqli_query($bdd,$req);
	
	//Mettre les résultats dans un tableau
	$IdsDossier = array();
	while($row = mysqli_fetch_array($sql_ressource, mysqli_ASSOC))
		$IdsDossier[] = $row['Id'];
	
		if ($mode_execution == 'verbose') {
			print_r($IdsDossier);
			echo "<br />";
		}
			
	// Libérer les ressources
	mysqli_free_result($sql_ressource);
	
	//retourner le tableau
	return $IdsDossier;
}

/**
 * Recupere les FI en statut TERA
 * 
 * @param array $IdsDossier
 * @return array
 */

function requete_OTD_TERA($IdsDossier) {
	global $mode_execution;
	
	$req = "SELECT \n";
	$req .= "		Id_Dossier, \n";
	$req .= "		DateIntervention \n";
	$req .= "FROM \n";
	$req .= "		sp_olwficheintervention \n";
	$req .= "WHERE \n";
	$req .= "		Id_StatutPROD = 'TERA' \n";
	if (sizeof($IdsDossier) > 0) {
		$req .= "	AND Id_Dossier IN ( ";
	
		$i=0;
		foreach ($IdsDossier as $Id) {
			if ($i < count($IdsDossier)-1)
				$req .= $Id.",";
			else
				$req .= $Id.") \n";
			$i++;
		}
	}else
		echo "Aucune données n'a été trouvé pour ce client, tous les clients sont affichés !<br />\n";
	
	$req .= "AND DateIntervention > 0\n";
	$req .= "ORDER BY Id_Dossier;\n";
	
	//Executer la requête
	$sql_ressource = mysqli_query($bdd,$req);
	
	//Mettre les résultats dans un tableau
	$data = array();
	while($row = mysqli_fetch_array($sql_ressource, mysqli_ASSOC))
		$data[] = $row;
		
	// Libérer les ressources
	 mysqli_free_result($sql_ressource);
	
	//retourner le tableau
	return $data;
}

/**
 * Recupere les FI en statut TERC
 * 
 * @param array $IdsDossier
 * @return array
 */

function requete_OTD_TERC($IdsDossier) {
	$req = "SELECT  \n";
	$req .= "		Id_Dossier,  \n";
	$req .= "		DateInterventionQ  \n";
	$req .= "FROM \n";
	$req .= "		sp_olwficheintervention \n";
	$req .= "WHERE \n";
	$req .= "		Id_StatutQUALITE = 'TERC' \n";
	if (sizeof($IdsDossier) > 0) {
		$req .= "	AND Id_Dossier IN ( ";
		
		$i=0;
		foreach ($IdsDossier as $Id) {
			if ($i < count($IdsDossier)-1)
				$req .= $Id.",";
				else
					$req .= $Id.") \n";
					$i++;
		}
	}else
		echo "Aucune données n'a été trouvé pour ce client, tous les clients sont affichés !<br />\n";
	
	$req .= "AND DateInterventionQ > 0\n";
	$req .= "ORDER BY Id_Dossier; \n";
	
	//Executer la requête
	$sql_ressource = mysqli_query($bdd,$req);

	//Mettre les résultats dans un tableau
	$data = array();
	while($row = mysqli_fetch_array($sql_ressource, mysqli_ASSOC))
		$data[] = $row;
	
	// Libérer les ressources
	 mysqli_free_result($sql_ressource);
	
	//retourner le tableau
	return $data;
}

/**
 * Recupere les FI en lancement PROD
 * 
 * @param array $IdsDossier
 * @return array
 */

function requete_OTD_LancementPROD($IdsDossier) {
	global $mode_execution;
	
	$req = "SELECT * \n";
	$req .= "FROM sp_olwficheintervention \n";
	
	if (sizeof($IdsDossier) > 0) {
		$req .= "WHERE \n";
		$req .= "	Id_Dossier IN ( ";
		
		$i=0;
		foreach ($IdsDossier as $Id) {
			if ($i < count($IdsDossier)-1)
				$req .= $Id.", ";
				else
					$req .= $Id.") \n";
					$i++;
		}
	}else
		echo "Aucune données n'a été trouvé pour ce client, tous les clients sont affichés !<br />\n";
	
	$req .= "ORDER BY \n";
	$req .= "	Id_Dossier, \n";
	$req .= "	Dateintervention ASC, \n";
	$req .= "	Id; \n";

	if($mode_execution== 'verbose')
		echo "<br />".$req."<br />";
	
	
	//Executer la requête
	$sql_ressource = mysqli_query($bdd,$req);
	
	// Traitement du premier enregistrement
	$data = traitement_OTD_PremiereFIdesDossiers($sql_ressource);

	// Libérer les ressources
	 mysqli_free_result($sql_ressource);
	
	 // retourner le tableau
	 return $data;
}

/**
 * 	Calcul le mode ReceptionDossier_LancementPROD
 * 
 * @param array $IdsDossier
 * @param	int	$uniteDeTemps Unite de temps pour la représentation graphique (1- Année, 2 - Mois, 3 - Semaine)
 * @return array
 */

function requete_OTD_mode_ReceptionDossier_LancementPROD($IdsDossier, $uniteDeTemps, $param, $dateDebut, $dateFin) {
	global $mode_execution;
	
	if ($mode_execution == 'dev')
		echo "\$uniteDeTemps : ".$uniteDeTemps."<br /> \n";
	
	//Calcul de la limite de temps
		$SQLparam = "";
	switch ($uniteDeTemps) {
		case 2: //Par mois
			$SQLparam = "AND DateIntervention >= '".date('Y')."-".$param."-01'\n";
			$SQLparam .= "AND DateIntervention <= '".date('Y')."-".$param."-31'\n";
			break;
			
		case 3:
			$SQLparam = "AND WEEK(DateIntervention) = ".$param."\n";
			break;
			
		case 4: //Personalisé
			$SQLparam = "AND DateIntervention >= '".$dateDebut."-01'\n";
			$SQLparam .= "AND DateIntervention <= '".$dateFin."-31'\n";
	}
		
	$req = "SELECT \n";
	$req .= "	sp_olwdossier.Id AS Id_Dossier, \n";
	$req .= "	sp_olwdossier.DateCreation, \n";
	$req .= "	sp_olwficheintervention.DateIntervention \n";
	$req .= "FROM \n";
	$req .= "	sp_olwdossier, \n";
	$req .= "	sp_olwficheintervention \n";
	$req .= "WHERE \n";
	$req .= "	sp_olwdossier.Id = sp_olwficheintervention.Id_Dossier \n";
	$req .= "	AND sp_olwficheintervention.DateIntervention > 0 \n";
	
	if (sizeof($IdsDossier) > 0) {		
		$req .= "	AND Id_Dossier IN ( ";
		
		$i=0;
		foreach ($IdsDossier as $Id) {
			if ($i < count($IdsDossier)-1)
				$req .= $Id.",";
				else
					$req .= $Id.") \n";
					$i++;
		}
	}else 
		echo "Aucune données n'a été trouvé pour ce client, tous les clients sont affichés !<br />\n";
	
	// Si il y a des paramètre supplémentaires les ajouter à la requête
	if (sizeof($param) > 0)
		$req .= $SQLparam;
	
	$req .= "ORDER BY \n";
	$req .= "	Id_Dossier, \n";
	$req .= "	DateIntervention ASC, \n";
	$req .= "	sp_olwficheintervention.Id; \n";
	
	if ($mode_execution == 'verbose')
		echo $req."<br />\n";

		if ($mode_execution == 'dev')
			echo $req."<br />\n";
		
	//Executer la requête
	$sql_ressource = mysqli_query($bdd,$req);
	
	// Traitement du premier enregistrement
	$data = traitement_OTD_PremiereFIdesDossiers($sql_ressource);

	if ($mode_execution == 'dev') {
		echo "Avant calcul_OTD()<br /> \n";
		print_r($data);
	}
	
	//Calcul
	$resultat = calcul_OTD($data, $uniteDeTemps, $param, 'DateIntervention', 'DateCreation'); //mode a l'année forcé pour tester
	if ($mode_execution == 'dev') {
		echo "Après calcul_OTD()<br /> \n";
		print_r($resultat);
	}
	
	// Libérer les ressources
	mysqli_free_result($sql_ressource);
	
	if ($mode_execution == 'dev') {
		echo "Retour de la fonction : <br /> \n";
		print_r($resultat);
	}
	
	// retourner le tableau
	return $resultat;
}

/**
 * Calcule le mode ReceptionDossier_TERC
 * 
 * @param array $IdsDossier
 * @param int $uniteDeTemps
 * @param	int	$param
 * @return array
 */

function requete_OTD_mode_ReceptionDossier_TERC($IdsDossier, $uniteDeTemps, $param) {
	global $mode_execution;
	
	$req = "SELECT \n";
	$req .= "	sp_olwdossier.Id, \n";
	$req .= "	sp_olwdossier.DateCreation, \n";
	$req .= "	sp_olwficheintervention.DateInterventionQ \n";
	$req .= "FROM \n";
	$req .= "	sp_olwdossier, \n";
	$req .= "	sp_olwficheintervention \n";
	$req .= "WHERE \n";
	$req .= "	Id_StatutPROD IN ('TERA', 'REWORK') \n";
	$req .= "AND Id_StatutQUALITE = 'TERC' \n";
	$req .= "AND sp_olwdossier.Id = sp_olwficheintervention.Id_Dossier \n";
	$req .= "AND sp_olwficheintervention.DateInterventionQ > 0 \n";
	if (sizeof($IdsDossier) > 0) {
		$req .= "	AND Id_Dossier IN ( ";
		
		$i=0;
		foreach ($IdsDossier as $Id) {
			if ($i < count($IdsDossier)-1)
				$req .= $Id.",";
				else
					$req .= $Id.") \n";
					$i++;
		}
	}else
		echo "Aucune données n'a été trouvé pour ce client, tous les clients sont affichés !<br />\n";
	
	$req .= "ORDER BY \n";
	$req .= "	Id_Dossier, \n";
	$req .= "	DateIntervention ASC, \n";
	$req .= "	sp_olwficheintervention.Id; \n";
	
	if ($mode_execution == 'verbose')
		echo $req."<br />\n";
		
		if ($mode_execution == 'dev')
			echo $req."<br />\n";
	
	//Executer la requête
	$sql_ressource = mysqli_query($bdd,$req);
	
	//Récupération du résultat
	$data = array(); 
	
	while($row = mysqli_fetch_array($sql_ressource, mysqli_ASSOC))
			$data[$row['Id']] = $row;
	
	//Comparaison / Calcul des différences de dates
	
	//Calcul
	$resultat = calcul_OTD($data, $uniteDeTemps, $param, 'DateInterventionQ', 'DateCreation');

	// Libérer les ressources
	mysqli_free_result($sql_ressource);
	
	// Retourner le tableau
	return $resultat;
}

/**
 * Calcul le mode LancementPROD_TERA
 * 
 * @param array $IdsDossier
 * @return array
 */

function requete_OTD_mode_LancementPROD_TERA($IdsDossier, $uniteDeTemps, $param)  {

	global $mode_execution;
	
	$resultat_LP = requete_OTD_LancementPROD($IdsDossier);
	$resultat_TERA = requete_OTD_TERA($IdsDossier);
	
	//Comparaison
	$data = traitement_OTD_Comparer($resultat_LP, $resultat_TERA, 'Id_Dossier', 'DateIntervention', 'DateIntervention');
	
	//Calcul
	$resultat = calcul_OTD($data, $uniteDeTemps, $param, 'DateIntervention', 'DateIntervention');
	
	//retourner le tableau
	return $resultat;
}

/**
 * Calcul le mode LancementPROD_TERC
 * 
 * @param array $IdsDossier
 * @return array
 */

function requete_OTD_mode_LancementPROD_TERC($IdsDossier, $uniteDeTemps, $param) {
	
	$resultat_LP = requete_OTD_LancementPROD($IdsDossier);
	$resultat_TERC = requete_OTD_TERC($IdsDossier);
	
	//Comparaison
	$data = traitement_OTD_Comparer($resultat_LP, $resultat_TERC, 'Id_Dossier', 'DateIntervention', 'DateInterventionQ');
	
	//Calcul
	$resultat = calcul_OTD($data,  $uniteDeTemps, $param, 'DateIntervention', 'DateInterventionQ');
	
	//retourner le tableau
	return $resultat;
}

/**
 * Calcul le mode TERA_TERC
 * 
 * @param array $IdsDossier
 * @return array
 */

function requete_OTD_mode_TERA_TERC($IdsDossier, $uniteDeTemps, $param) {
	
	$resultat_TERA = requete_OTD_TERA($IdsDossier);
	$resultat_TERC = requete_OTD_TERC($IdsDossier);
	
	//Comparaison
	$data = traitement_OTD_Comparer($resultat_TERA, $resultat_TERC, 'Id_Dossier', 'DateIntervention', 'DateInterventionQ');
	
	//Calcul
	$resultat = calcul_OTD($data, $uniteDeTemps, $param, 'DateIntervention', 'DateInterventionQ');
	
	//Retourner le tableau
	return $resultat; //$resultat;
}


/**
 * Recupere les premiere FI des dossiers
 * 
 * @param ressource $sql_ressource
 * @return array
 */

function traitement_OTD_PremiereFIdesDossiers($sql_ressource) {
	$premierFI = array();
	if (is_bool($sql_ressource)) {
		echo "Une erreur est survenue lors de l'exécution d'une requête SQL<br /> \n";
		return $premierFI;
		exit();
	}
	
// 	while($row = mysqli_fetch_array($sql_ressource, mysqli_ASSOC))
// 		if(!array_key_exists($row['Id_Dossier'], $premierFI))
// 			$premierFI[$row['Id_Dossier']] = $row;
		
	$i = 0;			
	while($row = mysqli_fetch_array($sql_ressource, mysqli_ASSOC))
		if(!array_key_exists($row['Id_Dossier'], $premierFI)) {
			$premierFI[$i] = $row;
			$i++;
		}

	return $premierFI;
}

/**
 *  Comparaison de deux tableaux en les affichants
 *  
 * @author	Anthony Schricke <aschricke@aaa-aero.com>  
 *	@package	OTD
 *  
 *	@param	array	$tab1	premier tableau
 *	@param	array	$tab2	second tableau
 *	@param	string	$nomId Nom de la colonne qui contient l'identifiant
 */

function traitement_OTD_Comparer($tab1, $tab2, $nomId, $nomColTab1, $nomColTab2) {

	$rtab1 = array();
	$rtab2 = array();
	
	$resultat = array();
	
	
	//Traitemant du tableau 1
	for($i =0; $i < count($tab1); $i++)
		$rtab1[$tab1[$i][$nomId]] = $tab1[$i][$nomColTab1];
	
	//Traitemant du tableau 2
	for($i =0; $i < count($tab2); $i++)
		$rtab2[$tab2[$i][$nomId]] = $tab2[$i][$nomColTab2];

		if ($nomColTab1 == $nomColTab2)
			$nomColTab2 = $nomColTab2."_1";
		
	foreach($rtab1 as $key => $value) {
		if (array_key_exists($key, $rtab2))
			$resultat[$key] = array($nomId => $key, $nomColTab1=> $rtab1[$key], $nomColTab2=> $rtab2[$key]);
	}
			
	return $resultat;
}


/**
 * Calcul de OTD a partir d un tableau de donnees
 * 
 * @param array $data
 * @param int $mode
 * @param int $param
 * @param string $nomDate1
 * @param string $nomDate2
 * @return array
 */

function calcul_OTD($data	, $mode, $param, $nomDate1, $nomDate2) {
global $mode_execution;
//Déclaration des constantes
	define("PAR_MOIS",1);
	define("PAR_SEMAINE", 2);
	define("PERSONALISE", 3);

	//Calculer
	if ($mode_execution == 'dev')
		echo "Je passe par ici (calcul_OTD) :) le mode est : ".$mode."<br />";
	//		Diviser par unité de temps
	switch ($mode) {
		case 1: // Année
			if ($mode_execution == 'dev') {
				echo "avant diviser par mois()<br /> \n";
				print_r($data);
			}
			$resultat = diviserParMois($data, $nomDate1, $nomDate2);
			if ($mode_execution == 'dev') {
				echo "après diviser par mois()<br /> \n";
				print_r($resultat);
			}
			break;
			
		case 2: // Mois
			$resultat = diviserParJours($data, $nomDate1, $nomDate2, PAR_MOIS, $param); 
			if ($mode_execution == 'dev') {
				echo "après diviser par jours() par mois<br /> \n";
				print_r($resultat);
			}
			break;
			
		case 3: // Semaine
			$resultat = diviserParJours($data, $nomDate1, $nomDate2, PAR_SEMAINE, $param);
			if ($mode_execution == 'dev') {
				echo "après diviser par jours() par semaine<br /> \n";
				print_r($resultat);
			}
			break;
			
		case 4: // Personalisé
			$resultat = diviserParJours($data, $nomDate1, $nomDate2, PERSONALISE, $param);
			if ($mode_execution == 'dev') {
				echo "après diviser par jours() personalisé<br /> \n";
				print_r($resultat);
			}
			break;
	}
	
	//Retourner tableau
	return $resultat;
}

/**
 *Permets de diviser le resultat par mois 
 *
 * @param	array	$data	tableau de donnees
 * @param	string	$nomColonneDateRef	nom de la colonne contenant la date
 * @param	string	$nomColonneDate2	nom de la colonne contenant la deuxieme date pour calculer la différence en jours
 */

function diviserParMois($data, $nomColonneDateRef, $nomColonneDate2) {
	$tabComptage = array();
 	$tabNbJours = array();
 	
	$tabComptage = array_fill(1, 12, 0);
	$tabNbJours = array_fill(1, 12, 0);
	
	foreach ($data as $row) {
		$numMois = date("m", strtotime($row[$nomColonneDateRef]));
		$tabComptage[intval($numMois)] = $tabComptage[intval($numMois)] + 1;
		
		$d1 = date_create($row[$nomColonneDateRef]);
		$d2 = date_create($row[$nomColonneDate2]);
//		$diff = $d2->diff($d1);
		$diff = date_difference($d1, $d2);
		
 		$tabNbJours[intval($numMois)] = $tabNbJours[intval($numMois)] + $diff->d;
	}

	$resultat = moyenne($tabComptage, $tabNbJours);
	
	return $resultat;
}

/**
 * Permets de diviser les donnees par Jours
 * 
 * @param array $data
 * @param	date $nomColonneDateRef
 * @param date $nomColonneDate2
 * @param	string	$mode	Pour définir la vue par mois ou par semaine
 */

function diviserParJours($data, $nomColonneDateRef, $nomColonneDate2, $mode, $param) {
	global $mode_execution;
	
	$nbJours = 0;
	$tabComptage = array();
	$tabNbJours = array();

	if ($mode_execution == 'dev') {
		echo "je passe par la division par jours <br />\n";
		print_r($data);
		echo "<br />\n";
	}
	//Obtenir le nombre de jours
	switch ($mode) {
		case 1: //Mode mois
			$date_debut = date('Y')."-".$param."-01";
			$nbJours = cal_days_in_month(CAL_GREGORIAN, date("m", strtotime($date_debut)), date("Y", strtotime($date_debut)));
			break;
		case 2: //Mode semaine
			$nbJours = 7;
			break;
		case 3: //Mode personalisé
			$nbJours = $param;
			break;
	}
	
	$tabComptage = array_fill(1, $nbJours, 0);
	$tabNbJours = array_fill(1, $nbJours, 0);
	
	foreach ($data as $row) {
		switch ($mode) {
			case 1: //Mode mois
				$numJour = date("j", strtotime($row[$nomColonneDateRef]));
				break;
			case 2: //Mode semaine
				$numJour = date("N", strtotime($row[$nomColonneDateRef]));
				break;
			case 3: //Mode personalise
				$numJour = date("N", strtotime($row[$nomColonneDateRef]));
				break;
		}
		
		$tabComptage[intval($numJour)] = $tabComptage[intval($numJour)] + 1;
		
		$d1 = date_create($row[$nomColonneDateRef]);
		$d2 = date_create($row[$nomColonneDate2]);
		//$diff = $d2->diff($d1);
		$diff = date_difference($d1, $d2);
		
		$tabNbJours[intval($numJour)] = $tabNbJours[intval($numJour)] + $diff->d;
	}
	
	$resultat = moyenne($tabComptage, $tabNbJours);
	
	//Retourner le résultat
	return $resultat;
}

/**
 * Fonction qui calcul la moyenne 
 * 
 * @param array $cpt
 * @param array $nbJ
 */
function moyenne($cpt, $nbJ) {
	$resultat = array();
	$resultat = array_fill(1, count($cpt), 0);
	
	for ($key = 1; $key <= count($cpt); $key++)
		if ($cpt[$key] > 0)
			$resultat[$key] = $nbJ[$key]/$cpt[$key];
		
	return $resultat;
}

/**
 * date_difference
 * 
 * Fait la différence entre deux dates
 * 
 * @param unknown $dateDebut La date la plus ancienne
 * @param unknown $dateFin La date la plus récente
 * @return number Le nombre de jours
 */
function date_difference($dateDebut, $dateFin) {
	$debut = new DateTime($dateDebut);
	$fin = new DateTime($dateFin);
	$nb_jours = round(($fin->format('U') - $debut->format('U')) / (60*60*24));
	return $nb_jours;
}

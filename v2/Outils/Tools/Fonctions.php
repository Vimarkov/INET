<?php
global $Tableau_ChampsMateriel;

global $TypeECME;
global $TypeEPI;
global $TypeMaqueDeControle;
global $TypeOutillage;
global $TypeTelephone;
global $TypeClef;
global $TypeInformatique;
global $TypeInfrastructure;
global $Vehicule;
global $TypeTOUS;

//Type de matériel
$TypeECME=1;
$TypeEPI=2;
$TypeMaqueDeControle=3;
$TypeOutillage=4;
$TypeTelephone=5;
$TypeClef=6;
$TypeInformatique=7;
$TypeInfrastructure=8;
$TypeVehicule=9;
$TypeMacaron=10;
$TypeTOUS=array($TypeECME,$TypeEPI,$TypeMaqueDeControle,$TypeOutillage,$TypeTelephone,$TypeClef,$TypeInformatique,$TypeInfrastructure,$TypeVehicule,$TypeMacaron);

$Tableau_ChampsMateriel=array
(
	array("Prix","Prix (€)","Price (€)",10,$TypeTOUS),
	array("SN","Num de série","Serial Number",20,array($TypeECME,$TypeEPI,$TypeOutillage,$TypeTelephone,$TypeInformatique,$TypeMacaron)),
	array("PN","PN","PN",20,array($TypeECME,$TypeOutillage,$TypeTelephone,$TypeInformatique)),
	array("Taille","Taille","Size",10,array($TypeEPI)),
	array("TypeECME","Type ECME","ECME type",20,array($TypeECME)),
	array("ClassePrecision","Classe ou précision","Class or precision",20,array($TypeECME)),
	array("NumTelephone","Num téléphone","Telephone number",20,array($TypeTelephone)),
	array("NumSIM","Num SIM","SIM number",20,array($TypeTelephone)),
	array("NumIMEI","Num IMEI","IMEI number",20,array($TypeTelephone)),
	array("NumClef","Num de clef","Key number",20,array($TypeClef)),
	array("NumMC","Numéro","Number",20,array($TypeMaqueDeControle)),
	array("IdentificationMC","Identification","Identification",20,array($TypeMaqueDeControle)),
	array("DateLettreEngagementMC","Date lettre d'engagement","Date commitment letter",20,array($TypeMaqueDeControle)),
	array("DisqueDur","Disque dur (Go)","Hard disk (Go)",10,array($TypeInformatique)),
	array("Processeur","Processeur","Processor",10,array($TypeInformatique)),
	array("Memoire","RAM (Go)","RAM (Go)",10,array($TypeInformatique)),
	array("RefAirbus","Référence Airbus","Airbus Reference",20,array($TypeInformatique)),
	array("NumPC","Numéro AAA","AAA number",10,array($TypeInformatique)),
	array("Immatriculation","Immatriculation","Immatriculation",10,array($TypeVehicule)),
	array("Kilometrage","Kilométrage","Mileage",10,array($TypeVehicule)),
	array("CodeCarteCarburant","Code carte carburant","Fuel card code",10,array($TypeVehicule)),
	array("ImmatriculationAssociee","Immatriculation associée","Associated immatriculation",10,array($TypeMacaron)),
	array("DateFinValidite","Date fin validité","End date validity",10,array($TypeMacaron)),
	array("PeriodiciteVerification","Périodicité vérification (en mois)","Verification periodicity (in months)",10,array($TypeECME,$TypeEPI,$TypeVehicule)),
	array("DateDerniereVerification","Date dernière vérification","Date of last check",8,array($TypeECME,$TypeEPI,$TypeVehicule)),
	array("BonCommande","Bon de commande","Purchase order",20,array($TypeECME,$TypeEPI,$TypeOutillage,$TypeInfrastructure,$TypeInformatique,$TypeVehicule))
);

function Next_CodeGravureMateriel($Id_Plateforme)
{
	global $bdd;
	global $IdPersonneConnectee;
	
	$RequeteCodeGravureMateriel="
		SELECT
			CodeGravureMateriel
		FROM
			new_competences_plateforme
		WHERE
			Id=".$Id_Plateforme." ";
	$ResultCodeGravureMateriel=mysqli_query($bdd,$RequeteCodeGravureMateriel);
	$RowCodeGravureMateriel=mysqli_fetch_array($ResultCodeGravureMateriel);
	
	$tailleCodeGravure=strlen($RowCodeGravureMateriel['CodeGravureMateriel'])+2;
	
	$RequeteLastNumGravureMateriel="
	SELECT IF(LOCATE('-',SUBSTRING(NumAAA,".$tailleCodeGravure."))>0,
	SUBSTRING(NumAAA,".$tailleCodeGravure.",LOCATE('-',SUBSTRING(NumAAA,".$tailleCodeGravure."))),
	SUBSTRING(NumAAA,".$tailleCodeGravure.")) AS NumAAA2 
	FROM tools_materiel 
	WHERE NumAAA LIKE '".$RowCodeGravureMateriel['CodeGravureMateriel']."-%' 

	UNION ALL

	SELECT IF(LOCATE('-',SUBSTRING(NumAAA,".$tailleCodeGravure."))>0,SUBSTRING(NumAAA,".$tailleCodeGravure.",LOCATE('-',SUBSTRING(NumAAA,".$tailleCodeGravure."))),SUBSTRING(NumAAA,".$tailleCodeGravure.")) AS NumAAA2 
	FROM tools_caisse
	WHERE NumAAA LIKE '".$RowCodeGravureMateriel['CodeGravureMateriel']."-%' 

	ORDER BY CONVERT(NumAAA2,SIGNED INTEGER) DESC 
	LIMIT 1;";
	$ResultLastNumGravureMateriel=mysqli_query($bdd,$RequeteLastNumGravureMateriel);
	$RowLastNumGravureMateriel=mysqli_fetch_array($ResultLastNumGravureMateriel);

	$NumSuivant=intval($RowLastNumGravureMateriel['NumAAA2'])+1;
	if(strlen($RowCodeGravureMateriel['CodeGravureMateriel'])-strlen($NumSuivant)>0){
		$NumSuivant=str_repeat('0',strlen($RowCodeGravureMateriel['CodeGravureMateriel'])-strlen($NumSuivant)).$NumSuivant;
	}

	
	return $RowCodeGravureMateriel['CodeGravureMateriel']."-".$NumSuivant;
}

//Renvoi le nombre de transfert EC à valider
function NombreTransfertOutilsECArrivee($Id_Personne){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteResponsablePlateforme;
	global $IdPosteChefEquipe;
	global $IdPosteMagasinier;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	global $IdPosteGestionnaireMGX;
	global $IdPosteResponsableMGX;
	global $IdPosteInformatique;
	
	
	$requete=" SELECT Id
		FROM tools_mouvement
		WHERE EtatValidation=0
		AND tools_mouvement.TypeMouvement=0
		AND Suppr=0 
		AND (
			IF(Type=1,CONCAT(tools_mouvement.Id_Prestation,'_',tools_mouvement.Id_Pole),
				IF(Id_Caisse=0,CONCAT(tools_mouvement.Id_Prestation,'_',tools_mouvement.Id_Pole),
					(SELECT CONCAT(TAB_Mouvement.Id_Prestation,'_',TAB_Mouvement.Id_Pole) 
					FROM tools_mouvement AS TAB_Mouvement
					WHERE TAB_Mouvement.EtatValidation<>-1 
					AND TAB_Mouvement.TypeMouvement=0 
					AND TAB_Mouvement.Suppr=0 
					AND TAB_Mouvement.Type=1 
					AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
					ORDER BY DateReception DESC, Id DESC 
					LIMIT 1
					)
				)
			) IN 
			(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
			FROM new_competences_personne_poste_prestation 
			WHERE Id_Personne=".$_SESSION["Id_Personne"]."
			AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
			) 
			
			OR 
			
			IF(Type=1,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=tools_mouvement.Id_Prestation),
				IF(Id_Caisse=0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=tools_mouvement.Id_Prestation),
					(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=TAB_Mouvement.Id_Prestation) 
					FROM tools_mouvement AS TAB_Mouvement
					WHERE TAB_Mouvement.EtatValidation<>-1 
					AND TAB_Mouvement.TypeMouvement=0 
					AND TAB_Mouvement.Suppr=0 
					AND TAB_Mouvement.Type=1 
					AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
					ORDER BY DateReception DESC, Id DESC 
					LIMIT 1
					)
				)
			) IN 
			(SELECT Id_Plateforme
			FROM new_competences_personne_poste_plateforme
			WHERE Id_Personne=".$_SESSION["Id_Personne"]."
			AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.") 
			)
		)
		";
	$result=mysqli_query($bdd,$requete);
	$nbResulta=mysqli_num_rows($result);
	
	return $nbResulta;
}

//Renvoi le nombre de transfert EC à valider
function NombreTransfertOutilsECDepart($Id_Personne){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteResponsablePlateforme;
	global $IdPosteChefEquipe;
	global $IdPosteMagasinier;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	global $IdPosteGestionnaireMGX;
	global $IdPosteResponsableMGX;
	global $IdPosteInformatique;
	

	$requete=" SELECT Id
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0
		AND DatePriseEnCompteDemandeur<='0001-01-01'
		AND Suppr=0 
		AND (
				IF(Type=1,
					(SELECT CONCAT(TAB.Id_Prestation,'_',TAB.Id_Pole)
						FROM tools_mouvement AS TAB
						WHERE EtatValidation=1
						AND TAB.TypeMouvement=0
						AND TAB.DatePriseEnCompteDemandeur>'0001-01-01'
						AND TAB.Type=1
						AND TAB.Id_Materiel__Id_Caisse=tools_mouvement.Id_Materiel__Id_Caisse
						AND TAB.Suppr=0 
						AND TAB.Id<>tools_mouvement.Id
						ORDER BY DateReception DESC, Id DESC LIMIT 1)
				,
					(SELECT IF(TAB.Id_Caisse=0,CONCAT(TAB.Id_Prestation,'_',TAB.Id_Pole),
							(SELECT CONCAT(TABCaisse.Id_Prestation,'_',TABCaisse.Id_Pole)
							FROM tools_mouvement AS TABCaisse
							WHERE TABCaisse.EtatValidation=1
							AND TABCaisse.TypeMouvement=0
							AND TABCaisse.DatePriseEnCompteDemandeur>'0001-01-01'
							AND TABCaisse.Type=1
							AND TABCaisse.Id_Materiel__Id_Caisse=TAB.Id_Caisse
							AND TABCaisse.Suppr=0 
							ORDER BY DateReception DESC, Id DESC LIMIT 1)
						)
						FROM tools_mouvement AS TAB
						WHERE EtatValidation=1
						AND TAB.TypeMouvement=0
						AND TAB.DatePriseEnCompteDemandeur>'0001-01-01'
						AND TAB.Type=0
						AND TAB.Id_Materiel__Id_Caisse=tools_mouvement.Id_Materiel__Id_Caisse
						AND TAB.Suppr=0 
						AND TAB.Id<>tools_mouvement.Id
						ORDER BY DateReception DESC, Id DESC LIMIT 1)
				) IN 
				(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
				FROM new_competences_personne_poste_prestation 
				WHERE Id_Personne=".$_SESSION["Id_Personne"]."
				AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
				) 
				
				OR 
				
				IF(Type=1,
					(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=TAB.Id_Prestation)
						FROM tools_mouvement AS TAB
						WHERE EtatValidation=1
						AND TAB.TypeMouvement=0
						AND TAB.DatePriseEnCompteDemandeur>'0001-01-01'
						AND TAB.Type=1
						AND TAB.Id_Materiel__Id_Caisse=tools_mouvement.Id_Materiel__Id_Caisse
						AND TAB.Suppr=0 
						AND TAB.Id<>tools_mouvement.Id
						ORDER BY DateReception DESC, Id DESC LIMIT 1)
				,
					(SELECT IF(TAB.Id_Caisse=0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=TAB.Id_Prestation),
							(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=TABCaisse.Id_Prestation)
							FROM tools_mouvement AS TABCaisse
							WHERE TABCaisse.EtatValidation=1
							AND TABCaisse.TypeMouvement=0
							AND TABCaisse.DatePriseEnCompteDemandeur>'0001-01-01'
							AND TABCaisse.Type=1
							AND TABCaisse.Id_Materiel__Id_Caisse=TAB.Id_Caisse
							AND TABCaisse.Suppr=0 
							ORDER BY DateReception DESC, Id DESC LIMIT 1)
						)
						FROM tools_mouvement AS TAB
						WHERE EtatValidation=1
						AND TAB.TypeMouvement=0
						AND TAB.DatePriseEnCompteDemandeur>'0001-01-01'
						AND TAB.Type=0
						AND TAB.Id_Materiel__Id_Caisse=tools_mouvement.Id_Materiel__Id_Caisse
						AND TAB.Suppr=0 
						AND TAB.Id<>tools_mouvement.Id
						ORDER BY DateReception DESC, Id DESC LIMIT 1)
				)IN 
				(SELECT Id_Plateforme
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$_SESSION["Id_Personne"]."
					AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.") 
				)
			)
		";
	$result=mysqli_query($bdd,$requete);
	$nbResulta=mysqli_num_rows($result);
	
	return $nbResulta;
}

//Renvoi le nombre de transfert EC à valider
function NombreTransfertOutilsECPlus7Jour($Id_Personne){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteResponsablePlateforme;
	global $IdPosteChefEquipe;
	global $IdPosteMagasinier;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	global $IdPosteGestionnaireMGX;
	global $IdPosteResponsableMGX;
	global $IdPosteInformatique;
	
	$nb=0;
	
	$requete=" SELECT Id
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0
		AND DatePriseEnCompteDemandeur<='0001-01-01'
		AND Suppr=0 
		AND (
			IF(Type=1,
				(SELECT CONCAT(TAB.Id_Prestation,'_',TAB.Id_Pole)
					FROM tools_mouvement AS TAB
					WHERE EtatValidation=1
					AND TAB.TypeMouvement=0
					AND TAB.DatePriseEnCompteDemandeur>'0001-01-01'
					AND TAB.Type=1
					AND TAB.Id_Materiel__Id_Caisse=tools_mouvement.Id_Materiel__Id_Caisse
					AND TAB.Suppr=0 
					AND TAB.Id<>tools_mouvement.Id
					ORDER BY DateReception DESC, Id DESC LIMIT 1)
			,
				(SELECT IF(TAB.Id_Caisse=0,CONCAT(TAB.Id_Prestation,'_',TAB.Id_Pole),
						(SELECT CONCAT(TABCaisse.Id_Prestation,'_',TABCaisse.Id_Pole)
						FROM tools_mouvement AS TABCaisse
						WHERE TABCaisse.EtatValidation=1
						AND TABCaisse.TypeMouvement=0
						AND TABCaisse.DatePriseEnCompteDemandeur>'0001-01-01'
						AND TABCaisse.Type=1
						AND TABCaisse.Id_Materiel__Id_Caisse=TAB.Id_Caisse
						AND TABCaisse.Suppr=0 
						ORDER BY DateReception DESC, Id DESC LIMIT 1)
					)
					FROM tools_mouvement AS TAB
					WHERE EtatValidation=1
					AND TAB.TypeMouvement=0
					AND TAB.DatePriseEnCompteDemandeur>'0001-01-01'
					AND TAB.Type=0
					AND TAB.Id_Materiel__Id_Caisse=tools_mouvement.Id_Materiel__Id_Caisse
					AND TAB.Suppr=0 
					AND TAB.Id<>tools_mouvement.Id
					ORDER BY DateReception DESC, Id DESC LIMIT 1)
			) IN 
			(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
			FROM new_competences_personne_poste_prestation 
			WHERE Id_Personne=".$_SESSION["Id_Personne"]."
			AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
			) 
			
			OR 
			
			IF(Type=1,
				(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=TAB.Id_Prestation)
					FROM tools_mouvement AS TAB
					WHERE EtatValidation=1
					AND TAB.TypeMouvement=0
					AND TAB.DatePriseEnCompteDemandeur>'0001-01-01'
					AND TAB.Type=1
					AND TAB.Id_Materiel__Id_Caisse=tools_mouvement.Id_Materiel__Id_Caisse
					AND TAB.Suppr=0 
					AND TAB.Id<>tools_mouvement.Id
					ORDER BY DateReception DESC, Id DESC LIMIT 1)
			,
				(SELECT IF(TAB.Id_Caisse=0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=TAB.Id_Prestation),
						(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=TABCaisse.Id_Prestation)
						FROM tools_mouvement AS TABCaisse
						WHERE TABCaisse.EtatValidation=1
						AND TABCaisse.TypeMouvement=0
						AND TABCaisse.DatePriseEnCompteDemandeur>'0001-01-01'
						AND TABCaisse.Type=1
						AND TABCaisse.Id_Materiel__Id_Caisse=TAB.Id_Caisse
						AND TABCaisse.Suppr=0 
						ORDER BY DateReception DESC, Id DESC LIMIT 1)
					)
					FROM tools_mouvement AS TAB
					WHERE EtatValidation=1
					AND TAB.TypeMouvement=0
					AND TAB.DatePriseEnCompteDemandeur>'0001-01-01'
					AND TAB.Type=0
					AND TAB.Id_Materiel__Id_Caisse=tools_mouvement.Id_Materiel__Id_Caisse
					AND TAB.Suppr=0 
					AND TAB.Id<>tools_mouvement.Id
					ORDER BY DateReception DESC, Id DESC LIMIT 1)
			) IN 
			(SELECT Id_Plateforme
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION["Id_Personne"]."
				AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.") 
			)
		)
	AND DateReception<'".date("Y-m-d",strtotime(date("Y-m-d")." -7 day"))."'
		";
	$result=mysqli_query($bdd,$requete);
	$nbResulta=mysqli_num_rows($result);
	
	
	$requete=" SELECT Id
		FROM tools_mouvement
		WHERE EtatValidation=0
		AND tools_mouvement.TypeMouvement=0
		AND Suppr=0 
		AND (
			IF(Type=1,CONCAT(tools_mouvement.Id_Prestation,'_',tools_mouvement.Id_Pole),
				IF(Id_Caisse=0,CONCAT(tools_mouvement.Id_Prestation,'_',tools_mouvement.Id_Pole),
					(SELECT CONCAT(TAB_Mouvement.Id_Prestation,'_',TAB_Mouvement.Id_Pole) 
					FROM tools_mouvement AS TAB_Mouvement
					WHERE TAB_Mouvement.EtatValidation<>-1 
					AND TAB_Mouvement.TypeMouvement=0 
					AND TAB_Mouvement.Suppr=0 
					AND TAB_Mouvement.Type=1 
					AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
					ORDER BY DateReception DESC, Id DESC 
					LIMIT 1
					)
				)
			) IN 
			(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
			FROM new_competences_personne_poste_prestation 
			WHERE Id_Personne=".$_SESSION["Id_Personne"]."
			AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
			) 
			
			OR 
			
			IF(Type=1,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=tools_mouvement.Id_Prestation),
				IF(Id_Caisse=0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=tools_mouvement.Id_Prestation),
					(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=TAB_Mouvement.Id_Prestation) 
					FROM tools_mouvement AS TAB_Mouvement
					WHERE TAB_Mouvement.EtatValidation<>-1 
					AND TAB_Mouvement.TypeMouvement=0 
					AND TAB_Mouvement.Suppr=0 
					AND TAB_Mouvement.Type=1 
					AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
					ORDER BY DateReception DESC, Id DESC 
					LIMIT 1
					)
				)
			) IN 
			(SELECT Id_Plateforme
			FROM new_competences_personne_poste_plateforme
			WHERE Id_Personne=".$_SESSION["Id_Personne"]."
			AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.") 
			)
		)
	AND DateReception<'".date("Y-m-d",strtotime(date("Y-m-d")." -7 day"))."'
		";
	$result=mysqli_query($bdd,$requete);
	$nbResulta2=mysqli_num_rows($result);
	
	$nb=$nbResulta+$nbResulta2;
	
	return $nb;
}

//Renvoi le nombre de transfert EC à valider
function NombreMaterielInventaire($Id_Personne){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteResponsablePlateforme;
	global $IdPosteChefEquipe;
	global $IdPosteMagasinier;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	global $IdPosteGestionnaireMGX;
	global $IdPosteResponsableMGX;
	global $IdPosteInformatique;
	global $IdPosteControleGestion;
	global $IdPersonneConnectee;
	
	//PARTIE OUTILS DE LA REQUETE
	$Requete2="SELECT 
			tools_materiel.Id
			";
	$Requete="FROM
				tools_materiel
			WHERE
				tools_materiel.Suppr=0 
				AND DateReceptionT < '".date('Y-m-d',strtotime(date('Y-m-d')."- 6 month"))."'
				AND (
				(SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id = Id_LieuT ) NOT IN ('Perdu','Perdu officiellement','Recyclage','Volé','Détruit','Réformé','Echange (SAV)','Don')
				OR Id_LieuT=0
				)
			
			AND ( CONCAT(Id_PrestationT,'_',Id_PoleT) IN 
			
				(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$_SESSION["Id_Personne"]."
					AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
				)
				
				OR 
				
				(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = Id_PrestationT) IN 
			
				(SELECT Id_Plateforme
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$_SESSION["Id_Personne"]."
					AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteResponsablePlateforme.",".$IdPosteControleGestion.")  
				)
			
			OR Id_PersonneT = ".$IdPersonneConnectee."
			
			)  ";
	 $Result=mysqli_query($bdd,$Requete2.$Requete);
	$nbResultaP1=mysqli_num_rows($Result);
		//PARTIE CAISSE DE LA REQUETE
		$Requete2Caisse="
				SELECT 
				tools_caisse.Id
			";
		$RequeteCaisse="FROM
			tools_caisse 
		WHERE 
			tools_caisse.Suppr=0
		AND DateReceptionT < '".date('Y-m-d',strtotime(date('Y-m-d')."- 6 month"))."'
			AND ((SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id = Id_LieuT ) NOT IN ('Perdu','Perdu officiellement','Recyclage','Volé','Détruit','Réformé','Echange (SAV)','Don')
			OR Id_LieuT=0
			)
			
			 AND (CONCAT(Id_PrestationT,'_',Id_PoleT) IN 
			(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
				FROM new_competences_personne_poste_prestation 
				WHERE Id_Personne=".$_SESSION["Id_Personne"]."
				AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
			)
			
			OR 
			
			(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = Id_PrestationT)
			IN 
		
			(SELECT Id_Plateforme
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION["Id_Personne"]."
				AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteResponsablePlateforme.",".$IdPosteControleGestion.")  
			)
			
			OR Id_PersonneT = ".$IdPersonneConnectee."
		
		)  ";
	$Result=mysqli_query($bdd,$Requete2Caisse.$RequeteCaisse);
	$nbResultaP2=mysqli_num_rows($Result);
	$nbResulta=$nbResultaP1+$nbResultaP2;
	return $nbResulta;
}

//Renvoi le nombre de transfert EC à valider
function NombreMaterielEtalonnageReparation($Id_Personne){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteResponsablePlateforme;
	global $IdPosteChefEquipe;
	global $IdPosteMagasinier;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	global $IdPosteGestionnaireMGX;
	global $IdPosteResponsableMGX;
	global $IdPosteInformatique;
	global $IdPosteControleGestion;
	global $IdPersonneConnectee;
	
	//PARTIE OUTILS DE LA REQUETE
	$Requete2="SELECT 
			TAB_MATERIEL.Id
		FROM 
		(
		SELECT
			tools_materiel.Id,
			(SELECT IF(TAB_Mouvement.Id_Caisse=0,
						CONCAT(TAB_Mouvement.EtatValidation,'|.1.|',TAB_Mouvement.Id_Prestation,'|.2.|',TAB_Mouvement.Id_Pole,'|.3.|',TAB_Mouvement.Id_Lieu,'|.4.|',TAB_Mouvement.Id_Personne,'|.5.|',TAB_Mouvement.Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|'),
						(
						SELECT CONCAT(EtatValidation,'|.1.|',Id_Prestation,'|.2.|',Id_Pole,'|.3.|',Id_Lieu,'|.4.|',Id_Personne,'|.5.|',Id_Materiel__Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|')
						FROM tools_mouvement
						WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						)
				)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
			) AS AffectationMouvement
			";
	$Requete="FROM
				tools_materiel
			LEFT JOIN
				tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
			LEFT JOIN
				tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
			LEFT JOIN
				tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id 
			WHERE
				tools_materiel.Suppr=0 ) AS TAB_MATERIEL
			WHERE SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5)) < '".date('Y-m-d',strtotime(date('Y-m-d')."- 1 month"))."'
				AND (SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5)) ) IN ('Etalonnage','Réparation')

			AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5))) IN 
		
		(SELECT Id_Plateforme
			FROM new_competences_personne_poste_plateforme
			WHERE Id_Personne=".$_SESSION["Id_Personne"]."
			AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.")  
		)	
		 ";

		//PARTIE CAISSE DE LA REQUETE
		$Requete2Caisse=" UNION ALL
				SELECT 
				TAB_MATERIEL.Id
			FROM 
			(
			SELECT Id,
			(
				SELECT CONCAT(EtatValidation,'|.1.|',Id_Prestation,'|.2.|',Id_Pole,'|.3.|',Id_Lieu,'|.4.|',Id_Personne,'|.5.|',Id_Materiel__Id_Caisse,'|.6.|',DateReception,'|.7.|') 
				FROM tools_mouvement 
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id 
				ORDER BY DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			) AS AffectationMouvement
			";
		$RequeteCaisse="FROM
			tools_caisse 
		WHERE 
			tools_caisse.Suppr=0  ) AS TAB_MATERIEL
		WHERE SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.7.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.6.|',TAB_MATERIEL.AffectationMouvement)+5)) < '".date('Y-m-d',strtotime(date('Y-m-d')."- 1 month"))."'
			AND (SELECT Libelle FROM tools_lieu WHERE tools_lieu.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.4.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)+5)) ) IN ('Etalonnage','Réparation')
			 AND 
			(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)))
			IN 
		
			(SELECT Id_Plateforme
				FROM new_competences_personne_poste_plateforme
				WHERE Id_Personne=".$_SESSION["Id_Personne"]."
				AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.")  
			)
		  ";
	$Result=mysqli_query($bdd,$Requete2.$Requete.$Requete2Caisse.$RequeteCaisse);
	$nbResulta=mysqli_num_rows($Result);
		
	return $nbResulta;
}

//Renvoi le nombre de location avec une date de fin <= 3 mois
function NombreLocationDateFinInferieur3Mois($Id_Personne){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteResponsablePlateforme;
	global $IdPosteChefEquipe;
	global $IdPosteMagasinier;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	global $IdPosteGestionnaireMGX;
	global $IdPosteResponsableMGX;
	global $IdPosteInformatique;
	global $IdPosteControleGestion;
	global $IdPosteDirection;
	global $IdPersonneConnectee;
	
	$req="SELECT CONCAT(Id_Prestation,'_',Id_Pole) Id
		FROM new_competences_personne_poste_prestation 
		WHERE Id_Personne=".$_SESSION["Id_Personne"]."
		AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") ";
	$Result=mysqli_query($bdd,$req);
	
	$listePrestaPole="('-1_-1')";
	$NbEnreg=mysqli_num_rows($Result);
	if($NbEnreg){
		$listePrestaPole="(";
		while($RowListe=mysqli_fetch_array($Result)){
			if($listePrestaPole<>"("){$listePrestaPole.=",";}
			$listePrestaPole.="'".$RowListe['Id']."'";
		}
		$listePrestaPole.=")";
	}
	
	$req="(SELECT Id_Plateforme
			FROM new_competences_personne_poste_plateforme
			WHERE Id_Personne=".$_SESSION["Id_Personne"]."
			AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteResponsablePlateforme.",".$IdPosteControleGestion.",".$IdPosteDirection.") 
		) ";
	$Result=mysqli_query($bdd,$req);
	
	$listePlateforme="(-1)";
	$NbEnreg=mysqli_num_rows($Result);
	if($NbEnreg){
		$listePlateforme="(";
		while($RowListe=mysqli_fetch_array($Result)){
			if($listePlateforme<>"("){$listePlateforme.=",";}
			$listePlateforme.="".$RowListe['Id_Plateforme']."";
		}
		$listePlateforme.=")";
	}

	//PARTIE OUTILS DE LA REQUETE
	$Requete2="

		SELECT 
			TAB_MATERIEL.ID
		FROM 
		(
		SELECT
			tools_materiel.Id AS ID,
			(SELECT IF(TAB_Mouvement.Id_Caisse=0,
						CONCAT(TAB_Mouvement.EtatValidation,'|.1.|',TAB_Mouvement.Id_Prestation,'|.2.|',TAB_Mouvement.Id_Pole,'|.3.|',TAB_Mouvement.Id_Lieu,'|.4.|',TAB_Mouvement.Id_Personne,'|.5.|',TAB_Mouvement.Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|'),
						(
						SELECT CONCAT(EtatValidation,'|.1.|',Id_Prestation,'|.2.|',Id_Pole,'|.3.|',Id_Lieu,'|.4.|',Id_Personne,'|.5.|',Id_Materiel__Id_Caisse,'|.6.|',TAB_Mouvement.DateReception,'|.7.|',TAB_Mouvement.Commentaire,'|.8.|')
						FROM tools_mouvement
						WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
						ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
						)
				)
				FROM tools_mouvement AS TAB_Mouvement
				WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
				ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
			) AS AffectationMouvement
			";
	$Requete="FROM
				tools_materiel
			LEFT JOIN
				tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
			LEFT JOIN
				tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id 
			WHERE tools_materiel.Suppr=0
				AND tools_materiel.Location=1 
				AND DateFinContratLocation <= '".date('Y-m-d', strtotime(date('Y-m-d')." +3 month"))."'
			) AS TAB_MATERIEL 
	WHERE (CONCAT(SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)),'_',SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5))) IN ".$listePrestaPole."
			
			OR 
			
			(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)))
			IN ".$listePlateforme."
		)";

		//PARTIE CAISSE DE LA REQUETE
		$Requete2Caisse="UNION ALL
			SELECT 
				TAB_MATERIEL.ID
			FROM (
				SELECT Id AS ID,
				(
					SELECT CONCAT(EtatValidation,'|.1.|',Id_Prestation,'|.2.|',Id_Pole,'|.3.|',Id_Lieu,'|.4.|',Id_Personne,'|.5.|',Id_Materiel__Id_Caisse,'|.6.|',DateReception,'|.7.|',Commentaire,'|.8.|') 
					FROM tools_mouvement 
					WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id 
					ORDER BY DateReception DESC, tools_mouvement.Id DESC LIMIT 1
				) AS AffectationMouvement
			";
		$RequeteCaisse="FROM
			tools_caisse
		WHERE 
			tools_caisse.Suppr=0 
			AND tools_caisse.Location=1 
			AND DateFinContratLocation <= '".date('Y-m-d', strtotime(date('Y-m-d')." +3 month"))."'
			) AS TAB_MATERIEL 
		WHERE (CONCAT(SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)),'_',SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.3.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)+5))) IN ".$listePrestaPole."
			
			OR 
			
			(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id = SUBSTRING(TAB_MATERIEL.AffectationMouvement,LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5,LOCATE('|.2.|',TAB_MATERIEL.AffectationMouvement)-(LOCATE('|.1.|',TAB_MATERIEL.AffectationMouvement)+5)))
			IN ".$listePlateforme."
		)";

	$Result=mysqli_query($bdd,$Requete2.$Requete.$Requete2Caisse.$RequeteCaisse);
	$nbResulta=mysqli_num_rows($Result);
		
	return $nbResulta;
}

//Renvoi le nombre de changements à valider
function NombreChangementMateriel($Id_Personne){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteResponsablePlateforme;
	global $IdPosteChefEquipe;
	global $IdPosteMagasinier;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	global $IdPosteGestionnaireMGX;
	global $IdPosteResponsableMGX;
	global $IdPosteInformatique;
	
	$nb=0;
	if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme,$IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
		$req="SELECT CONCAT(Id,'_0') AS Id
			FROM new_competences_prestation
			WHERE Id_Plateforme IN 
				(
					SELECT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$Id_Personne." 
					AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
				)
			AND Id_Plateforme=1
			AND Id NOT IN (
					SELECT Id_Prestation
					FROM new_competences_pole    
					WHERE Actif=0
				)
				
			UNION 
			
			SELECT DISTINCT CONCAT(new_competences_pole.Id_Prestation,'_',new_competences_pole.Id) AS Id
				FROM new_competences_pole
				INNER JOIN new_competences_prestation
				ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
				AND Actif=0
				AND Id_Plateforme IN 
				(
					SELECT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$Id_Personne." 
					AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
				)
				AND new_competences_prestation.Id_Plateforme=1
			";
	}
	else{
		$req="SELECT CONCAT(Id_Prestation,'_',Id_Pole) AS Id
			FROM new_competences_personne_poste_prestation 
			WHERE Id_Personne=".$Id_Personne."
			AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
		";
		
	}
	$Result=mysqli_query($bdd,$req);
	
	$listePrestaPole="('-1_-1')";
	$NbEnreg=mysqli_num_rows($Result);
	if($NbEnreg){
		$listePrestaPole="(";
		while($RowListe=mysqli_fetch_array($Result)){
			if($listePrestaPole<>"("){$listePrestaPole.=",";}
			$listePrestaPole.="'".$RowListe['Id']."'";
		}
		$listePrestaPole.=")";
	}

 
	//PARTIE OUTILS DE LA REQUETE
	$Requete="
			SELECT 
				tools_materiel.Id
			FROM 
				tools_materiel
			WHERE Id_PersonneT>0
			AND tools_materiel.Suppr=0 
			AND EtatValidationT IN (0,1)
			AND 
			(
				(SELECT CONCAT(rh_personne_mouvement.Id_Prestation,'_',rh_personne_mouvement.Id_Pole)
				FROM rh_personne_mouvement
				WHERE Suppr=0
				AND rh_personne_mouvement.Id_Personne=Id_PersonneT
				AND rh_personne_mouvement.EtatValidation IN (0,1)
				AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1)
				<> CONCAT(Id_PrestationT,'_',Id_PoleT)
				OR 
				(SELECT COUNT(Id)
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND DateDebut<='".date('Y-m-d')."'
				AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
				AND TypeDocument IN ('Nouveau','Avenant')
				AND rh_personne_contrat.Id_Personne=Id_PersonneT
				)=0
			)
			AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_PrestationT) = 1
			AND CONCAT(Id_PrestationT,'_',Id_PoleT) IN ".$listePrestaPole." ";
	$Result=mysqli_query($bdd,$Requete);
	$nbP1=mysqli_num_rows($Result);
	
	//PARTIE CAISSE DE LA REQUETE
	$Requete="
			SELECT 
				tools_caisse.Id
			FROM 
				tools_caisse 
			WHERE Id_PersonneT>0
			AND Suppr=0 
			AND EtatValidationT IN (0,1)
			AND 
			(
				(SELECT CONCAT(rh_personne_mouvement.Id_Prestation,'_',rh_personne_mouvement.Id_Pole)
				FROM rh_personne_mouvement
				WHERE Suppr=0
				AND rh_personne_mouvement.Id_Personne=Id_PersonneT
				AND rh_personne_mouvement.EtatValidation IN (0,1)
				AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
				AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."') LIMIT 1)
				<> CONCAT(Id_PrestationT,'_',Id_PoleT)
				OR 
				(SELECT COUNT(Id)
				FROM rh_personne_contrat
				WHERE Suppr=0
				AND DateDebut<='".date('Y-m-d')."'
				AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
				AND TypeDocument IN ('Nouveau','Avenant')
				AND rh_personne_contrat.Id_Personne=Id_PersonneT
				)=0
			)
		AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_PrestationT) = 1
		AND CONCAT(Id_PrestationT,'_',Id_PoleT) IN ".$listePrestaPole." ";
	$Result=mysqli_query($bdd,$Requete);
	$nbP2=mysqli_num_rows($Result);
	
	$nb=$nbP1+$nbP2;
	return $nb;
}

//Renvoi le nombre de changements de plateforme à valider
function NombreChangementPlateformeMateriel($Id_Personne){
	global $bdd;
	global $IdPosteResponsableRH;
	global $IdPosteAssistantRH;
	global $IdPosteResponsablePlateforme;
	global $IdPosteChefEquipe;
	global $IdPosteMagasinier;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	global $IdPosteGestionnaireMGX;
	global $IdPosteResponsableMGX;
	global $IdPosteInformatique;
	
	$nb=0;
	if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme,$IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
		$req="SELECT CONCAT(Id,'_0') AS Id
			FROM new_competences_prestation
			WHERE Id_Plateforme IN 
				(
					SELECT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$Id_Personne." 
					AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
				)
			AND Id_Plateforme=1
			AND Id NOT IN (
					SELECT Id_Prestation
					FROM new_competences_pole    
					WHERE Actif=0
				)
				
			UNION 
			
			SELECT DISTINCT CONCAT(new_competences_pole.Id_Prestation,'_',new_competences_pole.Id) AS Id
				FROM new_competences_pole
				INNER JOIN new_competences_prestation
				ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
				AND Actif=0
				AND Id_Plateforme IN 
				(
					SELECT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$Id_Personne." 
					AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
				)
				AND new_competences_prestation.Id_Plateforme=1
			";
		$Result=mysqli_query($bdd,$req);
	}
	else{
		$req="SELECT CONCAT(Id_Prestation,'_',Id_Pole) AS Id
			FROM new_competences_personne_poste_prestation 
			WHERE Id_Personne=".$Id_Personne."
			AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
		";
		$Result=mysqli_query($bdd,$req);
	}
	
	$listePrestaPole="('-1_-1')";
	$NbEnreg=mysqli_num_rows($Result);
	if($NbEnreg){
		$listePrestaPole="(";
		while($RowListe=mysqli_fetch_array($Result)){
			if($listePrestaPole<>"("){$listePrestaPole.=",";}
			$listePrestaPole.="'".$RowListe['Id']."'";
		}
		$listePrestaPole.=")";
	}
 
	//PARTIE OUTILS DE LA REQUETE
	$Requete="
		SELECT 
			tools_materiel.Id
		FROM 
			tools_materiel
		LEFT JOIN
			tools_modelemateriel ON tools_materiel.Id_ModeleMateriel=tools_modelemateriel.Id
		LEFT JOIN
			tools_famillemateriel ON tools_modelemateriel.Id_FamilleMateriel=tools_famillemateriel.Id
		LEFT JOIN
			tools_typemateriel ON tools_famillemateriel.Id_TypeMateriel=tools_typemateriel.Id
		WHERE Id_PersonneT>0
		AND tools_materiel.Suppr=0 
		AND EtatValidationT IN (0,1)
		AND 
		(
			((SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
			FROM new_competences_personne_plateforme
			WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
			AND new_competences_personne_plateforme.Id_Plateforme=14)>0
			AND
			(SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
			FROM new_competences_personne_plateforme
			WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT)=1)
			OR 
			(
				SELECT Active 
				FROM new_competences_prestation 
				WHERE new_competences_prestation.Id=Id_PrestationT
			)=-1
			OR 
			(
				SELECT Actif
				FROM new_competences_pole
				WHERE new_competences_pole.Id=Id_PoleT
			)=1
			OR 
			((SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
			FROM new_competences_personne_plateforme
			WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
			AND new_competences_personne_plateforme.Id_Plateforme<>(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_PrestationT))>0
			AND
			(SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
			FROM new_competences_personne_plateforme
			WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT)=1)
		)
		AND CONCAT(Id_PrestationT,'_',Id_PoleT) IN ".$listePrestaPole." ";
	$Result=mysqli_query($bdd,$Requete);
	$nbP1=mysqli_num_rows($Result);
	
	//PARTIE CAISSE DE LA REQUETE
	$Requete="
		SELECT 
			tools_caisse.Id
		FROM 
			tools_caisse
		WHERE Id_PersonneT>0
		AND tools_caisse.Suppr=0
		AND EtatValidationT IN (0,1)		
		AND 
		(
			((SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
			FROM new_competences_personne_plateforme
			WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
			AND new_competences_personne_plateforme.Id_Plateforme=14)>0
			AND
			(SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
			FROM new_competences_personne_plateforme
			WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT)=1)
			OR 
			(
				SELECT Active 
				FROM new_competences_prestation 
				WHERE new_competences_prestation.Id=Id_PrestationT
			)=-1
			OR 
			(
				SELECT Actif
				FROM new_competences_pole
				WHERE new_competences_pole.Id=Id_PoleT
			)=1
			OR 
			((SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
			FROM new_competences_personne_plateforme
			WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT
			AND new_competences_personne_plateforme.Id_Plateforme<>(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_PrestationT))>0
			AND
			(SELECT COUNT(new_competences_personne_plateforme.Id_Plateforme)
			FROM new_competences_personne_plateforme
			WHERE new_competences_personne_plateforme.Id_Personne=Id_PersonneT)=1)
		)
		AND CONCAT(Id_PrestationT,'_',Id_PoleT) IN ".$listePrestaPole." ";
	$Result=mysqli_query($bdd,$Requete);
	$nbP2=mysqli_num_rows($Result);
	$nb=$nbP1+$nbP2;
	return $nb;
}
?>
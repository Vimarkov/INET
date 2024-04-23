<?php 
//Inclusions des différentes pages de fonctions et requêtes
require_once($_SERVER['DOCUMENT_ROOT']."/v2/Outils/Database_fonctions.php");
require_once("IdentificationPersonnelEnFormation_fonctions.php");
require_once("MultiAutorisationTravail_Excel_Fonctions.php");
require_once("QCM_Fonctions.php");
require_once("session_fonctions.php");
require_once("WorkflowDesSurveillances_fonctions.php");
//Cette page PHP contient des fonctions qui vont servir dans les différentes pages de la gestion des formations
global $DateJour;
global $IdPersonneConnectee;
global $LoginPersonneConnectee;
global $LangueAffichage;

global $IdPosteResponsableRH;
global $IdPosteResponsableFormation;
global $IdPosteResponsableHSE;
global $IdPosteResponsableQualite;
global $IdPosteProcedeSpecial;
global $IdPosteAssistantFormationInterne;
global $IdPosteAssistantFormationExterne;
global $IdPosteAssistantFormationTC;
global $IdPosteFormateur;
global $IdPosteAssistantRH;
global $IdPosteResponsablePlateforme;
global $IdPosteGestionnaireMGX;
global $IdPosteResponsableMGX;
global $IdPosteInformatique;
global $IdPosteAssistantAdministratif;
global $IdPosteOperateurSaisieRH;
global $IdPosteControleGestion;
global $IdPosteResponsableRecrutement;
global $IdPosteRecrutement;
global $IdPosteGestionnaireBadges;
global $IdPosteDirection;
global $IdPosteAideRH;
global $IdPosteDirectionOperation;
global $IdPosteChargeMissionOperation;
global $IdPosteCoordinateurSecurite;
global $IdPosteMembreCODIR;
global $IdPosteDivision;
global $IdPosteReferentSurveillance;
global $IdPosteAdministrateur;
global $IdPosteReferentQualiteProcedesSpeciaux;
global $IdPosteInnovation;
global $IdPosteInnoLab;
global $IdPosteTrainingModules;
global $IdPosteAssistantQualite;

global $IdPosteChefEquipe;
global $IdPosteCoordinateurEquipe;
global $IdPosteCoordinateurProjet;
global $IdPosteResponsableProjet;
global $IdPosteResponsableOperation;
global $IdPosteReferentQualiteProduit;
global $IdPosteReferentQualiteSysteme;
global $IdPosteConsultation;
global $IdPosteAssistantePrestation;
global $IdPosteMagasinier;
global $IdPosteSaisiePrestationRECORD;

global $IdTypeFormationEprouvette;
global $IdTypeFormationTC;
global $IdTypeFormationInterne;
global $IdTypeFormationExterne;

global $IdPosteEluCFDT;
global $IdPosteEluCFE_CGC;
global $IdPosteEluCGT;
global $IdPosteEluFO;
global $IdPosteSecretaireCSE;
global $IdCommissionCSSCT;
global $IdCommissionEconomique;
global $IdCommissionFEL;
global $IdCommissionHandicap;
global $IdCommissionConventionCollective;

global $TableauIdPostesAssistantFormation;
global $TableauIdPostesAF_RF;
global $TableauIdPostesAF_RF_RQ;
global $TableauIdPostesAFI_RF_RQ;
global $TableauIdPostesAFI_RF_RQ_FORM;
global $TableauIdPostesCHE_COOE;
global $TableauIdPostesResponsablesPrestation;
global $TableauIdPostesRespPresta_CQ;
global $TableauIdPostesCQ;
global $TableauIdPostesRF_FORM_PS_RQP;
global $TableauIdPostesAF_RF_RQ_PS;
global $TableauIdPostesAF_RF_FORM_PS_RQP;
global $TableauIdPostesAF_RF_FORM;
global $TableauIdPostesAFI_RF_FORM;
global $TableauIdPostesAFI_RF_FORM_CQS;
global $TableauIdPostesAF_RF_RQ_RH_CQS_Form;
global $TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS;
global $TableauIdPostesAF_RF_RQ_RH_CQS;
global $TableauIdPostesAF_RF_RQ_RH;
global $TableauIdPostesAF_RF_RQ_RH_CQS_HSE;

//Liste des postes/plateformes
$IdPosteReferentQualiteSysteme=6;
$IdPosteResponsableFormation=13;
$IdPosteResponsableHSE=14;
$IdPosteResponsableQualite=15;
$IdPosteProcedeSpecial=16;
$IdPosteAssistantFormationInterne=17;
$IdPosteAssistantFormationExterne=18;
$IdPosteAssistantFormationTC=19;
$IdPosteFormateur=21;
$IdPosteMagasinier=22;
$IdPosteResponsableRH=23;
$IdPosteAssistantRH=31;
$IdPosteResponsablePlateforme=9;
$IdPosteGestionnaireMGX=11;
$IdPosteResponsableMGX=32;
$IdPosteInformatique=38;
$IdPosteAssistantAdministratif=33;
$IdPosteOperateurSaisieRH=34;
$IdPosteControleGestion=27;
$IdPosteResponsableRecrutement=28;
$IdPosteGestionnaireBadges=37;
$IdPosteRecrutement=36;
$IdPosteDirection=39;
$IdPosteAideRH=40;
$IdPosteDirectionOperation=41;
$IdPosteChargeMissionOperation=42;
$IdPosteCoordinateurSecurite=43;
$IdPosteMembreCODIR=44;
$IdPosteDivision=45;
$IdPosteSaisiePrestationRECORD=46;
$IdPosteReferentSurveillance=48;
$IdPosteAdministrateur=59;
$IdPosteReferentQualiteProcedesSpeciaux=60;
$IdPosteInnovation=61;
$IdPosteInnoLab=62;
$IdPosteTrainingModules=63;
$IdPosteAssistantQualite=64;

$IdPosteEluCFDT=49;
$IdPosteEluCFE_CGC=50;
$IdPosteEluCGT=51;
$IdPosteEluFO=52;
$IdPosteSecretaireCSE=53;
$IdCommissionCSSCT=54;
$IdCommissionEconomique=55;
$IdCommissionFEL=56;
$IdCommissionHandicap=57;
$IdCommissionConventionCollective=58;

$TableauIdPostesAssistantFormation=array($IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC);
$TableauIdPostesAF_RF=array($IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC);
$TableauIdPostesAF_RF_HSE=array($IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC,$IdPosteResponsableHSE);
$TableauIdPostesAF_RF_FORM=array($IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC,$IdPosteFormateur);
$TableauIdPostesAFI_RF_FORM=array($IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteFormateur);
$TableauIdPostesAF_RF_RQ=array($IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC,$IdPosteResponsableQualite);
$TableauIdPostesAFI_RF_RQ=array($IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteResponsableQualite);
$TableauIdPostesAFI_RF_RQ_FORM=array($IdPosteFormateur,$IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteResponsableQualite);
$TableauIdPostesAF_RF_RQ_PS=array($IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC,$IdPosteResponsableQualite,$IdPosteProcedeSpecial);
$TableauIdPostesRF_FORM_PS_RQP=array($IdPosteResponsableFormation,$IdPosteFormateur,$IdPosteProcedeSpecial,$IdPosteResponsableQualite);
$TableauIdPostesAF_RF_FORM_PS_RQP=array($IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC,$IdPosteResponsableFormation,$IdPosteFormateur,$IdPosteProcedeSpecial,$IdPosteResponsableQualite);
$TableauIdPostesAF_RH=array($IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC,$IdPosteResponsableRH);
$TableauIdPostesAF_RF_RH=array($IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC,$IdPosteResponsableRH);
$TableauIdPostesAF_RF_FORM_RH=array($IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC,$IdPosteFormateur,$IdPosteResponsableRH);
$TableauIdPostesAF_RF_RQ_RH=array($IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC,$IdPosteResponsableQualite,$IdPosteResponsableRH);

$TableauIdPostesAFI_RF_RQ_RH=array($IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteResponsableQualite,$IdPosteResponsableRH);
$TableauIdPostesAF_RF_RQ_PS_RH=array($IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC,$IdPosteResponsableQualite,$IdPosteProcedeSpecial,$IdPosteResponsableRH);
$TableauIdPostesAF_RF_FORM_PS_RQP_RH=array($IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC,$IdPosteResponsableFormation,$IdPosteFormateur,$IdPosteProcedeSpecial,$IdPosteResponsableQualite,$IdPosteResponsableRH);
$TableauIdPostesRH=array($IdPosteResponsableRH,$IdPosteAssistantRH);
$TableauIdPostesRH_RespPlat=array($IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteResponsablePlateforme);
$TableauIdPosteMGX=array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX);
$TableauIdPostesAF_RF_RQ_RH_CQS_ARH=array($IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC,$IdPosteResponsableQualite,$IdPosteResponsableRH,$IdPosteAssistantRH);
$TableauIdPosteOperation=array($IdPosteDirectionOperation,$IdPosteChargeMissionOperation);
$TableauIdPostesAF_RF_RQ_RH_CQS_Form=array($IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC,$IdPosteResponsableQualite,$IdPosteResponsableRH,$IdPosteFormateur);
$TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS=array($IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC,$IdPosteResponsableQualite,$IdPosteResponsableRH,$IdPosteFormateur,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite);
$TableauIdPostesAF_RF_RQ_RH_CQS=array($IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC,$IdPosteResponsableQualite,$IdPosteResponsableRH,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite);
$TableauIdPostesAF_RF_RQ_RH_CQS_HSE=array($IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC,$IdPosteResponsableQualite,$IdPosteResponsableRH,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteResponsableHSE);

//Liste des postes/prestations
$IdPosteChefEquipe=1;
$IdPosteCoordinateurEquipe=2;
$IdPosteCoordinateurProjet=3;
$IdPosteResponsableProjet=4;
$IdPosteReferentQualiteProduit=5;
$IdPosteResponsableOperation=7;
$IdPosteConsultation=10;
$IdPosteAssistantePrestation=24;
$TableauIdPostesCHE_COOE=array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe);
$TableauIdPostesResponsablesPrestation=array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet);
$TableauIdPostesRespPresta_CQ=array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite);
$TableauIdPostesCQ=array($IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite);
$TableauIdPostesAFI_RF_FORM_CQS=array($IdPosteResponsableFormation,$IdPosteAssistantFormationInterne,$IdPosteFormateur,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite);

//Type de formation
$IdTypeFormationEprouvette=1;
$IdTypeFormationTC=2;
$IdTypeFormationInterne=3;
$IdTypeFormationExterne=4;

$DateJour=date("Y-m-d");
if(isset($_SESSION['Id_Personne'])){$IdPersonneConnectee=$_SESSION['Id_Personne'];}
else{$IdPersonneConnectee=0;}
if(isset($_SESSION['Log'])){$LoginPersonneConnectee=$_SESSION['Log'];}
else{$LoginPersonneConnectee=0;}
if(isset($_SESSION['Langue'])){$LangueAffichage=$_SESSION['Langue'];}
else{$LangueAffichage="FR";}

//Renvoie "true" si la personne connectée apparait dans un des postes du tableau passé en paramètres sur la/les plateformes dont elle dépend dans la gestion des compétences
//Paramètres : Tableau des postes à vérifier en "OR"
//(pour éviter d'attendre le retour de plusieurs fonctions pour tester un même affichage pour plusieurs postes)
function DroitsFormationPlateforme($TableauIdPoste)
{
	global $bdd;
	global $IdPersonneConnectee;
	
	$DroitsFormationPlateforme=0;
	if(isset($_SESSION['Id_Plateformes']))
	{
		if(sizeof($_SESSION['Id_Plateformes'])>0 && sizeof($TableauIdPoste)>0)
		{
			$ReqDroits= "
				SELECT
					Id
				FROM
					new_competences_personne_poste_plateforme
				WHERE
					Id_Personne=".$IdPersonneConnectee."
					AND Id_Poste IN (".implode(",",$TableauIdPoste).")
					AND Id_Plateforme IN (".implode(",",$_SESSION['Id_Plateformes']).")";

			$ResultDroits=mysqli_query($bdd,$ReqDroits);
			$NbEnregDroits=mysqli_num_rows($ResultDroits);
			if($NbEnregDroits>0){$DroitsFormationPlateforme=1;}
		}
	}
	
	return $DroitsFormationPlateforme;
}

//Renvoie "true" si la personne connectée apparait dans un des postes du tableau passé en paramètres sur la/les plateformes dont elle dépend dans la gestion des compétences
//Paramètres : Tableau des postes à vérifier en "OR"
//(pour éviter d'attendre le retour de plusieurs fonctions pour tester un même affichage pour plusieurs postes)
function DroitsFormation1Plateforme($Id_Plateforme,$TableauIdPoste)
{
	global $bdd;
	global $IdPersonneConnectee;
	
	$DroitsFormationPlateforme=0;
	if(isset($_SESSION['Id_Plateformes']))
	{
		if(sizeof($_SESSION['Id_Plateformes'])>0 && sizeof($TableauIdPoste)>0)
		{
			$ReqDroits= "
				SELECT
					Id
				FROM
					new_competences_personne_poste_plateforme
				WHERE
					Id_Personne=".$IdPersonneConnectee."
					AND Id_Poste IN (".implode(",",$TableauIdPoste).")
					AND Id_Plateforme IN (".$Id_Plateforme.")";
			$ResultDroits=mysqli_query($bdd,$ReqDroits);
			$NbEnregDroits=mysqli_num_rows($ResultDroits);
			if($NbEnregDroits>0){$DroitsFormationPlateforme=1;}
		}
	}
	
	return $DroitsFormationPlateforme;
}

//Renvoie "true" si la personne connectée apparait dans un des postes du tableau passé en paramètres sur la/les plateformes dont elle dépend dans la gestion des compétences
//Paramètres : Tableau des postes à vérifier en "OR"
//(pour éviter d'attendre le retour de plusieurs fonctions pour tester un même affichage pour plusieurs postes)
function DroitsFormationPlateformes($Id_Plateformes,$TableauIdPoste)
{
	global $bdd;
	global $IdPersonneConnectee;
	
	$DroitsFormationPlateforme=0;
	if(isset($_SESSION['Id_Plateformes']))
	{
		if(sizeof($_SESSION['Id_Plateformes'])>0 && sizeof($TableauIdPoste)>0)
		{
			$ReqDroits= "
				SELECT
					Id
				FROM
					new_competences_personne_poste_plateforme
				WHERE
					Id_Personne=".$IdPersonneConnectee."
					AND Id_Poste IN (".implode(",",$TableauIdPoste).")
					AND Id_Plateforme IN (".implode(",",$Id_Plateformes).")";
			$ResultDroits=mysqli_query($bdd,$ReqDroits);
			$NbEnregDroits=mysqli_num_rows($ResultDroits);
			if($NbEnregDroits>0){$DroitsFormationPlateforme=1;}
		}
	}
	
	return $DroitsFormationPlateforme;
}
//Renvoie "true" si la personne connectée apparait dans un des postes du tableau passé en paramètres sur la/les plateformes dont elle dépend dans la gestion des compétences
//Paramètres : Tableau des postes à vérifier en "OR"
//(pour éviter d'attendre le retour de plusieurs fonctions pour tester un même affichage pour plusieurs postes)
function DroitsPlateforme($TableauIdPoste)
{
	global $bdd;
	global $IdPersonneConnectee;
	
	$DroitsFormationPlateforme=0;
	if(isset($_SESSION['Id_Plateformes']))
	{
		if(sizeof($_SESSION['Id_Plateformes'])>0 && sizeof($TableauIdPoste)>0)
		{
			$ReqDroits= "
				SELECT
					Id
				FROM
					new_competences_personne_poste_plateforme
				WHERE
					Id_Personne=".$IdPersonneConnectee."
					AND Id_Poste IN (".implode(",",$TableauIdPoste).") ";
			$ResultDroits=mysqli_query($bdd,$ReqDroits);
			$NbEnregDroits=mysqli_num_rows($ResultDroits);
			if($NbEnregDroits>0){$DroitsFormationPlateforme=1;}
		}
	}
	
	return $DroitsFormationPlateforme;
}

//V2 pour VerifLogin
function DroitsFormationPlateformeV2($Id_Personne, $TableauIdPoste)
{
	global $bdd;
	
	$DroitsFormationPlateforme=0;
	if(sizeof($_SESSION['Id_Plateformes'])>0 && sizeof($TableauIdPoste)>0)
	{
		$ReqDroits= "
			SELECT
				Id
			FROM
				new_competences_personne_poste_plateforme
			WHERE
				Id_Personne=".$Id_Personne."
				AND Id_Poste IN (".implode(",",$TableauIdPoste).")
				AND Id_Plateforme IN (".implode(",",$_SESSION['Id_Plateformes']).")";
		$ResultDroits=mysqli_query($bdd,$ReqDroits);
		$NbEnregDroits=mysqli_num_rows($ResultDroits);
		if($NbEnregDroits>0){$DroitsFormationPlateforme=1;}
	}
	
	return $DroitsFormationPlateforme;
}

//Renvoie "true" si la personne connectée apparait dans un des postes du tableau passé en paramètres sur la/les prestations dont elle dépend dans la gestion des compétences
//Paramètres : Tableau des postes à vérifier en "OR"
//(pour éviter d'attendre le retour de plusieurs fonctions pour tester un même affichage pour plusieurs postes)
function DroitsFormationPrestation($TableauIdPoste)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $DateJour;
	
	$DroitsFormationPrestation=false;
	$ReqDroits= "
		SELECT
			Id
		FROM
			new_competences_personne_poste_prestation
		WHERE
			Id_Personne=".$IdPersonneConnectee."
			AND Id_Poste IN (".implode(",",$TableauIdPoste).") ";
	$ResultDroits=mysqli_query($bdd,$ReqDroits);
	$NbEnregDroits=mysqli_num_rows($ResultDroits);
	if($NbEnregDroits>0){$DroitsFormationPrestation=true;}
	
	return $DroitsFormationPrestation;
}

//Renvoie "true" si la personne connectée apparait dans un des postes du tableau passé en paramètres sur la/les prestations dont elle dépend dans la gestion des compétences
//Paramètres : Tableau des postes à vérifier en "OR"
//(pour éviter d'attendre le retour de plusieurs fonctions pour tester un même affichage pour plusieurs postes)
function DroitsFormationPrestations($Id_Plateformes,$TableauIdPoste)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $DateJour;
	
	$DroitsFormationPrestation=false;
	$ReqDroits= "
		SELECT
			Id
		FROM
			new_competences_personne_poste_prestation
		WHERE
			Id_Personne=".$IdPersonneConnectee."
			AND Id_Poste IN (".implode(",",$TableauIdPoste).") 
			AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".implode(",",$Id_Plateformes).") ";
	
	$ResultDroits=mysqli_query($bdd,$ReqDroits);
	$NbEnregDroits=mysqli_num_rows($ResultDroits);
	if($NbEnregDroits>0){$DroitsFormationPrestation=true;}
	
	return $DroitsFormationPrestation;
}

function DroitsFormationPrestationV2($TableauIdPrestation,$TableauIdPoste)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $DateJour;
	
	$DroitsFormationPrestation=false;
	$ReqDroits= "
		SELECT
			Id
		FROM
			new_competences_personne_poste_prestation
		WHERE
			Id_Personne=".$IdPersonneConnectee."
			AND Id_Poste IN (".implode(",",$TableauIdPoste).")
			AND Id_Prestation IN (".implode(",",$TableauIdPrestation).")
			AND 
			(
				SELECT
					COUNT(Id_Prestation)
				FROM
					new_competences_personne_prestation
				WHERE
					Id_Personne=".$IdPersonneConnectee."
					AND Date_Debut<='".$DateJour."'
					AND (Date_Fin>='".$DateJour."' OR Date_Fin<='0001-01-01')
			)>0 ";
	$ResultDroits=mysqli_query($bdd,$ReqDroits);
	$NbEnregDroits=mysqli_num_rows($ResultDroits);
	if($NbEnregDroits>0){$DroitsFormationPrestation=true;}
	
	return $DroitsFormationPrestation;
}

//Renvoie les informations concernant les responsables de la prestation
//Le résultat est renvoyé sous forme de tableau (Id_Poste, Id_Personne, NomPrenom, Email)
//Les paramêtres sont : Id_Prestation, Id_Pole
function GetTableau_ResponsablesPrestationPole($IdPrestation, $IdPole)
{
	global $bdd;
	
	$ResponsablesPrestationPole=array();
	$ReqPole="";
	if($IdPole > 0){$ReqPole=" AND new_competences_personne_poste_prestation.Id_Pole=".$IdPole;}
	
	$ReqResponsablePostePrestation="
		SELECT
			DISTINCT new_competences_personne_poste_prestation.Id_Poste AS ID_POSTE,
			new_competences_personne_poste_prestation.Backup AS BACKUP,
			CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) as NOMPRENOM,
			new_rh_etatcivil.EmailPro AS EMAILPRO,
			new_rh_etatcivil.Id AS ID_PERSONNE
		FROM
			new_competences_personne_poste_prestation,
			new_rh_etatcivil
		WHERE
			new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
			AND new_competences_personne_poste_prestation.Id_Prestation=".$IdPrestation.
			$ReqPole."
		ORDER BY
			new_competences_personne_poste_prestation.Id_Poste,
			new_competences_personne_poste_prestation.Backup ASC";
	$resultResponsablePostePrestation=mysqli_query($bdd,$ReqResponsablePostePrestation);
	while($RowResponsablePostePrestation=mysqli_fetch_array($resultResponsablePostePrestation))
	{
		$ResponsablesPrestationPole[]=array($RowResponsablePostePrestation['ID_POSTE'],$RowResponsablePostePrestation['ID_PERSONNE'],$RowResponsablePostePrestation['NOMPRENOM'],$RowResponsablePostePrestation['EMAILPRO']);
	}
	
	return $ResponsablesPrestationPole;
}

//Renvoie d'un tableau d'emails des responsables en fonction d'une tableau de responsables et d'un tableau de poste qui servira de filtre
//Le résultat est renvoyé sous forme de tableau (Email)
//Les paramêtres sont : TableauResponsables & TableauPostesFiltre
function GetTableau_EmailResponsablesPourPostes($TableauResponsablesPrestationPole,$TableauIdPostesFiltre)
{
	$TableauEmailResponsablesPourPostes=array();
	for($i=0;$i<count($TableauResponsablesPrestationPole);$i++)
	{
		//Test de l'existence d'un email pour le responsable
		if(trim($TableauResponsablesPrestationPole[$i][3]) != "")
		{
			if(array_search($TableauResponsablesPrestationPole[$i][0], $TableauIdPostesFiltre) !== false)
			{
				$TableauEmailResponsablesPourPostes[]=trim($TableauResponsablesPrestationPole[$i][3]);
			}
		}
	}
	
	return $TableauEmailResponsablesPourPostes;
}

/**
 * Get_EvaluationNoteTheorique
 *
 * Cette fonction permet de calculer l'évalutaion à une qualification en fonction de la note obtenue
 * Une formation autre que la formation interne (notée par QCM) renverra automatiquement la lettre enregistrée par défaut
 *
 * @param 	int 	$Id_Personne 		Identifiant de la personne
 * @param 	int 	$Id_Qualification 	Identifiant de la qualification liée au besoin
 * @param 	int 	$Note 				Note obtenue
 * @param	int		$TypeFormation		Type de la formation
 * @param	int		$Etat				Etat obtenue (0:Non passée, 1:Validée, -1:Echouée)
 * @return 	String 	$LettreRetournee	Lettre Obtenue
 *
 * @author Rémy PARRAN <rparran@aaa-aero.com>
 */
function Get_EvaluationNoteTheorique($Id_Personne, $Id_Qualification, $Note, $Etat, $TypeFormation)
{
	global $IdTypeFormationInterne;
	
	$LettreRetournee="";
	
	//Récupération du tableau (Id_Metier,Métier,Col) de la personne
	$Metier_Personne=Get_Metier($Id_Personne);
	$Id_Metier_Personne=$Metier_Personne[0];
	$Col_Metier_Personne=$Metier_Personne[2];
	if($Col_Metier_Personne==""){$Col_Metier_Personne="Blanc";}
	
	//Récupération de la lettre théorique en fonction de la qualitication et du métier de la personne
	$LettreTheorique=Get_LettreMetierQualification($Id_Qualification, $Id_Metier_Personne);
	//Compilation en fonction du col, de la note et de la lettre
	switch($Col_Metier_Personne)
	{
		case "Bleu":
			$NoteMini=70;
			break;
		default:
			$NoteMini=80;
			break;
	}
	//Si Accueil général HSE alors Note=80 pour tous
	if($Id_Qualification==2750 || $Id_Qualification==3777){$NoteMini=80;}
	
	//Cas de l'échec, renvoie d'une lettre ""
	//Changer la première ligne si la qualité décide de mettre les lettres manuellement et pas automatiquement calculées
	if($TypeFormation==$IdTypeFormationInterne){
		if($Note>0){
			if($Note>=$NoteMini){$LettreRetournee=$LettreTheorique;}
		}
		else{
			if($Etat!=-1){$LettreRetournee=$LettreTheorique;}
		}
	}
	else{
		if($Etat!=-1){$LettreRetournee=$LettreTheorique;}
	}
	
	//Cas du test d'anglais --> toujours réussi avec Note = Low / Medium / High
	if($Id_Qualification==22){
		if($Note<50){$LettreRetournee="Low";}
		elseif($Note<80){$LettreRetournee="Medium";}
		elseif($Note>=80){$LettreRetournee="High";}
	}
	return $LettreRetournee;
}

/**
 * Set_EvaluationNote
 *
 * Cette fonction permet de mettre à jour les différentes tables en fonction de la note obtenue au QCM (interne ou externe)
 *
 * @param 	int 	$Id_Besoin 							Identifiant du besoin
 * @param 	int 	$Id_Personne 						Identifiant de la personne
 * @param 	int 	$Id_Qualification 					Identifiant de la qualification liée au besoin
 * @param 	int		$Id_Session_Personne_Qualification	Identifiant de la session de la personne en fonction de la qualification
 * @param 	int 	$Note 								Note obtenue
 * @param	int		$Etat								Etat obtenu (0:Non passée, 1:Validée, -1:Echouée)
 * @param	int		$TypeFormation						Type de la formation
 * @param	bool	$MAJ_Manuelle						MAJ Manuelle ou pas
 * @param	string	$Motif								Raison de la non réussite
 *
 * @author Rémy PARRAN <rparran@aaa-aero.com>
 */
function Set_EvaluationNote($Id_Besoin, $Id_Personne, $Id_Qualification, $Id_Session_Personne_Qualification, $Note, $Etat, $TypeFormation, $Motif, $MAJ_Manuelle)
{
	global $bdd;
	global $DateJour;
	global $IdPersonneConnectee;
	global $IdTypeFormationInterne;
	
	$SQL_MAJ_Manuelle="";
	if($MAJ_Manuelle)
	{
	    $SQL_MAJ_Manuelle="
            Id_Personne_MAJ_Manuelle='".$IdPersonneConnectee."',
            Date_MAJ_Manuelle='".$DateJour."',
            ModifManuelle=1,";
	}
	
	//Cas du test d'anglais --> toujours réussi avec Note = Low / Medium / High
	if($Id_Qualification==22){
		$Etat=1;
	}
	
	$laNote=$Note;
	if($Note=="NA"){$laNote=0;}
	$LettreEvaluationNoteTheorique=Get_EvaluationNoteTheorique($Id_Personne, $Id_Qualification, $laNote, $Etat, $TypeFormation);
	if($Note==""){$Note="NA";}
	
	//Pas de modif pour les lettres Q et S (vu avec Anais) en attendant le développement de la partie 4 
	
	//Lorsque les besoins ont été créés suite à une mauvaise note ou une absence sur une session
	//Si on décide de remettre en présence la personne :
	//Suppression des besoins et des B de la gestion des compétences
	//------------------------------------------------------------------------------------------
	$ReqIdFormation="SELECT Id_Formation FROM form_besoin WHERE Id=".$Id_Besoin;
	$ResultIdFormation=mysqli_query($bdd, $ReqIdFormation);
	$RowIdFormation=mysqli_fetch_array($ResultIdFormation);
	
	//Suppression des B
	$ReqSuppBCompetences="
		UPDATE
			new_competences_relation
		SET
			Suppr=1,
            Id_Modificateur=".$IdPersonneConnectee.",
			Date_Modification='".$DateJour."'
		WHERE
			Id_Besoin IN
			(
				SELECT
					Id
				FROM
					form_besoin
				WHERE
					Id_Formation=".$RowIdFormation['Id_Formation']."
					AND Id_Personne=".$Id_Personne."
					AND Id <> ".$Id_Besoin."
					AND Suppr=0
					AND Traite=0
					AND Commentaire<>'Suite à une formation liée'
			)";
	$ResultSuppBCompetences=mysqli_query($bdd, $ReqSuppBCompetences);
	
	//Suppression des Besoins
	$ReqSuppBesoin="
		UPDATE
			form_besoin
		SET
			Suppr=1,
			Motif_Suppr='Depuis la fonction Set_EvaluationNote',
            Id_Personne_MAJ=".$IdPersonneConnectee.",
			Date_MAJ='".$DateJour."'
		WHERE
			Id_Formation=".$RowIdFormation['Id_Formation']."
			AND Id_Personne=".$Id_Personne."
			AND Id <> ".$Id_Besoin."
			AND Traite=0
			AND Commentaire<>'Suite à une formation liée'
			AND Suppr=0";
	$ResultSuppBesoin=mysqli_query($bdd, $ReqSuppBesoin);
	
	//Récupération de la date de réalisation du QCM, on suppose que c'est le dernier jour de session 
	$ReqDateSession="
        SELECT
            DateSession 
		FROM
            form_session_date 
		WHERE
            Suppr=0 
		    AND Id_Session IN
            (
    			SELECT Id_Session 
                FROM form_session_personne
                WHERE Id IN (SELECT Id_Session_Personne	FROM form_session_personne_qualification WHERE Id=".$Id_Session_Personne_Qualification.")
            )
		ORDER BY
            DateSession DESC ";
	$ResultDateSession=mysqli_query($bdd, $ReqDateSession);
	$nbDateSession=mysqli_num_rows($ResultDateSession);
	
	$DateSession=$DateJour;
	if($nbDateSession>0)
	{
		$RowDateSession=mysqli_fetch_array($ResultDateSession);
		$DateSession=$RowDateSession['DateSession'];
	}
	//------------------------------------------------------------------------------------------
	if($Etat<>0){
		switch($LettreEvaluationNoteTheorique)
		{
			case "Low":
			case "Medium":
			case "High":
				//MAJ dans la gestion des compétences
				$ReqCompetencesMAJ="
					UPDATE
						new_competences_relation
					SET
						Date_Debut='".$DateSession."',
						Resultat_QCM='".$Note."',
						Evaluation='".$LettreEvaluationNoteTheorique."',
						Date_QCM='".$DateSession."',
						Id_Modificateur=".$IdPersonneConnectee.",
						Date_Modification='".$DateJour."',".
                        $SQL_MAJ_Manuelle."
						Suppr=0
					WHERE
						Evaluation NOT IN ('Q','S')
						AND Id_Besoin=".$Id_Besoin."
						AND Id_Qualification_Parrainage=".$Id_Qualification;
				$ResultCompetencesMAJ=mysqli_query($bdd,$ReqCompetencesMAJ);
				
				$reqBesoin="UPDATE 
								form_besoin 
							SET Suppr=0
							WHERE Id=".$Id_Besoin;
				$ResultBesoin=mysqli_query($bdd,$reqBesoin);
				break;
			case "L":
			case "T":
				//MAJ dans la gestion des compétences
				$ReqCompetencesMAJ="
					UPDATE
						new_competences_relation
					SET
						Resultat_QCM='".$Note."',
						Evaluation='".$LettreEvaluationNoteTheorique."',
						Date_QCM='".$DateSession."',
						Id_Modificateur=".$IdPersonneConnectee.",
						Date_Modification='".$DateJour."',".
                        $SQL_MAJ_Manuelle."
						Suppr=0
					WHERE
						Evaluation NOT IN ('Q','S')
						AND Id_Besoin=".$Id_Besoin."
						AND Id_Qualification_Parrainage=".$Id_Qualification;
				$ResultCompetencesMAJ=mysqli_query($bdd,$ReqCompetencesMAJ);
				
				$reqBesoin="UPDATE 
								form_besoin 
							SET Suppr=0
							WHERE Id=".$Id_Besoin;
				$ResultBesoin=mysqli_query($bdd,$reqBesoin);
				break;
			case "S":
			case "V":
			case "X":
				//MAJ dans la gestion des compétences
				$ReqQualification="
					SELECT
						Duree_Validite
					FROM
						new_competences_qualification
					WHERE
						Id=".$Id_Qualification;
				$ResultQualification=mysqli_query($bdd,$ReqQualification);
				$RowQualification=mysqli_fetch_array($ResultQualification);
				
				$ReqCompetencesMAJ="
					UPDATE
						new_competences_relation
					SET
						Resultat_QCM='".$Note."',
						Evaluation='".$LettreEvaluationNoteTheorique."',
						Date_QCM='".$DateSession."',".
                        $SQL_MAJ_Manuelle."
						Suppr=0,
                        Id_Modificateur=".$IdPersonneConnectee.",
			            Date_Modification='".$DateJour."',
						Date_Debut='".$DateSession."',";
				if($RowQualification['Duree_Validite']<>0){
					$ReqCompetencesMAJ.="Date_Fin=DATE_ADD('".$DateSession."', INTERVAL ".$RowQualification['Duree_Validite']." MONTH),";
				}
				else{
					$ReqCompetencesMAJ.="Sans_Fin='Oui',";
				}
				$ReqCompetencesMAJ.="
                        Id_Modificateur=".$IdPersonneConnectee.",
						Date_Modification='".$DateJour."'
					WHERE
						Evaluation NOT IN ('Q','S')
						AND Id_Besoin=".$Id_Besoin."
						AND Id_Qualification_Parrainage=".$Id_Qualification;
				$ResultCompetencesMAJ=mysqli_query($bdd,$ReqCompetencesMAJ);
				
				$reqBesoin="UPDATE 
								form_besoin 
							SET Suppr=0
							WHERE Id=".$Id_Besoin;
				$ResultBesoin=mysqli_query($bdd,$reqBesoin);
				break;
			case "":
				//MAJ dans la gestion des compétences
				$ReqCompetencesMAJ="
					UPDATE
						new_competences_relation
					SET
						Resultat_QCM='".$Note."',
						Evaluation='".$LettreEvaluationNoteTheorique."',
						Date_QCM='".$DateSession."',
						Id_Modificateur=".$IdPersonneConnectee.",
						Date_Modification='".$DateJour."',".
                        $SQL_MAJ_Manuelle."
						Suppr=0
					WHERE
						Id_Besoin=".$Id_Besoin."
						AND Id_Qualification_Parrainage=".$Id_Qualification;
				$ResultCompetencesMAJ=mysqli_query($bdd,$ReqCompetencesMAJ);
				
				$reqBesoin="UPDATE 
								form_besoin 
							SET Suppr=0
							WHERE Id=".$Id_Besoin;
				$ResultBesoin=mysqli_query($bdd,$reqBesoin);
				
				//Si Echec (Etat==-1)
				//Génération d'un nouveau besoin (Workflow des besoins)
				//Et dans la gestion des compétences
				//En vérifiant si le besoin n'est pas déjà crée
				//###OLD### Set_Besoin_Formation($Id_Besoin, $Id_Personne, $Id_Qualification, $Motif);
				Creer_BesoinsFormations_PersonnePrestationMetier($Id_Personne, 0, 0, 0, $Motif, $Id_Besoin);
				break;
		}
	}
	else{
		$Note="";
		$ReqCompetencesMAJ="
			UPDATE
				new_competences_relation
			SET
				Suppr=1,
                Id_Modificateur=".$IdPersonneConnectee.",
				Date_Modification='".$DateJour."' 
			WHERE
				Id_Besoin=".$Id_Besoin."
				AND Id_Qualification_Parrainage=".$Id_Qualification;
		$ResultCompetencesMAJ=mysqli_query($bdd,$ReqCompetencesMAJ);
	}
	//Mise à jour des la table form_session_personnne_qualification
	$ReqSessionPersonneQualificationMAJ="
		UPDATE
			form_session_personne_qualification
		SET
			Resultat='".$Note."',
			Etat=".$Etat."
		WHERE
			Id=".$Id_Session_Personne_Qualification;
	$ResultSessionPersonneQualificationMAJ=mysqli_query($bdd,$ReqSessionPersonneQualificationMAJ);
}

/**
 * Get_NbBesoinExistant
 *
 * Cette fonction permet de récupérer le nombre de besoin existant pour une personne et une formation (besoin non encore traité ou en cours)
 *
 * @param 	int $Id_Personne 	Identifiant de la personne
 * @param 	int	$Id_Formation	Identifiant de la formation
 * @return 	int					Nombre de besoin en cours
 *
 * @author Rémy PARRAN <rparran@aaa-aero.com>
 */
function Get_NbBesoinExistant($Id_Personne, $Id_Formation)
{
	global $bdd;
	
	//Vérification si le besoin n'a pas déjà été créé
	$ReqBesoinIdentiqueExistant="
		SELECT
			Id
		FROM
			form_besoin
		WHERE
			(Id_Formation=".$Id_Formation."
			OR Id_Formation IN (
						SELECT Id_Formation  
						FROM form_formationequivalente_formationplateforme 
						WHERE 
						form_formationequivalente_formationplateforme.Id_FormationEquivalente IN 
						(SELECT Id_FormationEquivalente  
						FROM form_formationequivalente_formationplateforme 
						LEFT JOIN form_formationequivalente
						ON form_formationequivalente.Id=form_formationequivalente_formationplateforme.Id_FormationEquivalente
						WHERE 
						form_formationequivalente.Suppr=0
						AND form_formationequivalente.Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=form_besoin.Id_Prestation LIMIT 1)
						AND form_formationequivalente_formationplateforme.Id_Formation=".$Id_Formation.")
				)
			)
			AND Id_Personne=".$Id_Personne."
			AND Valide<>-1
			AND Traite IN (0,1,2)
			AND Suppr=0";
	$ResultBesoinIdentiqueExistant=mysqli_query($bdd,$ReqBesoinIdentiqueExistant);
	$NbBesoinIdentiqueExistant=mysqli_num_rows($ResultBesoinIdentiqueExistant);
	
	return $NbBesoinIdentiqueExistant;
}

/**
 * Get_MeilleureFormationPourQualification
 *
 * Cette fonction permet de renvoyer la meilleure formation pour une qualification
 * Renvoie l'Id_Formation par défaut si pas de formation trouvée avec pour seule qualification celle passée en paramètre
 *
 * @param 	int 	$Id_Qualification			Identifiant de la qualification
 * @param 	int 	$Id_Formation_Par_Defaut	Identifiant de la formation par défaut si existante (0 sinon)
 * @return 	int									Identifiant de la la formation
 *
 * @author Rémy PARRAN <rparran@aaa-aero.com>
 */
function Get_MeilleureFormationPourQualification($Id_Qualification, $Id_Formation_Par_Defaut, $Id_Personne)
{
	global $bdd;
	
	$IdMeilleureFormationPourQualification=$Id_Formation_Par_Defaut;
	
	$ResultatMySQL_FormationsPourQualification=Get_ResultMysql_FormationsPourQualification($Id_Qualification, $Id_Personne);
	//Parcours de toutes les formations probables
	while($RowMySQL_FormationsPourQualification=mysqli_fetch_array($ResultatMySQL_FormationsPourQualification))
	{
		$ReqQualifFormation="
			SELECT
				Id_Qualification
			FROM
				form_formation_qualification
			WHERE
				Id_Formation=".$RowMySQL_FormationsPourQualification[0]."
				AND Suppr=0
				AND Masquer=0 ";
		$ResultQualifFormation=mysqli_query($bdd,$ReqQualifFormation);
		$NbQualifFormation=mysqli_num_rows($ResultQualifFormation);
		
		if($NbQualifFormation==1)
		{
			$IdMeilleureFormationPourQualification=$RowMySQL_FormationsPourQualification[0];
			break;
		}
	}
	
	return $IdMeilleureFormationPourQualification;
}

/**
 * Get_ResultMysql_FormationsPourQualification
 *
 * Cette fonction permet renvoyer le resultat MySQL des formations pour une qualification
 *
 * @param 	int 		$Id_Qualification	Identifiant de la qualification
 * @return 	resource	Resultat de la requête MYSQL concernant les formations adéquates
 *
 * @author Rémy PARRAN <rparran@aaa-aero.com>
 */
function Get_ResultMysql_FormationsPourQualification($Id_Qualification, $Id_Personne)
{
	global $bdd;
	
	$PlateformesPersonne=array();
	$ResultPlateformesPersonne=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne=".$Id_Personne);
	while($RowPlateformesPersonne=mysqli_fetch_array($ResultPlateformesPersonne)){array_push($PlateformesPersonne, $RowPlateformesPersonne[0]);}
	
	$ReqMeilleureFormationPourQualification="
		SELECT
			form_formation_qualification.Id_Formation,
			form_formation.Id_TypeFormation,
			form_formation.Recyclage
		FROM
			form_formation_qualification
			LEFT JOIN form_formation ON form_formation_qualification.Id_Formation=form_formation.Id
		WHERE
			form_formation_qualification.Suppr=0
			AND form_formation_qualification.Masquer=0
			AND form_formation_qualification.Id_Qualification=".$Id_Qualification."
			AND
			(
				form_formation.Id_Plateforme=0
				OR form_formation.Id_Plateforme IN (".implode(",",$PlateformesPersonne).")
			)";
	$ResultMeilleureFormationPourQualification=mysqli_query($bdd,$ReqMeilleureFormationPourQualification);
	return $ResultMeilleureFormationPourQualification;
}

/**
 * Get_Metier
 *
 * Cette fonction permet de récupérer le métier d'une personne (futur si existe par défaut)
 *
 * @param 	int $Id_Personne 	Identifiant de la personne
 * @return 	array				Tableau contenant l'Id_Metier, Le Libellé, le Col de la personne et s'il s'agit d'un métier normal ou futur
 *
 * @author Rémy PARRAN <rparran@aaa-aero.com>
 */
function Get_Metier($Id_Personne)
{
	global $bdd;
	
	$ReqMetierPersonne="
		SELECT
			new_competences_personne_metier.Id_Metier AS ID_METIER,
			new_competences_metier.Libelle as LIBELLE_METIER,
			new_competences_metier.Col as COL_METIER,
			new_competences_personne_metier.Futur AS FUTUR
		FROM
			new_competences_personne_metier
			LEFT JOIN new_competences_metier ON new_competences_metier.Id=new_competences_personne_metier.Id_Metier
		WHERE
			new_competences_personne_metier.Id_Personne=".$Id_Personne."
		ORDER BY
			FUTUR DESC";
	$ReqMetierPersonne;
	$ResultMetierPersonne=mysqli_query($bdd,$ReqMetierPersonne);
	$RowMetierPersonne=mysqli_fetch_array($ResultMetierPersonne);
	$RowMetierPersonne[0];
	
	return array($RowMetierPersonne[0],$RowMetierPersonne[1],$RowMetierPersonne[2],$RowMetierPersonne[3]);
}

/**
 * Get_LesMetiersFutur
 *
 * Cette fonction permet de récupérer le métier d'une personne (futur si existe par défaut)
 *
 * @param 	int $Id_Personne 	Identifiant de la personne
 * @return 	array				Tableau contenant l'Id_Metier, Le Libellé, le Col de la personne et s'il s'agit d'un métier normal ou futur
 *
 * @author Rémy PARRAN <rparran@aaa-aero.com>
 */
function Get_LesMetiersFutur($Id_Personne)
{
	global $bdd;
	
	$ReqMetierPersonne="
		SELECT
			new_competences_personne_metier.Id_Metier AS ID_METIER,
			new_competences_metier.Libelle as LIBELLE_METIER,
			new_competences_metier.Col as COL_METIER,
			new_competences_personne_metier.Futur AS FUTUR
		FROM
			new_competences_personne_metier
			LEFT JOIN new_competences_metier ON new_competences_metier.Id=new_competences_personne_metier.Id_Metier
		WHERE
			new_competences_personne_metier.Id_Personne=".$Id_Personne."
			AND new_competences_personne_metier.Futur=1";
	$ReqMetierPersonne;
	$ResultMetierPersonne=mysqli_query($bdd,$ReqMetierPersonne);

	return $ResultMetierPersonne;
}

/**
 * Get_LesMetiersNonFutur
 *
 * Cette fonction permet de récupérer le métier d'une personne (futur si existe par défaut)
 *
 * @param 	int $Id_Personne 	Identifiant de la personne
 * @return 	array				Tableau contenant l'Id_Metier, Le Libellé, le Col de la personne et s'il s'agit d'un métier normal ou futur
 *
 * @author Rémy PARRAN <rparran@aaa-aero.com>
 */
function Get_LesMetiersNonFutur($Id_Personne)
{
	global $bdd;
	
	$ReqMetierPersonne="
		SELECT
			new_competences_personne_metier.Id_Metier AS ID_METIER,
			new_competences_metier.Libelle as LIBELLE_METIER,
			new_competences_metier.Col as COL_METIER,
			new_competences_personne_metier.Futur AS FUTUR
		FROM
			new_competences_personne_metier
			LEFT JOIN new_competences_metier ON new_competences_metier.Id=new_competences_personne_metier.Id_Metier
		WHERE
			new_competences_personne_metier.Id_Personne=".$Id_Personne."
			AND new_competences_personne_metier.Futur=0";
	$ReqMetierPersonne;
	$ResultMetierPersonne=mysqli_query($bdd,$ReqMetierPersonne);

	return $ResultMetierPersonne;
}

/**
 * Get_LettreMetierQualification
 *
 * Cette fonction permet de récupérer la lettre à mettre dans la gestion des compétences en fonction du métier et de la qualification
 *
 * @param 	int $Id_Qualification 	Identifiant de la qualification
 * @param 	int $Id_Metier 			Identifiant du métier
 * @return 	int						Métier
 *
 * @author Rémy PARRAN <rparran@aaa-aero.com>
 */
function Get_LettreMetierQualification($Id_Qualification, $Id_Metier)
{
	global $bdd;
	
	//Lettre par défaut
	$ReqLettreParDefautQualification="
		SELECT
			Lettre_Theorie
		FROM
			new_competences_qualification
		WHERE
			Id=".$Id_Qualification;
	$ResultLettreParDefautQualification=mysqli_query($bdd,$ReqLettreParDefautQualification);
	$RowLettreParDefautQualification=mysqli_fetch_array($ResultLettreParDefautQualification);
	$LettreAInscrire=$RowLettreParDefautQualification[0];
	//Lettre non par défaut définie pour un métier
	if($Id_Metier<>"")
	{
		$ReqLettreQualificationMetier="
			SELECT
				Lettre
			FROM
				new_competences_qualification_metier_lettre
			WHERE
				Id_Metier=".$Id_Metier."
				AND Id_Qualification=".$Id_Qualification."
				AND Theorique_Pratique='T'
				AND Suppr=0";
		$ResultLettreQualificationMetier=mysqli_query($bdd,$ReqLettreQualificationMetier);
		if(mysqli_num_rows($ResultLettreQualificationMetier)>0)
		{
			$RowLettreQualificationMetier=mysqli_fetch_array($ResultLettreQualificationMetier);
			$LettreAInscrire=$RowLettreQualificationMetier[0];
		}
	}
	if($LettreAInscrire==""){$LettreAInscrire="L";}
	return $LettreAInscrire;
}

/**
 * get_FormationsDeAssociationMetier
 * 
 * Recupere les identifinats de formation sous forme de ressource 
 * a partir de la personne et de son metier 
 * 
 * @param int $Id_Personne Identifiant de la personne
 * @param int $Id_Metier Identifiant du metier
 * @return resource Les identifiants de formation
 */
function get_FormationsDeAssociationMetier($Id_Personne, $Id_Metier) {
    $req = "
        SELECT DISTINCT Id_Formation
        FROM form_prestation_metier_formation
        WHERE
            Id_Prestation IN ( SELECT DISTINCT Id_Prestation FROM new_competences_personne_prestation WHERE Id_Personne = ".$Id_Personne." )  
        AND Id_Metier = ".$Id_Metier.";
    ";

    return getRessource($req);    
}

/**
 * get_FormationsDeBesoinsPersonne
 * 
 * retourne les Id formation en fonction de 
 * l'identifiant d\'une personne et d\'une prestation
 * 
 * @param int $Id_Personne Identifiant de la personne
 * @param int $Id_Presta Identifiant de la prestation
 * @return resource
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function get_FormationsDeBesoinsPersonne($Id_Personne, $Id_Presta) {
   $req = "
        SELECT DISTINCT Id_Formation 
        FROM `form_besoin` 
        WHERE 
			Suppr=0 
        AND Id_Personne = ".$Id_Personne." 
        AND Id_Prestation = ".$Id_Presta."
    ";
   
   return getRessource($req);
}

/**
 * get_Prestations
 * 
 * R\écup\ère les identifiants de prestations a partir d\'un identifiant plateforme
 * 
 * @param int $Id_Plateformes Identifiant Plateforme
 * @return resource
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function get_Prestations($Id_Plateformes) {
	$req = "
			SELECT Id
			FROM
					new_competences_prestation
			WHERE
				  Id_Plateforme = ".$Id_Plateformes."
		;";
	
	return getRessource($req);
}

/**
 * get_liste_identifiantsQualifications
 * 
 * Récupère les identifinats de qualification en fonction d'une formation.
 * Retourne une liste d'identifiants séparés par une virgule
 * 
 * @param int $Id_Formation Identifiant de la Formation
 * @return string la liste des identifiants de qualifications
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function get_liste_identifiantsQualifications($Id_Formation) {
	$req = "
		SELECT Id_Qualification FROM form_formation_qualification
		WHERE Id_Formation = ".$Id_Formation."
		AND Suppr=0
		AND Masquer=0;
	";
	$ressource = getRessource($req);
	
	$arr = array();
	while($row = mysqli_fetch_array($ressource))
		array_push($arr, $row['Id_Qualification']);
	
	return implode(',', $arr);
}

/**
 * get_Personnes_besoinPrestationFormation
 * 
 * Récupère les identifinat des personnes qui ont un besoin sur une formation
 * 
 * @param int $Id_Prestation Identifinat de la prestation
 * @param int $Id_Formation Identifinat de la formation
 * @return resource
 */
function get_Personnes_besoinPrestationFormation($Id_Prestation, $Id_Formation) {
	$req = "
			SELECT DISTINCT Id_Personne FROM form_besoin
			WHERE Id_prestation = ".$Id_Prestation."
			AND Id_Formation = ".$Id_Formation."
			AND Suppr=0;
		";
	return  getRessource($req);
}

/**
 * Supprimer_lien_MetierPrestaFormation
 * 
 * Supprime le lien Metier Prestation Formation situ\é dans le table form_prestation_metier_formation.
 * ATTENTION : Il faut renseigner au moins 1 des 3 paramètres sinon la suppression ne serra pas executee. 
 * 
 * @param int $Id_Prestation Identifiant  de la prestation
 * @param int $Id_Metier Identifiant du m\étier
 * @param int $Id_Formation Identifiant de la formation
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function supprimer_lien_MetierPrestaFormation($Id_Prestation = -1, $Id_Metier = -1, $Id_Formation = -1)
{
    global $IdPersonneConnectee;
    global $DateJour;
    
	$params = " 1 ";
	
	if ($Id_Prestation > 0)
		$params .= "AND Id_Prestation = ".$Id_Prestation." ";
	
	if($Id_Metier > 0)
		$params .= "AND Id_Metier = ".$Id_Metier." ";
	
	if($Id_Formation > 0)
		$params .= "AND Id_Formation = ".$Id_Formation." ";

	$params .= ";";

	
	$req = "
		UPDATE
            form_prestation_metier_formation
		SET
            Suppr=1,
            Id_Personne_MAJ=".$IdPersonneConnectee.",
			Date_MAJ='".$DateJour."'
		WHERE
		".$params;
		//Oblige à ce que au moins 1 des 3 paramètres soit renseigné
		if (strlen($params) > 4 )
			getRessource($req);
}

/**
 * Supprimer_BesoinsFormations
 * 
 * Supprime les besoin en formation
 * 
 * 
 * @param int $Id_Prestation 	Identifiant de la prestation
 * @param int $Id_Pole 			Identifiant du Pole
 * @param int $Id_Formation 	Identifiant de la Formation
 * @param int $Id_Personne 		Identifiant de la personne
 * @param int $Provenance		Provenance de la suppression
 * 
 * @return resource Les identifiants besoin affectés par la suppression
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function Supprimer_BesoinsFormations($Id_Prestation=-1, $Id_Formation, $Id_Pole = -1, $Id_Personne = -1, $Provenance,$Id_Metier = -1)
{
    global $IdPersonneConnectee;
    global $DateJour;
    
	//Supprimer les besoins
	$params = "
        (Valide=0 OR (Valide=1 AND Traite=0)) 
        AND Id_Formation = ".$Id_Formation." 
		";
	
	if($Id_Prestation > 0){$params .= "AND Id_Prestation = ".$Id_Prestation." ";}
	if($Id_Personne> 0){$params .= "AND Id_Personne = ".$Id_Personne." ";}
	if($Id_Pole > 0){$params .= "AND Id_Pole=".$Id_Pole." ";}
	if($Id_Metier > 0)
	{
		$params .= "AND (
			SELECT new_competences_personne_metier.Id_Metier 
			FROM new_competences_personne_metier 
			WHERE new_competences_personne_metier.Id_Personne=form_besoin.Id_Personne
			ORDER BY Futur DESC LIMIT 1)=".$Id_Metier." ";
	}
	$params .= ";";
	
	//Construction des requêtes
	$req ="
		UPDATE
            form_besoin
		SET
            Suppr=1,
            Motif_Suppr='".$Provenance."',
            Id_Personne_MAJ=".$IdPersonneConnectee.",
            Date_MAJ='".$DateJour."'
		WHERE  
		".$params;

	$reqCibles = "
        SELECT Id FROM form_besoin
        WHERE
        ".$params;
	
	//Récupération des cibles
	$ressourceCibles = getRessource($reqCibles);
	
	//Suppression
	getRessource($req);
	
	//retourne les cibles
	return $ressourceCibles;
}

/**
 * supprimer_lesB
 * 
 * Supprime les 'B' dans la table des relations.
 * 
 * @param int $Id_Personne Identifiant de la personne
 * @param string $Ids_Qualification liste d'identifiants de qualification sous forme de chaine de caracteres separes par une virgule
 * @param string $Ids_Besoin liste d'identifiants de besoin
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com> 
 */

function supprimer_lesB($Id_Personne, $Ids_Qualification, $Ids_Besoin)
{
    global $IdPersonneConnectee;
    global $DateJour;
    
	//Supprimer les 'B' du profil
    if ($Ids_Qualification <> "" && $Id_Personne > 0 && $Ids_Besoin <> "") {
		$req = "
			UPDATE
                new_competences_relation
			SET
                Suppr=1,
                Id_Modificateur=".$IdPersonneConnectee.",
                Date_Modification='".$DateJour."'
			WHERE
				Id_Personne = ".$Id_Personne."
				AND Id_Qualification_Parrainage IN ( ".$Ids_Qualification." )
                AND Id_Besoin IN (".$Ids_Besoin.")
                AND Evaluation = 'B'
				AND ((SELECT Traite FROM form_besoin WHERE Id=Id_Besoin LIMIT 1)<2 
					OR (SELECT Traite FROM form_besoin WHERE Id=Id_Besoin LIMIT 1)=5
				)
			 ;
		";
		getRessource($req);
	}
}

/**
 * Creer_BesoinsFormations_PersonnePrestationMetier
 *
 * Cette fonction permet de créer les besoins en formations en fonction d'une personne, d'une métier et d'une prestation
 * Cette fonction remplie également les B dans la gestion des compétences
 * On peut choisir de limiter la fonction à une seule formation spécifique (au lieu de prendre en compte tout ce qui est défini dans la table form_prestation_metier_formation
 *
 * @param 	int 	$Id_Personne 			Identifiant de la personne
 * @param 	int 	$Id_Prestation			Identifiant de la prestation (mettre 0 si besoin spécifié en dernier paramètre)
 * @param 	int 	$Id_Pole 				Identifiant du pôle (mettre 0 si besoin spécifié en dernier paramètre)
 * @param 	int 	$Id_Metier 				Identifiant du métier (mettre 0 si besoin spécifié en dernier paramètre)
 * @param 	string 	$Motif 					Motif
 * @param	int		$Id_Besoin_Specifie		Identifiant du besoin spécifié (sinon 0 et toutes les formations seront prises en compte pour ce métier et cette prestation)
 * @param   int     $Formation              Identifiant pour une formation spécifiée
 * @param   int     $SansMail               A 0 par défaut si on veut envoyer des mails aux responsables
 * @return
 *
 * @author Rémy PARRAN <rparran@aaa-aero.com>
 */
function Creer_BesoinsFormations_PersonnePrestationMetier($Id_Personne, $Id_Prestation, $Id_Pole, $Id_Metier, $Motif, $Id_Besoin_Specifie=0,$Id_Formation=0,$SansMail = 0)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $DateJour;
	global $TableauIdPostesCHE_COOE;
	global $LangueAffichage;
	
	if($Id_Besoin_Specifie==0)
	{
		$Plus="";
		if($Id_Formation<>0){$Plus=" AND Id_Formation=".$Id_Formation." ";}
		$ReqBesoinsFormations="
			SELECT
				Id_Formation,
				Obligatoire
			FROM
				form_prestation_metier_formation
			WHERE
				Id_Prestation=".$Id_Prestation."
				AND Id_Pole=".$Id_Pole."
				AND Id_Metier=".$Id_Metier."
                AND Suppr=0".
                $Plus;

		$ResultBesoinsFormations=mysqli_query($bdd, $ReqBesoinsFormations);
		$NbBesoinsFormations=mysqli_num_rows($ResultBesoinsFormations);
		
		$ID_PRESTATION=$Id_Prestation;
		$ID_POLE=$Id_Pole;
	}
	else
	{
		$ReqBesoin="
			SELECT
				Id_Demandeur,
				Id_Prestation,
				Id_Pole,
				Id_Formation,
				Id_Personne,
				Commentaire,
				Obligatoire,
				Id_Valideur 
			FROM
				form_besoin
			WHERE
				Id=".$Id_Besoin_Specifie;
		$ResultBesoin=mysqli_query($bdd,$ReqBesoin);
		$RowBesoin=mysqli_fetch_array($ResultBesoin);
		$ID_PRESTATION=$RowBesoin['Id_Prestation'];
		$ID_POLE=$RowBesoin['Id_Pole'];
		
		$ReqBesoinsFormations="
			SELECT ".
				$RowBesoin['Id_Formation']." AS Id_Formation,".
				$RowBesoin['Obligatoire']." AS Obligatoire
			FROM
				form_prestation_metier_formation
			LIMIT 1";
		$ResultBesoinsFormations=mysqli_query($bdd, $ReqBesoinsFormations);
		$NbBesoinsFormations=mysqli_num_rows($ResultBesoinsFormations);
	}
	
	$bBesoin=0;
	if($NbBesoinsFormations>0)
	{
		while($RowBesoinsFormations=mysqli_fetch_array($ResultBesoinsFormations))
		{	
			if(Get_NbBesoinExistant($Id_Personne, $RowBesoinsFormations['Id_Formation'])==0 && (Get_QualifAJour($Id_Personne, $RowBesoinsFormations['Id_Formation'])==0 || $Motif=="Renouvellement" || $Motif=="Suite à échec"))
			{
				$bBesoin=1;
				$Valide=0;
				if($RowBesoinsFormations['Obligatoire']==1 || $Motif=="Renouvellement"){$Valide=1;}
				$ReqInsertBesoinsFormation_Personne="
					INSERT INTO
						form_besoin
					(
						Id_Demandeur,
						Id_Prestation,
						Id_Pole,
						Id_Formation,
						Id_Personne,
						Date_Demande,
						Motif,
						Valide,
						Obligatoire,
						Id_Personne_MAJ,
						Date_MAJ
					)
					VALUES
					(
						".$IdPersonneConnectee.",
						".$ID_PRESTATION.",
						".$ID_POLE.",
						".$RowBesoinsFormations['Id_Formation'].",
						".$Id_Personne.",
						'".$DateJour."',
						'".addslashes($Motif)."',
						".$Valide.",
						".$RowBesoinsFormations['Obligatoire'].",
						".$IdPersonneConnectee.",
						'".$DateJour."'
					)";
				$ResultInsertBesoinsFormation_Personne=mysqli_query($bdd, $ReqInsertBesoinsFormation_Personne);
				
				$ID_BESOIN=mysqli_insert_id($bdd);
				
				if($RowBesoinsFormations['Obligatoire']==1 || $Motif=="Renouvellement")
				{
					//On crée les B dans la gestion des compétences uniquement si le besoin est validé (donc obligatoire)
					Creer_B_Competences_PersonneFormation($Id_Personne, $RowBesoinsFormations['Id_Formation'], $ID_BESOIN);
				}
			}
		}
	}
	
	if($SansMail==0){
		if($bBesoin>0)
		{
			//Envoie de l'email
			
			//Récupération de l'ensemble des responsables de chaque personne
			$reqResponsables="
				SELECT DISTINCT
					Id_Personne, 
					(SELECT EmailPro FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS EmailPro 
				FROM
					new_competences_personne_poste_prestation 
				WHERE
					Id_Poste IN (".implode(",",$TableauIdPostesCHE_COOE).") 
					AND Id_Prestation=".$ID_PRESTATION."
					AND Id_Pole=".$ID_POLE."  
					AND (SELECT EmailPro FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne)<>'' ";
			$ResultResponsables=mysqli_query($bdd,$reqResponsables);
			$nbResp=mysqli_num_rows($ResultResponsables);
			
			if($nbResp>0)
			{
				$Emails="";
				while($RowResp=mysqli_fetch_array($ResultResponsables)){$Emails.=$RowResp['EmailPro'].",";}
				$Emails=substr($Emails,0,-1);
				
				$Personne="";
				$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE Id=".$Id_Personne;
				$ResultPersonne=mysqli_query($bdd,$req);
				$nbPersonne=mysqli_num_rows($ResultPersonne);
				if($nbPersonne>0)
				{
					$RowPersonne=mysqli_fetch_array($ResultPersonne);
					$Personne=$RowPersonne['Personne'];
				}
			
				//Elaboration du mail
				$Headers='From: "QUALIPSO"<qualipso@aaa-aero.com>'."\n";
				$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
				if($LangueAffichage=="FR")
				{
					$Objet="Nouveaux besoins en formation pour ".$Personne;
					$Message="	<html>
									<head><title>Nouveaux besoins</title></head>
									<body>
										Bonjour,
										<br><br>
										<i>Cette boîte mail est une boîte mail générique</i>
										<br><br>
										Des nouveaux besoins en formation ont été créée pour ".$Personne." <br>
										Pensez à les consulter
										<br>
										Bonne journée.<br>
										Formation Extranet Daher industriel services DIS.
									</body>
								</html>";
				}
				else
				{
					$Objet="New training needs for ".$Personne;
					$Message="	<html>
									<head><title>New needs</title></head>
									<body>
										Hello,
										<br><br>
										<i>This mailbox is a generic mailbox</i>
										<br><br>
										New training needs have been created for ".$Personne." <br>
										Remember to consult them
										<br>
										Have a good day.<br>
										Training Extranet Daher industriel services DIS.
									</body>
								</html>";
				}

				if(mail($Emails,$Objet,$Message,$Headers,'-f qualipso@aaa-aero.com'))
					{}
			}
		}
	}
}

/**
 * Creer_B_Competences_PersonneFormation
 *
 * Cette fonction permet de créer les B pour une personne dans la gestion des compétences en fonction d'une formation et d'un besoin
 *
 * @param 	int 	$Id_Personne 	Identifiant de la personne
 * @param 	int 	$Id_Formation	Identifiant de la formation
 * @param 	int 	$Id_Besoin		Identifiant de la formation
 *
 * @author Rémy PARRAN <rparran@aaa-aero.com>
 */
function Creer_B_Competences_PersonneFormation($Id_Personne, $Id_Formation, $Id_Besoin)
{
	global $bdd;
	global $DateJour;
	global $IdPersonneConnectee;
	
	//Qualification liées à la formation
	$ReqQualifFormation="
		SELECT
			Id_Qualification
		FROM
			form_formation_qualification
		WHERE
			Id_Formation=".$Id_Formation."
			AND Suppr=0
			AND Masquer=0";
	$ResultQualifFormation=mysqli_query($bdd,$ReqQualifFormation);
	$NbQualifFormation=mysqli_num_rows($ResultQualifFormation);
	
	//Ajout d'un B dans la gestion des compétences pour toutes les qualifications liées à cette formation
	if($NbQualifFormation>0)
	{
		mysqli_data_seek($ResultQualifFormation,0);
		$ReqInsertBesoinGPEC="
				INSERT INTO
				new_competences_relation
				(
					Id_Personne,
					Type,
					Id_Qualification_Parrainage,
					Evaluation,
					Visible,
					Id_Besoin,
					Id_Modificateur,
					Date_Modification
				)
				VALUES
				";
		while($RowQualifFormation=mysqli_fetch_array($ResultQualifFormation))
		{
			$ReqInsertBesoinGPEC.="
				(".
				    $Id_Personne.",
					'Qualification',".
					$RowQualifFormation['Id_Qualification'].",
					'B',
					0,".
					$Id_Besoin.",".
					$IdPersonneConnectee.",".
					$DateJour.
				"),";
		}
		$ReqInsertBesoinGPEC=substr($ReqInsertBesoinGPEC,0,strlen($ReqInsertBesoinGPEC)-1);
		$ResultInsertBesoinGPEC=mysqli_query($bdd,$ReqInsertBesoinGPEC);
	}
}

/**
 * transferer_fichier
 * 
 * Permets d'uploader un fichier
 * 
 * @param string $fileFields Nom des champs de fichiers pour accèder aux meta-donnees. Permets ensuite d'acceder au nom, type, taile, etc... par exemple 'fichier_I_'.$rowL['Id']
 * @param string $DirFichier Chemin des fichiers
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 * @author Pauline Fauge <pfauge@aaa-aero.com>
 * @author Remy Parran <rparran@aaa-aero.com>
 */
function transferer_fichier($filename, $filename_tmp, $path) {
	$err_message="";

	//Controle de la taille du fichier
	if(filesize($filename_tmp)>$_POST['MAX_FILE_SIZE']) {
		$err_message="Le fichier est trop volumineux.";
		return;
	}

	//Se prémunir des caractères spéciaux dans les noms d efichiers
	$filename=strtr($filename, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
	
	//Si jamais l'utilisateur ajoute 2 fois le même fichier avec le même nom
	$filename="le ".date('j-m-y')." a ".date('H-i-s')." ".$filename;
	
	//Upload du fichier
	$path = $path.basename($filename);
	
	if(move_uploaded_file($filename_tmp, $path)) {
		$err_message="The file ".basename($filename)." has been uploaded";
		return $filename;
	}
	else{
		$err_message="There was an error uploading the file, please try again!";
	}
}

/**
 * transferer_fichier
 * 
 * Permets d'uploader un fichier
 * 
 * @param string $fileFields Nom des champs de fichiers pour accèder aux meta-donnees. Permets ensuite d'acceder au nom, type, taile, etc... par exemple 'fichier_I_'.$rowL['Id']
 * @param string $DirFichier Chemin des fichiers
 * 
 * @author Pauline Fauge
 */
function transferer_fichierV2($filename, $filename_tmp, $path) {
	$err_message="";

	//Controle de la taille du fichier
	if(filesize($filename_tmp)>$_POST['MAX_FILE_SIZE']) {
		$err_message="Le fichier est trop volumineux.";
		return;
	}

	//Se prémunir des caractères spéciaux dans les noms d efichiers
	$filename=strtr($filename, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");

	//Upload du fichier
	$path = $path.basename($filename);
	
	if(move_uploaded_file($filename_tmp, $path)) {
		$err_message="The file ".basename($filename)." has been uploaded";
		return $filename;
	}
	else{
		$err_message="There was an error uploading the file, please try again!";
	}
}


/**
 * DeconvoquerPersonnes
 *
 * Cette fonction permet de remettre le champs Convocation_Envoyee à 0 pour pouvoir informer que la convocation doit être renvoyée
 *
 * @param 	int 	$Id_Session 	Identifiant de la session
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function DeconvoquerPersonnes($Id_Session,$Id=0)
{
	global $bdd;
	
	$reqSuite="";
	if($Id<>0){$reqSuite="AND Id=".$Id." ";}
	
	$ReqSessionPersonne="
		UPDATE
			form_session_personne
		SET
			Convocation_Envoyee=0
		WHERE
			Id_Session=".$Id_Session."
			".$reqSuite."
			AND Suppr=0";
	$ResultSession=mysqli_query($bdd, $ReqSessionPersonne);
}

/**
 * envoyerMail
 * 
 * cette fonction a ete cree suite a la copie du code de l\'article suivant : https://openclassrooms.com/courses/e-mail-envoyer-un-e-mail-en-php
 * Attention a l\'utilisation des pieces jointes. Il faut fournir un tableau de tableau avec les champs nommés suivants
 *  chemin, nom, attachement
 *  
 *   voici un exemple :
 *   
 *   $PJ = array();
 *   
 *   $pj1 = array('chemin' => 'C:\\Users\\ASCHRICKE\\Pictures\\Pas trié\\', 'nom' => 'd76885b37c7969c3fecf861887987efc.jpg', 'MIME-Type' => 'image/jpeg', 'attachement' => encoderFichier('C:\\Users\\ASCHRICKE\\Pictures\\Pas trié\\d76885b37c7969c3fecf861887987efc.jpg'));
 *   $pj2 = array('chemin' => 'C:\\Users\\ASCHRICKE\\Pictures\\Pas trié\\', 'nom' => 'Dassault-Mirage-2000N.jpg', 'MIME-Type' => 'image/jpeg', 'attachement' => encoderFichier('C:\\Users\\ASCHRICKE\\Pictures\\Pas trié\\Dassault-Mirage-2000N.jpg'));
 *   $pj3 = array('chemin' => 'C:\\Users\\ASCHRICKE\\Pictures\\Pas trié\\', 'nom' => 'Fighter_Airplane_440908.jpg', 'MIME-Type' => 'image/jpeg', 'attachement' => encoderFichier('C:\\Users\\ASCHRICKE\\Pictures\\Pas trié\\Fighter_Airplane_440908.jpg'));
 *   
 *   array_push($PJ, $pj1);
 *   array_push($PJ, $pj2);
 *   array_push($PJ, $pj3);
 *   
 * 
 * @param string $destinataire Le ou les destinataires du mail (s\épar\és par une virgule
 * @param string $sujet Le sujet du mail
 * @param string $message_txt Le corps du mail en format texte plat
 * @param string $message_html Le corps du mail en format html
 * @param array $PJ Tableau de tableau structur\é contenant les informations des pi\èces jointes
 * 
 * @author Weaponsb
 */
function envoyerMail($destinataire, $sujet, $message_txt, $message_html, $PJ = Array()) {
	
    // regarde si il y a des Pieces Jointes
    if(count($PJ) > 0) {    
    	//$mail = 'pfauge@aaa-aero.com'; // Déclaration de l'adresse de destination.
    	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $destinataire)) // On filtre les serveurs qui présentent des bogues.
    	{
    		$passage_ligne = "\r\n";
    	}
    	else
    	{
    		$passage_ligne = "\n";
    	}
    	//=====Déclaration des messages au format texte et au format HTML.
    	//$message_txt = "Salut à tous, voici un e-mail envoyé par un script PHP.";
    	//$message_html = "<html><head></head><body><b>Salut à tous</b>, voici un e-mail envoyé par un <i>script PHP</i>.</body></html>";
    	//==========
    	
    	$attachements = array();
    
    	//=====Création de la boundary.
    	$boundary = "-----=".md5(rand());
    	$boundary_alt = "-----=".md5(rand());
    	//==========
    	
    	//=====Définition du sujet.
    	//$sujet = "Hey mon ami !";
    	//=========
    	
    	//=====Création du header de l'e-mail.
    	$header = "From: \"Formation Extranet AAA\"<qualipso@aaa-aero.com>".$passage_ligne;
    //	$header.= "Reply-to: \"La meme adresse\" <aschricke@aaa-aero.com>".$passage_ligne;
    	$header.= "MIME-Version: 1.0".$passage_ligne;
    	$header.= "Content-Type: multipart/mixed;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
    	//==========
    	
    	//=====Création du message.
    	$message = $passage_ligne."--".$boundary.$passage_ligne;
    	$message.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary_alt\"".$passage_ligne;
    	$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
    	//=====Ajout du message au format texte.
    	$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
    	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    	$message.= $passage_ligne.$message_txt.$passage_ligne;
    	//==========
    	
    	$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
    	
    	//=====Ajout du message au format HTML.
    	$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
    	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    	$message.= $passage_ligne.$message_html.$passage_ligne;
    	//==========
    	
    	//=====On ferme la boundary alternative.
    	$message.= $passage_ligne."--".$boundary_alt."--".$passage_ligne;
    	//==========
    	
    	foreach($PJ as $current_PJ_infos) {
    	//for ($curseur = 0; $curseur < count($attachements); $curseur++ ) {
    		$message.= $passage_ligne."--".$boundary.$passage_ligne;
    		
    		//=====Ajout de la pièce jointe.
    		$message.= "Content-Type: image/jpeg; name=\"".$current_PJ_infos['nom']."\"".$passage_ligne;
    		$message.= "Content-Transfer-Encoding: base64".$passage_ligne;
    		$message.= "Content-Disposition: attachment; filename=\"".$current_PJ_infos['nom']."\"".$passage_ligne;
    		$message.= $passage_ligne.$current_PJ_infos['attachement'].$passage_ligne.$passage_ligne;
    	}
    	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
    	
    	//==========
    }else {
        //Headers
        $header='From: "QUALIPSO"<qualipso@aaa-aero.com>'." \n";
        $header.='Content-Type: text/html; charset="iso-8859-1"'." \n";
        
        //Message html
        $message = $message_html;
    }
	
	
	//=====Envoi de l'e-mail.
	if ($destinataire <> "")
		return mail($destinataire,$sujet,$message,$header,'-f qualipso@aaa-aero.com');
	else
		return false;
	
	//==========
}

function envoyerMailExtranet($destinataire, $sujet, $message_txt, $message_html, $PJ = Array()) {
	
    // regarde si il y a des Pieces Jointes
    if(count($PJ) > 0) {    
    	//$mail = 'pfauge@aaa-aero.com'; // Déclaration de l'adresse de destination.
    	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $destinataire)) // On filtre les serveurs qui présentent des bogues.
    	{
    		$passage_ligne = "\r\n";
    	}
    	else
    	{
    		$passage_ligne = "\n";
    	}
    	//=====Déclaration des messages au format texte et au format HTML.
    	//$message_txt = "Salut à tous, voici un e-mail envoyé par un script PHP.";
    	//$message_html = "<html><head></head><body><b>Salut à tous</b>, voici un e-mail envoyé par un <i>script PHP</i>.</body></html>";
    	//==========
    	
    	$attachements = array();
    
    	//=====Création de la boundary.
    	$boundary = "-----=".md5(rand());
    	$boundary_alt = "-----=".md5(rand());
    	//==========
    	
    	//=====Définition du sujet.
    	//$sujet = "Hey mon ami !";
    	//=========
    	
    	//=====Création du header de l'e-mail.
    	$header = "From: \"Formation Extranet AAA\"<qualipso@aaa-aero.com>".$passage_ligne;
    //	$header.= "Reply-to: \"La meme adresse\" <aschricke@aaa-aero.com>".$passage_ligne;
    	$header.= "MIME-Version: 1.0".$passage_ligne;
    	$header.= "Content-Type: multipart/mixed;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
    	//==========
    	
    	//=====Création du message.
    	$message = $passage_ligne."--".$boundary.$passage_ligne;
    	$message.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary_alt\"".$passage_ligne;
    	$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
    	//=====Ajout du message au format texte.
    	$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
    	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    	$message.= $passage_ligne.$message_txt.$passage_ligne;
    	//==========
    	
    	$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
    	
    	//=====Ajout du message au format HTML.
    	$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
    	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    	$message.= $passage_ligne.$message_html.$passage_ligne;
    	//==========
    	
    	//=====On ferme la boundary alternative.
    	$message.= $passage_ligne."--".$boundary_alt."--".$passage_ligne;
    	//==========
    	
    	foreach($PJ as $current_PJ_infos) {
    	//for ($curseur = 0; $curseur < count($attachements); $curseur++ ) {
    		$message.= $passage_ligne."--".$boundary.$passage_ligne;
    		
    		//=====Ajout de la pièce jointe.
    		$message.= "Content-Type: image/jpeg; name=\"".$current_PJ_infos['nom']."\"".$passage_ligne;
    		$message.= "Content-Transfer-Encoding: base64".$passage_ligne;
    		$message.= "Content-Disposition: attachment; filename=\"".$current_PJ_infos['nom']."\"".$passage_ligne;
    		$message.= $passage_ligne.$current_PJ_infos['attachement'].$passage_ligne.$passage_ligne;
    	}
    	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
    	
    	//==========
    }else {
        //Headers
        $header='From: "Extranet"<extranet@aaa-aero.com>'." \n";
        $header.='Content-Type: text/html; charset="iso-8859-1"'." \n";
        
        //Message html
        $message = $message_html;
    }
	
	
	//=====Envoi de l'e-mail.
	if ($destinataire <> "")
		return mail($destinataire,$sujet,$message,$header,'-f extranet@aaa-aero.com');
	else
		return false;
	
	//==========
}

/**
 * Cette fonction permet de récupérer la liste des plateformes dont on est responsable
 * par rapport à certains niveaux de responsabilité passés en paramètre (séparé par des virgules)
 *
 * @param   array     $TableauDeResponsabilite  Tableau des responsabilités    
 * @return  string    Liste d'Id_Plateforme
 *
 * @author  Rémy PARRAN <rparran@aaa-aero.com>
 */
function Get_Plateformes_Pour_Responsabilite($TableauDeResponsabilite)
{
    global $bdd;
    global $IdPersonneConnectee;
    
    $RequetePlateforme="
        SELECT
            DISTINCT Id_Plateforme
        FROM
            new_competences_personne_poste_plateforme
        WHERE
            Id_Personne=".$IdPersonneConnectee."
            AND Id_Poste IN (".implode(",",$TableauDeResponsabilite).")";
    $ResultPlateforme=mysqli_query($bdd,$RequetePlateforme);
    $NbPlateforme=mysqli_num_rows($ResultPlateforme);
    $ListePlateforme=0;
    if($NbPlateforme>0)
    {
        $ListePlateforme="";
        while($RowPlateforme=mysqli_fetch_array($ResultPlateforme)){$ListePlateforme.=$RowPlateforme['Id_Plateforme'].",";}
        $ListePlateforme=substr($ListePlateforme,0,-1);
    }

    return $ListePlateforme;
}

/**
 * Cette fonction permet de récupérer la liste des prestations dont on est responsable
 * par rapport à certains niveaux de responsabilité passés en paramètre (séparé par des virgules et Prestation_Pole)
 *
 * @param   array     $TableauDeResponsabilite  Tableau des responsabilités
 * @return  string    Liste d'Id_Prestation_Pole
 *
 * @author  Rémy PARRAN <rparran@aaa-aero.com>
 */
function Get_Prestations_Pour_Responsabilite($TableauDeResponsabilite)
{
    global $bdd;
    global $IdPersonneConnectee;
    
    $RequetePrestation="
        SELECT
            DISTINCT CONCAT(Id_Prestation,'_',Id_Pole) AS PRESTATION_POLE
        FROM
            new_competences_personne_poste_prestation
        WHERE
            Id_Personne=".$IdPersonneConnectee."
            AND Id_Poste IN (".implode(",",$TableauDeResponsabilite).")";
    
    $ResultPrestation=mysqli_query($bdd,$RequetePrestation);
    $NbPrestation=mysqli_num_rows($ResultPrestation);
    $ListePrestation=0;
    if($NbPrestation>0)
    {
        $ListePrestation="";
        while($RowPrestation=mysqli_fetch_array($ResultPrestation)){$ListePrestation.="'".$RowPrestation['PRESTATION_POLE']."',";}
        $ListePrestation=substr($ListePrestation,0,-1);
    }
    
    return $ListePrestation;
}

/**
 * Cette fonction permet de récupérer la liste des personnes concernées selon le profil de la personne connectée (séparé par des virgules)
 * 
 * @param   DateTime    $DatePriseEncompte  Date de prise en compte pour les calculs
 * @return  string                          Liste d'Id_Personne
 * 
 * @author Rémy PARRAN <rparran@aaa-aero.com>
 */
function Get_PersonnesDependantesProfilConnecte($DatePriseEnCompte = "")
{
    global $bdd;
    global $DateJour;
    global $IdPersonneConnectee;
    global $TableauIdPostesAF_RF_RQ;
    global $TableauIdPostesRespPresta_CQ;
	global $TableauIdPostesAF_RF_RQ_RH_CQS;
    
    $ListePersonne=0;
    
    if($DatePriseEnCompte==""){$DatePriseEnCompte=$DateJour;}
    
    if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS))
    {
        $RequetePrestation="
            SELECT
                Id_Prestation
		    FROM
                new_competences_personne_prestation
		    LEFT JOIN new_competences_prestation
                ON new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
            WHERE
                Date_Fin>='".$DatePriseEnCompte."'
                AND Id_Plateforme IN (".Get_Plateformes_Pour_Responsabilite($TableauIdPostesAF_RF_RQ_RH_CQS).")";
        $ResultPrestation=mysqli_query($bdd,$RequetePrestation);
        $NbPrestation=mysqli_num_rows($ResultPrestation);
        $ListePrestation=0;
        if($NbPrestation>0)
        {
            $ListePrestation="";
            while($RowPrestation=mysqli_fetch_array($ResultPrestation)){$ListePrestation.=$RowPrestation['Id_Prestation'].",";}
            $ListePrestation=substr($ListePrestation,0,-1);
        }
        
        $requetePersonnes2="
            AND Id_Prestation IN (".$ListePrestation.") ";
    }
    else
    {
        $RequetePrestation="
            SELECT
                DISTINCT CONCAT(Id_Prestation,'_',Id_Pole) AS PRESTATION_POLE
            FROM
                new_competences_personne_prestation
            WHERE
                Date_Fin>='".$DatePriseEnCompte."'
                AND CONCAT(Id_Prestation,'_',Id_Pole) IN (".Get_Prestations_Pour_Responsabilite($TableauIdPostesRespPresta_CQ).")";
        $ResultPrestation=mysqli_query($bdd,$RequetePrestation);
        $NbPrestation=mysqli_num_rows($ResultPrestation);
        $ListePrestation=0;
        if($NbPrestation>0)
        {
            $ListePrestation="";
            while($RowPrestation=mysqli_fetch_array($ResultPrestation)){$ListePrestation.="'".$RowPrestation['PRESTATION_POLE']."',";}
            $ListePrestation=substr($ListePrestation,0,-1);
        }
        
        $requetePersonnes2="
            AND CONCAT(Id_Prestation,'_',Id_Pole) IN (".$ListePrestation.") ";
    }
    
    $requetePersonnes="
        SELECT
			Id_Personne
		FROM
			new_competences_personne_prestation
		WHERE
		    Date_Fin>='".$DatePriseEnCompte."' ".
		    $requetePersonnes2;
    $resultPersResp=mysqli_query($bdd,$requetePersonnes);
    $nbPersResp=mysqli_num_rows($resultPersResp);
    $listeRespPers=0;
    if($nbPersResp>0)
    {
        $ListePersonne="";
        while($rowPersResp=mysqli_fetch_array($resultPersResp)){$ListePersonne.=$rowPersResp['Id_Personne'].",";}
        $ListePersonne=substr($ListePersonne,0,-1);
    }
    
    return $ListePersonne;
}

/**
 * Nombre de dates disponibles dans la planning
 *
 * Cette fonction permet de retourner le nombre de dates disponibles dans la planning
 *
 * @return 	int
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function NbDatesDisponibles()
{
	global $bdd;
	global $DateJour;
	global $IdPosteResponsableFormation;
	global $IdPosteAssistantFormationInterne;
	global $IdPosteAssistantFormationExterne;
	global $IdPosteAssistantFormationTC;
	global $IdPosteResponsableQualite; 
	global $IdPosteResponsableRH;
	global $IdTypeFormationEprouvette;
	global $IdTypeFormationTC;
	global $IdTypeFormationInterne;
	global $IdTypeFormationExterne;
	global $TableauIdPostesRespPresta_CQ;
	$requete="
        SELECT
			DISTINCT
			form_besoin.Id_Formation AS ID_FORMATION,
			form_besoin.Id_Prestation,
			form_besoin.Motif AS MOTIF_DEMANDE,
			form_formation.Recyclage AS RECYCLAGE_IDENTIQUE,
			new_competences_prestation.Id_Plateforme
		FROM
			form_besoin,
			form_formation,
			new_competences_prestation
		WHERE
			form_besoin.Id_Prestation=new_competences_prestation.Id
			AND form_besoin.Id_Formation=form_formation.Id
			AND form_besoin.Id_Personne IN (".Get_PersonnesDependantesProfilConnecte().")
			AND form_besoin.Suppr=0
			AND form_besoin.Traite=0 
			AND form_besoin.Valide=1 ";
	if(DroitsFormationPlateforme(array($IdPosteResponsableFormation,$IdPosteResponsableQualite,$IdPosteResponsableRH))==0){
		if(DroitsFormationPlateforme(array($IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC))){
			$requete.="AND (";
			if(DroitsFormationPlateforme(array($IdPosteAssistantFormationInterne))){
				$requete.="form_formation.Id_TypeFormation IN (".$IdTypeFormationEprouvette.",".$IdTypeFormationInterne.") OR ";
			}
			if(DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne))){
				$requete.="form_formation.Id_TypeFormation IN (".$IdTypeFormationExterne.") OR ";
			}
			if(DroitsFormationPlateforme(array($IdPosteAssistantFormationTC))){
				$requete.="form_formation.Id_TypeFormation IN (".$IdTypeFormationTC.") OR ";
			}
			$requete=substr($requete,0,-3);
			$requete.=") ";
		}
	}
	$result=mysqli_query($bdd,$requete);
	$nbValide=mysqli_num_rows($result);
	
	$nbDatesDispo=0;
	if($nbValide>0)
	{
    	while($row=mysqli_fetch_array($result))
    	{
    		$Recyl=0;
    		if($row['MOTIF_DEMANDE']=="Renouvellement"){$Recyl=1;}
    		if($row['RECYCLAGE_IDENTIQUE']==0){$Recyl=0;}
    		//Rajouter en fonction du planning la couleur orange ou verte
    		$reqF="
                SELECT
					DISTINCT
                    form_session_date.Id AS Id_SessionDate,
                    form_session_date.Id_Session,
                    form_session_date.DateSession,
                    form_session.Id_GroupeSession,
                    form_session.Id_Formation,
                    form_session.Formation_Liee,
                    form_session.Nb_Stagiaire_Maxi,
                    form_session.Recyclage
                FROM
                    form_session_date
                LEFT JOIN form_session
                    ON form_session_date.Id_Session=form_session.Id
                WHERE
                    form_session_date.Suppr=0
                    AND form_session_date.DateSession>'".$DateJour."'
                    AND form_session.Suppr=0 AND form_session.Annule=0 AND form_session.Diffusion_Creneau=1
                    AND form_session_date.Id_Session IN
                    (
                        SELECT
                            Id_Session
                        FROM
                            form_session_prestation
                        WHERE
                            Suppr=0
                            AND Id_Prestation=".$row['Id_Prestation']."
                    )
                    AND
                    (
                        (
                            form_session.Id_Formation=".$row['ID_FORMATION']."
                            AND form_session.Recyclage=".$Recyl."
                        )
                        OR ";
    		
    		$reqSimil="
                SELECT
                    Id_FormationEquivalente
                FROM
                    form_formationequivalente_formationplateforme
                LEFT JOIN form_formationequivalente
                    ON form_formationequivalente_formationplateforme.Id_FormationEquivalente=form_formationequivalente.Id
                WHERE
                    form_formationequivalente.Id_Plateforme=".$row['Id_Plateforme']." 
    				AND form_formationequivalente_formationplateforme.Id_Formation=".$row['ID_FORMATION']."
    				AND form_formationequivalente_formationplateforme.Recyclage=".$Recyl;
    		$resultSimil=mysqli_query($bdd,$reqSimil);
			
    		$nbSimil=mysqli_num_rows($resultSimil);
    		if($nbSimil>0)
    		{
    			while($rowSimil=mysqli_fetch_array($resultSimil))
    			{
    				$reqSimil2="
                        SELECT
                            Id_Formation,
                            Recyclage   
    					FROM
                            form_formationequivalente_formationplateforme 
    					LEFT JOIN form_formationequivalente 
                            ON form_formationequivalente_formationplateforme.Id_FormationEquivalente=form_formationequivalente.Id 
    					WHERE
                            form_formationequivalente_formationplateforme.Id_FormationEquivalente=".$rowSimil['Id_FormationEquivalente'];
    				$resultSimil2=mysqli_query($bdd,$reqSimil2);
    				$nbSimil2=mysqli_num_rows($resultSimil2);
    				
    				if($nbSimil2>0)
    				{
    					while($rowSimil2=mysqli_fetch_array($resultSimil2))
    					{
    						$reqF.=" ( form_session.Id_Formation=".$rowSimil2['Id_Formation']." AND form_session.Recyclage=".$rowSimil2['Recyclage'].") OR ";
    					}
    				}
    			}
    		}
    		$reqF=substr($reqF,0,-3);
    		$reqF.=") ";
    		$resultSession=mysqli_query($bdd,$reqF);
    		$resultAutresSessions=mysqli_query($bdd,$reqF);
    		$nbSession=mysqli_num_rows($resultSession);

    		if($nbSession>0){
    			$bOK=true;
    			$PlacesRestante=false;
    			while($rowSessionDate=mysqli_fetch_array($resultSession)){
    				//Vérifier si places restantes
    				$reqInscrit="SELECT Id FROM form_session_personne WHERE Validation_Inscription=1 AND Suppr=0 AND Id_Session=".$rowSessionDate['Id_Session'];
    				$resultNbInscrit=mysqli_query($bdd,$reqInscrit);
    				$nbInscrit=mysqli_num_rows($resultNbInscrit);
    				
    				if($rowSessionDate['Nb_Stagiaire_Maxi']>$nbInscrit){$PlacesRestante=true;}
    				//Vérifier si la session n'a pas commencé lors de date antérieur à la date du jour ou à la date du jour
    				$req="SELECT form_session_date.Id 
    					FROM form_session_date 
    						LEFT JOIN form_session 
    						ON form_session_date.Id_Session=form_session.Id 
    					WHERE form_session_date.DateSession<='".date('Y-m-d')."' 
    					AND form_session_date.Suppr=0 AND form_session.Suppr=0 
    					AND form_session.Annule=0 AND form_session.Diffusion_Creneau=1 
    					AND form_session_date.Id_Session=".$rowSessionDate['Id_Session'];
    				$resultDepasse=mysqli_query($bdd,$req);
    				$nbDepasse=mysqli_num_rows($resultDepasse);
    				if($nbDepasse>0){$bOK=false;}
    			}
    			if($bOK==true && $PlacesRestante==true){
    				$nbDatesDispo++;
    			}
    		}
		}
	}
	
	return $nbDatesDispo;
}

/**
 * Nombre de besoins à confirmer
 *
 * Cette fonction permet de retourner le nombre de besoins à confirmer
 *
 * @return 	int
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function NbBesoinsAConfirmer()
{
	global $bdd;
	global $IdPosteResponsableFormation;
	global $IdPosteAssistantFormationInterne;
	global $IdPosteAssistantFormationExterne;
	global $IdPosteAssistantFormationTC;
	global $IdPosteResponsableQualite; 
	global $IdPosteResponsableRH;
	global $IdTypeFormationEprouvette;
	global $IdTypeFormationTC;
	global $IdTypeFormationInterne;
	global $IdTypeFormationExterne;
	global $TableauIdPostesRespPresta_CQ;
	
	$requete="	SELECT
					DISTINCT
					form_besoin.Id AS ID_BESOIN,
					form_besoin.Id_Formation AS ID_FORMATION,
					form_besoin.Id_Personne,
					form_besoin.Motif AS MOTIF_DEMANDE,
					form_besoin.Date_Demande AS DATE_DEMANDE,
					form_besoin.Obligatoire 
				FROM
					form_besoin,
					form_formation
				WHERE
					form_besoin.Id_Personne IN
					(".Get_PersonnesDependantesProfilConnecte().")
					AND form_besoin.Suppr=0
					AND form_besoin.Traite=0 
					AND form_besoin.Valide=0 
					AND form_besoin.Id_Formation=form_formation.Id ";
	if(DroitsFormationPlateforme(array($IdPosteResponsableFormation,$IdPosteResponsableQualite,$IdPosteResponsableRH))==0){
		if(DroitsFormationPlateforme(array($IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC))){
			$requete.="AND (";
			if(DroitsFormationPlateforme(array($IdPosteAssistantFormationInterne))){
				$requete.="form_formation.Id_TypeFormation IN (".$IdTypeFormationEprouvette.",".$IdTypeFormationInterne.") OR ";
			}
			if(DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne))){
				$requete.="form_formation.Id_TypeFormation IN (".$IdTypeFormationExterne.") OR ";
			}
			if(DroitsFormationPlateforme(array($IdPosteAssistantFormationTC))){
				$requete.="form_formation.Id_TypeFormation IN (".$IdTypeFormationTC.") OR ";
			}
			$requete=substr($requete,0,-3);
			$requete.=") ";
		}
	}
	$result=mysqli_query($bdd,$requete);
	$nbAConfirmer=mysqli_num_rows($result);
	
	return $nbAConfirmer;
}

/**
 * Nombre de fin de qualifications en attente de validation
 *
 * Cette fonction permet de retourner le nombre de fin de qualifications en attente de validation
 *
 * @return 	int
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function NbFinQualifEnAttente()
{
	global $bdd;
	global $IdPersonneConnectee;
	global $TableauIdPostesAF_RF_RQ;
	global $TableauIdPostesRespPresta_CQ;
	global $TableauIdPostesAF_RF_RQ_RH_CQS;
	global $TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS;

	$date_4mois=date("Y-m-d",strtotime(date("Y-m-d")." + 4 month"));
	$date_moins_6mois=date("Y-m-d",strtotime(date("Y-m-d")." - 6 month"));
	
	//QUALIFICATIONS A REPASSER DANS LES 4 MOIS
	$requeteQualifications="
        SELECT
            *
        FROM
            (
            SELECT
            *
            FROM
                (
                SELECT
                    new_competences_relation.Id,
					new_competences_relation.Id_Personne,
					new_competences_relation.Evaluation,
					new_competences_relation.Id_Qualification_Parrainage,
					new_competences_relation.Date_Fin,
					new_competences_relation.Date_Debut,
					new_competences_relation.Date_QCM,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=new_competences_relation.Id_Personne) AS Personne,
					(SELECT Libelle FROM new_competences_prestation WHERE Id=new_competences_personne_prestation.Id_Prestation) AS Prestation,
					(SELECT Libelle FROM new_competences_pole WHERE Id=new_competences_personne_prestation.Id_Pole) AS Pole,
					(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_prestation.Id_Prestation) AS Id_Plateforme,
					new_competences_personne_prestation.Id_Prestation,
					new_competences_personne_prestation.Id_Pole,
					(SELECT Libelle FROM new_competences_qualification WHERE Id=new_competences_relation.Id_Qualification_Parrainage) AS Qualif,
					(
						SELECT
						(
							SELECT
								Libelle
							FROM
								new_competences_categorie_qualification
							WHERE
								new_competences_categorie_qualification.Id=new_competences_qualification.Id_Categorie_Qualification
						)
						FROM
							new_competences_qualification
						WHERE
							Id=new_competences_relation.Id_Qualification_Parrainage
					) AS Categorie,(@row_number:=@row_number + 1) AS rnk
                FROM
					new_competences_relation
				RIGHT JOIN new_competences_personne_prestation
					ON new_competences_relation.Id_Personne=new_competences_personne_prestation.Id_Personne 
				LEFT JOIN new_competences_qualification
					ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
				WHERE
				(
					new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."'
					OR new_competences_personne_prestation.Date_Fin<='0001-01-01'
				)";
				if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS))
				{
					$requeteQualifications2="
						AND new_competences_personne_prestation.Id_Prestation IN
						(
							SELECT
								Id
							FROM
								new_competences_prestation
							WHERE
								Id_Plateforme IN
								(
									SELECT
										Id_Plateforme
									FROM
										new_competences_personne_poste_plateforme
									WHERE
										Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS_Form_CQS).")
										AND Id_Personne=".$IdPersonneConnectee."
								)
						) ";
				}
				else
				{
					$requeteQualifications2="
						AND CONCAT(new_competences_personne_prestation.Id_Prestation,'_',new_competences_personne_prestation.Id_Pole) IN
						(
							SELECT
								CONCAT(Id_Prestation,'_',Id_Pole)
							FROM
								new_competences_personne_poste_prestation
							WHERE
								Id_Poste IN (".implode(",",$TableauIdPostesRespPresta_CQ).")
								AND Id_Personne=".$IdPersonneConnectee."
						) ";
				}
	$requeteQualifications2.="
                    AND new_competences_relation.Type='Qualification' 
                    AND new_competences_relation.Suppr=0 
					AND new_competences_relation.Statut_Surveillance != 'REFUSE'
                    AND new_competences_qualification.Duree_Validite>0
                    AND new_competences_relation.Date_Debut>'0001-01-01'
                    AND new_competences_relation.Date_Fin > '0001-01-01'
                    AND new_competences_relation.Date_Fin >= '".$date_moins_6mois."'
                ORDER BY
                    new_competences_relation.Date_Debut DESC
                ) AS Tab_Qualif
            GROUP BY
                Tab_Qualif.Id_Personne,
                Tab_Qualif.Id_Prestation,
                Tab_Qualif.Id_Qualification_Parrainage
            ) AS TAB
        WHERE
            TAB.Evaluation<>'B'
            AND TAB.Evaluation<>''
            AND TAB.Date_Fin<='".$date_4mois."'
			AND
                (
                SELECT
                    COUNT(Id)
                FROM
                    form_qualificationnecessaire_prestation
                WHERE
                    form_qualificationnecessaire_prestation.Id_Relation=TAB.Id
                    AND
                        (
                        form_qualificationnecessaire_prestation.Necessaire=0
                        AND form_qualificationnecessaire_prestation.Id_Prestation=TAB.Id_Prestation
						AND form_qualificationnecessaire_prestation.Id_Pole=TAB.Id_Pole
                        )
                )=0
            AND
                (
                    SELECT
                        COUNT(form_besoin.Id)
					FROM
                        form_besoin
					WHERE
                        form_besoin.Suppr=0
    					AND form_besoin.Motif='Renouvellement'
    					AND form_besoin.Id_Personne=TAB.Id_Personne
    					AND form_besoin.Valide >=0 
    					AND form_besoin.Traite<3
    					AND form_besoin.Id_Formation IN
                        (
                            SELECT
                                form_formation_qualification.Id_Formation
                            FROM
                                form_formation_qualification
                            WHERE
                                form_formation_qualification.Suppr=0
                                AND form_formation_qualification.Id_Qualification=TAB.Id_Qualification_Parrainage
                        )
				)=0 
				
				AND (
					(TAB.Id_Qualification_Parrainage IN (133,2145,2490,13,12,1683,75,167)
					AND 
						(
							SELECT
							   COUNT(new_competences_relation.Id)
							FROM
								new_competences_relation
							WHERE new_competences_relation.Id_Qualification_Parrainage IN (1606,2130,3258)
								AND new_competences_relation.Suppr=0
								AND new_competences_relation.Id_Personne=TAB.Id_Personne
								AND (new_competences_relation.Date_Fin <= '0001-01-01'
								OR new_competences_relation.Date_Fin >= '".date('Y-m-d')."')
						)=0
					)
				
				OR
					TAB.Id_Qualification_Parrainage NOT IN (133,2145,2490,13,12,1683,75,167)
				)
				
				AND (
                    SELECT
                       COUNT(new_competences_relation.Id)
                    FROM
                        new_competences_relation
                    WHERE new_competences_relation.Id_Qualification_Parrainage=TAB.Id_Qualification_Parrainage
            			AND new_competences_relation.Suppr=0
						AND new_competences_relation.Id_Personne=TAB.Id_Personne
						AND new_competences_relation.Evaluation IN ('L','T')
						AND new_competences_relation.Date_QCM>=TAB.Date_QCM
                        AND (new_competences_relation.Date_Fin <= '0001-01-01'
                        OR new_competences_relation.Date_Fin >= '".date('Y-m-d')."')
				)=0
				";
	$resultQualifications=mysqli_query($bdd,$requeteQualifications.$requeteQualifications2);
	$nbQualifs=mysqli_num_rows($resultQualifications);
	return $nbQualifs;
}
/**
 * encoderFichier
 * 
 * Permet d'encoder un fichier pour le mettre en PJ d\'un mail.
 * 
 * @param string $chemincomplet Chemin complet du fichier
 * @return string Le fichier encod\é
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function encoderFichier($chemincomplet) {	
	//=====Lecture et mise en forme de la pièce jointe.
	$fichier   = fopen($chemincomplet, "r");
	$attachement = fread($fichier, filesize($chemincomplet));
	$attachement = chunk_split(base64_encode($attachement));
	fclose($fichier);
	//==========
	return $attachement;
}

/**
 * Nombre de fin de qualifications en attente de formation
 *
 * Cette fonction permet de retourner le nombre de fin de qualifications en attente de formation
 *
 * @return 	int
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function NbFinQualifEnAttenteFormation()
{
	global $bdd;
	global $IdPersonneConnectee;
	global $TableauIdPostesAssistantFormation;
	global $TableauIdPostesAF_RH;
	
	$requeteQualifications="
        SELECT
            form_qualificationnecessaire_prestation.Id,
            form_qualificationnecessaire_prestation.Id_Relation,
            form_qualificationnecessaire_prestation.Id_Prestation,
            form_qualificationnecessaire_prestation.Necessaire,
            form_qualificationnecessaire_prestation.Id_Validateur,
            form_qualificationnecessaire_prestation.DateValidation
        FROM
            form_qualificationnecessaire_prestation
        LEFT JOIN new_competences_relation
            ON form_qualificationnecessaire_prestation.Id_Relation=new_competences_relation.Id
        WHERE
            Necessaire=1
            AND new_competences_relation.Suppr=0
            AND form_qualificationnecessaire_prestation.Id_Prestation IN
            (
                SELECT
                    Id
                FROM
                    new_competences_prestation
                WHERE
                    Id_Plateforme IN
                    (
                        SELECT
                            Id_Plateforme
                        FROM
                            new_competences_personne_poste_plateforme
                        WHERE
                            Id_Poste IN (".implode(",",$TableauIdPostesAF_RH).")
                            AND Id_Personne=".$IdPersonneConnectee."
                    )
            )";
	$resultQualifications=mysqli_query($bdd,$requeteQualifications);
	$nbQualifs=mysqli_num_rows($resultQualifications);
	
	return $nbQualifs;
}

/**
 * Nombre de surveillance en attente
 * Cette fonction permet de retourner le nombre de surveillance en attente
 *
 * @return 	int
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function NbSurveillanceEnattente()
{
	global $bdd;
	global $IdPersonneConnectee;
	
	$req = "
        SELECT
            new_competences_relation.Id,
            new_rh_etatcivil.Id AS Id_Personne,
            new_competences_qualification.Id AS Id_Qualif
        FROM
            new_competences_relation,
            new_rh_etatcivil,
            new_competences_qualification
        WHERE
            new_competences_relation.Id_Qualification_Parrainage = new_competences_qualification.Id
            AND new_competences_relation.Id_Personne = new_rh_etatcivil.Id
            AND new_competences_relation.Suppr=0
            AND new_competences_relation.Resultat_QCM = ''
            AND new_competences_relation.Date_Surveillance = 0
            AND new_competences_relation.Date_Debut >'0001-01-01'
            AND new_competences_relation.Id_Personne IN
            (
                SELECT
                    Id_Personne
                FROM
                    new_competences_personne_prestation
                WHERE
                    Date_Fin>='".date('Y-m-d')."'
                    AND CONCAT(Id_Prestation,'_',Id_Pole) IN
                    (
                        SELECT
                            CONCAT(Id_Prestation,'_',Id_Pole)
                        FROM
                            new_competences_personne_poste_prestation
                        WHERE
                            Id_Personne=".$IdPersonneConnectee."
                            AND Id_Poste IN (5,6)
                    )
            )";
	$resultSurveillance=mysqli_query($bdd,$req);
	$NbSurveillances=mysqli_num_rows($resultSurveillance);
	
	return $NbSurveillances;
}

/**
 * Nombre d'autorisation de conduite à rééditer
 * Cette fonction permet de retourner le nombre de d'autorisation de conduite à rééditer
 *
 * @return 	int
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function NbAutorisationAReediter()
{
	global $bdd;
	global $IdPersonneConnectee;
	
	$NbAutorisations=0;
	$req="SELECT DISTINCT new_competences_relation.Id_Personne, 
	(SELECT DateEditionAutorisationTravail FROM new_rh_etatcivil WHERE Id=new_competences_relation.Id_Personne) AS DateEditionAutorisationTravail ";
	$req2="FROM new_competences_relation 
	LEFT JOIN new_competences_qualification
	ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id 
	WHERE (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
	AND Date_Debut>'0001-01-01'
	AND Evaluation NOT IN ('B','')
	AND new_competences_relation.Suppr=0 
	AND Id_Personne IN (".Get_PersonnesDependantesProfilConnecte().") 
	AND (SELECT COUNT(new_competences_qualification_moyen.Id)
		FROM new_competences_qualification_moyen
		WHERE new_competences_qualification_moyen.Id_Qualification=new_competences_relation.Id_Qualification_Parrainage
		AND new_competences_qualification_moyen.Suppr=0)>0";
	$resultPersonne=mysqli_query($bdd,$req.$req2);
	$nbPersonne=mysqli_num_rows($resultPersonne);
	
	if ($nbPersonne>0){
		$couleur="#ffffff";
		while($row=mysqli_fetch_array($resultPersonne)){
			$bReedition=0;
			if($row['DateEditionAutorisationTravail']<='0001-01-01'){$bReedition=1;}

			//Liste des autorisations de conduite
			$AT="";
			$reqAT="SELECT DISTINCT new_competences_relation.Id_Qualification_Parrainage,new_competences_relation.Date_Fin,
			new_competences_relation.DateEditionAutorisationTravail,
			(SELECT Libelle FROM new_competences_moyen_categorie 
			WHERE new_competences_moyen_categorie.Id=new_competences_qualification_moyen.Id_Moyen_Categorie) AS Categorie,
			(SELECT 
				(SELECT Libelle FROM new_competences_moyen 
				WHERE new_competences_moyen.Id=new_competences_moyen_categorie.Id_Moyen) 
			FROM new_competences_moyen_categorie 
			WHERE new_competences_moyen_categorie.Id=new_competences_qualification_moyen.Id_Moyen_Categorie) AS Moyen 
			FROM new_competences_relation 
			LEFT JOIN new_competences_qualification_moyen
			ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification_moyen.Id_Qualification 
			LEFT JOIN new_competences_qualification
			ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
			WHERE new_competences_qualification_moyen.Suppr=0 
			AND new_competences_qualification_moyen.Suppr=0 
			AND new_competences_relation.Evaluation NOT IN ('B','')
			AND (Date_Fin>='".date('Y-m-d')."' OR new_competences_qualification.Duree_Validite=0)
			AND Date_Debut>'0001-01-01' 
			AND new_competences_relation.Id_Personne=".$row['Id_Personne']." ";
			$resultAT=mysqli_query($bdd,$reqAT);
			$nbAT=mysqli_num_rows($resultAT);
			if($nbAT>0){
				while($rowAT=mysqli_fetch_array($resultAT)){
					if($bReedition==0){
						if($rowAT['DateEditionAutorisationTravail']<='0001-01-01'){
							$bReedition=1;
						}
						elseif($rowAT['DateEditionAutorisationTravail']<$row['DateEditionAutorisationTravail']){
							$bReedition=1;
						}
					}
				}
				$AT=substr($AT,0,-4);
			}
			if($bReedition==1){$NbAutorisations++;}
		}
	}
	return $NbAutorisations;
}

/**
 * Nombre de personne en formation à un jour donnée
 * Cette fonction permet de retourner le nombre de personne en formation à un jour donnée
 *
 * @return 	int
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function NbPersonneEnFormation($dateFormation)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $IdPosteResponsableFormation;
	global $IdPosteAssistantFormationInterne;
	global $IdPosteAssistantFormationExterne;
	global $IdPosteAssistantFormationTC;
	global $IdPosteResponsableQualite; 
	global $IdPosteResponsableRH;
	global $IdTypeFormationEprouvette;
	global $IdTypeFormationTC;
	global $IdTypeFormationInterne;
	global $IdTypeFormationExterne;
	global $TableauIdPostesRespPresta_CQ;
	
	$NbPersonnes=0;
	
	$requete="SELECT DISTINCT form_session_personne.Id_Personne
		FROM form_session_personne 
		LEFT JOIN form_session
		ON form_session_personne.Id_Session=form_session.Id 
		LEFT JOIN form_formation
		ON form_formation.Id=form_session.Id_Formation
		WHERE form_session_personne.Suppr=0
		AND form_session.Annule=0
		AND form_session.Suppr=0
		AND (
			SELECT COUNT(form_session_date.Id_Session)
			FROM form_session_date
			WHERE form_session_date.Suppr=0
			AND form_session_date.Id_Session=form_session.Id
			AND form_session_date.DateSession='".$dateFormation."'
			)>0
		AND form_session_personne.Id_Personne IN (".Get_PersonnesDependantesProfilConnecte($dateFormation).")
        AND Validation_Inscription=1 ";

	if(DroitsFormationPlateforme(array($IdPosteResponsableFormation,$IdPosteResponsableQualite,$IdPosteResponsableRH))==0){
		if(DroitsFormationPlateforme(array($IdPosteAssistantFormationInterne,$IdPosteAssistantFormationExterne,$IdPosteAssistantFormationTC))){
			$requete.="AND (";
			if(DroitsFormationPlateforme(array($IdPosteAssistantFormationInterne))){
				$requete.="form_formation.Id_TypeFormation IN (".$IdTypeFormationEprouvette.",".$IdTypeFormationInterne.") OR ";
			}
			if(DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne))){
				$requete.="form_formation.Id_TypeFormation IN (".$IdTypeFormationExterne.") OR ";
			}
			if(DroitsFormationPlateforme(array($IdPosteAssistantFormationTC))){
				$requete.="form_formation.Id_TypeFormation IN (".$IdTypeFormationTC.") OR ";
			}
			$requete=substr($requete,0,-3);
			$requete.=") ";
		}
	}
		$ResultPersonnes=mysqli_query($bdd,$requete);
		$NbPersonnes=mysqli_num_rows($ResultPersonnes);
	return $NbPersonnes;
}

/**
 * Liste des personnes en formation
 * Cette fonction permet de retourner les personnes en formation à une date donnée
 *
 * @return 	int
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function GetPersonnesEnFormation($dateFormation)
{
	global $bdd;
	
	$Personnes="";
	
	Get_PersonnesDependantesProfilConnecte();
	
	$req="
        SELECT
            DISTINCT form_session_personne.Id_Personne,
            (
                SELECT
                    CONCAT(Nom,' ',Prenom)
                FROM
                    new_rh_etatcivil
                WHERE
                    new_rh_etatcivil.Id=form_session_personne.Id_Personne
            ) AS Personne 
		FROM
            form_session_personne 
		LEFT JOIN form_session
		  ON form_session_personne.Id_Session=form_session.Id 
		WHERE
            form_session_personne.Suppr=0
            AND form_session.Annule=0
            AND form_session.Suppr=0
            AND
            (
                SELECT
                    COUNT(form_session_date.Id_Session)
                FROM
                    form_session_date
                WHERE
                    form_session_date.Suppr=0
                    AND form_session_date.Id_Session=form_session.Id
                    AND form_session_date.DateSession='".$dateFormation."'
			)>0
        AND form_session_personne.Id_Personne IN (".Get_PersonnesDependantesProfilConnecte().")
        AND (Validation_Inscription=1) ";
		$ResultPersonnes=mysqli_query($bdd,$req);
		$NbPersonnes=mysqli_num_rows($ResultPersonnes);
		if($NbPersonnes>0)
		{
			while($rowPers=mysqli_fetch_array($ResultPersonnes)){$Personnes.=$rowPers['Personne']."<br>";}
		}
	return $Personnes;
}

/**
 * Get_QualifAJour
 *
 * Cette fonction permet de récupérer le nombre qualification à jour existante pour une personne 
 * ou formation compétence déjà passée
 *
 * @param 	int $Id_Personne 	Identifiant de la personne
 * @param 	int	$Id_Formation	Identifiant de la formation
 * @return 	int					Nombre de besoin en cours
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function Get_QualifAJour($Id_Personne, $Id_Formation)
{
	global $bdd;
	global $IdTypeFormationInterne;
	global $IdTypeFormationTC;
	global $IdTypeFormationEprouvette;
	global $IdTypeFormationExterne;
	
	$InterneEprouvette=1;
	
	//Verifier le type de la formation 
	$req="SELECT Id_TypeFormation FROM form_formation WHERE Id=".$Id_Formation;
	$resultForm=mysqli_query($bdd,$req);
	$NbForm=mysqli_num_rows($resultForm);
	if($NbForm>0)
	{
		$rowForm=mysqli_fetch_array($resultForm);
		if($rowForm['Id_TypeFormation']==$IdTypeFormationExterne || $rowForm['Id_TypeFormation']==$IdTypeFormationTC){$InterneEprouvette=0;}
	}
	
	$QualifAJour=1;
	//On regarde les qualifs non masquées
	$reqQualif="
        SELECT DISTINCT
            Id_Qualification,
		    (SELECT Libelle FROM new_competences_qualification WHERE new_competences_qualification.Id=Id_Qualification) AS Qualif 
		FROM
            form_formation_qualification 
		WHERE
            Id_Formation=".$Id_Formation." 
		    AND Suppr=0 
		    AND Masquer=0 ";
	$resultQualif=mysqli_query($bdd,$reqQualif);
	$NbQualif=mysqli_num_rows($resultQualif);
	
	//Vérification de la note minimale que doit avoir la personne
	//-------------------------------------------------------------
	//Récupération du tableau (Id_Metier,Métier,Col) de la personne
	$Metier_Personne=Get_Metier($Id_Personne);
	$NoteMini=80;
	switch($Metier_Personne[2])
	{
	    case "Bleu":
	        $NoteMini=70;
	        break;
	    default:
	        $NoteMini=80;
	        break;
	}

	//------------------------------------------------------------
	if($NbQualif>0)
	{
		mysqli_data_seek($resultQualif,0);
		$nbQualifAJour=0;
		while($rowQualif=mysqli_fetch_array($resultQualif))
		{
			//Si Accueil général HSE alors Note=80 pour tous
			if($rowQualif['Id_Qualification']==2750 || $Id_Qualification==3777){$NoteMini=80;}
	
			$req="SELECT
					new_competences_relation.Id,
					new_competences_relation.Date_Debut,
					new_competences_relation.Date_Fin,
					new_competences_relation.Date_QCM,
					new_competences_relation.Id_Qualification_Parrainage, 
					new_competences_relation.Evaluation,
					new_competences_qualification.Duree_Validite
				FROM
                    new_competences_relation
				LEFT JOIN new_competences_qualification
				ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
				WHERE
					new_competences_relation.Id_Personne=".$Id_Personne."
					AND new_competences_relation.Type='Qualification'
					AND new_competences_relation.Visible=0
					AND new_competences_relation.Suppr=0
					AND new_competences_relation.Evaluation<>''
					AND new_competences_relation.Evaluation<>'B'
					AND new_competences_relation.Id_Qualification_Parrainage=".$rowQualif['Id_Qualification']."
					AND (
    						new_competences_relation.Evaluation='L' 
    						OR new_competences_relation.Evaluation='T' 
    						OR
                            (
                                (new_competences_relation.Date_Fin<='0001-01-01' AND new_competences_qualification.Duree_Validite=0)
                                OR
                                (
                                    new_competences_relation.Date_Fin>='".date('Y-m-d',strtotime('-6 months',strtotime(date('Y-m-d'))))."'
                                )
                            )
						)
                    AND (
                            new_competences_relation.Resultat_QCM>=".$NoteMini."
                            OR new_competences_relation.Resultat_QCM=''
                            OR new_competences_relation.Resultat_QCM='NA'
							OR new_competences_relation.Resultat_QCM='NC'
                        ) ";
			$resultQualifPersonne=mysqli_query($bdd,$req);
			$nbQualifPersonne=mysqli_num_rows($resultQualifPersonne);
			if($nbQualifPersonne>0){$nbQualifAJour=1;}
		}
		if($nbQualifAJour==0){
			$QualifAJour=0;
		}
		
		//Verif si la personne n'a pas la formation sans qualification des anciennes formations sans qualif hors qualipso
		$MilieuRequete="";
		$ReqMilieu="
            SELECT
                DISTINCT form_formation_formationcompetence.Id_FormationCompetence
		    FROM
                form_formation_formationcompetence
		    WHERE
                form_formation_formationcompetence.Id_Formation=".$Id_Formation."
		        AND form_formation_formationcompetence.Suppr=0";
		$ResultMilieu=mysqli_query($bdd,$ReqMilieu);
		while($RowMilieu=mysqli_fetch_array($ResultMilieu)){$MilieuRequete.=$RowMilieu[0].",";}
		$MilieuRequete=substr($MilieuRequete,0,-1);
	    
	    $reqFormationCompetence="
			SELECT Id 
			FROM new_competences_personne_formation
			WHERE 
			Id_Personne=".$Id_Personne."
			AND Id_Formation IN (".$MilieuRequete.")";
		if($MilieuRequete<>"")
		{
			$resultFormationCompetence=mysqli_query($bdd,$reqFormationCompetence);
			$NbFormationCompetence=mysqli_num_rows($resultFormationCompetence);
			if($NbFormationCompetence>0){$QualifAJour=1;}
		}
	}
	else
	{
		//Verif si la personne n'a pas la formation sans qualification
		$ReqFormationsSansQualification="
			SELECT Id FROM form_besoin
			WHERE
				Id_Personne=".$Id_Personne."
				AND Suppr=0
				AND Valide=1
				AND Traite=4
				AND Id_Formation=".$Id_Formation."
				AND Id IN
				(
				SELECT Id_Besoin
				FROM form_session_personne
				WHERE form_session_personne.Id NOT IN 
					(
					SELECT Id_Session_Personne
					FROM form_session_personne_qualification
					WHERE Suppr=0	
					) AND Suppr=0
				)
			";
		$ResultFormationsSansQualification=mysqli_query($bdd,$ReqFormationsSansQualification);
		$NbFormationsSansQualification=mysqli_num_rows($ResultFormationsSansQualification);
		if($NbFormationsSansQualification == 0){$QualifAJour=0;}
		
		//Verif si la personne n'a pas la formation sans qualification des anciennes formations
		$MilieuRequete="";
		$ReqMilieu="
            SELECT
                DISTINCT form_formation_formationcompetence.Id_FormationCompetence
		    FROM
                form_formation_formationcompetence
		    WHERE
                form_formation_formationcompetence.Id_Formation=".$Id_Formation."
		        AND form_formation_formationcompetence.Suppr=0";
		$ResultMilieu=mysqli_query($bdd,$ReqMilieu);
		while($RowMilieu=mysqli_fetch_array($ResultMilieu)){$MilieuRequete.=$RowMilieu[0].",";}
		$MilieuRequete=substr($MilieuRequete,0,-1);
	    
	    $reqFormationCompetence="
			SELECT Id 
			FROM new_competences_personne_formation
			WHERE 
			Id_Personne=".$Id_Personne."
			AND Id_Formation IN (".$MilieuRequete.")";
		if($MilieuRequete<>"")
		{
			$resultFormationCompetence=mysqli_query($bdd,$reqFormationCompetence);
			$NbFormationCompetence=mysqli_num_rows($resultFormationCompetence);
			if($NbFormationCompetence>0){$QualifAJour=1;}
		}
	}
	
	//On regarde les qualifs masquées
	$reqQualif="SELECT DISTINCT
                    Id_Qualification,
				    (SELECT Libelle FROM new_competences_qualification WHERE new_competences_qualification.Id=Id_Qualification) AS Qualif 
				FROM
                    form_formation_qualification 
				WHERE
                    Id_Formation=".$Id_Formation." 
				    AND Suppr=0 
				    AND Masquer=1 ";
	$resultQualif=mysqli_query($bdd,$reqQualif);
	$NbQualif=mysqli_num_rows($resultQualif);
	
	//Si une des qualifs est à jour alors = 1 
	if($NbQualif>0)
	{
		while($rowQualif=mysqli_fetch_array($resultQualif))
		{
			if($rowQualif['Id_Qualification']==2750 || $Id_Qualification==3777){$NoteMini=80;}
			$req="SELECT
					new_competences_relation.Id,
					new_competences_relation.Date_Debut,
					new_competences_relation.Date_Fin,
					new_competences_relation.Date_QCM,
					new_competences_relation.Id_Qualification_Parrainage, 
					new_competences_relation.Evaluation,
					new_competences_qualification.Duree_Validite
				FROM
                    new_competences_relation
				LEFT JOIN new_competences_qualification
				    ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
				WHERE
					new_competences_relation.Id_Personne=".$Id_Personne."
					AND new_competences_relation.Type='Qualification'
					AND new_competences_relation.Visible=0
					AND new_competences_relation.Suppr=0
					AND new_competences_relation.Evaluation<>''
					AND new_competences_relation.Evaluation<>'B'
					AND new_competences_relation.Id_Qualification_Parrainage=".$rowQualif['Id_Qualification']."
					AND (
    						new_competences_relation.Evaluation='L' 
    						OR new_competences_relation.Evaluation='T' 
    						OR
                            (
                                new_competences_qualification.Duree_Validite=0
                                OR
                                (
                                    new_competences_relation.Date_Fin>='".date('Y-m-d',strtotime('-6 months',strtotime(date('Y-m-d'))))."'
                                )
                            )
						)
                    AND (
                            new_competences_relation.Resultat_QCM>='".$NoteMini."'
                            OR new_competences_relation.Resultat_QCM=''
                            OR new_competences_relation.Resultat_QCM='NA'
							OR new_competences_relation.Resultat_QCM='NC'
                        ) ";
			$resultQualifPersonne=mysqli_query($bdd,$req);
			$nbQualifPersonne=mysqli_num_rows($resultQualifPersonne);
			if($nbQualifPersonne>0){$QualifAJour=1;}
		}
	}
	
	//On regarde si il n'y a pas des anciennes formations sans qualif QUALIPSO existantes 
	$ReqFormationsQUALIPSOSansQualification="
		SELECT Id FROM form_besoin
		WHERE
			Id_Personne=".$Id_Personne."
			AND Suppr=0
			AND Valide=1
			AND Traite=4
			AND Id_Formation IN (SELECT Id_FormationQualipso FROM form_formation_formationqualipso WHERE Id_Formation=".$Id_Formation.")
			AND Id IN
			(
			SELECT Id_Besoin
			FROM form_session_personne
			WHERE form_session_personne.Id NOT IN 
				(
				SELECT Id_Session_Personne
				FROM form_session_personne_qualification
				WHERE Suppr=0	
				) AND Suppr=0
			)
		";
	$ResultFormationsQualipsoSansQualification=mysqli_query($bdd,$ReqFormationsQUALIPSOSansQualification);
	$NbFormationsQualipsoSansQualification=mysqli_num_rows($ResultFormationsQualipsoSansQualification);
	if($NbFormationsQualipsoSansQualification > 0){$QualifAJour=1;}

	return $QualifAJour;
}

/**
 * Get_QualifAJour
 *
 * Cette fonction permet de récupérer le nombre qualification à jour existante pour une personne 
 * ou formation compétence déjà passée
 *
 * @param 	int $Id_Personne 	Identifiant de la personne
 * @param 	int	$Id_Formation	Identifiant de la formation
 * @return 	int					Nombre de besoin en cours
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function Get_QualifAJourDans4Mois($Id_Personne, $Id_Formation)
{
	global $bdd;
	global $IdTypeFormationInterne;
	global $IdTypeFormationTC;
	global $IdTypeFormationEprouvette;
	global $IdTypeFormationExterne;
	
	$InterneEprouvette=1;
	
	//Verifier le type de la formation 
	$req="SELECT Id_TypeFormation FROM form_formation WHERE Id=".$Id_Formation;
	$resultForm=mysqli_query($bdd,$req);
	$NbForm=mysqli_num_rows($resultForm);
	if($NbForm>0)
	{
		$rowForm=mysqli_fetch_array($resultForm);
		if($rowForm['Id_TypeFormation']==$IdTypeFormationExterne || $rowForm['Id_TypeFormation']==$IdTypeFormationTC){$InterneEprouvette=0;}
	}
	
	$QualifAJour=1;
	//On regarde les qualifs non masquées
	$reqQualif="
        SELECT DISTINCT
            Id_Qualification,
		    (SELECT Libelle FROM new_competences_qualification WHERE new_competences_qualification.Id=Id_Qualification) AS Qualif 
		FROM
            form_formation_qualification 
		WHERE
            Id_Formation=".$Id_Formation." 
		    AND Suppr=0 
		    AND Masquer=0 ";
	$resultQualif=mysqli_query($bdd,$reqQualif);
	$NbQualif=mysqli_num_rows($resultQualif);
	
	//Vérification de la note minimale que doit avoir la personne
	//-------------------------------------------------------------
	//Récupération du tableau (Id_Metier,Métier,Col) de la personne
	$Metier_Personne=Get_Metier($Id_Personne);
	$NoteMini=80;
	switch($Metier_Personne[2])
	{
	    case "Bleu":
	        $NoteMini=70;
	        break;
	    default:
	        $NoteMini=80;
	        break;
	}
	
	//------------------------------------------------------------
	if($NbQualif>0)
	{
		mysqli_data_seek($resultQualif,0);
		$nbQualifAJour=0;
		while($rowQualif=mysqli_fetch_array($resultQualif))
		{
			//Si Accueil général HSE alors Note=80 pour tous
			if($rowQualif['Id_Qualification']==2750 || $Id_Qualification==3777){$NoteMini=80;}
	
			$req="SELECT
					new_competences_relation.Id,
					new_competences_relation.Date_Debut,
					new_competences_relation.Date_Fin,
					new_competences_relation.Date_QCM,
					new_competences_relation.Id_Qualification_Parrainage, 
					new_competences_relation.Evaluation,
					new_competences_qualification.Duree_Validite
				FROM
                    new_competences_relation
				LEFT JOIN new_competences_qualification
				ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
				WHERE
					new_competences_relation.Id_Personne=".$Id_Personne."
					AND new_competences_relation.Type='Qualification'
					AND new_competences_relation.Visible=0
					AND new_competences_relation.Suppr=0
					AND new_competences_relation.Evaluation<>''
					AND new_competences_relation.Evaluation<>'B'
					AND new_competences_relation.Id_Qualification_Parrainage=".$rowQualif['Id_Qualification']."
					AND (
    						new_competences_relation.Evaluation='L' 
    						OR new_competences_relation.Evaluation='T' 
    						OR
                            (
                                new_competences_qualification.Duree_Validite=0
                                OR
                                (
                                    new_competences_relation.Date_Fin>='".date('Y-m-d',strtotime('+4 months',strtotime(date('Y-m-d'))))."'
                                )
                            )
						)
                    AND (
                            new_competences_relation.Resultat_QCM>=".$NoteMini."
                            OR new_competences_relation.Resultat_QCM=''
                            OR new_competences_relation.Resultat_QCM='NA'
							OR new_competences_relation.Resultat_QCM='NC'
                        ) ";
			$resultQualifPersonne=mysqli_query($bdd,$req);
			$nbQualifPersonne=mysqli_num_rows($resultQualifPersonne);
			if($nbQualifPersonne>0){$nbQualifAJour=1;}
		}
		if($nbQualifAJour==0){
			$QualifAJour=0;
		}
	}
	else
	{
		//Verif si la personne n'a pas la formation sans qualification
		$ReqFormationsSansQualification="
			SELECT Id FROM form_besoin
			WHERE
				Id_Personne=".$Id_Personne."
				AND Suppr=0
				AND Valide=1
				AND Traite=4
				AND Id_Formation=".$Id_Formation."
				AND Id IN
				(
				SELECT Id_Besoin
				FROM form_session_personne
				WHERE form_session_personne.Id NOT IN 
					(
					SELECT Id_Session_Personne
					FROM form_session_personne_qualification
					WHERE Suppr=0	
					) AND Suppr=0
				)
			";
		$ResultFormationsSansQualification=mysqli_query($bdd,$ReqFormationsSansQualification);
		$NbFormationsSansQualification=mysqli_num_rows($ResultFormationsSansQualification);
		if($NbFormationsSansQualification == 0){$QualifAJour=0;}
		
		//Verif si la personne n'a pas la formation sans qualification des anciennes formations
		$MilieuRequete="";
		$ReqMilieu="
            SELECT
                DISTINCT form_formation_formationcompetence.Id_FormationCompetence
		    FROM
                form_formation_formationcompetence
		    WHERE
                form_formation_formationcompetence.Id_Formation=".$Id_Formation."
		        AND form_formation_formationcompetence.Suppr=0";
		$ResultMilieu=mysqli_query($bdd,$ReqMilieu);
		while($RowMilieu=mysqli_fetch_array($ResultMilieu)){$MilieuRequete.=$RowMilieu[0].",";}
		$MilieuRequete=substr($MilieuRequete,0,-1);
	    
	    $reqFormationCompetence="
			SELECT Id 
			FROM new_competences_personne_formation
			WHERE 
			Id_Personne=".$Id_Personne."
			AND Id_Formation IN (".$MilieuRequete.")";
		if($MilieuRequete<>"")
		{
			$resultFormationCompetence=mysqli_query($bdd,$reqFormationCompetence);
			$NbFormationCompetence=mysqli_num_rows($resultFormationCompetence);
			if($NbFormationCompetence>0){$QualifAJour=1;}
		}
	}
	
	//On regarde les qualifs masquées
	$reqQualif="SELECT DISTINCT
                    Id_Qualification,
				    (SELECT Libelle FROM new_competences_qualification WHERE new_competences_qualification.Id=Id_Qualification) AS Qualif 
				FROM
                    form_formation_qualification 
				WHERE
                    Id_Formation=".$Id_Formation." 
				    AND Suppr=0 
				    AND Masquer=1 ";
	$resultQualif=mysqli_query($bdd,$reqQualif);
	$NbQualif=mysqli_num_rows($resultQualif);
	
	//Si une des qualifs est à jour alors = 1 
	if($NbQualif>0)
	{
		while($rowQualif=mysqli_fetch_array($resultQualif))
		{
			if($rowQualif['Id_Qualification']==2750 || $Id_Qualification==3777){$NoteMini=80;}
			$req="SELECT
					new_competences_relation.Id,
					new_competences_relation.Date_Debut,
					new_competences_relation.Date_Fin,
					new_competences_relation.Date_QCM,
					new_competences_relation.Id_Qualification_Parrainage, 
					new_competences_relation.Evaluation,
					new_competences_qualification.Duree_Validite
				FROM
                    new_competences_relation
				LEFT JOIN new_competences_qualification
				    ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
				WHERE
					new_competences_relation.Id_Personne=".$Id_Personne."
					AND new_competences_relation.Type='Qualification'
					AND new_competences_relation.Visible=0
					AND new_competences_relation.Suppr=0
					AND new_competences_relation.Evaluation<>''
					AND new_competences_relation.Evaluation<>'B'
					AND new_competences_relation.Id_Qualification_Parrainage=".$rowQualif['Id_Qualification']."
					AND (
    						new_competences_relation.Evaluation='L' 
    						OR new_competences_relation.Evaluation='T' 
    						OR
                            (
                                new_competences_qualification.Duree_Validite=0
                                OR
                                (
                                    new_competences_relation.Date_Fin>='".date('Y-m-d',strtotime('+4 months',strtotime(date('Y-m-d'))))."'
                                )
                            )
						)
                    AND (
                            new_competences_relation.Resultat_QCM>='".$NoteMini."'
                            OR new_competences_relation.Resultat_QCM=''
                            OR new_competences_relation.Resultat_QCM='NA'
							OR new_competences_relation.Resultat_QCM='NC'
                        ) ";
			$resultQualifPersonne=mysqli_query($bdd,$req);
			$nbQualifPersonne=mysqli_num_rows($resultQualifPersonne);
			if($nbQualifPersonne>0){$QualifAJour=1;}
		}
	}

	return $QualifAJour;
}

/**
 * Get_SQL_PrestationsResponsablesPourPersonne
 *
 * Cette fonction permet de récupérer une chaine SQL contenant certains éléments concentrés sur les prestations (d'une plateforme) d'une personne
 * dont elle apparrait comme responsable parmi les postes passés en paramètres 
 *
 * @param 	int 	$Id_Plateforme 		Identifiant de la plateforme
 * @param	bool	$AfficheToutes		Booleen permetant de choisir si on affiche toutes les prestations ou juste les prestations filtrées par rapport au poste et la personne
 * @param 	array	$Tableau_Postes 	Tableau des postes pour lesquels on veut faire la vérification
 * @return 	string	Chaine SQL
 *
 * @author Rémy PARRAN <rparran@aaa-aero.com>
 */
function Get_SQL_PrestationsResponsablesPourPersonne($Id_Plateforme, $AfficheToutes, $Tableau_Postes)
{
	global $IdPersonneConnectee;
    
    if($AfficheToutes)
	{
		$ReqPrestations="
			SELECT
				Id AS Id_Prestation,
				Libelle,
				0 AS Id_Pole,
				'' AS Pole
			FROM
				new_competences_prestation
			WHERE
				Id_Plateforme=".$Id_Plateforme."
				AND new_competences_prestation.Active=0
				AND Id
					NOT IN (SELECT Id_Prestation FROM new_competences_pole WHERE Actif=0)
			UNION
			SELECT
				Id_Prestation,
				new_competences_prestation.Libelle,
				new_competences_pole.Id AS Id_Pole,
				CONCAT(' - ',new_competences_pole.Libelle) AS Pole
			FROM
				new_competences_pole
			LEFT JOIN
				new_competences_prestation
				ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
			WHERE
				Id_Plateforme=".$Id_Plateforme."
				AND new_competences_prestation.Active=0
				AND new_competences_pole.Actif=0
			ORDER BY
				Libelle,
				Pole";
	}
	else
	{
		$ReqPrestations="
			SELECT
				Id AS Id_Prestation,
				Libelle,
				0 AS Id_Pole,
				'' AS Pole
			FROM
				new_competences_prestation
			WHERE
				Id_Plateforme=".$Id_Plateforme."
				AND Id
					NOT IN (SELECT Id_Prestation FROM new_competences_pole WHERE Actif=0)
				AND
				(
					SELECT
						COUNT(Id)
					FROM
						new_competences_personne_poste_prestation
					WHERE
						Id_Poste IN (".implode(",",$Tableau_Postes).")
					AND Id_Personne=".$IdPersonneConnectee."
							AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
				)>0
			UNION
			SELECT
				Id_Prestation,
				new_competences_prestation.Libelle,
				new_competences_pole.Id AS Id_Pole,
				CONCAT(' - ',new_competences_pole.Libelle) AS Pole
			FROM
				new_competences_pole
			LEFT JOIN
				new_competences_prestation
				ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
			WHERE
				Id_Plateforme=".$Id_Plateforme."
			AND
			(
				SELECT
					COUNT(Id)
				FROM
					new_competences_personne_poste_prestation
				WHERE
					Id_Poste IN (".implode(",",$Tableau_Postes).")
					AND Id_Personne=".$IdPersonneConnectee."
					AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
					AND new_competences_personne_poste_prestation.Id_Pole=new_competences_pole.Id
			)>0
			ORDER BY
				Libelle,
				Pole";
	}
	
	return $ReqPrestations;
}

/**
 * Get_SQL_InformationsPourFormation
 *
 * Cette fonction permet de récupérer des informations simples concernant la formation
 *
 * @param 	int 	$Id_Plateforme 		Identifiant de la plateforme
 * @param	string	$Id_Formation		Identifiant de la formation ou Chaine de caractère SQL précisant la formation
 * @return 	string	Chaine SQL
 *
 * @author Rémy PARRAN <rparran@aaa-aero.com>
 */
function Get_SQL_InformationsPourFormation($Id_Plateforme, $Id_Formation)
{
	$Req="
		SELECT
			(SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS Organisme,
			(
				SELECT
					Libelle
				FROM
					form_formation_langue_infos
				WHERE
					form_formation_langue_infos.Id_Langue=form_formation_plateforme_parametres.Id_Langue
					AND Id_Formation=form_formation.Id
					AND Suppr=0
			) AS Libelle,
			(
				SELECT
					LibelleRecyclage
				FROM
					form_formation_langue_infos
				WHERE
					form_formation_langue_infos.Id_Langue=form_formation_plateforme_parametres.Id_Langue
					AND Id_Formation=form_formation.Id
					AND Suppr=0
			) AS LibelleRecyclage,
			form_formation.Id_TypeFormation,
			(SELECT Libelle FROM form_typeformation WHERE form_typeformation.Id=form_formation.Id_TypeFormation) AS TypeFormation
		FROM
			form_formation_plateforme_parametres
		LEFT JOIN
			form_formation
			ON form_formation_plateforme_parametres.Id_Formation=form_formation.Id
		WHERE
			form_formation_plateforme_parametres.Id_Plateforme=".$Id_Plateforme."
			AND form_formation_plateforme_parametres.Suppr=0
			AND form_formation.Id=".$Id_Formation;
	return $Req;
}
//Renvoie "true" si la personne connectée apparait dans un des postes du tableau passé en paramètres sur la prestation en paramètre
//Paramètres : Tableau des postes à vérifier en "OR"
function DroitsAUnePrestation($TableauIdPoste,$Id_Prestation,$Id_Pole)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $DateJour;
	
	$DroitsAUnePrestation=false;
	$ReqDroits= "
		SELECT
			Id
		FROM
			new_competences_personne_poste_prestation
		WHERE
			Id_Personne=".$IdPersonneConnectee."
			AND Id_Poste IN (".implode(",",$TableauIdPoste).")
			AND Id_Prestation = ".$Id_Prestation."
			AND Id_Pole = ".$Id_Pole." ";
	$ResultDroits=mysqli_query($bdd,$ReqDroits);
	$NbEnregDroits=mysqli_num_rows($ResultDroits);
	if($NbEnregDroits>0){$DroitsAUnePrestation=true;}
	
	return $DroitsAUnePrestation;
}

/**
 * Get_EvaluationReussiteETNoteTheorique
 *
 * Cette fonction permet de calculer l'évalutaion à une qualification en fonction de la note obtenue et si c'est réussi
 *
 *
 * @param 	int 	$Id_Personne 		Identifiant de la personne
 * @param 	int 	$Id_Qualification 	Identifiant de la qualification liée au besoin
 * @param 	int 	$Note 				Note obtenue
 * @return 	String[] 	$tab	Reussite ET Lettre Obtenue
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function Get_EvaluationReussiteETNoteTheorique($Id_Personne, $Id_Qualification, $Note)
{
	global $IdTypeFormationInterne;
	
	$tab=array();
	$LettreRetournee="";
	$Reussite=-1;
	
	//Récupération du tableau (Id_Metier,Métier,Col) de la personne
	$Metier_Personne=Get_Metier($Id_Personne);
	$Id_Metier_Personne=$Metier_Personne[0];
	$Col_Metier_Personne=$Metier_Personne[2];
	if($Col_Metier_Personne==""){$Col_Metier_Personne="Blanc";}
	
	//Récupération de la lettre théorique en fonction de la qualitication et du métier de la personne
	$LettreTheorique=Get_LettreMetierQualification($Id_Qualification, $Id_Metier_Personne);
	//Compilation en fonction du col, de la note et de la lettre
	switch($Col_Metier_Personne)
	{
		case "Bleu":
			$NoteMini=70;
			break;
		default:
			$NoteMini=80;
			break;
	}
	if($Id_Qualification==2750 || $Id_Qualification==3777){$NoteMini=80;}
	
	if($Note>=$NoteMini){
		$LettreRetournee=$LettreTheorique;
		$Reussite=1;
	}
	
	//Cas du test d'anglais --> toujours réussi avec Note = Low / Medium / High
	if($Id_Qualification==22){
		if($Note<50){$LettreRetournee="Low";}
		elseif($Note<80){$LettreRetournee="Medium";}
		elseif($Note>=80){$LettreRetournee="High";}
		
		$Reussite=1;
	}
	
	$tab[0]=$LettreRetournee;
	$tab[1]=$Reussite;
	
	return $tab;
}

/**
 * Set_ReussiteQCM
 *
 * Cette fonction permet de mettre à jour les différentes tables en fonction de la note obtenue au QCM (interne ou externe) et de calculer si réussite
 * (Partie 2 du projet)
 *
 * @param 	int 	$Id_Besoin 							Identifiant du besoin
 * @param 	int 	$Id_Personne 						Identifiant de la personne
 * @param 	int 	$Id_Qualification 					Identifiant de la qualification liée au besoin
 * @param 	int		$Id_Session_Personne_Qualification	Identifiant de la session de la personne en fonction de la qualification
 * @param 	int 	$Note 								Note obtenue
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function Set_ReussiteQCM($Id_Besoin, $Id_Personne, $Id_Qualification, $Id_Session_Personne_Qualification, $Note)
{
	global $bdd;
	global $DateJour;
	global $IdPersonneConnectee;
	global $IdTypeFormationInterne;

	$laNote=$Note;
	$LettreEvaluationNoteTheoriqueETReussite=Get_EvaluationReussiteETNoteTheorique($Id_Personne, $Id_Qualification, $laNote);
	
	$LettreEvaluationNoteTheorique=$LettreEvaluationNoteTheoriqueETReussite[0];
	$Etat=$LettreEvaluationNoteTheoriqueETReussite[1];

	//Lorsque les besoins ont été créés suite à une mauvaise note ou une absence sur une session
	//Si on décide de remettre en présence la personne :
	//Suppression des besoins et des B de la gestion des compétences
	//------------------------------------------------------------------------------------------
	$ReqIdFormation="SELECT Id_Formation FROM form_besoin WHERE Id=".$Id_Besoin;
	$ResultIdFormation=mysqli_query($bdd, $ReqIdFormation);
	$RowIdFormation=mysqli_fetch_array($ResultIdFormation);
	
	$req="SELECT Id_Session 
	FROM form_session_personne_qualification
	LEFT JOIN form_session_personne 
	ON form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id
	WHERE form_session_personne_qualification.Suppr=0 
	AND form_session_personne.Suppr=0
	AND form_session_personne_qualification.Id=".$Id_Session_Personne_Qualification." ";
	$ResultIdSession=mysqli_query($bdd, $req);
	$RowSession=mysqli_fetch_array($ResultIdSession);
	$nbSession2=mysqli_num_rows($ResultIdSession);
	
	if($nbSession2>0 && $RowSession['Id_Session']>0){
		$req="SELECT DateSession 
		FROM form_session_date 
		WHERE Suppr=0 
		AND Id_Session=".$RowSession['Id_Session']." 
		ORDER BY DateSession DESC ";
		$ResultDateSession=mysqli_query($bdd, $req);
		$RowDateSession=mysqli_fetch_array($ResultDateSession);
		$laDate=$RowDateSession['DateSession'];
	}
	else{
		$req="SELECT LEFT(DateHeureRepondeur,10) AS DateHeureRepondeur
		FROM form_session_personne_qualification
		WHERE form_session_personne_qualification.Suppr=0 
		AND form_session_personne_qualification.Id=".$Id_Session_Personne_Qualification." ";
		$ResultPerQuali=mysqli_query($bdd, $req);
		$RowPerQuali=mysqli_fetch_array($ResultPerQuali);
		$laDate=$RowPerQuali['DateHeureRepondeur'];
	}
	
	//Suppression des B
	$ReqSuppBCompetences="
		UPDATE
			new_competences_relation
		SET
			Suppr=1,
            Id_Modificateur=".$IdPersonneConnectee.",
			Date_Modification='".$DateJour."'
		WHERE
			Id_Besoin IN
			(
				SELECT
					Id
				FROM
					form_besoin
				WHERE
					Id_Formation=".$RowIdFormation['Id_Formation']."
					AND Id_Personne=".$Id_Personne."
					AND Id <> ".$Id_Besoin."
					AND Commentaire<>'Suite à une formation liée'
					AND Traite=0
					AND Suppr=0
			)";
	$ResultSuppBCompetences=mysqli_query($bdd, $ReqSuppBCompetences);
	
	//Suppression des Besoins
	$ReqSuppBesoin="
		UPDATE
			form_besoin
		SET
			Suppr=1,
            Motif_Suppr='Depuis la fonction Set_EvaluationNote',
            Id_Personne_MAJ=".$IdPersonneConnectee.",
            Date_MAJ='".$DateJour."'
		WHERE
			Id_Formation=".$RowIdFormation['Id_Formation']."
			AND Id_Personne=".$Id_Personne."
			AND Id <> ".$Id_Besoin."
			AND Commentaire<>'Suite à une formation liée'
			AND Traite=0
			AND Suppr=0";
	$ResultSuppBesoin=mysqli_query($bdd, $ReqSuppBesoin);
	//------------------------------------------------------------------------------------------
	switch($LettreEvaluationNoteTheorique)
	{	
		case "Low":
		case "Medium":
		case "High":
			//MAJ dans la gestion des compétences
			$ReqCompetencesMAJ="
				UPDATE
					new_competences_relation
				SET
					Date_Debut='".$laDate."',
					Resultat_QCM='".$Note."',
					Evaluation='".$LettreEvaluationNoteTheorique."',
					Id_Modificateur=".$IdPersonneConnectee.",
					Date_Modification='".$DateJour."',
					Date_QCM='".$laDate."',
					Suppr=0
				WHERE
					Id_Besoin=".$Id_Besoin."
					AND Id_Qualification_Parrainage=".$Id_Qualification;
			$ResultCompetencesMAJ=mysqli_query($bdd,$ReqCompetencesMAJ);
			break;
		case "L":
		case "T":
			//MAJ dans la gestion des compétences
			$ReqCompetencesMAJ="
				UPDATE
					new_competences_relation
				SET
					Resultat_QCM='".$Note."',
					Evaluation='".$LettreEvaluationNoteTheorique."',
					Id_Modificateur=".$IdPersonneConnectee.",
					Date_Modification='".$DateJour."',
					Date_QCM='".$laDate."',
					Suppr=0
				WHERE
					Id_Besoin=".$Id_Besoin."
					AND Id_Qualification_Parrainage=".$Id_Qualification;
			$ResultCompetencesMAJ=mysqli_query($bdd,$ReqCompetencesMAJ);
			break;
		case "S":
		case "V":
		case "X":
			//MAJ dans la gestion des compétences
			$ReqQualification="
				SELECT
					Duree_Validite
				FROM
					new_competences_qualification
				WHERE
					Id=".$Id_Qualification;
			$ResultQualification=mysqli_query($bdd,$ReqQualification);
			$RowQualification=mysqli_fetch_array($ResultQualification);
			
			$ReqCompetencesMAJ="
				UPDATE
					new_competences_relation
				SET
					Resultat_QCM='".$Note."',
					Evaluation='".$LettreEvaluationNoteTheorique."',
					Date_QCM='".$laDate."',
					Suppr=0,
					Date_Debut='".$laDate."',";
			if($RowQualification['Duree_Validite']<>0){$ReqCompetencesMAJ.="Date_Fin=DATE_ADD('".$laDate."', INTERVAL ".$RowQualification['Duree_Validite']." MONTH),";}
			else{$ReqCompetencesMAJ.="Sans_Fin='Oui',";}
			$ReqCompetencesMAJ.="
					Id_Modificateur=".$IdPersonneConnectee.",
					Date_Modification='".$DateJour."'
				WHERE
					Id_Besoin=".$Id_Besoin."
					AND Id_Qualification_Parrainage=".$Id_Qualification;
			$ResultCompetencesMAJ=mysqli_query($bdd,$ReqCompetencesMAJ);
			break;
		case "":
			//MAJ dans la gestion des compétences
			$ReqCompetencesMAJ="
				UPDATE
					new_competences_relation
				SET
					Resultat_QCM='".$Note."',
					Evaluation='".$LettreEvaluationNoteTheorique."',
					Date_QCM='".$laDate."',
					Id_Modificateur=".$IdPersonneConnectee.",
					Date_Modification='".$DateJour."',
					Suppr=0
				WHERE
					Id_Besoin=".$Id_Besoin."
					AND Id_Qualification_Parrainage=".$Id_Qualification;
			$ResultCompetencesMAJ=mysqli_query($bdd,$ReqCompetencesMAJ);
			//Génération d'un nouveau besoin (Workflow des besoins)
			//Et dans la gestion des compétences
			//En vérifiant si le besoin n'est pas déjà crée
			Creer_BesoinsFormations_PersonnePrestationMetier($Id_Personne, 0, 0, 0, "Suite à échec", $Id_Besoin);
			break;
	}

	//Mise à jour des la table form_session_personnne_qualification
	$ReqSessionPersonneQualificationMAJ="
		UPDATE
			form_session_personne_qualification
		SET
			Resultat='".$Note."',
			Etat=".$Etat."
		WHERE
			Id=".$Id_Session_Personne_Qualification;
	$ResultSessionPersonneQualificationMAJ=mysqli_query($bdd,$ReqSessionPersonneQualificationMAJ);
}

/**
 * Supprimer_BesoinsIncorrects
 *
 * Cette fonction permet de supprimer les faux B et les faux besoins
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function Supprimer_BesoinsIncorrects($Id_Prestation,$Id_Pole,$Id_Personne)
{
	global $bdd;
	
	//Récupération de la liste des B 
	$req="
        SELECT
            new_competences_relation.Id, 
            new_competences_relation.Id_Besoin, 
            new_competences_relation.Id_Personne,
            form_besoin.Id_Formation,
			form_besoin.Id_Prestation,
			form_besoin.Id_Pole,
			form_besoin.Motif
        FROM
            new_competences_relation 
        LEFT JOIN form_besoin
            ON new_competences_relation.Id_Besoin=form_besoin.Id
        WHERE
            new_competences_relation.Suppr=0 
        	AND new_competences_relation.Evaluation='B' 
			AND form_besoin.Motif<>'Nouveau'
        	AND new_competences_relation.Id_Besoin>0 ";
	
	if($Id_Prestation>0){
		$req.="AND form_besoin.Id_Prestation=".$Id_Prestation." ";
	}
	if($Id_Pole>0){
		$req.="AND form_besoin.Id_Pole=".$Id_Pole." ";
	}
	if($Id_Personne>0){
		$req.="AND new_competences_relation.Id_Personne=".$Id_Personne." ";
	}
	$ResultRelation=mysqli_query($bdd,$req);
	$NbRelation=mysqli_num_rows($ResultRelation);
	
	$i=0;
	if($NbRelation>0)
	{
		while($rowRelation=mysqli_fetch_array($ResultRelation))
		{
			//Verifier si la qualif n'est pas déjà à jour
			if($rowRelation['Motif']<>"Renouvellement"){
				if(ExisteFormationPrestaPole($rowRelation['Id_Formation'],$rowRelation['Id_Personne'],$rowRelation['Id_Prestation'],$rowRelation['Id_Pole'])==0 || Get_NbBesoinExistant($rowRelation['Id_Personne'], $rowRelation['Id_Formation'])>1 || Get_QualifAJour($rowRelation['Id_Personne'], $rowRelation['Id_Formation'])>0)
				{
					//Suppression du besoin
					$req="UPDATE form_besoin 
						SET Suppr=1,
						Motif_Suppr='Besoin déjà existant ".date('Y-m-d')."'				
						WHERE Id=".$rowRelation['Id_Besoin'];
					$ResultUpdate=mysqli_query($bdd,$req);
					
					//Suppression du B 
					$req="UPDATE new_competences_relation
						SET Suppr=1 
						WHERE Id_Besoin=".$rowRelation['Id_Besoin'];
					$ResultUpdate=mysqli_query($bdd,$req);
					$i++;
				}
			}
			else{
				if(ExisteFormationPrestaPole($rowRelation['Id_Formation'],$rowRelation['Id_Personne'],$rowRelation['Id_Prestation'],$rowRelation['Id_Pole'])==0 || Get_NbBesoinExistant($rowRelation['Id_Personne'], $rowRelation['Id_Formation'])>1 || Get_QualifAJourDans4Mois($rowRelation['Id_Personne'], $rowRelation['Id_Formation'])>0)
				{
					//Suppression du besoin
					$req="UPDATE form_besoin 
						SET Suppr=1,
						Motif_Suppr='Besoin déjà existant ".date('Y-m-d')."'				
						WHERE Id=".$rowRelation['Id_Besoin'];
					$ResultUpdate=mysqli_query($bdd,$req);
					
					//Suppression du B 
					$req="UPDATE new_competences_relation
						SET Suppr=1 
						WHERE Id_Besoin=".$rowRelation['Id_Besoin'];
					$ResultUpdate=mysqli_query($bdd,$req);
					
					$i++;
				}
			}
		}
	}
}

/**
 * Supprimer_BesoinsSansQualifIncorrects
 *
 * Cette fonction permet de supprimer les faux besoins
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function Supprimer_BesoinsSansQualifIncorrects($Id_Prestation,$Id_Pole,$Id_Personne)
{
	global $bdd;
	
	//Récupération de la liste des B 
	$req="
        SELECT
            form_besoin.Id,
            form_besoin.Id_Formation,
            form_besoin.Id_Personne,
			form_besoin.Id_Prestation,
			form_besoin.Id_Pole
		FROM
            form_besoin 
		WHERE
            form_besoin.Id_Formation IN (SELECT DISTINCT Id_Formation FROM form_formation_formationcompetence WHERE Suppr=0)
    		AND Suppr=0
			AND form_besoin.Motif<>'Renouvellement'
			AND form_besoin.Motif<>'Nouveau'
    		AND Traite=0 ";
	if($Id_Prestation>0){
		$req.="AND form_besoin.Id_Prestation=".$Id_Prestation." ";
	}
	if($Id_Pole>0){
		$req.="AND form_besoin.Id_Pole=".$Id_Pole." ";
	}
	if($Id_Personne>0){
		$req.="AND form_besoin.Id_Personne=".$Id_Personne." ";
	}
	$ResultB=mysqli_query($bdd,$req);
	$NbB=mysqli_num_rows($ResultB);
	
	$i=0;
	if($NbB>0)
	{
		while($rowB=mysqli_fetch_array($ResultB))
		{
			//Verifier si la qualif n'est pas déjà à jour
		    if(ExisteFormationPrestaPole($rowB['Id_Formation'],$rowB['Id_Personne'],$rowB['Id_Prestation'],$rowB['Id_Pole'])==0 || Get_NbBesoinExistant($rowB['Id_Personne'], $rowB['Id_Formation'])>1 || Get_QualifAJour($rowB['Id_Personne'], $rowB['Id_Formation'])>0)
		    {
		        //Suppression du besoin
				$req="UPDATE form_besoin 
					SET Suppr=1,
					Motif_Suppr='Besoin déjà existant ".date('Y-m-d')."'	
					WHERE Id=".$rowB['Id'];
				echo $req."<br><br>";
				$ResultUpdate=mysqli_query($bdd,$req);
				
				//Suppression du B
				$req="UPDATE new_competences_relation 
					SET Suppr=1 
					WHERE Id_Besoin=".$rowB['Id'];
				$ResultUpdate=mysqli_query($bdd,$req);
				$i++;
			}
		}
	}
}

function ExisteFormationPrestaPole($Id_Formation,$Id_Personne,$Id_Prestation,$Id_Pole){
	global $bdd;
	$Existe=0;
	
	$Metier_Personne=Get_Metier($Id_Personne);
	
	$req="SELECT Id 
		FROM form_prestation_metier_formation 
		WHERE Suppr=0 
		AND Id_Formation=".$Id_Formation." 
		AND Id_Metier=".$Metier_Personne[0]."  
		AND Id_Prestation=".$Id_Prestation."  
		AND Id_Pole=".$Id_Pole."  ";
	$Result=mysqli_query($bdd,$req);
	$NbResult=mysqli_num_rows($Result);
	if($NbResult>0){
		$Existe=1;
	}
	return $Existe;
}
/**
 * Supprimer_VieuxB
 *
 * Cette fonction permet de supprimer les vieux B
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function Supprimer_VieuxB()
{
	global $bdd;
	
	//Suppression du besoin
	$req="UPDATE new_competences_relation 
		SET Suppr=1, Date_MAJ_Manuelle='".date('Y-m-d')."'
		WHERE Suppr=0 
		AND Evaluation='B'
		AND Id_Besoin=0
		AND (SELECT COUNT(Id_Plateforme) 
			 FROM new_competences_personne_plateforme 
			 WHERE new_competences_personne_plateforme.Id_Personne=new_competences_relation.Id_Personne
			 AND new_competences_personne_plateforme.Id_Plateforme IN (1,23))>0
		AND (SELECT COUNT(new_competences_prestation.Id_Plateforme) 
			FROM new_competences_personne_prestation
			LEFT JOIN new_competences_prestation 
			ON new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
			WHERE new_competences_prestation.Id_Plateforme IN (1,23)
			AND new_competences_personne_prestation.Id_Personne=new_competences_relation.Id_Personne
			AND new_competences_personne_prestation.Date_Debut<='2018-10-02'
			AND (new_competences_personne_prestation.Date_Fin>='2018-10-02' OR new_competences_personne_prestation.Date_Fin<='0001-01-01'))>0
		AND new_competences_relation.Id_Personne NOT IN (10176,91,6177,2223,4151,10163,2949,1135,996,3371,9359,7818,2086,439,9308,64,3431,2825,4460)";
	$ResultUpdate=mysqli_query($bdd,$req);

}

/**
 * BesoinsManquants
 *
 * Cette fonction permet de recréer les besoins manquants 
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function RecreerBesoinsManquants($Id_Formation = 0)
{
	global $bdd;
	
	//Récupération de la liste des prestations et pôles ayant la configuration de faite
	$req="
        SELECT DISTINCT
            Id_Prestation,
            Id_Pole,
            Id_Metier,
            Id_Formation,
            Obligatoire
		FROM
            form_prestation_metier_formation 
		WHERE
            Suppr=0 ";
	if($Id_Formation>0){$req.="AND Id_Formation=".$Id_Formation ;}
	$ResultBPresta=mysqli_query($bdd,$req);
	$NbBPresta=mysqli_num_rows($ResultBPresta);
	
	$i=0;
	$Id_personnes_pour_MAJ = Array();
	if($NbBPresta>0)
	{
		while($rowBPresta=mysqli_fetch_array($ResultBPresta))
		{
			//Récupération de la liste des personnes de la prestation choisie
			$ReqPersonnePrestation="
				SELECT
					Id_Personne
				FROM
					new_competences_personne_prestation
				WHERE
					Id_Prestation=".$rowBPresta['Id_Prestation']." 
					AND Id_Pole=".$rowBPresta['Id_Pole']."
					AND Date_Fin >= '".date('Y-m-d')."'";
			$ResultPersonnePrestation=mysqli_query($bdd,$ReqPersonnePrestation);
			$nbPersonnePrestation=mysqli_num_rows($ResultPersonnePrestation);
			if($nbPersonnePrestation>0)
			{
				while($RowPersonnePrestation=mysqli_fetch_array($ResultPersonnePrestation))
				{
					$ResultMetierPersonne=Get_LesMetiersFutur($RowPersonnePrestation['Id_Personne']);
						$nbPersonnePrestation=mysqli_num_rows($ResultMetierPersonne);
						if($nbPersonnePrestation>0){
							while($Metier_Personne=mysqli_fetch_array($ResultMetierPersonne))
							{
								$Id_Metier_Personne=$Metier_Personne[0];
								$LIBELLE_METIER="";
								if($Metier_Personne[1]<>""){$LIBELLE_METIER=" (".$Metier_Personne[1].")";}
								$Motif="Nouveau besoin pour ce métier".$LIBELLE_METIER." et cette prestation";
								Creer_BesoinsFormations_PersonnePrestationMetier($RowPersonnePrestation['Id_Personne'], $rowBPresta['Id_Prestation'], $rowBPresta['Id_Pole'], $Id_Metier_Personne, $Motif, 0,$rowBPresta['Id_Formation'],-1);
							}
						}
						else{
							$ResultMetierPersonne=Get_LesMetiersNonFutur($RowPersonnePrestation['Id_Personne']);
							$nbPersonnePrestation=mysqli_num_rows($ResultMetierPersonne);
							if($nbPersonnePrestation>0){
								while($Metier_Personne=mysqli_fetch_array($ResultMetierPersonne))
								{
									$Id_Metier_Personne=$Metier_Personne[0];
									$LIBELLE_METIER="";
									if($Metier_Personne[1]<>""){$LIBELLE_METIER=" (".$Metier_Personne[1].")";}
									$Motif="Nouveau besoin pour ce métier".$LIBELLE_METIER." et cette prestation";
									Creer_BesoinsFormations_PersonnePrestationMetier($RowPersonnePrestation['Id_Personne'], $rowBPresta['Id_Prestation'], $rowBPresta['Id_Pole'], $Id_Metier_Personne, $Motif, 0,$rowBPresta['Id_Formation'],-1);
								}
							}
						}
					
					// Pour le passage en obligatoire ou facultatif
					$reqMetierFutur="
						SELECT
							Id_Personne
						FROM
							new_competences_personne_metier
						WHERE
							Id_Personne=".$RowPersonnePrestation['Id_Personne']."
							AND Id_Metier=".$rowBPresta['Id_Metier']."
							AND Futur=1;";
					
					$reqMetier="
						SELECT
							Id_Personne
						FROM
							new_competences_personne_metier
						WHERE
							Id_Personne=".$RowPersonnePrestation['Id_Personne']."
							AND Id_Metier=".$rowBPresta['Id_Metier']."
							AND Futur=0;";
					
					$resMetier = getRessource($reqMetier);
											
					if(mysqli_num_rows($resMetier) == 0){$resMetier = getRessource($reqMetierFutur);}
					
					if (mysqli_num_rows($resMetier) > 0)
					{
						//Ajoute l'Id_Personne				 		
						array_push($Id_personnes_pour_MAJ, $RowPersonnePrestation['Id_Personne']);
					}
				}
			}
			// Ticket sprint 3 Passer formation obligatoire en facultatif 				 	
			//#################################################################################################
			$Ids_Personne = implode(', ', $Id_personnes_pour_MAJ);
			
			/*$reqMAJ_enFacultatif="
				UPDATE
					form_besoin
				SET
					Obligatoire=0
				WHERE
					Id_Prestation=".$rowBPresta['Id_Prestation']."
					AND Id_Pole=".$rowBPresta['Id_Pole']."
					AND Id_Formation=".$rowBPresta['Id_Formation']."
					AND Traite=0
					AND Id_Personne IN (".$Ids_Personne.")
					AND Id_Personne_MAJ=0;";

			$reqMAJ_enObligatoire="
				UPDATE
					form_besoin
				SET
					Obligatoire=1,
					Valide=1
				WHERE
					Id_Prestation=".$rowBPresta['Id_Prestation']."
					AND Id_Pole=".$rowBPresta['Id_Pole']."
					AND Id_Formation=".$rowBPresta['Id_Formation']."
					AND Traite=0
					AND Id_Personne IN (".$Ids_Personne.");";
			
			$reqAncien="
				SELECT
					Obligatoire
				FROM
					form_prestation_metier_formation
				WHERE
					Id_Metier=".$rowBPresta['Id_Metier']."
					AND Id_Formation=".$rowBPresta['Id_Formation']."
					AND Id_Prestation=".$rowBPresta['Id_Prestation']."
					AND Id_Pole=".$rowBPresta['Id_Pole']."
					AND Suppr=0;";
			
			$resultatAncien = getRessource($reqAncien);
			$rowAncien = mysqli_fetch_array($resultatAncien);
			//Il faut qu'il y ait des personne affectées par la modification
			if(strlen($Ids_Personne) > 0)
			{
				//Passage en obligatoire
			    if($rowAncien['Obligatoire'] == 0 && $rowBPresta['Obligatoire'] == 1){getRessource($reqMAJ_enObligatoire);}

				//Passage en facultatif
			    if($rowAncien['Obligatoire'] == 1 && $rowBPresta['Obligatoire'] == 0){getRessource($reqMAJ_enFacultatif);}
			}*/
		}
	}
	echo $i;
}

/**
 * BesoinsManquants
 *
 * Cette fonction permet de recréer les besoins manquants 
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function RecreerBesoinsManquantsPrestation($Id_Prestation,$Id_Pole,$Id_Personne)
{
	global $bdd;
	
	//Récupération de la liste des prestations et pôles ayant la configuration de faite
	$req="
        SELECT DISTINCT
            Id_Metier,
            Id_Formation,
            Obligatoire
		FROM
            form_prestation_metier_formation 
		WHERE Suppr=0 
		AND Id_Prestation=".$Id_Prestation."
		AND Id_Pole=".$Id_Pole."
		";
	$ResultBPresta=mysqli_query($bdd,$req);
	$NbBPresta=mysqli_num_rows($ResultBPresta);
	
	$Id_personnes_pour_MAJ = Array();
	if($NbBPresta>0)
	{
		while($rowBPresta=mysqli_fetch_array($ResultBPresta))
		{
			$ResultMetierPersonne=Get_LesMetiersFutur($Id_Personne);
			$nbPersonnePrestation=mysqli_num_rows($ResultMetierPersonne);
			if($nbPersonnePrestation>0){
				while($Metier_Personne=mysqli_fetch_array($ResultMetierPersonne))
				{
					$Id_Metier_Personne=$Metier_Personne[0];
					$LIBELLE_METIER="";
					if($Metier_Personne[1]<>""){$LIBELLE_METIER=" (".$Metier_Personne[1].")";}
					$Motif="Nouveau besoin pour ce métier".$LIBELLE_METIER." et cette prestation";
					Creer_BesoinsFormations_PersonnePrestationMetier($Id_Personne, $Id_Prestation, $Id_Pole, $Id_Metier_Personne, $Motif, 0,$rowBPresta['Id_Formation'],-1);
				}
			}
			else{
				$ResultMetierPersonne=Get_LesMetiersNonFutur($Id_Personne);
				$nbPersonnePrestation=mysqli_num_rows($ResultMetierPersonne);
				if($nbPersonnePrestation>0){
					while($Metier_Personne=mysqli_fetch_array($ResultMetierPersonne))
					{
						$Id_Metier_Personne=$Metier_Personne[0];
						$LIBELLE_METIER="";
						if($Metier_Personne[1]<>""){$LIBELLE_METIER=" (".$Metier_Personne[1].")";}
						$Motif="Nouveau besoin pour ce métier".$LIBELLE_METIER." et cette prestation";
						Creer_BesoinsFormations_PersonnePrestationMetier($Id_Personne, $Id_Prestation, $Id_Pole, $Id_Metier_Personne, $Motif, 0,$rowBPresta['Id_Formation'],-1);
					}
				}
			}
		}
	}
}

/**
 * BesoinsManquants
 *
 * Cette fonction permet de recréer les besoins manquants 
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function RecreerBesoinsManquantsPrestationFormation($Id_Prestation,$Id_Pole,$Id_Formation,$Id_Metier,$Obligatoire)
{
	global $bdd;
	
	//Récupération de la liste des prestations et pôles ayant la configuration de faite
	$req="
        SELECT DISTINCT
            Id_Metier,
            Id_Formation,
            Obligatoire
		FROM
            form_prestation_metier_formation 
		WHERE Suppr=0 
		AND Id_Prestation=".$Id_Prestation."
		AND Id_Pole=".$Id_Pole."
		AND Id_Formation=".$Id_Formation."
		AND Id_Metier=".$Id_Metier."
		AND Obligatoire=".$Obligatoire."
		";
	$ResultBPresta=mysqli_query($bdd,$req);
	$NbBPresta=mysqli_num_rows($ResultBPresta);
	
	//Récupération de la liste des personnes de la prestation choisie
	$ReqPersonnePrestation="
		SELECT
			Id_Personne
		FROM
			new_competences_personne_prestation
		WHERE
			Id_Prestation=".$Id_Prestation." 
			AND Id_Pole=".$Id_Pole."
			AND Date_Fin >= '".date('Y-m-d')."'";
	$ResultPersonnePrestation=mysqli_query($bdd,$ReqPersonnePrestation);
	$nbPersonnePrestation=mysqli_num_rows($ResultPersonnePrestation);
	

	if($NbBPresta>0)
	{
		while($rowBPresta=mysqli_fetch_array($ResultBPresta))
		{
			if($nbPersonnePrestation>0)
			{
				mysqli_data_seek($ResultPersonnePrestation,0);
				while($RowPersonnePrestation=mysqli_fetch_array($ResultPersonnePrestation))
				{
					$ResultMetierPersonne=Get_LesMetiersFutur($RowPersonnePrestation['Id_Personne']);
					$nbPersonnePrestation=mysqli_num_rows($ResultMetierPersonne);
					if($nbPersonnePrestation>0){
						while($Metier_Personne=mysqli_fetch_array($ResultMetierPersonne))
						{
							$Id_Metier_Personne=$Metier_Personne[0];
							$LIBELLE_METIER="";
							if($Metier_Personne[1]<>""){$LIBELLE_METIER=" (".$Metier_Personne[1].")";}
							$Motif="Nouveau besoin pour ce métier".$LIBELLE_METIER." et cette prestation";
							Creer_BesoinsFormations_PersonnePrestationMetier($RowPersonnePrestation['Id_Personne'], $Id_Prestation, $Id_Pole, $Id_Metier_Personne, $Motif, 0,$rowBPresta['Id_Formation'],-1);
						}
					}
					else{
						$ResultMetierPersonne=Get_LesMetiersNonFutur($RowPersonnePrestation['Id_Personne']);
						$nbPersonnePrestation=mysqli_num_rows($ResultMetierPersonne);
						if($nbPersonnePrestation>0){
							while($Metier_Personne=mysqli_fetch_array($ResultMetierPersonne))
							{
								$Id_Metier_Personne=$Metier_Personne[0];
								$LIBELLE_METIER="";
								if($Metier_Personne[1]<>""){$LIBELLE_METIER=" (".$Metier_Personne[1].")";}
								$Motif="Nouveau besoin pour ce métier".$LIBELLE_METIER." et cette prestation";
								Creer_BesoinsFormations_PersonnePrestationMetier($RowPersonnePrestation['Id_Personne'], $Id_Prestation, $Id_Pole, $Id_Metier_Personne, $Motif, 0,$rowBPresta['Id_Formation'],-1);
							}
						}
					}
				}
			}
		}
	}
}

/**
 * BesoinsManquants
 *
 * Cette fonction permet de recréer les besoins manquants 
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function RecreerBesoinsManquantsPrestationFormation2($Id_Prestation,$Id_Pole,$Id_Personne,$Id_Formation)
{
	global $bdd;
	
	//Récupération de la liste des prestations et pôles ayant la configuration de faite
	$req="
        SELECT DISTINCT
            Id_Metier,
            Id_Formation,
            Obligatoire
		FROM
            form_prestation_metier_formation 
		WHERE Suppr=0 
		AND Id_Prestation=".$Id_Prestation."
		AND Id_Pole=".$Id_Pole."
		AND Id_Formation=".$Id_Formation."
		";
	$ResultBPresta=mysqli_query($bdd,$req);
	$NbBPresta=mysqli_num_rows($ResultBPresta);

	//Récupération de la liste des personnes de la prestation choisie
	$ReqPersonnePrestation="
		SELECT
			Id_Personne
		FROM
			new_competences_personne_prestation
		WHERE
			Id_Prestation=".$Id_Prestation." 
			AND Id_Pole=".$Id_Pole."
			AND Id_Personne=".$Id_Personne."
			AND Date_Fin >= '".date('Y-m-d')."'";
	$ResultPersonnePrestation=mysqli_query($bdd,$ReqPersonnePrestation);
	$nbPersonnePrestation=mysqli_num_rows($ResultPersonnePrestation);
	

	if($NbBPresta>0)
	{
		while($rowBPresta=mysqli_fetch_array($ResultBPresta))
		{
			if($nbPersonnePrestation>0)
			{
				mysqli_data_seek($ResultPersonnePrestation,0);
				while($RowPersonnePrestation=mysqli_fetch_array($ResultPersonnePrestation))
				{
					$ResultMetierPersonne=Get_LesMetiersFutur($RowPersonnePrestation['Id_Personne']);
					$nbPersonnePrestation=mysqli_num_rows($ResultMetierPersonne);
					if($nbPersonnePrestation>0){
						while($Metier_Personne=mysqli_fetch_array($ResultMetierPersonne))
						{
							$Id_Metier_Personne=$Metier_Personne[0];
							$LIBELLE_METIER="";
							if($Metier_Personne[1]<>""){$LIBELLE_METIER=" (".$Metier_Personne[1].")";}
							$Motif="Nouveau besoin pour ce métier".$LIBELLE_METIER." et cette prestation";
							Creer_BesoinsFormations_PersonnePrestationMetier($RowPersonnePrestation['Id_Personne'], $Id_Prestation, $Id_Pole, $Id_Metier_Personne, $Motif, 0,$rowBPresta['Id_Formation'],-1);
						}
					}
					else{
						$ResultMetierPersonne=Get_LesMetiersNonFutur($RowPersonnePrestation['Id_Personne']);
						$nbPersonnePrestation=mysqli_num_rows($ResultMetierPersonne);
						if($nbPersonnePrestation>0){
							while($Metier_Personne=mysqli_fetch_array($ResultMetierPersonne))
							{
								$Id_Metier_Personne=$Metier_Personne[0];
								$LIBELLE_METIER="";
								if($Metier_Personne[1]<>""){$LIBELLE_METIER=" (".$Metier_Personne[1].")";}
								$Motif="Nouveau besoin pour ce métier".$LIBELLE_METIER." et cette prestation";
								Creer_BesoinsFormations_PersonnePrestationMetier($RowPersonnePrestation['Id_Personne'], $Id_Prestation, $Id_Pole, $Id_Metier_Personne, $Motif, 0,$rowBPresta['Id_Formation'],-1);
							}
						}
					}
				}
			}
		}
	}
}

//Renvoie "true" si la personne connectée apparait dans un des postes du tableau passé en paramètres sur la prestation et le pole en paramètre
//Paramètres : Tableau des postes à vérifier en "OR"
//(pour éviter d'attendre le retour de plusieurs fonctions pour tester un même affichage pour plusieurs postes)
function DroitsPrestationPole($TableauIdPoste,$Id_Prestation,$Id_Pole)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $DateJour;
	
	$DroitsPrestationPole=false;
	$ReqDroits= "
		SELECT
			Id
		FROM
			new_competences_personne_poste_prestation
		WHERE
			Id_Personne=".$IdPersonneConnectee."
			AND Id_Poste IN (".implode(",",$TableauIdPoste).")
			AND Id_Prestation=".$Id_Prestation."
			AND Id_Pole=".$Id_Pole." ";
	$ResultDroits=mysqli_query($bdd,$ReqDroits);
	$NbEnregDroits=mysqli_num_rows($ResultDroits);
	if($NbEnregDroits>0){$DroitsPrestationPole=true;}
	
	return $DroitsPrestationPole;
}

//Renvoie "true" si la personne connectée apparait dans un des postes du tableau passé en paramètres sur la prestation et le pole en paramètre
//Paramètres : Tableau des postes à vérifier en "OR"
//(pour éviter d'attendre le retour de plusieurs fonctions pour tester un même affichage pour plusieurs postes)
function DroitsPrestation($TableauIdPoste,$Id_Prestation)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $DateJour;
	
	$DroitsPrestationPole=false;
	$ReqDroits= "
		SELECT
			Id
		FROM
			new_competences_personne_poste_prestation
		WHERE
			Id_Personne=".$IdPersonneConnectee."
			AND Id_Poste IN (".implode(",",$TableauIdPoste).")
			AND Id_Prestation=".$Id_Prestation." ";
	$ResultDroits=mysqli_query($bdd,$ReqDroits);
	$NbEnregDroits=mysqli_num_rows($ResultDroits);
	if($NbEnregDroits>0){$DroitsPrestationPole=true;}
	
	return $DroitsPrestationPole;
}

//Renvoie "true" si la personne connectée apparait dans un des postes du tableau passé en paramètres sur la prestation et le pole en paramètre
//Paramètres : Tableau des postes à vérifier en "OR"
//(pour éviter d'attendre le retour de plusieurs fonctions pour tester un même affichage pour plusieurs postes)
function DroitsPrestationPoleBackup($TableauIdPoste,$Id_Prestation,$Id_Pole,$Backup)
{
	global $bdd;
	global $IdPersonneConnectee;
	global $DateJour;
	
	$DroitsPrestationPole=false;
	$ReqDroits= "
		SELECT
			Id
		FROM
			new_competences_personne_poste_prestation
		WHERE
			Id_Personne=".$IdPersonneConnectee."
			AND Id_Poste IN (".implode(",",$TableauIdPoste).")
			AND Id_Prestation=".$Id_Prestation."
			AND Id_Pole=".$Id_Pole." ";
	if($Backup==0){
		$ReqDroits.="AND Backup=0 ";
	}
	else{
		$ReqDroits.="AND Backup>0 ";
	}
	$ResultDroits=mysqli_query($bdd,$ReqDroits);
	$NbEnregDroits=mysqli_num_rows($ResultDroits);
	if($NbEnregDroits>0){$DroitsPrestationPole=true;}
	
	return $DroitsPrestationPole;
}

//Renvoie "true" si la personne connectée apparait dans un des postes du tableau passé en paramètres sur la prestation et le pole en paramètre
//Paramètres : Tableau des postes à vérifier en "OR"
//(pour éviter d'attendre le retour de plusieurs fonctions pour tester un même affichage pour plusieurs postes)
function DroitsPrestationPoleBackupV2($Id_Personne,$TableauIdPoste,$Id_Prestation,$Id_Pole,$Backup)
{
	global $bdd;
	global $DateJour;
	
	$DroitsPrestationPole=false;
	$ReqDroits= "
		SELECT
			Id
		FROM
			new_competences_personne_poste_prestation
		WHERE
			Id_Personne=".$Id_Personne."
			AND Id_Poste IN (".implode(",",$TableauIdPoste).")
			AND Id_Prestation=".$Id_Prestation."
			AND Id_Pole=".$Id_Pole." ";
	if($Backup==0){
		$ReqDroits.="AND Backup=0 ";
	}
	else{
		$ReqDroits.="AND Backup>0 ";
	}
	$ResultDroits=mysqli_query($bdd,$ReqDroits);
	$NbEnregDroits=mysqli_num_rows($ResultDroits);
	if($NbEnregDroits>0){$DroitsPrestationPole=true;}
	
	return $DroitsPrestationPole;
}

//Renvoie "true" si la personne connectée apparait dans un des postes du tableau passé en paramètres sur la prestation et le pole en paramètre
//Paramètres : Tableau des postes à vérifier en "OR"
//(pour éviter d'attendre le retour de plusieurs fonctions pour tester un même affichage pour plusieurs postes)
function DroitsPrestationPoleV2($Id_Personne,$TableauIdPoste,$Id_Prestation,$Id_Pole)
{
	global $bdd;
	global $DateJour;
	
	$DroitsPrestationPole=false;
	$ReqDroits= "
		SELECT
			Id
		FROM
			new_competences_personne_poste_prestation
		WHERE
			Id_Personne=".$Id_Personne."
			AND Id_Poste IN (".implode(",",$TableauIdPoste).")
			AND Id_Prestation=".$Id_Prestation."
			AND Id_Pole=".$Id_Pole." ";
	$ResultDroits=mysqli_query($bdd,$ReqDroits);
	$NbEnregDroits=mysqli_num_rows($ResultDroits);
	if($NbEnregDroits>0){$DroitsPrestationPole=true;}
	
	return $DroitsPrestationPole;
}

function CopierQualif()
{
	global $bdd;
	
	$req="
        UPDATE
            new_competences_qualification
		SET
            Duree_Validite=48,
			Periodicite_Surveillance=24,
			Lettre_Theorie='L',
			Lettre_Pratique='Q',
            Nb_Page_Si_L=2 
		WHERE
            Id_Categorie_Qualification IN (112,113,125) 
    		AND Id<>1700 
    		AND Suppr=0";
	$ResultUpdate=mysqli_query($bdd,$req);
	
	$req="SELECT Id FROM new_competences_qualification WHERE new_competences_qualification.Id_Categorie_Qualification IN (112,113,125) AND Id<>1700 AND Suppr=0 ";
	$Result=mysqli_query($bdd,$req);
	$Nb=mysqli_num_rows($Result);

	if($Nb>0)
	{
		while($row=mysqli_fetch_array($Result))
		{
			
			$req="
				INSERT INTO
                    new_competences_qualification_metier_lettre
                (
                    Id_Qualification,
                    Id_Metier,
                    Lettre,
                    Theorique_Pratique
                )
				SELECT
                    ".$row['Id'].",
                    Id_Metier,Lettre,
                    Theorique_Pratique
				FROM
                    new_competences_qualification_metier_lettre 
				WHERE
                    new_competences_qualification_metier_lettre.Id_Qualification=1700 
				    AND Suppr=0";
			$ResultInsert=mysqli_query($bdd,$req);
			
			$req="
				INSERT INTO
                    new_competences_qualification_plateforme_infos
                (
                    Id_Qualification,
                    Id_Plateforme,
                    Avion,
                    Produit,
                    Client,
                    Doc_Applicable,
                    Formation_Initiale,
                    Formation_Specifique,
                    Experience,
                    Autre_Qualification
                )
				SELECT
                    ".$row['Id'].",
                    Id_Plateforme,
                    Avion,
                    Produit,
                    Client,
                    Doc_Applicable,
                    Formation_Initiale,
                    Formation_Specifique,
                    Experience,
                    Autre_Qualification
				FROM
                    new_competences_qualification_plateforme_infos 
				WHERE
                    Id_Qualification=1700
                    AND Suppr=0 ";
			$ResultInsert=mysqli_query($bdd,$req);
		}
	}
}

/**
 * Nombre de demande de besoin à traiter
 *
 * Cette fonction permet de retourner le nombre de demande de besoin à traiter
 *
 * @return 	int
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function NbDemandeBesoinATraiter()
{
	global $bdd;
	global $IdPosteResponsableFormation;
	global $IdPosteAssistantFormationInterne;
	global $IdPosteAssistantFormationExterne;
	global $IdPosteAssistantFormationTC;
	global $TableauIdPostesAF_RF_RQ_RH_CQS;
	global $IdPosteChefEquipe;
	global $IdPosteResponsableQualite; 
	global $IdPosteResponsableRH;
	global $IdTypeFormationEprouvette;
	global $IdTypeFormationTC;
	global $IdTypeFormationInterne;
	global $IdTypeFormationExterne;
	global $TableauIdPostesRespPresta_CQ;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	global $IdPosteReferentQualiteProduit;
	global $IdPosteReferentQualiteSysteme;
	
	
	$requete="SELECT form_demandebesoin.Id
			FROM form_demandebesoin
			LEFT JOIN new_competences_prestation ON form_demandebesoin.Id_Prestation=new_competences_prestation.Id
			WHERE Etat=0
			AND Suppr=0 ";
			if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS)){
				$requete.="AND new_competences_prestation.Id_Plateforme IN 
							(
								SELECT
									Id_Plateforme
								FROM
									new_competences_personne_poste_plateforme
								WHERE
									Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS).")
									AND Id_Personne=".$_SESSION["Id_Personne"]."
							) ";
			}
			else{
				$requete.="AND CONCAT(form_demandebesoin.Id_Prestation,'_',form_demandebesoin.Id_Pole) IN 
							(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
							) ";
			}
		$result=mysqli_query($bdd,$requete);
		$nbResulta=mysqli_num_rows($result);
	
	return $nbResulta;
}

/**
 * Nombre de demande de besoin à prendre en compte
 *
 * Cette fonction permet de retourner le nombre de demande de besoin à prendre en compte
 *
 * @return 	int
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function NbDemandeBesoinAPrendreEnCompte()
{
	global $bdd;
	global $IdPosteResponsableFormation;
	global $IdPosteAssistantFormationInterne;
	global $IdPosteAssistantFormationExterne;
	global $TableauIdPostesAF_RF_RQ_RH_CQS;
	global $IdPosteAssistantFormationTC;
	global $IdPosteResponsableQualite; 
	global $IdPosteResponsableRH;
	global $IdPosteChefEquipe;
	global $IdTypeFormationEprouvette;
	global $IdTypeFormationTC;
	global $IdTypeFormationInterne;
	global $IdTypeFormationExterne;
	global $TableauIdPostesRespPresta_CQ;
	global $IdPosteCoordinateurEquipe;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsableProjet;
	global $IdPosteReferentQualiteProduit;
	global $IdPosteReferentQualiteSysteme;
	
	$requete="SELECT form_demandebesoin.Id
			FROM form_demandebesoin
			LEFT JOIN new_competences_prestation ON form_demandebesoin.Id_Prestation=new_competences_prestation.Id
			WHERE Etat<>0
			AND Suppr=0
			AND Id_DemandeurPrisEnCompte=0 ";
			$requete.="AND CONCAT(form_demandebesoin.Id_Prestation,'_',form_demandebesoin.Id_Pole) IN 
						(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
						FROM new_competences_personne_poste_prestation 
						WHERE Id_Personne=".$_SESSION["Id_Personne"]."
						AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
						) ";
		$result=mysqli_query($bdd,$requete);
		$nbResulta=mysqli_num_rows($result);
	
	return $nbResulta;
}


/**
 * Nombre de surveillances à confirmer
 *
 * Cette fonction permet de retourner le nombre de surveillances à confirmer
 *
 * @return 	int
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function NbSurveillancesAConfirmer()
{
	global $bdd;
	global $IdPersonneConnectee;
	global $TableauIdPostesAF_RF_RQ;
	global $TableauIdPostesRespPresta_CQ;
	global $TableauIdPostesAFI_RF_RQ_FORM;



	$ListePersonneSelonProfilConnecte="
			SELECT
				Id_Personne  
			FROM
				new_competences_personne_prestation
			WHERE
				Date_Fin>='".date('Y-m-d')."'
				AND CONCAT(Id_Prestation,'_',Id_Pole) IN
				(
					SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation
					WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN (".implode(",",$TableauIdPostesRespPresta_CQ).")
				)
		";

	//Uniquement les spécials process
	$req="SELECT
			new_competences_relation.Id
		FROM
			new_competences_relation,
			new_competences_qualification
		WHERE
			new_competences_relation.Id_Qualification_Parrainage = new_competences_qualification.Id
			AND new_competences_relation.Suppr = 0
			AND (new_competences_relation.Date_Surveillance = 0
			OR
			(new_competences_relation.Date_Surveillance > 0 AND new_competences_relation.Statut_Surveillance = 'ECHEC')
			)
			AND new_competences_relation.Date_Debut > '0001-01-01'
			AND ((new_competences_relation.Date_Fin>= '".date('Y-m-d')."' AND new_competences_relation.Statut_Surveillance<>'REFUSE') OR 
				(ADDDATE(new_competences_relation.Date_Debut, INTERVAL new_competences_qualification.Duree_Validite MONTH) >= '".date('Y-m-d')."' 
				AND new_competences_relation.Statut_Surveillance='REFUSE'
				)
				)
			AND ADDDATE(new_competences_relation.Date_Debut, INTERVAL 2 YEAR)<='".date('Y-m-d',strtotime(date('Y-m-d')." +4 month"))."'
			AND new_competences_qualification.Periodicite_Surveillance > 0
			AND (SELECT Id_Categorie_Maitre FROM new_competences_categorie_qualification WHERE Id=new_competences_qualification.Id_Categorie_Qualification)=2
			AND new_competences_relation.Id_Qualification_Parrainage IN
			(
				SELECT
					DISTINCT Id_Qualification
				FROM
					form_formation_qualification
				LEFT JOIN form_formation
				  ON form_formation_qualification.Id_Formation=form_formation.Id
				WHERE
					form_formation_qualification.Suppr = 0
					AND form_formation.Suppr = 0
					AND form_formation.Id_TypeFormation IN (1,3)
			) 
			AND new_competences_relation.Id_Personne IN
			("
				.$ListePersonneSelonProfilConnecte."
			)
			AND new_competences_relation.Statut_Surveillance=''
			
	 ";
	$resultSurveillance=mysqli_query($bdd,$req);
	$nbSurveillance=mysqli_num_rows($resultSurveillance);
	return $nbSurveillance;
}


/**
 * Nombre de QCM de surveillance à diffuser
 *
 * Cette fonction permet de retourner le nombre de QCM de surveillance a diffuser
 *
 * @return 	int
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function NbQCMADiffuser()
{
	global $bdd;
	global $IdPersonneConnectee;
	global $TableauIdPostesAF_RF_RQ;
	global $TableauIdPostesRespPresta_CQ;
	global $TableauIdPostesAF_RF_RQ_RH_CQS;



	$ListePersonneSelonProfilConnecte="
			SELECT
				Id_Personne  
			FROM
				new_competences_personne_prestation
			WHERE
				Date_Fin>='".date('Y-m-d')."'
				AND CONCAT(Id_Prestation,'_',Id_Pole) IN
				(
					SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation
					WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN (".implode(",",$TableauIdPostesRespPresta_CQ).")
				)
		";

	//Uniquement les spécials process
	$req="SELECT
			new_competences_relation.Id
		FROM
			new_competences_relation,
			new_competences_qualification
		WHERE
			new_competences_relation.Id_Qualification_Parrainage = new_competences_qualification.Id
			AND new_competences_relation.Suppr = 0
			AND new_competences_relation.Date_Surveillance = 0
			AND new_competences_relation.Date_Debut > '0001-01-01'
			AND (new_competences_relation.Date_Fin>= '".date('Y-m-d')."' AND new_competences_relation.Statut_Surveillance<>'REFUSE')
			AND ADDDATE(new_competences_relation.Date_Debut, INTERVAL 2 YEAR)<='".date('Y-m-d',strtotime(date('Y-m-d')." +4 month"))."'
			AND new_competences_qualification.Periodicite_Surveillance > 0
			AND (SELECT Id_Categorie_Maitre FROM new_competences_categorie_qualification WHERE Id=new_competences_qualification.Id_Categorie_Qualification)=2
			AND new_competences_relation.Id_Qualification_Parrainage IN
			(
				SELECT
					DISTINCT Id_Qualification
				FROM
					form_formation_qualification
				LEFT JOIN form_formation
				  ON form_formation_qualification.Id_Formation=form_formation.Id
				WHERE
					form_formation_qualification.Suppr = 0
					AND form_formation.Suppr = 0
					AND form_formation.Id_TypeFormation IN (1,3)
			) 
			AND new_competences_relation.Id_Personne IN
			("
				.$ListePersonneSelonProfilConnecte."
			)
			AND new_competences_relation.Statut_Surveillance='VALIDE'
			
	 ";
	$resultSurveillance=mysqli_query($bdd,$req);
	$nbSurveillance=mysqli_num_rows($resultSurveillance);
	return $nbSurveillance;
}

/**
 * Supprimer les qualifs en attente de formation qui n'ont pas lieu d'être
 *
 * = Personnes qui ne sont plus sur la prestation en question
 *
 * @author Pauline FAUGE <pfauge@aaa-aero.com>
 */
function SupprimerQualifsEnAttenteMauvaisePrestation()
{
	global $bdd;


	$req="DELETE FROM 
			form_qualificationnecessaire_prestation 
		WHERE
			Necessaire=1
			AND (SELECT Suppr FROM new_competences_relation WHERE Id=Id_Relation)=0
			AND (SELECT COUNT(Id_Personne)
				FROM new_competences_personne_prestation
				WHERE new_competences_personne_prestation.Id_Personne=(SELECT Id_Personne FROM new_competences_relation WHERE Id=Id_Relation)
				AND new_competences_personne_prestation.Id_Prestation=form_qualificationnecessaire_prestation.Id_Prestation
				AND new_competences_personne_prestation.Id_Pole=form_qualificationnecessaire_prestation.Id_Pole
				AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
				AND (new_competences_personne_prestation.Date_Fin >='".date('Y-m-d')."' OR new_competences_personne_prestation.Date_Fin<='0001-01-01')
				)=0
	 ";
	$result=mysqli_query($bdd,$req);
}
?>
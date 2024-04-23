<?php
require("Connexioni.php");

require("Fonctions.php");
require("Formation/Globales_Fonctions.php");
require("PlanningV2/Fonctions_Planning.php");

if(isset($_POST["login"]))
{
	$tableau = array();
	// Exécuter des requêtes SQL
	$result=mysqli_query($bdd,"SELECT Nom,Prenom,Email, Id, Trigramme,LangueEN,EmailPro FROM new_rh_etatcivil WHERE Login='".$_POST["login"]."' AND Motdepasse='".$_POST["motdepasse"]."'");
	$nbenreg=mysqli_num_rows($result);
	
	if($_POST["login"] <> "" and $_POST["motdepasse"] <> "")
	{
		if($nbenreg==1)
		{
			//Creation des variables de session
			$row=mysqli_fetch_row($result);
			
			//Verification si la personne n'est pas en Z-SORTIE
			$resultPlat=mysqli_query($bdd,"SELECT Id_Personne FROM new_competences_personne_plateforme WHERE Id_Personne=".$row[3]." AND Id_Plateforme IN (11,14) ");
			$nbPlat=mysqli_num_rows($resultPlat);
			
			if($nbPlat==0){
				session_cache_limiter('private');

				// Configure le délai d'expiration à 30 minutes
				session_cache_expire(30);
				session_start();
				if($_SERVER['SERVER_NAME']=="127.0.0.1" || $_SERVER['SERVER_NAME']=="localhost" 
				|| $_SERVER['SERVER_NAME']=="192.168.20.3" || $_SERVER['SERVER_NAME']=="frcodc0001"
				|| $_SERVER['SERVER_NAME']=="172.30.48.42" || $_SERVER['SERVER_NAME']=="172.30.48.43"){
					$_SESSION['HTTP']="http";
				}
				else{
					$_SESSION['HTTP']="https";
				}
				
				$_SESSION['Log']=$_POST["login"];
				$_SESSION['Mdp']=$_POST["motdepasse"];
				$_SESSION['Nom']=$row[0];
				$_SESSION['Prenom']=$row[1];
				$_SESSION['Email']=$row[2];
				$_SESSION['Id_Personne']=$row[3];
				$_SESSION['trigramme']=$row[4];
				$_SESSION['Langue']="FR";
				if($row[5]==1){
					$_SESSION['Langue']="EN";
				}
				$_SESSION['EmailPro']=$row[6];
				
				$_SESSION['MdpDefaut']="aaa01";
				
				// Variables demande d'accès
				$_SESSION['videTableAcces']=0;
				$_SESSION['idPrestaGlobal']=0;
				$_SESSION['idPoleGlobal']=0;
				$_SESSION['tableauDemande']=0;
				$_SESSION['tableauAcces']=0;
				$_SESSION['tri']="defaut";
				
				$resultPlateforme=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne=".$row[3]."");
				$nbenregPlateforme=mysqli_num_rows($resultPlateforme);
				if($nbenregPlateforme>0)
				{
					while($donnees=mysqli_fetch_array($resultPlateforme))
					{
						array_push($tableau, $donnees[0]);
					}
				}
				$_SESSION['Id_Plateformes'] = $tableau;
				
				//Variables de sessions pour la recherche de comp?nces
				$_SESSION["Competences_Recherche_Plateforme"]="";
				$_SESSION["Competences_Recherche_Prestation"]="";
				$_SESSION["Competences_Recherche_Metier"]="";
				$_SESSION["Competences_Recherche_Formation"]="";
				$_SESSION["Competences_Recherche_Qualification"]="";
				$_SESSION["Competences_Recherche_EvaluationQualification"]="";
				$_SESSION["Competences_Recherche_Personne"]="";

				//Variable de sessions pour les surveillances
				$_SESSION['Page']="0";
				
				//Action
				$_SESSION['TriAction_Num']="";
				$_SESSION['TriAction_Priorite']="";
				$_SESSION['TriAction_Emetteur']="";
				$_SESSION['TriAction_Plateforme']="";
				$_SESSION['TriAction_DateCreation']="";
				$_SESSION['TriAction_Action']="";
				$_SESSION['TriAction_Acteur']="";
				$_SESSION['TriAction_Delai']="ASC";
				$_SESSION['TriAction_DateReport']="";
				$_SESSION['TriAction_Avancement']="DESC";
				$_SESSION['TriAction_DateSolde']="";
				$_SESSION['TriAction_Global']="Avancement DESC,Delai ASC,";
				$_SESSION['ActionPage']="0";
				
				//******************FORMATION************************//
				$_SESSION['FORM_FINVALIDITE_Page']="0";
				$_SESSION['FORM_FINVALIDITEFORMATION_Page']="0";
				$_SESSION['FORM_AUTORISATIONTRAVAIL_Page']="0";
				$_SESSION['FORM_BESOINFORMATION_Page']="0";
				$_SESSION['FORM_Surveillance_Page']="0";
				
				//----------------TRI ET FILTRE DES PAGES----------------//
				//~~~~~LANGUES~~~~~//
				$_SESSION['TriLangue_Libelle']="ASC";
				$_SESSION['TriLangue_General']="Libelle ASC,";
				
				//~~~~~LIEUX~~~~~//
				$_SESSION['TriLieu_Plateforme']="ASC";
				$_SESSION['TriLieu_Libelle']="ASC";
				$_SESSION['TriLieu_General']="Plateforme ASC,Libelle ASC,";
				
				//~~~~~ORGANISME~~~~~//
				$_SESSION['TriOrganisme_Plateforme']="ASC";
				$_SESSION['TriOrganisme_Libelle']="ASC";
				$_SESSION['TriOrganisme_General']="Plateforme ASC,Libelle ASC,";
				
				//~~~~~GROUPE DE FORMATION~~~~~//
				$_SESSION['TriGroupeForm_Plateforme']="ASC";
				$_SESSION['TriGroupeForm_Libelle']="ASC";
				$_SESSION['TriGroupeForm_General']="Plateforme ASC,Libelle ASC,";
				
				//~~~~~FORMATION EQUIVALENTE~~~~~//
				$_SESSION['TriFormEquivalente_Plateforme']="ASC";
				$_SESSION['TriFormEquivalente_Libelle']="ASC";
				$_SESSION['TriFormEquivalente_General']="Plateforme ASC,Libelle ASC,";
				
				//~~~~~AUTORISATION DE TRAVAIL~~~~~//
				$_SESSION['TriAT_Personne']="ASC";
				$_SESSION['TriAT_General']="Personne ASC,";
				
				$_SESSION['FiltreAT_Prestation']="";
				$_SESSION['FiltreAT_Moyen']="";
				$_SESSION['FiltreAT_Personne']="";
				$_SESSION['FiltreAT_Etat']="";
				$_SESSION['FiltreAT_RespProjet']="";
				
				//~~~~~WORKFLOW DES BESOINS~~~~~//
				$_SESSION['TriBesoins_LIBELLE_TYPEFORMATION']="";
				$_SESSION['TriBesoins_LIBELLE_FORMATION']="";
				$_SESSION['TriBesoins_LIBELLE_PRESTATION']="ASC";
				$_SESSION['TriBesoins_NOM_PRENOM']="ASC";
				$_SESSION['TriBesoins_Contrat']="";
				$_SESSION['TriBesoins_MOTIF_DEMANDE']="";
				$_SESSION['TriBesoins_DATE_DEMANDE']="";
				$_SESSION['TriBesoins_DateFinQualif']="";
				$_SESSION['TriBesoins_General']="LIBELLE_PRESTATION ASC,NOM_PRENOM ASC,";
				
				$_SESSION['FiltreBesoin_Type']="0";
				if(DroitsFormationPlateformeV2($_SESSION['Id_Personne'],array($IdPosteAssistantFormationInterne))){
					$_SESSION['FiltreBesoin_Type']=$IdTypeFormationInterne;
				}
				elseif(DroitsFormationPlateformeV2($_SESSION['Id_Personne'],array($IdPosteAssistantFormationExterne))){
					$_SESSION['FiltreBesoin_Type']=$IdTypeFormationExterne;
				}
				elseif(DroitsFormationPlateformeV2($_SESSION['Id_Personne'],array($IdPosteAssistantFormationTC))){
					$_SESSION['FiltreBesoin_Type']=$IdTypeFormationTC;
				}
				
				$_SESSION['FiltreBesoin_UER']="";
				$_SESSION['FiltreBesoin_Prestation']="";
				$_SESSION['FiltreBesoin_Motif']="";
				$_SESSION['FiltreBesoin_FinValiditeQualif']="";
				$_SESSION['FiltreBesoin_Personne']="0";
				$_SESSION['FiltreBesoin_Formation']="0";
				$_SESSION['FiltreBesoin_Recyclage']="0";
				$_SESSION['FiltreBesoin_PrisEnCompte']="";
				$_SESSION['FiltreBesoin_Etat']="";
				$_SESSION['FiltreBesoin_RespProjet']="";
				
				$_SESSION['Besoin_Suppr']="";
				
				//~~~~~FIN DE VALIDITE DES QUALIFICATIONS~~~~~//
				$_SESSION['TriFinQualif_Personne']="ASC";
				$_SESSION['TriFinQualif_Prestation']="";
				$_SESSION['TriFinQualif_Pole']="";
				$_SESSION['TriFinQualif_Qualification']="";
				$_SESSION['TriFinQualif_Categorie']="";
				$_SESSION['TriFinQualif_DateFin']="ASC";
				$_SESSION['TriFinQualif_General']="Date_Fin ASC,Personne ASC,";
				
				$_SESSION['FiltreFinQualif_Prestation']="0";
				$_SESSION['FiltreFinQualif_Personne']="";
				$_SESSION['FiltreFinQualif_Caduque']="";
				$_SESSION['FiltreFinQualif_Qualification']="0";
				$_SESSION['FiltreFinQualif_Etat']="0";
				$_SESSION['FiltreFinQualif_RespProjet']="";
				
				//~~~~~QUALIFICATIONS EN L OU T~~~~~//
				$_SESSION['TriQualifLT_Personne']="ASC";
				$_SESSION['TriQualifLT_Prestation']="";
				$_SESSION['TriQualifLT_Pole']="";
				$_SESSION['TriQualifLT_Qualification']="";
				$_SESSION['TriQualifLT_Metier']="";
				$_SESSION['TriQualifLT_Categorie']="";
				$_SESSION['TriQualifLT_DateDebut']="";
				$_SESSION['TriQualifLT_DateQCM']="ASC";
				$_SESSION['TriQualifLT_ResultatQCM']="";
				$_SESSION['TriQualifLT_Evaluation']="";
				$_SESSION['TriQualifLT_General']="Date_QCM ASC,Personne ASC,";
				
				$_SESSION['FiltreQualifLT_Prestation']="0";
				$_SESSION['FiltreQualifLT_Personne']="";
				$_SESSION['FiltreQualifLT_DateQCM']="";
				$_SESSION['FiltreQualifLT_AnneeQCM']="";
				$_SESSION['FiltreQualifLT_Qualification']="0";
				$_SESSION['FiltreQualifLT_Evaluation']="";
				
				//~~~~~QUALIFICATIONS EN ATTENTE DE FORMATION~~~~~//
				$_SESSION['TriQualifEnAttente_Personne']="ASC";
				$_SESSION['TriQualifEnAttente_Metier']="";
				$_SESSION['TriQualifEnAttente_FuturMetier']="";
				$_SESSION['TriQualifEnAttente_Prestation']="";
				$_SESSION['TriQualifEnAttente_Qualification']="";
				$_SESSION['TriQualifEnAttente_Categorie']="";
				$_SESSION['TriQualifEnAttente_DateFin']="ASC";
				$_SESSION['TriQualifEnAttente_General']="Date_Fin ASC,Personne ASC,";
				
				$_SESSION['FiltreQualifEnAttente_Prestation']="";
				$_SESSION['FiltreQualifEnAttente_Pole']="";
				$_SESSION['FiltreQualifEnAttente_Personne']="";
				$_SESSION['FiltreQualifEnAttente_Qualification']="";
				$_SESSION['FiltreQualifEnAttente_RespProjet']="";
				
				//~~~~~PERSONNEL EN FORMATION~~~~~//
				$_SESSION['TriPersFormation_Personne']="ASC";
				$_SESSION['TriPersFormation_Prestation']="ASC";
				$_SESSION['TriPersFormation_Pole']="";
				$_SESSION['TriPersFormation_GroupeFormation']="";
				$_SESSION['TriPersFormation_Lieu']="";
				$_SESSION['TriPersFormation_DateDebut']="";
				$_SESSION['TriPersFormation_DateFin']="";
				$_SESSION['TriPersFormation_HeureDebut']="";
				$_SESSION['TriPersFormation_HeureFin']="";
				$_SESSION['TriPersFormation_Duree']="";
				$_SESSION['TriPersFormation_General']="Prestation ASC,Personne ASC,";
				
				$_SESSION['FiltrePersFormation_Prestation']="";
				$_SESSION['FiltrePersFormation_Personne']="";
				$_SESSION['FiltrePersFormation_Etat']="-2";
				$_SESSION['FiltrePersFormation_DateDebut']=AfficheDateFR(date('Y-m-d'));
				$_SESSION['FiltrePersFormation_DateFin']="";
				$_SESSION['FiltrePersFormation_Formation']="";
				$_SESSION['FiltrePersFormation_GroupeFormation']="";
				$_SESSION['FiltrePersFormation_TypeFormation']="";
				$_SESSION['FiltrePersFormation_RespProjet']="";
				
				//~~~~~WORKFLOW DES SURVEILLANCES~~~~~//
				$_SESSION['TriFormSurveillance_Personne']="ASC";
				$_SESSION['TriFormSurveillance_Qualification']="";
				$_SESSION['TriFormSurveillance_DateDebut']="ASC";
				$_SESSION['TriFormSurveillance_DateFin']="";
				$_SESSION['TriFormSurveillance_Statut']="";
				$_SESSION['TriFormSurveillance_Prestation']="";
				$_SESSION['TriFormSurveillance_General']="Date_Debut ASC,Personne ASC,";
				
				$_SESSION['FiltreFormSurveillance_Plateforme']="0";
				$_SESSION['FiltreFormSurveillance_Prestation']="";
				$_SESSION['FiltreFormSurveillance_Personne']="";
				$_SESSION['FiltreFormSurveillance_Qualification']="";
				$_SESSION['FiltreFormSurveillance_Statut']="";
				$_SESSION['FiltreFormSurveillance_CQP']="";
				
				//~~~~~WORKFLOW DES SURVEILLANCES QBP~~~~~//
				$_SESSION['TriFormSurveillanceQBP_Personne']="ASC";
				$_SESSION['TriFormSurveillanceQBP_Qualification']="";
				$_SESSION['TriFormSurveillanceQBP_DateSurveillance']="ASC";
				$_SESSION['TriFormSurveillanceQBP_DateDebut']="";
				$_SESSION['TriFormSurveillanceQBP_Statut']="";
				$_SESSION['TriFormSurveillanceQBP_Prestation']="";
				$_SESSION['TriFormSurveillanceQBP_General']="DateSurveillance ASC,Personne ASC,";
				
				$_SESSION['FiltreFormSurveillanceQBP_Plateforme']="0";
				$_SESSION['FiltreFormSurveillanceQBP_Prestation']="";
				$_SESSION['FiltreFormSurveillanceQBP_Personne']="";
				$_SESSION['FiltreFormSurveillanceQBP_Qualification']="";
				$_SESSION['FiltreFormSurveillanceQBP_Statut']="";
				$_SESSION['FiltreFormSurveillanceQBP_CQP']="";
				
				//~~~~~CATALOGUE DE FORMATION~~~~~//
				$_SESSION['TriCatalogueForm_Reference']="";
				$_SESSION['TriCatalogueForm_Type']="";
				$_SESSION['TriCatalogueForm_Recyclage']="";
				$_SESSION['TriCatalogueForm_Intitule']="ASC";
				$_SESSION['TriCatalogueForm_General']="form_formation_langue_infos.Libelle ASC,";
				
				$_SESSION['FiltreCatalogueForm_Plateforme']="0";
				$_SESSION['FiltreCatalogueForm_Type']="0";
				$_SESSION['FiltreCatalogueForm_Recyclage']="";
				$_SESSION['FiltreCatalogueForm_MotCle']="";
				$_SESSION['FiltreCatalogueForm_Organisme']="";
				
				//~~~~~FORMATIONS SMQ~~~~~//
				$_SESSION['TriFormSMQ_Reference']="ASC";
				$_SESSION['TriFormSMQ_Type']="";
				$_SESSION['TriFormSMQ_Recyclage']="";
				$_SESSION['TriFormSMQ_DateMAJ']="";
				$_SESSION['TriFormSMQ_General']="Reference ASC,";
				
				$_SESSION['FiltreFormSMQ_MotCle']="";
				
				//~~~~~FORMATIONS / PLATEFORME~~~~~//
				$_SESSION['TriFormPlateforme_Reference']="ASC";
				$_SESSION['TriFormPlateforme_Type']="";
				$_SESSION['TriFormPlateforme_Recyclage']="";
				$_SESSION['TriFormPlateforme_General']="Reference ASC,";
				
				$_SESSION['FiltreFormPlateforme_Plateforme']="0";
				$_SESSION['FiltreFormPlateforme_Type']="";
				$_SESSION['FiltreFormPlateforme_MotCle']="";
				$_SESSION['FiltreFormPlateforme_Organisme']="0";
				
				//~~~~~QCM~~~~~//
				$_SESSION['TriQCM_Code']="ASC";
				$_SESSION['TriQCM_Client']="";
				$_SESSION['TriQCM_NbQuestion']="";
				$_SESSION['TriQCM_General']="Code ASC,";
				
				//~~~~~CLIENTS~~~~~//
				$_SESSION['TriClient_Plateforme']="ASC";
				$_SESSION['TriClient_Libelle']="ASC";
				$_SESSION['TriClient_General']="Libelle ASC,";
				
				//~~~~~DOCUMENT~~~~~//
				$_SESSION['TriDocument_Reference']="ASC";
				$_SESSION['TriDocument_General']="Reference ASC,";
				
				//~~~~~~PLANNING DES FORMATIONS~~~~~~//
				$_SESSION['FiltreFormPlanning_DateDebut']=date('Y-m-d');
				$_SESSION['FiltreFormPlanning_DateFin']=date("Y-m-d",strtotime(date('Y-m-d')." + 1 month"));
				
				$_SESSION['FiltreFormPlanning_TypeFormation']="0";
				if(DroitsFormationPlateformeV2($_SESSION['Id_Personne'],array($IdPosteAssistantFormationInterne))){
					$_SESSION['FiltreFormPlanning_TypeFormation']=$IdTypeFormationInterne;
				}
				elseif(DroitsFormationPlateformeV2($_SESSION['Id_Personne'],array($IdPosteAssistantFormationExterne))){
					$_SESSION['FiltreFormPlanning_TypeFormation']=$IdTypeFormationExterne;
				}
				elseif(DroitsFormationPlateformeV2($_SESSION['Id_Personne'],array($IdPosteAssistantFormationTC))){
					$_SESSION['FiltreFormPlanning_TypeFormation']=$IdTypeFormationTC;
				}
				
				$_SESSION['FiltreFormPlanning_TypeFormation_3']=0;
				$_SESSION['FiltreFormPlanning_TypeFormation_1']=0;
				if(DroitsFormationPlateformeV2($_SESSION['Id_Personne'],array($IdPosteAssistantFormationInterne))){
					$_SESSION['FiltreFormPlanning_TypeFormation_3']=1;
					$_SESSION['FiltreFormPlanning_TypeFormation_1']=1;
				}
				$_SESSION['FiltreFormPlanning_TypeFormation_4']=0;
				if(DroitsFormationPlateformeV2($_SESSION['Id_Personne'],array($IdPosteAssistantFormationExterne))){
					$_SESSION['FiltreFormPlanning_TypeFormation_4']=1;
				}
				$_SESSION['FiltreFormPlanning_TypeFormation_2']=0;
				if(DroitsFormationPlateformeV2($_SESSION['Id_Personne'],array($IdPosteAssistantFormationTC))){
					$_SESSION['FiltreFormPlanning_TypeFormation_2']=1;
				}
				
				$_SESSION['FiltreFormPlanning_TypeAffichage']="formateur";
				if(DroitsFormationPlateformeV2($_SESSION['Id_Personne'],array($IdPosteAssistantFormationExterne))){
					$_SESSION['FiltreFormPlanning_TypeAffichage']="session";
				}
				
				$_SESSION['FiltreFormPlanning_Organisme']=0;
				$_SESSION['FiltreFormPlanning_Formateur']=0;
				$_SESSION['FiltreFormPlanning_Lieu']=0;
				$_SESSION['FiltreFormPlanning_Horaire']=-1;
				$_SESSION['FiltreFormPlanning_Formation']="";
				$_SESSION['FiltreFormPlanning_Etat']="";
				
				
				$_SESSION['FiltreSuiviFormation_Plateforme']=0;
				$_SESSION['FiltreSuiviFormation_Personne']="";
				$_SESSION['FiltreSuiviFormation_DateDebut']=AfficheDateFR(date('Y-01-01'));
				$_SESSION['FiltreSuiviFormation_DateFin']="";
				$_SESSION['FiltreSuiviFormation_DateFinContrat']="";
				$_SESSION['FiltreSuiviFormation_Formation']="";
				$_SESSION['FiltreSuiviFormation_Organisme']=0;
				$_SESSION['FiltreSuiviFormation_TypeFormation']=0;
				if(DroitsFormationPlateformeV2($_SESSION['Id_Personne'],array($IdPosteAssistantFormationExterne))){
					$_SESSION['FiltreSuiviFormation_TypeFormation']=4;
				}
				elseif(DroitsFormationPlateformeV2($_SESSION['Id_Personne'],array($IdPosteAssistantFormationInterne))){
					$_SESSION['FiltreSuiviFormation_TypeFormation']=3;
				}
				$_SESSION['FiltreSuiviFormation_DdePriseEnvoyee']="0";
				$_SESSION['FiltreSuiviFormation_AccordPriseEnCharge']="0";
				$_SESSION['FiltreSuiviFormation_TraitementConvention']="0";
				$_SESSION['FiltreSuiviFormation_Motif']="";
				$_SESSION['FiltreSuiviFormation_FeuillePresence']="0";
				$_SESSION['FiltreSuiviFormation_AttestationFormation']="0";
				$_SESSION['FiltreSuiviFormation_EvaluationAChaud']="0";
				$_SESSION['FiltreSuiviFormation_RemplissageExtranet']="0";
				$_SESSION['FiltreSuiviFormation_HabilitationConduite']="0";
				
				$tab = array("Id","Reference","Hrbp","Responsable","CodeAnalytique","Matricule","Personne","Contrat","ETT","DateFinContrat","CSP","Sexe","Age","SalaireHoraireCharge","Formation","Type","Organisme","TypeCours","Categorie","InterIntra","DateDebut","DateFin","NbHeures","NbJours","Cout","CoutSalarial","DdePriseEnChargeEnvoyee","AccordPriseEnCharge","TraitementConvention","PresentAbsent","MotifAbs","FeuillePresence","AttestationFormation","EvaluationAChaud","RemplissageExtranet","HabilitationExtranet");
				foreach($tab as $tri){
					if($tri=="Reference"){$_SESSION['TriSuiviFormation_'.$tri]="DESC";}
					else{$_SESSION['TriSuiviFormation_'.$tri]="";}
				}
				$_SESSION['TriSuiviFormation_General']="Reference DESC,";
				
				//~~~~~INDICATEURS EVALUATIONS DES FORMATIONS~~~~~//
				$_SESSION['TriFormEvalForm_Personne']="ASC";
				$_SESSION['TriFormEvalForm_Prestation']="ASC";
				$_SESSION['TriFormEvalForm_Pole']="";
				$_SESSION['TriFormEvalForm_Formation']="";
				$_SESSION['TriFormEvalForm_DateDebut']="DESC";
				$_SESSION['TriFormEvalForm_HeureDebut']="";
				$_SESSION['TriFormEvalForm_HeureFin']="";
				$_SESSION['TriFormEvalForm_Duree']="";
				$_SESSION['TriFormEvalForm_Formateur']="";
				$_SESSION['TriFormEvalForm_Note']="";
				$_SESSION['TriFormEvalForm_General']="DateDebut DESC,Prestation ASC,Personne ASC,";
				
				$_SESSION['FiltreFormEvalForm_Prestation']="";
				$_SESSION['FiltreFormEvalForm_Personne']="";
				$_SESSION['FiltreFormEvalForm_DateDebut']=AfficheDateFR(date('Y-m-1',strtotime(date('Y-m-d')." -1 month")));
				$_SESSION['FiltreFormEvalForm_DateFin']="";
				$_SESSION['FiltreFormEvalForm_Formation']="";
				
				//-------------GRAPHIQUES----------------//
				$_SESSION['FiltreFormGraphique_Prestation']="";
				$_SESSION['FiltreFormGraphique_Formation']="";
				$_SESSION['FiltreFormGraphique_Mois']=date("m");
				$_SESSION['FiltreFormGraphique_Annee']=date("Y");
				
				//-------------EXTRACTS PARAMETRAGE----------------//
				$_SESSION['FiltreExtractParametrage_Prestations']="";
				$_SESSION['FiltreExtractParametrage_Formation']="";
				$_SESSION['FiltreExtractParametrage_Plateforme']=0;
				$_SESSION['FiltreExtractParametrage_Type']="";
				
				//-------------TABLEAU NB BESOINS / PRESTATIONS----------------//
				$_SESSION['FiltreTabNbBesoins_Prestations']="";
				$_SESSION['FiltreTabNbBesoins_Formation']=0;
				$_SESSION['FiltreTabNbBesoins_Plateforme']=0;
				$_SESSION['FiltreTabNbBesoins_Type']="";
				$_SESSION['FiltreTabNbBesoins_MotifNouveau']="";
				$_SESSION['FiltreTabNbBesoins_MotifRenouvellement']="";
				$_SESSION['FiltreTabNbBesoins_RespProjet']="";
				
				//-------------PERSONNES SANS FORMATION ----------------//
				$_SESSION['FiltrePersSansForm_Prestations']="";
				$_SESSION['FiltrePersSansForm_Plateforme']=0;
				$_SESSION['FiltrePersSansForm_Formation']="";
				$_SESSION['FiltrePersSansForm_RespProjet']="";
				
				//-------------PERSONNES SANS FORMATION OBLIGATOIRE----------------//
				$_SESSION['FiltrePersSansFormNonO_Prestations']="";
				$_SESSION['FiltrePersSansFormNonO_Plateforme']=0;
				$_SESSION['FiltrePersSansFormNonO_RespProjet']="";
				$_SESSION['FiltrePersSansFormNonO_DateDebut']=date("Y-m-d",strtotime(date('Y-m-d')." - 6 year"));
				$_SESSION['FiltrePersSansFormNonO_DateFin']=date('Y-m-d');
								
				//-------------PERSONNES AVEC FORMATION EN COURS DE VALIDITE ----------------//
				$_SESSION['FiltrePersFormEC_Prestations']="";
				$_SESSION['FiltrePersFormEC_Plateforme']=0;
				$_SESSION['FiltrePersFormEC_Formation']="";
				$_SESSION['FiltrePersFormEC_RespProjet']="";
				
				//-------------DUREE PAR FORMATION PAR METIER ----------------//
				$_SESSION['FiltreDureeFormMetier_Prestations']="";
				$_SESSION['FiltreDureeFormMetier_Plateforme']=0;
				$_SESSION['FiltreDureeFormMetier_Formation']="";
				$_SESSION['FiltreDureeFormMetier_RespProjet']="";
				$_SESSION['FiltreDureeFormMetier_Type']="";
				
				
				//-------------AVANCEMENT BESOINS----------------//
				$_SESSION['FiltreAvancementBesoin_Date']=date("Y-01-01",strtotime(date('Y-m-d')));
				$_SESSION['FiltreAvancementBesoin_Type']="";
				$_SESSION['FiltreAvancementBesoin_Plateforme']=0;
				$_SESSION['FiltreAvancementBesoin_Categorie']="";
				$_SESSION['FiltreAvancementBesoin_Formation']="";
				$_SESSION['FiltreAvancementBesoin_TypeContrat']="";
				
				//-------------NOMBRE DE SESSION DE FORMATION----------------//
				$_SESSION['FiltreNbSessionFormation_Prestations']="";
				$_SESSION['FiltreNbSessionFormation_Plateforme']=0;
				$_SESSION['FiltreNbSessionFormation_RespProjet']="";
				$_SESSION['FiltreNbSessionFormation_DateDebut']=date("Y-m-d",strtotime(date('Y-m-d')." - 6 month"));
				$_SESSION['FiltreNbSessionFormation_DateFin']=date('Y-m-d');
				$_SESSION['FiltreNbSessionFormation_Type']="";
				$_SESSION['FiltreNbSessionFormation_ModeAffichage']="Mois";
				$_SESSION['FiltreNbSessionFormation_Formation']="";
				$_SESSION['FiltreNbSessionFormation_Sessions']="";
				$_SESSION['FiltreNbSessionFormation_Categorie']="";
				$_SESSION['FiltreNbSessionFormation_Formateur']="";
				
				
				//---------------PERSONNES EN FORMATION NON EN COURS---------------//
				$_SESSION['FiltrePersonnesFormationNonEnCours_Plateforme']=0;
				$_SESSION['FiltrePersonnesFormationNonEnCours_Formation']=0;
				
				//-------------TAUX REMPLISSAGE SESSIONS----------------//
				$_SESSION['FiltreTauxRemplissageSessions_Prestations']="";
				$_SESSION['FiltreTauxRemplissageSessions_Plateforme']=0;
				$_SESSION['FiltreTauxRemplissageSessions_RespProjet']="";
				$_SESSION['FiltreTauxRemplissageSessions_DateDebut']=date("Y-m-d",strtotime(date('Y-m-d')." - 6 month"));
				$_SESSION['FiltreTauxRemplissageSessions_DateFin']=date('Y-m-d');
				$_SESSION['FiltreTauxRemplissageSessions_Type']="";
				$_SESSION['FiltreTauxRemplissageSessions_ModeAffichage']="Mois";
				$_SESSION['FiltreTauxRemplissageSessions_Formation']="";
				$_SESSION['FiltreTauxRemplissageSessions_Sessions']="";
				$_SESSION['FiltreTauxRemplissageSessions_Categorie']="";
				$_SESSION['FiltreTauxRemplissageSessions_Formateur']="";
				
				//-------------OQD : EVALUATION A CHAUD----------------//
				$_SESSION['FiltreEvaluationAChaud_Prestations']="";
				$_SESSION['FiltreEvaluationAChaud_Plateforme']=0;
				$_SESSION['FiltreEvaluationAChaud_DateDebut']=date("Y-m-d",strtotime(date('Y-m-d')." - 6 month"));
				$_SESSION['FiltreEvaluationAChaud_DateFin']=date('Y-m-d');
				$_SESSION['FiltreEvaluationAChaud_Type']="";
				$_SESSION['FiltreEvaluationAChaud_ModeAffichage']="Mois";
				$_SESSION['FiltreEvaluationAChaud_Formation']="";
				$_SESSION['FiltreEvaluationAChaud_Categorie']="";
				$_SESSION['FiltreEvaluationAChaud_Formateur']="";
				
				//-------------BESOINS SANS SESSION----------------//
				$_SESSION['FiltreBesoinsSansSession_Plateforme']=0;
				$_SESSION['FiltreBesoinsSansSession_Periode']="1 an";
				$_SESSION['FiltreBesoinsSansSession_Type']="";
				
				//-------------NOMBRE DE SESSION DE FORMATION----------------//
				$_SESSION['FiltreTauxReussiteFormation_Prestations']="";
				$_SESSION['FiltreTauxReussiteFormation_Plateforme']=0;
				$_SESSION['FiltreTauxReussiteFormation_RespProjet']="";
				$_SESSION['FiltreTauxReussiteFormation_DateDebut']=date("Y-m-d",strtotime(date('Y-m-d')." - 6 month"));
				$_SESSION['FiltreTauxReussiteFormation_DateFin']=date('Y-m-d');
				$_SESSION['FiltreTauxReussiteFormation_Type']="";
				$_SESSION['FiltreTauxReussiteFormation_ModeAffichage']="Mois";
				$_SESSION['FiltreTauxReussiteFormation_Formation']="";
				$_SESSION['FiltreTauxReussiteFormation_Sessions']="";
				$_SESSION['FiltreTauxReussiteFormation_Categorie']="";
				$_SESSION['FiltreTauxReussiteFormation_Formateur']="";
				
				//-------------NOMBRE DE PERSONNES INSCRITES----------------//
				$_SESSION['FiltreNbPersonneInscrite_ModeAffichage']="Mois";
				$_SESSION['FiltreNbPersonneInscrite_DateDebut']=date("Y-m-d",strtotime(date('Y-m-d')." - 6 month"));
				$_SESSION['FiltreNbPersonneInscrite_DateFin']=date('Y-m-d');
				$_SESSION['FiltreNbPersonneInscrite_Type']="";
				$_SESSION['FiltreNbPersonneInscrite_Plateforme']=0;
				$_SESSION['FiltreNbPersonneInscrite_Categorie']="";
				$_SESSION['FiltreNbPersonneInscrite_Formation']="";
				$_SESSION['FiltreNbPersonneInscrite_TypeContrat']="";
				
				//------------- % INSCRITS ET ABSENTS / PRESTATION ----------------//
				$_SESSION['FiltreInscritsAbsPresta_DateDebut']=date("Y-m-d",strtotime(date('Y-m-d')." - 1 month"));
				$_SESSION['FiltreInscritsAbsPresta_DateFin']=date('Y-m-d');
				$_SESSION['FiltreInscritsAbsPresta_Type']="";
				$_SESSION['FiltreInscritsAbsPresta_Plateforme']=0;
				$_SESSION['FiltreInscritsAbsPresta_Categorie']="";
				$_SESSION['FiltreInscritsAbsPresta_Formation']="";
				
				//-------------NOMBRE DE D'HEURES DE FORMATION DES PERSONNES INSCRITES----------------//
				$_SESSION['FiltreNbHeuresFormation_ModeAffichage']="Mois";
				$_SESSION['FiltreNbHeuresFormation_DateDebut']=date("Y-m-d",strtotime(date('Y-m-d')." - 6 month"));
				$_SESSION['FiltreNbHeuresFormation_DateFin']=date('Y-m-d');
				$_SESSION['FiltreNbHeuresFormation_Type']="";
				$_SESSION['FiltreNbHeuresFormation_Plateforme']=0;
				$_SESSION['FiltreNbHeuresFormation_Categorie']="";
				$_SESSION['FiltreNbHeuresFormation_Formateur']="";
				$_SESSION['FiltreNbHeuresFormation_Formation']="";
				$_SESSION['FiltreNbHeuresFormation_TypeContrat']="";
				
				//-------------NOMBRE DE PERSONNES PRESENTES / ABSENTES----------------//
				$_SESSION['FiltreNbPersonnePresenteAbs_ModeAffichage']="Mois";
				$_SESSION['FiltreNbPersonnePresenteAbs_DateDebut']=date("Y-m-d",strtotime(date('Y-m-d')." - 6 month"));
				$_SESSION['FiltreNbPersonnePresenteAbs_DateFin']=date('Y-m-d');
				$_SESSION['FiltreNbPersonnePresenteAbs_Type']="";
				$_SESSION['FiltreNbPersonnePresenteAbs_Plateforme']=0;
				$_SESSION['FiltreNbPersonnePresenteAbs_Categorie']="";
				$_SESSION['FiltreNbPersonnePresenteAbs_Formation']="";
				$_SESSION['FiltreNbPersonnePresenteAbs_TypeContrat']="";
				
				//-------------SESSIONS DE FORMATION ANNULEES----------------//
				$_SESSION['FiltreFormAnnulees_DateDebut']=date("Y-m-d",strtotime(date('Y-m-d')." - 6 month"));
				$_SESSION['FiltreFormAnnulees_DateFin']=date('Y-m-d');
				$_SESSION['FiltreFormAnnulees_Type']="";
				$_SESSION['FiltreFormAnnulees_Plateforme']=0;
				
				//-------------LISTE DES DESINSCRIPTIONS----------------//
				$_SESSION['FiltreFormDesinscription_DateDebut']=date("Y-m-d",strtotime(date('Y-m-d')." - 6 month"));
				$_SESSION['FiltreFormDesinscription_DateFin']=date('Y-m-d');
				$_SESSION['FiltreFormDesinscription_Type']="";
				$_SESSION['FiltreFormDesinscription_Plateforme']=0;
				
				
				//-------------NOMBRE DE SESSION PAR FORMATION----------------//
				$_SESSION['FiltreNbSessionParFormation_Prestations']="";
				$_SESSION['FiltreNbSessionParFormation_Plateforme']=0;
				$_SESSION['FiltreNbSessionParFormation_RespProjet']="";
				$_SESSION['FiltreNbSessionParFormation_DateDebut']=date("Y-m-d",strtotime(date('Y-m-d')." - 6 month"));
				$_SESSION['FiltreNbSessionParFormation_DateFin']=date('Y-m-d');
				$_SESSION['FiltreNbSessionParFormation_Type']="";
				$_SESSION['FiltreNbSessionParFormation_Formation']="";
				$_SESSION['FiltreNbSessionParFormation_Formateur']="";
				
				//-------------NOMBRES ET COUS PAR FORMATION----------------//
				$_SESSION['FiltreNbEtCoutParFormation_Plateforme']=0;
				$_SESSION['FiltreNbEtCoutParFormation_DateDebut']=date("Y-01-01");
				$_SESSION['FiltreNbEtCoutParFormation_DateFin']=date('Y-m-d');
				$_SESSION['FiltreNbEtCoutParFormation_Type']="";
				
				//~~~~~~PLANNING DES FORMATIONS~~~~~~//
				$_SESSION['FiltreFormPlanningResp_DateDebut']=date('Y-m-d');
				$_SESSION['FiltreFormPlanningResp_DateFin']=date("Y-m-d",strtotime(date('Y-m-d')." + 1 month"));
				
				if($_SERVER['SERVER_NAME']=="127.0.0.1" || $_SERVER['SERVER_NAME']=="192.168.20.3"){
					$_SESSION['PartieFormation']=2;
				}
				else{
					$_SESSION['PartieFormation']=2;
				}
				
				//~~~~~~GESTION DES FORMATIONS~~~~~~//
				$_SESSION['FiltreFormSessionForm_Formateur']=$_SESSION['Id_Personne'];
				$_SESSION['FiltreFormSessionForm_Date']=date('Y-m-d');
				
				
				
				
				
				//**************************************************//
				
				//****************     SURVEILLANCE      ********************//
				
				//**************************************************//
				$_SESSION['FiltreSurveillance_Plateforme']="0";
				$_SESSION['FiltreSurveillance_Prestation']="0";
				$_SESSION['FiltreSurveillance_DateSurveillance']="";
				$_SESSION['FiltreSurveillance_Theme']="0";
				$_SESSION['FiltreSurveillance_Surveille']="0";
				$_SESSION['FiltreSurveillance_Surveillant']="0";
				$_SESSION['FiltreSurveillance_Annee']="";
				$_SESSION['FiltreSurveillance_TypeTheme']="";
				$_SESSION['FiltreSurveillance_PlateformeTheme']="0";
				$_SESSION['FiltreSurveillance_Questionnaire']="0";
				$_SESSION['FiltreSurveillance_Etat']="tous";
				$_SESSION['FiltreSurveillance_NumSurveillance']="";
				
				//****************     RH      ********************//

				$_SESSION['FiltreRHHS_Prestation']="0";
				$_SESSION['FiltreRHHS_Pole']="0";
				$_SESSION['FiltreRHHS_Mois']=date("m");
				$_SESSION['FiltreRHHS_MoisCumules']="checked";
				$_SESSION['FiltreRHHS_Annee']=date("Y");
				$_SESSION['FiltreRHHS_Personne']="";
				$_SESSION['FiltreRHHS_EtatEnCours']="checked";
				$_SESSION['FiltreRHHS_EtatTransmiRH']="checked";
				$_SESSION['FiltreRHHS_EtatValide']="";
				$_SESSION['FiltreRHHS_EtatRefuse']="";
				$_SESSION['FiltreRHHS_EtatSupprime']="";
				$_SESSION['FiltreRHHS_RespProjet']="";
				
				$_SESSION['FiltreRHHSRH_EtatEnCours']="";
				$_SESSION['FiltreRHHSRH_EtatTransmiRH']="checked";
				$_SESSION['FiltreRHHSRH_EtatValide']="";
				$_SESSION['FiltreRHHSRH_EtatRefuse']="";
				$_SESSION['FiltreRHHSRH_EtatSupprime']="";
				
				$tab = array("Id","Personne","Prestation","Pole","Date1","Demandeur","DateHS","Nb_Heures_Jour","Nb_Heures_Nuit","Etat","DatePriseEnCompteRH","Contrat","TempsTravail");
				foreach($tab as $tri){
					if($tri=="Id"){$_SESSION['TriRHHS_'.$tri]="DESC";}
					else{$_SESSION['TriRHHS_'.$tri]="";}
				}
				$_SESSION['TriRHHS_General']="Id DESC,";

				
				$_SESSION['FiltreRHHSHistorique_Prestation']="0";
				$_SESSION['FiltreRHHSHistorique_Pole']="0";
				$_SESSION['FiltreRHHSHistorique_Mois']=date("m");
				$_SESSION['FiltreRHHSHistorique_MoisCumules']="";
				$_SESSION['FiltreRHHSHistorique_Annee']=date("Y");
				$_SESSION['FiltreRHHSHistorique_Personne']="";
				$_SESSION['FiltreRHHSHistorique_EtatEnCours']="";
				$_SESSION['FiltreRHHSHistorique_EtatTransmiRH']="";
				$_SESSION['FiltreRHHSHistorique_EtatValide']="checked";
				$_SESSION['FiltreRHHSHistorique_EtatRefuse']="";
				$_SESSION['FiltreRHHSHistorique_EtatSupprime']="";
				
				$tab = array("Personne","Prestation","Pole","Semaine","NbHeures");
				foreach($tab as $tri){
					if($tri=="Semaine"){$_SESSION['TriRHHSHistorique_'.$tri]="DESC";}
					else{$_SESSION['TriRHHSHistorique_'.$tri]="";}
				}
				$_SESSION['TriRHHSHistorique_General']="Semaine DESC,";
				
				$_SESSION['FiltreRHHSAlerte_Mois']=date("m");
				$_SESSION['FiltreRHHSAlerte_Annee']=date("Y");
				$_SESSION['FiltreRHHSAlerte_Personne']="";
				$_SESSION['FiltreRHHSAlerte_TypeAlerte']="10h";
				$_SESSION['FiltreRHHSAlerte_RespProjet']="";
				
				$_SESSION['FiltreRHJourAlerte_Mois']=date("m");
				$_SESSION['FiltreRHJourAlerte_Annee']=date("Y");
				$_SESSION['FiltreRHJourAlerte_Personne']="";
				$_SESSION['FiltreRHJourAlerte_RespProjet']="";
				
				$_SESSION['FiltreRHMouvement_PrestationDep']="0_0";
				$_SESSION['FiltreRHMouvement_PrestationDes']="0_0";
				$_SESSION['FiltreRHMouvement_Personne']="";
				$_SESSION['FiltreRHMouvement_Du']="";
				$_SESSION['FiltreRHMouvement_Au']="";
				$_SESSION['FiltreRHMouvement_EtatPrisEnCompte']="";
				$_SESSION['FiltreRHMouvement_EtatNonPrisEnCompte']="checked";
				$_SESSION['FiltreRHMouvement_RespProjet']="";
				
				$tab = array("Id","Personne","Contrat","PrestationDepart","PrestationDestination","DateDebut","DateFin",'DatePriseEnCompteRH');
				foreach($tab as $tri){
					if($tri=="Id"){$_SESSION['TriRHMouvement_'.$tri]="ASC";}
					else{$_SESSION['TriRHMouvement_'.$tri]="";}
				}
				$_SESSION['TriRHMouvement_General']="Id ASC,";
				
				$_SESSION['FiltreRHConges_Prestation']="0";
				$_SESSION['FiltreRHConges_Pole']="0";
				$_SESSION['FiltreRHConges_Mois']=date("m");
				$_SESSION['FiltreRHConges_MoisCumules']="checked";
				$_SESSION['FiltreRHConges_Annee']=date("Y");
				$_SESSION['FiltreRHConges_Personne']="";
				$_SESSION['FiltreRHConges_EtatEnCours']="checked";
				$_SESSION['FiltreRHConges_EtatTransmiRH']="checked";
				$_SESSION['FiltreRHConges_EtatValide']="";
				$_SESSION['FiltreRHConges_EtatRefuse']="";
				$_SESSION['FiltreRHConges_EtatSupprime']="";
				$_SESSION['FiltreRHConges_AffichageResponsable']="checked";
				$_SESSION['FiltreRHConges_AffichageBackup']="";
				$_SESSION['FiltreRHConges_RespProjet']="";
				
				$_SESSION['FiltreRHCongesRH_EtatEnCours']="";
				$_SESSION['FiltreRHCongesRH_EtatTransmiRH']="checked";
				$_SESSION['FiltreRHCongesRH_EtatValide']="";
				$_SESSION['FiltreRHCongesRH_EtatRefuse']="";
				$_SESSION['FiltreRHCongesRH_EtatSupprime']="";
				
				$tab = array("Id","Personne","Prestation","Pole","DateCreation","Demandeur","Etat","Contrat","TempsTravail","Metier");
				foreach($tab as $tri){
					if($tri=="Id"){$_SESSION['TriRHConges_'.$tri]="DESC";}
					else{$_SESSION['TriRHConges_'.$tri]="";}
				}
				$_SESSION['TriRHConges_General']="Id DESC,";
				
				$_SESSION['FiltreRHAstreinte_Prestation']="0";
				$_SESSION['FiltreRHAstreinte_Pole']="0";
				$_SESSION['FiltreRHAstreinte_Mois']=date("m");
				$_SESSION['FiltreRHAstreinte_MoisCumules']="checked";
				$_SESSION['FiltreRHAstreinte_Annee']=date("Y");
				$_SESSION['FiltreRHAstreinte_Personne']="";
				$_SESSION['FiltreRHAstreinte_EtatEnCours']="checked";
				$_SESSION['FiltreRHAstreinte_EtatTransmiRH']="checked";
				$_SESSION['FiltreRHAstreinte_EtatValide']="";
				$_SESSION['FiltreRHAstreinte_EtatRefuse']="";
				$_SESSION['FiltreRHAstreinte_EtatSupprime']="";
				$_SESSION['FiltreRHAstreinte_RespProjet']="";
				
				$_SESSION['FiltreRHAstreinteRH_EtatEnCours']="";
				$_SESSION['FiltreRHAstreinteRH_EtatTransmiRH']="checked";
				$_SESSION['FiltreRHAstreinteRH_EtatValide']="";
				$_SESSION['FiltreRHAstreinteRH_EtatRefuse']="";
				$_SESSION['FiltreRHAstreinteRH_EtatSupprime']="";
				
				$tab = array("Id","Personne","Prestation","Pole","DateCreation","Demandeur","Etat","DatePriseEnCompteRH","DateAstreinte","Contrat","TempsTravail");
				foreach($tab as $tri){
					if($tri=="Id"){$_SESSION['TriRHAstreinte_'.$tri]="DESC";}
					else{$_SESSION['TriRHAstreinte_'.$tri]="";}
				}
				$_SESSION['TriRHAstreinte_General']="Id DESC,";
				
				$_SESSION['FiltreRHRessource_Prestation']="0";
				$_SESSION['FiltreRHRessource_Pole']="0";
				$_SESSION['FiltreRHRessource_Metier']="";
				$_SESSION['FiltreRHRessource_Demandeur']=$_SESSION['Id_Personne'];
				$_SESSION['FiltreRHRessource_SigneDateDemarrage']="=";
				$_SESSION['FiltreRHRessource_DateDemarrage']=date('0001-01-01');
				$_SESSION['FiltreRHRessourceRecrut_EtatEnAttenteValidation']="";
				$_SESSION['FiltreRHRessource_EtatEnAttenteValidation']="checked";
				$_SESSION['FiltreRHRessource_EtatValide']="checked";
				$_SESSION['FiltreRHRessource_EtatRefuse']="";
				$_SESSION['FiltreRHRessource_EtatRechercheEC']="checked";
				$_SESSION['FiltreRHRessource_EtatCloture']="";
				$_SESSION['FiltreRHRessource_EtatSupprime']="";
				
				$tab = array("Id","Prestation","Pole","DateDemande","Demandeur","Etat","Lieu","Metier","DateBesoin","Duree","Nombre","Horaire","MotifContrat","MotifDemande","NbrTrouve");
				foreach($tab as $tri){
					if($tri=="DateBesoin"){$_SESSION['TriRHRessource_'.$tri]="DESC";}
					else{$_SESSION['TriRHRessource_'.$tri]="";}
				}
				$_SESSION['TriRHRessource_General']="DateBesoin DESC,";
				
				
				$_SESSION['FiltreRHRessourceB_Prestation']="0";
				$_SESSION['FiltreRHRessourceB_Metier']="";
				$_SESSION['FiltreRHRessourceB_DateDemarrageDebut']=date('0001-01-01');
				$_SESSION['FiltreRHRessourceB_DateDemarrageFin']=date('0001-01-01');
				$_SESSION['FiltreRHRessourceB_Nom']="";
				$_SESSION['FiltreRHRessourceB_Client']="";
				$_SESSION['FiltreRHRessourceB_MotifCloture']="0";
				
				$tab = array("Id","Prestation","Demandeur","DateDemande","Metier","DateBesoin","Duree","Commentaire","Nom","MotifCloture","DateRecrutement","Client");
				foreach($tab as $tri){
					if($tri=="DateBesoin"){$_SESSION['TriRHRessourceB_'.$tri]="DESC";}
					else{$_SESSION['TriRHRessourceB_'.$tri]="";}
				}
				$_SESSION['TriRHRessourceB_General']="DateBesoin DESC,";
				
				$_SESSION['FiltreRHRessourceIndicateur_DateDebut']=date('Y-01-01');
				$_SESSION['FiltreRHRessourceIndicateur_DateFin']=date('0001-01-01');
				$_SESSION['FiltreRHRessourceIndicateur_Plateforme']=0;
				$_SESSION['FiltreRHRessourceIndicateur_ResponsableOperation']="";
				$_SESSION['FiltreRHRessourceIndicateur_ResponsableProjet']="";
				
				//------------- RECRUTEMENT ----------------//
				$_SESSION['FiltreRecrutement_Annee']=date("Y");
				$_SESSION['FiltreRecrutement_Plateforme']=0;
				$_SESSION['FiltreRecrutement_RespOperation']="";
				$_SESSION['FiltreRecrutement_RespProjet']="";
				
				$_SESSION['FiltreRHAT_Mois']=date("m");
				$_SESSION['FiltreRHAT_MoisCumules']="checked";
				$_SESSION['FiltreRHAT_Annee']=date("Y");
				$_SESSION['FiltreRHAT_Personne']="0";
				$_SESSION['FiltreRHAT_Arret']="";
				$_SESSION['FiltreRHAT_RespProjet']="";
				$_SESSION['FiltreRHAT_ArretTravail']="0";
				
				
				$tab = array("Id","Personne","DateCreation","Demandeur","DateAT","HeureAT","Metier","LieuAT","Activite","CommentaireNature","ArretDeTravail");
				foreach($tab as $tri){
					if($tri=="Id"){$_SESSION['TriRHAT_'.$tri]="DESC";}
					else{$_SESSION['TriRHAT_'.$tri]="";}
				}
				$_SESSION['TriRHAT_General']="Id DESC,";
				
				$_SESSION['FiltreRHAbsences_Prestation']="0";
				$_SESSION['FiltreRHAbsences_Pole']="0";
				$_SESSION['FiltreRHAbsences_MoisCumules']="checked";
				$_SESSION['FiltreRHAbsences_Mois']=date("m");
				$_SESSION['FiltreRHAbsences_Annee']=date("Y");
				$_SESSION['FiltreRHAbsences_Personne']="";
				$_SESSION['FiltreRHAbsences_TypeAbsence']="";
				$_SESSION['FiltreRHAbsences_EtatPrisEnCompte']="";
				$_SESSION['FiltreRHAbsences_EtatNonPrisEnCompte']="checked";
				$_SESSION['FiltreRHAbsences_Prevue']="checked";
				$_SESSION['FiltreRHAbsences_NonPrevue']="checked";
				$_SESSION['FiltreRHAbsences_Supprime']="";
				$_SESSION['FiltreRHAbsences_RespProjet']="";
				
				$tab = array("Id","Personne","Contrat","Prestation","Pole","DateCreation","Demandeur","Prevue","TempsTravail","Metier");
				foreach($tab as $tri){
					if($tri=="Id"){$_SESSION['TriRHAbsences_'.$tri]="DESC";}
					else{$_SESSION['TriRHAbsences_'.$tri]="";}
				}
				$_SESSION['TriRHAbsences_General']="Id DESC,";
				
				//BESOINS RECRUTEMENT 
				$_SESSION['FiltreRecrutBesoin_Plateforme']="0";
				$_SESSION['FiltreRecrutBesoin_Prestation']="0";
				$_SESSION['FiltreRecrutBesoin_Metier']="";
				$_SESSION['FiltreRecrutBesoin_Demandeur']="0";
				$_SESSION['FiltreRecrutBesoin_Domaine']="0";
				$_SESSION['FiltreRecrutBesoin_Programme']="0";
				$_SESSION['FiltreRecrutBesoin_Etat']="0";
				$_SESSION['FiltreRecrutBesoin_Statut']="-2";
				$_SESSION['FiltreRecrutBesoin_SigneDateDemarrage']="=";
				$_SESSION['FiltreRecrutBesoin_DateDemarrage']=date('0001-01-01');
				$_SESSION['FiltreRecrutBesoin_Information']="";

				
				$tab = array("Ref","Prestation","Plateforme","Domaine","Programme","DateDemande","Demandeur","Etat","Lieu","Metier","DateBesoin","Duree","Nombre","Horaire","Etat","Statut","Statut2","CreationPoste","DateRecrutementMAJ","DateRecrutement","DateActualisation");
				foreach($tab as $tri){
					if($tri=="Ref"){$_SESSION['TriRecrutBesoin_'.$tri]="ASC";}
					else{$_SESSION['TriRecrutBesoin_'.$tri]="";}
				}
				$_SESSION['TriRecrutBesoin_General']="Ref ASC,";
				
				//ANNONCES RECRUTEMENT 
				$_SESSION['FiltreRecrutAnnonce_Plateforme']="0";
				$_SESSION['FiltreRecrutAnnonce_Metier']="";
				$_SESSION['FiltreRecrutAnnonce_Domaine']="0";
				$_SESSION['FiltreRecrutAnnonce_Programme']="0";
				$_SESSION['FiltreRecrutAnnonce_Etat']="0";
				$_SESSION['FiltreRecrutAnnonce_SigneDateDemarrage']="=";
				$_SESSION['FiltreRecrutAnnonce_DateDemarrage']=date('0001-01-01');
				$_SESSION['FiltreRecrutAnnonce_Information']="";
				$_SESSION['FiltreRecrutAnnonce_MesCandidatures']="0";
				
				$tab = array("Ref","DateRecrutement","Plateforme","Domaine","Lieu","NombrePoste","CategorieProf","Metier","DateBesoin","DateButoire","Demandeur","Nombre","Statut","DateRecrutementMAJ","PersonneAContacter","DateActualisation");
				foreach($tab as $tri){
					if($tri=="Metier"){$_SESSION['TriRecrutAnnonce_'.$tri]="ASC";}
					else{$_SESSION['TriRecrutAnnonce_'.$tri]="";}
				}
				$_SESSION['TriRecrutAnnonce_General']="Metier ASC,";
				
				//INDICATEURS RECRUTEMENT 
				$_SESSION['FiltreRecrutementIndicateur_Plateforme']="";
				$_SESSION['FiltreRecrutementIndicateur_DateDebut']="";
				$_SESSION['FiltreRecrutementIndicateur_DateFin']="";
				
				$tab = array("Ref","Lieu","Nombre");
				foreach($tab as $tri){
					if($tri=="Ref"){$_SESSION['TriRecrutIndicateur1_'.$tri]="ASC";}
					else{$_SESSION['TriRecrutIndicateur1_'.$tri]="";}
				}
				$_SESSION['TriRecrutIndicateur1_General']="Ref ASC,";
				
				$tab = array("Ref","Lieu","Nombre");
				foreach($tab as $tri){
					if($tri=="Ref"){$_SESSION['TriRecrutIndicateur2_'.$tri]="ASC";}
					else{$_SESSION['TriRecrutIndicateur2_'.$tri]="";}
				}
				$_SESSION['TriRecrutIndicateur2_General']="Ref ASC,";
				
				$tab = array("Ref","Metier","Lieu","Nombre","Restant");
				foreach($tab as $tri){
					if($tri=="Ref"){$_SESSION['TriRecrutIndicateur3_'.$tri]="ASC";}
					else{$_SESSION['TriRecrutIndicateur3_'.$tri]="";}
				}
				$_SESSION['TriRecrutIndicateur3_General']="Ref ASC,";
				
				//CONTRATS EN COURS
				$_SESSION['FiltreRHContrat_Recherche']="";
				$_SESSION['FiltreRHContrat_DateDebut']="";
				$_SESSION['FiltreRHContrat_SigneDateDebut']="=";
				$_SESSION['FiltreRHContrat_DateFin']="";
				$_SESSION['FiltreRHContrat_SigneDateFin']="=";
				
				
				$_SESSION['FiltreRHContratEC_Plateforme']="";
				$_SESSION['FiltreRHContratEC_Personne']="";
				$_SESSION['FiltreRHContratEC_Metier']="0";
				$_SESSION['FiltreRHContratEC_TypeDocument']="";
				$_SESSION['FiltreRHContratEC_TypeContrat']="0";
				$_SESSION['FiltreRHContratEC_Coeff']="";
				$_SESSION['FiltreRHContratEC_SigneCoeff']="=";
				$_SESSION['FiltreRHContratEC_DateDebut']="";
				$_SESSION['FiltreRHContratEC_SigneDateDebut']="=";
				$_SESSION['FiltreRHContratEC_DateFin']="";
				$_SESSION['FiltreRHContratEC_SigneDateFin']="=";
				$_SESSION['FiltreRHContratEC_Salaire']="";
				$_SESSION['FiltreRHContratEC_SigneSalaire']="=";
				$_SESSION['FiltreRHContratEC_TauxHoraire']="";
				$_SESSION['FiltreRHContratEC_SigneTauxHoraire']="=";
				$_SESSION['FiltreRHContratEC_TempsTravail']="0";
				$_SESSION['FiltreRHContratEC_Etat']="0";
				
				$tab = array("Id","Personne","Metier","TypeDocument","TypeContrat","AgenceInterim","Coeff","DateDebut","DateFin","TypeCoeff","SalaireBrut","TauxHoraire","TempsTravail","Etat","Titre","Plateforme");
				foreach($tab as $tri){
					if($tri=="Personne"){$_SESSION['TriRHContratEC_'.$tri]="ASC";}
					else{$_SESSION['TriRHContratEC_'.$tri]="";}
				}
				$_SESSION['TriRHContratEC_General']="Personne ASC,";
				
				//INFORMATIONS PERSONNEL 
				$_SESSION['FiltreRHInfosPersonnel_Plateforme']="0";
				$_SESSION['FiltreRHInfosPersonnel_Prestation']="0";
				$_SESSION['FiltreRHInfosPersonnel_Pole']="0";
				$_SESSION['FiltreRHInfosPersonnel_Personne']="0";
				$_SESSION['FiltreRHInfosPersonnel_Affichage']="0";
				
				//PLANNING PERSONNEL 
				$_SESSION['FiltreRHPlanning_Annee']=date("Y");
				
				//PLANNING PERSONNEL 2
				$_SESSION['FiltreRHPlanning2_Annee']=date("Y");
				$_SESSION['FiltreRHPlanning2_Personne']=0;
				
				//VACATIONS 
				$_SESSION['FiltreRHVacation_Prestation']="0";
				$_SESSION['FiltreRHVacation_Pole']="0";
				$_SESSION['FiltreRHVacation_Personne']="0";
				$_SESSION['FiltreRHVacation_DateDebut']=date('Y-m-1');
				$_SESSION['FiltreRHVacation_DateFin']=date("Y-m-d",mktime(0,0,0,date('m')+1,0,date('Y')));
				
				//PLANNING 
				$_SESSION['FiltreRHPlanning_Prestation']="0";
				$_SESSION['FiltreRHPlanning_Pole']="0";
				$_SESSION['FiltreRHPlanning_Personne']="0";
				$_SESSION['FiltreRHPlanning_DateDebut']=date('Y-m-01');
				$_SESSION['FiltreRHPlanning_DateFin']=date("Y-m-d",mktime(0,0,0,date('m')+1,0,date('Y')));
				
				$tab = array("Personne","CodeMetier");
				foreach($tab as $tri){
					if($tri=="Personne"){$_SESSION['TriRHPlanning_'.$tri]="ASC";}
					else{$_SESSION['TriRHPlanning_'.$tri]="";}
				}
				$_SESSION['TriRHPlanning_General']="Personne ASC,";
				
				
				//PLANNING RECHERCHE
				$_SESSION['FiltreRHPlanningRecherche_Prestation']="0";
				$_SESSION['FiltreRHPlanningRecherche_Pole']="0";
				$_SESSION['FiltreRHPlanningRecherche_Personne']="0";
				$_SESSION['FiltreRHPlanningRecherche_Absence']="";
				$_SESSION['FiltreRHPlanningRecherche_Vacation']="";
				$_SESSION['FiltreRHPlanningRecherche_DateDebut']=date('Y-m-01');
				$_SESSION['FiltreRHPlanningRecherche_DateFin']=date("Y-m-d",mktime(0,0,0,date('m')+1,0,date('Y')));
				
				$tab = array("Personne","CodeMetier");
				foreach($tab as $tri){
					if($tri=="Personne"){$_SESSION['TriRHPlanningRecherche_'.$tri]="ASC";}
					else{$_SESSION['TriRHPlanningRecherche_'.$tri]="";}
				}
				$_SESSION['TriRHPlanningRecherche_General']="Personne ASC,";
				
				//VISUALISATION PLANNING 
				$_SESSION['FiltreRHPlanningGlobal_Prestation']="0";
				$_SESSION['FiltreRHPlanningGlobal_Pole']="0";
				$_SESSION['FiltreRHPlanningGlobal_Personne']="0";
				$_SESSION['FiltreRHPlanningGlobal_DateDebut']=date('Y-m-1');
				$_SESSION['FiltreRHPlanningGlobal_DateFin']=date("Y-m-d",mktime(0,0,0,date('m')+1,0,date('Y')));
				
				$tab = array("Personne","CodeMetier");
				foreach($tab as $tri){
					if($tri=="Personne"){$_SESSION['TriRHPlanningGlobal_'.$tri]="ASC";}
					else{$_SESSION['TriRHPlanningGlobal_'.$tri]="";}
				}
				$_SESSION['TriRHPlanningGlobal_General']="Personne ASC,";
				
				//ODM EN COURS
				$_SESSION['FiltreRHODM_Plateforme']="";
				$_SESSION['FiltreRHODM_Personne']="";
				$_SESSION['FiltreRHODM_Metier']="0";
				$_SESSION['FiltreRHODM_TypeContrat']="0";
				$_SESSION['FiltreRHODM_DateDebut']="";
				$_SESSION['FiltreRHODM_SigneDateDebut']="=";
				$_SESSION['FiltreRHODM_DateFin']="";
				$_SESSION['FiltreRHODM_SigneDateFin']="=";
				$_SESSION['FiltreRHODM_Etat']="0";
				
				$tab = array("Id","Personne","Metier","TypeContrat","DateDebut","DateFin","Motif","Etat","Titre","Plateforme");
				foreach($tab as $tri){
					if($tri=="Personne"){$_SESSION['TriRHODM_'.$tri]="ASC";}
					else{$_SESSION['TriRHODM_'.$tri]="";}
				}
				$_SESSION['TriRHODM_General']="Personne ASC,";
				
				//MOUVEMENT DU PERSONNEL HISTORIQUE
				$_SESSION['FiltreRHMouvement_Recherche']="";
				
				//PRESTATION VACATION 
				$_SESSION['FiltreRHPrestaVacation_Prestation']="0_0";
				$_SESSION['FiltreRHVacation_Recherche']="";
				
				//FORMATIONS HORS VACATION 
				$_SESSION['FiltreRHForm_Prestation']="0";
				$_SESSION['FiltreRHForm_Pole']="0";
				$_SESSION['FiltreRHForm_Mois']=date("m");
				$_SESSION['FiltreRHForm_MoisCumules']="checked";
				$_SESSION['FiltreRHForm_Annee']=date("Y");
				$_SESSION['FiltreRHForm_Personne']="";
				$_SESSION['FiltreRHForm_EtatPrisEnCompte']="";
				$_SESSION['FiltreRHForm_EtatNonPrisEnCompte']="checked";
				$_SESSION['FiltreRHForm_RespProjet']="";
				
				$tab = array("Id","Personne","Contrat","PrestationReelle","PoleReel","Prestation","Pole","DateSession","Formation","DatePriseEnCompteRH","Heure_Debut","Heure_Fin");
				foreach($tab as $tri){
					if($tri=="Personne"){$_SESSION['TriRHForm_'.$tri]="ASC";}
					elseif($tri=="DateSession"){$_SESSION['TriRHForm_'.$tri]="ASC";}
					else{$_SESSION['TriRHForm_'.$tri]="";}
				}
				$_SESSION['TriRHForm_General']="Personne ASC,DateSession ASC,";
				
				//ANOMALIE CALCUL FORMATIONS PENDANT ET HORS VACATION
				$_SESSION['FiltreRHFormAnomalie_Prestation']="0";
				$_SESSION['FiltreRHFormAnomalie_Pole']="0";
				$_SESSION['FiltreRHFormAnomalie_Mois']=date("m");
				$_SESSION['FiltreRHFormAnomalie_MoisCumules']="checked";
				$_SESSION['FiltreRHFormAnomalie_Annee']=date("Y");
				$_SESSION['FiltreRHFormAnomalie_Personne']="";
				$_SESSION['FiltreRHFormAnomalie_RespProjet']="";
				
				$tab = array("Id","Personne","Contrat","PrestationReelle","PoleReel","Prestation","Pole","DateSession");
				foreach($tab as $tri){
					if($tri=="Personne"){$_SESSION['TriRHFormAnomalie_'.$tri]="ASC";}
					elseif($tri=="DateSession"){$_SESSION['TriRHFormAnomalie_'.$tri]="ASC";}
					else{$_SESSION['TriRHFormAnomalie_'.$tri]="";}
				}
				$_SESSION['TriRHFormAnomalie_General']="Personne ASC,DateSession ASC,";
				
				//VACATIONS APRES LE 26 DU MOIS
				$_SESSION['FiltreRHVacationEnCompte_Prestation']="0";
				$_SESSION['FiltreRHVacationEnCompte_Pole']="0";
				$_SESSION['FiltreRHVacationEnCompte_Mois']=date("m");
				$_SESSION['FiltreRHVacationEnCompte_MoisCumules']="checked";
				$_SESSION['FiltreRHVacationEnCompte_Annee']=date("Y");
				$_SESSION['FiltreRHVacationEnCompte_Personne']="";
				$_SESSION['FiltreRHVacationEnCompte_EtatPrisEnCompte']="";
				$_SESSION['FiltreRHVacationEnCompte_EtatNonPrisEnCompte']="checked";
				$_SESSION['FiltreRHVacationEnCompte_RespProjet']="";
				
				$tab = array("Id","Personne","Contrat","Prestation","Pole","DateVacation","Vacation","Etat","Modificateur","DateAction","DatePriseEnCompteRH");
				foreach($tab as $tri){
					if($tri=="DateVacation"){$_SESSION['TriRHVacationEnCompte_'.$tri]="ASC";}
					else{$_SESSION['TriRHVacationEnCompte_'.$tri]="";}
				}
				$_SESSION['TriRHVacationEnCompte_General']="DateVacation ASC,";
				
				//VISITES MEDICALES 
				$_SESSION['FiltreRHVMEC_Personne']="";
				$_SESSION['FiltreRHVMEC_Metier']="0";
				$_SESSION['FiltreRHVMEC_TypeContrat']="0";
				$_SESSION['FiltreRHVMEC_TypeVisite']="0";
				$_SESSION['FiltreRHVMEC_DateDerniereVM']="";
				$_SESSION['FiltreRHVMEC_SigneDateDerniereVM']="=";
				$_SESSION['FiltreRHVMEC_DateProchaineVM']="";
				$_SESSION['FiltreRHVMEC_SigneDateProchaineVM']="=";
				$_SESSION['FiltreRHVMEC_SMR']="";
				$_SESSION['FiltreRHVMEC_Restricition']="";
				
				$tab = array("Personne","Metier","TypeContrat","AgenceInterim","DateDebut","DateFin","DateDerniereVM","TypeVisite","SMR","Restriction","DateProchaineVM");
				foreach($tab as $tri){
					if($tri=="Personne"){$_SESSION['TriRHVMEC_'.$tri]="ASC";}
					elseif($tri=="DateDerniereVM"){$_SESSION['TriRHVMEC_'.$tri]="ASC";}
					else{$_SESSION['TriRHVMEC_'.$tri]="";}
				}
				$_SESSION['TriRHVMEC_General']="Personne ASC,DateDerniereVM ASC,";
				
				//DODM
				$_SESSION['FiltreRHDODM_PrestationDep']="0_0";
				$_SESSION['FiltreRHDODM_PrestationDes']="0_0";
				$_SESSION['FiltreRHDODM_Personne']="";
				$_SESSION['FiltreRHDODM_Mois']=date("m");
				$_SESSION['FiltreRHDODM_MoisCumules']="checked";
				$_SESSION['FiltreRHDODM_Annee']=date("Y");
				$_SESSION['FiltreRHDODM_EtatPrisEnCompte']="";
				$_SESSION['FiltreRHDODM_EtatNonPrisEnCompte']="checked";
				$_SESSION['FiltreRHDODM_RespProjet']="";
				
				$tab = array("Id","Personne","PrestationDepart","PrestationDestination","DateDebut","DateFin","Demandeur","Lieu","FraisReel","DemandeAvance",'DatePriseEnCompte');
				foreach($tab as $tri){
					if($tri=="Id"){$_SESSION['TriRHDODM_'.$tri]="ASC";}
					else{$_SESSION['TriRHDODM_'.$tri]="";}
				}
				$_SESSION['TriRHDODM_General']="Id ASC,";
				
				//PERIODE D'ESSAI
				$tab = array("Id","Personne","Prestation","Metier","TypeDocument","TypeContrat","AgenceInterim","DateDebut","DateFin","DateFinPeriodeEssai");
				foreach($tab as $tri){
					if($tri=="DateFinPeriodeEssai"){$_SESSION['TriRHContratPeriodeEssai_'.$tri]="ASC";}
					else{$_SESSION['TriRHContratPeriodeEssai_'.$tri]="";}
				}
				$_SESSION['TriRHContratPeriodeEssai_General']="DateFinPeriodeEssai ASC,";
				
				//18 MOIS 
				$tab = array("Id","Personne","Prestation","Metier","TypeDocument","TypeContrat","AgenceInterim","DateDebut","DateFin","DateDebut18Mois","NbMois","DateFin18Mois");
				foreach($tab as $tri){
					if($tri=="DateFin18Mois"){$_SESSION['TriRHContrat18Mois_'.$tri]="ASC";}
					else{$_SESSION['TriRHContrat18Mois_'.$tri]="";}
				}
				$_SESSION['TriRHContrat18Mois_General']="DateFin18Mois ASC,";
				
				//SUIVI DES EFFECTIFS
				$_SESSION['FiltreRHSuiviEffectif_Annee']=date("Y");
				$_SESSION['FiltreRHSuiviEffectif_Mois']=date("m");
				$_SESSION['FiltreRHSuiviEffectif_Semaine']=date("W");
				$_SESSION['FiltreRHSuiviEffectif_TypeSelect']="Semaine";
				$_SESSION['FiltreRHSuiviEffectif_Plateforme']="0";
				$_SESSION['FiltreRHSuiviEffectif_Prestation']="0";
				$_SESSION['FiltreRHSuiviEffectif_Pole']="0";
				$_SESSION['FiltreRHSuiviEffectif_GroupeMetier']="0";
				$_SESSION['FiltreRHSuiviEffectif_Metier']="0";
				
				//SUIVI DES AM LONGS
				$_SESSION['FiltreRHSuiviAM_Plateforme']="0";
				$_SESSION['FiltreRHSuiviAM_Date']="";
				
				//TAUX ABSENTEISME MALADIE
				$_SESSION['FiltreRHTauxAbsenteisme_Annee']=date("Y");
				$_SESSION['FiltreRHTauxAbsenteisme_Mois']=date("m");
				$_SESSION['FiltreRHTauxAbsenteisme_Plateforme']="0";
				$_SESSION['FiltreRHTauxAbsenteisme_Domaine']="0";
				$_SESSION['FiltreRHTauxAbsenteisme_Prestation']="0";
				$_SESSION['FiltreRHTauxAbsenteisme_Pole']="0";
				
				//ABSENCES EN FORMATION 
				$_SESSION['FiltreRHAbsencesForm_Prestation']="0";
				$_SESSION['FiltreRHAbsencesForm_Pole']="0";
				$_SESSION['FiltreRHAbsencesForm_MoisCumules']="checked";
				$_SESSION['FiltreRHAbsencesForm_Mois']=date("m");
				$_SESSION['FiltreRHAbsencesForm_Annee']=date("Y");
				$_SESSION['FiltreRHAbsencesForm_Personne']="";
				$_SESSION['FiltreRHAbsencesForm_EtatPrisEnCompte']="";
				$_SESSION['FiltreRHAbsencesForm_EtatNonPrisEnCompte']="checked";
				
				$tab = array("Personne","Prestation","Pole","Formation","DateDebut","DateFin","HeureDebut","HeureFin");
				foreach($tab as $tri){
					if($tri=="Personne"){$_SESSION['TriRHAbsencesForm_'.$tri]="ASC";}
					else{$_SESSION['TriRHAbsencesForm_'.$tri]="";}
				}
				$_SESSION['TriRHAbsencesForm_General']="Personne ASC,";
				
				//TURN OVER INTERIM
				$_SESSION['FiltreRHTurnOverInterim_Annee']=date("Y");
				$_SESSION['FiltreRHTurnOverInterim_Semaine']=date("W");
				$_SESSION['FiltreRHTurnOverInterim_Plateforme']="0";
				
				//TURN OVER AAA
				$_SESSION['FiltreRHTurnOverAAA_Annee']=date("Y");
				$_SESSION['FiltreRHTurnOverAAA_Mois']=date("m");
				$_SESSION['FiltreRHTurnOverAAA_Plateforme']="0";
				
				//SUIVI DES HEURES
				$_SESSION['FiltreRHSuiviHeures_Annee']=date("Y");
				$_SESSION['FiltreRHSuiviHeures_Mois']=date("m");
				$_SESSION['FiltreRHSuiviHeures_Semaine']=date("W");
				$_SESSION['FiltreRHSuiviHeures_TypeSelect']="Semaine";
				$_SESSION['FiltreRHSuiviHeures_Plateforme']="0";
				$_SESSION['FiltreRHSuiviHeures_Prestation']="0";
				$_SESSION['FiltreRHSuiviHeures_TypeContrat']="0";
				$_SESSION['FiltreRHSuiviHeures_Par']="0";
				
				//RELEVES D'HEURES
				$_SESSION['FiltreRHRelevesHeures_Annee']=date("Y");
				$_SESSION['FiltreRHRelevesHeures_Mois']=date("m");
				$_SESSION['FiltreRHRelevesHeures_Semaine']=date("W");
				$_SESSION['FiltreRHRelevesHeures_TypeSelect']="Mois";
				$_SESSION['FiltreRHRelevesHeures_Plateforme']="0";
				$_SESSION['FiltreRHRelevesHeures_TypeContrat']="0";
				$_SESSION['FiltreRHRelevesHeures_TempsTravail']="0";
				$_SESSION['FiltreRHRelevesHeures_Agence']="0";
				$_SESSION['FiltreRHRelevesHeures_Personne']="";
				$_SESSION['FiltreRHRelevesHeures_Etat']="";
				$_SESSION['FiltreRHRelevesHeures_DemandeEC']="";
				$_SESSION['RHRelevesHeures_Personnes']="";
				$_SESSION['FiltreRHRelevesHeures_Prestation']="0";
				
				//REPARTITION SALARIES AAA
				$_SESSION['FiltreRHRepartitionAAA_Annee']=date("Y");
				$_SESSION['FiltreRHRepartitionAAA_Mois']=date("m");
				$_SESSION['FiltreRHRepartitionAAA_Plateforme']="0";
				$_SESSION['FiltreRHRepartitionAAA_Prestation']="";
				$_SESSION['FiltreRHSuiviEffectif_Semaine']=date("W");
				$_SESSION['FiltreRHSuiviEffectif_SemaineFin']="";
				
				//COUTS SALARIES AAA
				$_SESSION['FiltreRHCoutAAA_AnneeD']=date("Y");
				$_SESSION['FiltreRHCoutAAA_MoisD']=date("m");
				$_SESSION['FiltreRHCoutAAA_AnneeF']=date("Y");
				$_SESSION['FiltreRHCoutAAA_MoisF']=date("m");
				$_SESSION['FiltreRHCoutAAA_Plateforme']=array("0");
				$_SESSION['FiltreRHCoutAAA_Division']=array("0");
				$_SESSION['FiltreRHCoutAAA_RespProjet']=array("0");
				$_SESSION['FiltreRHCoutAAA_Prestation']=array("0");
				$_SESSION['FiltreRHCoutAAA_TypeTraitement']=0;
				
				//Centre de cout 
				$_SESSION['FiltreRHCC_CC']="0";
				$_SESSION['FiltreRHCC_Sortie']="-1";
				
				//COMPTEUR DE CONGES
				$_SESSION['FiltreRHCompteur_Plateforme']="0";
				$_SESSION['FiltreRHCompteur_Prestation']="0";
				$_SESSION['FiltreRHCompteur_Pole']="0";
				$_SESSION['FiltreRHCompteur_Personne']="";
				$_SESSION['FiltreRHCompteur_Date']=date('Y-m-d');
				
				$tab = array("Id","Personne","Prestation","Contrat","TempsTravail","CP","CPA","RTT");
				foreach($tab as $tri){
					if($tri=="Personne"){$_SESSION['TriRHCompteur_'.$tri]="ASC";}
					else{$_SESSION['TriRHCompteur_'.$tri]="";}
				}
				$_SESSION['TriRHCompteur_General']="Personne ASC,";
				
				//AUTRES
				$_SESSION['RHPartie']=2;
				$_SESSION['AlerteHS']='';
				$_SESSION['AlerteJourAlerte']='';
				
				//Suivi des absences
				$_SESSION['FiltreRHSuiviAbsence_DateDebut']=date('Y-m-1');
				$_SESSION['FiltreRHSuiviAbsence_DateFin']="";
				$_SESSION['FiltreRHSuiviAbsence_EtatPrisEnCompte']="checked";
				$_SESSION['FiltreRHSuiviAbsence_EtatNonPrisEnCompte']="";
				$_SESSION['FiltreRHSuiviAbsence_Prevue']="checked";
				$_SESSION['FiltreRHSuiviAbsence_NonPrevue']="checked";
				$_SESSION['FiltreRHSuiviAbsence_Supprime']="";
				$_SESSION['FiltreRHSuiviAbsence_TypeAbs']="";
				$_SESSION['FiltreRHSuiviAbsence_Personne']="";
				$_SESSION['FiltreRHSuiviAbsence_Prestation']="";
				$_SESSION['FiltreRHSuiviAbsence_RespProjet']="";
				$_SESSION['FiltreRHSuiviAbsence_Metier']="";
				
				
				//**************************************************//
				
				//*************************MORI'S*********************//
				$_SESSION['MORIS_Annee2']=date('Y',strtotime(date('Y-m-d')." -1 month"));
				$_SESSION['MORIS_Mois2']=date('m',strtotime(date('Y-m-d')." -1 month"));
				$_SESSION['MORIS_Prestation']=0;
				$_SESSION['MORIS_Annee']=date('Y',strtotime(date('Y-m-d')." -1 month"));
				$_SESSION['MORIS_Mois']=date('m',strtotime(date('Y-m-d')." -1 month"));
				$_SESSION['MORIS_VisionMonoCompetence']=0;
				$_SESSION['MORIS_ListeFamilleIndefini']="";
				$_SESSION['MORIS_VisionOTDLivrable']=0;
				$_SESSION['MORIS_VisionOTD2Livrable']=0;
				$_SESSION['MORIS_VisionOQDLivrable']=0;
				$_SESSION['MORIS_VisionOQD2Livrable']=0;
				
				//*******************GESTION DU MATERIEL***************//
				//SUIVI DU MATERIEL
				$_SESSION['FiltreToolsSuivi_NumAAA']="";
				$_SESSION['FiltreToolsSuivi_Num']="";
				$_SESSION['FiltreToolsSuivi_NumFicheImmo']="";
				$_SESSION['FiltreToolsSuivi_Plateforme']="-1";
				$_SESSION['FiltreToolsSuivi_Prestation']="0";
				$_SESSION['FiltreToolsSuivi_PrestationA']="1";
				$_SESSION['FiltreToolsSuivi_PrestationI']="0";
				$_SESSION['FiltreToolsSuivi_Pole']="0";
				$_SESSION['FiltreToolsSuivi_Lieu']="0";
				$_SESSION['FiltreToolsSuivi_Caisse']="0";
				$_SESSION['FiltreToolsSuivi_Personne']="0";
				$_SESSION['FiltreToolsSuivi_PersonneEC']="1";
				$_SESSION['FiltreToolsSuivi_PersonneSortie']="0";
				$_SESSION['FiltreToolsSuivi_TypeMateriel']="0";
				$_SESSION['FiltreToolsSuivi_FamilleMateriel']="0";
				$_SESSION['FiltreToolsSuivi_ModeleMateriel']="0";
				$_SESSION['FiltreToolsSuivi_DateAffectation']="";
				$_SESSION['FiltreToolsSuivi_TypeDateAffectation']="";
				$_SESSION['FiltreToolsSuivi_Remarque']="";
				$_SESSION['FiltreToolsSuivi_Designation']="";
				$_SESSION['FiltreToolsSuivi_MaterielEquipe']="1";
				$_SESSION['FiltreToolsSuivi_DateReception']="";
				$_SESSION['FiltreToolsSuivi_TypeDateReception']="";
				$_SESSION['FiltreToolsSuivi_BonCommande']="";
				$_SESSION['FiltreToolsSuivi_Recherche']=0;
				
				if(isset($_SESSION['Id_Plateformes'])){
					foreach($_SESSION['Id_Plateformes'] as $value){
						if($_SESSION['FiltreToolsSuivi_Plateforme']=="-1"){
							$_SESSION['FiltreToolsSuivi_Plateforme']=$value;
						}
					}
				}
				
				$_SESSION['Page_ToolsChangement']="0";
				$_SESSION['NbLigne_ToolsChangement']="40";
				
				$tab = array("SN","LIBELLE_MODELEMATERIEL","NumAAA","Num","DateReception","LIBELLE_FOURNISSEUR","LIBELLE_FABRICANT","LIBELLE_PLATEFORME","LIBELLE_PRESTATION","LIBELLE_LIEU","LIBELLE_CAISSETYPE",'NOMPRENOM_PERSONNE','DateDerniereAffectation','TypeMateriel','FamilleMateriel','Designation');
				foreach($tab as $tri){
					if($tri=="LIBELLE_MODELEMATERIEL"){$_SESSION['TriToolsSuivi_'.$tri]="ASC";}
					elseif($tri=="NumAAA"){$_SESSION['TriToolsSuivi_'.$tri]="ASC";}
					else{$_SESSION['TriToolsSuivi_'.$tri]="";}
				}
				$_SESSION['TriToolsSuivi_General']="LIBELLE_MODELEMATERIEL ASC,NumAAA ASC,";
				
				//LOCATIONS
				$_SESSION['FiltreToolsLocation_NumAAA']="";
				$_SESSION['FiltreToolsLocation_Num']="";
				$_SESSION['FiltreToolsLocation_NumFicheImmo']="";
				$_SESSION['FiltreToolsLocation_Plateforme']="-1";
				$_SESSION['FiltreToolsLocation_Prestation']="0";
				$_SESSION['FiltreToolsLocation_PrestationA']="1";
				$_SESSION['FiltreToolsLocation_PrestationI']="0";
				$_SESSION['FiltreToolsLocation_Pole']="0";
				$_SESSION['FiltreToolsLocation_Lieu']="0";
				$_SESSION['FiltreToolsLocation_Caisse']="0";
				$_SESSION['FiltreToolsLocation_Personne']="0";
				$_SESSION['FiltreToolsLocation_PersonneEC']="1";
				$_SESSION['FiltreToolsLocation_PersonneSortie']="0";
				$_SESSION['FiltreToolsLocation_TypeMateriel']="0";
				$_SESSION['FiltreToolsLocation_FamilleMateriel']="0";
				$_SESSION['FiltreToolsLocation_ModeleMateriel']="0";
				$_SESSION['FiltreToolsLocation_DateAffectation']="";
				$_SESSION['FiltreToolsLocation_TypeDateAffectation']="";
				$_SESSION['FiltreToolsLocation_Remarque']="";
				$_SESSION['FiltreToolsLocation_Designation']="";
				$_SESSION['FiltreToolsLocation_TypeDateFinLocation']="";
				$_SESSION['FiltreToolsLocation_DateFinLocation']="";
				$_SESSION['FiltreToolsLocation_MaterielEquipe']="1";
				
				if(isset($_SESSION['Id_Plateformes'])){
					foreach($_SESSION['Id_Plateformes'] as $value){
						if($_SESSION['FiltreToolsLocation_Plateforme']=="-1"){
							$_SESSION['FiltreToolsLocation_Plateforme']=$value;
						}
					}
				}
				
				$_SESSION['Page_ToolsLocation']="0";
				
				$tab = array("SN","LIBELLE_MODELEMATERIEL","NumAAA","Num","DateReception","LIBELLE_FOURNISSEUR","LIBELLE_FABRICANT","LIBELLE_PLATEFORME","LIBELLE_PRESTATION","LIBELLE_LIEU","LIBELLE_CAISSETYPE",'NOMPRENOM_PERSONNE','DateDerniereAffectation','TypeMateriel','FamilleMateriel','Designation','DateDebutLocation','DateFinLocation');
				foreach($tab as $tri){
					if($tri=="LIBELLE_MODELEMATERIEL"){$_SESSION['TriToolsLocation_'.$tri]="ASC";}
					elseif($tri=="NumAAA"){$_SESSION['TriToolsLocation_'.$tri]="ASC";}
					else{$_SESSION['TriToolsLocation_'.$tri]="";}
				}
				$_SESSION['TriToolsLocation_General']="DateFinLocation ASC,";
				
				//ALERTE CHANGEMENTS
				$_SESSION['FiltreToolsChangement_NumAAA']="";
				$_SESSION['FiltreToolsChangement_Prestation']="0";
				$_SESSION['FiltreToolsChangement_Pole']="0";
				$_SESSION['FiltreToolsChangement_Personne']="0";
				$_SESSION['FiltreToolsChangement_TypeMateriel']="0";
				$_SESSION['FiltreToolsChangement_FamilleMateriel']="0";
				$_SESSION['FiltreToolsChangement_ModeleMateriel']="0";
				
				$tab = array("SN","LIBELLE_MODELEMATERIEL","NumAAA","Num","LIBELLE_PRESTATION",'NOMPRENOM_PERSONNE','DateDerniereAffectation','LIBELLE_NOUVELLEPRESTATION','DateMouvementPrestation');
				foreach($tab as $tri){
					if($tri=="NOMPRENOM_PERSONNE"){$_SESSION['TriToolsChangement_'.$tri]="ASC";}
					else{$_SESSION['TriToolsChangement_'.$tri]="";}
				}
				$_SESSION['TriToolsChangement_General']="NOMPRENOM_PERSONNE ASC,";
				
				$_SESSION['Page_ToolsChangementMateriel']="0";
				
				//ALERTE SORTIE
				$_SESSION['FiltreToolsAlerteSortie_NumAAA']="";
				$_SESSION['FiltreToolsAlerteSortie_Prestation']="0";
				$_SESSION['FiltreToolsAlerteSortie_Pole']="0";
				$_SESSION['FiltreToolsAlerteSortie_Personne']="0";
				$_SESSION['FiltreToolsAlerteSortie_TypeMateriel']="0";
				$_SESSION['FiltreToolsAlerteSortie_FamilleMateriel']="0";
				$_SESSION['FiltreToolsAlerteSortie_ModeleMateriel']="0";
				
				$tab = array("SN","LIBELLE_MODELEMATERIEL","NumAAA","Num","LIBELLE_PRESTATION",'NOMPRENOM_PERSONNE','DateDerniereAffectation','LIBELLE_NOUVELLEPRESTATION','DateMouvementPrestation');
				foreach($tab as $tri){
					if($tri=="NOMPRENOM_PERSONNE"){$_SESSION['TriToolsAlerteSortie_'.$tri]="ASC";}
					else{$_SESSION['TriToolsAlerteSortie_'.$tri]="";}
				}
				$_SESSION['TriToolsAlerteSortie_General']="NOMPRENOM_PERSONNE ASC,";
				
				$_SESSION['Page_ToolsAlerteSortieMateriel']="0";
				
				//INVENTAIRES 
				$_SESSION['FiltreToolsInventaire_NumAAA']="";
				$_SESSION['FiltreToolsInventaire_Prestation']="0";
				$_SESSION['FiltreToolsInventaire_Pole']="0";
				$_SESSION['FiltreToolsInventaire_Caisse']="0";
				$_SESSION['FiltreToolsInventaire_Personne']="0";
				$_SESSION['FiltreToolsInventaire_TypeMateriel']="0";
				$_SESSION['FiltreToolsInventaire_FamilleMateriel']="0";
				$_SESSION['FiltreToolsInventaire_ModeleMateriel']="0";
				
				$tab = array("SN","LIBELLE_MODELEMATERIEL","NumAAA","Num","LIBELLE_PRESTATION","LIBELLE_LIEU","LIBELLE_CAISSETYPE",'NOMPRENOM_PERSONNE','DateDerniereAffectation','TYPEMATERIEL','FAMILLEMATERIEL','Designation');
				foreach($tab as $tri){
					if($tri=="NOMPRENOM_PERSONNE"){$_SESSION['TriToolsInventaire_'.$tri]="ASC";}
					else{$_SESSION['TriToolsInventaire_'.$tri]="";}
				}
				$_SESSION['TriToolsInventaire_General']="NOMPRENOM_PERSONNE ASC,";
				
				$_SESSION['Page_ToolsInventaireMateriel']="0";
				$_SESSION['NbLigne_ToolsInventaireMateriel']="40";
				
				//MATERIEL PERDU
				$_SESSION['FiltreToolsPerdu_NumAAA']="";
				$_SESSION['FiltreToolsPerdu_Prestation']="0";
				$_SESSION['FiltreToolsPerdu_Pole']="0";
				$_SESSION['FiltreToolsPerdu_Personne']="0";
				$_SESSION['FiltreToolsPerdu_TypeMateriel']="0";
				$_SESSION['FiltreToolsPerdu_FamilleMateriel']="0";
				$_SESSION['FiltreToolsPerdu_ModeleMateriel']="0";
				
				$tab = array("SN","LIBELLE_MODELEMATERIEL","NumAAA","Num","LIBELLE_PRESTATION",'DateDerniereAffectation','DernierePresta','DernierLieu','DerniereCaisse','DernierePersonne','DerniereDateAffectation');
				foreach($tab as $tri){
					if($tri=="DateDerniereAffectation"){$_SESSION['TriToolsPerdu_'.$tri]="DESC";}
					else{$_SESSION['TriToolsPerdu_'.$tri]="";}
				}
				$_SESSION['TriToolsPerdu_General']="DateDerniereAffectation DESC,";
				
				$_SESSION['Page_ToolsPerdu']="0";
				
				
				//DECLARATIONS DE PERTE
				$_SESSION['FiltreToolsDeclarationsPerte_NumAAA']="";
				$_SESSION['FiltreToolsDeclarationsPerte_Prestation']="0";
				$_SESSION['FiltreToolsDeclarationsPerte_Pole']="0";
				$_SESSION['FiltreToolsDeclarationsPerte_Personne']="0";
				$_SESSION['FiltreToolsDeclarationsPerte_TypeMateriel']="0";
				$_SESSION['FiltreToolsDeclarationsPerte_FamilleMateriel']="0";
				$_SESSION['FiltreToolsDeclarationsPerte_ModeleMateriel']="0";
				
				$tab = array("FAMILLEMATERIEL","LIBELLE_MODELEMATERIEL","NumAAA","Prestation",'PV_Date','PV_RefDeclaration','Declarant','PV_Lieu','PV_TypeMSN','PV_MSN','PV_Condition');
				foreach($tab as $tri){
					if($tri=="PV_Date"){$_SESSION['TriToolsDeclarationsPerte_'.$tri]="DESC";}
					else{$_SESSION['TriToolsDeclarationsPerte_'.$tri]="";}
				}
				$_SESSION['TriToolsDeclarationsPerte_General']="PV_Date DESC,";
				
				$_SESSION['Page_ToolsDeclarationsPerte']="0";
				
				//OUTILS A L'ETALONNAGE
				$_SESSION['FiltreToolsOutilsEtalonnage_NumAAA']="";
				$_SESSION['FiltreToolsOutilsEtalonnage_Prestation']="0";
				$_SESSION['FiltreToolsOutilsEtalonnage_Pole']="0";
				$_SESSION['FiltreToolsOutilsEtalonnage_Personne']="0";
				$_SESSION['FiltreToolsOutilsEtalonnage_Caisse']="0";
				$_SESSION['FiltreToolsOutilsEtalonnage_TypeMateriel']="0";
				$_SESSION['FiltreToolsOutilsEtalonnage_FamilleMateriel']="0";
				$_SESSION['FiltreToolsOutilsEtalonnage_ModeleMateriel']="0";
				
				$tab = array("SN","LIBELLE_MODELEMATERIEL","NumAAA","Num","LIBELLE_LIEU",'DateDerniereAffectation','DernierePresta','DernierLieu','DerniereCaisse','DerniereCaisse','DernierePersonne');
				foreach($tab as $tri){
					if($tri=="DateDerniereAffectation"){$_SESSION['TriToolsOutilsEtalonnage_'.$tri]="DESC";}
					else{$_SESSION['TriToolsOutilsEtalonnage_'.$tri]="";}
				}
				$_SESSION['TriToolsOutilsEtalonnage_General']="DateDerniereAffectation DESC,";
				
				
				$_SESSION['Page_ToolsOutilsEtalonnage']="0";
				
				//INDICATEURS 
				
				$_SESSION['FiltreEtatGlobalMateriel_Plateformes']="";
				$_SESSION['FiltreEtatGlobalMateriel_Plateforme']=0;
				$_SESSION['FiltreEtatGlobalMateriel_Prestations']="";
				$_SESSION['FiltreEtatGlobalMateriel_Types']="";
				$_SESSION['FiltreEtatGlobalMateriel_ModeAffichage']="Plateforme";
				$_SESSION['FiltreEtatGlobalMateriel_Fournisseur']=0;
				
				$_SESSION['FiltrePerteMateriel_Plateformes']="";
				$_SESSION['FiltrePerteMateriel_Plateforme']=0;
				$_SESSION['FiltrePerteMateriel_Prestations']="";
				$_SESSION['FiltrePerteMateriel_DateDebut']=date("Y-m-d",strtotime(date('Y-m-d')." - 6 month"));
				$_SESSION['FiltrePerteMateriel_DateFin']=date('Y-m-d');
				$_SESSION['FiltrePerteMateriel_Types']="";
				$_SESSION['FiltrePerteMateriel_ModeAffichage']="Plateforme";
				
				$_SESSION['FiltreTypePerteMateriel_Plateformes']="";
				$_SESSION['FiltreTypePerteMateriel_Plateforme']=0;
				$_SESSION['FiltreTypePerteMateriel_Prestations']="";
				$_SESSION['FiltreTypePerteMateriel_DateDebut']=date("Y-m-d",strtotime(date('Y-m-d')." - 6 month"));
				$_SESSION['FiltreTypePerteMateriel_DateFin']=date('Y-m-d');
				$_SESSION['FiltreTypePerteMateriel_Types']="";
				$_SESSION['FiltreTypePerteMateriel_ModeAffichage']="Plateforme";
				
				$_SESSION['FiltreCoutPerteMateriel_Plateformes']="";
				$_SESSION['FiltreCoutPerteMateriel_Plateforme']=0;
				$_SESSION['FiltreCoutPerteMateriel_Prestations']="";
				$_SESSION['FiltreCoutPerteMateriel_DateDebut']=date("Y-m-d",strtotime(date('Y-m-d')." - 6 month"));
				$_SESSION['FiltreCoutPerteMateriel_DateFin']=date('Y-m-d');
				$_SESSION['FiltreCoutPerteMateriel_Types']="";
				$_SESSION['FiltreCoutPerteMateriel_ModeAffichage']="Plateforme";
				
				$_SESSION['FiltreInvestissementMateriel_Plateformes']="";
				$_SESSION['FiltreInvestissementMateriel_Plateforme']=0;
				$_SESSION['FiltreInvestissementMateriel_Prestations']="";
				$_SESSION['FiltreInvestissementMateriel_DateDebut']=date("Y-m-d",strtotime(date('Y-m-d')." - 6 month"));
				$_SESSION['FiltreInvestissementMateriel_DateFin']=date('Y-m-d');
				$_SESSION['FiltreInvestissementMateriel_Types']="";
				$_SESSION['FiltreInvestissementMateriel_ModeAffichage']="Plateforme";
				
				$_SESSION['FiltreImmobilisationMateriel_Plateformes']="";
				$_SESSION['FiltreImmobilisationMateriel_Plateforme']=0;
				$_SESSION['FiltreImmobilisationMateriel_Prestations']="";
				$_SESSION['FiltreImmobilisationMateriel_Types']="";
				$_SESSION['FiltreImmobilisationMateriel_ModeAffichage']="Plateforme";
				
				//TURN OVER AAA
				$_SESSION['FiltreToolsTurnOverAAA_Annee']=date("Y");
				$_SESSION['FiltreToolsTurnOverAAA_Mois']=date("m");
				$_SESSION['FiltreToolsTurnOverAAA_Plateforme']="0";
				
				
				//Personne sans matériel
				$_SESSION['FiltreToolsPersonnesSansMateriel_Prestation']="0";
				$_SESSION['FiltreToolsPersonnesSansMateriel_Plateforme']="0";
				
				//STANDARD PC
				$_SESSION['FiltreToolsStandard_NumAAA']="";
				$_SESSION['FiltreToolsStandard_Prestation']="0";
				$_SESSION['FiltreToolsStandard_Pole']="0";
				$_SESSION['FiltreToolsStandard_Personne']="0";

				//ETALONNAGE
				$_SESSION['FiltreToolsEtalonnage_NumAAA']="";
				$_SESSION['FiltreToolsEtalonnage_Prestation']="0";
				$_SESSION['FiltreToolsEtalonnage_Pole']="0";
				$_SESSION['FiltreToolsEtalonnage_Lieu']="0";
				$_SESSION['FiltreToolsEtalonnage_Caisse']="0";
				$_SESSION['FiltreToolsEtalonnage_Personne']="0";
				$_SESSION['FiltreToolsEtalonnage_TypeMateriel']="0";
				$_SESSION['FiltreToolsEtalonnage_FamilleMateriel']="0";
				$_SESSION['FiltreToolsEtalonnage_ModeleMateriel']="0";
				
				$tab = array("PN","SN","LIBELLE_MODELEMATERIEL","NumAAA","LIBELLE_PRESTATION","LIBELLE_LIEU","LIBELLE_CAISSETYPE",'NOMPRENOM_PERSONNE','TypeMateriel','FamilleMateriel','DateDerniereAffectation','DateDernierEtalonnage','PeriodiciteVerification','DateProchainEtalonnage');
				foreach($tab as $tri){
					if($tri=="LIBELLE_MODELEMATERIEL"){$_SESSION['TriToolsEtalonnage_'.$tri]="ASC";}
					elseif($tri=="NumAAA"){$_SESSION['TriToolsEtalonnage_'.$tri]="ASC";}
					else{$_SESSION['TriToolsEtalonnage_'.$tri]="";}
				}
				$_SESSION['TriToolsEtalonnage_General']="LIBELLE_MODELEMATERIEL ASC,NumAAA ASC,";
				
				//**************************************************//
				
				
				// SUIVI DES EPI ET CONSOMMABLES //
				$_SESSION['FiltreEPIStockMini_Prestation']="0";
				
				$_SESSION['FiltreEPIStock_Prestation']="0";
				
				
				//**************************************************//
				// 					EPE / EPP 						//
				//**************************************************//
				
				//Date butoir
				$_SESSION['FiltreEPEDateButoir_Plateforme']="0";
				$_SESSION['FiltreEPEDateButoir_Prestation']="0";
				$_SESSION['FiltreEPEDateButoir_Pole']="0";
				$_SESSION['FiltreEPEDateButoir_Personne']="0";
				$_SESSION['FiltreEPEDateButoir_TypeEPE']="EPE";
				$_SESSION['FiltreEPEDateButoir_Annee']=date('Y');
				$_SESSION['FiltreEPEDateButoir_AnneeEmbauche']="";
				$_SESSION['FiltreEPEDateButoir_SansDate']=0;
				$_SESSION['FiltreEPEDateButoir_NA']=0;
				$_SESSION['FiltreEPEDateButoir_EnAttente']=0;
				$_SESSION['EPE_DateButoir']="";

				//EPE / EPP
				$_SESSION['FiltreEPE_Plateforme']="0";
				$_SESSION['FiltreEPE_Prestation']="0";
				$_SESSION['FiltreEPE_Pole']="0";
				$_SESSION['FiltreEPE_Personne']="0";
				$_SESSION['FiltreEPE_TypeEPE']="checked";
				$_SESSION['FiltreEPE_TypeEPP']="checked";
				$_SESSION['FiltreEPE_TypeEPPBilan']="checked";
				$_SESSION['FiltreEPE_Annee']=date('Y');
				$_SESSION['FiltreEPE_EtatAF']="checked";
				$_SESSION['FiltreEPE_EtatBrouillon']="checked";
				$_SESSION['FiltreEPE_EtatEC']="checked";
				$_SESSION['FiltreEPE_EtatSoumis']="checked";
				$_SESSION['FiltreEPE_EtatRealise']="";
				$_SESSION['FiltreEPE_AffichageResponsable']="checked";
				$_SESSION['FiltreEPE_AffichageBackup']="";
				$_SESSION['FiltreEPE_Priorite']="0";
				$_SESSION['FiltreEPE_Manager']="0";
				$_SESSION['EPE_Page']=0;
				
				//Indicateurs
				$_SESSION['FiltreEPEIndicateurs_Annee']=date('Y');
				$_SESSION['FiltreEPEIndicateurs_Type']="";
				$_SESSION['FiltreEPEIndicateurs_Plateforme']="";
				$_SESSION['FiltreEPEIndicateurs_TypeEPE']="EPE";
				$_SESSION['FiltreEPEIndicateurs_Personne']="0";
				$_SESSION['FiltreEPEIndicateurs_Responsable']="0";
				
				//Plusieurs affectations 
				$_SESSION['FiltreEPEAffectation_Plateforme']="0";
				$_SESSION['FiltreEPEAffectation_Prestation']="0";
				$_SESSION['FiltreEPEAffectation_Pole']="0";
				$_SESSION['FiltreEPEAffectation_Annee']=date('Y');
				$_SESSION['FiltreEPEAffectation_Personne']="0";
				$_SESSION['FiltreEPEAffectation_SansAffectation']="1";
				
				//Liste des qualif et formations 
				$_SESSION['FiltreEPEQualifFormation_Obligatoire']="";
				
				//Progressions salariale et professionnelles
				$_SESSION['FiltreEPEProgression_Plateforme']="0";
				$_SESSION['FiltreEPEProgression_Prestation']="0";
				$_SESSION['FiltreEPEProgression_Pole']="0";
				$_SESSION['FiltreEPEProgression_Personne']="0";
				$_SESSION['FiltreEPEProgression_Annee']=date('Y',strtotime(date('Y-m-d')." -1 year"));
				$_SESSION['FiltreEPEProgression_Type']="";
				
				//Changement manager
				$_SESSION['FiltreEPEChangement_Recherche']="";
				$_SESSION['FiltreEPEChangement_Annee']=date('Y');
				
				//Souhait EPP
				$_SESSION['FiltreSouhaitEPP_Plateforme']="0";
				$_SESSION['FiltreSouhaitEPP_Prestation']="0";
				$_SESSION['FiltreSouhaitEPP_Pole']="0";
				$_SESSION['FiltreSouhaitEPP_Personne']="0";
				$_SESSION['FiltreSouhaitEPP_Annee']=date('Y');
				$_SESSION['FiltreSouhaitEPP_Affectation']="1";
				$_SESSION['FiltreSouhaitEPP_SouhaitEvolution']="0";
				$_SESSION['FiltreSouhaitEPP_SouhaitMobilite']="0";
				
				//**************************************************//
				// 					RECORD   						//
				//**************************************************//
				
				$_SESSION['FiltreRECORD_Division']="";
				$_SESSION['FiltreRECORD_UER']="";
				$_SESSION['FiltreRECORD_Contrat']="";
				$_SESSION['FiltreRECORD_FamilleR03']="";
				$_SESSION['FiltreRECORD_Client']="";
				$_SESSION['FiltreRECORD_DivisionClient']="";
				$_SESSION['FiltreRECORD_EntiteAchat']="";
				$_SESSION['FiltreRECORD_RespProjet']="";
				$_SESSION['FiltreRECORD_RespProjet0']="";
				$_SESSION['FiltreRECORD_Prestation']="";
				$_SESSION['FiltreRECORD_Vision']="1";
				$_SESSION['FiltreRECORD_NbMois']="12";
				$_SESSION['FiltreRECORD_VoirTout']="";
				$_SESSION['FiltreRECORD_Statut']="1";

				
				$tab = array("MatriculeAAA","Personne","MetierPaie","Prestation","Pole","Plateforme","Manager","SouhaitEvolution","SouhaitMobilite","Etat");
				foreach($tab as $tri){
					if($tri=="Personne"){$_SESSION['TriEPESouhait_'.$tri]="ASC";}
					else{$_SESSION['TriEPESouhait_'.$tri]="";}
				}
				$_SESSION['TriEPESouhait_General']="Personne ASC,";
				
				//ANNONCES RECRUTEMENT 
				$_SESSION['FiltreTel_Plateforme']="0";
				$_SESSION['FiltreTel_Prestation']="0_0";
				$_SESSION['FiltreTel_Personne']="0";
				$_SESSION['FiltreTel_Num']="";
				$_SESSION['FiltreTel_TypeBesoin']="";
				$_SESSION['FiltreTel_Etat']="";
				$_SESSION['FiltreTel_DateDebut']=date('0001-01-01');
				$_SESSION['FiltreTel_DateFin']=date('0001-01-01');
				
				$tab = array("Id","Plateforme","Prestation","Personne","CommentaireAffectation","Fonction","Demandeur","DateCreation","TypeBesoin","JustificatifDemande","EtatValidation","DateValidation");
				foreach($tab as $tri){
					if($tri=="Id"){$_SESSION['TriTel_'.$tri]="ASC";}
					else{$_SESSION['TriTel_'.$tri]="";}
				}
				$_SESSION['TriTel_General']="Id ASC,";
				
				//**************************************************//
				// 					SODA     						//
				//**************************************************//
				
				$_SESSION['FiltreSODA_Theme']="0";
				$_SESSION['FiltreSODA_Questionnaire']="0";
				$_SESSION['FiltreSODA_Actif']="0";
				$_SESSION['FiltreSODA_Specifique']="-1";
				
				$_SESSION['FiltreSODA_Annee']=date('Y');
				
				$_SESSION['FiltreSODAPlannif_Theme']="0";
				$_SESSION['FiltreSODAPlannif_Questionnaire']="0";
				
				$_SESSION['FiltreSODA_Plateforme']="0";
				$_SESSION['FiltreSODA_DateDebut']=date("Y-m-d", mktime(0, 0, 0, date('m'), 1 ,date('Y')));
				$_SESSION['FiltreSODA_DateFin']=date("Y-m-d", mktime(0, 0, 0, date('m')+1, 0 ,date('Y')));
				
				$_SESSION['FiltreSODAConsult_Plateforme']="0";
				$_SESSION['FiltreSODAConsult_Prestation']="0";
				$_SESSION['FiltreSODAConsult_PrestationA']="1";
				$_SESSION['FiltreSODAConsult_PrestationI']="0";
				$_SESSION['FiltreSODAConsult_DateSurveillance']="";
				$_SESSION['FiltreSODAConsult_Theme']="0";
				$_SESSION['FiltreSODAConsult_Surveille']="0";
				$_SESSION['FiltreSODAConsult_Surveillant']="0";
				$_SESSION['FiltreSODAConsult_Annee']="";
				$_SESSION['FiltreSODAConsult_TypeTheme']="";
				$_SESSION['FiltreSODAConsult_PlateformeTheme']="0";
				$_SESSION['FiltreSODAConsult_Questionnaire']="0";
				$_SESSION['FiltreSODAConsult_NumSurveillance']="";
				$_SESSION['FiltreSODAConsult_NumAT']="";
				$_SESSION['FiltreSODAConsult_ATNonRenseigne']="";
				$_SESSION['FiltreSODAConsult_InfObjectif']="";
				$_SESSION['FiltreSODAConsult_MonPerimetre']="";
				$_SESSION['FiltreSODAConsult_Etat']="";
				$_SESSION['FiltreSODAConsult_NCActionAT']="";
				
				$_SESSION['FiltreSODAQuestionNA_Plateforme']="0";
				$_SESSION['FiltreSODAQuestionNA_Prestation']="0";
				$_SESSION['FiltreSODAQuestionNA_Theme']="0";
				$_SESSION['FiltreSODAQuestionNA_Questionnaire']="0";
				$_SESSION['FiltreSODAQuestionNA_Surveille']="0";
				$_SESSION['FiltreSODAQuestionNA_Surveillant']="0";
				$_SESSION['FiltreSODAQuestionNA_NumSurveillance']="";
				
				$_SESSION['FiltreSODAAccueil_Theme']="0";
				$_SESSION['FiltreSODAAccueil_Questionnaire']="0";
				$_SESSION['FiltreSODAAccueil_Plateforme']="0";
				$_SESSION['FiltreSODAAccueil_Prestation']="0";
				$_SESSION['FiltreSODAAccueil_Surveillant']="-1";
				
				$_SESSION['FiltreSODAFormationPratique_UER']="0";
				$_SESSION['FiltreSODAFormationPratique_Qualification']="0";
				$_SESSION['FiltreSODAFormationPratique_Personne']="0";
				$_SESSION['FiltreSODAFormationPratique_Evaluation']="0";
				
				$resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$_SESSION['Id_Personne']);
				$nbAccess=mysqli_num_rows($resAcc);

				$resAccSuperAdmin=mysqli_query($bdd,"SELECT Id FROM soda_superadministrateur WHERE Id_Personne=".$_SESSION['Id_Personne']);
				$nbSuperAdmin=mysqli_num_rows($resAccSuperAdmin);
				
				if($nbAccess==0 && $nbSuperAdmin==0){
					if(isset($_SESSION['Id_Plateformes'])){
						foreach($_SESSION['Id_Plateformes'] as $value){
							if($_SESSION['FiltreSODAAccueil_Plateforme']=="0"){
								$_SESSION['FiltreSODAAccueil_Plateforme']=$value;
							}
						}
					}
				}
				
				$_SESSION['FiltreSODAAccueil_Surveillant']="-1";
				
				$tab = array("Id","Plateforme","Prestation","Theme","Questionnaire","Etat","Resultat","NumActionTracker","DateSurveillance","Surveille","Surveillant");
				foreach($tab as $tri){
					if($tri=="DateSurveillance"){$_SESSION['TriConsultSODA_'.$tri]="DESC";}
					elseif($tri=="Id"){$_SESSION['TriConsultSODA_'.$tri]="DESC";}
					else{$_SESSION['TriConsultSODA_'.$tri]="";}
				}
				$_SESSION['TriConsultSODA_General']="DateSurveillance DESC,Id DESC,";
				
				$_SESSION['FiltreSODATDBThematique_ModeAffichage']="Mois";
				$_SESSION['FiltreSODATDBThematique_Theme']="-1";
				$_SESSION['FiltreSODATDBThematique_Questionnaire']="-1";
				$_SESSION['FiltreSODATDBThematique_Plage']="12";
				$_SESSION['FiltreSODATDBThematique_UER']="-1";
				$_SESSION['FiltreSODATDBThematique_Mois']=date("m");
				$_SESSION['FiltreSODATDBThematique_Annee']=date("Y");
				$_SESSION['FiltreSODATDBThematique_MoisFin']=date("m");
				$_SESSION['FiltreSODATDBThematique_AnneeFin']=date("Y");
				
				$_SESSION['FiltreSODATDBOperation_ModeAffichage']="Theme";
				$_SESSION['FiltreSODATDBOperation_Theme']="-1";
				$_SESSION['FiltreSODATDBOperation_Questionnaire']="-1";
				$_SESSION['FiltreSODATDBOperation_Plage']="12";
				$_SESSION['FiltreSODATDBOperation_UER']="-1";
				$_SESSION['FiltreSODATDBOperation_Prestation']="-1";
				$_SESSION['FiltreSODATDBOperation_RespProjet']="-1";
				$_SESSION['FiltreSODATDBOperation_DateDebut']="0001-01-01";
				$_SESSION['FiltreSODATDBOperation_DateFin']="0001-01-01";
				$_SESSION['FiltreSODATDBOperation_Mois']="01";
				$_SESSION['FiltreSODATDBOperation_Annee']=date("Y");
				$_SESSION['FiltreSODATDBOperation_MoisFin']=12;
				$_SESSION['FiltreSODATDBOperation_AnneeFin']=date("Y");
				
				$_SESSION['FiltreSODATDBProcessus_Theme']="-1";
				$_SESSION['FiltreSODATDBProcessus_Plage']=date('Y');
				$_SESSION['FiltreSODATDBProcessus_UER']="-1";
				$_SESSION['FiltreSODATDBProcessus_DateDebut']="0001-01-01";
				$_SESSION['FiltreSODATDBProcessus_DateFin']="0001-01-01";
				
				//**************************************************//
				// 					ONBOARDING						//
				//**************************************************//
				
				$_SESSION['FiltreOnboarding_Theme']="";
				$_SESSION['FiltreOnboarding_MotsCles']="";
				
				//**************************************************//
				
				//**************************************************//
				// 					ACCIDENTS DE TRAVAIL			//
				//**************************************************//
				
				$_SESSION['FiltreAccidentT_UER']="0";
				$_SESSION['FiltreAccidentT_Mois']=date("m");
				$_SESSION['FiltreAccidentT_MoisCumules']="checked";
				$_SESSION['FiltreAccidentT_Annee']=date("Y");
				$_SESSION['FiltreAccidentT_Personne']="0";
				$_SESSION['FiltreAccidentT_Arret']="";
				$_SESSION['FiltreAccidentT_ArretTravail']="";
				
				$tab = array("Id","Personne","DateCreation","UER","Demandeur","DateAT","HeureAT","Metier","LieuAT","Activite","CommentaireNature","ArretDeTravail");
				foreach($tab as $tri){
					if($tri=="Id"){$_SESSION['TriAccidentT_'.$tri]="DESC";}
					else{$_SESSION['TriAccidentT_'.$tri]="";}
				}
				$_SESSION['TriAccidentT_General']="Id DESC,";
				
				
				//**************************************************//
				// 					GPAO     						//
				//**************************************************//
				
				$_SESSION['Id_GPAO']="0";
				$_SESSION['GPAO_Id_ListeDeroulante']="";
				$_SESSION['Menu']="1";
				$_SESSION['GPAO_IdWO']="0";
				$_SESSION['GPAO_Aircraft']=0;
				
				$_SESSION['PageGPAO_ListeWO']="0";

				$tabDisplay=array("General","Archives","Coordination","Production","Quality","Concession");
				$tabChamps=array('Customer','Imputation','AM','OF','NC','QLB','TLB','Concession','Para','PlanDate','LimitDateFOT','Designation','TargetTime','WorkingProgress','FI','MSN','Position','Type','CreationDate','Priority','Comments','ClosureDate','WorkingShift','NewEoW','OTDEoW','EscalationPoint','LastEoW','OTDComment','UpdateDateTandem','FOTDate','EoWDQ1','NewEoWDQ1','CreationDateDQ1','CommentsDQ1','FollowUpConcession','CommentsA_CMS2','NewEoWAvailable','PriorityReason','Skills','LastStatus','LastStatusDate','StatusComment');
				
				foreach($tabDisplay as $display)
				{
					foreach($tabChamps as $champs)
					{
						
						if($display=="General"){
							if($champs=="ClosureDate"){$_SESSION['TriGPAO_'.$display.'_'.$champs]="ASC";}
							elseif($champs=="CreationDate"){$_SESSION['TriGPAO_'.$display.'_'.$champs]="ASC";}
							else{$_SESSION['TriGPAO_'.$display.'_'.$champs]="";}
						}
						elseif($display=="Archives"){
							if($champs=="ClosureDate"){$_SESSION['TriGPAO_'.$display.'_'.$champs]="ASC";}
							else{$_SESSION['TriGPAO_'.$display.'_'.$champs]="";}
						}
						elseif($display=="Quality"){
							if($champs=="EscalationPoint"){$_SESSION['TriGPAO_'.$display.'_'.$champs]="DESC";}
							elseif($champs=="LastEoW"){$_SESSION['TriGPAO_'.$display.'_'.$champs]="DESC";}
							elseif($champs=="PlanDate"){$_SESSION['TriGPAO_'.$display.'_'.$champs]="DESC";}
							elseif($champs=="WorkingShift"){$_SESSION['TriGPAO_'.$display.'_'.$champs]="ASC";}
							elseif($champs=="LastStatus"){$_SESSION['TriGPAO_'.$display.'_'.$champs]="ASC";}
							else{$_SESSION['TriGPAO_'.$display.'_'.$champs]="";}
						}
						else{
							$_SESSION['TriGPAO_'.$display.'_'.$champs]="";
						}
						
					}
					if($display=="General"){
						$_SESSION['TriGPAO_'.$display.'_General']="ClosureDate ASC,CreationDate ASC,";
					}
					elseif($display=="Archives"){
						if($champs=="ClosureDate"){$_SESSION['TriGPAO_'.$display.'_'.$champs]="ASC";}
						$_SESSION['TriGPAO_'.$display.'_General']="ClosureDate ASC,";
					}
					elseif($display=="Quality"){
						$_SESSION['TriGPAO_'.$display.'_General']="EscalationPoint DESC,LastEoW DESC,PlanDate DESC,WorkingShift ASC,LastStatus ASC,";
					}
					else{
						$_SESSION['TriGPAO_'.$display.'_General']="";
					}
				}
				
				//Logistic 
				$tabChamps=array('Customer','Type','MSN','AM','OF','NC','QLB','Para','TLB','LastStatus','PartNumber','Quantity','CMS','RefDIV','PartsDeliveryDate','PartsReceivedOn','CreationDate');
				$display="Logistic";
				foreach($tabChamps as $champs)
				{
					$_SESSION['TriGPAO_'.$display.'_'.$champs]="";
				}
				$_SESSION['TriGPAO_'.$display.'_General']="";
				

				$req="SELECT Id_PrestationGPAO,Display,Valeur, Type
					FROM gpao_tableau 
					WHERE Suppr=0 ";
					
				$resultTitreDisplay=mysqli_query($bdd,$req);
				$nbTitreDisplay=mysqli_num_rows($resultTitreDisplay);
				
				$tabDisplay = array();
				if($nbTitreDisplay>0){
					while($rowDisplay=mysqli_fetch_array($resultTitreDisplay))
					{
						$_SESSION['FiltreGPAO'.$rowDisplay['Id_PrestationGPAO'].'_'.$rowDisplay['Display'].'_'.$rowDisplay['Valeur']]="";
						$_SESSION['FiltreGPAO'.$rowDisplay['Id_PrestationGPAO'].'_'.$rowDisplay['Display'].'_'.$rowDisplay['Valeur']."_2"]="";
						$_SESSION['FiltreGPAO'.$rowDisplay['Id_PrestationGPAO'].'_'.$rowDisplay['Display'].'_'.$rowDisplay['Valeur']."_Du"]="";
						$_SESSION['FiltreGPAO'.$rowDisplay['Id_PrestationGPAO'].'_'.$rowDisplay['Display'].'_'.$rowDisplay['Valeur']."_Au"]="";
						$_SESSION['FiltreGPAO'.$rowDisplay['Id_PrestationGPAO'].'_'.$rowDisplay['Display'].'_'.$rowDisplay['Valeur']."_Type"]="";
					}
				}
				
				//**************************************************//
				

				echo "<html>";
				if($_SESSION['Nom']=="" || $_SESSION['Prenom']=="" || $_SESSION['Log']=="" || $_SESSION['Mdp']=="")
				{
					echo "<body onload='window.location.top=\"".$chemin."/index.php?Cnx=CKIES\";'>";
				}
				else{
					if($_SESSION['Mdp']=="aaa01"){echo "<body onload='window.location.href=\"".$chemin."/MotDePasse.php\";'>";}
					else {
						echo "<body onload='window.location.href=\"".$chemin."/Accueil.php\";'>";
					}
				}

			}
			else{
				echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";
			}
		}
		else{
			echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";
		}
	}
	else{
		echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";
		}

	mysqli_free_result($result);	// Libération des résultats
	mysqli_close($bdd);			// Fermeture de la connexion
}
?>
</body>
</html>

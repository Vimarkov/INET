<?php
require("Connexioni.php");
require("../v2/Outils/Fonctions.php");
require("../v2/Outils/Formation/Globales_Fonctions.php");
require("../v2/Outils/PlanningV2/Fonctions_Planning.php");

if(isset($_POST["login"]))
{
	$tableau = array();
	// Exécuter des requêtes SQL
	$result=mysqli_query($bdd,"SELECT Nom,Prenom,Email, Id, Trigramme FROM new_rh_etatcivil WHERE Login='".$_POST["login"]."' AND Motdepasse='".$_POST["motdepasse"]."'");
	$nbenreg=mysqli_num_rows($result);
	
	if($_POST["login"] <> "" and $_POST["motdepasse"] <> "")
	{
		if($nbenreg==1)
		{
			//Creation des variables de session
			$row=mysqli_fetch_row($result);
			
			//Verification si la personne n'est pas en Z-SORTIE
			$resultPlat=mysqli_query($bdd,"SELECT Id_Personne FROM new_competences_personne_plateforme WHERE Id_Personne=".$row[3]." AND Id_Plateforme=14 ");
			$nbPlat=mysqli_num_rows($resultPlat);
			
			if($nbPlat==0){
				session_cache_limiter('private');

				/* Configure le délai d'expiration à 30 minutes */
				session_cache_expire(30);
				session_start();
				if($_SERVER['SERVER_NAME']=="127.0.0.1" || $_SERVER['SERVER_NAME']=="localhost" || $_SERVER['SERVER_NAME']=="192.168.20.3" || $_SERVER['SERVER_NAME']=="frcodc0001"){
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
				$_SESSION['Langue']=$_POST["Langue"];
				
				$_SESSION['MdpDefaut']="aaa01";
				
				/* Variables demande d'accès */
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
				
				//*******************GESTION DU MATERIEL***************//
				//SUIVI DU MATERIEL
				$_SESSION['FiltreToolsSuivi_NumAAA']="";
				$_SESSION['FiltreToolsSuivi_Num']="";
				$_SESSION['FiltreToolsSuivi_Prestation']="0";
				$_SESSION['FiltreToolsSuivi_Pole']="0";
				$_SESSION['FiltreToolsSuivi_Lieu']="0";
				$_SESSION['FiltreToolsSuivi_Caisse']="0";
				$_SESSION['FiltreToolsSuivi_Personne']="0";
				$_SESSION['FiltreToolsSuivi_TypeMateriel']="0";
				$_SESSION['FiltreToolsSuivi_FamilleMateriel']="0";
				$_SESSION['FiltreToolsSuivi_ModeleMateriel']="0";
				$_SESSION['FiltreToolsSuivi_DateAffectation']="";
				$_SESSION['FiltreToolsSuivi_TypeDateAffectation']="";
				$_SESSION['FiltreToolsSuivi_Remarque']="";
				
				$_SESSION['Page_ToolsChangement']="0";
				
				$tab = array("SN","LIBELLE_MODELEMATERIEL","NumAAA","Num","DateReception","LIBELLE_FOURNISSEUR","LIBELLE_FABRICANT","LIBELLE_PRESTATION","LIBELLE_LIEU","LIBELLE_CAISSETYPE",'NOMPRENOM_PERSONNE','DateDerniereAffectation','TypeMateriel','FamilleMateriel');
				foreach($tab as $tri){
					if($tri=="LIBELLE_MODELEMATERIEL"){$_SESSION['TriToolsSuivi_'.$tri]="ASC";}
					elseif($tri=="NumAAA"){$_SESSION['TriToolsSuivi_'.$tri]="ASC";}
					else{$_SESSION['TriToolsSuivi_'.$tri]="";}
				}
				$_SESSION['TriToolsSuivi_General']="LIBELLE_MODELEMATERIEL ASC,NumAAA ASC,";
				
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
					if($tri=="DateDerniereAffectation"){$_SESSION['TriToolsChangement_'.$tri]="DESC";}
					else{$_SESSION['TriToolsChangement_'.$tri]="";}
				}
				$_SESSION['TriToolsPerdu_General']="DateDerniereAffectation DESC,";
				
				
				$_SESSION['Page_ToolsPerdu']="0";
				
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
				$_SESSION['Page']="0";
				//**************************************************//
				
				
				//**************************************************//
				// 					SODA     						//
				//**************************************************//
				
				$_SESSION['FiltreSODA_Theme']="0";
				$_SESSION['FiltreSODA_Questionnaire']="0";
				$_SESSION['FiltreSODA_Actif']="0";
				
				$_SESSION['FiltreSODA_Annee']=date("Y");
				
				$_SESSION['FiltreSODAPlannif_Theme']="0";
				$_SESSION['FiltreSODAPlannif_Questionnaire']="0";
				
				$_SESSION['FiltreSODA_Plateforme']="0";
				$_SESSION['FiltreSODA_DateDebut']=date("Y-m-d", mktime(0, 0, 0, date('m'), 1 ,date('Y')));
				$_SESSION['FiltreSODA_DateFin']=date("Y-m-d", mktime(0, 0, 0, date('m')+1, 0 ,date('Y')));
				
				$_SESSION['FiltreSODAConsult_Plateforme']="0";
				$_SESSION['FiltreSODAConsult_Prestation']="0";
				$_SESSION['FiltreSODAConsult_DateSurveillance']="";
				$_SESSION['FiltreSODAConsult_Theme']="0";
				$_SESSION['FiltreSODAConsult_Surveille']="0";
				$_SESSION['FiltreSODAConsult_Surveillant']="0";
				$_SESSION['FiltreSODAConsult_Annee']="";
				$_SESSION['FiltreSODAConsult_TypeTheme']="";
				$_SESSION['FiltreSODAConsult_PlateformeTheme']="0";
				$_SESSION['FiltreSODAConsult_Questionnaire']="0";
				$_SESSION['FiltreSODAConsult_Etat']="";
				$_SESSION['FiltreSODAConsult_NumSurveillance']="";
				$_SESSION['FiltreSODAConsult_NumAT']="";
				$_SESSION['FiltreSODAConsult_ATNonRenseigne']="";
				$_SESSION['FiltreSODAConsult_InfObjectif']="";
				$_SESSION['FiltreSODAConsult_MonPerimetre']="";
				
				$_SESSION['FiltreSODAQuestionNA_Plateforme']="0";
				$_SESSION['FiltreSODAQuestionNA_Prestation']="0";
				$_SESSION['FiltreSODAQuestionNA_Theme']="0";
				$_SESSION['FiltreSODAQuestionNA_Questionnaire']="0";
				$_SESSION['FiltreSODAQuestionNA_Surveille']="0";
				$_SESSION['FiltreSODAQuestionNA_Surveillant']="0";
				$_SESSION['FiltreSODAQuestionNA_NumSurveillance']="";
				
				$_SESSION['FiltreSODAAccueil_Theme']="0";
				$_SESSION['FiltreSODAAccueil_Questionnaire']="0";
				$_SESSION['FiltreSODAAccueil_Plateforme']="-1";
				$_SESSION['FiltreSODAAccueil_Prestation']="0";
				$_SESSION['FiltreSODAAccueil_Surveillant']="-1";
			
				
				$req = "
				SELECT Id_Personne
				FROM new_competences_relation 
				WHERE Id_Personne=".$_SESSION['Id_Personne']." 
				AND (Evaluation='L'
				OR
				(Evaluation='X'
				AND Date_Debut<='".date('Y-m-d')."'
				AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
				)
				)
				AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
				UNION
				SELECT soda_surveillant.Id_Personne
				FROM
					soda_surveillant
				WHERE Id_Personne=".$_SESSION['Id_Personne']." ";
				$resultSurveillant=mysqli_query($bdd,$req);
				$nbenregSurveillant=mysqli_num_rows($resultSurveillant);
				if($nbenregSurveillant>0){$_SESSION['FiltreSODAAccueil_Surveillant']=$_SESSION['Id_Personne'];}
				
				echo "<html>";
				if($_SESSION['Nom']=="" || $_SESSION['Prenom']=="" || $_SESSION['Log']=="" || $_SESSION['Mdp']=="")
				{echo "<body onload='window.location.top=\"".$chemin."/index.php?Cnx=CKIES\";'>";}
				else{
					echo "<body onload='window.location.href=\"".$chemin."/Accueil.php\";'>";
				}
			}
			else{
				echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";
			}
		}
		else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
	}
	else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}

	mysqli_free_result($result);	// Libération des résultats
	mysqli_close($bdd);			// Fermeture de la connexion
}
?>
</body>
</html>

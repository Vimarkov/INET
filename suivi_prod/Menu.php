<?php
	session_start();
	require("Outils/Connexioni.php");
	echo "<div id='navigation'>";
	
	if($_SESSION['LogSP'] <> "" && $_SESSION['DroitSP'] <> ""){
?>
<table width="100%">
<tr><td align="center">
<ul id="menu" class="dropdown">
  <?php
	//Réinitialiser à la page d'accueil si pas de login enregistré (plus de connexion)
	if($_SESSION['PrestationSP']=="TBWP"){
		if(!isset($_SESSION['LogSP'])){echo "<script>top.location.href='".$chemin."/index.php';</script>";}
	 
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Dossier.php'>Suivi des dossiers</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Ajouter_Dossier.php'>Saisir un dossier</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Ajouter_DossierSansACP.php'>Saisir un dossier inexistant</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/PNE/Liste_PNE.php'>Suivi des PNE</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/NC/Liste_NC.php'>Suivi des NC</a></li>\n";
		if($_SESSION['Id_PersonneSP']==1351 || $_SESSION['Id_PersonneSP']==406 || $_SESSION['Id_PersonneSP']==3527 || $_SESSION['Id_PersonneSP']==1930 || $_SESSION['Id_PersonneSP']==3737 || $_SESSION['Id_PersonneSP']==194 || $_SESSION['Id_PersonneSP']==198 || $_SESSION['Id_PersonneSP']==1870){
			echo "<li><a name='importACP' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/ImportACP.php'>Import ACP</a></li>\n";
			echo "<li><a name='CIA' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/CIA.php'>CIA</a></li>\n";
		}
		echo "<li><a href='#'>Exports</a>\n";
		echo "<ul>";
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/AnalyseMSNCT.php'>Analyse MSN /CT</a></li>\n";
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_Extract.php'>Extracts</a></li>\n";
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_Indicateur.php'>Indicateurs RETP</a></li>\n";
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_IndicateurRETQ.php'>Indicateurs RETQ</a></li>\n";
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_Reporting.php'>Reporting</a></li>\n";
		echo "</ul></li>\n";
		if(substr($_SESSION['DroitSP'],3,1)=='1'){
			echo "<li><a href='#'>Paramètres</a>\n";
			echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Acces/Liste_Utilisateur.php'>Accès Utilisateurs</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ATA.php'>ATA</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ECME.php'>ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ECMEClient.php'>ECME Client</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Ingredient.php'>Ingrédients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Planning_CT.php'>Planning CT / MSN</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/NC/Liste_TypeDefaut.php'>Types défauts NC</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeECME.php'>Type ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Retour.php'>Types retours</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Urgence.php'>Urgences</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Zone.php'>Zones</a></li>\n";
			echo "</ul></li>\n";
		}
	}
	elseif($_SESSION['PrestationSP']=="AEWP" || $_SESSION['PrestationSP']=="CAALR" || $_SESSION['PrestationSP']=="CAATR" || $_SESSION['PrestationSP']=="TEST_PREPA"){
		if(!isset($_SESSION['LogSP'])){echo "<script>top.location.href='".$chemin."/index.php';</script>";}
	 
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Dossier.php'>Suivi des dossiers</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Ajouter_DossierSansACP.php'>Saisir un dossier inexistant</a></li>\n";
		echo "<li><a href='#'>Reporting</a>\n";
		echo "<ul>";
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_Extract.php'>Extracts</a></li>\n";
		echo "</ul></li>\n";
		if(substr($_SESSION['DroitSP'],3,1)=='1'){
			echo "<li><a href='#'>Paramètres</a>\n";
			echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Acces/Liste_Utilisateur.php'>Accès Utilisateurs</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Client.php'>Clients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ECME.php'>ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Section.php'>Sections</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Poste.php'>Postes</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Zone.php'>Zones</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeECME.php'>Type ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Retour.php'>Types de retours</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Ingredient.php'>Ingrédients</a></li>\n";
				if($_SESSION['PrestationSP']=="AEWP" || $_SESSION['PrestationSP']=="TEST_PREPA"){
					echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_SiteIntervention.php'>Site intervention</a></li>\n";
				}
			echo "</ul></li>\n";
		}
	}
	elseif($_SESSION['PrestationSP']=="OLW"){
		if(!isset($_SESSION['LogSP'])){echo "<script>top.location.href='".$chemin."/index.php';</script>";}
	 
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Dossier.php'>Suivi des dossiers</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Ajouter_DossierSansACP.php'>Saisir un dossier inexistant</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_Reporting.php'>Reporting</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_KPI.php'>KPI</a></li>\n";
		if(substr($_SESSION['DroitSP'],3,1)=='1'){
			echo "<li><a href='#'>Paramètres</a>\n";
			echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Acces/Liste_Utilisateur.php'>Accès Utilisateurs</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Client.php'>Clients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ECME.php'>ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Ingredient.php'>Ingrédients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_LigneContrat.php'>Lignes contrats</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Section.php'>Sections</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Pole.php'>Pôles</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Poste.php'>Postes</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_SiteIntervention.php'>Site intervention</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeECME.php'>Type ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Retour.php'>Types de retours</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Zone.php'>Zones</a></li>\n";
			echo "</ul></li>\n";
		}
	}
	elseif($_SESSION['PrestationSP']=="TTWP"){
		if(!isset($_SESSION['LogSP'])){echo "<script>top.location.href='".$chemin."/index.php';</script>";}
	 
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Dossier.php'>Suivi des dossiers</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Ajouter_DossierSansACP.php'>Saisir un dossier inexistant</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ActiviteSupplementaire.php'>Activité supplémentaire</a></li>\n";
		//echo "<li><a href='#'>Exports</a>\n";
		//echo "<ul>";
		//	echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_Indicateur.php'>Indicateurs RETP</a></li>\n";
		//	echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_IndicateurRETQ.php'>Indicateurs RETQ</a></li>\n";
		//	echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_Reporting.php'>Reporting</a></li>\n";
		//echo "</ul></li>\n";
		if(substr($_SESSION['DroitSP'],3,1)=='1'){
			echo "<li><a href='#'>Paramètres</a>\n";
			echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Acces/Liste_Utilisateur.php'>Accès Utilisateurs</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Client.php'>Clients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Section.php'>Sections</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Pole.php'>Pôles</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Poste.php'>Postes</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Retour.php'>Types de retours</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Ingredient.php'>Ingrédients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeActiviteSupp.php'>Type activité supplémentaire</a></li>\n";
			echo "</ul></li>\n";
		}
	}
	elseif($_SESSION['PrestationSP']=="AT47"){
		if(!isset($_SESSION['LogSP'])){echo "<script>top.location.href='".$chemin."/index.php';</script>";}
	 
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Dossier.php'>Suivi des dossiers</a></li>\n";
		if(substr($_SESSION['DroitSP'],4,1)=='1' || substr($_SESSION['DroitSP'],3,1)=='1'){
			echo "<li><a href='#'>Qualité</a>\n";
				echo "<ul>";
					echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_AM.php'>AM / NC majeures</a></li>\n";
					echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_CQLB.php'>Points CQLB</a></li>\n";
				echo "</ul></li>\n";
		}
		if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],3,1)=='1'){
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_MSN.php'>MSN</a></li>\n";
		}
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_Reporting.php'>Reporting</a></li>\n";
		if(substr($_SESSION['DroitSP'],3,1)=='1'){
			echo "<li><a href='#'>Paramètres</a>\n";
			echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Acces/Liste_Utilisateur.php'>Accès Utilisateurs</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Article.php'>Articles</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_InformationStatut.php'>Informations statuts</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ECME.php'>ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Localisation.php'>Localisations</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Type.php'>Types (AM/NC/CQLB)</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeECME.php'>Types ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeProduit.php'>Type Produit</a></li>\n";
			echo "</ul></li>\n";
		}
	}
	elseif($_SESSION['PrestationSP']=="EISA"){
		if(!isset($_SESSION['LogSP'])){echo "<script>top.location.href='".$chemin."/index.php';</script>";}
	 
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Dossier.php'>Suivi des dossiers</a>\n";
		echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Dossier_Corbeille.php'>Corbeille</a></li>\n";;
		echo "</ul>";
		echo "</li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_AM.php'>Suivi des AM</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Moteur.php'>Suivi des MSN</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_Reporting.php'>Reporting</a></li>\n";
		if(substr($_SESSION['DroitSP'],3,1)=='1'){
			echo "<li><a href='#'>Paramètres</a>\n";
			echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Acces/Liste_Utilisateur.php'>Accès Utilisateurs</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ActionCurative.php'>Action curative</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Article.php'>Article</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Cote.php'>Côté</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ECME.php'>ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Imputation.php'>Imputation</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Localisation.php'>Localisation</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_MomentDetection.php'>Moment de détection</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ProduitImpacte.php'>Produit impacté</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeDefaut.php'>Type défaut</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeECME.php'>Type ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeProduit.php'>Type Produit</a></li>\n";
			echo "</ul></li>\n";
		}
	}
	elseif($_SESSION['PrestationSP']=="LATECOERE 380"){
		if(!isset($_SESSION['LogSP'])){echo "<script>top.location.href='".$chemin."/index.php';</script>";}
	 
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Dossier.php'>Suivi des dossiers</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Ajouter_DossierSansACP.php'>Saisir un dossier inexistant</a></li>\n";
		if(substr($_SESSION['DroitSP'],3,1)=='1'){
			echo "<li><a href='#'>Paramètres</a>\n";
			echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Acces/Liste_Utilisateur.php'>Accès Utilisateurs</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Client.php'>Clients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Section.php'>Sections</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Pole.php'>Pôles</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Poste.php'>Postes</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Zone.php'>Zones</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Retour.php'>Types de retours</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Ingredient.php'>Ingrédients</a></li>\n";
			echo "</ul></li>\n";
		}
	}
	elseif($_SESSION['PrestationSP']=="BELX"){
		if(!isset($_SESSION['LogSP'])){echo "<script>top.location.href='".$chemin."/index.php';</script>";}
	 
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Dossier.php'>Suivi des dossiers</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Ajouter_DossierSansACP.php'>Saisir un dossier inexistant</a></li>\n";
		if(substr($_SESSION['DroitSP'],3,1)=='1'){
			echo "<li><a href='#'>Paramètres</a>\n";
			echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Acces/Liste_Utilisateur.php'>Accès Utilisateurs</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Client.php'>Clients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Section.php'>Sections</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Pole.php'>Pôles</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Poste.php'>Postes</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Zone.php'>Zones</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Retour.php'>Types de retours</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Ingredient.php'>Ingrédients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Urgence.php'>Urgences</a></li>\n";
			echo "</ul></li>\n";
		}
	}
	elseif($_SESSION['PrestationSP']=="RATX"){
		if(!isset($_SESSION['LogSP'])){echo "<script>top.location.href='".$chemin."/index.php';</script>";}
	 
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Dossier.php'>Suivi des dossiers</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Ajouter_DossierSansACP.php'>Saisir un dossier inexistant</a></li>\n";
		if(substr($_SESSION['DroitSP'],3,1)=='1'){
			echo "<li><a href='#'>Paramètres</a>\n";
			echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Acces/Liste_Utilisateur.php'>Accès Utilisateurs</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Client.php'>Clients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Section.php'>Sections</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Pole.php'>Pôles</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Poste.php'>Postes</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Zone.php'>Zones</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Retour.php'>Types de retours</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Ingredient.php'>Ingrédients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Urgence.php'>Urgences</a></li>\n";
			echo "</ul></li>\n";
		}
	}
	elseif($_SESSION['PrestationSP']=="SCOX"){
		if(!isset($_SESSION['LogSP'])){echo "<script>top.location.href='".$chemin."/index.php';</script>";}
	 
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Dossier.php'>Suivi des dossiers</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Ajouter_DossierSansACP.php'>Saisir un dossier inexistant</a></li>\n";
		if(substr($_SESSION['DroitSP'],3,1)=='1'){
			echo "<li><a href='#'>Paramètres</a>\n";
			echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Acces/Liste_Utilisateur.php'>Accès Utilisateurs</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Client.php'>Clients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Section.php'>Sections</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Pole.php'>Pôles</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Poste.php'>Postes</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Zone.php'>Zones</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Retour.php'>Types de retours</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Ingredient.php'>Ingrédients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Urgence.php'>Urgences</a></li>\n";
			echo "</ul></li>\n";
		}
	}
	elseif($_SESSION['PrestationSP']=="P17S"){
		if(!isset($_SESSION['LogSP'])){echo "<script>top.location.href='".$chemin."/index.php';</script>";}
		/*
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Dossier.php'>Suivi des dossiers</a>\n";
		echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Dossier_Corbeille.php'>Corbeille</a></li>\n";;
		echo "</ul>";
		echo "</li>\n";*/
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_AM.php'>Suivi des AM</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_Reporting.php'>Reporting</a></li>\n";
		if(substr($_SESSION['DroitSP'],3,1)=='1'){
			echo "<li><a href='#'>Paramètres</a>\n";
			echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Acces/Liste_Utilisateur.php'>Accès Utilisateurs</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ActionCurative.php'>Action curative</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ActionCorrective.php'>Action corrective</a></li>\n";
				//echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Article.php'>Article</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Cote.php'>Côté</a></li>\n";
				//echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ECME.php'>ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Imputation.php'>Imputation défaut</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Localisation.php'>Localisation</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_MomentDetection.php'>Moment de détection</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ProduitImpacte.php'>Produit impacté</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeDefaut.php'>Type défaut</a></li>\n";
				//echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeECME.php'>Type ECME</a></li>\n";
				//echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeProduit.php'>Type Produit</a></li>\n";
			echo "</ul></li>\n";
		}
	}
	elseif($_SESSION['PrestationSP']=="TRSA-TRMY"){
		if(!isset($_SESSION['LogSP'])){echo "<script>top.location.href='".$chemin."/index.php';</script>";}
		
		echo "<li><a href='#'>Suivi des dossiers</a>\n";
		echo "<ul>";
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Dossier.php'>TRSA</a></li>\n";
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_DossierTRMY.php'>TRMY</a></li>\n";
		echo "</ul></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TravauxSupp.php'>Suivi des travaux supplémentaires</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_MSN.php'>MSN</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_AM.php'>Suivi des AM/NC</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_CQLB.php'>Suivi des CQLB</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_Reporting.php'>Extract</a></li>\n";
		if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],3,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
			echo "<li><a href='#'>Paramètres</a>\n";
			echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Acces/Liste_Utilisateur.php'>Accès Utilisateurs</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ActionCorrective.php'>Action demandée</a></li>\n";
				//echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ActionCurative.php'>Action curative</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_AffectationZone.php'>Articles</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Cluster.php'>Cluster</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_CauseRetour.php'>Causes retour</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Cote.php'>Côté</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ECME.php'>ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeProduit.php'>Ingrédients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Localisation.php'>Localisation</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_MomentDetection.php'>Moment de détection</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Origine.php'>Origine</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ProduitImpacte.php'>Produit impacté</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Station.php'>Stations / Postes</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeAM.php'>Type d'AM/NC</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeDefaut.php'>Type de défaut</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeECME.php'>Type ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeTravail.php'>Type travail</a></li>\n";
			echo "</ul></li>\n";
		}
	}
	elseif($_SESSION['PrestationSP']=="SBVA"){
		if(!isset($_SESSION['LogSP'])){echo "<script>top.location.href='".$chemin."/index.php';</script>";}
		
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Dossier.php'>Suivi des dossiers</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Modif_Dossier.php?Mode=A'>Saisir un nouveau dossier</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_MSN.php'>MSN</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_AM.php'>Qualité</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_Reporting.php'>Exports</a></li>\n";
		if(substr($_SESSION['DroitSP'],3,1)=='1'){
			echo "<li><a href='#'>Paramètres</a>\n";
			echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Acces/Liste_Utilisateur.php'>Accès Utilisateurs</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_CategorieRetour.php'>Catégorie de retour</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_CauseRetard.php'>Cause retard</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ECME.php'>ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_FamilleDossier.php'>Famille de dossier</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Imputation.php'>Imputation</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Operation.php'>Opération</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeProduit.php'>Produit</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeECME.php'>Type ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeTravail.php'>Type</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeAM.php'>Type AM</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeDossier.php'>Type dossier</a></li>\n";
			echo "</ul></li>\n";
		}
	}
	elseif($_SESSION['PrestationSP']=="AISLP" || $_SESSION['PrestationSP']=="TT350"){
		if(!isset($_SESSION['LogSP'])){echo "<script>top.location.href='".$chemin."/index.php';</script>";}
	 
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Dossier.php'>Suivi des dossiers</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Ajouter_DossierSansACP.php'>Saisir un dossier</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_AM.php'>Suivi des NC AAA</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_QLB.php'>Suivi des QLB</a></li>\n";
		echo "<li><a href='#'>Extracts & KPI's</a>\n";
		echo "<ul>";
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_Extract.php'>Extracts</a></li>\n";
		echo "</ul></li>\n";
		if(substr($_SESSION['DroitSP'],3,1)=='1'){
			echo "<li><a href='#'>Paramètres</a>\n";
			echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Client.php'>Clients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ECME.php'>ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ECMEClient.php'>ECME Client</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Acces/Liste_Utilisateur.php'>Liste personnel</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Localisation.php'>Localisation</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Poste.php'>Postes</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Ingredient.php'>Ingrédients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Type.php'>Type (AM/NC/QLB)</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeECME.php'>Type ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeECMEClient.php'>Type ECME Client</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Retour.php'>Types de retours</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Section.php'>Sections</a></li>\n";
			echo "</ul></li>\n";
		}
	}
	elseif($_SESSION['PrestationSP']=="ZCS-PGC"){
		if(!isset($_SESSION['LogSP'])){echo "<script>top.location.href='".$chemin."/index.php';</script>";}
	 
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Dossier.php'>Suivi des dossiers</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Ajouter_DossierSansACP.php'>Saisir un dossier inexistant</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_Reporting.php'>Reporting</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_KPI.php'>KPI</a></li>\n";
		if(substr($_SESSION['DroitSP'],3,1)=='1'){
			echo "<li><a href='#'>Paramètres</a>\n";
			echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Acces/Liste_Utilisateur.php'>Accès Utilisateurs</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Client.php'>Clients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ECME.php'>ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Ingredient.php'>Ingrédients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Section.php'>Sections</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Pole.php'>Pôles</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Poste.php'>Postes</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeECME.php'>Type ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Retour.php'>Types de retours</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Zone.php'>Zones</a></li>\n";
			echo "</ul></li>\n";
		}
	}
	elseif($_SESSION['PrestationSP']=="ATRRQ"){
		if(!isset($_SESSION['LogSP'])){echo "<script>top.location.href='".$chemin."/index.php';</script>";}
	 
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Dossier.php'>Suivi des dossiers</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Ajouter_DossierSansACP.php'>Saisir un dossier inexistant</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_Reporting.php'>Reporting</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_KPI.php'>KPI</a></li>\n";
		if(substr($_SESSION['DroitSP'],3,1)=='1'){
			echo "<li><a href='#'>Paramètres</a>\n";
			echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Acces/Liste_Utilisateur.php'>Accès Utilisateurs</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Client.php'>Clients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ECME.php'>ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Ingredient.php'>Ingrédients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Section.php'>Sections</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Pole.php'>Pôles</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Poste.php'>Postes</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeECME.php'>Type ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Retour.php'>Types de retours</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Zone.php'>Zones</a></li>\n";
			echo "</ul></li>\n";
		}
	}
	elseif($_SESSION['PrestationSP']=="CO330" || $_SESSION['PrestationSP']=="CO350" || $_SESSION['PrestationSP']=="S-NHHPO" || $_SESSION['PrestationSP']=="RSP AAA GmbH" || $_SESSION['PrestationSP']=="TEST_PROD"){
		if(!isset($_SESSION['LogSP'])){echo "<script>top.location.href='".$chemin."/index.php';</script>";}
	 
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Dossier.php'>Suivi des dossiers</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Ajouter_DossierSansACP.php'>Saisir un dossier</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_AM.php'>Suivi des NC AAA</a></li>\n";
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_QLB.php'>Suivi des QLB</a></li>\n";
		echo "<li><a href='#'>Extracts & KPI's</a>\n";
		echo "<ul>";
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Export/Liste_Extract.php'>Extracts</a></li>\n";
		echo "</ul></li>\n";
		if(substr($_SESSION['DroitSP'],3,1)=='1'){
			echo "<li><a href='#'>Paramètres</a>\n";
			echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Client.php'>Clients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ECME.php'>ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_ECMEClient.php'>ECME Client</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Acces/Liste_Utilisateur.php'>Liste personnel</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Localisation.php'>Localisation</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Poste.php'>Postes</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Ingredient.php'>Ingrédients</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Type.php'>Type (AM/NC/QLB)</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeECME.php'>Type ECME</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_TypeECMEClient.php'>Type ECME Client</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Retour.php'>Types de retours</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/suivi_prod/"."Outils/".$_SESSION['PrestationSP']."/Dossier/Liste_Section.php'>Sections</a></li>\n";
			echo "</ul></li>\n";
		}
	}
  ?>
</ul>
</td></tr>
</table>
<?php
	}
	echo "</div>";
?>
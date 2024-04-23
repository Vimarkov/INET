<?php
	session_start();
	require("Outils/VerifPage.php");
	require("Outils/Connexioni.php");
	echo "<div id='navigation'>";
	
	if($_SESSION['LogTR'] <> "" && $_SESSION['DroitTR'] <> ""){
?>
<table width="100%">
<tr><td align="center">
<ul id="menu" class="dropdown">
  <?php
	//Réinitialiser à la page d'accueil si pas de login enregistré (plus de connexion)
		if(!isset($_SESSION['LogTR'])){echo "<script>top.location.href='".$chemin."/index.php?L=".$_SESSION['Langue']."';</script>";}
		//Nom en fonction de la langue
		if($_SESSION['Langue']=="EN"){
			$production="Production";
			$validation="Validation";
			$anomalie="Anomalies";
			$checklist="Check-list";
			$cdc="Specifications";
			$uo="Work unit";
			$tache="Task";
			$horsDelais="LeadTimes";
			$reporting="Reporting";
			$extract="Extract";
			$tdbProduction="Dashboard production/productivity";
			$tdbControle="Dashboard control";
			$parametre="Settings";
			$utilisateurs="User access";
			$origine="Origin";
			$ponderation="Weighting";
			$responsable="Responsible";
			$familleTache="Task family";
			$categorie="Category";
			$causeDelais="Cause of delay";
			$familleErreur="Family of errors";
			$dt="Technical Domain";
			$wp="Workpackages";
			$responsableDelais="Responsible for delays";
			$administrateur="Administrator";
			$accesAdministrateur="Administrator access";
			$categorieFAQ="Questions category";
			$prestation="Site";
			$faq="Questions";
			$planning="Planning";
			$pointage="Schedule";
			$bl="Delivery note";
		}
		else{
			$production="Production";
			$validation="Validation";
			$anomalie="Anomalies";
			$checklist="Check-list";
			$cdc="Cahier des charges";
			$uo="Unités d'oeuvres";
			$tache="Tâches";
			$horsDelais="Hors délais";
			$reporting="Reporting";
			$extract="Extract";
			$tdb="Tableau de bord";
			$tdbProduction="Tableau de bord production/productivité";
			$tdbControle="Tableau de bord contrôle";
			$parametre="Paramètres";
			$utilisateurs="Accès utilisateurs";
			$origine="Origine";
			$ponderation="Pondération";
			$responsable="Responsable";
			$familleTache="Famille tâches";
			$categorie="Catégorie";
			$causeDelais="Cause délais";
			$familleErreur="Famille erreur";
			$dt="Domaine technique";
			$wp="Workpackages";
			$responsableDelais="Responsable délais";
			$administrateur="Administrateur";
			$accesAdministrateur="Accès administrateur";
			$categorieFAQ="Catégorie FAQ";
			$prestation="Prestation";
			$faq="FAQ";
			$planning="Planning";
			$pointage="Pointage";
			$bl="Bon de livraison";
		}
		if(substr($_SESSION['DroitTR'],0,1)=='1' || substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],2,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],4,1)=='1'){
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Production/Production.php' target='General'>".$production."</a></li>\n";
		}
		if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],4,1)=='1'){
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Production/Validation.php'  target='General'>".$validation."</a></li>\n";
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Production/HorsDelais.php'  target='General'>".$horsDelais."</a></li>\n";
		}
		if(substr($_SESSION['DroitTR'],0,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],4,1)=='1'){
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Production/Anomalie.php'  target='General'>".$anomalie."</a></li>\n";
		}
		$reqPlanning="SELECT Id FROM trame_prestation WHERE Planning=1 AND Id=".$_SESSION['Id_PrestationTR'];
		$resultPlanning=mysqli_query($bdd,$reqPlanning);
		$nbResultaPlanning=mysqli_num_rows($resultPlanning);
		if($nbResultaPlanning==0){
			if(substr($_SESSION['DroitTR'],0,1)=='1' || substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],4,1)=='1'){
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Planning/Planning.php'  target='General'>".$planning."</a></li>\n";
			}
			if(substr($_SESSION['DroitTR'],0,1)=='1' || substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],4,1)=='1'){
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Planning/Pointage.php'  target='General'>".$pointage."</a></li>\n";
			}
		}
		if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1'){
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Production/CahierDesCharges.php'  target='General'>".$cdc."</a></li>\n";
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Production/Tache.php'  target='General'>".$tache."</a></li>\n";
		}
		if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' ){
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Production/UO.php'  target='General'>".$uo."</a></li>\n";
		}
		if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1'){
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Production/Checklist.php'  target='General'>".$checklist."</a></li>\n";
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Reporting/BonLivraison.php'  target='General'>".$bl."</a></li>\n";
		}
		echo "<li><a href='#'>".$reporting."</a>\n";
		echo "<ul>";
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Reporting/Extract.php' target='General'>".$extract."</a></li>\n";
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Reporting/TDB_Production.php' target='General'>".$tdbProduction."</a></li>\n";
			echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Reporting/TDB_Controle.php' target='General'>".$tdbControle."</a></li>\n";
		echo "</ul></li>\n";
		if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],5,1)=='1'){
			echo "<li><a href='#'>".$parametre."</a>\n";
			echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Parametre/Liste_Utilisateur.php' target='General'>".$utilisateurs."</a></li>\n";
				echo "<li><a href='#'>".$anomalie."</a>\n";
				echo "<ul>";
					echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Parametre/FamilleErreur.php' target='General'>".$familleErreur."</a></li>\n";
					echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Parametre/Origine.php' target='General'>".$origine."</a></li>\n";
					echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Parametre/Ponderation.php' target='General'>".$ponderation."</a></li>\n";
					echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Parametre/Responsable.php' target='General'>".$responsable."</a></li>\n";
				echo "</ul></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Parametre/CheckList.php' target='General'>".$checklist."</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Parametre/DomaineTechnique.php' target='General'>".$dt."</a></li>\n";
				echo "<li><a href='#'>".$horsDelais."</a>\n";
				echo "<ul>";
					echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Parametre/CauseDelais.php' target='General'>".$causeDelais."</a></li>\n";
					echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Parametre/ResponsableDelais.php' target='General'>".$responsableDelais."</a></li>\n";
				echo "</ul></li>\n";
				echo "<li><a href='#'>".$tache."</a>\n";
				echo "<ul>";
					echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Parametre/FamilleTache.php' target='General'>".$familleTache."</a></li>\n";
				echo "</ul></li>\n";
				echo "<li><a href='#'>".$uo."</a>\n";
				echo "<ul>";
					echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Parametre/Categorie.php' target='General'>".$categorie."</a></li>\n";
				echo "</ul></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Parametre/WP.php' target='General'>".$wp."</a></li>\n";
			echo "</ul></li>\n";
		}
		if(substr($_SESSION['DroitTR'],5,1)=='1'){
			echo "<li><a href='#'>".$administrateur."</a>\n";
			echo "<ul>";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Administrateur/Liste_Utilisateur.php' target='General'>".$accesAdministrateur."</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Administrateur/CategorieFAQ.php' target='General'>".$categorieFAQ."</a></li>\n";
				echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/Administrateur/Prestation.php' target='General'>".$prestation."</a></li>\n";
			echo "</ul></li>\n";
		}
		echo "<li><a href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/TraME/"."Outils/FAQ/FAQ.php' target='General'>".$faq."</a></li>\n";
		  
  ?>
</ul>
</td></tr>
</table>
<?php
	}
	echo "</div>";
?>
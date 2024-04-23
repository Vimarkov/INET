<?php 
session_start();
global $IdPersonneConnectee;
global $HTTPServeur;
global $HTTPServeur2;

if(isset($_SESSION['Id_Personne'])){$IdPersonneConnectee=$_SESSION['Id_Personne'];}
else{$IdPersonneConnectee=0;}

if(isset($_SESSION['HTTP'])){$HTTPServeur=$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/mobile/";}
else{$HTTPServeur="https://".$_SERVER['SERVER_NAME']."/mobile/";}

if(isset($_SESSION['HTTP'])){$HTTPServeur2=$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/";}
else{$HTTPServeur2="https://".$_SERVER['SERVER_NAME']."/v2/";}

$reqSurveillant = "SELECT Id FROM soda_surveillant WHERE Id_Personne=".$IdPersonneConnectee." ";	
$nbSurveillant=mysqli_num_rows($resAcc=mysqli_query($bdd,$reqSurveillant));

$req="SELECT Id_Personne 
	FROM new_competences_relation 
	WHERE Evaluation='X'
	AND Date_Debut<='".date('Y-m-d')."'
	AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
	AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
	AND Id_Personne=".$IdPersonneConnectee." ";
$resultSurQualifie=mysqli_query($bdd,$req);
$nbSurveillantQualifie=mysqli_num_rows($resultSurQualifie);

$req="SELECT Id_Personne 
	FROM new_competences_relation 
	WHERE Evaluation='L'
	AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777)
	AND Id_Personne=".$_SESSION['Id_Personne']." ";
$resultSurQualifie=mysqli_query($bdd,$req);
$nbSurveillantECQualif=mysqli_num_rows($resultSurQualifie);

?>
<!-- partial:index.partial.html -->
<div id="menu-container">
   <div id="menu-wrapper">
	  <div id="hamburger-menu"><span></span><span></span><span></span></div>
	  <!-- hamburger-menu -->
   </div>
   <!-- menu-wrapper -->
   <ul class="menu-list accordion">
		<?php
		if((mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM soda_administrateur WHERE Id_Personne=".$IdPersonneConnectee))>0) 
			|| (mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM soda_superadministrateur WHERE Id_Personne=".$IdPersonneConnectee))>0)
			|| (mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM soda_surveillant WHERE Id_Personne=".$IdPersonneConnectee))>0)
			|| (mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id_Personne FROM new_competences_relation WHERE (Evaluation='L' OR (Evaluation='X' AND Date_Debut<='".date('Y-m-d')."' AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01'))) AND Id_Qualification_Parrainage IN (SELECT Id FROM new_competences_qualification WHERE Id_Categorie_Qualification=151 AND Id<>3777) AND Id_Personne=".$IdPersonneConnectee))>0)
			|| (mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM soda_theme WHERE Suppr=0 AND (Id_Gestionnaire=".$IdPersonneConnectee." OR Id_Backup1=".$IdPersonneConnectee." OR Id_Backup2=".$IdPersonneConnectee." OR Id_Backup3=".$IdPersonneConnectee.") "))>0)
			|| DroitsFormationPlateformes(array(1,3,4,5,7,9,10,13,16,17,18,19,23,24,27,28,29,32),array($IdPosteResponsableQualite,$IdPosteCoordinateurSecurite,$IdPosteReferentQualiteSysteme,$IdPosteChargeMissionOperation))
			|| DroitsFormationPrestations(array(1,3,4,5,7,9,10,13,16,17,18,19,23,24,27,28,29,32),array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,8))
		)
		{	
		?>
		  <li id="nav1" class="toggle accordion-toggle"> 
			 <span class="icon-plus"><img width='30px' src="<?php echo $HTTPServeur2; ?>Images/Formation/Jumelles.png" border='0' /></span>
			 SODA v1
		  </li>
		  <ul class="menu-submenu accordion-content">
			<li style="border-bottom:1px dotted #988876;height:70px;"><a class="head" style="color:#988876;" href="<?php echo $HTTPServeur; ?>SODA/Accueil.php"><?php if($_SESSION["Langue"]=="FR"){echo "Surveillances planifiées";}else{echo "Scheduled monitoring";}?></a></li>
			<?php 
			if($nbSurveillant>0 || $nbSurveillantQualifie>0 || $nbSurveillantECQualif>0){
			?>
			<li style="border-bottom:1px dotted #988876;height:70px;"><a class="head" style="color:#988876;" href="<?php echo $HTTPServeur; ?>SODA/SurveillanceNonPlanifiee.php"><?php if($_SESSION["Langue"]=="FR"){echo "Surveillance non planifiée";}else{echo "Unscheduled monitoring";}?></a></li>
			<?php 
				//Vérifier Si la personne peut faire les surveillances de cette thématique
				$req="SELECT Id FROM soda_surveillant_theme WHERE Id_Surveillant=".$_SESSION['Id_Personne']." AND Id_Theme=8 ";
				$resultSurvTheme=mysqli_query($bdd,$req);
				$nbSurvTheme=mysqli_num_rows($resultSurvTheme);
				
				$req="SELECT Id FROM soda_theme WHERE Id=8 AND Id_Qualification IN (
					SELECT DISTINCT Id_Qualification_Parrainage 
					FROM new_competences_relation 
					WHERE (Evaluation IN ('L','X')
					AND Date_Debut<='".date('Y-m-d')."'
					AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01') 
					)
					AND Id_Qualification_Parrainage IN (SELECT Id_Qualification FROM soda_theme WHERE Id=8)
					AND Id_Personne=".$_SESSION['Id_Personne']."
				) ";
				$resultSurTheme= mysqli_query($bdd,$req);	
				$nbSurvThemeQualifie=mysqli_num_rows($resultSurTheme);
				
				if($nbSurvTheme>0 || $nbSurvThemeQualifie>0){
			?>
			<li style="border-bottom:1px dotted #988876;height:75px;"><a class="head" style="color:#988876;" href="<?php echo $HTTPServeur; ?>SODA/SurveillanceNonPlanifieeProcessus.php"><?php if($_SESSION["Langue"]=="FR"){echo "Surveillance PROCESSUS non planifiée";}else{echo "Unscheduled monitoring PROCESSUS";}?></a></li>
			<?php 
				}
			}
			?>
			<li style="border-bottom:1px dotted #988876;height:70px;"><a class="head" style="color:#988876;" href="<?php echo $HTTPServeur; ?>SODA/ConsulterSurveillances.php"><?php if($_SESSION["Langue"]=="FR"){echo "Consulter les surveillances";}else{echo "Consult the surveillances";}?></a></li>
		  </ul>
		<?php 
		}
		?>
   </ul>
</div>
<!-- menu-container -->
<!-- partial -->

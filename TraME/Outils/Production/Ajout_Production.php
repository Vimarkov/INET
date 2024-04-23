<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="Production.js?t=<?php echo time();?>"></script>
	<script type="text/javascript" src="../JS/date.js"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script>
		function FermerEtRecharger(){
			window.opener.location = "Production.php";
			window.close();
		}
		function datepick() {
			if (!Modernizr.inputtypes['date']) {
				$('input[type=date]').datepicker({
					dateFormat: 'dd/mm/yy'
				});
			}
		}
		function messageAC(reference,langue){
			var myRegEx=new RegExp(";","gm");
			var newref=reference.replace(myRegEx,"\n");
			if(langue=="EN"){
				alert('You must check the following deliverables : \n '+newref+' Go to the production tab to complete the checklist');
			}
			else{
				alert('Vous devez contrôler vos livrables suivants : \n '+newref+' Rendez-vous dans l\'onglet production pour compléter la check-list');
			}
		}
	</script>
	<link href="../../CSS/Demo_calendar_stylePROD.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
	<link href="../../CSS/Demo_calendar_jquery.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../JS/bootstrap.min.js"></script>
    <script type="text/javascript" src="../JS/prettify.js"></script>
    <script type="text/javascript" src="../JS/bootstrap-timepicker.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			$('#heureDebut').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: true,
				showMeridian: false,
				defaultTime: false
			});
			
			$('#heureFin').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: true,
				showMeridian: false,
				defaultTime: false
			});
			
			$('#heurePreparateur').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: true,
				showMeridian: false,
				defaultTime: false
			});
		});
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
Ecrire_Code_JS_Init_Date();
if($_POST){
	if($_POST['Mode']=="A" || $_POST['Mode']=="D"){
		$messageAC="";
		
		$Id_CL=0;
		$Niveau=0;
		$Delais=0;
		if($_POST['statutTravail']=="A VALIDER"){
			//Récupérer la CL de la tâche + niveau
			$req="SELECT Id_CL, NiveauControle,Delais FROM trame_tache WHERE Id=".$_POST['tache'];
			$result=mysqli_query($bdd,$req);
			$nbResulta=mysqli_num_rows($result);
			if ($nbResulta>0){
				$row=mysqli_fetch_array($result);
				$Id_CL=$row['Id_CL'];
				$Niveau=$row['NiveauControle'];
				$Delais=$row['Delais'];
			}
		}
		
		$Id_CLVersion=0;
		//Recherche de la version du CL
		$req="SELECT Id FROM trame_cl_version WHERE Id_CL=".$Id_CL." AND Valide=1 AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		if ($nbResulta>0){
			$row=mysqli_fetch_array($result);
			$Id_CLVersion=$row['Id'];
		}

		//Recherche le contenu de la version
		$req="SELECT Id FROM trame_cl_version_contenu WHERE Id_VersionCL=".$Id_CLVersion;
		$resultContenuVersion=mysqli_query($bdd,$req);
		$nbResultaContenuVersion=mysqli_num_rows($resultContenuVersion);
		
		//Sauvegarder WP utilisé
		$requete="UPDATE trame_acces SET Id_WP=".$_POST['wp']." WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_Personne=".$_SESSION['Id_PersonneTR'];
		$resultWP=mysqli_query($bdd,$requete);
		$attestation=0;
		if(isset($_POST['attestation'])){$attestation=1;}
		$tab = explode("\n",$_POST['reference']);
		foreach($tab as $reference){
			$reference= preg_replace("(\r\n|\n|\r)",'',$reference);
			if($reference<>""){
				$tempsPasse=0;
				$heurePrepa=$_POST['heurePreparateur'];
				if(strlen($heurePrepa)==7){$heurePrepa="0".$heurePrepa;}
				
				if($_POST['tempsPasse']<>""){$tempsPasse=$_POST['tempsPasse'];}
				$requete="INSERT INTO trame_travaileffectue (Id_Prestation,Id_Tache,Statut,Id_Preparateur,Id_WP,Designation,DatePreparateur,HeurePreparateur,DescriptionModification,StatutDelai,Attestation,NiveauControle,TempsPasse) ";
				$requete.="VALUES (".$_SESSION['Id_PrestationTR'].",".$_POST['tache'].",'".$_POST['statutTravail']."',".$_POST['preparateur'].",".$_POST['wp'].",'".addslashes($reference)."','".TrsfDate_($_POST['dateTravail'])."','".$heurePrepa."','".addslashes($_POST['commentaire'])."','".addslashes($_POST['statutDelais'])."',".$attestation.",".$Niveau.",".$tempsPasse.") ";
				$result=mysqli_query($bdd,$requete);
				$IdTravail = mysqli_insert_id($bdd);
				
				//Nombre de livrable réalisé dans le mois à ce niveau
				$req="SELECT Id FROM trame_travaileffectue WHERE Statut<>'EN COURS' AND NiveauControle=".$Niveau." AND Id_Prestation=".$_SESSION['Id_PrestationTR']." AND MONTH(DatePreparateur)=".date("m")." AND YEAR(DatePreparateur)=".date("Y")." ";
				$result=mysqli_query($bdd,$req);
				$nbLivrable=mysqli_num_rows($result);
				
				if($Id_CL>0 && $Id_CLVersion>0 && $nbResultaContenuVersion>0){
					if($Niveau>0 || isset($_POST['controle'])){
						//Nombre de livrable contrôlé du mois
						$req="SELECT Id FROM trame_controlecroise WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND NiveauControle=".$Niveau." AND MONTH(DateCreation)=".date("m")." AND YEAR(DateCreation)=".date("Y")." ";
						$result=mysqli_query($bdd,$req);
						$nbLivrableC=mysqli_num_rows($result);
						
						$nbEC=0;
						$nbLivEC=0;
						//Récupérer objectif de la CL ( Palier E/C)
						$req="SELECT Id, NbLivrable, Echantillonage FROM trame_controlecroise_objectif WHERE Niveau=".$Niveau." AND NbLivrable<=".$nbLivrable." ORDER BY NbLivrable DESC ";
						$result=mysqli_query($bdd,$req);
						$nbOjectif=mysqli_num_rows($result);
						if ($nbOjectif>0){
							$row=mysqli_fetch_array($result);
							$nbEC=$row['Echantillonage'];
							$nbLivEC=$row['NbLivrable'];
						}
						$nbSup=0;
						$nbLivSup=0;
						//Récupérer objectif de la CL ( Palier SUP)
						$req="SELECT Id, NbLivrable, Echantillonage FROM trame_controlecroise_objectif WHERE Niveau=".$Niveau." AND NbLivrable>=".$nbLivrable." ORDER BY NbLivrable ASC ";
						$result=mysqli_query($bdd,$req);
						$nbOjectif=mysqli_num_rows($result);
						if ($nbOjectif>0){
							$row=mysqli_fetch_array($result);
							$nbSup=$row['Echantillonage'];
							$nbLivSup=$row['NbLivrable'];
						}
						$ok=0;
						//PALIER E/C
						if($nbEC>$nbLivrableC){
							//A CONTROLER
							$messageAC.="- ".$reference.";";
							$ok=1;
						}
						//PALIER SUP
						elseif($nbLivrable>=$nbLivSup+$nbLivrableC-$nbSup){
							//A CONTROLER
							$messageAC.="- ".$reference.";";
							$ok=1;
						}
						if($ok==1 || isset($_POST['controle'])){
							//Remplacer le statut pour AUTO-CONTOLE
							$req="UPDATE trame_travaileffectue SET Statut='AC' WHERE Id=".$IdTravail;
							$result=mysqli_query($bdd,$req);

							//Ajouter une ligne dans trame_controlecroise
							$req="INSERT INTO trame_controlecroise (Id_TravailEffectue,Id_CLVersion,Id_Prestation,Id_Preparateur,NiveauControle,DateCreation) ";
							$req.="VALUES(".$IdTravail.",".$Id_CLVersion.",".$_SESSION['Id_PrestationTR'].",".$_POST['preparateur'].",".$Niveau.",'".date("Y-m-d")."') ";
							$result=mysqli_query($bdd,$req);
						}
					}
				}
				
				// UO mandatory et optional
				if($_POST['Mode']=="A"){
					$req="SELECT Id,Id_UO,Complexite,Relation,Id_DT,TypeTravail, ";
					$req.="(SELECT Temps FROM trame_tempsalloue WHERE trame_tempsalloue.Id_UO=trame_tache_uo.Id_UO AND ";
					$req.="trame_tempsalloue.Id_DomaineTechnique=trame_tache_uo.Id_DT AND ";
					$req.="trame_tempsalloue.Complexite=trame_tache_uo.Complexite AND ";
					$req.="trame_tempsalloue.TypeTravail=trame_tache_uo.TypeTravail LIMIT 1) AS TempsAlloue ";
					$req.="FROM trame_tache_uo WHERE Id_Tache=".$_POST['tache']." ";
					
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($rowUO=mysqli_fetch_array($result)){
							if($rowUO['Relation']=="Mandatory"){
								$TempsAlloue=0;
								if($rowUO['TempsAlloue']<>""){$TempsAlloue=$rowUO['TempsAlloue'];}
								$requete="INSERT INTO trame_travaileffectue_uo (Id_TravailEffectue,Id_UO,Complexite,Relation,Id_DomaineTechnique,TypeTravail,TravailFait,TempsAlloue) ";
								$requete.="VALUES (".$IdTravail.",".$rowUO['Id_UO'].",'".$rowUO['Complexite']."','".$rowUO['Relation']."',".$rowUO['Id_DT'].",'".$rowUO['TypeTravail']."',1,".$TempsAlloue.") ";
								$resultM=mysqli_query($bdd,$requete);
							}
							else{
								$TempsAlloue=0;
								$TravailFait=0;
								if($rowUO['TempsAlloue']<>""){$TempsAlloue=$rowUO['TempsAlloue'];}
								if(isset($_POST[$rowUO['Id']])){$TravailFait=1;}
								$requete="INSERT INTO trame_travaileffectue_uo (Id_TravailEffectue,Id_UO,Complexite,Relation,Id_DomaineTechnique,TypeTravail,TravailFait,TempsAlloue) ";
								$requete.="VALUES (".$IdTravail.",".$rowUO['Id_UO'].",'".$rowUO['Complexite']."','".$rowUO['Relation']."',".$rowUO['Id_DT'].",'".$rowUO['TypeTravail']."',".$TravailFait.",".$TempsAlloue.") ";
								$resultO=mysqli_query($bdd,$requete);
							}
						}
					}
				}
				else{
					$req="SELECT Id, Id_UO, Complexite, Relation, Id_DomaineTechnique AS Id_DT,TypeTravail,TravailFait,TempsAlloue 
						FROM trame_travaileffectue_uo 
						WHERE Id_TravailEffectue=".$_POST['id'];
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($rowUO=mysqli_fetch_array($result)){
							if($rowUO['Relation']=="Mandatory"){
								$TravailFait=1;
							}
							else{
								$TravailFait=0;
								if(isset($_POST[$rowUO['Id']])){$TravailFait=1;}
							}
							$requete="INSERT INTO trame_travaileffectue_uo (Id_TravailEffectue,Id_UO,Complexite,Relation,Id_DomaineTechnique,TypeTravail,TravailFait,TempsAlloue) ";
							$requete.="VALUES (".$IdTravail.",".$rowUO['Id_UO'].",'".$rowUO['Complexite']."','".$rowUO['Relation']."',".$rowUO['Id_DT'].",'".$rowUO['TypeTravail']."',".$TravailFait.",".$rowUO['TempsAlloue'].") ";
							$resultUO=mysqli_query($bdd,$requete);
						}
					}
				}
				
				// Infos complémentaires
				if($_POST['Mode']=="A"){
					$req="SELECT Id,Id AS Id_InfoTache, Info,Type FROM trame_tache_infocomplementaire WHERE Supprime=0 AND Id_Tache=".$_POST['tache'];
					
				}
				else{
					$req="SELECT Id, 
						(SELECT Id FROM trame_tache_infocomplementaire WHERE trame_tache_infocomplementaire.Id=trame_travaileffectue_info.Id_InfoTache) AS Id_InfoTache, ";
					$req.="(SELECT Type FROM trame_tache_infocomplementaire WHERE trame_tache_infocomplementaire.Id=trame_travaileffectue_info.Id_InfoTache) AS Type ";
					$req.="FROM trame_travaileffectue_info WHERE Id_TravailEffectue=".$_POST['id'];
				}
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					while($rowInfo=mysqli_fetch_array($result)){
						if(isset($_POST['Info_'.$rowInfo['Id']]) || $rowInfo['Type']=="Oui/Non"){
							$requete="INSERT INTO trame_travaileffectue_info (Id_TravailEffectue,Id_InfoTache,ValeurInfo) ";
							if($rowInfo['Type']=="Numerique"){
								$requete.="VALUES (".$IdTravail.",".$rowInfo['Id_InfoTache'].",'".$_POST['Info_'.$rowInfo['Id']]."') ";
							}
							elseif($rowInfo['Type']=="Date"){
								$requete.="VALUES (".$IdTravail.",".$rowInfo['Id_InfoTache'].",'".TrsfDate_($_POST['Info_'.$rowInfo['Id']])."') ";
							}
							elseif($rowInfo['Type']=="Oui/Non"){
								$checked=0;
								if(isset($_POST['Info_'.$rowInfo['Id']])){$checked=1;}
								$requete.="VALUES (".$IdTravail.",".$rowInfo['Id_InfoTache'].",'".$checked."') ";
							}
							else{
								$requete.="VALUES (".$IdTravail.",".$rowInfo['Id_InfoTache'].",'".addslashes($_POST['Info_'.$rowInfo['Id']])."') ";
							}
							$resultI=mysqli_query($bdd,$requete);
						}
					}
				}
			}
		}
		
		//Ajout du bloc planning si nécessaire 
		if(isset($_POST['blocPlanning'])){
			if($_POST['heureDebut']<>"" && $_POST['heureFin']<>""){
				$heureDebut=$_POST['heureDebut'];
				if(strlen($heureDebut)==7){$heureDebut="0".$heureDebut;}
				$heureFin=$_POST['heureFin'];
				if(strlen($heureFin)==7){$heureFin="0".$heureFin;}
				if($heureDebut<$heureFin){
					$req="INSERT INTO trame_planning (Id_Prestation,Id_Preparateur,Id_Tache,Id_WP,DateDebut,HeureDebut,HeureFin) ";
					$req.="VALUES (".$_SESSION['Id_PrestationTR'].",".$_POST['preparateur'].",".$_POST['tache'].",".$_POST['wp'].",'".TrsfDate_($_POST['dateTravail'])."','".$heureDebut."','".$heureFin."')";
					$result=mysqli_query($bdd,$req);
				}
			}
		}
		if($messageAC<>""){
			echo "<script type='text/javascript'>messageAC('".$messageAC."','".$_SESSION['Langue']."');</script>";
		}
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$attestation=0;
		$messageAC="";
		if(isset($_POST['attestation'])){$attestation=1;}
		
		$requete="UPDATE trame_travaileffectue SET ";
		$requete.="Statut='".addslashes($_POST['statutTravail'])."',";
		$tempsPasse=0;
		if($_POST['tempsPasse']<>""){$tempsPasse=$_POST['tempsPasse'];}
		$requete.="TempsPasse=".$tempsPasse.",";
		$requete.="Id_WP=".$_POST['wp'].",";
		$requete.="Id_Tache=".$_POST['tache'].",";
		$requete.="Designation='".addslashes($_POST['reference'])."',";
		$requete.="DatePreparateur='".TrsfDate_($_POST['dateTravail'])."',";
		$heurePrepa=$_POST['heurePreparateur'];
		if(strlen($heurePrepa)==7){$heurePrepa="0".$heurePrepa;}
		$requete.="HeurePreparateur='".$heurePrepa."',";
		if(isset($_POST['preparateur'])){
			$requete.="Id_Preparateur=".$_POST['preparateur'].",";
		}
		$requete.="DescriptionModification='".addslashes($_POST['commentaire'])."',";
		$requete.="StatutDelai='".addslashes($_POST['statutDelais'])."', ";
		$requete.="Attestation=".$attestation." ";
		$requete.=" WHERE Id=".$_POST['id'];
		$result=mysqli_query($bdd,$requete);
		
		if($_POST['OldTache']<>$_POST['tache']){	
			//Supprimer le controle
			if($_POST['statutTravail']=="AC" || $_POST['statutTravail']=="CONTROLE" || $_POST['statutTravail']=="REC"){
				$requete="UPDATE trame_travaileffectue SET ";
				$requete.="Statut='A VALIDER'";
				$requete.=" WHERE Id=".$_POST['id'];
				$result=mysqli_query($bdd,$requete);
			}
			
			$req="DELETE FROM trame_controlecroise_contenu WHERE Id_CC IN (SELECT Id FROM trame_controlecroise WHERE Id=".$_POST['id'].")";
			$result=mysqli_query($bdd,$req);
			
			$req="DELETE FROM trame_controlecroise WHERE Id_TravailEffectue=".$_POST['id'];
			$result=mysqli_query($bdd,$req);
			
			//Création du contrôle si nécessaire
			if($_POST['statutTravail']<>"EN COURS" && $_POST['statutTravail']<>"BLOQUE" && $_POST['statutTravail']<>"EN ATTENTE" && $_POST['statutTravail']<>"STAND BY"){
				$Id_CL=0;
				$Niveau=0;
				$Delais=0;

				//Récupérer la CL de la tâche + niveau
				$req="SELECT Id_CL, NiveauControle,Delais FROM trame_tache WHERE Id=".$_POST['tache'];
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$row=mysqli_fetch_array($result);
					$Id_CL=$row['Id_CL'];
					$Niveau=$row['NiveauControle'];
					$Delais=$row['Delais'];
				}
				$requete="UPDATE trame_travaileffectue SET ";
				$requete.="NiveauControle=".$Niveau."";
				$requete.=" WHERE Id=".$_POST['id'];
				$result=mysqli_query($bdd,$requete);
			
				$Id_CLVersion=0;
				//Recherche de la version du CL
				$req="SELECT Id FROM trame_cl_version WHERE Id_CL=".$Id_CL." AND Valide=1 AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$row=mysqli_fetch_array($result);
					$Id_CLVersion=$row['Id'];
				}

				//Recherche le contenu de la version
				$req="SELECT Id FROM trame_cl_version_contenu WHERE Id_VersionCL=".$Id_CLVersion;
				$resultContenuVersion=mysqli_query($bdd,$req);
				$nbResultaContenuVersion=mysqli_num_rows($resultContenuVersion);
				
				$annee=substr(TrsfDate_($_POST['dateTravail']),0,4);
				$mois=substr(TrsfDate_($_POST['dateTravail']),5,2);
				
				//Nombre de livrable réalisé dans le mois à ce niveau
				$req="SELECT Id FROM trame_travaileffectue WHERE Statut<>'EN COURS' AND NiveauControle=".$Niveau." AND Id_Prestation=".$_SESSION['Id_PrestationTR']." AND MONTH(DatePreparateur)=".date("m")." AND YEAR(DatePreparateur)=".date("Y")." ";
				$result=mysqli_query($bdd,$req);
				$nbLivrable=mysqli_num_rows($result);

				if($Id_CL>0 && $Id_CLVersion>0 && $nbResultaContenuVersion>0){
					if($Niveau>0 || isset($_POST['controle'])){
						//Nombre de livrable contrôlé du mois
						$req="SELECT Id FROM trame_controlecroise WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND NiveauControle=".$Niveau." AND MONTH(DateCreation)=".date("m")." AND YEAR(DateCreation)=".date("Y")." ";
						$result=mysqli_query($bdd,$req);
						$nbLivrableC=mysqli_num_rows($result);
						
						$nbEC=0;
						$nbLivEC=0;
						//Récupérer objectif de la CL ( Palier E/C)
						$req="SELECT Id, NbLivrable, Echantillonage FROM trame_controlecroise_objectif WHERE Niveau=".$Niveau." AND NbLivrable<=".$nbLivrable." ORDER BY NbLivrable DESC ";
						$result=mysqli_query($bdd,$req);
						$nbOjectif=mysqli_num_rows($result);
						if ($nbOjectif>0){
							$row=mysqli_fetch_array($result);
							$nbEC=$row['Echantillonage'];
							$nbLivEC=$row['NbLivrable'];
						}
						$nbSup=0;
						$nbLivSup=0;
						//Récupérer objectif de la CL ( Palier SUP)
						$req="SELECT Id, NbLivrable, Echantillonage FROM trame_controlecroise_objectif WHERE Niveau=".$Niveau." AND NbLivrable>=".$nbLivrable." ORDER BY NbLivrable ASC ";
						$result=mysqli_query($bdd,$req);
						$nbOjectif=mysqli_num_rows($result);
						if ($nbOjectif>0){
							$row=mysqli_fetch_array($result);
							$nbSup=$row['Echantillonage'];
							$nbLivSup=$row['NbLivrable'];
						}
						$ok=0;
						//PALIER E/C
						if($nbEC>$nbLivrableC){
							//A CONTROLER
							$messageAC.="- ".$_POST['reference'].";";
							$ok=1;
						}
						//PALIER SUP
						elseif($nbLivrable>=$nbLivSup+$nbLivrableC-$nbSup){
							//A CONTROLER
							$messageAC.="- ".$_POST['reference'].";";
							$ok=1;
						}
						if($ok==1 || isset($_POST['controle'])){
							//Remplacer le statut pour AUTO-CONTOLE
							$req="UPDATE trame_travaileffectue SET Statut='AC' WHERE Id=".$_POST['id'];
							$result=mysqli_query($bdd,$req);
							
							$preparateur=$_POST['IdPreparateur'];
							if(isset($_POST['preparateur'])){$preparateur=$_POST['preparateur'];}
							if($preparateur=="" || $preparateur==0){
								$preparateur=$_SESSION['Id_PersonneTR'];
							}
							//Ajouter une ligne dans trame_controlecroise
							$req="INSERT INTO trame_controlecroise (Id_TravailEffectue,Id_CLVersion,Id_Prestation,Id_Preparateur,NiveauControle,DateCreation) ";
							$req.="VALUES(".$_POST['id'].",".$Id_CLVersion.",".$_SESSION['Id_PrestationTR'].",".$preparateur.",".$Niveau.",'".date("Y-m-d")."') ";
							$result=mysqli_query($bdd,$req);
						}
					}
				}
			}
		}
		if($_POST['OldTache']==$_POST['tache'] && $_POST['ModificationTache']==""){
			// UO optional (les mandatory ne change pas)
			$req="SELECT Id FROM trame_travaileffectue_uo WHERE Relation='Optional' AND Id_TravailEffectue=".$_POST['id'];
			$result=mysqli_query($bdd,$req);
			$nbResulta=mysqli_num_rows($result);
			if ($nbResulta>0){
				while($rowUO=mysqli_fetch_array($result)){
					$TravailFait=0;
					if(isset($_POST[$rowUO['Id']])){$TravailFait=1;}
					$requete="UPDATE trame_travaileffectue_uo SET TravailFait=".$TravailFait." WHERE Id=".$rowUO['Id'];
					$resultO=mysqli_query($bdd,$requete);
				}
			}
			
			// Infos complémentaires
			$req="SELECT Id, ";
			$req.="(SELECT Type FROM trame_tache_infocomplementaire WHERE trame_tache_infocomplementaire.Id=trame_travaileffectue_info.Id_InfoTache) AS Type ";
			$req.="FROM trame_travaileffectue_info WHERE Id_TravailEffectue=".$_POST['id'];
			$result=mysqli_query($bdd,$req);
			$nbResulta=mysqli_num_rows($result);
			if ($nbResulta>0){
				while($rowInfo=mysqli_fetch_array($result)){
					if(isset($_POST['Info_'.$rowInfo['Id']]) || $rowInfo['Type']=="Oui/Non"){
						$requete="UPDATE trame_travaileffectue_info SET ValeurInfo= ";
						if($rowInfo['Type']=="Numerique"){
							$requete.="'".$_POST['Info_'.$rowInfo['Id']]."' ";
						}
						elseif($rowInfo['Type']=="Date"){
							$requete.="'".TrsfDate_($_POST['Info_'.$rowInfo['Id']])."' ";
						}
						elseif($rowInfo['Type']=="Oui/Non"){
							$checked=0;
							if(isset($_POST['Info_'.$rowInfo['Id']])){$checked=1;}
							$requete.="'".$checked."' ";
						}
						else{
							$requete.="'".$_POST['Info_'.$rowInfo['Id']]."' ";
						}
						$requete.="WHERE Id=".$rowInfo['Id'];
						$resultI=mysqli_query($bdd,$requete);
					}
				}
			}
			
			if((($_POST['OldStatut']=="EN COURS" || $_POST['OldStatut']=="BLOQUE" || $_POST['OldStatut']=="EN ATTENTE" || $_POST['OldStatut']=="STAND BY") && $_POST['statutTravail']=="A VALIDER") || isset($_POST['controle'])){
				// CONTROLE
				$Id_CL=0;
				$Niveau=0;
				$Delais=0;

				//Récupérer la CL de la tâche + niveau
				$req="SELECT Id_CL, NiveauControle,Delais FROM trame_tache WHERE Id=".$_POST['tache'];
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$row=mysqli_fetch_array($result);
					$Id_CL=$row['Id_CL'];
					$Niveau=$row['NiveauControle'];
					$Delais=$row['Delais'];
				}
				$requete="UPDATE trame_travaileffectue SET ";
				$requete.="NiveauControle=".$Niveau."";
				$requete.=" WHERE Id=".$_POST['id'];
				$result=mysqli_query($bdd,$requete);
			
				$Id_CLVersion=0;
				//Recherche de la version du CL
				$req="SELECT Id FROM trame_cl_version WHERE Id_CL=".$Id_CL." AND Valide=1 AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$row=mysqli_fetch_array($result);
					$Id_CLVersion=$row['Id'];
				}

				//Recherche le contenu de la version
				$req="SELECT Id FROM trame_cl_version_contenu WHERE Id_VersionCL=".$Id_CLVersion;
				$resultContenuVersion=mysqli_query($bdd,$req);
				$nbResultaContenuVersion=mysqli_num_rows($resultContenuVersion);
				
				$annee=substr(TrsfDate_($_POST['dateTravail']),0,4);
				$mois=substr(TrsfDate_($_POST['dateTravail']),5,2);
				
				//Nombre de livrable réalisé dans le mois à ce niveau
				$req="SELECT Id FROM trame_travaileffectue WHERE Statut<>'EN COURS' AND NiveauControle=".$Niveau." AND Id_Prestation=".$_SESSION['Id_PrestationTR']." AND MONTH(DatePreparateur)=".date("m")." AND YEAR(DatePreparateur)=".date("Y")." ";
				$result=mysqli_query($bdd,$req);
				$nbLivrable=mysqli_num_rows($result);
				if($Id_CL>0 && $Id_CLVersion>0 && $nbResultaContenuVersion>0){
					if($Niveau>0 || isset($_POST['controle'])){
						//Nombre de livrable contrôlé du mois
						$req="SELECT Id FROM trame_controlecroise WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND NiveauControle=".$Niveau." AND MONTH(DateCreation)=".date("m")." AND YEAR(DateCreation)=".date("Y")." ";
						$result=mysqli_query($bdd,$req);
						$nbLivrableC=mysqli_num_rows($result);
						$nbEC=0;
						$nbLivEC=0;
						//Récupérer objectif de la CL ( Palier E/C)
						$req="SELECT Id, NbLivrable, Echantillonage FROM trame_controlecroise_objectif WHERE Niveau=".$Niveau." AND NbLivrable<=".$nbLivrable." ORDER BY NbLivrable DESC ";
						$result=mysqli_query($bdd,$req);
						$nbOjectif=mysqli_num_rows($result);
						if ($nbOjectif>0){
							$row=mysqli_fetch_array($result);
							$nbEC=$row['Echantillonage'];
							$nbLivEC=$row['NbLivrable'];
						}
						$nbSup=0;
						$nbLivSup=0;
						//Récupérer objectif de la CL ( Palier SUP)
						$req="SELECT Id, NbLivrable, Echantillonage FROM trame_controlecroise_objectif WHERE Niveau=".$Niveau." AND NbLivrable>=".$nbLivrable." ORDER BY NbLivrable ASC ";
						$result=mysqli_query($bdd,$req);
						$nbOjectif=mysqli_num_rows($result);
						if ($nbOjectif>0){
							$row=mysqli_fetch_array($result);
							$nbSup=$row['Echantillonage'];
							$nbLivSup=$row['NbLivrable'];
						}
						$ok=0;
						//echo $nbEC."<".$nbLivrableC."<br>";
						//echo $nbLivrable.">=".$nbLivSup."+".$nbLivrableC."-".$nbSup;
						//PALIER E/C
						if($nbEC>$nbLivrableC){
							//A CONTROLER
							$messageAC.="- ".$_POST['reference'].";";
							$ok=1;
						}
						//PALIER SUP
						elseif($nbLivrable>=$nbLivSup+$nbLivrableC-$nbSup){
							//A CONTROLER
							$messageAC.="- ".$_POST['reference'].";";
							$ok=1;
						}
						if($ok==1 || isset($_POST['controle'])){
							//Remplacer le statut pour AUTO-CONTOLE
							$req="UPDATE trame_travaileffectue SET Statut='AC' WHERE Id=".$_POST['id'];
							$result=mysqli_query($bdd,$req);
							
							$preparateur=$_POST['IdPreparateur'];
							if(isset($_POST['preparateur'])){$preparateur=$_POST['preparateur'];}
							if($preparateur=="" || $preparateur==0){
								$preparateur=$_SESSION['Id_PersonneTR'];
							}
							//Ajouter une ligne dans trame_controlecroise
							$req="INSERT INTO trame_controlecroise (Id_TravailEffectue,Id_CLVersion,Id_Prestation,Id_Preparateur,NiveauControle,DateCreation) ";
							$req.="VALUES(".$_POST['id'].",".$Id_CLVersion.",".$_SESSION['Id_PrestationTR'].",".$preparateur.",".$Niveau.",'".date("Y-m-d")."') ";
							$result=mysqli_query($bdd,$req);
						}
					}
				}
			}
		}
		else{
			//Suppression des UO
			$requete="DELETE FROM trame_travaileffectue_uo WHERE Id_TravailEffectue=".$_POST['id'];
			$resultO=mysqli_query($bdd,$requete);
			
			// UO mandatory et optional
			$req="SELECT Id,Id_UO,Complexite,Relation,Id_DT,TypeTravail, ";
			$req.="(SELECT Temps FROM trame_tempsalloue WHERE trame_tempsalloue.Id_UO=trame_tache_uo.Id_UO AND ";
			$req.="trame_tempsalloue.Id_DomaineTechnique=trame_tache_uo.Id_DT AND ";
			$req.="trame_tempsalloue.Complexite=trame_tache_uo.Complexite AND ";
			$req.="trame_tempsalloue.TypeTravail=trame_tache_uo.TypeTravail LIMIT 1) AS TempsAlloue ";
			$req.="FROM trame_tache_uo WHERE Id_Tache=".$_POST['tache']." ";
			$result=mysqli_query($bdd,$req);
			$nbResulta=mysqli_num_rows($result);
			if ($nbResulta>0){
				while($rowUO=mysqli_fetch_array($result)){
					if($rowUO['Relation']=="Mandatory"){
						$TempsAlloue=0;
						if($rowUO['TempsAlloue']<>""){$TempsAlloue=$rowUO['TempsAlloue'];}
						$requete="INSERT INTO trame_travaileffectue_uo (Id_TravailEffectue,Id_UO,Complexite,Relation,Id_DomaineTechnique,TypeTravail,TravailFait,TempsAlloue) ";
						$requete.="VALUES (".$_POST['id'].",".$rowUO['Id_UO'].",'".$rowUO['Complexite']."','".$rowUO['Relation']."',".$rowUO['Id_DT'].",'".$rowUO['TypeTravail']."',1,".$TempsAlloue.") ";
						$resultM=mysqli_query($bdd,$requete);
					}
					else{
						$TempsAlloue=0;
						$TravailFait=0;
						if($rowUO['TempsAlloue']<>""){$TempsAlloue=$rowUO['TempsAlloue'];}
						if(isset($_POST[$rowUO['Id']])){$TravailFait=1;}
						$requete="INSERT INTO trame_travaileffectue_uo (Id_TravailEffectue,Id_UO,Complexite,Relation,Id_DomaineTechnique,TypeTravail,TravailFait,TempsAlloue) ";
						$requete.="VALUES (".$_POST['id'].",".$rowUO['Id_UO'].",'".$rowUO['Complexite']."','".$rowUO['Relation']."',".$rowUO['Id_DT'].",'".$rowUO['TypeTravail']."',".$TravailFait.",".$TempsAlloue.") ";
						$resultO=mysqli_query($bdd,$requete);
					}
				}
			}
			
			//Suppression des infos
			$requete="DELETE FROM trame_travaileffectue_info WHERE Id_TravailEffectue=".$_POST['id'];
			$resultO=mysqli_query($bdd,$requete);
			
			// Infos complémentaires
			$req="SELECT Id,Info,Type FROM trame_tache_infocomplementaire WHERE Supprime=0 AND Id_Tache=".$_POST['tache'];
			$result=mysqli_query($bdd,$req);
			$nbResulta=mysqli_num_rows($result);
			if ($nbResulta>0){
				while($rowInfo=mysqli_fetch_array($result)){
					if(isset($_POST['Info_'.$rowInfo['Id']]) || $rowInfo['Type']=="Oui/Non"){
						$requete="INSERT INTO trame_travaileffectue_info (Id_TravailEffectue,Id_InfoTache,ValeurInfo) ";
						if($rowInfo['Type']=="Numerique"){
							$requete.="VALUES (".$_POST['id'].",".$rowInfo['Id'].",'".$_POST['Info_'.$rowInfo['Id']]."') ";
						}
						elseif($rowInfo['Type']=="Date"){
							$requete.="VALUES (".$_POST['id'].",".$rowInfo['Id'].",'".TrsfDate_($_POST['Info_'.$rowInfo['Id']])."') ";
						}
						elseif($rowInfo['Type']=="Oui/Non"){
							$checked=0;
							if(isset($_POST['Info_'.$rowInfo['Id']])){$checked=1;}
							$requete.="VALUES (".$_POST['id'].",".$rowInfo['Id'].",'".$checked."') ";
						}
						else{
							$requete.="VALUES (".$_POST['id'].",".$rowInfo['Id'].",'".$_POST['Info_'.$rowInfo['Id']]."') ";
						}
						$resultI=mysqli_query($bdd,$requete);
					}
				}
			}
		}
		
		//Ajout du bloc planning si nécessaire 
		if(isset($_POST['blocPlanning'])){
			if($_POST['heureDebut']<>"" && $_POST['heureFin']<>""){
				$heureDebut=$_POST['heureDebut'];
				if(strlen($heureDebut)==7){$heureDebut="0".$heureDebut;}
				$heureFin=$_POST['heureFin'];
				if(strlen($heureFin)==7){$heureFin="0".$heureFin;}
				if($heureDebut<$heureFin){
					$preparateur=$_POST['IdPreparateur'];
					if(isset($_POST['preparateur'])){$preparateur=$_POST['preparateur'];}
					$req="INSERT INTO trame_planning (Id_Prestation,Id_Preparateur,Id_Tache,Id_WP,DateDebut,HeureDebut,HeureFin) ";
					$req.="VALUES (".$_SESSION['Id_PrestationTR'].",".$preparateur.",".$_POST['tache'].",".$_POST['wp'].",'".TrsfDate_($_POST['dateTravail'])."','".$_POST['heureDebut']."','".$_POST['heureFin']."')";
					$req.="VALUES (".$_SESSION['Id_PrestationTR'].",".$preparateur.",".$_POST['tache'].",".$_POST['wp'].",'".TrsfDate_($_POST['dateTravail'])."','".$heureDebut."','".$heureFin."')";
					$result=mysqli_query($bdd,$req);
				}
			}
		}
		
		if($messageAC<>""){
			echo "<script type='text/javascript'>messageAC('".$messageAC."','".$_SESSION['Langue']."');</script>";
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	$timestamp_debut = microtime(true);
	//Mode ajout ou modification
	$Id=$_GET['Id'];
	if($_GET['Mode']=="A" || $_GET['Mode']=="M" || $_GET['Mode']=="D"){
		$req="SELECT DateFacturation FROM trame_facturation WHERE Id_Prestation=".$_SESSION['Id_PrestationTR'];
		$resultFactu=mysqli_query($bdd,$req);
		$nbResultaFactu=mysqli_num_rows($resultFactu);
		if($nbResultaFactu>0){
			$LigneFactu=mysqli_fetch_array($resultFactu);
		}
		
		$read="";
		$disabled="";
		$disabled2="";
		$TypeDate="date";
		if($_GET['Mode2']=="L"){
			$read="readonly='readonly'";
			$disabled="disabled='disabled'";
			$TypeDate="texte";
		}
		if($_GET['Mode2']=="L" || $_GET['Mode2']=="M"){
			$disabled2="disabled='disabled'";
		}
		if($_GET['Id']!='0')
		{
			$req="SELECT Id, Id_Tache,Statut,Id_Preparateur,Id_WP,Designation,DatePreparateur,HeurePreparateur,DescriptionModification,StatutDelai,TempsPasse, ";
			$req.="(SELECT Delais FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS Delais,
					(SELECT Id_FamilleTache FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS Id_FamilleTache,
					(SELECT COUNT(trame_controlecroise.Id) FROM trame_controlecroise WHERE Id_TravailEffectue=trame_travaileffectue.Id) AS NbControle, ";
			$req.="(SELECT CritereOTD FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS CritereOTD, Attestation ";
			$req.="FROM trame_travaileffectue WHERE Id=".$_GET['Id'];
			$result=mysqli_query($bdd,$req);
			$Ligne=mysqli_fetch_array($result);
			if($_GET['Mode2']=="M"){
				if((substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],4,1)=='1') && $Ligne['Statut']=="EN COURS"){$disabled2="";}
			}
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Production.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
		<input type="hidden" name="Mode" id="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Droit" id="Droit" value="<?php echo $_SESSION['DroitTR']; ?>">
		<input type="hidden" name="OldDateTravail" id="OldDateTravail" value="<?php if($_GET['Mode']=="M"){echo AfficheDateFR($Ligne['DatePreparateur']);} ?>">
		<input type="hidden" name="DateFacturation" id="DateFacturation" value="<?php if($nbResultaFactu>0){echo $LigneFactu['DateFacturation'];} ?>">
		<input type="hidden" name="OldStatutDelais" id="OldStatutDelais" value="<?php if($_GET['Mode']=="M"){echo $Ligne['StatutDelai'];} ?>">
		<input type="hidden" name="OldStatut" id="OldStatut" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Statut'];} ?>">
		<input type="hidden" name="id" value="<?php if($_GET['Mode']=="M" || $_GET['Mode']=="D"){echo $Ligne['Id'];}?>">
		<input type="hidden" name="OldTache" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id_Tache'];}?>">
		<input type="hidden" name="NbControle" id="NbControle" value="<?php if($_GET['Mode']=="M"){echo $Ligne['NbControle'];}else{echo "0";} ?>">
		<input type="hidden" name="ModificationTache" id="ModificationTache" value="">
		<input type="hidden" name="IdPreparateur" id="IdPreparateur" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id_Preparateur'];}?>">
		<input type="hidden" name="IdPersonne" id="IdPersonne" value="<?php echo $_SESSION['Id_PersonneTR'];?>">
		<table width="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Manufacturing engineer";}else{echo "Préparateur";} ?></td>
				<td colspan="10">
					<select id="preparateur" name="preparateur" <?php echo $disabled2; ?>>
						
						<?php
							
							if($_GET['Mode']=="M"){
								if($disabled2==""){
									$req="SELECT new_rh_etatcivil.Id,new_rh_etatcivil.Nom,new_rh_etatcivil.Prenom ";
									$req.="FROM trame_acces LEFT JOIN new_rh_etatcivil ON trame_acces.Id_Personne=new_rh_etatcivil.Id ";
									$req.="WHERE  (SUBSTR(trame_acces.Droit,1,1)=1 || SUBSTR(trame_acces.Droit,2,1)=1 
										|| SUBSTR(trame_acces.Droit,3,1)=1 || SUBSTR(trame_acces.Droit,5,1)=1)
										AND trame_acces.Id_Prestation=".$_SESSION['Id_PrestationTR']." AND new_rh_etatcivil.LoginTrame<>'' ORDER BY Nom, Prenom;";
								}
								else{
									$req="SELECT new_rh_etatcivil.Id,new_rh_etatcivil.Nom,new_rh_etatcivil.Prenom ";
									$req.="FROM new_rh_etatcivil ";
									$req.="WHERE new_rh_etatcivil.Id=".$Ligne['Id_Preparateur'].";";
								}
							}
							else{
								if(substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],1,1)=='1' ){
									$req="SELECT new_rh_etatcivil.Id,new_rh_etatcivil.Nom,new_rh_etatcivil.Prenom ";
									$req.="FROM trame_acces LEFT JOIN new_rh_etatcivil ON trame_acces.Id_Personne=new_rh_etatcivil.Id ";
									$req.="WHERE (SUBSTR(trame_acces.Droit,1,1)=1 || SUBSTR(trame_acces.Droit,2,1)=1 
										|| SUBSTR(trame_acces.Droit,3,1)=1 || SUBSTR(trame_acces.Droit,5,1)=1)
										AND trame_acces.Id_Prestation=".$_SESSION['Id_PrestationTR']." AND new_rh_etatcivil.LoginTrame<>'' ORDER BY Nom, Prenom;";
								}
								else{
									$req="SELECT new_rh_etatcivil.Id,new_rh_etatcivil.Nom,new_rh_etatcivil.Prenom ";
									$req.="FROM new_rh_etatcivil ";
									$req.="WHERE new_rh_etatcivil.Id=".$_SESSION['Id_PersonneTR'].";";
								}
							}
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){ 
								while($rowPrepa=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="M"){
										if($Ligne['Id_Preparateur']==$rowPrepa['Id']){$selected="selected";}
									}
									else{
										if($_SESSION['Id_PersonneTR']==$rowPrepa['Id']){$selected="selected";}
									}
									echo "<option value='".$rowPrepa['Id']."' ".$selected.">".$rowPrepa['Nom']." ".$rowPrepa['Prenom']."</option>";
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Workpackage";}else{echo "Workpackage";} ?></td>
				<td colspan="10">
					<select id="wp" name="wp" <?php echo $disabled; ?> onchange="RechargerTache('<?php echo $_SESSION['Langue']; ?>')">
						<?php
							$leWP=0;
							if($_GET['Mode']=="A"){
								$requete="SELECT Id_WP FROM trame_acces WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_Personne=".$_SESSION['Id_PersonneTR'];
								$resultWP=mysqli_query($bdd,$requete);
								$nbResulta=mysqli_num_rows($resultWP);
								if ($nbResulta>0){
									$rowWP=mysqli_fetch_array($resultWP);
									$leWP=$rowWP['Id_WP'];
								}
							}
							elseif($_GET['Mode']=="D"){
								$leWP=$Ligne['Id_WP'];
							}
							echo"<option value='0'></option>";
							
							$req="SELECT Id, Libelle, Supprime, Actif FROM trame_wp WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowWP=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="M" || $_GET['Mode']=="D"){
										if($rowWP['Id']==$Ligne['Id_WP']){$selected="selected";}
										if(($rowWP['Supprime']==false && $rowWP['Actif']==false) || $rowWP['Id']==$Ligne['Id_WP'] ){
											echo "<option value='".$rowWP['Id']."' ".$selected.">".stripslashes(str_replace("\\","",$rowWP['Libelle']))."</option>";
										}
									}
									else{
										if($leWP==$rowWP['Id']){$selected="selected";}
										if($rowWP['Supprime']==false && $rowWP['Actif']==false){
											echo "<option value='".$rowWP['Id']."' ".$selected.">".stripslashes(str_replace("\\","",$rowWP['Libelle']))."</option>";
										}
									}
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Task family";}else{echo "Famille de tâche";} ?></td>
				<td colspan="10">
					<select id="famille" name="famille" <?php echo $disabled; ?> onchange="RechargerTache('<?php echo $_SESSION['Langue']; ?>')">
						<?php
							echo"<option value='0'></option>";
							$req="SELECT Id, Libelle, Supprime FROM trame_familletache WHERE Id_Prestation=".$_SESSION['Id_PrestationTR'].";";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowFamille=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="M" || $_GET['Mode']=="V"){
										if($rowFamille['Id']==$Ligne['Id_FamilleTache']){$selected="selected";}
									}
									if($rowFamille['Supprime']==false  || $rowFamille['Id']==$Ligne['Id_Categorie']){
										echo "<option value='".$rowFamille['Id']."' ".$selected.">".stripslashes(str_replace("\\","",$rowFamille['Libelle']))."</option>";
									}
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Task";}else{echo "Tâche";} ?></td>
				<td colspan="10">
					<div id="divTache">
						<select id="tache" name="tache" <?php echo $disabled; ?> onchange="RechargerInfos('<?php echo $_SESSION['Langue']; ?>')">
							<?php
								$req="SELECT Id, NiveauControle ";
								$req.="FROM trame_tache ";
								$req.="WHERE trame_tache.Id_Prestation=".$_SESSION['Id_PrestationTR']."";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									$i=0;
									while($rowTache=mysqli_fetch_array($result)){
										echo "<script>Liste_Tache[".$i."]= Array(\"".$rowTache['Id']."\",\"".$rowTache['NiveauControle']."\")</script>";
										$i++;
									}
								}
								$req="SELECT DISTINCT trame_tache.Id, trame_tache.Libelle,trame_tache.Delais, trame_tache_wp.Id_WP, trame_tache.Supprime,trame_tache.Id_FamilleTache,trame_tache.CritereOTD, trame_tache_wp.Supprime AS SupprTacheWP ";
								$req.="FROM trame_tache_wp LEFT JOIN trame_tache ON trame_tache_wp.Id_Tache=trame_tache.Id ";
								$req.="WHERE trame_tache.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									$nb=0;
									$i=0;
									while($rowTache=mysqli_fetch_array($result)){
										if($_GET['Mode']=="M" || $_GET['Mode']=="D"){
											if($rowTache['Id_WP']==$Ligne['Id_WP']){
												$nb++;
												$selected="";
												if($rowTache['Id']==$Ligne['Id_Tache']){$selected="selected";}
												if(($rowTache['Supprime']==false  && $rowTache['SupprTacheWP']==false)  || $rowTache['Id']==$Ligne['Id_Tache']){
													echo "<option value='".$rowTache['Id']."' ".$selected.">".stripslashes(str_replace("\\","",$rowTache['Libelle']))."</option>";
												}
											}
										}
										elseif($_GET['Mode']=="A"){
											if($leWP>0){
												if($rowTache['Id_WP']==$leWP){
													$nb++;
													$selected="";
													if($rowTache['Supprime']==false && $rowTache['SupprTacheWP']==false){
														echo "<option value='".$rowTache['Id']."' ".$selected.">".stripslashes(str_replace("\\","",$rowTache['Libelle']))."</option>";
													}
												}
											}
										}
										echo "<script>Liste_Tache_WP[".$i."]= Array(\"".$rowTache['Id']."\",\"".$rowTache['Id_WP']."\",\"".$rowTache['Supprime']."\",\"".addslashes($rowTache['Libelle'])."\",\"".$rowTache['Id_FamilleTache']."\",\"".$rowTache['Delais']."\",\"".$rowTache['CritereOTD']."\",\"".$rowTache['SupprTacheWP']."\")</script>";
										$i++;
									}
									if($nb==0){echo "<option value='0'></option>";}
								}
								else{
									echo "<option value='0'></option>";
								}
							?>
						</select>
					</div>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width='10%' class="Libelle" valign="top"><?php if($_SESSION['Langue']=="EN"){echo "Reference(s) (1 reference / line)";}else{echo "Référence(s) (1 référence / ligne)";} ?></td>
			</tr>
			<tr>
				<td width='10%' valign='top'>
					<textarea id="reference" name="reference" rows=20 cols=25 <?php echo $read; ?> style="resize:none;"><?php if($_GET['Mode']=="M"){echo $Ligne['Designation'];} ?></textarea>
				</td>
				<td width='10%' valign='top'>
					<table>
						<tr>
							<td>
								<table>
									<tr>
										<?php
											$Hover="id='hoverCritereOTD'";
											$infoBulle ="";
											if($_GET['Mode']=="M" || $_GET['Mode']=="V"){
												if($_SESSION['Langue']=="EN"){
													$infoBulle = "\n<span> Criteria OTD : ".stripslashes($Ligne['CritereOTD'])."</span>\n";
												}
												else{
													$infoBulle = "\n<span> Critère OTD : ".stripslashes($Ligne['CritereOTD'])."</span>\n";
												}
											}
										?>
										<td width="10%" class="Libelle" <?php echo $Hover;?>><?php if($_SESSION['Langue']=="EN"){echo $infoBulle."Deadline ";}else{echo $infoBulle."Statut du délais ";} ?></td>
										<td width="15%" align="left">
											<div id="leDelais">
												<select id="statutDelais" <?php echo $disabled; ?> name="statutDelais">
													<?php 
													if($_GET['Mode']=="M" || $_GET['Mode']=="D"){
														if($Ligne['Delais']==0){ 
													?>
														<option value="N/A" <?php if($_GET['Mode']=="M"){if($Ligne['StatutDelai']=="N/A"){echo "selected";}} ?>>N/A</option>
													<?php 
														} 
														else{ 
													?>
														<option value="OK" <?php if($_GET['Mode']=="M"){if($Ligne['StatutDelai']=="OK"){echo "selected";}} ?>>OK</option>
														<option value="KO" <?php if($_GET['Mode']=="M"){if($Ligne['StatutDelai']=="KO"){echo "selected";}} ?>>KO</option>
													<?php 
														} 
													}
													?>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td class="Libelle" >
										<?php 
											if($_SESSION['Langue']=="EN"){
												echo "Work status ";
											}
											else{
												echo "Statut du travail ";
											} 
										?>
										</td>
										<td>
											<select id="statutTravail" <?php echo $disabled; ?> name="statutTravail" onchange="AfficherTDControle()">
												<?php if($_GET['Mode']=="M"){
													if($Ligne['Statut']=="AC"){
														?>
															<option value="AC" selected><?php if($_SESSION['Langue']=="EN"){echo "AUTO CONTROL";}else{echo "AUTO-CONTROLE";} ?></option>
														<?php
													}
													elseif($Ligne['Statut']=="CONTROLE"){
														?>
															<option value="CONTROLE" selected><?php if($_SESSION['Langue']=="EN"){echo "CONTROL";}else{echo "CONTROLE";}?></option>
														<?php
													}
													elseif($Ligne['Statut']=="REC"){
														?>
															<option value="REC" selected><?php if($_SESSION['Langue']=="EN"){echo "CONTROL AGAIN";}else{echo "RECONTROLE";}?></option>
														<?php
													}
													else{
												?>
													<option value="A VALIDER" <?php if($_GET['Mode']=="M"){if($Ligne['Statut']=="A VALIDER"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "TO BE VALIDATED";}else{echo "A VALIDER";}?></option>
													<option value="EN COURS" <?php if($_GET['Mode']=="M"){if($Ligne['Statut']=="EN COURS"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "IN PROGRESS";}else{echo "EN COURS";}?></option>
													<option value="BLOQUE" <?php if($_GET['Mode']=="M"){if($Ligne['Statut']=="BLOQUE"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "BLOCKED";}else{echo "BLOQUE";}?></option>
													<option value="EN ATTENTE" <?php if($_GET['Mode']=="M"){if($Ligne['Statut']=="EN ATTENTE"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "WAITING";}else{echo "EN ATTENTE";}?></option>
													<option value="STAND BY" <?php if($_GET['Mode']=="M"){if($Ligne['Statut']=="STAND BY"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "STAND BY";}else{echo "STAND BY";}?></option>
												<?php
													}
												}
												else{
												?>
													<option value=""></option>
													<option value="A VALIDER" <?php if($_GET['Mode']=="M"){if($Ligne['Statut']=="A VALIDER"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "TO BE VALIDATED";}else{echo "A VALIDER";}?></option>
													<option value="EN COURS" <?php if($_GET['Mode']=="M"){if($Ligne['Statut']=="EN COURS"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "IN PROGRESS";}else{echo "EN COURS";}?></option>
													<option value="BLOQUE" <?php if($_GET['Mode']=="M"){if($Ligne['Statut']=="BLOQUE"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "BLOCKED";}else{echo "BLOQUE";}?></option>
													<option value="EN ATTENTE" <?php if($_GET['Mode']=="M"){if($Ligne['Statut']=="EN ATTENTE"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "WAITING";}else{echo "EN ATTENTE";}?></option>
													<option value="STAND BY" <?php if($_GET['Mode']=="M"){if($Ligne['Statut']=="STAND BY"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "STAND BY";}else{echo "STAND BY";}?></option>
												<?php
													}
												?>
											</select>
										</td>
									</tr>
									<?php
										$display="style=display:none;";
										$reqPlanning="SELECT Id FROM trame_prestation WHERE Planning=1 AND Id=".$_SESSION['Id_PrestationTR'];
										$resultPlanning=mysqli_query($bdd,$reqPlanning);
										$nbResultaPlanning=mysqli_num_rows($resultPlanning);
										if($nbResultaPlanning>0){
											$display="";
										}
									?>
									<tr <?php echo $display; ?>>
										<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Time spent (h) ";}else{echo "Temps passé (h) ";} ?></td>
										<td>
											<input onKeyUp="nombre(this)" type="texte" id="tempsPasse" <?php echo $read; ?> size="10" name="tempsPasse" value="<?php if($_GET['Mode']=="M"){echo $Ligne['TempsPasse'];}else{echo 0;} ?>" />
										</td>
									</tr>
									<tr>
										<td class="Libelle" id='leHover2'>
											<?php 
												if($_SESSION['Langue']=="EN"){
													echo "Date of work ";
													if($nbResultaFactu>0){
														echo "\n<span>Last invoice date : ".AfficheDateFR($LigneFactu['DateFacturation'])."</span>\n";
													}
												}
												else{
													echo "Date du travail ";
													if($nbResultaFactu>0){
														echo "\n<span>Dernière date de facturation : ".AfficheDateFR($LigneFactu['DateFacturation'])."</span>\n";
													}
												} 
											?>
										</td>
										<td>
											<input type="<?php echo $TypeDate; ?>" id="dateTravail" <?php echo $read; ?> size="10" name="dateTravail" onchange="VerifValidite('<?php echo $_SESSION['Langue']; ?>')" value="<?php if($_GET['Mode']=="M"){echo AfficheDateFR($Ligne['DatePreparateur']);}else{echo AfficheDateFR($DateJour);} ?>" />
										</td>
									</tr>
									<tr>
										<td class="Libelle">
											<?php 
											if($_SESSION['Langue']=="EN"){
												echo "Working hours ";
											}
											else{
												echo "Heure du travail ";
											} 
											?>
										</td>
										<?php 
											if($_GET['Mode']=="M"){
												$HeurePreparateur=$Ligne['HeurePreparateur'];
											}
											else{
												$HeurePreparateur=date('H:i:00');
											} 
										?>
										<td>
											<div class="input-group bootstrap-timepicker timepicker">
												<input class="form-control input-small" type="text" name="heurePreparateur" <?php echo $read; ?> id="heurePreparateur" size="6" value="<?php echo $HeurePreparateur; ?>">
											</div>
										</td>
									</tr>
									
									<tr>
										<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Time allocated (h) ";}else{echo "Temps alloué (h) ";} ?></td>
										<td>
											<input readonly='readonly' id="tempsAlloue" size="5" name="tempsAlloue" value="" />
										</td>
									</tr>
								</table>
							</td>
							<td valign='top'>
								<table>
									<tr>
										<td>
											<td valign='top'>
											<?php
												if($_GET['Mode']=="A" || $_GET['Mode']=="D" || $_GET['Mode']=="M"){
													$req="SELECT Id,Id_Tache,Info,Type FROM trame_tache_infocomplementaire WHERE Supprime=0 AND Id_Prestation=".$_SESSION['Id_PrestationTR'];
													$result=mysqli_query($bdd,$req);
													$nbResulta=mysqli_num_rows($result);
													
													
													if ($nbResulta>0){
														$i=0;
														while($rowInfo=mysqli_fetch_array($result)){
															echo "<script>Liste_Tache_Info[".$i."]= Array('".$rowInfo['Id']."','".$rowInfo['Id_Tache']."','".addslashes($rowInfo['Info'])."','".addslashes($rowInfo['Type'])."')</script>";
															$i++;
														}
													}
												}
											?>
											<div id="divInfos">
												<?php
													if($_GET['Mode']=="M" || $_GET['Mode']=="D"){
														$req="SELECT Id,ValeurInfo,Id_InfoTache, ";
														$req.="(SELECT Info FROM trame_tache_infocomplementaire WHERE trame_tache_infocomplementaire.Id=trame_travaileffectue_info.Id_InfoTache) AS Info, ";
														$req.="(SELECT Type FROM trame_tache_infocomplementaire WHERE trame_tache_infocomplementaire.Id=trame_travaileffectue_info.Id_InfoTache) AS Type ";
														$req.="FROM trame_travaileffectue_info WHERE Id_TravailEffectue=".$Id;
														$result=mysqli_query($bdd,$req);
														$nbResulta=mysqli_num_rows($result);
														if ($nbResulta>0){
															echo "<table>";
															while($rowInfo=mysqli_fetch_array($result)){
																echo "<tr><td style='font-weight:bold;'>".$rowInfo['Info']."</td>";
																if($rowInfo['Type']=="Numerique"){
																	echo "<td><input onKeyUp='nombre(this)' class='InfoComplementaire' type='text' ".$read." size='8' id='Info_".$rowInfo['Id']."' name='Info_".$rowInfo['Id']."' value='".$rowInfo['ValeurInfo']."' /></td></tr>";
																}
																elseif($rowInfo['Type']=="Texte"){
																	echo "<td><input type='text' class='InfoComplementaire' id='Info_".$rowInfo['Id']."' ".$read." size='10' name='Info_".$rowInfo['Id']."' value='".$rowInfo['ValeurInfo']."' /></td></tr>";
																}
																elseif($rowInfo['Type']=="Date"){
																	echo "<td><input type='".$TypeDate."' class='InfoComplementaire' onmousedown='datepick();' size='10' ".$read." id='Info_".$rowInfo['Id']."' name='Info_".$rowInfo['Id']."' value='".AfficheDateFR($rowInfo['ValeurInfo'])."' /></td></tr>";
																}
																elseif($rowInfo['Type']=="Oui/Non"){
																		$checked="";
																		if($rowInfo['ValeurInfo']==1){$checked="checked";}
																	echo "<td><input type='checkbox' class='InfoComplementaire' size='10' ".$read." id='Info_".$rowInfo['Id']."' name='Info_".$rowInfo['Id']."' ".$checked." /></td></tr>";
																}
																
															}
															echo "</table>";
														}
													}
												?>
											</div>
										</td>
									</tr>
								</table>
							</td>
						<tr>
							<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Comment ";}else{echo "Commentaire ";} ?></td>
						</tr>
						<tr>
							<td colspan="10">
								<textarea id="commentaire" name="commentaire" <?php echo $read; ?> rows=10 cols=95 style="resize:none;"><?php if($_GET['Mode']=="M"){echo stripslashes(str_replace("\\","",$Ligne['DescriptionModification']));} ?></textarea>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td colspan="10">
					<table  width="100%">
						<tr>
							<td width='50%' valign='top'>
								<table  width='100%'>
									<tr>
										<td>
											<?php
												if($_GET['Mode']=="A"  || $_GET['Mode']=="D" || $_GET['Mode']=="M"){
													$req="SELECT Id,Id_Tache,Id_UO,Relation,Id_DT,Complexite,TypeTravail, ";
													$req.="(SELECT Description FROM trame_uo WHERE trame_uo.Id=trame_tache_uo.Id_UO) AS UO ";
													$req.="FROM trame_tache_uo WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY UO";
													$result=mysqli_query($bdd,$req);
													$nbResulta=mysqli_num_rows($result);
													
													$req2="SELECT Temps,Id_UO,Id_DomaineTechnique,Complexite,TypeTravail FROM trame_tempsalloue LEFT JOIN trame_domainetechnique ON trame_tempsalloue.Id_DomaineTechnique=trame_domainetechnique.Id WHERE trame_domainetechnique.Id_Prestation=".$_SESSION['Id_PrestationTR']."  ";
													$resultTA=mysqli_query($bdd,$req2);
													$nbResultaTA=mysqli_num_rows($resultTA);
													if ($nbResulta>0){
														$i=0;
														while($rowUO=mysqli_fetch_array($result)){
															$TA=0;
															mysqli_data_seek($resultTA,0);
															if ($nbResultaTA>0){
																while($rowTA=mysqli_fetch_array($resultTA)){
																	if($rowTA['Id_UO']==$rowUO['Id_UO'] && $rowTA['Id_DomaineTechnique']==$rowUO['Id_DT'] && $rowTA['Complexite']==$rowUO['Complexite'] && $rowTA['TypeTravail']==$rowUO['TypeTravail']){
																		$TA=$rowTA['Temps'];
																	}
																}
															}
															echo "<script>Liste_Tache_uo[".$i."]= Array(\"".$rowUO['Id_Tache']."\",\"".$rowUO['Id_UO']."\",\"".addslashes(preg_replace("#\n|\t|\r#","",$rowUO['UO']))."\",\"".addslashes($rowUO['Relation'])."\",\"".$TA."\",\"".$rowUO['Id']."\",\"".$rowUO['TypeTravail']."\",\"".$rowUO['Complexite']."\")</script>";
															$i++;
														}
													}
												}
											?>
											<div id="divMandatory">
												<?php
												if($_GET['Mode']=="M" || $_GET['Mode']=="D"){
													echo "<script>document.getElementById('tempsAlloue').value=0</script>";
													$req="SELECT Id,TempsAlloue, ";
													$req.="(SELECT Description FROM trame_uo WHERE trame_uo.Id=trame_travaileffectue_uo.Id_UO) AS UO ";
													$req.="FROM trame_travaileffectue_uo WHERE Relation='Mandatory' AND Id_TravailEffectue=".$Id." ORDER BY UO";
													$result=mysqli_query($bdd,$req);
													$nbResulta=mysqli_num_rows($result);
													if ($nbResulta>0){
														echo "<table width='100%' cellpadding='0' cellspacing='0'>";
														if($_SESSION['Langue']=="EN"){
															echo "<tr><td style='font-weight:bold;'>Work unit mandatory</td></tr>";
														}
														else{
															echo "<tr><td style='font-weight:bold;'>Unit&#233; d'oeuvre mandatory</td></tr>";
														}
														while($rowUO=mysqli_fetch_array($result)){
															echo "<tr><td>".$rowUO['UO']."</td></tr>";
															echo "<script>document.getElementById('tempsAlloue').value=(Math.round((parseFloat(document.getElementById('tempsAlloue').value)) * 100) / 100)+(Math.round((parseFloat(".$rowUO['TempsAlloue'].")) * 100) / 100)</script>";
														}
														echo "</table>";
														echo "<script>document.getElementById('tempsAlloue').value=Math.round((parseFloat(document.getElementById('tempsAlloue').value)) * 100) / 100</script>";
													}
												}
												?>
											</div>
										</td>
									</tr>
								</table>
							</td>
							<td width='50%' valign='top'>
								<table>
									<tr>
										<td>
											<div id="divOptional">
											<?php
											if($_GET['Mode']=="M" || $_GET['Mode']=="D"){
												$req="SELECT Id,TempsAlloue,TravailFait, ";
												$req.="(SELECT Description FROM trame_uo WHERE trame_uo.Id=trame_travaileffectue_uo.Id_UO) AS UO ";
												$req.="FROM trame_travaileffectue_uo WHERE Relation='Optional' AND Id_TravailEffectue=".$Id." ORDER BY UO";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												if ($nbResulta>0){
													echo "<table width='100%' cellpadding='0' cellspacing='0'>";
													if($_SESSION['Langue']=="EN"){
														echo "<tr><td style='font-weight:bold;' colspan='2'>Work unit optional</td></tr>";
														echo "<tr><td bgcolor='#00325F' color=#ffffff; style='font-weight:bold;'>Yes/No</td>";
														echo "<td bgcolor='#00325F' color=#ffffff; style='font-weight:bold;'>Work unit done</td></tr>";
													}
													else{
														echo "<tr><td style='font-weight:bold;' colspan='2'>Unit&#233; d'oeuvre optional</td></tr>";
														echo "<tr><td bgcolor='#00325F' color=#ffffff; style='font-weight:bold;'>Oui/Non</td>";
														echo "<td bgcolor='#00325F' color=#ffffff; style='font-weight:bold;'>Unit&#233; d'oeuvre r&#233;alis&#233;e</td></tr>";
													}
													while($rowUO=mysqli_fetch_array($result)){
														$check="";
														if($rowUO['TravailFait']=="1"){
															$check="checked";
															echo "<script>document.getElementById('tempsAlloue').value=(Math.round((parseFloat(document.getElementById('tempsAlloue').value)) * 100) / 100)+(Math.round((parseFloat(".$rowUO['TempsAlloue'].")) * 100) / 100)</script>";
														}
														echo "<tr><td><input type='checkbox' ".$check." ".$disabled." onchange='TempsAlloue2(".$rowUO['Id'].",".$rowUO['TempsAlloue'].")' id='".$rowUO['Id']."' name='".$rowUO['Id']."' /></td><td>".$rowUO['UO']."</td></tr>";
													}
													echo "</table>";
													echo "<script>document.getElementById('tempsAlloue').value=Math.round((parseFloat(document.getElementById('tempsAlloue').value)) * 100) / 100</script>";
												}
											}
											?>	
											</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr id="tdControle" style="display:none;"><td colspan="10" class="Libelle"><input type="checkbox" id="controle" name="controle" />
			<?php
				if($_SESSION['Langue']=="EN"){
				 echo "Check this deliverable";
				}
				else{
					echo "Contrôler ce livrable";
				}
			?>
			</td></tr>
			<tr><td height="4"></td></tr>
			<tr><td colspan="10" class="Libelle"><input type="checkbox" <?php echo $disabled; ?> id="attestation" name="attestation" <?php if($_GET['Mode']=="M"){if($Ligne['Attestation']==1){echo "checked";}} ?> />
			<?php
				if($_SESSION['Langue']=="EN"){
				 echo "I certify my deliverable complies with AAA & Customer standards & processes, as well as to the defined check-list (if applicable)";
				}
				else{
					echo "J’atteste la conformité de mon livrable par rapport aux normes et procédures AAA & Client, ainsi que la check-list de contrôle (si existante)";
				}
			?>
			</td></tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td colspan="10" align="center">
					<?php if($_GET['Mode2']<>"L"){ ?>
					<input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="M"){if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}}else{if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}}?>">
					<?php } ?>
				</td>
			</tr>
		</table>
		<br>
		<?php 
		$reqPlanning="SELECT Id FROM trame_prestation WHERE Planning=1 AND Id=".$_SESSION['Id_PrestationTR'];
		$resultPlanning=mysqli_query($bdd,$reqPlanning);
		$nbResultaPlanning=mysqli_num_rows($resultPlanning);
		if($nbResultaPlanning==0){
			if($_GET){
				if($_GET['Mode']=="M"){$leJour=$Ligne['DatePreparateur'];}else{$leJour=$DateJour;}
			}
			else{
				$leJour=TrsfDate_($_POST['dateTravail']);
			}
			$tabDateTransfert = explode('-', $leJour);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$jour=date("Y-m-d",$timestampTransfert);
			$semaine=date("W",$timestampTransfert);
			$annee=date("Y",$timestampTransfert);

			$req="SELECT Id,DateDebut, HeureDebut, HeureFin,Id_Tache,Id_WP,Id_Prestation, ((HOUR(HeureDebut)*60)+ MINUTE(HeureDebut)) AS MinuteDebut, ";
			$req.="((HOUR(HeureFin)*60)+ MINUTE(HeureFin)) - ((HOUR(HeureDebut)*60)+ MINUTE(HeureDebut)) AS DureeMinute, ";
			$req.="(SELECT Libelle FROM trame_wp WHERE trame_wp.Id=trame_planning.Id_WP) AS WP, ";
			$req.="(SELECT Libelle FROM trame_tache WHERE trame_tache.Id=trame_planning.Id_Tache) AS Tache, ";
			$req.="(SELECT Libelle FROM trame_prestation WHERE trame_prestation.Id=trame_planning.Id_Prestation) AS Prestation, ";
			$req.="(SELECT Couleur FROM trame_prestation WHERE trame_prestation.Id=trame_planning.Id_Prestation) AS Couleur, ";
			$req.="Commentaire ";
			$req.="FROM trame_planning WHERE DateDebut='".$jour."' AND Id_Preparateur=".$_SESSION['Id_PersonneTR'];
			$result=mysqli_query($bdd,$req);
			$nbResulta=mysqli_num_rows($result);
			
			$reqPoint="SELECT Id, Id_Prestation FROM trame_plannif WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_Preparateur=".$_SESSION['Id_PersonneTR'];
			$reqPoint.=" AND Valide=1 AND Semaine=".$semaine." AND Annee=".$annee." ";
			
			$resultPoint=mysqli_query($bdd,$reqPoint);
			$nbResultaPoint=mysqli_num_rows($resultPoint);
			
			$req="SELECT HeureFin FROM trame_planning WHERE DateDebut='".$jour."' AND Id_Preparateur=".$_SESSION['Id_PersonneTR']." ORDER BY HeureFin DESC ";
			$resultFin=mysqli_query($bdd,$req);
			$nbResultaFin=mysqli_num_rows($resultFin);
			
			$HeureFin='07:00:00';
			if($nbResultaFin>0){
				$rowFin=mysqli_fetch_array($resultFin);
				$HeureFin=$rowFin['HeureFin'];
			}
			$minutes= date('i');
			$minutes = $minutes - ($minutes % 5);
			$HeureH=date('H:i:00',strtotime(date('Y-m-d H:00:00')." +".$minutes." minute"));
			if($HeureFin>$HeureH){$HeureH=date('H:i:00',strtotime($HeureFin." +1 hour"));}
			$calendarTD="calendar_td";
			$calendarEvent="calendar_event";
			$calendarEventDate="calendar_event_date";
			if($nbResultaPoint>0){$calendarTD="calendar2_td";$calendarEvent="calendar2_event";$calendarEventDate="calendar2_event_date";}
			echo "<div id='blocCalendar'>";
			if($nbResultaPoint==0){ 
			?>
				
				<table width="50%" align="center" class="TableCompetences">
					<tr>
						<td width="2%"><input type="checkbox" name="blocPlanning" id="blocPlanning"/></td>
						<td width="30%" class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Add a block to the schedule";}else{echo "Ajouter un bloc au planning";}?></td>
						<td width="5%"><?php if($_SESSION['Langue']=="EN"){echo "from";}else{echo "de";}?></td>
						<td width="15%">
							<div class="input-group bootstrap-timepicker timepicker">
								<input class="form-control input-small" type="text" name="heureDebut" id="heureDebut" size="6" value="<?php echo $HeureFin; ?>">
							</div>
						</td>
						<td width="5%"><?php if($_SESSION['Langue']=="EN"){echo "to";}else{echo "à";}?></td>
						<td width="15%">
							<div class="input-group bootstrap-timepicker timepicker">
							<input class="form-control input-small" class="time" type="text" name="heureFin" id="heureFin" size="6" value="<?php echo $HeureH; ?>">
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="7" align="center" class="Libelle">
						<?php if($_SESSION['Langue']=="EN"){echo "Warning: Block overlay is not possible !";}else{echo "Attention : La superposition de blocs n'est pas possible ! ";}?>
						</td>
					</tr>
				</table>
			<?php
			}
			echo "</div>";
		?>
		<div id="calendrier2">
		<table align="center" cellpadding="0" cellspacing="0">
			<tr>
			<?php
				for($i=0;$i<=23;$i++){
					echo "<td class=\"info_horairesPROD info_horaires_content2\">";
					$heure=$i;
					if($i<10){$heure="0".$i;}
					echo "<label style=\"font-size:14px;width:60px;\">".$heure."</label><sup>00</sup>";
					echo "</td>";
				}
			?>
			</tr>
			<input type="hidden" name="" id="nbResultaPoint" value="<?php echo $nbResultaPoint; ?>">
			<tr>
				<?php
				$j=0;
					if ($nbResulta>0){
						echo "<td colspan='24' valign=\"top\" class=\"other_day ".$calendarTD."\" height=\"80px\" id=\"".$jour."\">";
						while($row=mysqli_fetch_array($result)){
							$couleur="#cbcbcb";
							if($row['Couleur']<>""){$couleur=$row['Couleur'];}
							if($row['Id_Prestation']<>$_SESSION['Id_PrestationTR']){$calendarEvent="calendar_event";$calendarEventDate="calendar_event_date";}
							else{if($nbResultaPoint>0){$calendarTD="calendar2_td";$calendarEvent="calendar2_event";$calendarEventDate="calendar2_event_date";}}
							echo "<div class=\"".$calendarEvent."\" id=\"".$row['Id']."\" style=\"position:absolute;width:".($row['DureeMinute']*0.6)."px; margin-left:".($row['MinuteDebut']*0.6)."px;background-color:".$couleur.";display:table-cell;\">";
								echo "<div class=\"".$calendarEventDate."\" id=\"".$row['Id']."_date\" >";
									echo "<span id=\"".$row['Id']."_date_debut_heure\">".substr($row['HeureDebut'],0,2)."</span>:";
									echo "<span id=\"".$row['Id']."_date_debut_minute\">".substr($row['HeureDebut'],3,2)."</span> -";
									echo "<span id=\"".$row['Id']."_date_fin_heure\">".substr($row['HeureFin'],0,2)."</span>:";
									echo "<span id=\"".$row['Id']."_date_fin_minute\">".substr($row['HeureFin'],3,2)."</span>";
								echo "</div>";
								echo "<div class=\"calendar_event_title hoverCritereOTD\" id=\"".$row['Id']."_title\">[".$row['Prestation']."]\n<span>".$row['Tache']."</span><br></div>";
							echo "</div>";
							echo "<script>ListePlanning[".$j."]= Array('".$row['Id']."','".$row['DateDebut']."','".$row['HeureDebut']."','".$row['HeureFin']."')</script>";
							
							$j++;
						}
						echo "</td>";
					}
				?>
			</tr>
		</table>
		</div>
		<?php } ?>
		</form>
		<?php
			if($_GET['Mode']=="A" || $_GET['Mode']=="D"){
				if($leWP>0){
					echo "<script>RechargerInfos('".$_SESSION['Langue']."')</script>";
				}
			}
			echo "<script>AfficherTDControle()</script>";
		?>
<?php
	}
	elseif($_GET['Mode']=="S")
	//Mode suppression, supprime trame_travaileffectue + trame_travaileffectue_uo + trame_travaileffectue_info 
	{
		$requete="DELETE FROM trame_travaileffectue WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		$requete="DELETE FROM trame_travaileffectue_uo WHERE Id_TravailEffectue=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		$requete="DELETE FROM trame_travaileffectue_info WHERE Id_TravailEffectue=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		
		$req="DELETE FROM trame_controlecroise_contenu WHERE Id_CC IN (SELECT Id FROM trame_controlecroise WHERE Id=".$_GET['Id'].")";
		$result=mysqli_query($bdd,$req);
		
		$req="DELETE FROM trame_controlecroise WHERE Id_TravailEffectue=".$_GET['Id'];
		$result=mysqli_query($bdd,$req);
		echo "<script>FermerEtRecharger();</script>";
		
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
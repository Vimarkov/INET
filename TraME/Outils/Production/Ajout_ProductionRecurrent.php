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
	
	//Parcours des dates
	$dateDebut=TrsfDate_($_POST['dateTravail']);
	$dateFin=TrsfDate_($_POST['dateTravailFin']);
	
	$heurePrepa=$_POST['heurePreparateur'];
	if(strlen($heurePrepa)==7){$heurePrepa="0".$heurePrepa;}
	
	for($dateDebut=TrsfDate_($_POST['dateTravail']);$dateDebut<=$dateFin;$dateDebut=date("Y-m-d",strtotime($dateDebut." +1 day"))){
		if((isset($_POST['lundi']) && date("w",strtotime($dateDebut))==1)
			|| (isset($_POST['mardi']) && date("w",strtotime($dateDebut))==2)
			|| (isset($_POST['mercredi']) && date("w",strtotime($dateDebut))==3)
			|| (isset($_POST['jeudi']) && date("w",strtotime($dateDebut))==4)
			|| (isset($_POST['vendredi']) && date("w",strtotime($dateDebut))==5)
			|| (isset($_POST['samedi']) && date("w",strtotime($dateDebut))==6)
			|| (isset($_POST['dimanche']) && date("w",strtotime($dateDebut))==-1)
		){
			foreach($tab as $reference){
				$reference= preg_replace("(\r\n|\n|\r)",'',$reference);
				if($reference<>""){
					$tempsPasse=0;
					if($_POST['tempsPasse']<>""){$tempsPasse=$_POST['tempsPasse'];}
					$requete="INSERT INTO trame_travaileffectue (Id_Prestation,Id_Tache,Statut,Id_Preparateur,Id_WP,Designation,DatePreparateur,HeurePreparateur,DescriptionModification,StatutDelai,Attestation,NiveauControle,TempsPasse) ";
					$requete.="VALUES (".$_SESSION['Id_PrestationTR'].",".$_POST['tache'].",'".$_POST['statutTravail']."',".$_POST['preparateur'].",".$_POST['wp'].",'".addslashes($reference)."','".$dateDebut."','".$heurePrepa."','".addslashes($_POST['commentaire'])."','".addslashes($_POST['statutDelais'])."',".$attestation.",".$Niveau.",".$tempsPasse.") ";
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
					
					// Infos complémentaires
					$req="SELECT Id,Id AS Id_InfoTache, Info,Type FROM trame_tache_infocomplementaire WHERE Supprime=0 AND Id_Tache=".$_POST['tache'];

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
		}
	}

	if($messageAC<>""){
		echo "<script type='text/javascript'>messageAC('".$messageAC."','".$_SESSION['Langue']."');</script>";
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	$timestamp_debut = microtime(true);
	//Mode ajout ou modification
	$Id=$_GET['Id'];
	$req="SELECT DateFacturation FROM trame_facturation WHERE Id_Prestation=".$_SESSION['Id_PrestationTR'];
	$resultFactu=mysqli_query($bdd,$req);
	$LigneFactu=mysqli_fetch_array($resultFactu);
	
	$read="";
	$disabled="";
	$disabled2="";
	$TypeDate="date";

	if($_GET['Id']!='0')
	{
		$req="SELECT Id, Id_Tache,Statut,Id_Preparateur,Id_WP,Designation,DatePreparateur,HeurePreparateur,DescriptionModification,StatutDelai,TempsPasse, ";
		$req.="(SELECT Delais FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS Delais, 
				(SELECT COUNT(trame_controlecroise.Id) FROM trame_controlecroise WHERE Id_TravailEffectue=trame_travaileffectue.Id) AS NbControle, ";
		$req.="(SELECT CritereOTD FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS CritereOTD, Attestation ";
		$req.="FROM trame_travaileffectue WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$req);
		$Ligne=mysqli_fetch_array($result);
	}
?>
	<form id="formulaire" method="POST" action="Ajout_ProductionRecurrent.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
	<input type="hidden" name="Droit" id="Droit" value="<?php echo $_SESSION['DroitTR']; ?>">
	<input type="hidden" name="OldDateTravail" id="OldDateTravail" value="">
	<input type="hidden" name="DateFacturation" id="DateFacturation" value="<?php echo $LigneFactu['DateFacturation']; ?>">
	<input type="hidden" name="OldStatutDelais" id="OldStatutDelais" value="">
	<input type="hidden" name="OldStatut" id="OldStatut" value="">
	<input type="hidden" name="id" value="">
	<input type="hidden" name="OldTache" value="">
	<input type="hidden" name="NbControle" id="NbControle" value="">
	<input type="hidden" name="ModificationTache" id="ModificationTache" value="">
	<input type="hidden" name="IdPreparateur" id="IdPreparateur" value="">
	<input type="hidden" name="IdPersonne" id="IdPersonne" value="<?php echo $_SESSION['Id_PersonneTR'];?>">
	<table width="95%" align="center" class="TableCompetences">
		<tr class="TitreColsUsers">
			<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Manufacturing engineer";}else{echo "Préparateur";} ?></td>
			<td colspan="10">
				<select id="preparateur" name="preparateur" <?php echo $disabled2; ?>>
					
					<?php
						
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
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){ 
							while($rowPrepa=mysqli_fetch_array($result)){
								$selected="";
								if($_SESSION['Id_PersonneTR']==$rowPrepa['Id']){$selected="selected";}
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
						$requete="SELECT Id_WP FROM trame_acces WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_Personne=".$_SESSION['Id_PersonneTR'];
						$resultWP=mysqli_query($bdd,$requete);
						$nbResulta=mysqli_num_rows($resultWP);
						if ($nbResulta>0){
							$rowWP=mysqli_fetch_array($resultWP);
							$leWP=$rowWP['Id_WP'];
						}

						echo"<option value='0'></option>";
						
						$req="SELECT Id, Libelle, Supprime, Actif FROM trame_wp WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowWP=mysqli_fetch_array($result)){
								$selected="";
								if($leWP==$rowWP['Id']){$selected="selected";}
								if($rowWP['Supprime']==false && $rowWP['Actif']==false){
									echo "<option value='".$rowWP['Id']."' ".$selected.">".stripslashes(str_replace("\\","",$rowWP['Libelle']))."</option>";
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
							$req.="WHERE Recurrent=1
								AND trame_tache.Id_Prestation=".$_SESSION['Id_PrestationTR']."";
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
							$req.="WHERE Recurrent=1 AND trame_tache.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
							
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								$nb=0;
								$i=0;
								while($rowTache=mysqli_fetch_array($result)){
									if($leWP>0){
										if($rowTache['Id_WP']==$leWP){
											$nb++;
											$selected="";
											if($rowTache['Supprime']==false && $rowTache['SupprTacheWP']==false){
												echo "<option value='".$rowTache['Id']."' ".$selected.">".stripslashes(str_replace("\\","",$rowTache['Libelle']))."</option>";
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
				<textarea id="reference" name="reference" rows=20 cols=25 <?php echo $read; ?> style="resize:none;"></textarea>
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
									?>
									<td width="25%" class="Libelle" <?php echo $Hover;?>><?php if($_SESSION['Langue']=="EN"){echo $infoBulle."Deadline ";}else{echo $infoBulle."Statut du délais ";} ?></td>
									<td width="15%" align="left">
										<div id="leDelais">
											<select id="statutDelais" <?php echo $disabled; ?> name="statutDelais">
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
											<option value=""></option>
											<option value="A VALIDER" ><?php if($_SESSION['Langue']=="EN"){echo "TO BE VALIDATED";}else{echo "A VALIDER";}?></option>
											<option value="EN COURS" ><?php if($_SESSION['Langue']=="EN"){echo "IN PROGRESS";}else{echo "EN COURS";}?></option>
											<option value="BLOQUE" ><?php if($_SESSION['Langue']=="EN"){echo "BLOCKED";}else{echo "BLOQUE";}?></option>
											<option value="EN ATTENTE"><?php if($_SESSION['Langue']=="EN"){echo "WAITING";}else{echo "EN ATTENTE";}?></option>
											<option value="STAND BY"><?php if($_SESSION['Langue']=="EN"){echo "STAND BY";}else{echo "STAND BY";}?></option>
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
										<input onKeyUp="nombre(this)" type="texte" id="tempsPasse" <?php echo $read; ?> size="10" name="tempsPasse" value="0" />
									</td>
								</tr>
								<tr>
									<td class="Libelle" id='leHover2'>
										<?php 
										if($_SESSION['Langue']=="EN"){
											echo "Date of work ";
											echo "\n<span>Last invoice date : ".AfficheDateFR($LigneFactu['DateFacturation'])."</span>\n";
										}
										else{
											echo "Date du travail ";
											echo "\n<span>Dernière date de facturation : ".AfficheDateFR($LigneFactu['DateFacturation'])."</span>\n";
										} 
										?>
									</td>
									<td>
										<input type="<?php echo $TypeDate; ?>" id="dateTravail" <?php echo $read; ?> size="10" name="dateTravail" onchange="VerifValidite('<?php echo $_SESSION['Langue']; ?>')" value="<?php echo AfficheDateFR($DateJour); ?>" />
									</td>
								</tr>
								<tr>
									<td class="Libelle">
										<?php 
										if($_SESSION['Langue']=="EN"){
											echo "Recurrence end date ";
										}
										else{
											echo "Date fin récurrence ";
										} 
										?>
									</td>
									<td>
										<input type="<?php echo $TypeDate; ?>" id="dateTravailFin" <?php echo $read; ?> size="10" name="dateTravailFin" value="<?php echo AfficheDateFR($DateJour); ?>" />
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
										$HeurePreparateur=date('H:i:00');
									?>
									<td>
										<div class="input-group bootstrap-timepicker timepicker">
											<input class="form-control input-small" type="text" name="heurePreparateur" <?php echo $read; ?> id="heurePreparateur" size="6" value="<?php echo $HeurePreparateur; ?>">
										</div>
									</td>
								</tr>
								<tr>
									<td class="Libelle">
										<?php 
										if($_SESSION['Langue']=="EN"){
											echo "Days of recurrence ";
										}
										else{
											echo "Jours de récurrence ";
										} 
										?>
									</td>
									<td class="Libelle" colspan="3">
										<input type="checkbox" name="lundi" id="lundi" value="1"/>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "M";}else{echo "L";} ?>&nbsp;&nbsp;&nbsp;
										<input type="checkbox" name="mardi" id="mardi" value="2"/>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Tu";}else{echo "Ma";} ?>&nbsp;&nbsp;&nbsp;
										<input type="checkbox" name="mercredi" id="mercredi" value="3"/>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "W";}else{echo "Me";} ?>&nbsp;&nbsp;&nbsp;
										<input type="checkbox" name="jeudi" id="jeudi" value="4"/>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Th";}else{echo "J";} ?>&nbsp;&nbsp;&nbsp;
										<br>
										<input type="checkbox" name="vendredi" id="vendredi" value="5"/>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "F";}else{echo "V";} ?>&nbsp;&nbsp;&nbsp;
										<input type="checkbox" name="samedi" id="samedi" value="6"/>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Sa";}else{echo "S";} ?>&nbsp;&nbsp;&nbsp;
										<input type="checkbox" name="dimanche" id="dimanche" value="0" />&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Su";}else{echo "D";} ?>&nbsp;&nbsp;&nbsp;
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
										?>
										<div id="divInfos">
											
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
							<textarea id="commentaire" name="commentaire" <?php echo $read; ?> rows=10 cols=95 style="resize:none;"></textarea>
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
										?>
										<div id="divMandatory">
											
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
		<tr><td colspan="10" class="Libelle"><input type="checkbox" <?php echo $disabled; ?> id="attestation" name="attestation" />
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
				<input class="Bouton" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";} ?>">
			</td>
		</tr>
	</table>
	</form>
	<?php
		if($leWP>0){
			echo "<script>RechargerInfos('".$_SESSION['Langue']."')</script>";
		}
		echo "<script>AfficherTDControle()</script>";
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
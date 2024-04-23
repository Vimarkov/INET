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
	if($_POST['Mode']=="A"){
		//Sauvegarder WP utilisé
		$requete="UPDATE trame_acces SET Id_WP=".$_POST['wp']." WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_Personne=".$_SESSION['Id_PersonneTR'];
		$resultWP=mysqli_query($bdd,$requete);

		$tab = explode("\n",$_POST['reference']);
		foreach($tab as $reference){
			$reference= preg_replace("(\r\n|\n|\r)",'',$reference);
			if($reference<>""){
				$requete="INSERT INTO trame_travaileffectue (Id_Prestation,Id_Tache,Statut,Id_Preparateur,Id_WP,Designation,DatePreparateur,DescriptionModification,StatutDelai) ";
				$requete.="VALUES (".$_SESSION['Id_PrestationTR'].",".$_POST['tache'].",'".$_POST['statutTravail']."',".$_SESSION['Id_PersonneTR'].",".$_POST['wp'].",'".addslashes($reference)."','".TrsfDate_($_POST['dateTravail'])."','".addslashes($_POST['commentaire'])."','".addslashes($_POST['statutDelais'])."') ";
				$result=mysqli_query($bdd,$requete);
				$IdTravail = mysqli_insert_id($bdd);
				
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
				$req="SELECT Id,Info,Type FROM trame_tache_infocomplementaire WHERE Id_Tache=".$_POST['tache'];
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					while($rowInfo=mysqli_fetch_array($result)){
						if(isset($_POST['Info_'.$rowInfo['Id']])){
							$requete="INSERT INTO trame_travaileffectue_info (Id_TravailEffectue,Id_InfoTache,ValeurInfo) ";
							if($rowInfo['Type']=="Numerique"){
								$requete.="VALUES (".$IdTravail.",".$rowInfo['Id'].",'".$_POST['Info_'.$rowInfo['Id']]."') ";
							}
							elseif($rowInfo['Type']=="Date"){
								$requete.="VALUES (".$IdTravail.",".$rowInfo['Id'].",'".TrsfDate_($_POST['Info_'.$rowInfo['Id']])."') ";
							}
							else{
								$requete.="VALUES (".$IdTravail.",".$rowInfo['Id'].",'".$_POST['Info_'.$rowInfo['Id']]."') ";
							}
							$resultI=mysqli_query($bdd,$requete);
						}
					}
				}
			}
		}
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE trame_travaileffectue SET ";
		$requete.="Statut='".addslashes($_POST['statutTravail'])."',";
		if(substr($_SESSION['DroitTR'],0,1)==1){
			$requete.="Id_Preparateur=".$_SESSION['Id_PersonneTR'].",";
		}
		$requete.="Designation='".addslashes($_POST['reference'])."',";
		$requete.="DatePreparateur='".TrsfDate_($_POST['dateTravail'])."',";
		$requete.="DescriptionModification='".addslashes($_POST['commentaire'])."',";
		$requete.="StatutDelai='".addslashes($_POST['statutDelais'])."' ";
		$requete.=" WHERE Id=".$_POST['id'];
		$result=mysqli_query($bdd,$requete);
		
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
				if(isset($_POST['Info_'.$rowInfo['Id']])){
					$requete="UPDATE trame_travaileffectue_info SET ValeurInfo= ";
					if($rowInfo['Type']=="Numerique"){
						$requete.="'".$_POST['Info_'.$rowInfo['Id']]."' ";
					}
					elseif($rowInfo['Type']=="Date"){
						$requete.="'".TrsfDate_($_POST['Info_'.$rowInfo['Id']])."' ";
					}
					else{
						$requete.="'".$_POST['Info_'.$rowInfo['Id']]."' ";
					}
					$requete.="WHERE Id=".$rowInfo['Id'];
					$resultI=mysqli_query($bdd,$requete);
				}
			}
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	$timestamp_debut = microtime(true);
	//Mode ajout ou modification
	$Id=$_GET['Id'];
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
		
		$req="SELECT DateFacturation FROM trame_facturation WHERE Id_Prestation=".$_SESSION['Id_PrestationTR'];
		$resultFactu=mysqli_query($bdd,$req);
		$LigneFactu=mysqli_fetch_array($resultFactu);
		
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
			$req="SELECT Id, Id_Tache,Statut,Id_Preparateur,Id_WP,Designation,DatePreparateur,DescriptionModification,StatutDelai, ";
			$req.="(SELECT CritereOTD FROM trame_tache WHERE trame_tache.Id=trame_travaileffectue.Id_Tache) AS CritereOTD ";
			$req.="FROM trame_travaileffectue WHERE Id=".$_GET['Id'];
			$result=mysqli_query($bdd,$req);
			$Ligne=mysqli_fetch_array($result);
		}
?>

		<form id="formulaire" method="POST" action="Ajout_Production.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
		<input type="hidden" name="Mode" id="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Droit" id="Droit" value="<?php echo $_SESSION['DroitTR']; ?>">
		<input type="hidden" name="OldDateTravail" id="OldDateTravail" value="<?php if($_GET['Mode']=="M"){echo AfficheDateFR($Ligne['DatePreparateur']);} ?>">
		<input type="hidden" name="DateFacturation" id="DateFacturation" value="<?php echo $LigneFactu['DateFacturation']; ?>">
		<input type="hidden" name="OldStatutDelais" id="OldStatutDelais" value="<?php if($_GET['Mode']=="M"){echo $Ligne['StatutDelai'];} ?>">
		<input type="hidden" name="id" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<table width="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Workpackage";}else{echo "Workpackage";} ?></td>
				<td colspan="4">
					<select id="wp" name="wp" <?php echo $disabled2; ?> onchange="RechargerTache('<?php echo $_SESSION['Langue']; ?>')">
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
							$req="SELECT Id, Libelle, Supprime FROM trame_wp WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle ;";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowWP=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="M"){
										if($rowWP['Id']==$Ligne['Id_WP']){$selected="selected";}
										if($rowWP['Supprime']==false  || $rowWP['Id']==$Ligne['Id_WP']){
											echo "<option value='".$rowWP['Id']."' ".$selected.">".$rowWP['Libelle']."</option>";
										}
									}
									else{
										if($leWP==$rowWP['Id']){$selected="selected";}
										if($rowWP['Supprime']==false){
											echo "<option value='".$rowWP['Id']."' ".$selected.">".$rowWP['Libelle']."</option>";
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
				<td colspan="4">
					<select id="famille" name="famille" <?php echo $disabled2; ?> onchange="RechargerTache('<?php echo $_SESSION['Langue']; ?>')">
						<?php
							echo"<option value='0'></option>";
							$req="SELECT Id, Libelle, Supprime FROM trame_familletache WHERE Id_Prestation=".$_SESSION['Id_PrestationTR'].";";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowFamille=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="M"){
										if($rowFamille['Id']==$Ligne['Id_FamilleTache']){$selected="selected";}
									}
									if($rowFamille['Supprime']==false  || $rowFamille['Id']==$Ligne['Id_Categorie']){
										echo "<option value='".$rowFamille['Id']."' ".$selected.">".$rowFamille['Libelle']."</option>";
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
				<td colspan="4">
					<div id="divTache">
						<select id="tache" name="tache" <?php echo $disabled2; ?> onchange="RechargerInfos('<?php echo $_SESSION['Langue']; ?>')">
							<?php
								$req="SELECT DISTINCT trame_tache.Id, trame_tache.Libelle, trame_tache_wp.Id_WP, trame_tache.Supprime,trame_tache.Id_FamilleTache ";
								$req.="FROM trame_tache_wp LEFT JOIN trame_tache ON trame_tache_wp.Id_Tache=trame_tache.Id ";
								$req.="WHERE trame_tache.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									$nb=0;
									$i=0;
									while($rowTache=mysqli_fetch_array($result)){
										if($_GET['Mode']=="M"){
											if($rowTache['Id_WP']==$Ligne['Id_WP']){
												$nb++;
												$selected="";
												if($rowTache['Id']==$Ligne['Id_Tache']){$selected="selected";}
												if($rowTache['Supprime']==false  || $rowTache['Id']==$Ligne['Id_Tache']){
													echo "<option value='".$rowTache['Id']."' ".$selected.">".$rowTache['Libelle']."</option>";
												}
											}
										}
										elseif($_GET['Mode']=="A"){
											if($leWP>0){
												if($rowTache['Id_WP']==$leWP){
													$nb++;
													$selected="";
													if($rowTache['Supprime']==false){
														echo "<option value='".$rowTache['Id']."' ".$selected.">".$rowTache['Libelle']."</option>";
													}
												}
											}
										}
										echo "<script>Liste_Tache_WP[".$i."]= Array('".$rowTache['Id']."','".$rowTache['Id_WP']."','".$rowTache['Supprime']."','".addslashes($rowTache['Libelle'])."','".$rowTache['Id_FamilleTache']."')</script>";
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
				<td width='20%' class="Libelle" valign="top"><?php if($_SESSION['Langue']=="EN"){echo "Reference(s) (1 reference / line)";}else{echo "Référence(s) (1 référence / ligne)";} ?></td>
			</tr>
			<tr>
				<td width='20%' valign='top'>
					<textarea id="reference" name="reference" rows=20 cols=25 <?php echo $read; ?> style="resize:none;"><?php if($_GET['Mode']=="M"){echo $Ligne['Designation'];} ?></textarea>
				</td>
				<td width='40%' valign='top'>
					<table>
						<tr>
							<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Deadline ";}else{echo "Statut du délais ";} ?></td>
							<?php
								$Hover="";
								$infoBulle ="";
								if($_GET['Mode']=="M"){
									$Hover="id='leHover2'";
									$infoBulle = "\n<span>Critère OTD : ".stripslashes($Ligne['CritereOTD'])."</span>\n";
								}
							?>
							<td <?php echo $Hover;?>>
								<select id="statutDelais" <?php echo $disabled; ?> name="statutDelais">
									<option value="N/A" <?php if($_GET['Mode']=="M"){if($Ligne['StatutDelai']=="N/A"){echo "selected";}} ?>>N/A</option>
									<option value="OK" <?php if($_GET['Mode']=="M"){if($Ligne['StatutDelai']=="OK"){echo "selected";}} ?>>OK</option>
									<option value="KO" <?php if($_GET['Mode']=="M"){if($Ligne['StatutDelai']=="KO"){echo "selected";}} ?>>KO</option>
								</select>
								<?php echo $infoBulle;?>
							</td>
						</tr>
						<tr>
							<td class="Libelle" id='leHover2'>
							<?php 
								if($_SESSION['Langue']=="EN"){
									echo "Work status ";
									echo "\n<span>Last invoice date : ".AfficheDateFR($LigneFactu['DateFacturation'])."</span>\n";
								}
								else{
									echo "Statut du travail ";
									echo "\n<span>Dernière date de facturation : ".AfficheDateFR($LigneFactu['DateFacturation'])."</span>\n";
								} 
							?>
							</td>
							<td>
								<select id="statutTravail" <?php echo $disabled; ?> name="statutTravail">
									<option value=""></option>
									<option value="A VALIDER" <?php if($_GET['Mode']=="M"){if($Ligne['Statut']=="A VALIDER"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "TO BE VALIDATED";}else{echo "A VALIDER";}?></option>
									<option value="EN COURS" <?php if($_GET['Mode']=="M"){if($Ligne['Statut']=="EN COURS"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "IN PROGRESS";}else{echo "EN COURS";}?></option>
									<option value="REFUSE" <?php if($_GET['Mode']=="M"){if($Ligne['Statut']=="REFUSE"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "RETURN";}else{echo "RETOURNE";}?></option>
									<option value="VALIDE" <?php if($_GET['Mode']=="M"){if($Ligne['Statut']=="VALIDE"){echo "selected";}} ?>><?php if($_SESSION['Langue']=="EN"){echo "VALIDATED";}else{echo "VALIDE";}?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Date of work ";}else{echo "Date du travail ";} ?></td>
							<td>
								<input type="<?php echo $TypeDate; ?>" id="dateTravail" <?php echo $read; ?> size="10" name="dateTravail" onchange="VerifValidite('<?php echo $_SESSION['Langue']; ?>')" value="<?php if($_GET['Mode']=="M"){echo AfficheDateFR($Ligne['DatePreparateur']);}else{echo AfficheDateFR($DateJour);} ?>" />
							</td>
						</tr>
						<tr>
							<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Time allocated (h) ";}else{echo "Temps alloué (h) ";} ?></td>
							<td>
								<input readonly='readonly' id="tempsAlloue" size="5" name="tempsAlloue" value="" />
							</td>
						</tr>
						<tr>
							<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Comment ";}else{echo "Commentaire ";} ?></td>
						</tr>
						<tr>
							<td colspan="2">
								<textarea id="commentaire" name="commentaire" <?php echo $read; ?> rows=10 cols=95 style="resize:none;"><?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['DescriptionModification']);} ?></textarea>
							</td>
						</tr>
					</table>
				</td>
				<td width='40%' valign='top'>
					<?php
						if($_GET['Mode']=="A"){
							$req="SELECT Id,Id_Tache,Info,Type FROM trame_tache_infocomplementaire WHERE Id_Prestation=".$_SESSION['Id_PrestationTR'];
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
							if($_GET['Mode']=="M"){
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
									}
									echo "</table>";
								}
							}
						?>
					</div>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td colspan="6">
					<table  width="100%">
						<tr>
							<td width='50%' valign='top'>
								<table  width='100%'>
									<tr>
										<td>
											<?php
												if($_GET['Mode']=="A"){
													$req="SELECT Id,Id_Tache,Id_UO,Relation, ";
													$req.="(SELECT Description FROM trame_uo WHERE trame_uo.Id=trame_tache_uo.Id_UO) AS UO, ";
													$req.="(SELECT Temps FROM trame_tempsalloue WHERE trame_tempsalloue.Id_UO=trame_tache_uo.Id_UO AND ";
													$req.="trame_tempsalloue.Id_DomaineTechnique=trame_tache_uo.Id_DT AND ";
													$req.="trame_tempsalloue.Complexite=trame_tache_uo.Complexite AND ";
													$req.="trame_tempsalloue.TypeTravail=trame_tache_uo.TypeTravail LIMIT 1) AS TempsAlloue ";
													$req.="FROM trame_tache_uo WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY UO";
													$result=mysqli_query($bdd,$req);
													$nbResulta=mysqli_num_rows($result);
													if ($nbResulta>0){
														$i=0;
														while($rowUO=mysqli_fetch_array($result)){

															echo "<script>Liste_Tache_uo[".$i."]= Array(\"".$rowUO['Id_Tache']."\",\"".$rowUO['Id_UO']."\",\"".addslashes($rowUO['UO'])."\",\"".addslashes($rowUO['Relation'])."\",\"".$rowUO['TempsAlloue']."\",\"".$rowUO['Id']."\")</script>";
															$i++;
														}
													}
												}
											?>
											<div id="divMandatory">
												<?php
												if($_GET['Mode']=="M"){
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
											if($_GET['Mode']=="M"){
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
			<tr><td height="4"></td></tr>
			<tr>
				<td colspan="4" align="center">
					<?php if($_GET['Mode2']<>"L"){ ?>
					<input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="M"){if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}}else{if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}}?>">
					<?php } ?>
				</td>
			</tr>
		</table>
		</form>
		<?php
			if($_GET['Mode']=="A"){
				if($leWP>0){
					echo "<script>RechargerInfos('".$_SESSION['Langue']."')</script>";
				}
			}
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
		echo "<script>FermerEtRecharger();</script>";
		
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
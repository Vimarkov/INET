<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../JS/date.js"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript" src="Controle.js"></script>
	<style type="text/css">
		#id_confrmdiv
		{
			display: none;
			background-color: #eee;
			border-radius: 5px;
			border: 1px solid #aaa;
			position: fixed;
			width: 400px;
			left: 50%;
			margin-left: -150px;
			margin-top: 200px;
			padding: 6px 8px 8px;
			box-sizing: border-box;
			text-align: center;
		}
		#id_confrmdiv button {
			background-color: #ccc;
			display: inline-block;
			border-radius: 3px;
			border: 1px solid #aaa;
			padding: 2px;
			text-align: center;
			width: 80px;
			cursor: pointer;
		}
		#id_confrmdiv .button:hover
		{
			background-color: #ddd;
		}
		#confirmBox .message
		{
			text-align: left;
			margin-bottom: 8px;
		}
	</style>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
Ecrire_Code_JS_Init_Date();
if($_POST){
	if(isset($_POST['btn_save'])){
		//Passage du statut travaileffectue à CONTROLE
		$req="UPDATE trame_controlecroise SET Id_Controleur=".$_SESSION['Id_PersonneTR'].", DateControle='".date("Y-m-d")."', DateReControle='0001-01-01' WHERE Id=".$_POST['id'];
		$result=mysqli_query($bdd,$req);
		
		//Modification du contenu dans la table 
		$req="SELECT Id FROM trame_cl_version_contenu WHERE Id_VersionCL=".$_POST['id_CLVersion']." ;";
		$resultContenu=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($resultContenu);
		$statut="A VALIDER";
		if ($nbResulta>0){
			while($row=mysqli_fetch_array($resultContenu)){
				if($_POST[$row['Id']] == "KO"){
					$statut="REC";
				}
				$requete="UPDATE trame_controlecroise_contenu SET ValeurControle='".$_POST[$row['Id']]."', Commentaire='".addslashes($_POST[$row['Id']."_Commentaire"])."' ";
				$requete.="WHERE Id_Contenu=".$row['Id']." AND Id_CC=".$_POST['id']." ";
				$result=mysqli_query($bdd,$requete);
			}
		}
		
		//Passage du statut travaileffectue en A VALIDER
		$req="UPDATE trame_travaileffectue SET Statut='".$statut."' WHERE Id=".$_POST['idTravailEffectue'];
		$result=mysqli_query($bdd,$req);
	}
	elseif(isset($_POST['btn_recontrole'])){
		//Ajout de la date de recontrole
		$req="UPDATE trame_controlecroise SET Id_ReControleur=".$_SESSION['Id_PersonneTR'].", DateReControle='".date("Y-m-d")."' WHERE Id=".$_POST['id'];
		$result=mysqli_query($bdd,$req);
		
		//Passage du statut travaileffectue en A VALIDER
		$req="UPDATE trame_travaileffectue SET Statut='A VALIDER' WHERE Id=".$_POST['idTravailEffectue'];
		$result=mysqli_query($bdd,$req);
	}
	elseif(isset($_POST['btn_choixContr'])){
		$req="UPDATE trame_controlecroise SET Id_Controleur=".$_POST['controleur']." WHERE Id=".$_POST['id'];
		$result=mysqli_query($bdd,$req);
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Id=$_GET['Id'];
	
	$req="SELECT trame_controlecroise.Id, trame_travaileffectue.Designation,trame_controlecroise.Id_CLVersion,trame_controlecroise.Id_Preparateur,Id_Controleur, ";
	$req.="trame_controlecroise.DateAutoC,trame_controlecroise.DateControle,trame_controlecroise.DateReControle, ";
	$req.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=trame_controlecroise.Id_Controleur) AS Controleur, ";
	$req.="(SELECT (SELECT Libelle FROM trame_checklist WHERE trame_checklist.Id=trame_cl_version.Id_CL) FROM trame_cl_version WHERE trame_cl_version.Id=trame_controlecroise.Id_CLVersion) AS CheckList, ";
	$req.="(SELECT NumVersion FROM trame_cl_version WHERE trame_cl_version.Id=trame_controlecroise.Id_CLVersion) AS NumVersion ";
	$req.="FROM trame_controlecroise LEFT JOIN trame_travaileffectue ON trame_controlecroise.Id_TravailEffectue=trame_travaileffectue.Id ";
	$req.="WHERE trame_travaileffectue.Id=".$_GET['Id'];
	$result=mysqli_query($bdd,$req);
	$Ligne=mysqli_fetch_array($result);
	
	$read="";
	$disabled="";
	if($_SESSION['Id_PersonneTR']==$Ligne['Id_Preparateur']){
		if(substr($_SESSION['DroitTR'],3,1)=='1'){
			$read="";
			$disabled="";
		}
		else{
			$read="readonly='readonly'";
			$disabled="disabled='disabled'";
		}
	}
	elseif(substr($_SESSION['DroitTR'],2,1)=='1'){
		if(($Ligne['DateAutoC']>"0001-01-01" && $Ligne['DateControle']<="0001-01-01" && $_SESSION['Id_PersonneTR']==$Ligne['Id_Controleur']) || $Ligne['DateControle']>"0001-01-01" || $Ligne['Id_Controleur']==0){
			$read="";
			$disabled="";
		}
		else{
			$read="readonly='readonly'";
			$disabled="disabled='disabled'";
		}
	}
	else{
		$read="readonly='readonly'";
		$disabled="disabled='disabled'";
	}
	
?>
<div id="id_confrmdiv">
	<?php if($_SESSION['Langue']=="EN"){echo "Did you consider the following comments ? ";}else{{echo "Avez-vous pris en compte les commentaires suivants ? ";}} ?>
	<div id="lesCommentaires"></div>
    <button id="id_truebtn"><?php if($_SESSION['Langue']=="EN"){echo "Yes";}else{echo "Oui";} ?></button>
    <button id="id_falsebtn"><?php if($_SESSION['Langue']=="EN"){echo "No";}else{echo "Non";} ?></button>
</div>

	<form id="formulaire" method="POST" action="Ajout_Controle.php" onSubmit="<?php if($disabled==""){ ?>return VerifChamps('<?php echo $_SESSION['Langue'];?>');<?php } ?>">
	<input type="hidden" name="idTravailEffectue" value="<?php echo $Id;?>">
	<input type="hidden" name="id" value="<?php echo $Ligne['Id'];?>">
	<input type="hidden" name="boutonClick" id="boutonClick" value="">
	<input type="hidden" name="id_CLVersion" value="<?php echo $Ligne['Id_CLVersion'];?>">
	<table width="95%" align="center" class="TableCompetences">
		<tr class="TitreColsUsers">
			<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Reference";}else{echo "Référence";} ?> : </td>
			<td width="10%"><?php echo $Ligne['Designation']; ?></td>
			<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Check list";}else{echo "Check-list";} ?> : </td>
			<td width="10%"><?php echo $Ligne['CheckList']." (version ".$Ligne['NumVersion'].") "; ?></td>
			<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Auto control date";}else{echo "Date auto-contrôle";} ?> : </td>
			<td width="10%"><?php echo AfficheDateFR($Ligne['DateAutoC']); ?></td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr class="TitreColsUsers">
			<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Controller";}else{echo "Contrôleur";} ?> : </td>
			<td width="10%">
				
			<?php 
				if($Ligne['DateAutoC']>"0001-01-01" && $Ligne['DateControle']<="0001-01-01" && (substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)==1)){
					echo "<select id=\"controleur\" name=\"controleur\">";
						echo "<option value='0'></option>";
						$req="SELECT new_rh_etatcivil.Id,new_rh_etatcivil.Nom,new_rh_etatcivil.Prenom ";
						$req.="FROM trame_acces LEFT JOIN new_rh_etatcivil ON trame_acces.Id_Personne=new_rh_etatcivil.Id ";
						$req.="WHERE SUBSTR(trame_acces.Droit,3,1)=1
							AND trame_acces.Id_Prestation=".$_SESSION['Id_PrestationTR']." AND new_rh_etatcivil.LoginTrame<>'' ORDER BY Nom, Prenom;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){ 
							while($rowContr=mysqli_fetch_array($result)){
								$selected="";
								if($Ligne['Id_Controleur']==$rowContr['Id']){$selected="selected";}
								echo "<option value='".$rowContr['Id']."' ".$selected.">".$rowContr['Nom']." ".$rowContr['Prenom']."</option>";
							}
						}
					echo "</select>";
				}
				else{
					echo $Ligne['Controleur']; 
				}
			
			?>
				
			</td>
			<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Control date";}else{echo "Date du contrôle";} ?> : </td>
			<td width="10%"><?php echo AfficheDateFR($Ligne['DateControle']); ?></td>
			<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Control again date";}else{echo "Date de recontrôle";} ?> : </td>
			<td width="10%"><?php echo AfficheDateFR($Ligne['DateReControle']); ?></td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr class="TitreColsUsers">
			<td colspan="6" align="center">
				<a href="javascript:Excel(<?php echo $Id; ?>,<?php echo $Ligne['Id']; ?>, <?php echo $Ligne['Id_CLVersion']; ?>)">
					<img src='../../Images/excel.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Excel";}else{echo "Excel";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Excel";}else{echo "Excel";} ?>'>
				</a>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td colspan="6" align="center">
				<table width="100%" align="center">
					<tr>
						<td width="10%"></td>
						<td width="40%"></td>
						<td width="5%"></td>
						<td width="6%"></td>
						<td width="6%" align="center">&nbsp;
						<?php if($disabled==""){ ?>
						<a style="text-decoration:none;" class="Bouton" href="javascript:ToutOK()">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "All OK";}else{echo "Tout OK";}?>&nbsp;</a>
						<?php } ?>
						</td>
						<td width="6%"></td>
						<td width="6%"></td>
						<td width="20%"></td>
					</tr>
					<tr bgcolor="#00325F">
						<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Chapter";}else{echo "Chapitre";} ?></td>
						<td class="EnTeteTableauCompetences" width="40%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Control";}else{echo "Contrôle";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Picture";}else{echo "Image";} ?></td>
						<?php 
							if($_GET['Mode']=="M"){
						?>
						<td class="EnTeteTableauCompetences" width="6%" style="text-decoration:none;color:#ffffff;font-weight:bold;text-align:center;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "AUTO CONTROL";}else{echo "AUTO CONTROLE";} ?></td>
							<?php } ?>
						<td class="EnTeteTableauCompetences" width="6%" style="text-decoration:none;color:#ffffff;font-weight:bold;text-align:center;">&nbsp;OK</td>
						<td class="EnTeteTableauCompetences" width="6%" style="text-decoration:none;color:#ffffff;font-weight:bold;text-align:center;">&nbsp;KO</td>
						<td class="EnTeteTableauCompetences" width="6%" style="text-decoration:none;color:#ffffff;font-weight:bold;text-align:center;">&nbsp;N/A</td>
						<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Comment";}else{echo "Commentaire";} ?></td>
					</tr>
					
					<?php
						//Suppression si doublons créé en erreur 
						$req="
							DELETE FROM trame_controlecroise_contenu 
							WHERE Id_CC=".$Ligne['Id']." 
							AND trame_controlecroise_contenu.Id NOT IN 
							(
								SELECT Tab2.* 
								FROM (
								SELECT Tab.Id
								FROM (
									 SELECT trame_controlecroise_contenu.Id, 
									trame_controlecroise_contenu.Id_CC,
									trame_controlecroise_contenu.Id_Contenu 
									FROM trame_controlecroise_contenu 
									WHERE Id_CC=".$Ligne['Id']." 
									ORDER BY Id) AS Tab
								GROUP BY Tab.Id_Contenu) AS Tab2
							)
							";
						$resultDelete=mysqli_query($bdd,$req);
						
						$req="SELECT trame_cl_version_contenu.Id,Chapitre,Controle,Photo,trame_controlecroise_contenu.Id AS Id_CC_C,trame_controlecroise_contenu.Valeur,trame_controlecroise_contenu.ValeurControle,trame_controlecroise_contenu.Commentaire ";
						$req.="FROM trame_cl_version_contenu LEFT JOIN trame_controlecroise_contenu ON trame_cl_version_contenu.Id=trame_controlecroise_contenu.Id_Contenu ";
						$req.="WHERE Id_VersionCL=".$Ligne['Id_CLVersion']." AND trame_controlecroise_contenu.Id_CC=".$Ligne['Id']." ORDER BY Ordre;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						
						if ($nbResulta>0){
							$couleur="#E1E1D7";
							while($row=mysqli_fetch_array($result)){
							
								?>
									<tr bgcolor="<?php echo $couleur;?>">
										<td width="10%">&nbsp;<?php echo $row['Chapitre'];?></td>
										<td width="40%"><?php echo $row['Controle'];?></td>
										<td width="5%" align="center">
										<?php 
											if($row['Photo']<>""){
												echo "<img onclick=\"afficherIMG('".$row['Photo']."')\" src='../../Images/image.png' border='0'>";
											}
										?>
										</td>
										<?php 
											if($_GET['Mode']=="M"){
										?>
										<td width="6%" align="center">
											<?php echo $row['Valeur'];?>
										</td>
										<?php } ?>
										<td width="6%" align="center">
											<input type="radio" <?php if($row['ValeurControle']=="OK"){echo "checked";} ?> id="<?php echo $row['Id'];?>" name="<?php echo $row['Id'];?>" <?php echo $disabled;?> value="OK" />
										</td>
										<td width="6%" align="center">
											<input type="radio" <?php if($row['ValeurControle']=="KO"){echo "checked";} ?> id="<?php echo $row['Id'];?>" name="<?php echo $row['Id'];?>" <?php echo $disabled;?> value="KO" />
										</td>
										<td width="6%" align="center">
											<input type="radio" <?php if($row['ValeurControle']=="N/A"){echo "checked";} ?> id="<?php echo $row['Id'];?>" name="<?php echo $row['Id'];?>" <?php echo $disabled;?> value="N/A" />
										</td>
										<td width="20%" align="center">
											<input type="text" size="40" id="<?php echo $row['Id']."_Commentaire";?>" name="<?php echo $row['Id']."_Commentaire";?>" <?php echo $read;?> value="<?php echo stripslashes($row['Commentaire']); ?>" />
										</td>
									</tr>
								<?php
							}
						}
					?>
				</table>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td colspan="6" align="center">
				<?php 
					if($disabled==""){ 
				?>
					<input class="Bouton" type="submit" name="btn_save" id="btn_save" value="<?php if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}?>">
				<?php 
				}
					if($Ligne['DateAutoC']>"0001-01-01" && $Ligne['DateControle']<="0001-01-01" && (substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)==1)){
				?>
						<input class="Bouton" type="submit" name="btn_choixContr" onclick="document.getElementById('boutonClick').value='ChoixCTR';" value="<?php if($_SESSION['Langue']=="EN"){echo "Edit controller";}else{echo "Modifier contrôleur";}?>">
				<?php
				}
					if($_GET['Mode']=="M"){
				?>
					<input class="Bouton" type="button" name="btn_recontrole2" onclick="VerifierLaPriseEnCompte();" value="<?php if($_SESSION['Langue']=="EN"){echo "Control again done";}else{echo "Recontrôle effectué";}?>">
					<div id="div_recontrole" style="display:none;"></div>
				<?php	
					}
				?>
				
			</td>
		</tr>
	</table>
	</form>
<?php
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
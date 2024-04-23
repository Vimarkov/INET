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
		function afficherIMG(img){
			var w=open("",'image','weigth=toolbar=no,scrollbars=no,resizable=yes, width=810, height=310');	
			w.document.write("<HTML><BODY onblur=\"window.close();\"><IMG src='ImagesChecklist/"+img+"'>");
			w.document.write("</BODY></HTML>");
			w.focus();
			w.document.close();
		}
		function ToutOK(){
			var inputs = document.getElementsByTagName('INPUT');
			for(l=0;l<inputs.length;l++){
				if(inputs[l].type == "radio") {
					if(inputs[l].value=="OK"){
						inputs[l].checked = true;
					}
				}
			}
		}
		function ToutNA(){
			var inputs = document.getElementsByTagName('INPUT');
			for(l=0;l<inputs.length;l++){
				if(inputs[l].type == "radio") {
					if(inputs[l].value=="N/A"){
						inputs[l].checked = true;
					}
				}
			}
		}
		function VerifChamps(langue){
			//Vérifier que tous les radios bouton sont cochés (la moitié doit être coché)
			var inputs = document.getElementsByTagName('INPUT');
			var nbTotal=0;
			var nbCheck=0;
			for(l=0;l<inputs.length;l++){
				if(inputs[l].type == "radio"){
					nbTotal=nbTotal+1;
					if(inputs[l].checked==true){
						nbCheck=nbCheck+1;
					}
				}
			}
			if(nbCheck==(nbTotal/2)){
				return true;
			}
			else{
				if(langue=="EN"){
					alert('You have not checked all controls.');
				}
				else{
					alert('Vous n\'avez pas coché tous les contrôles.');
				}
				return false;
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
	//Passage du statut travaileffectue à CONTROLE
	$req="UPDATE trame_travaileffectue SET Statut='CONTROLE' WHERE Id=".$_POST['idTravailEffectue'];
	$result=mysqli_query($bdd,$req);
	
	//Passage du statut travaileffectue à CONTROLE
	$req="UPDATE trame_controlecroise SET DateAutoC='".date("Y-m-d")."' WHERE Id=".$_POST['id'];
	$result=mysqli_query($bdd,$req);
	
	//Ajout du contenu dans la table 
	$req="SELECT Id FROM trame_cl_version_contenu WHERE Id_VersionCL=".$_POST['id_CLVersion']." ;";
	$resultContenu=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($resultContenu);
	if ($nbResulta>0){
		while($row=mysqli_fetch_array($resultContenu)){
			$check = "N/A";
			if($_POST[$row['Id']] == "OK"){
				$check = "OK";
			}
			$requete="INSERT INTO trame_controlecroise_contenu (Id_CC,Id_Contenu,Valeur) ";
			$requete.="VALUES (".$_POST['id'].",".$row['Id'].",'".$check."') ";
			$result=mysqli_query($bdd,$requete);
		}
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Id=$_GET['Id'];
	
	
	
	//Vérifier si le controle croisé est bien créé sinon le recréer (cause : suite à un bug, déconnexion airbus)
	$req="SELECT trame_controlecroise.Id ";
	$req.="FROM trame_controlecroise ";
	$req.="WHERE trame_controlecroise.Id_TravailEffectue=".$_GET['Id'];
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	if($nbResulta==0){
		$req="SELECT trame_travaileffectue.Id_Prestation,trame_travaileffectue.Id_Preparateur,trame_travaileffectue.Id_Tache ";
		$req.="FROM trame_travaileffectue ";
		$req.="WHERE trame_travaileffectue.Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$req);
		$LigneTE=mysqli_fetch_array($result);
		
		$Id_CL=0;
		$Niveau=0;
		//Récupérer la CL de la tâche + niveau
		$req="SELECT Id_CL, NiveauControle FROM trame_tache WHERE Id=".$LigneTE['Id_Tache'];
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		if ($nbResulta>0){
			$row=mysqli_fetch_array($result);
			$Id_CL=$row['Id_CL'];
			$Niveau=$row['NiveauControle'];
		}
		
		$Id_CLVersion=0;
		//Recherche de la version du CL
		$req="SELECT Id FROM trame_cl_version WHERE Id_CL=".$Id_CL." AND Valide=1 AND Id_Prestation=".$LigneTE['Id_Prestation']." ";
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		if ($nbResulta>0){
			$row=mysqli_fetch_array($result);
			$Id_CLVersion=$row['Id'];
		}
		
		//Recréer le controle croisé 
		$req="INSERT INTO trame_controlecroise (Id_TravailEffectue,Id_CLVersion,Id_Prestation,Id_Preparateur,NiveauControle,DateCreation) ";
		$req.="VALUES(".$_GET['Id'].",".$Id_CLVersion.",".$LigneTE['Id_Prestation'].",".$LigneTE['Id_Preparateur'].",".$Niveau.",'".date("Y-m-d")."') ";
		$result=mysqli_query($bdd,$req);
	}
	
	$req="SELECT trame_controlecroise.Id, trame_travaileffectue.Designation,trame_controlecroise.Id_CLVersion, ";
	$req.="(SELECT (SELECT Libelle FROM trame_checklist WHERE trame_checklist.Id=trame_cl_version.Id_CL) FROM trame_cl_version WHERE trame_cl_version.Id=trame_controlecroise.Id_CLVersion) AS CheckList, ";
	$req.="(SELECT NumVersion FROM trame_cl_version WHERE trame_cl_version.Id=trame_controlecroise.Id_CLVersion) AS NumVersion ";
	$req.="FROM trame_controlecroise LEFT JOIN trame_travaileffectue ON trame_controlecroise.Id_TravailEffectue=trame_travaileffectue.Id ";
	$req.="WHERE trame_travaileffectue.Id=".$_GET['Id'];
	$result=mysqli_query($bdd,$req);
	$Ligne=mysqli_fetch_array($result);
?>

	<form id="formulaire" method="POST" action="Ajout_AC.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
	<input type="hidden" name="idTravailEffectue" value="<?php echo $Id;?>">
	<input type="hidden" name="id" value="<?php echo $Ligne['Id'];?>">
	<input type="hidden" name="id_CLVersion" value="<?php echo $Ligne['Id_CLVersion'];?>">
	<table width="95%" align="center" class="TableCompetences">
		<tr class="TitreColsUsers">
			<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Reference";}else{echo "Référence";} ?> : </td>
			<td width="10%"><?php echo $Ligne['Designation']; ?></td>
			<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Check list";}else{echo "Check-list";} ?> : </td>
			<td width="60%"><?php echo $Ligne['CheckList']." (version ".$Ligne['NumVersion'].") "; ?></td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td colspan="5" align="center">
				<table width="100%" align="center">
					<tr>
						<td width="15%"></td>
						<td width="65%"></td>
						<td width="5%"></td>
						<td width="8%" align="center">&nbsp;
						<a style="text-decoration:none;" class="Bouton" href="javascript:ToutOK()">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "All OK";}else{echo "Tout OK";}?>&nbsp;</a>
						</td>
						<td width="8%" align="center">&nbsp;
						<a style="text-decoration:none;" class="Bouton" href="javascript:ToutNA()">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "All NA";}else{echo "Tout NA";}?>&nbsp;</a>
						</td>
					</tr>
					<tr bgcolor="#00325F">
						<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Chapter";}else{echo "Chapitre";} ?></td>
						<td class="EnTeteTableauCompetences" width="65%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Control";}else{echo "Contrôle";} ?></td>
						<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Picture";}else{echo "Image";} ?></td>
						<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#ffffff;font-weight:bold;text-align:center;">&nbsp;OK</td>
						<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#ffffff;font-weight:bold;text-align:center;">&nbsp;N/A</td>
					</tr>
					
					<?php
						$req="SELECT Id,Chapitre,Controle,Photo ";
						$req.="FROM trame_cl_version_contenu WHERE Id_VersionCL=".$Ligne['Id_CLVersion']." ORDER BY Ordre;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						
						if ($nbResulta>0){
							$couleur="#E1E1D7";
							while($row=mysqli_fetch_array($result)){
							
								?>
									<tr bgcolor="<?php echo $couleur;?>">
										<td width="15%">&nbsp;<?php echo $row['Chapitre'];?></td>
										<td width="65%"><?php echo $row['Controle'];?></td>
										<td width="5%" align="center">
										<?php 
											if($row['Photo']<>""){
												echo "<img onclick=\"afficherIMG('".$row['Photo']."')\" src='../../Images/image.png' border='0'>";
											}
										?>
										</td>
										<td width="8%" align="center">
											<input type="radio" id="<?php echo $row['Id'];?>" name="<?php echo $row['Id'];?>" value="OK" />
										</td>
										<td width="8%" align="center">
											<input type="radio" id="<?php echo $row['Id'];?>" name="<?php echo $row['Id'];?>" value="N/A" />
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
			<td colspan="5" align="center">
				<input class="Bouton" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}?>">
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
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="Anomalie2.js?t=<?php echo time();?>"></script>
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
	$requete="INSERT INTO trame_anomalie (Id_Prestation,Id_TravailEffectue,Id_Createur,DateAnomalie,Reference,Id_WP,Probleme,ActionCurative,AnalyseCause,ActionPreventive,";
	$requete.="Id_Origine,Id_Ponderation,Id_Responsable,Id_FamilleErreur1,Id_FamilleErreur2,DatePrevisionnelle,DateReport,DateCloture,Observation) ";
	$requete.="VALUES (".$_SESSION['Id_PrestationTR'].",".$_POST['Id'].",".$_SESSION['Id_PersonneTR'].",'".TrsfDate_($_POST['dateCreation'])."','".$_POST['reference']."',".$_POST['wp'].",'".addslashes($_POST['probleme'])."',";
	$requete.="'".addslashes($_POST['actionCurative'])."','".addslashes($_POST['analyseCause'])."','".addslashes($_POST['actionPreventive'])."',";
	$requete.="".$_POST['origine'].",".$_POST['ponderation'].",".$_POST['responsable'].",".$_POST['familleErreur1'].",".$_POST['familleErreur2'].",";
	$requete.="'".TrsfDate_($_POST['datePrevisionnelle'])."','".TrsfDate_($_POST['dateReport'])."','".TrsfDate_($_POST['dateCloture'])."',";
	$requete.="'".addslashes($_POST['observation'])."') ";
	$result=mysqli_query($bdd,$requete);
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET){
	//Mode ajout ou modification
	$Id=$_GET['Id'];
	
	$req="SELECT Id, Id_Tache,Statut,Id_WP,Designation,DatePreparateur ";
	$req.="FROM trame_travaileffectue WHERE Id=".$_GET['Id'];
	$result=mysqli_query($bdd,$req);
	$Ligne=mysqli_fetch_array($result);
?>

	<form id="formulaire" method="POST" action="Ajout_Anomalie2.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
		<input type="hidden" name="Id" value="<?php echo $Id; ?>">
	<table width="95%" align="center" class="TableCompetences">
		<tr>
			<td width="20%" class="Libelle">
				<?php if($_SESSION['Langue']=="EN"){echo "Output data number ";}else{echo "N° donnée de sortie ";} ?>
				<img src="../../Images/etoile.png" width="8" height="8" border="0">
			</td>
			<td width="30%">
				<input type="text" id="reference" size="30" name="reference" onchange="VerifExistance('<?php echo $_SESSION['Langue']; ?>')" value="<?php echo $Ligne['Designation']; ?>" />
			</td>
			<td align="left" colspan="2">
				<div id="existanceLivrable"></div>
			</td>
			<td width="10%" class="Libelle">
				<?php if($_SESSION['Langue']=="EN"){echo "Workpackage";}else{echo "Workpackage";} ?>
				<img src="../../Images/etoile.png" width="8" height="8" border="0">
			</td>
			<td width="20%">
				<select id="wp" name="wp">
					<?php
						echo"<option value='0'></option>";
						$req="SELECT Id, Libelle, Supprime, Actif FROM trame_wp WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle ;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowWP=mysqli_fetch_array($result)){
								$selected="";
								if($rowWP['Id']==$Ligne['Id_WP']){$selected="selected";}
								if(($rowWP['Supprime']==false && $rowWP['Actif']==false) || $rowWP['Id']==$Ligne['Id_WP']){
									echo "<option value='".$rowWP['Id']."' ".$selected.">".$rowWP['Libelle']."</option>";
								}
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr class="TitreColsUsers">
			<td class="Libelle">
				<?php if($_SESSION['Langue']=="EN"){echo "Date of the anomaly ";}else{echo "Date de l'anomalie ";} ?>
				<img src="../../Images/etoile.png" width="8" height="8" border="0">
			</td>
			<td colspan="6">
				<input type="date" id="dateCreation" size="12" name="dateCreation" value="<?php echo AfficheDateFR($Ligne['DatePreparateur']); ?>" />
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr class="TitreColsUsers">
			<td class="Libelle">
				<?php if($_SESSION['Langue']=="EN"){echo "Problem ";}else{echo "Problème ";} ?>
				<img src="../../Images/etoile.png" width="8" height="8" border="0">
			</td>
			<td colspan="6">
				<input type="text" id="probleme" size="120" name="probleme" value="" />
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr class="TitreColsUsers">
			<td class="Libelle">
				<?php if($_SESSION['Langue']=="EN"){echo "Solution ";}else{echo "Solution ";} ?>
				<img src="../../Images/etoile.png" width="8" height="8" border="0">
			</td>
			<td colspan="6">
				<input type="text" id="actionCurative" size="120" name="actionCurative" value="" />
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr class="TitreColsUsers">
			<td class="Libelle">
				<?php if($_SESSION['Langue']=="EN"){echo "Causes analysis ";}else{echo "Analyse des causes ";} ?>
				<img src="../../Images/etoile.png" width="8" height="8" border="0">
			</td>
			<td colspan="6">
				<input type="text" id="analyseCause" size="120" name="analyseCause" value="" />
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr class="TitreColsUsers">
			<td class="Libelle">
				<?php if($_SESSION['Langue']=="EN"){echo "Preventive action ";}else{echo "Action préventive ";} ?>
			</td>
			<td colspan="6">
				<input type="text" id="actionPreventive" size="120" name="actionPreventive" value="" />
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr class="TitreColsUsers">
			<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Origin ";}else{echo "Origine ";} ?><img src="../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td>
				<select id="origine" name="origine">
					<?php
						echo"<option value='0'></option>";
						$req="SELECT Id, Libelle, Supprime FROM trame_origine WHERE Id_Prestation=".$_SESSION['Id_PrestationTR'].";";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowOrigine=mysqli_fetch_array($result)){
								$selected="";
								if($rowOrigine['Supprime']==false){
									echo "<option value='".$rowOrigine['Id']."' ".$selected.">".$rowOrigine['Libelle']."</option>";
								}
							}
						}
					?>
				</select>
			</td>
			<td class="Libelle" id='lehover'>
				<?php 
					$infoBulle="\n<span>";
					$req="SELECT Libelle, Description FROM trame_ponderation WHERE Supprime=0 AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($rowPonderation=mysqli_fetch_array($result)){
							$infoBulle.=$rowPonderation['Libelle']." ".$rowPonderation['Description']."<br>";
						}
					}
					$infoBulle.="</span>\n";
					if($_SESSION['Langue']=="EN"){echo $infoBulle."Weighting ";}else{echo $infoBulle."Pondération ";} 
				?>
			</td>
			<td>
				<select id="ponderation" name="ponderation">
					<?php
						echo"<option value='0'></option>";
						$req="SELECT Id, Libelle, Supprime FROM trame_ponderation WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowPonderation=mysqli_fetch_array($result)){
								$selected="";
								if($rowPonderation['Supprime']==false){
									echo "<option value='".$rowPonderation['Id']."' ".$selected.">".$rowPonderation['Libelle']."</option>";
								}
							}
						}
					?>
				</select>
			</td>
			<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Responsible ";}else{echo "Responsable ";} ?><img src="../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td>
				<select id="responsable" name="responsable">
					<?php
						echo"<option value='0'></option>";
						$req="SELECT Id, Libelle, Supprime FROM trame_responsable WHERE Id_Prestation=".$_SESSION['Id_PrestationTR'].";";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowResp=mysqli_fetch_array($result)){
								$selected="";
								if($rowResp['Supprime']==false){
									echo "<option value='".$rowResp['Id']."' ".$selected.">".$rowResp['Libelle']."</option>";
								}
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr class="TitreColsUsers">
			<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Error family 1 ";}else{echo "Famille d'erreur 1 ";} ?><img src="../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td>
				<select id="familleErreur1" name="familleErreur1">
					<?php
						echo"<option value='0'></option>";
						$req="SELECT Id, Libelle, Supprime FROM trame_familleerreur WHERE Id_Prestation=".$_SESSION['Id_PrestationTR'].";";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowFamille=mysqli_fetch_array($result)){
								$selected="";
								if($rowFamille['Supprime']==false){
									echo "<option value='".$rowFamille['Id']."' ".$selected.">".$rowFamille['Libelle']."</option>";
								}
							}
						}
					?>
				</select>
			</td>
			<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Error family 2";}else{echo "Famille d'erreur 2";} ?></td>
			<td>
				<select id="familleErreur2" name="familleErreur2">
					<?php
						echo"<option value='0'></option>";
						$req="SELECT Id, Libelle, Supprime FROM trame_familleerreur WHERE Id_Prestation=".$_SESSION['Id_PrestationTR'].";";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowFamille=mysqli_fetch_array($result)){
								$selected="";
								if($rowFamille['Supprime']==false){
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
			<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Expected date ";}else{echo "Date prévisionnelle ";} ?></td>
			<td>
				<input type="date" name="datePrevisionnelle" id="datePrevisionnelle" size="10" value="" />
			</td>
			<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Date of reporting ";}else{echo "Date report ";} ?></td>
			<td>
				<input type="date" name="dateReport" id="dateReport" size="10" value="" />
			</td>
			<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Closing date ";}else{echo "Date de clôture ";} ?></td>
			<td>
				<input type="date" name="dateCloture" id="dateCloture" size="10" value="" />
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Observation ";}else{echo "Observation ";} ?></td>
			<td colspan="6">
				<textarea id="observation" name="observation" rows=3 cols=100 style="resize:none;"></textarea>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td colspan="8" align="center">
				<input class="Bouton" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}?>">
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
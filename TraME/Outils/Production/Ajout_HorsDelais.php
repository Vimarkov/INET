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
			window.opener.location = "HorsDelais.php";
			window.close();
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
	$tab = explode(";",$_POST['id']);
	foreach($tab as $IdTravail){
		if($IdTravail<>""){
			$requete="UPDATE trame_travaileffectue SET ";
			$requete.="Id_ResponsableDelai=".$_POST['responsable'].",";
			$requete.="Id_CauseDelai=".$_POST['cause'].",";
			$requete.="CommentaireDelai='".addslashes($_POST['commentaire'])."' ";
			$requete.=" WHERE Id=".$IdTravail;
			$result=mysqli_query($bdd,$requete);
		}
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Id=$_GET['Id'];
	$tab = explode(";",$Id);
	$NbElement=sizeof($tab);
	if($NbElement==2){
		$req="SELECT Id, Id_ResponsableDelai,Id_CauseDelai,CommentaireDelai ";
		$req.="FROM trame_travaileffectue WHERE Id=".$tab[0];
		$result=mysqli_query($bdd,$req);
		$Ligne=mysqli_fetch_array($result);
	}
?>

	<form id="formulaire" method="POST" action="Ajout_HorsDelais.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
	<input type="hidden" name="id" value="<?php echo $Id;?>">
	<table width="95%" align="center" class="TableCompetences">
		<tr class="TitreColsUsers">
			<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Responsible for delays";}else{echo "Responsable délais";} ?></td>
			<td>
				<select id="responsable" name="responsable">
					<?php
						echo"<option value='0'></option>";
						$req="SELECT Id, Libelle, Supprime FROM trame_responsabledelais WHERE Id_Prestation=".$_SESSION['Id_PrestationTR'].";";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowResp=mysqli_fetch_array($result)){
								$selected="";
								if($NbElement==2){
									if($rowResp['Id']==$Ligne['Id_ResponsableDelai']){$selected="selected";}
									if($rowResp['Supprime']==false  || $rowResp['Id']==$Ligne['Id_ResponsableDelai']){
										echo "<option value='".$rowResp['Id']."' ".$selected.">".$rowResp['Libelle']."</option>";
									}
								}
								else{
									if($rowResp['Supprime']==false){
										echo "<option value='".$rowResp['Id']."' ".$selected.">".$rowResp['Libelle']."</option>";
									}
								}
							}
						}
					?>
				</select>
			</td>
			<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Cause of delay";}else{echo "Cause délais";} ?></td>
			<td>
				<select id="cause" name="cause">
					<?php
						echo"<option value='0'></option>";
						$req="SELECT Id, Libelle, Supprime FROM trame_causedelais WHERE Id_Prestation=".$_SESSION['Id_PrestationTR'].";";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowCause=mysqli_fetch_array($result)){
								$selected="";
								if($NbElement==2){
									if($rowCause['Id']==$Ligne['Id_CauseDelai']){$selected="selected";}
								}
								if($rowCause['Supprime']==false  || $rowCause['Id']==$Ligne['Id_CauseDelai']){
									echo "<option value='".$rowCause['Id']."' ".$selected.">".$rowCause['Libelle']."</option>";
								}
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Comment ";}else{echo "Commentaire ";} ?></td>
		</tr>
		<tr>
			<td colspan="4">
				<textarea id="commentaire" name="commentaire" rows=3 cols=100 style="resize:none;"><?php if($NbElement==2){echo stripslashes($Ligne['CommentaireDelai']);} ?></textarea>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td colspan="4" align="center">
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
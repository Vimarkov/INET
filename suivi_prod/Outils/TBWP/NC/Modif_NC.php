<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>	
	<script>
		function FermerEtRecharger(){
			window.opener.location = "Liste_NC.php";
			window.close();
		}
		function nombre(champ){
			var chiffres = new RegExp("[0-9\.]"); /* Modifier pour : var chiffres = new RegExp("[0-9]"); */
			var verif;
			var points = 0; /* Supprimer cette ligne */

			for(x = 0; x < champ.value.length; x++)
			{
				verif = chiffres.test(champ.value.charAt(x));
				if(champ.value.charAt(x) == "."){points++;} /* Supprimer cette ligne */
				if(points > 1){verif = false; points = 1;} /* Supprimer cette ligne */
				if(verif == false){champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;}
			}
		}
		Liste_Poste = new Array();
		function VerifChamps(){
			if(formulaire.msn.value==''){alert('Vous n\'avez pas renseigné le MSN.');return false;}
			if(formulaire.NumNC.value==''){alert('Vous n\'avez pas renseigné le n° NC.');return false;}
			if(formulaire.WO_S01Lie.value==''){alert('Vous n\'avez pas renseigné le WO S01 lié.');return false;}
			return true;
		}
	</script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../../JS/js/jquery-ui-1.8.5.min.js"></script>
</head>
<?php
session_start();
require("../../Connexioni.php");
require("../../Fonctions.php");

//Verifier si Google CHROME (true) ou Autre (fale)
if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
else {$NavigOk = false;}

Ecrire_Code_JS_Init_Date();

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
if($_POST){
	if(isset($_POST['btnEnregistrer'])){
		if($_POST['idNC']==0){
			//Ajout NC
			$req="INSERT INTO sp_nc (MSN,NumNC,ImputationAAA,DateCreation,Id_TypeDefaut,Id_Createur,Commentaire) VALUES ";
			$req.="(".addslashes($_POST['msn']).",'".$_POST['NumNC']."',".$_POST['ImputationAAA'].",'".$DateJour."',".$_POST['typeDefaut'].",".$_POST['idPersonne'].",'".addslashes($_POST['commentaire'])."')";
			$resultInsert=mysqli_query($bdd,$req);
		}
		else{
			//Mise à jour NC
			$req="UPDATE sp_nc SET ";
			$req.="MSN=".addslashes($_POST['msn']).",";
			$req.="NumNC='".addslashes($_POST['NumNC'])."',";
			$req.="ImputationAAA=".$_POST['ImputationAAA'].",";
			$req.="Id_TypeDefaut=".$_POST['typeDefaut'].", ";
			$req.="Commentaire='".addslashes($_POST['commentaire'])."' ";
			$req.="WHERE Id=".$_POST['idNC']."";
			$resultUpdate=mysqli_query($bdd,$req);
			
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
if($_GET){
	$IdPersonne=$_GET['Id_Personne'];
	$NC=$_GET['Id'];
	$titre="";
	if($_GET['Mode']=="A"){
		$titre="Ajouter une NC";
	}
	elseif($_GET['Mode']=="M"){
		$titre="Modifier une NC";
		$req="SELECT sp_nc.Id,sp_nc.MSN,sp_nc.NumNC,sp_nc.ImputationAAA,sp_nc.DateCreation,sp_nc.Id_TypeDefaut,sp_nc.Id_Createur,sp_nc.Commentaire,";
		$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_nc.Id_Createur) AS Createur ";
		$req.="FROM sp_nc ";
		$req.="WHERE sp_nc.Id=".$NC;
		$result=mysqli_query($bdd,$req);
		$row=mysqli_fetch_array($result);
	}
	elseif($_GET['Mode']=="S"){
		//Suppression du NC
		$req="DELETE FROM sp_nc WHERE Id=".$NC;
		$resultSuppr=mysqli_query($bdd,$req);
		echo "<script>FermerEtRecharger();</script>";
	}
}
?>
<form id="formulaire" class="test" method="POST" action="Modif_NC.php" onSubmit="return VerifChamps();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php echo $titre;?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
			<tr style="display:none;"><td><input type="text" name="idNC" value="<?php echo $NC;?>"></td></tr>
			<tr style="display:none;"><td><input type="text" name="idPersonne" value="<?php echo $IdPersonne;?>"></td></tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width="13%" class="Libelle">&nbsp; MSN : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
				<td width="20%">
					<input onKeyUp="nombre(this)" id="msn" name="msn" size="5" value="<?php if($_GET["Mode"]=="M"){echo $row['MSN'];}?>"></td>
				</td>
				<td width="13%" class="Libelle">&nbsp; N° NC : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
				<td width="20%">
					<input id="NumNC" name="NumNC" value="<?php if($_GET["Mode"]=="M"){echo $row['NumNC'];}?>"></td>
				</td>
				<td colspan="2" align="left">
				</td>
				<td width="20%"></td>

			</tr>
			<?php
				if($_GET["Mode"]=="M"){
			?>
				<tr><td height="4"></td></tr>
				<tr>
					<td width="13%" class="Libelle">&nbsp; Créateur : </td>
					<td width="20%">
						<?php echo $row['Createur'];?>
					</td>
					<td width="13%" class="Libelle">&nbsp; Date de création : </td>
					<td width="20%">
						<?php echo $row['DateCreation'];?>
					</td>
					<td colspan="2" align="left">
					</td>
					<td width="20%"></td>
				</tr>
			<?php
				}
			?>
			<tr><td height="4"></td></tr>
			<tr>
				<td width="13%" class="Libelle">&nbsp; Type de défaut : </td>
				<td width="20%">
					<div id="postes">
						<select id="typeDefaut" name="typeDefaut">
							<option name="0" value="0" selected></option>
							<?php
								$req="SELECT Id, Libelle FROM sp_typedefautnc WHERE Supprime=false ORDER BY Libelle;";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									while($rowTypeDefaut=mysqli_fetch_array($result)){
										$selected="";
										if($_GET["Mode"]=="M"){
											if($rowTypeDefaut['Id']==$row['Id_TypeDefaut']){$selected="selected";}
										}
										echo "<option name='".$rowTypeDefaut['Id']."' value='".$rowTypeDefaut['Id']."' ".$selected.">".$rowTypeDefaut['Libelle']."</option>";
									}
								}
							?>
						</select>
					</div>
				</td>
				<td width="13%" class="Libelle" valign="top">&nbsp; Imputation AAA : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
				<td width="20%" valign="top">
					<select id="ImputationAAA" name="ImputationAAA">
						<option name="1" value="1" <?php if($_GET["Mode"]=="M"){if($row['ImputationAAA']=="1"){echo "selected";}} ?>>Oui</option>
						<option name="0" value="0" <?php if($_GET["Mode"]=="M"){if($row['ImputationAAA']=="0"){echo "selected";}} ?>>Non</option>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width="13%" class="Libelle">&nbsp; Commentaire : </td>
				<td width="20%" colspan="4">
					<textarea id="commentaire" name="commentaire" rows="5" cols="50" style="resize:none;" ><?php if($_GET["Mode"]=="M"){echo stripslashes($row['Commentaire']);} ?></textarea>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr><td colspan="6">&nbsp;&nbsp;&nbsp;&nbsp;<img src='../../../Images/etoile.png' width='8' height='8' border='0'> Informations obligatoires à remplir</td></tr>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<?php
		if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
		?>
		<td colspan="6" align="center"><input class="Bouton" type="submit" name="btnEnregistrer" value="Enregistrer"></td>
		<?php
		}
		?>
	</tr>
</table>
</form>
<?php
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
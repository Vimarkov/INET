<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps(langue){
			if(langue=="EN"){
				if(formulaire.personne.value=='0'){alert('You didn\'t enter the user.');return false;}
				if(formulaire.Prepa.checked==false && formulaire.Resp.checked==false  && formulaire.Cont.checked==false  && formulaire.RespP.checked==false && formulaire.CE.checked==false){alert('You didn\'t enter the access rights.');return false;}
			}
			else{
				if(formulaire.personne.value=='0'){alert('Vous n\'avez pas renseigné la personne.');return false;}
				if(formulaire.Prepa.checked==false && formulaire.Resp.checked==false  && formulaire.Cont.checked==false  && formulaire.RespP.checked==false && formulaire.CE.checked==false){alert('Vous n\'avez pas renseigné les droits.');return false;}
			}
			return true;

		}
		function FermerEtRecharger(){
			opener.location.reload();
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
$mdp="aaa01";
if($_POST){
	if($_POST['Mode']=="A"){
		//     DROIT     =      Préparateur    Responsable prestation        Contrôleur      Resp/Coorp projet Chef Equipe
		$req="SELECT Nom,Prenom, LoginTrame, MdpTrame FROM new_rh_etatcivil WHERE Id=".$_POST['personne'];
		$result=mysqli_query($bdd,$req);
		$row=mysqli_fetch_array($result);
		
		//Verifier si cette personne n'a pas dejà accès
		if($row['LoginTrame'] == ""){
			$login=str_replace("'","",strtolower(substr($row['Prenom'],0,1).$row['Nom']));
			$login=str_replace(" ","",$login);
			
			//Vérifier existance Login dans la base
			$select = "SELECT Id FROM new_rh_etatcivil WHERE LoginTrame LIKE '".$login."%'";
			$result=mysqli_query($bdd,$select);
			$nbResulta=mysqli_num_rows($result);
			
			if($nbResulta>0){$login=$login.$nbResulta;}
			$requete="UPDATE new_rh_etatcivil SET ";
			$requete.="LoginTrame='".$login."', ";
			$requete.="MdpTrame='".$mdp."' ";
			$requete.=" WHERE Id=".$_POST['personne'];
			$result=mysqli_query($bdd,$requete);
		}
		
		$droit="";
		if(isset($_POST['Prepa'])){$droit="1";}else{$droit="0";}
		if(isset($_POST['Resp'])){$droit.="1";}else{$droit.="0";}
		if(isset($_POST['Cont'])){$droit.="1";}else{$droit.="0";}
		if(isset($_POST['RespP'])){$droit.="1";}else{$droit.="0";}
		if(isset($_POST['CE'])){$droit.="1";}else{$droit.="0";}
		
		$req="DELETE FROM trame_acces WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_Personne=".$_POST['personne'];
		$result=mysqli_query($bdd,$req);
		
		$req="INSERT INTO trame_acces (Id_Personne,Id_Prestation,Droit) VALUES (".$_POST['personne'].",".$_SESSION['Id_PrestationTR'].",'".$droit."')";
		echo $req;
		$result=mysqli_query($bdd,$req);
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		
		if(isset($_POST['Prepa'])){$droit="1";}else{$droit="0";}
		if(isset($_POST['Resp'])){$droit.="1";}else{$droit.="0";}
		if(isset($_POST['Cont'])){$droit.="1";}else{$droit.="0";}
		if(isset($_POST['RespP'])){$droit.="1";}else{$droit.="0";}
		if(isset($_POST['CE'])){$droit.="1";}else{$droit.="0";}
		
		$requete="UPDATE new_rh_etatcivil SET ";
		$requete.="Matricule='".$_POST['matricule']."', ";
		$requete.="EmailPro='".$_POST['Email']."'";
		$requete.=" WHERE Id=".$_POST['personne'];
		$result=mysqli_query($bdd,$requete);
		
		$req="DELETE FROM trame_acces WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_Personne=".$_POST['personne'];
		$result=mysqli_query($bdd,$req);
		
		$req="INSERT INTO trame_acces (Id_Personne,Id_Prestation,Droit) VALUES (".$_POST['personne'].",".$_SESSION['Id_PrestationTR'].",'".$droit."')";
		$result=mysqli_query($bdd,$req);
		
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
		if($_GET['Id']!='0'){
			$result=mysqli_query($bdd,"SELECT Id, Matricule,EmailPro FROM new_rh_etatcivil WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
			
			$result=mysqli_query($bdd,"SELECT Id, Droit FROM trame_acces WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_Personne=".$_GET['Id']);
			$Ligne2=mysqli_fetch_array($result);
		}
?>

		<form id="formulaire" method="POST" action="Ajout_Utilisateur.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id_Personne" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<table width="95%" height="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "User";}else{echo "Personne";} ?></td>
				<td>
					<select id="personne" name="personne">
					<?php
					if($_GET['Mode']=="A"){
						echo"<option name='0' value='0'></option>";
						$req="SELECT new_rh_etatcivil.Id, Nom, Prenom FROM new_rh_etatcivil WHERE NOT EXISTS (SELECT Id FROM trame_acces WHERE trame_acces.Id_Prestation=".$_SESSION['Id_PrestationTR']." AND trame_acces.Id_Personne=new_rh_etatcivil.Id) ORDER BY Nom, Prenom;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($row=mysqli_fetch_array($result)){
								echo "<option name='".$row['Id']."' value='".$row['Id']."'>".$row['Nom']." ".$row['Prenom']."</option>";
							}
						}
					}
					if($_GET['Mode']=="M"){
						$req="SELECT Id, Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$Ligne['Id']." ORDER BY Nom, Prenom;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($row=mysqli_fetch_array($result)){
								echo "<option name='".$row['Id']."' value='".$row['Id']."'>".$row['Nom']." ".$row['Prenom']."</option>";
							}
						}
					}
					?>
					</select>
				</td>
			</tr>
			<?php
				if($_GET['Id']!='0'){
			?>
			<tr class="TitreColsUsers">
				<td>NG/ST </td>
				<?php
					$matricule=$Ligne['Matricule'];
				?>
				<td><input type="text" name="matricule" value="<?php echo $matricule;?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td>Email </td>
				<?php
					$email=$Ligne['EmailPro'];
				?>
				<td><input type="text" name="Email" value="<?php echo $email;?>"></td>
			</tr>
			<?php
				}
			?>
			<tr class="TitreColsUsers">
				<td><?php if($_SESSION['Langue']=="EN"){echo "Access";}else{echo "Droit";} ?> : </td>
				<td colspan="3">
				<table><tr>
				<?php
					$Prepa="";
					$Resp="";
					$Cont="";
					$RespP="";
					$CE="";
					if($_GET['Mode']=="M"){
					if(substr($Ligne2['Droit'],0,1)=="1"){$Prepa="checked";}
					if(substr($Ligne2['Droit'],1,1)=="1"){$Resp="checked";}
					if(substr($Ligne2['Droit'],2,1)=="1"){$Cont="checked";}
					if(substr($Ligne2['Droit'],3,1)=="1"){$RespP="checked";}
					if(substr($Ligne2['Droit'],4,1)=="1"){$CE="checked";}
					}
				?>
				<td><input type='checkbox' id="Prepa" name='Prepa' value='Prepa' <?php echo $Prepa;?>><?php if($_SESSION['Langue']=="EN"){echo "Collaborator";}else{echo "Collaborateur";} ?> &nbsp;&nbsp;</td>
				<td><input type='checkbox' id="Cont" name='Cont' value='Cont' <?php echo $Cont;?>><?php if($_SESSION['Langue']=="EN"){echo "Controller";}else{echo "Contrôleur";} ?> &nbsp;&nbsp;</td>
				<td><input type='checkbox' id="CE" name='CE' value='CE' <?php echo $CE;?>><?php if($_SESSION['Langue']=="EN"){echo "Team Leader / Leader";}else{echo "Chef d'équipe/Leader";} ?> &nbsp;&nbsp;</td>
				<td><input type='checkbox' id="Resp" name='Resp' value='Resp' <?php echo $Resp;?>><?php if($_SESSION['Langue']=="EN"){echo "Site Manager";}else{echo "Responsable prestation";} ?> &nbsp;&nbsp;</td>
				<td><input type='checkbox' id="RespP" name='RespP' value='RespP' <?php echo $RespP;?>><?php if($_SESSION['Langue']=="EN"){echo "Project Manager";}else{echo "Resp/Coord projet";} ?> &nbsp;&nbsp;</td>
				</tr></table>
				</td>
			</tr>
			<tr>
				<td colspan="4" align="center"><input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="M"){if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}}else{if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}}?>"></td>
			</tr>
		</table>
		</form>
<?php
	}
	elseif($_GET['Mode']=="S")
	//Mode suppression
	{
		$req="DELETE FROM trame_acces WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_Personne=".$_GET['Id'];
		$result=mysqli_query($bdd,$req);
		
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_GET['Mode']=="R")
	//Mode réinitialisation
	{
		$requete="UPDATE new_rh_etatcivil SET ";
		$requete.="MdpTrame='aaa01' ";
		$requete.=" WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
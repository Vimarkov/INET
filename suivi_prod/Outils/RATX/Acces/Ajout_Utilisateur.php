<html>
<head>
	<title>Extranet de la société Assistance Aéronautique et Aérospatiale</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps(){
			if(formulaire.personne.value=='0'){alert('Vous n\'avez pas renseigné la personne.');return false;}
			else{
				if(formulaire.ST.checked==false && formulaire.CE.checked==false && formulaire.IQ.checked==false && formulaire.Admin.checked==false && formulaire.Compagnon.checked==false){
					alert('Vous n\'avez pas renseigné les droits.');
					return false;
				}
				else{return true;}
			}

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
require("../../Connexioni.php");
$mdp="aaa01";
if($_POST){
	if($_POST['Mode']=="A"){
		$req="SELECT Nom,Prenom, LoginSP, MdpSP FROM new_rh_etatcivil WHERE Id=".$_POST['personne'];
		$result=mysqli_query($bdd,$req);
		$row=mysqli_fetch_array($result);
		
		//Verifier si cette personne a dejà accès
		if($row['LoginSP'] == ""){
			$login=str_replace("'","",strtolower(substr($row['Prenom'],0,1).$row['Nom']));
			$login=str_replace(" ","",$login);
			
			//Vérifier existance Login dans la base
			$select = "SELECT Id FROM new_rh_etatcivil WHERE LoginSP LIKE '".$login."%'";
			$result=mysqli_query($bdd,$select);
			$nbResulta=mysqli_num_rows($result);
			
			if($nbResulta>0){$login=$login.$nbResulta;}
			$requete="UPDATE new_rh_etatcivil SET ";
			$requete.="LoginSP='".$login."', ";
			$requete.="MdpSP='".$mdp."' ";
			$requete.=" WHERE Id=".$_POST['personne'];
			$result=mysqli_query($bdd,$requete);
		}
		
		$droit="";
		if(isset($_POST['ST'])){$droit="1";}else{$droit="0";}
		if(isset($_POST['CE'])){$droit.="1";}else{$droit.="0";}
		if(isset($_POST['Compagnon'])){$droit.="1";}else{$droit.="0";}
		if(isset($_POST['Admin'])){$droit.="1";}else{$droit.="0";}
		if(isset($_POST['IQ'])){$droit.="1";}else{$droit.="0";}
		
		$req="DELETE FROM sp_acces WHERE Id_Prestation=834 AND Id_Personne=".$_POST['personne'];
		$result=mysqli_query($bdd,$req);
		
		$req="INSERT INTO sp_acces (Id_Personne,Id_Prestation,Droit) VALUES (".$_POST['personne'].",834,'".$droit."')";
		$result=mysqli_query($bdd,$req);
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		
		if(isset($_POST['ST'])){$droit="1";}else{$droit="0";}
		if(isset($_POST['CE'])){$droit.="1";}else{$droit.="0";}
		if(isset($_POST['Compagnon'])){$droit.="1";}else{$droit.="0";}
		if(isset($_POST['Admin'])){$droit.="1";}else{$droit.="0";}
		if(isset($_POST['IQ'])){$droit.="1";}else{$droit.="0";}
		
		$requete="UPDATE new_rh_etatcivil SET ";
		$requete.="Matricule='".$_POST['matricule']."', ";
		$requete.="EmailPro='".$_POST['Email']."'";
		$requete.=" WHERE Id=".$_POST['personne'];
		$result=mysqli_query($bdd,$requete);
		
		$req="DELETE FROM sp_acces WHERE Id_Prestation=834 AND Id_Personne=".$_POST['personne'];
		$result=mysqli_query($bdd,$req);
		
		$req="INSERT INTO sp_acces (Id_Personne,Id_Prestation,Droit) VALUES (".$_POST['personne'].",834,'".$droit."')";
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
			
			$result=mysqli_query($bdd,"SELECT Id, Droit FROM sp_acces WHERE Id_Prestation=834 AND Id_Personne=".$_GET['Id']);
			$Ligne2=mysqli_fetch_array($result);
		}
?>

		<form id="formulaire" method="POST" action="Ajout_Utilisateur.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id_Personne" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<input type="hidden" name="DroitSP" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];} ?>">
		<table width="95%" height="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td>Personne </td>
				<td>
					<select id="personne" name="personne">
					<?php
					if($_GET['Mode']=="A"){
						echo"<option name='0' value='0'></option>";
						$req="SELECT new_rh_etatcivil.Id, Nom, Prenom FROM new_rh_etatcivil WHERE NOT EXISTS (SELECT Id FROM sp_acces WHERE sp_acces.Id_Prestation=834 AND sp_acces.Id_Personne=new_rh_etatcivil.Id) ORDER BY Nom, Prenom;";
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
					else
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
				<td>Droit : </td>
				<td>
				<table><tr>
				<?php
					$CE="";
					$ST="";
					$IQ="";
					$Admin="";
					$Compagnon="";
					if($_GET['Mode']=="M"){
					if(substr($Ligne2['Droit'],0,1)=="1"){$ST="checked";}
					if(substr($Ligne2['Droit'],1,1)=="1"){$CE="checked";}
					if(substr($Ligne2['Droit'],2,1)=="1"){$Compagnon="checked";}
					if(substr($Ligne2['Droit'],3,1)=="1"){$Admin="checked";}
					if(substr($Ligne2['Droit'],4,1)=="1"){$IQ="checked";}
					}
				?>
				<td><input type='checkbox' id="ST" name='ST' value='ST' <?php echo $ST;?>>Support technique &nbsp;&nbsp;</td>
				<td><input type='checkbox' id="CE" name='CE' value='CE' <?php echo $CE;?>>Chef d'équipe &nbsp;&nbsp;</td>
				<td><input type='checkbox' id="Compagnon" name='Compagnon' value='Compagnon' <?php echo $Compagnon;?>>Compagnon &nbsp;&nbsp;</td>
				<td><input type='checkbox' id="IQ" name='IQ' value='IQ' <?php echo $IQ;?>>Inspecteur qualité &nbsp;&nbsp;</td>
				<td><input type='checkbox' id="Admin" name='Admin' value='Admin' <?php echo $Admin;?>>Admin &nbsp;&nbsp;</td>
				</tr></table>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="M"){echo "Valider";}else{echo "Ajouter";}?>"></td>
			</tr>
		</table>
		</form>
<?php
	}
	elseif($_GET['Mode']=="S")
	//Mode suppression
	{
		/*
		$requete="UPDATE new_rh_etatcivil SET ";
		$requete.="LoginSP='', ";
		$requete.="MdpSP='' ";
		$requete.=" WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		*/
		
		$req="DELETE FROM sp_acces WHERE Id_Prestation=834 AND Id_Personne=".$_GET['Id'];
		$result=mysqli_query($bdd,$req);
		
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_GET['Mode']=="R")
	//Mode réinitialisation
	{
		$requete="UPDATE new_rh_etatcivil SET ";
		$requete.="MdpSP='aaa01' ";
		$requete.=" WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
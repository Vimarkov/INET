<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		function FermerEtRecharger(Id){
			window.opener.location = "CahierDesCharges.php?Id="+Id;
			window.close();
		}
		function VerifChamps(langue){
			if(langue=="EN"){
				if(formulaire.uo.value=='0'){alert('You didn\'t enter the work unit.');return false;}
				if(formulaire.dt.value=='0'){alert('You didn\'t enter the technical domain.');return false;}
				if(formulaire.typeTravail.value==''){alert('You didn\'t enter the type of work.');return false;}
				if(formulaire.complexite.value==''){alert('You didn\'t enter the complexity.');return false;}
				if(formulaire.volume.value==''){alert('You didn\'t enter the type of work.');return false;}
				if(formulaire.otd.value==''){alert('You didn\'t enter the OTD.');return false;}
				if(formulaire.oqd.value==''){alert('You didn\'t enter the OQD.');return false;}
			}
			else{
				if(formulaire.uo.value=='0'){alert('Vous n\'avez pas renseigné l\'unité d\'oeuvre.');return false;}
				if(formulaire.dt.value=='0'){alert('Vous n\'avez pas renseigné le domaine technique.');return false;}
				if(formulaire.typeTravail.value==''){alert('Vous n\'avez pas renseigné le type de travail.');return false;}
				if(formulaire.complexite.value==''){alert('Vous n\'avez pas renseigné la complexité.');return false;}
				if(formulaire.volume.value==''){alert('Vous n\'avez pas renseigné le volume.');return false;}
				if(formulaire.otd.value==''){alert('Vous n\'avez pas renseigné l\'OTD.');return false;}
				if(formulaire.oqd.value==''){alert('Vous n\'avez pas renseigné l\'OQD.');return false;}
			}
			return true;
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
		function entier(champ){
			var chiffres = new RegExp("[0-9]"); /* Modifier pour : var chiffres = new RegExp("[0-9]"); */
			var verif;
			for(x = 0; x < champ.value.length; x++)
			{
				verif = chiffres.test(champ.value.charAt(x));
				if(verif == false){champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;}
			}
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
if($_POST){
	if($_POST['Mode']=="A"){
		$requete="INSERT INTO trame_uo_cdc (Id_UO,Id_WP,TypeTravail,Complexite,Id_DT,Volume,OTD,OQD) ";
		$requete.="VALUES (".$_POST['uo'].",".$_POST['id_WP'].",'".$_POST['typeTravail']."','".$_POST['complexite']."',".$_POST['dt'].",".$_POST['volume'].",".$_POST['otd'].",".$_POST['oqd'].") ";
		$result=mysqli_query($bdd,$requete);

		echo "<script>FermerEtRecharger(".$_POST['id_WP'].");</script>";
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE trame_uo_cdc SET ";
		$requete.="Id_UO=".$_POST['uo'].",";
		$requete.="Id_WP=".$_POST['id_WP'].",";
		$requete.="TypeTravail='".$_POST['typeTravail']."',";
		$requete.="Complexite='".$_POST['complexite']."',";
		$requete.="Id_DT=".$_POST['dt'].",";
		$requete.="Volume=".$_POST['volume'].",";
		$requete.="OTD=".$_POST['otd'].",";
		$requete.="OQD=".$_POST['oqd']." ";
		$requete.=" WHERE Id=".$_POST['id']."";
		$result=mysqli_query($bdd,$requete);

		echo "<script>FermerEtRecharger(".$_POST['id_WP'].");</script>";
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Id=$_GET['Id'];
	$WP=$_GET['WP'];
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT Id,Volume,OTD,OQD,TypeTravail,Complexite,Id_UO,Id_WP,Id_DT FROM trame_uo_cdc WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
		}
?>

		<form id="formulaire" method="POST" action="Ajout_CahierDesCharges.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="id" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<input type="hidden" name="id_WP" value="<?php echo $WP;?>">
		<table width="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Work unit";}else{echo "Unité d'oeuvre";} ?></td>
				<td colspan="6">
					<select id="uo" name="uo" style="width:600px;">
						<?php
							echo"<option value='0'></option>";
							$req="SELECT Id,Description,Supprime FROM trame_uo WHERE Id_Prestation=".$_SESSION['Id_PrestationTR'].";";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowUO=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="M"){
										if($rowUO['Id']==$Ligne['Id_UO']){$selected="selected";}
									}
									if($rowUO['Supprime']==false  || $rowUO['Id']==$Ligne['Id_UO']){
										echo "<option value='".$rowUO['Id']."' ".$selected.">".$rowUO['Description']."</option>";
									}
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Technical domain";}else{echo "Domaine technique";} ?></td>
				<td>
					<select id="dt" name="dt" style="width:70px;">
						<?php
							echo"<option value='0'></option>";
							$req="SELECT Id,Libelle,Supprime FROM trame_domainetechnique WHERE Id_Prestation=".$_SESSION['Id_PrestationTR'].";";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowDT=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="M"){
										if($rowDT['Id']==$Ligne['Id_DT']){$selected="selected";}
									}
									if($rowDT['Supprime']==false  || $rowDT['Id']==$Ligne['Id_DT']){
										echo "<option value='".$rowDT['Id']."' ".$selected.">".$rowDT['Libelle']."</option>";
									}
								}
							}
						?>
					</select>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Type of work";}else{echo "Type de travail";} ?></td>
				<td>
					<select id="typeTravail" name="typeTravail" style="width:100px;">
						<option value=""></option>
						<option value="Creation" <?php if($_GET['Mode']=="M"){if($Ligne['TypeTravail']=="Creation"){echo "selected";}}?>>Creation</option>
						<option value="Update" <?php if($_GET['Mode']=="M"){if($Ligne['TypeTravail']=="Update"){echo "selected";}}?>>Update</option>
					</select>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Complexity";}else{echo "Complexité";} ?></td>
				<td>
					<select id="complexite" name="complexite" style="width:100px;">
						<option value=""></option>
						<option value="Low" <?php if($_GET['Mode']=="M"){if($Ligne['Complexite']=="Low"){echo "selected";}}?>>Low</option>
						<option value="Medium" <?php if($_GET['Mode']=="M"){if($Ligne['Complexite']=="Medium"){echo "selected";}}?>>Medium</option>
						<option value="High" <?php if($_GET['Mode']=="M"){if($Ligne['Complexite']=="High"){echo "selected";}}?>>High</option>
						<option value="Very High" <?php if($_GET['Mode']=="M"){if($Ligne['Complexite']=="Very High"){echo "selected";}}?>>Very High</option>
						<option value="Other" <?php if($_GET['Mode']=="M"){if($Ligne['Complexite']=="Other"){echo "selected";}}?>>Other</option>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Volume";}else{echo "Volume";} ?></td>
				<td>
					<input onKeyUp="entier(this)" id="volume" name="volume" size="8px" value="<?php if($_GET['Mode']=="M"){ echo $Ligne['Volume'];}?>" />
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "OTD (%)";}else{echo "OTD (%)";} ?></td>
				<td>
					<input onKeyUp="nombre(this)" id="otd" name="otd" size="8px" value="<?php if($_GET['Mode']=="M"){ echo $Ligne['OTD'];}?>" />
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "OQD (%)";}else{echo "OQD (%)";} ?></td>
				<td>
					<input onKeyUp="nombre(this)" id="oqd" name="oqd" size="8px" value="<?php if($_GET['Mode']=="M"){ echo $Ligne['OQD'];}?>" />
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td colspan="6" align="center">
					<input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="M"){if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}}else{if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}}?>">
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$requete="DELETE FROM trame_uo_cdc WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger(".$_GET['WP'].");</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<script>
		function Verification_Saisie(){
			retour=true;
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.nom.value==''){alert('Vous n\'avez pas renseigné le MSN.');return false;}
			}
			else{
				if(formulaire.nom.value==''){alert('You did not fill in the MSN.');return false;}
			}
			$.ajax({
					url : 'ajax_MSNExiste.php',
					type : 'GET',
					data : 'MSN='+document.getElementById('nom').value,
					async: false,
					//affichage de l'erreur en cas de problème
					error:function(msg, string){
						},
					success:function(data){
						if(data.indexOf("OUI")>0){
							if(document.getElementById('Langue').value=="FR"){
								alert('Ce MSN existe déjà');
							}
							else{
								alert('This MSN already exists');
							}
							retour= false;
						}
					}
				});
			return retour;
		}
		function FermerEtRecharger(){
			opener.location.reload();
			window.close();
		}
	</script>
	<script language="javascript" src="Fonctions_GPAO.js?t=<?php echo time(); ?>"></script>
</head>
<body>
<?php

session_start();
require("../Connexioni.php");
require("../Fonctions.php");
	
if($_POST){
	$NT=0;
	if($_POST['NT']<>""){
		$NT=$_POST['NT'];
	}
	if($_POST['nom']<>''){
		$requete="INSERT INTO gpao_aircraft (MSN,Id_AircraftType,Id_AircraftDestination,NT,CreateAT,Id_PrestationGPAO) VALUES ('".addslashes($_POST['nom'])."',".$_POST['aircrafttype'].",".$_POST['aircraftdestination'].",".$NT.",'".date('Y-m-d H:i:s')."',".$_SESSION['Id_GPAO'].") ";
	}

	$result=mysqli_query($bdd,$requete);

	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
?>

		<form id="formulaire" method="POST" action="Ajout_Aircraft.php">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<input type="hidden" name="id" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<table width="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "MSN";}else{echo "MSN";} ?> </td>
				<td colspan="3">
					<input onKeyUp="nombre(this)" type="texte" name="nom" id="nom" size="10" value="<?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['MSN']);}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Aircraft Type";}else{echo "Aircraft Type";} ?> </td>
				<td colspan="3">
					<select id="aircrafttype" name="aircrafttype">
					<?php
						echo"<option name='0' value='0'></option>";
						$req="SELECT Id, Libelle 
								FROM gpao_aircrafttype
								WHERE (Suppr=0 ";
						if($_GET['Mode']=="M"){
							$req.="OR Id=".$Ligne['Id_AircraftType'];
						}
						$req.=")
							AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
							ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($row=mysqli_fetch_array($result)){
								$selected="";
								if($_GET['Mode']=="M"){
									if($row['Id']==$Ligne['Id_AircraftType']){$selected="selected";}
								}
								echo "<option value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
							}
						}
					?>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "NT";}else{echo "NT";} ?> </td>
				<td colspan="3">
					<input onKeyUp="nombre(this)" type="texte" name="NT" id="NT" size="15" value="<?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['NT']);}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Aircraft Destination";}else{echo "Aircraft Destination";} ?> </td>
				<td colspan="3">
					<select id="aircraftdestination" name="aircraftdestination">
					<?php
						echo"<option name='0' value='0'></option>";
						$req="SELECT Id, Libelle 
								FROM gpao_aircraftdestination
								WHERE (Suppr=0 ";
						if($_GET['Mode']=="M"){
							$req.="OR Id=".$Ligne['Id_AircraftDestination'];
						}
						$req.=")
							AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
							ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($row=mysqli_fetch_array($result)){
								$selected="";
								if($_GET['Mode']=="M"){
									if($row['Id']==$Ligne['Id_AircraftDestination']){$selected="selected";}
								}
								echo "<option value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
							}
						}
					?>
					</select>
				</td>
			</tr>
			
			<tr><td height="5px"></td></tr>
			<tr class="TitreColsUsers">
				<td colspan="6" align="center">
					<input class="Bouton" onclick="if(Verification_Saisie()==true){submit();}" value="<?php if($_GET['Mode']=="M"){if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}}else{if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}}?>">
				</td>
			</tr>
		</table>
		</form>
<?php
}
?>
	
</body>
</html>
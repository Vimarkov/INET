<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<script>
		function VerifRemplissage(nbLigne){
			//Vérifier le remplissage des lignes
			//Si quelque chose est renseigné sur la ligne alors il faut que Product type et référence soit complété au minimum
			for(i=0;i<nbLigne;i++){
				if(document.getElementById('productType_'+i).value!="" || document.getElementById('productReference_'+i).value!="" 
				|| document.getElementById('numBatch_'+i).value!="" || document.getElementById('expirationDate_'+i).value!=""
				|| document.getElementById('applicationDate_'+i).value!="" || document.getElementById('applicationTime_'+i).value!=""
				|| document.getElementById('numThermohydrometer_'+i).value!="" || document.getElementById('temperatureC_'+i).value!=""
				|| document.getElementById('humidity_'+i).value!=""){
					if(document.getElementById('productType_'+i).value=="" || document.getElementById('productReference_'+i).value==""){
						if(document.getElementById('Langue').value=="FR"){
							alert('Les champs "Product type" et "Reference" doivent être complétés pour pouvoir enregistrer');return false;
						}
						else{
							alert('The "Product type" and "Reference" fields must be completed in order to register');return false;
						}
					}
				}
			}
			return true;
		}
		function FermerEtRecharger(){
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
	if(isset($_POST['Btn_Enregistrer'])){
		//Supprimer les anciens
		$req="UPDATE gpao_chemicalproduct 
			SET Suppr=1, DateSuppr='".date('Y-m-d')."', Id_Suppr=".$_SESSION['Id_Personne']." 
			WHERE Suppr=0 
			AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			AND Id_WO=".$_SESSION['GPAO_IdWO']." ";
		$result=mysqli_query($bdd,$req);
		//Recréer les nouveau
		for($i=0;$i<$_POST['nbLigne'];$i++){
			if($_POST['productType_'.$i]<>"" && $_POST['productReference_'.$i]<>""){
				$req="INSERT INTO gpao_chemicalproduct (Id_PrestationGPAO,Id_WO,ProductType,ProductReference,NumBatch,ExpirationDate,ApplicationDate,ApplicationTime,NumThermohydrometer,TemperatureC,Humidity) 
				VALUES (".$_SESSION['Id_GPAO'].",".$_SESSION['GPAO_IdWO'].",'".addslashes($_POST['productType_'.$i])."','".addslashes($_POST['productReference_'.$i])."',
				'".addslashes($_POST['numBatch_'.$i])."',
				'".TrsfDate_($_POST['expirationDate_'.$i])."','".TrsfDate_($_POST['applicationDate_'.$i])."',
				'".$_POST['applicationTime_'.$i]."','".addslashes($_POST['numThermohydrometer_'.$i])."',
				'".addslashes($_POST['temperatureC_'.$i])."','".addslashes($_POST['humidity_'.$i])."') ";
				$result=mysqli_query($bdd,$req);
			}
		}
	}

	echo "<script>FermerEtRecharger();</script>";
}
else
{
	$req="SELECT Id,ProductType,ProductReference,NumBatch,ExpirationDate,ApplicationDate,ApplicationTime,NumThermohydrometer,TemperatureC,Humidity
		FROM gpao_chemicalproduct
		WHERE Suppr=0 
		AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
		AND Id_WO=".$_SESSION['GPAO_IdWO']." ";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	$total=5;
	if ($nbResulta>=5){
		$total=5+$nbResulta;
	}
?>
<form id="formulaire" method="POST" action="Ajout_ChemicalProduct.php" onSubmit="return VerifRemplissage(<?php echo $total; ?>);">
<input type="hidden" name="btn" id="btn" value="" />
<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
<table class="TableCompetences" width="99%" cellpadding="0" cellspacing="0">
	<tr>
		<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION['Langue']=="EN"){echo "Product Type";}else{echo "Product Type";} ?></td>
		<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION['Langue']=="EN"){echo "Reference";}else{echo "Reference";} ?></td>
		<td class="EnTeteTableauCompetences" width="15%"><?php if($_SESSION['Langue']=="EN"){echo "Batch n°";}else{echo "Batch n°";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Expiration Date";}else{echo "Expiration Date";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Application Date";}else{echo "Application Date";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Application Time";}else{echo "Application Time";} ?></td>
		<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "Thermohydrometer n°";}else{echo "Thermohydrometer n°";} ?></td>
		<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "T°C";}else{echo "T°C";} ?></td>
		<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "Humidity";}else{echo "Humidity";} ?></td>
	</tr>
<?php 
	for($i=0;$i<$total;$i++){
		$ProductType="";
		$ProductReference="";
		$NumBatch="";
		$ExpirationDate="";
		$ApplicationDate="";
		$ApplicationTime="";
		$NumThermohydrometer="";
		$TemperatureC="";
		$Humidity="";
		
		if ($i<$nbResulta){
			$row=mysqli_fetch_array($result);
			$ProductType=stripslashes($row['ProductType']);
			$ProductReference=stripslashes($row['ProductReference']);
			$NumBatch=stripslashes($row['NumBatch']);
			$ExpirationDate=AfficheDateFR($row['ExpirationDate']);
			$ApplicationDate=AfficheDateFR($row['ApplicationDate']);
			$ApplicationTime=substr($row['ApplicationTime'],0,5);
			$NumThermohydrometer=stripslashes($row['NumThermohydrometer']);
			$TemperatureC=stripslashes($row['TemperatureC']);
			$Humidity=stripslashes($row['Humidity']);
		}
?>
		<tr>
			<td>
				<select id="productType_<?php echo $i;?>" name="productType_<?php echo $i;?>" style="width:150px;">
					<option value=""></option>
				<?php
					$tab=array("Alodine","Blue Paint","Green Paint","Grey Paint","Sealant","SOCOPAC","Red Mark","Locktite");

					foreach($tab as $valeur)
					{
						$selected="";
						if($ProductType==$valeur){$selected="selected";}
						echo "<option value='".$valeur."' ".$selected.">".$valeur."</option>\n";
					}
				?>
				</select>
			</td>
			<td>
				<input type="texte" id="productReference_<?php echo $i;?>" name="productReference_<?php echo $i;?>" size="15" value="<?php echo $ProductReference;?>" >
			</td>
			<td>
				<input type="texte" id="numBatch_<?php echo $i;?>" name="numBatch_<?php echo $i;?>" size="15" value="<?php echo $NumBatch;?>" >
			</td>
			<td>
				<input type="date" id="expirationDate_<?php echo $i;?>" name="expirationDate_<?php echo $i;?>" size="20" value="<?php echo $ExpirationDate;?>">
			</td>
			<td>
				<input type="date" id="applicationDate_<?php echo $i;?>" name="applicationDate_<?php echo $i;?>" size="20" value="<?php echo $ApplicationDate;?>">
			</td>
			<td>
				<input type="time" id="applicationTime_<?php echo $i;?>" name="applicationTime_<?php echo $i;?>" size="15" value="<?php echo $ApplicationTime;?>" >
			</td>
			<td>
				<input type="texte" id="numThermohydrometer_<?php echo $i;?>" name="numThermohydrometer_<?php echo $i;?>" size="15" value="<?php echo $NumThermohydrometer;?>" >
			</td>
			<td>
				<input type="texte" id="temperatureC_<?php echo $i;?>" name="temperatureC_<?php echo $i;?>" size="5" value="<?php echo $TemperatureC;?>" >
			</td>
			<td>
				<input type="texte" id="humidity_<?php echo $i;?>" name="humidity_<?php echo $i;?>" size="5" value="<?php echo $Humidity;?>" >
			</td>
		</tr>
		<tr><td height="4"></td></tr>
<?php
	}
?>
	<tr>
		<td colspan="10" align="center">
			<input class="Bouton" type="submit" style="font-size:15px;" name="Btn_Enregistrer"  value="<?php if($_SESSION['Langue']=="EN"){echo "Save";}else{echo "Save";}?>">
		</td>
	</tr>
	<input type="hidden" name="nbLigne" id="nbLigne" value="<?php echo $total; ?>" />
</form>
<?php
}
?>
	
</body>
</html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<script>
		function VerifRemplissage(nbLigne){
			//Vérifier le remplissage des lignes
			//Si quelque chose est renseigné sur la ligne alors il faut que tootl type et référence soit complété au minimum
			for(i=0;i<nbLigne;i++){
				if(document.getElementById('toolType_'+i).value!="" || document.getElementById('reference_'+i).value!="" 
				|| document.getElementById('nextCalibrationDate_'+i).value!="" || document.getElementById('dateOfUse_'+i).value!=""){
					if(document.getElementById('toolType_'+i).value=="" || document.getElementById('reference_'+i).value==""){
						if(document.getElementById('Langue').value=="FR"){
							alert('Les champs "Tool type" et "Reference" doivent être complétés pour pouvoir enregistrer');return false;
						}
						else{
							alert('The "Tool type" and "Reference" fields must be completed in order to register');return false;
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
		//Supprimer les anciens CMTE
		$req="UPDATE gpao_cmte 
			SET Suppr=1, DateSuppr='".date('Y-m-d')."', Id_Suppr=".$_SESSION['Id_Personne']." 
			WHERE Suppr=0 
			AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			AND Id_WO=".$_SESSION['GPAO_IdWO']." ";
		$result=mysqli_query($bdd,$req);
		//Recréer les nouveau CMTE
		for($i=0;$i<$_POST['nbLigne'];$i++){
			if($_POST['toolType_'.$i]<>"" && $_POST['reference_'.$i]<>""){
				$req="INSERT INTO gpao_cmte (Id_PrestationGPAO,Id_WO,ToolType,Reference,NextCalibrationDate,DateOfUse) 
				VALUES (".$_SESSION['Id_GPAO'].",".$_SESSION['GPAO_IdWO'].",'".addslashes($_POST['toolType_'.$i])."','".addslashes($_POST['reference_'.$i])."',
				'".TrsfDate_($_POST['nextCalibrationDate_'.$i])."','".TrsfDate_($_POST['dateOfUse_'.$i])."') ";
				$result=mysqli_query($bdd,$req);
			}
		}
	}

	echo "<script>FermerEtRecharger();</script>";
}
else
{
	$req="SELECT Id,ToolType,Reference,NextCalibrationDate,DateOfUse
		FROM gpao_cmte
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
<form id="formulaire" method="POST" action="Ajout_CMTE.php" onSubmit="return VerifRemplissage(<?php echo $total; ?>);">
<input type="hidden" name="btn" id="btn" value="" />
<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
<table class="TableCompetences" width="99%" cellpadding="0" cellspacing="0">
	<tr>
		<td class="EnTeteTableauCompetences" width="40%"><?php if($_SESSION['Langue']=="EN"){echo "Tool type";}else{echo "Tool type";} ?></td>
		<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION['Langue']=="EN"){echo "Reference";}else{echo "Reference";} ?></td>
		<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION['Langue']=="EN"){echo "Next calibration";}else{echo "Next calibration";} ?></td>
		<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION['Langue']=="EN"){echo "Date of use";}else{echo "Date of use";} ?></td>
	</tr>
<?php 
	for($i=0;$i<$total;$i++){
		$ToolType="";
		$Reference="";
		$NextCalibrationDate="";
		$DateOfUse="";
		
		if ($i<$nbResulta){
			$row=mysqli_fetch_array($result);
			$ToolType=stripslashes($row['ToolType']);
			$Reference=stripslashes($row['Reference']);
			$NextCalibrationDate=AfficheDateFR($row['NextCalibrationDate']);
			$DateOfUse=AfficheDateFR($row['DateOfUse']);
		}
?>
		<tr>
			<td>
				<select id="toolType_<?php echo $i;?>" name="toolType_<?php echo $i;?>" style="width:250px;">
					<option value=""></option>
				<?php
					$tab=array("Torsiometer","Torque Wrench","Go - No Go","Depth Measuring Bridge","Thickness Measurer","Thermohygrometer","Setting Ring","Rivet Fork","Protusion Gauge","Milliohmmetre","Bridge Caliper","Measuring Block for Scuffplates","LGP Gauge","Inside Micrometer","Infrared Thermometer TESTO","HELIOS-PRESSIER Digital Indicator 0,01 mm / 12,5 mm","HAZET Torque Wrench 4-40 N.m","HAZET Torque Wrench 10-60 N.m","Tyrap Gun","Electrical Screwdriver","Digital Indicator","Comparateur","Caliper Gauge","Stripping Tool","Crimping Tool");

					foreach($tab as $valeur)
					{
						$selected="";
						if($ToolType==$valeur){$selected="selected";}
						echo "<option value='".$valeur."' ".$selected.">".$valeur."</option>\n";
					}
				?>
				</select>
			</td>
			<td>
				<input type="texte" id="reference_<?php echo $i;?>" name="reference_<?php echo $i;?>" size="20" value="<?php echo $Reference;?>" >
			</td>
			<td>
				<input type="date" id="nextCalibrationDate_<?php echo $i;?>" name="nextCalibrationDate_<?php echo $i;?>" size="20" value="<?php echo $NextCalibrationDate;?>">
			</td>
			<td>
				<input type="date" id="dateOfUse_<?php echo $i;?>" name="dateOfUse_<?php echo $i;?>" size="20" value="<?php echo $DateOfUse;?>">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
<?php
	}
?>
	<tr>
		<td colspan="4" align="center">
			<input class="Bouton" type="submit" style="font-size:15px;" name="Btn_Enregistrer"  value="<?php if($_SESSION['Langue']=="EN"){echo "Save";}else{echo "Save";}?>">
		</td>
	</tr>
	<input type="hidden" name="nbLigne" id="nbLigne" value="<?php echo $total; ?>" />
</table>	
</form>
<?php
}
?>
</body>
</html>
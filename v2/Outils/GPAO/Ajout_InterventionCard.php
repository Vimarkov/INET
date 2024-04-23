<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<script>
		function VerifRemplissage(nbLigne){
			//Vérifier le remplissage des lignes
			//Si quelque chose est renseigné sur la ligne alors il faut que N° IC, Start date, Start hour soit complété au minimum
			for(i=0;i<nbLigne;i++){
				if(document.getElementById('numIC_'+i).value!="" || document.getElementById('startDate_'+i).value!="" 
				|| document.getElementById('startHour_'+i).value!="" || document.getElementById('endDate_'+i).value!=""
				|| document.getElementById('endHour_'+i).value!="" || document.getElementById('intervenerName_'+i).value!=""
				|| document.getElementById('intervenerSignatureDate_'+i).value!="" || document.getElementById('qiStampNum_'+i).value!=""
				|| document.getElementById('qiClosureDate_'+i).value!="" || document.getElementById('closureType_'+i).value!=""){
					if(document.getElementById('numIC_'+i).value=="" || document.getElementById('startDate_'+i).value==""
					|| document.getElementById('startHour_'+i).value==""){
						if(document.getElementById('Langue').value=="FR"){
							alert('Les champs "Intervention card n°", "Start date" et "Start hour" doivent être complétés pour pouvoir enregistrer');return false;
						}
						else{
							alert('The "Intervention card n°", "Start date" and "Start hour" fields must be completed in order to register');return false;
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
		$req="UPDATE gpao_interventioncard 
			SET Suppr=1, DateSuppr='".date('Y-m-d')."', Id_Suppr=".$_SESSION['Id_Personne']." 
			WHERE Suppr=0 
			AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			AND Id_WO=".$_SESSION['GPAO_IdWO']." ";
		$result=mysqli_query($bdd,$req);
		//Recréer les nouveau
		for($i=0;$i<$_POST['nbLigne'];$i++){
			if($_POST['numIC_'.$i]<>"" && $_POST['startDate_'.$i]<>"" && $_POST['startHour_'.$i]<>""){
				$req="INSERT INTO gpao_interventioncard (Id_PrestationGPAO,Id_WO,Numero,StartDate,StartHour,EndDate,EndHour,
				ClosureType,IntervenerName,IntervenerSignatureDate,QIStampNum,QIClosureDate) 
				VALUES (".$_SESSION['Id_GPAO'].",".$_SESSION['GPAO_IdWO'].",'".addslashes($_POST['numIC_'.$i])."',
				'".TrsfDate_($_POST['startDate_'.$i])."','".$_POST['startHour_'.$i]."',
				'".TrsfDate_($_POST['endDate_'.$i])."','".$_POST['endHour_'.$i]."',
				'".addslashes($_POST['closureType_'.$i])."','".addslashes($_POST['intervenerName_'.$i])."',
				'".TrsfDate_($_POST['intervenerSignatureDate_'.$i])."',
				'".addslashes($_POST['qiStampNum_'.$i])."','".TrsfDate_($_POST['qiClosureDate_'.$i])."') ";
				echo $req;
				$result=mysqli_query($bdd,$req);
			}
		}
	}

	echo "<script>FermerEtRecharger();</script>";
}
else
{
	$req="SELECT Id,Numero,StartDate,StartHour,EndDate,EndHour,ClosureType,IntervenerName,IntervenerSignatureDate,QIStampNum,QIClosureDate
		FROM gpao_interventioncard
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
<form id="formulaire" method="POST" action="Ajout_InterventionCard.php" onSubmit="return VerifRemplissage(<?php echo $total; ?>);">
<input type="hidden" name="btn" id="btn" value="" />
<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
<table class="TableCompetences" width="99%" cellpadding="0" cellspacing="0">
	<tr>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Intervention Card n°";}else{echo "Intervention Card n°";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Start Date";}else{echo "Start Date";} ?></td>
		<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "Start Hour";}else{echo "Start Hour";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "End Date";}else{echo "End Date";} ?></td>
		<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "End Hour";}else{echo "End Hour";} ?></td>
		<td class="EnTeteTableauCompetences" width="15%"><?php if($_SESSION['Langue']=="EN"){echo "Intervener Name";}else{echo "Intervener Name";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Intervener Signature Date";}else{echo "Intervener Signature Date";} ?></td>
		<td class="EnTeteTableauCompetences" width="15%"><?php if($_SESSION['Langue']=="EN"){echo "QI Stamp n°";}else{echo "QI Stamp n°";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "QI Closure Date";}else{echo "QI Closure Date";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Closure Type";}else{echo "Closure Type";} ?></td>
	</tr>
<?php 
	for($i=0;$i<$total;$i++){
		$Numero="";
		$StartDate="";
		$StartHour="";
		$EndDate="";
		$EndHour="";
		$ClosureType="";
		$IntervenerName="";
		$IntervenerSignatureDate="";
		$QIStampNum="";
		$QIClosureDate="";
		
		if ($i<$nbResulta){
			$row=mysqli_fetch_array($result);
			$Numero=stripslashes($row['Numero']);
			$StartDate=AfficheDateFR($row['StartDate']);
			$StartHour=substr($row['StartHour'],0,5);
			$EndDate=AfficheDateFR($row['EndDate']);
			$EndHour=substr($row['EndHour'],0,5);
			$ClosureType=stripslashes($row['ClosureType']);
			$IntervenerName=stripslashes($row['IntervenerName']);
			$IntervenerSignatureDate=AfficheDateFR($row['IntervenerSignatureDate']);
			$QIStampNum=stripslashes($row['QIStampNum']);
			$QIClosureDate=AfficheDateFR($row['QIClosureDate']);
		}
?>
		<tr>
			<td>
				<input type="texte" id="numIC_<?php echo $i;?>" name="numIC_<?php echo $i;?>" size="15" value="<?php echo $Numero;?>" >
			</td>
			<td>
				<input type="date" id="startDate_<?php echo $i;?>" name="startDate_<?php echo $i;?>" size="8" value="<?php echo $StartDate;?>">
			</td>
			<td>
				<input type="time" id="startHour_<?php echo $i;?>" name="startHour_<?php echo $i;?>" size="5" value="<?php echo $StartHour;?>" >
			</td>
			<td>
				<input type="date" id="endDate_<?php echo $i;?>" name="endDate_<?php echo $i;?>" size="8" value="<?php echo $EndDate;?>" >
			</td>
			<td>
				<input type="time" id="endHour_<?php echo $i;?>" name="endHour_<?php echo $i;?>" size="5" value="<?php echo $EndHour;?>" >
			</td>
			<td>
				<input type="texte" id="intervenerName_<?php echo $i;?>" name="intervenerName_<?php echo $i;?>" size="20" value="<?php echo $IntervenerName;?>" >
			</td>
			<td>
				<input type="date" id="intervenerSignatureDate_<?php echo $i;?>" name="intervenerSignatureDate_<?php echo $i;?>" size="8" value="<?php echo $IntervenerSignatureDate;?>" >
			</td>
			<td>
				<input type="texte" id="qiStampNum_<?php echo $i;?>" name="qiStampNum_<?php echo $i;?>" size="15" value="<?php echo $QIStampNum;?>" >
			</td>
			<td>
				<input type="date" id="qiClosureDate_<?php echo $i;?>" name="qiClosureDate_<?php echo $i;?>" size="8" value="<?php echo $QIClosureDate;?>" >
			</td>
			<td>
				<select id="closureType_<?php echo $i;?>" name="closureType_<?php echo $i;?>" style="width:150px;">
					<option value=""></option>
				<?php
					$tab=array("CANCELLED","CLOSED","INTERRUPTED","OPEN");

					foreach($tab as $valeur)
					{
						$selected="";
						if($ClosureType==$valeur){$selected="selected";}
						echo "<option value='".$valeur."' ".$selected.">".$valeur."</option>\n";
					}
				?>
				</select>
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
</table>	
</form>
<?php
}
?>
	
</body>
</html>
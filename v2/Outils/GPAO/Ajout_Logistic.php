<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<script>
		function VerifRemplissage(nbLigne){
			//Vérifier le remplissage des lignes
			//Si quelque chose est renseigné sur la ligne alors il faut que Part number et Quantity soit complété au minimum
			for(i=0;i<nbLigne;i++){
				if(document.getElementById('partNumber_'+i).value!="" || document.getElementById('quantity_'+i).value!="" 
				|| document.getElementById('CMS_'+i).value!="" || document.getElementById('refDIV_'+i).value!=""
				|| document.getElementById('trackingNumber_'+i).value!="" || document.getElementById('sendingDate_'+i).value!=""
				|| document.getElementById('partsDeliveryDate_'+i).value!="" || document.getElementById('partsReceivedOn_'+i).value!=""
				|| document.getElementById('comments_'+i).value!="" || document.getElementById('trackingNumber_'+i).value!=""){
					if(document.getElementById('partNumber_'+i).value=="" || document.getElementById('quantity_'+i).value==""){
						if(document.getElementById('Langue').value=="FR"){
							alert('Les champs "Part number" et "Quantity" doivent être complétés pour pouvoir enregistrer');return false;
						}
						else{
							alert('The "Part number" and "Quantity" fields must be completed in order to register');return false;
						}
					}
				}
			}
			return true;
		}
		function FermerEtRecharger(){
			opener.location="TableauDeBord.php?Menu=1";
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

function unNombreSinon0($leNombre){
	$nb=0;
	if($leNombre<>""){$nb=(double)$leNombre;}
	return $nb;
}

if($_POST){
	if(isset($_POST['Btn_Enregistrer'])){
		//Update du WO 
		$req="UPDATE gpao_wo 
			SET 
				Para='".addslashes($_POST['para'])."',
				NC='".addslashes($_POST['nc'])."',
				Concession='".addslashes($_POST['concession'])."',
				TargetTime='".unNombreSinon0($_POST['targetTime'])."',
				LimitDateFOT='".TrsfDate_($_POST['limitDateFOT'])."',
				PlanDate='".TrsfDate_($_POST['plandate'])."',
				WorkingProgress='".unNombreSinon0($_POST['workingProgress'])."',
				Comments='".addslashes($_POST['commentACMS1'])."',
				CommentsA_CMS2='".addslashes($_POST['commentACMS2'])."'
			WHERE Id=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$req);	
		
		//Supprimer les anciens Logistic
		$req="UPDATE gpao_logistic
			SET Suppr=1, DateSuppr='".date('Y-m-d')."', Id_Suppr=".$_SESSION['Id_Personne']." 
			WHERE Suppr=0 
			AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			AND Id_WO=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$req);
		//Recréer les nouveau CMTE
		for($i=0;$i<$_POST['nbLigne'];$i++){
			if($_POST['partNumber_'.$i]<>"" && $_POST['quantity_'.$i]<>""){
				$req="INSERT INTO gpao_logistic (Id_PrestationGPAO,Id_WO,PartNumber,Quantity,CMS,RefDIV,TrackingNumber,PartsDeliveryDate,PartsReceivedOn,SendingDate,LogComments) 
				VALUES (".$_SESSION['Id_GPAO'].",".$_POST['Id'].",'".addslashes($_POST['partNumber_'.$i])."','".$_POST['quantity_'.$i]."',
				'".addslashes($_POST['CMS_'.$i])."','".addslashes($_POST['refDIV_'.$i])."','".addslashes($_POST['trackingNumber_'.$i])."',
				'".TrsfDate_($_POST['partsDeliveryDate_'.$i])."','".TrsfDate_($_POST['partsReceivedOn_'.$i])."',
				'".TrsfDate_($_POST['sendingDate_'.$i])."','".addslashes($_POST['logComments_'.$i])."') ";
				$result=mysqli_query($bdd,$req);
			}
		}
	}

	echo "<script>FermerEtRecharger();</script>";
}
else
{
	$Customer="";
	$MSN="";
	$Type="";
	$NC="";
	$para="";
	$concession="";
	$creationDate="";
	$planDate="";
	$limitDateFOT="";
	$targetTime="";
	$workingProgress="";
	$commentACMS1="";
	$commentACMS2="";

	$reqWO="SELECT Id,
			(SELECT Libelle FROM gpao_customer WHERE Id=Id_Customer) AS Customer,
			(SELECT MSN FROM gpao_aircraft WHERE Id=Id_Aircraft) AS MSN,
			(SELECT (SELECT Libelle FROM gpao_aircrafttype WHERE Id=Id_AircraftType) FROM gpao_aircraft WHERE Id=Id_Aircraft) AS Type,
			Para,NC,Concession,TargetTime,LimitDateFOT,PlanDate,WorkingProgress,Comments,CommentsA_CMS2,CreationDate
		FROM gpao_wo 
		WHERE Id=".$_GET['Id']." ";
	$resultWO=mysqli_query($bdd,$reqWO);
	$nbWO=mysqli_num_rows($resultWO);

	if ($nbWO > 0)
	{
		$rowWO=mysqli_fetch_array($resultWO);

		$Customer=stripslashes($rowWO['Customer']);
		$MSN=stripslashes($rowWO['MSN']);
		$Type=stripslashes($rowWO['Type']);
		$NC=stripslashes($rowWO['NC']);
		$para=stripslashes($rowWO['Para']);
		$concession=stripslashes($rowWO['Concession']);
		$creationDate=$rowWO['CreationDate'];
		$planDate=AfficheDateFR($rowWO['PlanDate']);
		$limitDateFOT=AfficheDateFR($rowWO['LimitDateFOT']);
		$targetTime=stripslashes($rowWO['TargetTime']);
		$workingProgress=stripslashes($rowWO['WorkingProgress']);
		$commentACMS1=stripslashes($rowWO['Comments']);
		$commentACMS2=stripslashes($rowWO['CommentsA_CMS2']);
	}
	
	$req="SELECT Id,PartNumber,Quantity,CMS,RefDIV,TrackingNumber,PartsDeliveryDate,PartsReceivedOn,SendingDate,LogComments
		FROM gpao_logistic
		WHERE Suppr=0 
		AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
		AND Id_WO=".$_GET['Id']." ";

	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	$total=5;
	if ($nbResulta>=5){
		$total=5+$nbResulta;
	}
?>
<form id="formulaire" method="POST" action="Ajout_Logistic.php" onSubmit="return VerifRemplissage(<?php echo $total; ?>);">
<input type="hidden" name="btn" id="btn" value="" />
<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
<table class="TableCompetences" width="99%" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="10">
				<table width="99%" cellpadding="0" cellspacing="0">
					<tr><td height="8"></td></tr>
					<tr>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Customer";}else{echo "Customer";} ?> :</td>
						<td class="Libelle" width='7%'>
							<?php echo $Customer;?>
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "MSN";}else{echo "MSN";} ?> :</td>
						<td class="Libelle" width='7%'>
							<?php echo $MSN;?>
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Type";}else{echo "Type";} ?> :</td>
						<td class="Libelle" width='7%'>
							<?php echo $Type;?>
						</td>
					</tr>
					<tr><td height="8"></td></tr>
					<tr>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "NC";}else{echo "NC";} ?> :</td>
						<td width='7%'>
							<input onKeyUp="nombre(this)" type="texte" name="nc" id="nc" size="15" value="<?php echo $NC;?>">
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Para";}else{echo "Para";} ?> :</td>
						<td width='7%'>
							<input type="texte" name="para" id="para" size="15" value="<?php echo $para;?>">
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Concession";}else{echo "Concession";} ?> :</td>
						<td width='7%'>
							<input type="texte" name="concession" id="concession" size="15" value="<?php echo $concession;?>">
						</td>
					</tr>
					<tr><td height="8"></td></tr>
					<tr>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Plan date";}else{echo "Plan date";} ?> :</td>
						<td width='7%'>
							<input type="date" name="plandate" id="plandate" size="20" value="<?php echo $planDate;?>">
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Limit date";}else{echo "Limit date";} ?> :</td>
						<td width='7%'>
							<input type="date" name="firstEoW" id="firstEoW" size="20" value="<?php echo $limitDateFOT;?>">
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Creation date";}else{echo "Creation date";} ?> :</td>
						<td width='7%'>
							<?php echo $creationDate;?>
						</td>
					</tr>
					<tr><td height="8"></td></tr>
					<tr>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Target time";}else{echo "Target time";} ?> :</td>
						<td width='7%'>
							<input onKeyUp="nombre(this)" type="texte" name="targetTime" id="targetTime" size="8" value="<?php echo $targetTime;?>">
						</td>
						<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Working progress";}else{echo "Working progress";} ?> :</td>
						<td class="Libelle" width='7%'>
							<input onKeyUp="nombre(this)" type="texte" name="workingProgress" id="workingProgress" size="6" value="<?php echo $workingProgress;?>"> %
						</td>
					</tr>
					<tr><td height="8"></td></tr>
					<tr>
						<td class="Libelle" width='5%' valign="top"><?php if($_SESSION['Langue']=="EN"){echo "Comments <br>A/CM S1";}else{echo "Comments <br>A/CM S1";} ?> :</td>
						<td width='7%' colspan="3" valign="top">
							<textarea id="commentACMS1" name="commentACMS1" rows="3" cols="80" style="resize:none;"><?php echo $commentACMS1;?></textarea>
						</td>
					</tr>
					<tr><td height="8"></td></tr>
					<tr>
						<td class="Libelle" width='5%' valign="top"><?php if($_SESSION['Langue']=="EN"){echo "Comments <br>A/CM S2";}else{echo "Comments <br>A/CM S2";} ?> :</td>
						<td width='7%' colspan="3" valign="top">
							<textarea id="commentACMS2" name="commentACMS2" rows="3" cols="80" style="resize:none;"><?php echo $commentACMS2;?></textarea>
						</td>
					</tr>
					<tr><td height="8"></td></tr>
				</table>
		</td>
	</tr>
	<tr>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Part number";}else{echo "Part number";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Quantity";}else{echo "Quantity";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "CMS";}else{echo "CMS";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "DIV";}else{echo "DIV";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Tracking Number";}else{echo "Tracking Number";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Parts send date";}else{echo "Parts send date";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Parts delivery date";}else{echo "Parts delivery date";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Parts received on";}else{echo "Parts received on";} ?></td>
		<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION['Langue']=="EN"){echo "Comments";}else{echo "Comments";} ?></td>
	</tr>
<?php 
	for($i=0;$i<$total;$i++){
		$PartNumber="";
		$Quantity="";
		$CMS="";
		$RefDIV="";
		$TrackingNumber="";
		$SendingDate="";
		$PartsDeliveryDate="";
		$PartsReceivedOn="";
		$LogComments="";

		if ($i<$nbResulta){
			$row=mysqli_fetch_array($result);
			$PartNumber=$row['PartNumber'];
			$Quantity=$row['Quantity'];
			$CMS=$row['CMS'];
			$RefDIV=$row['RefDIV'];
			$TrackingNumber=$row['TrackingNumber'];
			$SendingDate=AfficheDateFR($row['SendingDate']);
			$PartsDeliveryDate=AfficheDateFR($row['PartsDeliveryDate']);
			$PartsReceivedOn=AfficheDateFR($row['PartsReceivedOn']);
			$LogComments=stripslashes($row['LogComments']);
		}
?>
		<tr>
			<td>
				<input type="texte" id="partNumber_<?php echo $i;?>" name="partNumber_<?php echo $i;?>" size="10" value="<?php echo $PartNumber;?>" >
			</td>
			<td>
				<input type="texte" onKeyUp="nombre(this)" id="quantity_<?php echo $i;?>" name="quantity_<?php echo $i;?>" size="5" value="<?php echo $Quantity;?>" >
			</td>
			<td>
				<input type="texte" id="CMS_<?php echo $i;?>" name="CMS_<?php echo $i;?>" size="10" value="<?php echo $CMS;?>" >
			</td>
			<td>
				<input type="texte" id="refDIV_<?php echo $i;?>" name="refDIV_<?php echo $i;?>" size="10" value="<?php echo $RefDIV;?>" >
			</td>
			<td>
				<input type="texte" id="trackingNumber_<?php echo $i;?>" name="trackingNumber_<?php echo $i;?>" size="10" value="<?php echo $TrackingNumber;?>" >
			</td>
			<td>
				<input type="date" id="sendingDate_<?php echo $i;?>" name="sendingDate_<?php echo $i;?>" size="20" value="<?php echo $SendingDate;?>">
			</td>
			<td>
				<input type="date" id="partsDeliveryDate_<?php echo $i;?>" name="partsDeliveryDate_<?php echo $i;?>" size="20" value="<?php echo $PartsDeliveryDate;?>">
			</td>
			<td>
				<input type="date" id="partsReceivedOn_<?php echo $i;?>" name="partsReceivedOn_<?php echo $i;?>" size="20" value="<?php echo $PartsReceivedOn;?>">
			</td>
			<td>
				<input type="texte" id="logComments_<?php echo $i;?>" name="logComments_<?php echo $i;?>" size="40" value="<?php echo $LogComments;?>" >
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
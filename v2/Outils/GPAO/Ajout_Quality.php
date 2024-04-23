<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<script>
		List_REWORK = new Array();
		function VerifRemplissage(nbLigne,nbLigneQ){
			//Vérifier le remplissage des lignes qualité
			//Si quelque chose est renseigné sur la ligne alors il faut que Statut soit complété au minimum
			for(i=0;i<nbLigneQ;i++){
				if(document.getElementById('id_ImputationRework_'+i).value!="0" 
				|| document.getElementById('id_StatutList_'+i).value!="0" 
				|| document.getElementById('timeUsed_'+i).value!="" 
				|| document.getElementById('id_QualityControlType_'+i).value!="0"
				|| document.getElementById('statusComments_'+i).value!="" 
				|| document.getElementById('id_NameResponsible_'+i).value!="0" 
				|| document.getElementById('id_NameResponsible2_'+i).value!="0"){
					if(document.getElementById('id_StatutList_'+i).value=="0"){
						if(document.getElementById('Langue').value=="FR"){
							alert('Le champs "Status" doit être complété pour pouvoir enregistrer');return false;
						}
						else{
							alert('The "Status" field must be completed in order to register');return false;
						}
					}
					else{
						trouve=0;
						for(k=0;k<List_REWORK.length;k++){
							if (List_REWORK[k]==document.getElementById('id_StatutList_'+i).value){
								trouve=1;
								break;
							}
						}
						if(trouve==1){
							if(document.getElementById('id_ImputationRework_'+i).value=="0"){
								if(document.getElementById('Langue').value=="FR"){
									alert('Le champs "Type of Rework" doit être complété pour pouvoir enregistrer');return false;
								}
								else{
									alert('The "Type of Rework" field must be completed in order to register');return false;
								}
							}
						}
					}
				}
			}

			
			//Vérifier le remplissage des lignes production
			//Si quelque chose est renseigné sur la ligne alors il faut que DateProd et Id_Worker et ProductiveTime soit complété au minimum
			for(i=0;i<nbLigne;i++){
				if(document.getElementById('dateProd_'+i).value!="" || document.getElementById('id_Worker_'+i).value!="0" 
				|| document.getElementById('productiveTime_'+i).value!="" || document.getElementById('idleTime_'+i).value!=""
				|| document.getElementById('id_CauseIdleTime_'+i).value!="0" || document.getElementById('comments_'+i).value!=""){
					if(document.getElementById('dateProd_'+i).value=="" || document.getElementById('id_Worker_'+i).value=="0"
					|| document.getElementById('productiveTime_'+i).value==""){
						if(document.getElementById('Langue').value=="FR"){
							alert('Les champs "Date", "Worker" et "Productive time" doivent être complétés pour pouvoir enregistrer');return false;
						}
						else{
							alert('The "Date", "Worker" and "Productive time" fields must be completed in order to register');return false;
						}
					}
				}
			}
			return true;
		}
		function FermerEtRecharger(){
			opener.location="TableauDeBord.php?Menu=2";
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
	if(isset($_POST['Btn_Enregistrer']) || isset($_POST['Btn_Enregistrer2'])){
		//PARTIE QUALITY 
		$req="UPDATE gpao_statutquality 
			SET Suppr=1, DateSuppr='".date('Y-m-d')."', Id_Suppr=".$_SESSION['Id_Personne']." 
			WHERE Suppr=0 
			AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			AND Id_WO=".$_SESSION['GPAO_IdWO']." ";
		$result=mysqli_query($bdd,$req);
		//Recréer les nouveaux
		for($i=0;$i<$_POST['nbLigneQ'];$i++){
			if($_POST['id_StatutList_'.$i]<>"0"){
				$timeUsed=0;
				if($_POST['timeUsed_'.$i]<>""){
					$timeUsed=$_POST['timeUsed_'.$i];
				}
				$req="INSERT INTO gpao_statutquality (Id_PrestationGPAO,Id_WO,
				Id_StatutList,DateStatut,TimeUsed,
				Id_QualityControlType,Id_ImputationRework,Id_UserName,
				IssueDetectedByCustomer,StatusComments,ICClosed,Id_NameResponsible,Id_NameResponsible2) 
				VALUES (".$_SESSION['Id_GPAO'].",".$_SESSION['GPAO_IdWO'].",
				'".$_POST['id_StatutList_'.$i]."','".$_POST['dateStatut_'.$i]."',
				'".$timeUsed."','".$_POST['id_QualityControlType_'.$i]."','".$_POST['id_ImputationRework_'.$i]."',
				'".$_POST['id_UserName_'.$i]."','".$_POST['issueDetectedByCustomer_'.$i]."',
				'".addslashes($_POST['statusComments_'.$i])."','".$_POST['iCClosed_'.$i]."',
				'".$_POST['id_NameResponsible_'.$i]."','".$_POST['id_NameResponsible2_'.$i]."') ";
				$result=mysqli_query($bdd,$req);
			}
		}

		//PARTIE PRODUCTION
		
		//Supprimer les anciens
		$req="UPDATE gpao_productionsheet 
			SET Suppr=1, DateSuppr='".date('Y-m-d')."', Id_Suppr=".$_SESSION['Id_Personne']." 
			WHERE Suppr=0 
			AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			AND Id_WO=".$_SESSION['GPAO_IdWO']." ";
		$result=mysqli_query($bdd,$req);
		//Recréer les nouveaux
		for($i=0;$i<$_POST['nbLigne'];$i++){
			if($_POST['dateProd_'.$i]<>"" && $_POST['id_Worker_'.$i]<>"0" && $_POST['productiveTime_'.$i]<>""){
				$req="INSERT INTO gpao_productionsheet (Id_PrestationGPAO,Id_WO,DateProd,Id_Worker,ProductiveTime,IdleTime,Comments,Id_CauseIdleTime) 
				VALUES (".$_SESSION['Id_GPAO'].",".$_SESSION['GPAO_IdWO'].",'".TrsfDate_($_POST['dateProd_'.$i])."',
				'".$_POST['id_Worker_'.$i]."','".$_POST['productiveTime_'.$i]."','".$_POST['idleTime_'.$i]."',
				'".addslashes($_POST['comments_'.$i])."','".$_POST['id_CauseIdleTime_'.$i]."') ";
				$result=mysqli_query($bdd,$req);
			}
		}
	}

	echo "<script>FermerEtRecharger();</script>";
}
else
{
	//PRODUCTION
	$req="SELECT Id,DateProd,Id_Worker,ProductiveTime,IdleTime,Comments,Id_CauseIdleTime
		FROM gpao_productionsheet
		WHERE Suppr=0 
		AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
		AND Id_WO=".$_SESSION['GPAO_IdWO']." ";

	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	$total=5;
	if ($nbResulta>=5){
		$total=5+$nbResulta;
	}
	
	//QUALITY
	$req="SELECT Id,Id_StatutList,DateStatut,TimeUsed,Id_QualityControlType,Id_ImputationRework,Id_UserName,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_UserName) AS UserName,
		IssueDetectedByCustomer,StatusComments,ICClosed,Id_NameResponsible,Id_NameResponsible2
		FROM gpao_statutquality
		WHERE Suppr=0 
		AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
		AND Id_WO=".$_SESSION['GPAO_IdWO']." ";
		
	$resultQ=mysqli_query($bdd,$req);
	$nbResultaQ=mysqli_num_rows($resultQ);
	
	$totalQ=5;
	if ($nbResultaQ>=5){
		$totalQ=5+$nbResultaQ;
	}
?>
<form id="formulaire" method="POST" action="Ajout_Quality.php" onSubmit="return VerifRemplissage(<?php echo $total; ?>,<?php echo $totalQ; ?>);">
<input type="hidden" name="btn" id="btn" value="" />
<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
<table class="TableCompetences" width="99%" cellpadding="0" cellspacing="0">
	<tr>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Type of Rework";}else{echo "Type of Rework";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Status";}else{echo "Status";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Date";}else{echo "Date";} ?></td>
		<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "Time Used";}else{echo "Time Used";} ?></td>
		<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION['Langue']=="EN"){echo "Category";}else{echo "Category";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "User Name";}else{echo "User Name";} ?></td>
		<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "Issue Detected by Customer";}else{echo "Issue Detected by Customer";} ?></td>
		<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION['Langue']=="EN"){echo "IC Closed";}else{echo "IC Closed";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Status Comments";}else{echo "Status Comments";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Name responsible";}else{echo "Name responsible";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Name responsible 2";}else{echo "Name responsible 2";} ?></td>
	</tr>
<?php 
	for($i=0;$i<$totalQ;$i++){
		$Id_StatutList="";
		$DateStatutVisu="";
		$DateStatut=date('Y-m-d H:i:s');
		$TimeUsed="";
		$Id_QualityControlType="";
		$Id_ImputationRework="";
		$UserName="";
		$Id_UserName=$_SESSION['Id_Personne'];
		$IssueDetectedByCustomer="";
		$StatusComments="";
		$ICClosed="";
		$Id_NameResponsible="";
		$Id_NameResponsible2="";

		if ($i<$nbResultaQ){
			$row=mysqli_fetch_array($resultQ);
			$Id_StatutList=$row['Id_StatutList'];
			$DateStatutVisu=$row['DateStatut'];
			$DateStatut=$row['DateStatut'];
			$TimeUsed=$row['TimeUsed'];
			$Id_QualityControlType=$row['Id_QualityControlType'];
			$Id_ImputationRework=$row['Id_ImputationRework'];
			$UserName=stripslashes($row['UserName']);
			$Id_UserName=$row['Id_UserName'];
			$IssueDetectedByCustomer=$row['IssueDetectedByCustomer'];
			$StatusComments=stripslashes($row['StatusComments']);
			$ICClosed=$row['ICClosed'];
			$Id_NameResponsible=$row['Id_NameResponsible'];
			$Id_NameResponsible2=$row['Id_NameResponsible2'];
		}
?>
		<tr>
			<td>
				<select id="id_ImputationRework_<?php echo $i;?>" name="id_ImputationRework_<?php echo $i;?>" style="width:150px;">
				<?php
					echo"<option name='0' value='0'></option>";
					$req="SELECT Id, Libelle
						FROM gpao_imputationrework 
						WHERE Suppr=0 
						AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
						ORDER BY Libelle";
					$result2=mysqli_query($bdd,$req);
					$nbResulta2=mysqli_num_rows($result2);
					if ($nbResulta2>0){
						while($row2=mysqli_fetch_array($result2)){
							$selected="";
							if($row2['Id']==$Id_ImputationRework){
								$selected="selected";
							}
							echo "<option value='".$row2['Id']."' ".$selected.">".stripslashes($row2['Libelle'])."</option>";
						}
					}
				?>
				</select>
			</td>
			<td>
				<select id="id_StatutList_<?php echo $i;?>" name="id_StatutList_<?php echo $i;?>" style="width:150px;">
				<?php
					echo"<option name='0' value='0'></option>";
					$req="SELECT Id, Libelle
						FROM gpao_statutlist 
						WHERE Suppr=0 
						AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
						ORDER BY Libelle";
					$result2=mysqli_query($bdd,$req);
					$nbResulta2=mysqli_num_rows($result2);
					
					$k=0;
					if ($nbResulta2>0){
						while($row2=mysqli_fetch_array($result2)){
							$selected="";
							if($row2['Id']==$Id_StatutList){
								$selected="selected";
							}
							echo "<option value='".$row2['Id']."' ".$selected.">".stripslashes($row2['Libelle'])."</option>";
							
							if(strpos($row2['Libelle'], "REWORK") !== FALSE)
							{
								echo "<script>List_REWORK[".$k."] = '".$row2['Id']."';</script>";
								$k++;
							}
						}
					}
				?>
				</select>
			</td>
			<td>
				<?php echo $DateStatutVisu; ?>
				<input type="hidden" id="dateStatut_<?php echo $i;?>" name="dateStatut_<?php echo $i;?>" size="20" value="<?php echo $DateStatut;?>">
			</td>
			<td>
				<input type="texte" onKeyUp="nombre(this)" id="timeUsed_<?php echo $i;?>" name="timeUsed_<?php echo $i;?>" size="5" value="<?php echo $TimeUsed;?>" >
			</td>
			<td>
				<select id="id_QualityControlType_<?php echo $i;?>" name="id_QualityControlType_<?php echo $i;?>" style="width:100px;">
				<?php
					echo"<option name='0' value='0'></option>";
					$req="SELECT Id, Libelle
						FROM gpao_qualitycontroltype 
						WHERE Suppr=0 
						AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
						ORDER BY Libelle";
					$result2=mysqli_query($bdd,$req);
					$nbResulta2=mysqli_num_rows($result2);
					if ($nbResulta2>0){
						while($row2=mysqli_fetch_array($result2)){
							$selected="";
							if($row2['Id']==$Id_QualityControlType){
								$selected="selected";
							}
							echo "<option value='".$row2['Id']."' ".$selected.">".stripslashes($row2['Libelle'])."</option>";
						}
					}
				?>
				</select>
			</td>
			<td class="Libelle">
				<?php echo $UserName; ?>
				<input type="hidden" id="id_UserName_<?php echo $i;?>" name="id_UserName_<?php echo $i;?>" size="20" value="<?php echo $Id_UserName;?>">
			</td>
			<td>
				<select class="issueDetectedByCustomer_<?php echo $i;?>" name="issueDetectedByCustomer_<?php echo $i;?>" style="width:50px;">
				<?php
					echo"<option name='0' value='0'></option>";
					$tab=array(array(1,"Yes"),array(0,"No"));

					foreach($tab as $valeur)
					{
						$selected="";
						if($IssueDetectedByCustomer==$valeur[0]){$selected="selected";}
						echo "<option value='".$valeur[0]."' ".$selected.">".$valeur[1]."</option>\n";
					}
				?>
				</select>
			</td>
			<td>
				<select class="iCClosed_<?php echo $i;?>" name="iCClosed_<?php echo $i;?>" style="width:50px;">
				<?php
					echo"<option name='0' value='0'></option>";
					$tab=array(array(1,"Yes"),array(0,"No"));

					foreach($tab as $valeur)
					{
						$selected="";
						if($ICClosed==$valeur[0]){$selected="selected";}
						echo "<option value='".$valeur[0]."' ".$selected.">".$valeur[1]."</option>\n";
					}
				?>
				</select>
			</td>
			<td>
				<input type="texte" id="statusComments_<?php echo $i;?>" name="statusComments_<?php echo $i;?>" size="30" value="<?php echo $StatusComments;?>" >
			</td>
			<td>
				<select id="id_NameResponsible_<?php echo $i;?>" name="id_NameResponsible_<?php echo $i;?>" style="width:150px;">
				<?php
					echo"<option name='0' value='0'></option>";
					$req="SELECT DISTINCT new_rh_etatcivil.Id, Nom, Prenom 
						FROM gpao_workers 
						LEFT JOIN new_rh_etatcivil 
						ON gpao_workers.Id_Personne=new_rh_etatcivil.Id
						WHERE gpao_workers.Suppr=0 
						AND gpao_workers.Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
						ORDER BY Nom, Prenom";
					$result2=mysqli_query($bdd,$req);
					$nbResulta2=mysqli_num_rows($result2);
					if ($nbResulta2>0){
						while($row2=mysqli_fetch_array($result2)){
							$selected="";
							if($row2['Id']==$Id_NameResponsible){$selected="selected";}
							echo "<option name='".$row2['Id']."' value='".$row2['Id']."' ".$selected." >".$row2['Nom']." ".$row2['Prenom']."</option>";
						}
					}
				?>
				</select>
			</td>
			<td>
				<select id="id_NameResponsible2_<?php echo $i;?>" name="id_NameResponsible2_<?php echo $i;?>" style="width:150px;">
				<?php
					echo"<option name='0' value='0'></option>";
					$req="SELECT DISTINCT new_rh_etatcivil.Id, Nom, Prenom 
						FROM gpao_workers 
						LEFT JOIN new_rh_etatcivil 
						ON gpao_workers.Id_Personne=new_rh_etatcivil.Id
						WHERE gpao_workers.Suppr=0 
						AND gpao_workers.Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
						ORDER BY Nom, Prenom";
					$result2=mysqli_query($bdd,$req);
					$nbResulta2=mysqli_num_rows($result2);
					if ($nbResulta2>0){
						while($row2=mysqli_fetch_array($result2)){
							$selected="";
							if($row2['Id']==$Id_NameResponsible2){$selected="selected";}
							echo "<option name='".$row2['Id']."' value='".$row2['Id']."' ".$selected." >".$row2['Nom']." ".$row2['Prenom']."</option>";
						}
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
		<td colspan="12" align="center">
			<input class="Bouton" type="submit" style="font-size:15px;" name="Btn_Enregistrer2"  value="<?php if($_SESSION['Langue']=="EN"){echo "Save";}else{echo "Save";}?>">
		</td>
	</tr>
	<input type="hidden" name="nbLigneQ" id="nbLigneQ" value="<?php echo $totalQ; ?>" />
</table>
</br>
<table class="TableCompetences" width="99%" cellpadding="0" cellspacing="0">
	<tr>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Date";}else{echo "Date";} ?></td>
		<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION['Langue']=="EN"){echo "Worker";}else{echo "Worker";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Productive Time";}else{echo "Productive Time";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Idle time";}else{echo "Idle time";} ?></td>
		<td class="EnTeteTableauCompetences" width="30%"><?php if($_SESSION['Langue']=="EN"){echo "Cause of Idle time";}else{echo "Cause of Idle time";} ?></td>
		<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION['Langue']=="EN"){echo "Comments";}else{echo "Comments";} ?></td>
	</tr>
<?php 
	for($i=0;$i<$total;$i++){
		$DateProd="";
		$Id_Worker="";
		$ProductiveTime="";
		$IdleTime="";
		$Comments="";
		$Id_CauseIdleTime="";

		if ($i<$nbResulta){
			$row=mysqli_fetch_array($result);
			$DateProd=AfficheDateFR($row['DateProd']);
			$Id_Worker=$row['Id_Worker'];
			$ProductiveTime=$row['ProductiveTime'];
			$IdleTime=$row['IdleTime'];
			$Comments=stripslashes($row['Comments']);
			$Id_CauseIdleTime=$row['Id_CauseIdleTime'];
		}
?>
		<tr>
			<td>
				<input type="date" id="dateProd_<?php echo $i;?>" name="dateProd_<?php echo $i;?>" size="20" value="<?php echo $DateProd;?>">
			</td>
			<td>
				<select id="id_Worker_<?php echo $i;?>" name="id_Worker_<?php echo $i;?>" style="width:150px;">
				<?php
					echo"<option name='0' value='0'></option>";
					$req="SELECT DISTINCT new_rh_etatcivil.Id, Nom, Prenom 
						FROM gpao_workers 
						LEFT JOIN new_rh_etatcivil 
						ON gpao_workers.Id_Personne=new_rh_etatcivil.Id
						WHERE gpao_workers.Suppr=0 
						AND gpao_workers.Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
						ORDER BY Nom, Prenom";
					$result2=mysqli_query($bdd,$req);
					$nbResulta2=mysqli_num_rows($result2);
					if ($nbResulta2>0){
						while($row2=mysqli_fetch_array($result2)){
							$selected="";
							if($row2['Id']==$Id_Worker){$selected="selected";}
							echo "<option name='".$row2['Id']."' value='".$row2['Id']."' ".$selected." >".$row2['Nom']." ".$row2['Prenom']."</option>";
						}
					}
				?>
				</select>
			</td>
			<td>
				<input type="texte" onKeyUp="nombre(this)" id="productiveTime_<?php echo $i;?>" name="productiveTime_<?php echo $i;?>" size="8" value="<?php echo $ProductiveTime;?>" >
			</td>
			<td>
				<input type="texte" onKeyUp="nombre(this)" id="idleTime_<?php echo $i;?>" name="idleTime_<?php echo $i;?>" size="5" value="<?php echo $IdleTime;?>" >
			</td>
			<td>
				<select id="id_CauseIdleTime_<?php echo $i;?>" name="id_CauseIdleTime_<?php echo $i;?>" style="width:150px;">
				<?php
					echo"<option name='0' value='0'></option>";
					$req="SELECT Id, Libelle
						FROM gpao_reasonofblocking 
						WHERE Suppr=0 
						AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
						ORDER BY Libelle";
					$result2=mysqli_query($bdd,$req);
					$nbResulta2=mysqli_num_rows($result2);
					if ($nbResulta2>0){
						while($row2=mysqli_fetch_array($result2)){
							$selected="";
							if($row2['Id']==$Id_CauseIdleTime){$selected="selected";}
							echo "<option name='".$row2['Id']."' value='".$row2['Id']."' ".$selected.">".stripslashes($row2['Libelle'])."</option>";
						}
					}
				?>
				</select>
			</td>
			<td>
				<input type="texte" id="comments_<?php echo $i;?>" name="comments_<?php echo $i;?>" size="40" value="<?php echo $Comments;?>" >
			</td>
		</tr>
		<tr><td height="4"></td></tr>
<?php
	}
?>
	<tr>
		<td colspan="6" align="center">
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
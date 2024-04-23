<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<script>
		function VerifRemplissage(nbLigne){
			//Vérifier le remplissage des lignes
			//Si quelque chose est renseigné sur la ligne alors il faut que DateCoordination et Id_TechnicalCoordinator et ProductiveTime soit complété au minimum
			for(i=0;i<nbLigne;i++){
				if(document.getElementById('dateCoordination_'+i).value!="" || document.getElementById('id_TechnicalCoordinator'+i).value!="0" 
				|| document.getElementById('productiveTime_'+i).value!="" || document.getElementById('idleTime_'+i).value!=""
				|| document.getElementById('id_CauseIdleTime_'+i).value!="0" || document.getElementById('comments_'+i).value!=""){
					if(document.getElementById('dateCoordination_'+i).value=="" || document.getElementById('id_TechnicalCoordinator'+i).value=="0"
					|| document.getElementById('productiveTime_'+i).value==""){
						if(document.getElementById('Langue').value=="FR"){
							alert('Les champs "Date", "Technical coordinator" et "Productive time" doivent être complétés pour pouvoir enregistrer');return false;
						}
						else{
							alert('The "Date", "Technical coordinator" and "Productive time" fields must be completed in order to register');return false;
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
		$req="UPDATE gpao_coordinationtime 
			SET Suppr=1, DateSuppr='".date('Y-m-d')."', Id_Suppr=".$_SESSION['Id_Personne']." 
			WHERE Suppr=0 
			AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			AND Id_WO=".$_SESSION['GPAO_IdWO']." ";
		$result=mysqli_query($bdd,$req);
		//Recréer les nouveau CMTE
		for($i=0;$i<$_POST['nbLigne'];$i++){
			if($_POST['dateCoordination_'.$i]<>"" && $_POST['id_TechnicalCoordinator'.$i]<>"0" && $_POST['productiveTime_'.$i]<>""){
				$req="INSERT INTO gpao_coordinationtime (Id_PrestationGPAO,Id_WO,DateCoordination,Id_TechnicalCoordinator,ProductiveTime,IdleTime,Comments,Id_CauseIdleTime) 
				VALUES (".$_SESSION['Id_GPAO'].",".$_SESSION['GPAO_IdWO'].",'".TrsfDate_($_POST['dateCoordination_'.$i])."',
				'".$_POST['id_TechnicalCoordinator'.$i]."','".$_POST['productiveTime_'.$i]."','".$_POST['idleTime_'.$i]."',
				'".addslashes($_POST['comments_'.$i])."','".$_POST['id_CauseIdleTime_'.$i]."') ";
				$result=mysqli_query($bdd,$req);
			}
		}
	}

	echo "<script>FermerEtRecharger();</script>";
}
else
{
	$req="SELECT Id,DateCoordination,Id_TechnicalCoordinator,ProductiveTime,IdleTime,Comments,Id_CauseIdleTime
		FROM gpao_coordinationtime
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
<form id="formulaire" method="POST" action="Ajout_Coordination.php" onSubmit="return VerifRemplissage(<?php echo $total; ?>);">
<input type="hidden" name="btn" id="btn" value="" />
<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
<table class="TableCompetences" width="99%" cellpadding="0" cellspacing="0">
	<tr>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Date";}else{echo "Date";} ?></td>
		<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION['Langue']=="EN"){echo "Technical Coordinator";}else{echo "Technical Coordinator";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Productive Time";}else{echo "Productive Time";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Idle time";}else{echo "Idle time";} ?></td>
		<td class="EnTeteTableauCompetences" width="30%"><?php if($_SESSION['Langue']=="EN"){echo "Cause of Idle time";}else{echo "Cause of Idle time";} ?></td>
		<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION['Langue']=="EN"){echo "Comments";}else{echo "Comments";} ?></td>
	</tr>
<?php 
	for($i=0;$i<$total;$i++){
		$DateCoordination="";
		$Id_TechnicalCoordinator="";
		$ProductiveTime="";
		$IdleTime="";
		$Comments="";
		$Id_CauseIdleTime="";

		if ($i<$nbResulta){
			$row=mysqli_fetch_array($result);
			$DateCoordination=AfficheDateFR($row['DateCoordination']);
			$Id_TechnicalCoordinator=$row['Id_TechnicalCoordinator'];
			$ProductiveTime=$row['ProductiveTime'];
			$IdleTime=$row['IdleTime'];
			$Comments=stripslashes($row['Comments']);
			$Id_CauseIdleTime=$row['Id_CauseIdleTime'];
		}
?>
		<tr>
			<td>
				<input type="date" id="dateCoordination_<?php echo $i;?>" name="dateCoordination_<?php echo $i;?>" size="20" value="<?php echo $DateCoordination;?>">
			</td>
			<td>
				<select id="id_TechnicalCoordinator<?php echo $i;?>" name="id_TechnicalCoordinator<?php echo $i;?>" style="width:150px;">
				<?php
					echo"<option name='0' value='0'></option>";
					$req="SELECT DISTINCT new_rh_etatcivil.Id, Nom, Prenom 
						FROM gpao_coordinationworker 
						LEFT JOIN new_rh_etatcivil 
						ON gpao_coordinationworker.Id_Personne=new_rh_etatcivil.Id
						WHERE gpao_coordinationworker.Suppr=0 
						AND gpao_coordinationworker.Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
						ORDER BY Nom, Prenom";
					$result2=mysqli_query($bdd,$req);
					$nbResulta2=mysqli_num_rows($result2);
					if ($nbResulta2>0){
						while($row2=mysqli_fetch_array($result2)){
							$selected="";
							if($row2['Id']==$Id_TechnicalCoordinator){$selected="selected";}
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
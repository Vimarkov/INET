<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<script>
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
	
if($_POST){
	//Calcul de Last EoW 
	$thelastEoW="";
	if($_POST['firstEoW']<>""){
		if($_POST['newEoW']<>""){
			$thelastEoW=$_POST['newEoW'];
		}
		else{
			$thelastEoW=$_POST['firstEoW'];
		}
	}
	
	$req="UPDATE gpao_wo 
		SET 
			Id_Priority=".$_POST['priority'].",
			PriorityReason='".addslashes($_POST['priorityReason'])."',
			EscalationPoint=".$_POST['escalationpoint'].",
			PlanDate='".TrsfDate_($_POST['plandate'])."',
			LimitDateFOT='".TrsfDate_($_POST['firstEoW'])."',
			NewEoW='".TrsfDate_($_POST['newEoW'])."',
			Id_WorkingShift=".$_POST['workingShift'].",
			Comments='".addslashes($_POST['commentACMS1'])."',
			CommentsA_CMS2='".addslashes($_POST['commentACMS2'])."',
			OTDEoW=".$_POST['otdEoW'].",
			OTDComment='".addslashes($_POST['otdComment'])."',
			UpdateDateTandem='".TrsfDate_($_POST['EoWTandem'])."',
			LastEoW='".TrsfDate_($thelastEoW)."'
		WHERE Id=".$_POST['id']." ";
	$result=mysqli_query($bdd,$req);
	
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
?>

	<form id="formulaire" method="POST" action="Ajout_Plannification.php">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<input type="hidden" name="id" value="<?php echo $_GET['Id']; ?>">
		<table width="95%" align="center" class="TableCompetences">
			<?php 
			$id_Priority=0;
			$priorityReason="";
			$escalationPoint=-1;
			$planDate="";
			$firstEoW="";
			$newEoW="";
			$workingShift=0;
			$commentACMS1="";
			$commentACMS2="";
			$OTDEoW=-1;
			$OTDComment="";
			$EoWTandem="";
			
			$req="SELECT Id,Id_Customer,Id_Imputation,Skills,Id_CostCenter,
					(SELECT Position FROM gpao_aircraft WHERE Id=Id_Aircraft) AS Position,
					Para,AM,NC,Concession,
					OF,QLB,TLB,
					TargetTime,EscalationPoint,Id_Priority,PriorityReason,
					LimitDateFOT,NewEoW,LastEoW,UpdateDateTandem,
					PlanDate,Id_WorkingShift,WorkingProgress,ClosureDate,
					OTDEoW,OTDComment,
					Designation,Comments,CommentsA_CMS2,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_CreatedBy) AS CreatedBy,
					Id_CreatedBy,CreationDate
				FROM gpao_wo 
				WHERE Id=".$_GET['Id']." ";
			$resultList=mysqli_query($bdd,$req);
			$nbList=mysqli_num_rows($resultList);

			if ($nbList > 0)
			{
				$rowList=mysqli_fetch_array($resultList);

				$id_Priority=$rowList['Id_Priority'];
				$priorityReason=stripslashes($rowList['PriorityReason']);
				$escalationPoint=stripslashes($rowList['EscalationPoint']);
				$planDate=AfficheDateFR($rowList['PlanDate']);
				$firstEoW=AfficheDateFR($rowList['LimitDateFOT']);
				$newEoW=AfficheDateFR($rowList['NewEoW']);
				$workingShift=stripslashes($rowList['Id_WorkingShift']);
				$commentACMS1=stripslashes($rowList['Comments']);
				$commentACMS2=stripslashes($rowList['CommentsA_CMS2']);
				$OTDEoW=stripslashes($rowList['OTDEoW']);
				$OTDComment=stripslashes($rowList['OTDComment']);
				$EoWTandem=AfficheDateFR($rowList['UpdateDateTandem']);
			}
			?>
			<tr><td height="8"></td></tr>
			<tr>
				<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Priority";}else{echo "Priority";} ?> :</td>
				<td width='7%'>
					<select class="priority" name="priority" style="width:130px;">
						<option value="0"></option>
					<?php
						$req="SELECT Id,Libelle
						FROM gpao_priority
						WHERE Suppr=0
						AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
						ORDER BY Libelle";
						$resultList=mysqli_query($bdd,$req);
						$nbList=mysqli_num_rows($resultList);

						if ($nbList > 0)
						{
							while($rowList=mysqli_fetch_array($resultList))
							{
								$selected="";
								if($id_Priority==$rowList['Id']){$selected="selected";}
								echo "<option value='".$rowList['Id']."' ".$selected.">".$rowList['Libelle']."</option>\n";
							}
						 }
					?>
					</select>
				</td>
				<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Priority reason";}else{echo "Priority reason";} ?> :</td>
				<td width='7%'>
					<input type="texte" name="priorityReason" id="priorityReason" size="20" value="<?php echo $priorityReason;?>">
				</td>
				<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Escalation point";}else{echo "Escalation point";} ?> :</td>
				<td width='7%'>
					<select class="escalationpoint" name="escalationpoint" style="width:50px;">
						<option value="0"></option>
					<?php
						$tab=array(array(1,"Yes"),array(0,"No"));

						foreach($tab as $valeur)
						{
							$selected="";
							if($escalationPoint==$valeur[0]){$selected="selected";}
							echo "<option value='".$valeur[0]."' ".$selected.">".$valeur[1]."</option>\n";
						}
					?>
					</select>
				</td>
			</tr>
			<tr><td height="8"></td></tr>
			<tr>
				<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Plan date";}else{echo "Plan date";} ?> :</td>
				<td width='7%'>
					<input type="date" name="plandate" id="plandate" size="20" value="<?php echo $planDate;?>">
				</td>
			</tr>
			<tr><td height="8"></td></tr>
			<tr>
				<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "First EoW";}else{echo "First EoW";} ?> :</td>
				<td width='7%'>
					<input type="date" name="firstEoW" id="firstEoW" size="20" value="<?php echo $firstEoW;?>">
				</td>
				<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "New EoW";}else{echo "New EoW";} ?> :</td>
				<td width='7%'>
					<input type="date" name="newEoW" id="newEoW" size="20" value="<?php echo $newEoW;?>">
				</td>
				<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Working shift";}else{echo "Working shift";} ?> :</td>
				<td width='7%'>
					<select class="workingShift" name="workingShift" style="width:130px;">
						<option value="0"></option>
					<?php
						$req="SELECT Id,Libelle
						FROM gpao_workingshifts
						WHERE Suppr=0
						AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
						ORDER BY Libelle";
						$resultList=mysqli_query($bdd,$req);
						$nbList=mysqli_num_rows($resultList);

						if ($nbList > 0)
						{
							while($rowList=mysqli_fetch_array($resultList))
							{
								$selected="";
								if($workingShift==$rowList['Id']){$selected="selected";}
								echo "<option value='".$rowList['Id']."' ".$selected.">".$rowList['Libelle']."</option>\n";
							}
						 }
					?>
					</select>
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
			<tr>
				<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "OTD EoW";}else{echo "OTD EoW";} ?> :</td>
				<td width='7%'>
					<select class="otdEoW" name="otdEoW" style="width:50px;">
						<option value="0"></option>
					<?php
						$tab=array(array(1,"Yes"),array(0,"No"));

						foreach($tab as $valeur)
						{
							$selected="";
							if($OTDEoW==$valeur[0]){$selected="selected";}
							echo "<option value='".$valeur[0]."' ".$selected.">".$valeur[1]."</option>\n";
						}
					?>
					</select>
				</td>
				<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "OTD Comment";}else{echo "OTD Comment";} ?> :</td>
				<td width='7%'>
					<select class="otdComment" name="otdComment" style="width:150px;">
						<option value=""></option>
					<?php
						$tab=array("Missing documentation/assessment","Missing tools","No access_IC not signed","No Access_Intervention by third parties","No manpower","No qualification/competencies","Rework - Coordination;Rework - Production","Rework - Quality;Rework - Third parties","RRA Process","Waiting for dress Out Airbus;Waiting for MAP","Waiting for NDT;Waiting for paintshop","Waiting for Para to be stamped","Waiting for parts/fasteners/chemical products","Waiting for sandblasting","Work done in time - Concession INP");

						foreach($tab as $valeur)
						{
							$selected="";
							if($OTDComment==$valeur){$selected="selected";}
							echo "<option value='".$valeur."' ".$selected.">".$valeur."</option>\n";
						}
					?>
					</select>
				</td>
				<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "EoW in Tandem";}else{echo "EoW in Tandem";} ?> :</td>
				<td width='7%'>
					<input type="date" name="EoWTandem" id="EoWTandem" size="20" value="<?php echo $EoWTandem;?>">
				</td>
			</tr>
			<tr><td height="5px"></td></tr>
			<tr class="TitreColsUsers">
				<td colspan="6" align="center">
					<input class="Bouton" onclick="submit();" value="<?php if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}?>">
				</td>
			</tr>
		</table>
	</form>
<?php
}
?>
	
</body>
</html>
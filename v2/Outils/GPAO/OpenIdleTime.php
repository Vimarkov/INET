<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<script>
		
	</script>
	<script language="javascript" src="Fonctions_GPAO.js?t=<?php echo time(); ?>"></script>
</head>
<body>
<?php

session_start();
require("../Connexioni.php");
require("../Fonctions.php");
	
if($_POST){
	$Id_Worker=$_POST['worker'];
	$Date=$_POST['dateWO'];
}
else{
	$Id_Worker=0;
	$Date="";
}

?>

<form id="formulaire" method="POST" action="OpenIdleTime.php">
	<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
	<table width="95%" align="center" class="TableCompetences">
		<tr><td height="8"></td></tr>
		<tr>
			<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "Worker";}else{echo "Worker";} ?> :</td>
			<td width='10%'>
				<select class="worker" name="worker" style="width:130px;" onchange="submit();">
					<option value='0'></option>
				<?php
					$req="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(Nom,' ',Prenom) AS Libelle 
					FROM gpao_workers 
					LEFT JOIN new_rh_etatcivil 
					ON gpao_workers.Id_Personne=new_rh_etatcivil.Id
					WHERE gpao_workers.Suppr=0 
					AND gpao_workers.Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
					ORDER BY Nom, Prenom";
					$resultList=mysqli_query($bdd,$req);
					$nbList=mysqli_num_rows($resultList);
					
					if ($nbList > 0)
					{
						while($rowList=mysqli_fetch_array($resultList))
						{
							$selected="";
							if($Id_Worker==$rowList['Id']){$selected="selected";}
							echo "<option value='".$rowList['Id']."' ".$selected.">".$rowList['Libelle']."</option>\n";
						}
					 }
				?>
				</select>
			</td>
			<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "Date";}else{echo "Date";} ?> :</td>
			<td width='10%'>
				<input type="date" name="dateWO" id="dateWO" size="20" value="<?php echo $Date;?>">
			</td>
			<td align="center">
				<input class="Bouton" onclick="submit();" value="<?php if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}?>">
			</td>
		</tr>
		<tr><td height="8"></td></tr>
		<?php 
		
		$productiveTime="";
		$idleTime="";
		
		if($Id_Worker>0 && $Date<>""){
		?>
		<tr>
			<td colspan='5'>
				<table width="60%" align="left" class="TableCompetences">
					<tr>
						<td width="20%" class="EnTeteTableauCompetences"><?php if($_SESSION['Langue']=="EN"){echo "Idle time";}else{echo "Idle time";}?></td>
						<td width="80%" class="EnTeteTableauCompetences"><?php if($_SESSION['Langue']=="EN"){echo "Comments";}else{echo "Comments";}?></td>
					</tr>
					<?php
						$req="SELECT gpao_productionsheet.IdleTime,gpao_productionsheet.Comments
							FROM gpao_productionsheet 
							LEFT JOIN gpao_wo
							ON gpao_productionsheet.Id_WO=gpao_wo.Id
							WHERE gpao_productionsheet.Suppr=0 
							AND gpao_wo.Suppr=0 
							AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
							AND Id_Worker=".$Id_Worker." 
							AND DateProd='".TrsfDate_($Date)."' ";
						$resultList=mysqli_query($bdd,$req);
						$nbList=mysqli_num_rows($resultList);

						if ($nbList > 0)
						{
							while($rowList=mysqli_fetch_array($resultList)){
					?>
					<tr>
						<td><?php echo $rowList['IdleTime']; ?></td>
						<td><?php echo stripslashes($rowList['Comments']); ?></td>
					</tr>
					<?php
							}
						}
					?>
				</table>
			</td>
		</tr>
		<?php
		}
		
		?>
		<tr><td height="5px"></td></tr>
	</table>
</form>
	
</body>
</html>
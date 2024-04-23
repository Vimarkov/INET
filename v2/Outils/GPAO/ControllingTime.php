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
	$Id_Customer=$_POST['customer'];
	$Date=$_POST['dateWO'];
	$Id_Worker=$_POST['worker'];
}
else{
	$Id_Customer=0;
	$Date="";
	$Id_Worker=0;
}

?>

<form id="formulaire" method="POST" action="ControllingTime.php">
	<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
	<table width="95%" align="center" class="TableCompetences">
		<?php 
		
		$productiveTime="";
		$idleTime="";
		
		if($Id_Customer>0){
			$req="SELECT SUM(ProductiveTime) AS SommeProductiveTime,
				SUM(IdleTime) AS SommeIdleTime
				FROM gpao_productionsheet 
				LEFT JOIN gpao_wo
				ON gpao_productionsheet.Id_WO=gpao_wo.Id
				WHERE gpao_productionsheet.Suppr=0 
				AND gpao_wo.Suppr=0 
				AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
				AND Id_Customer=".$Id_Customer." ";
			if($Id_Worker>0){
				$req.="AND Id_Worker=".$Id_Worker." ";
			}
			if($Date<>""){
				$req.="AND DateProd='".TrsfDate_($Date)."' ";
			}
			$req.="GROUP BY Id_Customer ";
			$resultList=mysqli_query($bdd,$req);
			$nbList=mysqli_num_rows($resultList);

			if ($nbList > 0)
			{
				$rowList=mysqli_fetch_array($resultList);

				$productiveTime=$rowList['SommeProductiveTime'];
				$idleTime=$rowList['SommeIdleTime'];
			}
		}
		
		?>
		<tr><td height="8"></td></tr>
		<tr>
			<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "Customer";}else{echo "Customer";} ?> :</td>
			<td width='10%'>
				<select class="customer" name="customer" style="width:130px;" onchange="submit();">
					<option value='0'></option>
				<?php
					$req="SELECT Id,Libelle
					FROM gpao_customer
					WHERE Suppr=0 
					AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." ";
					$req.="ORDER BY Libelle";
					$resultList=mysqli_query($bdd,$req);
					$nbList=mysqli_num_rows($resultList);
					
					if ($nbList > 0)
					{
						while($rowList=mysqli_fetch_array($resultList))
						{
							$selected="";
							if($Id_Customer==$rowList['Id']){$selected="selected";}
							echo "<option value='".$rowList['Id']."' ".$selected.">".$rowList['Libelle']."</option>\n";
						}
					 }
				?>
				</select>
			</td>
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
		<tr>
			<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "Productive time";}else{echo "Productive time";} ?> :</td>
			<td width='10%'>
				<input readonly='readonly' name="productiveTime" id="productiveTime" size="10" value="<?php echo $productiveTime;?>">
			</td>
			<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "Idle time";}else{echo "Idle time";} ?> :</td>
			<td width='10%'>
				<input readonly='readonly' name="idleTime" id="idleTime" size="10" value="<?php echo $idleTime;?>">
			</td>
		</tr>
		<tr><td height="5px"></td></tr>
	</table>
</form>
	
</body>
</html>
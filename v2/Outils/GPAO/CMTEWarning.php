<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<script>
		function OuvreFenetreExcel(Lien){
			if(document.getElementById('reference').value!=""){
				var w=window.open("Extract_"+Lien+".php?Reference="+document.getElementById('reference').value,"Page"+Lien,"status=no,menubar=no,scrollbars=yes,width=50,height=50");
				w.focus();
			}
			else{
				var w=window.open("Extract_"+Lien+".php?Reference=","Page"+Lien,"status=no,menubar=no,scrollbars=yes,width=50,height=50");
				w.focus();
			}
		}
	</script>
	<script language="javascript" src="Fonctions_GPAO.js?t=<?php echo time(); ?>"></script>
</head>
<body>
<?php

session_start();
require("../Connexioni.php");
require("../Fonctions.php");
?>

<form id="formulaire" method="POST" action="CMTEWarning.php">
	<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
	<table width="99%" align="center" class="TableCompetences">
		<tr>
			<td class="EnTeteTableauCompetences" width="15%" colspan="4"><?php if($_SESSION['Langue']=="EN"){echo "The Following Tools Need to be Calibrated!";}else{echo "The Following Tools Need to be Calibrated!";}?></td>
		</tr>
		<tr>
			<td class="EnTeteTableauCompetences" width="15%" ><?php if($_SESSION['Langue']=="EN"){echo "Tool Type";}else{echo "Tool Type";}?></td>
			<td class="EnTeteTableauCompetences" width="10%" ><?php if($_SESSION['Langue']=="EN"){echo "Reference";}else{echo "Reference";}?></td>
			<td class="EnTeteTableauCompetences" width="10%" ><?php if($_SESSION['Langue']=="EN"){echo "Next Calibration Date";}else{echo "Next Calibration Date";}?></td>
			<td class="EnTeteTableauCompetences" width="10%" ><?php if($_SESSION['Langue']=="EN"){echo "Date of Use";}else{echo "Date of Use";}?></td>
	
		</tr>
		<?php 
		
		$req="SELECT DISTINCT gpao_cmte.Reference,gpao_cmte.ToolType,gpao_cmte.NextCalibrationDate,gpao_cmte.DateOfUse
			FROM gpao_cmte 
			LEFT JOIN gpao_wo
			ON gpao_cmte.Id_WO=gpao_wo.Id
			WHERE gpao_cmte.Suppr=0 
			AND gpao_wo.Suppr=0 
			AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			AND NextCalibrationDate>'0001-01-01'
			AND NextCalibrationDate < '".date('Y-m-d',strtotime(date('Y-m-d')." + 1 month"))."' ";
			
		$resultList=mysqli_query($bdd,$req);
		$nbList=mysqli_num_rows($resultList);

		if ($nbList > 0)
		{
			
			
			while($rowList=mysqli_fetch_array($resultList)){
				if($rowList['NextCalibrationDate']<date('Y-m-d')){
					$couleur="#ed1b24";
				}
				else{
					$couleur="#f7bf1d";
				}
	?>
			<tr bgcolor="<?php echo $couleur;?>">
				<td><?php echo stripslashes($rowList['ToolType']); ?></td>
				<td><?php echo stripslashes($rowList['Reference']); ?></td>
				<td><?php echo AfficheDateJJ_MM_AAAA($rowList['NextCalibrationDate']); ?></td>
				<td><?php echo AfficheDateJJ_MM_AAAA($rowList['DateOfUse']); ?></td>
			</tr>
	<?php
			}
		}
		?>
		<tr><td height="5px"></td></tr>
	</table>
</form>
	
</body>
</html>
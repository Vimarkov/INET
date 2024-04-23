<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<script>
		function OuvreFenetreExcel(Lien,All = 0){
			if(Lien=="ChemicalProductWarning"){
				var w=window.open("Extract_"+Lien+".php","PageE"+Lien,"status=no,menubar=no,scrollbars=yes,width=50,height=50");
				w.focus();
			}
			else{
				if(All==0){
					if(document.getElementById('reference').value!=""){
						var w=window.open("Extract_"+Lien+".php?Reference="+document.getElementById('reference').value,"PageE"+Lien,"status=no,menubar=no,scrollbars=yes,width=50,height=50");
						w.focus();
					}
				}
				else{
					var w=window.open("Extract_"+Lien+".php?Reference=","PageE"+Lien,"status=no,menubar=no,scrollbars=yes,width=50,height=50");
					w.focus();
				}
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
	
if($_POST){
	$reference=$_POST['reference'];
}
else{
	$reference="";
}

?>

<form id="formulaire" method="POST" action="ChemicalProductSearch.php">
	<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
	<table width="95%" align="center" class="TableCompetences">
		<tr><td height="8"></td></tr>
		<tr>
			<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "Reference";}else{echo "Reference";} ?> :</td>
			<td width='10%'>
				<input type="texte" name="reference" id="reference" size="12" value="<?php echo $reference;?>">
			</td>
			<td align="center">
				<input class="Bouton" onclick="submit();" value="<?php if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}?>">
			</td>
			<td align="right">
					<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreExcel('ChemicalProduct',0)"><?php if($_SESSION['Langue']=="EN"){echo "Extract Selection";}else{echo "Extract Selection";}?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreExcel('ChemicalProduct',1)"><?php if($_SESSION['Langue']=="EN"){echo "Extract All Data";}else{echo "Extract All Data";}?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreExcel('ChemicalProductWarning')"><?php if($_SESSION['Langue']=="EN"){echo "Upcoming Calibration";}else{echo "Upcoming Calibration";}?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
	</table><br>
	<table width="99%" align="center" class="TableCompetences">
		<tr>
			<td class="EnTeteTableauCompetences" width="5%" ><?php if($_SESSION['Langue']=="EN"){echo "MSN";}else{echo "MSN";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "NC";}else{echo "NC";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "OF/OT";}else{echo "OF/OT";}?></td>
			<td class="EnTeteTableauCompetences" width="10%" ><?php if($_SESSION['Langue']=="EN"){echo "Product Type";}else{echo "Product Type";}?></td>
			<td class="EnTeteTableauCompetences" width="10%" ><?php if($_SESSION['Langue']=="EN"){echo "Product Reference";}else{echo "Product Reference";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "Batch n°";}else{echo "Batch n°";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "Expiration Date";}else{echo "Expiration Date";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "Application Date";}else{echo "Application Date";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "Application Time";}else{echo "Application Time";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "Thermohydrometer n°";}else{echo "Thermohydrometer n°";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "T°C";}else{echo "T°C";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "Humidity";}else{echo "Humidity";}?></td>
		</tr>
		<?php 
		
		if($reference<>""){
			$req="SELECT (SELECT MSN FROM gpao_aircraft WHERE Id=Id_Aircraft) AS MSN,
			gpao_chemicalproduct.ProductType,gpao_chemicalproduct.ProductReference,
				gpao_chemicalproduct.NumBatch,gpao_chemicalproduct.ExpirationDate,gpao_chemicalproduct.ApplicationDate,
				gpao_chemicalproduct.ApplicationTime,gpao_chemicalproduct.NumThermohydrometer,
				gpao_chemicalproduct.TemperatureC,gpao_chemicalproduct.Humidity,
				NC,OF
				FROM gpao_chemicalproduct 
				LEFT JOIN gpao_wo
				ON gpao_chemicalproduct.Id_WO=gpao_wo.Id
				WHERE gpao_chemicalproduct.Suppr=0 
				AND gpao_wo.Suppr=0 
				AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
				AND ProductReference LIKE '%".$reference."%' ";

			$resultList=mysqli_query($bdd,$req);
			$nbList=mysqli_num_rows($resultList);

			if ($nbList > 0)
			{
				$couleur="#ffffff";
				while($rowList=mysqli_fetch_array($resultList)){
		?>
				<tr bgcolor="<?php echo $couleur;?>">
					<td><?php echo stripslashes($rowList['MSN']); ?></td>
					<td><?php echo stripslashes($rowList['NC']); ?></td>
					<td><?php echo stripslashes($rowList['OF']); ?></td>
					<td><?php echo stripslashes($rowList['ProductType']); ?></td>
					<td><?php echo stripslashes($rowList['ProductReference']); ?></td>
					<td><?php echo stripslashes($rowList['NumBatch']); ?></td>
					<td><?php echo AfficheDateJJ_MM_AAAA($rowList['ExpirationDate']); ?></td>
					<td><?php echo AfficheDateJJ_MM_AAAA($rowList['ApplicationDate']); ?></td>
					<td><?php echo stripslashes($rowList['ApplicationTime']); ?></td>
					<td><?php echo stripslashes($rowList['NumThermohydrometer']); ?></td>
					<td><?php echo stripslashes($rowList['TemperatureC']); ?></td>
					<td><?php echo stripslashes($rowList['Humidity']); ?></td>
				</tr>
		<?php
				if($couleur=="#ffffff"){$couleur="#a3e4ff";}
				else{$couleur="#ffffff";}
				}
			}
		}
		?>
		<tr><td height="5px"></td></tr>
	</table>
</form>
	
</body>
</html>
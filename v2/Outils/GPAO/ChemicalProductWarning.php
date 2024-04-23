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
?>

<form id="formulaire" method="POST" action="ChemicalProductWarning.php">
	<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
	<table width="99%" align="center" class="TableCompetences">
		<tr>
			<td class="EnTeteTableauCompetences" width="15%" colspan="6"><?php if($_SESSION['Langue']=="EN"){echo "The Following Chemical Products Soon Expire!";}else{echo "The Following Chemical Products Soon Expire!";}?></td>
		</tr>
		<tr>
			<td class="EnTeteTableauCompetences" width="15%" ><?php if($_SESSION['Langue']=="EN"){echo "Product Type";}else{echo "Product Type";}?></td>
			<td class="EnTeteTableauCompetences" width="15%" ><?php if($_SESSION['Langue']=="EN"){echo "Product Reference";}else{echo "Product Reference";}?></td>
			<td class="EnTeteTableauCompetences" width="15%" ><?php if($_SESSION['Langue']=="EN"){echo "Batch n°";}else{echo "Batch n°";}?></td>
			<td class="EnTeteTableauCompetences" width="15%" ><?php if($_SESSION['Langue']=="EN"){echo "Expiration Date";}else{echo "Expiration Date";}?></td>
			<td class="EnTeteTableauCompetences" width="15%" ><?php if($_SESSION['Langue']=="EN"){echo "Application Date";}else{echo "Application Date";}?></td>
			<td class="EnTeteTableauCompetences" width="15%" ><?php if($_SESSION['Langue']=="EN"){echo "Application Time";}else{echo "Application Time";}?></td>
		</tr>
		<?php 
		
		$req="SELECT DISTINCT gpao_chemicalproduct.ProductType,gpao_chemicalproduct.ProductReference,
			gpao_chemicalproduct.NumBatch,gpao_chemicalproduct.ExpirationDate,gpao_chemicalproduct.ApplicationDate,
			gpao_chemicalproduct.ApplicationTime
			FROM gpao_chemicalproduct 
			LEFT JOIN gpao_wo
			ON gpao_chemicalproduct.Id_WO=gpao_wo.Id
			WHERE gpao_chemicalproduct.Suppr=0 
			AND gpao_wo.Suppr=0 
			AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			AND ExpirationDate>'0001-01-01'
			AND ExpirationDate < '".date('Y-m-d',strtotime(date('Y-m-d')." + 1 month"))."' ";
			
		$resultList=mysqli_query($bdd,$req);
		$nbList=mysqli_num_rows($resultList);

		if ($nbList > 0)
		{
			
			
			while($rowList=mysqli_fetch_array($resultList)){
				if($rowList['ExpirationDate']<date('Y-m-d')){
					$couleur="#ed1b24";
				}
				else{
					$couleur="#f7bf1d";
				}
	?>
			<tr bgcolor="<?php echo $couleur;?>">
				<td><?php echo stripslashes($rowList['ProductType']); ?></td>
				<td><?php echo stripslashes($rowList['ProductReference']); ?></td>
				<td><?php echo stripslashes($rowList['NumBatch']); ?></td>
				<td><?php echo AfficheDateJJ_MM_AAAA($rowList['ExpirationDate']); ?></td>
				<td><?php echo AfficheDateJJ_MM_AAAA($rowList['ApplicationDate']); ?></td>
				<td><?php echo stripslashes($rowList['ApplicationTime']); ?></td>
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
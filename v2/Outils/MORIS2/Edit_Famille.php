<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/webforms2-0/webforms2-p.js"></script>	
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script src="../JS/colorpicker.js"></script>
	<script language="javascript" src="MORIS13.js"></script>
</head>
<body>
<?php

session_start();
require("../Connexioni.php");
require("../Fonctions.php");
?>
<form id="formulaire" method="POST" action="Edit_Famille.php" >
<table width="95%" align="center" class="TableCompetences">
	<tr class="TitreColsUsers">
		<td class="Libelle" width="50%"><?php if($_SESSION['Langue']=="EN"){echo "Internal";}else{echo "Interne";} ?> </td>
		<td class="Libelle" width="50%"><?php if($_SESSION['Langue']=="EN"){echo "External";}else{echo "Externe";} ?> </td>
	</tr>
	<tr>
		<td width="50%" >
			<table  width="90%" style="border:1px dotted black;" >
				<?php 
					$req="SELECT Id, Libelle 
						FROM moris_famille 
						WHERE Suppr=0 ";
					if($_GET['Id']>0){
						$req.="OR Id IN (SELECT Id_Famille FROM moris_moisprestation_famille WHERE Id_MoisPrestation=".$_GET['Id']." )";
					}
					$req.="ORDER BY Libelle";
					$resultFamille=mysqli_query($bdd,$req);
					$nbFamille=mysqli_num_rows($resultFamille);
					if($nbFamille>0){
						while($rowFamille=mysqli_fetch_array($resultFamille)){
				?>
				<tr>
					<td><input type="checkbox" class="interne" name="interne<?php echo $rowFamille['Id'];?>" id="interne<?php echo $rowFamille['Id'];?>" onChange="AfficherChargeCapa()" /><?php echo stripslashes($rowFamille['Libelle']);?></td>
				</tr>
				<?php
						}
					}
				?>
			</table>
		</td>
		<td width="50%" >
			<table  width="90%" style="border:1px dotted black;" >
				<?php 
					$req="SELECT Id, Libelle 
						FROM moris_famille 
						WHERE Suppr=0 ";
					if($_GET['Id']>0){
						$req.="OR Id IN (SELECT Id_Famille FROM moris_moisprestation_famille WHERE Id_MoisPrestation=".$_GET['Id']." )";
					}
					$req.="ORDER BY Libelle";
					$resultFamille=mysqli_query($bdd,$req);
					$nbFamille=mysqli_num_rows($resultFamille);
					if($nbFamille>0){
						while($rowFamille=mysqli_fetch_array($resultFamille)){
				?>
				<tr>
					<td><input type="checkbox" class="externe" name="externe<?php echo $rowFamille['Id'];?>" id="externe<?php echo $rowFamille['Id'];?>" onChange="AfficherChargeCapa()" /><?php echo stripslashes($rowFamille['Libelle']);?></td>
				</tr>
				<?php
						}
					}
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input class="Bouton" type="submit" style="font-size:15px;" name="Btn_Fermer" onClick="window.close();" value="<?php if($_SESSION['Langue']=="EN"){echo "Close";}else{echo "Fermer";}?>">
		</td>
	</tr>
</table>
</form>
<?php 
echo "<script>CocherFamilles();</script>";
?>
</body>
</html>
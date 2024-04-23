<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<script>
		function OuvreFenetreExcel(){
			if(document.getElementById('annee').value!=""){
				var w=window.open("Extract_WorkTimeMonth.php?Annee="+document.getElementById('annee').value+"&Mois="+document.getElementById('mois').value,"PageProduction","status=no,menubar=no,scrollbars=yes,width=50,height=50");
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
	
if($_POST){
	$annee=$_POST['annee'];
	$mois=$_POST['mois'];
}
else{
	$annee="";
	$mois="";
}

?>

<form id="formulaire" method="POST" action="WorkTimeMonth.php">
	<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
	<table width="95%" align="center" class="TableCompetences">
		<tr><td height="8"></td></tr>
		<tr>
			<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "Year";}else{echo "Year";} ?> :</td>
			<td width='10%'>
				<input type="texte" onKeyUp="nombre(this)" name="annee" id="annee" size="8" value="<?php echo $annee;?>">
			</td>

			<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "Month";}else{echo "Month";} ?> :</td>
			<td width='10%'>
				<input type="texte" onKeyUp="nombre(this)" name="mois" id="mois" size="8" value="<?php echo $mois;?>">
			</td>
			<td align="center">
				<input class="Bouton" onclick="submit();" value="<?php if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}?>">
			</td>
		</tr>
		<tr><td height="5px"></td></tr>
		<tr>
			<td align="right" colspan="6">
					<a style="text-decoration:none;" href="javascript:OuvreFenetreExcel()"><img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel"></a>
			</td>
		</tr>
	</table><br>
	<table width="99%" align="center" class="TableCompetences">
		<tr>
			<td class="EnTeteTableauCompetences" width="40%" ><?php if($_SESSION['Langue']=="EN"){echo "Worker";}else{echo "Worker";}?></td>
			<td class="EnTeteTableauCompetences" width="20%" ><?php if($_SESSION['Langue']=="EN"){echo "Productive Time";}else{echo "Productive Time";}?></td>
			<td class="EnTeteTableauCompetences" width="20%" ><?php if($_SESSION['Langue']=="EN"){echo "Idle Time";}else{echo "Idle Time";}?></td>
			<td class="EnTeteTableauCompetences" width="20%" ><?php if($_SESSION['Langue']=="EN"){echo "Total";}else{echo "Total";}?></td>
		</tr>
		<?php 
		
		if($annee<>""){
			$req="SELECT Id_Worker,(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Worker) AS Worker,
				SUM(ProductiveTime) AS SommeProductiveTime,
				SUM(IdleTime) AS SommeIdleTime
				FROM gpao_productionsheet 
				LEFT JOIN gpao_wo
				ON gpao_productionsheet.Id_WO=gpao_wo.Id
				WHERE gpao_productionsheet.Suppr=0 
				AND gpao_wo.Suppr=0 
				AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
				AND YEAR(DateProd)='".$annee."' ";
			if($mois<>""){
				$req.="AND MONTH(DateProd)='".$mois."' ";
			}
			$req.="	GROUP BY Id_Worker 
				ORDER BY Worker";
			$resultList=mysqli_query($bdd,$req);
			$nbList=mysqli_num_rows($resultList);

			if ($nbList > 0)
			{
				$couleur="#ffffff";
				while($rowList=mysqli_fetch_array($resultList)){
					$total=$rowList['SommeProductiveTime']+$rowList['SommeIdleTime'];
		?>
				<tr bgcolor="<?php echo $couleur;?>">
					<td><?php echo $rowList['Worker']; ?></td>
					<td><?php echo $rowList['SommeProductiveTime']; ?></td>
					<td><?php echo $rowList['SommeIdleTime']; ?></td>
					<td><?php echo $total; ?></td>
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
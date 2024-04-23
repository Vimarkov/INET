<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<script>
		function OuvreFenetreExcel(Lien,All = 0){
			if(Lien=="CMTEWarning"){
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

<form id="formulaire" method="POST" action="CMTESearch.php">
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
					<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreExcel('CMTE',0)"><?php if($_SESSION['Langue']=="EN"){echo "Extract Selection";}else{echo "Extract Selection";}?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreExcel('CMTE',1)"><?php if($_SESSION['Langue']=="EN"){echo "Extract All Data";}else{echo "Extract All Data";}?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreExcel('CMTEWarning')"><?php if($_SESSION['Langue']=="EN"){echo "Upcoming Calibration";}else{echo "Upcoming Calibration";}?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
	</table><br>
	<table width="99%" align="center" class="TableCompetences">
		<tr>
			<td class="EnTeteTableauCompetences" width="5%" ><?php if($_SESSION['Langue']=="EN"){echo "MSN";}else{echo "MSN";}?></td>
			<td class="EnTeteTableauCompetences" width="10%" ><?php if($_SESSION['Langue']=="EN"){echo "Reference";}else{echo "Reference";}?></td>
			<td class="EnTeteTableauCompetences" width="15%" ><?php if($_SESSION['Langue']=="EN"){echo "Tool Type";}else{echo "Tool Type";}?></td>
			<td class="EnTeteTableauCompetences" width="10%" ><?php if($_SESSION['Langue']=="EN"){echo "Next Calibration Date";}else{echo "Next Calibration Date";}?></td>
			<td class="EnTeteTableauCompetences" width="10%" ><?php if($_SESSION['Langue']=="EN"){echo "Date of Use";}else{echo "Date of Use";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "NC";}else{echo "NC";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "AM";}else{echo "AM";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "OF/OT";}else{echo "OF/OT";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "QLB";}else{echo "QLB";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "TLB";}else{echo "TLB";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "Concession";}else{echo "Concession";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "Para";}else{echo "Para";}?></td>
		</tr>
		<?php 
		
		if($reference<>""){
			$req="SELECT (SELECT MSN FROM gpao_aircraft WHERE Id=Id_Aircraft) AS MSN,
			gpao_cmte.Reference,
				gpao_cmte.ToolType,gpao_cmte.NextCalibrationDate,gpao_cmte.DateOfUse,
				NC,AM,OF,QLB,TLB,Concession,Para
				FROM gpao_cmte 
				LEFT JOIN gpao_wo
				ON gpao_cmte.Id_WO=gpao_wo.Id
				WHERE gpao_cmte.Suppr=0 
				AND gpao_wo.Suppr=0 
				AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
				AND Reference LIKE '%".$reference."%' ";

			$resultList=mysqli_query($bdd,$req);
			$nbList=mysqli_num_rows($resultList);

			if ($nbList > 0)
			{
				$couleur="#ffffff";
				while($rowList=mysqli_fetch_array($resultList)){
		?>
				<tr bgcolor="<?php echo $couleur;?>">
					<td><?php echo stripslashes($rowList['MSN']); ?></td>
					<td><?php echo stripslashes($rowList['Reference']); ?></td>
					<td><?php echo stripslashes($rowList['ToolType']); ?></td>
					<td><?php echo AfficheDateJJ_MM_AAAA($rowList['NextCalibrationDate']); ?></td>
					<td><?php echo AfficheDateJJ_MM_AAAA($rowList['DateOfUse']); ?></td>
					<td><?php echo stripslashes($rowList['NC']); ?></td>
					<td><?php echo stripslashes($rowList['AM']); ?></td>
					<td><?php echo stripslashes($rowList['OF']); ?></td>
					<td><?php echo stripslashes($rowList['QLB']); ?></td>
					<td><?php echo stripslashes($rowList['TLB']); ?></td>
					<td><?php echo stripslashes($rowList['Concession']); ?></td>
					<td><?php echo stripslashes($rowList['Para']); ?></td>
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
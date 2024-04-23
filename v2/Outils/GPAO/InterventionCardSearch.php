<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<script>
		function OuvreFenetreExcel(Lien,All){
			if(All==0){
				if(document.getElementById('nc').value!="" || document.getElementById('of').value!="" || document.getElementById('msn').value!=""){
					var w=window.open("Extract_"+Lien+".php?NC="+document.getElementById('nc').value+"&OF="+document.getElementById('of').value+"&MSN="+document.getElementById('msn').value,"PageE"+Lien,"status=no,menubar=no,scrollbars=yes,width=50,height=50");
					w.focus();
				}
			}
			else{
				var w=window.open("Extract_"+Lien+".php?NC=&OF=&MSN=","PageE"+Lien,"status=no,menubar=no,scrollbars=yes,width=50,height=50");
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
	$nc=$_POST['nc'];
	$of=$_POST['of'];
	$msn=$_POST['msn'];
}
else{
	$nc="";
	$of="";
	$msn="";
}

?>

<form id="formulaire" method="POST" action="InterventionCardSearch.php">
	<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
	<table width="95%" align="center" class="TableCompetences">
		<tr><td height="8"></td></tr>
		<tr>
			<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "NC";}else{echo "NC";} ?> :</td>
			<td width='10%'>
				<input type="texte" name="nc" id="nc" size="12" value="<?php echo $nc;?>">
			</td>
			<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "OF/OT";}else{echo "OF/OT";} ?> :</td>
			<td width='10%'>
				<input type="texte" name="of" id="of" size="12" value="<?php echo $of;?>">
			</td>
			<td class="Libelle" width='10%'><?php if($_SESSION['Langue']=="EN"){echo "MSN";}else{echo "MSN";} ?> :</td>
			<td width='10%'>
				<input type="texte" name="msn" id="msn" size="12" value="<?php echo $msn;?>">
			</td>
			<td align="center">
				<input class="Bouton" onclick="submit();" value="<?php if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}?>">
			</td>
			<td align="right">
					<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreExcel('InterventionCard',0)"><?php if($_SESSION['Langue']=="EN"){echo "Extract Selection";}else{echo "Extract Selection";}?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreExcel('InterventionCard',1)"><?php if($_SESSION['Langue']=="EN"){echo "Extract All Data";}else{echo "Extract All Data";}?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
	</table><br>
	<table width="99%" align="center" class="TableCompetences">
		<tr>
			<td class="EnTeteTableauCompetences" width="5%" ><?php if($_SESSION['Langue']=="EN"){echo "MSN";}else{echo "MSN";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "NC";}else{echo "NC";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "OF/OT";}else{echo "OF/OT";}?></td>
			<td class="EnTeteTableauCompetences" width="10%" ><?php if($_SESSION['Langue']=="EN"){echo "Intervention Card n°";}else{echo "Intervention Card n°";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "Start date";}else{echo "Start date";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "End Date";}else{echo "End Date";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "Closure Type";}else{echo "Closure Type";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "Intervention Name";}else{echo "Intervention Name";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "Intervention Signature Date";}else{echo "Intervention Signature Date";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "QI Stamp n°";}else{echo "QI Stamp n°";}?></td>
			<td class="EnTeteTableauCompetences" width="8%" ><?php if($_SESSION['Langue']=="EN"){echo "QI Closure Date";}else{echo "QI Closure Date";}?></td>
		</tr>
		<?php 
		
		if($nc<>"" || $of<>"" || $msn<>""){
			$req="SELECT (SELECT MSN FROM gpao_aircraft WHERE Id=Id_Aircraft) AS MSN,
				gpao_interventioncard.Numero,gpao_interventioncard.StartDate,
				gpao_interventioncard.EndDate,gpao_interventioncard.ClosureType,gpao_interventioncard.IntervenerName,
				gpao_interventioncard.IntervenerSignatureDate,gpao_interventioncard.QIStampNum,
				gpao_interventioncard.QIClosureDate,
				NC,OF
				FROM gpao_interventioncard 
				LEFT JOIN gpao_wo
				ON gpao_interventioncard.Id_WO=gpao_wo.Id
				WHERE gpao_interventioncard.Suppr=0 
				AND gpao_wo.Suppr=0 
				AND gpao_wo.Id_PrestationGPAO=".$_SESSION['Id_GPAO']." ";
			if($nc<>""){
				$req.="AND NC LIKE '%".$nc."%' ";
			}
			if($of<>""){
				$req.="AND OF LIKE '%".$of."%' ";
			}
			if($msn<>""){
				$req.="AND (SELECT MSN FROM gpao_aircraft WHERE Id=Id_Aircraft) LIKE '%".$msn."%' ";
			}

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
					<td><?php echo stripslashes($rowList['Numero']); ?></td>
					<td><?php echo AfficheDateJJ_MM_AAAA($rowList['StartDate']); ?></td>
					<td><?php echo AfficheDateJJ_MM_AAAA($rowList['EndDate']); ?></td>
					<td><?php echo stripslashes($rowList['ClosureType']); ?></td>
					<td><?php echo stripslashes($rowList['IntervenerName']); ?></td>
					<td><?php echo AfficheDateJJ_MM_AAAA($rowList['IntervenerSignatureDate']); ?></td>
					<td><?php echo stripslashes($rowList['QIStampNum']); ?></td>
					<td><?php echo AfficheDateJJ_MM_AAAA($rowList['QIClosureDate']); ?></td>
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
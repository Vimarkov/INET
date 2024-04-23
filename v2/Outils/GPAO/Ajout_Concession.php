<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<script>
		function FermerEtRecharger(){
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
	if(isset($_POST['Btn_Enregistrer'])){
		//Mise à jour des informations dans le WO concernant la Concession (DQ1)
		$req="UPDATE gpao_wo
			SET FOTDate='".TrsfDate_($_POST['FOTDate'])."',
			EoWDQ1='".TrsfDate_($_POST['EoWDQ1'])."',
			NewEoWDQ1='".TrsfDate_($_POST['NewEoWDQ1'])."',
			CreationDateDQ1='".TrsfDate_($_POST['CreationDateDQ1'])."',
			CommentsDQ1='".addslashes($_POST['CommentsDQ1'])."',
			FollowUpConcession=".$_POST['FollowUpConcession']."
			WHERE Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			AND Id=".$_SESSION['GPAO_IdWO']." ";
		$result=mysqli_query($bdd,$req);
	}

	echo "<script>FermerEtRecharger();</script>";
}
else
{
	$req="SELECT Id,FOTDate,EoWDQ1,NewEoWDQ1,CreationDateDQ1,CommentsDQ1,FollowUpConcession
		FROM gpao_wo
		WHERE Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
		AND Id=".$_SESSION['GPAO_IdWO']." ";

	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	if ($nbResulta>0){
	$row=mysqli_fetch_array($result);
?>
<form id="formulaire" method="POST" action="Ajout_Concession.php">
<input type="hidden" name="btn" id="btn" value="" />
<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
<table class="TableCompetences" width="99%" cellpadding="0" cellspacing="0">
	<tr>
		<td class="Libelle" width='20%'><?php if($_SESSION['Langue']=="EN"){echo "FOT Date";}else{echo "FOT Date";} ?> :</td>
		<td width='30%'>
			<input type="date" id="FOTDate" name="FOTDate" size="20" value="<?php echo AfficheDateFR($row['FOTDate']);?>">
		</td>
		<td class="Libelle" width='20%'><?php if($_SESSION['Langue']=="EN"){echo "Creation Date DQ1";}else{echo "Creation Date DQ1";} ?> :</td>
		<td width='30%'>
			<input type="date" id="CreationDateDQ1" name="CreationDateDQ1" size="20" value="<?php echo AfficheDateFR($row['CreationDateDQ1']);?>">
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td class="Libelle" width='20%'><?php if($_SESSION['Langue']=="EN"){echo "EoW DQ1";}else{echo "EoW DQ1";} ?> :</td>
		<td width='30%'>
			<input type="date" id="EoWDQ1" name="EoWDQ1" size="20" value="<?php echo AfficheDateFR($row['EoWDQ1']);?>">
		</td>
		<td class="Libelle" width='20%'><?php if($_SESSION['Langue']=="EN"){echo "New EoW DQ1";}else{echo "New EoW DQ1";} ?> :</td>
		<td width='30%'>
			<input type="date" id="NewEoWDQ1" name="NewEoWDQ1" size="20" value="<?php echo AfficheDateFR($row['NewEoWDQ1']);?>">
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td class="Libelle" width='5%'><?php if($_SESSION['Langue']=="EN"){echo "Follow Up Concession";}else{echo "Follow Up Concession";} ?> :</td>
		<td width='7%'>
			<select class="FollowUpConcession" name="FollowUpConcession" style="width:50px;">
			<?php
				$tab=array(array(1,"Yes"),array(0,"No"));

				foreach($tab as $valeur)
				{
					$selected="";
					if($row['FollowUpConcession']==$valeur[0]){$selected="selected";}
					echo "<option value='".$valeur[0]."' ".$selected.">".$valeur[1]."</option>\n";
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td class="Libelle" colspan="4" valign="top"><?php if($_SESSION['Langue']=="EN"){echo "Comments DQ1";}else{echo "Comments DQ1";} ?> :</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td colspan="4">
			<textarea id="CommentsDQ1" name="CommentsDQ1" rows="3" cols="70" style="resize:none;"><?php echo stripslashes($row['CommentsDQ1']);?></textarea>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td colspan="6" align="center">
			<input class="Bouton" type="submit" style="font-size:15px;" name="Btn_Enregistrer"  value="<?php if($_SESSION['Langue']=="EN"){echo "Save";}else{echo "Save";}?>">
		</td>
	</tr>
</table>	
</form>
<?php
	}
}
?>
	
</body>
</html>
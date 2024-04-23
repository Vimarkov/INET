<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<script>
		List_Category = new Array();
		function VerifRemplissage(nbLigne){
			//Vérifier le remplissage des lignes
			//Si quelque chose est renseigné sur la ligne alors il faut que Category et Quantity soit complété au minimum
			for(i=0;i<nbLigne;i++){
				if(document.getElementById('id_WOCategoryList_'+i).value!="0" || document.getElementById('quantity_'+i).value!=""){
					if(document.getElementById('id_WOCategoryList_'+i).value=="0" || document.getElementById('quantity_'+i).value==""){
						if(document.getElementById('Langue').value=="FR"){
							alert('Les champs "Category" et "Quantity" doivent être complétés pour pouvoir enregistrer');return false;
						}
						else{
							alert('The "Category", "Worker" and "Quantity" fields must be completed in order to register');return false;
						}
					}
				}
			}
			return true;
		}
		function ChangerValeurTarget(ligne,nbLigne){
			document.getElementById('timeUsed_'+ligne).value="";
			if(document.getElementById('id_WOCategoryList_'+ligne).value!="0"){
				for(i=0;i<List_Category.length;i++){
					if (List_Category[i][0]==document.getElementById('id_WOCategoryList_'+ligne).value){
						quantity=0;
						if(document.getElementById('quantity_'+ligne).value!=""){
							quantity=Number(document.getElementById('quantity_'+ligne).value);
						}
						document.getElementById('timeUsed_'+ligne).value=quantity*Number(List_Category[i][1]);
					}
				}
			}

			//Calcul de la somme des time used 
			CalculSommeTimeUsed(nbLigne);
		}
		
		function CalculSommeTimeUsed(nbLigne){
			somme=0;
			//Calcul de la somme des time used 
			for(i=0;i<nbLigne;i++){
				if(document.getElementById('timeUsed_'+i).value>0){
					somme+=Number(document.getElementById('timeUsed_'+i).value);
				}
			}
			document.getElementById('timeUsedTotal').value=somme;
		}
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
		//Supprimer les anciens
		$req="UPDATE gpao_wocategory 
			SET Suppr=1, DateSuppr='".date('Y-m-d')."', Id_Suppr=".$_SESSION['Id_Personne']." 
			WHERE Suppr=0 
			AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			AND Id_WO=".$_SESSION['GPAO_IdWO']." ";
		$result=mysqli_query($bdd,$req);
		//Recréer les nouveau CMTE
		for($i=0;$i<$_POST['nbLigne'];$i++){
			if($_POST['id_WOCategoryList_'.$i]<>"0" && $_POST['quantity_'.$i]<>""){
				$req="INSERT INTO gpao_wocategory (Id_PrestationGPAO,Id_WO,Id_WOCategoryList,Id_UserName,DateValidation,Quantity) 
				VALUES (".$_SESSION['Id_GPAO'].",".$_SESSION['GPAO_IdWO'].",'".$_POST['id_WOCategoryList_'.$i]."',
				'".$_POST['id_UserName_'.$i]."','".$_POST['dateValidation_'.$i]."',
				'".$_POST['quantity_'.$i]."') ";
				$result=mysqli_query($bdd,$req);
			}
		}
	}

	echo "<script>FermerEtRecharger();</script>";
}
else
{
	$req="SELECT Id,Id_WOCategoryList,Id_UserName,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_UserName) AS UserName,
		DateValidation,Quantity,
		(SELECT TimeUsed FROM gpao_wocategorylist WHERE Id=Id_WOCategoryList) AS TimeUsed
		FROM gpao_wocategory
		WHERE Suppr=0 
		AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
		AND Id_WO=".$_SESSION['GPAO_IdWO']." ";

	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	
	$total=5;
	if ($nbResulta>=5){
		$total=5+$nbResulta;
	}
?>
<form id="formulaire" method="POST" action="Ajout_WOCategory.php" onSubmit="return VerifRemplissage(<?php echo $total; ?>);">
<input type="hidden" name="btn" id="btn" value="" />
<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
<table class="TableCompetences" width="99%" cellpadding="0" cellspacing="0">
	<tr>
		<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION['Langue']=="EN"){echo "Category";}else{echo "Category";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Quantity";}else{echo "Quantity";} ?></td>
		<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Target (h)";}else{echo "Target (h)";} ?></td>
		<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION['Langue']=="EN"){echo "User Name";}else{echo "User Name";} ?></td>
		<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION['Langue']=="EN"){echo "Date of validation";}else{echo "Date of validation";} ?></td>
	</tr>
<?php 
	$SommeTimeUsed=0;
	for($i=0;$i<$total;$i++){
		$Id_WOCategoryList="";
		$UserName="";
		$DateValidationVisu="";
		$DateValidation=date('Y-m-d H:i:s');
		$Quantity="";
		$TimeUsed="";
		$Id_UserName=$_SESSION['Id_Personne'];

		if ($i<$nbResulta){
			$row=mysqli_fetch_array($result);
			$Id_WOCategoryList=$row['Id_WOCategoryList'];
			$UserName=$row['UserName'];
			$Id_UserName=$row['Id_UserName'];
			$DateValidationVisu=$row['DateValidation'];
			$DateValidation=$row['DateValidation'];
			$Quantity=$row['Quantity'];
		}
?>
		<tr>
			<td>
				<select id="id_WOCategoryList_<?php echo $i;?>" name="id_WOCategoryList_<?php echo $i;?>" onchange="ChangerValeurTarget(<?php echo $i;?>,<?php echo $total; ?>)" style="width:150px;">
				<?php
					echo"<option name='0' value='0'></option>";
					$req="SELECT Id, Libelle, TimeUsed
						FROM gpao_wocategorylist 
						WHERE Suppr=0 
						AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
						ORDER BY Libelle";
					$result2=mysqli_query($bdd,$req);
					$nbResulta2=mysqli_num_rows($result2);
					if ($nbResulta2>0){
						$k=0;
						while($row2=mysqli_fetch_array($result2)){
							$selected="";
							if($row2['Id']==$Id_WOCategoryList){
								$selected="selected";
								$TimeUsed=$row2['TimeUsed'];
								$SommeTimeUsed+=$row2['TimeUsed'];
							}
							echo "<option value='".$row2['Id']."' ".$selected.">".stripslashes($row2['Libelle'])."</option>";
							echo "<script>List_Category[".$k."] = new Array('".$row2['Id']."','".$row2['TimeUsed']."');</script>";
							$k++;
						}
					}
				?>
				</select>
				
			</td>
			<td>
				<input type="texte" onKeyUp="nombre(this)" id="quantity_<?php echo $i;?>" name="quantity_<?php echo $i;?>" onchange="ChangerValeurTarget(<?php echo $i;?>,<?php echo $total; ?>)" size="8" value="<?php echo $Quantity;?>" >
			</td>
			<td>
				<input type="texte" readonly='readonly' id="timeUsed_<?php echo $i;?>" name="timeUsed_<?php echo $i;?>" size="5" value="<?php echo $TimeUsed;?>" >
			</td>
			<td class="Libelle">
				<?php echo $UserName; ?>
				<input type="hidden" id="id_UserName_<?php echo $i;?>" name="id_UserName_<?php echo $i;?>" size="20" value="<?php echo $Id_UserName;?>">
			</td>
			<td>
				<?php echo $DateValidationVisu; ?>
				<input type="hidden" id="dateValidation_<?php echo $i;?>" name="dateValidation_<?php echo $i;?>" size="20" value="<?php echo $DateValidation;?>">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
<?php
	}
?>
		<tr>
			<td>
			</td>
			<td>
			</td>
			<td>
				<input type="texte" readonly='readonly' id="timeUsedTotal" name="timeUsedTotal" size="5" value="<?php echo $SommeTimeUsed;?>" >
			</td>
			<td>
			</td>
			<td>
			</td>
		</tr>
	<tr>
		<td colspan="6" align="center">
			<input class="Bouton" type="submit" style="font-size:15px;" name="Btn_Enregistrer"  value="<?php if($_SESSION['Langue']=="EN"){echo "Save";}else{echo "Save";}?>">
		</td>
	</tr>
	<input type="hidden" name="nbLigne" id="nbLigne" value="<?php echo $total; ?>" />
</table>	
</form>
<?php
	echo "<script>CalculSommeTimeUsed(".$total.")</script>";
}
?>
	
</body>
</html>
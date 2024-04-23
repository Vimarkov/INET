<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<script>
		function SelectionnerTout()
		{
			var elements = document.getElementsByClassName("check");
			if (document.getElementById('selectAll').checked == true)
			{
				for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
			}
			else
			{
				for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
			}
		}
		function FermerEtRecharger(){
			opener.location="TableauDeBord.php?Menu=1";
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
	$display=$_POST['Display'];
	$valeur=$_POST['Valeur'];
}
else{
	$display=$_GET['Display'];
	$valeur=$_GET['Valeur'];
}

if($_POST){
	if(isset($_POST['Btn_Enregistrer'])){
		$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur]="";
		
		if($valeur=='Customer' || $valeur=='Imputation' || $valeur=='MSN' || $valeur=='Type' || $valeur=='WorkingShift' || $valeur=='Priority' || $valeur=='LastStatus'){
			if($valeur=='Customer'){
				$req="SELECT Id,Libelle
				FROM gpao_customer
				WHERE Suppr=0 
				AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
				ORDER BY Libelle";
			}
			elseif($valeur=='Imputation'){
				$req="SELECT Id,Libelle
				FROM gpao_imputation
				WHERE Suppr=0 
				AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
				ORDER BY Libelle";
			}
			elseif($valeur=='MSN'){
				$req="SELECT Id,MSN AS Libelle
				FROM gpao_aircraft
				WHERE Suppr=0 
				AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
				ORDER BY Libelle";
			}
			elseif($valeur=='Type'){
				$req="SELECT Id,Libelle
				FROM gpao_aircrafttype
				WHERE Suppr=0 
				AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
				ORDER BY Libelle";
			}
			elseif($valeur=='WorkingShift'){
				$req="SELECT Id,Libelle
				FROM gpao_workingshifts
				WHERE Suppr=0 
				AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
				ORDER BY Libelle";
			}
			elseif($valeur=='Priority'){
				$req="SELECT Id,Libelle
				FROM gpao_priority
				WHERE Suppr=0 
				AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
				ORDER BY Libelle";
			}
			elseif($valeur=='LastStatus'){
				$req="SELECT Id,Libelle
				FROM gpao_statutlist
				WHERE Suppr=0 
				AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
				ORDER BY Libelle";
			}
			$resultList=mysqli_query($bdd,$req);
			$nbList=mysqli_num_rows($resultList);
			
			if ($nbList > 0)
			{
				while($rowList=mysqli_fetch_array($resultList))
				{
					if(isset($_POST['valeur_'.$rowList['Id']])){
						$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur]=$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur].$rowList['Id'].";";
					}
				}
			 }
		}
		elseif($valeur=='Position' || $valeur=='Skills' || $valeur=='OTDComment'){	
			if($valeur=='Position'){$tabListe=array("FL","L1","L2","L3","L4","WA","WB","WP");}
			elseif($valeur=='Skills'){$tabListe=array("ELECTRIC","MECHANIC","SEALER","STRUCTURE");}
			elseif($valeur=='OTDComment'){$tabListe=array("Missing documentation/assessment","Missing tools","No access_IC not signed","No Access_Intervention by third parties","No manpower","No qualification/competencies","Rework - Coordination;Rework - Production","Rework - Quality;Rework - Third parties","RRA Process","Waiting for dress Out Airbus;Waiting for MAP","Waiting for NDT;Waiting for paintshop","Waiting for Para to be stamped","Waiting for parts/fasteners/chemical products","Waiting for sandblasting","Work done in time - Concession INP");}
			
			$i=0;
			foreach($tabListe as $valeurListe)
			{
				if(isset($_POST["valeur_".$i])){
					$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur]=$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur].$valeurListe.";";
				}
				$i++;
			}
		}
		elseif($valeur=='EscalationPoint' || $valeur=='OTDEoW' || $valeur=='FollowUpConcession'){
			$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur.'_2']="";
			$tabListe=array(array(1,"Yes"),array(0,"No"));
			
			foreach($tabListe as $valeurListe)
			{
				if(isset($_POST['valeur_'.$valeurListe[0]])){
					$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur.'_2']=$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur.'_2'].$valeurListe[0].";";
					$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur]=$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur].$valeurListe[1].";";
				}
			}
		}
		elseif($valeur=='AM' || $valeur=='OF' || $valeur=='NC' || $valeur=='QLB' || $valeur=='TLB' || $valeur=='Concession' || $valeur=='Para' || $valeur=='Designation'
		|| $valeur=='FI' || $valeur=='Comments' || $valeur=='CommentsDQ1' || $valeur=='StatusComment' || $valeur=='CommentsA_CMS2' || $valeur=='PriorityReason'
		|| $valeur=='PartNumber' || $valeur=='CMS' || $valeur=='RefDIV'){
			for($i=0;$i<10;$i++){
				if($_POST['valeur_'.$i]<>""){
					$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur]=$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur].$_POST['valeur_'.$i].";";
				}
			}
		}
		elseif($valeur=='PlanDate' || $valeur=='LimitDateFOT' || $valeur=='CreationDate' || $valeur=='ClosureDate' || $valeur=='NewEoW' || $valeur=='LastEoW'
		 || $valeur=='UpdateDateTandem' || $valeur=='FOTDate' || $valeur=='EoWDQ1' || $valeur=='NewEoWDQ1' || $valeur=='CreationDateDQ1' 
		 || $valeur=='NewEoWAvailable' || $valeur=='LastStatusDate' || $valeur=='PartsDeliveryDate'
		 || $valeur=='PartsReceivedOn'){
			 $_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur.'_Du']=$_POST['valeur_Du'];
			 $_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur.'_Au']=$_POST['valeur_Au'];
		}
		elseif($valeur=='WorkingProgress' || $valeur=='TargetTime' || $valeur=='Quantity'){
			 $_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur]=$_POST['valeur'];
			 $_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur.'_Type']=$_POST['type'];
		}
	}

	echo "<script>FermerEtRecharger();</script>";
}
else
{

?>
<form id="formulaire" method="POST" action="Ajout_Filtre.php">
<input type="hidden" name="Display" id="Display" value="<?php echo $display; ?>" />
<input type="hidden" name="Valeur" id="Valeur" value="<?php echo $valeur; ?>" />
<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
<table class="TableCompetences" width="99%" cellpadding="0" cellspacing="0">
	<tr>
		<td class="Libelle">
<?php 
	$req="SELECT Titre, Valeur,Type
		FROM gpao_tableau 
		WHERE Id_PrestationGPAO=".$_SESSION['Id_GPAO']."
		AND Display='".$display."' 
		AND Valeur='".$valeur."' 
		AND Suppr=0 ";
	$resultTitreDisplay=mysqli_query($bdd,$req);
	$nbTitreDisplay=mysqli_num_rows($resultTitreDisplay);
	
	if($nbTitreDisplay>0){
		$rowDisplay=mysqli_fetch_array($resultTitreDisplay);
		echo $rowDisplay['Titre']." : ";
		
?>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
<?php 
	$tab=explode(";",$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur]);
	if($valeur=='Customer' || $valeur=='Imputation' || $valeur=='MSN' || $valeur=='Type' || $valeur=='WorkingShift' || $valeur=='Priority' || $valeur=='LastStatus'){
		echo "<tr>
				<td>
				<div id='Div_Division' style='height:200px;width:300px;overflow:auto;'>
				<table>
				<tr>
					<td>
						<input type='checkbox' name='selectAll' id='selectAll' onclick='SelectionnerTout()' />";
						if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";}
		echo "      </td>
				</tr>
				";

		if($valeur=='Customer'){
			$req="SELECT Id,Libelle
			FROM gpao_customer
			WHERE Suppr=0 
			AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			ORDER BY Libelle";
		}
		elseif($valeur=='Imputation'){
			$req="SELECT Id,Libelle
			FROM gpao_imputation
			WHERE Suppr=0 
			AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			ORDER BY Libelle";
		}
		elseif($valeur=='MSN'){
			$req="SELECT Id,MSN AS Libelle
			FROM gpao_aircraft
			WHERE Suppr=0 
			AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			ORDER BY Libelle";
		}
		elseif($valeur=='Type'){
			$req="SELECT Id,Libelle
			FROM gpao_aircrafttype
			WHERE Suppr=0 
			AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			ORDER BY Libelle";
		}
		elseif($valeur=='WorkingShift'){
			$req="SELECT Id,Libelle
			FROM gpao_workingshifts
			WHERE Suppr=0 
			AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			ORDER BY Libelle";
		}
		elseif($valeur=='Priority'){
			$req="SELECT Id,Libelle
			FROM gpao_priority
			WHERE Suppr=0 
			AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			ORDER BY Libelle";
		}
		elseif($valeur=='LastStatus'){
			$req="SELECT Id,Libelle
			FROM gpao_statutlist
			WHERE Suppr=0 
			AND Id_PrestationGPAO=".$_SESSION['Id_GPAO']." 
			ORDER BY Libelle";
		}
		$resultList=mysqli_query($bdd,$req);
		$nbList=mysqli_num_rows($resultList);
		
		if ($nbList > 0)
		{
			while($rowList=mysqli_fetch_array($resultList))
			{
				$selected="";
				foreach($tab as $filtre){
					if($filtre==$rowList['Id']){$selected="checked";}
				}
				echo "<tr><td><input type='checkbox' class='check' name='valeur_".$rowList['Id']."' ".$selected." />".$rowList['Libelle']."</td></tr>";
			}
		 }
		echo "</table></div></td></tr>";
	}
	elseif($valeur=='Position' || $valeur=='Skills' || $valeur=='OTDComment'){
		echo "<tr>
					<td>
						<input type='checkbox' name='selectAll' id='selectAll' onclick='SelectionnerTout()' />";
						if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";}
		echo "      </td>
				</tr>
				";
		
		if($valeur=='Position'){$tabListe=array("FL","L1","L2","L3","L4","WA","WB","WP");}
		elseif($valeur=='Skills'){$tabListe=array("ELECTRIC","MECHANIC","SEALER","STRUCTURE");}
		elseif($valeur=='OTDComment'){$tabListe=array("Missing documentation/assessment","Missing tools","No access_IC not signed","No Access_Intervention by third parties","No manpower","No qualification/competencies","Rework - Coordination;Rework - Production","Rework - Quality;Rework - Third parties","RRA Process","Waiting for dress Out Airbus;Waiting for MAP","Waiting for NDT;Waiting for paintshop","Waiting for Para to be stamped","Waiting for parts/fasteners/chemical products","Waiting for sandblasting","Work done in time - Concession INP");}
		
		$i=0;
		foreach($tabListe as $valeurListe)
		{
			$selected="";
			foreach($tab as $filtre){
				if($filtre==$valeurListe){$selected="checked";}
			}
			echo "<tr><td><input type='checkbox' class='check' name=\"valeur_".$i."\" ".$selected." />".$valeurListe."</td></tr>";
			$i++;
		}
	}
	elseif($valeur=='AM' || $valeur=='OF' || $valeur=='NC' || $valeur=='QLB' || $valeur=='TLB' || $valeur=='Concession' || $valeur=='Para' || $valeur=='Designation'
		|| $valeur=='FI' || $valeur=='Comments' || $valeur=='CommentsDQ1' || $valeur=='StatusComment' || $valeur=='CommentsA_CMS2'
		|| $valeur=='PriorityReason' || $valeur=='PartNumber' || $valeur=='CMS' || $valeur=='RefDIV'){
		$nb=0;
		$tabFiltre=explode(";",$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur]);
		foreach($tabFiltre as $filtre){
		?>
			<tr><td><input type="texte" size="50" name="valeur_<?php echo $nb;?>" value="<?php echo $filtre;?>" /></td></tr>
		<?php
			echo "<tr><td height='4'></td></tr>";
			$nb++;
		}
		for($i=$nb;$i<10;$i++){
			echo "<tr><td><input type='texte' size='50' name='valeur_".$i."' value='' /></td></tr>";
			echo "<tr><td height='4'></td></tr>";
		}
	}
	elseif($valeur=='EscalationPoint' || $valeur=='OTDEoW' || $valeur=='FollowUpConcession'){
		$tab=explode(";",$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur.'_2']);
		$tabListe=array(array(1,"Yes"),array(0,"No"));
		
		foreach($tabListe as $valeurListe)
		{
			$selected="";
			foreach($tab as $filtre){
				if($filtre==$valeurListe[0]){$selected="checked";}
			}
			echo "<tr><td><input type='checkbox' class='check' name='valeur_".$valeurListe[0]."' ".$selected." />".$valeurListe[1]."</td></tr>";
		}
	}
	elseif($valeur=='PlanDate' || $valeur=='LimitDateFOT' || $valeur=='CreationDate' || $valeur=='ClosureDate' || $valeur=='NewEoW' || $valeur=='LastEoW'
	 || $valeur=='UpdateDateTandem' || $valeur=='FOTDate' || $valeur=='EoWDQ1' || $valeur=='NewEoWDQ1' || $valeur=='CreationDateDQ1' 
	 || $valeur=='NewEoWAvailable' || $valeur=='LastStatusDate' || $valeur=='PartsDeliveryDate'
	 || $valeur=='PartsReceivedOn'){
		 $du=$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur.'_Du'];
		 $au=$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur.'_Au'];
	?>
		<tr><td>
			<?php if($_SESSION['Langue']=="EN"){echo "From ";}else{echo "Du ";} ?>
			<input type="date" size="50" name="valeur_Du" value="<?php echo $du;?>" />
			<?php if($_SESSION['Langue']=="EN"){echo " to ";}else{echo " au ";} ?>
			<input type="date" size="50" name="valeur_Au" value="<?php echo $au;?>" />
		</td></tr>
	<?php
	}
	elseif($valeur=='WorkingProgress' || $valeur=='TargetTime' || $valeur=='Quantity'){
	 	 $valeurChamps=$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur];
		 $type=$_SESSION['FiltreGPAO'.$_SESSION['Id_GPAO'].'_'.$display.'_'.$valeur.'_Type'];
	?>
		<tr><td>
			<select name="type" >
				<option value='=' <?php if($type=="="){echo "selected";} ?>>=</option>
				<option value='<' <?php if($type=="<"){echo "selected";} ?>><</option>
				<option value='>' <?php if($type==">"){echo "selected";} ?>>></option>
			</select>
			&nbsp;&nbsp;
			<input type="texte" onKeyUp="nombre(this)" size="8" name="valeur" value="<?php echo $valeurChamps;?>" />
		</td></tr>
	<?php
	 }
?>
	<tr><td height="4"></td></tr>
	<tr>
		<td>
			<input class="Bouton" type="submit" style="font-size:15px;" name="Btn_Enregistrer"  value="<?php if($_SESSION['Langue']=="EN"){echo "Save";}else{echo "Save";}?>">
		</td>
	</tr>
	<input type="hidden" name="nbLigne" id="nbLigne" value="<?php echo $total; ?>" />
<?php 
	}
?>
</table>	
</form>
<?php
}
?>
</body>
</html>
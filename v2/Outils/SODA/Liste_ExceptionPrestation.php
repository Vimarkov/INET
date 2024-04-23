<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");
?>

<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">
		function annuler(id)
		{
			var w=window.open("Annuler_ExceptionPrestation.php?Id="+id, "PageQuestion", "width=1200,height=700");
			w.focus();
		}
	</script>
</head>
<body>

<?php
if($_POST){
	$Id=$_POST['Id'];
}
else{
	$Id=$_GET['Id'];
}
$req = "SELECT Id,
		(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme, 
		(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
		DateCreation,
		(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Creation) AS Personne
		FROM soda_question_exceptionprestation
		WHERE Suppr=0 
		AND Id_Question=".$Id." 
		ORDER BY Plateforme,Prestation
		";
		
$result=mysqli_query($bdd,$req);
$nbQA=mysqli_num_rows($result);

?>
<form id="formulaire" method="POST" action="Liste_ExceptionPrestation.php">
<input type="hidden" name="Id" value="<?php echo $_GET['Id']; ?>">
<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>">
<table style="width:95%; height:95%; align:center;">
	<tr><td height="10"></td></tr>
	<tr>
		<td>
			<table class="TableCompetences" style="width:100%; align:center;background-color:#bac8ff;">
				<tr>
					<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Entité";}else{echo "Entity";}?></td>
					<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Activity";}?></td>
					<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Valideur";}else{echo "Validator";}?></td>
					<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Date";}else{echo "Date";}?></td>
					<td width="10%" class="Libelle"><?php if($_SESSION['Langue']=="FR"){echo "Annuler";}else{echo "Cancel";}?></td>
				</tr>
	<?php 
	if($nbQA){
		$Couleur="#EEEEEE";
		while($rowQA=mysqli_fetch_array($result)){
			$presta=substr($rowQA['Prestation'],0,strpos($rowQA['Prestation']," "));
			if($presta==""){$presta=$rowQA['Prestation'];}
	?>
		<tr bgcolor="<?php echo $Couleur;?>">
			<td><?php echo stripslashes($rowQA['Plateforme']);?></td>
			<td><?php echo $presta;?></td>
			<td><?php echo stripslashes($rowQA['Personne']);?></td>
			<td><?php echo AfficheDateJJ_MM_AAAA($rowQA['DateCreation']);?></td>
			<td><a href="javascript:annuler(<?php echo $rowQA['Id'];?>)"><img width="20px" src="../../Images/Refuser.gif"></a></td>
		</tr>
	<?php 
			if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
			else{$Couleur="#EEEEEE";}
		}
	}
	?>
			</table>
		</td>
	</tr>
</table>
</form>
</body>
</html>
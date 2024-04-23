<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");
?>

<html>
<head>
	<title>Extranet</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
	</script>
</head>
<body>

<?php
if($_POST)
{	
	if($_POST['qualification']<>'0'){
		$requete="INSERT INTO new_competences_relation 
			(Id_Personne, Type, Id_Qualification_Parrainage, Evaluation) VALUES 
			(".$_POST['Id'].",'Qualification',".$_POST['qualification'].",'L')";
		$resultInsertUpdate=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	$result=mysqli_query($bdd,"SELECT Id, CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE Id=".$_GET['Id']." ");
	$row=mysqli_fetch_array($result);
?>
	<form id="formulaire" method="POST" action="Ajout_QualifSurv.php">
		<input type="hidden" name="Id" value="<?php echo $row['Id'];?>">
		<table class="TableCompetences" style="width:95%; height:95%; align:center;">
			<tr>
				<td colspan="3" class="Libelle"><?php echo stripslashes($row['Personne']);?></td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Qualification";}else{echo "Qualification";} ?></td>
				<td>
					<select id="qualification" name="qualification" style="width:250px;">
					<?php
						echo"<option name='0' value='0'></option>";
						$req="SELECT Id,Libelle
							FROM new_competences_qualification
							WHERE Id_Categorie_Qualification=151 
							AND new_competences_qualification.Id<>3777
							AND Id NOT IN 
								(SELECT Id_Qualification_Parrainage
								FROM new_competences_relation
								WHERE Evaluation IN ('L','X')
								AND Suppr=0
								AND Date_Debut<='".date('Y-m-d')."'
								AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01')
								AND Id_Personne=".$row['Id']."
								)
							ORDER BY Libelle";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowP=mysqli_fetch_array($result)){
								echo "<option value='".$rowP['Id']."' >".$rowP['Libelle']."</option>";
							}
						}
					?>
					</select>
				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td colspan="2" align="center">
					<input class="Bouton" type="submit" <?php if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}?> />
				</td>
			</tr>
		</table>
	</form>
<?php
}
?>
</body>
</html>
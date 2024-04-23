<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Ajouter un besoin en formation</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script type="text/javascript">
		function VerifChamps(){
			return true;
		}
			
		function FermerEtRecharger(infos){
			window.opener.location="Planning_v2.php?"+infos;
			window.close();
		}
	</script>
</head>
<body>

<?php
if($_POST){
	if(isset($_POST['ajouter'])){
		if($_POST['formateur']<>"0" && $_POST['dateDebut']<>"" && $_POST['dateFin']<>"" ){
			$dateDebut=TrsfDate_($_POST['dateDebut']);
			$dateFin=TrsfDate_($_POST['dateFin']);
			if($dateDebut<=$dateFin){
				for($dateIndispo=$dateDebut;$dateIndispo<=$dateFin;$dateIndispo=date("Y-m-d",strtotime($dateIndispo." +1 day"))){
					$req="INSERT INTO form_formateur_indispo (Id_Plateforme,Id_Personne,DateIndispo) VALUES (".$_POST['Id_Plateforme'].",".$_POST['formateur'].",'".$dateIndispo."') ";
					$Result=mysqli_query($bdd,$req);
				}
			}
			
		}
	}
	elseif(isset($_POST['supprimer'])){
		if($_POST['formateur']<>"0" && $_POST['dateDebut']<>"" && $_POST['dateFin']<>"" ){
			$dateDebut=TrsfDate_($_POST['dateDebut']);
			$dateFin=TrsfDate_($_POST['dateFin']);
			if($dateDebut<=$dateFin){
				for($dateIndispo=$dateDebut;$dateIndispo<=$dateFin;$dateIndispo=date("Y-m-d",strtotime($dateIndispo." +1 day"))){
					$req="UPDATE form_formateur_indispo SET Suppr=1 WHERE Id_Plateforme=".$_POST['Id_Plateforme']." AND Id_Personne=".$_POST['formateur']." AND DateIndispo='".$dateIndispo."' ";
					$Result=mysqli_query($bdd,$req);
				}
			}
			
		}
	}
	echo "<script>FermerEtRecharger();</script>";
}
?>
<form id="formulaire" method="POST" action="Ajout_Indisponibilite_Formateur.php" onSubmit="return VerifChamps();">
	<input type="hidden" name="Id_Plateforme" value="<?php echo $_GET['Id_Plateforme'];?>">
	<input type="hidden" name="getPlanning" id="getPlanning" value="<?php echo "Id_Plateforme=".$_GET['Id_Plateforme']."&DateDeDebut=".$_GET['date']."&formateur=".$_GET['formateur']."&lieu=".$_GET['lieu']."&horaires=".$_GET['horaires']."&formation=".$_GET['formation']."&typeAffichage=".$_GET['typeAffichage']."&etatAffichage=".$_GET['etatAffichage'];?>">
	<table class="TableCompetences" style="width:95%; align:center;">
		<tr class="TitreColsUsers">
			<td  class="Libelle"><?php if($LangueAffichage=="FR"){echo "Formateur";}else{echo "Former";}?> : </td>
			<td>
				<select name="formateur" id="formateur">
					<option value="0"></option>
					<?php
					$req="SELECT DISTINCT Id, CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil ";
					$req.="WHERE Id IN (SELECT Id_Personne FROM new_competences_personne_poste_plateforme WHERE Id_Poste=21 AND Id_Plateforme=".$_GET['Id_Plateforme'].") ORDER BY Personne ASC";
					$resultFormateur=mysqli_query($bdd,$req);
					while($rowFormateur=mysqli_fetch_array($resultFormateur))
					{
						echo "<option value='".$rowFormateur['Id']."'";
						echo ">".stripslashes($rowFormateur['Personne'])."</option>\n";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="Libelle" width="13%"><?php if($LangueAffichage=="FR"){echo "Date de début";}else{echo "Start date";}?> : </td>
			<td width="17%"><input type="date" name="dateDebut" id="dateDebut" size="10" value=""></td>
			<td class="Libelle" width="13%"><?php if($LangueAffichage=="FR"){echo "Date de fin";}else{echo "End date";}?> : </td>
			<td width="17%"><input type="date" name="dateFin" id="dateFin" size="10" value=""></td>
		</tr>
		<tr class="TitreColsUsers">
			<td colspan="4" align="center">
				<input class="Bouton" name="ajouter" type="submit" <?php if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}?>>
				<input class="Bouton" name="supprimer" type="submit" <?php if($LangueAffichage=="FR"){echo "value='Supprimer'";}else{echo "value='Delete'";}?>>
			</td>
		</tr>
	</table>
</form>
<?php
mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
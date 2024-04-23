<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Ajouter un client</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.Id_GroupeMetierSODA.value=='0'){alert('Vous n\'avez pas renseigné le groupe métier.');return false;}
				else{return true;}
			}
			else{
				if(formulaire.Id_GroupeMetierSODA.value=='0'){alert('You did not fill in the business group.');return false;}
				else{return true;}
			}
		}
			
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
	</script>
</head>
<body>

<?php
$SrcProblem="";
$Problem=0;
$FichierTransfert=0;

if($_POST)
{	
	$requeteInsertUpdate="UPDATE new_competences_metier SET ";
	$requeteInsertUpdate.="Id_GroupeMetierSODA=".$_POST['Id_GroupeMetierSODA']."";
	$requeteInsertUpdate.=" WHERE Id=".$_POST['Id'];
	$resultInsertUpdate=mysqli_query($bdd,$requeteInsertUpdate);
	//echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Modif=false;
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0')
		{
			$Modif=True;
			$result=mysqli_query($bdd,"SELECT Id, Libelle, Id_GroupeMetierSODA FROM new_competences_metier WHERE Id=".$_GET['Id']." AND Suppr=0");
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Metier.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $row['Id'];}?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
		<table class="TableCompetences" style="width:95%; height:95%; align:center;">
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td colspan="3" class="Libelle"><?php if($Modif){echo stripslashes($row['Libelle']);}?></td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Groupe métier";}else{echo "Business groups";} ?></td>
				<td>
					<select id="Id_GroupeMetierSODA" name="Id_GroupeMetierSODA">
					<?php
						echo"<option name='0' value='0'></option>";
						$req="SELECT Id, Libelle FROM soda_groupemetier WHERE Suppr=0 ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowP=mysqli_fetch_array($result)){
								$selected="";
								if($Modif){
									if($rowP['Id']==$row['Id_GroupeMetierSODA']){$selected="selected";}
								}
								echo "<option value='".$rowP['Id']."' ".$selected.">".$rowP['Libelle']."</option>";
							}
						}
					?>
					</select>
				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td colspan="2" align="center">
					<input class="Bouton" type="submit" 
					<?php
						if($Modif){if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}}
						else{if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}}
					?>
					/>
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
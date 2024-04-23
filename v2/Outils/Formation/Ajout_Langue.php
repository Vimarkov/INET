<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Ajouter une langue</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
				else{return true;}
			}
			else{
				if(formulaire.Libelle.value==''){alert('You did not fill in the label.');return false;}
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
if($_POST)
{
	$requete="";
	if($_POST['Mode']=="Ajout")
	{
		if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM form_langue WHERE Libelle='".addslashes($_POST['Libelle'])."'"))==0)
		{
			$requete="INSERT INTO form_langue (Libelle,Id_Personne_MAJ,Date_MAJ)
				VALUES ('".addslashes($_POST['Libelle'])."',".$IdPersonneConnectee.",'".date('Y-m-d')."')";
		}
	}
	elseif($_POST['Mode']=="Modif")
	{
		if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM form_langue WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Id!=".$_POST['Id']))==0)
		{
			$requete="UPDATE form_langue SET";
			$requete.=" Libelle='".addslashes($_POST['Libelle'])."',";
			$requete.=" Id_Personne_MAJ=".$IdPersonneConnectee.",";
			$requete.=" Date_MAJ='".date('Y-m-d')."'";
			$requete.=" WHERE Id=".$_POST['Id'];
		}
	}
	if($requete!="")
	{
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
	else{echo "<font class='Erreur'>Ce Libelle existe déjà.<br>Vous devez recommencer l'opération.</font>";}
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
			$result=mysqli_query($bdd,"SELECT Id, Libelle FROM form_langue WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Langue.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php echo $_GET['Id'];?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
		<table style="width:95%; height:95%; align:center; class:TableCompetences;">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td colspan="3"><input name="Libelle" size="30" type="text" value="<?php if($Modif){echo $row['Libelle'];}?>"></td>
				<td>
					<input class="Bouton" type="submit" 
					<?php
						if($Modif)
						{
							if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
						}
						else
						{
							if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
						}
					?>
					>
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"UPDATE form_langue SET Suppr=1, 
			Id_Personne_MAJ=".$IdPersonneConnectee.", 
			Date_MAJ='".date('Y-m-d')."'
			WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger();</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
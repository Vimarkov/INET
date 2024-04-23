<html>
<head>
	<title>Formations - Ajouter un client</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script type="text/javascript">
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.plateforme.value==''){alert('Vous n\'avez pas renseigné l\'unité d\'exploitation.');return false;}
			}
			else{
				if(formulaire.plateforme.value==''){alert('You did not fill in the operating unit.');return false;}
			}
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
			}
			else{
				if(formulaire.Libelle.value==''){alert('You did not fill in the label.');return false;}
			}
			return true;
		}
			
		function FermerEtRecharger(Menu)
		{
			window.opener.location="Liste_RaisonRefusAstreinte.php?Menu="+Menu;
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");

$SrcProblem="";
$Problem=0;
$FichierTransfert=0;

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
		$requeteInsertUpdate="INSERT INTO rh_raisonrefus (Id_Plateforme,Libelle,Type)";
		$requeteInsertUpdate.=" VALUES (";
		$requeteInsertUpdate.="".$_POST['plateforme'].",";
		$requeteInsertUpdate.="'".addslashes($_POST['Libelle'])."',";
		$requeteInsertUpdate.="'RapportAstreinte'";
		$requeteInsertUpdate.=")";
	}
	else
	{		
		$requeteInsertUpdate="UPDATE rh_raisonrefus SET";
		$requeteInsertUpdate.=" Id_Plateforme=".$_POST['plateforme'].", ";
		$requeteInsertUpdate.=" Libelle='".addslashes($_POST['Libelle'])."' ";
		$requeteInsertUpdate.=" WHERE Id=".$_POST['Id'];
	}
	echo $requeteInsertUpdate;
	$resultInsertUpdate=mysqli_query($bdd,$requeteInsertUpdate);
	echo "<script>FermerEtRecharger(".$_POST['Menu'].");</script>";
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
			$result=mysqli_query($bdd,"SELECT Id, Libelle, Id_Plateforme FROM rh_raisonrefus WHERE Id=".$_GET['Id']." AND Suppr=0");
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_RaisonRefusAstreinte.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $row['Id'];}?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
				<td colspan="3">
					<select name="plateforme" id="plateforme" onChange="submit();">
						<?php
						$Plateforme=-1;
						$resultPlateforme=mysqli_query($bdd,"SELECT DISTINCT Id_Plateforme AS Id, 
							(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle 
							FROM new_competences_personne_poste_plateforme 
							WHERE Id_Poste 
								IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.") 
							AND Id_Personne=".$_SESSION['Id_Personne']." ORDER BY Libelle");
						while($rowPlateforme=mysqli_fetch_array($resultPlateforme))
						{
							echo "<option value='".$rowPlateforme['Id']."'";
							if($_GET){
								if($Modif){if($rowPlateforme['Id']==$row['Id_Plateforme']){echo " selected";$Plateforme=$row['Id_Plateforme'];}}
								else{
									if($Plateforme==-1){
									$Plateforme=$rowPlateforme['Id'];
									}
								}
							}
							else{
								if($rowPlateforme['Id']==$_POST['Id_Plateforme']){echo " selected";$Plateforme=$_POST['Id_Plateforme'];}
							}
							echo ">".stripslashes($rowPlateforme['Libelle'])."</option>\n";
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td colspan="3"><input name="Libelle" size="50" type="text" value="<?php if($Modif){echo stripslashes($row['Libelle']);}?>"></td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input class="Bouton" type="submit" 
					<?php
						if($Modif){if($_SESSION["Langue"]=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}}
						else{if($_SESSION["Langue"]=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}}
					?>
					/>
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"UPDATE rh_raisonrefus SET Suppr=1,Id_Suppr=".$_SESSION['Id_Personne'].", DateSuppr='".date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger(".$_GET['Menu'].");</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
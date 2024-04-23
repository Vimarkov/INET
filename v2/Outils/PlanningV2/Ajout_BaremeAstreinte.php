<html>
<head>
	<title>Formations - Ajouter un client</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script type="text/javascript">
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.plateforme.value==''){alert('Vous n\'avez pas renseign� l\'unit� d\'exploitation.');return false;}
				else{return true;}
			}
			else{
				if(formulaire.plateforme.value==''){alert('You did not fill in the operating unit.');return false;}
				else{return true;}
			}
		}

		function FermerEtRecharger(Menu)
		{
			window.opener.location="Liste_BaremeAstreinte.php?Menu="+Menu;
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
		$requeteInsertUpdate="INSERT INTO rh_bareme_astreinte (Id_Plateforme,ForfaitWeekend , ForfaitSemaine , Samedi,Dimanche,JourFerie)";
		$requeteInsertUpdate.=" VALUES (";
		$requeteInsertUpdate.="".$_POST['plateforme'].",";
		$requeteInsertUpdate.="".$_POST['forfaitWeekend'].",";
		$requeteInsertUpdate.="".$_POST['forfaitSemaine'].",";
		$requeteInsertUpdate.="".$_POST['samedi'].",";
		$requeteInsertUpdate.="".$_POST['dimanche'].",";
		$requeteInsertUpdate.="".$_POST['jourFerie']."";
		$requeteInsertUpdate.=")";
	}
	else
	{		
		$requeteInsertUpdate="UPDATE rh_bareme_astreinte SET ";
		$requeteInsertUpdate.="Id_Plateforme=".$_POST['plateforme'].",";
		$requeteInsertUpdate.="ForfaitWeekend=".$_POST['forfaitWeekend'].",";
		$requeteInsertUpdate.="ForfaitSemaine=".$_POST['forfaitSemaine'].",";
		$requeteInsertUpdate.="Samedi=".$_POST['samedi'].",";
		$requeteInsertUpdate.="Dimanche=".$_POST['dimanche'].",";
		$requeteInsertUpdate.="JourFerie=".$_POST['jourFerie']."";
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
			$result=mysqli_query($bdd,"SELECT Id, Id_Plateforme,ForfaitWeekend , ForfaitSemaine , Samedi,Dimanche,JourFerie FROM rh_bareme_astreinte WHERE Id=".$_GET['Id']." AND Suppr=0");
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_BaremeAstreinte.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $row['Id'];}?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Unit� d'exploitation";}else{echo "Operating unit";}?> : </td>
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
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Forfait weekend";}else{echo "Weekend package";}?> : </td>
				<td colspan="3"><input onKeyUp="nombre(this)" name="forfaitWeekend" size="8" type="text" value="<?php if($Modif){echo stripslashes($row['ForfaitWeekend']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Forfait semaine";}else{echo "Weekly package";}?> : </td>
				<td colspan="3"><input onKeyUp="nombre(this)" name="forfaitSemaine" size="8" type="text" value="<?php if($Modif){echo stripslashes($row['ForfaitSemaine']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Samedi";}else{echo "Saturday";}?> : </td>
				<td colspan="3"><input onKeyUp="nombre(this)" name="samedi" size="8" type="text" value="<?php if($Modif){echo stripslashes($row['Samedi']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Dimanche";}else{echo "Sunday";}?> : </td>
				<td colspan="3"><input onKeyUp="nombre(this)" name="dimanche" size="8" type="text" value="<?php if($Modif){echo stripslashes($row['Dimanche']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Jour f�ri�";}else{echo "Public holiday";}?> : </td>
				<td colspan="3"><input onKeyUp="nombre(this)" name="jourFerie" size="8" type="text" value="<?php if($Modif){echo stripslashes($row['JourFerie']);}?>"></td>
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
		$result=mysqli_query($bdd,"UPDATE rh_bareme_astreinte SET Suppr=1,Id_Suppr=".$_SESSION['Id_Personne'].", DateSuppr='".date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger(".$_POST['Menu'].");</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Lib�ration des r�sultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
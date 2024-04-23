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
			window.opener.location="Liste_JourFixe.php?Menu="+Menu;
			window.close();
		}
		Liste_Presta = new Array();
		function FiltrerPrestation(){
			var bTrouve = false;
			var selPresta="<select name='prestation' id='prestation' style='width:150px'>";
			selPresta= selPresta + "<option value='0'></option>";
			
			for(i=0;i<Liste_Presta.length;i++){
				if (Liste_Presta[i][2]==document.getElementById('plateforme').value || document.getElementById('plateforme').value==0){
					selPresta= selPresta + "<option value='"+Liste_Presta[i][0]+"_"+document.getElementById('plateforme').value+"'";
					selectedPresta="";
					if(document.getElementById('prestation').value==Liste_Presta[i][0]+"_"+document.getElementById('plateforme').value){selectedPresta= "selected";}
					selPresta= selPresta +selectedPresta+" >"+Liste_Presta[i][1]+"</option>";
					bTrouve=true;
				}
			}
			selPresta =selPresta + "</select>";
			document.getElementById('prestation').innerHTML=selPresta;
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("../Formation/Globales_Fonctions.php");

Ecrire_Code_JS_Init_Date();

if($_POST)
{
	$Id_Prestation=0;
	if($_POST['prestation']<>"0"){
		$arrayPresta=explode("_",$_POST['prestation']);
		$Id_Prestation=$arrayPresta[0];
	}
	
	if($_POST['Mode']=="Ajout")
	{
		$requeteInsertUpdate="INSERT INTO rh_jourfixe (Id_Plateforme,Id_Prestation,DateJour,Id_TypeAbsence)";
		$requeteInsertUpdate.=" VALUES (";
		$requeteInsertUpdate.="".$_POST['plateforme'].",";
		$requeteInsertUpdate.="".$Id_Prestation.",";
		$requeteInsertUpdate.="'".TrsfDate_($_POST['dateJour'])."',";
		$requeteInsertUpdate.="".$_POST['typeAbsence']."";
		$requeteInsertUpdate.=")";
	}
	else
	{		
		$requeteInsertUpdate="UPDATE rh_jourfixe SET";
		$requeteInsertUpdate.=" Id_Plateforme=".$_POST['plateforme'].", ";
		$requeteInsertUpdate.=" Id_Prestation=".$Id_Prestation.", ";
		$requeteInsertUpdate.=" DateJour='".TrsfDate_($_POST['dateJour'])."', ";
		$requeteInsertUpdate.=" Id_TypeAbsence=".$_POST['typeAbsence']." ";
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
			$result=mysqli_query($bdd,"SELECT Id, DateJour, Id_Plateforme, Id_Prestation, Id_TypeAbsence FROM rh_jourfixe WHERE Id=".$_GET['Id']." AND Suppr=0");
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_JourFixe.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $row['Id'];}?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
				<td colspan="3">
					<select name="plateforme" id="plateforme" onchange="FiltrerPrestation();">
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
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation : ";}else{echo "Site : ";} ?></td>
				<td>
					<select name="prestation" id="prestation" style="width:150px">
					<option value="0"></option>
					<?php
					$rq="SELECT DISTINCT Id, LEFT(Libelle,7) AS Libelle,Id_Plateforme
						FROM new_competences_prestation
						WHERE Active=0
						ORDER BY Libelle";

					$result=mysqli_query($bdd,$rq);
					$i=0;
					while($rowsite=mysqli_fetch_array($result))
					{
						echo "<option class='presta' value='".$rowsite['Id']."_".$rowsite['Id_Plateforme']."' ";
						if($row['Id_Prestation']==$rowsite['Id']){echo "selected";}
						echo ">";
								echo str_replace("'"," ",stripslashes($rowsite['Libelle']))."</option>\n";
						echo "<script>Liste_Presta[".$i."] = new Array(".$rowsite['Id'].",'".str_replace("'"," ",$rowsite['Libelle'])."',".$rowsite['Id_Plateforme'].");</script>";
						$i++;
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Jour fixe";}else{echo "Fixed day";}?> : </td>
				<td colspan="3"><input name="dateJour" size="50" type="date" value="<?php if($Modif){echo AfficheDateFR($row['DateJour']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Type d'absence";}else{echo "Type of absence";}?> : </td>
				<td colspan="3">
					<select name="typeAbsence" id="typeAbsence" style="width:150px;">
						<?php
						if($_SESSION["Langue"]=="FR"){
							$req="SELECT Id, 
							CodePlanning, Libelle, Suppr
							FROM rh_typeabsence
							ORDER BY CodePlanning";
						}
						else{
							$req="SELECT Id, 
							CodePlanning,
							LibelleEN AS Libelle, Suppr
							FROM rh_typeabsence
							ORDER BY CodePlanning";
						}
						$resultSelect=mysqli_query($bdd,$req);
						while($rowSelect=mysqli_fetch_array($resultSelect))
						{
							if($Modif==false){
								if($rowSelect['Suppr']==0){
									echo "<option value='".$rowSelect['Id']."'>".stripslashes($rowSelect['CodePlanning']." (".$rowSelect['Libelle'].")")."</option>";
								}
							}
							else{
								$selected="";
								if($rowSelect['Id']==$row['Id_TypeAbsence']){$selected="selected";}
								if($rowSelect['Suppr']==0 || $rowSelect['Id']==$row['Id_TypeAbsence']){
									echo "<option value='".$rowSelect['Id']."' ".$selected.">".stripslashes($rowSelect['CodePlanning']." (".$rowSelect['Libelle'].")")."</option>";
								}
							}
						}
						?>
					</select>
				</td>
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
	echo "<script>FiltrerPrestation();</script>";
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"UPDATE rh_jourfixe SET Suppr=1,Id_Suppr=".$_SESSION['Id_Personne'].", DateSuppr='".date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger(".$_GET['Menu'].");</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
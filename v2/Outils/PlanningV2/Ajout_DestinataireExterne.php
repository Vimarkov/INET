<html>
<head>
	<title>AT - Destinataire externe</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script type="text/javascript">
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.prestationPole.value=='0'){alert('Vous n\'avez pas la prestation.');return false;}
				if(formulaire.AdresseExterne.value==''){alert('Vous n\'avez pas l\'adresse du destinataire externe.');return false;}
				return true;
			}
			else{
				if(formulaire.prestationPole.value=='0'){alert('You did not fill in the site.');return false;}
				if(formulaire.AdresseExterne.value==''){alert('You did not fill in the address of the external recipient.');return false;}
				return true;
			}
		}
			
		function FermerEtRecharger(Menu)
		{
			window.opener.location="Tableau_De_BordAT.php?Menu="+Menu;
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");

$SrcProblem="";
$Problem=0;
$FichierTransfert=0;

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
		$Id_Prestation=0;
		$Id_Pole=0;
		if($_POST['prestationPole']<>"0"){
			$arrayPrestaPole=explode("_",$_POST['prestationPole']);
			$Id_Prestation=$arrayPrestaPole[0];
			$Id_Pole=$arrayPrestaPole[1];
		}
		
		$requeteInsertUpdate="INSERT INTO rh_at_destinataireexterne (AdresseExterne,Id_Prestation,Id_Pole,Id_Createur,DateCreation)";
		$requeteInsertUpdate.=" VALUES (";
		$requeteInsertUpdate.="'".addslashes($_POST['AdresseExterne'])."','".$Id_Prestation."','".$Id_Pole."','".$_SESSION['Id_Personne']."','".date('Y-m-d')."'";
		$requeteInsertUpdate.=")";
	}
	else
	{		
		$requeteInsertUpdate="UPDATE rh_at_destinataireexterne SET";
		$requeteInsertUpdate.=" AdresseExterne='".addslashes($_POST['AdresseExterne'])."' ";
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
			$result=mysqli_query($bdd,"SELECT Id, AdresseExterne,Id_Prestation,Id_Pole FROM rh_at_destinataireexterne WHERE Id=".$_GET['Id']." AND Suppr=0");
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_DestinataireExterne.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Menu" value="<?php echo $_GET['Menu']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $row['Id'];}?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation ";}else{echo "Site";}?> : </td>
				<td colspan="3">
					<select name="prestationPole" id="prestationPole" style="width:350px">
						<?php if(!$Modif){?>
						<option value="0"></option>
						<?php }?>
						<?php
						$rq="SELECT DISTINCT Id, Libelle, '' AS LibellePole, 0 AS Id_Pole,Id_Plateforme
							FROM new_competences_prestation
							WHERE Active=0
							AND Id NOT IN (
								SELECT Id_Prestation
								FROM new_competences_pole    
								WHERE Actif=0
							)
							
							UNION 
							
							SELECT DISTINCT new_competences_pole.Id_Prestation, new_competences_prestation.Libelle, 
								new_competences_pole.Libelle AS LibellePole, new_competences_pole.Id AS Id_Pole,new_competences_prestation.Id_Plateforme
								FROM new_competences_pole
								INNER JOIN new_competences_prestation
								ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
								AND Active=0
								AND Actif=0
								
							ORDER BY Libelle, LibellePole";

						$result=mysqli_query($bdd,$rq);
						while($rowsite=mysqli_fetch_array($result))
						{
							if(!$Modif || ($Modif && $rowsite['Id']."_".$rowsite['Id_Pole']==$row['Id_Prestation']."_".$row['Id_Pole'])){
								echo "<option class='presta' value='".$rowsite['Id']."_".$rowsite['Id_Pole']."'>";
								$pole="";
								if($rowsite['Id_Pole']>0){$pole=" - ".$rowsite['LibellePole'];}
								echo str_replace("'"," ",stripslashes($rowsite['Libelle'].$pole))."</option>\n";
							}
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Adresse destinataire externe";}else{echo "External recipient address";}?> : </td>
				<td colspan="3"><input name="AdresseExterne" id="AdresseExterne" size="50" type="text" value="<?php if($Modif){echo stripslashes($row['AdresseExterne']);}?>"></td>
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
		$result=mysqli_query($bdd,"UPDATE rh_at_destinataireexterne SET Suppr=1,Id_Suppr=".$_SESSION['Id_Personne'].", DateSuppr='".date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger(".$_GET['Menu'].");</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
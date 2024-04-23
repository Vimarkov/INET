<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Tools - Modèle de matériel</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script>
		function VerifChamps()
		{
			if(formulaire.Id_FamilleMateriel.value==''){alert('Vous n\'avez pas renseigné la famille de matériel.');return false;}
			if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
			return true;
		}
		
		Liste_FamilleMateriel_TypeMateriel = new Array();
		function Recharge_Liste_FamilleMateriel()
		{
			var sel="";
			sel ="<select size='1' name='Id_FamilleMateriel'>";
			
			for(var i=0;i<Liste_FamilleMateriel_TypeMateriel.length;i++)
			{
				if(Liste_FamilleMateriel_TypeMateriel[i][0]==document.getElementById('Id_TypeMateriel').value)
				{
					sel= sel + "<option value="+Liste_FamilleMateriel_TypeMateriel[i][1];
					if(Liste_FamilleMateriel_TypeMateriel[i][1]==document.getElementById('Id_FamilleMateriel_Initial').value){sel = sel + " selected";}
					sel= sel + ">"+Liste_FamilleMateriel_TypeMateriel[i][2]+"</option>";
				}
			}
			sel =sel + "</select>";
			document.getElementById('FamilleMateriel').innerHTML=sel;
		}
	</script>
</head>
<body>

<?php
require("../Connexioni.php");
require_once("../Fonctions.php");

$TablePrincipale="tools_modelemateriel";
$RequeteInsertUpdate="";

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
	    $Result=mysqli_query($bdd,"SELECT Id FROM ".$TablePrincipale." WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Id_FamilleMateriel='".$_POST['Id_FamilleMateriel']."'");
		if(mysqli_num_rows($Result)==0)
		{
		    $RequeteInsertUpdate="
				INSERT INTO "
					.$TablePrincipale."
				(
					Id_FamilleMateriel,
					Libelle,
					Reglable,
					Connectiques
					
				)
				VALUES
				(
					'".$_POST['Id_FamilleMateriel']."',
					'".addslashes($_POST['Libelle'])."',
					".$_POST['Reglable'].",
					'".addslashes($_POST['Connectiques'])."'
				);";
		}
	}
	elseif($_POST['Mode']=="Modif")
	{
	    $Result=mysqli_query($bdd,"SELECT Id FROM ".$TablePrincipale." WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Id_FamilleMateriel='".$_POST['Id_FamilleMateriel']."' AND Id!=".$_POST['Id']);
		if(mysqli_num_rows($Result)==0)
		{
		    $RequeteInsertUpdate="
				UPDATE "
					.$TablePrincipale."
				SET
					Id_FamilleMateriel='".$_POST['Id_FamilleMateriel']."',
					Libelle='".addslashes($_POST['Libelle'])."',
					Reglable=".$_POST['Reglable'].",
					Connectiques='".addslashes($_POST['Connectiques'])."'
				WHERE
					Id='".$_POST['Id']."';";
		}
	}
	
	if($RequeteInsertUpdate != "")
	{
	    $ResultInsertUpdate=mysqli_query($bdd,$RequeteInsertUpdate);
	    echo "<script>FermerEtRecharger('ModeleMateriel');</script>";
	}
	else
	{
	    echo "<font class='Erreur'>Ce libellé existe déjà.<br>Vous devez recommencer l'opération.</font>";
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Modif=false;
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0')
		{
			$Modif=true;
			$Requete="
				SELECT
					Id,
					(SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE Id=Id_FamilleMateriel) AS ID_TYPEMATERIEL,
					Id_FamilleMateriel,
					Libelle,Reglable,Connectiques
				FROM
					".$TablePrincipale."
				WHERE
					Id='".$_GET['Id']."';";
			$Result=mysqli_query($bdd,$Requete);
			$Row=mysqli_fetch_array($Result);
		}
?>
		<form id="formulaire" method="POST" action="" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $Row['Id'];}?>">
		<input type="hidden" id="Id_FamilleMateriel_Initial" value="<?php if($Modif){echo $Row['Id_FamilleMateriel'];}else{echo "0";}?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Type de matériel";}else{echo "Kind of material";}?> : </td>
				<td>
					<select name="Id_TypeMateriel" id="Id_TypeMateriel" onchange="Recharge_Liste_FamilleMateriel()";>
					<?php
					$RequeteTypeMateriel="
						SELECT
							Id,
							Libelle
						FROM
							tools_typemateriel
						WHERE
							Suppr=0
							AND Id>0
						ORDER BY
							Libelle ASC";
					$ResultTypeMateriel=mysqli_query($bdd,$RequeteTypeMateriel);
					while($RowTypeMateriel=mysqli_fetch_array($ResultTypeMateriel))
					{
						echo "<option value='".$RowTypeMateriel['Id']."'";
						if($Modif){if($Row['ID_TYPEMATERIEL']==$RowTypeMateriel['Id']){echo " selected";}}
						echo ">".$RowTypeMateriel['Libelle']."</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Famille de matériel";}else{echo "Material family";}?> : </td>
				<td>
					<div id="FamilleMateriel">
						<select size="1" name="Id_FamilleMateriel"></select>
					</div>
					<?php
					$RequeteFamilleMateriel="
						SELECT
							Id_TypeMateriel,
							Id,
							Libelle
						FROM
							tools_famillemateriel
						WHERE
							Suppr=0
						ORDER BY
							Libelle ASC";
					$ResultFamilleMateriel=mysqli_query($bdd,$RequeteFamilleMateriel);
					$i=0;
					while($RowFamilleMateriel=mysqli_fetch_array($ResultFamilleMateriel))
					{
						 echo "<script>Liste_FamilleMateriel_TypeMateriel[".$i."] = new Array(".$RowFamilleMateriel['Id_TypeMateriel'].",".$RowFamilleMateriel['Id'].",'".addslashes($RowFamilleMateriel['Libelle'])."');</script>\n";
						 $i+=1;
					}
					?>
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td><input name="Libelle" size="75" type="text" value="<?php if($Modif){echo stripslashes(htmlspecialchars($Row['Libelle']));}?>"></td>
			</tr>
			
			<?php 
			$style="style='display:none;'";
			if($Modif){if($Row['Id_FamilleMateriel']==165){$style="";}}
				?>
			<tr <?php echo $style; ?>>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Réglable";}else{echo "Adjustable";}?> : </td>
				<td>
				<select name="Reglable" id="Reglable">
					<?php 
						$selected1="";
						$selected0="";
						
						if($Modif){
							if($Row['Reglable']==1){$selected1="selected";}
							else{$selected0="selected";}
						}
					?>
					<option value="1" <?php echo $selected1; ?>><?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}?></option>
					<option value="0"  <?php echo $selected0; ?>><?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";}?></option>
				</select>
				</td>
			</tr>
			<tr <?php echo $style; ?>>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Connectiques";}else{echo "Connectors";}?> : </td>
				<td><input name="Connectiques" size="75" type="text" value="<?php if($Modif){echo $Row['Connectiques'];}?>"></td>
			</tr>
			<tr>
				<td colspan=2 align="center">
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
		$Result=mysqli_query($bdd,"UPDATE ".$TablePrincipale." SET Suppr=1 WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger('ModeleMateriel');</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($Result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
	echo "<script>Recharge_Liste_FamilleMateriel();</script>";
?>
	
</body>
</html>
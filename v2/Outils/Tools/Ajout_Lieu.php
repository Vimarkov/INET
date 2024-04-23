<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Tools - Lieu</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script>
		function VerifChamps()
		{
			if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
			else{return true;}
		}
		
		Liste_Pole_Prestation = new Array();
		
		function Change_Prestation()
		{
			var sel="";
			sel ="<select size='1' name='Id_Pole' id='Id_Pole'>";
			sel= sel + "<option value=0></option>";
			for(var i=0;i<Liste_Pole_Prestation.length;i++)
			{
				if(Liste_Pole_Prestation[i][0]==document.getElementById('Id_Prestation').value)
				{
					sel= sel + "<option value="+Liste_Pole_Prestation[i][1];
					if(Liste_Pole_Prestation[i][1]==document.getElementById('Id_Pole_Initial').value){sel = sel + " selected";}
					sel= sel + ">"+Liste_Pole_Prestation[i][2]+"</option>";
				}
			}
			sel =sel + "</select>";
			document.getElementById('Div_Pole').innerHTML=sel;
		}
	</script>
</head>
<body>

<?php
require("../Connexioni.php");
require_once("../Fonctions.php");

$TablePrincipale="tools_lieu";
$RequeteInsertUpdate="";

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
	    $Result=mysqli_query($bdd,"SELECT Id FROM ".$TablePrincipale." WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Id_Prestation='".$_POST['Id_Prestation']."'");
		if(mysqli_num_rows($Result)==0)
		{
		    $RequeteInsertUpdate="
				INSERT INTO "
					.$TablePrincipale."
				(
					Id_Prestation,
					Id_Pole,
					Libelle
				)
				VALUES
				(
					'".$_POST['Id_Prestation']."',
					'".$_POST['Id_Pole']."',
					'".addslashes($_POST['Libelle'])."'
				);";
		}
	}
	elseif($_POST['Mode']=="Modif")
	{
	    $Result=mysqli_query($bdd,"SELECT Id FROM ".$TablePrincipale." WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Id_Prestation='".$_POST['Id_Prestation']."' AND Id!=".$_POST['Id']);
		if(mysqli_num_rows($Result)==0)
		{
		    $RequeteInsertUpdate="
				UPDATE "
					.$TablePrincipale."
				SET
					Id_Prestation='".$_POST['Id_Prestation']."',
					Libelle='".addslashes($_POST['Libelle'])."'
				WHERE
					Id='".$_POST['Id']."';";
		}
	}
	
	if($RequeteInsertUpdate != "")
	{
	    $ResultInsertUpdate=mysqli_query($bdd,$RequeteInsertUpdate);
	    echo "<script>FermerEtRecharger('Lieu');</script>";
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
			$Result=mysqli_query($bdd,"SELECT Id, Id_Prestation, Id_Pole, Libelle FROM ".$TablePrincipale." WHERE Id='".$_GET['Id']."';");
			$Row=mysqli_fetch_array($Result);
		}
?>
		<form id="formulaire" method="POST" action="" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $Row['Id'];}?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?> : </td>
				<td>
					<select name="Id_Prestation" id="Id_Prestation" onclick="Change_Prestation();">
					<?php
					$RequetePrestation="
						SELECT
							new_competences_prestation.Id,
							new_competences_prestation.Libelle AS PRESTATION,
							new_competences_plateforme.Libelle AS PLATEFORME
						FROM
							new_competences_prestation,
							new_competences_plateforme
						WHERE
							new_competences_prestation.Active=0
							AND new_competences_prestation.Id_Plateforme IN
								(SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne=".$IdPersonneConnectee.")
							AND new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
						ORDER BY
							PLATEFORME ASC,
							PRESTATION ASC";
					$ResultPrestation=mysqli_query($bdd,$RequetePrestation);
					while($RowPrestation=mysqli_fetch_array($ResultPrestation))
					{
						echo "<option value='".$RowPrestation['Id']."'";
						if($Modif){if($Row['Id_Prestation']==$RowPrestation['Id']){echo " selected";}}
						echo ">".$RowPrestation['PLATEFORME']." # ".$RowPrestation['PRESTATION']."</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<input type="hidden" id="Id_Pole_Initial" value="<?php if($Modif){echo $Row['Id_Pole'];}?>">
				<td><?php if($LangueAffichage=="FR"){echo "Pôle";}else{echo "Pole";}?> : </td>
				<td>
					<div id="Div_Pole">
						<select size="1" name="Id_Pole"></select>
					</div>
					<?php
					$RequetePole="
						SELECT
							Id_Prestation,
							Id,
							Libelle
						FROM
							new_competences_pole
						WHERE
							Actif=0
						ORDER BY
							Libelle ASC";
					$ResultPole=mysqli_query($bdd,$RequetePole);
					$i=0;
					while($RowPole=mysqli_fetch_array($ResultPole))
					{
						echo "<script>Liste_Pole_Prestation[".$i."] = new Array(".$RowPole['Id_Prestation'].",".$RowPole['Id'].",'".addslashes($RowPole['Libelle'])."');</script>\n";
						$i+=1;
					}
					?>
				</td>
			</tr>
			<tr>
				<td><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td><input name="Libelle" size="75" type="text" value="<?php if($Modif){echo $Row['Libelle'];}?>"></td>
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
		echo "<script>FermerEtRecharger('Lieu');</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($Result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
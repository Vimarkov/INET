<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Tools - Matériel</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script type="text/javascript">
		function VerifChamps()
		{
			if(formulaire.Langue.value=="FR"){
				if(formulaire.dateSAV.value==''){alert('Vous n\'avez pas renseigné la date.');return false;}
			}
			else{
				if(formulaire.dateSAV.value==''){alert('You did not fill in the date.');return false;}
			}
			return true;
		}
		function OuvreFenetreSuppr(Type,Id,Id_Mouvement){
			var w=window.open("Suppr_SAV.php?Type="+Type+"&Id="+Id+"&Id_Mouvement="+Id_Mouvement,"PageToolsSuppr","status=no,menubar=no,width=50,height=50");
			w.focus();
		}
	</script>
</head>
<body>

<?php
require("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");

if($_POST)
{
	if(isset($_POST['valider'])){
	//Ajout de l'étalonnage
		$RequeteMouvement="
			INSERT INTO
				tools_mouvement
			(
				Type,
				TypeMouvement,
				Id_Materiel__Id_Caisse,
				SAV_Id_Organisme,
				SAV_Date,
				SAV_DevisAccepte,
				SAV_Prix,
				SAV_BonCommande,
				SAV_Remarque,
				SAV_Id_PersonneMAJ,
				SAV_DateMAJ
			)
			VALUES
			(
				'".$_POST['Type']."',
				'3',
				'".$_POST['Id']."',
				'".$_POST['organisme']."',
				'".TrsfDate_($_POST['dateSAV'])."',
				'".$_POST['devisAccepte']."',
				'".$_POST['prix']."',
				'".addslashes($_POST['bonCommande'])."',
				'".addslashes($_POST['remarques'])."',
				'".$IdPersonneConnectee."',
				'".$DateJour."'
			);";
		$ResultMouvement=mysqli_query($bdd,$RequeteMouvement);
	}
}

$Id=0;
$Type=-1;
if($_POST){$Id=$_POST['Id'];$Type=$_POST['Type'];}
else{$Id=$_GET['Id'];$Type=$_GET['Type'];}
	if($Type==0){
		$Requete="
				SELECT
					NumAAA
				FROM
					tools_materiel
				WHERE
					Id='".$Id."';";
	}
	else{
		$Requete="
				SELECT
					Num AS NumAAA
				FROM
					tools_caisse
				WHERE
					Id='".$Id."';";
	}
		
	$Result=mysqli_query($bdd,$Requete);
	$Row=mysqli_fetch_array($Result);
?>
<form id="formulaire" method="POST" action="" onSubmit="return VerifChamps();">
<input type="hidden" name="Id" value="<?php echo $Id; ?>">
<input type="hidden" name="Type" value="<?php echo $Type; ?>">
<input type="hidden" name="Langue" value="<?php echo $_SESSION['Langue'];?>">
<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#a127f1;">
	<tr>
		<td class="TitrePage">
		<?php
		if($LangueAffichage=="FR"){echo "SAV ".$Row['NumAAA'];}else{echo "SAV ".$Row['NumAAA'];}
		?>
		</td>
	</tr>
</table><br>
<table style="width:100%; height:95%; align:center;" class="TableCompetences">
	<tr>
		<td style="color:#22b63d" class="Libelle"  align="center" colspan="6"><?php if($LangueAffichage=="FR"){echo "AJOUT";}else{echo "ADD";} ?></td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Date";}else{echo "Date";}?> : </td>
		<td><input name="dateSAV" size="25" type="date" value=""></td>
		<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Organisme :";}else{echo "Organization :";} ?></td>
		<td>
			<select name="organisme" id="organisme">
			<?php
			$rq="SELECT Id, Libelle
				FROM tools_tiers 
				WHERE Suppr=0
				AND Type=1
				ORDER BY Libelle ASC ";
			$resultcaisse=mysqli_query($bdd,$rq);
			$Id_Caisse=0;
			while($row=mysqli_fetch_array($resultcaisse))
			{
				echo "<option value='".$row['Id']."' >".str_replace("'"," ",stripslashes($row['Libelle']))."</option>\n";
			}
			?>
			</select>
		</td>
		<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Devis accepté";}else{echo "Quote accepted";}?> : </td>
		<td>
			<select name="devisAccepte">
				<option value="1" selected><?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}?></option>
				<option value="0" ><?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";}?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Bon de commande";}else{echo "Purchase order";}?> : </td>
		<td><input name="bonCommande" size="25" type="text" value=""></td>
		<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Prix (€)";}else{echo "Price (€)";}?> : </td>
		<td><input name="prix" onKeyUp="nombre(this)" size="5" type="text" value=""></td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td class="Libelle" valign="top"><?php if($LangueAffichage=="FR"){echo "Remarques";}else{echo "Remarks";}?> : </td>
		<td colspan="6"><textarea name="remarques" rows="5" cols="100" rows="3" style="resize: none;"></textarea></td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td colspan="6" align="center">
			<input class="Bouton" name="valider" type="submit"
			<?php
				if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
			?>
			>
		</td>
	</tr>
</table><br>
<table style="width:100%; height:95%; align:center;" class="TableCompetences">
	<tr>
		<td style="color:#22b63d" class="Libelle" align="center" colspan="10"><?php if($LangueAffichage=="FR"){echo "HISTORIQUE";}else{echo "HISTORICAL";} ?></td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Date";}else{echo "Date";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Organisme";}else{echo "Organization";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Bon de commande";}else{echo "Purchase order";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Prix";}else{echo "Price";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Devis accepté";}else{echo "Quote accepted";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Remarque";}else{echo "Comment";}?></td>
		<td class="EnTeteTableauCompetences" width="2%"></td>
	</tr>
	<?php
		$req="SELECT Id,
			(SELECT Libelle FROM tools_tiers WHERE Id=SAV_Id_Organisme) AS Organisme,
			SAV_Date,SAV_BonCommande,SAV_Prix,SAV_Remarque,SAV_DevisAccepte
			FROM tools_mouvement
			WHERE Suppr=0
			AND Id_Materiel__Id_Caisse=".$Id."
			AND Type=".$Type."
			AND TypeMouvement=3
			ORDER BY SAV_Date DESC, Id DESC
		";
		$Result=mysqli_query($bdd,$req);
		$NbEnreg=mysqli_num_rows($Result);
		if($NbEnreg>0)
		{
		$Couleur="#EEEEEE";
		while($Row=mysqli_fetch_array($Result))
		{
			if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
			else{$Couleur="#EEEEEE";}
			if($Row['SAV_DevisAccepte']==1){$devis="<img width='15px' src='../../Images/tick.png' border='0'>";}
			else{$devis="<img width='15px' src='../../Images/delete.png' border='0'>";}
	?>
		<tr bgcolor="<?php echo $Couleur;?>">
			<td><?php echo AfficheDateJJ_MM_AAAA($Row['SAV_Date']);?></td>
			<td><?php echo stripslashes($Row['Organisme']);?></td>
			<td><?php echo stripslashes($Row['SAV_BonCommande']);?></td>
			<td><?php echo stripslashes($Row['SAV_Prix']);?></td>
			<td><?php echo stripslashes($devis);?></td>
			<td><?php echo nl2br(stripslashes($Row['SAV_Remarque']));?></td>
			<td><input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){OuvreFenetreSuppr('<?php echo $Type; ?>','<?php echo $Id; ?>','<?php echo $Row['Id']; ?>');}"></td>
		</tr>
	<?php
		}	//Fin boucle
	}		//Fin If
	mysqli_free_result($Result);	// Libération des résultats
	?>
</table>
</form>
</body>
</html>
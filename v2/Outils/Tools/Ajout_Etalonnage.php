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
				if(formulaire.dateVerif.value==''){alert('Vous n\'avez pas renseigné la date de vérification.');return false;}
				if(formulaire.numPV.value==''){alert('Vous n\'avez pas renseigné le numéro de PV.');return false;}
			}
			else{
				if(formulaire.dateVerif.value==''){alert('You did not fill in the verification date.');return false;}
				if(formulaire.numPV.value==''){alert('You have not entered the PV number.');return false;}
			}
			return true;
		}
		function OuvreFenetreSuppr(Id,Id_Mouvement){
			var w=window.open("Suppr_Etalonnage.php?Id="+Id+"&Id_Mouvement="+Id_Mouvement,"PageToolsEtalonnage","status=no,menubar=no,width=50,height=50");
			w.focus();
		}
		function OuvreFenetreExcel(Id)
			{window.open("Export_FicheDeVie.php?Id="+Id,"PageExcel","status=no,menubar=no,width=900,height=450");}
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
				FV_Id_Laboratoire,
				FV_DateEtalonnage,
				FV_Conformite,
				FV_NumPV,
				FV_BonCommande,
				FV_Prix,
				FV_Remarques,
				FV_Id_Decision,
				FV_Id_PersonneMAJ,
				FV_DateMAJ
			)
			VALUES
			(
				'0',
				'1',
				'".$_POST['Id']."',
				'".$_POST['organisme']."',
				'".TrsfDate_($_POST['dateVerif'])."',
				'".$_POST['conforme']."',
				'".$_POST['numPV']."',
				'".$_POST['bonCommande']."',
				'".$_POST['prix']."',
				'".addslashes($_POST['remarques'])."',
				'".$_POST['decision']."',
				'".$IdPersonneConnectee."',
				'".$DateJour."'
			);";
		$ResultMouvement=mysqli_query($bdd,$RequeteMouvement);
	}
	if($_POST['Page']=="Etalonnage"){
		echo "<script>FermerEtRecharger('".$_POST['Page']."');</script>";
	}
}

$Id=0;
if($_POST){$Id=$_POST['Id'];}
else{$Id=$_GET['Id'];}
		$Requete="
				SELECT
					NumAAA
				FROM
					tools_materiel
				WHERE
					Id='".$Id."';";
			$Result=mysqli_query($bdd,$Requete);
			$Row=mysqli_fetch_array($Result);
?>
<form id="formulaire" method="POST" action="" onSubmit="return VerifChamps();">
<input type="hidden" name="Id" value="<?php echo $Id; ?>">
<input type="hidden" name="Langue" value="<?php echo $_SESSION['Langue'];?>">
<input type="hidden" name="Page" name="Page" value="<?php if(isset($_GET['Page'])){echo $_GET['Page'];} ?>">
<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#23f5d0;">
	<tr>
		<td class="TitrePage">
		<?php
		if($LangueAffichage=="FR"){echo "Maintenance & Etalonnage ".$Row['NumAAA'];}else{echo "Maintenance & Calibration ".$Row['NumAAA'];}
		?>
		</td>
	</tr>
</table><br>
<table style="width:100%; height:95%; align:center;" class="TableCompetences">
	<tr>
		<td style="color:#22b63d" class="Libelle"  align="center" colspan="5"><?php if($LangueAffichage=="FR"){echo "AJOUT";}else{echo "ADD";} ?></td>
		<td width="5%" align="right">
			&nbsp;&nbsp;&nbsp;
			<a href="javascript:OuvreFenetreExcel(<?php echo $Id;?>)">
			<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
			</a>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "N° PV";}else{echo "PV number";}?> : </td>
		<td><input name="numPV" size="25" type="text" value=""></td>
		<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Date de vérif";}else{echo "Check date";}?> : </td>
		<td><input name="dateVerif" size="25" type="date" value=""></td>
		<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Organisme :";}else{echo "Organization :";} ?></td>
		<td>
			<select name="organisme" id="organisme">
			<?php
			$rq="SELECT Id, Libelle
				FROM tools_tiers 
				WHERE Suppr=0
				AND Type=3
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
		<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Conforme";}else{echo "Compliant";}?> : </td>
		<td>
			<select name="conforme">
				<option value="1" selected><?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}?></option>
				<option value="0" ><?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";}?></option>
			</select>
		</td>
		<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Décision (si non conforme) :";}else{echo "Decision (if not compliant) :";} ?></td>
		<td>
			<select name="decision" id="decision">
				<option value="0"></option>
			<?php
			if($_SESSION['Langue']=="FR"){
				$rq="SELECT Id, Libelle
					FROM tools_decision 
					WHERE Suppr=0
					ORDER BY Libelle ASC ";
			}
			else{
				$rq="SELECT Id, LibelleEN AS Libelle
					FROM tools_decision 
					WHERE Suppr=0
					ORDER BY Libelle ASC ";

			}
			$resultcaisse=mysqli_query($bdd,$rq);
			while($row=mysqli_fetch_array($resultcaisse))
			{
				echo "<option value='".$row['Id']."' >".str_replace("'"," ",stripslashes($row['Libelle']))."</option>\n";
			}
			?>
			</select>
		</td>
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
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Date de vérif";}else{echo "Date of verification";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Organisme";}else{echo "Organization";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "N° PV";}else{echo "PV number";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Bon de commande";}else{echo "Purchase order";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Prix";}else{echo "Price";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Conforme";}else{echo "Compliant";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Décision";}else{echo "Decision";}?></td>
		<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Remarque";}else{echo "Comment";}?></td>
		<td class="EnTeteTableauCompetences" width="2%"></td>
	</tr>
	<?php
		if($_SESSION['Langue']=="FR"){
			$req="SELECT Id,
			(SELECT Libelle FROM tools_tiers WHERE Id=FV_Id_Laboratoire) AS Organisme,
			FV_DateEtalonnage,FV_Conformite,FV_NumPV,FV_BonCommande,FV_Prix,FV_Remarques,
			(SELECT Libelle FROM tools_decision WHERE Id=FV_Id_Decision) AS Decision
			FROM tools_mouvement
			WHERE Suppr=0
			AND Id_Materiel__Id_Caisse=".$Id."
			AND TypeMouvement=1
			AND Type=0
			ORDER BY FV_DateEtalonnage DESC, Id DESC
		";
		}
		else{
			$req="SELECT Id,
			(SELECT Libelle FROM tools_tiers WHERE Id=FV_Id_Laboratoire) AS Organisme,
			FV_DateEtalonnage,FV_Conformite,FV_NumPV,FV_BonCommande,FV_Prix,FV_Remarques,
			(SELECT LibelleEN FROM tools_decision WHERE Id=FV_Id_Decision) AS Decision
			FROM tools_mouvement
			WHERE Suppr=0
			AND Id_Materiel__Id_Caisse=".$Id."
			AND TypeMouvement=1
			AND Type=0
			ORDER BY FV_DateEtalonnage DESC, Id DESC
		";
		}
		$Result=mysqli_query($bdd,$req);
		$NbEnreg=mysqli_num_rows($Result);
		if($NbEnreg>0)
		{
		$Couleur="#EEEEEE";
		while($Row=mysqli_fetch_array($Result))
		{
			if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
			else{$Couleur="#EEEEEE";}
			if($Row['FV_Conformite']==1){$conforme="<img width='15px' src='../../Images/tick.png' border='0'>";}
			else{$conforme="<img width='15px' src='../../Images/delete.png' border='0'>";}
	?>
		<tr bgcolor="<?php echo $Couleur;?>">
			<td><?php echo AfficheDateJJ_MM_AAAA($Row['FV_DateEtalonnage']);?></td>
			<td><?php echo stripslashes($Row['Organisme']);?></td>
			<td><?php echo stripslashes($Row['FV_NumPV']);?></td>
			<td><?php echo stripslashes($Row['FV_BonCommande']);?></td>
			<td><?php echo stripslashes($Row['FV_Prix']);?></td>
			<td><?php echo stripslashes($conforme);?></td>
			<td><?php echo stripslashes($Row['Decision']);?></td>
			<td><?php echo nl2br(stripslashes($Row['FV_Remarques']));?></td>
			<td><input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){OuvreFenetreSuppr('<?php echo $Id; ?>','<?php echo $Row['Id']; ?>');}"></td>
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
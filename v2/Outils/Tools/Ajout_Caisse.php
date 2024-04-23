<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Tools - Caisse à outils</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script>
		function VerifChamps()
		{
			if(formulaire.Num.value==''){alert('Vous n\'avez pas renseigné le numéro.');return false;}
			else{return true;}
		}
		function Change_CodeArticle()
		{
			if(document.getElementById('Id_CodeArticle').value!=0){
				tab = document.getElementById('Id_CodeArticle').value.split('_');
				if(tab[1]==1){
					document.getElementById('Immo1').style.display="";
					document.getElementById('Immo2').style.display="";
				}
				else{
					document.getElementById('Immo1').style.display="none";
					document.getElementById('Immo2').style.display="none";
				}
			}
		}
		function Change_Location()
		{
			if(document.getElementById('Location').value==1){
				document.getElementById('Location1').style.display="";
			}
			else{
				document.getElementById('Location1').style.display="none";
			}
		}
	</script>
</head>
<body>

<?php
require("../Connexioni.php");
require_once("../Fonctions.php");

$TablePrincipale="tools_caisse";
$RequeteInsertUpdate="";

if($_POST)
{


	$tabCodeArticle=explode("_",$_POST['Id_CodeArticle']);
	$RequeteInsertUpdate="
		UPDATE "
			.$TablePrincipale."
		SET
			Id_CaisseType='".$_POST['Id_CaisseType']."',
			NumAAA='".$_POST['NumAAA']."',
			Num='".$_POST['Num']."',
			BonCommande='".stripslashes($_POST['BonCommande'])."',
			SN='".$_POST['SN']."',
			Id_Fournisseur='".$_POST['Id_Fournisseur']."',
			Id_Fabricant='".$_POST['Id_Fabricant']."',
			Prix='".$_POST['Prix']."',
			Id_FamilleMateriel='".$_POST['Id_FamilleMateriel']."',
			NumFacture='".addslashes($_POST['NumFacture'])."',
			Id_CodeArticle=".$tabCodeArticle[0].",
			NumFicheImmo='".addslashes($_POST['NumFicheImmo'])."',
			DateDebutImmo='".TrsfDate_($_POST['DateDebutImmo'])."',
			DateFinImmo='".TrsfDate_($_POST['DateFinImmo'])."',
			Location=".$_POST['Location'].",
			DateDebutLocation='".TrsfDate_($_POST['DateDebutLocation'])."',
			DateFinContratLocation='".TrsfDate_($_POST['DateFinLocation'])."'
		WHERE
			Id='".$_POST['Id']."';";

	$ResultInsertUpdate=mysqli_query($bdd,$RequeteInsertUpdate);
	echo "<script>FermerEtRecharger('Materiel');</script>";

}
elseif($_GET)
{
	//Mode ajout ou modification
	$Modif=false;
	if($_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0')
		{
			$Modif=true;
			$req="SELECT Id, Id_CaisseType, Num, BonCommande,NumAAA,SN,Id_CaisseType,Id_Fournisseur,Id_Fabricant,NumFacture,Id_CodeArticle,Prix,Id_FamilleMateriel,(SELECT Immo FROM tools_codearticle WHERE Id=Id_CodeArticle) AS Immo,NumFicheImmo,DateDebutImmo,DateFinImmo,Location,DateDebutLocation,DateFinContratLocation FROM ".$TablePrincipale." WHERE Id='".$_GET['Id']."';";
			$Result=mysqli_query($bdd,$req);
			$Row=mysqli_fetch_array($Result);
		}
?>
		<form id="formulaire" method="POST" action="" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $Row['Id'];}?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "N° AAA";}else{echo "N° AAA";}?> : </td>
				<td><input name="NumAAA" size="25" type="text" value="<?php if($Modif){echo $Row['NumAAA'];}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Type de caisse";}else{echo "Kind of toolbox";}?> : </td>
				<td>
					<select name="Id_CaisseType">
					<?php
					$RequeteTypeCaisse="
						SELECT
							Id,
							Libelle
						FROM
							tools_caissetype
						WHERE
							Suppr=0
						ORDER BY
							Libelle ASC";
					$ResultTypeCaisse=mysqli_query($bdd,$RequeteTypeCaisse);
					while($RowTypeCaisse=mysqli_fetch_array($ResultTypeCaisse))
					{
						echo "<option value='".$RowTypeCaisse['Id']."'";
						if($Modif){if($Row['Id_CaisseType']==$RowTypeCaisse['Id']){echo " selected";}}
						echo ">".$RowTypeCaisse['Libelle']."</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Famille de matériel";}else{echo "Family of material";}?> : </td>
				<td>
					<select name="Id_FamilleMateriel">
					<?php
					$RequeteFamilleMateriel="
						SELECT
							Id,
							Libelle
						FROM
							tools_famillemateriel
						WHERE
							Suppr=0
						AND Id_TypeMateriel=-1
						ORDER BY
							Libelle ASC";
					$ResultFamilleMateriel=mysqli_query($bdd,$RequeteFamilleMateriel);
					$i=0;
					while($RowFamilleMateriel=mysqli_fetch_array($ResultFamilleMateriel))
					{
						echo "<option value='".$RowFamilleMateriel['Id']."'";
						if($Modif){if($Row['Id_FamilleMateriel']==$RowFamilleMateriel['Id']){echo " selected";}}
						echo ">".$RowFamilleMateriel['Libelle']."</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Fabricant";}else{echo "Manufacturer";}?> : </td>
				<td>
					<select name="Id_Fabricant">
						<option value="0"></option>
					<?php
					$RequeteFabricant="
						SELECT
							Id,
							Libelle
						FROM
							tools_tiers
						WHERE
							Type=1
							AND Suppr=0
						ORDER BY
							Libelle ASC";
					$ResultFabricant=mysqli_query($bdd,$RequeteFabricant);
					while($RowFabricant=mysqli_fetch_array($ResultFabricant))
					{
						echo "<option value='".$RowFabricant['Id']."'";
						if($Modif){if($Row['Id_Fabricant']==$RowFabricant['Id']){echo " selected";}}
						echo ">".$RowFabricant['Libelle']."</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Fournisseur";}else{echo "Provider";}?> : </td>
				<td>
					<select name="Id_Fournisseur">
						<option value="0"></option>
					<?php
					$RequeteFournisseur="
						SELECT
							Id,
							Libelle
						FROM
							tools_tiers
						WHERE
							Type=2
							AND Suppr=0
						ORDER BY
							Libelle ASC";
					$ResultFournisseur=mysqli_query($bdd,$RequeteFournisseur);
					while($RowFournisseur=mysqli_fetch_array($ResultFournisseur))
					{
						echo "<option value='".$RowFournisseur['Id']."'";
						if($Modif){if($Row['Id_Fournisseur']==$RowFournisseur['Id']){echo " selected";}}
						echo ">".$RowFournisseur['Libelle']."</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Numéro";}else{echo "Num";}?> : </td>
				<td><input name="Num" size="25" type="text" value="<?php if($Modif){echo $Row['Num'];}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "S/N";}else{echo "S/N";}?> : </td>
				<td><input name="SN" size="25" type="text" value="<?php if($Modif){echo $Row['SN'];}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Prix";}else{echo "Price";}?> : </td>
				<td><input name="Prix" size="10" onKeyUp="nombre(this)" type="text" value="<?php if($Modif){echo $Row['Prix'];}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Bon de commande";}else{echo "Purchase order";}?> : </td>
				<td><input name="BonCommande" size="25" type="text" value="<?php if($Modif){echo stripslashes($Row['BonCommande']);}?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Achat / Location";}else{echo "Purchase / Rental";}?> : </td>
				<td>
					<select name="Location" id="Location" onclick="Change_Location();">
						<option value="0" <?php if($Modif){if($Row['Location']=="0"){echo " selected";}} ?>><?php if($LangueAffichage=="FR"){echo "Achat";}else{echo "Purchase";}?></option>
						<option value="1" <?php if($Modif){if($Row['Location']=="1"){echo " selected";}} ?>><?php if($LangueAffichage=="FR"){echo "Location";}else{echo "Rental";}?></option>
					</select>
				</td>
			</tr>
			<tr style="display:<?php if($Modif){if($Row['Location']=="0"){echo "none";}} ?>" id="Location1" >
				<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "Date début contrat<br> de location";}else{echo "Rental contract<br> start date";}?> : </td>
				<td><input name='DateDebutLocation' size='15' type='date' value='<?php if($Modif){echo AfficheDateFR($Row['DateDebutLocation']);}?>'></td>
				<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "Date fin contrat de location";}else{echo "Rental contract end date";}?> : </td>
				<td><input name='DateFinLocation' size='15' type='date' value='<?php if($Modif){echo AfficheDateFR($Row['DateFinContratLocation']);}?>'></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Code article";}else{echo "Item code";}?> : </td>
				<td>
					<select name="Id_CodeArticle" id="Id_CodeArticle" onclick="Change_CodeArticle();">
						<option value="0"></option>
					<?php
					$RequeteCodeArticle="
						SELECT
							Id,CodeArticle,Immo
						FROM
							tools_codearticle
						WHERE
							Suppr=0
						ORDER BY
							CodeArticle ASC";
					$ResultCodeArticle=mysqli_query($bdd,$RequeteCodeArticle);
					while($RowCodeArticle=mysqli_fetch_array($ResultCodeArticle))
					{
						echo "<option value='".$RowCodeArticle['Id']."_".$RowCodeArticle['Immo']."'";
						if($Modif){if($Row['Id_CodeArticle']==$RowCodeArticle['Id']){echo " selected";}}
						echo ">".$RowCodeArticle['CodeArticle']."</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			<tr style="display:<?php if($Modif){if($Row['Immo']=="0"){echo "none";}} ?>" id="Immo1">
				<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "N° fiche immobilisation";}else{echo "Asset sheet no.";}?> : </td>
				<td><input name='NumFicheImmo' size='15' type='text' value='<?php if($Modif){echo $Row['NumFicheImmo'];}?>'></td>
			</tr>
			<tr style="display:<?php if($Modif){if($Row['Immo']=="0"){echo "none";}} ?>" id="Immo2">
				<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "Date début <br>immobilisation";}else{echo "Start date <br>of immobilization";}?> : </td>
				<td><input name='DateDebutImmo' size='15' type='date' value='<?php if($Modif){echo AfficheDateFR($Row['DateDebutImmo']);}?>'></td>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Date fin immobilisation";}else{echo "End date of immobilization";}?> : </td>
				<td><input name='DateFinImmo' size='15' type='date' value='<?php if($Modif){echo AfficheDateFR($Row['DateFinImmo']);}?>'></td>
			</tr>
			<tr class="TitreColsUsers">
				<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "N° facture";}else{echo "Invoice number";}?> : </td>
				<td><input name='NumFacture' size='15' type='text' value='<?php if($Modif){echo $Row['NumFacture'];}?>'></td>
			</tr>
			<tr>
				<td height="5"></td>
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
		echo "<script>FermerEtRecharger('Materiel');</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($Result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
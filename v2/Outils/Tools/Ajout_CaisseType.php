<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Tools - Caisse à outils "type"</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js?t=<?php echo time(); ?>"></script>
	<script>
		function VerifChamps()
		{
			if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
			else{return true;}
		}
	</script>
</head>
<body>

<?php
require("../Connexioni.php");
require_once("../Fonctions.php");

$TablePrincipale="tools_caissetype";
$RequeteInsertUpdate="";

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
	    $Result=mysqli_query($bdd,"SELECT Id FROM ".$TablePrincipale." WHERE Suppr=0 AND Libelle='".addslashes($_POST['Libelle'])."' AND Id_Plateforme=".$_POST['Id_Plateforme']);
		if(mysqli_num_rows($Result)==0)
		{
		    $RequeteInsertUpdate="
				INSERT INTO "
					.$TablePrincipale."
				(
					Libelle,
					Id_Plateforme
				)
				VALUES
				(
					'".addslashes($_POST['Libelle'])."',".
					$_POST['Id_Plateforme']."
				);";
		}
	}
	elseif($_POST['Mode']=="Modif")
	{
	    $Result=mysqli_query($bdd,"SELECT Id FROM ".$TablePrincipale." WHERE Suppr=0 AND Libelle='".addslashes($_POST['Libelle'])."' AND Id_Plateforme=".$_POST['Id_Plateforme']." AND Id!=".$_POST['Id']);
		if(mysqli_num_rows($Result)==0)
		{
		    $RequeteInsertUpdate="
				UPDATE "
					.$TablePrincipale."
				SET
					Libelle='".addslashes($_POST['Libelle'])."',
					Id_Plateforme=".$_POST['Id_Plateforme']."
				WHERE
					Id='".$_POST['Id']."';";
		}
	}
	
	if($RequeteInsertUpdate != "")
	{
	    $ResultInsertUpdate=mysqli_query($bdd,$RequeteInsertUpdate);
		if($_POST['Mode']=="Ajout"){$IdCaisseType=mysqli_insert_id($bdd);}
		else
		{
			$IdCaisseType=$_POST['Id'];
			$ResultSupprContenuDepart=mysqli_query($bdd,"UPDATE tools_caissetype_contenu SET Suppr=1 WHERE Id_CaisseType=".$IdCaisseType);
		}
		
		if(isset($_POST['ModeleMateriel_ValeurSelection']))
		{
			if($_POST['ModeleMateriel_ValeurSelection'] != "")
			{
				$RequeteAjoutNouveauContenu="INSERT INTO tools_caissetype_contenu (Id_CaisseType, Id_ModeleMateriel, Quantite) VALUES ";
				
				$Tableau_ModeleMateriel_ValeurSelection = explode("|",$_POST['ModeleMateriel_ValeurSelection']);
				$Tableau_ModeleMateriel_QuantiteSelection = explode("|",$_POST['ModeleMateriel_QuantiteSelection']);
				
				for($i=0;$i<sizeof($Tableau_ModeleMateriel_ValeurSelection);$i++)
				{
					if($i>0){$RequeteAjoutNouveauContenu.=",";}
					$RequeteAjoutNouveauContenu.="('".$IdCaisseType."','".$Tableau_ModeleMateriel_ValeurSelection[$i]."','".$Tableau_ModeleMateriel_QuantiteSelection[$i]."')";
				}
				$ResultAjoutNouveauContenu=mysqli_query($bdd,$RequeteAjoutNouveauContenu);
			}
		}
		
		echo "<script>FermerEtRecharger('CaisseType');</script>";
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
					Libelle,
					Id_Plateforme
				FROM "
					.$TablePrincipale."
				WHERE
					Id='".$_GET['Id']."';";
			$Result=mysqli_query($bdd,$Requete);
			$Row=mysqli_fetch_array($Result);
		}
?>
		<form id="formulaire" method="POST" action="" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $Row['Id'];}?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?></td>
				<td>
				<?php $Id_PrestaPole="0_0" ?>
					<select name="Id_Plateforme" id="Id_Plateforme" style="width:200px">
						<?php
							$Id_Plateforme=$_SESSION['FiltreToolsSuivi_Plateforme'];

							$requetePlat="SELECT Id, Libelle
								FROM new_competences_plateforme
								WHERE Id NOT IN (11,14)
								ORDER BY Libelle";

							$resultsPlat=mysqli_query($bdd,$requetePlat);
							while($rowPlat=mysqli_fetch_array($resultsPlat))
							{
								$selected="";
								if($Modif)
								{
									if($rowPlat['Id']==$Row['Id_Plateforme']){$selected="selected";}
								}
								else
								{
									if($Id_Plateforme==0){$Id_Plateforme=$rowPlat['Id'];}
									if($Id_Plateforme==$rowPlat['Id']){$selected="selected";}
								}
								echo "<option value='".$rowPlat['Id']."' ".$selected.">";
								echo str_replace("'"," ",stripslashes($rowPlat['Libelle']))."</option>\n";
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td><input name="Libelle" size="60" type="text" value="<?php if($Modif){echo stripslashes($Row['Libelle']);}?>"></td>
			</tr>
			<tr>
				<td colspan="2">
					<table>
						<tr>
							<td width="50%" class="Libelle">
								Modèles de matériel<br>
								<select name="Id_ModeleMateriel" id="Id_ModeleMateriel" multiple size="28" onDblclick="AjouterAListe('Id_ModeleMateriel','ModeleMateriel_ListeSelection','ModeleMateriel');">
								<?php
								$RequeteModeleMateriel="SELECT Id, Libelle FROM tools_modelemateriel WHERE Suppr=0 ORDER BY Libelle";
								$ResultModeleMateriel=mysqli_query($bdd,$RequeteModeleMateriel);
								while($RowModeleMateriel=mysqli_fetch_array($ResultModeleMateriel))
								{
									echo "<option value='".$RowModeleMateriel['Id']."'>".str_replace("'"," ",$RowModeleMateriel['Libelle'])."</option>\n";
								}
								?>
								</select>
							</td>
							<td width="50%" class="Libelle">
								Modèles de matériel de la caisse 'type'<br>
								<select name="ModeleMateriel_ListeSelection[]" id="ModeleMateriel_ListeSelection" multiple size="30" onDblclick="RetirerDeListe('ModeleMateriel_ListeSelection','ModeleMateriel');">
								<?php
								$valeur="";
								$quantite="";
								if($_GET['Mode']=="Modif"){
									$RequeteModeleMaterielCaisseType="
										SELECT
											Id_ModeleMateriel,
											(SELECT Libelle FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) AS LIBELLE_MODELEMATERIEL,
											Quantite
										FROM
											tools_caissetype_contenu
										WHERE
											Id_CaisseType=".$_GET['Id']."
											AND Suppr=0
										ORDER BY
											LIBELLE_MODELEMATERIEL";
									$ResultModeleMaterielCaisseType=mysqli_query($bdd,$RequeteModeleMaterielCaisseType);
									$i=0;
									
									while($RowModeleMaterielCaisseType=mysqli_fetch_array($ResultModeleMaterielCaisseType))
									{
										echo "<option value='".$RowModeleMaterielCaisseType['Id_ModeleMateriel']."'>".str_replace("'"," ",$RowModeleMaterielCaisseType['LIBELLE_MODELEMATERIEL'])." _________ Qty:".$RowModeleMaterielCaisseType['Quantite']."</option>\n";
										echo "<script>Tableau_InputACompleter_ValeurSelection[".$i."]=".$RowModeleMaterielCaisseType['Id_ModeleMateriel'].";</script>";
										echo "<script>Tableau_InputACompleter_QuantiteSelection[".$i."]=".$RowModeleMaterielCaisseType['Quantite'].";</script>";
										if($valeur<>""){
											$valeur.="|";
											$quantite.="|";
										}
										$valeur.=$RowModeleMaterielCaisseType['Id_ModeleMateriel'];
										$quantite.=$RowModeleMaterielCaisseType['Quantite'];
										$i++;
									}
								}
								?>
								</select>
								<input type="hidden" name="ModeleMateriel_ValeurSelection" id="ModeleMateriel_ValeurSelection" value="<?php echo $valeur;?>">
								<input type="hidden" name="ModeleMateriel_QuantiteSelection" id="ModeleMateriel_QuantiteSelection" value="<?php echo $quantite;?>">
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
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
		$ResultCaisseType=mysqli_query($bdd,"UPDATE ".$TablePrincipale." SET Suppr=1 WHERE Id=".$_GET['Id']);
		$ResultCaisseTypeContenu=mysqli_query($bdd,"UPDATE ".$TablePrincipale."_contenu SET Suppr=1 WHERE Id_CaisseType=".$_GET['Id']);
		echo "<script>FermerEtRecharger('CaisseType');</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($Result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
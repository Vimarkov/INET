<html>
<head>
	<title>Compétences - Pôle</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps()
		{
			if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
			else{return true;}
		}
			
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require_once("../Fonctions.php");

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_pole WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Id_Prestation='".$POST['Id_Prestation']."'");
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"INSERT INTO new_competences_pole (Id_Prestation,Libelle,Actif) VALUES (".$_POST['Id_Prestation'].",'".addslashes($_POST['Libelle'])."',".$_POST['actif'].")");
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Ce libellé existe déjà.<br>Vous devez recommencer l'opération.</font>";}
	}
	elseif($_POST['Mode']=="Modif")
	{
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_poste WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Id_Prestation='".$POST['Id_Prestation']."' AND Id!=".$_POST['Id']);
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"UPDATE new_competences_pole SET Id_Prestation=".$_POST['Id_Prestation'].", Libelle='".addslashes($_POST['Libelle'])."', Actif=".$_POST['actif']." WHERE Id=".$_POST['Id']);
			
			$result2=mysqli_query($bdd,"DELETE FROM new_competences_pole_qualification WHERE Id_Pole=".$_POST['Id']);
			
			//Traitement des qualifications cochées
			if(isset($_POST["Qualif"]))
			{
				$Qualif=$_POST["Qualif"];
				for($i=0;$i<=sizeof($Qualif);$i++)
				{
					if(isset($Qualif[$i]))
					{
						$result4=mysqli_query($bdd,"INSERT INTO new_competences_pole_qualification (Id_Pole, Id_Qualification) VALUES (".$_POST['Id'].",".$Qualif[$i].")");
					}
				}
			}
			
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Ce libellé existe déjà.<br>Vous devez recommencer l'opération.</font>";}
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT * FROM new_competences_pole WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Pole.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="Modif"){echo $row['Id'];}?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}?> : </td>
				<td>
					<select name="Id_Prestation">
					<?php
						$requete2="SELECT new_competences_prestation.Id, new_competences_prestation.Libelle";
						$requete2.=" FROM new_competences_prestation, new_competences_plateforme";
						$requete2.=" WHERE new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id";
						$requete2.=" AND new_competences_plateforme.Id IN (SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne'].")";
						$requete2.=" ORDER BY new_competences_prestation.Libelle ASC";
						$result2=mysqli_query($bdd,$requete2);
						while($row2=mysqli_fetch_array($result2))
						{
							echo "<option value='".$row2[0]."'";
							if($_GET['Mode']=="Modif"){if($row['Id_Prestation']==$row2[0]){echo " selected";}}
							echo ">".$row2[1]."</option>";
						}
					?>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td><input name="Libelle" size="80" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Libelle'];}?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($LangueAffichage=="FR"){echo "Actif";}else{echo "Active";}?> : </td>
				<td>
					<select name="actif">
						<option value="0" <?php if($_GET['Mode']=="Modif"){if($row['Actif']==0){echo " selected";}}?>>Oui</option>
						<option value="1" <?php if($_GET['Mode']=="Modif"){if($row['Actif']==1){echo " selected";}}?>>Non</option>
					</select>
				</td>
			</tr>
			<?php if($_GET['Mode']=="Modif"){ ?>
			<tr class="TitreColsUsers">
				<td colspan="2" class="Libelle"><?php if($LangueAffichage=="FR"){echo "COMPÉTENCES À AFFICHER DANS LE TABLEAU DES COMPÉTENCES ALLÉGÉ";}else{echo "SKILLS TO DISPLAY IN THE LIGHT SKILLS TABLE";}?> : </td>
			</tr>
			<tr valign="top">
				<td colspan="2">
					<table class="TableCompetences">
					<?php
						$result=mysqli_query($bdd,"SELECT new_competences_qualification.* FROM new_competences_prestation_qualification, new_competences_qualification, new_competences_categorie_qualification WHERE new_competences_prestation_qualification.Id_Prestation=".$row['Id_Prestation']." AND new_competences_prestation_qualification.Id_Qualification=new_competences_qualification.Id AND new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id ORDER BY new_competences_categorie_qualification.Libelle ASC, new_competences_qualification.Libelle ASC");
						$nbenreg=mysqli_num_rows($result);
						if($nbenreg>0)
						{
					?>
						<tr>
							<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
							<td colspan="2" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Catégorie qualification";}else{echo "Qualification group";}?></td>
						</tr>
						<?php
							$Couleur="#EEEEEE";
							$Categorie=0;
							while($row=mysqli_fetch_array($result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
								$result2=mysqli_query($bdd,"SELECT Libelle,Id FROM new_competences_categorie_qualification WHERE Id=".$row['Id_Categorie_Qualification']);
								$row2=mysqli_fetch_array($result2);
								if($Categorie!=$row2['Libelle']){echo "<tr height='1' bgcolor='#66AACC'><td colspan='4'></td></tr>";}
								$Categorie=$row2['Libelle'];
								$QualifAppartientParrainage=0;
								if($_GET['Mode']=="Modif")
								{
									$result3=mysqli_query($bdd,"SELECT * FROM new_competences_pole_qualification WHERE Id_Pole=".$_GET["Id"]." AND Id_Qualification=".$row["Id"]);
									$QualifAppartientParrainage=mysqli_num_rows($result3);
								}
						?>
						<tr bgcolor="<?php echo $Couleur;?>">
							<td width="300"><?php echo $row['Libelle'];?></td>
							<td width="310"><?php echo $row2['Libelle'];?></td>
							<td><input type="checkbox" name="Qualif[]" value="<?php echo $row['Id'];?>" <?php if($_GET['Mode']=="Modif" && $QualifAppartientParrainage==1){echo "checked='checked'";}?>></td>
						</tr>
					<?php
							}
						}
					?>
					</table>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="2" align="center"><input class="Bouton" type="submit"
					<?php
						if($_GET['Mode']=="Modif"){
							if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
						}
						else{
							if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
						}
					?>
				></td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_personne_prestation WHERE Id_Pole=".$_GET['Id']);
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"DELETE FROM new_competences_pole WHERE Id=".$_GET['Id']);
			//Supprimé les personnes rattachées à ce pôle dans la hierarchie du personne
			$result=mysqli_query($bdd,"DELETE FROM new_competences_personne_poste_prestation WHERE Id_Pole=".$_GET['Id']);
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Vous ne pouvez pas supprimer ce pole car une ou plusieurs personne y sont rattachées.</font>";}
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
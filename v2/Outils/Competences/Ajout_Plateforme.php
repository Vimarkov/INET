<html>
<head>
	<title></title><meta name="robots" content="noindex">
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
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_plateforme WHERE Libelle='".$_POST['Libelle']."'");
		if(mysqli_num_rows($result)==0)
		{
			$req="INSERT INTO new_competences_plateforme (Libelle,Id_Division,Adresse,ARP_Id,Company,CompanyAdresse,AfficherBadgeStamp) 
				VALUES ('".addslashes($_POST['Libelle'])."',".$_POST['division'].",'".addslashes($_POST['adresse'])."','".addslashes($_POST['arp_id'])."'
				,'".addslashes($_POST['entreprise'])."','".addslashes($_POST['adresseEntreprise'])."',".$_POST['AfficherBadgeStamp'].")";
			$result=mysqli_query($bdd,$req);
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Ce libellé existe déjà.<br>Vous devez recommencer l'opération.</font>";}
	}
	elseif($_POST['Mode']=="Modif")
	{
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_plateforme WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Id!=".$_POST['Id']);
		if(mysqli_num_rows($result)==0)
		{
			$req="UPDATE new_competences_plateforme 
			SET 
				Libelle='".addslashes($_POST['Libelle'])."', 
				Id_Division=".$_POST['division'].",
				Adresse='".addslashes($_POST['adresse'])."',
				ARP_Id='".addslashes($_POST['arp_id'])."',
				Company='".addslashes($_POST['entreprise'])."',
				CompanyAdresse='".addslashes($_POST['adresseEntreprise'])."',
				AfficherBadgeStamp=".$_POST['AfficherBadgeStamp']."
			WHERE Id=".$_POST['Id'];
			$result=mysqli_query($bdd,$req);
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
			$result=mysqli_query($bdd,"SELECT * FROM new_competences_plateforme WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Plateforme.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="Modif"){echo $row['Id'];}?>">
		<table style="width:95%; align:center;" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td width="30%"><input name="Libelle" size="20" type="text" value="<?php if($_GET['Mode']=="Modif"){echo stripslashes($row['Libelle']);}?>"></td>

				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Division";}else{echo "Division";}?> : </td>
				<td width="50%">
					<select name="division">
						<option value="0"></option>
						<?php
						$result2=mysqli_query($bdd,"SELECT * FROM new_competences_division2 ORDER BY Libelle ASC");
						while($row2=mysqli_fetch_array($result2))
						{
							echo "<option value='".$row2['Id']."'";
							if($_GET['Mode']=="Modif"){if($row['Id_Division']==$row2['Id']){echo " selected";}}
							echo ">".$row2['Libelle']."</option>";
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td width="10px">
				
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "ARP ID";}else{echo "ARP ID";}?> : </td>
				<td>
					<input name="arp_id" size="20" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['ARP_Id'];}?>">
				</td>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Adresse";}else{echo "Address";}?> : </td>
				<td><input name="adresse" size="40" type="text" value="<?php if($_GET['Mode']=="Modif"){echo stripslashes($row['Adresse']);}?>"></td>
			</tr>
			<tr>
				<td width="10px">
				
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Entreprise";}else{echo "Company";}?> : </td>
				<td><input name="entreprise" size="20" type="text" value="<?php if($_GET['Mode']=="Modif"){echo stripslashes($row['Company']);}?>"></td>

				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Adresse entreprise";}else{echo "Company address";}?> : </td>
				<td>
					<input name="adresseEntreprise" size="40" type="text" value="<?php if($_GET['Mode']=="Modif"){echo stripslashes($row['CompanyAdresse']);}?>">
				</td>
			</tr>
			<tr>
				<td width="10px">
				
				</td>
			</tr>
			<tr>
				<td class="Libelle" colspan="3"><?php if($LangueAffichage=="FR"){echo "Afficher les stamps dans le tableau de compétences";}else{echo "Display stamps in the skills table";}?> : </td>
				<td>
					<select name="AfficherBadgeStamp">
						<option value="0" <?php if($_GET['Mode']<>"Modif"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";}?></option>
						<option value="1" <?php if($_GET['Mode']=="Modif"){if($row['AfficherBadgeStamp']==1){echo "selected";}} ?>><?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td width="10px">
				
				</td>
			</tr>
			<tr>
				<td colspan="4" align="center"><input class="Bouton" type="submit"
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
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_personne_plateforme WHERE Id_Plateforme=".$_GET['Id']);
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"DELETE FROM new_competences_plateforme WHERE Id=".$_GET['Id']);
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Vous ne pouvez pas supprimer cette unité d'exploitation car une ou plusieurs personne y est rattachées.</font>";}
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
<html>
<head>
	<title>Compétences - Qualification - Plateforme - Informations</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
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
		$requeteVerificationExiste="SELECT Id FROM new_competences_qualification_plateforme_infos WHERE Id_Qualification=".$_POST['Id_Qualification']." AND Id_Plateforme=".$_POST['Id_Plateforme']." AND Suppr=0";
		$requeteInsertUpdate="INSERT INTO new_competences_qualification_plateforme_infos (Id_Qualification, Id_Plateforme, Avion, Produit, Client, Doc_Applicable, Formation_Initiale, Formation_Specifique, Experience, Autre_Qualification)";
		$requeteInsertUpdate.=" VALUES (";
		$requeteInsertUpdate.=$_POST['Id_Qualification'];
		$requeteInsertUpdate.=",".$_POST['Id_Plateforme'];
		$requeteInsertUpdate.=",'".addslashes($_POST['Avion'])."'";
		$requeteInsertUpdate.=",'".addslashes($_POST['Produit'])."'";
		$requeteInsertUpdate.=",'".addslashes($_POST['Client'])."'";
		$requeteInsertUpdate.=",'".addslashes($_POST['Doc_Applicable'])."'";
		$requeteInsertUpdate.=",'".addslashes($_POST['Formation_Initiale'])."'";
		$requeteInsertUpdate.=",'".addslashes($_POST['Formation_Specifique'])."'";
		$requeteInsertUpdate.=",'".addslashes($_POST['Experience'])."'";
		$requeteInsertUpdate.=",'".addslashes($_POST['Autre_Qualification'])."'";
		$requeteInsertUpdate.=")";
	}
	elseif($_POST['Mode']=="Modif")
	{
		$requeteVerificationExiste="SELECT Id FROM new_competences_qualification_plateforme_infos WHERE Id_Qualification='".$_POST['Id_Qualification']."' AND Id_Plateforme=".$_POST['Id_Plateforme']." AND Suppr=0 AND Id!=".$_POST['Id'];
		$requeteInsertUpdate="UPDATE new_competences_qualification_plateforme_infos ";
		$requeteInsertUpdate.="SET ";
		$requeteInsertUpdate.="Avion='".addslashes($_POST['Avion'])."'";
		$requeteInsertUpdate.=",Produit='".addslashes($_POST['Produit'])."'";
		$requeteInsertUpdate.=",Client='".addslashes($_POST['Client'])."'";
		$requeteInsertUpdate.=",Doc_Applicable='".addslashes($_POST['Doc_Applicable'])."'";
		$requeteInsertUpdate.=",Formation_Initiale='".addslashes($_POST['Formation_Initiale'])."'";
		$requeteInsertUpdate.=",Formation_Specifique='".addslashes($_POST['Formation_Specifique'])."'";
		$requeteInsertUpdate.=",Experience='".addslashes($_POST['Experience'])."'";
		$requeteInsertUpdate.=",Autre_Qualification='".addslashes($_POST['Autre_Qualification'])."'";
		$requeteInsertUpdate.=" WHERE ";
		$requeteInsertUpdate.=" Id=".$_POST['Id'];
	}
	$resultVerificationExiste=mysqli_query($bdd,$requeteVerificationExiste);
	if(mysqli_num_rows($resultVerificationExiste)==0){
		$resultInsertUpdate=mysqli_query($bdd,$requeteInsertUpdate);
		echo "<script>FermerEtRecharger();</script>";
	}
	else{echo "<font class='Erreur'>Les informations pour cette plateforme sont déjà spécifiées pour cette qualification.<br>Vous devez recommencer l'opération.</font>";}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		$Modif=false;
		if($_GET['Mode']=="Modif"){$Modif=true;}
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT Id, Id_Qualification, Id_Plateforme, Avion, Produit, Client, Doc_Applicable, Formation_Initiale, Formation_Specifique, Experience, Autre_Qualification FROM new_competences_qualification_plateforme_infos WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Qualification_Plateforme_Infos.php">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $row['Id'];}?>">
		<input type="hidden" name="Id_Qualification" value="<?php echo $_GET['Id_Qualification'];?>">
		<table style="width:98%; height:95%; align:center;">
			<tr class="TitreColsUsers">
				<td><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
				<td>
					<select name="Id_Plateforme">
					<?php
					$resultPlateforme=mysqli_query($bdd,"SELECT Id,Libelle FROM new_competences_plateforme ORDER BY Libelle ASC");
					while($rowPlateforme=mysqli_fetch_array($resultPlateforme))
					{
						echo "<option value='".$rowPlateforme['Id']."'";
						if($Modif){if($row['Id_Plateforme']==$rowPlateforme['Id']){echo " selected";}}
						echo ">".$rowPlateforme['Libelle']."</option>";							 
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Avion : </td>
				<td><input name="Avion" size="70" value="<?php if($Modif){echo stripslashes($row['Avion']);}?>"></td>
			</tr>
			<tr>
				<td>Produit : </td>
				<td><input name="Produit" size="70" value="<?php if($Modif){echo stripslashes($row['Produit']);}?>"></td>
			</tr>
			<tr>
				<td>Client : </td>
				<td><input name="Client" size="70" value="<?php if($Modif){echo stripslashes($row['Client']);}?>"></td>
			</tr>
			<tr>
				<td>Document(s) applicable(s) : </td>
				<td><input name="Doc_Applicable" size="70" value="<?php if($Modif){echo stripslashes($row['Doc_Applicable']);}?>"></td>
			</tr>
			<tr>
				<td>Formation initiale : </td>
				<td><input name="Formation_Initiale" size="70" value="<?php if($Modif){echo stripslashes($row['Formation_Initiale']);}?>"></td>
			</tr>
			<tr>
				<td>Formation spécficique : </td>
				<td><input name="Formation_Specifique" size="70" value="<?php if($Modif){echo stripslashes($row['Formation_Specifique']);}?>"></td>
			</tr>
			<tr>
				<td>Expérience : </td>
				<td><input name="Experience" size="70" value="<?php if($Modif){echo stripslashes($row['Experience']);}?>"></td>
			</tr>
			<tr>
				<td>Autre(s) qualification(s) : </td>
				<td><input name="Autre_Qualification" size="70" value="<?php if($Modif){echo stripslashes($row['Autre_Qualification']);}?>"></td>
			</tr>
			<tr>
				<td colspan=2 align="center">
					<input class="Bouton" type="submit"
						<?php
							if($_GET['Mode']=="Modif"){
								if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
							}
							else{
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
		$result=mysqli_query($bdd,"UPDATE new_competences_qualification_plateforme_infos SET Suppr=1 WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger();</script>";

	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
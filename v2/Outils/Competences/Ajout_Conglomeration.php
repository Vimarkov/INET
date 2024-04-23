<html>
<head>
	<title>Compétences - Prestation</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps()
		{
			if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
			return true;
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
require_once("../Formation/Globales_Fonctions.php");
require_once("../Fonctions.php");

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
			$req="INSERT INTO new_competences_conglomeration
					(Libelle) 
				VALUES ('".addslashes($_POST['Libelle'])."')";
		    $result=mysqli_query($bdd,$req);
			$IdConglomeration=mysqli_insert_id($bdd);

			//Traitement des prestations cochées
			if(isset($_POST["Prestation"]))
			{
				$Prestation=$_POST["Prestation"];
				for($i=0;$i<sizeof($Prestation);$i++)
				{
					if(isset($Prestation[$i])){
						$result4=mysqli_query($bdd,"INSERT INTO new_competences_conglomeration_prestation (Id_Conglomeration,Id_Prestation) VALUES (".$IdConglomeration.",".$Prestation[$i].")");
					}
				}
			}
			echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="Modif")
	{

		$req="UPDATE 
			new_competences_conglomeration 
		SET 
			Libelle='".addslashes($_POST['Libelle'])."' 
		WHERE Id=".$_POST['Id'];
		$result=mysqli_query($bdd,$req);

		$result2=mysqli_query($bdd,"DELETE FROM new_competences_conglomeration_prestation WHERE Id_Conglomeration=".$_POST['Id']);

		$IdConglomeration=$_POST['Id'];

		//Traitement des prestations cochées
		if(isset($_POST["Prestation"]))
		{
			$Prestation=$_POST["Prestation"];
			for($i=0;$i<sizeof($Prestation);$i++)
			{
				if(isset($Prestation[$i])){
					$result4=mysqli_query($bdd,"INSERT INTO new_competences_conglomeration_prestation (Id_Conglomeration,Id_Prestation) VALUES (".$IdConglomeration.",".$Prestation[$i].")");
				}
			}
		}
		
		
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_conglomeration WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Conglomeration.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="Modif"){echo $row['Id'];}?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td><input name="Libelle" size="80" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Libelle'];}?>"></td>
			</tr>
			<tr valign="top">
				<td colspan="2">
					<table class="TableCompetences" style="width:100%;">
					<?php
						$result=mysqli_query($bdd,"SELECT Id, Libelle, (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme FROM new_competences_prestation WHERE Active=0 ORDER BY Plateforme, Libelle ASC");
						$nbenreg=mysqli_num_rows($result);
						if($nbenreg>0)
						{
					?>
						<tr>
							<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?></td>
							<td colspan="2" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
						</tr>
						<?php
							$Couleur="#EEEEEE";
							$Categorie=0;
							while($row=mysqli_fetch_array($result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
								$PrestaAppartient=0;
								if($_GET['Mode']=="Modif")
								{
									$result3=mysqli_query($bdd,"SELECT Id FROM new_competences_conglomeration_prestation WHERE Id_Conglomeration=".$_GET["Id"]." AND Id_Prestation=".$row["Id"]);
									$PrestaAppartient=mysqli_num_rows($result3);
								}
						?>
						<tr bgcolor="<?php echo $Couleur;?>">
							<td width="300"><?php echo $row['Plateforme'];?></td>
							<td width="300"><?php echo $row['Libelle'];?></td>
							<td><input type="checkbox" name="Prestation[]" value="<?php echo $row['Id'];?>" <?php if($_GET['Mode']=="Modif" && $PrestaAppartient==1){echo "checked='checked'";}?>></td>
						</tr>
					<?php
							}
						}
					?>
					</table>
				</td>
			</tr>
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
		$result=mysqli_query($bdd,"UPDATE new_competences_conglomeration SET Suppr=1 WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger();</script>";
	}
}
?>
	
</body>
</html>
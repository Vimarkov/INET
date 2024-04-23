<!DOCTYPE html>
<html>
<head>
	<title>Compétences - Profil personne - Diplôme</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Feuille.css" type="text/css">
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- Webforms2 -->
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script>
		function FermerEtRecharger(Page)
		{
			opener.location=Page;
			window.close();
		}
		
		Liste_Diplome = new Array();
		function Recharge_Liste_Diplome()
		{
			var sel="";
			sel ="<select size='1' name='Id_Diplome'>";
			for(var i=0;i<Liste_Diplome.length;i++)
			{
				if (Liste_Diplome[i][0]==document.getElementById('Id_Niveau').value)
				{
					sel= sel + "<option value="+Liste_Diplome[i][1];
					if(Liste_Diplome[i][1]==document.getElementById('Id_Diplome_Initial').value){sel = sel + " selected";}
					sel= sel + ">"+Liste_Diplome[i][2]+"</option>";
				}
			}
			sel =sel + "</select>";
			document.getElementById('Diplome').innerHTML=sel;
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");

if($_POST)
{
	if($_POST['Id_Diplome']!="")
	{
		if($_POST['Mode']=="Ajout"){$requete="INSERT INTO new_competences_personne_diplome (Id_Personne, Id_Diplome, Date, Id_Categorie) VALUES (".$_POST['Id_Personne'].",".$_POST['Id_Diplome'].",'".TrsfDate($_POST['Date'])."',".$_POST['Id_Categorie'].")";}
		else{$requete="UPDATE new_competences_personne_diplome SET Id_Diplome=".$_POST['Id_Diplome'].", Date='".TrsfDate($_POST['Date'])."', Id_Categorie=".$_POST['Id_Categorie']." WHERE Id=".$_POST['Id'];}
		$result=mysqli_query($bdd,$requete);
	}
	//echo "<script>FermerEtRecharger('Profil.php?Mode=Modif&Id_Personne=".$_POST['Id_Personne']."');</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Mode']=="Modif")
		{
			$Diplome=mysqli_query($bdd,"SELECT new_competences_personne_diplome.*, (SELECT Id_Niveau FROM new_competences_diplome WHERE Id=new_competences_personne_diplome.Id_Diplome) AS IdNiveau FROM new_competences_personne_diplome WHERE Id=".$_GET['Id']);
			$LigneDiplome=mysqli_fetch_array($Diplome);
		}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

	<form id="formulaire" method="POST" action="Ajout_Profil_Diplome.php" class="None">
	<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="Modif"){echo $_GET['Id'];} ?>">
	<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
	<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
	<input type="hidden" id="Id_Diplome_Initial" value="<?php if($_GET['Mode']=="Modif"){echo $LigneDiplome['Id_Diplome'];}else{echo "0";}?>">
	<table style="align:center;" class="TableCompetences">
		<tr class="TitreColsUsers">
			<td><?php if($LangueAffichage=="FR"){echo "Niveau";}else{echo "Grade";}?> : </td>
			<td>
				<select id="Id_Niveau" name="Id_Niveau" onchange="Recharge_Liste_Diplome();">
				<?php
				$result=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_niveau_diplome ORDER BY Libelle ASC");
				while($row=mysqli_fetch_array($result))
				{
					echo "<option value='".$row[0]."'";
					if($_GET['Mode']=="Modif"){if($row[0]==$LigneDiplome['IdNiveau']){echo " selected";}}
					echo ">".$row[1]."</option>";
				}
				?>
				</select>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td><?php if($LangueAffichage=="FR"){echo "Diplôme";}else{echo "Diploma";}?> : </td>
			<td>
				<div id="Diplome">
					<select size="1" name="Id_Diplome"></select>
				</div>
				<?php
				$requete_Diplome="SELECT Id_Niveau, Id, Libelle FROM new_competences_diplome ORDER BY Libelle ASC";
				$result_Diplome= mysqli_query($bdd,$requete_Diplome) or die ("Select impossible");
				$i=0;
				while ($row_Diplome=mysqli_fetch_row($result_Diplome))
				{
					 echo "<script>Liste_Diplome[".$i."] = new Array(".$row_Diplome[0].",".$row_Diplome[1].",'".addslashes($row_Diplome[2])."');</script>";
					 $i+=1;
				}
				?>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td><?php if($LangueAffichage=="FR"){echo "Catégorie";}else{echo "Group";}?> : </td>
			<td>
				<select name="Id_Categorie">
				<?php
				$result=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_categorie_diplome ORDER BY Libelle ASC");
				while($row=mysqli_fetch_array($result))
				{
					echo "<option value='".$row[0]."'";
					if($_GET['Mode']=="Modif"){if($LigneDiplome['Id_Categorie']==$row[0]){echo " selected";}}
					echo ">".$row[1]."</option>";
				}
				?>
				</select>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td>Date :</td>
			<td>
				<input type="date" name="Date" size="10" value="<?php if($_GET['Mode']=="Modif"){echo AfficheDateFR($LigneDiplome['Date']);} ?>">
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
	if($_GET['Mode']=="Modif"){mysqli_free_result($Diplome);}	// Libération des résultats}
}
	echo "<script>Recharge_Liste_Diplome();</script>";
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
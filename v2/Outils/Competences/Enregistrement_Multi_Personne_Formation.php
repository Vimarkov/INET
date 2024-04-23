<!DOCTYPE html>
<html>
<head>
	<title>Compétences - Enregistrement Multi Personne-Formation</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- Webforms2 -->
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");

if($_POST)
{
	if(isset($_POST['Formation']))
	{
		$Formation=$_POST['Formation'];
		for($i=0;$i<sizeof($Formation);$i++)
		{
			if(isset($Formation[$i]))
			{
				if(isset($_POST['Personne']))
				{
					$Personne=$_POST['Personne'];
					for($j=0;$j<sizeof($Personne);$j++)
					{
						if(isset($Personne[$j]))
						{
							$result=mysqli_query($bdd,"INSERT INTO new_competences_personne_formation (Id_Personne, Id_Formation, Date, Type) VALUES (".$Personne[$j].",".$Formation[$i].",'".TrsfDate($_POST['Date'])."','Professionnelle')");
						}
					}
				}
			}
		}
	}
	echo "Personnes et formations ajoutées<br>";
}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<form id="formulaire" method="POST" action="Enregistrement_Multi_Personne_Formation.php">
	<table style="width:95%; height:95%;">
		<tr valign="top">
			<!-- QUALIFICATIONS -->
			<td width="50%">
				<table style="width:100%; align:center;">
					<tr>
						<td>
							<table class="TableCompetences" style="width:100%; align:center;">
								<tr>
									<td colspan="2" align="center" class="TitreSousPageCompetencesPetit"><?php if($LangueAffichage=="FR"){echo "FORMATIONS";}else{echo "TRAININGS";}?></td>
								</tr>
							<?php
								$result=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_formation ORDER BY Libelle ASC");
								$nbenreg=mysqli_num_rows($result);
								if($nbenreg>0)
								{
									$Couleur="#EEEEEE";
									while($row=mysqli_fetch_array($result))
									{
										if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
										else{$Couleur="#EEEEEE";}
								?>
								<tr bgcolor="<?php echo $Couleur;?>">
									<td width="500"><?php echo $row['Libelle'];?></td>
									<td><input type="checkbox" name="Formation[]" value="<?php echo $row['Id'];?>"></td>
								</tr>
							<?php
									}
								}
							?>
							</table>
						</td>
					</tr>
				</table>
			</td>
			<!-- PERSONNES -->
			<td width="50%">
				<table style="width:100%; align:center;">
					<tr>
						<td>
							<table class="TableCompetences" style="width:100%; align:center;">
								<tr>
									<td colspan="2" align="center" class="TitreSousPageCompetencesPetit"><?php if($LangueAffichage=="FR"){echo "PERSONNES";}else{echo "PERSONS";}?></td>
								</tr>
							<?php
								$result=mysqli_query($bdd,"SELECT Id, Nom, Prenom FROM new_rh_etatcivil ORDER BY Nom ASC, Prenom ASC");
								$nbenreg=mysqli_num_rows($result);
								if($nbenreg>0)
								{
									$Couleur="#EEEEEE";
									while($row=mysqli_fetch_array($result))
									{
										if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
										else{$Couleur="#EEEEEE";}
							?>
								<tr bgcolor="<?php echo $Couleur;?>">
									<td width="500"><?php echo $row['Nom']." ".$row['Prenom'];?></td>
									<td><input type="checkbox" name="Personne[]" value="<?php echo $row['Id'];?>"></td>
								</tr>
							<?php
									}
								}
							?>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align="center">Date : <input type="date" name="Date" size="30" type="text" value=""></td>
			<td align="center"><input class="Bouton" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Ajouter";}else{echo "Add";}?>"></td>
		</tr>
	</table>
</form>
<?php
mysqli_free_result($result);	// Libération des résultats
mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
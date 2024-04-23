<!DOCTYPE html>
<html>
<head>
	<title>Compétences - Enregistrement Multi Personne-Qualification</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
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
	if(isset($_POST['Qualification']))
	{
		$Qualification=$_POST['Qualification'];
		for($i=0;$i<sizeof($Qualification);$i++)
		{
			if(isset($Qualification[$i]))
			{
				if(isset($_POST['Personne']))
				{
					$Personne=$_POST['Personne'];
					for($j=0;$j<sizeof($Personne);$j++)
					{
						if(isset($Personne[$j]))
						{
							$result=mysqli_query($bdd,"INSERT INTO new_competences_relation (Id_Personne, Type, Id_Qualification_Parrainage, Date_Debut, Evaluation, Date_Fin, Date_QCM, Resultat_QCM,Id_Personne_MAJ_Manuelle,Date_MAJ_Manuelle,ModifManuelle) VALUES (".$Personne[$j].",'Qualification',".$Qualification[$i].",'".TrsfDate($_POST['Date_Debut'])."','".$_POST['Evaluation']."','".TrsfDate($_POST['Date_Fin'])."','".TrsfDate($_POST['Date_QCM'])."','".$_POST['Resultat_QCM']."',".$_SESSION['Id_Personne'].",'".date('Y-m-d')."',1)");
						}
					}
				}
			}
		}
	}
	echo "Personnes et qualifications ajoutées<br>";
}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<form id="formulaire" method="POST" action="Enregistrement_Multi_Personne_Qualification.php">
	<table style="width:95%; height:95%;">
		<tr valign="top">
			<!-- QUALIFICATIONS -->
			<td width="50%">
				<table style="width:100%; align:center;">
					<tr>
						<td>
							<table class="TableCompetences" style="width:100%; align:center;">
								<tr>
									<td colspan="2" align="center" class="TitreSousPageCompetencesPetit">QUALIFICATIONS</td>
								</tr>
							<?php
								$result=mysqli_query($bdd,"SELECT new_competences_qualification.* FROM new_competences_qualification, new_competences_categorie_qualification WHERE new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id ORDER BY new_competences_categorie_qualification.Libelle ASC, new_competences_qualification.Libelle ASC");
								$nbenreg=mysqli_num_rows($result);
								if($nbenreg>0)
								{
									$Couleur="#EEEEEE";
									$Categorie="";
									while($row=mysqli_fetch_array($result))
									{
										if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
										else{$Couleur="#EEEEEE";}
										$result2=mysqli_query($bdd,"SELECT Libelle,Id FROM new_competences_categorie_qualification WHERE Id=".$row['Id_Categorie_Qualification']);
										$row2=mysqli_fetch_array($result2);
										if($Categorie!=$row2['Libelle'])
										{
											echo "<tr><td class='PetiteCategorieCompetence' colspan='2' align='center'><b>".$row2['Libelle']."</b></td></tr>";
										}
										$Categorie=$row2['Libelle'];
										$QualifAppartientParrainage=0;
								?>
								<tr bgcolor="<?php echo $Couleur;?>">
									<td width="500"><?php echo $row['Libelle'];?></td>
									<td><input type="checkbox" name="Qualification[]" value="<?php echo $row['Id'];?>"></td>
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
	</table>
	<table>
		<tr>
			<td align="center"><?php if($LangueAffichage=="FR"){echo "Date début";}else{echo "Start date";}?> : <input type="date" name="Date_Debut" size="30" type="text" value=""></td>
			<td align="center"><?php if($LangueAffichage=="FR"){echo "Date fin";}else{echo "End date";}?> : <input type="date" name="Date_Fin" size="30" type="text" value=""></td>
			<td align="center">Evaluation : 
				<select size="1" name="Evaluation">
				<option value="B">B</option>
				<option value="L">L</option>
				<option value="X">X</option>
				<option value="Q">Q</option>
				<option value="S">S</option>
				<option value="T">T</option>
				<option value="V">V</option>
				</select>
			</td>
			<td align="center"><?php if($LangueAffichage=="FR"){echo "Date QCM";}else{echo "MCQ date";}?> : <input type="date" name="Date_QCM" size="30" type="text" value=""></td>
			<td align="center"><?php if($LangueAffichage=="FR"){echo "Résultat QCM";}else{echo "MCQ score";}?> : <input type="text" name="Resultat_QCM" size="5" type="text" value=""></td>
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
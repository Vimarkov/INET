<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Compétences - Recherche qualification</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function FermerEtRecharger()
		{
			//Mettre dans un variable texte tous les éléments cochés séparés par des ';'
			var VariableTexte="";
			var monform = document.getElementById("formulaire");
			var myCB = monform.getElementsByTagName("input")
			for(var i=0; i<=myCB.length-1; i++)
			{
				if(myCB[i].type.toLowerCase()=="checkbox")
				{
					if(myCB[i].checked){VariableTexte+=myCB[i].value+";";}
				}
			}
			opener.location="Recherche.php?Qualifications="+VariableTexte;
			window.close();
		}
	</script>
</head>

<?php
require("../Connexioni.php");
require_once("../Fonctions.php");
?>

<form id="formulaire" method="POST" action="javascript:FermerEtRecharger();">
	<table style="width:100%; height:95%;">
		<tr>
			<td class="TitrePage"><?php if($LangueAffichage=="FR"){echo "Recherche qualification";}else{echo "Qualification search";}?></td>
		</tr>
		<tr>
			<td>
				<table class="TableCompetences" style="width:100%;">
					<tr>
						<td colspan="2" class="TitreSousPageCompetencesPetit"><?php if($LangueAffichage=="FR"){echo "QUALIFICATIONS";}else{echo "QUALIFICATION";}?></td>
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
								echo "<tr><td class='PetiteCategorieCompetence' colspan='2' align='center'>".$row2['Libelle']."</td></tr>";
							}
							$Categorie=$row2['Libelle'];
							$QualifAppartientParrainage=0;
					?>
					<tr bgcolor="<?php echo $Couleur;?>">
						<td><?php echo $row['Libelle'];?></td>
						<td><input type="checkbox" name="Qualification[]" value="<?php echo $row['Id'];?>"></td>
					</tr>
					<?php
						}
					}
					?>
				</table>
			</td>
		</tr>
		<tr>
			<td align="center"><input class="Bouton" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Valider";}else{echo "Validate";}?>"></td>
		</tr>
	</table>
</form>
</body>
</html>
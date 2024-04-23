<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Compétences - Recherche prestation</title><meta name="robots" content="noindex">
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
			opener.location="Recherche.php?Prestations="+VariableTexte;
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
			<td class="TitrePage"><?php if($LangueAffichage=="FR"){echo "Recherche prestation";}else{echo "Activity search";}?></td>
		</tr>
		<?php
			$result=mysqli_query($bdd,"SELECT new_competences_prestation.* FROM new_competences_prestation, new_competences_plateforme WHERE new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id ORDER BY new_competences_plateforme.Libelle ASC, new_competences_prestation.Libelle ASC");
			$nbenreg=mysqli_num_rows($result);
			if($nbenreg>0)
			{
		?>
		<tr>
			<td>
				<table class="TableCompetences" style="width:100%;">
					<tr>
						<td colspan="2" class="TitreSousPageCompetencesPetit"><?php if($LangueAffichage=="FR"){echo "PRESTATIONS";}else{echo "ACTIVITIES";}?></td>
					</tr>
					<?php
					$Couleur="#EEEEEE";
					$Plateforme="";
					while($row=mysqli_fetch_array($result))
					{
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
						$result2=mysqli_query($bdd,"SELECT Libelle,Id FROM new_competences_plateforme WHERE Id=".$row['Id_Plateforme']);
						$row2=mysqli_fetch_array($result2);
						if($Plateforme!=$row2['Libelle'])
						{
							echo "<tr><td class='PetiteCategorieCompetence' colspan='2' align='center'>".$row2['Libelle']."</td></tr>";
						}
						$Plateforme=$row2['Libelle'];
						$QualifAppartientParrainage=0;
					?>
					<tr bgcolor="<?php echo $Couleur;?>">
						<td><?php echo $row['Libelle'];?></td>
						<td><input type="checkbox" name="Prestation[]" value="<?php echo $row['Id'];?>"></td>
					</tr>
					<?php
					}
					?>
				</table>
			</td>
		</tr>
		<?php
			}
		?>
		<tr>
			<td align="center"><input class="Bouton" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Valider";}else{echo "Validate";}?>"></td>
		</tr>
	</table>
</form>
</body>
</html>
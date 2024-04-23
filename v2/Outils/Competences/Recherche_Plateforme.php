<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Compétences - Recherche plateforme</title><meta name="robots" content="noindex">
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
			opener.location="Recherche.php?Plateformes="+VariableTexte;
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
			<td class="TitrePage"><?php if($LangueAffichage=="FR"){echo "Recherche plateforme";}else{echo "Plateform search";}?></td>
		</tr>
		<?php
			$result=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_plateforme WHERE Inactif=0 ORDER BY Libelle ASC");
			$nbenreg=mysqli_num_rows($result);
			if($nbenreg>0)
			{
		?>
		<tr>
			<td>
				<table class="TableCompetences" style="width:100%;">
					<tr>
						<td colspan="2" class="TitreSousPageCompetencesPetit"><?php if($LangueAffichage=="FR"){echo "UNITES D'EXPLOITATIONS";}else{echo "OPERATING UNITS";}?></td>
					</tr>
				<?php
					$Couleur="#EEEEEE";
					while($row=mysqli_fetch_array($result))
					{
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
				?>
					<tr bgcolor="<?php echo $Couleur;?>">
						<td><?php echo $row['Libelle'];?></td>
						<td><input type="checkbox" name="Plateforme[]" value="<?php echo $row['Id'];?>"></td>
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
			<td align="center"><input class="Bouton" type="submit" value="Valider"></td>
		</tr>
	</table>
</form>
</body>
</html>
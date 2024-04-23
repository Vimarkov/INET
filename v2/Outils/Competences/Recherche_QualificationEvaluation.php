<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Compétences - Recherche qualification évaluation</title><meta name="robots" content="noindex">
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
			opener.location="Recherche.php?EvaluationQualifications="+VariableTexte;
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
			<td class="TitrePage"><?php if($LangueAffichage=="FR"){echo "Recherche évaluation";}else{echo "Evaluation search";}?></td>
		</tr>
		<tr>
			<td>
				<table class="TableCompetences" style="width:100%;">
					<tr>
						<td colspan="2" class="TitreSousPageCompetencesPetit"><?php if($LangueAffichage=="FR"){echo "EVALUATION QUALIFICATION";}else{echo "QUALIFICATION EVALUATION";}?></td>
					</tr>
					<?php
					$Couleur="#EEEEEE";
					$Tableau=array('B','L','X','Q','S','T','V');
					foreach($Tableau as $indice => $valeur)
					{
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
					?>
					<tr bgcolor="<?php echo $Couleur;?>">
						<td><?php echo $valeur;?></td>
						<td><input type="checkbox" name="EvaluationQualifications[]" value="<?php echo $valeur;?>"></td>
					</tr>
					<?php
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
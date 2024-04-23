<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Ajouter un besoin en formation</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script type="text/javascript">
		function VerifChamps(){
			for(y=0;y<document.getElementById('Id_Prestation_A_Verifier').length;y++){document.getElementById('Id_Prestation_A_Verifier').options[y].selected = true;}
		}
		
		function ToutCocher(){
			if(document.getElementById('check_Prestations').checked==true){
				var elements = document.getElementsByClassName('check');
				for (i=0; i<elements.length; i++){
				  elements[i].checked=true;
				}
			}
			else{
				var elements = document.getElementsByClassName('check');
				for (i=0; i<elements.length; i++){
				  elements[i].checked=false;
				}
			}
		}
		function FermerEtRecharger()
		{
			window.close();
		}
	</script>
</head>
<body>

<?php
if($_POST){
	if(isset($_POST['generer'])){
		foreach($_POST['Id_Prestation_A_Verifier'] as $value)
		{
			$tabPresta=explode("_",$value);
			$IdPrestation=$tabPresta[0];
			$IdPole=$tabPresta[1];
			Supprimer_BesoinsSansQualifIncorrects($IdPrestation,$IdPole,0);
			Supprimer_BesoinsIncorrects($IdPrestation,$IdPole,0);
		}

		//echo "<script>FermerEtRecharger();</script>";
	}
}
?>
<form id="formulaire" method="POST" action="SupprimerBesoinsInutilesPrestation.php" onSubmit="return VerifChamps();">
	<table class="TableCompetences" style="width:95%; height:95%; align:center;">
		<tr class="TitreColsUsers">
			<td valign="top" class="Libelle" width="100%" colspan="2">
				<?php if($LangueAffichage=="FR"){echo "Cocher les prestations concernées par la suppression des besoins inutiles";}else{echo "Check the site concerned by the removal of unnecessary needs";}?> : <br>
				<?php
				$Id_Plateforme=$_GET['Id_Plateforme'];
				//Personnes présentes par prestation à cette date
				$rqPrestation="SELECT Id AS Id_Prestation, 
									Id_Plateforme,
									Libelle,
									0 AS Id_Pole,
									'' AS Pole
									FROM new_competences_prestation 
									WHERE Id NOT IN (
										SELECT Id_Prestation
										FROM new_competences_pole
                                        WHERE Actif=0
									)
									AND new_competences_prestation.Active=0
									AND Id_Plateforme=".$Id_Plateforme."
									
									UNION
									
									SELECT Id_Prestation,
									new_competences_prestation.Id_Plateforme,
									new_competences_prestation.Libelle,
									new_competences_pole.Id AS Id_Pole,
									CONCAT(' - ',new_competences_pole.Libelle) AS Pole
									FROM new_competences_pole
									INNER JOIN new_competences_prestation
									ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
									AND new_competences_pole.Actif=0
									AND new_competences_prestation.Active=0
									AND new_competences_prestation.Id_Plateforme=".$Id_Plateforme."
									ORDER BY Libelle, Pole";
				$resultPresta=mysqli_query($bdd,$rqPrestation);
				$NbPresta=mysqli_num_rows($resultPresta);
				?>
				<div>
				<input type='checkbox' id="check_Prestations" name="check_Prestations" value="" onchange="ToutCocher()"><?php if($LangueAffichage=="FR"){echo "Tout cocher";}else{echo "Check all";}?>
				</div>
				<div id="listePers" style="width:100%;height:200px;overflow:auto;">
				<?php
					if($NbPresta>0)
					{
						while($rowPrestation=mysqli_fetch_array($resultPresta))
						{
							echo "<div>";
							echo "<input class='check' type='checkbox' name='Id_Prestation_A_Verifier[]' value='".$rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole']."'>".stripslashes($rowPrestation['Libelle'].$rowPrestation['Pole'])."&nbsp;";
							echo "</div>";
						}
					}
				?>
				</div>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td colspan="2" align="center">
				<input class="Bouton" name="generer" type="submit" <?php if($LangueAffichage=="FR"){echo "value='Supprimer les besoins inutiles'";}else{echo "value='Remove unnecessary needs'";}?>>
			</td>
		</tr>
	</table>
</form>
<?php
mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
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
			for(y=0;y<document.getElementById('Id_Personnes_A_Former').length;y++){document.getElementById('Id_Personnes_A_Former').options[y].selected = true;}
		}
		
		function ToutCocher(){
			if(document.getElementById('check_Personnes').checked==true){
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
		$tabPresta=explode("_",$_POST['Id_PrestationPole']);
		$IdPrestation=$tabPresta[0];
		$IdPole=$tabPresta[1];
		
		foreach($_POST['Id_Personnes_A_Former'] as $value)
		{
			Supprimer_BesoinsSansQualifIncorrects($IdPrestation,$IdPole,$value);
			Supprimer_BesoinsIncorrects($IdPrestation,$IdPole,$value);
		}

		echo "<script>FermerEtRecharger();</script>";
	}
}
?>
<form id="formulaire" method="POST" action="SupprimerBesoinsInutilesPersonne.php" onSubmit="return VerifChamps();">
	<table class="TableCompetences" style="width:95%; height:95%; align:center;">
		<input type="hidden" name="Id_PrestationPole" value="<?php echo $_GET['Id_PrestationId_Pole']; ?>">
		<tr class="TitreColsUsers">
			<td class="Libelle" colspan=2>
				<?php
					if($LangueAffichage=="FR"){echo "Prestation - Pôle : ";}
					else{echo "Activity - Pole : ";}
					
					$tabPresta=explode("_",$_GET['Id_PrestationId_Pole']);
					$IdPrestation=$tabPresta[0];
					$IdPole=$tabPresta[1];
					
					$req="SELECT Libelle FROM new_competences_prestation WHERE Id=".$IdPrestation;
					$result=mysqli_query($bdd,$req);
					$Nb=mysqli_num_rows($result);
					if($Nb>0)
					{
						$row=mysqli_fetch_array($result);
						echo $row['Libelle'];
					}
					
					$req="SELECT Libelle FROM new_competences_prestation WHERE Id=".$IdPole;
					$result=mysqli_query($bdd,$req);
					$Nb=mysqli_num_rows($result);
					if($Nb>0)
					{
						$row=mysqli_fetch_array($result);
						echo " - ".$row['Libelle'];
					}
				?>
			</td>
		</tr>
		
		<tr class="TitreColsUsers">
			<td valign="top" class="Libelle" width="100%" colspan="2">
				<?php if($LangueAffichage=="FR"){echo "Cocher les personnes concernées par la suppression des besoins inutiles";}else{echo "Check the people concerned by the removal of unnecessary needs";}?> : <br>
				<?php
				//Personnes présentes par prestation à cette date
				$reqPersonnes="
					SELECT
						DISTINCT new_competences_personne_prestation.Id_Personne,
						CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
					FROM
						new_competences_personne_prestation RIGHT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id = new_competences_personne_prestation.Id_Personne
					WHERE
						new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."' 
					AND new_competences_personne_prestation.Id_Prestation=".$IdPrestation." 
					AND new_competences_personne_prestation.Id_Pole=".$IdPole." 
					ORDER BY Personne ASC;";
				$resultPersonnes=mysqli_query($bdd,$reqPersonnes);
				$NbPersonne=mysqli_num_rows($resultPersonnes);
				?>
				<div>
				<input type='checkbox' id="check_Personnes" name="check_Personnes" value="" onchange="ToutCocher()"><?php if($LangueAffichage=="FR"){echo "Tout cocher";}else{echo "Check all";}?>
				</div>
				<div id="listePers" style="width:100%;height:200px;overflow:auto;">
				<?php
					if($NbPersonne>0)
					{
						while($rowPersonnes=mysqli_fetch_array($resultPersonnes))
						{
							echo "<div>";
							echo "<input class='check' type='checkbox' name='Id_Personnes_A_Former[]' value='".$rowPersonnes['Id_Personne']."'>".$rowPersonnes['Personne']."&nbsp;";
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
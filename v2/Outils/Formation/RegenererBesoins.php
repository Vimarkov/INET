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
			for(y=0;y<document.getElementById('Id_Formations').length;y++){document.getElementById('Id_Formations').options[y].selected = true;}
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
		function ToutCocherForm(){
			if(document.getElementById('check_Formations').checked==true){
				var elements = document.getElementsByClassName('checkForm');
				for (i=0; i<elements.length; i++){
				  elements[i].checked=true;
				}
			}
			else{
				var elements = document.getElementsByClassName('checkForm');
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
		
		foreach($_POST['Id_Formation'] as $formation)
		{
			foreach($_POST['Id_Personnes_A_Former'] as $value)
			{
				RecreerBesoinsManquantsPrestationFormation2($IdPrestation,$IdPole,$value,$formation);
			}
		}

		echo "<script>FermerEtRecharger();</script>";
	}
}
?>
<form id="formulaire" method="POST" action="RegenererBesoins.php" onSubmit="return VerifChamps();">
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
			<td valign="top" class="Libelle" width="50%">
				<?php if($LangueAffichage=="FR"){echo "Cocher les formations";}else{echo "Check the formations";}?> : <br>
				<?php
				$req="
				SELECT form_formation.Id,
				(SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS Organisme,
				form_formation.Id_TypeFormation,
				(SELECT Libelle FROM form_formation_langue_infos
				WHERE form_formation_langue_infos.Id_Langue=form_formation_plateforme_parametres.Id_Langue
				AND Id_Formation=form_formation.Id AND Suppr=0) AS Libelle,
				(SELECT LibelleRecyclage FROM form_formation_langue_infos
				WHERE form_formation_langue_infos.Id_Langue=form_formation_plateforme_parametres.Id_Langue
				AND Id_Formation=form_formation.Id AND Suppr=0) AS LibelleRecyclage,
				form_formation.Recyclage
				FROM form_formation_plateforme_parametres 
				LEFT JOIN form_formation
				ON form_formation_plateforme_parametres.Id_Formation=form_formation.Id
				WHERE form_formation_plateforme_parametres.Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$IdPrestation.")
				AND form_formation_plateforme_parametres.Suppr=0 AND form_formation.Suppr=0
				AND form_formation.Id IN (
					SELECT Id_Formation
					FROM form_prestation_metier_formation
					WHERE Suppr=0
					AND Id_Prestation=".$tabPresta[0]."
					AND Id_Pole=".$tabPresta[1]."
				)
				ORDER BY Libelle,LibelleRecyclage 
				";

				$resultGroupeFormation=mysqli_query($bdd,$req);
				$NbForm=mysqli_num_rows($resultGroupeFormation);
				?>
				<div>
				<input type='checkbox' id="check_Formations" name="check_Formations" value="" onchange="ToutCocherForm()" checked><?php if($LangueAffichage=="FR"){echo "Tout cocher";}else{echo "Check all";}?>
				</div>
				<div id="listeForm" style="width:100%;height:200px;overflow:auto;">
				<?php
					if($NbForm>0){
						while($rowForm=mysqli_fetch_array($resultGroupeFormation)){
							echo "<div>";

							$Organisme="";
							if($rowForm['Organisme']<>""){$Organisme=" (".$rowForm['Organisme'].")";}
							
							if($rowForm['Libelle']<>""){
								echo "<input class='checkForm' type='checkbox' name='Id_Formation[]' value='".$rowForm['Id']."' checked>".stripslashes($rowForm['Libelle']).$Organisme."&nbsp;";
							}
							echo "</div>";
						}
					}
				?>
				</div>
			</td>
			<td valign="top" class="Libelle" width="50%">
				<?php if($LangueAffichage=="FR"){echo "Cocher les personnes concernées par la regénération des besoins";}else{echo "Check the people concerned by the regeneration of needs";}?> : <br>
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
				<input class="Bouton" name="generer" type="submit" <?php if($LangueAffichage=="FR"){echo "value='Regénérer les besoins'";}else{echo "value='Regenerate needs'";}?>>
			</td>
		</tr>
	</table>
</form>
<?php
mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
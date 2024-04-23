<?php
require("../Menu.php");
?>
<script type="text/javascript">
	function Supprime(Id)
	{
		document.getElementById('IdSuppr').value=Id;
		document.FormulaireSuppression.submit;
	}
</script>
<?php
if($_POST)
{
	//Suppression de l'administrateur
	if($_POST["TypeDemande"]=="Suppr")
	{
		$Requete="DELETE FROM dossiers_admin WHERE Id=".$_POST['IdSuppr'].";";
	}
	//Ajout de l'administrateur
	else
	{
		$Requete="INSERT INTO dossiers_admin (Id_Dossier, Id_Personne) VALUES ('".$_POST['Id_Dossier']."','".$_POST['Id_Personne']."');";
	}
	
	$Result=mysqli_query($bdd,$Requete);
}
?>
	<!--LISTE DES ADMINISTRATEURS-->
	<form id="FormulaireSuppression" method="POST" action="">
		<input type="hidden" name="TypeDemande" value="Suppr">
		<input type="hidden" id="IdSuppr" name="IdSuppr" value="0">
		
		<table class="GeneralPage" style="width:100%; border-spacing:0;">
			<tr>
				<td class="TitrePage">Administration des référents par dossier</td>
			</tr>
		</table>
		
		<br>
		
		<table class="TableCompetences" style="width:95%; height:95%; border-spacing:0; align:center;">
			<tr>
				<td><b>Dossier Général</b></td>
				<td><b>Administrateur</b></td>
				<td><b>Suppr</b></td>
			</tr>
	<?php
		$Requete_ListeAdministrateurs="
			SELECT
				Id,
				Id_Dossier,
				Id_Personne,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=dossiers_admin.Id_Personne) AS PERSONNE_NOMPRENOM,
				(SELECT Libelle FROM dossiers_general WHERE Id=dossiers_admin.Id_Dossier) AS DOSSIERGENERAL_LIBELLE
			FROM
				dossiers_admin
			ORDER BY
				DOSSIERGENERAL_LIBELLE,
				PERSONNE_NOMPRENOM;";
		$Result_ListeAdministrateurs=mysqli_query($bdd,$Requete_ListeAdministrateurs);
		
		while($Row_ListeAdministrateurs=mysqli_fetch_array($Result_ListeAdministrateurs))
		{
			echo "
			<tr>
				<td>".$Row_ListeAdministrateurs['DOSSIERGENERAL_LIBELLE']."</td>
				<td>".$Row_ListeAdministrateurs['PERSONNE_NOMPRENOM']."</td>
				<td><input type='image' src='../Images/Suppression.gif' style='border:0;' alt='Supprimer' title='Supprimer' onclick='if(window.confirm(\"Sûr de vouloir supprimer ?\")){Supprime(\"".$Row_ListeAdministrateurs['Id']."\");}'></td>
			</tr>";
		}
	?>
		</table>
	</form>

	<!--AJOUT D'UN ADMINISTRATEUR-->
	<form id="FormulaireAjout" method="POST" action="">
		<input type="hidden" name="TypeDemande" value="Ajout">
		<table style="width:95%; height:95%; border-spacing:0; align:center;">
			<td>
				<select name=Id_Dossier>
					<?php
						$Requete_ListeDossiers="SELECT Id, Libelle FROM dossiers_general ORDER BY Libelle;";
						$Result_ListeDossiers=mysqli_query($bdd,$Requete_ListeDossiers);
						
						while($Row_ListeDossiers=mysqli_fetch_array($Result_ListeDossiers))
						{
							echo "<option value='".$Row_ListeDossiers['Id']."'>".$Row_ListeDossiers['Libelle']."</option>";
						}
					?>
				</select>
			</td>
			<td>
				<select name=Id_Personne>
					<?php
						$Requete_ListePersonnes="
							SELECT
								Id,
								CONCAT(Nom,' ',Prenom) AS NOMPRENOM
							FROM
								new_rh_etatcivil
							WHERE
								Login!=''
							ORDER BY
								NOMPRENOM;";
						$Result_ListePersonnes=mysqli_query($bdd,$Requete_ListePersonnes);
						
						while($Row_ListePersonnes=mysqli_fetch_array($Result_ListePersonnes))
						{
							echo "<option value='".$Row_ListePersonnes['Id']."'>".$Row_ListePersonnes['NOMPRENOM']."</option>";
						}
					?>
				</select>
			</td>
			<td>
				<input class="Bouton" type="submit" value="Ajouter">
			</td>
		</table>
	</form>
<?php
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
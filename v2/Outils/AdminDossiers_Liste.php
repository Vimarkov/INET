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
<!--LISTE DES ADMINISTRATEURS-->
<form id="FormulaireSuppression" method="POST" action="">
	<input type="hidden" name="TypeDemande" value="Suppr">
	<input type="hidden" id="IdSuppr" name="IdSuppr" value="0">

	<table class="GeneralPage" style="width:100%; border-spacing:0;">
		<tr>
			<td class="TitrePage">Liste des référents administrateurs par dossier</td>
		</tr>
	</table>
	
	<br>
	
	<table class="TableCompetences" style="width:95%; height:95%; border-spacing:2; align:center;">
		<tr>
			<td><b>Dossier Général</b></td>
			<td><b>Administrateur</b></td>
			<td><b>Email</b></td>
		</tr>
<?php
	$Requete_ListeAdministrateurs="
		SELECT
			Id,
			Id_Dossier,
			Id_Personne,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=dossiers_admin.Id_Personne) AS PERSONNE_NOMPRENOM,
			(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=dossiers_admin.Id_Personne) AS PERSONNE_EMAIL,
			(SELECT Libelle FROM dossiers_general WHERE Id=dossiers_admin.Id_Dossier) AS DOSSIERGENERAL_LIBELLE
		FROM
			dossiers_admin
		ORDER BY
			DOSSIERGENERAL_LIBELLE,
			PERSONNE_NOMPRENOM;";
	$Result_ListeAdministrateurs=mysqli_query($bdd,$Requete_ListeAdministrateurs);
	
	$Couleur="#EEEEEE";
	while($Row_ListeAdministrateurs=mysqli_fetch_array($Result_ListeAdministrateurs))
	{
		if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
		else{$Couleur="#EEEEEE";}
		
		echo "
		<tr bgcolor='".$Couleur."'>
			<td>".$Row_ListeAdministrateurs['DOSSIERGENERAL_LIBELLE']."</td>
			<td>".$Row_ListeAdministrateurs['PERSONNE_NOMPRENOM']."</td>
			<td>".$Row_ListeAdministrateurs['PERSONNE_EMAIL']."</td>
		</tr>";
	}
?>
	</table>
</form>
<?php
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
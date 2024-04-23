<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreAjoutProjet(Id_Personne)
		{var w=window.open("AjoutProjet.php?Type=A&Id_Projet=0&Id_Personne="+Id_Personne,"PageProjet","status=no,menubar=no,width=1100,height=520");
		w.focus();
		}
	function OuvreFenetreModifProjet(Id_Projet, Id_Personne)
		{var w=window.open("AjoutProjet.php?Type=M&Id_Projet="+Id_Projet+"&Id_Personne="+Id_Personne,"PageProjet","status=no,menubar=no,width=1100,height=500");
		w.focus();
		}
	function OuvreFenetreModifProjetServiceInfo(Id_Projet, Id_Personne)
		{var w=window.open("ModifProjet.php?Id_Projet="+Id_Projet+"&Id_Personne="+Id_Personne,"PageProjet","status=no,menubar=no,width=1100,height=550");
		w.focus();
		}
</script>
<?php
//Vérification des droits de lecture, écriture, administration
$DroitAjout=false;
$resultDroits=mysqli_query($bdd,"SELECT MIN(Id_Poste) FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne']);
$nbDroits=mysqli_num_rows($resultDroits);
$rowDroits=mysqli_fetch_array($resultDroits);
if($rowDroits[0]<3){$DroitAjout=true;}

$resultDroitsPresta=mysqli_query($bdd,"SELECT MIN(Id_Poste), Id_Prestation, Id_Pole FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne']." GROUP BY Id_Prestation, Id_Pole");
$nbDroitsPresta=mysqli_num_rows($resultDroitsPresta);

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));

$req = "SELECT new_projet_informatique.Id, new_projet_informatique.Id_Developpeur, new_projet_informatique.DateDemande, new_projet_informatique.DateBesoin, ";
$req .= "new_projet_informatique.Importance, new_projet_informatique.Technologie, new_projet_informatique.Description, ";
$req .= "new_projet_informatique.Type, new_projet_informatique.Etat, new_projet_informatique.Titre, new_projet_informatique.DateReponse, ";
$req .= "new_projet_informatique.DatePossible, new_projet_informatique.Commentaire, ";
$req .= "(SELECT new_rh_etatcivil.Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id = new_projet_informatique.Id_Demandeur) AS Nom, ";
$req .= "(SELECT new_rh_etatcivil.Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id = new_projet_informatique.Id_Demandeur) AS Prenom ";
$req .= "FROM new_projet_informatique ";
if ($_SESSION['Id_Personne']!= 406 && $_SESSION['Id_Personne'] != 1351){
	$req .= "WHERE new_projet_informatique.Id_Demandeur=".$_SESSION['Id_Personne']." ";
}
$req .= "ORDER BY new_projet_informatique.DateDemande DESC, new_projet_informatique.DatePossible ASC ";

$result=mysqli_query($bdd,$req);
$nbProjet=mysqli_num_rows($result);
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<form class="test" method="POST" action="Liste_ProjetInformatique.php">
<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#64a8f2;">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Informatique # Demande de projet</td>
					<td>
						<a style="text-decoration:none;" href='../../Demande_Projet_Informatique.pdf' target='_blank'>
							<img src='../../Images/aide.gif' border='0' alt='Aide' title='Aide'>
						</a>&nbsp;
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center">
			<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreAjoutProjet(<?php echo $_SESSION['Id_Personne']; ?>)">&nbsp;Nouvelle demande&nbsp;</a>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td>
			<table class="TableCompetences" style="width:100%; align:center;">
				<tr align="center">
					<?php
						if ($_SESSION['Id_Personne']== 406 || $_SESSION['Id_Personne'] == 1351){
							echo "<td class='EnTeteTableauCompetences' width='10'>Demandeur</td>";
						}
					?>
					<td class="EnTeteTableauCompetences" width="10">Date demande</td>
					<td class="EnTeteTableauCompetences" width="30">Besoin</td>
					<td class="EnTeteTableauCompetences" width="30">Technologie souhaitée</td>
					<td class="EnTeteTableauCompetences" width="10">Date souhaité</td>
					<td class="EnTeteTableauCompetences" width="30">Etat</td>
					<td class="EnTeteTableauCompetences" width="10">Date de livraison</td>
					<td class="EnTeteTableauCompetences" width="10"></td>
				</tr>
				<?php
					if ($nbProjet > 0){
						while($row=mysqli_fetch_array($result)){
							echo "<tr>";
							if ($_SESSION['Id_Personne']== 406 || $_SESSION['Id_Personne'] == 1351){
								echo "<td>".$row['Nom']." ".$row['Prenom']."</td>";
							}
							echo "<td>".$row['DateDemande']."</td>";
							echo "<td>".$row['Titre']."</td>";
							echo "<td>".stripslashes($row['Technologie'])."</td>";
							echo "<td>".$row['DateBesoin']."</td>";
							echo "<td>".$row['Etat']."</td>";
							if ($row['DatePossible'] > "0001-01-01"){
								echo "<td>".$row['DatePossible']."</td>";
							}
							else{
								echo "<td></td>";
							}
							echo "<td>";
							if ($_SESSION['Id_Personne']!= 406 && $_SESSION['Id_Personne'] != 1351){
								if ($row['Id_Developpeur'] == 0 || ($row['Id_Developpeur'] != 0 && $row['Etat']=='Cahier des charges insuffisant')){
									echo "<a href='javascript:OuvreFenetreModifProjet(".$row['Id'].",".$_SESSION['Id_Personne'].")' >";
									echo "<img src='../../Images/Modif.gif' border='0' alt='Modification' title='Modifier'>";
									echo "</a>&nbsp;";
								}
							}
							else{
								echo "<a href='javascript:OuvreFenetreModifProjetServiceInfo(".$row['Id'].",".$_SESSION['Id_Personne'].")' >";
								echo "<img src='../../Images/Modif.gif' border='0' alt='Modification' title='Modifier'>";
								echo "</a>&nbsp;";
							}
							echo "</td>";
							echo "</tr>";
						}
					}
				?>
			</table>
		</td>
	</tr>
</table>
</form>

<?php
	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
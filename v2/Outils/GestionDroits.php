<html>
<head>
	<title>Gestion des droits et des utilisateurs</title><meta name="robots" content="noindex">
	<link href="../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function OuvreFenetreUsers(Mode,Id)
			{window.open("Users.php?Mode="+Mode+"&Id="+Id,"User","status=no,menubar=no,width=700,height=200");}
		function OuvreFenetreDroits(Id)
			{window.open("Droits.php?Id="+Id,"Droits","status=no,scrollbars=yes,menubar=no,width=1050,height=750");}
	</script>
</head>
<body>

<?php
session_start();	//require("../VerifPage.php");
require("Connexioni.php");

?>
<table style="width:95%; align:center;">
	<tr>
		<td>
			<table class="TableCompetences" style="width:100%;">
				<tr>
					<td class="EnTeteTableauCompetences">Prénom Nom</td>
					<td class="EnTeteTableauCompetences">Login</td>
					<td class="EnTeteTableauCompetences">Mot de passe</td>
					<td class="EnTeteTableauCompetences">Email Pro</td>
					<td class="EnTeteTableauCompetences">Tél. Pro</td>
					<td class="EnTeteTableauCompetences">Mo.</td>
					<td class="EnTeteTableauCompetences">Su.</td>
					<td class="EnTeteTableauCompetences">Dr.</td>
				</tr>
			<?php
			$Couleur="#DDDDDD";
			$result=mysqli_query($bdd,"SELECT Id, Nom, Prenom, Login, Motdepasse, EmailPro, TelephoneProMobil FROM new_rh_etatcivil ORDER BY Nom ASC, Prenom ASC");
			while($row=mysqli_fetch_array($result))
			{
				if($Couleur=="#DDDDDD"){$Couleur="#FFFFFF";}
				else{$Couleur="#DDDDDD";}
			?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td class="PetitUsers"><?php echo $row['Nom']." ".$row['Prenom'];?></td>
					<td class="PetitUsers"><?php echo $row['Login'];?></td>
					<td class="PetitUsers"><?php echo $row['Motdepasse'];?></td>
					<td><a class="Modif" href="mailto:<?php echo $row['EmailPro'];?>"><?php echo $row['EmailPro'];?></a></td>
					<td class="PetitUsers"><?php echo $row['TelephoneProMobil'];?></td>
					<td align="center">
						<a href="javascript:OuvreFenetreUsers('Modif','<?php echo $row['Id'];?>');">
							<img src="../Images/Modif.gif" border="0" alt="Modification">
						</a>
					</td>
					<td align="center">
						<a href="javascript:OuvreFenetreUsers('Suppr','<?php echo $row['Id'];?>');">
							<img src="../Images/Suppression.gif" border="0" alt="Suppression">
						</a>
					</td>
					<td align="center">
						<a href="javascript:OuvreFenetreDroits('<?php echo $row['Login'];?>');">
							<img src="../Images/DroitsUtilisateurs.gif" border="0" alt="Droits de l'utilisateur">
						</a>
					</td>
				</tr>
			<?php
			}
			?>
			</table>
		</td>
	</tr>
</table>
<?php
	mysqli_free_result($result);	// Libération des résultats}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
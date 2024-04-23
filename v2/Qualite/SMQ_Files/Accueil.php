<html>
<head>
	<title>SMQ AAA</title>
	<link href="../../CSS/SMQ.css" rel="stylesheet" type="text/css">
</head>
<?php
	require("../../Outils/VerifPage.php");
	require("../../Outils/Connexioni.php");
	require("../../Outils/Formation/Globales_Fonctions.php");
?>
<body>
<table style="width:100%; height:95%;">
	<tr height="25%">
		<td style="font-size:40px;color:#00325F;">
			<img width="450px" src="../../Images/Logos/Logo Daher_posi.png"><br>
			Daher Industrial Services
		</td>
	</tr>
	<tr height="25%" >
		<td class="PageAccueil" style="font-size:100px;color:#00325F;">SMQ / QMS</td>
	</tr>
	<tr height="25%">
		<td>
			<table style="width:100%; height:100%;">
				<tr>
					<td width="33%" align="center"><input type="submit" class="Bouton" value="ISO/EN" onclick="location.href='ISO_EN.php';"></td>
					<?php
						$req = "SELECT Id_Plateforme 
							FROM new_competences_personne_plateforme 
							WHERE Id_Plateforme = 4 
							AND Id_Personne =".$_SESSION['Id_Personne']."";
						$result=mysqli_query($bdd,$req);
						$nbResult=mysqli_num_rows($result);

						if($nbResult>0 || DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite)))
						{
					?>
					<td width="33%"><input type="submit" class="Bouton" value="PART 21" onclick="location.href='Part21.php';"></td>
					<td width="33%"><input type="submit" class="Bouton" value="PART 145" onclick="location.href='Part145.php';"></td>
					<?php
						}
					?>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>

</html>

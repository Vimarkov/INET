<!DOCTYPE html>

<?php
require("../Connexioni.php");
require("../../v2/Outils/Fonctions.php");
?>

<html>
<head>
	<title>AAA</title><meta name="robots" content="noindex">
	<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Dosis'><link rel="stylesheet" href="../style.css">
</head>
<?php
require("../Connexioni.php");
require("../../v2/Outils/Formation/Globales_Fonctions.php");
require("../Menu.php");
?>
<?php
function Titre($Libelle,$Lien){
	global $HTTPServeur;
	echo "<tr><td>&nbsp;&bull;&nbsp;<a style=\"color:black;text-decoration:none;\" onmouseover=\"this.style.color='#0d8df1';\" onmouseout=\"this.style.color='#000000';\" href='".$HTTPServeur.$Lien."' target='General'>".$Libelle."</a></td></tr>\n";
}

function Widget($Libelle,$Lien,$Image,$Couleur,$InfosSupp=""){
	global $HTTPServeur;
	
	echo "
		<table style='border-spacing: 50px;display:inline-table;' >
			<tr>
				<td style=\"width:250px;height:250px;border-style:outset; border-radius: 15px;border-color:".$Couleur.";border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='".$Couleur."'>
					<a style=\"text-decoration:none;width:130px;border-spacing:0;font-size:2em;text-align:center;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" href='".$HTTPServeur.$Lien."' target='General'>
						<img width='60px' src='../../v2/Images/".$Image."' border='0' /><br>
						".$Libelle."
					</a>
				</td>
			</tr>";
	$css="";
	
	if($InfosSupp<>""){$css="bgcolor='".$Couleur."' width='250px'";}
	
	echo "
		<tr>
			<td ".$css.">
				".$InfosSupp."
			</tD>
		</tr>
	";
	echo "</table>";
}

function WidgetTDB($Libelle,$Image,$Couleur,$CouleurLogo,$nb,$Libelle2,$Lien){
	$couleurNombre="";
	if($nb<>"0" && $nb<>"0/0"){$couleurNombre="color:#de0006;";}
	echo "
		<table style='border-spacing: 10px;display:inline-table;' >
			<tr>
				<td style=\"width:190px;height:90px;border-style:outset;border-color:".$Couleur.";border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='".$Couleur."'>
					<table width='100%' height='100%'>
						<tr>
							<td style=\"width:35%;height:100%;text-align:center;color:black;valign:top;font-weight:bold;\" rowspan='2' bgcolor='".$CouleurLogo."'>
								<a style=\"text-decoration:none;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' target='General'>
								<img width='40px' src='../../v2/Images/".$Image."' border='0' />
								</a>
							</td>
							<td width='65%' style='font-size:32px;".$couleurNombre."'>
								".$nb."
							</td>
						</tr>
						<tr>
							<td>
								".$Libelle."
							</td>
						</tr>
						<tr>
							<td colspan='2' style='color:red;'>
								".$Libelle2."
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>";
}

?>

<table style="width:100%; border-spacing:0; align:center;" bgcolor="#e6e6e6">
	<tr bgcolor="#91dfff" >
		<td colspan="2" style="width:70px;height:30px;border-spacing:0;text-align:center;color:#00577c;valign:top;font-weight:bold;border-style:outset;">
			<span style="font-size:3em;">
			SODA v0.1 (Alpha)<br>
			</span>
			<span style="font-size:2.5em;">
			<?php if($LangueAffichage=="FR"){echo "Surveillance Opérationnel Digital Adaptative";}else{echo "Digital Adaptive Operational Monitoring";}?>
			</span>
		</td>
	</tr>
	<tr>
		<td height="5px"></td>
	</tr>
	<tr>
		<td width="100%">
			<table style="width:100%; border-spacing:0; align:center;">
				<tr>
					<td align="center" style="width:60%" valign="top">
						<table>
							<tr>
								<td>
								<?php
									if(mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_metier WHERE Id_Metier=85 AND Futur=0 AND Id_Personne=".$_SESSION['Id_Personne']))>0
									|| DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteDirectionOperation,$IdPosteChargeMissionOperation))
									|| DroitsFormationPrestation(array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme)))
									{
									if($LangueAffichage=="FR"){$libelle="Ajouter une surveillance";}else{$libelle="<br>Add monitoring";}
									Widget($libelle,"Surveillance/Ajouter_Surveillance.php","Formation/Evaluation.png","#e9b56b");
									}
									if($LangueAffichage=="FR"){$libelle="<br>Surveillance";}else{$libelle="<br>Monitoring";}
									Widget($libelle,"Surveillance/Liste_Surveillance.php","Formation/Jumelles.png","#42d3d6");
								?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5px"></td>
	</tr>
</table>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script><script  src="../script.js"></script>
</body>
</html>
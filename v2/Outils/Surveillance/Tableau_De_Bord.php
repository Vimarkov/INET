<?php
require("../../Menu.php");

function Titre($Libelle,$Lien)
{
	global $HTTPServeur;
	echo "<tr><td>&nbsp;&bull;&nbsp;<a style=\"color:black;text-decoration: none;\" onmouseover=\"this.style.color='#0d8df1';\" onmouseout=\"this.style.color='#000000';\" href='".$HTTPServeur.$Lien."' >".$Libelle."</a></td></tr>\n";
}

function Widget($Libelle,$Lien,$Image,$Couleur,$InfosSupp="")
{
	global $HTTPServeur;
	
	echo "
		<table style='border-spacing: 10px;display:inline-table;' >
			<tr>
				<td style=\"width:130px;height:110px;border-style:outset; border-radius: 15px;border-color:".$Couleur.";border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='".$Couleur."'>
					<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:center;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" href='".$HTTPServeur.$Lien."' >
						<img width='40px' src='../../Images/".$Image."' border='0' /><br>
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
								<a style=\"text-decoration:none;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >
								<img width='40px' src='../../Images/".$Image."' border='0' />
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

<table style="width:100%; border-spacing:0; align:center;">
	<tr bgcolor="#6EB4CD" >
		<td colspan="2" style="width:70px;height:30px;border-spacing:0;text-align:center;color:#00577c;valign:top;font-weight:bold;font-size:15px;">
			<?php if($LangueAffichage=="FR"){echo "GESTION DES SURVEILLANCES";}else{echo "MONITORING MANAGEMENT";}?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<br>
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:70%">
			<tr>
				<td class="Libelle" style="font-size:13px;">
					<span style="font-size:15px;">Nouvelle règle :</span> <br>
					<br><br>
					L'application Gestion des Surveillances est remplacée par SODA. <br>
					Le module SODA est accessible de tous via L'Extranet Daher industriel services DIS &#x2794; Gestion des Prestations &#x2794; SODA. <br>
					Gestion des Surveillances reste cependant toujours consultable. <br>
					Merci pour la prise en compte et application immédiate de cette information. <br>
					<br>
					Si vous rencontrez des difficultés d'accès ou souhaitez des information sur SODA, veuillez nous adresser vos questions / commentaires à l'adresse suivante <u><span style='color:#4653c2;'>SODA@aaa-aero.com</span></u> en mettant votre responsable en copie.<br>
					Bonnes surveillances à vous.<br>
					<br>
				</td>
			</tr>
		</table>
		</td>
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
									if($LangueAffichage=="FR"){$libelle="<br>Surveillance";}else{$libelle="<br>Monitoring";}
									Widget($libelle,"Outils/Surveillance/Liste_Surveillance.php","Formation/Jumelles.png","#42d3d6");

									if($LangueAffichage=="FR"){$libelle="Planning des surveillances";}else{$libelle="<br>Monitoring schedule";}
									Widget($libelle,"Outils/Surveillance/PlanningSurveillance.php","Formation/GestionSessions.png","#f3f414");

									if($LangueAffichage=="FR"){$libelle="<br>Questionnaire";}else{$libelle="<br>Questionnaire";}
									Widget($libelle,"Outils/Surveillance/Liste_Questionnaire.php","Formation/QCM.png","#68de2a");
								?>
								</td>
							</tr>
							<tr>
								<td>
								<?php
									if($LangueAffichage=="FR"){$libelle="<br>Graphiques,Indicateurs";}else{$libelle="<br>Charts, KPIs";}
									Widget($libelle,"Outils/Surveillance/Indicateurs.php","RH/Camembert.png","#292adf");
									
									if($LangueAffichage=="FR"){$libelle="<br>Avancée par prestation";}else{$libelle="<br>Progression by activity";}
									Widget($libelle,"Outils/Surveillance/AvanceePrestation.php","Formation/Graphique.png","#c2465e");
									
									if($LangueAffichage=="FR"){$libelle="Recherche - non conformités";}else{$libelle="Search - Nonconformities";}
									Widget($libelle,"Outils/Surveillance/RechercheNC.php","RH/Question.png","#b6b5d3");
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
</body>
</html>
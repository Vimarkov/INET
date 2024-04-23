<?php
require("../../Menu.php");
?>
<script>
	function mettreEnVu(Id){
		$.ajax({
			url : 'Ajax_MettreEnVu.php',
			data : 'Id='+Id,
			dataType : "html",
			async : false,
			//affichage de l'erreur en cas de problème
			error:function(msg, string){
				},
			success:function(data){
					window.location.reload();
				}
		});
	}
</script>
<?php

if($_GET){
	$Menu=$_GET['Menu'];
	if($_GET['Theme']=="Ressourceshumaines"){$_SESSION['FiltreOnboarding_Theme']="Ressources humaines";}
	elseif($_GET['Theme']=="Qualité"){$_SESSION['FiltreOnboarding_Theme']="Qualité";}
	elseif($_GET['Theme']=="Informatique"){$_SESSION['FiltreOnboarding_Theme']="Informatique";}
	elseif($_GET['Theme']=="SécuritéEnvironnement"){$_SESSION['FiltreOnboarding_Theme']="Sécurité et environnement";}
	elseif($_GET['Theme']=="BienvenueChezAAA"){$_SESSION['FiltreOnboarding_Theme']="Bienvenue chez AAA";}
	elseif($_GET['Theme']=="Achats"){$_SESSION['FiltreOnboarding_Theme']="Achats";}
	elseif($_GET['Theme']=="ExcellenceOperationnelle"){$_SESSION['FiltreOnboarding_Theme']="Excellence opérationnelle";}
	elseif($_GET['Theme']=="Innovation"){$_SESSION['FiltreOnboarding_Theme']="Innovation";}
	elseif($_GET['Theme']=="FormationInterne"){$_SESSION['FiltreOnboarding_Theme']="Formation interne";}
	elseif($_GET['Theme']=="VieQuotidienne"){$_SESSION['FiltreOnboarding_Theme']="Vie quotidienne";}
}
else{$Menu=$_POST['Menu'];}

if($_SESSION["Langue"]=="FR")
{
	$MoisLettre = array("Jan", "Fev", "Mar", "Avr", "Mai", "Jui", "Juil", "Aou", "Sep", "Oct", "Nov", "Dec");
	$MoisLettre2 = array("J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D");
	$MoisLettre3 = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
}
else
{
	$MoisLettre = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
	$MoisLettre2 = array("J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D");
	$MoisLettre3 = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
}

$DirFichier=$CheminOnBoarding;

function unNombreSinon0($leNombre){
	$nb=0;
	if($leNombre<>""){$nb=(double)$leNombre;}
	return $nb;
}

function SousTitre($Libelle){
	$couleurTexte="#000000";
	echo "<td style=\"width:33%;height:20px;border-spacing:0;text-align:center;color:".$couleurTexte.";valign:top;font-size:18px;font-weight:bold;\">".$Libelle."</td>\n";
}
?>
<form class="test" id="formulaire" enctype="multipart/form-data" action="TableauDeBord.php" method="post">
<table style="width:100%; border-spacing:0px;">
	<tr>
		<td style="display:none;"><input name="Langue" id="Langue" value="<?php echo $LangueAffichage;?>"></td>
	</tr>
	<tr bgcolor="#6EB4CD">
		<td style="width:5%;height:40px;border-spacing:0;text-align:center;color:#000000;valign:top;font-weight:bold;" onclick="window.stop();">
			<a style="text-decoration:none;width:70px;height:40px;border-spacing:0;text-align:center;color:#000000;valign:top;font-weight:bold;" onmouseover="this.style.color='#000000';" onmouseout="this.style.color='#000000';" href="<?php echo $_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Onboarding/TableauDeBord.php?Menu=1" ;?>" >
				<img width='25px' src='../../Images/home.png' border='0' alt='Return' title='Return'>
			</a>
		</td>
		<td style="width:95%;height:40px;border-spacing:0;text-align:left;color:#000000;valign:top;font-weight:bold;" onclick="window.stop();">
			<?php 
			echo "ESPACE ONBOARDING - ".$_SESSION['FiltreOnboarding_Theme']."";
			?>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td colspan="2">
			<table style="width:100%; border-spacing:0px;">
				<tr>
					<td width="50%" style="border-right:3px dashed #717171">
						<table style="width:100%; border-spacing:0px;">
							<?php
							$req="SELECT Id,Libelle,Document,TypeDocument,
								(SELECT COUNT(Id) FROM onboarding_contenu_lu WHERE onboarding_contenu.Id=onboarding_contenu_lu.Id_Contenu AND onboarding_contenu_lu.Id_Personne=".$_SESSION['Id_Personne'].") AS Lu
								FROM onboarding_contenu
								WHERE Suppr=0
								AND Id_Plateforme=0
								AND Rubrique='".$_SESSION['FiltreOnboarding_Theme']."'
								AND Valide=1
								ORDER BY DateCreation DESC;";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								$couleur="#ffffff";
								while($row=mysqli_fetch_array($result)){
									if($row['Document']<>""){
									?>
										<tr>
											<td height="60px">
												<?php 
												if($row['TypeDocument']=="A télécharger"){
													echo "<a class=\"Info\" href=\"".$DirFichier.$row['Document']."\" onclick=\"mettreEnVu(".$row['Id'].")\" target=\"_blank\">";
													echo "<img width='40px' src='../../Images/Telechargement2.png' border='0'>";
													echo "</a>";
												}
												else{
													echo "<a class=\"Info\" href=\"".$DirFichier.$row['Document']."\" onclick=\"mettreEnVu(".$row['Id'].")\" target=\"_blank\">";
													echo "<img width='45px' src='../../Images/Video.png' border='0'>";
													echo "</a>";
												}
												?>
												&nbsp;&nbsp;&nbsp;
												<?php
													echo "<a class=\"Info\" href=\"".$DirFichier.$row['Document']."\" onclick=\"mettreEnVu(".$row['Id'].")\" target=\"_blank\">";
													echo $row['Libelle'];
													echo "</a>";
													
													if($row['Lu']>0){
														echo "&nbsp;&nbsp;";
														echo "<img width='15px' src='../../Images/tick.png' border='0'>";
													}
												?>
											</td>
										</tr>
									<?php
									}
								}
							}
							?>
						</table>
					</td>
					<td width="50%"  valign="top">
						<table style="width:100%; border-spacing:0px;">
							<tr>
								<?php if($_SESSION['Langue']=="FR"){echo SousTitre("Spécialités locales (UER)");}else{echo SousTitre("Local specialties (EBU)");}?>
							</tr>
							<?php
							$req="SELECT Id,Libelle,Document,TypeDocument,
								(SELECT COUNT(Id) FROM onboarding_contenu_lu WHERE onboarding_contenu.Id=onboarding_contenu_lu.Id_Contenu AND onboarding_contenu_lu.Id_Personne=".$_SESSION['Id_Personne'].") AS Lu,
								(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS UER
								FROM onboarding_contenu
								WHERE Suppr=0
								AND Id_Plateforme IN (SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne=".$_SESSION['Id_Personne']." )
								AND Rubrique='".$_SESSION['FiltreOnboarding_Theme']."'
								AND Valide=1
								ORDER BY DateCreation DESC;";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								$couleur="#ffffff";
								while($row=mysqli_fetch_array($result)){
									if($row['Document']<>""){
									?>
										<tr>
											<td height="60px">
												&nbsp;&nbsp;
												<?php 
												if($row['TypeDocument']=="A télécharger"){
													echo "<a class=\"Info\" href=\"".$DirFichier.$row['Document']."\" onclick=\"mettreEnVu(".$row['Id'].")\" target=\"_blank\">";
													echo "<img width='40px' src='../../Images/Telechargement2.png' border='0'>";
													echo "</a>";
												}
												else{
													echo "<a class=\"Info\" href=\"".$DirFichier.$row['Document']."\" onclick=\"mettreEnVu(".$row['Id'].")\" target=\"_blank\">";
													echo "<img width='45px' src='../../Images/Video.png' border='0'>";
													echo "</a>";
												}
												?>
												&nbsp;&nbsp;&nbsp;
												<?php
													echo "<a class=\"Info\" href=\"".$DirFichier.$row['Document']."\" onclick=\"mettreEnVu(".$row['Id'].")\" target=\"_blank\">";
													echo $row['Libelle']." [".$row['UER']."] ";
													echo "</a>";
													
													if($row['Lu']>0){
														echo "&nbsp;&nbsp;";
														echo "<img width='15px' src='../../Images/tick.png' border='0'>";
													}
												?>
											</td>
										</tr>
									<?php
									}
								}
							}
							?>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
</table>
</form>
</body>
</html>
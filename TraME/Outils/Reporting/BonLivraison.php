<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../JS/date.js"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script language="javascript" src="BL.js"></script>
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>	
	<script type="text/javascript">
		$(function(){
			$(window).scroll(
				function () {//Au scroll dans la fenetre on d?clenche la fonction
					if ($(this).scrollTop() > 1) { //si on a d?fil? de plus de 150px du haut vers le bas
						$('#navigation').addClass("fixNavigation"); //on ajoute la classe "fixNavigation" ? <div id="navigation">
					} else {
						$('#navigation').removeClass("fixNavigation");//sinon on retire la classe "fixNavigation" ? <div id="navigation">
					}
				}
			);			 
		});
	</script>
</head>
<?php
require("../../Menu.php");
require("../Fonctions.php");

$_SESSION['Formulaire']="Reporting/BonLivraison.php";
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
$req="SELECT DateFacturation FROM trame_facturation WHERE Id_Prestation=".$_SESSION['Id_PrestationTR'];
$resultFactu=mysqli_query($bdd,$req);
$LigneFactu=mysqli_fetch_array($resultFactu);
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="BonLivraison.php">
	<tr style="display:none;"><td><input type="texte" id="listeWP" name="listeWP" value=""/></td></tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "DELIVERY NOTE";}else{echo "BON DE LIVRAISON";} ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="8"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr><td height="4"></td></tr>
		<tr>
			<td width="6%" class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Value Date ";}else{echo "Date de valeur ";}?></td>
			<td width="60%">
				<input type="date" id="dateFacturation" name="dateFacturation" size="10" value="<?php echo AfficheDateFR($LigneFactu['DateFacturation']);?>"/>
				<a style="text-decoration:none;" class="Bouton" href="javascript:ModifierDateFactu()">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";}?>&nbsp;</a>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
	</table>
	</td></tr>
	<tr><td height="8"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr><td height="4"></td></tr>
		<tr>
			<td width="3%" class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "From ";}else{echo "Du ";}?></td>
			<td width="5%"><input type="date" id="dateDebut" name="dateDebut" size="10" value=""/></td>
			<td width="3%" class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "to ";}else{echo "au ";}?></td>
			<td width="5%"><input type="date" id="dateFin" name="dateFin" size="10" value=""/></td>
			<td width="15%" class="Libelle"><input type="checkbox" id="wpSeparare" name="wpSeparare" /><?php if($_SESSION['Langue']=="EN"){echo "Separate workpackage ";}else{echo "Workpackage séparés ";}?></td>
			<td width="55%"><a class="bouton" style="text-decoration:none;" href="javascript:Excel_BL()"><img src='../../Images/excel.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Excel";}else{echo "Excel";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Excel";}else{echo "Excel";} ?>'></a></td>
		</tr>
		<tr><td height="4"></td></tr>
	</table>
	</td></tr>
	<tr><td height="8"></td></tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:98%;">
			<tr bgcolor="#00325F">
				<td class="EnTeteTableauCompetences" colspan="2"><?php if($_SESSION['Langue']=="EN"){echo "Workpackage";}else{echo "Workpackage";} ?></td>
			</tr>
			<?php
				$req="SELECT Id, Libelle FROM trame_wp WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Supprime=false ORDER BY Libelle";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					while($row=mysqli_fetch_array($result)){
						echo "<tr><td colspan='2'>";
						echo "<input type='checkbox' id='WP_".$row['Id']."' name='WP_".$row['Id']."' onclick='cocherTache(".$row['Id'].")' />";
						echo $row['Libelle']."&nbsp;&nbsp;<img id='PlusMoins_".$row['Id']."' src='../../Images/Plus.gif' onclick='javascript:Affiche_Masque(".$row['Id'].");'>";
						echo "</td></tr>";
						
						$reqT="SELECT trame_tache.Id, trame_tache.Libelle, ";
						$reqT.="CASE WHEN trame_tache_wp.Supprime=1 THEN 1 ";
						$reqT.="WHEN trame_tache.Supprime=1 THEN 1 ";
						$reqT.="ELSE 0 END AS Supprime ";
						$reqT.="FROM trame_tache_wp LEFT JOIN trame_tache ON trame_tache_wp.Id_Tache = trame_tache.Id ";
						$reqT.="WHERE trame_tache_wp.Id_WP=".$row['Id']." ORDER BY Supprime, Libelle ";
						$resultT=mysqli_query($bdd,$reqT);
						$nbResultaT=mysqli_num_rows($resultT);
						if ($nbResultaT>0){
							while($rowT=mysqli_fetch_array($resultT)){
								$old="";
								$couleur="";
								if($rowT['Supprime']==true){
									$old=" [OLD]";
									$couleur="bgcolor='#b5b6bd'";
								}
								echo "<tr class='WP_".$row['Id']."' style='display:none;'><td width='10%'></td><td width='90%' ".$couleur."><input type='checkbox' id='_WP_".$row['Id']."Tache_".$rowT['Id']."' name='_WP_".$row['Id']."Tache_".$rowT['Id']."'/>".stripslashes($rowT['Libelle']).$old."</td></tr>";
							}
						}
						
					}
				}
			?>
		</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
</form>
</table>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>

</body>
</html>
<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout(){
			var w=window.open("Ajout_WP.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=700,height=250");
			w.focus();
			}
		function OuvreFenetreModif(Id){
			var w=window.open("Ajout_WP.php?Mode=M&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=700,height=250");
			w.focus();
			}
		function OuvreFenetreSuppr(Id){
			if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
			var w=window.open("Ajout_WP.php?Mode=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,,width=60,height=40");
			w.focus();
			}
		}
	</script>
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

$_SESSION['Formulaire']="Parametre/WP.php";

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],5,1)=='1'){
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="WP.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "Workpackage list";}else{echo "Liste des workpackages";} ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="left">
			<table align="left" style="width:50%;">
				<tr>
				<td align="center">
				<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add a workpackage";}else{echo "Ajouter un workpackage";} ?>&nbsp;</a>
				</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="left" class="GeneralInfo" style="width:50%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="16%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Wording";}else{echo "Libellé";} ?></td>
				<td class="EnTeteTableauCompetences" width="12%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Start date";}else{echo "Date début";} ?></td>
				<td class="EnTeteTableauCompetences" width="12%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "End date";}else{echo "Date fin";} ?></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
			</tr>
			<?php
				$req="SELECT Id,Libelle,DateDebut,DateFin, Actif FROM trame_wp WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Supprime=false ORDER BY Libelle;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						if($row['Actif']==1){$couleurLigne="#a6a9aa";}
						else{$couleurLigne=$couleur;}
						?>
							<tr bgcolor="<?php echo $couleurLigne;?>">
								<td width="16%">&nbsp;<?php echo $row['Libelle'];?></td>
								<td width="12%">&nbsp;<?php echo AfficheDateFR($row['DateDebut']);?></td>
								<td width="12%">&nbsp;<?php echo AfficheDateFR($row['DateFin']);?></td>
								<td width="2%" align="center">
									<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>)">
									<img src='../../Images/Modif.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";} ?>'>
									</a>
								</td>
								<td width="2%" align="center">
								<a href="javascript:OuvreFenetreSuppr(<?php echo $row['Id']; ?>)">
								<img src='../../Images/Suppression.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>'>
								</a>
								</td>
							</tr>
						<?php
						if($couleur=="#ffffff"){$couleur="#E1E1D7";}
						else{$couleur="#ffffff";}
					}
				}
			?>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
</form>
</table>

<?php
}
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
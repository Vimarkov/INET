<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout(){
			var w=window.open("Ajout_Utilisateur.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=600,height=150");
			w.focus();
			}
		function OuvreFenetreSuppr(Id){
			if(window.confirm('Etes-vous sûr de vouloir supprimer les accès?')){
				var w=window.open("Ajout_Utilisateur.php?Mode=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=60,height=40");
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

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));

if(substr($_SESSION['DroitTR'],5,1)=='1'){

$_SESSION['Formulaire']="Administrateur/Liste_Utilisateur.php";
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Liste_Utilisateur.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "Administrator access";}else{echo "Accès utilisateurs";} ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center" colspan="6">
			<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add administrator";}else{echo "Ajouter un administrateur";} ?>&nbsp;</a>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:40%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="16%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Name";}else{echo "Personne";} ?></td>
				<td class="EnTeteTableauCompetences" width="10%" >Login</td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
			</tr>
			<?php
				$req="SELECT new_rh_etatcivil.Id,new_rh_etatcivil.Nom,new_rh_etatcivil.Prenom,new_rh_etatcivil.LoginTrame ";
				$req.="FROM trame_admin LEFT JOIN new_rh_etatcivil ON trame_admin.Id_Personne=new_rh_etatcivil.Id ";
				$req.="WHERE new_rh_etatcivil.LoginTrame<>'' ORDER BY Nom, Prenom;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						?>
						<tr bgcolor="<?php echo $couleur;?>">
							<td width="16%">&nbsp;<?php echo $row['Nom']." ".$row['Prenom'];?></td>
							<td width="10%">&nbsp;<?php echo $row['LoginTrame'];?></td>
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
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
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
		function OuvreFenetreModif(Id){
			var w=window.open("Ajout_Utilisateur.php?Mode=M&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=600,height=150");
			w.focus();
			}
		function OuvreFenetreSuppr(Id){
			if(window.confirm('Etes-vous sûr de vouloir supprimer les accès?')){
				var w=window.open("Ajout_Utilisateur.php?Mode=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=60,height=40");
				w.focus();
				}
			}
		function OuvreFenetreReini(Id){
			if(window.confirm('Etes-vous sûr de vouloir réinitialiser le mot de passe ?')){
				var w=window.open("Ajout_Utilisateur.php?Mode=R&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=60,height=40");
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

if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],5,1)=='1'){

$_SESSION['Formulaire']="Parametre/Liste_Utilisateur.php";
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Liste_Utilisateur.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "User access";}else{echo "Accès utilisateurs";} ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center" colspan="6">
			<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add user";}else{echo "Ajouter un utilisateur";} ?>&nbsp;</a>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:100%;">
			<tr>
				<td class="EnTeteTableauCompetences" width="16%" >&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Name";}else{echo "Personne";} ?></td>
				<td class="EnTeteTableauCompetences" width="10%" >NG/ST</td>
				<td class="EnTeteTableauCompetences" width="10%" >Login</td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;"><?php if($_SESSION['Langue']=="EN"){echo "Collaborator";}else{echo "Collaborateur";} ?></td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;"><?php if($_SESSION['Langue']=="EN"){echo "Controller";}else{echo "Contrôleur";} ?></td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;"><?php if($_SESSION['Langue']=="EN"){echo "Team Leader / Leader";}else{echo "Chef d'équipe/Leader";} ?></td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;"><?php if($_SESSION['Langue']=="EN"){echo "Site Manager";}else{echo "Responsable prestation";} ?></td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;"><?php if($_SESSION['Langue']=="EN"){echo "Project manager";}else{echo "Resp/Coord projet";} ?></td>
				<td class="EnTeteTableauCompetences" width="16%"style="text-align:center;" >Email</td>
				<td class="EnTeteTableauCompetences" width="8%"style="text-align:center;" >Plateforme</td>
				<td class="EnTeteTableauCompetences" width="12%"style="text-align:center;" ><?php if($_SESSION['Langue']=="EN"){echo "Site";}else{echo "Prestation";} ?></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
			</tr>
			<?php
				$req="SELECT trame_acces.Droit,new_rh_etatcivil.Id,new_rh_etatcivil.Nom,new_rh_etatcivil.Prenom,new_rh_etatcivil.Matricule,new_rh_etatcivil.LoginTrame, ";
				$req.="new_rh_etatcivil.EmailPro FROM trame_acces LEFT JOIN new_rh_etatcivil ON trame_acces.Id_Personne=new_rh_etatcivil.Id ";
				$req.="WHERE trame_acces.Id_Prestation=".$_SESSION['Id_PrestationTR']." AND new_rh_etatcivil.LoginTrame<>'' ORDER BY Nom, Prenom;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						$P="";
						$R="";
						$C="";
						$RP="";
						$CE="";
						if(substr($row['Droit'],0,1)=='1'){$P="X";}
						if(substr($row['Droit'],1,1)=='1'){$R="X";}
						if(substr($row['Droit'],2,1)=='1'){$C="X";}
						if(substr($row['Droit'],3,1)=='1'){$RP="X";}
						if(substr($row['Droit'],4,1)=='1'){$CE="X";}
						
						$prestation="";
						$reqA="SELECT DISTINCT trame_prestation.Id, trame_prestation.Libelle ";
						$reqA.="FROM trame_acces LEFT JOIN trame_prestation ON trame_acces.Id_Prestation=trame_prestation.Id WHERE trame_acces.Id_Personne=".$row['Id'];
						$resultA=mysqli_query($bdd,$reqA);
						$nbResultaA=mysqli_num_rows($resultA);
						if ($nbResultaA>0){
							while($rowA=mysqli_fetch_array($resultA)){
								if($rowA['Id']==$_SESSION['Id_PrestationTR']){$prestation.="<B>".$rowA['Libelle']."</B><br>";}
								else{$prestation.=$rowA['Libelle']."<br>";}
							}
							if($prestation<>""){
								$prestation=substr($prestation,0,-4);
							}
						}
						
						//Plateforme
						$PLATEFORME="";
						$requete_plateforme="SELECT DISTINCT new_competences_plateforme.Libelle, new_competences_plateforme.Id 
											FROM new_competences_plateforme, new_competences_personne_plateforme
											WHERE new_competences_personne_plateforme.Id_Personne=".$row['Id']." 
											AND new_competences_plateforme.Id=new_competences_personne_plateforme.Id_Plateforme 
											ORDER BY new_competences_plateforme.Libelle ASC";
						$result_plateforme=mysqli_query($bdd,$requete_plateforme);
						$nbenreg_plateforme=mysqli_num_rows($result_plateforme);
						if($nbenreg_plateforme>0)
						{
							while($row_plateforme=mysqli_fetch_array($result_plateforme))
							{
								if($PLATEFORME==""){$PLATEFORME=$row_plateforme[0];}else{$PLATEFORME.="<br>".$row_plateforme[0];}
							}
						}
						?>
						<tr bgcolor="<?php echo $couleur;?>">
							<td width="16%">&nbsp;<?php echo $row['Nom']." ".$row['Prenom'];?></td>
							<td width="10%">&nbsp;<?php echo $row['Matricule'];?></td>
							<td width="10%">&nbsp;<?php echo $row['LoginTrame'];?></td>
							<td width="10%" align="center"><?php echo $P;?></td>
							<td width="10%" align="center"><?php echo $C;?></td>
							<td width="10%" align="center"><?php echo $CE;?></td>
							<td width="10%" align="center"><?php echo $R;?></td>
							<td width="10%" align="center"><?php echo $RP;?></td>
							<td width="16%">&nbsp;<?php echo $row['EmailPro'];?></td>
							<td width="8%">&nbsp;<?php echo $PLATEFORME;?></td>
							<td width="12%"><?php echo $prestation;?></td>
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
							</td>
							<td width="2%" align="center">
							<a href="javascript:OuvreFenetreReini(<?php echo $row['Id']; ?>)">
							<img src='../../Images/Reinitilisation.png' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Reinitialise";}else{echo "Réinitialiser";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Reinitialise";}else{echo "Réinitialiser";} ?>'>
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
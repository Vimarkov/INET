<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout(){
			var w=window.open("Ajout_Tache.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=1000,height=530");
			w.focus();
			}
		function OuvreFenetreModif(Id){
			var w=window.open("Ajout_Tache.php?Mode=M&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=1000,height=530");
			w.focus();
			}
		function OuvreFenetreSuppr(Id){
			if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
			var w=window.open("Ajout_Tache.php?Mode=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,,width=60,height=40");
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

$_SESSION['Formulaire']="Production/Tache.php";

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
if($_POST){
	$_SESSION['TACHE_Tache']=$_POST['tacheRecherche'];
}
if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1'){
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Tache.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "Tasks list";}else{echo "Liste des tâches";} ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td colspan="3"><b>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Search criteria :";}else{echo "Critères de recherche :";}?></b></td>
		</tr>
		<tr><td height="8"></td></tr>
		<tr>
			<td width="8%">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Task :";}else{echo "Tâche :";}?></td>
			<td width="15%"><input name="tacheRecherche" value="<?php echo $_SESSION['TACHE_Tache']; ?>" /></td>
			<td width="77%" align="left"><input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Search";}else{echo "Rechercher";}?>">&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr><td height="4"></td></tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="left">
			<table align="left" style="width:100%;">
				<tr>
				<td align="center">
				<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add task";}else{echo "Ajouter une tâche";} ?>&nbsp;</a>
				</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="left" class="GeneralInfo" style="width:100%;">
			<tr bgcolor="#00325F">
				<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Task";}else{echo "Tâche";} ?></td>
				<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Family";}else{echo "Famille";} ?></td>
				<td class="EnTeteTableauCompetences" width="12%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Workpackage";}else{echo "Workpackage";} ?></td>
				<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Further information";}else{echo "Informations complémentaires";} ?></td>
				<td class="EnTeteTableauCompetences" width="30%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Work unit";}else{echo "Unité d'oeuvre";} ?></td>
				<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "OTD";}else{echo "Critère OTD";} ?></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
			</tr>
			<?php
				$req="SELECT Id,Libelle,CritereOTD, (SELECT Libelle FROM trame_familletache WHERE trame_familletache.Id=trame_tache.Id_FamilleTache) AS Famille ";
				$req.="FROM trame_tache WHERE Supprime=false AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
				if($_SESSION['TACHE_Tache']<>""){
					$req.="AND Libelle LIKE '%".$_SESSION['TACHE_Tache']."%'";
				}
				$req." ORDER BY Libelle;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						$WP="";
						$req="SELECT trame_wp.Libelle FROM trame_tache_wp LEFT JOIN trame_wp ON trame_tache_wp.Id_WP=trame_wp.Id WHERE trame_tache_wp.Supprime=false AND Id_Tache=".$row['Id']." ORDER BY Libelle ";
						$resultWP=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($resultWP);
						if ($nbResulta>0){
							while($rowWP=mysqli_fetch_array($resultWP)){
								$WP.=addslashes($rowWP['Libelle'])."</br>";
							}
						}
						$UO="";
						$req="SELECT Complexite, Relation, TypeTravail, ";
						$req.="(SELECT Description FROM trame_uo WHERE trame_uo.Id=trame_tache_uo.Id_UO) AS UO, ";
						$req.="(SELECT Libelle FROM trame_domainetechnique WHERE trame_domainetechnique.Id=trame_tache_uo.Id_DT) AS DT ";
						$req.="FROM trame_tache_uo WHERE Id_Tache=".$row['Id']." ";
						$req.="ORDER BY UO ";
						$resultUO=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($resultUO);
						if ($nbResulta>0){
							$UO.="<table cellpadding='0' cellspacing='0' align='left' style='width:100%;'>";
							while($rowUO=mysqli_fetch_array($resultUO)){
								$UO.= "<tr><td style='width:40%;border:1px dotted;'>".stripslashes($rowUO['UO'])."</td><td style='width:20%;border:1px dotted;'>".stripslashes($rowUO['DT'])."</td><td style='width:20%;border:1px dotted;'>".stripslashes($rowUO['TypeTravail'])."</td><td style='width:20%;border:1px dotted;'>".stripslashes($rowUO['Complexite'])."</td><td style='width:20%;border:1px dotted;'>".stripslashes($rowUO['Relation'])."</td></tr>";
							}
							$UO.="</table>";
						}
						
						$Info="";
						$req="SELECT Id,Info,Type FROM trame_tache_infocomplementaire WHERE Id_Tache=".$row['Id']." AND Supprime=0 ORDER BY Info ";
						$resultInfo=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($resultInfo);
						if ($nbResulta>0){
							while($rowInfo=mysqli_fetch_array($resultInfo)){
								$type=$rowInfo['Type'];
								if($_SESSION['Langue']=="EN"){
									if($type=="Texte"){$type="Text";}
									elseif($type=="Numerique"){$type="Digital";}
									elseif($type=="Date"){$type="Date";}
									elseif($type=="Menu deroulant"){$type="Drop-down menu";}
								}
								
								$menu="";
								if($type=="Menu deroulant"){
									$req="SELECT Libelle FROM trame_menuderoulant WHERE Id_Tache_InfoComplementaire=".$rowInfo['Id']." AND Supprime=0 ORDER BY Libelle ";
									$resultMenu=mysqli_query($bdd,$req);
									$nbResultaMenu=mysqli_num_rows($resultMenu);
									if ($nbResultaMenu>0){
										$menu.=" [";
										while($rowMenu=mysqli_fetch_array($resultMenu)){
											if($menu<>" ["){$menu.="|";}
											$menu.=stripslashes($rowMenu['Libelle']);
										}
										$menu.="]";
									}
								}
								$Info.=stripslashes($rowInfo['Info'])." (".$type.")".$menu."<br>";
							}
						}
						?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="20%">&nbsp;<?php echo stripslashes(str_replace("\\","",$row['Libelle']));?></td>
								<td width="15%"><?php echo stripslashes(str_replace("\\","",$row['Famille']));?></td>
								<td width="12%"><?php echo stripslashes(str_replace("\\","",$WP));?></td>
								<td width="15%"><?php echo stripslashes(str_replace("\\","",$Info));?></td>
								<td width="30%"><?php echo $UO;?></td>
								<td width="15%"><?php echo $row['CritereOTD'];?></td>
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
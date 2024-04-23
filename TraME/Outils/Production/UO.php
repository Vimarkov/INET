<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout(){
			var w=window.open("Ajout_UO.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=1000,height=300");
			w.focus();
			}
		function OuvreFenetreModif(Id){
			var w=window.open("Ajout_UO.php?Mode=M&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=1000,height=300");
			w.focus();
			}
		function OuvreFenetreSuppr(Id){
			if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
			var w=window.open("Ajout_UO.php?Mode=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,,width=60,height=40");
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

$_SESSION['Formulaire']="Production/UO.php";

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="UO.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "Work unit list";}else{echo "Liste des unités d'oeuvres";} ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="left">
			<table align="left" style="width:100%;">
				<tr>
				<td align="center">
				<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add work unit";}else{echo "Ajouter une unité d'oeuvre";} ?>&nbsp;</a>
				</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="left" class="GeneralInfo" style="width:100%;">
			<tr bgcolor="#00325F">
				<td class="EnTeteTableauCompetences" rowspan="2" width="8%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Work unit";}else{echo "UO";} ?></td>
				<td class="EnTeteTableauCompetences" rowspan="2" width="15%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Description";}else{echo "Description";} ?></td>
				<td class="EnTeteTableauCompetences" rowspan="2" width="12%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Category";}else{echo "Catégorie";} ?></td>
				<td class="EnTeteTableauCompetences" rowspan="2" width="8%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Technical domain";}else{echo "Domaine technique";} ?></td>
				<td class="EnTeteTableauCompetences" colspan="5" width="24%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;Creation</td>
				<td class="EnTeteTableauCompetences" colspan="5" width="24%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;Update</td>
				<td class="EnTeteTableauCompetences" rowspan="2" width="2%"></td>
				<td class="EnTeteTableauCompetences" rowspan="2" width="2%"></td>
			</tr>
			<tr bgcolor="#00325F">
				<td class="EnTeteTableauCompetences" width="2%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;Low</td>
				<td class="EnTeteTableauCompetences" width="2%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;Medium</td>
				<td class="EnTeteTableauCompetences" width="2%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;High</td>
				<td class="EnTeteTableauCompetences" width="2%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;Very High</td>
				<td class="EnTeteTableauCompetences" width="2%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;Other</td>
				<td class="EnTeteTableauCompetences" width="2%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;Low</td>
				<td class="EnTeteTableauCompetences" width="2%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;Medium</td>
				<td class="EnTeteTableauCompetences" width="2%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;High</td>
				<td class="EnTeteTableauCompetences" width="2%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;Very High</td>
				<td class="EnTeteTableauCompetences" width="2%" style="text-decoration:none;color:#ffffff;font-weight:bold;">&nbsp;Other</td>
			</tr>
			<?php
				$req="SELECT Id,Libelle,Description, (SELECT Libelle FROM trame_categorie WHERE trame_categorie.Id=trame_uo.Id_Categorie) AS Categorie FROM trame_uo WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Supprime=false ORDER BY Libelle;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				
				$req="SELECT Id,Libelle FROM trame_domainetechnique WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Supprime=false";
				$resultDT=mysqli_query($bdd,$req);
				$nbResultaDT=mysqli_num_rows($resultDT);
				if ($nbResulta>0){
					$couleur="#ffffff";
					$k=1;
					while($row=mysqli_fetch_array($result)){
						if($nbResultaDT>0){mysqli_data_seek($resultDT,0);}
						$i=1;
						while($rowDT=mysqli_fetch_array($resultDT)){
							$CL="";
							$CM="";
							$CH="";
							$CVH="";
							$COt="";
							$UL="";
							$UM="";
							$UH="";
							$UVH="";
							$UOt="";
							$req="SELECT Temps,Complexite,TypeTravail FROM trame_tempsalloue WHERE Id_UO='".$row['Id']."' AND Id_DomaineTechnique=".$rowDT['Id']." ";
							$resultTA=mysqli_query($bdd,$req);
							$nbResultaTA=mysqli_num_rows($resultTA);
							if($nbResultaTA){
								while($rowTA=mysqli_fetch_array($resultTA)){
									if($rowTA['TypeTravail']=="Creation"){
										if($rowTA['Complexite']=="Low"){$CL=$rowTA['Temps'];}
										elseif($rowTA['Complexite']=="Medium"){$CM=$rowTA['Temps'];}
										elseif($rowTA['Complexite']=="High"){$CH=$rowTA['Temps'];}
										elseif($rowTA['Complexite']=="Very High"){$CVH=$rowTA['Temps'];}
										elseif($rowTA['Complexite']=="Other"){$COt=$rowTA['Temps'];}
									}
									elseif($rowTA['TypeTravail']=="Update"){
										if($rowTA['Complexite']=="Low"){$UL=$rowTA['Temps'];}
										elseif($rowTA['Complexite']=="Medium"){$UM=$rowTA['Temps'];}
										elseif($rowTA['Complexite']=="High"){$UH=$rowTA['Temps'];}
										elseif($rowTA['Complexite']=="Very High"){$UVH=$rowTA['Temps'];}
										elseif($rowTA['Complexite']=="Other"){$UOt=$rowTA['Temps'];}
									}
								}
							}
							$styleRowspan="";
							$StyleDT="";
							if($k<$nbResulta){$styleRowspan="style='border-bottom:1px dotted;'";}
							if($i==$nbResultaDT && $k<$nbResulta){$StyleDT="style='border-bottom:1px dotted;'";}
							if($i==1){
							?>
								<tr bgcolor="<?php echo $couleur;?>">
									<td width="8%" <?php echo $styleRowspan;?> <?php if($nbResultaDT>1){echo "rowspan='".$nbResultaDT."'";} ?>>&nbsp;<?php echo stripslashes(str_replace("\\","",$row['Libelle']));?></td>
									<td width="15%" <?php echo $styleRowspan;?> <?php if($nbResultaDT>1){echo "rowspan='".$nbResultaDT."'";} ?>>&nbsp;<?php echo stripslashes(str_replace("\\","",$row['Description']));?></td>
									<td width="12%" <?php echo $styleRowspan;?> <?php if($nbResultaDT>1){echo "rowspan='".$nbResultaDT."'";} ?>>&nbsp;<?php echo stripslashes(str_replace("\\","",$row['Categorie']));?></td>
									<td width="8%" class="Libelle" <?php echo $StyleDT;?> >&nbsp;<?php echo $rowDT['Libelle'];?></td>
									<td width="3%" <?php echo $StyleDT;?> ><?php echo $CL;?></td>
									<td width="3%" <?php echo $StyleDT;?> ><?php echo $CM;?></td>
									<td width="3%" <?php echo $StyleDT;?> ><?php echo $CH;?></td>
									<td width="3%" <?php echo $StyleDT;?> ><?php echo $CVH;?></td>
									<td width="3%" <?php echo $StyleDT;?> ><?php echo $COt;?></td>
									<td width="3%" <?php echo $StyleDT;?> ><?php echo $UL;?></td>
									<td width="3%" <?php echo $StyleDT;?> ><?php echo $UM;?></td>
									<td width="3%" <?php echo $StyleDT;?> ><?php echo $UH;?></td>
									<td width="3%" <?php echo $StyleDT;?> ><?php echo $UVH;?></td>
									<td width="3%" <?php echo $StyleDT;?> ><?php echo $UOt;?></td>
									<td width="2%" <?php echo $styleRowspan;?> align="center" <?php if($nbResultaDT>1){echo "rowspan='".$nbResultaDT."'";} ?>>
										<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>)">
										<img src='../../Images/Modif.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";} ?>'>
										</a>
									</td>
									<td width="2%" <?php echo $styleRowspan;?> align="center" <?php if($nbResultaDT>1){echo "rowspan='".$nbResultaDT."'";} ?>>
									<a href="javascript:OuvreFenetreSuppr(<?php echo $row['Id']; ?>)">
									<img src='../../Images/Suppression.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>'>
									</a>
									</td>
								</tr>
							<?php
							}
							else{
							?>
								<tr bgcolor="<?php echo $couleur;?>">
									<td width="3%" class="Libelle" <?php echo $StyleDT;?> >&nbsp;<?php echo $rowDT['Libelle'];?></td>
									<td width="3%" <?php echo $StyleDT;?> ><?php echo $CL;?></td>
									<td width="3%" <?php echo $StyleDT;?> ><?php echo $CM;?></td>
									<td width="3%" <?php echo $StyleDT;?> ><?php echo $CH;?></td>
									<td width="3%" <?php echo $StyleDT;?> ><?php echo $CVH;?></td>
									<td width="3%" <?php echo $StyleDT;?> ><?php echo $COt;?></td>
									<td width="3%" <?php echo $StyleDT;?> ><?php echo $UL;?></td>
									<td width="3%" <?php echo $StyleDT;?> ><?php echo $UM;?></td>
									<td width="3%" <?php echo $StyleDT;?> ><?php echo $UH;?></td>
									<td width="3%" <?php echo $StyleDT;?> ><?php echo $UVH;?></td>
									<td width="3%" <?php echo $StyleDT;?> ><?php echo $UOt;?></td>
								</tr>
							<?php
							}
							$i++;
							if($couleur=="#ffffff"){$couleur="#E1E1D7";}
							else{$couleur="#ffffff";}
						}
						$k++;
					}
				}
			?>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
</form>
</table>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
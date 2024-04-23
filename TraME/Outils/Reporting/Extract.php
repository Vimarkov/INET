<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script src="../JS/jquery-1.11.1.min.js"></script>
    <script src="../JS/mask.js"></script>
	<script src="Extract.js?time=<?php echo time();?>"></script>
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

$_SESSION['Formulaire']="Reporting/Extract.php";
if($_POST){
	if(isset($_POST['Recherche_RAZ'])){
		$_SESSION['EXTRACT_DateDebut']="";
		$_SESSION['EXTRACT_DateFin']="";
		$_SESSION['EXTRACT_Statut']="";
		$_SESSION['EXTRACT_WP']="";
		$_SESSION['EXTRACT_Controle']="";
		
		$_SESSION['EXTRACT_DateDebut2']="";
		$_SESSION['EXTRACT_DateFin2']="";
		$_SESSION['EXTRACT_Statut2']="";
		$_SESSION['EXTRACT_WP2']="";
		$_SESSION['EXTRACT_Controle2']="";
	}
	if(isset($_POST['Recherche_RAZ2'])){
		$_SESSION['EXTRACT_DateDebutPointage']="";
		$_SESSION['EXTRACT_DateFinPointage']="";
		$_SESSION['EXTRACT_WPPointage']="";
		$_SESSION['EXTRACT_TachePointage']="";
		$_SESSION['EXTRACT_PreparateurPointage']="";
		
		$_SESSION['EXTRACT_DateDebutPointage2']="";
		$_SESSION['EXTRACT_DateFinPointage2']="";
		$_SESSION['EXTRACT_WPPointage2']="";
		$_SESSION['EXTRACT_TachePointage2']="";
		$_SESSION['EXTRACT_PreparateurPointage2']="";	
	}
	if(isset($_POST['Recherche_RAZQualite'])){
		$_SESSION['EXTRACT_WPQualite']="";
		
		$_SESSION['EXTRACT_WPQualite2']="";	
	}
}
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Extract.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "EXTRACT";}else{echo "EXTRACT";} ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<?php 
	if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],4,1)=='1'){
	?>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:98%;">
			<tr>
				<td><a style="text-decoration:none;" href="javascript:Excel_Parametrage()">&nbsp;&nbsp;&nbsp;&#x2794;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Setting";}else{echo "Paramétrage";}?>&nbsp;</a></td>
			</tr>
			<tr>
				<td><a style="text-decoration:none;" href="javascript:Excel_CatalogueUO()">&nbsp;&nbsp;&nbsp;&#x2794;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Unit work catalog";}else{echo "Catalogue d'unité d'oeuvre";}?>&nbsp;</a></td>
			</tr>
		</table>
		</td>
	</tr>
	<?php
	}
	?>
	<tr><td height="8"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td><b>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Search criteria :";}else{echo "Critères de recherche :";}?></b></td>
			<td align="right">
			<a style="text-decoration:none;" href="javascript:OuvreFenetreAjout_Critere()">&nbsp;<img src="../../Images/Plus2.png" border="0" alt="<?php if($_SESSION['Langue']=="EN"){echo "Add criteria";}else{echo "Ajouter critères";}?>" title="<?php if($_SESSION['Langue']=="EN"){echo "Add criteria";}else{echo "Ajouter critères";}?>">&nbsp;&nbsp;</a>
			</td>
		</tr>
		<?php
			if($_SESSION['EXTRACT_DateDebut']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Start date : ".$_SESSION['EXTRACT_DateDebut']."</td>";
				}
				else{
					echo "<td>&nbsp; Date de début : ".$_SESSION['EXTRACT_DateDebut']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['EXTRACT_DateFin']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; End date : ".$_SESSION['EXTRACT_DateFin']."</td>";
				}
				else{
					echo "<td>&nbsp; Date de fin : ".$_SESSION['EXTRACT_DateFin']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['EXTRACT_Statut']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Status : ".$_SESSION['EXTRACT_Statut']."</td>";
				}
				else{
					echo "<td>&nbsp; Statut : ".$_SESSION['EXTRACT_Statut']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['EXTRACT_WP']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Workpackage : ".$_SESSION['EXTRACT_WP']."</td>";
				}
				else{
					echo "<td>&nbsp; Workpackage : ".$_SESSION['EXTRACT_WP']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['EXTRACT_Controle']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Only deliverables controlled or to be controlled : ".$_SESSION['EXTRACT_Controle']."</td>";
				}
				else{
					echo "<td>&nbsp; Uniquement les livrables contrôlés ou à contrôler : ".$_SESSION['EXTRACT_Controle']."</td>";
				}
				echo "</tr>";
			}
		?>
		<tr>
			
			<td align="center" colspan="6">
				<input class="Bouton" name="Recherche_RAZ" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Clear search criteria";}else{echo "Vider les critères de recherche";}?>"> &nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr><td height="4"></td></tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:98%;">
			<tr>
				<td><a style="text-decoration:none;" href="javascript:Excel_Tache()">&nbsp;&nbsp;&nbsp;&#x2794;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Tasks";}else{echo "Tâches";}?>&nbsp;</a></td>
			</tr>
			<tr>
				<td><a style="text-decoration:none;" href="javascript:Excel_Tache2()">&nbsp;&nbsp;&nbsp;&#x2794;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Tasks (detailed)";}else{echo "Tâches (détaillées)";}?>&nbsp;</a></td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td><a style="text-decoration:none;" href="javascript:Excel_UO()">&nbsp;&nbsp;&nbsp;&#x2794;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Work units";}else{echo "Unités d'oeuvres";}?>&nbsp;</a></td>
			</tr>
			<tr>
				<td><a style="text-decoration:none;" href="javascript:Excel_UO2()">&nbsp;&nbsp;&nbsp;&#x2794;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Work units (detailed)";}else{echo "Unités d'oeuvres (détaillées)";}?>&nbsp;</a></td>
			</tr>
			<tr>
				<td><a style="text-decoration:none;" href="javascript:Excel_Client()">&nbsp;&nbsp;&nbsp;&#x2794;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Extract customer";}else{echo "Extract client";}?>&nbsp;</a></td>
			</tr>
		</table>
		</td>
	</tr>
	<?php
		if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],4,1)=='1'){
		$reqPlanning="SELECT Id FROM trame_prestation WHERE Planning=1 AND Id=".$_SESSION['Id_PrestationTR'];
		$resultPlanning=mysqli_query($bdd,$reqPlanning);
		$nbResultaPlanning=mysqli_num_rows($resultPlanning);
		if($nbResultaPlanning==0){
	?>
		<tr><td height="16"></td></tr>
		<tr><td>
		<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
			<tr>
				<td><b>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Search criteria :";}else{echo "Critères de recherche :";}?></b></td>
				<td align="right">
				<a style="text-decoration:none;" href="javascript:OuvreFenetreAjout_CriterePointage()">&nbsp;<img src="../../Images/Plus2.png" border="0" alt="<?php if($_SESSION['Langue']=="EN"){echo "Add criteria";}else{echo "Ajouter critères";}?>" title="<?php if($_SESSION['Langue']=="EN"){echo "Add criteria";}else{echo "Ajouter critères";}?>">&nbsp;&nbsp;</a>
				</td>
			</tr>
			<?php
				if($_SESSION['EXTRACT_DateDebutPointage']<>""){
					echo "<tr>";
					if($_SESSION['Langue']=="EN"){
						echo "<td>&nbsp; Start date : ".$_SESSION['EXTRACT_DateDebutPointage']."</td>";
					}
					else{
						echo "<td>&nbsp; Date de début : ".$_SESSION['EXTRACT_DateDebutPointage']."</td>";
					}
					echo "</tr>";
				}
				if($_SESSION['EXTRACT_DateFinPointage']<>""){
					echo "<tr>";
					if($_SESSION['Langue']=="EN"){
						echo "<td>&nbsp; End date : ".$_SESSION['EXTRACT_DateFinPointage']."</td>";
					}
					else{
						echo "<td>&nbsp; Date de fin : ".$_SESSION['EXTRACT_DateFinPointage']."</td>";
					}
					echo "</tr>";
				}
				if($_SESSION['EXTRACT_WPPointage']<>""){
					echo "<tr>";
					if($_SESSION['Langue']=="EN"){
						echo "<td>&nbsp; Workpackage : ".$_SESSION['EXTRACT_WPPointage']."</td>";
					}
					else{
						echo "<td>&nbsp; Workpackage : ".$_SESSION['EXTRACT_WPPointage']."</td>";
					}
					echo "</tr>";
				}
				if($_SESSION['EXTRACT_TachePointage']<>""){
					echo "<tr>";
					if($_SESSION['Langue']=="EN"){
						echo "<td>&nbsp; Task : ".$_SESSION['EXTRACT_TachePointage']."</td>";
					}
					else{
						echo "<td>&nbsp; Tâche : ".$_SESSION['EXTRACT_TachePointage']."</td>";
					}
					echo "</tr>";
				}
				if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],4,1)=='1'){
					if($_SESSION['EXTRACT_PreparateurPointage']<>""){
						echo "<tr>";
						if($_SESSION['Langue']=="EN"){
							echo "<td>&nbsp; Manufacturing engineer : ".$_SESSION['EXTRACT_PreparateurPointage']."</td>";
						}
						else{
							echo "<td>&nbsp; Préparateur : ".$_SESSION['EXTRACT_PreparateurPointage']."</td>";
						}
						echo "</tr>";
					}
				}
			?>
			<tr>
				<td align="center" colspan="6">
					<input class="Bouton" name="Recherche_RAZ2" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Clear search criteria";}else{echo "Vider les critères de recherche";}?>"> &nbsp;&nbsp;&nbsp;&nbsp;
				</td>
			</tr>
			<tr><td height="4"></td></tr>
		</table>
		</td></tr>
		<tr><td height="4"></td></tr>
		<tr><td>
			<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:98%;">
				<tr>
					<td><a style="text-decoration:none;" href="javascript:Excel_Pointage()">&nbsp;&nbsp;&nbsp;&#x2794;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Schedule";}else{echo "Pointage";}?>&nbsp;</a></td>
				</tr>
			</table>
			</td>
		</tr>
	<?php
		}
		}
		if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],4,1)=='1'){
	?>
	<tr><td height="4"></td></tr>
	<tr><td height="16"></td></tr>
		<tr><td>
		<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
			<tr>
				<td><b>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Search criteria :";}else{echo "Critères de recherche :";}?></b></td>
				<td align="right">
				<a style="text-decoration:none;" href="javascript:OuvreFenetreAjout_CritereQualite()">&nbsp;<img src="../../Images/Plus2.png" border="0" alt="<?php if($_SESSION['Langue']=="EN"){echo "Add criteria";}else{echo "Ajouter critères";}?>" title="<?php if($_SESSION['Langue']=="EN"){echo "Add criteria";}else{echo "Ajouter critères";}?>">&nbsp;&nbsp;</a>
				</td>
			</tr>
			<?php
				if($_SESSION['EXTRACT_MoisQualite']<>""){
					echo "<tr>";
					if($_SESSION['Langue']=="EN"){
						echo "<td>&nbsp; Month : ".$_SESSION['EXTRACT_MoisQualite']."</td>";
					}
					else{
						echo "<td>&nbsp; Mois : ".$_SESSION['EXTRACT_MoisQualite']."</td>";
					}
					echo "</tr>";
				}
				if($_SESSION['EXTRACT_WPQualite']<>""){
					echo "<tr>";
					if($_SESSION['Langue']=="EN"){
						echo "<td>&nbsp; Workpackage : ".$_SESSION['EXTRACT_WPQualite']."</td>";
					}
					else{
						echo "<td>&nbsp; Workpackage : ".$_SESSION['EXTRACT_WPQualite']."</td>";
					}
					echo "</tr>";
				}
				if($_SESSION['EXTRACT_Checklist']<>""){
					echo "<tr>";
					if($_SESSION['Langue']=="EN"){
						echo "<td>&nbsp; Checklist : ".$_SESSION['EXTRACT_Checklist']."</td>";
					}
					else{
						echo "<td>&nbsp; Checklist : ".$_SESSION['EXTRACT_Checklist']."</td>";
					}
					echo "</tr>";
				}
				if($_SESSION['EXTRACT_Responsable']<>""){
					echo "<tr>";
					if($_SESSION['Langue']=="EN"){
						echo "<td>&nbsp; Responsible (only for the OQD) : ".$_SESSION['EXTRACT_Responsable']."</td>";
					}
					else{
						echo "<td>&nbsp; Responsable (uniquement pour l'OQD) : ".$_SESSION['EXTRACT_Responsable']."</td>";
					}
					echo "</tr>";
				}
				
			?>
			<tr>
				<td align="center" colspan="6">
					<input class="Bouton" name="Recherche_RAZQualite" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Clear search criteria";}else{echo "Vider les critères de recherche";}?>"> &nbsp;&nbsp;&nbsp;&nbsp;
				</td>
			</tr>
			<tr><td height="4"></td></tr>
		</table>
		</td></tr>
		<tr><td height="4"></td></tr>
		<tr><td>
			<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:98%;">
				<tr>
					<td><a style="text-decoration:none;" href="javascript:Excel_OTD()">&nbsp;&nbsp;&nbsp;&#x2794;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "OTD";}else{echo "OTD";}?>&nbsp;</a></td>
				</tr>
				<tr>
					<td><a style="text-decoration:none;" href="javascript:Excel_OQD()">&nbsp;&nbsp;&nbsp;&#x2794;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "OQD";}else{echo "OQD";}?>&nbsp;</a></td>
				</tr>
				<tr>
					<td><a style="text-decoration:none;" href="javascript:Excel_CC_Global()">&nbsp;&nbsp;&nbsp;&#x2794;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Indicators for monitoring general cross checks";}else{echo "Indicateurs de suivi des contrôles croisés généraux";}?>&nbsp;</a></td>
				</tr>
				<tr>
					<td><a style="text-decoration:none;" href="javascript:Excel_CC_CL()">&nbsp;&nbsp;&nbsp;&#x2794;&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Indicators for monitoring cross checks of a checklist";}else{echo "Indicateurs de suivi des contrôles croisés d'une check-list";}?>&nbsp;</a></td>
				</tr>
			</table>
			</td>
		</tr>
		<tr><td height="16"></td></tr>
		<tr><td>
			<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:98%;">
				<tr>
					<td colspan="4"><b>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Edit a checklist :";}else{echo "Editer une fiche de contrôle :";}?></b></td>
				</tr>
				<tr>
					<td width="10%" class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Référence";}else{echo "Reference";} ?></td>
					<td colspan="5"> 
						<input type="texte" name="reference" size="20" value="<?php if($_POST){echo $_POST['reference'];} ?>">
						<input type="submit" class="Bouton" name="btnRechercher" size="20" value="<?php if($_SESSION['Langue']=="EN"){echo "Search";}else{echo "Rechercher";} ?>">
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<?php
					if($_POST){
						if(isset($_POST['reference'])){
						?>
							<tr bgcolor="#00325F">
								<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Reference";}else{echo "Référence";} ?></td>
								<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Date of work";}else{echo "Date du travail";} ?></td>
								<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Status";}else{echo "Statut";} ?></td>
								<td class="EnTeteTableauCompetences" width="70%"><?php if($_SESSION['Langue']=="EN"){echo "Comment";}else{echo "Commentaire";} ?></td>
							</tr>
						<?php
							$req="SELECT Id,Statut,Designation,DatePreparateur AS DatePrepa,DescriptionModification,
								(SELECT Id FROM trame_controlecroise WHERE Id_TravailEffectue=trame_travaileffectue.Id ORDER BY Id DESC LIMIT 1) AS Id_CC,
								(SELECT Id_CLVersion FROM trame_controlecroise WHERE Id_TravailEffectue=trame_travaileffectue.Id ORDER BY Id DESC LIMIT 1) AS Id_CLVersion
								FROM trame_travaileffectue 
								WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." 
								AND Designation LIKE '%".$_POST['reference']."%' 
								AND (SELECT COUNT(Id) FROM trame_controlecroise WHERE Id_TravailEffectue=trame_travaileffectue.Id)>0 
								AND Statut<>'AC' ";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							
							if($nbResulta>0){
								$couleur="#ffffff";
								while($row=mysqli_fetch_array($result)){
									if($couleur=="#ffffff"){$couleur="#E1E1D7";}
									else{$couleur="#ffffff";}
									
									$statut=$row['Statut'];
									if($_SESSION['Langue']=="EN"){
										if($row['Statut']=="EN COURS"){$statut="IN PROGRESS";}
										elseif($row['Statut']=="BLOQUE"){$statut="BLOCKED";}
										elseif($row['Statut']=="EN ATTENTE"){$statut="WAITING";}
										elseif($row['Statut']=="A VALIDER"){$statut="TO BE VALIDATED";}
										elseif($row['Statut']=="VALIDE"){$statut="VALIDATED";}
										elseif($row['Statut']=="REFUSE"){$statut="RETURN";}
										elseif($row['Statut']=="AC"){$statut="AUTO CONTROL>";}
										elseif($row['Statut']=="CONTROLE"){$statut="CONTROL";}
										if($row['Statut']=="REC"){$statut="CONTROL AGAIN";}
									}
									else{
										if($row['Statut']=="AC"){$statut="AUTO-CONTROLE";}
										elseif($row['Statut']=="REC"){$statut="RECONTROLE";}
										elseif($row['Statut']=="CONTROLE"){$statut="CONTROLE";}
										elseif($row['Statut']=="REFUSE"){$statut="RETOURNE";}
									}
								?>
								<tr bgcolor="<?php echo $couleur;?>">
									<td>&nbsp;<?php echo "<a href='javascript:ExcelControle(".$row['Id'].",".$row['Id_CC'].",".$row['Id_CLVersion'].")'>".$row['Designation']."</a>";?></td>
									<td><?php echo AfficheDateJJ_MM_AAAA($row['DatePrepa']);?></td>
									<td><?php echo $statut; ?></td>
									<td><?php echo stripslashes(str_replace("\\","",$row['DescriptionModification']));?></td>
								</tr>
								<?php
								}
							}
						}
					}
				?>
			</table>
			</td>
		</tr>
	<?php
		}
	?>
	<tr><td height="16"></td></tr>
</form>
</table>
</body>
</html>
<?php
require("../../Menu.php");
?>
<script language="javascript">
	function ModifVacation(Mode,Id_PrestationPole,DateDebut,DateFin)
		{var w=window.open("Modifier_VacationPrestation.php?Mode="+Mode+"&Id_PrestationPole="+Id_PrestationPole+"&DateDebut="+DateDebut+"&DateFin="+DateFin,"PageVacation","status=no,menubar=no,width=800,height=600,scrollbars=1");
		w.focus();
		}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

$prestation=0;
if(isset($_GET['Id_PrestationPole'])){$prestation=$_GET['Id_PrestationPole'];}
elseif(isset($_POST['Id_PrestationPole'])){$prestation=$_POST['Id_PrestationPole'];}

$bExiste=false;

function Titre1($Libelle,$Lien,$Selected){
	$tiret="";
	if($Selected==true){$tiret="border-bottom:4px solid #bbbabc;";}
	echo "<td style=\"width:70px;height:30px;border-spacing:0;text-align:center;color:#656466;valign:top;font-weight:bold;".$tiret."\">
		<a style=\"text-decoration:none;width:70px;height:30px;border-spacing:0;text-align:center;color:#656466;valign:top;font-weight:bold;\" onmouseover=\"this.style.color='#656466';\" onmouseout=\"this.style.color='#656466';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle."</a></td>\n";
}
$Menu=6;
?>

<form class="test" action="Liste_PrestationVacationHistorique.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="Id_PrestationPole" id="Id_PrestationPole" value="<?php echo $prestation; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#FFFFFF;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=6'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Prestations / Vacations";}else{echo "Sites / Vacations";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td colspan="5">
			<table style="width:100%; border-spacing:0;">
				<tr bgcolor="#ffffff">
					<?php
						if($_SESSION["Langue"]=="FR"){Titre1("VACATIONS EN COURS","Outils/PlanningV2/Liste_PrestationVacation.php?Menu=".$Menu."",false);}
						else{Titre1("VACATIONS IN PROGRESS","Outils/PlanningV2/Liste_PrestationVacation.php?Menu=".$Menu."",false);}

						if($_SESSION["Langue"]=="FR"){Titre1("HISTORIQUE","Outils/PlanningV2/Liste_PrestationVacationHistorique.php?Menu=".$Menu."",true);}
						else{Titre1("HISTORICAL","Outils/PlanningV2/Liste_PrestationVacationHistorique.php?Menu=".$Menu."",true);}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr><td>
	<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:60%">
		<tr><td height="5"></td></tr>
		<tr>
			<td width="50%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Rechercher : ";}else{echo "Search : ";} 
				if($_POST){$_SESSION['FiltreRHVacation_Recherche']=$_POST['recherche'];}
				?>
				<input id="recherche" name="recherche" type="texte" value="<?php echo $_SESSION['FiltreRHVacation_Recherche']; ?>" size="25"/>&nbsp;&nbsp;&nbsp;
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
			</td>
			<td width="50%" rowspan="4" valign="top">
			<?php
				if($prestation>0){
					$req="SELECT DISTINCT DateDebut,DateFin
						FROM rh_prestation_vacation 
						WHERE CONCAT(Id_Prestation,'_',Id_Pole)='".$prestation."' 
						AND Suppr=0 
						ORDER BY DateDebut DESC,DateFin DESC";
					$result=mysqli_query($bdd,$req);
					
			?>
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr><td>
						<table width="95%" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td colspan="6">
								<table width="100%">
									<tr><td height="8"></td></tr>
									<tr>
										<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début";}else{echo "Start date";} ?></td>
										<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin";}else{echo "End date";} ?></td>
										<td class="EnTeteTableauCompetences" width="3%"></td>
										<td class="EnTeteTableauCompetences" width="3%" style="text-align:right;">
											<a class="Modif" href="javascript:ModifVacation('A','<?php echo $prestation; ?>','0001-01-01','0001-01-01');">
												<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
											</a>
										</td>
									</tr>
									<?php
									$nbResulta=mysqli_num_rows($result);
									
									$couleur="#FFFFFF";
									if($nbResulta>0){
										while($row=mysqli_fetch_array($result))
										{
											if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
											else{$couleur="#FFFFFF";}
									?>
											<tr bgcolor="<?php echo $couleur;?>">
												<td style="border-bottom:1px dotted #976fa1;"><?php echo AfficheDateJJ_MM_AAAA($row['DateDebut']);?></td>
												<td style="border-bottom:1px dotted #976fa1;"><?php echo AfficheDateJJ_MM_AAAA($row['DateFin']);?></td>
												<td style="border-bottom:1px dotted #976fa1;" align="center">
													<a class="Modif" href="javascript:ModifVacation('M','<?php echo $prestation; ?>','<?php echo $row['DateDebut']; ?>','<?php echo $row['DateFin']; ?>');">
														<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
													</a>
												</td>
												<td style="border-bottom:1px dotted #976fa1;" align="center">
													<a class="Modif" href="javascript:ModifVacation('S','<?php echo $prestation; ?>','<?php echo $row['DateDebut']; ?>','<?php echo $row['DateFin']; ?>');">
														<img src="../../Images/Suppression.gif" style="border:0;" alt="Suppression">
													</a>
												</td>
											</tr>
									<?php
										}	
									}
									?>
								</table>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
						</table>
					</td></tr>
				</table>
			<?php
				}
			?>
			</td>
		</tr>
		<tr><td height="5"></td></tr>
		<tr>
			<td width="50%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Liste des prestations : ";}else{echo "List of site : ";} 
				?>
			</td>
		</tr>
		<tr>
			<td width="50%" valign="top">
				&nbsp;<div id='div_Prestation' style='height:300px;width:100%;overflow:auto;' >
					<?php
					echo "<table width='100%' valign='top'>";
					$reqPlus="AND Id=0";
					$reqPlus2="AND new_competences_pole.Id=0 ";
					if($_SESSION['FiltreRHVacation_Recherche']<>""){
						$reqPlus="AND Libelle LIKE \"%".$_SESSION['FiltreRHVacation_Recherche']."%\" ";
						$reqPlus2="AND CONCAT(new_competences_prestation.Libelle,' - ',new_competences_pole.Libelle) LIKE \"%".$_SESSION['FiltreRHVacation_Recherche']."%\" ";
					}
					$requete="SELECT DISTINCT Id AS Id_Prestation, Libelle, '' AS LibellePole, 0 AS Id_Pole, Id_Plateforme
						FROM new_competences_prestation
						WHERE Active=0
						".$reqPlus."
						AND Id NOT IN (
							SELECT Id_Prestation
							FROM new_competences_pole   
							WHERE new_competences_pole.Actif=0
						)
						UNION 
						
						SELECT DISTINCT new_competences_pole.Id_Prestation, new_competences_prestation.Libelle,
							new_competences_pole.Libelle AS LibellePole, new_competences_pole.Id AS Id_Pole, new_competences_prestation.Id_Plateforme
							FROM new_competences_pole
							INNER JOIN new_competences_prestation
							ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
							WHERE Active=0
							AND Actif=0
							".$reqPlus2."
						ORDER BY Libelle, LibellePole";
					$result=mysqli_query($bdd,$requete);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$couleur="";
							$ancre="";
							if($prestation>0){
								if($prestation==$row['Id_Prestation']."_".$row['Id_Pole']){$couleur="bgcolor='#f3fa72'";$ancre="id='selection'";}
							}
							$pole="";
							if($row['Id_Pole']<>0){$pole= " - ".stripslashes($row['LibellePole']);}
							echo "<tr ".$ancre." ".$couleur."><td><a style=\"text-decoration:none;color:#674870;\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_PrestationVacationHistorique.php?Menu=".$Menu."&Id_PrestationPole=".$row['Id_Prestation']."_".$row['Id_Pole']."#selection'>".$row['Libelle'].$pole."</a></td></tr>";
						}
					}
					echo "</table>";
					?>
				</div>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
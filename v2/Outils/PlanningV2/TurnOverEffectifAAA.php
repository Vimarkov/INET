<?php
require("../../Menu.php");
?>
<script>
	function OuvreFenetreExcel1()
	{window.open("Export_TurnOverEffectifAAAEntree.php","PageExcel","status=no,menubar=no,width=900,height=450");}
	function OuvreFenetreExcel2()
	{window.open("Export_TurnOverEffectifAAASortie.php","PageExcel","status=no,menubar=no,width=900,height=450");}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

$TDB=0;
if($_GET){
	if(isset($_GET['TDB'])){
		$TDB=$_GET['TDB'];
	}
}
else{
	$TDB=$_POST['TDB'];
}
$OngletTDB="";
if($_GET){
	if(isset($_GET['OngletTDB'])){
		$OngletTDB=$_GET['OngletTDB'];
	}
}
else{
	$OngletTDB=$_POST['OngletTDB'];
}

?>

<form class="test" action="TurnOverEffectifAAA.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $TDB; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $OngletTDB; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#a988b2;">
				<tr>
					<td class="TitrePage">
					<?php
					$leMenu=$Menu;
					if($TDB>0){$leMenu=$TDB;}
					if($OngletTDB<>""){$leMenu.="&OngletTDB=".$OngletTDB;}
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$leMenu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Suivi / Turn over AAA";}else{echo "Follow-up / Turn over AAA";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Plateforme :";}else{echo "Plateform :";} ?>
				<select class="plateforme" style="width:100px;" name="plateforme" onchange="submit();">
				<?php
				$requetePlateforme="SELECT Id, Libelle
					FROM new_competences_plateforme
					WHERE Id IN 
						(
							SELECT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION['Id_Personne']." 
							AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
						)
					ORDER BY Libelle ASC";
				$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
				$nbPlateforme=mysqli_num_rows($resultPlateforme);
				
				$PlateformeSelect = 0;
				$Selected = "";
				
				$PlateformeSelect=$_SESSION['FiltreRHTurnOverAAA_Plateforme'];
				if($_POST){$PlateformeSelect=$_POST['plateforme'];}
				$_SESSION['FiltreRHTurnOverAAA_Plateforme']=$PlateformeSelect;	
				
				
				if ($nbPlateforme > 0)
				{
					while($row=mysqli_fetch_array($resultPlateforme))
					{
						$selected="";
						if($PlateformeSelect<>"")
							{if($PlateformeSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 else{
					 echo "<option name='0' value='0' Selected></option>";
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Mois :";}else{echo "Month :";} ?>
				<select id="mois" name="mois" onchange="submit();">
					<?php
						if($_SESSION["Langue"]=="FR"){
							$arrayMois=array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
						}
						else{
							$arrayMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
						}
						$mois=$_SESSION['FiltreRHTurnOverAAA_Mois'];
						if($_POST){$mois=$_POST['mois'];}
						$_SESSION['FiltreRHTurnOverAAA_Mois']=$mois;
						
						for($i=0;$i<=11;$i++){
							$numMois=$i+1;
							if($numMois<10){$numMois="0".$numMois;}
							echo "<option value='".$numMois."'";
							if($mois== ($i+1)){echo " selected ";}
							echo ">".$arrayMois[$i]."</option>\n";
						}
						
						$annee=$_SESSION['FiltreRHTurnOverAAA_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						if($annee==""){$annee=date("Y");}
						$_SESSION['FiltreRHTurnOverAAA_Annee']=$annee;
					?>
				</select>
			</td>
			<td width="8%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Année :";}else{echo "Year :";} ?>
				<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
			</td>
			<td width="40%">
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td>
			<table style="width:100%;">
				<tr>
					<td width="50%" class="Libelle" align="center">
						<?php if($_SESSION["Langue"]=="FR"){echo "ENTREES";}else{echo "ENTRY";} ?>
						<a href="javascript:OuvreFenetreExcel1()">
							<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
						</a>
					</td>
					<td width="50%" class="Libelle" align="center">
						<?php if($_SESSION["Langue"]=="FR"){echo "SORTIES";}else{echo "EXIT";} ?>
						<a href="javascript:OuvreFenetreExcel2()">
							<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
						</a>
					</td>
				</tr>
				<tr>
						<td width="50%" valign="top">
							<div style="width:100%;height:400px;overflow:auto;">
							<table class="TableCompetences" style="width:100%;">
								<tr>
									<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
									<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
									<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Date entrée";}else{echo "Date entered";} ?></td>
									<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Type de contrat";}else{echo "Type of Contract";} ?></td>
								</tr>
								<?php 
									$dateDebut=date('Y-m-d', mktime(0, 0, 0, $mois, 1 ,$annee));
									$dateFin=date('Y-m-d', mktime(0, 0, 0, $mois+1, 0 ,$annee));
									
									$dateDebutM_1=date('Y-m-d', mktime(0, 0, 0, $mois-1, 1 ,$annee));
									$dateFinM_1=date('Y-m-d', mktime(0, 0, 0, $mois, 0 ,$annee));
									
									$req="SELECT DISTINCT Id_Personne,
									(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
									FROM rh_personne_contrat
									WHERE Suppr=0
									AND Id_TypeContrat IN (SELECT Id FROM rh_typecontrat WHERE Suppr=0 AND EstSalarie=1)
									AND DateDebut<='".$dateFin."'
									AND (
										(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation)=".$PlateformeSelect."
										OR 
										(
											rh_personne_contrat.Id_Prestation=0
											AND (SELECT COUNT(rh_personne_mouvement.Id)
												FROM rh_personne_mouvement
												WHERE rh_personne_mouvement.Suppr=0
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation)=".$PlateformeSelect."
												AND rh_personne_mouvement.Id_Personne=rh_personne_contrat.Id_Personne
												AND rh_personne_mouvement.EtatValidation=1
												AND rh_personne_mouvement.DateDebut<='".$dateFin."'
												AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
											)>0
										)
									)
									AND (DateFin>='".$dateDebut."' OR DateFin<='0001-01-01' )
									AND TypeDocument IN ('Nouveau','Avenant')
									AND Id_Personne NOT IN (
										SELECT DISTINCT Id_Personne
										FROM rh_personne_contrat
										WHERE Suppr=0
										AND Id_TypeContrat IN (SELECT Id FROM rh_typecontrat WHERE Suppr=0 AND EstSalarie=1)
										AND (
											(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation)=".$PlateformeSelect."
											OR 
											(
												rh_personne_contrat.Id_Prestation=0
											)
										)
										AND DateDebut<='".$dateFinM_1."'
										AND (DateFin>='".$dateDebutM_1."' OR DateFin<='0001-01-01' )
										AND TypeDocument IN ('Nouveau','Avenant')
									)
									ORDER BY Personne ASC";
									$resultEntree=mysqli_query($bdd,$req);
									$nbEntree=mysqli_num_rows($resultEntree);
									
									$couleur="#FFFFFF";
									if($nbEntree>0){
										while($rowpersonne=mysqli_fetch_array($resultEntree))
										{
											if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
											else{$couleur="#FFFFFF";}
											
											
											//Prestation et date d'entrée à cette date 
											$req="SELECT Id_Prestation, Id_Pole,
												(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
												(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole
												FROM rh_personne_mouvement
												WHERE Suppr=0
												AND Id_Personne=".$rowpersonne['Id_Personne']." 
												AND EtatValidation=1
												AND rh_personne_mouvement.DateDebut<='".$dateFin."'
												AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."') ";
											$result=mysqli_query($bdd,$req);
											$nb=mysqli_num_rows($result);
											$Prestation="";
											if($nb>0){
												$rowMouv=mysqli_fetch_array($result);
												$Prestation=substr($rowMouv['Prestation'],0,7);
												if($rowMouv['Id_Pole']>0){
													$Prestation.=" - ".$rowMouv['Pole'];
												}
											}
											
											$DateEntree="";
											$TypeContrat="";
											$req="SELECT DateDebut,
											(SELECT Code FROM rh_typecontrat WHERE Id=Id_TypeContrat) AS TypeContrat
											FROM rh_personne_contrat
											WHERE Suppr=0
											AND Id_TypeContrat IN (SELECT Id FROM rh_typecontrat WHERE Suppr=0 AND EstSalarie=1)
											AND Id_Personne=".$rowpersonne['Id_Personne']."
											AND DateDebut<='".$dateFin."'
											AND (DateFin>='".$dateDebut."' OR DateFin<='0001-01-01' )
											AND TypeDocument IN ('Nouveau','Avenant')
											ORDER BY DateDebut ASC";
											$resultDate=mysqli_query($bdd,$req);
											$nbDate=mysqli_num_rows($resultDate);
											if($nbDate>0){
												$rowDate=mysqli_fetch_array($resultDate);
												$DateEntree=AfficheDateJJ_MM_AAAA($rowDate['DateDebut']);
												$TypeContrat=$rowDate['TypeContrat'];
											}
											
											echo "<tr bgcolor=".$couleur.">";
												echo "<td>".$rowpersonne['Personne']."</td>";
												echo "<td>".$Prestation."</td>";
												echo "<td>".$DateEntree."</td>";
												echo "<td>".$TypeContrat."</td>";
											echo "</tr>";
										}
									}
								?>
							</table>
							</div>
						</td>
						<td width="50%" valign="top">
							<div style="width:100%;height:400px;overflow:auto;">
							<table class="TableCompetences" style="width:100%;">
								<tr>
									<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
									<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
									<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Date de sortie";}else{echo "Release date";} ?></td>
									<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Motif de sortie";}else{echo "Exit reason";} ?></td>
									<td class="EnTeteTableauCompetences" width="20%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Type de contrat";}else{echo "Type of Contract";} ?></td>
								</tr>
								<?php 
									$dateDebut=date('Y-m-d', mktime(0, 0, 0, $mois, 1 ,$annee));
									$dateFin=date('Y-m-d', mktime(0, 0, 0, $mois+1, 0 ,$annee));
									
									$dateDebutM_1=date('Y-m-d', mktime(0, 0, 0, $mois+1, 1 ,$annee));
									$dateFinM_1=date('Y-m-d', mktime(0, 0, 0, $mois+2, 0 ,$annee));
									
									$req="SELECT DISTINCT Id_Personne,
									(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
									FROM rh_personne_contrat
									WHERE Suppr=0
									AND Id_TypeContrat IN (SELECT Id FROM rh_typecontrat WHERE Suppr=0 AND EstSalarie=1)
									AND DateDebut<='".$dateFin."'
									AND (
											(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation)=".$PlateformeSelect."
											OR 
											(
												rh_personne_contrat.Id_Prestation=0
												AND (SELECT COUNT(rh_personne_mouvement.Id)
													FROM rh_personne_mouvement
													WHERE rh_personne_mouvement.Suppr=0
													AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation)=".$PlateformeSelect."
													AND rh_personne_mouvement.Id_Personne=rh_personne_contrat.Id_Personne
													AND rh_personne_mouvement.EtatValidation=1
													AND rh_personne_mouvement.DateDebut<='".$dateFin."'
													AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
												)>0
											)
										)
									AND (DateFin>='".$dateDebut."')
									AND TypeDocument IN ('Nouveau','Avenant')
									AND Id_Personne NOT IN (
										SELECT DISTINCT Id_Personne
										FROM rh_personne_contrat
										WHERE Suppr=0
										AND Id_TypeContrat IN (SELECT Id FROM rh_typecontrat WHERE Suppr=0 AND EstSalarie=1)
										AND (
												(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation)=".$PlateformeSelect."
												OR 
												(
													rh_personne_contrat.Id_Prestation=0
												)
											)
										AND DateDebut<='".$dateFinM_1."'
										AND (DateFin>='".$dateDebutM_1."' OR DateFin<='0001-01-01')
										AND TypeDocument IN ('Nouveau','Avenant')
									)
									ORDER BY Personne ASC";
									$resultEntree=mysqli_query($bdd,$req);
									$nbEntree=mysqli_num_rows($resultEntree);

									$couleur="#FFFFFF";
									if($nbEntree>0){
										while($rowpersonne=mysqli_fetch_array($resultEntree))
										{
											if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
											else{$couleur="#FFFFFF";}
											
											
											//Prestation et date d'entrée à cette date 
											$req="SELECT Id_Prestation, Id_Pole,
												(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
												(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole
												FROM rh_personne_mouvement
												WHERE Suppr=0
												AND Id_Personne=".$rowpersonne['Id_Personne']." 
												AND EtatValidation=1
												AND rh_personne_mouvement.DateDebut<='".$dateFin."'
												AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."') ";
											$result=mysqli_query($bdd,$req);
											$nb=mysqli_num_rows($result);
											$Prestation="";
											if($nb>0){
												$rowMouv=mysqli_fetch_array($result);
												$Prestation=substr($rowMouv['Prestation'],0,7);
												if($rowMouv['Id_Pole']>0){
													$Prestation.=" - ".$rowMouv['Pole'];
												}
											}
											
											$DateSortie="";
											$Motif="";
											$TypeContrat="";
											$req="SELECT DateFin, 
											(SELECT Libelle FROM rh_motifsortie WHERE Id=Id_MotifSortie) AS Motif,
											(SELECT LibelleEN FROM rh_motifsortie WHERE Id=Id_MotifSortie) AS MotifEN,
											(SELECT Code FROM rh_typecontrat WHERE Id=Id_TypeContrat) AS TypeContrat
											FROM rh_personne_contrat
											WHERE Suppr=0
											AND Id_TypeContrat IN (SELECT Id FROM rh_typecontrat WHERE Suppr=0 AND EstSalarie=1)
											AND Id_Personne=".$rowpersonne['Id_Personne']."
											AND DateDebut<='".$dateFin."'
											AND (DateFin>='".$dateDebut."' OR DateFin<='0001-01-01' )
											AND TypeDocument IN ('Nouveau','Avenant')
											ORDER BY DateDebut ASC";

											$resultDate=mysqli_query($bdd,$req);
											$nbDate=mysqli_num_rows($resultDate);
											if($nbDate>0){
												$rowDate=mysqli_fetch_array($resultDate);
												$DateSortie=AfficheDateJJ_MM_AAAA($rowDate['DateFin']);
												if($_SESSION['Langue']=="FR"){$Motif=stripslashes($rowDate['Motif']);}
												else{$Motif=stripslashes($rowDate['MotifEN']);}
												$TypeContrat=$rowDate['TypeContrat'];
											}
											
											echo "<tr bgcolor=".$couleur.">";
												echo "<td>".$rowpersonne['Personne']."</td>";
												echo "<td>".$Prestation."</td>";
												echo "<td>".$DateSortie."</td>";
												echo "<td>".$Motif."</td>";
												echo "<td>".$TypeContrat."</td>";
											echo "</tr>";
										}
									}
								?>
							</table>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
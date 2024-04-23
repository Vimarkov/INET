<?php
require("../../Menu.php");

if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

if(isset($_GET['Tri'])){
	$tab = array("Personne","Prestation","Pole","Semaine","NbHeures");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriRHHSHistorique_General']= str_replace($tri." ASC,","",$_SESSION['TriRHHSHistorique_General']);
			$_SESSION['TriRHHSHistorique_General']= str_replace($tri." DESC,","",$_SESSION['TriRHHSHistorique_General']);
			$_SESSION['TriRHHSHistorique_General']= str_replace($tri." ASC","",$_SESSION['TriRHHSHistorique_General']);
			$_SESSION['TriRHHSHistorique_General']= str_replace($tri." DESC","",$_SESSION['TriRHHSHistorique_General']);
			if($_SESSION['TriRHHSHistorique_'.$tri]==""){$_SESSION['TriRHHSHistorique_'.$tri]="ASC";$_SESSION['TriRHHSHistorique_General'].= $tri." ".$_SESSION['TriRHHSHistorique_'.$tri].",";}
			elseif($_SESSION['TriRHHSHistorique_'.$tri]=="ASC"){$_SESSION['TriRHHSHistorique_'.$tri]="DESC";$_SESSION['TriRHHSHistorique_General'].= $tri." ".$_SESSION['TriRHHSHistorique_'.$tri].",";}
			else{$_SESSION['TriRHHSHistorique_'.$tri]="";}
		}
	}
}

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
function Titre1($Libelle,$Lien,$Selected){
	$tiret="";
	if($Selected==true){$tiret="border-bottom:4px solid white;";}
	echo "<td style=\"width:70px;height:30px;border-spacing:0;text-align:center;color:#0a7b6f;valign:top;font-weight:bold;".$tiret."\">
		<a style=\"text-decoration:none;width:70px;height:30px;border-spacing:0;text-align:center;color:#0a7b6f;valign:top;font-weight:bold;\" onmouseover=\"this.style.color='#0a7b6f';\" onmouseout=\"this.style.color='#0a7b6f';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle."</a></td>\n";
}
?>

<form class="test" action="Liste_HeureSuppHistorique.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $TDB; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $OngletTDB; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#11b9a7;">
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
						
					if($LangueAffichage=="FR"){echo "Liste des heures supplémentaires";}else{echo "List of overtime hours";}
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
				<tr bgcolor="#cafaf5">
					<?php
						$ParametreTDB="";
						if($TDB>0){$ParametreTDB="&TDB=".$TDB;}
						if($OngletTDB<>""){$ParametreTDB.="&OngletTDB=".$OngletTDB;}
						if($_SESSION["Langue"]=="FR"){Titre1("HEURES SUPPLEMENTAIRES EN COURS","Outils/PlanningV2/Liste_HeureSupp.php?Menu=".$Menu.$ParametreTDB,false);}
						else{Titre1("ADDITIONAL HOURS IN PROGRESS","Outils/PlanningV2/Liste_HeureSupp.php?Menu=".$Menu."",false);}

						if($_SESSION["Langue"]=="FR"){Titre1("HISTORIQUE","Outils/PlanningV2/Liste_HeureSuppHistorique.php?Menu=".$Menu.$ParametreTDB,true);}
						else{Titre1("HISTORICAL","Outils/PlanningV2/Liste_HeureSuppHistorique.php?Menu=".$Menu."",true);}
					?>
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
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?>
				<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
				<?php
				if($Menu==4){
					if(DroitsFormationPlateforme($TableauIdPostesRH)){
						$requeteSite="SELECT Id, Libelle
							FROM new_competences_prestation
							WHERE Id_Plateforme IN 
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
								)
							AND Active=0
							ORDER BY Libelle ASC";
					}
				}
				elseif($Menu==3){
					if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
						$requeteSite="SELECT Id, Libelle
							FROM new_competences_prestation
							WHERE Id_Plateforme IN 
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
								)
							AND Active=0
							ORDER BY Libelle ASC";
					}
					else{
						$requeteSite="SELECT Id, Libelle
							FROM new_competences_prestation
							WHERE Id IN 
								(SELECT Id_Prestation 
								FROM new_competences_personne_poste_prestation 
								WHERE Id_Personne=".$_SESSION["Id_Personne"]."
								AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
								)
							AND Active=0
							ORDER BY Libelle ASC";
						
					}
				}
				elseif($Menu==2){
					$requeteSite="SELECT DISTINCT new_competences_prestation.Id, 
							new_competences_prestation.Libelle
							FROM rh_personne_hs
							LEFT JOIN new_competences_prestation
							ON new_competences_prestation.Id=rh_personne_hs.Id_Prestation
							WHERE rh_personne_hs.Id_Personne=".$_SESSION['Id_Personne']."
							ORDER BY Libelle ASC";
				}
				$resultPrestation=mysqli_query($bdd,$requeteSite);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationSelect = 0;
				$Selected = "";
				
				$PrestationSelect=$_SESSION['FiltreRHHSHistorique_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestations'];}
				$_SESSION['FiltreRHHSHistorique_Prestation']=$PrestationSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				if ($nbPrestation > 0)
				{
					while($row=mysqli_fetch_array($resultPrestation))
					{
						$selected="";
						if($PrestationSelect<>"")
							{if($PrestationSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?>
				<select class="pole" style="width:100px;" name="pole" onchange="submit();">
				<?php

				if($Menu==4){
					if(DroitsFormationPlateforme($TableauIdPostesRH)){
						$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
								FROM new_competences_pole
								LEFT JOIN new_competences_prestation
								ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
								WHERE Id_Plateforme IN 
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
								)
								AND Actif=0
								AND new_competences_pole.Id_Prestation=".$PrestationSelect."
								ORDER BY new_competences_pole.Libelle ASC";
					}
				}
				elseif($Menu==3){
					if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
						$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
								FROM new_competences_pole
								LEFT JOIN new_competences_prestation
								ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
								WHERE Id_Plateforme IN 
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
								)
								AND Actif=0
								AND new_competences_pole.Id_Prestation=".$PrestationSelect."
								ORDER BY new_competences_pole.Libelle ASC";
					}
					else{
						$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
							FROM new_competences_pole
							LEFT JOIN new_competences_prestation
							ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
							WHERE new_competences_pole.Id IN 
								(SELECT Id_Pole 
								FROM new_competences_personne_poste_prestation 
								WHERE Id_Personne=".$_SESSION["Id_Personne"]."
								AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
								)
							AND Actif=0
							AND new_competences_pole.Id_Prestation=".$PrestationSelect."
							ORDER BY new_competences_pole.Libelle ASC";
					}
				}
				elseif($Menu==2){
					$requetePole="SELECT DISTINCT new_competences_pole.Id, 
							new_competences_pole.Libelle
							FROM rh_personne_hs
							LEFT JOIN new_competences_pole
							ON new_competences_pole.Id=rh_personne_hs.Id_Pole
							WHERE rh_personne_hs.Id_Personne=".$_SESSION['Id_Personne']."
							AND new_competences_pole.Id_Prestation=".$PrestationSelect."
							ORDER BY Libelle ASC";
				}
				$resultPole=mysqli_query($bdd,$requetePole);
				$nbPole=mysqli_num_rows($resultPole);
				
				$PoleSelect=$_SESSION['FiltreRHHSHistorique_Pole'];
				if($_POST){$PoleSelect=$_POST['pole'];}
				$_SESSION['FiltreRHHSHistorique_Pole']=$PoleSelect;
				
				$PoleSelect = 0;
				$Selected = "";
				echo "<option name='0' value='0' Selected></option>";
				if ($nbPole > 0)
				{
					while($row=mysqli_fetch_array($resultPole))
					{
						$selected="";
						if($PoleSelect<>"")
						{if($PoleSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="20%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Mois :";}else{echo "Month :";} ?>
				<select id="mois" name="mois" onchange="submit();">
					<option value='0' selected></option>
					<?php
						if($_SESSION["Langue"]=="FR"){
							$arrayMois=array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
						}
						else{
							$arrayMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
						}
						$mois=$_SESSION['FiltreRHHSHistorique_Mois'];
						if($_POST){$mois=$_POST['mois'];}
						$_SESSION['FiltreRHHSHistorique_Mois']=$mois;
						
						for($i=0;$i<=11;$i++){
							echo "<option value='".($i+1)."'";
							if($mois== ($i+1)){echo " selected ";}
							echo ">".$arrayMois[$i]."</option>\n";
						}
						
						$annee=$_SESSION['FiltreRHHSHistorique_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						if($annee==""){$annee=date("Y");}
						$_SESSION['FiltreRHHSHistorique_Annee']=$annee;
					?>
				</select>
				<?php
					$MoisCumules=$_SESSION['FiltreRHHSHistorique_MoisCumules'];
					if($_POST){
						if(isset($_POST['MoisCumules'])){$MoisCumules="checked";}else{$MoisCumules="";}				
					}
					$_SESSION['FiltreRHHSHistorique_MoisCumules']=$MoisCumules;
				?>
				<input type="checkbox" id="MoisCumules" name="MoisCumules" value="MoisCumules" <?php echo $MoisCumules; ?>><?php if($_SESSION["Langue"]=="FR"){echo "Jusqu'à la fin de l'année";}else{echo "Until the end of the year";} ?> &nbsp;&nbsp;
			</td>
			<td width="8%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Année :";}else{echo "Year :";} ?>
				<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
			</td>
			<td width="5%">
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td width="15%" class="Libelle" <?php if($Menu==2){echo "style='display:none;'";} ?>>
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?>
				<select id="personne" style="width:100px;" name="personne" onchange="submit();">
					<option value='0'></option>
					<?php
						if($Menu==4){
							if(DroitsFormationPlateforme($TableauIdPostesRH)){
								$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
										CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
										FROM rh_personne_hs
										LEFT JOIN new_rh_etatcivil
										ON new_rh_etatcivil.Id=rh_personne_hs.Id_Personne
										WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_hs.Id_Prestation) IN 
										(
											SELECT Id_Plateforme 
											FROM new_competences_personne_poste_plateforme
											WHERE Id_Personne=".$_SESSION['Id_Personne']." 
											AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
										)
										ORDER BY Personne ASC";
							}
						}
						elseif($Menu==3){
							if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
								$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
										CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
										FROM rh_personne_hs
										LEFT JOIN new_rh_etatcivil
										ON new_rh_etatcivil.Id=rh_personne_hs.Id_Personne
										WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_hs.Id_Prestation) IN 
										(
											SELECT Id_Plateforme 
											FROM new_competences_personne_poste_plateforme
											WHERE Id_Personne=".$_SESSION['Id_Personne']." 
											AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
										)
										ORDER BY Personne ASC";
							}
							else{
								$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
									CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
									FROM rh_personne_hs
									LEFT JOIN new_rh_etatcivil
									ON new_rh_etatcivil.Id=rh_personne_hs.Id_Personne
									WHERE CONCAT(rh_personne_hs.Id_Prestation,'_',rh_personne_hs.Id_Pole) IN 
										(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
										FROM new_competences_personne_poste_prestation 
										WHERE Id_Personne=".$_SESSION["Id_Personne"]."
										AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
										)
									ORDER BY Personne ASC";
							}
						}
						elseif($Menu==2){
							$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
									CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
									FROM rh_personne_hs
									LEFT JOIN new_rh_etatcivil
									ON new_rh_etatcivil.Id=rh_personne_hs.Id_Personne
									WHERE rh_personne_hs.Id_Personne=".$_SESSION['Id_Personne']."
									ORDER BY Personne ASC";
						}
					
						$resultPersonne=mysqli_query($bdd,$requetePersonne);
						$NbPersonne=mysqli_num_rows($resultPersonne);
						
						$personne=$_SESSION['FiltreRHHSHistorique_Personne'];
						if($_POST){$personne=$_POST['personne'];}
						$_SESSION['FiltreRHHSHistorique_Personne']= $personne;
						
						while($rowPersonne=mysqli_fetch_array($resultPersonne))
						{
							echo "<option value='".$rowPersonne['Id']."'";
							if ($personne == $rowPersonne['Id']){echo " selected ";}
							echo ">".$rowPersonne['Personne']."</option>\n";
						}
					?>
				</select>
			</td>
			<td colspan="6" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Etat :";}else{echo "State :";} ?>
					<?php
						$EnCours=$_SESSION['FiltreRHHSHistorique_EtatEnCours'];
						$TransmisRH=$_SESSION['FiltreRHHSHistorique_EtatTransmiRH'];
						$Validee=$_SESSION['FiltreRHHSHistorique_EtatValide'];
						$Refusee=$_SESSION['FiltreRHHSHistorique_EtatRefuse'];
						$Supprimee=$_SESSION['FiltreRHHSHistorique_EtatSupprime'];
						if($_POST){
							if(isset($_POST['EnCours'])){$EnCours="checked";}else{$EnCours="";}
							if(isset($_POST['TransmisRH'])){$TransmisRH="checked";}else{$TransmisRH="";}
							if(isset($_POST['Validee'])){$Validee="checked";}else{$Validee="";}
							if(isset($_POST['Refusee'])){$Refusee="checked";}else{$Refusee="";}
							if(isset($_POST['Supprimee'])){$Supprimee="checked";}else{$Supprimee="";}
							
						}
						$_SESSION['FiltreRHHSHistorique_EtatEnCours']=$EnCours;
						$_SESSION['FiltreRHHSHistorique_EtatTransmiRH']=$TransmisRH;
						$_SESSION['FiltreRHHSHistorique_EtatValide']=$Validee;
						$_SESSION['FiltreRHHSHistorique_EtatRefuse']=$Refusee;
						$_SESSION['FiltreRHHSHistorique_EtatSupprime']=$Supprimee;
					?>
					<input type="checkbox" id="EnCours" name="EnCours" value="EnCours" <?php echo $EnCours; ?>><?php if($_SESSION["Langue"]=="FR"){echo "EN COURS";}else{echo "IN PROGRESS";} ?> &nbsp;&nbsp;
					<input type="checkbox" id="TransmisRH" name="TransmisRH" value="TransmisRH" <?php echo $TransmisRH; ?>><?php if($_SESSION["Langue"]=="FR"){echo "EN TRAITEMENT RH";}else{echo "IN HR TREATMENT";} ?> &nbsp;&nbsp;
					<input type="checkbox" id="Validee" name="Validee" value="Validee" <?php echo $Validee; ?>><?php if($_SESSION["Langue"]=="FR"){echo "VALIDEE";}else{echo "VALIDATED";} ?> &nbsp;&nbsp;
					<input type="checkbox" id="Refusee" name="Refusee" value="Refusee" <?php echo $Refusee; ?>><?php if($_SESSION["Langue"]=="FR"){echo "REFUSEE";}else{echo "REFUSED";} ?> &nbsp;&nbsp;
					<input type="checkbox" id="Supprimee" name="Supprimee" value="Supprimee" <?php echo $Supprimee; ?>><?php if($_SESSION["Langue"]=="FR"){echo "SUPPRIMEE";}else{echo "DELETED";} ?> &nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<?php
		$requeteAnalyse="SELECT SUM(Nb_Heures_Jour+Nb_Heures_Nuit) AS NbHeures,WEEK(IF(DateRH>'0001-01-01',DateRH,DateHS),1) AS Semaine,
			Id_Personne,Id_Prestation,Id_Pole ";
		$requete2="SELECT SUM(Nb_Heures_Jour+Nb_Heures_Nuit) AS NbHeures,WEEK(IF(DateRH>'0001-01-01',DateRH,DateHS),1) AS Semaine,
			Id_Personne,Id_Prestation,Id_Pole,
			(SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_hs.Id_Prestation) AS Prestation, 
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_hs.Id_Personne) AS Personne, 
			(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=rh_personne_hs.Id_Pole) AS Pole ";
		$requete=" FROM rh_personne_hs
					WHERE (Suppr=0 OR Suppr=1) AND ";
		if($Menu==4){
			if(DroitsFormationPlateforme($TableauIdPostesRH)){
				$requete.="(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_hs.Id_Prestation) IN 
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
					)";
			}
		}
		elseif($Menu==3){
			if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
				$requete.="(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_hs.Id_Prestation) IN 
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
					)";
			}
			else{
				$requete.="CONCAT(rh_personne_hs.Id_Prestation,'_',rh_personne_hs.Id_Pole) IN 
							(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
							)";
			}
		}
		elseif($Menu==2){
			$requete.="rh_personne_hs.Id_Personne=".$_SESSION['Id_Personne']." ";
		}
		
		if($_SESSION['FiltreRHHSHistorique_Prestation']<>0){
			$requete.=" AND rh_personne_hs.Id_Prestation=".$_SESSION['FiltreRHHSHistorique_Prestation']." ";
			if($_SESSION['FiltreRHHSHistorique_Pole']<>0){
				$requete.=" AND rh_personne_hs.Id_Pole=".$_SESSION['FiltreRHHSHistorique_Pole']." ";
			}
		}
		if($Menu<>2){
			if($_SESSION['FiltreRHHSHistorique_Personne']<>0){
				$requete.=" AND rh_personne_hs.Id_Personne=".$_SESSION['FiltreRHHSHistorique_Personne']." ";
			}
		}
		$requete.="AND YEAR(IF(DateRH>'0001-01-01',DateRH,DateHS))='".$_SESSION['FiltreRHHSHistorique_Annee']."' ";
		if($_SESSION['FiltreRHHSHistorique_Mois']<>0){
			if($_SESSION['FiltreRHHSHistorique_MoisCumules']<>""){
				$requete.="AND MONTH(IF(DateRH>'0001-01-01',DateRH,DateHS))>='".$_SESSION['FiltreRHHSHistorique_Mois']."' ";
			}
			else{
				$requete.="AND MONTH(IF(DateRH>'0001-01-01',DateRH,DateHS))='".$_SESSION['FiltreRHHSHistorique_Mois']."' ";
			}
		}
		
		if($_SESSION['FiltreRHHSHistorique_EtatEnCours']<>"" || $_SESSION['FiltreRHHSHistorique_EtatTransmiRH']<>"" || $_SESSION['FiltreRHHSHistorique_EtatValide']<>"" || $_SESSION['FiltreRHHSHistorique_EtatRefuse']<>""){
			$requete.=" AND ( ";
			if($_SESSION['FiltreRHHSHistorique_EtatEnCours']<>""){
				$requete.=" (rh_personne_hs.Etat4=0 AND rh_personne_hs.Etat3<>-1 AND rh_personne_hs.Etat2<>-1) OR ";
			}
			if($_SESSION['FiltreRHHSHistorique_EtatTransmiRH']<>""){
				$requete.=" (rh_personne_hs.DatePriseEnCompteRH<='0001-01-01' AND rh_personne_hs.Etat4=1 AND rh_personne_hs.Etat3=1 AND rh_personne_hs.Etat2=1) OR ";
			}
			if($_SESSION['FiltreRHHSHistorique_EtatValide']<>""){
				$requete.=" (rh_personne_hs.Etat4=1 AND rh_personne_hs.DatePriseEnCompteRH>'0001-01-01') OR ";
			}
			if($_SESSION['FiltreRHHSHistorique_EtatRefuse']<>""){
				$requete.=" (rh_personne_hs.Etat4=-1 OR rh_personne_hs.Etat3=-1 OR rh_personne_hs.Etat2=-1) OR ";
			}
			$requete=substr($requete,0,-3);
			$requete.=" ) ";
			
			if($_SESSION['FiltreRHHSHistorique_EtatSupprime']<>""){
				$requete.=" AND (Suppr=0 OR Suppr=1) ";
			}
			else{
				$requete.=" AND Suppr=0 ";
			}
		}
		else{
			$requete.=" AND ( ";
			$requete.=" (rh_personne_hs.Etat4=0 AND rh_personne_hs.Etat3<>-1 AND rh_personne_hs.Etat2<>-1) ";
			$requete.=" ) ";
			
			if($_SESSION['FiltreRHHSHistorique_EtatSupprime']<>""){
				$requete.=" AND (Suppr=1) ";
			}
			else{
				$requete.=" AND Suppr=0 ";
			}
		}
		
		$requete.=" GROUP BY Id_Personne, Semaine ";
		$requeteOrder="";
		if($_SESSION['TriRHHSHistorique_General']<>""){
			$requeteOrder="ORDER BY ".substr($_SESSION['TriRHHSHistorique_General'],0,-1);
		}
		
		$result=mysqli_query($bdd,$requeteAnalyse.$requete);
		
		if(isset($_GET['Page'])){$page=$_GET['Page'];}
		else{$page=0;}
		$requete3=" LIMIT ".($page*40).",40";
		$nbResulta=mysqli_num_rows($result);
		$result=mysqli_query($bdd,$requete2.$requete.$requeteOrder.$requete3);
		$nombreDePages=ceil($nbResulta/40);
		$couleur="#FFFFFF";

	?>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				$nbPage=0;
				if($page>1){echo "<b> <a style='color:#00599f;' href='Liste_HeureSuppHistorique.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=0'><<</a> </b>";}
				$valeurDepart=1;
				if($page<=5){
					$valeurDepart=1;
				}
				elseif($page>=($nombreDePages-6)){
					$valeurDepart=$nombreDePages-6;
				}
				else{
					$valeurDepart=$page-5;
				}
				for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
					if($i<=$nombreDePages){
						if($i==($page+1)){
							echo "<b> [ ".$i." ] </b>"; 
						}	
						else{
							echo "<b> <a style='color:#00599f;' href='Liste_HeureSuppHistorique.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($page<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_HeureSuppHistorique.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_HeureSuppHistorique.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Personne"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriRHHSHistorique_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHHSHistorique_Personne']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_HeureSuppHistorique.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Prestation"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?><?php if($_SESSION['TriRHHSHistorique_Prestation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHHSHistorique_Prestation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_HeureSuppHistorique.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Pole"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle";}else{echo "Pole";} ?><?php if($_SESSION['TriRHHSHistorique_Pole']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHHSHistorique_Pole']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_HeureSuppHistorique.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Semaine"><?php if($_SESSION["Langue"]=="FR"){echo "Semaine";}else{echo "Week";} ?><?php if($_SESSION['TriRHHSHistorique_Semaine']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHHSHistorique_Semaine']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_HeureSuppHistorique.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=NbHeures"><?php if($_SESSION["Langue"]=="FR"){echo "Nbr d'heures";}else{echo "Number  of hour";} ?><?php if($_SESSION['TriRHHSHistorique_NbHeures']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHHSHistorique_NbHeures']=="ASC"){echo "&darr;";}?></a></td>
				</tr>
	<?php
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
					
		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td><?php echo stripslashes($row['Personne']);?></td>
						<td><?php echo substr(stripslashes($row['Prestation']),0,7);?></td>
						<td><?php echo stripslashes($row['Pole']);?></td>
						<td><?php echo $row['Semaine'];?></td>
						<td><?php echo $row['NbHeures'];?></td>
					</tr>
				<?php
				}	//Fin boucle
			}
			?>
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
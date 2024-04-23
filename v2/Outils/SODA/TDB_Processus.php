<?php
$couleurPlanif = "#ffff00";
$couleurRetard = "#ff0000";
$couleurRealise = "#0070c0";
$couleurCloture = "#92d050";
$couleurRetard = "#e9a1ac";

$Questionnaires="";
$Themes="";
$nbQ=0;
$SeuilReussite2=0;
function valeurSinonNull($lavaleur){
	if($lavaleur==0){return null;}
	else{return $lavaleur;}
}
if($_POST){	
	$Plateformes="";
	$req="SELECT DISTINCT new_competences_plateforme.Id,
		new_competences_plateforme.Libelle
		FROM new_competences_prestation
		LEFT JOIN new_competences_plateforme
		ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
		WHERE new_competences_prestation.Id_Plateforme NOT IN (11,14)
		AND new_competences_prestation.SousSurveillance IN ('','Oui/Yes')
		AND new_competences_prestation.Active=0
		AND new_competences_prestation.Id_Plateforme>0 
		ORDER BY new_competences_plateforme.Libelle;";
	$resultPlate=mysqli_query($bdd,$req);
	$nbPlate=mysqli_num_rows($resultPlate);
	if ($nbPlate > 0)
	{
		while($row=mysqli_fetch_array($resultPlate))
		{
			$selected="";
			if($_POST && !isset($_POST['btnReset2'])){
				if(isset($_POST['plateforme'.$row['Id']])){
					if($Plateformes<>''){$Plateformes.=",";}
					$Plateformes.=$row['Id'];
				}
			}
		}
	}
	
	if($_POST && !isset($_POST['btnReset2'])){
		if(isset($_POST['Theme'])){
			if (is_array($_POST['Theme'])) {
				foreach($_POST['Theme'] as $value){
					if($Themes<>''){$Themes.=",";}
				  $Themes.=substr($value,6);
				}
			} else {
				$value = $_POST['Theme'];
				$Themes = substr($value,6);
			}
		}
	}
	
	if($_POST){$annee=$_POST['annee'];}
	if($annee==""){$annee=date("Y");}
	
	if($_POST){$anneeFin=$_POST['anneeFin'];}
	if($anneeFin==""){$anneeFin=date("Y");}
	
	$_SESSION['FiltreSODATDBProcessus_UER']=$Plateformes;
	$_SESSION['FiltreSODATDBProcessus_Theme']=$Themes;
	$_SESSION['FiltreSODATDBOperation_Annee']=$annee;
	$_SESSION['FiltreSODATDBOperation_Mois']=$_POST['mois'];
	$_SESSION['FiltreSODATDBOperation_AnneeFin']=$anneeFin;
	$_SESSION['FiltreSODATDBOperation_MoisFin']=$_POST['moisFin'];
	if(isset($_POST['dateDebut'])){
		$_SESSION['FiltreSODATDBProcessus_DateDebut']=TrsfDate_($_POST['dateDebut']);
	}
	if(isset($_POST['dateFin'])){
		$_SESSION['FiltreSODATDBProcessus_DateFin']=TrsfDate_($_POST['dateFin']);
	}
} 
?>
<input type="hidden" id="Menu" name="Menu" value="<?php echo $Menu; ?>">
<table style="width:100%; border-spacing:0; align:center;">
<tr><td height="4"></td>
<tr>
	<td align="left" valign="top" width="20%">
		<table class="GeneralInfo" style="border-spacing:0; width:70%; align:center;box-shadow: 0 8px 10px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
			<tr>
				<td align="center">
					<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
					<div id="filtrer"></div>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" style="background-color:#B2AE9F;"><?php if($LangueAffichage=="FR"){echo "Plage";}else{echo "Range";}?> : </td>
			</tr>
			<tr>
				<td>
					<?php if($LangueAffichage=="FR"){echo "Du";}else{echo "From";}?>
				</td>
			</tr>
			<tr>
				<td>
					<?php if($LangueAffichage=="FR"){echo "Année";}else{echo "Year";}?>
					<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $_SESSION['FiltreSODATDBOperation_Annee']; ?>" size="5"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php if($_SESSION['Langue']=="EN"){echo "Month";}else{echo "Mois";} ?>&nbsp;&nbsp;
					<select id="mois" name="mois">
						<?php
							if($_SESSION["Langue"]=="EN"){
								$arrayMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
								
							}
							else{
								$arrayMois=array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
							}
							$mois=$_SESSION['FiltreSODATDBOperation_Mois'];
							if($_POST){$mois=$_POST['mois'];}
							$_SESSION['FiltreSODATDBOperation_Mois']=$mois;
							
							for($i=0;$i<=11;$i++){
								$numMois=$i+1;
								if($numMois<10){$numMois="0".$numMois;}
								echo "<option value='".$numMois."'";
								if($mois== ($i+1)){echo " selected ";}
								echo ">".$arrayMois[$i]."</option>\n";
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<?php if($LangueAffichage=="FR"){echo "Au";}else{echo "To";}?>
				</td>
			</tr>
			<tr>
				<td>
					<?php if($LangueAffichage=="FR"){echo "Année";}else{echo "Year";}?>
					<input onKeyUp="nombre(this)" id="anneeFin" name="anneeFin" type="texte" value="<?php echo $_SESSION['FiltreSODATDBOperation_AnneeFin']; ?>" size="5"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php if($_SESSION['Langue']=="EN"){echo "Month";}else{echo "Mois";} ?>&nbsp;&nbsp;
					<select id="moisFin" name="moisFin">
						<?php
							if($_SESSION["Langue"]=="EN"){
								$arrayMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
								
							}
							else{
								$arrayMois=array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
							}
							$moisFin=$_SESSION['FiltreSODATDBOperation_MoisFin'];
							if($_POST){$moisFin=$_POST['moisFin'];}
							$_SESSION['FiltreSODATDBOperation_MoisFin']=$moisFin;
							
							for($i=0;$i<=11;$i++){
								$numMois=$i+1;
								if($numMois<10){$numMois="0".$numMois;}
								echo "<option value='".$numMois."'";
								if($moisFin== ($i+1)){echo " selected ";}
								echo ">".$arrayMois[$i]."</option>\n";
							}
						?>
					</select>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" style="background-color:#B2AE9F;"><?php if($LangueAffichage=="FR"){echo "Thème ";}else{echo "Theme ";}?> : </td>
			</tr>
			<tr>
				<td>
					<input type="checkbox" name="selectAllUER" id="selectAllTheme" onclick="SelectionnerTout('Theme')" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
				</td>
			</tr>
			<tr>
				<td>
					<div id='Div_Theme' style='height:300px;width:300px;overflow:auto;'>
					<table>
						<?php
							$req = "SELECT Id, Libelle
									FROM soda_theme
									WHERE Suppr=0
									ORDER BY soda_theme.Libelle;";
							$resultTheme=mysqli_query($bdd,$req);
							$nbTheme=mysqli_num_rows($resultTheme);
							
							if ($nbTheme > 0){
								while($row=mysqli_fetch_array($resultTheme)){
									echo "<tr><td><input class='checkTheme' type='checkbox' name='Theme[]' value='Theme_".$row['Id']."' ";
									if($_POST){
										if (isset($_POST['Theme'])){
											foreach($_POST['Theme'] as $chkbx){
												if ($chkbx == "Theme_".$row['Id']){
													echo "checked";
												}
											}
										}
									}
									else{
										if($_SESSION['FiltreSODATDBProcessus_Theme']==-1){
											echo "checked";
										}
										else{
											$tabTheme=explode(",",$_SESSION['FiltreSODATDBProcessus_Theme']);
											foreach($tabTheme as $theme){
												if ($theme == $row['Id']){
													echo "checked";
												}
											}
										}
									}
									
									echo ">".$row['Libelle']."";
									echo "</td></tr>". "\n";
								}
							}
						?>
					</table>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "UER/Department/Subsidiary";}else{echo "UER/Dept/Filiale";} ?>&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td>
					<input type="checkbox" name="selectAllUER" id="selectAllUER" onclick="SelectionnerTout('UER')" <?php if($_SESSION['FiltreSODATDBProcessus_UER']==-1){echo "checked";}?> /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
				</td>
			</tr>
			<tr>
				<td>
					<div id='Div_Plateforme' style='height:150px;width:300px;overflow:auto;'>
					<table>
				<?php
					if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaire>0){
					$req="SELECT DISTINCT new_competences_plateforme.Id,
						new_competences_plateforme.Libelle
						FROM new_competences_prestation
						LEFT JOIN new_competences_plateforme
						ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
						WHERE new_competences_prestation.Id_Plateforme NOT IN (11,14)
						AND new_competences_prestation.SousSurveillance IN ('','Oui/Yes')
						AND new_competences_prestation.Active=0
						AND new_competences_prestation.Id_Plateforme>0 
						ORDER BY new_competences_plateforme.Libelle;";
					}
					else{
						$req="SELECT DISTINCT new_competences_plateforme.Id,
						new_competences_plateforme.Libelle
						FROM new_competences_prestation
						LEFT JOIN new_competences_plateforme
						ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
						WHERE new_competences_prestation.Id_Plateforme NOT IN (11,14)
						AND new_competences_prestation.SousSurveillance IN ('','Oui/Yes')
						AND new_competences_prestation.Active=0
						AND new_competences_prestation.Id_Plateforme>0 
						AND (
							(SELECT COUNT(Id) 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION['Id_Personne']."
							AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id 
							AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",
							".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.",".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")
							)>0
							OR
							(SELECT COUNT(Id) 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION['Id_Personne']."
							AND Id_Plateforme=new_competences_prestation.Id_Plateforme 
							AND Id_Poste IN (".$IdPosteResponsableQualite.",".$IdPosteResponsablePlateforme.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.")
							)>0
							)
						ORDER BY new_competences_plateforme.Libelle;";
					}
					$resultPlate=mysqli_query($bdd,$req);
					$nbPlate=mysqli_num_rows($resultPlate);
					if ($nbPlate > 0)
					{
						while($row=mysqli_fetch_array($resultPlate))
						{
							$selected="";
							if($_POST && !isset($_POST['btnReset2'])){
								if(isset($_POST['plateforme'.$row['Id']])){$selected="checked";}
							}
							else{
								if($_SESSION['FiltreSODATDBProcessus_UER']==-1){
									$selected="checked";
								}
								else{
									$tabUER=explode(",",$_SESSION['FiltreSODATDBProcessus_UER']);
									foreach($tabUER as $uer){
										if ($uer == $row['Id']){
											$selected="checked";
										}
									}
								}
							}
							echo "<tr><td><input class='checkUER' type='checkbox' ".$selected." value='".$row['Id']."' onclick=\"Selectionner('Prestation')\" id='plateforme".$row['Id']."' name='plateforme".$row['Id']."'>".stripslashes($row['Libelle'])."</td></tr>";
						}
					}
				?>
					</table>
					</div>
				</td>
			</tr>
			<tr>
				<td align="center">
					<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
					<div id="filtrer"></div>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
		</table>
	</td>
	<td align="center" valign="top" width="80%">
		<table width="99%">
			<?php 
			if($_SESSION['FiltreSODATDBProcessus_Theme']<>"-1" && $_SESSION['FiltreSODATDBProcessus_UER']<>"-1" && $_SESSION['FiltreSODATDBProcessus_Theme']<>"" && $_SESSION['FiltreSODATDBProcessus_UER']<>""){	
					$i=0;
					$arrayNbSurveillanceRealisees=array();
					$arrayNbPrestation=array();
					$arrayNoteMoyenne=array();
					
					$arrayParetoNC=array();
					$arrayParetoNC2=array();
					$arrayParetoNC3=array();
					$seuilReussite=80;
					
					$arrayNbSurveillanceRealiseesProcessus=array();
					$arrayNbPrestationProcessus=array();
					$arrayNoteMoyenneProcessus=array();
					$arrayAdherencePlanningProcessus=array();
					$arrayConformite=array();
					$arrayEcart=array();
					
					$semaine2=date('Y')."S";
					if(date('W')<10){$semaine2.=date('W');}else{$semaine2.=date('W');}
					
					$req = "SELECT soda_surveillance_question.Id_Question,
							(SELECT Libelle FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Questionnaire,
							(SELECT Question FROM soda_question WHERE Id=Id_Question) AS Question,
							COUNT(soda_surveillance_question.Id_Question) AS Nb
							FROM soda_surveillance_question
							LEFT JOIN soda_surveillance 
							ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
							WHERE soda_surveillance.Suppr=0 
							AND soda_surveillance.AutoSurveillance=0
							AND soda_surveillance.Etat='Clôturé'
							AND YEAR(soda_surveillance.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Annee']."
							AND MONTH(soda_surveillance.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Mois']."
							AND YEAR(soda_surveillance.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_AnneeFin']."
							AND MONTH(soda_surveillance.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_MoisFin']."
							AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBProcessus_Theme'].")
							AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
							AND soda_surveillance_question.Etat='NC'
							AND soda_surveillance_question.Id_Question>0
							GROUP BY soda_surveillance_question.Id_Question
							ORDER BY Nb DESC
							LIMIT 15
							";
					$result=mysqli_query($bdd,$req);
					$nbPareto=mysqli_num_rows($result);
					$i=0;
					if($nbPareto>0){
						while($rowPareto=mysqli_fetch_array($result)) {
							$arrayParetoNC[$i]=array("Mois" => utf8_encode($rowPareto['Id_Question']),"Libelle" => utf8_encode($rowPareto['Questionnaire']." <br> ".$rowPareto['Question']),"Nombre" => valeurSinonNull($rowPareto['Nb']));
							$i++;
						}
					}
					$tabParetoNC="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
					$tabParetoNC.="<tr><td style='border:1px solid black;text-align:center;width:10%' colspan='3'>";
					if($_SESSION['Langue']=="EN"){$tabParetoNC.="Non-compliant questions in volume (Top 15)";}else{$tabParetoNC.="Questions non conformes en volume (Top 15)";}
					$tabParetoNC.= "</td></tr>";
						foreach($arrayParetoNC as $nc){
							$tabParetoNC.= "<tr>";
								$tabParetoNC.= "<td style='border:1px solid black;text-align:center;width:10%'>".$nc['Mois']."</td>";
								$tabParetoNC.= "<td style='border:1px solid black;text-align:left;width:80%'>".stripslashes(utf8_decode($nc['Libelle']))."</td>";
								$tabParetoNC.= "<td style='border:1px solid black;text-align:center;width:10%'>".$nc['Nombre']."</td>";
							$tabParetoNC.= "</tr>";
						}
					$tabParetoNC.="</table>";
					
					$arrayParetoNC=array_reverse($arrayParetoNC);
					
					$req = "SELECT soda_surveillance_question.Id_Question,
							(SELECT Libelle FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Questionnaire,
							(SELECT Question FROM soda_question WHERE Id=Id_Question) AS Question,
								COUNT(soda_surveillance_question.Id_Question) AS NbQuestion,
								(SELECT COUNT(tab.Id) 
								FROM soda_surveillance AS tab 
								WHERE tab.Suppr=0 
								AND tab.AutoSurveillance=0
								AND tab.Etat='Clôturé'
								AND YEAR(tab.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Annee']."
								AND MONTH(tab.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Mois']."
								AND YEAR(tab.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_AnneeFin']."
								AND MONTH(tab.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_MoisFin']."
								AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=tab.Id_Prestation) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
								AND tab.Id IN (SELECT Id_Surveillance FROM soda_surveillance_question AS tab2 WHERE tab2.Id_Surveillance=tab.Id AND tab2.Id_Question=soda_surveillance_question.Id_Question)
								) AS NbSurveillance,
								(
								COUNT(soda_surveillance_question.Id_Question)/
								(SELECT COUNT(tab.Id) 
								FROM soda_surveillance AS tab 
								WHERE tab.Suppr=0 
								AND tab.AutoSurveillance=0
								AND tab.Etat='Clôturé'
								AND YEAR(tab.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Annee']."
								AND MONTH(tab.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Mois']."
								AND YEAR(tab.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_AnneeFin']."
								AND MONTH(tab.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_MoisFin']."
								AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=tab.Id_Prestation) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
								AND tab.Id IN (SELECT Id_Surveillance FROM soda_surveillance_question AS tab2 WHERE tab2.Id_Surveillance=tab.Id AND tab2.Id_Question=soda_surveillance_question.Id_Question)
								)
								) AS Nb
							FROM soda_surveillance_question
							LEFT JOIN soda_surveillance 
							ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
							WHERE soda_surveillance.Suppr=0 
							AND soda_surveillance.AutoSurveillance=0
							AND soda_surveillance.Etat='Clôturé'
							AND YEAR(soda_surveillance.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Annee']."
							AND MONTH(soda_surveillance.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Mois']."
							AND YEAR(soda_surveillance.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_AnneeFin']."
							AND MONTH(soda_surveillance.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_MoisFin']."
							AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBProcessus_Theme'].")
							AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
							AND soda_surveillance_question.Etat='NC'
							AND soda_surveillance_question.Id_Question>0
							GROUP BY soda_surveillance_question.Id_Question
							ORDER BY 
								Nb DESC, NbQuestion DESC
							LIMIT 15
							";
					$result2=mysqli_query($bdd,$req);
					$nbPareto2=mysqli_num_rows($result2);
					$i=0;
					if($nbPareto2>0){
						while($rowPareto2=mysqli_fetch_array($result2)) {
							$arrayParetoNC2[$i]=array("Mois" => utf8_encode($rowPareto2['Id_Question']),"Libelle" => utf8_encode($rowPareto2['Questionnaire']." <br> ".$rowPareto2['Question']),"Nombre" => valeurSinonNull(round($rowPareto2['Nb']*100)));
							$arrayParetoNC3[$i]=array("Mois" => utf8_encode($rowPareto2['Id_Question']),"Libelle" => utf8_encode($rowPareto2['Questionnaire']." <br> ".$rowPareto2['Question']),"NbQuestion" => valeurSinonNull($rowPareto2['NbSurveillance']),"NbNC" => valeurSinonNull($rowPareto2['NbQuestion']),"Erreur" => valeurSinonNull(round($rowPareto2['Nb']*100,1)));
							$i++;
						}
					}
					$tabParetoNC2="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
					$tabParetoNC2.="<tr><td style='border:1px solid black;text-align:center;width:10%' colspan='3'>";
					if($_SESSION['Langue']=="EN"){$tabParetoNC2.="Non-compliant questions in ratio (Top 15)";}else{$tabParetoNC2.="Questions non conformes en ratio (Top 15)";}
					$tabParetoNC2.= "</td></tr>";
						foreach($arrayParetoNC2 as $nc){
							$tabParetoNC2.= "<tr>";
								$tabParetoNC2.= "<td style='border:1px solid black;text-align:center;width:10%'>".$nc['Mois']."</td>";
								$tabParetoNC2.= "<td style='border:1px solid black;text-align:left;width:80%'>".stripslashes(utf8_decode($nc['Libelle']))."</td>";
								$tabParetoNC2.= "<td style='border:1px solid black;text-align:center;width:10%'>".$nc['Nombre']."%</td>";
							$tabParetoNC2.= "</tr>";
						}
					$tabParetoNC2.="</table>";
					$arrayParetoNC2=array_reverse($arrayParetoNC2);
					
					$req = "SELECT Id, Libelle AS Intitule
						FROM soda_theme 
						WHERE Id IN (".$_SESSION['FiltreSODATDBProcessus_Theme'].")
						AND Id<>8
						ORDER BY Libelle
						";
					$result=mysqli_query($bdd,$req);
					$nbTheme=mysqli_num_rows($result);
					$i=0;
					if($nbTheme>0){
						while($row2=mysqli_fetch_array($result)){
							$intitule2=str_replace("/","\n",$row2['Intitule']);
							
							$pourcentageApplicabilite=0;
							$pourcentageDiversite=0;
							$req="SELECT PourcentageApplicabilite, PourcentageDiversite
							FROM soda_objectif_theme
							WHERE Annee=".$_SESSION['FiltreSODATDBOperation_AnneeFin']." 
							AND Id_Theme=".$row2['Id']." ";
							$resultObj=mysqli_query($bdd,$req);
							$nbObj=mysqli_num_rows($resultObj);
							if ($nbObj > 0)
							{
								$rowT=mysqli_fetch_array($resultObj);
								$pourcentageApplicabilite=$rowT['PourcentageApplicabilite']/100;
								$pourcentageDiversite=$rowT['PourcentageDiversite']/100;
							}
							$objectifSurveillance=0;
							$objectifPrestation=0;
							
							$req="SELECT Id
								FROM new_competences_plateforme
								WHERE Id IN (".$_SESSION['FiltreSODATDBProcessus_UER'].") ";
							$resultUER=mysqli_query($bdd,$req);
							$nbUER=mysqli_num_rows($resultUER);
							if($nbUER>0){
								while($rowUER=mysqli_fetch_array($resultUER)){
									$req="SELECT Id,Libelle
										FROM new_competences_prestation
										WHERE Id_Plateforme NOT IN (11,14)
										AND SousSurveillance IN ('','Oui/Yes')
										AND Active=0 
										AND Id_Plateforme=".$rowUER['Id']." ";
									$resultPresta=mysqli_query($bdd,$req);
									$nbPresta=mysqli_num_rows($resultPresta);
									
									$req="SELECT SUM(Volume) AS Vol
									FROM soda_plannifmanuelle 
									WHERE Annee=".$_SESSION['FiltreSODATDBOperation_AnneeFin']." 
									AND Id_Questionnaire IN (SELECT Id FROM soda_questionnaire WHERE Suppr=0 AND Actif=0 AND Id_Theme=".$row2['Id']." AND Specifique=0) 
									AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=".$rowUER['Id']." ";
									$resultVolumePlanifie=mysqli_query($bdd,$req);
									$nbVolumePlanifie=mysqli_num_rows($resultVolumePlanifie);
									
									$volumeObjectif=round(($nbPresta*$pourcentageApplicabilite),0);
									$objectifDiversite=round(($nbPresta*$pourcentageDiversite),0);
									$objectifSurveillance+=$volumeObjectif;
									$objectifPrestation+=$objectifDiversite;
								}
							}

							$req = "SELECT Id
									FROM soda_surveillance 
									WHERE Suppr=0 
									AND AutoSurveillance=0
									AND Etat='Clôturé'
									AND YEAR(soda_surveillance.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Annee']."
									AND MONTH(soda_surveillance.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Mois']."
									AND YEAR(soda_surveillance.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_AnneeFin']."
									AND MONTH(soda_surveillance.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_MoisFin']."
									AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
									AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
									";
							$result2=mysqli_query($bdd,$req);
							$nbCloture=mysqli_num_rows($result2);
							
							$req = "SELECT DISTINCT Id_Prestation
									FROM soda_surveillance 
									WHERE Suppr=0 
									AND AutoSurveillance=0
									AND Etat='Clôturé'
									AND YEAR(soda_surveillance.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Annee']."
									AND MONTH(soda_surveillance.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Mois']."
									AND YEAR(soda_surveillance.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_AnneeFin']."
									AND MONTH(soda_surveillance.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_MoisFin']."
									AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
									AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
									";
							$result2=mysqli_query($bdd,$req);
							$nbPrestation=mysqli_num_rows($result2);
							
							
							$reqNote = "SELECT ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
									FROM soda_surveillance 
									WHERE Suppr=0 
									AND AutoSurveillance=0
									AND Etat='Clôturé'
									AND YEAR(soda_surveillance.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Annee']."
									AND MONTH(soda_surveillance.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Mois']."
									AND YEAR(soda_surveillance.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_AnneeFin']."
									AND MONTH(soda_surveillance.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_MoisFin']."
									AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
									AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
									";
							$resultNote=mysqli_query($bdd,$reqNote);
							$nbNote=mysqli_num_rows($resultNote);
							$noteMoyenne=0;
							if($nbNote>0){
								$rowNote=mysqli_fetch_array($resultNote);
								$noteMoyenne=$rowNote['NoteMoyenne'];
							}
							
							$pourcentageRealise=null;
							$pourcentagePrestation=null;
							if($objectifSurveillance>0){
								$pourcentageRealise=round(($nbCloture/$objectifSurveillance)*100,2);
							}
							if($objectifPrestation>0){
								$pourcentagePrestation=round(($nbPrestation/$objectifPrestation)*100,2);
							}
							$arrayNoteMoyenne[$i]=array("Mois" => utf8_encode($intitule2),"NoteMoyenne" => valeurSinonNull($noteMoyenne),"Seuil" => valeurSinonNull($seuilReussite));
							$arrayNbSurveillanceRealisees[$i]=array("Mois" => utf8_encode($intitule2),"NbSurveillance" => utf8_encode($nbCloture),"Objectif" => utf8_encode($objectifSurveillance),"Pourcentage" => utf8_encode($pourcentageRealise));
							$arrayNbPrestation[$i]=array("Mois" => utf8_encode($intitule2),"NbPrestation" => utf8_encode($nbPrestation),"Objectif" => utf8_encode($objectifPrestation),"Pourcentage" => utf8_encode($pourcentagePrestation));
							$i++;
						}
					}
					
					$req = "SELECT Id, Libelle AS Intitule
						FROM soda_questionnaire 
						WHERE Id_Theme=8
						ORDER BY Libelle
						";
					$result=mysqli_query($bdd,$req);
					$nbTheme=mysqli_num_rows($result);
					$i=0;
					if($nbTheme>0){
						while($row2=mysqli_fetch_array($result)){
							$intitule2=str_replace("[","(",$row2['Intitule']);
							$intitule2=str_replace("]",")",$intitule2);
							
							$objectifSurveillance=0;
							$objectifPrestation=0;
							
							$req="SELECT SUM(NbSurveillance) AS Nb
							FROM soda_objectif_theme
							WHERE Annee=".$_SESSION['FiltreSODATDBOperation_AnneeFin']." 
							AND Id_Plateforme IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
							AND Id_Questionnaire=".$row2['Id']." ";
							$resultObj=mysqli_query($bdd,$req);
							$nbObj=mysqli_num_rows($resultObj);
							if ($nbObj > 0)
							{
								$rowT=mysqli_fetch_array($resultObj);
								$objectifSurveillance=$rowT['Nb'];
							}
							
							$req="SELECT DISTINCT Id_Plateforme
							FROM soda_objectif_theme
							WHERE Annee=".$_SESSION['FiltreSODATDBOperation_AnneeFin']." 
							AND Id_Plateforme IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
							AND Id_Plateforme>0
							AND NbSurveillance>0
							AND Id_Questionnaire=".$row2['Id']." ";
							$resultObj=mysqli_query($bdd,$req);
							$objectifPrestation=mysqli_num_rows($resultObj);
							
							$req = "SELECT Id
									FROM soda_surveillance 
									WHERE Suppr=0 
									AND AutoSurveillance=0
									AND Etat='Clôturé'
									AND YEAR(soda_surveillance.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Annee']."
									AND MONTH(soda_surveillance.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Mois']."
									AND YEAR(soda_surveillance.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_AnneeFin']."
									AND MONTH(soda_surveillance.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_MoisFin']."
									AND Id_Questionnaire = ".$row2['Id']."
									AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
									";
							$result2=mysqli_query($bdd,$req);
							$nbCloture=mysqli_num_rows($result2);
							
							$req = "SELECT DISTINCT Id_Prestation
									FROM soda_surveillance 
									WHERE Suppr=0 
									AND AutoSurveillance=0
									AND Etat='Clôturé'
									AND YEAR(soda_surveillance.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Annee']."
									AND MONTH(soda_surveillance.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Mois']."
									AND YEAR(soda_surveillance.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_AnneeFin']."
									AND MONTH(soda_surveillance.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_MoisFin']."
									AND Id_Questionnaire = ".$row2['Id']."
									AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
									";
							$result2=mysqli_query($bdd,$req);
							$nbPrestation=mysqli_num_rows($result2);
							
							
							$reqNote = "SELECT ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
									FROM soda_surveillance 
									WHERE Suppr=0 
									AND AutoSurveillance=0
									AND Etat='Clôturé'
									AND YEAR(soda_surveillance.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Annee']."
									AND MONTH(soda_surveillance.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Mois']."
									AND YEAR(soda_surveillance.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_AnneeFin']."
									AND MONTH(soda_surveillance.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_MoisFin']."
									AND Id_Questionnaire = ".$row2['Id']."
									AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
									";
							$resultNote=mysqli_query($bdd,$reqNote);
							$nbNote=mysqli_num_rows($resultNote);
							$noteMoyenne=0;
							if($nbNote>0){
								$rowNote=mysqli_fetch_array($resultNote);
								$noteMoyenne=$rowNote['NoteMoyenne'];
							}
							
							$nbRetard=0;
							
							$reqVol="SELECT Annee,Semaine,
								Volume,
								(SELECT COUNT(soda_surveillance.Id) FROM soda_surveillance WHERE soda_surveillance.Suppr=0 AND AutoSurveillance=0 AND soda_plannifmanuelle.Id_Questionnaire=soda_surveillance.Id_Questionnaire
								AND Etat='Clôturé' AND soda_surveillance.Id_Prestation=soda_plannifmanuelle.Id_Prestation AND ((Id_PlannifManuelle=soda_plannifmanuelle.Id) OR ((Id_PlannifManuelle=0 OR (Id_PlannifManuelle>0 AND (SELECT COUNT(Id) FROM soda_plannifmanuelle WHERE Id=Id_PlannifManuelle)=0)) AND CONCAT(Annee,'_',IF(Semaine<10,CONCAT(0,Semaine),Semaine))=CONCAT(YEAR(DateSurveillance),'_',DATE_FORMAT(DateSurveillance,'%u'))) ) ) AS VolumeCloture
								FROM soda_plannifmanuelle 
								WHERE Annee = ".$_SESSION['FiltreSODATDBOperation_AnneeFin']."
								AND Id_Questionnaire = ".$row2['Id']."
								AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].") ";
							$resultVolume=mysqli_query($bdd,$reqVol);
							$nbVolume=mysqli_num_rows($resultVolume);
							if ($nbVolume>0){
								while($rowSurveillance=mysqli_fetch_array($resultVolume)) {
									$lasemaine=$rowSurveillance['Annee']."S";
									if($rowSurveillance['Semaine']<10){$lasemaine.="0".$rowSurveillance['Semaine'];}else{$lasemaine.=$rowSurveillance['Semaine'];}
									$leVolume=0;
									if($rowSurveillance['Volume']>$rowSurveillance['VolumeCloture']){$leVolume=$rowSurveillance['Volume']-$rowSurveillance['VolumeCloture'];}
									if($semaine2>$lasemaine){
										$nbRetard+=$leVolume;
									}
								}
							}
							
							$total=$nbCloture+$nbRetard;
							$nbCloture2=0;
							if($total>0){
								$nbCloture2=round(($nbCloture/$total)*100,1);
								$nbRetard=round(($nbRetard/$total)*100,1);
							}
							
							$reqConforme = "SELECT ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100,1) AS NoteMoyenne,
									(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Seuil
									FROM soda_surveillance 
									WHERE Suppr=0 
									AND AutoSurveillance=0
									AND Etat='Clôturé'
									AND YEAR(soda_surveillance.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Annee']."
									AND MONTH(soda_surveillance.DateSurveillance)>=".$_SESSION['FiltreSODATDBOperation_Mois']."
									AND YEAR(soda_surveillance.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_AnneeFin']."
									AND MONTH(soda_surveillance.DateSurveillance)<=".$_SESSION['FiltreSODATDBOperation_MoisFin']."
									AND Id_Questionnaire = ".$row2['Id']."
									AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
									";
							$resultConforme=mysqli_query($bdd,$reqConforme);
							$nConforme=mysqli_num_rows($resultConforme);
							$reussite=0;
							$echec=0;
							$nbReussie=0;
							if($nConforme>0){
								while($rowConforme=mysqli_fetch_array($resultConforme)){
									if($rowConforme['NoteMoyenne']<$rowConforme['Seuil']){$echec++;}
									else{$reussite++;$nbReussie++;}
								}
								$reussite=round(($reussite/$nConforme)*100,1);
								$echec=round(($echec/$nConforme)*100,1);
							}
							else{
								$nbReussie=null;
								$reussite=null;
								$echec=null;
							}
							
							$arrayAdherencePlanningProcessus[$i]=array("Mois" => utf8_encode($intitule2),"NbCloture" => valeurSinonNull($nbCloture2),"NbRetard" => valeurSinonNull($nbRetard));
							$arrayNoteMoyenneProcessus[$i]=array("Mois" => utf8_encode($intitule2),"NoteMoyenne" => valeurSinonNull($noteMoyenne),"Seuil" => valeurSinonNull($seuilReussite));
							$arrayNbSurveillanceRealiseesProcessus[$i]=array("Mois" => utf8_encode($intitule2),"NbSurveillance" => utf8_encode($nbCloture),"Objectif" => utf8_encode($objectifSurveillance));
							$arrayNbPrestationProcessus[$i]=array("Mois" => utf8_encode($intitule2),"NbPrestation" => utf8_encode($nbPrestation),"Objectif" => utf8_encode($objectifPrestation));
							$arrayEcart[$i]=array("Mois" => utf8_encode($intitule2),"NbSurveillance" => utf8_encode($nConforme),"NbReussie" => utf8_encode($nbReussie),"Reussite" => utf8_encode($reussite),"Echec" => utf8_encode($echec));
							$i++;
						}
					}
					
			?>
			<tr>
				<td width="100%">
					<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="50%">
								<table style="width:100%;">
									<tr><td height="4"></td></tr>
									<tr>
										<td valign="top">
											<div id="chart_NbSurveillance" style="width:100%;height:500px;"></div>
											<script>
												var chart = am4core.create("chart_NbSurveillance", am4charts.XYChart);

												// Add data
												chart.data = <?php echo json_encode($arrayNbSurveillanceRealisees); ?>;
												
												var title = chart.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of monitoring carried out ");}else{echo json_encode(utf8_encode("Nombre de surveillances réalisées "));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;
												
												// Create axes
												var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
												categoryAxis.dataFields.category = "Mois";
												categoryAxis.renderer.grid.template.location = 0;
												categoryAxis.renderer.minGridDistance = 15;
												categoryAxis.renderer.labels.template.horizontalCenter = "right";
												categoryAxis.renderer.labels.template.verticalCenter = "middle";
												categoryAxis.renderer.labels.template.rotation = 270;
												categoryAxis.renderer.minHeight = 0;

												var valueAxis1 = chart.yAxes.push(new am4charts.ValueAxis());
												valueAxis1.renderer.minWidth = 0;
												valueAxis1.min= 0;
												
												var series2 = chart.series.push(new am4charts.ColumnSeries());
												series2.dataFields.categoryX = "Mois";
												series2.dataFields.valueY = "Objectif";
												series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Objective");}else{echo json_encode(utf8_encode("Objectif"));} ?>;
												series2.tooltipText = "{name}: {valueY.value}";
												series2.strokeWidth = 2;
												series2.stroke  = "#f1bd00";
												series2.fill  = "#f1bd00";
												
												var bullet3 = series2.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY}";
												bullet3.locationY = 0.5;
												bullet3.label.fill = am4core.color("#ffffff");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
												
												var series3 = chart.series.push(new am4charts.ColumnSeries());
												series3.dataFields.valueY = "NbSurveillance";
												series3.dataFields.categoryX = "Mois";
												series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of monitors");}else{echo json_encode(utf8_encode("Nombre de surveillances"));} ?>;
												series3.columns.template.width = am4core.percent(80);
												series3.tooltipText = "{name}: {valueY.value}";
												series3.stroke  = "#3571c0";
												series3.fill  = "#3571c0";
												
												var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY}";
												bullet3.locationY = 0.5;
												bullet3.label.fill = am4core.color("#ffffff");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
												
												var  valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
												valueAxis2.calculateTotals = true;
												valueAxis2.min = 0;
												valueAxis2.strictMinMax = true;
												valueAxis2.renderer.opposite = true;
												valueAxis2.syncWithAxis = valueAxis1;
												valueAxis2.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("%");}else{echo json_encode(utf8_encode("%"));} ?>;
												
												var series4 = chart.series.push(new am4charts.LineSeries());												
												series4.tooltipText = "{name}: {valueY.value}";
												series4.dataFields.categoryX = "Mois";
												series4.dataFields.valueY = "Pourcentage";
												series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% realized");}else{echo json_encode(utf8_encode("% réalisé"));} ?>;
												series4.strokeOpacity = 0;
												series4.fill  = "#df1515";
												series4.stroke  = "#df1515";
												series4.bullets.push(new am4charts.CircleBullet());
												series4.yAxis = valueAxis2;
												
												var bullet3 = series4.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY} %";
												bullet3.locationY = -0.1;
												bullet3.label.fill = am4core.color("#df1515");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
												
												// Cursor
												chart.cursor = new am4charts.XYCursor();
												chart.cursor.behavior = "panX";
												chart.cursor.lineX.opacity = 0;
												chart.cursor.lineY.opacity = 0;
												
												/* Add legend */
												chart.legend = new am4charts.Legend();
												chart.scrollbarX = new am4core.Scrollbar();
												
												chart.exporting.menu = new am4core.ExportMenu();
											</script>
										</td>
									</tr>
								</table>
							</td>
							<td width="50%">
								<table style="width:100%;">
									<tr><td height="4"></td></tr>
									<tr>
										<td valign="top">
											<div id="chart_NbPrestation" style="width:100%;height:500px;"></div>
											<script>
												var chart = am4core.create("chart_NbPrestation", am4charts.XYChart);

												// Add data
												chart.data = <?php echo json_encode($arrayNbPrestation); ?>;
												
												var title = chart.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of different sites ");}else{echo json_encode(utf8_encode("Nombre de prestations différentes "));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;
												
												// Create axes
												var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
												categoryAxis.dataFields.category = "Mois";
												categoryAxis.renderer.grid.template.location = 0;
												categoryAxis.renderer.minGridDistance = 15;
												categoryAxis.renderer.labels.template.horizontalCenter = "right";
												categoryAxis.renderer.labels.template.verticalCenter = "middle";
												categoryAxis.renderer.labels.template.rotation = 270;
												categoryAxis.renderer.minHeight = 0;

												var valueAxis1 = chart.yAxes.push(new am4charts.ValueAxis());
												valueAxis1.renderer.minWidth = 0;
												valueAxis1.min= 0;
												
												var series2 = chart.series.push(new am4charts.ColumnSeries());
												series2.dataFields.categoryX = "Mois";
												series2.dataFields.valueY = "Objectif";
												series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Objective");}else{echo json_encode(utf8_encode("Objectif"));} ?>;
												series2.tooltipText = "{name}: {valueY.value}";
												series2.strokeWidth = 2;
												series2.stroke  = "#f1bd00";
												series2.fill  = "#f1bd00";
												
												var bullet3 = series2.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY}";
												bullet3.locationY = 0.5;
												bullet3.label.fill = am4core.color("#ffffff");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
												
												var series3 = chart.series.push(new am4charts.ColumnSeries());
												series3.dataFields.valueY = "NbPrestation";
												series3.dataFields.categoryX = "Mois";
												series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of sites");}else{echo json_encode(utf8_encode("Nombre de prestations"));} ?>;
												series3.columns.template.width = am4core.percent(80);
												series3.tooltipText = "{name}: {valueY.value}";

												series3.stroke  = "#48afcc";
												series3.fill  = "#48afcc";
												
												var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY}";
												bullet3.locationY = 0.5;
												bullet3.label.fill = am4core.color("#ffffff");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
												
												var  valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
												valueAxis2.calculateTotals = true;
												valueAxis2.min = 0;
												valueAxis2.strictMinMax = true;
												valueAxis2.renderer.opposite = true;
												valueAxis2.syncWithAxis = valueAxis1;
												valueAxis2.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("%");}else{echo json_encode(utf8_encode("%"));} ?>;
												
												var series4 = chart.series.push(new am4charts.LineSeries());												
												series4.tooltipText = "{name}: {valueY.value}";
												series4.dataFields.categoryX = "Mois";
												series4.dataFields.valueY = "Pourcentage";
												series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% site");}else{echo json_encode(utf8_encode("% prestation"));} ?>;
												series4.strokeOpacity = 0;
												series4.fill  = "#df1515";
												series4.stroke  = "#df1515";
												series4.bullets.push(new am4charts.CircleBullet());
												series4.yAxis = valueAxis2;
												
												var bullet3 = series4.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY} %";
												bullet3.locationY = -0.2;
												bullet3.label.fill = am4core.color("#df1515");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
												
												// Cursor
												chart.cursor = new am4charts.XYCursor();
												chart.cursor.behavior = "panX";
												chart.cursor.lineX.opacity = 0;
												chart.cursor.lineY.opacity = 0;
												
												/* Add legend */
												chart.legend = new am4charts.Legend();
												chart.scrollbarX = new am4core.Scrollbar();
												
												chart.exporting.menu = new am4core.ExportMenu();
											</script>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="50%">
								<table style="width:100%;">
									<tr><td height="4"></td></tr>
									<tr>
										<td valign="top">
											<div id="chart_NoteMoyenne" style="width:100%;height:500px;"></div>
											<script>
												var chart = am4core.create("chart_NoteMoyenne", am4charts.XYChart);

												// Add data
												chart.data = <?php echo json_encode($arrayNoteMoyenne); ?>;
												chart.numberFormatter.numberFormat = "#'%'";
												
												var title = chart.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Average grade ");}else{echo json_encode(utf8_encode("Note moyenne "));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;
												
												// Create axes
												var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
												categoryAxis.dataFields.category = "Mois";
												categoryAxis.renderer.grid.template.location = 0;
												categoryAxis.renderer.minGridDistance = 15;
												categoryAxis.renderer.labels.template.horizontalCenter = "right";
												categoryAxis.renderer.labels.template.verticalCenter = "middle";
												categoryAxis.renderer.labels.template.rotation = 270;
												categoryAxis.renderer.minHeight = 0;

												var valueAxis1 = chart.yAxes.push(new am4charts.ValueAxis());
												valueAxis1.renderer.minWidth = 0;
												valueAxis1.min= 0;
												valueAxis1.max= 100;
												
												var series2 = chart.series.push(new am4charts.LineSeries());
												series2.dataFields.categoryX = "Mois";
												series2.dataFields.valueY = "Seuil";
												series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Pass threshold");}else{echo json_encode(utf8_encode("Seuil de réussite"));} ?>;
												series2.tooltipText = "[{Mois}: bold]{valueY}[/]";
												series2.strokeWidth = 2;
												series2.stroke  = "#000000";
												series2.fill  = "#000000";
												
												var series3 = chart.series.push(new am4charts.ColumnSeries());
												series3.dataFields.valueY = "NoteMoyenne";
												series3.dataFields.categoryX = "Mois";
												series3.columns.template.width = am4core.percent(80);
												series3.tooltipText = "{valueY.value}";

												series3.stroke  = "#1ab559";
												series3.fill  = "#1ab559";
												
												var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY}";
												bullet3.locationY = 0.5;
												bullet3.label.fill = am4core.color("#ffffff");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
												
												// Cursor
												chart.cursor = new am4charts.XYCursor();
												chart.cursor.behavior = "panX";
												chart.cursor.lineX.opacity = 0;
												chart.cursor.lineY.opacity = 0;
												
												/* Add legend */
												chart.scrollbarX = new am4core.Scrollbar();
												
												chart.exporting.menu = new am4core.ExportMenu();
											</script>
										</td>
									</tr>
								</table>
							</td>
							<td width="50%">
							</td>
						</tr>
						<tr>
							<td width="50%">
								<table style="width:100%;">
									<tr><td height="4"></td></tr>
									<tr>
										<td valign="top">
											<div id="chart_NbSurveillanceProcessus" style="width:100%;height:500px;"></div>
											<script>
												var chart = am4core.create("chart_NbSurveillanceProcessus", am4charts.XYChart);

												// Add data
												chart.data = <?php echo json_encode($arrayNbSurveillanceRealiseesProcessus); ?>;
												
												var title = chart.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of PROCESSUS monitoring carried out ");}else{echo json_encode(utf8_encode("Nombre de surveillances PROCESSUS réalisées "));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;
												
												// Create axes
												var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
												categoryAxis.dataFields.category = "Mois";
												categoryAxis.renderer.grid.template.location = 0;
												categoryAxis.renderer.minGridDistance = 15;
												categoryAxis.renderer.labels.template.horizontalCenter = "right";
												categoryAxis.renderer.labels.template.verticalCenter = "middle";
												categoryAxis.renderer.labels.template.rotation = 270;
												categoryAxis.renderer.minHeight = 0;

												var valueAxis1 = chart.yAxes.push(new am4charts.ValueAxis());
												valueAxis1.renderer.minWidth = 0;
												valueAxis1.min= 0;
												
												var series2 = chart.series.push(new am4charts.ColumnSeries());
												series2.dataFields.categoryX = "Mois";
												series2.dataFields.valueY = "Objectif";
												series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Objective");}else{echo json_encode(utf8_encode("Objectif"));} ?>;
												series2.tooltipText = "[{Mois}: bold]{valueY}[/]";
												series2.strokeWidth = 2;
												series2.stroke  = "#f1bd00";
												series2.fill  = "#f1bd00";
												
												var bullet3 = series2.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY}";
												bullet3.locationY = 0.5;
												bullet3.label.fill = am4core.color("#ffffff");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
												
												var series3 = chart.series.push(new am4charts.ColumnSeries());
												series3.dataFields.valueY = "NbSurveillance";
												series3.dataFields.categoryX = "Mois";
												series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of monitors");}else{echo json_encode(utf8_encode("Nombre de surveillances"));} ?>;
												series3.columns.template.width = am4core.percent(80);
												series3.tooltipText = "{valueY.value}";

												series3.stroke  = "#3571c0";
												series3.fill  = "#3571c0";
												
												var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY}";
												bullet3.locationY = 0.5;
												bullet3.label.fill = am4core.color("#ffffff");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
												
												// Cursor
												chart.cursor = new am4charts.XYCursor();
												chart.cursor.behavior = "panX";
												chart.cursor.lineX.opacity = 0;
												chart.cursor.lineY.opacity = 0;
												
												/* Add legend */
												chart.scrollbarX = new am4core.Scrollbar();
												
												chart.exporting.menu = new am4core.ExportMenu();
											</script>
										</td>
									</tr>
								</table>
							</td>
							<td width="50%">
								<table style="width:100%;">
									<tr><td height="4"></td></tr>
									<tr>
										<td valign="top">
											<div id="chart_NbPrestationProcessus" style="width:100%;height:500px;"></div>
											<script>
												var chart = am4core.create("chart_NbPrestationProcessus", am4charts.XYChart);

												// Add data
												chart.data = <?php echo json_encode($arrayNbPrestationProcessus); ?>;
												
												var title = chart.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("PROCESS - Number of different UER ");}else{echo json_encode(utf8_encode("PROCESSUS - Nombre d'UER différents "));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;
												
												// Create axes
												var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
												categoryAxis.dataFields.category = "Mois";
												categoryAxis.renderer.grid.template.location = 0;
												categoryAxis.renderer.minGridDistance = 15;
												categoryAxis.renderer.labels.template.horizontalCenter = "right";
												categoryAxis.renderer.labels.template.verticalCenter = "middle";
												categoryAxis.renderer.labels.template.rotation = 270;
												categoryAxis.renderer.minHeight = 0;

												var valueAxis1 = chart.yAxes.push(new am4charts.ValueAxis());
												valueAxis1.renderer.minWidth = 0;
												valueAxis1.min= 0;
												
												var series2 = chart.series.push(new am4charts.ColumnSeries());
												series2.dataFields.categoryX = "Mois";
												series2.dataFields.valueY = "Objectif";
												series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Objective");}else{echo json_encode(utf8_encode("Objectif"));} ?>;
												series2.tooltipText = "[{Mois}: bold]{valueY}[/]";
												series2.strokeWidth = 2;
												series2.stroke  = "#f1bd00";
												series2.fill  = "#f1bd00";
												
												var bullet3 = series2.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY}";
												bullet3.locationY = 0.5;
												bullet3.label.fill = am4core.color("#ffffff");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
												
												var series3 = chart.series.push(new am4charts.ColumnSeries());
												series3.dataFields.valueY = "NbPrestation";
												series3.dataFields.categoryX = "Mois";
												series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of sites");}else{echo json_encode(utf8_encode("Nombre de prestations"));} ?>;
												series3.columns.template.width = am4core.percent(80);
												series3.tooltipText = "{valueY.value}";

												series3.stroke  = "#48afcc";
												series3.fill  = "#48afcc";
												
												var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY}";
												bullet3.locationY = 0.5;
												bullet3.label.fill = am4core.color("#ffffff");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
												
												// Cursor
												chart.cursor = new am4charts.XYCursor();
												chart.cursor.behavior = "panX";
												chart.cursor.lineX.opacity = 0;
												chart.cursor.lineY.opacity = 0;
												
												/* Add legend */
												chart.scrollbarX = new am4core.Scrollbar();
												
												chart.exporting.menu = new am4core.ExportMenu();
											</script>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="50%">
								<table style="width:100%;">
									<tr><td height="4"></td></tr>
									<tr>
										<td valign="top">
											<div id="chart_NoteMoyenneProcessus" style="width:100%;height:500px;"></div>
											<script>
												var chart = am4core.create("chart_NoteMoyenneProcessus", am4charts.XYChart);

												// Add data
												chart.data = <?php echo json_encode($arrayNoteMoyenneProcessus); ?>;
												chart.numberFormatter.numberFormat = "#'%'";
												
												var title = chart.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("PROCESS - Average grade ");}else{echo json_encode(utf8_encode("PROCESSUS - Note moyenne "));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;
												
												// Create axes
												var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
												categoryAxis.dataFields.category = "Mois";
												categoryAxis.renderer.grid.template.location = 0;
												categoryAxis.renderer.minGridDistance = 15;
												categoryAxis.renderer.labels.template.horizontalCenter = "right";
												categoryAxis.renderer.labels.template.verticalCenter = "middle";
												categoryAxis.renderer.labels.template.rotation = 270;
												categoryAxis.renderer.minHeight = 0;

												var valueAxis1 = chart.yAxes.push(new am4charts.ValueAxis());
												valueAxis1.renderer.minWidth = 0;
												valueAxis1.min= 0;
												valueAxis1.max= 100;
												
												var series2 = chart.series.push(new am4charts.LineSeries());
												series2.dataFields.categoryX = "Mois";
												series2.dataFields.valueY = "Seuil";
												series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Pass threshold");}else{echo json_encode(utf8_encode("Seuil de réussite"));} ?>;
												series2.tooltipText = "[{Mois}: bold]{valueY}[/]";
												series2.strokeWidth = 2;
												series2.stroke  = "#000000";
												series2.fill  = "#000000";
												
												var series3 = chart.series.push(new am4charts.ColumnSeries());
												series3.dataFields.valueY = "NoteMoyenne";
												series3.dataFields.categoryX = "Mois";
												series3.columns.template.width = am4core.percent(80);
												series3.tooltipText = "{valueY.value}";

												series3.stroke  = "#1ab559";
												series3.fill  = "#1ab559";
												
												var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY}";
												bullet3.locationY = 0.5;
												bullet3.label.fill = am4core.color("#ffffff");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
												
												// Cursor
												chart.cursor = new am4charts.XYCursor();
												chart.cursor.behavior = "panX";
												chart.cursor.lineX.opacity = 0;
												chart.cursor.lineY.opacity = 0;
												
												/* Add legend */
												chart.scrollbarX = new am4core.Scrollbar();
												
												chart.exporting.menu = new am4core.ExportMenu();
											</script>
										</td>
									</tr>
								</table>
							</td>
							<td width="50%">
								<table style="width:100%;">
									<tr><td height="4"></td></tr>
									<tr>
										<td valign="top">
											<div id="chart_AdherenceProcessus" style="width:100%;height:500px;"></div>
											<script>
												var chart = am4core.create("chart_AdherenceProcessus", am4charts.XYChart);

												// Add data
												chart.data = <?php echo json_encode($arrayAdherencePlanningProcessus); ?>;
												chart.numberFormatter.numberFormat = "#'%'";
												
												var title = chart.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Schedule adherence / Processus ");}else{echo json_encode(utf8_encode("Adhérence planning / Processus"));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;
												
												// Create axes
												var xAxis = chart.xAxes.push(new am4charts.CategoryAxis());
												xAxis.dataFields.category = "Mois";
												xAxis.renderer.grid.template.location = 0;
												xAxis.renderer.minGridDistance = 15;
												xAxis.renderer.labels.template.horizontalCenter = "right";
												xAxis.renderer.labels.template.verticalCenter = "middle";
												xAxis.renderer.labels.template.rotation = 270;
												xAxis.tooltip.disabled = true;
												xAxis.renderer.minHeight = 0;

												var valueAxis1 = chart.yAxes.push(new am4charts.ValueAxis());
												valueAxis1.renderer.minWidth = 0;
												valueAxis1.min= 0;
												valueAxis1.max= 100;
												valueAxis1.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of monitoring");}else{echo json_encode(utf8_encode("% des surveillances plannifiées à des dates passées"));} ?>;

												// Create series
												var series3 = chart.series.push(new am4charts.ColumnSeries());
												series3.columns.template.width = am4core.percent(90);
												series3.tooltipText = "{name}: {valueY.value}";
												series3.dataFields.categoryX = "Mois";
												series3.dataFields.valueY = "NbCloture";
												series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Completed");}else{echo json_encode(utf8_encode("Réalisées"));} ?>;
												series3.stacked = true;
												series3.stroke  = "#7ed957";
												series3.fill  = "#7ed957";
												
												var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY}";
												bullet3.locationX = 0.5;
												bullet3.label.fill = am4core.color("#000000");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
												
												var series3 = chart.series.push(new am4charts.ColumnSeries());
												series3.columns.template.width = am4core.percent(90);
												series3.tooltipText = "{name}: {valueY.value}";
												series3.dataFields.categoryX = "Mois";
												series3.dataFields.valueY = "NbRetard";
												series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Delay");}else{echo json_encode(utf8_encode("Retard"));} ?>;
												series3.stacked = true;
												series3.stroke  = "#ff5757";
												series3.fill  = "#ff5757";
												
												var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY}";
												bullet3.locationX = 0.5;
												bullet3.label.fill = am4core.color("#000000");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
												
												
												// Cursor
												chart.cursor = new am4charts.XYCursor();
												chart.cursor.behavior = "panX";
												chart.cursor.lineX.opacity = 0;
												chart.cursor.lineY.opacity = 0;
												
												/* Add legend */
												chart.legend = new am4charts.Legend();
												chart.scrollbarY = new am4core.Scrollbar();
												
												chart.exporting.menu = new am4core.ExportMenu();
											</script>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="50%" valign="top">
								<table style="width:100%;">
									<tr><td height="4"></td></tr>
									<tr>
										<td valign="top">
											<div id="chart_Ecart" style="width:100%;height:500px;"></div>
											<script>
												var chart = am4core.create("chart_Ecart", am4charts.XYChart);

												// Add data
												chart.data = <?php echo json_encode($arrayEcart); ?>;
												
												var title = chart.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Success rate / Process ");}else{echo json_encode(utf8_encode("Taux de réussite / Processus "));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;
												
												// Create axes
												var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
												categoryAxis.dataFields.category = "Mois";
												categoryAxis.renderer.grid.template.location = 0;
												categoryAxis.renderer.minGridDistance = 10;
												categoryAxis.renderer.labels.template.horizontalCenter = "right";
												categoryAxis.renderer.labels.template.verticalCenter = "middle";
												categoryAxis.renderer.labels.template.rotation = 270;
												categoryAxis.tooltip.disabled = true;
												categoryAxis.renderer.minHeight = 0;

												var valueAxis1 = chart.yAxes.push(new am4charts.ValueAxis());
												valueAxis1.renderer.minWidth = 0;
												valueAxis1.min= 0;
												valueAxis1.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number");}else{echo json_encode(utf8_encode("Nbr"));} ?>;
												
												// Create series
												var series3 = chart.series.push(new am4charts.ColumnSeries());
												series3.columns.template.width = am4core.percent(90);
												series3.tooltipText = "{name}: {valueY.value}";
												series3.dataFields.categoryX = "Mois";
												series3.dataFields.valueY = "NbSurveillance";
												series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of monitoring carried out");}else{echo json_encode(utf8_encode("Nbr de surveillances réalisées"));} ?>;
												series3.stacked = false;
												series3.stroke  = "#6EB4CD";
												series3.fill  = "#6EB4CD";
												
												var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY}";
												bullet3.locationY = 0.5;
												bullet3.label.fill = am4core.color("#ffffff");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
												
												var series2 = chart.series.push(new am4charts.ColumnSeries());
												series2.columns.template.width = am4core.percent(90);
												series2.tooltipText = "{name}: {valueY.value}";
												series2.dataFields.categoryX = "Mois";
												series2.dataFields.valueY = "NbReussie";
												series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of scouts to target");}else{echo json_encode(utf8_encode("Nbr de surveillances à l'objectif"));} ?>;
												series2.stacked = false;
												series2.stroke  = "#7ed957";
												series2.fill  = "#7ed957";
												
												var bullet2 = series2.bullets.push(new am4charts.LabelBullet());
												bullet2.label.text = "{valueY}";
												bullet2.locationY = 0.5;
												bullet2.label.fill = am4core.color("#ffffff");
												bullet2.interactionsEnabled = false;
												bullet2.fontSize = 10;
												
												var valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
												valueAxis2.renderer.minWidth = 0;
												valueAxis2.min= 0;
												valueAxis2.max= 130;
												valueAxis2.strictMinMax = true;
												valueAxis2.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("%");}else{echo json_encode(utf8_encode("%"));} ?>;
												valueAxis2.renderer.opposite = true;
												valueAxis2.syncWithAxis = valueAxis1;
												
												var series4 = chart.series.push(new am4charts.LineSeries());												
												series4.tooltipText = "[{Mois}: bold]{valueY}[/]";
												series4.dataFields.categoryX = "Mois";
												series4.dataFields.valueY = "Reussite";
												series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% success");}else{echo json_encode(utf8_encode("% réussite"));} ?>;
												series4.strokeOpacity = 0;
												series4.fill  = "#6bae3d";
												series4.bullets.push(new am4charts.CircleBullet());
												series4.yAxis = valueAxis2;
												
												var bullet3 = series4.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY} %";
												bullet3.locationY = -0.1;
												bullet3.label.fill = am4core.color("#000000");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
			
												// Cursor
												chart.cursor = new am4charts.XYCursor();
												chart.cursor.behavior = "panX";
												chart.cursor.lineX.opacity = 0;
												chart.cursor.lineY.opacity = 0;
												
												/* Add legend */
												chart.legend = new am4charts.Legend();
												chart.scrollbarX = new am4core.Scrollbar();
												
												chart.exporting.menu = new am4core.ExportMenu();
											</script>
										</td>
									</tr>
								</table>
							</td>
							<td width="50%">
							</td>
						</tr>
						<tr>
							<td width="50%">
								<table style="width:100%;">
									<tr><td height="4"></td></tr>
									<tr>
										<td align="right"><a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreExcel('NCProcessusv2')"><?php if($_SESSION['Langue']=="EN"){echo "Export NC";}else{echo "Export NC";}?></a></td>
									</tr>
									<tr>
										<td valign="top">
											<div id="chart_ParetoNC" style="width:100%;height:300px;"></div>
											<script>
												var chart = am4core.create("chart_ParetoNC", am4charts.XYChart);

												// Add data
												chart.data = <?php echo json_encode($arrayParetoNC); ?>;
												
												var title = chart.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Pareto of non-compliant questions in volume (Top 15)");}else{echo json_encode(utf8_encode("Pareto des questions non conformes en volume (Top 15)"));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;

												// Create axes
												var yAxis = chart.yAxes.push(new am4charts.CategoryAxis());
												yAxis.dataFields.category = "Mois";
												yAxis.renderer.grid.template.location = 0;
												yAxis.renderer.labels.template.fontSize = 10;
												yAxis.renderer.labels.positionX = 0;
												yAxis.renderer.minGridDistance = 0;
												
												var xAxis = chart.xAxes.push(new am4charts.ValueAxis());
												xAxis.min= 0;
																				
												var series3 = chart.series.push(new am4charts.ColumnSeries());
												series3.dataFields.valueX = "Nombre";
												series3.dataFields.categoryY = "Mois";
												series3.tooltipText = "{categoryY}\n [bold]{valueX}[/]";
												series3.columns.template.width = am4core.percent(90);
												series3.stroke  = "#6ce5e8";
												series3.fill  = "#6ce5e8";

												var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueX}";
												bullet3.label.fill = am4core.color("#000000");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
												
												// Cursor
												chart.cursor = new am4charts.XYCursor();
												chart.cursor.behavior = "panX";
												chart.cursor.lineX.opacity = 0;
												chart.cursor.lineY.opacity = 0;
												
												/* Add legend */
												chart.scrollbarX = new am4core.Scrollbar();
												
												chart.exporting.menu = new am4core.ExportMenu();
											</script>
										</td>
									</tr>
								</table>
							</td>
							<td width="50%">
								<?php echo $tabParetoNC;?>
							</td>
						</tr>
						<tr>
							<td width="50%">
								<?php echo $tabParetoNC2;?>
							</td>
							<td width="50%">
								<table style="width:100%;">
									<tr><td height="4"></td></tr>
									<tr>
										<td align="right"><a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreExcel('NC2Processusv2')"><?php if($_SESSION['Langue']=="EN"){echo "Export NC";}else{echo "Export NC";}?></a></td>
									</tr>
									<tr>
										<td valign="top">
											<div id="chart_ParetoNC2" style="width:100%;height:300px;"></div>
											<script>
												var chart = am4core.create("chart_ParetoNC2", am4charts.XYChart);

												// Add data
												chart.data = <?php echo json_encode($arrayParetoNC2); ?>;
												
												var title = chart.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Pareto of non-compliant questions in Ratio (Top 15)");}else{echo json_encode(utf8_encode("Pareto des questions non conformes en ratio (Top 15)"));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;

												// Create axes
												var yAxis = chart.yAxes.push(new am4charts.CategoryAxis());
												yAxis.dataFields.category = "Mois";
												yAxis.renderer.grid.template.location = 0;
												yAxis.renderer.labels.template.fontSize = 10;
												yAxis.renderer.labels.positionX = 0;
												yAxis.renderer.minGridDistance = 0;
												
												var xAxis = chart.xAxes.push(new am4charts.ValueAxis());
												xAxis.min= 0;
																				
												var series3 = chart.series.push(new am4charts.ColumnSeries());
												series3.dataFields.valueX = "Nombre";
												series3.dataFields.categoryY = "Mois";
												series3.tooltipText = "{categoryY}\n [bold]{valueX}[/] %";
												series3.columns.template.width = am4core.percent(90);
												series3.stroke  = "#6ce5e8";
												series3.fill  = "#6ce5e8";

												var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueX} %";
												bullet3.label.fill = am4core.color("#000000");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
												
												// Cursor
												chart.cursor = new am4charts.XYCursor();
												chart.cursor.behavior = "panX";
												chart.cursor.lineX.opacity = 0;
												chart.cursor.lineY.opacity = 0;
												
												/* Add legend */
												chart.scrollbarX = new am4core.Scrollbar();
												
												chart.exporting.menu = new am4core.ExportMenu();
											</script>
										</td>
									</tr>
								</table>
								<table style="width:100%;">
									<tr><td height="4"></td></tr>
									<tr>
										<td valign="top">
											<div id="chart_ParetoNC3" style="width:100%;height:400px;"></div>
											<script>
												var chart = am4core.create("chart_ParetoNC3", am4charts.XYChart);

												// Add data
												chart.data = <?php echo json_encode($arrayParetoNC3); ?>;
												
												var title = chart.titles.create();
												title.text = <?php if($_SESSION['Langue']=="FR"){echo json_encode("Nombre de reponses NC par questions");}else{echo json_encode(utf8_encode("Number of NC responses per question"));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;
												
												// Create axes
												var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
												categoryAxis.dataFields.category = "Mois";
												categoryAxis.renderer.grid.template.location = 0;
												categoryAxis.renderer.minGridDistance = 60;
												categoryAxis.renderer.labels.template.horizontalCenter = "right";
												categoryAxis.renderer.labels.template.verticalCenter = "middle";
												categoryAxis.renderer.labels.template.rotation = 270;
												categoryAxis.tooltip.disabled = true;
												categoryAxis.renderer.minHeight = 0;

												var valueAxis1 = chart.yAxes.push(new am4charts.ValueAxis());
												valueAxis1.renderer.minWidth = 0;
												valueAxis1.min= 0;
												valueAxis1.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number");}else{echo json_encode(utf8_encode("Nbr"));} ?>;
												
												// Create series
												var series3 = chart.series.push(new am4charts.ColumnSeries());
												series3.columns.template.width = am4core.percent(90);
												series3.tooltipText = "{name}: {valueY.value}";
												series3.dataFields.categoryX = "Mois";
												series3.dataFields.valueY = "NbNC";
												series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of NC responses");}else{echo json_encode(utf8_encode("Nbr de réponses NC"));} ?>;
												series3.stacked = false;
												series3.stroke  = "#ef7621";
												series3.fill  = "#ef7621";
												
												var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY}";
												bullet3.locationY = 0.5;
												bullet3.label.fill = am4core.color("#ffffff");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
												
												var series2 = chart.series.push(new am4charts.ColumnSeries());
												series2.columns.template.width = am4core.percent(90);
												series2.tooltipText = "{name}: {valueY.value}";
												series2.dataFields.categoryX = "Mois";
												series2.dataFields.valueY = "NbQuestion";
												series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of questions");}else{echo json_encode("Nbr de questions");} ?>;
												series2.stacked = false;
												series2.stroke  = "#f1bd00";
												series2.fill  = "#f1bd00";
												
												var bullet2 = series2.bullets.push(new am4charts.LabelBullet());
												bullet2.label.text = "{valueY}";
												bullet2.locationY = 0.5;
												bullet2.label.fill = am4core.color("#ffffff");
												bullet2.interactionsEnabled = false;
												bullet2.fontSize = 10;
												
												var valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
												valueAxis2.renderer.minWidth = 0;
												valueAxis2.min= 0;
												valueAxis2.max= 130;
												valueAxis2.strictMinMax = true;
												valueAxis2.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("%");}else{echo json_encode(utf8_encode("%"));} ?>;
												valueAxis2.renderer.opposite = true;
												valueAxis2.syncWithAxis = valueAxis1;
												
												var series4 = chart.series.push(new am4charts.LineSeries());												
												series4.tooltipText = "[{Mois}: bold]{valueY}[/]";
												series4.dataFields.categoryX = "Mois";
												series4.dataFields.valueY = "Erreur";
												series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% errors");}else{echo json_encode(utf8_encode("% erreurs"));} ?>;
												series4.strokeOpacity = 0;
												series4.fill  = "#6bae3d";
												series4.bullets.push(new am4charts.CircleBullet());
												series4.yAxis = valueAxis2;
												
												var bullet3 = series4.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY} %";
												bullet3.locationY = -0.1;
												bullet3.label.fill = am4core.color("#000000");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
			
												// Cursor
												chart.cursor = new am4charts.XYCursor();
												chart.cursor.behavior = "panX";
												chart.cursor.lineX.opacity = 0;
												chart.cursor.lineY.opacity = 0;
												
												/* Add legend */
												chart.legend = new am4charts.Legend();
												chart.scrollbarX = new am4core.Scrollbar();
												
												chart.exporting.menu = new am4core.ExportMenu();
											</script>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="100%">
					<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="40%" class="Libelle">
								&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Vue détaillée du";}else{echo "Detailed view from";}?> :
								<?php
									$dateDebut=$_SESSION['FiltreSODATDBProcessus_DateDebut'];
									$dateDeFin=$_SESSION['FiltreSODATDBProcessus_DateFin'];
									
									$lundi2=$dateDebut;
									if(date("N",strtotime($lundi2." +0 day"))==1){$lundi2=$lundi2;}
									else{$lundi2=date("Y-m-d",strtotime($lundi2."last Monday"));}
									$anneeSemaine2="";
									$lundiFin2=$dateDeFin;
									if(date("N",strtotime($lundiFin2." +0 day"))==1){$lundiFin2=$lundiFin2;}
									else{$lundiFin2=date("Y-m-d",strtotime($lundiFin2."last Monday"));}
									
									while($lundi2<=$lundiFin2){
									if(date("N",strtotime($lundi2." +0 day"))==1){$lundi2=$lundi2;}
									else{$lundi2=date("Y-m-d",strtotime($lundi2."last Monday"));}
									$jeudi=date("Y-m-d",strtotime($lundi2." +3 day"));

									if($jeudi>=$dateDebut && $lundi2<=$dateDeFin){
										$tabDate = explode('-', $jeudi);
										$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);

										if($anneeSemaine2<>""){$anneeSemaine2.=",";}
										$anneeSemaine2.= "'".date('Y_W', $timestamp)."'";
									}
									$lundi2=date("Y-m-d",strtotime($lundi2." +7 day"));
								
								}
								?>
									
									<input type="date" style="text-align:center;" name="dateDebut" size="10" value="<?php echo AfficheDateFR($dateDebut); ?>">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "au";}else{echo "to";}?> :
									<input type="date" style="text-align:center;" name="dateFin"  size="10" value="<?php echo AfficheDateFR($dateDeFin); ?>">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
							</td>
						</tr>
						<?php
						if($_SESSION['FiltreSODATDBProcessus_DateDebut']>"0001-01-01" && $_SESSION['FiltreSODATDBProcessus_DateFin']>"0001-01-01"){
						?>
						<tr>
							<td align="center">
								<table class="TableCompetences" align="center" width="100%">
									<tr>
										<td style="color:#00567c;border-bottom:2px #055981 solid;" width="15%">
											<?php 
											if($_SESSION["Langue"]=="FR"){echo "Processus";}else{echo "Proces";}
											?>
										</td>
										<td style="color:#00567c;border-bottom:2px #055981 solid;" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Nombre de surveillances restante à faire";}else{echo "Number of monitoring remaining to be done";}?></td>
										<td style="color:#00567c;border-bottom:2px #055981 solid;" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Taux d'adhérence planning";}else{echo "Schedule adherence rate";}?></td>
										
										<td style="color:#00567c;border-bottom:2px #055981 solid;" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Nombre de surveillances réalisées";}else{echo "Number of monitoring carried out";}?></td>
										<td style="color:#00567c;border-bottom:2px #055981 solid;" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Score moyen en %";}else{echo "Average score in %";}?></td>
										<td style="color:#00567c;border-bottom:2px #055981 solid;" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Taux de réussite en %";}else{echo "Success rate in %";}?></td>
										<td style="color:#00567c;border-bottom:2px #055981 solid;" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Fiches Action Tracker";}else{echo "Action Tracker Sheets";}?></td>
									</tr>
									<?php
									$req = "SELECT Id, Libelle AS Intitule
										FROM soda_questionnaire 
										WHERE Id_Theme=8
										ORDER BY Libelle
										";
									$result=mysqli_query($bdd,$req);
									$nbDonnees=mysqli_num_rows($result);
									
									if($nbDonnees>0){
										$couleur="#ffffff";
										while($row2=mysqli_fetch_array($result)){
											
											$intitule2=$row2['Intitule'];
											//NbCloturé
											$req = "SELECT Id,NumActionTracker
													FROM soda_surveillance 
													WHERE Suppr=0 
													AND AutoSurveillance=0
													AND Etat='Clôturé'
													AND DateSurveillance>='".$dateDebut."'
													AND DateSurveillance<='".$dateDeFin."'
													AND Id_Questionnaire = ".$row2['Id']."
													AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
													";
											
											$reqVol="SELECT Annee,Semaine,
												Volume,
												(SELECT COUNT(soda_surveillance.Id) FROM soda_surveillance WHERE soda_surveillance.Suppr=0 AND AutoSurveillance=0 AND soda_plannifmanuelle.Id_Questionnaire=soda_surveillance.Id_Questionnaire
												AND Etat='Clôturé' AND soda_surveillance.Id_Prestation=soda_plannifmanuelle.Id_Prestation AND ((Id_PlannifManuelle=soda_plannifmanuelle.Id) OR ((Id_PlannifManuelle=0 OR (Id_PlannifManuelle>0 AND (SELECT COUNT(Id) FROM soda_plannifmanuelle WHERE Id=Id_PlannifManuelle)=0)) AND CONCAT(Annee,'_',IF(Semaine<10,CONCAT(0,Semaine),Semaine))=CONCAT(YEAR(DateSurveillance),'_',DATE_FORMAT(DateSurveillance,'%u'))) ) ) AS VolumeCloture
												FROM soda_plannifmanuelle 
												WHERE CONCAT(Annee,'_',IF(Semaine<10,CONCAT(0,Semaine),Semaine)) IN (".$anneeSemaine2.")
												AND Id_Questionnaire = ".$row2['Id']."
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")";
													
											$reqAT = "SELECT DISTINCT NumActionTracker
													FROM soda_surveillance 
													WHERE Suppr=0 
													AND AutoSurveillance=0
													AND Etat='Clôturé'
													AND DateSurveillance>='".$dateDebut."'
													AND DateSurveillance<='".$dateDeFin."'
													AND Id_Questionnaire = ".$row2['Id']."
													AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
													";
											
											$reqNote = "SELECT ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
													FROM soda_surveillance 
													WHERE Suppr=0 
													AND AutoSurveillance=0
													AND Etat='Clôturé'
													AND DateSurveillance>='".$dateDebut."'
													AND DateSurveillance<='".$dateDeFin."'
													AND Id_Questionnaire = ".$row2['Id']."
													AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
													";
											$resultNote=mysqli_query($bdd,$reqNote);
											$nbNote=mysqli_num_rows($resultNote);
											$noteMoyenne=0;
											if($nbNote>0){
												$rowNote=mysqli_fetch_array($resultNote);
												$noteMoyenne=$rowNote['NoteMoyenne'];
											}
											
											$reqConforme = "SELECT ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100,1) AS NoteMoyenne,
													(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Seuil
													FROM soda_surveillance 
													WHERE Suppr=0 
													AND AutoSurveillance=0
													AND Etat='Clôturé'
													AND DateSurveillance>='".$dateDebut."'
													AND DateSurveillance<='".$dateDeFin."'
													AND Id_Questionnaire = ".$row2['Id']."
													AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
													";
											$resultConforme=mysqli_query($bdd,$reqConforme);
											$nConforme=mysqli_num_rows($resultConforme);
											$reussite=0;
											$echec=0;
											if($nConforme>0){
												while($rowConforme=mysqli_fetch_array($resultConforme)){
													if($rowConforme['NoteMoyenne']<$rowConforme['Seuil']){$echec++;}
													else{$reussite++;}
												}
												$reussite=round(($reussite/$nConforme)*100,1);
												$echec=round(($echec/$nConforme)*100,1);
											}
											else{
												$reussite=null;
												$echec=null;
											}
											
											$result2=mysqli_query($bdd,$req);
											$nbCloture=mysqli_num_rows($result2);
											
											$result2=mysqli_query($bdd,$reqAT);
											$nbAT=mysqli_num_rows($result2);
											$listeAT="";
											if($nbAT>0){
												while($rowSurveillance=mysqli_fetch_array($result2)){
													if($rowSurveillance['NumActionTracker']<>""){
														if($listeAT<>""){$listeAT.=", ";}
														$listeAT.=$rowSurveillance['NumActionTracker'];
													}
												}
											}
											
											$resultVolume=mysqli_query($bdd,$reqVol);
											$nbVolume=mysqli_num_rows($resultVolume);
											
											$nbRetard=0;
											$nbPlanif=0;
											$nbVolume2=0;
											$nbAdherence=0;
											if ($nbVolume>0){
												while($rowSurveillance=mysqli_fetch_array($resultVolume)) {
													$lasemaine=$rowSurveillance['Annee']."S";
													if($rowSurveillance['Semaine']<10){$lasemaine.="0".$rowSurveillance['Semaine'];}else{$lasemaine.=$rowSurveillance['Semaine'];}
													$leVolume=0;
													if($rowSurveillance['Volume']>$rowSurveillance['VolumeCloture']){$leVolume=$rowSurveillance['Volume']-$rowSurveillance['VolumeCloture'];}
													if($semaine2>$lasemaine){
														$nbRetard+=$leVolume;
														$nbVolume2+=$leVolume;
													}
													else{
														$nbPlanif+=$leVolume;
													}
												}
											}
											
											$total=$nbCloture+$nbRetard;
											if($total>0){
												$nbRetard=round(($nbRetard/$total)*100,1);
												$nbAdherence=round(($nbCloture/$total)*100,1);
											}

											?>
											<tr style="background-color:<?php echo $couleur;?>">
												<td><?php echo $intitule2;?></td>
												<td><?php echo $nbVolume2;?></td>
												<td><?php if($total>0){echo $nbAdherence."%";}else{echo "N/A";}?></td>
												<td><?php echo $nbCloture;?></td>
												
												<td><?php if($nbCloture>0){echo $noteMoyenne." %";}else{echo "N/A";} ?></td>
												<td><?php if($nConforme>0){echo $reussite." %";}else{echo "N/A";} ?></td>
												<td><?php echo $listeAT;?></td>
											</tr>
											<?php
											if($couleur=="#ffffff"){$couleur="#b7dfe3";}
											else{$couleur="#ffffff";}
										}
										
										//NbCloturé
										$req = "SELECT Id,NumActionTracker
												FROM soda_surveillance 
												WHERE Suppr=0 
												AND AutoSurveillance=0
												AND Etat='Clôturé'
												AND DateSurveillance>='".$dateDebut."'
												AND DateSurveillance<='".$dateDeFin."'
												AND (SELECT Id_Theme FROM soda_questionnaire WHERE soda_questionnaire.Id=soda_surveillance.Id_Questionnaire) = 8
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
												";
										
										$reqVol="SELECT Annee,Semaine,
											Volume,
											(SELECT COUNT(soda_surveillance.Id) FROM soda_surveillance WHERE soda_surveillance.Suppr=0 AND AutoSurveillance=0 AND soda_plannifmanuelle.Id_Questionnaire=soda_surveillance.Id_Questionnaire
											AND Etat='Clôturé' AND soda_surveillance.Id_Prestation=soda_plannifmanuelle.Id_Prestation AND ((Id_PlannifManuelle=soda_plannifmanuelle.Id) OR ((Id_PlannifManuelle=0 OR (Id_PlannifManuelle>0 AND (SELECT COUNT(Id) FROM soda_plannifmanuelle WHERE Id=Id_PlannifManuelle)=0)) AND CONCAT(Annee,'_',IF(Semaine<10,CONCAT(0,Semaine),Semaine))=CONCAT(YEAR(DateSurveillance),'_',DATE_FORMAT(DateSurveillance,'%u'))) ) ) AS VolumeCloture
											FROM soda_plannifmanuelle 
											WHERE CONCAT(Annee,'_',IF(Semaine<10,CONCAT(0,Semaine),Semaine)) IN (".$anneeSemaine2.")
											AND (SELECT Id_Theme FROM soda_questionnaire WHERE soda_questionnaire.Id=soda_plannifmanuelle.Id_Questionnaire) = 8
											AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")";
												
										$reqAT = "SELECT DISTINCT NumActionTracker
												FROM soda_surveillance 
												WHERE Suppr=0 
												AND AutoSurveillance=0
												AND Etat='Clôturé'
												AND DateSurveillance>='".$dateDebut."'
												AND DateSurveillance<='".$dateDeFin."'
												AND (SELECT Id_Theme FROM soda_questionnaire WHERE soda_questionnaire.Id=soda_surveillance.Id_Questionnaire) = 8
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
												";
										
										$reqNote = "SELECT ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
												FROM soda_surveillance 
												WHERE Suppr=0 
												AND AutoSurveillance=0
												AND Etat='Clôturé'
												AND DateSurveillance>='".$dateDebut."'
												AND DateSurveillance<='".$dateDeFin."'
												AND (SELECT Id_Theme FROM soda_questionnaire WHERE soda_questionnaire.Id=soda_surveillance.Id_Questionnaire) = 8
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
												";
										$resultNote=mysqli_query($bdd,$reqNote);
										$nbNote=mysqli_num_rows($resultNote);
										$noteMoyenne=0;
										if($nbNote>0){
											$rowNote=mysqli_fetch_array($resultNote);
											$noteMoyenne=$rowNote['NoteMoyenne'];
										}
										
										$reqConforme = "SELECT ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100,1) AS NoteMoyenne,
												(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Seuil
												FROM soda_surveillance 
												WHERE Suppr=0 
												AND AutoSurveillance=0
												AND Etat='Clôturé'
												AND DateSurveillance>='".$dateDebut."'
												AND DateSurveillance<='".$dateDeFin."'
												AND (SELECT Id_Theme FROM soda_questionnaire WHERE soda_questionnaire.Id=soda_surveillance.Id_Questionnaire) = 8
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBProcessus_UER'].")
												";
										$resultConforme=mysqli_query($bdd,$reqConforme);
										$nConforme=mysqli_num_rows($resultConforme);
										$reussite=0;
										$echec=0;
										if($nConforme>0){
											while($rowConforme=mysqli_fetch_array($resultConforme)){
												if($rowConforme['NoteMoyenne']<$rowConforme['Seuil']){$echec++;}
												else{$reussite++;}
											}
											$reussite=round(($reussite/$nConforme)*100,1);
											$echec=round(($echec/$nConforme)*100,1);
										}
										else{
											$reussite=null;
											$echec=null;
										}
										
										$result2=mysqli_query($bdd,$req);
										$nbCloture=mysqli_num_rows($result2);
										
										$result2=mysqli_query($bdd,$reqAT);
										$nbAT=mysqli_num_rows($result2);
										$listeAT="";
										if($nbAT>0){
											while($rowSurveillance=mysqli_fetch_array($result2)){
												if($rowSurveillance['NumActionTracker']<>""){
													if($listeAT<>""){$listeAT.=", ";}
													$listeAT.=$rowSurveillance['NumActionTracker'];
												}
											}
										}
										
										$resultVolume=mysqli_query($bdd,$reqVol);
										$nbVolume=mysqli_num_rows($resultVolume);
										
										$nbRetard=0;
										$nbPlanif=0;
										$nbVolume2=0;
										$nbAdherence=0;
										if ($nbVolume>0){
											while($rowSurveillance=mysqli_fetch_array($resultVolume)) {
												$lasemaine=$rowSurveillance['Annee']."S";
												if($rowSurveillance['Semaine']<10){$lasemaine.="0".$rowSurveillance['Semaine'];}else{$lasemaine.=$rowSurveillance['Semaine'];}
												$leVolume=0;
												if($rowSurveillance['Volume']>$rowSurveillance['VolumeCloture']){$leVolume=$rowSurveillance['Volume']-$rowSurveillance['VolumeCloture'];}
												if($semaine2>$lasemaine){
													$nbRetard+=$leVolume;
													$nbVolume2+=$leVolume;
												}
												else{
													$nbPlanif+=$leVolume;
												}
											}
										}
										
										$total=$nbCloture+$nbRetard;
										if($total>0){
											$nbRetard=round(($nbRetard/$total)*100,1);
											$nbAdherence=round(($nbCloture/$total)*100,1);
										}
										
										
										
										?>
										<tr style="background-color:<?php echo $couleur;?>">
											<td class="Libelle">Total</td>
											<td class="Libelle"><?php echo $nbVolume2;?></td>
											<td class="Libelle"><?php if($total>0){echo $nbAdherence."%";}else{echo "N/A";}?></td>
											<td class="Libelle"><?php echo $nbCloture;?></td>
											
											<td class="Libelle"><?php if($nbCloture>0){echo $noteMoyenne." %";}else{echo "N/A";} ?></td>
											<td class="Libelle"><?php if($nConforme>0){echo $reussite." %";}else{echo "N/A";} ?></td>
											<td class="Libelle"><?php echo $listeAT;?></td>
										</tr>
										<?php
									}
									
									?>
								</table>
							</td>
						</tr>
						<?php
						}
						?>
					</table>
				</td>
			</tr>
			<?php 
			}
			?>
		</table>
	</td>
</tr>
<tr><td height="150"></td></tr>
</table>	
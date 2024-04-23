<script>
	function CocheQuestionnaire(Id_Theme){
	table = document.getElementsByTagName('input');
	table2 = document.getElementsByTagName('input');
	tableTr = document.getElementsByTagName('tr');
	for (l=0;l<table.length;l++){
		if (table[l].type == 'checkbox'){
			if(table[l].value == 'Theme_'+Id_Theme && table[l].checked == true){
				for(j=0;j<table2.length;j++){
					if (table2[j].type == 'checkbox'){
						if(table2[j].value.substring(0,table2[j].value.indexOf("_")) == Id_Theme){
							table2[j].checked = true;
						}
					}
				}
			}
			else if(table[l].value == 'Theme_'+Id_Theme && table[l].checked == false){
				document.getElementById('Image_PlusMoins_'+Id_Theme).src="../../Images/Plus.gif";
				for (k=0;k<tableTr.length;k++){
					if(tableTr[k].getAttribute("value") == Id_Theme){
						tableTr[k].style.display = "none";
					}
				}
				for(j=0;j<table2.length;j++){
					if (table2[j].type == 'checkbox'){
						if(table2[j].value.substring(0,table2[j].value.indexOf("_")) == Id_Theme){
							table2[j].checked = false;
						}
					}
				}
			}
		}
	}
}
function AfficheQuestionnaire(Id_Theme){
	var SourceImage = document.getElementById('Image_PlusMoins_'+Id_Theme).src;
	var result = SourceImage.substring(SourceImage.length -6, SourceImage.length);
	table = document.getElementsByTagName('input');tableTr = document.getElementsByTagName('tr');
	if(result == "us.gif"){
		document.getElementById('Image_PlusMoins_'+Id_Theme).src="../../Images/Moins.gif";
		for (l=0;l<table.length;l++){
			if (table[l].type == 'checkbox'){
				for (k=0;k<tableTr.length;k++){
					if(tableTr[k].getAttribute("value") == Id_Theme){
						tableTr[k].style.display = "";
					}
				}
			}
		}
	}
	else{
		document.getElementById('Image_PlusMoins_'+Id_Theme).src="../../Images/Plus.gif";
		for (l=0;l<table.length;l++){
			if (table[l].type == 'checkbox'){
				for (k=0;k<tableTr.length;k++){
					if(tableTr[k].getAttribute("value") == Id_Theme){
						tableTr[k].style.display = "none";
					}
				}
			}
		}
	}
}
</script>
<?php
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
	$req="SELECT Id,Libelle,Actif,Specifique,Id_Theme,SeuilReussite
		FROM soda_questionnaire 
		WHERE Suppr=0 ";
	$resultQuestionnaire=mysqli_query($bdd,$req);
	$nbQuestionnaire=mysqli_num_rows($resultQuestionnaire);	
	if ($nbQuestionnaire > 0)
	{
		while($row=mysqli_fetch_array($resultQuestionnaire))
		{
			$selected="";
			if($_POST && !isset($_POST['btnReset2'])){
				if(isset($_POST[$row['Id_Theme']."_".$row['Id']])){
					if($Questionnaires<>''){$Questionnaires.=",";}
					$Questionnaires.=$row['Id'];
					$SeuilReussite2=$row['SeuilReussite'];
					$nbQ++;
				}
			}
		}
	}
	
	if($_POST){$annee=$_POST['annee'];}
	if($annee==""){$annee=date("Y");}
	
	$_SESSION['FiltreSODATDBThematique_ModeAffichage']=$_POST['ModeAffichage'];
	$_SESSION['FiltreSODATDBThematique_Plage']=$_POST['plage'];
	$_SESSION['FiltreSODATDBThematique_UER']=$Plateformes;
	$_SESSION['FiltreSODATDBThematique_Questionnaire']=$Questionnaires;
	$_SESSION['FiltreSODATDBThematique_Theme']=$Themes;
	$_SESSION['FiltreSODATDBThematique_Annee']=$annee;
	$_SESSION['FiltreSODATDBThematique_Mois']=$_POST['mois'];
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
				<td class="Libelle" style="background-color:#B2AE9F;"><?php if($LangueAffichage=="FR"){echo "Mode d'affichage";}else{echo "Display mode";}?> : </td>
			</tr>
			<tr>
				<td>
					<?php 
					$selected="";
					if($_SESSION['FiltreSODATDBThematique_ModeAffichage']=='Mois'){$selected="checked";}
					?>
					<input type="radio" name="ModeAffichage" value="Mois" <?php echo $selected; ?>/>
					<?php 
					if($LangueAffichage=="FR"){echo "Mois";}else{echo "Month";}
					
					$selected="";
					if($_SESSION['FiltreSODATDBThematique_ModeAffichage']=='UER'){$selected="checked";}
					?>
					<input type="radio" name="ModeAffichage" value="UER" <?php echo $selected; ?>/>UER
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" style="background-color:#B2AE9F;"><?php if($LangueAffichage=="FR"){echo "Plage";}else{echo "Range";}?> : </td>
			</tr>
			<tr>
				<td>
					<?php if($LangueAffichage=="FR"){echo "Année";}else{echo "Year";}?>
					<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $_SESSION['FiltreSODATDBThematique_Annee']; ?>" size="5"/>
				</td>
			</tr>
			<tr>
				<td>
					<?php if($_SESSION['Langue']=="EN"){echo "Month";}else{echo "Mois";} ?>&nbsp;&nbsp;
					<select id="mois" name="mois" onchange="submit();">
						<?php
							if($_SESSION["Langue"]=="EN"){
								$arrayMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
								
							}
							else{
								$arrayMois=array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
							}
							$mois=$_SESSION['FiltreSODATDBThematique_Mois'];
							if($_POST){$mois=$_POST['mois'];}
							$_SESSION['FiltreSODATDBThematique_Mois']=$mois;
							
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
					<?php 
					$selected="";
					if($_SESSION['FiltreSODATDBThematique_Plage']=='12'){$selected="checked";}
					?>
					<input type="radio" name="plage" value="12" <?php echo $selected; ?>/>
					<?php 
					if($LangueAffichage=="FR"){echo "12 derniers mois";}else{echo "Last 12 months";}
					
					$selected="";
					if($_SESSION['FiltreSODATDBThematique_Plage']=='Annee'){$selected="checked";}
					?>
					<input type="radio" name="plage" value="Annee" <?php echo $selected; ?>/>
					<?php 
					if($LangueAffichage=="FR"){echo "Année civile";}else{echo "Calendar year";}
					?>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" style="background-color:#B2AE9F;"><?php if($LangueAffichage=="FR"){echo "Thème / Questionnaire";}else{echo "Theme / Questionnaire";}?> : </td>
			</tr>
			<tr>
				<td>
					<input type="checkbox" name="selectAllThemeQuestionnaire" id="selectAllThemeQuestionnaire" onclick="SelectionnerTout('ThemeQuestionnaire')" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
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
									echo "<tr><td><input onchange='CocheQuestionnaire(".$row['Id'].")' class='checkTheme' type='checkbox' name='Theme[]' value='Theme_".$row['Id']."' ";
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
										if($_SESSION['FiltreSODATDBThematique_Theme']==-1){
											echo "checked";
										}
										else{
											$tabTheme=explode(",",$_SESSION['FiltreSODATDBThematique_Theme']);
											foreach($tabTheme as $theme){
												if ($theme == $row['Id']){
													echo "checked";
												}
											}
										}
									}
									
									echo ">".$row['Libelle']."";
									echo " <img id='Image_PlusMoins_".$row['Id']."' src='../../Images/Plus.gif' onclick='javascript:AfficheQuestionnaire(".$row['Id'].");'>";
									echo "</td></tr>". "\n";
										$req="SELECT Id,Libelle,Actif,Specifique,Id_Theme
											FROM soda_questionnaire 
											WHERE Suppr=0 
											AND Id_Theme=".$row['Id']." 
											ORDER BY Actif,Libelle ";
										$resultQuestionnaire=mysqli_query($bdd,$req);
										$nbQuestionnaire=mysqli_num_rows($resultQuestionnaire);			
										if ($nbQuestionnaire > 0){
											while($rowQuestionnaire=mysqli_fetch_array($resultQuestionnaire)){
												$checked = "";
												if($_POST){
													if (isset($_POST[$row['Id']."_".$rowQuestionnaire['Id']])){
														$checked = "checked";
													}
												}
												else{
													if($_SESSION['FiltreSODATDBThematique_Questionnaire']==-1){
														$checked = "checked";
													}
													else{
														$tabQuestionnaire=explode(",",$_SESSION['FiltreSODATDBThematique_Questionnaire']);
														foreach($tabQuestionnaire as $questionnaire){
															if ($questionnaire == $rowQuestionnaire['Id']){
																$checked = "checked";
															}
														}
													}
												}
												echo "<tr style='display:none;' value='".$row['Id']."'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
												$actif="";
												if($rowQuestionnaire['Actif']==1){$actif=" [INACTIF]";}
												echo "<input type='checkbox' id='".$row['Id']."_".$rowQuestionnaire['Id']."' name='".$row['Id']."_".$rowQuestionnaire['Id']."' value='".$row['Id']."_".$rowQuestionnaire['Id']."' ".$checked." >".$rowQuestionnaire['Libelle'].$actif."</td></tr>". "\n";
											}
										}
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
					<input type="checkbox" name="selectAllUER" id="selectAllUER" onclick="SelectionnerTout('UER')" <?php if($_SESSION['FiltreSODATDBThematique_UER']==-1){echo "checked";}?> /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
				</td>
			</tr>
			<tr>
				<td>
					<div id='Div_Plateforme' style='height:200px;width:300px;overflow:auto;'>
					<table>
				<?php
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
								if(isset($_POST['plateforme'.$row['Id']])){$selected="checked";}
							}
							else{
								if($_SESSION['FiltreSODATDBThematique_UER']==-1){
									$selected="checked";
								}
								else{
									$tabUER=explode(",",$_SESSION['FiltreSODATDBThematique_UER']);
									foreach($tabUER as $uer){
										if ($uer == $row['Id']){
											$selected="checked";
										}
									}
								}
							}
							echo "<tr><td><input class='checkUER' type='checkbox' ".$selected." value='".$row['Id']."' id='plateforme".$row['Id']."' name='plateforme".$row['Id']."'>".stripslashes($row['Libelle'])."</td></tr>";
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
				if($_SESSION['FiltreSODATDBThematique_Questionnaire']<>"-1" && $_SESSION['FiltreSODATDBThematique_UER']<>"-1" && $_SESSION['FiltreSODATDBThematique_Questionnaire']<>"" && $_SESSION['FiltreSODATDBThematique_UER']<>""){
					$titreJauge="";
					$titreSurveillances="";
					$titreCumul="";
					$legendeCumulSurveillance="";
					$legendeCumulSurveillant="";
					$legendeCumulSurveille="";
					$laPlage="";
					if($_SESSION['FiltreSODATDBThematique_Plage']=="12"){
						$dateDebut=date("Y-m-1",strtotime(date($_SESSION['FiltreSODATDBThematique_Annee']."-".$_SESSION['FiltreSODATDBThematique_Mois']."-1")." -1 Year"));
						$dateFin=date("Y-m-d",strtotime(date($_SESSION['FiltreSODATDBThematique_Annee']."-".$_SESSION['FiltreSODATDBThematique_Mois']."-1")." -1 day"));
						
						if($_SESSION['Langue']=="FR"){
							$titreJauge="Répartition des surveillances sur les 12 derniers mois";
							$laPlage=" sur les 12 derniers mois";
						}
						else{
							$titreJauge="Breakdown of surveillances over the last 12 months";
							$laPlage=" over the last 12 months";
						}
					}
					else{
						$dateDebut=date($_SESSION['FiltreSODATDBThematique_Annee'].'-01-01');
						$dateFin=date($_SESSION['FiltreSODATDBThematique_Annee'].'-12-31');
						
						if($_SESSION['Langue']=="FR"){
							$titreJauge="Répartition des surveillances sur l'année civile";
							$laPlage=" sur l'année civile";
						}
						else{
							$titreJauge="Distribution of monitoring over the calendar year";
							$laPlage=" over the calendar year";
						}
					}
					
					$i=0;
					$arraySurveillances=array();
					$arrayJaugeSurveillances=array();
					$arrayNoteMoyenne=array();
					$arrayParetoNC=array();
					$arrayParetoNC2=array();
					$arrayParetoNC3=array();
					$arrayActionNC=array();
					$arrayCumul=array();
					$arrayParetoNA=array();
					$SurveillanceParSurveille="";
					$SurveillanceParSurveillant="";
					
					$seuilReussite=80;
					if($nbQ==1){$seuilReussite=$SeuilReussite2;}
					
					$laDate=$dateDebut;
					
					$lundi=$laDate;
					if(date("N",strtotime($lundi." +0 day"))==1){$lundi=$lundi;}
					else{$lundi=date("Y-m-d",strtotime($lundi."last Monday"));}
					$semaine="";
					$anneeSemaine="";
					$lundiFin=date("Y-m-d",strtotime($laDate." +12 month"));
					if(date("N",strtotime($lundiFin." +0 day"))==1){$lundiFin=$lundiFin;}
					else{$lundiFin=date("Y-m-d",strtotime($lundiFin."last Monday"));}

					while($lundi<=$lundiFin){
						if(date("N",strtotime($lundi." +0 day"))==1){$lundi=$lundi;}
						else{$lundi=date("Y-m-d",strtotime($lundi."last Monday"));}
						$jeudi=date("Y-m-d",strtotime($lundi." +3 day"));

						if($jeudi>=$dateDebut && $lundi<=$dateFin){
							$tabDate = explode('-', $jeudi);
							$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);

							if($anneeSemaine<>""){$anneeSemaine.=",";}
							$anneeSemaine.= "'".date('Y_W', $timestamp)."'";
						}
						$lundi=date("Y-m-d",strtotime($lundi." +7 day"));
					}
					
					$semaine2=date('Y')."S";
					if(date('W')<10){$semaine2.=date('W');}else{$semaine2.=date('W');}
					
					$anneeMoisEC=date('Y')."_";
					$anneeMoisEC.=date('m');
					
					$nbCumulSurveillance=0;
					$nbCumulSurveillant=0;
					$nbCumulSurveille=0;
					if($_SESSION['FiltreSODATDBThematique_ModeAffichage']=="Mois"){
						if($_SESSION['Langue']=="FR"){$titreSurveillances="Nombre de surveillances / Mois";}
						else{$titreSurveillances="Number of monitorings / Month";}
						if($_SESSION['Langue']=="FR"){
							$titreCumul="Cumul des surveillances, surveillants et surveillés";
							$legendeCumulSurveillance="Cumul surveillance";
							$legendeCumulSurveillant="Cumul surveillant";
							$legendeCumulSurveille="Cumul surveillé";
						}
						else{
							$titreCumul="Accumulation of supervisions, supervisors and supervised";
							$legendeCumulSurveillance="Cumulative monitoring";
							$legendeCumulSurveillant="Accumulation supervisor";
							$legendeCumulSurveille="Accumulation monitored";
						}
						for($nbMois=1;$nbMois<=12;$nbMois++){
							$anneeEC=date("Y",strtotime($laDate." +0 month"));
							$moisEC=date("m",strtotime($laDate." +0 month"));
							
							$lundi=$laDate;
							if(date("N",strtotime($lundi." +0 day"))==1){$lundi=$lundi;}
							else{$lundi=date("Y-m-d",strtotime($lundi."last Monday"));}
							$semaine="";
							
							$lundiFin=date("Y-m-d",strtotime($laDate." +1 month"));
							if(date("N",strtotime($lundiFin." +0 day"))==1){$lundiFin=$lundiFin;}
							else{$lundiFin=date("Y-m-d",strtotime($lundiFin."last Monday"));}
							while($lundi<=$lundiFin){
								if(date("N",strtotime($lundi." +0 day"))==1){$lundi=$lundi;}
								else{$lundi=date("Y-m-d",strtotime($lundi."last Monday"));}
								$jeudi=date("Y-m-d",strtotime($lundi." +3 day"));
								$dimanche=date("Y-m-d",strtotime($lundi." +6 day"));
								
								if(date("Y-m",strtotime($jeudi))==$anneeEC."-".$moisEC){
									$tabDate = explode('-', $jeudi);
									$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
									
									if($semaine<>""){$semaine.=",";}
									$semaine.= date('W', $timestamp);
								}
								$lundi=$dimanche;
								$lundi=date("Y-m-d",strtotime($lundi." +1 day"));
							}
							$req="SELECT Volume
									FROM soda_plannifmanuelle 
									WHERE Annee=".$anneeEC." 
									AND Semaine IN (".$semaine.")
									AND Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
									AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].") 
									AND Volume>0";

							$resultVolumePlanifie=mysqli_query($bdd,$req);
							$nbVolumePlanifie=mysqli_num_rows($resultVolumePlanifie);
									
							$req = "SELECT COUNT(Id) AS Nb,
									ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
									FROM soda_surveillance 
									WHERE Suppr=0 
									AND AutoSurveillance=0
									AND Etat='Clôturé'
									AND YEAR(DateSurveillance)=".$anneeEC."
									AND MONTH(DateSurveillance)=".$moisEC."
									AND Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
									AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
									";
							$result=mysqli_query($bdd,$req);
							$nbCloture=mysqli_num_rows($result);
							$noteMoyenne=0;
							if($nbCloture>0){
								$row=mysqli_fetch_array($result);
								$noteMoyenne=$row['NoteMoyenne'];
								$nbCloture=$row['Nb'];
							}
							
							$req = "SELECT Id
									FROM soda_surveillance 
									WHERE Suppr=0 
									AND AutoSurveillance=0
									AND Etat='Brouillon'
									AND YEAR(DateSurveillance)=".$anneeEC."
									AND MONTH(DateSurveillance)=".$moisEC."
									AND Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
									AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
									";
							$result=mysqli_query($bdd,$req);
							$nbBrouillon=mysqli_num_rows($result);
							
							$req = "SELECT Id
									FROM soda_surveillance 
									WHERE Suppr=0 
									AND AutoSurveillance=0
									AND Etat='En cours - papier'
									AND YEAR(DateSurveillance)=".$anneeEC."
									AND MONTH(DateSurveillance)=".$moisEC."
									AND Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
									AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
									";
							$result=mysqli_query($bdd,$req);
							$nbPapier=mysqli_num_rows($result);
							
							$req = "SELECT DISTINCT Id_Surveillant
									FROM soda_surveillance 
									WHERE Suppr=0 
									AND AutoSurveillance=0
									AND Etat='Clôturé'
									AND Id_Surveillant>0
									AND YEAR(DateSurveillance)=".$anneeEC."
									AND MONTH(DateSurveillance)=".$moisEC."
									AND Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
									AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
									";
							$result=mysqli_query($bdd,$req);
							$nbSurveillant=mysqli_num_rows($result);
							
							$req = "SELECT DISTINCT Id_Surveille
									FROM soda_surveillance 
									WHERE Suppr=0 
									AND AutoSurveillance=0
									AND Etat='Clôturé'
									AND Id_Surveille>0
									AND YEAR(DateSurveillance)=".$anneeEC."
									AND MONTH(DateSurveillance)=".$moisEC."
									AND Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
									AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
									";
							$result=mysqli_query($bdd,$req);
							$nbSurveille=mysqli_num_rows($result);
							
							$nbCumulSurveillance+=$nbCloture;
							$nbCumulSurveillant+=$nbSurveillant;
							$nbCumulSurveille+=$nbSurveille;
							
							if($anneeEC."_".$moisEC>$anneeMoisEC){
								$nbCumulSurveillance=null;
								$nbCumulSurveillant=null;
								$nbCumulSurveille=null;
							}
							$arraySurveillances[$i]=array("Mois" => utf8_encode($MoisLettre[$moisEC-1]." ".$anneeEC),"Realise" => valeurSinonNull($nbCloture),"Planifie" => valeurSinonNull($nbVolumePlanifie),"Brouillon" => valeurSinonNull($nbBrouillon), "Papier" => valeurSinonNull($nbPapier));
							$arrayNoteMoyenne[$i]=array("Mois" => utf8_encode($MoisLettre[$moisEC-1]." ".$anneeEC),"NoteMoyenne" => valeurSinonNull($noteMoyenne),"Seuil" => valeurSinonNull($seuilReussite));
							$arrayCumul[$i]=array("Mois" => utf8_encode($MoisLettre[$moisEC-1]." ".$anneeEC),"Surveillance" => valeurSinonNull($nbCumulSurveillance),"Surveillant" => valeurSinonNull($nbCumulSurveillant),"Surveille" => valeurSinonNull($nbCumulSurveille));
							$laDate=date("Y-m-d",strtotime($laDate." +1 month"));
							$i++;
						}
					}
					else{
						if($_SESSION['Langue']=="FR"){$titreSurveillances="Nombre de surveillances / UER";}
						else{$titreSurveillances="Number of monitorings / UER";}
						if($_SESSION['Langue']=="FR"){
							$titreCumul="Surveillances, surveillants et surveillés / UER";
							$legendeCumulSurveillance="Surveillance";
							$legendeCumulSurveillant="Surveillant";
							$legendeCumulSurveille="Surveillé";
						}
						else{
							$titreCumul="Supervisions, supervisors and supervised / UER";
							$legendeCumulSurveillance="Monitoring";
							$legendeCumulSurveillant="Supervisor";
							$legendeCumulSurveille="Monitored";
						}
						
						$req="SELECT DISTINCT new_competences_plateforme.Id,
							new_competences_plateforme.Libelle
							FROM new_competences_prestation
							LEFT JOIN new_competences_plateforme
							ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
							WHERE new_competences_prestation.Id_Plateforme IN (".$_SESSION['FiltreSODATDBThematique_UER'].") 
							ORDER BY new_competences_plateforme.Libelle;";
						$resultPlate=mysqli_query($bdd,$req);
						$nbPlate=mysqli_num_rows($resultPlate);
						
						if($nbPlate>0){
							while($rowPla=mysqli_fetch_array($resultPlate)){
								$req="SELECT Volume
									FROM soda_plannifmanuelle 
									WHERE CONCAT(Annee,'_',Semaine) IN (".$anneeSemaine.")
									AND Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
									AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) =".$rowPla['Id']." ";
								$resultVolumePlanifie=mysqli_query($bdd,$req);
								$nbVolumePlanifie=mysqli_num_rows($resultVolumePlanifie);
										
								$req = "SELECT COUNT(Id) AS Nb, 
										ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
										FROM soda_surveillance 
										WHERE Suppr=0 
										AND AutoSurveillance=0
										AND Etat='Clôturé'
										AND DateSurveillance>='".$dateDebut."'
										AND DateSurveillance<='".$dateFin."'
										AND Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
										AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) =".$rowPla['Id']."
										";
								$result=mysqli_query($bdd,$req);
								$nbCloture=mysqli_num_rows($result);
								$noteMoyenne=0;
								if($nbCloture>0){
									$row=mysqli_fetch_array($result);
									$noteMoyenne=$row['NoteMoyenne'];
									$nbCloture=$row['Nb'];
								}
								
								$req = "SELECT Id
										FROM soda_surveillance 
										WHERE Suppr=0 
										AND AutoSurveillance=0
										AND Etat='Brouillon'
										AND DateSurveillance>='".$dateDebut."'
										AND DateSurveillance<='".$dateFin."'
										AND Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
										AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) =".$rowPla['Id']."
										";
								$result=mysqli_query($bdd,$req);
								$nbBrouillon=mysqli_num_rows($result);
								
								$req = "SELECT Id
										FROM soda_surveillance 
										WHERE Suppr=0 
										AND AutoSurveillance=0
										AND Etat='En cours - papier'
										AND DateSurveillance>='".$dateDebut."'
										AND DateSurveillance<='".$dateFin."'
										AND Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
										AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) =".$rowPla['Id']."
										";
								$result=mysqli_query($bdd,$req);
								$nbPapier=mysqli_num_rows($result);
								
								$req = "SELECT DISTINCT Id_Surveillant
										FROM soda_surveillance 
										WHERE Suppr=0 
										AND AutoSurveillance=0
										AND Etat='Clôturé'
										AND Id_Surveillant>0
										AND DateSurveillance>='".$dateDebut."'
										AND DateSurveillance<='".$dateFin."'
										AND Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
										AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) =".$rowPla['Id']."
										";
								$result=mysqli_query($bdd,$req);
								$nbSurveillant=mysqli_num_rows($result);
								
								$req = "SELECT DISTINCT Id_Surveille
										FROM soda_surveillance 
										WHERE Suppr=0 
										AND AutoSurveillance=0
										AND Etat='Clôturé'
										AND Id_Surveille>0
										AND DateSurveillance>='".$dateDebut."'
										AND DateSurveillance<='".$dateFin."'
										AND Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
										AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) =".$rowPla['Id']."
										";
								$result=mysqli_query($bdd,$req);
								$nbSurveille=mysqli_num_rows($result);
								
								$arraySurveillances[$i]=array("Mois" => utf8_encode($rowPla['Libelle']),"Realise" => valeurSinonNull($nbCloture),"Planifie" => valeurSinonNull($nbVolumePlanifie),"Brouillon" => valeurSinonNull($nbBrouillon), "Papier" => valeurSinonNull($nbPapier));
								$arrayNoteMoyenne[$i]=array("Mois" => utf8_encode($rowPla['Libelle']),"NoteMoyenne" => valeurSinonNull($noteMoyenne),"Seuil" => valeurSinonNull($seuilReussite));
								$arrayCumul[$i]=array("Mois" => utf8_encode($rowPla['Libelle']),"Surveillance" => valeurSinonNull($nbCloture),"Surveillant" => valeurSinonNull($nbSurveillant),"Surveille" => valeurSinonNull($nbSurveille));
								$i++;
							}
						}
					}
	
					$req="SELECT Annee,Semaine,
						Volume,
						(SELECT COUNT(soda_surveillance.Id) FROM soda_surveillance WHERE soda_surveillance.Suppr=0 AND AutoSurveillance=0 AND soda_plannifmanuelle.Id_Questionnaire=soda_surveillance.Id_Questionnaire
						AND Etat='Clôturé' AND soda_surveillance.Id_Prestation=soda_plannifmanuelle.Id_Prestation AND ((Id_PlannifManuelle=soda_plannifmanuelle.Id) OR ((Id_PlannifManuelle=0 OR (Id_PlannifManuelle>0 AND (SELECT COUNT(Id) FROM soda_plannifmanuelle WHERE Id=Id_PlannifManuelle)=0)) AND CONCAT(Annee,'_',IF(Semaine<10,CONCAT(0,Semaine),Semaine))=CONCAT(YEAR(DateSurveillance),'_',DATE_FORMAT(DateSurveillance,'%u'))) ) ) AS VolumeCloture
						FROM soda_plannifmanuelle 
						WHERE CONCAT(Annee,'_',IF(Semaine<10,CONCAT(0,Semaine),Semaine)) IN (".$anneeSemaine.")
						AND Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
						AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].") ";
					$resultVolume=mysqli_query($bdd,$req);
					$nbVolume=mysqli_num_rows($resultVolume);
					
					$nbRetard=0;
					$nbPlanif=0;
					if ($nbVolume>0){
						while($rowSurveillance=mysqli_fetch_array($resultVolume)) {
							$lasemaine=$rowSurveillance['Annee']."S";
							if($rowSurveillance['Semaine']<10){$lasemaine.="0".$rowSurveillance['Semaine'];}else{$lasemaine.=$rowSurveillance['Semaine'];}
							$leVolume=0;
							if($rowSurveillance['Volume']>$rowSurveillance['VolumeCloture']){$leVolume=$rowSurveillance['Volume']-$rowSurveillance['VolumeCloture'];}
							if($semaine2>$lasemaine){
								$nbRetard+=$leVolume;
							}
							else{
								$nbPlanif+=$leVolume;
							}
						}
					}
					
					$req = "SELECT Id
							FROM soda_surveillance 
							WHERE Suppr=0 
							AND AutoSurveillance=0
							AND Etat='Clôturé'
							AND DateSurveillance>='".$dateDebut."'
							AND DateSurveillance<='".$dateFin."'
							AND Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
							AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
							";
					$result=mysqli_query($bdd,$req);
					$nbCloture=mysqli_num_rows($result);
					
					$req = "SELECT Id
							FROM soda_surveillance 
							WHERE Suppr=0 
							AND AutoSurveillance=0
							AND Etat='Brouillon'
							AND DateSurveillance>='".$dateDebut."'
							AND DateSurveillance<='".$dateFin."'
							AND Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
							AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
							";
					$result=mysqli_query($bdd,$req);
					$nbBrouillon=mysqli_num_rows($result);
					
					$req = "SELECT Id
							FROM soda_surveillance 
							WHERE Suppr=0 
							AND AutoSurveillance=0
							AND Etat='En cours - papier'
							AND DateSurveillance>='".$dateDebut."'
							AND DateSurveillance<='".$dateFin."'
							AND Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
							AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
							";
					$result=mysqli_query($bdd,$req);
					$nbPapier=mysqli_num_rows($result);
					
					if($_SESSION['Langue']=="EN"){
						$arrayJaugeSurveillances[0]=array("Abscisse" => utf8_encode("Late"),"Nombre" => valeurSinonNull($nbRetard));
						$arrayJaugeSurveillances[1]=array("Abscisse" => utf8_encode("Planned"),"Nombre" => valeurSinonNull($nbPlanif));
						$arrayJaugeSurveillances[2]=array("Abscisse" => utf8_encode("Completed"),"Nombre" => valeurSinonNull($nbCloture));
						$arrayJaugeSurveillances[3]=array("Abscisse" => utf8_encode("Draft"),"Nombre" => valeurSinonNull($nbBrouillon));
						$arrayJaugeSurveillances[4]=array("Abscisse" => utf8_encode("En cours papier"),"Nombre" => valeurSinonNull($nbPapier));
					}
					else{
						$arrayJaugeSurveillances[0]=array("Abscisse" => utf8_encode("En retard"),"Nombre" => valeurSinonNull($nbRetard));
						$arrayJaugeSurveillances[1]=array("Abscisse" => utf8_encode("Planifiées"),"Nombre" => valeurSinonNull($nbPlanif));
						$arrayJaugeSurveillances[2]=array("Abscisse" => utf8_encode("Réalisées"),"Nombre" => valeurSinonNull($nbCloture));
						$arrayJaugeSurveillances[3]=array("Abscisse" => utf8_encode("Brouillon"),"Nombre" => valeurSinonNull($nbBrouillon));
						$arrayJaugeSurveillances[4]=array("Abscisse" => utf8_encode("In progress paper"),"Nombre" => valeurSinonNull($nbPapier));
					}

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
							AND soda_surveillance.DateSurveillance>='".$dateDebut."'
							AND soda_surveillance.DateSurveillance<='".$dateFin."'
							AND soda_surveillance.Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
							AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
							AND soda_surveillance_question.Etat='NC'
							AND soda_surveillance_question.Id_Question>0
							GROUP BY soda_surveillance_question.Id_Question
							ORDER BY Nb DESC
							LIMIT 10
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
					if($_SESSION['Langue']=="EN"){$tabParetoNC.="Non-compliant questions in volume (Top 10)";}else{$tabParetoNC.="Questions non conformes en volume (Top 10)";}
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
								AND tab.DateSurveillance>='".$dateDebut."'
								AND tab.DateSurveillance<='".$dateFin."'
								AND tab.Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
								AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=tab.Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
								AND tab.Id IN (SELECT Id_Surveillance FROM soda_surveillance_question AS tab2 WHERE tab2.Id_Surveillance=tab.Id AND tab2.Id_Question=soda_surveillance_question.Id_Question)
								) AS NbSurveillance,
								(
								COUNT(soda_surveillance_question.Id_Question)/
								(SELECT COUNT(tab.Id) 
								FROM soda_surveillance AS tab 
								WHERE tab.Suppr=0 
								AND tab.AutoSurveillance=0
								AND tab.Etat='Clôturé'
								AND tab.DateSurveillance>='".$dateDebut."'
								AND tab.DateSurveillance<='".$dateFin."'
								AND tab.Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
								AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=tab.Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
								AND tab.Id IN (SELECT Id_Surveillance FROM soda_surveillance_question AS tab2 WHERE tab2.Id_Surveillance=tab.Id AND tab2.Id_Question=soda_surveillance_question.Id_Question)
								)
								) AS Nb
							FROM soda_surveillance_question
							LEFT JOIN soda_surveillance 
							ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
							WHERE soda_surveillance.Suppr=0 
							AND soda_surveillance.AutoSurveillance=0
							AND soda_surveillance.Etat='Clôturé'
							AND soda_surveillance.DateSurveillance>='".$dateDebut."'
							AND soda_surveillance.DateSurveillance<='".$dateFin."'
							AND soda_surveillance.Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
							AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
							AND soda_surveillance_question.Etat='NC'
							AND soda_surveillance_question.Id_Question>0
							GROUP BY soda_surveillance_question.Id_Question
							ORDER BY 
								Nb DESC, NbQuestion DESC
							LIMIT 10
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
					if($_SESSION['Langue']=="EN"){$tabParetoNC2.="Non-compliant questions in ratio (Top 10)";}else{$tabParetoNC2.="Questions non conformes en ratio (Top 10)";}
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
					
					$req = "SELECT Action,
							COUNT(soda_surveillance_question.Action) AS Nb
							FROM soda_surveillance_question
							LEFT JOIN soda_surveillance 
							ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
							WHERE soda_surveillance.Suppr=0 
							AND soda_surveillance.AutoSurveillance=0
							AND soda_surveillance.Etat='Clôturé'
							AND soda_surveillance.DateSurveillance>='".$dateDebut."'
							AND soda_surveillance.DateSurveillance<='".$dateFin."'
							AND soda_surveillance.Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
							AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
							AND soda_surveillance_question.Etat='NC'
							AND soda_surveillance_question.Id_Question>0
							GROUP BY Action
							ORDER BY Action
							";
					$result=mysqli_query($bdd,$req);
					$nbAction=mysqli_num_rows($result);
					$i=0;
					if($nbPareto>0){
						while($rowPareto=mysqli_fetch_array($result)) {
							$arrayActionNC[$i]=array("Abscisse" => utf8_encode($rowPareto['Action']),"Nombre" => valeurSinonNull($rowPareto['Nb']));
							$i++;
						}
					}
					
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
							AND soda_surveillance.DateSurveillance>='".$dateDebut."'
							AND soda_surveillance.DateSurveillance<='".$dateFin."'
							AND soda_surveillance.Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
							AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
							AND soda_surveillance_question.Etat='NA'
							AND soda_surveillance_question.Id_Question>0
							GROUP BY soda_surveillance_question.Id_Question
							ORDER BY COUNT(soda_surveillance_question.Id_Question) DESC
							LIMIT 10
							";

					$result=mysqli_query($bdd,$req);
					$nbPareto=mysqli_num_rows($result);
					$i=0;
					if($nbPareto>0){
						while($rowPareto=mysqli_fetch_array($result)) {
							$arrayParetoNA[$i]=array("Mois" => utf8_encode($rowPareto['Id_Question']),"Libelle" => utf8_encode($rowPareto['Questionnaire']." <br> ".$rowPareto['Question']),"Nombre" => valeurSinonNull($rowPareto['Nb']));
							$i++;
						}
					}
					
					$tabParetoNA="<table style='border:1px solid black;border-spacing:0;padding:0px 0px 0px 0px;'>";
						foreach($arrayParetoNA as $na){
							$tabParetoNA.= "<tr>";
								$tabParetoNA.= "<td style='border:1px solid black;text-align:center;width:10%'>".$na['Mois']."</td>";
								$tabParetoNA.= "<td style='border:1px solid black;text-align:left;width:80%'>".stripslashes(utf8_decode($na['Libelle']))."</td>";
								$tabParetoNA.= "<td style='border:1px solid black;text-align:center;width:10%'>".$na['Nombre']."</td>";
							$tabParetoNA.= "</tr>";
						}
					$tabParetoNA."</table>";
					
					$arrayParetoNA=array_reverse($arrayParetoNA);
					
					$req = "SELECT DISTINCT Id_Surveillant
							FROM soda_surveillance 
							WHERE Suppr=0 
							AND AutoSurveillance=0
							AND Etat='Clôturé'
							AND Id_Surveillant>0
							AND DateSurveillance>='".$dateDebut."'
							AND DateSurveillance<='".$dateFin."'
							AND Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
							AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
							";
					$result=mysqli_query($bdd,$req);
					$nbSurveillant=mysqli_num_rows($result);
					
					$req = "SELECT DISTINCT Id_Surveille
							FROM soda_surveillance 
							WHERE Suppr=0 
							AND AutoSurveillance=0
							AND Etat='Clôturé'
							AND Id_Surveille>0
							AND DateSurveillance>='".$dateDebut."'
							AND DateSurveillance<='".$dateFin."'
							AND Id_Questionnaire IN (".$_SESSION['FiltreSODATDBThematique_Questionnaire'].")
							AND IF(Id_Prestation>0,(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation),Id_Plateforme) IN (".$_SESSION['FiltreSODATDBThematique_UER'].")
							";
					$result=mysqli_query($bdd,$req);
					$nbSurveille=mysqli_num_rows($result);
					
					if($nbSurveillant>0){
						$SurveillanceParSurveillant=round($nbCloture/$nbSurveillant,2);
					}
					if($nbSurveille>0){
						$SurveillanceParSurveille=round($nbCloture/$nbSurveille,2);
					}

			?>
			<tr>
				<td width="100%">
					<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="50%" rowspan="2">
								<table style="width:100%;">
									<tr><td height="4"></td></tr>
									<tr>
										<td valign="top">
											<div id="chart_Surveillance" style="width:100%;height:400px;"></div>
											<script>
												var chart = am4core.create("chart_Surveillance", am4charts.XYChart);

												// Add data
												chart.data = <?php echo json_encode($arraySurveillances); ?>;
												
												var title = chart.titles.create();
												title.text = <?php echo json_encode(utf8_encode($titreSurveillances)); ?>;
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
												categoryAxis.tooltip.disabled = true;
												categoryAxis.renderer.minHeight = 0;

												var valueAxis1 = chart.yAxes.push(new am4charts.ValueAxis());
												valueAxis1.renderer.minWidth = 0;
												valueAxis1.min= 0;
												valueAxis1.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of monitoring");}else{echo json_encode(utf8_encode("Nbr de surveillances"));} ?>;

												// Create series
												
												
												var series3 = chart.series.push(new am4charts.ColumnSeries());
												series3.columns.template.width = am4core.percent(90);
												series3.tooltipText = "{name}: {valueY.value}";
												series3.dataFields.categoryX = "Mois";
												series3.dataFields.valueY = "Realise";
												series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Completed");}else{echo json_encode(utf8_encode("Réalisées"));} ?>;
												series3.stacked = true;
												series3.stroke  = "#1ab559";
												series3.fill  = "#1ab559";
												
												var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY}";
												bullet3.locationY = 0.5;
												bullet3.label.fill = am4core.color("#ffffff");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 8;
												
												var series2 = chart.series.push(new am4charts.ColumnSeries());
												series2.columns.template.width = am4core.percent(90);
												series2.tooltipText = "{name}: {valueY.value}";
												series2.dataFields.categoryX = "Mois";
												series2.dataFields.valueY = "Brouillon";
												series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Draft");}else{echo json_encode("Brouillon");} ?>;
												series2.stacked = true;
												series2.stroke  = "#8ec9e5";
												series2.fill  = "#8ec9e5";
												
												var bullet2 = series2.bullets.push(new am4charts.LabelBullet());
												bullet2.label.text = "{valueY}";
												bullet2.locationY = 0.5;
												bullet2.label.fill = am4core.color("#ffffff");
												bullet2.interactionsEnabled = false;
												bullet2.fontSize = 8;
			
												var series5 = chart.series.push(new am4charts.ColumnSeries());
												series5.columns.template.width = am4core.percent(90);
												series5.tooltipText = "{name}: {valueY.value}";
												series5.dataFields.categoryX = "Mois";
												series5.dataFields.valueY = "Papier";
												series5.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("In progress paper");}else{echo json_encode("En cours papier");} ?>;
												series5.stacked = true;
												series5.stroke  = "#86edaf";
												series5.fill  = "#86edaf";
												
												var bullet5 = series5.bullets.push(new am4charts.LabelBullet());
												bullet5.label.text = "{valueY}";
												bullet5.locationY = 0.5;
												bullet5.label.fill = am4core.color("#ffffff");
												bullet5.interactionsEnabled = false;
												bullet5.fontSize = 8;
												
												var series1 = chart.series.push(new am4charts.ColumnSeries());
												series1.columns.template.width = am4core.percent(90);
												series1.tooltipText = "{name}: {valueY.value}";
												series1.dataFields.categoryX = "Mois";
												series1.dataFields.valueY = "Planifie";
												series1.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Planned");}else{echo json_encode(utf8_encode("Planifiées"));} ?>;
												series1.stacked = false;
												series1.stroke  = "#f5c115";
												series1.fill  = "#f5c115";
												
												var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
												bullet1.label.text = "{valueY}";
												bullet1.locationY = 0.5;
												bullet1.label.fill = am4core.color("#ffffff");
												bullet1.interactionsEnabled = false;
												bullet1.fontSize = 8;
												
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
										<td valign="top" align="center">
											<div id="chart_JaugeSurveillance" style="width:70%;height:200px;"></div>
											<script>
												// Create chart instance
												var chart = am4core.create("chart_JaugeSurveillance", am4charts.PieChart);
												
												var title = chart.titles.create();
												title.text = <?php echo json_encode(utf8_encode($titreJauge)); ?>;
												title.fontSize = 20;
												title.marginBottom = 0;

												// Add data
												chart.data = <?php echo json_encode($arrayJaugeSurveillances); ?>;
												
												var pieSeries = chart.series.push(new am4charts.PieSeries());
												pieSeries.dataFields.category = "Abscisse";
												pieSeries.dataFields.value = "Nombre";
												pieSeries.slices.template.stroke = am4core.color("#fff");
												pieSeries.slices.template.strokeWidth = 2;
												pieSeries.slices.template.strokeOpacity = 1;
												pieSeries.hiddenState.properties.opacity = 1;
												pieSeries.colors.list = [
												  am4core.color("#e9a1ab"),
												  am4core.color("#f5c115"),
												  am4core.color("#19b559"),
												  am4core.color("#8dc8e5"),
												  am4core.color("#85edae"),
												];
												pieSeries.labels.template.disabled = true;
												pieSeries.ticks.template.disabled = true;
												
												chart.endAngle = 360;
												chart.startAngle = 180;
												chart.innerRadius = am4core.percent(0);
												
												chart.legend = new am4charts.Legend();
												
												chart.exporting.menu = new am4core.ExportMenu();
											</script>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="100%">
								<table style="width:100%;">
									<tr><td height="4"></td></tr>
									<tr>
										<td valign="top">
											<div id="chart_NoteMoyenne" style="width:100%;height:400px;"></div>
											<script>
												var chart = am4core.create("chart_NoteMoyenne", am4charts.XYChart);

												// Add data
												chart.data = <?php echo json_encode($arrayNoteMoyenne); ?>;
												chart.numberFormatter.numberFormat = "#'%'";
												
												var title = chart.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Average grade");}else{echo json_encode(utf8_encode("Note moyenne"));} ?>;
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
												bullet3.fontSize = 8;
												
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
										<td align="right"><a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreExcel('NC')"><?php if($_SESSION['Langue']=="EN"){echo "Export NC";}else{echo "Export NC";}?></a></td>
									</tr>
									<tr>
										<td valign="top">
											<div id="chart_ParetoNC" style="width:100%;height:300px;"></div>
											<script>
												var chart = am4core.create("chart_ParetoNC", am4charts.XYChart);

												// Add data
												chart.data = <?php echo json_encode($arrayParetoNC); ?>;
												
												var title = chart.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Pareto of non-compliant questions in volume (Top 10)");}else{echo json_encode(utf8_encode("Pareto des questions non conformes en volume (Top 10)"));} ?>;
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
										<td align="right"><a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreExcel('NC2')"><?php if($_SESSION['Langue']=="EN"){echo "Export NC";}else{echo "Export NC";}?></a></td>
									</tr>
									<tr>
										<td valign="top">
											<div id="chart_ParetoNC2" style="width:100%;height:300px;"></div>
											<script>
												var chart = am4core.create("chart_ParetoNC2", am4charts.XYChart);

												// Add data
												chart.data = <?php echo json_encode($arrayParetoNC2); ?>;
												
												var title = chart.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Pareto of non-compliant questions in Ratio (Top 10)");}else{echo json_encode(utf8_encode("Pareto des questions non conformes en ratio (Top 10)"));} ?>;
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
						<tr>
							<td width="50%">
								<table style="width:100%;">
									<tr><td height="4"></td></tr>
									<tr>
										<td valign="top" align="center">
											<div id="chart_ActionNC" style="width:70%;height:200px;"></div>
											<script>
												// Create chart instance
												var chart = am4core.create("chart_ActionNC", am4charts.PieChart);
												
												var title = chart.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Typology of actions following NC");}else{echo json_encode(utf8_encode("Typologie des actions suite à NC"));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;

												// Add data
												chart.data = <?php echo json_encode($arrayActionNC); ?>;
												
												var pieSeries = chart.series.push(new am4charts.PieSeries());
												pieSeries.dataFields.category = "Abscisse";
												pieSeries.dataFields.value = "Nombre";
												pieSeries.slices.template.stroke = am4core.color("#fff");
												pieSeries.slices.template.strokeWidth = 2;
												pieSeries.slices.template.strokeOpacity = 1;
												pieSeries.hiddenState.properties.opacity = 1;
												pieSeries.colors.list = [
												  am4core.color("#f19109"),
												  am4core.color("#827f08"),
												  am4core.color("#19b559"),
												  am4core.color("#8dc8e5"),
												  am4core.color("#85edae"),
												];
												pieSeries.labels.template.disabled = true;
												pieSeries.ticks.template.disabled = true;
												
												chart.endAngle = 360;
												chart.startAngle = 180;
												chart.innerRadius = am4core.percent(0);
												
												chart.legend = new am4charts.Legend();
												
												chart.exporting.menu = new am4core.ExportMenu();
											</script>
										</td>
									</tr>
								</table>
							</td>
							<td>
								<table width="60%">
									<tr>
										<td width="20%"><img src="../../Images/Surveille.png"></td>
										<td width="20%" style="color:#2b88d9;font-size:30px;"><?php echo $SurveillanceParSurveille;?></td>
										<td width="60%" style="font-size:20px;">
											<?php 
											if($_SESSION['Langue']=="FR"){echo "Surveillance(s) par surveillés";}
											else{echo "Monitoring(s) by monitored";}
											?>
										</td>
									</tr>
									<tr>
										<td width="20%"><img src="../../Images/Surveillant.png"></td>
										<td width="20%" style="color:#19ae9f;font-size:30px;"><?php echo $SurveillanceParSurveillant;?></td>
										<td width="60%" style="font-size:20px;">
											<?php 
											if($_SESSION['Langue']=="FR"){echo "Surveillance(s) par surveillant";}
											else{echo "Monitoring(s) by supervisor";}
											?>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<table style="width:100%;">
									<tr><td height="4"></td></tr>
									<tr>
										<td valign="top">
											<div id="chart_Cumul" style="width:100%;height:400px;"></div>
											<script>
												var chart = am4core.create("chart_Cumul", am4charts.XYChart);

												// Add data
												chart.data = <?php echo json_encode($arrayCumul); ?>;
												
												var title = chart.titles.create();
												title.text = <?php echo json_encode(utf8_encode($titreCumul)); ?>;
												title.fontSize = 20;
												title.marginBottom = 0;
												
												// Create axes
												var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
												categoryAxis.dataFields.category = "Mois";
												categoryAxis.renderer.grid.template.location = 0;
												categoryAxis.renderer.minGridDistance = 0;
												categoryAxis.renderer.labels.template.horizontalCenter = "right";
												categoryAxis.renderer.labels.template.verticalCenter = "middle";
												categoryAxis.renderer.labels.template.rotation = 270;
												categoryAxis.tooltip.disabled = true;
												categoryAxis.renderer.minHeight = 0;

												var valueAxis1 = chart.yAxes.push(new am4charts.ValueAxis());
												valueAxis1.renderer.minWidth = 0;
												valueAxis1.min= 0;
												
												// Create series
												var series1 = chart.series.push(new am4charts.ColumnSeries());
												series1.columns.template.width = am4core.percent(90);
												series1.tooltipText = "{name}: {valueY.value}";
												series1.dataFields.categoryX = "Mois";
												series1.dataFields.valueY = "Surveillance";
												series1.name = <?php echo json_encode(utf8_encode($legendeCumulSurveillance)); ?>;
												series1.stacked = false;
												series1.stroke  = "#f5c115";
												series1.fill  = "#f5c115";
												
												var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
												bullet1.label.text = "{valueY}";
												bullet1.locationY = 0;
												bullet1.label.dy = -20;
												bullet1.label.fill = am4core.color("#000000");
												bullet1.interactionsEnabled = false;
												bullet1.fontSize = 8;
												
												var series2 = chart.series.push(new am4charts.ColumnSeries());
												series2.columns.template.width = am4core.percent(90);
												series2.tooltipText = "{name}: {valueY.value}";
												series2.dataFields.categoryX = "Mois";
												series2.dataFields.valueY = "Surveille";
												series2.name = <?php echo json_encode(utf8_encode($legendeCumulSurveille)); ?>;
												series2.stacked = false;
												series2.stroke  = "#41b8d5";
												series2.fill  = "#41b8d5";
												
												var bullet2 = series2.bullets.push(new am4charts.LabelBullet());
												bullet2.label.text = "{valueY}";
												bullet2.locationY = 0;
												bullet2.label.dy = -20;
												bullet2.label.fill = am4core.color("#000000");
												bullet2.interactionsEnabled = false;
												bullet2.fontSize = 8;
												
												var series3 = chart.series.push(new am4charts.ColumnSeries());
												series3.columns.template.width = am4core.percent(90);
												series3.tooltipText = "{name}: {valueY.value}";
												series3.dataFields.categoryX = "Mois";
												series3.dataFields.valueY = "Surveillant";
												series3.name = <?php echo json_encode(utf8_encode($legendeCumulSurveillant)); ?>;
												series3.stacked = false;
												series3.stroke  = "#64891a";
												series3.fill  = "#64891a";
												
												var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY}";
												bullet3.locationY = 0;
												bullet3.label.dy = -20;
												bullet3.label.fill = am4core.color("#000000");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 8;
												
												

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
							<td width="50%" align="center">
								
							</td>
						</tr>
						<tr>
							<td width="50%">
								<table style="width:100%;">
									<tr><td height="4"></td></tr>
									<tr>
										<td align="right"><a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreExcel('NA')"><?php if($_SESSION['Langue']=="EN"){echo "Export NA";}else{echo "Export NA";}?></a></td>
									</tr>
									<tr>
										<td valign="top">
											<div id="chart_ParetoNA" style="width:100%;height:300px;"></div>
											<script>
												var chart = am4core.create("chart_ParetoNA", am4charts.XYChart);

												// Add data
												chart.data = <?php echo json_encode($arrayParetoNA); ?>;
												
												var title = chart.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Pareto of non-applicable questions (Top 10)");}else{echo json_encode(utf8_encode("Pareto des questions non applicables (Top 10)"));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;

												// Create axes
												var yAxis = chart.yAxes.push(new am4charts.CategoryAxis());
												yAxis.dataFields.category = "Mois";
												yAxis.renderer.grid.template.location = 0;
												yAxis.renderer.labels.template.fontSize = 10;
												yAxis.renderer.minGridDistance = 10;
												yAxis.oversizedBehavior = "hide";
												
												var xAxis = chart.xAxes.push(new am4charts.ValueAxis());
												xAxis.min= 0;

												var series3 = chart.series.push(new am4charts.ColumnSeries());
												series3.dataFields.valueX = "Nombre";
												series3.dataFields.categoryY = "Mois";
												series3.tooltipText = "{categoryY}\n [bold]{valueX}[/]";
												series3.columns.template.width = am4core.percent(90);
												series3.stroke  = "#e6c060";
												series3.fill  = "#e6c060";

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
								<?php echo $tabParetoNA;?>
							</td>
						</tr>
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
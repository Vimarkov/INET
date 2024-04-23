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
<script src="../html2pdf.js-master/dist/html2pdf.bundle.min.js"></script>

<?php
$couleurPlanif = "#ffff00";
$couleurRetard = "#ff0000";
$couleurRealise = "#0070c0";
$couleurCloture = "#92d050";
$couleurRetard = "#e9a1ac";
$couleurEC = "#61b4ff";

$Questionnaires="";
$Themes="";
$nbQ=0;
$SeuilReussite2=0;
function valeurSinonNull($lavaleur){
	if($lavaleur==0){return null;}
	else{return $lavaleur;}
}

function anneeMois($annee, $numSemaine)
{
	//-- initialisation d'un objet DateTime au 4 janvier -------------------------
	//-- celui-ci se trouve obligatoirement dans la semaine N° 1 -----------------
	$date = new DateTime($annee . '-01-04');
	//-- si le 4 janvier n'est pas un lundi, -------------------------------------
	//-- le lundi précédent est le lundi de la semaine N° 1 ----------------------
	if ($date -> format('N') > 1)
	{
		$date -> modify('last monday');
	}
	
	//-- on ajoute le nombre de semaines -----------------------------------------
	//-- pour avoir le lundi de la semaine recherchée ----------------------------
	$date -> modify('+' . ($numSemaine - 1) . ' week');
	$dateDeb = $date -> format('d/m/Y');

	//-- on ajoute 6 jours pour avoir le dernier jour de la semaine recherchée ---
	$date -> modify('+4 day');

	$dateFin = $date -> format('Y_m');

	return $dateFin;
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
	
	$Presta="";
	$req="SELECT new_competences_prestation.Id
	FROM new_competences_prestation";
	$resultPlate=mysqli_query($bdd,$req);
	$nbPlate=mysqli_num_rows($resultPlate);
	if ($nbPlate > 0)
	{
		while($row=mysqli_fetch_array($resultPlate))
		{
			$selected="";
			if($_POST && !isset($_POST['btnReset2'])){
				if(isset($_POST['prestation'.$row['Id']])){
					if($Presta<>''){$Presta.=",";}
					$Presta.=$row['Id'];
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
	
	if($_POST){$anneeFin=$_POST['anneeFin'];}
	if($anneeFin==""){$anneeFin=date("Y");}
	
	$_SESSION['FiltreSODATDBOperation_ModeAffichage']=$_POST['ModeAffichage'];
	$_SESSION['FiltreSODATDBOperation_UER']=$Plateformes;
	$_SESSION['FiltreSODATDBOperation_Prestation']=$Presta;
	$_SESSION['FiltreSODATDBOperation_Questionnaire']=$Questionnaires;
	$_SESSION['FiltreSODATDBOperation_Theme']=$Themes;
	$_SESSION['FiltreSODATDBOperation_Annee']=$annee;
	$_SESSION['FiltreSODATDBOperation_Mois']=$_POST['mois'];
	$_SESSION['FiltreSODATDBOperation_AnneeFin']=$anneeFin;
	$_SESSION['FiltreSODATDBOperation_MoisFin']=$_POST['moisFin'];
	if(isset($_POST['dateDebut'])){
		$_SESSION['FiltreSODATDBOperation_DateDebut']=TrsfDate_($_POST['dateDebut']);
	}
	if(isset($_POST['dateFin'])){
		$_SESSION['FiltreSODATDBOperation_DateFin']=TrsfDate_($_POST['dateFin']);
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
				<td class="Libelle" style="background-color:#B2AE9F;"><?php if($LangueAffichage=="FR"){echo "Mode d'affichage";}else{echo "Display mode";}?> : </td>
			</tr>
			<tr>
				<td>
					<?php 
					$selected="";
					if($_SESSION['FiltreSODATDBOperation_ModeAffichage']=='Theme'){$selected="checked";}
					?>
					<input type="radio" name="ModeAffichage" value="Theme" <?php echo $selected; ?>/>
					<?php 
					if($LangueAffichage=="FR"){echo "Thème";}else{echo "Theme";}
					
					$selected="";
					if($_SESSION['FiltreSODATDBOperation_ModeAffichage']=='Prestation'){$selected="checked";}
					?>
					<input type="radio" name="ModeAffichage" value="Prestation" <?php echo $selected; ?>/>
					<?php 
					if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}
					
					$selected="";
					if($_SESSION['FiltreSODATDBOperation_ModeAffichage']=='UER'){$selected="checked";}
					?>
					<input type="radio" name="ModeAffichage" value="UER" <?php echo $selected; ?>/>
					<?php 
					if($LangueAffichage=="FR"){echo "UER";}else{echo "UER";}
					?>
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
									AND Id<>8
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
										if($_SESSION['FiltreSODATDBOperation_Theme']==-1){
											echo "checked";
										}
										else{
											$tabTheme=explode(",",$_SESSION['FiltreSODATDBOperation_Theme']);
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
					<input type="checkbox" name="selectAllUER" id="selectAllUER" onclick="SelectionnerTout('UER')" <?php if($_SESSION['FiltreSODATDBOperation_UER']==-1){echo "checked";}?> /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
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
								if($_SESSION['FiltreSODATDBOperation_UER']==-1){
									$selected="checked";
								}
								else{
									$tabUER=explode(",",$_SESSION['FiltreSODATDBOperation_UER']);
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
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Site";}else{echo "Prestation";} ?>&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td>
					<input type="checkbox" name="selectAllPrestation" id="selectAllPrestation" onclick="SelectionnerTout('Prestation')" <?php if($_SESSION['FiltreSODATDBOperation_Prestation']==-1){echo "checked";}?> /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
				</td>
			</tr>
			<tr>
				<td>
					<div id='Div_Prestation' style='height:150px;width:300px;overflow:auto;'>
					<table>
				<?php
					$laDatedeRef=date($_SESSION['FiltreSODATDBOperation_Annee']."-".$_SESSION['FiltreSODATDBOperation_Mois']."-1");
					$laDatedeRefFin=date($_SESSION['FiltreSODATDBOperation_AnneeFin']."-".$_SESSION['FiltreSODATDBOperation_MoisFin']."-1");
					$laDatedeRefFin=date("Y-m-d",strtotime($laDatedeRefFin." +1 month"));
					$laDatedeRefFin=date("Y-m-d",strtotime($laDatedeRefFin." -1 day"));

					$dateDebut=$laDatedeRef;
					$dateFin=$laDatedeRefFin;
					if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaire>0){
					$req="SELECT new_competences_prestation.Id,Id_Plateforme,Active,SousSurveillance,
						(SELECT new_competences_personne_poste_prestation.Id_Personne
						FROM new_competences_personne_poste_prestation
						WHERE new_competences_personne_poste_prestation.Id_Personne>0
						AND new_competences_personne_poste_prestation.Id_Poste=4
						AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
						AND new_competences_personne_poste_prestation.Backup=0 LIMIT 1) AS Id_RespProjet,
						new_competences_prestation.Libelle
						FROM new_competences_prestation
						WHERE 
						(
							(new_competences_prestation.SousSurveillance IN ('','Oui/Yes') AND new_competences_prestation.Active=0)
							OR
							(
								SELECT COUNT(Id)
								FROM soda_surveillance 
								WHERE Suppr=0 
								AND AutoSurveillance=0
								AND Etat='Clôturé'
								AND DateSurveillance>='".$dateDebut."'
								AND DateSurveillance<='".$dateFin."'
								AND Id_Prestation =new_competences_prestation.Id
							)>0
							OR
							(
								SELECT COUNT(Id)
								FROM soda_plannifmanuelle 
								WHERE Suppr=0 
								AND Annee =".$_SESSION['FiltreSODATDBOperation_Annee']."
								AND Id_Prestation =new_competences_prestation.Id
							)>0
						)
						AND new_competences_prestation.Id_Plateforme NOT IN (11,14) 
						ORDER BY new_competences_prestation.Libelle;";
					}
					else{
						$req="SELECT new_competences_prestation.Id,Id_Plateforme,Active,SousSurveillance,
						(SELECT new_competences_personne_poste_prestation.Id_Personne
						FROM new_competences_personne_poste_prestation
						WHERE new_competences_personne_poste_prestation.Id_Personne>0
						AND new_competences_personne_poste_prestation.Id_Poste=4
						AND new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
						AND new_competences_personne_poste_prestation.Backup=0 LIMIT 1) AS Id_RespProjet,
						new_competences_prestation.Libelle
						FROM new_competences_prestation
						WHERE 
						(
							(new_competences_prestation.SousSurveillance IN ('','Oui/Yes') AND new_competences_prestation.Active=0)
							OR
							(
								SELECT COUNT(Id)
								FROM soda_surveillance 
								WHERE Suppr=0 
								AND AutoSurveillance=0
								AND Etat='Clôturé'
								AND DateSurveillance>='".$dateDebut."'
								AND DateSurveillance<='".$dateFin."'
								AND Id_Prestation =new_competences_prestation.Id
							)>0
						)
						AND new_competences_prestation.Id_Plateforme>0 
						AND new_competences_prestation.Id_Plateforme NOT IN (11,14) 
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
						ORDER BY new_competences_prestation.Libelle;";
					}
					$resultPlate=mysqli_query($bdd,$req);
					$nbPlate=mysqli_num_rows($resultPlate);
					if ($nbPlate > 0)
					{
						$i=0;
						while($row=mysqli_fetch_array($resultPlate))
						{
							$selected="";
							if($_POST && !isset($_POST['btnReset2'])){
								if(isset($_POST['prestation'.$row['Id']])){$selected="checked";}
							}
							else{
								if($_SESSION['FiltreSODATDBOperation_Prestation']==-1){
									$selected="checked";
								}
								else{
									$tabPresta=explode(",",$_SESSION['FiltreSODATDBOperation_Prestation']);
									foreach($tabPresta as $presta){
										if ($presta == $row['Id']){
											$selected="checked";
										}
									}
								}
							}
							$active="";
							if($row['Active']<>0){$active=" [INACTIVE]";}
							$presta=substr($row['Libelle'],0,strpos($row['Libelle']," "));
							if($presta==""){$presta=$row['Libelle'];}
							if($presta<>""){
								$Id_RespProjet=0;
								if($row['Id_RespProjet']>0){$Id_RespProjet=$row['Id_RespProjet'];}
								echo "<tr><td><input class='checkPrestation' type='checkbox' ".$selected." value='".$row['Id']."' id='prestation".$row['Id']."' name='prestation".$row['Id']."'>".$presta.$active."</td></tr>";
								echo "<script>tabPresta[".$i."]= new Array(".$row['Id'].",".$row['Id_Plateforme'].",".$Id_RespProjet.");</script>";
								$i++;
							}
						}
					}
				?>
					</table>
					</div>
				</td>
			</tr>
			<tr>
				<td class="Libelle" style="background-color:#B2AE9F;">&nbsp;&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Project manager";}else{echo "Resp. projet";} ?>&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td>
					<input type="checkbox" name="selectAllRP" id="selectAllRP" onclick="SelectionnerTout('RP')" <?php if($_SESSION['FiltreSODATDBOperation_RespProjet']==-1){echo "checked";}?> /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
				</td>
			</tr>
			<tr>
				<td>
					<div id='Div_RP' style='height:200px;width:200px;overflow:auto;'>
						<table>
					<?php 
						if($nbAccess>0 || $nbSuperAdmin>0 || $nbGestionnaire>0){
							$req="SELECT DISTINCT new_competences_personne_poste_prestation.Id_Personne,
								(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
								FROM new_competences_personne_poste_prestation
								LEFT JOIN new_competences_prestation
								ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
								WHERE 
								(
									(new_competences_prestation.SousSurveillance IN ('','Oui/Yes') AND new_competences_prestation.Active=0)
									OR
									(
										SELECT COUNT(Id)
										FROM soda_surveillance 
										WHERE Suppr=0 
										AND AutoSurveillance=0
										AND Etat='Clôturé'
										AND DateSurveillance>='".$dateDebut."'
										AND DateSurveillance<='".$dateFin."'
										AND Id_Prestation =new_competences_prestation.Id
									)>0
								)
								AND new_competences_prestation.Active=0
								AND new_competences_prestation.Id_Plateforme>0 
								AND new_competences_personne_poste_prestation.Id_Personne>0
								AND new_competences_personne_poste_prestation.Id_Poste=4
								AND new_competences_personne_poste_prestation.Backup=0
								";
							$req.="ORDER BY (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne);";
						}	
						else{
							$req="SELECT DISTINCT new_competences_personne_poste_prestation.Id_Personne,
								(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
								FROM new_competences_personne_poste_prestation
								LEFT JOIN new_competences_prestation
								ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
								WHERE 
								(
									(new_competences_prestation.SousSurveillance IN ('','Oui/Yes') AND new_competences_prestation.Active=0)
									OR
									(
										SELECT COUNT(Id)
										FROM soda_surveillance 
										WHERE Suppr=0 
										AND AutoSurveillance=0
										AND Etat='Clôturé'
										AND DateSurveillance>='".$dateDebut."'
										AND DateSurveillance<='".$dateFin."'
										AND Id_Prestation =new_competences_prestation.Id
									)>0
								)
								AND new_competences_prestation.Active=0
								AND new_competences_prestation.Id_Plateforme>0 
								AND new_competences_personne_poste_prestation.Id_Personne>0
								AND new_competences_personne_poste_prestation.Id_Poste=4
								AND new_competences_personne_poste_prestation.Backup=0
								AND (
									(SELECT COUNT(Id) 
									FROM new_competences_personne_poste_prestation 
									WHERE Id_Personne=".$_SESSION['Id_Personne']."
									AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=new_competences_personne_poste_prestation.Id_Prestation)=new_competences_prestation.Id_Plateforme 
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
								";
							$req.="ORDER BY (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne);";
						}
						
						$resultRP=mysqli_query($bdd,$req);
						$nbRP=mysqli_num_rows($resultRP);
						
						$selected="";
						if($_POST && !isset($_POST['btnReset2'])){
							if(isset($_POST['RP0'])){$selected="checked";}
						}
						else{
							if($_SESSION['FiltreSODATDBOperation_RespProjet']==-1){
								$selected="checked";
							}
							else{
								$selected="";
							}
						}
						echo "<tr><td><input class='checkRP' type='checkbox' ".$selected." value='0' onclick=\"Selectionner('Prestation')\" id='RP0' name='RP0'>Non renseigné</td></tr>";
						
						if ($nbRP > 0)
						{
							while($row=mysqli_fetch_array($resultRP))
							{
								$selected="";
								if($_POST && !isset($_POST['btnReset2'])){
									if(isset($_POST['RP'.$row['Id_Personne']])){$selected="checked";}
								}
								else{
									if($_SESSION['FiltreSODATDBOperation_RespProjet']==-1){
										$selected="checked";
									}
									else{
										$selected="";
									}
								}
								echo "<tr><td><input class='checkRP' type='checkbox' ".$selected." value='".$row['Id_Personne']."' onclick=\"Selectionner('Prestation')\" id='RP".$row['Id_Personne']."' name='RP".$row['Id_Personne']."'>".stripslashes($row['Personne'])."</td></tr>";
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
			if($_SESSION['FiltreSODATDBOperation_Theme']<>"-1" && $_SESSION['FiltreSODATDBOperation_Prestation']<>"-1" && $_SESSION['FiltreSODATDBOperation_Theme']<>"" && $_SESSION['FiltreSODATDBOperation_Prestation']<>""){
					$laPlage="";
					$laDatedeRef=date($_SESSION['FiltreSODATDBOperation_Annee']."-".$_SESSION['FiltreSODATDBOperation_Mois']."-01");
					$laDatedeRefFin=date($_SESSION['FiltreSODATDBOperation_AnneeFin']."-".$_SESSION['FiltreSODATDBOperation_MoisFin']."-01");
					$laDatedeRefFin=date("Y-m-d",strtotime($laDatedeRefFin." +1 month"));
					$laDatedeRefFin=date("Y-m-d",strtotime($laDatedeRefFin." -1 day"));
					
					$dateDebut=$laDatedeRef;
					$dateFin=$laDatedeRefFin;
					
					$nbMois2=0;
					for($dateD=$dateDebut;$dateD<$dateFin;$dateD=date("Y-m-d",strtotime($dateD." +1 month"))){
						$nbMois2++;
					}

					if($dateDebut<=$dateFin){
					$i=0;
					$arrayLegende=array();
					$arrayVolumetrie=array();
					$arrayCumulVolumetrie=array();
					$arrayAdherencePlanning=array();
					$arrayNoteMoyenne=array();
					$arrayParetoNC=array();
					$arrayParetoNC2=array();
					$arrayParetoNC3=array();
					$arrayConformite=array();
					$arrayEcart=array();
					$plage="";
					$seuilReussite=80;
					
					$laDate=$dateDebut;
					
					$lundi=$laDate;
					if(date("N",strtotime($lundi." +0 day"))==1){$lundi=$lundi;}
					else{$lundi=date("Y-m-d",strtotime($lundi."last Monday"));}
					$anneeSemaine="";
					$lundiFin=$dateFin;
					if(date("N",strtotime($lundiFin." +0 day"))==1){$lundiFin=$lundiFin;}
					else{$lundiFin=date("Y-m-d",strtotime($lundiFin."last Monday"));}
					
					while($lundi<=$lundiFin){
						if(date("N",strtotime($lundi." +0 day"))==1){$lundi=$lundi;}
						else{$lundi=date("Y-m-d",strtotime($lundi."last Monday"));}
						$jeudi=date("Y-m-d",strtotime($lundi." +3 day"));

						if($jeudi>=$dateDebut && $jeudi<=$dateFin){
							$tabDate = explode('-', $jeudi);
							$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);

							if($anneeSemaine<>""){$anneeSemaine.=",";}
							$anneeSemaine.= "'".date('Y_W', $timestamp)."'";
						}
						$lundi=date("Y-m-d",strtotime($lundi." +7 day"));
					}
					
					//Liste des surveillances déjà réalisées ou planifiées par questionnaire 
					$req="SELECT Id_Questionnaire,Id_Prestation,COUNT(Id) AS NbQuestionnaire,YEAR(DateSurveillance) AS Annee
					FROM soda_surveillance 
					WHERE Suppr=0 
					AND AutoSurveillance=0
					AND Etat IN ('Clôturé')
					AND YEAR(DateSurveillance)=".date("Y",strtotime($dateDebut." +0 month"))."
					AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
					AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
					GROUP BY Id_Questionnaire,Id_Prestation,Annee
					";

					$resultSurveillancePla=mysqli_query($bdd,$req);
					$nbSurveillancePla=mysqli_num_rows($resultSurveillancePla);
					$tabSurveillances=array();
					$i=0;
					if($nbSurveillancePla>0){
						while($rowQuestion=mysqli_fetch_array($resultSurveillancePla)){
							$tabSurveillances[$i]=array($rowQuestion['Id_Questionnaire'],$rowQuestion['Id_Prestation'],$rowQuestion['NbQuestionnaire'],$rowQuestion['Annee']);
							$i++;
						}								
					}
					
					if($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="Theme"){
						if($LangueAffichage=="FR"){$plage="Thème";}else{$plage="Theme";}
						$req = "SELECT DISTINCT (SELECT (SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Intitule
							FROM soda_surveillance 
							WHERE Suppr=0 
							AND AutoSurveillance=0
							AND Etat='Clôturé'
							AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine.")
							AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
							AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
							ORDER BY (SELECT (SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) FROM soda_questionnaire WHERE Id=Id_Questionnaire)
							";
					}
					elseif($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="Prestation"){
						if($LangueAffichage=="FR"){$plage="Prestation";}else{$plage="Site";}
						$req = "SELECT DISTINCT (SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Intitule
							FROM soda_surveillance 
							WHERE Suppr=0 
							AND AutoSurveillance=0
							AND Etat='Clôturé'
							AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine.")
							AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
							AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
							ORDER BY (SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation)
							";
					}
					elseif($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="UER"){
						if($LangueAffichage=="FR"){$plage="UER";}else{$plage="UER";}
						$req = "SELECT DISTINCT (SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Intitule
							FROM soda_surveillance 
							WHERE Suppr=0 
							AND AutoSurveillance=0
							AND Etat='Clôturé'
							AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine.")
							AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
							AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
							ORDER BY (SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation)
							";
					}
					$result=mysqli_query($bdd,$req);
					$nbCloture=mysqli_num_rows($result);
					if($nbCloture>0){
						while($row=mysqli_fetch_array($result)){
							if($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="Theme"){$intitule=$row['Intitule'];}
							elseif($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="UER"){$intitule=$row['Intitule'];}
							else{
								$intitule=substr($row['Intitule'],0,strpos($row['Intitule']," "));
								if($intitule==""){$intitule=$row['Intitule'];}
							}
							$arrayLegende[$i][0]=$intitule;
							$arrayLegende[$i][1]=utf8_encode($intitule);
							$i++;
						}
					}
					
					$semaine2=date('Y')."S";
					if(date('W')<10){$semaine2.=date('W');}else{$semaine2.=date('W');}
					
					$anneeMoisEC=date('Y')."_";
					if(date('m')<10){$anneeMoisEC.="0".date('m');}else{$anneeMoisEC.=date('m');}
					
					$tabNbCumul=0;
					$i=0;
					for($nbMois=1;$nbMois<=$nbMois2;$nbMois++){
						$anneeEC=date("Y",strtotime($laDate." +0 month"));
						$moisEC=date("m",strtotime($laDate." +0 month"));
						
						$lundi=$laDate;
						if(date("N",strtotime($lundi." +0 day"))==1){$lundi=$lundi;}
						else{$lundi=date("Y-m-d",strtotime($lundi."last Monday"));}
						$semaineAnneeEC="";
						
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
								
								if($semaineAnneeEC<>""){$semaineAnneeEC.=",";}
								$semaineAnneeEC.= "'".date('Y_W', $timestamp)."'";
							}
							$lundi=$dimanche;
							$lundi=date("Y-m-d",strtotime($lundi." +1 day"));
						}
					
					
						
						if($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="Theme"){
							$req = "SELECT 
								(SELECT (SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Intitule,
								COUNT(Id) AS Nb
								FROM soda_surveillance 
								WHERE Suppr=0 
								AND AutoSurveillance=0
								AND Etat='Clôturé'
								AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$semaineAnneeEC.")
								AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
								AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
								GROUP BY (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire)
								";
								
						}
						elseif($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="Prestation"){
							$req = "SELECT 
								(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Intitule,
								COUNT(Id) AS Nb
								FROM soda_surveillance 
								WHERE Suppr=0 
								AND AutoSurveillance=0
								AND Etat='Clôturé'
								AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$semaineAnneeEC.")
								AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
								AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
								GROUP BY Id_Prestation
								";
						}
						elseif($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="UER"){
							$req = "SELECT 
								(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Intitule,
								COUNT(Id) AS Nb
								FROM soda_surveillance 
								WHERE Suppr=0 
								AND AutoSurveillance=0
								AND Etat='Clôturé'
								AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$semaineAnneeEC.")
								AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
								AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
								GROUP BY (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)
								";
						}

						$result=mysqli_query($bdd,$req);
						$nbCloture=mysqli_num_rows($result);
						$arrayVolumetrie[$i]['Mois']=utf8_encode($MoisLettre[$moisEC-1]." ".$anneeEC);
						if($nbCloture>0){
							while($row=mysqli_fetch_array($result)){
								if($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="Theme"){$intitule=$row['Intitule'];}
								elseif($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="UER"){$intitule=$row['Intitule'];}
								else{
									$intitule=substr($row['Intitule'],0,strpos($row['Intitule']," "));
									if($intitule==""){$intitule=$row['Intitule'];}
								}
								$arrayVolumetrie[$i][utf8_encode($intitule)]=utf8_encode($row['Nb']);
							}
						}
						
						if($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="Theme"){
							$req = "SELECT 
								(SELECT (SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Intitule,
								COUNT(Id) AS Nb
								FROM soda_surveillance 
								WHERE Suppr=0 
								AND AutoSurveillance=0
								AND Etat='Clôturé'
								AND (YEAR(DateSurveillance)=".$anneeEC." AND MONTH(DateSurveillance)>=1)
								AND (YEAR(DateSurveillance)=".$anneeEC." AND MONTH(DateSurveillance)<=".$moisEC.")
								AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
								AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
								GROUP BY (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire)
								";
						}
						elseif($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="Prestation"){
							$req = "SELECT 
								(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Intitule,
								COUNT(Id) AS Nb
								FROM soda_surveillance 
								WHERE Suppr=0 
								AND AutoSurveillance=0
								AND Etat='Clôturé'
								AND (YEAR(DateSurveillance)=".$anneeEC." AND MONTH(DateSurveillance)>=1)
								AND (YEAR(DateSurveillance)=".$anneeEC." AND MONTH(DateSurveillance)<=".$moisEC.")
								AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
								AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
								GROUP BY Id_Prestation
								";
						}
						elseif($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="UER"){
							$req = "SELECT 
								(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Intitule,
								COUNT(Id) AS Nb
								FROM soda_surveillance 
								WHERE Suppr=0 
								AND AutoSurveillance=0
								AND Etat='Clôturé'
								AND (YEAR(DateSurveillance)=".$anneeEC." AND MONTH(DateSurveillance)>=1)
								AND (YEAR(DateSurveillance)=".$anneeEC." AND MONTH(DateSurveillance)<=".$moisEC.")
								AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
								AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
								GROUP BY (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)
								";
						}
						$result=mysqli_query($bdd,$req);
						$nbCloture=mysqli_num_rows($result);
						$arrayCumulVolumetrie[$i]['Mois']=utf8_encode($MoisLettre[$moisEC-1]." ".$anneeEC);
						if($nbCloture>0){
							while($row=mysqli_fetch_array($result)){
								if($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="Theme"){$intitule=$row['Intitule'];}
								elseif($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="UER"){$intitule=$row['Intitule'];}
								else{
									$intitule=substr($row['Intitule'],0,strpos($row['Intitule']," "));
									if($intitule==""){$intitule=$row['Intitule'];}
								}
								$arrayCumulVolumetrie[$i][utf8_encode($intitule)]=utf8_encode($row['Nb']);
							}
						}
						
						
						$reqConforme = "SELECT ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100,1) AS NoteMoyenne,
								(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Seuil
								FROM soda_surveillance 
								WHERE Suppr=0 
								AND AutoSurveillance=0
								AND Etat='Clôturé'
								AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$semaineAnneeEC.")
								AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
								AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
								";
						$resultConforme=mysqli_query($bdd,$reqConforme);
						$nConforme=mysqli_num_rows($resultConforme);
						$reussite=0;
						$echec=0;
						$nbReussie=0;
						if($nConforme>0){
							while($rowConforme=mysqli_fetch_array($resultConforme)){
								if($rowConforme['NoteMoyenne']<>"" && $rowConforme['NoteMoyenne']<$rowConforme['Seuil']){$echec++;}
								else{$reussite++;}
							}
							$nbReussie=$reussite;
							$reussite=round(($reussite/$nConforme)*100,1);
							$echec=round(($echec/$nConforme)*100,1);
						}
						else{
							$reussite=null;
							$echec=null;
						}
						$arrayConformite[$i]=array("Mois" => utf8_encode($MoisLettre[$moisEC-1]." ".$anneeEC),"NbSurveillance" => utf8_encode($nConforme),"NbReussie" => utf8_encode($nbReussie),"Reussite" => utf8_encode($reussite),"Echec" => utf8_encode($echec));
						
						$laDate=date("Y-m-d",strtotime($laDate." +1 month"));
						$i++;
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
							AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine.")
							AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
							AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
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
								AND CONCAT(YEAR(tab.DateSurveillance),'_',IF(WEEK(tab.DateSurveillance,1)<10,CONCAT(0,WEEK(tab.DateSurveillance,1)),WEEK(tab.DateSurveillance,1))) IN (".$anneeSemaine.")
								AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=tab.Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
								AND tab.Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
								AND tab.Id IN 
									(SELECT Id_Surveillance 
									FROM soda_surveillance_question AS tab2 
									WHERE tab2.Id_Surveillance=tab.Id 
									AND tab2.Id_Question=soda_surveillance_question.Id_Question)
								) AS NbSurveillance,
								(
								COUNT(soda_surveillance_question.Id_Question)/
								(SELECT COUNT(tab.Id) 
								FROM soda_surveillance AS tab 
								WHERE tab.Suppr=0 
								AND tab.AutoSurveillance=0
								AND tab.Etat='Clôturé'
								AND CONCAT(YEAR(tab.DateSurveillance),'_',IF(WEEK(tab.DateSurveillance,1)<10,CONCAT(0,WEEK(tab.DateSurveillance,1)),WEEK(tab.DateSurveillance,1))) IN (".$anneeSemaine.")
								AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=tab.Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
								AND tab.Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
								AND tab.Id IN (SELECT Id_Surveillance FROM soda_surveillance_question AS tab2 WHERE tab2.Id_Surveillance=tab.Id AND tab2.Id_Question=soda_surveillance_question.Id_Question)
								)
								) AS Nb
							FROM soda_surveillance_question
							LEFT JOIN soda_surveillance 
							ON soda_surveillance_question.Id_Surveillance=soda_surveillance.Id
							WHERE soda_surveillance.Suppr=0 
							AND soda_surveillance.AutoSurveillance=0
							AND soda_surveillance.Etat='Clôturé'
							AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine.")
							AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
							AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
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
							$arrayParetoNC3[$i]=array("Mois" => utf8_encode($rowPareto2['Id_Question']),"Libelle" => utf8_encode($rowPareto2['Questionnaire']." <br> ".$rowPareto2['Question']),"NbQuestion" => valeurSinonNull($rowPareto2['NbSurveillance']),"NbNC" => valeurSinonNull($rowPareto2['NbQuestion']),"Erreur" => valeurSinonNull(round($rowPareto2['Nb']*100)));
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
					
					if($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="Theme"){
						$req = "SELECT Id, Libelle AS Intitule
							FROM soda_theme 
							WHERE Id IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
							ORDER BY Libelle
							";
					}
					elseif($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="Prestation"){
						$req = "SELECT Id, Libelle AS Intitule
							FROM new_competences_prestation 
							WHERE Id IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
							ORDER BY Libelle
							";
					}
					elseif($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="UER"){
						$req = "SELECT DISTINCT Id_Plateforme AS Id, (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Intitule
							FROM new_competences_prestation 
							WHERE Id IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
							ORDER BY (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme)
							";
					}
					$result=mysqli_query($bdd,$req);
					$nbCloture=mysqli_num_rows($result);

					$i=0;
					if($nbCloture>0){
						while($row2=mysqli_fetch_array($result)){
							$intitule2="";
							if($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="Theme"){
								$intitule2=str_replace("/","\n",$row2['Intitule']);
								//NbCloturé
								$req = "SELECT Id
										FROM soda_surveillance 
										WHERE Suppr=0 
										AND AutoSurveillance=0
										AND Etat='Clôturé'
										AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine.")
										AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
										AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
										";
										
								$reqRealiseSansPlanif = "SELECT Id
										FROM soda_surveillance 
										WHERE Suppr=0 
										AND AutoSurveillance=0
										AND Etat='Clôturé'
										AND (Id_PlannifManuelle=0 OR (SELECT COUNT(Id) FROM soda_plannifmanuelle WHERE Id=Id_PlannifManuelle AND Suppr=0 AND Volume>0)=0)
										AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine.")
										AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
										AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
										";
								
								$reqVol="SELECT Annee,Semaine,Id_Questionnaire,Id_Prestation,
									Volume,
									(SELECT COUNT(soda_surveillance.Id) 
									FROM soda_surveillance 
									WHERE soda_surveillance.Suppr=0 
									AND AutoSurveillance=0 
									AND soda_plannifmanuelle.Id_Questionnaire=soda_surveillance.Id_Questionnaire
									AND Etat='Clôturé' 
									AND soda_surveillance.Id_Prestation=soda_plannifmanuelle.Id_Prestation 
									AND Id_PlannifManuelle=soda_plannifmanuelle.Id) AS VolumeCloture
									FROM soda_plannifmanuelle 
									WHERE Suppr=0
									AND Annee>=".date("Y",strtotime($dateDebut." +0 month"))."
									AND Annee<=".date("Y", strtotime($dateFin." +0 month"))."
									AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
									AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].") 
									ORDER BY Annee,Semaine,Id_Prestation";

								$resultVolume=mysqli_query($bdd,$reqVol);
								$nbVolume=mysqli_num_rows($resultVolume);
								
								$reqNote = "SELECT ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
										FROM soda_surveillance 
										WHERE Suppr=0 
										AND AutoSurveillance=0
										AND Etat='Clôturé'
										AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine.")
										AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
										AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
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
										AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine.")
										AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
										AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
										";
										
								$resultConforme=mysqli_query($bdd,$reqConforme);
								$nConforme=mysqli_num_rows($resultConforme);
								$reussite=0;
								$echec=0;
								$nbReussie=0;
								if($nConforme>0){
									while($rowConforme=mysqli_fetch_array($resultConforme)){
										if($rowConforme['NoteMoyenne']<>"" && $rowConforme['NoteMoyenne']<$rowConforme['Seuil']){$echec++;}
										else{$reussite++;$nbReussie++;}
									}
									$reussite=round(($reussite/$nConforme)*100,1);
									$echec=round(($echec/$nConforme)*100,1);
								}
								else{
									$reussite=null;
									$nbReussie=null;
									$echec=null;
								}
							}
							elseif($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="UER"){
								$intitule2=$row2['Intitule'];
								//NbCloturé
								$req = "SELECT Id
										FROM soda_surveillance 
										WHERE Suppr=0 
										AND AutoSurveillance=0
										AND Etat='Clôturé'
										AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine.")
										AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
										AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
										AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$row2['Id']."
										";
										
								$reqRealiseSansPlanif = "SELECT Id
										FROM soda_surveillance 
										WHERE Suppr=0 
										AND AutoSurveillance=0
										AND Etat='Clôturé'
										AND (Id_PlannifManuelle=0 OR (SELECT COUNT(Id) FROM soda_plannifmanuelle WHERE Id=Id_PlannifManuelle AND Suppr=0 AND Volume>0)=0)
										AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine.")
										AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
										AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
										AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$row2['Id']."
										";
										
								$reqVol="SELECT Annee,Semaine,Id_Questionnaire,Id_Prestation,
									Volume,
									(SELECT COUNT(soda_surveillance.Id) 
									FROM soda_surveillance 
									WHERE soda_surveillance.Suppr=0 
									AND AutoSurveillance=0 
									AND soda_plannifmanuelle.Id_Questionnaire=soda_surveillance.Id_Questionnaire
									AND Etat='Clôturé' 
									AND soda_surveillance.Id_Prestation=soda_plannifmanuelle.Id_Prestation 
									AND Id_PlannifManuelle=soda_plannifmanuelle.Id) AS VolumeCloture
									FROM soda_plannifmanuelle 
									WHERE Suppr=0
									AND Annee>=".date("Y",strtotime($dateDebut." +0 month"))."
									AND Annee<=".date("Y", strtotime($dateFin." +0 month"))."
									AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
									AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
									AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$row2['Id']." 
									ORDER BY Annee,Semaine,Id_Prestation";
								$resultVolume=mysqli_query($bdd,$reqVol);
								$nbVolume=mysqli_num_rows($resultVolume);
								
								$reqNote = "SELECT ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
										FROM soda_surveillance 
										WHERE Suppr=0 
										AND AutoSurveillance=0
										AND Etat='Clôturé'
										AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine.")
										AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
										AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
										AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$row2['Id']."
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
										AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine.")
										AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
										AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
										AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$row2['Id']."
										";
								$resultConforme=mysqli_query($bdd,$reqConforme);
								$nConforme=mysqli_num_rows($resultConforme);
								$reussite=0;
								$echec=0;
								$nbReussie=0;
								if($nConforme>0){
									while($rowConforme=mysqli_fetch_array($resultConforme)){
										if($rowConforme['NoteMoyenne']<>"" && $rowConforme['NoteMoyenne']<$rowConforme['Seuil']){$echec++;}
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
							}
							else{
								$intitule2=substr($row2['Intitule'],0,strpos($row2['Intitule']," "));
								//NbCloturé
								$req = "SELECT Id
										FROM soda_surveillance 
										WHERE Suppr=0 
										AND AutoSurveillance=0
										AND Etat='Clôturé'
										AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine.")
										AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
										AND Id_Prestation = ".$row2['Id']."
										";
										
								//NbCloturé
								$reqRealiseSansPlanif = "SELECT Id
										FROM soda_surveillance 
										WHERE Suppr=0 
										AND AutoSurveillance=0
										AND Etat='Clôturé'
										AND (Id_PlannifManuelle=0 OR (SELECT COUNT(Id) FROM soda_plannifmanuelle WHERE Id=Id_PlannifManuelle AND Suppr=0 AND Volume>0)=0)
										AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine.")
										AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
										AND Id_Prestation = ".$row2['Id']."
										";

								$reqVol="SELECT Annee,Semaine,Id_Questionnaire,Id_Prestation,
									Volume,
									(SELECT COUNT(soda_surveillance.Id) 
									FROM soda_surveillance 
									WHERE soda_surveillance.Suppr=0 
									AND AutoSurveillance=0 
									AND soda_plannifmanuelle.Id_Questionnaire=soda_surveillance.Id_Questionnaire
									AND Etat='Clôturé' 
									AND soda_surveillance.Id_Prestation=soda_plannifmanuelle.Id_Prestation 
									AND Id_PlannifManuelle=soda_plannifmanuelle.Id 
									) AS VolumeCloture
									FROM soda_plannifmanuelle 
									WHERE Suppr=0
									AND Annee>=".date("Y",strtotime($dateDebut." +0 month"))."
									AND Annee<=".date("Y", strtotime($dateFin." +0 month"))."
									AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
									AND Id_Prestation = ".$row2['Id']." 
									ORDER BY Annee,Semaine,Id_Prestation ";
								$resultVolume=mysqli_query($bdd,$reqVol);
								$nbVolume=mysqli_num_rows($resultVolume);

								$reqNote = "SELECT ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
										FROM soda_surveillance 
										WHERE Suppr=0 
										AND AutoSurveillance=0
										AND Etat='Clôturé'
										AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine.")
										AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
										AND Id_Prestation = ".$row2['Id']."
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
										AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine.")
										AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
										AND Id_Prestation = ".$row2['Id']."
										";
								$resultConforme=mysqli_query($bdd,$reqConforme);
								$nConforme=mysqli_num_rows($resultConforme);
								$reussite=0;
								$echec=0;
								$nbReussie=0;
								if($nConforme>0){
									while($rowConforme=mysqli_fetch_array($resultConforme)){
										if($rowConforme['NoteMoyenne']<>"" &&  $rowConforme['NoteMoyenne']<$rowConforme['Seuil']){$echec++;}
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
							}
							$result2=mysqli_query($bdd,$req);
							$nbCloture=mysqli_num_rows($result2);
							
							$resultRealiseSansPlanif=mysqli_query($bdd,$reqRealiseSansPlanif);
							$nbRealiseSansPlanif=mysqli_num_rows($resultRealiseSansPlanif);
							
							$nbRetard=0;
							$nbPlanif=0;
							$nbPlanifTotal=0;
							$nbVolumeCloture=0;
							$volumeRealiseSurPlanifie=0;
							
							
							if ($nbVolume>0){
								while($rowSurveillance=mysqli_fetch_array($resultVolume)) {
									$lasemaine=$rowSurveillance['Annee']."S";
									if($rowSurveillance['Semaine']<10){$lasemaine.="0".$rowSurveillance['Semaine'];}else{$lasemaine.=$rowSurveillance['Semaine'];}
									
									$lasemainev2=$rowSurveillance['Annee']."_";
									if($rowSurveillance['Semaine']<10){$lasemainev2.="0".$rowSurveillance['Semaine'];}else{$lasemainev2.=$rowSurveillance['Semaine'];}
												
									$leVolume=$rowSurveillance['Volume'];
									for($i2=0;$i2<sizeof($tabSurveillances);$i2++){
										if($tabSurveillances[$i2][0]==$rowSurveillance['Id_Questionnaire'] && $tabSurveillances[$i2][1]==$rowSurveillance['Id_Prestation'] && $tabSurveillances[$i2][2]>0 && $tabSurveillances[$i2][3]==$rowSurveillance['Annee']){
											$volume=$leVolume;
											if($leVolume>=$tabSurveillances[$i2][2]){
												$leVolume=$leVolume-$tabSurveillances[$i2][2];
												$volumeRealiseSurPlanifie+=$tabSurveillances[$i2][2];
											}
											else{
												$leVolume=0;
												$volumeRealiseSurPlanifie+=$leVolume;
											}
											$tabSurveillances[$i2][2]=$tabSurveillances[$i2][2]-$volume;
											
										}
									}
									if($semaine2>=$lasemaine && strpos($anneeSemaine, $lasemainev2) !== false){
										$nbRetard+=$leVolume;
										$nbPlanifTotal+=$rowSurveillance['Volume'];
										$nbVolumeCloture+=$rowSurveillance['VolumeCloture'];
									}
									else{
										$nbPlanif+=$leVolume;
									}
								}
							}

							$nbRealise=$nbCloture;
							$total=$nbPlanifTotal;
							
							$pourcentageRealise=null;
							if(($total+$nbRealiseSansPlanif)>0){
								$pourcentageRealise=round(($nbCloture/($total+$nbRealiseSansPlanif))*100,0);
							}

							$arrayAdherencePlanning[$i]=array("Mois" => utf8_encode($intitule2),"Volume" => valeurSinonNull($total),"NbRealise" => valeurSinonNull($nbVolumeCloture),"NbCloture" => valeurSinonNull($pourcentageRealise),"NbRealiseSansPlanif" => valeurSinonNull($nbRealiseSansPlanif),"NbRealiseTotal" => valeurSinonNull($nbCloture));
							$arrayNoteMoyenne[$i]=array("Mois" => utf8_encode($intitule2),"NoteMoyenne" => valeurSinonNull($noteMoyenne),"Seuil" => valeurSinonNull($seuilReussite));
							$arrayEcart[$i]=array("Mois" => utf8_encode($intitule2),"NbSurveillance" => utf8_encode($nConforme),"NbReussie" => utf8_encode($nbReussie),"Reussite" => utf8_encode($reussite),"Echec" => utf8_encode($echec));
							$i++;
						}

					}
			?>	
			<tr>
				<td width="100%">
					<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="100%" colspan="2">
								<table style="width:100%;">
									<tr><td height="4"></td></tr>
									<tr>
										<td valign="top">
											<div id="chart_Volumetrie" style="width:100%;height:500px;"></div>
											<script>
												var chart = am4core.create("chart_Volumetrie", am4charts.XYChart);

												// Add data
												chart.data = <?php echo json_encode($arrayVolumetrie); ?>;
												
												var title = chart.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Volume of monitoring / month");}else{echo json_encode(utf8_encode("Volume des surveillances / mois"));} ?>;
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
												
												<?php foreach($arrayLegende as $legende){
												?>
													var series3 = chart.series.push(new am4charts.ColumnSeries());
													series3.columns.template.width = am4core.percent(90);
													series3.tooltipText = "{name}: {valueY.value}";
													
													series3.dataFields.categoryX = "Mois";
													series3.dataFields.valueY = "<?php echo $legende[0];?>";
													series3.name = <?php echo json_encode($legende[1]);?>;
													series3.stacked = true;
													series3.clustered = false;
													
													var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
													bullet3.label.text = "{valueY}";
													bullet3.locationY = 0.5;
													bullet3.label.fill = am4core.color("#ffffff");
													bullet3.interactionsEnabled = false;
													bullet3.fontSize = 10;
												<?php
												}?>
												

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
							<td width="100%" colspan="2">
								<table style="width:100%;">
									<tr><td height="4"></td></tr>
									<tr>
										<td valign="top">
											<div id="chart_CumulVolumetrie" style="width:100%;height:500px;"></div>
											<script>
												var chart1 = am4core.create("chart_CumulVolumetrie", am4charts.XYChart);

												// Add data
												chart1.data = <?php echo json_encode($arrayCumulVolumetrie); ?>;
												
												var title = chart1.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Volumetric accumulation of monitoring");}else{echo json_encode(utf8_encode("Cumul volumétrique des surveillances"));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;
												
												// Create axes
												var categoryAxis = chart1.xAxes.push(new am4charts.CategoryAxis());
												categoryAxis.dataFields.category = "Mois";
												categoryAxis.renderer.grid.template.location = 0;
												categoryAxis.renderer.minGridDistance = 15;
												categoryAxis.renderer.labels.template.horizontalCenter = "right";
												categoryAxis.renderer.labels.template.verticalCenter = "middle";
												categoryAxis.renderer.labels.template.rotation = 270;
												categoryAxis.tooltip.disabled = true;
												categoryAxis.renderer.minHeight = 0;

												var valueAxis1 = chart1.yAxes.push(new am4charts.ValueAxis());
												valueAxis1.renderer.minWidth = 0;
												valueAxis1.min= 0;
												
												<?php foreach($arrayLegende as $legende){
												?>
													var series3 = chart1.series.push(new am4charts.ColumnSeries());
													series3.columns.template.width = am4core.percent(90);
													series3.tooltipText = "{name}: {valueY.value}";
													series3.dataFields.categoryX = "Mois";
													series3.dataFields.valueY = "<?php echo $legende[0];?>";
													series3.name = <?php echo json_encode($legende[1]);?>;
													series3.stacked = true;
													//series3.stroke  = "#1ab559";
													//series3.fill  = "#1ab559";
													
													var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
													bullet3.label.text = "{valueY}";
													bullet3.locationY = 0.5;
													bullet3.label.fill = am4core.color("#ffffff");
													bullet3.interactionsEnabled = false;
													bullet3.fontSize = 10;
												<?php
												}?>
												

												// Cursor
												chart1.cursor = new am4charts.XYCursor();
												chart1.cursor.behavior = "panX";
												chart1.cursor.lineX.opacity = 0;
												chart1.cursor.lineY.opacity = 0;
												
												/* Add legend */
												chart1.legend = new am4charts.Legend();
												chart1.scrollbarX = new am4core.Scrollbar();
												
												chart1.exporting.menu = new am4core.ExportMenu();
											</script>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<!--
						<tr>
							<td align="right"><a style="text-decoration:none;" class="Bouton" href="javascript:GenererPlanning()"><?php if($_SESSION['Langue']=="EN"){echo "Export";}else{echo "Export";}?></a></td>
						</tr>
						-->
						<tr>
							<td colspan="2">
								<table style="width:100%;">
									<tr>
										<td>
											<div id="divplanning" style="overflow:auto;height:400px;width:100%;">
											&nbsp;
											<table bgcolor="#cccccc" width='100%'>
												<?php
												$anneeATraiter = date("Y");
												$dateDebut=date("Y-m-d",strtotime(date($_SESSION['FiltreSODATDBOperation_Annee']."-".$_SESSION['FiltreSODATDBOperation_Mois']."-1")." -1 month"));
												$dateDeFin=date("Y-m-d",strtotime(date($_SESSION['FiltreSODATDBOperation_Annee']."-".$_SESSION['FiltreSODATDBOperation_Mois']."-1")." +3 month"));
												$EnTeteMois = "";
												$EnTeteJourSemaine = "";
												$EnTeteJour = "";
												
												$tmpDate = date("Y/m/d",strtotime($dateDebut." +0 month"));
												$dateFin = date("Y/m/d", strtotime($dateDeFin." +0 month"));
												$tabDate = explode('/', $tmpDate);
												$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
												$tmpMois = date('n', $timestamp) . ' ' . date('Y', $timestamp);
												$cptMois = 0;
												$cptSemaine = 0;
												$cptJour = 0;
												$cptTotal = 0;
												if($_SESSION['Langue']=="FR")
												{
													$MoisLettre = array("Janv.", "Fev.", "Mars", "Avr.", "Mai", "Juin", "Juil.", "Aout", "Sept.", "Oct.", "Nov.", "Dec.");
												}
												else
												{
													$MoisLettre = array("Jan.", "Feb.", "Mar.", "Apr.", "May", "June", "July", "Aug.t", "Sept.", "Oct.", "Nov.", "Dec.");
												}
												// GESTION DES ENTETES DU TABLEAU (MOIS, SEMAINE ET JOUR)
												while ($tmpDate < $dateFin) 
												{
													$tabDate = explode('/', $tmpDate);
													$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
													$mois = $tabDate[1];
													$EnTeteMois .= "<th class='EnTeteMois' style='top:0;position: sticky;' width='30px' colspan='2'>".$MoisLettre[$mois-1]." ".$tabDate[0]."</th> ";
													//Mois suivant
													$tmpDate=date("Y/m/d",strtotime($tmpDate." +1 month"));
													
												}
												?>
												<thead align="center">
													<th  class="EnTeteMois" width="400px" align="center" valign="center" style="top:0;position: sticky;" colspan="17">Planning</th>
												</thead>
												<thead align="center">
													<th class="Libelle" style="top:0;position: sticky;" width="200px" colspan='3' align="center" valign="center">
														<table align="center">
															<tr>
																<td  class="Libelle" style="background-color:<?php echo $couleurPlanif; ?>;top:0;position: sticky;" width="200px" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Planifié";}else{echo "Planed";}?></th>
																<td  class="Libelle" style="background-color:<?php echo $couleurCloture; ?>;top:0;position: sticky;" width="200px" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Clôturé";}else{echo "Closed";}?></th>
															</tr>
															<tr>
																<td  class="Libelle" style="background-color:<?php echo $couleurEC; ?>;top:0;position: sticky;" width="200px" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Brouillon/En cours - papier";}else{echo "Draft/In progress - paper";}?></th>
																<td  class="Libelle" style="background-color:<?php echo $couleurRetard; ?>;top:0;position: sticky;" width="200px" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Retard";}else{echo "Delay";}?></th>
															</tr>
														</table>
													</th>
													<th  class="EnTeteMois" width="400px" align="center" valign="center" style="top:0;position: sticky;" colspan="13"><?php echo $anneeATraiter; ?></th>
												</thead>
												<thead align="center">
													<th class="EnTeteSemaine" style="font-size:12px;width:200px;top:0;position: sticky;" ><?php if($_SESSION['Langue']=="FR"){echo "Thème";}else{echo "Theme";}?></th>
													<th colspan="2" class="EnTeteSemaine" style="font-size:12px;width:400px;top:0;position: sticky;" >Questionnaire</th>
													<?php echo $EnTeteMois ;?>
													<th  class="EnTeteMois" width="30px" style="top:0;position: sticky;" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Volume planifié";}else{echo "Planned volume";}?></th>
													<th  class="EnTeteMois" width="30px" style="top:0;position: sticky;" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Objectif volume";}else{echo "Volume goal";}?></th>
													<th  class="EnTeteMois" width="30px" style="top:0;position: sticky;" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Delta volume";}else{echo "Delta-volume";}?></th>
													<th  class="EnTeteMois" width="30px" style="top:0;position: sticky;" align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Volume réalisé";}else{echo "Volume achieved";}?></th>
													<th  class="EnTeteMois" width="30px"style="top:0;position: sticky;"  align="center" valign="center"><?php if($_SESSION['Langue']=="FR"){echo "Delta réalisé-planif";}else{echo "Actual-planned delta";}?></th>
												</thead>
												<tbody>
													<?php
													// FIN GESTION DES ENTETES DU TABLEAU
													
													//DEBUT CORPS DU TABLEAU
													$tmpDate = date("Y/m/d",strtotime($dateDebut." +0 month"));
													$dateFin = date("Y/m/d", strtotime($dateDeFin." +0 month"));
													$dateFin = date("Y/m/d", strtotime($dateFin." -1 day"));
													
													$ldateFin  = date("Y-m-d", strtotime($dateDeFin." +0 month"));
													
													//Liste des questionnaires
													$req = "SELECT Id,Libelle,Id_Theme,Specifique,
															(SELECT Libelle FROM soda_theme WHERE Id=Id_Theme) AS Theme 
															FROM soda_questionnaire
															WHERE Suppr=0 
															AND Actif=0 
															AND Id_Theme IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
															ORDER BY Theme,Specifique,Libelle";
													$resultQuestionnaire=mysqli_query($bdd,$req);
													$nbQuestionnaire=mysqli_num_rows($resultQuestionnaire);

													$Id_Theme=0;
													$Id_Theme2=0;
													if ($nbQuestionnaire > 0){
														$couleurQuestionnaire = "bgcolor=#548FFB";
														while($row=mysqli_fetch_array($resultQuestionnaire)){
															$Id_Questionnaire = $row['Id'];

															$ligne1 = "<tr>";
															$ligne2 = "<tr>";
															
															//Nb questionnaires
															$req = "SELECT Id 
																	FROM soda_questionnaire
																	WHERE Suppr=0 
																	AND Actif=0 
																	AND Id_Theme=".$row['Id_Theme']." ";
															$resultQuestionnaire2=mysqli_query($bdd,$req);
															$nbQuestionnaire2=mysqli_num_rows($resultQuestionnaire2);
															$rowspan=$nbQuestionnaire2*2;
															
															if($Id_Theme2<>$row['Id_Theme']){
																$ligne1 .= "<td rowspan='".$rowspan."' ".$couleurQuestionnaire."  width='200px' height='30px'>".$row['Theme']."</td>";
																$Id_Theme2=$row['Id_Theme'];
															}
															$ligne1 .= "<td rowspan='2' width='450px' colspan='2' height='30px' ".$couleurQuestionnaire.">";
															if($row['Specifique']==0){$ligne1 .= "[Géné] ";}
															else{$ligne1 .= "[Spec] ";}
															$ligne1 .= $row['Libelle']."</td>";
															
															if ($couleurQuestionnaire == "bgcolor=#347afa"){
																
																$couleurQuestionnaire = "bgcolor=#548FFB";
															}
															else{
																$couleurQuestionnaire = "bgcolor=#347afa";
															}
															
															$tmpDate = date("Y/01/01",strtotime($dateDebut." +0 month"));
															$tmpDateFin = date("Y/01/01",strtotime($dateDebut." +0 month"));
															$width=30;
															$semaine2=date('Y')."S";
															if(date('W')<10){$semaine2.=date('W');}else{$semaine2.=date('W');}
															
															while ($tmpDateFin < $dateFin) {
																$tabDate = explode('/', $tmpDateFin);
																$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
																$annee = date('Y', $timestamp);
																$mois = date('m', $timestamp);
																$semaine = date('W', $timestamp);
																$dateAffichage = date("d/m/Y",$timestamp);
																$dateAffichage2 = date("Y-m-d",$timestamp);
																$class="semaine";
																
																//CALCUL SEMAINES DU MOIS
																$lundiV2=$tmpDateFin;
																if(date("N",strtotime($lundiV2." +0 day"))==1){$lundiV2=date("Y-m-d",strtotime($lundiV2));}
																else{$lundiV2=date("Y-m-d",strtotime($lundiV2."last Monday"));}
																$semaineAnneeEC2="";
																
																$lundiFinV2=date("Y-m-d",strtotime($tmpDateFin." +1 month"));
																if(date("N",strtotime($lundiFinV2." +0 day"))==1){$lundiFinV2=date("Y-m-d",strtotime($lundiFinV2));}
																else{$lundiFinV2=date("Y-m-d",strtotime($lundiFinV2."last Monday"));}
																
																while($lundiV2<=$lundiFinV2){
																	if(date("N",strtotime($lundiV2." +0 day"))==1){$lundiV2=$lundiV2;}
																	else{$lundiV2=date("Y-m-d",strtotime($lundiV2."last Monday"));}
																	$jeudiV2=date("Y-m-d",strtotime($lundiV2." +3 day"));
																	$dimancheV2=date("Y-m-d",strtotime($lundiV2." +6 day"));
																	
																	if(date("Y-m",strtotime($jeudiV2))==$annee."-".$mois){
																		$tabDate2 = explode('-', $jeudiV2);
																		$timestamp2 = mktime(0, 0, 0, $tabDate2[1], $tabDate2[2], $tabDate2[0]);
																		
																		if($semaineAnneeEC2<>""){$semaineAnneeEC2.=",";}
																		$semaineAnneeEC2.= "'".date('Y_W', $timestamp2)."'";
																	}
																	$lundiV2=$dimancheV2;
																	$lundiV2=date("Y-m-d",strtotime($lundiV2." +1 day"));
																}
																//FIN CALCUL SEMAINES DU MOIS

																//Recherche si planning pour ce jour-ci
																$nbPlanif=0;
																$nbCloture=0;
																$nbRetard=0;
																$nbEC=0;
																$nbCellule = 0;
																$infoRetard="";
																$infoPlanif="";
																$infoCloture="";
																
																$tmpDate2=$tmpDate;
																$dateFin2=date("Y/m/01",strtotime($tmpDateFin." +1 month"));
																$dateFin2=date("Y/m/d",strtotime($dateFin2." -1 day"));
																
																if(date("N",strtotime($tmpDate2." +0 day"))==1){
																	$tmpDate2=$tmpDate2;
																}
																else{
																	$tmpDate2=date("Y/m/d",strtotime($tmpDate2."last Monday"));
																}
																
																//Si dernier jour du mois = L,M,M alors revenir au dimanche précédent
																if(date("N",strtotime($dateFin2." +0 day"))==1 || date("N",strtotime($dateFin2." +0 day"))==2 || date("N",strtotime($dateFin2." +0 day"))==3){
																	$dateFin2=date("Y/m/d",strtotime($dateFin2."last Sunday"));
																}
				
																//Boucle tant qu'on est sur le même mois
																while ($tmpDate2 <= $dateFin2) {
																	
																	$lundi=$tmpDate2;
																	if(date("N",strtotime($lundi." +0 day"))==1){
																		$lundi=$lundi;
																	}
																	else{
																		$lundi=date("Y/m/d",strtotime($lundi."last Monday"));
																	}
																	$dimanche=date("Y/m/d",strtotime($lundi." +6 day"));
																	if($dateFin<$dimanche){$dimanche=$dateFin;}
																	
																	
																	//Liste des surveillances planifiées
																	$req = "SELECT Id_Questionnaire,Id_Prestation,Annee,Semaine,
																			(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
																			Volume-(SELECT COUNT(soda_surveillance.Id) FROM soda_surveillance WHERE soda_surveillance.Suppr=0 AND Etat IN ('Clôturé','En cours - papier','Brouillon') AND AutoSurveillance=0 AND Id_PlannifManuelle=soda_plannifmanuelle.Id ) AS Volume
																			FROM soda_plannifmanuelle 
																			WHERE Suppr=0
																			AND Annee=".date("Y",strtotime($lundi." +0 day"))." 
																			AND Semaine=".date("W",strtotime($lundi." +0 day"))."
																			AND Id_Questionnaire=".$Id_Questionnaire."
																			AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].") 
																			ORDER BY Prestation";

																	$resultSurveillance=mysqli_query($bdd,$req);
																	$nbSurveillance=mysqli_num_rows($resultSurveillance);
																	if ($nbSurveillance>0){
																		while($rowSurveillance=mysqli_fetch_array($resultSurveillance)) {
																			$lasemaine=$rowSurveillance['Annee']."S";
																			if($rowSurveillance['Semaine']<10){$lasemaine.="0".$rowSurveillance['Semaine'];}else{$lasemaine.=$rowSurveillance['Semaine'];}
																			
																			$volume=$rowSurveillance['Volume'];

																			if($semaine2>$lasemaine){
																				$nbRetard+=$volume;
																				
																				if($volume>0){
																					$presta=substr($rowSurveillance['Prestation'],0,strpos($rowSurveillance['Prestation']," "));
																					if($presta==""){$presta=$rowSurveillance['Prestation'];}
																					$infoRetard.="<B>".$presta."</B> : ".$rowSurveillance['Volume']." <br>";
																				}
																			}
																			else{
																				$nbPlanif+=$volume;
																				if($volume>0){
																					$presta=substr($rowSurveillance['Prestation'],0,strpos($rowSurveillance['Prestation']," "));
																					if($presta==""){$presta=$rowSurveillance['Prestation'];}
																					$infoPlanif.="<B>".$presta."</B> : ".$rowSurveillance['Volume']." <br>";
																				}
																			}
																			
																		}
																		
																	}
																	
																	
																	//Jour suivant
																	$tmpDate2=$dimanche;
																	$tmpDate2=date("Y/m/d",strtotime($tmpDate2." +1 day"));
						
																}
																
																
																if($tmpDateFin>=date("Y/m/d",strtotime($dateDebut." +0 month"))){
																
																	if($nbRetard>0){$nbCellule++;}
																	if($nbPlanif>0){$nbCellule++;}
																	//Liste des surveillances clôturés
																	$req = "SELECT Id
																			FROM soda_surveillance 
																			WHERE Suppr=0 
																			AND AutoSurveillance=0
																			AND Etat='Clôturé'
																			AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$semaineAnneeEC2.")
																			AND Id_Questionnaire=".$Id_Questionnaire."
																			AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
																			";
																	$resultCloture=mysqli_query($bdd,$req);
																	$nbCloture=mysqli_num_rows($resultCloture);
																	if($nbCloture>0){$nbCellule++;}
																	
																	//Liste des surveillances en cours
																	$req = "SELECT Id
																			FROM soda_surveillance 
																			WHERE Suppr=0 
																			AND AutoSurveillance=0
																			AND Etat IN ('En cours - papier','Brouillon')
																			AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$semaineAnneeEC2.")
																			AND Id_Questionnaire=".$Id_Questionnaire."
																			AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
																			";
																	$resultEC=mysqli_query($bdd,$req);
																	$nbEC=mysqli_num_rows($resultEC);
																	if($nbEC>0){$nbCellule++;}
																	
																	//Liste des surveillances clôturés
																	$req = "SELECT COUNT(Id) AS Nb,
																			(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation
																			FROM soda_surveillance 
																			WHERE Suppr=0 
																			AND AutoSurveillance=0
																			AND Etat='Clôturé'
																			AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$semaineAnneeEC2.")
																			AND Id_Questionnaire=".$Id_Questionnaire."
																			AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
																			GROUP BY Id_Prestation
																			ORDER BY Prestation
																			";
																	$resultClotureListe=mysqli_query($bdd,$req);
																	$nbClotureListe=mysqli_num_rows($resultClotureListe);
																	if($nbClotureListe>0){
																		while($rowClotureListe=mysqli_fetch_array($resultClotureListe)) {
																			$presta=substr($rowClotureListe['Prestation'],0,strpos($rowClotureListe['Prestation']," "));
																			if($presta==""){$presta=$rowClotureListe['Prestation'];}
																			$infoCloture.="<B>".$presta."</B> : ".$rowClotureListe['Nb']." <br>";
																			
																		}
																	}

																	if ($nbCellule == 1){
																		if ($nbPlanif > 0){
																			$ligne1 .= "<td style='font-size:11px;cursor:pointer;' width='55px' height='30px' colspan='2' rowspan='2' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$infoPlanif."</span>\n</td>";
																		}
																		elseif($nbRetard > 0){
																			$ligne1 .= "<td style='font-size:11px;cursor:pointer;' width='55px' height='30px' colspan='2' rowspan='2' align='center' bgcolor='".$couleurRetard."' id='leHover'>".$nbRetard."\n<span>".$infoRetard."</span>\n</td>";
																		}
																		elseif($nbCloture > 0){
																			$ligne1 .= "<td style='font-size:11px;cursor:pointer;' width='55px' height='30px' colspan='2' rowspan='2' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$infoCloture."</span></td>";
																		}
																		elseif($nbEC > 0){
																			$ligne1 .= "<td style='font-size:11px;cursor:pointer;' width='55px' height='30px' colspan='2' rowspan='2' align='center' bgcolor='".$couleurEC."'>".$nbEC."</td>";
																		}
																		else{
																			$ligne1 .= "<td style='font-size:11px;cursor:pointer;' width='55px' height='30px' colspan='2' rowspan='2' align='center' >PB</td>";
																		}
																	}
																	elseif ($nbCellule == 2){
																		if ($nbPlanif > 0 && $nbRetard > 0){
																			$ligne1 .= "<td style='font-size:9px;cursor:pointer;' width='28px' rowspan='2' height='30px' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$infoPlanif."</span>\n</td>";
																			$ligne1 .= "<td style='font-size:9px;' width='27px' rowspan='2' height='30px' align='center' bgcolor='".$couleurRetard."' id='leHover'>".$nbRetard."\n<span>".$infoRetard."</span>\n</td>";
																		}
																		elseif ($nbPlanif > 0 && $nbCloture > 0){
																			$ligne1 .= "<td style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$infoPlanif."</span>\n</td>";
																			$ligne2 .= "<td style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$infoCloture."</span></td>";
																		}
																		elseif ($nbPlanif > 0 && $nbEC > 0){
																			$ligne1 .= "<td style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$infoPlanif."</span>\n</td>";
																			$ligne2 .= "<td style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurEC."'>".$nbEC."</td>";
																		}
																		elseif ($nbRetard > 0 && $nbCloture > 0){
																			$ligne1 .= "<td style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurRetard."' id='leHover'>".$nbRetard."\n<span>".$infoRetard."</span>\n</td>";
																			$ligne2 .= "<td style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$infoCloture."</span></td>";
																		}
																		elseif ($nbRetard > 0 && $nbEC > 0){
																			$ligne1 .= "<td style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurRetard."' id='leHover'>".$nbRetard."\n<span>".$infoRetard."</span>\n</td>";
																			$ligne2 .= "<td style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurEC."'>".$nbEC."</td>";
																		}
																		elseif ($nbCloture > 0 && $nbEC > 0){
																			$ligne1 .= "<td style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$infoCloture."</span></td>";
																			$ligne2 .= "<td style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurEC."'>".$nbEC."</td>";
																		}
																		else{
																			$ligne1 .= "<td style='font-size:11px;cursor:pointer;' width='55px' height='30px' colspan='2' rowspan='2' align='center' ></td>";
																		}
																	}
																	elseif ($nbCellule == 3){
																		if ($nbPlanif > 0 && $nbRetard > 0 && $nbCloture > 0){
																			$ligne1 .= "<td style='font-size:9px;cursor:pointer;' width='28px' height='30px' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$infoPlanif."</span>\n</td>";
																			$ligne1 .= "<td style='font-size:9px;' width='27px' height='30px' align='center' bgcolor='".$couleurRetard."' id='leHover'>".$nbRetard."\n<span>".$infoRetard."</span>\n</td>";
																			$ligne2 .= "<td style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$infoCloture."</span></td>";
																		}
																		elseif ($nbPlanif > 0 && $nbRetard > 0 && $nbEC > 0){
																			$ligne1 .= "<td style='font-size:9px;cursor:pointer;' width='28px' height='30px' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$infoPlanif."</span>\n</td>";
																			$ligne1 .= "<td style='font-size:9px;' width='27px' height='30px' align='center' bgcolor='".$couleurRetard."' id='leHover'>".$nbRetard."\n<span>".$infoRetard."</span>\n</td>";
																			$ligne2 .= "<td style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurEC."'>".$nbEC."</td>";
																		}
																		elseif ($nbPlanif > 0 && $nbCloture > 0 && $nbEC > 0){
																			$ligne1 .= "<td style='font-size:9px;cursor:pointer;' width='28px' height='30px' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$infoPlanif."</span>\n</td>";
																			$ligne1 .= "<td style='font-size:9px;cursor:pointer;' width='55px' height='15px' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$infoCloture."</span></td>";
																			$ligne2 .= "<td style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurEC."'>".$nbEC."</td>";
																		}
																		elseif ($nbRetard > 0 && $nbCloture > 0 && $nbEC > 0){
																			$ligne1 .= "<td style='font-size:9px;' width='27px' height='30px' align='center' bgcolor='".$couleurRetard."' id='leHover'>".$nbRetard."\n<span>".$infoRetard."</span>\n</td>";
																			$ligne1 .= "<td style='font-size:9px;cursor:pointer;' width='55px' height='15px' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$infoCloture."</span></td>";
																			$ligne2 .= "<td style='font-size:9px;cursor:pointer;' width='55px' height='15px' colspan='2' align='center' bgcolor='".$couleurEC."'>".$nbEC."</td>";
																		}
																		else{
																			$ligne1 .= "<td style='font-size:11px;cursor:pointer;' width='55px' height='30px' colspan='2' rowspan='2' align='center' ></td>";
																		}
																	}
																	elseif ($nbCellule == 4){
																		$ligne1 .= "<td style='font-size:9px;cursor:pointer;' width='28px' height='30px' align='center' bgcolor='".$couleurPlanif."' id='leHover'>".$nbPlanif."\n<span>".$infoPlanif."</span>\n</td>";
																		$ligne1 .= "<td style='font-size:9px;' width='27px' height='30px' align='center' bgcolor='".$couleurRetard."' id='leHover'>".$nbRetard."\n<span>".$infoRetard."</span>\n</td>";
																		$ligne2 .= "<td style='font-size:9px;cursor:pointer;' width='55px' height='15px' align='center' bgcolor='".$couleurEC."'>".$nbEC."</td>";
																		$ligne2 .= "<td style='font-size:9px;cursor:pointer;' width='55px' height='15px' align='center' bgcolor='".$couleurCloture."' id='leHover'>".$nbCloture."\n<span>".$infoCloture."</span></td>";
																	}
																	else{
																		$ligne1 .= "<td style='font-size:11px;cursor:pointer;' width='55px' height='30px' colspan='2' rowspan='2' class='".$class."'><br></td>";
																	}
																}
																//Mois suivant et prochain lundi
																$tmpDate=date("Y/m/01",strtotime($dateFin2." +0 day"));
																$tmpDate=date("Y/m/01",strtotime($tmpDate." next month"));
																$tmpDateFin=$tmpDate;
									
																//Si dernier jour = L
																if(date("N",strtotime($tmpDate." +0 day"))==1){
																	$tmpDate=$tmpDate;
																}
																//Si dernier jour = M,Me,J
																elseif(date("N",strtotime($tmpDate." +0 day"))==2 || date("N",strtotime($tmpDate." +0 day"))==3 || date("N",strtotime($tmpDate." +0 day"))==4){
																	$tmpDate=date("Y/m/d",strtotime($tmpDate."last Monday"));
																}
																else{
																	$tmpDate=date("Y/m/d",strtotime($tmpDate."next Monday"));
																}
															}
															if($row['Specifique']==1){
																$volumeObjectif="";
																$deltaPlanifie="";
																$objectifDiversite="";
																$deltaDiversite="";
																$styleDP="";
																$styleD="";
															}
															
															if($row['Specifique']==1){
																$ligne1.="<td bgcolor='#e9e9e9' style='font-size:11px;width:400px;' height='30px' colspan='8' rowspan='2' align='center'></td>";
															}
															else{	
																if($Id_Theme<>$row['Id_Theme']){
																	//Nb questionnaires
																	$req = "SELECT Id 
																			FROM soda_questionnaire
																			WHERE Suppr=0 
																			AND Actif=0 
																			AND Id_Theme=".$row['Id_Theme']." 
																			AND Specifique=0";
																	$resultQuestionnaire2=mysqli_query($bdd,$req);
																	$nbQuestionnaire2=mysqli_num_rows($resultQuestionnaire2);
																	
																	$pourcentageApplicabilite=0;
																	$pourcentageDiversite=0;
																	$req="SELECT PourcentageApplicabilite, PourcentageDiversite
																	FROM soda_objectif_theme
																	WHERE Annee=".$anneeATraiter." 
																	AND Id_Theme=".$row['Id_Theme']." ";
																	$result=mysqli_query($bdd,$req);
																	$nb=mysqli_num_rows($result);
																	if ($nb > 0)
																	{
																		$rowT=mysqli_fetch_array($result);
																		$pourcentageApplicabilite=$rowT['PourcentageApplicabilite']/100;
																		$pourcentageDiversite=$rowT['PourcentageDiversite']/100;
																	}
																	
																	$req="SELECT Id,Libelle
																		FROM new_competences_prestation
																		WHERE Id_Plateforme NOT IN (11,14)
																		AND SousSurveillance IN ('','Oui/Yes')
																		AND Active=0 
																		AND Id IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].") ";
																	$resultPresta=mysqli_query($bdd,$req);
																	$nbPresta=mysqli_num_rows($resultPresta);
																	
																	$req="SELECT SUM(Volume) AS Vol
																	FROM soda_plannifmanuelle 
																	WHERE Suppr=0
																	AND Annee=".$anneeATraiter." 
																	AND Id_Questionnaire IN (SELECT Id FROM soda_questionnaire WHERE Suppr=0 AND Actif=0 AND Id_Theme=".$row['Id_Theme']." AND Specifique=0) 
																	AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].") ";
																	$resultVolumePlanifie=mysqli_query($bdd,$req);
																	$nbVolumePlanifie=mysqli_num_rows($resultVolumePlanifie);
																	
																	$volumeObjectif=round(($nbPresta*$pourcentageApplicabilite),0);
																	
																	$volumePlanifie=0;
																	if ($nbVolumePlanifie > 0)
																	{
																		$rowP=mysqli_fetch_array($resultVolumePlanifie);
																		$volumePlanifie=$rowP['Vol'];
																	}
																	
																	$deltaPlanifie=$volumePlanifie-$volumeObjectif;
																	if($deltaPlanifie>=0){$styleDP="background-color:#68b30f;";}
																	else{$styleDP="background-color:#f1696d;";}
																	
																	//Liste des surveillances clôturés de l'année
																	$req = "SELECT Id
																			FROM soda_surveillance 
																			WHERE Suppr=0 
																			AND AutoSurveillance=0
																			AND Etat='Clôturé'
																			AND YEAR(DateSurveillance)=".$anneeATraiter."
																			AND Id_Questionnaire IN (SELECT Id FROM soda_questionnaire WHERE Suppr=0 AND Actif=0 AND Id_Theme=".$row['Id_Theme']." AND Specifique=0) 
																			AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].") ";
															
																	$resultCloture=mysqli_query($bdd,$req);
																	$nbCloture=mysqli_num_rows($resultCloture);
																	
																	$deltaRealisePlanifie=$volumePlanifie-$nbCloture;
																	if($deltaRealisePlanifie<=0){$styleDR="background-color:#68b30f;";}
																	else{$styleDR="background-color:#f1696d;";}
																	
																	$rowspan=$nbQuestionnaire2*2;
																	$ligne1.="<td bgcolor='#e9e9e9' style='font-size:11px;width:30px;' height='30px' rowspan='".$rowspan."' align='center'>".$volumePlanifie."</td>
																	<td bgcolor='#e9e9e9' style='font-size:11px;width:30px;' height='30px' rowspan='".$rowspan."' align='center'>".$volumeObjectif."</td>
																	<td bgcolor='#e9e9e9' style='font-size:11px;width:30px;".$styleDP."' height='30px' rowspan='".$rowspan."' align='center'>".$deltaPlanifie."</td>
																	<td bgcolor='#e9e9e9' style='font-size:11px;width:30px;' height='30px' rowspan='".$rowspan."' align='center'>".$nbCloture."</td>
																	<td bgcolor='#e9e9e9' style='font-size:11px;width:30px;".$styleDR."' height='30px' rowspan='".$rowspan."' align='center'>".$deltaRealisePlanifie."</td>";
																	
																	$Id_Theme=$row['Id_Theme'];
																}
															}
															
															$ligne1 .= "</tr>";
															$ligne2 .= "</tr>";
															
															echo $ligne1;
															echo $ligne2;
														}
													 }
													?>
													</tbody>
											</table>
											</div>
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
											<div id="chart_Adherence" style="width:100%;height:600px;"></div>
											<script>
												var chart2 = am4core.create("chart_Adherence", am4charts.XYChart);

												// Add data
												chart2.data = <?php echo json_encode($arrayAdherencePlanning); ?>;
												
												var title = chart2.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Schedule adherence / ".$plage." ".$laPlage);}else{echo json_encode(utf8_encode("Adhérence planning / ".$plage." ".$laPlage));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;
												
												// Create axes
												var categoryAxis = chart2.xAxes.push(new am4charts.CategoryAxis());
												categoryAxis.dataFields.category = "Mois";
												categoryAxis.renderer.grid.template.location = 0;
												categoryAxis.renderer.minGridDistance = 10;
												categoryAxis.renderer.labels.template.horizontalCenter = "right";
												categoryAxis.renderer.labels.template.verticalCenter = "middle";
												categoryAxis.renderer.labels.template.rotation = 270;
												categoryAxis.tooltip.disabled = true;
												categoryAxis.renderer.minHeight = 0;

												var valueAxis1 = chart2.yAxes.push(new am4charts.ValueAxis());
												valueAxis1.renderer.minWidth = 0;
												valueAxis1.min= 0;
												valueAxis1.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number");}else{echo json_encode(utf8_encode("Nbr"));} ?>;
												
												// Create series
												var series3 = chart2.series.push(new am4charts.ColumnSeries());
												series3.columns.template.width = am4core.percent(90);
												series3.tooltipText = "{name}: {valueY.value}";
												series3.dataFields.categoryX = "Mois";
												series3.dataFields.valueY = "Volume";
												series3.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of surveillances planned to date");}else{echo json_encode(utf8_encode("Nbr de surveillances prévues à ce jour"));} ?>;
												series3.stacked = false;
												series3.stroke  = "#6EB4CD";
												series3.fill  = "#6EB4CD";
												
												var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
												bullet3.label.text = "{valueY}";
												bullet3.locationY = 0.5;
												bullet3.label.fill = am4core.color("#ffffff");
												bullet3.interactionsEnabled = false;
												bullet3.fontSize = 10;
												
												var series2 = chart2.series.push(new am4charts.ColumnSeries());
												series2.columns.template.width = am4core.percent(90);
												series2.tooltipText = "{name}: {valueY.value}";
												series2.dataFields.categoryX = "Mois";
												series2.dataFields.valueY = "NbRealise";
												series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number of monitoring carried out");}else{echo json_encode(utf8_encode("Nbr de surveillances réalisées"));} ?>;
												series2.stacked = false;
												series2.stroke  = "#7ed957";
												series2.fill  = "#7ed957";
												
												var bullet2 = series2.bullets.push(new am4charts.LabelBullet());
												bullet2.label.text = "{valueY}";
												bullet2.locationY = 0.5;
												bullet2.label.fill = am4core.color("#ffffff");
												bullet2.interactionsEnabled = false;
												bullet2.fontSize = 10;
												
												var valueAxis2 = chart2.yAxes.push(new am4charts.ValueAxis());
												valueAxis2.renderer.minWidth = 0;
												valueAxis2.min= 0;
												valueAxis2.max= 130;
												valueAxis2.strictMinMax = true;
												valueAxis2.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("%");}else{echo json_encode(utf8_encode("%"));} ?>;
												valueAxis2.renderer.opposite = true;
												valueAxis2.syncWithAxis = valueAxis1;
												
												var series4 = chart2.series.push(new am4charts.LineSeries());												
												series4.tooltipText = "{name}: {valueY.value}";
												series4.dataFields.categoryX = "Mois";
												series4.dataFields.valueY = "NbCloture";
												series4.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("% accomplished");}else{echo json_encode(utf8_encode("% réalisé"));} ?>;
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
												chart2.cursor = new am4charts.XYCursor();
												chart2.cursor.behavior = "panX";
												chart2.cursor.lineX.opacity = 0;
												chart2.cursor.lineY.opacity = 0;
												
												/* Add legend */
												chart2.legend = new am4charts.Legend();
												chart2.scrollbarX = new am4core.Scrollbar();
												
												chart2.exporting.menu = new am4core.ExportMenu();
											</script>
										</td>
									</tr>
									<tr>
										<td>
											<table>
												<tr>
													<td valign="top">
														Nbr de surveillances prévues à ce jour = 
													</td> 
													<td valign="top">
														Nbr de surveillances planifiées
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<table>
												<tr>
													<td valign="top">
														Nbr de surveillances réalisées = 
													</td> 
													<td valign="top">
														Nbr de surveillances réalisées des planifiées
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<table>
											<tr>
												<td valign="top">
													% réalisées =
												</td> 
												<td valign="top">
													Nbr de surveillances réalisées (planifiées ou non planifiées) / (Nbr de surveillances planifiées + nbr de surveillances réalisées sans planif)
												</td>
											</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
							<td width="50%">
								<table style="width:100%;">
									<tr><td height="4"></td></tr>
									<tr>
										<td valign="top">
											<div id="chart_NoteMoyenne" style="width:100%;height:600px;"></div>
											<script>
												var chart3 = am4core.create("chart_NoteMoyenne", am4charts.XYChart);

												// Add data
												chart3.data = <?php echo json_encode($arrayNoteMoyenne); ?>;
												chart3.numberFormatter.numberFormat = "#'%'";
												
												var title = chart3.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Average grade ".$laPlage);}else{echo json_encode(utf8_encode("Note moyenne ".$laPlage));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;
												
												// Create axes
												var categoryAxis = chart3.xAxes.push(new am4charts.CategoryAxis());
												categoryAxis.dataFields.category = "Mois";
												categoryAxis.renderer.grid.template.location = 0;
												categoryAxis.renderer.minGridDistance = 15;
												categoryAxis.renderer.labels.template.horizontalCenter = "right";
												categoryAxis.renderer.labels.template.verticalCenter = "middle";
												categoryAxis.renderer.labels.template.rotation = 270;
												categoryAxis.renderer.minHeight = 0;

												var valueAxis1 = chart3.yAxes.push(new am4charts.ValueAxis());
												valueAxis1.renderer.minWidth = 0;
												valueAxis1.min= 0;
												valueAxis1.max= 100;
												
												var series2 = chart3.series.push(new am4charts.LineSeries());
												series2.dataFields.categoryX = "Mois";
												series2.dataFields.valueY = "Seuil";
												series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Pass threshold");}else{echo json_encode(utf8_encode("Seuil de réussite"));} ?>;
												series2.tooltipText = "[{Mois}: bold]{valueY}[/]";
												series2.strokeWidth = 2;
												series2.stroke  = "#000000";
												series2.fill  = "#000000";
												
												var series3 = chart3.series.push(new am4charts.ColumnSeries());
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
												chart3.cursor = new am4charts.XYCursor();
												chart3.cursor.behavior = "panX";
												chart3.cursor.lineX.opacity = 0;
												chart3.cursor.lineY.opacity = 0;
												
												/* Add legend */
												chart3.scrollbarX = new am4core.Scrollbar();
												
												chart3.exporting.menu = new am4core.ExportMenu();
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
											<div id="chart_Conformite" style="width:100%;height:400px;"></div>
											<script>
												var chart4 = am4core.create("chart_Conformite", am4charts.XYChart);

												// Add data
												chart4.data = <?php echo json_encode($arrayConformite); ?>;
												
												var title = chart4.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Success rate ".$laPlage);}else{echo json_encode(utf8_encode("Taux de réussite ".$laPlage));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;
												
												// Create axes
												var categoryAxis = chart4.xAxes.push(new am4charts.CategoryAxis());
												categoryAxis.dataFields.category = "Mois";
												categoryAxis.renderer.grid.template.location = 0;
												categoryAxis.renderer.minGridDistance = 10;
												categoryAxis.renderer.labels.template.horizontalCenter = "right";
												categoryAxis.renderer.labels.template.verticalCenter = "middle";
												categoryAxis.renderer.labels.template.rotation = 270;
												categoryAxis.tooltip.disabled = true;
												categoryAxis.renderer.minHeight = 0;

												var valueAxis1 = chart4.yAxes.push(new am4charts.ValueAxis());
												valueAxis1.renderer.minWidth = 0;
												valueAxis1.min= 0;
												valueAxis1.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number");}else{echo json_encode(utf8_encode("Nbr"));} ?>;
												
												// Create series
												var series3 = chart4.series.push(new am4charts.ColumnSeries());
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
												
												var series2 = chart4.series.push(new am4charts.ColumnSeries());
												series2.columns.template.width = am4core.percent(90);
												series2.tooltipText = "{name}: {valueY.value}";
												series2.dataFields.categoryX = "Mois";
												series2.dataFields.valueY = "NbReussie";
												series2.name = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Nbr of surveillances to the objective");}else{echo json_encode(utf8_encode("Nbr de surveillances à l'objectif"));} ?>;
												series2.stacked = false;
												series2.stroke  = "#7ed957";
												series2.fill  = "#7ed957";
												
												var bullet2 = series2.bullets.push(new am4charts.LabelBullet());
												bullet2.label.text = "{valueY}";
												bullet2.locationY = 0.5;
												bullet2.label.fill = am4core.color("#ffffff");
												bullet2.interactionsEnabled = false;
												bullet2.fontSize = 10;
												
												var valueAxis2 = chart4.yAxes.push(new am4charts.ValueAxis());
												valueAxis2.renderer.minWidth = 0;
												valueAxis2.min= 0;
												valueAxis2.max= 130;
												valueAxis2.strictMinMax = true;
												valueAxis2.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("%");}else{echo json_encode(utf8_encode("%"));} ?>;
												valueAxis2.renderer.opposite = true;
												valueAxis2.syncWithAxis = valueAxis1;
												
												var series4 = chart4.series.push(new am4charts.LineSeries());												
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
												chart4.cursor = new am4charts.XYCursor();
												chart4.cursor.behavior = "panX";
												chart4.cursor.lineX.opacity = 0;
												chart4.cursor.lineY.opacity = 0;
												
												/* Add legend */
												chart4.legend = new am4charts.Legend();
												chart4.scrollbarX = new am4core.Scrollbar();
												
												chart4.exporting.menu = new am4core.ExportMenu();
											</script>
										</td>
									</tr>
								</table>
							</td>
							<td width="50%" valign="top">
								<table style="width:100%;">
									<tr><td height="4"></td></tr>
									<tr>
										<td valign="top">
											<div id="chart_Ecart" style="width:100%;height:600px;"></div>
											<script>
												var chart5 = am4core.create("chart_Ecart", am4charts.XYChart);

												// Add data
												chart5.data = <?php echo json_encode($arrayEcart); ?>;
												
												var title = chart5.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Success rate / ".$plage." ".$laPlage);}else{echo json_encode(utf8_encode("Taux de réussite / ".$plage." ".$laPlage));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;
												
												// Create axes
												var categoryAxis = chart5.xAxes.push(new am4charts.CategoryAxis());
												categoryAxis.dataFields.category = "Mois";
												categoryAxis.renderer.grid.template.location = 0;
												categoryAxis.renderer.minGridDistance = 10;
												categoryAxis.renderer.labels.template.horizontalCenter = "right";
												categoryAxis.renderer.labels.template.verticalCenter = "middle";
												categoryAxis.renderer.labels.template.rotation = 270;
												categoryAxis.tooltip.disabled = true;
												categoryAxis.renderer.minHeight = 0;

												var valueAxis1 = chart5.yAxes.push(new am4charts.ValueAxis());
												valueAxis1.renderer.minWidth = 0;
												valueAxis1.min= 0;
												valueAxis1.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number");}else{echo json_encode(utf8_encode("Nbr"));} ?>;
												
												// Create series
												var series3 = chart5.series.push(new am4charts.ColumnSeries());
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
												
												var series2 = chart5.series.push(new am4charts.ColumnSeries());
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
												
												var valueAxis2 = chart5.yAxes.push(new am4charts.ValueAxis());
												valueAxis2.renderer.minWidth = 0;
												valueAxis2.min= 0;
												valueAxis2.max= 130;
												valueAxis2.strictMinMax = true;
												valueAxis2.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("%");}else{echo json_encode(utf8_encode("%"));} ?>;
												valueAxis2.renderer.opposite = true;
												valueAxis2.syncWithAxis = valueAxis1;
												
												var series4 = chart5.series.push(new am4charts.LineSeries());												
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
												chart5.cursor = new am4charts.XYCursor();
												chart5.cursor.behavior = "panX";
												chart5.cursor.lineX.opacity = 0;
												chart5.cursor.lineY.opacity = 0;
												
												/* Add legend */
												chart5.legend = new am4charts.Legend();
												chart5.scrollbarX = new am4core.Scrollbar();
												
												chart5.exporting.menu = new am4core.ExportMenu();
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
										<td align="right"><a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreExcel('NCv2')"><?php if($_SESSION['Langue']=="EN"){echo "Export NC";}else{echo "Export NC";}?></a></td>
									</tr>
									<tr>
										<td valign="top">
											<div id="chart_ParetoNC" style="width:100%;height:300px;"></div>
											<script>
												var chart6 = am4core.create("chart_ParetoNC", am4charts.XYChart);

												// Add data
												chart6.data = <?php echo json_encode($arrayParetoNC); ?>;
												
												var title = chart6.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Pareto of non-compliant questions in volume (Top 15)");}else{echo json_encode(utf8_encode("Pareto des questions non conformes en volume (Top 15)"));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;

												// Create axes
												var yAxis = chart6.yAxes.push(new am4charts.CategoryAxis());
												yAxis.dataFields.category = "Mois";
												yAxis.renderer.grid.template.location = 0;
												yAxis.renderer.labels.template.fontSize = 10;
												yAxis.renderer.labels.positionX = 0;
												yAxis.renderer.minGridDistance = 0;
												
												var xAxis = chart6.xAxes.push(new am4charts.ValueAxis());
												xAxis.min= 0;
																				
												var series3 = chart6.series.push(new am4charts.ColumnSeries());
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
												chart6.cursor = new am4charts.XYCursor();
												chart6.cursor.behavior = "panX";
												chart6.cursor.lineX.opacity = 0;
												chart6.cursor.lineY.opacity = 0;
												
												/* Add legend */
												chart6.scrollbarX = new am4core.Scrollbar();
												
												chart6.exporting.menu = new am4core.ExportMenu();
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
										<td align="right"><a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreExcel('NC2v2')"><?php if($_SESSION['Langue']=="EN"){echo "Export NC";}else{echo "Export NC";}?></a></td>
									</tr>
									<tr>
										<td valign="top">
											<div id="chart_ParetoNC2" style="width:100%;height:300px;"></div>
											<script>
												var chart7 = am4core.create("chart_ParetoNC2", am4charts.XYChart);

												// Add data
												chart7.data = <?php echo json_encode($arrayParetoNC2); ?>;
												
												var title = chart7.titles.create();
												title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Pareto of non-compliant questions in Ratio (Top 15)");}else{echo json_encode(utf8_encode("Pareto des questions non conformes en ratio (Top 15)"));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;

												// Create axes
												var yAxis = chart7.yAxes.push(new am4charts.CategoryAxis());
												yAxis.dataFields.category = "Mois";
												yAxis.renderer.grid.template.location = 0;
												yAxis.renderer.labels.template.fontSize = 10;
												yAxis.renderer.labels.positionX = 0;
												yAxis.renderer.minGridDistance = 0;
												
												var xAxis = chart7.xAxes.push(new am4charts.ValueAxis());
												xAxis.min= 0;
																				
												var series3 = chart7.series.push(new am4charts.ColumnSeries());
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
												chart7.cursor = new am4charts.XYCursor();
												chart7.cursor.behavior = "panX";
												chart7.cursor.lineX.opacity = 0;
												chart7.cursor.lineY.opacity = 0;
												
												/* Add legend */
												chart7.scrollbarX = new am4core.Scrollbar();
												
												chart7.exporting.menu = new am4core.ExportMenu();
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
												var chart8 = am4core.create("chart_ParetoNC3", am4charts.XYChart);

												// Add data
												chart8.data = <?php echo json_encode($arrayParetoNC3); ?>;
												
												var title = chart8.titles.create();
												title.text = <?php if($_SESSION['Langue']=="FR"){echo json_encode("Nombre de reponses NC par questions");}else{echo json_encode(utf8_encode("Number of NC responses per question"));} ?>;
												title.fontSize = 20;
												title.marginBottom = 0;
												
												// Create axes
												var categoryAxis = chart8.xAxes.push(new am4charts.CategoryAxis());
												categoryAxis.dataFields.category = "Mois";
												categoryAxis.renderer.grid.template.location = 0;
												categoryAxis.renderer.minGridDistance = 60;
												categoryAxis.renderer.labels.template.horizontalCenter = "right";
												categoryAxis.renderer.labels.template.verticalCenter = "middle";
												categoryAxis.renderer.labels.template.rotation = 270;
												categoryAxis.tooltip.disabled = true;
												categoryAxis.renderer.minHeight = 0;

												var valueAxis1 = chart8.yAxes.push(new am4charts.ValueAxis());
												valueAxis1.renderer.minWidth = 0;
												valueAxis1.min= 0;
												valueAxis1.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("Number");}else{echo json_encode(utf8_encode("Nbr"));} ?>;
												
												// Create series
												var series3 = chart8.series.push(new am4charts.ColumnSeries());
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
												
												var series2 = chart8.series.push(new am4charts.ColumnSeries());
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
												
												var valueAxis2 = chart8.yAxes.push(new am4charts.ValueAxis());
												valueAxis2.renderer.minWidth = 0;
												valueAxis2.min= 0;
												valueAxis2.max= 130;
												valueAxis2.strictMinMax = true;
												valueAxis2.title.text = <?php if($_SESSION['Langue']=="EN"){echo json_encode("%");}else{echo json_encode(utf8_encode("%"));} ?>;
												valueAxis2.renderer.opposite = true;
												valueAxis2.syncWithAxis = valueAxis1;
												
												var series4 = chart8.series.push(new am4charts.LineSeries());												
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
												chart8.cursor = new am4charts.XYCursor();
												chart8.cursor.behavior = "panX";
												chart8.cursor.lineX.opacity = 0;
												chart8.cursor.lineY.opacity = 0;
												
												/* Add legend */
												chart8.legend = new am4charts.Legend();
												chart8.scrollbarX = new am4core.Scrollbar();
												
												chart8.exporting.menu = new am4core.ExportMenu();
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
								&nbsp; <?php if($_SESSION["Langue"]=="FR"){echo "Vue détaillée";}else{echo "Detailed view";}?> :
								<?php
									$laDatedeRef=date($_SESSION['FiltreSODATDBOperation_Annee']."-".$_SESSION['FiltreSODATDBOperation_Mois']."-01");
									$laDatedeRefFin=date($_SESSION['FiltreSODATDBOperation_AnneeFin']."-".$_SESSION['FiltreSODATDBOperation_MoisFin']."-01");
									$laDatedeRefFin=date("Y-m-d",strtotime($laDatedeRefFin." +1 month"));
									$laDatedeRefFin=date("Y-m-d",strtotime($laDatedeRefFin." -1 day"));

									$dateDebut=$laDatedeRef;
									$dateFin=$laDatedeRefFin;
									
									$lundi2=$dateDebut;
									if(date("N",strtotime($lundi2." +0 day"))==1){$lundi2=$lundi2;}
									else{$lundi2=date("Y-m-d",strtotime($lundi2."last Monday"));}
									$anneeSemaine2="";
									$lundiFin2=$dateFin;
									$semDebut="";
									$semFin="";
									if(date("N",strtotime($lundiFin2." +0 day"))==1){$lundiFin2=$lundiFin2;}
									else{$lundiFin2=date("Y-m-d",strtotime($lundiFin2."last Monday"));}
									
									while($lundi2<=$lundiFin2){
										if(date("N",strtotime($lundi2." +0 day"))==1){$lundi2=$lundi2;}
										else{$lundi2=date("Y-m-d",strtotime($lundi2."last Monday"));}
										$jeudi=date("Y-m-d",strtotime($lundi2." +3 day"));

										if($jeudi>=$dateDebut && $jeudi<=$dateFin){
											$tabDate = explode('-', $jeudi);
											$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);

											if($anneeSemaine2<>""){$anneeSemaine2.=",";}
											$anneeSemaine2.= "'".date('Y_W', $timestamp)."'";
											if($semDebut==""){$semDebut=date('Y_W', $timestamp);}
											$semFin=date('Y_W', $timestamp);
										}
										$lundi2=date("Y-m-d",strtotime($lundi2." +7 day"));
									
									}
									
									$anneeSemaineYTD="";
									$dateDebutYTD=date($_SESSION['FiltreSODATDBOperation_AnneeFin']."-01-01");
									$lundi2=date($_SESSION['FiltreSODATDBOperation_AnneeFin']."-01-01");
									while($lundi2<=$lundiFin2){
										if(date("N",strtotime($lundi2." +0 day"))==1){$lundi2=$lundi2;}
										else{$lundi2=date("Y-m-d",strtotime($lundi2."last Monday"));}
										$jeudi=date("Y-m-d",strtotime($lundi2." +3 day"));

										if($jeudi>=$dateDebutYTD && $jeudi<=$dateFin){
											$tabDate = explode('-', $jeudi);
											$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);

											if($anneeSemaineYTD<>""){$anneeSemaineYTD.=",";}
											$anneeSemaineYTD.= "'".date('Y_W', $timestamp)."'";
										}
										$lundi2=date("Y-m-d",strtotime($lundi2." +7 day"));
									
									}

								?>
							</td>
						</tr>
						<tr>
							<td align="center">
								<table class="TableCompetences" align="center" width="100%">
									<tr>
										<td style="color:#00567c;border-bottom:2px #055981 solid;" width="15%">
										</td>
										<td style="color:#00567c;border-bottom:2px #055981 solid;border-right:4px double #1e3a84;text-align:center;" width="10%" colspan="7">
										<?php
											echo $_SESSION['FiltreSODATDBOperation_Mois']."/".$_SESSION['FiltreSODATDBOperation_Annee'];
											echo " -> ";
											echo $_SESSION['FiltreSODATDBOperation_MoisFin']."/".$_SESSION['FiltreSODATDBOperation_AnneeFin'];
											if($_SESSION['Langue']=="FR"){
												echo "<br>(Sem. ".$semDebut." -> ".$semFin.")";
											}
											else{
												echo "<br>(Week ".$semDebut." -> ".$semFin.")";
											}
										?>
										</td>
										<td style="color:#00567c;border-bottom:2px #055981 solid;text-align:center;" width="10%" colspan="7">
										<?php
											echo "01/".$_SESSION['FiltreSODATDBOperation_Annee'];
											echo " -> ";
											echo $_SESSION['FiltreSODATDBOperation_MoisFin']."/".$_SESSION['FiltreSODATDBOperation_AnneeFin'];
										?>
										</td>
									</tr>
									<tr>
										<td style="color:#00567c;border-bottom:2px #055981 solid;" width="15%">
											<?php 
											if($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="Theme"){
												if($_SESSION["Langue"]=="FR"){echo "Thématique";}else{echo "Thematic";}
											}
											elseif($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="Prestation"){
												if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";}
											}
											elseif($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="UER"){
												echo "UER";
											}
											?>
										</td>
										<!-- SUR LA PERIODE -->
										<td style="color:#00567c;border-bottom:2px #055981 solid;text-align:center;" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Nb surv. planifiées";}else{echo "Number of scheduled watches";}?></td>
										<td style="color:#00567c;border-bottom:2px #055981 solid;text-align:center;" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Nb surv. planifiées réalisées";}else{echo "Number of planned watches carried out";}?></td>
										<td style="color:#00567c;border-bottom:2px #055981 solid;text-align:center;" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Taux adhérence planning";}else{echo "Schedule adherence rate";}?></td>
										<td style="color:#00567c;border-bottom:2px #055981 solid;text-align:center;" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Nb surv. réalisées sans planification";}else{echo "Number of watches carried out without planning";}?></td>
										<td style="color:#00567c;border-bottom:2px #055981 solid;text-align:center;" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Taux adhérence planning corrigé";}else{echo "Corrected planning adhesion rate";}?></td>
										<td style="color:#00567c;border-bottom:2px #055981 solid;text-align:center;" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Taux de réussite en %";}else{echo "Success rate in %";}?></td>
										<td style="color:#00567c;border-bottom:2px #055981 solid;text-align:center;border-right:4px double #1e3a84;" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Score moyen en %";}else{echo "Average score in %";}?></td>

										<!-- SUR L'ANNEE -->
										
										<td style="color:#00567c;border-bottom:2px #055981 solid;text-align:center;" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Nb surv. planifiées";}else{echo "Number of scheduled watches";}?></td>
										<td style="color:#00567c;border-bottom:2px #055981 solid;text-align:center;" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Nb surv. réalisées sur les planifiées";}else{echo "Number of watches carried out out of those planned";}?></td>
										<td style="color:#00567c;border-bottom:2px #055981 solid;text-align:center;" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Taux adhérence planning";}else{echo "Schedule adherence rate";}?></td>
										<td style="color:#00567c;border-bottom:2px #055981 solid;text-align:center;" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Nb surv. réalisées sans planification";}else{echo "Number of watches carried out without planning";}?></td>
										<td style="color:#00567c;border-bottom:2px #055981 solid;text-align:center;" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Taux adhérence planning corrigé";}else{echo "Corrected planning adhesion rate";}?></td>
										<td style="color:#00567c;border-bottom:2px #055981 solid;text-align:center;" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Taux de réussite en %";}else{echo "Success rate in %";}?></td>
										<td style="color:#00567c;border-bottom:2px #055981 solid;text-align:center;" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Score moyen en %";}else{echo "Average score in %";}?></td>
									</tr>
									<?php
									if($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="Theme"){
										$req = "SELECT Id, Libelle AS Intitule
											FROM soda_theme 
											WHERE Id IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
											AND Id NOT IN (9,12,13,14)
											ORDER BY Libelle
											";
									}
									elseif($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="Prestation"){
										$req = "SELECT Id, Libelle AS Intitule
											FROM new_competences_prestation 
											WHERE Id IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
											ORDER BY Libelle
											";
									}
									elseif($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="UER"){
										$req = "SELECT DISTINCT Id_Plateforme AS Id, (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Intitule
											FROM new_competences_prestation 
											WHERE Id IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
											ORDER BY (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme)
											";
									}
									$result=mysqli_query($bdd,$req);
									$nbDonnees=mysqli_num_rows($result);
									
									
									if($nbDonnees>0){
										$couleur="#ffffff";
										while($row2=mysqli_fetch_array($result)){
											$intitule2="";
											if($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="Theme"){
												$intitule2=$row2['Intitule'];
												//NbCloturé
												//Période
												$req = "SELECT Id,NumActionTracker
														FROM soda_surveillance 
														WHERE Suppr=0 
														AND AutoSurveillance=0
														AND Etat='Clôturé'
														AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
														";
												//Année
												$reqAnnee = "SELECT Id,NumActionTracker
														FROM soda_surveillance 
														WHERE Suppr=0 
														AND AutoSurveillance=0
														AND Etat='Clôturé'
														AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
														AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
														";
	
												$reqRealiseSansPlanif = "SELECT Id
													FROM soda_surveillance 
													WHERE Suppr=0 
													AND AutoSurveillance=0
													AND Etat='Clôturé'
													AND (Id_PlannifManuelle=0 OR (SELECT COUNT(Id) FROM soda_plannifmanuelle WHERE Id=Id_PlannifManuelle AND Suppr=0 AND Volume>0)=0)
													AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
													AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
													AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
													";
													
												$reqRealiseSansPlanifAnnee = "SELECT Id
													FROM soda_surveillance 
													WHERE Suppr=0 
													AND AutoSurveillance=0
													AND Etat='Clôturé'
													AND (Id_PlannifManuelle=0 OR (SELECT COUNT(Id) FROM soda_plannifmanuelle WHERE Id=Id_PlannifManuelle AND Suppr=0 AND Volume>0)=0)
													AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
													AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
													AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
													AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
													";
												
												$reqVol="SELECT Annee,Semaine,Id_Questionnaire,Id_Prestation,
													Volume AS VolTotal,
													(SELECT COUNT(soda_surveillance.Id) 
													FROM soda_surveillance 
													WHERE soda_surveillance.Suppr=0 
													AND AutoSurveillance=0 
													AND soda_plannifmanuelle.Id_Questionnaire=soda_surveillance.Id_Questionnaire
													AND Etat='Clôturé'
													AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.") 
													AND soda_surveillance.Id_Prestation=soda_plannifmanuelle.Id_Prestation 
													AND Id_PlannifManuelle=soda_plannifmanuelle.Id) AS VolumeCloture,
													(SELECT COUNT(soda_surveillance.Id) 
													FROM soda_surveillance 
													WHERE soda_surveillance.Suppr=0 
													AND AutoSurveillance=0 
													AND soda_plannifmanuelle.Id_Questionnaire=soda_surveillance.Id_Questionnaire
													AND Etat='Clôturé'
													AND YEAR(DateSurveillance)=".date("Y",strtotime($dateFin." +0 month"))."
													AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
													AND soda_surveillance.Id_Prestation=soda_plannifmanuelle.Id_Prestation 
													AND Id_PlannifManuelle=soda_plannifmanuelle.Id) AS VolumeClotureAnnee
													FROM soda_plannifmanuelle 
													WHERE Suppr=0 
													AND Annee=".date("Y",strtotime($dateFin." +0 month"))."
													AND CONCAT(Annee,'_',IF(Semaine<10,CONCAT(0,Semaine),Semaine)) IN (".$anneeSemaineYTD.")
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
													AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
													AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
													AND Volume>0
													ORDER BY Annee,Semaine,Id_Prestation";

												//Note
												//Période
												$reqNote = "SELECT ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
														FROM soda_surveillance 
														WHERE Suppr=0 
														AND AutoSurveillance=0
														AND Etat='Clôturé'
														AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
														";
												$resultNote=mysqli_query($bdd,$reqNote);
												$nbNote=mysqli_num_rows($resultNote);
												$noteMoyenne=0;
												if($nbNote>0){
													$rowNote=mysqli_fetch_array($resultNote);
													$noteMoyenne=$rowNote['NoteMoyenne'];
												}
														
												//Année
												$reqNoteAnnee = "SELECT ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
														FROM soda_surveillance 
														WHERE Suppr=0 
														AND AutoSurveillance=0
														AND Etat='Clôturé'
														AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
														AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
														";
												$resultNoteAnnee=mysqli_query($bdd,$reqNoteAnnee);
												$nbNoteAnnee=mysqli_num_rows($resultNoteAnnee);
												$noteMoyenneAnnee=0;
												if($nbNoteAnnee>0){
													$rowNote=mysqli_fetch_array($resultNoteAnnee);
													$noteMoyenneAnnee=$rowNote['NoteMoyenne'];
												}
												
												//Période
												$reqConforme = "SELECT ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100,1) AS NoteMoyenne,
														(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Seuil
														FROM soda_surveillance 
														WHERE Suppr=0 
														AND AutoSurveillance=0
														AND Etat='Clôturé'
														AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
														";
												$resultConforme=mysqli_query($bdd,$reqConforme);
												$nConforme=mysqli_num_rows($resultConforme);
												$reussite=0;
												$echec=0;
												if($nConforme>0){
													while($rowConforme=mysqli_fetch_array($resultConforme)){
														if($rowConforme['NoteMoyenne']<>"" && $rowConforme['NoteMoyenne']<$rowConforme['Seuil']){$echec++;}
														else{$reussite++;}
													}
													$reussite=round(($reussite/$nConforme)*100,1);
													$echec=round(($echec/$nConforme)*100,1);
												}
												else{
													$reussite=null;
													$echec=null;
												}
												
												//Année
												$reqConformeAnnee = "SELECT ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100,1) AS NoteMoyenne,
														(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Seuil
														FROM soda_surveillance 
														WHERE Suppr=0 
														AND AutoSurveillance=0
														AND Etat='Clôturé'
														AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
														AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
														";
												$resultConformeAnnee=mysqli_query($bdd,$reqConformeAnnee);
												$nConformeAnnee=mysqli_num_rows($resultConformeAnnee);
												$reussiteAnnee=0;
												$echecAnnee=0;
												if($nConformeAnnee>0){
													while($rowConforme=mysqli_fetch_array($resultConformeAnnee)){
														if($rowConforme['NoteMoyenne']<>"" && $rowConforme['NoteMoyenne']<$rowConforme['Seuil']){$echecAnnee++;}
														else{$reussiteAnnee++;}
													}
													$reussiteAnnee=round(($reussiteAnnee/$nConformeAnnee)*100,1);
													$echecAnnee=round(($echecAnnee/$nConformeAnnee)*100,1);
												}
												else{
													$reussiteAnnee=null;
													$echecAnnee=null;
												}
											}
											elseif($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="UER"){
												$intitule2=$row2['Intitule'];
												//NbCloturé
												//Période
												$req = "SELECT Id,NumActionTracker
														FROM soda_surveillance 
														WHERE Suppr=0 
														AND AutoSurveillance=0
														AND Etat='Clôturé'
														AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$row2['Id']."
														";
												//Année
												$reqAnnee = "SELECT Id,NumActionTracker
														FROM soda_surveillance 
														WHERE Suppr=0 
														AND AutoSurveillance=0
														AND Etat='Clôturé'
														AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
														AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$row2['Id']."
														";
														
												$reqRealiseSansPlanif = "SELECT Id
													FROM soda_surveillance 
													WHERE Suppr=0 
													AND AutoSurveillance=0
													AND Etat='Clôturé'
													AND (Id_PlannifManuelle=0 OR (SELECT COUNT(Id) FROM soda_plannifmanuelle WHERE Id=Id_PlannifManuelle AND Suppr=0 AND Volume>0)=0)
													AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
													AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
													AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
													AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$row2['Id']."
													";
												$reqRealiseSansPlanifAnnee = "SELECT Id
													FROM soda_surveillance 
													WHERE Suppr=0 
													AND AutoSurveillance=0
													AND Etat='Clôturé'
													AND (Id_PlannifManuelle=0 OR (SELECT COUNT(Id) FROM soda_plannifmanuelle WHERE Id=Id_PlannifManuelle AND Suppr=0 AND Volume>0)=0)
													AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
													AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
													AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
													AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$row2['Id']."
													";
												
												$reqVol="SELECT Annee,Semaine,Id_Questionnaire,Id_Prestation,
													Volume AS VolTotal,
													(SELECT COUNT(soda_surveillance.Id) 
													FROM soda_surveillance 
													WHERE soda_surveillance.Suppr=0 
													AND AutoSurveillance=0 
													AND soda_plannifmanuelle.Id_Questionnaire=soda_surveillance.Id_Questionnaire
													AND Etat='Clôturé'
													AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.") 
													AND soda_surveillance.Id_Prestation=soda_plannifmanuelle.Id_Prestation 
													AND Id_PlannifManuelle=soda_plannifmanuelle.Id) AS VolumeCloture,
													(SELECT COUNT(soda_surveillance.Id) 
													FROM soda_surveillance 
													WHERE soda_surveillance.Suppr=0 
													AND AutoSurveillance=0 
													AND soda_plannifmanuelle.Id_Questionnaire=soda_surveillance.Id_Questionnaire
													AND Etat='Clôturé'
													AND YEAR(DateSurveillance)=".date("Y",strtotime($dateFin." +0 month"))."
													AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
													AND soda_surveillance.Id_Prestation=soda_plannifmanuelle.Id_Prestation 
													AND Id_PlannifManuelle=soda_plannifmanuelle.Id) AS VolumeClotureAnnee
													FROM soda_plannifmanuelle 
													WHERE Suppr=0
													AND Annee=".date("Y",strtotime($dateFin." +0 month"))."
													AND CONCAT(Annee,'_',IF(Semaine<10,CONCAT(0,Semaine),Semaine)) IN (".$anneeSemaineYTD.")
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
													AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
													AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$row2['Id']."
													AND Volume>0
													ORDER BY Annee,Semaine,Id_Prestation";

												//Période
												$reqNote = "SELECT ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
														FROM soda_surveillance 
														WHERE Suppr=0 
														AND AutoSurveillance=0
														AND Etat='Clôturé'
														AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$row2['Id']."
														";
												$resultNote=mysqli_query($bdd,$reqNote);
												$nbNote=mysqli_num_rows($resultNote);
												$noteMoyenne=0;
												if($nbNote>0){
													$rowNote=mysqli_fetch_array($resultNote);
													$noteMoyenne=$rowNote['NoteMoyenne'];
												}
												
												//Annee
												$reqNoteAnnee = "SELECT ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
														FROM soda_surveillance 
														WHERE Suppr=0 
														AND AutoSurveillance=0
														AND Etat='Clôturé'
														AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
														AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$row2['Id']."
														";
												$resultNoteAnnee=mysqli_query($bdd,$reqNoteAnnee);
												$nbNoteAnnee=mysqli_num_rows($resultNoteAnnee);
												$noteMoyenneAnnee=0;
												if($nbNoteAnnee>0){
													$rowNoteAnnee=mysqli_fetch_array($resultNoteAnnee);
													$noteMoyenneAnnee=$rowNoteAnnee['NoteMoyenne'];
												}
												
												//Période
												$reqConforme = "SELECT ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100,1) AS NoteMoyenne,
														(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Seuil
														FROM soda_surveillance 
														WHERE Suppr=0 
														AND AutoSurveillance=0
														AND Etat='Clôturé'
														AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$row2['Id']."
														";
												$resultConforme=mysqli_query($bdd,$reqConforme);
												$nConforme=mysqli_num_rows($resultConforme);
												$reussite=0;
												$echec=0;
												if($nConforme>0){
													while($rowConforme=mysqli_fetch_array($resultConforme)){
														if($rowConforme['NoteMoyenne']<>"" && $rowConforme['NoteMoyenne']<$rowConforme['Seuil']){$echec++;}
														else{$reussite++;}
													}
													$reussite=round(($reussite/$nConforme)*100,1);
													$echec=round(($echec/$nConforme)*100,1);
												}
												else{
													$reussite=null;
													$echec=null;
												}
												
												//Année
												$reqConformeAnnee = "SELECT ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100,1) AS NoteMoyenne,
														(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Seuil
														FROM soda_surveillance 
														WHERE Suppr=0 
														AND AutoSurveillance=0
														AND Etat='Clôturé'
														AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
														AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) = ".$row2['Id']."
														";
												$resultConformeAnnee=mysqli_query($bdd,$reqConformeAnnee);
												$nConformeAnnee=mysqli_num_rows($resultConformeAnnee);
												$reussiteAnnee=0;
												$echecAnnee=0;
												if($nConformeAnnee>0){
													while($rowConforme=mysqli_fetch_array($resultConformeAnnee)){
														if($rowConforme['NoteMoyenne']<>"" && $rowConforme['NoteMoyenne']<$rowConforme['Seuil']){$echecAnnee++;}
														else{$reussiteAnnee++;}
													}
													$reussiteAnnee=round(($reussiteAnnee/$nConformeAnnee)*100,1);
													$echecAnnee=round(($echecAnnee/$nConformeAnnee)*100,1);
												}
												else{
													$reussiteAnnee=null;
													$echecAnnee=null;
												}
											}
											else{
												$intitule2=substr($row2['Intitule'],0,strpos($row2['Intitule']," "));
												//NbCloturé
												//Période
												$req = "SELECT Id,NumActionTracker
														FROM soda_surveillance 
														WHERE Suppr=0 
														AND AutoSurveillance=0
														AND Etat='Clôturé'
														AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND Id_Prestation = ".$row2['Id']."
														";
												
												//Année
												$reqAnnee = "SELECT Id,NumActionTracker
														FROM soda_surveillance 
														WHERE Suppr=0 
														AND AutoSurveillance=0
														AND Etat='Clôturé'
														AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
														AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND Id_Prestation = ".$row2['Id']."
														";
												
												$reqRealiseSansPlanif = "SELECT Id
													FROM soda_surveillance 
													WHERE Suppr=0 
													AND AutoSurveillance=0
													AND Etat='Clôturé'
													AND (Id_PlannifManuelle=0 OR (SELECT COUNT(Id) FROM soda_plannifmanuelle WHERE Id=Id_PlannifManuelle AND Suppr=0 AND Volume>0)=0)
													AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
													AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
													AND Id_Prestation = ".$row2['Id']."
													";
												$reqRealiseSansPlanifAnnee = "SELECT Id
													FROM soda_surveillance 
													WHERE Suppr=0 
													AND AutoSurveillance=0
													AND Etat='Clôturé'
													AND (Id_PlannifManuelle=0 OR (SELECT COUNT(Id) FROM soda_plannifmanuelle WHERE Id=Id_PlannifManuelle AND Suppr=0 AND Volume>0)=0)
													AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
													AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
													AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
													AND Id_Prestation = ".$row2['Id']."
													";

												$reqVol="SELECT Annee,Semaine,Id_Questionnaire,Id_Prestation,
													Volume AS VolTotal,
													(SELECT COUNT(soda_surveillance.Id) 
													FROM soda_surveillance 
													WHERE soda_surveillance.Suppr=0 
													AND AutoSurveillance=0 
													AND soda_plannifmanuelle.Id_Questionnaire=soda_surveillance.Id_Questionnaire
													AND Etat='Clôturé'
													AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.") 
													AND soda_surveillance.Id_Prestation=soda_plannifmanuelle.Id_Prestation 
													AND Id_PlannifManuelle=soda_plannifmanuelle.Id) AS VolumeCloture,
													(SELECT COUNT(soda_surveillance.Id) 
													FROM soda_surveillance 
													WHERE soda_surveillance.Suppr=0 
													AND AutoSurveillance=0 
													AND soda_plannifmanuelle.Id_Questionnaire=soda_surveillance.Id_Questionnaire
													AND Etat='Clôturé'
													AND YEAR(DateSurveillance)=".date("Y",strtotime($dateFin." +0 month"))."
													AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
													AND soda_surveillance.Id_Prestation=soda_plannifmanuelle.Id_Prestation 
													AND Id_PlannifManuelle=soda_plannifmanuelle.Id) AS VolumeClotureAnnee
													FROM soda_plannifmanuelle 
													WHERE Suppr=0
													AND Annee=".date("Y",strtotime($dateFin." +0 month"))."
													AND CONCAT(Annee,'_',IF(Semaine<10,CONCAT(0,Semaine),Semaine)) IN (".$anneeSemaineYTD.")
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
													AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
													AND Id_Prestation = ".$row2['Id']."
													AND Volume>0
													ORDER BY Annee,Semaine,Id_Prestation";
												
												//période
												$reqNote = "SELECT ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
														FROM soda_surveillance 
														WHERE Suppr=0 
														AND AutoSurveillance=0
														AND Etat='Clôturé'
														AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND Id_Prestation = ".$row2['Id']."
														";
												$resultNote=mysqli_query($bdd,$reqNote);
												$nbNote=mysqli_num_rows($resultNote);
												$noteMoyenne=0;
												if($nbNote>0){
													$rowNote=mysqli_fetch_array($resultNote);
													$noteMoyenne=$rowNote['NoteMoyenne'];
												}
												
												//Année 
												$reqNoteAnnee = "SELECT ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
														FROM soda_surveillance 
														WHERE Suppr=0 
														AND AutoSurveillance=0
														AND Etat='Clôturé'
														AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
														AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND Id_Prestation = ".$row2['Id']."
														";
												$resultNoteAnnee=mysqli_query($bdd,$reqNoteAnnee);
												$nbNoteAnnee=mysqli_num_rows($resultNoteAnnee);
												$noteMoyenneAnnee=0;
												if($nbNoteAnnee>0){
													$rowNote=mysqli_fetch_array($resultNoteAnnee);
													$noteMoyenneAnnee=$rowNote['NoteMoyenne'];
												}
												
												//Période
												$reqConforme = "SELECT ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100,1) AS NoteMoyenne,
														(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Seuil
														FROM soda_surveillance 
														WHERE Suppr=0 
														AND AutoSurveillance=0
														AND Etat='Clôturé'
														AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND Id_Prestation = ".$row2['Id']."
														";
												$resultConforme=mysqli_query($bdd,$reqConforme);
												$nConforme=mysqli_num_rows($resultConforme);
												$reussite=0;
												$echec=0;
												if($nConforme>0){
													while($rowConforme=mysqli_fetch_array($resultConforme)){
														if($rowConforme['NoteMoyenne']<>"" && $rowConforme['NoteMoyenne']<$rowConforme['Seuil']){$echec++;}
														else{$reussite++;}
													}
													$reussite=round(($reussite/$nConforme)*100,1);
													$echec=round(($echec/$nConforme)*100,1);
												}
												else{
													$reussite=null;
													$echec=null;
												}
												
												//Année
												$reqConformeAnnee = "SELECT ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100,1) AS NoteMoyenne,
														(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Seuil
														FROM soda_surveillance 
														WHERE Suppr=0 
														AND AutoSurveillance=0
														AND Etat='Clôturé'
														AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
														AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND Id_Prestation = ".$row2['Id']."
														";
												$resultConformeAnnee=mysqli_query($bdd,$reqConformeAnnee);
												$nConformeAnnee=mysqli_num_rows($resultConformeAnnee);
												$reussiteAnnee=0;
												$echecAnnee=0;
												if($nConformeAnnee>0){
													while($rowConforme=mysqli_fetch_array($resultConformeAnnee)){
														if($rowConforme['NoteMoyenne']<>"" && $rowConforme['NoteMoyenne']<$rowConforme['Seuil']){$echecAnnee++;}
														else{$reussiteAnnee++;}
													}
													$reussiteAnnee=round(($reussiteAnnee/$nConformeAnnee)*100,1);
													$echecAnnee=round(($echecAnnee/$nConformeAnnee)*100,1);
												}
												else{
													$reussiteAnnee=null;
													$echecAnnee=null;
												}
											}
											//Période
											$result2=mysqli_query($bdd,$req);
											$nbCloture=mysqli_num_rows($result2);

											//Année
											$result2Annee=mysqli_query($bdd,$reqAnnee);
											$nbClotureAnnee=mysqli_num_rows($result2Annee);
											
											$resultRealiseSansPlanif=mysqli_query($bdd,$reqRealiseSansPlanif);
											$nbRealiseSansPlanif=mysqli_num_rows($resultRealiseSansPlanif);
											
											$resultRealiseSansPlanifAnnee=mysqli_query($bdd,$reqRealiseSansPlanifAnnee);
											$nbRealiseSansPlanifAnnee=mysqli_num_rows($resultRealiseSansPlanifAnnee);
							
											$resultVolume=mysqli_query($bdd,$reqVol);
											$nbVolume=mysqli_num_rows($resultVolume);
											$nbRetard=0;
											$nbPlanif=0;
											$nbVolume2=0;
											$nbVolumeTotal=0;
											$nbAdherence=0;
											$nbAdherenceCorrige=0;
											$nbRealiseEnRetard=0;
											
											$nbRetardAnnee=0;
											$nbPlanifAnnee=0;
											$nbVolume2Annee=0;
											$nbVolumeTotalAnnee=0;
											$nbAdherenceAnnee=0;
											$nbAdherenceCorrigeAnnee=0;
											$nbRealiseEnRetardAnnee=0;
											if ($nbVolume>0){
												while($rowSurveillance=mysqli_fetch_array($resultVolume)) {
													$lasemaine=$rowSurveillance['Annee']."S";
													if($rowSurveillance['Semaine']<10){$lasemaine.="0".$rowSurveillance['Semaine'];}else{$lasemaine.=$rowSurveillance['Semaine'];}
													$lasemainev2=$rowSurveillance['Annee']."_";
													if($rowSurveillance['Semaine']<10){$lasemainev2.="0".$rowSurveillance['Semaine'];}else{$lasemainev2.=$rowSurveillance['Semaine'];}

													if($semaine2>=$lasemaine){
														if(strpos($anneeSemaine2, $lasemainev2) !== false){
															$nbRetard+=$volume;
															$nbVolume2+=$rowSurveillance['VolumeCloture'];
															$nbVolumeTotal+=$rowSurveillance['VolTotal'];
														}
														$nbRetardAnnee+=$volume;
														$nbVolume2Annee+=$rowSurveillance['VolumeClotureAnnee'];
														$nbVolumeTotalAnnee+=$rowSurveillance['VolTotal'];
													}
													else{
														$nbPlanif+=$volume;
													}
													
												}
											}
											$total=$nbVolumeTotal;
											$nbRealiseSurPlanifie=$nbVolume2;
											if($total>0){
												$nbAdherence=round(($nbRealiseSurPlanifie/$total)*100,0);
												$nbAdherenceCorrige=round((($nbRealiseSurPlanifie+$nbRealiseSansPlanif)/$total)*100,0);
											}
											
											$totalAnnee=$nbVolumeTotalAnnee;
											$nbRealiseSurPlanifieAnnee=$nbVolume2Annee;
											if($totalAnnee>0){
												$nbRetardAnnee=round(($nbRetardAnnee/$totalAnnee)*100,1);

												$nbAdherenceAnnee=round(($nbRealiseSurPlanifieAnnee/$totalAnnee)*100,0);
												$nbAdherenceCorrigeAnnee=round((($nbRealiseSurPlanifieAnnee+$nbRealiseSansPlanifAnnee)/$totalAnnee)*100,0);
												
											}
											?>
											<tr style="background-color:<?php echo $couleur;?>">
												<td><?php echo $intitule2;?></td>
												<td style="text-align:center;"><?php echo $total;?></td>
												<td style="text-align:center;"><?php echo $nbRealiseSurPlanifie;?></td>
												<td style="text-align:center;"><?php if($total>0){echo $nbAdherence."%";}else{echo "N/A";}?></td>
												<td style="text-align:center;"><?php echo $nbRealiseSansPlanif;?></td>
												<td style="text-align:center;"><?php if($total>0){echo $nbAdherenceCorrige."%";}else{echo "N/A";}?></td>
												<td style="text-align:center;"><?php if($nConforme>0){echo $reussite." %";}else{echo "N/A";} ?></td>
												<td style="text-align:center;border-right:4px double #1e3a84;"><?php if($nbCloture>0){echo $noteMoyenne." %";}else{echo "N/A";} ?></td>

												<td style="text-align:center;"><?php echo $totalAnnee;?></td>
												<td style="text-align:center;"><?php echo $nbRealiseSurPlanifieAnnee;?></td>
												<td style="text-align:center;"><?php if($totalAnnee>0){echo $nbAdherenceAnnee."%";}else{echo "N/A";}?></td>
												<td style="text-align:center;"><?php echo $nbRealiseSansPlanifAnnee;?></td>
												<td style="text-align:center;"><?php if($totalAnnee>0){echo $nbAdherenceCorrigeAnnee."%";}else{echo "N/A";}?></td>
												<td style="text-align:center;"><?php if($nConformeAnnee>0){echo $reussiteAnnee." %";}else{echo "N/A";} ?></td>
												<td style="text-align:center;"><?php if($nbClotureAnnee>0){echo $noteMoyenneAnnee." %";}else{echo "N/A";} ?></td>
											</tr>
											<?php
											if($couleur=="#ffffff"){$couleur="#b7dfe3";}
											else{$couleur="#ffffff";}
										}
										if($_SESSION['FiltreSODATDBOperation_ModeAffichage']=="Theme"){
											//SOUS TOTAL HORS HSE & PERFO INDUSTRIELLE 
											//NbCloturé
											$req = "SELECT Id,NumActionTracker
													FROM soda_surveillance 
													WHERE Suppr=0 
													AND AutoSurveillance=0
													AND Etat='Clôturé'
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) NOT IN (9,12,13,14)
													AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
													AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
													AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
													";
											$reqAnnee = "SELECT Id,NumActionTracker
													FROM soda_surveillance 
													WHERE Suppr=0 
													AND AutoSurveillance=0
													AND Etat='Clôturé'
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) NOT IN (9,12,13,14)
													AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
													AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
													AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
													AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
													";
													
											$reqRealiseSansPlanif = "SELECT Id
													FROM soda_surveillance 
													WHERE Suppr=0 
													AND AutoSurveillance=0
													AND Etat='Clôturé'
													AND (Id_PlannifManuelle=0 OR (SELECT COUNT(Id) FROM soda_plannifmanuelle WHERE Id=Id_PlannifManuelle AND Suppr=0 AND Volume>0)=0)
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) NOT IN (9,12,13,14)
													AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
													AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
													AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
													";
											$reqRealiseSansPlanifAnnee = "SELECT Id
												FROM soda_surveillance 
												WHERE Suppr=0 
												AND AutoSurveillance=0
												AND Etat='Clôturé'
												AND (Id_PlannifManuelle=0 OR (SELECT COUNT(Id) FROM soda_plannifmanuelle WHERE Id=Id_PlannifManuelle AND Suppr=0 AND Volume>0)=0)
												AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
												AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) NOT IN (9,12,13,14)
												AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
												AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
												AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
												";
											
											$reqVol="SELECT Annee,Semaine,Id_Questionnaire,Id_Prestation,
												Volume AS VolTotal,
												(SELECT COUNT(soda_surveillance.Id) 
												FROM soda_surveillance 
												WHERE soda_surveillance.Suppr=0 
												AND AutoSurveillance=0 
												AND soda_plannifmanuelle.Id_Questionnaire=soda_surveillance.Id_Questionnaire
												AND Etat='Clôturé'
												AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.") 
												AND soda_surveillance.Id_Prestation=soda_plannifmanuelle.Id_Prestation 
												AND Id_PlannifManuelle=soda_plannifmanuelle.Id) AS VolumeCloture,
												(SELECT COUNT(soda_surveillance.Id) 
												FROM soda_surveillance 
												WHERE soda_surveillance.Suppr=0 
												AND AutoSurveillance=0 
												AND soda_plannifmanuelle.Id_Questionnaire=soda_surveillance.Id_Questionnaire
												AND Etat='Clôturé'
												AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
												AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
												AND soda_surveillance.Id_Prestation=soda_plannifmanuelle.Id_Prestation 
												AND Id_PlannifManuelle=soda_plannifmanuelle.Id) AS VolumeClotureAnnee
												FROM soda_plannifmanuelle 
												WHERE Suppr=0
												AND Annee=".date("Y",strtotime($dateFin." +0 month"))."
												AND CONCAT(Annee,'_',IF(Semaine<10,CONCAT(0,Semaine),Semaine)) IN (".$anneeSemaineYTD.")
												AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
												AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) NOT IN (9,12,13,14)
												AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
												ORDER BY Annee,Semaine,Id_Prestation";

											$reqNote = "SELECT ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
													FROM soda_surveillance 
													WHERE Suppr=0 
													AND AutoSurveillance=0
													AND Etat='Clôturé'
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) NOT IN (9,12,13,14)
													AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
													AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
													AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
													";
		
											$resultNote=mysqli_query($bdd,$reqNote);
											$nbNote=mysqli_num_rows($resultNote);
											$noteMoyenne=0;
											if($nbNote>0){
												$rowNote=mysqli_fetch_array($resultNote);
												$noteMoyenne=$rowNote['NoteMoyenne'];
											}
											
											$reqNoteAnnee = "SELECT ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
													FROM soda_surveillance 
													WHERE Suppr=0 
													AND AutoSurveillance=0
													AND Etat='Clôturé'
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) NOT IN (9,12,13,14)
													AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
													AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
													AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
													AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
													";
		
											$resultNoteAnnee=mysqli_query($bdd,$reqNoteAnnee);
											$nbNoteAnnee=mysqli_num_rows($resultNoteAnnee);
											$noteMoyenneAnnee=0;
											if($nbNoteAnnee>0){
												$rowNoteAnnee=mysqli_fetch_array($resultNoteAnnee);
												$noteMoyenneAnnee=$rowNoteAnnee['NoteMoyenne'];
											}
											
											$reqConforme = "SELECT ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100,1) AS NoteMoyenne,
													(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Seuil
													FROM soda_surveillance 
													WHERE Suppr=0 
													AND AutoSurveillance=0
													AND Etat='Clôturé'
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) NOT IN (9,12,13,14)
													AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
													AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
													AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
													";
											$resultConforme=mysqli_query($bdd,$reqConforme);
											$nConforme=mysqli_num_rows($resultConforme);
											$reussite=0;
											$echec=0;
											if($nConforme>0){
												while($rowConforme=mysqli_fetch_array($resultConforme)){
													if($rowConforme['NoteMoyenne']<>"" && $rowConforme['NoteMoyenne']<$rowConforme['Seuil']){$echec++;}
													else{$reussite++;}
												}
												$reussite=round(($reussite/$nConforme)*100,1);
												$echec=round(($echec/$nConforme)*100,1);
											}
											else{
												$reussite=null;
												$echec=null;
											}
											
											$reqConformeAnnee = "SELECT ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100,1) AS NoteMoyenne,
													(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Seuil
													FROM soda_surveillance 
													WHERE Suppr=0 
													AND AutoSurveillance=0
													AND Etat='Clôturé'
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
													AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) NOT IN (9,12,13,14)
													AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
													AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
													AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
													AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
													";
											$resultConformeAnnee=mysqli_query($bdd,$reqConformeAnnee);
											$nConformeAnnee=mysqli_num_rows($resultConformeAnnee);
											$reussiteAnnee=0;
											$echecAnnee=0;
											if($nConformeAnnee>0){
												while($rowConforme=mysqli_fetch_array($resultConformeAnnee)){
													if($rowConforme['NoteMoyenne']<>"" && $rowConforme['NoteMoyenne']<$rowConforme['Seuil']){$echecAnnee++;}
													else{$reussiteAnnee++;}
												}
												$reussiteAnnee=round(($reussiteAnnee/$nConformeAnnee)*100,1);
												$echecAnnee=round(($echecAnnee/$nConformeAnnee)*100,1);
											}
											else{
												$reussiteAnnee=null;
												$echecAnnee=null;
											}
											
											$result2=mysqli_query($bdd,$req);
											$nbCloture=mysqli_num_rows($result2);
											
											$result2Annee=mysqli_query($bdd,$reqAnnee);
											$nbClotureAnnee=mysqli_num_rows($result2Annee);

											$resultVolume=mysqli_query($bdd,$reqVol);
											$nbVolume=mysqli_num_rows($resultVolume);
											
											$nbRetard=0;
											$nbPlanif=0;
											$nbVolume2=0;
											$nbVolumeTotal=0;
											$nbAdherence=0;
											$nbAdherenceCorrige=0;
											$nbRealiseEnRetard=0;
											
											$nbRetardAnnee=0;
											$nbPlanifAnnee=0;
											$nbVolume2Annee=0;
											$nbVolumeTotalAnnee=0;
											$nbAdherenceAnnee=0;
											$nbAdherenceCorrigeAnnee=0;
											$nbRealiseEnRetardAnnee=0;
											
											if ($nbVolume>0){
												while($rowSurveillance=mysqli_fetch_array($resultVolume)) {
													$lasemaine=$rowSurveillance['Annee']."S";
													if($rowSurveillance['Semaine']<10){$lasemaine.="0".$rowSurveillance['Semaine'];}else{$lasemaine.=$rowSurveillance['Semaine'];}
													$lasemainev2=$rowSurveillance['Annee']."_";
													if($rowSurveillance['Semaine']<10){$lasemainev2.="0".$rowSurveillance['Semaine'];}else{$lasemainev2.=$rowSurveillance['Semaine'];}
													
													$leMoisAnnee= anneeMois($rowSurveillance['Annee'],$rowSurveillance['Semaine']);
													$volume=$rowSurveillance['VolTotal'];
													
													if($semaine2>=$lasemaine){
														if(strpos($anneeSemaine2, $lasemainev2) !== false){
															$nbRetard+=$volume;
															$nbVolume2+=$rowSurveillance['VolumeCloture'];
															$nbVolumeTotal+=$rowSurveillance['VolTotal'];
														}
														$nbRetardAnnee+=$volume;
														$nbVolume2Annee+=$rowSurveillance['VolumeClotureAnnee'];
														$nbVolumeTotalAnnee+=$rowSurveillance['VolTotal'];
													}
													else{
														$nbPlanif+=$volume;
													}
												}
											}
											$total=$nbVolumeTotal;
											$nbRealiseSurPlanifie=$nbVolume2;
											
											$resultRealiseSansPlanif=mysqli_query($bdd,$reqRealiseSansPlanif);
											$nbRealiseSansPlanif=mysqli_num_rows($resultRealiseSansPlanif);
											
											$resultRealiseSansPlanifAnnee=mysqli_query($bdd,$reqRealiseSansPlanifAnnee);
											$nbRealiseSansPlanifAnnee=mysqli_num_rows($resultRealiseSansPlanifAnnee);
											
											if($total>0){
												$nbRetard=round(($nbRetard/$total)*100,1);
												$nbAdherence=round(($nbRealiseSurPlanifie/$total)*100,0);
												$nbAdherenceCorrige=round((($nbRealiseSurPlanifie+$nbRealiseSansPlanif)/$total)*100,0);
											}
											
											$totalAnnee=$nbVolumeTotalAnnee;
											$nbRealiseSurPlanifieAnnee=$nbVolume2Annee;
											if($totalAnnee>0){
												$nbRetardAnnee=round(($nbRetardAnnee/$totalAnnee)*100,1);
												$nbAdherenceAnnee=round(($nbRealiseSurPlanifieAnnee/$totalAnnee)*100,0);
												$nbAdherenceCorrigeAnnee=round((($nbRealiseSurPlanifieAnnee+$nbRealiseSansPlanifAnnee)/$totalAnnee)*100,0);
											}
											
											
											
											?>
											<tr style="background-color:#8783b2">
												<td class="Libelle">Sous Total</td>
												<td class="Libelle" style="text-align:center;"><?php echo $total;?></td>
												<td class="Libelle" style="text-align:center;"><?php echo $nbRealiseSurPlanifie;?></td>
												<td class="Libelle" style="text-align:center;"><?php if($total>0){echo $nbAdherence."%";}else{echo "N/A";}?></td>
												<td class="Libelle" style="text-align:center;"><?php echo $nbRealiseSansPlanif;?></td>
												<td class="Libelle" style="text-align:center;"><?php if($total>0){echo $nbAdherenceCorrige."%";}else{echo "N/A";}?></td>
												<td class="Libelle" style="text-align:center;"><?php if($nConforme>0){echo $reussite." %";}else{echo "N/A";} ?></td>
												<td class="Libelle" style="text-align:center;border-right:4px double #1e3a84;"><?php if($nbCloture>0){echo $noteMoyenne." %";}else{echo "N/A";} ?></td>
	
												<td class="Libelle" style="text-align:center;"><?php echo $totalAnnee;?></td>
												<td class="Libelle" style="text-align:center;"><?php echo $nbRealiseSurPlanifieAnnee;?></td>
												<td class="Libelle" style="text-align:center;"><?php if($totalAnnee>0){echo $nbAdherenceAnnee."%";}else{echo "N/A";}?></td>
												<td class="Libelle" style="text-align:center;"><?php echo $nbRealiseSansPlanifAnnee;?></td>
												<td class="Libelle" style="text-align:center;"><?php if($totalAnnee>0){echo $nbAdherenceCorrigeAnnee."%";}else{echo "N/A";}?></td>
												<td class="Libelle" style="text-align:center;"><?php if($nConformeAnnee>0){echo $reussiteAnnee." %";}else{echo "N/A";} ?></td>
												<td class="Libelle" style="text-align:center;"><?php if($nbClotureAnnee>0){echo $noteMoyenneAnnee." %";}else{echo "N/A";} ?></td>
											</tr>
											<?php

											//HSE & PERFO INDUSTRIELLE
											$req = "SELECT Id, Libelle AS Intitule
												FROM soda_theme 
												WHERE Id IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
												AND Id IN (9,12,13,14)
												ORDER BY Libelle
												";
												
											$result=mysqli_query($bdd,$req);
											$nbDonnees=mysqli_num_rows($result);
											
											if($nbDonnees>0){
												while($row2=mysqli_fetch_array($result)){
													$intitule2=$row2['Intitule'];
													//NbCloturé
													$req = "SELECT Id,NumActionTracker
															FROM soda_surveillance 
															WHERE Suppr=0 
															AND AutoSurveillance=0
															AND Etat='Clôturé'
															AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
															AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
															AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
															AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
															";
													$reqAnnee = "SELECT Id,NumActionTracker
															FROM soda_surveillance 
															WHERE Suppr=0 
															AND AutoSurveillance=0
															AND Etat='Clôturé'
															AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
															AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
															AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
															AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
															AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
															";
													
													$reqRealiseSansPlanif = "SELECT Id
														FROM soda_surveillance 
														WHERE Suppr=0 
														AND AutoSurveillance=0
														AND Etat='Clôturé'
														AND (Id_PlannifManuelle=0 OR (SELECT COUNT(Id) FROM soda_plannifmanuelle WHERE Id=Id_PlannifManuelle AND Suppr=0 AND Volume>0)=0)
														AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
														";
													$reqRealiseSansPlanifAnnee = "SELECT Id
														FROM soda_surveillance 
														WHERE Suppr=0 
														AND AutoSurveillance=0
														AND Etat='Clôturé'
														AND (Id_PlannifManuelle=0 OR (SELECT COUNT(Id) FROM soda_plannifmanuelle WHERE Id=Id_PlannifManuelle AND Suppr=0 AND Volume>0)=0)
														AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
														AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
														";
													
													$reqVol="SELECT Annee,Semaine,Id_Questionnaire,Id_Prestation,
														Volume AS VolTotal,
														(SELECT COUNT(soda_surveillance.Id) 
														FROM soda_surveillance 
														WHERE soda_surveillance.Suppr=0 
														AND AutoSurveillance=0 
														AND soda_plannifmanuelle.Id_Questionnaire=soda_surveillance.Id_Questionnaire
														AND Etat='Clôturé'
														AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.") 
														AND soda_surveillance.Id_Prestation=soda_plannifmanuelle.Id_Prestation 
														AND Id_PlannifManuelle=soda_plannifmanuelle.Id) AS VolumeCloture,
														(SELECT COUNT(soda_surveillance.Id) 
														FROM soda_surveillance 
														WHERE soda_surveillance.Suppr=0 
														AND AutoSurveillance=0 
														AND soda_plannifmanuelle.Id_Questionnaire=soda_surveillance.Id_Questionnaire
														AND Etat='Clôturé'
														AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
														AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
														AND soda_surveillance.Id_Prestation=soda_plannifmanuelle.Id_Prestation 
														AND Id_PlannifManuelle=soda_plannifmanuelle.Id) AS VolumeClotureAnnee
														FROM soda_plannifmanuelle 
														WHERE Suppr=0 
														AND Annee=".date("Y",strtotime($dateFin." +0 month"))."
														AND CONCAT(Annee,'_',IF(Semaine<10,CONCAT(0,Semaine),Semaine)) IN (".$anneeSemaineYTD.")
														AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
														AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
														AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
														ORDER BY Annee,Semaine,Id_Prestation";
													
													$reqNote = "SELECT ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
															FROM soda_surveillance 
															WHERE Suppr=0 
															AND AutoSurveillance=0
															AND Etat='Clôturé'
															AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
															AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
															AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
															AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
															";
													$resultNote=mysqli_query($bdd,$reqNote);
													$nbNote=mysqli_num_rows($resultNote);
													$noteMoyenne=0;
													if($nbNote>0){
														$rowNote=mysqli_fetch_array($resultNote);
														$noteMoyenne=$rowNote['NoteMoyenne'];
													}
													
													$reqNoteAnnee = "SELECT ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
															FROM soda_surveillance 
															WHERE Suppr=0 
															AND AutoSurveillance=0
															AND Etat='Clôturé'
															AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
															AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
															AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
															AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
															AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
															";
													$resultNoteAnnee=mysqli_query($bdd,$reqNoteAnnee);
													$nbNoteAnnee=mysqli_num_rows($resultNoteAnnee);
													$noteMoyenneAnnee=0;
													if($nbNoteAnnee>0){
														$rowNote=mysqli_fetch_array($resultNoteAnnee);
														$noteMoyenneAnnee=$rowNote['NoteMoyenne'];
													}
													
													$reqConforme = "SELECT ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100,1) AS NoteMoyenne,
															(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Seuil
															FROM soda_surveillance 
															WHERE Suppr=0 
															AND AutoSurveillance=0
															AND Etat='Clôturé'
															AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
															AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
															AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
															AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
															";
													$resultConforme=mysqli_query($bdd,$reqConforme);
													$nConforme=mysqli_num_rows($resultConforme);
													$reussite=0;
													$echec=0;
													if($nConforme>0){
														while($rowConforme=mysqli_fetch_array($resultConforme)){
															if($rowConforme['NoteMoyenne']<>"" && $rowConforme['NoteMoyenne']<$rowConforme['Seuil']){$echec++;}
															else{$reussite++;}
														}
														$reussite=round(($reussite/$nConforme)*100,1);
														$echec=round(($echec/$nConforme)*100,1);
													}
													else{
														$reussite=null;
														$echec=null;
													}
													
													$reqConformeAnnee = "SELECT ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100,1) AS NoteMoyenne,
															(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Seuil
															FROM soda_surveillance 
															WHERE Suppr=0 
															AND AutoSurveillance=0
															AND Etat='Clôturé'
															AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
															AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
															AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) = ".$row2['Id']."
															AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
															AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
															";
													$resultConformeAnnee=mysqli_query($bdd,$reqConformeAnnee);
													$nConformeAnnee=mysqli_num_rows($resultConformeAnnee);
													$reussiteAnnee=0;
													$echecAnnee=0;
													if($nConformeAnnee>0){
														while($rowConforme=mysqli_fetch_array($resultConformeAnnee)){
															if($rowConforme['NoteMoyenne']<>"" && $rowConforme['NoteMoyenne']<$rowConforme['Seuil']){$echecAnnee++;}
															else{$reussiteAnnee++;}
														}
														$reussiteAnnee=round(($reussiteAnnee/$nConformeAnnee)*100,1);
														$echecAnnee=round(($echecAnnee/$nConformeAnnee)*100,1);
													}
													else{
														$reussiteAnnee=null;
														$echecAnnee=null;
													}
													
													$result2=mysqli_query($bdd,$req);
													$nbCloture=mysqli_num_rows($result2);
													
													$result2Annee=mysqli_query($bdd,$reqAnnee);
													$nbClotureAnnee=mysqli_num_rows($result2Annee);

													$resultVolume=mysqli_query($bdd,$reqVol);
													$nbVolume=mysqli_num_rows($resultVolume);

													$nbPlanif=0;
													$nbVolume2=0;
													$nbAdherence=0;
													$nbAdherenceCorrige=0;
													$nbVolumeTotal=0;
													$nbRealiseEnRetard=0;
													
													$nbPlanifAnnee=0;
													$nbVolume2Annee=0;
													$nbAdherenceAnnee=0;
													$nbAdherenceCorrigeAnnee=0;
													$nbVolumeTotalAnnee=0;
													$nbRealiseEnRetardAnnee=0;
													if ($nbVolume>0){
														while($rowSurveillance=mysqli_fetch_array($resultVolume)) {
															$lasemaine=$rowSurveillance['Annee']."S";
															if($rowSurveillance['Semaine']<10){$lasemaine.="0".$rowSurveillance['Semaine'];}else{$lasemaine.=$rowSurveillance['Semaine'];}
															$lasemainev2=$rowSurveillance['Annee']."_";
															if($rowSurveillance['Semaine']<10){$lasemainev2.="0".$rowSurveillance['Semaine'];}else{$lasemainev2.=$rowSurveillance['Semaine'];}
															
															$leMoisAnnee= anneeMois($rowSurveillance['Annee'],$rowSurveillance['Semaine']);
															$volume=$rowSurveillance['VolTotal'];

															if($semaine2>=$lasemaine){
																if(strpos($anneeSemaine2, $lasemainev2) !== false){
																	$nbVolume2+=$rowSurveillance['VolumeCloture'];
																	$nbVolumeTotal+=$rowSurveillance['VolTotal'];
																}
																$nbVolume2Annee+=$rowSurveillance['VolumeClotureAnnee'];
																$nbVolumeTotalAnnee+=$rowSurveillance['VolTotal'];
															}
															else{
																$nbPlanif+=$volume;
															}
														}
													}
													
													$total=$nbVolumeTotal;
													$nbRealiseSurPlanifie=$nbVolume2;
													
													$resultRealiseSansPlanif=mysqli_query($bdd,$reqRealiseSansPlanif);
													$nbRealiseSansPlanif=mysqli_num_rows($resultRealiseSansPlanif);
													
													$resultRealiseSansPlanifAnnee=mysqli_query($bdd,$reqRealiseSansPlanifAnnee);
													$nbRealiseSansPlanifAnnee=mysqli_num_rows($resultRealiseSansPlanifAnnee);
													
													if($total>0){
														$nbAdherence=round(($nbRealiseSurPlanifie/$total)*100,0);
														$nbAdherenceCorrige=round((($nbRealiseSurPlanifie+$nbRealiseSansPlanif)/$total)*100,0);
													}
													
													$totalAnnee=$nbVolumeTotalAnnee;
													$nbRealiseSurPlanifieAnnee=$nbVolume2Annee;
													if($totalAnnee>0){
														$nbAdherenceAnnee=round(($nbRealiseSurPlanifieAnnee/$totalAnnee)*100,0);
														$nbAdherenceCorrigeAnnee=round((($nbRealiseSurPlanifieAnnee+$nbRealiseSansPlanifAnnee)/$totalAnnee)*100,0);
													}
													
													?>
													<tr style="background-color:<?php echo $couleur;?>">
														<td><?php echo $intitule2;?></td>
														<td style="text-align:center;"><?php echo $total;?></td>
														<td style="text-align:center;"><?php echo $nbRealiseSurPlanifie;?></td>
														<td style="text-align:center;"><?php if($total>0){echo $nbAdherence."%";}else{echo "N/A";}?></td>
														<td style="text-align:center;"><?php echo $nbRealiseSansPlanif;?></td>
														<td style="text-align:center;"><?php if($total>0){echo $nbAdherenceCorrige."%";}else{echo "N/A";}?></td>
														<td style="text-align:center;"><?php if($nConforme>0){echo $reussite." %";}else{echo "N/A";} ?></td>
														<td style="text-align:center;border-right:4px double #1e3a84;"><?php if($nbCloture>0){echo $noteMoyenne." %";}else{echo "N/A";} ?></td>

														<td style="text-align:center;"><?php echo $totalAnnee;?></td>
														<td style="text-align:center;"><?php echo $nbRealiseSurPlanifieAnnee;?></td>
														<td style="text-align:center;"><?php if($totalAnnee>0){echo $nbAdherenceAnnee."%";}else{echo "N/A";}?></td>
														<td style="text-align:center;"><?php echo $nbRealiseSansPlanifAnnee;?></td>
														<td style="text-align:center;"><?php if($totalAnnee>0){echo $nbAdherenceCorrigeAnnee."%";}else{echo "N/A";}?></td>
							
														<td style="text-align:center;"><?php if($nConformeAnnee>0){echo $reussiteAnnee." %";}else{echo "N/A";} ?></td>
														<td style="text-align:center;"><?php if($nbClotureAnnee>0){echo $noteMoyenneAnnee." %";}else{echo "N/A";} ?></td>
													</tr>
													<?php
													if($couleur=="#ffffff"){$couleur="#b7dfe3";}
													else{$couleur="#ffffff";}
												}
											}

										}
										
										//CALCUL TOTAL GENERAL 
										
										//NbCloturé
										$req = "SELECT Id,NumActionTracker
												FROM soda_surveillance 
												WHERE Suppr=0 
												AND AutoSurveillance=0
												AND Etat='Clôturé'
												AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
												AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
												AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
												";
												
										$reqAnnee = "SELECT Id,NumActionTracker
												FROM soda_surveillance 
												WHERE Suppr=0 
												AND AutoSurveillance=0
												AND Etat='Clôturé'
												AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
												AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
												AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
												AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
												";
										
										$reqRealiseSansPlanif = "SELECT Id
											FROM soda_surveillance 
											WHERE Suppr=0 
											AND AutoSurveillance=0
											AND Etat='Clôturé'
											AND (Id_PlannifManuelle=0 OR (SELECT COUNT(Id) FROM soda_plannifmanuelle WHERE Id=Id_PlannifManuelle AND Suppr=0 AND Volume>0)=0)
											AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
											AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
											AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
											AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
											";
										$reqRealiseSansPlanifAnnee = "SELECT Id
											FROM soda_surveillance 
											WHERE Suppr=0 
											AND AutoSurveillance=0
											AND Etat='Clôturé'
											AND (Id_PlannifManuelle=0 OR (SELECT COUNT(Id) FROM soda_plannifmanuelle WHERE Id=Id_PlannifManuelle AND Suppr=0 AND Volume>0)=0)
											AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
											AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
											AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
											AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
											AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
											";
										
										$reqVol="SELECT Annee,Semaine,Id_Questionnaire,Id_Prestation,
											Volume AS VolTotal,
											(SELECT COUNT(soda_surveillance.Id) 
											FROM soda_surveillance 
											WHERE soda_surveillance.Suppr=0 
											AND AutoSurveillance=0 
											AND soda_plannifmanuelle.Id_Questionnaire=soda_surveillance.Id_Questionnaire
											AND Etat='Clôturé'
											AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.") 
											AND soda_surveillance.Id_Prestation=soda_plannifmanuelle.Id_Prestation 
											AND Id_PlannifManuelle=soda_plannifmanuelle.Id) AS VolumeCloture,
											(SELECT COUNT(soda_surveillance.Id) 
											FROM soda_surveillance 
											WHERE soda_surveillance.Suppr=0 
											AND AutoSurveillance=0 
											AND soda_plannifmanuelle.Id_Questionnaire=soda_surveillance.Id_Questionnaire
											AND Etat='Clôturé'
											AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
											AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
											AND soda_surveillance.Id_Prestation=soda_plannifmanuelle.Id_Prestation 
											AND Id_PlannifManuelle=soda_plannifmanuelle.Id) AS VolumeClotureAnnee
											FROM soda_plannifmanuelle 
											WHERE Suppr=0 
											AND Annee=".date("Y",strtotime($dateFin." +0 month"))."
											AND CONCAT(Annee,'_',IF(Semaine<10,CONCAT(0,Semaine),Semaine)) IN (".$anneeSemaineYTD.")
											AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
											AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
											AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
											ORDER BY Annee,Semaine,Id_Prestation";

										$reqNote = "SELECT ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
												FROM soda_surveillance 
												WHERE Suppr=0 
												AND AutoSurveillance=0
												AND Etat='Clôturé'
												AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
												AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
												AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
												";
										$resultNote=mysqli_query($bdd,$reqNote);
										$nbNote=mysqli_num_rows($resultNote);
										$noteMoyenne=0;
										if($nbNote>0){
											$rowNote=mysqli_fetch_array($resultNote);
											$noteMoyenne=$rowNote['NoteMoyenne'];
										}
												
										$reqNoteAnnee = "SELECT ROUND(AVG(ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100)),1) AS NoteMoyenne
												FROM soda_surveillance 
												WHERE Suppr=0 
												AND AutoSurveillance=0
												AND Etat='Clôturé'
												AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
												AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
												AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
												AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
												";
	
										$resultNoteAnnee=mysqli_query($bdd,$reqNoteAnnee);
										$nbNoteAnnee=mysqli_num_rows($resultNoteAnnee);
										$noteMoyenneAnnee=0;
										if($nbNoteAnnee>0){
											$rowNote=mysqli_fetch_array($resultNoteAnnee);
											$noteMoyenneAnnee=$rowNote['NoteMoyenne'];
										}
										
										$reqConforme = "SELECT ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100,1) AS NoteMoyenne,
												(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Seuil
												FROM soda_surveillance 
												WHERE Suppr=0 
												AND AutoSurveillance=0
												AND Etat='Clôturé'
												AND CONCAT(YEAR(DateSurveillance),'_',IF(WEEK(DateSurveillance,1)<10,CONCAT(0,WEEK(DateSurveillance,1)),WEEK(DateSurveillance,1))) IN (".$anneeSemaine2.")
												AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
												AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
												";
										$resultConforme=mysqli_query($bdd,$reqConforme);
										$nConforme=mysqli_num_rows($resultConforme);
										$reussite=0;
										$echec=0;
										if($nConforme>0){
											while($rowConforme=mysqli_fetch_array($resultConforme)){
												if($rowConforme['NoteMoyenne']<>"" && $rowConforme['NoteMoyenne']<$rowConforme['Seuil']){$echec++;}
												else{$reussite++;}
											}
											$reussite=round(($reussite/$nConforme)*100,1);
											$echec=round(($echec/$nConforme)*100,1);
										}
										else{
											$reussite=null;
											$echec=null;
										}
										
										$reqConformeAnnee = "SELECT ROUND(((SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat='C')/(SELECT SUM(Ponderation) FROM soda_surveillance_question WHERE Id_Surveillance=soda_surveillance.Id AND Etat IN ('C','NC')))*100,1) AS NoteMoyenne,
												(SELECT SeuilReussite FROM soda_questionnaire WHERE Id=Id_Questionnaire) AS Seuil
												FROM soda_surveillance 
												WHERE Suppr=0 
												AND AutoSurveillance=0
												AND Etat='Clôturé'
												AND YEAR(DateSurveillance)=".date("Y", strtotime($dateFin." +0 month"))."
												AND MONTH(DateSurveillance)<=".date("m", strtotime($dateFin." +0 month"))."
												AND (SELECT Id_Theme FROM soda_questionnaire WHERE Id=Id_Questionnaire) IN (".$_SESSION['FiltreSODATDBOperation_Theme'].")
												AND Id_Prestation IN (".$_SESSION['FiltreSODATDBOperation_Prestation'].")
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$_SESSION['FiltreSODATDBOperation_UER'].")
												";
										$resultConformeAnnee=mysqli_query($bdd,$reqConformeAnnee);
										$nConformeAnnee=mysqli_num_rows($resultConformeAnnee);
										$reussiteAnnee=0;
										$echecAnnee=0;
										if($nConformeAnnee>0){
											while($rowConforme=mysqli_fetch_array($resultConformeAnnee)){
												if($rowConforme['NoteMoyenne']<>"" && $rowConforme['NoteMoyenne']<$rowConforme['Seuil']){$echecAnnee++;}
												else{$reussiteAnnee++;}
											}
											$reussiteAnnee=round(($reussiteAnnee/$nConformeAnnee)*100,1);
											$echecAnnee=round(($echecAnnee/$nConformeAnnee)*100,1);
										}
										else{
											$reussiteAnnee=null;
											$echecAnnee=null;
										}
										
										$result2=mysqli_query($bdd,$req);
										$nbCloture=mysqli_num_rows($result2);
										
										$result2Annee=mysqli_query($bdd,$reqAnnee);
										$nbClotureAnnee=mysqli_num_rows($result2Annee);
	
										
										$resultVolume=mysqli_query($bdd,$reqVol);
										$nbVolume=mysqli_num_rows($resultVolume);
										
										$nbPlanif=0;
										$nbVolume2=0;
										$nbVolumeTotal=0;
										$nbAdherence=0;
										$nbAdherenceCorrige=0;
										$nbRealiseEnRetard=0;
										
										$nbPlanifAnnee=0;
										$nbVolume2Annee=0;
										$nbVolumeTotalAnnee=0;
										$nbAdherenceAnnee=0;
										$nbAdherenceCorrigeAnnee=0;
										$nbRealiseEnRetardAnnee=0;
										if ($nbVolume>0){
											while($rowSurveillance=mysqli_fetch_array($resultVolume)) {
												$lasemaine=$rowSurveillance['Annee']."S";
												if($rowSurveillance['Semaine']<10){$lasemaine.="0".$rowSurveillance['Semaine'];}else{$lasemaine.=$rowSurveillance['Semaine'];}
												$lasemainev2=$rowSurveillance['Annee']."_";
												if($rowSurveillance['Semaine']<10){$lasemainev2.="0".$rowSurveillance['Semaine'];}else{$lasemainev2.=$rowSurveillance['Semaine'];}
												$leMoisAnnee= anneeMois($rowSurveillance['Annee'],$rowSurveillance['Semaine']);
												
												$volume=$rowSurveillance['VolTotal'];

												if($semaine2>=$lasemaine){
													if(strpos($anneeSemaine2, $lasemainev2) !== false){
														$nbVolume2+=$rowSurveillance['VolumeCloture'];
														$nbVolumeTotal+=$rowSurveillance['VolTotal'];
													}
													$nbVolume2Annee+=$rowSurveillance['VolumeClotureAnnee'];
													$nbVolumeTotalAnnee+=$rowSurveillance['VolTotal'];
												}
												else{
													$nbPlanif+=$volume;
												}
											}
										}
										
										$total=$nbVolumeTotal;
										$nbRealiseSurPlanifie=$nbVolume2;
										
										$resultRealiseSansPlanif=mysqli_query($bdd,$reqRealiseSansPlanif);
										$nbRealiseSansPlanif=mysqli_num_rows($resultRealiseSansPlanif);
										
										$resultRealiseSansPlanifAnnee=mysqli_query($bdd,$reqRealiseSansPlanifAnnee);
										$nbRealiseSansPlanifAnnee=mysqli_num_rows($resultRealiseSansPlanifAnnee);
										
										if($total>0){
											$nbAdherence=round(($nbRealiseSurPlanifie/$total)*100,0);
											$nbAdherenceCorrige=round((($nbRealiseSurPlanifie+$nbRealiseSansPlanif)/$total)*100,0);
										}
										
										$totalAnnee=$nbVolumeTotalAnnee;
										$nbRealiseSurPlanifieAnnee=$nbVolume2Annee;
										if($totalAnnee>0){
											$nbAdherenceAnnee=round(($nbRealiseSurPlanifieAnnee/$totalAnnee)*100,0);
											$nbAdherenceCorrigeAnnee=round((($nbRealiseSurPlanifieAnnee+$nbRealiseSansPlanifAnnee)/$totalAnnee)*100,0);
										}
										
										
										
										?>
										<tr style="background-color:#8783b2">
											<td class="Libelle">Total</td>
											<td class="Libelle" style="text-align:center;"><?php echo $total;?></td>
											<td class="Libelle" style="text-align:center;"><?php echo $nbRealiseSurPlanifie;?></td>
											<td class="Libelle" style="text-align:center;"><?php if($total>0){echo $nbAdherence."%";}else{echo "N/A";}?></td>
											<td class="Libelle" style="text-align:center;"><?php echo $nbRealiseSansPlanif;?></td>
											<td class="Libelle" style="text-align:center;"><?php if($total>0){echo $nbAdherenceCorrige."%";}else{echo "N/A";}?></td>
											<td class="Libelle" style="text-align:center;"><?php if($nConforme>0){echo $reussite." %";}else{echo "N/A";} ?></td>
											<td class="Libelle" style="text-align:center;border-right:4px double #1e3a84;"><?php if($nbCloture>0){echo $noteMoyenne." %";}else{echo "N/A";} ?></td>
											
											<td class="Libelle" style="text-align:center;"><?php echo $totalAnnee;?></td>
											<td class="Libelle" style="text-align:center;"><?php echo $nbRealiseSurPlanifieAnnee;?></td>
											<td class="Libelle" style="text-align:center;"><?php if($totalAnnee>0){echo $nbAdherenceAnnee."%";}else{echo "N/A";}?></td>
											<td class="Libelle" style="text-align:center;"><?php echo $nbRealiseSansPlanifAnnee;?></td>
											<td class="Libelle" style="text-align:center;"><?php if($totalAnnee>0){echo $nbAdherenceCorrigeAnnee."%";}else{echo "N/A";}?></td>
											
											<td class="Libelle" style="text-align:center;"><?php if($nConformeAnnee>0){echo $reussiteAnnee." %";}else{echo "N/A";} ?></td>
											<td class="Libelle" style="text-align:center;"><?php if($nbClotureAnnee>0){echo $noteMoyenneAnnee." %";}else{echo "N/A";} ?></td>
										</tr>
										<?php
									}
									
									?>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<?php 
				}
			}
			?>
		</table>

	</td>
</tr>
<tr><td height="150"></td></tr>
</table>	
<script>
  function GenererPlanning() {
	// Get the element.
	var element = document.getElementById('divplanning');

	// Generate the PDF.
	html2pdf().from(element).set({
	  margin: 1,
	  filename: 'test.pdf',
	  html2canvas: { scale: 2 },
	  jsPDF: {orientation: 'portrait', unit: 'in', format: 'letter', compressPDF: true}
	}).save();
  }
</script>
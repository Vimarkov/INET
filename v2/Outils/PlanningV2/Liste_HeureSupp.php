<?php
require("../../Menu.php");
?>
<script type="text/javascript" src="DemandeHSListe.js"></script>
<script language="javascript">
	function OuvreFenetreModif(Menu,Id)
		{var w=window.open("Modif_HS.php?Mode=M&Id="+Id+"&Menu="+Menu+"&TDB="+document.getElementById('TDB').value+"&OngletTDB="+document.getElementById('OngletTDB').value,"PageHS","status=no,menubar=no,width=1000,height=700");
		w.focus();
		}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

if(isset($_GET['Tri'])){
	$tab = array("Id","Personne","Prestation","Pole","Date1","Demandeur","DateHS","Nb_Heures_Jour","Nb_Heures_Nuit","Etat","DatePriseEnCompteRH","Contrat","TempsTravail");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriRHHS_General']= str_replace($tri." ASC,","",$_SESSION['TriRHHS_General']);
			$_SESSION['TriRHHS_General']= str_replace($tri." DESC,","",$_SESSION['TriRHHS_General']);
			$_SESSION['TriRHHS_General']= str_replace($tri." ASC","",$_SESSION['TriRHHS_General']);
			$_SESSION['TriRHHS_General']= str_replace($tri." DESC","",$_SESSION['TriRHHS_General']);
			if($_SESSION['TriRHHS_'.$tri]==""){$_SESSION['TriRHHS_'.$tri]="ASC";$_SESSION['TriRHHS_General'].= $tri." ".$_SESSION['TriRHHS_'.$tri].",";}
			elseif($_SESSION['TriRHHS_'.$tri]=="ASC"){$_SESSION['TriRHHS_'.$tri]="DESC";$_SESSION['TriRHHS_General'].= $tri." ".$_SESSION['TriRHHS_'.$tri].",";}
			else{$_SESSION['TriRHHS_'.$tri]="";}
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

<form class="test" action="Liste_HeureSupp.php" method="post">
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
	<?php
		if($Menu==3 || $Menu==4){
	?>
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
						if($_SESSION["Langue"]=="FR"){Titre1("HEURES SUPPLEMENTAIRES EN COURS","Outils/PlanningV2/Liste_HeureSupp.php?Menu=".$Menu.$ParametreTDB,true);}
						else{Titre1("ADDITIONAL HOURS IN PROGRESS","Outils/PlanningV2/Liste_HeureSupp.php?Menu=".$Menu."",true);}

						if($_SESSION["Langue"]=="FR"){Titre1("HISTORIQUE","Outils/PlanningV2/Liste_HeureSuppHistorique.php?Menu=".$Menu.$ParametreTDB,false);}
						else{Titre1("HISTORICAL","Outils/PlanningV2/Liste_HeureSuppHistorique.php?Menu=".$Menu."",false);}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<?php
		}
	?>
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
				
				$PrestationSelect=$_SESSION['FiltreRHHS_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestations'];}
				$_SESSION['FiltreRHHS_Prestation']=$PrestationSelect;	
				
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
				
				$PoleSelect=$_SESSION['FiltreRHHS_Pole'];
				if($_POST){$PoleSelect=$_POST['pole'];}
				$_SESSION['FiltreRHHS_Pole']=$PoleSelect;
				
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
						$mois=$_SESSION['FiltreRHHS_Mois'];
						if($_POST){$mois=$_POST['mois'];}
						$_SESSION['FiltreRHHS_Mois']=$mois;
						
						for($i=0;$i<=11;$i++){
							echo "<option value='".($i+1)."'";
							if($mois== ($i+1)){echo " selected ";}
							echo ">".$arrayMois[$i]."</option>\n";
						}
						
						$annee=$_SESSION['FiltreRHHS_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						if($annee==""){$annee=date("Y");}
						$_SESSION['FiltreRHHS_Annee']=$annee;
					?>
				</select>
				<?php
					$MoisCumules=$_SESSION['FiltreRHHS_MoisCumules'];
					if($_POST){
						if(isset($_POST['MoisCumules'])){$MoisCumules="checked";}else{$MoisCumules="";}				
					}
					$_SESSION['FiltreRHHS_MoisCumules']=$MoisCumules;
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
			<td width="5%">
				&nbsp;&nbsp;&nbsp;
				<a href="javascript:OuvreFenetreExcel('<?php echo $Menu; ?>')">
				<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
				</a>
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
						
						$personne=$_SESSION['FiltreRHHS_Personne'];
						if($_POST){$personne=$_POST['personne'];}
						$_SESSION['FiltreRHHS_Personne']= $personne;
						
						while($rowPersonne=mysqli_fetch_array($resultPersonne))
						{
							echo "<option value='".$rowPersonne['Id']."'";
							if ($personne == $rowPersonne['Id']){echo " selected ";}
							echo ">".$rowPersonne['Personne']."</option>\n";
						}
					?>
				</select>
			</td>
			<td colspan="6" width="25%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Etat :";}else{echo "State :";} ?>
					<?php
						$RH="";
						if($Menu==4){
							$RH="RH";	
						}
						
						$EnCours=$_SESSION['FiltreRHHS'.$RH.'_EtatEnCours'];
						$TransmisRH=$_SESSION['FiltreRHHS'.$RH.'_EtatTransmiRH'];
						$Validee=$_SESSION['FiltreRHHS'.$RH.'_EtatValide'];
						$Refusee=$_SESSION['FiltreRHHS'.$RH.'_EtatRefuse'];
						$Supprimee=$_SESSION['FiltreRHHS'.$RH.'_EtatSupprime'];
						if($_POST){
							if(isset($_POST['EnCours'])){$EnCours="checked";}else{$EnCours="";}
							if(isset($_POST['TransmisRH'])){$TransmisRH="checked";}else{$TransmisRH="";}
							if(isset($_POST['Validee'])){$Validee="checked";}else{$Validee="";}
							if(isset($_POST['Refusee'])){$Refusee="checked";}else{$Refusee="";}
							if(isset($_POST['Supprimee'])){$Supprimee="checked";}else{$Supprimee="";}
						}
						$_SESSION['FiltreRHHS'.$RH.'_EtatEnCours']=$EnCours;
						$_SESSION['FiltreRHHS'.$RH.'_EtatTransmiRH']=$TransmisRH;
						$_SESSION['FiltreRHHS'.$RH.'_EtatValide']=$Validee;
						$_SESSION['FiltreRHHS'.$RH.'_EtatRefuse']=$Refusee;
						$_SESSION['FiltreRHHS'.$RH.'_EtatSupprime']=$Supprimee;
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
		<tr>
			<td valign="top" colspan="8" class="Libelle" <?php if($Menu<>4){echo "style='display:none;'";} ?>>
				<?php if($LangueAffichage=="FR"){echo "Responsable Projet";}else{echo "Project manager";}?> :<br>
						<?php
						
							$Id_RespProjet=$_SESSION['FiltreRHHS_RespProjet'];
							if($_POST){
								$Id_RespProjet="";
								if(isset($_POST['Id_RespProjet'])){
									if (is_array($_POST['Id_RespProjet'])) {
										foreach($_POST['Id_RespProjet'] as $value){
											if($Id_RespProjet<>''){$Id_RespProjet.=",";}
										  $Id_RespProjet.=$value;
										}
									} else {
										$value = $_POST['Id_RespProjet'];
										$Id_RespProjet = $value;
									}
								}
							}
							$_SESSION['FiltreRHHS_RespProjet']=$Id_RespProjet;
	
							$rqRespProjet="SELECT DISTINCT Id_Personne,
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
							FROM new_competences_personne_poste_prestation 
							LEFT JOIN new_competences_prestation
							ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
							WHERE Id_Poste IN (".$IdPosteResponsableProjet.")
							AND Id_Plateforme IN (
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
							)
							AND Id_Personne<>0
							ORDER BY Personne";
							
							$resultRespProjet=mysqli_query($bdd,$rqRespProjet);
							$Id_RespProjet=0;
							while($rowRespProjet=mysqli_fetch_array($resultRespProjet))
							{
								$checked="";
								if($_POST){
									$checkboxes = isset($_POST['Id_RespProjet']) ? $_POST['Id_RespProjet'] : array();
									foreach($checkboxes as $value) {
										if($rowRespProjet['Id_Personne']==$value){$checked="checked";}
									}
								}
								else{
									$checkboxes = explode(',',$_SESSION['FiltreRHHS_RespProjet']);
									foreach($checkboxes as $value) {
										if($rowRespProjet['Id_Personne']==$value){$checked="checked";}
									}
								}
								echo "<input type='checkbox' class='checkRespProjet' name='Id_RespProjet[]' Id='Id_RespProjet[]' value='".$rowRespProjet['Id_Personne']."' ".$checked.">".$rowRespProjet['Personne'];
							}
						?>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<?php
		if($Menu==4){
			$lemois=$_SESSION['FiltreRHHS_Mois'];
			if($_SESSION['FiltreRHHS_Mois']<10){
				$lemois="0".$_SESSION['FiltreRHHS_Mois'];
			}
			$req="SELECT rh_personne_hs.Id
					FROM rh_personne_hs
					WHERE Suppr=0 AND
					(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_hs.Id_Prestation) IN 
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
					)
					AND rh_personne_hs.Etat4=1
					AND rh_personne_hs.DatePriseEnCompteRH<='0001-01-01'
					AND CONCAT(YEAR(DateHS),'_',IF(MONTH(DateHS)<10,CONCAT('0',MONTH(DateHS)),MONTH(DateHS)))<'".$_SESSION['FiltreRHHS_Annee'].'_'.$lemois."' 
					";
			$result=mysqli_query($bdd,$req);
			$nbResulta=mysqli_num_rows($result);
			if($nbResulta>0){
				echo "<tr>
						<td colspan='4' align='right' style='color:red'><span class='blink_me'><img width='25px' src='../../Images/attention.png'/></span>";
				if($_SESSION["Langue"]=="FR"){
					echo " Il reste des demandes à traiter sur les mois précédents &nbsp;";
				}
				else{
					echo " There are still requests to be addressed in previous months &nbsp;";
				}
				echo "
						</td>
						</tr>";
			}
		}
		
		$requeteAnalyse="SELECT rh_personne_hs.Id ";
		$requete2="SELECT rh_personne_hs.Id, rh_personne_hs.Nb_Heures_Jour,rh_personne_hs.Nb_Heures_Nuit,DateHS,rh_personne_hs.Etat2,rh_personne_hs.Etat3,
			rh_personne_hs.Id_Personne,
			rh_personne_hs.Etat4,DatePriseEnCompteRH,rh_personne_hs.DateRH,rh_personne_hs.Date1,rh_personne_hs.Id_Prestation,rh_personne_hs.Id_Pole,
			IF(
				rh_personne_hs.Etat4=0 AND rh_personne_hs.Etat3<>-1 AND rh_personne_hs.Etat2<>-1,
				1,
				IF(
					rh_personne_hs.DatePriseEnCompteRH<='0001-01-01' AND rh_personne_hs.Etat4=1 AND rh_personne_hs.Etat3=1 AND rh_personne_hs.Etat2=1,
					2,
					IF(
						rh_personne_hs.Etat4=1 AND rh_personne_hs.DatePriseEnCompteRH>'0001-01-01',
						3,
						IF(
							rh_personne_hs.Etat4=-1 OR rh_personne_hs.Etat3=-1 OR rh_personne_hs.Etat2=-1,
							4,
							5
						)
					)
				)
			)
			AS Etat,Commentaire2,Commentaire3,Commentaire4,Suppr,
			(SELECT (SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
			FROM rh_personne_contrat
			WHERE rh_personne_contrat.Suppr=0
			AND rh_personne_contrat.DateDebut<=rh_personne_hs.Date1
			AND (rh_personne_contrat.DateFin>=rh_personne_hs.Date1 OR rh_personne_contrat.DateFin<='0001-01-01')
			AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=rh_personne_hs.Id_Personne
			ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS Contrat,
			(SELECT (SELECT Libelle FROM rh_tempstravail WHERE rh_tempstravail.Id=Id_TempsTravail)
			FROM rh_personne_contrat
			WHERE rh_personne_contrat.Suppr=0
			AND rh_personne_contrat.DateDebut<=rh_personne_hs.Date1
			AND (rh_personne_contrat.DateFin>=rh_personne_hs.Date1 OR rh_personne_contrat.DateFin<='0001-01-01')
			AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=rh_personne_hs.Id_Personne
			ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS TempsTravail,
			(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN2) AS RaisonRefus2,
			(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN3) AS RaisonRefus3,
			(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN4) AS RaisonRefus4,
			(SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_hs.Id_Prestation) AS Prestation, 
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_hs.Id_Personne) AS Personne, 
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_hs.Id_Responsable1) AS Demandeur, 
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
			$requete.="CONCAT(rh_personne_hs.Id_Prestation,'_',rh_personne_hs.Id_Pole) IN 
						(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
						FROM new_competences_personne_poste_prestation 
						WHERE Id_Personne=".$_SESSION["Id_Personne"]."
						AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
						)";
		}
		elseif($Menu==2){
			$requete.="rh_personne_hs.Id_Personne=".$_SESSION['Id_Personne']." ";
		}
		if($Menu==4){
			if($_SESSION['FiltreRHHS_RespProjet']<>""){
				$requete.="AND CONCAT(rh_personne_hs.Id_Prestation,'_',rh_personne_hs.Id_Pole) 
							IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
								FROM new_competences_personne_poste_prestation
								WHERE Id_Personne IN (".$_SESSION['FiltreRHHS_RespProjet'].")
								AND Id_Poste IN (".$IdPosteResponsableProjet.")
							)
							";
			}
		}
		if($_SESSION['FiltreRHHS_Prestation']<>0){
			$requete.=" AND rh_personne_hs.Id_Prestation=".$_SESSION['FiltreRHHS_Prestation']." ";
			if($_SESSION['FiltreRHHS_Pole']<>0){
				$requete.=" AND rh_personne_hs.Id_Pole=".$_SESSION['FiltreRHHS_Pole']." ";
			}
		}
		if($Menu<>2){
			if($_SESSION['FiltreRHHS_Personne']<>0 && $_SESSION['FiltreRHHS_Personne']<>""){
				$requete.=" AND rh_personne_hs.Id_Personne=".$_SESSION['FiltreRHHS_Personne']." ";
			}
		}
		$requete.="AND YEAR(rh_personne_hs.DateHS)='".$_SESSION['FiltreRHHS_Annee']."' ";
		if($_SESSION['FiltreRHHS_Mois']<>0){
			if($_SESSION['FiltreRHHS_MoisCumules']<>""){
				$requete.="AND MONTH(rh_personne_hs.DateHS)>='".$_SESSION['FiltreRHHS_Mois']."' ";
			}
			else{
				$requete.="AND MONTH(rh_personne_hs.DateHS)='".$_SESSION['FiltreRHHS_Mois']."' ";
			}
		}
		
		if($_SESSION['FiltreRHHS'.$RH.'_EtatEnCours']<>"" || $_SESSION['FiltreRHHS'.$RH.'_EtatTransmiRH']<>"" || $_SESSION['FiltreRHHS'.$RH.'_EtatValide']<>"" || $_SESSION['FiltreRHHS'.$RH.'_EtatRefuse']<>""){
			$requete.=" AND ( ";
			if($_SESSION['FiltreRHHS'.$RH.'_EtatEnCours']<>""){
				$requete.=" (rh_personne_hs.Etat4=0 AND rh_personne_hs.Etat3<>-1 AND rh_personne_hs.Etat2<>-1) OR ";
			}
			if($_SESSION['FiltreRHHS'.$RH.'_EtatTransmiRH']<>""){
				$requete.=" (rh_personne_hs.DatePriseEnCompteRH<='0001-01-01' AND rh_personne_hs.Etat4=1) OR ";
			}
			if($_SESSION['FiltreRHHS'.$RH.'_EtatValide']<>""){
				$requete.=" (rh_personne_hs.Etat4=1 AND rh_personne_hs.DatePriseEnCompteRH>'0001-01-01') OR ";
			}
			if($_SESSION['FiltreRHHS'.$RH.'_EtatRefuse']<>""){
				$requete.=" (rh_personne_hs.Etat4=-1 OR rh_personne_hs.Etat3=-1 OR rh_personne_hs.Etat2=-1) OR ";
			}
			if($_SESSION['FiltreRHHS'.$RH.'_EtatSupprime']<>""){
				$requete.=" (Suppr=1) OR ";
			}
			$requete=substr($requete,0,-3);
			$requete.=" ) ";
			
			if($_SESSION['FiltreRHHS'.$RH.'_EtatSupprime']<>""){
				//$requete.=" AND (Suppr=0 OR Suppr=1) ";
			}
			else{
				$requete.=" AND Suppr=0 ";
			}
		}
		else{
			$requete.=" AND ( ";
			$requete.=" (rh_personne_hs.Etat4=0 AND rh_personne_hs.Etat3<>-1 AND rh_personne_hs.Etat2<>-1) ";
			$requete.=" ) ";
			
			if($_SESSION['FiltreRHHS'.$RH.'_EtatSupprime']<>""){
				$requete.=" AND (Suppr=1) ";
			}
			else{
				$requete.=" AND Suppr=0 ";
			}
		}
		$requeteOrder="";
		if($_SESSION['TriRHHS_General']<>""){
			$requeteOrder="ORDER BY ".substr($_SESSION['TriRHHS_General'],0,-1);
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
				if($page>1){echo "<b> <a style='color:#00599f;' href='Liste_HeureSupp.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=0'><<</a> </b>";}
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
							echo "<b> <a style='color:#00599f;' href='Liste_HeureSupp.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($page<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_HeureSupp.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_HeureSupp.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Id"><?php if($_SESSION["Langue"]=="FR"){echo "N° demande";}else{echo "Request number";} ?><?php if($_SESSION['TriRHHS_Id']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHHS_Id']=="ASC"){echo "&darr;";}?></a></td>
					<?php if($Menu==3 || $Menu==4){ ?>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_HeureSupp.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Personne"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriRHHS_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHHS_Personne']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_HeureSupp.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Contrat"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?><?php if($_SESSION['TriRHHS_Contrat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHHS_Contrat']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_HeureSupp.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=TempsTravail"><?php if($_SESSION["Langue"]=="FR"){echo "Temps travail";}else{echo "Work time";} ?><?php if($_SESSION['TriRHHS_TempsTravail']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHHS_TempsTravail']=="ASC"){echo "&darr;";}?></a></td>
					<?php } ?>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_HeureSupp.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Prestation"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?><?php if($_SESSION['TriRHHS_Prestation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHHS_Prestation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_HeureSupp.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Pole"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle";}else{echo "Pole";} ?><?php if($_SESSION['TriRHHS_Pole']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHHS_Pole']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_HeureSupp.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Date1"><?php if($_SESSION["Langue"]=="FR"){echo "Date création";}else{echo "Creation Date";} ?><?php if($_SESSION['TriRHHS_Date1']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHHS_Date1']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_HeureSupp.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Demandeur"><?php if($_SESSION["Langue"]=="FR"){echo "Demandeur";}else{echo "Applicant";} ?><?php if($_SESSION['TriRHHS_Demandeur']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHHS_Demandeur']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_HeureSupp.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=DateHS"><?php if($_SESSION["Langue"]=="FR"){echo "Date heure supp.";}else{echo "Date extra hour";} ?><?php if($_SESSION['TriRHHS_DateHS']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHHS_DateHS']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_HeureSupp.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Nb_Heures_Jour"><?php if($_SESSION["Langue"]=="FR"){echo "Nb h. jour";}else{echo "Nb h. day";} ?><?php if($_SESSION['TriRHHS_Nb_Heures_Jour']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHHS_Nb_Heures_Jour']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_HeureSupp.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Nb_Heures_Nuit"><?php if($_SESSION["Langue"]=="FR"){echo "Nb h. nuit";}else{echo "Nb h. night";} ?><?php if($_SESSION['TriRHHS_Nb_Heures_Nuit']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHHS_Nb_Heures_Nuit']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_HeureSupp.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Etat"><?php if($_SESSION["Langue"]=="FR"){echo "Etat de la demande";}else{echo "Request status";} ?><?php if($_SESSION['TriRHHS_Etat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHHS_Etat']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="12%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_HeureSupp.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=DatePriseEnCompteRH"><?php if($_SESSION["Langue"]=="FR"){echo "Date de prise en compte (paie)";}else{echo "Date of consideration (payroll)";} ?><?php if($_SESSION['TriRHHS_DatePriseEnCompteRH']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHHS_DatePriseEnCompteRH']=="ASC"){echo "&darr;";}?></a></td>
					<?php if($Menu==3){ ?>
					<td class='EnTeteTableauCompetences' width="15%" style="text-align:center;">
						<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Valider";}else{echo "Validate";}?>" onclick="if(!confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir valider la sélection ?";}else{echo "Are you sure you want to validate the selection?";} ?>')) return false;" name="validerSelection" value="<?php if($_SESSION["Langue"]=="FR"){echo "Valider";}else{echo "Validate";} ?>"><br>
						<input type='checkbox' id="check_Valide" name="check_Valide" value="" onchange="CocherValide()">
					</td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Valider";}else{echo "Validate";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Refuser";}else{echo "Refuse";} ?></td>
					<?php }
						elseif($Menu==4){
					?>
					<td class='EnTeteTableauCompetences' width="15%" style="text-align:center;">
						<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Valider la date de prise en compte";}else{echo "Validate the date of taking into account";}?>" onclick="if(!confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir valider la sélection ?";}else{echo "Are you sure you want to validate the selection?";} ?>')) return false;" name="validerSelectionRH" value="<?php if($_SESSION["Langue"]=="FR"){echo "Valider la date de prise en compte";}else{echo "Validate the date of taking into account";} ?>"><br>
						<input type='checkbox' id="check_ValidePriseEnCompte" name="check_ValidePriseEnCompte" value="" onchange="CocherPriseEnCompte()">
					</td>
					<?php 
						}
						if($Menu==3 || $Menu==4){ ?>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Supp.";}else{echo "Delete";} ?></td>
					<?php } ?>
				</tr>
	<?php
			
			if(isset($_POST['validerSelection'])){
				$result=mysqli_query($bdd,$requete2.$requete.$requeteOrder);
				while($row=mysqli_fetch_array($result)){
					if (isset($_POST['check_'.$row['Id'].''])){
						for($j=$_POST['step_'.$row['Id']];$j<=4;$j++){
							if($j==2){
								if(DroitsPrestationPole(array($IdPosteChefEquipe),$row['Id_Prestation'],$row['Id_Pole'])){
									$requeteUpdate="UPDATE rh_personne_hs SET 
											Id_Responsable2=".$_SESSION['Id_Personne'].",
											Date2='".date('Y-m-d')."',
											Etat2=1
											WHERE Id=".$row['Id']." ";
									$resultat=mysqli_query($bdd,$requeteUpdate);
								}
								else{$j=5;}
							}
							if($j==3){
								if(DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$row['Id_Prestation'],$row['Id_Pole'])){
									$requeteUpdate="UPDATE rh_personne_hs SET 
											Id_Responsable3=".$_SESSION['Id_Personne'].",
											Date3='".date('Y-m-d')."',
											Etat3=1
											WHERE Id=".$row['Id']." ";
									$resultat=mysqli_query($bdd,$requeteUpdate);
								}
								else{$j=5;}
							}
							if($j==4){
								if(DroitsPrestationPole(array($IdPosteCoordinateurProjet),$row['Id_Prestation'],$row['Id_Pole']) 
									|| (NombreHeuresJournee($_POST['id_personne_'.$row['Id']],$_POST['dateHS_'.$row['Id']])<=10
									&& NombreHeuresSemaine($_POST['id_personne_'.$row['Id']],$_POST['dateHS_'.$row['Id']])<=48)){
									$Id_Responsable4=0;
									if(DroitsPrestationPole(array($IdPosteCoordinateurProjet),$row['Id_Prestation'],$row['Id_Pole'])){
										$Id_Responsable4=$_SESSION['Id_Personne'];
									}
									$requeteUpdate="UPDATE rh_personne_hs SET 
											Id_Responsable4=".$Id_Responsable4.",
											Date4='".date('Y-m-d')."',
											Etat4=1
											WHERE Id=".$row['Id']." ";
									$resultat=mysqli_query($bdd,$requeteUpdate);
									
									if(DateAvant25DuMois($_POST['dateHS_'.$row['Id']],date('Y-m-d'))==1){
										$requeteUpdate="UPDATE rh_personne_hs SET 
												DateRH=DateHS,
												DatePriseEnCompteRH='".date('Y-m-d')."',
												Avant25Mois=1
												WHERE Id=".$row['Id']." ";
										$resultat=mysqli_query($bdd,$requeteUpdate);
									}
								}
								else{$j=5;}
							}
						}
					}
				}
			}
			elseif(isset($_POST['validerSelectionRH'])){
				$result=mysqli_query($bdd,$requete2.$requete.$requeteOrder);
				while($row=mysqli_fetch_array($result)){
					if (isset($_POST['checkRH_'.$row['Id'].''])){
						$requeteUpdate="UPDATE rh_personne_hs SET 
								Id_RH=".$_SESSION['Id_Personne'].",
								DatePriseEnCompteRH='".date('Y-m-d')."',
								DateRH=DateHS
								WHERE Id=".$row['Id']." ";
						$resultat=mysqli_query($bdd,$requeteUpdate);
					}
				}
			}
			$result=mysqli_query($bdd,$requete2.$requete.$requeteOrder.$requete3);
			
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
					
					$Etat="";
					$CouleurEtat=$couleur;
					$Hover="";
					if($row['Etat4']==1 && $row['DatePriseEnCompteRH']>'0001-01-01'){
						if($_SESSION["Langue"]=="FR"){
							$Etat="Validée et pris en compte sur la paie";}
						else{
							$Etat="Validated and taken into account on payroll";}
						$CouleurEtat="#7ffa1e";
					}
					elseif($row['Etat4']==-1 || $row['Etat3']==-1 || $row['Etat2']==-1){
						if($_SESSION["Langue"]=="FR"){
							$Etat="Refusée";}
						else{
							$Etat="Refused";}
						$Hover=" id='leHover' ";
						if($row['Etat2']==-1){
							$Etat.="<span>".stripslashes($row['RaisonRefus2'])."<br>";
							$Etat.=stripslashes($row['Commentaire2'])."</span>";
						}
						elseif($row['Etat3']==-1){
							$Etat.="<span>".stripslashes($row['RaisonRefus3'])."<br>";
							$Etat.=stripslashes($row['Commentaire3'])."</span>";
						}
						elseif($row['Etat4']==-1){
							$Etat.="<span>".stripslashes($row['RaisonRefus4'])."<br>";
							$Etat.=stripslashes($row['Commentaire4'])."</span>";
						}
						$CouleurEtat="#ff3d3d";
					}
					elseif($row['Etat4']==1 && $row['DatePriseEnCompteRH']<='0001-01-01'){
						if($_SESSION["Langue"]=="FR"){
							$Etat="Transmis aux RH";}
						else{
							$Etat="Submitted to HR";}
						$CouleurEtat="#449ef0";
					}
					elseif($row['Etat4']==0 && $row['Etat3']<>-1 && $row['Etat2']<>-1){
						$n=1;
						if($row['Etat2']==0){$n=1;}
						elseif($row['Etat3']==0){$n=2;}
						
						if($_SESSION["Langue"]=="FR"){
							$Etat="En attente de pré validation (".$n."/2)";}
						else{
							$Etat="Waiting for pre-validation (".$n."/ 2)";}
						$CouleurEtat="#fab342";
					}
					if($row['Suppr']==1){
						if($_SESSION["Langue"]=="FR"){
							$Etat="Supprimée";}
						else{
							$Etat="Deleted";}
						$CouleurEtat="#bca2aa";
					}
		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td align="center"><a style="color:#3e65fa;" href="javascript:OuvreFenetreModif(<?php echo $Menu; ?>,<?php echo $row['Id']; ?>)"><?php echo stripslashes($row['Id']);?></a></td>
						<?php if($Menu==3 || $Menu==4){ ?>
						<td><?php echo stripslashes($row['Personne']);?></td>
						<td><?php echo stripslashes($row['Contrat']);?></td>
						<td><?php echo stripslashes($row['TempsTravail']);?></td>
						<?php } ?>
						<td><?php echo substr(stripslashes($row['Prestation']),0,7);?></td>
						<td><?php echo stripslashes($row['Pole']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['Date1']);?></td>
						<td><?php echo stripslashes($row['Demandeur']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateHS']);?></td>
						<td><?php echo stripslashes($row['Nb_Heures_Jour']); ?></td>
						<td><?php echo stripslashes($row['Nb_Heures_Nuit']); ?></td>
						<td bgcolor="<?php echo $CouleurEtat; ?>" <?php echo $Hover; ?> ><?php echo $Etat; ?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateRH']);?></td>
						<?php if($Menu==3){ ?>
						<td align="center">
							<?php 
								if($row['Suppr']==0 && $row['DatePriseEnCompteRH']<='0001-01-01'){
									if(($row['Etat2']==0 && DroitsPrestationPole(array($IdPosteChefEquipe),$row['Id_Prestation'],$row['Id_Pole']))
									|| ($row['Etat2']==1 && $row['Etat3']==0 && DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$row['Id_Prestation'],$row['Id_Pole']))
									|| ($row['Etat2']==1 && $row['Etat3']==1 && $row['Etat4']==0 && DroitsPrestationPole(array($IdPosteCoordinateurProjet),$row['Id_Prestation'],$row['Id_Pole']))
									){
										if($row['Etat2']==0){$step=2;}
										elseif($row['Etat3']==0){$step=3;}
										elseif($row['Etat4']==0){$step=4;}
										echo "<input class='check' type='checkbox' name='check_".$row['Id']."' value=''>";
										echo "<input type='hidden' name='step_".$row['Id']."' value='".$step."'>";
										echo "<input type='hidden' name='id_personne_".$row['Id']."' value='".$row['Id_Personne']."'>";
										echo "<input type='hidden' name='dateHS_".$row['Id']."' value='".$row['DateHS']."'>";
									}
								}
							?>
						</td>
						<td align="center">
							<?php 
								if($row['Suppr']==0 && $row['DatePriseEnCompteRH']<='0001-01-01'){
									if(($row['Etat2']==0 && DroitsPrestationPole(array($IdPosteChefEquipe),$row['Id_Prestation'],$row['Id_Pole']))
									|| ($row['Etat2']==1 && $row['Etat3']==0 && DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$row['Id_Prestation'],$row['Id_Pole']))
									|| ($row['Etat2']==1 && $row['Etat3']==1 && $row['Etat4']==0 && DroitsPrestationPole(array($IdPosteCoordinateurProjet),$row['Id_Prestation'],$row['Id_Pole']))
									){
										if($row['Etat4']==0){$step=4;}
										elseif($row['Etat3']==0){$step=3;}
										elseif($row['Etat2']==0){$step=2;}
								?>
								<a style="text-decoration:none;" title="<?php if($_SESSION['Langue']=="FR"){echo "Valider";}else{echo "Validate";}?>" href="javascript:OuvreFenetreValider('<?php echo $Menu; ?>','<?php echo $row['Id']; ?>','<?php echo $step; ?>')"><img src="../../Images/Valider.png" width="18px" border="0" alt="Valider" title="Valider"></a>
								<?php
									}
								}
							?>
						</td>
						<td align="center">
							<?php 
								if($row['Suppr']==0 && $row['DatePriseEnCompteRH']<='0001-01-01'){
									if(($row['Etat2']==0 && DroitsPrestationPole(array($IdPosteChefEquipe),$row['Id_Prestation'],$row['Id_Pole']))
									|| ($row['Etat2']==1 && $row['Etat3']==0 && DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$row['Id_Prestation'],$row['Id_Pole']))
									|| ($row['Etat2']==1 && $row['Etat3']==1 && $row['Etat4']==0 && DroitsPrestationPole(array($IdPosteCoordinateurProjet),$row['Id_Prestation'],$row['Id_Pole']))
									){
										if($row['Etat4']==0){$step=4;}
										elseif($row['Etat3']==0){$step=3;}
										elseif($row['Etat2']==0){$step=2;}
								?>
								<a style="text-decoration:none;" title="<?php if($_SESSION['Langue']=="FR"){echo "Refuser";}else{echo "Refuse";}?>" href="javascript:OuvreFenetreRefus('<?php echo $Menu; ?>','<?php echo $row['Id']; ?>','<?php echo $step; ?>')"><img src="../../Images/supprimer.png" width="18px" border="0" alt="Refuse" title="Refuse"></a>
								<?php
									}
								}
							?>
						</td>
						<?php }
							elseif($Menu==4){
						?>
						<td align="center">
						<?php
							if($row['Suppr']==0){
								if($row['Etat4']==1 && $row['DatePriseEnCompteRH']<='0001-01-01'){
									echo "<input class='checkRH' type='checkbox' name='checkRH_".$row['Id']."' value=''>";
								}
							}
						?>
						</td>
						<?php
							}
						?>
						<?php if($Menu==3 || $Menu==4){ ?>
						<td>
							<?php if($row['Suppr']==0){if(($Menu==3 && $row['Etat2']==0 && $row['DatePriseEnCompteRH']<='0001-01-01' && DroitsPrestationPole(array($IdPosteChefEquipe),$row['Id_Prestation'],$row['Id_Pole'])) || $Menu==4){ ?>
							<a class="LigneTableauRecherchePersonne" style="cursor:pointer;" onclick="if(window.confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir supprimer ?";}else{echo "Are you sure you want to delete ?";} ?>')){OuvreFenetreSuppr('<?php echo $Menu; ?>','<?php echo $row['Id']; ?>');}else{return false;}"><img src="../../Images/Suppression.gif" border="0" title="Supprimer" alt="Suppression"></a>
							<?php }} ?>
						</td>
						<?php } ?>
					</tr>
				<?php
				}	//Fin boucle
			}
			?>
			</table>
		</td>
	</tr>
	<tr>
		<td height="150"></td>
	</tr>
	<?php 
		if($Menu==2){
	?>
	<tr>
		<td align="center">
		<table class="TableCompetences" align="center">
		<tr><td class="Libelle">
		<?php 
			if($_SESSION["Langue"]=="FR"){echo "Les heures supplémentaires sont payées dès lors que la semaine est complète";}else{echo "Overtime is paid when the week is complete";}
		?>	
		</td></tr>
		</table>
		</td>
	</tr>
	<?php 
		}
	?>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
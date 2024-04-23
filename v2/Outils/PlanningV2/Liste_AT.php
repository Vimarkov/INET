<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModif(Menu,Id)
		{var w=window.open("Modif_AT.php?Mode=M&Id="+Id+"&Menu="+Menu,"PageAT","status=no,menubar=no,width=1300,height=650,scrollbars=1");
		w.focus();
		}
	function OuvreFenetreSuppr(Menu,Id)
		{var w=window.open("Modif_AT.php?Mode=S&Id="+Id+"&Menu="+Menu,"PageAT","status=no,menubar=no,width=10,height=60");
		w.focus();
		}
	function OuvreFenetreExcel(Menu)
		{window.open("Export_AT.php?Menu="+Menu,"PageExcel","status=no,menubar=no,width=900,height=450");}
	function OuvreFormatExcel(Id)
		{window.open("AT_FormatExcel.php?Id="+Id,"PageExcel","status=no,menubar=no,width=900,height=450");}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

if(isset($_GET['Tri'])){
	$tab = array("Id","Personne","DateCreation","Demandeur","DateAT","HeureAT","Metier","LieuAT","Activite","CommentaireNature","ArretDeTravail");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			if($tri=="LieuAT"){
				if($_SESSION['Langue']=="FR"){
					$_SESSION['TriRHAT_General']= str_replace($tri." ASC,","",$_SESSION['TriRHAT_General']);
					$_SESSION['TriRHAT_General']= str_replace($tri." DESC,","",$_SESSION['TriRHAT_General']);
					$_SESSION['TriRHAT_General']= str_replace($tri." ASC","",$_SESSION['TriRHAT_General']);
					$_SESSION['TriRHAT_General']= str_replace($tri." DESC","",$_SESSION['TriRHAT_General']);
					if($_SESSION['TriRHAT_'.$tri]==""){$_SESSION['TriRHAT_'.$tri]="ASC";$_SESSION['TriRHAT_General'].= $tri." ".$_SESSION['TriRHAT_'.$tri].",";}
					elseif($_SESSION['TriRHAT_'.$tri]=="ASC"){$_SESSION['TriRHAT_'.$tri]="DESC";$_SESSION['TriRHAT_General'].= $tri." ".$_SESSION['TriRHAT_'.$tri].",";}
					else{$_SESSION['TriRHAT_'.$tri]="";}
				}
				else{
					$tri=$tri."EN";
					$_SESSION['TriRHAT_General']= str_replace($tri." ASC,","",$_SESSION['TriRHAT_General']);
					$_SESSION['TriRHAT_General']= str_replace($tri." DESC,","",$_SESSION['TriRHAT_General']);
					$_SESSION['TriRHAT_General']= str_replace($tri." ASC","",$_SESSION['TriRHAT_General']);
					$_SESSION['TriRHAT_General']= str_replace($tri." DESC","",$_SESSION['TriRHAT_General']);
					if($_SESSION['TriRHAT_'.$tri]==""){$_SESSION['TriRHAT_'.$tri]="ASC";$_SESSION['TriRHAT_General'].= $tri." ".$_SESSION['TriRHAT_'.$tri].",";}
					elseif($_SESSION['TriRHAT_'.$tri]=="ASC"){$_SESSION['TriRHAT_'.$tri]="DESC";$_SESSION['TriRHAT_General'].= $tri." ".$_SESSION['TriRHAT_'.$tri].",";}
					else{$_SESSION['TriRHAT_'.$tri]="";}
				}
			}
			else{
				$_SESSION['TriRHAT_General']= str_replace($tri." ASC,","",$_SESSION['TriRHAT_General']);
				$_SESSION['TriRHAT_General']= str_replace($tri." DESC,","",$_SESSION['TriRHAT_General']);
				$_SESSION['TriRHAT_General']= str_replace($tri." ASC","",$_SESSION['TriRHAT_General']);
				$_SESSION['TriRHAT_General']= str_replace($tri." DESC","",$_SESSION['TriRHAT_General']);
				if($_SESSION['TriRHAT_'.$tri]==""){$_SESSION['TriRHAT_'.$tri]="ASC";$_SESSION['TriRHAT_General'].= $tri." ".$_SESSION['TriRHAT_'.$tri].",";}
				elseif($_SESSION['TriRHAT_'.$tri]=="ASC"){$_SESSION['TriRHAT_'.$tri]="DESC";$_SESSION['TriRHAT_General'].= $tri." ".$_SESSION['TriRHAT_'.$tri].",";}
				else{$_SESSION['TriRHAT_'.$tri]="";}
			}
		}
	}
}
?>

<form class="test" action="Liste_AT.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#ff1111;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Liste des accidents de travail";}else{echo "List of accidents at work";}
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
			<td width="15%" class="Libelle" <?php if($Menu==2){echo "style='display:none;'";} ?>>
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?>
				<select id="personne" style="width:100px;" name="personne" onchange="submit();">
					<option value='0'></option>
					<?php
						if($Menu==4){
							if(DroitsFormationPlateforme($TableauIdPostesRH) || DroitsFormationPlateforme(array($IdPosteResponsableHSE))){
								$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
										CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
										FROM rh_personne_at
										LEFT JOIN new_rh_etatcivil
										ON new_rh_etatcivil.Id=rh_personne_at.Id_Personne
										WHERE ((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_at.Id_Prestation) IN 
										(
											SELECT Id_Plateforme 
											FROM new_competences_personne_poste_plateforme
											WHERE Id_Personne=".$_SESSION['Id_Personne']." 
											AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.",".$IdPosteResponsableHSE.")
										)
										OR rh_personne_at.Id_Prestation=0)
										ORDER BY Personne ASC";
							}
						}
						elseif($Menu==3){
							if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
								$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
										CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
										FROM rh_personne_at
										LEFT JOIN new_rh_etatcivil
										ON new_rh_etatcivil.Id=rh_personne_at.Id_Personne
										WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_at.Id_Prestation) IN 
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
									FROM rh_personne_at
									LEFT JOIN new_rh_etatcivil
									ON new_rh_etatcivil.Id=rh_personne_at.Id_Personne
									WHERE CONCAT(rh_personne_at.Id_Prestation,'_',rh_personne_at.Id_Pole) IN 
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
									FROM rh_personne_at
									LEFT JOIN new_rh_etatcivil
									ON new_rh_etatcivil.Id=rh_personne_at.Id_Personne
									WHERE rh_personne_at.Id_Personne=".$_SESSION['Id_Personne']."
									ORDER BY Personne ASC";
						}
					
						$resultPersonne=mysqli_query($bdd,$requetePersonne);
						$NbPersonne=mysqli_num_rows($resultPersonne);
						
						$personne=$_SESSION['FiltreRHAT_Personne'];
						if($_POST){$personne=$_POST['personne'];}
						$_SESSION['FiltreRHAT_Personne']= $personne;
						
						while($rowPersonne=mysqli_fetch_array($resultPersonne))
						{
							echo "<option value='".$rowPersonne['Id']."'";
							if ($personne == $rowPersonne['Id']){echo " selected ";}
							echo ">".$rowPersonne['Personne']."</option>\n";
						}
					?>
				</select>
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Arrêt de travail :";}else{echo "Work stopping :";} ?>
				<select id="arretTravail" name="arretTravail" onchange="submit();">
					<option value='0' selected></option>
					<?php
						$arretTravail=$_SESSION['FiltreRHAT_ArretTravail'];
						if($_POST){$arretTravail=$_POST['arretTravail'];}
						$_SESSION['FiltreRHAT_ArretTravail']=$arretTravail;
					?>
					<option value='1' <?php if($arretTravail==1){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?></option>
					<option value='-1' <?php if($arretTravail==-1){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?></option>
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
						$mois=$_SESSION['FiltreRHAT_Mois'];
						if($_POST){$mois=$_POST['mois'];}
						$_SESSION['FiltreRHAT_Mois']=$mois;
						
						for($i=0;$i<=11;$i++){
							$numMois=$i+1;
							if($numMois<10){$numMois="0".$numMois;}
							echo "<option value='".$numMois."'";
							if($mois== ($i+1)){echo " selected ";}
							echo ">".$arrayMois[$i]."</option>\n";
						}
						
						$annee=$_SESSION['FiltreRHAT_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						if($annee==""){$annee=date("Y");}
						$_SESSION['FiltreRHAT_Annee']=$annee;
					?>
				</select>
				<?php
					$MoisCumules=$_SESSION['FiltreRHAT_MoisCumules'];
					if($_POST){
						if(isset($_POST['MoisCumules'])){$MoisCumules="checked";}else{$MoisCumules="";}				
					}
					$_SESSION['FiltreRHAT_MoisCumules']=$MoisCumules;
				?>
				<input type="checkbox" id="MoisCumules" name="MoisCumules" value="MoisCumules" <?php echo $MoisCumules; ?>><?php if($_SESSION["Langue"]=="FR"){echo "Jusqu'à la fin de l'année";}else{echo "Until the end of the year";} ?> &nbsp;&nbsp;
			</td>
			<td width="30%" class="Libelle">
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
			<td valign="top" colspan="8" class="Libelle" <?php if($Menu<>4){echo "style='display:none;'";} ?>>
				<?php if($LangueAffichage=="FR"){echo "Responsable Projet";}else{echo "Project manager";}?> :<br>
						<?php
						
							$Id_RespProjet=$_SESSION['FiltreRHAT_RespProjet'];
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
							$_SESSION['FiltreRHAT_RespProjet']=$Id_RespProjet;
	
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
									$checkboxes = explode(',',$_SESSION['FiltreRHAT_RespProjet']);
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
		$requeteAnalyse="SELECT rh_personne_at.Id ";
		$requete2="SELECT rh_personne_at.Id,DateCreation,DateAT,HeureAT,Id_Metier,Id_Lieu_AT,Activite,CommentaireNature,
			rh_personne_at.Id_Prestation,rh_personne_at.Id_Pole,ArretDeTravail, 
			(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
			(SELECT Libelle FROM rh_lieu_at WHERE rh_lieu_at.Id=Id_Lieu_AT) AS LieuAT,
			(SELECT LibelleEN FROM rh_lieu_at WHERE rh_lieu_at.Id=Id_Lieu_AT) AS LieuATEN,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_at.Id_Personne) AS Personne, 
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_at.Id_Createur) AS Demandeur ";
		$requete=" FROM rh_personne_at
					WHERE Suppr=0 AND ";
		if($Menu==4){
			if(DroitsFormationPlateforme($TableauIdPostesRH) || DroitsFormationPlateforme(array($IdPosteResponsableHSE))){
				$requete.="((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_at.Id_Prestation) IN 
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.",".$IdPosteResponsableHSE.")
					)
					OR rh_personne_at.Id_Prestation=0
					)";
			}
		}
		elseif($Menu==14){
			$requete.="((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_at.Id_Prestation) IN 
				(
					SELECT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$_SESSION['Id_Personne']." 
					AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.",".$IdPosteResponsableHSE.")
				)
				OR rh_personne_at.Id_Prestation=0
				)";
		}
		elseif($Menu==3){
			if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
				$requete.="(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_at.Id_Prestation) IN 
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
					)";
			}
			else{
				$requete.="CONCAT(rh_personne_at.Id_Prestation,'_',rh_personne_at.Id_Pole) IN 
							(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
							)";
			}
		}
		elseif($Menu==2){
			$requete.="rh_personne_at.Id_Personne=".$_SESSION['Id_Personne']." ";
		}
		if($Menu<>2){
			if($_SESSION['FiltreRHAT_Personne']<>0){
				$requete.=" AND rh_personne_at.Id_Personne=".$_SESSION['FiltreRHAT_Personne']." ";
			}
		}
		if($Menu==4){
			if($_SESSION['FiltreRHAT_RespProjet']<>""){
				$requete.="AND CONCAT(rh_personne_at.Id_Prestation,'_',rh_personne_at.Id_Pole) 
							IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
								FROM new_competences_personne_poste_prestation
								WHERE Id_Personne IN (".$_SESSION['FiltreRHAT_RespProjet'].")
								AND Id_Poste IN (".$IdPosteResponsableProjet.")
							)
							";
			}
		}
		if($_SESSION['FiltreRHAT_Mois']<>0){
			if($_SESSION['FiltreRHAT_MoisCumules']<>""){
				$requete.=" AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))>='".$_SESSION['FiltreRHAT_Annee'].'_'.$_SESSION['FiltreRHAT_Mois']."' 
							AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))<='".$_SESSION['FiltreRHAT_Annee']."_12' ";
			}
			else{
				$requete.=" AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))='".$_SESSION['FiltreRHAT_Annee'].'_'.$_SESSION['FiltreRHAT_Mois']."' ";
			}
		}
		else{
			$requete.=" AND YEAR(DateAT)='".$_SESSION['FiltreRHAT_Annee']."' ";
		}
		
		if($_SESSION['FiltreRHAT_ArretTravail']==1){
			$requete.=" AND  ArretDeTravail=1 ";
		}
		elseif($_SESSION['FiltreRHAT_ArretTravail']==-1){
			$requete.=" AND  ArretDeTravail=0 ";
		}

		$requeteOrder="";
		if($_SESSION['TriRHAT_General']<>""){
			$requeteOrder="ORDER BY ".substr($_SESSION['TriRHAT_General'],0,-1);
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
				if($page>1){echo "<b> <a style='color:#00599f;' href='Liste_AT.php?Menu=".$Menu."&debut=1&Page=0'><<</a> </b>";}
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
							echo "<b> <a style='color:#00599f;' href='Liste_AT.php?Menu=".$Menu."&debut=1&Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($page<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_AT.php?Menu=".$Menu."&debut=1&Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=Id"><?php if($_SESSION["Langue"]=="FR"){echo "N° AT";}else{echo "Accident no";} ?><?php if($_SESSION['TriRHAT_Id']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAT_Id']=="ASC"){echo "&darr;";}?></a></td>
					<?php if($Menu==3 || $Menu==4){ ?>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=Personne"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriRHAT_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAT_Personne']=="ASC"){echo "&darr;";}?></a></td>
					<?php } ?>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=DateCreation"><?php if($_SESSION["Langue"]=="FR"){echo "Date déclaration";}else{echo "Declaration date";} ?><?php if($_SESSION['TriRHAT_DateCreation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAT_DateCreation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=Demandeur"><?php if($_SESSION["Langue"]=="FR"){echo "Déclaré par";}else{echo "Declared by";} ?><?php if($_SESSION['TriRHAT_Demandeur']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAT_Demandeur']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=DateAT"><?php if($_SESSION["Langue"]=="FR"){echo "Date AT";}else{echo "Date accident at work";} ?><?php if($_SESSION['TriRHAT_DateAT']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAT_DateAT']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=HeureAT"><?php if($_SESSION["Langue"]=="FR"){echo "Heure AT";}else{echo "Time accident at work";} ?><?php if($_SESSION['TriRHAT_HeureAT']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAT_HeureAT']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=ArretDeTravail"><?php if($_SESSION["Langue"]=="FR"){echo "Arrêt de travail";}else{echo "Work stopping";} ?><?php if($_SESSION['TriRHAT_ArretDeTravail']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAT_ArretDeTravail']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=Metier"><?php if($_SESSION["Langue"]=="FR"){echo "Metier";}else{echo "Job";} ?><?php if($_SESSION['TriRHAT_Metier']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAT_Metier']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=LieuAT"><?php if($_SESSION["Langue"]=="FR"){echo "Lieu";}else{echo "Place";} ?><?php if($_SESSION['TriRHAT_LieuAT']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAT_LieuAT']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=Activite"><?php if($_SESSION["Langue"]=="FR"){echo "Activité";}else{echo "Activity";} ?><?php if($_SESSION['TriRHAT_Activite']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAT_Activite']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=CommentaireNature"><?php if($_SESSION["Langue"]=="FR"){echo "Nature";}else{echo "Nature";} ?><?php if($_SESSION['TriRHAT_CommentaireNature']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAT_CommentaireNature']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"></td>
					<?php 
						if($Menu==4){ ?>
					<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Supp.";}else{echo "Delete";} ?></td>
					<?php } ?>
				</tr>
	<?php
			
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
					
					if($_SESSION["Langue"]=="FR"){$Lieu=$row['LieuAT'];}
					else{$Lieu=$row['LieuATEN'];}
					
					$arret="";
					if($row['ArretDeTravail']==1){$arret="X";}
		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td align="center"><a style="color:#3e65fa;" href="javascript:OuvreFenetreModif(<?php echo $Menu; ?>,<?php echo $row['Id']; ?>)"><?php echo stripslashes($row['Id']);?></a></td>
						<?php if($Menu==3 || $Menu==4){ ?>
						<td><?php echo stripslashes($row['Personne']);?></td>
						<?php } ?>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateCreation']);?></td>
						<td><?php echo stripslashes($row['Demandeur']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateAT']);?></td>
						<td><?php echo stripslashes($row['HeureAT']);?></td>
						<td><?php echo stripslashes($arret);?></td>
						<td><?php echo stripslashes($row['Metier']);?></td>
						<td><?php echo stripslashes($Lieu);?></td>
						<td><?php echo stripslashes(str_replace("\\","",$row['Activite']));?></td>
						<td><?php echo stripslashes(str_replace("\\","",$row['CommentaireNature']));?></td>
						<td>
							<a href="javascript:OuvreFormatExcel('<?php echo $row['Id']; ?>')">
							<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
							</a>
						</td>
						<?php if($Menu==4){ ?>
						<td>
							<?php if(($Menu==3 && $row['EtatN2']==0 && DroitsPrestationPole(array($IdPosteChefEquipe),$row['Id_Prestation'],$row['Id_Pole'])) || $Menu==4){ ?>
							<a class="LigneTableauRecherchePersonne" style='cursor:pointer;' onclick="if(window.confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir supprimer ?";}else{echo "Are you sure you want to delete ?";} ?>')){OuvreFenetreSuppr('<?php echo $Menu; ?>','<?php echo $row['Id']; ?>');}else{return false;}"><img src="../../Images/Suppression.gif" border="0" title="Supprimer" alt="Suppression"></a>
							<?php } ?>
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

</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
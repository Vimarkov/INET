<script language="javascript">
	function OuvreFenetreModif(Menu,Id)
		{var w=window.open("Modif_AT2.php?Mode=M&Id="+Id+"&Menu="+Menu,"PageAT","status=no,menubar=no,width=1300,height=650,scrollbars=1");
		w.focus();
		}
	function OuvreFenetreSuppr(Menu,Id)
		{var w=window.open("Modif_AT2.php?Mode=S&Id="+Id+"&Menu="+Menu,"PageAT","status=no,menubar=no,width=10,height=60");
		w.focus();
		}
	function OuvreFenetreExcel(Menu)
		{window.open("Export_AT.php?Menu="+Menu,"PageExcel","status=no,menubar=no,width=900,height=450");}
	function OuvreFormatExcel(Id)
		{window.open("AT_FormatExcel.php?Id="+Id,"PageExcel","status=no,menubar=no,width=900,height=450");}
</script>
<?php
if(isset($_GET['Tri'])){
	$tab = array("Id","Personne","DateCreation","UER","Demandeur","DateAT","HeureAT","Metier","LieuAT","Activite","CommentaireNature","ArretDeTravail");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			if($tri=="LieuAT"){
				if($_SESSION['Langue']=="FR"){
					$_SESSION['TriAccidentT_General']= str_replace($tri." ASC,","",$_SESSION['TriAccidentT_General']);
					$_SESSION['TriAccidentT_General']= str_replace($tri." DESC,","",$_SESSION['TriAccidentT_General']);
					$_SESSION['TriAccidentT_General']= str_replace($tri." ASC","",$_SESSION['TriAccidentT_General']);
					$_SESSION['TriAccidentT_General']= str_replace($tri." DESC","",$_SESSION['TriAccidentT_General']);
					if($_SESSION['TriAccidentT_'.$tri]==""){$_SESSION['TriAccidentT_'.$tri]="ASC";$_SESSION['TriAccidentT_General'].= $tri." ".$_SESSION['TriAccidentT_'.$tri].",";}
					elseif($_SESSION['TriAccidentT_'.$tri]=="ASC"){$_SESSION['TriAccidentT_'.$tri]="DESC";$_SESSION['TriAccidentT_General'].= $tri." ".$_SESSION['TriAccidentT_'.$tri].",";}
					else{$_SESSION['TriAccidentT_'.$tri]="";}
				}
				else{
					$tri=$tri."EN";
					$_SESSION['TriAccidentT_General']= str_replace($tri." ASC,","",$_SESSION['TriAccidentT_General']);
					$_SESSION['TriAccidentT_General']= str_replace($tri." DESC,","",$_SESSION['TriAccidentT_General']);
					$_SESSION['TriAccidentT_General']= str_replace($tri." ASC","",$_SESSION['TriAccidentT_General']);
					$_SESSION['TriAccidentT_General']= str_replace($tri." DESC","",$_SESSION['TriAccidentT_General']);
					if($_SESSION['TriAccidentT_'.$tri]==""){$_SESSION['TriAccidentT_'.$tri]="ASC";$_SESSION['TriAccidentT_General'].= $tri." ".$_SESSION['TriAccidentT_'.$tri].",";}
					elseif($_SESSION['TriAccidentT_'.$tri]=="ASC"){$_SESSION['TriAccidentT_'.$tri]="DESC";$_SESSION['TriAccidentT_General'].= $tri." ".$_SESSION['TriAccidentT_'.$tri].",";}
					else{$_SESSION['TriAccidentT_'.$tri]="";}
				}
			}
			else{
				$_SESSION['TriAccidentT_General']= str_replace($tri." ASC,","",$_SESSION['TriAccidentT_General']);
				$_SESSION['TriAccidentT_General']= str_replace($tri." DESC,","",$_SESSION['TriAccidentT_General']);
				$_SESSION['TriAccidentT_General']= str_replace($tri." ASC","",$_SESSION['TriAccidentT_General']);
				$_SESSION['TriAccidentT_General']= str_replace($tri." DESC","",$_SESSION['TriAccidentT_General']);
				if($_SESSION['TriAccidentT_'.$tri]==""){$_SESSION['TriAccidentT_'.$tri]="ASC";$_SESSION['TriAccidentT_General'].= $tri." ".$_SESSION['TriAccidentT_'.$tri].",";}
				elseif($_SESSION['TriAccidentT_'.$tri]=="ASC"){$_SESSION['TriAccidentT_'.$tri]="DESC";$_SESSION['TriAccidentT_General'].= $tri." ".$_SESSION['TriAccidentT_'.$tri].",";}
				else{$_SESSION['TriAccidentT_'.$tri]="";}
			}
		}
	}
}
?>

<form class="test" action="Liste_AT2.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
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
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "UER :";}else{echo "UER :";} ?>
				<select id="plateforme" style="width:100px;" name="plateforme" onchange="submit();">
					<option value='0'></option>
					<?php
						$requeteUER="
							SELECT DISTINCT (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_at.Id_Prestation) AS Id_Plateforme, 
							(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_at.Id_Prestation) AS Plateforme
							FROM rh_personne_at
							WHERE 
								Suppr=0 ";
						if(DroitsFormation1Plateforme("17",array($IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableQualite))){
							
						}
						else{
							$requeteUER.="AND
									(
										(
											SELECT COUNT(Id)	
											FROM new_competences_personne_prestation
											WHERE 
											new_competences_personne_prestation.Id_Personne=rh_personne_at.Id_Personne
											AND Date_Debut<='".date('Y-m-d')."'
											AND (Date_Fin<='0001-01-01' OR Date_Fin>='".date('Y-m-d')."')
											AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN (
												SELECT Id_Plateforme 
												FROM new_competences_personne_poste_plateforme
												WHERE Id_Personne=".$_SESSION['Id_Personne']." 
												AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteResponsablePlateforme.",".$IdPosteOperateurSaisieRH.",".$IdPosteResponsableHSE.",".$IdPosteCoordinateurSecurite.")
											)
										)>0
										
										OR 
										(
											SELECT COUNT(Id)
											FROM new_competences_personne_prestation
											WHERE new_competences_personne_prestation.Id_Personne=rh_personne_at.Id_Personne
											AND Date_Debut<='".date('Y-m-d')."'
											AND (Date_Fin<='0001-01-01' OR Date_Fin>='".date('Y-m-d')."')
											AND CONCAT(Id_Prestation,'_',Id_Pole) IN 
											(
												SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION["Id_Personne"]."
												AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
											)
										)>0
									) ";
									
						}
						$requeteUER.="ORDER BY Plateforme ASC";
						$resultUER=mysqli_query($bdd,$requeteUER);
						$NbUER=mysqli_num_rows($resultUER);
						
						$uer=$_SESSION['FiltreAccidentT_UER'];
						if($_POST){$uer=$_POST['plateforme'];}
						$_SESSION['FiltreAccidentT_UER']= $uer;
						
						if($NbUER>0){
							while($rowUER=mysqli_fetch_array($resultUER))
							{
								echo "<option value='".$rowUER['Id_Plateforme']."'";
								if ($uer == $rowUER['Id_Plateforme']){echo " selected ";}
								echo ">".$rowUER['Plateforme']."</option>\n";
							}
						}
					?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?>
				<select id="personne" style="width:100px;" name="personne" onchange="submit();">
					<option value='0'></option>
					<?php
						$requetePersonne="
							SELECT DISTINCT new_rh_etatcivil.Id, 
							CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
							FROM rh_personne_at
							LEFT JOIN new_rh_etatcivil
							ON new_rh_etatcivil.Id=rh_personne_at.Id_Personne
							WHERE Suppr=0 ";
							if(DroitsFormation1Plateforme("17",array($IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableQualite))){
								
							}
							else{
								$requetePersonne.="AND
										(
											(
												SELECT COUNT(Id)	
												FROM new_competences_personne_prestation
												WHERE 
												new_competences_personne_prestation.Id_Personne=rh_personne_at.Id_Personne
												AND Date_Debut<='".date('Y-m-d')."'
												AND (Date_Fin<='0001-01-01' OR Date_Fin>='".date('Y-m-d')."')
												AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN (
													SELECT Id_Plateforme 
													FROM new_competences_personne_poste_plateforme
													WHERE Id_Personne=".$_SESSION['Id_Personne']." 
													AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteResponsablePlateforme.",".$IdPosteOperateurSaisieRH.",".$IdPosteResponsableHSE.",".$IdPosteCoordinateurSecurite.")
												)
											)>0
											
											OR 
											(
												SELECT COUNT(Id)
												FROM new_competences_personne_prestation
												WHERE new_competences_personne_prestation.Id_Personne=rh_personne_at.Id_Personne
												AND Date_Debut<='".date('Y-m-d')."'
												AND (Date_Fin<='0001-01-01' OR Date_Fin>='".date('Y-m-d')."')
												AND CONCAT(Id_Prestation,'_',Id_Pole) IN 
												(
													SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
													FROM new_competences_personne_poste_prestation 
													WHERE Id_Personne=".$_SESSION["Id_Personne"]."
													AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
												)
											)>0
										) ";
										
							}
							$requetePersonne.="ORDER BY Personne ASC";
						$resultPersonne=mysqli_query($bdd,$requetePersonne);
						$NbPersonne=mysqli_num_rows($resultPersonne);
						
						$personne=$_SESSION['FiltreAccidentT_Personne'];
						if($_POST){$personne=$_POST['personne'];}
						$_SESSION['FiltreAccidentT_Personne']= $personne;
						
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
						$arretTravail=$_SESSION['FiltreAccidentT_ArretTravail'];
						if($_POST){$arretTravail=$_POST['arretTravail'];}
						$_SESSION['FiltreAccidentT_ArretTravail']=$arretTravail;
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
						$mois=$_SESSION['FiltreAccidentT_Mois'];
						if($_POST){$mois=$_POST['mois'];}
						$_SESSION['FiltreAccidentT_Mois']=$mois;
						
						for($i=0;$i<=11;$i++){
							$numMois=$i+1;
							if($numMois<10){$numMois="0".$numMois;}
							echo "<option value='".$numMois."'";
							if($mois== ($i+1)){echo " selected ";}
							echo ">".$arrayMois[$i]."</option>\n";
						}
						
						$annee=$_SESSION['FiltreAccidentT_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						if($annee==""){$annee=date("Y");}
						$_SESSION['FiltreAccidentT_Annee']=$annee;
					?>
				</select>
				<?php
					$MoisCumules=$_SESSION['FiltreAccidentT_MoisCumules'];
					if($_POST){
						if(isset($_POST['MoisCumules'])){$MoisCumules="checked";}else{$MoisCumules="";}				
					}
					$_SESSION['FiltreAccidentT_MoisCumules']=$MoisCumules;
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
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="8"></td></tr>
	<?php
		$requeteAnalyse="SELECT rh_personne_at.Id ";
		$requete2="SELECT rh_personne_at.Id,DateCreation,DateAT,HeureAT,Id_Metier,Id_Lieu_AT,Activite,CommentaireNature,
			rh_personne_at.Id_Prestation,rh_personne_at.Id_Pole,ArretDeTravail, 
			(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
			(SELECT Libelle FROM rh_lieu_at WHERE rh_lieu_at.Id=Id_Lieu_AT) AS LieuAT,
			(SELECT LibelleEN FROM rh_lieu_at WHERE rh_lieu_at.Id=Id_Lieu_AT) AS LieuATEN,
			(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_at.Id_Prestation) AS UER,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_at.Id_Personne) AS Personne, 
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_at.Id_Createur) AS Demandeur ";
		$requete=" 
			FROM rh_personne_at
			WHERE Suppr=0 
			";
		if(DroitsFormation1Plateforme("17",array($IdPosteDirectionOperation,$IdPosteChargeMissionOperation,$IdPosteResponsableQualite))){
			
		}
		else{
			$requete.="AND
					(
					(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_at.Id_Prestation) IN (
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteResponsablePlateforme.",".$IdPosteOperateurSaisieRH.",".$IdPosteResponsableHSE.",".$IdPosteCoordinateurSecurite.")
					)
					
					OR 
					(
						CONCAT(rh_personne_at.Id_Prestation,'_',rh_personne_at.Id_Pole) IN 
						(
							SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
						)
					)
				) ";
					
		}
		if($_SESSION['FiltreAccidentT_Mois']<>0){
			if($_SESSION['FiltreAccidentT_MoisCumules']<>""){
				$requete.=" AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))>='".$_SESSION['FiltreAccidentT_Annee'].'_'.$_SESSION['FiltreAccidentT_Mois']."' 
							AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))<='".$_SESSION['FiltreAccidentT_Annee']."_12' ";
			}
			else{
				$requete.=" AND CONCAT(YEAR(DateAT),'_',IF(MONTH(DateAT)<10,CONCAT('0',MONTH(DateAT)),MONTH(DateAT)))='".$_SESSION['FiltreAccidentT_Annee'].'_'.$_SESSION['FiltreAccidentT_Mois']."' ";
			}
		}
		else{
			$requete.=" AND YEAR(DateAT)='".$_SESSION['FiltreAccidentT_Annee']."' ";
		}
		
		if($_SESSION['FiltreAccidentT_ArretTravail']==1){
			$requete.=" AND  ArretDeTravail=1 ";
		}
		elseif($_SESSION['FiltreAccidentT_ArretTravail']==-1){
			$requete.=" AND  ArretDeTravail=0 ";
		}
		if($_SESSION['FiltreAccidentT_UER']<>0){
			$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_at.Id_Prestation)=".$_SESSION['FiltreAccidentT_UER']." ";
		}
		if($_SESSION['FiltreAccidentT_Personne']<>0){
			$requete.="AND rh_personne_at.Id_Personne=".$_SESSION['FiltreAccidentT_Personne']." ";
		}
		$requeteOrder="";
		if($_SESSION['TriAccidentT_General']<>""){
			$requeteOrder="ORDER BY ".substr($_SESSION['TriAccidentT_General'],0,-1);
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
					<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=Id"><?php if($_SESSION["Langue"]=="FR"){echo "N° AT";}else{echo "Accident no";} ?><?php if($_SESSION['TriAccidentT_Id']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAccidentT_Id']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="6%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=UER"><?php if($_SESSION["Langue"]=="FR"){echo "UER (lieu accident)";}else{echo "UER (accident location)";} ?><?php if($_SESSION['TriAccidentT_UER']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAccidentT_UER']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=Personne"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriAccidentT_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAccidentT_Personne']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=DateCreation"><?php if($_SESSION["Langue"]=="FR"){echo "Date déclaration";}else{echo "Declaration date";} ?><?php if($_SESSION['TriAccidentT_DateCreation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAccidentT_DateCreation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=Demandeur"><?php if($_SESSION["Langue"]=="FR"){echo "Déclaré par";}else{echo "Declared by";} ?><?php if($_SESSION['TriAccidentT_Demandeur']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAccidentT_Demandeur']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=DateAT"><?php if($_SESSION["Langue"]=="FR"){echo "Date AT";}else{echo "Date accident at work";} ?><?php if($_SESSION['TriAccidentT_DateAT']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAccidentT_DateAT']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=HeureAT"><?php if($_SESSION["Langue"]=="FR"){echo "Heure AT";}else{echo "Time accident at work";} ?><?php if($_SESSION['TriAccidentT_HeureAT']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAccidentT_HeureAT']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=ArretDeTravail"><?php if($_SESSION["Langue"]=="FR"){echo "Arrêt de travail";}else{echo "Work stopping";} ?><?php if($_SESSION['TriAccidentT_ArretDeTravail']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAccidentT_ArretDeTravail']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="12%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=Metier"><?php if($_SESSION["Langue"]=="FR"){echo "Metier";}else{echo "Job";} ?><?php if($_SESSION['TriAccidentT_Metier']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAccidentT_Metier']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=LieuAT"><?php if($_SESSION["Langue"]=="FR"){echo "Lieu";}else{echo "Place";} ?><?php if($_SESSION['TriAccidentT_LieuAT']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAccidentT_LieuAT']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=Activite"><?php if($_SESSION["Langue"]=="FR"){echo "Activité";}else{echo "Activity";} ?><?php if($_SESSION['TriAccidentT_Activite']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAccidentT_Activite']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AT.php?Menu=<?php echo $Menu; ?>&Tri=CommentaireNature"><?php if($_SESSION["Langue"]=="FR"){echo "Nature";}else{echo "Nature";} ?><?php if($_SESSION['TriAccidentT_CommentaireNature']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAccidentT_CommentaireNature']=="ASC"){echo "&darr;";}?></a></td>
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
						<td><a style="color:#3e65fa;" href="javascript:OuvreFenetreModif(<?php echo $Menu; ?>,<?php echo $row['Id']; ?>)"><?php echo stripslashes($row['Id']);?></a></td>
						<td><?php echo stripslashes($row['UER']);?></td>
						<td><?php echo stripslashes($row['Personne']);?></td>
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
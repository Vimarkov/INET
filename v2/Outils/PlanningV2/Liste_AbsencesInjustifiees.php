<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModif(Menu,Id)
		{var w=window.open("Modif_AbsenceInjustifiee.php?Mode=M&Id="+Id+"&Menu="+Menu+"&TDB="+document.getElementById('TDB').value+"&OngletTDB="+document.getElementById('OngletTDB').value,"PageHS","status=no,menubar=no,width=1000,height=400");
		w.focus();
		}
	function OuvreFenetreSuppr(Menu,Id)
		{var w=window.open("Modif_AbsenceInjustifiee.php?Mode=S&Id="+Id+"&Menu="+Menu+"&TDB="+document.getElementById('TDB').value+"&OngletTDB="+document.getElementById('OngletTDB').value,"PageHS","status=no,menubar=no,width=1000,height=400");
		w.focus();
		}
	function OuvreFenetreExcel(Menu)
		{window.open("Export_AbsencesInjustifiees.php?Menu="+document.getElementById('Menu').value,"PageExcel","status=no,menubar=no,width=900,height=450");}
	function CocherValide(){
		if(document.getElementById('check_Valide').checked==true){
			var elements = document.getElementsByClassName('check');
			for (i=0; i<elements.length; i++){
			  elements[i].checked=true;
			}
		}
		else{
			var elements = document.getElementsByClassName('check');
			for (i=0; i<elements.length; i++){
			  elements[i].checked=false;
			}
		}
	}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

if(isset($_GET['Tri'])){
	$tab = array("Id","Personne","Contrat","Prestation","Pole","DateCreation","Demandeur","Prevue","Metier");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriRHAbsences_General']= str_replace($tri." ASC,","",$_SESSION['TriRHAbsences_General']);
			$_SESSION['TriRHAbsences_General']= str_replace($tri." DESC,","",$_SESSION['TriRHAbsences_General']);
			$_SESSION['TriRHAbsences_General']= str_replace($tri." ASC","",$_SESSION['TriRHAbsences_General']);
			$_SESSION['TriRHAbsences_General']= str_replace($tri." DESC","",$_SESSION['TriRHAbsences_General']);
			if($_SESSION['TriRHAbsences_'.$tri]==""){$_SESSION['TriRHAbsences_'.$tri]="ASC";$_SESSION['TriRHAbsences_General'].= $tri." ".$_SESSION['TriRHAbsences_'.$tri].",";}
			elseif($_SESSION['TriRHAbsences_'.$tri]=="ASC"){$_SESSION['TriRHAbsences_'.$tri]="DESC";$_SESSION['TriRHAbsences_General'].= $tri." ".$_SESSION['TriRHAbsences_'.$tri].",";}
			else{$_SESSION['TriRHAbsences_'.$tri]="";}
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
?>

<form class="test" action="Liste_AbsencesInjustifiees.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $TDB; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $OngletTDB; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#f561a4;">
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
						
					if($LangueAffichage=="FR"){echo "Liste des absences injustifiées";}else{echo "List of unjustified absences";}
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
			<td width="15%" class="Libelle" <?php if($Menu=="2"){echo "style='display:none;'";} ?>>
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
				$resultPrestation=mysqli_query($bdd,$requeteSite);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationSelect = 0;
				$Selected = "";
				
				$PrestationSelect=$_SESSION['FiltreRHAbsences_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestations'];}
				$_SESSION['FiltreRHAbsences_Prestation']=$PrestationSelect;	
				
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
			<td width="15%" class="Libelle" <?php if($Menu=="2"){echo "style='display:none;'";} ?>>
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
				$resultPole=mysqli_query($bdd,$requetePole);
				$nbPole=mysqli_num_rows($resultPole);
				
				$PoleSelect=$_SESSION['FiltreRHAbsences_Pole'];
				if($_POST){$PoleSelect=$_POST['pole'];}
				$_SESSION['FiltreRHAbsences_Pole']=$PoleSelect;
				
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
						$mois=$_SESSION['FiltreRHAbsences_Mois'];
						if($_POST){$mois=$_POST['mois'];}
						$_SESSION['FiltreRHAbsences_Mois']=$mois;
						
						for($i=0;$i<=11;$i++){
							$numMois=$i+1;
							if($numMois<10){$numMois="0".$numMois;}
							echo "<option value='".$numMois."'";
							if($mois== ($i+1)){echo " selected ";}
							echo ">".$arrayMois[$i]."</option>\n";
						}
						
						$annee=$_SESSION['FiltreRHAbsences_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						if($annee==""){$annee=date("Y");}
						$_SESSION['FiltreRHAbsences_Annee']=$annee;
					?>
				</select>
				<?php
					$MoisCumules=$_SESSION['FiltreRHAbsences_MoisCumules'];
					if($_POST){
						if(isset($_POST['MoisCumules'])){$MoisCumules="checked";}else{$MoisCumules="";}				
					}
					$_SESSION['FiltreRHAbsences_MoisCumules']=$MoisCumules;
				?>
				<input type="checkbox" id="MoisCumules" name="MoisCumules" value="MoisCumules" <?php echo $MoisCumules; ?>><?php if($_SESSION["Langue"]=="FR"){echo "Jusqu'à la fin de l'année";}else{echo "Until the end of the year";} ?> &nbsp;&nbsp;
			</td>
			<td width="8%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Année :";}else{echo "Year :";} ?>
				<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
			</td>
			<td width="8%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prévenue :";}else{echo "Warned :";} ?>
					<?php
						$Prevue=$_SESSION['FiltreRHAbsences_Prevue'];
						$NonPrevue=$_SESSION['FiltreRHAbsences_NonPrevue'];
						if($_POST){
							if(isset($_POST['Prevue'])){$Prevue="checked";}else{$Prevue="";}
							if(isset($_POST['NonPrevue'])){$NonPrevue="checked";}else{$NonPrevue="";}
						}
						$_SESSION['FiltreRHAbsences_Prevue']=$Prevue;
						$_SESSION['FiltreRHAbsences_NonPrevue']=$NonPrevue;
					?>
					<input type="checkbox" id="Prevue" name="Prevue" value="Prevue" <?php echo $Prevue; ?>><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?> &nbsp;&nbsp;
					<input type="checkbox" id="NonPrevue" name="NonPrevue" value="NonPrevue" <?php echo $NonPrevue; ?>><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?> &nbsp;&nbsp;
			</td>
			<td width="5%">
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
			</td>
			<td width="5%">
				&nbsp;&nbsp;&nbsp;
				<a href="javascript:OuvreFenetreExcel()">
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
										FROM rh_personne_demandeabsence
										LEFT JOIN new_rh_etatcivil
										ON new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Personne
										WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_demandeabsence.Id_Prestation) IN 
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
								FROM rh_personne_demandeabsence
								LEFT JOIN new_rh_etatcivil
								ON new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Personne
								WHERE CONCAT(rh_personne_demandeabsence.Id_Prestation,'_',rh_personne_demandeabsence.Id_Pole) IN 
									(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
									FROM new_competences_personne_poste_prestation 
									WHERE Id_Personne=".$_SESSION["Id_Personne"]."
									AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
									)
								ORDER BY Personne ASC";
						}
					
						$resultPersonne=mysqli_query($bdd,$requetePersonne);
						$NbPersonne=mysqli_num_rows($resultPersonne);
						
						$personne=$_SESSION['FiltreRHAbsences_Personne'];
						if($_POST){$personne=$_POST['personne'];}
						$_SESSION['FiltreRHAbsences_Personne']= $personne;
						
						while($rowPersonne=mysqli_fetch_array($resultPersonne))
						{
							echo "<option value='".$rowPersonne['Id']."'";
							if ($personne == $rowPersonne['Id']){echo " selected ";}
							echo ">".$rowPersonne['Personne']."</option>\n";
						}
					?>
				</select>
			</td>
			<td width="15%" class="Libelle" <?php if($Menu==2){echo "style='display:none;'";} ?>>
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Type d'absence :";}else{echo "Type of absence :";} ?>
				<select id="typeAbsence" style="width:100px;" name="typeAbsence" onchange="submit();">
					<?php 
						$typeAbsence=$_SESSION['FiltreRHAbsences_TypeAbsence'];
						if($_POST){$typeAbsence=$_POST['typeAbsence'];}
						$_SESSION['FiltreRHAbsences_TypeAbsence']= $typeAbsence;
					?>
					<option value="" <?php if($typeAbsence==""){echo "selected";} ?>></option>
					<option value="0" <?php if($typeAbsence=="0"){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "ABS : Absences injustifiées";}else{echo "ABS : Unjustified absences";} ?></option>
					<?php
						$req="SELECT Id, CodePlanning, Libelle FROM rh_typeabsence WHERE Suppr=0 ORDER BY CodePlanning ";
					
						$resultTypeAbsence=mysqli_query($bdd,$req);
						$NbPersonne=mysqli_num_rows($resultTypeAbsence);
						
						
						
						while($rowTypeAbsence=mysqli_fetch_array($resultTypeAbsence))
						{
							echo "<option value='".$rowTypeAbsence['Id']."'";
							if ($typeAbsence == $rowTypeAbsence['Id']){echo " selected ";}
							echo ">".$rowTypeAbsence['CodePlanning']." : ".$rowTypeAbsence['Libelle']."</option>\n";
						}
					?>
				</select>
			</td>
			<td width="15%" colspan="3" class="Libelle" <?php if($Menu==2){echo "style='display:none;'";} ?>>
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Etat :";}else{echo "State :";} ?>
					<?php
						$PrisEnCompte=$_SESSION['FiltreRHAbsences_EtatPrisEnCompte'];
						$NonPrisEnCompte=$_SESSION['FiltreRHAbsences_EtatNonPrisEnCompte'];
						$Supprime=$_SESSION['FiltreRHAbsences_Supprime'];
						if($_POST){
							if(isset($_POST['PrisEnCompte'])){$PrisEnCompte="checked";}else{$PrisEnCompte="";}
							if(isset($_POST['NonPrisEnCompte'])){$NonPrisEnCompte="checked";}else{$NonPrisEnCompte="";}
							if(isset($_POST['Supprime'])){$Supprime="checked";}else{$Supprime="";}
						}
						$_SESSION['FiltreRHAbsences_EtatPrisEnCompte']=$PrisEnCompte;
						$_SESSION['FiltreRHAbsences_EtatNonPrisEnCompte']=$NonPrisEnCompte;
						$_SESSION['FiltreRHAbsences_Supprime']=$Supprime;
					?>
					<input type="checkbox" id="NonPrisEnCompte" name="NonPrisEnCompte" value="NonPrisEnCompte" <?php echo $NonPrisEnCompte; ?>><?php if($_SESSION["Langue"]=="FR"){echo "NON PRIS EN COMPTE";}else{echo "NOT TAKEN INTO ACCOUNT";} ?> &nbsp;&nbsp;
					<input type="checkbox" id="PrisEnCompte" name="PrisEnCompte" value="PrisEnCompte" <?php echo $PrisEnCompte; ?>><?php if($_SESSION["Langue"]=="FR"){echo "PRIS EN COMPTE";}else{echo "TAKEN INTO ACCOUNT";} ?> &nbsp;&nbsp;
					<input type="checkbox" id="Supprime" name="Supprime" value="Supprime" <?php echo $Supprime; ?>><?php if($_SESSION["Langue"]=="FR"){echo "SUPPRIME";}else{echo "DELETED";} ?> &nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td valign="top" colspan="8" class="Libelle" <?php if($Menu<>4){echo "style='display:none;'";} ?>>
				<?php if($LangueAffichage=="FR"){echo "Responsable Projet";}else{echo "Project manager";}?> :<br>
						<?php
						
							$Id_RespProjet=$_SESSION['FiltreRHAbsences_RespProjet'];
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
							$_SESSION['FiltreRHAbsences_RespProjet']=$Id_RespProjet;
	
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
									$checkboxes = explode(',',$_SESSION['FiltreRHAbsences_RespProjet']);
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
			$req="SELECT rh_personne_demandeabsence.Id
					FROM rh_personne_demandeabsence
					WHERE Suppr=0 
					AND Conge=0 
					AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_demandeabsence.Id_Prestation) IN 
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
					)
					AND rh_personne_demandeabsence.DatePriseEnCompteRH<='0001-01-01'
					AND (
						SELECT COUNT(rh_absence.Id)
						FROM rh_absence
						WHERE CONCAT(YEAR(rh_absence.DateDebut),'_',IF(MONTH(rh_absence.DateDebut)<10,CONCAT('0',MONTH(rh_absence.DateDebut)),MONTH(rh_absence.DateDebut)))<'".$_SESSION['FiltreRHAbsences_Annee'].'_'.$_SESSION['FiltreRHAbsences_Mois']."' 
						AND rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id
					)>0
					";
			$result=mysqli_query($bdd,$req);
			$nbResulta=mysqli_num_rows($result);
			if($nbResulta>0){
				echo "<tr>
						<td colspan='4' align='right' style='color:red'><span class='blink_me'><img width='25px' src='../../Images/attention.png'/></span>";
				if($_SESSION["Langue"]=="FR"){
					echo " Il reste des absences à traiter sur les mois précédents &nbsp;";
				}
				else{
					echo " There are still absences to deal with in previous months &nbsp;";
				}
				echo "
						</td>
						</tr>";
			}
		}
		
		$requeteAnalyse="SELECT rh_personne_demandeabsence.Id ";
		$requete2="SELECT rh_personne_demandeabsence.Id,rh_personne_demandeabsence.DateCreation,rh_personne_demandeabsence.RealiseParRH,
			(SELECT (SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
			FROM rh_personne_contrat
			WHERE rh_personne_contrat.Suppr=0
			AND rh_personne_contrat.DateDebut<=rh_personne_demandeabsence.DateCreation
			AND (rh_personne_contrat.DateFin>=rh_personne_demandeabsence.DateCreation OR rh_personne_contrat.DateFin<='0001-01-01')
			AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=rh_personne_demandeabsence.Id_Personne
			ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS Contrat,
			(SELECT (SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=rh_personne_contrat.Id_Metier)
			FROM rh_personne_contrat
			WHERE rh_personne_contrat.Suppr=0
			AND rh_personne_contrat.DateDebut<=rh_personne_demandeabsence.DateCreation
			AND (rh_personne_contrat.DateFin>=rh_personne_demandeabsence.DateCreation OR rh_personne_contrat.DateFin<='0001-01-01')
			AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
			AND rh_personne_contrat.Id_Personne=rh_personne_demandeabsence.Id_Personne
			ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS Metier,
			rh_personne_demandeabsence.DatePriseEnCompteN1,rh_personne_demandeabsence.DatePriseEnCompteRH,Prevue,DatePriseEnCompteN2,
			rh_personne_demandeabsence.Id_Pole,rh_personne_demandeabsence.Id_Prestation,
			(SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_demandeabsence.Id_Prestation) AS Prestation,
			(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=rh_personne_demandeabsence.Id_Pole) AS Pole,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Personne) AS Personne, 
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Createur) AS Demandeur,
			rh_personne_demandeabsence.Suppr			";
		$requete=" FROM rh_personne_demandeabsence
					WHERE Conge=0
					AND 
					";
		if($Menu==4){
			if(DroitsFormationPlateforme($TableauIdPostesRH)){
				$requete.="(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_demandeabsence.Id_Prestation) IN 
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
					)";
			}
			
			if($_SESSION['FiltreRHAbsences_EtatPrisEnCompte']<>"" || $_SESSION['FiltreRHAbsences_EtatNonPrisEnCompte']<>""){
				$requete.=" AND ( ";
				if($_SESSION['FiltreRHAbsences_EtatPrisEnCompte']<>""){
					$requete.=" rh_personne_demandeabsence.DatePriseEnCompteRH>'0001-01-01' OR ";
				}
				if($_SESSION['FiltreRHAbsences_EtatNonPrisEnCompte']<>""){
					$requete.=" rh_personne_demandeabsence.DatePriseEnCompteRH<='0001-01-01' OR ";
				}
				$requete=substr($requete,0,-3);
				$requete.=" ) ";
			}
		}
		elseif($Menu==3){
			$requete.="CONCAT(rh_personne_demandeabsence.Id_Prestation,'_',rh_personne_demandeabsence.Id_Pole) IN 
						(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
						FROM new_competences_personne_poste_prestation 
						WHERE Id_Personne=".$_SESSION["Id_Personne"]."
						AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
						)";
			if($_SESSION['FiltreRHAbsences_EtatPrisEnCompte']<>"" || $_SESSION['FiltreRHAbsences_EtatNonPrisEnCompte']<>""){
				$requete.=" AND ( ";
				if($_SESSION['FiltreRHAbsences_EtatPrisEnCompte']<>""){
					$requete.=" ((rh_personne_demandeabsence.DatePriseEnCompteN1>'0001-01-01' AND 
							CONCAT(rh_personne_demandeabsence.Id_Prestation,'_',rh_personne_demandeabsence.Id_Pole) IN 
							(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteChefEquipe.") 
							)
					)
					OR 
					(rh_personne_demandeabsence.DatePriseEnCompteN2>'0001-01-01' AND 
							CONCAT(rh_personne_demandeabsence.Id_Prestation,'_',rh_personne_demandeabsence.Id_Pole) IN 
							(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteCoordinateurEquipe.") 
							)
					)
					) OR ";
				}
				if($_SESSION['FiltreRHAbsences_EtatNonPrisEnCompte']<>""){
					$requete.=" ((rh_personne_demandeabsence.DatePriseEnCompteN1<='0001-01-01' AND 
							CONCAT(rh_personne_demandeabsence.Id_Prestation,'_',rh_personne_demandeabsence.Id_Pole) IN 
							(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteChefEquipe.") 
							)
					)
					OR 
					(rh_personne_demandeabsence.DatePriseEnCompteN2<='0001-01-01' AND 
							CONCAT(rh_personne_demandeabsence.Id_Prestation,'_',rh_personne_demandeabsence.Id_Pole) IN 
							(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteCoordinateurEquipe.") 
							)
					)
					) OR ";
				}
				$requete=substr($requete,0,-3);
				$requete.=" ) ";
			}
		}
		elseif($Menu==2){
			$requete.="rh_personne_demandeabsence.Id_Personne=".$_SESSION['Id_Personne']." ";
		}
		if($_SESSION['FiltreRHAbsences_Supprime']<>""){
			$requete.=" AND rh_personne_demandeabsence.Suppr=1 ";
		}
		else{
			$requete.=" AND rh_personne_demandeabsence.Suppr=0 ";
		}
		if($_SESSION['FiltreRHAbsences_Prestation']<>0){
			$requete.=" AND rh_personne_demandeabsence.Id_Prestation=".$_SESSION['FiltreRHAbsences_Prestation']." ";
			if($_SESSION['FiltreRHAbsences_Pole']<>0){
				$requete.=" AND rh_personne_demandeabsence.Id_Pole=".$_SESSION['FiltreRHAbsences_Pole']." ";
			}
		}
		if($Menu<>2){
			if($_SESSION['FiltreRHAbsences_Personne']<>0 && $_SESSION['FiltreRHAbsences_Personne']<>""){
				$requete.=" AND rh_personne_demandeabsence.Id_Personne=".$_SESSION['FiltreRHAbsences_Personne']." ";
			}
		}
		if($_SESSION['FiltreRHAbsences_Mois']<>0){
			if($_SESSION['FiltreRHAbsences_MoisCumules']<>""){
				$requete.="AND (
						SELECT COUNT(rh_absence.Id)
						FROM rh_absence
						WHERE 
						((CONCAT(YEAR(rh_absence.DateDebut),'_',IF(MONTH(rh_absence.DateDebut)<10,CONCAT('0',MONTH(rh_absence.DateDebut)),MONTH(rh_absence.DateDebut)))<='".$_SESSION['FiltreRHAbsences_Annee'].'_'.$_SESSION['FiltreRHAbsences_Mois']."' 
						AND CONCAT(YEAR(rh_absence.DateFin),'_',IF(MONTH(rh_absence.DateFin)<10,CONCAT('0',MONTH(rh_absence.DateFin)),MONTH(rh_absence.DateFin)))>='".$_SESSION['FiltreRHAbsences_Annee'].'_'.$_SESSION['FiltreRHAbsences_Mois']."')
							OR
						(CONCAT(YEAR(rh_absence.DateDebut),'_',IF(MONTH(rh_absence.DateDebut)<10,CONCAT('0',MONTH(rh_absence.DateDebut)),MONTH(rh_absence.DateDebut)))>='".$_SESSION['FiltreRHAbsences_Annee'].'_'.$_SESSION['FiltreRHAbsences_Mois']."'))
						AND rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id
					)>0 ";

			}
			else{
				$requete.="AND (
							SELECT COUNT(rh_absence.Id)
							FROM rh_absence
							WHERE CONCAT(YEAR(rh_absence.DateDebut),'_',IF(MONTH(rh_absence.DateDebut)<10,CONCAT('0',MONTH(rh_absence.DateDebut)),MONTH(rh_absence.DateDebut)))<='".$_SESSION['FiltreRHAbsences_Annee'].'_'.$_SESSION['FiltreRHAbsences_Mois']."' 
							AND CONCAT(YEAR(rh_absence.DateFin),'_',IF(MONTH(rh_absence.DateFin)<10,CONCAT('0',MONTH(rh_absence.DateFin)),MONTH(rh_absence.DateFin)))>='".$_SESSION['FiltreRHAbsences_Annee'].'_'.$_SESSION['FiltreRHAbsences_Mois']."'
							AND rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id
						)>0 ";
			}
		}
		else{
			$requete.="AND (
							SELECT COUNT(rh_absence.Id)
							FROM rh_absence
							WHERE YEAR(rh_absence.DateDebut)<='".$_SESSION['FiltreRHAbsences_Annee']."' 
							AND YEAR(rh_absence.DateFin)>='".$_SESSION['FiltreRHAbsences_Annee']."'
							AND rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id
						)>0 ";
		}
		
		if($_SESSION['FiltreRHAbsences_Prevue']<>"" || $_SESSION['FiltreRHAbsences_NonPrevue']<>""){
			$requete.=" AND ( ";
			if($_SESSION['FiltreRHAbsences_Prevue']<>""){
				$requete.=" rh_personne_demandeabsence.Prevue=1 OR ";
			}
			if($_SESSION['FiltreRHAbsences_NonPrevue']<>""){
				$requete.=" rh_personne_demandeabsence.Prevue=0 OR ";
			}
			$requete=substr($requete,0,-3);
			$requete.=" ) ";
		}
		if($Menu==4){
			if($_SESSION['FiltreRHAbsences_RespProjet']<>""){
				$requete.="AND CONCAT(rh_personne_demandeabsence.Id_Prestation,'_',rh_personne_demandeabsence.Id_Pole) 
							IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
								FROM new_competences_personne_poste_prestation
								WHERE Id_Personne IN (".$_SESSION['FiltreRHAbsences_RespProjet'].")
								AND Id_Poste IN (".$IdPosteResponsableProjet.")
							)
							";
			}
		}
		if($_SESSION['FiltreRHAbsences_TypeAbsence']<>""){
			$requete.="AND (
							SELECT COUNT(rh_absence.Id)
							FROM rh_absence
							WHERE IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial)=".$_SESSION['FiltreRHAbsences_TypeAbsence']." 
							AND rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id
							AND rh_absence.Suppr=0
						)>0 ";
		}
		
		$requeteOrder="";
		if($_SESSION['TriRHAbsences_General']<>""){
			$requeteOrder="ORDER BY ".substr($_SESSION['TriRHAbsences_General'],0,-1);
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
				if($page>1){echo "<b> <a style='color:#00599f;' href='Liste_AbsencesInjustifiees.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=0'><<</a> </b>";}
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
							echo "<b> <a style='color:#00599f;' href='Liste_AbsencesInjustifiees.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($page<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_AbsencesInjustifiees.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="5%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AbsencesInjustifiees.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Id"><?php if($_SESSION["Langue"]=="FR"){echo "N° déclaration";}else{echo "Declaration number";} ?><?php if($_SESSION['TriRHAbsences_Id']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAbsences_Id']=="ASC"){echo "&darr;";}?></a></td>
					<?php if($Menu==3 || $Menu==4){ ?>
					<td class="EnTeteTableauCompetences" width="15%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AbsencesInjustifiees.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Personne"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriRHAbsences_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAbsences_Personne']=="ASC"){echo "&darr;";}?></a></td>
					<?php } ?>
					<?php if($Menu==4){ ?>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AbsencesInjustifiees.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Contrat"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?><?php if($_SESSION['TriRHAbsences_Contrat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAbsences_Contrat']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AbsencesInjustifiees.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Metier"><?php if($_SESSION["Langue"]=="FR"){echo "Métier";}else{echo "Job";} ?><?php if($_SESSION['TriRHAbsences_Metier']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAbsences_Metier']=="ASC"){echo "&darr;";}?></a></td>
					<?php } ?>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AbsencesInjustifiees.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Prestation"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?><?php if($_SESSION['TriRHAbsences_Prestation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAbsences_Prestation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AbsencesInjustifiees.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Pole"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle";}else{echo "Pole";} ?><?php if($_SESSION['TriRHAbsences_Pole']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAbsences_Pole']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AbsencesInjustifiees.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=DateCreation"><?php if($_SESSION["Langue"]=="FR"){echo "Date création";}else{echo "Creation Date";} ?><?php if($_SESSION['TriRHAbsences_DateCreation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAbsences_DateCreation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AbsencesInjustifiees.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Demandeur"><?php if($_SESSION["Langue"]=="FR"){echo "Demandeur";}else{echo "Applicant";} ?><?php if($_SESSION['TriRHAbsences_Demandeur']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAbsences_Demandeur']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Date début";}else{echo "Start date";} ?></td>
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Date fin";}else{echo "End date";} ?></td>
					<td class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Type";}else{echo "Type";} ?></td>
					<td class="EnTeteTableauCompetences" style="text-align:center;" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AbsencesInjustifiees.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Prevue"><?php if($_SESSION["Langue"]=="FR"){echo "Prévenue";}else{echo "warned";} ?><?php if($_SESSION['TriRHAbsences_Prevue']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAbsences_Prevue']=="ASC"){echo "&darr;";}?></a></td>
					<?php if($Menu==3){ ?>
							<td class='EnTeteTableauCompetences' width="15%" style="text-align:center;">
								<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Pris en compte";}else{echo "Taken into account";}?>" name="priseEnCompte" value="<?php if($_SESSION["Langue"]=="FR"){echo "Pris en compte";}else{echo "Taken into account";} ?>"><br>
								<input type='checkbox' id="check_Valide" name="check_Valide" value="" checked onchange="CocherValide()">
							</td>
							<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Supp.";}else{echo "Delete";} ?></td>
					<?php }
						elseif($Menu==4){ ?>
							<td class='EnTeteTableauCompetences' width="15%" style="text-align:center;">
								<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Pris en compte";}else{echo "Taken into account";}?>" name="priseEnCompte" value="<?php if($_SESSION["Langue"]=="FR"){echo "Pris en compte";}else{echo "Taken into account";} ?>"><br>
								<input type='checkbox' id="check_Valide" name="check_Valide" value="" onchange="CocherValide()">
							</td>
							<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Supp.";}else{echo "Delete";} ?></td>
					<?php }
					?>
				</tr>
	<?php
			if(isset($_POST['priseEnCompte'])){
				while($row=mysqli_fetch_array($result)){
					if (isset($_POST['checkPriseEnCompte_'.$row['Id'].''])){
						if($Menu==3){
							if(DroitsPrestationPole(array($IdPosteChefEquipe),$row['Id_Prestation'],$row['Id_Pole'])){
								$requeteUpdate="UPDATE rh_personne_demandeabsence SET 
									DatePriseEnCompteN1='".date('Y-m-d')."'
									WHERE Id=".$row['Id']." ";
								$resultat=mysqli_query($bdd,$requeteUpdate);
							}
							if(DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$row['Id_Prestation'],$row['Id_Pole'])){
								$requeteUpdate="UPDATE rh_personne_demandeabsence SET 
									DatePriseEnCompteN2='".date('Y-m-d')."',
									Id_PriseEnCompteN2=".$_SESSION['Id_Personne']."
									WHERE Id=".$row['Id']." ";
								$resultat=mysqli_query($bdd,$requeteUpdate);
							}
						}
						elseif($Menu==4){
							$requeteUpdate="UPDATE rh_personne_demandeabsence SET 
								DatePriseEnCompteRH='".date('Y-m-d')."'
								WHERE Id=".$row['Id']." ";
							$resultat=mysqli_query($bdd,$requeteUpdate);
						}
						
					}
				}
			}
			$result=mysqli_query($bdd,$requete2.$requete.$requeteOrder.$requete3);
			
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
					
					if($row['Prevue']==0){$couleurEtat="#f51919";$Prevue="";}
					else{$couleurEtat="#60ea34";$Prevue="X";}
					
					$dateDebut="";
					$dateFin="";
					$contenu="";
					$req="SELECT DateDebut,DateFin,Id_TypeAbsenceInitial,Id_TypeAbsenceDefinitif,
							(SELECT CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=Id_TypeAbsenceInitial) AS TypeAbsenceIni,
							(SELECT CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=Id_TypeAbsenceDefinitif) AS TypeAbsenceDef,
							NbJour, HeureDepart, HeureArrivee, NbHeureAbsJour, NbHeureAbsNuit
							FROM rh_absence 
							WHERE Suppr=0 
							AND Id_Personne_DA=".$row['Id']." 
							ORDER BY DateDebut ASC ";
					$resultAbs=mysqli_query($bdd,$req);
					$nbAbs=mysqli_num_rows($resultAbs);
					if($nbAbs>0){
						while($rowAbs=mysqli_fetch_array($resultAbs)){
							if($_SESSION['Langue']=="FR"){
								$dateDebut.=AfficheDateJJ_MM_AAAA($rowAbs['DateDebut']);
								$dateFin.=AfficheDateJJ_MM_AAAA($rowAbs['DateFin']);
								if($rowAbs['Id_TypeAbsenceDefinitif']>0 && $rowAbs['Id_TypeAbsenceDefinitif']<>$rowAbs['Id_TypeAbsenceInitial']){
									
									$contenu.=" (".$rowAbs['NbJour']."";
									if($rowAbs['Id_TypeAbsenceInitial']>0){$contenu.="<del>".$rowAbs['TypeAbsenceIni']."</del>";}
									else{$contenu.="<del>ABS</del>";}
									$contenu.=" ".$rowAbs['TypeAbsenceDef'].")";
								}
								else{
									if($rowAbs['Id_TypeAbsenceInitial']>0){$contenu.=" (".$rowAbs['NbJour']." ".$rowAbs['TypeAbsenceIni'].")";}
									else{$contenu.=" (".$rowAbs['NbJour']." ABS)";}
									
								}
								if($rowAbs['HeureDepart']<>'00:00:00'){$contenu.="<br> Heure début : ".$rowAbs['HeureDepart']." ";}
								if($rowAbs['HeureArrivee']<>'00:00:00'){$contenu.="<br> Heure fin : ".$rowAbs['HeureArrivee']." ";}
								$nbheures=$rowAbs['NbHeureAbsJour']+$rowAbs['NbHeureAbsNuit'];
								if($nbheures>0){
									$contenu.="<br> Nb heures absence : ".$nbheures." ";
								}
								$contenu.="<br>";
								$dateDebut.="<br>";
								$dateFin.="<br>";
							}
							else{
								$contenu.=AfficheDateJJ_MM_AAAA($rowAbs['DateDebut'])." - ".AfficheDateJJ_MM_AAAA($rowAbs['DateFin']);
								if($rowAbs['Id_TypeAbsenceDefinitif']>0 && $rowAbs['Id_TypeAbsenceDefinitif']<>$rowAbs['Id_TypeAbsenceInitial']){
									$contenu.=" (".$rowAbs['NbJour']."";
									if($rowAbs['Id_TypeAbsenceInitial']>0){$contenu.="<del>".$rowAbs['TypeAbsenceIni']."</del>";}
									else{$contenu.="<del>ABS</del>";}
									$contenu.=" ".$rowAbs['NbJour']." ".$rowAbs['TypeAbsenceDef'].")";
								}
								else{
									if($rowAbs['Id_TypeAbsenceInitial']>0){$contenu.=" (".$rowAbs['NbJour']." ".$rowAbs['TypeAbsenceIni'].")";}
									else{$contenu.=" (".$rowAbs['NbJour']." ABS)";}
								}
								if($rowAbs['HeureDepart']<>'00:00:00'){$contenu.="<br> Start time : ".$rowAbs['HeureDepart']." ";}
								if($rowAbs['HeureArrivee']<>'00:00:00'){$contenu.="<br> End time : ".$rowAbs['HeureArrivee']." ";}
								$nbheures=$rowAbs['NbHeureAbsJour']+$rowAbs['NbHeureAbsNuit'];
								if($nbheures>0){
									$contenu.="<br> Number of hours absence : ".$nbheures." ";
								}
								$contenu.="<br>";
								$dateDebut.="<br>";
								$dateFin.="<br>";
							}
						}
					}
		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td align="center"><a style="color:#3e65fa;" href="javascript:OuvreFenetreModif(<?php echo $Menu; ?>,<?php echo $row['Id']; ?>)"><?php echo stripslashes($row['Id']);?></a></td>
						<?php if($Menu==3 || $Menu==4){ ?>
						<td><?php echo stripslashes($row['Personne']);?></td>
						<?php } ?>
						<?php if($Menu==4){ ?>
						<td><?php echo stripslashes($row['Contrat']);?></td>
						<td><?php echo stripslashes($row['Metier']);?></td>
						<?php } ?>
						<td><?php echo substr(stripslashes($row['Prestation']),0,7);?></td>
						<td><?php echo stripslashes($row['Pole']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateCreation']);?></td>
						<td><?php echo stripslashes($row['Demandeur']);?></td>
						<td><?php echo $dateDebut;?></td>
						<td><?php echo $dateFin;?></td>
						<td bgcolor="<?php echo $couleurEtat;?>"><?php echo $contenu;?></td>
						<td align="center"><?php echo $Prevue;?></td>
						<?php if($Menu==3 || $Menu==4){ ?>
						<td align="center">
							<?php 
								if($Menu==3){
									if(($row['DatePriseEnCompteN1']<='0001-01-01' && DroitsPrestationPole(array($IdPosteChefEquipe),$row['Id_Prestation'],$row['Id_Pole']))
										|| ($row['DatePriseEnCompteN2']<='0001-01-01' && DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$row['Id_Prestation'],$row['Id_Pole']))
									){
										echo "<input class='check' type='checkbox' name='checkPriseEnCompte_".$row['Id']."' value='' checked>";
									}
								}
								else{
									if($row['DatePriseEnCompteRH']<='0001-01-01'){
										echo "<input class='check' type='checkbox' name='checkPriseEnCompte_".$row['Id']."' value='' >";
									}
								}
							?>
						</td>
						<td>
							<?php
								//Uniquement si Manager et créé par Manager ou RH 
								if($Menu==4 ||($Menu==3 && DroitsPrestationPole(array($IdPosteChefEquipe),$row['Id_Prestation'],$row['Id_Pole']) && $row['RealiseParRH']==0 && $row['DatePriseEnCompteRH']<='0001-01-01')){
									if($row['Suppr']==0){
							?>
							<a class="LigneTableauRecherchePersonne" style="cursor:pointer;" onclick="if(window.confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir supprimer ?";}else{echo "Are you sure you want to delete ?";} ?>')){OuvreFenetreSuppr('<?php echo $Menu; ?>','<?php echo $row['Id']; ?>');}else{return false;}"><img src="../../Images/Suppression.gif" border="0" title="Supprimer" alt="Suppression"></a>
							<?php
									}
									else{
										echo "X";
									}
								}
							?>
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
		<td height="5"></td>
	</tr>
	<tr>
		<td colspan="4">
	<?php if($Menu==2){
		echo '<table  cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:80%;">';
		if($_SESSION["Langue"]=="FR"){
			$reqAbsVac = "SELECT Id ,Libelle, CodePlanning, InformationSalarie, NbJourAutorise 
						FROM rh_typeabsence 
						WHERE Suppr=0 
						AND InformationSalarie<>''
						ORDER BY Libelle ";
		}
		else{
			$reqAbsVac = "SELECT Id ,LibelleEN AS Libelle, CodePlanning, InformationSalarie, NbJourAutorise 
						FROM rh_typeabsence 
						WHERE Suppr=0 
						AND InformationSalarie<>''
						ORDER BY Libelle ";
		}
		$resultAbsVac=mysqli_query($bdd,$reqAbsVac);
		$nbAbsVac=mysqli_num_rows($resultAbsVac);
		if ($nbAbsVac > 0){
			while($rowAbsVac=mysqli_fetch_array($resultAbsVac)){	
				echo "<tr><td width='15%'>&nbsp;".$rowAbsVac['CodePlanning']." (".$rowAbsVac['Libelle'].") : </td><td width='47%'>".$rowAbsVac['InformationSalarie']."</td></tr>";
			}
		}
		echo '</table>';
	} 
	?>
		</td>
	</tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
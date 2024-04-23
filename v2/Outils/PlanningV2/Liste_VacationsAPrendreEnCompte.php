<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreRefus(Menu,Id){
		if(document.getElementById('Langue').value=="EN"){texte='Are you sure you want to refuse?';}
		else{texte='Etes-vous sûr de vouloir refuser ?';}
		if(window.confirm(texte)){
			var w=window.open("Refuser_Vacation.php?Id="+Id+"&Menu="+Menu+"&TDB="+document.getElementById('TDB').value+"&OngletTDB="+document.getElementById('OngletTDB').value,"PageVacation","status=no,menubar=no,scrollbars=yes,width=800,height=300");
		}			
	}
	function SelectionnerTout(){
		var elements = document.getElementsByClassName("check1");
		if (formulaire.selectAll.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){
				elements[i].checked = true;
			}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){
				elements[i].checked = false;
			}
		}
	}
	function SelectionnerTout2(){
		var elements = document.getElementsByClassName("check2");
		if (formulaire.selectAll2.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){
				elements[i].checked = true;
			}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){
				elements[i].checked = false;
			}
		}
	}
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
if(isset($_GET['Tri'])){
	$tab = array("Id","Personne","Contrat","Prestation","Pole","DateVacation","Vacation","Etat","Modificateur","DateAction","DatePriseEnCompteRH");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriRHVacationEnCompte_General']= str_replace($tri." ASC,","",$_SESSION['TriRHVacationEnCompte_General']);
			$_SESSION['TriRHVacationEnCompte_General']= str_replace($tri." DESC,","",$_SESSION['TriRHVacationEnCompte_General']);
			$_SESSION['TriRHVacationEnCompte_General']= str_replace($tri." ASC","",$_SESSION['TriRHVacationEnCompte_General']);
			$_SESSION['TriRHVacationEnCompte_General']= str_replace($tri." DESC","",$_SESSION['TriRHVacationEnCompte_General']);
			if($_SESSION['TriRHVacationEnCompte_'.$tri]==""){$_SESSION['TriRHVacationEnCompte_'.$tri]="ASC";$_SESSION['TriRHVacationEnCompte_General'].= $tri." ".$_SESSION['TriRHVacationEnCompte_'.$tri].",";}
			elseif($_SESSION['TriRHVacationEnCompte_'.$tri]=="ASC"){$_SESSION['TriRHVacationEnCompte_'.$tri]="DESC";$_SESSION['TriRHVacationEnCompte_General'].= $tri." ".$_SESSION['TriRHVacationEnCompte_'.$tri].",";}
			else{$_SESSION['TriRHVacationEnCompte_'.$tri]="";}
		}
	}
}


$timestamp = mktime(0, 0, 0, $_SESSION['FiltreRHVacationEnCompte_Mois'], 1, $_SESSION['FiltreRHVacationEnCompte_Annee']);

$PremierJour=date('Y-m-d',$timestamp);

//Dernier jour
$DernierJour=date('Y-m-d',strtotime($PremierJour." + 1 month"));
$tabDate = explode('-', $DernierJour);
$DernierJour=date('Y-m-d',mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]));

?>

<form id="formulaire" class="test" action="Liste_VacationsAPrendreEnCompte.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $TDB; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $OngletTDB; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#ffffff;">
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
						
					if($LangueAffichage=="FR"){echo "Liste des changements de vacations aprés le 20 du mois";}else{echo "List of vacations changes after the 20th of the month";}
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
				$resultPrestation=mysqli_query($bdd,$requeteSite);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationSelect = 0;
				$Selected = "";
				
				$PrestationSelect=$_SESSION['FiltreRHVacationEnCompte_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestations'];}
				$_SESSION['FiltreRHVacationEnCompte_Prestation']=$PrestationSelect;	
				
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
				$resultPole=mysqli_query($bdd,$requetePole);
				$nbPole=mysqli_num_rows($resultPole);
				
				$PoleSelect=$_SESSION['FiltreRHVacationEnCompte_Pole'];
				if($_POST){$PoleSelect=$_POST['pole'];}
				$_SESSION['FiltreRHVacationEnCompte_Pole']=$PoleSelect;
				
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
			<td width="15%" class="Libelle" <?php if($Menu==2){echo "style='display:none;'";} ?>>
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?>
				<select id="personne" style="width:100px;" name="personne" onchange="submit();">
					<option value='0'></option>
					<?php
						$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
								CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
							FROM new_rh_etatcivil
							LEFT JOIN rh_personne_mouvement 
							ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
							WHERE rh_personne_mouvement.DateDebut<='".$DernierJour."'
							AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$PremierJour."')
							AND rh_personne_mouvement.EtatValidation=1 
							AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) IN
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
								)
							ORDER BY Personne ASC";
					
						$resultPersonne=mysqli_query($bdd,$requetePersonne);
						$NbPersonne=mysqli_num_rows($resultPersonne);
						
						$personne=$_SESSION['FiltreRHVacationEnCompte_Personne'];
						if($_POST){$personne=$_POST['personne'];}
						$_SESSION['FiltreRHVacationEnCompte_Personne']= $personne;
						
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
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Mois :";}else{echo "Month :";} ?>
				<select id="mois" name="mois" onchange="submit();">
					<option value="0"></option>
					<?php
						if($_SESSION["Langue"]=="FR"){
							$arrayMois=array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
						}
						else{
							$arrayMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
						}
						$mois=$_SESSION['FiltreRHVacationEnCompte_Mois'];
						if($_POST){$mois=$_POST['mois'];}
						$_SESSION['FiltreRHVacationEnCompte_Mois']=$mois;
						
						for($i=0;$i<=11;$i++){
							$numMois=$i+1;
							if($numMois<10){$numMois="0".$numMois;}
							echo "<option value='".$numMois."'";
							if($mois== ($i+1)){echo " selected ";}
							echo ">".$arrayMois[$i]."</option>\n";
						}
						
						$annee=$_SESSION['FiltreRHVacationEnCompte_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						if($annee==""){$annee=date("Y");}
						$_SESSION['FiltreRHVacationEnCompte_Annee']=$annee;
					?>
				</select>
			</td>
			<td width="8%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Année :";}else{echo "Year :";} ?>
				<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
			</td>
			<td width="20%" colspan="3" class="Libelle" <?php if($Menu==2){echo "style='display:none;'";} ?>>
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Etat :";}else{echo "State :";} ?>
					<?php
						$PrisEnCompte=$_SESSION['FiltreRHVacationEnCompte_EtatPrisEnCompte'];
						$NonPrisEnCompte=$_SESSION['FiltreRHVacationEnCompte_EtatNonPrisEnCompte'];
						if($_POST){
							if(isset($_POST['PrisEnCompte'])){$PrisEnCompte="checked";}else{$PrisEnCompte="";}
							if(isset($_POST['NonPrisEnCompte'])){$NonPrisEnCompte="checked";}else{$NonPrisEnCompte="";}
						}
						$_SESSION['FiltreRHVacationEnCompte_EtatPrisEnCompte']=$PrisEnCompte;
						$_SESSION['FiltreRHVacationEnCompte_EtatNonPrisEnCompte']=$NonPrisEnCompte;
					?>
					<input type="checkbox" id="NonPrisEnCompte" name="NonPrisEnCompte" value="NonPrisEnCompte" <?php echo $NonPrisEnCompte; ?>><?php if($_SESSION["Langue"]=="FR"){echo "NON PRIS EN COMPTE";}else{echo "NOT TAKEN INTO ACCOUNT";} ?> &nbsp;&nbsp;
					<input type="checkbox" id="PrisEnCompte" name="PrisEnCompte" value="PrisEnCompte" <?php echo $PrisEnCompte; ?>><?php if($_SESSION["Langue"]=="FR"){echo "PRIS EN COMPTE";}else{echo "TAKEN INTO ACCOUNT";} ?> &nbsp;&nbsp;
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
			<td valign="top" colspan="8" class="Libelle" <?php if($Menu<>4){echo "style='display:none;'";} ?>>
				<?php if($LangueAffichage=="FR"){echo "Responsable Projet";}else{echo "Project manager";}?> :<br>
						<?php
						
							$Id_RespProjet=$_SESSION['FiltreRHVacationEnCompte_RespProjet'];
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
							$_SESSION['FiltreRHVacationEnCompte_RespProjet']=$Id_RespProjet;
	
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
									$checkboxes = explode(',',$_SESSION['FiltreRHVacationEnCompte_RespProjet']);
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
	
		
		$requeteAnalyse="SELECT Id ";

		$requete2="SELECT Id,DateVacation,DateCreation,Suppr,DateSuppr,DatePriseEnCompteRH,AttenteRetourSite,
				(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Prestation,
				(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_Pole) AS Pole,
				(SELECT Nom FROM rh_vacation WHERE rh_vacation.Id=Id_Vacation) AS Vacation,
				(SELECT (SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat)
				FROM rh_personne_contrat
				WHERE rh_personne_contrat.Suppr=0
				AND rh_personne_contrat.DateDebut<=rh_personne_vacation.DateVacation
				AND (rh_personne_contrat.DateFin>=rh_personne_vacation.DateVacation OR rh_personne_contrat.DateFin<='0001-01-01')
				AND rh_personne_contrat.TypeDocument IN ('Nouveau','Avenant')
				AND rh_personne_contrat.Id_Personne=rh_personne_vacation.Id_Personne
				ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS Contrat,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
				IF(Suppr=1,(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Suppr),(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Createur)) AS Modificateur,
				IF(Suppr=1,DateSuppr,DateCreation) AS DateAction,
				IF(Suppr=1,0,1) AS Etat
				";			
		$requete= " FROM
				rh_personne_vacation
			WHERE
				rh_personne_vacation.Id_Personne IN (
					SELECT DISTINCT rh_personne_mouvement.Id_Personne
					FROM rh_personne_mouvement 
					WHERE rh_personne_mouvement.DateDebut<='".$DernierJour."'
					AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$PremierJour."')
					AND rh_personne_mouvement.EtatValidation=1 
					AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) IN
						(
							SELECT Id_Plateforme 
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION['Id_Personne']." 
							AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
						)
				)
			AND (
				(Suppr=0 AND YEAR(DateVacation)='".$_SESSION['FiltreRHVacationEnCompte_Annee']."' AND CONCAT(YEAR(DateCreation),'-',IF(MONTH(DateCreation)<10,CONCAT(0,MONTH(DateCreation)),MONTH(DateCreation)),'-',IF(DAY(DateCreation)<10,CONCAT(0,DAY(DateCreation)),DAY(DateCreation)))>CONCAT(YEAR(DateVacation),'-',IF(MONTH(DateVacation)<10,CONCAT(0,MONTH(DateVacation)),MONTH(DateVacation)),'-21'))
			OR  (Suppr=1 AND YEAR(DateSuppr)='".$_SESSION['FiltreRHVacationEnCompte_Annee']."' AND CONCAT(YEAR(DateSuppr),'-',IF(MONTH(DateSuppr)<10,CONCAT(0,MONTH(DateSuppr)),MONTH(DateSuppr)),'-',IF(DAY(DateSuppr)<10,CONCAT(0,DAY(DateSuppr)),DAY(DateSuppr)))>CONCAT(YEAR(DateVacation),'-',IF(MONTH(DateVacation)<10,CONCAT(0,MONTH(DateVacation)),MONTH(DateVacation)),'-21'))
			
			)
			  ";
		if($_SESSION['FiltreRHVacationEnCompte_Prestation']<>0){
			$requete.=" AND Id_Prestation=".$_SESSION['FiltreRHVacationEnCompte_Prestation']." ";
			if($_SESSION['FiltreRHVacationEnCompte_Pole']<>0){
				$requete.=" AND Id_Pole=".$_SESSION['FiltreRHVacationEnCompte_Pole']." ";
			}
		}
		if($_SESSION['FiltreRHVacationEnCompte_Mois']<>0){
			$requete.="AND  (
						(Suppr=0 AND CONCAT(YEAR(DateVacation),'_',IF(MONTH(DateVacation)<10,CONCAT('0',MONTH(DateVacation)),MONTH(DateVacation)))='".$_SESSION['FiltreRHVacationEnCompte_Annee'].'_'.$_SESSION['FiltreRHVacationEnCompte_Mois']."' )
						OR	
						(Suppr=1 AND CONCAT(YEAR(DateSuppr),'_',IF(MONTH(DateSuppr)<10,CONCAT('0',MONTH(DateSuppr)),MONTH(DateSuppr)))='".$_SESSION['FiltreRHVacationEnCompte_Annee'].'_'.$_SESSION['FiltreRHVacationEnCompte_Mois']."' )
						)";
		}
		else{
			$requete.="AND ((Suppr=0 AND YEAR(DateVacation)='".$_SESSION['FiltreRHVacationEnCompte_Annee']."')
					OR (Suppr=1 AND YEAR(DateSuppr)='".$_SESSION['FiltreRHVacationEnCompte_Annee']."')) ";
		}
		if($_SESSION['FiltreRHVacationEnCompte_Personne']<>0 && $_SESSION['FiltreRHVacationEnCompte_Personne']<>""){
			$requete.=" AND Id_Personne=".$_SESSION['FiltreRHVacationEnCompte_Personne']." ";
		}
		if($_SESSION['FiltreRHVacationEnCompte_EtatPrisEnCompte']<>"" || $_SESSION['FiltreRHVacationEnCompte_EtatNonPrisEnCompte']){
			$requete.=" AND ( ";
			if($_SESSION['FiltreRHVacationEnCompte_EtatPrisEnCompte']<>""){
				$requete.=" DatePriseEnCompteRH>'0001-01-01' OR ";
			}
			if($_SESSION['FiltreRHVacationEnCompte_EtatNonPrisEnCompte']<>""){
				$requete.=" DatePriseEnCompteRH<='0001-01-01' OR ";
			}
			$requete=substr($requete,0,-3);
			$requete.=" ) ";
		}
		if($_SESSION['FiltreRHVacationEnCompte_RespProjet']<>""){
			$requete.="AND CONCAT(rh_personne_vacation.Id_Prestation,'_',rh_personne_vacation.Id_Pole) 
						IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
							FROM new_competences_personne_poste_prestation
							WHERE Id_Personne IN (".$_SESSION['FiltreRHVacationEnCompte_RespProjet'].")
							AND Id_Poste IN (".$IdPosteResponsableProjet.")
						)
						";
		}
		
		$requeteOrder="";
		if($_SESSION['TriRHVacationEnCompte_General']<>""){
			$requeteOrder="ORDER BY ".substr($_SESSION['TriRHVacationEnCompte_General'],0,-1);
		}

		$result=mysqli_query($bdd,$requeteAnalyse.$requete);
		
		if(isset($_GET['Page'])){$page=$_GET['Page'];}
		else{$page=0;}
		$requete3=" LIMIT ".($page*40).",40";
		$nbResulta=mysqli_num_rows($result);
		
		
		$nbResulta=mysqli_num_rows($result);
		$result=mysqli_query($bdd,$requete2.$requete.$requeteOrder.$requete3);
		$nombreDePages=ceil($nbResulta/40);
		$couleur="#FFFFFF";

	?>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				$nbPage=0;
				if($page>1){echo "<b> <a style='color:#00599f;' href='Liste_VacationsAPrendreEnCompte.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=0'><<</a> </b>";}
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
							echo "<b> <a style='color:#00599f;' href='Liste_VacationsAPrendreEnCompte.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($page<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_VacationsAPrendreEnCompte.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VacationsAPrendreEnCompte.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Personne"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriRHVacationEnCompte_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVacationEnCompte_Personne']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VacationsAPrendreEnCompte.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Contrat"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?><?php if($_SESSION['TriRHVacationEnCompte_Contrat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVacationEnCompte_Contrat']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VacationsAPrendreEnCompte.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Prestation"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?><?php if($_SESSION['TriRHVacationEnCompte_Prestation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVacationEnCompte_Prestation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VacationsAPrendreEnCompte.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Pole"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle";}else{echo "Pole";} ?><?php if($_SESSION['TriRHVacationEnCompte_Pole']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVacationEnCompte_Pole']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VacationsAPrendreEnCompte.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Etat"><?php if($_SESSION["Langue"]=="FR"){echo "Etat";}else{echo "State";} ?><?php if($_SESSION['TriRHVacationEnCompte_Etat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVacationEnCompte_Etat']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VacationsAPrendreEnCompte.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Vacation"><?php if($_SESSION["Langue"]=="FR"){echo "Vacation";}else{echo "Vacation";} ?><?php if($_SESSION['TriRHVacationEnCompte_Vacation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVacationEnCompte_Vacation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VacationsAPrendreEnCompte.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=DateVacation"><?php if($_SESSION["Langue"]=="FR"){echo "Date vacation";}else{echo "Vacation date";} ?><?php if($_SESSION['TriRHVacationEnCompte_DateVacation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVacationEnCompte_DateVacation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="20%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VacationsAPrendreEnCompte.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Modificateur"><?php if($_SESSION["Langue"]=="FR"){echo "Créé par / Supprimé par";}else{echo "Created by / Deleted by";} ?><?php if($_SESSION['TriRHVacationEnCompte_Modificateur']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVacationEnCompte_Modificateur']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="20%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VacationsAPrendreEnCompte.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=DateAction"><?php if($_SESSION["Langue"]=="FR"){echo "Date";}else{echo "Date";} ?><?php if($_SESSION['TriRHVacationEnCompte_DateAction']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVacationEnCompte_DateAction']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="20%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_VacationsAPrendreEnCompte.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=DatePriseEnCompteRH"><?php if($_SESSION["Langue"]=="FR"){echo "Pris en compte";}else{echo "Taken into account";} ?><?php if($_SESSION['TriRHVacationEnCompte_DatePriseEnCompteRH']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHVacationEnCompte_DatePriseEnCompteRH']=="ASC"){echo "&darr;";}?></a></td>
					<?php
						if($Menu==4){
					?>
					<td class='EnTeteTableauCompetences' width="15%" align="center">
						<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Prendre en compte";}else{echo "Take into account";}?>" onclick="if(!confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir valider la sélection ?";}else{echo "Are you sure you want to validate the selection?";} ?>')) return false;" name="validerSelectionRH" value="<?php if($_SESSION["Langue"]=="FR"){echo "Valider la date de prise en compte";}else{echo "Validate the date of taking into account";} ?>">&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="selectAll" id="selectAll" onclick="SelectionnerTout()" />
					</td>
					<td class='EnTeteTableauCompetences' width="15%" align="center">
						<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Attendre retour site";}else{echo "Waiting back site";}?>" onclick="if(!confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir valider la sélection ?";}else{echo "Are you sure you want to validate the selection?";} ?>')) return false;" name="AttendreRetourSite" value="<?php if($_SESSION["Langue"]=="FR"){echo "Attendre retour site";}else{echo "Waiting back site";} ?>">&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="selectAll2" id="selectAll2" onclick="SelectionnerTout2()" />
					</td>
					<td class='EnTeteTableauCompetences' width="5%" align="center">
						<?php if($_SESSION["Langue"]=="FR"){echo "Refuser";}else{echo "Refuse";} ?>
					</td>
					<?php 
						}
					?>
				</tr>
	<?php
			
			if(isset($_POST['validerSelectionRH'])){
				$result=mysqli_query($bdd,$requete2.$requete);
				while($row=mysqli_fetch_array($result)){
					if (isset($_POST['checkRH_'.$row['Id'].''])){
						$requeteUpdate="UPDATE rh_personne_vacation SET 
								Id_RH=".$_SESSION['Id_Personne'].",
								DatePriseEnCompteRH='".date('Y-m-d')."'
								WHERE Id=".$row['Id']." ";
						$resultat=mysqli_query($bdd,$requeteUpdate);
					}
				}
				$result=mysqli_query($bdd,$requete2.$requete.$requeteOrder.$requete3);
			}
			
			if(isset($_POST['AttendreRetourSite'])){
				$result=mysqli_query($bdd,$requete2.$requete);
				while($row=mysqli_fetch_array($result)){
					$attente=0;
					if (isset($_POST['checkAttendre_'.$row['Id'].''])){$attente=1;}
					$requeteUpdate="UPDATE rh_personne_vacation SET 
							AttenteRetourSite=".$attente."
							WHERE Id=".$row['Id']." ";
					$resultat=mysqli_query($bdd,$requeteUpdate);
				}
				$result=mysqli_query($bdd,$requete2.$requete.$requeteOrder.$requete3);
			}
			
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
					if($row['Etat']==0){
						if($_SESSION['Langue']=="FR"){$etat="Suppression";}
						else{$etat="Suppression";}
					}
					else{
						if($_SESSION['Langue']=="FR"){$etat="Création";}
						else{$etat="Creation";}
					}
					
					$prisEnCompte="";
					if($row['DatePriseEnCompteRH']>"0001-01-01"){
						$prisEnCompte="<img src=\"../../Images/tick.png\" border=\"0\">";
					}

		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td><?php echo stripslashes($row['Personne']);?></td>
						<td><?php echo stripslashes($row['Contrat']);?></td>
						<td><?php echo substr(stripslashes($row['Prestation']),0,7);?></td>
						<td><?php echo stripslashes($row['Pole']);?></td>
						<td><?php echo $etat;?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateVacation']);?></td>
						<td><?php echo $row['Vacation'];?></td>
						<td><?php echo $row['Modificateur'];?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateAction']);?></td>
						<td><?php echo $prisEnCompte; ?></td>
						<?php
							if($Menu==4){
						?>
						<td align="center">
						<?php
							if($row['DatePriseEnCompteRH']<="0001-01-01"){
								echo "<input class='check1' type='checkbox' name='checkRH_".$row['Id']."' value=''>";
							}
						?>
						</td>
						<td align="center">
						<?php
							$check="";
							if($row['AttenteRetourSite']==1){$check="checked";}
							echo "<input class='check2' type='checkbox' name='checkAttendre_".$row['Id']."' ".$check." value=''>";
						?>
						</td>
						<td align="center">
						<?php
							if($row['Etat']<>0){
						?>
							<a style="text-decoration:none;" title="<?php if($_SESSION['Langue']=="FR"){echo "Refuser";}else{echo "Refuse";}?>" href="javascript:OuvreFenetreRefus('<?php echo $Menu; ?>','<?php echo $row['Id']; ?>')"><img src="../../Images/supprimer.png" width="18px" border="0" alt="Refuse" title="Refuse"></a>
						<?php
						}
						?>
						</td>
						<?php
							}
						?>
					</tr>
				<?php
				}
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
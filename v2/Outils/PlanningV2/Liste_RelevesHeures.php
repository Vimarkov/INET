<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreExcel()
		{window.open("PointageIndividuel_ExportListe.php","PageExcel","status=no,menubar=no,width=900,height=450");}
	function OuvreFenetreExcelDSK()
		{window.open("PointageIndividuel_ExportListeDSK.php","PageExcel","status=no,menubar=no,width=900,height=450");}
	function OuvreFenetreExcelDSK2()
		{window.open("PointageIndividuel_ExportListeDSK2.php","PageExcel","status=no,menubar=no,width=900,height=450");}
	function OuvreFenetrePointageIndividuelExport(Id_Personne)
		{window.open("PointageIndividuel_ExportV2.php?Id_Personne="+Id_Personne,"PagePointageExport","status=no,menubar=no,scrollbars=1,width=90,height=40");}
	function Recharger(){
		var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnCharger2' name='btnCharger2' value='Charger'>";
		document.getElementById('charger').innerHTML=bouton;
		var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
		document.getElementById("btnCharger2").dispatchEvent(evt);
		document.getElementById('charger').innerHTML="";			
	}
	function RechargerDSK(){
		var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnCharger3' name='btnCharger3' value='Charger'>";
		document.getElementById('charger').innerHTML=bouton;
		var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
		document.getElementById("btnCharger3").dispatchEvent(evt);
		document.getElementById('charger').innerHTML="";			
	}
	function RechargerDSK2(){
		var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnCharger4' name='btnCharger4' value='Charger'>";
		document.getElementById('charger').innerHTML=bouton;
		var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
		document.getElementById("btnCharger4").dispatchEvent(evt);
		document.getElementById('charger').innerHTML="";			
	}
	function CocherExport(){
		nbReleve=0;
		if(document.getElementById('check_Exports').checked==true){
			var elements = document.getElementsByClassName('checkExport');
			for (i=0; i<elements.length; i++){
				if(nbReleve<200){
					nbReleve++;
					elements[i].checked=true;
				}
			}
		}
		else{
			var elements = document.getElementsByClassName('checkExport');
			for (i=0; i<elements.length; i++){
			  elements[i].checked=false;
			}
		}
		document.getElementById('nbReleve').innerHTML=nbReleve;
	}
	function CompterCheck(Id){
		nbValeur=document.getElementById('nbReleve').innerHTML;
		if(document.getElementById(Id).checked==true){
			nbValeur++;
		}
		else{
			nbValeur--;
		}
		document.getElementById('nbReleve').innerHTML=nbValeur;
	}
	function ExporterReleves(type){
		valeurs="";
		if(document.getElementById('nbReleve').innerHTML<=200){
			if(document.getElementById('nbReleve').innerHTML>0){
				var elements = document.getElementsByClassName('checkExport');
				for (i=0; i<elements.length; i++){
					if(elements[i].checked==true){
						valeurs+=elements[i].value+";";
					}
				}
				document.getElementById('listeReleves').value=valeurs;
				if(type=="DSK"){
					RechargerDSK();
				}
				else if(type=="DSK2"){
					RechargerDSK2();
				}
				else{
					Recharger();
				}
			}
			else{
				if(document.getElementById('Langue').value="FR"){
					alert("Veuillez cocher au moins 1 relevés");
				}
				else{
					alert("Please check at least 1 statement");
				}
			}
		}
		else{
			if(document.getElementById('Langue').value="FR"){
				alert("Veuillez cocher moins de 201 relevés");
			}
			else{
				alert("Please check less than 201 readings");
			}
		}
	}
	function OuvreFenetreModif(Mode,Id_Personne,Id_Agence)
	{
		var Confirm=false;
		if(document.getElementById('Langue').value=="FR"){
			if(Mode=="Suppr"){Confirm=window.confirm('Etes-vous sûr de vouloir supprimer ?');}
		}
		else{
			if(Mode=="Suppr"){Confirm=window.confirm('Are you sure you want to delete?');}
		}
		if((Mode=="Suppr" && Confirm==true) || Mode=="Ajout" || Mode=="Modif")
		{
			var w= window.open("Modif_Divers.php?Menu="+document.getElementById('Menu').value+"&Mode="+Mode+"&Id_Personne="+Id_Personne+"&Id_Agence="+Id_Agence,"PageDivers","status=no,menubar=no,width=600,height=250");
			w.focus();
		}
	}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

if($Menu==4 && DroitsFormationPlateforme($TableauIdPostesRH)){
	if($_POST){
		if(isset($_POST['btnCharger2'])){
			if($_POST['listeReleves']<>""){
				$_SESSION['RHRelevesHeures_Personnes']=$_POST['listeReleves'];
				echo "<script>
					OuvreFenetreExcel();
					setTimeout(function(){Recharger(); }, 3000);
				</script>";
			}
		}
		elseif(isset($_POST['btnCharger3'])){
			if($_POST['listeReleves']<>""){
				$_SESSION['RHRelevesHeures_Personnes']=$_POST['listeReleves'];
				echo "<script>
					OuvreFenetreExcelDSK();
					setTimeout(function(){RechargerDSK(); }, 3000);
				</script>";
			}
		}
		elseif(isset($_POST['btnCharger4'])){
			if($_POST['listeReleves']<>""){
				$_SESSION['RHRelevesHeures_Personnes']=$_POST['listeReleves'];
				echo "<script>
					OuvreFenetreExcelDSK2();
					setTimeout(function(){RechargerDSK2(); }, 3000);
				</script>";
			}
		}
	}
?>

<form class="test" action="Liste_RelevesHeures.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="listeReleves" id="listeReleves" value="" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#77c39a;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Relevés d'heures";}else{echo "Hour readings";}
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
				
				$PlateformeSelect=$_SESSION['FiltreRHRelevesHeures_Plateforme'];
				if($_POST){$PlateformeSelect=$_POST['plateforme'];}
				$_SESSION['FiltreRHRelevesHeures_Plateforme']=$PlateformeSelect;	
				
				if ($nbPlateforme > 0)
				{
					while($row=mysqli_fetch_array($resultPlateforme))
					{
						$selected="";
						if($PlateformeSelect<>"")
							{if($PlateformeSelect==$row['Id']){$selected="selected";}}
						else{
							$PlateformeSelect=$row['Id'];$selected="selected";
						}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} 
				
				$personne=$_SESSION['FiltreRHRelevesHeures_Personne'];
				if($_POST){$personne=$_POST['personne'];}
				$_SESSION['FiltreRHRelevesHeures_Personne']=$personne;
				
				?>
				<input id="personne" name="personne" type="texte" value="<?php echo $personne; ?>" size="20"/>&nbsp;&nbsp;
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Type de contrat :";}else{echo "Type of contract :";} ?>
				<select style="width:150px;" name="typeContrat" onchange="submit();">
				<?php
				if($_SESSION["Langue"]=="FR"){
					$requete="SELECT Id, Libelle
						FROM rh_typecontrat
						WHERE Suppr=0
						ORDER BY Libelle ASC";
				}
				else{
					$requete="SELECT Id, LibelleEN AS Libelle
						FROM rh_typecontrat
						WHERE Suppr=0
						ORDER BY Libelle ASC";
					
				}
				$result=mysqli_query($bdd,$requete);
				$nbType=mysqli_num_rows($result);
				
				$TypeContratSelect = 0;
				$Selected = "";
				
				$TypeContratSelect=$_SESSION['FiltreRHRelevesHeures_TypeContrat'];
				if($_POST){$TypeContratSelect=$_POST['typeContrat'];}
				$_SESSION['FiltreRHRelevesHeures_TypeContrat']=$TypeContratSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				if ($nbType > 0){
					while($row=mysqli_fetch_array($result)){
						$selected="";
						if($TypeContratSelect<>"")
							{if($TypeContratSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Temps de travail :";}else{echo "Work time :";} ?>
				<select style="width:150px;" name="tempsTravail" onchange="submit();">
				<?php

				$requete="SELECT Id, Libelle
					FROM rh_tempstravail
					WHERE Suppr=0
					ORDER BY Libelle ASC";

				$result=mysqli_query($bdd,$requete);
				$nbType=mysqli_num_rows($result);
				
				$TempsTravailSelect = 0;
				$Selected = "";
				
				$TempsTravailSelect=$_SESSION['FiltreRHRelevesHeures_TempsTravail'];
				if($_POST){$TempsTravailSelect=$_POST['tempsTravail'];}
				$_SESSION['FiltreRHRelevesHeures_TempsTravail']=$TempsTravailSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				if ($nbType > 0){
					while($row=mysqli_fetch_array($result)){
						$selected="";
						if($TempsTravailSelect<>"")
							{if($TempsTravailSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Agence d'intérim :";}else{echo "Acting Agency :";} ?>
				<select style="width:150px;" name="agenceInterim" onchange="submit();">
				<?php
				$requete="SELECT Id, Libelle
						FROM rh_agenceinterim
						WHERE Suppr=0
						ORDER BY Libelle ASC";
				$result=mysqli_query($bdd,$requete);
				$nbType=mysqli_num_rows($result);
				
				$AgenceInterimSelect = 0;
				$Selected = "";
				
				$AgenceInterimSelect=$_SESSION['FiltreRHRelevesHeures_Agence'];
				if($_POST){$AgenceInterimSelect=$_POST['agenceInterim'];}
				$_SESSION['FiltreRHRelevesHeures_Agence']=$AgenceInterimSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				if ($nbType > 0){
					while($row=mysqli_fetch_array($result)){
						$selected="";
						if($AgenceInterimSelect<>"")
							{if($AgenceInterimSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="5%">
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
				<div id="charger"></div>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td width="8%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Année :";}else{echo "Year :";} ?>
				<?php 
					$annee=$_SESSION['FiltreRHRelevesHeures_Annee'];
					if($_POST){$annee=$_POST['annee'];}
					if($annee==""){$annee=date("Y");}
					$_SESSION['FiltreRHRelevesHeures_Annee']=$annee;
				?>
				<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
			</td>
			<td width="12%" class="Libelle">
				<?php 
					$selectType=$_SESSION['FiltreRHRelevesHeures_TypeSelect'];
					 if($_POST){$selectType=$_POST['selectType'];}
					$_SESSION['FiltreRHRelevesHeures_TypeSelect']=$selectType;
				 ?>
				
				&nbsp;<input id="selectType" name="selectType" type="radio" onchange="submit()" value="Semaine" <?php if($selectType=="Semaine"){echo "checked";} ?>/><?php if($_SESSION["Langue"]=="FR"){echo "Semaine :";}else{echo "Week :";} ?>
				<select id="semaine" name="semaine" onchange="submit();">
					<?php
						$semaine=$_SESSION['FiltreRHRelevesHeures_Semaine'];
						if($_POST){$semaine=$_POST['semaine'];}
						$_SESSION['FiltreRHRelevesHeures_Semaine']=$semaine;
						
						for($i=1;$i<=52;$i++){
							echo "<option value='".$i."'";
							if($semaine== $i){echo " selected ";}
							echo ">".$i."</option>\n";
						}
						
						$annee=$_SESSION['FiltreRHRelevesHeures_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						if($annee==""){$annee=date("Y");}
						$_SESSION['FiltreRHRelevesHeures_Annee']=$annee;
					?>
				</select><br>
				&nbsp;<input id="selectType" name="selectType" type="radio" onchange="submit()" value="Mois" <?php if($selectType=="Mois"){echo "checked";} ?>/><?php if($_SESSION["Langue"]=="FR"){echo "Mois :";}else{echo "Month :";} ?>
				<select id="mois" name="mois" onchange="submit();">
					<?php
						if($_SESSION["Langue"]=="FR"){
							$arrayMois=array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
						}
						else{
							$arrayMois=array("January","February","March","April","May","June","July","August","September","October","November","December");
						}
						$mois=$_SESSION['FiltreRHRelevesHeures_Mois'];
						if($_POST){$mois=$_POST['mois'];}
						$_SESSION['FiltreRHRelevesHeures_Mois']=$mois;
						
						for($i=0;$i<=11;$i++){
							echo "<option value='".($i+1)."'";
							if($mois== ($i+1)){echo " selected ";}
							echo ">".$arrayMois[$i]."</option>\n";
						}
					?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Demandes en cours :";}else{echo "Demandes en cours :";} ?>
				<select style="width:100px;" name="demandeEC" onchange="submit();">
				<?php
				$DemandeEC = 0;
				$DemandeEC=$_SESSION['FiltreRHRelevesHeures_DemandeEC'];
				if($_POST){$DemandeEC=$_POST['demandeEC'];}
				$_SESSION['FiltreRHRelevesHeures_DemandeEC']=$DemandeEC;	
				?>
				<option value="0" selected></option>
				<option value="1" <?php if($DemandeEC==1){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?></option>
				<option value="2" <?php if($DemandeEC==2){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?></option>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Etat :";}else{echo "State :";} ?>
				<select style="width:150px;" name="etat" onchange="submit();">
				<?php
				$EtatSelect = 0;
				$EtatSelect=$_SESSION['FiltreRHRelevesHeures_Etat'];
				if($_POST){$EtatSelect=$_POST['etat'];}
				$_SESSION['FiltreRHRelevesHeures_Etat']=$EtatSelect;	
				?>
				<option value="0" selected><?php if($_SESSION["Langue"]=="FR"){echo "Tous les relevés";}else{echo "All readings";} ?></option>
				<option value="1" <?php if($EtatSelect==1){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Relevés non exportés";}else{echo "Non exported records";} ?></option>
				<option value="2" <?php if($EtatSelect==2){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Relevés déjà exportés";}else{echo "Reports already exported";} ?></option>
				</select>
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation (au moins un jour) :";}else{echo "Site (at least one day) :";} ?>
				<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
				<?php
				$requeteSite="SELECT Id, Libelle
					FROM new_competences_prestation
					WHERE Id_Plateforme=".$PlateformeSelect."
					AND Active=0
					ORDER BY Libelle ASC";
				$resultPrestation=mysqli_query($bdd,$requeteSite);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationSelect = 0;
				$Selected = "";
				$PrestationSelect=$_SESSION['FiltreRHRelevesHeures_Prestation'];
				$estDifferent=0;
				if($_POST){
					if($PrestationSelect<>$_POST['prestations']){$estDifferent=1;}
					$PrestationSelect=$_POST['prestations'];
				}
				echo "<option name='0' value='0' Selected></option>";
				if($_SESSION['FiltreRHRelevesHeures_Plateforme']==1){
					echo "<option name='-1' value='-1' ";
					if($PrestationSelect==-1){echo "selected";}
					if($_SESSION["Langue"]=="FR"){echo " >Hors YYYYY (Tous le mois)";}else{echo " >Outside YYYYY (Every month)";}
					echo "</option>";
				}
				if($_SESSION['FiltreRHRelevesHeures_Plateforme']==1){
					echo "<option name='-2' value='-2' ";
					if($PrestationSelect==-2){echo "selected";}
					if($_SESSION["Langue"]=="FR"){echo " >Uniquement YYYYY (Tous le mois)";}else{echo " >YYYYY only (Every month)";}
					echo "</option>";
				}
				if ($nbPrestation > 0)
				{
					while($row=mysqli_fetch_array($resultPrestation))
					{
						$selected="";
						if($PrestationSelect<>"0")
							{if($PrestationSelect==$row['Id']){$selected="selected";}}

						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 $_SESSION['FiltreRHRelevesHeures_Prestation']=$PrestationSelect;
				 ?>
				</select>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<?php
		$leMois=$mois;
		if($mois<10){
			$leMois="0".$mois;
		}
		
		$week = sprintf('%02d',$semaine);
		$start = strtotime($annee.'W'.$week);
		if($selectType=="Semaine"){
			$dateDebut=date('Y-m-d',strtotime('Monday',$start));
			$dateFin=date('Y-m-d',strtotime('Sunday',$start));
		}
		else{
			$dateDebut=date($annee."-".$leMois."-01");;
			$dateFin = $dateDebut;
			$tabDateFin = explode('-', $dateFin);
			$timestampFin = mktime(0, 0, 0, $tabDateFin[1]+1, $tabDateFin[2], $tabDateFin[0]);
			$dateFin = date("Y-m-d", $timestampFin);
		}
		
		$req = "SELECT DISTINCT new_rh_etatcivil.Id, 
			CONCAT(Nom,' ',Prenom) AS Personne,
			MatriculeDSK,MatriculeAAA,MatriculeDaher,
			(SELECT COUNT(rh_personne_demandeabsence.Id)
			FROM rh_absence 
			LEFT JOIN rh_personne_demandeabsence 
			ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
			WHERE rh_personne_demandeabsence.Id_Personne=new_rh_etatcivil.Id
			AND rh_absence.DateFin>='".$dateDebut."' 
			AND rh_absence.DateDebut<'".$dateFin."' 
			AND rh_personne_demandeabsence.Suppr=0 
			AND rh_absence.Suppr=0 
			AND rh_personne_demandeabsence.Annulation=0 
			AND rh_personne_demandeabsence.Conge=1 
			AND EtatN1<>-1
			AND EtatN2<>-1
			AND EtatRH=0) AS NbConges,
			(SELECT COUNT(rh_absence.Id_Personne_DA)
			FROM rh_absence 
			LEFT JOIN rh_personne_demandeabsence 
			ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
			WHERE rh_personne_demandeabsence.Id_Personne=new_rh_etatcivil.Id
			AND rh_absence.DateFin>='".$dateDebut."' 
			AND rh_absence.DateDebut<'".$dateFin."' 
			AND rh_personne_demandeabsence.Suppr=0 
			AND rh_absence.Suppr=0  
			AND rh_personne_demandeabsence.Conge=0 
			AND EtatN1<>-1
			AND EtatN2<>-1
			AND DatePriseEnCompteRH<='0001-01-01') AS NbAbsence,
			(SELECT COUNT(rh_personne_hs.Id)
			FROM rh_personne_hs
			WHERE Suppr=0 
			AND Id_Personne=new_rh_etatcivil.Id 
			AND IF(DateRH>'0001-01-01',DateRH,DateHS)>='".$dateDebut."' 
			AND IF(DateRH>'0001-01-01',DateRH,DateHS)<'".$dateFin."' 
			AND Etat2<>-1
			AND Etat3<>-1
			AND Etat4<>-1
			AND rh_personne_hs.DatePriseEnCompteRH<='0001-01-01') AS NbHS,
			(SELECT COUNT(rh_personne_rapportastreinte.Id)
			FROM rh_personne_rapportastreinte
			WHERE rh_personne_rapportastreinte.Suppr=0
			AND rh_personne_rapportastreinte.Id_Personne=new_rh_etatcivil.Id
			AND IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte)>='".$dateDebut."' 
			AND IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte)<'".$dateFin."' 
			AND EtatN1<>-1
			AND EtatN2<>-1
			AND DatePriseEnCompte<='0001-01-01') AS NbAst,
			(SELECT COUNT(rh_personne_vacation.Id)
			FROM rh_personne_vacation
			WHERE rh_personne_vacation.Suppr=0
			AND rh_personne_vacation.Id_Personne=new_rh_etatcivil.Id
			AND DatePriseEnCompteRH<='0001-01-01'
			AND (
				(Suppr=0 AND CONCAT(YEAR(DateCreation),'-',IF(MONTH(DateCreation)<10,CONCAT(0,MONTH(DateCreation)),MONTH(DateCreation)),'-',IF(DAY(DateCreation)<10,CONCAT(0,DAY(DateCreation)),DAY(DateCreation)))>CONCAT(YEAR(DateVacation),'-',IF(MONTH(DateVacation)<10,CONCAT(0,MONTH(DateVacation)),MONTH(DateVacation)),'-21'))
			OR  (Suppr=1 AND CONCAT(YEAR(DateSuppr),'-',IF(MONTH(DateSuppr)<10,CONCAT(0,MONTH(DateSuppr)),MONTH(DateSuppr)),'-',IF(DAY(DateSuppr)<10,CONCAT(0,DAY(DateSuppr)),DAY(DateSuppr)))>CONCAT(YEAR(DateVacation),'-',IF(MONTH(DateVacation)<10,CONCAT(0,MONTH(DateVacation)),MONTH(DateVacation)),'-21'))
			
			)
			AND ((Suppr=0 AND DateVacation>='".$dateDebut."' AND DateVacation<'".$dateFin."')
			OR  (Suppr=1 AND DateSuppr>='".$dateDebut."' AND DateSuppr<'".$dateFin."'))) AS NbVac,
			(SELECT COUNT(rh_personne_vacation.Id)
			FROM rh_personne_vacation
			WHERE rh_personne_vacation.Suppr=0
			AND rh_personne_vacation.Id_Personne=new_rh_etatcivil.Id
			AND Divers<>''
			AND ((Suppr=0 AND DateVacation>='".$dateDebut."' AND DateVacation<'".$dateFin."')
			OR  (Suppr=1 AND DateSuppr>='".$dateDebut."' AND DateSuppr<'".$dateFin."'))) AS NbDivers
		FROM new_rh_etatcivil
		LEFT JOIN rh_personne_mouvement 
		ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
		WHERE rh_personne_mouvement.DateDebut<'".$dateFin."'
		AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."')
		AND rh_personne_mouvement.EtatValidation=1 
		AND rh_personne_mouvement.Suppr=0
		AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation)=".$PlateformeSelect." ";
		
		if($_SESSION['FiltreRHRelevesHeures_Personne']<>""){
			$req.=" AND CONCAT(Nom,' ',Prenom) LIKE \"%".$_SESSION['FiltreRHRelevesHeures_Personne']."%\" ";
		}
		
		$requeteOrder="ORDER BY Personne ASC";
		$result=mysqli_query($bdd,$req.$requeteOrder);
		$nbResulta=mysqli_num_rows($result);
		$couleur="#FFFFFF";

	?>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td align="center" class="Libelle">
		<?php if($_SESSION["Langue"]=="FR"){echo "Nombre de relevés sélectionnés : ";}else{echo "Number of readings selected :";} ?><div style="display: inline" id="nbReleve">0</div>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td>
			<div id='Div_EnTete' align="center" style='width:99%;'>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="15%">
						<input type="button" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Exporter";}else{echo "Export";}?>" onclick="ExporterReleves('Normal');" name="validerSelection" value="<?php if($_SESSION["Langue"]=="FR"){echo "Exporter";}else{echo "Export";} ?>"><br>
						<!--<input type="button" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Exporter";}else{echo "Export";}?>" onclick="ExporterReleves('DSK');" name="validerSelection" value="<?php if($_SESSION["Langue"]=="FR"){echo "Exporter pour DSK";}else{echo "Export for DSK";} ?>"><br>-->
						<input type="button" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Exporter";}else{echo "Export";}?>" onclick="ExporterReleves('DSK2');" name="validerSelection" value="<?php if($_SESSION["Langue"]=="FR"){echo "Exporter CSV pour DSK";}else{echo "CSV Export for DSK";} ?>"><br>
						<input type='checkbox' id="check_Exports" name="check_Exports" value="" onchange="CocherExport()">
					</td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
					<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?></td>
					<td class="EnTeteTableauCompetences" width="12%"><?php if($_SESSION["Langue"]=="FR"){echo "Agence";}else{echo "Agency";} ?></td>
					<td class="EnTeteTableauCompetences" width="7%"><?php if($_SESSION["Langue"]=="FR"){echo "Demandes<br>en cours";}else{echo "Requests<br>in progress";} ?></td>
					<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION["Langue"]=="FR"){echo "Divers";}else{echo "Various";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule existant";}else{echo "Existing personnel number";} ?></td>
					<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Déjà exporté";}else{echo "Already exported";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Date d'export";}else{echo "Export date";} ?></td>
					<td class="EnTeteTableauCompetences" width="5%">
					</td>
				</tr>
			</table>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div id='Div_Personnes' align="center" style='height:300px;width:100%;overflow:auto;'>
			<table class="TableCompetences" align="center" width="100%">
		<?php
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					//Liste des congés
					$nbConges=0;
					if($row['NbConges']>0){
						$nbConges=$row['NbConges'];
					}

					//Liste des absences
					$nbAbs=0;
					if($row['NbAbsence']>0){
						$nbAbs=$row['NbAbsence'];
					}

					//Liste des heures supplémentaires
					$nbHS=0;
					if($row['NbHS']>0){
						$nbHS=$row['NbHS'];
					}
										
					//Liste des astreintes
					$nbAst=0;
					if($row['NbAst']>0){
						$nbAst=$row['NbAst'];
					}
					
					//Liste des vacations
					$nbVac=0;
					if($row['NbVac']>0){
						$nbVac=$row['NbVac'];
					}
					
					//Liste des divers renseignés
					$nbDivers=0;
					if($row['NbDivers']>0){
						$nbDivers=$row['NbDivers'];
					}

					$demandes="";
					
					$Hover="";
					if($nbAbs>0 || $nbConges>0 || $nbAst>0 || $nbHS>0 || $nbVac>0 || $nbDivers>0){
						$Hover=" id='leHover' ";
						$demandes="<img width='15px' src='../../Images/attention.png' border='0' ><span>";
						if($nbAbs>0){$demandes.="ABS ";}
						if($nbConges>0){$demandes.="CONGES ";}
						if($nbAst>0){$demandes.="AST ";}
						if($nbHS>0){$demandes.="HS ";}
						if($nbVac>0){$demandes.="Vac ";}
						if($nbDivers>0){$demandes.="Divers ";}
						$demandes.="</span>";
					}
					
					$nbContrat=0;
					
					//Recherche des salariés
					$req="
						SELECT *
						FROM
						(SELECT Id, Id_TypeContrat,Id_TempsTravail,Id_AgenceInterim,
						(SELECT Libelle FROM rh_typecontrat WHERE Id=Id_TypeContrat) AS Contrat,
						(SELECT Libelle FROM rh_typecontrat WHERE Id=Id_TypeContrat) AS ContratEN 
						FROM rh_personne_contrat
						WHERE Suppr=0
						AND DateDebut<'".$dateFin."'
						AND (DateFin>='".$dateDebut."' OR DateFin<='0001-01-01' )
						AND TypeDocument IN ('Nouveau','Avenant')
						AND Id_Personne=".$row['Id']."
						AND Id_AgenceInterim=0 ORDER BY DateDebut DESC, Id DESC LIMIT 1) AS tab
						WHERE Id_TypeContrat>0 
						";
					if($_SESSION['FiltreRHRelevesHeures_TypeContrat']<>0){
						$req.="AND Id_TypeContrat=".$_SESSION['FiltreRHRelevesHeures_TypeContrat']." ";
					}
					if($_SESSION['FiltreRHRelevesHeures_TempsTravail']<>0){
						$req.="AND Id_TempsTravail=".$_SESSION['FiltreRHRelevesHeures_TempsTravail']." ";
					}
					if($_SESSION['FiltreRHRelevesHeures_Agence']<>0){
						$req.="AND Id_AgenceInterim=-1 ";
					}
		
					$resultContrat=mysqli_query($bdd,$req);
					$nb=mysqli_num_rows($resultContrat);
					
					$laDate=date('Y-m-d',mktime(0, 0, 0, $mois+1, 0, $annee));
					
					if($nb>0){
						$rowContrat=mysqli_fetch_array($resultContrat);
						
						
						//Vérif si exporté 
						if($selectType=="Mois"){
							$req="SELECT Id, DateExport 
								FROM rh_personne_plateforme_planning_export 
								WHERE Suppr=0 
								AND Id_Personne=".$row['Id']." 
								AND Mois=".$mois." 
								AND Annee=".$annee."
								AND DateExport>'0001-01-01'
								AND Id_AgenceInterim=0 ";
						}
						else{
								$req="SELECT Id, DateExport 
								FROM rh_personne_plateforme_planning_export 
								WHERE Suppr=0 
								AND Id_Personne=".$row['Id']." 
								AND Semaine=".$semaine." 
								AND Annee=".$annee."
								AND DateExport>'0001-01-01'
								AND Id_AgenceInterim=0 ";

						}
						$resultExport=mysqli_query($bdd,$req);
						$nb=mysqli_num_rows($resultExport);
						$check="";
						$dateExport="";
						if($nb>0){
							$check="<img width='15px' src='../../Images/tick.png' border='0' >";
							$rowExport=mysqli_fetch_array($resultExport);
							$dateExport=AfficheDateJJ_MM_AAAA($rowExport['DateExport']);
						}
						
						
						if($selectType=="Mois"){
							$req="SELECT Divers 
								FROM rh_personne_plateforme_planning_export 
								WHERE Suppr=0 
								AND Id_Personne=".$row['Id']." 
								AND Mois=".$mois." 
								AND Annee=".$annee."
								AND Id_AgenceInterim=0 ";
							$resultExport=mysqli_query($bdd,$req);
							$nb=mysqli_num_rows($resultExport);
							$divers="";
							if($nb>0){
								$rowDivers=mysqli_fetch_array($resultExport);
								$divers=stripslashes($rowDivers['Divers']);
							}
						}else{
							$req="SELECT Divers 
								FROM rh_personne_plateforme_planning_export 
								WHERE Suppr=0 
								AND Id_Personne=".$row['Id']." 
								AND Semaine=".$semaine." 
								AND Annee=".$annee."
								AND Id_AgenceInterim=0 ";
							$resultExport=mysqli_query($bdd,$req);
							$nb=mysqli_num_rows($resultExport);
							$divers="";
							if($nb>0){
								$rowDivers=mysqli_fetch_array($resultExport);
								$divers=stripslashes($rowDivers['Divers']);
							}
						}
						
						$prestation=PrestationPoleLibelle_Personne($laDate,$row['Id']);
						
						//Vérifier si la personne est sur la prestation au moins un jour dans le mois 
						$appartientPresta=0;
						$laDate=$dateDebut;
						if($PrestationSelect<>0){
							$TousLesJours=1;
							while($laDate<$dateFin){
								if($PrestationSelect<>-1){
									if(Prestation_Personne($laDate,$row['Id'])==$PrestationSelect){$appartientPresta=1;}
								}
								if($PrestationSelect==-1 || $PrestationSelect==-2){
									$laPrestation=PrestationPoleLibelle_Personne($laDate,$row['Id']);
									if(substr($laPrestation,2,5)<>"YYYYY"){$TousLesJours=0;}
								}
								$laDate=date('Y-m-d',strtotime($laDate." + 1 day"));
							}
							if($PrestationSelect==-1){
								if($TousLesJours==1){$appartientPresta=0;}
								else{$appartientPresta=1;}
							}
							elseif($PrestationSelect==-2){
								if($TousLesJours==1){$appartientPresta=1;}
								else{$appartientPresta=0;}
							}
						}
						
						if($PrestationSelect==0 || $appartientPresta==1){
							
							if($_SESSION['FiltreRHRelevesHeures_Etat']==0 || ($_SESSION['FiltreRHRelevesHeures_Etat']==1 && $check=="") || ($_SESSION['FiltreRHRelevesHeures_Etat']==2 && $check<>"")){
								if($_SESSION['FiltreRHRelevesHeures_DemandeEC']==0 || ($_SESSION['FiltreRHRelevesHeures_DemandeEC']==1 && $demandes<>"") || ($_SESSION['FiltreRHRelevesHeures_DemandeEC']==2 && $demandes=="")){
								$nbContrat++;
								if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
								else{$couleur="#FFFFFF";}
								
								$integrable="<img width='15px' src='../../Images/tick.png' border='0' >";

								//Vérif si matricule DSK
								if($row['MatriculeAAA']=="" || $row['MatriculeDaher']==""){$integrable="Matricule AAA/Daher manquant";}

								
						?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="15%">
									<input class="checkExport" type="checkbox" id="checkExport_<?php echo $row['Id']."|0";?>" name="checkExport_<?php echo $row['Id']."|0";?>" value="<?php echo $row['Id']."|0";?>" onchange="CompterCheck('checkExport_<?php echo $row['Id'];?>|0')">
								</td>
								<td width="10%"><?php echo stripslashes($row['Personne']);?></td>
								<td width="8%"><?php echo stripslashes($prestation);?></td>
								<td width="5%"><?php if($LangueAffichage=="FR"){echo $rowContrat['Contrat'];}else{echo $rowContrat['ContratEN'];} ?></td>
								<td width="12%"></td>
								<td width="7%" <?php echo $Hover; ?>><?php echo $demandes; ?></td>
								<td width="20%">
										<?php echo $divers; ?>
										<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row['Id']; ?>','0');">
											<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
										</a>
								</td>
								<td width="10%">
								<?php 
									echo $integrable;
								?>
								</td>
								<td width="5%"><?php echo $check; ?></td>
								<td width="8%"><?php echo $dateExport; ?></td>
								<td width="2%">
									<?php echo "<a href='javascript:OuvreFenetrePointageIndividuelExport(\"".$row['Id']."|0\");'><img src='../../Images/excel2.gif' border='0' alt='Excel'></a>"; ?>
								</td>
							</tr>
						<?php
								}
							}
						}
					}
					
					//Recherche des types d'agences differentes
					$req="SELECT Id_AgenceInterim,Id_TypeContrat,AgenceInterim,Id_TempsTravail,
							(SELECT Libelle FROM rh_typecontrat WHERE Id=Id_TypeContrat) AS Contrat,
							(SELECT Libelle FROM rh_typecontrat WHERE Id=Id_TypeContrat) AS ContratEN 
							FROM
							(SELECT DISTINCT Id_AgenceInterim,Id_TypeContrat,DateDebut,Id,Id_TempsTravail,
							(SELECT Libelle FROM rh_agenceinterim WHERE rh_agenceinterim.Id=rh_personne_contrat.Id_AgenceInterim) AS AgenceInterim
							FROM rh_personne_contrat
							WHERE Suppr=0
							AND DateDebut<'".$dateFin."'
							AND (DateFin>='".$dateDebut."' OR DateFin<='0001-01-01' )
							AND TypeDocument IN ('Nouveau','Avenant')
							AND Id_Personne=".$row['Id']."
							AND Id_AgenceInterim>0
							GROUP BY Id_AgenceInterim
							ORDER BY DateDebut DESC, Id DESC
							) AS Tab
						WHERE Id_AgenceInterim>0 
						";
					if($_SESSION['FiltreRHRelevesHeures_TypeContrat']<>0){
						$req.="AND Tab.Id_TypeContrat=".$_SESSION['FiltreRHRelevesHeures_TypeContrat']." ";
					}
					if($_SESSION['FiltreRHRelevesHeures_TempsTravail']<>0){
						$req.="AND Tab.Id_TempsTravail=".$_SESSION['FiltreRHRelevesHeures_TempsTravail']." ";
					}
					if($_SESSION['FiltreRHRelevesHeures_Agence']<>0){
						$req.="AND Tab.Id_AgenceInterim=".$_SESSION['FiltreRHRelevesHeures_Agence']." ";
					}
					
					$resultContrat=mysqli_query($bdd,$req);
					$nb=mysqli_num_rows($resultContrat);
					
					
					
					if($nb>0){
						
						$prestation=PrestationPoleLibelle_Personne($laDate,$row['Id']);
						
						$appartientPresta=0;
						$laDate=$dateDebut;
						if($PrestationSelect<>0){
							$TousLesJours=1;
							while($laDate<$dateFin){
								if($PrestationSelect<>-1){
									if(Prestation_Personne($laDate,$row['Id'])==$PrestationSelect){$appartientPresta=1;}
								}
								if($PrestationSelect==-1 || $PrestationSelect==-2){
									$laPrestation=PrestationPoleLibelle_Personne($laDate,$row['Id']);
									if(substr($laPrestation,2,5)<>"YYYYY"){$TousLesJours=0;}
								}
								$laDate=date('Y-m-d',strtotime($laDate." + 1 day"));
							}
							if($PrestationSelect==-1){
								if($TousLesJours==1){$appartientPresta=0;}
								else{$appartientPresta=1;}
							}
							elseif($PrestationSelect==-2){
								if($TousLesJours==1){$appartientPresta=1;}
								else{$appartientPresta=0;}
							}
						}
						
						while($rowContrat=mysqli_fetch_array($resultContrat))
						{
							//Vérif si exporté 
							if($selectType=="Mois"){
								$req="SELECT Id, DateExport 
									FROM rh_personne_plateforme_planning_export 
									WHERE Suppr=0 
									AND Id_Personne=".$row['Id']." 
									AND Mois=".$mois." 
									AND Annee=".$annee."
									AND DateExport>'0001-01-01'
									AND Id_Plateforme=".$_SESSION['FiltreRHRelevesHeures_Plateforme']."
									AND Id_AgenceInterim=".$rowContrat['Id_AgenceInterim']." ";
							}
							else{
								$req="SELECT Id, DateExport 
									FROM rh_personne_plateforme_planning_export 
									WHERE Suppr=0 
									AND Id_Personne=".$row['Id']." 
									AND Semaine=".$semaine." 
									AND Annee=".$annee."
									AND DateExport>'0001-01-01'
									AND Id_Plateforme=".$_SESSION['FiltreRHRelevesHeures_Plateforme']."
									AND Id_AgenceInterim=".$rowContrat['Id_AgenceInterim']." ";
								
							}
							$resultExport=mysqli_query($bdd,$req);
							$nb=mysqli_num_rows($resultExport);
							$check="";
							$dateExport="";
							if($nb>0){
								$check="<img width='15px' src='../../Images/tick.png' border='0' >";
								$rowExport=mysqli_fetch_array($resultExport);
								$dateExport=AfficheDateJJ_MM_AAAA($rowExport['DateExport']);
							}
							
							if($selectType=="Mois"){
								$req="SELECT Divers 
									FROM rh_personne_plateforme_planning_export 
									WHERE Suppr=0 
									AND Id_Personne=".$row['Id']." 
									AND Mois=".$mois." 
									AND Annee=".$annee."
									AND Id_AgenceInterim=0 ";
							}
							else{
								$req="SELECT Divers 
									FROM rh_personne_plateforme_planning_export 
									WHERE Suppr=0 
									AND Id_Personne=".$row['Id']." 
									AND Semaine=".$semaine." 
									AND Annee=".$annee."
									AND Id_AgenceInterim=0 ";
							}
							$resultExport=mysqli_query($bdd,$req);
							$nb=mysqli_num_rows($resultExport);
							$divers="";
							if($nb>0){
								$rowDivers=mysqli_fetch_array($resultExport);
								$divers=stripslashes($rowDivers['Divers']);
							}
							
							if($PrestationSelect==0 || $appartientPresta==1){
								if($_SESSION['FiltreRHRelevesHeures_Etat']==0 || ($_SESSION['FiltreRHRelevesHeures_Etat']==1 && $check=="") || ($_SESSION['FiltreRHRelevesHeures_Etat']==2 && $check<>"")){
									if($_SESSION['FiltreRHRelevesHeures_DemandeEC']==0 || ($_SESSION['FiltreRHRelevesHeures_DemandeEC']==1 && $demandes<>"") || ($_SESSION['FiltreRHRelevesHeures_DemandeEC']==2 && $demandes=="")){
									$nbContrat++;
									if($nbContrat==1){
										if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
										else{$couleur="#FFFFFF";}
									}
									
									$integrable="<img width='15px' src='../../Images/tick.png' border='0' >";
									$matricule=$row['MatriculeDSK'];
									
									//Vérif si matricule DSK
									if($matricule==""){$integrable="Matricule DSK manquant";}
									
									//Vérif si matricule conforme
									if(strpos($matricule, "_")){
										if(!is_numeric(substr($matricule,strpos($matricule, "_")+1))){
											$integrable="Matricule DSK non conforme";
										}
									}
									elseif(!is_numeric($matricule)){
											$integrable="Matricule DSK non conforme";
									}
									
									//Vérif si trop d'heures sur une journée
						?>
								<tr bgcolor="<?php echo $couleur;?>">
									<td width="15%">
										<input class="checkExport" type="checkbox" id="checkExport_<?php echo $row['Id']."|".$rowContrat['Id_AgenceInterim'];?>" name="checkExport_<?php echo $row['Id']."|".$rowContrat['Id_AgenceInterim'];?>" value="<?php echo $row['Id']."|".$rowContrat['Id_AgenceInterim'];?>"  onchange="CompterCheck('checkExport_<?php echo $row['Id']."|".$rowContrat['Id_AgenceInterim'];?>')">
									</td>
									<td width="10%"><?php echo stripslashes($row['Personne']);?></td>
									<td width="8%"><?php echo stripslashes($prestation);?></td>
									<td width="5%"><?php if($LangueAffichage=="FR"){echo $rowContrat['Contrat'];}else{echo $rowContrat['ContratEN'];} ?></td>
									<td width="12%"><?php echo stripslashes($rowContrat['AgenceInterim']); ?></td>
									<td width="7%" <?php echo $Hover; ?>><?php echo $demandes; ?></td>
									<td width="20%">
										<?php echo $divers; ?>
										<a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row['Id']; ?>','<?php echo $rowContrat['Id_AgenceInterim']; ?>');">
											<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
										</a>
									</td>
									<td width="10%">
									<?php 
										echo $integrable;
									?>
									</td>
									<td width="5%"><?php echo $check; ?></td>
									<td width="8%"><?php echo $dateExport; ?></td>
									<td width="2%">
										<?php echo "<a href='javascript:OuvreFenetrePointageIndividuelExport(\"".$row['Id']."|".$rowContrat['Id_AgenceInterim']."\");'><img src='../../Images/excel2.gif' border='0' alt='Excel'></a>"; ?>
									</td>
								</tr>
						<?php
								}
								}
							}
						}
					}
					
					
					
		?>
					
				<?php
				}	//Fin boucle
			}
			?>
			</table>
			</div>
		</td>
	</tr>
</table>
</form>
<?php

}
?>
</body>
</html>
<?php
require("../../Menu.php");
?>
<script language="javascript">
		function OuvreFenetreModif(Menu,Id,Page)
			{var w=window.open("Modif_Contrat.php?Mode=M&Id="+Id+"&Menu="+Menu+"&Page="+Page,"PageContat","status=no,menubar=no,width=1000,height=550,scrollbars=1'");
			w.focus();
			}
		function OuvreFenetreSuppr(Menu,Id)
			{var w=window.open("Modif_Contrat.php?Mode=S&Id="+Id+"&Menu="+Menu,"PageContat","status=no,menubar=no,width=1000,height=550,scrollbars=1'");
			w.focus();
			}
		function OuvreFenetreExcel(Menu)
			{window.open("Export_ListeContrat.php?Menu="+document.getElementById('Menu').value,"PageExcel","status=no,menubar=no,width=900,height=450");}
		function OuvreFenetreBDDContrat()
			{window.open("Bdd_Contrat.php","PageExcel","status=no,menubar=no,width=900,height=450");}
		function OuvreFenetrePlanning()
			{window.open("Bdd_Planning.php","PageExcel","status=no,menubar=no,width=900,height=450");}
		function ODMExcel(Id)
			{window.open("Export_ODM.php?Id="+Id,"PageExcel","status=no,menubar=no,width=900,height=450");}
	</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

if($Menu==4 && DroitsFormationPlateforme($TableauIdPostesRH)){
if(isset($_GET['Tri'])){
	$tab = array("Id","Personne","Metier","TypeDocument","TypeContrat","AgenceInterim","Coeff","DateDebut","DateFin","TypeCoeff","SalaireBrut","TauxHoraire","TempsTravail","Etat","Titre","Plateforme");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriRHContratEC_General']= str_replace($tri." ASC,","",$_SESSION['TriRHContratEC_General']);
			$_SESSION['TriRHContratEC_General']= str_replace($tri." DESC,","",$_SESSION['TriRHContratEC_General']);
			$_SESSION['TriRHContratEC_General']= str_replace($tri." ASC","",$_SESSION['TriRHContratEC_General']);
			$_SESSION['TriRHContratEC_General']= str_replace($tri." DESC","",$_SESSION['TriRHContratEC_General']);
			if($_SESSION['TriRHContratEC_'.$tri]==""){$_SESSION['TriRHContratEC_'.$tri]="ASC";$_SESSION['TriRHContratEC_General'].= $tri." ".$_SESSION['TriRHContratEC_'.$tri].",";}
			elseif($_SESSION['TriRHContratEC_'.$tri]=="ASC"){$_SESSION['TriRHContratEC_'.$tri]="DESC";$_SESSION['TriRHContratEC_General'].= $tri." ".$_SESSION['TriRHContratEC_'.$tri].",";}
			else{$_SESSION['TriRHContratEC_'.$tri]="";}
		}
	}
}

function Titre1($Libelle,$Lien,$Selected){
		$tiret="";
		if($Selected==true){$tiret="border-bottom:4px solid white;";}
		echo "<td style=\"width:70px;height:30px;border-spacing:0;text-align:center;color:#5c4165;valign:top;font-weight:bold;".$tiret."\">
			<a style=\"text-decoration:none;width:70px;height:30px;border-spacing:0;text-align:center;color:#5c4165;valign:top;font-weight:bold;\" onmouseover=\"this.style.color='#5c4165';\" onmouseout=\"this.style.color='#5c4165';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle."</a></td>\n";
	}
?>

<form class="test" action="Liste_ContratEC.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#a988b2;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Gestion des contrats";}else{echo "Contract management";}
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
				<tr bgcolor="#cdbad2">
					<?php
						if($_SESSION["Langue"]=="FR"){Titre1("CONTRATS EN COURS","Outils/PlanningV2/Liste_ContratEC.php?Menu=".$Menu."",true);}
						else{Titre1("CONTRACTS IN PROGRESS","Outils/PlanningV2/Liste_ContratEC.php?Menu=".$Menu."",true);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("ODM EN COURS","Outils/PlanningV2/Liste_ODMEC.php?Menu=".$Menu."",false);}
						else{Titre1("MISSION ORDER IN PROGRESS","Outils/PlanningV2/Liste_ODMEC.php?Menu=".$Menu."",false);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("HISTORIQUE","Outils/PlanningV2/Liste_ContratHistorique.php?Menu=".$Menu."",false);}
						else{Titre1("HISTORICAL","Outils/PlanningV2/Liste_ContratHistorique.php?Menu=".$Menu."",false);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("IMPORT DIRECTSKILLS","Outils/PlanningV2/Liste_ImportDSK.php?Menu=".$Menu."",false);}
						else{Titre1("IMPORT DIRECTSKILLS","Outils/PlanningV2/Liste_ImportDSK.php?Menu=".$Menu."",false);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("IMPORT ODM DIRECTSKILLS","Outils/PlanningV2/Liste_ImportODMDSK.php?Menu=".$Menu."",false);}
						else{Titre1("IMPORT ODM DIRECTSKILLS","Outils/PlanningV2/Liste_ImportODMDSK.php?Menu=".$Menu."",false);}
						
						if($_SESSION["Langue"]=="FR"){Titre1("IMPORT AUGMENTATIONS","Outils/PlanningV2/Liste_ImportAugmentation.php?Menu=".$Menu."",false);}
						else{Titre1("IMPORT INCREASES","Outils/PlanningV2/Liste_ImportAugmentation.php?Menu=".$Menu."",false);}
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
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Plateforme :";}else{echo "Plateform :";} ?>
				<select class="plateforme" style="width:100px;" name="plateforme" onchange="submit();">
					<option value='' selected></option>
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
				
				$PlateformeSelect=$_SESSION['FiltreRHContratEC_Plateforme'];
				if($_POST){$PlateformeSelect=$_POST['plateforme'];}
				$_SESSION['FiltreRHContratEC_Plateforme']=$PlateformeSelect;	
				
				if ($nbPlateforme > 0)
				{
					while($row=mysqli_fetch_array($resultPlateforme))
					{
						$selected="";
						if($PlateformeSelect==$row['Id']){$selected="selected";}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} 
				
				$personne=$_SESSION['FiltreRHContratEC_Personne'];
				if($_POST){$personne=$_POST['personne'];}
				$_SESSION['FiltreRHContratEC_Personne']=$personne;
				
				?>
				<input id="personne" name="personne" type="texte" value="<?php echo $personne; ?>" size="20"/>&nbsp;&nbsp;
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Métier :";}else{echo "Job :";} ?>
				<select style="width:150px;" name="metier" onchange="submit();">
				<?php
				if($_SESSION["Langue"]=="FR"){
					$requete="SELECT Id, Libelle
						FROM new_competences_metier
						WHERE Suppr=0
						ORDER BY Libelle ASC";
				}
				else{
					$requete="SELECT Id, LibelleEN AS Libelle
						FROM new_competences_metier
						WHERE Suppr=0
						ORDER BY Libelle ASC";
					
				}
				$result=mysqli_query($bdd,$requete);
				$nbMetier=mysqli_num_rows($result);
				
				$MetierSelect = 0;
				$Selected = "";
				
				$MetierSelect=$_SESSION['FiltreRHContratEC_Metier'];
				if($_POST){$MetierSelect=$_POST['metier'];}
				$_SESSION['FiltreRHContratEC_Metier']=$MetierSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				if ($nbMetier > 0){
					while($row=mysqli_fetch_array($result)){
						$selected="";
						if($MetierSelect<>"")
							{if($MetierSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
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
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date de début :";}else{echo "Start date :";} 
				
				$signeDateDebut=$_SESSION['FiltreRHContratEC_SigneDateDebut'];
				if($_POST){$signeDateDebut=$_POST['signeDateDebut'];}
				$_SESSION['FiltreRHContratEC_SigneDateDebut']=$signeDateDebut;
				?>
				<select id="signeDateDebut" name="signeDateDebut" onchange="submit();">
					<option value='=' <?php if($signeDateDebut=="="){echo "selected";} ?>>=</option>
					<option value='<' <?php if($signeDateDebut=="<"){echo "selected";} ?>><</option>
					<option value='>' <?php if($signeDateDebut==">"){echo "selected";} ?>>></option>
				</select>
				<?php 
				$dateDebut=$_SESSION['FiltreRHContratEC_DateDebut'];
				if($_POST){$dateDebut=$_POST['dateDebut'];}
				$_SESSION['FiltreRHContratEC_DateDebut']=$dateDebut;
				
				?>
				<input id="dateDebut" name="dateDebut" type="date" value="<?php echo $dateDebut; ?>" size="10"/>&nbsp;&nbsp;
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date de fin :";}else{echo "End date :";} 
				
				$signeDateFin=$_SESSION['FiltreRHContratEC_SigneDateFin'];
				if($_POST){$signeDateFin=$_POST['signeDateFin'];}
				$_SESSION['FiltreRHContratEC_SigneDateFin']=$signeDateFin;
				?>
				<select id="signeDateFin" name="signeDateFin" onchange="submit();">
					<option value='=' <?php if($signeDateFin=="="){echo "selected";} ?>>=</option>
					<option value='<' <?php if($signeDateFin=="<"){echo "selected";} ?>><</option>
					<option value='>' <?php if($signeDateFin==">"){echo "selected";} ?>>></option>
				</select>
				<?php 
				$dateFin=$_SESSION['FiltreRHContratEC_DateFin'];
				if($_POST){$dateFin=$_POST['dateFin'];}
				$_SESSION['FiltreRHContratEC_DateFin']=$dateFin;
				
				?>
				<input id="dateFin" name="dateFin" type="date" value="<?php echo $dateFin; ?>" size="10"/>&nbsp;&nbsp;
			</td>
			<td width="10%" class="Libelle" style="display:none;">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Salaire brut :";}else{echo "Gross salary :";}
				
				$signeSalaire=$_SESSION['FiltreRHContratEC_SigneSalaire'];
				if($_POST){$signeSalaire=$_POST['signeSalaire'];}
				$_SESSION['FiltreRHContratEC_SigneSalaire']=$signeSalaire;
				?>
				<select id="signeSalaire" name="signeSalaire" onchange="submit();">
					<option value='=' <?php if($signeSalaire=="="){echo "selected";} ?>>=</option>
					<option value='<' <?php if($signeSalaire=="<"){echo "selected";} ?>><</option>
					<option value='>' <?php if($signeSalaire==">"){echo "selected";} ?>>></option>
				</select>
				<?php 
				$salaire=$_SESSION['FiltreRHContratEC_Salaire'];
				if($_POST){$salaire=$_POST['salaire'];}
				$_SESSION['FiltreRHContratEC_Salaire']=$salaire;
				
				?>
				<input onKeyUp="nombre(this)" id="salaire" name="salaire" type="texte" value="<?php echo $salaire; ?>" size="6"/>&nbsp;&nbsp;
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Taux horaire :";}else{echo "Hourly rate :";}
				
				$signeTauxHoraire=$_SESSION['FiltreRHContratEC_SigneTauxHoraire'];
				if($_POST){$signeTauxHoraire=$_POST['signeTauxHoraire'];}
				$_SESSION['FiltreRHContratEC_SigneTauxHoraire']=$signeTauxHoraire;
				?>
				<select id="signeTauxHoraire" name="signeTauxHoraire" onchange="submit();">
					<option value='=' <?php if($signeTauxHoraire=="="){echo "selected";} ?>>=</option>
					<option value='<' <?php if($signeTauxHoraire=="<"){echo "selected";} ?>><</option>
					<option value='>' <?php if($signeTauxHoraire==">"){echo "selected";} ?>>></option>
				</select>
				<?php 
				$tauxHoraire=$_SESSION['FiltreRHContratEC_TauxHoraire'];
				if($_POST){$tauxHoraire=$_POST['tauxHoraire'];}
				$_SESSION['FiltreRHContratEC_TauxHoraire']=$tauxHoraire;
				
				?>
				<input onKeyUp="nombre(this)" id="tauxHoraire" name="tauxHoraire" type="texte" value="<?php echo $tauxHoraire; ?>" size="6"/>&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
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
				
				$TypeContratSelect=$_SESSION['FiltreRHContratEC_TypeContrat'];
				if($_POST){$TypeContratSelect=$_POST['typeContrat'];}
				$_SESSION['FiltreRHContratEC_TypeContrat']=$TypeContratSelect;	
				
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
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Coeff :";}else{echo "Coeff :";} 
				
				$signeCoeff=$_SESSION['FiltreRHContratEC_SigneCoeff'];
				if($_POST){$signeCoeff=$_POST['signeCoeff'];}
				$_SESSION['FiltreRHContratEC_SigneCoeff']=$signeCoeff;
				?>
				<select id="signeCoeff" name="signeCoeff" onchange="submit();">
					<option value='=' <?php if($signeCoeff=="="){echo "selected";} ?>>=</option>
					<option value='<' <?php if($signeCoeff=="<"){echo "selected";} ?>><</option>
					<option value='>' <?php if($signeCoeff==">"){echo "selected";} ?>>></option>
				</select>
				<?php 
				$coeff=$_SESSION['FiltreRHContratEC_Coeff'];
				if($_POST){$coeff=$_POST['coeff'];}
				$_SESSION['FiltreRHContratEC_Coeff']=$coeff;
				
				?>
				<input id="coeff" name="coeff" type="texte" value="<?php echo $coeff; ?>" size="6"/>&nbsp;&nbsp;
			</td>
			<td width="10%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Temps de travail :";}else{echo "Work time :";} ?>
				<select style="width:150px;" name="tempsTravail" onchange="submit();">
				<?php
				$requete="SELECT Id, Libelle
					FROM rh_tempstravail
					WHERE Suppr=0
					ORDER BY Libelle ASC";

				$result=mysqli_query($bdd,$requete);
				$nbTemps=mysqli_num_rows($result);
				
				$TempsTravailSelect = 0;
				$Selected = "";
				
				$TempsTravailSelect=$_SESSION['FiltreRHContratEC_TempsTravail'];
				if($_POST){$TempsTravailSelect=$_POST['tempsTravail'];}
				$_SESSION['FiltreRHContratEC_TempsTravail']=$TempsTravailSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				if ($nbTemps > 0){
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
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Etat :";}else{echo "State :";} ?>
				<select style="width:150px;" name="etat" onchange="submit();">
				<?php
				$EtatSelect = 0;
				$EtatSelect=$_SESSION['FiltreRHContratEC_Etat'];
				if($_POST){$EtatSelect=$_POST['etat'];}
				$_SESSION['FiltreRHContratEC_Etat']=$EtatSelect;	
				?>
				<option value="0" selected></option>
				<option value="1" <?php if($EtatSelect==1){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Attente signature siège";}else{echo "Waiting signature head office";} ?></option>
				<option value="2" <?php if($EtatSelect==2){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Signature siège et attente signature salarié";}else{echo "Signature head office and waiting signature employee";} ?></option>
				<option value="3" <?php if($EtatSelect==3){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Signature salarié OK";}else{echo "Employee Signature OK";} ?></option>
				<option value="4" <?php if($EtatSelect==4){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Retour signé au siège (clôturé)";}else{echo "Signed return to head office (closed)";} ?></option>
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
		if($_SESSION["Langue"]=="FR"){
			$requete2="
				SELECT *
				FROM
				(
					SELECT *
					FROM 
						(SELECT Id,Id_Personne,Id_Metier,Id_TypeContrat,Id_TempsTravail,Titre,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
						(SELECT Libelle FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
						(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
						(SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS EstInterim,
						Coeff,DateDebut,DateFin,TypeDocument,TypeCoeff,SalaireBrut,TauxHoraire,
						(SELECT Libelle FROM rh_agenceinterim WHERE rh_agenceinterim.Id=Id_AgenceInterim) AS AgenceInterim,
						(SELECT Libelle FROM rh_tempstravail WHERE rh_tempstravail.Id=Id_TempsTravail) AS TempsTravail,
						(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Id_Plateforme,
						(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Plateforme,
						IF(DateSignatureSiege=0,1,
							IF(DateSignatureSalarie=0,2,
								IF(DateSignatureSalarie>'0001-01-01' AND DateRetourSigneAuSiege=0,3,
									IF(DateRetourSigneAuSiege>'0001-01-01',4,
									0
									)
								)
							)
						) AS Etat,(@row_number:=@row_number + 1) AS rnk
						FROM rh_personne_contrat
						WHERE Suppr=0
						AND DateDebut<='".date('Y-m-d')."'
						AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
						AND TypeDocument IN ('Nouveau','Avenant')
						ORDER BY Id_Personne, DateDebut DESC, Id DESC) AS table_contrat 
					GROUP BY Id_Personne
				) AS table_contrat2
				WHERE Personne<>'' 
				";
		}
		else{
			$requete2="
				SELECT *
				FROM
				(
					SELECT *
					FROM 
						(SELECT Id,Id_Personne,Id_Metier,Id_TypeContrat,Id_TempsTravail,Titre,
						(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
						(SELECT LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
						(SELECT LibelleEN FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
						(SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS EstInterim,
						Coeff,DateDebut,DateFin,TypeDocument,TypeCoeff,SalaireBrut,TauxHoraire,
						(SELECT Libelle FROM rh_agenceinterim WHERE rh_agenceinterim.Id=Id_AgenceInterim) AS AgenceInterim,
						(SELECT Libelle FROM rh_tempstravail WHERE rh_tempstravail.Id=Id_TempsTravail) AS TempsTravail,
						(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Id_Plateforme,
						(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Plateforme,
						IF(DateSignatureSiege=0,1,
							IF(DateSignatureSalarie=0,2,
								IF(DateSignatureSalarie>'0001-01-01' AND DateRetourSigneAuSiege=0,3,
									IF(DateRetourSigneAuSiege>'0001-01-01',4,
									0
									)
								)
							)
						) AS Etat,(@row_number:=@row_number + 1) AS rnk
						FROM rh_personne_contrat
						WHERE Suppr=0
						AND DateDebut<='".date('Y-m-d')."'
						AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
						AND TypeDocument IN ('Nouveau','Avenant')
						ORDER BY Id_Personne, DateDebut DESC) AS table_contrat 
					GROUP BY Id_Personne
				) AS table_contrat2
				WHERE Personne<>'' 
				";
		}
		if($_SESSION['FiltreRHContratEC_Plateforme']<>""){
			$requete2.=" AND Id_Plateforme = ".$_SESSION['FiltreRHContratEC_Plateforme']." ";
		}
		
		if($_SESSION['FiltreRHContratEC_Personne']<>""){
			$requete2.=" AND Personne LIKE \"%".$_SESSION['FiltreRHContratEC_Personne']."%\" ";
		}
		
		if($_SESSION['FiltreRHContratEC_Metier']<>"0"){
			$requete2.=" AND Id_Metier = ".$_SESSION['FiltreRHContratEC_Metier']." ";
		}
		if($_SESSION['FiltreRHContratEC_TypeContrat']<>"0"){
			$requete2.=" AND Id_TypeContrat = ".$_SESSION['FiltreRHContratEC_TypeContrat']." ";
		}
		if($_SESSION['FiltreRHContratEC_Coeff']<>""){
			$requete2.=" AND Coeff ".$_SESSION['FiltreRHContratEC_SigneCoeff']." ".$_SESSION['FiltreRHContratEC_Coeff']." ";
		}
		if($_SESSION['FiltreRHContratEC_DateDebut']<>""){
			$requete2.=" AND DateDebut ".$_SESSION['FiltreRHContratEC_SigneDateDebut']." '".TrsfDate_($_SESSION['FiltreRHContratEC_DateDebut'])."' ";
		}
		if($_SESSION['FiltreRHContratEC_DateFin']<>""){
			if($_SESSION['FiltreRHContratEC_SigneDateFin']=="<"){
				$requete2.=" AND DateFin ".$_SESSION['FiltreRHContratEC_SigneDateFin']." '".TrsfDate_($_SESSION['FiltreRHContratEC_DateFin'])."' 
				AND DateFin>'0001-01-01'
				";
			}
			elseif($_SESSION['FiltreRHContratEC_SigneDateFin']==">"){
				$requete2.=" AND (DateFin ".$_SESSION['FiltreRHContratEC_SigneDateFin']." '".TrsfDate_($_SESSION['FiltreRHContratEC_DateFin'])."' 
				OR DateFin<='0001-01-01' )
				";
			}
			elseif($_SESSION['FiltreRHContratEC_SigneDateFin']=="="){
				$requete2.=" AND DateFin ".$_SESSION['FiltreRHContratEC_SigneDateFin']." '".TrsfDate_($_SESSION['FiltreRHContratEC_DateFin'])."' 
				";
			}
		}
		if($_SESSION['FiltreRHContratEC_Salaire']<>""){
			$requete2.=" AND SalaireBrut ".$_SESSION['FiltreRHContratEC_SigneSalaire']." '".$_SESSION['FiltreRHContratEC_Salaire']."' ";
		}
		if($_SESSION['FiltreRHContratEC_Etat']<>"0"){
			$requete2.=" AND Etat = ".$_SESSION['FiltreRHContratEC_Etat']." ";
		}
		if($_SESSION['FiltreRHContratEC_TauxHoraire']<>""){
			$requete2.=" AND SalaireBrut ".$_SESSION['FiltreRHContratEC_SigneTauxHoraire']." '".$_SESSION['FiltreRHContratEC_TauxHoraire']."' ";
		}
		if($_SESSION['FiltreRHContratEC_TempsTravail']<>"0" && $_SESSION['FiltreRHContratEC_TempsTravail']<>""){
			$requete2.=" AND Id_TempsTravail = ".$_SESSION['FiltreRHContratEC_TempsTravail']." ";
		}
		
		$requeteOrder="";
		if($_SESSION['TriRHContratEC_General']<>""){
			$requeteOrder="ORDER BY ".substr($_SESSION['TriRHContratEC_General'],0,-1);
		}

		$result=mysqli_query($bdd,$requete2);
		
		if(isset($_GET['Page'])){$page=$_GET['Page'];}
		else{$page=0;}
		$requete3=" LIMIT ".($page*100).",100";
		$nbResulta=mysqli_num_rows($result);

		$result=mysqli_query($bdd,$requete2.$requeteOrder.$requete3);
		$nombreDePages=ceil($nbResulta/100);
		$couleur="#FFFFFF";

	?>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				$nbPage=0;
				if($page>1){echo "<b> <a style='color:#00599f;' href='Liste_ContratEC.php?Menu=".$Menu."&debut=1&Page=0'><<</a> </b>";}
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
							echo "<b> <a style='color:#00599f;' href='Liste_ContratEC.php?Menu=".$Menu."&debut=1&Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($page<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_ContratEC.php?Menu=".$Menu."&debut=1&Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratEC.php?Menu=<?php echo $Menu; ?>&Tri=Personne"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriRHContratEC_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratEC_Personne']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratEC.php?Menu=<?php echo $Menu; ?>&Tri=Titre"><?php if($_SESSION["Langue"]=="FR"){echo "Titre";}else{echo "Title";} ?><?php if($_SESSION['TriRHContratEC_Titre']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratEC_Titre']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratEC.php?Menu=<?php echo $Menu; ?>&Tri=Metier"><?php if($_SESSION["Langue"]=="FR"){echo "Métier";}else{echo "Job";} ?><?php if($_SESSION['TriRHContratEC_Metier']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratEC_Metier']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratEC.php?Menu=<?php echo $Menu; ?>&Tri=TypeDocument"><?php if($_SESSION["Langue"]=="FR"){echo "Type de document";}else{echo "Document type";} ?><?php if($_SESSION['TriRHContratEC_TypeDocument']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratEC_TypeDocument']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratEC.php?Menu=<?php echo $Menu; ?>&Tri=TypeContrat"><?php if($_SESSION["Langue"]=="FR"){echo "Type de contrat";}else{echo "Contract type";} ?><?php if($_SESSION['TriRHContratEC_TypeContrat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratEC_TypeContrat']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratEC.php?Menu=<?php echo $Menu; ?>&Tri=AgenceInterim"><?php if($_SESSION["Langue"]=="FR"){echo "Agence d'intérim";}else{echo "Acting Agency";} ?><?php if($_SESSION['TriRHContratEC_AgenceInterim']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratEC_AgenceInterim']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratEC.php?Menu=<?php echo $Menu; ?>&Tri=DateDebut"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début";}else{echo "Start date";} ?><?php if($_SESSION['TriRHContratEC_DateDebut']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratEC_DateDebut']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratEC.php?Menu=<?php echo $Menu; ?>&Tri=DateFin"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin";}else{echo "End date";} ?><?php if($_SESSION['TriRHContratEC_DateFin']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratEC_DateFin']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratEC.php?Menu=<?php echo $Menu; ?>&Tri=Coeff"><?php if($_SESSION["Langue"]=="FR"){echo "Coeff";}else{echo "Coeff";} ?><?php if($_SESSION['TriRHContratEC_Coeff']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratEC_Coeff']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratEC.php?Menu=<?php echo $Menu; ?>&Tri=TypeCoeff"><?php if($_SESSION["Langue"]=="FR"){echo "Type de coeff";}else{echo "Coeff type";} ?><?php if($_SESSION['TriRHContratEC_TypeCoeff']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratEC_TypeCoeff']=="ASC"){echo "&darr;";}?></a></td>
					<!--<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratEC.php?Menu=<?php echo $Menu; ?>&Tri=SalaireBrut"><?php if($_SESSION["Langue"]=="FR"){echo "Salaire";}else{echo "Salary";} ?><?php if($_SESSION['TriRHContratEC_SalaireBrut']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratEC_SalaireBrut']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="5%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratEC.php?Menu=<?php echo $Menu; ?>&Tri=TauxHoraire"><?php if($_SESSION["Langue"]=="FR"){echo "Taux horaire";}else{echo "Hourly rate";} ?><?php if($_SESSION['TriRHContratEC_TauxHoraire']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratEC_TauxHoraire']=="ASC"){echo "&darr;";}?></a></td>
					--><td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratEC.php?Menu=<?php echo $Menu; ?>&Tri=TempsTravail"><?php if($_SESSION["Langue"]=="FR"){echo "Temps de travail";}else{echo "Work time";} ?><?php if($_SESSION['TriRHContratEC_TempsTravail']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratEC_TempsTravail']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratEC.php?Menu=<?php echo $Menu; ?>&Tri=Etat"><?php if($_SESSION["Langue"]=="FR"){echo "Etat";}else{echo "State";} ?><?php if($_SESSION['TriRHContratEC_Etat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratEC_Etat']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_ContratEC.php?Menu=<?php echo $Menu; ?>&Tri=Plateforme"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";} ?><?php if($_SESSION['TriRHContratEC_Plateforme']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHContratEC_Plateforme']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="1%"></td>
				</tr>
		<?php
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td><a style="color:#3e65fa;" href="javascript:OuvreFenetreModif(<?php echo $Menu; ?>,<?php echo $row['Id']; ?>,'Liste_ContratEC')"><?php echo stripslashes($row['Personne']);?></a></td>
						<td><?php echo stripslashes($row['Titre']); ?></td>
						<td><?php echo stripslashes($row['Metier']); ?></td>
						<td><?php if($row['TypeDocument']=="Nouveau"){if($_SESSION["Langue"]=="FR"){echo "Nouveau";}else{echo "New";}}elseif($row['TypeDocument']=="Avenant"){if($_SESSION["Langue"]=="FR"){echo "Avenant";}} ?></td>
						<td><?php echo stripslashes($row['TypeContrat']); ?></td>
						<td><?php echo stripslashes($row['AgenceInterim']); ?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateDebut']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateFin']);?></td>
						<td><?php echo stripslashes($row['Coeff']);?></td>
						<td><?php if($row['EstInterim']==1){echo stripslashes($row['TypeCoeff']);} ?></td>
						<!--<td><?php if($row['SalaireBrut']>0){echo stripslashes($row['SalaireBrut']);} ?></td>
						<td><?php if($row['TauxHoraire']>0){echo stripslashes($row['TauxHoraire']);} ;?></td>
						--><td><?php echo stripslashes($row['TempsTravail']);?></td>
						<td>
							<?php 
								if($row['Etat']==1){if($_SESSION["Langue"]=="FR"){echo "Attente signature siège";}else{echo "Waiting signature head office";}}
								elseif($row['Etat']==2){if($_SESSION["Langue"]=="FR"){echo "Signature siège et attente signature salarié";}else{echo "Signature head office and waiting signature employee";}}
								elseif($row['Etat']==3){if($_SESSION["Langue"]=="FR"){echo "Signature salarié OK";}else{echo "Employee Signature OK";}}
								elseif($row['Etat']==4){if($_SESSION["Langue"]=="FR"){echo "Retour signé au siège (clôturé)";}else{echo "Signed return to head office (closed)";}}
							?>
						</td>
						<td><?php echo stripslashes($row['Plateforme']);?></td>
						<td>
						
						</td>
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
}
?>
</body>
</html>
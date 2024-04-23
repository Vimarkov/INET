<?php
require("../../Menu.php");
?>
<script language="javascript">
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
if(isset($_GET['Tri'])){
	$tab = array("Personne","Prestation","Pole","Formation","DateDebut","DateFin","HeureDebut","HeureFin");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriRHAbsencesForm_General']= str_replace($tri." ASC,","",$_SESSION['TriRHAbsencesForm_General']);
			$_SESSION['TriRHAbsencesForm_General']= str_replace($tri." DESC,","",$_SESSION['TriRHAbsencesForm_General']);
			$_SESSION['TriRHAbsencesForm_General']= str_replace($tri." ASC","",$_SESSION['TriRHAbsencesForm_General']);
			$_SESSION['TriRHAbsencesForm_General']= str_replace($tri." DESC","",$_SESSION['TriRHAbsencesForm_General']);
			if($_SESSION['TriRHAbsencesForm_'.$tri]==""){$_SESSION['TriRHAbsencesForm_'.$tri]="ASC";$_SESSION['TriRHAbsencesForm_General'].= $tri." ".$_SESSION['TriRHAbsencesForm_'.$tri].",";}
			elseif($_SESSION['TriRHAbsencesForm_'.$tri]=="ASC"){$_SESSION['TriRHAbsencesForm_'.$tri]="DESC";$_SESSION['TriRHAbsencesForm_General'].= $tri." ".$_SESSION['TriRHAbsencesForm_'.$tri].",";}
			else{$_SESSION['TriRHAbsencesForm_'.$tri]="";}
		}
	}
}

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
?>

<form class="test" action="Liste_AbsencesFormation.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $TDB; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $OngletTDB; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#e7aded;">
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
						
					if($LangueAffichage=="FR"){echo "Liste des absences en formation";}else{echo "List of absences in formation";}
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
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?>
				<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
				<?php
				$requeteSite="
					SELECT DISTINCT *
					FROM
					(SELECT
					(SELECT (SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=form_besoin.Id_Prestation) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Prestation,
					(SELECT form_besoin.Id_Prestation FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Id_Prestation ";
				$requeteSite.=" FROM form_session_personne
					LEFT JOIN form_session
					ON form_session_personne.Id_Session=form_session.Id
					WHERE form_session_personne.Suppr=0 ) AS TAB
					WHERE ";
					
				$requeteSite.=" TAB.Id_Prestation IN 
							(SELECT Id_Prestation
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
							) ";
				$resultPrestation=mysqli_query($bdd,$requeteSite);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationSelect = 0;
				$Selected = "";
				
				$PrestationSelect=$_SESSION['FiltreRHAbsencesForm_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestations'];}
				$_SESSION['FiltreRHAbsencesForm_Prestation']=$PrestationSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				if ($nbPrestation > 0)
				{
					while($row=mysqli_fetch_array($resultPrestation))
					{
						$selected="";
						if($PrestationSelect<>"")
							{if($PrestationSelect==$row['Id_Prestation']){$selected="selected";}}
						echo "<option value='".$row['Id_Prestation']."' ".$selected.">".stripslashes($row['Prestation'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?>
				<select class="pole" style="width:100px;" name="pole" onchange="submit();">
				<?php

				$requetePole="
					SELECT DISTINCT *
					FROM
					(SELECT
					(SELECT (SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=form_besoin.Id_Prestation) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Prestation,
					(SELECT form_besoin.Id_Prestation FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Id_Prestation,
					(SELECT (SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=form_besoin.Id_Pole) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Pole,
						(SELECT form_besoin.Id_Pole FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Id_Pole,";
				$requetePole.=" FROM form_session_personne
					LEFT JOIN form_session
					ON form_session_personne.Id_Session=form_session.Id
					WHERE form_session_personne.Suppr=0 ) AS TAB
					WHERE 
					TAB.Id_Prestation=".$PrestationSelect."
					";
					

				$requetePole.=" CONCAT(TAB.Id_Prestation,' ',TAB.Id_Pole) IN 
							(SELECT CONCAT(Id_Prestation,' ',Id_Pole)
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
							) ";
				$resultPole=mysqli_query($bdd,$requetePole);
				$nbPole=mysqli_num_rows($resultPole);
				
				$PoleSelect=$_SESSION['FiltreRHAbsencesForm_Pole'];
				if($_POST){$PoleSelect=$_POST['pole'];}
				$_SESSION['FiltreRHAbsencesForm_Pole']=$PoleSelect;
				
				$Selected = "";
				echo "<option name='0' value='0' Selected></option>";
				if ($nbPole > 0)
				{
					while($row=mysqli_fetch_array($resultPole))
					{
						$selected="";
						if($PoleSelect<>"")
						{if($PoleSelect==$row['Id_Pole']){$selected="selected";}}
						echo "<option value='".$row['Id_Pole']."' ".$selected.">".stripslashes($row['Pole'])."</option>\n";
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
						$mois=$_SESSION['FiltreRHAbsencesForm_Mois'];
						if($_POST){$mois=$_POST['mois'];}
						$_SESSION['FiltreRHAbsencesForm_Mois']=$mois;
						
						for($i=0;$i<=11;$i++){
							$numMois=$i+1;
							if($numMois<10){$numMois="0".$numMois;}
							echo "<option value='".$numMois."'";
							if($mois== ($i+1)){echo " selected ";}
							echo ">".$arrayMois[$i]."</option>\n";
						}
						
						$annee=$_SESSION['FiltreRHAbsencesForm_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						if($annee==""){$annee=date("Y");}
						$_SESSION['FiltreRHAbsencesForm_Annee']=$annee;
					?>
				</select>
				<?php
					$MoisCumules=$_SESSION['FiltreRHAbsencesForm_MoisCumules'];
					if($_POST){
						if(isset($_POST['MoisCumules'])){$MoisCumules="checked";}else{$MoisCumules="";}				
					}
					$_SESSION['FiltreRHAbsencesForm_MoisCumules']=$MoisCumules;
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
						$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
							CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
							FROM form_session_personne
								LEFT JOIN new_rh_etatcivil
								ON new_rh_etatcivil.Id=form_session_personne.Id_Personne
							WHERE (SELECT CONCAT(form_besoin.Id_Prestation,'_',form_besoin.Id_Pole) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) IN 
								(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
								FROM new_competences_personne_poste_prestation 
								WHERE Id_Personne=".$_SESSION["Id_Personne"]."
								AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
								)
							ORDER BY Personne ASC";
					
						$resultPersonne=mysqli_query($bdd,$requetePersonne);
						$NbPersonne=mysqli_num_rows($resultPersonne);
						
						$personne=$_SESSION['FiltreRHAbsencesForm_Personne'];
						if($_POST){$personne=$_POST['personne'];}
						$_SESSION['FiltreRHAbsencesForm_Personne']= $personne;
						
						while($rowPersonne=mysqli_fetch_array($resultPersonne))
						{
							echo "<option value='".$rowPersonne['Id']."'";
							if ($personne == $rowPersonne['Id']){echo " selected ";}
							echo ">".$rowPersonne['Personne']."</option>\n";
						}
					?>
				</select>
			</td>
			<td width="15%" colspan="3" class="Libelle" <?php if($Menu==2){echo "style='display:none;'";} ?>>
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Etat :";}else{echo "State :";} ?>
					<?php
						$PrisEnCompte=$_SESSION['FiltreRHAbsencesForm_EtatPrisEnCompte'];
						$NonPrisEnCompte=$_SESSION['FiltreRHAbsencesForm_EtatNonPrisEnCompte'];
						if($_POST){
							if(isset($_POST['PrisEnCompte'])){$PrisEnCompte="checked";}else{$PrisEnCompte="";}
							if(isset($_POST['NonPrisEnCompte'])){$NonPrisEnCompte="checked";}else{$NonPrisEnCompte="";}
						}
						$_SESSION['FiltreRHAbsencesForm_EtatPrisEnCompte']=$PrisEnCompte;
						$_SESSION['FiltreRHAbsencesForm_EtatNonPrisEnCompte']=$NonPrisEnCompte;
					?>
					<input type="checkbox" id="NonPrisEnCompte" name="NonPrisEnCompte" value="NonPrisEnCompte" <?php echo $NonPrisEnCompte; ?>><?php if($_SESSION["Langue"]=="FR"){echo "NON PRIS EN COMPTE";}else{echo "NOT TAKEN INTO ACCOUNT";} ?> &nbsp;&nbsp;
					<input type="checkbox" id="PrisEnCompte" name="PrisEnCompte" value="PrisEnCompte" <?php echo $PrisEnCompte; ?>><?php if($_SESSION["Langue"]=="FR"){echo "PRIS EN COMPTE";}else{echo "TAKEN INTO ACCOUNT";} ?> &nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<?php
		$requete2="
			SELECT *
			FROM
			(SELECT form_session_personne.Id,form_session_personne.Id_Personne,form_session_personne.DatePriseEnCompteN1,
			(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) AS DateDebut,
			(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession DESC LIMIT 1) AS DateFin,
			(SELECT Heure_Debut FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1) AS HeureDebut,
			(SELECT Heure_Fin FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession DESC LIMIT 1) AS HeureFin,
			(SELECT (SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=form_besoin.Id_Prestation) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Prestation,
			(SELECT form_besoin.Id_Prestation FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Id_Prestation,
			(SELECT (SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=form_besoin.Id_Pole) FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Pole,
			(SELECT form_besoin.Id_Formation FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Id_Formation,
			(SELECT form_besoin.Id_Pole FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin) AS Id_Pole,
			(SELECT 
				IF(
					(SELECT form_besoin.Motif FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin)='Renouvellement' 
					AND (SELECT Recyclage FROM form_formation WHERE form_formation.Id=form_session.Id_Formation)=1,
					LibelleRecyclage,
					Libelle
				)
			FROM form_formation_langue_infos
			WHERE form_formation_langue_infos.Id_Formation=(SELECT form_besoin.Id_Formation FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin)
			AND form_formation_langue_infos.Id_Langue=
				(SELECT Id_Langue 
				FROM form_formation_plateforme_parametres 
				WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=(SELECT form_besoin.Id_Prestation FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin))
				AND form_session.Id_Formation=(SELECT form_besoin.Id_Formation FROM form_besoin WHERE form_besoin.Id=form_session_personne.Id_Besoin)
				AND Suppr=0 
				LIMIT 1)
			AND Suppr=0
			LIMIT 1
			) AS Formation,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Personne ";
		$requete=" FROM form_session_personne
			LEFT JOIN form_session
			ON form_session_personne.Id_Session=form_session.Id
			WHERE form_session_personne.Suppr=0 AND form_session_personne.Presence<0) AS TAB
			WHERE YEAR(TAB.DateDebut)<='".$_SESSION['FiltreRHAbsencesForm_Annee']."' 
			AND YEAR(TAB.DateFin)>='".$_SESSION['FiltreRHAbsencesForm_Annee']."' ";
			
		$requete.="AND CONCAT(TAB.Id_Prestation,'_',TAB.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$_SESSION["Id_Personne"]."
					AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
					) ";
		
		if($_SESSION['FiltreRHAbsencesForm_Personne']<>0){
			$requete.=" AND TAB.Id_Personne=".$_SESSION['FiltreRHAbsencesForm_Personne']." ";
		}
		
		if($_SESSION['FiltreRHAbsencesForm_Prestation']<>0){
			$requete.=" AND TAB.Id_Prestation=".$_SESSION['FiltreRHAbsencesForm_Prestation']." ";
			if($_SESSION['FiltreRHAbsencesForm_Pole']<>0){
				$requete.=" AND TAB.Id_Pole=".$_SESSION['FiltreRHAbsencesForm_Pole']." ";
			}
		}
		
		if($_SESSION['FiltreRHAbsencesForm_Mois']<>0){
			if($_SESSION['FiltreRHAbsencesForm_MoisCumules']<>""){
				$requete.="AND CONCAT(YEAR(TAB.DateDebut),'_',IF(MONTH(TAB.DateDebut)<10,CONCAT('0',MONTH(TAB.DateDebut)),MONTH(TAB.DateDebut)))>='".$_SESSION['FiltreRHAbsencesForm_Annee'].'_'.$_SESSION['FiltreRHAbsencesForm_Mois']."' 
						AND CONCAT(YEAR(TAB.DateFin),'_',IF(MONTH(TAB.DateFin)<10,CONCAT('0',MONTH(TAB.DateFin)),MONTH(TAB.DateFin)))<='".$_SESSION['FiltreRHAbsencesForm_Annee']."_12' ";

			}
			else{
				$requete.="AND CONCAT(YEAR(TAB.DateFin),'_',IF(MONTH(TAB.DateFin)<10,CONCAT('0',MONTH(TAB.DateFin)),MONTH(TAB.DateFin)))>='".$_SESSION['FiltreRHAbsencesForm_Annee'].'_'.$_SESSION['FiltreRHAbsencesForm_Mois']."' ";
			}
		}
		
		if($_SESSION['FiltreRHAbsencesForm_EtatPrisEnCompte']<>"" || $_SESSION['FiltreRHAbsencesForm_EtatNonPrisEnCompte']<>""){
			$requete.=" AND ( ";
			if($_SESSION['FiltreRHAbsencesForm_EtatPrisEnCompte']<>""){
				$requete.=" (TAB.DatePriseEnCompteN1>'0001-01-01' OR  
							CONCAT(TAB.Id_Prestation,'_',TAB.Id_Pole) NOT IN 
							(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteChefEquipe.") 
							)
					) OR ";
			}
			if($_SESSION['FiltreRHAbsencesForm_EtatNonPrisEnCompte']<>""){
				$requete.=" (TAB.DatePriseEnCompteN1<='0001-01-01' AND  
							CONCAT(TAB.Id_Prestation,'_',TAB.Id_Pole) IN 
							(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteChefEquipe.") 
							)
					) OR ";
			}
			$requete=substr($requete,0,-3);
			$requete.=" ) ";
		}

		$requeteOrder="";
		if($_SESSION['TriRHAbsencesForm_General']<>""){
			$requeteOrder="ORDER BY ".substr($_SESSION['TriRHAbsencesForm_General'],0,-1);
		}
		
		
		$result=mysqli_query($bdd,$requete2.$requete);
		
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
				if($page>1){echo "<b> <a style='color:#00599f;' href='Liste_AbsencesFormation.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=0'><<</a> </b>";}
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
							echo "<b> <a style='color:#00599f;' href='Liste_AbsencesFormation.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($page<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_AbsencesFormation.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="15%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AbsencesFormation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Personne"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriRHAbsencesForm_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAbsencesForm_Personne']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AbsencesFormation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Prestation"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?><?php if($_SESSION['TriRHAbsencesForm_Prestation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAbsencesForm_Prestation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AbsencesFormation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Pole"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle";}else{echo "Pole";} ?><?php if($_SESSION['TriRHAbsencesForm_Pole']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAbsencesForm_Pole']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AbsencesFormation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Formation"><?php if($_SESSION["Langue"]=="FR"){echo "Formation";}else{echo "Training";} ?><?php if($_SESSION['TriRHAbsencesForm_Formation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAbsencesForm_Formation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AbsencesFormation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=DateDebut"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début";}else{echo "Start date";} ?><?php if($_SESSION['TriRHAbsencesForm_DateDebut']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAbsencesForm_DateDebut']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AbsencesFormation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=HeureDebut"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de début";}else{echo "Start time";} ?><?php if($_SESSION['TriRHAbsencesForm_HeureDebut']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAbsencesForm_HeureDebut']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AbsencesFormation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=DateFin"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin";}else{echo "End date";} ?><?php if($_SESSION['TriRHAbsencesForm_DateFin']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAbsencesForm_DateFin']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_AbsencesFormation.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=HeureFin"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de fin";}else{echo "End time";} ?><?php if($_SESSION['TriRHAbsencesForm_HeureFin']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHAbsencesForm_HeureFin']=="ASC"){echo "&darr;";}?></a></td>
					<td class='EnTeteTableauCompetences' width="15%" style="text-align:center;">
						<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Pris en compte";}else{echo "Taken into account";}?>" name="priseEnCompte" value="<?php if($_SESSION["Langue"]=="FR"){echo "Pris en compte";}else{echo "Taken into account";} ?>"><br>
						<input type='checkbox' id="check_Valide" name="check_Valide" value="" checked onchange="CocherValide()">
					</td>
				</tr>
	<?php
			if(isset($_POST['priseEnCompte'])){
				while($row=mysqli_fetch_array($result)){
					if (isset($_POST['checkPriseEnCompte_'.$row['Id'].''])){
						if(DroitsPrestationPole(array($IdPosteChefEquipe),$row['Id_Prestation'],$row['Id_Pole'])){
							$requeteUpdate="UPDATE form_session_personne SET 
								DatePriseEnCompteN1='".date('Y-m-d')."',
								Id_N1=".$_SESSION['Id_Personne']."
								WHERE Id=".$row['Id']." ";
							$resultat=mysqli_query($bdd,$requeteUpdate);
						}
					}
				}
			}
			$result=mysqli_query($bdd,$requete2.$requete.$requete3);
			
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td><?php echo stripslashes($row['Personne']);?></td>
						<td><?php echo substr(stripslashes($row['Prestation']),0,7);?></td>
						<td><?php echo stripslashes($row['Pole']);?></td>
						<td><?php echo stripslashes($row['Formation']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateDebut']);?></td>
						<td><?php echo stripslashes($row['HeureDebut']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateFin']);?></td>
						<td><?php echo stripslashes($row['HeureFin']);?></td>
						<td align="center">
							<?php 
								if($row['DatePriseEnCompteN1']<='0001-01-01' && DroitsPrestationPole(array($IdPosteChefEquipe),$row['Id_Prestation'],$row['Id_Pole']))
								{
									echo "<input class='check' type='checkbox' name='checkPriseEnCompte_".$row['Id']."' value='' checked>";
								}
								else{
									if($row['DatePriseEnCompteN1']>'0001-01-01'){
										echo "<img src=\"../../Images/tick.png\" border=\"0\">";
									}
								}
							?>
						</td>
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
		echo '<table  cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:50%;">';
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
				echo "<tr><td width='3%'>&nbsp;".$rowAbsVac['CodePlanning']." : </td><td width='47%'>".$rowAbsVac['InformationSalarie']."</td></tr>";
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
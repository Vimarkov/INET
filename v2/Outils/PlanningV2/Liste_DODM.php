<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModifDODM(Id_Personne,Id,Page,Id_DODM)
	{
		var w=window.open("Modif_ODM.php?Mode=M&Id_DODM="+Id_DODM+"&Id="+Id+"&Id_Personne="+Id_Personne+"&Menu="+document.getElementById('Menu').value+"&Page="+Page,"PageODM","status=no,menubar=no,width=1000,height=550,scrollbars=1");
		w.focus();
	}
	function OuvreFenetreSuppr(Menu,Id)
	{var w=window.open("Modif_DODM.php?Mode=S&Id="+Id+"&Menu="+Menu+"&TDB="+document.getElementById('TDB').value+"&OngletTDB="+document.getElementById('OngletTDB').value+"&Page=Liste_DODM","PageDODM","status=no,menubar=no,width=1000,height=550");
	w.focus();
	}
	function OuvreFenetreModif(Menu,Id)
		{var w=window.open("Modif_DODM.php?Mode=M&Id="+Id+"&Menu="+Menu,"PageDODM","status=no,menubar=no,width=1000,height=550,scrollbars=1'");
		w.focus();
		}
	function OuvreFormatExcel(Id)
		{window.open("DODM_FormatExcel.php?Id="+Id,"PageExcel","status=no,menubar=no,width=900,height=450");}
	function CocherPriseEnCompte(){
		if(document.getElementById('check_ValidePriseEnCompte').checked==true){
			var elements = document.getElementsByClassName('checkRH');
			for (i=0; i<elements.length; i++){
			  elements[i].checked=true;
			}
		}
		else{
			var elements = document.getElementsByClassName('checkRH');
			for (i=0; i<elements.length; i++){
			  elements[i].checked=false;
			}
		}
	}
	function NouveauODM(Id_Personne,Id,Page,Id_DODM)
	{var w=window.open("Ajout_ODM2.php?Mode=A&Id_DODM="+Id_DODM+"&Id="+Id+"&Id_Personne="+Id_Personne+"&Menu="+document.getElementById('Menu').value+"&Page="+Page,"PageODM","status=no,menubar=no,width=1100,height=600,scrollbars=1'");
	w.focus();
	}
	function OuvreFenetreExcel()
		{window.open("Export_DODM.php?Menu="+document.getElementById('Menu').value,"PageExcel","status=no,menubar=no,width=900,height=450");}
	function GenererAvanceFrais(Id)
		{window.open("Export_AvanceFrais.php?Menu="+document.getElementById('Menu').value+"&Id="+Id,"PageExcel","status=no,menubar=no,width=900,height=450");}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

if(isset($_GET['Tri'])){
	$tab = array("Id","Personne","PrestationDepart","PrestationDestination","DateDebut","DateFin","Demandeur","Lieu","FraisReel","DemandeAvance",'DatePriseEnCompte');
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriRHDODM_General']= str_replace($tri." ASC,","",$_SESSION['TriRHDODM_General']);
			$_SESSION['TriRHDODM_General']= str_replace($tri." DESC,","",$_SESSION['TriRHDODM_General']);
			$_SESSION['TriRHDODM_General']= str_replace($tri." ASC","",$_SESSION['TriRHDODM_General']);
			$_SESSION['TriRHDODM_General']= str_replace($tri." DESC","",$_SESSION['TriRHDODM_General']);
			if($_SESSION['TriRHDODM_'.$tri]==""){$_SESSION['TriRHDODM_'.$tri]="ASC";$_SESSION['TriRHDODM_General'].= $tri." ".$_SESSION['TriRHDODM_'.$tri].",";}
			elseif($_SESSION['TriRHDODM_'.$tri]=="ASC"){$_SESSION['TriRHDODM_'.$tri]="DESC";$_SESSION['TriRHDODM_General'].= $tri." ".$_SESSION['TriRHDODM_'.$tri].",";}
			else{$_SESSION['TriRHDODM_'.$tri]="";}
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

<form class="test" action="Liste_DODM.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $TDB; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $OngletTDB; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#a9e99d;">
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
						
					if($LangueAffichage=="FR"){echo "Liste des petits déplacements ponctuels";}else{echo "List of small punctual displacements";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation de départ:";}else{echo "Departure site :";} ?>
				<select class="prestation" style="width:100px;" name="prestationDepart" onchange="submit();">
				<?php
					$requeteSite="SELECT DISTINCT Id, Libelle, '' AS LibellePole, 0 AS Id_Pole
						FROM new_competences_prestation
						WHERE Active=0
						AND Id NOT IN (
							SELECT Id_Prestation
							FROM new_competences_pole    
						)
						
						UNION 
						
						SELECT DISTINCT new_competences_pole.Id_Prestation, new_competences_prestation.Libelle, 
							new_competences_pole.Libelle AS LibellePole, new_competences_pole.Id AS Id_Pole
							FROM new_competences_pole
							INNER JOIN new_competences_prestation
							ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
							AND Active=0
							AND Actif=0
							
						ORDER BY Libelle, LibellePole";

				$resultPrestation=mysqli_query($bdd,$requeteSite);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationDepartSelect=$_SESSION['FiltreRHDODM_PrestationDep'];
				if($_POST){$PrestationDepartSelect=$_POST['prestationDepart'];}
				$_SESSION['FiltreRHDODM_PrestationDep']=$PrestationDepartSelect;	
				
				echo "<option name='0_0' value='0_0' Selected></option>";
				if ($nbPrestation > 0)
				{
					while($rowsite=mysqli_fetch_array($resultPrestation))
					{
						$selected="";
						if($PrestationDepartSelect<>"")
							{if($PrestationDepartSelect==$rowsite['Id']."_".$rowsite['Id_Pole']){$selected="selected";}}
						echo "<option value='".$rowsite['Id']."_".$rowsite['Id_Pole']."' ".$selected.">";
						$pole="";
						if($rowsite['Id_Pole']>0){$pole=" - ".$rowsite['LibellePole'];}
						echo str_replace("'"," ",stripslashes($rowsite['Libelle'].$pole))."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation de destination:";}else{echo "Destination site :";} ?>
				<select class="prestation" style="width:100px;" name="prestationDestination" onchange="submit();">
				<?php
					$requeteSite="SELECT DISTINCT Id, Libelle, '' AS LibellePole, 0 AS Id_Pole
						FROM new_competences_prestation
						WHERE Active=0
						AND Id NOT IN (
							SELECT Id_Prestation
							FROM new_competences_pole    
						)
						
						UNION 
						
						SELECT DISTINCT new_competences_pole.Id_Prestation, new_competences_prestation.Libelle, 
							new_competences_pole.Libelle AS LibellePole, new_competences_pole.Id AS Id_Pole
							FROM new_competences_pole
							INNER JOIN new_competences_prestation
							ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
							AND Active=0
							AND Actif=0
						ORDER BY Libelle, LibellePole";

				$resultPrestation=mysqli_query($bdd,$requeteSite);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationDestinationSelect=$_SESSION['FiltreRHDODM_PrestationDes'];
				if($_POST){$PrestationDestinationSelect=$_POST['prestationDestination'];}
				$_SESSION['FiltreRHDODM_PrestationDes']=$PrestationDestinationSelect;	
				
				echo "<option name='0_0' value='0_0' Selected></option>";
				if ($nbPrestation > 0)
				{
					while($rowsite=mysqli_fetch_array($resultPrestation))
					{
						$selected="";
						if($PrestationDestinationSelect<>"")
							{if($PrestationDestinationSelect==$rowsite['Id']."_".$rowsite['Id_Pole']){$selected="selected";}}
						echo "<option value='".$rowsite['Id']."_".$rowsite['Id_Pole']."' ".$selected.">";
						$pole="";
						if($rowsite['Id_Pole']>0){$pole=" - ".$rowsite['LibellePole'];}
						echo str_replace("'"," ",stripslashes($rowsite['Libelle'].$pole))."</option>\n";
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
						$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
								CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
								FROM rh_personne_petitdeplacement
								LEFT JOIN new_rh_etatcivil
								ON new_rh_etatcivil.Id=rh_personne_petitdeplacement.Id_Personne
								WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_petitdeplacement.Id_Prestation) IN 
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
								)
								ORDER BY Personne ASC";
						$resultPersonne=mysqli_query($bdd,$requetePersonne);
						$NbPersonne=mysqli_num_rows($resultPersonne);
						
						$personne=$_SESSION['FiltreRHDODM_Personne'];
						if($_POST){$personne=$_POST['personne'];}
						$_SESSION['FiltreRHDODM_Personne']= $personne;
						
						while($rowPersonne=mysqli_fetch_array($resultPersonne))
						{
							echo "<option value='".$rowPersonne['Id']."'";
							if ($personne == $rowPersonne['Id']){echo " selected ";}
							echo ">".$rowPersonne['Personne']."</option>\n";
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
						$mois=$_SESSION['FiltreRHDODM_Mois'];
						if($_POST){$mois=$_POST['mois'];}
						$_SESSION['FiltreRHDODM_Mois']=$mois;
						
						for($i=0;$i<=11;$i++){
							$numMois=$i+1;
							if($numMois<10){$numMois="0".$numMois;}
							echo "<option value='".$numMois."'";
							if($mois== ($i+1)){echo " selected ";}
							echo ">".$arrayMois[$i]."</option>\n";
						}
						
						$annee=$_SESSION['FiltreRHDODM_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						if($annee==""){$annee=date("Y");}
						$_SESSION['FiltreRHDODM_Annee']=$annee;
					?>
				</select>
				<?php
					$MoisCumules=$_SESSION['FiltreRHDODM_MoisCumules'];
					if($_POST){
						if(isset($_POST['MoisCumules'])){$MoisCumules="checked";}else{$MoisCumules="";}				
					}
					$_SESSION['FiltreRHDODM_MoisCumules']=$MoisCumules;
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
			<td width="20%" class="Libelle" colspan="4">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Etat :";}else{echo "State :";} ?>
					<?php
						$PrisEnCompte=$_SESSION['FiltreRHDODM_EtatPrisEnCompte'];
						$NonPrisEnCompte=$_SESSION['FiltreRHDODM_EtatNonPrisEnCompte'];
						if($_POST){
							if(isset($_POST['PrisEnCompte'])){$PrisEnCompte="checked";}else{$PrisEnCompte="";}
							if(isset($_POST['NonPrisEnCompte'])){$NonPrisEnCompte="checked";}else{$NonPrisEnCompte="";}
						}
						$_SESSION['FiltreRHDODM_EtatPrisEnCompte']=$PrisEnCompte;
						$_SESSION['FiltreRHDODM_EtatNonPrisEnCompte']=$NonPrisEnCompte;
					?>
					<input type="checkbox" id="NonPrisEnCompte" name="NonPrisEnCompte" value="NonPrisEnCompte" <?php echo $NonPrisEnCompte; ?>><?php if($_SESSION["Langue"]=="FR"){echo "NON PRIS EN COMPTE";}else{echo "NOT TAKEN INTO ACCOUNT";} ?> &nbsp;&nbsp;
					<input type="checkbox" id="PrisEnCompte" name="PrisEnCompte" value="PrisEnCompte" <?php echo $PrisEnCompte; ?>><?php if($_SESSION["Langue"]=="FR"){echo "PRIS EN COMPTE";}else{echo "TAKEN INTO ACCOUNT";} ?> &nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td valign="top" colspan="8" class="Libelle" <?php if($Menu<>4){echo "style='display:none;'";} ?>>
				<?php if($LangueAffichage=="FR"){echo "Responsable Projet";}else{echo "Project manager";}?> :<br>
						<?php
						
							$Id_RespProjet=$_SESSION['FiltreRHDODM_RespProjet'];
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
							$_SESSION['FiltreRHDODM_RespProjet']=$Id_RespProjet;
	
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
									$checkboxes = explode(',',$_SESSION['FiltreRHDODM_RespProjet']);
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
		$requeteAnalyse="SELECT rh_personne_petitdeplacement.Id ";
		$requete2="SELECT rh_personne_petitdeplacement.Id, rh_personne_petitdeplacement.Id_Personne,rh_personne_petitdeplacement.Id_Prestation,rh_personne_petitdeplacement.Id_Pole,
			rh_personne_petitdeplacement.Id_PrestationDeplacement,rh_personne_petitdeplacement.Id_PoleDeplacement,rh_personne_petitdeplacement.DateCreation,rh_personne_petitdeplacement.Id_Createur,
			rh_personne_petitdeplacement.Id_Metier,rh_personne_petitdeplacement.Montant,rh_personne_petitdeplacement.AvancePonctuelle,rh_personne_petitdeplacement.Periode,
			rh_personne_petitdeplacement.DatePriseEnCompteRH,rh_personne_petitdeplacement.DateDebut,rh_personne_petitdeplacement.DateFin,
			CONCAT((SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation),
				IF(Id_Pole>0,' - ','') ,
				IF(Id_Pole>0,(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_Pole),'')
			) AS PrestationDepart,DatePriseEnCompteN1,
			CONCAT((SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_PrestationDeplacement),
				IF(Id_PoleDeplacement>0,' - ','') ,
				IF(Id_PoleDeplacement>0,(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_PoleDeplacement),'')
			) AS PrestationDestination,rh_personne_petitdeplacement.FraisReel,rh_personne_petitdeplacement.Lieu,
			IF(Montant>0,1,0) AS DemandeAvance,
			(SELECT new_competences_metier.LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=rh_personne_petitdeplacement.Id_Metier) AS MetierEN,
			(SELECT new_competences_metier.Libelle FROM new_competences_metier WHERE new_competences_metier.Id=rh_personne_petitdeplacement.Id_Metier) AS Metier,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_petitdeplacement.Id_Createur) AS Demandeur,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_petitdeplacement.Id_Personne) AS Personne ";
		$requete=" FROM rh_personne_petitdeplacement
					WHERE Suppr=0 
					";
		if($Menu==4){
			$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_petitdeplacement.Id_Prestation) IN 
				(
					SELECT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$_SESSION['Id_Personne']." 
					AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
				) ";
			
			if($_SESSION['FiltreRHDODM_EtatPrisEnCompte']<>"" || $_SESSION['FiltreRHDODM_EtatNonPrisEnCompte']<>""){
				$requete.=" AND ( ";
				if($_SESSION['FiltreRHDODM_EtatPrisEnCompte']<>""){
					$requete.=" rh_personne_petitdeplacement.DatePriseEnCompteRH>'0001-01-01' OR ";
				}
				if($_SESSION['FiltreRHDODM_EtatNonPrisEnCompte']<>""){
					$requete.=" rh_personne_petitdeplacement.DatePriseEnCompteRH<='0001-01-01' OR ";
				}
				$requete=substr($requete,0,-3);
				$requete.=" ) ";
			}
			else{
				$requete.=" AND ( ";
				$requete.=" rh_personne_petitdeplacement.DatePriseEnCompteRH<='0001-01-01' ";
				$requete.=" ) ";
			}
		}
		elseif($Menu==7){
			$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_petitdeplacement.Id_Prestation) IN 
				(
					SELECT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$_SESSION['Id_Personne']." 
					AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.")
				) ";
				
			if($_SESSION['FiltreRHDODM_EtatPrisEnCompte']<>"" || $_SESSION['FiltreRHDODM_EtatNonPrisEnCompte']<>""){
				$requete.=" AND ( ";
				if($_SESSION['FiltreRHDODM_EtatPrisEnCompte']<>""){
					$requete.=" (SELECT COUNT(rh_personne_petitdeplacement_typebesoin.Id) 
								FROM rh_personne_petitdeplacement_typebesoin
								WHERE rh_personne_petitdeplacement_typebesoin.Suppr=0 
								AND rh_personne_petitdeplacement_typebesoin.ValidationService=0
								AND rh_personne_petitdeplacement_typebesoin.Id_Personne_PetitDeplacement=rh_personne_petitdeplacement.Id
								AND (SELECT ServiceConcerne 
									FROM rh_typebesoin 
									WHERE rh_typebesoin.Id=rh_personne_petitdeplacement_typebesoin.Id_TypeBesoin)='Moyens généraux')=0 OR ";
				}
				if($_SESSION['FiltreRHDODM_EtatNonPrisEnCompte']<>""){
					$requete.=" (SELECT COUNT(rh_personne_petitdeplacement_typebesoin.Id) 
								FROM rh_personne_petitdeplacement_typebesoin
								WHERE rh_personne_petitdeplacement_typebesoin.Suppr=0 
								AND rh_personne_petitdeplacement_typebesoin.ValidationService=0
								AND rh_personne_petitdeplacement_typebesoin.Id_Personne_PetitDeplacement=rh_personne_petitdeplacement.Id
								AND (SELECT ServiceConcerne FROM rh_typebesoin WHERE rh_typebesoin.Id=rh_personne_petitdeplacement_typebesoin.Id_TypeBesoin)='Moyens généraux')>0 OR ";
				}
				$requete=substr($requete,0,-3);
				$requete.=" ) ";
			}
			else{
				$requete.=" AND (SELECT COUNT(rh_personne_petitdeplacement_typebesoin.Id) 
								FROM rh_personne_petitdeplacement_typebesoin
								WHERE rh_personne_petitdeplacement_typebesoin.Suppr=0 
								AND rh_personne_petitdeplacement_typebesoin.ValidationService=0
								AND rh_personne_petitdeplacement_typebesoin.Id_Personne_PetitDeplacement=rh_personne_petitdeplacement.Id
								AND (SELECT ServiceConcerne FROM rh_typebesoin WHERE rh_typebesoin.Id=rh_personne_petitdeplacement_typebesoin.Id_TypeBesoin)='Moyens généraux')>0 ";
				
			}
		}
		elseif($Menu==8){
			$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_petitdeplacement.Id_Prestation) IN 
				(
					SELECT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$_SESSION['Id_Personne']." 
					AND Id_Poste IN (".$IdPosteAssistantAdministratif.")
				) ";
				
				if($_SESSION['FiltreRHDODM_EtatPrisEnCompte']<>"" || $_SESSION['FiltreRHDODM_EtatNonPrisEnCompte']<>""){
					$requete.=" AND ( ";
					if($_SESSION['FiltreRHDODM_EtatPrisEnCompte']<>""){
						$requete.=" ((SELECT COUNT(rh_personne_petitdeplacement_typebesoin.Id) 
									FROM rh_personne_petitdeplacement_typebesoin
									WHERE rh_personne_petitdeplacement_typebesoin.Suppr=0 
									AND rh_personne_petitdeplacement_typebesoin.ValidationService=0
									AND rh_personne_petitdeplacement_typebesoin.Id_Personne_PetitDeplacement=rh_personne_petitdeplacement.Id
									AND (SELECT ServiceConcerne 
										FROM rh_typebesoin 
										WHERE rh_typebesoin.Id=rh_personne_petitdeplacement_typebesoin.Id_TypeBesoin)='Accueil')=0
									AND (Montant=0 OR (Montant>0 AND DatePriseEnCompteAvance>'0001-01-01'))
									) OR ";
					}
					if($_SESSION['FiltreRHDODM_EtatNonPrisEnCompte']<>""){
						$requete.=" ((SELECT COUNT(rh_personne_petitdeplacement_typebesoin.Id) 
									FROM rh_personne_petitdeplacement_typebesoin
									WHERE rh_personne_petitdeplacement_typebesoin.Suppr=0 
									AND rh_personne_petitdeplacement_typebesoin.ValidationService=0
									AND rh_personne_petitdeplacement_typebesoin.Id_Personne_PetitDeplacement=rh_personne_petitdeplacement.Id
									AND (SELECT ServiceConcerne FROM rh_typebesoin WHERE rh_typebesoin.Id=rh_personne_petitdeplacement_typebesoin.Id_TypeBesoin)='Accueil')>0 
									OR (Montant>0 AND DatePriseEnCompteAvance<='0001-01-01')
									) OR ";
					}
					$requete=substr($requete,0,-3);
					$requete.=" ) ";
				}
				else{
					$requete.=" AND ((SELECT COUNT(rh_personne_petitdeplacement_typebesoin.Id) 
									FROM rh_personne_petitdeplacement_typebesoin
									WHERE rh_personne_petitdeplacement_typebesoin.Suppr=0 
									AND rh_personne_petitdeplacement_typebesoin.ValidationService=0
									AND rh_personne_petitdeplacement_typebesoin.Id_Personne_PetitDeplacement=rh_personne_petitdeplacement.Id
									AND (SELECT ServiceConcerne FROM rh_typebesoin WHERE rh_typebesoin.Id=rh_personne_petitdeplacement_typebesoin.Id_TypeBesoin)='Accueil')>0 
									OR (Montant>0 AND DatePriseEnCompteAvance<='0001-01-01')
									) ";
				}
		}
		elseif($Menu==3){
			$requete.=" AND	CONCAT(rh_personne_petitdeplacement.Id_Prestation,'_',rh_personne_petitdeplacement.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$_SESSION["Id_Personne"]."
					AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
					) ";
			if($_SESSION['FiltreRHDODM_EtatPrisEnCompte']<>"" || $_SESSION['FiltreRHDODM_EtatNonPrisEnCompte']<>""){					
				$requete.=" AND ( ";
				if($_SESSION['FiltreRHDODM_EtatPrisEnCompte']<>""){
					$requete.=" rh_personne_petitdeplacement.DatePriseEnCompteN1>'0001-01-01' OR ";
				}
				if($_SESSION['FiltreRHDODM_EtatNonPrisEnCompte']<>""){
					$requete.=" rh_personne_petitdeplacement.DatePriseEnCompteN1<='0001-01-01' OR ";
				}
				$requete=substr($requete,0,-3);
				$requete.=" ) ";
			}
			else{
				$requete.=" AND ( ";
				$requete.=" rh_personne_petitdeplacement.DatePriseEnCompteN1<='0001-01-01' ";
				$requete.=" ) ";
			}
		}
		if($_SESSION['FiltreRHDODM_PrestationDep']<>0){
			$requete.=" AND CONCAT(rh_personne_petitdeplacement.Id_Prestation,'_',rh_personne_petitdeplacement.Id_Pole)='".$_SESSION['FiltreRHDODM_PrestationDep']."' ";
		}
		if($_SESSION['FiltreRHDODM_PrestationDes']<>0){
			$requete.=" AND CONCAT(rh_personne_petitdeplacement.Id_PrestationDeplacement,'_',rh_personne_petitdeplacement.Id_PoleDeplacement)='".$_SESSION['FiltreRHDODM_PrestationDes']."' ";
		}
		if($_SESSION['FiltreRHDODM_Personne']<>0 && $_SESSION['FiltreRHDODM_Personne']<>""){
			$requete.=" AND rh_personne_petitdeplacement.Id_Personne=".$_SESSION['FiltreRHDODM_Personne']." ";
		}
		if($Menu==4){
			if($_SESSION['FiltreRHDODM_RespProjet']<>""){
				$requete.="AND CONCAT(rh_personne_petitdeplacement.Id_Prestation,'_',rh_personne_petitdeplacement.Id_Pole) 
							IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
								FROM new_competences_personne_poste_prestation
								WHERE Id_Personne IN (".$_SESSION['FiltreRHDODM_RespProjet'].")
								AND Id_Poste IN (".$IdPosteResponsableProjet.")
							)
							";
			}
		}
		if($_SESSION['FiltreRHDODM_Mois']<>0){
			if($_SESSION['FiltreRHDODM_MoisCumules']<>""){
				$requete.="AND CONCAT(YEAR(rh_personne_petitdeplacement.DateDebut),'_',IF(MONTH(rh_personne_petitdeplacement.DateDebut)<10,CONCAT('0',MONTH(rh_personne_petitdeplacement.DateDebut)),MONTH(rh_personne_petitdeplacement.DateDebut)))>='".$_SESSION['FiltreRHDODM_Annee'].'_'.$_SESSION['FiltreRHDODM_Mois']."' 
					AND CONCAT(YEAR(rh_personne_petitdeplacement.DateFin),'_',IF(MONTH(rh_personne_petitdeplacement.DateFin)<10,CONCAT('0',MONTH(rh_personne_petitdeplacement.DateFin)),MONTH(rh_personne_petitdeplacement.DateFin)))<='".$_SESSION['FiltreRHDODM_Annee']."_12'
				";
			}
			else{
				$requete.="AND CONCAT(YEAR(rh_personne_petitdeplacement.DateDebut),'_',IF(MONTH(rh_personne_petitdeplacement.DateDebut)<10,CONCAT('0',MONTH(rh_personne_petitdeplacement.DateDebut)),MONTH(rh_personne_petitdeplacement.DateDebut)))>='".$_SESSION['FiltreRHDODM_Annee'].'_'.$_SESSION['FiltreRHDODM_Mois']."' 
					AND CONCAT(YEAR(rh_personne_petitdeplacement.DateFin),'_',IF(MONTH(rh_personne_petitdeplacement.DateFin)<10,CONCAT('0',MONTH(rh_personne_petitdeplacement.DateFin)),MONTH(rh_personne_petitdeplacement.DateFin)))<='".$_SESSION['FiltreRHDODM_Annee'].'_'.$_SESSION['FiltreRHDODM_Mois']."'
				";
			}
		}
		else{
			$requete.="AND  YEAR(rh_personne_petitdeplacement.DateDebut)<='".$_SESSION['FiltreRHDODM_Annee']."' 
					AND YEAR(rh_personne_petitdeplacement.DateFin)>='".$_SESSION['FiltreRHDODM_Annee']."' ";
		}
		$requeteOrder="";
		if($_SESSION['TriRHDODM_General']<>""){
			$requeteOrder="ORDER BY ".substr($_SESSION['TriRHDODM_General'],0,-1);
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
				if($page>1){echo "<b> <a style='color:#00599f;' href='Liste_DODM.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=0'><<</a> </b>";}
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
							echo "<b> <a style='color:#00599f;' href='Liste_DODM.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($page<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_DODM.php?Menu=".$Menu."&TDB=".$TDB."&OngletTDB=".$OngletTDB."&debut=1&Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DODM.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Id"><?php if($_SESSION["Langue"]=="FR"){echo "N° demande";}else{echo "Request number";} ?><?php if($_SESSION['TriRHDODM_Id']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHDODM_Id']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DODM.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=PrestationDepart"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?><?php if($_SESSION['TriRHDODM_PrestationDepart']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHDODM_PrestationDepart']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DODM.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=PrestationDestination"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation destination";}else{echo "Destination site";} ?><?php if($_SESSION['TriRHDODM_PrestationDestination']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHDODM_PrestationDestination']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DODM.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Personne"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriRHDODM_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHDODM_Personne']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DODM.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Demandeur"><?php if($_SESSION["Langue"]=="FR"){echo "Demandeur";}else{echo "Applicant";} ?><?php if($_SESSION['TriRHDODM_Demandeur']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHDODM_Demandeur']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DODM.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=DateDebut"><?php if($_SESSION["Langue"]=="FR"){echo "Date début";}else{echo "Start date";} ?><?php if($_SESSION['TriRHDODM_DateDebut']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHDODM_DateDebut']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DODM.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=DateFin"><?php if($_SESSION["Langue"]=="FR"){echo "Date fin";}else{echo "End date";} ?><?php if($_SESSION['TriRHDODM_DateFin']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHDODM_DateFin']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DODM.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=Lieu"><?php if($_SESSION["Langue"]=="FR"){echo "Lieu";}else{echo "Place of issue";} ?><?php if($_SESSION['TriRHDODM_Lieu']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHDODM_Lieu']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DODM.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=FraisReel"><?php if($_SESSION["Langue"]=="FR"){echo "Frais";}else{echo "Fee";} ?><?php if($_SESSION['TriRHDODM_FraisReel']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHDODM_FraisReel']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Besoins de réservation";}else{echo "Booking needs";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_DODM.php?Menu=<?php echo $Menu; ?>&TDB=<?php echo $TDB; ?>&OngletTDB=<?php echo $OngletTDB; ?>&Tri=DemandeAvance"><?php if($_SESSION["Langue"]=="FR"){echo "Demande avance";}else{echo "Advance Fee Request";} ?><?php if($_SESSION['TriRHDODM_DemandeAvance']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRHDODM_Lieu']=="ASC"){echo "&darr;";}?></a></td>
					<?php 
						if($Menu==4){
					?>
					<td class='EnTeteTableauCompetences' width="6%" style="text-align:center;">
						<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Valider la prise en compte";}else{echo "Validate the taking into account";}?>" onclick="if(!confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir valider la sélection ?";}else{echo "Are you sure you want to validate the selection?";} ?>')) return false;" name="validerSelectionRH" value="<?php if($_SESSION["Langue"]=="FR"){echo "Valider la date de prise en compte";}else{echo "Validate the date of taking into account";} ?>"><br>
						<input  type='checkbox' id="check_ValidePriseEnCompte" name="check_ValidePriseEnCompte" value="" checked onchange="CocherPriseEnCompte()">
					</td>
					<?php 
						}
					?>
					<?php 
						if($Menu==4 || $Menu==8 || $Menu==3){
					?>
					<td class='EnTeteTableauCompetences' width="8%" style="text-align:center;">
						<?php if($_SESSION["Langue"]=="FR"){echo "Excel Demande avance";}else{echo "Excel Advance Fee Request";} ?>
					</td>
					<?php 
						}
						if($Menu==3){
					?>
					<td class='EnTeteTableauCompetences' width="4%" style="text-align:center;">
						<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Pris en compte";}else{echo "Taken into account";}?>" name="priseEnCompte" value="<?php if($_SESSION["Langue"]=="FR"){echo "Pris en compte";}else{echo "Taken into account";} ?>"><br>
						<input type='checkbox' id="check_Valide" name="check_Valide" value="" checked onchange="CocherValide()">
					</td>
					<?php 
						}
					?>
					<?php 
						if($Menu==4){
					?>
					<td class='EnTeteTableauCompetences' width="2%" style="text-align:center;">ODM
					</td>
					<?php 
						}
					?>
					<td class="EnTeteTableauCompetences" width="3%">Excel</td>
					<?php 
						if($Menu==4){
					?>
					<td class='EnTeteTableauCompetences' width="2%" style="text-align:center;">
					</td>
					<?php 
						}
					?>
				</tr>
	<?php
			
			if(isset($_POST['validerSelectionRH'])){
				while($row=mysqli_fetch_array($result)){
					if (isset($_POST['checkRH_'.$row['Id'].''])){
						$requeteUpdate="UPDATE rh_personne_petitdeplacement SET 
								Id_RH=".$_SESSION['Id_Personne'].",
								DatePriseEnCompteRH='".date('Y-m-d')."'
								WHERE Id=".$row['Id']." ";
						$resultat=mysqli_query($bdd,$requeteUpdate);
					}
				}
			}
			if(isset($_POST['priseEnCompte'])){
				mysqli_data_seek($result,0);
				while($row=mysqli_fetch_array($result)){
					if (isset($_POST['checkPriseEnCompte_'.$row['Id'].''])){
						if($Menu==3){
							if(DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$row['Id_Prestation'],$row['Id_Pole'])){
								$requeteUpdate="UPDATE rh_personne_petitdeplacement SET 
									DatePriseEnCompteN1='".date('Y-m-d')."',
									Id_N1=".$_SESSION['Id_Personne']."
									WHERE Id=".$row['Id']." ";
								$resultat=mysqli_query($bdd,$requeteUpdate);
							}
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
					
					$Etat="";
					if($row['DatePriseEnCompteRH']>'0001-01-01'){
						$Etat="<img src=\"../../Images/tick.png\" border=\"0\">";
					}
					
					if($_SESSION["Langue"]=="FR"){$frais="Calendaires";}else{$frais= "Calendar";}
					if($row['FraisReel']==1){
						if($_SESSION["Langue"]=="FR"){$frais="Réels";}else{$frais= "Real";}
					}
					
					$demandeAvance="";
					if($row['Montant']>0){
						if($_SESSION["Langue"]=="FR"){$demandeAvance="Oui";}else{$demandeAvance="Yes";}
					}
					
					$besoinReservation="";
					if($_SESSION["Langue"]=="FR"){
						$req="SELECT 
							(SELECT Libelle FROM rh_typebesoin WHERE rh_typebesoin.Id=Id_TypeBesoin) AS TypeBesoin,
							(SELECT ServiceConcerne FROM rh_typebesoin WHERE rh_typebesoin.Id=Id_TypeBesoin) AS ServiceConcerne,
							ValidationService
							FROM rh_personne_petitdeplacement_typebesoin 
							WHERE Suppr=0 
							AND Id_Personne_PetitDeplacement=".$row['Id'];
					}
					else{
						$req="SELECT 
							(SELECT LibelleEN FROM rh_typebesoin WHERE rh_typebesoin.Id=Id_TypeBesoin) AS TypeBesoin,
							(SELECT ServiceConcerne FROM rh_typebesoin WHERE rh_typebesoin.Id=Id_TypeBesoin) AS ServiceConcerne,
							ValidationService
							FROM rh_personne_petitdeplacement_typebesoin 
							WHERE Suppr=0 
							AND Id_Personne_PetitDeplacement=".$row['Id'];
					}
					$resultBesoins=mysqli_query($bdd,$req);
					$nbBesoins=mysqli_num_rows($resultBesoins);
					
					$img="<img width='15px' src='../../Images/tick.png' border='0' alt='Check' title='Check'>";
					if($nbBesoins>0){
						$besoinReservation.="<table width='100%'>";
						while($rowBesoins=mysqli_fetch_array($resultBesoins)){
							$fait="";
							if($rowBesoins['ValidationService']==1){$fait=$img;}
							$besoinReservation.="<tr><td style='border-bottom:1px dotted black'>".$rowBesoins['TypeBesoin']."</td><td style='border-bottom:1px dotted black'>".$fait."</td></tr>";
						}
						$besoinReservation.="</table>";
					}
		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td align="center"><a style="color:#3e65fa;" href="javascript:OuvreFenetreModif(<?php echo $Menu; ?>,<?php echo $row['Id']; ?>)"><?php echo stripslashes($row['Id']);?></a></td>
						<td><?php echo stripslashes($row['PrestationDepart']);?></td>
						<td><?php echo stripslashes($row['PrestationDestination']);?></td>
						<td><?php echo stripslashes($row['Personne']);?></td>
						<td><?php echo stripslashes($row['Demandeur']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateDebut']);?></td>
						<td><?php if($row['DateFin']<="0001-01-01"){echo "-";}else{echo AfficheDateJJ_MM_AAAA($row['DateFin']);}?></td>
						<td><?php echo stripslashes($row['Lieu']);?></td>
						<td><?php echo $frais;?></td>
						<td><?php echo $besoinReservation;?></td>
						<td><?php echo $demandeAvance;?></td>
						<?php 
							if($Menu==4){
						?>
						<td align="center">
						<?php
							if($row['DatePriseEnCompteRH']<="0001-01-01"){
								echo "<input class='checkRH' type='checkbox' name='checkRH_".$row['Id']."' value='' checked>";
							}
							else{
								echo $Etat;
							}
						?>
						</td>
						<?php
							}
						?>
						<?php 
							if($Menu==4 || $Menu==8 || $Menu==3){
						?>
						<td align="center">
							<?php if($row['Montant']>0){ ?>
							<a href="javascript:GenererAvanceFrais('<?php echo $row['Id'];?>')">
								<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
							</a>
							<?php } ?>
						</td>
						<?php 
							}
							if($Menu==3){
						?>
						<td align="center">
							<?php 
								if(($row['DatePriseEnCompteN1']<='0001-01-01' && DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$row['Id_Prestation'],$row['Id_Pole'])))
								{
									echo "<input class='check' type='checkbox' name='checkPriseEnCompte_".$row['Id']."' value='' checked>";
								}
							?>
						</td>
						<?php 
							}
							if($Menu==4){
						?>
								<td align="center">
						<?php
								//Vérifier si un contrat n'existe pas déjà 
								$req="SELECT Id FROM rh_personne_contrat WHERE Suppr=0 AND Id_DODM=".$row['Id'];
								$resultODM=mysqli_query($bdd,$req);
								$nbODM=mysqli_num_rows($resultODM);
								if($nbODM==0){
								$Id_Contrat=IdContratEC($row['Id_Personne']);
									if($Id_Contrat>0){
							?>
										<input class="Bouton" type="button" id="nouveauODM" name="nouveauODM" value="<?php if($_SESSION["Langue"]=="FR"){echo "ODM";}else{echo "MO";} ?>" onClick="NouveauODM(<?php echo $row['Id_Personne']; ?>,<?php echo $Id_Contrat; ?>,'Liste_DODM',<?php echo $row['Id']; ?>)">
							<?php 
									}
								}
								else{
									$rowDODM=mysqli_fetch_array($resultODM);
							?>
									<input class="Bouton" type="button" id="modifODM" name="modifODM" value="<?php echo "n° ".$rowDODM['Id']; ?>" onClick="OuvreFenetreModifDODM(<?php echo $row['Id_Personne']; ?>,<?php echo $rowDODM['Id']; ?>,'Liste_DODM',<?php echo $row['Id']; ?>)">
							<?php
								}
						?>
								</td>
						<?php
							}
						?>
						<td>
							<a href="javascript:OuvreFormatExcel('<?php echo $row['Id']; ?>')">
							<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
							</a>
						</td>
						<?php 
						if($Menu==4){
						?>
						<td>
							<a class="LigneTableauRecherchePersonne" style="cursor:pointer;" onclick="if(window.confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir supprimer ?";}else{echo "Are you sure you want to delete ?";} ?>')){OuvreFenetreSuppr('<?php echo $Menu; ?>','<?php echo $row['Id']; ?>');}else{return false;}"><img src="../../Images/Suppression.gif" border="0" title="Supprimer" alt="Suppression"></a>
						</td>
						<?php 
							}
						?>
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
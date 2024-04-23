<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout_Critere(){
			var w=window.open("Ajout_Critere.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=1000,height=450");
			w.focus();
		}
		function Suppr_Critere(critere,valeur){
			var w=window.open("Ajout_Critere.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=400");
			w.focus();
		}
		function OuvreFenetreModif(Id,Id_Personne,Id_Dossier){
			var w=window.open("Modif_Dossier.php?Mode=M&Id="+Id+"&Id_Personne="+Id_Personne+"&Id_Dossier="+Id_Dossier,"PageDossier","status=no,menubar=no,scrollbars=yes,width=1300,height=700");
			w.focus();
		}
		function OuvreFenetreDupliquer(Id,Id_Personne){
			var w=window.open("Dupliquer_Dossier.php?Id="+Id+"&Id_Personne="+Id_Personne,"PageDupliquer","status=no,menubar=no,scrollbars=yes,width=1300,height=700");
			w.focus();
		}
		function OuvreFenetreSuppr(Id,Id_Personne,Id_Dossier){
			if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
				var w=window.open("Modif_Dossier.php?Mode=S&Id="+Id+"&Id_Personne="+Id_Personne+"&Id_Dossier="+Id_Dossier,"PageDossier","status=no,menubar=no,scrollbars=yes,width=130,height=60");
				w.focus();
			}
		}
		function OuvreFenetreArchive(Id,Id_Personne,Id_Dossier){
			if(window.confirm('Etes-vous sûr de vouloir archiver ?')){
				var w=window.open("Modif_Dossier.php?Mode=Archiver&Id="+Id+"&Id_Personne="+Id_Personne+"&Id_Dossier="+Id_Dossier,"PageDossier","status=no,menubar=no,scrollbars=yes,width=130,height=60");
				w.focus();
			}
		}
		function Excel(){
			var w=window.open("ExtractDossier.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
		}
		function Excel2(){
			var w=window.open("ExtractDossier2.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
		}
		function Excel3(){
			var w=window.open("ExtractDossier3.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
		}
		function rechercher(){
			formulaire.valeurRecherche.value = formulaire.rechercheOF.value;
			formulaire.numDossier.onchange();
		}
	</script>
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>		
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../../JS/js/jquery-ui-1.8.5.min.js"></script>
</head>
<?php
require("../../../Menu.php");
require("../../Fonctions.php");

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));

if($_POST){
	if(isset($_POST['Recherche_RAZ'])){
		
		$tab = array("MSNPage","ReferencePage","ProgrammePage","DateInterventionPage","VacationPage");
		foreach($tab as $tri){
			$_SESSION['Filtre'.$tri]="";
			$_SESSION['Filtre'.$tri."2"]="";
		}
		
		$tab = array("Programme","MSN","Reference","Client","TypeDossier","Priorite","Caec","Section","StatutPREPA","Titre","Poste","Localisation","StatutDossier","DatePrevisionnelleIntervention","DateInterventionDebut","DateInterventionFin","SansDateIntervention","VacationPROD","NumIC","StatutPROD","DateInterventionQDebut","DateInterventionQFin","SansDateInterventionQ","VacationQUALITE","IQ","StatutQUALITE");
		foreach($tab as $tri){
			$_SESSION['Filtre'.$tri]="";
			$_SESSION['Filtre'.$tri."2"]="";
		}
		
	}
	elseif(isset($_POST['BtnRechercher'])){
		$_SESSION['ModeFiltre']="oui";
	}
	elseif(isset($_POST['Tri_RAZ'])){
		$tab = array("MSN","Vacation","TypeDossier","Reference","Localisation","Titre","StatutPREPA","Poste","DateIntervention","StatutPROD","RetourPROD","StatutQUALITE","RetourQUALITE","NumFI","DateTERA","DateTERC","CT","TempsPasse","Operateurs");
		foreach($tab as $tri){
			$_SESSION['Tri'.$tri]="";
		}
		$_SESSION['TriGeneral']="";
	}
}

if(isset($_GET['Tri'])){
	$tab = array("MSN","Vacation","TypeDossier","TypeTravail","Reference","Localisation","Titre","StatutPREPA","Poste","DateIntervention","StatutPROD","RetourPROD","StatutQUALITE","RetourQUALITE","NumFI","DateTERA","DateTERC","CT","TempsPasse","Operateurs");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriGeneral']= str_replace($tri." ASC,","",$_SESSION['TriGeneral']);
			$_SESSION['TriGeneral']= str_replace($tri." DESC,","",$_SESSION['TriGeneral']);
			$_SESSION['TriGeneral']= str_replace($tri." ASC","",$_SESSION['TriGeneral']);
			$_SESSION['TriGeneral']= str_replace($tri." DESC","",$_SESSION['TriGeneral']);
			if($_SESSION['Tri'.$tri]==""){$_SESSION['Tri'.$tri]="ASC";$_SESSION['TriGeneral'].= $tri." ".$_SESSION['Tri'.$tri].",";}
			elseif($_SESSION['Tri'.$tri]=="ASC"){$_SESSION['Tri'.$tri]="DESC";$_SESSION['TriGeneral'].= $tri." ".$_SESSION['Tri'.$tri].",";}
			else{$_SESSION['Tri'.$tri]="";}
		}
	}
}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form id="formulaire" class="test" method="POST" action="Liste_Dossier.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Suivi des dossiers</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td colspan="8"><b>&nbsp; Critères de recherche : </b></td>
			<td colspan="10" align="right">
			<a style="text-decoration:none;" href="javascript:OuvreFenetreAjout_Critere()">&nbsp;<img src="../../../Images/Plus2.png" border="0" alt="Ajouter critères" title="Ajouter critères">&nbsp;&nbsp;</a>
			</td>
		</tr>
		<?php
			$tab = array("Programme","MSN","Reference","Client","TypeDossier","Priorite","Caec","Section","StatutPREPA","Titre","Poste","Localisation","StatutDossier","DatePrevisionnelleIntervention","DateInterventionDebut","DateInterventionFin","SansDateIntervention","VacationPROD","NumIC","StatutPROD","DateInterventionQDebut","DateInterventionQFin","SansDateInterventionQ","VacationQUALITE","IQ","StatutQUALITE","CT","TempsPasse","Operateurs");
			foreach($tab as $tri){
				if($_SESSION['Filtre'.$tri]<>""){
					$Titre=$tri;
					if($Titre=="Reference"){$Titre="N° dossier";}
					elseif($Titre=="TypeDossier"){$Titre="Type de dossier";}
					elseif($Titre=="Priorite"){$Titre="Priorité";}
					elseif($Titre=="Caec"){$Titre="CA/EC";}
					elseif($Titre=="StatutPREPA"){$Titre="Statut PREPA";}
					elseif($Titre=="StatutDossier"){$Titre="Statut dossier";}
					elseif($Titre=="DatePrevisionnelleIntervention"){$Titre="Date prévisionnelle inter.";}
					elseif($Titre=="DateInterventionDebut"){$Titre="Date inter. Prod (du)";}
					elseif($Titre=="DateInterventionFin"){$Titre="Date inter. Prod (au)";}
					elseif($Titre=="DateInterventionQDebut"){$Titre="Date inter. Qualité (du)";}
					elseif($Titre=="DateInterventionQFin"){$Titre="Date inter. Qualité (au)";}
					elseif($Titre=="SansDateIntervention"){$Titre="Sans date d'inter. Prod";}
					elseif($Titre=="SansDateInterventionQ"){$Titre="Sans date d'inter. Qualité";}
					elseif($Titre=="VacationPROD"){$Titre="Vacation Prod";}
					elseif($Titre=="VacationQUALITE"){$Titre="Vacation Qualité";}
					elseif($Titre=="NumIC"){$Titre="N° IC";}
					elseif($Titre=="StatutPROD"){$Titre="Statut Prod";}
					elseif($Titre=="StatutQUALITE"){$Titre="Statut Qualité";}
					elseif($Titre=="IQ"){$Titre="Inspecteur qualité";}
					elseif($Titre=="CT"){$Titre="CT";}
					elseif($Titre=="Operateurs"){$Titre="Opérateurs";}
					elseif($Titre=="TempsPasse"){$Titre="Temps passé";}
					
					echo "<tr>
							<td class='Libelle'>&nbsp; ".$Titre." : </td>
							<td colspan='10'>".$_SESSION['Filtre'.$tri]."</td>
						</tr>";
				}
			}		
		?>
		<tr>
			<td height="10">
			</td>
		</tr>
		<tr>
			<td height="10">
				<b>&nbsp; OU</b>
			</td>
		</tr>
		<tr>
			<td width="10%">
				&nbsp; MSN :
			</td>
			<td width="10%"> 
				<?php
					$msn=$_SESSION['FiltreMSNPage'];
					if($_POST){$msn=$_POST['msn'];}
					$_SESSION['FiltreMSNPage']=$msn;
				?>
				<input type="texte" style="text-align:center;" name="msn" size="10" value="<?php echo $msn; ?>">
			</td>
			<td width="5%">
				&nbsp; N° dossier :
			</td>
			<td width="10%"> 
				<?php
					$numDossier=$_SESSION['FiltreReferencePage'];
					if($_POST){$numDossier=$_POST['numDossier'];}
					$_SESSION['FiltreReferencePage']=$numDossier;
				?>
				<input type="texte" style="text-align:center;" name="numDossier" size="15" value="<?php echo $numDossier; ?>">
			</td>
			<td width="5%">&nbsp; Programme : </td>
			<td width="10%">
				<?php
					$programme=$_SESSION['FiltreProgrammePage'];
					if($_POST){$programme=$_POST['programme'];}
					$_SESSION['FiltreProgrammePage']=$programme;
				?>
				<select id="programme" name="programme">
					<option value=""></option>
					<option value="A320" <?php if($programme=="A320"){echo "selected";} ?>>A320</option>
					<option value="A330" <?php if($programme=="A330"){echo "selected";} ?>>A330</option>
					<option value="A350" <?php if($programme=="A350"){echo "selected";} ?>>A350</option>
					<option value="A380" <?php if($programme=="A380"){echo "selected";} ?>>A380</option>
				</select>
			</td>
			<td width="8%">
				&nbsp; Date intervention PROD :
			</td>
			<td width="10%"> 
				<?php
					$dateInterProd=$_SESSION['FiltreDateInterventionPage'];
					if($_POST){$dateInterProd=$_POST['dateInterProd'];}
					$_SESSION['FiltreDateInterventionPage']=$dateInterProd;
				?>
				<input type="date" style="text-align:center;" name="dateInterProd" size="15" value="<?php echo $dateInterProd; ?>">
			</td>
			<td width="5%">
				&nbsp; Vacation PROD :
			</td>
			<td width="10%"> 
				<?php
					$vacation=$_SESSION['FiltreVacationPage'];
					if($_POST){$vacation=$_POST['vacation'];}
					$_SESSION['FiltreVacationPage']=$vacation;
				?>
				<select id="vacation" name="vacation">
					<option value="" <?php if($vacation==''){echo "selected";} ?>></option>
					<option value="J" <?php if($vacation=='J'){echo "selected";} ?>>Jour</option>
					<option value="S" <?php if($vacation=='S'){echo "selected";} ?>>Soir</option>
					<option value="N" <?php if($vacation=='N'){echo "selected";} ?>>Nuit</option>
					<option value="VSD" <?php if($vacation=='VSD'){echo "selected";} ?>>Weekend</option>
				</select>
			</td>
		</tr>
		<tr>
			<td height="10px">
			</td>
		</tr>
		<tr>
			
			<td align="center" colspan="10"><input class="Bouton" name="BtnRechercher" size="10" type="submit" value="Rechercher">&nbsp;&nbsp;&nbsp;&nbsp;
			<input class="Bouton" name="Recherche_RAZ" type="submit" value="Vider les critères de recherche"> &nbsp;&nbsp;&nbsp;&nbsp;
			<input class="Bouton" name="Tri_RAZ" type="submit" value="Effacer les tris"> &nbsp;&nbsp;&nbsp;&nbsp;
			<a style="text-decoration:none;" class="Bouton" href="javascript:Excel()">&nbsp;Extract Excel&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
			<a style="text-decoration:none;" class="Bouton" href="javascript:Excel3()">&nbsp;Extract toutes colonnes&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		<tr><td height="4"></td></tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<?php
			$reqAnalyse="SELECT sp_olwficheintervention.Id ";
			$req2="SELECT sp_olwficheintervention.Id,
				sp_olwficheintervention.Id_Dossier,
				sp_olwdossier.MSN,
				sp_olwdossier.Titre,
				sp_olwdossier.TypeACP AS TypeDossier,
				CONCAT(IF(Ajusteur=1,'Ajustage<br>',''),IF(Elec=1,'Elec<br>',''),IF(Meca=1,'Meca','')) AS TypeTravail,
				sp_olwdossier.Reference,
				sp_olwficheintervention.CommentairePROD,
				sp_olwficheintervention.CommentaireQUALITE,
				sp_olwficheintervention.NumFI,
				sp_olwficheintervention.DateTERA,
				sp_olwficheintervention.DateTERC,
				sp_olwdossier.Id_StatutPREPA,
				(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwdossier.Id_Personne) AS CT,
				(SELECT sp_localisation.Libelle FROM sp_localisation WHERE sp_localisation.Id=sp_olwdossier.Id_ZoneDeTravail) AS Localisation,
				(SELECT sp_poste.Libelle FROM sp_poste WHERE sp_poste.Id=sp_olwdossier.Id_Poste) AS Poste,
				sp_olwdossier.DatePrevisionnelleIntervention,sp_olwficheintervention.Vacation AS Vacation2,
					IF(sp_olwficheintervention.Vacation='',0,
						IF(sp_olwficheintervention.Vacation='J',1,
							IF(sp_olwficheintervention.Vacation='S',2,
								IF(sp_olwficheintervention.Vacation='N',3,
									IF(sp_olwficheintervention.Vacation='VSD',4,0)
								)
							)
						)
					) AS Vacation,sp_olwficheintervention.DateIntervention,
				(SELECT sp_olwretour.Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourPROD) AS RetourPROD,
				(SELECT sp_olwretour.Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourQUALITE) AS RetourQUALITE,
				sp_olwficheintervention.Id_StatutPROD AS StatutPROD,sp_olwficheintervention.Id_StatutQUALITE AS StatutQUALITE,
				TempsProd AS TempsPasse
				";
				
				$req="FROM sp_olwficheintervention 
				LEFT JOIN sp_olwdossier 
				ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id
				
				WHERE sp_olwdossier.Id_Prestation=1598 AND ";
			
				if($_SESSION['FiltreMSNPage']<>""){
					$req.="sp_olwdossier.MSN=".$_SESSION['FiltreMSNPage']." AND ";
				}
				if($_SESSION['FiltreProgrammePage']<>""){
					$req.="sp_olwdossier.Programme='".$_SESSION['FiltreProgrammePage']."' AND ";
				}
				if($_SESSION['FiltreReferencePage']<>""){
					$req.="sp_olwdossier.Reference='".$_SESSION['FiltreReferencePage']."' AND ";
				}
				if($_SESSION['FiltreDateInterventionPage']<>""){
					$req.="sp_olwficheintervention.DateIntervention='".TrsfDate_($_SESSION['FiltreDateInterventionPage'])."' AND ";
				}
				if($_SESSION['FiltreVacationPage']<>""){
					$req.="sp_olwficheintervention.Vacation='".$_SESSION['FiltreVacationPage']."' AND ";
				}
				
				$tab = array("Programme","MSN","Reference","Client","TypeDossier","Priorite","Caec","Section","Titre","Poste","Localisation","StatutDossier","DatePrevisionnelleIntervention","DateInterventionDebut","DateInterventionFin","SansDateIntervention","VacationPROD","DateInterventionQDebut","DateInterventionQFin","SansDateInterventionQ","VacationQUALITE","IQ");
				foreach($tab as $filtre){
					if($_SESSION['Filtre'.$filtre.'2']<>""){
						$tab = explode(";",$_SESSION['Filtre'.$filtre.'2']);
						$req.="(";
						foreach($tab as $valeur){
							 if($valeur<>""){
								 if($filtre=="Programme"){$req.="sp_olwdossier.Programme='".$valeur."' OR ";}
								 if($filtre=="MSN"){$req.="sp_olwdossier.MSN=".$valeur." OR ";}
								 if($filtre=="Reference"){$req.="sp_olwdossier.Reference='".$valeur."' OR ";}
								 if($filtre=="Client"){$req.="sp_olwdossier.Id_Client=".str_replace("_","",$valeur)." OR ";}
								 if($filtre=="TypeDossier"){$req.="sp_olwdossier.TypeACP='".$valeur."' OR ";}
								 if($filtre=="Priorite"){$req.="sp_olwdossier.Priorite='".$valeur."' OR ";}
								 if($filtre=="Caec"){$req.="sp_olwdossier.CaecACP='".$valeur."' OR ";}
								 if($filtre=="Section"){$req.="sp_olwdossier.SectionACP='".$valeur."' OR ";}
								 if($filtre=="Titre"){$req.="sp_olwdossier.Titre='".addslashes($valeur)."' OR ";}
								 if($filtre=="Poste"){$req.="sp_olwdossier.Id_Poste=".str_replace("_","",$valeur)." OR ";}
								 if($filtre=="Localisation"){$req.="sp_olwdossier.Id_ZoneDeTravail=".str_replace("_","",$valeur)." OR ";}
								 if($filtre=="StatutDossier"){$req.="sp_olwdossier.Id_Statut='".$valeur."' OR ";}
								 if($filtre=="DatePrevisionnelleIntervention"){$req.="sp_olwdossier.DatePrevisionnelleIntervention='".TrsfDate_($valeur)."' OR ";}
								 if($filtre=="DateInterventionDebut"){$req.="sp_olwficheintervention.DateIntervention>='".TrsfDate_($valeur)."' OR ";}
								 if($filtre=="DateInterventionFin"){$req.="sp_olwficheintervention.DateIntervention<='".TrsfDate_($valeur)."' OR ";}
								 if($filtre=="SansDateIntervention"){$req.="sp_olwficheintervention.DateIntervention<='0001-01-01' OR ";}
								 if($filtre=="VacationPROD"){$req.="sp_olwficheintervention.Vacation='".addslashes(str_replace("_","",$valeur))."' OR ";}
								 if($filtre=="DateInterventionQDebut"){$req.="sp_olwficheintervention.DateInterventionQ>='".TrsfDate_($valeur)."' OR ";}
								 if($filtre=="DateInterventionQFin"){$req.="sp_olwficheintervention.DateInterventionQ<='".TrsfDate_($valeur)."' OR ";}
								 if($filtre=="SansDateInterventionQ"){$req.="sp_olwficheintervention.DateInterventionQ<='0001-01-01' OR ";}
								 if($filtre=="VacationQUALITE"){$req.="sp_olwficheintervention.VacationQ='".addslashes(str_replace("_","",$valeur))."' OR ";}
								 if($filtre=="IQ"){$req.="sp_olwficheintervention.Id_QUALITE=".str_replace("_","",$valeur)." OR ";}
							 }
						}
						$req=substr($req,0,-3);
						$req.=") AND ";
					}
				}
				
				$tab = array("StatutPROD","StatutQUALITE");
				foreach($tab as $filtre){
					if($_SESSION['Filtre'.$filtre.'2']<>""){
						$tab = explode(";",$_SESSION['Filtre'.$filtre.'2']);
						$req.="(";
						foreach($tab as $valeur){
							 if($valeur<>"0"){
								 if($filtre=="StatutPROD"){$req.="sp_olwficheintervention.Id_StatutPROD='".str_replace("_","",$valeur)."' OR ";}
								 if($filtre=="StatutQUALITE"){$req.="sp_olwficheintervention.Id_StatutQUALITE='".str_replace("_","",$valeur)."' OR ";}
							 }
						}
						$req=substr($req,0,-3);
						$req.=") AND ";
					}
				}
			
			if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
			if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}

			$result=mysqli_query($bdd,$reqAnalyse.$req);
			$nbResulta=mysqli_num_rows($result);
			
			if($_SESSION['TriGeneral']<>""){
				$req.="ORDER BY ".substr($_SESSION['TriGeneral'],0,-1);
			}
			
			$nombreDePages=ceil($nbResulta/200);
			if(isset($_GET['Page'])){$_SESSION['Page']=$_GET['Page'];}
			$req3=" LIMIT ".($_SESSION['Page']*200).",200";
			
			$result=mysqli_query($bdd,$req2.$req.$req3);
			$nbResulta=mysqli_num_rows($result);
		?>
		<tr>
			<td align="center" style="font-size:14px;">
				<?php
					$nbPage=0;
					if($_SESSION['Page']>1){echo "<b> <a style='color:#00599f;' href='Liste_Dossier.php?Page=0'><<</a> </b>";}
					$valeurDepart=1;
					if($_SESSION['Page']<=5){
						$valeurDepart=1;
					}
					elseif($_SESSION['Page']>=($nombreDePages-6)){
						$valeurDepart=$nombreDePages-6;
					}
					else{
						$valeurDepart=$_SESSION['Page']-5;
					}
					for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
						if($i<=$nombreDePages){
							if($i==($_SESSION['Page']+1)){
								echo "<b> [ ".$i." ] </b>"; 
							}	
							else{
								echo "<b> <a style='color:#00599f;' href='Liste_Dossier.php?Page=".($i-1)."'>".$i."</a> </b>";
							}
						}
					}
					if($_SESSION['Page']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_Dossier.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
				?>
			</td>
		</tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr bgcolor="#00325F">
					<td class="EnTeteTableauCompetences" width="1%" style="text-align:center;"></td>
					<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=MSN">MSN<?php if($_SESSION['TriMSN']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriMSN']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="3%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=Vacation">Vacation<?php if($_SESSION['TriVacation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriVacation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="4%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=TypeTravail">Type de travail<?php if($_SESSION['TriTypeTravail']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriTypeTravail']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=Reference">N° dossier<?php if($_SESSION['TriReference']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriReference']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=CT">CT<?php if($_SESSION['TriCT']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriCT']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="14%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=Titre">Titre<?php if($_SESSION['TriTitre']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriTitre']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=Poste">Poste<?php if($_SESSION['TriPoste']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriPoste']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=DateIntervention">Date intervention<?php if($_SESSION['TriDateIntervention']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriDateIntervention']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-align:center;text-decoration:none;color:#ffffff;font-weight:bold;">Compagnons</td>
					<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=TempsPasse">Temps passé<?php if($_SESSION['TriTempsPasse']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriTempsPasse']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="7%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=StatutPROD">Statut PROD<?php if($_SESSION['TriStatutPROD']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriStatutPROD']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=DateTERA">Date TERA<?php if($_SESSION['TriDateTERA']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriDateTERA']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="7%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=RetourPROD">Retour PROD<?php if($_SESSION['TriRetourPROD']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRetourPROD']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="7%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=StatutQUALITE">Statut QUALITE<?php if($_SESSION['TriStatutQUALITE']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriStatutQUALITE']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=DateTERC">Date TERC<?php if($_SESSION['TriDateTERC']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriDateTERC']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=RetourQUALITE">Retour QUALITE<?php if($_SESSION['TriRetourQUALITE']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRetourQUALITE']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="3%" style="text-align:center;"></td>
					<td class="EnTeteTableauCompetences" width="3%" style="text-align:center;"></td>
					<td class="EnTeteTableauCompetences" width="3%" style="text-align:center;"></td>
				</tr>
				<tr>
					<?php
						if ($nbResulta>0){
							$couleur="#ffffff";
							while($row=mysqli_fetch_array($result)){		
								$etoile="";
								$reqFI="SELECT MAX(Id) AS Id FROM sp_olwficheintervention WHERE Id_Dossier=".$row['Id_Dossier'];
								$resultFI=mysqli_query($bdd,$reqFI);
								$nbResultaFI=mysqli_num_rows($resultFI);
								if($nbResultaFI>0){
									$rowFI=mysqli_fetch_array($resultFI);
									if($rowFI['Id']==$row['Id']){$etoile="<img src='../../../Images/etoile-bleu.png' width='8' height='8' border='0'>";}
								}
								$infoBullePROD="";
								$HoverPROD="";
								$infoBulleQUALITE="";
								$HoverQUALITE="";
								if($row['CommentairePROD']<>""){
									$HoverPROD="id='leHover2'";
									$infoBullePROD = "\n<span>".stripslashes($row['CommentairePROD'])."</span>\n";
								}
								
								if($row['CommentaireQUALITE']<>""){
									$HoverQUALITE="id='leHover2'";
									$infoBulleQUALITE = "\n<span>".stripslashes($row['CommentaireQUALITE'])."</span>\n";
								}
								
								$vacation="";
								if($row['Vacation2']=="J"){$vacation="Jour";}
								elseif($row['Vacation2']=="S"){$vacation="Soir";}
								elseif($row['Vacation2']=="N"){$vacation="Nuit";}
								elseif($row['Vacation2']=="VSD"){$vacation="Weekend";}
								
								$couleur="#ffffff";
								if($row['StatutQUALITE']=="TERC"){$couleur="#407c09";}
								elseif($row['StatutPROD']=="TERA"){$couleur="#b0f472";}
								elseif($row['StatutPROD']=="RETOUR PROD"){$couleur="#f6664a";}
								elseif($row['StatutPROD']=="REWORK"){$couleur="#f8fe68";}
								elseif($row['StatutPROD']=="TFS"){
									if($row['RetourPROD']=="Repose"){
										$couleur="#153c8a";
									}
									elseif($row['RetourPROD']=="Finitions / Opérations"){
										$couleur="#53b9e1";
									}
									else{
										$couleur="#fa9696";
									}
								}
								
								$compagnons="";
								$req="SELECT DISTINCT Id_Personne,
									(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwfi_travaileffectue.Id_Personne) AS Personne
									FROM sp_olwfi_travaileffectue 
									WHERE Id_FI=".$row['Id']." 
									ORDER BY Personne;";
								$resultC=mysqli_query($bdd,$req);
								$nbResultaC=mysqli_num_rows($resultC);
								if ($nbResultaC>0){
									while($rowCompagnon=mysqli_fetch_array($resultC)){
										$compagnons.=$rowCompagnon['Personne']."<br>";
									}
								}
								?>
									<tr bgcolor="<?php echo $couleur;?>">
										<td style="text-align:center;">&nbsp;<?php echo $etoile;?></td>
										<td style="text-align:center;">&nbsp;<?php echo $row['MSN'];?></td>
										<td style="text-align:center;border-left:1px solid #0077aa;">&nbsp;<?php echo $vacation;?></td>
										<td style="text-align:center;border-left:1px solid #0077aa;"><?php echo $row['TypeTravail'];?></td>
										<td style="text-align:center;border-left:1px solid #0077aa;"><?php echo $row['Reference'];?></td>
										<td style="text-align:center;border-left:1px solid #0077aa;"><?php echo stripslashes($row['CT']);?></td>
										<td style="text-align:center;border-left:1px solid #0077aa;"><?php echo str_replace("\\","",stripslashes($row['Titre']));?></td>
										<td style="text-align:center;border-left:1px solid #0077aa;"><?php echo stripslashes($row['Poste']);?></td>
										<td style="text-align:center;border-left:1px solid #0077aa;"><?php echo AfficheDateJJ_MM_AAAA($row['DateIntervention']);?></td>
										<td style="text-align:center;border-left:1px solid #0077aa;"><?php echo $compagnons;?></td>
										<td style="text-align:center;border-left:1px solid #0077aa;"><?php echo $row['TempsPasse'];?></td>
										<td <?php echo $HoverPROD; ?> style="text-align:center;border-left:1px solid #0077aa;"><?php echo $row['StatutPROD'].$infoBullePROD; ?></td>
										<td style="text-align:center;border-left:1px solid #0077aa;"><?php echo AfficheDateJJ_MM_AAAA($row['DateTERA']);?></td>
										<td <?php echo $HoverPROD; ?> style="text-align:center;border-left:1px solid #0077aa;"><?php echo $row['RetourPROD'].$infoBullePROD; ?></td>
										<td <?php echo $HoverQUALITE;?> style="text-align:center;border-left:1px solid #0077aa;"><?php echo $row['StatutQUALITE'].$infoBulleQUALITE;?></td>
										<td style="text-align:center;border-left:1px solid #0077aa;"><?php echo AfficheDateJJ_MM_AAAA($row['DateTERC']);?></td>
										<td <?php echo $HoverQUALITE;?> style="text-align:center;border-left:1px solid #0077aa;"><?php echo $row['RetourQUALITE'].$infoBulleQUALITE;?></td>
										<td align="center">
											<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>,<?php echo $_SESSION['Id_PersonneSP'];?>,<?php echo $row['Id_Dossier'];?>)">
											<img src='../../../Images/Modif.gif' border='0' alt='Modifier' title='Modifier'>
											</a>
										</td>
										<td width="2%" align="center">
											<a href="javascript:OuvreFenetreDupliquer(<?php echo $row['Id']; ?>,<?php echo $_SESSION['Id_PersonneSP'];?>)">
												<img src='../../../Images/copier.gif' border='0' alt='Dupliquer' title='Dupliquer'>
											</a>
										</td>
										<td width="2%" align="center">
											<?php
											if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1'){
												//Uniquement sur le dernier élément créé
												if($nbResultaFI>0){
													if($rowFI['Id']==$row['Id']){
											?>
												<a href="javascript:OuvreFenetreSuppr(<?php echo $row['Id']; ?>,<?php echo $_SESSION['Id_PersonneSP'];?>,<?php echo $row['Id_Dossier'];?>)">
												<img src='../../../Images/Suppression.gif' border='0' alt='Supprimer' title='Supprimer'>
												</a>
											<?php
													}
												}
											}
											?>
										</td>
									</tr>
								<?php
								if($couleur=="#ffffff"){$couleur="#E1E1D7";}
								else{$couleur="#ffffff";}
							}
						}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				$nbPage=0;
				if($_SESSION['Page']>1){echo "<b> <a style='color:#00599f;' href='Liste_Dossier.php?Page=0'><<</a> </b>";}
				$valeurDepart=1;
				if($_SESSION['Page']<=5){
					$valeurDepart=1;
				}
				elseif($_SESSION['Page']>=($nombreDePages-6)){
					$valeurDepart=$nombreDePages-6;
				}
				else{
					$valeurDepart=$_SESSION['Page']-5;
				}
				for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
					if($i<=$nombreDePages){
						if($i==($_SESSION['Page']+1)){
							echo "<b> [ ".$i." ] </b>"; 
						}	
						else{
							echo "<b> <a style='color:#00599f;' href='Liste_Dossier.php?Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($_SESSION['Page']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_Dossier.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
</form>
</table>

<?php
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
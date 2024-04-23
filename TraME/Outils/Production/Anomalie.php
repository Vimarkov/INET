<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout_Critere(){
			var w=window.open("Ajout_CritereAnomalie.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=800,height=250");
			w.focus();
		}
		function Suppr_Critere(critere,valeur){
			var w=window.open("Ajout_CritereAnomalie.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=200");
			w.focus();
		}
		function OuvreFenetreAjout(){
			var w=window.open("Ajout_Anomalie.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=960,height=400");
			w.focus();
			}
		function OuvreFenetreModif(Id){
			var w=window.open("Ajout_Anomalie.php?Mode=M&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=960,height=400");
			w.focus();
			}
		function OuvreFenetreSuppr(Id){
			if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
			var w=window.open("Ajout_Anomalie.php?Mode=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,,width=60,height=40");
			w.focus();
			}
		}
		function Excel(){
			var w=window.open("Extract_Anomalie.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
		}
	</script>
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>	
	<script type="text/javascript">
		$(function(){
			$(window).scroll(
				function () {//Au scroll dans la fenetre on d?clenche la fonction
					if ($(this).scrollTop() > 1) { //si on a d?fil? de plus de 150px du haut vers le bas
						$('#navigation').addClass("fixNavigation"); //on ajoute la classe "fixNavigation" ? <div id="navigation">
					} else {
						$('#navigation').removeClass("fixNavigation");//sinon on retire la classe "fixNavigation" ? <div id="navigation">
					}
				}
			);			 
		});
	</script>
</head>
<?php
require("../../Menu.php");
require("../Fonctions.php");

$_SESSION['Formulaire']="Production/Anomalie.php";
if($_POST){
	if(isset($_POST['Recherche_RAZ'])){
		$_SESSION['ANOM_Reference']="";
		$_SESSION['ANOM_DateDebut']="";
		$_SESSION['ANOM_DateFin']="";
		$_SESSION['ANOM_WP']="";
		$_SESSION['ANOM_Probleme']="";
		$_SESSION['ANOM_Origine']="";
		$_SESSION['ANOM_Responsable']="";
		$_SESSION['ANOM_Createur']="";
		$_SESSION['ANOM_FamilleErreur']="";
		
		$_SESSION['ANOM_Reference2']="";
		$_SESSION['ANOM_DateDebut2']="";
		$_SESSION['ANOM_DateFin2']="";
		$_SESSION['ANOM_WP2']="";
		$_SESSION['ANOM_Probleme2']="";
		$_SESSION['ANOM_Origine2']="";
		$_SESSION['ANOM_Responsable2']="";
		$_SESSION['ANOM_Createur2']="";
		$_SESSION['ANOM_FamilleErreur2']="";

		$_SESSION['ANOM_ModeFiltre']="oui";
		$_SESSION['ANOM_Page']="0";
	}
	elseif(isset($_POST['BtnRechercher'])){
		$_SESSION['ANOM_ModeFiltre']="oui";
	}
	elseif(isset($_POST['Tri_RAZ'])){
		$_SESSION['TriANOM_Reference']="";
		$_SESSION['TriANOM_Date']="";
		$_SESSION['TriANOM_WP']="";
		$_SESSION['TriANOM_Probleme']="";
		$_SESSION['TriANOM_Origine']="";
		$_SESSION['TriANOM_Responsable']="";
		$_SESSION['TriANOM_Createur']="";
		$_SESSION['TriANOM_FamilleErreur1']="";
		$_SESSION['TriANOM_FamilleErreur2']="";
		$_SESSION['TriANOM_DatePrevisionnelle']="";
		$_SESSION['TriANOM_DateReport']="";
		$_SESSION['TriANOM_DateCloture']="";
		$_SESSION['TriANOM_General']="";
	}
}

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Anomalie.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "ANOMALIES";}else{echo "ANOMALIES";} ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td><b>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Search criteria :";}else{echo "Critères de recherche :";}?></b></td>
			<td align="right">
			<a style="text-decoration:none;" href="javascript:OuvreFenetreAjout_Critere()">&nbsp;<img src="../../Images/Plus2.png" border="0" alt="<?php if($_SESSION['Langue']=="EN"){echo "Add criteria";}else{echo "Ajouter critères";}?>" title="<?php if($_SESSION['Langue']=="EN"){echo "Add criteria";}else{echo "Ajouter critères";}?>">&nbsp;&nbsp;</a>
			</td>
		</tr>
		<?php
			if($_SESSION['ANOM_Reference']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; References : ".$_SESSION['ANOM_Reference']."</td>";
				}
				else{
					echo "<td>&nbsp; Références : ".$_SESSION['ANOM_Reference']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['ANOM_WP']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Workpackages : ".$_SESSION['ANOM_WP']."</td>";
				}
				else{
					echo "<td>&nbsp; Workpackages : ".$_SESSION['ANOM_WP']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['ANOM_Probleme']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Problems : ".$_SESSION['ANOM_Probleme']."</td>";
				}
				else{
					echo "<td>&nbsp; Problèmes : ".$_SESSION['ANOM_Probleme']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['ANOM_Origine']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Origins : ".$_SESSION['ANOM_Origine']."</td>";
				}
				else{
					echo "<td>&nbsp; Origines : ".$_SESSION['ANOM_Origine']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['ANOM_Responsable']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Responsibles : ".$_SESSION['ANOM_Responsable']."</td>";
				}
				else{
					echo "<td>&nbsp; Responsables : ".$_SESSION['ANOM_Responsable']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['ANOM_Createur']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Creators : ".$_SESSION['ANOM_Createur']."</td>";
				}
				else{
					echo "<td>&nbsp; Créateurs : ".$_SESSION['ANOM_Createur']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['ANOM_FamilleErreur']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Error Families : ".$_SESSION['ANOM_FamilleErreur']."</td>";
				}
				else{
					echo "<td>&nbsp; Familles erreurs : ".$_SESSION['ANOM_FamilleErreur']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['ANOM_DateDebut']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Start date : ".$_SESSION['ANOM_DateDebut']."</td>";
				}
				else{
					echo "<td>&nbsp; Date de début : ".$_SESSION['ANOM_DateDebut']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['ANOM_DateFin']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; End date : ".$_SESSION['ANOM_DateFin']."</td>";
				}
				else{
					echo "<td>&nbsp; Date de fin : ".$_SESSION['ANOM_DateFin']."</td>";
				}
				echo "</tr>";
			}
		?>
		<tr>
			
			<td align="center" colspan="6"><input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Search";}else{echo "Rechercher";}?>">&nbsp;&nbsp;&nbsp;&nbsp;
			<input class="Bouton" name="Recherche_RAZ" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Clear search criteria";}else{echo "Vider les critères de recherche";}?>"> &nbsp;&nbsp;&nbsp;&nbsp;
			<input class="Bouton" name="Tri_RAZ" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Delete sorts";}else{echo "Effacer les tris";}?>"> &nbsp;&nbsp;&nbsp;&nbsp;
			<a style="text-decoration:none;" class="Bouton" href="javascript:Excel()">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Excel";}else{echo "Extract Excel";}?>&nbsp;</a>
			</td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="left">
			<table align="left" style="width:100%;">
				<tr>
				<td align="center">
				<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add an anomaly";}else{echo "Ajouter une anomalie";}?>&nbsp;</a>
				</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<?php
			if(isset($_GET['Tri'])){
				if($_GET['Tri']=="DateAnomalie"){
					$_SESSION['TriANOM_General']= str_replace("DateAnomalie ASC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("DateAnomalie DESC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("DateAnomalie ASC","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("DateAnomalie DESC","",$_SESSION['TriANOM_General']);
					if($_SESSION['TriANOM_Date']==""){$_SESSION['TriANOM_Date']="ASC";$_SESSION['TriANOM_General'].= "DateAnomalie ".$_SESSION['TriANOM_Date'].",";}
					elseif($_SESSION['TriANOM_Date']=="ASC"){$_SESSION['TriANOM_Date']="DESC";$_SESSION['TriANOM_General'].= "DateAnomalie ".$_SESSION['TriANOM_Date'].",";}
					else{$_SESSION['TriANOM_Date']="";}
				}
				if($_GET['Tri']=="Reference"){
					$_SESSION['TriANOM_General']= str_replace("Reference ASC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("Reference DESC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("Reference ASC","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("Reference DESC","",$_SESSION['TriANOM_General']);
					if($_SESSION['TriANOM_Reference']==""){$_SESSION['TriANOM_Reference']="ASC";$_SESSION['TriANOM_General'].= "Reference ".$_SESSION['TriANOM_Reference'].",";}
					elseif($_SESSION['TriANOM_Reference']=="ASC"){$_SESSION['TriANOM_Reference']="DESC";$_SESSION['TriANOM_General'].= "Reference ".$_SESSION['TriANOM_Reference'].",";}
					else{$_SESSION['TriANOM_Reference']="";}
				}
				if($_GET['Tri']=="WP"){
					$_SESSION['TriANOM_General']= str_replace("WP ASC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("WP DESC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("WP ASC","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("WP DESC","",$_SESSION['TriANOM_General']);
					if($_SESSION['TriANOM_WP']==""){$_SESSION['TriANOM_WP']="ASC";$_SESSION['TriANOM_General'].= "WP ".$_SESSION['TriANOM_WP'].",";}
					elseif($_SESSION['TriANOM_WP']=="ASC"){$_SESSION['TriANOM_WP']="DESC";$_SESSION['TriANOM_General'].= "WP ".$_SESSION['TriANOM_WP'].",";}
					else{$_SESSION['TriANOM_WP']="";}
				}
				if($_GET['Tri']=="Probleme"){
					$_SESSION['TriANOM_General']= str_replace("Probleme ASC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("Probleme DESC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("Probleme ASC","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("Probleme DESC","",$_SESSION['TriANOM_General']);
					if($_SESSION['TriANOM_Probleme']==""){$_SESSION['TriANOM_Probleme']="ASC";$_SESSION['TriANOM_General'].= "Probleme ".$_SESSION['TriANOM_Probleme'].",";}
					elseif($_SESSION['TriANOM_Probleme']=="ASC"){$_SESSION['TriANOM_Probleme']="DESC";$_SESSION['TriANOM_General'].= "Probleme ".$_SESSION['TriANOM_Probleme'].",";}
					else{$_SESSION['TriANOM_Probleme']="";}
				}
				if($_GET['Tri']=="Origine"){
					$_SESSION['TriANOM_General']= str_replace("Origine ASC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("Origine DESC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("Origine ASC","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("Origine DESC","",$_SESSION['TriANOM_General']);
					if($_SESSION['TriANOM_Origine']==""){$_SESSION['TriANOM_Origine']="ASC";$_SESSION['TriANOM_General'].= "Origine ".$_SESSION['TriANOM_Origine'].",";}
					elseif($_SESSION['TriANOM_Origine']=="ASC"){$_SESSION['TriANOM_Origine']="DESC";$_SESSION['TriANOM_General'].= "Origine ".$_SESSION['TriANOM_Origine'].",";}
					else{$_SESSION['TriANOM_Origine']="";}
				}
				if($_GET['Tri']=="Responsable"){
					$_SESSION['TriANOM_General']= str_replace("Responsable ASC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("Responsable DESC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("Responsable ASC","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("Responsable DESC","",$_SESSION['TriANOM_General']);
					if($_SESSION['TriANOM_Responsable']==""){$_SESSION['TriANOM_Responsable']="ASC";$_SESSION['TriANOM_General'].= "Responsable ".$_SESSION['TriANOM_Responsable'].",";}
					elseif($_SESSION['TriANOM_Responsable']=="ASC"){$_SESSION['TriANOM_Responsable']="DESC";$_SESSION['TriANOM_General'].= "Responsable ".$_SESSION['TriANOM_Responsable'].",";}
					else{$_SESSION['TriANOM_Responsable']="";}
				}
				if($_GET['Tri']=="Createur"){
					$_SESSION['TriANOM_General']= str_replace("Createur ASC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("Createur DESC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("Createur ASC","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("Createur DESC","",$_SESSION['TriANOM_General']);
					if($_SESSION['TriANOM_Createur']==""){$_SESSION['TriANOM_Createur']="ASC";$_SESSION['TriANOM_General'].= "Createur ".$_SESSION['TriANOM_Createur'].",";}
					elseif($_SESSION['TriANOM_Createur']=="ASC"){$_SESSION['TriANOM_Createur']="DESC";$_SESSION['TriANOM_General'].= "Createur ".$_SESSION['TriANOM_Createur'].",";}
					else{$_SESSION['TriANOM_Createur']="";}
				}
				if($_GET['Tri']=="FamilleErreur1"){
					$_SESSION['TriANOM_General']= str_replace("FamilleErreur1 ASC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("FamilleErreur1 DESC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("FamilleErreur1 ASC","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("FamilleErreur1 DESC","",$_SESSION['TriANOM_General']);
					if($_SESSION['TriANOM_FamilleErreur1']==""){$_SESSION['TriANOM_FamilleErreur1']="ASC";$_SESSION['TriANOM_General'].= "FamilleErreur1 ".$_SESSION['TriANOM_FamilleErreur1'].",";}
					elseif($_SESSION['TriANOM_FamilleErreur1']=="ASC"){$_SESSION['TriANOM_FamilleErreur1']="DESC";$_SESSION['TriANOM_General'].= "FamilleErreur1 ".$_SESSION['TriANOM_FamilleErreur1'].",";}
					else{$_SESSION['TriANOM_FamilleErreur1']="";}
				}
				if($_GET['Tri']=="FamilleErreur2"){
					$_SESSION['TriANOM_General']= str_replace("FamilleErreur2 ASC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("FamilleErreur2 DESC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("FamilleErreur2 ASC","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("FamilleErreur2 DESC","",$_SESSION['TriANOM_General']);
					if($_SESSION['TriANOM_FamilleErreur2']==""){$_SESSION['TriANOM_FamilleErreur2']="ASC";$_SESSION['TriANOM_General'].= "FamilleErreur2 ".$_SESSION['TriANOM_FamilleErreur2'].",";}
					elseif($_SESSION['TriANOM_FamilleErreur2']=="ASC"){$_SESSION['TriANOM_FamilleErreur2']="DESC";$_SESSION['TriANOM_General'].= "FamilleErreur2 ".$_SESSION['TriANOM_FamilleErreur2'].",";}
					else{$_SESSION['TriANOM_FamilleErreur2']="";}
				}
				if($_GET['Tri']=="DatePrevisionnelle"){
					$_SESSION['TriANOM_General']= str_replace("DatePrevisionnelle ASC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("DatePrevisionnelle DESC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("DatePrevisionnelle ASC","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("DatePrevisionnelle DESC","",$_SESSION['TriANOM_General']);
					if($_SESSION['TriANOM_DatePrevisionnelle']==""){$_SESSION['TriANOM_DatePrevisionnelle']="ASC";$_SESSION['TriANOM_General'].= "DatePrevisionnelle ".$_SESSION['TriANOM_DatePrevisionnelle'].",";}
					elseif($_SESSION['TriANOM_DatePrevisionnelle']=="ASC"){$_SESSION['TriANOM_DatePrevisionnelle']="DESC";$_SESSION['TriANOM_General'].= "DatePrevisionnelle ".$_SESSION['TriANOM_DatePrevisionnelle'].",";}
					else{$_SESSION['TriANOM_DatePrevisionnelle']="";}
				}
				if($_GET['Tri']=="DateReport"){
					$_SESSION['TriANOM_General']= str_replace("DateReport ASC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("DateReport DESC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("DateReport ASC","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("DateReport DESC","",$_SESSION['TriANOM_General']);
					if($_SESSION['TriANOM_DateReport']==""){$_SESSION['TriANOM_DateReport']="ASC";$_SESSION['TriANOM_General'].= "DateReport ".$_SESSION['TriANOM_DateReport'].",";}
					elseif($_SESSION['TriANOM_DateReport']=="ASC"){$_SESSION['TriANOM_DateReport']="DESC";$_SESSION['TriANOM_General'].= "DateReport ".$_SESSION['TriANOM_DateReport'].",";}
					else{$_SESSION['TriANOM_DateReport']="";}
				}
				if($_GET['Tri']=="DateCloture"){
					$_SESSION['TriANOM_General']= str_replace("DateCloture ASC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("DateCloture DESC,","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("DateCloture ASC","",$_SESSION['TriANOM_General']);
					$_SESSION['TriANOM_General']= str_replace("DateCloture DESC","",$_SESSION['TriANOM_General']);
					if($_SESSION['TriANOM_DateCloture']==""){$_SESSION['TriANOM_DateCloture']="ASC";$_SESSION['TriANOM_General'].= "DateCloture ".$_SESSION['TriANOM_DateCloture'].",";}
					elseif($_SESSION['TriANOM_DateCloture']=="ASC"){$_SESSION['TriANOM_DateCloture']="DESC";$_SESSION['TriANOM_General'].= "DateCloture ".$_SESSION['TriANOM_DateCloture'].",";}
					else{$_SESSION['TriANOM_DateCloture']="";}
				}
			}
			if($_SESSION['ANOM_ModeFiltre']=="oui"){
				$reqAnalyse="SELECT trame_anomalie.Id ";
				$req2="SELECT Id,Reference,DateAnomalie,DatePrevisionnelle,DateReport,DateCloture,Probleme,ActionCurative,AnalyseCause,ActionPreventive,Observation, ";
				$req2.="(SELECT Libelle FROM trame_origine WHERE trame_origine.Id=trame_anomalie.Id_Origine) AS Origine, ";
				$req2.="(SELECT Libelle FROM trame_ponderation WHERE trame_ponderation.Id=trame_anomalie.Id_Ponderation) AS Ponderation, ";
				$req2.="(SELECT Libelle FROM trame_familleerreur WHERE trame_familleerreur.Id=trame_anomalie.Id_FamilleErreur1) AS FamilleErreur1, ";
				$req2.="(SELECT Libelle FROM trame_familleerreur WHERE trame_familleerreur.Id=trame_anomalie.Id_FamilleErreur2) AS FamilleErreur2, ";
				$req2.="(SELECT Libelle FROM trame_responsable WHERE trame_responsable.Id=trame_anomalie.Id_Responsable) AS Responsable, ";
				$req2.="(SELECT Libelle FROM trame_wp WHERE trame_wp.Id=trame_anomalie.Id_WP) AS WP, ";
				$req2.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=trame_anomalie.Id_Createur) AS Createur ";
				$req="FROM trame_anomalie WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND ";
				if($_SESSION['ANOM_Reference2']<>""){
					$tab = explode(";",$_SESSION['ANOM_Reference2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Reference='".$valeur."' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['ANOM_WP2']<>""){
					$tab = explode(";",$_SESSION['ANOM_WP2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Id_WP=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['ANOM_Probleme2']<>""){
					$tab = explode(";",$_SESSION['ANOM_Probleme2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Probleme LIKE '%".$valeur."%' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['ANOM_Origine2']<>""){
					$tab = explode(";",$_SESSION['ANOM_Origine2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Id_Origine=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['ANOM_Responsable2']<>""){
					$tab = explode(";",$_SESSION['ANOM_Responsable2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Id_Responsable=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['ANOM_Createur2']<>""){
					$tab = explode(";",$_SESSION['ANOM_Createur2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Id_Createur=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['ANOM_DateDebut2']<>"" || $_SESSION['ANOM_DateFin2']<>""){
					$req.=" ( ";
					if($_SESSION['ANOM_DateDebut2']<>""){
						$req.="DateAnomalie>= '". TrsfDate_($_SESSION['ANOM_DateDebut2'])."' ";
						$req.=" AND ";
					}
					if($_SESSION['ANOM_DateFin2']<>""){
						$req.="DateAnomalie <= '". TrsfDate_($_SESSION['ANOM_DateFin2'])."' ";
						$req.=" ";
					}
					if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
					$req.=" ) ";
				}
				if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
				if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}
				
				$result=mysqli_query($bdd,$reqAnalyse.$req);
				$nbResulta=mysqli_num_rows($result);
				
				if($_SESSION['TriANOM_General']<>""){
					$req.="ORDER BY ".substr($_SESSION['TriANOM_General'],0,-1);
				}

				$nombreDePages=ceil($nbResulta/50);
				if(isset($_GET['Page'])){$_SESSION['ANOM_Page']=$_GET['Page'];}
				else{$_SESSION['ANOM_Page']=0;}
				$req3=" LIMIT ".($_SESSION['ANOM_Page']*50).",50";
				$result=mysqli_query($bdd,$req2.$req.$req3);
				$nbResulta=mysqli_num_rows($result);
			}
		?>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				if($_SESSION['ANOM_ModeFiltre']=="oui"){
					$nbPage=0;
					if($_SESSION['ANOM_Page']>1){echo "<b> <a style='color:#00599f;' href='Anomalie.php?Page=0'><<</a> </b>";}
					$valeurDepart=1;
					if($_SESSION['ANOM_Page']<=5){
						$valeurDepart=1;
					}
					elseif($_SESSION['ANOM_Page']>=($nombreDePages-6)){
						$valeurDepart=$nombreDePages-6;
					}
					else{
						$valeurDepart=$_SESSION['ANOM_Page']-5;
					}
					for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
						if($i<=$nombreDePages){
							if($i==($_SESSION['ANOM_Page']+1)){
								echo "<b> [ ".$i." ] </b>"; 
							}	
							else{
								echo "<b> <a style='color:#00599f;' href='Anomalie.php?Page=".($i-1)."'>".$i."</a> </b>";
							}
						}
					}
					if($_SESSION['ANOM_Page']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Anomalie.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
				}
			?>
		</td>
	</tr>
		<table cellpadding="0" cellspacing="0" align="left" class="GeneralInfo" style="width:100%;">
			<tr bgcolor="#00325F">
				<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Anomalie.php?Tri=DateAnomalie"><?php if($_SESSION['Langue']=="EN"){echo "Date";}else{echo "Date";} ?><?php if($_SESSION['TriANOM_Date']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriANOM_Date']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Anomalie.php?Tri=Reference"><?php if($_SESSION['Langue']=="EN"){echo "Reference";}else{echo "Référence";} ?><?php if($_SESSION['TriANOM_Reference']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriANOM_Reference']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="14%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Anomalie.php?Tri=WP"><?php if($_SESSION['Langue']=="EN"){echo "Workpackage";}else{echo "Workpackage";} ?><?php if($_SESSION['TriANOM_WP']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriANOM_WP']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="15%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Anomalie.php?Tri=Probleme"><?php if($_SESSION['Langue']=="EN"){echo "Problem";}else{echo "Problème";} ?><?php if($_SESSION['TriANOM_Probleme']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriANOM_Probleme']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Anomalie.php?Tri=Origine"><?php if($_SESSION['Langue']=="EN"){echo "Origin";}else{echo "Origine";} ?><?php if($_SESSION['TriANOM_Origine']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriANOM_Origine']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Anomalie.php?Tri=Responsable"><?php if($_SESSION['Langue']=="EN"){echo "Responsible";}else{echo "Responsable";} ?><?php if($_SESSION['TriANOM_Responsable']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriANOM_Responsable']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Anomalie.php?Tri=FamilleErreur1"><?php if($_SESSION['Langue']=="EN"){echo "Error family 1";}else{echo "Famille erreur 1";} ?><?php if($_SESSION['TriANOM_FamilleErreur1']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriANOM_FamilleErreur1']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Anomalie.php?Tri=FamilleErreur2"><?php if($_SESSION['Langue']=="EN"){echo "Error family 2";}else{echo "Famille erreur 2";} ?><?php if($_SESSION['TriANOM_FamilleErreur2']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriANOM_FamilleErreur2']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Anomalie.php?Tri=DatePrevisionnelle"><?php if($_SESSION['Langue']=="EN"){echo "Expected date";}else{echo "Date prévisionnelle";} ?><?php if($_SESSION['TriANOM_DatePrevisionnelle']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriANOM_DatePrevisionnelle']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Anomalie.php?Tri=DateReport"><?php if($_SESSION['Langue']=="EN"){echo "Date of reporting";}else{echo "Date report";} ?><?php if($_SESSION['TriANOM_DateReport']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriANOM_DateReport']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Anomalie.php?Tri=DateCloture"><?php if($_SESSION['Langue']=="EN"){echo "Closing Date";}else{echo "Date clôture";} ?><?php if($_SESSION['TriANOM_DateCloture']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriANOM_DateCloture']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
			</tr>
			<?php
				if($_SESSION['ANOM_ModeFiltre']=="oui"){
					if ($nbResulta>0){
						$couleur="#ffffff";
						while($row=mysqli_fetch_array($result)){
							if($couleur=="#ffffff"){$couleur="#E1E1D7";}
							else{$couleur="#ffffff";}
							?>
								<tr bgcolor="<?php echo $couleur;?>">
									<td width="8%">&nbsp;<?php echo AfficheDateFR($row['DateAnomalie']);?></td>
									<td width="10%"><?php echo $row['Reference'];?></td>
									<td width="14%"><?php echo stripslashes(str_replace("\\","",$row['WP']));?></td>
									<td width="15%"><?php echo stripslashes(str_replace("\\","",$row['Probleme'])); ?></td>
									<td width="10%"><?php echo $row['Origine']; ?></td>
									<td width="10%"><?php echo $row['Responsable']; ?></td>
									<td width="10%"><?php echo stripslashes(str_replace("\\","",$row['FamilleErreur1'])); ?></td>
									<td width="10%"><?php echo stripslashes(str_replace("\\","",$row['FamilleErreur2'])); ?></td>
									<td width="8%">&nbsp;<?php echo AfficheDateFR($row['DatePrevisionnelle']);?></td>
									<td width="8%">&nbsp;<?php echo AfficheDateFR($row['DateReport']);?></td>
									<td width="8%">&nbsp;<?php echo AfficheDateFR($row['DateCloture']);?></td>
									<td width="2%" align="center">
										<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>)">
											<img src='../../Images/Modif.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Modify";}else{echo "Modifier";} ?>'>
										</a>
									</td>
									<td width="2%" align="center">
										<a href="javascript:OuvreFenetreSuppr(<?php echo $row['Id']; ?>)">
											<img src='../../Images/Suppression.gif' border='0' alt='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>' title='<?php if($_SESSION['Langue']=="EN"){echo "Suppress";}else{echo "Supprimer";} ?>'>
										</a>
									</td>
								</tr>
							<?php
						}
					}
				}
			?>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
</form>
</table>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>

</body>
</html>
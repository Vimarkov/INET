<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout_Critere(){
			var w=window.open("Ajout_Critere.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=900,height=640");
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
		function Excel(){
			var w=window.open("ExtractDossier.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
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

//Verifier si Google CHROME (true) ou Autre (fale)
if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
else {$NavigOk = false;}

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));

if($_POST){
	if(isset($_POST['Recherche_RAZ'])){
		$_SESSION['MSN']="";
		$_SESSION['NumDossier']="";
		$_SESSION['Section']="";
		$_SESSION['Zone']="";
		$_SESSION['Priorite']="";
		$_SESSION['CreateurDossier']="";
		$_SESSION['Client']="";
		$_SESSION['TravailRealise']="";
		$_SESSION['Titre']="";
		$_SESSION['Vacation']="";
		$_SESSION['CE']="";
		$_SESSION['DateDebut']="";
		$_SESSION['DateFin']="";
		$_SESSION['SansDate']="";
		$_SESSION['VacationQUALITE']="";
		$_SESSION['IQ']="";
		$_SESSION['Stamp']="";
		$_SESSION['DateDebutQUALITE']="";
		$_SESSION['DateFinQUALITE']="";
		$_SESSION['SansDateQUALITE']="";
		
		$_SESSION['NumIC']="";
		$_SESSION['Poste']="";
		$_SESSION['CreateurIC']="";
		$_SESSION['StatutIC']="";
		
		$_SESSION['MSN2']="";
		$_SESSION['NumDossier2']="";
		$_SESSION['NumIC2']="";
		$_SESSION['Client2']="";
		$_SESSION['Zone2']="";
		$_SESSION['Priorite2']="";
		$_SESSION['Section2']="";
		$_SESSION['CreateurDossier2']="";
		$_SESSION['CreateurIC2']="";
		$_SESSION['CE2']="";
		$_SESSION['IQ2']="";
		$_SESSION['Titre2']="";
		$_SESSION['StatutIC2']="";
		$_SESSION['Vacation2']="";
		$_SESSION['TravailRealise2']="";
		$_SESSION['DateDebut2']="";
		$_SESSION['DateFin2']="";
		$_SESSION['SansDate2']="";
		$_SESSION['Poste2']="";
		$_SESSION['Stamp2']="";
		$_SESSION['DateDebutQUALITE2']="";
		$_SESSION['DateFinQUALITE2']="";
		$_SESSION['SansDateQUALITE2']="";
		$_SESSION['VacationQUALITE2']="";
	}
	elseif(isset($_POST['BtnRechercher'])){
		$_SESSION['ModeFiltre']="oui";
	}
	elseif(isset($_POST['Tri_RAZ'])){
		$_SESSION['TriMSN']="ASC";
		$_SESSION['TriOF']="ASC";
		$_SESSION['TriPriorite']="";
		$_SESSION['TriTAI']="";
		$_SESSION['TriTitre']="";
		$_SESSION['TriDateIntervention']="";
		$_SESSION['TriVacation']="";
		$_SESSION['TriNumFI']="";
		$_SESSION['TriClient']="";
		$_SESSION['TriZone']="";
		$_SESSION['TriTravailRealise']="";
		$_SESSION['TriPoste']="";
		$_SESSION['TriStatutProd']="";
		$_SESSION['TriStatutQualite']="";
		$_SESSION['TriRetourPROD']="";
		$_SESSION['TriRetourQUALITE']="";
		$_SESSION['TriGeneral']="";
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
			<td><b>&nbsp; Critères de recherche : </b></td>
			<td align="right">
			<a style="text-decoration:none;" href="javascript:OuvreFenetreAjout_Critere()">&nbsp;<img src="../../../Images/Plus2.png" border="0" alt="Ajouter critères" title="Ajouter critères">&nbsp;&nbsp;</a>
			</td>
		</tr>
		<?php
			if($_SESSION['MSN']<>""){
				echo "<tr>";
				echo "<td>&nbsp; MSN : ".$_SESSION['MSN']."</td>";
				echo "</tr>";
			}
			if($_SESSION['NumDossier']<>""){
				echo "<tr>";
				echo "<td>&nbsp; N° OF/OT/Para : ".$_SESSION['NumDossier']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Section']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Sections : ".$_SESSION['Section']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Zone']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Localisations : ".$_SESSION['Zone']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Priorite']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Priorités : ".$_SESSION['Priorite']."</td>";
				echo "</tr>";
			}
			if($_SESSION['CreateurDossier']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Créateurs dossiers : ".$_SESSION['CreateurDossier']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Client']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Clients : ".$_SESSION['Client']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Titre']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Titre : ".$_SESSION['Titre']."</td>";
				echo "</tr>";
			}
			if($_SESSION['TravailRealise']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Sujet du travail réalisé : ".$_SESSION['TravailRealise']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Vacation']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Vacations PROD : ".$_SESSION['Vacation']."</td>";
				echo "</tr>";
			}
			if($_SESSION['CE']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Chefs d'équipes : ".$_SESSION['CE']."</td>";
				echo "</tr>";
			}
			if($_SESSION['DateDebut']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Date de début PROD : ".$_SESSION['DateDebut']."</td>";
				echo "</tr>";
			}
			if($_SESSION['DateFin']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Date de fin PROD : ".$_SESSION['DateFin']."</td>";
				echo "</tr>";
			}
			if($_SESSION['SansDate']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Sans date PROD : ".$_SESSION['SansDate']."</td>";
				echo "</tr>";
			}
			if($_SESSION['VacationQUALITE']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Vacations QUALITE : ".$_SESSION['VacationQUALITE']."</td>";
				echo "</tr>";
			}
			if($_SESSION['IQ']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Inspecteurs qualité : ".$_SESSION['IQ']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Stamp']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Marque de contrôle : ".$_SESSION['Stamp']."</td>";
				echo "</tr>";
			}
			if($_SESSION['DateDebutQUALITE']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Date de début QUALITE : ".$_SESSION['DateDebutQUALITE']."</td>";
				echo "</tr>";
			}
			if($_SESSION['DateFinQUALITE']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Date de fin QUALITE : ".$_SESSION['DateFinQUALITE']."</td>";
				echo "</tr>";
			}
			if($_SESSION['SansDateQUALITE']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Sans date QUALITE : ".$_SESSION['SansDateQUALITE']."</td>";
				echo "</tr>";
			}
			if($_SESSION['NumIC']<>""){
				echo "<tr>";
				echo "<td>&nbsp; N° IC : ".$_SESSION['NumIC']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Poste']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Postes : ".$_SESSION['Poste']."</td>";
				echo "</tr>";
			}
			if($_SESSION['CreateurIC']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Créateurs IC : ".$_SESSION['CreateurIC']."</td>";
				echo "</tr>";
			}
			if($_SESSION['StatutIC']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Statuts IC : ".$_SESSION['StatutIC']."</td>";
				echo "</tr>";
			}
			
		?>
		<tr>
			
			<td align="center" colspan="6"><input class="Bouton" name="BtnRechercher" size="10" type="submit" value="Rechercher">&nbsp;&nbsp;&nbsp;&nbsp;
			<input class="Bouton" name="Recherche_RAZ" type="submit" value="Vider les critères de recherche"> &nbsp;&nbsp;&nbsp;&nbsp;
			<input class="Bouton" name="Tri_RAZ" type="submit" value="Effacer les tris"> &nbsp;&nbsp;&nbsp;&nbsp;
			<a style="text-decoration:none;" class="Bouton" href="javascript:Excel()">&nbsp;Extract Excel&nbsp;</a>
			</td>
		</tr>
		
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<?php
			if(isset($_GET['Tri'])){
				if($_GET['Tri']=="MSN"){
					$_SESSION['TriGeneral']= str_replace("MSN ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("MSN DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("MSN ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("MSN DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriMSN']==""){$_SESSION['TriMSN']="ASC";$_SESSION['TriGeneral'].= "MSN ".$_SESSION['TriMSN'].",";}
					elseif($_SESSION['TriMSN']=="ASC"){$_SESSION['TriMSN']="DESC";$_SESSION['TriGeneral'].= "MSN ".$_SESSION['TriMSN'].",";}
					else{$_SESSION['TriMSN']="";}
				}
				if($_GET['Tri']=="OF"){
					$_SESSION['TriGeneral']= str_replace("Reference ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Reference DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Reference ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Reference DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriOF']==""){$_SESSION['TriOF']="ASC";$_SESSION['TriGeneral'].= "Reference ".$_SESSION['TriOF'].",";}
					elseif($_SESSION['TriOF']=="ASC"){$_SESSION['TriOF']="DESC";$_SESSION['TriGeneral'].= "Reference ".$_SESSION['TriOF'].",";}
					else{$_SESSION['TriOF']="";}
				}
				if($_GET['Tri']=="Zone"){
					$_SESSION['TriGeneral']= str_replace("Zone ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Zone DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Zone ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Zone DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriZone']==""){$_SESSION['TriZone']="ASC";$_SESSION['TriGeneral'].= "Zone ".$_SESSION['TriZone'].",";}
					elseif($_SESSION['TriZone']=="ASC"){$_SESSION['TriZone']="DESC";$_SESSION['TriGeneral'].= "Zone ".$_SESSION['TriZone'].",";}
					else{$_SESSION['TriZone']="";}
				}
				if($_GET['Tri']=="Priorite"){
					$_SESSION['TriGeneral']= str_replace("Priorite ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Priorite DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Priorite ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Priorite DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriPriorite']==""){$_SESSION['TriPriorite']="ASC";$_SESSION['TriGeneral'].= "Priorite ".$_SESSION['TriPriorite'].",";}
					elseif($_SESSION['TriPriorite']=="ASC"){$_SESSION['TriPriorite']="DESC";$_SESSION['TriGeneral'].= "Priorite ".$_SESSION['TriPriorite'].",";}
					else{$_SESSION['TriPriorite']="";}
				}
				if($_GET['Tri']=="TAI"){
					$_SESSION['TriGeneral']= str_replace("TAI_RestantACP ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("TAI_RestantACP DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("TAI_RestantACP ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("TAI_RestantACP DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriTAI']==""){$_SESSION['TriTAI']="ASC";$_SESSION['TriGeneral'].= "TAI_RestantACP ".$_SESSION['TriTAI'].",";}
					elseif($_SESSION['TriTAI']=="ASC"){$_SESSION['TriTAI']="DESC";$_SESSION['TriGeneral'].= "TAI_RestantACP ".$_SESSION['TriTAI'].",";}
					else{$_SESSION['TriTAI']="";}
				}
				if($_GET['Tri']=="Titre"){
					$_SESSION['TriGeneral']= str_replace("Titre ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Titre DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Titre ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Titre DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriTitre']==""){$_SESSION['TriTitre']="ASC";$_SESSION['TriGeneral'].= "Titre ".$_SESSION['TriTitre'].",";}
					elseif($_SESSION['TriTitre']=="ASC"){$_SESSION['TriTitre']="DESC";$_SESSION['TriGeneral'].= "Titre ".$_SESSION['TriTitre'].",";}
					else{$_SESSION['TriTitre']="";}
				}
				if($_GET['Tri']=="DateIntervention"){
					$_SESSION['TriGeneral']= str_replace("DateIntervention ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("DateIntervention DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("DateIntervention ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("DateIntervention DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriDateIntervention']==""){$_SESSION['TriDateIntervention']="ASC";$_SESSION['TriGeneral'].= "DateIntervention ".$_SESSION['TriDateIntervention'].",";}
					elseif($_SESSION['TriDateIntervention']=="ASC"){$_SESSION['TriDateIntervention']="DESC";$_SESSION['TriGeneral'].= "DateIntervention ".$_SESSION['TriDateIntervention'].",";}
					else{$_SESSION['TriDateIntervention']="";}
				}
				if($_GET['Tri']=="Vacation"){
					$_SESSION['TriGeneral']= str_replace("Vacation ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Vacation DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Vacation ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Vacation DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriVacation']==""){$_SESSION['TriVacation']="ASC";$_SESSION['TriGeneral'].= "Vacation ".$_SESSION['TriVacation'].",";}
					elseif($_SESSION['TriVacation']=="ASC"){$_SESSION['TriVacation']="DESC";$_SESSION['TriGeneral'].= "Vacation ".$_SESSION['TriVacation'].",";}
					else{$_SESSION['TriVacation']="";}
				}
				if($_GET['Tri']=="NumFI"){
					$_SESSION['TriGeneral']= str_replace("NumFI ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("NumFI DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("NumFI ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("NumFI DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriNumFI']==""){$_SESSION['TriNumFI']="ASC";$_SESSION['TriGeneral'].= "NumFI ".$_SESSION['TriNumFI'].",";}
					elseif($_SESSION['TriNumFI']=="ASC"){$_SESSION['TriNumFI']="DESC";$_SESSION['TriGeneral'].= "NumFI ".$_SESSION['TriNumFI'].",";}
					else{$_SESSION['TriNumFI']="";}
				}
				if($_GET['Tri']=="TravailRealise"){
					$_SESSION['TriGeneral']= str_replace("TravailRealise ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("TravailRealise DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("TravailRealise ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("TravailRealise DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriTravailRealise']==""){$_SESSION['TriTravailRealise']="ASC";$_SESSION['TriGeneral'].= "TravailRealise ".$_SESSION['TriTravailRealise'].",";}
					elseif($_SESSION['TriTravailRealise']=="ASC"){$_SESSION['TriTravailRealise']="DESC";$_SESSION['TriGeneral'].= "TravailRealise ".$_SESSION['TriTravailRealise'].",";}
					else{$_SESSION['TriTravailRealise']="";}
				}
				if($_GET['Tri']=="Poste"){
					$_SESSION['TriGeneral']= str_replace("PosteAvionACP ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("PosteAvionACP DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("PosteAvionACP ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("PosteAvionACP DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriPoste']==""){$_SESSION['TriPoste']="ASC";$_SESSION['TriGeneral'].= "PosteAvionACP ".$_SESSION['TriPoste'].",";}
					elseif($_SESSION['TriPoste']=="ASC"){$_SESSION['TriPoste']="DESC";$_SESSION['TriGeneral'].= "PosteAvionACP ".$_SESSION['TriPoste'].",";}
					else{$_SESSION['TriPoste']="";}
				}
				if($_GET['Tri']=="StatutPROD"){
					$_SESSION['TriGeneral']= str_replace("Id_StatutPROD ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Id_StatutPROD DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Id_StatutPROD ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Id_StatutPROD DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriStatutProd']==""){$_SESSION['TriStatutProd']="ASC";$_SESSION['TriGeneral'].= "Id_StatutPROD ".$_SESSION['TriStatutProd'].",";}
					elseif($_SESSION['TriStatutProd']=="ASC"){$_SESSION['TriStatutProd']="DESC";$_SESSION['TriGeneral'].= "Id_StatutPROD ".$_SESSION['TriStatutProd'].",";}
					else{$_SESSION['TriStatutProd']="";}
				}
				if($_GET['Tri']=="StatutQUALITE"){
					$_SESSION['TriGeneral']= str_replace("Id_StatutQUALITE ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Id_StatutQUALITE DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Id_StatutQUALITE ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Id_StatutQUALITE DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriStatutQualite']==""){$_SESSION['TriStatutQualite']="ASC";$_SESSION['TriGeneral'].= "Id_StatutQUALITE ".$_SESSION['TriStatutQualite'].",";}
					elseif($_SESSION['TriStatutQualite']=="ASC"){$_SESSION['TriStatutQualite']="DESC";$_SESSION['TriGeneral'].= "Id_StatutQUALITE ".$_SESSION['TriStatutQualite'].",";}
					else{$_SESSION['TriStatutQualite']="";}
				}
				if($_GET['Tri']=="RetourPROD"){
					$_SESSION['TriGeneral']= str_replace("RetourPROD ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("RetourPROD DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("RetourPROD ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("RetourPROD DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriRetourPROD']==""){$_SESSION['TriRetourPROD']="ASC";$_SESSION['TriGeneral'].= "RetourPROD ".$_SESSION['TriRetourPROD'].",";}
					elseif($_SESSION['TriRetourPROD']=="ASC"){$_SESSION['TriRetourPROD']="DESC";$_SESSION['TriGeneral'].= "RetourPROD ".$_SESSION['TriRetourPROD'].",";}
					else{$_SESSION['TriRetourPROD']="";}
				}
				if($_GET['Tri']=="RetourQUALITE"){
					$_SESSION['TriGeneral']= str_replace("RetourQUALITE ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("RetourQUALITE DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("RetourQUALITE ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("RetourQUALITE DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriRetourQUALITE']==""){$_SESSION['TriRetourQUALITE']="ASC";$_SESSION['TriGeneral'].= "RetourQUALITE ".$_SESSION['TriRetourQUALITE'].",";}
					elseif($_SESSION['TriRetourQUALITE']=="ASC"){$_SESSION['TriRetourQUALITE']="DESC";$_SESSION['TriGeneral'].= "RetourQUALITE ".$_SESSION['TriRetourQUALITE'].",";}
					else{$_SESSION['TriRetourQUALITE']="";}
				}
			}
			if($_SESSION['ModeFiltre']=="oui"){
				$reqAnalyse="SELECT sp_olwficheintervention.Id ";
				$req2="SELECT sp_olwficheintervention.Id,sp_olwficheintervention.Id_Dossier,sp_olwdossier.MSN,sp_olwdossier.Reference,";
				$req2.="sp_olwdossier.Priorite,sp_olwdossier.TAI_RestantACP,sp_olwdossier.Titre,sp_olwficheintervention.NumFI,";
				$req2.="sp_olwficheintervention.Vacation,sp_olwficheintervention.DateIntervention,CommentairePROD,CommentaireQUALITE,";
				$req2.="(SELECT sp_olwzonedetravail.Libelle FROM sp_olwzonedetravail WHERE sp_olwzonedetravail.Id=sp_olwdossier.Id_ZoneDeTravail) AS Zone, ";
				$req2.="(SELECT sp_olwretour.Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourPROD) AS RetourPROD, ";
				$req2.="(SELECT sp_olwretour.Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourQUALITE) AS RetourQUALITE, ";
				$req2.="sp_olwficheintervention.PosteAvionACP,sp_olwficheintervention.TravailRealise,sp_olwficheintervention.Id_StatutPROD,sp_olwficheintervention.Id_StatutQUALITE ";
				$req="FROM sp_olwficheintervention LEFT JOIN sp_olwdossier ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id ";
				$req.="WHERE sp_olwdossier.Id_Prestation=842 AND ";
				if($_SESSION['MSN2']<>""){
					$tab = explode(";",$_SESSION['MSN2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_olwdossier.MSN=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['NumDossier2']<>""){
					$tab = explode(";",$_SESSION['NumDossier2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_olwdossier.Reference='".addslashes($valeur)."' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['Section2']<>""){
					$tab = explode(";",$_SESSION['Section2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_olwdossier.SectionACP='".addslashes($valeur)."' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['Zone2']<>""){
					$tab = explode(";",$_SESSION['Zone2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_olwdossier.Id_ZoneDeTravail=".substr($valeur,1)." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['Priorite2']<>""){
					$tab = explode(";",$_SESSION['Priorite2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_olwdossier.Priorite=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['CreateurDossier2']<>""){
					$tab = explode(";",$_SESSION['CreateurDossier2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_olwdossier.Id_Personne=".substr($valeur,1)." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['Client2']<>""){
					$tab = explode(";",$_SESSION['Client2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_olwdossier.Id_Client='".substr($valeur,1)."' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['Titre2']<>""){
					$tab = explode(";",$_SESSION['Titre2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_olwdossier.Titre LIKE '%".addslashes($valeur)."%' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['TravailRealise2']<>""){
					$tab = explode(";",$_SESSION['TravailRealise2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_olwficheintervention.TravailRealise LIKE '%".addslashes($valeur)."%' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['Vacation2']<>""){
					$tab = explode(";",$_SESSION['Vacation2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_olwficheintervention.Vacation='".$valeur."' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['CE2']<>""){
					$tab = explode(";",$_SESSION['CE2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_olwficheintervention.Id_PROD=".substr($valeur,1)." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['SansDate2']=="oui"){
					$req.=" ( ";
					$req.="sp_olwficheintervention.DateIntervention <= '0001-01-01' OR ";
				}
				if($_SESSION['DateDebut2']<>"" || $_SESSION['DateFin2']<>""){
					$req.=" ( ";
					if($_SESSION['DateDebut2']<>""){
						$req.="sp_olwficheintervention.DateIntervention >= '". TrsfDate_($_SESSION['DateDebut2'])."' ";
						$req.=" AND ";
					}
					if($_SESSION['DateFin2']<>""){
						$req.="sp_olwficheintervention.DateIntervention <= '". TrsfDate_($_SESSION['DateFin2'])."' ";
						$req.=" ";
					}
					if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
					$req.=" ) ";
				}
				if(substr($req,strlen($req)-3)== "OR "){$req=substr($req,0,-3);}
				if($_SESSION['SansDate2']=="oui"){
					$req.=" ) ";
				}
				if($_SESSION['SansDate2']=="oui" || $_SESSION['DateDebut2']<>"" || $_SESSION['DateFin2']<>""){
					$req.=" AND ";
				}
				if($_SESSION['VacationQUALITE2']<>""){
					$tab = explode(";",$_SESSION['VacationQUALITE2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_olwficheintervention.VacationQ='".$valeur."' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['IQ2']<>""){
					$tab = explode(";",$_SESSION['IQ2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_olwficheintervention.Id_QUALITE=".substr($valeur,1)." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['Stamp2']<>""){
					$tab = explode(";",$_SESSION['Stamp2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$reqIQ="SELECT Id_Personne FROM new_competences_personne_stamp WHERE Num_Stamp='".$valeur."'";
							$resultIQ=mysqli_query($bdd,$reqIQ);
							$nbResultaIQ=mysqli_num_rows($resultIQ);
							if($nbResultaIQ>0){
								while($rowIQ=mysqli_fetch_array($resultIQ)){
									$req.="sp_olwficheintervention.Id_QUALITE=".$rowIQ['Id_Personne']." OR ";
								}
							}
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['SansDateQUALITE2']=="oui"){
					$req.=" ( ";
					$req.="sp_olwficheintervention.DateInterventionQ <= '0001-01-01' OR ";
				}
				if($_SESSION['DateDebutQUALITE2']<>"" || $_SESSION['DateFinQUALITE2']<>""){
					$req.=" ( ";
					if($_SESSION['DateDebutQUALITE2']<>""){
						$req.="sp_olwficheintervention.DateInterventionQ >= '". TrsfDate_($_SESSION['DateDebutQUALITE2'])."' ";
						$req.=" AND ";
					}
					if($_SESSION['DateFinQUALITE2']<>""){
						$req.="sp_olwficheintervention.DateInterventionQ <= '". TrsfDate_($_SESSION['DateFinQUALITE2'])."' ";
						$req.=" ";
					}
					if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
					$req.=" ) ";
				}
				if(substr($req,strlen($req)-3)== "OR "){$req=substr($req,0,-3);}
				if($_SESSION['SansDateQUALITE2']=="oui"){
					$req.=" ) ";
				}
				if($_SESSION['SansDateQUALITE2']=="oui" || $_SESSION['DateDebutQUALITE2']<>"" || $_SESSION['DateFinQUALITE2']<>""){
					$req.=" AND ";
				}
				if($_SESSION['NumIC2']<>""){
					$tab = explode(";",$_SESSION['NumIC2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_olwficheintervention.NumFI='".addslashes($valeur)."' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['Poste2']<>""){
					$tab = explode(";",$_SESSION['Poste2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_olwficheintervention.PosteAvionACP='".addslashes($valeur)."' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['CreateurIC2']<>""){
					$tab = explode(";",$_SESSION['CreateurIC2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_olwficheintervention.Id_Createur=".substr($valeur,1)." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['StatutIC2']<>""){
					$tab = explode(";",$_SESSION['StatutIC2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							if($valeur=="(vide)"){$req.="sp_olwficheintervention.Id_StatutPROD='' OR sp_olwficheintervention.Id_StatutQUALITE='' OR ";}
							elseif($valeur=="TFS" || $valeur=="TERA" || $valeur=="RETP" || $valeur=="A RELANCER"){$req.="sp_olwficheintervention.Id_StatutPROD='".$valeur."' OR ";}
							elseif($valeur=="TVS" || $valeur=="TERC" || $valeur=="RETQ"){$req.="sp_olwficheintervention.Id_StatutQUALITE='".$valeur."' OR ";}
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
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
			}
		?>
		<tr>
			<td align="center" style="font-size:14px;">
				<?php
					if($_SESSION['ModeFiltre']=="oui"){
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
					}
				?>
			</td>
		</tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr bgcolor="#00325F">
					<td class="EnTeteTableauCompetences" width="1%" style="text-align:center;"></td>
					<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=MSN">MSN<?php if($_SESSION['TriMSN']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriMSN']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=OF">N° OF<?php if($_SESSION['TriOF']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriOF']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="3%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=Zone">Zone<?php if($_SESSION['TriZone']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriZone']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="3%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=Priorite">Priorité<?php if($_SESSION['TriPriorite']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriPriorite']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="3%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=TAI">TAI restant<?php if($_SESSION['TriTAI']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriTAI']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="13%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=Titre">Titre<?php if($_SESSION['TriTitre']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriTitre']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="3%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=DateIntervention">Date d'intervention<?php if($_SESSION['TriDateIntervention']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriDateIntervention']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="3%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=Vacation">Vacation<?php if($_SESSION['TriVacation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriVacation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="3%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=NumFI">N°FI<?php if($_SESSION['TriNumFI']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriNumFI']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="18%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=TravailRealise">Travail à réaliser<?php if($_SESSION['TriTravailRealise']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriTravailRealise']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="4%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=Poste">Poste d'intervention<?php if($_SESSION['TriPoste']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriPoste']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="4%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=StatutPROD">Statut PROD<?php if($_SESSION['TriStatutProd']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriStatutProd']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="4%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=RetourPROD">Retour PROD<?php if($_SESSION['TriRetourPROD']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRetourPROD']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="4%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=StatutQUALITE">Statut QUALITE<?php if($_SESSION['TriStatutQualite']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriStatutQualite']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="4%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=RetourQUALITE">Retour QUALITE<?php if($_SESSION['TriRetourQUALITE']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRetourQUALITE']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;"></td>
					<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;"></td>
					<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;"></td>
				</tr>
				<tr>
					<?php
						if($_SESSION['ModeFiltre']=="oui"){
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
									$Priorite="";
									if($row['Priorite']==1){$Priorite="Low";}
									elseif($row['Priorite']==2){$Priorite="Medium";}
									else{$Priorite="High";}
									
									$vacation="";
									if($row['Vacation']=="J"){$vacation="Jour";}
									elseif($row['Vacation']=="S"){$vacation="Soir";}
									elseif($row['Vacation']=="N"){$vacation="Nuit";}
									elseif($row['Vacation']=="VSD Jour"){$vacation="VSD Jour";}
									elseif($row['Vacation']=="VSD Nuit"){$vacation="VSD Nuit";}
									
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
									?>
										<tr bgcolor="<?php echo $couleur;?>">
											<td width="1%" style="text-align:center;">&nbsp;<?php echo $etoile;?></td>
											<td width="2%" style="text-align:center;">&nbsp;<?php echo $row['MSN'];?></td>
											<td width="6%" style="text-align:center;"><?php echo $row['Reference'];?></td>
											<td width="5%" style="text-align:center;"><?php echo $row['Zone'];?></td>
											<td width="5%" style="text-align:center;"><?php echo $Priorite;?></td>
											<td width="5%" style="text-align:center;"><?php echo $row['TAI_RestantACP'];?></td>
											<td width="15%" style="text-align:center;"><?php echo $row['Titre'];?></td>
											<td width="3%" style="text-align:center;"><?php echo AfficheDateFR($row['DateIntervention']);?></td>
											<td width="5%" style="text-align:center;"><?php echo $vacation;?></td>
											<td width="5%" style="text-align:center;"><?php echo $row['NumFI'];?></td>
											<td width="5%" style="text-align:center;"><?php echo $row['TravailRealise'];?></td>
											<td width="4%" style="text-align:center;"><?php echo $row['PosteAvionACP'];?></td>
											<td width="4%" <?php echo $HoverPROD;?> style="text-align:center;"><?php echo $row['Id_StatutPROD'].$infoBullePROD;?></td>
											<td width="4%" style="text-align:center"><?php echo $row['RetourPROD'];?></td>
											<td width="4%" <?php echo $HoverQUALITE;?> style="text-align:center;"><?php echo $row['Id_StatutQUALITE'].$infoBulleQUALITE;?></td>
											<td width="4%" style="text-align:center;"><?php echo $row['RetourQUALITE'];?></td>
											<td width="2%" align="center">
												<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>,<?php echo $_SESSION['Id_PersonneSP'];?>,<?php echo $row['Id_Dossier'];?>)">
												<img src='../../../Images/Modif.gif' border='0' alt='Modifier' title='Modifier'>
												</a>
											</td>
											<td width="2%" align="center">
												<?php
													//Duplication uniquement sur le dernier élément créé + si <>TERA et <>TERC
													$reqFI="SELECT MAX(Id) AS Id FROM sp_olwficheintervention WHERE Id_Dossier=".$row['Id_Dossier'];
													$resultFI=mysqli_query($bdd,$reqFI);
													$nbResultaFI=mysqli_num_rows($resultFI);
													if($nbResultaFI>0){
														$rowFI=mysqli_fetch_array($resultFI);
														if($rowFI['Id']==$row['Id']){
															if(($row['Id_StatutPROD']<>"" && $row['Id_StatutPROD']<>"TERA") || ($row['Id_StatutPROD']=="TERA" && $row['Id_StatutQUALITE']<>"" && $row['Id_StatutQUALITE']<>"TERC") || ($row['Id_StatutPROD']=="" && $row['Id_StatutQUALITE']<>"" && $row['Id_StatutQUALITE']<>"TERC")){
												?>
																<a href="javascript:OuvreFenetreDupliquer(<?php echo $row['Id']; ?>,<?php echo $_SESSION['Id_PersonneSP'];?>)">
																<img src='../../../Images/copier.gif' border='0' alt='Dupliquer' title='Dupliquer'>
																</a>
												<?php
															}
														}
													}
												?>
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
						}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				if($_SESSION['ModeFiltre']=="oui"){
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
				}
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
<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout_Critere(){
			var w=window.open("Ajout_Critere.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=640");
			w.focus();
		}
		function Suppr_Critere(critere,valeur){
			var w=window.open("Ajout_Critere.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=400");
			w.focus();
		}
		function OuvreFenetreModif(Id,Id_Personne){
			var w=window.open("Modif_Dossier.php?Mode=M&Id="+Id+"&Id_Personne="+Id_Personne,"PageDossier","status=no,menubar=no,scrollbars=yes,width=1300,height=700");
			w.focus();
		}
		function OuvreFenetreDupliquer(Id,Id_Personne){
			var w=window.open("Dupliquer_Dossier.php?Id="+Id+"&Id_Personne="+Id_Personne,"PageDupliquer","status=no,menubar=no,scrollbars=yes,width=1300,height=700");
			w.focus();
		}
		function OuvreFenetreIndice(Id,Id_Personne){
			var w=window.open("Indicer_Dossier.php?Id="+Id+"&Id_Personne="+Id_Personne,"PageIndicer","status=no,menubar=no,scrollbars=yes,width=1300,height=700");
			w.focus();
		}
		function OuvreFenetreSuppr(Id,Id_Personne){
			if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
				var w=window.open("Modif_Dossier.php?Mode=S&Id="+Id+"&Id_Personne="+Id_Personne,"PageDossier","status=no,menubar=no,scrollbars=yes,width=130,height=60");
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
		function SaisirDossier(NumDossier){
			if(NumDossier!='' && NumDossier!='0'){
				var w=window.open("Saisir_Dossier.php?Id="+NumDossier,"PageSaisir","status=no,menubar=no,scrollbars=yes,width=1300,height=700");
				w.focus();
				}
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
		$_SESSION['MSN']="";
		$_SESSION['NumDossier']="";
		$_SESSION['NumIC']="";
		$_SESSION['Section']="";
		$_SESSION['Zone']="";
		$_SESSION['CreateurDossier']="";
		$_SESSION['CreateurIC']="";
		$_SESSION['CE']="";
		$_SESSION['IQ']="";
		$_SESSION['StatutIC']="";
		$_SESSION['Vacation']="";
		$_SESSION['Urgence']="";
		$_SESSION['Titre']="";
		$_SESSION['DateDebut']="";
		$_SESSION['DateFin']="";
		$_SESSION['SansDate']="";
		$_SESSION['EtatIC']="";
		$_SESSION['Pole_FI']="";
		$_SESSION['Competence']="";
		$_SESSION['Stamp']="";
		$_SESSION['OF']="";
		$_SESSION['PNE']="";
		$_SESSION['DateDebutQUALITE']="";
		$_SESSION['DateFinQUALITE']="";
		$_SESSION['SansDateQUALITE']="";
		$_SESSION['VacationQUALITE']="";
		
		$_SESSION['MSN2']="";
		$_SESSION['NumDossier2']="";
		$_SESSION['NumIC2']="";
		$_SESSION['Section2']="";
		$_SESSION['Zone2']="";
		$_SESSION['CreateurDossier2']="";
		$_SESSION['CreateurIC2']="";
		$_SESSION['CE2']="";
		$_SESSION['IQ2']="";
		$_SESSION['StatutIC2']="";
		$_SESSION['Vacation2']="";
		$_SESSION['Urgence2']="";
		$_SESSION['Titre2']="";
		$_SESSION['DateDebut2']="";
		$_SESSION['DateFin2']="";
		$_SESSION['SansDate2']="";
		$_SESSION['EtatIC2']="";
		$_SESSION['ModeFiltre']="";
		$_SESSION['Pole_FI2']="";
		$_SESSION['Competence2']="";
		$_SESSION['Stamp2']="";
		$_SESSION['PNE2']="";
		$_SESSION['DateDebutQUALITE2']="";
		$_SESSION['DateFinQUALITE2']="";
		$_SESSION['SansDateQUALITE2']="";
		$_SESSION['VacationQUALITE2']="";
	}
	elseif(isset($_POST['BtnRechercher'])){
		$_SESSION['OF']=$_POST['numOF'];
		$_SESSION['ModeFiltre']="oui";
	}
	elseif(isset($_POST['Tri_RAZ'])){
		$_SESSION['TriMSN']="";
		$_SESSION['TriOF']="";
		$_SESSION['TriSection']="";
		$_SESSION['TriZone']="";
		$_SESSION['TriUrgence']="";
		$_SESSION['TriPriorite']="";
		$_SESSION['TriTitre']="";
		$_SESSION['TriDateIntervention']="";
		$_SESSION['TriVacation']="";
		$_SESSION['TriFI']="";
		$_SESSION['TriTravailRealise']="";
		$_SESSION['TriStatutProd']="";
		$_SESSION['TriStatutQualite']="";
		$_SESSION['TriCreateurDossier']="";
		$_SESSION['TriEtatIC']="";
		$_SESSION['TriTAI']="";
		$_SESSION['TriCT']="";
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
		<tr>
			<td>&nbsp;N° OF : <input name="numOF" type="texte" value="<?php if($_POST){echo $_POST['numOF'];}else{echo $_SESSION['OF'];}?>"> </td>
		</tr>
		<tr>
			<td><b>&nbsp;OU</b></td>
		</tr>
		<?php
			if($_SESSION['MSN']<>""){
				echo "<tr>";
				echo "<td>MSN : ".$_SESSION['MSN']."</td>";
				echo "</tr>";
			}
			if($_SESSION['NumDossier']<>""){
				echo "<tr>";
				echo "<td>N° OF : ".$_SESSION['NumDossier']."</td>";
				echo "</tr>";
			}
			if($_SESSION['NumIC']<>""){
				echo "<tr>";
				echo "<td>N° IC : ".$_SESSION['NumIC']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Section']<>""){
				echo "<tr>";
				echo "<td>Sections : ".$_SESSION['Section']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Zone']<>""){
				echo "<tr>";
				echo "<td>Zones : ".$_SESSION['Zone']."</td>";
				echo "</tr>";
			}
			if($_SESSION['CreateurDossier']<>""){
				echo "<tr>";
				echo "<td>Créateurs dossiers : ".$_SESSION['CreateurDossier']."</td>";
				echo "</tr>";
			}
			if($_SESSION['CreateurIC']<>""){
				echo "<tr>";
				echo "<td>Créateurs IC : ".$_SESSION['CreateurIC']."</td>";
				echo "</tr>";
			}
			if($_SESSION['CE']<>""){
				echo "<tr>";
				echo "<td>Chefs d'équipes : ".$_SESSION['CE']."</td>";
				echo "</tr>";
			}
			if($_SESSION['IQ']<>""){
				echo "<tr>";
				echo "<td>Inspecteurs qualité : ".$_SESSION['IQ']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Stamp']<>""){
				echo "<tr>";
				echo "<td>Marque de contrôle : ".$_SESSION['Stamp']."</td>";
				echo "</tr>";
			}
			if($_SESSION['StatutIC']<>""){
				echo "<tr>";
				echo "<td>Statuts IC : ".$_SESSION['StatutIC']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Vacation']<>""){
				echo "<tr>";
				echo "<td>Vacations PROD : ".$_SESSION['Vacation']."</td>";
				echo "</tr>";
			}
			if($_SESSION['VacationQUALITE']<>""){
				echo "<tr>";
				echo "<td>Vacations QUALITE : ".$_SESSION['VacationQUALITE']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Urgence']<>""){
				echo "<tr>";
				echo "<td>Urgences : ".$_SESSION['Urgence']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Titre']<>""){
				echo "<tr>";
				echo "<td>Titres : ".$_SESSION['Titre']."</td>";
				echo "</tr>";
			}
			if($_SESSION['DateDebut']<>""){
				echo "<tr>";
				echo "<td>Date de début PROD : ".$_SESSION['DateDebut']."</td>";
				echo "</tr>";
			}
			if($_SESSION['DateFin']<>""){
				echo "<tr>";
				echo "<td>Date de fin PROD : ".$_SESSION['DateFin']."</td>";
				echo "</tr>";
			}
			if($_SESSION['SansDate']<>""){
				echo "<tr>";
				echo "<td>Sans date PROD : ".$_SESSION['SansDate']."</td>";
				echo "</tr>";
			}
			if($_SESSION['DateDebutQUALITE']<>""){
				echo "<tr>";
				echo "<td>Date de début QUALITE : ".$_SESSION['DateDebutQUALITE']."</td>";
				echo "</tr>";
			}
			if($_SESSION['DateFinQUALITE']<>""){
				echo "<tr>";
				echo "<td>Date de fin QUALITE : ".$_SESSION['DateFinQUALITE']."</td>";
				echo "</tr>";
			}
			if($_SESSION['SansDateQUALITE']<>""){
				echo "<tr>";
				echo "<td>Sans date QUALITE : ".$_SESSION['SansDateQUALITE']."</td>";
				echo "</tr>";
			}
			if($_SESSION['EtatIC']<>""){
				echo "<tr>";
				echo "<td>Etats IC : ".$_SESSION['EtatIC']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Pole_FI']<>""){
				echo "<tr>";
				echo "<td>Pôle : ".$_SESSION['Pole_FI']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Competence']<>""){
				echo "<tr>";
				echo "<td>Competences : ".$_SESSION['Competence']."</td>";
				echo "</tr>";
			}
			if($_SESSION['PNE']<>""){
				echo "<tr>";
				echo "<td>Poste neutre : ".$_SESSION['PNE']."</td>";
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
				if($_GET['Tri']=="DateInter"){
					$_SESSION['TriGeneral']= str_replace("DateIntervention ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("DateIntervention DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("DateIntervention ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("DateIntervention DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriDateIntervention']==""){$_SESSION['TriDateIntervention']="ASC";$_SESSION['TriGeneral'].= "DateIntervention ".$_SESSION['TriDateIntervention'].",";}
					elseif($_SESSION['TriDateIntervention']=="ASC"){$_SESSION['TriDateIntervention']="DESC";$_SESSION['TriGeneral'].= "DateIntervention ".$_SESSION['TriDateIntervention'].",";}
					else{$_SESSION['TriDateIntervention']="";}
				}
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
				if($_GET['Tri']=="Section"){
					$_SESSION['TriGeneral']= str_replace("SectionACP ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("SectionACP DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("SectionACP ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("SectionACP DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriSection']==""){$_SESSION['TriSection']="ASC";$_SESSION['TriGeneral'].= "SectionACP ".$_SESSION['TriSection'].",";}
					elseif($_SESSION['TriSection']=="ASC"){$_SESSION['TriSection']="DESC";$_SESSION['TriGeneral'].= "SectionACP ".$_SESSION['TriSection'].",";}
					else{$_SESSION['TriSection']="";}
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
				if($_GET['Tri']=="Urgence"){
					$_SESSION['TriGeneral']= str_replace("Urgence ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Urgence DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Urgence ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Urgence DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriUrgence']==""){$_SESSION['TriUrgence']="ASC";$_SESSION['TriGeneral'].= "Urgence ".$_SESSION['TriUrgence'].",";}
					elseif($_SESSION['TriUrgence']=="ASC"){$_SESSION['TriUrgence']="DESC";$_SESSION['TriGeneral'].= "Urgence ".$_SESSION['TriUrgence'].",";}
					else{$_SESSION['TriUrgence']="";}
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
				if($_GET['Tri']=="Titre"){
					$_SESSION['TriGeneral']= str_replace("Titre ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Titre DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Titre ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Titre DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriTitre']==""){$_SESSION['TriTitre']="ASC";$_SESSION['TriGeneral'].= "Titre ".$_SESSION['TriTitre'].",";}
					elseif($_SESSION['TriTitre']=="ASC"){$_SESSION['TriTitre']="DESC";$_SESSION['TriGeneral'].= "Titre ".$_SESSION['TriTitre'].",";}
					else{$_SESSION['TriTitre']="";}
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
				
				//A MODIFIER LORSQUE FICHIER EXCEL PAR NumFI
				if($_GET['Tri']=="FI"){
					$_SESSION['TriGeneral']= str_replace("Commentaire ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Commentaire DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Commentaire ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("Commentaire DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriFI']==""){$_SESSION['TriFI']="ASC";$_SESSION['TriGeneral'].= "Commentaire ".$_SESSION['TriFI'].",";}
					elseif($_SESSION['TriFI']=="ASC"){$_SESSION['TriFI']="DESC";$_SESSION['TriGeneral'].= "Commentaire ".$_SESSION['TriFI'].",";}
					else{$_SESSION['TriFI']="";}
				}
				if($_GET['Tri']=="TravailR"){
					$_SESSION['TriGeneral']= str_replace("TravailRealise ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("TravailRealise DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("TravailRealise ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("TravailRealise DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriTravailRealise']==""){$_SESSION['TriTravailRealise']="ASC";$_SESSION['TriGeneral'].= "TravailRealise ".$_SESSION['TriTravailRealise'].",";}
					elseif($_SESSION['TriTravailRealise']=="ASC"){$_SESSION['TriTravailRealise']="DESC";$_SESSION['TriGeneral'].= "TravailRealise ".$_SESSION['TriTravailRealise'].",";}
					else{$_SESSION['TriTravailRealise']="";}
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
				if($_GET['Tri']=="EtatIC"){
					$_SESSION['TriGeneral']= str_replace("EtatICCIA ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("EtatICCIA DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("EtatICCIA ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("EtatICCIA DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriEtatIC']==""){$_SESSION['TriEtatIC']="ASC";$_SESSION['TriGeneral'].= "EtatICCIA ".$_SESSION['TriEtatIC'].",";}
					elseif($_SESSION['TriEtatIC']=="ASC"){$_SESSION['TriEtatIC']="DESC";$_SESSION['TriGeneral'].= "EtatICCIA ".$_SESSION['TriEtatIC'].",";}
					else{$_SESSION['TriEtatIC']="";}
				}
				if($_GET['Tri']=="TAI_RestantACP"){
					$_SESSION['TriGeneral']= str_replace("TAI_RestantACP ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("TAI_RestantACP DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("TAI_RestantACP ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("TAI_RestantACP DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriTAI']==""){$_SESSION['TriTAI']="ASC";$_SESSION['TriGeneral'].= "TAI_RestantACP ".$_SESSION['TriTAI'].",";}
					elseif($_SESSION['TriTAI']=="ASC"){$_SESSION['TriTAI']="DESC";$_SESSION['TriGeneral'].= "TAI_RestantACP ".$_SESSION['TriTAI'].",";}
					else{$_SESSION['TriTAI']="";}
				}
				if($_GET['Tri']=="CreateurDossier"){
					$_SESSION['TriGeneral']= str_replace("CreateurDossier ASC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("CreateurDossier DESC,","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("CreateurDossier ASC","",$_SESSION['TriGeneral']);
					$_SESSION['TriGeneral']= str_replace("CreateurDossier DESC","",$_SESSION['TriGeneral']);
					if($_SESSION['TriCT']==""){$_SESSION['TriCT']="ASC";$_SESSION['TriGeneral'].= "CreateurDossier ".$_SESSION['TriCT'].",";}
					elseif($_SESSION['TriCT']=="ASC"){$_SESSION['TriCT']="DESC";$_SESSION['TriGeneral'].= "CreateurDossier ".$_SESSION['TriCT'].",";}
					else{$_SESSION['TriCT']="";}
				}
			}
			
				$reqAnalyse="SELECT sp_ficheintervention.Id ";
				$req2="SELECT sp_ficheintervention.Id,sp_dossier.MSN,sp_dossier.Reference,sp_dossier.SectionACP,sp_ficheintervention.Commentaire,sp_dossier.TAI_RestantACP,";
				$req2.="(SELECT sp_zonedetravail.Libelle FROM sp_zonedetravail WHERE sp_zonedetravail.Id=sp_dossier.Id_ZoneDeTravail) AS Zone, 
					(SELECT Libelle FROM sp_retour WHERE Id=sp_ficheintervention.Id_RetourPROD) AS RetourPROD, 
					(SELECT Libelle FROM sp_retour WHERE Id=sp_ficheintervention.Id_RetourQUALITE) AS RetourQUALITE,";
				$req2.="(SELECT sp_urgence.Libelle FROM sp_urgence WHERE sp_urgence.Id=sp_dossier.Id_Urgence) AS Urgence, ";
				$req2.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_dossier.Id_Personne) AS CreateurDossier, ";
				$req2.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_ficheintervention.Id_Createur) AS CreateurFI, ";
				$req2.="sp_dossier.Priorite,sp_dossier.Titre,sp_ficheintervention.NumFI, sp_ficheintervention.Vacation,
						sp_dossier.Elec,sp_dossier.Systeme,sp_dossier.Structure,sp_dossier.Oxygene,sp_dossier.Hydraulique,sp_dossier.Fuel,sp_dossier.Metal,sp_ficheintervention.Id_RetourPROD,sp_ficheintervention.Id_RetourQUALITE,
						";
				$req2.="sp_ficheintervention.DateIntervention,sp_ficheintervention.TravailRealise,sp_ficheintervention.Id_StatutPROD,sp_ficheintervention.Id_StatutQUALITE,sp_ficheintervention.EtatICCIA ";
				$req="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
				$req.="WHERE ";
				if($_SESSION['OF']){
					$req.="sp_dossier.Reference LIKE '%".addslashes($_SESSION['OF'])."%' ";
				}
				else{
					if($_SESSION['MSN2']<>""){
						$tab = explode(";",$_SESSION['MSN2']);
						$req.="(";
						foreach($tab as $valeur){
							 if($valeur<>""){
								$req.="sp_dossier.MSN=".$valeur." OR ";
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
								$req.="sp_dossier.Reference='".addslashes($valeur)."' OR ";
							 }
						}
						$req=substr($req,0,-3);
						$req.=") AND ";
					}
					if($_SESSION['NumIC2']<>""){
						$tab = explode(";",$_SESSION['NumIC2']);
						$req.="(";
						foreach($tab as $valeur){
							 if($valeur<>""){
								// A MODIFIER LORSQUE FICHIER EXCEL PAR NumFI
								$req.="sp_ficheintervention.Commentaire='".addslashes($valeur)."' OR ";
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
								$req.="sp_dossier.SectionACP='".addslashes($valeur)."' OR ";
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
								$req.="sp_dossier.Id_ZoneDeTravail=".substr($valeur,1)." OR ";
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
								$req.="sp_dossier.Id_Personne=".substr($valeur,1)." OR ";
							 }
						}
						$req=substr($req,0,-3);
						$req.=") AND ";
					}
					if($_SESSION['Pole_FI2']<>""){
						$tab = explode(";",$_SESSION['Pole_FI2']);
						$req.="(";
						foreach($tab as $valeur){
							 if($valeur<>""){
								$req.="sp_ficheintervention.Id_Pole=".substr($valeur,1)." OR ";
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
								$req.="sp_ficheintervention.Id_Createur=".substr($valeur,1)." OR ";
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
								$req.="sp_ficheintervention.Id_PROD=".substr($valeur,1)." OR ";
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
								$req.="sp_ficheintervention.Id_QUALITE=".substr($valeur,1)." OR ";
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
										$req.="sp_ficheintervention.Id_QUALITE=".$rowIQ['Id_Personne']." OR ";
									}
								}
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
								$req.="sp_ficheintervention.Vacation='".$valeur."' OR ";
							 }
						}
						$req=substr($req,0,-3);
						$req.=") AND ";
					}
					if($_SESSION['VacationQUALITE2']<>""){
						$tab = explode(";",$_SESSION['VacationQUALITE2']);
						$req.="(";
						foreach($tab as $valeur){
							 if($valeur<>""){
								$req.="sp_ficheintervention.VacationQ='".$valeur."' OR ";
							 }
						}
						$req=substr($req,0,-3);
						$req.=") AND ";
					}
					if($_SESSION['EtatIC2']<>""){
						$tab = explode(";",$_SESSION['EtatIC2']);
						$req.="(";
						foreach($tab as $valeur){
							 if($valeur<>""){
								$req.="sp_ficheintervention.EtatICCIA='".$valeur."' OR ";
							 }
						}
						$req=substr($req,0,-3);
						$req.=") AND ";
					}
					if($_SESSION['Competence2']<>""){
						$tab = explode(";",$_SESSION['Competence2']);
						$req.="(";
						foreach($tab as $valeur){
							if($valeur=="Fuel"){$req.="sp_dossier.Fuel=1 OR ";}
							elseif($valeur=="Elec"){$req.="sp_dossier.Elec=1 OR ";}
							elseif($valeur=="Hydraulique"){$req.="sp_dossier.Hydraulique=1 OR ";}
							elseif($valeur=="Metal"){$req.="sp_dossier.Metal=1 OR ";}
							elseif($valeur=="Structure"){$req.="sp_dossier.Structure=1 OR ";}
							elseif($valeur=="Oxygene"){$req.="sp_dossier.Oxygene=1 OR ";}
							elseif($valeur=="Systeme"){$req.="sp_dossier.Systeme=1 OR ";}
						}
						$req=substr($req,0,-3);
						$req.=") AND ";
					}
					if($_SESSION['Urgence2']<>""){
						$tab = explode(";",$_SESSION['Urgence2']);
						$req.="(";
						foreach($tab as $valeur){
							 if($valeur<>""){
								$req.="sp_dossier.Id_Urgence=".substr($valeur,1)." OR ";
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
								$req.="sp_dossier.Titre LIKE '%".addslashes($valeur)."%' OR ";
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
								if($valeur=="(vide)"){$req.="sp_ficheintervention.Id_StatutPROD='' OR sp_ficheintervention.Id_StatutQUALITE='' OR ";}
								elseif($valeur=="TFS" || $valeur=="QARJ"){$req.="sp_ficheintervention.Id_StatutPROD='".$valeur."' OR ";}
								elseif($valeur=="TVS" || $valeur=="CERT"){$req.="sp_ficheintervention.Id_StatutQUALITE='".$valeur."' OR ";}
							 }
						}
						$req=substr($req,0,-3);
						$req.=") AND ";
					}
					if($_SESSION['PNE2']<>""){
						if($_SESSION['PNE2']=="Avec les PNE"){
							$req.="sp_dossier.PNE=1 AND ";
						}
						elseif($_SESSION['PNE2']=="Sans les PNE"){
							$req.="sp_dossier.PNE=0 AND ";
						}
					}
					if($_SESSION['SansDate2']=="oui"){
						$req.=" ( ";
						$req.="sp_ficheintervention.DateIntervention <= '0001-01-01' OR ";
					}
					if($_SESSION['DateDebut2']<>"" || $_SESSION['DateFin2']<>""){
						$req.=" ( ";
						if($_SESSION['DateDebut2']<>""){
							$req.="sp_ficheintervention.DateIntervention >= '". TrsfDate_($_SESSION['DateDebut2'])."' ";
							$req.=" AND ";
						}
						if($_SESSION['DateFin2']<>""){
							$req.="sp_ficheintervention.DateIntervention <= '". TrsfDate_($_SESSION['DateFin2'])."' ";
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
					
					if($_SESSION['SansDateQUALITE2']=="oui"){
						$req.=" ( ";
						$req.="sp_ficheintervention.DateInterventionQ <= '0001-01-01' OR ";
					}
					if($_SESSION['DateDebutQUALITE2']<>"" || $_SESSION['DateFinQUALITE2']<>""){
						$req.=" ( ";
						if($_SESSION['DateDebutQUALITE2']<>""){
							$req.="sp_ficheintervention.DateInterventionQ >= '". TrsfDate_($_SESSION['DateDebutQUALITE2'])."' ";
							$req.=" AND ";
						}
						if($_SESSION['DateFinQUALITE2']<>""){
							$req.="sp_ficheintervention.DateInterventionQ <= '". TrsfDate_($_SESSION['DateFinQUALITE2'])."' ";
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
					
					if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
					if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}
				}
				$result=mysqli_query($bdd,$reqAnalyse.$req);
				$nbResulta=mysqli_num_rows($result);
				
				if($_SESSION['TriGeneral']<>""){
					$req.="ORDER BY ".substr($_SESSION['TriGeneral'],0,-1);
				}

				$nombreDePages=ceil($nbResulta/200);
				if(isset($_GET['Page'])){$_SESSION['Page']=$_GET['Page'];}
				//else{$_SESSION['Page']=0;}
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
				<tr>
					<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_Dossier.php?Tri=MSN">MSN<?php if($_SESSION['TriMSN']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriMSN']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_Dossier.php?Tri=OF">N° OF<?php if($_SESSION['TriOF']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriOF']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;color:#2f00ee;font-weight:bold;">Type de travail</td>
					<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_Dossier.php?Tri=CreateurDossier">CT<?php if($_SESSION['TriCT']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriCT']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="4%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_Dossier.php?Tri=Zone">Zone<?php if($_SESSION['TriZone']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriZone']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_Dossier.php?Tri=Urgence">Urgence<?php if($_SESSION['TriUrgence']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriUrgence']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="4%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_Dossier.php?Tri=Priorite">Priorité<?php if($_SESSION['TriPriorite']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriPriorite']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="4%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_Dossier.php?Tri=TAI_RestantACP">TAI restant<?php if($_SESSION['TriTAI']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriTAI']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="9%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_Dossier.php?Tri=Titre">Titre<?php if($_SESSION['TriTitre']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriTitre']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="7%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_Dossier.php?Tri=DateInter">Date intervention<?php if($_SESSION['TriDateIntervention']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriDateIntervention']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_Dossier.php?Tri=Vacation">Vacation<?php if($_SESSION['TriVacation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriVacation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_Dossier.php?Tri=FI">N° FI<?php if($_SESSION['TriFI']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriFI']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="18%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_Dossier.php?Tri=TravailR">Travail à réaliser<?php if($_SESSION['TriTravailRealise']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriTravailRealise']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="4%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_Dossier.php?Tri=StatutPROD">Statut PROD<?php if($_SESSION['TriStatutProd']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriStatutProd']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="4%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_Dossier.php?Tri=StatutQUALITE">Statut QUALITE<?php if($_SESSION['TriStatutQualite']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriStatutQualite']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;"></td>
					<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;"></td>
					<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;"></td>
				</tr>
				<tr>
					<?php
							
							if ($nbResulta>0){
								$couleur="#ffffff";
								while($row=mysqli_fetch_array($result)){
									$priorite="";
									if($row['Priorite']==1){$priorite="Low";}
									elseif($row['Priorite']==2){$priorite="Medium";}
									elseif($row['Priorite']==3){$priorite="High";}
									
									$vacation="";
									if($row['Vacation']=="J"){$vacation="Jour";}
									elseif($row['Vacation']=="S"){$vacation="Soir";}
									elseif($row['Vacation']=="N"){$vacation="Nuit";}
									elseif($row['Vacation']=="VSD Jour"){$vacation="VSD Jour";}
									elseif($row['Vacation']=="VSD Nuit"){$vacation="VSD Nuit";}
									
									$dateIntervention = "";
									if($row['DateIntervention']>"0001-01-01"){$dateIntervention = $row['DateIntervention'];}	
									$NumFI=$row['NumFI'];
									if($row['Commentaire']<>""){
										$NumFI=$row['Commentaire'];
									}
									
									$TypeTravail="";
									if($row['Elec']==1){$TypeTravail.="ELEC ";}
									if($row['Systeme']==1){$TypeTravail.="SYSTEME ";}
									if($row['Structure']==1){$TypeTravail.="STRUCTURE ";}
									if($row['Oxygene']==1){$TypeTravail.="OXYGENE ";}
									if($row['Hydraulique']==1){$TypeTravail.="HYDRAULIQUE ";}
									if($row['Fuel']==1){$TypeTravail.="FUEL ";}
									if($row['Metal']==1){$TypeTravail.="METAL ";}
									
									$couleur="#ffffff";
									if($row['Id_StatutQUALITE']=="CERT"){$couleur="#407c09";}
									elseif($row['Id_StatutPROD']=="QARJ"){$couleur="#b0f472";}
									elseif($row['Id_StatutPROD']=="REWORK"){$couleur="#f8fe68";}
									elseif($row['Id_StatutPROD']=="TFS"){
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
									?>
										<tr bgcolor="<?php echo $couleur;?>">
											<td width="2%" style="text-align:center;border-bottom:1px #0077aa dotted;">&nbsp;<?php echo $row['MSN'];?></td>
											<td width="6%" style="text-align:center;border-bottom:1px #0077aa dotted;"><?php echo $row['Reference'];?></td>
											<td width="6%" style="text-align:center;border-bottom:1px #0077aa dotted;"><?php echo $TypeTravail;?></td>
											<td width="6%" style="text-align:center;border-bottom:1px #0077aa dotted;"><?php echo $row['CreateurDossier'];?></td>
											<td width="4%" style="text-align:center;border-bottom:1px #0077aa dotted;"><?php echo $row['Zone'];?></td>
											<td width="5%" style="text-align:center;border-bottom:1px #0077aa dotted;"><?php echo $row['Urgence'];?></td>
											<td width="4%" style="text-align:center;border-bottom:1px #0077aa dotted;"><?php echo $priorite;?></td>
											<td width="4%" style="text-align:center;border-bottom:1px #0077aa dotted;"><?php echo $row['TAI_RestantACP'];?></td>
											<td width="9%" style="text-align:center;border-bottom:1px #0077aa dotted;"><?php echo $row['Titre'];?></td>
											<td width="7%" style="text-align:center;border-bottom:1px #0077aa dotted;"><?php echo $dateIntervention;?></td>
											<td width="6%" style="text-align:center;border-bottom:1px #0077aa dotted;"><?php echo $vacation;?></td>
											<td width="8%" style="text-align:center;border-bottom:1px #0077aa dotted;"><?php echo $NumFI; //A MODIFIER LORSQUE FICHIER EXCEL?></td> 
											<td width="18%" style="text-align:center;border-bottom:1px #0077aa dotted;" ><?php echo nl2br(stripslashes($row['TravailRealise']));?></td>
											<td width="4%" style="text-align:center;border-bottom:1px #0077aa dotted;"><?php echo $row['Id_StatutPROD'];?></td>
											<td width="4%" style="text-align:center;border-bottom:1px #0077aa dotted;"><?php echo $row['Id_StatutQUALITE'];?></td>
											<td width="2%" style="text-align:center;border-bottom:1px #0077aa dotted;">
												<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>,<?php echo $_SESSION['Id_PersonneSP'];?>)">
												<img src='../../../Images/Modif.gif' border='0' alt='Modifier' title='Modifier'>
												</a>
											</td>
											<td width="2%" style="text-align:center;border-bottom:1px #0077aa dotted;">
												<a href="javascript:OuvreFenetreDupliquer(<?php echo $row['Id']; ?>,<?php echo $_SESSION['Id_PersonneSP'];?>)">
												<img src='../../../Images/copier.gif' border='0' alt='Dupliquer' title='Dupliquer'>
												</a>
											</td>
											<td width="2%" style="text-align:center;border-bottom:1px #0077aa dotted;" valign="center">
												<?php
												if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1'){
												?>
													<a href="javascript:OuvreFenetreIndice(<?php echo $row['Id']; ?>,<?php echo $_SESSION['Id_PersonneSP'];?>)">
													<img src='../../../Images/Indicer2.GIF' border='0' alt='Indicer' title='Indicer'>
													</a>
												<?php
												}
												?>
											</td>
											<td width="2%" style="text-align:center;border-bottom:1px #0077aa dotted;">
												<?php
												if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1'){
												?>
													<a href="javascript:OuvreFenetreSuppr(<?php echo $row['Id']; ?>,<?php echo $_SESSION['Id_PersonneSP'];?>)">
													<img src='../../../Images/Suppression.gif' border='0' alt='Supprimer' title='Supprimer'>
													</a>
												<?php
												}
												?>
											</td>
										</tr>
									<?php
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
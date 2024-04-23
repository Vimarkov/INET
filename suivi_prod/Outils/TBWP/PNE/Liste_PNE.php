<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout_Critere(){
			var w=window.open("Ajout_Critere.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=440");
			w.focus();
			}
		function OuvreFenetreAjoutPNE(Id_Personne){
			var w=window.open("Modif_PNE.php?Mode=A&Id=0&Id_Personne="+Id_Personne,"PagePNE","status=no,menubar=no,width=1000,height=300");
			w.focus();
			}
		function Suppr_Critere(critere,valeur){
			var w=window.open("Ajout_Critere.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,fullscreen=yes,width=600,height=400");
			w.focus();
			}
		function OuvreFenetreModif(Id,Id_Personne){
			var w=window.open("Modif_PNE.php?Mode=M&Id="+Id+"&Id_Personne="+Id_Personne,"PageUtilisateur","status=no,menubar=no,fullscreen=yes,width=1000,height=300");
			w.focus();
			}
		function OuvreFenetreSuppr(Id,Id_Personne){
			if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
				var w=window.open("Modif_PNE.php?Mode=S&Id="+Id+"&Id_Personne="+Id_Personne,"PageUtilisateur","status=no,menubar=no,,fullscreen=yes,width=130,height=60");
				w.focus();
			}
			}
		function Tri(){
			var w=window.open("Ajout_Critere.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=400");
			w.focus();
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
		$_SESSION['FormA']="";
		$_SESSION['Pole']="";
		$_SESSION['Poste']="";
		$_SESSION['MSN_PNE']="";
		$_SESSION['Zone_PNE']="";
		$_SESSION['Compagnon']="";
		$_SESSION['NumEIC']="";
		$_SESSION['DateDebutPNE']="";
		$_SESSION['DateFinPNE']="";
		$_SESSION['SansDatePNE']="";
		$_SESSION['VacationPNE']="";
		$_SESSION['Id_CreateurPNE']="";
		$_SESSION['DateCreationPNE']="";
		
		$_SESSION['FormA2']="";
		$_SESSION['Pole2']="";
		$_SESSION['Poste2']="";
		$_SESSION['MSN_PNE2']="";
		$_SESSION['Zone_PNE2']="";
		$_SESSION['Compagnon2']="";
		$_SESSION['NumEIC2']="";
		$_SESSION['DateDebutPNE2']="";
		$_SESSION['DateFinPNE2']="";
		$_SESSION['SansDatePNE2']="";
		$_SESSION['VacationPNE2']="";
		$_SESSION['Id_CreateurPNE2']="";
		$_SESSION['DateCreationPNE2']="";
		$_SESSION['ModeFiltre2']="";
	}
	elseif(isset($_POST['BtnRechercher'])){
		$_SESSION['ModeFiltre2']="oui";
	}
	elseif(isset($_POST['Tri_RAZ'])){
		$_SESSION['TriFormA']="";
		$_SESSION['TriPole']="";
		$_SESSION['TriPoste']="";
		$_SESSION['TriMSN_PNE']="";
		$_SESSION['TriZone_PNE_PNE']="";
		$_SESSION['TriCompagnon']="";
		$_SESSION['TriNumEIC']="";
		$_SESSION['TriVacationPNEPNE']="";
		$_SESSION['TriId_CreateurPNE']="";
		$_SESSION['TriNbRetouche']="";
		$_SESSION['TriGeneralPNE']="";
	}
}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Liste_PNE.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Suivi des postes neutres</td>
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
			if($_SESSION['FormA']<>""){
				echo "<tr>";
				echo "<td>N° de Form A : ".$_SESSION['FormA']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Pole']<>""){
				echo "<tr>";
				echo "<td>Pôles : ".$_SESSION['Pole']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Poste']<>""){
				echo "<tr>";
				echo "<td>Postes : ".$_SESSION['Poste']."</td>";
				echo "</tr>";
			}
			if($_SESSION['MSN_PNE']<>""){
				echo "<tr>";
				echo "<td>MSN : ".$_SESSION['MSN_PNE']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Zone_PNE']<>""){
				echo "<tr>";
				echo "<td>Zones : ".$_SESSION['Zone_PNE']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Compagnon']<>""){
				echo "<tr>";
				echo "<td>Compagnons : ".$_SESSION['Compagnon']."</td>";
				echo "</tr>";
			}
			if($_SESSION['NumEIC']<>""){
				echo "<tr>";
				echo "<td>N° d'eic : ".$_SESSION['NumEIC']."</td>";
				echo "</tr>";
			}
			if($_SESSION['VacationPNE']<>""){
				echo "<tr>";
				echo "<td>Vacations : ".$_SESSION['VacationPNE']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Id_CreateurPNE']<>""){
				echo "<tr>";
				echo "<td>Créateur : ".$_SESSION['Id_CreateurPNE']."</td>";
				echo "</tr>";
			}
			if($_SESSION['DateDebutPNE']<>""){
				echo "<tr>";
				echo "<td>Date de début : ".$_SESSION['DateDebutPNE']."</td>";
				echo "</tr>";
			}
			if($_SESSION['DateFinPNE']<>""){
				echo "<tr>";
				echo "<td>Date de fin : ".$_SESSION['DateFinPNE']."</td>";
				echo "</tr>";
			}
			if($_SESSION['SansDatePNE']<>""){
				echo "<tr>";
				echo "<td>Sans date : ".$_SESSION['SansDatePNE']."</td>";
				echo "</tr>";
			}
		?>
		<tr>
			
			<td align="center" colspan="6"><input class="Bouton" name="BtnRechercher" size="10" type="submit" value="Rechercher">&nbsp;&nbsp;&nbsp;&nbsp;
			<input class="Bouton" name="Recherche_RAZ" type="submit" value="Vider les critères de recherche">
			<input class="Bouton" name="Tri_RAZ" type="submit" value="Effacer les tris">
			</td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td align="center">
			<?php 
			if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
				echo "<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjoutPNE(".$_SESSION['Id_PersonneSP'].")'>&nbsp;Ajouter PNE&nbsp;</a>";
			}
			?>
			
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<?php
			if(isset($_GET['Tri'])){
				if($_GET['Tri']=="FormA"){
					$_SESSION['TriGeneralPNE']= str_replace("NumFormA ASC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("NumFormA DESC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("NumFormA ASC","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("NumFormA DESC","",$_SESSION['TriGeneralPNE']);
					if($_SESSION['TriFormA']==""){$_SESSION['TriFormA']="ASC";$_SESSION['TriGeneralPNE'].= "NumFormA ".$_SESSION['TriFormA'].",";}
					elseif($_SESSION['TriFormA']=="ASC"){$_SESSION['TriFormA']="DESC";$_SESSION['TriGeneralPNE'].= "NumFormA ".$_SESSION['TriFormA'].",";}
					else{$_SESSION['TriFormA']="";}
				}
				if($_GET['Tri']=="MSN"){
					$_SESSION['TriGeneralPNE']= str_replace("MSN ASC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("MSN DESC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("MSN ASC","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("MSN DESC","",$_SESSION['TriGeneralPNE']);
					if($_SESSION['TriMSN_PNE']==""){$_SESSION['TriMSN_PNE']="ASC";$_SESSION['TriGeneralPNE'].= "MSN ".$_SESSION['TriMSN_PNE'].",";}
					elseif($_SESSION['TriMSN_PNE']=="ASC"){$_SESSION['TriMSN_PNE']="DESC";$_SESSION['TriGeneralPNE'].= "MSN ".$_SESSION['TriMSN_PNE'].",";}
					else{$_SESSION['TriMSN_PNE']="";}
				}
				if($_GET['Tri']=="Pole"){
					$_SESSION['TriGeneralPNE']= str_replace("Pole ASC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Pole DESC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Pole ASC","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Pole DESC","",$_SESSION['TriGeneralPNE']);
					if($_SESSION['TriPole']==""){$_SESSION['TriPole']="ASC";$_SESSION['TriGeneralPNE'].= "Pole ".$_SESSION['TriPole'].",";}
					elseif($_SESSION['TriPole']=="ASC"){$_SESSION['TriPole']="DESC";$_SESSION['TriGeneralPNE'].= "Pole ".$_SESSION['TriPole'].",";}
					else{$_SESSION['TriPole']="";}
				}
				if($_GET['Tri']=="Poste"){
					$_SESSION['TriGeneralPNE']= str_replace("Poste ASC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Poste DESC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Poste ASC","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Poste DESC","",$_SESSION['TriGeneralPNE']);
					if($_SESSION['TriPoste']==""){$_SESSION['TriPoste']="ASC";$_SESSION['TriGeneralPNE'].= "Poste ".$_SESSION['TriPoste'].",";}
					elseif($_SESSION['TriPoste']=="ASC"){$_SESSION['TriPoste']="DESC";$_SESSION['TriGeneralPNE'].= "Poste ".$_SESSION['TriPoste'].",";}
					else{$_SESSION['TriPoste']="";}
				}
				if($_GET['Tri']=="Zone"){
					$_SESSION['TriGeneralPNE']= str_replace("Zone ASC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Zone DESC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Zone ASC","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Zone DESC","",$_SESSION['TriGeneralPNE']);
					if($_SESSION['TriZone_PNE']==""){$_SESSION['TriZone_PNE']="ASC";$_SESSION['TriGeneralPNE'].= "Zone ".$_SESSION['TriZone_PNE'].",";}
					elseif($_SESSION['TriZone_PNE']=="ASC"){$_SESSION['TriZone_PNE']="DESC";$_SESSION['TriGeneralPNE'].= "Zone ".$_SESSION['TriZone_PNE'].",";}
					else{$_SESSION['TriZone_PNE']="";}
				}
				if($_GET['Tri']=="Compagnon"){
					$_SESSION['TriGeneralPNE']= str_replace("Compagnon ASC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Compagnon DESC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Compagnon ASC","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Compagnon DESC","",$_SESSION['TriGeneralPNE']);
					if($_SESSION['TriCompagnon']==""){$_SESSION['TriCompagnon']="ASC";$_SESSION['TriGeneralPNE'].= "Compagnon ".$_SESSION['TriCompagnon'].",";}
					elseif($_SESSION['TriCompagnon']=="ASC"){$_SESSION['TriCompagnon']="DESC";$_SESSION['TriGeneralPNE'].= "Compagnon ".$_SESSION['TriCompagnon'].",";}
					else{$_SESSION['TriCompagnon']="";}
				}
				if($_GET['Tri']=="NumEIC"){
					$_SESSION['TriGeneralPNE']= str_replace("NumEIC ASC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("NumEIC DESC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("NumEIC ASC","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("NumEIC DESC","",$_SESSION['TriGeneralPNE']);
					if($_SESSION['TriNumEIC']==""){$_SESSION['TriNumEIC']="ASC";$_SESSION['TriGeneralPNE'].= "NumEIC ".$_SESSION['TriNumEIC'].",";}
					elseif($_SESSION['TriNumEIC']=="ASC"){$_SESSION['TriNumEIC']="DESC";$_SESSION['TriGeneralPNE'].= "NumEIC ".$_SESSION['TriNumEIC'].",";}
					else{$_SESSION['TriNumEIC']="";}
				}
				if($_GET['Tri']=="Vacation"){
					$_SESSION['TriGeneralPNE']= str_replace("Vacation ASC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Vacation DESC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Vacation ASC","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Vacation DESC","",$_SESSION['TriGeneralPNE']);
					if($_SESSION['TriVacationPNE']==""){$_SESSION['TriVacationPNE']="ASC";$_SESSION['TriGeneralPNE'].= "Vacation ".$_SESSION['TriVacationPNE'].",";}
					elseif($_SESSION['TriVacationPNE']=="ASC"){$_SESSION['TriVacationPNE']="DESC";$_SESSION['TriGeneralPNE'].= "Vacation ".$_SESSION['TriVacationPNE'].",";}
					else{$_SESSION['TriVacationPNE']="";}
				}
				if($_GET['Tri']=="Commentaire"){
					$_SESSION['TriGeneralPNE']= str_replace("Commentaire ASC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Commentaire DESC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Commentaire ASC","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Commentaire DESC","",$_SESSION['TriGeneralPNE']);
					if($_SESSION['TriCommentairePNE']==""){$_SESSION['TriCommentairePNE']="ASC";$_SESSION['TriGeneralPNE'].= "Commentaire ".$_SESSION['TriCommentairePNE'].",";}
					elseif($_SESSION['TriCommentairePNE']=="ASC"){$_SESSION['TriCommentairePNE']="DESC";$_SESSION['TriGeneralPNE'].= "Commentaire ".$_SESSION['TriCommentairePNE'].",";}
					else{$_SESSION['TriCommentairePNE']="";}
				}
				if($_GET['Tri']=="Createur"){
					$_SESSION['TriGeneralPNE']= str_replace("Createur ASC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Createur DESC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Createur ASC","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("Createur DESC","",$_SESSION['TriGeneralPNE']);
					if($_SESSION['TriId_CreateurPNE']==""){$_SESSION['TriId_CreateurPNE']="ASC";$_SESSION['TriGeneralPNE'].= "Createur ".$_SESSION['TriId_CreateurPNE'].",";}
					elseif($_SESSION['TriId_CreateurPNE']=="ASC"){$_SESSION['TriId_CreateurPNE']="DESC";$_SESSION['TriGeneralPNE'].= "Createur ".$_SESSION['TriId_CreateurPNE'].",";}
					else{$_SESSION['TriId_CreateurPNE']="";}
				}
				if($_GET['Tri']=="DateInter"){
					$_SESSION['TriGeneralPNE']= str_replace("DateIntervention ASC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("DateIntervention DESC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("DateIntervention ASC","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("DateIntervention DESC","",$_SESSION['TriGeneralPNE']);
					if($_SESSION['TriDateInterventionPNE']==""){$_SESSION['TriDateInterventionPNE']="ASC";$_SESSION['TriGeneralPNE'].= "DateIntervention ".$_SESSION['TriDateInterventionPNE'].",";}
					elseif($_SESSION['TriDateInterventionPNE']=="ASC"){$_SESSION['TriDateInterventionPNE']="DESC";$_SESSION['TriGeneralPNE'].= "DateIntervention ".$_SESSION['TriDateInterventionPNE'].",";}
					else{$_SESSION['TriDateInterventionPNE']="";}
				}
				if($_GET['Tri']=="NbRetouche"){
					$_SESSION['TriGeneralPNE']= str_replace("NbRetouche ASC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("NbRetouche DESC,","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("NbRetouche ASC","",$_SESSION['TriGeneralPNE']);
					$_SESSION['TriGeneralPNE']= str_replace("NbRetouche DESC","",$_SESSION['TriGeneralPNE']);
					if($_SESSION['TriNbRetouche']==""){$_SESSION['TriNbRetouche']="ASC";$_SESSION['TriGeneralPNE'].= "NbRetouche ".$_SESSION['TriNbRetouche'].",";}
					elseif($_SESSION['TriNbRetouche']=="ASC"){$_SESSION['TriNbRetouche']="DESC";$_SESSION['TriGeneralPNE'].= "NbRetouche ".$_SESSION['TriNbRetouche'].",";}
					else{$_SESSION['TriNbRetouche']="";}
				}
			}
			
				$reqAnalyse="SELECT sp_pne.Id ";
				$req2="SELECT sp_pne.Id,sp_pne.NumFormA,sp_pne.MSN,sp_pne.NbRetouche,sp_pne.NumEIC,sp_pne.DateIntervention,sp_pne.Vacation,sp_pne.DateCreation,sp_pne.Poste,";
				$req2.="(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=sp_pne.Id_Pole) AS Pole, ";
				$req2.="(SELECT sp_zonedetravail.Libelle FROM sp_zonedetravail WHERE sp_zonedetravail.Id=sp_pne.Id_Zone) AS Zone,sp_pne.Commentaire, ";
				$req2.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_pne.Id_Createur) AS Createur, ";
				$req2.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_pne.Id_Compagnon) AS Compagnon ";
				$req="FROM sp_pne ";
				$req.="WHERE ";
			
				if($_SESSION['FormA2']<>""){
					$tab = explode(";",$_SESSION['FormA2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_pne.NumFormA='".addslashes($valeur)."' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['Pole2']<>""){
					$tab = explode(";",$_SESSION['Pole2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_pne.Id_Pole=".$valeur." OR ";
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
							$req.="sp_pne.Poste='".addslashes($valeur)."' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['MSN_PNE2']<>""){
					$tab = explode(";",$_SESSION['MSN_PNE2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_pne.MSN=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['Zone_PNE2']<>""){
					$tab = explode(";",$_SESSION['Zone_PNE2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_pne.Id_Zone=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['Compagnon2']<>""){
					$tab = explode(";",$_SESSION['Compagnon2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_pne.Id_Compagnon=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['NumEIC2']<>""){
					$tab = explode(";",$_SESSION['NumEIC2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_pne.NumEIC=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['VacationPNE2']<>""){
					$tab = explode(";",$_SESSION['VacationPNE2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_pne.Vacation='".$valeur."' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['Id_CreateurPNE2']<>""){
					$tab = explode(";",$_SESSION['Id_CreateurPNE2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_pne.Id_Createur=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['SansDatePNE2']=="oui"){
					$req.=" ( ";
					$req.="sp_pne.DateIntervention <= '0001-01-01' OR ";
				}
				if($_SESSION['DateDebutPNE2']<>"" || $_SESSION['DateFinPNE2']<>""){
					$req.=" ( ";
					if($_SESSION['DateDebutPNE2']<>""){
						$req.="sp_pne.DateIntervention >= '". TrsfDate_($_SESSION['DateDebutPNE2'])."' ";
						$req.=" AND ";
					}
					if($_SESSION['DateFinPNE2']<>""){
						$req.="sp_pne.DateIntervention <= '". TrsfDate_($_SESSION['DateFinPNE2'])."' ";
						$req.=" ";
					}
					if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
					$req.=" ) ";
				}
				if(substr($req,strlen($req)-3)== "OR "){$req=substr($req,0,-3);}
				if($_SESSION['SansDatePNE2']=="oui"){
					$req.=" ) ";
				}
				if($_SESSION['SansDatePNE2']=="oui" || $_SESSION['DateDebutPNE2']<>"" || $_SESSION['DateFinPNE2']<>""){
					$req.=" AND ";
				}
				if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
				if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}
				
				$result=mysqli_query($bdd,$reqAnalyse.$req);
				$nbResulta=mysqli_num_rows($result);
				
				if($_SESSION['TriGeneralPNE']<>""){
					$req.="ORDER BY ".substr($_SESSION['TriGeneralPNE'],0,-1);
				}

				$nombreDePages=ceil($nbResulta/200);
				if(isset($_GET['Page2'])){$_SESSION['Page2']=$_GET['Page2'];}
				$req3=" LIMIT ".$_SESSION['Page2'].",200";
				
				$result=mysqli_query($bdd,$req2.$req.$req3);
				$nbResulta=mysqli_num_rows($result);
		?>
		<tr>
			<td align="center" style="font-size:14px;">
				<?php
						$nbPage=0;
						if($_SESSION['Page2']>1){echo "<b> <a style='color:#00599f;' href='Liste_PNE.php?Page=0'><<</a> </b>";}
						$valeurDepart=1;
						if($_SESSION['Page2']<=5){
							$valeurDepart=1;
						}
						elseif($_SESSION['Page2']>=($nombreDePages-6)){
							$valeurDepart=$nombreDePages-6;
						}
						else{
							$valeurDepart=$_SESSION['Page2']-5;
						}
						for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
							if($i<=$nombreDePages){
								if($i==($_SESSION['Page2']+1)){
									echo "<b> [ ".$i." ] </b>"; 
								}	
								else{
									echo "<b> <a style='color:#00599f;' href='Liste_PNE.php?Page=".($i-1)."'>".$i."</a> </b>";
								}
							}
						}
						if($_SESSION['Page2']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_PNE.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
				?>
			</td>
		</tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr>
					<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_PNE.php?Tri=MSN">MSN<?php if($_SESSION['TriMSN_PNE']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriMSN_PNE']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_PNE.php?Tri=FormA">N° Form A<?php if($_SESSION['TriFormA']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriFormA']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="4%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_PNE.php?Tri=Poste">Poste<?php if($_SESSION['TriPoste']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriPoste']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="4%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_PNE.php?Tri=Pole">Pole<?php if($_SESSION['TriPole']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriPole']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="4%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_PNE.php?Tri=Zone">Zone<?php if($_SESSION['TriZone_PNE']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriZone_PNE']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_PNE.php?Tri=Compagnon">Compagnon<?php if($_SESSION['TriCompagnon']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriCompagnon']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="4%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_PNE.php?Tri=NbRetouche">Nb retouche<?php if($_SESSION['TriNbRetouche']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriNbRetouche']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="4%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_PNE.php?Tri=NumEIC">N° d'eic<?php if($_SESSION['TriNumEIC']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriNumEIC']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="7%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_PNE.php?Tri=DateInter">Date intervention<?php if($_SESSION['TriDateInterventionPNE']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriDateInterventionPNE']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_PNE.php?Tri=Vacation">Vacation<?php if($_SESSION['TriVacationPNE']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriVacationPNE']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="15%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_PNE.php?Tri=Commentaire">Commentaire<?php if($_SESSION['TriCommentairePNE']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriCommentairePNE']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_PNE.php?Tri=Createur">Créateur<?php if($_SESSION['TriId_CreateurPNE']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriId_CreateurPNE']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;"></td>
					<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;"></td>
				</tr>
				<tr>
					<?php

							
							if ($nbResulta>0){
								$couleur="#ffffff";
								while($row=mysqli_fetch_array($result)){
									$vacation="";
									if($row['Vacation']=="J"){$vacation="Jour";}
									elseif($row['Vacation']=="S"){$vacation="Soir";}
									elseif($row['Vacation']=="N"){$vacation="Nuit";}
									elseif($row['Vacation']=="VSD Jour"){$vacation="VSD Jour";}
									elseif($row['Vacation']=="VSD Nuit"){$vacation="VSD Nuit";}
									
									$DateIntervention = "";
									if($row['DateIntervention']>"0001-01-01"){$DateIntervention = $row['DateIntervention'];}								
									?>
										<tr bgcolor="<?php echo $couleur;?>">
											<td width="2%" style="text-align:center;">&nbsp;<?php echo $row['MSN'];?></td>
											<td width="6%" style="text-align:center;"><?php echo $row['NumFormA'];?></td>
											<td width="4%" style="text-align:center;"><?php echo $row['Poste'];?></td>
											<td width="4%" style="text-align:center;"><?php echo $row['Pole'];?></td>
											<td width="6%" style="text-align:center;"><?php echo $row['Zone'];?></td>
											<td width="10%" style="text-align:center;"><?php echo $row['Compagnon'];?></td>
											<td width="10%" style="text-align:center;"><?php echo $row['NbRetouche'];?></td>
											<td width="10%" style="text-align:center;"><?php echo $row['NumEIC'];?></td>
											<td width="7%" style="text-align:center;"><?php echo $DateIntervention;?></td>
											<td width="6%" style="text-align:center;"><?php echo $vacation;?></td>
											<td width="6%" style="text-align:center;"><?php echo nl2br($row['Commentaire']);?></td>
											<td width="8%" style="text-align:center;"><?php echo $row['Createur'];?></td>
											<td width="2%" align="center">
												<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>,<?php echo $_SESSION['Id_PersonneSP'];?>)">
												<img src='../../../Images/Modif.gif' border='0' alt='Modifier' title='Modifier'>
												</a>
											</td>
											<td width="2%" align="center">
											<a href="javascript:OuvreFenetreSuppr(<?php echo $row['Id']; ?>,<?php echo $_SESSION['Id_PersonneSP'];?>)">
											<img src='../../../Images/Suppression.gif' border='0' alt='Supprimer' title='Supprimer'>
											</a>
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
					if($_SESSION['Page2']>1){echo "<b> <a style='color:#00599f;' href='Liste_PNE.php?Page=0'><<</a> </b>";}
					$valeurDepart=1;
					if($_SESSION['Page2']<=5){
						$valeurDepart=1;
					}
					elseif($_SESSION['Page2']>=($nombreDePages-6)){
						$valeurDepart=$nombreDePages-6;
					}
					else{
						$valeurDepart=$_SESSION['Page2']-5;
					}
					for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
						if($i<=$nombreDePages){
							if($i==($_SESSION['Page2']+1)){
								echo "<b> [ ".$i." ] </b>"; 
							}	
							else{
								echo "<b> <a style='color:#00599f;' href='Liste_PNE.php?Page=".($i-1)."'>".$i."</a> </b>";
							}
						}
					}
					if($_SESSION['Page2']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_PNE.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
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
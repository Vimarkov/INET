<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout_Critere(){
			var w=window.open("Ajout_CritereAM.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=800,height=340");
			w.focus();
		}
		function Suppr_Critere(critere,valeur){
			var w=window.open("Ajout_CritereAM.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=200");
			w.focus();
		}
		function OuvreFenetreAjout(){
			var w=window.open("Ajout_AM.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=900,height=350");
			w.focus();
			}
		function OuvreFenetreModif(Id){
			var w=window.open("Ajout_AM.php?Mode=M&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=900,height=350");
			w.focus();
			}
		function OuvreFenetreSuppr(Id){
			if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
			var w=window.open("Ajout_AM.php?Mode=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,,width=60,height=40");
			w.focus();
			}
		}
		function Excel(){
			var w=window.open("ExtractAM.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
		}
		function OuvreFenetreModifDossier(Id){
			var w=window.open("Modif_Dossier.php?Mode=M&Id="+Id,"PageDossier","status=no,menubar=no,scrollbars=yes,width=1100,height=650");
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
require("../../../Menu.php");
require("../../Fonctions.php");

if($_POST){
	if(isset($_POST['Recherche_RAZ'])){
		$_SESSION['AMMSN']="";
		$_SESSION['AMNumAMNC']="";
		$_SESSION['AMNumOF']="";
		$_SESSION['AMOrigineAM']="";
		$_SESSION['AMImputation']="";
		$_SESSION['AMNumDERO']="";
		$_SESSION['AMMoteur']="";
		$_SESSION['AMNacelle']="";
		$_SESSION['AMMoment']="";
		$_SESSION['AMDu']="";
		$_SESSION['AMAu']="";
		$_SESSION['AMRecurrence']="";
		$_SESSION['AMStatut']="";
		$_SESSION['AMLocalisation']="";
		$_SESSION['AMTypeDefaut']="";
		$_SESSION['AMProduitImpacte']="";
		$_SESSION['AMCote']="";
		$_SESSION['AMActionCurative']="";
		
		
		$_SESSION['AMMSN2']="";
		$_SESSION['AMNumAMNC2']="";
		$_SESSION['AMNumOF2']="";
		$_SESSION['AMOrigineAM2']="";
		$_SESSION['AMImputation2']="";
		$_SESSION['AMNumDERO2']="";
		$_SESSION['AMMoteur2']="";
		$_SESSION['AMNacelle2']="";
		$_SESSION['AMMoment2']="";
		$_SESSION['AMDu2']="";
		$_SESSION['AMAu2']="";
		$_SESSION['AMRecurrence2']="";
		$_SESSION['AMStatut2']="";
		$_SESSION['AMLocalisation2']="";
		$_SESSION['AMTypeDefaut2']="";
		$_SESSION['AMProduitImpacte2']="";
		$_SESSION['AMCote2']="";
		$_SESSION['AMActionCurative2']="";

		$_SESSION['AMModeFiltre']="";
		$_SESSION['AMPage']="0";
	}
	elseif(isset($_POST['BtnRechercher'])){
		$_SESSION['AMModeFiltre']="oui";
	}
	elseif(isset($_POST['Tri_RAZ'])){
		$_SESSION['TriAMMSN']="";
		$_SESSION['TriAMNumAMNC']="";
		$_SESSION['TriAMNumOF']="";
		$_SESSION['TriAMOrigineAM']="";
		$_SESSION['TriAMImputation']="";
		$_SESSION['TriAMNumDERO']="";
		$_SESSION['TriAMMoteur']="";
		$_SESSION['TriAMNacelle']="";
		$_SESSION['TriAMMomentDetection']="";
		$_SESSION['TriAMRecurrence']="";
		$_SESSION['TriAMStatut']="";
		$_SESSION['TriAMDescription']="";
		$_SESSION['TriAMDate']="";
		$_SESSION['TriAMLocalisation']="";
		$_SESSION['TriAMTypeDefaut']="";
		$_SESSION['TriAMCote']="";
		$_SESSION['TriAMActionCurative']="";
		$_SESSION['TriAMGeneral']="";
	}
}

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Liste_AM.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Liste des AM</td>
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
			if($_SESSION['AMActionCurative']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Action curative : ".$_SESSION['AMActionCurative']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMCote']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Côté : ".$_SESSION['AMCote']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMDu']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Du : ".$_SESSION['AMDu']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMAu']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Au : ".$_SESSION['AMAu']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMMSN']<>""){
				echo "<tr>";
				echo "<td>&nbsp; MSN : ".$_SESSION['AMMSN']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMNumAMNC']<>""){
				echo "<tr>";
				echo "<td>&nbsp; N° AM : ".$_SESSION['AMNumAMNC']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMNumOF']<>""){
				echo "<tr>";
				echo "<td>&nbsp; N° OF : ".$_SESSION['AMNumOF']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMImputation']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Imputation : ".$_SESSION['AMImputation']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMOrigineAM']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Origine de l'AM : ".$_SESSION['AMOrigineAM']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMNumDERO']<>""){
				echo "<tr>";
				echo "<td>&nbsp; N° DERO : ".$_SESSION['AMNumDERO']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMLocalisation']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Localisation : ".$_SESSION['AMLocalisation']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMProduitImpacte']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Produit impacté : ".$_SESSION['AMProduitImpacte']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMMoment']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Moment de détection : ".$_SESSION['AMMoment']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMRecurrence']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Récurrence : ".$_SESSION['AMRecurrence']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMStatut']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Statut : ".$_SESSION['AMStatut']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMTypeDefaut']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Type de défaut : ".$_SESSION['AMTypeDefaut']."</td>";
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
		<td align="left">
			<table align="left" style="width:100%;">
				<tr>
				<td align="center">
				<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;Ajouter une AM&nbsp;</a>
				</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<?php
			if(isset($_GET['Tri'])){
				if($_GET['Tri']=="AMMSN"){
					$_SESSION['TriAMGeneral']= str_replace("MSN ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("MSN DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("MSN ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("MSN DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMMSN']==""){$_SESSION['TriAMMSN']="ASC";$_SESSION['TriAMGeneral'].= "MSN ".$_SESSION['TriAMMSN'].",";}
					elseif($_SESSION['TriAMMSN']=="ASC"){$_SESSION['TriAMMSN']="DESC";$_SESSION['TriAMGeneral'].= "MSN ".$_SESSION['TriAMMSN'].",";}
					else{$_SESSION['TriAMMSN']="";}
				}
				if($_GET['Tri']=="AMNumAMNC"){
					$_SESSION['TriAMGeneral']= str_replace("NumAMNC ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("NumAMNC DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("NumAMNC ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("NumAMNC DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMNumAMNC']==""){$_SESSION['TriAMNumAMNC']="ASC";$_SESSION['TriAMGeneral'].= "NumAMNC ".$_SESSION['TriAMNumAMNC'].",";}
					elseif($_SESSION['TriAMNumAMNC']=="ASC"){$_SESSION['TriAMNumAMNC']="DESC";$_SESSION['TriAMGeneral'].= "NumAMNC ".$_SESSION['TriAMNumAMNC'].",";}
					else{$_SESSION['TriAMNumAMNC']="";}
				}
				if($_GET['Tri']=="AMNumDERO"){
					$_SESSION['TriAMGeneral']= str_replace("NumDERO ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("NumDERO DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("NumDERO ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("NumDERO DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMNumDERO']==""){$_SESSION['TriAMNumDERO']="ASC";$_SESSION['TriAMGeneral'].= "NumDERO ".$_SESSION['TriAMNumDERO'].",";}
					elseif($_SESSION['TriAMNumDERO']=="ASC"){$_SESSION['TriAMNumDERO']="DESC";$_SESSION['TriAMGeneral'].= "NumDERO ".$_SESSION['TriAMNumDERO'].",";}
					else{$_SESSION['TriAMNumDERO']="";}
				}
				if($_GET['Tri']=="AMOrigineAM"){
					$_SESSION['TriAMGeneral']= str_replace("OrigineAM ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("OrigineAM DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("OrigineAM ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("OrigineAM DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMOrigineAM']==""){$_SESSION['TriAMOrigineAM']="ASC";$_SESSION['TriAMGeneral'].= "OrigineAM ".$_SESSION['TriAMOrigineAM'].",";}
					elseif($_SESSION['TriAMOrigineAM']=="ASC"){$_SESSION['TriAMOrigineAM']="DESC";$_SESSION['TriAMGeneral'].= "OrigineAM ".$_SESSION['TriAMOrigineAM'].",";}
					else{$_SESSION['TriAMOrigineAM']="";}
				}
				if($_GET['Tri']=="AMImputation"){
					$_SESSION['TriAMGeneral']= str_replace("ImputationAAA ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("ImputationAAA DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("ImputationAAA ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("ImputationAAA DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMImputation']==""){$_SESSION['TriAMImputation']="ASC";$_SESSION['TriAMGeneral'].= "ImputationAAA ".$_SESSION['TriAMImputation'].",";}
					elseif($_SESSION['TriAMImputation']=="ASC"){$_SESSION['TriAMImputation']="DESC";$_SESSION['TriAMGeneral'].= "ImputationAAA ".$_SESSION['TriAMImputation'].",";}
					else{$_SESSION['TriAMImputation']="";}
				}
				if($_GET['Tri']=="AMNumDERO"){
					$_SESSION['TriAMGeneral']= str_replace("NumDERO ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("NumDERO DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("NumDERO ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("NumDERO DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMNumDERO']==""){$_SESSION['TriAMNumDERO']="ASC";$_SESSION['TriAMGeneral'].= "NumDERO ".$_SESSION['TriAMNumDERO'].",";}
					elseif($_SESSION['TriAMNumDERO']=="ASC"){$_SESSION['TriAMNumDERO']="DESC";$_SESSION['TriAMGeneral'].= "NumDERO ".$_SESSION['TriAMNumDERO'].",";}
					else{$_SESSION['TriAMNumDERO']="";}
				}
				if($_GET['Tri']=="AMLocalisation"){
					$_SESSION['TriAMGeneral']= str_replace("Localisation ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Localisation DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Localisation ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Localisation DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMLocalisation']==""){$_SESSION['TriAMLocalisation']="ASC";$_SESSION['TriAMGeneral'].= "Localisation ".$_SESSION['TriAMLocalisation'].",";}
					elseif($_SESSION['TriAMLocalisation']=="ASC"){$_SESSION['TriAMLocalisation']="DESC";$_SESSION['TriAMGeneral'].= "Localisation ".$_SESSION['TriAMLocalisation'].",";}
					else{$_SESSION['TriAMLocalisation']="";}
				}
				if($_GET['Tri']=="AMTypeDefaut"){
					$_SESSION['TriAMGeneral']= str_replace("TypeDefaut ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("TypeDefaut DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("TypeDefaut ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("TypeDefaut DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMTypeDefaut']==""){$_SESSION['TriAMTypeDefaut']="ASC";$_SESSION['TriAMGeneral'].= "TypeDefaut ".$_SESSION['TriAMTypeDefaut'].",";}
					elseif($_SESSION['TriAMTypeDefaut']=="ASC"){$_SESSION['TriAMTypeDefaut']="DESC";$_SESSION['TriAMGeneral'].= "TypeDefaut ".$_SESSION['TriAMTypeDefaut'].",";}
					else{$_SESSION['TriAMTypeDefaut']="";}
				}
				if($_GET['Tri']=="AMProduitImpacte"){
					$_SESSION['TriAMGeneral']= str_replace("ProduitImpacte ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("ProduitImpacte DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("ProduitImpacte ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("ProduitImpacte DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMProduitImpacte']==""){$_SESSION['TriAMProduitImpacte']="ASC";$_SESSION['TriAMGeneral'].= "ProduitImpacte ".$_SESSION['TriAMProduitImpacte'].",";}
					elseif($_SESSION['TriAMProduitImpacte']=="ASC"){$_SESSION['TriAMProduitImpacte']="DESC";$_SESSION['TriAMGeneral'].= "ProduitImpacte ".$_SESSION['TriAMProduitImpacte'].",";}
					else{$_SESSION['TriAMProduitImpacte']="";}
				}
				if($_GET['Tri']=="AMNacelle"){
					$_SESSION['TriAMGeneral']= str_replace("Nacelle ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Nacelle DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Nacelle ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Nacelle DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMNacelle']==""){$_SESSION['TriAMNacelle']="ASC";$_SESSION['TriAMGeneral'].= "Nacelle ".$_SESSION['TriAMNacelle'].",";}
					elseif($_SESSION['TriAMNacelle']=="ASC"){$_SESSION['TriAMNacelle']="DESC";$_SESSION['TriAMGeneral'].= "Nacelle ".$_SESSION['TriAMNacelle'].",";}
					else{$_SESSION['TriAMNacelle']="";}
				}
				if($_GET['Tri']=="AMMoment"){
					$_SESSION['TriAMGeneral']= str_replace("Moment ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Moment DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Moment ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Moment DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMMomentDetection']==""){$_SESSION['TriAMMomentDetection']="ASC";$_SESSION['TriAMGeneral'].= "Moment ".$_SESSION['TriAMMomentDetection'].",";}
					elseif($_SESSION['TriAMMomentDetection']=="ASC"){$_SESSION['TriAMMomentDetection']="DESC";$_SESSION['TriAMGeneral'].= "Moment ".$_SESSION['TriAMMomentDetection'].",";}
					else{$_SESSION['TriAMMomentDetection']="";}
				}
				if($_GET['Tri']=="AMStatut"){
					$_SESSION['TriAMGeneral']= str_replace("Statut ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Statut DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Statut ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Statut DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMMomentDetection']==""){$_SESSION['TriAMMomentDetection']="ASC";$_SESSION['TriAMGeneral'].= "Statut ".$_SESSION['TriAMMomentDetection'].",";}
					elseif($_SESSION['TriAMMomentDetection']=="ASC"){$_SESSION['TriAMMomentDetection']="DESC";$_SESSION['TriAMGeneral'].= "Statut ".$_SESSION['TriAMMomentDetection'].",";}
					else{$_SESSION['TriAMMomentDetection']="";}
				}
				if($_GET['Tri']=="AMRecurrence"){
					$_SESSION['TriAMGeneral']= str_replace("Recurrence ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Recurrence DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Recurrence ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Recurrence DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMRecurrence']==""){$_SESSION['TriAMRecurrence']="ASC";$_SESSION['TriAMGeneral'].= "Recurrence ".$_SESSION['TriAMRecurrence'].",";}
					elseif($_SESSION['TriAMRecurrence']=="ASC"){$_SESSION['TriAMRecurrence']="DESC";$_SESSION['TriAMGeneral'].= "Recurrence ".$_SESSION['TriAMRecurrence'].",";}
					else{$_SESSION['TriAMRecurrence']="";}
				}
				if($_GET['Tri']=="Date"){
					$_SESSION['TriAMGeneral']= str_replace("DateCreation ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("DateCreation DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("DateCreation ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("DateCreation DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMDate']==""){$_SESSION['TriAMDate']="ASC";$_SESSION['TriAMGeneral'].= "DateCreation ".$_SESSION['TriAMDate'].",";}
					elseif($_SESSION['TriAMDate']=="ASC"){$_SESSION['TriAMDate']="DESC";$_SESSION['TriAMGeneral'].= "DateCreation ".$_SESSION['TriAMDate'].",";}
					else{$_SESSION['TriAMDate']="";}
				}
				if($_GET['Tri']=="Cote"){
					$_SESSION['TriAMGeneral']= str_replace("Cote ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Cote DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Cote ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Cote DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMCote']==""){$_SESSION['TriAMCote']="ASC";$_SESSION['TriAMGeneral'].= "Cote ".$_SESSION['TriAMCote'].",";}
					elseif($_SESSION['TriAMCote']=="ASC"){$_SESSION['TriAMCote']="DESC";$_SESSION['TriAMGeneral'].= "Cote ".$_SESSION['TriAMCote'].",";}
					else{$_SESSION['TriAMCote']="";}
				}
				if($_GET['Tri']=="ActionCurative"){
					$_SESSION['TriAMGeneral']= str_replace("ActionCurative ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("ActionCurative DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("ActionCurative ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("ActionCurative DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMActionCurative']==""){$_SESSION['TriAMActionCurative']="ASC";$_SESSION['TriAMGeneral'].= "ActionCurative ".$_SESSION['TriAMActionCurative'].",";}
					elseif($_SESSION['TriAMActionCurative']=="ASC"){$_SESSION['TriAMActionCurative']="DESC";$_SESSION['TriAMGeneral'].= "ActionCurative ".$_SESSION['TriAMActionCurative'].",";}
					else{$_SESSION['TriAMActionCurative']="";}
				}
			}
			$reqAnalyse="SELECT sp_atram.Id ";
			$req2="SELECT Id,MSN,NumOF,NumDERO,OrigineAM,Recurrence,NumAMNC,Id_Localisation,Id_TypeDefaut,Statut,DateCreation,Id_ProduitImpacte, ";
			$req2.="(SELECT Libelle FROM sp_atrmomentdetection WHERE sp_atrmomentdetection.Id=sp_atram.Id_MomentDetection) AS Moment, ";
			$req2.="(SELECT Libelle FROM sp_atrimputation WHERE sp_atrimputation.Id=sp_atram.Id_Imputation) AS ImputationAAA, ";
			$req2.="(SELECT Libelle FROM sp_atrlocalisation WHERE sp_atrlocalisation.Id=sp_atram.Id_Localisation) AS Localisation, ";
			$req2.="(SELECT Libelle FROM sp_atrproduitimpacte WHERE sp_atrproduitimpacte.Id=sp_atram.Id_ProduitImpacte) AS ProduitImpacte, ";
			$req2.="(SELECT Libelle FROM sp_atrtypedefaut WHERE sp_atrtypedefaut.Id=sp_atram.Id_TypeDefaut) AS TypeDefaut, ";
			$req2.="(SELECT Libelle FROM sp_atrcote WHERE sp_atrcote.Id=sp_atram.Id_Cote) AS Cote, ";
			$req2.="(SELECT Libelle FROM sp_atractioncurative WHERE sp_atractioncurative.Id=sp_atram.Id_ActionCurative) AS ActionCurative, ";
			$req2.="(SELECT Id FROM sp_atrot WHERE sp_atrot.OrdreMontage=sp_atram.NumOF AND sp_atrot.Supprime=0 LIMIT 1) AS Id_Dossier ";
			$req="FROM sp_atram WHERE Id_Prestation=463 AND ";
			if($_SESSION['AMMSN2']<>""){
				$tab = explode(";",$_SESSION['AMMSN2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="MSN=".$valeur." OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['AMNumAMNC2']<>""){
				$tab = explode(";",$_SESSION['AMNumAMNC2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="NumAMNC='".$valeur."' OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['AMNumOF2']<>""){
				$tab = explode(";",$_SESSION['AMNumOF2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="NumOF='".$valeur."' OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['AMOrigineAM2']<>""){
				$tab = explode(";",$_SESSION['AMOrigineAM2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="OrigineAM='".$valeur."' OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['AMImputation2']<>""){
				$tab = explode(";",$_SESSION['AMImputation2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="Id_Imputation=".$valeur." OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['AMNumDERO2']<>""){
				$tab = explode(";",$_SESSION['AMNumDERO2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="NumDERO='".$valeur."' OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['AMLocalisation2']<>""){
				$tab = explode(";",$_SESSION['AMLocalisation2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="Id_Localisation=".$valeur." OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['AMTypeDefaut2']<>""){
				$tab = explode(";",$_SESSION['AMTypeDefaut2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="Id_TypeDefaut=".$valeur." OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['AMProduitImpacte2']<>""){
				$tab = explode(";",$_SESSION['AMProduitImpacte2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="Id_ProduitImpacte=".$valeur." OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['AMMoment2']<>""){
				$tab = explode(";",$_SESSION['AMMoment2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="Id_MomentDetection=".$valeur." OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['AMRecurrence2']<>""){
				$tab = explode(";",$_SESSION['AMRecurrence2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="Recurrence=".$valeur." OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['AMStatut2']<>""){
				$tab = explode(";",$_SESSION['AMStatut2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="Statut='".$valeur."' OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['AMCote2']<>""){
				$tab = explode(";",$_SESSION['AMCote2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="Id_Cote=".$valeur." OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['AMActionCurative2']<>""){
				$tab = explode(";",$_SESSION['AMActionCurative2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="Id_ActionCurative=".$valeur." OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['AMDu2']<>"" || $_SESSION['AMAu2']<>""){
				$req.=" ( ";
				if($_SESSION['AMDu2']<>""){
					$req.="DateCreation >= '". TrsfDate_($_SESSION['AMDu2'])."' ";
					$req.=" AND ";
				}
				if($_SESSION['AMAu2']<>""){
					$req.="DateCreation <= '". TrsfDate_($_SESSION['AMAu2'])."' ";
					$req.=" ";
				}
				if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
				$req.=" ) ";
			}
			if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
			if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}

			$result=mysqli_query($bdd,$reqAnalyse.$req);
			$nbResulta=mysqli_num_rows($result);
			
			if($_SESSION['TriAMGeneral']<>""){
				$req.="ORDER BY ".substr($_SESSION['TriAMGeneral'],0,-1);
			}

			$nombreDePages=ceil($nbResulta/100);
			if(isset($_GET['Page'])){$_SESSION['AMPage']=$_GET['Page'];}
			$req3=" LIMIT ".($_SESSION['AMPage']*100).",100";

			$result=mysqli_query($bdd,$req2.$req.$req3);
			$nbResulta=mysqli_num_rows($result);
		?>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				$nbPage=0;
				if($_SESSION['AMPage']>1){echo "<b> <a style='color:#00599f;' href='Liste_AM.php?Page=0'><<</a> </b>";}
				$valeurDepart=1;
				if($_SESSION['AMPage']<=5){
					$valeurDepart=1;
				}
				elseif($_SESSION['AMPage']>=($nombreDePages-6)){
					$valeurDepart=$nombreDePages-6;
				}
				else{
					$valeurDepart=$_SESSION['AMPage']-5;
				}
				for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
					if($i<=$nombreDePages){
						if($i==($_SESSION['AMPage']+1)){
							echo "<b> [ ".$i." ] </b>"; 
						}	
						else{
							echo "<b> <a style='color:#00599f;' href='Liste_AM.php?Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($_SESSION['AMPage']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_AM.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
		<table cellpadding="0" cellspacing="0" align="left" class="GeneralInfo" style="width:100%;">
			<tr bgcolor="#00325F">
				<td class="EnTeteTableauCompetences" width="6%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=AMMSN">MSN<?php if($_SESSION['TriAMMSN']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMMSN']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="6%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=AMNumAMNC">N° AM<?php if($_SESSION['TriAMNumAMNC']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMNumAMNC']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="6%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=AMNumOF">N° OF<?php if($_SESSION['TriAMNumOF']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMNumOF']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="12%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=AMOrigineAM">Origine AM<?php if($_SESSION['TriAMOrigineAM']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMOrigineAM']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="12%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=Date">Date AM<?php if($_SESSION['TriAMDate']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMDate']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="12%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=AMImputation">Imputation<?php if($_SESSION['TriAMImputation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMImputation']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="6%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=AMNumDERO">N° DERO<?php if($_SESSION['TriAMNumDERO']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMNumDERO']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="6%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=AMLocalisation">Localisation<?php if($_SESSION['TriAMLocalisation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMLocalisation']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="6%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=AMCote">Côté<?php if($_SESSION['TriAMCote']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMCote']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="12%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=AMMoment">Moment de détection<?php if($_SESSION['TriAMMomentDetection']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMMomentDetection']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="5%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=AMRecurrence">Récurrence<?php if($_SESSION['TriAMRecurrence']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMRecurrence']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="12%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=AMTypeDefaut">Type défaut<?php if($_SESSION['TriAMTypeDefaut']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMTypeDefaut']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="6%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=AMActionCurative">Action<br>curative<?php if($_SESSION['TriAMActionCurative']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMActionCurative']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=AMStatut">Statut<?php if($_SESSION['TriAMStatut']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMStatut']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
			</tr>
			<?php
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						if($couleur=="#ffffff"){$couleur="#E1E1D7";}
						else{$couleur="#ffffff";}
						$ferme="";
						if($row['Statut']=="Fermée"){$ferme="style='background-color:#9fff1f;'";}
						?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="6%"><span <?php echo $ferme; ?> >&nbsp;<?php echo $row['MSN'];?></span></td>
								<td width="6%"><span <?php echo $ferme; ?>>&nbsp;<?php echo $row['NumAMNC'];?></span></td>
								<td width="6%"><span <?php echo $ferme; ?>>
									<?php If($row['Id_Dossier']<>""){echo "<a href='javascript:OuvreFenetreModifDossier(".$row['Id_Dossier'].")'>";} ?>
									&nbsp;<?php echo $row['NumOF'];?>
									<?php If($row['Id_Dossier']<>""){echo "</a>";} ?>
									</span>
								</td>
								<td width="12%"><span <?php echo $ferme; ?>>&nbsp;<?php echo addslashes($row['OrigineAM']);?></span></td>
								<td width="12%"><span <?php echo $ferme; ?>>&nbsp;<?php echo AfficheDateFR($row['DateCreation']);?></span></td>
								<td width="12%"><span <?php echo $ferme; ?>>&nbsp;<?php echo $row['ImputationAAA']; ?></span></td>
								<td width="6%"><span <?php echo $ferme; ?>>&nbsp;<?php echo $row['NumDERO'];?></span></td>
								<td width="6%"><span <?php echo $ferme; ?>>&nbsp;<?php echo $row['Localisation'];?></span></td>
								<td width="6%"><span <?php echo $ferme; ?>>&nbsp;<?php echo $row['Cote'];?></span></td>
								<td width="12%"><span <?php echo $ferme; ?>>&nbsp;<?php echo $row['Moment'];?></span></td>
								<td width="5%"><span <?php echo $ferme; ?>>&nbsp;<?php if($row['Recurrence']==1){echo "Oui";}else{echo "Non";}?></span></td>
								<td width="12%"><span <?php echo $ferme; ?>>&nbsp;<?php echo stripslashes($row['TypeDefaut']);?></span></td>
								<td width="6%"><span <?php echo $ferme; ?>>&nbsp;<?php echo $row['ActionCurative'];?></span></td>
								<td width="10%"><span <?php echo $ferme; ?>>&nbsp;<?php echo $row['Statut'];?></span></td>
								<td width="2%" align="center">
									<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>)">
									<img src='../../../Images/Modif.gif' border='0' alt='Modifier' title='Modifier'>
									</a>
								</td>
								<td width="2%" align="center">
								<a href="javascript:OuvreFenetreSuppr(<?php echo $row['Id']; ?>)">
								<img src='../../../Images/Suppression.gif' border='0' alt='Supprimer' title='Supprimer'>
								</a>
								</td>
							</tr>
						<?php
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
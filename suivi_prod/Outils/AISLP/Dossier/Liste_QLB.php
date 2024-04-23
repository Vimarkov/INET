<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout_Critere(){
			var w=window.open("Ajout_CritereQLB.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=800,height=300");
			w.focus();
		}
		function Suppr_Critere(critere,valeur){
			var w=window.open("Ajout_CritereQLB.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=300");
			w.focus();
		}
		function OuvreFenetreAjout(){
			var w=window.open("Ajout_QLB.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=600,height=300");
			w.focus();
			}
		function OuvreFenetreModif(Id){
			var w=window.open("Ajout_QLB.php?Mode=M&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=600,height=300");
			w.focus();
			}
		function OuvreFenetreSuppr(Id){
			if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
			var w=window.open("Ajout_QLB.php?Mode=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,,width=60,height=40");
			w.focus();
			}
		}
		function Excel(){
			var w=window.open("ExtractQLB.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
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
		$_SESSION['CQLBMSN']="";
		$_SESSION['CQLBNumCQLB']="";
		$_SESSION['CQLBNumCV']="";
		$_SESSION['CQLBLocalisation']="";
		$_SESSION['CQLBImputationAAA']="";
		$_SESSION['CQLBOMAssocie']="";
		$_SESSION['CQLBAMAssociee']="";
		$_SESSION['CQLBDu']="";
		$_SESSION['CQLBAu']="";
		$_SESSION['CQLBType']="";
		$_SESSION['CQLBRecurrence']="";
		
		$_SESSION['CQLBMSN2']="";
		$_SESSION['CQLBNumCQLB2']="";
		$_SESSION['CQLBNumCV2']="";
		$_SESSION['CQLBLocalisation2']="";
		$_SESSION['CQLBImputationAAA2']="";
		$_SESSION['CQLBOMAssocie2']="";
		$_SESSION['CQLBAMAssociee2']="";
		$_SESSION['CQLBDu2']="";
		$_SESSION['CQLBAu2']="";
		$_SESSION['CQLBType2']="";
		$_SESSION['CQLBRecurrence2']="";

		$_SESSION['CQLBModeFiltre']="";
		$_SESSION['CQLBPage']="0";
	}
	elseif(isset($_POST['BtnRechercher'])){
		$_SESSION['CQLBModeFiltre']="oui";
	}
	elseif(isset($_POST['Tri_RAZ'])){
		$_SESSION['TriCQLBMSN']="";
		$_SESSION['TriCQLBNumCQLB']="";
		$_SESSION['TriCQLBNumCV']="";
		$_SESSION['TriCQLBLocalisation']="";
		$_SESSION['TriCQLBImputationAAA']="";
		$_SESSION['TriCQLBOMAssocie']="";
		$_SESSION['TriCQLBDesignation']="";
		$_SESSION['TriCQLBAMAssocie']="";
		$_SESSION['TriCQLBDate']="";
		$_SESSION['TriCQLBType']="";
		$_SESSION['TriCQLBRecurrence']="";
		$_SESSION['TriCQLBGeneral']="";
	}
}

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
if(substr($_SESSION['DroitSP'],4,1)=='1' || substr($_SESSION['DroitSP'],3,1)=='1'){
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Liste_QLB.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Liste des QLB</td>
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
			if($_SESSION['CQLBMSN']<>""){
				echo "<tr>";
				echo "<td>&nbsp; MSN : ".$_SESSION['CQLBMSN']."</td>";
				echo "</tr>";
			}
			if($_SESSION['CQLBNumCQLB']<>""){
				echo "<tr>";
				echo "<td>&nbsp; N° CQLB : ".$_SESSION['CQLBNumCQLB']."</td>";
				echo "</tr>";
			}
			if($_SESSION['CQLBNumCV']<>""){
				echo "<tr>";
				echo "<td>&nbsp; N° CV : ".$_SESSION['CQLBNumCV']."</td>";
				echo "</tr>";
			}
			if($_SESSION['CQLBLocalisation']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Localisation : ".$_SESSION['CQLBLocalisation']."</td>";
				echo "</tr>";
			}
			if($_SESSION['CQLBImputationAAA']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Imputation AAA : ".$_SESSION['CQLBImputationAAA']."</td>";
				echo "</tr>";
			}
			if($_SESSION['CQLBOMAssocie']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Ordre de montage associé : ".$_SESSION['CQLBOMAssocie']."</td>";
				echo "</tr>";
			}
			if($_SESSION['CQLBAMAssociee']<>""){
				echo "<tr>";
				echo "<td>&nbsp; AM associée : ".$_SESSION['CQLBAMAssociee']."</td>";
				echo "</tr>";
			}
			if($_SESSION['CQLBDu']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Date de début : ".$_SESSION['CQLBDu']."</td>";
				echo "</tr>";
			}
			if($_SESSION['CQLBAu']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Date de fin : ".$_SESSION['CQLBAu']."</td>";
				echo "</tr>";
			}
			if($_SESSION['CQLBType']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Type : ".$_SESSION['CQLBType']."</td>";
				echo "</tr>";
			}
			if($_SESSION['CQLBRecurrence']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Récurrence : ".$_SESSION['CQLBRecurrence']."</td>";
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
				<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;Ajouter un QLB&nbsp;</a>
				</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<?php
			if(isset($_GET['Tri'])){
				if($_GET['Tri']=="CQLBMSN"){
					$_SESSION['TriCQLBGeneral']= str_replace("MSN ASC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("MSN DESC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("MSN ASC","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("MSN DESC","",$_SESSION['TriCQLBGeneral']);
					if($_SESSION['TriCQLBMSN']==""){$_SESSION['TriCQLBMSN']="ASC";$_SESSION['TriCQLBGeneral'].= "MSN ".$_SESSION['TriCQLBMSN'].",";}
					elseif($_SESSION['TriCQLBMSN']=="ASC"){$_SESSION['TriCQLBMSN']="DESC";$_SESSION['TriCQLBGeneral'].= "MSN ".$_SESSION['TriCQLBMSN'].",";}
					else{$_SESSION['TriCQLBMSN']="";}
				}
				if($_GET['Tri']=="CQLBDesignation"){
					$_SESSION['TriCQLBGeneral']= str_replace("Designation ASC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("Designation DESC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("Designation ASC","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("Designation DESC","",$_SESSION['TriCQLBGeneral']);
					if($_SESSION['TriCQLBDesignation']==""){$_SESSION['TriCQLBDesignation']="ASC";$_SESSION['TriCQLBGeneral'].= "Designation ".$_SESSION['TriCQLBDesignation'].",";}
					elseif($_SESSION['TriCQLBDesignation']=="ASC"){$_SESSION['TriCQLBDesignation']="DESC";$_SESSION['TriCQLBGeneral'].= "Designation ".$_SESSION['TriCQLBDesignation'].",";}
					else{$_SESSION['TriCQLBDesignation']="";}
				}
				if($_GET['Tri']=="CQLBNumCQLB"){
					$_SESSION['TriCQLBGeneral']= str_replace("NumCQLB ASC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("NumCQLB DESC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("NumCQLB ASC","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("NumCQLB DESC","",$_SESSION['TriCQLBGeneral']);
					if($_SESSION['TriCQLBNumCQLB']==""){$_SESSION['TriCQLBNumCQLB']="ASC";$_SESSION['TriCQLBGeneral'].= "NumCQLB ".$_SESSION['TriCQLBNumCQLB'].",";}
					elseif($_SESSION['TriCQLBNumCQLB']=="ASC"){$_SESSION['TriCQLBNumCQLB']="DESC";$_SESSION['TriCQLBGeneral'].= "NumCQLB ".$_SESSION['TriCQLBNumCQLB'].",";}
					else{$_SESSION['TriCQLBNumCQLB']="";}
				}
				if($_GET['Tri']=="CQLBNumCV"){
					$_SESSION['TriCQLBGeneral']= str_replace("NumCV ASC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("NumCV DESC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("NumCV ASC","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("NumCV DESC","",$_SESSION['TriCQLBGeneral']);
					if($_SESSION['TriCQLBNumCV']==""){$_SESSION['TriCQLBNumCV']="ASC";$_SESSION['TriCQLBGeneral'].= "NumCV ".$_SESSION['TriCQLBNumCV'].",";}
					elseif($_SESSION['TriCQLBNumCV']=="ASC"){$_SESSION['TriCQLBNumCV']="DESC";$_SESSION['TriCQLBGeneral'].= "NumCV ".$_SESSION['TriCQLBNumCV'].",";}
					else{$_SESSION['TriCQLBNumCV']="";}
				}
				if($_GET['Tri']=="CQLBLocalisation"){
					$_SESSION['TriCQLBGeneral']= str_replace("Localisation ASC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("Localisation DESC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("Localisation ASC","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("Localisation DESC","",$_SESSION['TriCQLBGeneral']);
					if($_SESSION['TriCQLBLocalisation']==""){$_SESSION['TriCQLBLocalisation']="ASC";$_SESSION['TriCQLBGeneral'].= "Localisation ".$_SESSION['TriCQLBLocalisation'].",";}
					elseif($_SESSION['TriCQLBLocalisation']=="ASC"){$_SESSION['TriCQLBLocalisation']="DESC";$_SESSION['TriCQLBGeneral'].= "Localisation ".$_SESSION['TriCQLBLocalisation'].",";}
					else{$_SESSION['TriCQLBLocalisation']="";}
				}
				if($_GET['Tri']=="CQLBImputationAAA"){
					$_SESSION['TriCQLBGeneral']= str_replace("ImputationAAA ASC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("ImputationAAA DESC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("ImputationAAA ASC","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("ImputationAAA DESC","",$_SESSION['TriCQLBGeneral']);
					if($_SESSION['TriCQLBImputationAAA']==""){$_SESSION['TriCQLBImputationAAA']="ASC";$_SESSION['TriCQLBGeneral'].= "ImputationAAA ".$_SESSION['TriCQLBImputationAAA'].",";}
					elseif($_SESSION['TriCQLBImputationAAA']=="ASC"){$_SESSION['TriCQLBImputationAAA']="DESC";$_SESSION['TriCQLBGeneral'].= "ImputationAAA ".$_SESSION['TriCQLBImputationAAA'].",";}
					else{$_SESSION['TriCQLBImputationAAA']="";}
				}
				if($_GET['Tri']=="CQLBOMAssocie"){
					$_SESSION['TriCQLBGeneral']= str_replace("OMAssocie ASC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("OMAssocie DESC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("OMAssocie ASC","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("OMAssocie DESC","",$_SESSION['TriCQLBGeneral']);
					if($_SESSION['TriCQLBOMAssocie']==""){$_SESSION['TriCQLBOMAssocie']="ASC";$_SESSION['TriCQLBGeneral'].= "OMAssocie ".$_SESSION['TriCQLBOMAssocie'].",";}
					elseif($_SESSION['TriCQLBOMAssocie']=="ASC"){$_SESSION['TriCQLBOMAssocie']="DESC";$_SESSION['TriCQLBGeneral'].= "OMAssocie ".$_SESSION['TriCQLBOMAssocie'].",";}
					else{$_SESSION['TriCQLBOMAssocie']="";}
				}
				if($_GET['Tri']=="CQLBAMAssociee"){
					$_SESSION['TriCQLBGeneral']= str_replace("AMAssociee ASC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("AMAssociee DESC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("AMAssociee ASC","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("AMAssociee DESC","",$_SESSION['TriCQLBGeneral']);
					if($_SESSION['TriCQLBAMAssocie']==""){$_SESSION['TriCQLBAMAssocie']="ASC";$_SESSION['TriCQLBGeneral'].= "AMAssociee ".$_SESSION['TriCQLBAMAssocie'].",";}
					elseif($_SESSION['TriCQLBAMAssocie']=="ASC"){$_SESSION['TriCQLBAMAssocie']="DESC";$_SESSION['TriCQLBGeneral'].= "AMAssociee ".$_SESSION['TriCQLBAMAssocie'].",";}
					else{$_SESSION['TriCQLBAMAssocie']="";}
				}
				if($_GET['Tri']=="CQLBDate"){
					$_SESSION['TriCQLBGeneral']= str_replace("DateCreation ASC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("DateCreation DESC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("DateCreation ASC","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("DateCreation DESC","",$_SESSION['TriCQLBGeneral']);
					if($_SESSION['TriCQLBDate']==""){$_SESSION['TriCQLBDate']="ASC";$_SESSION['TriCQLBGeneral'].= "DateCreation ".$_SESSION['TriCQLBDate'].",";}
					elseif($_SESSION['TriCQLBDate']=="ASC"){$_SESSION['TriCQLBDate']="DESC";$_SESSION['TriCQLBGeneral'].= "DateCreation ".$_SESSION['TriCQLBDate'].",";}
					else{$_SESSION['TriCQLBDate']="";}
				}
				if($_GET['Tri']=="CQLBType"){
					$_SESSION['TriCQLBGeneral']= str_replace("Type ASC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("Type DESC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("Type ASC","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("Type DESC","",$_SESSION['TriCQLBGeneral']);
					if($_SESSION['TriCQLBType']==""){$_SESSION['TriCQLBType']="ASC";$_SESSION['TriCQLBGeneral'].= "Type ".$_SESSION['TriCQLBType'].",";}
					elseif($_SESSION['TriCQLBType']=="ASC"){$_SESSION['TriCQLBType']="DESC";$_SESSION['TriCQLBGeneral'].= "Type ".$_SESSION['TriCQLBType'].",";}
					else{$_SESSION['TriCQLBType']="";}
				}
				if($_GET['Tri']=="CQLBRecurrence"){
					$_SESSION['TriCQLBGeneral']= str_replace("Recurrence ASC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("Recurrence DESC,","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("Recurrence ASC","",$_SESSION['TriCQLBGeneral']);
					$_SESSION['TriCQLBGeneral']= str_replace("Recurrence DESC","",$_SESSION['TriCQLBGeneral']);
					if($_SESSION['TriCQLBRecurrence']==""){$_SESSION['TriCQLBRecurrence']="ASC";$_SESSION['TriCQLBGeneral'].= "Recurrence ".$_SESSION['TriCQLBRecurrence'].",";}
					elseif($_SESSION['TriCQLBRecurrence']=="ASC"){$_SESSION['TriCQLBRecurrence']="DESC";$_SESSION['TriCQLBGeneral'].= "Recurrence ".$_SESSION['TriCQLBRecurrence'].",";}
					else{$_SESSION['TriCQLBRecurrence']="";}
				}
			}

			$reqAnalyse="SELECT sp_atrcqlb.Id ";
			$req2="SELECT Id,MSN,NumCQLB,NumCV,ImputationAAA,OMAssocie,AMAssociee,Id_Type,Recurrence,DateCreation, ";
			$req2.="Designation, ";
			$req2.="(SELECT Libelle FROM sp_localisation WHERE sp_localisation.Id=sp_atrcqlb.Id_Localisation) AS Localisation, ";
			$req2.="(SELECT Libelle FROM sp_atrtype WHERE sp_atrtype.Id=sp_atrcqlb.Id_Type) AS Type ";
			$req="FROM sp_atrcqlb WHERE Id_Prestation=16 AND ";
			if($_SESSION['CQLBMSN2']<>""){
				$tab = explode(";",$_SESSION['CQLBMSN2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="MSN=".$valeur." OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['CQLBNumCQLB2']<>""){
				$tab = explode(";",$_SESSION['CQLBNumCQLB2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="NumCQLB='".$valeur."' OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['CQLBNumCV2']<>""){
				$tab = explode(";",$_SESSION['CQLBNumCV2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="NumCV='".$valeur."' OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['CQLBLocalisation2']<>""){
				$tab = explode(";",$_SESSION['CQLBLocalisation2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="Id_Localisation='".$valeur."' OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['CQLBImputationAAA2']<>""){
				$tab = explode(";",$_SESSION['CQLBImputationAAA2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="ImputationAAA=".$valeur." OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['CQLBOMAssocie2']<>""){
				$tab = explode(";",$_SESSION['CQLBOMAssocie2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="OMAssocie='".$valeur."' OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['CQLBAMAssociee2']<>""){
				$tab = explode(";",$_SESSION['CQLBAMAssociee2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="AMAssociee='".$valeur."' OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['CQLBType2']<>""){
				$tab = explode(";",$_SESSION['CQLBType2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="Id_Type=".$valeur." OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['CQLBRecurrence2']<>""){
				$tab = explode(";",$_SESSION['CQLBRecurrence2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="Recurrence=".$valeur." OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['CQLBDu2']<>"" || $_SESSION['CQLBAu2']<>""){
				$req.=" ( ";
				if($_SESSION['CQLBDu2']<>""){
					$req.="DateCreation >= '". TrsfDate_($_SESSION['CQLBDu2'])."' ";
					$req.=" AND ";
				}
				if($_SESSION['CQLBAu2']<>""){
					$req.="DateCreation <= '". TrsfDate_($_SESSION['CQLBAu2'])."' ";
					$req.=" ";
				}
				if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
				$req.=" ) ";
			}
			if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
			if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}

			$result=mysqli_query($bdd,$reqAnalyse.$req);
			$nbResulta=mysqli_num_rows($result);
			
			if($_SESSION['TriCQLBGeneral']<>""){
				$req.="ORDER BY ".substr($_SESSION['TriCQLBGeneral'],0,-1);
			}

			$nombreDePages=ceil($nbResulta/100);
			if(isset($_GET['Page'])){$_SESSION['CQLBPage']=$_GET['Page'];}
			//else{$_SESSION['CQLBPage']=0;}
			$req3=" LIMIT ".($_SESSION['CQLBPage']*100).",100";
			
			$result=mysqli_query($bdd,$req2.$req.$req3);
			$nbResulta=mysqli_num_rows($result);
		?>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				$nbPage=0;
				if($_SESSION['CQLBPage']>1){echo "<b> <a style='color:#00599f;' href='Liste_QLB.php?Page=0'><<</a> </b>";}
				$valeurDepart=1;
				if($_SESSION['CQLBPage']<=5){
					$valeurDepart=1;
				}
				elseif($_SESSION['CQLBPage']>=($nombreDePages-6)){
					$valeurDepart=$nombreDePages-6;
				}
				else{
					$valeurDepart=$_SESSION['CQLBPage']-5;
				}
				for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
					if($i<=$nombreDePages){
						if($i==($_SESSION['CQLBPage']+1)){
							echo "<b> [ ".$i." ] </b>"; 
						}	
						else{
							echo "<b> <a style='color:#00599f;' href='Liste_QLB.php?Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($_SESSION['CQLBPage']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_QLB.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
		<table cellpadding="0" cellspacing="0" align="left" class="GeneralInfo" style="width:100%;">
			<tr bgcolor="#00325F">
				<td class="EnTeteTableauCompetences" width="6%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_QLB.php?Tri=CQLBMSN">MSN<?php if($_SESSION['TriCQLBMSN']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriCQLBMSN']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_QLB.php?Tri=CQLBNumCQLB">N° CQLB<?php if($_SESSION['TriCQLBNumCQLB']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriCQLBNumCQLB']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_QLB.php?Tri=CQLBNumCV">N° CV<?php if($_SESSION['TriCQLBNumCV']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriCQLBNumCV']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_QLB.php?Tri=CQLBLocalisation">Localisation<?php if($_SESSION['TriCQLBLocalisation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriCQLBLocalisation']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_QLB.php?Tri=CQLBImputationAAA">Imputation AAA<?php if($_SESSION['TriCQLBImputationAAA']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriCQLBImputationAAA']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_QLB.php?Tri=CQLBOMAssocie">Ordre de montage associé<?php if($_SESSION['TriCQLBOMAssocie']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriCQLBOMAssocie']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="20%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_QLB.php?Tri=CQLBDesignation">Désignation<?php if($_SESSION['TriCQLBDesignation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriCQLBDesignation']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_QLB.php?Tri=CQLBAMAssociee">AM associée<?php if($_SESSION['TriCQLBAMAssocie']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriCQLBAMAssocie']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="12%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_QLB.php?Tri=CQLBType">Type<?php if($_SESSION['TriCQLBType']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriCQLBType']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_QLB.php?Tri=CQLBRecurrence">Récurrence<?php if($_SESSION['TriCQLBRecurrence']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriCQLBRecurrence']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
			</tr>
			<?php
				if ($nbResulta>0){
					$couleur="#ffffff";
					while($row=mysqli_fetch_array($result)){
						if($couleur=="#ffffff"){$couleur="#E1E1D7";}
						else{$couleur="#ffffff";}
						?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="6%">&nbsp;<?php echo $row['MSN'];?></td>
								<td width="8%">&nbsp;<?php echo $row['NumCQLB'];?></td>
								<td width="8%">&nbsp;<?php echo $row['NumCV'];?></td>
								<td width="12%">&nbsp;<?php echo $row['Localisation'];?></td>
								<td width="10%">&nbsp;<?php if($row['ImputationAAA']==1){echo "Oui";}else{echo "Non";}?></td>
								<td width="10%">&nbsp;<?php echo $row['OMAssocie'];?></td>
								<td width="20%">&nbsp;<?php echo addslashes($row['Designation']);?></td>
								<td width="10%">&nbsp;<?php echo $row['AMAssociee'];?></td>
								<td width="12%">&nbsp;<?php echo $row['Type'];?></td>
								<td width="10%">&nbsp;<?php if($row['Recurrence']==1){echo "Oui";}else{echo "Non";}?></td>
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
}
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
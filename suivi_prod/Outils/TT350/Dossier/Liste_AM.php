<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout_Critere(){
			var w=window.open("Ajout_CritereAM.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=800,height=250");
			w.focus();
		}
		function Suppr_Critere(critere,valeur){
			var w=window.open("Ajout_CritereAM.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=200");
			w.focus();
		}
		function OuvreFenetreAjout(){
			var w=window.open("Ajout_AM.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=600,height=250");
			w.focus();
			}
		function OuvreFenetreModif(Id){
			var w=window.open("Ajout_AM.php?Mode=M&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=600,height=250");
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
		$_SESSION['AMOMAssocie']="";
		$_SESSION['AMDu']="";
		$_SESSION['AMAu']="";
		$_SESSION['AMImputationAAA']="";
		$_SESSION['AMNCMajeure']="";
		$_SESSION['AMType']="";
		$_SESSION['AMRecurrence']="";
		
		$_SESSION['AMMSN2']="";
		$_SESSION['AMNumAMNC2']="";
		$_SESSION['AMOMAssocie2']="";
		$_SESSION['AMDu2']="";
		$_SESSION['AMAu2']="";
		$_SESSION['AMImputationAAA2']="";
		$_SESSION['AMNCMajeure2']="";
		$_SESSION['AMType2']="";
		$_SESSION['AMRecurrence2']="";
		$_SESSION['AMPage']="0";
	}
	elseif(isset($_POST['Tri_RAZ'])){
		$_SESSION['TriAMMSN']="";
		$_SESSION['TriAMNumAMNC']="";
		$_SESSION['TriAMOMAssocie']="";
		$_SESSION['TriAMDesignation']="";
		$_SESSION['TriAMDate']="";
		$_SESSION['TriAMImputationAAA']="";
		$_SESSION['TriAMNCMajeure']="";
		$_SESSION['TriAMType']="";
		$_SESSION['TriAMRecurrence']="";
		$_SESSION['TriAMGeneral']="";
	}
}

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
if(substr($_SESSION['DroitSP'],4,1)=='1' || substr($_SESSION['DroitSP'],3,1)=='1'){
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Liste_AM.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Liste des anomalies de montage / NC majeures</td>
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
			if($_SESSION['AMMSN']<>""){
				echo "<tr>";
				echo "<td>&nbsp; MSN : ".$_SESSION['AMMSN']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMNumAMNC']<>""){
				echo "<tr>";
				echo "<td>&nbsp; N° AM/NC : ".$_SESSION['AMNumAMNC']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMOMAssocie']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Ordre de montage associé : ".$_SESSION['AMOMAssocie']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMDu']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Date de début : ".$_SESSION['AMDu']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMAu']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Date de fin : ".$_SESSION['AMAu']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMImputationAAA']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Imputation AAA : ".$_SESSION['AMImputationAAA']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMNCMajeure']<>""){
				echo "<tr>";
				echo "<td>&nbsp; NC majeure : ".$_SESSION['AMNCMajeure']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMType']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Type : ".$_SESSION['AMType']."</td>";
				echo "</tr>";
			}
			if($_SESSION['AMRecurrence']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Récurrence : ".$_SESSION['AMRecurrence']."</td>";
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
				<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;Ajouter une anomalie de montage / NC majeure&nbsp;</a>
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
				if($_GET['Tri']=="AMDesignation"){
					$_SESSION['TriAMGeneral']= str_replace("Designation ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Designation DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Designation ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Designation DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMDesignation']==""){$_SESSION['TriAMDesignation']="ASC";$_SESSION['TriAMGeneral'].= "Designation ".$_SESSION['TriAMDesignation'].",";}
					elseif($_SESSION['TriAMDesignation']=="ASC"){$_SESSION['TriAMDesignation']="DESC";$_SESSION['TriAMGeneral'].= "Designation ".$_SESSION['TriAMDesignation'].",";}
					else{$_SESSION['TriAMDesignation']="";}
				}
				if($_GET['Tri']=="AMOMAssocie"){
					$_SESSION['TriAMGeneral']= str_replace("OMAssocie ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("OMAssocie DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("OMAssocie ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("OMAssocie DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMOMAssocie']==""){$_SESSION['TriAMOMAssocie']="ASC";$_SESSION['TriAMGeneral'].= "OMAssocie ".$_SESSION['TriAMOMAssocie'].",";}
					elseif($_SESSION['TriAMOMAssocie']=="ASC"){$_SESSION['TriAMOMAssocie']="DESC";$_SESSION['TriAMGeneral'].= "OMAssocie ".$_SESSION['TriAMOMAssocie'].",";}
					else{$_SESSION['TriAMOMAssocie']="";}
				}
				if($_GET['Tri']=="AMDate"){
					$_SESSION['TriAMGeneral']= str_replace("DateCreation ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("DateCreation DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("DateCreation ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("DateCreation DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMDate']==""){$_SESSION['TriAMDate']="ASC";$_SESSION['TriAMGeneral'].= "DateCreation ".$_SESSION['TriAMDate'].",";}
					elseif($_SESSION['TriAMDate']=="ASC"){$_SESSION['TriAMDate']="DESC";$_SESSION['TriAMGeneral'].= "DateCreation ".$_SESSION['TriAMDate'].",";}
					else{$_SESSION['TriAMDate']="";}
				}
				if($_GET['Tri']=="AMImputationAAA"){
					$_SESSION['TriAMGeneral']= str_replace("ImputationAAA ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("ImputationAAA DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("ImputationAAA ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("ImputationAAA DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMImputationAAA']==""){$_SESSION['TriAMImputationAAA']="ASC";$_SESSION['TriAMGeneral'].= "ImputationAAA ".$_SESSION['TriAMImputationAAA'].",";}
					elseif($_SESSION['TriAMImputationAAA']=="ASC"){$_SESSION['TriAMImputationAAA']="DESC";$_SESSION['TriAMGeneral'].= "ImputationAAA ".$_SESSION['TriAMImputationAAA'].",";}
					else{$_SESSION['TriAMImputationAAA']="";}
				}
				if($_GET['Tri']=="AMNCMajeure"){
					$_SESSION['TriAMGeneral']= str_replace("NCMajeure ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("NCMajeure DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("NCMajeure ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("NCMajeure DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMNCMajeure']==""){$_SESSION['TriAMNCMajeure']="ASC";$_SESSION['TriAMGeneral'].= "NCMajeure ".$_SESSION['TriAMNCMajeure'].",";}
					elseif($_SESSION['TriAMNCMajeure']=="ASC"){$_SESSION['TriAMNCMajeure']="DESC";$_SESSION['TriAMGeneral'].= "NCMajeure ".$_SESSION['TriAMNCMajeure'].",";}
					else{$_SESSION['TriAMNCMajeure']="";}
				}
				if($_GET['Tri']=="AMType"){
					$_SESSION['TriAMGeneral']= str_replace("Type ASC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Type DESC,","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Type ASC","",$_SESSION['TriAMGeneral']);
					$_SESSION['TriAMGeneral']= str_replace("Type DESC","",$_SESSION['TriAMGeneral']);
					if($_SESSION['TriAMType']==""){$_SESSION['TriAMType']="ASC";$_SESSION['TriAMGeneral'].= "Type ".$_SESSION['TriAMType'].",";}
					elseif($_SESSION['TriAMType']=="ASC"){$_SESSION['TriAMType']="DESC";$_SESSION['TriAMGeneral'].= "Type ".$_SESSION['TriAMType'].",";}
					else{$_SESSION['TriAMType']="";}
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
			}
			$reqAnalyse="SELECT sp_atram.Id ";
			$req2="SELECT Id,MSN,ImputationAAA,NCMajeure,OMAssocie,Id_Type,Recurrence,DateCreation,NumAMNC, ";
			$req2.="Designation, ";
			$req2.="(SELECT Libelle FROM sp_atrtype WHERE sp_atrtype.Id=sp_atram.Id_Type) AS Type ";
			$req="FROM sp_atram WHERE Id_Prestation=316 AND ";
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
			if($_SESSION['AMOMAssocie2']<>""){
				$tab = explode(";",$_SESSION['AMOMAssocie2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="OMAssocie='".$valeur."' OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['AMImputationAAA2']<>""){
				$tab = explode(";",$_SESSION['AMImputationAAA2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="ImputationAAA=".$valeur." OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['AMNCMajeure2']<>""){
				$tab = explode(";",$_SESSION['AMNCMajeure2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="NCMajeure=".$valeur." OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['AMType2']<>""){
				$tab = explode(";",$_SESSION['AMType2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="Id_Type=".$valeur." OR ";
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
			//else{$_SESSION['AMPage']=0;}
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
				<td class="EnTeteTableauCompetences" width="6%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=AMNumAMNC">N° AM/NC<?php if($_SESSION['TriAMNumAMNC']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMNumAMNC']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="16%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=AMOMAssocie">Ordre de montage associé<?php if($_SESSION['TriAMOMAssocie']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMOMAssocie']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="20%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=AMDesignation">Désignation<?php if($_SESSION['TriAMDesignation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMDesignation']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="16%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=AMImputationAAA">Imputation AAA<?php if($_SESSION['TriAMImputationAAA']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMImputationAAA']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="16%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=AMNCMajeure">NC majeure<?php if($_SESSION['TriAMNCMajeure']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMNCMajeure']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="16%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=AMType">Type<?php if($_SESSION['TriAMType']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMType']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="16%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_AM.php?Tri=AMRecurrence">Récurrence<?php if($_SESSION['TriAMRecurrence']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriAMRecurrence']=="ASC"){echo "&darr;";}?></a></td>
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
								<td width="6%">&nbsp;<?php echo $row['NumAMNC'];?></td>
								<td width="16%">&nbsp;<?php echo $row['OMAssocie'];?></td>
								<td width="20%">&nbsp;<?php echo addslashes($row['Designation']);?></td>
								<td width="16%">&nbsp;<?php if($row['ImputationAAA']==1){echo "Oui";}else{echo "Non";}?></td>
								<td width="16%">&nbsp;<?php if($row['NCMajeure']==1){echo "Oui";}else{echo "Non";}?></td>
								<td width="16%">&nbsp;<?php echo $row['Type'];?></td>
								<td width="16%">&nbsp;<?php if($row['Recurrence']==1){echo "Oui";}else{echo "Non";}?></td>
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
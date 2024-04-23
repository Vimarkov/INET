<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout_Critere(){
			var w=window.open("Ajout_Critere.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=850,height=240");
			w.focus();
		}
		function Suppr_Critere(critere,valeur){
			var w=window.open("Ajout_Critere.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=400");
			w.focus();
		}
		function OuvreFenetreModif(Id){
			var nLargeurMax = window.screen.availWidth;
			var nHauteurMax = window.screen.availHeight;
			var w=window.open("Modif_Dossier.php?Mode=M&Id="+Id,"PageDossier","status=no,menubar=no,scrollbars=yes,fullscreen=yes,width="+nLargeurMax+", height="+nHauteurMax+"");
			w.focus();
		}
		function OuvreFenetreSuppr(Id){
			if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
				var w=window.open("Modif_Dossier.php?Mode=S&Id="+Id,"PageDossier","status=no,menubar=no,scrollbars=yes,width=130,height=60");
				w.focus();
			}
		}
		function Excel(){
			var w=window.open("ExtractDossier.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
		}
	</script>
</head>
<?php
require("../../../Menu.php");
require("../../Fonctions.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
$NumLigne=-1;
if($_POST){
	if(isset($_POST['Recherche_RAZ'])){
		$_SESSION['OTMSN']="";
		$_SESSION['OTOM']="";
		$_SESSION['OTDesignation']="";
		$_SESSION['OTLigne']="";
		$_SESSION['OTPoste45']="";
		$_SESSION['OTStatutP']="";
		$_SESSION['OTRaisonP']="";
		$_SESSION['OTStatutQ']="";
		$_SESSION['OTRaisonQ']="";
		
		$_SESSION['OTMSN2']="";
		$_SESSION['OTOM2']="";
		$_SESSION['OTDesignation2']="";
		$_SESSION['OTLigne2']="";
		$_SESSION['OTPoste452']="";
		$_SESSION['OTStatutP2']="";
		$_SESSION['OTRaisonP2']="";
		$_SESSION['OTStatutQ2']="";
		$_SESSION['OTRaisonQ2']="";
		
		$_SESSION['OTModeFiltre']="";
		$_SESSION['OTPage']="0";
	}
	elseif(isset($_POST['BtnRechercher'])){
		$_SESSION['OTModeFiltre']="oui";
	}
	elseif(isset($_POST['Tri_RAZ'])){
		$_SESSION['TriOTMSN']="ASC";
		$_SESSION['TriOTOM']="ASC";
		$_SESSION['TriOTDesignation']="";
		$_SESSION['TriOTLigne']="ASC";
		$_SESSION['TriOTPoste45']="";
		$_SESSION['TriOTAMAssociee']="";
		$_SESSION['TriOTStatutP']="";
		$_SESSION['TriOTRaisonP']="";
		$_SESSION['TriOTStatutQ']="";
		$_SESSION['TriOTRaisonQ']="";
		$_SESSION['OTTriGeneral']="";
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
			if($_SESSION['OTMSN']<>""){
				echo "<tr>";
				echo "<td>&nbsp; MSN : ".$_SESSION['OTMSN']."</td>";
				echo "</tr>";
			}
			if($_SESSION['OTOM']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Ordres de montages : ".$_SESSION['OTOM']."</td>";
				echo "</tr>";
			}
			if($_SESSION['OTDesignation']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Désignations : ".$_SESSION['OTDesignation']."</td>";
				echo "</tr>";
			}
			if($_SESSION['OTLigne']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Lignes : ".$_SESSION['OTLigne']."</td>";
				echo "</tr>";
			}
			if($_SESSION['OTPoste45']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Poste 45 : ".$_SESSION['OTPoste45']."</td>";
				echo "</tr>";
			}
			if($_SESSION['OTStatutP']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Statut PROD : ".$_SESSION['OTStatutP']."</td>";
				echo "</tr>";
			}
			if($_SESSION['OTRaisonP']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Informations statut production : ".$_SESSION['OTRaisonP']."</td>";
				echo "</tr>";
			}
			if($_SESSION['OTStatutQ']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Statut QUALITE : ".$_SESSION['OTStatutQ']."</td>";
				echo "</tr>";
			}
			if($_SESSION['OTRaisonQ']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Information statut qualité : ".$_SESSION['OTRaisonQ']."</td>";
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
					$_SESSION['OTTriGeneral']= str_replace("MSN ASC,","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("MSN DESC,","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("MSN ASC","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("MSN DESC","",$_SESSION['OTTriGeneral']);
					if($_SESSION['TriOTMSN']==""){$_SESSION['TriOTMSN']="ASC";$_SESSION['OTTriGeneral'].= "MSN ".$_SESSION['TriOTMSN'].",";}
					elseif($_SESSION['TriOTMSN']=="ASC"){$_SESSION['TriOTMSN']="DESC";$_SESSION['OTTriGeneral'].= "MSN ".$_SESSION['TriOTMSN'].",";}
					else{$_SESSION['TriOTMSN']="";}
				}
				if($_GET['Tri']=="OM"){
					$_SESSION['OTTriGeneral']= str_replace("OrdreMontage ASC,","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("OrdreMontage DESC,","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("OrdreMontage ASC","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("OrdreMontage DESC","",$_SESSION['OTTriGeneral']);
					if($_SESSION['TriOTOM']==""){$_SESSION['TriOTOM']="ASC";$_SESSION['OTTriGeneral'].= "OrdreMontage ".$_SESSION['TriOTOM'].",";}
					elseif($_SESSION['TriOTOM']=="ASC"){$_SESSION['TriOTOM']="DESC";$_SESSION['OTTriGeneral'].= "OrdreMontage ".$_SESSION['TriOTOM'].",";}
					else{$_SESSION['TriOTOM']="";}
				}
				if($_GET['Tri']=="Designation"){
					$_SESSION['OTTriGeneral']= str_replace("Designation ASC,","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("Designation DESC,","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("Designation ASC","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("Designation DESC","",$_SESSION['OTTriGeneral']);
					if($_SESSION['TriOTDesignation']==""){$_SESSION['TriOTDesignation']="ASC";$_SESSION['OTTriGeneral'].= "Designation ".$_SESSION['TriOTDesignation'].",";}
					elseif($_SESSION['TriOTDesignation']=="ASC"){$_SESSION['TriOTDesignation']="DESC";$_SESSION['OTTriGeneral'].= "Designation ".$_SESSION['TriOTDesignation'].",";}
					else{$_SESSION['TriOTDesignation']="";}
				}
				if($_GET['Tri']=="Ligne"){
					$_SESSION['OTTriGeneral']= str_replace("Ligne ASC,","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("Ligne DESC,","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("Ligne ASC","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("Ligne DESC","",$_SESSION['OTTriGeneral']);
					if($_SESSION['TriOTLigne']==""){$_SESSION['TriOTLigne']="ASC";$_SESSION['OTTriGeneral'].= "Ligne ".$_SESSION['TriOTLigne'].",";}
					elseif($_SESSION['TriOTLigne']=="ASC"){$_SESSION['TriOTLigne']="DESC";$_SESSION['OTTriGeneral'].= "Ligne ".$_SESSION['TriOTLigne'].",";}
					else{$_SESSION['TriOTLigne']="";}
				}
				if($_GET['Tri']=="Poste45"){
					$_SESSION['OTTriGeneral']= str_replace("Poste45 ASC,","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("Poste45 DESC,","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("Poste45 ASC","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("Poste45 DESC","",$_SESSION['OTTriGeneral']);
					if($_SESSION['TriOTPoste45']==""){$_SESSION['TriOTPoste45']="ASC";$_SESSION['OTTriGeneral'].= "Poste45 ".$_SESSION['TriOTPoste45'].",";}
					elseif($_SESSION['TriOTPoste45']=="ASC"){$_SESSION['TriOTPoste45']="DESC";$_SESSION['OTTriGeneral'].= "Poste45 ".$_SESSION['TriOTPoste45'].",";}
					else{$_SESSION['TriOTPoste45']="";}
				}
				if($_GET['Tri']=="AMAssociee"){
					$_SESSION['OTTriGeneral']= str_replace("AMAssociee ASC,","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("AMAssociee DESC,","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("AMAssociee ASC","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("AMAssociee DESC","",$_SESSION['OTTriGeneral']);
					if($_SESSION['TriOTAMAssociee']==""){$_SESSION['TriOTAMAssociee']="ASC";$_SESSION['OTTriGeneral'].= "AMAssociee ".$_SESSION['TriOTAMAssociee'].",";}
					elseif($_SESSION['TriOTAMAssociee']=="ASC"){$_SESSION['TriOTAMAssociee']="DESC";$_SESSION['OTTriGeneral'].= "AMAssociee ".$_SESSION['TriOTAMAssociee'].",";}
					else{$_SESSION['TriOTAMAssociee']="";}
				}
				if($_GET['Tri']=="StatutP"){
					$_SESSION['OTTriGeneral']= str_replace("Id_StatutPROD ASC,","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("Id_StatutPROD DESC,","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("Id_StatutPROD ASC","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("Id_StatutPROD DESC","",$_SESSION['OTTriGeneral']);
					if($_SESSION['TriOTStatutP']==""){$_SESSION['TriOTStatutP']="ASC";$_SESSION['OTTriGeneral'].= "Id_StatutPROD ".$_SESSION['TriOTStatutP'].",";}
					elseif($_SESSION['TriOTStatutP']=="ASC"){$_SESSION['TriOTStatutP']="DESC";$_SESSION['OTTriGeneral'].= "Id_StatutPROD ".$_SESSION['TriOTStatutP'].",";}
					else{$_SESSION['TriOTStatutP']="";}
				}
				if($_GET['Tri']=="CauseP"){
					$_SESSION['OTTriGeneral']= str_replace("CauseP ASC,","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("CauseP DESC,","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("CauseP ASC","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("CauseP DESC","",$_SESSION['OTTriGeneral']);
					if($_SESSION['TriOTRaisonP']==""){$_SESSION['TriOTRaisonP']="ASC";$_SESSION['OTTriGeneral'].= "CauseP ".$_SESSION['TriOTRaisonP'].",";}
					elseif($_SESSION['TriOTRaisonP']=="ASC"){$_SESSION['TriOTRaisonP']="DESC";$_SESSION['OTTriGeneral'].= "CauseP ".$_SESSION['TriOTRaisonP'].",";}
					else{$_SESSION['TriOTRaisonP']="";}
				}
				if($_GET['Tri']=="StatutQ"){
					$_SESSION['OTTriGeneral']= str_replace("Id_StatutQUALITE ASC,","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("Id_StatutQUALITE DESC,","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("Id_StatutQUALITE ASC","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("Id_StatutQUALITE DESC","",$_SESSION['OTTriGeneral']);
					if($_SESSION['TriOTStatutQ']==""){$_SESSION['TriOTStatutQ']="ASC";$_SESSION['OTTriGeneral'].= "Id_StatutQUALITE ".$_SESSION['TriOTStatutQ'].",";}
					elseif($_SESSION['TriOTStatutQ']=="ASC"){$_SESSION['TriOTStatutQ']="DESC";$_SESSION['OTTriGeneral'].= "Id_StatutQUALITE ".$_SESSION['TriOTStatutQ'].",";}
					else{$_SESSION['TriOTStatutQ']="";}
				}
				if($_GET['Tri']=="CauseQ"){
					$_SESSION['OTTriGeneral']= str_replace("CauseQ ASC,","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("CauseQ DESC,","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("CauseQ ASC","",$_SESSION['OTTriGeneral']);
					$_SESSION['OTTriGeneral']= str_replace("CauseQ DESC","",$_SESSION['OTTriGeneral']);
					if($_SESSION['TriOTRaisonQ']==""){$_SESSION['TriOTRaisonQ']="ASC";$_SESSION['OTTriGeneral'].= "CauseQ ".$_SESSION['TriOTRaisonQ'].",";}
					elseif($_SESSION['TriOTRaisonQ']=="ASC"){$_SESSION['TriOTRaisonQ']="DESC";$_SESSION['OTTriGeneral'].= "CauseQ ".$_SESSION['TriOTRaisonQ'].",";}
					else{$_SESSION['TriOTRaisonQ']="";}
				}
			}
			if($_SESSION['OTModeFiltre']=="oui"){
				$reqAnalyse="SELECT sp_atrot.Id ";
				$req2="SELECT Id,MSN,OrdreMontage,Designation,Id_StatutPROD,Id_StatutQUALITE,";
				$req2.="(SELECT sp_atrarticle.Ligne FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article LIMIT 1) AS Ligne, ";
				$req2.="(SELECT sp_atrarticle.Poste45 FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article LIMIT 1) AS Poste45, ";
				$req2.="(SELECT sp_atrcauseretard.Libelle FROM sp_atrcauseretard WHERE sp_atrcauseretard.Id=sp_atrot.Id_CauseRetardPROD LIMIT 1) AS CauseP, ";
				$req2.="(SELECT sp_atrcauseretard.Libelle FROM sp_atrcauseretard WHERE sp_atrcauseretard.Id=sp_atrot.Id_CauseRetardQUALITE LIMIT 1) AS CauseQ ";
				$req="FROM sp_atrot ";
				$req.="WHERE sp_atrot.Id_Prestation=262 AND sp_atrot.Supprime=0 AND ";
				if($_SESSION['OTMSN2']<>""){
					$tab = explode(";",$_SESSION['OTMSN2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_atrot.MSN=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['OTOM2']<>""){
					$tab = explode(";",$_SESSION['OTOM2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_atrot.OrdreMontage='".addslashes($valeur)."' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['OTDesignation2']<>""){
					$tab = explode(";",$_SESSION['OTDesignation2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_atrot.Designation LIKE '%".addslashes($valeur)."%' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['OTLigne2']<>""){
					$tab = explode(";",$_SESSION['OTLigne2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							if($valeur=="?"){$req.="(SELECT sp_atrarticle.Ligne FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article LIMIT 1) IS NULL OR ";}
							else{$req.="(SELECT sp_atrarticle.Ligne FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article LIMIT 1)=".$valeur." OR ";}
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['OTPoste452']<>""){
					$tab = explode(";",$_SESSION['OTPoste452']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="(SELECT sp_atrarticle.Poste45 FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article LIMIT 1)=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['OTStatutP2']<>""){
					$tab = explode(";",$_SESSION['OTStatutP2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							if($valeur=="(vide)"){$req.="sp_atrot.Id_StatutPROD='' OR ";}
							else{$req.="sp_atrot.Id_StatutPROD='".$valeur."' OR ";}
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['OTRaisonP2']<>""){
					$tab = explode(";",$_SESSION['OTRaisonP2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_atrot.Id_CauseRetardPROD=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['OTStatutQ2']<>""){
					$tab = explode(";",$_SESSION['OTStatutQ2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							if($valeur=="(vide)"){$req.="sp_atrot.Id_StatutQUALITE='' OR ";}
							else{$req.="sp_atrot.Id_StatutQUALITE='".$valeur."' OR ";}
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['OTRaisonQ2']<>""){
					$tab = explode(";",$_SESSION['OTRaisonQ2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_atrot.Id_CauseRetardQUALITE=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
				if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}
					
				$result=mysqli_query($bdd,$reqAnalyse.$req);
				$nbResulta=mysqli_num_rows($result);
				
				if($_SESSION['OTTriGeneral']<>""){
					$req.="ORDER BY ".substr($_SESSION['OTTriGeneral'],0,-1);
				}

				$nombreDePages=ceil($nbResulta/100);
				if(isset($_GET['Page'])){$_SESSION['OTPage']=$_GET['Page'];}
				//else{$_SESSION['OTPage']=0;}
				$req3=" LIMIT ".($_SESSION['OTPage']*100).",100";
				
				$result=mysqli_query($bdd,$req2.$req.$req3);
				$nbResulta=mysqli_num_rows($result);
			}
		?>
		<tr>
			<td align="center" style="font-size:14px;">
				<?php
					if($_SESSION['OTModeFiltre']=="oui"){
						$nbPage=0;
						if($_SESSION['OTPage']>1){echo "<b> <a style='color:#00599f;' href='Liste_Dossier.php?Page=0'><<</a> </b>";}
						$valeurDepart=1;
						if($_SESSION['OTPage']<=5){
							$valeurDepart=1;
						}
						elseif($_SESSION['OTPage']>=($nombreDePages-6)){
							$valeurDepart=$nombreDePages-6;
						}
						else{
							$valeurDepart=$_SESSION['OTPage']-5;
						}
						for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
							if($i<=$nombreDePages){
								if($i==($_SESSION['OTPage']+1)){
									echo "<b> [ ".$i." ] </b>"; 
								}	
								else{
									echo "<b> <a style='color:#00599f;' href='Liste_Dossier.php?Page=".($i-1)."'>".$i."</a> </b>";
								}
							}
						}
						if($_SESSION['OTPage']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_Dossier.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
					}
				?>
			</td>
		</tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr bgcolor="#00325F">
					<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=MSN">MSN<?php if($_SESSION['TriOTMSN']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriOTMSN']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=OM">Ordre de montage<?php if($_SESSION['TriOTOM']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriOTOM']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="20%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=Designation">Désignation<?php if($_SESSION['TriOTDesignation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriOTDesignation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=Ligne">Ligne<?php if($_SESSION['TriOTLigne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriOTLigne']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=Poste45">Poste 45<?php if($_SESSION['TriOTPoste45']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriOTPoste45']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="12%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=StatutP">Statut PROD<?php if($_SESSION['TriOTStatutP']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriOTStatutP']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="15%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=CauseP">Information statut production<?php if($_SESSION['TriOTRaisonP']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriOTRaisonP']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;color:#ffffff;">N° AM/NC</td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=StatutQ">Statut QUALITE<?php if($_SESSION['TriOTStatutQ']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriOTStatutQ']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="15%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Dossier.php?Tri=CauseQ">Information statut qualité<?php if($_SESSION['TriOTRaisonQ']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriOTRaisonQ']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;"></td>
					<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;"></td>
				</tr>
				<tr>
					<?php
						if($_SESSION['OTModeFiltre']=="oui"){
							if ($nbResulta>0){
								$couleur="#ffffff";
								while($row=mysqli_fetch_array($result)){	
									$Ligne="?";
									$couleurLigne="color:#e31b1b;";
									if($row['Ligne']<>""){$Ligne=$row['Ligne'];$couleurLigne="";}
									
									if($row['Poste45']==1){$Poste45="Oui";}
									elseif($row['Poste45']==null){$Poste45="?";}
									elseif($row['Poste45']==0){$Poste45="Non";}
									
									$AMAssocie="";
									$req="SELECT NumAMNC FROM sp_atram WHERE sp_atram.MSN=".$row['MSN']." AND sp_atram.OMAssocie='".$row['OrdreMontage']."' ";
									$resultAM=mysqli_query($bdd,$req);
									$nbResultaAM=mysqli_num_rows($resultAM);
									if($nbResultaAM>0){
										while($rowAM=mysqli_fetch_array($resultAM)){	
											$AMAssocie.=$rowAM['NumAMNC']."<br>";
										}
									}
									
									switch($row['Ligne']){
										case "1" : $couleur="#92d050";break;
										case "2" : $couleur="#00b0f0";break;
										case "3" : $couleur="#f79545";break;
										case "4" : $couleur="#c04f4c";break;
										case "5" : $couleur="#ccc0da";break;
										case "6" : $couleur="#538cd5";break;
										case "7" : $couleur="#ffff00";break;
										case "MOU/DEMOUL" : $couleur="#92d050";break;
										default : $couleur="#ffffff";break;
										
									}
									?>
										<tr bgcolor="<?php echo $couleur;?>">
											<td width="5%" style="text-align:center;border-bottom:1px #0077aa dotted;">&nbsp;<?php echo $row['MSN'];?></td>
											<td width="10%" style="text-align:center;border-bottom:1px #0077aa dotted;color:#000000;"><a style="text-decoration:none;color:#000000;font-style:bold;font-size:15px;" href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>)"><?php echo $row['OrdreMontage'];?></a></td>
											<td width="20%" style="text-align:center;border-bottom:1px #0077aa dotted;"><?php echo stripslashes($row['Designation']);?></td>
											<td width="5%" style="text-align:center;border-bottom:1px #0077aa dotted;<?php echo $couleurLigne;?>"><?php echo $Ligne;?></td>
											<td width="5%" style="text-align:center;border-bottom:1px #0077aa dotted;<?php echo $couleurLigne;?>"><?php echo $Poste45;?></td>
											<td width="12%" style="text-align:center;border-bottom:1px #0077aa dotted;"><?php echo $row['Id_StatutPROD'];?></td>
											<td width="15%" style="text-align:center;border-bottom:1px #0077aa dotted;"><?php echo $row['CauseP'];?></td>
											<td width="10%" style="text-align:center;border-bottom:1px #0077aa dotted;"><?php echo $AMAssocie;?></td>
											<td width="10%" style="text-align:center;border-bottom:1px #0077aa dotted;"><?php echo $row['Id_StatutQUALITE'];?></td>
											<td width="15%" style="text-align:center;border-bottom:1px #0077aa dotted;"><?php echo $row['CauseQ'];?></td>
											<?php
												if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1' || substr($_SESSION['DroitSP'],3,1)=='1'){
											?>
											<td width="2%" align="center" style="border-bottom:1px #0077aa dotted;">
												<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>)">
												<img src='../../../Images/Modif.gif' border='0' alt='Modifier' title='Modifier'>
												</a>
											</td>
											<td width="2%" align="center" style="border-bottom:1px #0077aa dotted;">
												<a href="javascript:OuvreFenetreSuppr(<?php echo $row['Id']; ?>)">
												<img src='../../../Images/Suppression.gif' border='0' alt='Supprimer' title='Supprimer'>
												</a>
											</td>
											<?php
												}
												else{
											?>
												<td width="2%" align="center" style="border-bottom:1px #0077aa dotted;">
												</td>
												<td width="2%" align="center" style="border-bottom:1px #0077aa dotted;">
												</td
											<?php
												}
											?>
										</tr>
									<?php
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
				if($_SESSION['OTModeFiltre']=="oui"){
					$nbPage=0;
					if($_SESSION['OTPage']>1){echo "<b> <a style='color:#00599f;' href='Liste_Dossier.php?Page=0'><<</a> </b>";}
					$valeurDepart=1;
					if($_SESSION['OTPage']<=5){
						$valeurDepart=1;
					}
					elseif($_SESSION['OTPage']>=($nombreDePages-6)){
						$valeurDepart=$nombreDePages-6;
					}
					else{
						$valeurDepart=$_SESSION['OTPage']-5;
					}
					for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
						if($i<=$nombreDePages){
							if($i==($_SESSION['OTPage']+1)){
								echo "<b> [ ".$i." ] </b>"; 
							}	
							else{
								echo "<b> <a style='color:#00599f;' href='Liste_Dossier.php?Page=".($i-1)."'>".$i."</a> </b>";
							}
						}
					}
					if($_SESSION['OTPage']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_Dossier.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
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
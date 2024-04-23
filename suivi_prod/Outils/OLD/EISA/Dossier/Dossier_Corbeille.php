<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout_Critere(){
			var w=window.open("Ajout_CritereCorbeille.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=700,height=240");
			w.focus();
		}
		function Suppr_Critere(critere,valeur){
			var w=window.open("Ajout_CritereCorbeille.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=400");
			w.focus();
		}
		function OuvreFenetreReactiver(Id){
			var elements = document.getElementsByClassName("check");
			Id="";
			for(var i=0, l=elements.length; i<l; i++){
				if(elements[i].checked == true){
					Id+=elements[i].name+";";
				}
			}
			if(Id!=""){
				if(window.confirm('Etes-vous sûr de vouloir réactiver ?')){
					var w=window.open("Activer_Dossier.php?Id="+Id,"PageDossier","status=no,menubar=no,scrollbars=yes,width=130,height=60");
					w.focus();
				}
			}
		}
	</script>
</head>
<?php
require("../../../Menu.php");
require("../../Fonctions.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
if($_POST){
	if(isset($_POST['Recherche_RAZ'])){
		$_SESSION['OTSupprMSN']="";
		$_SESSION['OTSupprOM']="";
		
		$_SESSION['OTSupprMSN2']="";
		$_SESSION['OTSupprOM2']="";
		
		$_SESSION['OTSupprModeFiltre']="oui";
		$_SESSION['OTSupprPage']="0";
	}
	elseif(isset($_POST['BtnRechercher'])){
		$_SESSION['OTSupprModeFiltre']="oui";
	}
	elseif(isset($_POST['Tri_RAZ'])){
		$_SESSION['TriOTSupprMSN']="";
		$_SESSION['TriOTSupprOM']="";
	}
}

?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form id="formulaire" class="test" method="POST" action="Dossier_Corbeille.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Corbeille : dossiers</td>
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
			if($_SESSION['OTSupprMSN']<>""){
				echo "<tr>";
				echo "<td>&nbsp; MSN : ".$_SESSION['OTSupprMSN']."</td>";
				echo "</tr>";
			}
			if($_SESSION['OTSupprOM']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Ordres de montages : ".$_SESSION['OTSupprOM']."</td>";
				echo "</tr>";
			}		
		?>
		<tr>
			
			<td align="center" colspan="6"><input class="Bouton" name="BtnRechercher" size="10" type="submit" value="Rechercher">&nbsp;&nbsp;&nbsp;&nbsp;
			<input class="Bouton" name="Recherche_RAZ" type="submit" value="Vider les critères de recherche"> &nbsp;&nbsp;&nbsp;&nbsp;
			<input class="Bouton" name="Tri_RAZ" type="submit" value="Effacer les tris"> &nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
		
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<?php
			if(isset($_GET['Tri'])){
				if($_GET['Tri']=="MSN"){
					$_SESSION['OTSupprTriGeneral']= str_replace("MSN ASC,","",$_SESSION['OTSupprTriGeneral']);
					$_SESSION['OTSupprTriGeneral']= str_replace("MSN DESC,","",$_SESSION['OTSupprTriGeneral']);
					$_SESSION['OTSupprTriGeneral']= str_replace("MSN ASC","",$_SESSION['OTSupprTriGeneral']);
					$_SESSION['OTSupprTriGeneral']= str_replace("MSN DESC","",$_SESSION['OTSupprTriGeneral']);
					if($_SESSION['TriOTSupprMSN']==""){$_SESSION['TriOTSupprMSN']="ASC";$_SESSION['OTSupprTriGeneral'].= "MSN ".$_SESSION['TriOTSupprMSN'].",";}
					elseif($_SESSION['TriOTSupprMSN']=="ASC"){$_SESSION['TriOTSupprMSN']="DESC";$_SESSION['OTSupprTriGeneral'].= "MSN ".$_SESSION['TriOTSupprMSN'].",";}
					else{$_SESSION['TriOTSupprMSN']="";}
				}
				if($_GET['Tri']=="OM"){
					$_SESSION['OTSupprTriGeneral']= str_replace("OrdreMontage ASC,","",$_SESSION['OTSupprTriGeneral']);
					$_SESSION['OTSupprTriGeneral']= str_replace("OrdreMontage DESC,","",$_SESSION['OTSupprTriGeneral']);
					$_SESSION['OTSupprTriGeneral']= str_replace("OrdreMontage ASC","",$_SESSION['OTSupprTriGeneral']);
					$_SESSION['OTSupprTriGeneral']= str_replace("OrdreMontage DESC","",$_SESSION['OTSupprTriGeneral']);
					if($_SESSION['TriOTSupprOM']==""){$_SESSION['TriOTSupprOM']="ASC";$_SESSION['OTSupprTriGeneral'].= "OrdreMontage ".$_SESSION['TriOTSupprOM'].",";}
					elseif($_SESSION['TriOTSupprOM']=="ASC"){$_SESSION['TriOTSupprOM']="DESC";$_SESSION['OTSupprTriGeneral'].= "OrdreMontage ".$_SESSION['TriOTSupprOM'].",";}
					else{$_SESSION['TriOTSupprOM']="";}
				}
			}
			$reqAnalyse="SELECT sp_atrot.Id ";
			$req2="SELECT Id,MSN,OrdreMontage,Designation,Id_StatutPROD,Id_StatutQUALITE,DatePROD ";
			$req="FROM sp_atrot ";
			$req.="WHERE sp_atrot.Id_Prestation=463 AND sp_atrot.Supprime=1 AND ";
			if($_SESSION['OTSupprMSN2']<>""){
				$tab = explode(";",$_SESSION['OTSupprMSN2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="sp_atrot.MSN=".$valeur." OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['OTSupprOM2']<>""){
				$tab = explode(";",$_SESSION['OTSupprOM2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="sp_atrot.OrdreMontage='".addslashes($valeur)."' OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
			if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}
			
			$result=mysqli_query($bdd,$reqAnalyse.$req);
			$nbResulta=mysqli_num_rows($result);
			
			if($_SESSION['OTSupprTriGeneral']<>""){
				$req.="ORDER BY ".substr($_SESSION['OTSupprTriGeneral'],0,-1);
			}

			$nombreDePages=ceil($nbResulta/100);
			if(isset($_GET['Page'])){$_SESSION['OTSupprPage']=$_GET['Page'];}
			$req3=" LIMIT ".($_SESSION['OTSupprPage']*100).",100";
			
			$result=mysqli_query($bdd,$req2.$req.$req3);
			$nbResulta=mysqli_num_rows($result);
		?>
		<tr>
			<td align="center" style="font-size:14px;">
				<?php
					$nbPage=0;
					if($_SESSION['OTSupprPage']>1){echo "<b> <a style='color:#00599f;' href='Dossier_Corbeille.php?Page=0'><<</a> </b>";}
					$valeurDepart=1;
					if($_SESSION['OTSupprPage']<=5){
						$valeurDepart=1;
					}
					elseif($_SESSION['OTSupprPage']>=($nombreDePages-6)){
						$valeurDepart=$nombreDePages-6;
					}
					else{
						$valeurDepart=$_SESSION['OTSupprPage']-5;
					}
					for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
						if($i<=$nombreDePages){
							if($i==($_SESSION['OTSupprPage']+1)){
								echo "<b> [ ".$i." ] </b>"; 
							}	
							else{
								echo "<b> <a style='color:#00599f;' href='Dossier_Corbeille.php?Page=".($i-1)."'>".$i."</a> </b>";
							}
						}
					}
					if($_SESSION['OTSupprPage']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Dossier_Corbeille.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
				?>
			</td>
		</tr>
		<td>
			<table width="30%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr bgcolor="#00325F">
					<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Dossier_Corbeille.php?Tri=MSN">MSN<?php if($_SESSION['TriOTSupprMSN']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriOTSupprMSN']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Dossier_Corbeille.php?Tri=OM">Ordre de montage<?php if($_SESSION['TriOTSupprOM']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriOTSupprOM']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;">
						<a href="javascript:OuvreFenetreReactiver()">
							<img src='../../../Images/Reinitilisation.png' border='0' alt='Réactiver' title='Réactiver'>
						</a>
					</td>
				</tr>
				<tr>
					<?php
						if ($nbResulta>0){
							$couleur="#ffffff";
							while($row=mysqli_fetch_array($result)){	
								?>
									<tr bgcolor="<?php echo $couleur;?>">
										<td width="5%" style="text-align:center;">&nbsp;<?php echo $row['MSN'];?></td>
										<td width="10%" style="text-align:center;"><?php echo $row['OrdreMontage'];?></td>
										<?php
											if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1' || substr($_SESSION['DroitSP'],3,1)=='1'){
										?>
										<td width="2%" align="center">
											<input class="check" type="checkbox" name="<?php echo $row['Id']; ?>" id="<?php echo $row['Id']; ?>"/>
										</td>
										<?php
											}
										?>
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
				if($_SESSION['OTSupprPage']>1){echo "<b> <a style='color:#00599f;' href='Liste_Dossier.php?Page=0'><<</a> </b>";}
				$valeurDepart=1;
				if($_SESSION['OTSupprPage']<=5){
					$valeurDepart=1;
				}
				elseif($_SESSION['OTSupprPage']>=($nombreDePages-6)){
					$valeurDepart=$nombreDePages-6;
				}
				else{
					$valeurDepart=$_SESSION['OTSupprPage']-5;
				}
				for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
					if($i<=$nombreDePages){
						if($i==($_SESSION['OTSupprPage']+1)){
							echo "<b> [ ".$i." ] </b>"; 
						}	
						else{
							echo "<b> <a style='color:#00599f;' href='Liste_Dossier.php?Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($_SESSION['OTSupprPage']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_Dossier.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
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
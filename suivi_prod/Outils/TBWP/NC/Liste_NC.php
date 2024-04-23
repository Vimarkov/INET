<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout_Critere(){
			var w=window.open("Ajout_Critere.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=270");
			w.focus();
			}
		function OuvreFenetreAjoutNC(Id_Personne){
			var w=window.open("Modif_NC.php?Mode=A&Id=0&Id_Personne="+Id_Personne,"PageNC","status=no,menubar=no,width=850,height=300");
			w.focus();
			}
		function Suppr_Critere(critere,valeur){
			var w=window.open("Ajout_Critere.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,fullscreen=yes,width=600,height=400");
			w.focus();
			}
		function OuvreFenetreModif(Id,Id_Personne){
			var w=window.open("Modif_NC.php?Mode=M&Id="+Id+"&Id_Personne="+Id_Personne,"PageUtilisateur","status=no,menubar=no,fullscreen=yes,width=850,height=300");
			w.focus();
			}
		function OuvreFenetreSuppr(Id,Id_Personne){
			if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
				var w=window.open("Modif_NC.php?Mode=S&Id="+Id+"&Id_Personne="+Id_Personne,"PageUtilisateur","status=no,menubar=no,,fullscreen=yes,width=130,height=60");
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
	<script src="../JS/../modernizr.js"></script>
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
		$_SESSION['MSN_NC']="";
		$_SESSION['Num_NC']="";
		$_SESSION['TypeDefaut']="";
		$_SESSION['ImputationAAA']="";
		$_SESSION['DateDebutNC']="";
		$_SESSION['DateFinNC']="";
		$_SESSION['Id_Createur']="";

		$_SESSION['MSN_NC2']="";
		$_SESSION['Num_NC2']="";
		$_SESSION['TypeDefaut2']="";
		$_SESSION['ImputationAAA2']="";
		$_SESSION['DateDebutNC2']="";
		$_SESSION['DateFinNC2']="";
		$_SESSION['Id_Createur2']="";
	}
	elseif(isset($_POST['BtnRechercher'])){
		$_SESSION['ModeFiltreNC']="oui";
	}
	elseif(isset($_POST['Tri_RAZ'])){
		$_SESSION['TriMSN_NC']="";
		$_SESSION['TriNum_NC']="";
		$_SESSION['TriTypeDefaut']="";
		$_SESSION['TriImputationAAA']="";
		$_SESSION['TriDateCreationNC']="";
		$_SESSION['TriId_Createur']="";
		$_SESSION['TriGeneralNC']="";
	}
}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Liste_NC.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Suivi des NC</td>
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
			if($_SESSION['MSN_NC']<>""){
				echo "<tr>";
				echo "<td>MSN : ".$_SESSION['MSN_NC']."</td>";
				echo "</tr>";
			}
			if($_SESSION['Num_NC']<>""){
				echo "<tr>";
				echo "<td>N° NC : ".$_SESSION['Num_NC']."</td>";
				echo "</tr>";
			}
			if($_SESSION['TypeDefaut']<>""){
				echo "<tr>";
				echo "<td>Type de défaut : ".$_SESSION['TypeDefaut']."</td>";
				echo "</tr>";
			}
			if($_SESSION['ImputationAAA']<>""){
				echo "<tr>";
				echo "<td>Imputation AAA : ".$_SESSION['ImputationAAA']."</td>";
				echo "</tr>";
			}
			if($_SESSION['DateDebutNC']<>""){
				echo "<tr>";
				echo "<td>Date de début : ".$_SESSION['DateDebutNC']."</td>";
				echo "</tr>";
			}
			if($_SESSION['DateFinNC']<>""){
				echo "<tr>";
				echo "<td>Date de fin : ".$_SESSION['DateFinNC']."</td>";
				echo "</tr>";
			}		
			if($_SESSION['Id_Createur']<>""){
				echo "<tr>";
				echo "<td>Créateur : ".$_SESSION['Id_Createur']."</td>";
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
				echo "<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjoutNC(".$_SESSION['Id_PersonneSP'].")'>&nbsp;Ajouter NC&nbsp;</a>";
			}
			?>
			
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<?php
			if(isset($_GET['Tri'])){
				if($_GET['Tri']=="MSN"){
					$_SESSION['TriGeneralNC']= str_replace("MSN ASC,","",$_SESSION['TriGeneralNC']);
					$_SESSION['TriGeneralNC']= str_replace("MSN DESC,","",$_SESSION['TriGeneralNC']);
					$_SESSION['TriGeneralNC']= str_replace("MSN ASC","",$_SESSION['TriGeneralNC']);
					$_SESSION['TriGeneralNC']= str_replace("MSN DESC","",$_SESSION['TriGeneralNC']);
					if($_SESSION['TriMSN_NC']==""){$_SESSION['TriMSN_NC']="ASC";$_SESSION['TriGeneralNC'].= "MSN ".$_SESSION['TriMSN_NC'].",";}
					elseif($_SESSION['TriMSN_NC']=="ASC"){$_SESSION['TriMSN_NC']="DESC";$_SESSION['TriGeneralNC'].= "MSN ".$_SESSION['TriMSN_NC'].",";}
					else{$_SESSION['TriMSN_NC']="";}
				}
				if($_GET['Tri']=="NumNC"){
					$_SESSION['TriGeneralNC']= str_replace("NumNC ASC,","",$_SESSION['TriGeneralNC']);
					$_SESSION['TriGeneralNC']= str_replace("NumNC DESC,","",$_SESSION['TriGeneralNC']);
					$_SESSION['TriGeneralNC']= str_replace("NumNC ASC","",$_SESSION['TriGeneralNC']);
					$_SESSION['TriGeneralNC']= str_replace("NumNC DESC","",$_SESSION['TriGeneralNC']);
					if($_SESSION['TriNum_NC']==""){$_SESSION['TriNum_NC']="ASC";$_SESSION['TriGeneralNC'].= "NumNC ".$_SESSION['TriNum_NC'].",";}
					elseif($_SESSION['TriNum_NC']=="ASC"){$_SESSION['TriNum_NC']="DESC";$_SESSION['TriGeneralNC'].= "NumNC ".$_SESSION['TriNum_NC'].",";}
					else{$_SESSION['TriNum_NC']="";}
				}
				if($_GET['Tri']=="TypeDefaut"){
					$_SESSION['TriGeneralNC']= str_replace("TypeDefaut ASC,","",$_SESSION['TriGeneralNC']);
					$_SESSION['TriGeneralNC']= str_replace("TypeDefaut DESC,","",$_SESSION['TriGeneralNC']);
					$_SESSION['TriGeneralNC']= str_replace("TypeDefaut ASC","",$_SESSION['TriGeneralNC']);
					$_SESSION['TriGeneralNC']= str_replace("TypeDefaut DESC","",$_SESSION['TriGeneralNC']);
					if($_SESSION['TriTypeDefaut']==""){$_SESSION['TriTypeDefaut']="ASC";$_SESSION['TriGeneralNC'].= "TypeDefaut ".$_SESSION['TriTypeDefaut'].",";}
					elseif($_SESSION['TriTypeDefaut']=="ASC"){$_SESSION['TriTypeDefaut']="DESC";$_SESSION['TriGeneralNC'].= "TypeDefaut ".$_SESSION['TriTypeDefaut'].",";}
					else{$_SESSION['TriTypeDefaut']="";}
				}
				if($_GET['Tri']=="ImputationAAA"){
					$_SESSION['TriGeneralNC']= str_replace("ImputationAAA ASC,","",$_SESSION['TriGeneralNC']);
					$_SESSION['TriGeneralNC']= str_replace("ImputationAAA DESC,","",$_SESSION['TriGeneralNC']);
					$_SESSION['TriGeneralNC']= str_replace("ImputationAAA ASC","",$_SESSION['TriGeneralNC']);
					$_SESSION['TriGeneralNC']= str_replace("ImputationAAA DESC","",$_SESSION['TriGeneralNC']);
					if($_SESSION['TriImputationAAA']==""){$_SESSION['TriImputationAAA']="ASC";$_SESSION['TriGeneralNC'].= "ImputationAAA ".$_SESSION['TriImputationAAA'].",";}
					elseif($_SESSION['TriImputationAAA']=="ASC"){$_SESSION['TriImputationAAA']="DESC";$_SESSION['TriGeneralNC'].= "ImputationAAA ".$_SESSION['TriImputationAAA'].",";}
					else{$_SESSION['TriImputationAAA']="";}
				}
				if($_GET['Tri']=="DateCreation"){
					$_SESSION['TriGeneralNC']= str_replace("DateCreation ASC,","",$_SESSION['TriGeneralNC']);
					$_SESSION['TriGeneralNC']= str_replace("DateCreation DESC,","",$_SESSION['TriGeneralNC']);
					$_SESSION['TriGeneralNC']= str_replace("DateCreation ASC","",$_SESSION['TriGeneralNC']);
					$_SESSION['TriGeneralNC']= str_replace("DateCreation DESC","",$_SESSION['TriGeneralNC']);
					if($_SESSION['TriDateCreationNC']==""){$_SESSION['TriDateCreationNC']="ASC";$_SESSION['TriGeneralNC'].= "DateCreation ".$_SESSION['TriDateCreationNC'].",";}
					elseif($_SESSION['TriDateCreationNC']=="ASC"){$_SESSION['TriDateCreationNC']="DESC";$_SESSION['TriGeneralNC'].= "DateCreation ".$_SESSION['TriDateCreationNC'].",";}
					else{$_SESSION['TriDateCreationNC']="";}
				}
				if($_GET['Tri']=="Createur"){
					$_SESSION['TriGeneralNC']= str_replace("Createur ASC,","",$_SESSION['TriGeneralNC']);
					$_SESSION['TriGeneralNC']= str_replace("Createur DESC,","",$_SESSION['TriGeneralNC']);
					$_SESSION['TriGeneralNC']= str_replace("Createur ASC","",$_SESSION['TriGeneralNC']);
					$_SESSION['TriGeneralNC']= str_replace("Createur DESC","",$_SESSION['TriGeneralNC']);
					if($_SESSION['TriId_Createur']==""){$_SESSION['TriId_Createur']="ASC";$_SESSION['TriGeneralNC'].= "Createur ".$_SESSION['TriId_Createur'].",";}
					elseif($_SESSION['TriId_Createur']=="ASC"){$_SESSION['TriId_Createur']="DESC";$_SESSION['TriGeneralNC'].= "Createur ".$_SESSION['TriId_Createur'].",";}
					else{$_SESSION['TriId_Createur']="";}
				}
			}
			
				$reqAnalyse="SELECT sp_nc.Id ";
				$req2="SELECT sp_nc.Id,sp_nc.MSN,sp_nc.NumNC,sp_nc.WO_S01Lie,sp_nc.ImputationAAA,sp_nc.DateCreation,sp_nc.Id_TypeDefaut,sp_nc.Id_Createur,";
				$req2.="(SELECT sp_typedefautnc.Libelle FROM sp_typedefautnc WHERE sp_typedefautnc.Id=sp_nc.Id_TypeDefaut) AS TypeDefaut, ";
				$req2.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_nc.Id_Createur) AS Createur ";
				$req="FROM sp_nc ";
				$req.="WHERE ";
			
				if($_SESSION['MSN_NC2']<>""){
					$tab = explode(";",$_SESSION['MSN_NC2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_nc.MSN='".addslashes($valeur)."' OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['Num_NC2']<>""){
					$tab = explode(";",$_SESSION['Num_NC2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_nc.NumNC=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['ImputationAAA2']<>""){
					$tab = explode(";",$_SESSION['ImputationAAA2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_nc.ImputationAAA=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['TypeDefaut2']<>""){
					$tab = explode(";",$_SESSION['TypeDefaut2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_nc.Id_TypeDefaut=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['Id_Createur2']<>""){
					$tab = explode(";",$_SESSION['Id_Createur2']);
					$req.="(";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="sp_nc.Id_Createur=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") AND ";
				}
				if($_SESSION['DateDebutNC2']<>"" || $_SESSION['DateFinNC2']<>""){
					$req.=" ( ";
					if($_SESSION['DateDebutNC2']<>""){
						$req.="sp_nc.DateCreation >= '". TrsfDate_($_SESSION['DateDebutNC2'])."' ";
						$req.=" AND ";
					}
					if($_SESSION['DateFinNC2']<>""){
						$req.="sp_nc.DateCreation <= '". TrsfDate_($_SESSION['DateFinNC2'])."' ";
						$req.=" ";
					}
					if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
					$req.=" ) ";
				}
				if(substr($req,strlen($req)-3)== "OR "){$req=substr($req,0,-3);}
				if($_SESSION['DateDebutNC2']<>"" || $_SESSION['DateFinNC2']<>""){
					$req.=" AND ";
				}
				if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
				if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}
				
				$result=mysqli_query($bdd,$reqAnalyse.$req);
				$nbResulta=mysqli_num_rows($result);
				
				if($_SESSION['TriGeneralNC']<>""){
					$req.="ORDER BY ".substr($_SESSION['TriGeneralNC'],0,-1);
				}

				$nombreDePages=ceil($nbResulta/200);
				if(isset($_GET['PageNC'])){$_SESSION['PageNC']=$_GET['PageNC'];}
				$req3=" LIMIT ".$_SESSION['PageNC'].",200";
				$result=mysqli_query($bdd,$req2.$req.$req3);
				$nbResulta=mysqli_num_rows($result);
		?>
		<tr>
			<td align="center" style="font-size:14px;">
				<?php
						$nbPage=0;
						if($_SESSION['PageNC']>1){echo "<b> <a style='color:#00599f;' href='Liste_NC.php?Page=0'><<</a> </b>";}
						$valeurDepart=1;
						if($_SESSION['PageNC']<=5){
							$valeurDepart=1;
						}
						elseif($_SESSION['PageNC']>=($nombreDePages-6)){
							$valeurDepart=$nombreDePages-6;
						}
						else{
							$valeurDepart=$_SESSION['PageNC']-5;
						}
						for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
							if($i<=$nombreDePages){
								if($i==($_SESSION['PageNC']+1)){
									echo "<b> [ ".$i." ] </b>"; 
								}	
								else{
									echo "<b> <a style='color:#00599f;' href='Liste_NC.php?Page=".($i-1)."'>".$i."</a> </b>";
								}
							}
						}
						if($_SESSION['PageNC']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_NC.php?Page=".($nombreDePages-1)."'>>></a> </b>";}

				?>
			</td>
		</tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr>
					<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_NC.php?Tri=MSN">MSN<?php if($_SESSION['TriMSN_NC']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriMSN_NC']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_NC.php?Tri=NumNC">N° NC<?php if($_SESSION['TriNum_NC']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriNum_NC']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="4%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_NC.php?Tri=TypeDefaut">Type défaut<?php if($_SESSION['TriTypeDefaut']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriTypeDefaut']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="4%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_NC.php?Tri=ImputationAAA">Imputation AAA<?php if($_SESSION['TriImputationAAA']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriImputationAAA']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_NC.php?Tri=DateCreation">Date création<?php if($_SESSION['TriDateCreationNC']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriDateCreationNC']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="4%" style="text-align:center;"><a style="text-decoration:none;" id="tri" href="Liste_NC.php?Tri=Createur">Créateur<?php if($_SESSION['TriId_Createur']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriId_Createur']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;"></td>
					<td class="EnTeteTableauCompetences" width="2%" style="text-align:center;"></td>
				</tr>
				<tr>
					<?php
							
							if ($nbResulta>0){
								$couleur="#ffffff";
								while($row=mysqli_fetch_array($result)){
									$imputation="";
									if($row['ImputationAAA']==0){$imputation="Non";}
									else{$imputation="Oui";}
									?>
										<tr bgcolor="<?php echo $couleur;?>">
											<td width="2%" style="text-align:center;">&nbsp;<?php echo $row['MSN'];?></td>
											<td width="6%" style="text-align:center;"><?php echo $row['NumNC'];?></td>
											<td width="4%" style="text-align:center;"><?php echo $row['TypeDefaut'];?></td>
											<td width="6%" style="text-align:center;"><?php echo $imputation;?></td>
											<td width="10%" style="text-align:center;"><?php echo $row['DateCreation'];?></td>
											<td width="10%" style="text-align:center;"><?php echo $row['Createur'];?></td>
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
					if($_SESSION['PageNC']>1){echo "<b> <a style='color:#00599f;' href='Liste_NC.php?Page=0'><<</a> </b>";}
					$valeurDepart=1;
					if($_SESSION['PageNC']<=5){
						$valeurDepart=1;
					}
					elseif($_SESSION['PageNC']>=($nombreDePages-6)){
						$valeurDepart=$nombreDePages-6;
					}
					else{
						$valeurDepart=$_SESSION['PageNC']-5;
					}
					for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
						if($i<=$nombreDePages){
							if($i==($_SESSION['PageNC']+1)){
								echo "<b> [ ".$i." ] </b>"; 
							}	
							else{
								echo "<b> <a style='color:#00599f;' href='Liste_NC.php?Page=".($i-1)."'>".$i."</a> </b>";
							}
						}
					}
					if($_SESSION['PageNC']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_NC.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
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
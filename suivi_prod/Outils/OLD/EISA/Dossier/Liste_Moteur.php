<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout_Critere(){
			var w=window.open("Ajout_CritereMoteur.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=200");
			w.focus();
		}
		function Suppr_Critere(critere,valeur){
			var w=window.open("Ajout_CritereMoteur.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=200");
			w.focus();
		}
		function OuvreFenetreAjout(){
			var w=window.open("Ajout_Moteur.php?Mode=A&Id=0","PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=850,height=400");
			w.focus();
			}
		function OuvreFenetreModif(Id){
			var w=window.open("Ajout_Moteur.php?Mode=M&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=850,height=400");
			w.focus();
			}
		function OuvreFenetreModifMoteur(Id){
			var w=window.open("Modif_Moteur.php?Mode=M&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=750,height=300");
			w.focus();
			}
		function OuvreFenetreModifEC(Id){
			var w=window.open("Modif_EC.php?Mode=M&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=700,height=500");
			w.focus();
			}
		function OuvreFenetreSuppr(Id){
			if(window.confirm('Etes-vous sûr de vouloir supprimer ?')){
			var w=window.open("Ajout_Moteur.php?Mode=S&Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,,width=60,height=40");
			w.focus();
			}
		}
		function ExcelEC(Id){
			var w=window.open("Extract_EC.php?Id="+Id,"PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
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
		$_SESSION['MOTMSN']="";
		$_SESSION['MOTTypeMoteur']="";
		$_SESSION['MOTPosteMontage']="";
		
		$_SESSION['MOTMSN2']="";
		$_SESSION['MOTTypeMoteur2']="";
		$_SESSION['MOTPosteMontage2']="";
		
		$_SESSION['MOTModeFiltre']="";
		$_SESSION['MOTPage']="0";
	}
	elseif(isset($_POST['BtnRechercher'])){
		$_SESSION['MOTModeFiltre']="oui";
	}
	elseif(isset($_POST['Tri_RAZ'])){
		$_SESSION['TriMOTMSN']="";
		$_SESSION['TriMOTTypeMoteur']="";
		$_SESSION['TriMOTPosteMontage']="";
		$_SESSION['TriMOTDate']="";
		$_SESSION['TriMOTGeneral']="";
	}
}

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Liste_Moteur.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Liste des moteurs</td>
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
			if($_SESSION['MOTMSN']<>""){
				echo "<tr>";
				echo "<td>&nbsp; MSN : ".$_SESSION['MOTMSN']."</td>";
				echo "</tr>";
			}
			if($_SESSION['MOTTypeMoteur']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Type de moteur : ".$_SESSION['MOTTypeMoteur']."</td>";
				echo "</tr>";
			}
			if($_SESSION['MOTPosteMontage']<>""){
				echo "<tr>";
				echo "<td>&nbsp; Poste de montage : ".$_SESSION['MOTPosteMontage']."</td>";
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
		<td align="left">
			<table align="left" style="width:100%;">
				<tr>
				<td align="center">
				<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjout()'>&nbsp;Ajouter un MSN&nbsp;</a>
				</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<?php
			if(isset($_GET['Tri'])){
				if($_GET['Tri']=="MOTMSN"){
					$_SESSION['TriMOTGeneral']= str_replace("MSN ASC,","",$_SESSION['TriMOTGeneral']);
					$_SESSION['TriMOTGeneral']= str_replace("MSN DESC,","",$_SESSION['TriMOTGeneral']);
					$_SESSION['TriMOTGeneral']= str_replace("MSN ASC","",$_SESSION['TriMOTGeneral']);
					$_SESSION['TriMOTGeneral']= str_replace("MSN DESC","",$_SESSION['TriMOTGeneral']);
					if($_SESSION['TriMOTMSN']==""){$_SESSION['TriMOTMSN']="ASC";$_SESSION['TriMOTGeneral'].= "MSN ".$_SESSION['TriMOTMSN'].",";}
					elseif($_SESSION['TriMOTMSN']=="ASC"){$_SESSION['TriMOTMSN']="DESC";$_SESSION['TriMOTGeneral'].= "MSN ".$_SESSION['TriMOTMSN'].",";}
					else{$_SESSION['TriMOTMSN']="";}
				}
				if($_GET['Tri']=="MOTTypeMoteur"){
					$_SESSION['TriMOTGeneral']= str_replace("TypeMoteur ASC,","",$_SESSION['TriMOTGeneral']);
					$_SESSION['TriMOTGeneral']= str_replace("TypeMoteur DESC,","",$_SESSION['TriMOTGeneral']);
					$_SESSION['TriMOTGeneral']= str_replace("TypeMoteur ASC","",$_SESSION['TriMOTGeneral']);
					$_SESSION['TriMOTGeneral']= str_replace("TypeMoteur DESC","",$_SESSION['TriMOTGeneral']);
					if($_SESSION['TriMOTTypeMoteur']==""){$_SESSION['TriMOTTypeMoteur']="ASC";$_SESSION['TriMOTGeneral'].= "TypeMoteur ".$_SESSION['TriMOTTypeMoteur'].",";}
					elseif($_SESSION['TriMOTTypeMoteur']=="ASC"){$_SESSION['TriMOTTypeMoteur']="DESC";$_SESSION['TriMOTGeneral'].= "TypeMoteur ".$_SESSION['TriMOTTypeMoteur'].",";}
					else{$_SESSION['TriMOTTypeMoteur']="";}
				}
				if($_GET['Tri']=="MOTPosteMontage"){
					$_SESSION['TriMOTGeneral']= str_replace("PosteMontage ASC,","",$_SESSION['TriMOTGeneral']);
					$_SESSION['TriMOTGeneral']= str_replace("PosteMontage DESC,","",$_SESSION['TriMOTGeneral']);
					$_SESSION['TriMOTGeneral']= str_replace("PosteMontage ASC","",$_SESSION['TriMOTGeneral']);
					$_SESSION['TriMOTGeneral']= str_replace("PosteMontage DESC","",$_SESSION['TriMOTGeneral']);
					if($_SESSION['TriMOTPosteMontage']==""){$_SESSION['TriMOTPosteMontage']="ASC";$_SESSION['TriMOTGeneral'].= "PosteMontage ".$_SESSION['TriMOTPosteMontage'].",";}
					elseif($_SESSION['TriMOTPosteMontage']=="ASC"){$_SESSION['TriMOTPosteMontage']="DESC";$_SESSION['TriMOTGeneral'].= "PosteMontage ".$_SESSION['TriMOTPosteMontage'].",";}
					else{$_SESSION['TriMOTPosteMontage']="";}
				}
				if($_GET['Tri']=="Date"){
					$_SESSION['TriMOTGeneral']= str_replace("DateMontage ASC,","",$_SESSION['TriMOTGeneral']);
					$_SESSION['TriMOTGeneral']= str_replace("DateMontage DESC,","",$_SESSION['TriMOTGeneral']);
					$_SESSION['TriMOTGeneral']= str_replace("DateMontage ASC","",$_SESSION['TriMOTGeneral']);
					$_SESSION['TriMOTGeneral']= str_replace("DateMontage DESC","",$_SESSION['TriMOTGeneral']);
					if($_SESSION['TriMOTDate']==""){$_SESSION['TriMOTDate']="ASC";$_SESSION['TriMOTGeneral'].= "DateMontage ".$_SESSION['TriMOTDate'].",";}
					elseif($_SESSION['TriMOTDate']=="ASC"){$_SESSION['TriMOTDate']="DESC";$_SESSION['TriMOTGeneral'].= "DateMontage ".$_SESSION['TriMOTDate'].",";}
					else{$_SESSION['TriMOTDate']="";}
				}
			}
			$reqAnalyse="SELECT sp_atrmoteur.Id ";
			$req2="SELECT Id,MSN,TypeMoteur,PosteMontage,DateMontage ";
			$req="FROM sp_atrmoteur WHERE Id_Prestation=463 AND ";
			if($_SESSION['MOTMSN2']<>""){
				$tab = explode(";",$_SESSION['MOTMSN2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="MSN=".$valeur." OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['MOTTypeMoteur2']<>""){
				$tab = explode(";",$_SESSION['MOTTypeMoteur2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="TypeMoteur='".$valeur."' OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if($_SESSION['MOTPosteMontage2']<>""){
				$tab = explode(";",$_SESSION['MOTPosteMontage2']);
				$req.="(";
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req.="PosteMontage='".$valeur."' OR ";
					 }
				}
				$req=substr($req,0,-3);
				$req.=") AND ";
			}
			if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
			if(substr($req,strlen($req)-6)== "WHERE "){$req=substr($req,0,-6);}

			$result=mysqli_query($bdd,$reqAnalyse.$req);
			$nbResulta=mysqli_num_rows($result);
			
			if($_SESSION['TriMOTGeneral']<>""){
				$req.="ORDER BY ".substr($_SESSION['TriMOTGeneral'],0,-1);
			}

			$nombreDePages=ceil($nbResulta/100);
			if(isset($_GET['Page'])){$_SESSION['MOTPage']=$_GET['Page'];}
			$req3=" LIMIT ".($_SESSION['MOTPage']*100).",100";

			$result=mysqli_query($bdd,$req2.$req.$req3);
			$nbResulta=mysqli_num_rows($result);
		?>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				$nbPage=0;
				if($_SESSION['MOTPage']>1){echo "<b> <a style='color:#00599f;' href='Liste_Moteur.php?Page=0'><<</a> </b>";}
				$valeurDepart=1;
				if($_SESSION['MOTPage']<=5){
					$valeurDepart=1;
				}
				elseif($_SESSION['MOTPage']>=($nombreDePages-6)){
					$valeurDepart=$nombreDePages-6;
				}
				else{
					$valeurDepart=$_SESSION['MOTPage']-5;
				}
				for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
					if($i<=$nombreDePages){
						if($i==($_SESSION['MOTPage']+1)){
							echo "<b> [ ".$i." ] </b>"; 
						}	
						else{
							echo "<b> <a style='color:#00599f;' href='Liste_Moteur.php?Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($_SESSION['MOTPage']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_Moteur.php?Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr><td>
		<table cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" style="width:60%;">
			<tr bgcolor="#00325F">
				<td class="EnTeteTableauCompetences" width="6%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Moteur.php?Tri=MOTMSN">MSN<?php if($_SESSION['TriMOTMSN']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriMOTMSN']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="12%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Moteur.php?Tri=MOTTypeMoteur">Type moteur<?php if($_SESSION['TriMOTTypeMoteur']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriMOTTypeMoteur']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="12%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Moteur.php?Tri=MOTPosteMontage">Poste montage<?php if($_SESSION['TriMOTPosteMontage']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriMOTPosteMontage']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="12%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="Liste_Moteur.php?Tri=Date">Date montage<?php if($_SESSION['TriMOTDate']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriMOTDate']=="ASC"){echo "&darr;";}?></a></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="2%"></td>
				<td class="EnTeteTableauCompetences" width="4%"></td>
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
								<td width="12%">&nbsp;<?php echo $row['TypeMoteur'];?></td>
								<td width="12%">&nbsp;<?php echo $row['PosteMontage'];?></td>
								<td width="12%">&nbsp;<?php echo AfficheDateJJ_MM_AAAA($row['DateMontage']);?></td>
								<td width="2%" align="center">
									<a href="javascript:OuvreFenetreModif(<?php echo $row['Id']; ?>)">
									<img src='../../../Images/Modif.gif' border='0' alt='Modifier' title='Modifier'>
									</a>
								</td>
								<td width="2%" align="center">
									<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreModifMoteur(<?php echo $row['Id']; ?>)">&nbsp;Moteurs&nbsp;</a>
								</td>
								<td width="2%" align="center">
									<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreModifEC(<?php echo $row['Id']; ?>)">&nbsp;EC&nbsp;</a>
								</td>
								<td width="2%" align="center">
									<a style="text-decoration:none;" class="Bouton" href="javascript:ExcelEC(<?php echo $row['Id']; ?>)">&nbsp;Excel EC&nbsp;</a>
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
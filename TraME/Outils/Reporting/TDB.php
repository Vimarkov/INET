<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout_Critere(){
			var w=window.open("Ajout_CritereTDB.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=250");
			w.focus();
		}
		function Suppr_Critere(critere,valeur){
			var w=window.open("Ajout_CritereTDB.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=200");
			w.focus();
		}
		function Excel(){
			var w=window.open("Extract_TDB.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
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
require("../../Menu.php");
require("../Fonctions.php");

$_SESSION['Formulaire']="Reporting/TDB.php";
if($_POST){
	if(isset($_POST['Recherche_RAZ'])){
		$_SESSION['TDB_DateDebut']="";
		$_SESSION['TDB_DateFin']="";
		$_SESSION['TDB_WP']="";
		$_SESSION['TDB_Preparateur']="";
		
		$_SESSION['TDB_DateDebut2']="";
		$_SESSION['TDB_DateFin2']="";
		$_SESSION['TDB_WP2']="";
		$_SESSION['TDB_Preparateur2']="";

		$_SESSION['TDB_ModeFiltre']="oui";
		$_SESSION['TDB_Page']="0";
	}
	elseif(isset($_POST['BtnRechercher'])){
		$_SESSION['TDB_ModeFiltre']="oui";
	}
}

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
?>

<?php Ecrire_Code_JS_Init_Date(); 
?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="TDB.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "DASHBOARD";}else{echo "TABLEAU DE BORD";} ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td><b>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Search criteria :";}else{echo "Critères de recherche :";}?></b></td>
			<td align="right">
			<a style="text-decoration:none;" href="javascript:OuvreFenetreAjout_Critere()">&nbsp;<img src="../../Images/Plus2.png" border="0" alt="<?php if($_SESSION['Langue']=="EN"){echo "Add criteria";}else{echo "Ajouter critères";}?>" title="<?php if($_SESSION['Langue']=="EN"){echo "Add criteria";}else{echo "Ajouter critères";}?>">&nbsp;&nbsp;</a>
			</td>
		</tr>
		<?php
			if($_SESSION['TDB_DateDebut']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Start date : ".$_SESSION['TDB_DateDebut']."</td>";
				}
				else{
					echo "<td>&nbsp; Date de début : ".$_SESSION['TDB_DateDebut']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['TDB_DateFin']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; End date : ".$_SESSION['TDB_DateFin']."</td>";
				}
				else{
					echo "<td>&nbsp; Date de fin : ".$_SESSION['TDB_DateFin']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['TDB_WP']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Workpackages : ".$_SESSION['TDB_WP']."</td>";
				}
				else{
					echo "<td>&nbsp; Workpackages : ".$_SESSION['TDB_WP']."</td>";
				}
				echo "</tr>";
			}
			if($_SESSION['TDB_Preparateur']<>""){
				echo "<tr>";
				if($_SESSION['Langue']=="EN"){
					echo "<td>&nbsp; Manufacturing Engineer : ".$_SESSION['TDB_Preparateur']."</td>";
				}
				else{
					echo "<td>&nbsp; Préparateurs : ".$_SESSION['TDB_Preparateur']."</td>";
				}
				echo "</tr>";
			}
		?>
		<tr>
			
			<td align="center" colspan="6"><input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Search";}else{echo "Rechercher";}?>">&nbsp;&nbsp;&nbsp;&nbsp;
			<input class="Bouton" name="Recherche_RAZ" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Clear search criteria";}else{echo "Vider les critères de recherche";}?>"> &nbsp;&nbsp;&nbsp;&nbsp;
			<a style="text-decoration:none;" class="Bouton" href="javascript:Excel()">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Excel";}else{echo "Extract Excel";}?>&nbsp;</a>
			</td>
		</tr>
		
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<?php
			
			if($_SESSION['TDB_ModeFiltre']=="oui"){
				$req2="SELECT trame_wp.Id, trame_wp.Libelle AS Workpackage ";
				$req="FROM trame_wp WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Supprime=0 ";
				if($_SESSION['TDB_WP2']<>""){
					$tab = explode(";",$_SESSION['TDB_WP2']);
					$req.="AND (";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="Id=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") ";
				}
				$req.="ORDER BY Workpackage";
				$result=mysqli_query($bdd,$req2.$req);
				$nbResulta=mysqli_num_rows($result);
			}
			
		?>
		<table cellpadding="0" cellspacing="0" align="left" class="GeneralInfo" style="width:100%;">
			<tr bgcolor="#00325F">
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="20%"><?php if($_SESSION['Langue']=="EN"){echo "Workpackage";}else{echo "Workpackage";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="8%"><?php if($_SESSION['Langue']=="EN"){echo "VALIDATED (H)";}else{echo "VALIDE (H)";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="8%"><?php if($_SESSION['Langue']=="EN"){echo "TO BE VALIDATED (H)";}else{echo "A VALIDER (H)";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="8%"><?php if($_SESSION['Langue']=="EN"){echo "IN PROGRESS (H)";}else{echo "EN COURS (H)";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="8%"><?php if($_SESSION['Langue']=="EN"){echo "RETURN (H)";}else{echo "RETOURNE (H)";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="8%"><?php if($_SESSION['Langue']=="EN"){echo "TIME SPENT (H)";}else{echo "TEMPS PASSE (H))";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="15%"><?php if($_SESSION['Langue']=="EN"){echo "RATIO=(Time Validated + Time to Validate) / Time spent";}else{echo "RATIO=(Temps Validé + Temps à Valider) / Temps Passé";} ?></td>
			</tr>
			<?php
				$valide=0;
				$AValider=0;
				$EnCours=0;
				$Refuse=0;
				$TempsPasse=0;
				$Productivite=0;
				$couleur="#ffffff";
				if($_SESSION['TDB_ModeFiltre']=="oui"){
					if ($nbResulta>0){
						$couleur="#ffffff";
						
						$req2="SELECT trame_travaileffectue.Id_WP, SUM(trame_travaileffectue_uo.TempsAlloue) AS TempsAlloue FROM trame_travaileffectue_uo ";
						$req2.="LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
						$req2.="WHERE trame_travaileffectue_uo.TravailFait=1 AND trame_travaileffectue.Statut='VALIDE' ";
						if($_SESSION['TDB_Preparateur2']<>""){
							$tab = explode(";",$_SESSION['TDB_Preparateur2']);
							$req2.="AND (";
							foreach($tab as $valeur){
								 if($valeur<>""){
									$req2.="trame_travaileffectue.Id_Preparateur=".$valeur." OR ";
								 }
							}
							$req2=substr($req2,0,-3);
							$req2.=") ";
						}
						if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0'){
							$req2.=" AND trame_travaileffectue.Id_Preparateur=".$_SESSION['Id_PersonneTR']." ";
						}
						if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
							$req2.=" AND ( ";
							if($_SESSION['TDB_DateDebut2']<>""){
								$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
								$req2.=" AND ";
							}
							if($_SESSION['TDB_DateFin2']<>""){
								$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
								$req2.=" ";
							}
							if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
							$req2.=" ) ";
						}
						$req2.="GROUP BY trame_travaileffectue.Id_WP";
						$resultV=mysqli_query($bdd,$req2);
						$nbResultaV=mysqli_num_rows($resultV);
						
						$req2="SELECT trame_travaileffectue.Id_WP,SUM(trame_travaileffectue_uo.TempsAlloue) AS TempsAlloue FROM trame_travaileffectue_uo ";
						$req2.="LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
						$req2.="WHERE trame_travaileffectue_uo.TravailFait=1 AND trame_travaileffectue.Statut='A VALIDER' ";
						if($_SESSION['TDB_Preparateur2']<>""){
							$tab = explode(";",$_SESSION['TDB_Preparateur2']);
							$req2.="AND (";
							foreach($tab as $valeur){
								 if($valeur<>""){
									$req2.="trame_travaileffectue.Id_Preparateur=".$valeur." OR ";
								 }
							}
							$req2=substr($req2,0,-3);
							$req2.=") ";
						}
						if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0'){
							$req2.=" AND trame_travaileffectue.Id_Preparateur=".$_SESSION['Id_PersonneTR']." ";
						}
						if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
							$req2.=" AND ( ";
							if($_SESSION['TDB_DateDebut2']<>""){
								$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
								$req2.=" AND ";
							}
							if($_SESSION['TDB_DateFin2']<>""){
								$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
								$req2.=" ";
							}
							if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
							$req2.=" ) ";
						}
						$req2.="GROUP BY trame_travaileffectue.Id_WP";
						$resultAV=mysqli_query($bdd,$req2);
						$nbResultaAV=mysqli_num_rows($resultAV);
						
						$req2="SELECT trame_travaileffectue.Id_WP,SUM(trame_travaileffectue_uo.TempsAlloue) AS TempsAlloue FROM trame_travaileffectue_uo ";
						$req2.="LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
						$req2.="WHERE trame_travaileffectue_uo.TravailFait=1 AND (trame_travaileffectue.Statut='EN COURS' OR trame_travaileffectue.Statut='BLOQUE' OR trame_travaileffectue.Statut='AC' OR trame_travaileffectue.Statut='REC' OR trame_travaileffectue.Statut='CONTROLE') ";
						if($_SESSION['TDB_Preparateur2']<>""){
							$tab = explode(";",$_SESSION['TDB_Preparateur2']);
							$req2.="AND (";
							foreach($tab as $valeur){
								 if($valeur<>""){
									$req2.="trame_travaileffectue.Id_Preparateur=".$valeur." OR ";
								 }
							}
							$req2=substr($req2,0,-3);
							$req2.=") ";
						}
						if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0'){
							$req2.=" AND trame_travaileffectue.Id_Preparateur=".$_SESSION['Id_PersonneTR']." ";
						}
						if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
							$req2.=" AND ( ";
							if($_SESSION['TDB_DateDebut2']<>""){
								$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
								$req2.=" AND ";
							}
							if($_SESSION['TDB_DateFin2']<>""){
								$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
								$req2.=" ";
							}
							if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
							$req2.=" ) ";
						}
						$req2.="GROUP BY trame_travaileffectue.Id_WP";
						$resultEC=mysqli_query($bdd,$req2);
						$nbResultaEC=mysqli_num_rows($resultEC);
						
						$req2="SELECT trame_travaileffectue.Id_WP,SUM(trame_travaileffectue_uo.TempsAlloue) AS TempsAlloue FROM trame_travaileffectue_uo ";
						$req2.="LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
						$req2.="WHERE trame_travaileffectue_uo.TravailFait=1 AND trame_travaileffectue.Statut='REFUSE' ";
						if($_SESSION['TDB_Preparateur2']<>""){
							$tab = explode(";",$_SESSION['TDB_Preparateur2']);
							$req2.="AND (";
							foreach($tab as $valeur){
								 if($valeur<>""){
									$req2.="trame_travaileffectue.Id_Preparateur=".$valeur." OR ";
								 }
							}
							$req2=substr($req2,0,-3);
							$req2.=") ";
						}
						if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0'){
							$req2.=" AND trame_travaileffectue.Id_Preparateur=".$_SESSION['Id_PersonneTR']." ";
						}
						if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
							$req2.=" AND ( ";
							if($_SESSION['TDB_DateDebut2']<>""){
								$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
								$req2.=" AND ";
							}
							if($_SESSION['TDB_DateFin2']<>""){
								$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
								$req2.=" ";
							}
							if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
							$req2.=" ) ";
						}
						$req2.="GROUP BY trame_travaileffectue.Id_WP";
						$resultR=mysqli_query($bdd,$req2);
						$nbResultaR=mysqli_num_rows($resultR);
						
						$reqPlanning="SELECT Id FROM trame_prestation WHERE Planning=1 AND Id=".$_SESSION['Id_PrestationTR'];
						$resultPlanning=mysqli_query($bdd,$reqPlanning);
						$nbResultaPlanning=mysqli_num_rows($resultPlanning);
						if($nbResultaPlanning>0){
							$req2="SELECT Id_WP, SUM(TempsPasse) AS NbHeure FROM trame_travaileffectue ";
							$req2.="WHERE ";
							if($_SESSION['TDB_Preparateur2']<>""){
								$tab = explode(";",$_SESSION['TDB_Preparateur2']);
								$req2.=" (";
								foreach($tab as $valeur){
									 if($valeur<>""){
										$req2.="trame_travaileffectue.Id_Preparateur=".$valeur." OR ";
									 }
								}
								$req2=substr($req2,0,-3);
								$req2.=") AND ";
							}
							if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0'){
								$req2.=" trame_travaileffectue.Id_Preparateur=".$_SESSION['Id_PersonneTR']." AND ";
							}
							if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
								$req2.=" ( ";
								if($_SESSION['TDB_DateDebut2']<>""){
									$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
									$req2.=" AND ";
								}
								if($_SESSION['TDB_DateFin2']<>""){
									$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
									$req2.=" ";
								}
								if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
								$req2.=" ) ";
							}
							if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
							$req2.="GROUP BY Id_WP";
							$resultTP=mysqli_query($bdd,$req2);
							$nbResultaTP=mysqli_num_rows($resultTP);
						}
						else{
							$req2="SELECT trame_planning.Id_WP, SUM(((HOUR(HeureFin)*60)+ MINUTE(HeureFin)) - ((HOUR(HeureDebut)*60)+ MINUTE(HeureDebut))) AS NbHeure FROM trame_planning ";
							$req2.="WHERE ";
							if($_SESSION['TDB_Preparateur2']<>""){
								$tab = explode(";",$_SESSION['TDB_Preparateur2']);
								$req2.="AND (";
								foreach($tab as $valeur){
									 if($valeur<>""){
										$req2.="trame_planning.Id_Preparateur=".$valeur." OR ";
									 }
								}
								$req2=substr($req2,0,-3);
								$req2.=") ";
							}
							if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0'){
								$req2.=" trame_planning.Id_Preparateur=".$_SESSION['Id_PersonneTR']." AND ";
							}
							if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
								$req2.=" ( ";
								if($_SESSION['TDB_DateDebut2']<>""){
									$req2.="trame_planning.DateDebut >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
									$req2.=" AND ";
								}
								if($_SESSION['TDB_DateFin2']<>""){
									$req2.="trame_planning.DateDebut <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
									$req2.=" ";
								}
								if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
								$req2.=" ) ";
							}
							if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
							$req2.="GROUP BY trame_planning.Id_WP";
							$resultTP=mysqli_query($bdd,$req2);
							$nbResultaTP=mysqli_num_rows($resultTP);
						}
						while($row=mysqli_fetch_array($result)){
							?>	
							<?php
							$sommeV=0;
							if($nbResultaV<>0){
								mysqli_data_seek($resultV,0);
								while($row2=mysqli_fetch_array($resultV)){
									if($row2['Id_WP']==$row['Id']){
										$valide=$valide+floatval($row2['TempsAlloue']);
										$sommeV=floatval($row2['TempsAlloue']);
									}
								}
							}
							?>
								
							<?php
							$sommeAV=0;
							if($nbResultaAV<>0){
								mysqli_data_seek($resultAV,0);
								while($row2=mysqli_fetch_array($resultAV)){
									if($row2['Id_WP']==$row['Id']){
										$AValider=$AValider+floatval($row2['TempsAlloue']);
										$sommeAV=floatval($row2['TempsAlloue']);
									}
								}
							}
							?>
								
							<?php
							$sommeEC=0;
							if($nbResultaEC<>0){
								mysqli_data_seek($resultEC,0);
								while($row2=mysqli_fetch_array($resultEC)){
									if($row2['Id_WP']==$row['Id']){
										$EnCours=$EnCours+floatval($row2['TempsAlloue']);
										$sommeEC=floatval($row2['TempsAlloue']);
									}
								}
							}
							?>
								
							<?php
							$sommeR=0;
							if($nbResultaR<>0){
								mysqli_data_seek($resultR,0);
								while($row2=mysqli_fetch_array($resultR)){
									if($row2['Id_WP']==$row['Id']){
										$Refuse=$Refuse+floatval($row2['TempsAlloue']);
										$sommeR=floatval($row2['TempsAlloue']);
									}
								}
							}
							?>
								
							<?php
							$sommeTP=0;
							if($nbResultaTP<>0){
								mysqli_data_seek($resultTP,0);
								while($row2=mysqli_fetch_array($resultTP)){
									if($row2['Id_WP']==$row['Id']){
										$TempsPasse=$TempsPasse+floatval($row2['NbHeure']);
										$sommeTP=floatval($row2['NbHeure']);
									}
								}
							}
							if($nbResultaPlanning==0){
								$sommeTP = $sommeTP/60;
							}
								if($sommeV>0 || $sommeAV>0 || $sommeEC>0 || $sommeR>0 || $sommeTP>0){
									if($couleur=="#ffffff"){$couleur="#E1E1D7";}
									else{$couleur="#ffffff";}
							?>
							<tr bgcolor="<?php echo $couleur;?>">
								<td width="20%">&nbsp;<?php echo $row['Workpackage'];?></td>
								<td width="8%"><?php echo $sommeV;?></td>
								<td width="8%"><?php echo $sommeAV;?></td>
								<td width="8%"><?php echo $sommeEC;?></td>
								<td width="8%"><?php echo $sommeR;?></td>
								<td width="8%"><?php echo $sommeTP;?></td>
								<td width="15%"><?php if($sommeTP>0){echo round(($sommeV+$sommeAV)/$sommeTP,2);}else{ echo 0;} ?></td>
							</tr>
							<?php
								}
						}
					}
				}
				if($couleur=="#ffffff"){$couleur="#E1E1D7";}
				else{$couleur="#ffffff";}
				if($nbResultaPlanning==0){
					$TempsPasse = $TempsPasse/60;
				}
				if($TempsPasse>0){
					$Productivite=round(($valide+$AValider)/$TempsPasse,2);
				}
			?>
			<tr bgcolor="<?php echo $couleur;?>">
				<td width="20%" style="Font-Weight:Bold;">&nbsp;Total</td>
				<td width="8%" style="Font-Weight:Bold;"><?php echo $valide;?></td>
				<td width="8%" style="Font-Weight:Bold;"><?php echo $AValider;?></td>
				<td width="8%" style="Font-Weight:Bold;"><?php echo $EnCours; ?></td>
				<td width="8%" style="Font-Weight:Bold;"><?php echo $Refuse; ?></td>
				<td width="8%" style="Font-Weight:Bold;"><?php echo $TempsPasse; ?></td>
				<td width="15%" style="Font-Weight:Bold;"><?php echo round($Productivite,2); ?></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr><td height="16"></td></tr>
	<?php
	if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1'){
	?>
	<tr>
		<td>
		<?php
			
			if($_SESSION['TDB_ModeFiltre']=="oui"){
				$req2="SELECT DISTINCT new_rh_etatcivil.Id,CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne ";
				$req="FROM trame_travaileffectue INNER JOIN new_rh_etatcivil ON trame_travaileffectue.Id_Preparateur=new_rh_etatcivil.Id ";
				$req.="WHERE trame_travaileffectue.Id_Preparateur>0 AND trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
				if($_SESSION['TDB_Preparateur2']<>""){
					$tab = explode(";",$_SESSION['TDB_Preparateur2']);
					$req.="AND (";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="new_rh_etatcivil.Id=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") ";
				}
				$req.="ORDER BY Personne ";
				$result=mysqli_query($bdd,$req2.$req);
				$nbResulta=mysqli_num_rows($result);
			}
		?>
		<table cellpadding="0" cellspacing="0" align="left" class="GeneralInfo" style="width:100%;">
			<tr bgcolor="#00325F">
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="20%"><?php if($_SESSION['Langue']=="EN"){echo "Manufacturing Engineer";}else{echo "Préparateur";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="8%"><?php if($_SESSION['Langue']=="EN"){echo "VALIDATED (H)";}else{echo "VALIDE (H)";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="8%"><?php if($_SESSION['Langue']=="EN"){echo "TO BE VALIDATED (H)";}else{echo "A VALIDER (H)";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="8%"><?php if($_SESSION['Langue']=="EN"){echo "IN PROGRESS (H)";}else{echo "EN COURS (H)";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="8%"><?php if($_SESSION['Langue']=="EN"){echo "RETURN (H)";}else{echo "RETOURNE (H)";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="8%"><?php if($_SESSION['Langue']=="EN"){echo "TIME SPENT (H)";}else{echo "TEMPS PASSE (H)";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="15%"><?php if($_SESSION['Langue']=="EN"){echo "RATIO=(Time Validated + Time to Validate) / Time spent";}else{echo "RATIO=(Temps Validé + Temps à Valider) / Temps Passé";} ?></td>
			</tr>
			<?php
				$valide=0;
				$AValider=0;
				$EnCours=0;
				$Refuse=0;
				$TempsPasse=0;
				$Productivite=0;
				$couleur="#ffffff";
				if($_SESSION['TDB_ModeFiltre']=="oui"){
					$req2="SELECT trame_travaileffectue.Id_Preparateur,SUM(trame_travaileffectue_uo.TempsAlloue) AS TempsAlloue FROM trame_travaileffectue_uo ";
					$req2.="LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
					$req2.="WHERE trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." AND trame_travaileffectue_uo.TravailFait=1 AND trame_travaileffectue.Statut='VALIDE' ";
					if($_SESSION['TDB_WP2']<>""){
						$tab = explode(";",$_SESSION['TDB_WP2']);
						$req2.="AND (";
						foreach($tab as $valeur){
							 if($valeur<>""){
								$req2.="trame_travaileffectue.Id_WP=".$valeur." OR ";
							 }
						}
						$req2=substr($req2,0,-3);
						$req2.=") ";
					}
					if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
						$req2.=" AND ( ";
						if($_SESSION['TDB_DateDebut2']<>""){
							$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
							$req2.=" AND ";
						}
						if($_SESSION['TDB_DateFin2']<>""){
							$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
							$req2.=" ";
						}
						if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
						$req2.=" ) ";
					}
					$req2.="GROUP BY trame_travaileffectue.Id_Preparateur";
					$resultV=mysqli_query($bdd,$req2);
					$nbResultaV=mysqli_num_rows($resultV);
					
					$req2="SELECT trame_travaileffectue.Id_Preparateur,SUM(trame_travaileffectue_uo.TempsAlloue) AS TempsAlloue FROM trame_travaileffectue_uo ";
					$req2.="LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
					$req2.="WHERE trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." AND trame_travaileffectue_uo.TravailFait=1 AND trame_travaileffectue.Statut='A VALIDER' ";
					if($_SESSION['TDB_WP2']<>""){
						$tab = explode(";",$_SESSION['TDB_WP2']);
						$req2.="AND (";
						foreach($tab as $valeur){
							 if($valeur<>""){
								$req2.="trame_travaileffectue.Id_WP=".$valeur." OR ";
							 }
						}
						$req2=substr($req2,0,-3);
						$req2.=") ";
					}
					if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
						$req2.=" AND ( ";
						if($_SESSION['TDB_DateDebut2']<>""){
							$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
							$req2.=" AND ";
						}
						if($_SESSION['TDB_DateFin2']<>""){
							$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
							$req2.=" ";
						}
						if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
						$req2.=" ) ";
					}
					$req2.="GROUP BY trame_travaileffectue.Id_Preparateur";
					$resultAV=mysqli_query($bdd,$req2);
					$nbResultaAV=mysqli_num_rows($resultAV);
					
					$req2="SELECT trame_travaileffectue.Id_Preparateur,SUM(trame_travaileffectue_uo.TempsAlloue) AS TempsAlloue FROM trame_travaileffectue_uo ";
					$req2.="LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
					$req2.="WHERE trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." AND trame_travaileffectue_uo.TravailFait=1 AND (trame_travaileffectue.Statut='EN COURS' OR trame_travaileffectue.Statut='BLOQUE' OR trame_travaileffectue.Statut='AC' OR trame_travaileffectue.Statut='REC' OR trame_travaileffectue.Statut='CONTROLE') ";
					if($_SESSION['TDB_WP2']<>""){
						$tab = explode(";",$_SESSION['TDB_WP2']);
						$req2.="AND (";
						foreach($tab as $valeur){
							 if($valeur<>""){
								$req2.="trame_travaileffectue.Id_WP=".$valeur." OR ";
							 }
						}
						$req2=substr($req2,0,-3);
						$req2.=") ";
					}
					if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
						$req2.=" AND ( ";
						if($_SESSION['TDB_DateDebut2']<>""){
							$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
							$req2.=" AND ";
						}
						if($_SESSION['TDB_DateFin2']<>""){
							$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
							$req2.=" ";
						}
						if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
						$req2.=" ) ";
					}
					$req2.="GROUP BY trame_travaileffectue.Id_Preparateur";
					$resultEC=mysqli_query($bdd,$req2);
					$nbResultaEC=mysqli_num_rows($resultEC);
					
					$req2="SELECT trame_travaileffectue.Id_Preparateur, SUM(trame_travaileffectue_uo.TempsAlloue) AS TempsAlloue FROM trame_travaileffectue_uo ";
					$req2.="LEFT JOIN trame_travaileffectue ON trame_travaileffectue_uo.Id_TravailEffectue=trame_travaileffectue.Id ";
					$req2.="WHERE trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." AND trame_travaileffectue_uo.TravailFait=1 AND trame_travaileffectue.Statut='REFUSE' ";
					if($_SESSION['TDB_WP2']<>""){
						$tab = explode(";",$_SESSION['TDB_WP2']);
						$req2.="AND (";
						foreach($tab as $valeur){
							 if($valeur<>""){
								$req2.="trame_travaileffectue.Id_WP=".$valeur." OR ";
							 }
						}
						$req2=substr($req2,0,-3);
						$req2.=") ";
					}
					if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
						$req2.=" AND ( ";
						if($_SESSION['TDB_DateDebut2']<>""){
							$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
							$req2.=" AND ";
						}
						if($_SESSION['TDB_DateFin2']<>""){
							$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
							$req2.=" ";
						}
						if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
						$req2.=" ) ";
					}
					$req2.="GROUP BY trame_travaileffectue.Id_Preparateur";
					$resultR=mysqli_query($bdd,$req2);
					$nbResultaR=mysqli_num_rows($resultR);
					
					$reqPlanning="SELECT Id FROM trame_prestation WHERE Planning=1 AND Id=".$_SESSION['Id_PrestationTR'];
					$resultPlanning=mysqli_query($bdd,$reqPlanning);
					$nbResultaPlanning=mysqli_num_rows($resultPlanning);
					if($nbResultaPlanning>0){
						$req2="SELECT trame_travaileffectue.Id_Preparateur, SUM(TempsPasse) AS NbHeure FROM trame_travaileffectue ";
						$req2.="WHERE trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
						if($_SESSION['TDB_WP2']<>""){
							$tab = explode(";",$_SESSION['TDB_WP2']);
							$req2.="AND (";
							foreach($tab as $valeur){
								 if($valeur<>""){
									$req2.="trame_travaileffectue.Id_WP=".$valeur." OR ";
								 }
							}
							$req2=substr($req2,0,-3);
							$req2.=") ";
						}
						if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0'){
							$req2.=" AND trame_travaileffectue.Id_Preparateur=".$_SESSION['Id_PersonneTR']." ";
						}
						if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
							$req2.=" AND ( ";
							if($_SESSION['TDB_DateDebut2']<>""){
								$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
								$req2.=" AND ";
							}
							if($_SESSION['TDB_DateFin2']<>""){
								$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
								$req2.=" ";
							}
							if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
							$req2.=" ) ";
						}
						$req2.="GROUP BY trame_travaileffectue.Id_Preparateur";
						$resultTP=mysqli_query($bdd,$req2);
						$nbResultaTP=mysqli_num_rows($resultTP);
					}
					else{
						$req2="SELECT trame_planning.Id_Preparateur, SUM(((HOUR(HeureFin)*60)+ MINUTE(HeureFin)) - ((HOUR(HeureDebut)*60)+ MINUTE(HeureDebut))) AS NbHeure FROM trame_planning ";
						$req2.="WHERE trame_planning.Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
						if($_SESSION['TDB_WP2']<>""){
							$tab = explode(";",$_SESSION['TDB_WP2']);
							$req2.="AND (";
							foreach($tab as $valeur){
								 if($valeur<>""){
									$req2.="trame_planning.Id_WP=".$valeur." OR ";
								 }
							}
							$req2=substr($req2,0,-3);
							$req2.=") ";
						}
						if(substr($_SESSION['DroitTR'],1,1)=='0' && substr($_SESSION['DroitTR'],3,1)=='0'){
							$req2.=" AND trame_planning.Id_Preparateur=".$_SESSION['Id_PersonneTR']." ";
						}
						if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
							$req2.=" AND ( ";
							if($_SESSION['TDB_DateDebut2']<>""){
								$req2.="trame_planning.DateDebut >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
								$req2.=" AND ";
							}
							if($_SESSION['TDB_DateFin2']<>""){
								$req2.="trame_planning.DateDebut <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
								$req2.=" ";
							}
							if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
							$req2.=" ) ";
						}
						$req2.="GROUP BY trame_planning.Id_Preparateur";
						$resultTP=mysqli_query($bdd,$req2);
						$nbResultaTP=mysqli_num_rows($resultTP);
					}
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$sommeV=0;
							if($nbResultaV<>0){
								mysqli_data_seek($resultV,0);
								while($row2=mysqli_fetch_array($resultV)){
									if($row2['Id_Preparateur']==$row['Id']){
										$valide=$valide+floatval($row2['TempsAlloue']);
										$sommeV=floatval($row2['TempsAlloue']);
									}
								}
							}
							$sommeAV=0;
							if($nbResultaAV<>0){
								mysqli_data_seek($resultAV,0);
								while($row2=mysqli_fetch_array($resultAV)){
									if($row2['Id_Preparateur']==$row['Id']){
										$AValider=$AValider+floatval($row2['TempsAlloue']);
										$sommeAV=floatval($row2['TempsAlloue']);
									}
								}
							}
							$sommeEC=0;
							if($nbResultaEC<>0){
								mysqli_data_seek($resultEC,0);
								while($row2=mysqli_fetch_array($resultEC)){
									if($row2['Id_Preparateur']==$row['Id']){
										$EnCours=$EnCours+floatval($row2['TempsAlloue']);
										$sommeEC=floatval($row2['TempsAlloue']);
									}
								}
							}
							$sommeR=0;
							if($nbResultaR<>0){
								mysqli_data_seek($resultR,0);
								while($row2=mysqli_fetch_array($resultR)){
									if($row2['Id_Preparateur']==$row['Id']){
										$Refuse=$Refuse+floatval($row2['TempsAlloue']);
										$sommeR=floatval($row2['TempsAlloue']);
									}
								}
							}
							$sommeTP=0;
							if($nbResultaTP<>0){
								mysqli_data_seek($resultTP,0);
								while($row2=mysqli_fetch_array($resultTP)){
									if($row2['Id_Preparateur']==$row['Id']){
										$TempsPasse=$TempsPasse+floatval($row2['NbHeure']);
										$sommeTP=floatval($row2['NbHeure']);
									}
								}
							}
							if($nbResultaPlanning==0){
								$sommeTP = $sommeTP/60;
							}
							if($sommeV>0 || $sommeAV>0 || $sommeEC>0 || $sommeR>0 || $sommeTP>0){
								if($couleur=="#ffffff"){$couleur="#E1E1D7";}
								else{$couleur="#ffffff";}
							?>
								<tr bgcolor="<?php echo $couleur;?>">
									<td width="20%">&nbsp;<?php echo $row['Personne'];?></td>
									<td width="8%"><?php echo $sommeV;?></td>
									<td width="8%"><?php echo $sommeAV;?></td>
									<td width="8%"><?php echo $sommeEC; ?></td>
									<td width="8%"><?php echo $sommeR; ?></td>
									<td width="8%"><?php echo $sommeTP; ?></td>
									<td width="15%"><?php if($sommeTP>0){echo round(($sommeV+$sommeAV)/$sommeTP,2);}else{ echo 0;}; ?></td>
								</tr>
							<?php
							}
						}
					}
				}
				if($couleur=="#ffffff"){$couleur="#E1E1D7";}
				else{$couleur="#ffffff";}
				if($nbResultaPlanning==0){
					$TempsPasse = $TempsPasse/60;
				}
				if($TempsPasse>0){
					$Productivite=round(($valide+$AValider)/$TempsPasse,2);
				}
			?>
			<tr bgcolor="<?php echo $couleur;?>">
				<td width="20%" style="Font-Weight:Bold;">&nbsp;Total</td>
				<td width="8%" style="Font-Weight:Bold;"><?php echo $valide;?></td>
				<td width="8%" style="Font-Weight:Bold;"><?php echo $AValider;?></td>
				<td width="8%" style="Font-Weight:Bold;"><?php echo $EnCours; ?></td>
				<td width="8%" style="Font-Weight:Bold;"><?php echo $Refuse; ?></td>
				<td width="8%" style="Font-Weight:Bold;"><?php echo $TempsPasse; ?></td>
				<td width="15%" style="Font-Weight:Bold;"><?php echo $Productivite; ?></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr><td height="16"></td></tr>
	<tr>
		<td>
		<?php
			
			if($_SESSION['TDB_ModeFiltre']=="oui"){
				$req2="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne ";
				$req="FROM trame_travaileffectue INNER JOIN new_rh_etatcivil ON trame_travaileffectue.Id_Preparateur=new_rh_etatcivil.Id ";
				$req.="WHERE trame_travaileffectue.Id_Preparateur>0 AND trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
				if($_SESSION['TDB_Preparateur2']<>""){
					$tab = explode(";",$_SESSION['TDB_Preparateur2']);
					$req.="AND (";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="new_rh_etatcivil.Id=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") ";
				}
				$req.="ORDER BY Personne ";
				$result=mysqli_query($bdd,$req2.$req);
				$nbResulta=mysqli_num_rows($result);
			}
		?>
		<table cellpadding="0" cellspacing="0" align="left" class="GeneralInfo" style="width:100%;">
			<tr bgcolor="#00325F">
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Manufacturing Engineer";}else{echo "Préparateur";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="20%"><?php if($_SESSION['Langue']=="EN"){echo "AUTO CONTROL<br>(number of deliverables)";}else{echo "AUTO-CONTROLE<br>(nombre de livrable)";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="20%"><?php if($_SESSION['Langue']=="EN"){echo "CONTROL<br>(number of deliverables to be controlled by the controller)";}else{echo "CONTROLE<br>(nombre de livrable à contrôler par le contrôleur)";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="30%"><?php if($_SESSION['Langue']=="EN"){echo "CONTROL AGAIN<br>(number of deliverables)";}else{echo "RECONTROLE<br>(nombre de livrable)";} ?></td>
			</tr>
			<?php
				$ac=0;
				$cont=0;
				$rec=0;
				$couleur="#ffffff";
				if($_SESSION['TDB_ModeFiltre']=="oui"){
					if ($nbResulta>0){
						$req2="SELECT trame_travaileffectue.Id_Preparateur, Count(Statut) AS Nb FROM trame_travaileffectue ";
						$req2.="WHERE trame_travaileffectue.Statut='AC' ";
						if($_SESSION['TDB_WP2']<>""){
							$tab = explode(";",$_SESSION['TDB_WP2']);
							$req2.="AND (";
							foreach($tab as $valeur){
								 if($valeur<>""){
									$req2.="trame_travaileffectue.Id_WP=".$valeur." OR ";
								 }
							}
							$req2=substr($req2,0,-3);
							$req2.=") ";
						}
						if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
							$req2.=" AND ( ";
							if($_SESSION['TDB_DateDebut2']<>""){
								$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
								$req2.=" AND ";
							}
							if($_SESSION['TDB_DateFin2']<>""){
								$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
								$req2.=" ";
							}
							if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
							$req2.=" ) ";
						}
						$req2.="GROUP BY trame_travaileffectue.Id_Preparateur";
						$resultAC=mysqli_query($bdd,$req2);
						$nbResultaAC=mysqli_num_rows($resultAC);
						
						$req2="SELECT trame_travaileffectue.Id_Preparateur, COUNT(Statut) AS Nb FROM trame_travaileffectue ";
						$req2.="WHERE trame_travaileffectue.Statut='CONTROLE' ";
						if($_SESSION['TDB_WP2']<>""){
							$tab = explode(";",$_SESSION['TDB_WP2']);
							$req2.="AND (";
							foreach($tab as $valeur){
								 if($valeur<>""){
									$req2.="trame_travaileffectue.Id_WP=".$valeur." OR ";
								 }
							}
							$req2=substr($req2,0,-3);
							$req2.=") ";
						}
						if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
							$req2.=" AND ( ";
							if($_SESSION['TDB_DateDebut2']<>""){
								$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
								$req2.=" AND ";
							}
							if($_SESSION['TDB_DateFin2']<>""){
								$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
								$req2.=" ";
							}
							if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
							$req2.=" ) ";
						}
						$req2.="GROUP BY trame_travaileffectue.Id_Preparateur";
						$resultCONT=mysqli_query($bdd,$req2);
						$nbResultaCONT=mysqli_num_rows($resultCONT);
						
						$req2="SELECT trame_travaileffectue.Id_Preparateur, COUNT(Statut) AS Nb FROM trame_travaileffectue ";
						$req2.="WHERE trame_travaileffectue.Statut='REC' ";
							if($_SESSION['TDB_WP2']<>""){
							$tab = explode(";",$_SESSION['TDB_WP2']);
							$req2.="AND (";
							foreach($tab as $valeur){
								 if($valeur<>""){
									$req2.="trame_travaileffectue.Id_WP=".$valeur." OR ";
								 }
							}
							$req2=substr($req2,0,-3);
							$req2.=") ";
						}
						if($_SESSION['TDB_DateDebut2']<>"" || $_SESSION['TDB_DateFin2']<>""){
							$req2.=" AND ( ";
							if($_SESSION['TDB_DateDebut2']<>""){
								$req2.="trame_travaileffectue.DatePreparateur >= '". TrsfDate_($_SESSION['TDB_DateDebut2'])."' ";
								$req2.=" AND ";
							}
							if($_SESSION['TDB_DateFin2']<>""){
								$req2.="trame_travaileffectue.DatePreparateur <= '". TrsfDate_($_SESSION['TDB_DateFin2'])."' ";
								$req2.=" ";
							}
							if(substr($req2,strlen($req2)-4)== "AND "){$req2=substr($req2,0,-4);}
							$req2.=" ) ";
						}
						$req2.="GROUP BY trame_travaileffectue.Id_Preparateur";
						$resultREC=mysqli_query($bdd,$req2);
						$nbResultaREC=mysqli_num_rows($resultREC);
						while($row=mysqli_fetch_array($result)){
							$nbAC=0;
							if($nbResultaAC<>0){
								mysqli_data_seek($resultAC,0);
								while($row2=mysqli_fetch_array($resultAC)){
									if($row2['Id_Preparateur']==$row['Id']){
										$ac=$ac+$row2['Nb'];
										$nbAC=$row2['Nb'];
									}
								}
							}
							$nbCONT=0;
							if($nbResultaCONT<>0){
								mysqli_data_seek($resultCONT,0);
								while($row2=mysqli_fetch_array($resultCONT)){
									if($row2['Id_Preparateur']==$row['Id']){
										$cont=$cont+$row2['Nb'];
										$nbCONT=$row2['Nb'];
									}
								}
							}
							$nbREC=0;
							if($nbResultaREC<>0){
								mysqli_data_seek($resultREC,0);
								while($row2=mysqli_fetch_array($resultREC)){
									if($row2['Id_Preparateur']==$row['Id']){
										$rec=$rec+$row2['Nb'];
										$nbCONT=$row2['Nb'];
									}
								}
							}
							if($nbAC>0 || $nbCONT>0 || $nbREC>0){
								if($couleur=="#ffffff"){$couleur="#E1E1D7";}
								else{$couleur="#ffffff";}
							?>
								<tr bgcolor="<?php echo $couleur;?>">
									<td width="10%">&nbsp;<?php echo $row['Personne'];?></td>
									<td width="20%"><?php echo $nbAC;?></td>
									<td width="20%"><?php echo $nbCONT;?></td>
									<td width="30%"><?php echo $nbREC; ?></td>
								</tr>
							<?php
							}
							
						}
					}
				}
				if($couleur=="#ffffff"){$couleur="#E1E1D7";}
				else{$couleur="#ffffff";}
			
			?>
			<tr bgcolor="<?php echo $couleur;?>">
				<td width="10%" style="Font-Weight:Bold;">&nbsp;Total</td>
				<td width="20%" style="Font-Weight:Bold;"><?php echo $ac;?></td>
				<td width="20%" style="Font-Weight:Bold;"><?php echo $cont;?></td>
				<td width="30%" style="Font-Weight:Bold;"><?php echo $rec; ?></td>
			</tr>
		</table>
		</td>
	</tr>
	<?php
	
	}
	 
	?>
	<tr><td height="4"></td></tr>
</form>
</table>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>

</body>
</html>
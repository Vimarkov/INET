<!DOCTYPE html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout_Critere(){
			var w=window.open("Ajout_CritereTDB_Controle.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=250");
			w.focus();
		}
		function Suppr_Critere(critere,valeur){
			var w=window.open("Ajout_CritereTDB_Controle.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=200");
			w.focus();
		}
		function Excel(){
			var w=window.open("Extract_TDB_Controle.php","PageExtract","status=no,menubar=no,scrollbars=yes,width=60,height=40");
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
<form class="test" method="POST" action="TDB_Controle.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "DASHBOARD CONTROL";}else{echo "TABLEAU DE BORD CONTRÔLE";} ?></td>
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
	<tr><td height="16"></td></tr>
	<tr>
		<td>
		<?php
			
			if($_SESSION['TDB_ModeFiltre']=="oui"){
				$req2="SELECT DISTINCT trame_travaileffectue.Id_Tache, trame_tache.Libelle AS Tache, trame_tache.NiveauControle, 
						(SELECT Libelle FROM trame_checklist WHERE Id=Id_CL) AS CL
				";
				$req="FROM trame_travaileffectue 
					INNER JOIN trame_tache 
					ON trame_travaileffectue.Id_Tache=trame_tache.Id ";
				$req.="WHERE trame_travaileffectue.Id_Preparateur>0 AND trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
				if($_SESSION['TDB_Preparateur2']<>""){
					$tab = explode(";",$_SESSION['TDB_Preparateur2']);
					$req.="AND (";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="trame_travaileffectue.Id_Preparateur=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") ";
				}
				if($_SESSION['TDB_WP2']<>""){
					$tab = explode(";",$_SESSION['TDB_WP2']);
					$req.="AND (";
					foreach($tab as $valeur){
						 if($valeur<>""){
							$req.="trame_travaileffectue.Id_WP=".$valeur." OR ";
						 }
					}
					$req=substr($req,0,-3);
					$req.=") ";
				}
				$req.="ORDER BY Tache ";
				$result=mysqli_query($bdd,$req2.$req);
				$nbResulta=mysqli_num_rows($result);
			}
		?>
		<table cellpadding="0" cellspacing="0" align="left" class="GeneralInfo" style="width:100%;">
			<tr bgcolor="#00325F">
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="25%"><?php if($_SESSION['Langue']=="EN"){echo "Task";}else{echo "Tâche";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="12%"><?php if($_SESSION['Langue']=="EN"){echo "AUTO CONTROL<br>(number of deliverables)";}else{echo "AUTO-CONTROLE<br>(nombre de livrable)";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="12%"><?php if($_SESSION['Langue']=="EN"){echo "CONTROL<br>(number of deliverables to be controlled by the controller)";}else{echo "CONTROLE<br>(nombre de livrable à contrôler par le contrôleur)";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="12%"><?php if($_SESSION['Langue']=="EN"){echo "CONTROL AGAIN<br>(number of deliverables)";}else{echo "RECONTROLE<br>(nombre de livrable)";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "RATE<br>(AC+CC+RA)/Nbr deliverables";}else{echo "RATIO<br>(AC+CC+RC)/Nbr livrable";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "LEVEL OF<br>CONTROL";}else{echo "NIVEAU<br>CONTROLE";} ?></td>
				<td class="EnTeteTableauCompetences" style="text-decoration:none;color:#ffffff;font-weight:bold;" width="20%"><?php if($_SESSION['Langue']=="EN"){echo "CHECK-LIST";}else{echo "CHECK-LIST";} ?></td>
			</tr>
			<?php
				$ac=0;
				$cont=0;
				$rec=0;
				$total=0;
				$couleur="#ffffff";
				if($_SESSION['TDB_ModeFiltre']=="oui"){
					if ($nbResulta>0){
						$req2="SELECT trame_travaileffectue.Id_Tache, Count(Statut) AS Nb FROM trame_travaileffectue ";
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
						$req2.="GROUP BY trame_travaileffectue.Id_Tache";
						$resultAC=mysqli_query($bdd,$req2);
						$nbResultaAC=mysqli_num_rows($resultAC);
						
						$req2="SELECT trame_travaileffectue.Id_Tache, COUNT(Statut) AS Nb FROM trame_travaileffectue ";
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
						$req2.="GROUP BY trame_travaileffectue.Id_Tache";
						$resultCONT=mysqli_query($bdd,$req2);
						$nbResultaCONT=mysqli_num_rows($resultCONT);
						
						$req2="SELECT trame_travaileffectue.Id_Tache, COUNT(Statut) AS Nb FROM trame_travaileffectue ";
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
						$req2.="GROUP BY trame_travaileffectue.Id_Tache";
						$resultREC=mysqli_query($bdd,$req2);
						$nbResultaREC=mysqli_num_rows($resultREC);
						
						$req2="SELECT trame_travaileffectue.Id_Tache, Count(Statut) AS Nb FROM trame_travaileffectue ";
						$req2.="WHERE trame_travaileffectue.Id_Tache<>0 ";
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
						$req2.="GROUP BY trame_travaileffectue.Id_Tache";
						$resultTOTAL=mysqli_query($bdd,$req2);
						$nbResultaTOTAL=mysqli_num_rows($resultTOTAL);
						
						while($row=mysqli_fetch_array($result)){
							$nbAC=0;
							if($nbResultaAC<>0){
								mysqli_data_seek($resultAC,0);
								while($row2=mysqli_fetch_array($resultAC)){
									if($row2['Id_Tache']==$row['Id_Tache']){
										$ac=$ac+$row2['Nb'];
										$nbAC=$row2['Nb'];
									}
								}
							}
							$nbCONT=0;
							if($nbResultaCONT<>0){
								mysqli_data_seek($resultCONT,0);
								while($row2=mysqli_fetch_array($resultCONT)){
									if($row2['Id_Tache']==$row['Id_Tache']){
										$cont=$cont+$row2['Nb'];
										$nbCONT=$row2['Nb'];
									}
								}
							}
							$nbREC=0;
							if($nbResultaREC<>0){
								mysqli_data_seek($resultREC,0);
								while($row2=mysqli_fetch_array($resultREC)){
									if($row2['Id_Tache']==$row['Id_Tache']){
										$rec=$rec+$row2['Nb'];
										$nbCONT=$row2['Nb'];
									}
								}
							}
							
							$nbTotal=0;
							if($nbResultaTOTAL<>0){
								mysqli_data_seek($resultTOTAL,0);
								while($row2=mysqli_fetch_array($resultTOTAL)){
									if($row2['Id_Tache']==$row['Id_Tache']){
										$total=$total+$row2['Nb'];
										$nbTotal=$row2['Nb'];
									}
								}
							}
							
							$ratio=0;
							if($nbTotal>0){$ratio=round(($nbAC+$nbCONT+$nbREC)/$nbTotal,2);}
							$Niveau=$row['NiveauControle'];
							if($Niveau==-1){$Niveau="M";}
							if($nbAC>0 || $nbCONT>0 || $nbREC>0){
								if($couleur=="#ffffff"){$couleur="#E1E1D7";}
								else{$couleur="#ffffff";}
							?>
								<tr bgcolor="<?php echo $couleur;?>">
									<td>&nbsp;<?php echo $row['Tache'];?></td>
									<td><?php echo $nbAC;?></td>
									<td><?php echo $nbCONT;?></td>
									<td><?php echo $nbREC; ?></td>
									<td><?php echo $ratio; ?></td>
									<td><?php echo $Niveau; ?></td>
									<td><?php echo $row['CL']; ?></td>
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
				<td style="Font-Weight:Bold;">&nbsp;Total</td>
				<td style="Font-Weight:Bold;"><?php echo $ac;?></td>
				<td style="Font-Weight:Bold;"><?php echo $cont;?></td>
				<td style="Font-Weight:Bold;"><?php echo $rec; ?></td>
				<td style="Font-Weight:Bold;"><?php echo ""; ?></td>
				<td style="Font-Weight:Bold;"><?php echo ""; ?></td>
				<td style="Font-Weight:Bold;"><?php echo ""; ?></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
</form>
</table>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>

</body>
</html>
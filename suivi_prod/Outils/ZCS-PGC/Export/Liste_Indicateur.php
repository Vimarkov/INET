<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript">
		function OuvreFenetreAjout_Critere(){
			var w=window.open("Ajout_CritereIndicateur.php?Type=A","PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=440");
			w.focus();
		}
		function Suppr_Critere(critere,valeur){
			var w=window.open("Ajout_CritereIndicateur.php?Type=S&critere="+critere+"&valeur="+valeur,"PageCritere","status=no,menubar=no,scrollbars=yes,width=600,height=400");
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

/* pChart library inclusions */
 include("../../../pChart/class/pData.class.php");
 include("../../../pChart/class/pDraw.class.php");
 include("../../../pChart/class/pImage.class.php"); 
 include("../../../pChart/class/pPie.class.php"); 
 
//Verifier si Google CHROME (true) ou Autre (fale)
if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
else {$NavigOk = false;}

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));

if($_POST){
	if(isset($_POST['Recherche_RAZ'])){
		$_SESSION['Indicateur_MSN']="";
		$_SESSION['Indicateur_Vacation']="";
		$_SESSION['Indicateur_Du']="";
		$_SESSION['Indicateur_Au']="";
		$_SESSION['Indicateur_Poste']="";
		$_SESSION['Indicateur_Client']="";
		
		$_SESSION['Indicateur_MSN2']="";
		$_SESSION['Indicateur_Vacation2']="";
		$_SESSION['Indicateur_Du2']="";
		$_SESSION['Indicateur_Au2']="";
		$_SESSION['Indicateur_Poste2']="";
		$_SESSION['Indicateur_Client2']="";
	}
}
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form id="formulaire" class="test" method="POST" action="Liste_Indicateur.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Indicateurs RETP</td>
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
				if($_SESSION['Indicateur_MSN']<>""){
					echo "<tr>";
					echo "<td>MSN : ".$_SESSION['Indicateur_MSN']."</td>";
					echo "</tr>";
				}
				if($_SESSION['Indicateur_Vacation']<>""){
					echo "<tr>";
					echo "<td>Vacations : ".$_SESSION['Indicateur_Vacation']."</td>";
					echo "</tr>";
				}
				if($_SESSION['Indicateur_Du']<>""){
					echo "<tr>";
					echo "<td>Date de début : ".$_SESSION['Indicateur_Du']."</td>";
					echo "</tr>";
				}
				if($_SESSION['Indicateur_Au']<>""){
					echo "<tr>";
					echo "<td>Date de fin : ".$_SESSION['Indicateur_Au']."</td>";
					echo "</tr>";
				}
				if($_SESSION['Indicateur_Poste']<>""){
					echo "<tr>";
					echo "<td>Postes : ".$_SESSION['Indicateur_Poste']."</td>";
					echo "</tr>";
				}
				if($_SESSION['Indicateur_Client']<>""){
					echo "<tr>";
					echo "<td>Clients : ".$_SESSION['Indicateur_Client']."</td>";
					echo "</tr>";
				}
			?>
			
		</td></tr>
		<tr><td height="4"></td></tr>
		<tr>	
			<td colspan="6" align="center" colspan="6"><input class="Bouton" name="BtnRechercher" size="10" type="submit" value="Afficher">&nbsp;&nbsp;&nbsp;&nbsp;
			<input class="Bouton" name="Recherche_RAZ" type="submit" value="Vider les critères de recherche">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td><b>&nbsp; Indicateurs : </b></td>
		</tr>
		<tr><td height="10"></td></tr>
		<tr>
			<td align="center">
			<?php
				if($_POST){
					if(isset($_POST['BtnRechercher'])){
					
						//CRITERES DU WHERE
						$req=" AND ";
						if($_SESSION['Indicateur_MSN2']<>""){
							$tab = explode(";",$_SESSION['Indicateur_MSN2']);
							$req.="(";
							foreach($tab as $valeur){
								 if($valeur<>""){
									$req.="sp_dossier.MSN=".$valeur." OR ";
								 }
							}
							$req=substr($req,0,-3);
							$req.=") AND ";
						}
						if($_SESSION['Indicateur_Pole2']<>""){
							$tab = explode(";",$_SESSION['Indicateur_Pole2']);
							$req.="(";
							foreach($tab as $valeur){
								 if($valeur<>""){
									$req.="sp_ficheintervention.Id_Pole=".substr($valeur,1)." OR ";
								 }
							}
							$req=substr($req,0,-3);
							$req.=") AND ";
						}
						if($_SESSION['Indicateur_Vacation2']<>""){
							$tab = explode(";",$_SESSION['Indicateur_Vacation2']);
							$req.="(";
							foreach($tab as $valeur){
								 if($valeur<>""){
									$req.="sp_ficheintervention.Vacation='".$valeur."' OR ";
								 }
							}
							$req=substr($req,0,-3);
							$req.=") AND ";
						}
						if($_SESSION['Indicateur_Du2']<>"" || $_SESSION['Indicateur_Au2']<>""){
							$req.=" ( ";
							if($_SESSION['Indicateur_Du2']<>""){
								$req.="sp_ficheintervention.DateIntervention >= '". TrsfDate_($_SESSION['Indicateur_Du2'])."' ";
								$req.=" AND ";
							}
							if($_SESSION['Indicateur_Au2']<>""){
								$req.="sp_ficheintervention.DateIntervention <= '". TrsfDate_($_SESSION['Indicateur_Au2'])."' ";
								$req.=" ";
							}
							if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
							$req.=" ) ";
						}
						if(substr($req,strlen($req)-3)== "OR "){$req=substr($req,0,-3);}
						if($_SESSION['Indicateur_Du2']<>"" || $_SESSION['Indicateur_Au2']<>""){
							$req.=" AND ";
						}
						if(substr($req,strlen($req)-4)== "AND "){$req=substr($req,0,-4);}
						$reqCriteres=$req;
						
						//**************** LISTE DES POINTS ***********************//
						$req="SELECT Id_Pole,Id_StatutPROD, Id_RetourPROD, Id_StatutQUALITE,sp_dossier.PNE, ";
						$req.="(SELECT EstRetour FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS EstRetour ";
						$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
						$req.="WHERE sp_ficheintervention.DateIntervention>'0001-01-01' AND sp_ficheintervention.Vacation<>'' AND (Id_StatutPROD='QARJ' OR Id_StatutPROD='TFS' OR Id_StatutQUALITE='CERT') ";
						$req.=$reqCriteres;
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						//***********************************************************//
						
						//**************** LISTE DES GAMMES PAR POLE ***********************//
						$req="SELECT DISTINCT sp_ficheintervention.Id_Dossier,sp_ficheintervention.Id_Pole ";
						$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
						$req.="WHERE sp_ficheintervention.DateIntervention>'0001-01-01' AND sp_ficheintervention.Vacation<>'' AND (Id_StatutPROD='QARJ' OR Id_StatutPROD='TFS' OR Id_StatutQUALITE='CERT') ";
						$req.=$reqCriteres;
						$resultGammePole=mysqli_query($bdd,$req);
						$nbGammePole=mysqli_num_rows($resultGammePole);
						
						//***********************************************************//
						
						//**************** LISTE DES GAMMES ***********************//
						$req="SELECT DISTINCT Id_Dossier ";
						$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
						$req.="WHERE sp_ficheintervention.DateIntervention>'0001-01-01' AND sp_ficheintervention.Vacation<>'' AND (Id_StatutPROD='QARJ' OR Id_StatutPROD='TFS' OR Id_StatutQUALITE='CERT') ";
						$req.=$reqCriteres;
						$resultGamme=mysqli_query($bdd,$req);
						$nbGamme=mysqli_num_rows($resultGamme);
						
						//***********************************************************//
						
						//**************** LISTE DES POLES ***********************//
						$req="SELECT DISTINCT Id_Pole, (SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=sp_ficheintervention.Id_Pole) AS Pole ";
						$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
						$req.="WHERE sp_ficheintervention.DateIntervention>'0001-01-01' AND sp_ficheintervention.Vacation<>'' AND (Id_StatutPROD='QARJ' OR Id_StatutPROD='TFS' OR Id_StatutQUALITE='CERT') ";
						$req.=$reqCriteres;
						$req.=" ORDER BY Pole ";
						$resultPole=mysqli_query($bdd,$req);
						$nbPole=mysqli_num_rows($resultPole);
						//***********************************************************//
						
						//**************** LISTE DES RETP ***********************//
						$req="SELECT DISTINCT Id_RetourPROD, (SELECT Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS RetourPROD, ";
						$req.="(SELECT EstRetour FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS EstRetour ";
						$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
						$req.="WHERE Id_RetourPROD<>0 AND sp_ficheintervention.DateIntervention>'0001-01-01' AND sp_ficheintervention.Vacation<>'' AND (Id_StatutPROD='QARJ' OR Id_StatutPROD='TFS' OR Id_StatutQUALITE='CERT') ";
						$req.=$reqCriteres;
						$req.=" ORDER BY RetourPROD ";
						$resultRetourProd=mysqli_query($bdd,$req);
						$nbRETP=mysqli_num_rows($resultRetourProd);
						//***********************************************************//
						
						echo "<table width='50%' cellpadding='0' cellspacing='0' align='center'>";
						
						//Liste des poles
						if ($nbPole>0){	
							echo "<tr><td></td>";
							while($rowPole=mysqli_fetch_array($resultPole)){
								$pole="";
								if($rowPole['Id_Pole']<>5){$pole=$rowPole['Pole'];}
								else{$pole="STRUCTURE";}
								echo "<td align='center'  style='border:1px #000000 solid;'>".$pole."</td>";
							}
							echo "<td align='center'  style='border:1px #000000 solid;'>GLOBAL</td></tr>";
						}
						
						//STATUTS
						$ListeStatut= array('CERT hors PNE','CERT PNE','QARJ','TFS');
						foreach ($ListeStatut AS $value){
								echo "<tr><td  style='border:1px #000000 solid;'>".$value."</td>";
								if ($nbPole>0){
									mysqli_data_seek($resultPole,0);
									while($rowPole=mysqli_fetch_array($resultPole)){
										$nb=0;
										if ($nbResulta>0){
											mysqli_data_seek($result,0);
											while($row=mysqli_fetch_array($result)){
												if($value=="CERT hors PNE"){
													if($row['PNE']==0){
														if($row['Id_StatutQUALITE']=="CERT" && $row['Id_Pole']==$rowPole['Id_Pole']){$nb++;}
													}
												}
												elseif($value=="CERT PNE"){
													if($row['PNE']==1){
														if($row['Id_StatutQUALITE']=="CERT" && $row['Id_Pole']==$rowPole['Id_Pole']){$nb++;}
													}
												}
												else{
													if($row['Id_StatutPROD']==$value && $row['Id_Pole']==$rowPole['Id_Pole']){$nb++;}
												}
											}
										}
										echo "<td align='center' style='border:1px #000000 solid;'>".$nb."</td>";
									}
									
								}
								
								//Global
								$nb=0;
								if ($nbResulta>0){
									mysqli_data_seek($result,0);
									while($row=mysqli_fetch_array($result)){
										if($value=="CERT hors PNE"){
											if($row['PNE']==0){
												if($row['Id_StatutQUALITE']=="CERT"){$nb++;}
											}
										}
										elseif($value=="CERT PNE"){
											if($row['PNE']==1){
												if($row['Id_StatutQUALITE']=="CERT"){$nb++;}
											}
										}
										else{
											if($row['Id_StatutPROD']==$value){$nb++;}
										}
									}
								}
								echo "<td align='center'  style='border:1px #000000 solid;'>".$nb."</td>";
								echo "</tr>";
						}
						echo "<tr><td height='10'></td></tr>";
						//RETP
						if ($nbRETP>0){
							mysqli_data_seek($resultRetourProd,0);
							while($rowRETP=mysqli_fetch_array($resultRetourProd)){
									$bgcolor="";
									if($rowRETP['EstRetour']==1){
										$bgcolor="bgcolor='#e1fff5'";
									}
									echo "<tr><td style='border:1px #000000 solid;' ".$bgcolor.">".$rowRETP['RetourPROD']."</td>";
									if ($nbPole>0){
										mysqli_data_seek($resultPole,0);
										while($rowPole=mysqli_fetch_array($resultPole)){
											$nb=0;
											if ($nbResulta>0){
												mysqli_data_seek($result,0);
												while($row=mysqli_fetch_array($result)){
													if($row['Id_RetourPROD']==$rowRETP['Id_RetourPROD'] && $row['Id_Pole']==$rowPole['Id_Pole']){$nb++;}
												}
											}
											echo "<td align='center'  style='border:1px #000000 solid;'>".$nb."</td>";
										}
										
									}
									
									//Global
									$nb=0;
									if ($nbResulta>0){
										mysqli_data_seek($result,0);
										while($row=mysqli_fetch_array($result)){
											if($row['Id_RetourPROD']==$rowRETP['Id_RetourPROD']){$nb++;}
										}
									}
									echo "<td align='center'  style='border:1px #000000 solid;'>".$nb."</td>";
									echo "</tr>";
							}
						}
						echo "<tr><td height='10'></td></tr>";
						
						//Nb retours
						echo "<tr><td style='border:1px #000000 solid;'>Nombre de retours</td>";
						if ($nbPole>0){
							mysqli_data_seek($resultPole,0);
							while($rowPole=mysqli_fetch_array($resultPole)){
								$nb=0;
								if ($nbResulta>0){
									mysqli_data_seek($result,0);
									while($row=mysqli_fetch_array($result)){
										if($row['EstRetour']==1 && $row['Id_Pole']==$rowPole['Id_Pole']){$nb++;}
									}
								}
								echo "<td align='center' style='border:1px #000000 solid;'>".$nb."</td>";
							}
							
						}
						
						//Global
						$nb=0;
						if ($nbResulta>0){
							mysqli_data_seek($result,0);
							while($row=mysqli_fetch_array($result)){
								if($row['EstRetour']==1){$nb++;}
							}
						}
						echo "<td align='center' style='border:1px #000000 solid;'>".$nb."</td>";
						echo "</tr>";
						
						//Nb Gammes
						echo "<tr><td style='border:1px #000000 solid;'>Nombre de gammes</td>";
						if ($nbPole>0){
							mysqli_data_seek($resultPole,0);
							while($rowPole=mysqli_fetch_array($resultPole)){
								$nb=0;
								if ($nbGammePole>0){
									mysqli_data_seek($resultGammePole,0);
									while($rowGammePole=mysqli_fetch_array($resultGammePole)){
										if($rowGammePole['Id_Pole']==$rowPole['Id_Pole']){$nb++;}
									}
								}
								echo "<td align='center' style='border:1px #000000 solid;'>".$nb."</td>";
							}
							
						}
						
						//Global
						$nb=0;
						if ($nbGamme>0){
							mysqli_data_seek($resultGamme,0);
							while($rowGamme=mysqli_fetch_array($resultGamme)){
								$nb++;
							}
						}
						echo "<td align='center' style='border:1px #000000 solid;'>".$nb."</td>";
						echo "</tr>";
						
						
						//Taux de retours
						echo "<tr><td style='border:1px #000000 solid;'>Taux de retour</td>";
						if ($nbPole>0){
							mysqli_data_seek($resultPole,0);
							while($rowPole=mysqli_fetch_array($resultPole)){
								$nbRetour=0;
								if ($nbResulta>0){
									mysqli_data_seek($result,0);
									while($row=mysqli_fetch_array($result)){
										if($row['EstRetour']==1 && $row['Id_Pole']==$rowPole['Id_Pole']){$nbRetour++;}
									}
								}
								$nbGa=0;
								if ($nbGammePole>0){
									mysqli_data_seek($resultGammePole,0);
									while($rowGammePole=mysqli_fetch_array($resultGammePole)){
										if($rowGammePole['Id_Pole']==$rowPole['Id_Pole']){$nbGa++;}
									}
								}
								$Taux=0;
								if($nbGa>0){$Taux=round(($nbRetour/$nbGa)*100,0);}
								echo "<td align='center' style='border:1px #000000 solid;'>".$Taux."%</td>";
							}
							
						}
						
						//Global
						$nbRetour=0;
						if ($nbResulta>0){
							mysqli_data_seek($result,0);
							while($row=mysqli_fetch_array($result)){
								if($row['EstRetour']==1){$nbRetour++;}
							}
						}
						$nbGa=0;
						if ($nbGamme>0){
							mysqli_data_seek($resultGamme,0);
							while($rowGamme=mysqli_fetch_array($resultGamme)){
								$nbGa++;
							}
						}
						$Taux=0;
						if($nbGa>0){$Taux=round(($nbRetour/$nbGa)*100,0);}
						echo "<td align='center' style='border:1px #000000 solid;'>".$Taux."%</td>";
						echo "</tr>";
						
						echo "</table>";
						echo "</td></tr>";
						echo "<tr><td height='10'></td></tr>";
						echo "<tr><td>";
						echo "<table width='100%' cellpadding='0' cellspacing='0' align='center'>";
						//********************************** GRAPHIQUES PAR POLE + GLOBAL ************************//
						
						//POLES
						if ($nbPole>0){
							$nbCol=0;
							mysqli_data_seek($resultPole,0);
							while($rowPole=mysqli_fetch_array($resultPole)){
								$Total=0;
								if ($nbRETP>0){
									mysqli_data_seek($resultRetourProd,0);
									while($rowRETP=mysqli_fetch_array($resultRetourProd)){
										if($rowRETP['EstRetour']==1){
											if ($nbResulta>0){
												mysqli_data_seek($result,0);
												while($row=mysqli_fetch_array($result)){
													if($row['Id_Pole']==$rowPole['Id_Pole'] && $row['Id_RetourPROD']==$rowRETP['Id_RetourPROD']){$Total++;}
												}
											}
										}
									}
									
								}
								
								$arraynbRetour=array();
								$arrayNom = array();
								if ($nbRETP>0){
									mysqli_data_seek($resultRetourProd,0);
									
									while($rowRETP=mysqli_fetch_array($resultRetourProd)){
										if($rowRETP['EstRetour']==1){
											$nb=0;
											if ($nbResulta>0){
												mysqli_data_seek($result,0);
												while($row=mysqli_fetch_array($result)){
													if($row['Id_Pole']==$rowPole['Id_Pole'] && $row['Id_RetourPROD']==$rowRETP['Id_RetourPROD']){$nb++;}
												}
											}
											if($nb>0){
												$valeur="0%";
												if($Total>0){$valeur=round(($nb/$Total)*100,0)."% ";}
												$arrayNom[]=$valeur.$rowRETP['RetourPROD'];
												$arraynbRetour[]=$nb;
											}
										}
									}
									
								}
								$MyData = new pData(); 
								if(count($arraynbRetour)>0){
									$MyData->addPoints($arraynbRetour,"Retours"); 
									$MyData->setSerieDescription("Retours","Retours");
									$MyData->addPoints($arrayNom,"Nom retours");
									$MyData->setAbscissa("Nom retours");
								}
						
								 /* Create the pChart object */
								$myPicture = new pImage(1000,500,$MyData);

								/* Overlay with a gradient */
								$myPicture->drawGradientArea(0,0,999,25,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>100));

								/* Add a border to the picture */
								$myPicture->drawRectangle(0,0,999,499,array("R"=>0,"G"=>0,"B"=>0));

								/* Write the picture title */ 
								$myPicture->setFontProperties(array("FontName"=>"../../../pChart/fonts/verdana.ttf","FontSize"=>10));
								
								if($rowPole['Id_Pole']<>5){
									$Titre=$rowPole['Pole'];
								}
								else{
									$Titre="STRUCTURE";
								}
								if($_SESSION['Indicateur_Du2']<>"" || $_SESSION['Indicateur_Au2']<>""){
									$Titre.=" ( ".$_SESSION['Indicateur_Du2']." - ".$_SESSION['Indicateur_Au2']." ) ";
								}
								$myPicture->drawText(10,20,$Titre,array("R"=>255,"G"=>255,"B"=>255));

								/* Set the default font properties */ 
								$myPicture->setFontProperties(array("FontName"=>"../../../pChart/fonts/verdana.ttf","FontSize"=>5,"R"=>0,"G"=>0,"B"=>0));

								/* Enable shadow computing */ 
								$myPicture->setShadow(TRUE,array("X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>50));
								$myPicture->setGraphArea(90,90,900,450);
								
								/* Create the pPie object */ 
								$PieChart = new pPie($myPicture,$MyData);

								/* Draw an AA pie chart */ 
								$PieChart->draw3DPie(500,300,array("Radius"=>140,"WriteValues"=>FALSE,"DrawLabels"=>TRUE,"LabelStacked"=>TRUE,"Border"=>TRUE));

								/* Write the legend box */ 
								$myPicture->setShadow(FALSE);
								
								/* Render the picture (choose the best way) */
								$myPicture->render("pictures2/".$rowPole['Pole'].".png");	
								echo "<tr>";
								echo "<td align='center'>";
								echo "<img src='pictures2/".$rowPole['Pole'].".png' />";
								echo "</tr>";
							}
						}
						
						//Global
						$Total=0;
						if ($nbRETP>0){
							mysqli_data_seek($resultRetourProd,0);
							while($rowRETP=mysqli_fetch_array($resultRetourProd)){
								if($rowRETP['EstRetour']==1){
									if ($nbResulta>0){
										mysqli_data_seek($result,0);
										while($row=mysqli_fetch_array($result)){
											if($row['Id_RetourPROD']==$rowRETP['Id_RetourPROD']){$Total++;}
										}
									}
								}
							}
							
						}
						$arraynbRetour=array();
						$arrayNom = array();
						if ($nbRETP>0){
							mysqli_data_seek($resultRetourProd,0);
							while($rowRETP=mysqli_fetch_array($resultRetourProd)){
								if($rowRETP['EstRetour']==1){
									$nb=0;
									if ($nbResulta>0){
										mysqli_data_seek($result,0);
										while($row=mysqli_fetch_array($result)){
											if($row['Id_RetourPROD']==$rowRETP['Id_RetourPROD']){$nb++;}
										}
									}
									if($nb>0){
										$valeur="0%";
										if($Total>0){$valeur=round(($nb/$Total)*100,0)."% ";}
										$arrayNom[]=$valeur.$rowRETP['RetourPROD'];
										$arraynbRetour[]=$nb;
									}
								}
							}
							
						}
						$MyData = new pData(); 
						if(count($arraynbRetour)>0){
							$MyData->addPoints($arraynbRetour,"Retours"); 
							$MyData->setSerieDescription("Retours","Retours");
							$MyData->addPoints($arrayNom,"Nom retours");
							$MyData->setAbscissa("Nom retours");
						}
				
						 /* Create the pChart object */
						$myPicture = new pImage(1000,500,$MyData);

						/* Overlay with a gradient */
						$myPicture->drawGradientArea(0,0,999,25,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>100));

						/* Add a border to the picture */
						$myPicture->drawRectangle(0,0,999,499,array("R"=>0,"G"=>0,"B"=>0));

						/* Write the picture title */ 
						$myPicture->setFontProperties(array("FontName"=>"../../../pChart/fonts/verdana.ttf","FontSize"=>10));
						
						$Titre="GLOBAL";
						if($_SESSION['Indicateur_Du2']<>"" || $_SESSION['Indicateur_Au2']<>""){
							$Titre.=" ( ".$_SESSION['Indicateur_Du2']." - ".$_SESSION['Indicateur_Au2']." ) ";
						}
						$myPicture->drawText(10,20,$Titre,array("R"=>255,"G"=>255,"B"=>255));

						/* Set the default font properties */ 
						$myPicture->setFontProperties(array("FontName"=>"../../../pChart/fonts/verdana.ttf","FontSize"=>5,"R"=>0,"G"=>0,"B"=>0));

						/* Enable shadow computing */ 
						$myPicture->setShadow(TRUE,array("X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>50));
						$myPicture->setGraphArea(90,90,900,450);
						
						/* Create the pPie object */ 
						$PieChart = new pPie($myPicture,$MyData);

						/* Draw an AA pie chart */ 
						$PieChart->draw3DPie(500,300,array("Radius"=>140,"WriteValues"=>FALSE,"DrawLabels"=>TRUE,"LabelStacked"=>TRUE,"Border"=>TRUE));

						/* Write the legend box */ 
						$myPicture->setShadow(FALSE);
						
						/* Render the picture (choose the best way) */
						$myPicture->render("pictures2/GLOBAL.png");	
						echo "<tr>";
						echo "<td align='center'>";
						echo "<img src='pictures2/GLOBAL.png' />";
						echo "</tr>";
						
						echo "</table>";
						//****************************************************************************************//
					}
				}
			?>
			</td>
		</tr>
		</table>
	</td></tr>
	<tr><td height="10"></td></tr>
</form>
</table>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
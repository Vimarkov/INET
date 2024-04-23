<html>
<head>
	<title>Extranet de la société Assistance Aéronautique et Aérospatiale</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="MSN.js"></script>
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>	
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script type="text/javascript" src="../../JS/js/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="../../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript" src="../../JS/bootstrap.min.js"></script>
    <script type="text/javascript" src="../../JS/prettify.js"></script>
    <script type="text/javascript" src="../../JS/bootstrap-timepicker.js"></script>
	<script>
		function FermerEtRecharger(){
			opener.location.reload();
			window.close();
		}
	</script>
	<script type="text/javascript">
		$(document).ready(function () {
			$('#heureMoulage').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: true,
				showMeridian: false,
				defaultTime: false
			});
			
			$('#heureDemoulage').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: true,
				showMeridian: false,
				defaultTime: false
			});
		});
	</script>
</head>
<body>

<?php
session_start();
require("../../Fonctions.php");
require("../../Connexioni.php");

Ecrire_Code_JS_Init_Date();
if($_POST){
	if($_POST['Mode']=="A"){
		$tabMoulage = explode(":",$_POST['heureMoulage']);
		$heureMoulage = date("H:i:s",mktime(intval($tabMoulage[0]), intval($tabMoulage[1]), 0, 0, 0, 0));
		$tabDemoulage = explode(":",$_POST['heureDemoulage']);
		$heureDemoulage = date("H:i:s",mktime(intval($tabDemoulage[0]), intval($tabDemoulage[1]), 0, 0, 0, 0));
		$cycle=0;
		if($_POST['cycleTheorique']<>""){$cycle=$_POST['cycleTheorique'];}
		$requete="INSERT INTO sp_atrmsn (MSN,DateMoulage,HeureMoulage,DateDemoulage,HeureDemoulage,Id_Prestation,Commentaire,CycleTheorique,SurveillanceATR) ";
		$requete.="VALUES (".$_POST['msn'].",'".TrsfDate_($_POST['dateMoulage'])."','".$heureMoulage."','".TrsfDate_($_POST['dateDemoulage'])."','".$heureDemoulage."',262,'".addslashes($_POST['commentaire'])."',".$cycle.",".$_POST['surveillanceATR'].") ";
		$result=mysqli_query($bdd,$requete);
		$IdCree = mysqli_insert_id($bdd);
		if($IdCree>0){
			//Ajout des visites clients
			$tabVisites = explode(";",$_POST['visites']);
			foreach($tabVisites as $valeur){
				 if($valeur<>""){
					$tab2 = explode("_",$valeur);
					$req="INSERT INTO sp_atrmsn_customer (Id_MSN,Id_Visite,Presentation,Zone,Support,Quality,Commentaire1,Commentaire2,Commentaire3,Commentaire4,DateVisite) VALUES (".$IdCree.",".$tab2[0].",'".$tab2[1]."','".$tab2[2]."','".$tab2[3]."','".$tab2[4]."','".addslashes($tab2[5])."','".addslashes($tab2[6])."','".addslashes($tab2[7])."','".addslashes($tab2[8])."','".TrsfDate_($tab2[9])."')";
					$resultAjour=mysqli_query($bdd,$req);
				 }
			}
		}
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$tabMoulage = explode(":",$_POST['heureMoulage']);
		$heureMoulage = date("H:i:s",mktime(intval($tabMoulage[0]), intval($tabMoulage[1]), 0, 0, 0, 0));
		$tabDemoulage = explode(":",$_POST['heureDemoulage']);
		$heureDemoulage = date("H:i:s",mktime(intval($tabDemoulage[0]), intval($tabDemoulage[1]), 0, 0, 0, 0));
		$cycle=0;
		if($_POST['cycleTheorique']<>""){$cycle=$_POST['cycleTheorique'];}
		
		$requete="UPDATE sp_atrmsn SET ";
		$requete.="MSN=".$_POST['msn'].",";
		$requete.="CycleTheorique=".$cycle.",";
		$requete.="SurveillanceATR=".$_POST['surveillanceATR'].",";
		$requete.="DateMoulage='".TrsfDate_($_POST['dateMoulage'])."',";
		$requete.="HeureMoulage='".$heureMoulage."',";
		$requete.="Commentaire='".addslashes($_POST['commentaire'])."',";
		$requete.="DateDemoulage='".TrsfDate_($_POST['dateDemoulage'])."',";
		$requete.="HeureDemoulage='".$heureDemoulage."' ";
		$requete.="WHERE Id=".$_POST['Id'];
		$result=mysqli_query($bdd,$requete);
		
		//Suppression des visites clients
		$req="DELETE FROM sp_atrmsn_customer WHERE Id_MSN=".$_POST['Id'];
		$resultAjour=mysqli_query($bdd,$req);
		//Ajout des visites clients
		$tabVisites = explode(";",$_POST['visites']);
		foreach($tabVisites as $valeur){
			 if($valeur<>""){
				$tab2 = explode("_",$valeur);
				$req="INSERT INTO sp_atrmsn_customer (Id_MSN,Id_Visite,Presentation,Zone,Support,Quality,Commentaire1,Commentaire2,Commentaire3,Commentaire4,DateVisite) VALUES (".$_POST['Id'].",".$tab2[0].",'".$tab2[1]."','".$tab2[2]."','".$tab2[3]."','".$tab2[4]."','".addslashes($tab2[5])."','".addslashes($tab2[6])."','".addslashes($tab2[7])."','".addslashes($tab2[8])."','".TrsfDate_($tab2[9])."')";
				$resultAjour=mysqli_query($bdd,$req);
			 }
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	$titre="";
	if($_GET['Mode']=="A"){$titre="Ajouter un MSN";}
	elseif($_GET['Mode']=="M"){$titre="Modifier un MSN";}
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT Id, MSN, DateMoulage,DateDemoulage,HeureMoulage,HeureDemoulage, Commentaire,CycleTheorique,SurveillanceATR FROM sp_atrmsn WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_MSN.php" onSubmit="return VerifChamps();">
		<table width="100%">
			<tr>
				<td>
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="4"></td>
							<td class="TitrePage"><?php echo $titre;?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td>
				<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
				<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
				</td>
			</tr>
			<tr>
				<td>
					<table width="95%"  align="center" class="TableCompetences">
						<tr>
							<?php
								if($_GET['Mode']=="A"){
									$req="SELECT MSN FROM sp_atrmsn WHERE Id_Prestation=262";
								}
								elseif($_GET['Mode']=="M"){
									$req="SELECT MSN FROM sp_atrmsn WHERE Id<>".$Ligne['Id']." AND Id_Prestation=262";
								}
								$resultMSN=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($resultMSN);
								if ($nbResulta>0){
									$i=0;
									while($rowMSN=mysqli_fetch_array($resultMSN)){
										echo "<script>ListeMSN[".$i."]=".$rowMSN['MSN']."</script>";
										$i++;
									}
								}
							?>
							<td class="Libelle">&nbsp;MSN : </td>
							<td>
								<input onKeyUp="nombre(this)" type="texte" name="msn" id="msn" size="5" value="<?php if($_GET['Mode']=="M"){echo $Ligne['MSN'];}?>">
							</td>
							<td></td><td></td>
							<td class="Libelle" valign="top">&nbsp; Commentaire : </td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle">&nbsp;Date de moulage : </td>
							<td>
								<input type="date" name="dateMoulage" id="dateMoulage" size="10" value="<?php if($_GET['Mode']=="M"){echo AfficheDateFR($Ligne['DateMoulage']);}?>">
							</td>
							<td class="Libelle">&nbsp;Heure de moulage : </td>
							<td>
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small" type="text" name="heureMoulage" id="heureMoulage" size="5" value="<?php if($_GET['Mode']=="M"){echo $Ligne['HeureMoulage'];}else{echo "0:00:00";}?>">
								</div>
							</td>
							<td rowspan="3">
								<textarea id="commentaire" name="commentaire" rows="3" cols="70" style="resize:none;"><?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['Commentaire']);}?></textarea>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle">&nbsp;Date de démoulage : </td>
							<td>
								<input type="date" name="dateDemoulage" id="dateDemoulage" size="10" value="<?php if($_GET['Mode']=="M"){echo AfficheDateFR($Ligne['DateDemoulage']);}?>">
							</td>
							<td class="Libelle">&nbsp;Heure de démoulage : </td>
							<td>
								<div class="input-group bootstrap-timepicker timepicker">
								<input class="form-control input-small" class="time" type="text" name="heureDemoulage" id="heureDemoulage" size="5" value="<?php if($_GET['Mode']=="M"){echo $Ligne['HeureDemoulage'];}else{echo "0:00:00";}?>">
								</div>
							</td>
						</tr>
						<tr>
							<td class="Libelle">&nbsp;Cycle Théorique : </td>
							<td>
								<input onKeyUp="nombre(this)" type="texte" name="cycleTheorique" id="cycleTheorique" size="5" value="<?php if($_GET['Mode']=="M"){echo $Ligne['CycleTheorique'];}?>">
							</td>
							<td class="Libelle">&nbsp;Surveillance ATR : </td>
							<td>
								<select name="surveillanceATR">
									<option value="0" <?php if($_GET['Mode']=="M"){if($Ligne['SurveillanceATR']==0){echo "checked";}}else{echo "checked";} ?>>Non</option>
									<option value="1" <?php if($_GET['Mode']=="M"){if($Ligne['SurveillanceATR']==1){echo "checked";}} ?>>Oui</option>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="4" width='10%' class='Libelle' valign="top">
								<table cellpadding='0' cellspacing='0' style='-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;'>
									<tr>
										<td colspan='7' bgcolor='#e4e7f0' class="Libelle">&nbsp; Ajouter les visites clients : &nbsp;</td>
									</tr>
									<tr>
										<td colspan='7' bgcolor='#e4e7f0'>&nbsp; Visite : 
										&nbsp;<select name='visite' id='visite' style="width:250px;" onkeypress='if(event.keyCode == 13)Ajouter()'>
													<option name='' value=''></option>
													<?php
														$req="SELECT Id, Libelle FROM sp_atrvisite ";
														$result=mysqli_query($bdd,$req);
														$nbResulta=mysqli_num_rows($result);
														if ($nbResulta>0){
															while($rowVisite=mysqli_fetch_array($result)){
																echo "<option value='".$rowVisite['Id'].";".$rowVisite['Libelle']."'>".$rowVisite['Libelle']."</option>";
															}
														}
													?>
											</select>
										</td>
									</tr>
									<tr>
										<td colspan='7' bgcolor='#e4e7f0'>&nbsp; Date :&nbsp;&nbsp;
											<input type="date" name="dateVisite" id="dateVisite" size="10" value="">
										</td>
									</tr>
									<tr>
										<td colspan="2" bgcolor='#e4e7f0'></td>
										<td bgcolor='#e4e7f0' align="center">Dissatisfied</td>
										<td bgcolor='#e4e7f0' align="center">Somehow Dissatisfied</td>
										<td bgcolor='#e4e7f0' align="center">Satisfied</td>
										<td bgcolor='#e4e7f0' align="center">Totally Satisfied</td>
										<td bgcolor='#e4e7f0' align="center">Commentaire</td>
									</tr>
									<tr>
										<td width="30%" colspan="2" bgcolor='#e4e7f0'>&nbsp;1. Presentation planning<br>(communication,notification in due time...)</td>
										<td width="10px" bgcolor='#e4e7f0' align="center"><input type="radio" id='question1' name='question1' value="Dissatisfied"></td>
										<td width="10px" bgcolor='#e4e7f0' align="center"><input type="radio" id='question1' name='question1' value="Somehow Dissatisfied"></td>
										<td width="10px" bgcolor='#e4e7f0' align="center"><input type="radio" id='question1' name='question1' value="Satisfied"></td>
										<td width="10px" bgcolor='#e4e7f0' align="center"><input type="radio" id='question1' name='question1' checked value="Totally Satisfied"></td>
										<td width="10px" bgcolor='#e4e7f0' align="center"><input type="texte" id='commentaire1' name='commentaire1' value=""></td>
									</tr>
									<tr>
										<td width="30%" colspan="2" bgcolor='#e4e7f0'>&nbsp;2. Zone readliness at the start of presentation ?<br>(punctuality,cleanliness...)</td>
										<td width="10px" bgcolor='#e4e7f0' align="center"><input type="radio" id='question2' name='question2' value="Dissatisfied"></td>
										<td width="10px" bgcolor='#e4e7f0' align="center"><input type="radio" id='question2' name='question2' value="Somehow Dissatisfied"></td>
										<td width="10px" bgcolor='#e4e7f0' align="center"><input type="radio" id='question2' name='question2' value="Satisfied"></td>
										<td width="10px" bgcolor='#e4e7f0' align="center"><input type="radio" id='question2' name='question2' checked value="Totally Satisfied"></td>
										<td width="10px" bgcolor='#e4e7f0' align="center"><input type="texte" id='commentaire2' name='commentaire2' value=""></td>
									</tr>
									<tr>
										<td width="30%" colspan="2" bgcolor='#e4e7f0'>&nbsp;3. Support provided by ATR team during your presentation?</td>
										<td width="10px" bgcolor='#e4e7f0' align="center"><input type="radio" id='question3' name='question3' value="Dissatisfied"></td>
										<td width="10px" bgcolor='#e4e7f0' align="center"><input type="radio" id='question3' name='question3' value="Somehow Dissatisfied"></td>
										<td width="10px" bgcolor='#e4e7f0' align="center"><input type="radio" id='question3' name='question3' value="Satisfied"></td>
										<td width="10px" bgcolor='#e4e7f0' align="center"><input type="radio" id='question3' name='question3' checked value="Totally Satisfied"></td>
										<td width="10px" bgcolor='#e4e7f0' align="center"><input type="texte" id='commentaire3' name='commentaire3' value=""></td>
									</tr>
									<tr>
										<td width="30%" colspan="2" bgcolor='#e4e7f0'>&nbsp;4. Quality/Conformity of the area presented?</td>
										<td width="10px" bgcolor='#e4e7f0' align="center"><input type="radio" id='question4' name='question4' value="Dissatisfied"></td>
										<td width="10px" bgcolor='#e4e7f0' align="center"><input type="radio" id='question4' name='question4' value="Somehow Dissatisfied"></td>
										<td width="10px" bgcolor='#e4e7f0' align="center"><input type="radio" id='question4' name='question4' value="Satisfied"></td>
										<td width="10px" bgcolor='#e4e7f0' align="center"><input type="radio" id='question4' name='question4' checked value="Totally Satisfied"></td>
										<td width="10px" bgcolor='#e4e7f0' align="center"><input type="texte" id='commentaire4' name='commentaire4' value=""></td>
									</tr>
									<tr><td height="4" bgcolor='#e4e7f0' colspan="7"></td></tr>
									<tr>
										<td colspan="7" bgcolor='#e4e7f0' align='center' style='height:25px;' valign='center'>
											<a style='text-decoration:none;' class='Bouton' href='javascript:Ajouter()'>&nbsp;Ajouter&nbsp;</a>
										</td>
									</tr>
								</table>
							</td>
							<td width='30%' valign='top'>		
								<table id='tab_Visite' width="100%" cellpadding='0' cellspacing='0'>
									<tr bgcolor="black" align="center">
										<td width="40%" class='Libelle' style="color:white;">Visite</td>
										<td width="10%" class='Libelle' style="color:white;">Dissatisfied</td>
										<td width="10%" class='Libelle' style="color:white;">Somehow Dissatisfied</td>
										<td width="10%" class='Libelle' style="color:white;">Satisfied</td>
										<td width="10%" class='Libelle' style="color:white;">Totally Satisfied</td>
										<td width="20%" class='Libelle' style="color:white;">Commentaire</td>
										<td style="color:white;"></td>
									</tr>
									<?php
										$listeVisite="";
										if($_GET['Mode']=="M"){
											$req="SELECT Id_Visite, Presentation,Zone,Support,Quality,Commentaire1,Commentaire2,Commentaire3,Commentaire4,DateVisite, ";
											$req.="(SELECT Libelle FROM sp_atrvisite WHERE sp_atrvisite.Id=sp_atrmsn_customer.Id_Visite) AS Visite ";
											$req.="FROM sp_atrmsn_customer WHERE Id_MSN=".$Ligne['Id'];
											$resultVisite=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($resultVisite);
											if ($nbResulta>0){
												while($rowVisite=mysqli_fetch_array($resultVisite)){
													$Id=$rowVisite['Id_Visite']."_".$rowVisite['Presentation']."_".$rowVisite['Zone']."_".$rowVisite['Support']."_".$rowVisite['Quality']."_".addslashes($rowVisite['Commentaire1'])."_".addslashes($rowVisite['Commentaire2'])."_".addslashes($rowVisite['Commentaire3'])."_".addslashes($rowVisite['Commentaire4'])."_".AfficheDateFR($rowVisite['DateVisite']).";";
													$Id2=$rowVisite['Id_Visite']."_".$rowVisite['Presentation']."_".$rowVisite['Zone']."_".$rowVisite['Support']."_".$rowVisite['Quality']."_".$rowVisite['Commentaire1']."_".$rowVisite['Commentaire2']."_".$rowVisite['Commentaire3']."_".$rowVisite['Commentaire4']."_".AfficheDateFR($rowVisite['DateVisite']).";";
													$listeVisite.=$Id2;
													echo "<tr id=\"".$Id2."_1\">";
														echo "<td bgcolor='#a0d8d4' style=\"font-weight:bold;\" colspan='6'>".$rowVisite['Visite']."&nbsp;&nbsp;&nbsp; (".AfficheDateFR($rowVisite['DateVisite']).")</td>";
														echo "<td bgcolor='#a0d8d4'><a style='text-decoration:none;' href=\"javascript:Supprimer('".$Id."')\">&nbsp;<img src='../../../Images/Suppression2.gif' border='0' alt='Suppr' title='Suppr'>&nbsp;&nbsp;</a></td>";
													echo "</tr>";
													echo "<tr id=\"".$Id2."_2\">";
														echo "<td style=\"border-bottom:dotted 1px #000000;\">1. Presentation planning<br>(communication,notification in due time...)</td>";
														if($rowVisite['Presentation']=="Dissatisfied"){echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\">X</td>";}
														else{echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\"></td>";}
														if($rowVisite['Presentation']=="Somehow Dissatisfied"){echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\">X</td>";}
														else{echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\"></td>";}
														if($rowVisite['Presentation']=="Satisfied"){echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\">X</td>";}
														else{echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\"></td>";}
														if($rowVisite['Presentation']=="Totally Satisfied"){echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\">X</td>";}
														else{echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\"></td>";}
														echo "<td style=\"border-bottom:dotted 1px #000000;\">".stripslashes($rowVisite['Commentaire1'])."</td>";
														echo "<td style=\"border-bottom:dotted 1px #000000;\"></td>";
													echo "</tr>";
													echo "<tr id=\"".$Id2."_3\">";
														echo "<td style=\"border-bottom:dotted 1px #000000;\">2. Zone readliness at the start of presentation ?<br>(punctuality,cleanliness...)</td>";
														if($rowVisite['Zone']=="Dissatisfied"){echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\">X</td>";}
														else{echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\"></td>";}
														if($rowVisite['Zone']=="Somehow Dissatisfied"){echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\">X</td>";}
														else{echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\"></td>";}
														if($rowVisite['Zone']=="Satisfied"){echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\">X</td>";}
														else{echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\"></td>";}
														if($rowVisite['Zone']=="Totally Satisfied"){echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\">X</td>";}
														else{echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\"></td>";}
														echo "<td style=\"border-bottom:dotted 1px #000000;\">".stripslashes($rowVisite['Commentaire2'])."</td>";
														echo "<td style=\"border-bottom:dotted 1px #000000;\"></td>";
													echo "</tr>";
													echo "<tr id=\"".$Id2."_4\">";
														echo "<td style=\"border-bottom:dotted 1px #000000;\">3. Support provided by ATR team during your presentation?</td>";
														if($rowVisite['Support']=="Dissatisfied"){echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\">X</td>";}
														else{echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\"></td>";}
														if($rowVisite['Support']=="Somehow Dissatisfied"){echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\">X</td>";}
														else{echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\"></td>";}
														if($rowVisite['Support']=="Satisfied"){echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\">X</td>";}
														else{echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\"></td>";}
														if($rowVisite['Support']=="Totally Satisfied"){echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\">X</td>";}
														else{echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\"></td>";}
														echo "<td style=\"border-bottom:dotted 1px #000000;\">".stripslashes($rowVisite['Commentaire3'])."</td>";
														echo "<td style=\"border-bottom:dotted 1px #000000;\"></td>";
													echo "</tr>";
													echo "<tr id=\"".$Id2."_5\">";
														echo "<td style=\"border-bottom:dotted 1px #000000;\">4. Quality/Conformity of the area presented?</td>";
														if($rowVisite['Quality']=="Dissatisfied"){echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\">X</td>";}
														else{echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\"></td>";}
														if($rowVisite['Quality']=="Somehow Dissatisfied"){echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\">X</td>";}
														else{echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\"></td>";}
														if($rowVisite['Quality']=="Satisfied"){echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\">X</td>";}
														else{echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\"></td>";}
														if($rowVisite['Quality']=="Totally Satisfied"){echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\">X</td>";}
														else{echo "<td align='center' valign='center' style=\"border-bottom:dotted 1px #000000;\"></td>";}
														echo "<td style=\"border-bottom:dotted 1px #000000;\">".stripslashes($rowVisite['Commentaire4'])."</td>";
														echo "<td style=\"border-bottom:dotted 1px #000000;\"></td>";
													echo "</tr>";
												}
											}
										}
									?>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="display:none;">
					<input id="visites" name="visites" value="<?php echo $listeVisite;?>"  readonly="readonly">
				</td>
			</tr>
			<tr>
				<td align="center">
					<input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="M"){echo "Valider";}else{echo "Ajouter";}?>">
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$requete="DELETE FROM sp_atrmsn ";
		$requete.=" WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
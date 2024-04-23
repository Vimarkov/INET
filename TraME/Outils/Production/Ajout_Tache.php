<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="Tache.js?time=<?php echo time();?>"></script>
	<script>
		function FermerEtRecharger(){
			window.opener.location = "Tache.php";
			window.close();
		}
		function AfficherChoix(){
			var elements = document.getElementsByClassName("listeChoix");
			if(document.getElementById('typeInfo').value=="Menu deroulant"){
				for(var i=0, l=elements.length; i<l; i++){
					elements[i].style.display="";
				}
			}
			else{
				for(var i=0, l=elements.length; i<l; i++){
					elements[i].style.display="none";
				}
			}
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
if($_POST){
	if($_POST['Mode']=="A"){
		$requete="INSERT INTO trame_tache (Libelle,CritereOTD,Id_FamilleTache,Id_Prestation,Id_CL,NiveauControle,Delais,Recurrent) ";
		$requete.="VALUES ('".addslashes($_POST['libelle'])."','".addslashes($_POST['critereOTD'])."',".$_POST['famille'].",".$_SESSION['Id_PrestationTR'].",".$_POST['checklist'].",".$_POST['niveauControle'].",".$_POST['delais'].",".$_POST['recurrent'].") ";
		$result=mysqli_query($bdd,$requete);
		$IdTache = mysqli_insert_id($bdd);
		
		if($IdTache>0){
			//Ajout historique CL si renseigné
			if($_POST['checklist']<>"0"){
				$requete="INSERT INTO trame_tache_historique_cl (Id_Tache,Id_Prestation,Id_CL,Niveau,DateModif,Id_Personne) ";
				$requete.="VALUES (".$IdTache.",".$_SESSION['Id_PrestationTR'].",".$_POST['checklist'].",".$_POST['niveauControle'].",'".date('Y-m-d')."',".$_SESSION['Id_PersonneTR'].") ";
				$result=mysqli_query($bdd,$requete);
			}
			//Ajout des workpackages
			$tab = explode(";",$_POST['lesWP']);
			foreach($tab as $valeur){
				 if($valeur<>""){
					$req="INSERT INTO trame_tache_wp (Id_Tache,Id_WP,Id_Prestation) VALUES (".$IdTache.",".substr($valeur,0,-2).",".$_SESSION['Id_PrestationTR'].")";
					$resultAjour=mysqli_query($bdd,$req);
				 }
			}
			
			//Ajout des infos complémentaires
			$tab = explode(";",$_POST['lesInfos']);
			foreach($tab as $valeur){
				 if($valeur<>""){
					$tab2 = explode("_",$valeur);
					$req="INSERT INTO trame_tache_infocomplementaire (Id_Tache,Info,Type,Id_Prestation) VALUES (".$IdTache.",'".addslashes($tab2[0])."','".addslashes($tab2[1])."',".$_SESSION['Id_PrestationTR'].")";
					$resultAjour=mysqli_query($bdd,$req);
					$IdTacheInfo = mysqli_insert_id($bdd);
				
					if($tab2[1]=="Menu deroulant"){
						$tabChoix = explode("<>",$tab2[2]);
						foreach($tabChoix as $choix){
							 if($choix<>""){
								$req="INSERT INTO trame_menuderoulant (Id_Tache_InfoComplementaire,Libelle) VALUES (".$IdTacheInfo.",'".addslashes($choix)."')";
								$resultAjourTI=mysqli_query($bdd,$req);
							 }
						}
					}
				 }
			}
			
			//Ajout des UO
			$tab = explode(";",$_POST['lesUO']);
			foreach($tab as $valeur){
				 if($valeur<>""){
					$tab2 = explode("_",$valeur);
					$req="INSERT INTO trame_tache_uo (Id_Tache,Id_UO,Id_DT,TypeTravail,Complexite,Relation,Id_Prestation) ";
					$req.="VALUES (".$IdTache.",".substr($tab2[0],0,-2).",".$tab2[1].",'".$tab2[2]."','".$tab2[3]."','".$tab2[4]."',".$_SESSION['Id_PrestationTR'].")";
					$resultAjour=mysqli_query($bdd,$req);
				 }
			}
		}
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE trame_tache SET ";
		$requete.="Libelle='".addslashes($_POST['libelle'])."',";
		$requete.="Delais=".$_POST['delais'].",";
		$requete.="CritereOTD='".addslashes($_POST['critereOTD'])."',";
		$requete.="Id_FamilleTache=".$_POST['famille'].", ";
		$requete.="Id_CL=".$_POST['checklist'].", ";
		$requete.="Recurrent=".$_POST['recurrent'].", ";
		$requete.="NiveauControle=".$_POST['niveauControle']." ";
		$requete.=" WHERE Id=".$_POST['id']."";
		$result=mysqli_query($bdd,$requete);
		$IdTache = $_POST['id'];
		
		//Ajout historique CL si renseigné
		if($_POST['oldCL']<>$_POST['checklist'] || $_POST['oldNiveau']<>$_POST['niveauControle']){
			$requete="INSERT INTO trame_tache_historique_cl (Id_Tache,Id_Prestation,Id_CL,Niveau,DateModif,Id_Personne) ";
			$requete.="VALUES (".$IdTache.",".$_SESSION['Id_PrestationTR'].",".$_POST['checklist'].",".$_POST['niveauControle'].",'".date('Y-m-d')."',".$_SESSION['Id_PersonneTR'].") ";
			$result=mysqli_query($bdd,$requete);
		}
		
		//Suppression des workpackages
		$req="UPDATE trame_tache_wp SET Supprime=true WHERE Id_Tache=".$IdTache;
		$resultDelete=mysqli_query($bdd,$req);
		
		//Ajout des workpackages
		$tab = explode(";",$_POST['lesWP']);
		foreach($tab as $valeur){
			 if($valeur<>""){
				$req="SELECT Id FROM trame_tache_wp WHERE Id_Tache=".$IdTache." AND Id_WP=".substr($valeur,0,-2)." AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if($nbResulta>0){
					$req="UPDATE trame_tache_wp SET Supprime=false WHERE Id_Tache=".$IdTache." AND Id_WP=".substr($valeur,0,-2)." AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
					$resultModif=mysqli_query($bdd,$req);
				}
				else{
					$req="INSERT INTO trame_tache_wp (Id_Tache,Id_WP,Id_Prestation) VALUES (".$IdTache.",".substr($valeur,0,-2).",".$_SESSION['Id_PrestationTR'].")";
					$resultAjour=mysqli_query($bdd,$req);
				}
			 }
		}
		
		//Suppression des infos complémenaire
		$req="UPDATE trame_menuderoulant SET Supprime=1 WHERE Id_Tache_InfoComplementaire IN (SELECT Id FROM trame_tache_infocomplementaire WHERE Id_Tache=".$IdTache.")";
		$resultDelete=mysqli_query($bdd,$req);
		
		$req="UPDATE trame_tache_infocomplementaire SET Supprime=1 WHERE Id_Tache=".$IdTache;
		$resultDelete=mysqli_query($bdd,$req);
		
		//Ajout des infos complémentaires
		$tab = explode(";",$_POST['lesInfos']);
		foreach($tab as $valeur){
			 if($valeur<>""){
				$tab2 = explode("_",$valeur);
				$req="INSERT INTO trame_tache_infocomplementaire (Id_Tache,Info,Type,Id_Prestation) VALUES (".$IdTache.",'".addslashes($tab2[0])."','".addslashes($tab2[1])."',".$_SESSION['Id_PrestationTR'].")";
				$resultAjour=mysqli_query($bdd,$req);
				$IdTacheInfo = mysqli_insert_id($bdd);
				
				if($tab2[1]=="Menu deroulant"){
					$tabChoix = explode("<>",$tab2[2]);
					foreach($tabChoix as $choix){
						 if($choix<>""){
							$req="INSERT INTO trame_menuderoulant (Id_Tache_InfoComplementaire,Libelle) VALUES (".$IdTacheInfo.",'".addslashes($choix)."')";
							$resultAjourTI=mysqli_query($bdd,$req);
						 }
					}
				}
			 }
		}
		
		//Suppression des uo
		$req="DELETE FROM trame_tache_uo WHERE Id_Tache=".$IdTache;
		$resultDelete=mysqli_query($bdd,$req);
		
		//Ajout des UO
		$tab = explode(";",$_POST['lesUO']);
		foreach($tab as $valeur){
			 if($valeur<>""){
				$tab2 = explode("_",$valeur);
				$req="INSERT INTO trame_tache_uo (Id_Tache,Id_UO,Id_DT,TypeTravail,Complexite,Relation,Id_Prestation) ";
				$req.="VALUES (".$IdTache.",".substr($tab2[0],0,-2).",".$tab2[1].",'".$tab2[2]."','".$tab2[3]."','".$tab2[4]."',".$_SESSION['Id_PrestationTR'].")";
				$resultAjour=mysqli_query($bdd,$req);
			 }
		}
		if($_POST['oldNiveau']<>$_POST['niveauControle']){
			//Envoi d'un email pour avertir
			$headers='From: "Extranet AAA"<extranet@aaa-aero.com>'."\n";
			$headers.='Content-Type: text/html; charset="ansi"'."\n";

			$destinataire="";
			$req="SELECT ";
			$req.="(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=trame_acces.Id_Personne) AS EmailPro ";
			$req.="FROM trame_acces WHERE (SUBSTRING(Droit,2,1)=1 OR SUBSTRING(Droit,4,1)=1) AND Id_Prestation=".$_SESSION['Id_PrestationTR'].";";
			$resulEmail=mysqli_query($bdd,$req);
			$nbEmail=mysqli_num_rows($resulEmail);
			if ($nbEmail>0){
				while($row=mysqli_fetch_array($resulEmail)){
					if($row['EmailPro']<>""){
						$destinataire.=$row['EmailPro'].",";
					}
				}
			}

			if($destinataire<>""){
				$req="SELECT Libelle FROM trame_tache WHERE Id=".$IdTache;
				$result=mysqli_query($bdd,$req);
				$nb=mysqli_num_rows($result);
				$Nom="";
				if($nb>0){
					$row=mysqli_fetch_array($result);
					$Nom=$row['Libelle'];
				}
				if($_SESSION['Langue']=="EN"){$object="TraME - Changing a Level";}
				else{$object="TraME - Modification d'un niveau";}

				$message="<html>";
				$message.="<head>";
					$message.="<title>TraME</title>";
				$message.="</head>";
				$message.="<body>";
				$message.="<table width='100%'>";
				
				$AncienNiveau=$_POST['oldNiveau'];
				if($AncienNiveau==-1){$AncienNiveau="M";}
				$NouveauNiveau=$_POST['niveauControle'];
				if($NouveauNiveau==-1){$NouveauNiveau="M";}
				if($_SESSION['Langue']=="EN"){
						$message.="<tr><td>The level of the \"".$Nom."\" task has been changed.<br> Old level : ".$AncienNiveau."<br> New level : ".$NouveauNiveau."</td></tr>";
				}
				else{
					$message.="<tr><td>Le niveau de la tâche \"".$Nom."\" a été modifié.<br> Ancien niveau : ".$AncienNiveau."<br> Nouveau niveau : ".$NouveauNiveau."</td></tr>";
				}
				$message.="</table></td></tr>";
				$message.="</table></body></html>";

				if(mail($destinataire, $object , $message , $headers,'-f extranet@aaa-aero.com')){
					echo "<script>window.close();</script>";
				}
				else{
					if($_SESSION['Langue']=="EN"){
						echo"<script language=\"javascript\">alert('".addslashes("The mail was not sent")."')</script>";
					}
					else{
						echo"<script language=\"javascript\">alert('".addslashes("Le mail n'a pas été envoyé")."')</script>";
					}
				}	
			}
			else{
				if($_SESSION['Langue']=="EN"){
					echo"<script language=\"javascript\">alert('".addslashes("The mail was not sent")."')</script>";
				}
				else{
					echo"<script language=\"javascript\">alert('".addslashes("Le mail n'a pas été envoyé")."')</script>";
				}
			}
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Id=$_GET['Id'];
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
		//Liste des tâches
		$reqTache="SELECT Libelle FROM trame_tache WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Supprime=false ";
		if($_GET['Id']!='0')
		{
			$reqTache.="AND Id<>".$_GET['Id'];
			$req="SELECT Id, Libelle, Delais, Id_FamilleTache, CritereOTD, Id_CL, NiveauControle, Recurrent FROM trame_tache WHERE Id=".$_GET['Id']." ;";
			$result=mysqli_query($bdd,$req);
			$Ligne=mysqli_fetch_array($result);
		}
		$resultTache=mysqli_query($bdd,$reqTache);
		$nbResultaTache=mysqli_num_rows($resultTache);
		if ($nbResultaTache>0){
			$i=0;
			while($rowTache=mysqli_fetch_array($resultTache)){
				echo "<script>Liste_Tache[".$i."]=\"".$rowTache['Libelle']."\"</script>";
				$i++;
			}
		}
?>

		<form id="formulaire" method="POST" action="Ajout_Tache.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="id" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<input type="hidden" name="oldCL" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id_CL'];}?>">
		<input type="hidden" name="oldNiveau" value="<?php if($_GET['Mode']=="M"){echo $Ligne['NiveauControle'];}?>">
		<table width="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Task";}else{echo "Tâche";} ?></td>
				<td colspan="4">
					<input type="texte" name="libelle" id="libelle" size="90" value="<?php if($_GET['Mode']=="M"){echo stripslashes(str_replace("\\","",$Ligne['Libelle']));}?>">
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Deadlines to be filled in";}else{echo "Délais à renseigner";} ?></td>
				<td>
					<select id="delais" name="delais">
						<option value='0' <?php if($_GET['Mode']=="M"){if($Ligne['Delais']==0){echo "selected";}}else{echo "selected";} ?>>Non</option>
						<option value='1' <?php if($_GET['Mode']=="M"){if($Ligne['Delais']==1){echo "selected";}} ?>>Oui</option>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Can be recurrent";}else{echo "Peut être récurrente";} ?></td>
				<td>
					<select id="recurrent" name="recurrent">
						<option value='0' <?php if($_GET['Mode']=="M"){if($Ligne['Recurrent']==0){echo "selected";}}else{echo "selected";} ?>>Non</option>
						<option value='1' <?php if($_GET['Mode']=="M"){if($Ligne['Recurrent']==1){echo "selected";}} ?>>Oui</option>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Task family";}else{echo "Famille de tâche";} ?></td>
				<td>
					<select id="famille" name="famille">
						<?php
							echo"<option value='0'></option>";
							$req="SELECT Id, Libelle, Supprime FROM trame_familletache WHERE Id_Prestation=".$_SESSION['Id_PrestationTR'].";";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowFamille=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="M"){
										if($rowFamille['Id']==$Ligne['Id_FamilleTache']){$selected="selected";}
									}
									if($rowFamille['Supprime']==false  || $rowFamille['Id']==$Ligne['Id_Categorie']){
										echo "<option value='".$rowFamille['Id']."' ".$selected.">".$rowFamille['Libelle']."</option>";
									}
								}
							}
						?>
					</select>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "OTD";}else{echo "Crtière OTD";} ?></td>
				<td>
					<input type="texte" name="critereOTD" id="critereOTD" size="50" value="<?php if($_GET['Mode']=="M"){echo $Ligne['CritereOTD'];}?>">
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Check-list";}else{echo "Check-list";} ?></td>
				<td>
					<select id="checklist" name="checklist">
						<?php
							echo"<option value='0'></option>";
							$req="SELECT Id, Libelle, Supprime FROM trame_checklist WHERE Id_Prestation=".$_SESSION['Id_PrestationTR'].";";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowCL=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="M"){
										if($rowCL['Id']==$Ligne['Id_CL']){$selected="selected";}
									}
									if($rowCL['Supprime']==false  || $rowCL['Id']==$Ligne['Id_CL']){
										echo "<option value='".$rowCL['Id']."' ".$selected.">".$rowCL['Libelle']."</option>";
									}
								}
							}
						?>
					</select>
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Level of Control";}else{echo "Niveau de contrôle";} ?></td>
				<td>
					<select id="niveauControle" name="niveauControle">
						<option value='0' <?php if($_GET['Mode']=="M"){if($Ligne['NiveauControle']==0){echo "selected";}}?>>0</option>
						<option value='1' <?php if($_GET['Mode']=="M"){if($Ligne['NiveauControle']==1){echo "selected";}}?>>1</option>
						<option value='2' <?php if($_GET['Mode']=="M"){if($Ligne['NiveauControle']==2){echo "selected";}}?>>2</option>
						<option value='3' <?php if($_GET['Mode']=="M"){if($Ligne['NiveauControle']==3){echo "selected";}}?>>3</option>
						<option value='4' <?php if($_GET['Mode']=="M"){if($Ligne['NiveauControle']==4){echo "selected";}}?>>4</option>
						<option value='-1' <?php if($_GET['Mode']=="M"){if($Ligne['NiveauControle']==-1){echo "selected";}}?>>M</option>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width='20%' class="Libelle" valign="top">
					<table cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
						<tr>
							<td bgcolor='#e4e7f0' colspan='2'><i>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add workpackages";}else{echo "Ajouter les workpackages";}?></i></td>
						</tr>
						<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; <?php if($_SESSION['Langue']=="EN"){echo "Workpackages";}else{echo "Workpackages";}?> : </td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; 
								<div id='Div_WP' style='height:200px;width:200px;overflow:auto;'>
									<?php
									echo "<table width='100%'>";
									$req="SELECT Id, Libelle FROM trame_wp WHERE Supprime=false AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle ";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										$i=0;
										while($rowWP=mysqli_fetch_array($result)){
											echo "<tr><td><input type='checkbox' class='wps' name='".$rowWP['Id']."' value='".$rowWP['Id']."'>".$rowWP['Libelle']."</td></tr>";
											echo "<script>Liste_WP[".$i."] = new Array('".$rowWP['Id']."','".addslashes($rowWP['Libelle'])."');</script>\n";
											$i+=1;
										}
									}
									echo "</table>";
									?>
								</div>
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
								<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterWP()'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}?>&nbsp;</a>
							</td>
						</tr>
					</table>
				</td>
				<td width='30%' valign='top'>
					<table id="tab_WP" width='100%' cellpadding='0' cellspacing='0'>
						<tr><td class="Libelle" width="70%"><?php if($_SESSION['Langue']=="EN"){echo "Workpackage";}else{echo "Workpackage";}?></td>
						<?php
								$listeWP="";
								if($_GET['Mode']=="M"){
									$req="SELECT trame_tache_wp.Id_WP,trame_wp.Libelle FROM trame_tache_wp LEFT JOIN trame_wp ON trame_tache_wp.Id_WP=trame_wp.Id WHERE trame_tache_wp.Supprime=false AND Id_Tache=".$Id." ORDER BY Libelle ";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowWP=mysqli_fetch_array($result)){
											$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerWP('".$rowWP['Id_WP']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
											echo "<tr id='".$rowWP['Id_WP']."'><td>".stripslashes($rowWP['Libelle'])."</td><td>".$btn."</td></tr>";
											$listeWP.=";".$rowWP['Id_WP']."WP";
										}
									}
								}
							?>
						</tr>
					</table>
				</td>
				<td width='20%' class="Libelle" valign="top">
					<table cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
						<tr>
							<td bgcolor='#e4e7f0' colspan='2'><i>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add additional information";}else{echo "Ajouter les informations complémentaires";}?></i></td>
						</tr>
						<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; <?php if($_SESSION['Langue']=="EN"){echo "Information";}else{echo "Information";}?> : </td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; 
								<input style="text-align:center;" id="information" name="information" onkeypress="if(event.keyCode == 13)AjouterInfo('<?php echo $_SESSION['Langue'];?>')" size="15" type="text" value="">
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; <?php if($_SESSION['Langue']=="EN"){echo "Type";}else{echo "Type";}?> : </td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; 
							<select id="typeInfo" name="typeInfo" style="width:100px;" onchange="AfficherChoix();" onkeypress="if(event.keyCode == 13){AjouterInfo('<?php echo $_SESSION['Langue'];?>')}">
								<option value=""></option>
								<option value="Texte"><?php if($_SESSION['Langue']=="EN"){echo "Text";}else{echo "Texte";}?></option>
								<option value="Menu deroulant"><?php if($_SESSION['Langue']=="EN"){echo "Drop-down menu";}else{echo "Menu deroulant";}?></option>
								<option value="Numerique"><?php if($_SESSION['Langue']=="EN"){echo "Digital";}else{echo "Numerique";}?></option>
								<option value="Date"><?php if($_SESSION['Langue']=="EN"){echo "Date";}else{echo "Date";}?></option>
								<option value="Oui/Non"><?php if($_SESSION['Langue']=="EN"){echo "Yes/No";}else{echo "Oui/Non";}?></option>
							</select>
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0' class="listeChoix" style="display:none;">&nbsp; <?php if($_SESSION['Langue']=="EN"){echo "Choice";}else{echo "Choix";}?> : </td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0' class="listeChoix" style="display:none;">&nbsp; 
								<input style="text-align:center;" id="choix1" name="choix1" size="15" type="text" value="">
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0' class="listeChoix" style="display:none;">&nbsp; 
								<input style="text-align:center;" id="choix2" name="choix2" size="15" type="text" value="">
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0' class="listeChoix" style="display:none;">&nbsp; 
								<input style="text-align:center;" id="choix3" name="choix3" size="15" type="text" value="">
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0' class="listeChoix" style="display:none;">&nbsp; 
								<input style="text-align:center;" id="choix4" name="choix4" size="15" type="text" value="">
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0' class="listeChoix" style="display:none;">&nbsp; 
								<input style="text-align:center;" id="choix5" name="choix5" size="15" type="text" value="">
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
								<a style='text-decoration:none;' class='Bouton' href="javascript:AjouterInfo('<?php echo $_SESSION['Langue'];?>')">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}?>&nbsp;</a>
							</td>
						</tr>
					</table>
				</td>
				<td width='35%' valign='top'>
					<table id="tab_Info" width='100%' cellpadding='0' cellspacing='0'>
						<tr><td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Information";}else{echo "Information";}?></td><td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Type";}else{echo "Type";}?></td>
						<?php
								$listeInfos="";
								if($_GET['Mode']=="M"){
									$req="SELECT Id,Info,Type FROM trame_tache_infocomplementaire WHERE Supprime=0 AND Id_Tache=".$Id." ORDER BY Info ";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowInfo=mysqli_fetch_array($result)){
											$type=$rowInfo['Type'];
											if($_SESSION['Langue']=="EN"){
												if($type=="Texte"){$type="Text";}
												elseif($type=="Numerique"){$type="Digital";}
												elseif($type=="Date"){$type="Date";}
												elseif($type=="Menu deroulant"){$type="Drop-down menu";}
											}
											
											$menu="";
											$listeMenu="";
											if($type=="Menu deroulant"){
												$req="SELECT Libelle FROM trame_menuderoulant WHERE Id_Tache_InfoComplementaire=".$rowInfo['Id']." AND Supprime=0 ORDER BY Libelle ";
												$resultMenu=mysqli_query($bdd,$req);
												$nbResultaMenu=mysqli_num_rows($resultMenu);
												if ($nbResultaMenu>0){
													$menu.=" [";
													$listeMenu.="_";
													while($rowMenu=mysqli_fetch_array($resultMenu)){
														if($menu<>" ["){$menu.="|";$listeMenu.="<>";}
														$menu.=stripslashes($rowMenu['Libelle']);
														$listeMenu.=stripslashes($rowMenu['Libelle']);
													}
													$menu.="]";
												}
											}
								
											$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerInfo('".addslashes($rowInfo['Info']."_".$rowInfo['Type'].$listeMenu)."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
											echo "<tr id=\"".$rowInfo['Info']."_".$rowInfo['Type'].$listeMenu."\"><td>".stripslashes($rowInfo['Info'])."</td><td>".$type.$menu."</td><td>".$btn."</td></tr>";
											$listeInfos.=";".$rowInfo['Info']."_".$rowInfo['Type'].$listeMenu;
										}
									}
								}
							?>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width='20%' class="Libelle" valign="top">
					<table cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
						<tr>
							<td bgcolor='#e4e7f0' colspan='2'><i>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Work unit list";}else{echo "Liste des unités d'oeuvres";}?></i></td>
						</tr>
						<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; <?php if($_SESSION['Langue']=="EN"){echo "Work unit";}else{echo "UO";}?> : </td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; 
							<select id="uo" name="uo" style="width:130px;" onkeypress="if(event.keyCode == 13)AjouterUO()">
								<?php
								echo"<option name='0' value='0'></option>";
								$req="SELECT Id, Description FROM trame_uo WHERE Supprime=false AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									$i=0;
									while($rowUO=mysqli_fetch_array($result)){
										echo "<option value='".$rowUO['Id']."'>".$rowUO['Description']."</option>";
										echo "<script>Liste_UO[".$i."] = new Array('".$rowUO['Id']."','".addslashes($rowUO['Description'])."');</script>\n";
										$i+=1;
									}
								}
								?>
							</select>
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; <?php if($_SESSION['Langue']=="EN"){echo "Technical domain";}else{echo "Domainte Tech.";}?> : </td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; 
							<select id="dt" name="dt" style="width:90px;" onkeypress="if(event.keyCode == 13)AjouterUO()">
								<?php
								echo"<option name='0' value='0'></option>";
								$req="SELECT Id, Libelle FROM trame_domainetechnique WHERE Supprime=false AND Id_Prestation=".$_SESSION['Id_PrestationTR']." ";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									$i=0;
									while($rowDT=mysqli_fetch_array($result)){
										echo "<option value='".$rowDT['Id']."'>".$rowDT['Libelle']."</option>";
										echo "<script>Liste_DT[".$i."] = new Array('".$rowDT['Id']."','".addslashes($rowDT['Libelle'])."');</script>\n";
										$i+=1;
									}
								}
								?>
							</select>
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; <?php if($_SESSION['Langue']=="EN"){echo "Type of work";}else{echo "Type de travail";}?> : </td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; 
							<select id="tt" name="tt" style="width:90px;" onkeypress="if(event.keyCode == 13)AjouterUO()">
								<option value=''></option>
								<option value='Creation'>Creation</option>
								<option value='Update'>Update</option>
							</select>
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; <?php if($_SESSION['Langue']=="EN"){echo "Complexity";}else{echo "Complexité";}?> : </td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; 
							<select id="complexite" name="complexite" style="width:90px;" onkeypress="if(event.keyCode == 13)AjouterUO()">
								<option value=''></option>
								<option value='Low'>Low</option>
								<option value='Medium'>Medium</option>
								<option value='High'>High</option>
								<option value="Very High">Very High</option>
								<option value="Other">Other</option>
							</select>
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; <?php if($_SESSION['Langue']=="EN"){echo "Relation";}else{echo "Relation";}?> : </td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; 
							<select id="relation" name="relation" style="width:90px;" onkeypress="if(event.keyCode == 13)AjouterUO()">
								<option value=''></option>
								<option value='Mandatory'>Mandatory</option>
								<option value='Optional'>Optional</option>
							</select>
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
								<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterUO()'>&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}?>&nbsp;</a>
							</td>
						</tr>
					</table>
				</td>
				<td colspan="6" valign='top'>
					<table id="tab_UO" width='100%' cellpadding='0' cellspacing='0'>
						<tr>
							<td class="Libelle" width="40%"><?php if($_SESSION['Langue']=="EN"){echo "Work unit";}else{echo "Unité d'oeuvre";}?></td>
							<td class="Libelle" width="15%"><?php if($_SESSION['Langue']=="EN"){echo "Technical domain";}else{echo "Domaine technique";}?></td>
							<td class="Libelle" width="15%"><?php if($_SESSION['Langue']=="EN"){echo "Type of work";}else{echo "Type de travail";}?></td>
							<td class="Libelle" width="15%"><?php if($_SESSION['Langue']=="EN"){echo "Complexity";}else{echo "Complexité";}?></td>
							<td class="Libelle" width="15%"><?php if($_SESSION['Langue']=="EN"){echo "Relation";}else{echo "Relation";}?></td>
							<td></td>
						<?php
								$listeUO="";
								if($_GET['Mode']=="M"){
									$req="SELECT Id_UO,Id_DT, Complexite, Relation, TypeTravail, ";
									$req.="(SELECT Description FROM trame_uo WHERE trame_uo.Id=trame_tache_uo.Id_UO) AS UO, ";
									$req.="(SELECT Libelle FROM trame_domainetechnique WHERE trame_domainetechnique.Id=trame_tache_uo.Id_DT) AS DT ";
									$req.="FROM trame_tache_uo WHERE Id_Tache=".$Id." ORDER BY UO ";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowUO=mysqli_fetch_array($result)){
											$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerUO('".$rowUO['Id_UO']."UO_".$rowUO['Id_DT']."_".$rowUO['TypeTravail']."_".$rowUO['Complexite']."_".$rowUO['Relation']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
											echo "<tr id='".$rowUO['Id_UO']."UO_".$rowUO['Id_DT']."_".$rowUO['TypeTravail']."_".$rowUO['Complexite']."_".$rowUO['Relation']."'><td style='border-bottom:1px dotted #000000'>".stripslashes($rowUO['UO'])."</td><td style='border-bottom:1px dotted #000000'>".stripslashes($rowUO['DT'])."</td><td style='border-bottom:1px dotted #000000'>".stripslashes($rowUO['TypeTravail'])."</td><td style='border-bottom:1px dotted #000000'>".stripslashes($rowUO['Complexite'])."</td><td style='border-bottom:1px dotted #000000'>".stripslashes($rowUO['Relation'])."</td><td style='border-bottom:1px dotted #000000'>".$btn."</td></tr>";
											$listeUO.=";".$rowUO['Id_UO']."UO_".$rowUO['Id_DT']."_".$rowUO['TypeTravail']."_".$rowUO['Complexite']."_".$rowUO['Relation'];
										}
									}
								}
							?>
						</tr>
					</table>
				</td>
			</tr>
			<tr style="display:none;"><td><input id="lesWP" name="lesWP" value="<?php echo $listeWP;?>" readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="lesInfos" name="lesInfos" value="<?php echo $listeInfos;?>" readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="lesUO" name="lesUO" value="<?php echo $listeUO;?>" readonly="readonly"></td></tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td colspan="4" align="center">
					<input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="M"){if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}}else{if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}}?>">
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$requete="UPDATE trame_tache SET ";
		$requete.="Supprime=true ";
		$requete.=" WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
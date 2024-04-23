<!DOCTYPE html>
<html>
	<head>
		<title>TraME</title><meta name="robots" content="noindex">
		<link rel="stylesheet" href="../JS/styleCalendrier.css?t=<?php echo time(); ?>">
		<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
		<link href="../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="../JS/date.js"></script>
		<script type="text/javascript" src="../JS/jquery.min.js"></script>
		<!-- HTML5 Shim -->
		<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->
		<!-- Modernizr -->
		<script src="../JS/modernizr.js"></script>
		<!-- jQuery  -->
		<script src="../JS/js/jquery-1.4.3.min.js"></script>
		<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
		<script>
		function Recharger(){
			opener.location="Validation.php";
		}
		function FermerEtRecharger(){
			window.opener.location = "Validation.php";
			window.close();
		}
		</script>
	</head>
	
	<body>
		<?php
		session_start();
		require("../Connexioni.php");
		require("../Fonctions.php");
		
		$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
		Ecrire_Code_JS_Init_Date();
		if($_POST){
			if($_POST['reference']<>"" && strpos($_SESSION['VALI_Reference2'],$_POST['reference'].";")===false){
				$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('reference','".$_POST['reference']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				$_SESSION['VALI_Reference']=$_SESSION['VALI_Reference'].$_POST['reference'].$btn;
				$_SESSION['VALI_Reference2']=$_SESSION['VALI_Reference2'].$_POST['reference'].";";
				
				RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"Reference",$_SESSION['VALI_Reference'],$_SESSION['VALI_Reference2']);
			}
			if($_POST['motCles']<>"" && strpos($_SESSION['VALI_MotCles2'],$_POST['motCles'].";")===false){
				$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('motCles','".$_POST['motCles']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				$_SESSION['VALI_MotCles']=$_SESSION['VALI_MotCles'].$_POST['motCles'].$btn;
				$_SESSION['VALI_MotCles2']=$_SESSION['VALI_MotCles2'].$_POST['motCles'].";";
				
				RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"MotCles",$_SESSION['VALI_MotCles'],$_SESSION['VALI_MotCles2']);
			}
			$left=substr($_POST['wp'],0,strpos($_POST['wp'],";"));
			if($_POST['wp']<>"" && strpos($_SESSION['VALI_WP2'],$left.";")===false){
				$right=substr($_POST['wp'],strpos($_POST['wp'],";")+1);
				$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('wp','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				$_SESSION['VALI_WP']=$_SESSION['VALI_WP'].$right.$btn;
				$_SESSION['VALI_WP2']=$_SESSION['VALI_WP2'].$left.";";
				
				RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"WP",$_SESSION['VALI_WP'],$_SESSION['VALI_WP2']);
			}
			$left=substr($_POST['tache'],0,strpos($_POST['tache'],";"));
			if($_POST['tache']<>"" && strpos($_SESSION['VALI_Tache2'],$left.";")===false){
				$right=substr($_POST['tache'],strpos($_POST['tache'],";")+1);
				$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('tache','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				$_SESSION['VALI_Tache']=$_SESSION['VALI_Tache'].$right.$btn;
				$_SESSION['VALI_Tache2']=$_SESSION['VALI_Tache2'].$left.";";
				
				RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"Tache",$_SESSION['VALI_Tache'],$_SESSION['VALI_Tache2']);
			}
			$left=substr($_POST['preparateur'],0,strpos($_POST['preparateur'],";"));
			if($_POST['preparateur']<>"" && strpos($_SESSION['VALI_Preparateur2'],$left.";")===false){
				$right=substr($_POST['preparateur'],strpos($_POST['preparateur'],";")+1);
				$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('preparateur','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				$_SESSION['VALI_Preparateur']=$_SESSION['VALI_Preparateur'].$right.$btn;
				$_SESSION['VALI_Preparateur2']=$_SESSION['VALI_Preparateur2'].$left.";";
				
				RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"Preparateur",$_SESSION['VALI_Preparateur'],$_SESSION['VALI_Preparateur2']);
			}
			$left=substr($_POST['controleur'],0,strpos($_POST['controleur'],";"));
			if($_POST['controleur']<>"" && strpos($_SESSION['VALI_Controleur2'],$left.";")===false){
				$right=substr($_POST['controleur'],strpos($_POST['controleur'],";")+1);
				$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('controleur','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				$_SESSION['VALI_Controleur']=$_SESSION['VALI_Controleur'].$right.$btn;
				$_SESSION['VALI_Controleur2']=$_SESSION['VALI_Controleur2'].$left.";";
				
				RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"Controleur",$_SESSION['VALI_Controleur'],$_SESSION['VALI_Controleur2']);
			}
			$left=substr($_POST['statutPROD'],0,strpos($_POST['statutPROD'],";"));
			if($_POST['statutPROD']<>"" && strpos($_SESSION['VALI_Statut2'],$left.";")===false){
				$right=substr($_POST['statutPROD'],strpos($_POST['statutPROD'],";")+1);
				$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','".$_POST['statutPROD']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				$_SESSION['VALI_Statut']=$_SESSION['VALI_Statut'].$right.$btn;
				$_SESSION['VALI_Statut2']=$_SESSION['VALI_Statut2'].$left.";";
				
				RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"Statut",$_SESSION['VALI_Statut'],$_SESSION['VALI_Statut2']);
			}
			$left=substr($_POST['delai'],0,strpos($_POST['delai'],";"));
			if($_POST['delai']<>"" && strpos($_SESSION['VALI_Delai2'],$left.";")===false){
				$right=substr($_POST['delai'],strpos($_POST['delai'],";")+1);
				$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('delai','".$_POST['delai']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				$_SESSION['VALI_Delai']=$_SESSION['VALI_Delai'].$right.$btn;
				$_SESSION['VALI_Delai2']=$_SESSION['VALI_Delai2'].$left.";";
				
				RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"Delai",$_SESSION['VALI_Delai'],$_SESSION['VALI_Delai2']);
			}
			if($_POST['dateDebut']<>"" && strpos($_SESSION['VALI_DateDebut2'],$_POST['dateDebut'].";")===false){
				$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('dateDebut','".$_POST['dateDebut']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				$_SESSION['VALI_DateDebut']=$_POST['dateDebut'].$btn;
				$_SESSION['VALI_DateDebut2']=$_POST['dateDebut'];
				
				RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"DateDebut",$_SESSION['VALI_DateDebut'],$_SESSION['VALI_DateDebut2']);
			}
			if($_POST['dateFin']<>"" && strpos($_SESSION['VALI_DateFin2'],$_POST['dateFin'].";")===false){
				$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('dateFin','".$_POST['dateFin']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				$_SESSION['VALI_DateFin']=$_POST['dateFin'].$btn;
				$_SESSION['VALI_DateFin2']=$_POST['dateFin'];
				
				RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"DateFin",$_SESSION['VALI_DateFin'],$_SESSION['VALI_DateFin2']);
			}
			$left=substr($_POST['familletache'],0,strpos($_POST['familletache'],";"));
			if($_POST['familletache']<>"" && strpos($_SESSION['VALI_FamilleTache2'],$left.";")===false){
				$right=substr($_POST['familletache'],strpos($_POST['familletache'],";")+1);
				$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('familletache','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				$_SESSION['VALI_FamilleTache']=$_SESSION['VALI_FamilleTache'].$right.$btn;
				$_SESSION['VALI_FamilleTache2']=$_SESSION['VALI_FamilleTache2'].$left.";";
				
				RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"familletache",$_SESSION['VALI_FamilleTache'],$_SESSION['VALI_FamilleTache2']);
			}
			echo "<script>Recharger();</script>";
		}
		elseif($_GET){
// 			supprimer un critère
			if($_GET['Type']=="S"){
				if($_GET['critere']=="reference"){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('reference','".$_GET['valeur']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['VALI_Reference']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['VALI_Reference']);
					$_SESSION['VALI_Reference2']=str_replace($_GET['valeur'].";","",$_SESSION['VALI_Reference2']);
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"Reference",$_SESSION['VALI_Reference'],$_SESSION['VALI_Reference2']);
				}
				elseif($_GET['critere']=="motCles"){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('motCles','".$_GET['valeur']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['VALI_MotCles']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['VALI_MotCles']);
					$_SESSION['VALI_MotCles2']=str_replace($_GET['valeur'].";","",$_SESSION['VALI_MotCles2']);
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"MotCles",$_SESSION['VALI_MotCles'],$_SESSION['VALI_MotCles2']);
				}
				elseif($_GET['critere']=="wp"){
					$_SESSION['VALI_WP2']=str_replace($_GET['valeur'].";","",$_SESSION['VALI_WP2']);
					$tab = explode(";",$_SESSION['VALI_WP2']);
					$_SESSION['VALI_WP']="";
					foreach($tab as $Id){
						if($Id<>""){
							$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('wp','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
							$req="SELECT Libelle FROM trame_wp WHERE Id=".$Id;
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if($nbResulta>0){
								$row=mysqli_fetch_array($result);
								$_SESSION['VALI_WP'].=$row['Libelle'].$valeur;
							}
						}
					}
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"WP",$_SESSION['VALI_WP'],$_SESSION['VALI_WP2']);
				}
				elseif($_GET['critere']=="tache"){
					$_SESSION['VALI_Tache2']=str_replace($_GET['valeur'].";","",$_SESSION['VALI_Tache2']);
					$tab = explode(";",$_SESSION['VALI_Tache2']);
					$_SESSION['VALI_Tache']="";
					foreach($tab as $Id){
						if($Id<>""){
							$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('tache','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
							$req="SELECT Libelle FROM trame_tache WHERE Id=".$Id;
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if($nbResulta>0){
								$row=mysqli_fetch_array($result);
								$_SESSION['VALI_Tache'].=$row['Libelle'].$valeur;
							}
						}
					}
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"Tache",$_SESSION['VALI_Tache'],$_SESSION['VALI_Tache2']);
				}
				elseif($_GET['critere']=="preparateur"){
					$_SESSION['VALI_Preparateur2']=str_replace($_GET['valeur'].";","",$_SESSION['VALI_Preparateur2']);
					$tab = explode(";",$_SESSION['VALI_Preparateur2']);
					$_SESSION['VALI_Preparateur']="";
					foreach($tab as $Id){
						if($Id<>""){
							$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('preparateur','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
							$req="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$Id;
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if($nbResulta>0){
								$row=mysqli_fetch_array($result);
								$_SESSION['VALI_Preparateur'].=$row['Nom']." ".$row['Prenom'].$valeur;
							}
						}
					}
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"Preparateur",$_SESSION['VALI_Preparateur'],$_SESSION['VALI_Preparateur2']);
				}
				elseif($_GET['critere']=="controleur"){
					$_SESSION['VALI_Controleur2']=str_replace($_GET['valeur'].";","",$_SESSION['VALI_Controleur2']);
					$tab = explode(";",$_SESSION['VALI_Controleur2']);
					$_SESSION['VALI_Controleur']="";
					foreach($tab as $Id){
						if($Id<>""){
							$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('controleur','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
							$req="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$Id;
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if($nbResulta>0){
								$row=mysqli_fetch_array($result);
								$_SESSION['VALI_Controleur'].=$row['Nom']." ".$row['Prenom'].$valeur;
							}
						}
					}
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"Controleur",$_SESSION['VALI_Controleur'],$_SESSION['VALI_Controleur2']);
				}
				elseif($_GET['critere']=="statutPROD"){
					$left=substr($_GET['valeur'],0,strpos($_GET['valeur'],";"));
					$right=substr($_GET['valeur'],strpos($_GET['valeur'],";")+1);
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','".$_GET['valeur']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['VALI_Statut']=str_replace($right.$valeur,"",$_SESSION['VALI_Statut']);
					$_SESSION['VALI_Statut2']=str_replace($left.";","",$_SESSION['VALI_Statut2']);
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"Statut",$_SESSION['VALI_Statut'],$_SESSION['VALI_Statut2']);
				}
				elseif($_GET['critere']=="delai"){
					$left=substr($_GET['valeur'],0,strpos($_GET['valeur'],";"));
					$right=substr($_GET['valeur'],strpos($_GET['valeur'],";")+1);
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('delai','".$_GET['valeur']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['VALI_Delai']=str_replace($right.$valeur,"",$_SESSION['VALI_Delai']);
					$_SESSION['VALI_Delai2']=str_replace($left.";","",$_SESSION['VALI_Delai2']);
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"Delai",$_SESSION['VALI_Delai'],$_SESSION['VALI_Delai2']);
				}
				elseif($_GET['critere']=="dateDebut"){
					$_SESSION['VALI_DateDebut']="";
					$_SESSION['VALI_DateDebut2']="";
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"DateDebut",$_SESSION['VALI_DateDebut'],$_SESSION['VALI_DateDebut2']);
				}
				elseif($_GET['critere']=="dateFin"){
					$_SESSION['VALI_DateFin']="";
					$_SESSION['VALI_DateFin2']="";
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"DateFin",$_SESSION['VALI_DateFin'],$_SESSION['VALI_DateFin2']);
				}
				elseif($_GET['critere']=="familletache"){
					$_SESSION['VALI_FamilleTache2']=str_replace($_GET['valeur'].";","",$_SESSION['VALI_FamilleTache2']);
					$tab = explode(";",$_SESSION['VALI_FamilleTache2']);
					$_SESSION['VALI_FamilleTache']="";
					foreach($tab as $Id){
						if($Id<>""){
							$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('familletache','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
							$req="SELECT Libelle FROM trame_familletache WHERE Id=".$Id;
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if($nbResulta>0){
								$row=mysqli_fetch_array($result);
								$_SESSION['VALI_FamilleTache'].=$row['Libelle'].$valeur;
							}
						}
					}
					
					RemplirFiltreTri($_SESSION['Id_PrestationTR'],$_SESSION['Id_PersonneTR'],'Filtre','Validation',"familletache",$_SESSION['VALI_FamilleTache'],$_SESSION['VALI_FamilleTache2']);
				}
				echo "<script>FermerEtRecharger();</script>";
			}
		}
		?>
		<table width="100%" cellpadding="0" cellspacing="0" align="center">
		<form class="test" method="POST" action="Ajout_CritereVALID.php">
			<tr>
				<td>
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="4"></td>
							<td class="TitrePage">Ajouter des critères</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr><td>
			<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr><td height="4"></td></tr>
				<tr>
					<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Reference";}else{echo "Référence";} ?></td>
					<td colspan="4"> 
						<input type="texte" name="reference" size="20" value="">
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Keyword";}else{echo "Mot clés";} ?></td>
					<td colspan="4"> 
						<input type="texte" name="motCles" size="50" value="">
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Task family";}else{echo "Famille tâche";} ?></td>
					<td colspan="4">
						<select id="familletache" name="familletache">
							<?php
								echo"<option value=''></option>";
								$req="SELECT DISTINCT trame_tache.Id_FamilleTache AS Id, (SELECT Libelle FROM trame_familletache WHERE Id=Id_FamilleTache) AS Libelle FROM trame_travaileffectue LEFT JOIN trame_tache on trame_travaileffectue.Id_Tache=trame_tache.Id WHERE trame_tache.Id_FamilleTache>0 AND trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									while($rowFamilleTache=mysqli_fetch_array($result)){
										echo "<option value=\"".$rowFamilleTache['Id'].";".$rowFamilleTache['Libelle']."\">".$rowFamilleTache['Libelle']."</option>";
									}
								}
							?>
						</select>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Workpackage";}else{echo "Workpackage";} ?></td>
					<td colspan="4">
						<select id="wp" name="wp">
							<?php
								echo"<option value=''></option>";
								$req="SELECT DISTINCT trame_wp.Id, trame_wp.Libelle FROM trame_travaileffectue LEFT JOIN trame_wp on trame_travaileffectue.Id_WP=trame_wp.Id WHERE trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									while($rowWP=mysqli_fetch_array($result)){
										echo "<option value=\"".$rowWP['Id'].";".$rowWP['Libelle']."\">".$rowWP['Libelle']."</option>";
									}
								}
							?>
						</select>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Task";}else{echo "Tâche";} ?></td>
					<td colspan="4">
						<select id="tache" name="tache" style="width:600px;">
							<?php
								echo"<option value=''></option>";
								$req="SELECT DISTINCT trame_tache.Id, trame_tache.Libelle FROM trame_travaileffectue LEFT JOIN trame_tache on trame_travaileffectue.Id_Tache=trame_tache.Id WHERE trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Libelle;";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									while($rowTache=mysqli_fetch_array($result)){
										echo "<option value=\"".$rowTache['Id'].";".$rowTache['Libelle']."\">".$rowTache['Libelle']."</option>";
									}
								}
							?>
						</select>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Manufacturing Engineer";}else{echo "Préparateur";} ?></td>
					<td>
						<select id="preparateur" name="preparateur">
							<?php
								echo"<option value=''></option>";
								$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom FROM trame_travaileffectue LEFT JOIN new_rh_etatcivil on trame_travaileffectue.Id_Preparateur=new_rh_etatcivil.Id WHERE trame_travaileffectue.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Nom, Prenom;";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									while($rowPrepa=mysqli_fetch_array($result)){
										echo "<option value=\"".$rowPrepa['Id'].";".$rowPrepa['Nom']." ".$rowPrepa['Prenom']."\">".$rowPrepa['Nom']." ".$rowPrepa['Prenom']."</option>";
									}
								}
							?>
						</select>
					</td>
					
					<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Delay";}else{echo "Delai";} ?></td>
					<td>
						<select name="delai">
							<option value=""/>
							<option value="N/A;<?php if($_SESSION['Langue']=="EN"){echo "N/A";}else{echo "N/A";}?>"><?php if($_SESSION['Langue']=="EN"){echo "N/A";}else{echo "N/A";}?></option>
							<option value="OK;<?php if($_SESSION['Langue']=="EN"){echo "OK";}else{echo "OK";}?>"><?php if($_SESSION['Langue']=="EN"){echo "OK";}else{echo "OK";}?></option>
							<option value="KO;<?php if($_SESSION['Langue']=="EN"){echo "KO";}else{echo "KO";}?>"><?php if($_SESSION['Langue']=="EN"){echo "KO";}else{echo "KO";}?></option>
						</select>
					</td>					
					<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Status";}else{echo "Statut";} ?></td>
					<td>
						<select name="statutPROD">
							<option value=""/>
							<option value="A VALIDER;<?php if($_SESSION['Langue']=="EN"){echo "TO BE VALIDATED";}else{echo "A VALIDER";}?>"><?php if($_SESSION['Langue']=="EN"){echo "TO BE VALIDATED";}else{echo "A VALIDER";}?></option>
							<option value="VALIDE;<?php if($_SESSION['Langue']=="EN"){echo "VALIDATED";}else{echo "VALIDE";}?>"><?php if($_SESSION['Langue']=="EN"){echo "VALIDATED";}else{echo "VALIDE";}?></option>
							<option value="REFUSE;<?php if($_SESSION['Langue']=="EN"){echo "RETURN";}else{echo "RETOURNE";}?>"><?php if($_SESSION['Langue']=="EN"){echo "RETURN";}else{echo "RETOURNE";}?></option>
						</select>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Controller";}else{echo "Contrôleur";} ?></td>
					<td>
						<select id="controleur" name="controleur">
							<?php
								echo"<option value=''></option>";
								$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom FROM trame_controlecroise LEFT JOIN new_rh_etatcivil on trame_controlecroise.Id_Controleur=new_rh_etatcivil.Id WHERE trame_controlecroise.Id_Controleur<>0 AND trame_controlecroise.Id_Prestation=".$_SESSION['Id_PrestationTR']." ORDER BY Nom, Prenom;";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									while($rowPrepa=mysqli_fetch_array($result)){
										echo "<option value=\"".$rowPrepa['Id'].";".$rowPrepa['Nom']." ".$rowPrepa['Prenom']."\">".$rowPrepa['Nom']." ".$rowPrepa['Prenom']."</option>";
									}
								}
							?>
						</select>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "Start date";}else{echo "Date de début";} ?></td>
					<td>
						<input type="date" name="dateDebut" size="10" value=""/>
					</td>
					<td class="Libelle">&nbsp;<?php if($_SESSION['Langue']=="EN"){echo "End date";}else{echo "Date de fin";} ?></td>
					<td>
						<input type="date" name="dateFin"  size="10" value=""/>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
				<tr>
					<td align="center" colspan="10">
						<input class="Bouton" name="BtnAjouter" size="10" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";} ?>">
					</td>					
				</tr>
				<tr><td height="4"></td></tr>
			</table>
			</td></tr>
		</form>
		</table>
	</body>
</html>
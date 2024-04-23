<!DOCTYPE html>
<html>
<head>
<title>TraME</title><meta name="robots" content="noindex">
<link rel="stylesheet" href="../JS/styleCalendrier.css?t=<?php echo time(); ?>">
<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
<script language="javascript" src="Production.js?t=<?php echo time();?>"></script>
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
function FermerEtRecharger(){
	window.opener.location = "Validation.php";
	window.close();
}
function VerifChamps2(langue){
	if(langue=="EN"){
		if(formulaire.raison.value==''){alert('You didn\'t enter the reason.');return false;}
	}
	else{
		if(formulaire.raison.value==''){alert('Vous n\'avez pas renseigné la raison.');return false;}
	}
	return true;
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

if($_GET){
	if($_GET['Type']=="V"){
		$tab = explode(";",$_GET['Id']);
		foreach($tab as $IdTravail){
			if($IdTravail<>""){
				$requete="UPDATE trame_travaileffectue ";
				$requete.="SET Statut=\"VALIDE\",Id_Responsable=".$_SESSION['Id_PersonneTR']." , DateValidation='".date('Y-m-d')."' ";
				$requete.=" WHERE Id=".$IdTravail.";";
				echo $requete;
				$result=mysqli_query($bdd,$requete);
			}
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
if($_POST){
	if(isset($_POST['Refuser'])){
		$tab = explode(";",$_SESSION['VALI_Ids']);
		foreach($tab as $IdTravail){
			if($IdTravail<>""){
				$requete="UPDATE trame_travaileffectue ";
				$requete.="SET Statut=\"REFUSE\",Id_Responsable=".$_SESSION['Id_PersonneTR']." , DateValidation='".date('Y-m-d')."',";
				$requete.=" RaisonRefus=\"".addslashes($_POST['raison'])."\"";
				$requete.=" WHERE Id=".$IdTravail.";";
				//echo $requete;
				$result=mysqli_query($bdd,$requete);
				
				//Envoyer mail si refus
				$headers='From: "Extranet AAA"<extranet@aaa-aero.com>'."\n";
				$req2="SELECT EmailPro FROM new_rh_etatcivil WHERE Id=".$_SESSION['Id_PersonneTR'];
				$resulEmail2=mysqli_query($bdd,$req2);
				$nbEmail2=mysqli_num_rows($resulEmail2);
				if($nbEmail2>0){
					$row2=mysqli_fetch_array($resulEmail2);
					if($row2['EmailPro']<>""){
						$headers.='Cc: '.$row2['EmailPro'].'' . "\r\n" ;
					}
				}
				
				$headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
				
				$destinataire="";
				$req="SELECT Id_Preparateur,Designation,DatePreparateur,DescriptionModification,RaisonRefus, ";
				$req.="(SELECT Libelle FROM trame_tache WHERE Id=trame_travaileffectue.Id_Tache) AS Tache, ";
				$req.="(SELECT Libelle FROM trame_wp WHERE Id=trame_travaileffectue.Id_WP) AS WP, ";
				$req.="(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=trame_travaileffectue.Id_Preparateur) AS EmailPro ";
				$req.="FROM trame_travaileffectue WHERE Id=".$IdTravail.";";
				$resulEmail=mysqli_query($bdd,$req);
				$nbEmail=mysqli_num_rows($resulEmail);
				if ($nbEmail>0){
					$row=mysqli_fetch_array($resulEmail);
					$destinataire=$row['EmailPro'];
					if($_SESSION['Langue']=="EN"){$object="TraME - Work returned for modification : ".$row['Designation'];}
					else{$object="TraME - Travail retourné pour modification : ".$row['Designation'];}
					
					$Infos="";
					$req="SELECT ValeurInfo, ";
					$req.="(SELECT Info FROM trame_tache_infocomplementaire WHERE trame_tache_infocomplementaire.Id=trame_travaileffectue_info.Id_InfoTache) AS Info, ";
					$req.="(SELECT Type FROM trame_tache_infocomplementaire WHERE trame_tache_infocomplementaire.Id=trame_travaileffectue_info.Id_InfoTache) AS Type ";
					$req.="FROM trame_travaileffectue_info WHERE Id_TravailEffectue=".$IdTravail;
					$resultInfo=mysqli_query($bdd,$req);
					$nbResultaInfo=mysqli_num_rows($resultInfo);
					if ($nbResultaInfo>0){
						while($rowInfo=mysqli_fetch_array($resultInfo)){
							if($rowInfo['Type']=="Date"){
								$Infos.="<tr><td width='15%'><b>".$rowInfo['Info']."</b></td><td width='85%'>".AfficheDateFR($rowInfo['ValeurInfo'])."</td></tr>";
							}
							else{
								$Infos.="<tr><td width='15%'><b>".$rowInfo['Info']."</b></td><td width='85%'>".$rowInfo['ValeurInfo']."</td></tr>";
							}
						}
					}
					$message="<html>";
					$message.="<head>";
						$message.="<title>Validation</title>";
					$message.="</head>";
					$message.="<body>";
					$message.="<table width='100%'>";
					if($_SESSION['Langue']=="EN"){
						$message.="<tr><td width='15%'><b>Reference</b></td><td width='85%'>".$row['Designation']."</td></tr>";
						$message.="<tr><td width='15%'><b>Task</td><td>".$row['Tache']."</td></tr>";
						$message.="<tr><td width='15%'><b>Workpackage</td><td>".$row['WP']."</td></tr>";
						$message.="<tr><td width='15%'><b>Date of work</td><td>".AfficheDateFR($row['DatePreparateur'])."</td></tr>";
						$message.="<tr><td colspan='2'><b>Comment manufacturing engineer</td></tr>";
						$message.="<tr><td colspan='2'>".nl2br(stripslashes($row['DescriptionModification']))."</td></tr>";
						$message.="<tr><td colspan='2'><b>Further information</td></tr>";
						$message.=$Infos;
						$message.="<tr><td colspan='2'><b>Reason for return</td></tr>";
						$message.="<tr><td colspan='2'>".nl2br(stripslashes($row['RaisonRefus']))."</td></tr>";
					}
					else{
						$message.="<tr><td width='15%'><b>Référence</td><td width='85%'>".$row['Designation']."</td></tr>";
						$message.="<tr><td width='15%'><b>Tâche</td><td width='85%'>".$row['Tache']."</td></tr>";
						$message.="<tr><td width='15%'><b>Workpackage</td><td width='85%'>".$row['WP']."</td></tr>";
						$message.="<tr><td width='15%'><b>Date du travail</td><td width='85%'>".AfficheDateFR($row['DatePreparateur'])."</td></tr>";
						$message.="<tr><td colspan='2'><b>Commentaire préparateur</td></tr>";
						$message.="<tr><td colspan='2'>".nl2br(stripslashes($row['DescriptionModification']))."</td></tr>";
						$message.="<tr><td colspan='2'><b>Informations complémentaires</td></tr>";
						$message.=$Infos;
						$message.="<tr><td colspan='2'><b>Raison du retour</td></tr>";
						$message.="<tr><td colspan='2'>".nl2br(stripslashes($row['RaisonRefus']))."</td></tr>";
					}
					$message.="</table></td></tr>";
					$message.="</table></body></html>";

					if(mail($destinataire, $object , $message , $headers,'-f extranet@aaa-aero.com')){echo "OK";}
					else{echo "KO";}
				}
			}
		}
	}
	echo "<script>FermerEtRecharger();</script>";
}	
?>

	<form id="formulaire" method="POST" action="Valider_Tache.php">
	<?php
		if($_GET) {
			if ($_GET['Id']<>""){
				$_SESSION['VALI_Ids']=$_GET['Id'];
			}
		}	?>

		<table width="95%" align="center" class="TableCompetences">
			<tr><td height="4"></td></tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Reason";}else{echo "Raison";} ?></td>
			</tr>
			<tr>
				<td colspan="4">
					<textarea id="raison" name="raison" rows=3 cols=100 style="resize:none;"></textarea>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
	<!-- 			boutons valider et refuser -->
				<td colspan="4" align="center">
					<input class="Bouton" type="submit" id="Refuser" name="Refuser" onclick="return VerifChamps2('<?php echo $_SESSION['Langue'];?>');" value="<?php $_SESSION['VALI_decision']="REFUSE"; if($_SESSION['Langue']=="EN"){echo "Back for modification";}else{echo "Retour pour modification";}?>">
				</td>
			</tr>
		</table>
	</form>
	
</body>
</html>

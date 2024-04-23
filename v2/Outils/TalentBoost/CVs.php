<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="Besoin.js?t=<?php echo time(); ?>"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script type="text/javascript" src="../JS/mask.js"></script>
	<script type="text/javascript" src="../JS/js/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript" src="../JS/bootstrap.min.js"></script>
    <script type="text/javascript" src="../JS/prettify.js"></script>
    <script type="text/javascript" src="../JS/bootstrap-timepicker.js"></script>
	<script>
		function OuvreFenetreCV(Id)
		{window.open("leCV.php?Id="+Id,"PageLeCV","status=no,menubar=no,scrollbars=1,width=90,height=40");}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require_once("../PlanningV2/Fonctions_Planning.php");
require_once("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
Ecrire_Code_JS_Init_Date();

function forcerTelechargement($nom, $situation, $poids)
{
header('Content-Type: application/octet-stream');
header('Content-disposition: attachment; filename='. $nom);
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');
readfile($situation);
exit();
}

$Etat="";
$CouleurEtat="#ffffff";
$NumRefus=2;
$EstRefuse=0;

$typedate="date";
$modifiable="";
$selection="";

$DirFichier=$CheminRecrutement;
?>

<form id="formulaire" class="test" action="Candidatures.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $Id; ?>" />
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td align="center" style="border:1px solid black;" width="15%">
								<img width="150px" src="../../Images/Logos/Logo_AAA_FR.png" /> 
							</td>
							<td colspan="8"  width="85%" bgcolor="#2e5496" style="color:#ffffff;font-size:16px;border:1px solid black;" align="center" class="Libelle">
							<?php 
								if($_SESSION["Langue"]=="FR"){echo "CV";}else{echo "CV";}
							?>
							</td>
						</tr>
						<tr>
							<td height="10"></td>
						<tr>
						<tr>
							<td colspan="2" align="center">
								<table class="TableCompetences" align="center" width="40%">
									<tr>
										<td class="EnTeteTableauCompetences" width="12%"  style="text-decoration:none;color:#000000;font-weight:bold;text-align:center;"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
										<td class="EnTeteTableauCompetences" width="2%"  style="text-decoration:none;color:#000000;font-weight:bold;text-align:center;"></td>
									</tr>
									<?php 
									$req="SELECT DISTINCT Id_Personne, (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
									FROM talentboost_candidature
									LEFT JOIN talentboost_annonce ON talentboost_candidature.Id_Annonce=talentboost_annonce.Id
									WHERE talentboost_annonce.Suppr=0  AND ValidationContratDG=1
									AND talentboost_candidature.CV<>'' ";
									
									if($_SESSION['FiltreRecrutAnnonce_Plateforme']<>0){
										$req.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=".$_SESSION['FiltreRecrutAnnonce_Plateforme']." ";
									}
									if($_SESSION['FiltreRecrutAnnonce_Metier']<>""){
										$req.=" AND talentboost_annonce.Metier LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Metier']."%\" ";
									}
									if($_SESSION['FiltreRecrutAnnonce_Domaine']<>0){
										$req.=" AND talentboost_annonce.Id_Domaine = ".$_SESSION['FiltreRecrutAnnonce_Domaine']." ";
									}
									if($_SESSION['FiltreRecrutAnnonce_Programme']<>"0"){
										$req.=" AND talentboost_annonce.Programme LIKE \"".$_SESSION['FiltreRecrutAnnonce_Programme']."\" ";
									}
									if($_SESSION['FiltreRecrutAnnonce_Etat']<>-2){
										$req.=" AND talentboost_annonce.EtatPoste=".$_SESSION['FiltreRecrutAnnonce_Etat']." ";
									}
									if($_SESSION['FiltreRecrutAnnonce_DateDemarrage']>"0001-01-01" && $_SESSION['FiltreRecrutAnnonce_DateDemarrage']<>""){
										$req.=" AND talentboost_annonce.DateBesoin".$_SESSION['FiltreRecrutAnnonce_SigneDateDemarrage']." '".$_SESSION['FiltreRecrutAnnonce_DateDemarrage']."' ";
									}
									if($_SESSION['FiltreRecrutAnnonce_Information']<>""){
										$req.=" AND (
											Lieu LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
											OR CategorieProf LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
											OR DescriptifPoste LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
											OR SavoirFaire LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
											OR SavoirEtre LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
											OR Prerequis LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
											OR Diplome LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
											OR Langue LIKE \"%".$_SESSION['FiltreRecrutAnnonce_Information']."%\"
										) ";
									}
									if($_SESSION['FiltreRecrutAnnonce_MesCandidatures']=="1"){
										$req.=" AND (
														SELECT COUNT(talentboost_candidature.Id) 
														FROM talentboost_candidature 
														WHERE talentboost_candidature.Suppr=0
														AND talentboost_candidature.Id_Personne=".$_SESSION['Id_Personne']."
														AND talentboost_candidature.Id_Annonce=talentboost_annonce.Id
														)>0 ";
									}
									$req.="ORDER BY Personne ";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if($nbResulta>0){
										$couleur="#FFFFFF"; 
										while($row=mysqli_fetch_array($result))
										{
											if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
											else{$couleur="#FFFFFF";}
											
											$req="SELECT CV, (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
											FROM talentboost_candidature
											LEFT JOIN talentboost_annonce ON talentboost_candidature.Id_Annonce=talentboost_annonce.Id
											WHERE talentboost_annonce.Suppr=0  AND ValidationContratDG=1
											AND talentboost_candidature.CV<>''
											AND Id_Personne=".$row['Id_Personne']." ";
											$result2=mysqli_query($bdd,$req);
											$row2=mysqli_fetch_array($result2);
											
											$file=$DirFichier."/".$row2['CV'];
											$name="CV_".$row2['Personne'].substr($row2['CV'],strpos($row2['CV'],"."));
											
											
											?>
												<tr bgcolor="<?php echo $couleur;?>">
													<td align="center"><?php echo stripslashes($row['Personne']);?></td>
													<td align="center">
														<a href="<?php echo $file; ?>" download="<?php echo $name; ?>">
															<?php echo "<img  width='15px' src='../../Images/Trombone.png' />"; ?>
														</a>
													</td>
												</tr>
											<?php
										}
									}
									?>
								</table>
							</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
</table>
</form>
	
</body>
</html>
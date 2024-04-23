<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
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
	<script type="text/javascript">
	function FermerEtRecharger()
	{
		window.opener.location="Liste_EPE.php";
		window.close();
	}
	</script>
</head>


<?php
require_once("../Connexioni.php");
require("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
include '../../Excel/PHPExcel.php';
include '../../Excel/PHPExcel/Writer/Excel2007.php';

Ecrire_Code_JS_Init_Date();

$DateJour=date("Y-m-d");
$bEnregistrement=false;
if($_POST){
	if(isset($_POST['btnEnregistrer'])){
		$datePrevisionnelle="0001-01-01";
		if($_POST['datePrevisionnelle']<>""){$datePrevisionnelle=TrsfDate_($_POST['datePrevisionnelle']);}
		
		if(isset($_POST['EPE'])){
			$req="UPDATE epe_personne_datebutoir SET DatePrevisionnelle='".$datePrevisionnelle."' WHERE TypeEntretien='EPE' AND Id_Personne=".$_POST['Id_Personne']." AND YEAR(DateButoir)=".$_SESSION['FiltreEPE_Annee']." ";
			$resultUpdt=mysqli_query($bdd,$req);
		}
		if(isset($_POST['EPP'])){
			$req="UPDATE epe_personne_datebutoir SET DatePrevisionnelle='".$datePrevisionnelle."' WHERE TypeEntretien='EPP' AND Id_Personne=".$_POST['Id_Personne']." AND YEAR(DateButoir)=".$_SESSION['FiltreEPE_Annee']." ";
			$resultUpdt=mysqli_query($bdd,$req);
		}
		if(isset($_POST['EPPBilan'])){
			$req="UPDATE epe_personne_datebutoir SET DatePrevisionnelle='".$datePrevisionnelle."' WHERE TypeEntretien='EPP Bilan' AND Id_Personne=".$_POST['Id_Personne']." AND YEAR(DateButoir)=".$_SESSION['FiltreEPE_Annee']." ";
			$resultUpdt=mysqli_query($bdd,$req);
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}

$requete="SELECT new_rh_etatcivil.Id, CONCAT(Nom,' ',Prenom) AS Personne
			FROM new_rh_etatcivil
			WHERE Id=".$_GET['Id_Personne'];
			
$result=mysqli_query($bdd,$requete);
$rowRH=mysqli_fetch_array($result);

$requete="SELECT TypeEntretien
			FROM epe_personne_datebutoir
			WHERE Id_Personne=".$_GET['Id_Personne']."
			AND YEAR(epe_personne_datebutoir.DateButoir)=".$_SESSION['FiltreEPE_Annee']."
			AND IF((SELECT COUNT(Id)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
			(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
			'A faire') IN ('A faire','Brouillon')
			";	
$result=mysqli_query($bdd,$requete);
$nombre=mysqli_num_rows($result);
?>

<form id="formulaire" class="test" action="Plannifier.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id_Personne" id="Id_Personne" value="<?php echo $_GET['Id_Personne']; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing:0; background-color:#1a0078;">
				<tr>
					<td class="TitrePage" align="center" style="color:#ffffff;font-size: 18px;">
					<?php
						if($_SESSION["Langue"]=="FR"){echo "Planifier les entretiens";}else{echo "Planifier les entretiens";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2"><?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "Person :";} ?></td>
							<td width="30%" style="font-size: 16px;"><?php echo $rowRH['Personne']; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2"><?php if($_SESSION["Langue"]=="FR"){echo "Type :";}else{echo "Type :";} ?></td>
							<td width="30%" style="font-size: 16px;"><?php 
							if($nombre>0){
								while($rowType=mysqli_fetch_array($result))
								{
									if($rowType['TypeEntretien']=="EPE"){$type="EPE";}
									elseif($rowType['TypeEntretien']=="EPP"){$type="EPP";}
									elseif($rowType['TypeEntretien']=="EPP Bilan"){$type="EPPBilan";}
									echo "<input type='checkbox' checked name='".$type."' value='".$rowType['TypeEntretien']."'>".$rowType['TypeEntretien']." &nbsp;&nbsp;";
								}
							}
							?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="20%" class="Libelle2"><?php if($_SESSION["Langue"]=="FR"){echo "Date prévisionnelle :";}else{echo "Expected date :";} ?></td>
							<td width="30%" style="font-size: 16px;"><input type="date" size="10" name="datePrevisionnelle" value="" /></td>
						</tr>
						<tr><td height="4"></td></tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td colspan="4" align="center">
			<input class="Bouton" name="btnEnregistrer" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Valider";}else{echo "Validate";} ?>"/>
		</td>
	</tr>
	</tr>
</table>
</form>
</body>
</html>
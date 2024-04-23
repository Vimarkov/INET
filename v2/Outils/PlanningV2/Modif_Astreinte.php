<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="RapportAstreinte.js"></script>
	<script language="javascript" src="RapportAstreinteSuite.js"></script>
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
		$(document).ready(function () {
			$('.heures').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: false,
				showMeridian: false,
				defaultTime: false
			});
			Mask.newMask({ 
				$el: $('#heureDebut11'), 
				mask: 'HH:mm' 
			});
			Mask.newMask({ 
				$el: $('#heureDebut21'), 
				mask: 'HH:mm' 
			});
			Mask.newMask({ 
				$el: $('#heureDebut31'), 
				mask: 'HH:mm' 
			});
			Mask.newMask({ 
				$el: $('#heureFin11'), 
				mask: 'HH:mm' 
			});
			Mask.newMask({ 
				$el: $('#heureFin21'), 
				mask: 'HH:mm' 
			});
			Mask.newMask({ 
				$el: $('#heureFin31'), 
				mask: 'HH:mm' 
			});
		});
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");
Ecrire_Code_JS_Init_Date();

$bEnregistrement=false;
if($_POST){
	if(isset($_POST['Valider']))
	{
		//Modification pour changer le montant 
		$reqRA="SELECT 
		TIMEDIFF(HeureFin1,HeureDebut1) AS DiffHeures1,
		TIMEDIFF(HeureFin2,HeureDebut2) AS DiffHeures2,
		TIMEDIFF(HeureFin3,HeureDebut3) AS DiffHeures3
		FROM rh_personne_rapportastreinte
		WHERE Id=".$_POST['Id']."
		";
		$resultRA=mysqli_query($bdd,$reqRA);
		$nbResultaRA=mysqli_num_rows($resultRA);
		if($nbResultaRA>0){
			$rowRA=mysqli_fetch_array($resultRA);

			$Id_Plateforme=0;
			$reqPresta="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$_POST['Id_Prestation'];
			$resultPresta=mysqli_query($bdd,$reqPresta);
			$nbResultaPresta=mysqli_num_rows($resultPresta);
			if($nbResultaPresta>0){
				$rowPresta=mysqli_fetch_array($resultPresta);
				$Id_Plateforme=$rowPresta['Id_Plateforme'];
			}
			
			$reqUpdt="UPDATE rh_personne_rapportastreinte
				SET Montant=".MontantAstreinte($Id_Plateforme,TrsfDate_($_POST['dateDebut1']),$rowRA['DiffHeures1'],$rowRA['DiffHeures2'],$rowRA['DiffHeures3'])."
				WHERE Id=".$_POST['Id']."
				";
			$resultUpdate=mysqli_query($bdd,$reqUpdt);
		}
		
		if($_POST['statut']==1){
			for($j=$_POST['Step'];$j<=2;$j++){
				if($j==1){
					if(DroitsPrestationPole(array($IdPosteChefEquipe),$_POST['Id_Prestation'],$_POST['Id_Pole'])){
						$requeteUpdate="UPDATE rh_personne_rapportastreinte SET 
								Id_ValidateurN1=".$_SESSION['Id_Personne'].",
								DateValidationN1='".date('Y-m-d')."',
								EtatN1=1
								WHERE Id=".$_POST['Id']." ";
						$resultat=mysqli_query($bdd,$requeteUpdate);
					}
					else{$j=5;}
				}
				if($j==2){
					if(DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$_POST['Id_Prestation'],$_POST['Id_Pole'])){
						$requeteUpdate="UPDATE rh_personne_rapportastreinte SET 
								Id_ValidateurN2=".$_SESSION['Id_Personne'].",
								DateValidationN2='".date('Y-m-d')."',
								EtatN2=1
								WHERE Id=".$_POST['Id']." ";
						$resultat=mysqli_query($bdd,$requeteUpdate);
					}
					else{$j=3;}
				}
			}
		}
		else{
			$requeteUpdate="UPDATE rh_personne_rapportastreinte SET 
				Id_ValidateurN".$_POST['Step']."=".$_SESSION['Id_Personne'].",
				DateValidationN".$_POST['Step']."='".date('Y-m-d')."',
				EtatN".$_POST['Step']."=-1,
				Id_RaisonRefusN".$_POST['Step']."=".$_POST['raisonRefus'].",
				RaisonRefusN".$_POST['Step']."='".addslashes($_POST['commentaire'])."' 
				WHERE Id=".$_POST['Id']." ";
		$resultat=mysqli_query($bdd,$requeteUpdate);
		}
	}
	elseif(isset($_POST['btnEnregistrer2'])){
		
		$heureDebut1='00:00:00';
		if($_POST['heureDebut11']<>""){$heureDebut1=$_POST['heureDebut11'];}
		$heureFin1='00:00:00';
		if($_POST['heureFin11']<>""){$heureFin1=$_POST['heureFin11'];}
		
		$heureDebut2='00:00:00';
		if($_POST['heureDebut21']<>""){$heureDebut2=$_POST['heureDebut21'];}
		$heureFin2='00:00:00';
		if($_POST['heureFin21']<>""){$heureFin2=$_POST['heureFin21'];}
		
		$heureDebut3='00:00:00';
		if($_POST['heureDebut31']<>""){$heureDebut3=$_POST['heureDebut31'];}
		$heureFin3='00:00:00';
		if($_POST['heureFin31']<>""){$heureFin3=$_POST['heureFin31'];}
		
		$requeteUpdate="UPDATE rh_personne_rapportastreinte 
				SET DateAstreinte='".TrsfDate_($_POST['dateDebut1'])."',
				Commentaire='".addslashes($_POST['commentaire1'])."',
				Intervention=".$_POST['intervention1'].",
				HeureDebut1='".$heureDebut1."',
				HeureFin1='".$heureFin1."',
				Commentaire1='".addslashes($_POST['commentaire11'])."',
				HeureDebut2='".$heureDebut2."',
				HeureFin2='".$heureFin2."',
				Commentaire2='".addslashes($_POST['commentaire21'])."',
				HeureDebut3='".$heureDebut3."',
				HeureFin3='".$heureFin3."',
				Commentaire3='".addslashes($_POST['commentaire31'])."'
				WHERE Id=".$_POST['Id']." ";
			$resultat=mysqli_query($bdd,$requeteUpdate);
			
		//Modification pour changer le montant 
		$reqRA="SELECT 
		TIMEDIFF(HeureFin1,HeureDebut1) AS DiffHeures1,
		TIMEDIFF(HeureFin2,HeureDebut2) AS DiffHeures2,
		TIMEDIFF(HeureFin3,HeureDebut3) AS DiffHeures3
		FROM rh_personne_rapportastreinte
		WHERE Id=".$_POST['Id']."
		";
		$resultRA=mysqli_query($bdd,$reqRA);
		$nbResultaRA=mysqli_num_rows($resultRA);
		if($nbResultaRA>0){
			$rowRA=mysqli_fetch_array($resultRA);

			$Id_Plateforme=0;
			$reqPresta="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$_POST['Id_Prestation'];
			$resultPresta=mysqli_query($bdd,$reqPresta);
			$nbResultaPresta=mysqli_num_rows($resultPresta);
			if($nbResultaPresta>0){
				$rowPresta=mysqli_fetch_array($resultPresta);
				$Id_Plateforme=$rowPresta['Id_Plateforme'];
			}
		
			$reqUpdt="UPDATE rh_personne_rapportastreinte
				SET Montant=".MontantAstreinte($Id_Plateforme,TrsfDate_($_POST['dateDebut1']),$rowRA['DiffHeures1'],$rowRA['DiffHeures2'],$rowRA['DiffHeures3'])."
				WHERE Id=".$_POST['Id']."
				";
			$resultUpdate=mysqli_query($bdd,$reqUpdt);
		}
	}
	elseif(isset($_POST['EnregistrerRH'])){
		
		//Modification pour changer le montant 
		$reqRA="SELECT 
		TIMEDIFF(HeureFin1,HeureDebut1) AS DiffHeures1,
		TIMEDIFF(HeureFin2,HeureDebut2) AS DiffHeures2,
		TIMEDIFF(HeureFin3,HeureDebut3) AS DiffHeures3
		FROM rh_personne_rapportastreinte
		WHERE Id=".$_POST['Id']."
		";
		$resultRA=mysqli_query($bdd,$reqRA);
		$nbResultaRA=mysqli_num_rows($resultRA);
		if($nbResultaRA>0){
			$rowRA=mysqli_fetch_array($resultRA);

			$Id_Plateforme=0;
			$reqPresta="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$_POST['Id_Prestation'];
			$resultPresta=mysqli_query($bdd,$reqPresta);
			$nbResultaPresta=mysqli_num_rows($resultPresta);
			if($nbResultaPresta>0){
				$rowPresta=mysqli_fetch_array($resultPresta);
				$Id_Plateforme=$rowPresta['Id_Plateforme'];
			}

			$reqUpdt="UPDATE rh_personne_rapportastreinte
				SET Montant=".MontantAstreinte($Id_Plateforme,TrsfDate_($_POST['dateDebut1']),$rowRA['DiffHeures1'],$rowRA['DiffHeures2'],$rowRA['DiffHeures3'])."
				WHERE Id=".$_POST['Id']."
				";
			$resultUpdate=mysqli_query($bdd,$reqUpdt);
		}
		
		if($_POST['datePriseEnCompte1'] <> ""){
			$requeteUpdate="UPDATE rh_personne_rapportastreinte SET 
					Id_ValidateurRH=".$_SESSION['Id_Personne'].",
					DateValidationRH='".date('Y-m-d')."', 
					EtatRH=1,
					DatePriseEnCompte='".TrsfDate_($_POST['datePriseEnCompte1'])."'
					WHERE Id=".$_POST['Id']." ";
		}
		else{
			$requeteUpdate="UPDATE rh_personne_rapportastreinte SET 
					Id_ValidateurRH=".$_SESSION['Id_Personne'].",
					DateValidationRH='".date('Y-m-d')."', 
					EtatRH=1,
					DatePriseEnCompte=DateAstreinte
					WHERE Id=".$_POST['Id']." ";
		}
		$resultat=mysqli_query($bdd,$requeteUpdate);
	}
	echo "<script>FermerEtRecharger(".$_POST['Menu'].",".$_POST['TDB'].",'".$_POST['OngletTDB']."');</script>";
}
else{
	if($_GET['Mode']=="S"){
		$requeteUpdate="UPDATE rh_personne_rapportastreinte SET 
				Suppr=1,
				Id_Suppr=".$_SESSION['Id_Personne'].",
				DateSuppr='".date('Y-m-d')."'
				WHERE Id=".$_GET['Id']." ";
		echo $requeteUpdate;
		$resultat=mysqli_query($bdd,$requeteUpdate);
		echo "<script>FermerEtRecharger(".$_GET['Menu'].",".$_GET['TDB'].",'".$_GET['OngletTDB']."');</script>";
	}
}
$Menu=$_GET['Menu'];

$requete="SELECT rh_personne_rapportastreinte.Id,DateCreation,DateValidationRH,Id_Personne,rh_personne_rapportastreinte.Commentaire,
			rh_personne_rapportastreinte.EtatN2,rh_personne_rapportastreinte.EtatN1,rh_personne_rapportastreinte.DateValidationN1,
			rh_personne_rapportastreinte.DateValidationN2,DateAstreinte,
			Intervention,HeureDebut1,HeureFin1,HeureDebut2,HeureFin2,HeureDebut3,HeureFin3,DatePriseEnCompte,
			rh_personne_rapportastreinte.Id_Prestation,rh_personne_rapportastreinte.Id_Pole,
			Commentaire1,Commentaire2,Commentaire3,
			(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN1) AS RaisonRefus1,
			(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN2) AS RaisonRefus2,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_rapportastreinte.Id_ValidateurN1) AS ResponsableN1,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_rapportastreinte.Id_ValidateurN2) AS ResponsableN2,
			(SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_rapportastreinte.Id_Prestation) AS Prestation, 
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_rapportastreinte.Id_Personne) AS Personne, 
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_rapportastreinte.Id_Createur) AS Demandeur, 
			(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=rh_personne_rapportastreinte.Id_Pole) AS Pole
	FROM rh_personne_rapportastreinte
	WHERE rh_personne_rapportastreinte.Id=".$_GET['Id'] ;
$result=mysqli_query($bdd,$requete);
$row=mysqli_fetch_array($result);

$Etat="";
$CouleurEtat="#ffffff";
$NumRefus=2;
$EstRefuse=0;

if($row['EtatN2']==1 && $row['DateValidationRH']>'0001-01-01'){
	if($_SESSION["Langue"]=="FR"){
		$Etat="Validée et pris en compte sur la paie";}
	else{
		$Etat="Validated and taken into account on payroll";}
	$CouleurEtat="#7ffa1e";
}
elseif($row['EtatN2']==-1 || $row['EtatN1']==-1){
	if($_SESSION["Langue"]=="FR"){
		$Etat="Refusée";}
	else{
		$Etat="Refused";}
	$CouleurEtat="#ff3d3d";
}
elseif($row['EtatN2']==1 && $row['DateValidationRH']<='0001-01-01'){
	if($_SESSION["Langue"]=="FR"){
		$Etat="Transmis aux RH";}
	else{
		$Etat="Submitted to HR";}
	$CouleurEtat="#449ef0";
}
elseif($row['EtatN2']==0 && $row['EtatN1']<>-1){
	if($_SESSION["Langue"]=="FR"){
		$Etat="En attente de de pré validation";}
	else{
		$Etat="Waiting for pre-validation";}
	$CouleurEtat="#fab342";
}

$step=5;
$ModifRH=0;
if($Menu==3){
	if(($row['EtatN1']==0 && DroitsPrestationPole(array($IdPosteChefEquipe),$row['Id_Prestation'],$row['Id_Pole']))
	|| ($row['EtatN1']==1 && $row['EtatN2']==0 && DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$row['Id_Prestation'],$row['Id_Pole'])))
	{
		if($row['EtatN1']==0){$step=1;}
		elseif($row['EtatN2']==0){$step=2;}
	}
}
elseif($Menu==4){
	if($row['EtatN2']==1){$ModifRH=1;}
}
?>

<form id="formulaire" class="test" action="Modif_Astreinte.php" method="post" onsubmit=" return VerifChamps();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Step" id="Step" value="<?php echo $step; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="Id_Prestation" id="Id_Prestation" value="<?php echo $row['Id_Prestation']; ?>" />
	<input type="hidden" name="Id_Personne" id="Id_Personne" value="<?php echo $row['Id_Personne']; ?>" />
	<input type="hidden" name="Id_Pole" id="Id_Pole" value="<?php echo $row['Id_Pole']; ?>" />
	<input type="hidden" name="ValiderRefuser" id="ValiderRefuser" value="" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $_GET['TDB']; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $_GET['OngletTDB']; ?>" />
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° demande :";}else{echo "Request number :";} ?></td>
							<td width="15%" style="color:#3e65fa;">
								<?php echo $row['Id']; ?>
							</td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "Person :";} ?></td>
							<td width="15%">
								<?php echo $row['Personne']; ?>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
							<td width="20%">
								<?php echo stripslashes($row['Prestation']); ?>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?></td>
							<td width="15%">
								<?php echo stripslashes($row['Pole']); ?>
							</td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<?php
							if($row['EtatN1']<>0){
						?>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Validateur :";}else{echo "Validator :";} ?></td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td width="10%" class="Libelle"></td>
							<td colspan="3">
								<?php if($_SESSION["Langue"]=="FR"){echo "Responsable N+1 : ";}else{echo "N + 1 manager : ";} echo $row['ResponsableN1']." (".AfficheDateJJ_MM_AAAA($row['DateValidationN1']).")"; ?>
							</td>
						</tr>
							<?php
								if($row['EtatN2']<>0){
							?>
							<tr>
								<td height="5"></td>
							</tr>
							<tr>
								<td width="10%" class="Libelle"></td>
								<td colspan="3">
									<?php if($_SESSION["Langue"]=="FR"){echo "Responsable N+2 : ";}else{echo "N + 2 manager : ";} echo $row['ResponsableN2']." (".AfficheDateJJ_MM_AAAA($row['DateValidationN1']).")"; ?>
								</td>
							</tr>
							<?php
								}
								else{
									$requetePersonnePoste="SELECT DISTINCT new_competences_personne_poste_prestation.Backup, 
																CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne 
																FROM new_competences_personne_poste_prestation, new_rh_etatcivil 
																WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
																AND new_competences_personne_poste_prestation.Id_Poste = 2
																AND new_competences_personne_poste_prestation.Id_Prestation=".$row['Id_Prestation']."
																AND new_competences_personne_poste_prestation.Id_Pole=".$row['Id_Pole']."
																ORDER BY new_competences_personne_poste_prestation.Backup ASC";
										$resultPersonnePoste=mysqli_query($bdd,$requetePersonnePoste);
										$NbPersonne=mysqli_num_rows($resultPersonnePoste);
										$personne="";
										if($NbPersonne>0){
											while($rowPersonnePoste=mysqli_fetch_array($resultPersonnePoste)){
												if($personne<>""){$personne.=" | ";}
												$personne.=$rowPersonnePoste['Personne'];
											}
										}
								?>
									<tr>
										<td width="10%" class="Libelle"></td>
										<td width="15%" colspan="6">
											<?php if($_SESSION["Langue"]=="FR"){echo "Responsable N+2 : ";}else{echo "N + 2 manager : ";} echo $personne; ?>
										</td>
									</tr>
								<?php
								}
							}
							else{
								$requetePersonnePoste="SELECT DISTINCT new_competences_personne_poste_prestation.Backup, 
														CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne 
														FROM new_competences_personne_poste_prestation, new_rh_etatcivil 
														WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
														AND new_competences_personne_poste_prestation.Id_Poste = 1
														AND new_competences_personne_poste_prestation.Id_Prestation=".$row['Id_Prestation']."
														AND new_competences_personne_poste_prestation.Id_Pole=".$row['Id_Pole']."
														ORDER BY new_competences_personne_poste_prestation.Backup ASC";
								$resultPersonnePoste=mysqli_query($bdd,$requetePersonnePoste);
								$NbPersonne=mysqli_num_rows($resultPersonnePoste);
								$personne="";
								if($NbPersonne>0){
									while($rowPersonnePoste=mysqli_fetch_array($resultPersonnePoste)){
										if($personne<>""){$personne.=" | ";}
										$personne.=$rowPersonnePoste['Personne'];
									}
								}
						?>
							<tr>
								<td width="10%" class="Libelle"></td>
								<td width="15%" colspan="6">
									<?php if($_SESSION["Langue"]=="FR"){echo "Responsable N+1 : ";}else{echo "N + 1 manager : ";} echo $personne; ?>
								</td>
							</tr>
						<?php
								$requetePersonnePoste="SELECT DISTINCT new_competences_personne_poste_prestation.Backup, 
															CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne 
															FROM new_competences_personne_poste_prestation, new_rh_etatcivil 
															WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
															AND new_competences_personne_poste_prestation.Id_Poste = 2
															AND new_competences_personne_poste_prestation.Id_Prestation=".$row['Id_Prestation']."
															AND new_competences_personne_poste_prestation.Id_Pole=".$row['Id_Pole']."
															ORDER BY new_competences_personne_poste_prestation.Backup ASC";
									$resultPersonnePoste=mysqli_query($bdd,$requetePersonnePoste);
									$NbPersonne=mysqli_num_rows($resultPersonnePoste);
									$personne="";
									if($NbPersonne>0){
										while($rowPersonnePoste=mysqli_fetch_array($resultPersonnePoste)){
											if($personne<>""){$personne.=" | ";}
											$personne.=$rowPersonnePoste['Personne'];
										}
									}
							?>
								<tr>
									<td width="10%" class="Libelle"></td>
									<td width="15%" colspan="6">
										<?php if($_SESSION["Langue"]=="FR"){echo "Responsable N+2 : ";}else{echo "N + 2 manager : ";} echo $personne; ?>
									</td>
								</tr>
							<?php
							}
						?>
						<tr><td height="4" colspan="6" style="border-bottom: #1e7bf8 1px solid;"></td></tr>
						<tr>
							<td height="5"></td>
						</tr>
						<?php 
					$nb=0;
					
					$typedate="date";
					$modifiable="";
					$selection="";
					if($row['EtatN1']==1){$typedate="text";$modifiable="readonly='readonly'";$selection="disabled='disabled'";}
					
					$typedateRH="date";
					$modifiableRH="";
					$typedateRH="text";
					$modifiableRH="readonly='readonly'";
					if($row['EtatN2']==1 && $Menu==4){$typedateRH="date";$modifiableRH="";}

						$couleur="#EEEEEE";
							if($couleur=="#dbdbdb"){$couleur="#EEEEEE";}
							else{$couleur="#dbdbdb";}
							
						?>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date astreinte :";}else{echo "Due date :";} ?> </td>
								<td width="10%">
								<input type="<?php echo $typedate;?>" style="text-align:center;" id="dateDebut1" name="dateDebut1" size="10" value="<?php if($row['EtatN1']==1){echo AfficheDateFR($row['DateAstreinte']);}else{echo AfficheDateFR($row['DateAstreinte']);} ?>" <?php echo $modifiable; ?>></td>
								<?php
									if($row['EtatN2']==1){
								?>
									<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de prise en compte (paie) :";}else{echo "Date taken into account (payroll) :";} ?></td>
									<td width="10%"><input type="<?php echo $typedateRH;?>" style="text-align:center;" id="datePriseEnCompte1" name="datePriseEnCompte1" size="10" value="<?php if($row['EtatN2']==1 && $Menu==4){echo AfficheDateFR($row['DatePriseEnCompte']);}else{echo AfficheDateJJ_MM_AAAA($row['DatePriseEnCompte']);} ?>" <?php echo $modifiableRH; ?>></td>
								<?php
									}
								?>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
								<td width="10%" colspan="3">
									<textarea name="commentaire1" id="commentaire1" cols="100" rows="2" style="resize:none;" <?php echo $modifiable; ?>><?php echo stripslashes($row['Commentaire']); ?></textarea>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Intervention :";}else{echo "Intervention";} ?> </td>
								<td class="intervention1" width="55%"  colspan="4">
									<input type="radio" id='intervention1' name='intervention1' <?php echo $selection; ?> onclick="Affiche_Heure(1)" value="1" <?php if($row['Intervention']==1){echo "checked";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?>&nbsp;&nbsp;
									<input type="radio" id='intervention1' name='intervention1' <?php echo $selection; ?> onclick="Affiche_Heure(1)" value="0" <?php if($row['Intervention']==0){echo "checked";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?>&nbsp;&nbsp;
								</td>
							</tr>
							<tr class="nbHeure1" <?php if($row['Intervention']==1){echo "";}else{echo "style='display:none;'";} ?>><td height="4"></td></tr>
							<tr class="nbHeure1" <?php if($row['Intervention']==1){echo "";}else{echo "style='display:none;'";} ?> >
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
								<td width="10%">
									<div class="input-group bootstrap-timepicker timepicker">
										<input class="form-control input-small heures" style="text-align:center;" name="heureDebut11" id="heureDebut11" size="10" type="text" value="<?php echo $row['HeureDebut1']; ?>" <?php echo $modifiable; ?>>
									</div>
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
								<td width="10%">
									<div class="input-group bootstrap-timepicker timepicker">
										<input class="form-control input-small heures" style="text-align:center;" name="heureFin11" id="heureFin11" size="10" type="text" value= "<?php echo $row['HeureFin1']; ?>" <?php echo $modifiable; ?>>
									</div>
								</td>
							</tr>
							<tr><td height="4" class="nbHeure1" <?php if($row['Intervention']==1){echo "";}else{echo "style='display:none;'";} ?>></td></tr>
							<tr class="nbHeure1" <?php if($row['Intervention']==1){echo "";}else{echo "style='display:none;'";} ?>>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
								<td width="10%" colspan="3">
									<textarea name="commentaire11" id="commentaire11" cols="100" rows="2" style="resize:none;" <?php echo $modifiable; ?>><?php echo stripslashes($row['Commentaire1']); ?></textarea>
								</td>
							</tr>
							<tr class="nbHeure1" <?php if($row['Intervention']==1){echo "";}else{echo "style='display:none;'";} ?>><td height="4"></td></tr>
							<tr class="nbHeure1" <?php if($row['Intervention']==1){echo "";}else{echo "style='display:none;'";} ?> >
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
								<td width="10%">
									<div class="input-group bootstrap-timepicker timepicker">
										<input class="form-control input-small heures" style="text-align:center;" name="heureDebut21" id="heureDebut21" size="10" type="text" value="<?php echo $row['HeureDebut2']; ?>" <?php echo $modifiable; ?>>
									</div>
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
								<td width="10%">
									<div class="input-group bootstrap-timepicker timepicker">
										<input class="form-control input-small heures" style="text-align:center;" name="heureFin21" id="heureFin21" size="10" type="text" value= "<?php echo $row['HeureFin2']; ?>" <?php echo $modifiable; ?>>
									</div>
								</td>
							</tr>
							<tr><td height="4" class="nbHeure1" <?php if($row['Intervention']==1){echo "";}else{echo "style='display:none;'";} ?>></td></tr>
							<tr class="nbHeure1" <?php if($row['Intervention']==1){echo "";}else{echo "style='display:none;'";} ?>>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
								<td width="10%" colspan="3">
									<textarea name="commentaire21" id="commentaire21" cols="100" rows="2" style="resize:none;" <?php echo $modifiable; ?>><?php echo stripslashes($row['Commentaire2']); ?></textarea>
								</td>
							</tr>
							<tr class="nbHeure1" <?php if($row['Intervention']==1){echo "";}else{echo "style='display:none;'";} ?>><td height="4"></td></tr>
							<tr class="nbHeure1" <?php if($row['Intervention']==1){echo "";}else{echo "style='display:none;'";} ?> >
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?> : </td>
								<td width="10%">
									<div class="input-group bootstrap-timepicker timepicker">
										<input class="form-control input-small heures" style="text-align:center;" name="heureDebut31" id="heureDebut31" size="10" type="text" value="<?php echo $row['HeureDebut3']; ?>" <?php echo $modifiable; ?>>
									</div>
								</td>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?> : </td>
								<td width="10%">
									<div class="input-group bootstrap-timepicker timepicker">
										<input class="form-control input-small heures" style="text-align:center;" name="heureFin31" id="heureFin31" size="10" type="text" value= "<?php echo $row['HeureFin3']; ?>" <?php echo $modifiable; ?>>
									</div>
								</td>
							</tr>
							<tr><td height="4" class="nbHeure1" <?php if($row['Intervention']==1){echo "";}else{echo "style='display:none;'";} ?>></td></tr>
							<tr class="nbHeure1" <?php if($row['Intervention']==1){echo "";}else{echo "style='display:none;'";} ?>>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?> : </td>
								<td width="10%" colspan="3">
									<textarea name="commentaire31" id="commentaire31" cols="100" rows="2" style="resize:none;" <?php echo $modifiable; ?>><?php echo stripslashes($row['Commentaire3']); ?></textarea>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
						<?php
							if($row['EtatN1']==0){
						?>
						<tr>
							<td colspan="6" align="center">
								<div id="Ajouter">
								</div>
								<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="EnregistrerModif()">
							</td>
						</tr>
						<?php
							}
							if($row['EtatN2']==1 && $Menu==4){
						?>
						<tr>
							<td colspan="10" align="center">
								<div id="ABS_INJ">
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="10" align="center">
								<div id="HorsContrat">
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="10" align="center">
								<div id="HSJourNonT">
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="10" align="center">
								<div id="AS">
								</div>
							</td>
						</tr>
						<tr style="display:none;">
							<td colspan="10" align="center">
								<div id="HS">
								</div>
							</td>
						</tr>
						<tr style="display:none;">
							<td colspan="10" align="center">
								<div id="ABS">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="6" align="center">
								<div id="Ajouter">
								</div>
								<input class="Bouton" type="button" id="EnregistrerRH" name="EnregistrerRH" value="Enregistrer" onClick="EnregistrerRH2()">
							</td>
						</tr>
						<?php		
							}
						?>
						<?php
							if($step==5){
								if($EstRefuse==1){
						?>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Raison du refus :";}else{echo "Reason for refusal :";} ?></td>
							<td width="20%">
								<?php echo stripslashes($row['RaisonRefus'.$NumRefus]); ?>
							</td>
							<tr><td height="5"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire :";}else{echo "Comment :";} ?></td>
								<td colspan="6">
									<textarea name="commentaire" id="commentaire" cols="100" rows="4" style="resize:none;" readonly="readonly"><?php echo stripslashes($row['Commentaire'.$NumRefus]); ?></textarea>
								</td>
							</tr>
							<tr>
								<td height="5"></td>
							</tr>
						<?php	
								}
							}
							else{
						?>
						<tr>
							<td height="10"></td>
						</tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Statut :";}else{echo "Status :";} ?></td>
							<td width="15%">
								<select name="statut" id="statut" onchange="AfficherRefus()">
									<option value="1" selected><?php if($_SESSION["Langue"]=="FR"){echo "Valider";}else{echo "Validate";} ?></option>
									<option value="0"><?php if($_SESSION["Langue"]=="FR"){echo "Refuser";}else{echo "Refuse";} ?></option>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr style="display:none;" id="trRaison">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Raison du refus :";}else{echo "Reason for refusal :";} ?></td>
							<td width="15%">
									<select name="raisonRefus" id="raisonRefus">
									<option value="0"></option>
									<?php
										
										$requete="SELECT Id, Libelle
											FROM rh_raisonrefus
											WHERE
												Suppr=0
											AND Type='RapportAstreinte'
											AND Id_Plateforme = (
												SELECT Id_Plateforme 
												FROM rh_personne_rapportastreinte 
												LEFT JOIN new_competences_prestation 
												ON rh_personne_rapportastreinte.Id_Prestation=new_competences_prestation.Id
												WHERE rh_personne_rapportastreinte.Id=".$_GET['Id']." LIMIT 1)
											ORDER BY Libelle ASC";
										$result=mysqli_query($bdd,$requete);
										while($rowR=mysqli_fetch_array($result))
										{
											echo "<option value='".$rowR['Id']."'>";
											echo str_replace("'"," ",stripslashes($rowR['Libelle']))."</option>\n";
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr style="display:none;" id="trCommentaire">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire :";}else{echo "Comment :";} ?></td>
							<td colspan="6">
								<textarea name="commentaire" id="commentaire" cols="100" rows="4" style="resize:none;"></textarea>
							</td>
						</tr>
						<?php } 
							if($step<>5){
						?>
						<tr>
							<td colspan="6" align="center">
								<input class="Bouton" type="submit" name="Valider" onclick="document.getElementById('ValiderRefuser').value='V';" value="<?php if($_SESSION["Langue"]=="FR"){echo "Enregistrer";}else{echo "Save";} ?>"/>&nbsp;&nbsp;&nbsp;&nbsp
							</td>
						</tr>
						<?php } 
						?>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td>
			<table class="GeneralInfo" align="center" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td height="10"></td>
				</tr>
			<?php 
			

			$EnAttente="#ffbf03";
			$Automatique="#3d9538";
			$Validee="#6beb47";
			$Refusee="#ff5353";
			$Gris="#dddddd";
			$AbsenceInjustifies="#ff0303";
			$TransmisRH="#449ef0";
			
	
			$Debut=date("Y-m-1",strtotime($row['DateAstreinte']." -1 month"));
			$Fin=date("Y-m-1",strtotime($row['DateAstreinte']." +1 month"));

			$tmpDate=date("Y-m-1",strtotime($row['DateAstreinte']." -1 month"));


			//Liste des congés
			$reqConges="SELECT rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,
						rh_personne_demandeabsence.EtatN1,rh_personne_demandeabsence.EtatN2,rh_personne_demandeabsence.EtatRH,
						(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
						(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef
						FROM rh_absence 
						LEFT JOIN rh_personne_demandeabsence 
						ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
						WHERE rh_personne_demandeabsence.Id_Personne=".$row['Id_Personne']." 
						AND rh_absence.DateFin>='".$Debut."' 
						AND rh_absence.DateDebut<='".$Fin."' 
						AND rh_personne_demandeabsence.Suppr=0 
						AND rh_absence.Suppr=0 
						AND rh_personne_demandeabsence.Annulation=0 
						AND rh_personne_demandeabsence.Conge=1 
						ORDER BY rh_absence.Id DESC, rh_absence.Id_Personne_DA DESC ";
			$resultConges=mysqli_query($bdd,$reqConges);
			$nbConges=mysqli_num_rows($resultConges);

			//Liste des absences
			$reqAbs="SELECT rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial,
						(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
						(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef
						FROM rh_absence 
						LEFT JOIN rh_personne_demandeabsence 
						ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
						WHERE rh_personne_demandeabsence.Id_Personne=".$row['Id_Personne']." 
						AND rh_absence.DateFin>='".$Debut."' 
						AND rh_absence.DateDebut<='".$Fin."' 
						AND rh_personne_demandeabsence.Suppr=0 
						AND rh_absence.Suppr=0  
						AND rh_personne_demandeabsence.Conge=0 
						ORDER BY rh_absence.Id DESC, rh_absence.Id_Personne_DA DESC ";
			$resultAbs=mysqli_query($bdd,$reqAbs);
			$nbAbs=mysqli_num_rows($resultAbs);

			//Liste des heures supplémentaires
			$req="SELECT rh_personne_hs.Id, rh_personne_hs.Nb_Heures_Jour,rh_personne_hs.Nb_Heures_Nuit,IF(DateRH>'0001-01-01',DateRH,DateHS) AS DateHS,
						IF(
							rh_personne_hs.Etat4=0 AND rh_personne_hs.Etat3<>-1 AND rh_personne_hs.Etat2<>-1,
							1,
							IF(
								rh_personne_hs.DatePriseEnCompteRH<='0001-01-01' AND rh_personne_hs.Etat4=1 AND rh_personne_hs.Etat3=1 AND rh_personne_hs.Etat2=1,
								2,
								IF(
									rh_personne_hs.Etat4=1 AND rh_personne_hs.DatePriseEnCompteRH>'0001-01-01',
									3,
									IF(
										rh_personne_hs.Etat4=-1 OR rh_personne_hs.Etat3=-1 OR rh_personne_hs.Etat2=-1,
										4,
										5
									)
								)
							)
						)
						AS Etat
					FROM rh_personne_hs
					WHERE Suppr=0 
					AND Id_Personne=".$row['Id_Personne']." 
					AND IF(DateRH>'0001-01-01',DateRH,DateHS)>='".$Debut."' 
					AND IF(DateRH>'0001-01-01',DateRH,DateHS)<='".$Fin."' 
					";
			$resultHS=mysqli_query($bdd,$req);
			$nb2HS=mysqli_num_rows($resultHS);
								
			//Liste des astreintes
			$req="SELECT IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte) AS DateAstreinte,
					IF(
						rh_personne_rapportastreinte.EtatN2=0 AND rh_personne_rapportastreinte.EtatN1<>-1,
						1,
						IF(
							rh_personne_rapportastreinte.DateValidationRH<='0001-01-01' AND rh_personne_rapportastreinte.EtatN2=1 AND rh_personne_rapportastreinte.EtatN1=1,
							2,
							IF(
								rh_personne_rapportastreinte.EtatN2=1 AND rh_personne_rapportastreinte.DateValidationRH>'0001-01-01',
								3,
								IF(
									rh_personne_rapportastreinte.EtatN2=-1 OR rh_personne_rapportastreinte.EtatN1=-1,
									4,
									5
								)
							)
						)
					) AS Etat,
				TIMEDIFF(HeureFin1,HeureDebut1) AS DiffHeures1,
				TIMEDIFF(HeureFin2,HeureDebut2) AS DiffHeures2,
				TIMEDIFF(HeureFin3,HeureDebut3) AS DiffHeures3,Montant,Intervention
				FROM rh_personne_rapportastreinte
				WHERE rh_personne_rapportastreinte.Suppr=0
				AND rh_personne_rapportastreinte.Id_Personne=".$row['Id_Personne']."
				AND DateAstreinte>='".$Debut."' 
				AND DateAstreinte<='".$Fin."' 
				";
				
			$resultAst=mysqli_query($bdd,$req);
			$nbAst=mysqli_num_rows($resultAst);

			//Liste des vacations différentes
			$req="SELECT Id_Vacation,Id_Prestation,Id_Pole,DateVacation,
				rh_vacation.Nom,rh_vacation.Couleur
				FROM rh_personne_vacation 
				LEFT JOIN rh_vacation
				ON rh_personne_vacation.Id_Vacation=rh_vacation.Id
				WHERE rh_personne_vacation.Suppr=0
				AND rh_personne_vacation.Id_Personne=".$row['Id_Personne']."
				AND rh_personne_vacation.DateVacation>='".$Debut."' 
				AND rh_personne_vacation.DateVacation<='".$Fin."' 
				";
			$resultVac=mysqli_query($bdd,$req);
			$nbVac=mysqli_num_rows($resultVac);

			//Liste des formations 


			if($_SESSION["Langue"]=="FR"){
				$MoisLettre = array("Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Decembre");
				$joursem = array("D", "L", "Mar", "Mer", "J", "V", "S");
				$joursem2 = array("L", "Mar", "Mer", "J", "V", "S","D");
			}
			else{
				$MoisLettre = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
				$joursem = array("Sun", "M", "Tu", "W", "Th", "F", "Sat");
				$joursem2 = array("M", "Tu", "W", "Th", "F", "Sat","Sun");
			}
			echo "<tr>";
			
			$mois1=date("m",strtotime($row['DateAstreinte']." -1 month"));
			$mois2=date("m",strtotime($row['DateAstreinte']." +0 month"));
			$mois3=date("m",strtotime($row['DateAstreinte']." +1 month"));
			$tab=array($mois1,$mois2,$mois3);
			$nb=1;
			$tmpDate=date("Y-m-1",strtotime($row['DateAstreinte']." -1 month"));
			foreach ($tab as $i){
					echo "<td align='center'>";
						echo "<table style='border:1px solid #787878;' width='85%' cellpadding='0' cellspacing='0'>";
							$tabDate = explode('-', $tmpDate);
							$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
							$mois = $tabDate[1];
							echo "<tr><td class='cEnTete' colspan='8' align='center'>".$MoisLettre[$mois-1]." ".$tabDate[0]."</td></tr>";
							if($_SESSION["Langue"]=="FR"){
								echo "<tr><td class='cLigne1' align='center'></td><td class='cLigne1' align='center'>Lun.</td><td class='cLigne1' align='center'>Mar.</td><td class='cLigne1' align='center'>Mer.</td>";
								echo "<td class='cLigne1' align='center'>Jeu.</td><td class='cLigne1' align='center'>Ven.</td><td class='cLigne1' align='center'>Sam.</td><td class='cLigne1' align='center'>Dim.</td></tr>";
							}
							else{
								echo "<tr><td class='cLigne1' align='center'></td><td class='cLigne1' align='center'>Mon.</td><td class='cLigne1' align='center'>Tue.</td><td class='cLigne1' align='center'>Wed.</td>";
								echo "<td class='cLigne1' align='center'>Thu.</td><td class='cLigne1' align='center'>Fri.</td><td class='cLigne1' align='center'>Sat.</td><td class='cLigne1' align='center'>Sun.</td></tr>";
							}
							//Premier jour du mois
							$dateMois=date("Y-m-d",mktime(0,0,0,$tabDate[1],1,$tabDate[0]));
							for($ligne=1;$ligne<=6;$ligne++){
								echo "<tr>";
								for($colonne=0;$colonne<=7;$colonne++){
									$tabDateMois = explode('-', $dateMois);
									$timestampMois = mktime(0, 0, 0, $tabDateMois[1], $tabDateMois[2], $tabDateMois[0]);
									$semaine = date('W', $timestampMois);
									$jour = $tabDateMois[2];
									$jourSemaine = date('w', $timestampMois);
									
									$Trouve=false;
									$TypeDC="";
									$bEtat="rien";
									$type="";
									
									$bEtat="rien";
									$type="";	
									$indice="";
									$valAstreinte="";
									$IndiceAbs="";
									$NbHeureSuppJour=0;
									$NbHeureSuppNuit=0;
									$nbHS=0;
									if($colonne==0){
										echo "<td class='numSemaine'>".$semaine."</td>";
									}
									else{
										//Vacation contrat
										$bgcolor="";
										$laCouleur=TravailCeJourDeSemaine($dateMois,$row['Id_Personne']);
										if($laCouleur<>""){
											$type="J";
											$bgcolor="bgcolor='".$laCouleur."'";
										}
										
										//Vacation particulière
										$VacParticuliere=0;
										$Id_PrestationPole=PrestationPole_Personne($dateMois,$row['Id_Personne']);
										if($Id_PrestationPole<>0){
											$tabPresta=explode("_",$Id_PrestationPole);
											$Id_Presta=$tabPresta[0];
											$Id_Pole=$tabPresta[1];
											if($nbVac>0){
												mysqli_data_seek($resultVac,0);
												while($rowVac=mysqli_fetch_array($resultVac)){
													if($rowVac['Id_Prestation']==$Id_Presta && $rowVac['Id_Pole']==$Id_Pole && $rowVac['DateVacation']==$dateMois){
														$type=$rowVac['Nom'];
														$bgcolor="bgcolor='".$rowVac['Couleur']."'";
														$VacParticuliere=1;
														break;
													}
												}
											}
										}
										
										//Astreintes
									if($nbAst>0){
										mysqli_data_seek($resultAst,0);
										while($rowAst=mysqli_fetch_array($resultAst)){
											if($rowAst['DateAstreinte']==$dateMois){
												$valAstreinte=" AS";
												$nbHeures="0h ";
												if($rowAst['Intervention']==1){
													$nbHeures=Ajouter_Heures($rowAst['DiffHeures1'],$rowAst['DiffHeures2'],$rowAst['DiffHeures3']);
													$valAstreinte.=" ".$nbHeures;
												}
												break;
											}
										}
									}
									
									//HS
									if($nb2HS>0){
										mysqli_data_seek($resultHS,0);
										while($rowHS=mysqli_fetch_array($resultHS)){
											if($rowHS['DateHS']==$dateMois){
												$nbHS+=$rowHS['Nb_Heures_Jour']+$rowHS['Nb_Heures_Nuit'];
												$NbHeureSuppJour+=$rowHS['Nb_Heures_Jour'];
												$NbHeureSuppNuit+=$rowHS['Nb_Heures_Nuit'];
												if($indice<>""){$indice.="+";}
												if($_SESSION["Langue"]=="FR"){$indice.=($rowHS['Nb_Heures_Jour']+$rowHS['Nb_Heures_Nuit'])."HS";}
												else{$indice.=($rowHS['Nb_Heures_Jour']+$rowHS['Nb_Heures_Nuit'])."OT";}
												$bEtatHS="attenteValidation";
												if($rowHS['Etat']==4){$bEtatHS="refusee";}
												elseif($rowHS['Etat']==3 || $rowHS['Etat']==2){$bEtatHS="validee";}
											}
										}
									}
									
										//Absences
										if($nbAbs>0){
											mysqli_data_seek($resultAbs,0);
											while($rowAbs=mysqli_fetch_array($resultAbs)){
												if($rowAbs['DateDebut']<=$dateMois && $rowAbs['DateFin']>=$dateMois){
													$bEtat="validee";
													if($rowAbs['TypeAbsenceDef']<>""){
														$type=$rowAbs['TypeAbsenceDef'];
														if($rowAbs['Id_TypeAbsenceDefinitif']==0){
															$bEtat="absInjustifiee";
															$type="ABS";
														}
													}
													else{
														$type=$rowAbs['TypeAbsenceIni'];
														if($rowAbs['Id_TypeAbsenceInitial']==0){$bEtat="absInjustifiee";$type="ABS";}
													}
													break;
												}
											}
										}
										
										//Congés
										if($nbConges>0){
											mysqli_data_seek($resultConges,0);
											while($rowConges=mysqli_fetch_array($resultConges)){
												if($rowConges['DateDebut']<=$dateMois && $rowConges['DateFin']>=$dateMois){
													if($rowConges['TypeAbsenceDef']<>""){$type=$rowConges['TypeAbsenceDef'];}
													else{$type=$rowConges['TypeAbsenceIni'];}
													$bEtat="attenteValidation";
													if($rowConges['EtatN1']==-1 || $rowConges['EtatN2']==-1){$bEtat="refusee";}
													elseif($rowConges['EtatRH']==1){$bEtat="validee";}
													elseif($rowConges['EtatRH']==0 && $rowConges['EtatN2']==1){$bEtat="TransmisRH";}
													break;
												}
											}
										}
									
										if($jour==1){
											if($joursem[$jourSemaine]==$joursem2[$colonne-1] && $tabDate[1]==$tabDateMois[1]){
												if($laCouleur==""){
													if(estWE($timestampMois)){
														$bgcolor="bgcolor='".$Gris."'";
													}
												}
												if($bEtat=="attenteValidation"){$bgcolor="bgcolor='".$EnAttente."'";}
												elseif($bEtat=="validee"){$bgcolor="bgcolor='".$Validee."'";}
												elseif($bEtat=="refusee"){$bgcolor="bgcolor='".$Refusee."'";}
												elseif($bEtat=="TransmisRH"){$bgcolor="bgcolor='".$TransmisRH."'";}
												
												if($VacParticuliere==0){
													$jourFixe=estJour_Fixe($dateMois,$row['Id_Personne']);
													if($jourFixe<>""){
														$bgcolor="bgcolor='".$Automatique."'";
														$type=$jourFixe;
													}
												}

												echo "<td class='jourSemaine' ".$bgcolor." align='center'>".$jour."<sup>".$type.$IndiceAbs.$indice.$valAstreinte."</sup></td>";
												$tabDateMois = explode('-', $dateMois);
												$timestampMois = mktime(0, 0, 0, $tabDateMois[1], $tabDateMois[2]+1, $tabDateMois[0]);
												$dateMois = date("Y-m-d", $timestampMois);
											}
											else{
												echo "<td style='border:1px solid #b9b9b9;font-size:12px;' align='center'></td>";
											}
										}
										else{
											if($laCouleur==""){
												if(estWE($timestampMois)){
													$bgcolor="bgcolor='".$Gris."'";
												}
											}
											if($bEtat=="attenteValidation"){$bgcolor="bgcolor='".$EnAttente."'";}
											elseif($bEtat=="validee"){$bgcolor="bgcolor='".$Validee."'";}
											elseif($bEtat=="refusee"){$bgcolor="bgcolor='".$Refusee."'";}
											elseif($bEtat=="absInjustifiee"){$bgcolor="bgcolor='".$AbsenceInjustifies."'";}
											elseif($bEtat=="TransmisRH"){$bgcolor="bgcolor='".$TransmisRH."'";}
											
											if($VacParticuliere==0){
												$jourFixe=estJour_Fixe($dateMois,$row['Id_Personne']);
												if($jourFixe<>""){
													$bgcolor="bgcolor='".$Automatique."'";
													$type=$jourFixe;
												}
											}
											
											echo "<td class='jourSemaine' ".$bgcolor." align='center'>".$jour."<sup>".$type.$IndiceAbs.$indice.$valAstreinte."</sup></td>";
											$tabDateMois = explode('-', $dateMois);
											$timestampMois = mktime(0, 0, 0, $tabDateMois[1], $tabDateMois[2]+1, $tabDateMois[0]);
											$dateMois = date("Y-m-d", $timestampMois);
										}
									}
									
								}
								echo "</tr>";
							}
						echo "</table>";
					echo "</td>";
				//Mois suivant
				$tabDate = explode('-', $tmpDate);
				$timestamp = mktime(0, 0, 0, $tabDate[1]+1, $tabDate[2], $tabDate[0]);
				$tmpDate = date("Y-m-d", $timestamp);
				
				$nb++;
				if($nb==4 || $nb==7 || $nb==10){
					echo "</tr><tr><td height='20'></td></tr><tr>";
				}
				}
				echo "</tr><tr><td height='20'></td></tr>";
			?>
				<tr>
					<td colspan="6" align="center">
						<table align="center" width="50%" cellpadding="0" cellspacing="0">
							<tr align="left">
								<td bgcolor="<?php echo $EnAttente; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "En attente de pré validation";}else{echo "Waiting for pre-validation";} ?></td>
								<td bgcolor="<?php echo $TransmisRH; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Transmis aux RH";}else{echo "Submitted to HR";} ?></td>
								<td bgcolor="<?php echo $Automatique; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Automatique";}else{echo "Automatic";} ?></td>
								<td bgcolor="<?php echo $Validee; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Validée";}else{echo "Validated";} ?></td>
								<td bgcolor="<?php echo $Refusee; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Refusée";}else{echo "Declined";} ?></td>
								<td bgcolor="<?php echo $AbsenceInjustifies; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Absence injustifiée";}else{echo "Unjustified absence";} ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
			</table>
		</td>
	</tr>
</table>
</form>
	
</body>
</html>
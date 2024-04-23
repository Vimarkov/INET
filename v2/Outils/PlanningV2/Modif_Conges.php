<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="DemandeAbsence.js?t=<?php echo time(); ?>"></script>
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
	<script language="javascript">
		function VerifChamps()
		{
			if(document.getElementById('statut').value==0){
				if(document.getElementById('Langue').value=="FR"){
					if(document.getElementById('commentaire').value==""){alert("Veuillez ajouter un commentaire.");return false;}
				}
				else{
					if(document.getElementById('commentaire').value==""){alert("Please add a comment.");return false;}

				}
			}
			return true;
		}
		function FermerEtRecharger(Menu,TDB,OngletTDB,Page)
		{
			window.opener.location=Page+".php?Menu="+Menu+"&TDB="+TDB+"&OngletTDB="+OngletTDB;
			window.close();
		}
		function AfficherRefus(){
			if(document.getElementById('statut').value==0){
				document.getElementById('trRaison').style.display="";
				document.getElementById('trCommentaire').style.display="";
			}
			else{
				document.getElementById('trRaison').style.display="none";
				document.getElementById('trCommentaire').style.display="none";
			}
		}
		function OuvreFormatExcel(Id)
			{window.open("DemandeCongesExcel.php?Id="+Id,"PageExcel","status=no,menubar=no,width=90,height=90");}
	</script>
	<script type="text/javascript">
		$(document).ready(function () {
			$('.heureDebut').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: false,
				showMeridian: false,
				defaultTime: false
			});
			Mask.newMask({ 
				$el: $('.heureDebut'), 
				mask: 'HH:mm' 
			});
			
			$('.heureFin').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: false,
				showMeridian: false,
				defaultTime: false
			});
			Mask.newMask({ 
				$el: $('.heureFin'), 
				mask: 'HH:mm' 
			});
		});
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
require_once("Fonctions_Planning.php");
Ecrire_Code_JS_Init_Date();

$bEnregistrement=false;
if($_POST){
	if(isset($_POST['Valider']))
	{
		if($_POST['statut']==1){
			for($j=$_POST['Step'];$j<=2;$j++){
				if($j==1){
					if(DroitsPrestationPole(array($IdPosteChefEquipe),$_POST['Id_Prestation'],$_POST['Id_Pole'])){
						$requeteUpdate="UPDATE rh_personne_demandeabsence SET 
								Id_N1=".$_SESSION['Id_Personne'].",
								DateValidationN1='".date('Y-m-d')."',
								EtatN1=1
								WHERE Id=".$_POST['Id']." ";
						$resultat=mysqli_query($bdd,$requeteUpdate);
					}
					else{$j=5;}
				}
				if($j==2){
					if(DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$_POST['Id_Prestation'],$_POST['Id_Pole']) || NiveauValidationCongesPrestation($_POST['Id_Prestation'])==1
					|| DroitsPrestationPole(array($IdPosteCoordinateurProjet),$row['Id_Prestation'],$row['Id_Pole'])){
						$Id_N2=$_SESSION['Id_Personne'];
						if(NiveauValidationCongesPrestation($_POST['Id_Prestation'])==1){$Id_N2=0;}
						$requeteUpdate="UPDATE rh_personne_demandeabsence SET 
								Id_N2=".$Id_N2.",
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
			$requeteUpdate="UPDATE rh_personne_demandeabsence SET 
				Id_N".$_POST['Step']."=".$_SESSION['Id_Personne'].",
				DateValidationN".$_POST['Step']."='".date('Y-m-d')."',
				EtatN".$_POST['Step']."=-1,
				Id_RaisonRefusN".$_POST['Step']."=".$_POST['raisonRefus'].",
				Commentaire".$_POST['Step']."='".addslashes($_POST['commentaire'])."' 
				WHERE Id=".$_POST['Id']." ";
			$resultat=mysqli_query($bdd,$requeteUpdate);
		}
		
	}
	elseif(isset($_POST['ModifierType']))
	{
		$requete="SELECT rh_absence.Id, rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,rh_absence.NbJour,
			rh_absence.Id_TypeAbsenceInitial,rh_absence.Id_TypeAbsenceDefinitif,rh_absence.NbHeureAbsJour,rh_absence.NbHeureAbsNuit,
			rh_absence.Id_FonctionRepresentative,
			rh_absence.HeureDepart,
			rh_absence.HeureArrivee
			FROM rh_absence
			WHERE Suppr=0 AND rh_absence.Id_Personne_DA=".$_POST['Id']."
			ORDER BY DateDebut
			" ;
		$resultAbs=mysqli_query($bdd,$requete);
		$nbAbs=mysqli_num_rows($resultAbs);
		$IdsAbsence="";
		if($nbAbs>0){
			$i=0;
			while($rowAbs=mysqli_fetch_array($resultAbs)){
				$tmpDate = $rowAbs['DateDebut'];
				$dateFin = $rowAbs['DateFin'];
				$bModif=0;
				$dateDebut=$tmpDate;
				$Id_TypeIni=$rowAbs['Id_TypeAbsenceInitial'];
				$Id_TypeDef=$Id_TypeIni;
				$estDif=0;
				$nbJour=0;
				
				$nbJIni=$rowAbs['NbHeureAbsJour'];
				$nbNIni=$rowAbs['NbHeureAbsNuit'];
				$heureIni=$rowAbs['HeureDepart'];
				$heureFinIni=$rowAbs['HeureArrivee'];
				$Id_FonctionRepresentativeIni=$rowAbs['Id_FonctionRepresentative'];
				$nbJDef=$rowAbs['NbHeureAbsJour'];
				$nbNDef=$rowAbs['NbHeureAbsNuit'];
				$heureDef=$rowAbs['HeureDepart'];
				$heureFinDef=$rowAbs['HeureArrivee'];
				$Id_FonctionRepresentativeDef=$rowAbs['Id_FonctionRepresentative'];
				while ($tmpDate <= $dateFin){
					if($_POST['typeAbsence'.$i]<>-1){
					//if($_POST['typeAbsence'.$i]<>0){
						if($_POST['nbHeureJour'.$i]<>""){$nbJ=$_POST['nbHeureJour'.$i];} //Nb heure jour
						elseif($_POST['nbHeuresBDD'.$i]<>""){$nbJ=$_POST['nbHeuresBDD'.$i];} //Nb heure BDD
						elseif($_POST['nbHeureRC'.$i]<>""){$nbJ=$_POST['nbHeureRC'.$i];} //Nb heure RC
						else{$nbJ=0;}
						if($_POST['nbHeureNuit'.$i]<>""){$nbN=$_POST['nbHeureNuit'.$i];} //Nb heure nuit
						else{$nbN=0;}
						if($_POST['heureDebut'.$i]<>""){$heure=$_POST['heureDebut'.$i];} //Heure début 
						else{$heure="00:00:00";}
						if($_POST['heureFin'.$i]<>""){$heureFin=$_POST['heureFin'.$i];} //Heure début 
						else{$heureFin="00:00:00";}
						$Id_FonctionRepresentative=$_POST['fonctionRepresentative'.$i];
						
						if($Id_TypeIni<>$_POST['typeAbsence'.$i] || $nbJIni<>$nbJ || $nbNIni<>$nbN || $heureIni<>$heure || $heureFinIni<>$heureFin || $Id_FonctionRepresentativeIni<>$Id_FonctionRepresentative){
							if($dateDebut==$tmpDate){
								$Id_TypeIni=$_POST['typeAbsence'.$i];
								if($_POST['nbHeureJour'.$i]<>""){$nbJIni=$_POST['nbHeureJour'.$i];} //Nb heure jour
								elseif($_POST['nbHeuresBDD'.$i]<>""){$nbJIni=$_POST['nbHeuresBDD'.$i];} //Nb heure BDD
								elseif($_POST['nbHeureRC'.$i]<>""){$nbJIni=$_POST['nbHeureRC'.$i];} //Nb heure RC
								else{$nbJIni=0;}
								if($_POST['nbHeureNuit'.$i]<>""){$nbNIni=$_POST['nbHeureNuit'.$i];} //Nb heure nuit
								else{$nbNIni=0;}
								if($_POST['heureDebut'.$i]<>""){$heureIni=$_POST['heureDebut'.$i];} //Heure début 
								else{$heureIni="00:00:00";}
								if($_POST['heureFin'.$i]<>""){$heureFinIni=$_POST['heureFin'.$i];} //Heure début 
								else{$heureFinIni="00:00:00";}
								$Id_FonctionRepresentativeIni=$_POST['fonctionRepresentative'.$i];
							}
							else{
								$estDif=1;
								$Id_TypeDef=$_POST['typeAbsence'.$i];
								if($_POST['nbHeureJour'.$i]<>""){$nbJDef=$_POST['nbHeureJour'.$i];} //Nb heure jour
								elseif($_POST['nbHeuresBDD'.$i]<>""){$nbJDef=$_POST['nbHeuresBDD'.$i];} //Nb heure BDD
								elseif($_POST['nbHeureRC'.$i]<>""){$nbJDef=$_POST['nbHeureRC'.$i];} //Nb heure RC
								else{$nbJDef=0;}
								if($_POST['nbHeureNuit'.$i]<>""){$nbNDef=$_POST['nbHeureNuit'.$i];} //Nb heure nuit
								else{$nbNDef=0;}
								if($_POST['heureDebut'.$i]<>""){$heureDef=$_POST['heureDebut'.$i];} //Heure début 
								else{$heureDef="00:00:00";}
								if($_POST['heureFin'.$i]<>""){$heureFinDef=$_POST['heureFin'.$i];} //Heure début 
								else{$heureFinDef="00:00:00";}
								$Id_FonctionRepresentativeDef=$_POST['fonctionRepresentative'.$i];
							}
						}
					/*}
					else{
						if($Id_TypeIni<>$rowAbs['Id_TypeAbsenceInitial'] || $nbJIni<>$rowAbs['NbHeureAbsJour'] || $nbNIni<>$rowAbs['NbHeureAbsNuit'] || $heureIni<>$rowAbs['HeureDepart'] || $heureFinIni<>$rowAbs['HeureArrivee'] || $Id_FonctionRepresentativeIni<>$rowAbs['Id_FonctionRepresentative']){
							$estDif=1;
							$Id_TypeDef=$rowAbs['Id_TypeAbsenceInitial'];
							$nbJDef=$rowAbs['NbHeureAbsJour'];
							$nbNDef=$rowAbs['NbHeureAbsNuit'];
							$heureDef=$rowAbs['HeureDepart'];
							$heureFinDef=$rowAbs['HeureArrivee'];
							$Id_FonctionRepresentativeDef=$rowAbs['Id_FonctionRepresentative'];
						}
					}*/
					}
					else{
						//Supprimer la ligne
						$estDif=1;
					}
					$nbJour++;
					if($estDif==1 && $_POST['typeAbsence'.$i]<>-1){
						$req="INSERT INTO rh_absence (Id_Personne_DA,Id_TypeAbsenceInitial,Id_TypeAbsenceDefinitif,DateDebut,DateFin,
								HeureDepart,HeureArrivee,NbHeureAbsJour,NbHeureAbsNuit,Id_FonctionRepresentative,NbJour) 
							VALUES ";

						$NewIdType=0;
						if($Id_TypeIni<>$rowAbs['Id_TypeAbsenceInitial']){$NewIdType=$Id_TypeIni;}
						$req.="(".$rowAbs['Id_Personne_DA'].",".$rowAbs['Id_TypeAbsenceInitial'].",".$NewIdType.",
							'".$dateDebut."','".date("Y-m-d",strtotime($tmpDate." -1 day"))."','".$heureIni."','".$heureFinIni."',".$nbJIni.",".$nbNIni.",".$Id_FonctionRepresentativeIni.",".($nbJour-1).")";
						$resultAjout=mysqli_query($bdd,$req);
						$estDif=0;
						$Id_TypeIni=$Id_TypeDef;
						$dateDebut=$tmpDate;
						$nbJour=1;
						$nbJIni=$nbJDef;
						$nbNIni=$nbNDef;
						$heureIni=$heureDef;
						$heureFinIni=$heureFinDef;
						$Id_FonctionRepresentativeIni=$Id_FonctionRepresentativeDef;
					}
					elseif($estDif==1 && $_POST['typeAbsence'.$i]==-1){
						$req="INSERT INTO rh_absence (Id_Personne_DA,Id_TypeAbsenceInitial,Id_TypeAbsenceDefinitif,DateDebut,DateFin,
								HeureDepart,HeureArrivee,NbHeureAbsJour,NbHeureAbsNuit,Id_FonctionRepresentative,NbJour) 
							VALUES ";

						$NewIdType=0;
						if($Id_TypeIni<>$rowAbs['Id_TypeAbsenceInitial']){$NewIdType=$Id_TypeIni;}
						$req.="(".$rowAbs['Id_Personne_DA'].",".$rowAbs['Id_TypeAbsenceInitial'].",".$NewIdType.",
							'".$dateDebut."','".date("Y-m-d",strtotime($tmpDate." -1 day"))."','".$heureIni."','".$heureFinIni."',".$nbJIni.",".$nbNIni.",".$Id_FonctionRepresentativeIni.",".($nbJour-1).")";
						$resultAjout=mysqli_query($bdd,$req);
						$estDif=0;
						$Id_TypeIni=$Id_TypeDef;
						$dateDebut=date("Y-m-d",strtotime($tmpDate." +1 day"));
						$nbJour=0;
						$nbJIni=$nbJDef;
						$nbNIni=$nbNDef;
						$heureIni=$heureDef;
						$heureFinIni=$heureFinDef;
						$Id_FonctionRepresentativeIni=$Id_FonctionRepresentativeDef;
					}
					if($_POST['typeAbsence'.$i]<>0){
						$bModif=1;
					}
					$i++;
					//Jour suivant
					$tmpDate =date("Y-m-d",strtotime($tmpDate." +1 day"));
				}
				$req="INSERT INTO rh_absence (Id_Personne_DA,Id_TypeAbsenceInitial,Id_TypeAbsenceDefinitif,DateDebut,DateFin,
							HeureDepart,HeureArrivee,NbHeureAbsJour,NbHeureAbsNuit,Id_FonctionRepresentative,NbJour) 
						VALUES ";

				$NewIdType=0;
					if($Id_TypeIni<>$rowAbs['Id_TypeAbsenceInitial']){$NewIdType=$Id_TypeIni;}
					$req.="(".$rowAbs['Id_Personne_DA'].",".$rowAbs['Id_TypeAbsenceInitial'].",".$NewIdType.",
						'".$dateDebut."','".date("Y-m-d",strtotime($tmpDate." -1 day"))."','".$heureIni."','".$heureFinIni."',".$nbJIni.",".$nbNIni.",".$Id_FonctionRepresentativeIni.",".$nbJour.")";
				$resultAjout=mysqli_query($bdd,$req);
				
				$req="UPDATE rh_absence SET Suppr=1 WHERE Id=".$rowAbs['Id'];
				$resultUpdate=mysqli_query($bdd,$req);
				
				$req="UPDATE rh_personne_demandeabsence SET Id_RH=".$_SESSION['Id_Personne'].", DateValidationRH='".date('Y-m-d')."', EtatRH=1 WHERE Id=".$rowAbs['Id_Personne_DA'];
				$resultUpdate=mysqli_query($bdd,$req);
			}
		}
	}
	echo "<script>FermerEtRecharger(".$_POST['Menu'].",".$_POST['TDB'].",'".$_POST['OngletTDB']."','".$_POST['Page']."');</script>";
}
else{
	if($_GET['Mode']=="S"){
		$requeteUpdate="UPDATE rh_personne_demandeabsence SET 
				Suppr=1,
				Id_Suppr=".$_SESSION['Id_Personne'].",
				DateSuppr='".date('Y-m-d')."'
				WHERE Id=".$_GET['Id']." ";
		$resultat=mysqli_query($bdd,$requeteUpdate);
		echo "<script>FermerEtRecharger(".$_GET['Menu'].",".$_GET['TDB'].",'".$_GET['OngletTDB']."','".$_GET['Page']."');</script>";
	}
}
$Menu=$_GET['Menu'];

$requete="SELECT rh_personne_demandeabsence.Id,Backup,
	rh_personne_demandeabsence.EtatN1,rh_personne_demandeabsence.EtatN2,rh_personne_demandeabsence.EtatRH,Id_Personne,
	rh_personne_demandeabsence.DatePriseEnCompteRH,rh_personne_demandeabsence.DateValidationN1,rh_personne_demandeabsence.DateValidationN2,
	rh_personne_demandeabsence.Id_Prestation,rh_personne_demandeabsence.Id_Pole,
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_N1) AS ResponsableN1,  
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_N2) AS ResponsableN2,  
	(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN1) AS RaisonRefus1,Commentaire1,
	(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN2) AS RaisonRefus2,Commentaire2,
	(SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_demandeabsence.Id_Prestation) AS Prestation, 
	(SELECT new_competences_prestation.NbNiveauValidationConges FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_demandeabsence.Id_Prestation) AS NbNiveauValidationConges, 
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Personne) AS Personne,  
	(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=rh_personne_demandeabsence.Id_Pole) AS Pole 
	FROM rh_personne_demandeabsence
	WHERE rh_personne_demandeabsence.Id=".$_GET['Id'] ;
$result=mysqli_query($bdd,$requete);
$row=mysqli_fetch_array($result);

$Etat="";
$CouleurEtat="#ffffff";
$NumRefus=2;
$EstRefuse=0;

if($row['EtatN2']==1 && $row['EtatRH']==1){
	$req="SELECT Id 
		FROM rh_absence 
		WHERE Suppr=0 
		AND Id_Personne_DA=".$_GET['Id']." 
		AND Id_TypeAbsenceDefinitif>0
		AND Id_TypeAbsenceDefinitif<>Id_TypeAbsenceInitial ";
	$resultAbs=mysqli_query($bdd,$req);
	$nbAbs=mysqli_num_rows($resultAbs);
	if($nbAbs>0){
		if($_SESSION["Langue"]=="FR"){
			$Etat="Modifiées par RH";}
		else{
			$Etat="Modified by HR";}
		$CouleurEtat="#ff53ab";
	}
	else{
		if($_SESSION["Langue"]=="FR"){
			$Etat="Validée";}
		else{
			$Etat="Validated";}
		$CouleurEtat="#7ffa1e";
	}
}
elseif($row['EtatN2']==1 && $row['EtatRH']==0){
	if($_SESSION["Langue"]=="FR"){
		$Etat="Transmis aux RH";}
	else{
		$Etat="Submitted to HR";}
	$CouleurEtat="#449ef0";
}
elseif($row['EtatN2']==-1 || $row['EtatN1']==-1){
	if($_SESSION["Langue"]=="FR"){
		$Etat="Refusée";}
	else{
		$Etat="Refused";}
	$EstRefuse=1;
	$CouleurEtat="#ff3d3d";
	if($row['EtatN1']==-1){$NumRefus=1;}
}
elseif($row['EtatN2']==0 && $row['EtatN1']<>-1){
	if($_SESSION["Langue"]=="FR"){
		$Etat="En attente de pré validation";}
	else{
		$Etat="Waiting for pre-validation";}
	$CouleurEtat="#fab342";
}

$step=5;
$ModifRH=0;
if($Menu==3){
	if(($row['EtatN1']==0 && DroitsPrestationPole(array($IdPosteChefEquipe),$row['Id_Prestation'],$row['Id_Pole']))
	|| ($row['EtatN1']==1 && $row['EtatN2']==0 && DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$row['Id_Prestation'],$row['Id_Pole']))
	|| ($row['EtatN1']==1 && $row['EtatN2']==0 && DroitsPrestationPole(array($IdPosteCoordinateurProjet),$row['Id_Prestation'],$row['Id_Pole']) && $row['Id_Personne']<>$_SESSION['Id_Personne'] && DroitsPrestationPoleV2($row['Id_Personne'],array($IdPosteCoordinateurEquipe),$row['Id_Prestation'],$row['Id_Pole']))
	){
		if($row['EtatN1']==0){$step=1;}
		elseif($row['EtatN2']==0){$step=2;}
	}
}
elseif($Menu==4){
	if($row['EtatN2']==1){$ModifRH=1;}
}
?>

<form id="formulaire" class="test" action="Modif_Conges.php" method="post" onsubmit=" return VerifChamps();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Step" id="Step" value="<?php echo $step; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="Id_Prestation" id="Id_Prestation" value="<?php echo $row['Id_Prestation']; ?>" />
	<input type="hidden" name="Id_Pole" id="Id_Pole" value="<?php echo $row['Id_Pole']; ?>" />
	<input type="hidden" name="ValiderRefuser" id="ValiderRefuser" value="" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $_GET['TDB']; ?>" />
	<input type="hidden" name="Page" id="Page" value="<?php echo $_GET['Page']; ?>" />
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
							<td align="right" colspan="5">
								<a href="javascript:OuvreFormatExcel('<?php echo $_GET['Id']; ?>')">
								<img width="25px" src="../../Images/pdf.png" border="0" alt="PDF" title="PDF">
								</a>
							</td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "Person :";} ?></td>
							<td width="25%">
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
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Backup :";}else{echo "Backup :";} ?></td>
							<td width="25%" colspan="3">
								<?php echo stripslashes($row['Backup']); ?>
							</td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<?php 
						
						if($_SESSION["Langue"]=="FR"){
							$requete="SELECT rh_absence.Id, rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,rh_absence.NbJour,
								IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) AS Id_TypeAbs,
								(SELECT rh_typeabsence.NbJourAutorise FROM rh_typeabsence WHERE rh_typeabsence.Id=IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial)) AS NbJourAutorise,
								rh_absence.Id_TypeAbsenceInitial,rh_absence.Id_TypeAbsenceDefinitif,rh_absence.NbHeureAbsJour,rh_absence.NbHeureAbsNuit,rh_absence.Id_FonctionRepresentative,
								(SELECT CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=Id_TypeAbsenceInitial) AS TypeAbsIni,
								(SELECT CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=Id_TypeAbsenceDefinitif) AS TypeAbsDef,
								(SELECT Libelle FROM rh_typeabsence WHERE rh_typeabsence.Id=Id_TypeAbsenceInitial) AS LibelleAbsIni,
								(SELECT Libelle FROM rh_typeabsence WHERE rh_typeabsence.Id=Id_TypeAbsenceDefinitif) AS LibelleAbsDef,
								(SELECT Libelle FROM rh_fonctionrepresentative WHERE rh_fonctionrepresentative.Id=Id_FonctionRepresentative) AS FonctionRepresentative,
								rh_absence.HeureDepart,rh_absence.HeureArrivee
								FROM rh_absence
								WHERE Suppr=0 AND rh_absence.Id_Personne_DA=".$_GET['Id']."
								ORDER BY DateDebut
								" ;
						}
						else{
							$requete="SELECT rh_absence.Id, rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,rh_absence.NbJour,
								IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial) AS Id_TypeAbs,
								(SELECT rh_typeabsence.NbJourAutorise FROM rh_typeabsence WHERE rh_typeabsence.Id=IF(Id_TypeAbsenceDefinitif>0,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial)) AS NbJourAutorise,
								rh_absence.Id_TypeAbsenceInitial,rh_absence.Id_TypeAbsenceDefinitif,rh_absence.NbHeureAbsJour,rh_absence.NbHeureAbsNuit,rh_absence.Id_FonctionRepresentative,
								(SELECT CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=Id_TypeAbsenceInitial) AS TypeAbsIni,
								(SELECT CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=Id_TypeAbsenceDefinitif) AS TypeAbsDef,
								(SELECT LibelleEN FROM rh_typeabsence WHERE rh_typeabsence.Id=Id_TypeAbsenceInitial) AS LibelleAbsIni,
								(SELECT LibelleEN FROM rh_typeabsence WHERE rh_typeabsence.Id=Id_TypeAbsenceDefinitif) AS LibelleAbsDef,
								(SELECT Libelle FROM rh_fonctionrepresentative WHERE rh_fonctionrepresentative.Id=Id_FonctionRepresentative) AS FonctionRepresentative,
								rh_absence.HeureDepart,rh_absence.HeureArrivee
								FROM rh_absence
								WHERE Suppr=0 AND rh_absence.Id_Personne_DA=".$_GET['Id']."
								ORDER BY DateDebut
								" ;
						}
						$resultAbs=mysqli_query($bdd,$requete);
						$nbAbs=mysqli_num_rows($resultAbs);
						if($nbAbs>0){
							$couleur="#dbdbdb";
							while($rowAbs=mysqli_fetch_array($resultAbs)){
								if($couleur=="#dbdbdb"){$couleur="#EEEEEE";}
								else{$couleur="#dbdbdb";}
						?>
						<tr bgcolor="<?php echo $couleur;?>">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Du :";}else{echo "from :";} ?></td>
							<td width="15%">
								<?php echo AfficheDateJJ_MM_AAAA($rowAbs['DateDebut']); ?>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "au :";}else{echo "to :";} ?></td>
							<td width="20%">
								<?php echo AfficheDateJJ_MM_AAAA($rowAbs['DateFin']); ?>
							</td>
							<td width="10%" class="Libelle" colspan="2">
								<?php 
									echo $rowAbs['NbJour'];
									if($rowAbs['Id_TypeAbsenceDefinitif']>0){
										echo " <del>".$rowAbs['TypeAbsIni']." (".$rowAbs['LibelleAbsIni'].")</del> ".$rowAbs['TypeAbsDef']. " (".$rowAbs['LibelleAbsDef'].")";
									}
									else{
										echo " ".$rowAbs['TypeAbsIni']." (".$rowAbs['LibelleAbsIni'].")";
									}
								?>
							</td>
						</tr>
						<?php
								//CSS
								if($rowAbs['Id_TypeAbs']==8){
									echo "<tr bgcolor='".$couleur."'>";
									if($_SESSION["Langue"]=="FR"){echo "<td class='Libelle'>Journée complète</td>";}else{echo "<td class='Libelle'>Full day</td>";}
									if($rowAbs['NbHeureAbsJour']>0 || $rowAbs['NbHeureAbsNuit']>0){
										if($_SESSION["Langue"]=="FR"){echo "<td>Non</td>";}else{echo "<td>No</td>";}
										if($_SESSION["Langue"]=="FR"){echo "<td class='Libelle'>Heure de début</td>";}else{echo "<td class='Libelle'>Start time</td>";}
										echo "<td>".$rowAbs['HeureDepart']."</td>";
										if($_SESSION["Langue"]=="FR"){echo "<td class='Libelle'>Heure de fin</td>";}else{echo "<td class='Libelle'>End time</td>";}
										echo "<td>".$rowAbs['HeureArrivee']."</td>";
										echo "</tr>";
										echo "<tr bgcolor='".$couleur."'>";
										if($_SESSION["Langue"]=="FR"){echo "<td class='Libelle'>Nbre d'heures d'absence (6h et 21h)</td>";}else{echo "<td class='Libelle'>Number of hours of absence (6h and 21h)</td>";}
										echo "<td>".$rowAbs['NbHeureAbsJour']."</td>";
										if($_SESSION["Langue"]=="FR"){echo "<td class='Libelle'>Nbre d'heures d'absence (21h et 6h)</td>";}else{echo "<td class='Libelle'>Number of hours of absence (21h and 6h)</td>";}
										echo "<td colspan='4'>".$rowAbs['NbHeureAbsNuit']."</td>";
										echo "</tr>";
										
									}
									else{
										if($_SESSION["Langue"]=="FR"){echo "<td colspan='6'>Oui</td>";}else{echo "<td colspan='6'>Yes</td>";}
										echo "</tr>";
									}
								}
								//RC
								elseif($rowAbs['Id_TypeAbs']==11){
									echo "<tr bgcolor='".$couleur."'>";
									if($_SESSION["Langue"]=="FR"){echo "<td class='Libelle'>Nombre d'heure</td>";}else{echo "<td class='Libelle'>Number of hours</td>";}
									echo "<td colspan='6'>".$rowAbs['NbHeureAbsJour']."</td>";
									echo "</tr>";
								}
								//BDD
								elseif($rowAbs['Id_TypeAbs']==9){
									echo "<tr bgcolor='".$couleur."'>";
									if($_SESSION["Langue"]=="FR"){echo "<td class='Libelle'>Nombre d'heures</td>";}else{echo "<td class='Libelle'>Number of hours</td>";}
									echo "<td>".$rowAbs['NbHeureAbsJour']."</td>";
									if($_SESSION["Langue"]=="FR"){echo "<td class='Libelle'>Fonction représentative</td>";}else{echo "<td class='Libelle'>Representative function</td>";}
									echo "<td colspan='4'>".$rowAbs['FonctionRepresentative']."</td>";
									echo "</tr>";
								}
							}
						}
						?>
						<tr>
							<td height="5" colspan="5"></td>
						</tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Etat :";}else{echo "State :";} ?></td>
							<td width="15%" bgcolor="<?php echo $CouleurEtat;?>">
								<?php echo $Etat; ?>
							</td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Validateur :";}else{echo "Validator :";} ?></td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<?php
							if($row['EtatN1']<>0){
						?>
							<tr>
								<td width="10%" class="Libelle"></td>
								<td width="15%">
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
								<td width="15%">
									<?php if($_SESSION["Langue"]=="FR"){echo "Responsable N+2 : ";}else{echo "N + 2 manager : ";} echo $row['ResponsableN2']." (".AfficheDateJJ_MM_AAAA($row['DateValidationN1']).")"; ?>
								</td>
							</tr>
							<?php
								}
								else{
									if($row['NbNiveauValidationConges']==0 || $row['NbNiveauValidationConges']==2){
										if(DroitsPrestationPoleV2($row['Id_Personne'],array($IdPosteCoordinateurEquipe),$row['Id_Prestation'],$row['Id_Pole'])){
											$requetePersonnePoste="SELECT DISTINCT new_competences_personne_poste_prestation.Backup, 
																CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne 
																FROM new_competences_personne_poste_prestation, new_rh_etatcivil 
																WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
																AND new_competences_personne_poste_prestation.Id_Poste = 3
																AND new_competences_personne_poste_prestation.Id_Prestation=".$row['Id_Prestation']."
																AND new_competences_personne_poste_prestation.Id_Pole=".$row['Id_Pole']."
																ORDER BY new_competences_personne_poste_prestation.Backup ASC";
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
											
										}
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
								if($row['NbNiveauValidationConges']==0 || $row['NbNiveauValidationConges']==2){
									if(DroitsPrestationPoleV2($row['Id_Personne'],array($IdPosteCoordinateurEquipe),$row['Id_Prestation'],$row['Id_Pole'])){
										$requetePersonnePoste="SELECT DISTINCT new_competences_personne_poste_prestation.Backup, 
															CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne 
															FROM new_competences_personne_poste_prestation, new_rh_etatcivil 
															WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
															AND new_competences_personne_poste_prestation.Id_Poste = 3
															AND new_competences_personne_poste_prestation.Id_Prestation=".$row['Id_Prestation']."
															AND new_competences_personne_poste_prestation.Id_Pole=".$row['Id_Pole']."
															ORDER BY new_competences_personne_poste_prestation.Backup ASC";
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
										
									}									
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
						?>
						<tr>
							<td height="5"></td>
						</tr>
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
											AND Type='DemandeAbsence'
											AND Id_Plateforme = (
												SELECT Id_Plateforme 
												FROM rh_personne_demandeabsence 
												LEFT JOIN new_competences_prestation 
												ON rh_personne_demandeabsence.Id_Prestation=new_competences_prestation.Id
												WHERE rh_personne_demandeabsence.Id=".$_GET['Id']." LIMIT 1)
											ORDER BY Libelle ASC";
										$result=mysqli_query($bdd,$requete);
										while($rowRaison=mysqli_fetch_array($result))
										{
											echo "<option value='".$rowRaison['Id']."'>";
											echo str_replace("'"," ",stripslashes($rowRaison['Libelle']))."</option>\n";
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
						<tr>
							
						</tr>
						<?php } 
							if($step<>5){
								if(($row['EtatN1']==0 && DroitsPrestationPole(array($IdPosteChefEquipe),$row['Id_Prestation'],$row['Id_Pole']) && $row['Id_Personne']<>$_SESSION['Id_Personne'])
								|| ($row['EtatN1']==1 && $row['EtatN2']==0 && DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$row['Id_Prestation'],$row['Id_Pole']) && $row['Id_Personne']<>$_SESSION['Id_Personne'])
								|| ($row['EtatN1']==1 && $row['EtatN2']==0 && DroitsPrestationPole(array($IdPosteCoordinateurProjet),$row['Id_Prestation'],$row['Id_Pole']) && $row['Id_Personne']<>$_SESSION['Id_Personne'] && DroitsPrestationPoleV2($row['Id_Personne'],array($IdPosteCoordinateurEquipe),$row['Id_Prestation'],$row['Id_Pole']))
								){
						?>
						<tr>
							<td colspan="6" align="center">
								<input class="Bouton" type="submit" name="Valider" onclick="document.getElementById('ValiderRefuser').value='V';" value="<?php if($_SESSION["Langue"]=="FR"){echo "Enregistrer";}else{echo "Save";} ?>"/>&nbsp;&nbsp;&nbsp;&nbsp
							</td>
						</tr>
						<?php 
								}
							} 
							if($Menu==4 && $ModifRH==1){
								
								$selType3="<option value='0' selected></option>";
								
								$Dispo="(DispoPourSalarie=1 OR DispoPourSalarie=0)";
								if(estSalarie(date('Y-m-d'),$row['Id_Personne'])==0){$Dispo="DispoPourInterimaire=1";}
											
								if($_SESSION["Langue"]=="FR"){
									$reqAbsVac = "SELECT Id ,Libelle, CodePlanning, InformationSalarie, NbJourAutorise 
												FROM rh_typeabsence 
												WHERE Suppr=0 
												AND ".$Dispo."
												ORDER BY CodePlanning ";
								}
								else{
									$reqAbsVac = "SELECT Id ,LibelleEN AS Libelle, CodePlanning, InformationSalarie, NbJourAutorise 
												FROM rh_typeabsence 
												WHERE Suppr=0 
												AND ".$Dispo."
												ORDER BY CodePlanning ";
								}
								$resultAbsVac=mysqli_query($bdd,$reqAbsVac);
								$nbAbsVac=mysqli_num_rows($resultAbsVac);
								if ($nbAbsVac > 0){
									while($rowAbsVac=mysqli_fetch_array($resultAbsVac)){	
										$selType3.="<option value='".$rowAbsVac['Id']."' >".$rowAbsVac['CodePlanning']." | ".$rowAbsVac['Libelle']."</option>";
									}
								}
								if($_SESSION['Langue']=="FR"){
									$selType3.="<option value='-1'>Supprimer</option>";
								}
								else{
									$selType3.="<option value='-1'>Remove</option>";
								}
								$selType3.="</select>";
								
								$req = "SELECT Id ,Libelle
											FROM rh_fonctionrepresentative 
											WHERE Suppr=0 
											ORDER BY Libelle ";

								$result=mysqli_query($bdd,$req);
								$nb=mysqli_num_rows($result);
								$selFonction="";
								if ($nb > 0){
									while($rowFonction=mysqli_fetch_array($result)){	
										$selFonction.="<option value='".$rowFonction['Id']."' >".$rowFonction['Libelle']."</option>";
									}
								}

								$resultAbs=mysqli_query($bdd,$requete);
								$nbAbs=mysqli_num_rows($resultAbs);
								if($nbAbs>0){
									echo "<tr><td height='4'></td></tr>";
									if($_SESSION["Langue"]=="FR"){
										echo "<tr><td colspan='6' class='Libelle'>Modification du type de congés</td></tr>";
									}
									else{
										echo "<tr><td colspan='6' class='Libelle'>Changing the type of leave</td></tr>";
									}
									echo "<tr><td colspan='6'>";
									echo "<div style='height:160px;width:100%;overflow:auto;'>
									<table style='width:100%;'>";
									if($_SESSION["Langue"]=="FR"){
										echo "<tr>
											<td class='EnTeteTableauCompetences' width='10%'>Date</td>
											<td class='EnTeteTableauCompetences' width='10%'>Type de congés</td>
											<td class='EnTeteTableauCompetences' width='80%'>Nouveau type de congés</td></tr>";
									}
									else{
											echo "<tr>
											<td class='EnTeteTableauCompetences' width='10%'>Date</td>
											<td class='EnTeteTableauCompetences' width='10%'>Type of leave</td>
											<td class='EnTeteTableauCompetences' width='80%'>New type of leave</td></tr>";
									}
									$couleur="#dbdbdb";
									$i=0;
									while($rowAbs=mysqli_fetch_array($resultAbs)){
										$tmpDate = $rowAbs['DateDebut'];
										$dateFin = $rowAbs['DateFin'];
										while ($tmpDate <= $dateFin){
											if($couleur=="#dbdbdb"){$couleur="#EEEEEE";}
											else{$couleur="#dbdbdb";}
											
											$contenuSelect="<option value='0'></option>";
											//if($rowAbs['NbJourAutorise']==0){$contenuSelect=$selType3;}
											$contenuSelect=$selType3;
											echo "<tr bgcolor='".$couleur."' >
												<td>
												<input type='hidden' name='Id_".$i."' id='Id_".$i."' value='".$rowAbs['Id']."' />
												".AfficheDateJJ_MM_AAAA($tmpDate)."&nbsp;&nbsp;
												</td>
												<td align='center'>
												".$rowAbs['TypeAbsIni']."
												</td>
												<td>
												<select id='typeAbsence".$i."' name='typeAbsence".$i."' onchange='Modif_TypeAbsenceRH(".$i.");' style='width: 60%;'>".$contenuSelect."
												<table>";
												?>
												<tr class="journee<?php echo $i;?>" style="display:none;">
													<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Journée complète :";}else{echo "Full day :";} ?> </td>
													<td width="15%" colspan="3" align="left">
														<input type="radio" id='journeeComplete<?php echo $i;?>' name='journeeComplete<?php echo $i;?>' onclick="Modif_TypeAbsence2RH(<?php echo $i;?>)" value="Oui" checked><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?> &nbsp;&nbsp;
														<input type="radio" id='journeeComplete<?php echo $i;?>' name='journeeComplete<?php echo $i;?>' onclick="Modif_TypeAbsence2RH(<?php echo $i;?>)" value="Non" ><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?> &nbsp;&nbsp;
													</td>
												</tr>
												<tr class="nbHeure<?php echo $i;?>" style="display:none;">
													<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de début";}else{echo "Start time";} ?> : </td>
													<td width="15%">
														<div class="input-group bootstrap-timepicker timepicker">
															<input class="form-control input-small heureDebut" type="text" name="heureDebut<?php echo $i;?>" id="heureDebut<?php echo $i;?>" size="8" value="">
														</div>
													</td>
													<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de fin";}else{echo "End time";} ?> : </td>
													<td width="15%">
														<div class="input-group bootstrap-timepicker timepicker">
															<input class="form-control input-small heureFin" type="text" name="heureFin<?php echo $i;?>" id="heureFin<?php echo $i;?>" size="8" value="">
														</div>
													</td>
												</tr>
												<tr class="nbHeure<?php echo $i;?>" style="display:none;">
													<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nbre d'heures d'absence entre 6h et 21h";}else{echo "Number of hours of absence between 6h and 21h";} ?> : </td>
													<td width="15%">
														<input onKeyUp="nombre(this)" style="text-align:center;" name="nbHeureJour<?php echo $i;?>" id="nbHeureJour<?php echo $i;?>" size="10" type="text" value= "">
													</td>
													<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nbre d'heures d'absence entre 21h et 6h";}else{echo "Number of hours of absence between 21h and 6h";} ?>  : </td>
													<td width="15%">
														<input onKeyUp="nombre(this)" style="text-align:center;" name="nbHeureNuit<?php echo $i;?>" id="nbHeureNuit<?php echo $i;?>" size="10" type="text" value= "">
													</td>
												</tr>
												<tr class="nbHeuresRC<?php echo $i;?>" style="display:none;">
													<td width="45%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nombre d'heure";}else{echo "Number of hours";} ?> : </td>
													<td width="45%">
														<input onKeyUp="nombre2(this)" type="text" name="nbHeureRC<?php echo $i;?>" id="nbHeureRC<?php echo $i;?>" size="6" value="">
													</td>
												</tr>
												<tr class="delegation<?php echo $i;?>" style="display:none;">
													<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nombre d'heure";}else{echo "Number of hours";} ?> : </td>
													<td width="15%">
														<input onKeyUp="nombre(this)" type="text" name="nbHeuresBDD<?php echo $i;?>" id="nbHeuresBDD<?php echo $i;?>" size="6" value="">
													</td>
													<td width="15%" class="Libelle" valign="top"><?php if($_SESSION["Langue"]=="FR"){echo "Fonction représentative";}else{echo "Representative function";} ?> : </td>
													<td width="15%" valign="top">
														<select id="fonctionRepresentative<?php echo $i;?>" name="fonctionRepresentative<?php echo $i;?>" style="width: 60%;">
															<option name="0" value="0"></option>
															<?php echo $selFonction; ?>
														</select>
													</td>
												</tr>
											<?php
											echo "</table>
												</td>
											</tr>";
											//Jour suivant
											$tmpDate =date("Y-m-d",strtotime($tmpDate." +1 day"));
											$i++;
										}
									}
									echo "</table>
										</div>";
									echo "</td></tr>";
								}
								
								?>
								<tr>
									<td colspan='6' align='center'>
										<input class='Bouton' type='submit' name='ModifierType' value="<?php if($_SESSION["Langue"]=="FR"){echo "Modifier";}else{echo "Modify";} ?>" />
									</td>
								</tr>
								<?php
							}
						?>
					</table>
				</td></tr>
			</table>
		</td>
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
			
			$requete="SELECT DateDebut
				FROM rh_absence
				WHERE Suppr=0 
				AND rh_absence.Id_Personne_DA=".$_GET['Id']."
				ORDER BY DateDebut ASC
				" ;
			$resultAbs=mysqli_query($bdd,$requete);
			$rowAbs=mysqli_fetch_array($resultAbs);
								
			$Debut=date("Y-m-1",strtotime($rowAbs['DateDebut']." -6 month"));
			$Fin=date("Y-m-1",strtotime($rowAbs['DateDebut']." +6 month"));

			$tmpDate=date("Y-m-1",strtotime($rowAbs['DateDebut']." -6 month"));


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
			
			
			$mois1=date("m",strtotime($rowAbs['DateDebut']." -6 month"));
			$mois2=date("m",strtotime($rowAbs['DateDebut']." -5 month"));
			$mois3=date("m",strtotime($rowAbs['DateDebut']." -4 month"));
			$mois4=date("m",strtotime($rowAbs['DateDebut']." -3 month"));
			$mois5=date("m",strtotime($rowAbs['DateDebut']." -2 month"));
			$mois6=date("m",strtotime($rowAbs['DateDebut']." -1 month"));
			$mois7=date("m",strtotime($rowAbs['DateDebut']." +0 month"));
			$mois8=date("m",strtotime($rowAbs['DateDebut']." +1 month"));
			$mois9=date("m",strtotime($rowAbs['DateDebut']." +2 month"));
			$mois10=date("m",strtotime($rowAbs['DateDebut']." +3 month"));
			$mois11=date("m",strtotime($rowAbs['DateDebut']." +4 month"));
			$mois12=date("m",strtotime($rowAbs['DateDebut']." +5 month"));
			$tab=array($mois1,$mois2,$mois3,$mois4,$mois5,$mois6,$mois7,$mois8,$mois9,$mois10,$mois11,$mois12);
			$nb=1;
			
			$tmpDate=date("Y-m-1",strtotime($rowAbs['DateDebut']." -6 month"));
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

												echo "<td class='jourSemaine' ".$bgcolor." align='center'>".$jour."<sup>".$type."</sup></td>";
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
											
											echo "<td class='jourSemaine' ".$bgcolor." align='center'>".$jour."<sup>".$type."</sup></td>";
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
					<td height="10"></td>
				</tr>
				<tr>
					<td colspan="6" align="center">
						<table align="center" width="60%" cellpadding="0" cellspacing="0">
							<tr align="left">
								<td bgcolor="<?php echo $EnAttente; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="15%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "En attente de pré validation";}else{echo "Waiting for pre-validation";} ?></td>
								<td bgcolor="<?php echo $TransmisRH; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Transmis aux RH";}else{echo "Submitted to HR";} ?></td>
								<td bgcolor="<?php echo $Automatique; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Automatique";}else{echo "Automatic";} ?></td>
								<td bgcolor="<?php echo $Validee; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Validée";}else{echo "Validated";} ?></td>
								<td bgcolor="<?php echo $Refusee; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Refusée";}else{echo "Declined";} ?></td>
								<td bgcolor="<?php echo $AbsenceInjustifies; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="15%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Absence injustifiée";}else{echo "Unjustified absence";} ?></td>
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
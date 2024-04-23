<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<!-- Feuille de style -->
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Planning.css">
	<script language="javascript" src="Modif_VacationPersonne.js"></script>
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script>
		function FermerEtRecharger(Menu)
		{
			opener.location.href="Planning.php?Menu="+Menu;
			window.close();
		}
		function effacerVacation(){
			document.getElementById('vacationAbsence').value = "0";
		}
		function effacerCopie(){
			document.getElementById('PersonneCopie').value = "0";
		}
		function DemandeCreationHS(nbHeures,Id_Prestation,Id_Personne,dates,Prestation,Personne,Id_Createur,dates2,NomFormation,dateRenvoi,Pole,Tri){
			question="Voulez-vous créer une heure supplémentaire pour "+Personne+"? \n sur la prestation "+Prestation+"\n de "+nbHeures+" heure(s) de jour \n pour les dates suivantes : \n ";
			var $tab = dates.split(",");
			for(var i= 0; i < $tab.length; i++){
				question= question+$tab[i]+"\n ";
			}
			if(window.confirm(question)){
				window.location = "CreerHeureSupp.php?nbHeures="+nbHeures+"&Id_Prestation="+Id_Prestation+"&Id_Personne="+Id_Personne+"&Id_Createur="+Id_Createur+"&dates2="+dates2+"&NomFormation="+NomFormation+"&dateRenvoi="+dateRenvoi+"&Pole="+Pole+"&Tri="+Tri;
			}
		}
	</script>
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
</head>

<?php
require("../Connexioni.php");
require("../Fonctions.php");
require_once("Fonctions_Planning.php");

if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

if(isset($_POST['submitSupprimer'])){
	if($_POST['dateDebut']<>"" && $_POST['dateFin']<>""){
		$Personne="";
		if(isset($_POST['PersonneSelect']))
		{
			$PersonneSelect = $_POST['PersonneSelect'];
			for($i=0;$i<sizeof($PersonneSelect);$i++)
			{
				if(isset($PersonneSelect[$i])){$Personne.=$PersonneSelect[$i].";";}
			}
		}
		$TabPersonne = preg_split("/[;]+/", $Personne);
		for($i=0;$i<sizeof($TabPersonne)-1;$i++){
			
			//Vérifier si la sélection contient des jours RH. Si c'est le cas aucune modification 
			$req="SELECT Id 
				FROM rh_personne_vacation 
				WHERE Id_Prestation=".$_SESSION['FiltreRHPlanning_Prestation']."
				AND Id_Pole=".$_SESSION['FiltreRHPlanning_Pole']." 
				AND Id_Personne=".$TabPersonne[$i]." 
				AND DateVacation>='".TrsfDate_($_POST['dateDebut'])."'
				AND DateVacation<='".TrsfDate_($_POST['dateFin'])."'
				AND Suppr=0
				AND EmisParRH=1
				";
			$resultSelect=mysqli_query($bdd,$req);
			$nbSelect=mysqli_num_rows($resultSelect);
			
			if ($nbSelect == 0 || $_POST['Menu']=="4"){
				$req="UPDATE rh_personne_vacation
					SET Suppr=1,
					Id_Suppr=".$_SESSION['Id_Personne'].", 
					DateSuppr='".date('Y-m-d')."' 
					WHERE Id_Prestation=".$_SESSION['FiltreRHPlanning_Prestation']."
					AND Id_Pole=".$_SESSION['FiltreRHPlanning_Pole']." 
					AND Id_Personne=".$TabPersonne[$i]." 
					AND DateVacation>='".TrsfDate_($_POST['dateDebut'])."'
					AND DateVacation<='".TrsfDate_($_POST['dateFin'])."'";
				$resultSuppr=mysqli_query($bdd,$req);
			}
		}
	}
	echo "<script>FermerEtRecharger(".$_POST['Menu'].");</script>";
 }
 $VacJES = "";
if(isset($_POST['submitEnregistrer'])){
	//Suppression des anciennes données
	if($_POST['dateDebut']<>"" && $_POST['dateFin']<>""){
		$Personne="";
		if(isset($_POST['PersonneSelect']))
		{
			$PersonneSelect = $_POST['PersonneSelect'];
			for($i=0;$i<sizeof($PersonneSelect);$i++)
			{
				if(isset($PersonneSelect[$i])){$Personne.=$PersonneSelect[$i].";";}
			}
		}
		$TabPersonne = preg_split("/[;]+/", $Personne);
		for($i=0;$i<sizeof($TabPersonne)-1;$i++){
			$VacJES = "";
			//Vérifier si la sélection contient des jours RH. Si c'est le cas aucune modification 
			$req="SELECT Id 
				FROM rh_personne_vacation 
				WHERE Id_Prestation=".$_SESSION['FiltreRHPlanning_Prestation']."
				AND Id_Pole=".$_SESSION['FiltreRHPlanning_Pole']." 
				AND Id_Personne=".$TabPersonne[$i]." 
				AND DateVacation>='".TrsfDate_($_POST['dateDebut'])."'
				AND DateVacation<='".TrsfDate_($_POST['dateFin'])."'
				AND Suppr=0
				AND EmisParRH=1
				";
			$resultSelect=mysqli_query($bdd,$req);
			$nbSelect=mysqli_num_rows($resultSelect);
			
			if ($nbSelect == 0 || $_POST['Menu']=="4"){
				$req="UPDATE rh_personne_vacation
					SET Suppr=1,
					Id_Suppr=".$_SESSION['Id_Personne'].",
					DateSuppr='".date('Y-m-d')."'  
					WHERE Id_Prestation=".$_SESSION['FiltreRHPlanning_Prestation']."
					AND Id_Pole=".$_SESSION['FiltreRHPlanning_Pole']." 
					AND Id_Personne=".$TabPersonne[$i]." 
					AND DateVacation>='".TrsfDate_($_POST['dateDebut'])."'
					AND DateVacation<='".TrsfDate_($_POST['dateFin'])."'";
				$resultSuppr=mysqli_query($bdd,$req);
				
				//Ajout des nouvelles données vacation
				
				$reqSuite="";
				if($_POST['Menu']=="4"){
					$reqSuite=",NbHeureJour,NbHeureEquipeJour,NbHeureEquipeNuit,NbHeurePause,NbHeureFormation,NbHeureFormationETT,NbHeureAPrendreEnCompte";
				}
				$requeteInsert="INSERT INTO rh_personne_vacation (Id_Personne, Id_Vacation, Id_Prestation, Id_Pole, DateVacation, DateCreation, Id_Createur,EmisParRH,Divers,DatePriseEnCompteRH,Commentaire".$reqSuite.")";
				$requeteInsert.=" VALUES ";
				
				$EmisParRH=0;
				if($Menu==4){$EmisParRH=1;}
				
				$nbCopie=0;
				if ($_POST['PersonneCopie'] <> "0"){
					//Recherche des vacations de la copie
					
					$reqCopie = "SELECT Id_Vacation, DateVacation 
							FROM rh_personne_vacation 
							WHERE Suppr=0
							AND Id_Prestation=".$_SESSION['FiltreRHPlanning_Prestation']."
							AND Id_Pole=".$_SESSION['FiltreRHPlanning_Pole']." 
							AND Id_Personne=".$_POST['PersonneCopie']." 
							AND DateVacation>='".TrsfDate_($_POST['dateDebut'])."'
							AND DateVacation<='".TrsfDate_($_POST['dateFin'])."'";
					$resultCopie=mysqli_query($bdd,$reqCopie);
					$nbCopie=mysqli_num_rows($resultCopie);
				}

				$dateDebutReq=TrsfDate_($_POST['dateDebut']);
				$dateFinReq=TrsfDate_($_POST['dateFin']);
				
				while ($dateDebutReq <= $dateFinReq){
					
					//Vérifier si la sélection contient des jours fixes. 
					$req="SELECT DateJour 
						FROM rh_jourfixe 
						WHERE Suppr=0 
						AND DateJour='".$dateDebutReq."'
						AND Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$_SESSION['FiltreRHPlanning_Prestation'].")
						AND (Id_Prestation=0 OR Id_Prestation=".$_SESSION['FiltreRHPlanning_Prestation'].")
						";
					$resultJourFixe=mysqli_query($bdd,$req);
					$nbJourFixe=mysqli_num_rows($resultJourFixe);
					
					//Vérifier si un congé ou ABS n'est pas prévu ce jour là 
					$reqConges="SELECT rh_personne_demandeabsence.Id
								FROM rh_absence 
								LEFT JOIN rh_personne_demandeabsence 
								ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
								WHERE rh_personne_demandeabsence.Id_Personne=".$TabPersonne[$i]." 
								AND rh_absence.DateFin>='".$dateDebutReq."' 
								AND rh_absence.DateDebut<='".$dateDebutReq."' 
								AND rh_personne_demandeabsence.Suppr=0 
								AND rh_absence.Suppr=0 
								AND rh_personne_demandeabsence.Annulation=0 
								AND rh_absence.NbHeureAbsJour=0
								AND rh_absence.NbHeureAbsJour=0
								AND EtatN1<>-1
								AND EtatN2<>-1 ";
					$resultConges=mysqli_query($bdd,$reqConges);
					$nbConges=mysqli_num_rows($resultConges);
					
					$datePriseEnCompte="0001-01-01";
					if(DateAvant26DuMois($dateDebutReq,date('Y-m-d'))==1){
						$datePriseEnCompte=date('Y-m-d');
					}
					//Vérifier que ce jour peut être ajouté
					$bTravail=0;
					if(TravailCeJourDeSemaine($dateDebutReq,$TabPersonne[$i])<>""){
						$bTravail=1;
					}
					else{
						if(IdContrat($row['Id'],$tmpDate)==0){
							if(TravailCeJourDeSemaineDernierContrat($dateDebutReq,$TabPersonne[$i])<>""){
								$bTravail=1;
							}
						}
					}
					if($bTravail==1){
						if ($_POST['PersonneCopie'] <> "0"){
							$Vacation = "";
							if ($nbCopie > 0){
								mysqli_data_seek($resultCopie,0);
								while($rowCopie=mysqli_fetch_array($resultCopie)){
									if($rowCopie['DateVacation'] == $dateDebutReq){
										$Vacation = $rowCopie['Id_Vacation'];
									}
								}
							}
							if ($Vacation <> ""){
								if(($nbJourFixe==0 || isset($_POST['check_JoursFixes'])) && $nbConges==0){
									$reqSuite="";
									if($_POST['Menu']=="4"){
										$nbJ=0;
										$nbEJ=0;
										$nbEN=0;
										$nbPause=0;
										$nbForm=0;
										$nbFormETT=0;
										$nbHeureAPrendreEnCompte=0;
										if($_POST['NbHeureJour']<>""){$nbJ=$_POST['NbHeureJour'];}
										if($_POST['NbHeureEquipeJour']<>""){$nbEJ=$_POST['NbHeureEquipeJour'];}
										if($_POST['NbHeureEquipeNuit']<>""){$nbEN=$_POST['NbHeureEquipeNuit'];}
										if($_POST['NbHeurePause']<>""){$nbPause=$_POST['NbHeurePause'];}
										if($_POST['NbHeureFormation']<>""){$nbForm=$_POST['NbHeureFormation'];}
										if($_POST['NbHeureFormationETT']<>""){$nbFormETT=$_POST['NbHeureFormationETT'];}
										if($_POST['NbHeureJour']<>"" || $_POST['NbHeureEquipeJour']<>"" || $_POST['NbHeureEquipeNuit']<>"" || $_POST['NbHeurePause']<>"" || $_POST['NbHeureFormation']<>""){$nbHeureAPrendreEnCompte=1;}
										$reqSuite=",".$nbJ.",".$nbEJ.",".$nbEN.",".$nbPause.",".$nbForm.",".$nbFormETT.",".$nbHeureAPrendreEnCompte;
									}
									$requeteInsert.="(".$TabPersonne[$i].",'".$Vacation."',".$_SESSION['FiltreRHPlanning_Prestation'].",".$_SESSION['FiltreRHPlanning_Pole'].",'".$dateDebutReq."','".date('Y-m-d')."',".$_SESSION['Id_Personne'].",".$EmisParRH.",'".addslashes($_POST['divers'])."','".$datePriseEnCompte."','".addslashes($_POST['commentaire'])."'".$reqSuite.")";
									$requeteInsert.=",";
								}
							}
						}
						else{
							if ($_POST['vacationAbsence'] == "-1" || $_POST['vacationAbsence'] == "-2"){
								if ($VacJES == ""){
									if($_POST['vacationAbsence'] == "-1"){
										$VacJES = "1";
									}
									if($_POST['vacationAbsence'] == "-2"){
										$VacJES = "2";
									}
								}
								else{
									//Jour précedent
									$tabDate = explode('-', $dateDebutReq);
									$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
									$weekJourPrecedent = date("W", $timestamp);
									
									//Jour en cours
									$tabDate = explode('-', $dateDebutReq);
									$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
									$weekDuJour = date("W", $timestamp);
									if ($VacJES == "1"){
										if ($weekJourPrecedent <> $weekDuJour){$VacJES = "2";}
									}
									else{
										if ($weekJourPrecedent <> $weekDuJour){$VacJES = "1";}
									}
								}
							}
							elseif ($_POST['vacationAbsence'] == "-3" || $_POST['vacationAbsence'] == "-4"){
								if ($VacJES == ""){
									if($_POST['vacationAbsence'] == "-3"){
										$VacJES = "11";
									}
									if($_POST['vacationAbsence'] == "-4"){
										$VacJES = "10";
									}
								}
								else{
									//Jour précedent
									$tabDate = explode('-', $dateDebutReq);
									$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
									$weekJourPrecedent = date("W", $timestamp);
									
									//Jour en cours
									$tabDate = explode('-', $dateDebutReq);
									$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
									$weekDuJour = date("W", $timestamp);
									if ($VacJES == "11"){
										if ($weekJourPrecedent <> $weekDuJour){$VacJES = "10";}
									}
									else{
										if ($weekJourPrecedent <> $weekDuJour){$VacJES = "11";}
									}
								}
							}
							elseif ($_POST['vacationAbsence'] == "-5" || $_POST['vacationAbsence'] == "-6"){
								if ($VacJES == ""){
									if($_POST['vacationAbsence'] == "-5"){
										$VacJES = "12";
									}
									if($_POST['vacationAbsence'] == "-6"){
										$VacJES = "13";
									}
								}
								else{
									//Jour précedent
									$tabDate = explode('-', $dateDebutReq);
									$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
									$weekJourPrecedent = date("W", $timestamp);
									
									//Jour en cours
									$tabDate = explode('-', $dateDebutReq);
									$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
									$weekDuJour = date("W", $timestamp);
									if ($VacJES == "12"){
										if ($weekJourPrecedent <> $weekDuJour){$VacJES = "13";}
									}
									else{
										if ($weekJourPrecedent <> $weekDuJour){$VacJES = "12";}
									}
								}
							}
							elseif ($_POST['vacationAbsence'] == "-7" || $_POST['vacationAbsence'] == "-8"){
								if ($VacJES == ""){
									if($_POST['vacationAbsence'] == "-7"){
										$VacJES = "9";
									}
									if($_POST['vacationAbsence'] == "-8"){
										$VacJES = "13";
									}
								}
								else{
									//Jour précedent
									$tabDate = explode('-', $dateDebutReq);
									$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
									$weekJourPrecedent = date("W", $timestamp);
									
									//Jour en cours
									$tabDate = explode('-', $dateDebutReq);
									$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
									$weekDuJour = date("W", $timestamp);
									if ($VacJES == "9"){
										if ($weekJourPrecedent <> $weekDuJour){$VacJES = "13";}
									}
									else{
										if ($weekJourPrecedent <> $weekDuJour){$VacJES = "9";}
									}
								}
							}
							elseif ($_POST['vacationAbsence'] == "-9" || $_POST['vacationAbsence'] == "-10"){
								if ($VacJES == ""){
									if($_POST['vacationAbsence'] == "-9"){
										$VacJES = "5";
									}
									if($_POST['vacationAbsence'] == "-10"){
										$VacJES = "2";
									}
								}
								else{
									//Jour précedent
									$tabDate = explode('-', $dateDebutReq);
									$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
									$weekJourPrecedent = date("W", $timestamp);
									
									//Jour en cours
									$tabDate = explode('-', $dateDebutReq);
									$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
									$weekDuJour = date("W", $timestamp);
									if ($VacJES == "5"){
										if ($weekJourPrecedent <> $weekDuJour){$VacJES = "2";}
									}
									else{
										if ($weekJourPrecedent <> $weekDuJour){$VacJES = "5";}
									}
								}
							}
							if ($VacJES == ""){
								$VacJES = $_POST['vacationAbsence'];
							}
							
							if(($nbJourFixe==0 || isset($_POST['check_JoursFixes'])) && $nbConges==0){
								$reqSuite="";
								if($_POST['Menu']=="4"){
									$nbJ=0;
									$nbEJ=0;
									$nbEN=0;
									$nbPause=0;
									$nbForm=0;
									$nbFormETT=0;
									$nbHeureAPrendreEnCompte=0;
									if($_POST['NbHeureJour']<>""){$nbJ=$_POST['NbHeureJour'];}
									if($_POST['NbHeureEquipeJour']<>""){$nbEJ=$_POST['NbHeureEquipeJour'];}
									if($_POST['NbHeureEquipeNuit']<>""){$nbEN=$_POST['NbHeureEquipeNuit'];}
									if($_POST['NbHeurePause']<>""){$nbPause=$_POST['NbHeurePause'];}
									if($_POST['NbHeureFormation']<>""){$nbForm=$_POST['NbHeureFormation'];}
									if($_POST['NbHeureFormationETT']<>""){$nbFormETT=$_POST['NbHeureFormationETT'];}
									if($_POST['NbHeureJour']<>"" || $_POST['NbHeureEquipeJour']<>"" || $_POST['NbHeureEquipeNuit']<>"" || $_POST['NbHeurePause']<>"" || $_POST['NbHeureFormation']<>""){$nbHeureAPrendreEnCompte=1;}
									$reqSuite=",".$nbJ.",".$nbEJ.",".$nbEN.",".$nbPause.",".$nbForm.",".$nbFormETT.",".$nbHeureAPrendreEnCompte;
								}
								$requeteInsert.="(".$TabPersonne[$i].",'".$VacJES."',".$_SESSION['FiltreRHPlanning_Prestation'].",".$_SESSION['FiltreRHPlanning_Pole'].",'".$dateDebutReq."','".date('Y-m-d')."',".$_SESSION['Id_Personne'].",".$EmisParRH.",'".addslashes($_POST['divers'])."','".$datePriseEnCompte."','".addslashes($_POST['commentaire'])."'".$reqSuite.")";
								$requeteInsert.=",";
							}
						}
					}
					//Jour suivant
					$tabDate = explode('-', $dateDebutReq);
					$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
					$dateDebutReq = date("Y-m-d", $timestamp);
				}
				$requeteInsert =substr($requeteInsert, 0, -1)."" ;
				$resultAjout=mysqli_query($bdd,$requeteInsert);
			}
		}
	}
	echo "<script>FermerEtRecharger(".$_POST['Menu'].");</script>";
 }

if($_GET){
	$laDateDebut = $_GET['DateVacation'];
	$laDateFin = $_GET['DateVacation'];
	$IdPersonne= $_GET['Id_Personne'];
}
else{
	$laDateDebut = TrsfDate_($_POST['dateDebut']);
	$laDateFin = TrsfDate_($_POST['dateFin']);
	$IdPersonne= $_POST['Id_Personne'];
}

$laDate=$laDateDebut;
$dateJJJJMM=date('Y-m',strtotime($laDate."+0 month"));

$date_2Mois=date('Y-m',strtotime(date('Y-m-d')."- 2 month"));
$date_1Mois=date('Y-m',strtotime(date('Y-m-d')."- 1 month"));
$date_10=date('Y-m-10');
$mois_10=date('Y-m');
$date_Jour=date('Y-m-d');
if($Menu<>4){
	if($dateJJJJMM<=$date_2Mois || ($dateJJJJMM<=$date_1Mois && $date_Jour>=$date_10 && $mois_10<>$dateJJJJMM)){
		$onClick="";
		$laDateDebut="0001-01-01";
		$laDateFin="0001-01-01";
	}
}
?>
<!-- Script DATE  -->
<script>
var initDatepicker = function() {  
$('input[type=date]').each(function() {  
	var $input = $(this);  
	$input.datepicker({  
		minDate: $input.attr('min'),  
		maxDate: $input.attr('max'),  
		dateFormat: 'dd/mm/yy'  
		});  
	});  
};  
  
if(!Modernizr.inputtypes.date){  
	$(document).ready(initDatepicker);  
}; 

function afficherJusquau(i) {
	var divAffiche = document.getElementById('divAffiche');
	var divAffiche2 = document.getElementById('divAffiche2');
	switch(i) {
		case 0 : divAffiche.style.display = 'none';divAffiche2.style.display = 'none'; break;
		default: divAffiche.style.display = '';divAffiche2.style.display = ''; break;
	}
}
 </script>
 <form  id="formulaire" method="post" action="Modif_VacationPersonne.php" onsubmit=" return selectallVac();">
	<table class="TableCompetences" width="100%">
			<input type="hidden" id="boutonClick" name="boutonClick" value="">
			<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue'];?>">
			<tr style="display:none;">
				<td><input type="text" name="Id_Personne" size="11" value="<?php echo $IdPersonne; ?>"></td>
			</tr>
			<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
			<tr>
				<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personnes :";}else{echo "People :";} ?></td>
				<td width="30%" valign="top">
					<select name="Id_Personne" id="Id_Personne" multiple size="15" onDblclick="ajouter();">
					<?php
					$Personne="";
					if($_POST){
						if(isset($_POST['PersonneSelect']))
						{
							$PersonneSelect = $_POST['PersonneSelect'];
							for($i=0;$i<sizeof($PersonneSelect);$i++)
							{
								if(isset($PersonneSelect[$i])){
									if($Personne<>""){$Personne.=",";}
									$Personne.=$PersonneSelect[$i];
								}
							}
						}
					}
					
					$reqSuite="";
					if($Personne<>""){$reqSuite=" AND new_rh_etatcivil.Id NOT IN (".$Personne.") ";}
					
					$rq="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
						rh_personne_mouvement.Id_Prestation, rh_personne_mouvement.Id_Pole
						FROM new_rh_etatcivil
						LEFT JOIN rh_personne_mouvement 
						ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
						WHERE rh_personne_mouvement.DateDebut<='".$laDateFin."'
						AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$laDateDebut."')
						AND rh_personne_mouvement.EtatValidation=1 
						AND rh_personne_mouvement.Id_Prestation=".$_SESSION['FiltreRHPlanning_Prestation']."
						AND rh_personne_mouvement.Id_Pole=".$_SESSION['FiltreRHPlanning_Pole']."
						AND rh_personne_mouvement.Suppr=0
						".$reqSuite."
						ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
						
					$resultpersonne=mysqli_query($bdd,$rq);
					$i=0;
					while($rowpersonne=mysqli_fetch_array($resultpersonne))
					{
						if($rowpersonne['Id']<>$IdPersonne){
							echo "<option value='".$rowpersonne['Id']."'>".str_replace("'"," ",$rowpersonne['Personne'])."</option>\n";
						}
						echo "<script>Liste_Personne[".$i."] = new Array(".$rowpersonne['Id'].",'".str_replace("'"," ",$rowpersonne['Personne'])."','".$rowpersonne['Id_Prestation']."','".$rowpersonne['Id_Pole']."');</script>";
						$i+=1;
					}
					?>
					</select>

				</td>
				<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personnes sélectionnées (double-clic) :";}else{echo "Selected people (double-click) :";} ?></td>
				<td width="30%" valign="top">
					<select name="PersonneSelect[]" id="PersonneSelect" multiple size="15" onDblclick="effacer();">
					<?php
					$reqSuite="";
					if($Personne<>""){$reqSuite=" OR new_rh_etatcivil.Id IN (".$Personne.") ";}
					$rq="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
						rh_personne_mouvement.Id_Prestation, rh_personne_mouvement.Id_Pole
						FROM new_rh_etatcivil
						LEFT JOIN rh_personne_mouvement 
						ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
						WHERE rh_personne_mouvement.DateDebut<='".$laDateFin."'
						AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$laDateDebut."')
						AND rh_personne_mouvement.EtatValidation=1 
						AND rh_personne_mouvement.Suppr=0
						AND rh_personne_mouvement.Id_Prestation=".$_SESSION['FiltreRHPlanning_Prestation']."
						AND rh_personne_mouvement.Id_Pole=".$_SESSION['FiltreRHPlanning_Pole']."
						AND (new_rh_etatcivil.Id=".$IdPersonne." ".$reqSuite.")
						ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
					$resultpersonne=mysqli_query($bdd,$rq);

					while($rowpersonne=mysqli_fetch_array($resultpersonne))
					{
						
						echo "<option value='".$rowpersonne['Id']."'>".str_replace("'"," ",$rowpersonne['Personne'])."</option>\n";
					}
					?>
					</select>
					<?php
					
					?>
				</td>
			</tr>
			<tr style="display:none;"><td><input type="texte" name="idPersonne" value="<?php echo $idPersonne; ?>"/></td></tr>
			<tr>
				<td height="4"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début :";}else{echo "Start date :";} ?></td>
				<td>
					<input type="date" style="text-align:center;" name="dateDebut" size="11" onchange="submit();" value="<?php echo AfficheDateFR($laDateDebut); ?>">
				</td>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin :";}else{echo "End date :";} ?></td>
				<td>
					<input type="date" style="text-align:center;" name="dateFin" size="11" onchange="submit();" value="<?php echo AfficheDateFR($laDateFin); ?>">
				</td>
			</tr>
			<tr>
				<td height="4"></td>
			</tr>
			<tr>
				<td class="Libelle">Vacation :</td>
				<td>
					<select id="vacationAbsence" name="vacationAbsence" onchange="effacerCopie();">
						<option value='0' selected></option>
						<?php
						if($_SESSION["Langue"]=="FR"){
							$reqVac = "SELECT Id ,Nom, Libelle FROM rh_vacation WHERE Suppr=0 ORDER BY Nom ";
						}
						else{
							$reqVac = "SELECT Id ,Nom, LibelleEN As Libelle FROM rh_vacation WHERE Suppr=0 ORDER BY Nom ";
						}
						
						$resultVac=mysqli_query($bdd,$reqVac);
						$nbVac=mysqli_num_rows($resultVac);
						if ($nbVac > 0){
							while($rowVac=mysqli_fetch_array($resultVac))
							{	
								$Selected = "";
								if($_POST){if($_POST['vacationAbsence']==$rowVac['Id']){$Selected="selected";}}
								echo "<option value='".$rowVac['Id']."' ".$Selected.">".$rowVac['Nom']." | ".$rowVac['Libelle']."</option>";
								
							}
						}
						if($_SESSION["Langue"]=="FR"){
						?>
						<option value='-1' <?php if($_POST){if($_POST['vacationAbsence']==-1){echo "selected";}} ?>>J / ES | Alterner semaines</option>
						<option value='-2' <?php if($_POST){if($_POST['vacationAbsence']==-2){echo "selected";}} ?>>ES / J | Alterner semaines</option>
						<option value='-3' <?php if($_POST){if($_POST['vacationAbsence']==-3){echo "selected";}} ?>>J2 / ES2 | Alterner semaines</option>
						<option value='-4' <?php if($_POST){if($_POST['vacationAbsence']==-4){echo "selected";}} ?>>ES2 / J2 | Alterner semaines</option>
						<option value='-5' <?php if($_POST){if($_POST['vacationAbsence']==-5){echo "selected";}} ?>>J3 / ES3 | Alterner semaines</option>
						<option value='-6' <?php if($_POST){if($_POST['vacationAbsence']==-6){echo "selected";}} ?>>ES3 / J3 | Alterner semaines</option>
						<option value='-7' <?php if($_POST){if($_POST['vacationAbsence']==-7){echo "selected";}} ?>>EJ3 / ES3 | Alterner semaines</option>
						<option value='-8' <?php if($_POST){if($_POST['vacationAbsence']==-8){echo "selected";}} ?>>ES3 / EJ3 | Alterner semaines</option>
						<option value='-9' <?php if($_POST){if($_POST['vacationAbsence']==-9){echo "selected";}} ?>>EJ / ES | Alterner semaines</option>
						<option value='-10' <?php if($_POST){if($_POST['vacationAbsence']==-10){echo "selected";}} ?>>ES / EJ | Alterner semaines</option>
						<?php
						}
						else{
						?>
						<option value='-1' <?php if($_POST){if($_POST['vacationAbsence']==-1){echo "selected";}} ?>>J / ES | Alternate weeks</option>
						<option value='-2' <?php if($_POST){if($_POST['vacationAbsence']==-2){echo "selected";}} ?>>ES / J | Alternate weeks</option>
						<option value='-3' <?php if($_POST){if($_POST['vacationAbsence']==-3){echo "selected";}} ?>>J2 / ES2 | Alternate weeks</option>
						<option value='-4' <?php if($_POST){if($_POST['vacationAbsence']==-4){echo "selected";}} ?>>ES2 / J2 | Alternate weeks</option>
						<option value='-5' <?php if($_POST){if($_POST['vacationAbsence']==-5){echo "selected";}} ?>>J3 / ES3 | Alternate weeks</option>
						<option value='-6' <?php if($_POST){if($_POST['vacationAbsence']==-6){echo "selected";}} ?>>ES3 / J3 | Alternate weeks</option>
						<option value='-7' <?php if($_POST){if($_POST['vacationAbsence']==-7){echo "selected";}} ?>>EJ3 / ES3 | Alternate weeks</option>
						<option value='-8' <?php if($_POST){if($_POST['vacationAbsence']==-8){echo "selected";}} ?>>ES3 / EJ3 | Alternate weeks</option>
						<option value='-9' <?php if($_POST){if($_POST['vacationAbsence']==-9){echo "selected";}} ?>>EJ / ES | Alternate weeks</option>
						<option value='-10' <?php if($_POST){if($_POST['vacationAbsence']==-10){echo "selected";}} ?>>ES / EJ | Alternate weeks</option>
						<?php
						}
						?>
					</select>
				</td>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "ou copie des vacations de :";}else{echo "or copy of the vacations of :";} ?></td>
				<td>
					<?php
					//Personnes  présentent sur cette prestation à ces dates
					$req="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
						rh_personne_mouvement.Id_Prestation, rh_personne_mouvement.Id_Pole
						FROM new_rh_etatcivil
						LEFT JOIN rh_personne_mouvement 
						ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
						WHERE rh_personne_mouvement.DateDebut<='".$laDateDebut."'
						AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$laDateFin."')
						AND rh_personne_mouvement.EtatValidation=1 
						AND rh_personne_mouvement.Suppr=0
						AND rh_personne_mouvement.Id_Prestation=".$_SESSION['FiltreRHPlanning_Prestation']."
						AND rh_personne_mouvement.Id_Pole=".$_SESSION['FiltreRHPlanning_Pole']."
						ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
					
					$resultPersonne=mysqli_query($bdd,$req);
					$nbPersonne=mysqli_num_rows($resultPersonne);
					echo "<select id='PersonneCopie' name='PersonneCopie' onChange='effacerVacation();'>";
					$Selected = "";
					
					if($_POST){
						$Copie=$_POST['PersonneCopie'];
					}
					if ($nbPersonne > 0){
						echo "<option value='0' selected></option>\n";
						while($row=mysqli_fetch_array($resultPersonne)){
							if ($Copie != "0"){
								if ($row[0] == $Copie){$Selected = "Selected";}
							}
							echo "<option value='".$row['Id']."' ".$Selected.">".$row['Personne']."</option>\n";
							$Selected = "";
						}
					}
					echo "</select>";
					?>
				</td>
			</tr>
			<tr>
				<td height="4"></td>
			</tr>
			<tr>
				<td class="Libelle">
					<?php if($_SESSION["Langue"]=="FR"){echo "Divers :";}else{echo "Diverse :";} ?>
				</td>
				<td colspan="3">
					<textarea name="divers" rows=3 cols=80 resize="none"><?php if($_POST){echo $_POST['divers'];}?></textarea>
				</td>
			</tr>
			<tr>
				<td height="4"></td>
			</tr>
			<tr>
				<td class="Libelle">
					<?php if($_SESSION["Langue"]=="FR"){echo "Commentaire :";}else{echo "Comment :";} ?>
				</td>
				<td colspan="3">
					<textarea name="commentaire" rows=3 cols=80 resize="none"><?php if($_POST){echo $_POST['commentaire'];}?></textarea>
				</td>
			</tr>
			<tr>
				<td height="4"></td>
			</tr>
			<tr>
				<td colspan="4" class="Libelle">
					<input class="Bouton" name="check_JoursFixes" type="checkbox"><?php if($_SESSION["Langue"]=="FR"){echo "Modifier les jours fixes de cette période (jours fériés, RTT, ...)";}else{echo "Modify the fixed days of this period (holidays, RTT, ...)";} ?>
				</td>
			</tr>
			<tr>
				<td height="4"></td>
			</tr>
			<tr>
				<td colspan="10" align="center">
					<div id="ABSENCES" />
					</div>
				</td>
			</tr>
			<tr>
				<td height="4"></td>
			</tr>
			<tr style="display:none;">
				<td colspan="10" align="center">
					<input id="JOURS" value="" />
				</td>
			</tr>
			<tr>
				<td colspan="6" style="color:red;">
					<?php 
						if($_SESSION["Langue"]=="FR"){
							echo "La modification sera faite uniquement sur les jours travaillées dans le contrat";
						}
						else{
							echo "The change will be made only on the days worked in the contract";
						} 
					?>
				</td>
			</tr>
			<tr>
				<td colspan="6" style="color:red;">
					<?php 
						if($_SESSION["Langue"]=="FR"){
							echo "Vous ne pouvez pas modifier la case contenant l’annotation <img width='12px' src='../../Images/RH.png' />. Seul le service RH peut réviser la vacation.";
						}
						else{
							echo "You can not edit the box containing the annotation <img width='12px' src='../../Images/RH.png' />. Only the HR department can review the vacation.";
						} 
					?>
				</td>
			</tr>
			<?php 
				if($Menu==4){
			?>
			<tr height='1' bgcolor="#66AACC"><td colspan="4"></td></tr>
			<tr>
				<td colspan="4" style="color:red;">
				<?php 
					if($_SESSION["Langue"]=="FR"){
						echo "Attention : Si un nombre d'heure est défini ci-dessous, seule cette information sera prise en compte au moment du pointage.";
					}
					else{
						echo "Warning: If a number of hours is defined below, only this information will be taken into account at the time of the clocking.";
					} 
				?>
				</td>
			</tr>
			<tr>
				<td class="Libelle">Nb heures Jour : </td>
				<td><input onKeyUp="nombre(this)" name="NbHeureJour" size="10" type="text" value= ""></td>
			</tr>
			<tr>
				<td class="Libelle">Nb heures Formation : </td>
				<td><input onKeyUp="nombre(this)" name="NbHeureFormation" size="10" type="text" value= ""></td>
			</tr>
			<tr>
				<td class="Libelle">Nb heures Formation payées par ETT : </td>
				<td><input onKeyUp="nombre(this)" name="NbHeureFormationETT" size="10" type="text" value= ""></td>
			</tr>
			<tr>
				<td class="Libelle">Nb heures Equipe Jour : </td>
				<td><input onKeyUp="nombre(this)" name="NbHeureEquipeJour" size="10" type="text" value= ""></td>
			</tr>
			<tr>
				<td class="Libelle">Nb heures Equipe Nuit : </td>
				<td><input onKeyUp="nombre(this)" name="NbHeureEquipeNuit" size="10" type="text" value= ""></td>
			</tr>
			<tr>
				<td class="Libelle">Nb heures Pause : </td>
				<td><input onKeyUp="nombre(this)" name="NbHeurePause" size="10" type="text" value= ""></td>
			</tr>
			<tr height='1' bgcolor="#66AACC"><td colspan="4"></td></tr>
			<?php 
				}
			?>
			<tr>
				<td colspan="4" align="center">
					<input class="Bouton" name="submitEnregistrer" type="submit" onclick="document.getElementById('boutonClick').value='Ajout';" value='<?php if($_SESSION["Langue"]=="FR"){echo "Enregistrer";}else{echo "Save";}?>'>
					<input class="Bouton" name="submitSupprimer" type="submit" onclick="document.getElementById('boutonClick').value='Suppr';" value='<?php if($_SESSION["Langue"]=="FR"){echo "Supprimer";}else{echo "Remove";}?>'>
				</td>
			</tr>
	</table>
	</form>
</body>
<?php
	echo "<script>VerifCongesHeures();</script>";
?>
</html>
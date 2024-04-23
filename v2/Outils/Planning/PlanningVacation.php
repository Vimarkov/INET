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
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script>
		function FermerEtRecharger(Id_Prestation,uneDate,Id_Pole,Tri)
		{
			opener.location.href="Planning.php?Id_Prestation="+Id_Prestation+"&uneDate="+uneDate+"&Id_Pole="+Id_Pole+"&Tri="+Tri;
			window.close();
		}
		function OuvreFenetreAidePlanning()
			{window.open("AidePlanning.php","PageAidePlanning","status=no,menubar=no,width=900,height=450");}
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

if(isset($_POST['submitSupprimer'])){
	//Suppression des anciennes données et ajout des nouvelles
	if ((isset($_POST['Lundi']) || isset($_POST['Mardi']) || isset($_POST['Mercredi']) || isset($_POST['Jeudi']) || isset($_POST['Vendredi']) || isset($_POST['Samedi']) || isset($_POST['Dimanche'])) || ($_POST['DateDebut']==$_POST['DateFin'])){
		if ($NavigOk ==1){
			$tabDateTransfert = explode('-', $_POST['DateDebut']);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateDebutReq = date("Y/m/d", $timestampTransfert);
			$tabDateTransfert = explode('-', $_POST['DateFin']);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateFinReq = date("Y/m/d", $timestampTransfert);
		}
		else{
			$tabDateTransfert = explode('/', $_POST['DateDebut']);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[0], $tabDateTransfert[2]);
			$dateDebutReq = date("Y/m/d", $timestampTransfert);
			
			$tabDateTransfert = explode('/', $_POST['DateFin']);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[0], $tabDateTransfert[2]);
			$dateFinReq = date("Y/m/d", $timestampTransfert);
		}
		
		//Suppression des anciennes valeurs vacation
		$reqDelete = "DELETE FROM new_planning_personne_vacationabsence WHERE Id_Personne='".$_POST['Personne']."' AND Id_Prestation='".$_POST['Prestation']."' ";
		$reqDelete .= "AND DatePlanning>='".$dateDebutReq."' AND DatePlanning<='".$dateFinReq."' ";
		if ((isset($_POST['Lundi']) || isset($_POST['Mardi']) || isset($_POST['Mercredi']) || isset($_POST['Jeudi']) || isset($_POST['Vendredi']) || isset($_POST['Samedi']) || isset($_POST['Dimanche'])) && ($_POST['DateDebut']!=$_POST['DateFin'])){
			$reqDelete .= "AND (";
			if (isset($_POST['Lundi'])){
				$reqDelete .= "DAYOFWEEK(DatePlanning) = 2 OR ";
			}
			if (isset($_POST['Mardi'])){
				$reqDelete .= "DAYOFWEEK(DatePlanning) = 3 OR ";
			}
			if (isset($_POST['Mercredi'])){
				$reqDelete .= "DAYOFWEEK(DatePlanning) = 4 OR ";
			}
			if (isset($_POST['Jeudi'])){
				$reqDelete .= "DAYOFWEEK(DatePlanning) = 5 OR ";
			}
			if (isset($_POST['Vendredi'])){
				$reqDelete .= "DAYOFWEEK(DatePlanning) = 6 OR ";
			}
			if (isset($_POST['Samedi'])){
				$reqDelete .= "DAYOFWEEK(DatePlanning) = 7 OR ";
			}
			if (isset($_POST['Dimanche'])){
				$reqDelete .= "DAYOFWEEK(DatePlanning) = 1 OR ";
			}
			$reqDelete = substr($reqDelete,0,-3);
			$reqDelete .= ")";
		}
		
		//Suppression des anciennes valeurs formation
		$reqDeleteFor = "DELETE FROM new_planning_personne_formation WHERE Id_Personne='".$_POST['Personne']."' AND Id_Prestation='".$_POST['Prestation']."' ";
		$reqDeleteFor .= "AND DateFormation>='".$dateDebutReq."' AND DateFormation<='".$dateFinReq."' ";
		if ((isset($_POST['Lundi']) || isset($_POST['Mardi']) || isset($_POST['Mercredi']) || isset($_POST['Jeudi']) || isset($_POST['Vendredi']) || isset($_POST['Samedi']) || isset($_POST['Dimanche'])) && ($_POST['DateDebut']!=$_POST['DateFin'])){
			$reqDeleteFor .= "AND (";
			if (isset($_POST['Lundi'])){
				$reqDeleteFor .= "DAYOFWEEK(DatePlanning) = 2 OR ";
			}
			if (isset($_POST['Mardi'])){
				$reqDeleteFor .= "DAYOFWEEK(DatePlanning) = 3 OR ";
			}
			if (isset($_POST['Mercredi'])){
				$reqDeleteFor .= "DAYOFWEEK(DatePlanning) = 4 OR ";
			}
			if (isset($_POST['Jeudi'])){
				$reqDeleteFor .= "DAYOFWEEK(DatePlanning) = 5 OR ";
			}
			if (isset($_POST['Vendredi'])){
				$reqDeleteFor .= "DAYOFWEEK(DatePlanning) = 6 OR ";
			}
			if (isset($_POST['Samedi'])){
				$reqDeleteFor .= "DAYOFWEEK(DatePlanning) = 7 OR ";
			}
			if (isset($_POST['Dimanche'])){
				$reqDeleteFor .= "DAYOFWEEK(DatePlanning) = 1 OR ";
			}
			$reqDeleteFor = substr($reqDeleteFor,0,-3);
			$reqDeleteFor .= ")";
		}
		
		$resultSupp=mysqli_query($bdd,$reqDelete);
		$resultSupp=mysqli_query($bdd,$reqDeleteFor);
			
		//Fermeture de la fenêtre et rechargement de la fenetre planning
		echo "<script>FermerEtRecharger('".$_POST['Prestation']."','".$_POST['dateARenvoyer']."','".$_POST['Pole']."','".$_POST['Tri']."');</script>";
	}
	else{
		echo"<script language=\"javascript\">alert('Veuillez cocher au moins un jour de la semaine')</script>";	
	}
 }
 
if(isset($_POST['submitEnregistrer'])){
	//Suppression des anciennes données et ajout des nouvelles
	if ((isset($_POST['Lundi']) || isset($_POST['Mardi']) || isset($_POST['Mercredi']) || isset($_POST['Jeudi']) || isset($_POST['Vendredi']) || isset($_POST['Samedi']) || isset($_POST['Dimanche'])) || ($_POST['DateDebut']==$_POST['DateFin'])){
		if ($NavigOk ==1){
			$tabDateTransfert = explode('-', $_POST['DateDebut']);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateDebutReq = date("Y/m/d", $timestampTransfert);
			$tabDateTransfert = explode('-', $_POST['DateFin']);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
			$dateFinReq = date("Y/m/d", $timestampTransfert);
		}
		else{
			$tabDateTransfert = explode('/', $_POST['DateDebut']);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[0], $tabDateTransfert[2]);
			$dateDebutReq = date("Y/m/d", $timestampTransfert);
			
			$tabDateTransfert = explode('/', $_POST['DateFin']);
			$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[0], $tabDateTransfert[2]);
			$dateFinReq = date("Y/m/d", $timestampTransfert);
		}
		
		//Suppression des anciennes valeurs vacation
		$reqDelete = "DELETE FROM new_planning_personne_vacationabsence WHERE Id_Personne='".$_POST['Personne']."' AND Id_Prestation='".$_POST['Prestation']."' ";
		$reqDelete .= "AND DatePlanning>='".$dateDebutReq."' AND DatePlanning<='".$dateFinReq."' ";
		if ((isset($_POST['Lundi']) || isset($_POST['Mardi']) || isset($_POST['Mercredi']) || isset($_POST['Jeudi']) || isset($_POST['Vendredi']) || isset($_POST['Samedi']) || isset($_POST['Dimanche'])) && ($_POST['DateDebut']!=$_POST['DateFin'])){
			$reqDelete .= "AND (";
			if (isset($_POST['Lundi'])){
				$reqDelete .= "DAYOFWEEK(DatePlanning) = 2 OR ";
			}
			if (isset($_POST['Mardi'])){
				$reqDelete .= "DAYOFWEEK(DatePlanning) = 3 OR ";
			}
			if (isset($_POST['Mercredi'])){
				$reqDelete .= "DAYOFWEEK(DatePlanning) = 4 OR ";
			}
			if (isset($_POST['Jeudi'])){
				$reqDelete .= "DAYOFWEEK(DatePlanning) = 5 OR ";
			}
			if (isset($_POST['Vendredi'])){
				$reqDelete .= "DAYOFWEEK(DatePlanning) = 6 OR ";
			}
			if (isset($_POST['Samedi'])){
				$reqDelete .= "DAYOFWEEK(DatePlanning) = 7 OR ";
			}
			if (isset($_POST['Dimanche'])){
				$reqDelete .= "DAYOFWEEK(DatePlanning) = 1 OR ";
			}
			$reqDelete = substr($reqDelete,0,-3);
			$reqDelete .= ")";
		}
		
		//Suppression des anciennes valeurs formation
		$reqDeleteFor = "DELETE FROM new_planning_personne_formation WHERE ID_Personne='".$_POST['Personne']."' AND Id_prestation='".$_POST['Prestation']."' ";
		$reqDeleteFor .= "AND DateFormation>='".$dateDebutReq."' AND DateFormation<='".$dateFinReq."' ";
		if ((isset($_POST['Lundi']) || isset($_POST['Mardi']) || isset($_POST['Mercredi']) || isset($_POST['Jeudi']) || isset($_POST['Vendredi']) || isset($_POST['Samedi']) || isset($_POST['Dimanche'])) && ($_POST['DateDebut']!=$_POST['DateFin'])){
			$reqDeleteFor .= "AND (";
			if (isset($_POST['Lundi'])){
				$reqDeleteFor .= "DAYOFWEEK(DateFormation) = 2 OR ";
			}
			if (isset($_POST['Mardi'])){
				$reqDeleteFor .= "DAYOFWEEK(DateFormation) = 3 OR ";
			}
			if (isset($_POST['Mercredi'])){
				$reqDeleteFor .= "DAYOFWEEK(DateFormation) = 4 OR ";
			}
			if (isset($_POST['Jeudi'])){
				$reqDeleteFor .= "DAYOFWEEK(DateFormation) = 5 OR ";
			}
			if (isset($_POST['Vendredi'])){
				$reqDeleteFor .= "DAYOFWEEK(DateFormation) = 6 OR ";
			}
			if (isset($_POST['Samedi'])){
				$reqDeleteFor .= "DAYOFWEEK(DateFormation) = 7 OR ";
			}
			if (isset($_POST['Dimanche'])){
				$reqDeleteFor .= "DAYOFWEEK(DateFormation) = 1 OR ";
			}
			$reqDeleteFor = substr($reqDeleteFor,0,-3);
			$reqDeleteFor .= ")";
		}
		
		//Ajout des nouvelles données vacation
		$requeteInsert="INSERT INTO new_planning_personne_vacationabsence (Id_Personne, Id_VacationAbsence, Id_Prestation, Id_Pole, DatePlanning, Id_Responsable, DateModification, Commentaire, PassageInfirmerieSansArret, Divers)";
		$requeteInsert.=" VALUES ";
		
		//Ajout des nouvelles données formation
		$requeteInsertFor="INSERT INTO new_planning_personne_formation (ID_Personne, DateFormation, NbHeureVacation, NbHeureHorsVacation, Id_prestation, NomFormation) VALUES ";
		
		$VacJES = "";
		
		$nbCopie=0;
		if(isset($_POST['PersonneCopie'])){
			if ($_POST['PersonneCopie'] <> "0"){
				//Recherche des vacations de la copie
				$reqCopie = "SELECT new_planning_personne_vacationabsence.Id_VacationAbsence, new_planning_personne_vacationabsence.DatePlanning FROM new_planning_personne_vacationabsence WHERE ";
				$reqCopie .= "Id_Personne=".$_POST['PersonneCopie']." AND DatePlanning>='".$dateDebutReq."' AND DatePlanning<='".$dateFinReq."' ;";
				$resultCopie=mysqli_query($bdd,$reqCopie);
				$nbCopie=mysqli_num_rows($resultCopie);
			}
		}
		
		$tableau = array();
		$tableau2 = array();
		while ($dateDebutReq <= $dateFinReq){
			//Vérifier que ce jour peut être ajouté
			$reqVerif = "SELECT new_planning_personne_vacationabsence.Id_Prestation FROM new_planning_personne_vacationabsence WHERE ";
			$reqVerif .= "Id_Personne='".$_POST['Personne']."' AND DatePlanning='".$dateDebutReq."';";
			$resultPlanning=mysqli_query($bdd,$reqVerif);
			$nbPlanning=mysqli_num_rows($resultPlanning);
			$OK = true;
			$tableau[]=$dateDebutReq;
			$tabDate = explode('/', $dateDebutReq);
			$tmp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
			$tableau2[] = date("Y-m-d",$tmp);
			if ($nbPlanning > 0){
				$rowPlanning=mysqli_fetch_array($resultPlanning);
				if($rowPlanning[0] == $_POST['Prestation']){
					$OK = true;
				}
				else{
					$OK = false;
				}
			}
			else{
				$OK = true;
			}
			
			if ($OK == true){
				if (isset($_POST['PersonneCopie']) && $_POST['PersonneCopie'] <> "0"){
					$Vacation = "";
					$tabDate = explode('/', $dateDebutReq);
					$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
					$dateCompare = date("Y-m-d", $timestamp);
					if ($nbCopie > 0){
						mysqli_data_seek($resultCopie,0);
						while($rowCopie=mysqli_fetch_array($resultCopie)){
							if($rowCopie['DatePlanning'] == $dateCompare){
								$Vacation = $rowCopie['Id_VacationAbsence'];
							}
						}
					}
					$tabDate = explode('/', $dateDebutReq);
					$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
					$jour = date('w', $timestamp);
					if ($Vacation <> ""){
						if (((isset($_POST['Lundi']) && $jour==1) || (isset($_POST['Mardi']) && $jour==2) || (isset($_POST['Mercredi']) && $jour==3) || (isset($_POST['Jeudi']) && $jour==4) || (isset($_POST['Vendredi']) && $jour==5) || (isset($_POST['Samedi']) && $jour==6) || (isset($_POST['Dimanche']) && $jour==0)) || ($_POST['DateDebut']==$_POST['DateFin'])){
							$requeteInsert.="(".$_POST['Personne'].",'".$Vacation."',".$_POST['Prestation'].",".$_POST['Pole'].",'".$dateDebutReq."',".$_SESSION['Id_Personne']."";
							$requeteInsert.=",'".Date("Y/m/d")."','".addslashes($_POST['Commentaire'])."',".$_POST['PassageInfirmerie'].",'".addslashes($_POST['Divers'])."')";
							$requeteInsert.=",";
							if(($_POST['NbHeureFormationVacation'] <> "" && $_POST['NbHeureFormationVacation'] <> 0) || ($_POST['NbHeureFormationHorsVacation'] <> "" && $_POST['NbHeureFormationHorsVacation'] <> 0)){
								$NomFormation = "";
								$NbForVac = 0;
								$NbForHorsVac = 0;
								if($_POST['NbHeureFormationVacation'] <> ""){$NbForVac = $_POST['NbHeureFormationVacation'];}
								if($_POST['NbHeureFormationHorsVacation'] <> ""){$NbForHorsVac = $_POST['NbHeureFormationHorsVacation'];}
								if($_POST['NomFormation'] <> ""){$NomFormation = $_POST['NomFormation'];}
								$requeteInsertFor.="(".$_POST['Personne'].",'".$dateDebutReq."',".$NbForVac."";
								$requeteInsertFor.=",'".$NbForHorsVac."','".$_POST['Prestation']."','".addslashes($NomFormation)."')";
								$requeteInsertFor.=",";
							}
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
								$VacJES = "3";
							}
						}
						else{
							//Jour précedent
							$tabDate = explode('/', $dateDebutReq);
							$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]-1, $tabDate[0]);
							$weekJourPrecedent = date("W", $timestamp);
							
							//Jour en cours
							$tabDate = explode('/', $dateDebutReq);
							$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
							$weekDuJour = date("W", $timestamp);
							if ($VacJES == "1"){
								if ($weekJourPrecedent <> $weekDuJour){$VacJES = "3";}
							}
							else{
								if ($weekJourPrecedent <> $weekDuJour){$VacJES = "1";}
							}
						}
					}
					if ($VacJES == ""){
						$VacJES = $_POST['vacationAbsence'];
					}
					$tabDate = explode('/', $dateDebutReq);
					$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
					$jour = date('w', $timestamp);
					if ((((isset($_POST['Lundi']) && $jour==1) || (isset($_POST['Mardi']) && $jour==2) || (isset($_POST['Mercredi']) && $jour==3) || (isset($_POST['Jeudi']) && $jour==4) || (isset($_POST['Vendredi']) && $jour==5) || (isset($_POST['Samedi']) && $jour==6) || (isset($_POST['Dimanche']) && $jour==0)) || (isset($_POST['Samedi']) && $jour==6) || (isset($_POST['Dimanche']) && $jour==0)) || ($_POST['DateDebut']==$_POST['DateFin'])){
						$requeteInsert.="(".$_POST['Personne'].",'".$VacJES."',".$_POST['Prestation'].",".$_POST['Pole'].",'".$dateDebutReq."',".$_SESSION['Id_Personne']."";
						$requeteInsert.=",'".Date("Y/m/d")."','".addslashes($_POST['Commentaire'])."',".$_POST['PassageInfirmerie'].",'".addslashes($_POST['Divers'])."')";
						$requeteInsert.=",";
						
						if(($_POST['NbHeureFormationVacation'] <> "" && $_POST['NbHeureFormationVacation'] <> 0) || ($_POST['NbHeureFormationHorsVacation'] <> "" && $_POST['NbHeureFormationHorsVacation'] <> 0)){
							$NomFormation = "";
							$NbForVac = 0;
							$NbForHorsVac = 0;
							if($_POST['NbHeureFormationVacation'] <> ""){$NbForVac = $_POST['NbHeureFormationVacation'];}
							if($_POST['NbHeureFormationHorsVacation'] <> ""){$NbForHorsVac = $_POST['NbHeureFormationHorsVacation'];}
							if($_POST['NomFormation'] <> ""){$NomFormation = $_POST['NomFormation'];}
							$requeteInsertFor.="(".$_POST['Personne'].",'".$dateDebutReq."',".$NbForVac."";
							$requeteInsertFor.=",'".$NbForHorsVac."','".$_POST['Prestation']."','".addslashes($NomFormation)."')";
							$requeteInsertFor.=",";
						}
					}
				}
			}
			//Jour suivant
			$tabDate = explode('/', $dateDebutReq);
			$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2]+1, $tabDate[0]);
			$dateDebutReq = date("Y/m/d", $timestamp);
		}
		$requeteInsert =substr($requeteInsert, 0, -1)."" ;
		$requeteInsertFor =substr($requeteInsertFor, 0, -1)."" ;

		$resultSupp=mysqli_query($bdd,$reqDelete);
		$resultSupp=mysqli_query($bdd,$reqDeleteFor);
		$resultAjout=mysqli_query($bdd,$requeteInsert);
		if($requeteInsertFor<>"INSERT INTO new_planning_personne_formation (ID_Personne, DateFormation, NbHeureVacation, NbHeureHorsVacation, Id_prestation, NomFormation) VALUES"){
			$resultAjout=mysqli_query($bdd,$requeteInsertFor);
		}
		
		//Si formation hors vacation -> Proposer la création d'heures supplémentaires de jour 
		if($_POST['NbHeureFormationHorsVacation'] <> "" && $_POST['NbHeureFormationHorsVacation'] > 0 ){
			$reqPersonne="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$_POST['Personne'];
			$result=mysqli_query($bdd,$reqPersonne);
			$LignePersonne=mysqli_fetch_array($result);
			$reqPresta="SELECT Libelle FROM new_competences_prestation WHERE Id=".$_POST['Prestation'];
			$result=mysqli_query($bdd,$reqPresta);
			$LignePrestation=mysqli_fetch_array($result);
			$nomPersonne = $LignePersonne['Nom']." ".$LignePersonne['Prenom'];
			$nomPrestation = $LignePrestation['Libelle'];
			$tabString=implode(",", $tableau);
			$tab2String=implode(",", $tableau2);
			$NomFormation = addslashes($_POST['NomFormation']);
			echo "<script>DemandeCreationHS('".$_POST['NbHeureFormationHorsVacation']."','".$_POST['Prestation']."','".$_POST['Personne']."','".$tabString."','".$nomPrestation."','".$nomPersonne."','".$_SESSION['Id_Personne']."','".$tab2String."','".$NomFormation."','".$_POST['dateARenvoyer']."','".$_POST['Pole']."','".$_POST['Tri']."');</script>";
		}
		//Fermeture de la fenêtre et rechargement de la fenetre planning
		echo "<script>FermerEtRecharger('".$_POST['Prestation']."','".$_POST['dateARenvoyer']."','".$_POST['Pole']."','".$_POST['Tri']."');</script>";
	}
	else{
		echo"<script language=\"javascript\">alert('Veuillez cocher au moins un jour de la semaine')</script>";	
	}
 }

if($_POST){
	if ($NavigOk ==1){
		$tabDateTransfert = explode('-', $_POST['DateDebut']);
		$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
		$dateDebutReq = date("Y/m/d", $timestampTransfert);
		$tabDateTransfert = explode('-', $_POST['DateFin']);
		$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[2], $tabDateTransfert[0]);
		$dateFinReq = date("Y/m/d", $timestampTransfert);
	}
	else{
		$tabDateTransfert = explode('/', $_POST['DateDebut']);
		$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[0], $tabDateTransfert[2]);
		$dateDebutReq = date("Y/m/d", $timestampTransfert);
		
		$tabDateTransfert = explode('/', $_POST['DateFin']);
		$timestampTransfert = mktime(0, 0, 0, $tabDateTransfert[1], $tabDateTransfert[0], $tabDateTransfert[2]);
		$dateFinReq = date("Y/m/d", $timestampTransfert);
	}
	$laDateDebut = $_POST['DateDebut'];
	$laDateFin = $_POST['DateFin'];
	$dateJour = "";
	$IdPrestation = $_POST['Prestation'];
	$IdPole = $_POST['Pole'];
	$IdPersonne=$_POST['Personne'];
	$laDateARenvoyer = $_POST['dateARenvoyer'];
	$Tri = $_POST['Tri'];
	$IdVacation=$_POST['vacationAbsence'];
	$Copie=0;
	if(isset($_POST['PersonneCopie'])){
		$Copie=$_POST['PersonneCopie'];
	}
	$PassageInf=$_POST['PassageInfirmerie'];
	$CommentaireForm=$_POST['Commentaire'];
	$NomForm=$_POST['NomFormation'];
	$NbHForVac=$_POST['NbHeureFormationVacation'];
	$NbHForHorsVac=$_POST['NbHeureFormationHorsVacation'];
	$DiversForm=$_POST['Divers'];
}
elseif($_GET){
	$dateDebutReq = date("Y/m/d", $_GET['lDate']);
	$dateFinReq = date("Y/m/d", $_GET['lDate']);			
	if ($NavigOk ==1){
		$laDateDebut = date("Y-m-d", $_GET['lDate']);
		$laDateFin = date("Y-m-d", $_GET['lDate']);
	}
	else{
		$laDateDebut = date("d/m/Y", $_GET['lDate']);
		$laDateFin = date("d/m/Y", $_GET['lDate']);
	}
	$dateDebutReq = date("Y/m/d",  $_GET['lDate']);
	$dateFinReq = date("Y/m/d", $_GET['lDate']);
	$IdPrestation = $_GET['Id_Prestation'];
	$IdPole = $_GET['Id_Pole'];
	$IdPersonne= $_GET['Id_Personne'];
	$IdRepeter = "0";
	$laDateARenvoyer = $_GET['lDateEnvoi'];
	$Tri = $_GET['Tri'];
	$IdVacation="0";
	$Copie="0";
	$PassageInf="0";
	$CommentaireForm="";
	$NomForm="";
	$NbHForVac="";
	$NbHForHorsVac="";
	$DiversForm="";
}

//La date de fin ne peut pas être inferieur à la date de début
if($dateDebutReq > $dateFinReq){
	$dateFinReq = $dateDebutReq;
	$laDateFin = $laDateDebut;
}

//Absences / Vacations
$reqAbsVac = "SELECT new_planning_vacationabsence.Id ,new_planning_vacationabsence.Nom ,new_planning_vacationabsence.Description ";
$reqAbsVac .= "FROM new_planning_vacationabsence ORDER BY new_planning_vacationabsence.Nom ASC; ";

$resultAbsVac=mysqli_query($bdd,$reqAbsVac);
$nbAbsVac=mysqli_num_rows($resultAbsVac);

//Recherche si en formations
$reqFor = "SELECT new_planning_personne_formation.NbHeureVacation, new_planning_personne_formation.NbHeureHorsVacation, new_planning_personne_formation.NomFormation ";
$reqFor .= "FROM new_planning_personne_formation ";
$reqFor .= "WHERE new_planning_personne_formation.Id_Personne =".$IdPersonne." ";
$reqFor .= "AND new_planning_personne_formation.DateFormation ='".$dateDebutReq."' AND new_planning_personne_formation.DateFormation ='".$dateFinReq."';";

$formationJour=mysqli_query($bdd,$reqFor);
$nbformationJour=mysqli_num_rows($formationJour);

$NomFormation = "";
$NbForVac = "";
$NbForHorsVac = "";
if ($nbformationJour>0){
	$rowformationJour=mysqli_fetch_array($formationJour);
	$NbForVac = $rowformationJour[0];
	$NbForHorsVac = $rowformationJour[1];
	$NomFormation = $rowformationJour['NomFormation'];
}

//Recherche si planning
$reqPla = "SELECT 
	new_planning_vacationabsence.Nom, 
	new_planning_vacationabsence.Couleur, 
	new_planning_personne_vacationabsence.Commentaire, 
	new_planning_vacationabsence.Id, 
	new_planning_personne_vacationabsence.ID_Prestation, 
	new_planning_personne_vacationabsence.PassageInfirmerieSansArret, 
	new_planning_personne_vacationabsence.Divers 
	FROM new_planning_personne_vacationabsence 
	LEFT JOIN new_planning_vacationabsence 
	ON new_planning_personne_vacationabsence.ID_VacationAbsence = new_planning_vacationabsence.Id 
	WHERE new_planning_personne_vacationabsence.Id_Personne =".$IdPersonne." 
	AND new_planning_personne_vacationabsence.DatePlanning='".$dateDebutReq."' 
	AND new_planning_personne_vacationabsence.DatePlanning='".$dateFinReq."';";

$vacationJour=mysqli_query($bdd,$reqPla);
$nbVacationJour=mysqli_num_rows($vacationJour);

$Id_VacationJour = "";
$Commentaire = "";
$Divers = "";
$Id_PrestationJour = "";
if ($nbVacationJour==1){
	$rowVacationJour=mysqli_fetch_array($vacationJour);
	$Id_VacationJour = $rowVacationJour['Id'];
	$Commentaire = $rowVacationJour['Commentaire'];
	$Id_PrestationJour = $rowVacationJour['ID_Prestation'];
	$Divers = $rowVacationJour['Divers'];
}
else{
	$Id_VacationJour = 0;
	$Commentaire = "";
	$Id_PrestationJour = "";
	$Divers = "";
}

$affichage = "";
if ($dateDebutReq == $dateFinReq){
	$affichage = "style='display:none;'";
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
 <form  id="formulaire" method="post" action="PlanningVacation.php" onsubmit=" return selectall();">
	<table class="TableCompetences" width=100%>
			<tr style="display:none;">
				<td><input type="text" name="Prestation" size="11" value="<?php echo $IdPrestation; ?>"></td>
				<td><input type="text" name="Pole" size="11" value="<?php echo $IdPole; ?>"></td>
				<td><input type="text" name="dateARenvoyer" size="11" value="<?php echo $laDateARenvoyer; ?>"></td>
				<td><input type="text" name="Tri" size="11" value="<?php echo $Tri; ?>"></td>
				<td><input type="text" name="IdVacation" size="11" value="<?php echo $IdVacation; ?>"></td>
				<td><input type="text" name="Copie" size="11" value="<?php echo $Copie; ?>"></td>
				<td><input type="text" name="PassageInf" size="11" value="<?php echo $PassageInf; ?>"></td>
				<td><input type="text" name="CommentaireForm" size="11" value="<?php echo $CommentaireForm; ?>"></td>
				<td><input type="text" name="NomForm" size="11" value="<?php echo $NomForm; ?>"></td>
				<td><input type="text" name="NbHForVac" size="11" value="<?php echo $NbHForVac; ?>"></td>
				<td><input type="text" name="NbHForHorsVac" size="11" value="<?php echo $NbHForHorsVac; ?>"></td>
				<td><input type="text" name="DiversForm" size="11" value="<?php echo $DiversForm; ?>"></td>
			</tr>
			<tr>
				<td><h3>Vacation / Absence</h3></td>
				<td></td>
				<td></td>
				<td align="left">
				</td>
			</tr>
			<tr>
				<td>Personnes :</td>
				<td>
					<?php
					//Personnes  présentent sur cette prestation à ces dates
					$req = "SELECT DISTINCT(new_competences_personne_prestation.Id_Personne) AS Id_Personne, ";
					$req .= "CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne ";
					$req .= "FROM (new_competences_personne_prestation RIGHT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id = new_competences_personne_prestation.Id_Personne) ";
					$req .= "LEFT JOIN new_competences_personne_metier ON new_competences_personne_metier.Id_Personne = new_rh_etatcivil.Id ";
					$req .= "WHERE new_competences_personne_prestation.Id_Prestation =".$IdPrestation." ";
					if ($IdPole > 0){$req .= "AND new_competences_personne_prestation.Id_Pole =".$IdPole." ";}
					$req .= "AND ((new_competences_personne_prestation.Date_Debut<='".$dateDebutReq."' AND new_competences_personne_prestation.Date_Fin>='".$dateDebutReq."') ";
					$req .= "OR (new_competences_personne_prestation.Date_Debut<='".$dateFinReq."' AND new_competences_personne_prestation.Date_Fin>='".$dateFinReq."') ";
					$req .= "OR (new_competences_personne_prestation.Date_Debut>='".$dateDebutReq."' AND new_competences_personne_prestation.Date_Fin<='".$dateFinReq."')) ";
					$req .= "ORDER BY Personne ASC;";
					
					$resultPersonne=mysqli_query($bdd,$req);
					$nbPersonne=mysqli_num_rows($resultPersonne);
					echo "<select name='Personne' onchange='submit();'>";
					if ($nbPersonne > 0){
						while($row=mysqli_fetch_array($resultPersonne))
						{
							if ($IdPersonne == $row['Id_Personne']){
								echo "<option value='".$row['Id_Personne']."' selected>".$row['Personne']."</option>\n";
							}
							else{
								echo "<option value='".$row['Id_Personne']."'>".$row['Personne']."</option>\n";
							}
						}
					}
					echo "</select>";
					?>
				</td>
			</tr>
			<tr>
				<td>
					Date de début :
				</td>
				<td>
					<input type="date" style="text-align:center;" name="DateDebut" size="11" onchange="submit();" value="<?php echo $laDateDebut; ?>">
				</td>
				<td>
					Date de fin :
				</td>
				<td>
					<input type="date" style="text-align:center;" name="DateFin" size="11" onchange="submit();" value="<?php echo $laDateFin; ?>">
				</td>
			</tr>
			<tr <?php echo $affichage; ?>>
				<td colspan="4">
					Jour de semaine travaillé : 
					<INPUT type="checkbox" name="Lundi" value="1" checked> Lundi
					<INPUT type="checkbox" name="Mardi" value="2" checked> Mardi
					<INPUT type="checkbox" name="Mercredi" value="3" checked> Mercredi
					<INPUT type="checkbox" name="Jeudi" value="4" checked> Jeudi
					<INPUT type="checkbox" name="Vendredi" value="5" checked> Vendredi
					<INPUT type="checkbox" name="Samedi" value="6"> Samedi
					<INPUT type="checkbox" name="Dimanche" value="0"> Dimanche
				</td>
			</tr>
			<tr>
				<td>Vacation / absence :
				</td>
				<td>
					<select id="vacationAbsence" name="vacationAbsence" onchange="effacerCopie();">
					<?php
					
					if ($nbAbsVac > 0){
						$Selected = "";
						while($rowAbsVac=mysqli_fetch_array($resultAbsVac))
						{	
							if ($IdVacation != "0"){
								if ($rowAbsVac[0] == $IdVacation){$Selected = "Selected";}
							}
							else{
								if ($rowAbsVac[0] == $Id_VacationJour){$Selected = "Selected";}
							}
							echo "<option name='".$rowAbsVac[0]."' value='".$rowAbsVac[0]."' ".$Selected.">".$rowAbsVac[1]." | ".$rowAbsVac[2]."</option>";
							$Selected = "";
						}
					}
					?>
						<option name='-1' value='-1'>J / ES | Alterner semaines</option>
						<option name='-2' value='-2'>ES / J | Alterner semaines</option>
					</select>
				</td>
				<td>ou copie des vacations de :</td>
				<td>
					<?php
					//Personnes  présentent sur cette prestation à ces dates
					$req = "SELECT DISTINCT(new_competences_personne_prestation.Id_Personne) AS Id_Personne, ";
					$req .= "CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne ";
					$req .= "FROM (new_competences_personne_prestation RIGHT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id = new_competences_personne_prestation.Id_Personne) ";
					$req .= "LEFT JOIN new_competences_personne_metier ON new_competences_personne_metier.Id_Personne = new_rh_etatcivil.Id ";
					$req .= "WHERE new_competences_personne_prestation.Id_Prestation =".$IdPrestation." ";
					if ($IdPole > 0){$req .= "AND new_competences_personne_prestation.Id_Pole =".$IdPole." ";}
					$req .= "AND ((new_competences_personne_prestation.Date_Debut<='".$dateDebutReq."' AND new_competences_personne_prestation.Date_Fin>='".$dateDebutReq."') ";
					$req .= "OR (new_competences_personne_prestation.Date_Debut<='".$dateFinReq."' AND new_competences_personne_prestation.Date_Fin>='".$dateFinReq."') ";
					$req .= "OR (new_competences_personne_prestation.Date_Debut>='".$dateDebutReq."' AND new_competences_personne_prestation.Date_Fin<='".$dateFinReq."')) ";
					$req .= "AND new_competences_personne_prestation.Id_Personne <> ".$IdPersonne." ";
					$req .= "ORDER BY Personne ASC;";
					
					$resultPersonne=mysqli_query($bdd,$req);
					$nbPersonne=mysqli_num_rows($resultPersonne);
					echo "<select id='PersonneCopie' name='PersonneCopie' onChange='effacerVacation();'>";
					$Selected = "";
					if ($nbPersonne > 0){
						if ($Copie == "0"){
							echo "<option value='0' Selected></option>\n";
						}
						while($row=mysqli_fetch_array($resultPersonne)){
							if ($Copie != "0"){
								if ($row[0] == $Copie){$Selected = "Selected";}
							}
							echo "<option value='".$row['Id_Personne']."' ".$Selected.">".$row['Personne']."</option>\n";
							$Selected = "";
						}
					}
					echo "</select>";
					?>
				</td>
			</tr>
			<tr>
				<td>Passage à l'infirmerie sans arrêt de travail :
				</td>
				<td>
					<select name="PassageInfirmerie" onchange=";">
					<?php
						if ($PassageInf == "0"){
							if ($rowVacationJour['PassageInfirmerieSansArret'] == 0){
								echo "<option name='0' value='0' Selected>Non</option>";
								echo "<option name='1' value='1' >Oui</option>";
							}
							else{
								echo "<option name='0' value='0'>Non</option>";
								echo "<option name='1' value='1' Selected>Oui</option>";
							}
						}
						else{
							if ($rowVacationJour['PassageInfirmerieSansArret'] == $PassageInf){
								echo "<option name='0' value='0' Selected>Non</option>";
								echo "<option name='1' value='1' >Oui</option>";
							}
							else{
								echo "<option name='0' value='0'>Non</option>";
								echo "<option name='1' value='1' Selected>Oui</option>";
							}
						}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					Commentaire :
				</td>
				<td colspan="3">
					<textarea name="Commentaire" rows=3 cols=50 resize="none"><?php if ($CommentaireForm == ""){echo $Commentaire;} else{echo $CommentaireForm;}?></textarea>
				</td>
			</tr>
			<tr>
				<td>
					Divers :
				</td>
				<td colspan="3">
					<textarea name="Divers" rows=3 cols=50 resize="none"><?php if ($DiversForm == ""){echo $Divers;} else{echo $DiversForm;}?></textarea>
				</td>
			</tr>
			<?php
				//La Personne  présentent sur cette prestation à ces dates
				$reqPers = "SELECT new_competences_personne_prestation.Id FROM new_competences_personne_prestation ";
				$reqPers .= "WHERE new_competences_personne_prestation.Id_Prestation =".$IdPrestation." AND new_competences_personne_prestation.Id_Personne =".$IdPersonne." ";
				$reqPers .= "AND (new_competences_personne_prestation.Date_Debut<='".$dateDebutReq."' AND new_competences_personne_prestation.Date_Fin>='".$dateFinReq."');";
				
				$resultLaPersonne=mysqli_query($bdd,$reqPers);
				$nbLaPersonne=mysqli_num_rows($resultLaPersonne);
				
				$reqPlaAutre = "SELECT new_planning_vacationabsence.Id ";
				$reqPlaAutre .= "FROM new_planning_personne_vacationabsence LEFT JOIN new_planning_vacationabsence ON new_planning_personne_vacationabsence.ID_VacationAbsence = new_planning_vacationabsence.Id ";
				$reqPlaAutre .= "WHERE new_planning_personne_vacationabsence.Id_Personne =".$IdPersonne."";
				$reqPlaAutre .= " AND new_planning_personne_vacationabsence.Id_Prestation<>".$IdPrestation." ";
				$reqPlaAutre .= "AND new_planning_personne_vacationabsence.DatePlanning>='".$dateDebutReq."' AND new_planning_personne_vacationabsence.DatePlanning<='".$dateFinReq."';";

				$PlaAutre=mysqli_query($bdd,$reqPlaAutre);
				$nbPlaAutre=mysqli_num_rows($PlaAutre);
				
				if($nbLaPersonne == 0 || $nbPlaAutre>0){
					echo "<tr><td colspan='4' style='color:red;'>Attention : Cette personne n'est pas affectée à cette prestation ";
					echo "(via le profil des compétences) pour certaines des dates <br/>souhaitées ou a déjà un planning complété par une autre prestation.<br/>Les modifications seront faites ";
					echo "uniquement sur les jours où la personne est affectée à la prestation et sans planning <br/>déjà réalisé sur une autre prestation</td></tr>";
				}
			?>
			<tr height='1' bgcolor="#66AACC"><td colspan="4"></td></tr>
			<tr>
				<td><h3>Formation</h3></td>
			</tr>
			<tr>
				<td>Nom de la formation : </td>
				<td><input name="NomFormation" size="100" type="text" value= "<?php if($NomForm == ""){echo $NomFormation;} else{echo $NomForm;} ?>"></td>
			</tr>
			<tr>
				<td>Nombre d'heures par jour pendant la vacation : </td>
				<td><input onKeyUp="nombre(this)" name="NbHeureFormationVacation" size="10" type="text" value= "<?php if($NbHForVac == ""){echo $NbForVac;} else{echo $NbHForVac;} ?>"></td>
			</tr>
			<tr>
				<td>Nombre d'heures par jour hors vacation : </td>
				<td><input onKeyUp="nombre(this)" name="NbHeureFormationHorsVacation" size="10" type="text" value= "<?php if($NbHForHorsVac == ""){echo $NbForHorsVac;} else{echo $NbHForHorsVac;} ?>"></td>
			</tr>
			<?php
				//Liste des formations faites ce jour là
				$req="SELECT DISTINCT form_session_date.Id_Session, form_session_date.DateSession,form_session_date.Heure_Debut, form_session_date.Heure_Fin, form_session_date.PauseRepas, 
					form_session_date.HeureDebutPause, form_session_date.HeureFinPause, form_session.Id_Formation,
					(SELECT Id_Langue FROM form_formation_plateforme_parametres 
						WHERE form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
						AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
						AND Suppr=0 LIMIT 1) AS Id_Langue,
					form_session.Recyclage,
					(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
						WHERE form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
						AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
						AND Suppr=0 LIMIT 1) AS Organisme
					FROM form_session_date 
					LEFT JOIN form_session 
					ON form_session_date.Id_Session=form_session.Id
					WHERE form_session_date.Suppr=0 
					AND form_session.Suppr=0
					AND form_session.Annule=0 
					AND form_session_date.DateSession>='".TrsfDate_($laDateDebut)."'
					AND form_session_date.DateSession<='".TrsfDate_($laDateFin)."'
					AND (
						SELECT COUNT(form_session_personne.Id) 
						FROM form_session_personne
						WHERE form_session_personne.Suppr=0
						AND form_session_personne.Id_Personne=".$IdPersonne." 
						AND form_session_personne.Validation_Inscription IN (1) 
						AND form_session_personne.Id_Session=form_session.Id
						AND form_session_personne.Presence IN (1,-2)
						)>0 
					ORDER BY Id_Session ";
				$resultSession=mysqli_query($bdd,$req);
				$nbSession=mysqli_num_rows($resultSession);
				if($nbSession>0){
					$reqLangue="SELECT Libelle, LibelleRecyclage, Id_Formation, Id_Langue  
								FROM form_formation_langue_infos 
								WHERE Suppr=0";
					$resultFormLangue=mysqli_query($bdd,$reqLangue);
					$nbFormLangue=mysqli_num_rows($resultFormLangue);
					
					echo "<tr>";
						echo "<td colspan='5'><img src='../../Images/attention.png' style='cursor: default;' width='20px' border='0' alt='Attention' title='Attention'>Pour informations : ";
					echo "Cette personne a été en formation les jours suivants : </td>";
					echo "</tr>";	
						$Id_Session="";
						while($rowSession=mysqli_fetch_array($resultSession)){
							$heures=substr($rowSession['Heure_Debut'],0,5)." - ";
							if($rowSession['PauseRepas']==1){
								if($rowSession['Heure_Fin']>$rowSession['HeureFinPause']){
									$heures.=substr($rowSession['HeureDebutPause'],0,5)." | ".substr($rowSession['HeureFinPause'],0,5)." ";
								}
							}
							$heures.=substr($rowSession['Heure_Fin'],0,5);
							
							$Libelle="";
							if($nbFormLangue>0){
								mysqli_data_seek($resultFormLangue,0);
								while($rowFormLangue=mysqli_fetch_array($resultFormLangue)){
									if($rowFormLangue['Id_Formation']==$rowSession['Id_Formation'] && $rowFormLangue['Id_Langue']==$rowSession['Id_Langue'] ){
										if($rowSession['Recyclage']==1){
											$Libelle=stripslashes($rowFormLangue['Libelle']);
										}
										else{
											$Libelle=stripslashes($rowFormLangue['LibelleRecyclage']);
											if($Libelle==""){
												$Libelle=stripslashes($rowFormLangue['Libelle']);
											}
										}
										if($rowSession['Organisme']<>""){$Libelle.=" (".$rowSession['Organisme'].")";}
										
									}
								}
							}
							$Presence="";
							if($Id_Session<>$rowSession['Id_Session']){
								$reqSemiPre="SELECT SemiPresence 
								FROM form_session_personne
								WHERE form_session_personne.Suppr=0
								AND form_session_personne.Id_Personne=".$IdPersonne." 
								AND form_session_personne.Validation_Inscription=1 
								AND form_session_personne.Id_Session=".$rowSession['Id_Session']."
								AND Presence=-2 ";
								$resultSemiPresence=mysqli_query($bdd,$reqSemiPre);
								$nbSemiPresence=mysqli_num_rows($resultSemiPresence);
								if($nbSemiPresence>0){
									$rowSemiPresence=mysqli_fetch_array($resultSemiPresence);
									$Presence=" (Semi-présence : ".substr($rowSemiPresence['SemiPresence'],0,5)." )";
								}
								echo "<tr>";
									echo "<td colspan='5'>".$Libelle.$Presence."</td>";
								echo "</tr>";
								$Id_Session=$rowSession['Id_Session'];						
							}
							echo "<tr>";
								echo "<td colspan='5'>&nbsp;&bull;".AfficheDateFR($rowSession['DateSession'])." : ".$heures."</td>";
							echo "</tr>";	
						}
					echo "<tr>";
						echo "<td colspan='5'>Veuillez à répartir les heures de formations pendant et hors vacation </td>";
					echo "</tr>";
				}
				
				$req="SELECT DISTINCT form_session_date.Id_Session, form_session_date.DateSession,form_session_date.Heure_Debut, form_session_date.Heure_Fin, form_session_date.PauseRepas, 
					form_session_date.HeureDebutPause, form_session_date.HeureFinPause, form_session.Id_Formation,
					(SELECT Id_Langue FROM form_formation_plateforme_parametres 
						WHERE form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
						AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
						AND Suppr=0 LIMIT 1) AS Id_Langue,
					form_session.Recyclage,
					(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
						WHERE form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
						AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
						AND Suppr=0 LIMIT 1) AS Organisme
					FROM form_session_date 
					LEFT JOIN form_session 
					ON form_session_date.Id_Session=form_session.Id
					WHERE form_session_date.Suppr=0 
					AND form_session.Suppr=0
					AND form_session.Annule=0 
					AND form_session_date.DateSession>='".TrsfDate_($laDateDebut)."'
					AND form_session_date.DateSession<='".TrsfDate_($laDateFin)."'
					AND (
						SELECT COUNT(form_session_personne.Id) 
						FROM form_session_personne
						WHERE form_session_personne.Suppr=0
						AND form_session_personne.Id_Personne=".$IdPersonne." 
						AND form_session_personne.Validation_Inscription IN (0,1) 
						AND form_session_personne.Id_Session=form_session.Id
						AND form_session_personne.Presence IN (0)
						)>0 
					ORDER BY Id_Session ";
				$resultSession=mysqli_query($bdd,$req);
				$nbSession=mysqli_num_rows($resultSession);
				if($nbSession>0){
					$reqLangue="SELECT Libelle, LibelleRecyclage, Id_Formation, Id_Langue  
								FROM form_formation_langue_infos 
								WHERE Suppr=0";
					$resultFormLangue=mysqli_query($bdd,$reqLangue);
					$nbFormLangue=mysqli_num_rows($resultFormLangue);
					
					echo "<tr>";
						echo "<td colspan='5'><img src='../../Images/attention.png' style='cursor: default;' width='20px' border='0' alt='Attention' title='Attention'>Pour informations : ";
					echo "Cette personne a été inscrite en formation les jours suivants : </td>";
					echo "</tr>";	
						$Id_Session="";
						while($rowSession=mysqli_fetch_array($resultSession)){
							$heures=substr($rowSession['Heure_Debut'],0,5)." - ";
							if($rowSession['PauseRepas']==1){
								if($rowSession['Heure_Fin']>$rowSession['HeureFinPause']){
									$heures.=substr($rowSession['HeureDebutPause'],0,5)." | ".substr($rowSession['HeureFinPause'],0,5)." ";
								}
							}
							$heures.=substr($rowSession['Heure_Fin'],0,5);
							
							$Libelle="";
							if($nbFormLangue>0){
								mysqli_data_seek($resultFormLangue,0);
								while($rowFormLangue=mysqli_fetch_array($resultFormLangue)){
									if($rowFormLangue['Id_Formation']==$rowSession['Id_Formation'] && $rowFormLangue['Id_Langue']==$rowSession['Id_Langue'] ){
										if($rowSession['Recyclage']==1){
											$Libelle=stripslashes($rowFormLangue['Libelle']);
										}
										else{
											$Libelle=stripslashes($rowFormLangue['LibelleRecyclage']);
											if($Libelle==""){
												$Libelle=stripslashes($rowFormLangue['Libelle']);
											}
										}
										if($rowSession['Organisme']<>""){$Libelle.=" (".$rowSession['Organisme'].")";}
										
									}
								}
							}
							$Presence="";
							if($Id_Session<>$rowSession['Id_Session']){
								$reqSemiPre="SELECT SemiPresence 
								FROM form_session_personne
								WHERE form_session_personne.Suppr=0
								AND form_session_personne.Id_Personne=".$IdPersonne." 
								AND form_session_personne.Validation_Inscription=1 
								AND form_session_personne.Id_Session=".$rowSession['Id_Session']."
								AND Presence=-2 ";
								$resultSemiPresence=mysqli_query($bdd,$reqSemiPre);
								$nbSemiPresence=mysqli_num_rows($resultSemiPresence);
								if($nbSemiPresence>0){
									$rowSemiPresence=mysqli_fetch_array($resultSemiPresence);
									$Presence=" (Semi-présence : ".substr($rowSemiPresence['SemiPresence'],0,5)." )";
								}
								echo "<tr>";
									echo "<td colspan='5'>".$Libelle.$Presence."</td>";
								echo "</tr>";
								$Id_Session=$rowSession['Id_Session'];						
							}
							echo "<tr>";
								echo "<td colspan='5'>&nbsp;&bull;".AfficheDateFR($rowSession['DateSession'])." : ".$heures."</td>";
							echo "</tr>";	
						}
					echo "<tr>";
						echo "<td colspan='5'>Veuillez à répartir les heures de formations pendant et hors vacation </td>";
					echo "</tr>";
				}
			?>
			<tr height='1' bgcolor="#66AACC"><td colspan="4"></td></tr>
			<tr>
				<td colspan="4" align="center">
					<input class="Bouton" name="submitEnregistrer" type="submit" value='Enregistrer'>
					<input class="Bouton" name="submitSupprimer" type="submit" value='Supprimer'>
				</td>
			</tr>
	</table>
	</form>
</body>
</html>
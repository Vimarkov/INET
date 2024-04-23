<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="DODM.js"></script>
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
		function Affiche_Heure(check){
			if(check==1){
				var elements = document.getElementsByClassName('heures');
				for (i=0; i<elements.length; i++){
				  elements[i].style.display='none';
				}
				document.getElementById('heureDebut').value="";
				document.getElementById('heureFin').value="";
			}
			else{
				var elements = document.getElementsByClassName('heures');
				for (i=0; i<elements.length; i++){
				  elements[i].style.display='';
				}
			}
			if(document.getElementById('journeeEntiere').checked==false){
				formulaire.dateFin.value=formulaire.dateDebut.value;
			}
		}
		$(document).ready(function () {
				$('#heureDebut').timepicker({
					minuteStep: 1,
					template: 'modal',
					appendWidgetTo: 'body',
					showSeconds: false,
					showMeridian: false,
					defaultTime: false
				});
				Mask.newMask({ 
					$el: $('#heureDebut'), 
					mask: 'HH:mm' 
				});
				$('#heureFin').timepicker({
					minuteStep: 1,
					template: 'modal',
					appendWidgetTo: 'body',
					showSeconds: false,
					showMeridian: false,
					defaultTime: false
				});
				Mask.newMask({ 
					$el: $('#heureFin'), 
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
require("Fonctions_Planning.php");
Ecrire_Code_JS_Init_Date();

$DateJour=date("Y-m-d");
$bEnregistrement=false;
$Message="";
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

if($_POST){
	if($Menu==3){
		$montant=0;
		$avance=0;
		$periode="";
		if($_POST['demandeAvance']==1){
			if($_POST['montant']<>""){$montant=$_POST['montant'];}
			$avance=$_POST['avance'];
			$periode=$_POST['periode'];
		}
		
		$heureD="00:00:00";
		$heureF="00:00:00";
		if($_POST['heureDebut']<>""){$heureD=$_POST['heureDebut'];}
		if($_POST['heureFin']<>""){$heureF=$_POST['heureFin'];}
		
		$requete="UPDATE rh_personne_petitdeplacement 
				SET Lieu='".addslashes($_POST['lieu'])."',
				ObjetDeplacement='".addslashes($_POST['objectDeplacement'])."',
				FraisReel=".$_POST['typeDeFrais'].",
				DateDebut='".TrsfDate_($_POST['dateDebut'])."',
				DateFin='".TrsfDate_($_POST['dateFin'])."',
				HeureDebut='".$heureD."',
				HeureFin='".$heureF."',
				Montant=".$montant.",
				AvancePonctuelle=".$avance.",
				Periode='".TrsfDate_($_POST['periode'])."',
				DatePriseEnCompteAvance='0001-01-01',
				Id_Avance=0,
				DatePriseEnCompteN1='0001-01-01',
				Id_N1=0
				WHERE Id=".$_POST['Id'];
		$result=mysqli_query($bdd,$requete);

		//Besoins de réservation 
		$req="SELECT Id, Libelle FROM rh_typebesoin WHERE Suppr=0";
		$resultTB=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($resultTB);
		if ($nbResulta>0){
			while($row=mysqli_fetch_array($resultTB)){
				if(isset($_POST['CheckBesoin_'.$row['Id']])){
					$TypeTrajet="";
					$LieuDepartAller="";
					$LieuArriveeAller="";
					$DateDepartAller="0001-01-01";
					$HeureDepartAller="00:00:00";
					$HeureArriveeAller="00:00:00";
					$LieuDepartRetour="";
					$LieuArriveeRetour="";
					$DateDepartRetour="0001-01-01";
					$HeureDepartRetour="00:00:00";
					$HeureArriveeRetour="00:00:00";
					$VehiculeAAA="";
					$DateDebutVehiculeAAA="0001-01-01";
					$DateFinVehiculeAAA="0001-01-01";
					$HeureDebutVehiculeAAA="00:00:00";
					$HeureFinVehiculeAAA="00:00:00";
					$ConducteurLocationVoiture="";
					$LieuDebutLocationVoiture="";
					$DateDebutLocationVoiture="0001-01-01";
					$HeureDebutLocationVoiture="00:00:00";
					$LieuFinLocationVoiture="";
					$DateFinLocationVoiture="0001-01-01";
					$HeureFinLocationVoiture="00:00:00";
					$NbNuitHotel=0;
					$LieuHotel="";
					$DateArriveeHotel="0001-01-01";
					$DateDepartHotel="0001-01-01";
					if($row['Id']==2){
						$NbNuitHotel=unNombreSinon0_($_POST['besoinNbNuitHotel_']);
						$LieuHotel=addslashes($_POST['besoinLieuHotel_']);
						$DateArriveeHotel=TrsfDate_($_POST['besoinDateArriveeHotel_']);
						$DateDepartHotel=TrsfDate_($_POST['besoinDateDepartHotel_']);
					}
					elseif($row['Id']==3){
						$TypeTrajet=$_POST['besoinTrainAvion_'];
						$LieuDepartAller=addslashes($_POST['besoinLieuDepartAller_']);
						$LieuArriveeAller=addslashes($_POST['besoinLieuArriveeAller_']);
						$DateDepartAller=TrsfDate_($_POST['besoinDateDepartAller_']);
						if($_POST['besoinHeureDepartAller_']<>""){$HeureDepartAller=$_POST['besoinHeureDepartAller_'];}
						if($_POST['besoinHeureArriveeAller_']<>""){$HeureArriveeAller=$_POST['besoinHeureArriveeAller_'];}
						$LieuDepartRetour=addslashes($_POST['besoinLieuDepartRetour_']);
						$LieuArriveeRetour=addslashes($_POST['besoinLieuArriveeRetour_']);
						$DateDepartRetour=TrsfDate_($_POST['besoinDateArriveeRetour_']);
						if($_POST['besoinHeureDepartRetour_']<>""){$HeureDepartRetour=$_POST['besoinHeureDepartRetour_'];}
						if($_POST['besoinHeureArriveeRetour_']<>""){$HeureArriveeRetour=$_POST['besoinHeureArriveeRetour_'];}
					}
					elseif($row['Id']==4){
						$ConducteurLocationVoiture=addslashes($_POST['besoinNomConducteur_']);
						$LieuDebutLocationVoiture=addslashes($_POST['besoinLieuDepartLocationVoiture_']);
						$DateDebutLocationVoiture=TrsfDate_($_POST['besoinDateDepartLocationVoiture_']);
						if($_POST['besoinHeureDepartLocationVoiture_']<>""){$HeureDebutLocationVoiture=$_POST['besoinHeureDepartLocationVoiture_'];}
						$LieuFinLocationVoiture=addslashes($_POST['besoinLieuRetourLocationVoiture_']);
						$DateFinLocationVoiture=TrsfDate_($_POST['besoinDateRetourLocationVoiture_']);
						if($_POST['besoinHeureRetourLocationVoiture_']<>""){$HeureFinLocationVoiture=$_POST['besoinHeureRetourLocationVoiture_'];}
					}
					elseif($row['Id']==5){
						$VehiculeAAA=addslashes($_POST['besoinVehiculeAAA_']);
						$DateDebutVehiculeAAA=TrsfDate_($_POST['besoinDateDebutLocationAAA_']);
						$DateFinVehiculeAAA=TrsfDate_($_POST['besoinDateFinLocationAAA_']);
						if($_POST['besoinHeureDebutLocationAAA_']<>""){$HeureDebutVehiculeAAA=$_POST['besoinHeureDebutLocationAAA_'];}
						if($_POST['besoinHeureFinLocationAAA_']<>""){$HeureFinVehiculeAAA=$_POST['besoinHeureFinLocationAAA_'];}
					}
					
					$req="SELECT 
						(SELECT Libelle FROM rh_typebesoin WHERE rh_typebesoin.Id=Id_TypeBesoin) AS TypeBesoin,
						(SELECT ServiceConcerne FROM rh_typebesoin WHERE rh_typebesoin.Id=Id_TypeBesoin) AS ServiceConcerne,
						ValidationService,Commentaire,
						TypeTrajet,LieuDepartAller,LieuArriveeAller,DateDepartAller,HeureDepartAller,HeureArriveeAller,LieuDepartRetour,LieuArriveeRetour,DateDepartRetour,HeureDepartRetour,HeureArriveeRetour,
							VehiculeAAA,DateDebutVehiculeAAA,DateFinVehiculeAAA,HeureDebutVehiculeAAA,HeureFinVehiculeAAA,
							ConducteurLocationVoiture,LieuDebutLocationVoiture,DateDebutLocationVoiture,HeureDebutLocationVoiture,LieuFinLocationVoiture,DateFinLocationVoiture,HeureFinLocationVoiture,
							NbNuitHotel,LieuHotel,DateArriveeHotel,DateDepartHotel
						FROM rh_personne_petitdeplacement_typebesoin 
						WHERE Id_Personne_PetitDeplacement=".$_POST['Id']."
						AND Id_TypeBesoin=".$row['Id']." ";
					$resultPersBesoins=mysqli_query($bdd,$req);
					$nbPersBesoins=mysqli_num_rows($resultPersBesoins);
					if($nbPersBesoins>0){
						$requete="UPDATE rh_personne_petitdeplacement_typebesoin 
							SET Suppr=0,
							DateSuppr='0001-01-01',
							Id_Suppr=0,
							ValidationService=0,
							Id_Validateur=0,
							Commentaire='".addslashes($_POST['besoin_'.$row['Id']])."',
							TypeTrajet='".$TypeTrajet."',
							LieuDepartAller='".$LieuDepartAller."',
							LieuArriveeAller='".$LieuArriveeAller."',
							DateDepartAller='".$DateDepartAller."',
							HeureDepartAller='".$HeureDepartAller."',
							HeureArriveeAller='".$HeureArriveeAller."',
							LieuDepartRetour='".$LieuDepartRetour."',
							LieuArriveeRetour='".$LieuArriveeRetour."',
							DateDepartRetour='".$DateDepartRetour."',
							HeureDepartRetour='".$HeureDepartRetour."',
							HeureArriveeRetour='".$HeureArriveeRetour."',
							VehiculeAAA='".$VehiculeAAA."',
							DateDebutVehiculeAAA='".$DateDebutVehiculeAAA."',
							DateFinVehiculeAAA='".$DateFinVehiculeAAA."',
							HeureDebutVehiculeAAA='".$HeureDebutVehiculeAAA."',
							HeureFinVehiculeAAA='".$HeureFinVehiculeAAA."',
							ConducteurLocationVoiture='".$ConducteurLocationVoiture."',
							LieuDebutLocationVoiture='".$LieuDebutLocationVoiture."',
							DateDebutLocationVoiture='".$DateDebutLocationVoiture."',
							HeureDebutLocationVoiture='".$HeureDebutLocationVoiture."',
							LieuFinLocationVoiture='".$LieuFinLocationVoiture."',
							DateFinLocationVoiture='".$DateFinLocationVoiture."',
							HeureFinLocationVoiture='".$HeureFinLocationVoiture."',
							NbNuitHotel=".$NbNuitHotel.",
							LieuHotel='".$LieuHotel."',
							DateArriveeHotel='".$DateArriveeHotel."',
							DateDepartHotel='".$DateDepartHotel."'
							WHERE Id_Personne_PetitDeplacement=".$_POST['Id']."
							AND Id_TypeBesoin=".$row['Id']." ";
							
						$result=mysqli_query($bdd,$requete);
						echo $requete;
					}
					else{
						$req="INSERT INTO rh_personne_petitdeplacement_typebesoin (Id_Personne_PetitDeplacement,Id_TypeBesoin,Commentaire,
						TypeTrajet,LieuDepartAller,LieuArriveeAller,DateDepartAller,HeureDepartAller,HeureArriveeAller,LieuDepartRetour,LieuArriveeRetour,DateDepartRetour,HeureDepartRetour,HeureArriveeRetour,
							VehiculeAAA,DateDebutVehiculeAAA,DateFinVehiculeAAA,HeureDebutVehiculeAAA,HeureFinVehiculeAAA,
							ConducteurLocationVoiture,LieuDebutLocationVoiture,DateDebutLocationVoiture,HeureDebutLocationVoiture,LieuFinLocationVoiture,DateFinLocationVoiture,HeureFinLocationVoiture,
							NbNuitHotel,LieuHotel,DateArriveeHotel,DateDepartHotel)
						VALUES (".$_POST['Id'].",".$row['Id'].",'".addslashes($_POST['besoin_'.$row['Id']])."',
							'".$TypeTrajet."',
							'".$LieuDepartAller."',
							'".$LieuArriveeAller."',
							'".$DateDepartAller."',
							'".$HeureDepartAller."',
							'".$HeureArriveeAller."',
							'".$LieuDepartRetour."',
							'".$LieuArriveeRetour."',
							'".$DateDepartRetour."',
							'".$HeureDepartRetour."',
							'".$HeureArriveeRetour."',
							'".$VehiculeAAA."',
							'".$DateDebutVehiculeAAA."',
							'".$DateFinVehiculeAAA."',
							'".$HeureDebutVehiculeAAA."',
							'".$HeureFinVehiculeAAA."',
							'".$ConducteurLocationVoiture."',
							'".$LieuDebutLocationVoiture."',
							'".$DateDebutLocationVoiture."',
							'".$HeureDebutLocationVoiture."',
							'".$LieuFinLocationVoiture."',
							'".$DateFinLocationVoiture."',
							'".$HeureFinLocationVoiture."',
							".$NbNuitHotel.",
							'".$LieuHotel."',
							'".$DateArriveeHotel."',
							'".$DateDepartHotel."') ";
						$resultAdd=mysqli_query($bdd,$req);
					}
					
				}
				else{
					$requete="UPDATE rh_personne_petitdeplacement_typebesoin 
							SET Suppr=1,
							DateSuppr='".date('Y-m-d')."',
							Id_Suppr=".$_SESSION['Id_Personne']."
							WHERE Id_Personne_PetitDeplacement=".$_POST['Id']."
							AND Id_TypeBesoin=".$row['Id']." ";
					$result=mysqli_query($bdd,$requete);
				}
			}
		}
		
		//Envoyer un mail pour informer d'une modification de petit déplacement ponctuel 
			$requete2="SELECT Id,Id_Prestation,Id_Pole,
				(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Prestation,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_petitdeplacement.Id_Personne) AS Personne 
				FROM rh_personne_petitdeplacement
				WHERE Id=".$_POST['Id'];

			$result=mysqli_query($bdd,$requete2);
			$rowDODM=mysqli_fetch_array($result);
			
		if($_SESSION['Langue']=="FR"){
			$sujet="Mise à jour de la déclaration de petit déplacement ponctuel - n°".$_POST['Id']." - ".$rowDODM['Personne']." - ".$rowDODM['Prestation'];
			$message_html="	<html>
				<head><title>".$sujet."</title></head>
				<body>
					Bonjour,
					<br>
					La déclaration de petit déplacement ponctuel n°".$_POST['Id']." a été modifiée pour ".$rowDODM['Personne']."
					<br>
					Veuillez vous rendre sur l'Extranet pour prendre en compte cette déclaration.
					<br>
					<br>
					Bonne journée,<br>
					L'Extranet Daher industriel services DIS.
				</body>
			</html>";
		}
		else{
			$sujet="Update of the declaration of small temporary displacement - n°".$_POST['Id']." - ".$rowDODM['Personne']." - ".$rowDODM['Prestation'];
			$message_html="	<html>
				<head><title>".$sujet."</title></head>
				<body>
					Bonjour,
					<br>
					The statement of small punctual displacement n°".$IdCree." has been modified to ".$rowDODM['Personne']."
					<br>
					Please visit the Extranet to take this statement into account.
					<br>
					<br>
					Have a good day,<br>
					Extranet Daher industriel services DIS.
				</body>
			</html>";
		}
		
	$req="SELECT Id_Plateforme
	FROM new_competences_prestation  
	WHERE new_competences_prestation.Id=".$rowDODM['Id_Prestation'];
	$resultPresta=mysqli_query($bdd,$req);
	$nbPresta=mysqli_num_rows($resultPresta);
	if($nbPresta>0){
		$rowPresta=mysqli_fetch_array($resultPresta);
		$Emails="";
		//Resp RH + Assistante RH + Service admin
		$reqMail="SELECT DISTINCT EmailPro 
				FROM new_competences_personne_poste_plateforme 
				LEFT JOIN new_rh_etatcivil
				ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
				WHERE new_competences_personne_poste_plateforme.Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.",".$IdPosteAssistantAdministratif.")
				AND Id_Plateforme=".$rowPresta['Id_Plateforme']." ";
		$ResultMail=mysqli_query($bdd,$reqMail);
		$NbMail=mysqli_num_rows($ResultMail);
		if($NbMail>0){
			while($RowMail=mysqli_fetch_array($ResultMail)){
				if($RowMail['EmailPro']<>""){$Emails.=$RowMail['EmailPro'].",";}
			}
			
		}
		
		//Vérifier si les MGX doivent recevoir un  mail 
		$req="SELECT rh_personne_petitdeplacement_typebesoin.Id 
			FROM rh_personne_petitdeplacement_typebesoin 
			LEFT JOIN rh_typebesoin 
			ON rh_personne_petitdeplacement_typebesoin.Id_TypeBesoin=rh_typebesoin.Id 
			WHERE rh_personne_petitdeplacement_typebesoin.Suppr=0 
			AND ServiceConcerne='Moyens généraux' 
			AND Id_Personne_PetitDeplacement=".$rowDODM['Id']." ";
		$resultMGX=mysqli_query($bdd,$req);
		$nbMGX=mysqli_num_rows($resultMGX);
		if($nbMGX>0){
			//MGX
			$reqMail="SELECT DISTINCT EmailPro 
					FROM new_competences_personne_poste_plateforme 
					LEFT JOIN new_rh_etatcivil
					ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
					WHERE new_competences_personne_poste_plateforme.Id_Poste IN (".$IdPosteResponsableMGX.",".$IdPosteGestionnaireMGX.")
					AND Id_Plateforme=".$rowPresta['Id_Plateforme']." ";
			$ResultMail=mysqli_query($bdd,$reqMail);
			$NbMail=mysqli_num_rows($ResultMail);
			if($NbMail>0){
				while($RowMail=mysqli_fetch_array($ResultMail)){
					if($RowMail['EmailPro']<>""){$Emails.=$RowMail['EmailPro'].",";}
				}
				
			}
		}
		
		//Ajout du N+1
		$reqMail="SELECT DISTINCT EmailPro 
				FROM new_competences_personne_poste_prestation
				LEFT JOIN new_rh_etatcivil
				ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
				WHERE new_competences_personne_poste_prestation.Id_Poste IN (2)
				AND Id_Prestation=".$rowDODM['Id_Prestation']." 
				AND Id_Pole=".$rowDODM['Id_Pole']." ";
		$ResultMail=mysqli_query($bdd,$reqMail);
		$NbMail=mysqli_num_rows($ResultMail);
		if($NbMail>0){
			while($RowMail=mysqli_fetch_array($ResultMail)){
				if($RowMail['EmailPro']<>""){$Emails.=$RowMail['EmailPro'].",";}
			}
		}
		
		if($Emails<>""){$Emails=substr($Emails,0,-1);}
		
		//$Emails="pfauge@aaa-aero.com";
		if($Emails<>"")
		{
			$Headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
			$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
		
			if(mail($Emails,$sujet,$message_html,$Headers,'-f extranet@aaa-aero.com')){echo "";}
			else{echo "";}
		}
	}
	}
	elseif($Menu==7 || $Menu==8){
		//MOYENS GENERAUX 
		//Besoins de réservation 
		$req="SELECT Id, Libelle FROM rh_typebesoin WHERE Suppr=0";
		$resultTB=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($resultTB);
		if ($nbResulta>0){
			while($row=mysqli_fetch_array($resultTB)){
				if(isset($_POST['CheckBesoin_'.$row['Id']])){
					$requete="UPDATE rh_personne_petitdeplacement_typebesoin 
							SET ValidationService=1,
							Date_Validation='".date('Y-m-d')."',
							Id_Validateur=".$_SESSION['Id_Personne']."
							WHERE Id_Personne_PetitDeplacement=".$_POST['Id']."
							AND Id_TypeBesoin=".$row['Id']." ";
					$result=mysqli_query($bdd,$requete);
				}
				else{
					$requete="UPDATE rh_personne_petitdeplacement_typebesoin 
							SET ValidationService=0,
							Date_Validation='0001-01-01',
							Id_Validateur=0
							WHERE Id_Personne_PetitDeplacement=".$_POST['Id']."
							AND Id_TypeBesoin=".$row['Id']." ";
					$result=mysqli_query($bdd,$requete);
				}
			}
		}
		if($Menu==8){
			if(isset($_POST['priseEnCompteAvance'])){
				$requete="UPDATE rh_personne_petitdeplacement
							SET DatePriseEnCompteAvance='".date('Y-m-d')."',
							Id_Avance=".$_SESSION['Id_Personne']."
							WHERE Id=".$_POST['Id']." ";
					$result=mysqli_query($bdd,$requete);
			}
			else{
				$requete="UPDATE rh_personne_petitdeplacement
							SET DatePriseEnCompteAvance='0001-01-01',
							Id_Avance=0
							WHERE Id=".$_POST['Id']." ";
					$result=mysqli_query($bdd,$requete);
			}
			
			//Vérifier si l'accueil a validé tous ses points 
			$req="SELECT rh_personne_petitdeplacement_typebesoin.Id 
				FROM rh_personne_petitdeplacement_typebesoin 
				LEFT JOIN rh_typebesoin 
				ON rh_personne_petitdeplacement_typebesoin.Id_TypeBesoin=rh_typebesoin.Id 
				WHERE rh_personne_petitdeplacement_typebesoin.Suppr=0 
				AND ServiceConcerne='Accueil' 
				AND Id_Personne_PetitDeplacement=".$_POST['Id']." ";
			$resultACC=mysqli_query($bdd,$req);
			$nbACC=mysqli_num_rows($resultACC);
			
			$req="SELECT rh_personne_petitdeplacement_typebesoin.Id 
				FROM rh_personne_petitdeplacement_typebesoin 
				LEFT JOIN rh_typebesoin 
				ON rh_personne_petitdeplacement_typebesoin.Id_TypeBesoin=rh_typebesoin.Id 
				WHERE rh_personne_petitdeplacement_typebesoin.Suppr=0 
				AND ServiceConcerne='Accueil' 
				AND ValidationService=1
				AND Id_Personne_PetitDeplacement=".$_POST['Id']." ";
			$resultACC=mysqli_query($bdd,$req);
			$nbACC2=mysqli_num_rows($resultACC);
			
			$requete2="SELECT Id,Id_Prestation,Id_Pole,Montant,(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_petitdeplacement.Id_Personne) AS Personne 
				FROM rh_personne_petitdeplacement
				WHERE Id=".$_POST['Id'];

			$result=mysqli_query($bdd,$requete2);
			$rowDODM=mysqli_fetch_array($result);
			
			if((($nbACC>0 && $nbACC==$nbACC2) || $nbACC==0) && ($rowDODM['Montant']==0 || ($rowDODM['Montant']>0 && isset($_POST['priseEnCompteAvance'])))){
				//Envoyer un mail pour informer d'une modification de petit déplacement ponctuel 
				$requete2="SELECT Id,Id_Prestation,Id_Pole,(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_petitdeplacement.Id_Personne) AS Personne 
					FROM rh_personne_petitdeplacement
					WHERE Id=".$_POST['Id'];

				$result=mysqli_query($bdd,$requete2);
				$rowDODM=mysqli_fetch_array($result);
					
				if($_SESSION['Langue']=="FR"){
					$sujet="Prise en compte par le service Accueil de la déclaration de petit déplacement ponctuel - n°".$_POST['Id']." - ".$rowDODM['Personne'];
					$message_html="	<html>
						<head><title>".$sujet."</title></head>
						<body>
							Bonjour,
							<br>
							La déclaration de petit déplacement ponctuel n°".$_POST['Id']." a été prise en compte par le service Accueil.
							<br>
							Veuillez vous rendre sur l'Extranet pour voir les besoins concernés.
							<br>
							<br>
							Bonne journée,<br>
							L'Extranet Daher industriel services DIS.
						</body>
					</html>";
				}
				else{
					$sujet="Taking into account by the service Home of the declaration of small occasional displacement - n°".$_POST['Id']." - ".$rowDODM['Personne'];
					$message_html="	<html>
						<head><title>".$sujet."</title></head>
						<body>
							Bonjour,
							<br>
							The statement of small punctual displacement No. ".$_POST['Id']." has been taken into account by the service Home.
							<br>
							Please visit the Extranet to see the needs involved.
							<br>
							<br>
							Have a good day,<br>
							Extranet Daher industriel services DIS.
						</body>
					</html>";
				}
				
				$req="SELECT Id_Plateforme
				FROM new_competences_prestation  
				WHERE new_competences_prestation.Id=".$rowDODM['Id_Prestation'];
				$resultPresta=mysqli_query($bdd,$req);
				$nbPresta=mysqli_num_rows($resultPresta);
				if($nbPresta>0){
					$rowPresta=mysqli_fetch_array($resultPresta);
					$Emails="";
					
					//Envoi au chef d'équipe + N+1
					$reqMail="SELECT DISTINCT EmailPro 
							FROM new_competences_personne_poste_prestation
							LEFT JOIN new_rh_etatcivil
							ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
							WHERE new_competences_personne_poste_prestation.Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.")
							AND Id_Prestation=".$rowDODM['Id_Prestation']." 
							AND Id_Pole=".$rowDODM['Id_Pole']." ";
					$ResultMail=mysqli_query($bdd,$reqMail);
					$NbMail=mysqli_num_rows($ResultMail);
					if($NbMail>0){
						while($RowMail=mysqli_fetch_array($ResultMail)){
							if($RowMail['EmailPro']<>""){$Emails.=$RowMail['EmailPro'].",";}
						}
					}
					
					if($Emails<>""){$Emails=substr($Emails,0,-1);}
					
					//$Emails="pfauge@aaa-aero.com";
					if($Emails<>"")
					{
						$Headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
						$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
					
						if(mail($Emails,$sujet,$message_html,$Headers,'-f extranet@aaa-aero.com')){echo "";}
						else{echo "";}
					}
				}
			}
		}
		elseif($Menu==7){
			//Vérifier si les MGX ont validé tous leurs points 
			$req="SELECT rh_personne_petitdeplacement_typebesoin.Id 
				FROM rh_personne_petitdeplacement_typebesoin 
				LEFT JOIN rh_typebesoin 
				ON rh_personne_petitdeplacement_typebesoin.Id_TypeBesoin=rh_typebesoin.Id 
				WHERE rh_personne_petitdeplacement_typebesoin.Suppr=0 
				AND ServiceConcerne='Moyens généraux' 
				AND Id_Personne_PetitDeplacement=".$_POST['Id']." ";
			$resultACC=mysqli_query($bdd,$req);
			$nbACC=mysqli_num_rows($resultACC);
			
			$req="SELECT rh_personne_petitdeplacement_typebesoin.Id 
				FROM rh_personne_petitdeplacement_typebesoin 
				LEFT JOIN rh_typebesoin 
				ON rh_personne_petitdeplacement_typebesoin.Id_TypeBesoin=rh_typebesoin.Id 
				WHERE rh_personne_petitdeplacement_typebesoin.Suppr=0 
				AND ServiceConcerne='Moyens généraux' 
				AND ValidationService=1
				AND Id_Personne_PetitDeplacement=".$_POST['Id']." ";
			$resultACC=mysqli_query($bdd,$req);
			$nbACC2=mysqli_num_rows($resultACC);
			
			$requete2="SELECT Id,Id_Prestation,Id_Pole,Montant,(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_petitdeplacement.Id_Personne) AS Personne 
				FROM rh_personne_petitdeplacement
				WHERE Id=".$_POST['Id'];

			$result=mysqli_query($bdd,$requete2);
			$rowDODM=mysqli_fetch_array($result);
			
			if(($nbACC>0 && $nbACC==$nbACC2) || $nbACC==0){				
				if($_SESSION['Langue']=="FR"){
					$sujet="Prise en compte par le service des moyens généraux de la déclaration de petit déplacement ponctuel - n°".$_POST['Id']." - ".$rowDODM['Personne'];
					$message_html="	<html>
						<head><title>".$sujet."</title></head>
						<body>
							Bonjour,
							<br>
							La déclaration de petit déplacement ponctuel n°".$_POST['Id']." a été prise en compte par le service des moyens généraux.
							<br>
							Veuillez vous rendre sur l'Extranet pour voir les besoins concernés.
							<br>
							<br>
							Bonne journée,<br>
							L'Extranet Daher industriel services DIS.
						</body>
					</html>";
				}
				else{
					$sujet="Taking into account by the service of the general means of the declaration of small occasional displacement - n°".$_POST['Id']." - ".$rowDODM['Personne'];
					$message_html="	<html>
						<head><title>".$sujet."</title></head>
						<body>
							Bonjour,
							<br>
							The statement of small punctual displacement no. ".$_POST['Id']." has been taken into account by the service of the general means.
							<br>
							Please visit the Extranet to see the needs involved.
							<br>
							<br>
							Have a good day,<br>
							Extranet Daher industriel services DIS.
						</body>
					</html>";
				}
				
				$req="SELECT Id_Plateforme
				FROM new_competences_prestation  
				WHERE new_competences_prestation.Id=".$rowDODM['Id_Prestation'];
				$resultPresta=mysqli_query($bdd,$req);
				$nbPresta=mysqli_num_rows($resultPresta);
				if($nbPresta>0){
					$rowPresta=mysqli_fetch_array($resultPresta);
					$Emails="";
					
					//Envoi au chef d'équipe + N+1
					$reqMail="SELECT DISTINCT EmailPro 
							FROM new_competences_personne_poste_prestation
							LEFT JOIN new_rh_etatcivil
							ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
							WHERE new_competences_personne_poste_prestation.Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.")
							AND Id_Prestation=".$rowDODM['Id_Prestation']." 
							AND Id_Pole=".$rowDODM['Id_Pole']." ";
					$ResultMail=mysqli_query($bdd,$reqMail);
					$NbMail=mysqli_num_rows($ResultMail);
					if($NbMail>0){
						while($RowMail=mysqli_fetch_array($ResultMail)){
							if($RowMail['EmailPro']<>""){$Emails.=$RowMail['EmailPro'].",";}
						}
					}
					
					if($Emails<>""){$Emails=substr($Emails,0,-1);}
					
					//$Emails="pfauge@aaa-aero.com";
					if($Emails<>"")
					{
						$Headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
						$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
					
						if(mail($Emails,$sujet,$message_html,$Headers,'-f extranet@aaa-aero.com')){echo "";}
						else{echo "";}
					}
				}
			}
		}
	}
	echo "<script>FermerEtRecharger('".$Menu."')</script>";
}
if($_GET['Mode']=="S"){
	$req="UPDATE rh_personne_petitdeplacement 
		SET 
			Suppr=1,
			DateSuppr='".date('Y-m-d')."',
			Id_Suppr=".$_SESSION['Id_Personne']."
		WHERE 
			Id=".$_GET['Id']."";
	$resultModif=mysqli_query($bdd,$req);

	echo "<script>FermerEtRecharger('".$Menu."')</script>";
}
$req="SELECT rh_personne_petitdeplacement.Id, rh_personne_petitdeplacement.Id_Personne,rh_personne_petitdeplacement.Id_Prestation,rh_personne_petitdeplacement.Id_Pole,ObjetDeplacement,HeureDebut,HeureFin,
rh_personne_petitdeplacement.Id_PrestationDeplacement,rh_personne_petitdeplacement.Id_PoleDeplacement,rh_personne_petitdeplacement.DateCreation,rh_personne_petitdeplacement.Id_Createur,
rh_personne_petitdeplacement.Id_Metier,rh_personne_petitdeplacement.Montant,rh_personne_petitdeplacement.AvancePonctuelle,rh_personne_petitdeplacement.Periode,
rh_personne_petitdeplacement.DatePriseEnCompteRH,rh_personne_petitdeplacement.DateDebut,rh_personne_petitdeplacement.DateFin,
CONCAT((SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation),
	IF(Id_Pole>0,' - ','') ,
	IF(Id_Pole>0,(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_Pole),'')
) AS PrestationDepart,DatePriseEnCompteAvance,
CONCAT((SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_PrestationDeplacement),
	IF(Id_PoleDeplacement>0,' - ','') ,
	IF(Id_PoleDeplacement>0,(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_PoleDeplacement),'')
) AS PrestationDestination,rh_personne_petitdeplacement.FraisReel,rh_personne_petitdeplacement.Lieu,
IF(Montant>0,1,0) AS DemandeAvance,Pays,
(SELECT new_competences_metier.LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=rh_personne_petitdeplacement.Id_Metier) AS MetierEN,
(SELECT new_competences_metier.Libelle FROM new_competences_metier WHERE new_competences_metier.Id=rh_personne_petitdeplacement.Id_Metier) AS Metier,
(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_petitdeplacement.Id_Createur) AS Demandeur,
(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_petitdeplacement.Id_Personne) AS Personne
FROM rh_personne_petitdeplacement
WHERE Id=".$_GET['Id'];
$result=mysqli_query($bdd,$req);
$rowDODM=mysqli_fetch_array($result);

$nonModifiable="readonly";
$typeDate="text";
if($Menu==3){
	$nonModifiable="";
	$typeDate="date";
}
?>

<form id="formulaire" class="test" action="Modif_DODM.php" method="post" onsubmit="<?php if($Menu==3){echo "return VerifChamps();";}?>">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $rowDODM['Id']; ?>" />
	<tr>
		<td colspan="5">
			<table style="width:100%; border-spacing:0;">
				<tr>
					<td class="TitrePage" style="background-color:#a9e99d;">
					<?php 
						if($_SESSION["Langue"]=="FR"){echo "Petit déplacement ponctuel n° ".$rowDODM['Id'];}else{echo "Small punctual displacement n° ".$rowDODM['Id'];}
					?>
					</td>
					<td width="4"></td>
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
					<table class="TableCompetences" align="center" width="100%" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
							<td width="30%">
									<?php echo stripslashes($rowDODM['PrestationDepart']); ?>
							</td>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation destination :";}else{echo "Destination site :";} ?></td>
							<td width="30%">
									<?php echo stripslashes($rowDODM['PrestationDestination']); ?>
							</td>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?></td>
							<td width="35%" valign="top">
								<?php echo stripslashes($rowDODM['Personne']); ?>
							</td>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Demandeur :";}else{echo "Applicant :";} ?></td>
							<td width="35%" valign="top">
								<?php echo stripslashes($rowDODM['Demandeur']); ?>
							</td>

						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Objet du déplacement :";}else{echo "Object of the trip :";} ?> </td>
							<td width="30%" colspan="4">
								<input type="text" id='objectDeplacement' name='objectDeplacement' <?php echo $nonModifiable;	?> size="80" value="<?php echo stripslashes($rowDODM['ObjetDeplacement']); ?>">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Lieu de la mission :";}else{echo "Location of the mission :";} ?> </td>
							<td width="30%">
								<input type="text" id='lieu' name='lieu' <?php echo $nonModifiable;	?> size="40" value="<?php echo stripslashes($rowDODM['Lieu']); ?>">
							</td>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Pays :";}else{echo "Country :";} ?> </td>
							<td width="30%">
								<input type="text" id='pays' name='pays' size="20" value="<?php echo stripslashes($rowDODM['Pays']); ?>">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début :";}else{echo "Start date :";} ?></td>
							<td width="30%"><input type="<?php echo $typeDate;	?>" <?php echo $nonModifiable;	?> id="dateDebut" name="dateDebut" size="10" value="<?php if($Menu==3){echo AfficheDateFR($rowDODM['DateDebut']);}else{echo AfficheDateJJ_MM_AAAA($rowDODM['DateDebut']);} ?>"></td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin :";}else{echo "End date :";} ?></td>
							<td width="30%"><input type="<?php echo $typeDate;	?>" <?php echo $nonModifiable;	?> id="dateFin" name="dateFin" size="10" value="<?php if($Menu==3){echo AfficheDateFR($rowDODM['DateFin']);}else{echo AfficheDateJJ_MM_AAAA($rowDODM['DateFin']);} ?>"></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Journée entière :";}else{echo "Whole day";} ?> </td>
							<td width="10%">
								<input type="radio" id='journeeEntiere' name='journeeEntiere' onclick="Affiche_Heure(1)" value="1" <?php echo $nonModifiable;	?> <?php if($rowDODM['HeureDebut']=="00:00:00" && $rowDODM['HeureFin']=="00:00:00"){echo "checked";} ?> ><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?>&nbsp;&nbsp;
								<input type="radio" id='journeeEntiere' name='journeeEntiere' onclick="Affiche_Heure(0)" value="0" <?php echo $nonModifiable;	?> <?php if($rowDODM['HeureDebut']<>"00:00:00" || $rowDODM['HeureFin']<>"00:00:00"){echo "checked";}?>><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?>&nbsp;&nbsp;
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr class="heures" <?php if($rowDODM['HeureDebut']=="00:00:00" && $rowDODM['HeureFin']=="00:00:00"){echo "style='display:none;'";} ?>>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small" style="text-align:center;" name="heureDebut" id="heureDebut" size="10" type="text" value= "<?php echo $rowDODM['HeureDebut'];?>">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small" style="text-align:center;" name="heureFin" id="heureFin" size="10" type="text" value= "<?php echo $rowDODM['HeureFin'];?>">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="15%" colspan="3" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Besoins de réservation :";}else{echo "Booking needs :";} ?></td>
						</tr>
						<tr>
							<td colspan="6">
								<div id='Div_Besoin' style='width:80%;overflow:auto;background-color:#ddf6d8'>
									<?php
									echo "<table width='100%'>";
									if($_SESSION["Langue"]=="FR"){$req="SELECT Id, Libelle FROM rh_typebesoin WHERE Suppr=0 ORDER BY Libelle";}
									else{$req="SELECT Id, LibelleEN AS Libelle FROM rh_typebesoin WHERE Suppr=0 ORDER BY LibelleEN";}
									$resultB=mysqli_query($bdd,$req);
									$nbResultaB=mysqli_num_rows($resultB);
									if ($nbResultaB>0){
										while($rowB=mysqli_fetch_array($resultB)){
											$req="SELECT 
												(SELECT Libelle FROM rh_typebesoin WHERE rh_typebesoin.Id=Id_TypeBesoin) AS TypeBesoin,
												(SELECT ServiceConcerne FROM rh_typebesoin WHERE rh_typebesoin.Id=Id_TypeBesoin) AS ServiceConcerne,
												ValidationService,Commentaire,
												TypeTrajet,LieuDepartAller,LieuArriveeAller,DateDepartAller,HeureDepartAller,HeureArriveeAller,LieuDepartRetour,LieuArriveeRetour,DateDepartRetour,HeureDepartRetour,HeureArriveeRetour,
												VehiculeAAA,DateDebutVehiculeAAA,DateFinVehiculeAAA,HeureDebutVehiculeAAA,HeureFinVehiculeAAA,
												ConducteurLocationVoiture,LieuDebutLocationVoiture,DateDebutLocationVoiture,HeureDebutLocationVoiture,LieuFinLocationVoiture,DateFinLocationVoiture,HeureFinLocationVoiture,
												NbNuitHotel,LieuHotel,DateArriveeHotel,DateDepartHotel
												FROM rh_personne_petitdeplacement_typebesoin 
												WHERE Suppr=0 
												AND Id_Personne_PetitDeplacement=".$rowDODM['Id']."
												AND Id_TypeBesoin=".$rowB['Id']." ";
											$resultBesoins=mysqli_query($bdd,$req);
											$nbBesoins=mysqli_num_rows($resultBesoins);
											
											$Commentaire="";
											$image="&nbsp;&nbsp;";
											if($nbBesoins>0){
												$rowPersonneB=mysqli_fetch_array($resultBesoins);
												$Commentaire=$rowPersonneB['Commentaire'];
											}
											
											if($Menu==3){
												$checked="";
												$image="&nbsp;&nbsp;";
												if($nbBesoins>0){
													$checked="checked";
													if($rowPersonneB['ValidationService']>0){$checked="checked";$image="<img width='15px' src='../../Images/tick.png' border='0' alt='Check' title='Check'>";}
												}
												echo "<tr><td width='25%'><input type='checkbox' class='besoins' ".$checked." name='CheckBesoin_".$rowB['Id']."' id='CheckBesoin_".$rowB['Id']."' >".$rowB['Libelle']." : </td>";
											}
											else{
												if($nbBesoins>0){
													$checked="";
													$image="&nbsp;&nbsp;";
													if($rowPersonneB['ValidationService']>0){$checked="checked";$image="<img width='15px' src='../../Images/tick.png' border='0' alt='Check' title='Check'>";}
													if($Menu==7){
														if($rowPersonneB['ServiceConcerne']=="Moyens généraux"){
															echo "<tr><td width='5%'><input type='checkbox' class='besoins' ".$checked." name='CheckBesoin_".$rowB['Id']."' id='CheckBesoin_".$rowB['Id']."' ></td><td width='25%'>".$rowB['Libelle']." : </td>";
														}
														else{
															echo "<tr><td width='5%'>".$image."</td><td width='25%'>".$rowB['Libelle']." : </td>";
														}
													}
													elseif($Menu==8){
														if($rowPersonneB['ServiceConcerne']=="Accueil"){
															echo "<tr><td width='5%'><input type='checkbox' class='besoins' ".$checked." name='CheckBesoin_".$rowB['Id']."' id='CheckBesoin_".$rowB['Id']."' ></td><td width='25%'>".$rowB['Libelle']." : </td>";
														}
														else{
															echo "<tr><td width='5%'>".$image."</td><td width='25%'>".$rowB['Libelle']." : </td>";
														}
													}
													elseif($Menu==4){
														echo "<tr><td width='5%'>".$image."</td><td width='25%'>".$rowB['Libelle']." : </td>";
													}
												}
											}
											
											if($Menu==3 || $nbBesoins>0){
												echo "<td width='75%'><table>";
												
												$TypeTrajet="";
												$LieuDepartAller="";
												$LieuArriveeAller="";
												$DateDepartAller="0001-01-01";
												$HeureDepartAller="00:00:00";
												$HeureArriveeAller="00:00:00";
												$LieuDepartRetour="";
												$LieuArriveeRetour="";
												$DateDepartRetour="0001-01-01";
												$HeureDepartRetour="00:00:00";
												$HeureArriveeRetour="00:00:00";
												$VehiculeAAA="";
												$DateDebutVehiculeAAA="0001-01-01";
												$DateFinVehiculeAAA="0001-01-01";
												$HeureDebutVehiculeAAA="00:00:00";
												$HeureFinVehiculeAAA="00:00:00";
												$ConducteurLocationVoiture="";
												$LieuDebutLocationVoiture="";
												$DateDebutLocationVoiture="0001-01-01";
												$HeureDebutLocationVoiture="00:00:00";
												$LieuFinLocationVoiture="";
												$DateFinLocationVoiture="0001-01-01";
												$HeureFinLocationVoiture="00:00:00";
												$NbNuitHotel=0;
												$LieuHotel="";
												$DateArriveeHotel="0001-01-01";
												$DateDepartHotel="0001-01-01";
												if($nbBesoins>0){
													$NbNuitHotel=$rowPersonneB['NbNuitHotel'];
													$LieuHotel=stripslashes($rowPersonneB['LieuHotel']);
													$DateArriveeHotel=AfficheDateFR($rowPersonneB['DateArriveeHotel']);
													$DateDepartHotel=AfficheDateFR($rowPersonneB['DateDepartHotel']);

													$TypeTrajet=$rowPersonneB['TypeTrajet'];
													$LieuDepartAller=stripslashes($rowPersonneB['LieuDepartAller']);
													$LieuArriveeAller=stripslashes($rowPersonneB['LieuArriveeAller']);
													$DateDepartAller=AfficheDateFR($rowPersonneB['DateDepartAller']);
													$HeureDepartAller=$rowPersonneB['HeureDepartAller'];
													$HeureArriveeAller=$rowPersonneB['HeureArriveeAller'];
													$LieuDepartRetour=stripslashes($rowPersonneB['LieuDepartRetour']);
													$LieuArriveeRetour=stripslashes($rowPersonneB['LieuArriveeRetour']);
													$DateDepartRetour=AfficheDateFR($rowPersonneB['DateDepartRetour']);
													$HeureDepartRetour=$rowPersonneB['HeureDepartRetour'];
													$HeureArriveeRetour=$rowPersonneB['HeureArriveeRetour'];

													$ConducteurLocationVoiture=stripslashes($rowPersonneB['ConducteurLocationVoiture']);
													$LieuDebutLocationVoiture=stripslashes($rowPersonneB['LieuDebutLocationVoiture']);
													$DateDebutLocationVoiture=AfficheDateFR($rowPersonneB['DateDebutLocationVoiture']);
													$HeureDebutLocationVoiture=$rowPersonneB['HeureDebutLocationVoiture'];
													$LieuFinLocationVoiture=stripslashes($rowPersonneB['LieuFinLocationVoiture']);
													$DateFinLocationVoiture=AfficheDateFR($rowPersonneB['DateFinLocationVoiture']);
													$HeureFinLocationVoiture=$rowPersonneB['HeureFinLocationVoiture'];

													$VehiculeAAA=stripslashes($rowPersonneB['VehiculeAAA']);
													$DateDebutVehiculeAAA=AfficheDateFR($rowPersonneB['DateDebutVehiculeAAA']);
													$DateFinVehiculeAAA=AfficheDateFR($rowPersonneB['DateFinVehiculeAAA']);
													$HeureDebutVehiculeAAA=$rowPersonneB['HeureDebutVehiculeAAA'];
													$HeureFinVehiculeAAA=$rowPersonneB['HeureFinVehiculeAAA'];
												}
												if($rowB['Id']==2){
													//Réservation hotel
													if($_SESSION['Langue']=="FR"){echo "<tr><td>Nombre de nuit : ";}else{echo "<tr><td>Number of nights : ";}
													echo "<input onKeyUp='nombre(this)' size='5' type='text' name='besoinNbNuitHotel_' id='besoinNbNuitHotel_' value='".$NbNuitHotel."'></td>";
													if($_SESSION['Langue']=="FR"){echo "<td>Lieu : ";}else{echo "<td>Location : ";}
													echo "<input type='text' size='30' name='besoinLieuHotel_' id='besoinLieuHotel_' value='".$LieuHotel."'></td></tr>";
													
													if($_SESSION['Langue']=="FR"){echo "<tr><td>Date arrivée : ";}else{echo "<tr><td>Arrival date : ";}
													echo "<input type='date' name='besoinDateArriveeHotel_' id='besoinDateArriveeHotel_' value='".$DateArriveeHotel."'></td>";
													if($_SESSION['Langue']=="FR"){echo "<td>Date départ : ";}else{echo "<td>Departure date : ";}
													echo "<input type='date' name='besoinDateDepartHotel_' id='besoinDateDepartHotel_' value='".$DateDepartHotel."'></td></tr>";
												}
												elseif($rowB['Id']==3){
													//Réservation train/avion
													$selectedAvion="";
													$selectedTrain="";
													if($TypeTrajet=="Avion"){$selectedAvion="selected";}
													else{$selectedTrain="selected";}
													if($_SESSION['Langue']=="FR"){
														echo "<tr><td>Train/Avion : <select name='besoinTrainAvion_' id='besoinTrainAvion_' ><option value='Avion' ".$selectedAvion.">Avion</option><option value='Train' ".$selectedTrain.">Train</option></select></td></td></tr>";
													}
													else{
														echo "<tr><td>Train/Plane : <select name='besoinTrainAvion_' id='besoinTrainAvion_' ><option value='Avion' ".$selectedAvion.">Plane</option><option value='Train' ".$selectedTrain.">Train</option></select></td></td></tr>";
													}
													if($_SESSION['Langue']=="FR"){echo "<tr><td class='Libelle'>ALLER :</td></tr>";}else{echo "<tr><td class='Libelle'>BACK :</td></tr>";}
													
													if($_SESSION['Langue']=="FR"){echo "<tr><td>Lieu départ : ";}else{echo "<tr><td>Departure location : ";}
													echo "<input type='text' size='30' name='besoinLieuDepartAller_' id='besoinLieuDepartAller_' value='".$LieuDepartAller."'></td>";
													if($_SESSION['Langue']=="FR"){echo "<td>Lieu arrivée : ";}else{echo "<td>Arrival place : ";}
													echo "<input type='text' size='30' name='besoinLieuArriveeAller_' id='besoinLieuArriveeAller_' value='".$LieuArriveeAller."'></td></tr>";
													
													if($_SESSION['Langue']=="FR"){echo "<tr><td>Date départ : ";}else{echo "<tr><td>Departure date : ";}
													echo "<input type='date' name='besoinDateDepartAller_' id='besoinDateDepartAller_' value='".$DateDepartAller."'></td></tr>";
													
													if($_SESSION['Langue']=="FR"){echo "<tr><td>Heure départ : ";}else{echo "<td>Departure hour : ";}
													echo "<div class='input-group bootstrap-timepicker timepicker' style='display: inline'>";
													echo "<input class='form-control input-small' style='text-align:center;' type='text' size='10' name='besoinHeureDepartAller_' id='besoinHeureDepartAller_' value='".$HeureDepartAller."'>";
													echo "</div></td>";
													if($_SESSION['Langue']=="FR"){echo "<td>Heure arrivée : ";}else{echo "<td>Arrival hour : ";}
													echo "<div class='input-group bootstrap-timepicker timepicker' style='display: inline'>";
													echo "<input class='form-control input-small' style='text-align:center;' type='text' size='10' name='besoinHeureArriveeAller_' id='besoinHeureArriveeAller_' value='".$HeureArriveeAller."'>";
													echo "</div></td></tr>";
													
													
													if($_SESSION['Langue']=="FR"){echo "<tr><td class='Libelle'>RETOUR :</td></tr>";}else{echo "<tr><td class='Libelle'>FORTH :</td></tr>";}
													
														if($_SESSION['Langue']=="FR"){echo "<tr><td>Lieu départ : ";}else{echo "<tr><td>Departure location : ";}
													echo "<input type='text' size='30' name='besoinLieuDepartRetour_' id='besoinLieuDepartRetour_' value='".$LieuDepartRetour."'></td>";
													if($_SESSION['Langue']=="FR"){echo "<td>Lieu arrivée : ";}else{echo "<td>Arrival place : ";}
													echo "<input type='text' size='30' name='besoinLieuArriveeRetour_' id='besoinLieuArriveeRetour_' value='".$LieuArriveeRetour."'></td></tr>";
													
													if($_SESSION['Langue']=="FR"){echo "<tr><td>Date départ : ";}else{echo "<tr><td>Departure date : ";}
													echo "<input type='date' name='besoinDateDepartRetour_' id='besoinDateDepartRetour_' value='".$DateDepartRetour."' value='".$DateDepartRetour."'></td></tr>";
													
													if($_SESSION['Langue']=="FR"){echo "<tr><td>Heure départ : ";}else{echo "<td>Departure hour : ";}
													echo "<div class='input-group bootstrap-timepicker timepicker' style='display: inline'>";
													echo "<input class='form-control input-small' style='text-align:center;' type='text' size='10' name='besoinHeureDepartRetour_' id='besoinHeureDepartRetour_' value='".$HeureDepartRetour."'>";
													echo "</div></td>";
													if($_SESSION['Langue']=="FR"){echo "<td>Heure arrivée : ";}else{echo "<td>Arrival hour : ";}
													echo "<div class='input-group bootstrap-timepicker timepicker' style='display: inline'>";
													echo "<input class='form-control input-small' style='text-align:center;' type='text' size='10' name='besoinHeureArriveeRetour_' id='besoinHeureArriveeRetour_' value='".$HeureArriveeRetour."'>";
													echo "</div></td></tr>";
													
												}
												elseif($rowB['Id']==5){
													//Réservation véhicule AAA
													if($_SESSION['Langue']=="FR"){echo "<tr><td>Véhicule : ";}else{echo "<tr><td>Vehicle : ";}
													echo "<input type='text' size='30' name='besoinVehiculeAAA_' id='besoinVehiculeAAA_' value='".$VehiculeAAA."'></td>";
													
													if($_SESSION['Langue']=="FR"){echo "<tr><td>Date début : ";}else{echo "<tr><td>Start date : ";}
													echo "<input type='date' name='besoinDateDebutLocationAAA_' id='besoinDateDebutLocationAAA_' value='".$DateDebutVehiculeAAA."'></td>";
													if($_SESSION['Langue']=="FR"){echo "<td>Date fin : ";}else{echo "<td>End date : ";}
													echo "<input type='date' name='besoinDateFinLocationAAA_' id='besoinDateFinLocationAAA_' value='".$DateFinVehiculeAAA."'></td></tr>";
													
													if($_SESSION['Langue']=="FR"){echo "<tr><td>Heure début : ";}else{echo "<td>Start hour : ";}
													echo "<div class='input-group bootstrap-timepicker timepicker' style='display: inline'>";
													echo "<input class='form-control input-small' style='text-align:center;' type='text' size='10' name='besoinHeureDebutLocationAAA_' id='besoinHeureDebutLocationAAA_' value='".$HeureDebutVehiculeAAA."'>";
													echo "</div></td>";
													if($_SESSION['Langue']=="FR"){echo "<td>Heure fin : ";}else{echo "<td>End hour : ";}
													echo "<div class='input-group bootstrap-timepicker timepicker' style='display: inline'>";
													echo "<input class='form-control input-small' style='text-align:center;' type='text' size='10' name='besoinHeureFinLocationAAA_' id='besoinHeureFinLocationAAA_' value='".$HeureFinVehiculeAAA."'>";
													echo "</div></td></tr>";
												}
												elseif($rowB['Id']==4){
													//Réservation voiture de location
													
													if($_SESSION['Langue']=="FR"){echo "<tr><td>Nom du conducteur : ";}else{echo "<tr><td>Driver's name : ";}
													echo "<input type='text' size='30' name='besoinNomConducteur_' id='besoinNomConducteur_' value='".$ConducteurLocationVoiture."'></td>";
													
													if($_SESSION['Langue']=="FR"){echo "<tr><td class='Libelle'>MISE A DISPOSITION :</td></tr>";}else{echo "<tr><td class='Libelle'>PROVISION :</td></tr>";}
													
													if($_SESSION['Langue']=="FR"){echo "<tr><td>Lieu : ";}else{echo "<tr><td>Location : ";}
													echo "<input type='text' size='30' name='besoinLieuDepartLocationVoiture_' id='besoinLieuDepartLocationVoiture_' value='".$LieuDebutLocationVoiture."'></td></tr>";
													
													if($_SESSION['Langue']=="FR"){echo "<tr><td>Date : ";}else{echo "<tr><td>Date : ";}
													echo "<input type='date' name='besoinDateDepartLocationVoiture_' id='besoinDateDepartLocationVoiture_' value='".$DateDebutLocationVoiture."'></td>";
													if($_SESSION['Langue']=="FR"){echo "<td>Heure : ";}else{echo "<td>Hour : ";}
													echo "<div class='input-group bootstrap-timepicker timepicker' style='display: inline'>";
													echo "<input class='form-control input-small' style='text-align:center;' type='text' size='10' name='besoinHeureDepartLocationVoiture_' id='besoinHeureDepartLocationVoiture_' value='".$HeureDebutLocationVoiture."'>";
													echo "</div></td></tr>";
													
													
													if($_SESSION['Langue']=="FR"){echo "<tr><td class='Libelle'>RESTITUTION :</td></tr>";}else{echo "<tr><td class='Libelle'>RETURN :</td></tr>";}
													
														if($_SESSION['Langue']=="FR"){echo "<tr><td>Lieu : ";}else{echo "<tr><td>Location : ";}
													echo "<input type='text' size='30' name='besoinLieuRetourLocationVoiture_' id='besoinLieuRetourLocationVoiture_' value='".$LieuFinLocationVoiture."'></td></tr>";
													
													if($_SESSION['Langue']=="FR"){echo "<tr><td>Date : ";}else{echo "<tr><td>Date : ";}
													echo "<input type='date' name='besoinDateRetourLocationVoiture_' id='besoinDateRetourLocationVoiture_'></td>";
													if($_SESSION['Langue']=="FR"){echo "<td>Heure : ";}else{echo "<td>Hour : ";}
													echo "<div class='input-group bootstrap-timepicker timepicker' style='display: inline'>";
													echo "<input class='form-control input-small' style='text-align:center;' type='text' size='10' name='besoinHeureRetourLocationVoiture_' id='besoinHeureRetourLocationVoiture_' value='".$HeureFinLocationVoiture."'>";
													echo "</div></td></tr>";
													
												}
												echo "<tr><td colspan='4'><textarea name='besoin_".$rowB['Id']."' ".$nonModifiable." id='besoin_".$rowB['Id']."' cols='90' rows='2' noresize>".stripslashes($Commentaire)."</textarea>".$image."</td></tr></table></td></tr>";
												echo "<tr><td width='25%' colspan='4' style='border-bottom:1px dotted #37a223;'></td>";
											}
										}
									}
									echo "</table>";
									?>
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<?php if($Menu<>7){?>
						<tr>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Frais réels ou calendaires :";}else{echo "Actual or calendar fees :";} ?> </td>
							<td width="30%">
								<?php if($Menu==3){?>
								<input type="radio" id='typeDeFrais' name='typeDeFrais' onclick="AfficheDemandeAvance()" value="1" <?php if($rowDODM['FraisReel']==1){echo "checked";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Réels";}else{echo "Actual";} ?> &nbsp;&nbsp;
								<input type="radio" id='typeDeFrais' name='typeDeFrais' onclick="AfficheDemandeAvance()" value="0" <?php if($rowDODM['FraisReel']==0){echo "checked";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Calendaires";}else{echo "Calendar";} ?> &nbsp;&nbsp;
								<?php 
									}
									else{
										if($rowDODM['FraisReel']==1){
											if($_SESSION["Langue"]=="FR"){echo "Réels";}else{echo "Actual";}
										}
										else{
											if($_SESSION["Langue"]=="FR"){echo "Calendaires";}else{echo "Calendar";}
										}
									}
								?>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr id="trDemandeAvance" <?php if($rowDODM['FraisReel']==0){echo "style='display:none;'";} ?>>
							<?php if($Menu==3){?>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Souhaitez-vous faire une demande d'avance sur frais :";}else{echo "Would you like to apply for an advance on fees :";} ?> </td>
							<td width="30%">
								<input type="radio" id='demandeAvance' name='demandeAvance' onclick="AfficheAvance()" <?php if($rowDODM['Montant']>0){echo "checked";} ?> value="1"	><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?> &nbsp;&nbsp;
								<input type="radio" id='demandeAvance' name='demandeAvance' onclick="AfficheAvance()" <?php if($rowDODM['Montant']==0){echo "checked";} ?> value="0"><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?> &nbsp;&nbsp;
							</td>
							<?php 
								}
								else{
									if($_SESSION["Langue"]=="FR"){
										echo '<td width="15%" class="Libelle">Avance sur frais</td>';
									}
									else{
										echo '<td width="15%" class="Libelle">Advance on fees</td>';
									}
									if($rowDODM['Montant']>0){
										if($_SESSION["Langue"]=="FR"){echo "<td width='30%'>Oui</td>";}else{echo "<td width='30%'>Yes</td>";}
									}
									else{
										if($_SESSION["Langue"]=="FR"){echo "<td width='30%'>Non</td>";}else{echo "<td width='30%'>No</td>";}
									}
								}
							?>
						</tr>
						<tr id="trAvance1" <?php echo "style='display:none;'"; ?>><td height="4"></td></tr>
						<tr id="trAvance2" <?php echo "style='display:none;'"; ?>>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Avance ponctuelle ou permanente :";}else{echo "One-time or permanent advance :";} ?> </td>
							<td width="30%">
								<?php if($Menu==3){?>
								<input type="radio" id='avance' name='avance' onclick="AffichePeriode()" value="1" <?php if($rowDODM['AvancePonctuelle']==1){echo "checked";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Ponctuelle";}else{echo "One-time";} ?> &nbsp;&nbsp;
								<input type="radio" id='avance' name='avance' onclick="AffichePeriode()" value="0" <?php if($rowDODM['AvancePonctuelle']==0){echo "checked";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Permanente";}else{echo "Permanent";} ?> &nbsp;&nbsp;
								<?php 
									}
									else{
										if($rowDODM['AvancePonctuelle']==1){
											if($_SESSION["Langue"]=="FR"){echo "Ponctuelle";}else{echo "One-time";}
										}
										else{
											if($_SESSION["Langue"]=="FR"){echo "Permanente";}else{echo "Permanent";}
										}
									}
								?>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr id="trAvance3" <?php if($rowDODM['Montant']==0){echo "style='display:none;'";} ?>>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Montant :";}else{echo "Amount :";} ?> </td>
							<td width="30%">
								<input onKeyUp="nombre(this)" type="text" id='montant' <?php echo $nonModifiable;?> name='montant' size="8" value="<?php echo $rowDODM['Montant']; ?>">
							</td>
							<td width="15%" id="labelPeriode1" class="Libelle" ><?php if($_SESSION["Langue"]=="FR"){echo "Date :";}else{echo "Date :";} ?> </td>
							<td width="30%" id="labelPeriode2" >
								<input type="<?php echo $typeDate;?>" id='periode' name='periode' size="10" <?php echo $nonModifiable;?> value="<?php if($Menu==3){echo AfficheDateFR($rowDODM['Periode']);}else{echo AfficheDateJJ_MM_AAAA($rowDODM['Periode']);} ?>">
							</td>
						</tr>
						<?php } 
						if($Menu==8 && $rowDODM['Montant']>0){
						?>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle" colspan="3" >
							<input type="checkbox" id='priseEnCompteAvance' name='priseEnCompteAvance' <?php if($rowDODM['DatePriseEnCompteAvance']>'0001-01-01'){echo "checked";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Prise en compte de la demande d'avance";}else{echo "Taking into account the request for an advance";} ?>
							</td>
						</tr>
						<?php
						}
						?>
						<tr><td height="8"></td></tr>
						<tr>
							<td colspan="6" align="center">
								<?php if($Menu<>4){?>
								<input class="Bouton" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Valider";}else{echo "Validate";} ?>"/>
								<?php } ?>
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
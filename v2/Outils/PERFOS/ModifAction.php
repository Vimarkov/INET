<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<!-- Latin 1 = ISO-8859-1-->
	<meta charset=ISO-8859-1 />
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Action.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" language="Javascript" src="ModifAction.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	
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
	 </script>
</head>

<?php
require("../Connexioni.php");
require("../Fonctions.php");

if(isset($_POST['submitValider'])){
	$probleme = "";
	$lettre = "";
	$niveau = "";
	$ldate = date("Y-m-d");
	if($_POST['Id_Action'] == 0){
		//Ajout d'une Action
		$Pole = 0;
		if (!empty($_POST['pole'])){$Pole =$_POST['pole'];}
		
		$reqDroit = "SELECT MIN(Id_Poste) AS Id_Poste FROM  new_competences_personne_poste_prestation WHERE Id_Personne=".$_POST['Personne'];
		$reqDroit .= " AND Id_Prestation=".$_POST['prestation']." AND Id_Pole=".$Pole;
		$resultDroit=mysqli_query($bdd,$reqDroit);
		$rowPoste=mysqli_fetch_array($resultDroit);
		$niveauCreateur=1;
		if($rowPoste['Id_Poste'] < 3){$niveauCreateur=1;}
		else if($rowPoste['Id_Poste'] == 3 || $rowPoste['Id_Poste'] == 5){$niveauCreateur=2;}
		else if($rowPoste['Id_Poste'] == 4 || $rowPoste['Id_Poste'] == 6 || $rowPoste['Id_Poste'] == 7){$niveauCreateur=3;}
		else if($rowPoste['Id_Poste'] == 8 || $rowPoste['Id_Poste'] == 9){$niveauCreateur=4;}
		$requeteInsert="INSERT INTO new_action (Type,Lettre,DateCreation,Vacation,Id_Createur,Probleme,Commentaire,SituationAvancement,Id_Prestation,Id_Pole,Niveau,Action,Id_Acteur,Delais,ReprisDQ506,Avancement,DateSolde,NiveauCreateur) ";
		$requeteInsert.=" VALUES ";
		$requeteInsert.=" ('SQCDPF','".$_POST['lettre']."','".$ldate."','".$_POST['vacation']."',".$_POST['Personne'].",";
		$requeteInsert.="'".addslashes($_POST['probleme'])."','".addslashes($_POST['commentaire'])."','".addslashes($_POST['situationAvancement'])."',".$_POST['prestation'].",".$Pole.",".$_POST['niveaux'].",";
		
		$dateDelais = 0;
		if($_POST['delais']<> ""){
			if ($NavigOk ==1){
				$laDate = $_POST['delais'];
				$tabdateDelais = explode('-', $laDate);
				$timestampDateD = mktime(0, 0, 0, $tabdateDelais[1], $tabdateDelais[2], $tabdateDelais[0]);
				$dateDelais = date("Y-m-d",$timestampDateD);
			}
			else{
				$laDate = $_POST['delais'];
				$tabdateDelais = explode('/', $laDate);
				$timestampDateD = mktime(0, 0, 0, $tabdateDelais[1], $tabdateDelais[0], $tabdateDelais[2]);
				$dateDelais = date("Y-m-d",$timestampDateD);
			}
		}
		
		if($_POST['bvisible'] ==1){
			
			if($_POST['avancement'] >= 4){
				$dateCloture = 0;
				if ($NavigOk ==1){
					$laDateC = $_POST['dateCloture'];
					$tabdateCloture = explode('-', $laDateC);
					$timestampDateC = mktime(0, 0, 0, $tabdateCloture[1], $tabdateCloture[2], $tabdateCloture[0]);
					$dateCloture = date("Y-m-d",$timestampDateC);
				}
				else{
					$laDateC = $_POST['dateCloture'];
					$tabdateCloture = explode('/', $laDateC);
					$timestampDateC = mktime(0, 0, 0, $tabdateCloture[1], $tabdateCloture[0], $tabdateCloture[2]);
					$dateCloture = date("Y-m-d",$timestampDateC);
				}
				$requeteInsert.="'".addslashes($_POST['action'])."',".$_POST['Personne'].",'".$dateDelais."',".$_POST['repris'].",3,'".$dateCloture."',".$_POST['niveaux'].")";
			}
			else{
				$requeteInsert.="'".addslashes($_POST['action'])."',".$_POST['Personne'].",'".$dateDelais."',".$_POST['repris'].",".$_POST['avancement'].",0,".$_POST['niveaux'].")";
			}
		}
		else{
			$requeteInsert.="'".addslashes($_POST['action'])."',0,'".$dateDelais."',0,0,0,".$niveauCreateur.")";
		}
		$resultAjout=mysqli_query($bdd,$requeteInsert);
		$IdCree = mysqli_insert_id($bdd);
		
		if($_POST['avancement'] > 4){
			//Si poursuivre N+1 ou N-1 alors ajout d'une nouvelle action
			$requeteInsert="INSERT INTO new_action (Type,Lettre,DateCreation,Vacation,Id_Createur,Probleme,Commentaire,Id_Prestation,Id_Pole,Niveau,Action,Delais,Avancement,NiveauCreateur,Id_ActionLiee) ";
			$requeteInsert.=" VALUES ";
			$requeteInsert.=" ('SQCDPF','".$_POST['lettre']."','".$ldate."','".$_POST['vacation']."',".$_POST['Personne'].",";
			
			$niveau2 = $_POST['niveaux'];
			if($_POST['avancement'] == 5){
				$niveau2++;
			}
			else if($_POST['avancement'] == 6){
				$niveau2--;
			}
			$requeteInsert.="'".addslashes($_POST['probleme'])."','".addslashes($_POST['commentaireN'])."',".$_POST['prestation'].",".$Pole.",".$niveau2.",";
			
			$dateDelaisN = 0;
			if($_POST['delaisN']<>""){
				if ($NavigOk ==1){
					$laDate = $_POST['delaisN'];
					$tabdateDelais = explode('-', $laDate);
					$timestampDateD = mktime(0, 0, 0, $tabdateDelais[1], $tabdateDelais[2], $tabdateDelais[0]);
					$dateDelaisN = date("Y-m-d",$timestampDateD);
				}
				else{
					$laDate = $_POST['delaisN'];
					$tabdateDelais = explode('/', $laDate);
					$timestampDateD = mktime(0, 0, 0, $tabdateDelais[1], $tabdateDelais[0], $tabdateDelais[2]);
					$dateDelaisN = date("Y-m-d",$timestampDateD);
				}
			}
			$requeteInsert.="'".addslashes($_POST['actionN'])."','".$dateDelaisN."',0,".$niveauCreateur.",".$IdCree.")";
			
			$resultAjout=mysqli_query($bdd,$requeteInsert);	
		}
		
		$reqPresta = "SELECT Libelle FROM new_competences_prestation WHERE Id =".$_POST['prestation']."";
		$resultPresta=mysqli_query($bdd,$reqPresta);
		$rowPresta = mysqli_fetch_array($resultPresta);
		
		$NomPole = "";
		if ($Pole > 0){
			$reqPole = "SELECT Libelle FROM new_competences_pole WHERE Id =".$Pole."";
			$resultPole=mysqli_query($bdd,$reqPole);
			$rowPole = mysqli_fetch_array($resultPole);
			$NomPole = $rowPole['Libelle'];
		}
		
		$objetMail = "Nouvelle action - ".$rowPresta['Libelle']." ".$NomPole." - " .$ldate."";
		
		$reqDroit = "SELECT MIN(Id_Poste) AS Id_Poste FROM  new_competences_personne_poste_prestation WHERE Id_Personne=".$_POST['Personne'];
		$reqDroit .= " AND Id_Prestation=".$_POST['prestation']." AND Id_Pole=".$Pole;			
		$resultDroit=mysqli_query($bdd,$reqDroit);
		$nbDroit=mysqli_num_rows($resultDroit);
		$rowPoste=mysqli_fetch_array($resultDroit);	
		
		//Ajout de la hierarchie en fonction du niveau
		$requeteResponsablePostePrestation="SELECT DISTINCT new_rh_etatcivil.EmailPro ";
		$requeteResponsablePostePrestation.=" FROM new_competences_personne_poste_prestation LEFT JOIN new_rh_etatcivil ON new_competences_personne_poste_prestation.Id_Personne = new_rh_etatcivil.Id ";
		$requeteResponsablePostePrestation.=" WHERE new_competences_personne_poste_prestation.Id_Prestation=".$_POST['prestation'];
		if ($Pole > 0){
			$requeteResponsablePostePrestation.=" AND new_competences_personne_poste_prestation.Id_Pole=".$Pole;
		}
		if($_POST['niveaux'] == 1){
			$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=1 OR new_competences_personne_poste_prestation.Id_Poste=2 OR";
			$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=3 OR new_competences_personne_poste_prestation.Id_Poste=5) ";
		}
		elseif($_POST['niveaux'] == 2){
			if($rowPoste['Id_Poste']=='1' || $rowPoste['Id_Poste']=='2'){
				$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=1 OR new_competences_personne_poste_prestation.Id_Poste=2 OR";
				$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=3 OR new_competences_personne_poste_prestation.Id_Poste=5) ";
			}
			else{
				$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=3 OR new_competences_personne_poste_prestation.Id_Poste=5 OR";
				$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=4 OR new_competences_personne_poste_prestation.Id_Poste=6 OR new_competences_personne_poste_prestation.Id_Poste=7) ";
			}
		}
		elseif($_POST['niveaux'] == 3){
			if($rowPoste['Id_Poste']=='3' || $rowPoste['Id_Poste']=='5'){
				$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=3 OR new_competences_personne_poste_prestation.Id_Poste=5 OR";
				$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=4 OR new_competences_personne_poste_prestation.Id_Poste=6 OR new_competences_personne_poste_prestation.Id_Poste=7) ";
			}
			else{
				$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=4 OR new_competences_personne_poste_prestation.Id_Poste=6 OR";
				$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=7 OR new_competences_personne_poste_prestation.Id_Poste=8 OR new_competences_personne_poste_prestation.Id_Poste=9) ";
			}
		}
		elseif($_POST['niveaux'] == 4){
			if($rowPoste['Id_Poste']=='4' || $rowPoste['Id_Poste']=='6' || $rowPoste['Id_Poste']=='7'){
				$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=4 OR new_competences_personne_poste_prestation.Id_Poste=6 OR";
				$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=7 OR new_competences_personne_poste_prestation.Id_Poste=8 OR new_competences_personne_poste_prestation.Id_Poste=9) ";				
			}
			else{
				$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=8 OR new_competences_personne_poste_prestation.Id_Poste=9) ";
			}
		}
		$resultResponsablePostePrestation=mysqli_query($bdd,$requeteResponsablePostePrestation);
		
		if($_POST['avancement'] > 4){
			$objetMail2 = "Nouvelle action - ".$rowPresta['Libelle']." ".$NomPole." - " .$ldate." - ".$_POST['vacation'];
			
			//Ajout de la hierarchie en fonction du niveau si poursuivre
			$requeteResponsablePostePrestation="SELECT DISTINCT new_rh_etatcivil.EmailPro ";
			$requeteResponsablePostePrestation.=" FROM new_competences_personne_poste_prestation LEFT JOIN new_rh_etatcivil ON new_competences_personne_poste_prestation.Id_Personne = new_rh_etatcivil.Id ";
			$requeteResponsablePostePrestation.=" WHERE new_competences_personne_poste_prestation.Id_Prestation=".$_POST['prestation'];
			if ($Pole > 0){
				$requeteResponsablePostePrestation.=" AND new_competences_personne_poste_prestation.Id_Pole=".$Pole;
			}
			if($niveau2 == 1){
				$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=1 OR new_competences_personne_poste_prestation.Id_Poste=2 OR";
				$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=3 OR new_competences_personne_poste_prestation.Id_Poste=5) ";
			}
			elseif($niveau2 == 2){
				if($rowPoste['Id_Poste']=='1' || $rowPoste['Id_Poste']=='2'){
					$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=1 OR new_competences_personne_poste_prestation.Id_Poste=2 OR";
					$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=3 OR new_competences_personne_poste_prestation.Id_Poste=5) ";
				}
				else{
					$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=3 OR new_competences_personne_poste_prestation.Id_Poste=5 OR";
					$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=4 OR new_competences_personne_poste_prestation.Id_Poste=6 OR new_competences_personne_poste_prestation.Id_Poste=7) ";
				}
			}
			elseif($niveau2 == 3){
				if($rowPoste['Id_Poste']=='3' || $rowPoste['Id_Poste']=='5'){
					$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=3 OR new_competences_personne_poste_prestation.Id_Poste=5 OR";
					$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=4 OR new_competences_personne_poste_prestation.Id_Poste=6 OR new_competences_personne_poste_prestation.Id_Poste=7) ";
				}
				else{
					$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=4 OR new_competences_personne_poste_prestation.Id_Poste=6 OR";
					$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=7 OR new_competences_personne_poste_prestation.Id_Poste=8 OR new_competences_personne_poste_prestation.Id_Poste=9) ";
				}
			}
			elseif($niveau2 == 4){
				if($rowPoste['Id_Poste']=='4' || $rowPoste['Id_Poste']=='6' || $rowPoste['Id_Poste']=='7'){
					$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=4 OR new_competences_personne_poste_prestation.Id_Poste=6 OR";
					$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=7 OR new_competences_personne_poste_prestation.Id_Poste=8 OR new_competences_personne_poste_prestation.Id_Poste=9) ";				
				}
				else{
					$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=8 OR new_competences_personne_poste_prestation.Id_Poste=9) ";
				}
			}
			$resultResponsablePostePrestationPoursuivre=mysqli_query($bdd,$requeteResponsablePostePrestation);
			
		}
		$probleme = $_POST['probleme'];
		$lettre = $_POST['lettre'];
		$niveau = $_POST['niveaux'];
		
		//---------Créateur du Action--------//
		$reqCreateur="SELECT Nom, Prenom, EmailPro FROM new_rh_etatcivil WHERE Id=".$_POST['Personne'];
		$resultCreateur=mysqli_query($bdd,$reqCreateur);
		$rowCreateur = mysqli_fetch_array($resultCreateur);

	}
	else{
		//Modif Action
		$requeteUpdate="UPDATE new_action SET ";
		if($_POST['createur'] == $_POST['Personne']){
			$requeteUpdate.= "Probleme='".addslashes($_POST['probleme'])."', ";
			$requeteUpdate.= "Niveau=".$_POST['niveaux'].", ";
			$requeteUpdate.= "Lettre='".$_POST['lettre']."', ";
		}
		$requeteUpdate.= "Action='".addslashes($_POST['action'])."', ";
		$dateDelais = "0001-01-01";
		if($_POST['delais']<>""){
			if ($NavigOk ==1){
				$laDate = $_POST['delais'];
				$tabdateDelais = explode('-', $laDate);
				$timestampDateD = mktime(0, 0, 0, $tabdateDelais[1], $tabdateDelais[2], $tabdateDelais[0]);
				$dateDelais = date("Y-m-d",$timestampDateD);
			}
			else{
				$laDate = $_POST['delais'];
				$tabdateDelais = explode('/', $laDate);
				$timestampDateD = mktime(0, 0, 0, $tabdateDelais[1], $tabdateDelais[0], $tabdateDelais[2]);
				$dateDelais = date("Y-m-d",$timestampDateD);
			}
		}
		$requeteUpdate.= "Delais='".$dateDelais."', ";
		$requeteUpdate.= "Commentaire='".addslashes($_POST['commentaire'])."', ";
		$requeteUpdate.= "SituationAvancement='".addslashes($_POST['situationAvancement'])."', ";
		if($_POST['acteur'] == $_POST['Personne'] && $_POST['bvisible'] <>1){
			$requeteUpdate.= "Action='".addslashes($_POST['action'])."', ";
			$requeteUpdate.= "Id_Acteur=0, ";
			$requeteUpdate.= "Avancement='0', ";
			$requeteUpdate.= "DateSolde='', ";
			$requeteUpdate.= "ReprisDQ506=0, ";
		}
		elseif($_POST['acteur'] == $_POST['Personne'] || $_POST['bvisible'] ==1){
			$requeteUpdate.= "Id_Acteur=".$_POST['Personne'].", ";
			if($_POST['avancement'] >= 4){
				$requeteUpdate.= "Avancement='4', ";
				$dateCloture = 0;
				if ($NavigOk ==1){
					$laDateC = $_POST['dateCloture'];
					$tabdateCloture = explode('-', $laDateC);
					$timestampDateC = mktime(0, 0, 0, $tabdateCloture[1], $tabdateCloture[2], $tabdateCloture[0]);
					$dateCloture = date("Y-m-d",$timestampDateC);
				}
				else{
					$laDateC = $_POST['dateCloture'];
					$tabdateCloture = explode('/', $laDateC);
					$timestampDateC = mktime(0, 0, 0, $tabdateCloture[1], $tabdateCloture[0], $tabdateCloture[2]);
					$dateCloture = date("Y-m-d",$timestampDateC);
				}
				$requeteUpdate.= "DateSolde='".$dateCloture."', ";
			}
			else{
				$requeteUpdate.= "Avancement='".$_POST['avancement']."', ";
			}
			$requeteUpdate.= "ReprisDQ506=".$_POST['repris'].", ";
		}
		$requeteUpdate=substr($requeteUpdate,0,-2);
		$requeteUpdate.= " WHERE Id =".$_POST['Id_Action'].";";
		$resultModif=mysqli_query($bdd,$requeteUpdate);
		
		$reqSelect = "SELECT Id_Prestation, Id_Pole, DateCreation, Vacation, Lettre, Niveau, Probleme,Id_Createur,Id_ActionLiee FROM new_action WHERE Id=".$_POST['Id_Action'];
		$resultSelect=mysqli_query($bdd,$reqSelect);
		$rowSelect = mysqli_fetch_array($resultSelect);
		
		if($_POST['avancement'] > 4){
			//Si poursuivre N+1 ou N-1 alors ajout d'une nouvelle action
			$requeteInsert="INSERT INTO new_action (Type,Lettre,DateCreation,Vacation,Id_Createur,Probleme,Commentaire,Id_Prestation,Id_Pole,Niveau,Action,Delais,Avancement,NiveauCreateur,Id_ActionLiee) ";
			$requeteInsert.=" VALUES ";
			$requeteInsert.=" ('SQCDPF','".$rowSelect['Lettre']."','".$ldate."','".$_POST['vacation']."',".$_POST['Personne'].",";
			$niveau2 = $rowSelect['Niveau'];
			if($_POST['avancement'] == 5){
				$niveau2++;
			}
			else if($_POST['avancement'] == 6){
				$niveau2--;
			}
			$requeteInsert.="'".addslashes($rowSelect['Probleme'])."','".addslashes($_POST['commentaireN'])."',".$rowSelect['Id_Prestation'].",".$rowSelect['Id_Pole'].",".$niveau2.",";
			
			$dateDelaisN = 0;
			if($_POST['delaisN']<>""){
				if ($NavigOk ==1){
					$laDate = $_POST['delaisN'];
					$tabdateDelais = explode('-', $laDate);
					$timestampDateD = mktime(0, 0, 0, $tabdateDelais[1], $tabdateDelais[2], $tabdateDelais[0]);
					$dateDelaisN = date("Y-m-d",$timestampDateD);
				}
				else{
					$laDate = $_POST['delaisN'];
					$tabdateDelais = explode('/', $laDate);
					$timestampDateD = mktime(0, 0, 0, $tabdateDelais[1], $tabdateDelais[0], $tabdateDelais[2]);
					$dateDelaisN = date("Y-m-d",$timestampDateD);
				}
			}
			$IdActionLiee = 0;
			if($rowSelect['Id_ActionLiee']>0){$IdActionLiee = $rowSelect['Id_ActionLiee'];}
			else{$IdActionLiee = $_POST['Id_Action'];}
			$requeteInsert.="'".addslashes($_POST['actionN'])."','".$dateDelaisN."',0,".$rowSelect['Niveau'].",".$IdActionLiee.")";
			echo $requeteInsert;
			$resultAjout=mysqli_query($bdd,$requeteInsert);
		}
		
		$reqPresta = "SELECT Libelle FROM new_competences_prestation WHERE Id =".$rowSelect['Id_Prestation']."";
		$resultPresta=mysqli_query($bdd,$reqPresta);
		$rowPresta = mysqli_fetch_array($resultPresta);
		
		$NomPole = "";
		if ($rowSelect['Id_Pole'] > 0){
			$reqPole = "SELECT Libelle FROM new_competences_pole WHERE Id =".$rowSelect['Id_Pole']."";
			$resultPole=mysqli_query($bdd,$reqPole);
			$rowPole = mysqli_fetch_array($resultPole);
			$NomPole = $rowPole['Libelle'];
		}
		
		if ($_POST['avancement'] >= 4){
			$objetMail = "Action clôturée - ".$rowPresta['Libelle']." ".$NomPole." - " .$rowSelect['DateCreation']." - ".$rowSelect['Vacation'];
		}
		else{
			$objetMail = "Action modifiée - ".$rowPresta['Libelle']." ".$NomPole." - " .$rowSelect['DateCreation']." - ".$rowSelect['Vacation'];
		}

		$reqAct="SELECT new_action.Lettre, new_action.Probleme, new_action.Action, Vacation, new_action.Delais, new_action.Niveau, ";
		$reqAct.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=new_action.Id_Acteur) AS Responsable ";
		$reqAct.="FROM new_action WHERE new_action.DateCreation='".$rowSelect['DateCreation']."' AND new_action.Id_Prestation=".$rowSelect['Id_Prestation']." AND new_action.Id_Pole=".$rowSelect['Id_Pole'];
		
		$reqDroit = "SELECT MIN(Id_Poste) AS Id_Poste FROM  new_competences_personne_poste_prestation WHERE Id_Personne=".$rowSelect['Id_Createur'];
		$reqDroit .= " AND Id_Prestation=".$rowSelect['Id_Prestation']." AND Id_Pole=".$rowSelect['Id_Pole'];			
		$resultDroit=mysqli_query($bdd,$reqDroit);
		$nbDroit=mysqli_num_rows($resultDroit);
		$rowPoste=mysqli_fetch_array($resultDroit);
		
		//Ajouter des responsables
		$requeteResponsablePostePrestation="SELECT DISTINCT new_rh_etatcivil.EmailPro ";
		$requeteResponsablePostePrestation.=" FROM new_competences_personne_poste_prestation LEFT JOIN new_rh_etatcivil ON new_competences_personne_poste_prestation.Id_Personne = new_rh_etatcivil.Id ";
		$requeteResponsablePostePrestation.=" WHERE new_competences_personne_poste_prestation.Id_Prestation=".$rowSelect['Id_Prestation'];
		if ($rowSelect['Id_Pole'] > 0){
			$requeteResponsablePostePrestation.=" AND new_competences_personne_poste_prestation.Id_Pole=".$rowSelect['Id_Pole'];
		}
		if($rowSelect['Niveau'] == 1){
			$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=1 OR new_competences_personne_poste_prestation.Id_Poste=2 OR";
			$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=3 OR new_competences_personne_poste_prestation.Id_Poste=5) ";
		}
		elseif($rowSelect['Niveau'] == 2){
			if($rowPoste['Id_Poste']=='1' || $rowPoste['Id_Poste']=='2'){
				$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=1 OR new_competences_personne_poste_prestation.Id_Poste=2 OR";
				$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=3 OR new_competences_personne_poste_prestation.Id_Poste=5) ";
			}
			else{
				$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=3 OR new_competences_personne_poste_prestation.Id_Poste=5 OR";
				$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=4 OR new_competences_personne_poste_prestation.Id_Poste=6 OR new_competences_personne_poste_prestation.Id_Poste=7) ";
			}
		}
		elseif($rowSelect['Niveau'] == 3){
			if($rowPoste['Id_Poste']=='3' || $rowPoste['Id_Poste']=='5'){
				$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=3 OR new_competences_personne_poste_prestation.Id_Poste=5 OR";
				$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=4 OR new_competences_personne_poste_prestation.Id_Poste=6 OR new_competences_personne_poste_prestation.Id_Poste=7) ";
			}
			else{
				$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=4 OR new_competences_personne_poste_prestation.Id_Poste=6 OR";
				$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=7 OR new_competences_personne_poste_prestation.Id_Poste=8 OR new_competences_personne_poste_prestation.Id_Poste=9) ";
			}
		}
		elseif($rowSelect['Niveau'] == 4){
			if($rowPoste['Id_Poste']=='4' || $rowPoste['Id_Poste']=='6' || $rowPoste['Id_Poste']=='7'){
				$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=4 OR new_competences_personne_poste_prestation.Id_Poste=6 OR";
				$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=7 OR new_competences_personne_poste_prestation.Id_Poste=8 OR new_competences_personne_poste_prestation.Id_Poste=9) ";				
			}
			else{
				$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=8 OR new_competences_personne_poste_prestation.Id_Poste=9) ";
			}
		}
		$resultResponsablePostePrestation=mysqli_query($bdd,$requeteResponsablePostePrestation);
		
		if($_POST['avancement'] > 4){
			$objetMail2 = "Nouvelle action - ".$rowPresta['Libelle']." ".$NomPole." - " .$rowSelect['DateCreation']." ";
			
			//Ajout de la hierarchie en fonction du niveau si poursuivre
			$requeteResponsablePostePrestation="SELECT DISTINCT new_rh_etatcivil.EmailPro ";
			$requeteResponsablePostePrestation.=" FROM new_competences_personne_poste_prestation LEFT JOIN new_rh_etatcivil ON new_competences_personne_poste_prestation.Id_Personne = new_rh_etatcivil.Id ";
			$requeteResponsablePostePrestation.=" WHERE new_competences_personne_poste_prestation.Id_Prestation=".$rowSelect['Id_Prestation'];
			if ($rowSelect['Id_Pole'] > 0){
				$requeteResponsablePostePrestation.=" AND new_competences_personne_poste_prestation.Id_Pole=".$rowSelect['Id_Pole'];
			}
			if($niveau2 == 1){
				$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=1 OR new_competences_personne_poste_prestation.Id_Poste=2 OR";
				$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=3 OR new_competences_personne_poste_prestation.Id_Poste=5) ";
			}
			elseif($niveau2 == 2){
				if($rowPoste['Id_Poste']=='1' || $rowPoste['Id_Poste']=='2'){
					$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=1 OR new_competences_personne_poste_prestation.Id_Poste=2 OR";
					$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=3 OR new_competences_personne_poste_prestation.Id_Poste=5) ";
				}
				else{
					$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=3 OR new_competences_personne_poste_prestation.Id_Poste=5 OR";
					$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=4 OR new_competences_personne_poste_prestation.Id_Poste=6 OR new_competences_personne_poste_prestation.Id_Poste=7) ";
				}
			}
			elseif($niveau2 == 3){
				if($rowPoste['Id_Poste']=='3' || $rowPoste['Id_Poste']=='5'){
					$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=3 OR new_competences_personne_poste_prestation.Id_Poste=5 OR";
					$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=4 OR new_competences_personne_poste_prestation.Id_Poste=6 OR new_competences_personne_poste_prestation.Id_Poste=7) ";
				}
				else{
					$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=4 OR new_competences_personne_poste_prestation.Id_Poste=6 OR";
					$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=7 OR new_competences_personne_poste_prestation.Id_Poste=8 OR new_competences_personne_poste_prestation.Id_Poste=9) ";
				}
			}
			elseif($niveau2 == 4){
				if($rowPoste['Id_Poste']=='4' || $rowPoste['Id_Poste']=='6' || $rowPoste['Id_Poste']=='7'){
					$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=4 OR new_competences_personne_poste_prestation.Id_Poste=6 OR";
					$requeteResponsablePostePrestation.=" new_competences_personne_poste_prestation.Id_Poste=7 OR new_competences_personne_poste_prestation.Id_Poste=8 OR new_competences_personne_poste_prestation.Id_Poste=9) ";				
				}
				else{
					$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=8 OR new_competences_personne_poste_prestation.Id_Poste=9) ";
				}
			}
			$resultResponsablePostePrestationPoursuivre=mysqli_query($bdd,$requeteResponsablePostePrestation);
			
		}
		$ldate = $rowSelect['DateCreation'];
		$probleme = $rowSelect['Probleme'];
		$lettre = $rowSelect['Lettre'];
		$niveau = $rowSelect['Niveau'];
		
		//---------Créateur du Action--------//
		$reqCreateur="SELECT Nom, Prenom, EmailPro FROM new_rh_etatcivil WHERE Id=".$rowSelect['Id_Createur'];
		$resultCreateur=mysqli_query($bdd,$reqCreateur);
		$rowCreateur = mysqli_fetch_array($resultCreateur);
	}
	echo "<script>FermerEtRecharger(".$_POST['IdPrestationSelect'].",".$_POST['IdPoleSelect'].",".$_POST['DateSelect'].",".$_POST['visionSelect'].",".$_POST['CreateurSelect'].",".$_POST['ActeurSelect'].",".$_POST['AvancementSelect'].",".$_POST['NiveauSelect'].",'".$_POST['LettreSelect']."');</script>";
}

if ($_GET){
	$IdPersonne = $_GET['Id_Personne'];
	$IdPrestationSelect = $_GET['Id_Prestation'];
	$IdPoleSelect = $_GET['Id_Pole'];
	$DateSelect = $_GET['dateEnvoi'];
	$vision = $_GET['vision'];
	$createurSelect = $_GET['createur'];
	$acteurSelect = $_GET['acteur'];
	$avancementSelect = $_GET['avancement'];
	$niveauSelect = $_GET['niveau'];
	$lettreSelect = $_GET['lettre'];
	
	$IdAction = 0;
	if($_GET['Mode'] == "M"){
		$IdAction = $_GET['Id_Action'];
	}
	elseif($_GET['Mode'] == "S"){
		$IdAction = $_GET['Id_Action'];
		$reqSuppr="DELETE FROM new_action WHERE Id=".$IdAction;
		$resultSuppr=mysqli_query($bdd,$reqSuppr);
		echo "<script>FermerEtRecharger(".$IdPrestationSelect.",".$IdPoleSelect.",".$DateSelect.",".$vision.",".$createurSelect.",".$acteurSelect.",".$avancementSelect.",".$niveauSelect.",'".$lettreSelect."');</script>";			
	}
	
	$req = "SELECT new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom FROM new_rh_etatcivil WHERE Id=".$IdPersonne;
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	if ($nb>0){
		$row=mysqli_fetch_array($result);
		echo "<script>resp_Action = '".$row['Nom']." ".$row['Prenom']."';</script>\n";
	}
	
	$req = "SELECT new_action.Id, new_action.DateCreation, Vacation, new_action.Id_Prestation, new_action.Id_Pole, new_action.Id_Createur, new_action.SituationAvancement, ";
	$req .= "new_action.Probleme, new_action.Action, new_action.Id_Acteur, new_action.Delais, new_action.Avancement, new_action.DateSolde, new_action.Niveau, new_action.ReprisDQ506, ";
	$req .= "(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id = new_action.Id_Prestation) AS Prestation, ";
	$req .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id =new_action.Id_Pole) AS Pole, new_action.Lettre, new_action.DateSolde, new_action.Commentaire ";
	$req .= "FROM new_action ";
	$req .= "WHERE new_action.Id =".$IdAction.";";
	
	$resultAction=mysqli_query($bdd,$req);
	$nbAction=mysqli_num_rows($resultAction);
	$titre="Créer une action";
	if ($nbAction>0){
		$row=mysqli_fetch_array($resultAction);
		$prestation = $row['Prestation'];
		$pole = $row['Pole'];
		$uneDate = $row['DateCreation'];
		$vacation = $row['Vacation'];
		$titre="Modifier une action";
	}
}
?>
<form class="test" method="POST" action="ModifAction.php" onSubmit="return VerifChamps();">
	<table width="100%" cellpadding="0" cellspacing="0" align="center">
		<tr>
			<td>
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td width="4"/>
						<td class="TitrePage"><?php echo $titre; ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr style="display:none;">
			<td><input type="text" name="Personne" size="11" value="<?php echo $IdPersonne; ?>"></td>
			<td><input type="text" name="IdPrestationSelect" size="11" value="<?php echo $IdPrestationSelect; ?>"></td>
			<td><input type="text" name="IdPoleSelect" size="11" value="<?php echo $IdPoleSelect; ?>"></td>
			<td><input type="text" name="DateSelect" size="11" value="<?php echo $DateSelect; ?>"></td>
			<td><input type="text" name="visionSelect" size="11" value="<?php echo $vision; ?>"></td>
			<td><input type="text" id="Id_Action" name="Id_Action" size="11" value="<?php echo $IdAction; ?>"></td>
			<td><input type="text" id="bvisible" name="bvisible" size="11" value=""></td>
			<td><input type="text" id="createur" name="createur" size="11" value="<?php if ($nbAction>0){echo $row['Id_Createur'];}?>"></td>
			<td><input type="text" id="acteur" name="acteur" size="11" value="<?php if ($nbAction>0){echo $row['Id_Acteur'];}?>"></td>
			<td><input type="text" name="CreateurSelect" size="11" value="<?php echo $createurSelect; ?>"></td>
			<td><input type="text" name="ActeurSelect" size="11" value="<?php echo $acteurSelect; ?>"></td>
			<td><input type="text" name="AvancementSelect" size="11" value="<?php echo $avancementSelect; ?>"></td>
			<td><input type="text" name="NiveauSelect" size="11" value="<?php echo $niveauSelect; ?>"></td>
			<td><input type="text" name="LettreSelect" size="11" value="<?php echo $lettreSelect; ?>"></td>
		</tr>
		<tr><td height="4"/></tr>
		<tr><td>
			<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr>
					<td width=8%>
						&nbsp; Prestation : 
					</td>
					<td width=25%>
						<?php 
						if ($nbAction>0){
							echo $row['Prestation'];
						}
						else{
						?>
							<select id="prestation" name="prestation" onchange="Recharge_Liste_Pole();">
							<?php
							$req = "SELECT distinct(new_competences_personne_poste_prestation.Id_Prestation), (SELECT LEFT(Libelle,7) FROM new_competences_prestation ";
							$req .= "WHERE new_competences_prestation.Id = new_competences_personne_poste_prestation.Id_Prestation) AS NomPrestation FROM new_competences_personne_poste_prestation WHERE ";
							$req .= "new_competences_personne_poste_prestation.Id_Personne=".$IdPersonne." ORDER BY NomPrestation;";
							$i=0;
							$resultPrestation=mysqli_query($bdd,$req);
							$nbPrestation=mysqli_num_rows($resultPrestation);
							
							if ($nbPrestation > 0){
								echo "<option value='0' selected></option>";
								while($rowPresta=mysqli_fetch_array($resultPrestation)){
									echo "<option value='".$rowPresta[0]."'>".$rowPresta[1]."</option>";
								}
							 }
							 ?>
							</select>
						<?php
						}
						?>
					</td>
					<td width=8%>
						&nbsp; Pôle : 
					</td>
					<td width=25%>
						<div id="pole">
						<?php 
						if ($nbAction>0){
							echo $row['Pole'];
						}
						else{
						?>
							<select size="1" id="poles" name="pole" onchange="Rechercher_Action();">
							<option value="0" selected/>
							</select>
							<?php
							$reqPole = "SELECT distinct new_competences_personne_poste_prestation.Id_Pole, ";
							$reqPole .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id = new_competences_personne_poste_prestation.Id_Pole) AS LibellePole, ";
							$reqPole .= "new_competences_personne_poste_prestation.Id_Prestation AS Id_Prestation ";
							$reqPole .= "FROM new_competences_personne_poste_prestation WHERE ";
							$reqPole .= "new_competences_personne_poste_prestation.Id_Personne=".$IdPersonne." ORDER BY LibellePole;";
							
							$resultPole=mysqli_query($bdd,$reqPole);
							$nbPole=mysqli_num_rows($resultPole);
							$i=0;
							if ($nbPole > 0){
								while($rowPole=mysqli_fetch_array($resultPole)){
									echo "<script>Liste_Pole_Prestation[".$i."] = new Array(".$rowPole['Id_Pole'].",".$rowPole['Id_Prestation'].",'".addslashes($rowPole['LibellePole'])."');</script>\n";
									$i+=1;
								}
							 }
							 ?>
						<?php
							}
						?>
						</div>
					</td>
					<td width=20% align="left">
						&nbsp;
						Date : 
						<?php 
						if ($nbAction>0){
							echo $row['DateCreation'];
						}
						else{
							if ($NavigOk ==1){
								$dateCreation = date("Y-m-d");
								$tabdateAction = explode('-', $dateCreation);
								$timestampDate = mktime(0, 0, 0, $tabdateAction[1], $tabdateAction[2], $tabdateAction[0]);
								$dateEnvoi = $timestampDate;

							}
							else{
								$dateCreation = date("d/m/Y");
								$tabdateAction = explode('/', $dateCreation);
								$timestampDate = mktime(0, 0, 0, $tabdateAction[1], $tabdateAction[0], $tabdateAction[2]);
								$dateEnvoi = $timestampDate;
							}
							echo $dateCreation;
						}
						?>
					</td>
					<td width=20% align="left">
						&nbsp;
						Vacation : 
						<?php 
						if ($nbAction>0){
							echo $row['Vacation'];
						}
						else{
						?>
						<select id="vacation" name="vacation" onchange="Rechercher_Action();">
							<option value="Jour" selected>Jour</option>
							<option value="Soir">Soir</option>
							<option value="Soir">Nuit</option>
						</select>
						<?php						
						}
						?>
						
					</td>
				</tr>
				<tr>
					<td><br></td>
				</tr>
				<tr>
					<td width=8%>
						&nbsp; Lettre : 
					</td>
					<td width=25%>
						<?php	
						$selectS = "";
						$selectQ = "";
						$selectC = "";
						$selectD = "";
						$selectP = "";
						$selectF = "";
						$bloquer = "";
						if ($nbAction>0){
							switch ($row['Lettre']){
							case "S":
								$selectS = "selected";
								break;
							case "Q":
								$selectQ = "selected";
								break;
							case "C":
								$selectC = "selected";
								break;
							case "D":
								$selectD = "selected";
								break;
							case "P":
								$selectP = "selected";
								break;
							case "F":
								$selectF = "selected";
								break;
							}
							if($row['Id_Createur']<>$IdPersonne){$bloquer = "disabled";}
						}
						?>
						<select class="lettre" name="lettre" <?php echo $bloquer; ?>>
							<option value="S" <?php echo $selectS;?>>S</option>
							<option value="Q" <?php echo $selectQ;?>>Q</option>
							<option value="C" <?php echo $selectC;?>>C</option>
							<option value="D" <?php echo $selectD;?>>D</option>
							<option value="P" <?php echo $selectP;?>>P</option>
							<option value="F" <?php echo $selectF;?>>F</option>
						</select>
					</td>
					<td width=8%>
						&nbsp; Niveau : 
					</td>
					<td width=25%>
						<div id="niveau">
						<select class="niveaux" name="niveaux" id="niveaux" onchange="AfficherChamps(1);" <?php echo $bloquer; ?>>
					<?php
						if ($nbAction>0){
							$reqDroit = "SELECT Id_Poste FROM  new_competences_personne_poste_prestation WHERE Id_Personne=".$IdPersonne;
							$reqDroit .= " AND Id_Prestation=".$row['Id_Prestation']." AND Id_Pole=".$row['Id_Pole'];						
							$resultDroit=mysqli_query($bdd,$reqDroit);
							$nbDroit=mysqli_num_rows($resultDroit);
							if($nbDroit > 0){
								$minPoste=0;
								$maxPoste=0;
								while($rowPoste=mysqli_fetch_array($resultDroit)){
									if($minPoste == 0){
										$minPoste = $rowPoste['Id_Poste'];
										$maxPoste = $rowPoste['Id_Poste'];
									}
									else{
										if ($rowPoste['Id_Poste'] < $minPoste){$minPoste =$rowPoste['Id_Poste'];}
										if ($rowPoste['Id_Poste'] > $maxPoste){$maxPoste =$rowPoste['Id_Poste'];}
									}
								}
								if($minPoste < 3){
									if($row['Niveau']==1){echo "<option value='1' selected>1</option>";}
									else{echo "<option value='1'>1</option>";}
									if($row['Niveau']==2){echo "<option value='2' selected>2</option>";}
									else{echo "<option value='2'>2</option>";}
									if($maxPoste == 3 || $maxPoste == 5){
										if($row['Niveau']==3){echo "<option value='3' selected>3</option>";}
										else{echo "<option value='3'>3</option>";}
									}
									if($maxPoste == 4 || $maxPoste == 6 || $maxPoste == 7 || $maxPoste == 8 || $maxPoste == 9){
										if($row['Niveau']==3){echo "<option value='3' selected>3</option>";}
										else{echo "<option value='3'>3</option>";}
										if($row['Niveau']==4){echo "<option value='4' selected>4</option>";}
										else{echo "<option value='4'>4</option>";}
									}
								}
								else if($minPoste == 3 || $minPoste == 5){
									if($row['Niveau']==1){echo "<option value='1' selected>1</option>";}
									else{echo "<option value='1'>1</option>";}
									if($row['Niveau']==2){echo "<option value='2' selected>2</option>";}
									else{echo "<option value='2'>2</option>";}
									if($row['Niveau']==3){echo "<option value='3' selected>3</option>";}
										else{echo "<option value='3'>3</option>";}
									if($maxPoste == 4 || $maxPoste == 6 || $maxPoste == 7 || $maxPoste == 8 || $maxPoste == 9){
										if($row['Niveau']==4){echo "<option value='4' selected>4</option>";}
										else{echo "<option value='4'>4</option>";}
									}
								}
								else if($minPoste == 4 || $minPoste == 6 || $minPoste == 7){
									if($row['Niveau']==2){echo "<option value='2' selected>2</option>";}
									else{echo "<option value='2'>2</option>";}
									if($row['Niveau']==3){echo "<option value='3' selected>3</option>";}
									else{echo "<option value='3'>3</option>";}
									if($row['Niveau']==4){echo "<option value='4' selected>4</option>";}
									else{echo "<option value='4'>4</option>";}
								}
								else if($minPoste == 8 || $minPoste == 9){
									if($row['Niveau']==3){echo "<option value='3' selected>3</option>";}
									else{echo "<option value='3'>3</option>";}
									if($row['Niveau']==4){echo "<option value='4' selected>4</option>";}
									else{echo "<option value='4'>4</option>";}
								}
							}
							echo "<script>minPoste = ".$minPoste.";</script>\n";
							echo "<script>maxPoste = ".$maxPoste.";</script>\n";
						}
						else{
							$reqDroit = "SELECT Id_Prestation, Id_Pole, Id_Poste FROM  new_competences_personne_poste_prestation WHERE Id_Personne=".$IdPersonne;		
							$resultDroit=mysqli_query($bdd,$reqDroit);
							$nbDroit=mysqli_num_rows($resultDroit);
							$i=0;
							if ($nbDroit > 0){
								while($row=mysqli_fetch_array($resultDroit)){
									echo "<script>Liste_Niveau[".$i."] = new Array(".$row['Id_Prestation'].",".$row['Id_Pole'].",".$row['Id_Poste'].");</script>\n";
									$i+=1;
								}
							 }
						}
					?>
						</select>
						</div>
					</td>
					<?php
						echo "<td>&nbsp; N° : ";
						echo $row['Id'];
						echo "</td>";
					?>
				</tr>
				<tr>
					<td><br></td>
				</tr>
				<tr>
					<td width=8%>
						&nbsp; Problème : 
					</td>
					<td colspan="7">
						<?php
							$valPb="";
							if ($nbAction>0){
									$valPb=$row['Probleme'];
							}
						?>
						<input type="text" style="text-align:left;" id="probleme" name="probleme" <?php echo $bloquer; ?> size="150" value="<?php echo $valPb;?>">
					</td>
				</tr>
				<tr id="ligneAction1">
					<td><br></td>
				</tr>
				<tr id="ligneAction2">
					<td width=8%>
						&nbsp; Action : 
					</td>
					<td colspan="7">
						<?php
							$valAction="";
							if ($nbAction>0){
								$valAction=$row['Action'];
							}
						?>
						<input type="text" style="text-align:left;" id="action" name="action"  size="150" value="<?php echo $valAction;?>">
					</td>
				</tr>
				<tr>
					<td><br></td>
				</tr>
				<tr>
					<td width=8%>
						&nbsp; Commentaire : 
					</td>
					<td colspan="7">
						<?php
							$valCommentaire="";
							if ($nbAction>0){
									$valCommentaire=$row['Commentaire'];
							}
						?>
						<textarea <?php echo $bloquer; ?> style="text-align:left;" id="commentaire" name="commentaire" cols="155" rows="3"><?php echo $valCommentaire;?></textarea>
					</td>
				</tr>
				<tr>
					<td><br></td>
				</tr>
				<tr>
					<td width=8%>
						&nbsp; Situation <br> &nbsp; avancement : 
					</td>
					<td colspan="7">
						<?php
							$valSituationAvancement="";
							if ($nbAction>0){
									$valSituationAvancement=$row['SituationAvancement'];
							}
						?>
						<textarea style="text-align:left;" id="situationAvancement" name="situationAvancement" cols="155" rows="3"><?php echo $valSituationAvancement;?></textarea>
					</td>
				</tr>
				<tr id="ligneDelais1">
					<td><br></td>
				</tr>
				<tr id="ligneDelais2">
					<td width=8%>
						&nbsp; Délais :
					</td>
					<td>
						<?php
							$valDelais="";
							if ($nbAction>0){
								if($row['Delais'] > '0001-01-01'){$valDelais=AfficheDateFR($row['Delais']);}
							}
						?>
						<input type="date" style="text-align:left;" id="delais" name="delais" size="10" value="<?php echo $valDelais;?>">
					</td>
				</tr>
				<tr id="ligneAvancement1">
					<td><br></td>
				</tr>
				<tr id="ligneAvancement2">
					<td width=10%>
						&nbsp; Repris par <br> &nbsp;le D-0601 : 
					</td>
					<td width=25%>
						<?php
							$selectOui="";
							$selectNon="";
							if ($nbAction>0){
								if($row['ReprisDQ506']==1){$selectOui="selected";}
								elseif($row['ReprisDQ506']==2){$selectNon="selected";}
							}
						?>
						<select name="repris" id="repris">
							<option value="1" <?php echo $selectOui;?>>Oui</option>
							<option value="2" <?php echo $selectNon;?>>Non</option>
						</select>
					</td>
					<td width=8%>
						&nbsp; Avancement : 
					</td>
					<td>
						<?php
							$select1="";
							$select2="";
							$select3="";
							$select4="";
							$select5="";
							$select6="";
							if ($nbAction>0){
								if($row['Avancement']<=1){$select1="selected";}
								elseif($row['Avancement']==2){$select2="selected";}
								elseif($row['Avancement']==3){$select3="selected";}
								elseif($row['Avancement']==4){$select4="selected";}
								elseif($row['Avancement']==5){$select5="selected";}
								elseif($row['Avancement']==6){$select6="selected";}
							}
						?>
						<select id="avancement" name="avancement" onchange="AfficherAvancement();">
							<option value="1" <?php echo $select1;?>>Point pris en compte</option>
							<option value="2" <?php echo $select2;?>>Point e/c</option>
							<option value="3" <?php echo $select3;?>>Solution/Action</option>
							<option value="4" <?php echo $select4;?>>Action clôturée</option>
							<option id="Avancement5" value="5" <?php echo $select5;?>>Poursuivre N+1</option>
							<option id="Avancement6" value="6" <?php echo $select6;?>>Poursuivre N-1</option>
						</select>
						<div id="ImgAvancement" style="display:inline;">
							<?php
							if ($nbAction>0){
								if($row['Avancement']<=1){echo "<img src='../../Images/EnCompte.gif' border='0' alt='EnCompte' title='En compte'>";}
								elseif($row['Avancement']==2){echo "<img src='../../Images/EnCours.gif' border='0' alt='EnCours' title='En cours'>";}
								elseif($row['Avancement']==3){echo "<img src='../../Images/Solution.gif' border='0' alt='Solution' title='Solution/action'>";}
								elseif($row['Avancement']>=4){echo "<img src='../../Images/Cloturee.gif' border='0' alt='Cloturee' title='Cloturée'>";}
							}
							else{
								echo "<img src='../../Images/EnCompte.gif' border='0' alt='EnCompte' title='En compte'>";
							}
							?>
						</div>
					</td>
					<?php
						if ($nbAction>0){
							$display ="style='display:none;'";
							$dateCloture ="";
							if ($NavigOk ==1){$dateCloture = date("Y-m-d");}
							else{$dateCloture = date("d/m/Y");}
							if($row['Avancement']==4){$display ="";$dateCloture=AfficheDateFR($row['DateSolde']);}
							echo "<td width=8% id='titreCloture' ".$display.">";
							echo "&nbsp; Date de clôture : ";
							echo "</td>";
							echo "<td id='corpsCloture' ".$display.">";
							echo "<input type='date' style='text-align:left;' id='dateCloture' name='dateCloture' size='10' value=".$dateCloture.">";
							echo "</td>";
						}
						else{
					?>
						<td width=8% id="titreCloture" style="display:none;">
							&nbsp; Date de clôture : 
						</td>

						<td id="corpsCloture" style="display:none;">
							<input type="date" style="text-align:left;" id="dateCloture" name="dateCloture" size="10" value="<?php if ($NavigOk ==1){echo date("Y-m-d");}else{echo date("d/m/Y");}?>">
						</td>
					<?php
						}
					?>
				</tr>
				<tr id="ligneN0">
					<td><br></td>
				</tr>
				<tr id="ligneN1">
					<td width=8%>
						&nbsp; Commentaire : 
					</td>
					<td colspan="7">
						<textarea style="text-align:left;" id="commentaireN" name="commentaireN" cols="155" rows="5"></textarea>
					</td>
				</tr>
				<tr id="ligneN2">
					<td><br></td>
				</tr>
				<tr id="ligneN3">
					<td width=8%>
						&nbsp; Action : 
					</td>
					<td colspan="7">
						<input type="text" style="text-align:left;" id="actionN" name="actionN"  size="150" value="">
					</td>
				</tr>
				<tr id="ligneN4">
					<td><br></td>
				</tr>
				<tr id="ligneN5">
					<td width=8%>
						&nbsp; Délais :
					</td>
					<td>
						<input type="date" style="text-align:left;" id="delaisN" name="delaisN" size="10" value="">
					</td>
				</tr>
				<tr><td height="8" colspan="7"></td></tr>
				<tr align="center" id="btnValider">
					<td colspan="7" align="center" align="center">
						<div>
							<input  class="Bouton" name="submitValider" type="submit" value='Valider'>
						</div>
					</td>
				</tr>
			</table>
		</td></tr>
	</table>
</form>

<?php
	if ($nbAction>0){echo "<script>AfficherChamps(1);</script>";}
	else{echo "<script>AfficherChamps(0);</script>";}
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
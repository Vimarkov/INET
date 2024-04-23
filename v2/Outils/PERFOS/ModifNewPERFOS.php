<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Perfos.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
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

<script type="text/javascript" src="ModifSQCDPF.js"></script>

<?php
require_once("../Connexioni.php");
require_once("../Fonctions.php");

if(isset($_POST['submitValider'])){
	if($_POST['Idnew_perfos'] == 0){
		$ldate = "";
		if ($NavigOk ==1){
			$dateperfos = $_POST['DateSQCDPF'];
			$tabdateperfos = explode('-', $dateperfos);
			$timestampDate = mktime(0, 0, 0, $tabdateperfos[1], $tabdateperfos[2], $tabdateperfos[0]);
			$ldate = date("Y-m-d",$timestampDate);
		}
		else{
			$dateperfos = $_POST['DateSQCDPF'];
			$tabdateperfos = explode('/', $dateperfos);
			$timestampDate = mktime(0, 0, 0, $tabdateperfos[1], $tabdateperfos[0], $tabdateperfos[2]);
			$ldate = date("Y-m-d",$timestampDate);
		}
		//Ajout du SQCDPF
		$Pole = 0;
		$lmaintenant = date("Y-m-d h:i:s");
		if (!empty($_POST['pole'])){$Pole =$_POST['pole'];}
		
		$requeteInsert="INSERT INTO new_v2sqcdpf (Id_Prestation, Id_Pole, DateSQCDPF, Vacation, Id_Personne1, S_J_1, Q_J_1, C_J_1, D_J_1, P_J_1, F_J_1, CommentaireS_J_1, CommentaireQ_J_1, CommentaireC_J_1, CommentaireD_J_1, CommentaireP_J_1, CommentaireF_J_1,DatePersonne1) ";
		$requeteInsert.=" VALUES ";
		$requeteInsert.=" (".$_POST['prestation'].",".$Pole.",'".$ldate."','".$_POST['vacation']."',".$_POST['Personne'].",";
		$requeteInsert.="".$_POST['noteS_J_1'].",".$_POST['noteQ_J_1'].",".$_POST['noteC_J_1'].",".$_POST['noteD_J_1'].",";
		$requeteInsert.="".$_POST['noteP_J_1'].",".$_POST['noteF_J_1'].",";
		$requeteInsert.="'".addslashes($_POST['CommentaireS_J_1'])."','".addslashes($_POST['CommentaireQ_J_1'])."','".addslashes($_POST['CommentaireC_J_1'])."','".addslashes($_POST['CommentaireD_J_1'])."',";
		$requeteInsert.="'".addslashes($_POST['CommentaireP_J_1'])."','".addslashes($_POST['CommentaireF_J_1'])."','".$lmaintenant."')";
		$resultAjout=mysqli_query($bdd,$requeteInsert);
		
		//Ajout des actions
		if ($_POST['noteS_J_1'] == 2 && $_POST['lettreS'] <> ""){
			$reqInsertAction = "INSERT INTO new_action (Type,Lettre,PointChaud,DateCreation,Vacation,Id_Createur,Probleme,Action,Id_Acteur,Delais,Niveau,Id_Prestation,Id_Pole,NiveauCreateur,Commentaire) VALUES ";
			$reqInsertAction .= "('SQCDPF','".$_POST['lettreS']."','','".$ldate."','".$_POST['vacation']."',".$_POST['Personne'].",'".addslashes($_POST['pbS'])."',";
			$personne = 0;
			$delais = "0001-01-01";
			if($_POST['niveauS'] == 1){
				$personne = $_POST['Personne'];
			}
			if($_POST['delaiS'] <> ""){$delais =$_POST['delaiS'];}
			$reqInsertAction .= "'".addslashes($_POST['actionS'])."',".$personne.",'".$delais."',";
			$reqInsertAction .= "'".addslashes($_POST['niveauS'])."',".$_POST['prestation'].",".$Pole.",1,'".addslashes($_POST['commentaireS'])."')";
			$resultActionS=mysqli_query($bdd,$reqInsertAction);
		}

		if ($_POST['noteQ_J_1'] == 2 && $_POST['lettreQ'] <> ""){
			$reqInsertAction = "INSERT INTO new_action (Type,Lettre,PointChaud,DateCreation,Vacation,Id_Createur,Probleme,Action,Id_Acteur,Delais,Niveau,Id_Prestation,Id_Pole,NiveauCreateur,Commentaire) VALUES ";
			$reqInsertAction .= "('SQCDPF','".$_POST['lettreQ']."','','".$ldate."','".$_POST['vacation']."',".$_POST['Personne'].",'".addslashes($_POST['pbQ'])."',";
			$personne = 0;
			$delais = "0001-01-01";
			if($_POST['niveauQ'] == 1){
				$personne = $_POST['Personne'];
			}
			if($_POST['delaiQ'] <> ""){$delais =$_POST['delaiQ'];}
			$reqInsertAction .= "'".addslashes($_POST['actionQ'])."',".$personne.",'".$delais."',";
			$reqInsertAction .= "'".addslashes($_POST['niveauQ'])."',".$_POST['prestation'].",".$Pole.",1,'".addslashes($_POST['commentaireQ'])."')";
			$resultActionQ=mysqli_query($bdd,$reqInsertAction);
		}

		if ($_POST['noteC_J_1'] == 2 && $_POST['lettreC'] <> ""){
			$reqInsertAction = "INSERT INTO new_action (Type,Lettre,PointChaud,DateCreation,Vacation,Id_Createur,Probleme,Action,Id_Acteur,Delais,Niveau,Id_Prestation,Id_Pole,NiveauCreateur,Commentaire) VALUES ";
			$reqInsertAction .= "('SQCDPF','".$_POST['lettreC']."','','".$ldate."','".$_POST['vacation']."',".$_POST['Personne'].",'".addslashes($_POST['pbC'])."',";
			$personne = 0;
			$delais = "0001-01-01";
			if($_POST['niveauC'] == 1){
				$personne = $_POST['Personne'];
			}
			if($_POST['delaiC'] <> ""){$delais =$_POST['delaiC'];}
			$reqInsertAction .= "'".addslashes($_POST['actionC'])."',".$personne.",'".$delais."',";
			$reqInsertAction .= "'".addslashes($_POST['niveauC'])."',".$_POST['prestation'].",".$Pole.",1,'".addslashes($_POST['commentaireC'])."')";
			$resultActionC=mysqli_query($bdd,$reqInsertAction);
		}

		if ($_POST['noteD_J_1'] == 2 && $_POST['lettreD'] <> ""){
			$reqInsertAction = "INSERT INTO new_action (Type,Lettre,PointChaud,DateCreation,Vacation,Id_Createur,Probleme,Action,Id_Acteur,Delais,Niveau,Id_Prestation,Id_Pole,NiveauCreateur,Commentaire) VALUES ";
			$reqInsertAction .= "('SQCDPF','".$_POST['lettreD']."','','".$ldate."','".$_POST['vacation']."',".$_POST['Personne'].",'".addslashes($_POST['pbD'])."',";
			$personne = 0;
			$delais = "0001-01-01";
			if($_POST['niveauD'] == 1){
				$personne = $_POST['Personne'];
			}
			if($_POST['delaiD'] <> ""){$delais =$_POST['delaiD'];}
			$reqInsertAction .= "'".addslashes($_POST['actionD'])."',".$personne.",'".$delais."',";
			$reqInsertAction .= "'".addslashes($_POST['niveauD'])."',".$_POST['prestation'].",".$Pole.",1,'".addslashes($_POST['commentaireD'])."')";
			$resultActionD=mysqli_query($bdd,$reqInsertAction);
		}
		
		if ($_POST['noteP_J_1'] == 2 && $_POST['lettreP'] <> ""){
			$reqInsertAction = "INSERT INTO new_action (Type,Lettre,PointChaud,DateCreation,Vacation,Id_Createur,Probleme,Action,Id_Acteur,Delais,Niveau,Id_Prestation,Id_Pole,NiveauCreateur,Commentaire) VALUES ";
			$reqInsertAction .= "('SQCDPF','".$_POST['lettreP']."','','".$ldate."','".$_POST['vacation']."',".$_POST['Personne'].",'".addslashes($_POST['pbP'])."',";
			$personne = 0;
			$delais = "0001-01-01";
			if($_POST['niveauP'] == 1){
				$personne = $_POST['Personne'];
			}
			if($_POST['delaiP'] <> ""){$delais =$_POST['delaiP'];}
			$reqInsertAction .= "'".addslashes($_POST['actionP'])."',".$personne.",'".$delais."',";
			$reqInsertAction .= "'".addslashes($_POST['niveauP'])."',".$_POST['prestation'].",".$Pole.",1,'".addslashes($_POST['commentaireP'])."')";
			$resultActionP=mysqli_query($bdd,$reqInsertAction);
		}
		
		if ($_POST['noteF_J_1'] == 2 && $_POST['lettreF'] <> ""){
			$reqInsertAction = "INSERT INTO new_action (Type,Lettre,PointChaud,DateCreation,Vacation,Id_Createur,Probleme,Action,Id_Acteur,Delais,Niveau,Id_Prestation,Id_Pole,NiveauCreateur,Commentaire) VALUES ";
			$reqInsertAction .= "('SQCDPF','".$_POST['lettreF']."','','".$ldate."','".$_POST['vacation']."',".$_POST['Personne'].",'".addslashes($_POST['pbF'])."',";
			$personne = 0;
			$delais = "0001-01-01";
			if($_POST['niveauF'] == 1){
				$personne = $_POST['Personne'];
			}
			if($_POST['delaiF'] <> ""){$delais =$_POST['delaiF'];}
			$reqInsertAction .= "'".addslashes($_POST['actionF'])."',".$personne.",'".$delais."',";
			$reqInsertAction .= "'".addslashes($_POST['niveauF'])."',".$_POST['prestation'].",".$Pole.",1,'".addslashes($_POST['commentaireF'])."')";
			$resultActionF=mysqli_query($bdd,$reqInsertAction);
		}
		
		//Créer les actions supplémentaires
		if($_POST['actionSupp'] <> ""){
			$ListeAction = explode(";",$_POST['actionSupp']);
			foreach ($ListeAction as $value) {
				if($value<>""){
					$reqInsertAction = "INSERT INTO new_action (Type,Lettre,PointChaud,DateCreation,Vacation,Id_Createur,Probleme,Action,Id_Acteur,Delais,Niveau,Id_Prestation,Id_Pole,NiveauCreateur,Commentaire) VALUES ";
					$reqInsertAction .= "('SQCDPF','".$_POST['lettre'.$value]."','*','".$ldate."','".$_POST['vacation']."',".$_POST['Personne'].",'".addslashes($_POST['pb'.$value])."',";
					$personne = 0;
					$delais = "0001-01-01";
					if($_POST['niveau'.$value] == 1){
						$personne = $_POST['Personne'];
					}
					if($_POST['delai'.$value] <> ""){$delais =$_POST['delai'.$value];}
					$reqInsertAction .= "'".addslashes($_POST['action'.$value])."',".$personne.",'".$delais."',";
					$reqInsertAction .= "'".addslashes($_POST['niveau'.$value])."',".$_POST['prestation'].",".$Pole.",1,'".addslashes($_POST['commentaire'.$value])."')";
					$resultActionSupp=mysqli_query($bdd,$reqInsertAction);
				}
			}
		}
		
		$rqListe="SELECT new_rh_etatcivil.Id, new_rh_etatcivil.EmailPro FROM new_rh_etatcivil";
		$rqListe.=" LEFT JOIN new_sqcdpf_prestation_equipemail ON new_rh_etatcivil.Id=new_sqcdpf_prestation_equipemail.Id_Personne ";
		$rqListe.=" WHERE new_sqcdpf_prestation_equipemail.Id_Prestation=".$_POST['prestation']."";
		if (!empty($_POST['pole'])){$rqListe.=" AND new_sqcdpf_prestation_equipemail.Id_Pole=".$Pole."";}
		$resultpersonneListe=mysqli_query($bdd,$rqListe);
		
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
		
		$objetMail = "SQCDPF - ".$rowPresta['Libelle']." ".$NomPole." - " .$ldate." - ".$_POST['vacation'];
		
		//Ajouter le coordinateur projet + Référence qualité produit
		$requeteResponsablePostePrestation="SELECT DISTINCT new_rh_etatcivil.EmailPro ";
		$requeteResponsablePostePrestation.=" FROM new_competences_personne_poste_prestation LEFT JOIN new_rh_etatcivil ON new_competences_personne_poste_prestation.Id_Personne = new_rh_etatcivil.Id ";
		$requeteResponsablePostePrestation.=" WHERE new_competences_personne_poste_prestation.Id_Prestation=".$_POST['prestation'];
		if ($Pole > 0){
			$requeteResponsablePostePrestation.=" AND new_competences_personne_poste_prestation.Id_Pole=".$Pole;
		}
		$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=1 OR new_competences_personne_poste_prestation.Id_Poste=2 OR new_competences_personne_poste_prestation.Id_Poste=3 OR new_competences_personne_poste_prestation.Id_Poste=5 OR new_competences_personne_poste_prestation.Id_Poste=35) ";
		$resultResponsablePostePrestation=mysqli_query($bdd,$requeteResponsablePostePrestation);
		
		$reqAct="SELECT new_action.Lettre, new_action.Probleme, new_action.Action, new_action.Delais, new_action.Niveau, ";
		$reqAct.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=new_action.Id_Acteur) AS Responsable, new_action.Commentaire ";
		$reqAct.="FROM new_action WHERE new_action.DateCreation='".$ldate."' AND new_action.Id_Prestation=".$_POST['prestation']." AND new_action.Id_Pole=".$Pole."";
		
	}
	else{
		//Modif du SQCDPF
		$lmaintenant = date("Y-m-d H:i:s");
	
		$requeteUpdate="UPDATE new_v2sqcdpf SET ";
		$requeteUpdate.= "Id_Personne1= ".$_POST['Personne'].", ";
		$requeteUpdate.= "DatePersonne1= '".$lmaintenant."', ";
		$requeteUpdate.= "S_J_1= ".$_POST['noteS_J_1'].", ";
		$requeteUpdate.= "Q_J_1= ".$_POST['noteQ_J_1'].", ";
		$requeteUpdate.= "C_J_1= ".$_POST['noteC_J_1'].", ";
		$requeteUpdate.= "D_J_1= ".$_POST['noteD_J_1'].", ";
		$requeteUpdate.= "P_J_1= ".$_POST['noteP_J_1'].", ";
		$requeteUpdate.= "F_J_1= ".$_POST['noteF_J_1'].", ";
		$requeteUpdate.= "CommentaireS_J_1= '".addslashes($_POST['CommentaireS_J_1'])."', ";
		$requeteUpdate.= "CommentaireQ_J_1= '".addslashes($_POST['CommentaireQ_J_1'])."', ";
		$requeteUpdate.= "CommentaireC_J_1= '".addslashes($_POST['CommentaireC_J_1'])."', ";
		$requeteUpdate.= "CommentaireD_J_1= '".addslashes($_POST['CommentaireD_J_1'])."', ";
		$requeteUpdate.= "CommentaireP_J_1= '".addslashes($_POST['CommentaireP_J_1'])."', ";
		$requeteUpdate.= "CommentaireF_J_1= '".addslashes($_POST['CommentaireF_J_1'])."' ";
		$requeteUpdate.= " WHERE Id =".$_POST['Idnew_perfos'].";";
		
		$resultModif=mysqli_query($bdd,$requeteUpdate);
		
		$reqSelect = "SELECT Id_Prestation, Id_Pole, DateSQCDPF, Vacation FROM new_v2sqcdpf WHERE Id=".$_POST['Idnew_perfos'];
		$resultSelect=mysqli_query($bdd,$reqSelect);
		$rowSelect = mysqli_fetch_array($resultSelect);
		
		$rqListe="SELECT new_rh_etatcivil.Id, new_rh_etatcivil.EmailPro FROM new_rh_etatcivil";
		$rqListe.=" LEFT JOIN new_sqcdpf_prestation_equipemail ON new_rh_etatcivil.Id=new_sqcdpf_prestation_equipemail.Id_Personne ";
		$rqListe.=" WHERE new_sqcdpf_prestation_equipemail.Id_Prestation=".$rowSelect['Id_Prestation']."";
		$rqListe.=" AND new_sqcdpf_prestation_equipemail.Id_Pole=".$rowSelect['Id_Pole']."";
		$resultpersonneListe=mysqli_query($bdd,$rqListe);
		
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
		
		$objetMail = "SQCDPF modifié - ".$rowPresta['Libelle']." ".$NomPole." - " .$ldate." - ".$rowSelect['Vacation'];
		
		$reqAct="SELECT new_action.Lettre, new_action.Probleme, new_action.Action, new_action.Delais, new_action.Niveau, ";
		$reqAct.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=new_action.Id_Acteur) AS Responsable, new_action.Commentaire ";
		$reqAct.="FROM new_action WHERE new_action.DateCreation='".$rowSelect['DateSQCDPF']."' AND new_action.Id_Prestation=".$rowSelect['Id_Prestation']." AND new_action.Id_Pole=".$rowSelect['Id_Pole'];
		
		//Ajouter le coordinateur projet + Référence qualité produit
		$requeteResponsablePostePrestation="SELECT DISTINCT new_rh_etatcivil.EmailPro ";
		$requeteResponsablePostePrestation.=" FROM new_competences_personne_poste_prestation LEFT JOIN new_rh_etatcivil ON new_competences_personne_poste_prestation.Id_Personne = new_rh_etatcivil.Id ";
		$requeteResponsablePostePrestation.=" WHERE new_competences_personne_poste_prestation.Id_Prestation=".$rowSelect['Id_Prestation'];
		if ($rowSelect['Id_Pole'] > 0){
			$requeteResponsablePostePrestation.=" AND new_competences_personne_poste_prestation.Id_Pole=".$rowSelect['Id_Pole'];
		}
		$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=1 OR new_competences_personne_poste_prestation.Id_Poste=2 OR new_competences_personne_poste_prestation.Id_Poste=3 OR new_competences_personne_poste_prestation.Id_Poste=5 OR new_competences_personne_poste_prestation.Id_Poste=35) ";
		$resultResponsablePostePrestation=mysqli_query($bdd,$requeteResponsablePostePrestation);
	}
	
	//Envoyer le SQCDPF
	$Destinataires = "";
	while($rowListe = mysqli_fetch_array($resultpersonneListe)){
		if($rowListe['EmailPro']<>""){
			$Destinataires .= $rowListe['EmailPro'].",";
		}
	}
	
	while($rowResp = mysqli_fetch_array($resultResponsablePostePrestation)){
		if($rowListe['EmailPro']<>""){
			$Destinataires .= $rowResp['EmailPro'].",";
		}
	}
	$Destinataires = substr($Destinataires,0,-1);
	$headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
	$headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";

	$message='<html>';
	$message.='<head>';
	$message.='<title>SQCDPF</title>';
	$message.='</head><body>Bonjour,<br><br>';
	
	$message.="<table width='90%' cellpadding='0' cellspacing='0' align='left'>";
	$message.="<tr><td>";
	$message.="<table width='100%' cellpadding='0' cellspacing='0' align='left'> \n";
	$message.="<tr><td height='4'></td></tr> \n";
	$message.="<tr> \n";
	$message.="<td width='10%' Style='border:thin solid #000000;background:#c5d9f1;' align='center'>Lettre</td> \n";
	$message.="<td width='90%' Style='border:thin solid #000000;background:#c5d9f1;' align='center'>Commentaire</td> \n";
	$message.="</tr> \n";
	
	//---------------S------------------//	
	$message.="<tr align='center'> \n";
	if ($_POST['noteS_J_1'] == "1"){$message.="<td Style='border:thin solid #000000;' width='10%' bgcolor='#e2fdbd'>S</td> \n";}
	else{$message.="<td Style='border:thin solid #000000;' bgcolor='#ffafae'>S</td> \n";}
	$message.="<td Style='border:thin solid #000000;' width='90%'>".$_POST['CommentaireS_J_1']."</td> \n";
	$message.="</tr>";
	
	//---------------Q------------------//
	$message.="<tr align='center'> \n";
	if ($_POST['noteQ_J_1'] == "1"){$message.="<td Style='border:thin solid #000000;' width='10%' bgcolor='#e2fdbd'>Q</td> \n";}
	else{$message.="<td Style='border:thin solid #000000;' bgcolor='#ffafae'>Q</td> \n";}
	$message.="<td Style='border:thin solid #000000;' width='90%'>".$_POST['CommentaireQ_J_1']."</td> \n";
	$message.="</tr>";
	
	//---------------C------------------//
	$message.="<tr align='center'> \n";
	if ($_POST['noteC_J_1'] == "1"){$message.="<td Style='border:thin solid #000000;' width='10%' bgcolor='#e2fdbd'>C</td> \n";}
	else{$message.="<td Style='border:thin solid #000000;' bgcolor='#ffafae'>C</td> \n";}
	$message.="<td Style='border:thin solid #000000;' width='90%'>".$_POST['CommentaireC_J_1']."</td> \n";
	$message.="</tr>";
	
	//---------------D------------------//
	$message.="<tr align='center'> \n";
	if ($_POST['noteD_J_1'] == "1"){$message.="<td Style='border:thin solid #000000;' width='10%' bgcolor='#e2fdbd'>D</td> \n";}
	else{$message.="<td Style='border:thin solid #000000;' bgcolor='#ffafae'>D</td> \n";}
	$message.="<td Style='border:thin solid #000000;' width='90%'>".$_POST['CommentaireD_J_1']."</td> \n";
	$message.="</tr>";
	
	//---------------P------------------//
	$message.="<tr align='center'> \n";
	if ($_POST['noteP_J_1'] == "1"){$message.="<td Style='border:thin solid #000000;' width='10%' bgcolor='#e2fdbd'>P</td> \n";}
	else{$message.="<td Style='border:thin solid #000000;' bgcolor='#ffafae'>P</td> \n";}
	$message.="<td Style='border:thin solid #000000;' width='90%'>".$_POST['CommentaireP_J_1']."</td> \n";
	$message.="</tr>";
	
	//---------------F------------------//
	$message.="<tr align='center'> \n";
	if ($_POST['noteF_J_1'] == "1"){$message.="<td Style='border:thin solid #000000;' width='10%' bgcolor='#e2fdbd' align='center'>F</td> \n";}
	else{$message.="<td Style='border:thin solid #000000;' bgcolor='#ffafae' align='center'>F</td> \n";}
	$message.="<td Style='border:thin solid #000000;' width='90%' align='center'>".$_POST['CommentaireF_J_1']."</td> \n";
	$message.="</tr>";
	
	//---------Créateur du SQCDPF--------//
	$reqCreateur="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$_POST['Personne'];
	$resultCreateur=mysqli_query($bdd,$reqCreateur);
	$rowCreateur = mysqli_fetch_array($resultCreateur);
	$message.="<tr><td colspan='6' align='left'>Créateur du SQCDPF : ".$rowCreateur['Nom']." ".$rowCreateur['Prenom']."</td></tr> \n";
	$message.="</table> \n";
	$message.="</td></tr>";
	$message.="<tr><td></br><br/></td></tr>";
	$message.="<tr><td>";
	$message.="<table width='100%' cellpadding='0' cellspacing='0' align='left'> \n";
	$message.="<tr><td align='left'></td></tr> \n";
	$message.="<tr><td colspan='6' align='left'>Points chauds</td></tr> \n";
	$message.="<tr bgcolor='#bacfea'>";
	$message.="<td align='center'>Niveau</td>";
	$message.="<td align='center'>Lettre</td>";
	$message.="<td align='center'>Description du problème</td>";
	$message.="<td align='center'>Commentaire</td>";
	$message.="<td align='center'>Description action</td>";
	$message.="<td align='center'>Responsable action</td>";
	$message.="<td align='center'>Délai</td>";
	$message.="</tr>";
	
	$couleur = "#eef3fa";
	
	$resulAction=mysqli_query($bdd,$reqAct);
	$nbAction=mysqli_num_rows($resulAction);
	if ($nbAction>0){
		while($rowAction=mysqli_fetch_array($resulAction)){
			if ($couleur == "#eef3fa"){$couleur = "#ffffff";}
			else{$couleur = "#eef3fa";}
			$message.="<tr bgcolor='".$couleur."'>";
			$message.="<td align='center'>".$rowAction['Niveau']."</td>";
			$message.="<td align='center'>".$rowAction['Lettre']."</td>";
			$message.="<td align='center'>".$rowAction['Probleme']."</td>";
			$message.="<td align='center'>".$rowAction['Commentaire']."</td>";
			$message.="<td align='center'>".$rowAction['Action']."</td>";
			$message.="<td align='center'>".$rowAction['Responsable']."</td>";
			if($rowAction['Delais'] > "0001-01-01"){
				$message.="<td align='center'>".$rowAction['Delais']."</td>";
			}
			else{
				$message.="<td align='center'></td>";
			}
			$message.="</tr>";
		}
	}					
	$message.="</table> \n";
	$message.="</td></tr>";
	
	$message.="<tr><td>";
	//Commentaire général
	$message.="<table> \n";
	$message.="<tr><td  align='left'><br/><br/></td></tr> \n";
	$message.="<tr><td align='left'>Bonne journée</td></tr> \n";
	$message.="<tr><td align='left'><a href='https://extranet.aaa-aero.com'>Extranet</a></td></tr> \n";
	$message.="</table> \n";
	$message.="</td></tr>";
	$message.="</table> \n";
	$message.='</body></html>';
	
	if($Destinataires<>""){
		if(mail($Destinataires, $objetMail , $message, $headers,'-f extranet@aaa-aero.com')){echo '';}
		else{echo 'Le message n\'a pu être envoyé';}
	}
	echo "<script>FermerEtRecharger(".$_POST['IdPrestationSelect'].",".$_POST['IdPoleSelect'].",".$_POST['DateSelect'].");</script>";
}

if ($_GET){
	$IdPersonne = $_GET['Id_Personne'];
	$IdPrestationSelect = $_GET['Id_Prestation'];
	$IdPoleSelect = $_GET['Id_Pole'];
	$DateSelect = $_GET['dateEnvoi'];
	$Idnew_perfos = 0;
	if($_GET['Mode'] == "M"){
		$Idnew_perfos = $_GET['Id_perfos'];
	}
	elseif($_GET['Mode'] == "S"){
		$Idnew_perfos = $_GET['Id_perfos'];
		$reqSuppr="DELETE FROM new_v2sqcdpf WHERE Id=".$Idnew_perfos;
		$resultSuppr=mysqli_query($bdd,$reqSuppr);
		echo "<script>FermerEtRecharger(".$IdPrestationSelect.",".$IdPoleSelect.",".$DateSelect.");</script>";
		
	}
	
	$req = "SELECT new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom FROM new_rh_etatcivil WHERE Id=".$IdPersonne;
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	if ($nb>0){
		$row=mysqli_fetch_array($result);
		echo "<script>resp_Action = '".$row['Nom']." ".$row['Prenom']."';</script>\n";
	}
	
	$req = "SELECT new_v2sqcdpf.Id, new_v2sqcdpf.DateSQCDPF, Vacation, new_v2sqcdpf.Id_Prestation, new_v2sqcdpf.Id_Pole, new_v2sqcdpf.Id_Personne1, new_v2sqcdpf.Id_Personne2, new_v2sqcdpf.Id_Personne3, new_v2sqcdpf.Id_Personne4, ";
	$req .= "(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id = new_v2sqcdpf.Id_Prestation) AS Prestation, ";
	$req .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id =new_v2sqcdpf.Id_Pole) AS Pole, ";
	$req .= "new_v2sqcdpf.S_J_1, new_v2sqcdpf.Q_J_1, new_v2sqcdpf.C_J_1, new_v2sqcdpf.D_J_1, new_v2sqcdpf.P_J_1, new_v2sqcdpf.F_J_1, new_v2sqcdpf.CommentaireS_J_1, new_v2sqcdpf.CommentaireQ_J_1, new_v2sqcdpf.CommentaireC_J_1, ";
	$req .="new_v2sqcdpf.CommentaireD_J_1, new_v2sqcdpf.CommentaireP_J_1, new_v2sqcdpf.CommentaireF_J_1, new_v2sqcdpf.CommentaireGeneral ";
	$req .= "FROM new_v2sqcdpf ";
	$req .= "WHERE new_v2sqcdpf.Id =".$Idnew_perfos.";";
		
	$resultnew_perfos=mysqli_query($bdd,$req);
	$nbnew_perfos=mysqli_num_rows($resultnew_perfos);
	
	if ($nbnew_perfos>0){
		$row=mysqli_fetch_array($resultnew_perfos);
		$prestation = $row['Prestation'];
		$pole = $row['Pole'];
		$uneDate = $row['DateSQCDPF'];
	}
}
?>
<form class="test" name="formSQCDPF" method="POST" action="ModifNewPERFOS.php" onSubmit="return VerifChamps();">
	<table width="100%" cellpadding="0" cellspacing="0" align="center">
		<tr>
			<td>
				<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#6EB4CD;">
					<tr>
						<td width="4"></td>
						<td class="TitrePage">SQCDPF # Ajouter un SQCDPF</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td height="4"/></tr>
		<tr><td>
			<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr>
					<td width=30%>
						&nbsp; Prestation :
						<?php
						if ($nbnew_perfos>0){
							echo $row['Prestation'];
						}
						else{
						?>
							<select id="prestation" name="prestation" onchange="Recharge_Liste_Pole();">
							<?php
							$req = "SELECT distinct(new_competences_personne_poste_prestation.Id_Prestation), (SELECT LEFT(Libelle,7) FROM new_competences_prestation ";
							$req .= "WHERE new_competences_prestation.Id = new_competences_personne_poste_prestation.Id_Prestation) AS NomPrestation FROM new_competences_personne_poste_prestation WHERE ";
							$req .= "new_competences_personne_poste_prestation.Id_Personne=".$IdPersonne." and (new_competences_personne_poste_prestation.Id_Poste <3 OR new_competences_personne_poste_prestation.Id_Poste =35) ORDER BY NomPrestation;";
							$i=0;
							$resultPrestation=mysqli_query($bdd,$req);
							$nbPrestation=mysqli_num_rows($resultPrestation);
							
							$reqPerfos = "SELECT new_v2sqcdpf.DateSQCDPF, new_v2sqcdpf.Id_Prestation, new_v2sqcdpf.Id_Pole,Vacation ";
							$reqPerfos .= "FROM new_v2sqcdpf ";
							
							$reqCoorPro = "SELECT new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom, new_competences_personne_poste_prestation.Id_Personne, ";
							$reqCoorPro .= "new_competences_personne_poste_prestation.Id_Prestation, new_competences_personne_poste_prestation.Id_Pole ";
							$reqCoorPro .= "FROM new_competences_personne_poste_prestation LEFT JOIN new_rh_etatcivil ";
							$reqCoorPro .= "ON new_competences_personne_poste_prestation.Id_Personne = new_rh_etatcivil.Id ";
							$reqCoorPro .= "WHERE (new_competences_personne_poste_prestation.Id_Poste = 3 OR new_competences_personne_poste_prestation.Id_Poste = 5) ";
							if ($nbPrestation > 0){
								$reqPerfos .= "WHERE ";
								$reqCoorPro .= "AND ( ";
								echo "<option value='0' selected></option>";
								while($rowPresta=mysqli_fetch_array($resultPrestation)){
									echo "<option value='".$rowPresta[0]."'>".$rowPresta[1]."</option>";
									$reqPerfos.=" new_v2sqcdpf.Id_Prestation=".$rowPresta[0]." OR ";
									$reqCoorPro.=" new_competences_personne_poste_prestation.Id_Prestation=".$rowPresta[0]." OR ";
								}
								
								$reqPerfos=substr($reqPerfos,0,-3);
								$reqCoorPro=substr($reqCoorPro,0,-3);
								$reqCoorPro .= ") ";
							 }
							 
							
							$resultPERFOS=mysqli_query($bdd,$reqPerfos);
							$nbPERFOS=mysqli_num_rows($resultPERFOS);
							if ($nbPERFOS>0){
								$i=0;
								while($rowPERFOS=mysqli_fetch_array($resultPERFOS)){
									if ($NavigOk ==1){
										$laDate=$rowPERFOS['DateSQCDPF'];
									}
									else{
										$tabdateperfos = explode('-', $rowPERFOS['DateSQCDPF']);
										$timestampDate = mktime(0, 0, 0, $tabdateperfos[1], $tabdateperfos[2], $tabdateperfos[0]);
										$laDate=date("d/m/Y",$timestampDate);
									}
									echo "<script>Liste_PERFOS[".$i."] = new Array('".$laDate."',".$rowPERFOS['Id_Prestation'].",".$rowPERFOS['Id_Pole'].",'".$rowPERFOS['Vacation']."');</script>\n";
									$i+=1;
								}
							}
							 ?>
							</select>
						<?php
							$resultCoorPro=mysqli_query($bdd,$reqCoorPro);
							$nbCoorPro=mysqli_num_rows($resultCoorPro);
							if ($nbCoorPro>0){
								$i=0;
								while($rowCoorPro=mysqli_fetch_array($resultCoorPro)){
									echo "<script>ListeCoordinateurProjet[".$i."] = new Array('".$rowCoorPro['Nom']."','".$rowCoorPro['Prenom']."',".$rowCoorPro['Id_Prestation'].",".$rowCoorPro['Id_Pole'].");</script>\n";
									$i+=1;
								}
							}
						}
						?>
					</td>
					<td width=15%>
						<div id="pole">
						&nbsp; Pôle :
						<?php 
						if ($nbnew_perfos>0){
							echo $row['Pole'];
						}
						else{
						?>
							<select size="1" id="poles" name="pole" onchange="Rechercher_PERFOS();">
							<option value="0" selected></option>
							</select>
							<?php
							$reqPole = "SELECT distinct new_competences_personne_poste_prestation.Id_Pole, ";
							$reqPole .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id = new_competences_personne_poste_prestation.Id_Pole) AS LibellePole, ";
							$reqPole .= "new_competences_personne_poste_prestation.Id_Prestation AS Id_Prestation ";
							$reqPole .= "FROM new_competences_personne_poste_prestation WHERE ";
							$reqPole .= "new_competences_personne_poste_prestation.Id_Personne=".$IdPersonne." AND (new_competences_personne_poste_prestation.Id_Poste <3 OR new_competences_personne_poste_prestation.Id_Poste =35) ORDER BY LibellePole;";
							
							$resultPole=mysqli_query($bdd,$reqPole);
							$nbPole=mysqli_num_rows($resultPole);
							$i=0;
							if ($nbPole > 0){
								while($rowPole=mysqli_fetch_array($resultPole)){
									echo "<script>Liste_Pole_Prestation[".$i."] = new Array(".$rowPole['Id_Pole'].",".$rowPole['Id_Prestation'].",'".addslashes($rowPole['LibellePole'])."');</script>\n";
									$i+=1;
								}
							 }
							}
						?>
						</div>
					</td>
					<td width=20%>
						&nbsp;
						Date :
						<?php 
						if ($nbnew_perfos>0){
							echo AfficheDateJJ_MM_AAAA($row['DateSQCDPF']);
						}
						else{
							if ($NavigOk ==1){
								$dateperfos = date("Y-m-d");
								$tabdateperfos = explode('-', $dateperfos);
								$timestampDate = mktime(0, 0, 0, $tabdateperfos[1], $tabdateperfos[2], $tabdateperfos[0]);
								$dateEnvoi = $timestampDate;

							}
							else{
								$dateperfos = date("d/m/Y");
								$tabdateperfos = explode('/', $dateperfos);
								$timestampDate = mktime(0, 0, 0, $tabdateperfos[1], $tabdateperfos[0], $tabdateperfos[2]);
								$dateEnvoi = $timestampDate;
							}
						?>
							<input onchange="Rechercher_PERFOS();" type="date" id="datePERFOS" style="text-align:center;" name="DateSQCDPF" size="10" value="<?php echo $dateperfos; ?>">
						<?php
						}
						?>
					</td>
					<td width=15%>
						<div id="vacations">
						&nbsp; Vacation :
						<?php 
						if ($nbnew_perfos>0){
							echo $row['Vacation'];
						}
						else{
						?>
							<select id="vacation" name="vacation" onchange="Rechercher_PERFOS();">
								<option value="Jour" selected>Jour</option>
								<option value="Soir">Soir</option>
								<option value="Nuit">Nuit</option>
							</select>
						</div>
						<?php 
						}
						?>
					</td>
					<td width=10%>
						<div id="aide"></div>
					</td>
				</tr>
			</table>
		</td></tr>
		<tr><td height="4"></td></tr>
		<tr><td>
			<table  class="TableCompetences" width="100%" cellpadding="0" cellspacing="0" align="center">
				<tr>
					<td class="TitreSousPagePERFOS" >Reporting SQCDPF J-1/S-1</td>
				</tr>
				<tr style="display:none;">
					<td><input type="text" name="Personne" size="11" value="<?php echo $IdPersonne; ?>"></td>
					<td><input type="text" name="noteS_J_1" id="noteS_J_1" size="11" value="<?php if ($nbnew_perfos>0){echo $row['S_J_1'];} ?>"></td>
					<td><input type="text" name="noteQ_J_1" id="noteQ_J_1" size="11" value="<?php if ($nbnew_perfos>0){echo $row['Q_J_1'];} ?>"></td>
					<td><input type="text" name="noteC_J_1" id="noteC_J_1" size="11" value="<?php if ($nbnew_perfos>0){echo $row['C_J_1'];} ?>"></td>
					<td><input type="text" name="noteD_J_1" id="noteD_J_1" size="11" value="<?php if ($nbnew_perfos>0){echo $row['D_J_1'];} ?>"></td>
					<td><input type="text" name="noteP_J_1" id="noteP_J_1" size="11" value="<?php if ($nbnew_perfos>0){echo $row['P_J_1'];} ?>"></td>
					<td><input type="text" name="noteF_J_1" id="noteF_J_1" size="11" value="<?php if ($nbnew_perfos>0){echo $row['F_J_1'];} ?>"></td>
					<td><input type="text" name="IdPrestationSelect" size="11" value="<?php echo $IdPrestationSelect; ?>"></td>
					<td><input type="text" name="IdPoleSelect" size="11" value="<?php echo $IdPoleSelect; ?>"></td>
					<td><input type="text" name="DateSelect" size="11" value="<?php echo $DateSelect; ?>"></td>
					<td><input type="text" id="Idnew_perfos" name="Idnew_perfos" size="11" value="<?php echo $Idnew_perfos; ?>"></td>
					<td><input type="text"  id="actionSupp" name="actionSupp" size="11" value=""></td>
				</tr>
				<tr><td ><br/></td></tr>
				<tr><td height="4" align="center">
						<?php
						echo "<a href=\"javascript:FeuVert(".$IdPrestationSelect.",".$IdPoleSelect.",".$DateSelect.")\">";						
						echo "		<img src=\"../../Images/FeuVert.jpg\" border='0' alt=\"FeuVert\">";
						echo "</a>";
						?>
				</td></tr>
				<tr><td>
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr align="center">
							<?php
							if ($nbnew_perfos>0){
								if ($row['S_J_1'] == "1"){echo "<td id='S' class='lettreVerte' colspan='2'>S<font style=\"font-size:12px;\">écurité</font></td>";}
								elseif ($row['S_J_1'] == "2"){echo "<td id='S' class='lettreRouge' colspan='2'>S<font style=\"font-size:12px;\">écurité</font></td>";}
							}
							else{echo "<td id='S' class='lettre' colspan='2'>S<font style=\"font-size:12px;\">écurité</font></td>";}
							
								if ($nbnew_perfos>0){
									if ($row['Q_J_1'] == "1"){echo "<td id='Q' class='lettreVerte' colspan='2'>Q<font style=\"font-size:12px;\">ualité</font></td>";}
									elseif ($row['Q_J_1'] == "2"){echo "<td id='Q' class='lettreRouge' colspan='2'>Q<font style=\"font-size:12px;\">ualité</font></td>";}
								}
								else{echo "<td id='Q' class='lettre' colspan='2'>Q<font style=\"font-size:12px;\">ualité</font></td>";}
								
								if ($nbnew_perfos>0){
									if ($row['C_J_1'] == "1"){echo "<td id='C' class='lettreVerte' colspan='2'>C<font style=\"font-size:12px;\">oûts</font></td>";}
									elseif ($row['C_J_1'] == "2"){echo "<td id='C' class='lettreRouge' colspan='2'>C<font style=\"font-size:12px;\">oûts</font></td>";}
								}
								else{echo "<td id='C' class='lettre' colspan='2'>C<font style=\"font-size:12px;\">oûts</font></td>";}
								
								if ($nbnew_perfos>0){
									if ($row['D_J_1'] == "1"){echo "<td id='D' class='lettreVerte' colspan='2'>D<font style=\"font-size:12px;\">élais</font></td>";}
									elseif ($row['D_J_1'] == "2"){echo "<td id='D' class='lettreRouge' colspan='2'>D<font style=\"font-size:12px;\">élais</font></td>";}
								}
								else{echo "<td id='D' class='lettre' colspan='2'>D<font style=\"font-size:12px;\">élais</font></td>";}
								
								if ($nbnew_perfos>0){
									if ($row['P_J_1'] == "1"){echo "<td id='P' class='lettreVerte' colspan='2'>P<font style=\"font-size:12px;\">ersonnel</font></td>";}
									elseif ($row['P_J_1'] == "2"){echo "<td id='P' class='lettreRouge' colspan='2'>P<font style=\"font-size:12px;\">ersonnel</font></td>";}
								}
								else{echo "<td id='P' class='lettre' colspan='2'>P<font style=\"font-size:12px;\">ersonnel</font></td>";}								
								
								if ($nbnew_perfos>0){
									if ($row['F_J_1'] == "1"){echo "<td id='F' class='lettreVerte' colspan='2'>F<font style=\"font-size:12px;\">ormation</font></td>";}
									elseif ($row['F_J_1'] == "2"){echo "<td id='F' class='lettreRouge' colspan='2'>F<font style=\"font-size:12px;\">ormation</font></td>";}
								}
								else{echo "<td id='F' class='lettre' colspan='2'>F<font style=\"font-size:12px;\">ormation</font></td>";}
								
							?>
						</tr>
						<tr align="center">
							<td style="border:1px #d9d9d7 solid;color:white;" valign="top" bgcolor="#4a81c8" colspan="2">Sécurité du personnel</td>
							<td style="border:1px #d9d9d7 solid;color:white;" valign="top" bgcolor="#4a81c8" colspan="2">Respect OQD</td>
							<td style="border:1px #d9d9d7 solid;color:white;" valign="top" bgcolor="#4a81c8" colspan="2">Difficultés réalisation</td>							
							<td style="border:1px #d9d9d7 solid;color:white;" valign="top" bgcolor="#4a81c8" colspan="2">Respect des objectifs <br>de délais et de «quantité»</td>
							<td style="border:1px #d9d9d7 solid;color:white;" valign="top" bgcolor="#4a81c8" colspan="2">Adéquation charge capacité</td>
							<td style="border:1px #d9d9d7 solid;color:white;" valign="top" bgcolor="#4a81c8" colspan="2">Niveau de formation du personnel</td>
						</tr>
						<tr>
							<td align="right" style="border-left:1px #d9d9d7 solid;">
								<a href="javascript:ModifCouleur('S','V')">
								<img src="../../Images/BoutonVert.png" border='0' alt="SVert" title="">
								</a>
							</td>
							<td align="left" style="border-right:1px #d9d9d7 solid;">
								<a href="javascript:ModifCouleur('S','R')">
								<img src="../../Images/BoutonRouge.png" border='0' alt="SRouge" title="">
								</a>
							</td>
							<td align="right" style="border-left:1px #d9d9d7 solid;">
								<a href="javascript:ModifCouleur('Q','V')">
								<img src="../../Images/BoutonVert.png" border='0' alt="QVert" title="QVert">
								</a>
							</td>
							<td align="left" style="border-right:1px #d9d9d7 solid;">
								<a href="javascript:ModifCouleur('Q','R')">
								<img src="../../Images/BoutonRouge.png" border='0' alt="QRouge" title="QRouge">
								</a>
							</td>
							<td align="right" style="border-left:1px #d9d9d7 solid;">
								<a href="javascript:ModifCouleur('C','V')">
								<img src="../../Images/BoutonVert.png" border='0' alt="CVert" title="CVert">
								</a>
							</td>
							<td align="left" style="border-right:1px #d9d9d7 solid;">
								<a href="javascript:ModifCouleur('C','R')">
								<img src="../../Images/BoutonRouge.png" border='0' alt="CRouge" title="CRouge">
								</a>
							</td>
							<td align="right" style="border-left:1px #d9d9d7 solid;">
								<a href="javascript:ModifCouleur('D','V')">
								<img src="../../Images/BoutonVert.png" border='0' alt="DVert" title="DVert">
								</a>
							</td>
							<td align="left" style="border-right:1px #d9d9d7 solid;">
								<a href="javascript:ModifCouleur('D','R')">
								<img src="../../Images/BoutonRouge.png" border='0' alt="DRouge" title="DRouge">
								</a>
							</td>
							<td align="right" style="border-left:1px #d9d9d7 solid;">
								<a href="javascript:ModifCouleur('P','V')">
								<img src="../../Images/BoutonVert.png" border='0' alt="PVert" title="PVert">
								</a>
							</td>
							<td align="left" style="border-right:1px #d9d9d7 solid;">
								<a href="javascript:ModifCouleur('P','R')">
								<img src="../../Images/BoutonRouge.png" border='0' alt="PRouge" title="PRouge">
								</a>
							</td>
							<td align="right" style="border-left:1px #d9d9d7 solid;">
								<a href="javascript:ModifCouleur('F','V')">
								<img src="../../Images/BoutonVert.png" border='0' alt="FVert" title="FVert">
								</a>
							</td>
							<td align="left" style="border-right:1px #d9d9d7 solid;">
								<a href="javascript:ModifCouleur('F','R')">
								<img src="../../Images/BoutonRouge.png" border='0' alt="FRouge" title="FRouge">
								</a>
							</td>
						</tr>
						<tr align="center">
							<td style="border:1px #d9d9d7 solid;" colspan="2"><textarea class="CommentaireLettre" name="CommentaireS_J_1" rows="0" cols="0"><?php if ($nbnew_perfos>0){echo $row['CommentaireS_J_1'];} ?></textarea></td>
							<td style="border:1px #d9d9d7 solid;" colspan="2"><textarea class="CommentaireLettre" name="CommentaireQ_J_1" rows="0" cols="0"><?php if ($nbnew_perfos>0){echo $row['CommentaireQ_J_1'];} ?></textarea></td>
							<td style="border:1px #d9d9d7 solid;" colspan="2"><textarea class="CommentaireLettre" name="CommentaireC_J_1" rows="0" cols="0"><?php if ($nbnew_perfos>0){echo $row['CommentaireC_J_1'];} ?></textarea></td>
							<td style="border:1px #d9d9d7 solid;" colspan="2"><textarea class="CommentaireLettre" name="CommentaireD_J_1" rows="0" cols="0"><?php if ($nbnew_perfos>0){echo $row['CommentaireD_J_1'];} ?></textarea></td>
							<td style="border:1px #d9d9d7 solid;" colspan="2"><textarea class="CommentaireLettre" name="CommentaireP_J_1" rows="0" cols="0"><?php if ($nbnew_perfos>0){echo $row['CommentaireP_J_1'];} ?></textarea></td>
							<td style="border:1px #d9d9d7 solid;" colspan="2"><textarea class="CommentaireLettre" name="CommentaireF_J_1" rows="0" cols="0"><?php if ($nbnew_perfos>0){echo $row['CommentaireF_J_1'];} ?></textarea></td>
						</tr>
					</table>
				</td></tr>
				<tr><td height="4"/></tr>
				<tr>
					<td class="TitreSousPagePERFOS" >Points chauds J-J/actions SQCDPF</td>
				</tr>
				<tr><td>
					<table id="tab_Actions" width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr style="display:none;">
							<?php
								$reqPers = "SELECT Nom, Prenom FROM new_rh_etatcivil WHERE ";
							?>
								<td><input type="text" id="personne" value=""></input></td>
						</tr>
						<tr bgcolor="#bacfea">
							<td class="SousenTetePERFOS">Niveau</td>
							<td class="SousenTetePERFOS">Lettre</td>
							<td class="SousenTetePERFOS">Point Chaud</td>
							<td class="SousenTetePERFOS">Description du problème <br/>(Point chaud, Point SQCDPF)</td>
							<td class="SousenTetePERFOS">Commentaire</td>
							<td class="SousenTetePERFOS">Description action</td>
							<td class="SousenTetePERFOS">Acteur</td>
							<td class="SousenTetePERFOS">Délai</td>
							<td class="SousenTetePERFOS"></td>
							<?php
								if ($nbnew_perfos==0){
									echo "<td class='SousenTetePERFOS'></td>";
								}
							?>
						</tr>
						<?php
// 							Le tableau des action dans le cadre de la modification
							if ($nbnew_perfos>0){
								$req="SELECT new_action.Lettre, new_action.PointChaud, new_action.Probleme, new_action.Action, new_action.Delais, new_action.Niveau, ";
								$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=new_action.Id_Acteur) AS Responsable, new_action.Commentaire ";
								$req.="FROM new_action WHERE new_action.DateCreation='".$row['DateSQCDPF']."' AND new_action.Vacation='".$row['Vacation']."' AND new_action.Id_Prestation=".$row['Id_Prestation']." AND new_action.Id_Pole=".$row['Id_Pole']."";
								$resulAction=mysqli_query($bdd,$req);
								$nbAction=mysqli_num_rows($resulAction);
								if ($nbAction>0){
									$couleur = "#eef3fa";
									while($rowAction=mysqli_fetch_array($resulAction)){
										if ($couleur == "#eef3fa"){$couleur = "#ffffff";}
										else{$couleur = "#eef3fa";}
										echo "<tr bgcolor='".$couleur."'>";
										echo "<td align='center'>".$rowAction['Niveau']."</td>";
										echo "<td align='center'>".$rowAction['Lettre']."</td>";
										echo "<td align='center'>".$rowAction['PointChaud']."</td>";
										echo "<td align='center'>".$rowAction['Probleme']."</td>";
										echo "<td align='center'>".$rowAction['Commentaire']."</td>";
										echo "<td align='center'>".$rowAction['Action']."</td>";
										echo "<td align='center'>".$rowAction['Responsable']."</td>";
										echo "<td align='center'></td>";
										if($rowAction['Delais']<="0001-01-01"){
											echo "<td align='center'></td>";
										}
										else{
											echo "<td align='center'>".AfficheDateJJ_MM_AAAA($rowAction['Delais'])."</td>";
										}
										echo "</tr>";
									}
								}
							}
						?>
					</table>
				</td></tr>
				<?php
					if ($nbnew_perfos==0){
				?>
				<tr>
					<td><br></td>
				</tr>
				<tr>
					<td colspan="7">
						<a style="text-decoration:none;" class="Bouton" href="javascript:CreerAction('')">&nbsp;Créer une nouvelle action&nbsp;</a>
					</td>
				</tr>
				<?php
					}
				?>
				<tr><td height="8" colspan="7"/></tr>
				<tr align="center">
					<td colspan="7" align="center">
						<div id="btnValider">
							<input  class="Bouton"  name="submitValider" type="submit" value='Valider'>
						</div>
					</td>
				</tr>
			</table>
		</td></tr>
	</table>
</form>

<?php
	echo "<script>Rechercher_PERFOS();</script>";
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
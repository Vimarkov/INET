<?php
	session_start();
	require("Outils/Connexioni.php");
	$_SESSION['Id_PrestationTR']=$_GET['Id_Prestation'];
	$req="SELECT Id_PrestationExtra FROM trame_prestation WHERE Id=".$_SESSION['Id_PrestationTR'];
	$resultPrestaTR=mysqli_query($bdd,$req);
	$nbPrestaTR=mysqli_num_rows($resultPrestaTR);
	if($nbPrestaTR>0){
		$rowPrestaTR=mysqli_fetch_array($resultPrestaTR);
		$_SESSION['Id_PrestationExtranet']=$rowPrestaTR['Id_PrestationExtra'];
	}
	$_SESSION['Formulaire']="Production/Production.php";
	$resultAcces=mysqli_query($bdd,"SELECT Droit FROM trame_acces WHERE Id_Prestation=".$_GET['Id_Prestation']." AND Id_Personne=".$_SESSION['Id_PersonneTR']." ");
	$nbAcces=mysqli_num_rows($resultAcces);
	$resultAdmin=mysqli_query($bdd,"SELECT Id FROM trame_admin WHERE Id_Personne=".$_SESSION['Id_PersonneTR']." ");
	$nbAdmin=mysqli_num_rows($resultAdmin);
	$admin="0";
	if($nbAdmin>0){$admin="1";}
	if($nbAcces>0){
		$rowDroit=mysqli_fetch_array($resultAcces);
		$_SESSION['DroitTR']=$rowDroit['Droit'].$admin;
		
	}
	else{
		$_SESSION['DroitTR']="00000".$admin;
	}
	$resultUpdate=mysqli_query($bdd,"UPDATE new_rh_etatcivil SET Id_PrestationTraME=".$_GET['Id_Prestation']." WHERE Id=".$_SESSION['Id_PersonneTR']." ");
 
	//VARIABLES SESSION Production
	$tab =array("Reference","DateDebut","DateFin","WP","Tache","MotCles","Preparateur","Statut","PageDateDebut","PageDateFin","PagePreparateur","PageWP");
	foreach($tab as $value){
		$req="SELECT Valeur, Valeur2 FROM trame_parametrage WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_Personne=".$_SESSION['Id_PersonneTR']." AND Type='Filtre' AND Page='Production' AND Variable='".$value."' ";
		$resultTest=mysqli_query($bdd,$req);
		$nbTest=mysqli_num_rows($resultTest);
		if($nbTest==0){
			if($value=="Preparateur"){
				if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],2,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1'){
					$_SESSION['PROD_'.$value]="";
					$_SESSION['PROD_'.$value.'2']="";
				}
				else{
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('preparateur','".$_SESSION['Id_PersonneTR']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['PROD_'.$value]=$_SESSION['NomTR']." ".$_SESSION['PrenomTR'].$valeur;
					$_SESSION['PROD_'.$value.'2']=$_SESSION['Id_PersonneTR'].";";
				}
			}
			elseif($value=="Statut"){
				if($_GET['L']=="EN"){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','EN COURS;IN PROGRESS')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['PROD_'.$value]="IN PROGRESS".$valeur;
				}
				else{
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','EN COURS;EN COURS')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['PROD_'.$value]="EN COURS".$valeur;
				}
				if($_GET['L']=="EN"){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','BLOQUE;BLOQUED')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['PROD_'.$value]="BLOCKED".$valeur;
				}
				else{
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','BLOQUE;BLOQUED')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['PROD_'.$value]="BLOQUE".$valeur;
				}
				if($_GET['L']=="EN"){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','AC;AUTO CONTROL')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['PROD_'.$value].="AUTO CONTROL".$valeur;
				}
				else{
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','AC;AUTO-CONTROLE')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['PROD_'.$value].="AUTO-CONTROLE".$valeur;
				}
				if($_GET['L']=="EN"){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','REC;CONTROL AGAIN')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['PROD_'.$value].="CONTROL AGAIN".$valeur;
				}
				else{
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','REC;RECONTROLE')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['PROD_'.$value].="RECONTROLE".$valeur;
				}
				if($_GET['L']=="EN"){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','CONTROLE;CONTROL')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['PROD_'.$value].="CONTROL".$valeur;
				}
				else{
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','CONTROLE;CONTROLE')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['PROD_'.$value].="CONTROLE".$valeur;
				}
				if($_GET['L']=="EN"){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','A VALIDER;TO BE VALIDATED')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['PROD_'.$value].="TO BE VALIDATED".$valeur;
				}
				else{
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','A VALIDER;A VALIDER')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['PROD_'.$value].="A VALIDER".$valeur;
				}
				$_SESSION['PROD_'.$value.'2']="BLOQUE;EN COURS;AC;CONTROLE;A VALIDER;REC;";
			}
			else{
				$_SESSION['PROD_'.$value]="";
				$_SESSION['PROD_'.$value.'2']="";
			}
			
			$req="INSERT INTO trame_parametrage (Id_Prestation,Id_Personne,Type,Page,Variable,Valeur,Valeur2) VALUES (".$_SESSION['Id_PrestationTR'].",".$_SESSION['Id_PersonneTR'].",'Filtre','Production','".$value."','".addslashes($_SESSION['PROD_'.$value])."','".addslashes($_SESSION['PROD_'.$value.'2'])."') ";
			$resultTest=mysqli_query($bdd,$req);
		}
		else{
			$rowValeur=mysqli_fetch_array($resultTest);
			$_SESSION['PROD_'.$value]=stripslashes($rowValeur['Valeur']);
			$_SESSION['PROD_'.$value.'2']=stripslashes($rowValeur['Valeur2']);
		}
	}
	
	$tab =array("Reference","Date","WP","Tache","Preparateur","Statut","TempsPasse","TempsAlloue","General");
	foreach($tab as $value){
		$req="SELECT Valeur, Valeur2 FROM trame_parametrage WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_Personne=".$_SESSION['Id_PersonneTR']." AND Type='Tri' AND Page='Production' AND Variable='".$value."' ";
		$resultTest=mysqli_query($bdd,$req);
		$nbTest=mysqli_num_rows($resultTest);
		if($nbTest==0){
			if($value=="General"){$_SESSION['TriPROD_'.$value]="Id DESC,";}
			else{$_SESSION['TriPROD_'.$value]="";}

			$req="INSERT INTO trame_parametrage (Id_Prestation,Id_Personne,Type,Page,Variable,Valeur) VALUES (".$_SESSION['Id_PrestationTR'].",".$_SESSION['Id_PersonneTR'].",'Tri','Production','".$value."','".addslashes($_SESSION['TriPROD_'.$value])."') ";
			$resultTest=mysqli_query($bdd,$req);
		}
		else{
			$rowValeur=mysqli_fetch_array($resultTest);
			$_SESSION['TriPROD_'.$value]=stripslashes($rowValeur['Valeur']);
		}
	}
	
	$_SESSION['PROD_ModeFiltre']="oui";
	$_SESSION['PROD_Page']="0";
	$_SESSION['PROD_NbLigne']=50;
	
	//VARIABLES SESSION Validation
	$tab =array("Reference","DateDebut","DateFin","WP","Tache","MotCles","Preparateur","Controleur","Statut","Delai","PageDateDebut","PageDateFin","PagePreparateur","PageWP");
	foreach($tab as $value){
		$req="SELECT Valeur, Valeur2 FROM trame_parametrage WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_Personne=".$_SESSION['Id_PersonneTR']." AND Type='Filtre' AND Page='Validation' AND Variable='".$value."' ";
		$resultTest=mysqli_query($bdd,$req);
		$nbTest=mysqli_num_rows($resultTest);
		if($nbTest==0){
			if($value=="Statut"){
				if($_GET['L']=="EN"){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','A VALIDER;TO BE VALIDATED')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['VALI_'.$value].="TO BE VALIDATED".$valeur;
				}
				else{
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','A VALIDER;A VALIDER')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['VALI_'.$value].="A VALIDER".$valeur;
				}
				
				$_SESSION['VALI_'.$value.'2']="A VALIDER;";
			}
			else{
				$_SESSION['VALI_'.$value]="";
				$_SESSION['VALI_'.$value.'2']="";
			}
			
			$req="INSERT INTO trame_parametrage (Id_Prestation,Id_Personne,Type,Page,Variable,Valeur,Valeur2) VALUES (".$_SESSION['Id_PrestationTR'].",".$_SESSION['Id_PersonneTR'].",'Filtre','Validation','".$value."','".addslashes($_SESSION['VALI_'.$value])."','".addslashes($_SESSION['VALI_'.$value.'2'])."') ";
			$resultTest=mysqli_query($bdd,$req);
		}
		else{
			$rowValeur=mysqli_fetch_array($resultTest);
			$_SESSION['VALI_'.$value]=stripslashes($rowValeur['Valeur']);
			$_SESSION['VALI_'.$value.'2']=stripslashes($rowValeur['Valeur2']);
		}
	}
	
	$tab =array("Reference","Date","WP","Tache","Preparateur","Statut","TempsPasse","TempsAlloue","Responsable","RaisonRefus","Delai","CommentaireDelai","Commentaire","Controleur","General");
	foreach($tab as $value){
		$req="SELECT Valeur, Valeur2 FROM trame_parametrage WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_Personne=".$_SESSION['Id_PersonneTR']." AND Type='Tri' AND Page='Validation' AND Variable='".$value."' ";
		$resultTest=mysqli_query($bdd,$req);
		$nbTest=mysqli_num_rows($resultTest);
		if($nbTest==0){
			if($value=="General"){$_SESSION['TriVALI_'.$value]="DatePreparateur ASC,Designation ASC,";}
			else{$_SESSION['TriVALI_'.$value]="";}

			$req="INSERT INTO trame_parametrage (Id_Prestation,Id_Personne,Type,Page,Variable,Valeur) VALUES (".$_SESSION['Id_PrestationTR'].",".$_SESSION['Id_PersonneTR'].",'Tri','Validation','".$value."','".addslashes($_SESSION['TriVALI_'.$value])."') ";
			$resultTest=mysqli_query($bdd,$req);
		}
		else{
			$rowValeur=mysqli_fetch_array($resultTest);
			$_SESSION['TriVALI_'.$value]=stripslashes($rowValeur['Valeur']);
		}
	}
	$_SESSION['VALI_ModeFiltre']="oui";
	$_SESSION['VALI_Page']="0";
	$_SESSION['VALI_NbLigne']=50;
	$_SESSION['VALI_Ids']="";
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="Dossier.js"></script>
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>	
	<script>
		function FermerEtRecharger(){
			if(window.opener.document.getElementById('formulaire')){
				window.opener.document.getElementById('formulaire').submit();
			}
			window.close();
		}
		function AfficherSubmit(){
			var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
			document.getElementById('Enregistrer').innerHTML=bouton;
			var evt = document.createEvent("MouseEvents");
			evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
			document.getElementById("btnEnregistrer2").dispatchEvent(evt);
			document.getElementById('Enregistrer').innerHTML="";
		}	
	</script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../../JS/js/jquery-ui-1.8.5.min.js"></script>
</head>
<?php
session_start();
require("../../Connexioni.php");
require("../../Fonctions.php");

Ecrire_Code_JS_Init_Date();

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
$HeureJour = date("H:i:s");

if($_POST){
	if(isset($_POST['btnEnregistrer2'])){	
		//MISE A JOUR DU DOSSIER
		$req="UPDATE sp_atrot SET ";
		if(substr($_SESSION['DroitSP'],4,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1'){
			if($_POST['statutQualite']=="TERC" && $_POST['statutQualite']<>$_POST['OLDstatutQUALITE']){
				$req.="DateTERC='".$DateJour."', ";
				$req.="HeureTERC='".$HeureJour."', ";
			}
			$req.="Id_StatutQUALITE='".$_POST['statutQualite']."', ";
			$req.="DateQUALITE='".TrsfDate_($_POST['dateQUALITE'])."', ";
			$req.="Id_CauseRetardQUALITE=".$_POST['causeRetardQualite'].", ";
		}
		if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
			if($_POST['statutProd']=="TERA" && $_POST['statutProd']<>$_POST['OLDstatutPROD']){
				$req.="DateTERA='".$DateJour."', ";
				$req.="HeureTERA='".$HeureJour."', ";
			}
			$req.="Id_StatutPROD='".$_POST['statutProd']."', ";
			$req.="Id_CauseRetardPROD=".$_POST['causeRetardProd'].", ";
			$req.="DatePROD='".TrsfDate_($_POST['datePROD'])."', ";
		}
		$req.="Commentaire='".addslashes($_POST['commentaire'])."', ";
		
		if(isset($_POST['PasDePS'])){$req.="PasDePS=1, ";}
		else{$req.="PasDePS=0, ";}
		
		if(isset($_POST['PasDeECMEPROD'])){$req.="PasDeECMEPROD=1, ";}
		else{$req.="PasDeECMEPROD=0, ";}
		if(isset($_POST['PasDeECMEQUALITE'])){$req.="PasDeECMEQUALITE=1, ";}
		else{$req.="PasDeECMEQUALITE=0, ";}
		if(isset($_POST['PasDeIngredient'])){$req.="PasDeIngredient=1, ";}
		else{$req.="PasDeIngredient=0, ";}
		
		if(isset($_POST['ValidationPSCE'])){$req.="ValidationPSCE=1, ";}
		else{$req.="ValidationPSCE=0, ";}
		if(isset($_POST['ValidationPSIQ'])){$req.="ValidationPSIQ=1 ";}
		else{$req.="ValidationPSIQ=0 ";}
		
		$req.="WHERE Id=".$_POST['id'];

		$resultUpdate=mysqli_query($bdd,$req);
		if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
			//Suppression des leader
			$req="DELETE FROM sp_atrot_ce WHERE Id_OT=".$_POST['id'];
			$resultDelete=mysqli_query($bdd,$req);
			
			//Ajout des leader
			$tabPL = explode(";",$_POST['lesces']);
			foreach($tabPL as $valeur){
				 if($valeur<>""){
					$req="INSERT INTO sp_atrot_ce (Id_OT,Id_Personne) VALUES (".$_POST['id'].",".$valeur.")";
					$resultAjour=mysqli_query($bdd,$req);
				 }
			}
		}
		if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
			//Suppression des compagnons
			$req="DELETE FROM sp_atrot_compagnon WHERE Id_OT=".$_POST['id'];
			$resultDelete=mysqli_query($bdd,$req);
			
			//Ajout des compagnons
			$tabPL = explode(";",$_POST['lescompagnons']);
			foreach($tabPL as $valeur){
				 if($valeur<>""){
					$req="INSERT INTO sp_atrot_compagnon (Id_OT,Id_Personne) VALUES (".$_POST['id'].",".$valeur.")";
					$resultAjour=mysqli_query($bdd,$req);
				 }
			}
		}
		if(substr($_SESSION['DroitSP'],4,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1'){
			//Suppression des iq
			$req="DELETE FROM sp_atrot_controleur WHERE Id_OT=".$_POST['id'];
			$resultDelete=mysqli_query($bdd,$req);
			
			//Ajout des iq
			$tabPL = explode(";",$_POST['IQ']);
			foreach($tabPL as $valeur){
				 if($valeur<>""){
					$req="INSERT INTO sp_atrot_controleur (Id_OT,Id_Personne) VALUES (".$_POST['id'].",".$valeur.")";
					$resultAjour=mysqli_query($bdd,$req);
				 }
			}
		}
		
		//Avant la mise à jour des ECME PROD 
		//Récupération des ECME PROD non identifiés & vérifier si existe déjà dans la BDD
		//Sinon envoi un mail aux Coordinateurs d'équipe de la prestation pour les avertir
		$tabECME = explode(";",$_POST['ECMEPROD']);
		foreach($tabECME as $valeur){
			 if($valeur<>""){
				$tablesECME = explode("ECME_ECME",$valeur);
				$InfosECME=$tablesECME[1];
				if($InfosECME<>""){
					$tabECME2=explode("_",$InfosECME);
					$ECME=$tabECME2[0];
					$req="SELECT Id FROM sp_atrot_ecme WHERE Reference='".addslashes($ECME)."' AND Id_OT=".$_POST['id'];
					$resultSelect=mysqli_query($bdd,$req);
					$nbECME=mysqli_num_rows($resultSelect);
					if($nbECME==0){
						//Email 
						$Headers='From: "Extranet AAA"<extranet@aaa-aero.com>'."\n";
						$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
						
						$Emails="";
						$ReqResponsablePostePrestation="
							SELECT DISTINCT new_rh_etatcivil.EmailPro
							FROM
								new_competences_personne_poste_prestation,
								new_rh_etatcivil
							WHERE
								new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
								AND new_competences_personne_poste_prestation.Id_Prestation=463
								AND new_competences_personne_poste_prestation.Id_Poste=2
								AND new_rh_etatcivil.EmailPro<>''";
						$resultCE=mysqli_query($bdd,$ReqResponsablePostePrestation);
						$nbCE=mysqli_num_rows($resultCE);
						if($nbCE>0){
							while($rowCE=mysqli_fetch_array($resultCE)){
								$Emails.=$rowCE['EmailPro'].",";
							}
						}
						$Objet="Nouvel ECME à mettre à jour, suivi production prestation EISA";
						$MessageMail="	<html>
										<head><title>Nouvel ECME à mettre à jour, suivi production prestation EISA</title></head>
										<body>
											Bonjour,
											<br><br>
											Attention, une référence d'ECME non identifiée dans la liste prédéfinie de votre outil de suivi production vient d'être saisie pour la prestation EISA<br>
											Veuillez vérifier l'exactitude des données saisies et mettre à jour la liste des ECME si cet outil est amené à être utilisé sur votre prestation pour vos futurs travaux
											<br>ECME : ".$ECME."
											<br>
											Bonne journée.<br>
											L'Extranet AAA.
										</body>
									</html>";
						if($Emails<>""){
							if(mail($Emails,$Objet,$MessageMail,$Headers,'-f extranet@aaa-aero.com'))
								{echo "Un message a été envoyé à ".$Emails."\n";}
							else{echo "Un message n'a pu être envoyé à ".$Emails."\n";}
						}
					}
				}
			 }
		}
		
		//Suppression des ecme PROD
		$req="DELETE FROM sp_atrot_ecme WHERE ProdQualite=0 AND Id_OT=".$_POST['id'];
		$resultDelete=mysqli_query($bdd,$req);
		
		//Ajout des ecme PROD
		$tabPL = explode(";",$_POST['ECMEPROD']);
		foreach($tabPL as $valeur){
			 if($valeur<>""){
				$Id_ECME=0;
				$Id_TypeECME=0;
				$ReferenceECME="";
				$tabECME = explode("ECME_ECME",$valeur);
				if($tabECME[0]==0){
					$tabECME2 = explode("_",$tabECME[1]);
					$ReferenceECME=$tabECME2[0];
					$Id_TypeECME=$tabECME2[1];
				}
				else{
					$tabECME2 = explode("_",$tabECME[0]);
					$Id_ECME=$tabECME2[0];
					$Id_TypeECME=$tabECME2[1];
				}
				$req="INSERT INTO sp_atrot_ecme (Id_OT,Id_ECME,Id_TypeECME,Reference,ProdQualite) 
				VALUES (".$_POST['id'].",".$Id_ECME.",".$Id_TypeECME.",'".$ReferenceECME."',0)";
				$resultAjour=mysqli_query($bdd,$req);
			 }
		}
		
		//Avant la mise à jour des ECME QUALITE 
		//Récupération des ECME PROD non identifiés & vérifier si existe déjà dans la BDD
		//Sinon envoi un mail aux Coordinateurs d'équipe de la prestation pour les avertir
		$tabECME = explode(";",$_POST['ECMEQUALITE']);
		foreach($tabECME as $valeur){
			 if($valeur<>""){
				$tablesECME = explode("ECME_ECME",$valeur);
				$InfosECME=$tablesECME[1];
				if($InfosECME<>""){
					$tabECME2=explode("_",$InfosECME);
					$ECME=$tabECME2[0];
					$req="SELECT Id FROM sp_atrot_ecme WHERE Reference='".addslashes($ECME)."' AND Id_OT=".$_POST['id'];
					$resultSelect=mysqli_query($bdd,$req);
					$nbECME=mysqli_num_rows($resultSelect);
					if($nbECME==0){
						//Email 
						$Headers='From: "Extranet AAA"<extranet@aaa-aero.com>'."\n";
						$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
						
						$Emails="";
						$ReqResponsablePostePrestation="
							SELECT DISTINCT new_rh_etatcivil.EmailPro
							FROM
								new_competences_personne_poste_prestation,
								new_rh_etatcivil
							WHERE
								new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
								AND new_competences_personne_poste_prestation.Id_Prestation=463
								AND new_competences_personne_poste_prestation.Id_Poste=2
								AND new_rh_etatcivil.EmailPro<>''";
						$resultCE=mysqli_query($bdd,$ReqResponsablePostePrestation);
						$nbCE=mysqli_num_rows($resultCE);
						if($nbCE>0){
							while($rowCE=mysqli_fetch_array($resultCE)){
								$Emails.=$rowCE['EmailPro'].",";
							}
						}
						$Objet="Nouvel ECME à mettre à jour, suivi production prestation EISA";
						$MessageMail="	<html>
										<head><title>Nouvel ECME à mettre à jour, suivi production prestation EISA</title></head>
										<body>
											Bonjour,
											<br><br>
											Attention, une référence d'ECME non identifiée dans la liste prédéfinie de votre outil de suivi production vient d'être saisie pour la prestation EISA<br>
											Veuillez vérifier l'exactitude des données saisies et mettre à jour la liste des ECME si cet outil est amené à être utilisé sur votre prestation pour vos futurs travaux
											<br>ECME : ".$ECME."
											<br>
											Bonne journée.<br>
											L'Extranet AAA.
										</body>
									</html>";
						if($Emails<>""){
							if(mail($Emails,$Objet,$MessageMail,$Headers,'-f extranet@aaa-aero.com'))
								{echo "Un message a été envoyé à ".$Emails."\n";}
							else{echo "Un message n'a pu être envoyé à ".$Emails."\n";}
						}
					}
				}
			 }
		}
		
		//Suppression des ecme QUALITE
		$req="DELETE FROM sp_atrot_ecme WHERE ProdQualite=1 AND Id_OT=".$_POST['id'];
		$resultDelete=mysqli_query($bdd,$req);
		
		//Ajout des ecme QUALITE
		$tabPL = explode(";",$_POST['ECMEQUALITE']);
		foreach($tabPL as $valeur){
			 if($valeur<>""){
				$Id_ECME=0;
				$Id_TypeECME=0;
				$ReferenceECME="";
				$tabECME = explode("ECME_ECME",$valeur);
				if($tabECME[0]==0){
					$tabECME2 = explode("_",$tabECME[1]);
					$ReferenceECME=$tabECME2[0];
					$Id_TypeECME=$tabECME2[1];
				}
				else{
					$tabECME2 = explode("_",$tabECME[0]);
					$Id_ECME=$tabECME2[0];
					$Id_TypeECME=$tabECME2[1];
				} 
				$req="INSERT INTO sp_atrot_ecme (Id_OT,Id_ECME,Id_TypeECME,Reference,ProdQualite) 
				VALUES (".$_POST['id'].",".$Id_ECME.",".$Id_TypeECME.",'".$ReferenceECME."',1)";
				$resultAjour=mysqli_query($bdd,$req);
			 }
		}
		
		//Suppression des ecme client
		$req="DELETE FROM sp_atrot_ecmeclient WHERE Id_OT=".$_POST['id'];
		$resultDelete=mysqli_query($bdd,$req);
		
		//Ajout des ecme client
		$tabPL = explode(";",$_POST['ECMECLIENT']);
		foreach($tabPL as $valeur){
			 if($valeur<>""){
				$tabECME = explode("_",$valeur);

				$req="INSERT INTO sp_atrot_ecmeclient (Id_OT,NumClient,DateFinEtalonnage) 
				VALUES (".$_POST['id'].",'".$tabECME[0]."','".TrsfDate_($tabECME[1])."')";
				$resultAjour=mysqli_query($bdd,$req);
			 }
		}
		
		//Avant la mise à jour des Inrédients
		//Récupération des ingredients non identifiés & vérifier si existe déjà dans la BDD
		//Sinon envoi un mail aux Coordinateurs d'équipe de la prestation pour les avertir
		$tabProduit = explode(";",$_POST['Produit']);
		foreach($tabProduit as $valeur){
			 if($valeur<>""){
				$tablesProduit= explode("ING_ING",$valeur);
				$InfosProduit=$tablesProduit[1];
				if($InfosProduit<>""){
					$tabProduit2=explode("_",$InfosProduit);
					$Produit=$tabProduit2[0];
					$req="SELECT Id FROM sp_atrot_produit WHERE Produit='".addslashes($Produit)."' AND Id_OT=".$_POST['id'];
					$resultSelect=mysqli_query($bdd,$req);
					$nbProduit=mysqli_num_rows($resultSelect);
					if($nbProduit==0){
						//Email 
						$Headers='From: "Extranet AAA"<extranet@aaa-aero.com>'."\n";
						$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
						
						$Emails="";
						$ReqResponsablePostePrestation="
							SELECT DISTINCT new_rh_etatcivil.EmailPro
							FROM
								new_competences_personne_poste_prestation,
								new_rh_etatcivil
							WHERE
								new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
								AND new_competences_personne_poste_prestation.Id_Prestation=463
								AND new_competences_personne_poste_prestation.Id_Poste=2
								AND new_rh_etatcivil.EmailPro<>''";
						$resultCE=mysqli_query($bdd,$ReqResponsablePostePrestation);
						$nbCE=mysqli_num_rows($resultCE);
						if($nbCE>0){
							while($rowCE=mysqli_fetch_array($resultCE)){
								$Emails.=$rowCE['EmailPro'].",";
							}
						}
						$Objet="Nouvel ingrédient à mettre à jour, suivi production prestation EISA";
						$MessageMail="	<html>
										<head><title>Nouvel ingrédient à mettre à jour, suivi production prestation EISA</title></head>
										<body>
											Bonjour,
											<br><br>
											Attention, un ingrédient non identifié dans la liste prédéfinie de votre outil de suivi production vient d'être saisi pour la prestation EISA<br>
											Veuillez vérifier l'exactitude des données saisies et mettre à jour la liste des ingrédients si cet ingrédient est amené à être utilisé sur votre prestation pour vos futurs travaux
											<br>Ingrédient : ".$Produit."
											<br>
											Bonne journée.<br>
											L'Extranet AAA.
										</body>
									</html>";
						if($Emails<>""){
							if(mail($Emails,$Objet,$MessageMail,$Headers,'-f extranet@aaa-aero.com'))
								{echo "Un message a été envoyé à ".$Emails."\n";}
							else{echo "Un message n'a pu être envoyé à ".$Emails."\n";}
						}
					}
				}
			 }
		}
		
		//Suppression des produits
		$req="DELETE FROM sp_atrot_produit WHERE Id_OT=".$_POST['id'];
		$resultDelete=mysqli_query($bdd,$req);
		
		//Ajout des produits
		$tabProduit = explode(";",$_POST['Produit']);
		foreach($tabProduit as $valeur){
			 if($valeur<>""){
				$Id_Produit=0;
				$ReferenceProduit="";
				$NumLot="";
				$DatePeremption="";
				$Coeff="";
				$Temperature="";
				$tabING = explode("ING_ING",$valeur);
				if($tabING[0]==0){
					$tabING2 = explode("_",$tabING[1]);
					$ReferenceProduit=$tabING2[0];
					$NumLot=$tabING2[1];
					$DatePeremption=TrsfDate_($tabING2[2]);
					$Coeff=$tabING2[3];
					$Temperature=$tabING2[4];
				}
				else{
					$tabING2 = explode("_",$tabING[0]);
					$Id_Produit=$tabING2[0];
					$NumLot=$tabING2[1];
					$DatePeremption=TrsfDate_($tabING2[2]);
					$Coeff=$tabING2[3];
					$Temperature=$tabING2[4];
				}

				$tab2 = explode("_",$valeur);
				$req="INSERT INTO sp_atrot_produit (Id_OT,Id_TypeProduit,NumLot,DatePeremption,Coeff,Temperature,Produit) VALUES (";
				$req.=$_POST['id'].",".$Id_Produit.",'".$NumLot."','".$DatePeremption."','".$Coeff."','".$Temperature."','".$ReferenceProduit."')";
				$resultAjour=mysqli_query($bdd,$req);
			 }
		}
		
		//Avant la mise à jour des PS 
		//Récupération des PS non identifiés & vérifier si existe déjà dans la BDD
		//Sinon envoi un mail aux CQP de la prestation pour les avertir
		$tabAIPI = explode(";",$_POST['lesAIPI']);
		foreach($tabAIPI as $valeur){
			 if($valeur<>""){
				$tabPS = explode("PS_PS",$valeur);
				$Qualif=$tabPS[1];
				if($Qualif<>""){
					$req="SELECT Id FROM sp_atrot_aipi WHERE Qualification='".addslashes($Qualif)."' AND Id_OT=".$_POST['id'];
					$resultSelect=mysqli_query($bdd,$req);
					$nbQualif=mysqli_num_rows($resultSelect);
					if($nbQualif==0){
						//Email 
						$Headers='From: "Extranet AAA"<extranet@aaa-aero.com>'."\n";
						$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
						
						$Emails="";
						$ReqResponsablePostePrestation="
							SELECT DISTINCT new_rh_etatcivil.EmailPro
							FROM
								new_competences_personne_poste_prestation,
								new_rh_etatcivil
							WHERE
								new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
								AND new_competences_personne_poste_prestation.Id_Prestation=463
								AND new_competences_personne_poste_prestation.Id_Poste=5
								AND new_rh_etatcivil.EmailPro<>''";
						$resultCQP=mysqli_query($bdd,$ReqResponsablePostePrestation);
						$nbCQP=mysqli_num_rows($resultCQP);
						if($nbCQP>0){
							while($rowCQP=mysqli_fetch_array($resultCQP)){
								$Emails.=$rowCQP['EmailPro'].",";
							}
						}
						$Objet="Nouveau PS identifié, suivi production prestation EISA";
						$MessageMail="	<html>
										<head><title>Nouveau PS identifié, suivi production prestation EISA</title></head>
										<body>
											Bonjour,
											<br><br>
											Attention, une référence de procédé spécial non identifiée au tableu de compétences vient d'être saisie dans le suivi production de la prestation EISA<br>
											PS : ".$Qualif."
											<br>
											Bonne journée.<br>
											L'Extranet AAA.
										</body>
									</html>";
						if($Emails<>""){
							if(mail($Emails,$Objet,$MessageMail,$Headers,'-f extranet@aaa-aero.com'))
								{echo "Un message a été envoyé à ".$Emails."\n";}
							else{echo "Un message n'a pu être envoyé à ".$Emails."\n";}
						}
					}
				}
			 }
		}

		//Suppression des PS
		$req="DELETE FROM sp_atrot_aipi WHERE Id_OT=".$_POST['id'];
		$resultDelete=mysqli_query($bdd,$req);
		
		//Ajout des PS
		$tabAIPI = explode(";",$_POST['lesAIPI']);
		foreach($tabAIPI as $valeur){
			 if($valeur<>""){
				$tabPS = explode("PS_PS",$valeur);
				$Id_Qualif=0;
				if($tabPS[0]<>""){$Id_Qualif=$tabPS[0];}
				$Qualif=$tabPS[1];
				$req="INSERT INTO sp_atrot_aipi (Id_OT,Id_Qualification,Qualification) VALUES (".$_POST['id'].",".$Id_Qualif.",'".addslashes($Qualif)."')";
				$resultAjour=mysqli_query($bdd,$req);
			 }
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
if($_GET){
	$Id=$_GET['Id'];
	if($_GET['Mode']=="M"){
		$req="SELECT Id,MSN,OrdreMontage,Designation,Id_StatutPROD,Id_StatutQUALITE,Id_CauseRetardPROD,Id_CauseRetardQUALITE,Commentaire,DateQUALITE,";
		$req.="(SELECT sp_atrmoteur.PosteMontage FROM sp_atrmoteur WHERE sp_atrmoteur.MSN=sp_atrot.MSN LIMIT 1) AS PosteMontage,DatePROD,PasDePS,ValidationPSCE,ValidationPSIQ,PasDeECMEPROD,PasDeECMEQUALITE, ";
		$req.="(SELECT sp_atrarticle.TypeMoteur FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article LIMIT 1) AS TypeMoteur,PasDeIngredient, ";
		$req.="(SELECT sp_atrarticle.MoteurSharklet FROM sp_atrarticle WHERE sp_atrarticle.Article=sp_atrot.Article LIMIT 1) AS MoteurSharklet ";
		$req.="FROM sp_atrot WHERE Id=".$Id;
		$result=mysqli_query($bdd,$req);
		$row=mysqli_fetch_array($result);
	}
	elseif($_GET['Mode']=="S"){
		$tab = explode(";",$Id);
		foreach($tab as $IdDossier){
			$req="UPDATE sp_atrot SET Supprime=1 WHERE Id=".$IdDossier;
			$resultSuppr=mysqli_query($bdd,$req);
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
?>
<form id="formulaire" class="test" method="POST" action="Modif_Dossier.php" onSubmit="return VerifChamps();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Modifier un dossier
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
			<tr>
				<td colspan="6" style="font-size:15px;font-weight:bold;color:#4d4a82;" align="center">INFORMATIONS DOSSIER</td>
			</tr>
			<tr><td height="4"></td></tr>
			<?php
				$read="";
				$disabled="";
				$etoile="<img src='../../../Images/etoile.png' width='8' height='8' border='0'>";
				if(substr($_SESSION['DroitSP'],0,1)=='0' && substr($_SESSION['DroitSP'],1,1)=='0' && substr($_SESSION['DroitSP'],4,1)=='0'){
					$read="readonly='readonly'";
					$disabled="disabled='disabled'";
					$etoile="";
				}
				$etoile2="<img src='../../../Images/etoile.png' width='8' height='8' border='0'>";
			?>
			<tr style="display:none;"><td><input id="id" name="id" value="<?php echo $Id; ?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="droit" name="droit" value="<?php echo $_SESSION['DroitSP'];?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="OLDstatutPROD" name="OLDstatutPROD" value="<?php echo $_SESSION['Id_StatutPROD'];?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="OLDstatutQUALITE" name="OLDstatutQUALITE" value="<?php echo $_SESSION['Id_StatutQUALITE'];?>"  readonly="readonly"></td></tr>
			<tr>
				<td width="13%" class="Libelle">&nbsp; MSN : </td>
				<td width="20%">
					<?php echo $row['MSN'];?>
				</td>
				<td width="13%" class="Libelle">&nbsp; Ordre de montage : </td>
				<td width="13%">
					<?php echo $row['OrdreMontage'];?>
				</td>
				<td width="13%" class="Libelle">&nbsp; Type de moteur : </td>
				<td width="20%">
					<?php 
						$TypeMoteur="?";
							if($row['TypeMoteur']<>""){$TypeMoteur=$row['TypeMoteur'];}
						echo stripslashes($TypeMoteur);
					?>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width="13%" class="Libelle">&nbsp; Désignation : </td>
				<td width="20%">
					<?php echo stripslashes($row['Designation']);?>
				</td>
				<td width="13%" class="Libelle">&nbsp; Moteur/Sharklet : </td>
				<td width="20%">
					<?php 
						$MoteurSharklet="?";
							if($row['MoteurSharklet']<>""){$MoteurSharklet=$row['MoteurSharklet'];}
						echo stripslashes($MoteurSharklet);
					?>
				</td>
				<td width="13%" class="Libelle">&nbsp; Poste de montage : </td>
				<td width="20%">
					<?php 
						$PosteMontage="?";
							if($row['PosteMontage']<>""){$PosteMontage=$row['PosteMontage'];}
						echo stripslashes($PosteMontage);
					?>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width="13%" class="Libelle" valign="top">&nbsp; Commentaire : </td>
				<td width="20%" colspan="6">
					<textarea id="commentaire" name="commentaire" rows="3" cols="70" style="resize:none;"><?php echo stripslashes($row['Commentaire']);?></textarea>
				</td>
			</tr>
			<tr><td height="8"></td></tr>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table width="100%" cellpadding="3" cellspacing="0" align="center" class="GeneralInfo">
			<tr>
				<?php
					$readSTCE="";
					$disabledSTCE="";
					$readCE="";
					$disabledCE="";
					$readIQ="";
					$disabledIQ="";
					$etoileSTCE="<img src='../../../Images/etoile.png' width='8' height='8' border='0'>";
					if(substr($_SESSION['DroitSP'],0,1)=='0' && substr($_SESSION['DroitSP'],1,1)=='0'){
						$readSTCE="readonly='readonly'";
						$disabledSTCE="disabled='disabled'";
						$etoileSTCE="";
					}
					if(substr($_SESSION['DroitSP'],1,1)=='0' && substr($_SESSION['DroitSP'],4,1)=='0'){
						$readCE="readonly='readonly'";
						$disabledCE="disabled='disabled'";
						$etoileCE="";
			
						$readIQ="readonly='readonly'";
						$disabledIQ="disabled='disabled'";
						$etoileIQ="";
					}
				?>
				<td colspan="2" width="33%" valign="top">
					<table width="100%" id="tableProd" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="4" colspan="2" align="center" style="font-size:14px;font-weight:bold;" bgcolor="#dbeef9">PROD</td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9">&nbsp; Date PROD : </td>
							<td width="20%" bgcolor="#dbeef9">
								<input style="text-align:center;" id="datePROD" name="datePROD" size="8" type="date" value="<?php echo AfficheDateFR($row['DatePROD']); ?>">
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9">&nbsp; Statut PROD : </td>
							<td width="20%" bgcolor="#dbeef9">
								<select id="statutProd" name="statutProd" <?php echo $disabledCE;?>>
									<option value=""></option>
									<option value="En cours" <?php if($row['Id_StatutPROD']=="En cours"){echo "selected";} ?>>En cours</option>
									<option value="TFS" <?php if($row['Id_StatutPROD']=="TFS"){echo "selected";} ?>>TFS</option>
									<option value="TERA" <?php if($row['Id_StatutPROD']=="TERA"){echo "selected";} ?>>TERA</option>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9">&nbsp; Cause retard PROD : </td>
							<td width="20%" bgcolor="#dbeef9">
								<select id="causeRetardProd" name="causeRetardProd" <?php echo $disabledCE;?>>
									<option name="0" value="0"></option>
									<?php
									$req="SELECT Id, Libelle, Supprime FROM sp_atrcauseretard WHERE Id_Prestation=463 ORDER BY Libelle;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowRetour=mysqli_fetch_array($result)){
											$selected = "";
											if($row['Id_CauseRetardPROD']==$rowRetour['Id']){$selected="selected";}
											if($row['Id_CauseRetardPROD']==$rowRetour['Id'] || $rowRetour['Supprime']==0){
												echo "<option name='".$rowRetour['Id']."' value='".$rowRetour['Id']."' ".$selected.">".$rowRetour['Libelle']."</option>";
											}
										}
									}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width='13%' class="Libelle" valign="top">
								<table cellpadding='0' cellspacing='0' width='100%' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor='#e4e7f0' colspan='2'><i>&nbsp; Ajouter les leaders :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; Personne : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
										<select id="leader" name="leader" style="width:130px;" onkeypress="if(event.keyCode == 13)AjouterLeader()">
											<?php
											echo"<option name='0' value='0'></option>";
											$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
											$req.="FROM new_rh_etatcivil LEFT JOIN sp_acces ON new_rh_etatcivil.Id=sp_acces.Id_Personne WHERE sp_acces.Id_Prestation=463 AND SUBSTR(sp_acces.Droit,2,1)='1' ORDER BY Nom, Prenom;";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											if ($nbResulta>0){
												$i=0;
												while($rowCE=mysqli_fetch_array($result)){
													echo "<option value='".$rowCE['Id']."'>".$rowCE['Nom']." ".$rowCE['Prenom']."</option>";
													echo "<script>Liste_CE[".$i."] = new Array('".$rowCE['Id']."','".addslashes($rowCE['Nom'])."','".addslashes($rowCE['Prenom'])."');</script>\n";
													$i+=1;
												}
											}
											?>
										</select>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
										<?php
											if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
										?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterCE()'>&nbsp;Ajouter&nbsp;</a>
										<?php
											}
										?>
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='20%' valign='top'>
								<table id="tab_CE" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle" width="70%">Personne</td>
									<?php
											$listeCE="";
											$req="SELECT Id, Id_Personne,";
											$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_atrot_ce.Id_Personne) AS NomPrenom ";
											$req.="FROM sp_atrot_ce WHERE Id_OT=".$Id." ORDER BY NomPrenom;";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											if ($nbResulta>0){
												while($rowCE=mysqli_fetch_array($result)){
													$btn="";
													if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
														$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerCE('".$rowCE['Id_Personne']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
													}
													echo "<tr id='".$rowCE['Id_Personne']."'><td>".$rowCE['NomPrenom']."</td><td>".$btn."</td></tr>";
													$listeCE.=$rowCE['Id_Personne'].";";
												}
											}
										?>
									</tr>
								</table>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width='20%' class="Libelle" valign="top">
								<table cellpadding='0' cellspacing='0' width='100%' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor='#e4e7f0' colspan='2'><i>&nbsp; Ajouter les compagnons :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; Personne : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' width='100%'>&nbsp; 
											<div id='Div_Compagnon' style='height:200px;overflow:auto;'>
											<?php
											echo "<table width='100%'>";
											$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
											$req.="FROM new_rh_etatcivil LEFT JOIN sp_acces ON new_rh_etatcivil.Id=sp_acces.Id_Personne WHERE sp_acces.Id_Prestation=463 AND SUBSTR(sp_acces.Droit,3,1)='1' ORDER BY Nom, Prenom;";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											if ($nbResulta>0){
												$i=0;
												while($rowCompagnon=mysqli_fetch_array($result)){
													echo "<tr><td><input type='checkbox' class='compagnons' name='".$rowCompagnon['Id']."' value='".$rowCompagnon['Id']."'>".$rowCompagnon['Nom']." ".$rowCompagnon['Prenom']."</td></tr>";
													echo "<script>Liste_Personne[".$i."] = new Array('".$rowCompagnon['Id']."','".addslashes($rowCompagnon['Nom'])."','".addslashes($rowCompagnon['Prenom'])."');</script>\n";
													$i+=1;
												}
											}
											echo "</table>";
											?>
											</div>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
										<?php
											if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
										?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterTE()'>&nbsp;Ajouter&nbsp;</a>
										<?php
											}
										?>
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='20%' valign='top'>
								<table id="tab_TravailEffectue" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle" width="70%">Personne</td>
									<?php
											$listeCompagnon="";
											$req="SELECT Id, Id_Personne,";
											$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_atrot_compagnon.Id_Personne) AS NomPrenom ";
											$req.="FROM sp_atrot_compagnon WHERE Id_OT=".$Id." ORDER BY NomPrenom;";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											if ($nbResulta>0){
												while($rowCompagnon=mysqli_fetch_array($result)){
													$btn="";
													if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
														$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerTE('".$rowCompagnon['Id_Personne']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
													}
													echo "<tr id='".$rowCompagnon['Id_Personne']."'><td>".$rowCompagnon['NomPrenom']."</td><td>".$btn."</td></tr>";
													$listeCompagnon.=$rowCompagnon['Id_Personne'].";";
												}
											}
										?>
									</tr>
								</table>
							</td>
						</tr>
						<tr><td height="8" colspan="5" bgcolor="#dbeef9"></td></tr>
					</table>
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="4" colspan="2"></td></tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr><td height="4" colspan="2" align="center" style="font-size:14px;font-weight:bold;" bgcolor="#c7e048">QUALITE</td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#c7e048">&nbsp; Date QUALITE : </td>
							<td width="20%" bgcolor="#c7e048">
								<input style="text-align:center;" id="dateQUALITE" name="dateQUALITE" size="8" type="date" value="<?php echo AfficheDateFR($row['DateQUALITE']); ?>">
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#c7e048">&nbsp; Statut QUALITE : </td>
							<td width="20%" bgcolor="#c7e048">
								<div id="statutsQualite">
									<select id="statutQualite" name="statutQualite" <?php echo $disabledIQ;?>>
										<option name="" value=""></option>
										<option value="En cours" <?php if($row['Id_StatutQUALITE']=="En cours"){echo "selected";} ?>>En cours</option>
										<option value="TVS" <?php if($row['Id_StatutQUALITE']=="TVS"){echo "selected";} ?>>TVS</option>
										<option value="TERC" <?php if($row['Id_StatutQUALITE']=="TERC"){echo "selected";} ?>>TERC</option>
									</select>
								</div>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td width="10%" class="Libelle" bgcolor="#c7e048">&nbsp; Cause retard QUALITE : </td>
							<td width="20%" bgcolor="#c7e048">
								<select id="causeRetardQualite" name="causeRetardQualite" <?php echo $disabledIQ;?>>
									<option name="0" value="0"></option>
									<?php
									$req="SELECT Id, Libelle, Supprime FROM sp_atrcauseretard WHERE Id_Prestation=463 ORDER BY Libelle;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowRetour=mysqli_fetch_array($result)){
											$selected = "";
											if($row['Id_CauseRetardQUALITE']==$rowRetour['Id']){$selected="selected";}
											if($row['Id_CauseRetardQUALITE']==$rowRetour['Id'] || $rowRetour['Supprime']==0){
												echo "<option name='".$rowRetour['Id']."' value='".$rowRetour['Id']."' ".$selected.">".$rowRetour['Libelle']."</option>";
											}
										}
									}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td bgcolor="#c7e048" width='10%' class="Libelle" valign="top">
								<table cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor='#e4e7f0' colspan='2'><i>&nbsp; Ajouter les contrôleurs :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; Personne : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
										<select id="controleur" name="controleur" style="width:130px;" onkeypress="if(event.keyCode == 13)AjouterControleur()">
											<?php
											echo"<option name='0' value='0'></option>";
											$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
											$req.="FROM new_rh_etatcivil LEFT JOIN sp_acces ON new_rh_etatcivil.Id=sp_acces.Id_Personne WHERE sp_acces.Id_Prestation=463 AND SUBSTR(sp_acces.Droit,5,1)='1' ORDER BY Nom, Prenom;";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											if ($nbResulta>0){
												$i=0;
												while($rowIQ=mysqli_fetch_array($result)){
													echo "<option value='".$rowIQ['Id']."'>".$rowIQ['Nom']." ".$rowIQ['Prenom']."</option>";
													echo "<script>Liste_IQ[".$i."] = new Array('".$rowIQ['Id']."','".addslashes($rowIQ['Nom'])."','".addslashes($rowIQ['Prenom'])."');</script>\n";
													$i+=1;
												}
											}
											?>
										</select>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
										<?php
											if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
										?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterControleur()'>&nbsp;Ajouter&nbsp;</a>
										<?php
											}
										?>
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#c7e048" width='20%' valign='top'>
								<table id="tab_IQ" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle" width="70%">Personne</td>
									<?php
											$listeQualite="";
											$req="SELECT Id, Id_Personne,";
											$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_atrot_controleur.Id_Personne) AS NomPrenom ";
											$req.="FROM sp_atrot_controleur WHERE Id_OT=".$Id." ORDER BY NomPrenom;";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											if ($nbResulta>0){
												while($rowCompagnon=mysqli_fetch_array($result)){
													$btn="";
													if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
														$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerTE('".$rowCompagnon['Id_Personne']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
													}
													echo "<tr id='".$rowCompagnon['Id_Personne']."'><td>".$rowCompagnon['NomPrenom']."</td><td>".$btn."</td></tr>";
													$listeQualite.=$rowCompagnon['Id_Personne'].";";
												}
											}
										?>
									</tr>
								</table>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
					</table>
				</td>
				<td colspan="2" width="33%" valign="top">
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="10" colspan="2"></td></tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width='35%' class="Libelle" valign="center">
								<table width="100%" cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor="#e4e7f0" colspan='2'><i>&nbsp; Ajouter les ECME PROD :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; Type : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<select id="typeECMEPROD" name="typeECMEPROD" style="width:100px;" onchange="Recharge_RefECMEPROD()" onkeypress="if(event.keyCode == 13)AjouterECMEPROD()">
												<?php
												echo"<option name='0' value='0'></option>";
												$req="SELECT Id, Libelle FROM sp_atrtypeecme WHERE Supprime=false AND Id_Prestation=463 ";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												if ($nbResulta>0){
													$i=0;
													while($rowType=mysqli_fetch_array($result)){
														echo "<option value='".$rowType['Id']."'>".$rowType['Libelle']."</option>";
														echo "<script>Liste_ECME[".$i."] = new Array('".$rowType['Id']."','".str_replace("'"," ",$rowType['Libelle'])."');</script>\n";
														$i+=1;
													}
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;Référence : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>
											<div id="listeECMEPROD">
												&nbsp; <select id="referencePROD" name="referencePROD" style="width:100px;" onkeypress="if(event.keyCode == 13)AjouterECMEPROD()">
													<?php
													echo"<option value='0'></option>";
													$req="SELECT Id, Libelle,Id_Type FROM sp_atrecme WHERE Supprime=false AND Id_Prestation=463 ";
													$result=mysqli_query($bdd,$req);
													$nbResulta=mysqli_num_rows($result);
													if ($nbResulta>0){
														$i=0;
														while($rowECME=mysqli_fetch_array($result)){
															echo "<script>Liste_RefECMEPROD[".$i."] = new Array('".$rowECME['Id']."','".$rowECME['Id_Type']."','".str_replace("'"," ",$rowECME['Libelle'])."');</script>\n";
															$i+=1;
														}
													}
													?>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;OU autre ECME </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:left;" id="nomECMEPROD" onkeypress="if(event.keyCode == 13)AjouterECMEPROD()" name="nomECMEPROD" size="20" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
											<?php
												if(substr($_SESSION['DroitSP'],1,1)=='1'){
											?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterECMEPROD()'>&nbsp;Ajouter&nbsp;</a>
											<?php
												}
											?>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<?php
												$modifiableCE="";
												if(substr($_SESSION['DroitSP'],1,1)=='0'){$modifiableCE="onclick='return false'";}
												$modificationIQ="";
												if(substr($_SESSION['DroitSP'],4,1)=='0'){$modificationIQ="onclick='return false'";}
											?>
											<input style="text-align:left;" id="PasDeECMEPROD" <?php echo $modifiableCE; ?> name="PasDeECMEPROD" type="checkbox" value="PasDeECMEPROD" <?php if($row['PasDeECMEPROD']==1){echo "checked";} ?>> Pas de ECME requis
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='65%' valign='top'>
								<table id="tab_ECMEPROD" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle">Type</td><td class="Libelle">Référence</td></tr>
									<?php
										$listeECMEPROD="";
										$req="SELECT Id_ECME,
										IF(Id_ECME>0,sp_atrecme.Libelle,sp_atrot_ecme.Reference) AS Libelle,
										IF(Id_ECME>0,sp_atrecme.Id_Type,sp_atrot_ecme.Id_TypeECME) AS Id_Type,
										IF(Id_ECME>0,(SELECT Libelle FROM sp_atrtypeecme WHERE sp_atrtypeecme.Id=sp_atrecme.Id_Type),
										(SELECT Libelle FROM sp_atrtypeecme WHERE sp_atrtypeecme.Id=sp_atrot_ecme.Id_TypeECME)) AS Type
										FROM sp_atrot_ecme 
										LEFT JOIN sp_atrecme 
										ON sp_atrot_ecme.Id_ECME=sp_atrecme.Id 
										WHERE sp_atrot_ecme.ProdQualite=0 
										AND Id_OT=".$Id." ORDER BY Libelle;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($rowECME=mysqli_fetch_array($result)){
												$btn="";
												if($rowECME['Id_ECME']>0){
													if(substr($_SESSION['DroitSP'],1,1)=='1'){
														$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerECMEPROD('".$rowECME['Id_ECME']."_".$rowECME['Id_Type']."ECME_ECME')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
													}
													echo "<tr id='ECMEPROD".$rowECME['Id_ECME']."_".$rowECME['Id_Type']."ECME_ECME'>";
													$listeECMEPROD.=$rowECME['Id_ECME']."_".$rowECME['Id_Type']."ECME_ECME;";
												}
												else{
													if(substr($_SESSION['DroitSP'],1,1)=='1'){
														$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerECMEPROD('0ECME_ECME".$rowECME['Libelle']."_".$rowECME['Id_Type']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
													}
													echo "<tr id='ECMEPROD0ECME_ECME".$rowECME['Libelle']."_".$rowECME['Id_Type']."'>";
													$listeECMEPROD.="0ECME_ECME".$rowECME['Libelle']."_".$rowECME['Id_Type'].";";
												}
												echo "<td>".$rowECME['Type']."</td><td>".$rowECME['Libelle']."</td><td>".$btn."</td></tr>";
												
											}
										}
									?>
								</table>
							</td>
						</tr>
						<tr><td height="8" colspan="2" bgcolor="#dbeef9"></td></tr>
					</table></br>
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="10" colspan="2"></td></tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width='35%' class="Libelle" valign="center">
								<table width="100%" cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor="#e4e7f0" colspan='2'><i>&nbsp; Ajouter les ECME QUALITE :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; Type : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<select id="typeECMEQUALITE" name="typeECMEQUALITE" style="width:100px;" onchange="Recharge_RefECMEQUALITE()" onkeypress="if(event.keyCode == 13)AjouterECMEQUALITE()">
												<?php
												echo"<option name='0' value='0'></option>";
												$req="SELECT Id, Libelle FROM sp_atrtypeecme WHERE Supprime=false AND Id_Prestation=463 ";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												if ($nbResulta>0){
													$i=0;
													while($rowType=mysqli_fetch_array($result)){
														echo "<option value='".$rowType['Id']."'>".$rowType['Libelle']."</option>";
														echo "<script>Liste_ECME[".$i."] = new Array('".$rowType['Id']."','".str_replace("'"," ",$rowType['Libelle'])."');</script>\n";
														$i+=1;
													}
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;Référence : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>
											<div id="listeECMEQUALITE">
												&nbsp; <select id="referenceQUALITE" name="referenceQUALITE" style="width:100px;" onkeypress="if(event.keyCode == 13)AjouterECMEQUALITE()">
													<?php
													echo"<option value='0'></option>";
													$req="SELECT Id, Libelle,Id_Type FROM sp_atrecme WHERE Supprime=false AND Id_Prestation=463 ";
													$result=mysqli_query($bdd,$req);
													$nbResulta=mysqli_num_rows($result);
													if ($nbResulta>0){
														$i=0;
														while($rowECME=mysqli_fetch_array($result)){
															echo "<script>Liste_RefECMEQUALITE[".$i."] = new Array('".$rowECME['Id']."','".$rowECME['Id_Type']."','".str_replace("'"," ",$rowECME['Libelle'])."');</script>\n";
															$i+=1;
														}
													}
													?>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;OU autre ECME </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:left;" id="nomECMEQUALITE" onkeypress="if(event.keyCode == 13)AjouterECMEQUALITE()" name="nomECMEQUALITE" size="20" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
											<?php
												if(substr($_SESSION['DroitSP'],4,1)=='1'){
											?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterECMEQUALITE()'>&nbsp;Ajouter&nbsp;</a>
											<?php
												}
											?>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:left;" <?php echo $modificationIQ; ?> id="PasDeECMEQUALITE" name="PasDeECMEQUALITE" type="checkbox" value="PasDeECMEQUALITE" <?php if($row['PasDeECMEQUALITE']==1){echo "checked";} ?>> Pas de ECME requis
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='65%' valign='top'>
								<table id="tab_ECMEQUALITE" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle">Type</td><td class="Libelle">Référence</td></tr>
									<?php
										$listeECMEQUALITE="";
										$req="SELECT Id_ECME,
										IF(Id_ECME>0,sp_atrecme.Libelle,sp_atrot_ecme.Reference) AS Libelle,
										IF(Id_ECME>0,sp_atrecme.Id_Type,sp_atrot_ecme.Id_TypeECME) AS Id_Type,
										IF(Id_ECME>0,(SELECT Libelle FROM sp_atrtypeecme WHERE sp_atrtypeecme.Id=sp_atrecme.Id_Type),
										(SELECT Libelle FROM sp_atrtypeecme WHERE sp_atrtypeecme.Id=sp_atrot_ecme.Id_TypeECME)) AS Type
										FROM sp_atrot_ecme 
										LEFT JOIN sp_atrecme 
										ON sp_atrot_ecme.Id_ECME=sp_atrecme.Id 
										WHERE sp_atrot_ecme.ProdQualite=1 
										AND Id_OT=".$Id." ORDER BY Libelle;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($rowECME=mysqli_fetch_array($result)){
												$btn="";
												if($rowECME['Id_ECME']>0){
													if(substr($_SESSION['DroitSP'],4,1)=='1'){
														$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerECMEQUALITE('".$rowECME['Id_ECME']."_".$rowECME['Id_Type']."ECME_ECME')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
													}
													echo "<tr id='ECMEQUALITE".$rowECME['Id_ECME']."_".$rowECME['Id_Type']."ECME_ECME'>";
													$listeECMEQUALITE.=$rowECME['Id_ECME']."_".$rowECME['Id_Type']."ECME_ECME;";
												}
												else{
													if(substr($_SESSION['DroitSP'],4,1)=='1'){
														$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerECMEQUALITE('0ECME_ECME".$rowECME['Libelle']."_".$rowECME['Id_Type']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
													}
													echo "<tr id='ECMEQUALITE0ECME_ECME".$rowECME['Libelle']."_".$rowECME['Id_Type']."'>";
													$listeECMEQUALITE.="0ECME_ECME".$rowECME['Libelle']."_".$rowECME['Id_Type'].";";
												}
												echo "<td>".$rowECME['Type']."</td><td>".$rowECME['Libelle']."</td><td>".$btn."</td></tr>";
											}
										}
									?>
								</table>
							</td>
						</tr>
						<tr><td height="8" colspan="2" bgcolor="#dbeef9"></td></tr>
					</table></br>
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="10" colspan="2"></td></tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width='35%' class="Libelle" valign="center">
								<table width="100%" cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor="#e4e7f0" colspan='2'><i>&nbsp; Ajouter les ECME CLIENT :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; N° client : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:left;" id="numClient" onkeypress="if(event.keyCode == 13)AjouterECMECLIENT()" name="numClient" size="20" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;Date de fin d'étalonnage : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:left;" id="dateEtalonnageECMEClient" onkeypress="if(event.keyCode == 13)AjouterECMECLIENT()" name="dateEtalonnageECMEClient" size="20" type="date" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
											<?php
												if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
											?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterECMECLIENT()'>&nbsp;Ajouter&nbsp;</a>
											<?php
												}
											?>
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='65%' valign='top'>
								<table id="tab_ECMECLIENT" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle">N° Client</td><td class="Libelle">Date de fin d'étalonnage</td></tr>
									<?php
										$listeECMECLIENT="";
										$req="SELECT NumClient,DateFinEtalonnage
										FROM sp_atrot_ecmeclient 
										WHERE Id_OT=".$Id." ORDER BY NumClient;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($rowECME=mysqli_fetch_array($result)){
												$btn="";
												if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
													$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerECMECLIENT('".$rowECME['NumClient']."_".AfficheDateFR($rowECME['DateFinEtalonnage'])."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
												}
												echo "<tr id='ECMECLIENT".$rowECME['NumClient']."_".AfficheDateFR($rowECME['DateFinEtalonnage'])."'>";
												$listeECMECLIENT.=$rowECME['NumClient']."_".AfficheDateFR($rowECME['DateFinEtalonnage']).";";
											
												echo "<td>".$rowECME['NumClient']."</td><td>".AfficheDateFR($rowECME['DateFinEtalonnage'])."</td><td>".$btn."</td></tr>";
											}
										}
									?>
								</table>
							</td>
						</tr>
						<tr><td height="8" colspan="2" bgcolor="#dbeef9"></td></tr>
					</table></br>
					<table width="100%" id="tableProduit" cellpadding="0" cellspacing="0" align="center">
						<tr>
							<td bgcolor="#dbeef9" width='13%' class="Libelle" valign="top">
								<table width="100%" cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor="#e4e7f" colspan='2'><i>&nbsp; Ajouter les produits :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; Référence : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<select id="RefProduit" name="RefProduit" onkeypress="if(event.keyCode == 13)AjouterProduit()" style="width:130px;">
												<?php
												echo"<option name='0' value='0'></option>";
												$req="SELECT Id, Libelle FROM sp_atrtypeproduit WHERE Supprime=false AND Id_Prestation=463 ORDER BY Libelle;";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												if ($nbResulta>0){
													$i=0;
													while($rowIngredient=mysqli_fetch_array($result)){
														echo "<option name='".$rowIngredient['Id']."' value='".$rowIngredient['Id']."'>".$rowIngredient['Libelle']."</option>";
														echo "<script>Liste_Produit[".$i."] = new Array('".$rowIngredient['Id']."','".addslashes($rowIngredient['Libelle'])."');</script>\n";
														$i+=1;
													}
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;OU autre référence </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:left;" id="nomProduit" onkeypress="if(event.keyCode == 13)AjouterProduit()" name="nomProduit" size="20" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;N° lot : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" id="numLot" onkeypress="if(event.keyCode == 13)AjouterProduit()" name="numLot" size="8" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor="#e4e7f0">&nbsp;Date peremption : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" id="datePeremption" onkeypress="if(event.keyCode == 13)AjouterProduit()" name="datePeremption" size="8" type="date" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor="#e4e7f0">&nbsp;Coeff. Hygrométrique : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" onkeypress="if(event.keyCode == 13)AjouterProduit()" id="coeffH" name="coeffH" size="8" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor="#e4e7f0">&nbsp;Température : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" onkeypress="if(event.keyCode == 13)AjouterProduit()" id="temperature" name="temperature" size="8" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
										<?php
											if(substr($_SESSION['DroitSP'],1,1)=='1'){
										?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterProduit()'>&nbsp;Ajouter&nbsp;</a>
										<?php
											}
										?>	
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:left;" <?php echo $modifiableCE; ?> id="PasDeIngredient" name="PasDeIngredient" type="checkbox" value="PasDeIngredient" <?php if($row['PasDeIngredient']==1){echo "checked";} ?>> Pas d'ingredient requis
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='20%' valign='top'>
								<table id="tab_Produit" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle">Ingrédient</td><td class="Libelle">N° lot</td><td class="Libelle">Date<br>péremption</td><td class="Libelle">Coeff.<br>hygrométrique</td><td class="Libelle">Température</td></tr>
									<?php
										$listeProduit="";
										$req="SELECT Id_TypeProduit,NumLot,DatePeremption,Coeff,Temperature,";
										$req.="IF(Id_TypeProduit>0,(SELECT sp_atrtypeproduit.Libelle FROM sp_atrtypeproduit WHERE sp_atrtypeproduit.Id=sp_atrot_produit.Id_TypeProduit),sp_atrot_produit.Produit) AS Produit ";
										$req.="FROM sp_atrot_produit WHERE Id_OT=".$Id." ORDER BY Produit;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($rowIngredient=mysqli_fetch_array($result)){
												$btn="";
												if($rowIngredient['Id_TypeProduit']>0){
													if(substr($_SESSION['DroitSP'],1,1)=='1'){
														$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerProduit('".$rowIngredient['Id_TypeProduit']."_".$rowIngredient['NumLot']."_".AfficheDateFR($rowIngredient['DatePeremption'])."_".$rowIngredient['Coeff']."_".$rowIngredient['Temperature']."ING_ING')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
													}
													$listeProduit.=$rowIngredient['Id_TypeProduit']."_".$rowIngredient['NumLot']."_".AfficheDateFR($rowIngredient['DatePeremption'])."_".$rowIngredient['Coeff']."_".$rowIngredient['Temperature']."ING_ING;";
													echo "<tr id='".$rowIngredient['Id_TypeProduit']."_".$rowIngredient['NumLot']."_".AfficheDateFR($rowIngredient['DatePeremption'])."_".$rowIngredient['Coeff']."_".$rowIngredient['Temperature']."ING_ING'>";
												}
												else{
													if(substr($_SESSION['DroitSP'],1,1)=='1'){
														$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerProduit('0ING_ING".stripslashes($rowIngredient['Produit'])."_".$rowIngredient['NumLot']."_".AfficheDateFR($rowIngredient['DatePeremption'])."_".$rowIngredient['Coeff']."_".$rowIngredient['Temperature']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
													}
													$listeProduit.="0ING_ING".stripslashes($rowIngredient['Produit'])."_".$rowIngredient['NumLot']."_".AfficheDateFR($rowIngredient['DatePeremption'])."_".$rowIngredient['Coeff']."_".$rowIngredient['Temperature'].";";
													echo "<tr id='0ING_ING".stripslashes($rowIngredient['Produit'])."_".$rowIngredient['NumLot']."_".AfficheDateFR($rowIngredient['DatePeremption'])."_".$rowIngredient['Coeff']."_".$rowIngredient['Temperature']."'>";
												}
												echo "<td>".$rowIngredient['Produit']."</td><td>".$rowIngredient['NumLot']."</td>";
												echo "<td>".AfficheDateFR($rowIngredient['DatePeremption'])."</td><td>".$rowIngredient['Coeff']."</td><td>".$rowIngredient['Temperature']."</td><td>".$btn."</td></tr>";
												
											}
										}
									?>
								</table>
							</td>
						</tr>
						<tr><td height="8" colspan="2" bgcolor="#dbeef9"></td></tr>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="10" colspan="2"></td></tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width='35%' class="Libelle" valign="top">
								<table width="100%" cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor="#e4e7f0"><i>&nbsp; Ajouter les procédés spéciaux :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;Module : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<select id="module" name="module" onchange="Recharge_PS()" style="width:100px;">
												<?php
												echo"<option value='0'></option>";
												//Liste des modules
												$req="SELECT DISTINCT Module 
													FROM sp_correspondancemodule 
													ORDER BY Module";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												if ($nbResulta>0){
													while($rowModule=mysqli_fetch_array($result)){
														echo "<option value='".$rowModule['Module']."'>".$rowModule['Module']."</option>";
													}
												}
												
												//Liste des modules
												$req="SELECT DISTINCT Id_Qualification, Module 
													FROM sp_correspondancemodule 
													ORDER BY Module";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												if ($nbResulta>0){
													$i=0;
													while($rowModule=mysqli_fetch_array($result)){
														echo "<script>Liste_Module[".$i."] = new Array('".$rowModule['Module']."','".addslashes($rowModule['Id_Qualification'])."');</script>\n";
														$i+=1;
													}
												}
												
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;Référence : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>
											<div id="listeRefAIPI">
											<select id="RefAIPI" name="RefAIPI" onkeypress="if(event.keyCode == 13)AjouterAIPIS()" style="width:130px;">
												<?php
												echo"<option name='0' value='0'></option>";
												//Liste des specials process
												$req="SELECT DISTINCT new_competences_qualification.Id,new_competences_qualification.Libelle ";
												$req.="FROM new_competences_prestation_qualification ";
												$req.="LEFT JOIN new_competences_qualification ";
												$req.="ON new_competences_prestation_qualification.Id_Qualification = new_competences_qualification.Id ";
												$req.="WHERE new_competences_prestation_qualification.Id_Prestation=463 ";
												$req.="AND (SELECT COUNT(new_competences_categorie_qualification.Id) 
															FROM new_competences_categorie_qualification 
															WHERE new_competences_categorie_qualification.Id=
															new_competences_qualification.Id_Categorie_Qualification
															AND new_competences_categorie_qualification.Id_Categorie_Maitre=2)>0 
														ORDER BY new_competences_qualification.Libelle";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												if ($nbResulta>0){
													$i=0;
													while($rowAIPI=mysqli_fetch_array($result)){
														echo "<option name='".$rowAIPI['Id']."' value='".$rowAIPI['Id']."'>".$rowAIPI['Libelle']."</option>";
														echo "<script>Liste_AIPI[".$i."] = new Array('".$rowAIPI['Id']."','".addslashes($rowAIPI['Libelle'])."');</script>\n";
														$i+=1;
													}
												}
												?>
											</select>
											</div>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;OU autre procédé spécial </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:left;" id="nomPS" onkeypress="if(event.keyCode == 13)AjouterAIPIS()" name="nomPS" size="20" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' style='height:25px;' valign='center'>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterAIPIS()'>&nbsp;Ajouter&nbsp;</a>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:left;" id="PasDePS" name="PasDePS" type="checkbox" value="PasDePS" onchange="ValidationAutoPS()" <?php if($row['PasDePS']==1){echo "checked";} ?>> Pas de procédé spécial requis
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='65%' valign='top'>
								<table id="tab_AIPI" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle" align="center">Procédés spéciaux appelés</td></tr>
									<?php
										$listeAIPI="";
										$req="SELECT Id_Qualification,Qualification, ";
										$req.="IF(Id_Qualification>0,(SELECT new_competences_qualification.Libelle ";
										$req.="FROM new_competences_qualification WHERE new_competences_qualification.Id=sp_atrot_aipi.Id_Qualification),Qualification) AS Libelle ";
										$req.="FROM sp_atrot_aipi WHERE Id_OT=".$Id." ORDER BY Libelle;";
										
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($rowAIPI=mysqli_fetch_array($result)){
												$btn="";
												if(substr($_SESSION['DroitSP'],1,1)=='1'){
													$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerAIPIS('".$rowAIPI['Id_Qualification']."PS_PS".str_replace("'"," ",stripslashes($rowAIPI['Qualification']))."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
												}
												echo "<tr id='".$rowAIPI['Id_Qualification']."PS_PS".stripslashes($rowAIPI['Qualification'])."'>";
												echo "<td>".$rowAIPI['Libelle']."</td><td>".$btn."</td></tr>";
												$listeAIPI.=$rowAIPI['Id_Qualification']."PS_PS".str_replace("'"," ",stripslashes($rowAIPI['Qualification'])).";";
											}
										}
									?>
								</table>
							</td>
						</tr>
						<tr>
							<td bgcolor='#dbeef9'>&nbsp; 
								
							</td>
							<td bgcolor='#dbeef9'>&nbsp; 
								 
								<input <?php echo $modifiableCE; ?> style="text-align:left;" id="ValidationPSCE" name="ValidationPSCE" type="checkbox" value="ValidationPSCE" <?php if($row['ValidationPSCE']==1){echo "checked";} ?>> Validation Chef d'équipe
								&nbsp; &nbsp; &nbsp; <input <?php echo $modificationIQ; ?> style="text-align:left;" id="ValidationPSIQ" name="ValidationPSIQ" type="checkbox" value="ValidationPSIQ" <?php if($row['ValidationPSIQ']==1){echo "checked";} ?>> Validation Qualité
							</td>
						</tr>
						<tr><td height="8" colspan="2" bgcolor="#dbeef9"></td></tr>
					</table>
				</td>
			</tr>
			<tr style="display:none;"><td><input id="lescompagnons" name="lescompagnons" value="<?php echo $listeCompagnon;?>" readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="lesces" name="lesces" value="<?php echo $listeCE;?>" readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="IQ" name="IQ" value="<?php echo $listeQualite;?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="ECMEPROD" name="ECMEPROD" value="<?php echo $listeECMEPROD;?>" readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="ECMEQUALITE" name="ECMEQUALITE" value="<?php echo $listeECMEQUALITE;?>" readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="ECMECLIENT" name="ECMECLIENT" value="<?php echo $listeECMECLIENT;?>" readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="Produit" name="Produit" value="<?php echo $listeProduit;?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id='lesAIPI' name='lesAIPI' value="<?php echo $listeAIPI;?>"  readonly='readonly'></td></tr>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<?php
		if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
		?>
		<td colspan="6" align="center">
			<div id="Enregistrer">
			</div>	
			<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="AfficherSubmit()">
		</td>
		<?php
		}
		?>
	
	</tr>
	<tr><td height="4"></td></tr>
</table>
</form>
<?php
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
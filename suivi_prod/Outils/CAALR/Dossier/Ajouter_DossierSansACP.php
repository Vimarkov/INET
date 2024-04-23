<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../JS/Wickedpicker/stylesheets/wickedpicker.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>		
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script src="../../JS/Wickedpicker/src/wickedpicker.js"></script>
	<script language="javascript" src="Dossier.js"></script>
	<script type="text/javascript">
		$(function(){
			$('.timepicker-two').wickedpicker({twentyFour: true});
		});
	</script>
</head>
<?php
require("../../../Menu.php");
require("../../Fonctions.php");

Ecrire_Code_JS_Init_Date();

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
$HeureJour = date("H:i:s");
$modePoste = 0;
$modeDuplication=0;
if($_POST){
	if(isset($_POST['btnEnregistrer2'])){
		$modePoste = 1;
		$dateTERC="0001-01-01";
		$dateRenvoiNC="0001-01-01";
		if($_POST['dateTERCPrevisionnelle']<>""){$dateTERC=TrsfDate_($_POST['dateTERCPrevisionnelle']);}
		if($_POST['dateRenvoiNC']<>""){$dateRenvoiNC=TrsfDate_($_POST['dateRenvoiNC']);}
		
		//Ajout du dossier
		$req="INSERT INTO sp_olwdossier (Id_Prestation,Id_Personne,MSN,DateRenvoiNC,Imputation,Id_Client,";
		$req.="ReferenceNC,DateNC,HeureNC,";
		$req.="ReferenceAM,DateAM,HeureAM,";
		$req.="ReferencePF,DatePF,HeurePF,";
		$req.="Reference,DateDossier,HeureDossier,";
		$req.="TypeACP,SectionACP,CaecACP,Priorite,Titre,DateCreation,DateTERCPrevisionnelle,";
		$req.="Systeme,Structure,Metal,Composite,Id_ZoneDeTravail,Id_Statut,Avancement,Id_Retour,CommentaireZICIA,Programme)";
		
		$req.=" VALUES (418,".$_SESSION['Id_PersonneSP'].",".$_POST['msn'].",'".$dateRenvoiNC."','".$_POST['imputation']."',".$_POST['client'].",";
		if($_POST['numNC']<>""){$req.="'".addslashes($_POST['numNC'])."','".$DateJour."','".$HeureJour."',";}
		else{$req.="'','0001-01-01','00:00:00',";}
		if($_POST['numAM']<>""){$req.="'".addslashes($_POST['numAM'])."','".$DateJour."','".$HeureJour."',";}
		else{$req.="'','0001-01-01','00:00:00',";}
		if($_POST['numPF']<>""){$req.="'".addslashes($_POST['numPF'])."','".$DateJour."','".$HeureJour."',";}
		else{$req.="'','0001-01-01','00:00:00',";}
		if($_POST['numDossier']<>""){$req.="'".addslashes($_POST['numDossier'])."','".$DateJour."','".$HeureJour."',";}
		else{$req.="'','0001-01-01','00:00:00',";}
		
		$req.="'".addslashes($_POST['typeDossier'])."','".$_POST['section']."','".$_POST['caec']."',".$_POST['priorite'].",'".addslashes($_POST['titre'])."',";
		$req.="'".$DateJour."','".$dateTERC."',";
		if(isset($_POST['Systeme'])){$req.="1,";}else{$req.="0,";}
		if(isset($_POST['Structure'])){$req.="1,";}else{$req.="0,";}
		if(isset($_POST['Metal'])){$req.="1,";}else{$req.="0,";}
		if(isset($_POST['Composite'])){$req.="1,";}else{$req.="0,";}
		$req.="".$_POST['zone'].",";
		if(substr($_SESSION['DroitSP'],4,1)=='1'){
			if($_POST['statutQualite']<>"0"){$req.="'".$_POST['statutQualite']."',0,".$_POST['retourQualite'].",";}
			else{$req.="'".$_POST['statutProd']."',".$_POST['avancementProd'].",".$_POST['retourProd'].",";}
		}
		elseif(substr($_SESSION['DroitSP'],1,1)=='1'){
			$req.="'".$_POST['statutProd']."',".$_POST['avancementProd'].",".$_POST['retourProd'].",";
		}
		else{
			$req.="'',0,0,";
		}
		$req.="'".addslashes($_POST['commentaireZI'])."','".addslashes($_POST['programme'])."')";
		$resultAjour=mysqli_query($bdd,$req);
		$IdCree = mysqli_insert_id($bdd);
		if($IdCree<>0){
			//Ajout de la fiche d'intervention
			$req="INSERT INTO sp_olwficheintervention (";
			$req.="Id_Dossier,Id_Createur,DateCreation,PosteAvionACP,DeroNecessaire,TravailRealise,Commentaire,DateAppro,DateDA,NumDERO,NumDA,OutillageSpecifique,TypeAppro,AMLiee,OTLie,StatutPrepa,DatePrepa,PasDePS,PasDeECMEPROD,PasDeECMEQUALITE,PasDeIngredient,ValidationPSCE,ValidationPSIQ";
			if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1'){
				$req.=",DateIntervention,Vacation,NumFI,TempsObjectif";
			}
			if(substr($_SESSION['DroitSP'],1,1)=='1'){
				$req.=",Id_StatutPROD,Avancement,DateCreationPROD,Id_RetourPROD,Id_PROD,CommentairePROD,DateTERA";
			}
			if(substr($_SESSION['DroitSP'],4,1)=='1'){
				$req.=",DateInterventionQ,VacationQ,Id_StatutQUALITE,DateCreationQUALITE,Id_RetourQUALITE,Id_QUALITE,CommentaireQUALITE,DateTERC";
			}
			$req.=") VALUES (";
			
			$outillage="";
			if($_POST['besoinOutillage']==1){
				$outillage=addslashes($_POST['outillageSpecifique']);
			}
			$req.=$IdCree.",".$_SESSION['Id_PersonneSP'].",'".$DateJour."','".addslashes($_POST['poste'])."',".$_POST['DeroNecessaire'].",'".addslashes($_POST['travailRealise'])."','".addslashes($_POST['commentaire'])."'";
			$req.=",'".TrsfDate_($_POST['dateAppro'])."','".TrsfDate_($_POST['dateDA'])."','".$_POST['numDERO']."','".$_POST['numDA']."','".$outillage."','".$_POST['typeDA']."','".$_POST['amLiee']."','".$_POST['otLie']."'";
			if($_POST['statutPrepa']<>""){
				$req.=",'".$_POST['statutPrepa']."','".$DateJour."',";
			}
			else{
				$req.=",'','0001-01-01',";
			}
			
			if(isset($_POST['PasDePS'])){$req.="1, ";}else{$req.="0, ";}
			
			if(isset($_POST['PasDeECMEPROD'])){$req.="1, ";}else{$req.="0, ";}
			if(isset($_POST['PasDeECMEQUALITE'])){$req.="1, ";}else{$req.="0, ";}
			if(isset($_POST['PasDeIngredient'])){$req.="1, ";}else{$req.="0, ";}
			
			if(isset($_POST['ValidationPSCE'])){$req.="1, ";}else{$req.="0, ";}
			if(isset($_POST['ValidationPSIQ'])){$req.="1 ";}else{$req.="0 ";}
			
			if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1'){
				$req.=",";			
				$temps=0;
				if($_POST['tempsObjectif']<>""){$temps=$_POST['tempsObjectif'];}
				$req.= "'".TrsfDate_($_POST['dateIntervention'])."','".$_POST['vacation']."','".$_POST['numIC']."',".$temps."";
			}
			
			if(substr($_SESSION['DroitSP'],1,1)=='1'){
				if($_POST['statutProd']<>"0"){
					$req.= ",'".$_POST['statutProd']."',".$_POST['avancementProd'].",'".$DateJour."',".$_POST['retourProd'].",".$_SESSION['Id_PersonneSP'].",'".addslashes($_POST['commentairePROD'])."','".TrsfDate_($_POST['dateTERA'])."'";
				}
				else{
					$req.= ",'','0001-01-01',0,0,'".addslashes($_POST['commentairePROD'])."','0001-01-01'";
				}
			}
			if(substr($_SESSION['DroitSP'],4,1)=='1'){
				$req.= ",'".TrsfDate_($_POST['dateInterventionQ'])."','".$_POST['vacationQ']."'";
				$Id_IQ=0;
				if($_POST['IQ']<>""){$Id_IQ=$_POST['IQ'];}
				else{$Id_IQ=$_SESSION['Id_PersonneSP'];}
				if($_POST['statutQualite']<>"0"){
					$req.= ",'".$_POST['statutQualite']."','".$DateJour."',".$_POST['retourQualite'].",".$Id_IQ.",'".addslashes($_POST['commentaireQualite'])."','".TrsfDate_($_POST['dateTERC'])."'";
				}
				else{
					$req.= ",'','0001-01-01',0,".$Id_IQ.",'".addslashes($_POST['commentaireQualite'])."','0001-01-01'";
				}
			}
			
			$req.= "); ";
			$resultAjour=mysqli_query($bdd,$req);
			$IdFICree = mysqli_insert_id($bdd);
			
			
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
						$req="SELECT Id FROM sp_olwfi_ecme WHERE ECME='".addslashes($ECME)."' AND Id_FI=".$IdFICree;
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
									AND new_competences_personne_poste_prestation.Id_Prestation=418
									AND new_competences_personne_poste_prestation.Id_Poste=2
									AND new_rh_etatcivil.EmailPro<>''";
							$resultCE=mysqli_query($bdd,$ReqResponsablePostePrestation);
							$nbCE=mysqli_num_rows($resultCE);
							if($nbCE>0){
								while($rowCE=mysqli_fetch_array($resultCE)){
									$Emails.=$rowCE['EmailPro'].",";
								}
							}
							$Objet="Nouvel ECME à mettre à jour, suivi production prestation AEWP";
							$MessageMail="	<html>
											<head><title>Nouvel ECME à mettre à jour, suivi production prestation AEWP</title></head>
											<body>
												Bonjour,
												<br><br>
												Attention, une référence d'ECME non identifiée dans la liste prédéfinie de votre outil de suivi production vient d'être saisie pour la prestation AEWP<br>
												Veuillez vérifier l'exactitude des données saisies et mettre à jour la liste des ECME si cet outil est amené à être utilisé sur votre prestation pour vos futurs travaux
												<br>ECME : ".$ECME."
												<br>
												Bonne journée.<br>
												L'Extranet AAA.
											</body>
										</html>";
							if($Emails<>""){
								if(mail($Emails,$Objet,$MessageMail,$Headers,'-f extranet@aaa-aero.com'))
									{echo "";}
							}
						}
					}
				 }
			}
			
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
					$req="INSERT INTO sp_olwfi_ecme (Id_FI,Id_ECME,Id_TypeECME,ECME,ProdQualite) 
					VALUES (".$IdFICree.",".$Id_ECME.",".$Id_TypeECME.",'".$ReferenceECME."',0)";
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
						$req="SELECT Id FROM sp_olwfi_ecme WHERE ECME='".addslashes($ECME)."' AND Id_FI=".$IdFICree;
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
									AND new_competences_personne_poste_prestation.Id_Prestation=418
									AND new_competences_personne_poste_prestation.Id_Poste=2
									AND new_rh_etatcivil.EmailPro<>''";
							$resultCE=mysqli_query($bdd,$ReqResponsablePostePrestation);
							$nbCE=mysqli_num_rows($resultCE);
							if($nbCE>0){
								while($rowCE=mysqli_fetch_array($resultCE)){
									$Emails.=$rowCE['EmailPro'].",";
								}
							}
							$Objet="Nouvel ECME à mettre à jour, suivi production prestation AEWP";
							$MessageMail="	<html>
											<head><title>Nouvel ECME à mettre à jour, suivi production prestation AEWP</title></head>
											<body>
												Bonjour,
												<br><br>
												Attention, une référence d'ECME non identifiée dans la liste prédéfinie de votre outil de suivi production vient d'être saisie pour la prestation AEWP<br>
												Veuillez vérifier l'exactitude des données saisies et mettre à jour la liste des ECME si cet outil est amené à être utilisé sur votre prestation pour vos futurs travaux
												<br>ECME : ".$ECME."
												<br>
												Bonne journée.<br>
												L'Extranet AAA.
											</body>
										</html>";
							if($Emails<>""){
								if(mail($Emails,$Objet,$MessageMail,$Headers,'-f extranet@aaa-aero.com'))
									{echo "";}
							}
						}
					}
				 }
			}
			
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
					$req="INSERT INTO sp_olwfi_ecme (Id_FI,Id_ECME,Id_TypeECME,ECME,ProdQualite) 
					VALUES (".$IdFICree.",".$Id_ECME.",".$Id_TypeECME.",'".$ReferenceECME."',1)";
					$resultAjour=mysqli_query($bdd,$req);
				 }
			}
			
			//Ajout des ecme client
			$tabPL = explode(";",$_POST['ECMECLIENT']);
			foreach($tabPL as $valeur){
				 if($valeur<>""){
					$tabECME = explode("_",$valeur);

					$req="INSERT INTO sp_olwfi_ecmeclient (Id_FI,NumClient,DateFinEtalonnage) 
					VALUES (".$IdFICree.",'".$tabECME[0]."','".TrsfDate_($tabECME[1])."')";
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
						$req="SELECT Id FROM sp_olwfi_ingredient WHERE Ingredient='".addslashes($Produit)."' AND Id_FI=".$IdFICree;
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
									AND new_competences_personne_poste_prestation.Id_Prestation=418
									AND new_competences_personne_poste_prestation.Id_Poste=2
									AND new_rh_etatcivil.EmailPro<>''";
							$resultCE=mysqli_query($bdd,$ReqResponsablePostePrestation);
							$nbCE=mysqli_num_rows($resultCE);
							if($nbCE>0){
								while($rowCE=mysqli_fetch_array($resultCE)){
									$Emails.=$rowCE['EmailPro'].",";
								}
							}
							$Objet="Nouvel ingrédient à mettre à jour, suivi production prestation AEWP";
							$MessageMail="	<html>
											<head><title>Nouvel ingrédient à mettre à jour, suivi production prestation AEWP</title></head>
											<body>
												Bonjour,
												<br><br>
												Attention, un ingrédient non identifié dans la liste prédéfinie de votre outil de suivi production vient d'être saisi pour la prestation AEWP<br>
												Veuillez vérifier l'exactitude des données saisies et mettre à jour la liste des ingrédients si cet ingrédient est amené à être utilisé sur votre prestation pour vos futurs travaux
												<br>Ingrédient : ".$Produit."
												<br>
												Bonne journée.<br>
												L'Extranet AAA.
											</body>
										</html>";
							if($Emails<>""){
								if(mail($Emails,$Objet,$MessageMail,$Headers,'-f extranet@aaa-aero.com'))
									{echo "";}
							}
						}
					}
				 }
			}
			
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
					$req="INSERT INTO sp_olwfi_ingredient (Id_FI,Id_Ingredient,NumLot,DatePeremption,CoeffHydrometrique,Temperature,Ingredient) VALUES (";
					$req.=$IdFICree.",".$Id_Produit.",'".$NumLot."','".$DatePeremption."','".$Coeff."','".$Temperature."','".$ReferenceProduit."')";
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
						$req="SELECT Id FROM sp_olwfi_aipi WHERE Qualification='".addslashes($Qualif)."' AND Id_FI=".$IdFICree;
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
									AND new_competences_personne_poste_prestation.Id_Prestation=418
									AND new_competences_personne_poste_prestation.Id_Poste=5
									AND new_rh_etatcivil.EmailPro<>''";
							$resultCQP=mysqli_query($bdd,$ReqResponsablePostePrestation);
							$nbCQP=mysqli_num_rows($resultCQP);
							if($nbCQP>0){
								while($rowCQP=mysqli_fetch_array($resultCQP)){
									$Emails.=$rowCQP['EmailPro'].",";
								}
							}
							$Objet="Nouveau PS identifié, suivi production prestation AEWP";
							$MessageMail="	<html>
											<head><title>Nouveau PS identifié, suivi production prestation AEWP</title></head>
											<body>
												Bonjour,
												<br><br>
												Attention, une référence de procédé spécial non identifiée au tableu de compétences vient d'être saisie dans le suivi production de la prestation AEWP<br>
												PS : ".$Qualif."
												<br>
												Bonne journée.<br>
												L'Extranet AAA.
											</body>
										</html>";
							if($Emails<>""){
								if(mail($Emails,$Objet,$MessageMail,$Headers,'-f extranet@aaa-aero.com'))
									{echo "";}
							}
						}
					}
				 }
			}

			//Ajout des PS
			$tabAIPI = explode(";",$_POST['lesAIPI']);
			foreach($tabAIPI as $valeur){
				 if($valeur<>""){
					$tabPS = explode("PS_PS",$valeur);
					$Id_Qualif=0;
					if($tabPS[0]<>""){$Id_Qualif=$tabPS[0];}
					$Qualif=$tabPS[1];
					$req="INSERT INTO sp_olwfi_aipi (Id_FI,Id_Qualification,Qualification) VALUES (".$IdFICree.",".$Id_Qualif.",'".addslashes($Qualif)."')";
					$resultAjour=mysqli_query($bdd,$req);
				 }
			}
			
		if(substr($_SESSION['DroitSP'],0,1)=='1'){
			//Ajout MB21
			if($_POST['typeDA']=="MB21"){
				$tabMB21 = explode(";",$_POST['mb21']);
				foreach($tabMB21 as $valeur){
					 if($valeur<>""){
						$tab2 = explode("_",$valeur);
						$req="INSERT INTO sp_olwfi_mb21 (Id_FI,NumResa,NumOT) VALUES (".$IdFICree.",'".$tab2[0]."','".$tab2[1]."')";
						$resultAjour=mysqli_query($bdd,$req);
					 }
				}
			}
		}
			
		if(substr($_SESSION['DroitSP'],0,1)=='1'){
			/********Ajout de la PREPA**********/
			$listePREPA=array("Enquete","CheckIQ","Appro","DA","DERO","MAP","Partenaire","Acces");
			foreach($listePREPA as $type){
				$DateDebut="0001-01-01";
				$DateFin="0001-01-01";
				$HeureDebut="00:00:00";
				$HeureFin="00:00:00";
				if($_POST['dateDebut'.$type]<>""){$DateDebut=$_POST['dateDebut'.$type];}
				if($_POST['dateFin'.$type]<>""){$DateFin=$_POST['dateFin'.$type];}
				if($_POST['heureDebut'.$type]<>""){$HeureDebut=$_POST['heureDebut'.$type];}
				if($_POST['heureFin'.$type]<>""){$HeureFin=$_POST['heureFin'.$type];}
				$req="INSERT INTO sp_olwfi_prepa(Id_FI,Id_Prestation,TypePrepa,DateDebut,HeureDebut,DateFin,HeureFin,Commentaire) VALUES ";
				$req.="(".$IdFICree.",418,'".$type."','".$DateDebut."','".$HeureDebut."','".$DateFin."','".$HeureFin."','".addslashes($_POST['commentaire'.$type])."')";
				$resultAjout=mysqli_query($bdd,$req);
			}
		}
			
		if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
			if(substr($_SESSION['DroitSP'],1,1)=='1'){
				//Ajout des compagnons
				$tabCompagnon = explode(";",$_POST['travailEffectue']);
				foreach($tabCompagnon as $valeur){
					 if($valeur<>""){
						$tab2 = explode("C_",$valeur);
						$req="INSERT INTO sp_olwfi_travaileffectue (Id_FI,Id_Personne,TempsPasse) VALUES (".$IdFICree.",".$tab2[0].",".$tab2[1].")";
						$resultAjour=mysqli_query($bdd,$req);
					 }
				}
			}
		}
		
		if(substr($_SESSION['DroitSP'],0,1)=='1'){
			echo "<script>GenererFicheSuiveuse(".$IdCree.",".$IdFICree.")</script>";
		}
	}
	echo "<script>location='Liste_Dossier.php';</script>";
	}
}
?>
<form id="formulaire" class="test" method="POST" action="Ajouter_DossierSansACP.php" onSubmit="return VerifChamps(<?php echo substr($_SESSION['DroitSP'],0,1);?>)">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Planifier un nouveau dossier</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
	if($modePoste==1){
		echo "<tr><td colspan='6' align='center' style='color:red;'>Le dossier a été créé et planifié</td></tr>";
	}
	?>
	<tr><td height="4"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td colspan="6" style="font-size:15px;font-weight:bold;color:#4d4a82;" align="center">INFORMATIONS DOSSIER
			<input style="display:none;" type="texte" id="typeSaisie" name="typeSaisie" value="PROD"/>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width="13%" class="Libelle">&nbsp; Programme : </td>
			<td width='15%'>
				<select id="programme" name="programme">
					<option value=""></option>
					<option value="A320">A320</option>
					<option value="A330">A330</option>
					<option value="A350">A350</option>
					<option value="A380">A380</option>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr style="display:none;"><td><input id="idDossier" name="idDossier" value="0"  readonly="readonly"></td></tr>
		<tr>
			<td width="13%" class="Libelle">&nbsp; N° NC : </td>
			<td width="15%">
				<input type="texte" id="numNC" name="numNC" size="15" />
			</td>
			<td width="13%" class="Libelle">&nbsp; N° AM : </td>
			<td width="15%">
				<input type="texte" id="numAM" name="numAM" size="15"/>
			</td>
			<td width="13%" class="Libelle">&nbsp; N° dossier : </td>
			<td width="15%">
				<input type="texte" id="numDossier" name="numDossier" size="15"/>
			</td>
			<td width="13%" class="Libelle">&nbsp; N° point folio : </td>
			<td width="15%">
				<input type="texte" id="numPF" name="numPF" size="15"/>
			</td>
		</tr>
		<?php
			/*
			//Vérification si le dossier n'existe pas déjà dans sp_olwdossier
			$req="SELECT Id,Reference FROM sp_olwdossier WHERE Id_Prestation=418 ";
			$resultBDD=mysqli_query($bdd,$req);
			$nbBDD=mysqli_num_rows($resultBDD);
			if($nbBDD>0){
				$i=0;
				while($rowRef=mysqli_fetch_array($resultBDD)){
					echo "<script>Liste_Reference[".$i."] = new Array('".$rowRef['Id']."','".$rowRef['Reference']."');</script>\n";
					$i+=1;
				}
			}
				
			//Vérification si la NC n'existe pas déjà dans sp_olwdossier
			$req="SELECT Id,ReferenceNC FROM sp_olwdossier WHERE Id_Prestation=418 ";
			$resultBDD=mysqli_query($bdd,$req);
			$nbBDD=mysqli_num_rows($resultBDD);
			if($nbBDD>0){
				$i=0;
				while($rowRef=mysqli_fetch_array($resultBDD)){
					echo "<script>Liste_ReferenceNC[".$i."] = new Array('".$rowRef['Id']."','".$rowRef['ReferenceNC']."');</script>\n";
					$i+=1;
				}
			}
			
			//Vérification si l'AM n'existe pas déjà dans sp_olwdossier
			$req="SELECT Id,ReferenceAM FROM sp_olwdossier WHERE Id_Prestation=418 ";
			$resultBDD=mysqli_query($bdd,$req);
			$nbBDD=mysqli_num_rows($resultBDD);
			if($nbBDD>0){
				$i=0;
				while($rowRef=mysqli_fetch_array($resultBDD)){
					echo "<script>Liste_ReferenceAM[".$i."] = new Array('".$rowRef['Id']."','".$rowRef['ReferenceAM']."');</script>\n";
					$i+=1;
				}
			}
			
			//Vérification si l'AM n'existe pas déjà dans sp_olwdossier
			$req="SELECT Id,ReferencePF, MSN FROM sp_olwdossier WHERE Id_Prestation=418 ";
			$resultBDD=mysqli_query($bdd,$req);
			$nbBDD=mysqli_num_rows($resultBDD);
			if($nbBDD>0){
				$i=0;
				while($rowRef=mysqli_fetch_array($resultBDD)){
					echo "<script>Liste_ReferencePF[".$i."] = new Array('".$rowRef['Id']."','".$rowRef['ReferencePF']."','".$rowRef['MSN']."');</script>\n";
					$i+=1;
				}
			}
			*/
		?>
		<tr><td height="4"></td></tr>
		<?php
		$visible="style='display:none;'";
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
		<tr>
			<td width='13%' class='Libelle'>&nbsp; MSN : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td width='15%'><input onKeyUp='nombre(this)' id='msn' name='msn' value='' size='5'></td>
			<td width='13%' class='Libelle'>&nbsp; Client : </td>
			<td width='15%'>
				<select id="client" name="client">
					<option name='0' value='0'></option>
					<?php
						$req="SELECT Id,Libelle FROM sp_client WHERE Id_Prestation=418 AND Supprime=false ORDER BY Libelle";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowClient=mysqli_fetch_array($result)){
								$selected="";
								echo "<option name='".$rowClient['Id']."' value='".$rowClient['Id']."' ".$selected.">".$rowClient['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
			<td width="13%" class="Libelle">&nbsp; Type du dossier : </td>
			<td width='15%'>
				<select id="typeDossier" name="typeDossier">
					<option value=""></option>
					<option value="OF">OF</option>
					<option value="OT">OT</option>
					<option value="Para">Para</option>
				</select>
			</td>
			<td width="13%" class="Libelle">&nbsp; Imputation : </td>
			<td width='15%'>
				<select id="imputation" name="imputation">
					<option value=""></option>
					<option value="AIRBUS">AIRBUS</option>
					<option value="STELIA">STELIA</option>
				</select>
			</td>
		</tr>
		<?php
		echo "<tr style='display:none;'><td><input id='droit' name='droit' value='".$_SESSION['DroitSP']."'  readonly='readonly'></td></tr>";
		echo "<tr><td height='4'></td></tr>";
		echo "<tr>";
		echo "<td width='13%' class='Libelle' valign='top'>&nbsp; Priorité : ".$etoile."</td>";
		echo "<td width='15%' valign='top'>";
		?>
			<select id="priorite" name="priorite" <?php echo $disabled;?>>
				<option value="1">1</option>
				<option value="3">DA</option>
				<option value="2">2</option>
			</select>
		<?php
		echo "</td>";
		echo "<td width='13%' class='Libelle'>&nbsp; CA/EC : <img src='../../../Images/etoile.png' width='8' height='8' border='0'></td><td width='15%'><input id='caec' name='caec' value='' size='8'></td>";
		echo "<td width='13%' class='Libelle' valign='top'>&nbsp; Compétence(s) : </td>";
		echo "<td width='25%' colspan='3'>";
		echo "<table width='100%' cellpadding='0' cellspacing='0' align='left'>";
		?>
			<tr>
			<td><input type="checkbox" id="Composite" name="Composite" value="Composite" <?php echo $disabled;?> >Composite &nbsp;&nbsp;</td>
			<td><input type="checkbox" id="Metal" name="Metal" value="Metal" <?php echo $disabled;?> >Metal &nbsp;&nbsp;</td>
			<td><input type="checkbox" id="Structure" name="Structure" value="Structure" <?php echo $disabled;?>>Structure &nbsp;&nbsp;</td>
			<td><input type="checkbox" id="Systeme" name="Systeme" value="Systeme" <?php echo $disabled;?>>Systeme &nbsp;&nbsp;</td>
			</tr>
		<?php
		echo "</table>";
		echo "</td>";
		echo "</tr>";
		echo "<tr><td height='4'></td></tr>";
		echo "<tr>";
		?>
			<td width="13%" class="Libelle">&nbsp; Section : <?php echo $etoile2; ?></td>
			<td width='15%'>
				<select id="section" name="section">
					<option value=""></option>
					<?php
						$req="SELECT Id,Libelle FROM sp_olwsection WHERE Id_Prestation=418 AND Supprime=false ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowSection=mysqli_fetch_array($result)){
								$selected="";
								echo "<option value='".$rowSection['Libelle']."' ".$selected.">".$rowSection['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
		<?php
			echo "<td width='13%' class='Libelle'>&nbsp; Titre : ".$etoile."</td><td width='15%' colspan='3'>";
			?>
				<input id="titre" name="titre" value="" style="width: 80%;" <?php echo $read;?>>
			<?php
			echo "</td>";
		echo "</tr>";
		echo "<tr><td height='4'></td></tr>";
		echo "<tr>";
			echo "<td width='13%' class='Libelle'>&nbsp; Zone de travail : ".$etoile."</td><td width='15%'>";
			echo"<select id='zone' name='zone' ".$disabled.">";
				echo"<option name='0' value='0'></option>";
				$req="SELECT Id,Libelle FROM sp_olwzonedetravail  WHERE Id_Prestation=418 AND Supprime=false ORDER BY Libelle;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					while($rowZone=mysqli_fetch_array($result)){
						$selected="";
						echo "<option name='".$rowZone['Id']."' value='".$rowZone['Id']."' ".$selected.">".$rowZone['Libelle']."</option>";
					}
				}
			echo"</select>";
			echo "</td>";
			echo "<td width='13%' class='Libelle'>&nbsp; Localisation : ".$etoile."</td><td width='15%' colspan='3'>";
			?>
				<input id="commentaireZI" name="commentaireZI" value="" style="width: 80%;" <?php echo $read;?>>
			<?php
			echo "</td>";
		echo "</tr>";
		?>
		<tr><td height="4"></td></tr>
		<?php
		$nbTempsDossier=0;
		echo "<tr><td width='13%' valign='top' class='Libelle'>&nbsp; Temps passé : </td><td width='15%' valign='top'><input type='text' size='4' style='border:none' name='tpsDossier' id='tpsDossier' value='".$nbTempsDossier."' readonly='readonly'/></td>";
		?>
		<td width="13%" class="Libelle">&nbsp; Date TERC prévisionnelle : </td>
		<td width="15%">
			<input type="date" style="text-align:center;" id="dateTERCPrevisionnelle" name="dateTERCPrevisionnelle" size="10" value="">
		</td>
		<td width="13%" class="Libelle">&nbsp; Date renvoi de la NC : </td>
		<td width="15%">
			<input type="date" style="text-align:center;" id="dateRenvoiNC" name="dateRenvoiNC" size="10" value="">
		</td>
		</tr>
		<tr><td height="8"></td></tr>
		</table></td></tr>
		<tr><td height="4"></td></tr>
		<tr><td>
		<table width="100%" cellpadding="3" cellspacing="0" align="center" class="GeneralInfo">
			<tr>
				<td colspan="6" style="font-size:15px;font-weight:bold;color:#4d4a82;" align="center">INFORMATIONS INTERVENTION</td>
			</tr>
			<tr>
				<td colspan="2" width="25%" valign="top">
					<table width="100%" cellpadding="0" cellspacing="0" align="center" bgcolor="#599bff">
						<tr><td height="4" colspan="2" align="center" style="font-size:14px;font-weight:bold;" bgcolor="#599bff">PREPA</td></tr>
						<tr>
							<td width="13%" class="Libelle">&nbsp; Poste avion : </td>
							<td width="20%">
								<select id="poste" name="poste" <?php echo $disabled;?>>
									<option name="" value=""></option>
								<?php
									$IdPole=0;
									$poste="";
									$req="SELECT Id,Libelle FROM sp_poste WHERE Id_Prestation=418 AND Supprime=false ORDER BY Libelle;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowPoste=mysqli_fetch_array($result)){
											$selected="";
											echo "<option value='".$rowPoste['Libelle']."' ".$selected.">".$rowPoste['Libelle']."</option>";
										}
									}
								?>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td width="13%" class="Libelle">&nbsp; Dérogation nécessaire : </td>
							<td width="20%">
								<input type="radio" id="DeroNecessaire" name="DeroNecessaire" value="1">Oui &nbsp;&nbsp;
								<input type="radio" id="DeroNecessaire" name="DeroNecessaire" value="0" checked>Non &nbsp;&nbsp;
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td width="13%" class="Libelle">&nbsp; Besoin moyen : <br>&nbsp; spécifique </td>
							<td width="20%">
								<input type="radio" id="besoinOutillage" name="besoinOutillage" onclick="AfficherBesoin(1);" value="1">Oui &nbsp;&nbsp;
								<input type="radio" id="besoinOutillage" name="besoinOutillage" onclick="AfficherBesoin(0);" value="0" checked>Non &nbsp;&nbsp;
							</td>
						</tr>
						<tr class="outillage" style="display:none;"><td height="4" colspan="2"></td></tr>
						<tr class="outillage" style="display:none;">
							<td width="20%" colspan="2">
								&nbsp;<textarea id="outillageSpecifique" name="outillageSpecifique" rows="2" cols="45" style="resize:none;" <?php echo $read;?>></textarea>
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td width="13%" class="Libelle">&nbsp; Statut prépa : </td>
							<td width="20%">
								<select id="statutPrepa" name="statutPrepa" <?php echo $disabled;?>>
									<option value=""></option>
									<option value="A lancer PROD">A lancer PROD</option>
									<option value="A planifier">A planifier</option>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td colspan="2" width="13%" id="LibelleTravailRealise" class="Libelle" valign="center">&nbsp; Travail à réaliser : <?php echo $etoile;?></td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td colspan="2" width="20%">
								&nbsp;<textarea id="travailRealise" name="travailRealise" rows="3" cols="45" style="resize:none;" <?php echo $read;?>><?php if($modeDuplication==1){echo $_POST['travailRealise'];}?></textarea>
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td colspan="2" width="13%" id="LibelleCommentaire" class="Libelle" valign="center">&nbsp; Commentaire : </td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td colspan="2" width="20%">
								&nbsp;<textarea id="commentaire" name="commentaire" rows="3" cols="45" style="resize:none;" <?php echo $read;?>></textarea>
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td colspan="2">
								<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
									<tr height="25">
										<td width="30%" align="center"></td>
										<td width="30%" align="center" class="Libelle">Début</td>
										<td width="30%" align="center" class="Libelle">Fin</td>
									</tr>
									<tr height="35">
										<td width="35%" bgcolor="#dadadc" class="Libelle">&nbsp; Enquête</td>
										<td width="30%" bgcolor="#dadadc" align="center" id="DebutEnquete">
											<?php
												if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
											<a style="text-decoration:none;" href="javascript:Valider('Enquete');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a>
											<?php
											}
											?>
										</td>
										<td width="30%" bgcolor="#dadadc" align="center" id="FinEnquete"></td>
									</tr>
									<tr height="48">
										<td colspan="3" bgcolor="#dadadc" valign="center" align="center">
											<textarea id="commentaireEnquete" name="commentaireEnquete" rows="1" cols="48" style="resize:none;" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" class="Libelle">&nbsp; Check IQ</td>
										<td width="30%" align="center" id="DebutCheckIQ">
											<?php
												if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
											<a style="text-decoration:none;" href="javascript:Valider('CheckIQ');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a>
											<?php
											}
											?>
										</td>
										<td width="30%" id="FinCheckIQ" align="center"></td>
									</tr>
									<tr height="48">
										<td colspan="3" valign="center" align="center">
											<textarea id="commentaireCheckIQ" name="commentaireCheckIQ" rows="1" cols="48" style="resize:none;" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" bgcolor="#dadadc" class="Libelle">&nbsp; Demande appro</td>
										<td width="30%" bgcolor="#dadadc" align="center" id="DebutAppro">
											<?php
												if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
											<a style="text-decoration:none;" href="javascript:Valider('Appro');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a>
											<?php
											}
											?>
										</td>
										<td width="30%" bgcolor="#dadadc" align="center" id="FinAppro"></td>
									</tr>
									<tr height="20">
										<td bgcolor="#dadadc" valign="center" align="left" class="Libelle">
											&nbsp; Date réception prévu : 
										</td>
										<td bgcolor="#dadadc" valign="center" align="left" class="Libelle">
											<input type="<?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "text";}else{echo "date";} ?>" style="text-align:center;" id="dateAppro" name="dateAppro" size="10" value="" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>>
										</td>
										<td colspan="2" bgcolor="#dadadc" valign="center" align="left" class="Libelle">
											&nbsp; Type : 
											<select id="typeDA" name="typeDA" onchange="AfficherMB21();" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>>
												<option name="" value=""></option>
												<option name="Client" value="Client">Client</option>
												<option name="MB21" value="MB21">MB21</option>
											</select>
										</td>
									</tr>
									<tr class="client" height="20" style="display:none;">
										<td bgcolor="#dadadc" valign="center" align="left" class="Libelle">
											&nbsp; AM liée : 
										</td>
										<td colspan="2" bgcolor="#dadadc" valign="center" align="left" class="Libelle">
											<input type="text" style="text-align:center;" id="amLiee" name="amLiee" size="10" value="" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>>
										</td>
									</tr>
									<tr class="client" height="20" style="display:none;">
										<td bgcolor="#dadadc" valign="center" align="left" class="Libelle">
											&nbsp; OT lié :
										</td>
										<td colspan="2" bgcolor="#dadadc" valign="center" align="left" class="Libelle">
											<input type="text" style="text-align:center;" id="otLie" name="otLie" size="10" value="" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>>
										</td>
									</tr>
									<tr class="mb21" style="display:none;">
										<td bgcolor="#dadadc">
											<table cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
												<tr>
													<td bgcolor='#e4e7f0' colspan='2'><i>&nbsp; Réservations :</i></td>
												</tr>
												<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
												<tr>
													<td bgcolor='#e4e7f0'>&nbsp; N° Resa : </td>
												</tr>
												<tr>
													<td bgcolor='#e4e7f0'>&nbsp; 
														<input style="text-align:center;" onKeypress="if(event.keyCode == 13)AjouterMB21()" id="numResa" name="numResa" size="10" type="text" value="">
													</td>
												</tr>
												<tr>
													<td width="40%" bgcolor='#e4e7f0'>&nbsp; N° OT : </td>
												</tr>
												<tr>
													<td width="60%" bgcolor='#e4e7f0'>&nbsp; 
														<input style="text-align:center;" onKeypress="if(event.keyCode == 13)AjouterMB21()" id="numOT" name="numOT" size="10" type="text" value="">
													</td>
												</tr>
												<tr>
													<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
													<?php
														if(substr($_SESSION['DroitSP'],0,1)=='1'){
													?>
														<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterMB21()'>&nbsp;Ajouter&nbsp;</a>
													<?php
														}
													?>
													</td>
												</tr>
											</table>
										</td>
										<td colspan="2" valign="top" bgcolor="#dadadc">
											<table bgcolor="#dadadc" id="tab_MB21" width='100%' cellpadding='0' cellspacing='0'>
												<tr valign="top" ><td class="Libelle">N° résa</td><td class="Libelle">N° OT</td></tr>
											</table>
										</td>
									</tr>
									<tr style="display:none;"><td><input id="mb21" name="mb21" value=""  readonly="readonly"></td></tr>
									<tr height="48">
										<td colspan="3" bgcolor="#dadadc" valign="center" align="center">
											<textarea id="commentaireAppro" name="commentaireAppro" rows="1" cols="48" style="resize:none;" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" class="Libelle">&nbsp; Demande assistance</td>
										<td width="30%" align="center" id="DebutDA">
											<?php
												if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
											<a style="text-decoration:none;" href="javascript:Valider('DA');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a>
											<?php
											}
											?>
										</td>
										<td width="30%" id="FinDA" align="center"></td>
									</tr>
									<tr height="20">
										<td colspan="3" valign="center" align="left" class="Libelle">
											&nbsp; Date prévu : 
											<input type="<?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "text";}else{echo "date";} ?>" style="text-align:center;" id="dateDA" name="dateDA" size="10" value="" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>>
											&nbsp;&nbsp;&nbsp; N° DA : 
											<input type="text" style="text-align:center;" id="numDA" name="numDA" size="15" value="" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>>
										</td>
									</tr>
									<tr height="48">
										<td colspan="3" valign="center" align="center">
											<textarea id="commentaireDA" name="commentaireDA" rows="1" cols="48" style="resize:none;" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" bgcolor="#dadadc" class="Libelle">&nbsp; Création dérogation</td>
										<td width="30%" bgcolor="#dadadc" align="center" id="DebutDERO">
											<?php
												if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
											<a style="text-decoration:none;" href="javascript:Valider('DERO');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a>
											<?php
											}
											?>
										</td>
										<td width="30%" bgcolor="#dadadc" id="FinDERO" align="center"></td>
									</tr>
									<tr height="20">
										<td colspan="3" bgcolor="#dadadc" valign="center" align="left" class="Libelle">
											&nbsp; N° dérogation : 
											<input type="text" style="text-align:center;" id="numDERO" name="numDERO" size="15" value="" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>>
										</td>
									</tr>
									<tr height="48">
										<td colspan="3" bgcolor="#dadadc" valign="center" align="center">
											<textarea id="commentaireDERO" name="commentaireDERO" rows="1" cols="48" style="resize:none;" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" class="Libelle">&nbsp; Attente map</td>
										<td width="30%" align="center" id="DebutMAP">
											<?php
												if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
											<a style="text-decoration:none;" href="javascript:Valider('MAP');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a>
											<?php
											}
											?>
										</td>
										<td width="30%" align="center" id="FinMAP"></td>
									</tr>
									<tr height="48">
										<td colspan="3" valign="center" align="center">
											<textarea id="commentaireMAP" name="commentaireMAP" rows="1" cols="48" style="resize:none;" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" bgcolor="#dadadc" class="Libelle">&nbsp; Transfert partenaire</td>
										<td width="30%" bgcolor="#dadadc" align="center" id="DebutPartenaire">
											<?php
												if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
											<a style="text-decoration:none;" href="javascript:Valider('Partenaire');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a>
											<?php
											}
											?>
										</td>
										<td width="30%" bgcolor="#dadadc" id="FinPartenaire" align="center"></td>
									</tr>
									<tr height="48">
										<td colspan="3" bgcolor="#dadadc" valign="center" align="center">
											<textarea id="commentairePartenaire" name="commentairePartenaire" rows="1" cols="48" style="resize:none;" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" class="Libelle">&nbsp; Attente accès</td>
										<td width="30%" align="center" id="DebutAcces">
											<?php
												if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
											<a style="text-decoration:none;" href="javascript:Valider('Acces');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a>
											<?php
											}
											?>
										</td>
										<td width="30%" align="center" id="FinAcces"></td>
									</tr>
									<tr height="48">
										<td colspan="3" valign="center" align="center">
											<textarea id="commentaireAcces" name="commentaireAcces" rows="1" cols="48" style="resize:none;" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>></textarea>
										</td>
									</tr>
									<tr style="display:none;">
										<td>
											<input type="text" style="text-align:center;" id="dateDebutEnquete" name="dateDebutEnquete" size="15" value="">
											<input type="text" style="text-align:center;" id="dateFinEnquete" name="dateFinEnquete" size="15" value="">
											<input type="text" style="text-align:center;" id="heureDebutEnquete" name="heureDebutEnquete" size="15" value="">
											<input type="text" style="text-align:center;" id="heureFinEnquete" name="heureFinEnquete" size="15" value="">
											
											<input type="text" style="text-align:center;" id="dateDebutCheckIQ" name="dateDebutCheckIQ" size="15" value="">
											<input type="text" style="text-align:center;" id="dateFinCheckIQ" name="dateFinCheckIQ" size="15" value="">
											<input type="text" style="text-align:center;" id="heureDebutCheckIQ" name="heureDebutCheckIQ" size="15" value="">
											<input type="text" style="text-align:center;" id="heureFinCheckIQ" name="heureFinCheckIQ" size="15" value="">
											
											<input type="text" style="text-align:center;" id="dateDebutAppro" name="dateDebutAppro" size="15" value="">
											<input type="text" style="text-align:center;" id="dateFinAppro" name="dateFinAppro" size="15" value="">
											<input type="text" style="text-align:center;" id="heureDebutAppro" name="heureDebutAppro" size="15" value="">
											<input type="text" style="text-align:center;" id="heureFinAppro" name="heureFinAppro" size="15" value="">
											
											<input type="text" style="text-align:center;" id="dateDebutDA" name="dateDebutDA" size="15" value="">
											<input type="text" style="text-align:center;" id="dateFinDA" name="dateFinDA" size="15" value="">
											<input type="text" style="text-align:center;" id="heureDebutDA" name="heureDebutDA" size="15" value="">
											<input type="text" style="text-align:center;" id="heureFinDA" name="heureFinDA" size="15" value="">
											
											<input type="text" style="text-align:center;" id="dateDebutDERO" name="dateDebutDERO" size="15" value="">
											<input type="text" style="text-align:center;" id="dateFinDERO" name="dateFinDERO" size="15" value="">
											<input type="text" style="text-align:center;" id="heureDebutDERO" name="heureDebutDERO" size="15" value="">
											<input type="text" style="text-align:center;" id="heureFinDERO" name="heureFinDERO" size="15" value="">
											
											<input type="text" style="text-align:center;" id="dateDebutMAP" name="dateDebutMAP" size="15" value="">
											<input type="text" style="text-align:center;" id="dateFinMAP" name="dateFinMAP" size="15" value="">
											<input type="text" style="text-align:center;" id="heureDebutMAP" name="heureDebutMAP" size="15" value="">
											<input type="text" style="text-align:center;" id="heureFinMAP" name="heureFinMAP" size="15" value="">
											
											<input type="text" style="text-align:center;" id="dateDebutPartenaire" name="dateDebutPartenaire" size="15" value="">
											<input type="text" style="text-align:center;" id="dateFinPartenaire" name="dateFinPartenaire" size="15" value="">
											<input type="text" style="text-align:center;" id="heureDebutPartenaire" name="heureDebutPartenaire" size="15" value="">
											<input type="text" style="text-align:center;" id="heureFinPartenaire" name="heureFinPartenaire" size="15" value="">
											
											<input type="text" style="text-align:center;" id="dateDebutAcces" name="dateDebutAcces" size="15" value="">
											<input type="text" style="text-align:center;" id="dateFinAcces" name="dateFinAcces" size="15" value="">
											<input type="text" style="text-align:center;" id="heureDebutAcces" name="heureDebutAcces" size="15" value="">
											<input type="text" style="text-align:center;" id="heureFinAcces" name="heureFinAcces" size="15" value="">
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#599bff"></td></tr>
					</table>
				</td>
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
					if(substr($_SESSION['DroitSP'],1,1)=='0'){
						$readCE="readonly='readonly'";
						$disabledCE="disabled='disabled'";
						$etoileCE="";
					}
					if(substr($_SESSION['DroitSP'],4,1)=='0'){
						$readIQ="readonly='readonly'";
						$disabledIQ="disabled='disabled'";
						$etoileIQ="";
					}
				?>
				<td colspan="2" width="33%" valign="top">
					<table width="100%" id="tableProd" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="4" colspan="2" align="center" style="font-size:14px;font-weight:bold;" bgcolor="#dbeef9">PROD</td></tr>
						<tr>
							<td bgcolor="#dbeef9" width="13%" class="Libelle">&nbsp; Date intervention : </td>
							<td bgcolor="#dbeef9" width="20%">
								<input <?php if(substr($_SESSION['DroitSP'],0,1)=='0' && substr($_SESSION['DroitSP'],1,1)=='0'){echo "type='texte'";}else{echo "type='date'";}?> style="text-align:center;" id="dateIntervention" name="dateIntervention" size="15" value="" <?php echo  $readSTCE;?>>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width="13%" class="Libelle">&nbsp; Vacation : </td>
							<td bgcolor="#dbeef9" width="20%" colspan="3">
								<select id="vacation" name="vacation" <?php echo $disabledSTCE;?>>
									<option name="" value=""></option>
									<option name="J" value="J">Jour</option>
									<option name="S" value="S">Soir</option>
									<option name="N" value="N">Nuit</option>
									<option name="VSD Jour" value="VSD Jour">VSD Jour</option>
									<option name="VSD Nuit" value="VSD Nuit">VSD Nuit</option>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width="13%" class="Libelle">&nbsp; N° IC : </td>
							<td bgcolor="#dbeef9" width="20%" colspan="3">
								<input type="text" style="text-align:center;" id="numIC" name="numIC" size="15" value=""  <?php if(substr($_SESSION['DroitSP'],0,1)=='0' && substr($_SESSION['DroitSP'],1,1)=='0'){echo "readonly='readonly'";} ?>>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width="13%" class="Libelle">&nbsp;Temps objectif : </td>
							<td bgcolor="#dbeef9" width="20%" colspan="3">
								<input onKeyUp="nombre(this)" id="tempsObjectif" <?php echo $readSTCE;?> name="tempsObjectif" size="5" type="text" value="">
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width='20%' class="Libelle" valign="top">
								<table cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor='#e4e7f0' colspan='2'><i>&nbsp; Ajouter les opérateurs :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; Personne : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
										<div id='Div_Compagnon' style='height:200px;width:200px;overflow:auto;'>
											<?php
											echo "<table width='100%'>";
											$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
											$req.="FROM new_rh_etatcivil LEFT JOIN sp_acces ON new_rh_etatcivil.Id=sp_acces.Id_Personne WHERE sp_acces.Id_Prestation=418 AND SUBSTR(sp_acces.Droit,3,1)='1' ORDER BY Nom, Prenom;";
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
										<td width="40%" bgcolor='#e4e7f0'>&nbsp;Temps passé : </td>
									</tr>
									<tr>
										<td width="60%" bgcolor='#e4e7f0'>&nbsp; 
											<input onKeyUp="nombre(this)" style="text-align:center;" onKeypress="if(event.keyCode == 13)AjouterTE()" id="tempsPasse" name="tempsPasse" size="5" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
										<?php
											if(substr($_SESSION['DroitSP'],1,1)=='1'){
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
									<tr><td class="Libelle" width="70%">Personne</td><td class="Libelle">Nb. heures</td></tr>
								</table>
								<table id="tab_TravailEffectue" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle" width="70%">TOTAL</td><td class="Libelle">
										<input type="text" size="4" style="background-color:#dbeef9;border:none" name="tpsFI" id="tpsFI" value="0" readonly="readonly"/>
									</td></tr>
								</table>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9">&nbsp; Statut PROD : </td>
							<td width="20%" bgcolor="#dbeef9">
								<select id="statutProd" name="statutProd" onchange="Recharge_StatutProd();" <?php echo $disabledCE;?>>
									<option name="" value=""></option>
									<?php
									$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation=418 AND TypeStatut='P' AND Id<>'REWORK' ORDER BY Id;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowStatut=mysqli_fetch_array($result)){
											echo "<option name='".$rowStatut['Id']."' value='".$rowStatut['Id']."'>".$rowStatut['Id']."</option>";
										}
									}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9">&nbsp; Date TERA : </td>
							<td width="20%" bgcolor="#dbeef9">
								<input <?php if(substr($_SESSION['DroitSP'],0,1)=='0' && substr($_SESSION['DroitSP'],1,1)=='0'){echo "type='texte'";}else{echo "type='date'";}?> style="text-align:center;" id="dateTERA" name="dateTERA" size="15" value="" <?php echo  $readSTCE;?>>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9">&nbsp; Retour PROD : <a style="text-decoration:none;" href="javascript:OuvreDef();"><img id="img" src="../../../Images/aide2.gif" alt="aide" title="aide"></a></td>
							<td width="20%" bgcolor="#dbeef9">
								<div id='retourP'>
								<select id="retourProd" name="retourProd" <?php echo $disabledCE;?>>
									<option name="0" value="0"></option>
									<?php
									$req="SELECT Id, Libelle, Id_Statut, Supprime FROM sp_olwretour WHERE Id_Prestation=418 AND Supprime=0 ORDER BY Libelle;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										$i=0;
										while($rowRetour=mysqli_fetch_array($result)){
											echo "<script>Liste_Retour[".$i."] = new Array(\"".$rowRetour['Id']."\",\"".$rowRetour['Libelle']."\",\"".$rowRetour['Id_Statut']."\",\"".$rowRetour['Supprime']."\");</script>\n";
											$i+=1;
										}
									}
									?>
								</select>
								</div>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9">&nbsp; Avancement PROD :</td>
							<td width="20%" bgcolor="#dbeef9">
								<div id='avancementP'>
								<select id="avancementProd" name="avancementProd" <?php echo $disabledCE;?>>
									<option name="0" value="0" selected></option>
								</select>
								</div>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9" valign="center">&nbsp; Commentaire PROD : </td>
							<td width="20%" bgcolor="#dbeef9">
								<textarea id="commentairePROD" name="commentairePROD" rows="5" cols="40" style="resize:none;" <?php echo $readCE;?>></textarea>
							</td>
						</tr>
					</table>
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="4" colspan="2"></td></tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr><td height="4" colspan="2" align="center" style="font-size:14px;font-weight:bold;" bgcolor="#c7e048">QUALITE</td></tr>
						<tr>
							<td bgcolor="#c7e048" width="13%" class="Libelle">&nbsp; Date intervention : </td>
							<td bgcolor="#c7e048" width="20%">
								<input <?php if(substr($_SESSION['DroitSP'],4,1)=='0'){echo "type='texte'";}else{echo "type='date'";}?> style="text-align:center;" id="dateInterventionQ" name="dateInterventionQ" size="15" value="" <?php echo  $readIQ;?>>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td bgcolor="#c7e048" width="13%" class="Libelle">&nbsp; Vacation : </td>
							<td bgcolor="#c7e048" width="20%" colspan="3">
								<select id="vacationQ" name="vacationQ" <?php echo $disabledIQ;?>>
									<option name="" value=""></option>
									<option name="J" value="J">Jour</option>
									<option name="S" value="S">Soir</option>
									<option name="N" value="N">Nuit</option>
									<option name="VSD Jour" value="VSD Jour">VSD Jour</option>
									<option name="VSD Nuit" value="VSD Nuit">VSD Nuit</option>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td bgcolor="#c7e048" width="13%" class="Libelle">
								&nbsp; Inspecteur qualité :
							</td>
							<td bgcolor="#c7e048" width="20%" colspan="3">
								<select name="IQ">
									<option name="" value=""></option>
									<?php
									$req="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS NomPrenom ";
									$req.="FROM new_rh_etatcivil LEFT JOIN sp_acces ON new_rh_etatcivil.Id=sp_acces.Id_Personne WHERE sp_acces.Id_Prestation=418 AND SUBSTR(sp_acces.Droit,5,1)=1 ORDER BY NomPrenom;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowIQ=mysqli_fetch_array($result)){
											$selected="";
											if(substr($_SESSION['DroitSP'],4,1)=='1' && $_SESSION['Id_PersonneSP'] == $rowIQ['Id']){$selected="selected";}
											echo "<option name='".$rowIQ['Id']."' value='".$rowIQ['Id']."' ".$selected.">".$rowIQ['NomPrenom']."</option>";
										}
									}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#c7e048">&nbsp; Statut QUALITE : </td>
							<td width="20%" bgcolor="#c7e048">
								<div id="statutsQualite">
									<select id="statutQualite" name="statutQualite" onchange="Recharge_StatutQualite();" <?php echo $disabledIQ;?>>
										<option name="" value=""></option>
										<?php
										$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation=418 AND TypeStatut='Q' ORDER BY Id;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											$i=0;
											while($rowStatut=mysqli_fetch_array($result)){
												echo "<script>Liste_Statut[".$i."] = new Array('".$rowStatut['Id']."');</script>\n";
												$i+=1;
											}
										}
										?>
									</select>
								</div>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#c7e048">&nbsp; Date TERC : </td>
							<td width="20%" bgcolor="#c7e048">
								<input <?php if(substr($_SESSION['DroitSP'],4,1)=='0'){echo "type='texte'";}else{echo "type='date'";}?> style="text-align:center;" id="dateTERC" name="dateTERC" size="15" value="" <?php echo  $readIQ;?>>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td width="26%" class="Libelle" bgcolor="#c7e048">&nbsp; Retour QUALITE : <a style="text-decoration:none;" href="javascript:OuvreDef();"><img id="img" src="../../../Images/aide2.gif" alt="aide" title="aide"></a></td>
							<td width="20%" bgcolor="#c7e048">
								<div id='retourQ'>
								<select id="retourQualite" name="retourQualite" <?php echo $disabledIQ;?>>
									<option name="0" value="0"></option>
								</select>
								</div>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#c7e048" valign="center">&nbsp; Commentaire QUALITE : </td>
							<td width="20%" bgcolor="#c7e048">
								<textarea id="commentaireQualite" name="commentaireQualite" rows="5" cols="40" style="resize:none;" <?php echo $readIQ;?>></textarea>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
					</table>
				</td>
				<td colspan="2" width="33%" valign="top">
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
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
												$req="SELECT Id, Libelle FROM sp_olwtypeecme WHERE Supprime=false AND Id_Prestation=418 ORDER BY Libelle ";
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
													$req="SELECT Id, Libelle,Id_Type FROM sp_olwecme WHERE Supprime=false AND Id_Prestation=418 ORDER BY Libelle ";
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
											<input style="text-align:left;" id="PasDeECMEPROD" <?php echo $modifiableCE; ?> name="PasDeECMEPROD" type="checkbox" value="PasDeECMEPROD"> Pas de ECME requis
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='65%' valign='top'>
								<table id="tab_ECMEPROD" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle">Type</td><td class="Libelle">Référence</td></tr>
								</table>
							</td>
						</tr>
						<tr><td height="8" colspan="2" bgcolor="#dbeef9"></td></tr>
					</table>
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
												$req="SELECT Id, Libelle FROM sp_olwtypeecme WHERE Supprime=false AND Id_Prestation=418 ORDER BY Libelle ";
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
													$req="SELECT Id, Libelle,Id_Type FROM sp_olwecme WHERE Supprime=false AND Id_Prestation=418 ORDER BY Libelle ";
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
											<input style="text-align:left;" <?php echo $modificationIQ; ?> id="PasDeECMEQUALITE" name="PasDeECMEQUALITE" type="checkbox" value="PasDeECMEQUALITE"> Pas de ECME requis
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='65%' valign='top'>
								<table id="tab_ECMEQUALITE" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle">Type</td><td class="Libelle">Référence</td></tr>
								</table>
							</td>
						</tr>
						<tr><td height="8" colspan="2" bgcolor="#dbeef9"></td></tr>
					</table>
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
								</table>
							</td>
						</tr>
						<tr><td height="8" colspan="2" bgcolor="#dbeef9"></td></tr>
					</table><br>
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
												$req="SELECT Id, Libelle FROM sp_olwingredient WHERE Supprime=false AND Id_Prestation=418 ORDER BY Libelle;";
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
											<input style="text-align:left;" <?php echo $modifiableCE; ?> id="PasDeIngredient" name="PasDeIngredient" type="checkbox" value="PasDeIngredient"> Pas d'ingredient requis
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='20%' valign='top'>
								<table id="tab_Produit" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle">Ingrédient</td><td class="Libelle">N° lot</td><td class="Libelle">Date<br>péremption</td><td class="Libelle">Coeff.<br>hygrométrique</td><td class="Libelle">Température</td></tr>
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
												$req.="WHERE new_competences_prestation_qualification.Id_Prestation IN (418,950,418) ";
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
											<input style="text-align:left;" id="PasDePS" name="PasDePS" type="checkbox" value="PasDePS" onchange="ValidationAutoPS()"> Pas de procédé spécial requis
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='65%' valign='top'>
								<table id="tab_AIPI" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle" align="center">Procédés spéciaux appelés</td></tr>
								</table>
							</td>
						</tr>
						<tr>
							<td bgcolor='#dbeef9'>&nbsp; 
								
							</td>
							<td bgcolor='#dbeef9'>&nbsp; 
								<table>
									<tr>
										<td>
											<input <?php echo $modifiableCE; ?> style="text-align:left;" id="ValidationPSCE" name="ValidationPSCE" type="checkbox" value="ValidationPSCE" > Validation Chef d'équipe
										</td>
										<td>
											<input <?php echo $modificationIQ; ?> style="text-align:left;" id="ValidationPSIQ" name="ValidationPSIQ" type="checkbox" value="ValidationPSIQ" > Validation Qualité
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr><td height="8" colspan="2" bgcolor="#dbeef9"></td></tr>
					</table>
				</td>
			</tr>
			<tr><td height="9" colspan="6"></td></tr>
			<tr style="display:none;"><td><input id='travailEffectue' name='travailEffectue' value=''  readonly='readonly'></td></tr>
			<tr style="display:none;"><td><input id="ECMEPROD" name="ECMEPROD" value="" readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="ECMEQUALITE" name="ECMEQUALITE" value="" readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="ECMECLIENT" name="ECMECLIENT" value="" readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id='lesAIPI' name='lesAIPI' value='' readonly='readonly'></td></tr>
			<tr style="display:none;"><td><input id="Produit" name="Produit" value="" readonly="readonly"></td></tr>
			<tr style="display:none;"><td height="4" colspan="6" bgcolor="#dbeef9"></td></tr>
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
		<div id="EnregistrerDupliquer">
		</div>	
		<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="AfficherSubmit()"> &nbsp;&nbsp;&nbsp;
		</td>
		<?php
		}
		?>
	</tr>
	<tr><td colspan="6">&nbsp;&nbsp;&nbsp;&nbsp;<img src='../../../Images/etoile.png' width='8' height='8' border='0'> Informations obligatoires à remplir</td></tr>
	<tr><td height="4"></td></tr>
</table>
</form>
<?php
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
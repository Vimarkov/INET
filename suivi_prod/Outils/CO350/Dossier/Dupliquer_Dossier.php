<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="Dossier3.js"></script>
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>	
	<script>
		function FermerEtRecharger(){
			window.opener.location = "Liste_Dossier.php";
			window.close();
		}
		function FicheSuiveuse2(Id,Id_FI)
		{window.open("FicheSuiveuse.php?Id_Dossier="+Id+"&Id_FI="+Id_FI,"PageFS","status=no,menubar=no,scrollbars=1,width=90,height=40");}		
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
		$tai=0;
		if($_POST['tai']<>""){$tai=$_POST['tai'];}
		
		//MISE A JOUR DU DOSSIER
		$req="UPDATE sp_olwdossier SET ";
		$req.="MSN=".$_POST['msn'].", ";
		$req.="TAI_RestantACP=".$tai.", ";
		$req.="Programme='".addslashes($_POST['programme'])."', ";
		$req.="Reference='".addslashes($_POST['numDossier'])."', ";
		$req.="CodeUsine='".addslashes($_POST['codeUsine'])."', ";
		$req.="Id_Client=".$_POST['client'].", ";
		$req.="TypeACP='".addslashes($_POST['typeDossier'])."', ";
		$req.="Priorite='".$_POST['priorite']."', ";
		$req.="CaecACP='".$_POST['caec']."', ";
		if(isset($_POST['Ajustage'])){$req.="Ajusteur=1, ";}else{$req.="Ajusteur=0, ";}
		if(isset($_POST['Elec'])){$req.="Elec=1, ";}else{$req.="Elec=0, ";}
		if(isset($_POST['Meca'])){$req.="Meca=1, ";}else{$req.="Meca=0, ";}
		$req.="Titre='".addslashes($_POST['titre'])."', ";
		$req.="Id_StatutPREPA='".addslashes($_POST['statutPrepa'])."', ";
		$req.="Id_Poste=".$_POST['poste'].", ";
		if(substr($_SESSION['DroitSP'],4,1)=='1'){
			if($_POST['statutQualite']<>"0"){
				$req.="Id_Statut='".$_POST['statutQualite']."', ";
				$req.="Id_Retour=".$_POST['retourQualite'].", ";
			}
			else{
				$req.="Id_Statut='".$_POST['statutProd']."', ";
				$req.="Id_Retour=".$_POST['retourProd'].", ";
			}
		}
		elseif(substr($_SESSION['DroitSP'],1,1)=='1'){
			$req.="Id_Statut='".$_POST['statutProd']."', ";
			$req.="Id_Retour=".$_POST['retourProd'].", ";
		}
		$req.="DatePrevisionnelleIntervention='".TrsfDate_($_POST['datePrevisionnelleIntervention'])."' ";
		$req.="WHERE Id=".$_POST['idDossier'];
		$resultUpdate=mysqli_query($bdd,$req);

		//Ajout de la fiche d'intervention
		$req="INSERT INTO sp_olwficheintervention (";
			$req.="Id_Dossier,NumFI,TravailRealise,Id_Createur,DateCreation,PasDePS,PasDeECMEPROD,PasDeECMEQUALITE,PasDeIngredient,ValidationPSCE,ValidationPSIQ,
				DateIntervention,Vacation,TempsProd,Id_StatutPROD,DateCreationPROD,Id_RetourPROD,Id_PROD,CommentairePROD,DateTERA,
				DateInterventionQ,DateCreationQUALITE,Id_QUALITE,CommentaireQUALITE,DateTERC ";
				
				if(substr($_SESSION['DroitSP'],4,1)=='1'){
					$req.=",VacationQ,Id_StatutQUALITE,Id_RetourQUALITE";
				}
			
			$req.=")
				VALUES (".$_POST['idDossier'].",'".$_POST['numIC']."','".addslashes($_POST['travailRealise'])."',".$_SESSION['Id_PersonneSP'].",'".$DateJour."',";
				
		if(isset($_POST['PasDePS'])){$req.="1, ";}else{$req.="0, ";}
		if(isset($_POST['PasDeECMEPROD'])){$req.="1, ";}else{$req.="0, ";}
		if(isset($_POST['PasDeECMEQUALITE'])){$req.="1, ";}else{$req.="0, ";}
		if(isset($_POST['PasDeIngredient'])){$req.="1, ";}else{$req.="0, ";}
		if(isset($_POST['ValidationPSCE'])){$req.="1, ";}else{$req.="0, ";}
		if(isset($_POST['ValidationPSIQ'])){$req.="1, ";}else{$req.="0, ";}
		
		$tempsProd=0;
		if($_POST['tempsProd']<>""){$tempsProd=$_POST['tempsProd'];}
			
		$req.= "'".TrsfDate_($_POST['dateIntervention'])."','".$_POST['vacation']."',".$tempsProd.",'".$_POST['statutProd']."','".$DateJour."',".$_POST['retourProd'].",
				".$_SESSION['Id_PersonneSP'].",'".addslashes($_POST['commentairePROD'])."','".TrsfDate_($_POST['dateTERA'])."',
				'".TrsfDate_($_POST['dateInterventionQ'])."','".$DateJour."',".$_SESSION['Id_PersonneSP'].",'".addslashes($_POST['commentaireQualite'])."','".TrsfDate_($_POST['dateTERC'])."' ";
			if(substr($_SESSION['DroitSP'],4,1)=='1'){
				$req.= ",'".$_POST['vacationQ']."','".$_POST['statutQualite']."',".$_POST['retourQualite']." ";
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
									AND new_competences_personne_poste_prestation.Id_Prestation = 1792
									AND new_competences_personne_poste_prestation.Id_Poste=2
									AND new_rh_etatcivil.EmailPro<>''";
							$resultCE=mysqli_query($bdd,$ReqResponsablePostePrestation);
							$nbCE=mysqli_num_rows($resultCE);
							if($nbCE>0){
								while($rowCE=mysqli_fetch_array($resultCE)){
									$Emails.=$rowCE['EmailPro'].",";
								}
							}
							$Objet="Nouvel ECME à mettre à jour, suivi production prestation AISLP";
							$MessageMail="	<html>
											<head><title>Nouvel ECME à mettre à jour, suivi production prestation AISLP</title></head>
											<body>
												Bonjour,
												<br><br>
												Attention, une référence d'ECME non identifiée dans la liste prédéfinie de votre outil de suivi production vient d'être saisie pour la prestation AISLP<br>
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
									AND new_competences_personne_poste_prestation.Id_Prestation = 1792
									AND new_competences_personne_poste_prestation.Id_Poste=2
									AND new_rh_etatcivil.EmailPro<>''";
							$resultCE=mysqli_query($bdd,$ReqResponsablePostePrestation);
							$nbCE=mysqli_num_rows($resultCE);
							if($nbCE>0){
								while($rowCE=mysqli_fetch_array($resultCE)){
									$Emails.=$rowCE['EmailPro'].",";
								}
							}
							$Objet="Nouvel ECME à mettre à jour, suivi production prestation AISLP";
							$MessageMail="	<html>
											<head><title>Nouvel ECME à mettre à jour, suivi production prestation AISLP</title></head>
											<body>
												Bonjour,
												<br><br>
												Attention, une référence d'ECME non identifiée dans la liste prédéfinie de votre outil de suivi production vient d'être saisie pour la prestation AISLP<br>
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
					$Id_ECME=0;
					$Id_TypeECME=0;
					$ReferenceECME="";
					$dateEtalonnage="";
					$tabECME = explode("ECME_ECME",$valeur);
					if($tabECME[0]==0){
						$tabECME2 = explode("_",$tabECME[1]);
						$ReferenceECME=$tabECME2[0];
						$Id_TypeECME=$tabECME2[1];
						$dateEtalonnage=$tabECME2[2];
					}
					else{
						$tabECME2 = explode("_",$tabECME[0]);
						$Id_ECME=$tabECME2[0];
						$Id_TypeECME=$tabECME2[1];
						$dateEtalonnage=$tabECME2[2];
					} 
					$req="INSERT INTO sp_olwfi_ecmeclient (Id_FI,Id_ECME,Id_TypeECME,NumClient,DateFinEtalonnage) 
					VALUES (".$IdFICree.",".$Id_ECME.",".$Id_TypeECME.",'".$ReferenceECME."','".TrsfDate_($dateEtalonnage)."')";
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
									AND new_competences_personne_poste_prestation.Id_Prestation = 1792
									AND new_competences_personne_poste_prestation.Id_Poste=2
									AND new_rh_etatcivil.EmailPro<>''";
							$resultCE=mysqli_query($bdd,$ReqResponsablePostePrestation);
							$nbCE=mysqli_num_rows($resultCE);
							if($nbCE>0){
								while($rowCE=mysqli_fetch_array($resultCE)){
									$Emails.=$rowCE['EmailPro'].",";
								}
							}
							$Objet="Nouvel ingrédient à mettre à jour, suivi production prestation AISLP";
							$MessageMail="	<html>
											<head><title>Nouvel ingrédient à mettre à jour, suivi production prestation AISLP</title></head>
											<body>
												Bonjour,
												<br><br>
												Attention, un ingrédient non identifié dans la liste prédéfinie de votre outil de suivi production vient d'être saisi pour la prestation AISLP<br>
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
									AND new_competences_personne_poste_prestation.Id_Prestation = 1792
									AND new_competences_personne_poste_prestation.Id_Poste=5
									AND new_rh_etatcivil.EmailPro<>''";
							$resultCQP=mysqli_query($bdd,$ReqResponsablePostePrestation);
							$nbCQP=mysqli_num_rows($resultCQP);
							if($nbCQP>0){
								while($rowCQP=mysqli_fetch_array($resultCQP)){
									$Emails.=$rowCQP['EmailPro'].",";
								}
							}
							$Objet="Nouveau PS identifié, suivi production prestation AISLP";
							$MessageMail="	<html>
											<head><title>Nouveau PS identifié, suivi production prestation AISLP</title></head>
											<body>
												Bonjour,
												<br><br>
												Attention, une référence de procédé spécial non identifiée au tableu de compétences vient d'être saisie dans le suivi production de la prestation AISLP<br>
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
					
			
		if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
			if(substr($_SESSION['DroitSP'],1,1)=='1'){
				//Ajout des compagnons
				$tabCompagnon = explode(";",$_POST['travailEffectue']);
				foreach($tabCompagnon as $valeur){
					 if($valeur<>""){
						$tab2 = explode("C_",$valeur);
						$req="INSERT INTO sp_olwfi_travaileffectue (Id_FI,Id_Personne) VALUES (".$IdFICree.",".$tab2[0].")";
						$resultAjour=mysqli_query($bdd,$req);
					 }
				}
			}
		}
		
		if(substr($_SESSION['DroitSP'],0,1)=='1'){
			echo "<script>FicheSuiveuse2(".$_POST['idDossier'].",".$IdFICree.")</script>";
		}
		
		echo "<script>FermerEtRecharger();</script>";
	}
}
if($_GET){
	$FI=$_GET['Id'];
	$IdPersonne=$_GET['Id_Personne'];
		//INFORMATIONS DOSSIER
		$req="SELECT sp_olwficheintervention.Id,sp_olwficheintervention.Id_Dossier,sp_olwdossier.MSN,sp_olwdossier.Id_Client,
				sp_olwdossier.Reference,sp_olwdossier.DateDossier,sp_olwdossier.HeureDossier,sp_olwdossier.Titre,
				sp_olwdossier.TypeACP,sp_olwdossier.CaecACP,sp_olwdossier.Priorite,sp_olwdossier.DateCreation,CodeUsine,
				sp_olwdossier.DatePrevisionnelleIntervention,sp_olwdossier.Ajusteur,sp_olwdossier.Elec,
				sp_olwdossier.Meca,sp_olwdossier.Id_ZoneDeTravail,sp_olwdossier.Id_Statut,sp_olwdossier.TAI_RestantACP,
				sp_olwdossier.Id_Retour,sp_olwdossier.Programme,sp_olwdossier.Id_StatutPREPA,sp_olwdossier.Id_Poste,sp_olwficheintervention.NumFI,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwdossier.Id_Personne) AS CreateurDossier,
				sp_olwficheintervention.PasDePS,sp_olwficheintervention.ValidationPSCE,sp_olwficheintervention.ValidationPSIQ,
				sp_olwficheintervention.PasDeECMEPROD,sp_olwficheintervention.PasDeECMEQUALITE,sp_olwficheintervention.PasDeIngredient,";
		//PROD
		$req.="sp_olwficheintervention.DateIntervention,sp_olwficheintervention.Vacation,sp_olwficheintervention.Id_StatutPROD,sp_olwficheintervention.DateTERA,";
		$req.="sp_olwficheintervention.Id_RetourPROD,sp_olwficheintervention.CommentairePROD,sp_olwficheintervention.TravailRealise,";
			//QUALITE
		$req.="sp_olwficheintervention.DateInterventionQ,sp_olwficheintervention.VacationQ,sp_olwficheintervention.Id_QUALITE,sp_olwficheintervention.Id_StatutQUALITE,
			sp_olwficheintervention.DateTERC,sp_olwficheintervention.Id_RetourQUALITE,sp_olwficheintervention.CommentaireQUALITE ";
		
		$req.="FROM sp_olwficheintervention 
			LEFT JOIN sp_olwdossier 
			ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id 
			WHERE sp_olwficheintervention.Id=".$FI;
		$result=mysqli_query($bdd,$req);
		$row=mysqli_fetch_array($result);
}
?>
<form id="formulaire" class="test" method="POST" action="Dupliquer_Dossier.php" onSubmit="return VerifChamps();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Dupliquer une intervention
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
			<tr>
				<td colspan="8" style="font-size:15px;font-weight:bold;color:#4d4a82;" align="center">INFORMATIONS DOSSIER</td>
			</tr>
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
			<tr><td height="4"></td></tr>
			<tr>
				<td width="13%" class="Libelle">&nbsp; Programme : </td>
				<td width='15%'>
					<select id="programme" name="programme">
						<option value=""></option>
						<option value="A320" <?php if($row['Programme']=="A320"){echo "selected";}?>>A320</option>
						<option value="A330" <?php if($row['Programme']=="A330"){echo "selected";}?>>A330</option>
						<option value="A350" <?php if($row['Programme']=="A350"){echo "selected";}?>>A350</option>
						<option value="A380" <?php if($row['Programme']=="A380"){echo "selected";}?>>A380</option>
					</select>
				</td>
				<td width="13%" class="Libelle">&nbsp; MSN : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
				<td width="15%">
					<input id='msn' name='msn' onKeyUp="nombre(this)" value='<?php echo $row['MSN'];?>'></td>
				</td>
				<td width="13%" class="Libelle">&nbsp; N° dossier :  <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
				<td width="15%">
					<input type="texte" id="numDossier" name="numDossier" size="15" value="<?php echo $row['Reference'];?>"/>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				
				<td width='13%' class='Libelle'>&nbsp; Client : </td>
				<td width='15%'>
					<select id="client" name="client">
						<option name='0' value='0'></option>
						<?php
							$req="SELECT Id,Libelle,Supprime FROM sp_client WHERE Id_Prestation = 1792 ORDER BY Libelle";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowClient=mysqli_fetch_array($result)){
									$selected="";
									if($rowClient['Id']==$row['Id_Client']){$selected="selected";}
									if($rowClient['Id']==$row['Id_Client'] ||$rowClient['Supprime']==false){
										echo "<option name='".$rowClient['Id']."' value='".$rowClient['Id']."' ".$selected.">".$rowClient['Libelle']."</option>";
									}
								}
							}
						?>
					</select>
				</td>
				<td width="13%" class="Libelle">&nbsp; Type de dossier : </td>
				<td width='15%'>
					<select id="typeDossier" name="typeDossier">
						<option value=""></option>
						<option value="FormA" <?php if($row['TypeACP']=="FormA"){ echo "selected";}?>>FormA</option>
						<option value="NC" <?php if($row['TypeACP']=="NC"){ echo "selected";}?>>NC</option>
						<option value="OW" <?php if($row['TypeACP']=="OW"){ echo "selected";}?>>OW</option>
						<option value="PARA" <?php if($row['TypeACP']=="PARA"){ echo "selected";}?>>PARA</option>
						<option value="QLB" <?php if($row['TypeACP']=="QLB"){ echo "selected";}?>>QLB</option>
						<option value="S01" <?php if($row['TypeACP']=="S01"){ echo "selected";}?>>S01</option>
						<option value="S02" <?php if($row['TypeACP']=="S02"){ echo "selected";}?>>S02</option>
						<option value="S03" <?php if($row['TypeACP']=="S03"){ echo "selected";}?>>S03</option>
						<option value="S99" <?php if($row['TypeACP']=="S99"){ echo "selected";}?>>S99</option>
						<option value="TLB" <?php if($row['TypeACP']=="TLB"){ echo "selected";}?>>TLB</option>
					</select>
				</td>
				<td width='13%' class='Libelle'>&nbsp; CA/EC : </td>
				<td width='15%'>
					<input id='caec' name='caec' value='<?php echo $row['CaecACP'];?>' size='8'>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr style="display:none;"><td><input id="idDossier" name="idDossier" value="<?php echo $row['Id_Dossier']; ?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="idFI" name="idFI" value="<?php echo $FI; ?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="dernierIC" name="dernierIC" value="<?php echo $DerniereIC; ?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="droit" name="droit" value="<?php echo $_SESSION['DroitSP'];?>"  readonly="readonly"></td></tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width="13%" class="Libelle">&nbsp; Créateur : </td>
				<td width="15%"><?php echo $row['CreateurDossier']; ?></td>
				<td width="13%" class="Libelle">&nbsp; Date de création : </td>
				<td width="15%"><?php echo AfficheDateJJ_MM_AAAA($row['DateCreation']); ?></td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width='13%' class='Libelle' valign='top'>&nbsp; Type(s) de travail : </td>
				<td colspan='3'>
					<table width='100%' cellpadding='0' cellspacing='0' align='left'>
						<tr>
							<td><input type="checkbox" id="Ajustage" name="Ajustage" value="Ajustage" <?php if($row['Ajusteur']==1){ echo "checked";}?> <?php echo $disabled;?> >Ajustage &nbsp;&nbsp;</td>
							<td><input type="checkbox" id="Elec" name="Elec" value="Elec" <?php if($row['Elec']==1){ echo "checked";}?> <?php echo $disabled;?> >Elec &nbsp;&nbsp;</td>
							<td><input type="checkbox" id="Meca" name="Meca" value="Méca" <?php if($row['Meca']==1){ echo "checked";}?> <?php echo $disabled;?> >Méca &nbsp;&nbsp;</td>
						</tr>
					</table>
				</td>
				<td width="13%" class="Libelle">&nbsp; Code usine : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
				<td width='15%'>
					<select id="codeUsine" name="codeUsine">
						<option value=""></option>
						<option value="DA" <?php if($row['CodeUsine']=="DA"){ echo "selected";}?>>DA</option>
						<option value="DZ" <?php if($row['CodeUsine']=="DZ"){ echo "selected";}?>>DZ</option>
						<option value="TO" <?php if($row['CodeUsine']=="TO"){ echo "selected";}?>>TO</option>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width='13%' class='Libelle'>&nbsp; Titre : <?php echo $etoile; ?></td>
				<td colspan='3'>
					<input id="titre" name="titre" value="<?php echo stripslashes($row['Titre']);?>" style="width: 80%;" <?php echo $read;?>>
				</td>
				<td width='13%' class='Libelle'>&nbsp; Localisation : </td>
				<td width='15%'>
					<select id="localisation" name="localisation">
						<option name='0' value='0'></option>
						<?php
							$req="SELECT Id,Libelle, Supprime FROM sp_localisation WHERE Id_Prestation = 1792 ORDER BY Libelle";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowL=mysqli_fetch_array($result)){
									$selected="";
									if($rowL['Id']==$row['Id_ZoneDeTravail']){$selected="selected";}
									if($rowL['Id']==$row['Id_ZoneDeTravail'] ||$rowL['Id']==false){
										echo "<option name='".$rowL['Id']."' value='".$rowL['Id']."' ".$selected.">".$rowL['Libelle']."</option>";
									}
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width="13%" class="Libelle">&nbsp; Statut Prépa : </td>
				<td width="15%">
					<select id="statutPrepa" name="statutPrepa" >
						<option name="" value=""></option>
						<?php
						$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation = 1792 AND TypeStatut='R' ORDER BY Id;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowStatut=mysqli_fetch_array($result)){
								$selected="";
								if($rowStatut['Id']==$row['Id_StatutPREPA']){$selected="selected";}
								echo "<option name='".$rowStatut['Id']."' value='".$rowStatut['Id']."' ".$selected." >".$rowStatut['Id']."</option>";
							}
						}
						?>
					</select>
				</td>
				<td width='13%' class='Libelle'>&nbsp; Poste : </td>
				<td width='15%'>
					<select id="poste" name="poste">
						<option name='0' value='0'></option>
						<?php
							$req="SELECT Id,Libelle, Supprime FROM sp_poste WHERE Id_Prestation = 1792 ORDER BY Libelle";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowL=mysqli_fetch_array($result)){
									$selected="";
									if($rowL['Id']==$row['Id_Poste']){$selected="selected";}
									if($rowL['Id']==$row['Id_Poste'] ||$rowL['Supprime']==false){
										echo "<option value='".$rowL['Id']."' ".$selected.">".$rowL['Libelle']."</option>";
									}
								}
							}
						?>
					</select>
				</td>
				<td width='13%' class='Libelle' valign='top'>&nbsp; Priorité : <?php echo $etoile;?></td>
				<td width='15%' valign='top'>
					<select id="priorite" name="priorite" <?php echo $disabled;?>>
						<option value="1" <?php if($row['Priorite']==1){ echo "selected";}?>>1</option>
						<option value="2" <?php if($row['Priorite']==2){ echo "selected";}?>>2</option>
						<option value="3" <?php if($row['Priorite']==3){ echo "selected";}?>>3</option>
						<option value="4" <?php if($row['Priorite']==4){ echo "selected";}?>>4</option>
						<option value="5" <?php if($row['Priorite']==5){ echo "selected";}?>>5</option>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width="13%" class="Libelle">&nbsp; TAI : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
				<td width="15%">
					<input id='tai' name='tai' onKeyUp="nombre(this)" value='<?php echo $row['TAI_RestantACP'];?>'></td>
				</td>
				<td width="13%" class="Libelle">&nbsp; Date prévisionnelle intervention : </td>
				<td width="15%">
					<input type="date" style="text-align:center;" id="datePrevisionnelleIntervention" name="datePrevisionnelleIntervention" size="10" value="<?php echo AfficheDateFR($row['DatePrevisionnelleIntervention']);?>">
				</td>
			</tr>
		<tr><td height="8"></td></tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table width="100%" cellpadding="3" cellspacing="0" align="center" class="GeneralInfo">
			<tr>
				<td colspan="9" style="font-size:15px;font-weight:bold;color:#4d4a82;" align="center">HISTORIQUE DES INTERVENTIONS</td>
			</tr>
			<tr>
				<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;">N° IC</td>
				<td class="EnTeteTableauCompetences" width="12%" style="text-align:center;">Date intervention<br>PROD</td>
				<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;">Vacation PROD</td>
				<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;">Statut PROD</td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;">Retour PROD</td>
				<td class="EnTeteTableauCompetences" width="12%" style="text-align:center;">Date intervention<br>QUALITE</td>
				<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;">Vacation QUALITE</td>
				<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;">Statut QUALITE</td>
				<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;">Retour QUALITE</td>
			</tr>
			<?php
				$reqHistorique = "SELECT sp_olwficheintervention.Id,sp_olwficheintervention.DateIntervention, sp_olwficheintervention.Id_StatutPROD,";
				$reqHistorique .= "sp_olwficheintervention.DateInterventionQ,Id_StatutQUALITE,  ";
				$reqHistorique .= "sp_olwficheintervention.Vacation, sp_olwficheintervention.VacationQ,sp_olwficheintervention.NumFI, ";
				$reqHistorique .= "(SELECT sp_olwretour.Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourPROD) AS RetourProd, ";
				$reqHistorique .= "(SELECT sp_olwretour.Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourQUALITE) AS RetourQualite ";
				$reqHistorique .= "FROM sp_olwficheintervention ";
				$reqHistorique .= "WHERE sp_olwficheintervention.Id_Dossier=".$row['Id_Dossier'];
				$resultH=mysqli_query($bdd,$reqHistorique);
				$nbH=mysqli_num_rows($resultH);
				if ($nbH>0){
					$nb=1;
					while($rowH=mysqli_fetch_array($resultH)){
						$bordure="";
						if($nb<$nbH){
							$bordure="border-bottom:1px dotted #0077aa;";
						}
						?>
							<tr>
							<td width="6%" style="text-align:center;<?php echo $bordure;?>"><?php echo $rowH['NumFI'];?></td>
							<td width="12%" style="text-align:center;<?php echo $bordure;?>"><?php echo AfficheDateFR($rowH['DateIntervention']);?></td>
							<td width="10%" style="text-align:center;<?php echo $bordure;?>"><?php echo $rowH['Vacation'];?></td>
							<td width="6%" style="text-align:center;<?php echo $bordure;?>"><?php echo $rowH['Id_StatutPROD'];?></td>
							<td width="10%" style="text-align:center;<?php echo $bordure;?>"><?php echo $rowH['RetourProd'];?></td>
							<td width="12%" style="text-align:center;<?php echo $bordure;?>"><?php echo AfficheDateFR($rowH['DateInterventionQ']);?></td>
							<td width="10%" style="text-align:center;<?php echo $bordure;?>"><?php echo $rowH['VacationQ'];?></td>
							<td width="6%" style="text-align:center;<?php echo $bordure;?>"><?php echo $rowH['Id_StatutQUALITE'];?></td>
							<td width="10%" style="text-align:center;<?php echo $bordure;?>"><?php echo $rowH['RetourQualite'];?></td>
							</tr>
						<?php
						$nb++;
					}
				}
			?>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
		<tr><td>
		<table width="100%" cellpadding="3" cellspacing="0" align="center" class="GeneralInfo">
			<tr>
				<td colspan="6" style="font-size:15px;font-weight:bold;color:#4d4a82;" align="center">INFORMATIONS INTERVENTION</td>
			</tr>
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
				<td colspan="2" width="50%" valign="top">
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td width="13%" class="Libelle">&nbsp; N° IC</td>
							<td width="20%">
								<input type="text" style="text-align:center;" id="numIC" name="numIC" size="15" value="<?php echo $row['NumFI'];?>"  <?php if(substr($_SESSION['DroitSP'],0,1)=='0' && substr($_SESSION['DroitSP'],1,1)=='0'){echo "readonly='readonly'";} ?>>
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td colspan="2" width="13%" class="Libelle" id="LibelleTravailRealise" valign="top">&nbsp; Travail à réaliser : </td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td colspan="2" width="20%">
								<textarea id="travailRealise" name="travailRealise" rows="5" cols="40" style="resize:none;" <?php echo $readSTCE;?>><?php echo stripslashes($row['TravailRealise']);?></textarea>
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
					</table>
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
									<option name="VSD" value="VSD">Weekend</option>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width="13%" class="Libelle">&nbsp; Temps passé : </td>
							<td bgcolor="#dbeef9" width="20%" colspan="3">
								<input  onKeyUp='nombre(this)' type="text" style="text-align:center;" id="tempsProd" name="tempsProd" size="7" value=""  <?php if(substr($_SESSION['DroitSP'],0,1)=='0' && substr($_SESSION['DroitSP'],1,1)=='0'){echo "readonly='readonly'";} ?>>
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
											$req.="FROM new_rh_etatcivil LEFT JOIN sp_acces ON new_rh_etatcivil.Id=sp_acces.Id_Personne WHERE sp_acces.Id_Prestation IN (1792) AND SUBSTR(sp_acces.Droit,3,1)='1' ORDER BY Nom, Prenom;";
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
									<tr><td class="Libelle" width="70%">Personne</td></tr>
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
									$reqTERA="SELECT Id FROM sp_olwficheintervention WHERE Id_Dossier=".$row['Id_Dossier']." AND Id<>".$FI." AND Id_StatutPROD='TERA'";
									$resultTERA=mysqli_query($bdd,$reqTERA);
									$nbTERA=mysqli_num_rows($resultTERA);
									
									if($nbTERA==0){$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation = 1792 AND TypeStatut='P' AND Id<>'REWORK' ORDER BY Id;";}
									else{$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation = 1792 AND TypeStatut='P' AND Id<>'TERA' ORDER BY Id;";}
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
							<td width="13%" class="Libelle" bgcolor="#dbeef9">&nbsp; Retour PROD : </td>
							<td width="20%" bgcolor="#dbeef9">
								<div id='retourP'>
								<select id="retourProd" name="retourProd" <?php echo $disabledCE;?>>
									<option name="0" value="0">Non</option>
									<option name="1" value="1">Oui</option>
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
									<option name="VSD" value="VSD">Weekend</option>
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
									$req.="FROM new_rh_etatcivil LEFT JOIN sp_acces ON new_rh_etatcivil.Id=sp_acces.Id_Personne WHERE sp_acces.Id_Prestation = 1792 AND SUBSTR(sp_acces.Droit,5,1)=1 ORDER BY NomPrenom;";
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
										$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation = 1792 AND TypeStatut='Q' ORDER BY Id;";
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
							<td width="26%" class="Libelle" bgcolor="#c7e048">&nbsp; Retour QUALITE : </td>
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
				<td colspan="2" width="50%" valign="top">
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
												$req="SELECT Id, Libelle FROM sp_olwtypeecme WHERE Supprime=false AND Id_Prestation = 1792 ";
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
													$req="SELECT Id, Libelle,Id_Type FROM sp_olwecme WHERE Supprime=false AND Id_Prestation = 1792 ";
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
												$req="SELECT Id, Libelle FROM sp_olwtypeecme WHERE Supprime=false AND Id_Prestation = 1792 ";
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
													$req="SELECT Id, Libelle,Id_Type FROM sp_olwecme WHERE Supprime=false AND Id_Prestation = 1792 ";
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
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; Type : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<select id="typeECMECLIENT" name="typeECMECLIENT" onchange="Recharge_RefECMECLIENT()" style="width:100px;" onkeypress="if(event.keyCode == 13)AjouterECMECLIENT()">
												<?php
												echo"<option name='0' value='0'></option>";
												$req="SELECT Id, Libelle FROM sp_olwtypeecmeclient WHERE Supprime=false AND Id_Prestation = 1792 ";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												if ($nbResulta>0){
													$i=0;
													while($rowType=mysqli_fetch_array($result)){
														echo "<option value='".$rowType['Id']."'>".$rowType['Libelle']."</option>";
														echo "<script>Liste_ECMECLIENT[".$i."] = new Array('".$rowType['Id']."','".str_replace("'"," ",$rowType['Libelle'])."');</script>\n";
														$i+=1;
													}
												}
												?>
											</select>
										</td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;N° client : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>
											<div id="listeECMECLIENT">
												&nbsp; <select id="referenceClient" name="referenceClient" style="width:100px;" onkeypress="if(event.keyCode == 13)AjouterECMECLIENT()">
													<?php
													echo"<option value='0'></option>";
													$req="SELECT Id, Libelle,Id_Type FROM sp_olwecmeclient WHERE Id_Prestation = 1792 AND Supprime=0 ";
													$result=mysqli_query($bdd,$req);
													$nbResulta=mysqli_num_rows($result);
													if ($nbResulta>0){
														$i=0;
														while($rowECME=mysqli_fetch_array($result)){
															echo "<script>Liste_RefECMECLIENT[".$i."] = new Array('".$rowECME['Id']."','".$rowECME['Id_Type']."','".str_replace("'"," ",$rowECME['Libelle'])."');</script>\n";
															$i+=1;
														}
													}
													?>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;OU autre n° client : </td>
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
									<tr><td class="Libelle">Type</td><td class="Libelle">N° Client</td><td class="Libelle">Date de fin d'étalonnage</td></tr>
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
												$req="SELECT Id, Libelle FROM sp_olwingredient WHERE Supprime=false AND Id_Prestation = 1792 ORDER BY Libelle;";
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
												$req.="WHERE new_competences_prestation_qualification.Id_Prestation = 1792 ";
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
									<?php
										$listeAIPI="";
										$req="SELECT Id_Qualification,Qualification, ";
										$req.="IF(Id_Qualification>0,(SELECT new_competences_qualification.Libelle ";
										$req.="FROM new_competences_qualification WHERE new_competences_qualification.Id=sp_olwfi_aipi.Id_Qualification),Qualification) AS Libelle ";
										$req.="FROM sp_olwfi_aipi WHERE Id_FI=".$FI." ORDER BY Libelle;";
										
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
			<tr style="display:none;"><td><input id='lesAIPI' name='lesAIPI' value="<?php echo $listeAIPI;?>" readonly='readonly'></td></tr>
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
			<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="AfficherSubmit()">
		</td>
		<?php
		}
		?>
	
	</tr>
	<tr><td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;<img src='../../../Images/etoile.png' width='8' height='8' border='0'> Informations obligatoires à remplir</td></tr>
	<tr><td height="4"></td></tr>
</table>
</form>
<?php
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
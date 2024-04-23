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
	<script>
		function OuvreDef(){window.open("pdf.php?Doc=PDF/Definition des cat�gories","PageDoc","status=no,menubar=no,scrollbars=no,width=50,height=50");}
		function AfficherSubmit(){
			var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
			document.getElementById('Enregistrer').innerHTML=bouton;
			var evt = document.createEvent("MouseEvents");
			evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
			document.getElementById("btnEnregistrer2").dispatchEvent(evt);
			document.getElementById('Enregistrer').innerHTML="";
		}
	</script>
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
		//Ajout du dossier
		$req="INSERT INTO sp_olwdossier (Id_Prestation,Id_Personne,MSN,Id_Client,TypeACP,Reference,ReferenceNC,ControleEquipement,";
		$req.="SectionACP,CaecACP,Priorite,Titre,DateCreation,TAI_RestantACP,";
		$req.="Systeme,Structure,Metal,Mastic,Peinture,Id_ZoneDeTravail,Origine,NumOrigine,Id_Statut,Avancement,Id_Retour,CommentaireZICIA,NumSN)";
		$req.=" VALUES (1539,".$_SESSION['Id_PersonneSP'].",".$_POST['msn'].",".$_POST['client'].",'".$_POST['typeDossier']."','".addslashes($_POST['numDossier'])."','".addslashes($_POST['numNC'])."',".$_POST['controleEquipement'].",";
		$req.="'".$_POST['section']."','".$_POST['caec']."',".$_POST['priorite'].",'".addslashes($_POST['titre'])."',";
		$req.="'".$DateJour."',".$_POST['tai'].",";
		if(isset($_POST['Systeme'])){$req.="1,";}else{$req.="0,";}
		if(isset($_POST['Structure'])){$req.="1,";}else{$req.="0,";}
		if(isset($_POST['Metal'])){$req.="1,";}else{$req.="0,";}
		if(isset($_POST['Mastic'])){$req.="1,";}else{$req.="0,";}
		if(isset($_POST['Peinture'])){$req.="1,";}else{$req.="0,";}
		$req.="".$_POST['zone'].",'".$_POST['origine']."','".addslashes($_POST['numOrigine'])."',";
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
		$req.="'".addslashes($_POST['commentaireZI'])."','".addslashes($_POST['numSN'])."')";
		$resultAjour=mysqli_query($bdd,$req);
		$IdCree = mysqli_insert_id($bdd);
		if($IdCree<>0){
			//Ajout ATA/Sous-ATA
			$tabATA = explode(";",$_POST['ata_sousata']);
			foreach($tabATA as $valeur){
				 if($valeur<>""){
					$tab2 = explode("_",$valeur);
					$req="INSERT INTO sp_olwdossier_ata (Id_Dossier,ATA,SousATA) VALUES (".$IdCree.",".$tab2[0].",".$tab2[1].")";
					$resultAjour=mysqli_query($bdd,$req);
				 }
			}
			
			//Ajout de la fiche d'intervention
			$req="INSERT INTO sp_olwficheintervention (";
			$req.="Id_Dossier,Id_Createur,DateCreation,PosteAvionACP,Id_Pole,TempsST,DeposeRepose,PieceAuPoste,NumFI,TravailRealise,Commentaire,PasDePS,PasDeECMEPROD,PasDeECMEQUALITE,PasDeIngredient,ValidationPSCE,ValidationPSIQ";
			if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1'){
				$req.=",DateIntervention,Vacation,TempsObjectif";
			}
			if(substr($_SESSION['DroitSP'],1,1)=='1'){
				$req.=",Id_StatutPROD,Avancement,DateCreationPROD,Id_RetourPROD,Id_PROD,CommentairePROD";
			}
			if(substr($_SESSION['DroitSP'],4,1)=='1'){
				$req.=",DateInterventionQ,VacationQ,TempsQUALITE,Id_StatutQUALITE,DateCreationQUALITE,Id_RetourQUALITE,Id_QUALITE,CommentaireQUALITE";
			}
			$req.=") VALUES (";
			
			$temps=0;
			if($_POST['tempsST']<>""){$temps=$_POST['tempsST'];}
			$req.=$IdCree.",".$_SESSION['Id_PersonneSP'].",'".$DateJour."','".addslashes($_POST['poste'])."',".$_POST['pole'].",".$temps.",".$_POST['deposeRepose'].",";
			$req.="'".addslashes($_POST['pieceauposte'])."','".addslashes($_POST['numFI'])."','".addslashes($_POST['travailRealise'])."','".addslashes($_POST['commentaire'])."',";
			
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
				$req.= "'".TrsfDate_($_POST['dateIntervention'])."','".$_POST['vacation']."',".$temps."";
			}
			
			if(substr($_SESSION['DroitSP'],1,1)=='1'){
				if($_POST['statutProd']<>"0"){
					$req.= ",'".$_POST['statutProd']."',".$_POST['avancementProd'].",'".$DateJour."',".$_POST['retourProd'].",".$_SESSION['Id_PersonneSP'].",'".addslashes($_POST['commentairePROD'])."'";
				}
				else{
					$req.= ",'','0001-01-01',0,0,'".addslashes($_POST['commentairePROD'])."'";
				}
			}
			if(substr($_SESSION['DroitSP'],4,1)=='1'){
				$tempsQUALITE=0;	
				if($_POST['tempsControle']<>""){$tempsQUALITE=$_POST['tempsControle'];}
				$req.= ",'".TrsfDate_($_POST['dateInterventionQ'])."','".$_POST['vacationQ']."',".$tempsQUALITE."";
				$Id_IQ=0;
				if($_POST['IQ']<>""){$Id_IQ=$_POST['IQ'];}
				else{$Id_IQ=$_SESSION['Id_PersonneSP'];}
				if($_POST['statutQualite']<>"0"){
					$req.= ",'".$_POST['statutQualite']."','".$DateJour."',".$_POST['retourQualite'].",".$Id_IQ.",'".addslashes($_POST['commentaireQualite'])."'";
				}
				else{
					$req.= ",'','0001-01-01',0,".$Id_IQ.",'".addslashes($_POST['commentaireQualite'])."'";
				}
			}
			
			$req.= "); ";
			$resultAjour=mysqli_query($bdd,$req);
			$IdFICree = mysqli_insert_id($bdd);
			
			//Avant la mise � jour des ECME PROD 
			//R�cup�ration des ECME PROD non identifi�s & v�rifier si existe d�j� dans la BDD
			//Sinon envoi un mail aux Coordinateurs d'�quipe de la prestation pour les avertir
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
									AND new_competences_personne_poste_prestation.Id_Prestation IN (1539,1511)
									AND new_competences_personne_poste_prestation.Id_Poste=2
									AND new_rh_etatcivil.EmailPro<>''";
							$resultCE=mysqli_query($bdd,$ReqResponsablePostePrestation);
							$nbCE=mysqli_num_rows($resultCE);
							if($nbCE>0){
								while($rowCE=mysqli_fetch_array($resultCE)){
									$Emails.=$rowCE['EmailPro'].",";
								}
							}
							$Objet="Nouvel ECME � mettre � jour, suivi production prestation AHDO";
							$MessageMail="	<html>
											<head><title>Nouvel ECME � mettre � jour, suivi production prestation AHDO</title></head>
											<body>
												Bonjour,
												<br><br>
												Attention, une r�f�rence d'ECME non identifi�e dans la liste pr�d�finie de votre outil de suivi production vient d'�tre saisie pour la prestation AHDO<br>
												Veuillez v�rifier l'exactitude des donn�es saisies et mettre � jour la liste des ECME si cet outil est amen� � �tre utilis� sur votre prestation pour vos futurs travaux
												<br>ECME : ".$ECME."
												<br>
												Bonne journ�e.<br>
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
			
			//Avant la mise � jour des ECME QUALITE 
			//R�cup�ration des ECME PROD non identifi�s & v�rifier si existe d�j� dans la BDD
			//Sinon envoi un mail aux Coordinateurs d'�quipe de la prestation pour les avertir
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
									AND new_competences_personne_poste_prestation.Id_Prestation IN (1539,1511)
									AND new_competences_personne_poste_prestation.Id_Poste=2
									AND new_rh_etatcivil.EmailPro<>''";
							$resultCE=mysqli_query($bdd,$ReqResponsablePostePrestation);
							$nbCE=mysqli_num_rows($resultCE);
							if($nbCE>0){
								while($rowCE=mysqli_fetch_array($resultCE)){
									$Emails.=$rowCE['EmailPro'].",";
								}
							}
							$Objet="Nouvel ECME � mettre � jour, suivi production prestation AHDO";
							$MessageMail="	<html>
											<head><title>Nouvel ECME � mettre � jour, suivi production prestation AHDO</title></head>
											<body>
												Bonjour,
												<br><br>
												Attention, une r�f�rence d'ECME non identifi�e dans la liste pr�d�finie de votre outil de suivi production vient d'�tre saisie pour la prestation AHDO<br>
												Veuillez v�rifier l'exactitude des donn�es saisies et mettre � jour la liste des ECME si cet outil est amen� � �tre utilis� sur votre prestation pour vos futurs travaux
												<br>ECME : ".$ECME."
												<br>
												Bonne journ�e.<br>
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
			
			//Avant la mise � jour des Inr�dients
			//R�cup�ration des ingredients non identifi�s & v�rifier si existe d�j� dans la BDD
			//Sinon envoi un mail aux Coordinateurs d'�quipe de la prestation pour les avertir
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
									AND new_competences_personne_poste_prestation.Id_Prestation IN (1539,1511)
									AND new_competences_personne_poste_prestation.Id_Poste=2
									AND new_rh_etatcivil.EmailPro<>''";
							$resultCE=mysqli_query($bdd,$ReqResponsablePostePrestation);
							$nbCE=mysqli_num_rows($resultCE);
							if($nbCE>0){
								while($rowCE=mysqli_fetch_array($resultCE)){
									$Emails.=$rowCE['EmailPro'].",";
								}
							}
							$Objet="Nouvel ingr�dient � mettre � jour, suivi production prestation AHDO";
							$MessageMail="	<html>
											<head><title>Nouvel ingr�dient � mettre � jour, suivi production prestation AHDO</title></head>
											<body>
												Bonjour,
												<br><br>
												Attention, un ingr�dient non identifi� dans la liste pr�d�finie de votre outil de suivi production vient d'�tre saisi pour la prestation AHDO<br>
												Veuillez v�rifier l'exactitude des donn�es saisies et mettre � jour la liste des ingr�dients si cet ingr�dient est amen� � �tre utilis� sur votre prestation pour vos futurs travaux
												<br>Ingr�dient : ".$Produit."
												<br>
												Bonne journ�e.<br>
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
			
			//Avant la mise � jour des PS 
			//R�cup�ration des PS non identifi�s & v�rifier si existe d�j� dans la BDD
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
									AND new_competences_personne_poste_prestation.Id_Prestation IN (1539,1511)
									AND new_competences_personne_poste_prestation.Id_Poste=5
									AND new_rh_etatcivil.EmailPro<>''";
							$resultCQP=mysqli_query($bdd,$ReqResponsablePostePrestation);
							$nbCQP=mysqli_num_rows($resultCQP);
							if($nbCQP>0){
								while($rowCQP=mysqli_fetch_array($resultCQP)){
									$Emails.=$rowCQP['EmailPro'].",";
								}
							}
							$Objet="Nouveau PS identifi�, suivi production prestation AHDO";
							$MessageMail="	<html>
											<head><title>Nouveau PS identifi�, suivi production prestation AHDO</title></head>
											<body>
												Bonjour,
												<br><br>
												Attention, une r�f�rence de proc�d� sp�cial non identifi�e au tableu de comp�tences vient d'�tre saisie dans le suivi production de la prestation AHDO<br>
												PS : ".$Qualif."
												<br>
												Bonne journ�e.<br>
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
				//Ajout des compagnons
				$tabCompagnon = explode(";",$_POST['travailEffectue']);
				foreach($tabCompagnon as $valeur){
					 if($valeur<>""){
						$tab2 = explode("C_",$valeur);
						$req="INSERT INTO sp_olwfi_travaileffectue (Id_FI,Id_Personne,TempsPasse,TempsTravail) VALUES (".$IdFICree.",".$tab2[0].",".$tab2[1].",".$tab2[2].")";
						$resultAjour=mysqli_query($bdd,$req);
					 }
				}
			}
		}
		
		if(substr($_SESSION['DroitSP'],0,1)=='1'){
			echo "<script>GenererFicheSuiveuse(".$IdCree.")</script>";
		}
	}
}
?>
<form id="formulaire" class="test" method="POST" action="Ajouter_DossierSansACP.php" onSubmit="return VerifChamps()">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Planifier un nouveau dossier <font color="red"></font></td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
	if($modePoste==1){
		echo "<tr><td colspan='6' align='center' style='color:red;'>Le dossier a �t� cr�� et planifi�</td></tr>";
	}
	?>
	<tr><td height="4"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td colspan="6" style="font-size:15px;font-weight:bold;color:#4d4a82;" align="center">INFORMATIONS DOSSIER
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr style="display:none;"><td><input id="idDossier" name="idDossier" value="0"  readonly="readonly"></td></tr>
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
			<td width="13%" class="Libelle">&nbsp; N� dossier : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td width="15%">
				<input type="texte" id="numDossier" name="numDossier" size="15"/>
			</td>
			<td width='13%' class='Libelle'>&nbsp; MSN : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td width='15%'><input onKeyUp='nombre(this)' id='msn' name='msn' value='' size='5'></td>
			<td width='13%' class='Libelle'>&nbsp; Client : </td>
			<td width='15%'>
				<select id="client" name="client" onchange="AfficherSN()">
					<option name='0' value='0'></option>
					<?php
						$req="SELECT Id,Libelle FROM sp_client WHERE Id_Prestation=1539 AND Supprime=false ORDER BY Libelle";
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
		</tr>
		<?php
			/*
			//V�rification si le dossier n'existe pas d�j� dans sp_olwdossier
			$req="SELECT Id,Reference FROM sp_olwdossier WHERE Id_Prestation=1539 ";
			$resultBDD=mysqli_query($bdd,$req);
			$nbBDD=mysqli_num_rows($resultBDD);
			if($nbBDD>0){
				$i=0;
				while($rowRef=mysqli_fetch_array($resultBDD)){
					echo "<script>Liste_Reference[".$i."] = new Array('".$rowRef['Id']."','".$rowRef['Reference']."');</script>\n";
					$i+=1;
				}
			}*/
		?>
		<tr><td height='4'></td></tr>
		<tr>
			<?php
			echo "<td width='13%' class='Libelle'>&nbsp; Zone de travail : ".$etoile."</td><td width='15%'>";
			echo"<select id='zone' name='zone' ".$disabled.">";
				echo"<option name='0' value='0'></option>";
				$req="SELECT Id,Libelle FROM sp_olwzonedetravail  WHERE Id_Prestation=1539 AND Supprime=false ORDER BY Libelle;";
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
			?>
			<td style="display:none;" width="13%" class="Libelle">&nbsp; Section : <?php echo $etoile2; ?></td>
			<td style="display:none;" width='15%'>
				<select id="section" name="section">
					<option value=""></option>
					<?php
						$req="SELECT Id,Libelle FROM sp_olwsection WHERE Id_Prestation=1539 AND Supprime=false ORDER BY Libelle;";
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
			</td>
		</tr>
		<?php
		echo "<tr style='display:none;'><td><input id='droit' name='droit' value='".$_SESSION['DroitSP']."'  readonly='readonly'></td></tr>";
		echo "<tr><td height='4'></td></tr>";
		echo "<tr style='display:none;'>";
		echo "<td width='13%' class='Libelle' valign='top'>&nbsp; Priorit� : ".$etoile."</td>";
		echo "<td width='15%' valign='top'>";
		?>
			<select id="priorite" name="priorite" <?php echo $disabled;?>>
				<option value="1">Low</option>
				<option value="2">Medium</option>
				<option value="3">High</option>
			</select>
		<?php
		echo "</td>";
		echo "<td width='13%' class='Libelle'>&nbsp; Tps restant (h) : </td>";
		echo "<td width='20%'><input onKeyUp='nombre(this)' id='tai' name='tai' value='' size='8' ></td>";
		echo "<td width='13%' class='Libelle'>&nbsp; CA/EC : </td><td width='15%'><input id='caec' name='caec' value='' size='8'></td>";
		echo "</tr>";
		echo "<tr style='display:none;'><td height='4'></td></tr>";
		echo "<tr style='display:none;'>";
		echo "<td width='13%' class='Libelle'>&nbsp; Origine : </td><td width='20%'>";
			?>
			<select id="origine" name="origine" onchange="AfficherDepose();" <?php echo $disabled;?>>
				<option name='' value=''></option>
				<option name='Admin' value='Admin'>Admin</option>
				<option name='DA' value='DA'>DA</option>
				<option name='NC' value='NC'>NC</option>
				<option name='PNE' value='PNE'>PNE</option>
			</select>
			<?php
		echo "</td>";
		echo "<td width='13%'class='Libelle'>&nbsp; N� origine : </td><td width='20%'>";
		?>
			<input id='numOrigine' name='numOrigine' value='' <?php echo $read;?>></td>
		<?php
		echo "<td width='13%'class='Libelle'>&nbsp; Contr�le �quipement : </td><td width='20%'>";
		?>
			<select id="controleEquipement" name="controleEquipement" <?php echo $disabled;?>>
				<option value='0' selected>Non</option>
				<option value='1'>Oui</option>
			</select>
		<?php
		echo "</tr>";
		echo "<tr><td height='4'></td></tr>";
		echo "<tr style='display:none;'>";
			
			echo "<td width='13%' class='Libelle'>&nbsp; Commentaire zone : </td><td width='15%' colspan='3'>";
			?>
				<input id="commentaireZI" name="commentaireZI" value="" style="width: 80%;" <?php echo $read;?>>
			<?php
			echo "</td>";
		echo "</tr>";
		echo "<tr style='display:none;'><td height='4'></td></tr>";
		echo "<tr style='display:none;'>";
		echo "<td width='13%' class='Libelle' valign='top'>&nbsp; Comp�tence(s) : ".$etoile."</td>";
		echo "<td width='15%'>";
		echo "<table width='100%' cellpadding='0' cellspacing='0' align='left'>";
		?>
			<tr>
				<td><input type="checkbox" id="Mastic" name="Mastic" value="Mastic" <?php echo $disabled;?> >Mastic &nbsp;&nbsp;</td>
				<td><input type="checkbox" id="Structure" name="Structure" value="Structure" <?php echo $disabled;?>>Structure &nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td><input type="checkbox" id="Metal" name="Metal" value="Metal" <?php echo $disabled;?> >Metal &nbsp;&nbsp;</td>
				<td><input type="checkbox" id="Systeme" name="Systeme" value="Systeme" <?php echo $disabled;?>>Systeme &nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td><input type="checkbox" id="Peinture" name="Peinture" value="Peinture" <?php echo $disabled;?>>Peinture &nbsp;&nbsp;</td>
				
			</tr>
		<?php
		echo "</table>";
		echo "</td>";
		echo "<td width='13%' class='Libelle'>";
				echo "<table cellpadding='0' cellspacing='0' style='-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;'>";
				echo "<tr>";
				echo "<td colspan='2'>&nbsp; Liste des ATA/Sous-ATA : &nbsp;".$etoile."";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td bgcolor='#e4e7f0'>";
				echo "&nbsp; ATA : ";
				echo "</td>";
				echo "<td bgcolor='#e4e7f0'>";
				echo"<select name='ata' id='ata' onchange='Recharge_SousATA();' onkeypress='if(event.keyCode == 13)Ajouter()'>";
					echo"<option name='' value=''></option>";
					$req="SELECT DISTINCT ATA FROM sp_atasousata ORDER BY ATA;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($rowATA=mysqli_fetch_array($result)){
							echo "<option name='".$rowATA['ATA']."' value='".$rowATA['ATA']."'>".$rowATA['ATA']."</option>";
						}
					}
				echo"</select>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td bgcolor='#e4e7f0'>&nbsp;Sous-ATA : </td>";
					echo "<td bgcolor='#e4e7f0'>";
						echo "<div id='sousatas'>";
							echo"<select id='sousata' name='sousata' onkeypress='if(event.keyCode == 13)Ajouter()'>";
								echo"<option value=''></option>";
								$req="SELECT ATA, SousATA FROM sp_atasousata ORDER BY ATA, SousATA;";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									$i=0;
									while($rowATA=mysqli_fetch_array($result)){
										echo "<script>Liste_SousATA[".$i."] = new Array('".$rowATA['ATA']."','".$rowATA['SousATA']."');</script>\n";
										$i+=1;
									}
								}
							echo"</select>";
						echo "</div>";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>";
				if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
					echo "<a style='text-decoration:none;' class='Bouton' href='javascript:Ajouter()'>&nbsp;Ajouter&nbsp;</a>";
				}
				echo "</td>";
				echo "</tr>";
				echo "</table>";
			echo "</td>";
			echo "<td width='15%' valign='top'><table id='tab_ATA' width='100%' cellpadding='0' cellspacing='0'>";
			echo "<tr><td class='Libelle'>ATA</td><td class='Libelle'>Sous-ATA</td><td></td></tr>";
			$listeATA="";
			echo "</table>";
		echo "</td>";
		echo "<td width='13%'class='Libelle' valign='top' style='display:none;' id='SNLibelle'>&nbsp; N� SN : </td><td width='20%' valign='top' style='display:none;' id='SNInput'>";
		?>
		<input id='numSN' name='numSN' value='' <?php echo $read;?>></td>
		<?php
		echo "</tr>";
		echo "<tr style='display:none;'><td><input id='ata_sousata' name='ata_sousata' value='".$listeATA."'  readonly='readonly'></td></tr>";
		?>
		<tr><td height="4"></td></tr>
		<tr>
			<td width="13%" class="Libelle">&nbsp; Type du dossier : </td>
			<td width='15%'>
				<select id="typeDossier" name="typeDossier">
					<option value=""></option>
					<option value="S01">S01</option>
					<option value="S02">S02</option>
					<option value="S03">S03</option>
					<option value="S99">S99</option>
				</select>
			</td>
			<td width="13%" class="Libelle">&nbsp; N� NC/AM : </td>
			<td width="15%">
				<input type="texte" id="numNC" name="numNC" size="15"/>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<?php
		$nbTempsDossier=0;
		echo "<tr><td width='13%' valign='top' class='Libelle'>&nbsp; Temps pass� : </td><td width='15%' valign='top'><input type='text' size='4' style='border:none' name='tpsDossier' id='tpsDossier' value='".$nbTempsDossier."' readonly='readonly'/></td>";
		?>
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
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="4"></td></tr>
						<tr>
							<td width="13%" class="Libelle">&nbsp; Poste avion : </td>
							<td width="20%">
								<select id="poste" name="poste" <?php echo $disabled;?>>
									<option name="" value=""></option>
								<?php
									$IdPole=0;
									$poste="";
									$req="SELECT Id,Libelle FROM sp_poste WHERE Id_Prestation=1539 AND Supprime=false ORDER BY Libelle;";
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
							<td width="13%" class="Libelle">&nbsp;Temps support technique : </td>
							<td width="20%" colspan="3">
								<input onKeyUp="nombre(this)" id="tempsST" <?php echo $disabled;?> name="tempsST" size="5" type="text" value="">
							</td>
						</tr>
						<tr style="display:none;"><td height="4" colspan="2"></td></tr>
						<tr style="display:none;">
							<td width="13%" class="Libelle">&nbsp; P�le : <?php echo $etoile;?></td>
							<td width="20%">
								<select id="pole" name="pole" <?php echo $disabled;?>>
									<option name="0" value="0"></option>
									<?php
										$req="SELECT Id, Libelle FROM sp_pole WHERE Id_Prestation IN (1539,1511) ORDER BY Libelle;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($rowPole=mysqli_fetch_array($result)){
												$selected="";
												echo "<option name='".$rowPole['Id']."' value='".$rowPole['Id']."' ".$selected.">".$rowPole['Libelle']."</option>";
											}
										}
									?>
								</select>
							</td>
						</tr>
						<tr class="depose" style="display:none;"><td height="4" colspan="2"></td></tr>
						<tr class="depose" style="display:none;">
							<td width="13%" class="Libelle">
								&nbsp; D�pose <?php echo $etoile;?> <input type="radio" id='deposeRepose' name='deposeRepose' value="0" checked>
							</td>
							<td class="Libelle">
								&nbsp; Repose <input type="radio" id='deposeRepose' name='deposeRepose' value="1">
							</td>
						</tr>
						<tr style="display:none;"><td height="4" colspan="2"></td></tr>
						<tr style="display:none;">
							<td width="13%" id="LibellePieceauposte" class="Libelle">&nbsp; Pi�ce � retirer <br> &nbsp; au poste : </td>
							<td width="20%">
								<select id="pieceauposte" name="pieceauposte" <?php echo $disabled;?>>
									<option value=""></option>
									<option value="Chariot de DA">Chariot de DA</option>
									<option value="K943">K943</option>
									<option value="Station livraison">Station livraison</option>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td width="13%" class='Libelle'>&nbsp; N� FI : </td>
							<td width="20%">
								<input id="numFI" name="numFI" value="">
							</td>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
						<tr>
							<td colspan="2" width="13%" id="LibelleTravailRealise" class="Libelle" valign="center">&nbsp; Travail � r�aliser : <?php echo $etoile;?></td>
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
							<td bgcolor="#dbeef9" width="13%" class="Libelle">&nbsp;Temps objectif : </td>
							<td bgcolor="#dbeef9" width="20%" colspan="3">
								<input onKeyUp="nombre(this)" id="tempsObjectif" <?php echo $readSTCE;?> name="tempsObjectif" size="5" type="text" value="">
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width='13%' class="Libelle" valign="top">
								<table cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor='#e4e7f0' colspan='2'><i>&nbsp; Ajouter les op�rateurs :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; Personne : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
										<select id="compagnon" id="compagnon" name="compagnon" style="width:130px;" onkeypress="if(event.keyCode == 13)AjouterTE()">
											<?php
											echo"<option name='0' value='0'></option>";
											$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
											$req.="FROM new_rh_etatcivil LEFT JOIN sp_acces ON new_rh_etatcivil.Id=sp_acces.Id_Personne WHERE sp_acces.Id_Prestation=1539 AND SUBSTR(sp_acces.Droit,3,1)='1' ORDER BY Nom, Prenom;";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											if ($nbResulta>0){
												$i=0;
												while($rowCompagnon=mysqli_fetch_array($result)){
													echo "<option value='".$rowCompagnon['Id']."'>".$rowCompagnon['Nom']." ".$rowCompagnon['Prenom']."</option>";
													echo "<script>Liste_Personne[".$i."] = new Array('".$rowCompagnon['Id']."','".addslashes($rowCompagnon['Nom'])."','".addslashes($rowCompagnon['Prenom'])."');</script>\n";
													$i+=1;
												}
											}
											?>
										</select>
										</td>
									</tr>
									<tr>
										<td width="40%" bgcolor='#e4e7f0'>&nbsp;Temps intervention : </td>
									</tr>
									<tr>
										<td width="60%" bgcolor='#e4e7f0'>&nbsp; 
											<input onKeyUp="nombre(this)" style="text-align:center;" onKeypress="if(event.keyCode == 13)AjouterTE()" id="tempsPasse" name="tempsPasse" size="5" type="text" value="">
										</td>
									</tr>
									<tr>
										<td width="40%" bgcolor='#e4e7f0'>&nbsp;Temps travail : </td>
									</tr>
									<tr>
										<td width="60%" bgcolor='#e4e7f0'>&nbsp; 
											<input onKeyUp="nombre(this)" style="text-align:center;" onKeypress="if(event.keyCode == 13)AjouterTE()" id="tempsTravail" name="tempsTravail" size="5" type="text" value="">
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
									<tr><td class="Libelle" width="50%">Personne</td><td class="Libelle">Temps intervention</td><td class="Libelle">Temps travail</td></tr>
								</table>
								<table id="tab_TravailEffectue" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle" width="50%">TOTAL</td><td class="Libelle">
										<input type="text" size="4" style="background-color:#dbeef9;border:none" name="tpsFI" id="tpsFI" value="0" readonly="readonly"/>
									</td>
									<td class="Libelle">
										<input type="text" size="4" style="background-color:#dbeef9;border:none" name="tpsTravailFI" id="tpsTravailFI" value="0" readonly="readonly"/>
									</td>
									</tr>
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
									$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation=1539 AND TypeStatut='P' AND Id<>'REWORK' ORDER BY Id;";
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
							<td width="13%" class="Libelle" bgcolor="#dbeef9">&nbsp; Retour PROD : <a style="text-decoration:none;" href="javascript:OuvreDef();"><img id="img" src="../../../Images/aide2.gif" alt="aide" title="aide"></a></td>
							<td width="20%" bgcolor="#dbeef9">
								<div id='retourP'>
								<select id="retourProd" name="retourProd" <?php echo $disabledCE;?>>
									<option name="0" value="0"></option>
									<?php
									$req="SELECT Id, Libelle, Id_Statut, Supprime FROM sp_olwretour WHERE Id_Prestation=1539 AND Supprime=0 ORDER BY Libelle;";
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
							<td bgcolor="#c7e048" width="13%" class="Libelle">&nbsp;Temps de contr�le : </td>
							<td bgcolor="#c7e048" width="20%" colspan="3">
								<input onKeyUp="nombre(this)" id="tempsControle" <?php echo $readIQ;?> name="tempsControle" size="5" type="text" value="">
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td bgcolor="#c7e048" width="13%" class="Libelle">
								&nbsp; Inspecteur qualit� :
							</td>
							<td bgcolor="#c7e048" width="20%" colspan="3">
								<select name="IQ">
									<option name="" value=""></option>
									<?php
									$req="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS NomPrenom ";
									$req.="FROM new_rh_etatcivil LEFT JOIN sp_acces ON new_rh_etatcivil.Id=sp_acces.Id_Personne WHERE sp_acces.Id_Prestation=1539 AND SUBSTR(sp_acces.Droit,5,1)=1 ORDER BY NomPrenom;";
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
						<tr>
							<td width="13%" class="Libelle" bgcolor="#c7e048">&nbsp; Statut QUALITE : </td>
							<td width="20%" bgcolor="#c7e048">
								<div id="statutsQualite">
									<select id="statutQualite" name="statutQualite" onchange="Recharge_StatutQualite();" <?php echo $disabledIQ;?>>
										<option name="" value=""></option>
										<?php
										$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation=1539 AND TypeStatut='Q' ORDER BY Id;";
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
												$req="SELECT Id, Libelle FROM sp_olwtypeecme WHERE Supprime=false AND Id_Prestation=1539 ";
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
										<td bgcolor='#e4e7f0'>&nbsp;R�f�rence : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>
											<div id="listeECMEPROD">
												&nbsp; <select id="referencePROD" name="referencePROD" style="width:100px;" onkeypress="if(event.keyCode == 13)AjouterECMEPROD()">
													<?php
													echo"<option value='0'></option>";
													$req="SELECT Id, Libelle,Id_Type FROM sp_olwecme WHERE Supprime=false AND Id_Prestation=1539 ";
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
									<tr><td class="Libelle">Type</td><td class="Libelle">R�f�rence</td></tr>
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
												$req="SELECT Id, Libelle FROM sp_olwtypeecme WHERE Supprime=false AND Id_Prestation=1539 ";
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
										<td bgcolor='#e4e7f0'>&nbsp;R�f�rence : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>
											<div id="listeECMEQUALITE">
												&nbsp; <select id="referenceQUALITE" name="referenceQUALITE" style="width:100px;" onkeypress="if(event.keyCode == 13)AjouterECMEQUALITE()">
													<?php
													echo"<option value='0'></option>";
													$req="SELECT Id, Libelle,Id_Type FROM sp_olwecme WHERE Supprime=false AND Id_Prestation=1539 ";
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
									<tr><td class="Libelle">Type</td><td class="Libelle">R�f�rence</td></tr>
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
										<td bgcolor='#e4e7f0'>&nbsp; N� client : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:left;" id="numClient" onkeypress="if(event.keyCode == 13)AjouterECMECLIENT()" name="numClient" size="20" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;Date de fin d'�talonnage : </td>
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
									<tr><td class="Libelle">N� Client</td><td class="Libelle">Date de fin d'�talonnage</td></tr>
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
										<td bgcolor='#e4e7f0'>&nbsp; R�f�rence : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<select id="RefProduit" name="RefProduit" onkeypress="if(event.keyCode == 13)AjouterProduit()" style="width:130px;">
												<?php
												echo"<option name='0' value='0'></option>";
												$req="SELECT Id, Libelle FROM sp_olwingredient WHERE Supprime=false AND Id_Prestation=1539 ORDER BY Libelle;";
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
										<td bgcolor='#e4e7f0'>&nbsp;OU autre r�f�rence </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:left;" id="nomProduit" onkeypress="if(event.keyCode == 13)AjouterProduit()" name="nomProduit" size="20" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;N� lot : </td>
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
										<td bgcolor="#e4e7f0">&nbsp;Coeff. Hygrom�trique : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" onkeypress="if(event.keyCode == 13)AjouterProduit()" id="coeffH" name="coeffH" size="8" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor="#e4e7f0">&nbsp;Temp�rature : </td>
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
									<tr><td class="Libelle">Ingr�dient</td><td class="Libelle">N� lot</td><td class="Libelle">Date<br>p�remption</td><td class="Libelle">Coeff.<br>hygrom�trique</td><td class="Libelle">Temp�rature</td></tr>
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
										<td bgcolor="#e4e7f0"><i>&nbsp; Ajouter les proc�d�s sp�ciaux :</i></td>
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
										<td bgcolor='#e4e7f0'>&nbsp;R�f�rence : </td>
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
												$req.="WHERE new_competences_prestation_qualification.Id_Prestation IN (1539,1511) ";
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
										<td bgcolor='#e4e7f0'>&nbsp;OU autre proc�d� sp�cial </td>
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
											<input style="text-align:left;" id="PasDePS" name="PasDePS" type="checkbox" value="PasDePS" onchange="ValidationAutoPS()"> Pas de proc�d� sp�cial requis
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='65%' valign='top'>
								<table id="tab_AIPI" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle" align="center">Proc�d�s sp�ciaux appel�s</td></tr>
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
											<input <?php echo $modifiableCE; ?> style="text-align:left;" id="ValidationPSCE" name="ValidationPSCE" type="checkbox" value="ValidationPSCE" > Validation Chef d'�quipe
										</td>
										<td>
											<input <?php echo $modificationIQ; ?> style="text-align:left;" id="ValidationPSIQ" name="ValidationPSIQ" type="checkbox" value="ValidationPSIQ" > Validation Qualit�
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
	<tr><td colspan="6">&nbsp;&nbsp;&nbsp;&nbsp;<img src='../../../Images/etoile.png' width='8' height='8' border='0'> Informations obligatoires � remplir</td></tr>
	<tr><td height="4"></td></tr>
</table>
</form>
<?php
//	mysqli_free_result($resultDroits);	// Lib�ration des r�sultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
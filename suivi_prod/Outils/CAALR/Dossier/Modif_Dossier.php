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

$DirFichier="Outils/AEWP/Dossier/FicheSuiveuse/";
$DirFichier2="FicheSuiveuse/";

Ecrire_Code_JS_Init_Date();

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
$HeureJour = date("H:i:s");

if($_POST){
	if(isset($_POST['btnEnregistrer2'])){	
		$fichierSuiveuse="";
		$reqPJ="";
		//S'il y avait une fichier
		if(isset($_POST['SupprFichier']))
		{
			if($_POST['SupprFichier'])
			{
				if(file_exists ($DirFichier2.$_POST['fichieractuel'])){
					if(unlink($DirFichier2.$_POST['fichieractuel'])){$fichierSuiveuse="";}
				}
				else{
					$fichierSuiveuse="";
				}
				
				$reqPJ=",FicheSuiveuse='' ";
			}
		}
		
		//****TRANSFERT FICHIER****
		if($_FILES['fichierSuiveuse']['name']!="")
		{
			$tmp_file=$_FILES['fichierSuiveuse']['tmp_name'];
			if(is_uploaded_file($tmp_file)){
				//On vérifie la taille du fichiher
				if(filesize($_FILES['fichierSuiveuse']['tmp_name'])<=$_POST['MAX_FILE_SIZE'])
				{
					// on copie le fichier dans le dossier de destination
					$name_file=$_FILES['fichierSuiveuse']['name'];
					$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°#", "aaaaooeeeeiiuunc____________");
					while(file_exists($DirFichier2.$name_file)){$name_file="_".date('j-m-y')."_".date('H-i-s')." ".$name_file;}
					if(move_uploaded_file($tmp_file,$DirFichier2.$name_file))
					{$fichierSuiveuse=$name_file;$reqPJ=",FicheSuiveuse='".$fichierSuiveuse."' ";}
				}
			}
		}
		
		//MISE A JOUR DU DOSSIER
		$req="UPDATE sp_olwdossier SET ";
		$req.="MSN=".$_POST['msn']." 
		".$reqPJ.", ";
		$req.="Programme='".addslashes($_POST['programme'])."', ";
		$req.="ReferenceNC='".addslashes($_POST['numNC'])."', ";
		if($_POST['dateNC']=="" && $_POST['numNC']<>""){
			$req.="DateNC='".$DateJour."', ";
			$req.="HeureNC='".$HeureJour."', ";
		}
		$req.="DateRenvoiNC='".TrsfDate_($_POST['dateRenvoiNC'])."', ";
		$req.="Imputation='".$_POST['imputation']."', ";
		$req.="Id_Client=".$_POST['client'].", ";
		$req.="ReferenceAM='".addslashes($_POST['numAM'])."', ";
		if($_POST['dateAM']=="" && $_POST['numAM']<>""){
			$req.="DateAM='".$DateJour."', ";
			$req.="HeureAM='".$HeureJour."', ";
		}
		$req.="Reference='".addslashes($_POST['numDossier'])."', ";
		if($_POST['dateOF']=="" && $_POST['numDossier']<>""){
			$req.="DateDossier='".$DateJour."', ";
			$req.="HeureDossier='".$HeureJour."', ";
		}
		$req.="ReferencePF='".addslashes($_POST['numPF'])."', ";
		if($_POST['datePF']=="" && $_POST['numPF']<>""){
			$req.="DatePF='".$DateJour."', ";
			$req.="HeurePF='".$HeureJour."', ";
		}
		$req.="TypeACP='".addslashes($_POST['typeDossier'])."', ";
		$req.="SectionACP='".$_POST['section']."', ";
		$req.="CaecACP='".$_POST['caec']."', ";
		$req.="Priorite=".$_POST['priorite'].", ";
		$req.="Titre='".addslashes($_POST['titre'])."', ";
		$req.="DateTERCPrevisionnelle='".TrsfDate_($_POST['dateTERCPrevisionnelle'])."', ";
		if(isset($_POST['Systeme'])){$req.="Systeme=1, ";}else{$req.="Systeme=0, ";}
		if(isset($_POST['Structure'])){$req.="Structure=1, ";}else{$req.="Structure=0, ";}
		if(isset($_POST['Metal'])){$req.="Metal=1, ";}else{$req.="Metal=0, ";}
		if(isset($_POST['Composite'])){$req.="Composite=1, ";}else{$req.="Composite=0, ";}
		$req.="Id_ZoneDeTravail=".$_POST['zone'].", ";
		
		if($_POST['dernierIC']==true){
			if(substr($_SESSION['DroitSP'],4,1)=='1'){
				if($_POST['statutQualite']<>"0"){
					$req.="Id_Statut='".$_POST['statutQualite']."', ";
					$req.="Avancement=0, ";
					$req.="Id_Retour=".$_POST['retourQualite'].", ";
				}
				else{
					$req.="Id_Statut='".$_POST['statutProd']."', ";
					$req.="Avancement=".$_POST['avancementProd'].", ";
					$req.="Id_Retour=".$_POST['retourProd'].", ";
				}
			}
			elseif(substr($_SESSION['DroitSP'],1,1)=='1'){
				$req.="Id_Statut='".$_POST['statutProd']."', ";
				$req.="Avancement=".$_POST['avancementProd'].", ";
				$req.="Id_Retour=".$_POST['retourProd'].", ";
			}
		}
		if($_POST['statutQualite']=='TERC'){
			$req.="Archive=1, ";
		}

		$req.="CommentaireZICIA='".addslashes($_POST['commentaireZI'])."' ";
		$req.="WHERE Id=".$_POST['idDossier'];
		$resultUpdate=mysqli_query($bdd,$req);
		echo $req;
		
		//MISE A JOUR FICHE D'INTERVENTION
		$reqSelect = "SELECT Id_StatutPROD,Id_StatutQUALITE,StatutPrepa FROM sp_olwficheintervention WHERE Id=".$_POST['idFI'];
		$resultSelect=mysqli_query($bdd,$reqSelect);
		$rowSelect = mysqli_fetch_array($resultSelect);

		$req="UPDATE sp_olwficheintervention SET ";
		$req.="PosteAvionACP='".addslashes($_POST['poste'])."',";
		$req.="DeroNecessaire=".$_POST['DeroNecessaire'].",";
		$req.="TypeAppro='".$_POST['typeDA']."',";
		$req.="AMLiee='".$_POST['amLiee']."',";
		$req.="OTLie='".$_POST['otLie']."',";
		$req.="StatutPrepa='".$_POST['statutPrepa']."',";
		if($rowSelect['StatutPrepa']<>$_POST['statutPrepa']){
			$req.="DatePrepa='".$DateJour."',";
		}
		if($_POST['besoinOutillage']==1){
			$req.="OutillageSpecifique='".addslashes($_POST['outillageSpecifique'])."',";
		}
		else{
			$req.="OutillageSpecifique='',";
		}
		$req.="TravailRealise='".addslashes($_POST['travailRealise'])."',";
		$req.="Commentaire='".addslashes($_POST['commentaire'])."',";
		
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
		
		if($_POST['idFILiee']==0 || $_POST['idStatutProdFILiee']=="RETOUR PREPA"){	
			$req.=",DateAppro='".TrsfDate_($_POST['dateAppro'])."',";
			$req.="DateDA='".TrsfDate_($_POST['dateDA'])."',";
			$req.="NumDERO='".addslashes($_POST['numDERO'])."',";
			$req.="NumDA='".addslashes($_POST['numDA'])."'";
		}
		if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1'){
			$temps=0;			
			if($_POST['tempsObjectif']<>""){$temps=$_POST['tempsObjectif'];}
			$req.= ",DateIntervention='".TrsfDate_($_POST['dateIntervention'])."',";
			$req.= "Vacation='".$_POST['vacation']."',";
			$req.="NumFI='".addslashes($_POST['numIC'])."',";
			$req.= "TempsObjectif=".$temps."";
		}
		if(substr($_SESSION['DroitSP'],1,1)=='1'){
			$req.=",CommentairePROD='".addslashes($_POST['commentairePROD'])."',";
			if($_POST['dernierIC']==true){
				$req.="Id_StatutPROD='".$_POST['statutProd']."',";
				$req.="Avancement=".$_POST['avancementProd'].",";
				if($rowSelect['Id_StatutPROD']<>$_POST['statutProd']){
					$req.="DateCreationPROD='".$DateJour."',";
				}
				$req.="Id_RetourPROD=".$_POST['retourProd'].",";
				$req.="DateTERA='".TrsfDate_($_POST['dateTERA'])."',";
			}
			$req.="Id_PROD=".$_SESSION['Id_PersonneSP']."";
		}
		if(substr($_SESSION['DroitSP'],4,1)=='1'){
			$req.=",DateInterventionQ='".TrsfDate_($_POST['dateInterventionQ'])."',";
			$req.="VacationQ='".$_POST['vacationQ']."',";
			if($_POST['dernierIC']==true){
				$req.="Id_StatutQUALITE='".$_POST['statutQualite']."',";
				if($rowSelect['Id_StatutQUALITE']<>$_POST['statutQualite']){
					$req.="DateCreationQUALITE='".$DateJour."',";
				}
				$req.="Id_RetourQUALITE=".$_POST['retourQualite'].",";
				$req.="DateTERC='".TrsfDate_($_POST['dateTERC'])."',";
			}
			$Id_IQ=0;
			if($_POST['IQ']<>""){$Id_IQ=$_POST['IQ'];}
			else{$Id_IQ=$_SESSION['Id_PersonneSP'];}
			$req.="Id_QUALITE=".$Id_IQ.",";
			$req.="CommentaireQUALITE='".addslashes($_POST['commentaireQualite'])."'";
		}
		$req.=" WHERE Id=".$_POST['idFI'];
		$resultModif=mysqli_query($bdd,$req);
		
		if(substr($_SESSION['DroitSP'],0,1)=='1'){
			//Suppression des MB21
			$req="DELETE FROM sp_olwfi_mb21 WHERE Id_FI=".$_POST['idFI'];
			$resultDelete=mysqli_query($bdd,$req);
			
			//Ajout MB21
			if($_POST['typeDA']=="MB21"){
				$tabMB21 = explode(";",$_POST['mb21']);
				foreach($tabMB21 as $valeur){
					 if($valeur<>""){
						$tab2 = explode("_",$valeur);
						$req="INSERT INTO sp_olwfi_mb21 (Id_FI,NumResa,NumOT) VALUES (".$_POST['idFI'].",'".$tab2[0]."','".$tab2[1]."')";
						$resultAjour=mysqli_query($bdd,$req);
					 }
				}
			}
		}
		
		if(substr($_SESSION['DroitSP'],0,1)=='1'){
			//Suppression de la prepa
			$req="DELETE FROM sp_olwfi_prepa WHERE Id_FI=".$_POST['idFI'];
			$resultDelete=mysqli_query($bdd,$req);
			if($_POST['idFILiee']==0 || $_POST['idStatutProdFILiee']=="RETOUR PREPA"){	
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
					$req.="(".$_POST['idFI'].",418,'".$type."','".$DateDebut."','".$HeureDebut."','".$DateFin."','".$HeureFin."','".addslashes($_POST['commentaire'.$type])."')";
					$resultAjout=mysqli_query($bdd,$req);
				}
			}
		}

		if(substr($_SESSION['DroitSP'],1,1)=='1'){
			//Suppression des anciens compagnons
			$req="DELETE FROM sp_olwfi_travaileffectue WHERE Id_FI=".$_POST['idFI'];
			$resultDelete=mysqli_query($bdd,$req);
			
			//Ajout des compagnons
			$tabCompagnon = explode(";",$_POST['travailEffectue']);
			foreach($tabCompagnon as $valeur){
				 if($valeur<>""){
					$tab2 = explode("C_",$valeur);
					$req="INSERT INTO sp_olwfi_travaileffectue (Id_FI,Id_Personne,TempsPasse) VALUES (".$_POST['idFI'].",".$tab2[0].",".$tab2[1].")";
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
					$req="SELECT Id FROM sp_olwfi_ecme WHERE ECME='".addslashes($ECME)."' AND Id_FI=".$_POST['idFI'];
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
		
		//Suppression des ecme PROD
		$req="DELETE FROM sp_olwfi_ecme WHERE ProdQualite=0 AND Id_FI=".$_POST['idFI'];
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
				$req="INSERT INTO sp_olwfi_ecme (Id_FI,Id_ECME,Id_TypeECME,ECME,ProdQualite) 
				VALUES (".$_POST['idFI'].",".$Id_ECME.",".$Id_TypeECME.",'".$ReferenceECME."',0)";
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
					$req="SELECT Id FROM sp_olwfi_ecme WHERE ECME='".addslashes($ECME)."' AND Id_FI=".$_POST['idFI'];
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
		
		//Suppression des ecme QUALITE
		$req="DELETE FROM sp_olwfi_ecme WHERE ProdQualite=1 AND Id_FI=".$_POST['idFI'];
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
				$req="INSERT INTO sp_olwfi_ecme (Id_FI,Id_ECME,Id_TypeECME,ECME,ProdQualite) 
				VALUES (".$_POST['idFI'].",".$Id_ECME.",".$Id_TypeECME.",'".$ReferenceECME."',1)";
				$resultAjour=mysqli_query($bdd,$req);
			 }
		}
		
		//Suppression des ecme client
		$req="DELETE FROM sp_olwfi_ecmeclient WHERE Id_FI=".$_POST['idFI'];
		$resultDelete=mysqli_query($bdd,$req);
		
		//Ajout des ecme client
		$tabPL = explode(";",$_POST['ECMECLIENT']);
		foreach($tabPL as $valeur){
			 if($valeur<>""){
				$tabECME = explode("_",$valeur);

				$req="INSERT INTO sp_olwfi_ecmeclient (Id_FI,NumClient,DateFinEtalonnage) 
				VALUES (".$_POST['idFI'].",'".$tabECME[0]."','".TrsfDate_($tabECME[1])."')";
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
					$req="SELECT Id FROM sp_olwfi_ingredient WHERE Ingredient='".addslashes($Produit)."' AND Id_FI=".$_POST['idFI'];
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
		
		//Suppression des produits
		$req="DELETE FROM sp_olwfi_ingredient WHERE Id_FI=".$_POST['idFI'];
		$resultDelete=mysqli_query($bdd,$req);
		
		//Ajout des produits
		$tabProduit = explode(";",$_POST['Produit']);
		foreach($tabProduit as $valeur){
			 if($valeur<>""){
				$Id_Produit=0;
				$ReferenceProduit="";
				$NumLot="";
				$DatePeremption="";
				$CoeffHydrometrique="";
				$Temperature="";
				$tabING = explode("ING_ING",$valeur);
				if($tabING[0]==0){
					$tabING2 = explode("_",$tabING[1]);
					$ReferenceProduit=$tabING2[0];
					$NumLot=$tabING2[1];
					$DatePeremption=TrsfDate_($tabING2[2]);
					$CoeffHydrometrique=$tabING2[3];
					$Temperature=$tabING2[4];
				}
				else{
					$tabING2 = explode("_",$tabING[0]);
					$Id_Produit=$tabING2[0];
					$NumLot=$tabING2[1];
					$DatePeremption=TrsfDate_($tabING2[2]);
					$CoeffHydrometrique=$tabING2[3];
					$Temperature=$tabING2[4];
				}

				$tab2 = explode("_",$valeur);
				$req="INSERT INTO sp_olwfi_ingredient (Id_FI,Id_Ingredient,NumLot,DatePeremption,CoeffHydrometrique,Temperature,Ingredient) VALUES (";
				$req.=$_POST['idFI'].",".$Id_Produit.",'".$NumLot."','".$DatePeremption."','".$CoeffHydrometrique."','".$Temperature."','".$ReferenceProduit."')";
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
					$req="SELECT Id FROM sp_olwfi_aipi WHERE Qualification='".addslashes($Qualif)."' AND Id_FI=".$_POST['idFI'];
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

		//Suppression des PS
		$req="DELETE FROM sp_olwfi_aipi WHERE Id_FI=".$_POST['idFI'];
		$resultDelete=mysqli_query($bdd,$req);
		
		//Ajout des PS
		$tabAIPI = explode(";",$_POST['lesAIPI']);
		foreach($tabAIPI as $valeur){
			 if($valeur<>""){
				$tabPS = explode("PS_PS",$valeur);
				$Id_Qualif=0;
				if($tabPS[0]<>""){$Id_Qualif=$tabPS[0];}
				$Qualif=$tabPS[1];
				$req="INSERT INTO sp_olwfi_aipi (Id_FI,Id_Qualification,Qualification) VALUES (".$_POST['idFI'].",".$Id_Qualif.",'".addslashes($Qualif)."')";
				$resultAjour=mysqli_query($bdd,$req);
			 }
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
if($_GET){
	$FI=$_GET['Id'];
	if($_GET['Mode']=="M"){
		$IdPersonne=$_GET['Id_Personne'];
		//INFORMATIONS DOSSIER
		$req="SELECT sp_olwficheintervention.Id,sp_olwficheintervention.Id_Dossier,sp_olwdossier.MSN,sp_olwdossier.ReferencePF,sp_olwdossier.Reference,sp_olwdossier.ReferenceAM,sp_olwdossier.ReferenceNC,";
		$req.="sp_olwdossier.DateAM,sp_olwdossier.HeureAM,sp_olwdossier.DateNC,sp_olwdossier.HeureNC,sp_olwdossier.DateDossier,sp_olwdossier.HeureDossier,sp_olwdossier.FicheSuiveuse,";
		$req.="sp_olwdossier.DatePF,sp_olwdossier.HeurePF,sp_olwdossier.Id_Client,sp_olwdossier.TypeACP AS Type,sp_olwdossier.Imputation, ";
		$req.="sp_olwdossier.Priorite,sp_olwdossier.CaecACP AS Caec,sp_olwdossier.Composite,sp_olwdossier.Metal,sp_olwdossier.Structure,sp_olwdossier.Systeme,";
		$req.="sp_olwdossier.SectionACP AS MCA,sp_olwdossier.Titre,sp_olwdossier.Id_ZoneDeTravail,sp_olwdossier.CommentaireZICIA,";
		$req.="sp_olwdossier.DateTERCPrevisionnelle,sp_olwdossier.DateRenvoiNC,sp_olwdossier.DateCreation,sp_olwdossier.Programme,";
		$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwdossier.Id_Personne) AS CreateurDossier, ";
		
		//INFORMATION INTERVENTION
			//PREPA
		$req.="sp_olwficheintervention.PosteAvionACP,sp_olwficheintervention.DeroNecessaire,sp_olwficheintervention.TravailRealise,sp_olwficheintervention.Commentaire,";
		$req.="sp_olwficheintervention.NumFI,sp_olwficheintervention.DateAppro,sp_olwficheintervention.DateDA,sp_olwficheintervention.NumDA,sp_olwficheintervention.NumDERO,";
		$req.="sp_olwficheintervention.Id_FILiee,sp_olwficheintervention.outillageSpecifique,sp_olwficheintervention.StatutPrepa,";
		$req.="(SELECT sp_FILiee.Id_StatutPROD FROM sp_olwficheintervention AS sp_FILiee WHERE sp_FILiee.Id=sp_olwficheintervention.Id_FILiee) AS Id_StatutPRODFILiee,
				sp_olwficheintervention.PasDePS,sp_olwficheintervention.ValidationPSCE,sp_olwficheintervention.ValidationPSIQ,
				sp_olwficheintervention.PasDeECMEPROD,sp_olwficheintervention.PasDeECMEQUALITE,sp_olwficheintervention.PasDeIngredient,";
		//PROD
		$req.="sp_olwficheintervention.DateIntervention,sp_olwficheintervention.Vacation,sp_olwficheintervention.TempsObjectif,sp_olwficheintervention.Id_StatutPROD,sp_olwficheintervention.DateTERA,";
		$req.="sp_olwficheintervention.Id_RetourPROD,sp_olwficheintervention.CommentairePROD,sp_olwficheintervention.TypeAppro,sp_olwficheintervention.AMLiee,sp_olwficheintervention.OTLie,";
			//QUALITE
		$req.="sp_olwficheintervention.DateInterventionQ,sp_olwficheintervention.VacationQ,sp_olwficheintervention.Id_QUALITE,sp_olwficheintervention.Id_StatutQUALITE,sp_olwficheintervention.DateTERC,";
		$req.="sp_olwficheintervention.Id_RetourQUALITE,sp_olwficheintervention.CommentaireQUALITE ";
		$req.="FROM sp_olwficheintervention LEFT JOIN sp_olwdossier ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id ";
		$req.="WHERE sp_olwficheintervention.Id=".$FI;
		$result=mysqli_query($bdd,$req);
		$row=mysqli_fetch_array($result);
		
		//VERIF SI DERNIER ELEMENT
		$DerniereIC=false;
		$reqFI="SELECT MAX(Id) AS Id FROM sp_olwficheintervention WHERE Id_Dossier=".$_GET['Id_Dossier'];
		$resultFI=mysqli_query($bdd,$reqFI);
		$nbResultaFI=mysqli_num_rows($resultFI);
		if($nbResultaFI>0){
			$rowFI=mysqli_fetch_array($resultFI);
			if($rowFI['Id']==$FI){$DerniereIC=true;}
		}
	}
	elseif($_GET['Mode']=="Archiver"){
		//ARCHIVER DOSSIER
		$req="UPDATE sp_olwdossier SET Archive=1 WHERE Id=".$_GET['Id_Dossier'];
		$resultSuppr=mysqli_query($bdd,$req);
		
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_GET['Mode']=="S"){
		$reqNbFI="SELECT Id AS Id FROM sp_olwficheintervention WHERE Id_Dossier=".$_GET['Id_Dossier'];
		$resultNbFI=mysqli_query($bdd,$reqNbFI);
		$nbResultaNbFI=mysqli_num_rows($resultNbFI);
		
		//SUPPRESSION FICHE INTERVENTION
		$req="DELETE FROM sp_olwficheintervention WHERE Id=".$FI;
		$resultSuppr=mysqli_query($bdd,$req);
		
		//SUPPRESSION AIPIS DE LA FI
		$req="DELETE FROM sp_olwfi_aipi WHERE Id_FI=".$FI;
		$resultSuppr=mysqli_query($bdd,$req);
		
		//SUPPRESSION ECME DE LA FI
		$req="DELETE FROM sp_olwfi_ecme WHERE Id_FI=".$FI;
		$resultSuppr=mysqli_query($bdd,$req);
		
		//SUPPRESSION INGREDIENT DE LA FI
		$req="DELETE FROM sp_olwfi_ingredient WHERE Id_FI=".$FI;
		$resultSuppr=mysqli_query($bdd,$req);
		
		//SUPPRESSION PREPA DE LA FI
		$req="DELETE FROM sp_olwfi_prepa WHERE Id_FI=".$FI;
		$resultSuppr=mysqli_query($bdd,$req);
		
		//SUPPRESSION TRAVAIL EFFECTUE DE LA FI
		$req="DELETE FROM sp_olwfi_travaileffectue WHERE Id_FI=".$FI;
		$resultSuppr=mysqli_query($bdd,$req);
		
		if($nbResultaNbFI==1){
			//SUPPRESSION DU DOSSIER
			$req="DELETE FROM sp_olwdossier WHERE Id=".$_GET['Id_Dossier'];
			$resultSuppr=mysqli_query($bdd,$req);
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
?>
<form id="formulaire" class="test" method="POST" enctype="multipart/form-data" action="Modif_Dossier.php" onSubmit="return VerifChamps(<?php echo substr($_SESSION['DroitSP'],0,1);?>);">
<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
<table width="100%" cellpadding="0" cellspacing="0" align="center">

	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Modifier une intervention
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
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width="13%" class="Libelle">&nbsp; N° NC : </td>
				<td width="15%">
					<input type="texte" id="numNC" name="numNC" size="15" value="<?php echo $row['ReferenceNC'];?>"/>
				</td>
				<td width="13%" class="Libelle">&nbsp; N° AM : </td>
				<td width="15%">
					<input type="texte" id="numAM" name="numAM" size="15" value="<?php echo $row['ReferenceAM'];?>"/>
				</td>
				<td width="13%" class="Libelle">&nbsp; N° dossier : </td>
				<td width="13%">
					<input type="texte" id="numDossier" name="numDossier" size="15" value="<?php echo $row['Reference'];?>"/>
				</td>
				<td width="15%" class="Libelle">&nbsp; N° point folio : </td>
				<td width="15%">
					<input type="texte" id="numPF" name="numPF" size="15" value="<?php echo $row['ReferencePF'];?>"/>
				</td>
			</tr>
			<tr>
				<td width="13%" class="Libelle"><?php if($row['ReferenceNC']){echo"&nbsp; Créé le :";}?></td>
				<td width="15%">
					<?php if($row['ReferenceNC']){echo AfficheDateFR($row['DateNC'])." ".$row['HeureNC'];} ?>
				</td>
				<td width="13%" class="Libelle"><?php if($row['ReferenceAM']){echo"&nbsp; Créé le :";}?></td>
				<td width="15%">
					<?php if($row['ReferenceAM']){echo AfficheDateFR($row['DateAM'])." ".$row['HeureAM'];} ?>
				</td>
				<td width="13%" class="Libelle"><?php if($row['Reference']){echo"&nbsp; Créé le :";}?></td>
				<td width="13%">
					<?php if($row['Reference']){echo AfficheDateFR($row['DateDossier'])." ".$row['HeureDossier'];} ?>
				</td>
				<td width="15%" class="Libelle"><?php if($row['ReferencePF']){echo"&nbsp; Créé le :";}?></td>
				<td width="15%">
					<?php if($row['ReferencePF']){echo AfficheDateFR($row['DatePF'])." ".$row['HeurePF'];} ?>
				</td>
			</tr>
			<tr style="display:none;">
				<td width="13%" class="Libelle"><label>&nbsp; Date réception NC : </label></td>
				<td width="15%">
					<input type="date" style="text-align:center;" id="dateNC" name="dateNC" size="10" value="<?php echo AfficheDateFR($row['DateNC']);?>">
					<input type="text" style="text-align:center;" id="heureNC" name="heureNC" size="5" value="<?php echo $row['HeureNC'];?>">
				</td>
				<td width="13%" class="Libelle"><label>&nbsp; Date création AM : </label></td>
				<td width="15%">
					<input type="date" style="text-align:center;" id="dateAM" name="dateAM" size="10" value="<?php echo AfficheDateFR($row['DateAM']);?>">
					<input type="text" style="text-align:center;" id="heureAM" name="heureAM" size="5" value="<?php echo $row['HeureAM'];?>">
				</td>
				<td width="13%" class="Libelle"><label>&nbsp; Date création dossier : </label></td>
				<td width="13%">
					<input type="date" style="text-align:center;" id="dateOF" name="dateOF" size="10" value="<?php echo AfficheDateFR($row['DateDossier']);?>">
					<input type="text" style="text-align:center;" id="heureOF" name="heureOF" size="5"  value="<?php echo $row['HeureDossier'];?>">
				</td>
				<td width="15%" class="Libelle"><label>&nbsp; Date création point folio : </label></td>
				<td width="15%">
					<input type="date" style="text-align:center;" id="datePF" name="datePF" size="10" value="<?php echo AfficheDateFR($row['DatePF']);?>">
					<input type="text" style="text-align:center;" id="heurePF" name="heurePF" size="5"  value="<?php echo $row['HeurePF'];?>">
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<?php
			/*
			//Vérification si le dossier n'existe pas déjà dans sp_olwdossier
			$req="SELECT Id,Reference FROM sp_olwdossier WHERE Id_Prestation=418 AND Id<>".$row['Id_Dossier']." ";
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
			$req="SELECT Id,ReferenceNC FROM sp_olwdossier WHERE Id_Prestation=418 AND Id<>".$row['Id_Dossier']."";
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
			$req="SELECT Id,ReferenceAM FROM sp_olwdossier WHERE Id_Prestation=418 AND Id<>".$row['Id_Dossier']."";
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
			$req="SELECT Id,ReferencePF, MSN FROM sp_olwdossier WHERE Id_Prestation=418 AND Id<>".$row['Id_Dossier']."";
			$resultBDD=mysqli_query($bdd,$req);
			$nbBDD=mysqli_num_rows($resultBDD);
			if($nbBDD>0){
				$i=0;
				while($rowRef=mysqli_fetch_array($resultBDD)){
					echo "<script>Liste_ReferencePF[".$i."] = new Array('".$rowRef['Id']."','".$rowRef['ReferencePF']."','".$rowRef['MSN']."');</script>\n";
					$i+=1;
				}
			}*/
			
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
				<td width="13%" class="Libelle">&nbsp; MSN : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
				<td width="20%">
					<input id='msn' name='msn' onKeyUp="nombre(this)" value='<?php echo $row['MSN'];?>'></td>
				</td>
				<td width='13%' class='Libelle'>&nbsp; Client : </td>
				<td width='15%'>
					<select id="client" name="client">
						<option name='0' value='0'></option>
						<?php
							$req="SELECT Id,Libelle,Supprime FROM sp_client WHERE Id_Prestation=418 ORDER BY Libelle";
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
				<td width="13%" class="Libelle">&nbsp; Type du dossier : </td>
				<td width='13%'>
					<select id="typeDossier" name="typeDossier">
						<option value=""></option>
						<option value="OF" <?php if($row['Type']=="OF"){ echo "selected";}?>>OF</option>
						<option value="OT" <?php if($row['Type']=="OT"){ echo "selected";}?>>OT</option>
						<option value="Para" <?php if($row['Type']=="Para"){ echo "selected";}?>>Para</option>
					</select>
				</td>
				<td width="15%" class="Libelle">&nbsp; Imputation : </td>
				<td width='15%'>
					<select id="imputation" name="imputation">
						<option value=""></option>
						<option value="AIRBUS" <?php if($row['Imputation']=="AIRBUS"){ echo "selected";}?>>AIRBUS</option>
						<option value="STELIA" <?php if($row['Imputation']=="STELIA"){ echo "selected";}?>>STELIA</option>
					</select>
				</td>
			</tr>
			<tr style="display:none;"><td><input id="idDossier" name="idDossier" value="<?php echo $row['Id_Dossier']; ?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="idFI" name="idFI" value="<?php echo $FI; ?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="idFILiee" name="idFILiee" value="<?php echo $row['Id_FILiee']; ?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="idStatutProdFILiee" name="idStatutProdFILiee" value="<?php echo $row['Id_StatutPRODFILiee']; ?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="dernierIC" name="dernierIC" value="<?php echo $DerniereIC; ?>"  readonly="readonly"></td></tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width="13%" class="Libelle">&nbsp; Créateur : </td>
				<td width="20%"><?php echo $row['CreateurDossier']; ?></td>
				<td width="13%" class="Libelle">&nbsp; Date de création : </td>
				<td width="20%"><?php echo $row['DateCreation']; ?></td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr style="display:none;">
				<td><input id="droit" name="droit" value="<?php echo $_SESSION['DroitSP'];?>"  readonly="readonly"></td>
			</tr>
			<tr>
				<td width='13%' class='Libelle' valign='top'>&nbsp; Priorité : <?php echo $etoile;?></td>
				<td width='15%' valign='top'>
					<select id="priorite" name="priorite" <?php echo $disabled;?>>
						<option value="1" <?php if($row['Priorite']==1){ echo "selected";}?>>1</option>
						<option value="3" <?php if($row['Priorite']==3){ echo "selected";}?>>DA</option>
						<option value="2" <?php if($row['Priorite']==2){ echo "selected";}?>>2</option>
					</select>
				</td>
				<td width='13%' class='Libelle'>&nbsp; CA/EC : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
				<td width='15%'><input id='caec' name='caec' value='<?php echo $row['Caec'];?>' size='8'></td>
				<td width='13%' class='Libelle' valign='top'>&nbsp; Compétence(s) : </td>
				<td colspan='3'>
					<table width='100%' cellpadding='0' cellspacing='0' align='left'>
						<tr>
							<td><input type="checkbox" id="Composite" name="Composite" value="Composite" <?php if($row['Composite']==1){ echo "checked";}?> <?php echo $disabled;?> >Composite &nbsp;&nbsp;</td>
							<td><input type="checkbox" id="Metal" name="Metal" value="Metal" <?php if($row['Metal']==1){ echo "checked";}?> <?php echo $disabled;?> >Metal &nbsp;&nbsp;</td>
							<td><input type="checkbox" id="Structure" name="Structure" value="Structure" <?php if($row['Structure']==1){ echo "checked";}?> <?php echo $disabled;?>>Structure &nbsp;&nbsp;</td>
							<td><input type="checkbox" id="Systeme" name="Systeme" value="Systeme" <?php if($row['Systeme']==1){ echo "checked";}?> <?php echo $disabled;?>>Systeme &nbsp;&nbsp;</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width="13%" class="Libelle">&nbsp; Section : <?php echo $etoile2; ?></td>
				<td width='15%'>
					<select id="section" name="section">
						<option value=""></option>
						<?php
							$req="SELECT Id,Libelle, Supprime FROM sp_olwsection WHERE Id_Prestation=418 ORDER BY Libelle;";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowSection=mysqli_fetch_array($result)){
									$selected="";
									if($rowSection['Libelle']==$row['MCA']){$selected="selected";}
									if($rowSection['Libelle']==$row['MCA'] || $rowSection['Supprime']==false){
										echo "<option value='".$rowSection['Libelle']."' ".$selected.">".$rowSection['Libelle']."</option>";
									}
								}
							}
						?>
					</select>
				</td>
				<td width='13%' class='Libelle'>&nbsp; Titre : <?php echo $etoile; ?></td>
				<td colspan='3'>
					<input id="titre" name="titre" value="<?php echo $row['Titre'];?>" style="width: 80%;" <?php echo $read;?>>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width='13%' class='Libelle'>&nbsp; Zone de travail : <?php echo $etoile; ?></td><td width='15%'>
					<select id='zone' name='zone' <?php echo $disabled; ?>>
						<option name='0' value='0'></option>
						<?php
							$req="SELECT Id,Libelle, Supprime FROM sp_olwzonedetravail  WHERE Id_Prestation=418 ORDER BY Libelle;";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowZone=mysqli_fetch_array($result)){
									$selected="";
									if($rowZone['Id']==$row['Id_ZoneDeTravail']){$selected="selected";}
									if($rowZone['Id']==$row['Id_ZoneDeTravail'] ||$rowZone['Supprime']==false){
										echo "<option name='".$rowZone['Id']."' value='".$rowZone['Id']."' ".$selected.">".$rowZone['Libelle']."</option>";
									}
								}
							}
						?>
					</select>
				</td>
				<td width='13%' class='Libelle'>&nbsp; Localisation : <?php echo $etoile; ?></td><td width='15%' colspan='3'>
					<input id="commentaireZI" name="commentaireZI" value="<?php echo $row['CommentaireZICIA']; ?>" style="width: 80%;" <?php echo $read;?>>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<?php
				$nbTempsDossier=0;
				$reqSum="SELECT SUM(sp_olwfi_travaileffectue.TempsPasse) AS Tps FROM sp_olwfi_travaileffectue ";
				$reqSum.="LEFT JOIN sp_olwficheintervention ON sp_olwfi_travaileffectue.Id_FI=sp_olwficheintervention.Id ";
				$reqSum.="WHERE sp_olwficheintervention.Id_Dossier=".$row['Id_Dossier'];
				$resultSum=mysqli_query($bdd,$reqSum);
				$nbSum=mysqli_num_rows($resultSum);
				if ($nbSum>0){
					$rowSum=mysqli_fetch_array($resultSum);
					if($rowSum['Tps']<>""){$nbTempsDossier=$rowSum['Tps'];}
				}
				
			?>
			<tr>
				<td width='13%' valign='top' class='Libelle'>&nbsp; Temps passé : </td>
				<td width='15%' valign='top'><input type='text' size='4' style='border:none' name='tpsDossier' id='tpsDossier' value='<?php echo $nbTempsDossier; ?>' readonly='readonly'/></td>
				<td width="13%" class="Libelle">&nbsp; Date TERC prévisionnelle : </td>
				<td width="15%">
					<input type="date" style="text-align:center;" id="dateTERCPrevisionnelle"  name="dateTERCPrevisionnelle" size="10" value="<?php if($row['DateTERCPrevisionnelle']>"0001-01-01"){echo AfficheDateFR($row['DateTERCPrevisionnelle']);} ?>">
				</td>
				<td width="13%" class="Libelle">&nbsp; Date renvoi de la NC : </td>
				<td width="13%">
					<input type="date" style="text-align:center;" id="dateRenvoiNC" name="dateRenvoiNC"  size="10" value="<?php if($row['DateRenvoiNC']>"0001-01-01"){echo AfficheDateFR($row['DateRenvoiNC']);} ?>">
				</td>
				<td colspan="2" align="left">
					<a style="text-decoration:none;" class="Bouton" href="javascript:FicheSuiveuse2('<?php echo $row['Id_Dossier'];?>','<?php echo $row['Id'];?>')">&nbsp;Fiche suiveuse vierge&nbsp;</a>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width="13%" class="Libelle">&nbsp; Fiche suiveuse : </td>
				<td width='15%' valign='top' colspan="3">
					<?php 
						if($row['FicheSuiveuse']<>"")
						{
							echo '<a class="Info" href="'.$chemin."/".$DirFichier.$row['FicheSuiveuse'].'" target="_blank"><img width="20px" src="../../../Images/Trombone.png" border="0" /></a>';
							echo '<input type="hidden" name="fichieractuel" value="'.$row['FicheSuiveuse'].'">';
							if($_SESSION["Langue"]=="EN"){
								echo '<input type="checkbox" name="SupprFichier">Delete file';
							}
							else{
								echo '<input type="checkbox" name="SupprFichier">Supprimer le fichier';
							}
						}
					?>
					<input name="fichierSuiveuse" type="file">
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
				<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;">Poste</td>
				<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;">N° IC</td>
				<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;">Statut PREPA</td>
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
				$reqHistorique = "SELECT sp_olwficheintervention.Id,sp_olwficheintervention.PosteAvionACP,sp_olwficheintervention.DateIntervention, sp_olwficheintervention.Id_StatutPROD,";
				$reqHistorique .= "sp_olwficheintervention.DateInterventionQ, sp_olwficheintervention.Id_StatutQUALITE,StatutPrepa,DatePrepa, ";
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
						$Prepa="";
						if($rowH['StatutPrepa']<>""){$Prepa=AfficheDateFr($rowH['DatePrepa'])."| ".$rowH['StatutPrepa']."<br>";}
						$reqPrepa="SELECT TypePrepa, DateDebut, DateFin FROM sp_olwfi_prepa WHERE Id_FI=".$rowH['Id']." ";
						$reqPrepa.=" AND DateDebut>'0001-01-01' ";
						$resultPrepa=mysqli_query($bdd,$reqPrepa);
						$nbResultaPrepa=mysqli_num_rows($resultPrepa);
						if($nbResultaPrepa>0){
							while($rowPrepa=mysqli_fetch_array($resultPrepa)){
								if($rowPrepa['DateFin']>'0001-01-01'){
									$Prepa.=AfficheDateFr($rowPrepa['DateFin'])." | Fin ";
								}
								else{
									$Prepa.=AfficheDateFr($rowPrepa['DateDebut'])." | Début ";
								}
								switch($rowPrepa['TypePrepa']){
									case "Enquete": $Prepa.="enquête<br>";break;
									case "CheckIQ": $Prepa.="check IQ<br>";break;
									case "Appro": $Prepa.="demande appro<br>";break;
									case "DA": $Prepa.="demande assistance<br>";break;
									case "DERO": $Prepa.="création dérogation<br>";break;
									case "MAP": $Prepa.="attente MAP<br>";break;
									case "Partenaire": $Prepa.="attente partenaire<br>";break;
									case "Acces": $Prepa.="attente accès<br>";break;
									case "IC": $Prepa.="création IC<br>";break;
								}
							}
						}
						$bordure="";
						if($nb<$nbH){
							$bordure="border-bottom:1px dotted #0077aa;";
						}
						?>
							<tr>
							<td width="6%" style="text-align:center;<?php echo $bordure;?>"><?php echo $rowH['PosteAvionACP'];?></td>
							<td width="6%" style="text-align:center;<?php echo $bordure;?>"><?php echo $rowH['NumFI'];?></td>
							<td width="20%" style="text-align:left;<?php echo $bordure;?>"><?php echo $Prepa;?></td>
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
				<td colspan="10" style="font-size:15px;font-weight:bold;color:#4d4a82;" align="center">INFORMATIONS INTERVENTION</td>
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
									$req="SELECT Id,Libelle, Supprime FROM sp_poste WHERE Id_Prestation=418 ORDER BY Libelle;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowPoste=mysqli_fetch_array($result)){
											$selected="";
											if($rowPoste['Libelle']==$row['PosteAvionACP']){$selected="selected";}
											if($rowPoste['Libelle']==$row['PosteAvionACP'] || $rowPoste['Supprime']==false){
												echo "<option value='".$rowPoste['Libelle']."' ".$selected.">".$rowPoste['Libelle']."</option>";
											}
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
								<input type="radio" id="DeroNecessaire" name="DeroNecessaire" value="1" <?php if($row['DeroNecessaire']==1){echo "checked";} ?>>Oui &nbsp;&nbsp;
								<input type="radio" id="DeroNecessaire" name="DeroNecessaire" value="0" <?php if($row['DeroNecessaire']==0){echo "checked";} ?>>Non &nbsp;&nbsp;
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td width="13%" class="Libelle">&nbsp; Besoin moyen : <br>&nbsp; spécifique </td>
							<td width="20%">
								<input type="radio" id="besoinOutillage" name="besoinOutillage" onclick="AfficherBesoin(1);" value="1" <?php if($row['outillageSpecifique']<>""){echo "checked";} ?>>Oui &nbsp;&nbsp;
								<input type="radio" id="besoinOutillage" name="besoinOutillage" onclick="AfficherBesoin(0);" value="0" <?php if($row['outillageSpecifique']==""){echo "checked";} ?>>Non &nbsp;&nbsp;
							</td>
						</tr>
						<?php
							$styleOutillage="style='display:none;'";
							if($row['outillageSpecifique']<>""){
								$styleOutillage="";
							}
						?>
						<tr class="outillage" <?php echo $styleOutillage;?>><td height="4" colspan="2"></td></tr>
						<tr class="outillage" <?php echo $styleOutillage;?>>
							<td width="20%" colspan="2">
								&nbsp;<textarea id="outillageSpecifique" name="outillageSpecifique" rows="2" cols="45" style="resize:none;" <?php echo $read;?>><?php echo stripslashes($row['outillageSpecifique']); ?></textarea>
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td width="13%" class="Libelle">&nbsp; Statut prépa : </td>
							<td width="20%">
								<select id="statutPrepa" name="statutPrepa" <?php echo $disabled;?>>
									<option value=""></option>
									<option value="A lancer PROD" <?php if($row['StatutPrepa']=="A lancer PROD"){echo "selected";} ?>>A lancer PROD</option>
									<option value="A planifier" <?php if($row['StatutPrepa']=="A planifier"){echo "selected";} ?>>A planifier</option>
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
								&nbsp;<textarea id="travailRealise" name="travailRealise" rows="3" cols="45" style="resize:none;" <?php echo $read;?>><?php echo stripslashes($row['TravailRealise']);?></textarea>
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td colspan="2" width="13%" id="LibelleCommentaire" class="Libelle" valign="center">&nbsp; Commentaire : </td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td colspan="2" width="20%">
								&nbsp;<textarea id="commentaire" name="commentaire" rows="3" cols="45" style="resize:none;" <?php echo $read;?>><?php echo stripslashes($row['Commentaire']);?></textarea>
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td colspan="2">
								<?php
									if($row['Id_FILiee']==0 || $row['Id_StatutPRODFILiee']=="RETOUR PREPA"){
								?>
								<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
									<?php
										$reqPrepa="SELECT TypePrepa, DateDebut, DateFin, HeureDebut, HeureFin, Commentaire FROM sp_olwfi_prepa WHERE Id_FI=".$row['Id']." ";
										$resultPrepa=mysqli_query($bdd,$reqPrepa);
										$nbResultaPrepa=mysqli_num_rows($resultPrepa);
										$EnqueteDebut = "<br>00:00:00";
										$EnqueteFin = "<br>00:00:00";
										$CheckIQDebut = "<br>00:00:00";
										$CheckIQFin = "<br>00:00:00";
										$AccesDebut = "<br>00:00:00";
										$AccesFin = "<br>00:00:00";
										$ApproDebut = "<br>00:00:00";
										$ApproFin = "<br>00:00:00";
										$DADebut = "<br>00:00:00";
										$DAFin = "<br>00:00:00";
										$DERODebut = "<br>00:00:00";
										$DEROFin = "<br>00:00:00";
										$MAPDebut = "<br>00:00:00";
										$MAPFin = "<br>00:00:00";
										$PartenaireDebut = "<br>00:00:00";
										$PartenaireFin = "<br>00:00:00";
										$CommentaireEnquete="";
										$CommentaireCheckIQ="";
										$CommentaireAppro="";
										$CommentaireDA="";
										$CommentaireDERO="";
										$CommentaireMAP="";
										$CommentairePartenaire="";
										$CommentaireAcces="";
										if($nbResultaPrepa>0){
											while($rowPrepa=mysqli_fetch_array($resultPrepa)){
												$btnAnnulerDebut="<a style=\"text-decoration:none;\" href=\"javascript:AnnulerDebut('".$rowPrepa['TypePrepa']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
												$btnAnnulerFin="<a style=\"text-decoration:none;\" href=\"javascript:AnnulerFin('".$rowPrepa['TypePrepa']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
												switch($rowPrepa['TypePrepa']){
													case "Enquete": 
														$EnqueteDebut=AfficheDateFr($rowPrepa['DateDebut'])."<br>".$rowPrepa['HeureDebut'].$btnAnnulerDebut;
														$EnqueteFin=AfficheDateFr($rowPrepa['DateFin'])."<br>".$rowPrepa['HeureFin'].$btnAnnulerFin;
														$CommentaireEnquete=stripslashes($rowPrepa['Commentaire']);
														break;
													case "CheckIQ":
														$CheckIQDebut=AfficheDateFr($rowPrepa['DateDebut'])."<br>".$rowPrepa['HeureDebut'].$btnAnnulerDebut;
														$CheckIQFin=AfficheDateFr($rowPrepa['DateFin'])."<br>".$rowPrepa['HeureFin'].$btnAnnulerFin;
														$CommentaireCheckIQ=stripslashes($rowPrepa['Commentaire']);
														break;
													case "Appro": 
														$ApproDebut=AfficheDateFr($rowPrepa['DateDebut'])."<br>".$rowPrepa['HeureDebut'].$btnAnnulerDebut;
														$ApproFin=AfficheDateFr($rowPrepa['DateFin'])."<br>".$rowPrepa['HeureFin'].$btnAnnulerFin;
														$CommentaireAppro=stripslashes($rowPrepa['Commentaire']);
														break;
													case "DA": 
														$DADebut=AfficheDateFr($rowPrepa['DateDebut'])."<br>".$rowPrepa['HeureDebut'].$btnAnnulerDebut;
														$DAFin=AfficheDateFr($rowPrepa['DateFin'])."<br>".$rowPrepa['HeureFin'].$btnAnnulerFin;
														$CommentaireDA=stripslashes($rowPrepa['Commentaire']);
														break;
													case "DERO":
														$DERODebut=AfficheDateFr($rowPrepa['DateDebut'])."<br>".$rowPrepa['HeureDebut'].$btnAnnulerDebut;
														$DEROFin=AfficheDateFr($rowPrepa['DateFin'])."<br>".$rowPrepa['HeureFin'].$btnAnnulerFin;
														$CommentaireDERO=stripslashes($rowPrepa['Commentaire']);
														break;
													case "MAP":
														$MAPDebut=AfficheDateFr($rowPrepa['DateDebut'])."<br>".$rowPrepa['HeureDebut'].$btnAnnulerDebut;
														$MAPFin=AfficheDateFr($rowPrepa['DateFin'])."<br>".$rowPrepa['HeureFin'].$btnAnnulerFin;
														$CommentaireMAP=stripslashes($rowPrepa['Commentaire']);
														break;
													case "Partenaire":
														$PartenaireDebut=AfficheDateFr($rowPrepa['DateDebut'])."<br>".$rowPrepa['HeureDebut'].$btnAnnulerDebut;
														$PartenaireFin=AfficheDateFr($rowPrepa['DateFin'])."<br>".$rowPrepa['HeureFin'].$btnAnnulerFin;
														$CommentairePartenaire=stripslashes($rowPrepa['Commentaire']);
														break;
													case "Acces":
														$AccesDebut=AfficheDateFr($rowPrepa['DateDebut'])."<br>".$rowPrepa['HeureDebut'].$btnAnnulerDebut;
														$AccesFin=AfficheDateFr($rowPrepa['DateFin'])."<br>".$rowPrepa['HeureFin'].$btnAnnulerFin;
														$CommentaireAcces=stripslashes($rowPrepa['Commentaire']);
														break;
												}
											}
										}
									?>
									<tr height="25">
										<td width="30%" align="center"></td>
										<td width="30%" align="center" class="Libelle">Début</td>
										<td width="30%" align="center" class="Libelle">Fin</td>
									</tr>
									<tr height="35">
										<td width="35%" bgcolor="#dadadc" class="Libelle">&nbsp; Enquête</td>
										<td width="30%" bgcolor="#dadadc" align="center" id="DebutEnquete">
											<?php
												if(substr($EnqueteDebut,0,12)=="<br>00:00:00" ){
													if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
													<a style="text-decoration:none;" href="javascript:Valider('Enquete');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a>
											<?php
													}
												}
												else{
													echo $EnqueteDebut;
												}
											?>
										</td>
										<td width="30%" bgcolor="#dadadc" align="center" id="FinEnquete">
											<?php
												if(substr($EnqueteDebut,0,12)<>"<br>00:00:00" && substr($EnqueteFin,0,12)=="<br>00:00:00"){
													if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
													<a style="text-decoration:none;" href="javascript:Terminer('Enquete');"><img id="img" src="../../../Images/Avion2.png" height="35" alt="Terminer" title="Terminer"></a>
											<?php
													}
												}
												elseif(substr($EnqueteFin,0,12)<>"<br>00:00:00"){
													echo $EnqueteFin;
												}
											?>
										</td>
									</tr>
									<tr height="48">
										<td colspan="3" bgcolor="#dadadc" valign="center" align="center">
											<textarea id="commentaireEnquete" name="commentaireEnquete" rows="1" cols="48" style="resize:none;" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>><?php echo $CommentaireEnquete ;?></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" class="Libelle">&nbsp; Check IQ</td>
										<td width="30%" align="center" id="DebutCheckIQ">
											<?php
												if(substr($CheckIQDebut,0,12)=="<br>00:00:00" ){
													if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
													<a style="text-decoration:none;" href="javascript:Valider('CheckIQ');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a>
											<?php
													}
												}
												else{
													echo $CheckIQDebut;
												}
											?>
										</td>
										<td width="30%" align="center" id="FinCheckIQ">
											<?php
												if(substr($CheckIQDebut,0,12)<>"<br>00:00:00" && substr($CheckIQFin,0,12)=="<br>00:00:00"){
													if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
													<a style="text-decoration:none;" href="javascript:Terminer('CheckIQ');"><img id="img" src="../../../Images/Avion2.png" height="35" alt="Terminer" title="Terminer"></a>
											<?php
													}
												}
												elseif(substr($CheckIQFin,0,12)<>"<br>00:00:00"){
													echo $CheckIQFin;
												}
											?>
										</td>
									</tr>
									<tr height="48">
										<td colspan="3" valign="center" align="center">
											<textarea id="commentaireCheckIQ" name="commentaireCheckIQ" rows="1" cols="48" style="resize:none;" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>><?php echo $CommentaireCheckIQ ;?></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" bgcolor="#dadadc" class="Libelle">&nbsp; Demande appro</td>
										<td width="30%" bgcolor="#dadadc" align="center" id="DebutAppro">
											<?php
												if(substr($ApproDebut,0,12)=="<br>00:00:00" ){
													if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
													<a style="text-decoration:none;" href="javascript:Valider('Appro');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a>
											<?php
													}
												}
												else{
													echo $ApproDebut;
												}
											?>
										</td>
										<td width="30%" bgcolor="#dadadc" align="center" id="FinAppro">
											<?php
												if(substr($ApproDebut,0,12)<>"<br>00:00:00" && substr($ApproFin,0,12)=="<br>00:00:00" ){
													if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
													<a style="text-decoration:none;" href="javascript:Terminer('Appro');"><img id="img" src="../../../Images/Avion2.png" height="35" alt="Terminer" title="Terminer"></a>
											<?php
													}
												}
												elseif(substr($ApproFin,0,12)<>"<br>00:00:00"){
													echo $ApproFin;
												}
											?>
										</td>
									</tr>
									<tr height="20">
										<td bgcolor="#dadadc" valign="center" align="left" class="Libelle">
											&nbsp; Date réception prévu : 
										</td>
										<td bgcolor="#dadadc" valign="center" align="left" class="Libelle">
											<input type="<?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "text";}else{echo "date";} ?>"  style="text-align:center;" id="dateAppro" name="dateAppro" size="10" value="<?php if($row['DateAppro']>'0001-01-01'){echo AfficheDateFR($row['DateAppro']);}?>" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>>
										</td>
										<td colspan="2" bgcolor="#dadadc" valign="center" align="left" class="Libelle">
											&nbsp; Type : 
											<select id="typeDA" name="typeDA" onchange="AfficherMB21();" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>>
												<option name="" value=""></option>
												<option name="Client" value="Client" <?php if($row['TypeAppro']=="Client"){echo "selected";} ?>>Client</option>
												<option name="MB21" value="MB21" <?php if($row['TypeAppro']=="MB21"){echo "selected";} ?>>MB21</option>
											</select>
										</td>
									</tr>
									<?php
										$typeAppro="style='display:none;'";
										$typeApproMB21="style='display:none;'";
										if($row['TypeAppro']=="Client"){$typeAppro="";}
										if($row['TypeAppro']=="MB21"){$typeApproMB21="";}
									?>
									<tr class="client" height="20" <?php echo $typeAppro;?>>
										<td bgcolor="#dadadc" valign="center" align="left" class="Libelle">
											&nbsp; AM liée : 
										</td>
										<td colspan="2" bgcolor="#dadadc" valign="center" align="left" class="Libelle">
											<input type="text" style="text-align:center;" id="amLiee" name="amLiee" size="10" value="<?php echo $row['AMLiee'];?>" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>>
										</td>
									</tr>
									<tr class="client" height="20" <?php echo $typeAppro;?>>
										<td bgcolor="#dadadc" valign="center" align="left" class="Libelle">
											&nbsp; OT lié :
										</td>
										<td colspan="2" bgcolor="#dadadc" valign="center" align="left" class="Libelle">
											<input type="text" style="text-align:center;" id="otLie" name="otLie" size="10" value="<?php echo $row['OTLie'];?>" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>>
										</td>
									</tr>
									<tr class="mb21" <?php echo $typeApproMB21;?>>
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
												<?php
													$listeMB21="";
													$reqMB21="SELECT NumResa, NumOT FROM sp_olwfi_mb21 WHERE Id_FI=".$FI;
													$result=mysqli_query($bdd,$reqMB21);
													$nbResulta=mysqli_num_rows($result);
													if ($nbResulta>0){
														while($rowMB21=mysqli_fetch_array($result)){
															$btn="";
															if(substr($_SESSION['DroitSP'],0,1)=='1'){
																$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerMB21('".$rowMB21['NumResa']."_".$rowMB21['NumOT']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
															}
															echo "<tr id='".$rowMB21['NumResa']."_".$rowMB21['NumOT']."'><td>".$rowMB21['NumResa']."</td><td>".$rowMB21['NumOT']."</td><td>".$btn."</td></tr>";
															$listeMB21.=$rowMB21['NumResa']."_".$rowMB21['NumOT'].";";
														}
													}
													
												?>
											</table>
										</td>
									</tr>
									<tr style="display:none;"><td><input id="mb21" name="mb21" value="<?php echo $listeMB21; ?>"  readonly="readonly"></td></tr>
									<tr height="48">
										<td colspan="3" bgcolor="#dadadc" valign="center" align="center">
											<textarea id="commentaireAppro" name="commentaireAppro" rows="1" cols="48" style="resize:none;" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>><?php echo $CommentaireAppro ;?></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" class="Libelle">&nbsp; Demande assistance</td>
										<td width="30%" align="center" id="DebutDA">
											<?php
												if(substr($DADebut,0,12)=="<br>00:00:00" ){
													if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
													<a style="text-decoration:none;" href="javascript:Valider('DA');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a>
											<?php
													}
												}
												else{
													echo $DADebut;
												}
											?>
										</td>
										<td width="30%" id="FinDA" align="center">
											<?php
												if(substr($DADebut,0,12)<>"<br>00:00:00" && substr($DAFin,0,12)=="<br>00:00:00" ){
													if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
													<a style="text-decoration:none;" href="javascript:Terminer('DA');"><img id="img" src="../../../Images/Avion2.png" height="35" alt="Terminer" title="Terminer"></a>
											<?php
													}
												}
												elseif(substr($DADebut,0,12)<>"<br>00:00:00"){
													echo $DAFin;
												}
											?>
										</td>
									</tr>
									<tr height="20">
										<td colspan="3" valign="center" align="left" class="Libelle">
											&nbsp; Date prévu : 
											<input type="<?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "text";}else{echo "date";} ?>"  style="text-align:center;" id="dateDA" name="dateDA" size="10" value="<?php if($row['DateDA']>'0001-01-01'){echo AfficheDateFR($row['DateDA']);}?>" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>>
											&nbsp;&nbsp;&nbsp; N° DA : 
											<input type="text" style="text-align:center;" id="numDA" name="numDA" size="15" value="<?php echo $row['NumDA'];?>" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>>
										</td>
									</tr>
									<tr height="48">
										<td colspan="3" valign="center" align="center">
											<textarea id="commentaireDA" name="commentaireDA" rows="1" cols="48" style="resize:none;" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>><?php echo $CommentaireDA ;?></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" bgcolor="#dadadc" class="Libelle">&nbsp; Création dérogation</td>
										<td width="30%" bgcolor="#dadadc" align="center" id="DebutDERO">
											<?php
												if(substr($DERODebut,0,12)=="<br>00:00:00" ){
													if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
													<a style="text-decoration:none;" href="javascript:Valider('DERO');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a>
											<?php
													}
												}
												else{
													echo $DERODebut;
												}
											?>
										</td>
										<td width="30%" bgcolor="#dadadc" id="FinDERO" align="center">
											<?php
												if(substr($DERODebut,0,12)<>"<br>00:00:00" && substr($DEROFin,0,12)=="<br>00:00:00" ){
													if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
													<a style="text-decoration:none;" href="javascript:Terminer('DERO');"><img id="img" src="../../../Images/Avion2.png" height="35" alt="Terminer" title="Terminer"></a>
											<?php
													}
												}
												elseif(substr($DEROFin,0,12)<>"<br>00:00:00"){
													echo $DEROFin;
												}
											?>
										</td>
									</tr>
									<tr height="20">
										<td colspan="3" bgcolor="#dadadc" valign="center" align="left" class="Libelle">
											&nbsp; N° dérogation : 
											<input type="text" style="text-align:center;" id="numDERO" name="numDERO" size="15" value="<?php echo $row['NumDERO'];?>" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>>
										</td>
									</tr>
									<tr height="48">
										<td colspan="3" bgcolor="#dadadc" valign="center" align="center">
											<textarea id="commentaireDERO" name="commentaireDERO" rows="1" cols="48" style="resize:none;" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>><?php echo $CommentaireDERO ;?></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" class="Libelle">&nbsp; Attente map</td>
										<td width="30%" align="center" id="DebutMAP">
											<?php
												if(substr($MAPDebut,0,12)=="<br>00:00:00" ){
													if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
													<a style="text-decoration:none;" href="javascript:Valider('MAP');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a>
											<?php
													}
												}
												else{
													echo $MAPDebut;
												}
											?>
										</td>
										<td width="30%" align="center" id="FinMAP">
											<?php
												if(substr($MAPDebut,0,12)<>"<br>00:00:00" && substr($MAPFin,0,12)=="<br>00:00:00" ){
													if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
													<a style="text-decoration:none;" href="javascript:Terminer('MAP');"><img id="img" src="../../../Images/Avion2.png" height="35" alt="Terminer" title="Terminer"></a>
											<?php
													}
												}
												elseif(substr($MAPFin,0,12)<>"<br>00:00:00"){
													echo $MAPFin;
												}
											?>
										</td>
									</tr>
									<tr height="48">
										<td colspan="3" valign="center" align="center">
											<textarea id="commentaireMAP" name="commentaireMAP" rows="1" cols="48" style="resize:none;" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>><?php echo $CommentaireMAP ;?></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" bgcolor="#dadadc" class="Libelle">&nbsp; Transfert partenaire</td>
										<td width="30%" bgcolor="#dadadc" align="center" id="DebutPartenaire">
											<?php
												if(substr($PartenaireDebut,0,12)=="<br>00:00:00" ){
													if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
													<a style="text-decoration:none;" href="javascript:Valider('Partenaire');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a>
											<?php
													}
												}
												else{
													echo $PartenaireDebut;
												}
											?>
										</td>
										<td width="30%" bgcolor="#dadadc" id="FinPartenaire" align="center">
											<?php
												if(substr($PartenaireDebut,0,12)<>"<br>00:00:00" && substr($PartenaireFin,0,12)=="<br>00:00:00" ){
													if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
													<a style="text-decoration:none;" href="javascript:Terminer('Partenaire');"><img id="img" src="../../../Images/Avion2.png" height="35" alt="Terminer" title="Terminer"></a>
											<?php
													}
												}
												elseif(substr($PartenaireFin,0,12)<>"<br>00:00:00"){
													echo $PartenaireFin;
												}
											?>
										</td>
									</tr>
									<tr height="48">
										<td colspan="3" bgcolor="#dadadc" valign="center" align="center">
											<textarea id="commentairePartenaire" name="commentairePartenaire" rows="1" cols="48" style="resize:none;" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>><?php echo $CommentairePartenaire ;?></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" class="Libelle">&nbsp; Attente accès</td>
										<td width="30%" align="center" id="DebutAcces">
											<?php
												if(substr($AccesDebut,0,12)=="<br>00:00:00" ){
													if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
													<a style="text-decoration:none;" href="javascript:Valider('Acces');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a>
											<?php
													}
												}
												else{
													echo $AccesDebut;
												}
											?>
										</td>
										<td width="30%" align="center" id="FinAcces">
											<?php
												if(substr($AccesDebut,0,12)<>"<br>00:00:00" && substr($AccesFin,0,12)=="<br>00:00:00" ){
													if(substr($_SESSION['DroitSP'],0,1)=='1'){
											?>
													<a style="text-decoration:none;" href="javascript:Terminer('Acces');"><img id="img" src="../../../Images/Avion2.png" height="35" alt="Terminer" title="Terminer"></a>
											<?php
													}
												}
												elseif(substr($AccesFin,0,12)<>"<br>00:00:00"){
													echo $AccesFin;
												}
											?>
										</td>
									</tr>
									<tr height="48">
										<td colspan="3" valign="center" align="center">
											<textarea id="commentaireAcces" name="commentaireAcces" rows="1" cols="48" style="resize:none;" <?php if(substr($_SESSION['DroitSP'],0,1)=='0'){echo "readonly='readonly'";} ?>><?php echo $CommentaireAcces ;?></textarea>
										</td>
									</tr>
									<tr style="display:none;">
										<td>
											<input type="text" style="text-align:center;" id="dateDebutEnquete" name="dateDebutEnquete" size="15" value="<?php if(substr($EnqueteDebut,0,4)<>"<br>"){echo TrsfDate_(substr($EnqueteDebut,0,10));}?>">
											<input type="text" style="text-align:center;" id="dateFinEnquete" name="dateFinEnquete" size="15" value="<?php if(substr($EnqueteFin,0,4)<>"<br>"){echo TrsfDate_(substr($EnqueteFin,0,10));}?>">
											<input type="text" style="text-align:center;" id="heureDebutEnquete" name="heureDebutEnquete" size="15" value="<?php if(substr($EnqueteDebut,0,4)<>"<br>"){echo substr($EnqueteDebut,14);}?>">
											<input type="text" style="text-align:center;" id="heureFinEnquete" name="heureFinEnquete" size="15" value="<?php if(substr($EnqueteFin,0,4)<>"<br>"){echo substr($EnqueteFin,14);}?>">
											
											<input type="text" style="text-align:center;" id="dateDebutCheckIQ" name="dateDebutCheckIQ" size="15" value="<?php if(substr($CheckIQDebut,0,4)<>"<br>"){echo TrsfDate_(substr($CheckIQDebut,0,10));}?>">
											<input type="text" style="text-align:center;" id="dateFinCheckIQ" name="dateFinCheckIQ" size="15" value="<?php if(substr($CheckIQFin,0,4)<>"<br>"){echo TrsfDate_(substr($CheckIQFin,0,10));}?>">
											<input type="text" style="text-align:center;" id="heureDebutCheckIQ" name="heureDebutCheckIQ" size="15" value="<?php if(substr($CheckIQDebut,0,4)<>"<br>"){echo substr($CheckIQDebut,14);}?>">
											<input type="text" style="text-align:center;" id="heureFinCheckIQ" name="heureFinCheckIQ" size="15" value="<?php if(substr($CheckIQFin,0,4)<>"<br>"){echo substr($CheckIQFin,14);}?>">
											
											<input type="text" style="text-align:center;" id="dateDebutAppro" name="dateDebutAppro" size="15" value="<?php if(substr($ApproDebut,0,4)<>"<br>"){echo TrsfDate_(substr($ApproDebut,0,10));}?>">
											<input type="text" style="text-align:center;" id="dateFinAppro" name="dateFinAppro" size="15" value="<?php if(substr($ApproFin,0,4)<>"<br>"){echo TrsfDate_(substr($ApproFin,0,10));}?>">
											<input type="text" style="text-align:center;" id="heureDebutAppro" name="heureDebutAppro" size="15" value="<?php if(substr($ApproDebut,0,4)<>"<br>"){echo substr($ApproDebut,14);}?>">
											<input type="text" style="text-align:center;" id="heureFinAppro" name="heureFinAppro" size="15" value="<?php if(substr($ApproFin,0,4)<>"<br>"){echo substr($ApproFin,14);}?>">
											
											<input type="text" style="text-align:center;" id="dateDebutDA" name="dateDebutDA" size="15" value="<?php if(substr($DADebut,0,4)<>"<br>"){echo TrsfDate_(substr($DADebut,0,10));}?>">
											<input type="text" style="text-align:center;" id="dateFinDA" name="dateFinDA" size="15" value="<?php if(substr($DAFin,0,4)<>"<br>"){echo TrsfDate_(substr($DAFin,0,10));}?>">
											<input type="text" style="text-align:center;" id="heureDebutDA" name="heureDebutDA" size="15" value="<?php if(substr($DADebut,0,4)<>"<br>"){echo substr($DADebut,14);}?>">
											<input type="text" style="text-align:center;" id="heureFinDA" name="heureFinDA" size="15" value="<?php if(substr($DAFin,0,4)<>"<br>"){echo substr($DAFin,14);}?>">
											
											<input type="text" style="text-align:center;" id="dateDebutDERO" name="dateDebutDERO" size="15" value="<?php if(substr($DERODebut,0,4)<>"<br>"){echo TrsfDate_(substr($DERODebut,0,10));}?>">
											<input type="text" style="text-align:center;" id="dateFinDERO" name="dateFinDERO" size="15" value="<?php if(substr($DEROFin,0,4)<>"<br>"){echo TrsfDate_(substr($DEROFin,0,10));}?>">
											<input type="text" style="text-align:center;" id="heureDebutDERO" name="heureDebutDERO" size="15" value="<?php if(substr($DERODebut,0,4)<>"<br>"){echo substr($DERODebut,14);}?>">
											<input type="text" style="text-align:center;" id="heureFinDERO" name="heureFinDERO" size="15" value="<?php if(substr($DEROFin,0,4)<>"<br>"){echo substr($DEROFin,14);}?>">
											
											<input type="text" style="text-align:center;" id="dateDebutMAP" name="dateDebutMAP" size="15" value="<?php if(substr($MAPDebut,0,4)<>"<br>"){echo TrsfDate_(substr($MAPDebut,0,10));}?>">
											<input type="text" style="text-align:center;" id="dateFinMAP" name="dateFinMAP" size="15" value="<?php if(substr($MAPFin,0,4)<>"<br>"){echo TrsfDate_(substr($MAPFin,0,10));}?>">
											<input type="text" style="text-align:center;" id="heureDebutMAP" name="heureDebutMAP" size="15" value="<?php if(substr($MAPDebut,0,4)<>"<br>"){echo substr($MAPDebut,14);}?>">
											<input type="text" style="text-align:center;" id="heureFinMAP" name="heureFinMAP" size="15" value="<?php if(substr($MAPFin,0,4)<>"<br>"){echo substr($MAPFin,14);}?>">
											
											<input type="text" style="text-align:center;" id="dateDebutPartenaire" name="dateDebutPartenaire" size="15" value="<?php if(substr($PartenaireDebut,0,4)<>"<br>"){echo TrsfDate_(substr($PartenaireDebut,0,10));}?>">
											<input type="text" style="text-align:center;" id="dateFinPartenaire" name="dateFinPartenaire" size="15" value="<?php if(substr($PartenaireFin,0,4)<>"<br>"){echo TrsfDate_(substr($PartenaireFin,0,10));}?>">
											<input type="text" style="text-align:center;" id="heureDebutPartenaire" name="heureDebutPartenaire" size="15" value="<?php if(substr($PartenaireDebut,0,4)<>"<br>"){echo substr($PartenaireDebut,14);}?>">
											<input type="text" style="text-align:center;" id="heureFinPartenaire" name="heureFinPartenaire" size="15" value="<?php if(substr($PartenaireFin,0,4)<>"<br>"){echo substr($PartenaireFin,14);}?>">
											
											<input type="text" style="text-align:center;" id="dateDebutAcces" name="dateDebutAcces" size="15" value="<?php if(substr($AccesDebut,0,4)<>"<br>"){echo TrsfDate_(substr($AccesDebut,0,10));}?>">
											<input type="text" style="text-align:center;" id="dateFinAcces" name="dateFinAcces" size="15" value="<?php if(substr($AccesFin,0,4)<>"<br>"){echo TrsfDate_(substr($AccesFin,0,10));}?>">
											<input type="text" style="text-align:center;" id="heureDebutAcces" name="heureDebutAcces" size="15" value="<?php if(substr($AccesDebut,0,4)<>"<br>"){echo substr($AccesDebut,14);}?>">
											<input type="text" style="text-align:center;" id="heureFinAcces" name="heureFinAcces" size="15" value="<?php if(substr($AccesFin,0,4)<>"<br>"){echo substr($AccesFin,14);}?>">
										</td>
									</tr>
								</table>
								<?php
									}
								?>
							</td>
						</tr>
						<tr><td height="4" colspan="2" ></td></tr>
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
								<input <?php if(substr($_SESSION['DroitSP'],0,1)=='0' && substr($_SESSION['DroitSP'],1,1)=='0'){echo "type='texte'";}else{echo "type='date'";}?> style="text-align:center;" id="dateIntervention" name="dateIntervention"  size="15" value="<?php echo AfficheDateFR($row['DateIntervention']);?>" <?php echo  $readSTCE;?>>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width="13%" class="Libelle">&nbsp; Vacation : </td>
							<td bgcolor="#dbeef9" width="20%" colspan="3">
								<select id="vacation" name="vacation" <?php echo $disabledSTCE;?>>
									<option name="" value=""></option>
									<option name="J" value="J" <?php if($row['Vacation']=="J"){echo "selected";} ?>>Jour</option>
									<option name="S" value="S" <?php if($row['Vacation']=="S"){echo "selected";} ?>>Soir</option>
									<option name="N" value="N" <?php if($row['Vacation']=="N"){echo "selected";} ?>>Nuit</option>
									<option name="VSD Jour" value="VSD Jour" <?php if($row['Vacation']=="VSD Jour"){echo "selected";} ?>>VSD Jour</option>
									<option name="VSD Nuit" value="VSD Nuit" <?php if($row['Vacation']=="VSD Nuit"){echo "selected";} ?>>VSD Nuit</option>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
							<td bgcolor="#dbeef9" width="13%" class="Libelle">&nbsp; N° IC : </td>
							<td bgcolor="#dbeef9" width="20%" colspan="3">
								<input type="text" style="text-align:center;" id="numIC" name="numIC" size="15" value="<?php echo $row['NumFI'];?>"  <?php if(substr($_SESSION['DroitSP'],0,1)=='0' && substr($_SESSION['DroitSP'],1,1)=='0'){echo "readonly='readonly'";} ?>>
							</td>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width="13%" class="Libelle">&nbsp;Temps objectif : </td>
							<td bgcolor="#dbeef9" width="20%" colspan="3">
								<input onKeyUp="nombre(this)" id="tempsObjectif" <?php echo $readSTCE;?> name="tempsObjectif" size="5" type="text" value="<?php echo $row['TempsObjectif']; ?>">
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width='13%' class="Libelle" valign="top">
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
									<tr><td class="Libelle" width="70%">Personne</td><td class="Libelle">Nb. heures</td>
									<?php
											$listeCompagnon="";
											$TempsPasseTotalFI=0;
											$req="SELECT Id, Id_Personne, TempsPasse,";
											$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwfi_travaileffectue.Id_Personne) AS NomPrenom ";
											$req.="FROM sp_olwfi_travaileffectue WHERE Id_FI=".$FI." ORDER BY NomPrenom;";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											if ($nbResulta>0){
												while($rowCompagnon=mysqli_fetch_array($result)){
													$btn="";
													if(substr($_SESSION['DroitSP'],1,1)=='1'){
														$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerTE('".$rowCompagnon['Id_Personne']."C_".$rowCompagnon['TempsPasse']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
													}
													echo "<tr id='".$rowCompagnon['Id_Personne']."C_".$rowCompagnon['TempsPasse']."'><td>".$rowCompagnon['NomPrenom']."</td><td>".$rowCompagnon['TempsPasse']."</td><td>".$btn."</td></tr>";
													$listeCompagnon.=$rowCompagnon['Id_Personne']."C_".$rowCompagnon['TempsPasse'].";";
													$TempsPasseTotalFI+=$rowCompagnon['TempsPasse'];
												}
											}
										?>
									</tr>
								</table>
								<table id="tab_TravailEffectue" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle" width="70%">TOTAL</td><td class="Libelle">
										<input type="text" size="4" style="background-color:#dbeef9;border:none" name="tpsFI" id="tpsFI" value="<?php echo $TempsPasseTotalFI;?>" readonly="readonly"/>
									</td></tr>
								</table>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9">&nbsp; Statut PROD : </td>
							<td width="20%" bgcolor="#dbeef9">
								<?php 
									$disabledNew = $disabledCE;
									$disabledIQNew = $disabledIQ;
									if($DerniereIC==false){
										$disabledNew = "disabled='disabled'";
										$disabledIQNew = "disabled='disabled'";
									}
								?>
								<select id="statutProd" name="statutProd" onchange="Recharge_StatutProd();" <?php echo $disabledNew;?>>
									<option name="" value=""></option>
									<?php
									
									$reqTERA="SELECT Id FROM sp_olwficheintervention WHERE Id_Dossier=".$row['Id_Dossier']." AND Id<>".$FI." AND Id_StatutPROD='TERA'";
									$resultTERA=mysqli_query($bdd,$reqTERA);
									$nbTERA=mysqli_num_rows($resultTERA);
									
									if($nbTERA>0 && $disabledNew=="disabled='disabled'"){
										$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation=418 AND TypeStatut='P' ORDER BY Id;";
									}
									elseif($nbTERA>0 && $disabledNew<>"disabled='disabled'"){
										$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation=418 AND TypeStatut='P' AND Id<>'TERA' ORDER BY Id;";
									}
									else{
										$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation=418 AND TypeStatut='P' AND Id<>'REWORK' ORDER BY Id;";
									}
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowStatut=mysqli_fetch_array($result)){
											$selected = "";
											if($row['Id_StatutPROD']==$rowStatut['Id']){$selected="selected";}
											echo "<option name='".$rowStatut['Id']."' value='".$rowStatut['Id']."' ".$selected.">".$rowStatut['Id']."</option>";
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
								<input <?php if(substr($_SESSION['DroitSP'],0,1)=='0' && substr($_SESSION['DroitSP'],1,1)=='0'){echo "type='texte'";}else{echo "type='date'";}?> style="text-align:center;" id="dateTERA" name="dateTERA" size="15" value="<?php echo AfficheDateFR($row['DateTERA']); ?>" <?php echo  $readSTCE;?>>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9">&nbsp; Retour PROD : <a style="text-decoration:none;" href="javascript:OuvreDef();"><img id="img" src="../../../Images/aide2.gif" alt="aide" title="aide"></a></td>
							<td width="20%" bgcolor="#dbeef9">
								<div id='retourP'>
								<select id="retourProd" name="retourProd" <?php echo $disabledNew;?>>
									<option name="0" value="0"></option>
									<?php
									$req="SELECT Id, Libelle, Id_Statut, Supprime FROM sp_olwretour WHERE Id_Prestation=418 ORDER BY Libelle;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										$i=0;
										while($rowRetour=mysqli_fetch_array($result)){
											echo "<script>Liste_Retour[".$i."] = new Array(\"".$rowRetour['Id']."\",\"".$rowRetour['Libelle']."\",\"".$rowRetour['Id_Statut']."\",\"".$rowRetour['Supprime']."\");</script>\n";
											if($row['Id_StatutPROD']==$rowRetour['Id_Statut']){
												$selected = "";
												if($row['Id_RetourPROD']==$rowRetour['Id']){$selected="selected";}
												if($row['Id_RetourPROD']==$rowRetour['Id'] || $rowRetour['Supprime']==0){
													echo "<option name='".$rowRetour['Id']."' value='".$rowRetour['Id']."' ".$selected.">".$rowRetour['Libelle']."</option>";
												}
											}
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
								<select id="avancementProd" name="avancementProd" <?php echo $disabledNew;?>>
									<?php
										if($row['Id_StatutPROD']=="TFS"){
											for($i=0;$i<=100;$i=$i+5){
												$selected="";
												if($i==$row['Avancement']){$selected="selected";}
												echo "<option name='".$i."' value='".$i."' ".$selected.">".$i."</option>";
											}
										}
										else{
											echo "<option name='0' value='0' selected></option>";
										}
									?>
								</select>
								</div>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9" valign="center">&nbsp; Commentaire PROD : </td>
							<td width="20%" bgcolor="#dbeef9">
								<textarea id="commentairePROD" name="commentairePROD" rows="5" cols="40" style="resize:none;" <?php echo $readCE;?>><?php echo stripslashes($row['CommentairePROD']); ?></textarea>
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
								<input <?php if(substr($_SESSION['DroitSP'],4,1)=='0'){echo "type='texte'";}else{echo "type='date'";}?> style="text-align:center;" id="dateInterventionQ" name="dateInterventionQ"  size="15" value="<?php echo AfficheDateFR($row['DateInterventionQ']);?>" <?php echo  $readIQ;?>>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td bgcolor="#c7e048" width="13%" class="Libelle">&nbsp; Vacation : </td>
							<td bgcolor="#c7e048" width="20%" colspan="3">
								<select id="vacationQ" name="vacationQ" <?php echo $disabledIQ;?>>
									<option name="" value=""></option>
									<option name="J" value="J"  <?php if($row['VacationQ']=="J"){echo "selected";} ?>>Jour</option>
									<option name="S" value="S"  <?php if($row['VacationQ']=="S"){echo "selected";} ?>>Soir</option>
									<option name="N" value="N"  <?php if($row['VacationQ']=="N"){echo "selected";} ?>>Nuit</option>
									<option name="VSD Jour" value="VSD Jour"  <?php if($row['VacationQ']=="VSD Jour"){echo "selected";} ?>>VSD Jour</option>
									<option name="VSD Nuit" value="VSD Nuit"  <?php if($row['VacationQ']=="VSD Nuit"){echo "selected";} ?>>VSD Nuit</option>
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
											if($row['Id_QUALITE'] <> 0){
												if($row['Id_QUALITE'] == $rowIQ['Id']){$selected="selected";}
											}
											else{
												if(substr($_SESSION['DroitSP'],4,1)=='1' && $_SESSION['Id_PersonneSP'] == $rowIQ['Id']){$selected="selected";}
											}
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
									<select id="statutQualite" name="statutQualite" onchange="Recharge_StatutQualite();" <?php echo $disabledIQNew;?>>
										<option name="" value=""></option>
										<?php
										$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation=418 AND TypeStatut='Q' ORDER BY Id;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											$i=0;
											while($rowStatut=mysqli_fetch_array($result)){
												echo "<script>Liste_Statut[".$i."] = new Array('".$rowStatut['Id']."');</script>\n";
												if($row['Id_StatutPROD']=="TERA" || $row['Id_StatutPROD']=="REWORK"){
													$selected = "";
													if($row['Id_StatutQUALITE']==$rowStatut['Id']){$selected="selected";}
													echo "<option name='".$rowStatut['Id']."' value='".$rowStatut['Id']."' ".$selected.">".$rowStatut['Id']."</option>";
												}
												elseif($row['Id_StatutPROD']=="TFS"){
													if($rowStatut['Id']=="TVS"){
														$selected = "";
														if($row['Id_StatutQUALITE']==$rowStatut['Id']){$selected="selected";}
														echo "<option name='".$rowStatut['Id']."' value='".$rowStatut['Id']."' ".$selected.">".$rowStatut['Id']."</option>";
													}
												}
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
								<input <?php if(substr($_SESSION['DroitSP'],4,1)=='0'){echo "type='texte'";}else{echo "type='date'";}?> style="text-align:center;" id="dateTERC" name="dateTERC" size="15" value="<?php echo AfficheDateFR($row['DateTERC']); ?>" <?php echo  $readIQ;?>>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td width="26%" class="Libelle" bgcolor="#c7e048">&nbsp; Retour QUALITE : <a style="text-decoration:none;" href="javascript:OuvreDef();"><img id="img" src="../../../Images/aide2.gif" alt="aide" title="aide"></a></td>
							<td width="20%" bgcolor="#c7e048">
								<div id='retourQ'>
								<select id="retourQualite" name="retourQualite" <?php echo $disabledIQNew;?>>
									<option name="0" value="0"></option>
									<?php
										$req="SELECT Id, Libelle, Id_Statut, Supprime FROM sp_olwretour ORDER BY Libelle;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($rowRetour=mysqli_fetch_array($result)){
												if($row['Id_StatutQUALITE']==$rowRetour['Id_Statut']){
													$selected = "";
													if($row['Id_RetourQUALITE']==$rowRetour['Id']){$selected="selected";}
													if($row['Id_RetourQUALITE']==$rowRetour['Id'] || $rowRetour['Supprime']==0){
														echo "<option name='".$rowRetour['Id']."' value='".$rowRetour['Id']."' ".$selected.">".$rowRetour['Libelle']."</option>";
													}
												}
											}
										}
									?>
								</select>
								</div>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#c7e048" valign="center">&nbsp; Commentaire QUALITE : </td>
							<td width="20%" bgcolor="#c7e048">
								<textarea id="commentaireQualite" name="commentaireQualite" rows="5" cols="40" style="resize:none;" <?php echo $readIQ;?>><?php echo stripslashes($row['CommentaireQUALITE']); ?></textarea>
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
										IF(Id_ECME>0,sp_olwecme.Libelle,sp_olwfi_ecme.ECME) AS Libelle,
										IF(Id_ECME>0,sp_olwecme.Id_Type,sp_olwfi_ecme.Id_TypeECME) AS Id_Type,
										IF(Id_ECME>0,(SELECT Libelle FROM sp_olwtypeecme WHERE sp_olwtypeecme.Id=sp_olwecme.Id_Type),
										(SELECT Libelle FROM sp_olwtypeecme WHERE sp_olwtypeecme.Id=sp_olwfi_ecme.Id_TypeECME)) AS Type
										FROM sp_olwfi_ecme 
										LEFT JOIN sp_olwecme 
										ON sp_olwfi_ecme.Id_ECME=sp_olwecme.Id 
										WHERE sp_olwfi_ecme.ProdQualite=0 
										AND Id_FI=".$FI." ORDER BY Libelle;";
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
										IF(Id_ECME>0,sp_olwecme.Libelle,sp_olwfi_ecme.ECME) AS Libelle,
										IF(Id_ECME>0,sp_olwecme.Id_Type,sp_olwfi_ecme.Id_TypeECME) AS Id_Type,
										IF(Id_ECME>0,(SELECT Libelle FROM sp_olwtypeecme WHERE sp_olwtypeecme.Id=sp_olwecme.Id_Type),
										(SELECT Libelle FROM sp_olwtypeecme WHERE sp_olwtypeecme.Id=sp_olwfi_ecme.Id_TypeECME)) AS Type
										FROM sp_olwfi_ecme 
										LEFT JOIN sp_olwecme 
										ON sp_olwfi_ecme.Id_ECME=sp_olwecme.Id 
										WHERE sp_olwfi_ecme.ProdQualite=1 
										AND Id_FI=".$FI." ORDER BY Libelle;";
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
										FROM sp_olwfi_ecmeclient 
										WHERE Id_FI=".$FI." ORDER BY NumClient;";
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
										<td bgcolor="#e4e7f0">&nbsp;Coeff Hygrométrique : </td>
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
									<tr><td class="Libelle">Ingrédient</td><td class="Libelle">N° lot</td><td class="Libelle">Date<br>péremption</td><td class="Libelle">CoeffHydrometrique.<br>hygrométrique</td><td class="Libelle">Température</td></tr>
									<?php
										$listeProduit="";
										$req="SELECT Id_Ingredient,NumLot,DatePeremption,CoeffHydrometrique,Temperature,";
										$req.="IF(Id_Ingredient>0,(SELECT sp_olwingredient.Libelle FROM sp_olwingredient WHERE sp_olwingredient.Id=sp_olwfi_ingredient.Id_Ingredient),sp_olwfi_ingredient.Ingredient) AS Produit ";
										$req.="FROM sp_olwfi_ingredient WHERE Id_FI=".$FI." ORDER BY Produit;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($rowIngredient=mysqli_fetch_array($result)){
												$btn="";
												if($rowIngredient['Id_Ingredient']>0){
													if(substr($_SESSION['DroitSP'],1,1)=='1'){
														$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerProduit('".$rowIngredient['Id_Ingredient']."_".$rowIngredient['NumLot']."_".AfficheDateFR($rowIngredient['DatePeremption'])."_".$rowIngredient['CoeffHydrometrique']."_".$rowIngredient['Temperature']."ING_ING')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
													}
													$listeProduit.=$rowIngredient['Id_Ingredient']."_".$rowIngredient['NumLot']."_".AfficheDateFR($rowIngredient['DatePeremption'])."_".$rowIngredient['CoeffHydrometrique']."_".$rowIngredient['Temperature']."ING_ING;";
													echo "<tr id='".$rowIngredient['Id_Ingredient']."_".$rowIngredient['NumLot']."_".AfficheDateFR($rowIngredient['DatePeremption'])."_".$rowIngredient['CoeffHydrometrique']."_".$rowIngredient['Temperature']."ING_ING'>";
												}
												else{
													if(substr($_SESSION['DroitSP'],1,1)=='1'){
														$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerProduit('0ING_ING".stripslashes($rowIngredient['Produit'])."_".$rowIngredient['NumLot']."_".AfficheDateFR($rowIngredient['DatePeremption'])."_".$rowIngredient['CoeffHydrometrique']."_".$rowIngredient['Temperature']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
													}
													$listeProduit.="0ING_ING".stripslashes($rowIngredient['Produit'])."_".$rowIngredient['NumLot']."_".AfficheDateFR($rowIngredient['DatePeremption'])."_".$rowIngredient['CoeffHydrometrique']."_".$rowIngredient['Temperature'].";";
													echo "<tr id='0ING_ING".stripslashes($rowIngredient['Produit'])."_".$rowIngredient['NumLot']."_".AfficheDateFR($rowIngredient['DatePeremption'])."_".$rowIngredient['CoeffHydrometrique']."_".$rowIngredient['Temperature']."'>";
												}
												echo "<td>".$rowIngredient['Produit']."</td><td>".$rowIngredient['NumLot']."</td>";
												echo "<td>".AfficheDateFR($rowIngredient['DatePeremption'])."</td><td>".$rowIngredient['CoeffHydrometrique']."</td><td>".$rowIngredient['Temperature']."</td><td>".$btn."</td></tr>";
												
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
								 
								<input <?php echo $modifiableCE; ?> style="text-align:left;" id="ValidationPSCE" name="ValidationPSCE" type="checkbox" value="ValidationPSCE" <?php if($row['ValidationPSCE']==1){echo "checked";} ?>> Validation Chef d'équipe
								&nbsp; &nbsp; &nbsp; <input <?php echo $modificationIQ; ?> style="text-align:left;" id="ValidationPSIQ" name="ValidationPSIQ" type="checkbox" value="ValidationPSIQ" <?php if($row['ValidationPSIQ']==1){echo "checked";} ?>> Validation Qualité
							</td>
						</tr>
						<tr><td height="8" colspan="2" bgcolor="#dbeef9"></td></tr>
					</table>
				</td>
			</tr>
			<tr style="display:none;"><td><input id="travailEffectue" name="travailEffectue" value="<?php echo $listeCompagnon;?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="ECMEPROD" name="ECMEPROD" value="<?php echo $listeECMEPROD;?>" readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="ECMEQUALITE" name="ECMEQUALITE" value="<?php echo $listeECMEQUALITE;?>" readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="ECMECLIENT" name="ECMECLIENT" value="<?php echo $listeECMECLIENT;?>" readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="Produit" name="Produit" value="<?php echo $listeProduit;?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id='lesAIPI' name='lesAIPI' value="<?php echo $listeAIPI;?>"  readonly='readonly'></td></tr>
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
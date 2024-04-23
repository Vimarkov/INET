<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="Dossier.js?t=<?php echo time(); ?>"></script>
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
		$req.="CommentaireZICIA='".addslashes($_POST['commentaireZI'])."' ";
		$req.="WHERE Id=".$_POST['idDossier'];
		$resultUpdate=mysqli_query($bdd,$req);

		//AJOUTER UNE FICHE D'INTERVENTION
		$req="INSERT INTO sp_olwficheintervention (";
		$req.="Id_Dossier,Id_FILiee,Id_Createur,DateCreation,PosteAvionACP,DeroNecessaire,TravailRealise,Commentaire,DateAppro,DateDA,NumDERO,NumDA,OutillageSpecifique,TypeAppro,AMLiee,OTLie,StatutPrepa,DatePrepa";
		if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1'){
			$req.=",DateIntervention,Id_SiteIntervention,Vacation,NumFI,TempsObjectif";
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
		$req.=$_POST['idDossier'].",".$_POST['idFI'].",".$_SESSION['Id_PersonneSP'].",'".$DateJour."','".addslashes($_POST['poste'])."',".$_POST['DeroNecessaire'].",'".addslashes($_POST['travailRealise'])."','".addslashes($_POST['commentaire'])."'";
		$req.=",'".TrsfDate_($_POST['dateAppro'])."','".TrsfDate_($_POST['dateDA'])."','".$_POST['numDERO']."','".$_POST['numDA']."','".$outillage."','".$_POST['typeDA']."','".$_POST['amLiee']."','".$_POST['otLie']."'";
		if($_POST['statutPrepa']<>""){
			$req.=",'".$_POST['statutPrepa']."','".$DateJour."'";
		}
		else{
			$req.=",'','0001-01-01'";
		}
		if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1'){
			$req.=",";			
			$temps=0;
			if($_POST['tempsObjectif']<>""){$temps=$_POST['tempsObjectif'];}
			$req.= "'".TrsfDate_($_POST['dateIntervention'])."',".$_POST['siteIntervention'].",'".$_POST['vacation']."','".$_POST['numIC']."',".$temps."";
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
			if($_POST['Id_StatutPROD']=="RETOUR PREPA"){
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
					$req.="(".$IdFICree.",-15,'".$type."','".$DateDebut."','".$HeureDebut."','".$DateFin."','".$HeureFin."','".addslashes($_POST['commentaire'.$type])."')";
					$resultAjout=mysqli_query($bdd,$req);
				}
			}
		}
		
		if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],0,1)=='1'){
			//Ajout des AIPI/AIPS
			$tabAIPI = explode(";",$_POST['lesAIPI']);
			foreach($tabAIPI as $valeur){
				 if($valeur<>""){
					$req="INSERT INTO sp_olwfi_aipi (Id_FI,Id_Qualification) VALUES (".$IdFICree.",".$valeur.")";
					$resultAjour=mysqli_query($bdd,$req);
				 }
			}
		}
		
		if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
			
			//Ajout des ECME
			$tabECME = explode(";",$_POST['ECME']);
			foreach($tabECME as $valeur){
				 if($valeur<>""){
					$tab2 = explode("_",$valeur);
					$req="INSERT INTO sp_olwfi_ecme (Id_FI,ECME,DateEtalonnage) VALUES (".$IdFICree.",'".$tab2[0]."','".TrsfDate_($tab2[1])."')";
					$resultAjour=mysqli_query($bdd,$req);
				 }
			}
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
				
				//Ajout des ingrédients
				$tabIngredient = explode(";",$_POST['Ingredient']);
				foreach($tabIngredient as $valeur){
					 if($valeur<>""){
						$tab2 = explode("_",$valeur);
						$req="INSERT INTO sp_olwfi_ingredient (Id_FI,Id_Ingredient,NumLot,DatePeremption,CoeffHydrometrique) VALUES (";
						$req.=$IdFICree.",".$tab2[0].",'".$tab2[1]."','".TrsfDate_($tab2[2])."','".$tab2[3]."')";
						$resultAjour=mysqli_query($bdd,$req);
					 }
				}
			}
		}
		
		
		if(substr($_SESSION['DroitSP'],0,1)=='1'){
			echo "<script>GenererFicheSuiveuse(".$_POST['idDossier'].",".$IdFICree.")</script>";
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
if($_GET){
	$FI=$_GET['Id'];
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
	$req.="sp_olwficheintervention.Id_StatutPROD,sp_olwficheintervention.Id_RetourPROD,sp_olwficheintervention.Id_StatutQUALITE,sp_olwficheintervention.Id_RetourQUALITE,sp_olwficheintervention.PosteAvionACP,sp_olwficheintervention.Commentaire,sp_olwficheintervention.DateTERA,sp_olwficheintervention.DateTERC ";
	$req.="FROM sp_olwficheintervention LEFT JOIN sp_olwdossier ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id ";
	$req.="WHERE sp_olwficheintervention.Id=".$FI;
	$result=mysqli_query($bdd,$req);
	$row=mysqli_fetch_array($result);
}
?>
<form id="formulaire" class="test" method="POST" enctype="multipart/form-data" action="Dupliquer_Dossier.php" onSubmit="return VerifChamps(<?php echo substr($_SESSION['DroitSP'],0,1);?>);">
<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
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
				<td width="15%">
					<input type="texte" id="numDossier" name="numDossier" size="15" value="<?php echo $row['Reference'];?>"/>
				</td>
				<td width="13%" class="Libelle">&nbsp; N° point folio : </td>
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
				<td width="15%">
					<?php if($row['Reference']){echo AfficheDateFR($row['DateDossier'])." ".$row['HeureDossier'];} ?>
				</td>
				<td width="13%" class="Libelle"><?php if($row['ReferencePF']){echo"&nbsp; Créé le :";}?></td>
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
				<td width="15%">
					<input type="date" style="text-align:center;" id="dateOF" name="dateOF" size="10" value="<?php echo AfficheDateFR($row['DateDossier']);?>">
					<input type="text" style="text-align:center;" id="heureOF" name="heureOF" size="5"  value="<?php echo $row['HeureDossier'];?>">
				</td>
				<td width="13%" class="Libelle"><label>&nbsp; Date création point folio : </label></td>
				<td width="15%">
					<input type="date" style="text-align:center;" id="datePF" name="datePF" size="10" value="<?php echo AfficheDateFR($row['DatePF']);?>">
					<input type="text" style="text-align:center;" id="heurePF" name="heurePF" size="5"  value="<?php echo $row['HeurePF'];?>">
				</td>
			</tr>
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
				<td width="13%" class="Libelle">&nbsp; MSN : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
				<td width="20%">
					<input id='msn' name='msn' onKeyUp="nombre(this)" value='<?php echo $row['MSN'];?>'></td>
				</td>
				<td width='13%' class='Libelle'>&nbsp; Client : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
				<td width='15%'>
					<select id="client" name="client">
						<option name='0' value='0'></option>
						<?php
							$req="SELECT Id,Libelle,Supprime FROM sp_client WHERE Id_Prestation=-15 ORDER BY Libelle";
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
				<td width='15%'>
					<select id="typeDossier" name="typeDossier">
						<option value=""></option>
						<option value="OF" <?php if($row['Type']=="OF"){ echo "selected";}?>>OF</option>
						<option value="OT" <?php if($row['Type']=="OT"){ echo "selected";}?>>OT</option>
						<option value="Para" <?php if($row['Type']=="Para"){ echo "selected";}?>>Para</option>
					</select>
				</td>
				<td width="13%" class="Libelle">&nbsp; Imputation : </td>
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
			<tr style="display:none;"><td><input id="idDossier" name="Id_StatutPROD" value="<?php echo $row['Id_StatutPROD']; ?>"  readonly="readonly"></td></tr>
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
				<td width='25%' colspan='3'>
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
							$req="SELECT Id,Libelle, Supprime FROM sp_olwsection WHERE Id_Prestation=-15 ORDER BY Libelle;";
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
				<td width='15%' colspan='3'>
					<input id="titre" name="titre" value="<?php echo $row['Titre'];?>" style="width: 80%;" <?php echo $read;?>>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width='13%' class='Libelle'>&nbsp; Zone de travail : <?php echo $etoile; ?></td><td width='15%'>
					<select id='zone' name='zone' <?php echo $disabled; ?>>
						<option name='0' value='0'></option>
						<?php
							$req="SELECT Id,Libelle, Supprime FROM sp_olwzonedetravail WHERE Id_Prestation=-15 ORDER BY Libelle;";
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
				<td width="15%">
					<input type="date" style="text-align:center;" id="dateRenvoiNC"  name="dateRenvoiNC" size="10" value="<?php if($row['DateRenvoiNC']>"0001-01-01"){echo AfficheDateFR($row['DateRenvoiNC']);} ?>">
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
									<option name="0" value="0"></option>
								<?php
									$IdPole=0;
									$poste="";
									$req="SELECT Id,Libelle FROM sp_poste WHERE Id_Prestation=-15 AND Supprime=false ORDER BY Libelle;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowPoste=mysqli_fetch_array($result)){
											$selected="";
											if($rowPoste['Libelle']==$row['PosteAvionACP']){$selected="selected";}
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
								&nbsp;<textarea id="travailRealise" name="travailRealise" rows="3" cols="45" style="resize:none;" <?php echo $read;?>></textarea>
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
									$displayPrepa="style='display:none;'";
									If($row['Id_StatutPROD']=="RETOUR PREPA"){
										$displayPrepa="";
									}
								?>
								<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo" <?php echo $displayPrepa;?>>
									<tr height="25">
										<td width="30%" align="center"></td>
										<td width="30%" align="center" class="Libelle">Début</td>
										<td width="30%" align="center" class="Libelle">Fin</td>
									</tr>
									<tr height="35">
										<td width="35%" bgcolor="#dadadc" class="Libelle">&nbsp; Enquête</td>
										<td width="30%" bgcolor="#dadadc" align="center" id="DebutEnquete"><a style="text-decoration:none;" href="javascript:Valider('Enquete');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a></td>
										<td width="30%" bgcolor="#dadadc" align="center" id="FinEnquete"></td>
									</tr>
									<tr height="48">
										<td colspan="3" bgcolor="#dadadc" valign="center" align="center">
											<textarea id="commentaireEnquete" name="commentaireEnquete" rows="1" cols="48" style="resize:none;"></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" class="Libelle">&nbsp; Check IQ</td>
										<td width="30%" align="center" id="DebutCheckIQ"><a style="text-decoration:none;" href="javascript:Valider('CheckIQ');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a></td>
										<td width="30%" id="FinCheckIQ" align="center"></td>
									</tr>
									<tr height="48">
										<td colspan="3" valign="center" align="center">
											<textarea id="commentaireCheckIQ" name="commentaireCheckIQ" rows="1" cols="48" style="resize:none;"></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" bgcolor="#dadadc" class="Libelle">&nbsp; Demande appro</td>
										<td width="30%" bgcolor="#dadadc" align="center" id="DebutAppro"><a style="text-decoration:none;" href="javascript:Valider('Appro');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a></td>
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
											<textarea id="commentaireAppro" name="commentaireAppro" rows="1" cols="48" style="resize:none;"></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" class="Libelle">&nbsp; Demande assistance</td>
										<td width="30%" align="center" id="DebutDA"><a style="text-decoration:none;" href="javascript:Valider('DA');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a></td>
										<td width="30%" id="FinDA" align="center"></td>
									</tr>
									<tr height="20">
										<td colspan="3" valign="center" align="left" class="Libelle">
											&nbsp; Date prévu : 
											<input type="date" style="text-align:center;"  id="dateDA" name="dateDA" size="10" value="">
											&nbsp;&nbsp;&nbsp; N° DA : 
											<input type="text" style="text-align:center;" id="numDA" name="numDA" size="15" value="">
										</td>
									</tr>
									<tr height="48">
										<td colspan="3" valign="center" align="center">
											<textarea id="commentaireDA" name="commentaireDA" rows="1" cols="48" style="resize:none;"></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" bgcolor="#dadadc" class="Libelle">&nbsp; Création dérogation</td>
										<td width="30%" bgcolor="#dadadc" align="center" id="DebutDERO"><a style="text-decoration:none;" href="javascript:Valider('DERO');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a></td>
										<td width="30%" bgcolor="#dadadc" id="FinDERO" align="center"></td>
									</tr>
									<tr height="20">
										<td colspan="3" bgcolor="#dadadc" valign="center" align="left" class="Libelle">
											&nbsp; N° dérogation : 
											<input type="text" style="text-align:center;" id="numDERO" name="numDERO" size="15" value="">
										</td>
									</tr>
									<tr height="48">
										<td colspan="3" bgcolor="#dadadc" valign="center" align="center">
											<textarea id="commentaireDERO" name="commentaireDERO" rows="1" cols="48" style="resize:none;"></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" class="Libelle">&nbsp; Attente map</td>
										<td width="30%" align="center" id="DebutMAP"><a style="text-decoration:none;" href="javascript:Valider('MAP');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a></td>
										<td width="30%" align="center" id="FinMAP"></td>
									</tr>
									<tr height="48">
										<td colspan="3" valign="center" align="center">
											<textarea id="commentaireMAP" name="commentaireMAP" rows="1" cols="48" style="resize:none;"></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" bgcolor="#dadadc" class="Libelle">&nbsp; Transfert partenaire</td>
										<td width="30%" bgcolor="#dadadc" align="center" id="DebutPartenaire"><a style="text-decoration:none;" href="javascript:Valider('Partenaire');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a></td>
										<td width="30%" bgcolor="#dadadc" id="FinPartenaire" align="center"></td>
									</tr>
									<tr height="48">
										<td colspan="3" bgcolor="#dadadc" valign="center" align="center">
											<textarea id="commentairePartenaire" name="commentairePartenaire" rows="1" cols="48" style="resize:none;"></textarea>
										</td>
									</tr>
									<tr height="35">
										<td width="30%" class="Libelle">&nbsp; Attente accès</td>
										<td width="30%" align="center" id="DebutAcces"><a style="text-decoration:none;" href="javascript:Valider('Acces');"><img id="img" src="../../../Images/Avion1.png" height="35" alt="Démarrer" title="Démarrer"></a></td>
										<td width="30%" align="center" id="FinAcces"></td>
									</tr>
									<tr height="48">
										<td colspan="3" valign="center" align="center">
											<textarea id="commentaireAcces" name="commentaireAcces" rows="1" cols="48" style="resize:none;"></textarea>
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
								<input <?php if(substr($_SESSION['DroitSP'],0,1)=='0' && substr($_SESSION['DroitSP'],1,1)=='0'){echo "type='texte'";}else{echo "type='date'";}?> style="text-align:center;" id="dateIntervention" name="dateIntervention"  size="15" value="" <?php echo  $readSTCE;?>>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9">&nbsp; Site intervention : </td>
							<td width="20%" bgcolor="#dbeef9">
								<select id="siteIntervention" name="siteIntervention" <?php echo $disabledSTCE;?>>
									<option name="0" value="0"></option>
								<?php
									$req="SELECT Id,Libelle FROM sp_olwsiteintervention WHERE Id_Prestation=-15 AND Supprime=false ORDER BY Libelle;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowSI=mysqli_fetch_array($result)){
											$selected="";
											echo "<option value='".$rowSI['Id']."' ".$selected.">".$rowSI['Libelle']."</option>";
										}
									}
								?>
								</select>
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
											$req.="FROM new_rh_etatcivil LEFT JOIN sp_acces ON new_rh_etatcivil.Id=sp_acces.Id_Personne WHERE sp_acces.Id_Prestation=-15 AND SUBSTR(sp_acces.Droit,3,1)='1' ORDER BY Nom, Prenom;";
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
									</tr>
								</table>
								<table id="tab_TravailEffectue" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle" width="70%">TOTAL</td><td class="Libelle">
										<input type="text" size="4" style="background-color:#dbeef9;border:none" name="tpsFI" id="tpsFI" value="" readonly="readonly"/>
									</td></tr>
								</table>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9">&nbsp; Statut PROD : </td>
							<td width="20%" bgcolor="#dbeef9">
								<?php
									$reqTERA="SELECT Id FROM sp_olwficheintervention WHERE Id_Dossier=".$row['Id_Dossier']." AND Id_StatutPROD='TERA'";
									$resultTERA=mysqli_query($bdd,$reqTERA);
									$nbTERA=mysqli_num_rows($resultTERA);
									
								?>
								<select id="statutProd" name="statutProd" onchange="Recharge_StatutProd();" <?php echo $disabledCE;?>>
									<option name="" value=""></option>
									<?php
									if($nbTERA>0 && $disabledNew=="disabled='disabled'"){
										$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation=-15 AND TypeStatut='P' ORDER BY Id;";
									}
									elseif($nbTERA>0 && $disabledNew<>"disabled='disabled'"){
										$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation=-15 AND TypeStatut='P' AND Id<>'TERA' ORDER BY Id;";
									}
									else{
										$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation=-15 AND TypeStatut='P' AND Id<>'REWORK' ORDER BY Id;";
									}
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
									$req="SELECT Id, Libelle, Id_Statut, Supprime FROM sp_olwretour WHERE Id_Prestation=-15 AND Supprime=0 ORDER BY Libelle;";
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
									<?php
										if($row['Id_StatutPROD']=="TFS"){
											for($i=0;$i<=100;$i=$i+5){
												$selected="";
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
								<textarea id="commentairePROD" name="commentairePROD" rows="5" cols="40" style="resize:none;" <?php echo $readCE;?>></textarea>
							</td>
						</tr>
					</table>
					<table width="100%" id="tableIngredient" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="10" colspan="2"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width='35%' class="Libelle" valign="center">
								<table width="100%" cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor="#e4e7f" colspan='2'><i>&nbsp; Ajouter les ingrédients :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; Ingrédient : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<select id="RefIngredient" name="RefIngredient" onkeypress="if(event.keyCode == 13)AjouterIngredient()" style="width:130px;">
												<?php
												echo"<option name='0' value='0'></option>";
												$req="SELECT Id, Libelle FROM sp_olwingredient WHERE Id_Prestation=-15 AND Supprime=false ORDER BY Libelle;";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												if ($nbResulta>0){
													$i=0;
													while($rowIngredient=mysqli_fetch_array($result)){
														echo "<option name='".$rowIngredient['Id']."' value='".$rowIngredient['Id']."'>".$rowIngredient['Libelle']."</option>";
														echo "<script>Liste_Ingredient[".$i."] = new Array('".$rowIngredient['Id']."','".addslashes($rowIngredient['Libelle'])."');</script>\n";
														$i+=1;
													}
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;N° lot : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" id="numLot" onkeypress="if(event.keyCode == 13)AjouterIngredient()" name="numLot" size="8" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor="#e4e7f0">&nbsp;Date péremption : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" id="datePeremption"  onkeypress="if(event.keyCode == 13)AjouterIngredient()" name="datePeremption" size="8" type="date" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor="#e4e7f0">&nbsp;Coeff. Hygrométrique : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" onkeypress="if(event.keyCode == 13)AjouterIngredient()" id="coeffH" name="coeffH" size="8" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
										<?php
											if(substr($_SESSION['DroitSP'],1,1)=='1'){
										?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterIngredient()'>&nbsp;Ajouter&nbsp;</a>
										<?php
											}
										?>	
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='65%' valign='top'>
								<table id="tab_Ingredient" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle">Ingrédient</td><td class="Libelle">N° lot</td><td class="Libelle">Date</td><td class="Libelle">Coeff.</td></tr>
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
										<td bgcolor="#e4e7f0" colspan='2'><i>&nbsp; Ajouter les ECME :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;Référence : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" id="reference" name="reference" onkeypress="if(event.keyCode == 13)AjouterECME()" size="15" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor="#e4e7f0">&nbsp;Date d'étalonnage : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" id="dateEtalonnage"  onkeypress="if(event.keyCode == 13)AjouterECME()" name="dateEtalonnage" size="8" type="date" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
										<?php
											if(substr($_SESSION['DroitSP'],1,1)=='1'){
										?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterECME()'>&nbsp;Ajouter&nbsp;</a>
										<?php
											}
										?>	
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='65%' valign='top'>
								<table id="tab_ECME" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle">ECME</td><td class="Libelle">Date d'étalonnage</td></tr>
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
										<td bgcolor="#e4e7f0"><i>&nbsp; Ajouter les AIPI-AIPS :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;Référence : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<select id="RefAIPI" name="RefAIPI" onkeypress="if(event.keyCode == 13)AjouterAIPIS()" style="width:130px;">
												<?php
												echo"<option name='0' value='0'></option>";
												$req="SELECT new_competences_qualification.Id,new_competences_qualification.Libelle ";
												$req.="FROM new_competences_prestation_qualification ";
												$req.="LEFT JOIN new_competences_qualification ";
												$req.="ON new_competences_prestation_qualification.Id_Qualification = new_competences_qualification.Id ";
												$req.="WHERE new_competences_prestation_qualification.Id_Prestation IN (-15,950,418) ";
												$req.="AND new_competences_qualification.Id_Categorie_Qualification=112 ";
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
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' style='height:25px;' valign='center'>
										<?php
											if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1'){
										?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterAIPIS()'>&nbsp;Ajouter&nbsp;</a>
										<?php
											}
										?>	
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='65%' valign='top'>
								<table id="tab_AIPI" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle" align="center">AIPI-AIPS</td></tr>
								</table>
							</td>
						</tr>
						<tr><td height="8" colspan="2" bgcolor="#dbeef9"></td></tr>
					</table>
				</td>
				<td colspan="2" width="33%" valign="top">
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="4" colspan="2"></td></tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr><td height="4" colspan="2" align="center" style="font-size:14px;font-weight:bold;" bgcolor="#c7e048">QUALITE</td></tr>
						<tr>
							<td bgcolor="#c7e048" width="13%" class="Libelle">&nbsp; Date intervention : </td>
							<td bgcolor="#c7e048" width="20%">
								<input <?php if(substr($_SESSION['DroitSP'],4,1)=='0'){echo "type='texte'";}else{echo "type='date'";}?> style="text-align:center;" id="dateInterventionQ" name="dateInterventionQ"  size="15" value="" <?php echo  $readIQ;?>>
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
									$req.="FROM new_rh_etatcivil LEFT JOIN sp_acces ON new_rh_etatcivil.Id=sp_acces.Id_Personne WHERE sp_acces.Id_Prestation=-15 AND SUBSTR(sp_acces.Droit,5,1)=1 ORDER BY NomPrenom;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowIQ=mysqli_fetch_array($result)){
											$selected="";
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
										$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation=-15 AND TypeStatut='Q' ORDER BY Id;";
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
			</tr>
			<tr style="display:none;"><td><input id="travailEffectue" name="travailEffectue" value=""  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="ECME" name="ECME" value=""  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id='lesAIPI' name='lesAIPI' value=""  readonly='readonly'></td></tr>
			<tr style="display:none;"><td><input id="Ingredient" name="Ingredient" value=""  readonly="readonly"></td></tr>
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
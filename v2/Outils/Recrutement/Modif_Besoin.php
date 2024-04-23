<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="Besoin.js"></script>
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
	<script>
		function FermerEtRecharger()
		{
			window.opener.location="Besoins.php";
			window.close();
		}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require_once("../PlanningV2/Fonctions_Planning.php");
require_once("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
Ecrire_Code_JS_Init_Date();
require("Fonction_Recrutement.php");
$DirFichier="Documents/";
$SrcProblem="";
$Problem=0;
$FichierTransfert=0;
$Fichier="";

$bEnregistrement=false;
if($_POST){
	if(isset($_POST['btnEnregistrer2'])){
		
		if(isset($_POST['SupprFichier']))
		{
			if($_POST['SupprFichier'])
			{
				if(!unlink($DirFichier.$_POST['fichieractuel'])){$SrcProblem.="Impossible de supprimer le fichier.";$Problem=1;}
				elseif($FichierTransfert==0){$Fichier="";}
			}
		}
		
		$requete="UPDATE recrut_annonce 
			SET Id_Domaine=".$_POST['domaine'].",
			Programme='".addslashes($_POST['programme'])."',
			DemandePSE=".$_POST['demandePSE'].",
			Division='".addslashes($_POST['division'])."',
			Lieu='".addslashes($_POST['lieu'])."',
			Metier='".addslashes($_POST['metier'])."',
			Nombre=".$_POST['nombr'].",
			DateBesoin='".TrsfDate_($_POST['dateSouhaitee'])."',
			PosteDefinitif=".$_POST['posteDefinitif'].",
			Duree='".addslashes($_POST['duree'])."',
			CreationPoste=".$_POST['etatPoste'].",
			Id_TypeHoraire=".$_POST['Id_TypeHoraire'].",
			Id_CategorieProfessionnelle=".$_POST['categorie'].",
			Horaire='".addslashes($_POST['horaire'])."',
			DescriptifPoste='".addslashes($_POST['DescriptifPoste'])."',
			SavoirFaire='".addslashes($_POST['savoirfaire'])."',
			SavoirEtre='".addslashes($_POST['savoiretre'])."',
			Langue='".addslashes($_POST['Langues'])."',
			Prerequis='".addslashes($_POST['Prerequis'])."',
			CategorieProf='".addslashes($_POST['categorieProfessionnelle'])."',
			IGD='".addslashes($_POST['IGD'])."',
			Salaire='".addslashes($_POST['salaire'])."',
			MotifDemande='".addslashes($_POST['MotifDemande'])."'
			WHERE Id=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$requete);
		
		$req="DELETE FROM recrut_annonce_savoiretre WHERE Id_Annonce=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$req);
		
		$req="SELECT Id, Libelle FROM recrut_savoiretre WHERE Suppr=0 ORDER BY Libelle ";
		$result=mysqli_query($bdd,$req);
		$nbenreg=mysqli_num_rows($result);
		if($nbenreg>0)
		{
			while($rowSE=mysqli_fetch_array($result))
			{
				if(isset($_POST['savoiretres_'.$rowSE['Id']])){
					$req="INSERT INTO recrut_annonce_savoiretre (Id_Annonce,Id_SavoirEtre) VALUES (".$_POST['Id'].",".$rowSE['Id'].") ";
					$resultAdd=mysqli_query($bdd,$req);
				}
			}
		}
		
		if($_FILES['fichier']['name']!="")
		{
			$tmp_file=$_FILES['fichier']['tmp_name'];
			if(!is_uploaded_file($tmp_file)){$SrcProblem.="Le fichier est introuvable.";$Problem=1;}
			else
			{
				//On vérifie la taille du fichiher
				if(filesize($_FILES['fichier']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
				{$SrcProblem.="Le fichier est trop volumineux.";$Problem=1;}
				else
				{
					// on copie le fichier dans le dossier de destination
					$name_file=$_FILES['fichier']['name'];
					$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
					while(file_exists($DirFichier.$name_file)){$name_file="le_".date('j-m-y')."_a_".date('H-i-s')."_".$name_file;}
					if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
					{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
					else{$Fichier=$name_file;$FichierTransfert=1;}
				}
			}
		}
			
		if($Problem==1){echo "<script>alert('Il y a eu une erreur lors de la copie du fichier joint (".$SrcProblem."). Veuillez vérifier si celui-ci est bien ajouté dans ce que vous venez de créer.');</script>";}
		else
		{
			if($_FILES['fichier']['name']!="" || (isset($_POST['SupprFichier']) && $_POST['SupprFichier'])){
				$requeteUpt="UPDATE recrut_annonce SET";
					$requeteUpt.=" FicheMetier='".$Fichier."'";
					$requeteUpt.=" WHERE Id=".$_POST['Id'];
					$resultUpt=mysqli_query($bdd,$requeteUpt);
			}
		}
		
	}
	elseif(isset($_POST['btnEnregistrerOperation'])){
		
		if(isset($_POST['SupprFichier']))
		{
			if($_POST['SupprFichier'])
			{
				if(!unlink($DirFichier.$_POST['fichieractuel'])){$SrcProblem.="Impossible de supprimer le fichier.";$Problem=1;}
				elseif($FichierTransfert==0){$Fichier="";}
			}
		}
		
		$requete="UPDATE recrut_annonce 
			SET Id_Domaine=".$_POST['domaine'].",
			Programme='".addslashes($_POST['programme'])."',
			DemandePSE=".$_POST['demandePSE'].",
			Division='".addslashes($_POST['division'])."',
			Lieu='".addslashes($_POST['lieu'])."',
			Metier='".addslashes($_POST['metier'])."',
			Nombre=".$_POST['nombr'].",
			DateBesoin='".TrsfDate_($_POST['dateSouhaitee'])."',
			PosteDefinitif=".$_POST['posteDefinitif'].",
			Duree='".addslashes($_POST['duree'])."',
			CreationPoste=".$_POST['etatPoste'].",
			Id_TypeHoraire=".$_POST['Id_TypeHoraire'].",
			Horaire='".addslashes($_POST['horaire'])."',
			DescriptifPoste='".addslashes($_POST['DescriptifPoste'])."',
			Id_CategorieProfessionnelle=".$_POST['categorie'].",
			SavoirFaire='".addslashes($_POST['savoirfaire'])."',
			SavoirEtre='".addslashes($_POST['savoiretre'])."',
			Langue='".addslashes($_POST['Langues'])."',
			Prerequis='".addslashes($_POST['Prerequis'])."',
			CategorieProf='".addslashes($_POST['categorieProfessionnelle'])."',
			IGD='".addslashes($_POST['IGD'])."',
			Salaire='".addslashes($_POST['salaire'])."',
			MotifDemande='".addslashes($_POST['MotifDemande'])."',
			Id_Validateur=".$_SESSION['Id_Personne'].",
			DateValidation='".date('Y-m-d')."',
			EtatValidation=".$_POST['etatValidation'].",
			RaisonRefus='".addslashes($_POST['raisonRefus'])."'
			WHERE Id=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$requete);
		
		$req="DELETE FROM recrut_annonce_savoiretre WHERE Id_Annonce=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$req);
		
		$req="SELECT Id, Libelle FROM recrut_savoiretre WHERE Suppr=0 ORDER BY Libelle ";
		$result=mysqli_query($bdd,$req);
		$nbenreg=mysqli_num_rows($result);
		if($nbenreg>0)
		{
			while($rowSE=mysqli_fetch_array($result))
			{
				if(isset($_POST['savoiretres_'.$rowSE['Id']])){
					$req="INSERT INTO recrut_annonce_savoiretre (Id_Annonce,Id_SavoirEtre) VALUES (".$_POST['Id'].",".$rowSE['Id'].") ";
					$resultAdd=mysqli_query($bdd,$req);
				}
			}
		}
		if($_FILES['fichier']['name']!="")
		{
			$tmp_file=$_FILES['fichier']['tmp_name'];
			if(!is_uploaded_file($tmp_file)){$SrcProblem.="Le fichier est introuvable.";$Problem=1;}
			else
			{
				//On vérifie la taille du fichiher
				if(filesize($_FILES['fichier']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
				{$SrcProblem.="Le fichier est trop volumineux.";$Problem=1;}
				else
				{
					// on copie le fichier dans le dossier de destination
					$name_file=$_FILES['fichier']['name'];
					$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
					while(file_exists($DirFichier.$name_file)){$name_file="le_".date('j-m-y')."_a_".date('H-i-s')."_".$name_file;}
					if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
					{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
					else{$Fichier=$name_file;$FichierTransfert=1;}
				}
			}
		}
			
		if($Problem==1){echo "<script>alert('Il y a eu une erreur lors de la copie du fichier joint (".$SrcProblem."). Veuillez vérifier si celui-ci est bien ajouté dans ce que vous venez de créer.');</script>";}
		else
		{

			if($_FILES['fichier']['name']!="" || (isset($_POST['SupprFichier']) && $_POST['SupprFichier'])){
				$requeteUpt="UPDATE recrut_annonce SET";
					$requeteUpt.=" FicheMetier='".$Fichier."'";
					$requeteUpt.=" WHERE Id=".$_POST['Id'];
					$resultUpt=mysqli_query($bdd,$requeteUpt);
			}
		}
	}
	elseif(isset($_POST['btnAnnulationValidation'])){
		
		$requete="UPDATE recrut_annonce 
			SET Id_Validateur=0,
			DateValidation='0001-01-01,
			EtatValidation=0,
			RaisonRefus='',
			Id_Approbation=0,
			DateApprobation='0001-01-01,
			EtatApprobation=0,
			OuvertureAutresPlateformes=0
			WHERE Id=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$requete);
	}
	elseif(isset($_POST['btnEnregistrerPlateforme'])){
		
		if(isset($_POST['SupprFichier']))
		{
			if($_POST['SupprFichier'])
			{
				if(!unlink($DirFichier.$_POST['fichieractuel'])){$SrcProblem.="Impossible de supprimer le fichier.";$Problem=1;}
				elseif($FichierTransfert==0){$Fichier="";}
			}
		}
		
		$requete="UPDATE recrut_annonce 
			SET Id_Domaine=".$_POST['domaine'].",
			Programme='".addslashes($_POST['programme'])."',
			DemandePSE=".$_POST['demandePSE'].",
			Division='".addslashes($_POST['division'])."',
			Lieu='".addslashes($_POST['lieu'])."',
			Metier='".addslashes($_POST['metier'])."',
			Nombre=".$_POST['nombr'].",
			DateBesoin='".TrsfDate_($_POST['dateSouhaitee'])."',
			PosteDefinitif=".$_POST['posteDefinitif'].",
			Duree='".addslashes($_POST['duree'])."',
			CreationPoste=".$_POST['etatPoste'].",
			Id_TypeHoraire=".$_POST['Id_TypeHoraire'].",
			Horaire='".addslashes($_POST['horaire'])."',
			DescriptifPoste='".addslashes($_POST['DescriptifPoste'])."',
			SavoirFaire='".addslashes($_POST['savoirfaire'])."',
			SavoirEtre='".addslashes($_POST['savoiretre'])."',
			Langue='".addslashes($_POST['Langues'])."',
			Prerequis='".addslashes($_POST['Prerequis'])."',
			CategorieProf='".addslashes($_POST['categorieProfessionnelle'])."',
			Id_CategorieProfessionnelle=".$_POST['categorie'].",
			IGD='".addslashes($_POST['IGD'])."',
			Salaire='".addslashes($_POST['salaire'])."',
			MotifDemande='".addslashes($_POST['MotifDemande'])."',
			Id_Approbation=".$_SESSION['Id_Personne'].",
			DateApprobation='".date('Y-m-d')."',
			EtatApprobation=".$_POST['etatApprobation'].",
			OuvertureAutresPlateformes=".$_POST['deploiementOffre'].",
			RaisonRefusApprobation='".addslashes($_POST['raisonRefusApprobation'])."'
			WHERE Id=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$requete);
		
		$req="DELETE FROM recrut_annonce_savoiretre WHERE Id_Annonce=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$req);
		
		$req="SELECT Id, Libelle FROM recrut_savoiretre WHERE Suppr=0 ORDER BY Libelle ";
		$result=mysqli_query($bdd,$req);
		$nbenreg=mysqli_num_rows($result);
		if($nbenreg>0)
		{
			while($rowSE=mysqli_fetch_array($result))
			{
				if(isset($_POST['savoiretres_'.$rowSE['Id']])){
					$req="INSERT INTO recrut_annonce_savoiretre (Id_Annonce,Id_SavoirEtre) VALUES (".$_POST['Id'].",".$rowSE['Id'].") ";
					$resultAdd=mysqli_query($bdd,$req);
				}
			}
		}
		
		if($_FILES['fichier']['name']!="")
		{
			$tmp_file=$_FILES['fichier']['tmp_name'];
			if(!is_uploaded_file($tmp_file)){$SrcProblem.="Le fichier est introuvable.";$Problem=1;}
			else
			{
				//On vérifie la taille du fichiher
				if(filesize($_FILES['fichier']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
				{$SrcProblem.="Le fichier est trop volumineux.";$Problem=1;}
				else
				{
					// on copie le fichier dans le dossier de destination
					$name_file=$_FILES['fichier']['name'];
					$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
					while(file_exists($DirFichier.$name_file)){$name_file="le_".date('j-m-y')."_a_".date('H-i-s')."_".$name_file;}
					if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
					{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
					else{$Fichier=$name_file;$FichierTransfert=1;}
				}
			}
		}
			
		if($Problem==1){echo "<script>alert('Il y a eu une erreur lors de la copie du fichier joint (".$SrcProblem."). Veuillez vérifier si celui-ci est bien ajouté dans ce que vous venez de créer.');</script>";}
		else
		{

			if($_FILES['fichier']['name']!="" || (isset($_POST['SupprFichier']) && $_POST['SupprFichier'])){
				$requeteUpt="UPDATE recrut_annonce SET";
					$requeteUpt.=" FicheMetier='".$Fichier."'";
					$requeteUpt.=" WHERE Id=".$_POST['Id'];
					$resultUpt=mysqli_query($bdd,$requeteUpt);
			}
		}
		
		if($_POST['posteDefinitif']==0){
			creerMail("BESOIN INTERNE",$_SESSION['Langue'],$_POST['Id']);
		}
	}
	elseif(isset($_POST['btnAnnulationApprobation'])){
		
		$requete="UPDATE recrut_annonce 
			SET Id_Approbation=0,
			DateApprobation='0001-01-01,
			EtatApprobation=0,
			OuvertureAutresPlateformes=0,
			Id_Recrutement=0,
			DateRecrutement='0001-01-01,
			DateRecrutementMAJ='0001-01-01,
			EtatRecrutement=0,
			RaisonRefusRecrutement=''
			WHERE Id=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$requete);
	}
	elseif(isset($_POST['btnAnnulationRecrutement'])){
		
		$requete="UPDATE recrut_annonce 
			SET Id_Recrutement=0,
			DateRecrutement='0001-01-01,
			DateRecrutementMAJ='0001-01-01,
			EtatRecrutement=0,
			RaisonRefusRecrutement=''
			WHERE Id=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$requete);
	}
	elseif(isset($_POST['btnEnregistrerRecrutement'])){
		
		if(isset($_POST['SupprFichier']))
		{
			if($_POST['SupprFichier'])
			{
				if(!unlink($DirFichier.$_POST['fichieractuel'])){$SrcProblem.="Impossible de supprimer le fichier.";$Problem=1;}
				elseif($FichierTransfert==0){$Fichier="";}
			}
		}
		
		$requete="UPDATE recrut_annonce 
			SET Id_Domaine=".$_POST['domaine'].",
			Programme='".addslashes($_POST['programme'])."',
			DemandePSE=".$_POST['demandePSE'].",
			Division='".addslashes($_POST['division'])."',
			Lieu='".addslashes($_POST['lieu'])."',
			Metier='".addslashes($_POST['metier'])."',
			Nombre=".$_POST['nombr'].",
			DateBesoin='".TrsfDate_($_POST['dateSouhaitee'])."',
			PosteDefinitif=".$_POST['posteDefinitif'].",
			Duree='".addslashes($_POST['duree'])."',
			CreationPoste=".$_POST['etatPoste'].",
			Id_TypeHoraire=".$_POST['Id_TypeHoraire'].",
			Horaire='".addslashes($_POST['horaire'])."',
			DescriptifPoste='".addslashes($_POST['DescriptifPoste'])."',
			SavoirFaire='".addslashes($_POST['savoirfaire'])."',
			SavoirEtre='".addslashes($_POST['savoiretre'])."',
			Langue='".addslashes($_POST['Langues'])."',
			Prerequis='".addslashes($_POST['Prerequis'])."',
			CategorieProf='".addslashes($_POST['categorieProfessionnelle'])."',
			Id_CategorieProfessionnelle=".$_POST['categorie'].",
			IGD='".addslashes($_POST['IGD'])."',
			Salaire='".addslashes($_POST['salaire'])."',
			MotifDemande='".addslashes($_POST['MotifDemande'])."',
			Id_Recrutement=".$_SESSION['Id_Personne'].",
			DateRecrutement='".date('Y-m-d')."',
			EtatRecrutement=".$_POST['etatRecrutement'].",
			RaisonRefusRecrutement='".addslashes($_POST['raisonRefusRecrutement'])."'
			WHERE Id=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$requete);
		
		$req="DELETE FROM recrut_annonce_savoiretre WHERE Id_Annonce=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$req);
		
		$req="SELECT Id, Libelle FROM recrut_savoiretre WHERE Suppr=0 ORDER BY Libelle ";
		$result=mysqli_query($bdd,$req);
		$nbenreg=mysqli_num_rows($result);
		if($nbenreg>0)
		{
			while($rowSE=mysqli_fetch_array($result))
			{
				if(isset($_POST['savoiretres_'.$rowSE['Id']])){
					$req="INSERT INTO recrut_annonce_savoiretre (Id_Annonce,Id_SavoirEtre) VALUES (".$_POST['Id'].",".$rowSE['Id'].") ";
					$resultAdd=mysqli_query($bdd,$req);
				}
			}
		}
		
		if($_FILES['fichier']['name']!="")
		{
			$tmp_file=$_FILES['fichier']['tmp_name'];
			if(!is_uploaded_file($tmp_file)){$SrcProblem.="Le fichier est introuvable.";$Problem=1;}
			else
			{
				//On vérifie la taille du fichiher
				if(filesize($_FILES['fichier']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
				{$SrcProblem.="Le fichier est trop volumineux.";$Problem=1;}
				else
				{
					// on copie le fichier dans le dossier de destination
					$name_file=$_FILES['fichier']['name'];
					$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
					while(file_exists($DirFichier.$name_file)){$name_file="le_".date('j-m-y')."_a_".date('H-i-s')."_".$name_file;}
					if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
					{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
					else{$Fichier=$name_file;$FichierTransfert=1;}
				}
			}
		}
			
		if($Problem==1){echo "<script>alert('Il y a eu une erreur lors de la copie du fichier joint (".$SrcProblem."). Veuillez vérifier si celui-ci est bien ajouté dans ce que vous venez de créer.');</script>";}
		else
		{

			if($_FILES['fichier']['name']!="" || (isset($_POST['SupprFichier']) && $_POST['SupprFichier'])){
				$requeteUpt="UPDATE recrut_annonce SET";
					$requeteUpt.=" FicheMetier='".$Fichier."'";
					$requeteUpt.=" WHERE Id=".$_POST['Id'];
					$resultUpt=mysqli_query($bdd,$requeteUpt);
			}
		}
		
		if($_POST['etatRecrutement']==1){
			creerMail("OFFRE EMPLOI",$_SESSION['Langue'],$_POST['Id']);
		}
	}
	elseif(isset($_POST['btnEnregistrerRecrutementMAJ'])){
		
		if(isset($_POST['SupprFichier']))
		{
			if($_POST['SupprFichier'])
			{
				if(!unlink($DirFichier.$_POST['fichieractuel'])){$SrcProblem.="Impossible de supprimer le fichier.";$Problem=1;}
				elseif($FichierTransfert==0){$Fichier="";}
			}
		}
		
		$requete="UPDATE recrut_annonce 
			SET Id_Domaine=".$_POST['domaine'].",
			Programme='".addslashes($_POST['programme'])."',
			DemandePSE=".$_POST['demandePSE'].",
			Division='".addslashes($_POST['division'])."',
			Lieu='".addslashes($_POST['lieu'])."',
			Metier='".addslashes($_POST['metier'])."',
			Nombre=".$_POST['nombr'].",
			DateBesoin='".TrsfDate_($_POST['dateSouhaitee'])."',
			PosteDefinitif=".$_POST['posteDefinitif'].",
			Duree='".addslashes($_POST['duree'])."',
			CreationPoste=".$_POST['etatPoste'].",
			EtatPoste=".$_POST['statutPoste'].",
			Id_TypeHoraire=".$_POST['Id_TypeHoraire'].",
			Horaire='".addslashes($_POST['horaire'])."',
			DescriptifPoste='".addslashes($_POST['DescriptifPoste'])."',
			SavoirFaire='".addslashes($_POST['savoirfaire'])."',
			SavoirEtre='".addslashes($_POST['savoiretre'])."',
			Langue='".addslashes($_POST['Langues'])."',
			Prerequis='".addslashes($_POST['Prerequis'])."',
			CategorieProf='".addslashes($_POST['categorieProfessionnelle'])."',
			Id_CategorieProfessionnelle=".$_POST['categorie'].",
			IGD='".addslashes($_POST['IGD'])."',
			Salaire='".addslashes($_POST['salaire'])."',
			MotifDemande='".addslashes($_POST['MotifDemande'])."',
			DateRecrutementMAJ='".date('Y-m-d')."'
			WHERE Id=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$requete);
		
		$req="DELETE FROM recrut_annonce_savoiretre WHERE Id_Annonce=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$req);
		
		$req="SELECT Id, Libelle FROM recrut_savoiretre WHERE Suppr=0 ORDER BY Libelle ";
		$result=mysqli_query($bdd,$req);
		$nbenreg=mysqli_num_rows($result);
		if($nbenreg>0)
		{
			while($rowSE=mysqli_fetch_array($result))
			{
				if(isset($_POST['savoiretres_'.$rowSE['Id']])){
					$req="INSERT INTO recrut_annonce_savoiretre (Id_Annonce,Id_SavoirEtre) VALUES (".$_POST['Id'].",".$rowSE['Id'].") ";
					$resultAdd=mysqli_query($bdd,$req);
				}
			}
		}
		
		if($_FILES['fichier']['name']!="")
		{
			$tmp_file=$_FILES['fichier']['tmp_name'];
			if(!is_uploaded_file($tmp_file)){$SrcProblem.="Le fichier est introuvable.";$Problem=1;}
			else
			{
				//On vérifie la taille du fichiher
				if(filesize($_FILES['fichier']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
				{$SrcProblem.="Le fichier est trop volumineux.";$Problem=1;}
				else
				{
					// on copie le fichier dans le dossier de destination
					$name_file=$_FILES['fichier']['name'];
					$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
					while(file_exists($DirFichier.$name_file)){$name_file="le_".date('j-m-y')."_a_".date('H-i-s')."_".$name_file;}
					if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
					{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
					else{$Fichier=$name_file;$FichierTransfert=1;}
				}
			}
		}
			
		if($Problem==1){echo "<script>alert('Il y a eu une erreur lors de la copie du fichier joint (".$SrcProblem."). Veuillez vérifier si celui-ci est bien ajouté dans ce que vous venez de créer.');</script>";}
		else
		{

			if($_FILES['fichier']['name']!="" || (isset($_POST['SupprFichier']) && $_POST['SupprFichier'])){
				$requeteUpt="UPDATE recrut_annonce SET";
					$requeteUpt.=" FicheMetier='".$Fichier."'";
					$requeteUpt.=" WHERE Id=".$_POST['Id'];
					$resultUpt=mysqli_query($bdd,$requeteUpt);
			}
		}
		
		creerMail("MAJ RECRUTEMENT",$_SESSION['Langue'],$_POST['Id']);
	}
	elseif(isset($_POST['btnEnregistrerPlateformeMAJ'])){
		
		if(isset($_POST['SupprFichier']))
		{
			if($_POST['SupprFichier'])
			{
				if(!unlink($DirFichier.$_POST['fichieractuel'])){$SrcProblem.="Impossible de supprimer le fichier.";$Problem=1;}
				elseif($FichierTransfert==0){$Fichier="";}
			}
		}
		
		$requete="UPDATE recrut_annonce 
			SET Id_Domaine=".$_POST['domaine'].",
			Programme='".addslashes($_POST['programme'])."',
			DemandePSE=".$_POST['demandePSE'].",
			Division='".addslashes($_POST['division'])."',
			Lieu='".addslashes($_POST['lieu'])."',
			Metier='".addslashes($_POST['metier'])."',
			Nombre=".$_POST['nombr'].",
			DateBesoin='".TrsfDate_($_POST['dateSouhaitee'])."',
			PosteDefinitif=".$_POST['posteDefinitif'].",
			Duree='".addslashes($_POST['duree'])."',
			CreationPoste=".$_POST['etatPoste'].",
			EtatPoste=".$_POST['statutPoste'].",
			CandidatsRetenus='".addslashes($_POST['personnesRecrutees'])."',
			Id_TypeHoraire=".$_POST['Id_TypeHoraire'].",
			Horaire='".addslashes($_POST['horaire'])."',
			DescriptifPoste='".addslashes($_POST['DescriptifPoste'])."',
			SavoirFaire='".addslashes($_POST['savoirfaire'])."',
			SavoirEtre='".addslashes($_POST['savoiretre'])."',
			Langue='".addslashes($_POST['Langues'])."',
			Prerequis='".addslashes($_POST['Prerequis'])."',
			CategorieProf='".addslashes($_POST['categorieProfessionnelle'])."',
			Id_CategorieProfessionnelle=".$_POST['categorie'].",
			IGD='".addslashes($_POST['IGD'])."',
			Salaire='".addslashes($_POST['salaire'])."',
			MotifDemande='".addslashes($_POST['MotifDemande'])."'
			WHERE Id=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$requete);
		
		$req="DELETE FROM recrut_annonce_savoiretre WHERE Id_Annonce=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$req);
		
		$req="SELECT Id, Libelle FROM recrut_savoiretre WHERE Suppr=0 ORDER BY Libelle ";
		$result=mysqli_query($bdd,$req);
		$nbenreg=mysqli_num_rows($result);
		if($nbenreg>0)
		{
			while($rowSE=mysqli_fetch_array($result))
			{
				if(isset($_POST['savoiretres_'.$rowSE['Id']])){
					$req="INSERT INTO recrut_annonce_savoiretre (Id_Annonce,Id_SavoirEtre) VALUES (".$_POST['Id'].",".$rowSE['Id'].") ";
					$resultAdd=mysqli_query($bdd,$req);
				}
			}
		}
		
		if($_FILES['fichier']['name']!="")
		{
			$tmp_file=$_FILES['fichier']['tmp_name'];
			if(!is_uploaded_file($tmp_file)){$SrcProblem.="Le fichier est introuvable.";$Problem=1;}
			else
			{
				//On vérifie la taille du fichiher
				if(filesize($_FILES['fichier']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
				{$SrcProblem.="Le fichier est trop volumineux.";$Problem=1;}
				else
				{
					// on copie le fichier dans le dossier de destination
					$name_file=$_FILES['fichier']['name'];
					$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
					while(file_exists($DirFichier.$name_file)){$name_file="le_".date('j-m-y')."_a_".date('H-i-s')."_".$name_file;}
					if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
					{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
					else{$Fichier=$name_file;$FichierTransfert=1;}
				}
			}
		}
			
		if($Problem==1){echo "<script>alert('Il y a eu une erreur lors de la copie du fichier joint (".$SrcProblem."). Veuillez vérifier si celui-ci est bien ajouté dans ce que vous venez de créer.');</script>";}
		else
		{

			if($_FILES['fichier']['name']!="" || (isset($_POST['SupprFichier']) && $_POST['SupprFichier'])){
				$requeteUpt="UPDATE recrut_annonce SET";
					$requeteUpt.=" FicheMetier='".$Fichier."'";
					$requeteUpt.=" WHERE Id=".$_POST['Id'];
					$resultUpt=mysqli_query($bdd,$requeteUpt);
			}
		}
		
		if($_POST['statutPosteOLD']==0 && $_POST['statutPoste']==1){
			creerMail("BESOIN POURVU",$_SESSION['Langue'],$_POST['Id']);
		}
		
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET){
	if($_GET['Mode']=="S"){
		$requete="UPDATE recrut_annonce 
			SET Suppr=1,
			Id_Suppr=".$_SESSION['Id_Personne'].",
			DateSuppr='".date('Y-m-d')."'
			WHERE Id=".$_GET['Id']." ";
		$result=mysqli_query($bdd,$requete);
		
		echo "<script>FermerEtRecharger();</script>";
	}
}
if($_SESSION["Langue"]=="FR"){
	$reqSuite="IF(EtatRecrutement<>0,'OFFRE','BESOIN') AS Etat, 
		IF(EtatValidation=0,'En attente validation',
			IF(EtatValidation=-1,'Refusé',
				IF(EtatValidation=1 && EtatApprobation=0,'En attente approbation',
					IF(EtatValidation=1 && EtatApprobation=-1,'Non approuvé',
							IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=0,'Validé en interne',
							  IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=1,'En attente validation offre',
								IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'Refusé','Passage en annonce')
								)
							)
						)
					)
				)
			) AS Statut, ";
}
else{
	$reqSuite="IF(EtatRecrutement<>0,'OFFER','NEED') AS Etat, 
		IF(EtatValidation=0,'Pending validation',
					IF(EtatValidation=-1,'Refuse',
						IF(EtatValidation=1 && EtatApprobation=0,'Pending approval',
							IF(EtatValidation=1 && EtatApprobation=-1,'Not approved',
									IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=0,'Internally validated',
										IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=1,'Pending validation offer',
											IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'Refuse','Switch to announcement')
										)
									)
								)
							)
						)
					) AS Statut, ";
}
$requete="SELECT Id,DateDemande,Id_Demandeur,Id_Prestation,Metier,Nombre,Lieu,RaisonRefus,Suppr,Id_CategorieProfessionnelle,Division,DemandePSE,
			".$reqSuite."
			CONCAT(Metier,'-',
			Lieu,'-',
			Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',DATE_FORMAT(DateDemande,'%d%m%y')
			) AS Ref,RaisonRefus,RaisonRefusRecrutement,RaisonRefusApprobation,EtatPoste,
			EtatApprobation,EtatRecrutement,Programme,CreationPoste,CategorieProf,IGD,Salaire,MotifDemande,
			DescriptifPoste,SavoirFaire,SavoirEtre,Prerequis,OuvertureAutresPlateformes,CandidatsRetenus,
			DateBesoin,Duree,PosteDefinitif,Id_Prestation,Id_Domaine,Id_TypeHoraire,DateRecrutementMAJ,
			(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
			(SELECT Libelle FROM recrut_domaine WHERE Id=Id_Domaine) AS Domaine,Langue,FicheMetier,
			(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
			(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,EtatValidation
	FROM recrut_annonce
	WHERE recrut_annonce.Id=".$_GET['Id'] ;
$result=mysqli_query($bdd,$requete);
$row=mysqli_fetch_array($result);

$Etat="";
$CouleurEtat="#ffffff";
$NumRefus=2;
$EstRefuse=0;

$typedate="date";
$modifiable="";
$selection="";

if($row['EtatRecrutement']<>0 || ($row['EtatApprobation']==1 && $row['OuvertureAutresPlateformes']==0) ){$Couleur="#ecf943";}
else{$Couleur="#6c94d0";}
					
//if($row['Etat']>0 || DroitsPrestation(array($IdPosteResponsableProjet),$row['Id_Prestation'])==false || $Menu<>3){$typedate="text";$modifiable="readonly='readonly'";$selection="disabled='disabled'";}

$typedateRH="text";
$modifiableRecrut="readonly='readonly'";
//if($row['Etat']==1 && $Menu==12){$typedateRecrut="date";$modifiableRecrut="";}
?>

<form id="formulaire" class="test" enctype="multipart/form-data" action="Modif_Besoin.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
	<input type="hidden" name="ValiderRefuser" id="ValiderRefuser" value="" />
	<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
	<input type="hidden" name="statutPosteOLD" id="statutPosteOLD" value="<?php echo $row['EtatPoste']; ?>" />
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="10%" bgcolor="<?php echo $Couleur;?>" class="Libelle" colspan="8" align="center">&nbsp;<?php echo $row['Etat']; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Division";}else{echo "Division";}?> : </td>
							<td width="18%">&nbsp;
								<select name="division" id="division">
									<option value=""></option>
									<option value="FAL" <?php if($row['Division']=="FAL"){echo "selected";} ?>>FAL</option>
									<option value="SUC" <?php if($row['Division']=="SUC"){echo "selected";} ?>>SUC</option>
									<option value="DIVISIONS" <?php if($row['Division']=="DIVISIONS"){echo "selected";} ?>>DIVISIONS</option>
								</select>
							</td>
							<td width="10%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;<?php if($_SESSION['Id_Personne']<>1132 && $_SESSION['Id_Personne']<>4320){echo "display:none;";} ?>">&nbsp;<?php if($LangueAffichage=="FR"){echo "Demande PSE";}else{echo "PSE request";}?> : </td>
							<td width="18%" style="<?php if($_SESSION['Id_Personne']<>1132 && $_SESSION['Id_Personne']<>4320){echo "display:none;";} ?>">&nbsp;
								<select name="demandePSE" id="demandePSE">
									<option value="1" <?php if($row['DemandePSE']==1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";}?></option>
									<option value="0" <?php if($row['DemandePSE']==0){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}?></option>
								</select>
							</td>
							<td width="10%" bgcolor="#2e5496" style="color:#ffffff;" class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Date mise à jour :";}else{echo "Date updated :";} ?></td>
							<td width="10%" style="color:#2e5496;"><b>&nbsp;&nbsp;<?php echo AfficheDateJJ_MM_AAAA($row['DateRecrutementMAJ']); ?></b></td>
							<td width="10%" bgcolor="#2e5496" style="color:#ffffff;" class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Demandeur :";}else{echo "Applicant :";} ?></td>
							<td width="10%">&nbsp;&nbsp;<?php echo $row['Demandeur']; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" bgcolor="#2e5496" style="color:#ffffff;" class="Libelle">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Ref :";}else{echo "Ref :";} ?></td>
							<td width="10%" style="color:#2e5496;"><b>&nbsp;&nbsp;<?php echo $row['Ref']; ?></b></td>
							<td width="10%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?></td>
							<td width="10%">&nbsp;&nbsp;<?php echo stripslashes($row['Plateforme']); ?></td>
							<td width="10%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
							<td width="10%">&nbsp;&nbsp;<?php echo stripslashes($row['Prestation']); ?></td>
							<td class="Libelle" width="8%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Programme";}else{echo "Programm";}?> : </td>
							<td width="20%">
								&nbsp;<input style="width:150px" name="programme" id="programme" value="<?php echo stripslashes($row['Programme']); ?>"/>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Domaine :";}else{echo "Domain :";} ?></td>
							<td width="10%">&nbsp;
									<select name="domaine" id="domaine" style="width:100px;">
									<?php

										$req="SELECT Id, Libelle
										FROM recrut_domaine
										WHERE Suppr=0
										OR Id=".$row['Id_Domaine']."
										ORDER BY Libelle ASC";
										$resultDom=mysqli_query($bdd,$req);
										while($rowDom=mysqli_fetch_array($resultDom))
										{
											$selected="";
											if($rowDom['Id']==$row['Id_Domaine']){$selected="selected";}
											echo "<option value='".$rowDom['Id']."' ".$selected." >";
											echo str_replace("'"," ",stripslashes($rowDom['Libelle']))."</option>\n";
										}
									?>
								</select>
							</td>
							<td class="Libelle" width="8%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Lieu";}else{echo "Place";}?> : </td>
							<td width="18%">&nbsp;
								<input style="width:200px" name="lieu" id="lieu" value="<?php echo stripslashes($row['Lieu']); ?>"/>
							</td>
							<td width="8%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Métier :";}else{echo "Job :";} ?></td>
							<td width="20%">&nbsp;
								<input style="width:200px" name="metier" id="metier" value="<?php echo stripslashes($row['Metier']); ?>"/>
								<input name="fichier" type="file" onChange="CheckFichier();">
								<?php
								if($row['FicheMetier']!="")
								{
								?>
									<br>
									<?php 
										if($LangueAffichage=="FR"){
											echo "<a class=\"Info\" href=\"".$DirFichier."/".$row['FicheMetier']."\" target=\"_blank\">Ouvrir</a>";
										}
										else{
											echo "<a class=\"Info\" href=\"".$DirFichier."/".$row['FicheMetier']."\" target=\"_blank\">Open</a>";
										}
									?>
									<input type="hidden" name="fichieractuel" value="<?php echo $row['FicheMetier'];?>">
									<br>
									<input type="checkbox" name="SupprFichier" onClick="CheckFichier();">
								<?php if($LangueAffichage=="FR"){echo "Supprimer le fichier";}else{echo "Delete file";}?>
								<?php
								}
								?>
							</td>
							<td class="Libelle" width="8%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Nombre";}else{echo "Number";}?> : </td>
							<td width="25%">&nbsp;<input onKeyUp="nombre(this)" style="width:30px" name="nombr" id="nombr" value="<?php echo stripslashes($row['Nombre']); ?>"/></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Date début souhaitée";}else{echo "Desired start date";}?> : </td>
							<td width="10%">&nbsp;<input type="date" id="dateSouhaitee" name="dateSouhaitee" size="10" value="<?php echo AfficheDateFR($row['DateBesoin']); ?>"></td>
							<td width="8%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Type de poste";}else{echo "Position type";}?> : </td>
							<td width="18%">&nbsp;
								<select name="posteDefinitif" id="posteDefinitif" onchange="afficherDuree();">
									<option value="1" <?php if($row['PosteDefinitif']==1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste définitif";}else{echo "Definitive position";}?></option>
									<option value="0" <?php if($row['PosteDefinitif']==0){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Mission";}else{echo "Mission";}?></option>
									<option value="2" <?php if($row['PosteDefinitif']==2){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "CDD 6 mois";}else{echo "CDD 6 mois";}?></option>
									<option value="3" <?php if($row['PosteDefinitif']==3){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "CDD 2 mois";}else{echo "CDD 2 mois";}?></option>
									<option value="4" <?php if($row['PosteDefinitif']==4){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "CDD";}else{echo "CDD";}?></option>
								</select>
							</td>
							<td id="duree1" class="Libelle" width="8%" bgcolor="#2e5496" style="color:#ffffff;<?php  if($row['PosteDefinitif']==1 || $row['PosteDefinitif']==2){echo "display:none;";}?>" >&nbsp;<?php if($LangueAffichage=="FR"){echo "Durée";}else{echo "Duration";}?> : </td>
							<td id="duree2" width="20%" <?php  if($row['PosteDefinitif']==1 || $row['PosteDefinitif']==2){echo "style='display:none;'";}?>>
								&nbsp;<input style="width:150px" name="duree" id="duree" value="<?php if($row['PosteDefinitif']==0){echo stripslashes($row['Duree']);} ?>"/>
							</td>
							
							<td class="Libelle" width="8%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Type d'horaire";}else{echo "Schedule type";}?> : </td>
							<td width="25%">
								<table>
									<tr>
										<td style="white-space: nowrap;">
											&nbsp;<select name="Id_TypeHoraire" id="Id_TypeHoraire" style="width:100px;">
													<option value="0"></option>
												<?php
													$requete="SELECT Id, Libelle
													FROM recrut_typehoraire
													WHERE Suppr=0
													ORDER BY Libelle ASC";
													$results=mysqli_query($bdd,$requete);
													while($rows=mysqli_fetch_array($results))
													{
														$selected="";
														if($rows['Id']==$row['Id_TypeHoraire']){$selected="selected";}
														echo "<option value='".$rows['Id']."' ".$selected.">";
														echo str_replace("'"," ",stripslashes($rows['Libelle']))."</option>\n";
													}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td style="white-space: nowrap;">
											&nbsp;<input  style="width:150px" name="horaire" id="horaire" value=""/>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="8%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Etat du poste";}else{echo "Position status";}?> : </td>
							<td width="18%">
								&nbsp;<select name="etatPoste" id="etatPoste">
									<option value=""></option>
									<option value="0" <?php if($row['CreationPoste']==0){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Création de poste";}else{echo "Job creation";}?></option>
									<option value="1" <?php if($row['CreationPoste']==1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste vacant";}else{echo "Vacancy";}?></option>
								</select>
							</td>
							<td class="Libelle" width="10%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Statut ";}else{echo "Status ";}?> : </td>
							<td width="18%" >
								&nbsp;<select name="categorieProfessionnelle" id="categorieProfessionnelle">
									<option value="Agent de maitrise" <?php if(stripslashes($row['CategorieProf'])=="Agent de maitrise"){echo "selected";} ?>>Agent de maitrise</option>
									<option value="ART 4 BIS" <?php if(stripslashes($row['CategorieProf'])=="ART 4 BIS"){echo "selected";} ?>>ART 4 BIS</option>
									<option value="Cadre" <?php if(stripslashes($row['CategorieProf'])=="Cadre"){echo "selected";} ?>>Cadre</option>
									<option value="Employé" <?php if(stripslashes($row['CategorieProf'])=="Employé"){echo "selected";} ?>>Employé</option>
									<option value="Ouvrier" <?php if(stripslashes($row['CategorieProf'])=="Ouvrier"){echo "selected";} ?>>Ouvrier</option>
									<option value="Technicien" <?php if(stripslashes($row['CategorieProf'])=="Technicien"){echo "selected";} ?>>Technicien</option>
								</select>
							</td>
							<td class="Libelle" width="10%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Salaire ";}else{echo "Salary ";}?> : </td>
							<td width="18%" >
								&nbsp;<input  style="width:150px" name="salaire" id="salaire" value="<?php echo stripslashes($row['Salaire']); ?>"/>
							</td>
							<td width="10%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Catégorie d’emploi, à titre indicatif :";}else{echo "Job category, for information only :";} ?></td>
							<td width="10%">&nbsp;
									<select name="categorie" id="categorie" style="width:200px;">
										<option value="0"></option>
									<?php
										$requete="SELECT Id, Libelle
											FROM recrut_categorieprofessionnelle
											WHERE Suppr=0
											ORDER BY Libelle ASC";
										
										$result=mysqli_query($bdd,$requete);
										while($rowS=mysqli_fetch_array($result))
										{
											$selected="";
											if($rowS['Id']==$row['Id_CategorieProfessionnelle']){$selected="selected";}
											echo "<option value='".$rowS['Id']."' ".$selected.">";
											echo str_replace("'"," ",stripslashes($rowS['Libelle']))."</option>\n";
										}
									?>
								</select>
							</td>
							<td class="Libelle" width="10%" bgcolor="#2e5496" style="color:#ffffff;display:none;">&nbsp;<?php if($LangueAffichage=="FR"){echo "IGD ";}else{echo "IGD ";}?> : </td>
							<td width="18%" style="display:none;">
								&nbsp;<select name="IGD" id="IGD">
									<option value="Oui" <?php if(stripslashes($row['IGD'])=="Oui"){echo "selected";} ?>>Oui</option>
									<option value="Non" <?php if(stripslashes($row['IGD'])=="Non"){echo "selected";} ?>>Non</option>
								</select>
							</td>
						</tr>
						<tr style="display:none;"><td height="4"></td></tr>
						<tr style="display:none;">
							<td class="Libelle" width="10%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Motif de la demande ";}else{echo "Reason for the request ";}?> : </td>
							<td width="18%"  colspan="5">
								&nbsp;<input  style="width:800px" name="MotifDemande" id="MotifDemande" value="<?php echo stripslashes($row['MotifDemande']); ?>"/>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" valign="top" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Descriptif du poste :";}else{echo "Job Description :";} ?></td>
							<td width="30%" colspan="3">
								&nbsp;<textarea name="DescriptifPoste" id="DescriptifPoste" cols="90" rows="8" style="resize:none;"><?php echo stripslashes($row['DescriptifPoste']); ?></textarea>
							</td>
							<td width="10%" class="Libelle" valign="top" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Diplômes :";}else{echo "Diplomas :";} ?></td>
							<td width="30%" colspan="3">
								&nbsp;<textarea name="Prerequis" id="Prerequis" cols="90" rows="8" style="resize:none;"><?php echo stripslashes($row['Prerequis']); ?></textarea>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" valign="top" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Savoir faire,prérequis :<br><br>Qualités professionnelles<br><br>Polyvalence métier<br><br>Compétences techniques<br><br>Compétences managériales";}else{echo "Know-how, prerequisites:<br><br>Professional skills<br><br>-Experience<br><br>Business versatility<br><br>Technical skills<br><br>Managerial skills";} ?></td>
							<td width="30%" colspan="3" valign="top">
								&nbsp;<textarea name="savoirfaire" id="savoirfaire" cols="90" rows="12" style="resize:none;"><?php echo stripslashes($row['SavoirFaire']); ?></textarea>
							</td>
							<td width="10%" class="Libelle" valign="top" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Savoir être :";}else{echo "know how to be :";} ?></td>
							<td width="30%" colspan="3" valign="top">
								&nbsp;
								<table width="100%">
									<tr>
										<td width="50%" valign="top">
											<table width="100%">
												<?php
													$req="SELECT Id, Libelle FROM recrut_savoiretre WHERE Suppr=0 ORDER BY Libelle ";
													$result=mysqli_query($bdd,$req);
													$nbenreg=mysqli_num_rows($result);
													if($nbenreg>0)
													{
														while($rowSE=mysqli_fetch_array($result))
														{
															$req="SELECT Id FROM recrut_annonce_savoiretre WHERE Id_Annonce=".$row['Id']." AND Id_SavoirEtre=".$rowSE['Id']." ";
															$resultASE=mysqli_query($bdd,$req);
															$nbASE=mysqli_num_rows($resultASE);
															
															$checked="";
															if($nbASE>0){$checked="checked";}
															
															echo "<tr><td>";
															echo"<input type='checkbox' class='savoiretres' name='savoiretres_".$rowSE['Id']."' ".$checked." value='".$rowSE['Id']."'>".stripslashes($rowSE['Libelle'])." ";
															echo "</td></tr>";
														}
													}
												?>
											</table>
										</td>
										<td width="50%" valign="top">
											&nbsp;<textarea name="savoiretre" id="savoiretre" cols="45" rows="12" style="resize:none;"><?php echo stripslashes($row['SavoirEtre']); ?></textarea>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle" width="10%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Langues ";}else{echo "Languages ";}?> : </td>
							<td width="30%" colspan="3">
								&nbsp;<input  style="width:550px" name="Langues" id="Langues" value="<?php echo stripslashes($row['Langue']); ?>"/>
							</td>
							<td class="Libelle" width="10%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Etat ";}else{echo "State ";}?> : </td>
							<td width="30%" colspan="3">
								&nbsp;<b><?php echo stripslashes($row['Statut']); 
								if($row['EtatValidation']==-1){echo " : ".stripslashes($row['RaisonRefus']);}
								elseif($row['EtatValidation']==1 && $row['EtatApprobation']==-1){echo " : ".stripslashes($row['RaisonRefusApprobation']);}
								elseif($row['EtatValidation']==1 && $row['EtatApprobation']==1 && $row['EtatRecrutement']==-1){echo " : ".stripslashes($row['RaisonRefusRecrutement']);}
								?></b>
							</td>
						</tr>
						<?php if($row['EtatApprobation']>0){ ?>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle" width="10%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Déploiement de l'offre";}else{echo "Deployment of the offer";}?> : </td>
							<td width="30%" colspan="3">
								&nbsp;<b><?php 
								if($row['OuvertureAutresPlateformes']==0){if($LangueAffichage=="FR"){echo "Interne";}else{echo "Internal";}}
								else{if($LangueAffichage=="FR"){echo "Autres unités d'exploitations";}else{echo "Other operating units";}}
								?></b>
							</td>
						</tr>
						<?php 
						}
							$reqPrestaPoste = "SELECT Id_Prestation 
								FROM new_competences_personne_poste_prestation 
								WHERE Id_Personne =".$IdPersonneConnectee."  
								AND ".$row['Id_Prestation']."
								AND Id_Poste IN (".$IdPosteResponsableOperation.")
								";	
							$nbPoste=mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPrestaPoste));
							
							if($row['EtatValidation']==0 && $nbPoste>0){
						?>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="8%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Validation";}else{echo "Validation";}?> : </td>
							<td width="18%">
								&nbsp;<select name="etatValidation" id="etatValidation" onchange="AfficherRefus()">
									<option value="0"></option>
									<option value="1"><?php if($LangueAffichage=="FR"){echo "Validé";}else{echo "Validated";}?></option>
									<option value="-1"><?php if($LangueAffichage=="FR"){echo "Refusé";}else{echo "Refused";}?></option>
								</select>
							</td>
							<td id="tdRaison1" class="Libelle" width="10%" bgcolor="#2e5496" style="color:#ffffff;display:none;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Raison refus ";}else{echo "Reason for refusal ";}?> : </td>
							<td id="tdRaison2" width="30%" colspan="3" style="display:none;">
								&nbsp;<input  style="width:350px" name="raisonRefus" id="raisonRefus" value=""/>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="8" align="center">
								<div id="Ajouter">
								</div>
								<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="EnregistrerModif('Operation')">
							</td>
						</tr>
						<?php
							}
							elseif($row['EtatValidation']>0 && $row['EtatApprobation']==0){
								if(DroitsFormation1Plateforme($row['Id_Plateforme'],array($IdPosteResponsablePlateforme)) || DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
						?>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="8%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Approbation";}else{echo "Approval";}?> : </td>
							<td width="18%">
								&nbsp;<select name="etatApprobation" id="etatApprobation" onchange="AfficherRefusApprobation()">
									<option value="0"></option>
									<option value="1"><?php if($LangueAffichage=="FR"){echo "Approuvé";}else{echo "Approved";}?></option>
									<option value="-1"><?php if($LangueAffichage=="FR"){echo "Non approuvé";}else{echo "Not approved";}?></option>
								</select>
							</td>
							<td id="tdRaisonA1" class="Libelle" width="10%" bgcolor="#2e5496" style="color:#ffffff;display:none;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Raison refus ";}else{echo "Reason for refusal ";}?> : </td>
							<td id="tdRaisonA2" width="30%" colspan="3" style="display:none;">
								&nbsp;<input  style="width:350px" name="raisonRefusApprobation" id="raisonRefusApprobation" value=""/>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr id="trDeploiement" style="display:none;">
							<td width="8%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Déploiement de l'offre";}else{echo "Deployment of the offer";}?> : </td>
							<td width="18%">
								&nbsp;<select name="deploiementOffre" id="deploiementOffre">
									<?php if($row['PosteDefinitif']==0){ ?>
									<option value="0"><?php if($LangueAffichage=="FR"){echo "Interne";}else{echo "Internal";}?></option>
									<?php } ?>
									<option value="1"><?php if($LangueAffichage=="FR"){echo "Autres unités d'exploitations";}else{echo "Other operating units";}?></option>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="7" align="center">
								<div id="Ajouter">
								</div>
								<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="EnregistrerModif('Plateforme')">
							</td>
							<td align="right">
								<input class="Bouton" type="submit" id="btnAnnulationValidation" name="btnAnnulationValidation" value="Annuler validation">
							</td>
						</tr>
						<?php
								}
							}
							elseif($row['EtatValidation']>0 && $row['EtatApprobation']>0 && $row['OuvertureAutresPlateformes']==0){
								if(DroitsFormation1Plateforme($row['Id_Plateforme'],array($IdPosteAssistantRH,$IdPosteResponsableRH))){
							?>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="8%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Statut du poste";}else{echo "Job status";}?> : </td>
								<td width="18%">
									&nbsp;<select name="statutPoste" id="statutPoste">
										<option value="0" <?php if($row['EtatPoste']==0){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste ouvert";}else{echo "Open post";}?></option>
										<option value="1" <?php if($row['EtatPoste']==1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste pourvu";}else{echo "Position filled";}?></option>
										<option value="-1" <?php if($row['EtatPoste']==-1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste annulé";}else{echo "Position canceled";}?></option>
									</select>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
								<tr>
									<td width="10%" class="Libelle" valign="top" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personnes recrutées :";}else{echo "People recruited :";} ?></td>
									<td width="30%" colspan="3">
										&nbsp;<textarea name="personnesRecrutees" id="personnesRecrutees" cols="40" rows="8" style="resize:none;"><?php echo stripslashes($row['CandidatsRetenus']); ?></textarea>
									</td>
								</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td colspan="7" align="center">
								</td>
								<td align="right">
									<input class="Bouton" type="submit" id="btnAnnulationApprobation" name="btnAnnulationApprobation" value="Annuler approbation">
								</td>
							</tr>
							<?php 
								}
								elseif($row['Id_Demandeur']==$_SESSION['Id_Personne']){
								?>
								<tr><td height="4"></td></tr>
								<tr>
									<td width="8%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Statut du poste";}else{echo "Job status";}?> : </td>
									<td width="18%">
										&nbsp;<select name="statutPoste" id="statutPoste">
											<option value="0" <?php if($row['EtatPoste']==0){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste ouvert";}else{echo "Open post";}?></option>
											<option value="1" <?php if($row['EtatPoste']==1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste pourvu";}else{echo "Position filled";}?></option>
											<option value="2" <?php if($row2['EtatPoste']==2){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste non pourvu";}else{echo "Position not filled";}?></option>
											<option value="3" <?php if($row2['EtatPoste']==3){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste pourvu partiellement";}else{echo "Position partially filled";}?></option>
											<option value="-1" <?php if($row['EtatPoste']==-1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste annulé";}else{echo "Position canceled";}?></option>
										</select>
									</td>
								</tr>
								<tr><td height="4"></td></tr>
								<tr>
									<td width="10%" class="Libelle" valign="top" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personnes recrutées :";}else{echo "People recruited :";} ?></td>
									<td width="30%" colspan="3">
										&nbsp;<textarea name="personnesRecrutees" id="personnesRecrutees" cols="40" rows="8" style="resize:none;"><?php echo stripslashes($row['CandidatsRetenus']); ?></textarea>
									</td>
								</tr>
								<tr><td height="4"></td></tr>
								<tr>
									<td colspan="8" align="center">
										<div id="Ajouter">
										</div>
										<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="MAJ" onClick="EnregistrerModif('PlateformeMAJ')">
									</td>
								</tr>
								<?php
								}

							}
							elseif($row['EtatValidation']>0 && $row['EtatApprobation']>0 && $row['OuvertureAutresPlateformes']>0 && $row['EtatRecrutement']==0){
								if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
						?>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="8%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Validation";}else{echo "Validation";}?> : </td>
							<td width="18%">
								&nbsp;<select name="etatRecrutement" id="etatRecrutement" onchange="AfficherRefusRecrutement()">
									<option value="0"></option>
									<option value="1"><?php if($LangueAffichage=="FR"){echo "Validé";}else{echo "Validated";}?></option>
									<option value="-1"><?php if($LangueAffichage=="FR"){echo "Refusé";}else{echo "Refused";}?></option>
								</select>
							</td>
							<td id="tdRaisonR1" class="Libelle" width="10%" bgcolor="#2e5496" style="color:#ffffff;display:none;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Raison refus ";}else{echo "Reason for refusal ";}?> : </td>
							<td id="tdRaisonR2" width="30%" colspan="3" style="display:none;">
								&nbsp;<input  style="width:350px" name="raisonRefusRecrutement" id="raisonRefusRecrutement" value=""/>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="7" align="center">
								<div id="Ajouter">
								</div>
								<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="EnregistrerModif('Recrutement')">
							</td>
							<td align="right">
								<input class="Bouton" type="submit" id="btnAnnulationApprobation" name="btnAnnulationApprobation" value="Annuler approbation">
							</td>
						</tr>
						<?php
								}
							}
							elseif($row['EtatValidation']>0 && $row['EtatApprobation']>0 && $row['EtatRecrutement']<>0){
								if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
							?>
							<tr><td height="4"></td></tr>
							<tr>
								<td width="8%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Statut du poste";}else{echo "Job status";}?> : </td>
								<td width="18%">
									&nbsp;<select name="statutPoste" id="statutPoste">
										<option value="0" <?php if($row['EtatPoste']==0){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste ouvert";}else{echo "Open post";}?></option>
										<option value="1" <?php if($row['EtatPoste']==1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste pourvu";}else{echo "Position filled";}?></option>
										<option value="-1" <?php if($row['EtatPoste']==-1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste annulé";}else{echo "Position canceled";}?></option>
									</select>
								</td>
							</tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td colspan="7" align="center">
									<div id="Ajouter">
									</div>
									<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="MAJ" onClick="EnregistrerModif('RecrutementMAJ')">
								</td>
								<td align="right">
									<input class="Bouton" type="submit" id="btnAnnulationRecrutement" name="btnAnnulationRecrutement" value="Annuler diffusion annonce">
								</td>
							</tr>
							<?php 
								}
							}
							elseif($row['EtatApprobation']<>-1 && $row['EtatRecrutement']<>-1){
								$reqPrestaPoste = "SELECT Id_Prestation 
									FROM new_competences_personne_poste_prestation 
									WHERE Id_Personne =".$IdPersonneConnectee."  
									AND ".$row['Id_Prestation']."
									AND Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.")
									";	
								$nbPoste=mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPrestaPoste));
								if($nbPoste>0){
						?>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="8" align="center">
								<div id="Ajouter">
								</div>
								<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="EnregistrerModif('Responsable')">
							</td>
						</tr>
						<?php
								}
							}
						?>
						
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
</table>
</form>
	
</body>
</html>
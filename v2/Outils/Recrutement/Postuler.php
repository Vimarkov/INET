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
</head>
<?php
session_start();
require("../Connexioni.php");
require_once("../PlanningV2/Fonctions_Planning.php");
require_once("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
Ecrire_Code_JS_Init_Date();
require("Fonction_Recrutement.php");

$LangueAffichage=$_SESSION['Langue'];
$SrcProblem="";
$Problem=0;
$FichierTransfert=0;

$DirFichier="Documents/";
if(isset($_POST['fichieractuel'])){$Fichier=$_POST['fichieractuel'];}
else{$Fichier="";}
$bEnregistrement=false;
if($_POST){
	if(isset($_POST['btnEnregistrer'])){
		$requete="INSERT INTO recrut_candidature (Id_Annonce,Id_Personne,Id_Plateforme,Id_Prestation,DateCreation,HeureCreation,Tel,Mail,MailPro,Responsable,Motivation,PosteOccupe)
			VALUES (".$_POST['Id'].",".$_SESSION['Id_Personne'].",".$_POST['plateforme'].",".$_POST['Id_PrestationPole'].",'".date("Y-m-d")."','".date("H:i:s")."','".$_POST['Tel']."',
			'".$_POST['Email']."','".$_POST['EmailPro']."','".addslashes($_POST['responsable'])."','".addslashes($_POST['motivation'])."','".addslashes($_POST['PosteOccupe'])."') ";
		$result=mysqli_query($bdd,$requete);
		$IdCree = mysqli_insert_id($bdd);
	if($IdCree>0){
		//****TRANSFERT FICHIER****
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

			$requeteUpt="UPDATE recrut_candidature SET";
			$requeteUpt.=" CV='".$Fichier."'";
			$requeteUpt.=" WHERE Id=".$IdCree;
			$resultUpt=mysqli_query($bdd,$requeteUpt);
		}
?>
<br><br><br><br><br><br><br><br><br><br><br><br>
<table class="TableCompetences"  width="100%" cellpadding="0" cellspacing="0" valign="center">
	<tr>
		<td align="center" style="border:1px solid black;font-size:18px;">
<?php
		if($_SESSION["Langue"]=="FR"){
			echo "Votre candidature va être transférée au PIC, elle sera analysée attentivement, en cas de pluralité de candidatures, les critères d’ordre conformes au PSE s’appliqueront.";
		}
		else{
			echo "Your application will be transferred to the PIC, it will be carefully analyzed, in the event of multiple applications, the order criteria in accordance with the PSE will apply.";
		}
	}
?>
		</td>
	</tr>
</table>
<?php
	if($IdCree>0){
		creerMailCandidature("CANDIDATURE",$_SESSION['Langue'],$_POST['Id'],$IdCree);
		creerMailCandidat("CANDIDAT",$_SESSION['Langue'],$_POST['Id'],$IdCree);
	}
	echo "<script>window.opener.location='Modif_Annonce.php?Mode=M&Id=".$_POST['Id']."';</script>";
	echo "<script>window.opener.opener.location='Annonces.php';</script>";
	}
	elseif(isset($_POST['btnAnnuler'])){
		$requeteUpt="UPDATE recrut_candidature SET 
						Suppr=1,
						DateSuppr='".date('Y-m-d')."',
						Id_Suppr=".$_SESSION['Id_Personne']."
					WHERE Id=".$_POST['Id_Candidat'];
		$resultUpt=mysqli_query($bdd,$requeteUpt);
?>
<br><br><br><br><br><br><br><br><br><br><br><br>
<table class="TableCompetences"  width="100%" cellpadding="0" cellspacing="0" valign="center">
	<tr>
		<td align="center" style="border:1px solid black;">
<?php
		if($_SESSION["Langue"]=="FR"){
			echo "Votre candidature pour l'offre ".$_POST['Ref']." a été annulée.";
		}
		else{
			echo "Your application for offer ".$_POST['Ref']." has been canceled.";
		}
?>
		</td>
	</tr>
</table>
<?php
		if($_POST['Id_Candidat']>0){
			creerMailAnnulation($_SESSION['Langue'],$_POST['Id'],$_POST['Id_Candidat']);
		}
		echo "<script>window.opener.location='Modif_Annonce.php?Mode=M&Id=".$_POST['Id']."';</script>";
		echo "<script>window.opener.opener.location='Annonces.php';</script>";
	}
	elseif(isset($_POST['btnModifier'])){
		$requete="UPDATE recrut_candidature 
			SET Id_Plateforme=".$_POST['plateforme'].",
			Id_Prestation=".$_POST['Id_PrestationPole'].",
			Tel='".$_POST['Tel']."',
			Mail='".$_POST['Email']."',
			MailPro='".$_POST['EmailPro']."',
			Responsable='".addslashes($_POST['responsable'])."',
			Motivation='".addslashes($_POST['motivation'])."',
			PosteOccupe='".addslashes($_POST['PosteOccupe'])."',
			DateMAJ='".date('Y-m-d')."'
			WHERE Id=".$_POST['Id_Candidat'];
		$result=mysqli_query($bdd,$requete);
		
		//S'il y avait une fichier
		if(isset($_POST['SupprFichier']))
		{
			if($_POST['SupprFichier'])
			{
				if(!unlink($DirFichier.$_POST['fichieractuel'])){$SrcProblem.="Impossible de supprimer le fichier.";$Problem=1;}
				elseif($FichierTransfert==0){$Fichier="";}
				
				$requeteUpt="UPDATE recrut_candidature SET";
				$requeteUpt.=" CV=''";
				$requeteUpt.=" WHERE Id=".$_POST['Id_Candidat'];
				$resultUpt=mysqli_query($bdd,$requeteUpt);
			}
		}
		
		//****TRANSFERT FICHIER****
		if($_FILES['fichier']['name']!="")
		{
			$Fichier='';
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
			
			if($Problem==1){echo "<script>alert('Il y a eu une erreur lors de la copie du fichier joint (".$SrcProblem."). Veuillez vérifier si celui-ci est bien ajouté dans ce que vous venez de créer.');</script>";}
			else
			{

				$requeteUpt="UPDATE recrut_candidature SET";
				$requeteUpt.=" CV='".$Fichier."'";
				$requeteUpt.=" WHERE Id=".$_POST['Id_Candidat'];
				$resultUpt=mysqli_query($bdd,$requeteUpt);
			}
		}

		
		?>
		<br><br><br><br><br><br><br><br><br><br><br><br>
		<table class="TableCompetences"  width="100%" cellpadding="0" cellspacing="0" valign="center">
			<tr>
				<td align="center" style="border:1px solid black;" valign="center">
		<?php
				if($_SESSION["Langue"]=="FR"){
					echo "Votre candidature pour l'offre ".$_POST['Ref']." a été modifiée.";
				}
				else{
					echo "Your application for offer ".$_POST['Ref']." has been modified.";
				}
		?>
				</td>
			</tr>
		</table>
		<?php
		echo "<script>window.opener.location='Modif_Annonce.php?Mode=M&Id=".$_POST['Id']."';</script>";
		echo "<script>window.opener.opener.location='Annonces.php';</script>";
	}
}
else{
if($_SESSION["Langue"]=="FR"){
	$reqSuite="IF(EtatRecrutement<>0,'OFFRE',IF(EtatApprobation=1 && OuvertureAutresPlateformes=0,'OFFRE','BESOIN')) AS Etat, 
		IF(EtatValidation=0,'',
			IF(EtatValidation=-1,'',
				IF(EtatValidation=1 && EtatApprobation=0,'',
					IF(EtatValidation=1 && EtatApprobation=-1,'',
							IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=0,IF(EtatPoste=0,'Poste ouvert',IF(EtatPoste=1,'Poste pourvu',IF(EtatPoste=2,'Poste non pourvu',IF(EtatPoste=3,'Poste pourvu partiellement','Poste annulé')))),
							  IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=1,'',
								IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'',IF(EtatPoste=0,'Poste ouvert',IF(EtatPoste=1,'Poste pourvu',IF(EtatPoste=2,'Poste non pourvu',IF(EtatPoste=3,'Poste pourvu partiellement','Poste annulé')))))
								)
							)
						)
					)
				)
			) AS Statut2,
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
	$reqSuite="IF(EtatRecrutement<>0,'OFFER',IF(EtatApprobation=1 && OuvertureAutresPlateformes=0,'OFFER','NEED')) AS Etat, 
		IF(EtatValidation=0,'',
			IF(EtatValidation=-1,'',
				IF(EtatValidation=1 && EtatApprobation=0,'',
					IF(EtatValidation=1 && EtatApprobation=-1,'',
							IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=0,IF(EtatPoste=0,'Open post',IF(EtatPoste=1,'Position filled',IF(EtatPoste=2,'Position not filled',IF(EtatPoste=3,'Position partially filled','Position canceled')))),
								IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=1,'',
									IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'',IF(EtatPoste=0,'Open post',IF(EtatPoste=1,'Position filled',IF(EtatPoste=2,'Position not filled',IF(EtatPoste=3,'Position partially filled','Position canceled')))))
								)
							)
						)
					)
				)
			) AS Statut2,
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
$requete="SELECT Id,DateDemande,Id_Demandeur,Id_Prestation,Metier,Nombre,Lieu,RaisonRefus,Suppr,
			".$reqSuite."
			CONCAT(Metier,'-',
			Lieu,'-',
			Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',DATE_FORMAT(DateDemande,'%d%m%y')
			) AS Ref,RaisonRefus,RaisonRefusRecrutement,RaisonRefusApprobation,
			EtatApprobation,EtatRecrutement,Programme,CreationPoste,CategorieProf,IGD,Salaire,
			DescriptifPoste,SavoirFaire,SavoirEtre,Prerequis,
			DateBesoin,Duree,PosteDefinitif,Id_Prestation,Id_Domaine,Id_TypeHoraire,
			(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
			(SELECT Libelle FROM recrut_domaine WHERE Id=Id_Domaine) AS Domaine,Langue,
			(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
			(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,EtatValidation
	FROM recrut_annonce
	WHERE recrut_annonce.Id=".$_GET['Id'] ;
$result=mysqli_query($bdd,$requete);
$row=mysqli_fetch_array($result);

if($_GET['Mode']=="V" || $_GET['Mode']=="Mo"){
	$req="SELECT Id,Id_Plateforme,Id_Prestation,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
	(SELECT Nom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Nom,
	(SELECT Prenom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Prenom,
	(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
	(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
	DateCreation, LEFT(HeureCreation,8)	AS HeureCreation,Tel,Mail,MailPro,Responsable,Motivation,CV,CompetencesSpecifiques,PosteOccupe,
	CompetencesAcquises,Experiences,Diplomes,Langue1,NiveauLangue1,Langue2,NiveauLangue2,Langue3,NiveauLangue3
	FROM recrut_candidature 
	WHERE Id=".$_GET['Id_Candidat']." 
	ORDER BY DateCreation, HeureCreation ";
	$result=mysqli_query($bdd,$req);
	$rowCandidat=mysqli_fetch_array($result);
}
$Etat="";
$CouleurEtat="#ffffff";
$NumRefus=2;
$EstRefuse=0;

$typedate="date";
$modifiable="";
$selection="";

if($row['EtatRecrutement']<>0){$Couleur="#ecf943";}
else{$Couleur="#6c94d0";}
					
$typedateRH="text";
$modifiableRecrut="readonly='readonly'";
?>

<form id="formulaire" class="test" enctype="multipart/form-data" action="Postuler.php" method="post" onsubmit=" return VerifChampsPostuler();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
	<input type="hidden" name="Id_Candidat" id="Id_Candidat" value="<?php if($_GET['Mode']=="Mo"){echo $_GET['Id_Candidat'];} ?>" />
	<input type="hidden" name="Ref" id="Ref" value="<?php echo $row['Ref']; ?>" />
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td align="center" style="border:1px solid black;">
								<img width="150px" src="../../Images/Logos/Logo_AAA_FR.png" /> 
							</td>
							<td colspan="8" bgcolor="#2e5496" style="color:#ffffff;font-size:16px;border:1px solid black;" align="center" class="Libelle">
							<?php 
								if($_SESSION["Langue"]=="FR"){echo "FORMULAIRE DE CANDIDATURE POSTE EN INTERNE";}else{echo "INTERNAL POST APPLICATION FORM";}
							?>
							</td>
						</tr>
						<tr>
							<td colspan="10" style="color:#2c538b;font-size:14px;border-bottom:1px solid black;" align="center" class="Libelle">
							<?php 
								if($_SESSION["Langue"]=="FR"){echo "Pour postuler au poste qui a retenu votre attention, merci de bien vouloir compléter impérativement ce formulaire de candidature.";}else{echo "To apply for the position that caught your attention, please be sure to complete this application form.";}
							?>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" rowspan="5" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Informations salarié";}else{echo "Employee information";} ?></td>
							<td width="10%" class="Libelle" bgcolor="#b9c5c9" >&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Nom";}else{echo "Last name";} ?></td>
							<td width="10%" class="Libelle" bgcolor="#b9c5c9">&nbsp;<?php if($_GET['Mode']=="M" || $_GET['Mode']=="Mo"){echo $_SESSION['Nom'];}else{echo $rowCandidat['Nom'];} ?></td>
							<td width="10%" class="Libelle" bgcolor="#b9c5c9" >&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prénom";}else{echo "First name";} ?></td>
							<td width="10%" class="Libelle" bgcolor="#b9c5c9">&nbsp;<?php if($_GET['Mode']=="M" || $_GET['Mode']=="Mo"){echo $_SESSION['Prenom'];}else{echo $rowCandidat['Prenom'];} ?></td>
							<td class="Libelle" width="8%" bgcolor="#cad8ee" style="color:#2c538b;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Email perso.";}else{echo "Personal email";}?> : </td>
							<td width="10%">
								&nbsp;
								<?php if($_GET['Mode']=="M" || $_GET['Mode']=="Mo"){ ?>
								<input style="width:200px" name="Email" id="Email" value="<?php if($_GET['Mode']=="Mo"){echo stripslashes($rowCandidat['Mail']);} ?>"/>
								<?php }
								else{
									echo stripslashes($rowCandidat['Mail']);
								}
								?>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle" width="8%" bgcolor="#cad8ee" style="color:#2c538b;">&nbsp;<?php if($LangueAffichage=="FR"){echo "N° téléphone";}else{echo "Telephone number";}?> : </td>
							<td width="10%">
								&nbsp;
								<?php if($_GET['Mode']=="M" || $_GET['Mode']=="Mo"){ ?>
								<input style="width:100px" name="Tel" id="Tel" type="tel" value="<?php if($_GET['Mode']=="Mo"){echo stripslashes($rowCandidat['Tel']);} ?>"/>
								<?php }
								else{
									echo stripslashes($rowCandidat['Tel']);
								}
								?>
							</td>
							<td class="Libelle" width="10%" bgcolor="#cad8ee" style="color:#2c538b;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Poste occupé <br>actuellement";}else{echo "Position currently <br>occupied";}?> : </td>
							<td width="10%">
								&nbsp;
								<?php if($_GET['Mode']=="M" || $_GET['Mode']=="Mo"){ ?>
								<input style="width:150px" name="PosteOccupe" id="PosteOccupe" type="text" value="<?php if($_GET['Mode']=="Mo"){echo stripslashes($rowCandidat['PosteOccupe']);} ?>"/>
								<?php }
								else{
									echo stripslashes($rowCandidat['PosteOccupe']);
								}
								?>
							</td>
							<td class="Libelle" width="8%" bgcolor="#cad8ee" style="color:#2c538b;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Email pro.";}else{echo "Professional email";}?> : </td>
							<td width="10%">
								&nbsp;
								<?php if($_GET['Mode']=="M" || $_GET['Mode']=="Mo"){ ?>
								<input style="width:200px" name="EmailPro" id="EmailPro" value="<?php if($_GET['Mode']=="Mo"){echo stripslashes($rowCandidat['MailPro']);} ?>"/>
								<?php }
								else{
									echo stripslashes($rowCandidat['MailPro']);
								}
								?>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" bgcolor="#cad8ee" style="color:#2c538b;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?></td>
							<td width="10%">&nbsp;
							<?php if($_GET['Mode']=="M" || $_GET['Mode']=="Mo"){ ?>
							<select style="width:90px;" name="plateforme" id="plateforme" onchange="RechargerPrestationPostule()">
							<?php
							$requete="SELECT Id, Libelle
								FROM new_competences_plateforme
								WHERE Id IN (1,3,4,5,9,10,13,17,19,23,24,27)
								ORDER BY Libelle ASC";
							$result=mysqli_query($bdd,$requete);
							$nb=mysqli_num_rows($result);
							echo "<option name='0' value='0' Selected></option>";
							if ($nb > 0)
							{
								while($row2=mysqli_fetch_array($result))
								{
									
									$selected="";
									if($_GET['Mode']=="Mo"){
										if($rowCandidat['Id_Plateforme']==$row2['Id']){$selected="selected";}
									}
									echo "<option value='".$row2['Id']."' ".$selected.">".stripslashes($row2['Libelle'])."</option>\n";
								}
							 }
							 ?>
							</select>
							<?php }
							else{
								echo stripslashes($rowCandidat['Plateforme']);
							}
							?>
							</td>
							<td width="10%" class="Libelle" bgcolor="#cad8ee" style="color:#2c538b;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
							<td width="10%">&nbsp;
							<?php if($_GET['Mode']=="M"  || $_GET['Mode']=="Mo"){ ?>
							<select name="Id_PrestationPole" id="Id_PrestationPole" class="Id_PrestationPole" style="width:100px">
								<?php
									$requeteSite="SELECT DISTINCT Id, LEFT(Libelle,7) AS Libelle, Id_Plateforme
										FROM new_competences_prestation
										WHERE Active=0
										ORDER BY Libelle";
									$resultsite=mysqli_query($bdd,$requeteSite);
									$i=0;
									
									while($rowsite=mysqli_fetch_array($resultsite))
									{
										
										if($_GET['Mode']=="Mo"){
											if($rowCandidat['Id_Plateforme']==$rowsite['Id_Plateforme']){
												$selected="";
												if($rowCandidat['Id_Prestation']==$rowsite['Id']){$selected="selected";}
												echo "<option value='".$rowsite['Id']."' ".$selected.">";
												echo str_replace("'"," ",stripslashes($rowsite['Libelle']))."</option>\n";
											}
										}
										else{
											echo "<option value='".$rowsite['Id']."' >";
											echo str_replace("'"," ",stripslashes($rowsite['Libelle']))."</option>\n";
										}
										
										echo "<script>Liste_PrestaPolePostule[".$i."] = new Array(".$rowsite['Id'].",'".str_replace("'"," ",$rowsite['Libelle'])."',".$rowsite['Id_Plateforme'].");</script>";
										$i++;
									}
								?>
							</select>
							<?php }
							else{
								echo stripslashes($rowCandidat['Prestation']);
							}
							?>
							</td>
							<td class="Libelle" width="8%" bgcolor="#cad8ee" style="color:#2c538b;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Responsable actuel";}else{echo "Responsible";}?> : </td>
							<td width="20%">
								&nbsp;
								<?php if($_GET['Mode']=="M" || $_GET['Mode']=="Mo"){ ?>
								<input style="width:190px" name="responsable" id="responsable" value="<?php if($_GET['Mode']=="Mo"){echo stripslashes($rowCandidat['Responsable']);} ?>"/>
								<?php }
								else{
									echo stripslashes($rowCandidat['Responsable']);
								}
								?>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Poste pour lequel vous postulez";}else{echo "Position for which you are applying";} ?></td>
							<td width="10%" class="Libelle" bgcolor="#b9c5c9">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Métier du poste";}else{echo "Job";} ?></td>
							<td width="10%" class="Libelle" bgcolor="#b9c5c9">&nbsp;<?php echo stripslashes($row['Metier']); ?></td>
							<td width="10%" class="Libelle" bgcolor="#b9c5c9">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation de destination";}else{echo "Destination operating unit";} ?></td>
							<td width="10%" class="Libelle" bgcolor="#b9c5c9">&nbsp;<?php echo stripslashes($row['Plateforme']); ?></td>
							<td width="10%" class="Libelle" bgcolor="#b9c5c9" >&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Ref du poste";}else{echo "Job ref";} ?></td>
							<td width="10%" class="Libelle" bgcolor="#b9c5c9" colspan="3">&nbsp;<?php echo stripslashes($row['Ref']); ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" bgcolor="#2e5496" valign="center" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Commentaires éventuels salarié";}else{echo "Possible employee comments";} ?></td>
							<td width="30%" colspan="8">
								&nbsp;
								<?php if($_GET['Mode']=="M" || $_GET['Mode']=="Mo"){ ?>
								<textarea name="motivation" id="motivation" cols="150" rows="5" style="resize:none;"><?php if($_GET['Mode']=="Mo"){echo stripslashes($rowCandidat['Motivation']);} ?></textarea>
								<?php }
								else{
									echo nl2br(stripslashes($rowCandidat['Motivation']));
								}
								?>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" bgcolor="#2e5496" valign="center" style="color:#ffffff;"><?php if($LangueAffichage=="FR"){echo "Vous avez la possibilité en complément de ces informations de joindre votre CV";}else{echo "You have the possibility in addition to this information to attach your CV";}?></td>
							<?php if($_GET['Mode']=="M" || $_GET['Mode']=="Mo"){ ?>
							<td><input name="fichier" type="file" onChange="CheckFichier();">
							<?php 
							if($_GET['Mode']=="Mo"){
								if($rowCandidat['CV']!="")
								{
								?>
								<td colspan="3">&nbsp;
									<?php 
										if($LangueAffichage=="FR"){
											echo "<a class=\"Info\" href=\"".$DirFichier."/".$rowCandidat['CV']."\" target=\"_blank\">Ouvrir</a>";
										}
										else{
											echo "<a class=\"Info\" href=\"".$DirFichier."/".$rowCandidat['CV']."\" target=\"_blank\">Open</a>";
										}
									?>
									<input type="hidden" name="fichieractuel" value="<?php echo $rowCandidat['CV'];?>">
									<input type="checkbox" name="SupprFichier" onClick="CheckFichier();"><?php if($LangueAffichage=="FR"){echo "Supprimer le fichier";}else{echo "Delete file";}?>
								</td>
								<?php
								}
							}
							?>
							</td>
							<?php 
							}
							else{
							?>
								<?php
								if($rowCandidat['CV']!="")
								{
								?>
								<td>&nbsp;
									<?php 
										if($LangueAffichage=="FR"){
											echo "<a class=\"Info\" href=\"".$DirFichier."/".$rowCandidat['CV']."\" target=\"_blank\">Ouvrir</a>";
										}
										else{
											echo "<a class=\"Info\" href=\"".$DirFichier."/".$rowCandidat['CV']."\" target=\"_blank\">Open</a>";
										}
									?>
									<input type="hidden" name="fichieractuel" value="<?php echo $rowCandidat['CV'];?>">
								</td>
								<?php
								}
							}
							?>
						</tr>
						<?php if($_GET['Mode']=="M"){ ?>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="9" align="center">
								<div id="Ajouter">
								</div>
								<input class="Bouton" type="submit" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer">
							</td>
						</tr>
						<?php } 
							elseif($_GET['Mode']=="Mo"){
						?>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="8" align="center">
								<input class="Bouton" type="submit" id="btnModifier" name="btnModifier" value="Modifier">
							</td>
							<td align="right">
								<input class="Bouton" type="submit" id="btnAnnuler" name="btnAnnuler" value="Annuler">
							</td>
						</tr>
						<?php
							}
						?>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
</table>
</form>
<?php
if($_GET['Mode']<>"Mo"){
echo "<script>RechargerPrestationPostule();</script>";
}
}
?>
</body>
</html>
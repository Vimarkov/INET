<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="Besoin.js?t=<?php echo time(); ?>"></script>
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
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
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
		
		$requete="UPDATE talentboost_annonce 
			SET Id_Domaine=".$_POST['domaine'].",
			Programme='".addslashes($_POST['programme'])."',
			Lieu='".addslashes($_POST['lieu'])."',
			Metier='".addslashes($_POST['metier'])."',
			Nombre=".$_POST['nombr'].",
			ValidationContratDG=".$_POST['validationDG'].",
			DateBesoin='".TrsfDate_($_POST['dateSouhaitee'])."',
			PosteDefinitif=".$_POST['posteDefinitif'].",
			DescriptifPoste='".addslashes($_POST['DescriptifPoste'])."',
			SavoirFaire='".addslashes($_POST['savoirfaire'])."',
			SavoirEtre='".addslashes($_POST['savoiretre'])."',
			EtatPoste=".$_POST['statutPoste'].",
			Langue='".addslashes($_POST['Langues'])."',
			Diplome='".addslashes($_POST['Diplome'])."',
			Prerequis='".addslashes($_POST['prerequis'])."',
			CategorieProf='".addslashes($_POST['categorieProfessionnelle'])."',
			DateRecrutementMAJ='".date('Y-m-d')."',
			Id_PersonneAContacter=".$_POST['personneAContacter'].",
			MotifDemande='".addslashes($_POST['MotifDemande'])."'
			WHERE Id=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$requete);
		
		if($_POST['validationDGOLD']<>$_POST['validationDG']){
			if($_POST['validationDG']<>0){
				$req="UPDATE talentboost_annonce SET DateValidationDG='".date('Y-m-d')."', DateActualisation='0001-01-01' WHERE Id=".$_POST['Id']." ";
				$resultAdd=mysqli_query($bdd,$req);
			}
			else{
				$req="UPDATE talentboost_annonce SET DateValidationDG='0001-01-01', DateActualisation='0001-01-01' WHERE Id=".$_POST['Id']." ";
				$resultAdd=mysqli_query($bdd,$req);
			}
		}
		else{
			$req="UPDATE talentboost_annonce SET DateActualisation='".date('Y-m-d')."' WHERE Id=".$_POST['Id']." ";
			$resultAdd=mysqli_query($bdd,$req);
		}
		
		$req="DELETE FROM talentboost_annonce_savoiretre WHERE Id_Annonce=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$req);
		
		$req="SELECT Id, Libelle FROM talentboost_savoiretre WHERE Suppr=0 ORDER BY Libelle ";
		$result=mysqli_query($bdd,$req);
		$nbenreg=mysqli_num_rows($result);
		if($nbenreg>0)
		{
			while($rowSE=mysqli_fetch_array($result))
			{
				if(isset($_POST['savoiretres_'.$rowSE['Id']])){
					$req="INSERT INTO talentboost_annonce_savoiretre (Id_Annonce,Id_SavoirEtre) VALUES (".$_POST['Id'].",".$rowSE['Id'].") ";
					$resultAdd=mysqli_query($bdd,$req);
				}
			}
		}
		
		$req="DELETE FROM talentboost_annonce_prerequis WHERE Id_Annonce=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$req);
		
		$req="SELECT Id, Libelle FROM talentboost_prerequis WHERE Suppr=0 ORDER BY Libelle ";
		$result=mysqli_query($bdd,$req);
		$nbenreg=mysqli_num_rows($result);
		if($nbenreg>0)
		{
			while($rowSE=mysqli_fetch_array($result))
			{
				if(isset($_POST['prerequis_'.$rowSE['Id']])){
					$req="INSERT INTO talentboost_annonce_prerequis (Id_Annonce,Id_Prerequis) VALUES (".$_POST['Id'].",".$rowSE['Id'].") ";
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
				$requeteUpt="UPDATE talentboost_annonce SET";
					$requeteUpt.=" FicheMetier='".$Fichier."'";
					$requeteUpt.=" WHERE Id=".$_POST['Id'];
					$resultUpt=mysqli_query($bdd,$requeteUpt);
			}
		}
		
	}
	
	if($_POST['validationDGOLD']<>$_POST['validationDG']){
		if($_POST['validationDG']==1){
			creerMail("OFFRE EMPLOI",$_SESSION['Langue'],$_POST['Id']);
		}
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET){
	if($_GET['Mode']=="S"){
		$requete="UPDATE talentboost_annonce 
			SET Suppr=1,
			Id_Suppr=".$_SESSION['Id_Personne'].",
			DateSuppr='".date('Y-m-d')."'
			WHERE Id=".$_GET['Id']." ";
		$result=mysqli_query($bdd,$requete);
		
		echo "<script>FermerEtRecharger();</script>";
	}
}
if($_SESSION["Langue"]=="FR"){
	$reqSuite="IF(ValidationContratDG<>0,'OUI','NON') AS Etat,
			IF(ValidationContratDG=0,'BESOIN EN ATTENTE VALIDATION DG',
				IF(ValidationContratDG=-1,'BESOIN REFUSÉ PAR LA DG','OFFRE')
			) AS Statut,
			IF(ValidationContratDG=0,'',
					IF(ValidationContratDG=-1,'',
						IF(EtatPoste=0,'Poste ouvert',IF(EtatPoste=1,'Poste pourvu',IF(EtatPoste=2,'Poste non pourvu',IF(EtatPoste=3,'Poste pourvu partiellement',IF(EtatPoste=4,'Demande clôturée','Poste annulé')))))
					)
				) AS Statut2,";
}
else{
	$reqSuite="IF(ValidationContratDG<>0,'YES','NO') AS Etat,
				IF(ValidationContratDG=0,'NEED PENDING CEO VALIDATION',
					IF(ValidationContratDG=-1,'NEED REFUSED BY THE DG','OFFER')
				) AS Statut,
				IF(ValidationContratDG=0,'',
					IF(ValidationContratDG=-1,'',
						IF(EtatPoste=0,'Open post',IF(EtatPoste=1,'Position filled',IF(EtatPoste=2,'Position not filled',IF(EtatPoste=3,'Position partially filled',IF(EtatPoste=4,'Request closed','Position canceled')))))
					)
				) AS Statut2,	";
}
$requete="SELECT Id,DateDemande,Id_Demandeur,Id_Prestation,Metier,Nombre,Lieu,RaisonRefus,Suppr,Diplome,ValidationContratDG,
			".$reqSuite."
			CONCAT(Metier,'-',
			Lieu,'-',
			Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',DATE_FORMAT(DateDemande,'%d%m%y')
			) AS Ref,RaisonRefus,RaisonRefusRecrutement,RaisonRefusApprobation,EtatPoste,Id_PersonneAContacter,
			EtatApprobation,EtatRecrutement,Programme,CreationPoste,CategorieProf,MotifDemande,
			DescriptifPoste,SavoirFaire,SavoirEtre,Prerequis,OuvertureAutresPlateformes,CandidatsRetenus,
			DateBesoin,Duree,PosteDefinitif,Id_Prestation,Id_Domaine,Id_TypeHoraire,DateRecrutementMAJ,
			(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
			(SELECT Libelle FROM talentboost_domaine WHERE Id=Id_Domaine) AS Domaine,Langue,FicheMetier,
			(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
			Id_Plateforme,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,EtatValidation
	FROM talentboost_annonce
	WHERE talentboost_annonce.Id=".$_GET['Id'] ;
$result=mysqli_query($bdd,$requete);
$row=mysqli_fetch_array($result);

$Etat="";
$CouleurEtat="#ffffff";
$NumRefus=2;
$EstRefuse=0;

$typedate="date";
$modifiable="";
$selection="";

if($row['ValidationContratDG']>0){$Couleur="#ecf943";}
elseif($row['ValidationContratDG']<0){$Couleur="#f55645";}
else{$Couleur="#6c94d0";}
					
$typedateRH="text";
$modifiableRecrut="readonly='readonly'";
?>

<form id="formulaire" class="test" enctype="multipart/form-data" action="Modif_Besoin.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
	<input type="hidden" name="ValiderRefuser" id="ValiderRefuser" value="" />
	<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
	<input type="hidden" name="statutPosteOLD" id="statutPosteOLD" value="<?php echo $row['EtatPoste']; ?>" />
	<input type="hidden" name="validationDGOLD" id="validationDGOLD" value="<?php echo $row['ValidationContratDG']; ?>" />
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="10%" bgcolor="<?php echo $Couleur;?>" class="Libelle" colspan="8" align="center">&nbsp;<?php echo $row['Statut']; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
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
										FROM talentboost_domaine
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
							<td class="Libelle" width="10%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Statut ";}else{echo "Status ";}?> : </td>
							<td width="18%" >
								&nbsp;<select name="categorieProfessionnelle" id="categorieProfessionnelle">
									<option value="" <?php if(stripslashes($row['CategorieProf'])==""){echo "selected";} ?>></option>
									<option value="Agent de maitrise" <?php if(stripslashes($row['CategorieProf'])=="Agent de maitrise"){echo "selected";} ?>>Agent de maitrise</option>
									<option value="ART 4 BIS" <?php if(stripslashes($row['CategorieProf'])=="ART 4 BIS"){echo "selected";} ?>>ART 4 BIS</option>
									<option value="Cadre" <?php if(stripslashes($row['CategorieProf'])=="Cadre"){echo "selected";} ?>>Cadre</option>
									<option value="Employé" <?php if(stripslashes($row['CategorieProf'])=="Employé"){echo "selected";} ?>>Employé</option>
									<option value="Ouvrier" <?php if(stripslashes($row['CategorieProf'])=="Ouvrier"){echo "selected";} ?>>Ouvrier</option>
									<option value="Technicien" <?php if(stripslashes($row['CategorieProf'])=="Technicien"){echo "selected";} ?>>Technicien</option>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
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
								&nbsp;<textarea name="Diplome" id="Diplome" cols="90" rows="8" style="resize:none;"><?php echo stripslashes($row['Diplome']); ?></textarea>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" valign="top" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Savoir faire :<br><br>Qualités professionnelles<br><br>Polyvalence métier<br><br>Compétences techniques<br><br>Compétences managériales";}else{echo "Know-how :<br><br>Professional skills<br><br>-Experience<br><br>Business versatility<br><br>Technical skills<br><br>Managerial skills";} ?></td>
							<td width="30%" colspan="3" valign="top">
								&nbsp;<textarea name="savoirfaire" id="savoirfaire" cols="90" rows="12" style="resize:none;"><?php echo stripslashes($row['SavoirFaire']); ?></textarea>
							</td>
							<td width="10%" rowspan="3" class="Libelle" valign="top" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Savoir être :";}else{echo "know how to be :";} ?></td>
							<td width="30%" rowspan="3" colspan="3" valign="top">
								&nbsp;
								<table width="100%">
									<tr>
										<td width="40%" valign="top">
											<table width="100%">
												<?php
													$req="SELECT Id, Libelle FROM talentboost_savoiretre WHERE Suppr=0 ORDER BY Libelle ";
													$result=mysqli_query($bdd,$req);
													$nbenreg=mysqli_num_rows($result);
													if($nbenreg>0)
													{
														while($rowSE=mysqli_fetch_array($result))
														{
															$req="SELECT Id FROM talentboost_annonce_savoiretre WHERE Id_Annonce=".$row['Id']." AND Id_SavoirEtre=".$rowSE['Id']." ";
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
										<td width="10%" valign="top">
											&nbsp;
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" valign="top" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prérequis :";}else{echo "Prerequisites :";} ?></td>
							<td width="30%" colspan="3" valign="top">
								&nbsp;
								<table width="100%">
									<tr>
										<td width="40%" valign="top">
											<table width="100%">
												<?php
													$req="SELECT Id, Libelle FROM talentboost_prerequis WHERE Suppr=0 ORDER BY Libelle ";
													$result=mysqli_query($bdd,$req);
													$nbenreg=mysqli_num_rows($result);
													if($nbenreg>0)
													{
														while($rowSE=mysqli_fetch_array($result))
														{
															$req="SELECT Id FROM talentboost_annonce_prerequis WHERE Id_Annonce=".$row['Id']." AND Id_Prerequis=".$rowSE['Id']." ";
															$resultASE=mysqli_query($bdd,$req);
															$nbASE=mysqli_num_rows($resultASE);
															
															$checked="";
															if($nbASE>0){$checked="checked";}
															
															echo "<tr><td>";
															echo"<input type='checkbox' class='prerequis' name='prerequis_".$rowSE['Id']."' ".$checked." value='".$rowSE['Id']."'>".stripslashes($rowSE['Libelle'])." ";
															echo "</td></tr>";
														}
													}
												?>
											</table>
										</td>
										<td width="50%" valign="top">
											<textarea name="prerequis" id="prerequis" cols="45" rows="9" style="resize:none;"><?php echo stripslashes($row['Prerequis']); ?></textarea>
										</td>
										<td width="10%" valign="top">
											&nbsp;
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
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle" width="10%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Personne à contacter";}else{echo "Contact person";}?> </td>
							<td width="18%" colspan="3">
								<?php 
								$requetePersonne="SELECT Id, Nom, Prenom 
											FROM new_rh_etatcivil
											WHERE Id IN (SELECT Id_Personne
												FROM new_competences_personne_poste_plateforme
												WHERE Id_Plateforme NOT IN(11,14) 
												AND Id_Poste IN (".$IdPosteAssistantRH.",".$IdPosteResponsableRH.",".$IdPosteResponsableRecrutement.",".$IdPosteRecrutement.")
												)
											OR Id IN (SELECT Id_Personne
												FROM new_competences_personne_poste_prestation
												WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) NOT IN(11,14) 
												AND Id_Poste IN (".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.",".$IdPosteResponsablePlateforme.")
												)
											OR Id=".$row['Id_PersonneAContacter']."
											ORDER BY Nom, Prenom";
								$resultPersonne=mysqli_query($bdd,$requetePersonne);
								?>
								&nbsp;<select name="personneAContacter" id="personneAContacter">
										<option value="0"></option>
										<?php
											while($rowPersonne=mysqli_fetch_array($resultPersonne))
											{
												$selected="";
												if($rowPersonne['Id']==$row['Id_PersonneAContacter']){$selected="selected";}
												echo "<option value='".$rowPersonne['Id']."' ".$selected." >".stripslashes($rowPersonne['Nom']." ".$rowPersonne['Prenom'])."</option>\n";
											}
											mysqli_data_seek($resultPersonne,0);
										?>
									</select>
							</td>
							<td class="Libelle" width="10%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Validation du contrat papier par la DG :";}else{echo "Validation of the paper contract by the DG :";}?> </td>
							<td width="18%" >
								&nbsp;<select name="validationDG" id="validationDG">
									<option value="0" <?php if(stripslashes($row['ValidationContratDG'])=="0"){echo "selected";} ?> ></option>
									<option value="-1" <?php if(stripslashes($row['ValidationContratDG'])=="-1"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";}?></option>
									<option value="1" <?php if(stripslashes($row['ValidationContratDG'])=="1"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}?></option>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<?php 
						$display="style='display:none;'";
						if($row['ValidationContratDG']>0){
							if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
								$display="";
							}
						}
						?>
						<tr <?php echo $display; ?>>
							<td width="8%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Statut du poste";}else{echo "Job status";}?> : </td>
							<td width="18%">
								&nbsp;<select name="statutPoste" id="statutPoste">
									<option value="0" <?php if($row['EtatPoste']==0){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste ouvert";}else{echo "Open post";}?></option>
									<option value="1" <?php if($row['EtatPoste']==1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste pourvu";}else{echo "Position filled";}?></option>
									<option value="2" <?php if($row['EtatPoste']==2){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste non pourvu";}else{echo "Closed post";}?></option>
									<option value="3" <?php if($row['EtatPoste']==3){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste pourvu partiellement";}else{echo "Position partially filled";}?></option>
									<option value="-1" <?php if($row['EtatPoste']==-1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste annulé";}else{echo "Position canceled";}?></option>
									<option value="4" <?php if($row['EtatPoste']==-4){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Demande clôturée";}else{echo "Request closed";} ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="8" align="center">
								<div id="Ajouter">
								</div>
								<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="EnregistrerModif('Responsable')">
							</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
</table>
</form>
	
</body>
</html>
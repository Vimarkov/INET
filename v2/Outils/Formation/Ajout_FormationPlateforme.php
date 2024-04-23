<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Ajout formation plateforme</title><meta name="robots" content="noindex">
	
	<?php
			//Le sablier header
			include '../Sablier_header.php';
	?>
		
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" charset="utf-8" src="Formation.js"></script>
	<script>
		function FermerEtRecharger(motCles,Id_Plateforme){
			window.opener.location = "Liste_FormationPlateforme.php?motcles="+motCles+"&Id_Plateforme="+Id_Plateforme;
			window.close();
		}
	</script>
</head>
<body>

<?php
//Pour le sablier
include '../Sablier_Body.php';
?>
<script type="text/javascript">sablier();</script>
<?php 
if($_POST){
	if(isset($_POST['transformer'])){
		if($_POST['id_Pla']<>0){
			$requete="UPDATE form_formation SET Id_Plateforme=0,Id_Personne_MAJ=".$IdPersonneConnectee.", Date_MAJ='".date('Y-m-d')."'  WHERE Id=".$_POST['id']."";
			$result=mysqli_query($bdd,$requete);
		}
		echo "<script charset='utf-8'>FermerEtRecharger(\"".$_POST['motcles']."\",\"".$_POST['Id_Plateforme']."\");</script>";
	}
	elseif(isset($_POST['valider'])){
		if($_POST['Mode']=="A"){
			$requete="INSERT INTO form_formation (Id_Plateforme, Reference,Categorie, Id_TypeFormation, Tuteur, Recyclage, Obligatoire, Id_Personne_MAJ, Date_MAJ,DateCreation) ";
			$requete.=" VALUES (".$_POST['Id_Plateforme'].",'".addslashes($_POST['libelle'])."','".addslashes($_POST['categorie2'])."',".$_POST['Id_TypeFormation'].",".$_POST['Tuteur'].",".$_POST['Recyclage'].",".$_POST['Obligatoire'];
			$requete.=",".$IdPersonneConnectee.",'".date('Y-m-d')."','".date('Y-m-d')."')";
			$result=mysqli_query($bdd,$requete);
			$IdForm = mysqli_insert_id($bdd);
			
			$DirFichier="Docs/Formation/".$IdForm;
			if(!file_exists($DirFichier)){
				$res = mkdir_ftp($DirFichier, 0773);
				
				if(!$res)
					echo 'Echec lors de la création des répertoires...';
			}
			$DirFichier="Docs/Formation/".$IdForm."/";
			
			if($IdForm>0){
				//Ajout Parametres
				$cout=0;
				$coutInterimaire=0;
				$duree=0;
				$nbJour=1;
				$coutR=0;
				$coutInterimaireR=0;
				$organisme=0;
				$dureeR=0;
				$nbJourR=1;
				$coutTarifGroupe=0;
				$coutTarifGroupeR=0;
				if($_POST['cout']<>""){$cout=$_POST['cout'];}
				if($_POST['coutInterimaire']<>""){$coutInterimaire=$_POST['coutInterimaire'];}
				if($_POST['coutR']<>""){$coutR=$_POST['coutR'];}
				if($_POST['coutInterimaireR']<>""){$coutInterimaireR=$_POST['coutInterimaireR'];}
				if($_POST['coutTarifGroupe']<>""){$coutTarifGroupe=$_POST['coutTarifGroupe'];}
				if($_POST['coutTarifGroupeR']<>""){$coutTarifGroupeR=$_POST['coutTarifGroupeR'];}
				if($_POST['duree']<>"")
					$duree = str_replace(":", ".", $_POST['duree']);
				if($_POST['nbJour']<>"" && $_POST['nbJour']<>"0"){$nbJour=$_POST['nbJour'];}
				if($_POST['dureeR']<>"")
					$dureeR = str_replace(":", ".", $_POST['dureeR']);
				if($_POST['nbJourR']<>"" && $_POST['nbJourR']<>"0"){$nbJourR=$_POST['nbJourR'];}
				if($_POST['organisme']<>""){$organisme=$_POST['organisme'];}
				$requete="INSERT INTO form_formation_plateforme_parametres (Id_Formation,Id_Plateforme, Id_Langue, Id_Langue_Document, CoutSalarieAAA,CoutInterimaire,CoutTarifGroupe, Duree, CoutSalarieAAARecyclage, CoutInterimaireRecyclage,CoutTarifGroupeRecyclage, DureeRecyclage,NbJour,NbJourRecyclage,Id_Organisme,BesoinParametrableUniquementAF, Id_Personne_MAJ, Date_MAJ) ";
				$requete.=" VALUES (".$IdForm.",".$_POST['Id_Plateforme'].",".$_POST['LangueAffichage'].",".$_POST['LangueDocuments'].",".$cout.",".$coutInterimaire.",".$coutTarifGroupe.",".$duree.",".$coutR.",".$coutInterimaireR.",".$coutTarifGroupeR.",".$dureeR.",".$nbJour.",".$nbJourR.",".$organisme.",".$_POST['parametrableAF'].",".$IdPersonneConnectee.",'".date('Y-m-d')."')";
				$result=mysqli_query($bdd,$requete);
				
				//Ajout des documents
				$tab = explode(";",$_POST['lesDocs']);
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req="INSERT INTO form_formation_document (Id_Formation,Id_Document,Id_Personne_MAJ, Date_MAJ) VALUES (".$IdForm.",".substr($valeur,0,-4).",".$IdPersonneConnectee.",'".date('Y-m-d')."')";
						$resultAjour=mysqli_query($bdd,$req);
					 }
				}
				
				//Ajout des formations 
				$tab = explode(";",$_POST['lesFormsCompetences']);
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req="INSERT INTO form_formation_formationcompetence (Id_Formation,Id_FormationCompetence) VALUES (".$IdForm.",".substr($valeur,0,-14).")";
						$resultAjour=mysqli_query($bdd,$req);
					 }
				}
				
				//Ajout des formations QUALIPSO
				$tab = explode(";",$_POST['lesFormsQUALIPSO']);
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req="INSERT INTO form_formation_formationqualipso (Id_Formation,Id_FormationQualipso) VALUES (".$IdForm.",".substr($valeur,0,-12).")";
						$resultAjour=mysqli_query($bdd,$req);
					 }
				}
				
				//Ajout des qualifications et des qcm par qualification
				$tab = explode(";",$_POST['lesQualifs']);
				foreach($tab as $valeur){
					 if($valeur<>""){
						$tabValeur = explode("_",$valeur);
						//Vérifier que la qualif n'existe pas déjà
						$reqVerif="SELECT Id FROM form_formation_qualification WHERE Id_Formation=".$IdForm." AND Suppr=0 AND Id_Qualification=".$tabValeur[0]." ";
						$resultVerif=mysqli_query($bdd,$reqVerif);
						$nbResultaVerif=mysqli_num_rows($resultVerif);
						
						if($nbResultaVerif==0){
							$req="INSERT INTO form_formation_qualification (Id_Formation,Id_Qualification,Id_Personne_MAJ, Date_MAJ,Masquer) VALUES (".$IdForm.",".$tabValeur[0].",".$IdPersonneConnectee.",'".date('Y-m-d')."',".$tabValeur[1].")";
							$resultAjour=mysqli_query($bdd,$req);
							$IdFormationQCM=mysqli_insert_id($bdd);
						}
						else{
							$rowLaQualif=mysqli_fetch_array($resultVerif);
							$IdFormationQCM=$rowLaQualif['Id'];
							
							$req="UPDATE form_formation_qualification SET Masquer=".$tabValeur[1]." WHERE Id=".$IdFormationQCM;
							$resultUpdate=mysqli_query($bdd,$req);
						}
						$tabQCM = explode("!",$tabValeur[2]);
						foreach($tabQCM as $qcm){
							if($qcm<>""){
								$tabQCMLangue = explode("*",$qcm);
								$req="INSERT INTO form_formation_qualification_qcm (Id_Formation_Qualification,Id_QCM,Id_Langue,Id_Personne_MAJ, Date_MAJ) VALUES (".$IdFormationQCM.",".$tabQCMLangue[0].",".$tabQCMLangue[1].",".$IdPersonneConnectee.",'".date('Y-m-d')."')";
								$resultAjour=mysqli_query($bdd,$req);
							}
						}
					 }
				}
				
				//Ajout des informations / langues
				$req="SELECT Id FROM form_langue WHERE Suppr=0 ORDER BY Libelle";
				$resultL=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($resultL);
				if($nbResulta>0){
					while($rowL=mysqli_fetch_array($resultL)){
						if(isset($_POST['Libelle_'.$rowL['Id']])){
							if($_POST['Libelle_'.$rowL['Id']]<>""){
								$Fichier="";
								$FichierRecyclage="";
								$Problem=0;
								$SrcProblem="";

								//****TRANSFERT FICHIER****
								if($_FILES['fichier_I_'.$rowL['Id']]['name']!=""){
									$tmp_file=$_FILES['fichier_I_'.$rowL['Id']]['tmp_name'];
									if(!is_uploaded_file($tmp_file)){$SrcProblem.="Le fichier est introuvable.";$Problem=1;}
									else
									{
										//On vérifie la taille du fichiher
										if(filesize($_FILES['fichier_I_'.$rowL['Id']]['tmp_name'])>$_POST['MAX_FILE_SIZE'])
										{$SrcProblem.="Le fichier est trop volumineux.";$Problem=1;}
										else
										{
											// on copie le fichier dans le dossier de destination
											$name_file=$_FILES['fichier_I_'.$rowL['Id']]['name'];
											$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
											while(file_exists($DirFichier.$name_file)){$name_file="le ".date('j-m-y')." a ".date('H-i-s')." ".$name_file;}
											if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
											{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
											else{$Fichier=$name_file;}
										}
									}
								}
								if($_FILES['fichier_R_'.$rowL['Id']]['name']!="")
								{
									$tmp_file=$_FILES['fichier_R_'.$rowL['Id']]['tmp_name'];
									if(!is_uploaded_file($tmp_file)){$SrcProblem.="Le fichier recyclage est introuvable.";$Problem=1;}
									else
									{
										//On vérifie la taille du fichiher
										if(filesize($_FILES['fichier_R_'.$rowL['Id']]['tmp_name'])>$_POST['MAX_FILE_SIZE'])
										{$SrcProblem.="Le fichier recyclage est trop volumineux.";$Problem=1;}
										else
										{
											// on copie le fichier dans le dossier de destination
											$name_file=$_FILES['fichier_R_'.$rowL['Id']]['name'];
											$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
											while(file_exists($DirFichier.$name_file)){$name_file="le ".date('j-m-y')." a ".date('H-i-s')." ".$name_file;}
											if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
											{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
											else{$FichierRecyclage=$name_file;}
										}
									}
								}
								if($Problem==1){echo "<script>alert('Il y a eu une erreur lors de la copie du fichier joint (".$SrcProblem."). Veuillez vérifier si celui-ci est bien ajouté dans ce que vous venez de créer.');</script>";}
								
								$req="INSERT INTO form_formation_langue_infos (Id_Formation,Id_Langue,Libelle,Description,LibelleRecyclage,DescriptionRecyclage,Fichier,FichierRecyclage,Id_Personne_MAJ, Date_MAJ) VALUES (".$IdForm.",".$rowL['Id'].",'".addslashes($_POST['Libelle_'.$rowL['Id']])."','".addslashes($_POST['Description_'.$rowL['Id']])."','".addslashes($_POST['LibelleR_'.$rowL['Id']])."','".addslashes($_POST['DescriptionR_'.$rowL['Id']])."','".$Fichier."','".$FichierRecyclage."',".$IdPersonneConnectee.",'".date('Y-m-d')."')";
								$resultAjour=mysqli_query($bdd,$req);
							}
						}
					}
				}
				
			}
			echo "<script charset='utf-8'>FermerEtRecharger(\"".$_POST['motcles']."\",\"".$_POST['Id_Plateforme']."\");</script>";
		}
		elseif($_POST['Mode']=="M"){
			if($_POST['id_Pla']<>0){
				$requete="UPDATE form_formation SET ";
				$requete.="Reference='".addslashes($_POST['libelle'])."',";
				$requete.="Categorie='".addslashes($_POST['categorie2'])."',";
				$requete.="Id_TypeFormation=".$_POST['Id_TypeFormation'].",";
				$requete.="Tuteur=".$_POST['Tuteur'].",";
				$requete.="Recyclage=".$_POST['Recyclage'].",";
				$requete.="Obligatoire=".$_POST['Obligatoire'].",";
				$requete.="Id_Personne_MAJ=".$IdPersonneConnectee.",";
				$requete.="Date_MAJ='".date('Y-m-d')."' ";
				$requete.=", Id_Personne_MAJ=".$IdPersonneConnectee."";
				$requete.=", Date_MAJ='".date('Y-m-d')."'";
				$requete.=" WHERE Id=".$_POST['id']."";
				$result=mysqli_query($bdd,$requete);
			}
			$IdForm = $_POST['id'];
			
			$DirFichier="Docs/Formation/".$IdForm;
			if(!file_exists($DirFichier)){
				$res = mkdir_ftp($DirFichier, 0773);
				if(!$res){echo 'Echec lors de la création des répertoires...';}
			}
			$DirFichier="Docs/Formation/".$IdForm."/";
			
			$cout=0;
			$coutInterimaire=0;
			$duree=0;
			$nbJour=1;
			$coutR=0;
			$coutInterimaireR=0;
			$dureeR=0;
			$nbJourR=1;
			$organisme=0;
			$coutTarifGroupe=0;
			$coutTarifGroupeR=0;
			if($_POST['cout']<>""){$cout=$_POST['cout'];}
			if($_POST['coutInterimaire']<>""){$coutInterimaire=$_POST['coutInterimaire'];}
			if($_POST['coutR']<>""){$coutR=$_POST['coutR'];}
			if($_POST['coutInterimaireR']<>""){$coutInterimaireR=$_POST['coutInterimaireR'];}
			if($_POST['duree']<>"")
				$duree = str_replace(":", ".", $_POST['duree']);
			if($_POST['nbJour']<>"" && $_POST['nbJour']<>"0"){$nbJour=$_POST['nbJour'];}
			if($_POST['dureeR']<>"")
				$dureeR = str_replace(":", ".", $_POST['dureeR']);
			if($_POST['nbJourR']<>"" && $_POST['nbJourR']<>"0"){$nbJourR=$_POST['nbJourR'];}
			if($_POST['coutTarifGroupe']<>""){$coutTarifGroupe=$_POST['coutTarifGroupe'];}
			if($_POST['coutTarifGroupeR']<>""){$coutTarifGroupeR=$_POST['coutTarifGroupeR'];}
				
			if($_POST['organisme']<>""){$organisme=$_POST['organisme'];}
			$requete="SELECT Id FROM form_formation_plateforme_parametres WHERE Id_Formation=".$IdForm." AND Id_Plateforme=".$_POST['Id_Plateforme'];
			$result=mysqli_query($bdd,$requete);
			$nbResulta=mysqli_num_rows($result);
			if($nbResulta>0){
				$requete="UPDATE form_formation_plateforme_parametres SET ";
				$requete.="Id_Langue=".$_POST['LangueAffichage'].",";
				$requete.="Id_Langue_Document=".$_POST['LangueDocuments'].",";
				$requete.="CoutSalarieAAA=".$cout.",";
				$requete.="CoutInterimaire=".$coutInterimaire.",";
				$requete.="CoutTarifGroupe=".$coutTarifGroupe.",";
				$requete.="CoutTarifGroupeRecyclage=".$coutTarifGroupeR.",";
				$requete.="Duree=".$duree.",";
				$requete.="CoutSalarieAAARecyclage=".$coutR.",";
				$requete.="CoutInterimaireRecyclage=".$coutInterimaireR.",";
				$requete.="DureeRecyclage=".$dureeR.", ";
				$requete.="NbJour=".$nbJour.",";
				$requete.="Id_Organisme=".$organisme.",";
				$requete.="BesoinParametrableUniquementAF=".$_POST['parametrableAF'].",";
				$requete.="NbJourRecyclage=".$nbJourR." ";
				$requete.=", Id_Personne_MAJ=".$IdPersonneConnectee."";
				$requete.=", Date_MAJ='".date('Y-m-d')."'";
				$requete.=" WHERE Id_Formation=".$IdForm." AND Id_Plateforme=".$_POST['Id_Plateforme'];

				$result=mysqli_query($bdd,$requete);
			}
			else{
				//Ajout Parametres
				$requete="INSERT INTO form_formation_plateforme_parametres (Id_Formation,Id_Plateforme, Id_Langue, Id_Langue_Document, CoutSalarieAAA,CoutInterimaire,CoutTarifGroupe, Duree, CoutSalarieAAARecyclage, CoutInterimaireRecyclage,CoutTarifGroupeRecyclage, DureeRecyclage,NbJour,NbJourRecyclage,Id_Organisme,BesoinParametrableUniquementAF,Id_Personne_MAJ, Date_MAJ) ";
				$requete.=" VALUES (".$IdForm.",".$_POST['Id_Plateforme'].",".$_POST['LangueAffichage'].",".$_POST['LangueDocuments'].",".$cout.",".$coutInterimaire.",".$coutTarifGroupe.",".$duree.",".$coutR.",".$coutInterimaireR.",".$coutTarifGroupeR.",".$dureeR.",".$nbJour.",".$nbJourR.",".$organisme.",".$_POST['parametrableAF'].",".$IdPersonneConnectee.",'".date('Y-m-d')."')";

				$result=mysqli_query($bdd,$requete);
			}
			if($_POST['id_Pla']<>0){
				//Suppression des documents
				$req="UPDATE form_formation_document SET Suppr=1 WHERE Id_Formation=".$IdForm;
				$resultDelete=mysqli_query($bdd,$req);
				
				//Ajout des documents
				$tab = explode(";",$_POST['lesDocs']);
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req="INSERT INTO form_formation_document (Id_Formation,Id_Document,Id_Personne_MAJ, Date_MAJ) VALUES (".$IdForm.",".substr($valeur,0,-4).",".$IdPersonneConnectee.",'".date('Y-m-d')."')";
						$resultAjour=mysqli_query($bdd,$req);
					 }
				}
				
				//Suppression des formations
				$req="UPDATE form_formation_formationcompetence SET Suppr=1 WHERE Id_Formation=".$IdForm;
				$resultDelete=mysqli_query($bdd,$req);
				
				//Ajout des formations 
				$tab = explode(";",$_POST['lesFormsCompetences']);
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req="INSERT INTO form_formation_formationcompetence (Id_Formation,Id_FormationCompetence) VALUES (".$IdForm.",".substr($valeur,0,-14).")";
						$resultAjour=mysqli_query($bdd,$req);
					 }
				}
				
				//Suppression des formations
				$req="UPDATE form_formation_formationqualipso SET Suppr=1 WHERE Id_Formation=".$IdForm;
				$resultDelete=mysqli_query($bdd,$req);
				
				//Ajout des formations QUALIPSO
				$tab = explode(";",$_POST['lesFormsQUALIPSO']);
				foreach($tab as $valeur){
					 if($valeur<>""){
						$req="INSERT INTO form_formation_formationqualipso (Id_Formation,Id_FormationQualipso) VALUES (".$IdForm.",".substr($valeur,0,-12).")";
						$resultAjour=mysqli_query($bdd,$req);
					 }
				}
		
				//Suppression des qualifications
				$req="UPDATE form_formation_qualification_QCM SET Suppr=1 WHERE Id_Formation_Qualification IN (SELECT Id FROM form_formation_qualification WHERE Id_Formation=".$IdForm.")";
				$resultDelete=mysqli_query($bdd,$req);
				
				$req="UPDATE form_formation_qualification SET Suppr=1 WHERE Id_Formation=".$IdForm;
				$resultDelete=mysqli_query($bdd,$req);
				
				//Ajout des qualifications et des qcm par qualification
				$tab = explode(";",$_POST['lesQualifs']);
				foreach($tab as $valeur){
					 if($valeur<>""){
						$tabValeur = explode("_",$valeur);
						//Vérifier que la qualif n'existe pas déjà
						$reqVerif="SELECT Id FROM form_formation_qualification WHERE Id_Formation=".$IdForm." AND Suppr=0 AND Id_Qualification=".$tabValeur[0]." ";
						$resultVerif=mysqli_query($bdd,$reqVerif);
						$nbResultaVerif=mysqli_num_rows($resultVerif);
						
						if($nbResultaVerif==0){
							$req="INSERT INTO form_formation_qualification (Id_Formation,Id_Qualification,Id_Personne_MAJ, Date_MAJ,Masquer) VALUES (".$IdForm.",".$tabValeur[0].",".$IdPersonneConnectee.",'".date('Y-m-d')."',".$tabValeur[1].")";
							$resultAjour=mysqli_query($bdd,$req);
							$IdFormationQCM=mysqli_insert_id($bdd);
						}
						else{
							$rowLaQualif=mysqli_fetch_array($resultVerif);
							$IdFormationQCM=$rowLaQualif['Id'];
							
							$req="UPDATE form_formation_qualification SET Masquer=".$tabValeur[1]." WHERE Id=".$IdFormationQCM;
							$resultUpdate=mysqli_query($bdd,$req);
						}
						$tabQCM = explode("!",$tabValeur[2]);
						foreach($tabQCM as $qcm){
							if($qcm<>""){
								$tabQCMLangue = explode("*",$qcm);
								$req="INSERT INTO form_formation_qualification_qcm (Id_Formation_Qualification,Id_QCM,Id_Langue,Id_Personne_MAJ, Date_MAJ) VALUES (".$IdFormationQCM.",".$tabQCMLangue[0].",".$tabQCMLangue[1].",".$IdPersonneConnectee.",'".date('Y-m-d')."')";
								$resultAjour=mysqli_query($bdd,$req);
							}
						}
					 }
				}

				//Suppression des informations langues
				$req="UPDATE form_formation_langue_infos SET Suppr=1 WHERE Id_Formation=".$IdForm;
				$resultDelete=mysqli_query($bdd,$req);
				
				//Ajout des informations / langues
				$req="SELECT Id FROM form_langue WHERE Suppr=0 ORDER BY Libelle";
				$resultL=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($resultL);
				if($nbResulta>0){
					while($rowL=mysqli_fetch_array($resultL)){
						if(isset($_POST['Libelle_'.$rowL['Id']])){
							if($_POST['Libelle_'.$rowL['Id']]<>""){
								$Fichier="";
								$FichierRecyclage="";
								$Problem=0;
								$SrcProblem="";
								//S'il y avait une fichier
								if(isset($_POST['supprFichier_I_'.$rowL['Id']])){
									if($_POST['supprFichier_I_'.$rowL['Id']]){
										if(file_exists($DirFichier.$_POST['fichieractuel_I_'.$rowL['Id']])){
											if(!unlink($DirFichier.$_POST['fichieractuel_I_'.$rowL['Id']])){$SrcProblem.="Impossible de supprimer le fichier.";$Problem=1;}
										}
									}
								}
								if(isset($_POST['supprFichier_R_'.$rowL['Id']])){
									if($_POST['supprFichier_R_'.$rowL['Id']]){
										if(file_exists($DirFichier.$_POST['fichieractuel_R_'.$rowL['Id']])){
											if(!unlink($DirFichier.$_POST['fichieractuel_R_'.$rowL['Id']])){$SrcProblem.="Impossible de supprimer le fichier.";$Problem=1;}
										}
									}
								}
								
								//****TRANSFERT FICHIER****
								if($_FILES['fichier_I_'.$rowL['Id']]['name']!="")
								{
									$tmp_file=$_FILES['fichier_I_'.$rowL['Id']]['tmp_name'];
									if(!is_uploaded_file($tmp_file)){$SrcProblem.="Le fichier est introuvable.";$Problem=1;}
									else
									{
										//On vérifie la taille du fichiher
										if(filesize($_FILES['fichier_I_'.$rowL['Id']]['tmp_name'])>$_POST['MAX_FILE_SIZE'])
										{$SrcProblem.="Le fichier est trop volumineux.";$Problem=1;}
										else
										{
											// on copie le fichier dans le dossier de destination
											$name_file=$_FILES['fichier_I_'.$rowL['Id']]['name'];
											$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
											while(file_exists($DirFichier.$name_file)){$name_file="le ".date('j-m-y')." a ".date('H-i-s')." ".$name_file;}
											if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
											{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
											else{$Fichier=$name_file;}
										}
									}
								}
								if($_FILES['fichier_R_'.$rowL['Id']]['name']!="")
								{
									$tmp_file=$_FILES['fichier_R_'.$rowL['Id']]['tmp_name'];
									if(!is_uploaded_file($tmp_file)){$SrcProblem.="Le fichier recyclage est introuvable.";$Problem=1;}
									else
									{
										//On vérifie la taille du fichiher
										if(filesize($_FILES['fichier_R_'.$rowL['Id']]['tmp_name'])>$_POST['MAX_FILE_SIZE'])
										{$SrcProblem.="Le fichier recyclage est trop volumineux.";$Problem=1;}
										else
										{
											// on copie le fichier dans le dossier de destination
											$name_file=$_FILES['fichier_R_'.$rowL['Id']]['name'];
											$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
											while(file_exists($DirFichier.$name_file)){$name_file="le ".date('j-m-y')." a ".date('H-i-s')." ".$name_file;}
											if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
											{$SrcProblem.="Impossible de copier le fichier.";$Problem=1;}
											else{$FichierRecyclage=$name_file;}
										}
									}
								}
								if($Problem==1){echo "<script>alert('Il y a eu une erreur lors de la copie du fichier joint (".$SrcProblem."). Veuillez vérifier si celui-ci est bien ajouté dans ce que vous venez de créer.');</script>";}
								
								$req="SELECT Id FROM form_formation_langue_infos WHERE Suppr=0 AND Id_Langue=".$rowL['Id']." AND Id_Formation=".$IdForm;
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									$req="UPDATE form_formation_langue_infos SET Libelle='".addslashes($_POST['Libelle_'.$rowL['Id']])."',Description='".addslashes($_POST['Description_'.$rowL['Id']])."',";
									$req.="LibelleRecyclage='".addslashes($_POST['LibelleR_'.$rowL['Id']])."',DescriptionRecyclage='".addslashes($_POST['DescriptionR_'.$rowL['Id']])."'";
									if(isset($_POST['supprFichier_I_'.$rowL['Id']]) || $Fichier){
										$req.=",Fichier='".$Fichier."' ";
									}
									if(isset($_POST['supprFichier_R_'.$rowL['Id']]) || $FichierRecyclage){
										$req.=",FichierRecyclage='".$FichierRecyclage."' ";
									}
									$req.=", Id_Personne_MAJ=".$IdPersonneConnectee."";
									$req.=", Date_MAJ='".date('Y-m-d')."'";
									$req.=" WHERE Suppr=0 AND Id_Langue=".$rowL['Id']." AND Id_Formation=".$IdForm;
									$resultMAJ=mysqli_query($bdd,$req);
									
								}
								else{
									$req="INSERT INTO form_formation_langue_infos (Id_Formation,Id_Langue,Libelle,Description,LibelleRecyclage,DescriptionRecyclage,Fichier,FichierRecyclage,Id_Personne_MAJ, Date_MAJ) VALUES (".$IdForm.",".$rowL['Id'].",'".addslashes($_POST['Libelle_'.$rowL['Id']])."','".addslashes($_POST['Description_'.$rowL['Id']])."','".addslashes($_POST['LibelleR_'.$rowL['Id']])."','".addslashes($_POST['DescriptionR_'.$rowL['Id']])."','".$Fichier."','".$FichierRecyclage."',".$IdPersonneConnectee.",'".date('Y-m-d')."')";
									$resultAjour=mysqli_query($bdd,$req);
								}
							}
						}
					}
				}

			}
			echo "<script charset='utf-8'>FermerEtRecharger(\"".$_POST['motcles']."\",\"".$_POST['Id_Plateforme']."\");</script>";
		}
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Id=$_GET['Id'];
	$read="";
	$disabled="";
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
		//Liste des formations
		$reqForm="SELECT Reference FROM form_formation WHERE (Id_Plateforme=0 OR Id_Plateforme=".$_GET['Id_Plateforme'].") AND Suppr=false ";
		if($_GET['Id']!='0'){
			$reqForm.="AND Id<>".$_GET['Id'];
			$result=mysqli_query($bdd,"SELECT Id, Id_Plateforme, Reference,Categorie, Id_TypeFormation,Tuteur, Recyclage,Obligatoire, Id_Personne_MAJ FROM form_formation WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
			
			if($Ligne['Id_Plateforme']==0){
				$read="readonly='readonly'";
				$disabled="disabled='disabled'";
			}
			
			$resultPla=mysqli_query($bdd,"SELECT Id,Id_Langue,Id_Langue_Document,CoutSalarieAAA,CoutInterimaire,CoutTarifGroupe,CoutTarifGroupeRecyclage,Duree,CoutSalarieAAARecyclage,CoutInterimaireRecyclage,DureeRecyclage,NbJour,NbJourRecyclage, Id_Organisme,BesoinParametrableUniquementAF FROM form_formation_plateforme_parametres WHERE Id_Plateforme=".$_GET['Id_Plateforme']." AND Id_Formation=".$_GET['Id']);
			$LignePla=mysqli_fetch_array($resultPla);
		}
		$resultForm=mysqli_query($bdd,$reqForm);
		$nbResultaForm=mysqli_num_rows($resultForm);
		if ($nbResultaForm>0){
			$i=0;
			while($rowForm=mysqli_fetch_array($resultForm)){
				echo "<script>Liste_Form[".$i."]=\"".addslashes($rowForm['Reference'])."\"</script>";
				$i++;
			}
		}
?>

		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_FormationPlateforme.php" onSubmit="return VerifChamps('<?php echo $LangueAffichage;?>');">
		<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
		<input type="hidden" name="Mode" id="Mode" value="<?php echo $_GET['Mode']; ?>"/>
		<input type="hidden" name="id" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>"/>
		<input type="hidden" name="Id_Plateforme" value="<?php echo $_GET['Id_Plateforme'];?>"/>
		<input type="hidden" name="id_Pla" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id_Plateforme'];}?>"/>
		<input type="hidden" name="motcles" id="motcles" value="<?php echo $_GET['motcles']; ?>"/>
		<input type="hidden" name="Langue" id="Langue" value="<?php echo $LangueAffichage; ?>"/>
		<table style="width:95%; align:center;" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Référence";}else{echo "Reference";} ?></td>
				<td colspan="5">
					<input <?php echo $read;?> type="text" name="libelle" id="libelle" size="50" value="<?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['Reference']);}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Catégorie";}else{echo "Category";} ?></td>
				<td colspan="5">
					<input <?php echo $read;?> type="text" name="categorie2" id="categorie2" size="50" value="<?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['Categorie']);}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";} ?></td>
				<td>
					<select name="Id_TypeFormation" <?php echo $disabled;?>>
						<?php
						$resultTypeFormation=mysqli_query($bdd,"SELECT Id, Libelle FROM form_typeformation WHERE Suppr=0 ORDER BY Libelle ASC");
						while($rowTypeFormation=mysqli_fetch_array($resultTypeFormation))
						{
							echo "<option value='".$rowTypeFormation['Id']."'";
							if($_GET['Mode']=="M"){if($rowTypeFormation['Id']==$Ligne['Id_TypeFormation']){echo " selected";}}
							echo ">".stripslashes($rowTypeFormation['Libelle'])."</option>\n";
						}
						?>
					</select>
				</td>
				<td  class="Libelle"><?php if($LangueAffichage=="FR"){echo "Recyclage différent";}else{echo "Different recycling";} ?> : </td>
				<td>
					<select id="Recyclage" name="Recyclage" <?php echo $disabled;?> onchange="AfficherRecyclagePla();">
						<?php
						if($LangueAffichage=="FR"){
							$Tableau=array('Oui|1','Non|0');
						}
						else{
							$Tableau=array('Yes|1','No|0');
						}
						foreach($Tableau as $indice => $valeur)
						{
							$valeur=explode("|",$valeur);
							echo "<option value='".$valeur[1]."'";
							if($_GET['Mode']=="M"){if($valeur[1]==$Ligne['Recyclage']){echo " selected";}}
							echo ">".$valeur[0]."</option>\n";
						}
						?>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td  class="Libelle"><?php if($LangueAffichage=="FR"){echo "Est une formation tuteur";}else{echo "Is a tutor training";} ?> : </td>
				<td>
					<select name="Tuteur" <?php echo $disabled;?>>
						<?php
						if($LangueAffichage=="FR"){
							$Tableau=array('Non|0','Oui|1');
						}
						else{
							$Tableau=array('No|0','Yes|1');
						}
						foreach($Tableau as $indice => $valeur)
						{
							$valeur=explode("|",$valeur);
							echo "<option value='".$valeur[1]."'";
							if($_GET['Mode']=="M"){if($valeur[1]==$Ligne['Tuteur']){echo " selected";}}
							echo ">".$valeur[0]."</option>\n";
						}
						?>
					</select>
				</td>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Coût salarié AAA (par personne)";}else{echo "AAA employee cost (per person)";} ?></td>
				<td>
					<input onKeyUp="nombre(this)" type="text" name="cout" id="cout" size="10" value="<?php if($_GET['Mode']=="M"){echo $LignePla['CoutSalarieAAA'];}?>">
				</td>
				<td id="RecyclageCoutDuree1" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Coût salarié AAA recyclage (par personne)";}else{echo "AAA Recycled Employee Cost (per person)";} ?></td>
				<td id="RecyclageCoutDuree2">
					<input onKeyUp="nombre(this)" type="text" name="coutR" id="coutR" size="10" value="<?php if($_GET['Mode']=="M"){echo $LignePla['CoutSalarieAAARecyclage'];}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td  class="Libelle"><?php if($LangueAffichage=="FR"){echo "Langue d'affichage";}else{echo "Display language";} ?> : </td>
				<td>
					<select name="LangueAffichage" id="LangueAffichage" onchange="AfficherLanguePla();">
						<?php
						$resultLangue=mysqli_query($bdd,"SELECT Id, Libelle FROM form_langue WHERE Suppr=0 ORDER BY Libelle ASC");
						while($rowLangue=mysqli_fetch_array($resultLangue))
						{
							echo "<option value='".$rowLangue['Id']."'";
							if($_GET['Mode']=="A"){if($rowLangue['Id']==1){echo " selected";}}
							if($_GET['Mode']=="M"){if($rowLangue['Id']==$LignePla['Id_Langue']){echo " selected";}}
							echo ">".stripslashes($rowLangue['Libelle'])."</option>\n";
						}
						?>
					</select>
				</td>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Coût intérimaire (par personne)";}else{echo "Interim cost (per person)";} ?></td>
				<td>
					<input onKeyUp="nombre(this)" type="text" name="coutInterimaire" id="coutInterimaire" size="7" value="<?php if($_GET['Mode']=="M"){echo $LignePla['CoutInterimaire'];}?>">
				</td>
				<td id="RecyclageCoutDuree3" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Coût intérimaire recyclage (par personne)";}else{echo "Interim cost recycling (per person)";} ?></td>
				<td id="RecyclageCoutDuree4">
					<input onKeyUp="nombre(this)" type="text" name="coutInterimaireR" id="coutInterimaireR" size="7" value="<?php if($_GET['Mode']=="M"){echo $LignePla['CoutInterimaireRecyclage'];}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td  class="Libelle"></td>
				<td></td>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Coût tarif groupe";}else{echo "Group rate cost";} ?></td>
				<td>
					<input onKeyUp="nombre(this)" type="text" name="coutTarifGroupe" id="coutTarifGroupe" size="7" value="<?php if($_GET['Mode']=="M"){echo $LignePla['CoutTarifGroupe'];}?>">
				</td>
				<td id="RecyclageCoutDuree9" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Coût tarif groupe recyclage";}else{echo "Group rate cost recycling";} ?></td>
				<td id="RecyclageCoutDuree10">
					<input onKeyUp="nombre(this)" type="text" name="coutTarifGroupeR" id="coutTarifGroupeR" size="7" value="<?php if($_GET['Mode']=="M"){echo $LignePla['CoutTarifGroupeRecyclage'];}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td  class="Libelle"><?php if($LangueAffichage=="FR"){echo "Langue des documents par défaut";}else{echo "Default document language";} ?> : </td>
				<td>
					<select name="LangueDocuments" id="LangueDocuments">
						<?php
						$resultLangue=mysqli_query($bdd,"SELECT Id, Libelle FROM form_langue WHERE Suppr=0 ORDER BY Libelle ASC");
						while($rowLangue=mysqli_fetch_array($resultLangue))
						{
							echo "<option value='".$rowLangue['Id']."'";
							if($_GET['Mode']=="A"){if($rowLangue['Id']==1){echo " selected";}}
							if($_GET['Mode']=="M"){if($rowLangue['Id']==$LignePla['Id_Langue_Document']){echo " selected";}}
							echo ">".stripslashes($rowLangue['Libelle'])."</option>\n";
						}
						?>
					</select>
				</td>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Nb jours";}else{echo "Number of days";} ?></td>
				<td>
					<input onKeyUp="chiffre(this)" type="text" name="nbJour" id="nbJour" size="7" value="<?php if($_GET['Mode']=="M"){echo $LignePla['NbJour'];}?>">
				</td>
				<td id="RecyclageCoutDuree5" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Nb jours recyclage";}else{echo "Number of days recycling";} ?></td>
				<td id="RecyclageCoutDuree6">
					<input onKeyUp="chiffre(this)" type="text" name="nbJourR" id="nbJourR" size="7" value="<?php if($_GET['Mode']=="M"){echo $LignePla['NbJourRecyclage'];}?>">
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Organisme de formation";}else{echo "Training organization";} ?></td>
				<td>
					<select id="organisme" name="organisme" style="width:150px;">
						<?php
						echo"<option name='0' value='0'></option>";
						$resultOrganisme=mysqli_query($bdd,"SELECT Id, Libelle FROM form_organisme WHERE Id_Plateforme=".$_GET['Id_Plateforme']." AND Suppr=0 ORDER BY Libelle");
						while($rowOrganisme=mysqli_fetch_array($resultOrganisme)){
							$selected="";
							if($_GET['Mode']=="M"){if($rowOrganisme['Id']==$LignePla['Id_Organisme']){$selected="selected";}}
							echo "<option value='".$rowOrganisme['Id']."' ".$selected.">".$rowOrganisme['Libelle']."</option>\n";
						}
						?>
					</select>
				</td>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Durée (en heures 00:00)";}else{echo "Duration (in hours 00:00)";} ?></td>
 				<td> 
					<input onchange="heure(this)" type="text" name="duree" id="duree" size="7" value="<?php 
						if($_GET['Mode']=="M")							
							echo str_replace(".", ":", $LignePla['Duree']);
						?>">
				</td>
				<td id="RecyclageCoutDuree7" class="Libelle"><?php if($LangueAffichage=="FR"){echo "Durée recyclage (en heures 00:00)";}else{echo "Recycling time (in hours 00:00)";} ?></td>
				<td id="RecyclageCoutDuree8">
					<input onKeyUp="heure(this)" type="text" name="dureeR" id="dureeR" size="7" value="<?php 
						if($_GET['Mode']=="M")							
							echo str_replace(".", ":", $LignePla['DureeRecyclage']);
						?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td  class="Libelle"><?php if($LangueAffichage=="FR"){echo "Uniquement paramétrable par les AF/RF (Besoins par métier et par prestation)";}else{echo "Only configurable by training assistants / training managers (needs by job and by site)";} ?> : </td>
				<td>
					<select name="parametrableAF">
						<?php
						if($LangueAffichage=="FR"){
							$Tableau=array('Non|0','Oui|1');
						}
						else{
							$Tableau=array('No|0','Yes|1');
						}
						foreach($Tableau as $indice => $valeur)
						{
							$valeur=explode("|",$valeur);
							echo "<option value='".$valeur[1]."'";
							if($_GET['Mode']=="M"){if($valeur[1]==$LignePla['BesoinParametrableUniquementAF']){echo " selected";}}
							echo ">".$valeur[0]."</option>\n";
						}
						?>
					</select>
				</td>
				<td  class="Libelle"><?php if($LangueAffichage=="FR"){echo "Obligatoire";}else{echo "Mandatory";} ?> : </td>
				<td>
					<select name="Obligatoire">
						<?php
						if($LangueAffichage=="FR"){
							$Tableau=array('Non|0','Oui|1');
						}
						else{
							$Tableau=array('No|0','Yes|1');
						}
						foreach($Tableau as $indice => $valeur)
						{
							$valeur=explode("|",$valeur);
							echo "<option value='".$valeur[1]."'";
							if($_GET['Mode']=="M"){if($valeur[1]==$Ligne['Obligatoire']){echo " selected";}}
							echo ">".$valeur[0]."</option>\n";
						}
						?>
					</select>
				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<?php if($_GET['Mode']=="A" || ($_GET['Mode']=="M" && $Ligne['Id_Plateforme']<>0)){ ?>
				<td class="Libelle" colspan="2" valign="top">
					<table style="border-spacing:0; width:90%; -moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
						<tr>
							<td bgcolor='#e4e7f0' colspan='2'><i>&nbsp;<?php if($LangueAffichage=="FR"){echo "Ajouter les qualifications";}else{echo "Add qualifications";}?></i></td>
						</tr>
						<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; <?php if($LangueAffichage=="FR"){echo "Catégorie qualification maître";}else{echo "Category qualification master";}?> : </td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; 
							<select id="categorie_Maitre" name="categorie_Maitre" style="width:200px;" onchange="Lister_Categories_Qualification();" onkeypress="if(event.keyCode == 13)AjouterQualif()">
								<?php
								echo"<option name='0' value='0'></option>";
								$resultCategorieQualificationMaitre=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_categorie_qualification_maitre ORDER BY Libelle");
								while($rowCategorieQualificationMaitre=mysqli_fetch_array($resultCategorieQualificationMaitre))
								{
									echo "<option value='".$rowCategorieQualificationMaitre['Id']."'>".$rowCategorieQualificationMaitre['Libelle']."</option>\n";
								}
								?>
							</select>
							</td>
						</tr>
						<?php
							//Catégories
							$requeteCategoriesQualification="SELECT Id, Libelle, Id_Categorie_Maitre FROM new_competences_categorie_qualification ORDER BY Libelle ";
							$resultCategoriesQualification=mysqli_query($bdd,$requeteCategoriesQualification);
							$i=0;
							while($rowCategories=mysqli_fetch_array($resultCategoriesQualification)){
								echo "<script>Liste_Categorie[".$i."] = new Array('".$rowCategories['Id']."','".$rowCategories['Id_Categorie_Maitre']."','".addslashes($rowCategories['Libelle'])."');</script>\n";
								$i+=1;
							}
							$requeteQualifications="SELECT new_competences_qualification.Id,new_competences_qualification.Id_Categorie_Qualification, new_competences_qualification.Libelle, new_competences_qualification.Id_Categorie_Qualification, ";
							$requeteQualifications.="new_competences_categorie_qualification.Libelle AS Categorie, ";
							$requeteQualifications.="(SELECT Libelle FROM new_competences_categorie_qualification_maitre WHERE Id=new_competences_categorie_qualification.Id_Categorie_Maitre) AS CategorieMaitre ";
							$requeteQualifications.="FROM new_competences_qualification LEFT JOIN new_competences_categorie_qualification ";
							$requeteQualifications.="ON new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id ";
							$requeteQualifications.="WHERE new_competences_qualification.Suppr=0 ORDER BY new_competences_qualification.Libelle ";
							$resultQualifications=mysqli_query($bdd,$requeteQualifications);
							$i=0;
							while($rowQualifications=mysqli_fetch_array($resultQualifications)){
								echo "<script>Liste_Qualif[".$i."]=new Array('".$rowQualifications['Id']."','".str_replace("'"," ",str_replace('"',' ',addslashes($rowQualifications['Libelle'])))."','".addslashes($rowQualifications['Categorie'])."','".addslashes($rowQualifications['CategorieMaitre'])."','".$rowQualifications['Id_Categorie_Qualification']."');</script>";
								$i++;
							}
						?>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; <?php if($LangueAffichage=="FR"){echo "Catégorie";}else{echo "Category";}?> : </td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; 
							<div style="display:inline;" Id="Categories_Qualification">
							<select id="categorie" name="categorie" style="width:200px;" onkeypress="if(event.keyCode == 13)AjouterQualif()">
							</select>
							</div>
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; <?php if($LangueAffichage=="FR"){echo "Qualification";}else{echo "Qualification";}?> : </td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; 
							<div style="display:inline;" Id="Qualifications">
							<select id="qualification" name="qualification" style="width:300px;" onkeypress="if(event.keyCode == 13)AjouterQualif()">
							</select>
							</div>
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; <?php if($LangueAffichage=="FR"){echo "Acquise";}else{echo "Acquired";}?> : </td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; 
							<select id="acquise" name="acquise" style="width:100px;" onkeypress="if(event.keyCode == 13)AjouterQualif()">
								<option value="0" selected><?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";} ?></option>
								<option value="1"><?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";} ?></option>
							</select>
							</td>
						</tr>
						<tr>
							<td colspan="6" bgcolor='#e4e7f0' class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "QCM associés";}else{echo "Related MCQ";}?></td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>
								<div id="listeQCM" style="height:100px;overflow:auto;">
								<table style="width:100%;">
						<?php
							$req="SELECT Id, Libelle FROM form_langue ";
							$resultLangue=mysqli_query($bdd,$req);
							$nbLangue=mysqli_num_rows($resultLangue);
							if($nbLangue>0){
								$i=0;
								while($rowLangue=mysqli_fetch_array($resultLangue)){
									echo "<script>Liste_Langue[".$i."]=new Array('".$rowLangue['Id']."','".addslashes($rowLangue['Libelle'])."');</script>\n";
									$i++;
								}
							}
							
							$req="SELECT Id, Code 
								FROM form_qcm 
								WHERE Suppr=0 
								AND (SELECT COUNT(form_qcm_langue.Id) 
									FROM form_qcm_langue 
									WHERE form_qcm_langue.Id_QCM=form_qcm.Id 
									AND form_qcm_langue.Brouillon=0
									AND form_qcm_langue.Suppr=0
									)>0
								ORDER BY Code ASC";
							$resultQCM=mysqli_query($bdd,$req);
							
							$req="SELECT  Id_QCM, Id_Langue, ";
							$req.="(SELECT Libelle FROM form_langue WHERE form_langue.Id=form_qcm_langue.Id_Langue) AS Langue ";
							$req.="FROM form_qcm_langue WHERE Suppr=0 AND Brouillon=0 ";
							$resultQCMLangue=mysqli_query($bdd,$req);
							$nbResultaQCMLangue=mysqli_num_rows($resultQCMLangue);
							
							$i=0;
							while($rowQCM=mysqli_fetch_array($resultQCM)){
								$nb=0;
								$select="<select id='qcmlangue_".$rowQCM['Id']."' name='qcmlangue_".$rowQCM['Id']."'>";
								
								if($nbResultaQCMLangue>0){
									mysqli_data_seek($resultQCMLangue,0);
									while($rowQCMLangue=mysqli_fetch_array($resultQCMLangue)){
										if($rowQCMLangue['Id_QCM']==$rowQCM['Id']){
											$nb++;
											$select.="<option value='".$rowQCMLangue['Id_Langue']."'>".$rowQCMLangue['Langue']."</option>";
										}
									}
								}
								$select.="</select>";
								if($nb>0){
									echo "<tr>";
									echo "<td width='100px'>";
									echo "<input class='checkQCM' type='checkbox' id='QCM_".$rowQCM['Id']."' name='QCM_".$rowQCM['Id']."' value='".$rowQCM['Id']."'>&nbsp;";
									echo stripslashes($rowQCM['Code']);
									echo "</td>";
									echo "<td width='30%'>";
									echo $select;
									echo "</td>";
									echo "</tr>";
									echo "<script>Liste_QCM[".$i."]=new Array('".$rowQCM['Id']."','".addslashes($rowQCM['Code'])."');</script>\n";
									$i++;
								}
							}
						?>
								</table>
								</div>
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='middle'>
								<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterQualif()'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Ajouter";}else{echo "Add";}?>&nbsp;</a>
							</td>
						</tr>
					</table>
				</td>
				<?php } ?>
				<td colspan="6" valign='top'>
					<table id="tab_Qualif" style="width:100%; border-spacing:0;">
						<tr>
							<td class="Libelle" width="9%"><?php if($LangueAffichage=="FR"){echo "Acquise";}else{echo "Acquired";}?></td>
							<td class="Libelle" width="15%"><?php if($LangueAffichage=="FR"){echo "Catégorie maitre";}else{echo "Category master";}?></td>
							<td class="Libelle" width="20%"><?php if($LangueAffichage=="FR"){echo "Catégorie";}else{echo "Category";}?></td>
							<td class="Libelle" width="36%"><?php if($LangueAffichage=="FR"){echo "Qualification";}else{echo "Qualification";}?></td>
							<td class="Libelle" width="20%"><?php if($LangueAffichage=="FR"){echo "QCM";}else{echo "MCQ";}?></td>
						</tr>
						<?php
								$listeQualifs="";
								if($_GET['Mode']=="M"){
									$req="SELECT form_formation_qualification.Id,form_formation_qualification.Id_Qualification,new_competences_qualification.Libelle,form_formation_qualification.Masquer, ";
									$req.="(SELECT Libelle FROM new_competences_categorie_qualification WHERE new_competences_categorie_qualification.Id=new_competences_qualification.Id_Categorie_Qualification) AS Categorie, ";
									$req.="(SELECT (SELECT Libelle FROM new_competences_categorie_qualification_maitre WHERE Id=new_competences_categorie_qualification.Id_Categorie_Maitre) FROM new_competences_categorie_qualification WHERE new_competences_categorie_qualification.Id=new_competences_qualification.Id_Categorie_Qualification) AS CategorieMaitre ";
									$req.="FROM form_formation_qualification LEFT JOIN new_competences_qualification ";
									$req.="ON form_formation_qualification.Id_Qualification=new_competences_qualification.Id ";
									$req.="WHERE form_formation_qualification.Suppr=false ";
									$req.="AND form_formation_qualification.Id_Formation=".$Ligne['Id']." ";
									$req.="ORDER BY Libelle ";
									$result=mysqli_query($bdd,$req);
									
									$req="SELECT form_formation_qualification_qcm.Id_Formation_Qualification, form_formation_qualification_qcm.Id_QCM, form_qcm.Code,form_formation_qualification_qcm.Id_Langue, ";
									$req.="(SELECT Libelle FROM form_langue WHERE form_langue.Id=form_formation_qualification_qcm.Id_Langue) AS Langue ";
									$req.="FROM form_formation_qualification_qcm LEFT JOIN form_qcm ";
									$req.="ON form_formation_qualification_qcm.Id_QCM=form_qcm.Id ";
									$req.="WHERE form_formation_qualification_qcm.Suppr=0 ORDER BY Code";
									$resultQCM=mysqli_query($bdd,$req);
									$nbResultaQCM=mysqli_num_rows($resultQCM);
									
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowQualif=mysqli_fetch_array($result)){
											$qcm="";
											$Id_QCM="";
											$nbQCM=0;
											if($LangueAffichage=="FR"){$Acquis="Oui";}
											else{$Acquis="Yes";}
											if($rowQualif['Masquer']==1){
												if($LangueAffichage=="FR"){$Acquis="Non";}
												else{$Acquis="No";}
											}
											if($nbResultaQCM>0){
												mysqli_data_seek($resultQCM,0);
												while($rowQCM=mysqli_fetch_array($resultQCM)){
													if($rowQCM['Id_Formation_Qualification']==$rowQualif['Id']){
														$nbQCM++;
														$qcm=$rowQCM['Code']." (".$rowQCM['Langue'].")<br>";
														$Id_QCM=$rowQCM['Id_QCM']."*".$rowQCM['Id_Langue']."!";
														
														$btn="";
														if($Ligne['Id_Plateforme']<>0){
															$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerQualif('".$rowQualif['Id_Qualification']."_".$rowQualif['Masquer']."_".$Id_QCM."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
														}
														echo "<tr id='".$rowQualif['Id_Qualification']."_".$rowQualif['Masquer']."_".$Id_QCM."_Qualif'><td style='border-bottom:1px dotted #000000'>".$Acquis."</td><td style='border-bottom:1px dotted #000000'>".stripslashes($rowQualif['CategorieMaitre'])."</td><td style='border-bottom:1px dotted #000000'>".stripslashes($rowQualif['Categorie'])."</td><td style='border-bottom:1px dotted #000000'>".stripslashes($rowQualif['Libelle'])."</td><td style='border-bottom:1px dotted #000000'>".$qcm."</td><td style='border-bottom:1px dotted #000000'>".$btn."</td></tr>";
														$listeQualifs.=";".$rowQualif['Id_Qualification']."_".$rowQualif['Masquer']."_".$Id_QCM."";
													}
												}
											}
											if($nbQCM==0){
												$btn="";
												if($Ligne['Id_Plateforme']<>0){
													$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerQualif('".$rowQualif['Id_Qualification']."_".$rowQualif['Masquer']."_"."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
												}
												echo "<tr id='".$rowQualif['Id_Qualification']."_".$rowQualif['Masquer']."_"."_Qualif'><td style='border-bottom:1px dotted #000000'>".$Acquis."</td><td style='border-bottom:1px dotted #000000'>".stripslashes($rowQualif['CategorieMaitre'])."</td><td style='border-bottom:1px dotted #000000'>".stripslashes($rowQualif['Categorie'])."</td><td style='border-bottom:1px dotted #000000'>".stripslashes($rowQualif['Libelle'])."</td><td style='border-bottom:1px dotted #000000'></td><td style='border-bottom:1px dotted #000000'>".$btn."</td></tr>";
												$listeQualifs.=";".$rowQualif['Id_Qualification']."_".$rowQualif['Masquer']."_"."";
											}
										}
									}
								}
							?>
					</table>
				</td>
			</tr>
			<tr><td height="8"></td></tr>
			<tr>
				<?php if($_GET['Mode']=="A" || ($_GET['Mode']=="M" && $Ligne['Id_Plateforme']<>0)){ ?>
				<td class="Libelle" colspan="2" valign="top">
					<table style="width:90%; border-spacing:0; -moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
						<tr>
							<td bgcolor='#e4e7f0' colspan='2'><i>&nbsp;<?php if($LangueAffichage=="FR"){echo "Ajouter les formations sans qualifications correspondantes";}else{echo "Add training without corresponding qualifications";}?></i></td>
						</tr>
						<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; <?php if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";}?> : </td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; 
							<select id="formationProfil" name="formationProfil" style="width:130px;" onkeypress="if(event.keyCode == 13)AjouterFormation()">
								<?php
								echo"<option name='0' value='0'></option>";
								$req="SELECT Id, Libelle FROM new_competences_formation ORDER BY Libelle ";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									$i=0;
									while($rowFor=mysqli_fetch_array($result)){
										echo "<option value='".$rowFor['Id']."'>".$rowFor['Libelle']."</option>";
										echo "<script>Liste_FormCompetence[".$i."] = new Array('".$rowFor['Id']."','".addslashes($rowFor['Libelle'])."');</script>\n";
										$i+=1;
									}
								}
								?>
							</select>
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='middle'>
								<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterFormation()'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Ajouter";}else{echo "Add";}?>&nbsp;</a>
							</td>
						</tr>
					</table>
				</td>
				<?php } ?>
				<td valign='top' colspan="6">
					<table id="tab_FormCompetences" style="width:100%; boder-spacing:0;">
						<tr><td class="Libelle" width="100%"><?php if($LangueAffichage=="FR"){echo "Formation correspondante";}else{echo "Corresponding training";}?></td><td width="80%"></td>
						<?php
								$listeForm="";
								if($_GET['Mode']=="M"){
									$req="SELECT form_formation_formationcompetence.Id_FormationCompetence,
											new_competences_formation.Libelle 
											FROM form_formation_formationcompetence 
											LEFT JOIN new_competences_formation 
											ON form_formation_formationcompetence.Id_FormationCompetence=new_competences_formation.Id 
											WHERE form_formation_formationcompetence.Suppr=0 
											AND form_formation_formationcompetence.Id_Formation=".$Ligne['Id']." ORDER BY Libelle ";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowFor=mysqli_fetch_array($result)){
											$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerFormation('".$rowFor['Id_FormationCompetence']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
											echo "<tr id='".$rowFor['Id_FormationCompetence']."_FormCompetence'><td>".stripslashes($rowFor['Libelle'])."</td><td>".$btn."</td></tr>";
											$listeForm.=";".$rowFor['Id_FormationCompetence']."FormCompetence";
										}
									}
								}
							?>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td height="8"></td></tr>
			<tr>
				<?php if($_GET['Mode']=="A" || ($_GET['Mode']=="M" && $Ligne['Id_Plateforme']<>0)){ ?>
				<td class="Libelle" colspan="2" valign="top">
					<table style="width:90%; border-spacing:0; -moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
						<tr>
							<td bgcolor='#e4e7f0' colspan='2'><i>&nbsp;<?php if($LangueAffichage=="FR"){echo "Ajouter les formations QUALIPSO supprimées sans qualifications correspondantes";}else{echo "Add training QUALIPSO deleted without corresponding qualifications";}?></i></td>
						</tr>
						<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; <?php if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";}?> : </td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; 
							<select id="formationQUALIPSOProfil" name="formationQUALIPSOProfil" style="width:130px;" onkeypress="if(event.keyCode == 13)AjouterFormationQUALIPSO()">
								<?php
								echo"<option name='0' value='0'></option>";
								$req="SELECT form_formation.Id, Libelle 
								FROM form_formation_langue_infos,
								form_formation 
								WHERE form_formation.Suppr=1
								AND form_formation_langue_infos.Id_Formation=form_formation.Id
								AND form_formation_langue_infos.Suppr=0
								AND form_formation_langue_infos.Id_Langue IN (SELECT Id_Langue FROM form_formation_plateforme_parametres WHERE form_formation_plateforme_parametres.Suppr=0 
									AND form_formation_plateforme_parametres.Id_Formation=form_formation.Id AND Id_Plateforme=".$Ligne['Id_Plateforme'].")
								AND (SELECT COUNT(Id)
									FROM form_formation_qualification
									WHERE Id_Formation=form_formation.Id)=0
								ORDER BY Libelle ";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									$i=0;
									while($rowFor=mysqli_fetch_array($result)){
										echo "<option value='".$rowFor['Id']."'>".$rowFor['Libelle']."</option>";
										echo "<script>Liste_FormQualipso[".$i."] = new Array('".$rowFor['Id']."','".addslashes($rowFor['Libelle'])."');</script>\n";
										$i+=1;
									}
								}
								?>
							</select>
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='middle'>
								<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterFormationQUALIPSO()'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Ajouter";}else{echo "Add";}?>&nbsp;</a>
							</td>
						</tr>
					</table>
				</td>
				<?php } ?>
				<td valign='top' colspan="6">
					<table id="tab_FormQualipso" style="width:100%; boder-spacing:0;">
						<tr><td class="Libelle" width="100%"><?php if($LangueAffichage=="FR"){echo "Formation QUALIPSO correspondante";}else{echo "Corresponding training QUALIPSO";}?></td><td width="80%"></td>
						<?php
								$listeFormQUALIPSO="";
								if($_GET['Mode']=="M"){
									$req="SELECT form_formation_formationqualipso.Id_FormationQualipso,
											form_formation_langue_infos.Libelle 
											FROM form_formation_formationqualipso 
											LEFT JOIN form_formation_langue_infos 
											ON form_formation_formationqualipso.Id_FormationQualipso=form_formation_langue_infos.Id_Formation 
											WHERE form_formation_formationqualipso.Suppr=0 
											AND form_formation_langue_infos.Suppr=0
											AND form_formation_langue_infos.Id_Langue IN (SELECT Id_Langue FROM form_formation_plateforme_parametres WHERE form_formation_plateforme_parametres.Suppr=0 
											AND form_formation_plateforme_parametres.Id_Formation=form_formation_formationqualipso.Id_FormationQualipso AND Id_Plateforme=".$Ligne['Id_Plateforme'].")
											AND form_formation_formationqualipso.Id_Formation=".$Ligne['Id']." ORDER BY Libelle ";

									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowFor=mysqli_fetch_array($result)){
											$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerFormationQualipso('".$rowFor['Id_FormationQualipso']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
											echo "<tr id='".$rowFor['Id_FormationQualipso']."_FormQualipso'><td>".stripslashes($rowFor['Libelle'])."</td><td>".$btn."</td></tr>";
											$listeFormQUALIPSO.=";".$rowFor['Id_FormationQualipso']."FormQualipso";
										}
									}
								}
							?>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td height="8"></td></tr>
			<tr>
				<?php if($_GET['Mode']=="A" || ($_GET['Mode']=="M" && $Ligne['Id_Plateforme']<>0)){ ?>
				<td class="Libelle" colspan="2" valign="top">
					<table style="width:90%; border-spacing:0; -moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
						<tr>
							<td bgcolor='#e4e7f0' colspan='2'><i>&nbsp;<?php if($LangueAffichage=="FR"){echo "Ajouter les documents à signer";}else{echo "Add the documents to sign";}?></i></td>
						</tr>
						<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; <?php if($LangueAffichage=="FR"){echo "Documents";}else{echo "Documents";}?> : </td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; 
							<select id="document" name="document" style="width:130px;" onkeypress="if(event.keyCode == 13)AjouterDocs()">
								<?php
								echo"<option name='0' value='0'></option>";
								$req="SELECT Id, Reference FROM form_document WHERE Suppr=0 ORDER BY Reference ";
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									$i=0;
									while($rowDoc=mysqli_fetch_array($result)){
										echo "<option value='".$rowDoc['Id']."'>".$rowDoc['Reference']."</option>";
										echo "<script>Liste_Docs[".$i."] = new Array('".$rowDoc['Id']."','".addslashes($rowDoc['Reference'])."');</script>\n";
										$i+=1;
									}
								}
								?>
							</select>
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='middle'>
								<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterDocs()'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Ajouter";}else{echo "Add";}?>&nbsp;</a>
							</td>
						</tr>
					</table>
				</td>
				<?php } ?>
				<td valign='top' colspan="6">
					<table id="tab_Doc" style="width:100%; border-spacing:0;">
						<tr><td class="Libelle" width="20%"><?php if($LangueAffichage=="FR"){echo "Document";}else{echo "Document";}?></td><td width="80%"></td>
						<?php
								$listeDocs="";
								if($_GET['Mode']=="M"){
									$req="SELECT form_formation_document.Id_Document,form_document.Reference FROM form_formation_document LEFT JOIN form_document ON form_formation_document.Id_Document=form_document.Id WHERE form_formation_document.Suppr=false AND form_formation_document.Id_Formation=".$Ligne['Id']." ORDER BY Reference ";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowDoc=mysqli_fetch_array($result)){
											$btn="";
											if($Ligne['Id_Plateforme']<>0){
												$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerDocs('".$rowDoc['Id_Document']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
											}
											echo "<tr id='".$rowDoc['Id_Document']."_Doc'><td>".stripslashes($rowDoc['Reference'])."</td><td>".$btn."</td></tr>";
											$listeDocs.=";".$rowDoc['Id_Document']."Docs";
										}
									}
								}
							?>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td height="8"></td></tr>
			<tr>
				<td width="95%" colspan="6" valign='top'>
					<table id="tab_Infos" style="width:100%; border-spacing:0; -moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
						<tr>
							<td class="Libelle" bgcolor='#e4e7f0' width="20%"><?php if($LangueAffichage=="FR"){echo "Langue";}else{echo "Language";}?></td>
							<td class="Libelle" bgcolor='#e4e7f0' width="10%"><?php if($LangueAffichage=="FR"){echo "Recyclage";}else{echo "Recycling";}?></td>
							<td class="Libelle" bgcolor='#e4e7f0' width="30%"><?php if($LangueAffichage=="FR"){echo "Libelle";}else{echo "Libelle";}?></td>
							<td class="Libelle" bgcolor='#e4e7f0' width="40%"><?php if($LangueAffichage=="FR"){echo "Description";}else{echo "Description";}?></td>
							<td class="Libelle" bgcolor='#e4e7f0'><?php if($LangueAffichage=="FR"){echo "Fichier";}else{echo "File";}?></td>
						</tr>
						<?php
							if($_GET['Mode']=="M"){
								$req="SELECT form_formation_langue_infos.Id_Langue,form_langue.Libelle AS Langue,form_formation_langue_infos.Libelle,form_formation_langue_infos.Description, ";
								$req.="form_formation_langue_infos.LibelleRecyclage,form_formation_langue_infos.DescriptionRecyclage,form_formation_langue_infos.Fichier,form_formation_langue_infos.FichierRecyclage ";
								$req.="FROM form_formation_langue_infos LEFT JOIN form_langue ";
								$req.="ON form_formation_langue_infos.Id_Langue=form_langue.Id ";
								$req.="WHERE form_formation_langue_infos.Suppr=false ";
								$req.="AND form_formation_langue_infos.Id_Formation=".$Ligne['Id']." ";
								$req.="ORDER BY Libelle ";
								$resultInfos=mysqli_query($bdd,$req);
								$nbResultaInfos=mysqli_num_rows($resultInfos);
							}
							$req="SELECT Id,Libelle FROM form_langue WHERE Suppr=0 ORDER BY Libelle";
							$resultL=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($resultL);
							if($nbResulta>0){
								while($rowL=mysqli_fetch_array($resultL)){
									$Libelle="";
									$LibelleR="";
									$Description="";
									$DescriptionR="";
									$Fichier="";
									$FichierR="";
									if($_GET['Mode']=="M"){
										if($nbResultaInfos>0){
											mysqli_data_seek($resultInfos,0);
											while($rowInfos=mysqli_fetch_array($resultInfos)){
												if($rowInfos['Id_Langue']==$rowL['Id']){
													$Libelle=stripslashes($rowInfos['Libelle']);
													$LibelleR=stripslashes($rowInfos['LibelleRecyclage']);
													$Description=stripslashes($rowInfos['Description']);
													$DescriptionR=stripslashes($rowInfos['DescriptionRecyclage']);
													$Fichier=stripslashes($rowInfos['Fichier']);
													$FichierR=stripslashes($rowInfos['FichierRecyclage']);
												}
											}
										}
									}
									echo "<tr id='tr_L_".$rowL['Id']."'>";
										echo "<td id='td_L".$rowL['Id']."' bgcolor='#e4e7f0' style='border-bottom:1px dotted #000000' rowspan='2'>".stripslashes($rowL['Libelle'])."</td>";
										echo "<td bgcolor='#e4e7f0' style='border-bottom:1px dotted #000000'></td>";
										echo "<td bgcolor='#e4e7f0' style='border-bottom:1px dotted #000000'><input ".$read." style='width:350px;' id='Libelle_".$rowL['Id']."' name='Libelle_".$rowL['Id']."' value=\"".$Libelle."\" /></td>";
										echo "<td bgcolor='#e4e7f0'style='border-bottom:1px dotted #000000'><textarea ".$read." rows='4' cols='60' id='Description_".$rowL['Id']."' name='Description_".$rowL['Id']."' style='resize:none;'>".$Description."</textarea></td>";
										echo "<td bgcolor='#e4e7f0'>";
										if($_GET['Mode']=="A" || $Ligne['Id_Plateforme']<>0){
											echo "<input id=\"fichier_I_".$rowL['Id']."\" name=\"fichier_I_".$rowL['Id']."\" type=\"file\" onChange=\"CheckFichier('I','".$rowL['Id']."');\">\n";
										}
										if($_GET['Mode']=="M" && $Fichier<>""){	
											echo "<br>";
											echo "<a class=\"Info\" href=\"Docs/Formation/".$Ligne['Id']."/".$Fichier."\" target=\"_blank\">Ouvrir</a>";
											if($Ligne['Id_Plateforme']<>0){
												echo "<input type='hidden' name='fichieractuel_I_".$rowL['Id']."' value='".$Fichier."'>";
												echo "<br>";
												echo "<input type=\"checkbox\" id=\"supprFichier_I_".$rowL['Id']."\" name=\"supprFichier_I_".$rowL['Id']."\" onClick=\"CheckFichier('I','".$rowL['Id']."');\">Supprimer le fichier";
											}
										}
										echo "</td>\n";
									echo "</tr>";
									echo "<tr id='tr_R_".$rowL['Id']."'>";
										echo "<td bgcolor='#e4e7f0' style='border-bottom:1px dotted #000000'>R</td>";
										echo "<td bgcolor='#e4e7f0' style='border-bottom:1px dotted #000000'><input ".$read." style='width:350px;' id='LibelleR_".$rowL['Id']."' name='LibelleR_".$rowL['Id']."' value=\"".$LibelleR."\" /></td>";
										echo "<td bgcolor='#e4e7f0' style='border-bottom:1px dotted #000000'><textarea ".$read." rows='4' cols='60' id='DescriptionR_".$rowL['Id']."' name='DescriptionR_".$rowL['Id']."' style='resize:none;'>".$DescriptionR."</textarea></td>";
										echo "<td bgcolor='#e4e7f0'>";
										if($_GET['Mode']=="A" || $Ligne['Id_Plateforme']<>0){
											echo "<input id=\"fichier_R_".$rowL['Id']."\" name=\"fichier_R_".$rowL['Id']."\" type=\"file\" onChange=\"CheckFichier('R','".$rowL['Id']."');\">\n";
										}
										if($_GET['Mode']=="M" && $FichierR<>""){
											echo "<br>";
											echo "<a class='Info' href='Docs/Formation/".$Ligne['Id']."/".$FichierR."' target='_blank'>Ouvrir</a>";
											if($Ligne['Id_Plateforme']<>0){
												echo "<input type='hidden' name='fichieractuel_R_".$rowL['Id']."' value='".$FichierR."'>";
												echo "<br>";
												echo "<input type='checkbox' id='supprFichier_R_".$rowL['Id']."' name='supprFichier_R_".$rowL['Id']."' onClick='CheckFichier('R','".$rowL['Id']."');'>Supprimer le fichier";
											}
										}
										echo "</td>\n";
									echo "</tr>";
								}
							}
							?>
						<tr>
							<td bgcolor='#e4e7f0' height="4"></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr style="display:none;"><td><input id="lesFormsCompetences" name="lesFormsCompetences" value="<?php echo $listeForm;?>" readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="lesFormsQUALIPSO" name="lesFormsQUALIPSO" value="<?php echo $listeFormQUALIPSO;?>" readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="lesDocs" name="lesDocs" value="<?php echo $listeDocs;?>" readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="lesQualifs" name="lesQualifs" value="<?php echo $listeQualifs;?>" readonly="readonly"></td></tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td colspan="6" align="center">
					<input class="Bouton" type="submit" name="valider" value="<?php if($_GET['Mode']=="M"){if($LangueAffichage=="FR"){echo "Valider";}else{echo "Validate";}}else{if($LangueAffichage=="FR"){echo "Ajouter";}else{echo "Add";}}?>">
				</td>
			</tr>
			<?php 
				if($_GET['Mode']=="M" && $Ligne['Id_Plateforme']<>0 && DroitsFormationPlateforme(array($IdPosteResponsableFormation))){
			?>
			<tr>
				<td colspan="6" align="right">
					<input class="Bouton" type="submit" name="transformer" value="<?php if($_GET['Mode']=="M"){if($LangueAffichage=="FR"){echo "Transformer en formation SMQ";}else{echo "Transforming into QMS training";}}else{if($LangueAffichage=="FR"){echo "Ajouter";}else{echo "Add";}}?>">
				</td>
			</tr>
			<?php
				}
			?>
		</table>
		</form>
<?php
	echo "<script>AfficherRecyclagePla();</script>";
	echo "<script>AfficherLanguePla();</script>";
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"UPDATE form_formation SET Suppr=1,Id_Personne_MAJ=".$IdPersonneConnectee.", Date_MAJ='".date('Y-m-d')."' WHERE Id=".$_GET['Id']);		
		//Suppression des besoins
		//$Id = Id Formation
		//$_GET['Id_Plateforme'] La plateforme		
		
		$Id_Formation = $Id;
		
		//Récupération des prestations
		$res = get_Prestations($_GET['Id_Plateforme']);
		
		//Récupèration des personnes avec des besoins pour cette formation
		while($r = mysqli_fetch_array($res)) {
		  $Id_Prestation = $r['Id'];
		    
		  //Suppression des formations liées à des prestations et à des métiers 
		  supprimer_lien_MetierPrestaFormation($Id_Prestation, -1, $Id_Formation);
		
		  $ressource = get_Personnes_besoinPrestationFormation($Id_Prestation, $Id_Formation);
		  
    	  //Récupérer la ou les qualifications
		  $Ids_Qualif = get_liste_identifiantsQualifications($Id_Formation);
			
    	  //Suppression des besoins
		  $besoinsAffectes = Supprimer_BesoinsFormations($Id_Prestation, $Id_Formation, -1, -1, "Ajout_FormationPlateforme");

    	  //Calculer la chaine des Id_Besoins
    	  $tableau = Array();
    	  while($row = mysqli_fetch_array($besoinsAffectes))
    	    array_push($tableau, $row['Id']);
    	  $chaineIdsBesoins = implode(",", $tableau);
    	  
	      //Suppression des 'B'
    	  while($row = mysqli_fetch_array($ressource))
    	    supprimer_lesB($row['Id_Personne'], $Ids_Qualif, $chaineIdsBesoins);
  
    	  //envoyerMailDAlerte($Id_Prestation, $Id_Formation, $bdd);
		}
		echo "<script charset='utf-8'>FermerEtRecharger(\"".$_GET['motcles']."\",\"".$_GET['Id_Plateforme']."\");</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
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
	if($_SESSION['Id_Personne']<>""){
		$req="SELECT Id_Plateforme, Id_Domaine, Programme FROM new_competences_prestation WHERE Id=".$_POST['Id_Prestation'];
		$resultsite=mysqli_query($bdd,$req);
		$nbenreg=mysqli_num_rows($resultsite);
		$programme="";
		$Id_Domaine=0;
		$Id_Plateforme=0;
		$OuvertureAutresPlateformes=0;
		if($nbenreg>0)
		{
			$rowsite=mysqli_fetch_array($resultsite);
			$programme=$rowsite['Programme'];
			$Id_Domaine=$rowsite['Id_Domaine'];
			$Id_Plateforme=$rowsite['Id_Plateforme'];
		}
		if($_POST['posteDefinitif']==1){$OuvertureAutresPlateformes=1;}
		
		$requete="INSERT INTO recrut_annonce 
			(Id_Demandeur,DateDemande,Id_Prestation,Id_Domaine,Programme,Lieu,Metier,
			Nombre,DateBesoin,PosteDefinitif,Duree,CreationPoste,Id_TypeHoraire,Id_CategorieProfessionnelle,Horaire,DescriptifPoste,SavoirFaire,SavoirEtre,Langue,Prerequis,CategorieProf,IGD,Salaire,MotifDemande,OuvertureAutresPlateformes,Division,DemandePSE) 
			VALUES 
			(".$_SESSION['Id_Personne'].",'".date('Y-m-d')."',".$_POST['Id_Prestation'].",".$_POST['domaine'].",'".addslashes($_POST['programme'])."','".addslashes($_POST['lieu'])."','".addslashes($_POST['metier'])."',
			".$_POST['nombr'].",'".TrsfDate_($_POST['dateSouhaitee'])."',".$_POST['posteDefinitif'].",'".addslashes($_POST['duree'])."',".$_POST['etatPoste'].",
			".$_POST['Id_TypeHoraire'].",".$_POST['categorie'].",'".addslashes($_POST['horaire'])."',
			'".addslashes($_POST['DescriptifPoste'])."','".addslashes($_POST['savoirfaire'])."','".addslashes($_POST['savoiretre'])."','".addslashes($_POST['Langues'])."','".addslashes($_POST['Prerequis'])."','".addslashes($_POST['categorieProfessionnelle'])."','".addslashes($_POST['IGD'])."','".addslashes($_POST['salaire'])."','".addslashes($_POST['MotifDemande'])."',".$OuvertureAutresPlateformes.",'".addslashes($_POST['division'])."',".$_POST['demandePSE'].") ";
		$result=mysqli_query($bdd,$requete);
		$IdCree = mysqli_insert_id($bdd);
		$bEnregistrement=true;
		
		if($IdCree>0){
			$req="SELECT Id, Libelle FROM recrut_savoiretre WHERE Suppr=0 ORDER BY Libelle ";
			$result=mysqli_query($bdd,$req);
			$nbenreg=mysqli_num_rows($result);
			if($nbenreg>0)
			{
				while($rowSE=mysqli_fetch_array($result))
				{
					if(isset($_POST['savoiretres_'.$rowSE['Id']])){
						$req="INSERT INTO recrut_annonce_savoiretre (Id_Annonce,Id_SavoirEtre) VALUES (".$IdCree.",".$rowSE['Id'].") ";
						$resultAdd=mysqli_query($bdd,$req);
					}
				}
			}
			
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
			if($Problem==0){

				$requeteUpt="UPDATE recrut_annonce SET";
				$requeteUpt.=" FicheMetier='".$Fichier."'";
				$requeteUpt.=" WHERE Id=".$IdCree;
				$resultUpt=mysqli_query($bdd,$requeteUpt);
			}
		}
		
		
		if(DroitsPrestation(array($IdPosteResponsableOperation),$_POST['Id_Prestation']) || DroitsFormation1Plateforme($Id_Plateforme,array($IdPosteResponsablePlateforme))){
			$requeteUpdate="UPDATE recrut_annonce SET 
						Id_Validateur=".$_SESSION['Id_Personne'].",
						DateValidation='".date('Y-m-d')."',
						EtatValidation=1
						WHERE Id=".$IdCree." ";
				$resultat=mysqli_query($bdd,$requeteUpdate);
		}
		elseif(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH)) || DroitsFormation1Plateforme($Id_Plateforme,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
			//17=Siège social
			$requeteUpdate="UPDATE recrut_annonce SET 
						Id_Validateur=".$_SESSION['Id_Personne'].",
						DateValidation='".date('Y-m-d')."',
						EtatValidation=1,
						Id_Approbation=".$_SESSION['Id_Personne'].",
						DateApprobation='".date('Y-m-d')."',
						EtatApprobation=1,
						OuvertureAutresPlateformes=".$_POST['deploiementOffre']."
						WHERE Id=".$IdCree." ";
				$resultat=mysqli_query($bdd,$requeteUpdate);
				
			creerMail("BESOIN INTERNE",$_SESSION['Langue'],$IdCree);
		}
	}
	echo "<script>FermerEtRecharger();</script>";
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

<form id="formulaire" class="test" enctype="multipart/form-data" action="Dupliquer_Besoin.php" method="post" onsubmit=" return selectall();">
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
							<td width="10%" class="Libelle" colspan="8" align="center">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Déclarer un besoin";}else{echo "Declare a need";} ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
							<td width="10%">&nbsp;
									<select name="Id_Prestation" id="Id_Prestation" onchange="Recharge_Responsables();" style="width:100px;">
									<?php
										if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
											$requeteSite="SELECT Id, Libelle
												FROM new_competences_prestation
												WHERE Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27)

												AND Active=0
												ORDER BY Libelle ASC";
										}
										else{
											$requeteSite="SELECT Id, Libelle
												FROM new_competences_prestation
												WHERE 
												(Id IN 
													(SELECT Id_Prestation 
													FROM new_competences_personne_poste_prestation 
													WHERE Id_Personne=".$_SESSION["Id_Personne"]."
													AND Id_Poste IN (".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.") 
													)
												OR Id_Plateforme IN 
													(SELECT Id_Plateforme 
													FROM new_competences_personne_poste_plateforme
													WHERE Id_Personne=".$_SESSION["Id_Personne"]."
													AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") 
													)	
												)
												AND Active=0
												ORDER BY Libelle ASC";
										}
										
										$resultsite=mysqli_query($bdd,$requeteSite);
										while($rowsite=mysqli_fetch_array($resultsite))
										{
											$selected="";
											if($row['Id_Prestation']==$rowsite['Id']){$selected="selected";}
											echo "<option value='".$rowsite['Id']."' ".$selected.">";
											echo str_replace("'"," ",stripslashes($rowsite['Libelle']))."</option>\n";
										}
									?>
								</select>
							</td>
							<td width="10%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Division";}else{echo "Division";}?> : </td>
							<td width="18%">&nbsp;
								<select name="division" id="division">
									<option value=""></option>
									<option value="FAL" <?php if($row['Division']=="FAL"){echo "selected";} ?>>FAL</option>
									<option value="SUC" <?php if($row['Division']=="SUC"){echo "selected";} ?>>SUC</option>
									<option value="DIVISIONS" <?php if($row['Division']=="DIVISIONS"){echo "selected";} ?>>DIVISIONS</option>
								</select>
							</td>
							<td class="Libelle" width="8%" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Programme";}else{echo "Programm";}?> : </td>
							<td width="20%">
								&nbsp;<input style="width:150px" name="programme" id="programme" value="<?php echo stripslashes($row['Programme']); ?>"/>
							</td>
							<td width="10%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;<?php if($_SESSION['Id_Personne']<>1132 && $_SESSION['Id_Personne']<>4320){echo "display:none;";} ?>">&nbsp;<?php if($LangueAffichage=="FR"){echo "Demande PSE";}else{echo "PSE request";}?> : </td>
							<td width="18%" style="<?php if($_SESSION['Id_Personne']<>1132 && $_SESSION['Id_Personne']<>4320){echo "display:none;";} ?>">&nbsp;
								<select name="demandePSE" id="demandePSE">
									<option value="0" <?php if($row['DemandePSE']==0){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}?></option>
									<option value="1" <?php if($row['DemandePSE']==1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";}?></option>
								</select>
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
							<td width="10%">&nbsp;<input type="date" id="dateSouhaitee" name="dateSouhaitee" size="10" value=""></td>
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
							<td id="duree1" class="Libelle" width="8%" bgcolor="#2e5496" style="color:#ffffff;<?php  if($row['PosteDefinitif']==1){echo "display:none;";}?>" >&nbsp;<?php if($LangueAffichage=="FR"){echo "Durée";}else{echo "Duration";}?> : </td>
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
						</tr>
						<tr><td height="4"></td></tr>
						<tr id="trDeploiement" style="display:none;">
							<td width="8%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Déploiement de l'offre";}else{echo "Deployment of the offer";}?> : </td>
							<td width="18%">
								&nbsp;<select name="deploiementOffre" id="deploiementOffre">
									<option value="1"><?php if($LangueAffichage=="FR"){echo "Autres unités d'exploitations";}else{echo "Other operating units";}?></option>
									<option value="0"><?php if($LangueAffichage=="FR"){echo "Interne";}else{echo "Internal";}?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="10" align="center">
								<input class="Bouton" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Ajouter";}else{echo "Add";} ?>"/>
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
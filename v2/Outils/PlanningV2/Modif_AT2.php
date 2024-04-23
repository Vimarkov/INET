<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="AccidentTravail2.js?time=<?php echo time();?>"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript" src="../JS/mask.js"></script>
	<script type="text/javascript" src="../JS/bootstrap.min.js"></script>
    <script type="text/javascript" src="../JS/prettify.js"></script>
    <script type="text/javascript" src="../JS/bootstrap-timepicker.js"></script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
Ecrire_Code_JS_Init_Date();

$DirFichier="Outils/PlanningV2/AT/";
$DirFichier2="AT/";

$DateJour=date("Y-m-d");
$bEnregistrement=false;
if($_POST){
	if(isset($_POST['btnEnregistrer2'])){
		$fichierPassage="";
		$reqPassage="";
		//S'il y avait une fichier
		if(isset($_POST['SupprFichier']))
		{
			if($_POST['SupprFichier'])
			{
				if(file_exists ($DirFichier2.$_POST['fichieractuel'])){
					if(unlink($DirFichier2.$_POST['fichieractuel'])){$fichierPassage="";}
				}
				else{
					$fichierPassage="";
				}
				$reqPassage="FichierPassageInfirmerie='', ";
			}
		}
		//****TRANSFERT FICHIER****
		if($_FILES['fichier']['name']!="")
		{
			$tmp_file=$_FILES['fichier']['tmp_name'];
			if(is_uploaded_file($tmp_file)){
				//On vérifie la taille du fichiher
				if(filesize($_FILES['fichier']['tmp_name'])<=$_POST['MAX_FILE_SIZE'])
				{
					// on copie le fichier dans le dossier de destination
					$name_file=$_FILES['fichier']['name'];
					$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
					while(file_exists($DirFichier2.$name_file)){$name_file="_".date('j-m-y')."_".date('H-i-s')." ".$name_file;}
					if(move_uploaded_file($tmp_file,$DirFichier2.$name_file))
					{$fichierPassage=$name_file;$reqPassage="FichierPassageInfirmerie='".$fichierPassage."', ";}
				}
			}
		}
		
		//Création d'un accident de travail
		$DateRH="0001-01-01";
		$Id_RH=0;
		if($_POST['Menu']==4){
			$DateRH=$DateJour;
			$Id_RH=$_SESSION['Id_Personne'];
		}
		
		$tabPresta=explode("_",$_POST['Id_PrestationPole']);
		$Id_Prestation=$tabPresta[0];
		$Id_Pole=$tabPresta[1];
		
		$IdTypeVehicule=0;
		if(isset($_POST['typevehicule'])){
			$IdTypeVehicule=$_POST['typevehicule'];
		}
		$ConditionClim=0;
		if(isset($_POST['conditionsClim'])){$ConditionClim=1;}
		$MauvaisEtatInfra=0;
		if(isset($_POST['mauvaisEtatInfra'])){$MauvaisEtatInfra=1;}
		$TrajetAller=0;
		if(isset($_POST['trajet'])){
			$TrajetAller=$_POST['trajet'];
		}
		$HoraireTravail=0;
		if(isset($_POST['horairesSpec'])){$HoraireTravail=1;}
		$ProblemeTechnique=0;
		if(isset($_POST['pbTechnique'])){$ProblemeTechnique=1;}
		
		$req="UPDATE rh_personne_at 
			SET Id_Prestation=".$Id_Prestation.",
			Id_Pole=".$Id_Pole.",
			Id_Metier=".$_POST['metier'].",
			Id_Lieu_AT=".$_POST['lieus'].",
			".$reqPassage."
			DateAT='".TrsfDate_($_POST['dateAT'])."',
			HeureAT='".$_POST['heureAT']."',
			Id_TypeContrat=".$_POST['Id_Contrat'].",
			Contrat='".addslashes($_POST['contrat'])."',
			Adresse='".addslashes($_POST['adresse'])."',
			CP='".addslashes($_POST['cp'])."',
			Ville='".addslashes($_POST['ville'])."',
			NumSecurite='".addslashes($_POST['numSecu'])."',
			DateNaissance='".TrsfDate_($_POST['dateNaissance'])."',
			Anciennete='".TrsfDate_($_POST['anciennete'])."',
			HeureDebutAM='".$_POST['heureDebut1']."',
			HeureFinAM='".$_POST['heureFin1']."',
			HeureDebutPM='".$_POST['heureDebut2']."',
			HeureFinPM='".$_POST['heureFin2']."',
			Id_TypeVehicule=".$IdTypeVehicule.",
			ConditionClim=".$ConditionClim.",
			MauvaisEtatInfra=".$MauvaisEtatInfra.",
			TrajetAller=".$TrajetAller.",
			HoraireTravail=".$HoraireTravail.",
			ProblemeTechnique=".$ProblemeTechnique.",
			CommentaireCirconstance='".addslashes($_POST['commentaireCondition1'])."',
			CommentaireCirconstance2='".addslashes($_POST['commentaireCondition2'])."',
			LieuAccident='".addslashes($_POST['lieu'])."',
			SIRETClient='".addslashes($_POST['siretClient'])."',
			Activite='".addslashes($_POST['activiteVictime'])."',
			CommentaireNature='".addslashes($_POST['natureAccident'])."',
			ArretDeTravail=".$_POST['arretTravail'].",
			EvacuationVers='".addslashes($_POST['evacuationVers'])."',
			AutreVictime='".addslashes($_POST['autreVictime'])."',
			TiersResponsable='".addslashes($_POST['tiersResponsable'])."',
			Temoin='".addslashes($_POST['temoin'])."',
			CoordonneesTemoins='".addslashes($_POST['coordonnees'])."',
			1erePersonneAvertie='".addslashes($_POST['personne1'])."',
			DateConnaissanceAT='".TrsfDate_($_POST['dateConnaissanceAT'])."',
			HeureConnaissanceAT='".$_POST['heureConnaisanceAT']."',
			DoutesCirconstances='".addslashes($_POST['doutes'])."',
			AutresInformations='".addslashes($_POST['autresInfos'])."',
			DatePriseEnCompteRH='".$DateRH."',
			Id_RH=".$Id_RH." 
		WHERE Id=".$_POST['Id'];
		$resultModif=mysqli_query($bdd,$req);
		$IdCree = $_POST['Id'];
		if($IdCree>0){
			
			//Suppression
			$req="UPDATE rh_personne_at_siegelesion SET Suppr=1, Id_Suppr=".$_SESSION['Id_Personne'].",DateSuppr='".date('Y-m-d')."' WHERE Id_Personne_AT=".$IdCree;
			$resultModif=mysqli_query($bdd,$req);
			$req="UPDATE rh_personne_at_nature_lesion SET Suppr=1, Id_Suppr=".$_SESSION['Id_Personne'].",DateSuppr='".date('Y-m-d')."' WHERE Id_PersonneAT=".$IdCree;
			$resultModif=mysqli_query($bdd,$req);
			$req="UPDATE rh_personne_at_objet SET Suppr=1, Id_Suppr=".$_SESSION['Id_Personne'].",DateSuppr='".date('Y-m-d')."' WHERE Id_Personne_AT=".$IdCree;
			$resultModif=mysqli_query($bdd,$req);
			
			//Ajout des sièges lesions 
			$req="SELECT Id, Libelle, CoteGD FROM rh_siege_lesion_at WHERE Suppr=0";
			$result=mysqli_query($bdd,$req);
			$nbResulta=mysqli_num_rows($result);
			if ($nbResulta>0){
				while($row=mysqli_fetch_array($result)){
					
					if($row['Libelle']=="Autres" || $row['Libelle']=="Autre" || $row['Libelle']=="Others" || $row['Libelle']=="Other"|| 
					$row['Libelle']=="°Autres" || $row['Libelle']=="°Autre" || $row['Libelle']=="°Others" || $row['Libelle']=="°Other"){
						if(isset($_POST['siegeLesionAutre_'.$row['Id']])){
							if($_POST['siegeLesionAutre_'.$row['Id']]<>""){
								$req="INSERT INTO rh_personne_at_siegelesion (Id_Personne_AT,Id_SiegeLesion,AutreSiege,Gauche,Droite)
								VALUES (".$IdCree.",".$row['Id'].",'".stripslashes($_POST['siegeLesionAutre_'.$row['Id']])."',0,0) ";
								$resultAdd=mysqli_query($bdd,$req);
							}
						}
					}
					else{
						if($row['CoteGD']==0){
							if(isset($_POST['siegeLesion_'.$row['Id']])){
								$req="INSERT INTO rh_personne_at_siegelesion (Id_Personne_AT,Id_SiegeLesion,AutreSiege,Gauche,Droite)
								VALUES (".$IdCree.",".$row['Id'].",'',0,0) ";
								$resultAdd=mysqli_query($bdd,$req);
							}
						}
						else{
							$gauche=0;
							$droite=0;
							if(isset($_POST['siegeLesionG_'.$row['Id']])){$gauche=1;}
							if(isset($_POST['siegeLesionD_'.$row['Id']])){$droite=1;}
							
							if($gauche>0 || $droite>0){
								$req="INSERT INTO rh_personne_at_siegelesion (Id_Personne_AT,Id_SiegeLesion,AutreSiege,Gauche,Droite)
								VALUES (".$IdCree.",".$row['Id'].",'',".$gauche.",".$droite.") ";
								$resultAdd=mysqli_query($bdd,$req);
							}
						}
					}
				}
			}
			
			//Ajout des natures des lésions
			$req="SELECT Id, Libelle FROM rh_nature_lesion WHERE Suppr=0";
			$result=mysqli_query($bdd,$req);
			$nbResulta=mysqli_num_rows($result);
			if ($nbResulta>0){
				while($row=mysqli_fetch_array($result)){
					
					if($row['Libelle']=="Autres" || $row['Libelle']=="Autre" || $row['Libelle']=="Others" || $row['Libelle']=="Other"|| 
					$row['Libelle']=="°Autres" || $row['Libelle']=="°Autre" || $row['Libelle']=="°Others" || $row['Libelle']=="°Other"){
						if(isset($_POST['natureLesionAutre_'.$row['Id']])){
							if($_POST['natureLesionAutre_'.$row['Id']]<>""){
								$req="INSERT INTO rh_personne_at_nature_lesion(Id_PersonneAT,Id_NatureLesion,AutreNature)
								VALUES (".$IdCree.",".$row['Id'].",'".stripslashes($_POST['natureLesionAutre_'.$row['Id']])."') ";
								$resultAdd=mysqli_query($bdd,$req);
							}
						}
					}
					else{
						if(isset($_POST['natureLesion_'.$row['Id']])){
							$req="INSERT INTO rh_personne_at_nature_lesion (Id_PersonneAT,Id_NatureLesion,AutreNature)
							VALUES (".$IdCree.",".$row['Id'].",'') ";
							$resultAdd=mysqli_query($bdd,$req);
						}
					}
				}
			}
			
			//Ajout des objets
			$req="SELECT Id FROM rh_typeobjet_at WHERE Suppr=0";
			$result=mysqli_query($bdd,$req);
			$nbResulta=mysqli_num_rows($result);
			if ($nbResulta>0){
				while($row=mysqli_fetch_array($result)){
					if(isset($_POST['objet_'.$row['Id']])){
						if($_POST['objet_'.$row['Id']]<>""){
							$req="INSERT INTO rh_personne_at_objet (Id_Personne_AT,Id_TypeObjet,Objet)
							VALUES (".$IdCree.",".$row['Id'].",'".stripslashes($_POST['objet_'.$row['Id']])."') ";
							$resultAdd=mysqli_query($bdd,$req);
						}
					}
				}
			}
		}
	}
	echo "<script>FermerEtRecharger(".$_POST['Menu'].");</script>";
}

if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

if($_GET){
	if($_GET['Mode']=="S"){
		$req="UPDATE rh_personne_at 
		SET Suppr=1,
		DateSuppr='".date('Y-m-d')."',
		Id_Suppr=".$_SESSION['Id_Personne']."
		WHERE Id=".$_GET['Id'];
		$resultSuppr=mysqli_query($bdd,$req);
		echo "<script>FermerEtRecharger(".$_GET['Menu'].");</script>";
	}
}
$req="SELECT Id,Id_Personne,Id_Createur,Id_Prestation,Id_Pole,Id_Metier,Id_Lieu_AT,FichierPassageInfirmerie,
		DateCreation,DateAT,HeureAT,Id_TypeContrat,Adresse,CP,Ville,NumSecurite,DateNaissance,Anciennete,HeureDebutAM,HeureFinAM,
		HeureDebutPM,HeureFinPM,LieuAccident,SIRETClient,Activite,CommentaireNature,ArretDeTravail,EvacuationVers,AutreVictime,
		(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Prestation,
		TiersResponsable,Temoin,CoordonneesTemoins,1erePersonneAvertie,DateConnaissanceAT,HeureConnaissanceAT,DoutesCirconstances,AutresInformations,
		Id_TypeVehicule,ConditionClim,MauvaisEtatInfra,TrajetAller,HoraireTravail,ProblemeTechnique,CommentaireCirconstance,CommentaireCirconstance2,
		DateRemplissage,DatePriseEnCompteRH,Id_RH,Contrat
	FROM rh_personne_at
	WHERE Id=".$_GET['Id'];
$result=mysqli_query($bdd,$req);
$row=mysqli_fetch_array($result);

$typeChamps="text";
$readOnly="readonly";
$disabled="disabled='disabled'";
if($Menu==4){
	$typeChamps="date";
	$readOnly="";
	$disabled="";
}
?>

<form id="formulaire" enctype="multipart/form-data" class="test" action="Modif_AT2.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="90%" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de l'AT :";}else{echo "Date of the accident :";} ?> </td>
							<td width="10%"><input type="date" style="text-align:center;" id="dateAT" name="dateAT" size="10" <?php echo $readOnly; ?> value="<?php echo AfficheDateFR($row['DateAT']); ?>"></td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de l'AT : ";}else{echo "Time of the accident : ";} ?> </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small" style="text-align:center;" name="heureAT" id="heureAT" <?php echo $readOnly; ?> size="10" type="time" value="<?php echo $row['HeureAT']; ?>">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?></td>
							<td width="10%">
								<select name="Id_Personne" id="Id_Personne" disabled="disabled">
								<?php
								$rq="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
									FROM new_rh_etatcivil
									LEFT JOIN new_competences_personne_prestation 
									ON new_rh_etatcivil.Id=new_competences_personne_prestation.Id_Personne 
									WHERE new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
									AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin='0001-01-01' OR new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."') ";
								if($Menu==4){
									$rq.="AND ((SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN 
									(
										SELECT Id_Plateforme 
										FROM new_competences_personne_poste_plateforme
										WHERE Id_Personne=".$_SESSION['Id_Personne']." 
										AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
									)
									OR new_rh_etatcivil.Id=".$row['Id_Personne']."
									)";
								}
								else{
									if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
										$rq.="AND ((SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN 
										(
											SELECT Id_Plateforme 
											FROM new_competences_personne_poste_plateforme
											WHERE Id_Personne=".$_SESSION['Id_Personne']." 
											AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
										)
										OR new_rh_etatcivil.Id=".$row['Id_Personne']."
										)";
									}
									else{
										$rq.="AND (CONCAT(Id_Prestation,'_',Id_Pole) IN 
										(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
										FROM new_competences_personne_poste_prestation 
										WHERE Id_Personne=".$_SESSION["Id_Personne"]."
										AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
										)
										OR new_rh_etatcivil.Id=".$row['Id_Personne']."
										)";
									}
								}
								$rq.="ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
								$resultpersonne=mysqli_query($bdd,$rq);
								while($rowpersonne=mysqli_fetch_array($resultpersonne))
								{
									$selected="";
									if($row['Id_Personne']==$rowpersonne['Id']){$selected="selected";}
									echo "<option value='".$rowpersonne['Id']."' ".$selected.">".str_replace("'"," ",$rowpersonne['Personne'])."</option>\n";
								}
								?>
								</select>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat : ";}else{echo "Contract : ";} ?> </td>
							<td <?php if($Menu==3){echo "width='60%'";}else{echo "width='10%'";} ?>>
								<?php 
									$contrat="";
									if($row['Id_TypeContrat']==0){
										$contrat=$row['Contrat'];
									}
									else{
										if($_SESSION["Langue"]=="FR"){
											$req="SELECT Libelle FROM rh_typecontrat WHERE Id=".$row['Id_TypeContrat'];
										}
										else{
											$req="SELECT LibelleEN AS Libelle FROM rh_typecontrat WHERE Id=".$row['Id_TypeContrat'];
										}
										$resultContrat=mysqli_query($bdd,$req);
										$rowContrat=mysqli_fetch_array($resultContrat);
										$contrat=$rowContrat['Libelle'];
									}
								?>
								<input name="contrat" id="contrat" size="10" value="<?php echo $contrat; ?>" readonly="readonly">
								<input type="hidden" name="Id_Contrat" id="Id_Contrat" value="0" />
							</td>
						</tr>
						<tr <?php if($Menu==3){echo "style='display:none;'";} ?>><td height="4"></td></tr>
						<tr <?php if($Menu==3){echo "style='display:none;'";} ?>>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Adresse :";}else{echo "Address :";} ?></td>
							<td width="10%">
								<input name="adresse" id="adresse" size="50" <?php echo $readOnly; ?> value="<?php echo stripslashes($row['Adresse']); ?>">
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "CP :";}else{echo "PC :";} ?></td>
							<td width="10%">
								<input name="cp" id="cp" size="8" <?php echo $readOnly; ?> value="<?php echo stripslashes($row['CP']); ?>">
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Ville :";}else{echo "City :";} ?></td>
							<td width="10%">
								<input name="ville" id="ville" size="15" <?php echo $readOnly; ?> value="<?php echo stripslashes($row['Ville']); ?>">
							</td>
						</tr>
						<tr <?php if($Menu==3){echo "style='display:none;'";} ?>><td height="4"></td></tr>
						<tr <?php if($Menu==3){echo "style='display:none;'";} ?>>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° de sécu :";}else{echo "Security number :";} ?></td>
							<td width="10%">
								<input name="numSecu" id="numSecu" size="15" <?php echo $readOnly; ?> value="<?php echo stripslashes($row['NumSecurite']); ?>">
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de naissance :";}else{echo "Birth date :";} ?></td>
							<td width="10%">
								<input type="date" name="dateNaissance" id="dateNaissance" <?php echo $readOnly; ?> size="10" value="<?php echo AfficheDateFR($row['DateNaissance']); ?>">
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Ancienneté :";}else{echo "Seniority :";} ?></td>
							<td width="10%">
								<input type="date" name="anciennete" id="anciennete" size="5" <?php echo $readOnly; ?> value="<?php echo stripslashes($row['Anciennete']); ?>">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Métier :";}else{echo "Job :";} ?></td>
							<td width="10%">
								<select name="metier" id="metier" style="width:200px" <?php echo $disabled; ?>>
								<?php
								$rq="SELECT Id, Libelle
									FROM new_competences_metier
									WHERE Suppr=0
									ORDER BY Libelle ASC";
								$result=mysqli_query($bdd,$rq);
								while($rowMetier=mysqli_fetch_array($result))
								{
									$selected="";
									if($row['Id_Metier']==$rowMetier['Id']){$selected="selected";}
									echo "<option value='".$rowMetier['Id']."' ".$selected.">".str_replace("'"," ",$rowMetier['Libelle'])."</option>\n";
								}
								?>
								</select>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Horaire de travail le jour de l'AT";}else{echo "Work schedule on the day of the accident";} ?> : </td>
							<td width="10%" colspan="4">
								<?php if($_SESSION["Langue"]=="FR"){echo " de ";}else{echo " from ";} ?>
								<div class="input-group bootstrap-timepicker timepicker" style="display: inline">
									<input class="form-control input-small" style="text-align:center;" <?php echo $readOnly; ?> name="heureDebut1" id="heureDebut1" size="10" type="time" value="<?php echo $row['HeureDebutAM']; ?>">
								</div>
								<?php if($_SESSION["Langue"]=="FR"){echo " à ";}else{echo " to ";} ?>
								<div class="input-group bootstrap-timepicker timepicker" style="display: inline">
									<input class="form-control input-small" style="text-align:center;" <?php echo $readOnly; ?> name="heureFin1" id="heureFin1" size="10" type="time" value="<?php echo $row['HeureFinAM']; ?>">
								</div>
								<?php if($_SESSION["Langue"]=="FR"){echo " et de ";}else{echo " and from ";} ?>
								<div class="input-group bootstrap-timepicker timepicker" style="display: inline">
									<input class="form-control input-small" style="text-align:center;" <?php echo $readOnly; ?> name="heureDebut2" id="heureDebut2" size="10" type="time" value="<?php echo $row['HeureDebutPM']; ?>">
								</div>
								<?php if($_SESSION["Langue"]=="FR"){echo "à";}else{echo "to";} ?>
								<div class="input-group bootstrap-timepicker timepicker" style="display: inline">
									<input class="form-control input-small" style="text-align:center;" <?php echo $readOnly; ?> name="heureFin2" id="heureFin2" size="10" type="time" value="<?php echo $row['HeureFinPM']; ?>">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #fd616b"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="4"><?php if($_SESSION["Langue"]=="FR"){echo "Lieu exact de l'accident (adresse exacte de l'usine où s'est produit l'AT. En cas d'accident de trajet indiquer la commune, route,...)";}
							else{echo "Accurate location of the accident (exact address of the factory where the AT occurred, in the event of a commuting accident, indicate the municipality, road, etc.)";} ?></td>
						</tr>
						<tr>
							<td colspan="2">
								<textarea name="lieu" id="lieu" cols="90" rows="3" <?php echo $readOnly; ?> noresize="noresize"><?php echo stripslashes($row['LieuAccident']); ?></textarea>
							</td>
							<td width="10%" class="Libelle" valign="top"><?php if($_SESSION["Langue"]=="FR"){echo "SIRET du client :";}else{echo "SIRET of the client :";} ?></td>
							<td width="10%" valign="top">
								<input type="text" name="siretClient" id="siretClient" <?php echo $readOnly; ?> size="25" value="<?php echo stripslashes($row['SIRETClient']); ?>">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?></td>
							<td width="10%">
								<select name="Id_Plateforme" id="Id_Plateforme" style="width:300px" <?php echo $disabled; ?> onchange="RechargerPrestation('<?php echo $row['Id_Prestation']."_".$row['Id_Pole'];?>')">
								<option value="0"></option>
									<?php
										$Id_Plateforme=0;
										if($row['Id_Prestation']<>0){
											$req="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$row['Id_Prestation'];
											$resultPlat=mysqli_query($bdd,$req);
											$nbPlat=mysqli_num_rows($resultPlat);
											if($nbPlat>0){
												$rowPla=mysqli_fetch_array($resultPlat);
												$Id_Plateforme=$rowPla['Id_Plateforme'];
											}
										}
										
										$requetePlat="SELECT Id, Libelle
											FROM new_competences_plateforme
											WHERE Id NOT IN (11,14)
											ORDER BY Libelle";
										$resultsPlat=mysqli_query($bdd,$requetePlat);
										while($rowPlat=mysqli_fetch_array($resultsPlat))
										{
											$selected="";
											if($Id_Plateforme==$rowPlat['Id']){$selected="selected";}
											echo "<option value='".$rowPlat['Id']."' ".$selected.">";
											echo str_replace("'"," ",stripslashes($rowPlat['Libelle']))."</option>\n";
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
							<td width="10%">
								<select name="Id_PrestationPole" id="Id_PrestationPole" class="Id_PrestationPole" <?php echo $disabled; ?> style="width:400px">
									<option value="0_0"></option>
									<?php
										$requeteSite="SELECT DISTINCT Id, Libelle, '' AS LibellePole, 0 AS Id_Pole, Id_Plateforme
											FROM new_competences_prestation
											WHERE Active=0
											AND Id NOT IN (
												SELECT Id_Prestation
												FROM new_competences_pole  
												WHERE Actif=0												
											)
											
											UNION 
											
											SELECT DISTINCT new_competences_pole.Id_Prestation, new_competences_prestation.Libelle,
												new_competences_pole.Libelle AS LibellePole, new_competences_pole.Id AS Id_Pole, new_competences_prestation.Id_Plateforme
												FROM new_competences_pole
												INNER JOIN new_competences_prestation
												ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
												AND Active=0
												AND Actif=0
												
											ORDER BY Libelle, LibellePole";
										$resultsite=mysqli_query($bdd,$requeteSite);
										$i=0;
										
										while($rowsite=mysqli_fetch_array($resultsite))
										{
											$selected="";
											if($row['Id_Prestation']."_".$row['Id_Pole']==$rowsite['Id']."_".$rowsite['Id_Pole']){$selected="selected";}
											echo "<option value='".$rowsite['Id']."_".$rowsite['Id_Pole']."' ".$selected.">";
											$pole="";
											if($rowsite['Id_Pole']>0){$pole=" - ".$rowsite['LibellePole'];}
											echo str_replace("'"," ",stripslashes($rowsite['Libelle'].$pole))."</option>\n";
											echo "<script>Liste_PrestaPole[".$i."] = new Array(".$rowsite['Id'].",".$rowsite['Id_Pole'].",'".str_replace("'"," ",$rowsite['Libelle'])."','".str_replace("'"," ",$rowsite['LibellePole'])."',".$rowsite['Id_Plateforme'].");</script>";
											$i++;
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #fd616b"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="2"><?php if($_SESSION["Langue"]=="FR"){echo "Etait-ce :";}else{echo "Was it :";} ?></td>
							<td width="10%" class="Libelle" colspan="2"><?php if($_SESSION["Langue"]=="FR"){echo "Objet dont le contact a blessé la victime :";}else{echo "Object whose contact hurt the victim :";} ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="2">
								<div id='Div_Lieu' style='height:160px;width:100%;overflow:auto;'>
									<?php
									echo "<table width='100%'>";
									if($_SESSION["Langue"]=="FR"){$req="SELECT Id, Libelle FROM rh_lieu_at WHERE Suppr=0 ORDER BY Libelle";}
									else{$req="SELECT Id, LibelleEN AS Libelle FROM rh_lieu_at WHERE Suppr=0 ORDER BY LibelleEN";}
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($row2=mysqli_fetch_array($result)){
											$select="";
											if($row['Id_Lieu_AT']==$row2['Id']){$select="checked";}
											echo "<tr><td><input type='radio' ".$disabled." class='lieus' name='lieus' value='".$row2['Id']."' ".$select.">".$row2['Libelle']."</td></tr>";
										}
									}
									echo "</table>";
									?>
								</div>
							</td>
							<td colspan="2">
								<div id='Div_Objet' style='height:160px;width:100%;overflow:auto;'>
									<?php
									echo "<table width='100%'>";
									if($_SESSION["Langue"]=="FR"){$req="SELECT Id, Libelle FROM rh_typeobjet_at WHERE Suppr=0 ORDER BY Libelle";}
									else{$req="SELECT Id, LibelleEN AS Libelle FROM rh_typeobjet_at WHERE Suppr=0 ORDER BY LibelleEN";}
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									
									$req="SELECT Objet,Id_TypeObjet		
									FROM rh_personne_at_objet 
									WHERE Suppr=0 
									AND Id_Personne_AT=".$row['Id']."
									";
									$resultObj=mysqli_query($bdd,$req);
									$nbObj=mysqli_num_rows($resultObj);
									
									if ($nbResulta>0){
										while($row2=mysqli_fetch_array($result)){
											$Objet="";
											if ($nbObj>0){
												mysqli_data_seek($resultObj,0);
												while($rowObj=mysqli_fetch_array($resultObj)){
													if($rowObj['Id_TypeObjet']==$row2['Id']){$Objet=$rowObj['Objet'];}
												}
											}
											if($row2['Libelle']=="Autres" || $row2['Libelle']=="Autre" || $row2['Libelle']=="Others" || $row2['Libelle']=="Other"|| 
											$row2['Libelle']=="°Autres" || $row2['Libelle']=="°Autre" || $row2['Libelle']=="°Others" || $row2['Libelle']=="°Other"){
												echo "<tr><td>".substr($row2['Libelle'],1)." : </td><td><input type='text' ".$readOnly." class='objets' name='objet_".$row2['Id']."' id='objet_".$row2['Id']."' value='".$Objet."'></td></tr>";
											}
											else{
												echo "<tr><td>".$row2['Libelle']." : </td><td><input type='text' class='objets' ".$readOnly."  name='objet_".$row2['Id']."' id='objet_".$row2['Id']."' value='".$Objet."'></td></tr>";
											}
										}
									}
									echo "</table>";
									?>
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #fd616b"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="4"><?php if($_SESSION["Langue"]=="FR"){echo "Activité de la victime lors de l'accident (décrire l'activité exacte que le salarié exerçait au moment de l'accident)";}
							else{echo "Activity of the victim during the accident (describe the exact activity the employee was performing at the time of the accident)";} ?></td>
						</tr>
						<tr>
							<td colspan="2">
								<textarea name="activiteVictime" id="activiteVictime" <?php echo $readOnly; ?> cols="90" rows="3" noresize="noresize"><?php echo stripslashes($row['Activite']); ?></textarea>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #fd616b"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="4"><?php if($_SESSION["Langue"]=="FR"){echo "Dans le cas d'un accident de trajet (circonstances de l'AT Trajet)";}
							else{echo "In the event of a commuting accident (circumstances of AT Trajet)";} ?></td>
						</tr>
						<tr>
							<td width="10%" class="Libelle" colspan="2"><?php if($_SESSION["Langue"]=="FR"){echo "Type de véhicule :";}else{echo "Vehicle type :";} ?></td>
							<td width="10%" class="Libelle" colspan="2"></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="2" valign="top">
								<div id='Div_TypeVehicule' style='height:160px;width:100%;overflow:auto;'>
									<?php
									echo "<table width='100%'>";
									$req="SELECT Id, Libelle FROM rh_typevehicule WHERE Suppr=0 ORDER BY Libelle";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									$selected=0;
									if ($nbResulta>0){
										while($row2=mysqli_fetch_array($result)){
											$select="";
											
											if($_POST){
												if(isset($_POST['typevehicule'])){
													if($_POST['typevehicule']==$row2['Id']){$select="checked";$selected=1;}
												}
											}
											else{
												if($row['Id_TypeVehicule']==$row2['Id']){$select="checked";$selected=1;}
											}
											echo "<tr><td><input type='radio' class='typevehicule' name='typevehicule' value='".$row2['Id']."' ".$select.">".$row2['Libelle']."</td></tr>";
										}
									}
									echo "</table>";
									?>
								</div>
							</td>
							<td colspan="2">
									<?php
									echo "<table width='100%'>";
									
									$valeur="";
									if($_POST){if(isset($_POST['conditionsClim'])){$valeur="checked";}}
									else{if($row['ConditionClim']==1){$valeur="checked";}}
									echo "<tr>
										<td width='30%'>Conditions climatiques particulières : </td>
										<td width='15%'><input type='checkbox' class='conditions' name='conditionsClim' id='conditionsClim' ".$valeur."></td>
										<td width='55%' rowspan='2'><textarea name='commentaireCondition1' id='commentaireCondition1' cols='30' rows='2' noresize='noresize'>";
										if($_POST){echo $_POST['commentaireCondition1'];}
										else{echo $row['CommentaireCirconstance'];}
									echo "</textarea></td>
									</tr>";
									$valeur="";
									if($_POST){if(isset($_POST['mauvaisEtatInfra'])){$valeur="checked";}}
									else{if($row['MauvaisEtatInfra']==1){$valeur="checked";}}
									echo "<tr>
											<td width='30%'>Mauvais état des infrastructures : </td>
											<td width='15%'><input type='checkbox' class='conditions' name='mauvaisEtatInfra' id='mauvaisEtatInfra' ".$valeur."></td>
											<td width='55%'></td>
										</tr>";
									
									$select1="";
									$select2="";
									if($_POST){
										if(isset($_POST['trajet'])){
											if($_POST['trajet']==1){$select1="checked";}
											if($_POST['trajet']==2){$select2="checked";}
										}
									}
									else{
										if($row['TrajetAller']==1){$select1="checked";}
										if($row['TrajetAller']==2){$select2="checked";}
									}
									echo "<tr>
										<td width='30%'><input type='radio' class='trajet' name='trajet' value='1' ".$select1.">Trajet Aller</td>
										<td width='15%' colspan='2'><input type='radio' class='trajet' name='trajet' value='2' ".$select2.">Trajet Retour</td>
									</tr>";
									
									$valeur="";
									if($_POST){if(isset($_POST['horairesSpec'])){$valeur="checked";}}
									else{if($row['HoraireTravail']==1){$valeur="checked";}}
									echo "<tr>
											<td width='30%'>Horaires de travail spécifiques : </td>
											<td width='15%'><input type='checkbox' class='conditions' name='horairesSpec' id='horairesSpec' ".$valeur."></td>
											<td width='55%' rowspan='2'><textarea name='commentaireCondition2' id='commentaireCondition2' cols='30' rows='2' noresize='noresize'>";
											if($_POST){echo $_POST['commentaireCondition2'];}
											else{echo $row['CommentaireCirconstance2'];}
											echo "</textarea></td>
										</tr>";
									$valeur="";
									if($_POST){if(isset($_POST['pbTechnique'])){$valeur="checked";}}
									else{if($row['ProblemeTechnique']==1){$valeur="checked";}}
									echo "<tr>
											<td width='30%'>Problème technique du véhicule accidenté : </td>
											<td width='15%'><input type='checkbox' class='conditions' name='pbTechnique' id='pbTechnique' ".$valeur."></td>
											<td width='55%'></td>
											</tr>
											";
									echo "</table>";
									?>

							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #fd616b"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="4"><?php if($_SESSION["Langue"]=="FR"){echo "Nature de l'accident (rupture de matériel, glissade, chute, effort, heurt, collision, écrasement, coupure, substance dangereuse ...";}
							else{echo "Nature of the accident (equipment breakdown, slipping, falling, exertion, impact, collision, crushing, cutting, dangerous substance ...";} ?></td>
						</tr>
						<tr>
							<td colspan="2">
								<textarea name="natureAccident" id="natureAccident" <?php echo $readOnly; ?> cols="90" rows="3" noresize="noresize"><?php echo stripslashes($row['CommentaireNature']); ?></textarea>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #fd616b"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="2"><?php if($_SESSION["Langue"]=="FR"){echo "Siège(s) des lésions et côté concerné :";}else{echo "Seat(s) of lesions and side concerned :";} ?></td>
							<td width="10%" class="Libelle" colspan="2"><?php if($_SESSION["Langue"]=="FR"){echo "Nature des lésions :";}else{echo "Nature of lesions :";} ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="2">
								<div id='Div_SiegeLesion' style='height:200px;width:50%;overflow:auto;'>
									<?php
									echo "<table width='100%'>";
									if($_SESSION["Langue"]=="FR"){$req="SELECT Id, Libelle, CoteGD FROM rh_siege_lesion_at WHERE Suppr=0 ORDER BY Libelle";}
									else{$req="SELECT Id, LibelleEN AS Libelle, CoteGD FROM rh_siege_lesion_at WHERE Suppr=0 ORDER BY LibelleEN";}
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									
									$req="SELECT Id_SiegeLesion,AutreSiege,Gauche,Droite	
									FROM rh_personne_at_siegelesion 
									WHERE Suppr=0 
									AND Id_Personne_AT=".$row['Id']."
									";
									$resultSiege=mysqli_query($bdd,$req);
									$nbSiege=mysqli_num_rows($resultSiege);
		
									if ($nbResulta>0){
										while($row2=mysqli_fetch_array($result)){
											$AutreSiege="";
											$Gauche="";
											$Droite="";
											$checked="";
											if ($nbObj>0){
												mysqli_data_seek($resultSiege,0);
												while($rowSiege=mysqli_fetch_array($resultSiege)){
													if($rowSiege['Id_SiegeLesion']==$row2['Id']){
														$AutreSiege=$rowSiege['AutreSiege'];
														$checked="checked";
														if($rowSiege['Gauche']==1){$Gauche="checked";}
														if($rowSiege['Droite']==1){$Droite="checked";}
													}
												}
											}
											if($row2['Libelle']=="Autres" || $row2['Libelle']=="Autre" || $row2['Libelle']=="Others" || $row2['Libelle']=="Other"|| 
											$row2['Libelle']=="°Autres" || $row2['Libelle']=="°Autre" || $row2['Libelle']=="°Others" || $row2['Libelle']=="°Other"){
												echo "<tr><td width='10%'>".substr($row2['Libelle'],1)."</td><td width='90%'>";
												echo"<input type='text' class='siegesAutres' ".$readOnly." name='siegeLesionAutre_".$row2['Id']."' value='".$AutreSiege."'>";
											}
											else{
												echo "<tr><td width='10%'>".$row2['Libelle']."</td><td width='90%'>";
												if($row2['CoteGD']==0){
													echo"<input type='checkbox' class='sieges' ".$disabled." name='siegeLesion_".$row2['Id']."' ".$checked." value='siegeLesion_".$row2['Id']."'>";
												}
												else{
													echo"<input type='checkbox' class='sieges' ".$disabled." name='siegeLesionG_".$row2['Id']."' ".$Gauche." value='siegeLesionG_".$row2['Id']."'>";
													if($_SESSION["Langue"]=="FR"){echo "G";}else{echo "L";}
													echo"<input type='checkbox' class='sieges' ".$disabled." name='siegeLesionD_".$row2['Id']."' ".$Droite." value='siegeLesionD_".$row2['Id']."'>";
													if($_SESSION["Langue"]=="FR"){echo "D";}else{echo "R";}
												}
											}
											echo "</td></tr>";
										}
									}
									echo "</table>";
									?>
								</div>
							</td>
							<td colspan="2">
								<div id='Div_NatureLesion' style='height:200px;width:80%;overflow:auto;'>
									<?php
									echo "<table width='100%'>";
									if($_SESSION["Langue"]=="FR"){$req="SELECT Id, Libelle FROM rh_nature_lesion WHERE Suppr=0 ORDER BY Libelle";}
									else{$req="SELECT Id, LibelleEN AS Libelle FROM rh_nature_lesion WHERE Suppr=0 ORDER BY LibelleEN";}
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									
									$req="SELECT AutreNature,Id_NatureLesion		
									FROM rh_personne_at_nature_lesion
									WHERE Suppr=0 
									AND Id_PersonneAT=".$row['Id']."
									";
									$resultNature=mysqli_query($bdd,$req);
									$nbNature=mysqli_num_rows($resultNature);
		
									if ($nbResulta>0){
										while($row2=mysqli_fetch_array($result)){
											$AutreNature="";
											$checked="";
											if ($nbNature>0){
												mysqli_data_seek($resultNature,0);
												while($rowNature=mysqli_fetch_array($resultNature)){
													if($rowNature['Id_NatureLesion']==$row2['Id']){
														$AutreNature=$rowNature['AutreNature'];
														$checked="checked";
													}
												}
											}
											
											
											if($row2['Libelle']=="Autres" || $row2['Libelle']=="Autre" || $row2['Libelle']=="Others" || $row2['Libelle']=="Other"|| 
											$row2['Libelle']=="°Autres" || $row2['Libelle']=="°Autre" || $row2['Libelle']=="°Others" || $row2['Libelle']=="°Other"){
												echo "<tr><td width='15%'>".substr($row2['Libelle'],1)."</td><td width='90%'>";
												echo"<input type='text' class='naturesAutres' ".$readOnly." name='natureLesionAutre_".$row2['Id']."' value='".$AutreNature."'>";
											}
											else{
												echo "<tr><td width='15%'>".$row2['Libelle']."</td><td width='90%'>";
												echo"<input type='checkbox' class='natures' ".$disabled." name='natureLesion_".$row2['Id']."' ".$checked." value='natureLesion_".$row2['Id']."'>";
											}
											echo "</td></tr>";
										}
									}
									echo "</table>";
									?>
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #fd616b"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="2"><?php if($_SESSION["Langue"]=="FR"){echo "Conséquences de l'AT :";}else{echo "Consequences of the accident :";} ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Arrêt de travail :";}else{echo "Work stopping :";} ?>
							<input type="radio" name="arretTravail" id="arretTravail" <?php echo $disabled; ?> value="1" <?php if($row['ArretDeTravail']==1){echo "checked";} ?>>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Sans arrêt :";}else{echo "Nonstop :";} ?>
							<input type="radio" name="arretTravail" id="arretTravail" <?php echo $disabled; ?> value="0" <?php if($row['ArretDeTravail']==0){echo "checked";} ?>>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Evacuation vers :";}else{echo "Evacuation to :";} ?></td>
							<td width="10%">
								<input type="text" name="evacuationVers" <?php echo $readOnly; ?> id="evacuationVers" size="35" value="<?php echo stripslashes($row['EvacuationVers']); ?>">	
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Autre victime :";}else{echo "Other victim :";} ?></td>
							<td width="10%">
								<input type="text" name="autreVictime" <?php echo $readOnly; ?> id="autreVictime" size="35" value="<?php echo stripslashes($row['AutreVictime']); ?>">	
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Tiers responsable :";}else{echo "Third party :";} ?></td>
							<td width="10%">
								<input type="text" name="tiersResponsable" <?php echo $readOnly; ?> id="tiersResponsable" size="35" value="<?php echo stripslashes($row['TiersResponsable']); ?>">	
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Témoin :";}else{echo "Witness :";} ?></td>
							<td width="10%">
								<input type="text" name="temoin" id="temoin" <?php echo $readOnly; ?> size="35" value="<?php echo stripslashes($row['Temoin']); ?>">	
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Coordonnées :";}else{echo "Contact information :";} ?></td>
							<td width="10%">
								<input type="text" name="coordonnees" id="coordonnees" <?php echo $readOnly; ?> size="35" value="<?php echo stripslashes($row['CoordonneesTemoins']); ?>">	
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "1ère personne avertie :";}else{echo "1st informed person :";} ?></td>
							<td width="10%">
								<input type="text" name="personne1" id="personne1" <?php echo $readOnly; ?> size="35" value="<?php echo stripslashes($row['1erePersonneAvertie']); ?>">	
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de connaissance de l'AT :";}else{echo "Date of knowledge of the accident :";} ?> </td>
							<td width="10%"><input type="date" style="text-align:center;" <?php echo $readOnly; ?> id="dateConnaissanceAT" name="dateConnaissanceAT" size="10" value="<?php echo AfficheDateFR($row['DateConnaissanceAT']); ?>"></td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de connaissance de l'AT : ";}else{echo "Time of knowledge of the accident : ";} ?> </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small" style="text-align:center;" <?php echo $readOnly; ?> name="heureConnaisanceAT" id="heureConnaisanceAT" size="10" type="time" value="<?php echo $row['HeureConnaissanceAT']; ?>">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #fd616b"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="4"><?php if($_SESSION["Langue"]=="FR"){echo "Doutes, réserves sur les circonstances professionnels de l'accident";}
							else{echo "Doubts, reservations about the professional circumstances of the accident";} ?></td>
						</tr>
						<tr>
							<td colspan="2">
								<textarea name="doutes" id="doutes" cols="90" rows="3" <?php echo $readOnly; ?> noresize="noresize"><?php echo stripslashes($row['DoutesCirconstances']); ?></textarea>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #fd616b"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="4"><?php if($_SESSION["Langue"]=="FR"){echo "Actions de sécurisation immédiates (Ex: isolement outil défectueux / balisage / arrêt de l'intervention / sensibilisation du reste de l'équipe…)";}
							else{echo "Immediate security actions (Ex: defective tool isolation / marking / stopping the intervention / awareness of the rest of the team ...)";} ?></td>
						</tr>
						<tr>
							<td colspan="2">
								<textarea name="autresInfos" id="autresInfos" <?php echo $readOnly; ?> cols="90" rows="3" noresize="noresize"><?php echo stripslashes($row['AutresInformations']); ?></textarea>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Passage à l'infirmerie";}else{echo "Passage to the infirmary";}?> : </td>
							<?php
							if($Menu==4)
							{
							?>
							<td><input name="fichier" type="file" onChange="CheckFichier();"></td>
							<?php
							}
							?>
						</tr>
						<tr>
							<?php
							if($row['FichierPassageInfirmerie']<>"")
							{
							?>
							<td>
								<a class="Info" href="<?php echo $chemin."/".$DirFichier.$row['FichierPassageInfirmerie']; ?>" target="_blank"><?php if($_SESSION["Langue"]=="FR"){echo "Ouvrir";}else{echo "Open";}?></a>
								<input type="hidden" name="fichieractuel" value="<?php echo $row['Fichier'];?>">
							</td>
							<?php
							if($Menu==4)
							{
							?>
							<td class="PoliceModif"><input type="checkbox" name="SupprFichier" onClick="CheckFichier();"><?php if($_SESSION["Langue"]=="FR"){echo "Supprimer le fichier";}else{echo "Delete file";}?></td>
							<?php
							}
							}
							?>
						</tr>
						<?php
						if($Menu==4)
						{
						?>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="6" align="center">
								<div id="Ajouter">
								</div>
								<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="Enregistrer()">
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
	echo "<script>RechargerPrestation('".$row['Id_Prestation']."_".$row['Id_Pole']."');</script>";
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>
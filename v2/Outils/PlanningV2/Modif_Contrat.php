<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="Contrat.js?t=<?php echo time(); ?>"></script>
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
	<script type="text/javascript">
		$(document).ready(function () {
			$('.heure').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: false,
				showMeridian: false,
				defaultTime: false
			});
		});
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
Ecrire_Code_JS_Init_Date();

$DateJour=date("Y-m-d");
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
if($Menu==4 && DroitsFormationPlateforme($TableauIdPostesRH)){
if($_POST){
	if(isset($_POST['btnEnregistrer2'])){
		//Modif d'un contrat de travail
		$coeffFacturation=0;
		if($_POST['coeffFacturation']<>""){$coeffFacturation=$_POST['coeffFacturation'];}
		$salaireMensuel=0;
		if($_POST['salaireMensuel']<>""){$salaireMensuel=$_POST['salaireMensuel'];}
		$tauxHoraire=0;
		if($_POST['tauxHoraire']<>""){$tauxHoraire=$_POST['tauxHoraire'];}
		$Id_Prestation=0;
		$Id_Pole=0;
		if($_POST['prestationPole']<>"0"){
			$arrayPrestaPole=explode("_",$_POST['prestationPole']);
			$Id_Prestation=$arrayPrestaPole[0];
			$Id_Pole=$arrayPrestaPole[1];
		}
		$niveau="";
		$Coeff="";
		$Echelon="";
		if($_POST['niveauCoeffEchlon']<>""){
			$tab=explode(";",$_POST['niveauCoeffEchlon']);
			$niveau=$tab[0];
			$Coeff=$tab[1];
			$Echelon=$tab[2];
		}
		
		$classification=0;
		if(isset($_POST['classficiationMetier'])){if($_POST['classficiationMetier']<>"" && $_POST['classficiationMetier']<>"0"){$classification=$_POST['classficiationMetier'];}}
		
		$req="UPDATE rh_personne_contrat 
			SET 
				Id_TypeContrat=".$_POST['typeContrat'].",
				Id_AgenceInterim=".$_POST['agenceInterim'].",
				Id_Metier=".$_POST['metier'].",
				Id_ClassificationMetier=".$classification.",
				Niveau='".$niveau."',
				Echelon='".$Echelon."',
				Coeff='".$Coeff."',
				Cotation='".$_POST['cotation']."',
				Id_FicheEmploi=".$_POST['ficheEmploi'].",
				SMHReference='".$_POST['smh']."',
				SalaireReference=".$_POST['salaireRef'].",
				TypeCoeff='".$_POST['typeCoeff']."',
				CoeffFacturationAgence=".$coeffFacturation.",
				SalaireBrut=".$salaireMensuel.",
				TauxHoraire=".$tauxHoraire.",
				DateDebut='".TrsfDate_($_POST['dateDebut'])."',
				DateFin='".TrsfDate_($_POST['dateFin'])."',
				DateFinPeriodeEssai='".TrsfDate_($_POST['dateFinPeriodeEssai'])."',
				Id_TempsTravail=".$_POST['tempsTravail'].",
				Id_LieuTravail=".$_POST['lieuTravail'].",
				Id_Prestation=".$Id_Prestation.",
				Id_Pole=".$Id_Pole.",
				DateModification='".date('Y-m-d')."',
				Id_Modif=".$_SESSION['Id_Personne'].",
				DateSouplessePositive='".TrsfDate_($_POST['dateSouplessePositive'])."',
				DateSouplesseNegative='".TrsfDate_($_POST['dateSouplesseNegative'])."',
				Id_MotifSortie=".$_POST['motifSortie'].",
				CommentaireMotif='".addslashes($_POST['commentaireMotif'])."',
				Remarque='".addslashes($_POST['remarque'])."',
				Titre='".addslashes($_POST['titre'])."',
				Id_Client=".$_POST['client'].",
				Motif='".addslashes($_POST['motif'])."',
				DateSignatureSiege='".TrsfDate_($_POST['dateSignatureSiege'])."',
				DateSignatureSalarie='".TrsfDate_($_POST['dateSignatureSalarie'])."',
				DateRetourSigneAuSiege='".TrsfDate_($_POST['dateRetourSigne'])."',
				ChampsModifie='".$_POST['ChampsModifies']."'
			WHERE 
				Id=".$_POST['Id_Contrat']."";
		$resultModif=mysqli_query($bdd,$req);
		
		//mise à jour des temps partiels 
		$requeteInsert="INSERT INTO rh_personne_contrat_tempspartiel 
						(Id_Personne_Contrat, Id_Vacation, NbHeureJour, NbHeureEJ, NbHeureEN,NbHeurePause,JourSemaine,HeureDebut,HeureFin,Teletravail) 
						VALUES";
		$NbCompteVacation=0;
		
		$resultVacation=mysqli_query($bdd,"SELECT Id FROM rh_vacation WHERE Suppr=0 ");
		$NbLigneVacation=mysqli_num_rows($resultVacation);
		while($rowVacation=mysqli_fetch_array($resultVacation)){
			$NbCompteVacation+=1;
			$NbCompteJour=-1;
			$NbHeureJour = 0;
			$NbHeureEquipeJour = 0;
			$NbHeureEquipeNuit = 0;
			$NbHeurePause = 0;
			$heureDebut='00:00:00';
			$heureFin='00:00:00';
			$teletravail=0;
			while($NbCompteJour<6)
			{
				$NbCompteJour+=1;
				$NbHeureJour = 0;
				$NbHeureEquipeJour = 0;
				$NbHeureEquipeNuit = 0;
				$NbHeurePause = 0;
				$heureDebut='00:00:00';
				$heureFin='00:00:00';
				$teletravail=0;
				if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_J']<>""){$NbHeureJour = $_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_J'];}
				if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_EJ']<>""){$NbHeureEquipeJour = $_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_EJ'];}
				if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_EN']<>""){$NbHeureEquipeNuit = $_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_EN'];}
				if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_P']<>""){$NbHeurePause = $_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_P'];}
				if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_HeureDebut']<>""){$heureDebut = $_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_HeureDebut'];}
				if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_HeureFin']<>""){$heureFin = $_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_HeureFin'];}
				if($_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_Teletravail']<>""){$teletravail = $_POST[''.$rowVacation['Id'].'_'.$NbCompteJour.'_Teletravail'];}
				
				$requeteInsert.=" (".$_POST['Id_Contrat'].",".$rowVacation['Id'].",".$NbHeureJour.",".$NbHeureEquipeJour.",
								".$NbHeureEquipeNuit.",".$NbHeurePause.",".$NbCompteJour.",'".$heureDebut."','".$heureFin."',".$teletravail.")";
				if($NbCompteJour<=6 && $NbCompteVacation<=$NbLigneVacation ){$requeteInsert.=",";}
			}
		}
		$requeteInsert =  substr($requeteInsert, 0, -1).";" ;
		
		$requeteSupp="UPDATE rh_personne_contrat_tempspartiel 
					SET Suppr=1,
					Id_Suppr=".$_SESSION['Id_Personne'].",
					DateSuppr='".date('Y-m-d')."' 
					WHERE Id_Personne_Contrat=".$_POST['Id_Contrat'];
		$resultSupp=mysqli_query($bdd,$requeteSupp);
		
		$resultInsert=mysqli_query($bdd,$requeteInsert);
		
		//Créer un mouvement si le mouvement n'existe pas 
		$req="SELECT Id
			FROM rh_personne_mouvement
			WHERE (rh_personne_mouvement.DateDebut<='".TrsfDate_($_POST['dateFin'])."' OR '".TrsfDate_($_POST['dateFin'])."'='0001-01-01')
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".TrsfDate_($_POST['dateDebut'])."')
			AND rh_personne_mouvement.EtatValidation IN (0,1) 
			AND rh_personne_mouvement.Id_Personne=".$_POST['Id_Personne']."
			AND rh_personne_mouvement.Suppr=0";
		$resultatMod=mysqli_query($bdd,$req);
		$nbResultaMod=mysqli_num_rows($resultatMod);
		if($nbResultaMod==0){
			$requete="INSERT INTO rh_personne_mouvement ";
			$requete.="(Id_PrestationDepart,Id_PoleDepart,Id_Prestation,Id_Pole,Id_Personne,DateDebut,DateFin,Id_Createur,DateCreation) VALUES ";
			$requete.="(0,0,".$Id_Prestation.",".$Id_Pole.",".$_POST['lId_Personne'].",'".TrsfDate_($_POST['dateDebut'])."','".TrsfDate_($_POST['dateFin'])."',".$_SESSION['Id_Personne'].",'".date('Y-m-d')."')";
			$result=mysqli_query($bdd,$requete);
		}
		
		//Mettre une date de fin 
		if($_POST['AppliquerAuxAutresContrats']==1){
			if(TrsfDate_($_POST['dateFin'])<>'000-00-00'){
				$req="UPDATE rh_personne_contrat 
					SET DateFin='".TrsfDate_($_POST['dateFin'])."'
					WHERE 
					DateFin<='0001-01-01'
					AND Id_Personne=".$_POST['Id_Personne']."
					AND TypeDocument<>'ODM'";
				$result=mysqli_query($bdd,$req);
			}
		}
		
		echo "<script>FermerEtRecharger('".$Menu."','".$_POST['Id_Personne']."','".$_POST['Page']."')</script>";
		
	}
}
else{
	if($_GET['Mode']=="S"){
		$req="UPDATE rh_personne_contrat 
			SET 
				Suppr=1,
				DateSuppr='".date('Y-m-d')."',
				Id_Suppr=".$_SESSION['Id_Personne']."
			WHERE 
				Id=".$_GET['Id']."";
		$resultModif=mysqli_query($bdd,$req);

		echo "<script>FermerEtRecharger('".$Menu."','".$_GET['Id_Personne']."','".$_GET['Page']."')</script>";
	}
}
$req="SELECT Id,Id_ContratInitial,Id_Personne,Id_TypeContrat,Id_AgenceInterim,Id_Metier,SalaireReference,TypeCoeff,CoeffFacturationAgence,SalaireBrut,Cotation,Id_FicheEmploi,SMHReference,
	TauxHoraire,DateDebut,DateFin,DateFinPeriodeEssai,Id_TempsTravail,Id_Prestation,Id_Pole,TypeDocument,DateCreation,Id_Createur,Id_ClassificationMetier,Niveau,Coeff,Echelon,
	(SELECT EstUnTempsPlein FROM rh_tempstravail WHERE rh_tempstravail.Id=Id_TempsTravail) AS TempsPartiel,Titre,Id_MotifSortie,CommentaireMotif,
	DateSouplessePositive,DateSouplesseNegative,Remarque,Id_LieuTravail,Id_Client,Motif,DateSignatureSiege,DateSignatureSalarie,DateRetourSigneAuSiege,
	(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation) AS Id_Plateforme,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne) AS Personne,ChampsModifie 
	FROM rh_personne_contrat 
	WHERE Id=".$_GET['Id']."
	";
$result=mysqli_query($bdd,$req);
$rowContrat=mysqli_fetch_array($result);

$Id_Contrat=$_GET['Id'];

$etoile="<img src='../../Images/etoile.png' width='8' height='8' border='0'>";
?>

<form id="formulaire" class="test" action="Modif_Contrat.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="Id_Personne" id="Id_Personne" value="<?php if($_GET['Page']=="Liste_ContratHistorique"){echo $_GET['Id_Personne'];}else{echo 0;} ?>" />
	<input type="hidden" name="Page" id="Page" value="<?php echo $_GET['Page']; ?>" />
	<input type="hidden" name="Id_Contrat" id="Id_Contrat" value="<?php echo $Id_Contrat; ?>" />
	<input type="hidden" name="Old_NiveauCoeffEchelon" id="Old_NiveauCoeffEchelon" value="<?php echo $rowContrat['Niveau'].";".$rowContrat['Coeff'].";".$rowContrat['Echelon']; ?>" />
	<input type="hidden" name="lId_Personne" id="lId_Personne" value="<?php echo $rowContrat['Id_Personne']; ?>" />
	<input type="hidden" name="ChampsModifies" id="ChampsModifies" value="<?php echo $rowContrat['ChampsModifie']; ?>" />
	<input type="hidden" name="AppliquerAuxAutresContrats" id="AppliquerAuxAutresContrats" value="0" />
	<input type="hidden" name="Mode" id="Mode" value="M" />
	<tr>
		<td colspan="5">
			<table style="width:100%; border-spacing:0;">
				<tr>
					<td class="TitrePage" style="background-color:#a988b2;">
					<?php 
						if($rowContrat['TypeDocument']=="Nouveau"){
							if($_SESSION["Langue"]=="FR"){echo "Contrat n° ".$rowContrat['Id'];}else{echo "Contract n° ".$rowContrat['Id'];}
						}
						elseif($rowContrat['TypeDocument']=="Avenant"){
							if($_SESSION["Langue"]=="FR"){echo "Avenant n° ".$rowContrat['Id'];}else{echo "Amendment n° ".$rowContrat['Id'];}
						}
					?>
					</td>
					<td width="4"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="90%" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personne : ";}else{echo "People : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<?php echo $rowContrat['Personne']; ?>
							</td>
							<td width="10%" class="Libelle" id="LibelleMetier"><?php if($_SESSION["Langue"]=="FR"){echo "Métier : ";}else{echo "Job : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<select name="metier" id="metier" style="width:200px" onchange="selectionClassif2()">
								<option value="0"></option>
								<?php
								$rq="SELECT Id, Libelle
									FROM new_competences_metier
									WHERE Suppr=0
									ORDER BY Libelle ASC";
								$result=mysqli_query($bdd,$rq);
								$i=0;
								while($row=mysqli_fetch_array($result))
								{
									$selected="";
									if($rowContrat['Id_Metier']==$row['Id']){$selected="selected";}
									echo "<option value='".$row['Id']."' ".$selected.">".str_replace("'"," ",$row['Libelle'])."</option>\n";
									echo "<script>ListeMetier[".$i."]=new Array('".$row['Id']."','".$row['Id_Classification']."');</script>";
									$i++;
								}
								?>
								</select>
								<input type="hidden" name="id_Metier" id="id_Metier" value="<?php echo $rowContrat['Id_Metier']; ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleTypeContrat"><?php if($_SESSION["Langue"]=="FR"){echo "Type de contrat : ";}else{echo "Type of Contract : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<select name="typeContrat" id="typeContrat" style="width:150px" onchange="Afficher_AgenceV2('id_typeContrat','typeContrat','LibelleTypeContrat');">
								<option value="0"></option>
								<?php
								if($_SESSION["Langue"]=="FR"){
									$rq="SELECT Id, Libelle,EstInterim
										FROM rh_typecontrat
										WHERE Suppr=0
										ORDER BY Libelle ASC";
								}
								else{
									$rq="SELECT Id, LibelleEN AS Libelle,EstInterim
										FROM rh_typecontrat
										WHERE Suppr=0
										ORDER BY Libelle ASC";
								}
								$result=mysqli_query($bdd,$rq);
								$i=0;
								$estInterim=0;
								while($row=mysqli_fetch_array($result))
								{
									$selected="";
									if($rowContrat['Id_TypeContrat']==$row['Id']){$selected="selected";$estInterim=$row['EstInterim'];}
									echo "<option value='".$row['Id']."' ".$selected.">".str_replace("'"," ",$row['Libelle'])."</option>\n";
									echo "<script>ListeTypeContrat[".$i."]=new Array('".$row['Id']."','".$row['EstInterim']."');</script>";
									$i++;
								}
								?>
								</select>
								<input type="hidden" name="id_typeContrat" id="id_typeContrat" value="<?php echo $rowContrat['Id_TypeContrat']; ?>" />
							</td>
							<td width="10%" class="Libelle agence" style="<?php if($estInterim==0){echo "display:none;";} ?>" id="LibelleAgenceInterim"><?php if($_SESSION["Langue"]=="FR"){echo "Agence d'intérim : ";}else{echo "Acting Agency : ";} ?><?php echo $etoile;?></td>
							<td width="10%" class="agence" style="<?php if($estInterim==0){echo "display:none;";} ?>">
								<select name="agenceInterim" id="agenceInterim" style="width:100px" onchange="Afficher_CoeffFacturationV2('id_agenceInterim','agenceInterim','LibelleAgenceInterim');">
								<option value="0"></option>
								<?php
								$rq="SELECT Id, Libelle, CoeffGestion, CoeffDelegation
									FROM rh_agenceinterim
									WHERE Suppr=0
									ORDER BY Libelle ASC";
								$result=mysqli_query($bdd,$rq);
								$i=0;
								while($row=mysqli_fetch_array($result))
								{
									$selected="";
									if($rowContrat['Id_AgenceInterim']==$row['Id']){$selected="selected";}
									echo "<option value='".$row['Id']."' ".$selected.">".str_replace("'"," ",$row['Libelle'])."</option>\n";
									echo "<script>ListeAgenceInterim[".$i."]=new Array('".$row['Id']."','".$row['CoeffGestion']."','".$row['CoeffDelegation']."');</script>";
									$i++;
								}
								?>
								</select>
								<input type="hidden" name="id_agenceInterim" id="id_agenceInterim" value="<?php echo $rowContrat['Id_AgenceInterim']; ?>" />
							</td>
						</tr>
						<tr class="agence" style="<?php if($estInterim==0){echo "display:none;";} ?>"><td height="4"></td></tr>
						<tr class="agence" style="<?php if($estInterim==0){echo "display:none;";} ?>">
							<td width="10%" class="Libelle" id="LibelleTypeCoeff"><?php if($_SESSION["Langue"]=="FR"){echo "Type de coeff : ";}else{echo "Coeff type : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<select name="typeCoeff" id="typeCoeff" style="width:100px" onchange="Afficher_CoeffFacturationV2('id_typeCoeff','typeCoeff','LibelleTypeCoeff');">
									<option value="Gestion" <?php if($rowContrat['TypeCoeff']=="Gestion"){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Gestion";}else{echo "Management";} ?></option>
									<option value="Delegation" <?php if($rowContrat['TypeCoeff']=="Delegation"){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Délégation";}else{echo "Delegation";} ?></option>
								</select>
								<input type="hidden" name="id_typeCoeff" id="id_typeCoeff" value="<?php echo $rowContrat['TypeCoeff']; ?>" />
							</td>
							<td width="10%" class="Libelle" valign="top" id="LibelleCoeffFacturation"><?php if($_SESSION["Langue"]=="FR"){echo "Coeff : ";}else{echo "Coeff : ";} ?><?php echo $etoile;?></td>
							<td width="10%" valign="top">
								<input onKeyUp="nombre(this)" onChange="ModifierCouleurChamps('id_coeffFacturation','coeffFacturation','LibelleCoeffFacturation')" type="text" name="coeffFacturation" id="coeffFacturation" size="5" value="<?php if($rowContrat['CoeffFacturationAgence']>0){echo $rowContrat['CoeffFacturationAgence'];} ?>">
								<input type="hidden" name="id_coeffFacturation" id="id_coeffFacturation" value="<?php echo $rowContrat['CoeffFacturationAgence']; ?>" />
							</td>
						</tr>
						<?php
							$tabNiveau=array("I","I","I","II","II","II","III","III","III","IV","IV","IV","V","V","V","V","","","","","","","","II","II","II","II","II","II","II","IIIA","IIIB","IIIC");
							$tabCoeff=array("140","145","155","170","180","190","215","225","240","255","270","285","305","335","365","395","60","68","76","80","84","86","92","100","108","114","120","125","130","135","135","180","240");
							$tabEchelon=array("1","2","3","1","2","3","1","2","3","1","2","3","1","2","3","3","","","","","","","","","","","","","","","","","");
						?>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" valign="top" id="LibelleclassficiationMetier"><?php if($_SESSION["Langue"]=="FR"){echo "Classification métier :";}else{echo "Business Classification :";} ?></td>
							<td width="10%" valign="top">
								<select name="classficiationMetier" id="classficiationMetier" onchange="RechargerListeTAG3()">
								<?php
								if($_SESSION['Langue']=="FR"){
									$req="SELECT Id, 
									Libelle
									FROM rh_classificationmetier
									WHERE Suppr=0
									ORDER BY Libelle ";
								}
								else{
									$req="SELECT Id, 
									LibelleEN AS Libelle
									FROM rh_classificationmetier
									WHERE Suppr=0
									ORDER BY LibelleEN ";
								}
								$resultClassif=mysqli_query($bdd,$req);
								$nbenreg=mysqli_num_rows($resultClassif);
								if($nbenreg>0)
								{
									while($rowClassif=mysqli_fetch_array($resultClassif))
									{
										$selected="";
										if($rowClassif['Id']==$rowContrat['Id_ClassificationMetier']){$selected="selected";}
										echo "<option value='".$rowClassif['Id']."' ".$selected.">".stripslashes($rowClassif['Libelle'])."</option>";
									}
								}
								?>
								</select>
								<input type="hidden" name="id_classficiationMetier" id="id_classficiationMetier" value="<?php echo $rowContrat['Id_ClassificationMetier']; ?>" />
							</td>
							<td width="10%" class="Libelle" valign="top" id="LibelleniveauCoeffEchlon"><?php if($_SESSION["Langue"]=="FR"){echo "Niveau - Coeff - Echelon : ";}else{echo "Level - Coeff - Echelon : ";} ?><?php echo $etoile;?></td>
							<td width="10%" valign="top">
								<div id="Div_niveauCoeffEchlon">
								
								<select name="niveauCoeffEchlon" id="niveauCoeffEchlon" onchange="RechercherSalaire2()">
								<?php
								$i=0;
								foreach($tabNiveau as $niveau){
									$selected="";
									if($niveau.";".$tabCoeff[$i].";".$tabEchelon[$i]==$rowContrat['Niveau'].";".$rowContrat['Coeff'].";".$rowContrat['Echelon']){$selected="selected";}
								?>
									<option <?php echo $selected; ?> value="<?php echo $niveau.";".$tabCoeff[$i].";".$tabEchelon[$i];?>"><?php echo $niveau." - ".$tabCoeff[$i]." - ".$tabEchelon[$i];?></option>
								<?php
									$i++;
								}
								
								$req="SELECT Id_ClassificationMetier, Niveau, Echelon, Coeff,Salaire FROM rh_tag WHERE Suppr=0 ";
								$resultTAG=mysqli_query($bdd,$req);
								$nbTAG=mysqli_num_rows($resultTAG);
								$i=0;
								if($nbTAG>0){
									while($rowTAG=mysqli_fetch_array($resultTAG)){
										echo "<script>Liste_TAG[".$i."]=Array('".$rowTAG['Niveau']."','".$rowTAG['Coeff']."','".$rowTAG['Echelon']."','".$rowTAG['Id_ClassificationMetier']."','".$rowTAG['Salaire']."')</script>";
										$i++;
									}
								}
								?>
								</select>
								</div>
								<input type="hidden" name="id_niveauCoeffEchlon" id="id_niveauCoeffEchlon" value="<?php echo $rowContrat['Niveau'].";".$rowContrat['Coeff'].";".$rowContrat['Echelon']; ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<?php 
						$tabCotation=array("","A1","A2","B3","B4","C5","C6","D7","D8","E9","E10","F11","F12","G13","G14","H15","H16","I17","I18");
						?>
						<tr>
							<td width="10%" class="Libelle" valign="top"><?php if($_SESSION["Langue"]=="FR"){echo "Fiche emploi :";}else{echo "Job sheet :";} ?><?php echo $etoile;?></td>
							<td width="10%" valign="top">
								<select name="ficheEmploi" id="ficheEmploi" onchange="RechargerCotation()">
									<option value="0"></option>
								<?php
								$req="SELECT Id, 
								Libelle,Cotation
								FROM rh_ficheemploi
								WHERE Suppr=0
								ORDER BY Libelle ";
								$resultFE=mysqli_query($bdd,$req);
								$nbenreg=mysqli_num_rows($resultFE);
								$i=0;
								if($nbenreg>0)
								{
									while($rowFE=mysqli_fetch_array($resultFE))
									{
										$selected="";
										if($rowFE['Id']==$rowContrat['Id_FicheEmploi']){$selected="selected";}
									
										echo "<option value='".$rowFE['Id']."' ".$selected.">".stripslashes($rowFE['Libelle'])."</option>";
										
										echo "<script>Liste_FicheEmploi[".$i."]=Array('".$rowFE['Id']."','".$rowFE['Cotation']."')</script>";
										$i++;
									}
								}
								
								$req="SELECT Cotation,Salaire FROM rh_smh WHERE Suppr=0 ";
								$resultTAG=mysqli_query($bdd,$req);
								$nbTAG=mysqli_num_rows($resultTAG);
								$i=0;
								if($nbTAG>0){
									while($rowTAG=mysqli_fetch_array($resultTAG)){
										echo "<script>Liste_SMH[".$i."]=Array('".$rowTAG['Cotation']."','".$rowTAG['Salaire']."')</script>";
										$i++;
									}
								}
								
								?>
								</select>
							</td>
							<td width="10%" class="Libelle" valign="top"><?php if($_SESSION["Langue"]=="FR"){echo "Cotation : ";}else{echo "Cote : ";} ?></td>
							<td width="10%" valign="top">
								<select name="cotation" id="cotation" onchange="RechargerSMH()">
								<?php
								$i=0;
								foreach($tabCotation as $cotation){
									$selected="";
									if($cotation==$rowContrat['Cotation']){$selected="selected";}
								?>
									<option value="<?php echo $cotation;?>" <?php echo $selected;?>><?php echo $cotation;?></option>
								<?php
									$i++;
								}
								?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" valign="top" id="LibelleSMHReference"><?php if($_SESSION["Langue"]=="FR"){echo "Salaire de référence conventionnel 2024: ";}else{echo "Conventional reference salary 2024: ";} ?><?php echo $etoile;?></td>
							<td width="10%" valign="top">
								<input onKeyUp="nombre(this)" onchange="ModifierCouleurChamps('id_SMHReference','SMHReference','LibelleSMHReference');" type="text" name="smh" id="smh" size="10" value="<?php if($rowContrat['SMHReference']>0){echo $rowContrat['SMHReference'];} ?>">
								<input type="hidden" name="id_SMHReference" id="id_SMHReference" value="<?php echo $rowContrat['SMHReference']; ?>" />
							</td>
							<td width="10%" class="Libelle" valign="top" id="LibelleSalaireRef"><?php if($_SESSION["Langue"]=="FR"){echo "Salaire de référence conventionnel < 2024 : ";}else{echo "Conventional reference salary < 2024 : ";} ?><?php echo $etoile;?></td>
							<td width="10%" valign="top">
								<input onKeyUp="nombre(this)" onChange="ModifierCouleurChamps('id_salaireRef','salaireRef','LibelleSalaireRef')" type="text" name="salaireRef" id="salaireRef" size="5" value="<?php if($rowContrat['SalaireReference']>0){echo $rowContrat['SalaireReference'];} ?>">
								<input type="hidden" name="id_salaireRef" id="id_salaireRef" value="<?php echo $rowContrat['SalaireReference']; ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr class="salarie" style="<?php if($estInterim==1){echo "display:none;";} ?>">
							<td width="10%" class="Libelle" valign="top"  id="LibelleSalaireMensuel"><?php if($_SESSION["Langue"]=="FR"){echo "Salaire mensuel : ";}else{echo "Monthly pay : ";} ?></td>
							<td width="10%" valign="top">
								<input onKeyUp="nombre(this)" onChange="ModifierCouleurChamps('id_salaireMensuel','salaireMensuel','LibelleSalaireMensuel')" type="text" type="text" name="salaireMensuel" id="salaireMensuel" size="5" value="<?php if($rowContrat['SalaireBrut']>0){echo $rowContrat['SalaireBrut'];} ?>">
								<input type="hidden" name="id_salaireMensuel" id="id_salaireMensuel" value="<?php echo $rowContrat['SalaireBrut']; ?>" />
							</td>
						</tr>
						<tr class="agence" style="<?php if($estInterim==0){echo "display:none;";} ?>">
							<td width="10%" class="Libelle"  valign="top" id="LibelleTauxHoraire"><?php if($_SESSION["Langue"]=="FR"){echo "Taux horaire : ";}else{echo "Hourly rate : ";} ?><?php echo $etoile;?></td>
							<td width="10%" valign="top">
								<input onKeyUp="nombre(this)" onChange="ModifierCouleurChamps('id_tauxHoraire','tauxHoraire','LibelleTauxHoraire')" type="text" name="tauxHoraire" id="tauxHoraire" size="5" value="<?php if($rowContrat['TauxHoraire']>0){echo $rowContrat['TauxHoraire'];} ?>">
								<input type="hidden" name="id_tauxHoraire" id="id_tauxHoraire" value="<?php echo $rowContrat['TauxHoraire']; ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr style="display:none;" id="ErreurSMHRef">
							<td colspan="4">
							<?php
								if($_SESSION['Langue']=="FR"){
									echo "<img width='25px' src='../../Images/attention.png'/> Le salaire < salaire de référence conventionnel 2024";
								}
								else{
									echo "<img width='25px' src='../../Images/attention.png'/> Salary <conventional reference salary 2024";
								}
							?>
							</td>
						</tr>
						<tr style="display:none;" id="ErreurSalaireRef">
							<td colspan="4">
							<?php
								if($_SESSION['Langue']=="FR"){
									echo "<img width='25px' src='../../Images/attention.png'/> Le salaire < salaire de référence conventionnel";
								}
								else{
									echo "<img width='25px' src='../../Images/attention.png'/> Salary <conventional reference salary";
								}
							?>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #cdbad2"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleDateDebut"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début : ";}else{echo "Start date : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<input type="date" style="text-align:center;" onChange="ModifierCouleurChamps('id_dateDebut','dateDebut','LibelleDateDebut')" id="dateDebut" name="dateDebut" size="8" value="<?php echo AfficheDateFR($rowContrat['DateDebut']); ?>">
								<input type="hidden" name="id_dateDebut" id="id_dateDebut" value="<?php echo AfficheDateFR($rowContrat['DateDebut']); ?>" />
							</td>
							<td width="10%" class="Libelle" id="LibelleDateFin"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin :";}else{echo "End date :";} ?> </td>
							<td width="10%">
								<input type="date" style="text-align:center;"  onChange="ModifierCouleurChamps('id_dateFin','dateFin','LibelleDateFin')" id="dateFin" name="dateFin" size="8" value="<?php echo AfficheDateFR($rowContrat['DateFin']); ?>">
								<input type="hidden" name="id_dateFin" id="id_dateFin" value="<?php echo AfficheDateFR($rowContrat['DateFin']); ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleDateFinPeriodeEssai"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin période d'essai :";}else{echo "End date of the trial period :";} ?> </td>
							<td width="10%">
								<input type="date" style="text-align:center;" onChange="ModifierCouleurChamps('id_dateFinPeriodeEssai','dateFinPeriodeEssai','LibelleDateFinPeriodeEssai')" id="dateFinPeriodeEssai" name="dateFinPeriodeEssai" size="5" value="<?php echo AfficheDateFR($rowContrat['DateFinPeriodeEssai']); ?>">
								<input type="hidden" name="id_dateFinPeriodeEssai" id="id_dateFinPeriodeEssai" value="<?php echo AfficheDateFR($rowContrat['DateFinPeriodeEssai']); ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleDateSouplesseNegative"><?php if($_SESSION["Langue"]=="FR"){echo "Date souplesse négative :";}else{echo "Negative flexibility date :";} ?> </td>
							<td width="10%">
								<input type="date" style="text-align:center;" onChange="ModifierCouleurChamps('id_dateSouplesseNegative','dateSouplesseNegative','LibelleDateSouplesseNegative')" id="dateSouplesseNegative" name="dateSouplesseNegative" size="5" value="<?php echo AfficheDateFR($rowContrat['DateSouplesseNegative']); ?>">
								<input type="hidden" name="id_dateSouplesseNegative" id="id_dateSouplesseNegative" value="<?php echo AfficheDateFR($rowContrat['DateSouplesseNegative']); ?>" />
							</td>
							<td width="10%" class="Libelle" id="LibelleDateSouplessePositive"><?php if($_SESSION["Langue"]=="FR"){echo "Date souplesse positive :";}else{echo "Positive flexibility date :";} ?> </td>
							<td width="10%">
								<input type="date" style="text-align:center;" onChange="ModifierCouleurChamps('id_dateSouplessePositive','dateSouplessePositive','LibelleDateSouplessePositive')" id="dateSouplessePositive" name="dateSouplessePositive" size="5" value="<?php echo AfficheDateFR($rowContrat['DateSouplessePositive']); ?>">
								<input type="hidden" name="id_dateSouplessePositive" id="id_dateSouplessePositive" value="<?php echo AfficheDateFR($rowContrat['DateSouplessePositive']); ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleMotifSortie"><?php if($_SESSION["Langue"]=="FR"){echo "Motif de sortie : ";}else{echo "Exit reason : ";} ?></td>
							<td width="10%">
								<select name="motifSortie" id="motifSortie" style="width:150px" onchange="ModifierCouleurChamps('id_motifSortie','motifSortie','LibelleMotifSortie');">
								<option value="0"></option>
								<?php
								if($_SESSION["Langue"]=="FR"){
									$rq="SELECT Id, Libelle
										FROM rh_motifsortie
										WHERE Suppr=0
										ORDER BY Libelle ASC";
								}
								else{
									$rq="SELECT Id, LibelleEN AS Libelle
										FROM rh_motifsortie
										WHERE Suppr=0
										ORDER BY Libelle ASC";
								}
								$result=mysqli_query($bdd,$rq);
								while($row=mysqli_fetch_array($result))
								{
									$selected="";
									if($rowContrat['Id_MotifSortie']==$row['Id']){$selected="selected";}
									echo "<option value='".$row['Id']."' ".$selected.">".str_replace("'"," ",$row['Libelle'])."</option>\n";
								}
								?>
								</select>
								<input type="hidden" name="id_motifSortie" id="id_motifSortie" value="<?php echo $rowContrat['Id_MotifSortie']; ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="4" id="LibelleCommentaireMotif"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire motif de sortie : ";}else{echo "Comment : ";} ?></td>
						</tr>
						<tr>
							<td colspan="4">
								<textarea name="commentaireMotif" id="commentaireMotif" onchange="ModifierCouleurChamps('id_commentaireMotif','commentaireMotif','LibelleCommentaireMotif');" cols="90" rows="3" noresize="noresize"><?php echo str_replace("\\\\","",stripslashes($rowContrat['CommentaireMotif'])); ?></textarea>
								<input type="hidden" name="id_commentaireMotif" id="id_commentaireMotif" value="<?php echo str_replace("\\\\","",stripslashes($rowContrat['CommentaireMotif'])); ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #cdbad2"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleTempsTravail"><?php if($_SESSION["Langue"]=="FR"){echo "Temps de travail : ";}else{echo "Work time : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<select name="tempsTravail" id="tempsTravail" style="width:150px" onChange="ModifierCouleurChamps('id_tempsTravail','tempsTravail','LibelleTempsTravail')">
								<option value="0"></option>
								<?php
								$rq="SELECT Id, Libelle
									FROM rh_tempstravail
									WHERE Suppr=0
									ORDER BY Libelle ASC";

								$result=mysqli_query($bdd,$rq);
								while($row=mysqli_fetch_array($result))
								{
									$selected="";
									if($rowContrat['Id_TempsTravail']==$row['Id']){$selected="selected";}
									echo "<option value='".$row['Id']."' ".$selected.">".str_replace("'"," ",$row['Libelle'])."</option>\n";
								}
								?>
								</select>
								<input type="hidden" name="id_tempsTravail" id="id_tempsTravail" value="<?php echo $rowContrat['Id_TempsTravail']; ?>" />
							</td>
							<td width="10%" class="Libelle" id="LibelleLieuTravail"><?php if($_SESSION["Langue"]=="FR"){echo "Lieu de travail : ";}else{echo "Workplace : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<select name="lieuTravail" id="lieuTravail" style="width:250px" onChange="ModifierCouleurChamps('id_lieuTravail','lieuTravail','LibelleLieuTravail')">
								<option value="0"></option>
								<?php
								$rq="SELECT Id, Libelle
									FROM rh_lieutravail
									WHERE Suppr=0
									ORDER BY Libelle ASC";

								$result=mysqli_query($bdd,$rq);
								while($row=mysqli_fetch_array($result))
								{
									$selected="";
									if($rowContrat['Id_LieuTravail']==$row['Id']){$selected="selected";}
									echo "<option value='".$row['Id']."' ".$selected.">".str_replace("'"," ",$row['Libelle'])."</option>\n";
								}
								?>
								</select>
								<input type="hidden" name="id_lieuTravail" id="id_lieuTravail" value="<?php echo $rowContrat['Id_LieuTravail']; ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="10" class="Libelle">
								<?php if($_SESSION["Langue"]=="FR"){echo "A compléter uniquement si temps partiel ou télétravail";}else{echo "To be completed only if part-time or telecommuting";} ?>
							</td>
						</tr>
						<tr>
							<td colspan="10">
							<table width="90%" align="center">
								<tr>
									<td width="10%" bgcolor='#d597b3' align="center" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Vacation";}else{echo "Session";} ?></td>
									<td width="10%" bgcolor='#d597b3' align="center" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Jour semaine";}else{echo "Day week";} ?></td>
									<td width="6%" bgcolor='#d597b3' align="center" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "J";}else{echo "D";} ?></td>
									<td width="6%" bgcolor='#d597b3' align="center" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "EJ";}else{echo "DT";} ?></td>
									<td width="6%" bgcolor='#d597b3' align="center" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "EN";}else{echo "NT";} ?></td>
									<td width="6%" bgcolor='#d597b3' align="center" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Pause";}else{echo "Break";} ?></td>
									<td width="10%" bgcolor='#d597b3' align="center" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure début";}else{echo "Start time";} ?></td>
									<td width="10%" bgcolor='#d597b3' align="center" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure fin";}else{echo "End time";} ?></td>
									<td width="10%" bgcolor='#d597b3' align="center" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Télétravail";}else{echo "Telecommuting";} ?></td>
								</tr>
							</table>
							<div id='div_TP' style='height:200px;width:100%;overflow:auto;' >
								<table width="90%" align="center">

									<?php
									$req="SELECT Id,Nom FROM rh_vacation WHERE Suppr=0 ORDER BY Nom";

									$resultVac=mysqli_query($bdd,$req);
									$nbenreg=mysqli_num_rows($resultVac);
									if($nbenreg>0)
									{
										$Couleur="#EEEEEE";
										while($rowVac=mysqli_fetch_array($resultVac))
										{
											?>
												<tr bgcolor="<?php echo $Couleur;?>">
													<td width="11%" rowspan=8 style="text-align:center;"><?php echo $rowVac['Nom'];?></td>
												</tr>
												<?php
												$tabJour=array(1,2,3,4,5,6,0);
												foreach($tabJour as $i){
												?>
													<tr>
														<td class="EnTeteTableauCompetences" width="10%">
														<?php
															switch ($i) {
																case 1:
																	if($_SESSION["Langue"]=="FR"){echo "Lundi";}else{echo "Monday";}
																	break;
																case 2:
																	if($_SESSION["Langue"]=="FR"){echo "Mardi";}else{echo "Tuesday";}
																	break;
																case 3:
																	if($_SESSION["Langue"]=="FR"){echo "Mercredi";}else{echo "Wednesday";}
																	break;
																case 4:
																	if($_SESSION["Langue"]=="FR"){echo "Jeudi";}else{echo "Thursday";}
																	break;
																case 5:
																	if($_SESSION["Langue"]=="FR"){echo "Vendredi";}else{echo "Friday";}
																	break;
																case 6:
																	if($_SESSION["Langue"]=="FR"){echo "Samedi";}else{echo "Saturday";}
																	break;
																case 0:
																	if($_SESSION["Langue"]=="FR"){echo "Dimanche";}else{echo "Sunday";}
																	break;
															}
														?>
														</td>
														<?php
														$requete = "SELECT Id,NbHeureJour, NbHeureEJ, NbHeureEN, NbHeurePause,HeureDebut,HeureFin,Teletravail 
																FROM rh_personne_contrat_tempspartiel 
																WHERE Id_Personne_Contrat=".$Id_Contrat." 
																AND Suppr=0
																AND JourSemaine=".$i." 
																AND Id_Vacation=".$rowVac['Id']."";
														$resultPrestationVacation=mysqli_query($bdd,$requete);
														$nbenregPrestationVacation=mysqli_num_rows($resultPrestationVacation);
														if($nbenregPrestationVacation>0)
														{
															$rowVacation=mysqli_fetch_array($resultPrestationVacation);
															$nbJour="";
															$nbEJ="";
															$nbEN="";
															$nbP="";
															$heureDebut="";
															$heureFin="";
															$teletravail=$rowVacation['Teletravail'];
															if($rowVacation['NbHeureJour']>0){$nbJour=$rowVacation['NbHeureJour'];}
															if($rowVacation['NbHeureEJ']>0){$nbEJ=$rowVacation['NbHeureEJ'];}
															if($rowVacation['NbHeureEN']>0){$nbEN=$rowVacation['NbHeureEN'];}
															if($rowVacation['NbHeurePause']>0){$nbP=$rowVacation['NbHeurePause'];}
															if($rowVacation['HeureDebut']<>'00:00:00'){$heureDebut=$rowVacation['HeureDebut'];}
															if($rowVacation['HeureFin']<>'00:00:00'){$heureFin=$rowVacation['HeureFin'];}
														?>
															<td align="center" width="6%"><input onKeyUp="nombre(this)" style="text-align:center;" name= <?php echo $rowVac['Id']."_".$i."_J"; ?> size="5" type="text" value="<?php echo $nbJour;?>"></td>
															<td align="center" width="6%"><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $rowVac['Id']."_".$i."_EJ"; ?> size="5" type="text" value="<?php echo $nbEJ;?>"></td>
															<td align="center" width="6%"><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $rowVac['Id']."_".$i."_EN"; ?> size="5" type="text" value="<?php echo $nbEN;?>"></td>
															<td align="center" width="6%"><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $rowVac['Id']."_".$i."_P"; ?> size="5" type="text" value="<?php echo $nbP;?>"></td>
															<td align="center" width="10%"><div class="input-group bootstrap-timepicker timepicker"><input class="form-control input-small heure" name=<?php echo $rowVac['Id']."_".$i."_HeureDebut"; ?> size="8" value="<?php echo $heureDebut;?>"></div></td>
															<td align="center" width="10%"><div class="input-group bootstrap-timepicker timepicker"><input class="form-control input-small heure" name=<?php echo $rowVac['Id']."_".$i."_HeureFin"; ?> size="8" value="<?php echo $heureFin;?>"></div></td>
															<td align="center" width="10%">
																<select name=<?php echo $rowVac['Id']."_".$i."_Teletravail"; ?> style="width:60px;">
																	<option value="0" <?php if($teletravail==0){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?></option>
																	<option value="1" <?php if($teletravail==1){echo "selected";} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?></option>
																</select>
															</td>
														<?php
														}
														else
														{
														?>
															<td align="center" width="6%"><input onKeyUp="nombre(this)" style="text-align:center;" name= <?php echo $rowVac['Id']."_".$i."_J"; ?> size="5" type="text" value=""></td>
															<td align="center" width="6%"><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $rowVac['Id']."_".$i."_EJ"; ?> size="5" type="text" value=""></td>
															<td align="center" width="6%"><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $rowVac['Id']."_".$i."_EN"; ?> size="5" type="text" value=""></td>
															<td align="center" width="6%"><input onKeyUp="nombre(this)" style="text-align:center;" name=<?php echo $rowVac['Id']."_".$i."_P"; ?> size="5" type="text" value=""></td>
															<td align="center" width="10%"><div class="input-group bootstrap-timepicker timepicker"><input class="form-control input-small heure" name=<?php echo $rowVac['Id']."_".$i."_HeureDebut"; ?> size="8" value=""></div></td>
															<td align="center" width="10%"><div class="input-group bootstrap-timepicker timepicker"><input class="form-control input-small heure"name=<?php echo $rowVac['Id']."_".$i."_HeureFin"; ?> size="8" value=""></div></td>
															<td align="center" width="10%">
																<select name=<?php echo $rowVac['Id']."_".$i."_Teletravail"; ?> style="width:50px;">
																	<option value="0"><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?></option>
																	<option value="1"><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?></option>
																</select>
															</td>
														<?php
														}
														?>
													</tr>
												<?php
												$i = $i + 1;
												} ?>
												<tr height='1' bgcolor='#66AACC'><td colspan='9'></td></tr>
											</tr>
											<?php
										}
									}
									?>
								</table>
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibellePlateforme"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation d'affectation :";}else{echo "Operating unit of assignment :";} ?></td>
							<td width="10%">
								<select name="plateforme" id="plateforme" style="width:150px" onchange="FiltrerPrestationPoleV2('id_plateforme','plateforme','LibellePlateforme');">
								<option value="0"></option>
								<?php
								$rq="SELECT Id, Libelle
									FROM new_competences_plateforme
									WHERE Id Not IN (11,14)
									ORDER BY Libelle ASC";

								$result=mysqli_query($bdd,$rq);
								while($row=mysqli_fetch_array($result))
								{
									$selected="";
									if($rowContrat['Id_Plateforme']==$row['Id']){$selected="selected";}
									echo "<option value='".$row['Id']."' ".$selected.">".str_replace("'"," ",$row['Libelle'])."</option>\n";
								}
								?>
								</select>
								<input type="hidden" name="id_plateforme" id="id_plateforme" value="<?php echo $rowContrat['Id_Plateforme']; ?>" />
							</td>
							<td width="10%" class="Libelle" id="LibellePrestationPole"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation d'affectation : ";}else{echo "Assignment service : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<select name="prestationPole" id="prestationPole" style="width:150px" onchange="ModifierCouleurChamps('id_prestationPole','prestationPole','LibellePrestationPole');">
								<option value="0"></option>
								<?php
								$rq="SELECT DISTINCT Id, Libelle, '' AS LibellePole, 0 AS Id_Pole,Id_Plateforme
									FROM new_competences_prestation
									WHERE Active=0
									AND Id NOT IN (
										SELECT Id_Prestation
										FROM new_competences_pole
										WHERE Actif=0
									)
									
									UNION 
									
									SELECT DISTINCT new_competences_pole.Id_Prestation, new_competences_prestation.Libelle, 
										new_competences_pole.Libelle AS LibellePole, new_competences_pole.Id AS Id_Pole,new_competences_prestation.Id_Plateforme
										FROM new_competences_pole
										INNER JOIN new_competences_prestation
										ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
										AND Active=0
										AND Actif=0
										
									ORDER BY Libelle, LibellePole";

								$result=mysqli_query($bdd,$rq);
								$i=0;
								while($rowsite=mysqli_fetch_array($result))
								{
									$selected="";
									if($rowContrat['Id_Prestation']."_".$rowContrat['Id_Pole']."_".$rowContrat['Id_Plateforme']==$rowsite['Id']."_".$rowsite['Id_Pole']."_".$rowsite['Id_Plateforme']){$selected="selected";}
									$display="style='display:none;'";
									if($rowContrat['Id_Plateforme']==$rowsite['Id_Plateforme']){$display="";}
									echo "<option class='presta' ".$display." value='".$rowsite['Id']."_".$rowsite['Id_Pole']."_".$rowsite['Id_Plateforme']."' ".$selected." >";
									$pole="";
									if($rowsite['Id_Pole']>0){$pole=" - ".$rowsite['LibellePole'];}
									echo str_replace("'"," ",stripslashes($rowsite['Libelle'].$pole))."</option>\n";
									
									echo "<script>Liste_PrestaPole[".$i."] = new Array(".$rowsite['Id'].",".$rowsite['Id_Pole'].",'".str_replace("'"," ",$rowsite['Libelle'])."','".str_replace("'"," ",$rowsite['LibellePole'])."',".$rowsite['Id_Plateforme'].");</script>";
									$i++;
								}
								?>
								</select>
								<input type="hidden" name="id_prestationPole" id="id_prestationPole" value="<?php echo $rowContrat['Id_Prestation']."_".$rowContrat['Id_Pole']."_".$rowContrat['Id_Plateforme']; ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleClient"><?php if($_SESSION["Langue"]=="FR"){echo "Client :";}else{echo "Client :";} ?></td>
							<td width="10%">
								<select name="client" id="client" style="width:150px" onchange="ModifierCouleurChamps('id_client','client','LibelleClient');">
								<option value="0"></option>
								<?php
								$rq="SELECT Id, Libelle
									FROM rh_client
									WHERE Suppr=0
									ORDER BY Libelle ASC";

								$result=mysqli_query($bdd,$rq);
								while($row=mysqli_fetch_array($result))
								{
									$selected="";
									if($rowContrat['Id_Client']==$row['Id']){$selected="selected";}
									echo "<option value='".$row['Id']."' ".$selected.">".str_replace("'"," ",$row['Libelle'])."</option>\n";
								}
								?>
								</select>
								<input type="hidden" name="id_client" id="id_client" value="<?php echo $rowContrat['Id_Client']; ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #cdbad2"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" valign="top" id="LibelleTitre"><?php if($_SESSION["Langue"]=="FR"){echo "Titre :";}else{echo "Title :";} ?></td>
							<td width="10%" valign="top" colspan="4">
								<input type="text" name="titre" id="titre" size="100"  onchange="ModifierCouleurChamps('id_titre','titre','LibelleTitre');" value="<?php echo str_replace("\\\\","",stripslashes($rowContrat['Titre'])); ?>">
								<input type="hidden" name="id_titre" id="id_titre" value="<?php echo str_replace("\\\\","",stripslashes($rowContrat['Titre'])); ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="4" id="LibelleMotif"><?php if($_SESSION["Langue"]=="FR"){echo "Motif";}else{echo "Reason";} ?></td>
						</tr>
						<tr>
							<td colspan="4">
								<textarea name="motif" id="motif" cols="90" rows="3" onchange="ModifierCouleurChamps('id_motif','motif','LibelleMotif');" noresize="noresize"><?php echo str_replace("\\\\","",stripslashes($rowContrat['Motif'])); ?></textarea>
								<input type="hidden" name="id_motif" id="id_motif" value="<?php echo str_replace("\\\\","",stripslashes($rowContrat['Motif'])); ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="4" id="LibelleRemarque"><?php if($_SESSION["Langue"]=="FR"){echo "Remarque";}else{echo "Note";} ?></td>
						</tr>
						<tr>
							<td colspan="4">
								<textarea name="remarque" id="remarque" onchange="ModifierCouleurChamps('id_remarque','remarque','LibelleRemarque');" cols="90" rows="3" noresize="noresize"><?php echo str_replace("\\\\","",stripslashes($rowContrat['Remarque'])); ?></textarea>
								<input type="hidden" name="id_remarque" id="id_remarque" value="<?php echo str_replace("\\\\","",stripslashes($rowContrat['Remarque'])); ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #cdbad2"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleDateSignatureSiege"><?php if($_SESSION["Langue"]=="FR"){echo "Date de signature du siège :";}else{echo "Date of signature of the registered office :";} ?> </td>
							<td width="10%">
								<input type="date" style="text-align:center;" onChange="ModifierCouleurChamps('id_dateSignatureSiege','dateSignatureSiege','LibelleDateSignatureSiege')" id="dateSignatureSiege" name="dateSignatureSiege" size="10" value="<?php echo AfficheDateFR($rowContrat['DateSignatureSiege']); ?>">
								<input type="hidden" name="id_dateSignatureSiege" id="id_dateSignatureSiege" value="<?php echo AfficheDateFR($rowContrat['DateSignatureSiege']); ?>" />
							</td>
							<td width="10%" class="Libelle" id="LibelleDateSignatureSalarie"><?php if($_SESSION["Langue"]=="FR"){echo "Date de signature du salarié :";}else{echo "Date of signature of the employee :";} ?> </td>
							<td width="10%">
								<input type="date" style="text-align:center;" onChange="ModifierCouleurChamps('id_dateSignatureSalarie','dateSignatureSalarie','LibelleDateSignatureSalarie')" id="dateSignatureSalarie" name="dateSignatureSalarie" size="10" value="<?php echo AfficheDateFR($rowContrat['DateSignatureSalarie']); ?>">
								<input type="hidden" name="id_dateSignatureSalarie" id="id_dateSignatureSalarie" value="<?php echo AfficheDateFR($rowContrat['DateSignatureSalarie']); ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleDateRetourSigne"><?php if($_SESSION["Langue"]=="FR"){echo "Date de retour signé au siège :";}else{echo "Date of return signed at the head office :";} ?> </td>
							<td width="10%">
								<input type="date" style="text-align:center;" onChange="ModifierCouleurChamps('id_dateRetourSigne','dateRetourSigne','LibelleDateRetourSigne')" id="dateRetourSigne" name="dateRetourSigne" size="10" value="<?php echo AfficheDateFR($rowContrat['DateRetourSigneAuSiege']); ?>">
								<input type="hidden" name="id_dateRetourSigne" id="id_dateRetourSigne" value="<?php echo AfficheDateFR($rowContrat['DateRetourSigneAuSiege']); ?>" />
							</td>
						</tr>
						<tr>
							<td colspan="6" align="center">
								<div id="Ajouter">
								</div>
								<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="if(window.confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir enregistrer ?";}else{echo "Are you sure you want to save ?";} ?>')){Enregistrer();}else{return false;}">
							</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
</table>
<?php
	echo "<script>FiltrerPrestationPole();</script>";
	echo "<script>RechargerListeTAG2();</script>";
	echo "<script>CompareSalaire();</script>";
}
?>
</form>
</body>
</html>
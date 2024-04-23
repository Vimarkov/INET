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
		//Modification de l'ODM
		$IPD=0;
		if($_POST['indemniteDeplacement']<>""){$IPD=$_POST['indemniteDeplacement'];}
		$repas=0;
		if($_POST['indemniteRepas']<>""){$repas=$_POST['indemniteRepas'];}
		$IGD=0;
		if($_POST['indemniteIGD']<>""){$IGD=$_POST['indemniteIGD'];}
		$repasGD=0;
		if($_POST['indemniteRepasGD']<>""){$repasGD=$_POST['indemniteRepasGD'];}
		$fraisReel=0;
		if($_POST['fraisReel']<>""){$fraisReel=$_POST['fraisReel'];}
		$primeResp=0;
		if($_POST['primeResponsabilite']<>""){$primeResp=$_POST['primeResponsabilite'];}
		$primeEquipe=0;
		if($_POST['primeEquipe']<>""){$primeEquipe=$_POST['primeEquipe'];}
		$vsd=0;
		if($_POST['majorationVSD']<>""){$vsd=$_POST['majorationVSD'];}
		$indemniteOutillage=0;
		if($_POST['indemniteOutillage']<>""){$indemniteOutillage=$_POST['indemniteOutillage'];}
		$panierGD=0;
		if($_POST['panierGD']<>""){$panierGD=$_POST['panierGD'];}
		$panierVSD=0;
		if($_POST['panierVSD']<>""){$panierVSD=$_POST['panierVSD'];}
		
		$Id_Prestation=0;
		$Id_Pole=0;
		if($_POST['prestationPole']<>"0"){
			$arrayPrestaPole=explode("_",$_POST['prestationPole']);
			$Id_Prestation=$arrayPrestaPole[0];
			$Id_Pole=$arrayPrestaPole[1];
		}
		$req="UPDATE rh_personne_contrat 
			SET Id_Metier=".$_POST['metier'].",
				DateDebut='".TrsfDate_($_POST['dateDebut'])."',
				DateFin='".TrsfDate_($_POST['dateFin'])."',
				Id_Prestation=".$Id_Prestation.",
				Id_Pole=".$Id_Pole.",
				DateModification='".date('Y-m-d')."',
				Id_Modif=".$_SESSION['Id_Personne'].",
				Id_Client=".$_POST['client'].",
				Id_Responsable=".$_POST['responsableAAA'].",
				MontantIPD=".$IPD.",
				MontantRepas=".$repas.",
				MontantIGD=".$IGD.",
				MontantRepasGD=".$repasGD.",
				FraisReel=".$fraisReel.",
				PrimeResponsabilite=".$primeResp.",
				IndemniteOutillage=".$indemniteOutillage.",
				MajorationVSD=".$vsd.",
				PrimeEquipe=".$primeEquipe.",
				PanierGrandeNuit=".$panierGD.",
				PanierVSD=".$panierVSD.",
				Motif='".addslashes($_POST['descriptionMission'])."',
				Titre='".addslashes($_POST['titre'])."',
				DateSignatureSiege='".TrsfDate_($_POST['dateSignatureSiege'])."',
				DateSignatureSalarie='".TrsfDate_($_POST['dateSignatureSalarie'])."',
				DateRetourSigneAuSiege='".TrsfDate_($_POST['dateRetourSigne'])."',
				ChampsModifie='".$_POST['ChampsModifies']."'
			WHERE 
				Id=".$_POST['Id_Contrat']."";
		$resultModif=mysqli_query($bdd,$req);

		//Mise à jour des moyens de déplacement
		if($_POST['Id_Contrat']>0){
			$req="SELECT Id FROM rh_moyendeplacement WHERE Suppr=0";
			$result=mysqli_query($bdd,$req);
			$nbResulta=mysqli_num_rows($result);
			
			$requeteInsert="INSERT INTO rh_personne_contrat_moyendeplacement (Id_Personne_Contrat,Id_MoyenDeplacement,Montant,Periodicite,Reference) VALUES ";
			if ($nbResulta>0){
				while($row=mysqli_fetch_array($result)){
					if(isset($_POST['moyensCheck_'.$row['Id']])){
						$montant=0;
						if($_POST['montant_'.$row['Id']]<>""){$montant=$_POST['montant_'.$row['Id']];}
						$requeteInsert.=" (".$_POST['Id_Contrat'].",".$row['Id'].",".$montant.",'".addslashes($_POST['periodicite_'.$row['Id']])."',
						'".addslashes($_POST['reference_'.$row['Id']])."'),";
					}
				}
			}
			
			$requeteSupp="UPDATE rh_personne_contrat_moyendeplacement 
						SET Suppr=1,
						Id_Suppr=".$_SESSION['Id_Personne'].",
						DateSuppr='".date('Y-m-d')."' 
						WHERE Id_Personne_Contrat=".$_POST['Id_Contrat'];
			$resultSupp=mysqli_query($bdd,$requeteSupp);
			
			$requeteInsert =  substr($requeteInsert, 0, -1).";" ;
			$resultInsert=mysqli_query($bdd,$requeteInsert);
		}

		echo "<script>FermerEtRechargerODM('".$Menu."','".$_POST['Id_Personne']."','".$_POST['Page']."')</script>";
		
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

		echo "<script>FermerEtRechargerODM('".$Menu."','".$_GET['Id_Personne']."','".$_GET['Page']."')</script>";
	}
$req="SELECT Id,Id_ContratInitial,Id_Personne,Id_TypeContrat,Id_AgenceInterim,Id_Metier,SalaireReference,TypeCoeff,CoeffFacturationAgence,SalaireBrut,
	(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS TypeContrat,Id_Responsable,
	(SELECT LibelleEN FROM rh_typecontrat WHERE rh_typecontrat.Id=rh_personne_contrat.Id_TypeContrat) AS TypeContratEN,
	TauxHoraire,DateDebut,DateFin,DateFinPeriodeEssai,Id_TempsTravail,Id_Prestation,Id_Pole,TypeDocument,DateCreation,Id_Createur,
	DateSouplessePositive,DateSouplesseNegative,Remarque,Id_LieuTravail,Id_Client,DateSignatureSiege,DateSignatureSalarie,DateRetourSigneAuSiege,
	Id_Responsable,MontantIPD,MontantRepas,MontantIGD,MontantRepasGD,Titre,
			FraisReel,PrimeResponsabilite,IndemniteOutillage,MajorationVSD,PrimeEquipe,PanierGrandeNuit,PanierVSD,Motif,
	(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation) AS Id_Plateforme,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_contrat.Id_Personne) AS Personne,ChampsModifie 
	FROM rh_personne_contrat 
	WHERE Id=".$_GET['Id']."
	";
$result=mysqli_query($bdd,$req);
$rowContrat=mysqli_fetch_array($result);

$Id_Contrat=$_GET['Id'];
$etoile="<img src='../../Images/etoile.png' width='8' height='8' border='0'>";
}
?>

<form id="formulaire" class="test" action="Modif_ODM.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="Id_Personne" id="Id_Personne" value="<?php if($_GET['Page']=="Liste_ContratHistorique"){echo $_GET['Id_Personne'];}else{echo 0;} ?>" />
	<input type="hidden" name="Page" id="Page" value="<?php echo $_GET['Page']; ?>" />
	<input type="hidden" name="Id_Contrat" id="Id_Contrat" value="<?php echo $Id_Contrat; ?>" />
	<input type="hidden" name="ChampsModifies" id="ChampsModifies" value="<?php echo $rowContrat['ChampsModifie']; ?>" />
	<input type="hidden" name="AppliquerAuxAutresContrats" id="AppliquerAuxAutresContrats" value="0" />
	<tr>
		<td colspan="5">
			<table style="width:100%; border-spacing:0;">
				<tr>
					<td class="TitrePage" style="background-color:#a988b2;">
					<?php 
						if($_SESSION["Langue"]=="FR"){echo "ODM n° ".$rowContrat['Id'];}else{echo "Mission order n° ".$rowContrat['Id'];}
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
							<td width="10%" class="Libelle" id="LibelleTypeContrat"><?php if($_SESSION["Langue"]=="FR"){echo "Type de contrat :";}else{echo "Type of Contract :";} ?></td>
							<td width="10%">
								<?php if($_SESSION["Langue"]=="FR"){echo $rowContrat['TypeContrat'];}else{echo $rowContrat['TypeContratEN'];} ?>
								<input type="hidden" name="id_typeContrat" id="id_typeContrat" value="<?php echo $rowContrat['Id_TypeContrat']; ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleMetier"><?php if($_SESSION["Langue"]=="FR"){echo "Métier : ";}else{echo "Job : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<select name="metier" id="metier" style="width:200px" onchange="ModifierCouleurChamps('id_Metier','metier','LibelleMetier')">
								<option value="0"></option>
								<?php
								$rq="SELECT Id, Libelle
									FROM new_competences_metier
									WHERE Suppr=0
									ORDER BY Libelle ASC";
								$result=mysqli_query($bdd,$rq);
								while($row=mysqli_fetch_array($result))
								{
									$selected="";
									if($rowContrat['Id_Metier']==$row['Id']){$selected="selected";}
									echo "<option value='".$row['Id']."' ".$selected.">".str_replace("'"," ",$row['Libelle'])."</option>\n";
								}
								?>
								</select>
								<input type="hidden" name="id_Metier" id="id_Metier" value="<?php echo $rowContrat['Id_Metier']; ?>" />
							</td>
						</tr>
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
							<td width="10%" class="Libelle" valign="top" id="LibelleTitre"><?php if($_SESSION["Langue"]=="FR"){echo "Titre :";}else{echo "Title :";} ?></td>
							<td width="10%" valign="top" colspan="4">
								<input type="text" name="titre" id="titre" size="100"  onchange="ModifierCouleurChamps('id_titre','titre','LibelleTitre');" value="<?php echo str_replace("\\\\","",stripslashes($rowContrat['Titre'])); ?>">
								<input type="hidden" name="id_titre" id="id_titre" value="<?php echo str_replace("\\\\","",stripslashes($rowContrat['Titre'])); ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #cdbad2"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="4"><?php if($_SESSION["Langue"]=="FR"){echo "Description de la mission";}else{echo "Description of the mission";} ?></td>
						</tr>
						<tr>
							<td colspan="4">
								<textarea name="descriptionMission" id="descriptionMission" onchange="ModifierCouleurChamps('id_descriptionMission','descriptionMission','LibelleDescriptionMission');" cols="90" rows="3" noresize="noresize"><?php echo stripslashes($rowContrat['Motif']); ?></textarea>
								<input type="hidden" name="id_descriptionMission" id="id_descriptionMission" value="<?php echo stripslashes($rowContrat['Motif']); ?>" />
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
							<td width="10%" class="Libelle" id="LibelleResponsable"><?php if($_SESSION["Langue"]=="FR"){echo "Responsable AAA chez le client : ";}else{echo "AAA Manager at the client : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<select name="responsableAAA" id="responsableAAA" style="width:150px" onchange="ModifierCouleurChamps('id_Responsable','responsableAAA','LibelleResponsable');">
								<option value="0"></option>
								<?php
								$rq="SELECT new_rh_etatcivil.Id, 
									CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
									FROM new_rh_etatcivil ";
								$rq.="ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";

								$result=mysqli_query($bdd,$rq);
								while($row=mysqli_fetch_array($result))
								{
									$selected="";
									if($rowContrat['Id_Responsable']==$row['Id']){$selected="selected";}
									echo "<option value='".$row['Id']."' ".$selected.">".str_replace("'"," ",$row['Personne'])."</option>\n";
								}
								?>
								</select>
								<input type="hidden" name="id_Responsable" id="id_Responsable" value="<?php echo $rowContrat['Id_Responsable']; ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleDateDebut"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début : ";}else{echo "Start date : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<input type="date" style="text-align:center;" onChange="ModifierCouleurChamps('id_dateDebut','dateDebut','LibelleDateDebut')" id="dateDebut" name="dateDebut" size="10" value="<?php echo AfficheDateFR($rowContrat['DateDebut']); ?>">
								<input type="hidden" name="id_dateDebut" id="id_dateDebut" value="<?php echo AfficheDateFR($rowContrat['DateDebut']); ?>" />
							</td>
							<td width="10%" class="Libelle" id="LibelleDateFin"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin :";}else{echo "End date :";} ?> </td>
							<td width="10%">
								<input type="date" style="text-align:center;"  onChange="ModifierCouleurChamps('id_dateFin','dateFin','LibelleDateFin')" id="dateFin" name="dateFin" size="10" value="<?php echo AfficheDateFR($rowContrat['DateFin']); ?>">
								<input type="hidden" name="id_dateFin" id="id_dateFin" value="<?php echo AfficheDateFR($rowContrat['DateFin']); ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #cdbad2"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="2" rowspan="2" valign="2"><?php if($_SESSION["Langue"]=="FR"){echo "Indemnité petit déplacement (IPD)";}else{echo "Small displacement allowance";} ?> </td>
							<td width="10%" class="Libelle" id="LibelleIndemniteDeplacement"><?php if($_SESSION["Langue"]=="FR"){echo "Indemnité déplacement (par jour travaillé) : ";}else{echo "Displacement allowance (per day worked) : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<input onKeyUp="nombre(this)" type="text" style="text-align:center;" onChange="ModifierCouleurChamps('id_indemniteDeplacement','indemniteDeplacement','LibelleIndemniteDeplacement')" id="indemniteDeplacement" name="indemniteDeplacement" size="10" value="<?php echo $rowContrat['MontantIPD']; ?>">
								<input type="hidden" name="id_indemniteDeplacement" id="id_indemniteDeplacement" value="<?php echo $rowContrat['MontantIPD']; ?>" />
							</td>
						</tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleIndemniteRepas"><?php if($_SESSION["Langue"]=="FR"){echo "Indemnité repas : ";}else{echo "Meal allowance : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<input onKeyUp="nombre(this)" type="text" style="text-align:center;" onChange="ModifierCouleurChamps('id_indemniteRepas','indemniteRepas','LibelleIndemniteRepas')" id="indemniteRepas" name="indemniteRepas" size="10" value="<?php echo $rowContrat['MontantRepas']; ?>">
								<input type="hidden" name="id_indemniteRepas" id="id_indemniteRepas" value="<?php echo $rowContrat['MontantRepas']; ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="2" rowspan="2" valign="2"><?php if($_SESSION["Langue"]=="FR"){echo "Indemnité grand déplacement (IGD)";}else{echo "Large displacement allowance";} ?> </td>
							<td width="10%" class="Libelle" id="LibelleIndemniteIGD"><?php if($_SESSION["Langue"]=="FR"){echo "Indemnité de découcher + petit déjeuner :";}else{echo "Allowance to leave + breakfast :";} ?> </td>
							<td width="10%">
								<input onKeyUp="nombre(this)" type="text" style="text-align:center;" onChange="ModifierCouleurChamps('id_indemniteIGD','indemniteIGD','LibelleIndemniteIGD')" id="indemniteIGD" name="indemniteIGD" size="10" value="<?php echo $rowContrat['MontantIGD']; ?>">
								<input type="hidden" name="id_indemniteIGD" id="id_indemniteIGD" value="<?php echo $rowContrat['MontantIGD']; ?>" />
							</td>
						</tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleIndemniteRepasGD"><?php if($_SESSION["Langue"]=="FR"){echo "Indemnité repas :";}else{echo "Meal allowance :";} ?> </td>
							<td width="10%">
								<input onKeyUp="nombre(this)" type="text" style="text-align:center;" onChange="ModifierCouleurChamps('id_indemniteRepasGD','indemniteRepasGD','LibelleIndemniteRepasGD')" id="indemniteRepasGD" name="indemniteRepasGD" size="10" value="<?php echo $rowContrat['MontantRepasGD']; ?>">
								<input type="hidden" name="id_indemniteRepasGD" id="id_indemniteRepasGD" value="<?php echo $rowContrat['MontantRepasGD']; ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleFraisReel"><?php if($_SESSION["Langue"]=="FR"){echo "Frais réels :";}else{echo "Real costs :";} ?> </td>
							<td width="10%">
								<input onKeyUp="nombre(this)" type="text" style="text-align:center;" onChange="ModifierCouleurChamps('id_fraisReel','fraisReel','LibelleFraisReel')" id="fraisReel" name="fraisReel" size="10" value="<?php echo $rowContrat['FraisReel']; ?>">
								<input type="hidden" name="id_fraisReel" id="id_fraisReel" value="<?php echo $rowContrat['FraisReel']; ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibellePrimeResponsabilite"><?php if($_SESSION["Langue"]=="FR"){echo "Prime de responsabilité (par mois) :";}else{echo "Liability premium (per month) :";} ?> </td>
							<td width="10%">
								<input onKeyUp="nombre(this)" type="text" style="text-align:center;" onChange="ModifierCouleurChamps('id_primeResponsabilite','primeResponsabilite','LibellePrimeResponsabilite')" id="primeResponsabilite" name="primeResponsabilite" size="10" value="<?php echo $rowContrat['PrimeResponsabilite']; ?>">
								<input type="hidden" name="id_primeResponsabilite" id="id_primeResponsabilite" value="<?php echo $rowContrat['PrimeResponsabilite']; ?>" />
							</td>
							<td width="10%" class="Libelle" id="LibellePrimeEquipe"><?php if($_SESSION["Langue"]=="FR"){echo "Prime d'équipe <img style='width:10px;' src='../../Images/etoile.png' border='0' /> (par jour) :";}else{echo "Team bonus (per day) :";} ?> </td>
							<td width="10%">
								<input onKeyUp="nombre(this)" type="text" style="text-align:center;" onChange="ModifierCouleurChamps('id_primeEquipe','primeEquipe','LibellePrimeEquipe')" id="primeEquipe" name="primeEquipe" size="10" value="<?php echo $rowContrat['PrimeEquipe']; ?>">
								<input type="hidden" name="id_primeEquipe" id="id_primeEquipe" value="<?php echo $rowContrat['PrimeEquipe']; ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleIndemniteOutillage"><?php if($_SESSION["Langue"]=="FR"){echo "Indemnité outillage (par heure) :";}else{echo "Tool allowance (per hour) :";} ?> </td>
							<td width="10%">
								<input onKeyUp="nombre(this)" type="text" style="text-align:center;" onChange="ModifierCouleurChamps('id_indemniteOutillage','indemniteOutillage','LibelleIndemniteOutillage')" id="indemniteOutillage" name="indemniteOutillage" size="10" value="<?php echo $rowContrat['IndemniteOutillage']; ?>">
								<input type="hidden" name="id_indemniteOutillage" id="id_indemniteOutillage" value="<?php echo $rowContrat['IndemniteOutillage']; ?>" />
							</td>
							<td width="10%" class="Libelle" id="LibellePanierGD"><?php if($_SESSION["Langue"]=="FR"){echo "Panier grande nuit (par nuit) :";}else{echo "Basket big night (per night) :";} ?> </td>
							<td width="10%">
								<input onKeyUp="nombre(this)" type="text" style="text-align:center;" onChange="ModifierCouleurChamps('id_panierGD','panierGD','LibellePanierGD')" id="panierGD" name="panierGD" size="10" value="<?php echo $rowContrat['PanierGrandeNuit']; ?>">
								<input type="hidden" name="id_panierGD" id="id_panierGD" value="<?php echo $rowContrat['PanierGrandeNuit']; ?>" />
							</td>
						</tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleMajorationVSD"><?php if($_SESSION["Langue"]=="FR"){echo "Majoration VSD :";}else{echo "FSS enhancement :";} ?> </td>
							<td width="10%">
								<input onKeyUp="nombre(this)" type="text" style="text-align:center;" onChange="ModifierCouleurChamps('id_majorationVSD','majorationVSD','LibelleMajorationVSD')" id="majorationVSD" name="majorationVSD" size="10" value="<?php echo $rowContrat['MajorationVSD']; ?>">
								<input type="hidden" name="id_majorationVSD" id="id_majorationVSD" value="<?php echo $rowContrat['MajorationVSD']; ?>" />
							</td>
							<td width="10%" class="Libelle" id="LibellePanierVSD"><?php if($_SESSION["Langue"]=="FR"){echo "Panier VSD :";}else{echo "FSS Basket :";} ?> </td>
							<td width="10%">
								<input onKeyUp="nombre(this)" type="text" style="text-align:center;" onChange="ModifierCouleurChamps('id_panierVSD','panierVSD','LibellePanierVSD')" id="panierVSD" name="panierVSD" size="10" value="<?php echo $rowContrat['PanierVSD']; ?>">
								<input type="hidden" name="id_panierVSD" id="id_panierVSD" value="<?php echo $rowContrat['PanierVSD']; ?>" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="3">
							<img style='width:10px;' src='../../Images/etoile.png' border='0' />
							<?php if($_SESSION["Langue"]=="FR"){echo "si travail en équipe";}else{echo "if team work";} ?>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #cdbad2"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Moyens de déplacement";}else{echo "Means of displacement";} ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="6">
								<table class="TableCompetences" align="center" width="80%">
								<tr>
									<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION["Langue"]=="FR"){echo "Moyens";}else{echo "Means";} ?></td>
									<td class="EnTeteTableauCompetences" width="5%" style="text-align:center;"><?php if($_SESSION["Langue"]=="FR"){echo "X";}else{echo "X";} ?></td>
									<td class="EnTeteTableauCompetences" width="20%" style="text-align:center;"><?php if($_SESSION["Langue"]=="FR"){echo "Montant<br>(maximum en euros)";}else{echo "Amount<br>(maximum in euros)";} ?></td>
									<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;"><?php if($_SESSION["Langue"]=="FR"){echo "Périodicité";}else{echo "Periodicity";} ?></td>
									<td class="EnTeteTableauCompetences" width="30%"><?php if($_SESSION["Langue"]=="FR"){echo "Référence - justificatifs";}else{echo "Reference - proof";} ?></td>
								</tr>
								<?php
								if($_SESSION["Langue"]=="FR"){$req="SELECT Id, Libelle FROM rh_moyendeplacement WHERE Suppr=0 ORDER BY Libelle";}
								else{$req="SELECT Id, LibelleEN AS Libelle FROM rh_moyendeplacement WHERE Suppr=0 ORDER BY LibelleEN";}
								$result=mysqli_query($bdd,$req);
								$nbResulta=mysqli_num_rows($result);
								if ($nbResulta>0){
									while($row=mysqli_fetch_array($result)){
										echo "<tr>";
											
											if($row['Libelle']=="Autres" || $row['Libelle']=="Autre" || $row['Libelle']=="Others" || $row['Libelle']=="Other"|| 
											$row['Libelle']=="°Autres" || $row['Libelle']=="°Autre" || $row['Libelle']=="°Others" || $row['Libelle']=="°Other"){
												echo"<td>".substr($row['Libelle'],1)." : </td>";
											}
											else{
												echo "<td>".$row['Libelle']." : </td>";
											}
											
											$Montant="";
											$Periodicite="";
											$Reference="";
											$checked="";
											$req="SELECT 
												Montant,Periodicite,Reference 
												FROM rh_personne_contrat_moyendeplacement 
												WHERE Suppr=0 
												AND Id_MoyenDeplacement=".$row['Id']."
												AND Id_Personne_Contrat=".$_GET['Id'];
											$resultM=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($resultM);
											if ($nbResulta>0){
												$checked="checked";
												$rowM=mysqli_fetch_array($resultM);
												$Montant=$rowM['Montant'];
												$Periodicite=$rowM['Periodicite'];
												$Reference=$rowM['Reference'];
											}
											
											echo "<td  style='text-align:center;'><input type='checkbox' ".$checked." name='moyensCheck_".$row['Id']."' value='moyensCheck_".$row['Id']."'></td>";
											echo "<td  style='text-align:center;'><input onKeyUp='nombre(this)' type='text' size='10' name='montant_".$row['Id']."' id='montant_".$row['Id']."' value='".$Montant."'></td>";
											echo "<td  style='text-align:center;'><input type='text' size='10' name='periodicite_".$row['Id']."' id='periodicite_".$row['Id']."' value='".$Periodicite."'></td>";
											echo "<td><input type='text' size='40' name='reference_".$row['Id']."' id='reference_".$row['Id']."' value='".$Reference."'></td>";
										echo "</tr>";
									}
								}
								?>
								</table>
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
								<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="if(window.confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir enregistrer ?";}else{echo "Are you sure you want to save ?";} ?>')){EnregistrerODM();}else{return false;}">
							</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
</table>
</form>
<?php
	echo "<script>FiltrerPrestationPole();</script>";
	mysqli_close($bdd);					// Fermeture de la connexion
}
?>
</body>
</html>
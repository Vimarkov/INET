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
	<script>
		function FermerEtRecharger()
		{
			window.opener.location="Annonces.php";
			window.close();
		}
		function Postuler(Id)
		{var w=window.open("Postuler.php?Mode=M&Id="+Id,"PagePostuler","status=no,scrollbars=1,menubar=no,width=1300,height=550");
		w.focus();
		}
		function ModifPostule(Id,Id_Candidat)
		{var w=window.open("Postuler.php?Mode=Mo&Id="+Id+"&Id_Candidat="+Id_Candidat,"PagePostuler","status=no,scrollbars=1,menubar=no,width=1300,height=550");
		w.focus();
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

$bEnregistrement=false;
if($_POST){
	if(isset($_POST['btnEnregistrer2'])){
		
		$requete="UPDATE talentboost_annonce 
			SET Id_Domaine=".$_POST['domaine'].",
			Programme='".$_POST['programme']."',
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
			IGD='".addslashes($_POST['IGD'])."',
			Salaire='".addslashes($_POST['salaire'])."'
			WHERE Id=".$_POST['Id']." ";
		$result=mysqli_query($bdd,$requete);
	}
	echo "<script>FermerEtRecharger();</script>";
}
if($_SESSION["Langue"]=="FR"){
	$reqSuite="IF(ValidationContratDG<>0,'OUI','NON') AS Etat,
			IF(ValidationContratDG=0,'BESOIN EN ATTENTE VALIDATION DG',
				IF(ValidationContratDG=-1,'BESOIN REFUS� PAR LA DG','OFFRE')
			) AS Statut,
			IF(ValidationContratDG=0,'',
					IF(ValidationContratDG=-1,'',
						IF(EtatPoste=0,'Poste ouvert',IF(EtatPoste=1,'Poste pourvu',IF(EtatPoste=2,'Poste non pourvu',IF(EtatPoste=3,'Poste pourvu partiellement',IF(EtatPoste=4,'Demande cl�tur�e','Poste annul�')))))
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
$requete="SELECT Id,DateDemande,Id_Demandeur,Id_Prestation,Metier,Nombre,Lieu,RaisonRefus,Suppr,
			".$reqSuite."
			CONCAT(Metier,'-',
			Lieu,'-',
			Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',DATE_FORMAT(DateRecrutement,'%d%m%y')
			) AS Ref,EtatPoste,
			EtatApprobation,EtatRecrutement,Programme,CreationPoste,CategorieProf,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_PersonneAContacter) AS PersonneAContacter,
			DescriptifPoste,SavoirFaire,SavoirEtre,Prerequis,DateValidationDG AS DateRecrutement,IF(DateActualisation>'0001-01-01',DateActualisation,DateValidationDG) AS DateButoire,Diplome,
			DateBesoin,PosteDefinitif,Id_Prestation,Id_Domaine,
			(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
			(SELECT Libelle FROM talentboost_domaine WHERE Id=Id_Domaine) AS Domaine,Langue,
			(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
			(SELECT Document FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS DocPlateforme,
			Id_Plateforme,
			FicheMetier AS DocMetier,
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

$DirFichier=$CheminRecrutement;

if($row['EtatRecrutement']<>0){$Couleur="#ecf943";}
else{$Couleur="#6c94d0";}
					
$typedateRH="text";
$modifiableRecrut="readonly='readonly'";
?>

<form id="formulaire" class="test" action="Modif_Besoin.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
	<input type="hidden" name="ValiderRefuser" id="ValiderRefuser" value="" />
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
								if($_SESSION["Langue"]=="FR"){echo "OFFRE INTERNE";}else{echo "INTERNAL OFFER";}
							?>
							</td>
						</tr>
						<tr height="10">
							<td width="15%" bgcolor="#2e5496" style="color:#ffffff;border:1px solid black;" class="Libelle" rowspan="2">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Poste recherch�";}else{echo "Job sought";} ?></td>
							<td width="10%" colspan="2" class="Libelle" style="border:1px solid black;">&nbsp;&nbsp;
							<table width="100%">
								<tr>
									<td>
										<?php echo stripslashes($row['Metier']); ?>
									</td>
									<?php
									if($row['DocMetier']<>""){
										echo "<td><a class=\"Info\" href=\"".$DirFichier.$row['DocMetier']."\" target=\"_blank\">";
										echo "<img src='../../Images/image.png' border='0'>";
										echo "</a></td>";
									}
									?>
								</tr>
							</table>
							</td>
							<td width="10%" class="Libelle" bgcolor="#cad8ee" style="color:#2c538b;border:1px solid black;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Lieu :";}else{echo "Place :";} ?></td>
							<td width="10%" class="Libelle" style="border:1px solid black;">&nbsp;&nbsp;<?php echo stripslashes($row['Lieu']);
							if($row['DocPlateforme']<>""){
								echo "<a class=\"Info\" href=\"".$DirFichier.$row['DocPlateforme']."\" target=\"_blank\">";
								echo "<img src='../../Images/Carte.png' width='25px' border='0'>";
								echo "</a>";
							}
							?>
							</td>
							<td class="Libelle" width="10%" bgcolor="#cad8ee" style="color:#2c538b;border:1px solid black;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Programme";}else{echo "Programm";}?> : </td>
							<td width="10%" class="Libelle" style="border:1px solid black;">
								&nbsp;&nbsp;<?php echo stripslashes($row['Programme']); ?>
							</td>
							<td width="10%" class="Libelle" bgcolor="#cad8ee" style="color:#2c538b;border:1px solid black;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Domaine :";}else{echo "Domain :";} ?></td>
							<td width="10%" class="Libelle" style="border:1px solid black;">&nbsp;&nbsp;<?php echo stripslashes($row['Domaine']); ?>
							</td>
						</tr>
						<tr height="10">
							<td class="Libelle" bgcolor="#cad8ee" style="color:#2c538b;border:1px solid black;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Date d�but de poste souhait�e";}else{echo "Desired start date";}?> : </td>
							<td class="Libelle" style="border:1px solid black;">&nbsp;&nbsp;<?php echo AfficheDateJJ_MM_AAAA($row['DateBesoin']); ?></td>
							<td class="Libelle" bgcolor="#cad8ee" style="color:#2c538b;border:1px solid black;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Type de poste";}else{echo "Position type";}?> : </td>
							<td class="Libelle" style="border:1px solid black;">&nbsp;&nbsp;
								<?php if($row['PosteDefinitif']==1){
										if($LangueAffichage=="FR"){echo "Poste d�finitif";}else{echo "Definitive position";}
									}
									elseif($row['PosteDefinitif']==0){
										if($LangueAffichage=="FR"){echo "Mission";}else{echo "Mission";}
									}
									elseif($row['PosteDefinitif']==2){
										if($LangueAffichage=="FR"){echo "CDD 6 mois";}else{echo "CDD 6 mois";}
									}
									elseif($row['PosteDefinitif']==3){
										if($LangueAffichage=="FR"){echo "CDD 2 mois";}else{echo "CDD 2 mois";}
									}
									elseif($row['PosteDefinitif']==4){
										if($LangueAffichage=="FR"){echo "CDD";}else{echo "CDD";}
									}
								?>
							</td>
							<td class="Libelle" bgcolor="#cad8ee" style="color:#2c538b;border:1px solid black;" >&nbsp;<?php if($LangueAffichage=="FR"){echo "Statut";}else{echo "Status";}?> : </td>
							<td class="Libelle" colspan="3" style="border:1px solid black;">&nbsp;&nbsp;<?php echo $row['CategorieProf']; ?></td>
						</tr>
						<tr height="30">
							<td bgcolor="#2e5496" style="color:#ffffff;" class="Libelle" style="border:1px solid black;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Etat du poste";}else{echo "Position status";} ?></td>
							<td class="Libelle" colspan="8" style="border:1px solid black;" align="center">
								<?php 
								$req="SELECT Id,DateCreation FROM talentboost_candidature WHERE Suppr=0 AND Id_Annonce=".$_GET['Id']." AND Id_Personne=".$_SESSION['Id_Personne']." ";
								$resultCandidature=mysqli_query($bdd,$req);
								$nbCandidature=mysqli_num_rows($resultCandidature);

								if($row['EtatPoste']==0 && $nbCandidature==0){ 
									$dateButoir=date("Y-m-d",strtotime($row['DateButoire']." +22 day"));
									$JourdateButoir=date("w",strtotime($row['DateButoire']." +22 day"));
									if($JourdateButoir==6){$dateButoir=date("Y-m-d",strtotime($row['DateButoire']." +24 day"));}
									if($JourdateButoir==0){$dateButoir=date("Y-m-d",strtotime($row['DateButoire']." +23 day"));}
									if(date('Y-m-d')<=$dateButoir){
								?>
								<input class="Bouton" style="font-size:14px;" type="button" value="<?php if($_SESSION["Langue"]=="FR"){echo "Postuler";}else{echo "Apply";} ?>" onclick="Postuler(<?php echo $row['Id'] ;?>);" />
								<?php 
									}
									else{echo "Ce poste a �t� cl�tur� sur TalentBoost";}
								}
								else{
									if($row['EtatPoste']<>0){
										//echo stripslashes($row['Statut2']);
										if($nbCandidature>0){
											echo "Ce poste a �t� cl�tur� sur la Bourse � Emploi, nous vous tiendrons inform�s, tr�s prochainement, sur le traitement de votre candidature";
										}
										else{
											echo "Ce poste a �t� cl�tur� sur la Bourse � Emploi";
										}
									}
									elseif($nbCandidature>0){
										$rowCandidature=mysqli_fetch_array($resultCandidature);
										if($_SESSION["Langue"]=="FR"){echo "Vous avez postul� � cette annonce le ".AfficheDateJJ_MM_AAAA($rowCandidature['DateCreation'])."&nbsp;&nbsp;&nbsp;<br>" ;}
										else{echo "You applied for this ad on ".AfficheDateJJ_MM_AAAA($rowCandidature['DateCreation'])."&nbsp;&nbsp;&nbsp;<br>" ;}
										$dateButoir=date("Y-m-d",strtotime($row['DateButoire']." +22 day"));
										$JourdateButoir=date("w",strtotime($row['DateButoire']." +22 day"));
										if($JourdateButoir==6){$dateButoir=date("Y-m-d",strtotime($row['DateButoire']." +24 day"));}
										if($JourdateButoir==0){$dateButoir=date("Y-m-d",strtotime($row['DateButoire']." +23 day"));}
										if(date('Y-m-d')<=$dateButoir){
										?>
											<input class="Bouton" style="font-size:14px;" type="button" value="<?php if($_SESSION["Langue"]=="FR"){echo "Modifier";}else{echo "Edit";} ?>" onclick="ModifPostule(<?php echo $row['Id'] ;?>,<?php echo $rowCandidature['Id'] ;?>);" />
										<?php
										}
										else{echo "Ce poste a �t� cl�tur� sur la Bourse � Emploi, nous vous tiendrons inform�s, tr�s prochainement, sur le traitement de votre candidature";}
									}
								}
								?>
							</td>
						</tr>
						<tr height="50">
							<td class="Libelle" valign="align" bgcolor="#2e5496" style="color:#ffffff;border:1px solid black;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Descriptif du poste d�taill�";}else{echo "Detailed job description";} ?></td>
							<td colspan="8" class="Libelle" style="border:1px solid black;">
								<?php echo nl2br(stripslashes($row['DescriptifPoste'])); ?>
							</td>
						</tr>
						<tr height="50">
							<td class="Libelle" valign="align" bgcolor="#2e5496" style="color:#ffffff;border:1px solid black;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Savoir faire :<br><br>Qualit�s professionnelles<br><br>Polyvalence m�tier<br><br>Comp�tences techniques<br><br>Comp�tences manag�riales";}else{echo "Know-how :<br><br>Professional skills<br><br>-Experience<br><br>Business versatility<br><br>Technical skills<br><br>Managerial skills";} ?></td>
							<td colspan="8" class="Libelle" style="border:1px solid black;">
								<?php echo nl2br(stripslashes($row['SavoirFaire'])); ?>
							</td>
						</tr>
						<tr height="50">
							<td class="Libelle" valign="align" bgcolor="#2e5496" style="color:#ffffff;border:1px solid black;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Pr�requis :";}else{echo "Prerequisites";} ?></td>
							<td colspan="8" class="Libelle" style="border:1px solid black;">
								<table width="100%">
									<?php
										$req="SELECT talentboost_prerequis.Libelle FROM talentboost_prerequis LEFT JOIN talentboost_annonce_prerequis ON talentboost_annonce_prerequis.Id_Prerequis=talentboost_prerequis.Id WHERE Id_Annonce=".$row['Id']." ORDER BY  talentboost_prerequis.Libelle ";
										$result=mysqli_query($bdd,$req);
										$nbenreg=mysqli_num_rows($result);
										if($nbenreg>0)
										{
											while($rowSE=mysqli_fetch_array($result))
											{
												echo "<tr><td>";
												echo "- ".stripslashes($rowSE['Libelle'])." ";
												echo "</td></tr>";
											}
										}
										if($row['Prerequis']<>""){
											echo "<tr><td>";
												echo nl2br(stripslashes($row['Prerequis']));
											echo "</td></tr>";
										}
									?>
								</table>
							</td>
						</tr>
						<tr height="50">
							<td class="Libelle" valign="align" bgcolor="#2e5496" style="color:#ffffff;border:1px solid black;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Savoir �tre";}else{echo "know how to be";} ?></td>
							<td colspan="8" class="Libelle" style="border:1px solid black;">
								<table width="100%">
									<?php
										$req="SELECT talentboost_savoiretre.Libelle FROM talentboost_annonce_savoiretre LEFT JOIN talentboost_savoiretre ON talentboost_annonce_savoiretre.Id_SavoirEtre=talentboost_savoiretre.Id WHERE Id_Annonce=".$row['Id']." ORDER BY  talentboost_savoiretre.Libelle ";
										$result=mysqli_query($bdd,$req);
										$nbenreg=mysqli_num_rows($result);
										if($nbenreg>0)
										{
											while($rowSE=mysqli_fetch_array($result))
											{
												echo "<tr><td>";
												echo "- ".stripslashes($rowSE['Libelle'])." ";
												echo "</td></tr>";
											}
										}
										if($row['SavoirEtre']<>""){
											echo "<tr><td>";
												echo nl2br(stripslashes($row['SavoirEtre']));
											echo "</td></tr>";
										}
									?>
								</table>
							</td>
						</tr>
						<tr height="50">
							
							<td  class="Libelle" valign="align" bgcolor="#2e5496" style="color:#ffffff;border:1px solid black;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Dipl�mes :";}else{echo "Diplomas :";} ?></td>
							<td colspan="8" class="Libelle" style="border:1px solid black;">
								<?php echo nl2br(stripslashes($row['Diplome'])); ?>
							</td>
						</tr>
						<tr height="30">
							<td class="Libelle" bgcolor="#2e5496" style="color:#ffffff;border:1px solid black;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Langues ";}else{echo "Languages ";}?> : </td>
							<td colspan="8" class="Libelle" style="border:1px solid black;">
								&nbsp;&nbsp;<?php echo stripslashes($row['Langue']); ?>
							</td>
						</tr>
						<tr height="30">
							<td class="Libelle" bgcolor="#2e5496" style="color:#ffffff;border:1px solid black;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Date de la demande du besoin ";}else{echo "Date of requirement request ";}?> : </td>
							<td class="Libelle" style="border:1px solid black;">&nbsp;<?php echo AfficheDateJJ_MM_AAAA($row['DateRecrutement']) ?></td>
							<td class="Libelle" colspan="2" bgcolor="#cad8ee" style="color:#2c538b;border:1px solid black;display:none;">
								&nbsp;<?php 
								if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH)) || DroitsFormationPlateforme(array($IdPosteAssistantRH.",".$IdPosteResponsableRH)) || DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
									if($LangueAffichage=="FR"){echo "Nom du demandeur ";}else{echo "Name of requester ";}
								}
								?>
							</td>
							<td class="Libelle" style="border:1px solid black;display:none;">
								<?php 
								if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH)) || DroitsFormationPlateforme(array($IdPosteResponsablePlateforme)) || DroitsFormationPlateforme(array($IdPosteAssistantRH.",".$IdPosteResponsableRH))){
								?>
								&nbsp;<?php echo stripslashes($row['Demandeur']); ?>
								<?php 
								}
								?>
							</td>
							<td class="Libelle" bgcolor="#cad8ee" style="color:#2c538b;border:1px solid black;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Ref annonce ";}else{echo "Ref ad ";}?></td>
							<td class="Libelle" colspan="3" style="border:1px solid black;">
								&nbsp;<?php echo stripslashes($row['Ref']); ?>
							</td>
							<td class="Libelle" bgcolor="#cad8ee" style="color:#2c538b;border:1px solid black;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Personne � contacter ";}else{echo "Contact person ";}?></td>
							<td class="Libelle" colspan="2" style="border:1px solid black;">
								&nbsp;<?php echo stripslashes($row['PersonneAContacter']); ?>
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
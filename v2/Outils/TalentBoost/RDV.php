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
	<script type="text/javascript">
		$(document).ready(function () {
			$('#heureRDV').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: false,
				showMeridian: false,
				defaultTime: false
			});
			Mask.newMask({ 
				$el: $('#heureRDV'), 
				mask: 'HH:mm' 
			});
		});
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

$LangueAffichage=$_SESSION['Langue'];

if($_POST){
	if(isset($_POST['btnModifier'])){
		$requete="UPDATE talentboost_candidature 
			SET ReponseSalarie=".$_POST['reponseSalarie'].",
			Commentaire='".addslashes($_POST['commentaire'])."'
			WHERE Id=".$_POST['Id_Candidat'];
			echo $requete;
		$result=mysqli_query($bdd,$requete);
		
		echo "<script>window.opener.location='Candidatures.php?Mode=M&Id=".$_POST['Id']."';</script>";
		echo "<script>window.close();</script>";
	}
}
else{
if($_SESSION["Langue"]=="FR"){
	$reqSuite="IF(EtatRecrutement<>0,'OFFRE',IF(EtatApprobation=1 && OuvertureAutresPlateformes=0,'OFFRE','BESOIN')) AS Etat, 
		IF(EtatValidation=0,'',
			IF(EtatValidation=-1,'',
				IF(EtatValidation=1 && EtatApprobation=0,'',
					IF(EtatValidation=1 && EtatApprobation=-1,'',
							IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=0,IF(EtatPoste=0,'Poste ouvert',IF(EtatPoste=1,'Poste pourvu',IF(EtatPoste=2,'Poste non pourvu',IF(EtatPoste=3,'Poste pourvu partiellement',IF(EtatPoste=4,'Demande clôturée','Poste annulé'))))),
							  IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=1,'',
								IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'',IF(EtatPoste=0,'Poste ouvert',IF(EtatPoste=1,'Poste pourvu',IF(EtatPoste=2,'Poste non pourvu',IF(EtatPoste=3,'Poste pourvu partiellement',IF(EtatPoste=4,'Demande clôturée','Poste annulé'))))))
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
							IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=0,IF(EtatPoste=0,'Open post',IF(EtatPoste=1,'Position filled',IF(EtatPoste=2,'Position not filled',IF(EtatPoste=3,'Position partially filled',IF(EtatPoste=4,'Request closed','Position canceled'))))),
								IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=1,'',
									IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'',IF(EtatPoste=0,'Open post',IF(EtatPoste=1,'Position filled',IF(EtatPoste=2,'Position not filled',IF(EtatPoste=3,'Position partially filled',IF(EtatPoste=4,'Request closed','Position canceled'))))))
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
			DateBesoin,Duree,PosteDefinitif,Id_Domaine,Id_TypeHoraire,
			(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
			(SELECT Libelle FROM talentboost_domaine WHERE Id=Id_Domaine) AS Domaine,Langue,
			(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
			Id_Plateforme,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,EtatValidation
	FROM talentboost_annonce
	WHERE talentboost_annonce.Id=".$_GET['Id'] ;
$result=mysqli_query($bdd,$requete);
$row=mysqli_fetch_array($result);


$req="SELECT Id,Id_Plateforme,Id_Prestation,
(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
(SELECT Nom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Nom,
(SELECT Prenom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Prenom,
(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,ReponseSalarie,
(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
DateCreation, LEFT(HeureCreation,8)	AS HeureCreation,Tel,Mail,MailPro,Responsable,Motivation,CV,CompetencesSpecifiques,PosteOccupe,
CompetencesAcquises,Experiences,Diplomes,Langue1,NiveauLangue1,Langue2,NiveauLangue2,Langue3,NiveauLangue3,
DateRDV,LEFT(HeureRDV,5) AS HeureRDV,Commentaire,Priorite
FROM talentboost_candidature 
WHERE Id=".$_GET['Id_Candidat']." 
ORDER BY DateCreation, HeureCreation ";
$result=mysqli_query($bdd,$req);
$rowCandidat=mysqli_fetch_array($result);

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

<form id="formulaire" class="test"  action="RDV.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
	<input type="hidden" name="Id_Candidat" id="Id_Candidat" value="<?php echo $_GET['Id_Candidat']; ?>" />
	<input type="hidden" name="Ref" id="Ref" value="<?php echo $row['Ref']; ?>" />
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
							<?php echo stripslashes($row['Ref']); ?>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" rowspan="3" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Informations salarié";}else{echo "Employee information";} ?></td>
							<td width="10%" class="Libelle" bgcolor="#b9c5c9" >&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Nom";}else{echo "Last name";} ?></td>
							<td width="10%" class="Libelle" bgcolor="#b9c5c9">&nbsp;<?php echo $rowCandidat['Nom']; ?></td>
							<td width="10%" class="Libelle" bgcolor="#b9c5c9" >&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prénom";}else{echo "First name";} ?></td>
							<td width="10%" class="Libelle" bgcolor="#b9c5c9">&nbsp;<?php echo $rowCandidat['Prenom']; ?></td>
							<td class="Libelle" width="8%" bgcolor="#cad8ee" style="color:#2c538b;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Email perso.";}else{echo "Personal email";}?> : </td>
							<td width="10%">
								&nbsp;
								<?php echo stripslashes($rowCandidat['Mail']);
								?>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle" width="8%" bgcolor="#cad8ee" style="color:#2c538b;">&nbsp;<?php if($LangueAffichage=="FR"){echo "N° téléphone";}else{echo "Telephone number";}?> : </td>
							<td width="10%">
								&nbsp;
								<?php echo stripslashes($rowCandidat['Tel']);
								?>
							</td>
							<td class="Libelle" width="10%" bgcolor="#cad8ee" style="color:#2c538b;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Poste occupé <br>actuellement";}else{echo "Position currently <br>occupied";}?> : </td>
							<td width="10%">
								&nbsp;
								<?php echo stripslashes($rowCandidat['PosteOccupe']);
								?>
							</td>
							<td class="Libelle" width="8%" bgcolor="#cad8ee" style="color:#2c538b;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Email pro.";}else{echo "Professional email";}?> : </td>
							<td width="10%">
								&nbsp;
								<?php echo stripslashes($rowCandidat['MailPro']);
								?>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Sélection";}else{echo "Selection";} ?></td>
							<td width="10%" class="Libelle" bgcolor="#cad8ee"  style="color:#2c538b;">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Réponse salarié";}else{echo "Employee response";} ?></td>
							<td>
								<select id="reponseSalarie" style="width:100px;" name="reponseSalarie">
									<option value="0" <?php if($rowCandidat['Priorite']==0){echo "selected";} ?>></option>
									<option value="1" <?php if($rowCandidat['Priorite']==1){echo "selected";} ?>>Accepté</option>
									<option value="-1" <?php if($rowCandidat['Priorite']==-1){echo "selected";} ?>>Refusé</option>
								</select>
							</td>
							<td width="10%" class="Libelle" bgcolor="#cad8ee" style="color:#2c538b;" valign="center">&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?></td>
							<td width="30%" colspan="8">
								&nbsp;
								<textarea name="commentaire" id="commentaire" cols="60" rows="5" style="resize:none;"><?php echo stripslashes($rowCandidat['Commentaire']); ?></textarea>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="9" align="center">
								<div id="Ajouter">
								</div>
								<input class="Bouton" type="submit" id="btnModifier" name="btnModifier" value="Enregistrer">
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
}
?>
</body>
</html>
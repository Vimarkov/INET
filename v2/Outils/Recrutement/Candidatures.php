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
			window.opener.location="Annonces.php";
			window.close();
		}
		function VisualiserCandidature(Id,Id_Candidat)
		{var w=window.open("Postuler.php?Mode=V&Id="+Id+"&Id_Candidat="+Id_Candidat,"PagePostuler","status=no,scrollbars=1,menubar=no,width=1100,height=550");
		w.focus();
		}
		function RDV(Id,Id_Candidat)
		{var w=window.open("RDV.php?Id="+Id+"&Id_Candidat="+Id_Candidat,"PageRDV","status=no,scrollbars=1,menubar=no,width=800,height=400");
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

if($_GET){
	$Id=$_GET['Id'];
}
else{
	$Id=$_POST['Id'];
	
	$req="SELECT Id			
	FROM recrut_candidature 
	WHERE Id_Annonce=".$Id." ";
	$result=mysqli_query($bdd,$req);
	$nbResulta=mysqli_num_rows($result);
	if($nbResulta>0){
		while($row=mysqli_fetch_array($result))
		{
			if(isset($_POST['CandidatRetenu_'.$row['Id']])){$req="UPDATE recrut_candidature SET CandidatRetenu=1 WHERE Id=".$row['Id']." ";}
			else{$req="UPDATE recrut_candidature SET CandidatRetenu=0 WHERE Id=".$row['Id']." ";}
			$resulM=mysqli_query($bdd,$req);
		}
	}
			
	if(isset($_POST['btnEnregistrer'])){
		$req="UPDATE recrut_annonce SET EtatPoste=".$_POST['statutPoste']." WHERE Id=".$Id." ";
		$resulM=mysqli_query($bdd,$req);
		
		echo "<script>window.opener.location='Annonces.php';</script>";
	}
}
if($_SESSION["Langue"]=="FR"){
	$reqSuite="IF(EtatRecrutement<>0,'OFFRE','BESOIN') AS Etat, 
		IF(EtatValidation=0,'En attente validation',
			IF(EtatValidation=-1,'Refusé',
				IF(EtatValidation=1 && EtatApprobation=0,'En attente approbation',
					IF(EtatValidation=1 && EtatApprobation=-1,'Non approuvé',
							IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0,'En attente validation offre',
								IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'Refusé','Passage en annonce')
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
							IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0,'Pending validation offer',
								IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'Refuse','Switch to announcement')
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
			DescriptifPoste,SavoirFaire,SavoirEtre,Prerequis,DateRecrutement,
			(SELECT Libelle FROM recrut_typehoraire WHERE Id=Id_TypeHoraire) AS TypeHoraire,
			DateBesoin,Duree,PosteDefinitif,Id_Prestation,Id_Domaine,Id_TypeHoraire,Horaire,
			(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
			(SELECT Libelle FROM recrut_domaine WHERE Id=Id_Domaine) AS Domaine,Langue,
			(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
			(SELECT (SELECT Document FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS DocPlateforme,
			(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
			FicheMetier AS DocMetier,EtatPoste,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,EtatValidation
	FROM recrut_annonce
	WHERE recrut_annonce.Id=".$Id ;
$result=mysqli_query($bdd,$requete);
$row2=mysqli_fetch_array($result);

$Etat="";
$CouleurEtat="#ffffff";
$NumRefus=2;
$EstRefuse=0;

$typedate="date";
$modifiable="";
$selection="";

$DirFichier=$CheminRecrutement;

if($row2['EtatRecrutement']<>0){$Couleur="#ecf943";}
else{$Couleur="#6c94d0";}
					

$typedateRH="text";
$modifiableRecrut="readonly='readonly'";
?>

<form id="formulaire" class="test" action="Candidatures.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $Id; ?>" />
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td align="center" style="border:1px solid black;" width="15%">
								<img width="150px" src="../../Images/Logos/Logo_AAA_FR.png" /> 
							</td>
							<td colspan="8"  width="85%" bgcolor="#2e5496" style="color:#ffffff;font-size:16px;border:1px solid black;" align="center" class="Libelle">
							<?php 
								if($_SESSION["Langue"]=="FR"){echo "CANDIDATURES : ";}else{echo "APPLICATIONS : ";}
								echo $row2['Ref'];
							?>
							</td>
						</tr>
						<tr>
							<td height="10"></td>
						<tr>
						<tr>
							<td colspan="2" align="center">
								<table class="TableCompetences" align="center" width="95%">
									<tr>
										<td class="EnTeteTableauCompetences" width="5%"  align="center" style="text-decoration:none;color:#000000;font-weight:bold;text-align:center;"><?php if($_SESSION["Langue"]=="FR"){echo "Candidat retenu";}else{echo "Successful candidate";} ?></td>
										<td class="EnTeteTableauCompetences" width="12%"  style="text-decoration:none;color:#000000;font-weight:bold;text-align:center;"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
										<td class="EnTeteTableauCompetences" width="10%"  style="text-decoration:none;color:#000000;font-weight:bold;text-align:center;"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";} ?></td>
										<td class="EnTeteTableauCompetences" width="10%"  style="text-decoration:none;color:#000000;font-weight:bold;text-align:center;"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
										<td class="EnTeteTableauCompetences" width="10%"  style="text-decoration:none;color:#000000;font-weight:bold;text-align:center;"><?php if($_SESSION["Langue"]=="FR"){echo "Date création";}else{echo "Creation Date";} ?></td>
										<td class="EnTeteTableauCompetences" width="10%"  style="text-decoration:none;color:#000000;font-weight:bold;text-align:center;"><?php if($_SESSION["Langue"]=="FR"){echo "Heure création";}else{echo "Creation time";} ?></td>
										<td class="EnTeteTableauCompetences" width="5%"  style="text-decoration:none;color:#000000;font-weight:bold;text-align:center;"><?php if($_SESSION["Langue"]=="FR"){echo "Réponse salarié";}else{echo "Employee response";} ?></td>
										<td class="EnTeteTableauCompetences" width="20%"  style="text-decoration:none;color:#000000;font-weight:bold;text-align:center;"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire";}else{echo "Comment";} ?></td>
										<td class="EnTeteTableauCompetences" width="5%"  style="text-decoration:none;color:#000000;font-weight:bold;text-align:center;"></td>
										<td class="EnTeteTableauCompetences" width="5%"  style="text-decoration:none;color:#000000;font-weight:bold;text-align:center;"></td>
										<td class="EnTeteTableauCompetences" width="2%"  style="text-decoration:none;color:#000000;font-weight:bold;text-align:center;">
										</td>
									</tr>
									<?php 
									$req="SELECT Id,Id_Annonce,CandidatRetenu,Id_Personne,
									(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
									Id_Plateforme,ReponseSalarie,
									(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
									(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
									DateCreation, LEFT(HeureCreation,8)	AS HeureCreation,Suppr,DateSuppr,DateMAJ,
									DateRDV,LEFT(HeureRDV,5) AS HeureRDV, IF(Priorite=0,'',Priorite) AS Priorite,Commentaire
									FROM recrut_candidature 
									WHERE Id_Annonce=".$Id." 
									ORDER BY DateCreation, HeureCreation ";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if($nbResulta>0){
										$couleur="#FFFFFF";
										while($row=mysqli_fetch_array($result))
										{
											if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
											else{$couleur="#FFFFFF";}
											
											$laCouleur=$couleur;
											if($row['Suppr']==1){$laCouleur="#7d8185";}
											
											if($row['CandidatRetenu']==1){$laCouleur="#69bf3c";}
											$heureRDV="";
											if($row['HeureRDV']<>"00:00"){$heureRDV=$row['HeureRDV'];}
											
											$reponseSalarie="";
											if($row['ReponseSalarie']==1){$reponseSalarie="Accepté";}
											elseif($row['ReponseSalarie']==-1){$reponseSalarie="Refusé";}
											elseif($row['ReponseSalarie']==2){$reponseSalarie="Repêché";}
											?>
												<tr bgcolor="<?php echo $laCouleur;?>">
													<td align="center">
														<?php 
														if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))  || DroitsFormation1Plateforme($row2['Id_Plateforme'],array($IdPosteAssistantRH,$IdPosteResponsableRH))){
														?>
														<input type="checkbox" onclick="submit()" class="CandidatRetenu" <?php if($row['CandidatRetenu']==1){echo "checked";} ?>  name="CandidatRetenu_<?php echo $row['Id']; ?>" value="<?php echo $row['Id']; ?>">
														<?php 
														}
														else{
															if($row['CandidatRetenu']==1){echo "X";}
														}
														?>
													</td>
													<td align="center"><?php echo stripslashes($row['Personne']);?></td>
													<td align="center"><?php echo stripslashes($row['Plateforme']);?></td>
													<td align="center"><?php echo stripslashes($row['Prestation']);?></td>
													<td align="center"><?php echo AfficheDateJJ_MM_AAAA($row['DateCreation']);?></td>
													<td align="center"><?php echo stripslashes($row['HeureCreation']);?></td>
													<td align="center"><?php echo stripslashes($reponseSalarie);?></td>
													<td align="center"><?php echo stripslashes($row['Commentaire']);?></td>
													<td align="center">
													<?php if($row['Suppr']==0){?>
													<a style="color:#3e65fa;" href="javascript:VisualiserCandidature(<?php echo $row['Id_Annonce']; ?>,<?php echo $row['Id']; ?>)"><img width="15px" src="../../Images/doc.png" /></a>
													<?php }
													else{
														if($_SESSION["Langue"]=="FR"){echo "Supprimée le ".AfficheDateJJ_MM_AAAA($row['DateSuppr']);}
														else{echo "Deleted on ".AfficheDateJJ_MM_AAAA($row['DateSuppr']);}
													}
													?>
													</td>
													<td  align="center">
													<?php if($row['DateMAJ']>'0001-01-01'){
														if($_SESSION["Langue"]=="FR"){echo "Modifiée le ".AfficheDateJJ_MM_AAAA($row['DateMAJ']);}
														else{echo "Modified on ".AfficheDateJJ_MM_AAAA($row['DateMAJ']);}
													}
													?>
													</td>
													<td  align="center">
													<?php if($row['Suppr']==0){
													if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH)) || DroitsFormation1Plateforme($row2['Id_Plateforme'],array($IdPosteAssistantRH,$IdPosteResponsableRH,$IdPosteResponsablePlateforme))){	
														?>
													<a style="color:#3e65fa;" href="javascript:RDV(<?php echo $row['Id_Annonce']; ?>,<?php echo $row['Id']; ?>)"><img width="15px" src="../../Images/RH/Planning.png" /></a>
													<?php 
													}
													}
													?>
													</td>
												</tr>
											<?php
										}
									}
									?>
								</table>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="8" align="center">
								<table width="30%" align="center">
									<tr>
										<td width="8%" class="Libelle" bgcolor="#2e5496" style="color:#ffffff;">&nbsp;<?php if($LangueAffichage=="FR"){echo "Statut";}else{echo "Status";}?> : </td>
										<td width="18%">
											&nbsp;<select name="statutPoste" id="statutPoste">
												<option value="0" <?php if($row2['EtatPoste']==0){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste ouvert";}else{echo "Open post";}?></option>
												<option value="1" <?php if($row2['EtatPoste']==1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste pourvu";}else{echo "Post filled";}?></option>
												<option value="2" <?php if($row2['EtatPoste']==2){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste non pourvu";}else{echo "Closed post";}?></option>
												<option value="3" <?php if($row2['EtatPoste']==3){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste pourvu partiellement";}else{echo "Position partially filled";}?></option>
												<option value="-1" <?php if($row2['EtatPoste']==-1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Poste annulé";}else{echo "Post canceled";}?></option>
											</select>
										</td>
										<td width="8%">
											<?php 
												if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH)) || DroitsFormation1Plateforme($row2['Id_Plateforme'],array($IdPosteAssistantRH,$IdPosteResponsableRH))){
											?>
												<input class="Bouton" type="submit" id="btnEnregistrer" name="btnEnregistrer" value="Modifier">
											<?php
												}
											?>
										</td>
									</tr>
								</table>
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
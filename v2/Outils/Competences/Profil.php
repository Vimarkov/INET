<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>	
	<title>Compétences - Profil</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script src="../JS/js/jquery-1.4.3.min.js"></script>	
	<script src="Profil.js"></script>
	<script>
		function OuvreFenetreCompetencesPresta(Id,Id_Prestation,Id_PrestaPers)
			{window.open("Individual_Competency_ListPrestation.php?Affiche=Id&Id_Personne="+Id+"&Id_Prestation="+Id_Prestation+"&Id_PrestaPers="+Id_PrestaPers,"CompetencyList2","status=no,menubar=no,scrollbars=yes,rezisable=yes,width=1010,height=600");}
		function OuvreFenetreCompetencesExportPresta(Id,Id_Prestation,Id_PrestaPers)
			{window.open("Individual_Competency_List_ExportPrestation.php?Affiche=Id&Type=Prestation&Id="+Id+"&Id_Prestation="+Id_Prestation+"&Id_PrestaPers="+Id_PrestaPers,"PageExport2","status=no,menubar=no,resizable=yes,scrollbars=yes,width=100,height=100");}
		function OuvreFenetreCompetencesPresta2(Id,Id_Prestation,Id_PrestaPers)
			{window.open("Individual_Competency_ListPrestation.php?Affiche=Nom&Id_Personne="+Id+"&Id_Prestation="+Id_Prestation+"&Id_PrestaPers="+Id_PrestaPers,"CompetencyList2","status=no,menubar=no,scrollbars=yes,rezisable=yes,width=1010,height=600");}
		function OuvreFenetreCompetencesExportPresta2(Id,Id_Prestation,Id_PrestaPers)
			{window.open("Individual_Competency_List_ExportPrestation.php?Affiche=Nom&Type=Prestation&Id="+Id+"&Id_Prestation="+Id_Prestation+"&Id_PrestaPers="+Id_PrestaPers,"PageExport2","status=no,menubar=no,resizable=yes,scrollbars=yes,width=100,height=100");}
	</script>
</head>
<body leftmargin="0" topmargin="0">

<?php
require("../Connexioni.php");
require("../Fonctions.php");
require_once("../Formation/Globales_Fonctions.php");
require_once("../Database_fonctions.php");

if($_SESSION['Id_Personne']>0){
/**
 * supprimer_BesoinEtBmetier
 *
 * Supprime les besoins en formation et les 'B' a partir du metier
 *
 * @param int $Id_Metier Identifiant du metier
 *
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function supprimer_BesoinEtBmetier($Id_Metier)
{
    //Suppression des besoins
    //$_GET['Id_Personne'] Identifiant de la personne
    
    //Récupération des formations
    $res = get_FormationsDeAssociationMetier($_POST['Id_Personne'], $Id_Metier);
    
    //Récuperation des prestations
    //$_GET['Id_Personne']
    $reqPrestas = "
        SELECT DISTINCT Id_Prestation, Id_Pole 
        FROM new_competences_personne_prestation 
        WHERE Id_Personne = ".$_POST['Id_Personne']."
		AND Date_Fin>='".date('Y-m-d')."'
		; ";
	
	//Liste des autres métiers de la personne 
	$reqMetier="SELECT Id_Metier FROM new_competences_personne_metier WHERE Id_Metier<>".$Id_Metier." AND Id_Personne=".$_POST['Id_Personne']." ";
	$resultMetier=getRessource($reqMetier);
	$nbMetier=mysqli_num_rows($resultMetier);
	
	$result=getRessource($reqPrestas);
    $nbenregResult=mysqli_num_rows($result);
    if($nbenregResult>0)
    {
		while($row = mysqli_fetch_array($res))
		{
			//Récupérer la ou les qualifications
			$IdFormation = $row['Id_Formation'];

			//Suppression des besoins
			mysqli_data_seek($result, 0); //renviens au début du recordset
			while($rowPresta = mysqli_fetch_array($result))
			{
				if($nbMetier==0)
				{
					$resBesoinsAffectes = Supprimer_BesoinsFormations($rowPresta['Id_Prestation'], $IdFormation,$rowPresta['Id_Pole'],$_POST['Id_Personne'], "Profil personne");
				}
				else{
					//Vérifier si cette formation n'est pas nécessaire pour un autre métier
					$reqNecessaire="SELECT Id_Metier 
						FROM form_prestation_metier_formation 
						WHERE Id_Metier IN (
							SELECT Id_Metier 
							FROM new_competences_personne_metier 
							WHERE Id_Metier<>".$Id_Metier." AND Id_Personne=".$_POST['Id_Personne']."
							)
						AND Id_Prestation=".$rowPresta['Id_Prestation']."
						AND Id_Pole=".$rowPresta['Id_Pole']."
						AND Id_Formation=".$IdFormation."
						AND Suppr=0
						 ";

					$resultNecessaire=getRessource($reqNecessaire);
					$nbNecessaire=mysqli_num_rows($resultNecessaire);
					if($nbNecessaire==0){
						$resBesoinsAffectes = Supprimer_BesoinsFormations($rowPresta['Id_Prestation'], $IdFormation,$rowPresta['Id_Pole'],$_POST['Id_Personne'], "Profil personne");
					}
				}
			}
		}
	}
	//Suppression des 'B' reliées à des besoins supprimés
	$req = "
		UPDATE new_competences_relation
		SET Suppr=1
		WHERE new_competences_relation.Suppr=0
		AND new_competences_relation.Id_Besoin>0
		AND Evaluation='B' 
		AND ((SELECT Traite FROM form_besoin WHERE Id=Id_Besoin LIMIT 1)<2 
			OR (SELECT Traite FROM form_besoin WHERE Id=Id_Besoin LIMIT 1)=5
		)
		AND Id_Besoin IN (
			SELECT form_besoin.Id FROM form_besoin 
			WHERE form_besoin.Suppr=1
		)";
	$result=getRessource($req);
}

/**
 * supprimer_BesoinEtBprestation
 * 
 * Supprime les besoins en formation et les 'B' a partir de la prestation
 * 
 * @param int $Id_Prestation Identifiant de la prestation
 * 
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function supprimer_BesoinEtBprestation($Id_Prestation,$Id_Pole)
{
    //Suppression des besoins
    //$_GET['Id_Personne'] Identifiant de la personne

    //Récupération de toutes les prestations similaires de la personne
    //(Actuelle ou futur)
    $req = "
      SELECT *
      FROM new_competences_personne_prestation
      WHERE
          Id_Personne = ".$_POST['Id_Personne']."
      AND Id_Prestation = ".$Id_Prestation."
	  AND Id_Pole = ".$Id_Pole."
      AND Date_Fin >= NOW()
    ;";
    $res = getRessource($req);
    $nbMemePrestaFutur = mysqli_num_rows($res);
    
    if($nbMemePrestaFutur > 1)
        return;
    
    //Récupération des formations
    $resu = get_FormationsDeBesoinsPersonne($_POST['Id_Personne'], $Id_Prestation);
	
    while($row = mysqli_fetch_array($resu))
    {
        $Idformation = $row['Id_Formation'];
		
        //Suppression des besoins
        $resBesoinsAffectes = Supprimer_BesoinsFormations($Id_Prestation, $Idformation,$Id_Pole,$_POST['Id_Personne'], "Profil");
    }
	
	//Suppression des 'B' reliées à des besoins supprimés
	$req = "
		UPDATE new_competences_relation
		SET Suppr=1
		WHERE new_competences_relation.Suppr=0
		AND new_competences_relation.Id_Besoin>0
		AND Evaluation='B' 
		AND ((SELECT Traite FROM form_besoin WHERE Id=Id_Besoin LIMIT 1)<2 
			OR (SELECT Traite FROM form_besoin WHERE Id=Id_Besoin LIMIT 1)=5
		)
		AND Id_Besoin IN (
			SELECT form_besoin.Id FROM form_besoin 
			WHERE form_besoin.Suppr=1
		)";
	$result = getRessource($req);
}

if($_POST){$result=mysqli_query($bdd,"SELECT Nom, Prenom, NumBadge, Date_Naissance,Id, Matricule,CertifyingStaffNumber,CertifyingStaffPrecision FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=".$_POST['Id_Personne']);}
else{$result=mysqli_query($bdd,"SELECT Nom, Prenom, NumBadge, Date_Naissance,Id, Matricule,CertifyingStaffNumber,CertifyingStaffPrecision FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=".$_GET['Id_Personne']);}
$row=mysqli_fetch_array($result);
$Nom=$row['Nom'];
$Prenom=$row['Prenom'];
$CertifyingStaffNumber=$row['CertifyingStaffNumber'];
$CertifyingStaffPrecision=$row['CertifyingStaffPrecision'];

if($_GET){$MODE=$_GET['Mode'];}
else{if(isset($_POST['Mode'])){$MODE=$_POST['Mode'];}}

if($_POST)
//Mode suppression
{
	if(isset($_POST['Id_Plateforme'])){$result=mysqli_query($bdd,"DELETE FROM new_competences_personne_plateforme WHERE Id_Personne=".$_POST['Id_Personne']." AND Id_Plateforme=".$_POST['Id_Plateforme']);}
	elseif(isset($_POST['Id_Metier']))
	{
	    //Suppression du métier
	    $req = "SELECT Id_Metier FROM `new_competences_personne_metier` WHERE `Id` = ".$_POST['Id_Metier'].";";
	    $row = mysqli_fetch_array(getRessource($req));
	    supprimer_BesoinEtBmetier($row['Id_Metier']);
	    
	    $result=mysqli_query($bdd,"DELETE FROM new_competences_personne_metier WHERE Id=".$_POST['Id_Metier']);
	}
	elseif(isset($_POST['Id_Prestation']))
	{
		$Id=$_POST['Id_Prestation'];
		$req="SELECT Date_Debut, Date_Fin, Id_Personne, Id_Prestation, Id_Pole FROM new_competences_personne_prestation WHERE Id='".$Id."' ;";
		$resultSelect=mysqli_query($bdd,$req);
		$rowSelect=mysqli_fetch_array($resultSelect);
		
		//Suppression de la prestation
		supprimer_BesoinEtBprestation($rowSelect['Id_Prestation'],$rowSelect['Id_Pole']);

		$debut1 = $rowSelect['Date_Debut'];
		$debut2 = $rowSelect['Date_Fin'];
		
		if($debut1 <> 0)
		{
			$reqPersonne="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$rowSelect['Id_Personne'];
			$result=mysqli_query($bdd,$reqPersonne);
			$LignePersonne=mysqli_fetch_array($result);
			$reqPresta="SELECT Libelle FROM new_competences_prestation WHERE Id=".$rowSelect['Id_Prestation'];
			$result=mysqli_query($bdd,$reqPresta);
			$LignePrestation=mysqli_fetch_array($result);
			$question1="de ".$LignePersonne['Nom']." ".$LignePersonne['Prenom']."";
			$question2="pour la prestation ".$LignePrestation['Libelle']." ";
			$question3="du ".$debut1." au ".$debut2." ";
			$question4="";
			$tabDate = explode('-', $debut1);
			$tmpDebut1 = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
			$tabDate = explode('-', $debut2);
			$tmpDebut2 = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
			$tmpFin1 = 0;
			$tmpFin2 = 0;				
			echo "<script>DemandeSuppression('".$question1."','".$question2."','".$question3."','".$question4."','".$tmpDebut1."','".$tmpDebut2."','".$tmpFin1."','".$tmpFin2."','".$rowSelect['Id_Prestation']."','".$rowSelect['Id_Personne']."','0','".$rowSelect['Id_Pole']."')</script>";
		}
		$result=mysqli_query($bdd,"DELETE FROM new_competences_personne_prestation WHERE Id=".$_POST['Id_Prestation']);
		echo "<script>alert('Pensez à avertir les moyens généraux et le pôle informatique pour le transfert du matériel !')</script>";
	}
	elseif(isset($_POST['Id_Formation'])){$result=mysqli_query($bdd,"DELETE FROM new_competences_personne_formation WHERE Id=".$_POST['Id_Formation']);}
	elseif(isset($_POST['Id_Diplome'])){$result=mysqli_query($bdd,"DELETE FROM new_competences_personne_diplome WHERE Id=".$_POST['Id_Diplome']);}
	elseif(isset($_POST['Id_Relation'])){$result=mysqli_query($bdd,"UPDATE new_competences_relation SET Suppr=1,Id_Personne_MAJ_Manuelle=".$IdPersonneConnectee.",Date_MAJ_Manuelle='".date('Y-m-d')."',ModifManuelle=1 WHERE Id=".$_POST['Id_Relation']);}
	elseif(isset($_POST['Id_FicheHSE'])){$result=mysqli_query($bdd,"DELETE FROM new_competences_personne_fichehse WHERE Id=".$_POST['Id_FicheHSE']);}
	elseif(isset($_POST['Id_EIA'])){$result=mysqli_query($bdd,"DELETE FROM new_competences_personne_rh_eia WHERE Id=".$_POST['Id_EIA']);}
	elseif(isset($_POST['Id_DateButoir'])){$result=mysqli_query($bdd,"DELETE FROM epe_personne_datebutoir WHERE Id=".$_POST['Id_DateButoir']);}
	elseif(isset($_POST['Id_Stamp'])){$result=mysqli_query($bdd,"DELETE FROM new_competences_personne_stamp WHERE Id=".$_POST['Id_Stamp']);}
	elseif(isset($_POST['valeurCochee']))
	{
	    //$req="UPDATE new_competences_relation SET Suppr=1,Id_Personne_MAJ_Manuelle=".$IdPersonneConnectee.",Date_MAJ_Manuelle='".date('Y-m-d')."',ModifManuelle=1  WHERE ";
		$tabRelation = explode(";",$_POST['valeurCochee']);
		foreach($tabRelation as $relation)
		{
			if($relation<>"")
			{
			    $req="UPDATE new_competences_relation SET Suppr=1,Id_Personne_MAJ_Manuelle=".$IdPersonneConnectee.",Date_MAJ_Manuelle='".date('Y-m-d')."',ModifManuelle=1  WHERE Id=".$relation." ";
				$result=mysqli_query($bdd,$req);
			}
		}
	}
	
	$Droits="Aucun";
	if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))	
	)
	{
		$Droits="Administrateur";
	}
	elseif(DroitsPlateforme(array($IdPosteResponsableQualite,$IdPosteResponsableRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH,$IdPosteAssistantRH,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))
	|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
	)
	{
		$Droits="Ecriture";
	}
	$DroitsModifPrestation=EstPresent_HierarchiePrestation();
	
	//Plateforme
	$Plateforme_Identique=false;
	$requete_plateforme="SELECT DISTINCT new_competences_plateforme.Libelle, new_competences_plateforme.Id FROM new_competences_plateforme, new_competences_personne_plateforme";
	$requete_plateforme.=" WHERE new_competences_personne_plateforme.Id_Personne=".$_POST['Id_Personne']." AND new_competences_plateforme.Id=new_competences_personne_plateforme.Id_Plateforme ";
	$requete_plateforme.=" ORDER BY new_competences_plateforme.Libelle ASC";
	$result_plateforme=mysqli_query($bdd,$requete_plateforme);
	$nbenreg_plateforme=mysqli_num_rows($result_plateforme);
	if($nbenreg_plateforme>0)
	{
		while($row_plateforme=mysqli_fetch_array($result_plateforme))
		{
			foreach($_SESSION['Id_Plateformes'] as &$value){if($row_plateforme['Id']==$value){$Plateforme_Identique=true;}}
		}
	}
	
	if(($Droits=="Ecriture" && $Plateforme_Identique) || $Droits=="Administrateur")
	{
		echo "<script>window.location.replace('Profil.php?Mode=Modif&Id_Personne=".$_POST['Id_Personne']."');</script>";
	}
	else
	{
		if($DroitsModifPrestation==false){echo "<script>window.location.replace('Profil.php?Mode=Lecture&Id_Personne=".$_POST['Id_Personne']."');</script>";}
		else{echo "<script>window.location.replace('Profil.php?Mode=ModifPresta&Id_Personne=".$_POST['Id_Personne']."');</script>";}
	}
}
else
//Mode ajout ou modification
{
?>
<table style="width:100%; align:center;">
	<tr>
		<td>
			<form id="formulaire" method="POST" action="Profil.php">
    			<table class="GeneralPage" style="width:100%;">
    				<tr>
    					<td class="TitrePage">
    						<?php
    							if($LangueAffichage=="FR"){echo "Profil de ";}
    							else{echo "Profile of ";}
    							echo $row['Prenom']." ".$row['Nom'];
								echo " (AAA-".$row['Id'].")";
    						?>
    					</td>
						<td class="TitrePage" width="35%" align="right">
    					<?php
    					if(
						DroitsFormationPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteResponsablePlateforme,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH))
						|| DroitsFormationPrestation(array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit))
						)
						{
    					    echo "<a class='Modif' href='javascript:OuvreFenetre(\"Ajout_Profil_NG.php?Mode=Ajout&Id_Personne=".$_GET['Id_Personne']."\",\"50\",\"250\");'><img src='../../Images/Modif.gif' border='0' alt='Modifier' title='Modify'></a>";
    					}
    					?>
    					ST / NG : <?php echo $row['Matricule'];?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</td>
    					<td class="TitrePage" width="35%" align="right">
    					<?php
    					if(
						DroitsFormationPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteResponsablePlateforme,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH))
						|| DroitsFormationPrestation(array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit))
						)
						{
    					    echo "<a class='Modif' href='javascript:OuvreFenetre(\"Ajout_Profil_EtatCivil.php?Mode=Ajout&Id_Personne=".$_GET['Id_Personne']."\",\"50\",\"350\");'><img src='../../Images/Modif.gif' border='0' alt='Modifier' title='Modify'></a>";
    					}
    					?>
    					Badge Number : <?php echo $row['NumBadge'];?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</td>
    				</tr>
    			</table>
			</form>
		</td>
	</tr>
	
	<tr>
		<td>
			<table>
				<tr valign="top">
					<td>
						<table>
							<tr><td>
								<table style="width:150px;" class="ProfilCompetence">
									<!-- PLATEFORME -->
									<!--############-->
									<tr class="TitreSousPageCompetences">
										<td colspan="2"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?></td>
										<?php 
											if(
											DroitsFormationPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH))
											|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
											)
											{
										?>
										<td width="15"><a class="Modif" href="javascript:OuvreFenetre('Ajout_Profil_Plateforme.php?Mode=Ajout&Id_Personne=<?php echo $_GET['Id_Personne'];?>','250','500');"><img src="../../Images/Ajout.gif" border="0" alt="Ajouter" title="Ajouter"></a></td>
										<?php }?>
									</tr>
									<?php
									$result=mysqli_query($bdd,"SELECT new_competences_plateforme.* FROM new_competences_personne_plateforme, new_competences_plateforme WHERE new_competences_personne_plateforme.Id_Plateforme=new_competences_plateforme.Id AND new_competences_personne_plateforme.Id_Personne=".$_GET['Id_Personne']." ORDER BY new_competences_plateforme.Libelle ASC");
									$nbenreg=mysqli_num_rows($result);
									$Couleur="#EEEEEE";
									if($nbenreg>0)
									{
										while($row=mysqli_fetch_array($result))
										{
											$Plateforme=$row['Libelle'];
											if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
											else{$Couleur="#EEEEEE";}
									?>
									<tr bgcolor="<?php echo $Couleur;?>">
										<td width="135" class="PetitCompetence"><?php echo $Plateforme; ?></td>
										<?php 
										if(
										DroitsFormationPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH))
										|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
										)
										{
										?>
										<td width="15">
											<form id="formulaire" method="POST" action="Profil.php">
            									<input type="hidden" name="Id_Plateforme" value="<?php echo $row['Id']; ?>">
            									<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
    											<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){return true;}else{return false;}">
    										</form>
    									</td>
										<td width="15"><a class="Modif" href="javascript:OuvreFenetre('Ajout_Profil_Plateforme.php?Id_Plateforme=<?php echo $row['Id'];?>&Mode=Modif&Id_Personne=<?php echo $_GET['Id_Personne'];?>','350','550');"><img src="../../Images/Modif.gif" border="0" alt="Modifier" title="Modifier"></a></td>
										<?php }?>
									</tr>
									<?php
										}
									}
									?>
								</table>
							</td>
							</tr>
							<tr><td>
								<table style="width:400px;" class="ProfilCompetence">
									<!-- METIER -->
									<!--########-->
									<tr class="TitreSousPageCompetences">
										<?php 
										if(
										DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))
										|| DroitsFormationPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH))
										|| DroitsFormationPlateformes(array(12,16,18,20,22,26,30),array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteResponsablePlateforme))
										)
										{
										?>
										<td><?php if($LangueAffichage=="FR"){echo "Métier/Fonction";}else{echo "Job/Function";}?></td>
										<td width="15"><a class="Modif" href="javascript:OuvreFenetre('Ajout_Profil_Metier.php?Mode=Ajout&Id_Personne=<?php echo $_GET['Id_Personne'];?>','150','900');"><img src="../../Images/Ajout.gif" border="0" alt="Ajouter" title="Ajouter"></a></td>
										<?php }else{ ?>
										<td><?php if($LangueAffichage=="FR"){echo "Métier/Fonction";}else{echo "Job/Function";}?></td>
										<?php } ?>
									</tr>
									<?php
									$requete="
                                        SELECT
                                            new_competences_personne_metier.Id,
                                            new_competences_metier.Libelle,
                                            new_competences_personne_metier.Futur,
                                            new_competences_metier.Fiche
                                        FROM
                                            new_competences_personne_metier,
                                            new_competences_metier
                                        WHERE
                                            new_competences_personne_metier.Id_Metier=new_competences_metier.Id
                                            AND new_competences_personne_metier.Id_Personne=".$_GET['Id_Personne']."
                                        ORDER BY
                                            new_competences_personne_metier.Id DESC";
									$result=mysqli_query($bdd,$requete);
									$nbenreg=mysqli_num_rows($result);
									$Couleur="#EEEEEE";
									if($nbenreg>0)
									{
										while($row=mysqli_fetch_array($result))
										{
											if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
											else{$Couleur="#EEEEEE";}
									?>
									<tr bgcolor="<?php echo $Couleur;?>">
										<td width="285" class="PetitCompetence">
											<?php
												echo $row['Libelle']." ";
												if($row['Futur']==1){echo " (Futur métier)";}
												if($row['Fiche']!="" && file_exists($CheminQualite."D/5/".$row['Fiche']."-GRP-fr.pdf"))
												{	
													echo " # <a href='javascript:OuvrirFichier(\"".$row['Fiche']."\");'>(".$row['Fiche'].")</a>";
												}
											?>
										</td>
										<?php 
										if(
										DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))
										|| DroitsFormationPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH))
										|| DroitsFormationPlateformes(array(12,16,18,20,22,26,30),array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteResponsablePlateforme))
										)
										{
										?>
										<td width="15">
											<form id="formulaire" method="POST" action="Profil.php">
            									<input type="hidden" name="Id_Metier" value="<?php echo $row[0]; ?>">
            									<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
    											<input type="image" src="../../Images/Suppression.gif" style="border:0;" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){return true;}else{return false;}">
											</form>
										</td>	
										<?php }?>
									</tr>
									<?php
										}
									}
									?>
								</table>
							</td>
							</tr>
						</table>
					</td>
					<td width="20"></td>
					<td>
						<table style="width:300px;" class="ProfilCompetence">
							<!-- DIPLOME -->
							<!--#########-->
							<tr class="TitreSousPageCompetences">
								<td colspan="3"><?php if($LangueAffichage=="FR"){echo "Diplômes";}else{echo "Diplomas";}?></td>
								<?php 
								if(
								DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))
								|| DroitsFormationPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH))
								|| DroitsFormationPlateformes(array(12,16,18,20,22,26,30),array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteResponsablePlateforme))
								)
								{
								?>
								<td width="15"><a class="Modif" href="javascript:OuvreFenetre('Ajout_Profil_Diplome.php?Mode=Ajout&Id_Personne=<?php echo $_GET['Id_Personne'];?>','250','800');"><img src="../../Images/Ajout.gif" border="0" alt="Ajouter" title="Ajouter"></a></td>
								<?php }?>
							</tr>
							<?php
							$requete="SELECT new_competences_personne_diplome.Id, new_competences_personne_diplome.Date, new_competences_niveau_diplome.Libelle, new_competences_diplome.Libelle, new_competences_categorie_diplome.Libelle";
							$requete.=" FROM new_competences_personne_diplome, new_competences_diplome, new_competences_niveau_diplome, new_competences_categorie_diplome";
							$requete.=" WHERE new_competences_personne_diplome.Id_Diplome=new_competences_diplome.Id";
							$requete.=" AND new_competences_personne_diplome.Id_Personne=".$_GET['Id_Personne'];
							$requete.=" AND new_competences_diplome.Id_Niveau=new_competences_niveau_diplome.Id";
							$requete.=" AND new_competences_personne_diplome.Id_Categorie=new_competences_categorie_diplome.Id";
							$requete.=" ORDER BY new_competences_niveau_diplome.Libelle ASC, new_competences_personne_diplome.Date DESC";
							$result=mysqli_query($bdd,$requete);
							$nbenreg=mysqli_num_rows($result);
							$Couleur="#EEEEEE";
							$Niveau="";
							if($nbenreg>0)
							{
								while($row=mysqli_fetch_array($result))
								{
									if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
									else{$Couleur="#EEEEEE";}
    								if($Niveau!=$row[2])
    								{
							?>
							<tr>
								<td colspan="4" class="PetiteCategorieCompetence"><b><?php echo $row[2]; ?></b></td>
							</tr>
							<?php	
								    }
								    $Niveau=$row[2];
							?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td class="PetitCompetence" width="330"><?php echo $row[3]." (".$row[4].")"; ?></td>
								<td class="PetitCompetence" width="80"><?php echo AfficheDateJJ_MM_AAAA($row[1]); ?></td>	
								<?php 
								if(
								DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))
								|| DroitsFormationPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH))
								|| DroitsFormationPlateformes(array(12,16,18,20,22,26,30),array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteResponsablePlateforme))
								)
								{
								?>
								<td width="15">
									<form id="formulaire" method="POST" action="Profil.php">
            							<input type="hidden" name="Id_Diplome" value="<?php echo $row[0]; ?>">
            							<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
										<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){return true;}else{return false;}">
									</form>
								</td>
								<td width="15"><a class="Modif" href="javascript:OuvreFenetre('Ajout_Profil_Diplome.php?Id=<?php echo $row[0];?>&Mode=Modif&Id_Personne=<?php echo $_GET['Id_Personne'];?>','250','800');"><img src="../../Images/Modif.gif" border="0" alt="Modifier" title="Modifier"></a></td>
								<?php }?>
							</tr>
							<?php
								}
							}
							?>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table>
				<tr valign="top">
					<td>
						<table style="width:1000px;" class="ProfilCompetence" id="Table_Prestation">
							<!-- PRESTATION -->
							<!--############-->
							<tr class="TitreSousPageCompetences">
								<td colspan="7"><?php if($LangueAffichage=="FR"){echo "Prestations";}else{echo "Activities";}?></td>
								<?php 
								if(
								DroitsFormationPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteResponsablePlateforme,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH))
								|| DroitsFormationPrestation(array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit))
								)
								{
								?>
								<td width="15"><a class="Modif" href="javascript:OuvreFenetre('Ajout_Profil_Prestation.php?Mode=Ajout&ModeProfil=<?php echo $_GET['Mode'];?>&Id_Personne=<?php echo $_GET['Id_Personne'];?>','300','650');"><img src="../../Images/Ajout.gif" border="0" alt="Ajouter" title="Ajouter"></a></td>
								<?php }
								if(DroitsFormationPrestation(array($IdPosteReferentQualiteProduit,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite)) || DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))){
								echo "<td></td><td></td>";	
								}
								?>
							</tr>
							<?php
							$requete="
                                SELECT
                                    new_competences_personne_prestation.Id,
                                    new_competences_personne_prestation.Date_Debut,
                                    new_competences_personne_prestation.Date_Fin,
                                    (SELECT new_competences_plateforme.Libelle FROM new_competences_plateforme WHERE new_competences_plateforme.Id = new_competences_prestation.Id_Plateforme) AS Plateforme,
                                    new_competences_prestation.Libelle,
                                    (SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id = new_competences_personne_prestation.Id_Pole) AS Pole,
									new_competences_personne_prestation.Id_Prestation
                                FROM
                                    new_competences_personne_prestation
                                LEFT JOIN new_competences_prestation
                                    ON new_competences_personne_prestation.Id_Prestation = new_competences_prestation.Id
                                WHERE
                                    new_competences_personne_prestation.Id_Personne=".$_GET['Id_Personne']."
                                ORDER BY
                                    new_competences_personne_prestation.Date_Fin DESC,
                                    new_competences_personne_prestation.Date_Debut DESC";
	
							$result=mysqli_query($bdd,$requete);
							$nbenreg=mysqli_num_rows($result);
							$Couleur="#EEEEEE";
							if($nbenreg>0)
							{
								while($row=mysqli_fetch_array($result))
								{
									if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
									else{$Couleur="#EEEEEE";}
							?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td class="PetitCompetence" width="830"><?php echo $row[3]." # ".$row[4]." # ".$row[5]; ?></td>
								<td class="PetitCompetence" width="140"><?php echo AfficheDateJJ_MM_AAAA($row[1])." au ".AfficheDateJJ_MM_AAAA($row[2]); ?></td>
								<?php
								if(
								DroitsFormationPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteResponsablePlateforme,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH))
								|| DroitsFormationPrestation(array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit))
								)
								{
								?>
									<td width="15">
										<form id="formulaire" method="POST" action="Profil.php">
                							<input type="hidden" name="Id_Prestation" value="<?php echo $row[0]; ?>">
                							<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
											<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){return true;}else{return false;}">
										</form>
									</td>
									<td width="15"><a class="Modif" href="javascript:OuvreFenetre('Ajout_Profil_Prestation.php?ModeProfil=<?php echo $_GET['Mode'];?>&Id=<?php echo $row[0];?>&Mode=<?php echo $MODE;?>&Id_Personne=<?php echo $_GET['Id_Personne'];?>','300','650');"><img src="../../Images/Modif.gif" border="0" alt="Modifier" title="Modifier"></a></td>
								<?php
								}
								?>
								<td width="15"><a class="Modif" href="javascript:OuvreFenetreCompetencesPresta2('<?php echo $_GET['Id_Personne']; ?>','<?php echo $row['Id_Prestation']; ?>','<?php echo $row[0]; ?>');"><img src="../../Images/Competences.gif" border="0" alt="Competency List" title="Competency List"></a></td>
								<td width="15"><a class="Modif" href="javascript:OuvreFenetreCompetencesExportPresta2('<?php echo $_GET['Id_Personne']; ?>','<?php echo $row['Id_Prestation']; ?>','<?php echo $row[0]; ?>');"><img src="../../Images/excel.gif" border="0" alt="Competency List Excel" title="Competency List Excel"></a></td>
								<?php
								if(DroitsFormationPrestation(array($IdPosteReferentQualiteSysteme)) || DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))){
								?>
									<td width="15"><a class="Modif" href="javascript:OuvreFenetreCompetencesPresta('<?php echo $_GET['Id_Personne']; ?>','<?php echo $row['Id_Prestation']; ?>','<?php echo $row[0]; ?>');"><img width="15px" src="../../Images/etoileBleu.png" border="0" alt="Competency List" title="Competency List"></a></td>
									<td width="15"><a class="Modif" href="javascript:OuvreFenetreCompetencesExportPresta('<?php echo $_GET['Id_Personne']; ?>','<?php echo $row['Id_Prestation']; ?>','<?php echo $row[0]; ?>');"><img src="../../Images/excel.gif" border="0" alt="Competency List Excel" title="Competency List Excel"></a></td>
								<?php
								}
								?>
								
							</tr>
							<?php
								}
							}
							?>
						</table>
					</td>
					<td valign="top">
						<?php
							if($nbenreg>5)
							{
							echo '<img id="Image_PlusMoins_Prestation" src="../../Images/Moins.gif" onclick="javascript:Affiche_Masque(\'Prestation\');">';
							echo '<script>Affiche_Masque("Prestation");</script>';
							}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table>
				<tr valign="top">
					<td>
						<table style="width:1000px;" class="ProfilCompetence">
							<!--  STAMP  -->
							<!--#########-->
							<tr class="TitreSousPageCompetences">
								<td colspan="8">Stamp</td>
								<?php 
								if(
								DroitsFormationPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))
								|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
								|| DroitsFormationPlateformes(array(12,16,18,20,22,26,30),array($IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH))
								)
								{
								?>
								<td width="15"><a class="Modif" href="javascript:OuvreFenetre('Ajout_Profil_Stamp.php?Mode=Ajout&Id_Personne=<?php echo $_GET['Id_Personne'];?>','250','800');"><img src="../../Images/Ajout.gif" border="0" alt="Ajouter" title="Ajouter"></a></td>
								<?php }?>
							</tr>
							<tr>
								<td class="PetiteCategorieCompetence">N° Mark</td>
								<td class="PetiteCategorieCompetence">Scope of application</td>
								<td class="PetiteCategorieCompetence">Work specification N°</td>
								<td class="PetiteCategorieCompetence">Customer</td>
								<td class="PetiteCategorieCompetence">WA/QA iaw commitment letter</td>
								<td class="PetiteCategorieCompetence">Start date</td>
								<td class="PetiteCategorieCompetence">End date</td>
								<td class="PetiteCategorieCompetence" colspan=2>&nbsp;</td>
							</tr>
							<?php
							$result=mysqli_query($bdd,"SELECT * FROM new_competences_personne_stamp WHERE Id_Personne=".$_GET['Id_Personne']." ORDER BY Num_Stamp ASC");
							$nbenreg=mysqli_num_rows($result);
							$Couleur="#EEEEEE";
							if($nbenreg>0)
							{
								while($row=mysqli_fetch_array($result))
								{
									if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
									else{$Couleur="#EEEEEE";}
							?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td class="PetitCompetence" width="100"><?php echo $row[2]; ?></td>
								<td class="PetitCompetence" width="350"><?php echo $row[3]; ?></td>
								<td class="PetitCompetence" width="470"><?php echo $row[4]; ?></td>
								<td class="PetitCompetence" width="100"><?php echo $row[5]; ?></td>
								<td class="PetitCompetence" width="100"><?php echo $row[8]; ?></td>
								<td class="PetitCompetence" width="75"><?php echo AfficheDateJJ_MM_AAAA($row[6]); ?></td>
								<td class="PetitCompetence" width="75"><?php echo AfficheDateJJ_MM_AAAA($row[7]); ?></td>
								
								<?php 
								if(
								DroitsFormationPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))
								|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
								|| DroitsFormationPlateformes(array(12,16,18,20,22,26,30),array($IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH))
								)
								{
								?>
								<td width="15">
									<form id="formulaire" method="POST" action="Profil.php">
            							<input type="hidden" name="Id_Stamp" value="<?php echo $row[0]; ?>">
            							<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
										<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){return true;}else{return false;}">
									</form>
								</td>
								<td width="15"><a class="Modif" href="javascript:OuvreFenetre('Ajout_Profil_Stamp.php?Id=<?php echo $row[0];?>&Mode=Modif&Id_Personne=<?php echo $_GET['Id_Personne'];?>','250','800');"><img src="../../Images/Modif.gif" border="0" alt="Modifier" title="Modifier"></a></td>
								<?php }?>
							</tr>
							<?php
								}
							}
							?>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table>
				<tr valign="top">
					<td>
						<table style="width:1000px;" class="ProfilCompetence">
							<!--  CERTIFYING STAFF  -->
							<!--#########-->
							<tr class="TitreSousPageCompetences">
								<td colspan="3">Certifying Staff</td>
								<?php 
								if(
								DroitsFormationPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))
								|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
								|| DroitsFormationPlateformes(array(12,16,18,20,22,26,30),array($IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH))
								)
								{
								?>
								<td width="15"><a class="Modif" href="javascript:OuvreFenetre('Ajout_Profil_Certifying.php?Mode=Ajout&Id_Personne=<?php echo $_GET['Id_Personne'];?>','250','800');"><img src="../../Images/Ajout.gif" border="0" alt="Ajouter" title="Ajouter"></a></td>
								<?php }?>
							</tr>
							<tr>
								<td class="PetiteCategorieCompetence">Certifying Staff number</td>
								<td class="PetiteCategorieCompetence">Authorization to sign</td>
								<td class="PetiteCategorieCompetence" colspan="2">Precision</td>
							</tr>
							<tr bgcolor="#FFFFFF">
								<td><?php echo $CertifyingStaffNumber;?></td>
								<td>
								<?php
								$result=mysqli_query($bdd,"SELECT AutorisationSign FROM new_competences_personne_certifying WHERE Id_Personne=".$_GET['Id_Personne']." ORDER BY AutorisationSign ASC");
								$nbenreg=mysqli_num_rows($result);
								$autorisation="";
								if($nbenreg>0)
								{
									$k=0;
									while($rowAuto=mysqli_fetch_array($result))
									{
										if($k>0){$autorisation.= " | ";}
										$autorisation.=$rowAuto['AutorisationSign'];
										$k++;
									}
								}
								echo $autorisation;
								?>
								</td>
								<td colspan="2"><?php echo $CertifyingStaffPrecision;?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table>
				<tr valign="top">
					<td>
						<table style="width:625px;" class="ProfilCompetence" id="Table_Formation">
							<!-- FORMATIONS -->
							<!--############-->
							<tr class="TitreSousPageCompetences">
								<td colspan="4"><?php if($LangueAffichage=="FR"){echo "Formations";}else{echo "Trainings";}?></td>
								<?php
								if(
								DroitsFormationPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH))
								|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
								)
								{
								?>
								<td width="15"><a class="Modif" href="javascript:OuvreFenetre('Ajout_Profil_Formation.php?Mode=Ajout&Id_Personne=<?php echo $_GET['Id_Personne'];?>','250','800');"><img src="../../Images/Ajout.gif" border="0" alt="Ajouter" title="Ajouter"></a></td>
								<?php }?>
							</tr>
							<!-- GESTION DE L'AFFICHAGE DES FORMATIONS ISSUES DE LA GESTION DES FORMATIONS -->
							<!-- ######################################################################### -->
							<?php
							$req="
								SELECT
								form_besoin.Id AS Id_Besoin,
								0 AS Id_PersonneFormation,
								(
								SELECT
									form_session_date.DateSession
								FROM
									form_session_personne
								LEFT JOIN 
									form_session_date 
								ON 
									form_session_personne.Id_Session=form_session_date.Id_Session
								WHERE
									form_session_personne.Id_Besoin=form_besoin.Id
									AND form_session_personne.Id NOT IN 
										(
										SELECT
											Id_Session_Personne
										FROM
											form_session_personne_qualification
										WHERE
											Suppr=0	
										)
									AND form_session_personne.Suppr=0
									AND form_session_personne.Validation_Inscription=1
									AND form_session_personne.Presence=1
									AND form_session_date.Suppr=0
								ORDER BY DateSession DESC
								LIMIT 1
								) AS DateSession,
								(SELECT (SELECT Libelle FROM form_organisme WHERE Id=Id_Organisme) FROM form_formation_plateforme_parametres 
									WHERE form_formation_plateforme_parametres.Id_Formation=form_besoin.Id_Formation
									AND form_formation_plateforme_parametres.Id_Plateforme=new_competences_prestation.Id_Plateforme 
									AND Suppr=0 LIMIT 1) AS Organisme,
								(SELECT IF(form_besoin.Motif='Renouvellement',LibelleRecyclage,Libelle)
									FROM form_formation_langue_infos
									WHERE Id_Formation=form_besoin.Id_Formation
									AND Id_Langue=
										(SELECT Id_Langue 
										FROM form_formation_plateforme_parametres 
										WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
										AND Id_Formation=form_besoin.Id_Formation
										AND Suppr=0 
										LIMIT 1)
									AND Suppr=0) AS Libelle,
							'Professionnelle' AS Type
							FROM
								form_besoin,
								new_competences_prestation
							WHERE
								form_besoin.Id_Personne=".$_GET['Id_Personne']."
								AND form_besoin.Id_Prestation=new_competences_prestation.Id
								AND form_besoin.Suppr=0
								AND form_besoin.Valide=1
								AND form_besoin.Traite=4
								AND form_besoin.Id IN
								(
								SELECT
									Id_Besoin
								FROM
									form_session_personne
								WHERE
									form_session_personne.Id NOT IN 
										(
										SELECT
											Id_Session_Personne
										FROM
											form_session_personne_qualification
										WHERE
											Suppr=0	
										)
									AND Suppr=0
									AND form_session_personne.Validation_Inscription=1
									AND form_session_personne.Presence=1
								)
								
								UNION 
								
								SELECT 
								0 AS Id_Besoin,
								new_competences_personne_formation.Id AS Id_PersonneFormation, 
								new_competences_personne_formation.Date AS DateSession,
								'' AS Organisme,
								(SELECT Libelle FROM new_competences_formation WHERE new_competences_personne_formation.Id_Formation=new_competences_formation.Id) AS Libelle ,
								new_competences_personne_formation.Type 
								FROM new_competences_personne_formation
								WHERE new_competences_personne_formation.Id_Personne=".$_GET['Id_Personne']." 
								ORDER BY Type ASC, Libelle ASC, DateSession DESC ";
							$result=mysqli_query($bdd,$req);
							$nbenreg=mysqli_num_rows($result);
							$Couleur="#EEEEEE";
							$Categorie="";
							$LibelleDAvant="";
							if($nbenreg>0)
							{
								while($row=mysqli_fetch_array($result))
								{
									$CodeHTMLAfficheCompetencesIdentiques="";
									$CodeHTMLBoutonPlusCompetencesIdentiques="";
									if($LibelleDAvant != $row['Libelle'])
									{
										$LibelleDAvant = $row['Libelle'];
										$Requete_QualifCount="
											SELECT
											(SELECT IF(form_besoin.Motif='Renouvellement',LibelleRecyclage,Libelle)
												FROM form_formation_langue_infos
												WHERE Id_Formation=form_besoin.Id_Formation
												AND Id_Langue=
													(SELECT Id_Langue 
													FROM form_formation_plateforme_parametres 
													WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
													AND Id_Formation=form_besoin.Id_Formation
													AND Suppr=0 
													LIMIT 1)
												AND Suppr=0) AS Libelle,
										COUNT(form_besoin.Id) AS cpt
										FROM
											form_besoin
										WHERE
											form_besoin.Id_Personne=".$_GET['Id_Personne']."
											AND form_besoin.Suppr=0
											AND form_besoin.Valide=1
											AND form_besoin.Traite=4
											AND (SELECT IF(form_besoin.Motif='Renouvellement',LibelleRecyclage,Libelle)
											FROM form_formation_langue_infos
											WHERE Id_Formation=form_besoin.Id_Formation
											AND Id_Langue=
												(SELECT Id_Langue 
												FROM form_formation_plateforme_parametres 
												WHERE Id_Plateforme=(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=form_besoin.Id_Prestation)
												AND Id_Formation=form_besoin.Id_Formation
												AND Suppr=0 
												LIMIT 1)
											AND Suppr=0) LIKE \"".$row['Libelle']."\"
											AND form_besoin.Id IN
											(
											SELECT
												Id_Besoin
											FROM
												form_session_personne
											WHERE
												form_session_personne.Id NOT IN 
													(
													SELECT
														Id_Session_Personne
													FROM
														form_session_personne_qualification
													WHERE
														Suppr=0	
													)
												AND Suppr=0
												AND form_session_personne.Validation_Inscription=1
												AND form_session_personne.Presence=1
											)
											
											UNION 
											
											SELECT 
											(SELECT Libelle FROM new_competences_formation WHERE new_competences_personne_formation.Id_Formation=new_competences_formation.Id) AS Libelle,
											COUNT(new_competences_personne_formation.Id) AS cpt
											FROM new_competences_personne_formation
											WHERE new_competences_personne_formation.Id_Personne=".$_GET['Id_Personne']." 
											AND (SELECT Libelle FROM new_competences_formation WHERE new_competences_personne_formation.Id_Formation=new_competences_formation.Id) LIKE \"".$row['Libelle']."\"
											GROUP BY Libelle
											ORDER BY Cpt DESC
											";
										$row2 = mysqli_fetch_array(mysqli_query($bdd,$Requete_QualifCount));
										
											if($row2['cpt']>1){
												$CodeHTMLBoutonPlusCompetencesIdentiques="<td width='15' class='collapser'><img id=\"".$row['Libelle']."\" src='../../Images/Plus.gif'></td>";
											}
											else{
												$CodeHTMLBoutonPlusCompetencesIdentiques="<td width='15' class='collapser'></td>";
											}
										}
										else
										{
											$CodeHTMLAfficheCompetencesIdentiques='class="autresCompetences" id="'.$row['Libelle'].'" style="display:none;"';
										}
									
									
									if($Categorie!=$row['Type'])
									{
							?>
							<tr>
								<td colspan="5" class="PetiteCategorieCompetence"><b><?php echo $row['Type']; ?></b></td>
							</tr>
							<?php	
									}
									$Categorie=$row['Type'];
									
									if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
									else{$Couleur="#EEEEEE";}
									
							?>
							<tr bgcolor="<?php echo $Couleur;?>" <?php echo $CodeHTMLAfficheCompetencesIdentiques;?>>
								<td class="PetitCompetence" width="535"><?php echo $row['Libelle']." ".$row['Organisme']; ?></td>
								<td class="PetitCompetence" width="60"><?php echo AfficheDateJJ_MM_AAAA($row['DateSession']); ?></td>	
								<?php 
								if(
								DroitsFormationPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH))
								|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
								)
								{
								?>
								<td width="15">
									<?php if($row['Id_Besoin']==0){?>
									<form id="formulaire" method="POST" action="Profil.php">
            							<input type="hidden" name="Id_Formation" value="<?php echo $row['Id_PersonneFormation']; ?>">
            							<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
										<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){return true;}else{return false;}">
									</form>
									<?php } ?>
								</td>
								<td width="15">
									<?php if($row['Id_Besoin']==0){?>
									<a class="Modif" href="javascript:OuvreFenetre('Ajout_Profil_Formation.php?Id=<?php echo $row['Id_PersonneFormation'];?>&Mode=Modif&Id_Personne=<?php echo $_GET['Id_Personne'];?>','250','800');"><img src="../../Images/Modif.gif" border="0" alt="Modifier" title="Modifier"></a>
									<?php } ?>
								</td>
								<?php }?>
									<?php
									echo $CodeHTMLBoutonPlusCompetencesIdentiques;	
									?>									
							</tr>
							<?php
								}
							}
							?>
							<!-- ######################################################################### -->
						</table>
					</td>
					<td valign="top">
						<?php
							if($nbenreg>5)
							{
							echo '<img id="Image_PlusMoins_Formation" src="../../Images/Moins.gif" onclick="javascript:Affiche_Masque(\'Formation\');">';
							echo '<script>Affiche_Masque("Formation");</script>';
							}
						?>
					</td>
					
					<td width="15">
					</td>
					
					<td>
						<table class="ProfilCompetence" id="Table_RH_EIA">
							<!-- RH - EIA-->
							<!--#########-->
							<tr class="TitreSousPageCompetences">
								<td colspan="5"><?php if($LangueAffichage=="FR"){echo "Entretiens professionnels";}else{echo "Career interviews";}?></td>
								<?php 
								if(
								DroitsFormationPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH))
								)
								{
								?>
								<td width="15"><a class="Modif" href="javascript:OuvreFenetre('Ajout_Profil_RH_EIA.php?Mode=Ajout&Id_Personne=<?php echo $_GET['Id_Personne'];?>','250','800');"><img src="../../Images/Ajout.gif" border="0" alt="Ajouter" title="Ajouter"></a></td>
								<?php }?>
							</tr>
							<tr>
								<td class="TitreSousPageCompetencesPetit"><?php if($LangueAffichage=="FR"){echo "Date prévue";}else{echo "Previsional date";}?></td>
								<td class="TitreSousPageCompetencesPetit"><?php if($LangueAffichage=="FR"){echo "Date de report";}else{echo "Report date";}?></td>
								<td class="TitreSousPageCompetencesPetit"><?php if($LangueAffichage=="FR"){echo "Date réalisée";}else{echo "Done date";}?></td>
								<td class="TitreSousPageCompetencesPetit" colspan=3>Delta</td>
							</tr>
							<?php
							$req="SELECT Id, Id_Personne, Date_Prevue, Date_Report, Date_Reel, Type, 0 AS ProjetEPE, 0 AS EntretienEC FROM new_competences_personne_rh_eia WHERE Id_Personne=".$_GET['Id_Personne']." 
							UNION
							SELECT Id, Id_Personne,DateButoir AS Date_Prevue, DateReport AS Date_Report, 
							IF((SELECT COUNT(Id)
							FROM epe_personne 
							WHERE Suppr=0 AND DateSalarie>'0001-01-01'  AND DateEvaluateur>'0001-01-01'  AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(DateCreation)= YEAR(epe_personne_datebutoir.DateButoir))>0,
							(SELECT DateEntretien
							FROM epe_personne 
							WHERE Suppr=0 AND DateSalarie>'0001-01-01'  AND DateEvaluateur>'0001-01-01'  AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(DateCreation)= YEAR(epe_personne_datebutoir.DateButoir) LIMIT 1)
							,'0001-01-01')
							AS Date_Reel, TypeEntretien AS Type, 1 AS ProjetEPE,
							(SELECT COUNT(Id)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=epe_personne_datebutoir.Id_Personne AND YEAR(DateEntretien)= YEAR(epe_personne_datebutoir.DateButoir)) AS EntretienEC
							FROM epe_personne_datebutoir
							WHERE Id_Personne=".$_GET['Id_Personne']." 
							ORDER BY Type ASC, Date_Prevue DESC";
							$result=mysqli_query($bdd,$req);
							$nbenreg=mysqli_num_rows($result);
							$Categorie="";
							$Couleur="#EEEEEE";
							if($nbenreg>0)
							{
								while($row=mysqli_fetch_array($result))
								{
									if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
									else{$Couleur="#EEEEEE";}
									if($Categorie!=$row['Type'])
									{
							?>
							<tr>
								<td colspan="6" class="PetiteCategorieCompetence"><b><?php if($row['Type'] == "EPE"){echo "(EPE) Entretien Professionnel d'Evaluation";}elseif($row['Type'] == "EPP"){echo "(EPP) Entretien Professionnel Parcours";}else{echo "(Bilan à 6 ans) Etat des lieux récapitulatif du parcours professionnel";} ?></b></td>
							</tr>
							<?php	
									}
									$Categorie=$row['Type'];
							?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td <?php if(($row['Date_Reel'] <= '0001-01-01') && round((strtotime($row['Date_Prevue']) - strtotime($DateJour))/(60*60*24))<=30){echo 'bgcolor="#FF9966"';}else{echo 'bgcolor="'.$Couleur.'"';}?> class="PetitCompetence" width="75"><a target="_blank" href="<?php echo "Edition_EntretienPro.php?IdPersonne=".$_GET['Id_Personne']."&IdEval=".$IdPersonneConnectee."&Type=".$row['Type']."&ProjetEPE=".$row['ProjetEPE']."&Id_Entretien=".$row['Id'];?>"><?php echo AfficheDateJJ_MM_AAAA($row['Date_Prevue']); ?></a></td>
								<td class="PetitCompetence" width="75"><?php if($row['Date_Report']<>$row['Date_Prevue']){echo AfficheDateJJ_MM_AAAA($row['Date_Report']);} ?></td>
								<td class="PetitCompetence" width="75"><?php echo AfficheDateJJ_MM_AAAA($row['Date_Reel']); ?></td>
								<td class="PetitCompetence" width="75"><?php if($row['Date_Prevue'] > '0001-01-01' && $row['Date_Prevue'] != '0001-01-01' && $row['Date_Reel'] > '0001-01-01' && $row['Date_Reel'] != '0001-01-01'){echo round((strtotime($row[4]) - strtotime($row[2]))/(60*60*24));} ?></td>
								<?php 
								if(
								DroitsFormationPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH))
								)
								{
								?>
								<td width="15">
									<form id="formulaire" method="POST" action="Profil.php">
										<?php if($row['ProjetEPE']==0){ ?>
            							<input type="hidden" name="Id_EIA" value="<?php echo $row[0]; ?>">
            							<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
    									<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){return true;}else{return false;}">
										<?php }
											elseif($row['ProjetEPE']==1 && $row['EntretienEC']==0){
										?>
										<input type="hidden" name="Id_DateButoir" value="<?php echo $row[0]; ?>">
            							<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
										<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){return true;}else{return false;}">
										<?php 
											}
										?>
									</form>
								</td>
								<td width="15">
								<?php if($row['ProjetEPE']==0){ ?>
								<a class="Modif" href="javascript:OuvreFenetre('Ajout_Profil_RH_EIA.php?Id=<?php echo $row[0];?>&Mode=Modif&Id_Personne=<?php echo $_GET['Id_Personne'];?>','250','800');"><img src="../../Images/Modif.gif" border="0" alt="Modifier" title="Modifier"></a>
								<?php }elseif($row['ProjetEPE']==1){ ?>
								<a class="Modif" href="javascript:OuvreFenetre('../EPE/Modif_DateButoir_Profil.php?Id=<?php echo $row[0];?>&Mode=Modif&Id_Personne=<?php echo $_GET['Id_Personne'];?>','250','800');"><img src="../../Images/Modif.gif" border="0" alt="Modifier" title="Modifier"></a>
								<?php 
									}
								?>
								</td>
								<?php }?>
							</tr>
							<?php
								}
							}
							?>
						</table>
					</td>
					<td valign="top">
						<?php
							if($nbenreg>5)
							{
							echo '<img id="Image_PlusMoins_RH_EIA" src="../../Images/Moins.gif" onclick="javascript:Affiche_Masque(\'RH_EIA\');">';
							echo '<script>Affiche_Masque("RH_EIA");</script>';
							}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table>
				<tr>
					<td>
						<table style="width:550px;" class="ProfilCompetence">
							<!-- FICHE HSE -->
							<!--#########-->
							<tr class="TitreSousPageCompetences">
								<td colspan="5"><?php if($LangueAffichage=="FR"){echo "Fiches HSE";}else{echo "HSE Cards";}?></td>
								<?php 
								if(DroitsPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH))
								)
								{
								?>
								<td width="15"><a class="Modif" href="javascript:OuvreFenetre('Ajout_Profil_FicheHSE.php?Mode=Ajout&Id_Personne=<?php echo $_GET['Id_Personne'];?>','180','800');"><img src="../../Images/Ajout.gif" border="0" alt="Ajouter" title="Ajouter"></a></td>
								<?php }?>
							</tr>
							<tr>
								<td class="TitreSousPageCompetencesPetit"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
								<td class="TitreSousPageCompetencesPetit"><?php if($LangueAffichage=="FR"){echo "Métier";}else{echo "Job";}?></td>
								<td class="TitreSousPageCompetencesPetit"><?php if($LangueAffichage=="FR"){echo "Date début";}else{echo "Start date";}?></td>
								<td class="TitreSousPageCompetencesPetit" colspan=3><?php if($LangueAffichage=="FR"){echo "Date fin";}else{echo "End date";}?></td>
							</tr>
							<?php
							$result=mysqli_query($bdd,"SELECT * FROM new_competences_personne_fichehse WHERE Id_Personne=".$_GET['Id_Personne']." ORDER BY Date_Debut DESC");
							$nbenreg=mysqli_num_rows($result);
							$Couleur="#EEEEEE";
							if($nbenreg>0)
							{
								while($row=mysqli_fetch_array($result))
								{
									if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
									else{$Couleur="#EEEEEE";}
							?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td class="PetitCompetence" width="300">
									<a target="_blank" href="<?php echo "Edition_FicheHSE.php?Nom=".$Nom."&Prenom=".$Prenom."&Plateforme=".$Plateforme."&Emploi=".$row[4]."&Date_Debut=".$row[2]."&Date_Fin=".$row[3];?>"><?php if($LangueAffichage=="FR"){echo "Fiche de pénibilité";}else{echo "Hardbess sheet";}?></a> 
									<?php if($LangueAffichage=="FR"){echo "et fiche d'exposition";}else{echo "and exposure sheet";}?>
								</td>
								<td class="PetitCompetence" width="100"><?php echo str_replace("_"," ",$row[4]); ?></td>
								<td class="PetitCompetence" width="60"><?php echo AfficheDateJJ_MM_AAAA($row[2]); ?></td>
								<td class="PetitCompetence" width="60"><?php echo AfficheDateJJ_MM_AAAA($row[3]); ?></td>
								<?php 
								if(DroitsPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH))
								)
								{
								?>
								<td width="15">
									<form id="formulaire" method="POST" action="Profil.php">
            							<input type="hidden" name="Id_FicheHSE" value="<?php echo $row[0]; ?>">
            							<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
										<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){return true;}else{return false;}">
									</form>
								</td>
								<td width="15"><a class="Modif" href="javascript:OuvreFenetre('Ajout_Profil_FicheHSE.php?Id=<?php echo $row[0];?>&Mode=Modif&Id_Personne=<?php echo $_GET['Id_Personne'];?>','180','800');"><img src="../../Images/Modif.gif" border="0" alt="Modifier" title="Modifier"></a></td>
								<?php }?>
							</tr>
							<?php
								}
							}
							?>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table id="tableauCompetences">
				<tr>
				</tr>
				<!-- QUALIFICATIONS -->
				<!--################-->
				<?php
				$Requete_Categorie_Maitre="SELECT * FROM new_competences_categorie_qualification_maitre ORDER BY Id";
				$Liste_Categorie_Maitre=mysqli_query($bdd,$Requete_Categorie_Maitre);
				$Evaluation="";
				while($Ligne_Categorie_Maitre=mysqli_fetch_array($Liste_Categorie_Maitre))
				{
					switch($Ligne_Categorie_Maitre['Id'])
					{
						case 1:$Evaluation="B/L/V";break;
						case 2:$Evaluation="B/L/Q/<br>S/T";break;
						case 3:$Evaluation="B/L/X";break;
					}
				?>
				<tr>
					<td>
						<table class="ProfilCompetence" style="width:1000px;" id="Table_Qualification_<?php echo $Ligne_Categorie_Maitre['Libelle']; ?>">
							<tr class="TitreSousPageCompetences">
								<?php 
									$colspan="15";
									if($MODE=="Lecture"){$colspan="19";}
								?>
								<td colspan="<?php echo $colspan; ?>"><?php echo $Ligne_Categorie_Maitre['Libelle']; ?></td>
								<td width=10>
									<?php
									if(
									DroitsFormationPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH,$IdPosteResponsableQualite,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))
									|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
									)
									{
									?>
									<a class="Modif" href="javascript:OuvreFenetre('Ajout_Profil_Qualification.php?Mode=Ajout&Id_Categorie_Maitre=<?php echo $Ligne_Categorie_Maitre['Id']; ?>&Id_Personne=<?php echo $_GET['Id_Personne'];?>&Id=','550','1100');">
										<img src="../../Images/Ajout.gif" border="0" alt="Ajouter" title="Ajouter">
									</a>
									<?php
									}
									?>
								</td>
							</tr>
							<tr>
								<td colspan="3" class="TitreSousPageCompetencesPetit"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
								<td class="TitreSousPageCompetencesPetit"><?php if($LangueAffichage=="FR"){echo "Date début";}else{echo "Start date";}?></td>
								<td class="TitreSousPageCompetencesPetit"><?php if($LangueAffichage=="FR"){echo "Date fin";}else{echo "End date";}?></td>
								<td class="TitreSousPageCompetencesPetit"><?php echo $Evaluation; ?></td>
								<td class="TitreSousPageCompetencesPetit"><?php if($LangueAffichage=="FR"){echo "Note";}else{echo "Score";}?></td>
								<td class="TitreSousPageCompetencesPetit"><?php if($LangueAffichage=="FR"){echo "Date QCM";}else{echo "QCM Date";}?></td>
								<td class="TitreSousPageCompetencesPetit"><?php if($LangueAffichage=="FR"){echo "Note surv.";}else{echo "Monitoring score";}?></td>
								<td class="TitreSousPageCompetencesPetit"><?php if($LangueAffichage=="FR"){echo "Date surv.";}else{echo "Monitoring date";}?></td>
								<td colspan="10" class="TitreSousPageCompetencesPetit">&nbsp;</td>
							</tr>
							<?php
							$Requete_Qualif="
								SELECT
									new_competences_qualification.Id,
									new_competences_qualification.Id_Categorie_Qualification,
									new_competences_qualification.Libelle,
									new_competences_qualification.Periodicite_Surveillance,
									new_competences_categorie_qualification.Libelle,
									new_competences_relation.Sans_Fin,
									new_competences_relation.Evaluation,
									new_competences_relation.Date_QCM,
									new_competences_relation.QCM_Surveillance,
									new_competences_relation.Date_Surveillance,
									new_competences_relation.Id AS Id_Relation,
									new_competences_relation.Visible,
									new_competences_relation.Date_Debut,
									new_competences_relation.Date_Fin,
									new_competences_relation.Resultat_QCM,
									new_competences_relation.Id_Besoin,
									new_competences_relation.FicheQualification,
									new_competences_relation.Id_Session_Personne_Qualification,
									(SELECT Traite FROM form_besoin WHERE form_besoin.Id=new_competences_relation.Id_Besoin) AS Traite,
									(SELECT Id FROM form_session_personne WHERE form_session_personne.Id_Besoin=new_competences_relation.Id_Besoin AND Suppr=0 ORDER BY Id DESC LIMIT 1) AS Id_SessionPersonne,
									(SELECT Duree_Validite FROM new_competences_qualification WHERE Id=Id_Qualification_Parrainage) AS Duree_Validite,
									new_competences_relation.AttestationFormation
								FROM
									new_competences_relation,
									new_competences_qualification,
									new_competences_categorie_qualification
								WHERE
									new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
									AND new_competences_qualification.Id=new_competences_relation.Id_Qualification_Parrainage
									AND new_competences_relation.Id_Personne=".$_GET['Id_Personne']."
									AND new_competences_relation.Type='Qualification'
									AND new_competences_relation.Suppr=0
									AND new_competences_categorie_qualification.Id_Categorie_Maitre=".$Ligne_Categorie_Maitre['Id']."
								ORDER BY
									new_competences_categorie_qualification.Libelle ASC,
									new_competences_qualification.Libelle ASC,
									new_competences_relation.Date_Debut DESC,
									new_competences_relation.Date_QCM DESC";
									
							$ListeQualification=mysqli_query($bdd,$Requete_Qualif);
							$Categorie="";
							$Couleur="#EEEEEE";
							$nbenreg=mysqli_num_rows($ListeQualification);
							$LibelleDAvant = "";

							while($LigneQualification=mysqli_fetch_array($ListeQualification))
							{
								//Initialisation des variables
								$DateFinValiditeA3Mois="0001-01-01";
								$DateSurveillanceA2Ans="0001-01-01";
								$Valide=true;
							
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
								$result2=mysqli_query($bdd,"SELECT Libelle FROM new_competences_categorie_qualification WHERE Id=".$LigneQualification['Id_Categorie_Qualification']);
								$row2=mysqli_fetch_array($result2);
								if($Categorie!=$row2['Libelle'])
								{
								    echo "<tr>
                                            <td colspan=\"19\" class=\"PetiteCategorieCompetence\"><b>".$row2['Libelle']."</b></td>
                                        </tr>";	
								}
								$Categorie=$row2['Libelle'];
								$result3=mysqli_query($bdd,"SELECT Libelle,Periodicite_Surveillance FROM new_competences_qualification WHERE Id=".$LigneQualification['Id']);
								$row3=mysqli_fetch_array($result3);

								//Calcul affichage spécifique
								$AffichageFinValidite="";
								if($LigneQualification['Date_Fin']>'0001-01-01' && $LigneQualification['Date_Fin']!='0001-01-01' && $LigneQualification['Sans_Fin']!='Oui')	//Sans Limite = 'Oui' en [16]
								{
									$day=substr($LigneQualification['Date_Fin'],8,2);
									$year=substr($LigneQualification['Date_Fin'],0,4);
									$month=substr($LigneQualification['Date_Fin'],5,2);
									$DateFinValiditeA3Mois=date("Y-m-d",mktime(0,0,0,$month-3,$day,$year));
									if($DateFinValiditeA3Mois<=$DateJour){$AffichageFinValidite=" bgcolor='#FF9966'";$Valide=false;}
								}
								
								$AffichageSurveillance="";
								if(($LigneQualification['Evaluation']=="Q" || $LigneQualification['Evaluation']=="S") && $Valide == true && $LigneQualification['Date_Debut']>'0001-01-01' && $LigneQualification['Date_Surveillance']<='0001-01-01')
								{
									$day=substr($LigneQualification['Date_Debut'],8,2);
									$year=substr($LigneQualification['Date_Debut'],0,4);
									$month=substr($LigneQualification['Date_Debut'],5,2);
									$DateSurveillanceA2Ans=date("Y-m-d",mktime(0,0,0,$month-1+$row3['Periodicite_Surveillance'],$day,$year));
									if($DateSurveillanceA2Ans<=$DateJour && $row3['Periodicite_Surveillance']>0){$AffichageSurveillance=" bgcolor='#FF9966'";}
								}

								$CodeHTMLAfficheCompetencesIdentiques="";
								$CodeHTMLBoutonPlusCompetencesIdentiques="";
								if($LibelleDAvant != $row3['Libelle'])
								{
									$LibelleDAvant = $row3['Libelle'];
									$Requete_QualifCount="
										SELECT
											new_competences_qualification.Libelle,
											COUNT(new_competences_qualification.Libelle) AS cpt
										FROM
											new_competences_relation,
											new_competences_qualification,
											new_competences_categorie_qualification
										WHERE
											new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
											AND new_competences_qualification.Id=new_competences_relation.Id_Qualification_Parrainage
											AND new_competences_relation.Id_Personne=".$_GET['Id_Personne']."
											AND new_competences_relation.Type='Qualification'
											AND new_competences_relation.Suppr=0
											AND REPLACE(new_competences_qualification.Libelle,'\"','') =\"".str_replace('"','',stripslashes($row3['Libelle']))."\"
											AND new_competences_categorie_qualification.Id_Categorie_Maitre=".$Ligne_Categorie_Maitre['Id']."
										GROUP BY
											new_competences_qualification.Libelle
										ORDER BY
											new_competences_categorie_qualification.Libelle ASC,
											new_competences_qualification.Libelle ASC,
											new_competences_relation.Date_Debut DESC,
											new_competences_relation.Date_QCM DESC";
									$row = mysqli_fetch_array(getRessource($Requete_QualifCount));
									
									if($row['cpt']>1)
									    $CodeHTMLBoutonPlusCompetencesIdentiques="<td width='15' class='collapser'><img id=\"".$row3['Libelle']."\" src='../../Images/Plus.gif'></td>";
								}
								else
								{
									$CodeHTMLAfficheCompetencesIdentiques='class="autresCompetences" id="'.$row3['Libelle'].'" style="display:none;"';
								}
									$couleur="";
									if($LigneQualification['Id_Besoin']<>"0"){$couleur="bgcolor='#f5ff9f'";}
									?>							
									<tr bgcolor="<?php echo $Couleur;?>" <?php echo $CodeHTMLAfficheCompetencesIdentiques;?>>
										<td width="400" colspan="3" class="PetitCompetence">
											<?php
											echo $row3['Libelle'];
											
											if(isset($_SESSION['PartieFormation']))
											{
												if($_SESSION['PartieFormation']>1)
												{
													if($LigneQualification['Id_Besoin']<>0 && $LigneQualification['Evaluation']<>'B' && $LigneQualification['Evaluation']<>'')
													{
														if($LigneQualification['Traite']==4 && $LigneQualification['Id_SessionPersonne']>0)
														{
															//echo "<a class=\"Info\" href=\"javascript:genererAttestation(".$LigneQualification['Id_SessionPersonne'].");\"><img src='../../Images/certificat.jpg' style='border:0;width:20px;' title='Attestation'></a>";
														}
													} 
												}
											}
											?>
										</td>
										<td width="80" class="PetitCompetence" title="Date de validation de la qualification"><?php echo AfficheDateJJ_MM_AAAA($LigneQualification['Date_Debut']); ?></td>
										<td width="80" class="PetitCompetence" title="Date de fin de validité de la qualification"<?php echo $AffichageFinValidite;?>><?php if($LigneQualification['Sans_Fin']=='Oui' || (($LigneQualification['Date_Fin']<='0001-01-01' || $LigneQualification['Date_Fin']=='0001-01-01') && $LigneQualification['Duree_Validite']==0)){echo "Sans limite";}else{echo AfficheDateJJ_MM_AAAA($LigneQualification['Date_Fin']);} ?></td>
										<td class="PetitCompetence" <?php echo $couleur; ?> align="center"><?php echo $LigneQualification['Evaluation']; ?></td>
										<td class="PetitCompetence" align="center"><?php echo $LigneQualification['Resultat_QCM']; ?></td>
										<td width="80" class="PetitCompetence"><?php echo AfficheDateJJ_MM_AAAA($LigneQualification['Date_QCM']); ?></td>
										<td class="PetitCompetence" align="center"><?php echo $LigneQualification['QCM_Surveillance']; ?></td>
										<td width="80" class="PetitCompetence" title="Date de la réalisation de la surveillance"<?php echo $AffichageSurveillance;?>><?php echo AfficheDateJJ_MM_AAAA($LigneQualification['Date_Surveillance']);?></td>
										<?php
										if(
										DroitsFormationPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite))
										|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
										)
										{
										?>								
										<td width="15">
											<form id="formulaire" method="POST" action="Profil.php">
            									<input type="hidden" name="Id_Relation" value="<?php echo $LigneQualification['Id_Relation']; ?>">
            									<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
												<?php if($LigneQualification['Id_Besoin']=="0" || ($LigneQualification['Evaluation']<>"B" && DroitsFormationPlateforme(array($IdPosteResponsableQualite)))){ ?>
    											<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){return true;}else{return false;}">
												<?php } ?>
											</form>
										</td>
										<td width="15">
											<?php if($LigneQualification['Id_Besoin']=="0" || ($LigneQualification['Evaluation']<>"B" && DroitsFormationPlateforme(array($IdPosteResponsableQualite))) || (($LigneQualification['Evaluation']=="L" || $LigneQualification['Evaluation']=="Q" || $LigneQualification['Evaluation']=="S") && (DroitsFormationPrestation(array($IdPosteReferentQualiteProduit)) || DroitsFormationPlateforme(array($IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite)) ))){ ?>
											<a class="Modif" href="javascript:OuvreFenetre('Ajout_Profil_Qualification.php?Id=<?php echo $LigneQualification['Id_Relation'];?>&Mode=Modif&Id_Categorie_Maitre=<?php echo $Ligne_Categorie_Maitre['Id']; ?>&Id_Personne=<?php echo $_GET['Id_Personne'];?>','550','1100');"><img src="../../Images/Modif.gif" border="0" alt="Modifier" title="Modifier"></a>									
											<?php }
											elseif(DroitsFormationPlateforme(array($IdPosteResponsableQualite,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite)) || DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))){
											?>
											<a class="Modif" href="javascript:OuvreFenetre('Modif_Profil_Qualipso.php?Id=<?php echo $LigneQualification['Id_Relation'];?>&Mode=Modif&Id_Categorie_Maitre=<?php echo $Ligne_Categorie_Maitre['Id']; ?>&Id_Personne=<?php echo $_GET['Id_Personne'];?>','550','1000');"><img src="../../Images/Modif.gif" border="0" alt="Modifier" title="Modifier"></a>
											<?php
											} ?>
										</td>
										<td width="15">
										<?php 
											if($LigneQualification['FicheQualification']<>""){
												echo "<a class=\"Info\" href=\"FichesQualifications/".$LigneQualification['FicheQualification']."\"><img src='../../Images/certificat.jpg' style='border:0;width:20px;' title='FQ'></a>";
											}
										?>
										</td>
										<?php 
										}
										elseif(
										(DroitsFormationPlateforme(array($IdPosteAdministrateur,$IdPosteResponsableQualite,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteAssistantAdministratif,$IdPosteOperateurSaisieRH,$IdPosteReferentQualiteSysteme,$IdPosteAssistantQualite,$IdPosteResponsablePlateforme))
										|| DroitsFormationPrestation(array($IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation,$IdPosteReferentQualiteProduit))
										)
										&& $LigneQualification['Evaluation']=="B" && mysqli_num_rows($resAcc=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_prestation WHERE Id_Prestation IN (228,48,521,78,19,23,24,25,26,506) AND Id_Personne=".$IdPersonneConnectee." AND Id_Poste = 2"))>0)
										{
											?>
											<td width="15" colspan="8">
											<?php if($LigneQualification['Id_Besoin']=="0" || ($LigneQualification['Evaluation']<>"B" && DroitsFormationPlateforme(array($IdPosteResponsableQualite)))){ ?>
												<input type="image" src="../../Images/Suppression.gif" style="border:0;" alt="Supprimer" title="Supprimer" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){return true;}else{return false;}">
											<?php } ?>
											</td>
										<?php
										}
										else
										{
										?>
											<td colspan="8"></td>
										<?php
										}
										//Rechercher si une surveillance existe 
										$req="SELECT Id 
										FROM form_session_personne_qualification 
										WHERE Suppr=0 
										AND Id_Relation=".$LigneQualification['Id_Relation'];
										$resultSurveillance=mysqli_query($bdd,$req);
										$NbSurveillance=mysqli_num_rows($resultSurveillance);
										
										//Rechercher si un historique de date de surveillance existe
										$req="SELECT Id 
										FROM new_competences_relation_surveillance 
										WHERE Id_Relation=".$LigneQualification['Id_Relation'];
										$resultSurveillance2=mysqli_query($bdd,$req);
										$NbSurveillance2=mysqli_num_rows($resultSurveillance2);
										
										//Rechercher si une attestation existe pour cette qualif
										if(($LigneQualification['Id_Besoin']>0 || $NbSurveillance>0 || $NbSurveillance2>0 || $LigneQualification['AttestationFormation']<>"") && $_SESSION['PartieFormation']>1)
										{
											echo "<td width='15'>";
											echo "<img style='cursor:pointer;' onclick='OuvrirDossier(".$LigneQualification['Id_Relation'].")' width='15px' src='../../Images/dossier.jpg'>";
											echo "</td>";
										}
										echo $CodeHTMLBoutonPlusCompetencesIdentiques;									
									?>
								
								<td width="15" colspan="3"><?php if($LigneQualification['Visible']==1){echo "M";} ?></td>
							</tr>
						<?php
							}
							
				        ?>
						</table>
						</td>
						<td valign="top">
							<?php
								if($nbenreg>5)
								{
								echo '<img id="Image_PlusMoins_Qualification_'.$Ligne_Categorie_Maitre['Libelle'].'" src="../../Images/Moins.gif" onclick="javascript:Affiche_Masque(\'Qualification_'.$Ligne_Categorie_Maitre['Libelle'].'\');">';
								echo '<script>Affiche_Masque("Qualification_'.$Ligne_Categorie_Maitre['Libelle'].'");</script>';
								}
							?>
						</td>
						</tr>
				<?php
				}
				?>
			</table>
		</td>
	</tr>
</table>
<?php
}
}

?>
</body>
</html>
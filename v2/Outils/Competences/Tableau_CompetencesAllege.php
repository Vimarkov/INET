<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Compétences - Tableau des compétences</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" type="text/javascript" >
		function showTooltip(div, title, desc)
		{
		 div.style.display = 'inline';
		 div.style.position = 'absolute';
		 div.style.width = '350';
		 div.style.backgroundColor = '#EFFCF0';
		 div.style.border = 'dashed 1px black';
		 div.style.padding = '10px';
		 div.innerHTML = '<b>' + title + '</b><div style="text-align:left; padding-left:10; padding-right:5">' + desc + '</div>';
		}
		 
		function hideTooltip(div)
		{
		 div.style.display = 'none';
		}
		function OuvreFenetreProfil(Mode,Id)
			{window.open("Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1040,height=800");}
		function Modif_Competence(Id_Personne,Id_CategorieMaitre,Id_Relation,Type,Id_Prestation,Id_Categorie,Id_Qualif){
			if(Id_Relation>0){
				window.open("Ajout_Profil_Qualification2.php?Mode=Modif&Id_Categorie_Maitre="+Id_CategorieMaitre+"&Id_Personne="+Id_Personne+"&Id="+Id_Relation+"&Type="+Type+"&Id_Prestation="+Id_Prestation+"&Id_Qualif=","PageQualif","status=no,menubar=no,scrollbars=yes,width=1000,height=500");
			}
			else{
				window.open("Ajout_Profil_Qualification2.php?Mode=Ajout&Id_Categorie_Maitre="+Id_CategorieMaitre+"&Id_Personne="+Id_Personne+"&Id="+Id_Categorie+"&Type="+Type+"&Id_Prestation="+Id_Prestation+"&Id_Qualif="+Id_Qualif+"","PageQualif","status=no,menubar=no,scrollbars=yes,width=1000,height=500");
			}
		}
	</script>
	<style>
		th.En_Tete_Cellule_Tableau_Competence{text-align:center;color:#000000;font-size:11px;background-color:#A9D0F5;border:1px #003333 solid;vertical-align:top;width:15px;}
		th.En_Tete_Cellule_Tableau_Competence0{text-align:center;color:#000000;font-size:11px;background-color:#A9D0F5;border:1px #003333 solid;vertical-align:top;width:15px;}
		th.En_Tete_Cellule_Tableau_Competence1{text-align:center;color:#FFFFFF;font-size:11px;background-color:#999999;border:1px #003333 solid;vertical-align:top;width:15px;}
		th.Cellule_Tableau_Competence{border:1px #0066CC solid;text-align:center;}
		tbody{
			overflow-x: hidden;             /* esthétique */
			overflow-y: auto;               /* permet de scroller les cellules */
		}
		tbody th {
		  position: -webkit-sticky; /* for Safari */
		  position: sticky;
		  left: 0;
		}
	</style>
	
	<script src="../JS/jquery-3.4.0.min.js"></script>	
	<script src="../JS/jquery.stickytableheaders.min.js"></script>
	<script type="text/javascript">
	$(function() {
	  $("table").stickyTableHeaders();
	});
	</script>
</head>

<?php
require("../Connexioni.php");
require_once("../Formation/Globales_Fonctions.php");
require_once("../Fonctions.php");

$Droits="Aucun";
if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))	
)
{
	$Droits="Administrateur";
}
elseif(DroitsPlateforme(array($IdPosteResponsableQualite,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteReferentQualiteSysteme))
|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
)
{
	$Droits="Ecriture";
}
$DroitsModifPrestation=EstPresent_HierarchiePrestation();

$Plateforme_Identique=false;
$AfficherStamp=0;
$AfficherBadge=0;
$AfficherEtatQualif=0;
$Filiale=0;
if($_GET['Type']=="Prestation")
{
	$Requetes_Titre="SELECT Libelle, Code_Analytique, Id_Plateforme, AfficherBadgeStamp, AfficherBadge FROM new_competences_prestation WHERE Id=".$_GET['Id'];
	$Result_Titre=mysqli_query($bdd,$Requetes_Titre);
	$Ligne_Titre=mysqli_fetch_array($Result_Titre);
	$ResultLogo=mysqli_query($bdd,"SELECT Logo FROM new_competences_plateforme WHERE Id=".$Ligne_Titre[2]);
	$LigneLogo=mysqli_fetch_array($ResultLogo);
	$Logo=$LigneLogo[0];
	if(isset($_SESSION['Id_Plateformes']))
	{
		foreach($_SESSION['Id_Plateformes'] as &$value)
		{
			if($Ligne_Titre[2]==$value){$Plateforme_Identique=true;}
		}
	}
	if($Ligne_Titre['AfficherBadgeStamp']==1){$AfficherStamp=1;}
	if($Ligne_Titre['AfficherBadge']==1){$AfficherBadge=1;}
	if($Ligne_Titre['Id_Plateforme']==7 || $Ligne_Titre['Id_Plateforme']==12 || $Ligne_Titre['Id_Plateforme']==16
	|| $Ligne_Titre['Id_Plateforme']==18 || $Ligne_Titre['Id_Plateforme']==20 || $Ligne_Titre['Id_Plateforme']==22
	|| $Ligne_Titre['Id_Plateforme']==26 || $Ligne_Titre['Id_Plateforme']==30){$Filiale=1;}
}
elseif($_GET['Type']=="Plateforme")
{
	$Requetes_Titre="SELECT Libelle, Logo, Id FROM new_competences_plateforme WHERE Id=".$_GET['Id'];
	$Result_Titre=mysqli_query($bdd,$Requetes_Titre);
	$Ligne_Titre=mysqli_fetch_array($Result_Titre);
	$Logo=$Ligne_Titre[1];
	if(isset($_SESSION['Id_Plateformes']))
	{
		foreach($_SESSION['Id_Plateformes'] as &$value)
		{
			if($_GET['Id']==$value){$Plateforme_Identique=true;}
		}
	}
	if($Ligne_Titre['Id']==7 || $Ligne_Titre['Id']==12 || $Ligne_Titre['Id']==16
	|| $Ligne_Titre['Id']==18 || $Ligne_Titre['Id']==20 || $Ligne_Titre['Id']==22
	|| $Ligne_Titre['Id']==26 || $Ligne_Titre['Id']==30){$Filiale=1;}
}
elseif($_GET['Type']=="Pole")
{
	$Requetes_Titre="SELECT new_competences_prestation.Libelle, new_competences_prestation.Code_Analytique, 
					new_competences_prestation.Id_Plateforme, 
					new_competences_prestation.AfficherBadgeStamp,
					new_competences_prestation.AfficherBadge,
					new_competences_pole.Libelle AS Pole
					FROM new_competences_pole
					LEFT JOIN new_competences_prestation 
					ON new_competences_prestation.Id=new_competences_pole.Id_Prestation
					WHERE new_competences_pole.Id =".$_GET['Id']." ";
	$Result_Titre=mysqli_query($bdd,$Requetes_Titre);
	$Ligne_Titre=mysqli_fetch_array($Result_Titre);
	$ResultLogo=mysqli_query($bdd,"SELECT Logo FROM new_competences_plateforme WHERE Id=".$Ligne_Titre[2]);
	$LigneLogo=mysqli_fetch_array($ResultLogo);
	$Logo=$LigneLogo[0];
	if(isset($_SESSION['Id_Plateformes']))
	{
		foreach($_SESSION['Id_Plateformes'] as &$value)
		{
			if($Ligne_Titre[2]==$value){$Plateforme_Identique=true;}
		}
	}
	if($Ligne_Titre['AfficherBadgeStamp']==1){$AfficherStamp=1;}
	if($Ligne_Titre['AfficherBadge']==1){$AfficherBadge=1;}
	if($Ligne_Titre['Id_Plateforme']==7 || $Ligne_Titre['Id_Plateforme']==12 || $Ligne_Titre['Id_Plateforme']==16
	|| $Ligne_Titre['Id_Plateforme']==18 || $Ligne_Titre['Id_Plateforme']==20 || $Ligne_Titre['Id_Plateforme']==22
	|| $Ligne_Titre['Id_Plateforme']==26 || $Ligne_Titre['Id_Plateforme']==30){$Filiale=1;}
}
?>

<table style="border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="TableCompetences" style="margin-bottom:100px;margin-right:380px; border-spacing:0;">
			<?php
				if($_GET['Type']=="Prestation")
				{
					$Requetes_Liste_Qualifs="
						SELECT
							new_competences_qualification.Id,
							new_competences_qualification.Id_Categorie_Qualification,
							new_competences_qualification.Libelle,
							new_competences_categorie_qualification.Id_Categorie_Maitre
						FROM
							new_competences_qualification
							LEFT JOIN
								new_competences_prestation_qualification ON new_competences_qualification.Id=new_competences_prestation_qualification.Id_Qualification
							LEFT JOIN
								new_competences_categorie_qualification ON new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
						WHERE
							new_competences_prestation_qualification.Id_Prestation=".$_GET['Id'];
				}
				elseif($_GET['Type']=="Plateforme")
				{
					$Requetes_Liste_Qualifs="
						SELECT
							DISTINCT new_competences_qualification.Id,
							new_competences_qualification.Id_Categorie_Qualification,
							new_competences_qualification.Libelle,
							new_competences_categorie_qualification.Id_Categorie_Maitre
						FROM
							new_competences_qualification
							LEFT JOIN
								new_competences_prestation_qualification ON new_competences_qualification.Id=new_competences_prestation_qualification.Id_Qualification
							LEFT JOIN
								new_competences_categorie_qualification ON new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
						WHERE
							new_competences_prestation_qualification.Id_Prestation IN
							(
								SELECT
									Id
								FROM
									new_competences_prestation
								WHERE
									Id_Plateforme=".$_GET['Id']."
							)";
				}
				elseif($_GET['Type']=="Pole")
				{
					$Requetes_Liste_Qualifs="
						SELECT
							new_competences_qualification.Id,
							new_competences_qualification.Id_Categorie_Qualification,
							new_competences_qualification.Libelle,
							new_competences_categorie_qualification.Id_Categorie_Maitre
						FROM
							new_competences_qualification
							LEFT JOIN
								new_competences_pole_qualification ON new_competences_qualification.Id=new_competences_pole_qualification.Id_Qualification
							LEFT JOIN new_competences_categorie_qualification ON new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
						WHERE
							new_competences_pole_qualification.Id_Pole = ".$_GET['Id']." 
							AND new_competences_pole_qualification.Id_Qualification IN (
								SELECT Id_Qualification
								FROM new_competences_prestation_qualification
								WHERE Id_Prestation IN (SELECT Id_Prestation FROM new_competences_pole WHERE Id=".$_GET['Id'].")
							)
							";
						
				}
				$Requetes_Liste_Qualifs.="
					ORDER BY
						new_competences_categorie_qualification.Libelle ASC,
						new_competences_qualification.Libelle ASC";
				$Result_Liste_Qualification=mysqli_query($bdd,$Requetes_Liste_Qualifs);
				
				$nbenreg=mysqli_num_rows($Result_Liste_Qualification);
				if($nbenreg>0)
				{
			?>
				<tr style="background-color:#ffffff;">
					<th <?php if($AfficherStamp==1 && $AfficherBadge==1){echo 'colspan="5"';}else{if($_GET['Type']=="Plateforme" && $AfficherStamp==1){echo 'colspan="5"';}else{echo 'colspan="4"';}}?> rowspan="3" align="center">
						<table width="100%">
							<tr>
								<td width="20%" rowspan="3">
									<img src="../../Images/Logos/Logo Daher_posi.png" width="148">
									<?php 
									if($Logo<>""){
										echo "<img src='../../Images/Logos/".$Logo."' width='148'>"; 
									}
									?>
								</td>
								<td bgcolor="#00325F" style="color:#ffffff;font-size:20px;" align="center" width="60%" rowspan="3">
									<font style="text-decoration=underline; font-weight=bold;"><?php if($Filiale==0){echo "TABLEAU DES COMPETENCES";}else{echo "COMPETENCY LIST";}?></font>
								</td>
								<td width="20%">D-0731</td>
							</tr>
							<tr>
								<td width="20%"><?php if($Filiale==0){
									echo "Trame Version 2";
								}
								else{
									echo "Template issue 2";
								}
								?></td>
							</tr>
							<tr>
								<td width="20%"><?php if($Filiale==0){
									echo "20-Fév. 2024";
								}
								else{
									echo "Feb. 20, 2024";
								}
								?></td>
							</tr>
						</table>
						<br>
						<?php
						if($Filiale==0){
							$MoisLettre = array("Janv.", "Févr.", "Mar.", "Avr.", "Mai", "Juin", "Juil.", "Aoû.", "Sept.", "Oct.", "Nov.", "Déc.");
						}
						else
						{
							$MoisLettre = array("Jan.", "Feb.", "Mar.", "Apr.", "May", "Jun.", "Jul.", "Aug.", "Sept.", "Oct.", "Nov.", "Dec.");
						}

						if($Filiale==0){
							echo "Contenu mis à jour le : ".date('d-').$MoisLettre[date('m')-1].date(' Y');
						}
						else{
							echo "Content updated on : ".$MoisLettre[date('m')-1].date(' d, Y');
						}
						
						echo "<br>";
						if($Filiale==1)
						{
							if($_GET['Type']=="Prestation"){echo "<br><br><b>Prestation : </b>".$Ligne_Titre[0]." - ".$Ligne_Titre[1];}
							elseif($_GET['Type']=="Plateforme"){echo "<br><br><b>Unité d'exploitation : </b>".$Ligne_Titre[0];}
							elseif($_GET['Type']=="Pole"){echo "<br><br><b>Pôle : </b>".$Ligne_Titre['Libelle']." ( ".$Ligne_Titre['Pole'].") - ".$Ligne_Titre['Code_Analytique'];}
						}
						else
						{
							if($_GET['Type']=="Prestation"){echo "<br><br><b>Activity : </b>".$Ligne_Titre[0]." - ".$Ligne_Titre[1];}
							elseif($_GET['Type']=="Plateforme"){echo "<br><br><b>Operating unit : </b>".$Ligne_Titre[0];}
							elseif($_GET['Type']=="Pole"){echo "<br><br><b>Pole : </b>".$Ligne_Titre[0]." ( ".$Ligne_Titre['Pole'].") - ".$Ligne_Titre['Code_Analytique'];}
						}
						?>
						<br><br>
						<img src="../../Images/Legende_GPEC2.png">
					</th>
					<?php
					$Result_Liste_Qualification=mysqli_query($bdd,$Requetes_Liste_Qualifs);
					$Derniere_Categorie=0;
					$Affiche_Categorie=0;
					$Nb_Qualification_Categorie=1;
					while($Ligne_Liste_Qualification=mysqli_fetch_array($Result_Liste_Qualification))
					{
						if($Derniere_Categorie!=$Ligne_Liste_Qualification['Id_Categorie_Qualification'])
						{
							if($Derniere_Categorie!=0)
							{
								$Requete_Categorie="SELECT Libelle FROM new_competences_categorie_qualification WHERE Id=".$Derniere_Categorie;
								$Result_Categorie=mysqli_query($bdd,$Requete_Categorie);
								$Ligne_Categorie=mysqli_fetch_array($Result_Categorie);
								echo "<th class='En_Tete_Cellule_Tableau_Competence".$Affiche_Categorie."' colspan='".$Nb_Qualification_Categorie."'>".$Ligne_Categorie[0]."</th>";
								$Nb_Qualification_Categorie=1;
							}
							if($Affiche_Categorie==0){$Affiche_Categorie=1;}else{$Affiche_Categorie=0;}
							$Derniere_Categorie=$Ligne_Liste_Qualification['Id_Categorie_Qualification'];
						}
						else
						{
							$Nb_Qualification_Categorie+=1;
						}
					}
					$Nb_Qualification_Categorie+=1;
					$Requete_Categorie="SELECT Libelle FROM new_competences_categorie_qualification WHERE Id=".$Derniere_Categorie;
					$Result_Categorie=mysqli_query($bdd,$Requete_Categorie);
					$Ligne_Categorie=mysqli_fetch_array($Result_Categorie);
					echo "<th class='En_Tete_Cellule_Tableau_Competence".$Affiche_Categorie."' colspan='".$Nb_Qualification_Categorie."'>".$Ligne_Categorie[0]."</th>";
					?>
				</tr>
				<tr>
					<?php
					$Affiche_Categorie=0;
					$Derniere_Categorie=0;
					$Result_Liste_Qualification=mysqli_query($bdd,$Requetes_Liste_Qualifs);
					while($Ligne_Liste_Qualification=mysqli_fetch_array($Result_Liste_Qualification))
					{
						if($Derniere_Categorie!=$Ligne_Liste_Qualification['Id_Categorie_Qualification'])
						{
							if($Affiche_Categorie==0){$Affiche_Categorie=1;}else{$Affiche_Categorie=0;}
							$Derniere_Categorie=$Ligne_Liste_Qualification['Id_Categorie_Qualification'];
						}
						echo "<th class='En_Tete_Cellule_Tableau_Competence".$Affiche_Categorie."'>\n";
						echo "<span class='clsVertTB'>".str_replace("'"," ",$Ligne_Liste_Qualification['Libelle'])."</span>";
						echo "</th>\n";
					}
					?>
				</tr>
				<?php
					if($_GET['Type']=="Prestation")
					{
						$Requete_Liste_PersonneMilieu="
							LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id
							LEFT JOIN new_competences_prestation ON new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
						WHERE
							new_competences_prestation.Id=".$_GET['Id']."
							AND new_competences_personne_prestation.Date_Debut <='".$DateJour."'
							AND new_competences_personne_prestation.Date_Fin>='".$DateJour."'";
					}
					elseif($_GET['Type']=="Plateforme")
					{
						$Requete_Liste_PersonneMilieu="
							LEFT JOIN new_competences_personne_plateforme ON new_competences_personne_plateforme.Id_Personne=new_rh_etatcivil.Id
							LEFT JOIN new_competences_plateforme ON new_competences_personne_plateforme.Id_Plateforme=new_competences_plateforme.Id
						WHERE
							new_competences_plateforme.Id=".$_GET['Id'];
					}
					elseif($_GET['Type']=="Pole")
					{
						$Requete_Liste_PersonneMilieu="
							LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id
							LEFT JOIN new_competences_prestation ON new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
						WHERE
							new_competences_personne_prestation.Id_Pole=".$_GET['Id']."
							AND new_competences_personne_prestation.Date_Debut <='".$DateJour."'
							AND new_competences_personne_prestation.Date_Fin>='".$DateJour."'";
					}
					$Requete_Liste_Personne="
						SELECT
							DISTINCT new_rh_etatcivil.Id
						FROM
							new_rh_etatcivil".
						$Requete_Liste_PersonneMilieu."
						ORDER BY
							new_rh_etatcivil.Nom ASC,
							new_rh_etatcivil.Prenom ASC";
					$Result_Liste_Personne=mysqli_query($bdd,$Requete_Liste_Personne);
					
					$Couleur="#EEEEEE";
					while($Ligne_Liste_Personne=mysqli_fetch_array($Result_Liste_Personne))
					{
						//Personne	//MAJ DU 27/12/12
						$Nom="";
						$Prenom="";
						$NumBadge="";
						$Stamp="";
						$requete_etatcivil="SELECT Nom, Prenom,NumBadge FROM new_rh_etatcivil WHERE Id=".$Ligne_Liste_Personne[0];
						$result_etatcivil=mysqli_query($bdd,$requete_etatcivil);
						$row_etatcivil=mysqli_fetch_array($result_etatcivil);
						$Nom=$row_etatcivil['Nom'];
						$Prenom=$row_etatcivil['Prenom'];
						$NumBadge=$row_etatcivil['NumBadge'];
						
						$req="SELECT Num_Stamp, Scope FROM new_competences_personne_stamp WHERE Id_Personne=".$Ligne_Liste_Personne[0]." AND (Date_Debut<='0001-01-01' OR (Date_Debut<='".date('Y-m-d')."' AND (Date_Fin>='".date('Y-m-d')."' OR Date_Fin<='0001-01-01'))) ";
						$result_stamp=mysqli_query($bdd,$req);
						$nbenreg_stamp=mysqli_num_rows($result_stamp);
						if($nbenreg_stamp>0)
						{
							while($row_stamp=mysqli_fetch_array($result_stamp)){
								if($Stamp<>""){$Stamp.="<br>";}
								$Stamp.=$row_stamp['Num_Stamp']." # ".$row_stamp['Scope'];
							}
						}
						
						//Prestation
						$PRESTATION="";
						$requete_prestation="
							SELECT
								new_competences_prestation.Libelle
							FROM
								new_competences_prestation
								LEFT JOIN new_competences_personne_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
							WHERE
								new_competences_personne_prestation.Id_Personne=".$Ligne_Liste_Personne[0]."
								AND new_competences_personne_prestation.Date_Debut <='".$DateJour."'
								AND new_competences_personne_prestation.Date_Fin>='".$DateJour."'";
						$result_prestation=mysqli_query($bdd,$requete_prestation);
						$nbenreg_prestation=mysqli_num_rows($result_prestation);
						if($nbenreg_prestation>0)
							{while($row_prestation=mysqli_fetch_array($result_prestation)){if($PRESTATION==""){$PRESTATION=$row_prestation[0];}else{$PRESTATION.="<br>".$row_prestation[0];}}}
					
						//Metier
						$METIER="";
						$requete_metier="
							SELECT
								new_competences_metier.Libelle FROM new_competences_metier
								LEFT JOIN new_competences_personne_metier ON new_competences_metier.Id=new_competences_personne_metier.Id_Metier
							WHERE
								new_competences_personne_metier.Id_Personne=".$Ligne_Liste_Personne[0]."
                                AND Futur=0
							ORDER BY
								new_competences_personne_metier.Id DESC
							LIMIT 1";
						$result_metier=mysqli_query($bdd,$requete_metier);
						$nbenreg_metier=mysqli_num_rows($result_metier);
						if($nbenreg_metier>0)
							{while($row_metier=mysqli_fetch_array($result_metier)){if($METIER==""){$METIER=$row_metier[0];}else{$METIER.="<br>".$row_metier[0];}}}
					
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
				?>
				<tbody>
				<tr bgcolor="<?php echo $Couleur;?>">
					<th class="Cellule_Tableau_Competence" colspan="2" bgcolor="<?php echo $Couleur;?>">
						<?php
							if(($Droits=="Ecriture" && $Plateforme_Identique) || $Droits=="Administrateur")
								{echo "<a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Modif\",\"".$Ligne_Liste_Personne[0]."\");'>".$Nom." ".$Prenom."</a>";}
							elseif($DroitsModifPrestation && $Plateforme_Identique)
								{echo "<a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"ModifPresta\",\"".$Ligne_Liste_Personne[0]."\");'>".$Nom." ".$Prenom."</a>";}
							else
								{echo "<a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$Ligne_Liste_Personne[0]."\");'>".$Nom." ".$Prenom."</a>";}
						?>
					</th>
					<?php
						if($AfficherBadge==1){
					?>
					<td class="Cellule_Tableau_Competence"><?php echo $NumBadge;?></td>
					<?php
						}
						if($AfficherStamp==1){
					?>
					<td class="Cellule_Tableau_Competence"><?php echo $Stamp;?></td>
					<?php
						}
					?>
					<td <?php if($_GET['Type']=="Prestation" || $_GET['Type']=="Pole"){if($AfficherStamp==0 && $AfficherBadge==0){echo "colspan='2'";}}?> class="Cellule_Tableau_Competence"><?php if(strrpos($METIER,"/")){echo substr($METIER,strripos($METIER,"/")+2);}else{echo $METIER;}?></td>
					<?php
					if($_GET['Type']=="Plateforme")
					{
					?>
					<td class="Cellule_Tableau_Competence" width=60><?php echo $PRESTATION;?></td>
					<?php
					}
						$Requete_Ligne_Qualifications="
							SELECT
								*
							FROM
								(
								SELECT
									Id,
									Evaluation,
									Date_Debut,
									Date_Fin,
									Resultat_QCM,
									Date_QCM,
									Date_Surveillance,
									Id_Qualification_Parrainage,
									(
										SELECT
											new_competences_qualification.Libelle
										FROM
											new_competences_qualification
										WHERE
											new_competences_qualification.Id=new_competences_relation.Id_Qualification_Parrainage
									) AS LibelleQualif,
									Id_Besoin,
									Sans_Fin,(@row_number:=@row_number + 1) AS rnk
									FROM new_competences_relation
								WHERE
									Id_Personne=".$Ligne_Liste_Personne[0]."
									AND Type='Qualification'
                                    AND Evaluation <> ''
									AND new_competences_relation.Suppr=0 
									AND new_competences_relation.Visible=0
									AND (Date_Fin>='".$DateJour."' OR Date_Fin<='0001-01-01' OR Sans_Fin='Oui')
								ORDER BY
									Date_QCM DESC
								) AS Toto
							 GROUP BY
								Toto.Id_Qualification_Parrainage";
						$Result_Ligne_Qualifications=mysqli_query($bdd,$Requete_Ligne_Qualifications);
						
						//Affichage de la ligne des compétences
						mysqli_data_seek($Result_Liste_Qualification,0);
						while($Ligne_Liste_Qualification=mysqli_fetch_array($Result_Liste_Qualification))
						{	
							echo "<td class='Cellule_Tableau_Competence' ";
							$test = 0;
							if (mysqli_num_rows($Result_Ligne_Qualifications) > 0)
							{
								mysqli_data_seek($Result_Ligne_Qualifications,0);
								while($Ligne_Qualifications=mysqli_fetch_array($Result_Ligne_Qualifications))
								{
									if($Ligne_Qualifications[7] == $Ligne_Liste_Qualification[0])
									{
										if(($Droits=="Ecriture" && $Plateforme_Identique) || $Droits=="Administrateur"){
											if($Ligne_Qualifications['Id_Besoin']==0){
												echo " onclick=\"Modif_Competence(".$Ligne_Liste_Personne[0].",".$Ligne_Liste_Qualification['Id_Categorie_Maitre'].",".$Ligne_Qualifications['Id'].",'".$_GET['Type']."',".$_GET['Id'].",0,0)\"";
											}
										}
										$test = 1;
										if($Ligne_Qualifications[1] == "L"){$Couleur='#FFFF00';}
										elseif($Ligne_Qualifications[1] == "X" || $Ligne_Qualifications[1] == "S"){$Couleur='#0099FF';}
										elseif($Ligne_Qualifications[1] == "Q" || $Ligne_Qualifications[1] == "Q1" || $Ligne_Qualifications[1] == "Q2" || $Ligne_Qualifications[1] == "Q3"){$Couleur='#00FF00';}
										elseif($Ligne_Qualifications[1] == "T"){$Couleur='#AAAAAA';}
										elseif($Ligne_Qualifications[1] == "B" || $Ligne_Qualifications[1] == "Bi"){$Couleur='#F5A81D';}
										elseif($Ligne_Qualifications[1] == "V"){$Couleur='#DE63FA';}
										elseif($Ligne_Qualifications[1] == "Low" || $Ligne_Qualifications[1] == "Medium" || $Ligne_Qualifications[1] == "High"){$Couleur='#AAAAAA';}
										
										//Demande modification affichage compétences arrivant à échéances - 16.11.2021 MCAROUX
										if($Ligne_Qualifications[3]>"0001-01-01" && $Ligne_Qualifications['Sans_Fin']=="Non" && $Ligne_Qualifications[3]<=date("Y-m-d", strtotime("+2 month")) && !in_array($Ligne_Qualifications[1],array("B","Bi","Low","Medium","High"))){$Couleur="#ff5050";}
										
										echo " bgcolor='".$Couleur."'>";
										
										echo "<span onMouseOut=\"hideTooltip(t".str_replace("'"," ",$Ligne_Qualifications[0]).")\"";
										echo " onMouseOver=\"showTooltip(t".str_replace("'"," ",$Ligne_Qualifications[0]).", 'Infos', '";
										echo "Personne : ".$Nom." ".$Prenom."";
										echo "<br>Qualification : ".str_replace("'"," ",str_replace('"',' ',$Ligne_Qualifications['LibelleQualif']))."";
										echo "<br>Date début : ".$Ligne_Qualifications[2];
										echo "<br>Date fin : ".$Ligne_Qualifications[3];
										echo "<br>% QCM : ".$Ligne_Qualifications[4];
										echo "<br>Date QCM : ".$Ligne_Qualifications[5];
										echo "<br>Date Surv. : ".$Ligne_Qualifications[6];
										echo "') \">";
										
										$Lettre=$Ligne_Qualifications[1];
										if($Ligne_Qualifications[1]=="Low"){$Lettre="L";}
										elseif($Ligne_Qualifications[1]=="Medium"){$Lettre="M";}
										elseif($Ligne_Qualifications[1]=="High"){$Lettre="H";}
										
										echo $Lettre;
										echo "</span><div style='display:none' id=\"t".$Ligne_Qualifications[0]."\"></div>";
										break;
									}
								}
							}
							if ($test == 0) 
							{
								if(($Droits=="Ecriture" && $Plateforme_Identique) || $Droits=="Administrateur"){
									echo " onclick=\"Modif_Competence(".$Ligne_Liste_Personne[0].",".$Ligne_Liste_Qualification['Id_Categorie_Maitre'].",0,'".$_GET['Type']."',".$_GET['Id'].",".$Ligne_Liste_Qualification['Id_Categorie_Qualification'].",".$Ligne_Liste_Qualification['Id'].")\"";
								}
								echo ">";
								echo "<span onMouseOut=\"hideTooltip(t".str_replace("'"," ",$Ligne_Liste_Qualification[0].$Ligne_Liste_Personne[0]).")\"";
								echo " onMouseOver=\"showTooltip(t".str_replace("'"," ",$Ligne_Liste_Qualification[0].$Ligne_Liste_Personne[0]).", 'Infos', '";
								echo "<br>Qualification : ".str_replace("'"," ",str_replace('"',' ',$Ligne_Liste_Qualification['Libelle']))."";
								echo "') \">";
								echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
								echo "</span><div style='display:none' id=\"t".$Ligne_Liste_Qualification[0].$Ligne_Liste_Personne[0]."\"></div>";
							}
							echo "</td>\n";
							
						}
					?>
				</tr>
				<?php
					}	//Fin boucle
					
				}		//Fin If
					mysqli_free_result($Result_Liste_Qualification);	// Libération des résultats
				?>
				</tbody>
			</table>
		</td>
	</tr>
	<tr><td height="40"></td></tr>
	<?php 
	if($Filiale==0){
	?>
	<tr>
		<td>
			<table style="width:100%;">
				<tr>
					<td align="center"><font size=1>DOCUMENT QUALITE DIS - Reproduction interdite sans autorisation écrite de DIS</font></td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
	}
	else{
	?>
	<tr>
		<td>
			<table style="width:100%;">
				<tr>
					<td align="center"><font size=1>DIS QUALITY DOCUMENT - Reproduction forbidden without written authorization from DIS</font></td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
	}
	?>
</table>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
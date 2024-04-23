<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Compétences - Tableau des formations</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" type="text/javascript" >
		function OuvreFenetreProfil(Mode,Id)
			{window.open("Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1040,height=800");}
		function Modif_Competence(Id,Id_Personne,Type,Id_Prestation,Id_Formation){
			if(Id>0){
				window.open("Ajout_Profil_Formation2.php?Mode=Modif&Id="+Id+"&Id_Personne="+Id_Personne+"&Type="+Type+"&Id_Prestation="+Id_Prestation+"&Id_Formation="+Id_Formation,"PageQualif","status=no,menubar=no,scrollbars=yes,width=1000,height=500");
			}
			else{
				window.open("Ajout_Profil_Formation2.php?Mode=Ajout&Id="+Id+"&Id_Personne="+Id_Personne+"&Type="+Type+"&Id_Prestation="+Id_Prestation+"&Id_Formation="+Id_Formation,"PageQualif","status=no,menubar=no,scrollbars=yes,width=1000,height=500");
			}
		}
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
if($_GET['Type']=="Prestation")
{
	$Requetes_Titre="SELECT Libelle, Code_Analytique, Id_Plateforme FROM new_competences_prestation WHERE Id=".$_GET['Id'];
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
}
elseif($_GET['Type']=="Plateforme")
{
	$Requetes_Titre="SELECT Libelle, Logo FROM new_competences_plateforme WHERE Id=".$_GET['Id'];
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
}
elseif($_GET['Type']=="Pole")
{
	$Requetes_Titre="SELECT Libelle, Code_Analytique, Id_Plateforme FROM new_competences_prestation WHERE Id IN (SELECT Id_Prestation FROM new_competences_pole WHERE Id=".$_GET['Id'].")";
	$Result_Titre=mysqli_query($bdd,$Requetes_Titre);
	$Ligne_Titre=mysqli_fetch_array($Result_Titre);
	$ResultLogo=mysqli_query($bdd,"SELECT Logo FROM new_competences_plateforme WHERE Id=".$Ligne_Titre[2]);
	$LigneLogo=mysqli_fetch_array($ResultLogo);
	$Logo="";
	if($LigneLogo[0]<>""){
		$Logo=$LigneLogo[0];
	}
	if(isset($_SESSION['Id_Plateformes']))
	{
		foreach($_SESSION['Id_Plateformes'] as &$value)
		{
			if($Ligne_Titre[2]==$value){$Plateforme_Identique=true;}
		}
	}
}
?>

<table style="border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="TableCompetences">
			<?php
				if($_GET['Type']=="Prestation")
				{
					$Requetes_Liste_Formations="SELECT DISTINCT new_competences_formation.*";
					$Requetes_Liste_Formations.=" FROM new_competences_prestation_formation, new_competences_formation";
					$Requetes_Liste_Formations.=" WHERE";
					$Requetes_Liste_Formations.=" new_competences_formation.Id=new_competences_prestation_formation.Id_Formation";
					$Requetes_Liste_Formations.=" AND new_competences_prestation_formation.Id_Prestation=".$_GET['Id'];
				}
				elseif($_GET['Type']=="Plateforme")
				{
					$Requetes_Liste_Formations="SELECT DISTINCT new_competences_formation.* FROM";
					$Requetes_Liste_Formations.="new_competences_personne_formation, new_rh_etatcivil, new_competences_formation";
					$Requetes_Liste_Formations.=", new_competences_plateforme, new_competences_personne_plateforme";
					$Requetes_Liste_Formations.=" WHERE";
					$Requetes_Liste_Formations.=" new_competences_personne_plateforme.Id_Personne=new_rh_etatcivil.Id";
					$Requetes_Liste_Formations.=" AND new_competences_personne_plateforme.Id_Plateforme=new_competences_plateforme.Id";
					$Requetes_Liste_Formations.=" AND new_competences_plateforme.Id=".$_GET['Id'];
					$Requetes_Liste_Formations.=" AND new_competences_personne_formation.Id_Personne=new_rh_etatcivil.Id";
				}
				elseif($_GET['Type']=="Pole")
				{
					$Requetes_Liste_Formations="SELECT DISTINCT new_competences_formation.*";
					$Requetes_Liste_Formations.=" FROM new_competences_prestation_formation, new_competences_formation, new_competences_pole";
					$Requetes_Liste_Formations.=" WHERE";
					$Requetes_Liste_Formations.=" new_competences_formation.Id=new_competences_prestation_formation.Id_Formation";
					$Requetes_Liste_Formations.=" AND new_competences_prestation_formation.Id_Prestation=new_competences_pole.Id_Prestation";
					$Requetes_Liste_Formations.=" AND new_competences_pole.Id=".$_GET['Id'];
				}
				$Requetes_Liste_Formations.=" ORDER BY new_competences_formation.Libelle ASC";
				//echo $Requetes_Liste_Formations;
				$Result_Liste_Formation=mysqli_query($bdd,$Requetes_Liste_Formations);
				$nbenreg=mysqli_num_rows($Result_Liste_Formation);
				if($nbenreg>0)
				{
			?>
				<tr>
					<td colspan="4" rowspan="2" align="center">
						<img src="../../Images/Logos/Logo.gif" height="75" width="148">
						<?php echo "<img src='../../Images/Logos/".$Logo."'>"; ?>
						<br><br>
						<font style="text-decoration=underline; font-weight=bold;"><?php if($LangueAffichage=="FR"){echo "TABLEAU DES FORMATIONS";}else{echo "TRAINING TABLE";}?></font>
						<?php
						if($LangueAffichage=="FR")
						{
							if($_GET['Type']=="Prestation" || $_GET['Type']=="Pole"){echo "<br><br><b>Prestation : </b>".$Ligne_Titre[0]." - ".$Ligne_Titre[1];}
							elseif($_GET['Type']=="Plateforme"){echo "<br><br><b>Unité d'exploitation : </b>".$Ligne_Titre[0];}
							echo "<br><br><u>Mise à jour le  : </u>".$DateJour;
						}
						else
						{
							if($_GET['Type']=="Prestation" || $_GET['Type']=="Pole"){echo "<br><br><b>Activity : </b>".$Ligne_Titre[0]." - ".$Ligne_Titre[1];}
							elseif($_GET['Type']=="Plateforme"){echo "<br><br><b>Operating unit : </b>".$Ligne_Titre[0];}
							echo "<br><br><u>Updated on  : </u>".$DateJour;
						}
						?>
					</td>
				</tr>
				<tr>
					<?php
					$Result_Liste_Formation=mysqli_query($bdd,$Requetes_Liste_Formations);
					while($Ligne_Liste_Formation=mysqli_fetch_array($Result_Liste_Formation))
					{
						echo "<td class='En_Tete_Cellule_Tableau_Competence'>";
						echo "<div style='writing-mode:tb-rl; white-space: nowrap;'>".$Ligne_Liste_Formation['Libelle']."</div>";
					}
					?>
				</tr>
				<?php
					$Requete_Liste_Personne="SELECT DISTINCT new_rh_etatcivil.Id ";
					$Requete_Liste_Personne.="FROM new_rh_etatcivil";
					if($_GET['Type']=="Prestation"){$Requete_Liste_Personne.=", new_competences_prestation, new_competences_personne_prestation";}
					elseif($_GET['Type']=="Plateforme"){$Requete_Liste_Personne.=", new_competences_plateforme, new_competences_personne_plateforme";}
					elseif($_GET['Type']=="Pole"){$Requete_Liste_Personne.=", new_competences_prestation, new_competences_personne_prestation, new_competences_pole";}
					$Requete_Liste_Personne.=" WHERE";
					if($_GET['Type']=="Prestation"){$Requete_Liste_Personne.=" new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id AND new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id AND new_competences_prestation.Id=".$_GET['Id']." AND new_competences_personne_prestation.Date_Debut <='".$DateJour."' AND new_competences_personne_prestation.Date_Fin>='".$DateJour."'";}
					elseif($_GET['Type']=="Plateforme"){$Requete_Liste_Personne.=" new_competences_personne_plateforme.Id_Personne=new_rh_etatcivil.Id AND new_competences_personne_plateforme.Id_Plateforme=new_competences_plateforme.Id AND new_competences_plateforme.Id=".$_GET['Id'];}
					if($_GET['Type']=="Pole"){$Requete_Liste_Personne.=" new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id AND new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id AND new_competences_personne_prestation.Id_Pole=".$_GET['Id']." AND new_competences_personne_prestation.Date_Debut <='".$DateJour."' AND new_competences_personne_prestation.Date_Fin>='".$DateJour."'";}
					$Requete_Liste_Personne.=" ORDER BY ";
					if($_GET['Type']=="Prestation" || $_GET['Type']=="Pole"){$Requete_Liste_Personne.="new_competences_prestation.Libelle ASC,";}
					elseif($_GET['Type']=="Plateforme"){$Requete_Liste_Personne.="new_competences_plateforme.Libelle ASC,";}
					$Requete_Liste_Personne.="new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
					$Result_Liste_Personne=mysqli_query($bdd,$Requete_Liste_Personne);

					$Couleur="#EEEEEE";
					while($Ligne_Liste_Personne=mysqli_fetch_array($Result_Liste_Personne))
					{
						//Personne	//MAJ DU 27/12/12
						$Nom="";
						$Prenom="";
						$requete_etatcivil="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$Ligne_Liste_Personne[0];
						$result_etatcivil=mysqli_query($bdd,$requete_etatcivil);
						$row_etatcivil=mysqli_fetch_array($result_etatcivil);
						$Nom=$row_etatcivil[0];
						$Prenom=$row_etatcivil[1];
					
						//Prestation
						$PRESTATION="";
						$requete_prestation="SELECT DISTINCT new_competences_prestation.Libelle FROM new_competences_prestation, new_competences_personne_prestation";
						$requete_prestation.=" WHERE new_competences_personne_prestation.Id_Personne=".$Ligne_Liste_Personne[0]." AND new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation ";
						$requete_prestation.=" AND new_competences_personne_prestation.Date_Debut <='".$DateJour."' AND new_competences_personne_prestation.Date_Fin>='".$DateJour."'";
						$requete_prestation.=" ORDER By new_competences_prestation.Libelle ASC";
						$result_prestation=mysqli_query($bdd,$requete_prestation);
						$nbenreg_prestation=mysqli_num_rows($result_prestation);
						if($nbenreg_prestation>0)
							{while($row_prestation=mysqli_fetch_array($result_prestation)){if($PRESTATION==""){$PRESTATION=$row_prestation[0];}else{$PRESTATION.="<br>".$row_prestation[0];}}}
					
						//Metier
						$METIER="";
						$requete_metier="SELECT DISTINCT new_competences_metier.Libelle FROM new_competences_metier, new_competences_personne_metier";
						$requete_metier.=" WHERE new_competences_personne_metier.Id_Personne=".$Ligne_Liste_Personne[0]." AND new_competences_metier.Id=new_competences_personne_metier.Id_Metier ";
						$requete_metier.=" ORDER By new_competences_metier.Libelle ASC";
						$result_metier=mysqli_query($bdd,$requete_metier);
						$nbenreg_metier=mysqli_num_rows($result_metier);
						if($nbenreg_metier>0)
							{while($row_metier=mysqli_fetch_array($result_metier)){if($METIER==""){$METIER=$row_metier[0];}else{$METIER.="<br>".$row_metier[0];}}}
					
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
				?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td class="Cellule_Tableau_Competence" width="90"><?php 
						if(($Droits=="Ecriture" && $Plateforme_Identique) || $Droits=="Administrateur")
							{echo "<a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Modif\",\"".$Ligne_Liste_Personne[0]."\");'>".$Nom."</a>";}
						elseif($DroitsModifPrestation && $Plateforme_Identique)
							{echo "<a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"ModifPresta\",\"".$Ligne_Liste_Personne[0]."\");'>".$Nom."</a>";}
						else
							{echo "<a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$Ligne_Liste_Personne[0]."\");'>".$Nom."</a>";}
					?>
					</td>
					<td class="Cellule_Tableau_Competence" width="90"><?php echo $Prenom;?></td>
					<td <?php if($_GET['Type']=="Prestation" || $_GET['Type']=="Pole"){echo "colspan='2'";}?> class="Cellule_Tableau_Competence"><?php if(strrpos($METIER,"/")){echo substr($METIER,strripos($METIER,"/")+2);}else{echo $METIER;}?></td>
					<?php
					if($_GET['Type']=="Plateforme")
					{
					?>
					<td class="Cellule_Tableau_Competence" width="60"><?php echo $PRESTATION;?></td>
					<?php
					}
						//Affichage de la ligne des compétences
						$Result_Liste_Formation=mysqli_query($bdd,$Requetes_Liste_Formations);
						while($Ligne_Liste_Formation=mysqli_fetch_array($Result_Liste_Formation))
						{
							$TexteModif="";
							$Requete_Ligne_Formations="SELECT Id FROM new_competences_personne_formation WHERE Id_Personne=".$Ligne_Liste_Personne['Id']." AND Id_Formation=".$Ligne_Liste_Formation['Id'];
							$Result_Ligne_Formations=mysqli_query($bdd,$Requete_Ligne_Formations);
							$Nb_Result_Ligne_Formations=mysqli_num_rows($Result_Ligne_Formations);
							echo "<td class='Cellule_Tableau_Competence'";
							if($Nb_Result_Ligne_Formations>0){
								$Ligne_Formations=mysqli_fetch_array($Result_Ligne_Formations);
								echo " style='background-color:#009df8;' ";
								if(($Droits=="Ecriture" && $Plateforme_Identique) || $Droits=="Administrateur")
								{
									echo " onclick=\"Modif_Competence(".$Ligne_Formations['Id'].",".$Ligne_Liste_Personne['Id'].",'".$_GET['Type']."',".$_GET['Id'].",".$Ligne_Liste_Formation['Id'].")\" ";
								}
							}
							else{
								if(($Droits=="Ecriture" && $Plateforme_Identique) || $Droits=="Administrateur")
								{
									echo " onclick=\"Modif_Competence(0,".$Ligne_Liste_Personne['Id'].",'".$_GET['Type']."',".$_GET['Id'].",".$Ligne_Liste_Formation['Id'].")\" ";
								}
							}
								
							echo ">";
							if($Nb_Result_Ligne_Formations>0){echo "X";}
							
							echo "</td>";
						}
					?>
				</tr>
				<?php
					}	//Fin boucle
				}		//Fin If
					mysqli_free_result($Result_Liste_Formation);	// Libération des résultats
				?>
			</table>
		</td>
	</tr>
</table>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Compétences - D0701</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
</head>

<?php
require_once("../Connexioni.php");
require_once("../Fonctions.php");

//Logo
$Logo_Plateforme="";
$result=mysqli_query($bdd,"SELECT * FROM new_competences_plateforme WHERE Id IN (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$_GET['Id'].")");
$nbenreg=mysqli_num_rows($result);
if($nbenreg>0)
{
	$row=mysqli_fetch_array($result);
	$Logo_Plateforme=$row["Logo"];
}

$User="";
if(isset($_SESSION['Log']))
{
	$result=mysqli_query($bdd,"SELECT Prenom, Nom FROM new_rh_etatcivil WHERE Login='".$_SESSION['Log']."'");
	$row=mysqli_fetch_array($result);
	$User=$row[0]." ".$row[1];
}
?>

<table style="border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="TableCompetences">
				<tr>
					<td><img src="../../Images/Logos/Logo_Doc_Group.png"></td>
					<td bgcolor="#EEEEEE" class="TitreDQ" width="672" align="center" colspan=7>
						<?php
							if($LangueAffichage=="FR"){echo "E T A T &nbsp;&nbsp;&nbsp;&nbsp; D U &nbsp;&nbsp;&nbsp;&nbsp; P E R S O N N E L";}
							else{echo "P E R S O N N E L  &nbsp;&nbsp;&nbsp;&nbsp; S T A T E M E N T";}
						?>
					</td>
					<td><?php if($Logo_Plateforme != ""){echo "<img src='../../Images/Logos/".$Logo_Plateforme."'>"; }?></td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td class="Cellule_Tableau_Competence" colspan=2>
						<?php
							if($LangueAffichage=="FR"){echo "Mis à jour le : ";}
							else{echo "Update on : ";}
							echo $DateJour;
						?>
					</td>
					<td class="Cellule_Tableau_Competence" colspan=6>
						<?php
							if($LangueAffichage=="FR"){echo "Par : ";}
							else{echo "By : ";}
							echo $User;
						?>
					</td>
					<td class="Cellule_Tableau_Competence">Signature : </td>
				</tr>
				<tr bgcolor="#EEEEEE">
					<td class="Cellule_Tableau_Competence"><?php if($LangueAffichage=="FR"){echo "Nom Prénom";}else{echo "Name Firstname";}?></td>
					<td class="Cellule_Tableau_Competence"><?php if($LangueAffichage=="FR"){echo "Poste";}else{echo "Function";}?></td>
					<td class="Cellule_Tableau_Competence"><?php if($LangueAffichage=="FR"){echo "Date<br>d'entrée";}else{echo "Date of<br>entry";}?></td>
					<td class="Cellule_Tableau_Competence"><?php if($LangueAffichage=="FR"){echo "Date<br>de sortie";}else{echo "Date of<br>departure";}?></td>
					<td class="Cellule_Tableau_Competence"><?php if($LangueAffichage=="FR"){echo "Matricule";}else{echo "Registration<br>number";}?></td>
					<td class="Cellule_Tableau_Competence"><?php if($LangueAffichage=="FR"){echo "Contrat";}else{echo "Contract type";}?></td>
					<td class="Cellule_Tableau_Competence"><?php if($LangueAffichage=="FR"){echo "Entretien<br>professionnel fait le";}else{echo "Profesional interview<br>conducted on";}?></td>
					<td class="Cellule_Tableau_Competence"><?php if($LangueAffichage=="FR"){echo "Entretien<br>professionnel à faire le";}else{echo "Profesional interview to<br>be conducted on";}?></td>
					<td class="Cellule_Tableau_Competence"><?php if($LangueAffichage=="FR"){echo "Observations";}else{echo "Comments";}?></td>
				</tr>
			<?php
				/*$Requete_Liste_Personne="SELECT Id_Personne, Date_Debut, Date_Fin FROM new_competences_personne_prestation";
				$Requete_Liste_Personne.=" WHERE Id_Prestation=".$_GET["Id"]." AND Date_Fin>='".$DateJour."'";
				$Requete_Liste_Personne.=" ORDER BY Date_Fin DESC";*/
				$Requete_Liste_Personne="SELECT DISTINCT new_competences_personne_prestation.Id_Personne, new_competences_personne_prestation.Date_Debut, new_competences_personne_prestation.Date_Fin FROM new_competences_personne_prestation, new_competences_personne_metier, new_rh_etatcivil";
				$Requete_Liste_Personne.=" WHERE new_competences_personne_prestation.Id_Prestation=".$_GET["Id"]." AND new_competences_personne_prestation.Date_Fin>='".$DateJour."'";
				$Requete_Liste_Personne.=" AND new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id AND new_competences_personne_prestation.Id_Personne=new_competences_personne_metier.Id_Personne";
				$Requete_Liste_Personne.=" ORDER BY new_competences_personne_metier.Id_Metier ASC, new_rh_etatcivil.Nom ASC";
				$Result_Liste_Personne=mysqli_query($bdd,$Requete_Liste_Personne);
				while($Ligne_Liste_Personne=mysqli_fetch_array($Result_Liste_Personne))
				{
					//Personne
					$Nom="";
					$Prenom="";
					$NumBadgeMatricule="";
					$result_etatcivil=mysqli_query($bdd,"SELECT Nom, Prenom, Contrat, NumBadge, Matricule FROM new_rh_etatcivil WHERE Id=".$Ligne_Liste_Personne[0]);
					$row_etatcivil=mysqli_fetch_array($result_etatcivil);
					$Nom=$row_etatcivil[0];
					$Prenom=$row_etatcivil[1];
					$Contrat=$row_etatcivil[2];
					$NumBadgeMatricule=$row_etatcivil[3]." / ".$row_etatcivil[4];
					
					//Entretien
					$EntretienFait="";
					$EntretienAFaire="";
					$result_entretien=mysqli_query($bdd,"SELECT Date_Prevue FROM new_competences_personne_rh_eia WHERE Id_Personne=".$Ligne_Liste_Personne[0]." AND Date_Reel<='0001-01-01' ORDER BY Date_Prevue DESC");
					$row_entretien=mysqli_fetch_array($result_entretien);
					if(mysqli_num_rows($result_entretien)>0){$EntretienAFaire=$row_entretien[0];}
					$result_entretien=mysqli_query($bdd,"SELECT Date_Reel FROM new_competences_personne_rh_eia WHERE Id_Personne=".$Ligne_Liste_Personne[0]." ORDER BY Date_Reel DESC");
					$row_entretien=mysqli_fetch_array($result_entretien);
					if(mysqli_num_rows($result_entretien)>0){$EntretienFait=$row_entretien[0];}
				
					//Metier
					$METIER="";
					$requete_metier="SELECT DISTINCT new_competences_metier.Libelle FROM new_competences_metier, new_competences_personne_metier";
					$requete_metier.=" WHERE new_competences_personne_metier.Id_Personne=".$Ligne_Liste_Personne[0]." AND new_competences_metier.Id=new_competences_personne_metier.Id_Metier ";
					$requete_metier.=" ORDER By new_competences_metier.Libelle ASC";
					$result_metier=mysqli_query($bdd,$requete_metier);
					$nbenreg_metier=mysqli_num_rows($result_metier);
					if($nbenreg_metier>0)
						{while($row_metier=mysqli_fetch_array($result_metier)){if($METIER==""){$METIER=$row_metier[0];}else{$METIER.="<br>".$row_metier[0];}}}
				
					if($Ligne_Liste_Personne[2]>=$DateJour){$Couleur="#FFFFFF";}
					else{$Couleur="#EEEEEE";}
				?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td class="Cellule_Tableau_Competence" width="110"><?php echo $Nom." ".$Prenom?></td>
					<td class="Cellule_Tableau_Competence" width="100"><?php echo $METIER;?></td>
					<td class="Cellule_Tableau_Competence" width="90"><?php echo $Ligne_Liste_Personne[1];?></td>
					<td class="Cellule_Tableau_Competence" width="90">&nbsp;<?php //echo $Ligne_Liste_Personne[2];?></td>
					<td class="Cellule_Tableau_Competence" width="90"><?php echo $NumBadgeMatricule;?></td>
					<td class="Cellule_Tableau_Competence" width="90"><?php echo $Contrat;?></td>
					<td class="Cellule_Tableau_Competence" width="75"><?php echo $EntretienFait;?></td>
					<td class="Cellule_Tableau_Competence" width="75"><?php echo $EntretienAFaire;?></td>
					<td class="Cellule_Tableau_Competence" width="90">&nbsp;</td>
				</tr>
				<?php
				}
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table style="width:100%;">
				<tr>
					<td colspan="3">_____________________________________________________________________________________________________________</td>
				</tr>
				<tr>
					<td>
						<font size="1">
							<?php
								if($LangueAffichage=="FR"){echo "D-0701 - Edition 1<br>01/09/2017";}
								else{echo "D-0701 - Issue 1<br>01/09/2017";}
							?>
						</font>
					</td>
					<td width="80%" align="center">
						<font size="1">
							<?php
								if($LangueAffichage=="FR"){echo "DOCUMENT QUALITE AAA GROUP<br><br>Reproduction interdite sans autorisation écrite de AAA GROUP";}
								else{echo "AAA GROUP QUALITY MANAGEMENT DOCUMENT<br><br>Reproduction forbidden without written authorization by AAA GROUP";}
							?>
						</font>
					</td>
					<td><font size="1" style="align:right;">Page 1/1</font></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
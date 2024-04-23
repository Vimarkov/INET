<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Individual competency list</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<style>
		html,body
		{
			background-color:#FFFFFF;;
		}
	</style>
	<script type="text/javascript">
	function OuvrirFichier(Fic)
	{
		window.open("../../../Qualite/D/5/"+Fic+"-GRP-fr.pdf","PageFicheMetier","status=no,menubar=no,width="+screen.width-10+",height="+screen.height-10+",resizable=yes");
	}
	</script>
</head>

<?php
require_once("../Connexioni.php");
require_once("../Fonctions.php");

if($_POST){$result=mysqli_query($bdd,"SELECT Nom, Prenom, NumBadge, Date_Naissance FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=".$_POST['Id_Personne']);}
else{$result=mysqli_query($bdd,"SELECT Nom, Prenom, NumBadge, Date_Naissance FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=".$_GET['Id_Personne']);}
$row=mysqli_fetch_array($result);
$Prenom=$row['Prenom'];
$Nom=$row['Nom'];
$NumBadge=$row['NumBadge'];
$Date_Naissance=$row['Date_Naissance'];

//Logo
$Logo_Plateforme="";
$result=mysqli_query($bdd,"SELECT new_competences_plateforme.* FROM new_competences_personne_plateforme, new_competences_plateforme WHERE new_competences_personne_plateforme.Id_Plateforme=new_competences_plateforme.Id AND new_competences_personne_plateforme.Id_Personne=".$_GET['Id_Personne']." ORDER BY new_competences_personne_plateforme.Id_Plateforme DESC");
$nbenreg=mysqli_num_rows($result);
if($nbenreg>0)
{
	$row=mysqli_fetch_array($result);
	$Logo_Plateforme=$row["Logo"];
}
?>

<body leftmargin="0" topmargin="0">
<table style="width:100%; align:center;">
	<tr>
		<td>
			<table class="ProfilCompetence" style="width:100%;">
				<tr>
					<td width="20%" rowspan="3">
						<img src="../../Images/Logos/Logo Daher_posi.png" width="148">
						<?php 
						if($Logo_Plateforme<>""){
							echo "<img src='../../Images/Logos/".$Logo_Plateforme."' width='148'>"; 
						}
						?>
					</td>
					<td bgcolor="#00325F" style="color:#ffffff;font-size:20px;" align="center" width="70%" rowspan="3">
						<font style="text-decoration=underline; font-weight=bold;">I N D I V I D U A L&nbsp;&nbsp;&nbsp;C O M P E T E N C Y&nbsp;&nbsp;&nbsp;L I S T</font>
					</td>
					<td width="10%">D-0732-1</td>
				</tr>
				<tr>
					<td>Template issue 2</td>
				</tr>
				<tr>
					<td>Feb. 20, 2024</td>
				</tr>
				<tr>
					<td colspan="3"><?php echo "Content updated on : ".date('M. d, Y');?></td>
				</tr>
			</table>
		</td>
	</tr>
	
	<tr>
		<td>
			<table style="border-spacing:0; width:100%;">
				<tr valign="top">
					<td>
						<table style="width:100%;" class="ProfilCompetence">
							<!-- PLATEFORME -->
							<!--############-->
							<tr class="TitreSousPageCompetences">
								<td>OPERATING UNIT</td>
								<td>AAA ADDRESS</td>
								<td>ARP ID</td>
							</tr>
							<?php
							$result=mysqli_query($bdd,"SELECT new_competences_plateforme.* FROM new_competences_personne_plateforme, new_competences_plateforme WHERE new_competences_personne_plateforme.Id_Plateforme=new_competences_plateforme.Id AND new_competences_personne_plateforme.Id_Personne=".$_GET['Id_Personne']." ORDER BY new_competences_personne_plateforme.Id_Plateforme DESC");
							$nbenreg=mysqli_num_rows($result);
							$Couleur="#FFFFFF";
							if($nbenreg>0)
							{
								$row=mysqli_fetch_array($result);
							?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td><?php echo $row['Libelle']; ?></td>
								<td><?php echo $row['Adresse']; ?></td>
								<td><?php echo $row['ARP_Id']; ?></td>
							</tr>
							<?php
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
			<table style="border-spacing:0; width:100%;">
				<tr>
					<td>
						<table style="width:100%;" class="ProfilCompetence">
							<!-- METIER/FONCTION -->
							<!-- ############### -->
							<?php
								$requeteFonctionMetier="
                                    SELECT
                                        new_competences_metier.Libelle,
                                        new_competences_metier.Fiche
                                    FROM
                                        new_competences_personne_metier,
                                        new_competences_metier
                                    WHERE
                                        new_competences_personne_metier.Id_Metier=new_competences_metier.Id
                                        AND new_competences_personne_metier.Id_Personne=".$_GET['Id_Personne']."
                                    ORDER BY
                                        new_competences_personne_metier.Id DESC";
							?>
							<tr class="TitreSousPageCompetences">
								<td>NAME</td>
								<td>DATE OF BIRTH</td>
								<td>JOB/FUNCTION</td>
								<td>BADGE</td>
								<td>STAMPS NUMBERS</td>
							</tr>
							<tr bgcolor="#FFFFFF">
								<td><?php echo $Nom." ".$Prenom; ?></td>
								<td><?php echo AfficheDateJJ_MM_AAAA($Date_Naissance); ?></td>
								<td>
									<?php
										$FonctionMetier="";
										$result=mysqli_query($bdd,$requeteFonctionMetier);
										$nbenreg=mysqli_num_rows($result);
										if($nbenreg>0)
										{
											while($row=mysqli_fetch_array($result))
											{
												if($FonctionMetier<>""){$FonctionMetier.="<br>";}
												if($row['Fiche']!="" && file_exists($CheminQualite."D/5/".$row['Fiche']."-GRP-fr.pdf"))
												{
													$FonctionMetier.= $row['Libelle']." <a href=\"javascript:OuvrirFichier('".$row['Fiche']."');\">(".$row['Fiche'].")</a>";
												}
												else{$FonctionMetier.= $row['Libelle'];}
											}
										}
										echo $FonctionMetier;
									?>
								</td>
								<td><?php echo $NumBadge; ?></td>
								<td>
								<?php
									$Stamps="";
									$result=mysqli_query($bdd,"SELECT Num_Stamp, Scope,Date_Debut,Date_Fin FROM new_competences_personne_stamp WHERE Id_Personne=".$_GET['Id_Personne']." AND Date_Debut<='".$DateJour."' AND (Date_Fin>='".$DateJour."' OR Date_Fin<='0001-01-01') ORDER BY Num_Stamp ASC");
									$nbenreg=mysqli_num_rows($result);
									$Couleur="#EEEEEE";
									if($nbenreg>0)
									{
										while($row=mysqli_fetch_array($result))
										{
											if($Stamps<>""){$Stamps.="<br>";}
											$Stamps.=$row['Num_Stamp']." # ".$row['Scope'];
											if(($row['Date_Debut']>'0001-01-01' ) || ($row['Date_Fin']>'0001-01-01' )){
												$Stamps.= " (".AfficheDateJJ_MM_AAAA($row['Date_Debut'])." - ".AfficheDateJJ_MM_AAAA($row['Date_Fin']).")";
											}
										}
									}
									echo $Stamps;
								?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		<td>
	</tr>
	
	<tr>
		<td>
			<table class="ProfilCompetence" style="width:100%;">
				<!-- PRESTATION -->
				<!--############-->
				<tr class="TitreSousPageCompetences">
					<td colspan="2">ACTIVITIES</td>
				</tr>
				<tr>
					<td class="TitreSousPageCompetencesPetit">Wording</td>
					<td class="TitreSousPageCompetencesPetit" width="80" >Start date</td>
				</tr>
				<?php
				$requete="
                    SELECT
                        new_competences_prestation.Libelle,
                        new_competences_personne_prestation.Date_Debut,
                        new_competences_prestation.Id
                    FROM
                        new_competences_personne_prestation,
                        new_competences_prestation
                    WHERE
                        new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
                        AND new_competences_personne_prestation.Id_Personne=".$_GET['Id_Personne']."
                        AND new_competences_personne_prestation.Date_Fin >= '".$DateJour."'
                    ORDER BY
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
					<td><?php echo $row[0]; ?></td>
					<td width="80"><?php echo $row[1]; ?></td>
				</tr>
				<?php
					}
				}
				?>
			</table>
		</td>
	</tr>
	
	<?php
	$requete_Deb="
        SELECT
            DISTINCT new_competences_qualification.Libelle,
            new_competences_relation.Date_Debut,
            new_competences_relation.Date_Fin,
            new_competences_relation.Resultat_QCM,
            new_competences_relation.Evaluation,
            new_competences_relation.Date_QCM,
            new_competences_relation.Date_Surveillance,
            new_competences_relation.Sans_Fin,
            new_competences_categorie_qualification.Libelle,
            new_competences_relation.QCM_Surveillance
        FROM
            new_competences_relation,
            new_competences_qualification,
            new_competences_categorie_qualification
        WHERE
            new_competences_relation.Type='Qualification'
            AND new_competences_relation.Suppr=0
            AND new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
            AND new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
            AND
            (
                new_competences_relation.Date_Fin<='0001-01-01'
                OR new_competences_relation.Date_Fin >= '".$DateJour."'
            )
            AND new_competences_relation.Visible=0
            AND new_competences_relation.Id_Personne=".$_GET['Id_Personne']."
            AND new_competences_categorie_qualification.Id_Categorie_Maitre=";
	$requete_Fin="
            ORDER BY
                new_competences_categorie_qualification.Libelle ASC,
                new_competences_qualification.Libelle ASC,
                new_competences_relation.Date_QCM DESC,
                new_competences_relation.Date_Debut DESC";
	?>
	
	<tr>
		<td>
			<table class="ProfilCompetence" style="width:100%;">
				<!-- JOB VALIDATION -->
				<!--###############-->
				<?php
				$Requete_Categorie="1";
				?>
				<tr class="TitreSousPageCompetences">
					<td colspan="7">JOB VALIDATION</td>
				</tr>
				<tr>
					<td colspan="2" class="TitreSousPageCompetencesPetit">Wording</td>
					<td width="80" class="TitreSousPageCompetencesPetit">Start date</td>
					<td width="80" class="TitreSousPageCompetencesPetit">End date</td>
					<td width="40" class="TitreSousPageCompetencesPetit">B/L/V</td>
					<td width="40" class="TitreSousPageCompetencesPetit">Score</td>
					<td width="80" class="TitreSousPageCompetencesPetit">QCM date</td>
				</tr>
				<?php
				$Couleur="#EEEEEE";
				$result=mysqli_query($bdd,$requete_Deb.$Requete_Categorie.$requete_Fin);
				$nbenreg=mysqli_num_rows($result);
				$Categorie="";
				$Libelle="";
				$Evalution="";
				if($nbenreg > 0)
				{
					while($LigneQualification=mysqli_fetch_array($result))
					{
						if($Categorie != $LigneQualification[8])
						{
							$Categorie=$LigneQualification[8];
							$Couleur=="#EEEEEE";
				?>
				<tr>
					<td colspan="9" class="PetiteCategorieCompetence"><b><?php echo $Categorie; ?></b></td>
				</tr>
				<?php
						}
						if($Libelle != $LigneQualification[0] 
						|| ($Evalution=="Q" && $LigneQualification['Evaluation']=="S")
						|| ($Evalution=="S" && $LigneQualification['Evaluation']=="Q")
						)
						{
							$Libelle=$LigneQualification[0];
							$Evalution=$LigneQualification['Evaluation'];
							if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
							else{$Couleur="#EEEEEE";}
				?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td width="2"></td>
					<td><?php echo $Libelle; ?></td>
					<td title="Date de validation de la qualification"><?php if($LigneQualification[1]>'0001-01-01' && $LigneQualification[1]!='0001-01-01'){echo $LigneQualification[1];} ?></td>
					<td title="Date de fin de validité de la qualification"><?php if($LigneQualification[7]=='Oui'){echo "Sans limite";}elseif($LigneQualification[2]>'0001-01-01' && $LigneQualification[2]!='0001-01-01'){echo $LigneQualification[2];} ?></td>
					<td align="center"><?php echo $LigneQualification[4]; ?></td>
					<td align="center"><?php echo $LigneQualification[3]; ?></td>
					<td><?php if($LigneQualification[5]>'0001-01-01' && $LigneQualification[5]!='0001-01-01'){echo $LigneQualification[5];} ?></td>
				</tr>
				<?php
						}
					}
				}
				?>
			</table>
		</td>
	</tr>
	
	<tr>
		<td>
			<table class="ProfilCompetence" style="width:100%;">
				<!-- SPECIAL PROCESSES -->
				<!--###################-->
				<?php
				$Requete_Categorie="2";
				?>
				<tr class="TitreSousPageCompetences">
					<td colspan="9">SPECIAL PROCESSES</td>
				</tr>
				<tr>
					<td colspan="2" class="TitreSousPageCompetencesPetit">Wording</td>
					<td width="80" class="TitreSousPageCompetencesPetit">Start date</td>
					<td width="80" class="TitreSousPageCompetencesPetit">End date</td>
					<td width="40" class="TitreSousPageCompetencesPetit">B/L/Q/S/T</td>
					<td width="40" class="TitreSousPageCompetencesPetit">Score</td>
					<td width="80" class="TitreSousPageCompetencesPetit">QCM date</td>
					<td width="40" class="TitreSousPageCompetencesPetit">Monitoring score</td>
					<td width="80" class="TitreSousPageCompetencesPetit">Monitoring date</td>
				</tr>
				<?php
				$Couleur="#EEEEEE";
				$result=mysqli_query($bdd,$requete_Deb.$Requete_Categorie.$requete_Fin);
				$nbenreg=mysqli_num_rows($result);
				$Categorie="";
				$Libelle="";
				$Evalution="";
				if($nbenreg > 0)
				{
					while($LigneQualification=mysqli_fetch_array($result))
					{
						if($Categorie != $LigneQualification[8])
						{
							$Categorie=$LigneQualification[8];
							$Couleur=="#EEEEEE";
				?>
				<tr>
					<td colspan="9" class="PetiteCategorieCompetence"><b><?php echo $Categorie; ?></b></td>
				</tr>
				<?php
						}
						if($Libelle != $LigneQualification[0]
						|| ($Evalution=="Q" && $LigneQualification['Evaluation']=="S")
						|| ($Evalution=="S" && $LigneQualification['Evaluation']=="Q")
						)
						{
							$Libelle=$LigneQualification[0];
							$Evalution=$LigneQualification['Evaluation'];
							if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
							else{$Couleur="#EEEEEE";}
				?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td width="2"></td>
					<td><?php echo $Libelle; ?></td>
					<td title="Date de validation de la qualification"><?php if($LigneQualification[1]>'0001-01-01' && $LigneQualification[1]!='0001-01-01'){echo $LigneQualification[1];} ?></td>
					<td title="Date de fin de validité de la qualification"><?php if($LigneQualification[7]=='Oui'){echo "Sans limite";}elseif($LigneQualification[2]>'0001-01-01' && $LigneQualification[2]!='0001-01-01'){echo $LigneQualification[2];} ?></td>
					<td align="center"><?php echo $LigneQualification[4]; ?></td>
					<td align="center"><?php echo $LigneQualification[3]; ?></td>
					<td><?php if($LigneQualification[5]>'0001-01-01' && $LigneQualification[5]!='0001-01-01'){echo $LigneQualification[5];} ?></td>
					<td align="center"><?php echo $LigneQualification[9]; ?></td>
					<td title="Date de la réalisation de la surveillance"><?php if($LigneQualification[6]>'0001-01-01' && $LigneQualification[6]!='0001-01-01'){echo $LigneQualification[6];} ?></td>
				</tr>
				<?php
						}
					}
				}
				?>
			</table>
		</td>
	</tr>
	
	<tr>
		<td>
			<table class="ProfilCompetence" style="width:100%;">
				<!-- NO SPECIAL PROCESSES / SPECIFIC COMPETENCIES -->
				<!--###############-->
				<?php
				$Requete_Categorie="3";
				?>
				<tr class="TitreSousPageCompetences">
					<td colspan="7">NO SPECIAL PROCESSES / SPECIFIC COMPETENCIES</td>
				</tr>
				<tr>
					<td colspan="2" class="TitreSousPageCompetencesPetit">Wording</td>
					<td width="80" class="TitreSousPageCompetencesPetit">Start date</td>
					<td width="80" class="TitreSousPageCompetencesPetit">End date</td>
					<td width="40" class="TitreSousPageCompetencesPetit">B/L/X</td>
					<td width="40" class="TitreSousPageCompetencesPetit">Score</td>
					<td width="80" class="TitreSousPageCompetencesPetit">QCM date</td>
				</tr>
				<?php
				$Couleur="#EEEEEE";
				$result=mysqli_query($bdd,$requete_Deb.$Requete_Categorie.$requete_Fin);
				$nbenreg=mysqli_num_rows($result);
				$Categorie="";
				$Libelle="";
				$Evalution="";
				if($nbenreg > 0)
				{
					while($LigneQualification=mysqli_fetch_array($result))
					{
						if($Categorie != $LigneQualification[8])
						{
							$Categorie=$LigneQualification[8];
							$Couleur=="#EEEEEE";
				?>
				<tr>
					<td colspan="9" class="PetiteCategorieCompetence"><b><?php echo $Categorie; ?></b></td>
				</tr>
				<?php
						}
						if($Libelle != $LigneQualification[0]
						|| ($Evalution=="Q" && $LigneQualification['Evaluation']=="S")
						|| ($Evalution=="S" && $LigneQualification['Evaluation']=="Q")
						)
						{
							$Libelle=$LigneQualification[0];
							$Evalution=$LigneQualification['Evaluation'];
							if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
							else{$Couleur="#EEEEEE";}
				?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td width="2"></td>
					<td><?php echo $Libelle; ?></td>
					<td title="Date de validation de la qualification"><?php if($LigneQualification[1]>'0001-01-01' && $LigneQualification[1]!='0001-01-01'){echo $LigneQualification[1];} ?></td>
					<td title="Date de fin de validité de la qualification"><?php if($LigneQualification[7]=='Oui'){echo "Sans limite";}elseif($LigneQualification[2]>'0001-01-01' && $LigneQualification[2]!='0001-01-01'){echo $LigneQualification[2];} ?></td>
					<td align="center"><?php echo $LigneQualification[4]; ?></td>
					<td align="center"><?php echo $LigneQualification[3]; ?></td>
					<td><?php if($LigneQualification[5]>'0001-01-01'){echo $LigneQualification[5];} ?></td>
				</tr>
				<?php
						}
					}
				}
				?>
			</table>
		</td>
	</tr>
	
	<tr>
		<td>
			<table style="border-spacing:0; width:100%;">
				<tr valign="top">
					<!-- OTHERS TRAININGS -->
					<!--#########-->
					<td>
						<table class="ProfilCompetence" style="width:100%;">
							<tr class="TitreSousPageCompetences">
								<td colspan="2">OTHERS TRAININGS</td>
							</tr>
							<tr>
								<td class="TitreSousPageCompetencesPetit">Wording</td>
								<td class="TitreSousPageCompetencesPetit" width="80">Date</td>
							</tr>
							<?php
							$requete="
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
							$result=mysqli_query($bdd,$requete);
							$nbenreg=mysqli_num_rows($result);
							$Couleur="#EEEEEE";
							$Libelle="";
							$Categorie="";
							if($nbenreg>0)
							{
								while($row=mysqli_fetch_array($result))
								{
									if($Categorie!=$row['Type'])
									{
							?>
							<tr>
								<td colspan="2" class="PetiteCategorieCompetence"><b><?php echo $row['Type']; ?></b></td>
							</tr>
							<?php	
									}
									$Categorie=$row['Type'];
								
									if($Libelle != $row['Libelle'])
									{
										$Libelle=$row['Libelle'];
										if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
										else{$Couleur="#EEEEEE";}
							?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td><?php echo $Libelle; ?></td>
								<td><?php if($row['DateSession'] > '0001-01-01' && $row['DateSession'] != '0001-01-01'){echo $row['DateSession'];} ?></td>	
							</tr>
							<?php
									}
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
		<td colspan="20" align="center">
			<img src="../../Images/Legende_GPEC2.png">
		</td>
	</tr>
	<tr>
		<td>
			<table style="width:100%;">
				<tr>
					<td align="center"><font size=1>DIS QUALITY DOCUMENT - Reproduction forbidden without written authorization from DIS</font></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php
if($_GET['Id_Personne']!='0'){mysqli_free_result($result);}	// Libération des résultats}
mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
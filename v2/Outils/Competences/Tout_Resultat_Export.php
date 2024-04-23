<?php
session_start();
?>

<html>
<head>
	<title>Compétences - Tous résultats exportés</title><meta name="robots" content="noindex">
	<!--<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">-->
</head>

<?php
require("../Connexioni.php");
require_once("../Fonctions.php");

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=dataExport.xls");
?>

<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table>
				<tr>
					<!--<td width="10"></td>-->
					<td>
						<table class="TableCompetences" style="width:1050px;">
						<?php
							$requete="SELECT Id_Personne, Id_Qualification_Parrainage, Evaluation, Date_QCM, Date_Surveillance, Resultat_QCM ";
							$requete.="FROM new_competences_relation WHERE new_competences_relation.Suppr=0 ";
							$requete.=" ORDER BY Id_Personne ASC, Id_Qualification_Parrainage ASC, Date_QCM DESC";
							$result=mysqli_query($bdd,$requete);
							$nbenreg=mysqli_num_rows($result);
							if($nbenreg>0)
							{
						?>
							<tr>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Code analytique";}else{echo "Analytic code";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Nom Prénom";}else{echo "Name First name";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Métier";}else{echo "Job";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Qualification";}else{echo "Qualification";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Evaluation";}else{echo "Evaluation";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Date QCM";}else{echo "MCQ Date";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Résultat QCM";}else{echo "MCQ result";}?></td>
							</tr>
							<?php
								$Couleur="#EEEEEE";
								while($row=mysqli_fetch_array($result))
								{
									if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
									else{$Couleur="#EEEEEE";}
									
									$day=substr($row[2],8,2);
									$year=substr($row[2],0,4);
									$month=substr($row[2],5,2);
									
									//PERSONNE
									$PRENOM="";
									$NOM="";
									$requete_personne="SELECT Nom, Prenom ";
									$requete_personne.="FROM new_rh_etatcivil WHERE";
									$requete_personne.=" Id=".$row[0];
									$result_personne=mysqli_query($bdd,$requete_personne);
									if(mysqli_num_rows($result_personne)>0){$row_personne=mysqli_fetch_array($result_personne);$NOM=$row_personne[0];$PRENOM=$row_personne[1];}
									
									//QUALIFICATION
									$QUALIFICATION="";
									$requete_qualif="SELECT Libelle,Periodicite_Surveillance ";
									$requete_qualif.="FROM new_competences_qualification WHERE";
									$requete_qualif.=" Id=".$row[1];
									$result_qualif=mysqli_query($bdd,$requete_qualif);
									if(mysqli_num_rows($result_qualif)>0)
									{
										$row_qualif=mysqli_fetch_array($result_qualif);
										$QUALIFICATION=$row_qualif[0];
									}
									
									//Plateforme
									$PLATEFORME="";
									$requete_plateforme="SELECT DISTINCT new_competences_plateforme.Libelle FROM new_competences_plateforme, new_competences_personne_plateforme";
									$requete_plateforme.=" WHERE new_competences_personne_plateforme.Id_Personne=".$row[0]." AND new_competences_plateforme.Id=new_competences_personne_plateforme.Id_Plateforme ";
									$requete_plateforme.=" ORDER By new_competences_plateforme.Libelle ASC";
									$result_plateforme=mysqli_query($bdd,$requete_plateforme);
									$nbenreg_plateforme=mysqli_num_rows($result_plateforme);
									if($nbenreg_plateforme>0)
										{while($row_plateforme=mysqli_fetch_array($result_plateforme)){if($PLATEFORME==""){$PLATEFORME=$row_plateforme[0];}else{$PLATEFORME.=" # ".$row_plateforme[0];}}}
									
									//Prestation
									$PRESTATION="";
									$requete_prestation="SELECT DISTINCT new_competences_prestation.Libelle FROM new_competences_prestation, new_competences_personne_prestation";
									$requete_prestation.=" WHERE new_competences_personne_prestation.Id_Personne=".$row[0]." AND new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation ";
									$requete_prestation.=" AND new_competences_personne_prestation.Date_Debut <='".$DateJour."' AND new_competences_personne_prestation.Date_Fin>='".$DateJour."'";
									$requete_prestation.=" ORDER By new_competences_personne_prestation.Date_Debut DESC";
									//echo $requete_prestation."<br>";
									$result_prestation=mysqli_query($bdd,$requete_prestation);
									$nbenreg_prestation=mysqli_num_rows($result_prestation);
									if($nbenreg_prestation>0)
										{$row_prestation=mysqli_fetch_array($result_prestation);$PRESTATION.=$row_prestation[0];}
									
									//Metier
									$METIER="";
									$requete_metier="SELECT DISTINCT new_competences_metier.Libelle FROM new_competences_metier, new_competences_personne_metier";
									$requete_metier.=" WHERE new_competences_personne_metier.Id_Personne=".$row[0]." AND new_competences_metier.Id=new_competences_personne_metier.Id_Metier ";
									$requete_metier.=" ORDER By new_competences_metier.Libelle ASC";
									$result_metier=mysqli_query($bdd,$requete_metier);
									$nbenreg_metier=mysqli_num_rows($result_metier);
									if($nbenreg_metier>0)
										{while($row_metier=mysqli_fetch_array($result_metier)){if($METIER==""){$METIER=$row_metier[0];}else{$METIER.=" # ".$row_metier[0];}}}
										
							?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td><?php echo $PLATEFORME;?></td>
								<td><?php echo $PRESTATION;?></td>
								<td><?php echo $NOM." ".$PRENOM;?></td>
								<td><?php echo $METIER;?></td>
								<td><?php echo $QUALIFICATION;?></td>
								<td><?php echo $row[2];?></td>
								<td><?php echo $row[3];?></td>
								<td><?php echo $row[5];?></td>
							<?php
								}
							?>
							</tr>
				<?php
					}		//Fin boucle
					mysqli_free_result($result);	// Libération des résultats
				?>
						</table>
					</td>
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
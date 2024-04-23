<html>
<head>
	<title>Competences - EIA Export</title><meta name="robots" content="noindex">
</head>
<?php
session_start();
require("../Connexioni.php");
require_once("../Fonctions.php");

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=EIA_Export.xls");
?>

<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table>
				<tr>
					<td>
						<table class="TableCompetences" style="width:1050px;">
						<?php
							$requete="SELECT Id_Personne, Date_Prevue, Date_Report, Date_Reel, Type ";
							$requete.="FROM new_competences_personne_rh_eia";
							$requete.=" ORDER BY Id_Personne ASC";
							$result=mysqli_query($bdd,$requete);
							$nbenreg=mysqli_num_rows($result);
							if($nbenreg>0)
							{
						?>
							<tr>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Code analytique";}else{echo "Analytic code";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Matricule";}else{echo "ID";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Nom";}else{echo "Name";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Prénom";}else{echo "First name";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Métier";}else{echo "Job";}?></td>
								<td class="EnTeteTableauCompetences">Type</td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Date prévue";}else{echo "Previsional date";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Date de report";}else{echo "Report date";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Date réalisée";}else{echo "Realized date";}?></td>
								<td class="EnTeteTableauCompetences">Delta</td>
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
									$CODEANALYTIQUE="";
									$requete_prestation="SELECT new_competences_prestation.Libelle, new_competences_prestation.Code_Analytique FROM new_competences_prestation, new_competences_personne_prestation";
									$requete_prestation.=" WHERE new_competences_personne_prestation.Id_Personne=".$row[0]." AND new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation ";
									$requete_prestation.=" AND new_competences_personne_prestation.Date_Debut <='".$DateJour."' AND new_competences_personne_prestation.Date_Fin>='".$DateJour."'";
									$requete_prestation.=" ORDER By new_competences_personne_prestation.Date_Debut DESC";
									//echo $requete_prestation."<br>";
									$result_prestation=mysqli_query($bdd,$requete_prestation);
									$nbenreg_prestation=mysqli_num_rows($result_prestation);
									if($nbenreg_prestation>0)
										{$row_prestation=mysqli_fetch_array($result_prestation);$PRESTATION.=$row_prestation[0];$CODEANALYTIQUE.=$row_prestation[1];}
									
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
								<td><?php echo $CODEANALYTIQUE;?></td>
								<td><?php echo $row[0];?></td>
								<td><?php echo $NOM;?></td>
								<td><?php echo $PRENOM;?></td>
								<td><?php echo $METIER;?></td>
								<td><?php echo $row[4];?></td>
								<td><?php echo $row[1];?></td>
								<td><?php echo $row[2];?></td>
								<td><?php echo $row[3];?></td>
								<td><?php if($row[1] > '0001-01-01' && $row[1] != '0001-01-01' && $row[3] > '0001-01-01' && $row[3] != '0001-01-01'){echo round((strtotime($row[3]) - strtotime($row[1]))/(60*60*24));} ?></td>
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
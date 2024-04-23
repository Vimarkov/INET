<?php
session_start();
?>

<html>
<head>
	<title>Compétences - Toutes les qualifcations de tout le monde v</title><meta name="robots" content="noindex">
	<!--<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">-->
</head>

<?php
require("../Connexioni.php");
require_once("../Formation/Globales_Fonctions.php");
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
					<td>
						<table class="TableCompetences" style="width:1050;">
							<tr>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "ID PRESTATION";}else{echo "PRESTATION ID";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "ID PERSONNE";}else{echo "PERSON ID";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Nom Prénom";}else{echo "Name First name";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Contrat";}else{echo "Contract";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Métier";}else{echo "Job";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Qualification";}else{echo "Qualification";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Date QCM";}else{echo "QCM Date";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Ligne masquée dans le table de compétence";}else{echo "Hidden row in skill table";}?></td>
							</tr>
						<?php
							$PRENOM="";
							$NOM="";
							$CONTRAT="";
							$requete_personne="SELECT Id, Nom, Prenom, Contrat ";
							$requete_personne.="FROM new_rh_etatcivil";
							$requete_personne.=" WHERE Id IN (SELECT Id_Personne FROM new_competences_personne_plateforme";
							if(sizeof($_SESSION['Id_Plateformes'])>0){$requete_personne.=" WHERE ";}
							foreach($_SESSION['Id_Plateformes'] as &$value){$requete_personne.="new_competences_personne_plateforme.Id_Plateforme=".$value." OR ";}
							$requete_personne=substr($requete_personne,0,strlen($requete_personne)-4);
							$requete_personne.=")";
							$result_personne=mysqli_query($bdd,$requete_personne);
							while($row_personne=mysqli_fetch_array($result_personne))
							{
								$ID_PERSONNE=$row_personne[0];
								$NOM=$row_personne[1];
								$PRENOM=$row_personne[2];
								$CONTRAT=$row_personne[3];
						
								//Plateforme
								$PLATEFORME="";
								$requete_plateforme="SELECT DISTINCT new_competences_plateforme.Libelle FROM new_competences_plateforme, new_competences_personne_plateforme";
								$requete_plateforme.=" WHERE new_competences_personne_plateforme.Id_Personne=".$row_personne[0]." AND new_competences_plateforme.Id=new_competences_personne_plateforme.Id_Plateforme ";
								$requete_plateforme.=" ORDER By new_competences_plateforme.Libelle ASC";
								$result_plateforme=mysqli_query($bdd,$requete_plateforme);
								$nbenreg_plateforme=mysqli_num_rows($result_plateforme);
								if($nbenreg_plateforme>0)
									{while($row_plateforme=mysqli_fetch_array($result_plateforme)){if($PLATEFORME==""){$PLATEFORME=$row_plateforme[0];}else{$PLATEFORME.=" # ".$row_plateforme[0];}}}
						
								//Prestation
								$PRESTATION="";
								$requete_prestation="SELECT DISTINCT new_competences_prestation.Id, new_competences_prestation.Libelle FROM new_competences_prestation, new_competences_personne_prestation";
								$requete_prestation.=" WHERE new_competences_personne_prestation.Id_Personne=".$row_personne[0]." AND new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation ";
								$requete_prestation.=" AND new_competences_personne_prestation.Date_Debut <='".$DateJour."' AND new_competences_personne_prestation.Date_Fin>='".$DateJour."'";
								$requete_prestation.=" ORDER By new_competences_personne_prestation.Date_Debut DESC";
								$result_prestation=mysqli_query($bdd,$requete_prestation);
								$nbenreg_prestation=mysqli_num_rows($result_prestation);
								if($nbenreg_prestation>0)
									{$row_prestation=mysqli_fetch_array($result_prestation);$ID_PRESTATION=$row_prestation[0];$PRESTATION.=$row_prestation[1];}
								
								//Metier
								$METIER="";
								$requete_metier="SELECT DISTINCT new_competences_metier.Libelle FROM new_competences_metier, new_competences_personne_metier";
								$requete_metier.=" WHERE new_competences_personne_metier.Id_Personne=".$row_personne[0]." AND new_competences_metier.Id=new_competences_personne_metier.Id_Metier ";
								$requete_metier.=" ORDER By new_competences_metier.Libelle ASC";
								$result_metier=mysqli_query($bdd,$requete_metier);
								$nbenreg_metier=mysqli_num_rows($result_metier);
								if($nbenreg_metier>0)
									{while($row_metier=mysqli_fetch_array($result_metier)){if($METIER==""){$METIER=$row_metier[0];}else{$METIER.=" # ".$row_metier[0];}}}

								$requete="SELECT Id_Personne, Id_Qualification_Parrainage, Evaluation, Date_QCM,Visible ";
								$requete.="FROM new_competences_relation LEFT JOIN new_competences_qualification ON new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id ";
								$requete.=" WHERE Evaluation='V' AND Id_Personne='".$row_personne[0]."' AND new_competences_relation.Suppr=0 ";
								$requete.=" AND (SELECT new_competences_categorie_qualification.Id_Categorie_Maitre FROM new_competences_categorie_qualification WHERE new_competences_categorie_qualification.Id=new_competences_qualification.Id_Categorie_Qualification)=1 ";
								$requete.=" ORDER BY Date_QCM DESC, Id_Qualification_Parrainage ASC ";
								$result=mysqli_query($bdd,$requete);
								$nbenreg=mysqli_num_rows($result);
								//echo $requete;
								if($nbenreg>0)
								{
									$Couleur="#EEEEEE";
									while($row=mysqli_fetch_array($result))
									{
										if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
										else{$Couleur="#EEEEEE";}
										
										$day=substr($row[2],8,2);
										$year=substr($row[2],0,4);
										$month=substr($row[2],5,2);
										
										//QUALIFICATION
										$QUALIFICATION="";
										$requete_qualif="SELECT new_competences_qualification.Libelle, new_competences_qualification.Periodicite_Surveillance, (SELECT new_competences_categorie_qualification.Libelle FROM new_competences_categorie_qualification WHERE new_competences_categorie_qualification.Id=new_competences_qualification.Id_Categorie_Qualification) AS NomCategorie ";
										$requete_qualif.="FROM new_competences_qualification WHERE";
										$requete_qualif.=" Id=".$row[1];
										$result_qualif=mysqli_query($bdd,$requete_qualif);
										if(mysqli_num_rows($result_qualif)>0){
											$row_qualif=mysqli_fetch_array($result_qualif);
											$QUALIFICATION=$row_qualif[0];
											$CATEGORIE_QUALIFICATION=$row_qualif[2];
										}
											
							?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td><?php echo $PLATEFORME;?></td>
								<td><?php echo $ID_PRESTATION;?></td>
								<td><?php echo $PRESTATION;?></td>
								<td><?php echo $ID_PERSONNE;?></td>
								<td><?php echo $NOM." ".$PRENOM;?></td>
								<td><?php echo $CONTRAT;?></td>
								<td><?php echo $METIER;?></td>
								<td><?php echo $QUALIFICATION;?></td>
								<td><?php echo $row['Date_QCM'];?></td>
								<td><?php if($row['Visible']==1){echo "X";} ?></td>
							</tr>
							<?php
									}
								}
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
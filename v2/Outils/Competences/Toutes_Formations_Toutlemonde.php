<?php
session_start();
?>

<html>
<head>
	<title>Compétences - Toutes les formations de tout le monde v</title><meta name="robots" content="noindex">
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
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Nom Prénom";}else{echo "Name First name";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Libellé formation";}else{echo "Training wording";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?></td>
								<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Date";}else{echo "Date";}?></td>
							</tr>
						<?php
							$requete="
								SELECT
									CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) as NomPrenom,
									new_competences_formation.Libelle AS LibelleFormation,
									new_competences_personne_formation.Type as TypeFormation,
									new_competences_personne_formation.Date as DateFormation
								FROM 
									new_competences_personne_formation
									LEFT JOIN new_rh_etatcivil
										ON new_rh_etatcivil.Id=new_competences_personne_formation.Id_Personne
									LEFT JOIN new_competences_formation
										ON new_competences_formation.Id=new_competences_personne_formation.Id_Formation";
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
							?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td><?php echo $row['NomPrenom']?></td>
								<td><?php echo $row['LibelleFormation']?></td>
								<td><?php echo $row['TypeFormation']?></td>
								<td><?php echo $row['DateFormation'];?></td>
							</tr>
							<?php
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
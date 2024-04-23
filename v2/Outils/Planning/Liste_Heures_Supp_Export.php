<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
</head>
<?php
require("../VerifPage.php");
require("../Connexioni.php");

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=heureSuppExport.xls");
	
//Vérification des droits de lecture, écriture, administration
$resultDroits=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne']);
$nbDroits=mysqli_num_rows($resultDroits);
?>


<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<?php
	if($nbDroits>0)
	{
		$requete="SELECT new_rh_heures_supp.*, new_competences_prestation.Libelle, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS NOMPrenom,";
		$requete.=" (SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=new_rh_heures_supp.Id_Pole) AS Pole";
		$requete.=" FROM new_rh_heures_supp";
		$requete.=" LEFT JOIN new_competences_prestation ON new_rh_heures_supp.Id_Prestation=new_competences_prestation.Id";
		$requete.=" LEFT JOIN new_rh_etatcivil ON new_rh_heures_supp.Id_Personne=new_rh_etatcivil.Id";
		$requete.=" WHERE new_rh_heures_supp.Id_Prestation IN (SELECT Id_Prestation FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne'].")";
		$requete.=" AND (new_rh_heures_supp.Id_Pole IN (SELECT Id_Pole FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne'].") OR new_rh_heures_supp.Id_Pole = 0)";
		if($_GET['Prestation']<>0){
			$requete.=" AND new_rh_heures_supp.Id_Prestation=".$_GET['Prestation']." ";
			if($_GET['Pole']<>0){
				$requete.=" AND new_rh_heures_supp.Id_Pole=".$_GET['Pole']." ";
			}
		}
		if($_GET['Id_Personne']<>0){
			$requete.=" AND new_rh_heures_supp.Id_Personne=".$_GET['Id_Personne']." ";
		}
		if($_GET['Date']>'0001-01-01'){
			$dateRequete=date("Y-m-d",$_GET['Date']);
			$requete.=" AND new_rh_heures_supp.Date='".$dateRequete."' ";
		}
		$requete.=" ORDER BY new_rh_heures_supp.Date1 DESC";
		$result=mysqli_query($bdd,$requete);
		$couleur="#FFFFFF";
	?>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="1000">
				<tr>
					<td class="EnTeteTableauCompetences">Site</td>
					<td class="EnTeteTableauCompetences">Pôle</td>
					<td class="EnTeteTableauCompetences" width="130">Personne</td>
					<td class="EnTeteTableauCompetences">Nb H. jour</td>
					<td class="EnTeteTableauCompetences">Nb H. nuit</td>
					<td class="EnTeteTableauCompetences">Date HSup</td>
					<td class="EnTeteTableauCompetences">Motif</td>
					<td class="EnTeteTableauCompetences" width="130">Emetteur</td>
					<td class="EnTeteTableauCompetences">Date emission</td>
					<td class="EnTeteTableauCompetences" width="130">Resp. N+1</td>
					<td class="EnTeteTableauCompetences">Date N+1</td>
					<td class="EnTeteTableauCompetences">Etat N+1</td>
					<td class="EnTeteTableauCompetences">Comment. N+1</td>
					<td class="EnTeteTableauCompetences" width="130">Resp. N+2</td>
					<td class="EnTeteTableauCompetences">Date N+2</td>
					<td class="EnTeteTableauCompetences">Etat N+2</td>
					<td class="EnTeteTableauCompetences">Comment. N+2</td>
					<td class="EnTeteTableauCompetences" width="130">Resp. N+3</td>
					<td class="EnTeteTableauCompetences">Date N+3</td>
					<td class="EnTeteTableauCompetences">Etat N+3</td>
					<td class="EnTeteTableauCompetences">Comment. N+3</td>
				</tr>
	<?php
			while($row=mysqli_fetch_array($result))
			{
				if($row['Etat4']!=''){$step=4;}
				elseif($row['Etat3']!=''){$step=3;}
				elseif($row['Etat2']!=''){$step=2;}
				else{$step=1;}
				
				//Récupération des différents noms des responsables de niveau au dessus sur la prestation en question
				$Responsable2="";
				$Responsable3="";
				$Responsable4="";
				$PersonneConnectee_IdPosteMaxSurPrestation=0;
				$requeteResponsablePostePrestation="SELECT DISTINCT new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Backup, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) as NomPrenom, new_rh_etatcivil.Id";
				$requeteResponsablePostePrestation.=" FROM new_competences_personne_poste_prestation, new_rh_etatcivil";
				$requeteResponsablePostePrestation.=" WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id";
				$requeteResponsablePostePrestation.=" AND new_competences_personne_poste_prestation.Id_Prestation=".$row['Id_Prestation'];
				if($row['Id_Pole'] > 0){
					$requeteResponsablePostePrestation.=" AND new_competences_personne_poste_prestation.Id_Pole=".$row['Id_Pole'];
				}
				$requeteResponsablePostePrestation.=" ORDER BY new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Backup ASC";
				$resultResponsablePostePrestation=mysqli_query($bdd,$requeteResponsablePostePrestation);
				while($rowResponsablePostePrestation=mysqli_fetch_array($resultResponsablePostePrestation))
				{
					//Récupération de la valeur du poste le plus haut sur cette prestation de la personne connectée
					if($rowResponsablePostePrestation['Id']==$_SESSION['Id_Personne'] && $rowResponsablePostePrestation['Id']>$PersonneConnectee_IdPosteMaxSurPrestation)
					{
						$PersonneConnectee_IdPosteMaxSurPrestation=$rowResponsablePostePrestation['Id_Poste'];
					}
					
					switch($rowResponsablePostePrestation['Id_Poste'])
					{
						case 2: $Responsable2.=$rowResponsablePostePrestation['NomPrenom']."<br>";break;
						case 3: $Responsable3.=$rowResponsablePostePrestation['NomPrenom']."<br>";break;
						case 4: $Responsable4.=$rowResponsablePostePrestation['NomPrenom']."<br>";break;
					}
				}
				$Responsable2=substr($Responsable2,0,strlen($Responsable2)-4);
				$Responsable3=substr($Responsable3,0,strlen($Responsable3)-4);
				$Responsable4=substr($Responsable4,0,strlen($Responsable4)-4);
				$requete_user="SELECT CONCAT(Nom,' ',Prenom) AS NomPrenom FROM new_rh_etatcivil WHERE Login='".$row['Login1']."'";		
				$result_user=mysqli_query($bdd,$requete_user);
				$row_user=mysqli_fetch_array($result_user);
				$Responsable1=$row_user['NomPrenom'];
				
				//Mise en variable des noms des validateurs si existants sinon groupe de responsables définis dans la hérarchie du personnel
				if($step>=2 && $row['Login2']<>"")
				{
					$requete_user="SELECT CONCAT(Nom,' ',Prenom) AS NomPrenom FROM new_rh_etatcivil WHERE Login='".$row['Login2']."'";
					$result_user=mysqli_query($bdd,$requete_user);
					$row_user=mysqli_fetch_array($result_user);
					$Responsable2=$row_user['NomPrenom'];
				}
				if($step>=3 && $row['Login3']<>"")
				{
					$requete_user="SELECT CONCAT(Nom,' ',Prenom) AS NomPrenom FROM new_rh_etatcivil WHERE Login='".$row['Login3']."'";
					$result_user=mysqli_query($bdd,$requete_user);
					$row_user=mysqli_fetch_array($result_user);
					$Responsable3=$row_user['NomPrenom'];
				}
				if($step>=4 && $row['Login4']<>"")
				{
					$requete_user="SELECT CONCAT(Nom,' ',Prenom) AS NomPrenom FROM new_rh_etatcivil WHERE Login='".$row['Login4']."'";
					$result_user=mysqli_query($bdd,$requete_user);
					$row_user=mysqli_fetch_array($result_user);
					$Responsable4=$row_user['NomPrenom'];
				}
				
				if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
				else{$couleur="#FFFFFF";}
	?>
				<tr bgcolor="<?php echo $couleur;?>">
					<td><?php echo substr(stripslashes($row['Libelle']),0,7);?></td>
					<td><?php echo stripslashes($row['Pole']);?></td>
					<td><?php echo stripslashes($row['NOMPrenom']);?></td>
					<td><?php echo stripslashes($row['Nb_Heures_Jour']); ?></td>
					<td><?php echo stripslashes($row['Nb_Heures_Nuit']); ?></td>
					<td><?php echo stripslashes($row['Date']); ?></td>
					<td><?php echo stripslashes($row['Motif']);?></td>
					<td><?php echo $Responsable1;?></td>
					<td><?php echo stripslashes($row['Date1']);?></td>
					<td><?php echo $Responsable2;?></td>
					<td><?php echo stripslashes($row['Date2']); ?></td>
					<td><?php echo stripslashes($row['Etat2']); ?></td>
					<td><?php echo stripslashes($row['Commentaire2']); ?></td>
					<td><?php echo $Responsable3;?></td>
					<td><?php echo stripslashes($row['Date3']); ?></td>
					<td><?php echo stripslashes($row['Etat3']); ?></td>
					<td><?php echo stripslashes($row['Commentaire3']); ?></td>
					<td><?php echo $Responsable4;?></td>
					<td><?php echo stripslashes($row['Date4']); ?></td>
					<td><?php echo stripslashes($row['Etat4']); ?></td>
					<td><?php echo stripslashes($row['Commentaire4']); ?></td>
				</tr>
			<?php
			}	//Fin boucle
			?>
			</table>
		</td>
	</tr>
	<tr height='15'><td></td></tr>
<?php
		mysqli_free_result($result);	// Libération des résultats
	}			//Fin vérification des droits
	else
	{
?>
		<tr><td class="Erreur">Vous n'avez pas les droits pour afficher le contenu de ce dossier.</td></tr>
<?php
	}
?>

</table>

<?php
	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
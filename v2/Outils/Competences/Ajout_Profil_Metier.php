<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Compétences - Profil personne - Métier</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function FermerEtRecharger(Page)
		{
			opener.location=Page;
			window.close();
		}
	</script>
</head>
<body>

<?php
require("../Connexioni.php");
require_once("../Formation/Globales_Fonctions.php");
require_once("../Fonctions.php");

if($_POST)
{
	if($_POST['Metier']!="")
	{
		$result=mysqli_query($bdd,"INSERT INTO new_competences_personne_metier (Id_Personne, Id_Metier, Futur) VALUES (".$_POST['Id_Personne'].",".$_POST['Metier'].",".$_POST['Futur'].")");
	}
	
	//GESTION DES BESOINS EN FORMATIONS AUTOMATIQUEMENT CREES EN FONCTION DU METIER ET DE LA PRESTATION
	//#################################################################################################
	
	$ResultMetierPersonne=Get_LesMetiersFutur($_POST['Id_Personne']);
	$nbPersonnePrestation=mysqli_num_rows($ResultMetierPersonne);
	if($nbPersonnePrestation>0){
		while($Metier_Personne=mysqli_fetch_array($ResultMetierPersonne))
		{
			$Id_Metier_Personne=$Metier_Personne[0];
			$Libelle_Metier_Personne=$Metier_Personne[1];
			$Futur_Metier_Personne=$Metier_Personne[3];
			if($Futur_Metier_Personne==0){$Motif="Changement de métier : ".$Libelle_Metier_Personne;}
			else{$Motif="En formation sur nouveau métier : ".$Libelle_Metier_Personne;}
			
			$ReqPrestationsEnCours_Personne="
				SELECT
					DISTINCT 
					Id_Prestation,
					Id_Pole
				FROM
					new_competences_personne_prestation
				WHERE
					Id_Personne=".$_POST['Id_Personne']."
					AND Date_Fin >= '".$DateJour."'";
			$ResultPrestationsEnCours_Personne=mysqli_query($bdd,$ReqPrestationsEnCours_Personne);
			while($RowPrestationsEnCours_Personne=mysqli_fetch_array($ResultPrestationsEnCours_Personne))
			{
				Creer_BesoinsFormations_PersonnePrestationMetier($_POST['Id_Personne'], $RowPrestationsEnCours_Personne['Id_Prestation'], $RowPrestationsEnCours_Personne['Id_Pole'], $Id_Metier_Personne, $Motif,0,0, -1);
			}
		}
	}
	else{
			$ResultMetierPersonne=Get_LesMetiersNonFutur($_POST['Id_Personne']);
			$nbPersonnePrestation=mysqli_num_rows($ResultMetierPersonne);
			if($nbPersonnePrestation>0){
				while($Metier_Personne=mysqli_fetch_array($ResultMetierPersonne))
				{
					$Id_Metier_Personne=$Metier_Personne[0];
					$Libelle_Metier_Personne=$Metier_Personne[1];
					$Futur_Metier_Personne=$Metier_Personne[3];
					if($Futur_Metier_Personne==0){$Motif="Changement de métier : ".$Libelle_Metier_Personne;}
					else{$Motif="En formation sur nouveau métier : ".$Libelle_Metier_Personne;}
					
					$ReqPrestationsEnCours_Personne="
						SELECT
							DISTINCT 
							Id_Prestation,
							Id_Pole
						FROM
							new_competences_personne_prestation
						WHERE
							Id_Personne=".$_POST['Id_Personne']."
							AND Date_Fin >= '".$DateJour."'";
					$ResultPrestationsEnCours_Personne=mysqli_query($bdd,$ReqPrestationsEnCours_Personne);
					while($RowPrestationsEnCours_Personne=mysqli_fetch_array($ResultPrestationsEnCours_Personne))
					{
						Creer_BesoinsFormations_PersonnePrestationMetier($_POST['Id_Personne'], $RowPrestationsEnCours_Personne['Id_Prestation'], $RowPrestationsEnCours_Personne['Id_Pole'], $Id_Metier_Personne, $Motif,0,0,-1);
					}
				}
			}
	}
	
	echo "<script>FermerEtRecharger('Profil.php?Mode=Modif&Id_Personne=".$_POST['Id_Personne']."');</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout")
	{
?>
	<form id="formulaire" method="POST" action="Ajout_Profil_Metier.php">
	<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
	<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
	<table style="width:95%; height:95%; align:center;">
		<tr class="TitreColsUsers">
			<td><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
			<td>
				<select name="Metier">
					<?php
					$result=mysqli_query($bdd,"SELECT Id, Libelle FROM new_competences_metier ORDER BY Libelle ASC");
					while($row=mysqli_fetch_array($result))
					{
						echo "<option value='".$row['Id']."'>".$row['Libelle']."</option>";
					}
					?>
				</select>
			</td>
			<td  class="Libelle">Futur métier : </td>
				<td>
					<select name="Futur">
						<?php
						$Tableau=array('Non|0','Oui|1');
						foreach($Tableau as $indice => $valeur)
						{
							$valeur=explode("|",$valeur);
							echo "<option value='".$valeur[1]."'>".$valeur[0]."</option>\n";
						}
						?>
					</select>
				</td>
			<td><input class="Bouton" type="submit"
				<?php
					if($_GET['Mode']=="Modif"){
						if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
					}
					else{
						if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
					}
				?>
			></td>
		</tr>
	</table>
	</form>
<?php	
	}
	if($_GET['Id_Personne']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
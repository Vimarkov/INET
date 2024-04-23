<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Compétences - Profil personne - Qualification</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Feuille.css" type="text/css">
	<script>
		function FermerEtRecharger(Page)
		{
			opener.location=Page;
			window.close();
		}
		List = new Array();

		function VerifChamps()
		{
			//if(formulaire.Date_Debut.value==""){alert('La date de début doit être au format aaaa-mm-jj.');return false;}
			//if(formulaire.Date_Fin.value==""){alert('La date de fin doit être au format aaaa-mm-jj.');return false;}
			return true;
		}
	</script>
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
</head>

<?php
require("../Connexioni.php");
require_once("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

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
$IdPersonneConnectee=0;
if(isset($_SESSION['Id_Personne'])){$IdPersonneConnectee=$_SESSION['Id_Personne'];}

if($_POST)
{	
	$Plateforme_Identique=false;
	//Plateforme
	$PLATEFORME="";
	$requete_plateforme="SELECT DISTINCT new_competences_plateforme.Libelle, new_competences_plateforme.Id FROM new_competences_plateforme, new_competences_personne_plateforme";
	$requete_plateforme.=" WHERE new_competences_personne_plateforme.Id_Personne=".$_POST['Id_Personne']." AND new_competences_plateforme.Id=new_competences_personne_plateforme.Id_Plateforme ";
	$requete_plateforme.=" ORDER BY new_competences_plateforme.Libelle ASC";
	$result_plateforme=mysqli_query($bdd,$requete_plateforme);
	$nbenreg_plateforme=mysqli_num_rows($result_plateforme);
	if(isset($_SESSION['Id_Plateformes']))
	{
		if($nbenreg_plateforme>0)
		{
			while($row_plateforme=mysqli_fetch_array($result_plateforme))
			{
				foreach($_SESSION['Id_Plateformes'] as &$value){if($row_plateforme['Id']==$value){$Plateforme_Identique=true;}}
			}
		}
	}
	
	$DroitsModifPrestation=false;
	if($IdPersonneConnectee > 0)
	{
		$IdPersonneConnectee=$_SESSION['Id_Personne'];
		$resultHierarchie=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$IdPersonneConnectee." ORDER BY Id_Poste DESC");
		$nbHierarchie=mysqli_num_rows($resultHierarchie);
		if($nbHierarchie>0){$DroitsModifPrestation=true;}
	}
	

	if($_POST['Mode']=="Modif")
	{
		if(isset($_POST['visible'])){
			$Requete="UPDATE new_competences_relation SET ";
			$Requete.="Id_Personne_MAJ_Manuelle=".$IdPersonneConnectee.",";
			$Requete.="Date_MAJ_Manuelle='".date('Y-m-d')."',";
			$Requete.="ModifManuelle=1,";
			$Requete.="Visible=".$_POST['visible']." ";
			$Requete.="WHERE Id=".$_POST['Id'];
		}
	}
	$result=mysqli_query($bdd,$Requete);

	if(($Droits=="Ecriture" && $Plateforme_Identique) || $Droits=="Administrateur"){
		echo "<script>FermerEtRecharger('Profil.php?Mode=Modif&Id_Personne=".$_POST['Id_Personne']."');</script>";
	}
	else{
		if($DroitsModifPrestation==false){
			echo "<script>FermerEtRecharger('Profil.php?Mode=Lecture&Id_Personne=".$_POST['Id_Personne']."');</script>";
		}
		else{
			echo "<script>FermerEtRecharger('Profil.php?Mode=ModifPresta&Id_Personne=".$_POST['Id_Personne']."');</script>";
		}
	}
}
elseif($_GET)
{
	$UniquementB=0;
	if($Droits=="Ecriture" || $Droits=="Administrateur"){
		$UniquementB=1;
	}
//Mode ajout ou modification
	if($_GET['Mode']=="Modif")
	{
		$Relation=mysqli_query($bdd,"SELECT * FROM new_competences_relation WHERE Id=".$_GET['Id']);
		$LigneRelation=mysqli_fetch_array($Relation);
	}
	$i=0; // variable de test
	$j=0; // variable pour garder la valeur du premier enregistrement catégorie pour l'affichage
?>

<?php Ecrire_Code_JS_Init_Date(); ?>

	<form id="formulaire" method="POST" action="Modif_Profil_Qualipso.php" onSubmit="return VerifChamps();" class="None">
	<input type="hidden" id="Mode" name="Mode" value="<?php echo $_GET['Mode']; ?>">
	<input type="hidden" name="Id" value="<?php echo $_GET['Id']; ?>">
	<input type="hidden" name="Id_Personne" value="<?php echo $_GET['Id_Personne'];?>">
	<input type="hidden" name="Id_Categorie_Maitre" value="<?php echo $_GET['Id_Categorie_Maitre'];?>">
	<input type="hidden" id="UniquementB" name="UniquementB" value="<?php echo $UniquementB;?>">
	<table  style="width:90%; align:center;" class="TableCompetences">
		<tr>
			<td>
				<table style="width:100%;">
					<tr class="TitreColsUsers">
						<td><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?> : </td>
						<td>
							<?php
								$result=mysqli_query($bdd,"SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$_GET['Id_Personne']);
								$row=mysqli_fetch_array($result);
								echo $row['Nom']." ".$row['Prenom'];
							?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		
		<tr><td><table><tr><td bgcolor=yellow><div id="Cours" style="font-size:10px"></div></td></tr></table></td></tr>
		
		<tr>
			<td>
				<table style="width:100%;">
					<?php
					if($IdPersonneConnectee > 0)
					{
						$resultHierar=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$IdPersonneConnectee." AND (Id_Poste=5 OR Id_Poste=8) ");
						$nbHierar=mysqli_num_rows($resultHierar);
						
						$resultHierarPlat=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_poste_plateforme WHERE Id_Personne=".$IdPersonneConnectee." AND (Id_Poste=17 OR Id_Poste=18 OR Id_Poste=13) ");
						$nbHierarPlat=mysqli_num_rows($resultHierarPlat);
						if($nbHierar>0  || $nbHierarPlat>0)
						{
					?>
					<tr class="TitreColsUsers">
						<td><?php if($LangueAffichage=="FR"){echo "Masquer la ligne dans le tableau de compétence";}else{echo "Hide the line in the competency table";}?> :</td>
						<td>
							<select name="visible">
								<option value="0" <?php if($_GET['Mode']=="Modif"){if($LigneRelation['Visible']==0){echo "selected";}}else{echo "selected";} ?>>Non</option>
								<option value="1" <?php if($_GET['Mode']=="Modif"){if($LigneRelation['Visible']==1){echo "selected";}} ?>>Oui</option>
							</select>
						</td>
					</tr>
					<?php
						}
					}
					?>
					<tr><td colspan="8" align="center"><input class="Bouton" type="submit"
						<?php
							if($_GET['Mode']=="Modif")
							{
								if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
							}
							else
							{
								if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
							}
						?>
					></td></tr>
				</table>
			</td>
		</tr>
	</table>
	</form>
<?php
	if($_GET['Id_Personne']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
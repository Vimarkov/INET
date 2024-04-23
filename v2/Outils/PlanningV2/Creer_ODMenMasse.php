<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="Contrat.js?t=<?php echo time(); ?>"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script type="text/javascript" src="../JS/mask.js"></script>
	<script type="text/javascript" src="../JS/js/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript" src="../JS/bootstrap.min.js"></script>
    <script type="text/javascript" src="../JS/prettify.js"></script>
    <script type="text/javascript" src="../JS/bootstrap-timepicker.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			$('.heure').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: false,
				showMeridian: false,
				defaultTime: false
			});
		});
		
		function EnregistrerODMEnMasse(){
			var valide = true;

			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.dateDebut.value==""){valide=false;alert("Veuillez renseigner la date de début");return false;}
			}
			else{
				if(formulaire.dateDebut.value==""){valide=false;alert("Please enter the start date");return false;}
				
			}
			var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
			document.getElementById('Ajouter').innerHTML=bouton;
			var evt = document.createEvent("MouseEvents");
			evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
			document.getElementById("btnEnregistrer2").dispatchEvent(evt);
			document.getElementById('Ajouter').innerHTML="";
		}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
require("Fonctions_Planning.php");
Ecrire_Code_JS_Init_Date();

$DateJour=date("Y-m-d");
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
if($Menu==4 && DroitsFormationPlateforme($TableauIdPostesRH)){
if($_POST){
	if(isset($_POST['btnEnregistrer2'])){

		$requete2="
			SELECT *
			FROM
			(
				SELECT *
				FROM 
					(SELECT Id,Id_Personne,Id_Metier,Id_TypeContrat,Titre,Id_ContratInitial,
					MontantIPD,MontantRepas,MontantIGD,MontantRepasGD,Id_Client,Id_Responsable,
					FraisReel,PrimeResponsabilite,IndemniteOutillage,MajorationVSD,PrimeEquipe,PanierGrandeNuit,PanierVSD,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Personne) AS Personne,
					(SELECT LibelleEN FROM new_competences_metier WHERE new_competences_metier.Id=Id_Metier) AS Metier,
					(SELECT LibelleEN FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
					IF(DateSignatureSiege=0,1,
						IF(DateSignatureSalarie=0,2,
							IF(DateSignatureSalarie>'0001-01-01' AND DateRetourSigneAuSiege=0,3,
								IF(DateRetourSigneAuSiege>'0001-01-01',4,
								0
								)
							)
						)
					) AS Etat,
					DateDebut,DateFin,Motif,(@row_number:=@row_number + 1) AS rnk
					FROM rh_personne_contrat
					WHERE Suppr=0
					AND DateDebut<='".date('Y-m-d')."'
					AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
					AND TypeDocument IN ('ODM')
					ORDER BY Id_Personne, DateDebut DESC) AS table_contrat 
				GROUP BY Id_Personne
			) AS table_contrat2
			WHERE Personne<>'' 
			";
		if($_SESSION['FiltreRHODM_Personne']<>""){
			$requete2.=" AND Personne LIKE \"%".$_SESSION['FiltreRHODM_Personne']."%\" ";
		}
		
		if($_SESSION['FiltreRHODM_Metier']<>"0"){
			$requete2.=" AND Id_Metier = ".$_SESSION['FiltreRHODM_Metier']." ";
		}
		if($_SESSION['FiltreRHODM_TypeContrat']<>"0"){
			$requete2.=" AND Id_TypeContrat = ".$_SESSION['FiltreRHODM_TypeContrat']." ";
		}
		if($_SESSION['FiltreRHODM_Etat']<>"0"){
			$requete2.=" AND Etat = ".$_SESSION['FiltreRHODM_Etat']." ";
		}
		if($_SESSION['FiltreRHODM_DateDebut']<>""){
			$requete2.=" AND DateDebut ".$_SESSION['FiltreRHODM_SigneDateDebut']." '".TrsfDate_($_SESSION['FiltreRHODM_DateDebut'])."' ";
		}
		if($_SESSION['FiltreRHODM_DateFin']<>""){
			if($_SESSION['FiltreRHODM_SigneDateFin']=="<"){
				$requete2.=" AND DateFin ".$_SESSION['FiltreRHODM_SigneDateFin']." '".TrsfDate_($_SESSION['FiltreRHODM_DateFin'])."' 
				AND DateFin>'0001-01-01'
				";
			}
			elseif($_SESSION['FiltreRHODM_SigneDateFin']==">"){
				$requete2.=" AND (DateFin ".$_SESSION['FiltreRHODM_SigneDateFin']." '".TrsfDate_($_SESSION['FiltreRHODM_DateFin'])."' 
				OR DateFin<='0001-01-01' )
				";
			}
			elseif($_SESSION['FiltreRHODM_SigneDateFin']=="="){
				$requete2.=" AND DateFin ".$_SESSION['FiltreRHODM_SigneDateFin']." '".TrsfDate_($_SESSION['FiltreRHODM_DateFin'])."' 
				";
			}
		}
		echo $requete2;
		$result=mysqli_query($bdd,$requete2);
		$nbResulta=mysqli_num_rows($result);
						
		if($nbResulta>0){
			while($row=mysqli_fetch_array($result))
			{
				$PrestaPole=PrestationPole_Personne(date('Y-m-d'),$row['Id_Personne']);
				$Id_Prestation=0;
				$Id_Pole=0;
				if($PrestaPole<>0){
					$tab=explode("_",$PrestaPole);
					$Id_Prestation=$tab[0];
					$Id_Pole=$tab[1];
				}
				
				$Id_Contrat=$row['Id'];
				if($row['Id_ContratInitial']>0){$Id_Contrat=$row['Id_ContratInitial'];}
				
				$primeEquipe=0;
				if($_POST['primeEquipe']<>""){$primeEquipe=$_POST['primeEquipe'];}
				$IndemniteRepas=$row['MontantRepas'];
				if($_POST['indemniteRepas']<>"" && $_POST['indemniteRepas']<>"0"){
					$IndemniteRepas=$_POST['indemniteRepas'];
				}
				
				//Création d'un ODM
				$req="INSERT INTO rh_personne_contrat (Id_ContratInitial,Id_Personne,Id_TypeContrat,Id_Metier,DateDebut,DateFin,
					Id_Prestation,Id_Pole,TypeDocument,DateCreation,Id_Createur,Id_Client,Id_Responsable,MontantIPD,MontantRepas,MontantIGD,MontantRepasGD,
					FraisReel,PrimeResponsabilite,IndemniteOutillage,MajorationVSD,PrimeEquipe,PanierGrandeNuit,PanierVSD,Motif,Titre) 
					VALUES 
						(".$Id_Contrat.",".$row['Id_Personne'].",".$row['Id_TypeContrat'].",".$row['Id_Metier'].",
						'".TrsfDate_($_POST['dateDebut'])."','".TrsfDate_($_POST['dateFin'])."',".$Id_Prestation.",".$Id_Pole.",'ODM','".date('Y-m-d')."',".$_SESSION['Id_Personne'].",
						".$row['Id_Client'].",".$row['Id_Responsable'].",".$row['MontantIPD'].",".$IndemniteRepas.",".$row['MontantIGD'].",".$row['MontantRepasGD'].",
						".$row['FraisReel'].",".$row['PrimeResponsabilite'].",".$row['IndemniteOutillage'].",".$row['MajorationVSD'].",".$primeEquipe.",".$row['PanierGrandeNuit'].",
						".$row['PanierVSD'].",'".addslashes($row['Motif'])."','".addslashes($_POST['titre'])."')";
				$resultAjout=mysqli_query($bdd,$req);
				$IdCree = mysqli_insert_id($bdd);
				
				//Ajout des moyens de déplacement
				if($IdCree>0){
					$requeteInsert="INSERT INTO rh_personne_contrat_moyendeplacement (Id_Personne_Contrat,Id_MoyenDeplacement,Montant,Periodicite,Reference) 
					SELECT ".$IdCree.",Id_MoyenDeplacement,Montant,Periodicite,Reference
					FROM rh_personne_contrat_moyendeplacement
					WHERE Id_Personne_Contrat=".$row['Id']."
					AND Suppr=0
					";
					$resultInsert=mysqli_query($bdd,$requeteInsert);
				}
				
				//Mettre une date de fin 
				if(TrsfDate_($_POST['dateDebut'])<>'000-00-00'){
					$req="UPDATE rh_personne_contrat 
						SET DateFin='".date('Y-m-d',strtotime(TrsfDate_($_POST['dateDebut'])." - 1 day"))."'
						WHERE 
						(DateFin<='0001-01-01' OR DateFin>'".date('Y-m-d')."')
						AND Id<>0
						AND TypeDocument='ODM'
						AND Id<>".$IdCree."
						AND Id_Personne=".$row['Id_Personne']."
						AND (Id_ContratInitial=".$Id_Contrat." OR Id=".$Id_Contrat.") ";
					$resultv2=mysqli_query($bdd,$req);
				}
			}
		}
		
		echo "<script>FermerEtRechargerODM('".$Menu."','".$_POST['Page']."')</script>";
		
	}
}
else{

$etoile="<img src='../../Images/etoile.png' width='8' height='8' border='0'>";
?>

<form id="formulaire" class="test" action="Creer_ODMenMasse.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="Page" id="Page" value="<?php echo $_GET['Page']; ?>" />
	<tr>
		<td colspan="5">
			<table style="width:100%; border-spacing:0;">
				<tr>
					<td class="TitrePage" style="background-color:#a988b2;">
					<?php 
						if($_SESSION["Langue"]=="FR"){echo "Créer des ODM pour la liste";}else{echo "Create ODM for the list";}
					?>
					</td>
					<td width="4"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="90%" align="center" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" valign="top" id="LibelleTitre"><?php if($_SESSION["Langue"]=="FR"){echo "Titre :";}else{echo "Title :";} ?></td>
							<td width="10%" valign="top" colspan="4">
								<input type="text" name="titre" id="titre" size="100" value="">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleDateDebut"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début : ";}else{echo "Start date : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<input type="date" style="text-align:center;"  id="dateDebut" name="dateDebut" size="10" value="">
							</td>
							<td width="10%" class="Libelle" id="LibelleDateFin"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin :";}else{echo "End date :";} ?> </td>
							<td width="10%">
								<input type="date" style="text-align:center;"  id="dateFin" name="dateFin" size="10" value="">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" id="LibelleIndemniteRepas"><?php if($_SESSION["Langue"]=="FR"){echo "Indemnité repas : ";}else{echo "Meal allowance : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<input onKeyUp="nombre(this)" type="text" style="text-align:center;" id="indemniteRepas" name="indemniteRepas" size="10" value="6.30">
							</td>
							<td width="10%" class="Libelle" id="LibellePrimeEquipe"><?php if($_SESSION["Langue"]=="FR"){echo "Prime d'équipe (par jour) : ";}else{echo "Team bonus (per day) : ";} ?><?php echo $etoile;?> </td>
							<td width="10%">
								<input onKeyUp="nombre(this)" type="text" style="text-align:center;"  id="primeEquipe" name="primeEquipe" size="10" value="14.20">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="6" align="center">
								<div id="Ajouter">
								</div>
								<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="if(window.confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir enregistrer ?";}else{echo "Are you sure you want to save ?";} ?>')){EnregistrerODMEnMasse();}else{return false;}">
							</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
}
}
?>
</body>
</html>
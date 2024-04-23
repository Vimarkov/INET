<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Extranet de la société Assistance Aéronautique et Aérospatiale</title>
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Perfos.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript">
		function FermerEtRecharger()
		{
			opener.location.href="Liste_ProjetInformatique.php?";
			window.close();
		}
	</script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->	
	
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
</head>
<?php
require_once("../Connexioni.php");
require_once("../Fonctions.php");
	
if(isset($_POST['submitValider'])){
	if ($_POST['dateLivraison'] <> ""){
		if ($NavigOk ==1){
			$tabDateLivraison = explode('-', $_POST['dateLivraison']);
			$timestampDateLivraison = mktime(0, 0, 0, $tabDateLivraison[1], $tabDateLivraison[2], $tabDateLivraison[0]);
		}
		else{
			$tabDateLivraison = explode('/', $_POST['dateLivraison']);
			$timestampDateLivraison = mktime(0, 0, 0, $tabDateLivraison[1], $tabDateLivraison[0], $tabDateLivraison[2]);
			
		}
		$DateLivraison = date("Y-m-d", $timestampDateLivraison);
	}
	else{
		$DateLivraison = "";
	}
	$requeteUpdate="UPDATE new_projet_informatique ";
	$requeteUpdate.="SET DateReponse='".$DateJour."', ";
	$requeteUpdate.="DatePossible='".$DateLivraison."', ";
	$requeteUpdate.="Id_Developpeur='".$_POST['Personne']."', ";
	$requeteUpdate.="Etat='".addslashes($_POST['Etat'])."', ";
	$requeteUpdate.="Avancement='".$_POST['Avancement']."', ";
	$requeteUpdate.="Commentaire='".addslashes($_POST['Commentaire'])."' ";
	$requeteUpdate.="WHERE Id='".$_POST['Projet']."'";
	
	$resultUpdate=mysqli_query($bdd,$requeteUpdate);
	
	//#################
	//##### EMAIL #####
	//#################
	
	//Recherche du destinataire
	if ($_POST['Email'] <> ""){
		$Destinataires=$_POST['Email'];
		$headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
		$headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
		$objetMail= "Réponse - Demande de projet informatique";
		$message = "Projet : <br>";
		$message.= "<Table border=1>";
		$message.= "<tr><td width='40'>Titre</td><td>".$_POST['Titre']."</td></tr>";
		$message.= "<tr><td>Type</td><td>".$_POST['TypeDemande']."</td></tr>";
		$message.= "<tr><td>Importance (1 -> 10)</td><td>".$_POST['importance']."</td></tr>";
		$message.= "<tr><td>Technologie</td><td>".$_POST['Technologie']."</td></tr>";
		$message.= "<tr><td>Date livraison souhaitée</td><td>".$_POST['Livraison']."</td></tr>";
		$message.= "<tr><td>Description</td><td>".$_POST['Description']."</td></tr>";
		$message.= "</Table><br>";
		$message.= "Réponse : <br>";
		$message.= "<Table border=1>";
		$message.= "<tr><td width='40'>Etat</td><td>".$_POST['Etat']."</td></tr>";
		if ($DateLivraison <> ""){
		$message.= "<tr><td>Date de livraison possible</td><td>".$DateLivraison."</td></tr>";
		}
		if ($_POST['Avancement'] <> "0"){
		$message.= "<tr><td>Avancement</td><td>".$_POST['Avancement']."%</td></tr>";
		}
		$message.= "<tr><td>Commentaire</td><td>".$_POST['Commentaire']."</td></tr>";
		$message.= "</Table><br>";
		
		//if(mail($Destinataires, $objetMail , $message, $headers,'-f extranet@aaa-aero.com')){}
		//else{echo 'Le message n\'a pu être envoyé';}
	}
	echo "<script>FermerEtRecharger();</script>";
}

$Etat = "";
$Avancement = "";
$DateLivraisonPossible = "";
$Commentaire = "";

if ($_GET){
	$IdProjet = $_GET['Id_Projet'];
	$IdPersonne = $_GET['Id_Personne'];
}
elseif ($_POST){
	$IdProjet = $_POST['Projet'];
	$IdPersonne = $_POST['Personne'];
	
	$Etat = $_POST['Etat'];
	$Avancement = $_POST['Avancement'];
	$Commentaire = $_POST['Commentaire'];
	$DateLivraisonPossible = $_POST['dateLivraison'];
	$Email = $_POST['Email'];
}

$Titre = "";
$Importance = "";
$Technologie = "";
$Description = "";
$Livraison = "";
$TypeDemande = "";
$cdc = "";
$DateDemande = "";
$Personne = "";
$Email = "";
$req = "SELECT new_projet_informatique.Id, new_projet_informatique.Id_Developpeur, new_projet_informatique.DateDemande, new_projet_informatique.DateBesoin, ";
$req .= "new_projet_informatique.Importance, new_projet_informatique.Technologie, new_projet_informatique.Description, ";
$req .= "new_projet_informatique.Type, new_projet_informatique.Etat, new_projet_informatique.Titre, new_projet_informatique.DateReponse, ";
$req .= "new_projet_informatique.DatePossible, new_projet_informatique.Commentaire, new_projet_informatique.CDC, ";
$req .= "(SELECT new_rh_etatcivil.Nom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id = new_projet_informatique.Id_Demandeur) AS Nom, ";
$req .= "(SELECT new_rh_etatcivil.Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id = new_projet_informatique.Id_Demandeur) AS Prenom, ";
$req .= "(SELECT new_rh_etatcivil.EmailPro FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id = new_projet_informatique.Id_Demandeur) AS Email, ";
$req .= "new_projet_informatique.Avancement ";
$req .= "FROM new_projet_informatique ";
$req .= "WHERE new_projet_informatique.Id=".$IdProjet."";
$result=mysqli_query($bdd,$req);
$nbProjet=mysqli_num_rows($result);
if ($nbProjet > 0){
	$row=mysqli_fetch_array($result);
	$Titre = $row['Titre'];
	$Importance = $row['Importance'];
	$Technologie = $row['Technologie'];
	$Description = stripslashes($row['Description']);
	$Personne = $row['Nom']." ".$row['Prenom'];
	$Email = $row['Email'];
	if ($_GET){
		$Etat = $row['Etat'];
		$Avancement = $row['Avancement'];
		$Commentaire = $row['Commentaire'];
	}
	if ($NavigOk ==1){
		$Livraison = $row['DateBesoin'];
		if ($_GET){
			$DateLivraisonPossible = $row['DatePossible'];
		}
	}
	else{
		$tabDateLivraison = explode('-', $row['DateBesoin']);
		$timestampDateLivraison = mktime(0, 0, 0, $tabDateLivraison[1], $tabDateLivraison[2], $tabDateLivraison[0]);
		$Livraison = date("d/m/Y", $timestampDateLivraison);
		
		if ($_GET){
			$tabDatePossible = explode('-', $row['DatePossible']);
			$timestampDatePossible = mktime(0, 0, 0, $tabDatePossible[1], $tabDatePossible[2], $tabDatePossible[0]);
			$DateLivraisonPossible = date("d/m/Y", $timestampDatePossible);
		}
	}
	
	if ($_GET){
		if ($row['DatePossible'] == 0){
			$DateLivraisonPossible = "";
		}
	}
	$TypeDemande = $row['Type'];
	$DateDemande = $row['DateDemande'];
	$cdc = $row['CDC'];
}
?>
<!-- Script DATE  -->
<script>
	var initDatepicker = function() {  
	$('input[type=date]').each(function() {  
		var $input = $(this);  
		$input.datepicker({  
			minDate: $input.attr('min'),  
			maxDate: $input.attr('max'),  
			dateFormat: 'dd/mm/yy'  
			});  
		});  
	};  
	  
	if(!Modernizr.inputtypes.date){  
		$(document).ready(initDatepicker);  
	}; 
 </script>
<form name="formProjet"  enctype="multipart/form-data" class="test" method="POST" action="ModifProjet.php">
	<table style="width:100%; border-spacing:0; align:center;">
		<tr style="width:10%;">
			<td>
				<table style="width:100%; border-spacing:0;">
					<tr>
						<td width="4"></td>
						<td class="TitrePage">Informatique # Ajouter une demande de projet informatique</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr><td>
			<table class="TableCompetences" style="width:100%; border-spacing:0; align:center;">
				<tr style="display:none;">
					<td><input type="text" name="Personne" size="1" value="<?php echo $IdPersonne; ?>"></td>
					<td><input type="text" name="Projet" size="1" value="<?php echo $IdProjet; ?>"></td>
					<td><input type="text" name="Titre" size="1" value="<?php echo $Titre; ?>"></td>
					<td><input type="text" name="TypeDemande" size="1" value="<?php echo $TypeDemande; ?>"></td>
					<td><input type="text" name="Technologie" size="1" value="<?php echo $Technologie; ?>"></td>
					<td><input type="text" name="Livraison" size="1" value="<?php echo $Livraison; ?>"></td>
					<td><input type="text" name="importance" size="1" value="<?php echo $Importance; ?>"></td>
					<td><input type="text" name="Email" size="1" value="<?php echo $Email; ?>"></td>
				</tr>
				<tr>
					<td width="250"><h3>Demandeur</h3></td>
				</tr>
				<tr>
					<td>Demandeur : </td>
					<td><?php echo $Personne; ?></td>
					<td>Date : </td>
					<td><?php echo $DateDemande; ?></td>
				</tr>
				<tr><td height="2"></td></tr>
				<tr>
					<td>Type : </td>
					<td><?php echo $TypeDemande; ?></td>
					<td>Importance (1->10) : </td>
					<td><?php echo $Importance; ?></td>
				</tr>
				<tr>
					<td>Titre de la demande : </td>
					<td colspan="3"><?php echo $Titre; ?></td>
				</tr>
				<tr>
					<td>Technologie souhaitée <br>(Excel, Access, Web, …) : </td>
					<td><?php echo $Technologie; ?></td>
					<td>Date de livraison souhaitée : </td>
					<td><?php echo $Livraison; ?></td>
				</tr>
				<tr>
					<td>Description : </td>
					<td colspan="3"><textarea readonly="readonly"  class="DescriptionTextareaRead" name="Description" rows="0" cols="0" style="resize:none;"><?php echo $Description; ?></textarea></td>
				</tr>
				<tr>
					<td>Cahier des charges : </td>
					<td>
						<?php 
						$CheminFichier= "CDC/".$cdc;
						echo "<a class='Info' href='".$CheminFichier."' target='_blank'>".$cdc."</a>";
						?>
					</td>
				</tr>
			</table>
		</td></tr>
		<tr><td>
			<table class="TableCompetences" style="width:100%; border-spacing:0; align:center;">
				<tr>
					<td width="250"><h3>Réponse</h3></td>
				</tr>
				<tr>
					<td>Etat : </td>
					<td>
						<select class="Etat" name="Etat">
							<option value="Attente retour informatique" <?php if($Etat == "Attente retour informatique"){echo "Selected";}?>>Attente retour informatique</option>
							<option value="Cahier des charges insuffisant" <?php if($Etat == "Cahier des charges insuffisant"){echo "Selected";}?>>Cahier des charges insuffisant</option>
							<option value="Attente validation du projet" <?php if($Etat == "Attente validation du projet"){echo "Selected";}?>>Attente validation du projet</option>
							<option value="Validé" <?php if($Etat == "Validé"){echo "Selected";}?>>Validé</option>
							<option value="Refusé" <?php if($Etat == "Refusé"){echo "Selected";}?>>Refusé</option>
							<option value="En cours de développement" <?php if($Etat == "En cours de développement"){echo "Selected";}?>>En cours de développement</option>
							<option value="Phase de test" <?php if($Etat == "Phase de test"){echo "Selected";}?>>Phase de test</option>
							<option value="Déploiement" <?php if($Etat == "Déploiement"){echo "Selected";}?>>Déploiement</option>
							<option value="Terminé" <?php if($Etat == "Terminé"){echo "Selected";}?>>Terminé</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Avancement (en %): </td>
					<td><input type="text" name="Avancement" size="5" value="<?php echo $Avancement; ?>"></td>
				</tr>
				
				<tr>
					<td>Date de livraison possible : </td>
					<td><input type="date" style="text-align:center;" name="dateLivraison" size="15" value="<?php echo $DateLivraisonPossible; ?>"></td>
				</tr>
				
				<tr>
					<td>Commentaire : </td>
					<td><textarea class="CommentaireTextarea" name="Commentaire" rows="0" cols="0" style="resize:none;"><?php echo $Commentaire; ?></textarea></td>
				</tr>
				<tr align="center">
					<td  colspan="7" align="center" style="text-align:center;">
						<input class="Bouton" name="submitValider" type="submit" value='Valider'>
					</td>
				</tr>
			</table>
		</td></tr>
	</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>
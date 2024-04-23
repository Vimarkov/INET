<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<!-- Feuille de style -->
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Planning.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function FermerEtRechargerInfosPersonnel(Id_Prestation,Id_Pole)
		{
			opener.location.href="InformationsPersonnel.php?Id_Prestation="+Id_Prestation+"&Id_Pole="+Id_Pole;
			window.close();
		}
	</script>
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->	
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
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
</head>

<?php
require("../Connexioni.php");
require("../Fonctions.php");


if(isset($_POST['submitValider'])){
	//Mise à jour de l'enregistrement new_planning_personne_vacationabsence
	$requete="UPDATE new_rh_etatcivil SET ";
	$requete.="TelephoneProMobil='".$_POST['TelephoneProMobil']."', ";
	$requete.="TelephoneProFixe='".$_POST['TelephoneProFixe']."', ";
	$requete.="EmailPro='".$_POST['EmailPro']."', ";
	$requete.="NumBadge='".$_POST['NumBadge']."', ";
	$requete.="Matricule='".$_POST['Matricule']."' ";
	$requete.=" WHERE Id='".$_POST['Personne']."'";
	$result=mysqli_query($bdd,$requete);

	$resultUpdate=mysqli_query($bdd,$requete);
	//Fermeture de la fenêtre et rechargement
	echo "<script>FermerEtRechargerInfosPersonnel('".$_POST['Prestation']."','".$_POST['Pole']."');</script>";
 }
 if($_POST){
	$IdPrestation = $_POST['Prestation'];
	$IdPole = $_POST['Pole'];
	$IdPersonne = $_POST['Personne'];
}
elseif($_GET){
	$IdPrestation = $_GET['Id_Prestation'];
	$IdPole = $_GET['Id_Pole'];
	$IdPersonne = $_GET['Id_Personne'];
}

//Personnes  présentent sur cette prestation à ces dates
$req = "SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne, ";
$req .= "(SELECT new_competences_metier.Libelle FROM new_competences_metier WHERE new_competences_metier.Id = new_competences_personne_metier.Id_Metier) AS Metier, ";
$req .= "new_rh_etatcivil.TelephoneProFixe, ";
$req .= "new_rh_etatcivil.TelephoneProMobil, ";
$req .= "new_rh_etatcivil.EmailPro, ";
$req .= "new_rh_etatcivil.NumBadge, ";
$req .= "new_rh_etatcivil.Matricule, ";
$req .= "new_rh_etatcivil.Date_Naissance ";
$req .= "FROM (new_competences_personne_prestation RIGHT JOIN new_rh_etatcivil ON new_rh_etatcivil.Id = new_competences_personne_prestation.Id_Personne) ";
$req .= "LEFT JOIN new_competences_personne_metier ON new_competences_personne_metier.Id_Personne = new_rh_etatcivil.Id ";
$req .= "WHERE new_rh_etatcivil.Id=".$IdPersonne.";";

$resultPersonne=mysqli_query($bdd,$req);
$nbPersonne=mysqli_num_rows($resultPersonne);

$reqPresta = "SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id ='".$IdPrestation."';";
$resultPresta=mysqli_query($bdd,$reqPresta);
$nbPresta=mysqli_num_rows($resultPresta);
?>

</script>
	<table class="TableCompetences" width=100%>
		 <form id="formulaire" method="post" action="ModifierInformationsPersonnel.php">
			<tr style="display:none;">
				<td><input type="text" name="Personne" size="11" value="<?php echo $IdPersonne; ?>"></td>
				<td><input type="text" name="Prestation" size="11" value="<?php echo $IdPrestation; ?>"></td>
				<td><input type="text" name="Pole" size="11" value="<?php echo $IdPole; ?>"></td>
			</tr>
			<?php
			if ($nbPersonne > 0){
				$row=mysqli_fetch_array($resultPersonne);
			?>
			<tr>
				<td>Personne :</td>
				<td>
					<?php echo $row[0]; ?>
				</td>
				<td>
					Métier :
				</td>
				<td>
					<?php echo $row[1]; ?>
				</td>
			</tr>
			<tr>
				<td>
					Prestation :
				</td>
				<td>
					<?php
					if ($nbPresta > 0){
						$rowPresta=mysqli_fetch_array($resultPresta);
						echo $rowPresta[0];
					}
					?>
				</td>
			</tr>
			<tr>
				<td>Téléphone pro fixe : </td>
				<td><input name="TelephoneProFixe" size="20" value="<?php echo $row['TelephoneProFixe'];?>"></td>
				<td>Téléphone pro mobile : </td>
				<td><input name="TelephoneProMobil" size="20" value="<?php echo $row['TelephoneProMobil'];?>"></td>
			</tr>
			<tr>
				<td>Email pro: </td>
				<td><input name="EmailPro" size="20" value="<?php echo $row['EmailPro'];?>"></td>
			</tr>
			<tr>
				<td>N° de badge : </td>
				<td><input name="NumBadge" size="20" value="<?php echo $row['NumBadge'];?>"></td>
				<td>NG/ST : </td>
				<td><input name="Matricule" size="20" value="<?php echo $row['Matricule'];?>"></td>
			</tr>
			<tr>
				<td colspan="4" align="center">
					<input class="Bouton" name="submitValider" type="submit" value='Valider'>
				</td>
			</tr>
			<?php
			}
			?>
		</form>
	</table>

</body>
</html>
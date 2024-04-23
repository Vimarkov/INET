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
		
		function validateForm() {
			
			var titre = document.forms["formProjet"]["Titre"].value;
			if (titre == null || titre == "") {
				alert("Veuillez renseigner le titre");
				return false;
			}
			var Technologie = document.forms["formProjet"]["Technologie"].value;
			if (Technologie == null || Technologie == "") {
				alert("Veuillez renseigner la technologie souhaitée");
				return false;
			}
			var dateLivraison = document.forms["formProjet"]["dateLivraison"].value;
			if (dateLivraison == null || dateLivraison == "") {
				alert("Veuillez renseigner la date de livraison souhaitée");
				return false;
			}
			var Description = document.forms["formProjet"]["Description"].value;
			if (Description == null || Description == "") {
				alert("Veuillez renseigner une description du besoin");
				return false;
			}
			var fichier = document.forms["formProjet"]["fichier"].value;
			if (fichier == null || fichier == "") {
				alert("Veuillez joindre le cahier des charges.");
				return false;
			}
		}
	</script>
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->	
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
</head>

<?php
require_once("../Connexioni.php");
require_once("../Fonctions.php");

if(isset($_POST['submitValider'])){
	$DirFichier="CDC/";
	$Valide = false;
	//****TRANSFERT FICHIER****
	if($_FILES['fichier']['name']!="")
	{
		$SrcProblem = "";
		$tmp_file=$_FILES['fichier']['tmp_name'];
		if(!is_uploaded_file($tmp_file)){$SrcProblem.="<br>Le fichier est introuvable";$Problem=1;$NomFichier="";}
		else
		{
			//On verifie l'extension
			$type_file=strrchr($_FILES['fichier']['name'], '.'); 
			if($type_file !='.docx')
				{$SrcProblem.="<br>Le fichier doit être au format .docx";$Problem=1;$NomImage="";}
			else
			{
				//On vérifie la taille du fichiher
				if(filesize($_FILES['fichier']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
					{$SrcProblem.="<br>Le fichier est trop volumineux";$Problem=1;$NomFichier="";}
				else{
					$Valide = true;
				}
			}
		}
	}
	
	if ($Valide == true){
		if ($NavigOk ==1){
			$tabDateLivraison = explode('-', $_POST['dateLivraison']);
			$timestampDateLivraison = mktime(0, 0, 0, $tabDateLivraison[1], $tabDateLivraison[2], $tabDateLivraison[0]);
		}
		else{
			$tabDateLivraison = explode('/', $_POST['dateLivraison']);
			$timestampDateLivraison = mktime(0, 0, 0, $tabDateLivraison[1], $tabDateLivraison[0], $tabDateLivraison[2]);
			
		}
		$DateLivraison = date("Y-m-d", $timestampDateLivraison);
		
		if ($_POST['TypeProjet'] == "M"){
			$requeteUpdate="UPDATE new_projet_informatique ";
			$requeteUpdate.="SET DateDemande='".$DateJour."', ";
			$requeteUpdate.="DateBesoin='".$DateLivraison."', ";
			$requeteUpdate.="Importance='".$_POST['importance']."', ";
			$requeteUpdate.="Technologie='".addslashes($_POST['Technologie'])."', ";
			$requeteUpdate.="Description='".addslashes($_POST['Description'])."', ";
			$requeteUpdate.="Type='".addslashes($_POST['TypeDemande'])."', ";
			$requeteUpdate.="Titre='".addslashes($_POST['Titre'])."' ";
			$requeteUpdate.="WHERE Id='".$_POST['Projet']."'";
			
			$resultUpdate=mysqli_query($bdd,$requeteUpdate);
			
			// on copie le fichier dans le dossier de destination
			$leFichier="CDC_".$_POST['Projet'].".docx";
			
			if(!unlink($DirFichier.$leFichier)){$SrcProblem.="<br>Impossible de supprimer le fichier.";$Problem=1;}
			if(!move_uploaded_file($tmp_file,$DirFichier.$leFichier))
				{$SrcProblem.="<br>Impossible de copier le fichier.";$Problem=1;$NomFichier="";}
				
			
			//#################
			//##### EMAIL #####
			//#################
			$Destinataires="extranet@aaa-aero.com";
			$headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
			$headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
			$objetMail= "Modification - Demande de projet informatique - ".$_POST['NomPersonne']."";
			$message= "<Table border=1>";
			$message.= "<tr><td width='40'>Titre</td><td>".$_POST['Titre']."</td></tr>";
			$message.= "<tr><td>Type</td><td>".$_POST['TypeDemande']."</td></tr>";
			$message.= "<tr><td>Importance (1 -> 10)</td><td>".$_POST['importance']."</td></tr>";
			$message.= "<tr><td>Technologie</td><td>".$_POST['Technologie']."</td></tr>";
			$message.= "<tr><td>Date livraison souhaitée</td><td>".$_POST['dateLivraison']."</td></tr>";
			$message.= "<tr><td>Description</td><td>".$_POST['Description']."</td></tr>";
			$message.= "<tr><td>Nom du cahier des charges</td><td>".$leFichier."</td></tr>";
			$message.= "</Table>";
			
			if(mail($Destinataires, $objetMail , $message, $headers,'-f extranet@aaa-aero.com')){}
			else{echo 'Le message n\'a pu être envoyé';}
			
			echo "<script>alerte('La demande a été prise en compte.');</script>";
			echo "<script>FermerEtRecharger();</script>";
		}
		else{
			$requeteInsert="INSERT INTO new_projet_informatique (Id_Demandeur, DateDemande, DateBesoin, Importance, Technologie, ";
			$requeteInsert.="Description, Type, Etat, Titre) ";
			$requeteInsert.="VALUES ";
			$requeteInsert.="('".$_POST['Personne']."','".$DateJour."','".$DateLivraison."','".$_POST['importance']."','".addslashes($_POST['Technologie'])."',";
			$requeteInsert.="'".addslashes($_POST['Description'])."','".addslashes($_POST['TypeDemande'])."','Attente retour informatique','".addslashes($_POST['Titre'])."')";
			$resultAjout=mysqli_query($bdd,$requeteInsert);
			$UID=mysqli_insert_id($bdd); 
			$requeteUpdate="UPDATE new_projet_informatique ";
			$requeteUpdate.="SET CDC='CDC_".$UID.".docx' ";
			$requeteUpdate.="WHERE Id=".$UID."";
			$resultUpdate=mysqli_query($bdd,$requeteUpdate);
			
			$leFichier="CDC_".$UID.".docx";
			// on copie le fichier dans le dossier de destination
			if(!move_uploaded_file($tmp_file,$DirFichier.$leFichier))
				{$SrcProblem.="<br>Impossible de copier le fichier.";$Problem=1;$NomFichier="";}
			
			//#################
			//##### EMAIL #####
			//#################
			$Destinataires="extranet@aaa-aero.com";
			$headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
			$headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
			$objetMail= "Demande de projet informatique - ".$_POST['NomPersonne']."";
			$message= "<Table border=1>";
			$message.= "<tr><td width='40'>Titre</td><td>".$_POST['Titre']."</td></tr>";
			$message.= "<tr><td>Type</td><td>".$_POST['TypeDemande']."</td></tr>";
			$message.= "<tr><td>Importance (1 -> 10)</td><td>".$_POST['importance']."</td></tr>";
			$message.= "<tr><td>Technologie</td><td>".$_POST['Technologie']."</td></tr>";
			$message.= "<tr><td>Date livraison souhaitée</td><td>".$_POST['dateLivraison']."</td></tr>";
			$message.= "<tr><td>Description</td><td>".$_POST['Description']."</td></tr>";
			$message.= "<tr><td>Nom du cahier des charges</td><td>".$leFichier."</td></tr>";
			$message.= "</Table>";
			
			if(mail($Destinataires, $objetMail , $message, $headers,'-f extranet@aaa-aero.com')){echo $message;}
			else{echo 'Le message n\'a pu être envoyé';}
			
			echo "<script>alerte('La demande a été prise en compte.');</script>";
			echo "<script>FermerEtRecharger();</script>";
		}
	}
	else{
		echo $SrcProblem;
		$Titre = $_POST['Titre'];
		$Importance = $_POST['importance'];
		$Technologie = $_POST['Technologie'];
		$Description = $_POST['Description'];
		$Livraison = $_POST['dateLivraison'];
		$TypeDemande = $_POST['TypeDemande'];
	}
}

if ($_GET){
	$TypeProjet = $_GET['Type'];
	$IdProjet = $_GET['Id_Projet'];
	$IdPersonne = $_GET['Id_Personne'];
	
	$Titre = "";
	$Importance = "";
	$Technologie = "";
	$Description = "";
	$Livraison = "";
	$TypeDemande = "";
	$cdc = "";
	$DateDemande = $DateJour;
	if ($TypeProjet == "M"){
		$req = "SELECT new_projet_informatique.Id, new_projet_informatique.Id_Developpeur, new_projet_informatique.DateDemande, new_projet_informatique.DateBesoin, ";
		$req .= "new_projet_informatique.Importance, new_projet_informatique.Technologie, new_projet_informatique.Description, ";
		$req .= "new_projet_informatique.Type, new_projet_informatique.Etat, new_projet_informatique.Titre, new_projet_informatique.DateReponse, ";
		$req .= "new_projet_informatique.DatePossible, new_projet_informatique.Commentaire, new_projet_informatique.CDC ";
		$req .= "FROM new_projet_informatique ";
		$req .= "WHERE new_projet_informatique.Id=".$IdProjet."";
		$result=mysqli_query($bdd,$req);
		$nbProjet=mysqli_num_rows($result);
		if ($nbProjet > 0){
			$row=mysqli_fetch_array($result);
			$Titre = $row['Titre'];
			$Importance = $row['Importance'];
			$Technologie = $row['Technologie'];
			$Description = $row['Description'];
			if ($NavigOk ==1){
				$Livraison = $row['DateBesoin'];
			}
			else{
				$tabDateLivraison = explode('-', $row['DateBesoin']);
				$timestampDateLivraison = mktime(0, 0, 0, $tabDateLivraison[1], $tabDateLivraison[2], $tabDateLivraison[0]);
				$Livraison = date("d/m/Y", $timestampDateLivraison);
			}
			$TypeDemande = $row['Type'];
			$DateDemande = $row['DateDemande'];
			$cdc = "CDC/".$row['CDC'];
			
		}
	}
}
elseif ($_POST){
	$TypeProjet = $_POST['TypeProjet'];
	$IdProjet = $_POST['Projet'];
	$IdPersonne = $_POST['Personne'];
	$Titre = $_POST['Titre'];
	$Importance = $_POST['importance'];
	$Technologie = $_POST['Technologie'];
	$Description = $_POST['Description'];
	$Livraison = $_POST['dateLivraison'];
	$TypeDemande = $_POST['TypeDemande'];
	$DateDemande = $_POST['DateDemande'];
	$cdc = $_POST['cdc'];
}
$Personne = "";
$selectPersonne = "SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$IdPersonne."";
$resultPersonne=mysqli_query($bdd,$selectPersonne);
$nbPersonne=mysqli_num_rows($resultPersonne);
if ($nbPersonne > 0){
	$row=mysqli_fetch_array($resultPersonne);
	$Personne = $row['Prenom']." ".$row['Nom'];
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
 
<form name="formProjet"  enctype="multipart/form-data" class="test" method="POST" action="AjoutProjet.php" onsubmit="return validateForm()">
	<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
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
					<td><input type="text" name="TypeProjet" size="1" value="<?php echo $TypeProjet; ?>"></td>
					<td><input type="text" name="cdc" size="1" value="<?php echo $cdc; ?>"></td>
					<td><input type="text" name="DateDemande" size="1" value="<?php echo $DateDemande; ?>"></td>
					<td><input type="text" name="NomPersonne" size="1" value="<?php echo $Personne; ?>"></td>
				</tr>
				<tr>
					<td>Demandeur : <?php echo $Personne; ?></td>
					<td>&nbsp; Date : <?php echo $DateDemande; ?></td>
				</tr>
				<tr><td height="2"></td></tr>
				<tr>
					<td>Type : </td>
					<td>
						<input type="radio" name="TypeDemande" value="Creation" <?php if ($TypeDemande == "Creation" || $TypeDemande == ""){echo "checked";}?>>Creation
						<input type="radio" name="TypeDemande" value="Evolution" <?php if ($TypeDemande == "Evolution"){echo "checked";}?>>Evolution
					</td>
				</tr>
				<tr>
					<td>Titre de la demande : </td>
					<td><input type="text" name="Titre" size="110" value="<?php echo $Titre; ?>"></td>
				</tr>
				<tr>
					<td>Importance (1->10) : </td>
					<td>
						<input type="radio" name="importance" value="1" <?php if ($Importance == "1" || $Importance == ""){echo "checked";}?>><font color="#00b050">1</font>
						<input type="radio" name="importance" value="2" <?php if ($Importance == "2"){echo "checked";}?>><font color="#00b050">2</font>
						<input type="radio" name="importance" value="3" <?php if ($Importance == "3"){echo "checked";}?>><font color="#00b050">3</font>
						<input type="radio" name="importance" value="4" <?php if ($Importance == "4"){echo "checked";}?>><font color="#00b050">4</font>
						<input type="radio" name="importance" value="5" <?php if ($Importance == "5"){echo "checked";}?>><font color="#ffc000">5</font>
						<input type="radio" name="importance" value="6" <?php if ($Importance == "6"){echo "checked";}?>><font color="#ffc000">6</font>
						<input type="radio" name="importance" value="7" <?php if ($Importance == "7"){echo "checked";}?>><font color="#ffc000">7</font>
						<input type="radio" name="importance" value="8" <?php if ($Importance == "8"){echo "checked";}?>><font color="#FF0000">8</font>
						<input type="radio" name="importance" value="9" <?php if ($Importance == "9"){echo "checked";}?>><font color="#FF0000">9</font>
						<input type="radio" name="importance" value="10" <?php if ($Importance == "10"){echo "checked";}?>><font color="#FF0000">10</font>
					</td>
				</tr>
				<tr>
					<td>Technologie souhaitée <br>(Excel, Access, Web, …) : </td>
					<td><input type="text" name="Technologie" size="60" value="<?php echo $Technologie; ?>"></td>
				</tr>
				<tr>
					<td>Date de livraison souhaitée : </td>
					<td><input type="date" style="text-align:center;" name="dateLivraison" size="15" value="<?php echo $Livraison; ?>"></td>
				</tr>
				<tr>
					<td>Description : </td>
					<td><textarea class="DescriptionTextarea" name="Description" rows="0" cols="0" style="resize:none;"><?php echo $Description; ?></textarea></td>
				</tr>
				<tr>
					<td>Veuillez joindre le cahier des charges complété: </td>
					<td><input name="fichier" type="file" value="<?php echo $cdc;?>">
					<?php
						$CheminFichier="Cahier des charges informatique - Vierge.docx";
						if ($cdc != ""){
							$CheminFichier= $cdc;
							echo "<a class='Info' href='".$CheminFichier."' target='_blank'>Cahier des charges existant</a>";
						}
					?>
					</td>
				</tr>
				<tr>
    				<td>
    					<a class="Info" href="<?php echo $CheminFichier; ?>" target="_blank"> # Ouvrir cahier des charges vierge # </a>
    				</td>
    			</tr>
				<tr><td colspan="4"><font color="#FF0000" size="-2">Limite de taille du fichier à 10 Mo.</font></td></tr>
				
				<tr><td height="8"></td></tr>
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
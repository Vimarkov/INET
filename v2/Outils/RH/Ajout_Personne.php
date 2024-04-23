<html>
<head>
	<title>Compétences - Personne</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps()
		{
			if(formulaire.Nom.value==''){alert('Vous n\'avez pas renseigné le nom.');return false;}
			if(formulaire.Prenom.value==''){alert('Vous n\'avez pas renseigné le prénom.');return false;}
			if(formulaire.dateNaissance.value==''){alert('Vous n\'avez pas renseigné la date de naissance.');return false;}
			if(formulaire.Contrat.value==''){alert('Vous n\'avez pas renseigné le contrat.');return false;}
			if(formulaire.Contrat.value=='Externe' || formulaire.Contrat.value=='Sous-traitant'){
				if(formulaire.Societe.value==''){alert('Vous n\'avez pas renseigné la société.');return false;}
			}
			else{
				if(formulaire.emailPerso.value==''){alert('Vous n\'avez pas renseigné l\'email de la personne.');return false;}
			}
			return true;
		}
		
		function AfficherChamps(){
			var elements = document.getElementsByClassName("cSociete");
			var elementsE = document.getElementsByClassName("cEmail");
			if(formulaire.Contrat.value=='Externe' || formulaire.Contrat.value=='Sous-traitant' || formulaire.Contrat.value=='Consultant'){
				for(var i=0, l=elements.length; i<l; i++){elements[i].style.display="";}
				for(var i=0, l=elementsE.length; i<l; i++){elementsE[i].style.display="none";}
			}
			else{
				for(var i=0, l=elements.length; i<l; i++){elements[i].style.display="none";}
				for(var i=0, l=elementsE.length; i<l; i++){elementsE[i].style.display="";}
			}
		}
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
		
		function Ajout_Plateforme_Metier(Id)
			{window.open('../Competences/Ajout_Profil_Plateforme_Metier.php?Id_Personne='+Id,"PageFichier","status=no,menubar=no,width=50,height=300");}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("../PlanningV2/Fonctions_Planning.php");

function ucname($string)
{
    $string =ucwords(strtolower($string));
    if (strpos($string, '-')!==false) {$string =implode('-', array_map('ucfirst', explode('-', $string)));}
    return $string;
}

// Génération d'une chaine aléatoire
function chaine_aleatoire($nb_car, $chaine = 'azertyuiopqsdfghjklmwxcvbn123456789')
{
    $nb_lettres = strlen($chaine) - 1;
    $generation = '';
    for($i=0; $i < $nb_car; $i++)
    {
        $pos = mt_rand(0, $nb_lettres);
        $car = $chaine[$pos];
        $generation .= $car;
    }
    return $generation;
}

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
		$result=mysqli_query($bdd,"SELECT * FROM new_rh_etatcivil WHERE Nom='".trim($_POST['Nom'])."' AND Prenom='".trim($_POST['Prenom'])."' AND Date_Naissance='".TrsfDate_($_POST['dateNaissance'])."' ");
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"INSERT INTO new_rh_etatcivil (Nom, Prenom,Date_Naissance,Email,Contrat,Societe) VALUES (\"".trim(strtoupper($_POST['Nom']))."\",\"".trim(ucname($_POST['Prenom']))."\",\"".TrsfDate_($_POST['dateNaissance'])."\",\"".$_POST['emailPerso']."\",\"".$_POST['Contrat']."\",\"".addslashes($_POST['Societe'])."\")");
			$Id=mysqli_insert_id($bdd);
			
			//Verifier si cette personne n'a pas dejà accès
			$login=str_replace("'","",strtolower(substr(trim(ucname($_POST['Prenom'])),0,1).trim($_POST['Nom'])));
			$login=str_replace(" ","",$login);
	
			//Vérifier existance Login dans la base
			$select = "SELECT Id FROM new_rh_etatcivil WHERE Login LIKE '".$login."%'";
			$result=mysqli_query($bdd,$select);
			$nbResulta=mysqli_num_rows($result);
			if($nbResulta>0){
				$bOK=0;
				$compteur=1;
				
				//Tant que le login n'est pas unique 
				while($bOK==0){
					$select = "SELECT Id FROM new_rh_etatcivil WHERE Login LIKE '".$login.$compteur."%'";
					$resultLogin=mysqli_query($bdd,$select);
					$nbLogin=mysqli_num_rows($resultLogin);
					if($nbLogin==0){$bOK=1;}
					else{
						$compteur++;
					}
				}
				$login=$login.$compteur;
			}
			
			if($_POST['Contrat']=="Sous-traitant" || $_POST['Contrat']=="Externe" || $_POST['Contrat']=="Consultant"){
				$login=$login.".external";
			}
			
			$MotDePasse=chaine_aleatoire(8);
			
			$requete="UPDATE new_rh_etatcivil SET ";
			$requete.="Login='".$login."', ";
			$requete.="Motdepasse='".$MotDePasse."' ";
			$requete.=" WHERE Id=".$Id;
			$result=mysqli_query($bdd,$requete);
			
			if($_POST['Contrat']<>"Consultant" && $_POST['Contrat']<>"Sous-traitant" && $_POST['Contrat']<>"Externe"){
				//Envoi d'un Email pour informer l'utilisateur
				GenererMailIdentifiantsExtranetV2($_POST['Nom'],$_POST['Prenom'],$login,$MotDePasse,$_POST['emailPerso'],$_SESSION['Langue']);
			}
			
			echo "<script>Ajout_Plateforme_Metier('".$Id."');</script>";
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Cette personne existe déjà.<br>Vous devez recommencer l'opération.</font>";}
	}
	elseif($_POST['Mode']=="Modif")
	{
		$result=mysqli_query($bdd,"SELECT * FROM new_rh_etatcivil WHERE Nom=\"".trim($_POST['Nom'])."\" AND Prenom=\"".trim($_POST['Prenom'])."\" AND Date_Naissance=\"".TrsfDate_($_POST['dateNaissance'])."\" AND Id!=".$_POST['Id']);
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"UPDATE new_rh_etatcivil SET Nom=\"".trim(strtoupper($_POST['Nom']))."\", Prenom=\"".trim(ucname($_POST['Prenom']))."\", Contrat='".$_POST['Contrat']."', Societe='".addslashes($_POST['Societe'])."', Date_Naissance=\"".TrsfDate_($_POST['dateNaissance'])."\" WHERE Id=".$_POST['Id']);
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Cette personne existe déjà.<br>Vous devez recommencer l'opération.</font>";}
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT * FROM new_rh_etatcivil WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Personne.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="Modif"){echo $row['Id'];}?>">
		<table style="width:95%; height:95%; align:center;">
			<tr class="TitreColsUsers">
				<td class="Libelle">Nom : </td>
				<td><input name="Nom" size="20" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Nom'];}?>"></td>
				<td class="Libelle">Prénom : </td>
				<td><input name="Prenom" size="20" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Prenom'];}?>"></td>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de naissance : ";}else{echo "Birth date : ";} ?></td>
				<td><input type="date" style="text-align:center;" id="dateNaissance" name="dateNaissance" size="10" value="<?php if($_GET['Mode']=="Modif"){echo $row['Date_Naissance'];}?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle">Contrat :</td>
				 <td>
					<select name="Contrat" onchange="AfficherChamps()">
					<?php
						$Tableau=array('','CDI','CDIC','CDD','Intérimaire','Alternance','Stage','AFPR','Consultant','Sous-traitant','Externe');
						foreach($Tableau as $indice => $valeur)
						{
							echo "<option value='".$valeur;
							if($_GET['Mode']=="Modif"){if($row['Contrat']==$valeur){echo "' selected>";}else{echo "'>";}}
							else{echo "'>";}
							echo $valeur."</option>";
						}
					?>
					</select>
				</td>
				<td class="Libelle cSociete" <?php if($_GET['Mode']=="Modif"){if($row['Contrat']<>"Externe" && $row['Contrat']<>"Sous-traitant" && $row['Contrat']<>"Consultant"){echo "style='display:none;'";}}else{echo "style='display:none;'";}?>>Société :</td>
				<td class="cSociete" <?php if($_GET['Mode']=="Modif"){if($row['Contrat']<>"Externe" && $row['Contrat']<>"Sous-traitant" && $row['Contrat']<>"Consultant"){echo "style='display:none;'";}}else{echo "style='display:none;'";}?>><input name="Societe" size="20" type="text" value="<?php if($_GET['Mode']=="Modif"){echo stripslashes($row['Societe']);}?>"></td>
			</tr>
			<?php 
				if($_GET['Mode']=="Ajout"){
			?>
			<tr>
				<td class="Libelle cEmail"><?php if($_SESSION["Langue"]=="FR"){echo "Email personnel : ";}else{echo "Personal email : ";} ?>
				</td>
				<td colspan="6" class="cEmail">
					<input name="emailPerso" id="emailPerso" size="30" value="">
					<?php 
						if($_SESSION["Langue"]=="FR"){echo "Non nécessaire pour les consultant, externe, sous-traitant";}
						else{echo "Not required for consultants, outsourcers, subcontractors";} 
						?>
				</td>
			</tr>
			<tr>
				<td colspan="6" class="cEmail">
					<i><?php 
						if($_SESSION["Langue"]=="FR"){echo "un mail sera envoyé à cette adresse avec le login et mot de passe Extranet de la personne (Hors Consultant, Externe, Sous-traitant)";}
						else{echo "an e-mail will be sent to this address with the person's Extranet login and password (Excluding consultant, external, subcontractor)";} 
						?>
					</i>
				</td>
			</tr> 
			<?php 
				}
			?>
			<tr class="TitreColsUsers">
				<td colspan="6" align="center">
					<input class="Bouton" type="submit" 
					<?php
						if($_GET['Mode']=="Modif"){echo "value='Valider'";}
						else{echo "value='Ajouter'";}
					?>
					>
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_relation WHERE Suppr=0 AND Id_Personne=".$_GET['Id']);
		$result2=mysqli_query($bdd,"SELECT * FROM new_rh_contrat WHERE Id_Personne=".$_GET['Id']);
		if(mysqli_num_rows($result)==0 && mysqli_num_rows($result2)==0)
		{
			$result=mysqli_query($bdd,"DELETE FROM new_rh_etatcivil WHERE Id=".$_GET['Id']);
			$result=mysqli_query($bdd,"DELETE FROM new_competences_personne_metier WHERE Id_Personne=".$_GET['Id']);
			$result=mysqli_query($bdd,"DELETE FROM new_competences_personne_fonction WHERE Id_Personne=".$_GET['Id']);
			$result=mysqli_query($bdd,"DELETE FROM new_competences_personne_diplome WHERE Id_Personne=".$_GET['Id']);
			$result=mysqli_query($bdd,"DELETE FROM new_competences_personne_plateforme WHERE Id_Personne=".$_GET['Id']);
			$result=mysqli_query($bdd,"DELETE FROM new_competences_personne_poste WHERE Id_Personne=".$_GET['Id']);
			$result=mysqli_query($bdd,"DELETE FROM new_competences_personne_prestation WHERE Id_Personne=".$_GET['Id']);
			$result=mysqli_query($bdd,"DELETE FROM new_competences_personne_fichehse WHERE Id_Personne=".$_GET['Id']);
			$result=mysqli_query($bdd,"DELETE FROM new_competences_personne_rh_eia WHERE Id_Personne=".$_GET['Id']);
			echo "<script>FermerEtRecharger();</script>";
		}
		else
		{
			if(mysqli_num_rows($result)>0){echo "Veuillez supprimer les compétences de la personne avant de la supprimer.";}
			if(mysqli_num_rows($result2)>0){echo "Veuillez supprimer les contrats de la personne avant de la supprimer.";}
		}
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
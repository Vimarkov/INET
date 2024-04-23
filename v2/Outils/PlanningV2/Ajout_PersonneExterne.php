<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function Enregistrer(){
		var valide = true;
		if(document.getElementById('Langue').value=="FR"){
			if(formulaire.nom.value==""){valide=false;alert("Veuillez compl�ter le nom");return false;}
			if(formulaire.prenom.value==""){valide=false;alert("Veuillez compl�ter le pr�nom");return false;}
			if(formulaire.nationalite.value==""){valide=false;alert("Veuillez compl�ter la nationalit�");return false;}
			if(formulaire.dateNaissance.value==""){valide=false;alert("Veuillez compl�ter la date de naissance");return false;}
			if(formulaire.contrat.value==""){valide=false;alert("Veuillez compl�ter le contrat");return false;}
			if(formulaire.societe.value==""){valide=false;alert("Veuillez compl�ter la nom de la soci�t�");return false;}
		}
		else{
			if(formulaire.nom.value==""){valide=false;alert("Please fill in the name");return false;}
			if(formulaire.prenom.value==""){valide=false;alert("Please fill in the first name");return false;}
			if(formulaire.nationalite.value==""){valide=false;alert("Please complete the nationality");return false;}
			if(formulaire.dateNaissance.value==""){valide=false;alert("Please fill in the date of birth");return false;}
			if(formulaire.contrat.value==""){valide=false;alert("Please fill in the contract");return false;}
			if(formulaire.societe.value==""){valide=false;alert("Please fill in the company name");return false;}
		}
		
		if(valide==true){
			var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
			document.getElementById('Ajouter').innerHTML=bouton;
			var evt = document.createEvent("MouseEvents");
			evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
			document.getElementById("btnEnregistrer2").dispatchEvent(evt);
			document.getElementById('Ajouter').innerHTML="";
		}
	}
</script>
<?php
$DateJour=date("Y-m-d");
$bEnregistrement=false;
$bExiste=false;

// G�n�ration d'une chaine al�atoire
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

if($_POST){
	if(isset($_POST['btnEnregistrer2'])){
		//V�rfier si le nom et pr�nom et date de naissance existe 
		$req="SELECT Id FROM new_rh_etatcivil WHERE Nom=\"".$_POST['nom']."\" AND Prenom=\"".$_POST['prenom']."\" AND Date_Naissance='".TrsfDate_($_POST['dateNaissance'])."' ";
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		if ($nbResulta>0){
			$bExiste=true;
		}
		else{
			$Login=str_replace("'","",strtolower(substr($_POST['prenom'],0,1).$_POST['nom']));
			$Login=str_replace(" ","",$Login);
			
			//V�rifier existance Login dans la base
			$select = "SELECT Id FROM new_rh_etatcivil WHERE Login LIKE '".$Login."%'";
			$result=mysqli_query($bdd,$select);
			$nbResulta=mysqli_num_rows($result);
			if($nbResulta>0){$Login=$Login.$nbResulta;}
			$Login=$Login.".external";
			
			$MotDePasse=chaine_aleatoire(8);
			
			$req="INSERT INTO new_rh_etatcivil 
				(Nom,Prenom,Sexe,Nationalite,Date_Naissance,Ville_Naissance,Contrat,Societe,Login,Motdepasse)
			VALUES 
				(\"".addslashes($_POST['nom'])."\",\"".addslashes($_POST['prenom'])."\",\"".$_POST['sexe']."\",\"".addslashes($_POST['nationalite'])."\",\"".TrsfDate_($_POST['dateNaissance'])."\"
				,\"".addslashes($_POST['lieuNaissance'])."\",\"".addslashes($_POST['contrat'])."\",\"".addslashes($_POST['societe'])."\",\"".$Login."\",\"".$MotDePasse."\")
			";
			$resultAjout=mysqli_query($bdd,$req);
			$Id_Personne=mysqli_insert_id($bdd);
			
			if($Id_Personne>0)
			{
				$req="INSERT INTO new_competences_personne_plateforme (Id_Personne,Id_Plateforme) VALUES (".$Id_Personne.",".$_POST['Id_Plateforme'].") ";
				$resultAjoutPlat=mysqli_query($bdd,$req);
			}
			$bEnregistrement=true;
		}
	}
}
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

$etoile="<img src='../../Images/etoile.png' width='8' height='8' border='0'>";
?>

<form id="formulaire" class="test" action="Ajout_PersonneExterne.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="VerifMatricule" id="VerifMatricule" value="" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing:0; background-color:#f53939;">
				<tr>
					<td class="TitrePage">
					<?php
						echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
						if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
						else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
						echo "</a>"; 
						echo "&nbsp;&nbsp;&nbsp;";
						if($_SESSION["Langue"]=="FR"){echo "D�claration d'une personne externe";}else{echo "Declaration of an external person";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<?php if($bEnregistrement==true){ ?>
		<tr><td colspan="6" align="center" style="color:#ff0000;font:bold;">
			<?php 
				if($_SESSION["Langue"]=="FR"){echo "Cette personne a �t� enregistr�e.";}
				else{echo "This person has been registered.";} 
			?>
			
		</td></tr>
		<tr><td height="4"></td></tr>
	<?php }
		elseif($bExiste==true){
	?>
		<tr><td colspan="6" align="center" style="color:#ff0000;font:bold;">
			<?php 
				if($_SESSION["Langue"]=="FR"){echo "Cette personne existe d�j�.";}
				else{echo "This person already exists.";} 
			?>
			
		</td></tr>
		<tr><td height="4"></td></tr>
	<?php
			
		}
	?>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="90%" align="center" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nom : ";}else{echo "Name : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<input name="nom" id="nom" size="15" value="<?php if($bExiste==true){echo $_POST['nom'];} ?>">
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Pr�nom : ";}else{echo "First name : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<input name="prenom" id="prenom" size="15" value="<?php if($bExiste==true){echo $_POST['prenom'];} ?>">
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Unit� d'exploitation :";}else{echo "Operating unit :";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<select name="Id_Plateforme" id="Id_Plateforme" style="width:150px">
									<?php
										$requetePlat="SELECT Id, Libelle
											FROM new_competences_plateforme
											WHERE Id NOT IN (11,14)
											ORDER BY Libelle";
										$resultsPlat=mysqli_query($bdd,$requetePlat);
										while($rowPlat=mysqli_fetch_array($resultsPlat))
										{
											$selected="";
											if($rowPlat['Id']==1){$selected="selected";}
											echo "<option value='".$rowPlat['Id']."' ".$selected.">";
											echo str_replace("'"," ",stripslashes($rowPlat['Libelle']))."</option>\n";
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Sexe : ";}else{echo "Gender : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<select name="sexe" id="sexe">
									<option value="Homme" <?php if($bExiste==true){if($_POST['sexe']=="Homme"){echo "selected";}} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Homme";}else{echo "Man";} ?></option>
									<option value="Femme" <?php if($bExiste==true){if($_POST['sexe']=="Femme"){echo "selected";}} ?>><?php if($_SESSION["Langue"]=="FR"){echo "Femme";}else{echo "Woman";} ?></option>
								</select>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nationalit� : ";}else{echo "Nationality : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<input name="nationalite" id="nationalite" size="15" value="<?php if($bExiste==true){echo $_POST['nationalite'];} ?>">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de naissance : ";}else{echo "Birth date : ";} ?><?php echo $etoile;?></td>
							<td width="10%"><input type="date" style="text-align:center;" id="dateNaissance" name="dateNaissance" size="10" value="<?php if($bExiste==true){echo $_POST['dateNaissance'];} ?>"></td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Lieu de naissance :";}else{echo "Place of birth :";} ?></td>
							<td width="10%">
								<input name="lieuNaissance" id="lieuNaissance" size="15" value="<?php if($bExiste==true){echo $_POST['lieuNaissance'];} ?>">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle">Contrat :<?php echo $etoile;?></td>
							<td width="10%">
								<select name="contrat" id="contrat">
								<?php
									$Tableau=array('','Consultant','Sous-traitant','Externe');
									foreach($Tableau as $indice => $valeur)
									{
										echo "<option value='".$valeur;
										if($bExiste==true){if($_POST['societe']==$valeur){echo "' selected>";}else{echo "'>";}}
										else{echo "'>";}
										echo $valeur."</option>";
									}
								?>
								</select>
							</td>
							<td class="Libelle">Soci�t� :<?php echo $etoile;?></td>
							<td><input name="societe" id="societe" size="20" type="text" value="<?php if($bExiste==true){echo $_POST['societe'];}?>"></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="6" class="cEmail">
								<i><?php 
									if($_SESSION["Langue"]=="FR"){echo "Veuillez vous adresser au service informatique (informatique.aaa@daher.com) si cette personne a besoin d'un acc�s � l'Extranet";}
									else{echo "Please contact the IT department (informatique.aaa@daher.com) if this person requires Extranet access.";} 
									?>
								</i>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="6" align="center">
								<div id="Ajouter">
								</div>
								<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="Enregistrer()">
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
?>
</body>
</html>
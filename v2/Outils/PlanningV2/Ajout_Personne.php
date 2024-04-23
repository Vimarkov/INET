<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function Enregistrer(){
		var valide = true;
		if(document.getElementById('Langue').value=="FR"){
			if(formulaire.nom.value==""){valide=false;alert("Veuillez compléter le nom");return false;}
			if(formulaire.prenom.value==""){valide=false;alert("Veuillez compléter le prénom");return false;}
			if(formulaire.nationalite.value==""){valide=false;alert("Veuillez compléter la nationalité");return false;}
			if(formulaire.dateNaissance.value==""){valide=false;alert("Veuillez compléter la date de naissance");return false;}
			if(formulaire.emailPerso.value==""){valide=false;alert("Veuillez compléter l\'email personnel");return false;}
		}
		else{
			if(formulaire.nom.value==""){valide=false;alert("Please fill in the name");return false;}
			if(formulaire.prenom.value==""){valide=false;alert("Please fill in the first name");return false;}
			if(formulaire.nationalite.value==""){valide=false;alert("Please complete the nationality");return false;}
			if(formulaire.dateNaissance.value==""){valide=false;alert("Please fill in the date of birth");return false;}
			if(formulaire.emailPerso.value==""){valide=false;alert("Please complete the personal email");return false;}
		}
		
		existeDeja=0;
		//Vérifier si les MatriculeDSK et MatriculeAAA et Matricule Daher n'existe pas déjà 
		$.ajax({
			url : 'Ajax_VerifMatricule.php',
			data : 'MatriculeDSK='+document.getElementById('matriculeDSK').value+'&MatriculeAAA='+document.getElementById('matriculeAAA').value+'&MatriculeDaher='+document.getElementById('matriculeDaher').value,
			dataType : "html",
			async : false,
			//affichage de l'erreur en cas de problème
			error:function(msg, string){
				},
			success:function(data){
				document.getElementById('VerifMatricule').value=data;
				}
		});
		if(document.getElementById('VerifMatricule').value.indexOf("EXISTE")!=-1){
			existeDeja=1;
		}
		
		if(existeDeja==1){
			if(document.getElementById('Langue').value=="FR"){
				alert("Le matricule AAA ou le matricule DSK ou le matricule Daher existe déjà.");
			}
			else{
				alert("AAA number or DSK number or Daher number already exists.");
			}
			return false;
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

if($_POST){
	if(isset($_POST['btnEnregistrer2'])){
		//Vérfier si le nom et prénom et date de naissance existe 
		$req="SELECT Id FROM new_rh_etatcivil WHERE Nom=\"".$_POST['nom']."\" AND Prenom=\"".$_POST['prenom']."\" AND Date_Naissance='".TrsfDate_($_POST['dateNaissance'])."' ";
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		if ($nbResulta>0){
			$bExiste=true;
		}
		else{
			$Login=str_replace("'","",strtolower(substr($_POST['prenom'],0,1).$_POST['nom']));
			$Login=str_replace(" ","",$Login);
			
			//Vérifier existance Login dans la base
			$select = "SELECT Id FROM new_rh_etatcivil WHERE Login LIKE '".$Login."%'";
			$result=mysqli_query($bdd,$select);
			$nbResulta=mysqli_num_rows($result);
			if($nbResulta>0){$Login=$Login.$nbResulta;}
			$Login=$Login.".external";
			
			$MotDePasse=chaine_aleatoire(8);
			
			$req="INSERT INTO new_rh_etatcivil 
				(Nom,Prenom,Sexe,Nationalite,Date_Naissance,Ville_Naissance,Num_SS,Adresse,CP,Ville,TelephoneFixe,Email,
				Type_TitreTravailEtranger,Num_TitreTravailEtranger,DateAncienneteCDI,MatriculeAAA,MatriculeDaher,MatriculeDSK,Login,Motdepasse)
			VALUES 
				(\"".addslashes($_POST['nom'])."\",\"".addslashes($_POST['prenom'])."\",\"".$_POST['sexe']."\",\"".addslashes($_POST['nationalite'])."\",\"".TrsfDate_($_POST['dateNaissance'])."\"
				,\"".addslashes($_POST['lieuNaissance'])."\",\"".$_POST['numSecu']."\",\"".addslashes($_POST['adresse'])."\",\"".$_POST['cp']."\",\"".addslashes($_POST['ville'])."\"
				,\"".$_POST['telephonePerso']."\",\"".$_POST['emailPerso']."\",\"".addslashes($_POST['titreSejour'])."\",\"".addslashes($_POST['numTitreSejour'])."\"
				,\"".TrsfDate_($_POST['dateAnciennete'])."\",\"".$_POST['matriculeAAA']."\",\"".$_POST['matriculeDaher']."\",\"".$_POST['matriculeDSK']."\",\"".$Login."\",\"".$MotDePasse."\")
			";
			$resultAjout=mysqli_query($bdd,$req);
			$Id_Personne=mysqli_insert_id($bdd);
			
			//Envoi d'un Email pour informer l'utilisateur
			GenererMailIdentifiantsExtranet($_POST['nom'],$_POST['prenom'],$Login,$MotDePasse,$_POST['dateNaissance'],$_POST['emailPerso'],$_SESSION['Langue']);
			
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

<form id="formulaire" class="test" action="Ajout_Personne.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="VerifMatricule" id="VerifMatricule" value="" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing:0; background-color:#87ceff;">
				<tr>
					<td class="TitrePage">
					<?php
						echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
						if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
						else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
						echo "</a>"; 
						echo "&nbsp;&nbsp;&nbsp;";
						if($_SESSION["Langue"]=="FR"){echo "Déclaration d'une personne";}else{echo "Declaration of a person";}
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
				if($_SESSION["Langue"]=="FR"){echo "Cette personne a été enregistrée.";}
				else{echo "This person has been registered.";} 
			?>
			
		</td></tr>
		<tr><td height="4"></td></tr>
	<?php }
		elseif($bExiste==true){
	?>
		<tr><td colspan="6" align="center" style="color:#ff0000;font:bold;">
			<?php 
				if($_SESSION["Langue"]=="FR"){echo "Cette personne existe déjà.";}
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
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prénom : ";}else{echo "First name : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<input name="prenom" id="prenom" size="15" value="<?php if($bExiste==true){echo $_POST['prenom'];} ?>">
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?><?php echo $etoile;?></td>
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
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nationalité : ";}else{echo "Nationality : ";} ?><?php echo $etoile;?></td>
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
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° sécurité social :";}else{echo "Social security number :";} ?></td>
							<td width="10%">
								<input name="numSecu" id="numSecu" size="15" value="<?php if($bExiste==true){echo $_POST['numSecu'];} ?>">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Titre de séjour :";}else{echo "Title of stay :";} ?></td>
							<td width="10%">
								<input name="titreSejour" id="titreSejour" size="30" value="<?php if($bExiste==true){echo $_POST['titreSejour'];} ?>">
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° titre de séjour :";}else{echo "Number of residence permit :";} ?></td>
							<td width="10%">
								<input name="numTitreSejour" id="numTitreSejour" size="30" value="<?php if($bExiste==true){echo $_POST['numTitreSejour'];} ?>">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Adresse :";}else{echo "Address :";} ?></td>
							<td width="10%">
								<input name="adresse" id="adresse" size="50" value="<?php if($bExiste==true){echo $_POST['adresse'];} ?>">
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "CP :";}else{echo "PC :";} ?></td>
							<td width="10%">
								<input name="cp" id="cp" size="8" value="<?php if($bExiste==true){echo $_POST['cp'];} ?>">
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Ville :";}else{echo "City :";} ?></td>
							<td width="10%">
								<input name="ville" id="ville" size="15" value="<?php if($bExiste==true){echo $_POST['ville'];} ?>">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° téléphone personnel :";}else{echo "Personal telephone number :";} ?></td>
							<td width="10%">
								<input name="telephonePerso" id="telephonePerso" size="15" value="<?php if($bExiste==true){echo $_POST['telephonePerso'];} ?>">
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Email personnel : ";}else{echo "Personal email : ";} ?><?php echo $etoile;?></td>
							<td width="10%">
								<input name="emailPerso" id="emailPerso" size="25" value="<?php if($bExiste==true){echo $_POST['emailPerso'];} ?>">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date d'ancienneté (si CDI) :";}else{echo "Date of seniority (if CDI):";} ?> </td>
							<td width="10%"><input type="date" style="text-align:center;" id="dateAnciennete" name="dateAnciennete" size="10" value="<?php if($bExiste==true){echo $_POST['dateAnciennete'];} ?>"></td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° matricule AAA Paris (si CDI) :";}else{echo "AAA Paris number (if CDI) :";} ?></td>
							<td width="10%">
								<input name="matriculeAAA" id="matriculeAAA" size="15" value="<?php if($bExiste==true){echo $_POST['matriculeAAA'];} ?>">
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° matricule DirectSkill :";}else{echo "DirectSkill number :";} ?></td>
							<td width="10%">
								<input name="matriculeDSK" id="matriculeDSK" size="15" value="<?php if($bExiste==true){echo $_POST['matriculeDSK'];} ?>">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° matricule Daher (si CDI) :";}else{echo "Daher number (if CDI) :";} ?></td>
							<td width="10%">
								<input name="matriculeDaher" id="matriculeDaher" size="15" value="<?php if($bExiste==true){echo $_POST['matriculeDaher'];} ?>">
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
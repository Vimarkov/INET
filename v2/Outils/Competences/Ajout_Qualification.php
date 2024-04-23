<html>
<head>
	<title>Compétences - Qualification</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function OuvreFenetreModif(Page,Mode,Id,Id_Qualification,Largeur,Hauteur){
			var w=window.open(Page+".php?Mode="+Mode+"&Id="+Id+"&Id_Qualification="+Id_Qualification,"PageQualificationMetierLettre","status=no,menubar=no,width="+Largeur+",height="+Hauteur);
			w.focus();
		}

		function VerifChamps()
		{
			if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
			else{return true;}
		}
			
		function FermerEtRecharger(Id,Id_Categorie_Maitre)
		{
			opener.location.href="Liste_Qualification.php";
			location.href="Ajout_Qualification.php?Mode=Modif&Id="+Id+"&Id_Categorie_Maitre="+Id_Categorie_Maitre;
		}
		function FermerEtRecharger2()
		{
			opener.location.href="Liste_Qualification.php";
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require_once("../Fonctions.php");

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
		$requeteVerificationExiste="SELECT Id FROM new_competences_qualification WHERE Libelle='".$_POST['Libelle']."' AND Id_Categorie_Qualification=".$_POST['Categorie_Qualification'];
		$requeteInsertUpdate="INSERT INTO new_competences_qualification (Id_Categorie_Qualification, Libelle, Periodicite_Surveillance,Duree_Validite,Lettre_Theorie,Lettre_Pratique,
		Nb_Page_Si_L,Id_Modificateur,DateModif)";
		$requeteInsertUpdate.=" VALUES (";
		$requeteInsertUpdate.=$_POST['Categorie_Qualification'];
		$requeteInsertUpdate.=",'".addslashes($_POST['Libelle'])."'";
		$requeteInsertUpdate.=",".$_POST['Periodicite_Surveillance'];
		$requeteInsertUpdate.=",".$_POST['Duree_Validite'];
		$requeteInsertUpdate.=",'".$_POST['Lettre_Theorie']."'";
		$requeteInsertUpdate.=",'".$_POST['Lettre_Pratique']."'";
		$requeteInsertUpdate.=",".$_POST['Nb_Page_Si_L'];
		$requeteInsertUpdate.=",".$_SESSION['Id_Personne'];
		$requeteInsertUpdate.=",'".date('Y-m-d')."'";
		$requeteInsertUpdate.=")";
	}
	elseif($_POST['Mode']=="Modif")
	{
		$requeteVerificationExiste="SELECT Id FROM new_competences_qualification WHERE Libelle='".$_POST['Libelle']."' AND Id!=".$_POST['Id']." AND Id_Categorie_Qualification=".$_POST['Categorie_Qualification'];
		$requeteInsertUpdate="UPDATE new_competences_qualification";
		$requeteInsertUpdate.=" SET ";
		$requeteInsertUpdate.="Libelle='".addslashes($_POST['Libelle'])."'";
		$requeteInsertUpdate.=",Id_Categorie_Qualification=".$_POST['Categorie_Qualification'];
		$requeteInsertUpdate.=",Periodicite_Surveillance=".$_POST['Periodicite_Surveillance'];
		$requeteInsertUpdate.=",Duree_Validite=".$_POST['Duree_Validite'];
		$requeteInsertUpdate.=",Lettre_Theorie='".$_POST['Lettre_Theorie']."'";
		$requeteInsertUpdate.=",Lettre_Pratique='".$_POST['Lettre_Pratique']."'";
		$requeteInsertUpdate.=",Nb_Page_Si_L=".$_POST['Nb_Page_Si_L'];
		$requeteInsertUpdate.=",Id_Modificateur=".$_SESSION['Id_Personne'];
		$requeteInsertUpdate.=",DateModif='".date('Y-m-d')."'";
		$requeteInsertUpdate.=" WHERE Id=".$_POST['Id'];
		$Id=$_POST['Id'];
	}
	$resultVerificationExiste=mysqli_query($bdd,$requeteVerificationExiste);
	if(mysqli_num_rows($resultVerificationExiste)==0)
	{
		$resultInsertUpdate=mysqli_query($bdd,$requeteInsertUpdate);
		if($_POST['Mode']=="Ajout"){$Id=mysqli_insert_id($bdd);}
		echo "<script>FermerEtRecharger(".$Id.",".$_POST['Id_Categorie_Maitre'].");</script>";
	}
	else{echo "<font class='Erreur'>Ce libellé existe déjà pour cette catégorie.<br>Vous devez recommencer l'opération.</font>";}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT *,
					(SELECT Id_Categorie_Maitre FROM new_competences_categorie_qualification WHERE Id=new_competences_qualification.Id_Categorie_Qualification) AS Id_Categorie_Maitre
					FROM new_competences_qualification WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
		
		$ReqDroits= "
			SELECT
				Id
			FROM
				new_competences_personne_poste_plateforme
			WHERE
				Id_Personne=".$_SESSION['Id_Personne']."
				AND Id_Plateforme IN (1)";
		$ResultDroits=mysqli_query($bdd,$ReqDroits);
		$NbEnregDroits=mysqli_num_rows($ResultDroits);
?>
		<form id="formulaire" method="POST" action="Ajout_Qualification.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="Modif"){echo $row['Id'];}?>">
		<input type="hidden" name="Id_Categorie_Maitre" value="<?php if($_GET['Mode']=="Ajout"){echo $_GET['Id_Categorie_Maitre'];}else{echo $row['Id_Categorie_Maitre'];} ?>">
		<table style="width:95%; align:center;">
			<tr class="TitreColsUsers">
				<td width="200"><?php if($LangueAffichage=="FR"){echo "Catégorie qualification";}else{echo "Qualification group";}?> : </td>
				<td>
					<select name="Categorie_Qualification">
				<?php
					$result2=mysqli_query($bdd,"SELECT * FROM new_competences_categorie_qualification WHERE Id_Categorie_Maitre=".$_GET['Id_Categorie_Maitre']." ORDER BY Libelle");
					while($row2=mysqli_fetch_array($result2))
					{
						echo "<option ";
						if($_GET['Mode']=="Modif"){if($row['Id_Categorie_Qualification']==$row2['Id']){echo "selected ";}}
						echo "value='".$row2['Id']."'>".$row2['Libelle']."</option>\n";
					}
				?>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td><input name="Libelle" size="50" type="text" value="<?php if($_GET['Mode']=="Modif"){echo stripslashes($row['Libelle']);}?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($LangueAffichage=="FR"){echo "Périodicité surveillance (mois)";}else{echo "Periodicity monitoring (months)";}?> :</td>
				<td>
					<select name="Periodicite_Surveillance">
					<?php
					$Tableau=array('0','6','12','18','24','30','36','42','48','60','72');
					foreach($Tableau as $indice => $valeur)
					{
						echo "<option value='".$valeur."'";
						if($_GET['Mode']=="Modif"){if($row['Periodicite_Surveillance']==$valeur){echo " selected";}}
						echo ">".$valeur."</option>\n";
					}
					?>
					</select>
					(0 signifie qu'il n'y a pas de surveillance)
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td><?php if($LangueAffichage=="FR"){echo "Durée de validité (mois)";}else{echo "Period of validity (months)";}?> :</td>
				<td>
					<select name="Duree_Validite">
					<?php
					$Tableau=array('0','6','12','18','24','30','36','42','48','60','72');
					foreach($Tableau as $indice => $valeur)
					{
						echo "<option value='".$valeur."'";
						if($_GET['Mode']=="Modif"){if($row['Duree_Validite']==$valeur){echo " selected";}}
						echo ">".$valeur."</option>\n";
					}
					?>
					</select>
					(0 signifie que la qualification n'a pas de fin de validité)
				</td>
			</tr>
			<tr class="TitreColsUsers" <?php if($NbEnregDroits==0){echo "style='display:none;'";} ?>>
				<td>Nb page fiche qualif si "L" :</td>
				<td>
					<select name="Nb_Page_Si_L">
					<?php
					$Tableau=array('1','2');
					foreach($Tableau as $indice => $valeur)
					{
						echo "<option value='".$valeur."'";
						if($_GET['Mode']=="Modif"){if($row['Nb_Page_Si_L']==$valeur){echo " selected";}}
						echo ">".$valeur."</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers" <?php if($NbEnregDroits==0){echo "style='display:none;'";} ?>>
				<td><?php if($LangueAffichage=="FR"){echo "Lettre obtenue par défaut (thérorie)";}else{echo "Letter obtained by default (theory)";}?> :</td>
				<td>
					<select name="Lettre_Theorie">
					<?php
					$Tableau=array('L','Q','S','T','V','X');
					foreach($Tableau as $indice => $valeur)
					{
						echo "<option value='".$valeur."'";
						if($_GET['Mode']=="Modif"){if($row['Lettre_Theorie']==$valeur){echo " selected";}}
						echo ">".$valeur."</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers" <?php if($NbEnregDroits==0){echo "style='display:none;'";} ?>>
				<td><?php if($LangueAffichage=="FR"){echo "Lettre obtenue par défaut (pratique)";}else{echo "Letter obtained by default (practical)";}?> :</td>
				<td>
					<select name="Lettre_Pratique">
					<?php
					$Tableau=array('Q','S','T','V','X');
					foreach($Tableau as $indice => $valeur)
					{
						echo "<option value='".$valeur."'";
						if($_GET['Mode']=="Modif"){if($row['Lettre_Pratique']==$valeur){echo " selected";}}
						echo ">".$valeur."</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input class="Bouton" type="submit" 
					<?php
						if($_GET['Mode']=="Modif"){
							if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
						}
						else{
							if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
						}
					?>
					>
				</td>
			</tr>
			<?php
			if($_GET['Mode']=="Modif")
			{
				if($NbEnregDroits>0){
			?>
			<tr>
				<td colspan="2">
					<table class="ProfilCompetence">
						<tr>
							<td class="PetiteCategorieCompetence" width="200">Moyen</td>
							<td class="PetiteCategorieCompetence" width="150">Catégorie autorisation</td>
							<td class="PetiteCategorieCompetence" colspan=2 align="right" width="20px">
								<a class="Modif" href="javascript:OuvreFenetreModif('Ajout_Qualification_Moyen','Ajout','0','<?php echo $_GET['Id']; ?>','500','100');">
									<img src="../../Images/Modif.gif" border="0" alt="Modification">
								</a>
							</td>
						</tr>
						<?php
						$requeteMoyens="SELECT new_competences_qualification_moyen.Id, new_competences_qualification_moyen.Id_Qualification, new_competences_moyen_categorie.Libelle, new_competences_moyen.Libelle";
						$requeteMoyens.=" FROM new_competences_qualification_moyen, new_competences_moyen_categorie, new_competences_moyen";
						$requeteMoyens.=" WHERE";
						$requeteMoyens.=" new_competences_qualification_moyen.Id_Moyen_Categorie=new_competences_moyen_categorie.Id";
						$requeteMoyens.=" AND new_competences_moyen_categorie.Id_Moyen=new_competences_moyen.Id";
						$requeteMoyens.=" AND new_competences_qualification_moyen.Suppr=0 AND new_competences_qualification_moyen.Id_Qualification=".$_GET['Id']." ";
						$requeteMoyens.=" ORDER BY new_competences_moyen.Libelle ASC,new_competences_moyen_categorie.Libelle ASC";
						$resultMoyens=mysqli_query($bdd,$requeteMoyens);
						$Couleur="#EEEEEE";
						while($rowMoyens=mysqli_fetch_array($resultMoyens)){
							if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
							else{$Couleur="#EEEEEE";}
						?>
						<tr bgcolor="<?php echo $Couleur;?>">
							<td><?php echo $rowMoyens[3];?></td>
							<td><?php echo $rowMoyens[2];?></td>
							<td width="20">
								<a class="Modif" href="javascript:OuvreFenetreModif('Ajout_Qualification_Moyen','Modif','<?php echo $rowMoyens['Id']; ?>','<?php echo $_GET['Id']; ?>','600','150');">
									<img src="../../Images/Modif.gif" border="0" alt="Modification">
								</a>
							</td>
							<td width="20">
								<a class="Modif" href="javascript:OuvreFenetreModif('Ajout_Qualification_Moyen','Suppr','<?php echo $rowMoyens['Id']; ?>','<?php echo $_GET['Id']; ?>','600','150');">
									<img src="../../Images/Suppression.gif" border="0" alt="Suppression">
								</a>
							</td>
						</tr>
						<?php
						}
						?>
					</table>
				</td>
			</tr>
			
			<tr>
				<td colspan="2">
					<table class="ProfilCompetence">
						<tr>
							<td class="PetiteCategorieCompetence" width="300">Métier</td>
							<td class="PetiteCategorieCompetence" width="75">Lettre</td>
							<td class="PetiteCategorieCompetence" width="75">Théorique/Pratique</td>
							<td class="PetiteCategorieCompetence" colspan=2 align="right" width="20px">
								<a class="Modif" href="javascript:OuvreFenetreModif('Ajout_Qualification_Metier_Lettre','Ajout','0','<?php echo $_GET['Id']; ?>','600','400');">
									<img src="../../Images/Modif.gif" border="0" alt="Modification">
								</a>
							</td>
						</tr>
						<?php
						$requeteMetiers_Lettres="SELECT Id, Id_Qualification, (SELECT Libelle FROM new_competences_metier WHERE Id=Id_Metier) AS Metier, Lettre, Theorique_Pratique FROM new_competences_qualification_metier_lettre WHERE Suppr=0 AND Id_Qualification=".$_GET['Id']." ORDER BY Metier ASC,Theorique_Pratique ASC, Lettre ASC";
						$resultMetiers_Lettres=mysqli_query($bdd,$requeteMetiers_Lettres);
						$Couleur="#EEEEEE";
						while($rowMetiers_Lettres=mysqli_fetch_array($resultMetiers_Lettres))
						{
							if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
							else{$Couleur="#EEEEEE";}
						?>
						<tr bgcolor="<?php echo $Couleur;?>">
							<td><?php echo stripslashes($rowMetiers_Lettres[2]);?></td>
							<td><?php echo $rowMetiers_Lettres[3];?></td>
							<td><?php echo $rowMetiers_Lettres[4];?></td>
							<td width="20">
								<a class="Modif" href="javascript:OuvreFenetreModif('Ajout_Qualification_Metier_Lettre','Modif','<?php echo $rowMetiers_Lettres['Id']; ?>','<?php echo $_GET['Id']; ?>','600','150');">
									<img src="../../Images/Modif.gif" border="0" alt="Modification">
								</a>
							</td>
							<td width="20">
								<a class="Modif" href="javascript:OuvreFenetreModif('Ajout_Qualification_Metier_Lettre','Suppr','<?php echo $rowMetiers_Lettres['Id']; ?>','<?php echo $_GET['Id']; ?>','600','150');">
									<img src="../../Images/Suppression.gif" border="0" alt="Suppression">
								</a>
							</td>
						</tr>
						<?php
						}
						?>
					</table>
				</td>
			</tr>
			
			<tr>
				<td colspan="2">
					<table class="ProfilCompetence">
						<tr>
							<td class="PetiteCategorieCompetence" width="120">Plateforme</td>
							<td class="PetiteCategorieCompetence" width="120">Avion</td>
							<td class="PetiteCategorieCompetence" width="120">Produit</td>
							<td class="PetiteCategorieCompetence" width="120">Client</td>
							<td class="PetiteCategorieCompetence" colspan=2 align="right" width="20px">
								<a class="Modif" href="javascript:OuvreFenetreModif('Ajout_Qualification_Plateforme_Infos','Ajout','0','<?php echo $_GET['Id']; ?>','600','350');">
									<img src="../../Images/Modif.gif" border="0" alt="Modification">
								</a>
							</td>
						</tr>
						<?php
						$requetePlateforme_Infos="SELECT Id, Id_Qualification, (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme, Avion, Produit, Client FROM new_competences_qualification_plateforme_infos WHERE Suppr=0 AND Id_Qualification=".$_GET['Id']." ORDER BY Plateforme ASC";
						$resultPlateforme_Infos=mysqli_query($bdd,$requetePlateforme_Infos);
						$Couleur="#EEEEEE";
						while($rowPlateforme_Infos=mysqli_fetch_array($resultPlateforme_Infos))
						{
							if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
							else{$Couleur="#EEEEEE";}
						?>
						<tr bgcolor="<?php echo $Couleur;?>">
							<td><?php echo stripslashes($rowPlateforme_Infos[2]);?></td>
							<td><?php echo stripslashes($rowPlateforme_Infos[3]);?></td>
							<td><?php echo stripslashes($rowPlateforme_Infos[4]);?></td>
							<td><?php echo stripslashes($rowPlateforme_Infos[5]);?></td>
							<td width="20">
								<a class="Modif" href="javascript:OuvreFenetreModif('Ajout_Qualification_Plateforme_Infos','Modif','<?php echo $rowPlateforme_Infos['Id']; ?>','<?php echo $_GET['Id']; ?>','600','350');">
									<img src="../../Images/Modif.gif" border="0" alt="Modification">
								</a>
							</td>
							<td width="20">
								<a class="Modif" href="javascript:OuvreFenetreModif('Ajout_Qualification_Plateforme_Infos','Suppr','<?php echo $rowPlateforme_Infos['Id']; ?>','<?php echo $_GET['Id']; ?>','600','350');">
									<img src="../../Images/Suppression.gif" border="0" alt="Suppression">
								</a>
							</td>
						</tr>
						<?php
						}
						?>
					</table>
				</td>
			</tr>
			<?php
				}
			}
			?>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_relation WHERE Id_Qualification=".$_GET['Id']." AND Type='Qualification'");
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"DELETE FROM new_competences_qualification WHERE Id=".$_GET['Id']);
			$result=mysqli_query($bdd,"DELETE FROM new_competences_prestation_qualification WHERE Id_Qualification=".$_GET['Id']);
			echo "<script>FermerEtRecharger2();</script>";
		}
		else{echo "<font class='Erreur'>Vous ne pouvez pas supprimer cette qualification car une ou plusieurs personne y est rattachées.</font>";}
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
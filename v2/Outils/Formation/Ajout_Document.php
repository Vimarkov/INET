<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Ajouter un document</title><meta name="robots" content="noindex">
	<link href="../JS/styleCalendrier.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript">
		function OuvreFenetreModif(Mode,Id_Document,Id)
		{
			Confirm=false;
			if(document.getElementById('Langue').value=="FR"){
				if(Mode=="Suppr"){Confirm=window.confirm('Etes-vous sûr de vouloir supprimer ?');}
			}
			else{
				if(Mode=="Suppr"){Confirm=window.confirm('Are you sure you want to delete?');}
			}
			if((Mode=="Suppr" && Confirm==true) || Mode=="Ajout" || Mode=="Modif")
			{
				var w=window.open("Ajout_Document_Langue.php?Mode="+Mode+"&Id_Document="+Id_Document+"&Id="+Id,"PageDocumentLangue","status=no,menubar=no,width=800,height=650,scrollbars=1");
				w.focus();
			}
		}
		function VerifChamps()
		{
			if(document.getElementById('Langue').value=="FR"){
				if(formulaire.Reference.value==''){alert('Vous n\'avez pas renseigné la référence.');return false;}
				else{return true;}
			}
			else{
				if(formulaire.Reference.value==''){alert('You did not fill in the reference.');return false;}
				else{return true;}
			}
		}
			
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
	</script>
</head>
<body>

<?php
if($_POST)
{
	$requete="";
	if($_POST['Mode']=="Ajout")
	{
		if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM form_document WHERE Suppr=0 AND Reference='".addslashes($_POST['Reference'])."'"))==0)
		{
			$requete="
                INSERT INTO form_document
                    (
                    Reference,
                    Id_Personne_MAJ,
                    Date_MAJ,
                    Fichier_PHP
                    )
                VALUES
                    (
                    '".$_POST['Reference']."',".
			        $IdPersonneConnectee.",
                    '".date('Y-m-d')."',
                    '".$_POST['Fichier_PHP']."'
                    )";
		}
	}
	else	//Mode modification
	{
		if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM form_document WHERE Suppr=0 AND Reference='".$_POST['Reference']."' AND Id!=".$_POST['Id']))==0)
		{
			$requete="
                UPDATE
                    form_document
                SET
                    Reference='".$_POST['Reference']."',
                    Id_Personne_MAJ=".$IdPersonneConnectee.",
                    Date_MAJ='".date('Y-m-d')."',
                    Fichier_PHP='".$_POST['Fichier_PHP']."'
                WHERE
                    Id=".$_POST['Id'];
		}
	}
	if($requete!="")
	{
		$result=mysqli_query($bdd,$requete);
		if($_POST['Mode']=="Ajout")
		{
		    //Récupération de l'ID du document ajouté
		    $Id_Document_Ajoute=mysqli_insert_id($bdd);
		    
		    //Modification des données concernant les formations qui intègrent le Document_A_Remplacer
		    $ReqMAJFormations="
                UPDATE
                    form_formation_document
                SET
                    Id_Document=".$Id_Document_Ajoute.",
                    Id_Personne_MAJ=".$IdPersonneConnectee.",
                    Date_MAJ='".date('Y-m-d')."'
                WHERE
                    Id_Document=".$_POST['Id_Document_A_Remplacer'];
		    $ResultMAJFormations=mysqli_query($bdd,$RequeteMAJFormations);
		    
		    //Suppression de l'ancien document dans la table (afin qu'il n'apparaisse plus dans les listes déroulantes
		    $ReqSupprDocument="
                UPDATE
                    form_document
                SET
                    Suppr=1,
                    Id_Personne_MAJ=".$IdPersonneConnectee.",
                    Date_MAJ='".date('Y-m-d')."'
                WHERE
                    Id=".$_POST['Id_Document_A_Remplacer'];
		    $ResultSupprDocument=mysqli_query($bdd,$RequeteSupprDocument);
		    
			//Création du répertoire pour la gestion des fichiers joints des questions des documents
		    $res = mkdir_ftp($CheminFormation."Document/".$Id_Document_Ajoute, 0773);
			if(!$res){echo 'Echec lors de la création des répertoires...';}
		}
		echo "<script>FermerEtRecharger();</script>";
	}
	
	else{echo "<font class='Erreur'>Cette reference existe déjà.<br>Vous devez recommencer l'opération.</font>";}
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Modif=false;
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0'){
			$Modif=true;
			$result=mysqli_query($bdd,"SELECT Id, Reference, Fichier_PHP FROM form_document WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}

Ecrire_Code_JS_Init_Date();
?>
		<form id="formulaire" method="POST" action="Ajout_Document.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php echo $_GET['Id'];?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle">Reference : </td>
				<td>
					<input name="Reference" id="Reference" size="25" value="<?php if($Modif){echo $row['Reference'];}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle">Fichier PHP : </td>
				<td>
					<input name="Fichier_PHP" id="Fichier_PHP" size="25" value="<?php if($Modif){echo $row['Fichier_PHP'];}?>">
				</td>
			</tr>
			<tr>
				<td class="Libelle">Document à remplacer : </td>
				<td>
				<?php 
				    $ReqFichier="SELECT Id, Reference FROM form_document WHERE Suppr=0 ORDER BY Reference";
				    $ResultFichier=mysqli_query($bdd,$ReqFichier);
				    echo "<select name='Id_Document_A_Remplacer'>";
                    echo "<option value='0'></option>";
                    while($RowFichier=mysqli_fetch_array($ResultFichier))
                    {
                        echo "<option value='".$RowFichier['Id']."'>".$RowFichier['Reference']."</option>";
                    }
                    echo "</select>";
                ?>
                </td>
			</tr>
			<tr>
				<td colspan=2 align="center">
					<input class="Bouton" type="submit" 
					<?php
						if($Modif)
						{
							if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
						}
						else
						{
							if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
						}
					?>
					>
				</td>
			</tr>
			
			<?php
			if($Modif)
			{
			?>
			<!-- Gestion des différentes langues  -->
			<tr>
				<td colspan=3>
					<table class="ProfilCompetence" style="width:100%;">
						<tr>
							<td class="PetiteCategorieCompetence" width="20%"><?php if($LangueAffichage=="FR"){echo "Langue";}else{echo "Language";}?></td>
							<td class="PetiteCategorieCompetence" width="45%"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
							<td class="PetiteCategorieCompetence" width="15%"><?php if($LangueAffichage=="FR"){echo "Mis à jour par";}else{echo "Update By";}?></td>
							<td class="PetiteCategorieCompetence" width="15%"><?php if($LangueAffichage=="FR"){echo "Mis à jour le";}else{echo "Updated on";}?></td>
							<td class="PetiteCategorieCompetence" colspan=2 width="5%" align="right">
								<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','<?php echo $_GET['Id'];?>','0');">
									<img src="../../Images/Ajout.gif" border="0" alt="Ajouter une langue au document">
								</a>
							</td>
						</tr>
						<?php 
						$reqLangue="SELECT Id, Id_Langue, (SELECT Libelle FROM form_langue WHERE Id=Id_Langue) AS Langue, Libelle, Date_MAJ, ";
						$reqLangue.="(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_document_langue.Id_Personne_MAJ) AS Personne ";
						$reqLangue.="FROM form_document_langue WHERE Id_Document=".$_GET['Id']." AND Suppr=0";
						$resultLangue=mysqli_query($bdd,$reqLangue);
						$nbLangue=mysqli_num_rows($resultLangue);
						$Couleur="#EEEEEE";
						if($nbLangue>0)
						{
							while($rowLangue=mysqli_fetch_array($resultLangue))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
						?>
								<tr bgcolor="<?php echo $Couleur;?>">
									<td class="PetitCompetence"><?php echo $rowLangue['Langue'];?></td>
									<td class="PetitCompetence"><?php echo $rowLangue['Libelle'];?></td>
									<td class="PetitCompetence"><?php echo $rowLangue['Personne'];?></td>
									<td class="PetitCompetence"><?php echo AfficheDateJJ_MM_AAAA($rowLangue['Date_MAJ']);?></td>
									<td>
										<a class="Modif" href="javascript:OuvreFenetreModif('Modif', '<?php echo $_GET['Id'];?>','<?php echo $rowLangue['Id']; ?>');">
											<img src="../../Images/Modif.gif" border="0" alt="Modification">
										</a>
									</td>
									<td>
										<a class="Modif" href="javascript:OuvreFenetreModif('Suppr', '<?php echo $_GET['Id'];?>','<?php echo $rowLangue['Id']; ?>');">
											<img src="../../Images/Suppression.gif" border="0" alt="Suppression">
										</a>
									</td>
								</tr>
						<?php
							}
						}
						?>
					</table>
				</td>
			</tr>
			<?php 
			}
			?>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"UPDATE form_document SET Suppr=1, Id_Personne_MAJ=".$IdPersonneConnectee.", Date_MAJ='".date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger();</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
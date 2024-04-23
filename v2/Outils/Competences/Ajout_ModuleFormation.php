<html>
<head>
	<title>Compétences - Catégorie Diplôme</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps()
		{
			if(formulaire.Reference.value==''){alert('Vous n\'avez pas renseigné la référence.');return false;}
			if(formulaire.Intitule.value==''){alert('Vous n\'avez pas renseigné l\'intitulé.');return false;}
			return true;
		}
			
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
		function OuvrirFichier(Fic)
			{window.open("../../../Qualite/DQ/4/DQ413/Modules_de_formation/"+Fic,"PageFichier","status=no,menubar=no,width="+screen.width-10+",height="+screen.height-10+",resizable=yes");}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require_once("../Formation/Globales_Fonctions.php");
require_once("../Fonctions.php");

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
		$result=mysqli_query($bdd,"SELECT Id FROM moduleformation_formation WHERE Suppr=0 AND Reference='".addslashes($_POST['Reference'])."'");
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"INSERT INTO moduleformation_formation (Id_Categorie,Reference,Intitule,DateCreation,Id_Createur) VALUES ('".$_POST['Id_Categorie']."','".addslashes($_POST['Reference'])."','".addslashes($_POST['Intitule'])."','".date('Y-m-d')."','".$_SESSION['Id_Personne']."')");
			$Id_Formation=mysqli_insert_id($bdd);
			
			//Ajout des références
			for($i=0;$i<$_POST['nbLigne'];$i++){
				if($_POST['reference_'.$i]<>"" && $_POST['intitule_'.$i]<>""){
					$req="INSERT INTO moduleformation_formation (Id_Formation,Reference,Intitule,DateCreation,Id_Createur,Indice,DateDocument) 
					VALUES (".$Id_Formation.",'".addslashes($_POST['reference_'.$i])."','".addslashes($_POST['intitule_'.$i])."',
					'".date('Y-m-d')."','".$_SESSION['Id_Personne']."','".addslashes($_POST['indice_'.$i])."',
					'".TrsfDate_($_POST['date_'.$i])."') ";
					$result=mysqli_query($bdd,$req);
					$Id_Document=mysqli_insert_id($bdd);
					
					if($Id_Document>0){
						if(!empty($_FILES['uploadedFile_'.$i]) && !isset($_FILES['suppr_'.$i])){
							if($_FILES['uploadedFile_'.$i]['name'] <> ""){
								$nomfichier = transferer_fichier($_FILES['uploadedFile_'.$i]['name'], $_FILES['uploadedFile_'.$i]['tmp_name'], "../../../Qualite/DQ/4/DQ413/Modules_de_formation/");
								if($nomfichier<>""){
									$reqUpdate="UPDATE moduleformation_formation SET Lien='".$nomfichier."', TypeDocument='".$_POST['typeDocument_'.$i]."' WHERE Id=".$Id_Document;
									$resultUpdate=mysqli_query($bdd,$reqUpdate);
								}
							}
						}
					}
				}
			}
			
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Ce libellé existe déjà.<br>Vous devez recommencer l'opération.</font>";}
	}
	elseif($_POST['Mode']=="Modif")
	{
		$result=mysqli_query($bdd,"SELECT Id FROM moduleformation_formation WHERE Suppr=0 AND Reference='".addslashes($_POST['Reference'])."' AND Id!=".$_POST['Id']);
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"UPDATE moduleformation_formation SET Id_Categorie='".$_POST['Id_Categorie']."',Reference='".addslashes($_POST['Reference'])."',Intitule='".addslashes($_POST['Intitule'])."' WHERE Id=".$_POST['Id']);
			$Id_Formation=$_POST['Id'];
			
			for($i=0;$i<$_POST['nbLigne'];$i++){
				if($_POST['id_'.$i]=="0"){
					//Ajout des références
					if($_POST['reference_'.$i]<>"" && $_POST['intitule_'.$i]<>""){
						$req="INSERT INTO moduleformation_formation (Id_Formation,Reference,Intitule,DateCreation,Id_Createur,Indice,DateDocument) 
						VALUES (".$Id_Formation.",'".addslashes($_POST['reference_'.$i])."','".addslashes($_POST['intitule_'.$i])."',
						'".date('Y-m-d')."','".$_SESSION['Id_Personne']."','".addslashes($_POST['indice_'.$i])."',
						'".TrsfDate_($_POST['date_'.$i])."') ";
						$result=mysqli_query($bdd,$req);
						$Id_Document=mysqli_insert_id($bdd);
						
						if($Id_Document>0){
							if(!empty($_FILES['uploadedFile_'.$i]) && !isset($_POST['suppr_'.$i])){
								if($_FILES['uploadedFile_'.$i]['name'] <> ""){
									$nomfichier = transferer_fichier($_FILES['uploadedFile_'.$i]['name'], $_FILES['uploadedFile_'.$i]['tmp_name'], "../../../Qualite/DQ/4/DQ413/Modules_de_formation/");
									if($nomfichier<>""){
										$reqUpdate="UPDATE moduleformation_formation SET Lien='".$nomfichier."', TypeDocument='".$_POST['typeDocument_'.$i]."' WHERE Id=".$Id_Document;
										$resultUpdate=mysqli_query($bdd,$reqUpdate);
									}
								}
							}
						}
					}
				}
				else{
					//Mise à jour des références
					if($_POST['reference_'.$i]<>"" && $_POST['intitule_'.$i]<>""){
						$req="UPDATE moduleformation_formation 
						SET Reference='".addslashes($_POST['reference_'.$i])."',
						Intitule='".addslashes($_POST['intitule_'.$i])."',
						Indice='".addslashes($_POST['indice_'.$i])."',
						DateDocument='".TrsfDate_($_POST['date_'.$i])."'
						WHERE Id=".$_POST['id_'.$i]." ";
						$result=mysqli_query($bdd,$req);
						$Id_Document=$_POST['id_'.$i];
						
						if($Id_Document>0){
							if(!empty($_FILES['uploadedFile_'.$i]) && !isset($_POST['suppr_'.$i])){
								if($_FILES['uploadedFile_'.$i]['name'] <> ""){
									
									$nomfichier = transferer_fichier($_FILES['uploadedFile_'.$i]['name'], $_FILES['uploadedFile_'.$i]['tmp_name'], "../../../Qualite/DQ/4/DQ413/Modules_de_formation/");
									if($nomfichier<>""){
										$reqUpdate="UPDATE moduleformation_formation SET Lien='".$nomfichier."', TypeDocument='".$_POST['typeDocument_'.$i]."' WHERE Id=".$Id_Document;
										$resultUpdate=mysqli_query($bdd,$reqUpdate);
									}
								}
								
							}
							if(isset($_POST['suppr_'.$i]) && $_POST['document_'.$i]<>""){
								if(file_exists($CheminQualite."DQ/4/DQ413/Modules_de_formation/".$_POST['document_'.$i])){
									//Supprimer le document
									unlink($CheminQualite."DQ/4/DQ413/Modules_de_formation/".$_POST['document_'.$i]);	
								}
								$reqUpdate="UPDATE moduleformation_formation SET Lien='', TypeDocument='' WHERE Id=".$Id_Document;
								$resultUpdate=mysqli_query($bdd,$reqUpdate);
							}
						}
					}
					else{
						$req="UPDATE moduleformation_formation 
						SET Reference='".addslashes($_POST['reference_'.$i])."',
						Suppr=1,
						Id_Suppr='".$_SESSION['Id_Personne']."',
						DateSuppr='".date('Y-m-d')."'
						WHERE Id=".$_POST['id_'.$i]." ";
						$result=mysqli_query($bdd,$req);
						$Id_Document=$_POST['id_'.$i];

					}						
				}
			}
			
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Ce libellé existe déjà.<br>Vous devez recommencer l'opération.</font>";}
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT Id,Reference,Intitule,Id_Categorie FROM moduleformation_formation WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_ModuleFormation.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="Modif"){echo $row['Id'];}?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
		<table style="width:100%; align:center;" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle">Catégorie : </td>
				<td colspan="3">
					<select name="Id_Categorie">
					<?php
					$resultCat=mysqli_query($bdd,"SELECT Id, Libelle FROM moduleformation_categorie WHERE Suppr=0 ORDER BY Libelle ASC");
					while($rowCat=mysqli_fetch_array($resultCat))
					{
						echo "<option value='".$rowCat['Id']."'";
						if($_GET['Mode']=="Modif"){if($row['Id_Categorie']==$rowCat['Id']){echo " selected";}}
						echo ">".$rowCat['Libelle']."</option>";							 
					}
					?>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle">Référence : </td>
				<td><input name="Reference" size="30" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Reference'];}?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle">Intitulé : </td>
				<td><input name="Intitule" size="70" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Intitule'];}?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td colspan="3" align="center">
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
		</table>
		<br>
		<table style="width:100%; align:center;" class="TableCompetences">
			<tr>
				<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION['Langue']=="EN"){echo "Reference";}else{echo "Référence";} ?></td>
				<td class="EnTeteTableauCompetences" width="30%"><?php if($_SESSION['Langue']=="EN"){echo "Title";}else{echo "Intitulé";} ?></td>
				<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Indice";}else{echo "Indice";} ?></td>
				<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Date";}else{echo "Date";} ?></td>
				<td class="EnTeteTableauCompetences" width="15%" colspan="3"><?php if($_SESSION['Langue']=="EN"){echo "File";}else{echo "Fichier";} ?></td>
				<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION['Langue']=="EN"){echo "Document type";}else{echo "Type de document";} ?></td>
			</tr>
			<?php 
				if($_GET['Id']!='0')
				{
					$reqDoc="SELECT Id, Id_Formation, Reference , Intitule, Indice, DateDocument,Lien,TypeDocument FROM moduleformation_formation WHERE Suppr=0 AND Id_Formation=".$row['Id']." ";
					$resultDoc=mysqli_query($bdd,$reqDoc);
					$nbDocument=mysqli_num_rows($resultDoc);
					
					$total=8;
					if ($nbDocument>=8){
						$total=8+$nbResulta;
					}
				}
				else{
					$nbDocument=0;
					$total=8;
				}
				
				for($i=0;$i<$total;$i++){
					$Reference="";
					$Intitule="";
					$Indice="";
					$Date="";
					$Document="";
					$TypeDocument="";
					$Id="0";

					if ($i<$nbDocument){
						$row=mysqli_fetch_array($resultDoc);
						$Reference=stripslashes($row['Reference']);
						$Intitule=stripslashes($row['Intitule']);
						$Indice=stripslashes($row['Indice']);
						$Date=AfficheDateFR($row['DateDocument']);
						$Document=$row['Lien'];
						$TypeDocument=$row['TypeDocument'];
						$Id=$row['Id'];
					}
			?>
					<tr>
						<td>
							<input type="hidden" id="id_<?php echo $i;?>" name="id_<?php echo $i;?>" size="30px" value="<?php echo $Id;?>" >
							<input type="hidden" id="document_<?php echo $i;?>" name="document_<?php echo $i;?>" size="30px" value="<?php echo $Document;?>" >
							<input type="texte" id="reference_<?php echo $i;?>" name="reference_<?php echo $i;?>" size="30px" value="<?php echo $Reference;?>" >
						</td>
						<td>
							<input type="texte" id="intitule_<?php echo $i;?>" name="intitule_<?php echo $i;?>" size="70px" value="<?php echo $Intitule;?>" >
						</td>
						<td>
							<input type="texte" id="indice_<?php echo $i;?>" name="indice_<?php echo $i;?>" size="10px" value="<?php echo $Indice;?>" >
						</td>
						<td>
							<input type="date" id="date_<?php echo $i;?>" name="date_<?php echo $i;?>" size="10px" value="<?php echo $Date;?>" >
						</td>
						<td>
							<?php 
								if($Document<>""){
									echo "<a class=\"Info\" href=\"javascript:OuvrirFichier('".$Document."');\"><img src='../../Images/doc.png' style='border:0;width:15px;' title='Doc'></a>";
								}
							?>
						</td>
						<td>
							<input type="file" name="uploadedFile_<?php echo $i;?>" />
						</td>
						<td>
							<input type="checkbox" name="suppr_<?php echo $i;?>" />Suppr.
						</td>
						<td>
							<select class="typeDocument_<?php echo $i;?>" name="typeDocument_<?php echo $i;?>" style="width:90px;">
							<?php
								$tab=array("","Document","QCM");

								foreach($tab as $valeur)
								{
									$selected="";
									if($TypeDocument==$valeur){$selected="selected";}
									echo "<option value='".$valeur."' ".$selected.">".$valeur."</option>\n";
								}
							?>
							</select>
						</td>
						
					</tr>
					<tr><td height="4"></td></tr>
			<?php
				}
			?>
			<input type="hidden" id="nbLigne" name="nbLigne" value="<?php echo $total;?>" >
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"UPDATE moduleformation_formation SET Suppr=1, Id_Suppr=".$_SESSION['Id_Personne'].", DateSuppr='".date('Y-m-d')."' WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger();</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
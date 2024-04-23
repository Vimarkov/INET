<html>
<head>
	<title>Formations - Ajouter un besoin en formation</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script type="text/javascript">
		function VerifChamps(){
			for(y=0;y<document.getElementById('Id_Qualifications').length;y++){document.getElementById('Id_Qualifications').options[y].selected = true;}
			for(y=0;y<document.getElementById('Id_Metiers').length;y++){document.getElementById('Id_Metiers').options[y].selected = true;}
		}
			
		function FermerEtRecharger()
		{
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("../Formation/Globales_Fonctions.php");

if($_POST){
	if(isset($_POST['dupliquer'])){
		$nbMetier=0;
		if($_POST['metierACopier']<>0){
			foreach($_POST['Id_Metiers'] as $metier)
			{
				$nbMetier++;
			}
		}
		foreach($_POST['Id_Qualifications'] as $value)
		{
			if($nbMetier==0){
				$req="UPDATE new_competences_qualification_metier_lettre SET Suppr=1 WHERE Id_Qualification=".$value." ";
				if($_POST['metierACopier']<>0){
					$req.="AND Id_Metier=".$_POST['metierACopier'];
				}
				$ResultUpdate=mysqli_query($bdd,$req);
				
				$req="
					INSERT INTO new_competences_qualification_metier_lettre (Id_Qualification,Id_Metier,Lettre,Theorique_Pratique)
					SELECT ".$value.", Id_Metier, Lettre, Theorique_Pratique 
					FROM new_competences_qualification_metier_lettre 
					WHERE Id_Qualification=".$_POST['qualificationACopier']."
					AND Suppr=0
					";
				if($_POST['metierACopier']<>0){
					$req.="AND Id_Metier=".$_POST['metierACopier'];
				}
				$ResultInsert=mysqli_query($bdd,$req);
			}
			else{
				foreach($_POST['Id_Metiers'] as $metier)
				{
					$req="UPDATE new_competences_qualification_metier_lettre SET Suppr=1 WHERE Id_Qualification=".$value." AND Id_Metier=".$metier;
					$ResultUpdate=mysqli_query($bdd,$req);
					
					$req="
						INSERT INTO new_competences_qualification_metier_lettre (Id_Qualification,Id_Metier,Lettre,Theorique_Pratique)
						SELECT ".$value.", ".$metier.", Lettre, Theorique_Pratique 
						FROM new_competences_qualification_metier_lettre 
						WHERE Id_Qualification=".$_POST['qualificationACopier']."
						AND Suppr=0
						AND Id_Metier=".$_POST['metierACopier'];
					$ResultInsert=mysqli_query($bdd,$req);
				}
			}
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
?>
<form id="formulaire" method="POST" action="Copier_LettresQualification.php" onSubmit="return VerifChamps();">
	<table class="TableCompetences" style="width:95%; height:95%; align:center;">
		<tr>
			<td class="Libelle" style="width:20%;">
				<?php if($LangueAffichage=="FR"){echo "Qualification à dupliquer";}else{echo "Qualification to be duplicated";}?> :
			</td>
		</tr>
		<tr>
			<td style="width:80%;" colspan="3">
				<select name="qualificationACopier" id="qualificationACopier" style="width:300px;">
					<?php
					$requeteQualifications="SELECT new_competences_qualification.Id, new_competences_qualification.Libelle,new_competences_categorie_qualification.Libelle AS Categorie
											FROM new_competences_qualification 
											LEFT JOIN new_competences_categorie_qualification 
											ON new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id 
											WHERE new_competences_qualification.Suppr=0 
											ORDER BY new_competences_qualification.Libelle ";
					$resultQualifications=mysqli_query($bdd,$requeteQualifications);
				
					while($rowQualifications=mysqli_fetch_array($resultQualifications)){
						echo "<option value='".$rowQualifications['Id']."'>".stripslashes($rowQualifications['Libelle'])." (".stripslashes($rowQualifications['Categorie']).")</option>";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="Libelle" style="width:20%;">
				<?php if($LangueAffichage=="FR"){echo "Métier à dupliquer";}else{echo "Duplicate job";}?> :
			</td>
		</tr>
		<tr>
			<td style="width:80%;" colspan="3">
				<select name="metierACopier" id="metierACopier" style="width:200px;">
					<option value="0"><?php if($LangueAffichage=="FR"){echo "Tous";}else{echo "All";}?></option>
					<?php
					$resultMetier=mysqli_query($bdd,"SELECT Id,Libelle FROM new_competences_metier ORDER BY Libelle ASC");		
					while($rowMetier=mysqli_fetch_array($resultMetier)){
						echo "<option value='".$rowMetier['Id']."'>".stripslashes($rowMetier['Libelle'])."</option>";
					}
					?>
				</select>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td valign="top" class="Libelle" width="100%" colspan="2">
				<?php if($LangueAffichage=="FR"){echo "Si métier à dupliquer sélectionné cocher les métiers identiques au métier sélectionné <br>(si rien est coché, le métier correspondra au métier à dupliquer)";}else{echo "If job to be duplicated selected check jobs identical to job selected <br> (if nothing is checked, the job will correspond to the job to be duplicated";}?> : <br>
				<?php
					$resultMetier=mysqli_query($bdd,"SELECT Id,Libelle FROM new_competences_metier ORDER BY Libelle ASC");
				?>
				<div id="listeMtier" style="width:100%;height:150px;overflow:auto;">
				<?php
					while($rowMetier=mysqli_fetch_array($resultMetier)){
						echo "<div>";
						echo "<input class='check' type='checkbox' name='Id_Metiers[]' value='".$rowMetier['Id']."'>".stripslashes($rowMetier['Libelle'])."";
						echo "</div>";
					}
				?>
				</div>
			</td>
		</tr>
		<tr>
			<td height="10px"></td>
		</tr>
		<tr class="TitreColsUsers">
			<td valign="top" class="Libelle" width="100%" colspan="2">
				<?php if($LangueAffichage=="FR"){echo "Cocher les qualifications où dupliquer";}else{echo "Check the qualifications where to duplicate";}?> : <br>
				<?php
					$resultQualifications=mysqli_query($bdd,$requeteQualifications);
				?>
				<div id="listeQualif" style="width:100%;height:350px;overflow:auto;">
				<?php
					while($rowQualifications=mysqli_fetch_array($resultQualifications)){
						echo "<div>";
						echo "<input class='check' type='checkbox' name='Id_Qualifications[]' value='".$rowQualifications['Id']."'>".stripslashes($rowQualifications['Libelle'])." (".stripslashes($rowQualifications['Categorie']).")";
						echo "</div>";
					}
				?>
				</div>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td colspan="2" align="center">
				<input class="Bouton" name="dupliquer" type="submit" <?php if($LangueAffichage=="FR"){echo "value='Dupliquer'";}else{echo "value='Duplicate'";}?>>
			</td>
		</tr>
	</table>
</form>
<?php
mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
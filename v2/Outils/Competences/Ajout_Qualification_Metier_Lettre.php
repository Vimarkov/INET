<html>
<head>
	<title>Compétences - Qualification - Métier - Lettre</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
		function ToutCocher(){
			if(document.getElementById('check_Metiers').checked==true){
				var elements = document.getElementsByClassName('check');
				for (i=0; i<elements.length; i++){
				  elements[i].checked=true;
				}
			}
			else{
				var elements = document.getElementsByClassName('check');
				for (i=0; i<elements.length; i++){
				  elements[i].checked=false;
				}
			}
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
		$resultMetier=mysqli_query($bdd,"SELECT Id,Libelle FROM new_competences_metier ORDER BY Libelle ASC");
		while($rowMetier=mysqli_fetch_array($resultMetier))
		{	
			if(isset($_POST['Metier_'.$rowMetier['Id']])){
				$requeteVerificationExiste="SELECT Id FROM new_competences_qualification_metier_lettre WHERE Id_Qualification=".$_POST['Id_Qualification']." AND Id_Metier=".$rowMetier['Id']." AND Theorique_Pratique='".substr($_POST['Theorique_Pratique'],0,1)."' AND Suppr=0";
				$requeteInsertUpdate="INSERT INTO new_competences_qualification_metier_lettre (Id_Qualification, Id_Metier, Lettre,Theorique_Pratique)";
				$requeteInsertUpdate.=" VALUES (";
				$requeteInsertUpdate.=$_POST['Id_Qualification'];
				$requeteInsertUpdate.=",".$rowMetier['Id'];
				$requeteInsertUpdate.=",'".$_POST['Lettre']."'";
				$requeteInsertUpdate.=",'".$_POST['Theorique_Pratique']."'";
				$requeteInsertUpdate.=")";
				echo $requeteVerificationExiste;
				$resultVerificationExiste=mysqli_query($bdd,$requeteVerificationExiste);
				if(mysqli_num_rows($resultVerificationExiste)==0)
				{
					$resultInsertUpdate=mysqli_query($bdd,$requeteInsertUpdate);
				}
			}
		}
	}
	elseif($_POST['Mode']=="Modif")
	{
		$requeteVerificationExiste="SELECT Id FROM new_competences_qualification_metier_lettre WHERE Id_Qualification='".$_POST['Id_Qualification']."' AND Id_Metier=".$_POST['Id_Metier']." AND Theorique_Pratique='".substr($_POST['Theorique_Pratique'],0,1)."' AND Suppr=0 AND Id!=".$_POST['Id'];
		$requeteInsertUpdate="UPDATE new_competences_qualification_metier_lettre";
		$requeteInsertUpdate.=" SET ";
		$requeteInsertUpdate.=" Id_Qualification=".$_POST['Id_Qualification'];
		$requeteInsertUpdate.=",Id_Metier=".$_POST['Id_Metier'];
		$requeteInsertUpdate.=",Lettre='".$_POST['Lettre']."'";
		$requeteInsertUpdate.=",Theorique_Pratique='".$_POST['Theorique_Pratique']."'
								WHERE Id=".$_POST['Id'];
		
		$resultVerificationExiste=mysqli_query($bdd,$requeteVerificationExiste);
		if(mysqli_num_rows($resultVerificationExiste)==0)
		{
			$resultInsertUpdate=mysqli_query($bdd,$requeteInsertUpdate);
			
		}
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		$Modif=false;
		if($_GET['Mode']=="Modif"){$Modif=true;}
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT Id, Id_Qualification, Id_Metier, Lettre, Theorique_Pratique FROM new_competences_qualification_metier_lettre WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Qualification_Metier_Lettre.php">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $row['Id'];}?>">
		<input type="hidden" name="Id_Qualification" value="<?php echo $_GET['Id_Qualification'];?>">
		<table class="TableCompetences" style="width:95%; height:95%; align:center;">
			<tr>
				<td>Type de qualification (Pratique/Théorique)</td>
				<td>
					<select name="Theorique_Pratique">
					<?php
					$Tableau=array('Pratique|P','Théorique|T');
					foreach($Tableau as $indice => $valeur)
					{
						$valeur=explode("|",$valeur);
						echo "<option value='".$valeur[0]."'";
						if($Modif){if($row['Theorique_Pratique']==$valeur[1]){echo " selected";}}
						echo ">".$valeur[0]."</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Lettre si qualification validée (type ci-dessus)</td>
				<td>
					<select name="Lettre">
					<?php
					$Tableau=array('L','Q','S','T','V','X');
					foreach($Tableau as $indice => $valeur)
					{
						echo "<option value='".$valeur."'";
						if($Modif){if($row['Lettre']==$valeur){echo " selected";}}
						echo ">".$valeur."</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			<?php 
				if($Modif){
			?>
				<tr class="TitreColsUsers">
					<td><?php if($LangueAffichage=="FR"){echo "Métier";}else{echo "Job";}?> : </td>
					<td>
						<select name="Id_Metier">
						<?php
						$resultMetier=mysqli_query($bdd,"SELECT Id,Libelle FROM new_competences_metier ORDER BY Libelle ASC");
						while($rowMetier=mysqli_fetch_array($resultMetier))
						{
							echo "<option value='".$rowMetier['Id']."'";
							if($row['Id_Metier']==$rowMetier['Id']){echo " selected";}
							echo ">".$rowMetier['Libelle']."</option>";							 
						}
						?>
						</select>
					</td>
				</tr>
			<?php
				}
				else{
			?>
				<tr class="TitreColsUsers">
					<td colspan="2"><?php if($LangueAffichage=="FR"){echo "Métier";}else{echo "Job";}?> : </td>
				</tr>
				<tr class="TitreColsUsers">
					<td colspan="2"><input type='checkbox' id="check_Metiers" name="check_Metiers" value="" onchange="ToutCocher()"><?php if($LangueAffichage=="FR"){echo "Tout cocher";}else{echo "Check all";}?></td>
				</tr>
				<tr class="TitreColsUsers" >
					<td colspan="2" valign="top">
						<div id="listePresta" style="height:200px;width:400px;overflow:auto;">
						<?php
						$resultMetier=mysqli_query($bdd,"SELECT Id,Libelle FROM new_competences_metier ORDER BY Libelle ASC");
						while($rowMetier=mysqli_fetch_array($resultMetier))
						{	
							echo "<div>";
							echo "<input class='check' type='checkbox' id='Metier_".$rowMetier['Id']."' name='Metier_".$rowMetier['Id']."'>&nbsp;";
							echo stripslashes($rowMetier['Libelle']);
							echo "</div>";
						}
						?>
						</div>
					</td>
				</tr>
			<?php
				}
			?>
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
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"UPDATE new_competences_qualification_metier_lettre SET Suppr=1 WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger();</script>";

	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps(langue){
			if(langue=="EN"){
				if(formulaire.categorie.value=='0'){alert('You didn\'t enter the category.');return false;}
				if(formulaire.question.value==''){alert('You didn\'t enter the question.');return false;}
				if(formulaire.reponse.value==''){alert('You didn\'t enter the answer.');return false;}
			}
			else{
				if(formulaire.categorie.value=='0'){alert('Vous n\'avez pas renseigné la catégorie.');return false;}
				if(formulaire.question.value==''){alert('Vous n\'avez pas renseigné la question.');return false;}
				if(formulaire.reponse.value==''){alert('Vous n\'avez pas renseigné la réponse.');return false;}
			}
			return true;

		}
		function FermerEtRecharger(){
			window.opener.location = "FAQ.php";
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
$mdp="aaa01";
if($_POST){
	if($_POST['Mode']=="A"){
		$req="INSERT INTO trame_faq (Id_Categorie,Question,Reponse) VALUES (".$_POST['categorie'].",'".addslashes($_POST['question'])."','".addslashes($_POST['reponse'])."')";
		$result=mysqli_query($bdd,$req);
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE trame_faq SET ";
		$requete.="Id_Categorie=".$_POST['categorie'].", ";
		$requete.="Question='".addslashes($_POST['question'])."', ";
		$requete.="Reponse='".addslashes($_POST['reponse'])."' ";
		$requete.=" WHERE Id=".$_POST['id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="M" || $_GET['Mode']=="V"){
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT Id, Id_Categorie,Question,Reponse FROM trame_faq WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
		}
?>

		<form id="formulaire" method="POST" action="Ajout_FAQ.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="id" value="<?php if($_GET['Mode']=="M"|| $_GET['Mode']=="V"){echo $Ligne['Id'];}?>">
		<table width="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle" ><?php if($_SESSION['Langue']=="EN"){echo "Category";}else{echo "Catégorie";} ?></td>
				<td>
					<select id="categorie" name="categorie" <?php if($_GET['Mode']=="V"){echo "disabled='disabled'";} ?>>
					<?php
						echo"<option value='0'></option>";
						$req="SELECT Id, Libelle, Supprime FROM trame_categorie_faq;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($row=mysqli_fetch_array($result)){
								$selected="";
								if($_GET['Mode']=="M" || $_GET['Mode']=="V"){
									if($row['Id']==$Ligne['Id_Categorie']){$selected="selected";}
								}
								if($row['Supprime']==false  || $row['Id']==$Ligne['Id_Categorie']){
									echo "<option value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
								}
							}
						}
					?>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle" valign="top"><?php if($_SESSION['Langue']=="EN"){echo "Question";}else{echo "Question";} ?></td>
				<td>
					<textarea id="question" name="question" rows="2" cols="100" style="resize:none;" <?php if($_GET['Mode']=="V"){echo "readonly=readonly";} ?>><?php if($_GET['Mode']=="M" || $_GET['Mode']=="V"){ echo stripslashes($Ligne['Question']);}?></textarea>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"  valign="top"><?php if($_SESSION['Langue']=="EN"){echo "Answer";}else{echo "Réponse";} ?></td>
				<td>
					<textarea id="reponse" name="reponse" rows="5" cols="100" style="resize:none;" <?php if($_GET['Mode']=="V"){echo "readonly=readonly";} ?>><?php if($_GET['Mode']=="M" || $_GET['Mode']=="V"){ echo stripslashes($Ligne['Reponse']);}?></textarea>
				</td>
			</tr>
			<tr>
				<?php
					if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
				?>
					<td colspan="2" align="center"><input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="M" || $_GET['Mode']=="V"){if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}}else{if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}}?>"></td>
				<?php
					}
				?>
			</tr>
		</table>
		</form>
<?php
	}
	elseif($_GET['Mode']=="S")
	//Mode suppression
	{
		$req="DELETE FROM trame_faq WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$req);
		
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
<html>
<head>
	<title>Extranet de la société Assistance Aéronautique et Aérospatiale</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps(){
			if(formulaire.article.value==''){alert('Vous n\'avez pas renseigné l\'article.');return false;}
			return true;
		}
		function FermerEtRecharger(){
			window.opener.location = "Liste_Article.php";
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../../Connexioni.php");
if($_POST){
	if($_POST['Mode']=="A"){
		$droit="";
		$requete="INSERT INTO sp_atrarticle (Id_Prestation,Article,Ligne,Poste45,Denomination) VALUES (262,'".addslashes($_POST['article'])."',".$_POST['ligne'].",".$_POST['poste45'].",'".addslashes($_POST['denomination'])."') ";
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE sp_atrarticle SET ";
		$requete.="Article='".addslashes($_POST['article'])."',";
		$requete.="Denomination='".addslashes($_POST['denomination'])."',";
		$requete.="Ligne='".addslashes($_POST['ligne'])."',";
		$requete.="Poste45='".addslashes($_POST['poste45'])."'";
		$requete.=" WHERE Id=".$_POST['urgence'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT Id, Article, Ligne, Poste45, Denomination FROM sp_atrarticle WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
		}
?>

		<form id="formulaire" method="POST" action="Ajout_Article.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="urgence" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<table width="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td>Article </td>
				<td colspan="3">
					<input type="texte" name="article" id="article" size="20" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Article'];}?>">
				</td>
			</tr><tr class="TitreColsUsers">
				<td>Dénomination </td>
				<td colspan="3">
					<input type="texte" name="denomination" id="denomination" size="20" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Denomination'];}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td>Ligne </td>
				<td>
					<select name="ligne">
						<?php
							for($i=1;$i<8;$i++){
								$selected="";
								if($_GET['Mode']=="M"){
									if($Ligne['Ligne']==$i){$selected="selected";}
								}
								echo "<option value='".$i."' ".$selected.">".$i."</option>";
							}
						?>
						<option value='MOU/DEMOUL' <?php if($_GET['Mode']=="M"){if($Ligne['Ligne']=='MOU/DEMOUL'){echo "selected";}} ?>>MOU/DEMOUL</option>
						<option value='LOG' <?php if($_GET['Mode']=="M"){if($Ligne['Ligne']=='LOG'){echo "selected";}} ?>>LOG</option>
						<option value='IQ' <?php if($_GET['Mode']=="M"){if($Ligne['Ligne']=='IQ'){echo "selected";}} ?>>IQ</option>
						<option value='/' <?php if($_GET['Mode']=="M"){if($Ligne['Ligne']=='/'){echo "selected";}} ?>>/</option>
					</select>
				</td>
				<td>Poste 45 </td>
				<td>
					<select name="poste45">
						<option value="0">Non</option>
						<option value="1" <?php if($_GET['Mode']=="M"){if($Ligne['Poste45']==1){echo "selected";}}?>>Oui</option>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td colspan="5" align="center">
					<input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="M"){echo "Valider";}else{echo "Ajouter";}?>">
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$requete="DELETE FROM sp_atrarticle ";
		$requete.=" WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
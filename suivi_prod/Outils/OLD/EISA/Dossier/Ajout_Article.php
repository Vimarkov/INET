<html>
<head>
	<title>Extranet de la société Assistance Aéronautique et Aérospatiale</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		ListeArticle= new Array();
		function VerifChamps(){
			if(formulaire.article.value==''){alert('Vous n\'avez pas renseigné l\'article.');return false;}
			//Verifier existance de l'article
			bExiste=false;
			for(i=0;i<ListeArticle.length;i++){
				if (ListeArticle[i]==formulaire.article.value){
					bExiste = true;
				}
			}
			if(bExiste==true){alert('Cet article existe déjà.');return false;}
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
		$requete="INSERT INTO sp_atrarticle (Id_Prestation,Article,TypeMoteur,MoteurSharklet) VALUES (463,'".addslashes($_POST['article'])."','".$_POST['typeMoteur']."','".$_POST['moteurSharklet']."') ";
		echo $requete;
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE sp_atrarticle SET ";
		$requete.="Article='".addslashes($_POST['article'])."',";
		$requete.="TypeMoteur='".addslashes($_POST['typeMoteur'])."',";
		$requete.="MoteurSharklet='".addslashes($_POST['moteurSharklet'])."'";
		$requete.=" WHERE Id=".$_POST['urgence'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){

		if($_GET['Mode']=="A"){
			$req="SELECT Article FROM sp_atrarticle WHERE Id_Prestation=463";
		}
		elseif($_GET['Mode']=="M"){
			$req="SELECT Article FROM sp_atrarticle WHERE Id<>".$_GET['Id']." AND Id_Prestation=463";
		}
		$resultArt=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($resultArt);
		if ($nbResulta>0){
			$i=0;
			while($rowArt=mysqli_fetch_array($resultArt)){
				echo "<script>ListeArticle[".$i."]=".$rowArt['Article']."</script>";
				$i++;
			}
		}

		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT Id, Article, TypeMoteur, MoteurSharklet FROM sp_atrarticle WHERE Id=".$_GET['Id']);
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
			</tr>
			<tr class="TitreColsUsers">
				<td>Type de moteur </td>
				<td>
					<select id="typeMoteur" name="typeMoteur">
						<option name="" value=""></option>
						<option value="CFM" <?php if($_GET['Mode']=="M"){if($Ligne['TypeMoteur']=="CFM"){echo "selected";}} ?>>CFM</option>
						<option value="IAE" <?php if($_GET['Mode']=="M"){if($Ligne['TypeMoteur']=="IAE"){echo "selected";}} ?>>IAE</option>
						<option value="PW" <?php if($_GET['Mode']=="M"){if($Ligne['TypeMoteur']=="PW"){echo "selected";}} ?>>PW</option>
						<option value="LEAP" <?php if($_GET['Mode']=="M"){if($Ligne['TypeMoteur']=="LEAP"){echo "selected";}} ?>>LEAP</option>
						<option value="TOUS" <?php if($_GET['Mode']=="M"){if($Ligne['TypeMoteur']=="TOUS"){echo "selected";}} ?>>TOUS</option>
					</select>
				</td>
				<td>Moteur/Sharklet </td>
				<td>
					<select name="moteurSharklet">
						<option value=""></option>
						<option value="Moteur" <?php if($_GET['Mode']=="M"){if($Ligne['MoteurSharklet']=="Moteur"){echo "selected";}} ?>>Moteur</option>
						<option value="Sharklet" <?php if($_GET['Mode']=="M"){if($Ligne['MoteurSharklet']=="Sharklet"){echo "selected";}} ?>>Sharklet</option>
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
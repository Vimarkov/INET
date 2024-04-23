<html>
<head>
	<title>Extranet de la société Assistance Aéronautique et Aérospatiale</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps(){
			if(formulaire.libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
			else{
				if(formulaire.statut.value=='0'){alert('Vous n\'avez pas renseigné le statut.');return false;}
				else{
					return true;
				}
			}

		}
		function FermerEtRecharger(){
			opener.location.reload();
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
		$requete="INSERT INTO sp_olwretour (Libelle,Id_Statut,EstRetour,Id_Prestation) VALUES ('".addslashes($_POST['libelle'])."','".addslashes($_POST['statut'])."',".$_POST['estRetour'].",842)";
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE sp_olwretour SET ";
		$requete.="Libelle='".addslashes($_POST['libelle'])."',";
		$requete.="Id_Statut='".$_POST['statut']."',";
		$requete.="EstRetour=".$_POST['estRetour']."";
		$requete.=" WHERE Id=".$_POST['retour'];
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
			$result=mysqli_query($bdd,"SELECT Id, Libelle,Id_Statut,EstRetour FROM sp_olwretour WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
		}
?>

		<form id="formulaire" method="POST" action="Ajout_Retour.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="retour" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<table width="95%" height="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td>Libellé </td>
				<td>
					<input type="texte" name="libelle" id="libelle" size="30" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Libelle'];}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td>Statut </td>
				<td>
					<select name="statut" id="statut">
						<option value="0"></option>
						<option value="A RELANCER" <?php if($_GET['Mode']=="M"){if($Ligne['Id_Statut']=="A RELANCER"){echo "selected";}}?>>A RELANCER</option>
						<option value="RETP" <?php if($_GET['Mode']=="M"){if($Ligne['Id_Statut']=="RETP"){echo "selected";}}?>>RETP</option>
						<option value="RETQ" <?php if($_GET['Mode']=="M"){if($Ligne['Id_Statut']=="RETQ"){echo "selected";}}?>>RETQ</option>
						<option value="TFS" <?php if($_GET['Mode']=="M"){if($Ligne['Id_Statut']=="TFS"){echo "selected";}}?>>TFS</option>
						<option value="TVS" <?php if($_GET['Mode']=="M"){if($Ligne['Id_Statut']=="TVS"){echo "selected";}}?>>TVS</option>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td>Est un retour </td>
				<td>
					<select name="estRetour" id="estRetour">
						<option value="0" <?php if($_GET['Mode']=="M"){if($Ligne['EstRetour']==0){echo "selected";}}?>>Non</option>
						<option value="1" <?php if($_GET['Mode']=="M"){if($Ligne['EstRetour']==1){echo "selected";}}?>>Oui</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
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
		$requete="UPDATE sp_olwretour SET Supprime=true WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
		
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
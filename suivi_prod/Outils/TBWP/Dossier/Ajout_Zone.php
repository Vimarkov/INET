<html>
<head>
	<title>Extranet de la société Assistance Aéronautique et Aérospatiale</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps(){
			if(formulaire.libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
			else{
				if(formulaire.critere.value=='0'){alert('Vous n\'avez pas renseigné le critère.');return false;}
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
		$requete="INSERT INTO sp_zonedetravail (Libelle,Intitule,Id_CritereZone) VALUES ('".addslashes($_POST['libelle'])."','".addslashes($_POST['description'])."',".addslashes($_POST['critere']).")";
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE sp_zonedetravail SET ";
		$requete.="Libelle='".addslashes($_POST['libelle'])."',";
		$requete.="Intitule='".addslashes($_POST['description'])."',";
		$requete.="Id_CritereZone=".$_POST['critere']."";
		$requete.=" WHERE Id=".$_POST['zone'];
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
			$result=mysqli_query($bdd,"SELECT Id, Libelle,Intitule, Id_CritereZone FROM sp_zonedetravail WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
		}
?>

		<form id="formulaire" method="POST" action="Ajout_Zone.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="zone" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<table width="95%" height="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td>Libellé </td>
				<td>
					<input type="texte" name="libelle" id="libelle" size="30" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Libelle'];}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td>Description </td>
				<td>
					<input type="texte" name="description" id="description" size="60" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Intitule'];}?>">
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td>Critère </td>
				<td>
					<select name="critere" id="critere">
						<option value="0"></option>
						<?php
							$req="SELECT Id, Libelle FROM sp_criterezone ORDER BY Libelle";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($row=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="M"){
										if($Ligne['Id_CritereZone']==$row['Id']){
											$selected="selected";
										}
									}
									echo "<option name='".$row['Id']."' value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
								}
							}
						?>
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
		$req="SELECT Id FROM sp_dossier WHERE Id_ZoneDeTravail=".$_GET['Id'];
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		if ($nbResulta==0){
			$requete="DELETE FROM sp_zonedetravail WHERE Id=".$_GET['Id'];
			$result=mysqli_query($bdd,$requete);
			echo "<script>FermerEtRecharger();</script>";
		}
		else{
			echo "<script>alert('Impossible de supprimer cette zone car elle est rattachée à un ou plusieurs dossiers');</script>";
			echo "<script>FermerEtRecharger();</script>";
		}
		
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
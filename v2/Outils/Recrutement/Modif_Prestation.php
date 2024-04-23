<html>
<head>
	<title>Formations - Ajouter un client</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script type="text/javascript">	
		function FermerEtRecharger()
		{
			window.opener.location="Liste_Prestation.php";
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
$LangueAffichage=$_SESSION['Langue'];
$SrcProblem="";
$Problem=0;
$FichierTransfert=0;

if($_POST)
{
	
	$requeteUpt="UPDATE new_competences_prestation SET
				Id_DomaineRecrutement='".$_POST['domaine']."',
				Programme='".addslashes($_POST['programme'])."'
				WHERE Id=".$_POST['Id'];
	$resultUpt=mysqli_query($bdd,$requeteUpt);

	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Modif=false;
	if($_GET['Id']!='0')
	{
		$Modif=True;
		$result=mysqli_query($bdd,"SELECT new_competences_prestation.Id, LEFT(new_competences_prestation.Libelle,7) AS Prestation,new_competences_plateforme.Libelle AS Plateforme,Id_DomaineRecrutement, Programme FROM new_competences_prestation LEFT JOIN new_competences_plateforme ON new_competences_plateforme.Id=new_competences_prestation.Id_Plateforme WHERE new_competences_prestation.Id=".$_GET['Id']." ");
		$row=mysqli_fetch_array($result);
	}
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Modif_Prestation.php">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php echo $row['Id'];?>">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
				<td colspan="3"><?php echo stripslashes($row['Plateforme']);?></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";}?> : </td>
				<td colspan="3"><?php echo stripslashes($row['Prestation']);?></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Programme";}else{echo "Program";}?> : </td>
				<td><input name="programme" size="50" type="text" value="<?php echo $row['Programme'];?>"></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Domaine";}else{echo "Domain";}?> : </td>
				<td>
					<select name="domaine" id="domaine">
						<option value="0"></option>
					<?php
					$requete="
						SELECT
							Id,
							Libelle
						FROM
							recrut_domaine
						WHERE
							Suppr=0
						ORDER BY
							Libelle ASC";
					$result=mysqli_query($bdd,$requete);
					$i=0;
					while($rowDomaine=mysqli_fetch_array($result))
					{
						echo "<option value='".$rowDomaine['Id']."'";
						if($Modif){if($row['Id_DomaineRecrutement']==$rowDomaine['Id']){echo " selected";}}
						echo ">".$rowDomaine['Libelle']."</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input class="Bouton" type="submit" 
					<?php
						if($_SESSION["Langue"]=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
					?>
					/>
				</td>
			</tr>
		</table>
		</form>
<?php
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
<html>
<head>
	<title>Formations - Ajouter un client</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script type="text/javascript">
		function FermerEtRecharger(Menu)
		{
			window.opener.location="ParametrageCout.php?Menu="+Menu;
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");

$SrcProblem="";
$Problem=0;
$FichierTransfert=0;

if($_POST)
{
	$result=mysqli_query($bdd,"SELECT Id FROM rh_parametrage_cout WHERE Suppr=0 AND Id_Plateforme=0");
	$nbenreg=mysqli_num_rows($result);
	
	$taux=0;
	if($_POST['taux']<>""){$taux=$_POST['taux'];}
	if($nbenreg>0)
	{
		$row=mysqli_fetch_array($result);
		$req="UPDATE rh_parametrage_cout SET Taux=".$taux.", DateCreation='".date('Y-m-d')."', Id_Createur=".$_SESSION['Id_Personne']." WHERE Id=".$row['Id'];
	}
	else{
		$req="INSERT INTO rh_parametrage_cout (Id_Plateforme,Id_Prestation,Id_TypeMetier,Id_Vacation,Taux,DateCreation,Id_Createur) VALUES (0,0,0,0,".$taux.",'".date('Y-m-d')."',".$_SESSION['Id_Personne'].")";
	}
	
	$resultInsertUpdate=mysqli_query($bdd,$req);
	echo "<script>FermerEtRecharger(".$_POST['Menu'].");</script>";
}
elseif($_GET)
{
	
?>
		<form id="formulaire" enctype="multipart/form-data" method="POST" action="Ajout_TauxGeneral.php">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<input type="hidden" id="Menu" name="Menu" value="<?php echo $_GET['Menu']; ?>">
		<table style="width:95%; align:center;" class="TableCompetences">
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Taux général";}else{echo "General rate";}?> : </td>
				<td colspan="3"><input onKeyUp="nombre(this)" name="taux" size="5" type="text" value=""></td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input class="Bouton" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Enregistrer";}else{echo "Save";}?>" />
				</td>
			</tr>
		</table>
		</form>
<?php
}
?>
</body>
</html>
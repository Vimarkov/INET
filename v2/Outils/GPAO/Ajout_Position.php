<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<script>
		function FermerEtRecharger(){
			opener.location.reload();
			window.close();
		}
	</script>
	<script language="javascript" src="Fonctions_GPAO.js?t=<?php echo time(); ?>"></script>
</head>
<body>
<?php

session_start();
require("../Connexioni.php");
require("../Fonctions.php");
	
if($_POST){
	$requete="UPDATE gpao_aircraft SET Position='".addslashes($_POST['position'])."' WHERE Id=".$_POST['id']." ";
	$result=mysqli_query($bdd,$requete);

	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET)
{
?>

	<form id="formulaire" method="POST" action="Ajout_Position.php">
		<input type="hidden" id="Langue" name="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<input type="hidden" name="id" value="<?php echo $_GET['Id']; ?>">
		<table width="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Position";}else{echo "Position";} ?> :</td>
				<td colspan="3">
					<select id="position" name="position">
					<?php
					
					$req="SELECT Position
						FROM gpao_aircraft 
						WHERE Id=".$_GET['Id']." ";
					$resultList=mysqli_query($bdd,$req);
					$nbList=mysqli_num_rows($resultList);
					
					$position="";
					if ($nbList > 0)
					{
						$rowList=mysqli_fetch_array($resultList);
						$position=$rowList['Position'];
					}
					$tab=array("FL","L1","L2","L3","L4","WA","WB","WP");

					foreach($tab as $valeur)
					{
						$selected="";
						if($position==$valeur){$selected="selected";}
						echo "<option value='".$valeur."' ".$selected.">".$valeur."</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			
			<tr><td height="5px"></td></tr>
			<tr class="TitreColsUsers">
				<td colspan="6" align="center">
					<input class="Bouton" onclick="submit();" value="<?php if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}?>">
				</td>
			</tr>
		</table>
	</form>
<?php
}
?>
	
</body>
</html>
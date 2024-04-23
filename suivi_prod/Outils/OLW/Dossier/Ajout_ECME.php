<html>
<head>
	<title>Extranet de la société Assistance Aéronautique et Aérospatiale</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		Liste_ECME = new Array();
		function VerifChamps(){
			if(formulaire.libelle.value==''){alert('Vous n\'avez pas renseigné la référence.');return false;}
			if(formulaire.type.value=='0'){alert('Vous n\'avez pas renseigné le type.');return false;}
			//Verifier existance du ECME
			bExiste=false
			for(i=0;i<Liste_ECME.length;i++){
				if (Liste_ECME[i][0]==formulaire.libelle.value && Liste_ECME[i][1]!=formulaire.urgence.value){
					bExiste = true;
				}
			}
			if(bExiste==true){alert('Cet ECME existe déjà.');return false;}
			return true;
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
		$requete="INSERT INTO sp_olwecme (Libelle,Id_Type,Id_Prestation) VALUES ('".addslashes($_POST['libelle'])."',".$_POST['type'].",688) ";
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE sp_olwecme SET ";
		$requete.="Libelle='".addslashes($_POST['libelle'])."',";
		$requete.="Id_Type=".addslashes($_POST['type'])."";
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
			$result=mysqli_query($bdd,"SELECT Id, Libelle,Id_Type FROM sp_olwecme WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
		}
		
		//LIste des ECME existants
		$req="SELECT Id,Libelle FROM sp_olwecme WHERE Id_Prestation=688 AND Supprime=0 ";
		$resultBDD=mysqli_query($bdd,$req);
		$nbBDD=mysqli_num_rows($resultBDD);
		if($nbBDD>0){
			$i=0;
			while($rowRef=mysqli_fetch_array($resultBDD)){
				echo "<script>Liste_ECME[".$i."] = new Array('".$rowRef['Libelle']."','".$rowRef['Id']."');</script>\n";
				$i+=1;
			}
		}
?>

		<form id="formulaire" method="POST" action="Ajout_ECME.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="urgence" id="urgence" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<table width="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td>Référence </td>
				<td>
					<input type="texte" name="libelle" id="libelle" size="20" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Libelle'];}?>">
				</td>
			</tr>
			<tr>
				<td>Type </td>
				<td>
					<select name='type' id='type'>
						<option value='0'></option>
						<?php
							$req="SELECT Id, Libelle, Supprime FROM sp_olwtypeecme WHERE Id_Prestation=688 ORDER BY Libelle ";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowType=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="A"){
										if($rowType['Supprime']==false){
											echo "<option value='".$rowType['Id']."'>".$rowType['Libelle']."</option>";
										}
									}
									elseif($_GET['Mode']=="M"){
										if($rowType['Supprime']==false || $rowType['Id']==$Ligne['Id_Type']){
											if($rowType['Id']==$Ligne['Id_Type']){$selected="selected";}
											echo "<option value='".$rowType['Id']."' ".$selected.">".$rowType['Libelle']."</option>";
										}
									}
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
		$requete="UPDATE sp_olwecme SET ";
		$requete.="Supprime=true ";
		$requete.=" WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
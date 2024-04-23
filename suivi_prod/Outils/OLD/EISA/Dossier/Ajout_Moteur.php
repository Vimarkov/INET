<html>
<head>
	<title>Extranet de la société Assistance Aéronautique et Aérospatiale</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>		
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script language="javascript" src="MSN.js"></script>
	<script>
		Liste_MSN = new Array();
		function VerifChamps(){
			if(formulaire.msn.value==''){alert('Vous n\'avez pas renseigné le MSN.');return false;}
			bExiste=false;
			for(i=0;i<Liste_MSN.length;i++){
				if (Liste_MSN[i][0]!=formulaire.Id.value && Liste_MSN[i][1]==formulaire.msn.value){
					bExiste = true;
				}
			}
			if(bExiste==true){alert('Ce MSN existe déjà.');return false;}
			if(formulaire.typeMoteur.value==''){alert('Vous n\'avez pas renseigné le type de moteur.');return false;}
			if(formulaire.posteMontage.value==''){alert('Vous n\'avez pas renseigné le poste de montage.');return false;}
			if(formulaire.dateMontage.value==''){alert('Vous n\'avez pas renseigné la date de montage.');return false;}
			return true;
		}
		function FermerEtRecharger(){
			window.opener.location = "Liste_Moteur.php";
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../../Fonctions.php");
require("../../Connexioni.php");

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
Ecrire_Code_JS_Init_Date();
if($_POST){
	if($_POST['Mode']=="A"){
		$requete="INSERT INTO sp_atrmoteur (Id_Prestation,MSN,TypeMoteur,PosteMontage,DateMontage) ";
		$requete.="VALUES (463,".$_POST['msn'].",'".$_POST['typeMoteur']."','".$_POST['posteMontage']."','".TrsfDate_($_POST['dateMontage'])."') ";
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE sp_atrmoteur SET ";
		$requete.="MSN=".$_POST['msn'].",";
		$requete.="TypeMoteur='".$_POST['typeMoteur']."',";
		$requete.="DateMontage='".TrsfDate_($_POST['dateMontage'])."',";
		$requete.="PosteMontage='".$_POST['posteMontage']."' ";
		$requete.="WHERE Id=".$_POST['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	$titre="";
	if($_GET['Mode']=="A"){$titre="Ajouter un MSN";}
	elseif($_GET['Mode']=="M"){$titre="Modifier un MSN";}
	
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
		if($_GET['Id']!='0'){
			$result=mysqli_query($bdd,"SELECT Id,MSN,TypeMoteur,PosteMontage,DateMontage FROM sp_atrmoteur WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
			
			$reqMSN="SELECT Id,MSN FROM sp_atrmoteur WHERE Id_Prestation=463 AND Id<>".$_GET['Id']." ";
		}
		else{
			$reqMSN="SELECT Id,MSN FROM sp_atrmoteur WHERE Id_Prestation=463 ";
		}
		
		//Vérification si le MSN existe pas déjà
		$resultMSN=mysqli_query($bdd,$reqMSN);
		$nbMSN=mysqli_num_rows($resultMSN);
		if($nbMSN>0){
			$i=0;
			while($rowMSN=mysqli_fetch_array($resultMSN)){
				echo "<script>Liste_MSN[".$i."] = new Array('".$rowMSN['Id']."','".$rowMSN['MSN']."');</script>\n";
				$i+=1;
			}
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Moteur.php" onSubmit="return VerifChamps();">
		<table width="100%">
			<tr>
				<td>
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="4"></td>
							<td class="TitrePage"><?php echo $titre;?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td>
				<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
				<input type="hidden" name="Id" id="Id" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
				</td>
			</tr>
			<tr>
				<td>
					<table width="95%"  align="center" class="TableCompetences">
						<tr>
							<td class="Libelle">&nbsp;MSN :</td>
							<td>
								<input onKeyUp="nombre(this)" type="texte" name="msn" id="msn" size="6" value="<?php if($_GET['Mode']=="M"){echo $Ligne['MSN'];}?>">
							</td>
							<td class="Libelle">&nbsp;Type de moteur :</td>
							<td>
								<select name="typeMoteur">
									<option name="" value=""></option>
										<option value="CFMI" <?php if($_GET['Mode']=="M"){if($Ligne['TypeMoteur']=="CFMI"){echo "selected";}} ?>>CFMI</option>
										<option value="IAE" <?php if($_GET['Mode']=="M"){if($Ligne['TypeMoteur']=="IAE"){echo "selected";}} ?>>IAE</option>
										<option value="PW" <?php if($_GET['Mode']=="M"){if($Ligne['TypeMoteur']=="PW"){echo "selected";}} ?>>PW</option>
										<option value="LEAP" <?php if($_GET['Mode']=="M"){if($Ligne['TypeMoteur']=="LEAP"){echo "selected";}} ?>>LEAP</option>
									</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle">&nbsp;Poste de montage : </td>
							<td>
								<select id="posteMontage" name="posteMontage">
									<option name="" value=""></option>
									<option value="AF" <?php if($_GET['Mode']=="M"){if($Ligne['PosteMontage']=="AF"){echo "selected";}} ?>>AF</option>
									<option value="M15" <?php if($_GET['Mode']=="M"){if($Ligne['PosteMontage']=="IAE"){echo "selected";}} ?>>M15</option>
								</select>
							</td>
							<td class="Libelle">&nbsp;Date montage :</td>
							<td>
								<input type="date" name="dateMontage" id="dateMontage" size="12" value="<?php if($_GET['Mode']=="M"){echo AfficheDateFR($Ligne['DateMontage']);}?>">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
					</table>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td align="center">
					<input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="M"){echo "Valider";}else{echo "Ajouter";}?>">
				</td>
			</tr>
		</table>
		</form>
<?php
		if($_GET['Mode']=="M"){
			echo "<script>RechercheDesignation();</script>";
		}
	}
	else
	//Mode suppression
	{
		$requete="DELETE FROM sp_atrmoteur ";
		$requete.=" WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
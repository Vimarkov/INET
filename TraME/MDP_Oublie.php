<!DOCTYPE html>
<html>
<head>
	<link href="CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps(langue){
			if(langue=="EN"){
				if(formulaire.prestation.value=='0'){alert('You have not entered the service.');return false;}
				if(formulaire.nom.value==''){alert('You didn\'t enter your last name.');return false;}
				if(formulaire.prenom.value==''){alert('You didn\'t enter your first name.');return false;}
			}
			else{
				if(formulaire.prestation.value=='0'){alert('Vous n\'avez par renseigné la prestation.');return false;}
				if(formulaire.nom.value==''){alert('Vous n\'avez pas renseigné votre nom.');return false;}
				if(formulaire.prenom.value==''){alert('Vous n\'avez pas renseigné votre prénom.');return false;}
			}
			return true;
		}
		function Fermer(){
			window.close();
		}
	</script>
</head>
<?php
	session_start();
	require("Outils/Connexioni.php");
if($_POST){
	$destinataire="";
	$req="SELECT ";
	$req.="(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=trame_acces.Id_Personne) AS EmailPro ";
	$req.="FROM trame_acces WHERE SUBSTRING(Droit,2,1)=1 AND Id_Prestation=".$_POST['prestation'].";";
	$resulEmail=mysqli_query($bdd,$req);
	$nbEmail=mysqli_num_rows($resulEmail);
	if ($nbEmail>0){
		while($row=mysqli_fetch_array($resulEmail)){
			if($row['EmailPro']<>""){
				$destinataire.=$row['EmailPro'].",";
			}
		}
	}
	
	$req="SELECT Libelle FROM trame_prestation WHERE Id=".$_POST['prestation'];
	$result=mysqli_query($bdd,$req);
	$nb=mysqli_num_rows($result);
	$prestation="";
	if($nb>0){
		$row=mysqli_fetch_array($result);
		$prestation=$row['Libelle'];
	}
	if($destinataire<>""){
		$headers='From: "Extranet AAA"<extranet@aaa-aero.com>'."\n";
		$headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
							
		if($_SESSION['Langue']=="EN"){$object="eTraME - Resetting a Password";}
		else{$object="eTraME - Réinitialisation d'un mot de passe";}
		$message="<html>";
		$message.="<head>";
			$message.="<title>Mot de passe oublié</title>";
		$message.="</head>";
		$message.="<body>";
		$message.="<table width='100%'>";
		if($_SESSION['Langue']=="EN"){
			$message.="<tr><td colspan='2'>The person below forgot his password</td></tr>";
			$message.="<tr><td width='15%'><b>Fisrt name</b></td><td width='85%'>".$_POST['prenom']."</td></tr>";
			$message.="<tr><td width='15%'><b>Last name</td><td>".$_POST['nom']."</td></tr>";
			$message.="<tr><td width='15%'><b>Prestation</td><td>".$prestation."</td></tr>";
		}
		else{
			$message.="<tr><td colspan='2'>La personne ci-dessous a oublié son mot de passe</td></tr>";
			$message.="<tr><td width='15%'><b>Fisrt name</td><td width='85%'>".$_POST['prenom']."</td></tr>";
			$message.="<tr><td width='15%'><b>Last name</td><td width='85%'>".$_POST['nom']."</td></tr>";
			$message.="<tr><td width='15%'><b>Prestation</td><td width='85%'>".$prestation."</td></tr>";
		}
		$message.="</table></td></tr>";
		$message.="</table></body></html>";
		if(mail($destinataire, $object , $message , $headers,'-f extranet@aaa-aero.com')){echo "OK";}
		else{echo "KO";}
	}
	echo "<script>Fermer();</script>";
}
?>
<form id="formulaire" method="POST" action="MDP_Oublie.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
<table class="TableCompetences" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
	<tr>
		<td class="Libelle" >Prestation
		</td>
		<td>
			<select  id="prestation" name="prestation">
				<?php
					echo "<option value='0' ".$selected." >Sélectionner une prestation</option>";
					$req="SELECT Id AS Id_Prestation, Libelle FROM trame_prestation ORDER BY Libelle ";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$selected="";
							echo "<option value='".$row['Id_Prestation']."' ".$selected." >".$row['Libelle']."</option>";
						}
					}
				?>
			</select>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr class="TitreColsUsers">
		<td class="Libelle">
			<?php if($_SESSION['Langue']=="EN"){echo "Last name ";}else{echo "Nom ";} ?>
		</td>
		<td>
			<input type="text" id="nom" name="nom" value="" />
		</td>
		<td class="Libelle">
			<?php if($_SESSION['Langue']=="EN"){echo "First name ";}else{echo "Prénom ";} ?>
		</td>
		<td>
			<input type="text" id="prenom"  name="prenom" value="" />
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td colspan="4" align="center">
			<input class="Bouton" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}?>">
		</td>
	</tr>
</table>
</body>
</html>
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
		function VerifChamps(){
			if(formulaire.msn.value==''){alert('Vous n\'avez pas renseigné le MSN.');return false;}
			if(formulaire.numAMNC.value==''){alert('Vous n\'avez pas renseigné le n° AM/NC.');return false;}
			if(formulaire.type.value=='0'){alert('Vous n\'avez pas renseigné le type.');return false;}
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
require("../../Fonctions.php");
require("../../Connexioni.php");

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
Ecrire_Code_JS_Init_Date();
if($_POST){
	if($_POST['Mode']=="A"){
		$requete="INSERT INTO sp_atram (DateCreation,Id_Createur,Id_Prestation,MSN,ImputationAAA,NCMajeure,OMAssocie,Id_Type,Recurrence,NumAMNC,Designation) ";
		$requete.="VALUES ('".$DateJour."',".$_SESSION['Id_PersonneSP'].",316,".$_POST['msn'].",".$_POST['imputationAAA'].",".$_POST['ncMajeure'].",";
		$requete.="'".$_POST['omAssocie']."',".$_POST['type'].",".$_POST['recurrence'].",'".$_POST['numAMNC']."','".addslashes($_POST['designation'])."') ";
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE sp_atram SET ";
		$requete.="MSN=".$_POST['msn'].",";
		$requete.="ImputationAAA=".$_POST['imputationAAA'].",";
		$requete.="NCMajeure=".$_POST['ncMajeure'].",";
		$requete.="OMAssocie='".$_POST['omAssocie']."',";
		$requete.="Id_Type=".$_POST['type'].",";
		$requete.="NumAMNC='".$_POST['numAMNC']."',";
		$requete.="Recurrence=".$_POST['recurrence']." ";
		$requete.="WHERE Id=".$_POST['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	$titre="";
	if($_GET['Mode']=="A"){$titre="Ajouter une AM / NC majeure";}
	elseif($_GET['Mode']=="M"){$titre="Modifier une AM / NC majeure";}
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
		if($_GET['Id']!='0'){
			$result=mysqli_query($bdd,"SELECT Id, MSN,ImputationAAA,NCMajeure,OMAssocie,NumAMNC,Id_Type,Recurrence, Designation FROM sp_atram WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_AM.php" onSubmit="return VerifChamps();">
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
				<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
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
							<td class="Libelle">&nbsp;N° AM/NC :</td>
							<td>
								<input type="texte" name="numAMNC" id="numAMNC" size="10" value="<?php if($_GET['Mode']=="M"){echo $Ligne['NumAMNC'];}?>">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle">&nbsp;Imputation AAA :</td>
							<td>
								<select name='imputationAAA' id='imputationAAA'>
										<option value='1' <?php if($_GET['Mode']=="M"){if($Ligne['ImputationAAA']==1){echo "selected";}}?>>Oui</option>
										<option value='0' <?php if($_GET['Mode']=="M"){if($Ligne['ImputationAAA']==0){echo "selected";}}else{echo "selected";}?>>Non</option>
								</select>
							</td>
							<td class="Libelle">&nbsp;NC majeure :</td>
							<td>
								<select name='ncMajeure' id='ncMajeure'>
										<option value='1' <?php if($_GET['Mode']=="M"){if($Ligne['NCMajeure']==1){echo "selected";}}?>>Oui</option>
										<option value='0' <?php if($_GET['Mode']=="M"){if($Ligne['NCMajeure']==0){echo "selected";}}else{echo "selected";}?>>Non</option>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle">&nbsp;Ordre de montage associé :</td>
							<td>
								<input type="texte" name="omAssocie" id="omAssocie" size="10" value="<?php if($_GET['Mode']=="M"){echo $Ligne['OMAssocie'];}?>">
							</td>
							<td class="Libelle">&nbsp;Type :</td>
							<td>
								<select name='type' id='type'>
									<option value='0'></option>
									<?php
										$req="SELECT Id, Libelle, Supprime FROM sp_atrtype WHERE Id_Prestation=16 ORDER BY Libelle ";
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
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle">&nbsp;Désignation :</td>
							<td colspan="3">
								<input type="texte" name="designation" id="designation" size="30" value="<?php if($_GET['Mode']=="M"){echo stripslashes($Ligne['Designation']);}?>">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle">&nbsp;Récurrence :</td>
							<td>
								<select name='recurrence' id='recurrence'>
										<option value='1' <?php if($_GET['Mode']=="M"){if($Ligne['Recurrence']==1){echo "selected";}}?>>Oui</option>
										<option value='0' <?php if($_GET['Mode']=="M"){if($Ligne['Recurrence']==0){echo "selected";}}else{echo "selected";}?>>Non</option>
								</select>
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
		$requete="DELETE FROM sp_atram ";
		$requete.=" WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
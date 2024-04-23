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
			if(formulaire.numCQLB.value==''){alert('Vous n\'avez pas renseigné le n° CQLB.');return false;}
			if(formulaire.numCV.value==''){alert('Vous n\'avez pas renseigné le n° CV.');return false;}
			if(formulaire.localisation.value=='0'){alert('Vous n\'avez pas renseigné la localisation.');return false;}
			if(formulaire.type.value=='0'){alert('Vous n\'avez pas renseigné le type.');return false;}
			return true;
		}
		function FermerEtRecharger(){
			opener.location.reload();
			window.close();
		}
		ListeDesignation = new Array();
		function RechercheDesignation(){
			$Ligne="";
			for(i=0;i<ListeDesignation.length;i++){
				if (ListeDesignation[i][0] == formulaire.omAssocie.value){
					$Ligne=ListeDesignation[i][1];
				}
			}
			document.getElementById('designation').innerHTML=$Ligne;
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
		$requete="INSERT INTO sp_atrcqlb (DateCreation,Id_Createur,Id_Prestation,MSN,NumCQLB,NumCV,Id_Localisation,ImputationAAA,OMAssocie,AMAssociee,Id_Type,Recurrence,Designation) ";
		$requete.="VALUES ('".$DateJour."',".$_SESSION['Id_PersonneSP'].",1792,".$_POST['msn'].",'".$_POST['numCQLB']."','".$_POST['numCV']."',";
		$requete.="".$_POST['localisation'].",".$_POST['imputationAAA'].",'".$_POST['omAssocie']."','".$_POST['amAssociee']."',";
		$requete.="".$_POST['type'].",".$_POST['recurrence'].",'".addslashes($_POST['designation'])."') ";
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE sp_atrcqlb SET ";
		$requete.="MSN=".$_POST['msn'].",";
		$requete.="NumCQLB='".$_POST['numCQLB']."',";
		$requete.="Designation='".addslashes($_POST['designation'])."',";
		$requete.="NumCV='".$_POST['numCV']."',";
		$requete.="Id_Localisation=".$_POST['localisation'].",";
		$requete.="ImputationAAA=".$_POST['imputationAAA'].",";
		$requete.="OMAssocie='".$_POST['omAssocie']."',";
		$requete.="AMAssociee='".$_POST['amAssociee']."',";
		$requete.="Id_Type=".$_POST['type'].",";
		$requete.="Recurrence=".$_POST['recurrence']." ";
		$requete.="WHERE Id=".$_POST['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	$titre="";
	if($_GET['Mode']=="A"){$titre="Ajouter un CQLB";}
	elseif($_GET['Mode']=="M"){$titre="Modifier un CQLB";}
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
		$req="SELECT OrdreMontage, Designation FROM sp_atrot WHERE Id_Prestation=1792";
		$resultOM=mysqli_query($bdd,$req);
		$nbResultaOM=mysqli_num_rows($resultOM);
		if ($nbResultaOM>0){
			$i=0;
			while($row=mysqli_fetch_array($resultOM)){
				echo "<script>ListeDesignation[".$i."] = new Array('".$row['OrdreMontage']."','".str_replace("'"," ",$row['Designation'])."');</script>\n";
				$i++;
			}
		}
		
		if($_GET['Id']!='0'){
			$result=mysqli_query($bdd,"SELECT Id,MSN,NumCQLB,NumCV,Id_Localisation,ImputationAAA,AMAssociee,OMAssocie,Id_Type,Recurrence,Designation FROM sp_atrcqlb WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_QLB.php" onSubmit="return VerifChamps();">
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
								<input onKeyUp="nombre(this)" type="texte" name="msn" id="msn" size="8" value="<?php if($_GET['Mode']=="M"){echo $Ligne['MSN'];}?>">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle">&nbsp;N° QLB :</td>
							<td>
								<input type="texte" name="numCQLB" id="numCQLB" size="10" value="<?php if($_GET['Mode']=="M"){echo $Ligne['NumCQLB'];}?>">
							</td>
							<td class="Libelle">&nbsp;N° CV :</td>
							<td>
								<input type="texte" name="numCV" id="numCV" size="10" value="<?php if($_GET['Mode']=="M"){echo $Ligne['NumCV'];}?>">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle">&nbsp;Localisation :</td>
							<td>
								<select name='localisation' id='localisation'>
									<option value='0'></option>
									<?php
										$req="SELECT Id, Libelle, Supprime FROM sp_localisation WHERE Id_Prestation=1792 ORDER BY Libelle ";
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
													if($rowType['Supprime']==false || $rowType['Id']==$Ligne['Id_Localisation']){
														if($rowType['Id']==$Ligne['Id_Localisation']){$selected="selected";}
														echo "<option value='".$rowType['Id']."' ".$selected.">".$rowType['Libelle']."</option>";
													}
												}
											}
										}
									?>
								</select>
							</td>
							<td class="Libelle">&nbsp;Imputation AAA :</td>
							<td>
								<select name='imputationAAA' id='imputationAAA'>
										<option value='1' <?php if($_GET['Mode']=="M"){if($Ligne['ImputationAAA']==1){echo "selected";}}?>>Oui</option>
										<option value='0' <?php if($_GET['Mode']=="M"){if($Ligne['ImputationAAA']==0){echo "selected";}}else{echo "selected";}?>>Non</option>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle">&nbsp;Ordre de montage associé :</td>
							<td>
								<input onKeyUp="RechercheDesignation()" type="texte" name="omAssocie" id="omAssocie" size="10" value="<?php if($_GET['Mode']=="M"){echo $Ligne['OMAssocie'];}?>">
							</td>
							<td class="Libelle">&nbsp;AM associée :</td>
							<td>
								<input type="texte" name="amAssociee" id="amAssociee" size="10" value="<?php if($_GET['Mode']=="M"){echo $Ligne['AMAssociee'];}?>">
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
							<td class="Libelle">&nbsp;Type :</td>
							<td>
								<select name='type' id='type'>
									<option value='0'></option>
									<?php
										$req="SELECT Id, Libelle, Supprime FROM sp_atrtype WHERE Id_Prestation=1792 ORDER BY Libelle ";
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
		$requete="DELETE FROM sp_atrcqlb ";
		$requete.=" WHERE Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
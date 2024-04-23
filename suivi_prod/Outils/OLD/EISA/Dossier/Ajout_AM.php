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
			if(formulaire.numAMNC.value=='0'){alert('Vous n\'avez pas renseigné le n° AM.');return false;}
			if(formulaire.dateAM.value==''){alert('Vous n\'avez pas renseigné la date de l\'AM.');return false;}
			return true;
		}
		function FermerEtRecharger(){
			window.opener.location = "Liste_AM.php";
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
		$requete="INSERT INTO sp_atram (DateCreation,Id_Createur,Id_Prestation,MSN,NumAMNC,NumOF,Id_Localisation,Id_Imputation,Id_MomentDetection,NumDERO,Recurrence,Id_TypeDefaut,Statut,OrigineAM,Id_ProduitImpacte,Id_Cote,Id_ActionCurative) ";
		$requete.="VALUES ('".TrsfDate_($_POST['dateAM'])."',".$_SESSION['Id_PersonneSP'].",463,".$_POST['msn'].",'".$_POST['numAMNC']."','".$_POST['numOF']."',".$_POST['localisation'].",";
		$requete.="".$_POST['imputation'].",".$_POST['moment'].",'".$_POST['numDERO']."',".$_POST['recurrence'].",".$_POST['typedefaut'].",'".addslashes($_POST['statut'])."','".addslashes($_POST['origineAM'])."',".$_POST['produitImpacte'].",".$_POST['cote'].",".$_POST['actionCurative'].") ";
		$result=mysqli_query($bdd,$requete);
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE sp_atram SET ";
		$requete.="MSN=".$_POST['msn'].",";
		$requete.="NumAMNC='".$_POST['numAMNC']."',";
		$requete.="NumOF='".$_POST['numOF']."',";
		$requete.="DateCreation='".TrsfDate_($_POST['dateAM'])."',";
		$requete.="Id_Localisation=".$_POST['localisation'].",";
		$requete.="Id_Cote=".$_POST['cote'].",";
		$requete.="Id_ActionCurative=".$_POST['actionCurative'].",";
		$requete.="Id_Imputation=".$_POST['imputation'].",";
		$requete.="Id_MomentDetection=".$_POST['moment'].",";
		$requete.="NumDERO='".$_POST['numDERO']."',";
		$requete.="Recurrence=".$_POST['recurrence'].",";
		$requete.="Id_TypeDefaut=".$_POST['typedefaut'].",";
		$requete.="Id_ProduitImpacte=".$_POST['produitImpacte'].",";
		$requete.="OrigineAM='".addslashes($_POST['origineAM'])."',";
		$requete.="Statut='".$_POST['statut']."' ";
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
			$result=mysqli_query($bdd,"SELECT Id,MSN,NumOF,NumDERO,OrigineAM,Recurrence,DateCreation,NumAMNC,Moteur,Nacelle,Descriptif,Statut,Id_MomentDetection,Id_Imputation,Id_TypeDefaut,Id_ProduitImpacte,Id_Localisation,Id_ActionCurative,Id_Cote FROM sp_atram WHERE Id=".$_GET['Id']);
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
							<td class="Libelle">&nbsp;N° AM :</td>
							<td>
								<input type="texte" name="numAMNC" id="numAMNC" size="10" value="<?php if($_GET['Mode']=="M"){echo $Ligne['NumAMNC'];}?>">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle">&nbsp;N° OF :</td>
							<td>
								<input type="texte" name="numOF" id="numOF" size="10" value="<?php if($_GET['Mode']=="M"){echo $Ligne['NumOF'];}?>">
							</td>
							<td class="Libelle">&nbsp;Localisation :</td>
							<td>
								<select name="localisation">
									<option value="0"></option>
									<?php 
										$req="SELECT Id,Libelle FROM sp_atrlocalisation WHERE Id_Prestation=463";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($row=mysqli_fetch_array($result)){
												$selected="";
												if($row['Id']==$Ligne['Id_Localisation']){$selected="selected";}
												echo "<option value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
											}
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle">&nbsp;Origine de l'AM :</td>
							<td>
								<select name="origineAM">
									<option value=""></option>
									<option value="Poste engine" <?php if($_GET['Mode']=="M"){if($Ligne['OrigineAM']=='Poste engine'){echo "selected";}}?>>Poste engine</option>
									<option value="P17" <?php if($_GET['Mode']=="M"){if($Ligne['OrigineAM']=='P17'){echo "selected";}}?>>P17</option>
								</select>
							</td>
							<td class="Libelle">&nbsp;Produit impacté :</td>
							<td>
								<select name="produitImpacte">
									<option value="0"></option>
									<?php 
										$req="SELECT Id,Libelle FROM sp_atrproduitimpacte WHERE Id_Prestation=463";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($row=mysqli_fetch_array($result)){
												$selected="";
												if($row['Id']==$Ligne['Id_ProduitImpacte']){$selected="selected";}
												echo "<option value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
											}
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle">&nbsp;Imputation :</td>
							<td>
								<select name="imputation">
									<option value="0"></option>
									<?php 
										$req="SELECT Id,Libelle FROM sp_atrimputation WHERE Id_Prestation=463";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($row=mysqli_fetch_array($result)){
												$selected="";
												if($row['Id']==$Ligne['Id_Imputation']){$selected="selected";}
												echo "<option value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
											}
										}
									?>
								</select>
							</td>
							<td class="Libelle">&nbsp;Moment de détection :</td>
							<td>
								<select name='moment' id='moment'>
									<option value="0"></option>
									<?php 
										$req="SELECT Id,Libelle FROM sp_atrmomentdetection WHERE Id_Prestation=463";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($row=mysqli_fetch_array($result)){
												$selected="";
												if($row['Id']==$Ligne['Id_MomentDetection']){$selected="selected";}
												echo "<option value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
											}
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle">&nbsp;N° DERO :</td>
							<td>
								<input type="texte" name="numDERO" id="numDERO" size="10" value="<?php if($_GET['Mode']=="M"){echo $Ligne['NumDERO'];}?>">
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
						<tr>
							<td class="Libelle">&nbsp;Type de défaut :</td>
							<td>
								<select name="typedefaut">
									<option value="0"></option>
									<?php 
										$req="SELECT Id,Libelle FROM sp_atrtypedefaut WHERE Id_Prestation=463";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($row=mysqli_fetch_array($result)){
												$selected="";
												if($row['Id']==$Ligne['Id_TypeDefaut']){$selected="selected";}
												echo "<option value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
											}
										}
									?>
								</select>
							</td>
							<td class="Libelle">&nbsp;Côté :</td>
							<td>
								<select name="cote">
									<option value="0"></option>
									<?php 
										$req="SELECT Id,Libelle FROM sp_atrcote WHERE Id_Prestation=463";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($row=mysqli_fetch_array($result)){
												$selected="";
												if($row['Id']==$Ligne['Id_Cote']){$selected="selected";}
												echo "<option value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
											}
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle">&nbsp;Action curative :</td>
							<td>
								<select name="actionCurative">
									<option value="0"></option>
									<?php 
										$req="SELECT Id,Libelle FROM sp_atractioncurative WHERE Id_Prestation=463";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($row=mysqli_fetch_array($result)){
												$selected="";
												if($row['Id']==$Ligne['Id_ActionCurative']){$selected="selected";}
												echo "<option value='".$row['Id']."' ".$selected.">".$row['Libelle']."</option>";
											}
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle">&nbsp;Date AM :</td>
							<td>
								<input type="date" name="dateAM" id="dateAM" size="12" value="<?php if($_GET['Mode']=="M"){echo AfficheDateFR($Ligne['DateCreation']);}?>">
							</td>
							<td class="Libelle">&nbsp;Statut :</td>
							<td>
								<select name='statut' id='statut'>
									<option value='Ouverte' <?php if($_GET['Mode']=="M"){if($Ligne['Statut']=='Ouverte'){echo "selected";}}else{echo "selected";}?>>Ouverte</option>
									<option value='Fermée' <?php if($_GET['Mode']=="M"){if($Ligne['Statut']=='Fermée'){echo "selected";}}?>>Fermée</option>
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
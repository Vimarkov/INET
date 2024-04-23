<html>
<head>
	<title>TraME</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		Liste_UO= new Array();
		function VerifChamps(langue){
			//Verifier existance de l'UO
			bExiste=false;
			for(i=0;i<Liste_UO.length;i++){
				if (Liste_UO[i]==formulaire.uo.value){
					bExiste = true;
				}
			}
			if(langue=="EN"){
				if(formulaire.uo.value==''){alert('You didn\'t enter the work unit.');return false;}
				if(bExiste==true){alert('This work unit already exists.');return false;}
				if(formulaire.description.value==''){alert('You didn\'t enter the description.');return false;}
			}
			else{
				if(formulaire.uo.value==''){alert('Vous n\'avez pas renseigné l\'unité d\'oeuvre.');return false;}
				if(bExiste==true){alert('Cette unité d\'oeuvre existe déjà.');return false;}
				if(formulaire.description.value==''){alert('Vous n\'avez pas renseigné la description.');return false;}
			}
			return true;
		}
		function FermerEtRecharger(){
			window.opener.location = "UO.php";
			window.close();
		}
		function nombre(champ){
			var chiffres = new RegExp("[0-9\.]"); /* Modifier pour : var chiffres = new RegExp("[0-9]"); */
			var verif;
			var points = 0; /* Supprimer cette ligne */

			for(x = 0; x < champ.value.length; x++)
			{
			verif = chiffres.test(champ.value.charAt(x));
			if(champ.value.charAt(x) == "."){points++;} /* Supprimer cette ligne */
			if(points > 1){verif = false; points = 1;} /* Supprimer cette ligne */
			if(verif == false){champ.value = champ.value.substr(0,x) + champ.value.substr(x+1,champ.value.length-x+1); x--;}
			}
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
if($_POST){
	if($_POST['Mode']=="A"){
		$droit="";
		$requete="INSERT INTO trame_uo (Libelle,Description,Id_Categorie,Id_Prestation) ";
		$requete.="VALUES ('".addslashes($_POST['uo'])."','".addslashes($_POST['description'])."',".$_POST['categorie'].",".$_SESSION['Id_PrestationTR'].") ";
		$result=mysqli_query($bdd,$requete);
		$IdUO = mysqli_insert_id($bdd);
		
		//Ajout des temps alloués
		$req="SELECT Id FROM trame_domainetechnique WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Supprime=false";
		$resultDT=mysqli_query($bdd,$req);
		$nbResultaDT=mysqli_num_rows($resultDT);
		while($rowDT=mysqli_fetch_array($resultDT)){
			if($_POST['TA_'.$rowDT['Id'].'_CL'] <> ""){
				$requete="INSERT INTO trame_tempsalloue (Id_UO,Id_DomaineTechnique,TypeTravail,Complexite,Temps) ";
				$requete.="VALUES (".$IdUO.",".$rowDT['Id'].",'Creation','Low',".$_POST['TA_'.$rowDT['Id'].'_CL'].") ";
				$result=mysqli_query($bdd,$requete);
			}
			if($_POST['TA_'.$rowDT['Id'].'_CM'] <> ""){
				$requete="INSERT INTO trame_tempsalloue (Id_UO,Id_DomaineTechnique,TypeTravail,Complexite,Temps) ";
				$requete.="VALUES (".$IdUO.",".$rowDT['Id'].",'Creation','Medium',".$_POST['TA_'.$rowDT['Id'].'_CM'].") ";
				$result=mysqli_query($bdd,$requete);
			}
			if($_POST['TA_'.$rowDT['Id'].'_CH'] <> ""){
				$requete="INSERT INTO trame_tempsalloue (Id_UO,Id_DomaineTechnique,TypeTravail,Complexite,Temps) ";
				$requete.="VALUES (".$IdUO.",".$rowDT['Id'].",'Creation','High',".$_POST['TA_'.$rowDT['Id'].'_CH'].") ";
				$result=mysqli_query($bdd,$requete);
			}
			if($_POST['TA_'.$rowDT['Id'].'_CVH'] <> ""){
				$requete="INSERT INTO trame_tempsalloue (Id_UO,Id_DomaineTechnique,TypeTravail,Complexite,Temps) ";
				$requete.="VALUES (".$IdUO.",".$rowDT['Id'].",'Creation','Very High',".$_POST['TA_'.$rowDT['Id'].'_CVH'].") ";
				$result=mysqli_query($bdd,$requete);
			}
			if($_POST['TA_'.$rowDT['Id'].'_COt'] <> ""){
				$requete="INSERT INTO trame_tempsalloue (Id_UO,Id_DomaineTechnique,TypeTravail,Complexite,Temps) ";
				$requete.="VALUES (".$IdUO.",".$rowDT['Id'].",'Creation','Other',".$_POST['TA_'.$rowDT['Id'].'_COt'].") ";
				$result=mysqli_query($bdd,$requete);
			}
			if($_POST['TA_'.$rowDT['Id'].'_UL'] <> ""){
				$requete="INSERT INTO trame_tempsalloue (Id_UO,Id_DomaineTechnique,TypeTravail,Complexite,Temps) ";
				$requete.="VALUES (".$IdUO.",".$rowDT['Id'].",'Update','Low',".$_POST['TA_'.$rowDT['Id'].'_UL'].") ";
				$result=mysqli_query($bdd,$requete);
			}
			if($_POST['TA_'.$rowDT['Id'].'_UM'] <> ""){
				$requete="INSERT INTO trame_tempsalloue (Id_UO,Id_DomaineTechnique,TypeTravail,Complexite,Temps) ";
				$requete.="VALUES (".$IdUO.",".$rowDT['Id'].",'Update','Medium',".$_POST['TA_'.$rowDT['Id'].'_UM'].") ";
				$result=mysqli_query($bdd,$requete);
			}
			if($_POST['TA_'.$rowDT['Id'].'_UH'] <> ""){
				$requete="INSERT INTO trame_tempsalloue (Id_UO,Id_DomaineTechnique,TypeTravail,Complexite,Temps) ";
				$requete.="VALUES (".$IdUO.",".$rowDT['Id'].",'Update','High',".$_POST['TA_'.$rowDT['Id'].'_UH'].") ";
				$result=mysqli_query($bdd,$requete);
			}
			if($_POST['TA_'.$rowDT['Id'].'_UVH'] <> ""){
				$requete="INSERT INTO trame_tempsalloue (Id_UO,Id_DomaineTechnique,TypeTravail,Complexite,Temps) ";
				$requete.="VALUES (".$IdUO.",".$rowDT['Id'].",'Update','Very High',".$_POST['TA_'.$rowDT['Id'].'_UVH'].") ";
				$result=mysqli_query($bdd,$requete);
			}
			if($_POST['TA_'.$rowDT['Id'].'_UOt'] <> ""){
				$requete="INSERT INTO trame_tempsalloue (Id_UO,Id_DomaineTechnique,TypeTravail,Complexite,Temps) ";
				$requete.="VALUES (".$IdUO.",".$rowDT['Id'].",'Update','Other',".$_POST['TA_'.$rowDT['Id'].'_UOt'].") ";
				$result=mysqli_query($bdd,$requete);
			}
		}
		echo "<script>FermerEtRecharger();</script>";
	}
	elseif($_POST['Mode']=="M"){
		$requete="UPDATE trame_uo SET ";
		$requete.="Id_Categorie=".$_POST['categorie'].",";
		$requete.="Libelle='".addslashes($_POST['uo'])."',";
		$requete.="Description='".addslashes($_POST['description'])."'";
		$requete.=" WHERE Id=".$_POST['id']."";
		$result=mysqli_query($bdd,$requete);
		$IdUO = $_POST['id'];
		
		//Suppression des temps alloués
		$requete="DELETE FROM trame_tempsalloue WHERE Id_UO=".$IdUO." ";
		$result=mysqli_query($bdd,$requete);
		
		//Ajout des temps alloués
		$req="SELECT Id FROM trame_domainetechnique WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Supprime=false";
		$resultDT=mysqli_query($bdd,$req);
		$nbResultaDT=mysqli_num_rows($resultDT);
		while($rowDT=mysqli_fetch_array($resultDT)){
			if($_POST['TA_'.$rowDT['Id'].'_CL'] <> ""){
				$requete="INSERT INTO trame_tempsalloue (Id_UO,Id_DomaineTechnique,TypeTravail,Complexite,Temps) ";
				$requete.="VALUES (".$IdUO.",".$rowDT['Id'].",'Creation','Low',".$_POST['TA_'.$rowDT['Id'].'_CL'].") ";
				$result=mysqli_query($bdd,$requete);
			}
			if($_POST['TA_'.$rowDT['Id'].'_CM'] <> ""){
				$requete="INSERT INTO trame_tempsalloue (Id_UO,Id_DomaineTechnique,TypeTravail,Complexite,Temps) ";
				$requete.="VALUES (".$IdUO.",".$rowDT['Id'].",'Creation','Medium',".$_POST['TA_'.$rowDT['Id'].'_CM'].") ";
				$result=mysqli_query($bdd,$requete);
			}
			if($_POST['TA_'.$rowDT['Id'].'_CH'] <> ""){
				$requete="INSERT INTO trame_tempsalloue (Id_UO,Id_DomaineTechnique,TypeTravail,Complexite,Temps) ";
				$requete.="VALUES (".$IdUO.",".$rowDT['Id'].",'Creation','High',".$_POST['TA_'.$rowDT['Id'].'_CH'].") ";
				$result=mysqli_query($bdd,$requete);
			}
			if($_POST['TA_'.$rowDT['Id'].'_CVH'] <> ""){
				$requete="INSERT INTO trame_tempsalloue (Id_UO,Id_DomaineTechnique,TypeTravail,Complexite,Temps) ";
				$requete.="VALUES (".$IdUO.",".$rowDT['Id'].",'Creation','Very High',".$_POST['TA_'.$rowDT['Id'].'_CVH'].") ";
				$result=mysqli_query($bdd,$requete);
			}
			if($_POST['TA_'.$rowDT['Id'].'_COt'] <> ""){
				$requete="INSERT INTO trame_tempsalloue (Id_UO,Id_DomaineTechnique,TypeTravail,Complexite,Temps) ";
				$requete.="VALUES (".$IdUO.",".$rowDT['Id'].",'Creation','Other',".$_POST['TA_'.$rowDT['Id'].'_COt'].") ";
				$result=mysqli_query($bdd,$requete);
			}
			if($_POST['TA_'.$rowDT['Id'].'_UL'] <> ""){
				$requete="INSERT INTO trame_tempsalloue (Id_UO,Id_DomaineTechnique,TypeTravail,Complexite,Temps) ";
				$requete.="VALUES (".$IdUO.",".$rowDT['Id'].",'Update','Low',".$_POST['TA_'.$rowDT['Id'].'_UL'].") ";
				$result=mysqli_query($bdd,$requete);
			}
			if($_POST['TA_'.$rowDT['Id'].'_UM'] <> ""){
				$requete="INSERT INTO trame_tempsalloue (Id_UO,Id_DomaineTechnique,TypeTravail,Complexite,Temps) ";
				$requete.="VALUES (".$IdUO.",".$rowDT['Id'].",'Update','Medium',".$_POST['TA_'.$rowDT['Id'].'_UM'].") ";
				$result=mysqli_query($bdd,$requete);
			}
			if($_POST['TA_'.$rowDT['Id'].'_UH'] <> ""){
				$requete="INSERT INTO trame_tempsalloue (Id_UO,Id_DomaineTechnique,TypeTravail,Complexite,Temps) ";
				$requete.="VALUES (".$IdUO.",".$rowDT['Id'].",'Update','High',".$_POST['TA_'.$rowDT['Id'].'_UH'].") ";
				$result=mysqli_query($bdd,$requete);
			}
			if($_POST['TA_'.$rowDT['Id'].'_UVH'] <> ""){
				$requete="INSERT INTO trame_tempsalloue (Id_UO,Id_DomaineTechnique,TypeTravail,Complexite,Temps) ";
				$requete.="VALUES (".$IdUO.",".$rowDT['Id'].",'Update','Very High',".$_POST['TA_'.$rowDT['Id'].'_UVH'].") ";
				$result=mysqli_query($bdd,$requete);
			}
			if($_POST['TA_'.$rowDT['Id'].'_UOt'] <> ""){
				$requete="INSERT INTO trame_tempsalloue (Id_UO,Id_DomaineTechnique,TypeTravail,Complexite,Temps) ";
				$requete.="VALUES (".$IdUO.",".$rowDT['Id'].",'Update','Other',".$_POST['TA_'.$rowDT['Id'].'_UOt'].") ";
				$result=mysqli_query($bdd,$requete);
			}
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="A" || $_GET['Mode']=="M"){
		//Liste des nom d'UO
		$reqUO="SELECT Libelle FROM trame_uo WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Supprime=false ";
		if($_GET['Id']!='0')
		{
			$reqUO.="AND Id<>".$_GET['Id'];
			$result=mysqli_query($bdd,"SELECT Id, Libelle, Description, Id_Categorie FROM trame_uo WHERE Id=".$_GET['Id']);
			$Ligne=mysqli_fetch_array($result);
		}
		$resultUO=mysqli_query($bdd,$reqUO);
		$nbResultaUO=mysqli_num_rows($resultUO);
		if ($nbResultaUO>0){
			$i=0;
			while($rowUO=mysqli_fetch_array($resultUO)){
				echo "<script>Liste_UO[".$i."]='".$rowUO['Libelle']."'</script>";
				$i++;
			}
		}
?>

		<form id="formulaire" method="POST" action="Ajout_UO.php" onSubmit="return VerifChamps('<?php echo $_SESSION['Langue'];?>');">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="id" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Id'];}?>">
		<table width="95%" align="center" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Work unit";}else{echo "Unité d'oeuvre";} ?></td>
				<td>
					<input type="texte" name="uo" id="uo" size="10" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Libelle'];}?>">
				</td>
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Description";}else{echo "Description";} ?></td>
				<td>
					<input type="texte" name="description" id="description" size="60" value="<?php if($_GET['Mode']=="M"){echo $Ligne['Description'];}?>">
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle" ><?php if($_SESSION['Langue']=="EN"){echo "Category";}else{echo "Catégorie";} ?></td>
				<td colspan=6>
					<select id="categorie" name="categorie" style="width:400px;">
						<?php
							echo"<option value='0'></option>";
							$req="SELECT Id, Libelle, Supprime FROM trame_categorie WHERE Id_Prestation=".$_SESSION['Id_PrestationTR'].";";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowCategorie=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="M"){
										if($rowCategorie['Id']==$Ligne['Id_Categorie']){$selected="selected";}
									}
									if($rowCategorie['Supprime']==false  || $rowCategorie['Id']==$Ligne['Id_Categorie']){
										echo "<option value='".$rowCategorie['Id']."' ".$selected.">".$rowCategorie['Libelle']."</option>";
									}
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr class="TitreColsUsers">
				<td class="Libelle" colspan="2"><?php if($_SESSION['Langue']=="EN"){echo "Allocated time";}else{echo "Temps alloué (h)";} ?></td>
			</tr>
			<tr class="TitreColsUsers">
				<td colspan=4>
					<table cellpadding="0" cellspacing="0" width="100%" align="center" style="border:1px solid;">
						<tr>
							<td class="Libelle" style="border:1px solid;background-color:#dbdbdb;" rowspan="2" align="center" valign="center"><?php if($_SESSION['Langue']=="EN"){echo "Technical domain";}else{echo "Domaine technique";} ?></td>
							<td class="Libelle" style="border:1px solid;background-color:#dbdbdb;" colspan="5" align="center">Creation</td>
							<td class="Libelle" style="border:1px solid;background-color:#dbdbdb;" colspan="5" align="center">Update</td>
						</tr>
						<tr>
							<td class="Libelle" style="border:1px solid;background-color:#dbdbdb;" align="center">Low</td>
							<td class="Libelle" style="border:1px solid;background-color:#dbdbdb;" align="center">Medium</td>
							<td class="Libelle" style="border:1px solid;background-color:#dbdbdb;" align="center">High</td>
							<td class="Libelle" style="border:1px solid;background-color:#dbdbdb;" align="center">Very High</td>
							<td class="Libelle" style="border:1px solid;background-color:#dbdbdb;" align="center">Other</td>
							<td class="Libelle" style="border:1px solid;background-color:#dbdbdb;" align="center">Low</td>
							<td class="Libelle" style="border:1px solid;background-color:#dbdbdb;" align="center">Medium</td>
							<td class="Libelle" style="border:1px solid;background-color:#dbdbdb;" align="center">High</td>
							<td class="Libelle" style="border:1px solid;background-color:#dbdbdb;" align="center">Very High</td>
							<td class="Libelle" style="border:1px solid;background-color:#dbdbdb;" align="center">Other</td>
						</tr>
						<?php
							$req="SELECT Id, Libelle FROM trame_domainetechnique WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Supprime=false";
							$resultDT=mysqli_query($bdd,$req);
							$nbResultaDT=mysqli_num_rows($resultDT);
							if($nbResultaDT){
								while($rowDT=mysqli_fetch_array($resultDT)){
									$CL="";
									$CM="";
									$CH="";
									$CVH="";
									$COt="";
									$UL="";
									$UM="";
									$UH="";
									$UVH="";
									$UOt="";
									if($_GET['Mode']=="M"){
										$req="SELECT Temps,Complexite,TypeTravail FROM trame_tempsalloue WHERE Id_UO='".$Ligne['Id']."' AND Id_DomaineTechnique=".$rowDT['Id']." ";
										$resultTA=mysqli_query($bdd,$req);
										$nbResultaTA=mysqli_num_rows($resultTA);
										if($nbResultaTA){
											while($rowTA=mysqli_fetch_array($resultTA)){
												if($rowTA['TypeTravail']=="Creation"){
													if($rowTA['Complexite']=="Low"){$CL=$rowTA['Temps'];}
													elseif($rowTA['Complexite']=="Medium"){$CM=$rowTA['Temps'];}
													elseif($rowTA['Complexite']=="High"){$CH=$rowTA['Temps'];}
													elseif($rowTA['Complexite']=="Very High"){$CVH=$rowTA['Temps'];}
													elseif($rowTA['Complexite']=="Other"){$COt=$rowTA['Temps'];}
												}
												elseif($rowTA['TypeTravail']=="Update"){
													if($rowTA['Complexite']=="Low"){$UL=$rowTA['Temps'];}
													elseif($rowTA['Complexite']=="Medium"){$UM=$rowTA['Temps'];}
													elseif($rowTA['Complexite']=="High"){$UH=$rowTA['Temps'];}
													elseif($rowTA['Complexite']=="Very High"){$UVH=$rowTA['Temps'];}
													elseif($rowTA['Complexite']=="Other"){$UOt=$rowTA['Temps'];}
												}
											}
										}
									}
							?>
									<tr>
										<td class="Libelle" style="border:1px solid;background-color:#dbdbdb;" align="center"><?php echo $rowDT['Libelle'];?></td>
										<td style="border:1px solid;" align="center"><input style="text-align:center;" name="TA_<?php echo $rowDT['Id']."_CL"; ?>" onKeyUp="nombre(this)" size="10" type="texte" value="<?php echo $CL;?>"/></td>
										<td style="border:1px solid;" align="center"><input style="text-align:center;" name="TA_<?php echo $rowDT['Id']."_CM"; ?>" onKeyUp="nombre(this)" size="10" type="texte" value="<?php echo $CM;?>"/></td>
										<td style="border:1px solid;" align="center"><input style="text-align:center;" name="TA_<?php echo $rowDT['Id']."_CH"; ?>" onKeyUp="nombre(this)" size="10" type="texte" value="<?php echo $CH;?>"/></td>
										<td style="border:1px solid;" align="center"><input style="text-align:center;" name="TA_<?php echo $rowDT['Id']."_CVH"; ?>" onKeyUp="nombre(this)" size="10" type="texte" value="<?php echo $CVH;?>"/></td>
										<td style="border:1px solid;" align="center"><input style="text-align:center;" name="TA_<?php echo $rowDT['Id']."_COt"; ?>" onKeyUp="nombre(this)" size="10" type="texte" value="<?php echo $COt;?>"/></td>
										<td style="border:1px solid;" align="center"><input style="text-align:center;" name="TA_<?php echo $rowDT['Id']."_UL"; ?>" onKeyUp="nombre(this)" size="10" type="texte" value="<?php echo $UL;?>"/></td>
										<td style="border:1px solid;" align="center"><input style="text-align:center;" name="TA_<?php echo $rowDT['Id']."_UM"; ?>" onKeyUp="nombre(this)" size="10" type="texte" value="<?php echo $UM;?>"/></td>
										<td style="border:1px solid;" align="center"><input style="text-align:center;" name="TA_<?php echo $rowDT['Id']."_UH"; ?>" onKeyUp="nombre(this)" size="10" type="texte" value="<?php echo $UH;?>"/></td>
										<td style="border:1px solid;" align="center"><input style="text-align:center;" name="TA_<?php echo $rowDT['Id']."_UVH"; ?>" onKeyUp="nombre(this)" size="10" type="texte" value="<?php echo $UVH;?>"/></td>
										<td style="border:1px solid;" align="center"><input style="text-align:center;" name="TA_<?php echo $rowDT['Id']."_UOt"; ?>" onKeyUp="nombre(this)" size="10" type="texte" value="<?php echo $UOt;?>"/></td>
									</tr>
							<?php
								}
							}
						?>
					</table>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td colspan="4" align="center">
					<input class="Bouton" type="submit" value="<?php if($_GET['Mode']=="M"){if($_SESSION['Langue']=="EN"){echo "Validate";}else{echo "Valider";}}else{if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}}?>">
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$requete="UPDATE trame_uo SET ";
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
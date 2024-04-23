<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="../../../CSS/New_Menu.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="Dossier.js"></script>
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>	
	<script>
		function FermerEtRecharger(){
			window.opener.location = "Liste_Dossier.php";
			window.close();
		}
		function OuvreDef(){window.open("pdf.php?Doc=PDF/Definition des cat�gories","PageDoc","status=no,menubar=no,scrollbars=no,width=50,height=50");}
		function FicheSuiveuse2(Id,Id_FI)
		{window.open("FicheSuiveuse.php?Id_Dossier="+Id+"&Id_FI="+Id_FI,"PageFS","status=no,menubar=no,scrollbars=1,width=90,height=40");}
		function AfficherSubmit(){
			var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnEnregistrer2' name='btnEnregistrer2' value='Enregistrer'>";
			document.getElementById('Enregistrer').innerHTML=bouton;
			var evt = document.createEvent("MouseEvents");
			evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
			document.getElementById("btnEnregistrer2").dispatchEvent(evt);
			document.getElementById('Enregistrer').innerHTML="";
		}	
	</script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../../JS/js/jquery-ui-1.8.5.min.js"></script>
</head>
<?php
session_start();
require("../../Connexioni.php");
require("../../Fonctions.php");

//Verifier si Google CHROME (true) ou Autre (fale)
if (!empty($_SERVER['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];} 
else if (!empty($HTTP_SERVER_VARS['HTTP_USER_AGENT'])){$HTTP_USER_AGENT = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];} 
else if (!isset($HTTP_USER_AGENT)){$HTTP_USER_AGENT = '';} 
if (preg_match_all("#Chrome(.*) Safari#isU", $_SERVER["HTTP_USER_AGENT"], $version)){$NavigOk = true;} 
else {$NavigOk = false;}

Ecrire_Code_JS_Init_Date();

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
$HeureJour = date("H:i:s");

if($_POST){
	if(isset($_POST['btnEnregistrer2'])){	
		//MISE A JOUR DU DOSSIER
		$req="UPDATE sp_olwdossier SET ";
		$req.="MSN=".$_POST['msn'].", ";
		$req.="Id_Client=".$_POST['client'].", ";
		$req.="Reference='".addslashes($_POST['numDossier'])."', ";
		$req.="SectionACP='".$_POST['section']."', ";
		$req.="Titre='".addslashes($_POST['titre'])."', ";
		$req.="Origine='".$_POST['origine']."', ";
		$req.="TypeACP='".$_POST['type']."', ";
		$req.="Id_Urgence=".$_POST['urgence'].", ";
		$req.="PNE=".$_POST['pne'].", ";
		$req.="NumOrigine='".addslashes($_POST['numOrigine'])."', ";
		$req.="Priorite=".$_POST['priorite'].", ";
		$req.="TAI_RestantACP=".$_POST['tai'].", ";
		$req.="CaecACP='".$_POST['caec']."', ";
		$req.="Id_ZoneDeTravail=".$_POST['zone'].", ";
		if($_POST['dernierIC']==true){
			if(substr($_SESSION['DroitSP'],4,1)=='1'){
				if($_POST['statutQualite']<>"0"){
					$req.="Id_Statut='".$_POST['statutQualite']."', ";
					$req.="Avancement=0, ";
					$req.="Id_Retour=".$_POST['retourQualite'].", ";
				}
				else{
					$req.="Id_Statut='".$_POST['statutProd']."', ";
					$req.="Avancement=".$_POST['avancementProd'].", ";
					$req.="Id_Retour=".$_POST['retourProd'].", ";
				}
			}
			elseif(substr($_SESSION['DroitSP'],1,1)=='1'){
				$req.="Id_Statut='".$_POST['statutProd']."', ";
				$req.="Avancement=".$_POST['avancementProd'].", ";
				$req.="Id_Retour=".$_POST['retourProd'].", ";
			}
		}
		$req.="CommentaireZICIA='".addslashes($_POST['commentaireZI'])."', ";
		if(isset($_POST['Fuel'])){$req.="Fuel=1, ";}else{$req.="Fuel=0, ";}
		if(isset($_POST['Elec'])){$req.="Elec=1, ";}else{$req.="Elec=0, ";}
		if(isset($_POST['Hydraulique'])){$req.="Hydraulique=1, ";}else{$req.="Hydraulique=0, ";}
		if(isset($_POST['Metal'])){$req.="Metal=1, ";}else{$req.="Metal=0, ";}
		if(isset($_POST['Meca'])){$req.="Meca=1, ";}else{$req.="Meca=0, ";}
		if(isset($_POST['Structure'])){$req.="Structure=1, ";}else{$req.="Structure=0, ";}
		if(isset($_POST['Systeme'])){$req.="Systeme=1, ";}else{$req.="Systeme=0, ";}
		if(isset($_POST['Oxygene'])){$req.="Oxygene=1 ";}else{$req.="Oxygene=0 ";}
		$req.="WHERE Id=".$_POST['idDossier'];
		$resultUpdate=mysqli_query($bdd,$req);
		
		//Suppression des anciens ATA
		$req="DELETE FROM sp_olwdossier_ata WHERE Id_Dossier=".$_POST['idDossier'];
		$resultDelete=mysqli_query($bdd,$req);

		//Ajout des ATA/Sous-ATA
		$tabATA = explode(";",$_POST['ata_sousata']);
		foreach($tabATA as $valeur){
			 if($valeur<>""){
				$tab2 = explode("_",$valeur);
				$req="INSERT INTO sp_olwdossier_ata (Id_Dossier,ATA,SousATA) VALUES (".$_POST['idDossier'].",".$tab2[0].",".$tab2[1].")";
				$resultAjour=mysqli_query($bdd,$req);
			 }
		}
		
		//MISE A JOUR FICHE D'INTERVENTION
		$reqSelect = "SELECT Id_StatutPROD,Id_StatutQUALITE FROM sp_olwficheintervention WHERE Id=".$_POST['idFI'];
		$resultSelect=mysqli_query($bdd,$reqSelect);
		$rowSelect = mysqli_fetch_array($resultSelect);
		
		$saisie=0;
		if($_POST['typeSaisie']=="QUALITE"){$saisie=1;}
		$req="UPDATE sp_olwficheintervention SET ";
		$req.="PosteAvionACP='".addslashes($_POST['poste'])."',";
		$req.="Id_Pole=".$_POST['pole'].",";
		$req.="PieceAuPoste='".addslashes($_POST['pieceauposte'])."',";
		$req.="TravailRealise='".addslashes($_POST['travailRealise'])."',";
		$req.="DeposeRepose=".$_POST['deposeRepose'].", ";
		$req.="SaisieQualite=".$saisie.", ";
		$req.="Commentaire='".addslashes($_POST['commentaire'])."',";
		$req.="NumFI='".addslashes($_POST['numFI'])."'";
		if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1'){
			$temps=0;			
			if($_POST['tempsObjectif']<>""){$temps=$_POST['tempsObjectif'];}
			$req.= ",DateIntervention='".TrsfDate_($_POST['dateIntervention'])."',";
			$req.= "Vacation='".$_POST['vacation']."',";
			$req.= "TempsObjectif=".$temps."";
		}
		if(substr($_SESSION['DroitSP'],1,1)=='1'){
			$req.=",CommentairePROD='".addslashes($_POST['commentairePROD'])."',";
			if($_POST['dernierIC']==true){
				$req.="Id_StatutPROD='".$_POST['statutProd']."',";
				$req.="Avancement=".$_POST['avancementProd'].",";
				if($rowSelect['Id_StatutPROD']<>$_POST['statutProd']){
					$req.="DateCreationPROD='".$DateJour."',";
				}
				$req.="Id_RetourPROD=".$_POST['retourProd'].",";
			}
			$req.="Id_PROD=".$_SESSION['Id_PersonneSP']."";
		}
		if(substr($_SESSION['DroitSP'],4,1)=='1'){
			$req.=",DateInterventionQ='".TrsfDate_($_POST['dateInterventionQ'])."',";
			$req.="VacationQ='".$_POST['vacationQ']."',";
			if($_POST['dernierIC']==true){
				$req.="Id_StatutQUALITE='".$_POST['statutQualite']."',";
				if($rowSelect['Id_StatutQUALITE']<>$_POST['statutQualite']){
					$req.="DateCreationQUALITE='".$DateJour."',";
				}
				$req.="Id_RetourQUALITE=".$_POST['retourQualite'].",";
			}
			$Id_IQ=0;
			if($_POST['IQ']<>""){$Id_IQ=$_POST['IQ'];}
			else{$Id_IQ=$_SESSION['Id_PersonneSP'];}
			$req.="Id_QUALITE=".$Id_IQ.",";
			$req.="CommentaireQUALITE='".addslashes($_POST['commentaireQualite'])."'";
		}
		$req.=" WHERE Id=".$_POST['idFI'];
		$resultModif=mysqli_query($bdd,$req);
		
		if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
			//Suppression des anciens ECME
			$req="DELETE FROM sp_olwfi_ecme WHERE Id_FI=".$_POST['idFI'];
			$resultDelete=mysqli_query($bdd,$req);
			
			//Ajout des ECME
			$tabECME = explode(";",$_POST['ECME']);
			foreach($tabECME as $valeur){
				 if($valeur<>""){
					$tab2 = explode("_",$valeur);
					$req="INSERT INTO sp_olwfi_ecme (Id_FI,ECME,DateEtalonnage) VALUES (".$_POST['idFI'].",'".$tab2[0]."','".TrsfDate_($tab2[1])."')";
					$resultAjour=mysqli_query($bdd,$req);
				 }
			}
			
			if(substr($_SESSION['DroitSP'],1,1)=='1'){
				//Suppression des anciens compagnons
				$req="DELETE FROM sp_olwfi_travaileffectue WHERE Id_FI=".$_POST['idFI'];
				$resultDelete=mysqli_query($bdd,$req);
				
				//Ajout des compagnons
				$tabCompagnon = explode(";",$_POST['travailEffectue']);
				foreach($tabCompagnon as $valeur){
					 if($valeur<>""){
						$tab2 = explode("C_",$valeur);
						$req="INSERT INTO sp_olwfi_travaileffectue (Id_FI,Id_Personne,TempsPasse) VALUES (".$_POST['idFI'].",".$tab2[0].",".$tab2[1].")";
						$resultAjour=mysqli_query($bdd,$req);
					 }
				}
		
				//Suppression des anciens ingr�dients
				$req="DELETE FROM sp_olwfi_ingredient WHERE Id_FI=".$_POST['idFI'];
				$resultDelete=mysqli_query($bdd,$req);
				
				//Ajout des ingr�dients
				$tabIngredient = explode(";",$_POST['Ingredient']);
				foreach($tabIngredient as $valeur){
					 if($valeur<>""){
						$tab2 = explode("_",$valeur);
						$req="INSERT INTO sp_olwfi_ingredient (Id_FI,Id_Ingredient,NumLot,DatePeremption,CoeffHydrometrique) VALUES (";
						$req.=$_POST['idFI'].",".$tab2[0].",'".$tab2[1]."','".TrsfDate_($tab2[2])."','".$tab2[3]."')";
						$resultAjour=mysqli_query($bdd,$req);
					 }
				}
				
				//Suppression des AIPIS
				$req="DELETE FROM sp_olwfi_aipi WHERE Id_FI=".$_POST['idFI'];
				$resultDelete=mysqli_query($bdd,$req);
				
				//Ajout des AIPI/AIPS
				$tabAIPI = explode(";",$_POST['lesAIPI']);
				foreach($tabAIPI as $valeur){
					 if($valeur<>""){
						$req="INSERT INTO sp_olwfi_aipi (Id_FI,Id_Qualification) VALUES (".$_POST['idFI'].",".$valeur.")";
						$resultAjour=mysqli_query($bdd,$req);
					 }
				}
			}
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
if($_GET){
	$FI=$_GET['Id'];
	if($_GET['Mode']=="M"){
		$IdPersonne=$_GET['Id_Personne'];
		//INFORMATIONS DOSSIER
		$req="SELECT sp_olwficheintervention.Id,sp_olwficheintervention.Id_Dossier,sp_olwdossier.MSN,sp_olwdossier.Reference,";
		$req.="sp_olwdossier.Id_Client,sp_olwdossier.Fuel,sp_olwdossier.Elec,sp_olwdossier.Id_Urgence,sp_olwdossier.PNE,sp_olwdossier.TypeACP, ";
		$req.="sp_olwdossier.Priorite,sp_olwdossier.CaecACP AS Caec,sp_olwdossier.Hydraulique,sp_olwdossier.Metal,sp_olwdossier.Meca,sp_olwdossier.Structure,sp_olwdossier.Systeme,sp_olwdossier.Oxygene,";
		$req.="sp_olwdossier.SectionACP AS MCA,sp_olwdossier.Titre,sp_olwdossier.Id_ZoneDeTravail,sp_olwdossier.CommentaireZICIA,";
		$req.="sp_olwdossier.DateCreation,sp_olwdossier.TAI_RestantACP,sp_olwdossier.Origine,sp_olwdossier.NumOrigine,";
		$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwdossier.Id_Personne) AS CreateurDossier, ";
		
		//INFORMATION INTERVENTION
			//PREPA
		$req.="sp_olwficheintervention.PosteAvionACP,sp_olwficheintervention.Id_Pole,sp_olwficheintervention.TravailRealise,sp_olwficheintervention.SaisieQualite,";
		$req.="sp_olwficheintervention.Commentaire,sp_olwficheintervention.PieceAuPoste,sp_olwficheintervention.NumFI,sp_olwficheintervention.DeposeRepose,";
		//PROD
		$req.="sp_olwficheintervention.DateIntervention,sp_olwficheintervention.Vacation,sp_olwficheintervention.TempsObjectif,";
		$req.="sp_olwficheintervention.Id_StatutPROD,sp_olwficheintervention.Id_RetourPROD,sp_olwficheintervention.CommentairePROD,sp_olwficheintervention.Avancement,";
		$req.="sp_olwficheintervention.Id_FILiee,";
		$req.="(SELECT sp_FILiee.Id_StatutPROD FROM sp_olwficheintervention AS sp_FILiee WHERE sp_FILiee.Id=sp_olwficheintervention.Id_FILiee) AS Id_StatutPRODFILiee,";
		//QUALITE
		$req.="sp_olwficheintervention.DateInterventionQ,sp_olwficheintervention.VacationQ,sp_olwficheintervention.Id_QUALITE,sp_olwficheintervention.Id_StatutQUALITE,";
		$req.="sp_olwficheintervention.Id_RetourQUALITE,sp_olwficheintervention.CommentaireQUALITE ";
		$req.="FROM sp_olwficheintervention LEFT JOIN sp_olwdossier ON sp_olwficheintervention.Id_Dossier=sp_olwdossier.Id ";
		$req.="WHERE sp_olwficheintervention.Id=".$FI;
		$result=mysqli_query($bdd,$req);
		$row=mysqli_fetch_array($result);
		
		//VERIF SI DERNIER ELEMENT
		$DerniereIC=false;
		$reqFI="SELECT MAX(Id) AS Id FROM sp_olwficheintervention WHERE Id_Dossier=".$_GET['Id_Dossier'];
		$resultFI=mysqli_query($bdd,$reqFI);
		$nbResultaFI=mysqli_num_rows($resultFI);
		if($nbResultaFI>0){
			$rowFI=mysqli_fetch_array($resultFI);
			if($rowFI['Id']==$FI){$DerniereIC=true;}
		}
	}
	elseif($_GET['Mode']=="S"){
		$reqNbFI="SELECT Id AS Id FROM sp_olwficheintervention WHERE Id_Dossier=".$_GET['Id_Dossier'];
		$resultNbFI=mysqli_query($bdd,$reqNbFI);
		$nbResultaNbFI=mysqli_num_rows($resultNbFI);
		
		//SUPPRESSION FICHE INTERVENTION
		$req="DELETE FROM sp_olwficheintervention WHERE Id=".$FI;
		$resultSuppr=mysqli_query($bdd,$req);
		
		//SUPPRESSION AIPIS DE LA FI
		$req="DELETE FROM sp_olwfi_aipi WHERE Id_FI=".$FI;
		$resultSuppr=mysqli_query($bdd,$req);
		
		//SUPPRESSION ECME DE LA FI
		$req="DELETE FROM sp_olwfi_ecme WHERE Id_FI=".$FI;
		$resultSuppr=mysqli_query($bdd,$req);
		
		//SUPPRESSION INGREDIENT DE LA FI
		$req="DELETE FROM sp_olwfi_ingredient WHERE Id_FI=".$FI;
		$resultSuppr=mysqli_query($bdd,$req);
		
		//SUPPRESSION TRAVAIL EFFECTUE DE LA FI
		$req="DELETE FROM sp_olwfi_travaileffectue WHERE Id_FI=".$FI;
		$resultSuppr=mysqli_query($bdd,$req);
		
		if($nbResultaNbFI==1){
			//SUPPRESSION DU DOSSIER
			$req="DELETE FROM sp_olwdossier WHERE Id=".$_GET['Id_Dossier'];
			$resultSuppr=mysqli_query($bdd,$req);
			
			//SUPPRESSION DES ATA DU DOSSIER
			$req="DELETE FROM sp_olwdossier_ata WHERE Id_Dossier=".$_GET['Id_Dossier'];
			$resultSuppr=mysqli_query($bdd,$req);
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
?>
<form id="formulaire" class="test" method="POST" action="Modif_Dossier.php" onSubmit="return VerifChamps();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Modifier une intervention
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
			<tr>
				<td colspan="6" style="font-size:15px;font-weight:bold;color:#4d4a82;" align="center">INFORMATIONS DOSSIER
				<input style="display:none;" type="texte" id="typeSaisie" name="typeSaisie" value="<?php if($row['SaisieQualite']==0){echo "PROD";}else{echo "QUALITE";}?>"/>
				</td>
			</tr>
			<tr>
			<td colspan="6" align="left">&nbsp; 
				<?php if($row['SaisieQualite']==0){ ?>
					<a style="text-decoration:none;color:#0066CC;" id="saisiePROD" class="Bouton" href="javascript:ModifPROD()">&nbsp;Saisie PROD&nbsp;</a>&nbsp;&nbsp;
					<a style="text-decoration:none;color:#888888;" id="saisieQUALITE" class="Bouton2" href="javascript:ModifQUALITE()">&nbsp;Saisie QUALITE&nbsp;</a>
				<?php } 
					else { 
				?>
					<a style="text-decoration:none;color:#888888;" id="saisiePROD" class="Bouton2" href="javascript:ModifPROD()">&nbsp;Saisie PROD&nbsp;</a>&nbsp;&nbsp;
					<a style="text-decoration:none;color:#0066CC;" id="saisieQUALITE" class="Bouton" href="javascript:ModifQUALITE()">&nbsp;Saisie QUALITE&nbsp;</a>
				<?php } ?>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			
			<?php
				//V�rification si le dossier n'existe pas d�j� dans sp_olwdossier
				$req="SELECT Id,Reference FROM sp_olwdossier WHERE Id_Prestation=842 AND Id<>".$row['Id_Dossier']." ";
				$resultBDD=mysqli_query($bdd,$req);
				$nbBDD=mysqli_num_rows($resultBDD);
				if($nbBDD>0){
					$i=0;
					while($rowRef=mysqli_fetch_array($resultBDD)){
						echo "<script>Liste_Reference[".$i."] = new Array('".$rowRef['Id']."','".$rowRef['Reference']."');</script>\n";
						$i+=1;
					}
				}
				
				$visible="style='display:none;'";
				$read="";
				$disabled="";
				$etoile="<img src='../../../Images/etoile.png' width='8' height='8' border='0'>";
				if(substr($_SESSION['DroitSP'],0,1)=='0' && substr($_SESSION['DroitSP'],1,1)=='0' && substr($_SESSION['DroitSP'],4,1)=='0'){
					$read="readonly='readonly'";
					$disabled="disabled='disabled'";
					$etoile="";
				}
				$etoile2="<img src='../../../Images/etoile.png' width='8' height='8' border='0'>";
			?>
			<tr>
				<td width="13%" class="Libelle">&nbsp; N� dossier : </td>
				<td width="13%">
					<input type="texte" id="numDossier" name="numDossier" size="15" value="<?php echo $row['Reference'];?>"/>
				</td>
				<td width="13%" class="Libelle">&nbsp; MSN : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
				<td width="20%">
					<input id='msn' name='msn' onKeyUp="nombre(this)" value='<?php echo $row['MSN'];?>'></td>
				</td>
				<td width='13%' class='Libelle'>&nbsp; Client : </td>
				<td width='15%'>
					<select id="client" name="client">
						<option name='0' value='0'></option>
						<?php
							$req="SELECT Id,Libelle,Supprime FROM sp_client WHERE Id_Prestation=842 ORDER BY Libelle";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowClient=mysqli_fetch_array($result)){
									$selected="";
									if($rowClient['Id']==$row['Id_Client']){$selected="selected";}
									if($rowClient['Id']==$row['Id_Client'] ||$rowClient['Supprime']==false){
										echo "<option name='".$rowClient['Id']."' value='".$rowClient['Id']."' ".$selected.">".$rowClient['Libelle']."</option>";
									}
								}
							}
						?>
					</select>
				</td>
			</tr>
			<tr style="display:none;"><td><input id="idDossier" name="idDossier" value="<?php echo $row['Id_Dossier']; ?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="idFI" name="idFI" value="<?php echo $FI; ?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="idFILiee" name="idFILiee" value="<?php echo $row['Id_FILiee']; ?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="idStatutProdFILiee" name="idStatutProdFILiee" value="<?php echo $row['Id_StatutPRODFILiee']; ?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="dernierIC" name="dernierIC" value="<?php echo $DerniereIC; ?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="droit" name="droit" value="<?php echo $_SESSION['DroitSP'];?>"  readonly="readonly"></td></tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width="13%" class="Libelle">&nbsp; Cr�ateur : </td>
				<td width="20%"><?php echo $row['CreateurDossier']; ?></td>
				<td width="13%" class="Libelle">&nbsp; Date de cr�ation : </td>
				<td width="20%"><?php echo $row['DateCreation']; ?></td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width="13%" class="Libelle">&nbsp; Section : </td>
				<td width='15%'>
					<select id="section" name="section">
						<option value=""></option>
						<?php
							$req="SELECT Id,Libelle, Supprime FROM sp_olwsection WHERE Id_Prestation=842 ORDER BY Libelle;";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowSection=mysqli_fetch_array($result)){
									$selected="";
									if($rowSection['Libelle']==$row['MCA']){$selected="selected";}
									if($rowSection['Libelle']==$row['MCA'] || $rowSection['Supprime']==false){
										echo "<option value='".$rowSection['Libelle']."' ".$selected.">".$rowSection['Libelle']."</option>";
									}
								}
							}
						?>
					</select>
				</td>
				<td width='13%' class='Libelle'>&nbsp; Titre : <?php echo $etoile; ?></td>
				<td colspan='3'>
					<input id="titre" name="titre" value="<?php echo $row['Titre'];?>" style="width: 80%;" <?php echo $read;?>>
				</td>
			</tr>
			<tr><td height='4'></td></tr>
			<tr>
				<td width='13%' class='Libelle'>&nbsp; Urgence : </td>
				<td width='20%'>
					<select id="urgence" name="urgence">
						<option value="0"></option>
						<?php
							$req="SELECT Id,Libelle FROM sp_olwurgence WHERE Id_Prestation=842 ORDER BY Libelle;";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowSection=mysqli_fetch_array($result)){
									$selected="";
									if($rowSection['Id']==$row['Id_Urgence']){$selected="selected";}
									if($rowSection['Id']==$row['Id_Urgence'] || $rowSection['Supprime']==false){
										echo "<option value='".$rowSection['Id']."' ".$selected.">".$rowSection['Libelle']."</option>";
									}
								}
							}
						?>
					</select>
				</td>
				<td width="13%" class="Libelle" valign="top">&nbsp; PNE : </td>
				<td width="20%" valign="top">
					<select id="pne" name="pne">
						<option name="0" value="0" <?php if($row['PNE']=="0"){echo "selected";} ?>>Non</option>
						<option name="1" value="1" <?php if($row['PNE']=="1"){echo "selected";} ?>>Oui</option>
					</select>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width='13%' class='Libelle' valign='top'>&nbsp; Priorit� : </td>
				<td width='15%' valign='top'>
					<select id="priorite" name="priorite" <?php echo $disabled;?>>
						<option value="1" <?php if($row['Priorite']==1){ echo "selected";}?>>Low</option>
						<option value="2" <?php if($row['Priorite']==2){ echo "selected";}?>>Medium</option>
						<option value="3" <?php if($row['Priorite']==3){ echo "selected";}?>>High</option>
					</select>
				</td>
				<td width='13%' class='Libelle'>&nbsp; Tps restant (h) : </td>
				<td width='15%'><input onKeyUp='nombre(this)' id='tai' name='tai' value='<?php echo $row['TAI_RestantACP']; ?>' size='8' ></td>
				<td width='13%' class='Libelle'>&nbsp; CA/EC : </td>
				<td width='15%'><input id='caec' name='caec' value='<?php echo $row['Caec'];?>' size='8'></td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width='13%' class='Libelle'>&nbsp; Type : <?php echo $etoile;?></td>
				<td width='20%'>
					<select id="type" name="type">
						<option value=""></option>
						<option value="AM" <?php if($row['TypeACP']=='AM'){echo "selected";} ?>>AM</option>
						<option value="E QLB" <?php if($row['TypeACP']=='E QLB'){echo "selected";} ?>>E QLB</option>
						<option value="Form A" <?php if($row['TypeACP']=='Form A'){echo "selected";} ?>>Form A</option>
						<option value="NC" <?php if($row['TypeACP']=='NC'){echo "selected";} ?>>NC</option>
						<option value="OT" <?php if($row['TypeACP']=='OT'){echo "selected";} ?>>OT</option>
						<option value="TLB" <?php if($row['TypeACP']=='TLB'){echo "selected";} ?>>TLB</option>
						<option value="WO" <?php if($row['TypeACP']=='WO'){echo "selected";} ?>>WO</option>
					</select>
				</td>
				<td width='13%' class='Libelle'>&nbsp; Origine : </td>
				<td width='20%'>
					<select id='origine' name='origine' onchange="AfficherDepose();" <?php echo $disabled;?>>
						<option name='' value=''></option>
						<option name='Admin' value='Admin' <?php if($row['Origine']=='Admin'){echo "selected";} ?>>Admin</option>
						<option name='DA' value='DA' <?php if($row['Origine']=='DA'){echo "selected";} ?>>DA</option>
						<option name='NC' value='NC' <?php if($row['Origine']=='NC'){echo "selected";} ?>>NC</option>
						<option name='PNE' value='PNE' <?php if($row['Origine']=='PNE'){echo "selected";} ?>>PNE</option>
					</select>
				</td>
				<td width='13%'class='Libelle'>&nbsp; N� origine : </td>
				<td width='20%'>
					<input id='numOrigine' name='numOrigine' value='<?php echo $row['NumOrigine']; ?>' <?php echo $read;?>>
				</td>
			</tr>
			<tr><td height='4'></td></tr>
			<tr>
				<td width='13%' class='Libelle'>&nbsp; Zone de travail : <?php echo $etoile; ?></td><td width='15%'>
					<select id='zone' name='zone' <?php echo $disabled; ?>>
						<option name='0' value='0'></option>
						<?php
							$req="SELECT Id,Libelle, Supprime FROM sp_olwzonedetravail  WHERE Id_Prestation=842 ORDER BY Libelle;";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($rowZone=mysqli_fetch_array($result)){
									$selected="";
									if($rowZone['Id']==$row['Id_ZoneDeTravail']){$selected="selected";}
									if($rowZone['Id']==$row['Id_ZoneDeTravail'] ||$rowZone['Supprime']==false){
										echo "<option name='".$rowZone['Id']."' value='".$rowZone['Id']."' ".$selected.">".stripslashes($rowZone['Libelle'])."</option>";
									}
								}
							}
						?>
					</select>
				</td>
				<td width='13%' class='Libelle'>&nbsp; Commentaire zone : </td><td width='15%' colspan='3'>
					<input id="commentaireZI" name="commentaireZI" value="<?php echo $row['CommentaireZICIA']; ?>" style="width: 80%;" <?php echo $read;?>>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<tr>
				<td width='13%' class='Libelle' valign='top'>&nbsp; Comp�tence(s) : <?php echo $etoile;?></td>
				<td>
					<table width='100%' cellpadding='0' cellspacing='0' align='left'>
						<tr>
							<td><input type="checkbox" id="Fuel" name="Fuel" value="Fuel" <?php if($row['Fuel']==1){ echo "checked";}?> <?php echo $disabled;?> >Fuel &nbsp;&nbsp;</td>
							<td><input type="checkbox" id="Elec" name="Elec" value="Elec" <?php if($row['Elec']==1){ echo "checked";}?> <?php echo $disabled;?>>Elec &nbsp;&nbsp;</td>
						</tr>
						<tr>
							<td><input type="checkbox" id="Hydraulique" name="Hydraulique" value="Hydraulique" <?php if($row['Hydraulique']==1){ echo "checked";}?> <?php echo $disabled;?> >Hydraulique &nbsp;&nbsp;</td>
							<td><input type="checkbox" id="Meca" name="Meca" value="Meca" <?php if($row['Meca']==1){ echo "checked";}?> <?php echo $disabled;?>>M�ca &nbsp;&nbsp;</td>
						</tr>
						<tr>
							<td><input type="checkbox" id="Metal" name="Metal" value="Metal" <?php if($row['Metal']==1){ echo "checked";}?> <?php echo $disabled;?>>M�tal &nbsp;&nbsp;</td>
							<td><input type="checkbox" id="Oxygene" name="Oxygene" value="Oxygene" <?php if($row['Oxygene']==1){ echo "checked";}?> <?php echo $disabled;?>>Oxyg�ne &nbsp;&nbsp;</td>
						</tr>
						<tr>
							<td><input type="checkbox" id="Structure" name="Structure" value="Structure" <?php if($row['Structure']==1){ echo "checked";}?> <?php echo $disabled;?>>Structure &nbsp;&nbsp;</td>
							<td><input type="checkbox" id="Systeme" name="Systeme" value="Systeme" <?php if($row['Systeme']==1){ echo "checked";}?> <?php echo $disabled;?>>Syst�me &nbsp;&nbsp;</td>
						</tr>
					</table>
				</td>
				<td width='15%' class='Libelle'>
					<table cellpadding='0' cellspacing='0' style='-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;'>
						<tr>
							<td colspan='2'>&nbsp; Liste des ATA/Sous-ATA : </td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp; ATA : </td>
							<td bgcolor='#e4e7f0'>
								<select name='ata' id='ata' onchange='Recharge_SousATA();' onkeypress='if(event.keyCode == 13)Ajouter()'>
									<option name='' value=''></option>
									<?php
									$req="SELECT DISTINCT ATA FROM sp_atasousata ORDER BY ATA;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowATA=mysqli_fetch_array($result)){
											echo "<option name='".$rowATA['ATA']."' value='".$rowATA['ATA']."'>".$rowATA['ATA']."</option>";
										}
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0'>&nbsp;Sous-ATA : </td>
							<td bgcolor='#e4e7f0'>
								<div id='sousatas'>
									<select id='sousata' name='sousata' onkeypress='if(event.keyCode == 13)Ajouter()'>
										<option value=''></option>
											<?php
											$req="SELECT ATA, SousATA FROM sp_atasousata ORDER BY ATA, SousATA;";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											if ($nbResulta>0){
												$i=0;
												while($rowATA=mysqli_fetch_array($result)){
													echo "<script>Liste_SousATA[".$i."] = new Array('".$rowATA['ATA']."','".$rowATA['SousATA']."');</script>\n";
													$i+=1;
												}
											}
											?>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
								<?php
									if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
										echo "<a style='text-decoration:none;' class='Bouton' href='javascript:Ajouter()'>&nbsp;Ajouter&nbsp;</a>";
									}
								?>
							</td>
						</tr>
					</table>
				</td>
				<td width='20%' valign='top'>
					<table id='tab_ATA' width='100%' cellpadding='0' cellspacing='0'>
						<tr><td width='10%' class='Libelle'>ATA</td><td width='15%' class='Libelle'>Sous-ATA</td><td></td></tr>
						<?php
							$listeATA="";
							$req="SELECT ATA, SousATA FROM sp_olwdossier_ata WHERE Id_Dossier=".$row['Id_Dossier']." ORDER BY ATA, SousATA;";
							$result=mysqli_query($bdd,$req);
							$nbResultaATA=mysqli_num_rows($result);
							if ($nbResultaATA>0){
								while($rowATA=mysqli_fetch_array($result)){
									$btn="";
									if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
										$btn="<a style=\"text-decoration:none;\" href=\"javascript:Supprimer('".$rowATA['ATA']."_".$rowATA['SousATA'].";')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
									}
									echo "<tr id='".$rowATA['ATA']."_".$rowATA['SousATA'].";'>";
									echo "<td>".$rowATA['ATA']."</td>";
									echo "<td>".$rowATA['SousATA']."</td>";
									echo "<td>".$btn."</td></tr>";
									$listeATA.=$rowATA['ATA']."_".$rowATA['SousATA'].";";
								}
							}
						?>
					</table>
				</td>
			</tr>
			<tr><td height="4"></td></tr>
			<?php
				$nbTempsDossier=0;
				$reqSum="SELECT SUM(sp_olwfi_travaileffectue.TempsPasse) AS Tps FROM sp_olwfi_travaileffectue ";
				$reqSum.="LEFT JOIN sp_olwficheintervention ON sp_olwfi_travaileffectue.Id_FI=sp_olwficheintervention.Id ";
				$reqSum.="WHERE sp_olwficheintervention.Id_Dossier=".$row['Id_Dossier'];
				$resultSum=mysqli_query($bdd,$reqSum);
				$nbSum=mysqli_num_rows($resultSum);
				if ($nbSum>0){
					$rowSum=mysqli_fetch_array($resultSum);
					if($rowSum['Tps']<>""){$nbTempsDossier=$rowSum['Tps'];}
				}
				
			?>
			<tr>
				<td width='13%' valign='top' class='Libelle'>&nbsp; Temps pass� : </td>
				<td width='15%' valign='top'><input type='text' size='4' style='border:none' name='tpsDossier' id='tpsDossier' value='<?php echo $nbTempsDossier; ?>' readonly='readonly'/></td>
				<td></td>
				<td colspan="2" align="left">
					<a style="text-decoration:none;" class="Bouton" href="javascript:FicheSuiveuse2('<?php echo $row['Id_Dossier'];?>','<?php echo $row['Id'];?>')">&nbsp;Fiche suiveuse&nbsp;</a>
				</td>
			</tr>
			<?php
			echo "<tr style='display:none;'><td><input id='ata_sousata' name='ata_sousata' value='".$listeATA."'  readonly='readonly'></td></tr>";
			?>
			<tr><td height="8"></td></tr>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table width="100%" cellpadding="3" cellspacing="0" align="center" class="GeneralInfo">
			<tr>
				<td colspan="9" style="font-size:15px;font-weight:bold;color:#4d4a82;" align="center">HISTORIQUE DES INTERVENTIONS</td>
			</tr>
			<tr>
				<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;">Poste</td>
				<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;">P�le</td>
				<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;">N� IC</td>
				<td class="EnTeteTableauCompetences" width="12%" style="text-align:center;">Date intervention<br>PROD</td>
				<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;">Vacation PROD</td>
				<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;">Statut PROD</td>
				<td class="EnTeteTableauCompetences" width="15%" style="text-align:center;">Retour PROD</td>
				<td class="EnTeteTableauCompetences" width="12%" style="text-align:center;">Date intervention<br>QUALITE</td>
				<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;">Vacation QUALITE</td>
				<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;">Statut QUALITE</td>
				<td class="EnTeteTableauCompetences" width="15%" style="text-align:center;">Retour QUALITE</td>
			</tr>
			<?php
				$reqHistorique = "SELECT sp_olwficheintervention.Id,sp_olwficheintervention.PosteAvionACP,sp_olwficheintervention.DateIntervention, sp_olwficheintervention.Id_StatutPROD,";
				$reqHistorique .= "sp_olwficheintervention.DateInterventionQ, sp_olwficheintervention.Id_StatutQUALITE, ";
				$reqHistorique .= "sp_olwficheintervention.Vacation, sp_olwficheintervention.VacationQ,sp_olwficheintervention.NumFI, ";
				$reqHistorique .= "(SELECT sp_pole.Libelle FROM sp_pole WHERE sp_pole.Id=sp_olwficheintervention.Id_Pole) AS Pole, ";
				$reqHistorique .= "(SELECT sp_olwretour.Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourPROD) AS RetourProd, ";
				$reqHistorique .= "(SELECT sp_olwretour.Libelle FROM sp_olwretour WHERE sp_olwretour.Id=sp_olwficheintervention.Id_RetourQUALITE) AS RetourQualite ";
				$reqHistorique .= "FROM sp_olwficheintervention ";
				$reqHistorique .= "WHERE sp_olwficheintervention.Id_Dossier=".$row['Id_Dossier'];
				$resultH=mysqli_query($bdd,$reqHistorique);
				$nbH=mysqli_num_rows($resultH);
				if ($nbH>0){
					$nb=1;
					while($rowH=mysqli_fetch_array($resultH)){
						$bordure="";
						if($nb<$nbH){
							$bordure="border-bottom:1px dotted #0077aa;";
						}
						?>
							<tr>
							<td width="6%" style="text-align:center;<?php echo $bordure;?>"><?php echo $rowH['PosteAvionACP'];?></td>
							<td width="6%" style="text-align:center;<?php echo $bordure;?>"><?php echo $rowH['Pole'];?></td>
							<td width="6%" style="text-align:center;<?php echo $bordure;?>"><?php echo $rowH['NumFI'];?></td>
							<td width="12%" style="text-align:center;<?php echo $bordure;?>"><?php echo AfficheDateFR($rowH['DateIntervention']);?></td>
							<td width="10%" style="text-align:center;<?php echo $bordure;?>"><?php echo $rowH['Vacation'];?></td>
							<td width="6%" style="text-align:center;<?php echo $bordure;?>"><?php echo $rowH['Id_StatutPROD'];?></td>
							<td width="10%" style="text-align:center;<?php echo $bordure;?>"><?php echo $rowH['RetourProd'];?></td>
							<td width="12%" style="text-align:center;<?php echo $bordure;?>"><?php echo AfficheDateFR($rowH['DateInterventionQ']);?></td>
							<td width="10%" style="text-align:center;<?php echo $bordure;?>"><?php echo $rowH['VacationQ'];?></td>
							<td width="6%" style="text-align:center;<?php echo $bordure;?>"><?php echo $rowH['Id_StatutQUALITE'];?></td>
							<td width="10%" style="text-align:center;<?php echo $bordure;?>"><?php echo $rowH['RetourQualite'];?></td>
							</tr>
						<?php
						$nb++;
					}
				}
			?>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr><td>
		<table width="100%" cellpadding="3" cellspacing="0" align="center" class="GeneralInfo">
			<tr>
				<td colspan="10" style="font-size:15px;font-weight:bold;color:#4d4a82;" align="center">INFORMATIONS INTERVENTION</td>
			</tr>
			<tr>
				<td colspan="2" width="25%" valign="top">
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="4" colspan="2" align="center" style="font-size:14px;font-weight:bold;"></td></tr>
						<tr>
							<td width="13%" class="Libelle">&nbsp; Poste avion : </td>
							<td width="20%">
								<select id="poste" name="poste" <?php echo $disabled;?>>
								<?php
									$IdPole=0;
									$poste="";
									$req="SELECT Id,Libelle, Supprime FROM sp_poste WHERE Id_Prestation=842 ORDER BY Libelle;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowPoste=mysqli_fetch_array($result)){
											$selected="";
											if($rowPoste['Libelle']==$row['PosteAvionACP']){$selected="selected";}
											if($rowPoste['Libelle']==$row['PosteAvionACP'] || $rowPoste['Supprime']==false){
												echo "<option value='".$rowPoste['Libelle']."' ".$selected.">".$rowPoste['Libelle']."</option>";
											}
										}
									}
									else{
										echo "<option name='' value=''></option>";
									}
								?>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
							<tr>
							<td width="13%" class="Libelle">&nbsp; P�le : </td>
							<td width="20%">
								<select id="pole" name="pole" <?php echo $disabled;?>>
									<?php
										$req="SELECT Id, Libelle FROM sp_pole WHERE Id_Prestation=842 ORDER BY Libelle;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($rowPole=mysqli_fetch_array($result)){
												$selected="";
												if($rowPole['Id']==$row['Id_Pole']){$selected="selected";}
												echo "<option name='".$rowPole['Id']."' value='".$rowPole['Id']."' ".$selected.">".$rowPole['Libelle']."</option>";
											}
										}
										else{
											echo "<option name='0' value='0'></option>";
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td width="13%" class="Libelle">&nbsp; N� FI</td>
							<td width="20%">
								<input type="text" name="numFI" value="<?php echo $row['NumFI'];?>">
							</td>
						</tr>
						<?php
							$chckDepose="";
							$chckRepose="";
							$displayDA="";
							if($row['Origine']<>"DA"){$displayDA="style='display:none;'";}
							if($row['DeposeRepose']==0){$chckDepose="checked";}
							else{$chckRepose="checked";}
						?>
						<tr class="depose" <?php echo $displayDA; ?>><td height="4" colspan="2"></td></tr>
						<tr class="depose" <?php echo $displayDA; ?>>
							<td width="13%" class="Libelle">
								&nbsp; D�pose <?php echo $etoile;?> <input type="radio" id='deposeRepose' name='deposeRepose' value="0" <?php echo $chckDepose;?>>
							</td>
							<td class="Libelle">
								&nbsp; Repose <input type="radio" id='deposeRepose' name='deposeRepose' value="1" <?php echo $chckRepose;?>>
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td width="13%" class="Libelle" id="LibellePieceauposte">&nbsp; Pi�ce � retirer <br> &nbsp; au poste : </td>
							<td width="20%">
								<select id="pieceauposte" name="pieceauposte" <?php echo $disabled;?>>
									<option value=""></option>
									<option value="Chariot de DA" <?php if($row['PieceAuPoste']=='Chariot de DA'){echo "selected";} ?>>Chariot de DA</option>
									<option value="K943" <?php if($row['PieceAuPoste']=='K943'){echo "selected";} ?>>K943</option>
									<option value="Station livraison" <?php if($row['PieceAuPoste']=='Station livraison'){echo "selected";} ?>>Station livraison</option>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td colspan="2" width="13%" id="LibelleTravailRealise" class="Libelle" valign="center">&nbsp; Travail � r�aliser : </td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td colspan="2" width="20%">
								&nbsp;<textarea id="travailRealise" name="travailRealise" rows="3" cols="45" style="resize:none;" <?php echo $read;?>><?php echo stripslashes($row['TravailRealise']);?></textarea>
							</td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td colspan="2" width="13%" id="LibelleCommentaire" class="Libelle" valign="center">&nbsp; Commentaire : </td>
						</tr>
						<tr><td height="4" colspan="2"></td></tr>
						<tr>
							<td colspan="2" width="20%">
								&nbsp;<textarea id="commentaire" name="commentaire" rows="3" cols="45" style="resize:none;" <?php echo $read;?>><?php echo stripslashes($row['Commentaire']);?></textarea>
							</td>
						</tr>
						<tr><td height="4" colspan="2" ></td></tr>
					</table>
				</td>
				<?php
					$readSTCE="";
					$disabledSTCE="";
					$readCE="";
					$disabledCE="";
					$readIQ="";
					$disabledIQ="";
					$etoileSTCE="<img src='../../../Images/etoile.png' width='8' height='8' border='0'>";
					if(substr($_SESSION['DroitSP'],0,1)=='0' && substr($_SESSION['DroitSP'],1,1)=='0'){
						$readSTCE="readonly='readonly'";
						$disabledSTCE="disabled='disabled'";
						$etoileSTCE="";
					}
					if(substr($_SESSION['DroitSP'],1,1)=='0'){
						$readCE="readonly='readonly'";
						$disabledCE="disabled='disabled'";
						$etoileCE="";
					}
					if(substr($_SESSION['DroitSP'],4,1)=='0'){
						$readIQ="readonly='readonly'";
						$disabledIQ="disabled='disabled'";
						$etoileIQ="";
					}
				?>
				<td colspan="2" width="33%" valign="top">
					<table width="100%" id="tableProd" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="4" colspan="2" align="center" style="font-size:14px;font-weight:bold;" bgcolor="#dbeef9">PROD</td></tr>
						<tr>
							<td bgcolor="#dbeef9" width="13%" class="Libelle">&nbsp; Date intervention : </td>
							<td bgcolor="#dbeef9" width="20%">
								<input <?php if(substr($_SESSION['DroitSP'],0,1)=='0' && substr($_SESSION['DroitSP'],1,1)=='0'){echo "type='texte'";}else{echo "type='date'";}?> style="text-align:center;" id="dateIntervention" name="dateIntervention"  size="15" value="<?php echo AfficheDateFR($row['DateIntervention']);?>" <?php echo  $readSTCE;?>>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width="13%" class="Libelle">&nbsp; Vacation : </td>
							<td bgcolor="#dbeef9" width="20%" colspan="3">
								<select id="vacation" name="vacation" <?php echo $disabledSTCE;?>>
									<option name="" value=""></option>
									<option name="J" value="J" <?php if($row['Vacation']=="J"){echo "selected";} ?>>Jour</option>
									<option name="S" value="S" <?php if($row['Vacation']=="S"){echo "selected";} ?>>Soir</option>
									<option name="N" value="N" <?php if($row['Vacation']=="N"){echo "selected";} ?>>Nuit</option>
									<option name="VSD Jour" value="VSD Jour" <?php if($row['Vacation']=="VSD Jour"){echo "selected";} ?>>VSD Jour</option>
									<option name="VSD Nuit" value="VSD Nuit" <?php if($row['Vacation']=="VSD Nuit"){echo "selected";} ?>>VSD Nuit</option>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width="13%" class="Libelle">&nbsp;Temps objectif : </td>
							<td bgcolor="#dbeef9" width="20%" colspan="3">
								<input onKeyUp="nombre(this)" id="tempsObjectif" <?php echo $readSTCE;?> name="tempsObjectif" size="5" type="text" value="<?php echo $row['TempsObjectif']; ?>">
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width='13%' class="Libelle" valign="top">
								<table cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor='#e4e7f0' colspan='2'><i>&nbsp; Ajouter les op�rateurs :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; Personne : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
										<select id="compagnon" id="compagnon" name="compagnon" style="width:130px;" onkeypress="if(event.keyCode == 13)AjouterTE()">
											<?php
											echo"<option name='0' value='0'></option>";
											$req="SELECT DISTINCT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
											$req.="FROM new_rh_etatcivil LEFT JOIN sp_acces ON new_rh_etatcivil.Id=sp_acces.Id_Personne WHERE sp_acces.Id_Prestation=842 AND SUBSTR(sp_acces.Droit,3,1)='1' ORDER BY Nom, Prenom;";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											if ($nbResulta>0){
												$i=0;
												while($rowCompagnon=mysqli_fetch_array($result)){
													echo "<option value='".$rowCompagnon['Id']."'>".$rowCompagnon['Nom']." ".$rowCompagnon['Prenom']."</option>";
													echo "<script>Liste_Personne[".$i."] = new Array('".$rowCompagnon['Id']."','".addslashes($rowCompagnon['Nom'])."','".addslashes($rowCompagnon['Prenom'])."');</script>\n";
													$i+=1;
												}
											}
											?>
										</select>
										</td>
									</tr>
									<tr>
										<td width="40%" bgcolor='#e4e7f0'>&nbsp;Temps pass� : </td>
									</tr>
									<tr>
										<td width="60%" bgcolor='#e4e7f0'>&nbsp; 
											<input onKeyUp="nombre(this)" style="text-align:center;" onKeypress="if(event.keyCode == 13)AjouterTE()" id="tempsPasse" name="tempsPasse" size="5" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
										<?php
											if(substr($_SESSION['DroitSP'],1,1)=='1'){
										?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterTE()'>&nbsp;Ajouter&nbsp;</a>
										<?php
											}
										?>
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='20%' valign='top'>
								<table id="tab_TravailEffectue" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle" width="70%">Personne</td><td class="Libelle">Nb. heures</td>
									<?php
											$listeCompagnon="";
											$TempsPasseTotalFI=0;
											$req="SELECT Id, Id_Personne, TempsPasse,";
											$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwfi_travaileffectue.Id_Personne) AS NomPrenom ";
											$req.="FROM sp_olwfi_travaileffectue WHERE Id_FI=".$FI." ORDER BY NomPrenom;";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											if ($nbResulta>0){
												while($rowCompagnon=mysqli_fetch_array($result)){
													$btn="";
													if(substr($_SESSION['DroitSP'],1,1)=='1'){
														$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerTE('".$rowCompagnon['Id_Personne']."C_".$rowCompagnon['TempsPasse']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
													}
													echo "<tr id='".$rowCompagnon['Id_Personne']."C_".$rowCompagnon['TempsPasse']."'><td>".$rowCompagnon['NomPrenom']."</td><td>".$rowCompagnon['TempsPasse']."</td><td>".$btn."</td></tr>";
													$listeCompagnon.=$rowCompagnon['Id_Personne']."C_".$rowCompagnon['TempsPasse'].";";
													$TempsPasseTotalFI+=$rowCompagnon['TempsPasse'];
												}
											}
										?>
									</tr>
								</table>
								<table id="tab_TravailEffectue" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle" width="70%">TOTAL</td><td class="Libelle">
										<input type="text" size="4" style="background-color:#dbeef9;border:none" name="tpsFI" id="tpsFI" value="<?php echo $TempsPasseTotalFI;?>" readonly="readonly"/>
									</td></tr>
								</table>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9">&nbsp; Statut PROD : </td>
							<td width="20%" bgcolor="#dbeef9">
								<?php 
									$disabledNew = $disabledCE;
									$disabledIQNew = $disabledIQ;
									if($DerniereIC==false){
										$disabledNew = "disabled='disabled'";
										$disabledIQNew = "disabled='disabled'";
									}
								?>
								<select id="statutProd" name="statutProd" onchange="Recharge_StatutProd();" <?php echo $disabledNew;?>>
									<option name="" value=""></option>
									<?php
									$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation=842 AND TypeStatut='P' AND Id<>'REWORK' ORDER BY Id;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowStatut=mysqli_fetch_array($result)){
											$selected = "";
											if($row['Id_StatutPROD']==$rowStatut['Id']){$selected="selected";}
											echo "<option name='".$rowStatut['Id']."' value='".$rowStatut['Id']."' ".$selected.">".$rowStatut['Id']."</option>";
										}
									}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9">&nbsp; Retour PROD : <a style="text-decoration:none;" href="javascript:OuvreDef();"><img id="img" src="../../../Images/aide2.gif" alt="aide" title="aide"></a></td>
							<td width="20%" bgcolor="#dbeef9">
								<div id='retourP'>
								<select id="retourProd" name="retourProd" <?php echo $disabledNew;?>>
									<option name="0" value="0"></option>
									<?php
									$req="SELECT Id, Libelle, Id_Statut, Supprime FROM sp_olwretour WHERE Id_Prestation=842 ORDER BY Libelle;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										$i=0;
										while($rowRetour=mysqli_fetch_array($result)){
											echo "<script>Liste_Retour[".$i."] = new Array(\"".$rowRetour['Id']."\",\"".$rowRetour['Libelle']."\",\"".$rowRetour['Id_Statut']."\",\"".$rowRetour['Supprime']."\");</script>\n";
											if($row['Id_StatutPROD']==$rowRetour['Id_Statut']){
												$selected = "";
												if($row['Id_RetourPROD']==$rowRetour['Id']){$selected="selected";}
												if($row['Id_RetourPROD']==$rowRetour['Id'] || $rowRetour['Supprime']==0){
													echo "<option name='".$rowRetour['Id']."' value='".$rowRetour['Id']."' ".$selected.">".$rowRetour['Libelle']."</option>";
												}
											}
											$i+=1;
										}
									}
									?>
								</select>
								</div>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9">&nbsp; Avancement PROD :</td>
							<td width="20%" bgcolor="#dbeef9">
								<div id='avancementP'>
								<select id="avancementProd" name="avancementProd" <?php echo $disabledNew;?>>
									<?php
										if($row['Id_StatutPROD']=="TFS"){
											for($i=0;$i<=100;$i=$i+5){
												$selected="";
												if($i==$row['Avancement']){$selected="selected";}
												echo "<option name='".$i."' value='".$i."' ".$selected.">".$i."</option>";
											}
										}
										else{
											echo "<option name='0' value='0' selected></option>";
										}
									?>
								</select>
								</div>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9" valign="center">&nbsp; Commentaire PROD : </td>
							<td width="20%" bgcolor="#dbeef9">
								<textarea id="commentairePROD" name="commentairePROD" rows="5" cols="40" style="resize:none;" <?php echo $readCE;?>><?php echo stripslashes($row['CommentairePROD']); ?></textarea>
							</td>
						</tr>
					</table>
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="4" colspan="2"></td></tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr><td height="4" colspan="2" align="center" style="font-size:14px;font-weight:bold;" bgcolor="#c7e048">QUALITE</td></tr>
						<tr>
							<td bgcolor="#c7e048" width="13%" class="Libelle">&nbsp; Date intervention : </td>
							<td bgcolor="#c7e048" width="20%">
								<input <?php if(substr($_SESSION['DroitSP'],4,1)=='0'){echo "type='texte'";}else{echo "type='date'";}?> style="text-align:center;" id="dateInterventionQ" name="dateInterventionQ"  size="15" value="<?php echo AfficheDateFR($row['DateInterventionQ']);?>" <?php echo  $readIQ;?>>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td bgcolor="#c7e048" width="13%" class="Libelle">&nbsp; Vacation : </td>
							<td bgcolor="#c7e048" width="20%" colspan="3">
								<select id="vacationQ" name="vacationQ" <?php echo $disabledIQ;?>>
									<option name="" value=""></option>
									<option name="J" value="J"  <?php if($row['VacationQ']=="J"){echo "selected";} ?>>Jour</option>
									<option name="S" value="S"  <?php if($row['VacationQ']=="S"){echo "selected";} ?>>Soir</option>
									<option name="N" value="N"  <?php if($row['VacationQ']=="N"){echo "selected";} ?>>Nuit</option>
									<option name="VSD Jour" value="VSD Jour"  <?php if($row['VacationQ']=="VSD Jour"){echo "selected";} ?>>VSD Jour</option>
									<option name="VSD Nuit" value="VSD Nuit"  <?php if($row['VacationQ']=="VSD Nuit"){echo "selected";} ?>>VSD Nuit</option>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td bgcolor="#c7e048" width="13%" class="Libelle">
								&nbsp; Inspecteur qualit� :
							</td>
							<td bgcolor="#c7e048" width="20%" colspan="3">
								<select name="IQ">
									<option name="" value=""></option>
									<?php
									$req="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS NomPrenom ";
									$req.="FROM new_rh_etatcivil LEFT JOIN sp_acces ON new_rh_etatcivil.Id=sp_acces.Id_Personne WHERE sp_acces.Id_Prestation=842 AND SUBSTR(sp_acces.Droit,5,1)=1 ORDER BY NomPrenom;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowIQ=mysqli_fetch_array($result)){
											$selected="";
											if($row['Id_QUALITE'] <> 0){
												if($row['Id_QUALITE'] == $rowIQ['Id']){$selected="selected";}
											}
											else{
												if(substr($_SESSION['DroitSP'],4,1)=='1' && $_SESSION['Id_PersonneSP'] == $rowIQ['Id']){$selected="selected";}
											}
											echo "<option name='".$rowIQ['Id']."' value='".$rowIQ['Id']."' ".$selected.">".$rowIQ['NomPrenom']."</option>";
										}
									}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#c7e048">&nbsp; Statut QUALITE : </td>
							<td width="20%" bgcolor="#c7e048">
								<div id="statutsQualite">
									<select id="statutQualite" name="statutQualite" onchange="Recharge_StatutQualite();" <?php echo $disabledIQNew;?>>
										<option name="" value=""></option>
										<?php
										$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation=842 AND TypeStatut='Q' ORDER BY Id;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											$i=0;
											while($rowStatut=mysqli_fetch_array($result)){
												echo "<script>Liste_Statut[".$i."] = new Array('".$rowStatut['Id']."');</script>\n";
												if($row['Id_StatutPROD']=="TERA" || $row['Id_StatutPROD']=="REWORK" || $row['SaisieQualite']==1 ){
													$selected = "";
													if($row['Id_StatutQUALITE']==$rowStatut['Id']){$selected="selected";}
													echo "<option name='".$rowStatut['Id']."' value='".$rowStatut['Id']."' ".$selected.">".$rowStatut['Id']."</option>";
												}
												elseif($row['Id_StatutPROD']=="TFS"){
													if($rowStatut['Id']=="TVS"){
														$selected = "";
														if($row['Id_StatutQUALITE']==$rowStatut['Id']){$selected="selected";}
														echo "<option name='".$rowStatut['Id']."' value='".$rowStatut['Id']."' ".$selected.">".$rowStatut['Id']."</option>";
													}
												}
												$i+=1;
											}
										}
										?>
									</select>
								</div>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td width="26%" class="Libelle" bgcolor="#c7e048">&nbsp; Retour QUALITE : <a style="text-decoration:none;" href="javascript:OuvreDef();"><img id="img" src="../../../Images/aide2.gif" alt="aide" title="aide"></a></td>
							<td width="20%" bgcolor="#c7e048">
								<div id='retourQ'>
								<select id="retourQualite" name="retourQualite" <?php echo $disabledIQNew;?>>
									<option name="0" value="0"></option>
									<?php
										$req="SELECT Id, Libelle, Id_Statut, Supprime FROM sp_olwretour ORDER BY Libelle;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($rowRetour=mysqli_fetch_array($result)){
												if($row['Id_StatutQUALITE']==$rowRetour['Id_Statut']){
													$selected = "";
													if($row['Id_RetourQUALITE']==$rowRetour['Id']){$selected="selected";}
													if($row['Id_RetourQUALITE']==$rowRetour['Id'] || $rowRetour['Supprime']==0){
														echo "<option name='".$rowRetour['Id']."' value='".$rowRetour['Id']."' ".$selected.">".$rowRetour['Libelle']."</option>";
													}
												}
											}
										}
									?>
								</select>
								</div>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#c7e048" valign="center">&nbsp; Commentaire QUALITE : </td>
							<td width="20%" bgcolor="#c7e048">
								<textarea id="commentaireQualite" name="commentaireQualite" rows="5" cols="40" style="resize:none;" <?php echo $readIQ;?>><?php echo stripslashes($row['CommentaireQUALITE']); ?></textarea>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
					</table>
				</td>
				<td colspan="2" width="33%" valign="top">
					<table width="100%" id="tableIngredient" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="4" colspan="2" align="center" style="font-size:14px;font-weight:bold;" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width='35%' class="Libelle" valign="center">
								<table width="100%" cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor="#e4e7f" colspan='2'><i>&nbsp; Ajouter les ingr�dients :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; Ingr�dient : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<select id="RefIngredient" name="RefIngredient" onkeypress="if(event.keyCode == 13)AjouterIngredient()" style="width:130px;">
												<?php
												echo"<option name='0' value='0'></option>";
												$req="SELECT Id, Libelle FROM sp_olwingredient WHERE Id_Prestation=842 AND Supprime=false ORDER BY Libelle;";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												if ($nbResulta>0){
													$i=0;
													while($rowIngredient=mysqli_fetch_array($result)){
														echo "<option name='".$rowIngredient['Id']."' value='".$rowIngredient['Id']."'>".$rowIngredient['Libelle']."</option>";
														echo "<script>Liste_Ingredient[".$i."] = new Array('".$rowIngredient['Id']."','".addslashes($rowIngredient['Libelle'])."');</script>\n";
														$i+=1;
													}
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;N� lot : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" id="numLot" onkeypress="if(event.keyCode == 13)AjouterIngredient()" name="numLot" size="8" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor="#e4e7f0">&nbsp;Date p�remption : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" id="datePeremption"  onkeypress="if(event.keyCode == 13)AjouterIngredient()" name="datePeremption" size="8" type="date" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor="#e4e7f0">&nbsp;Coeff. Hygrom�trique : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" onkeypress="if(event.keyCode == 13)AjouterIngredient()" id="coeffH" name="coeffH" size="8" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
										<?php
											if(substr($_SESSION['DroitSP'],1,1)=='1'){
										?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterIngredient()'>&nbsp;Ajouter&nbsp;</a>
										<?php
											}
										?>	
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='65%' valign='top'>
								<table id="tab_Ingredient" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle">Ingr�dient</td><td class="Libelle">N� lot</td><td class="Libelle">Date</td><td class="Libelle">Coeff.</td></tr>
									<?php
										$listeIngredient="";
										$req="SELECT Id_Ingredient,NumLot,DatePeremption,CoeffHydrometrique,";
										$req.="(SELECT sp_olwingredient.Libelle FROM sp_olwingredient WHERE sp_olwingredient.Id=sp_olwfi_ingredient.Id_Ingredient) AS Ingredient ";
										$req.="FROM sp_olwfi_ingredient WHERE Id_FI=".$FI." ORDER BY Ingredient;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($rowIngredient=mysqli_fetch_array($result)){
												$btn="";
												if(substr($_SESSION['DroitSP'],1,1)=='1'){
													$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerIngredient('".$rowIngredient['Id_Ingredient']."_".$rowIngredient['NumLot']."_".AfficheDateFR($rowIngredient['DatePeremption'])."_".$rowIngredient['CoeffHydrometrique']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
												}
												echo "<tr id='".$rowIngredient['Id_Ingredient']."_".$rowIngredient['NumLot']."_".AfficheDateFR($rowIngredient['DatePeremption'])."_".$rowIngredient['CoeffHydrometrique']."'>";
												echo "<td>".$rowIngredient['Ingredient']."</td><td>".$rowIngredient['NumLot']."</td>";
												echo "<td>".AfficheDateFR($rowIngredient['DatePeremption'])."</td><td>".$rowIngredient['CoeffHydrometrique']."</td><td>".$btn."</td></tr>";
												$listeIngredient.=$rowIngredient['Id_Ingredient']."_".$rowIngredient['NumLot']."_".AfficheDateFR($rowIngredient['DatePeremption'])."_".$rowIngredient['CoeffHydrometrique'].";";
											}
										}
									?>
								</table>
							</td>
						</tr>
						<tr><td height="8" colspan="2" bgcolor="#dbeef9"></td></tr>
					</table>
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="10" colspan="2"></td></tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width='35%' class="Libelle" valign="center">
								<table width="100%" cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor="#e4e7f0" colspan='2'><i>&nbsp; Ajouter les ECME :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;R�f�rence : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" id="reference" name="reference" onkeypress="if(event.keyCode == 13)AjouterECME()" size="15" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
										<?php
											if(substr($_SESSION['DroitSP'],1,1)=='1'){
										?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterECME()'>&nbsp;Ajouter&nbsp;</a>
										<?php
											}
										?>	
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='65%' valign='top'>
								<table id="tab_ECME" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle">ECME</td></tr>
									<?php
										$listeECME="";
										$req="SELECT ECME FROM sp_olwfi_ecme WHERE Id_FI=".$FI." ORDER BY ECME;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($rowECME=mysqli_fetch_array($result)){
												$btn="";
												if(substr($_SESSION['DroitSP'],1,1)=='1'){
													$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerECME('".$rowECME['ECME']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
												}
												echo "<tr id='".$rowECME['ECME']."'>";
												echo "<td>".$rowECME['ECME']."</td><td>".$btn."</td></tr>";
												$listeECME.=$rowECME['ECME'].";";
											}
										}
									?>
								</table>
							</td>
						</tr>
						<tr><td height="8" colspan="2" bgcolor="#dbeef9"></td></tr>
					</table>
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="10" colspan="2"></td></tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width='35%' class="Libelle" valign="top">
								<table width="100%" cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor="#e4e7f0"><i>&nbsp; Ajouter les proc�d�s sp�ciaux :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;R�f�rence : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<select id="RefAIPI" name="RefAIPI" onkeypress="if(event.keyCode == 13)AjouterAIPIS()" style="width:130px;">
												<?php
												echo"<option name='0' value='0'></option>";
												$req="SELECT new_competences_qualification.Id,new_competences_qualification.Libelle ";
												$req.="FROM new_competences_prestation_qualification ";
												$req.="LEFT JOIN new_competences_qualification ";
												$req.="ON new_competences_prestation_qualification.Id_Qualification = new_competences_qualification.Id ";
												$req.="WHERE new_competences_prestation_qualification.Id_Prestation=842 ";
												$req.="AND (new_competences_qualification.Id_Categorie_Qualification=112 OR new_competences_qualification.Id_Categorie_Qualification=113) ORDER BY Libelle ";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												if ($nbResulta>0){
													$i=0;
													while($rowAIPI=mysqli_fetch_array($result)){
														echo "<option name='".$rowAIPI['Id']."' value='".$rowAIPI['Id']."'>".$rowAIPI['Libelle']."</option>";
														echo "<script>Liste_AIPI[".$i."] = new Array('".$rowAIPI['Id']."','".addslashes($rowAIPI['Libelle'])."');</script>\n";
														$i+=1;
													}
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' style='height:25px;' valign='center'>
										<?php
											if(substr($_SESSION['DroitSP'],1,1)=='1'){
										?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterAIPIS()'>&nbsp;Ajouter&nbsp;</a>
										<?php
											}
										?>	
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='65%' valign='top'>
								<table id="tab_AIPI" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle" align="center">Proc�d�s sp�ciaux</td></tr>
									<?php
										$listeAIPI="";
										$req="SELECT Id_Qualification, ";
										$req.="(SELECT new_competences_qualification.Libelle ";
										$req.="FROM new_competences_qualification WHERE new_competences_qualification.Id=sp_olwfi_aipi.Id_Qualification) AS Libelle ";
										$req.="FROM sp_olwfi_aipi WHERE Id_FI=".$FI." ORDER BY Libelle;";
										
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											while($rowAIPI=mysqli_fetch_array($result)){
												$btn="";
												if(substr($_SESSION['DroitSP'],1,1)=='1'){
													$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerAIPIS('".$rowAIPI['Id_Qualification']."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
												}
												echo "<tr id='".$rowAIPI['Id_Qualification']."'>";
												echo "<td>".$rowAIPI['Libelle']."</td><td>".$btn."</td></tr>";
												$listeAIPI.=$rowAIPI['Id_Qualification'].";";
											}
										}
									?>
								</table>
							</td>
						</tr>
						<tr><td height="8" colspan="2" bgcolor="#dbeef9"></td></tr>
					</table>
				</td>
			</tr>
			<tr style="display:none;"><td><input id="travailEffectue" name="travailEffectue" value="<?php echo $listeCompagnon;?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="ECME" name="ECME" value="<?php echo $listeECME;?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id='lesAIPI' name='lesAIPI' value="<?php echo $listeAIPI;?>"  readonly='readonly'></td></tr>
			<tr style="display:none;"><td><input id="Ingredient" name="Ingredient" value="<?php echo $listeIngredient;?>"  readonly="readonly"></td></tr>
			<tr style="display:none;"><td height="4" colspan="6" bgcolor="#dbeef9"></td></tr>
		</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<?php
		if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
		?>
		<td colspan="6" align="center">
			<div id="Enregistrer">
			</div>	
			<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="AfficherSubmit()">
		</td>
		<?php
		}
		?>
	
	</tr>
	<tr><td colspan="10">&nbsp;&nbsp;&nbsp;&nbsp;<img src='../../../Images/etoile.png' width='8' height='8' border='0'> Informations obligatoires � remplir</td></tr>
	<tr><td height="4"></td></tr>
</table>
</form>
<?php
	if($row['SaisieQualite']==1){
	echo "<script>ModifQUALITE()</script>";
	}
//	mysqli_free_result($resultDroits);	// Lib�ration des r�sultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
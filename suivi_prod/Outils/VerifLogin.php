<?php
	require("Connexioni.php");
	$tableau = array(); 
	
	session_cache_limiter('private');
	
	/* Configure le délai d'expiration à 30 minutes */
	session_cache_expire(30);
	session_start();

	if($_SERVER['SERVER_NAME']=="127.0.0.1" || $_SERVER['SERVER_NAME']=="localhost" 
	|| $_SERVER['SERVER_NAME']=="192.168.20.3" || $_SERVER['SERVER_NAME']=="frcodc0001"
	|| $_SERVER['SERVER_NAME']=="172.30.48.42" || $_SERVER['SERVER_NAME']=="172.30.48.43"){
		$_SESSION['HTTP']="http";
	}
	else{
		$_SESSION['HTTP']="https";
	}

		// Exécuter des requêtes SQL
		if($_POST["prestation"]=="TBWP"){
			$result=mysqli_query($bdd,"SELECT Nom,Prenom,Email,Id FROM new_rh_etatcivil WHERE LoginSP='".$_POST["login"]."' AND MdpSP='".$_POST["motdepasse"]."'");
			$nbenreg=mysqli_num_rows($result);
			if($_POST["login"] <> "" and $_POST["motdepasse"] <> ""){
				if($nbenreg==1){
					//Creation des variables de session
					$row=mysqli_fetch_array($result);
					
					if($_POST["prestation"]=="TBWP"){
						$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=255 ");
					}
					
					$nbAcces=mysqli_num_rows($resultAcces);
					if($nbAcces>0){
						$rowDroit=mysqli_fetch_array($resultAcces);
						
						$_SESSION['LogSP']=$_POST["login"];
						$_SESSION['MdpSP']=$_POST["motdepasse"];
						$_SESSION['PrestationSP']=$_POST["prestation"];
						$_SESSION['NomSP']=$row[0];
						$_SESSION['PrenomSP']=$row[1];
						$_SESSION['EmailSP']=$row[2];
						$_SESSION['Id_PersonneSP']=$row['Id'];
						$_SESSION['DroitSP']=$rowDroit['Droit'];
						
						//Variables de sessions pour la recherche
						$_SESSION['MSN']="";
						$_SESSION['NumDossier']="";
						$_SESSION['NumIC']="";
						$_SESSION['Section']="";
						$_SESSION['Zone']="";
						$_SESSION['CreateurDossier']="";
						$_SESSION['CreateurIC']="";
						$_SESSION['CE']="";
						$_SESSION['IQ']="";
						$_SESSION['StatutIC']="";
						$_SESSION['Vacation']="";
						$_SESSION['Urgence']="";
						$_SESSION['Titre']="";
						$_SESSION['DateDebut']="";
						$_SESSION['DateFin']="";
						$_SESSION['SansDate']="";
						$_SESSION['EtatIC']="";
						$_SESSION['Pole_FI']="";
						$_SESSION['Competence']="";
						$_SESSION['Stamp']="";
						$_SESSION['OF']="";
						$_SESSION['PNE']="";
						$_SESSION['DateDebutQUALITE']="";
						$_SESSION['DateFinQUALITE']="";
						$_SESSION['SansDateQUALITE']="";
						$_SESSION['VacationQUALITE']="";
						
						$_SESSION['MSN2']="";
						$_SESSION['NumDossier2']="";
						$_SESSION['NumIC2']="";
						$_SESSION['Section2']="";
						$_SESSION['Zone2']="";
						$_SESSION['CreateurDossier2']="";
						$_SESSION['CreateurIC2']="";
						$_SESSION['CE2']="";
						$_SESSION['IQ2']="";
						$_SESSION['StatutIC2']="";
						$_SESSION['Vacation2']="";
						$_SESSION['Urgence2']="";
						$_SESSION['Titre2']="";
						$_SESSION['DateDebut2']="";
						$_SESSION['DateFin2']="";
						$_SESSION['SansDate2']="";
						$_SESSION['EtatIC2']="";
						$_SESSION['Pole_FI2']="";
						$_SESSION['Competence2']="";
						$_SESSION['Stamp2']="";
						$_SESSION['PNE2']="";
						$_SESSION['DateDebutQUALITE2']="";
						$_SESSION['DateFinQUALITE2']="";
						$_SESSION['SansDateQUALITE2']="";
						$_SESSION['VacationQUALITE2']="";
						
						$_SESSION['ModeFiltre']="";
						$_SESSION['Page']="0";
						
						//Variable session Tri
						$_SESSION['TriMSN']="ASC";
						$_SESSION['TriOF']="ASC";
						$_SESSION['TriSection']="";
						$_SESSION['TriZone']="";
						$_SESSION['TriUrgence']="";
						$_SESSION['TriPriorite']="";
						$_SESSION['TriTitre']="";
						$_SESSION['TriDateIntervention']="DESC";
						$_SESSION['TriVacation']="";
						$_SESSION['TriFI']="";
						$_SESSION['TriTravailRealise']="";
						$_SESSION['TriStatutProd']="";
						$_SESSION['TriStatutQualite']="";
						$_SESSION['TriCreateurDossier']="";
						$_SESSION['TriEtatIC']="";
						$_SESSION['TriTAI']="";
						$_SESSION['TriTypeTravail']="";
						$_SESSION['TriCT']="";
						$_SESSION['TriGeneral']="DateIntervention DESC,MSN ASC,Reference ASC,";

						//PNE
						$_SESSION['FormA']="";
						$_SESSION['Pole']="";
						$_SESSION['Poste']="";
						$_SESSION['MSN_PNE']="";
						$_SESSION['Zone_PNE']="";
						$_SESSION['Compagnon']="";
						$_SESSION['NumEIC']="";
						$_SESSION['DateDebutPNE']="";
						$_SESSION['DateFinPNE']="";
						$_SESSION['SansDatePNE']="";
						$_SESSION['VacationPNE']="";
						$_SESSION['Id_CreateurPNE']="";
						
						$_SESSION['FormA2']="";
						$_SESSION['Pole2']="";
						$_SESSION['Poste2']="";
						$_SESSION['MSN_PNE2']="";
						$_SESSION['Zone_PNE2']="";
						$_SESSION['Compagnon2']="";
						$_SESSION['NumEIC2']="";
						$_SESSION['DateDebutPNE2']="";
						$_SESSION['DateFinPNE2']="";
						$_SESSION['SansDatePNE2']="";
						$_SESSION['VacationPNE2']="";
						$_SESSION['Id_CreateurPNE2']="";
						
						$_SESSION['TriFormA']="ASC";
						$_SESSION['TriPole']="";
						$_SESSION['TriPoste']="";
						$_SESSION['TriMSN_PNE']="ASC";
						$_SESSION['TriZone_PNE']="";
						$_SESSION['TriCompagnon']="";
						$_SESSION['TriNumEIC']="";
						$_SESSION['TriDateInterventionPNE']="DESC";
						$_SESSION['TriVacationPNE']="";
						$_SESSION['TriId_CreateurPNE']="";
						$_SESSION['TriNbRetouche']="";
						$_SESSION['TriCommentairePNE']="";
						$_SESSION['TriGeneralPNE']="DateIntervention DESC,MSN ASC,NumFormA ASC,";
						
						$_SESSION['ModeFiltre2']="";
						$_SESSION['Page2']="0";
						
						//NC
						$_SESSION['MSN_NC']="";
						$_SESSION['Num_NC']="";
						$_SESSION['WO_S01']="";
						$_SESSION['TypeDefaut']="";
						$_SESSION['ImputationAAA']="";
						$_SESSION['DateDebutNC']="";
						$_SESSION['DateFinNC']="";
						$_SESSION['Id_Createur']="";

						$_SESSION['MSN_NC2']="";
						$_SESSION['Num_NC2']="";
						$_SESSION['WO_S012']="";
						$_SESSION['TypeDefaut2']="";
						$_SESSION['ImputationAAA2']="";
						$_SESSION['DateDebutNC2']="";
						$_SESSION['DateFinNC2']="";
						$_SESSION['Id_Createur2']="";

						$_SESSION['TriMSN_NC']="";
						$_SESSION['TriNum_NC']="";
						$_SESSION['TriWO_S01']="";
						$_SESSION['TriTypeDefaut']="";
						$_SESSION['TriImputationAAA']="";
						$_SESSION['TriDateCreationNC']="";
						$_SESSION['TriId_Createur']="";
						$_SESSION['TriGeneralNC']="";
						
						//Extract
						$_SESSION['Extract_MSN']="";
						$_SESSION['Extract_Du']="";
						$_SESSION['Extract_Au']="";
						$_SESSION['Extract_Vacation']="";
						$_SESSION['Extract_Statut']="";
						$_SESSION['Extract_Pole']="";
						$_SESSION['Extract_Urgence']="";
						$_SESSION['Extract_Zone']="";
						$_SESSION['Extract_SansDate']="";
						
						$_SESSION['Extract_MSN2']="";
						$_SESSION['Extract_Du2']="";
						$_SESSION['Extract_Au2']="";
						$_SESSION['Extract_Vacation2']="";
						$_SESSION['Extract_Statut2']="";
						$_SESSION['Extract_Pole2']="";
						$_SESSION['Extract_Urgence2']="";
						$_SESSION['Extract_Zone2']="";
						$_SESSION['Extract_SansDate2']="";
						
						$_SESSION['ModeFiltreNC']="";
						$_SESSION['PageNC']="0";
						
						//Indicateurs
						$_SESSION['Indicateur_MSN']="";
						$_SESSION['Indicateur_Vacation']="";
						$_SESSION['Indicateur_Du']="";
						$_SESSION['Indicateur_Au']="";
						$_SESSION['Indicateur_Pole']="";
						
						$_SESSION['Indicateur_MSN2']="";
						$_SESSION['Indicateur_Vacation2']="";
						$_SESSION['Indicateur_Du2']="";
						$_SESSION['Indicateur_Au2']="";
						$_SESSION['Indicateur_Pole2']="";
						
						$_SESSION['EXTRACT_INGIngredient']="";
						$_SESSION['EXTRACT_INGNumLot']="";
						$_SESSION['EXTRACT_INGDatePeremption']="";
						$_SESSION['EXTRACT_INGDateTERA']="";
						$_SESSION['EXTRACT_INGDateTERC']="";
						$_SESSION['EXTRACT_INGDu']="";
						$_SESSION['EXTRACT_INGAu']="";
						$_SESSION['EXTRACT_INGMSN']="";
						$_SESSION['EXTRACT_INGDossier']="";
						
						$_SESSION['EXTRACT_INGIngredient2']="";
						$_SESSION['EXTRACT_INGNumLot2']="";
						$_SESSION['EXTRACT_INGDatePeremption2']="";
						$_SESSION['EXTRACT_INGDateTERA2']="";
						$_SESSION['EXTRACT_INGDateTERC2']="";
						$_SESSION['EXTRACT_INGDu2']="";
						$_SESSION['EXTRACT_INGAu2']="";
						$_SESSION['EXTRACT_INGMSN2']="";
						$_SESSION['EXTRACT_INGDossier2']="";
						
						$_SESSION['EXTRACT_ECMEMetier']="";
						$_SESSION['EXTRACT_ECMEReference']="";
						$_SESSION['EXTRACT_ECMEType']="";
						$_SESSION['EXTRACT_ECMEDateTERA']="";
						$_SESSION['EXTRACT_ECMEDateTERC']="";
						$_SESSION['EXTRACT_ECMEDu']="";
						$_SESSION['EXTRACT_ECMEAu']="";
						$_SESSION['EXTRACT_ECMEMSN']="";
						$_SESSION['EXTRACT_ECMEDossier']="";
						
						$_SESSION['EXTRACT_ECMEMetier2']="";
						$_SESSION['EXTRACT_ECMEReference2']="";
						$_SESSION['EXTRACT_ECMEType2']="";
						$_SESSION['EXTRACT_ECMEDateTERA2']="";
						$_SESSION['EXTRACT_ECMEDateTERC2']="";
						$_SESSION['EXTRACT_ECMEDu2']="";
						$_SESSION['EXTRACT_ECMEAu2']="";
						$_SESSION['EXTRACT_ECMEMSN2']="";
						$_SESSION['EXTRACT_ECMEDossier2']="";
						
						$_SESSION['EXTRACT_ECMECLIENTClient']="";
						$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERA']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERC']="";
						$_SESSION['EXTRACT_ECMECLIENTDu']="";
						$_SESSION['EXTRACT_ECMECLIENTAu']="";
						$_SESSION['EXTRACT_ECMECLIENTMSN']="";
						$_SESSION['EXTRACT_ECMECLIENTDossier']="";
						
						$_SESSION['EXTRACT_ECMECLIENTClient2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERA2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERC2']="";
						$_SESSION['EXTRACT_ECMECLIENTDu2']="";
						$_SESSION['EXTRACT_ECMECLIENTAu2']="";
						$_SESSION['EXTRACT_ECMECLIENTMSN2']="";
						$_SESSION['EXTRACT_ECMECLIENTDossier2']="";
						
						$_SESSION['EXTRACT_PSCompagnon']="";
						$_SESSION['EXTRACT_PSIQ']="";
						$_SESSION['EXTRACT_PSReference']="";
						$_SESSION['EXTRACT_PSDateTERA']="";
						$_SESSION['EXTRACT_PSDateTERC']="";
						$_SESSION['EXTRACT_PSDu']="";
						$_SESSION['EXTRACT_PSAu']="";
						$_SESSION['EXTRACT_PSMSN']="";
						$_SESSION['EXTRACT_PSDossier']="";
						
						$_SESSION['EXTRACT_PSCompagnon2']="";
						$_SESSION['EXTRACT_PSIQ2']="";
						$_SESSION['EXTRACT_PSReference2']="";
						$_SESSION['EXTRACT_PSDateTERA2']="";
						$_SESSION['EXTRACT_PSDateTERC2']="";
						$_SESSION['EXTRACT_PSDu2']="";
						$_SESSION['EXTRACT_PSAu2']="";
						$_SESSION['EXTRACT_PSMSN2']="";
						$_SESSION['EXTRACT_PSDossier2']="";
						
						$resultPlateforme=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne='".$row[3]."'");
						$nbenregPlateforme=mysqli_num_rows($resultPlateforme);
						if($nbenregPlateforme>0)
						{
							while($donnees=mysqli_fetch_array($resultPlateforme))
							{
								array_push($tableau, $donnees[0]);
							}
						}
						$_SESSION['Id_Plateformes'] = $tableau;
						echo "<html>";
						
						if($_SESSION['NomSP']=="" || $_SESSION['PrenomSP']=="" || $_SESSION['LogSP']=="" || $_SESSION['MdpSP']=="")
						{echo "<body onload='window.location.top=\"".$chemin."/index.php?Cnx=CKIES\";'>";}
						else{
							if($_SESSION['MdpSP']=="aaa01"){echo "<body onload='window.location.href=\"".$chemin."/MotDePasse.php\";'>";}
							else {echo "<body onload='window.location.href=\"".$chemin."/Accueil.php\";'>";}
						}
					}
					else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD2\";'>";
						}
				}
				else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";
				}
			}
			else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			mysqli_free_result($result);	// Libération des résultats
			mysqli_close($bdd);			// Fermeture de la connexion
		}
		elseif($_POST["prestation"]=="AEWP" || $_POST["prestation"]=="CAALR" || $_POST["prestation"]=="CAATR" || $_POST["prestation"]=="TEST_PREPA"){
			$result=mysqli_query($bdd,"SELECT Nom,Prenom,Email,Id FROM new_rh_etatcivil WHERE LoginSP='".$_POST["login"]."' AND MdpSP='".$_POST["motdepasse"]."'");
			$nbenreg=mysqli_num_rows($result);
			if($_POST["login"] <> "" and $_POST["motdepasse"] <> ""){
				if($nbenreg==1){
					//Creation des variables de session
					$row=mysqli_fetch_array($result);
					
					if($_POST["prestation"]=="AEWP"){
						$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=237 ");
					}
					elseif($_POST["prestation"]=="CAALR"){
						$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=418 ");
					}
					elseif($_POST["prestation"]=="CAATR"){
						$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=79 ");
					}
					elseif($_POST["prestation"]=="TEST_PREPA"){
						$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=-15 ");
					}
					
					$nbAcces=mysqli_num_rows($resultAcces);
					if($nbAcces>0){
						$rowDroit=mysqli_fetch_array($resultAcces);

						$_SESSION['LogSP']=$_POST["login"];
						$_SESSION['MdpSP']=$_POST["motdepasse"];
						$_SESSION['PrestationSP']=$_POST["prestation"];
						$_SESSION['NomSP']=$row[0];
						$_SESSION['PrenomSP']=$row[1];
						$_SESSION['EmailSP']=$row[2];
						$_SESSION['Id_PersonneSP']=$row['Id'];
						$_SESSION['DroitSP']=$rowDroit['Droit'];
						
						//Variables de sessions pour la recherche
						$_SESSION['MSN']="";
						$_SESSION['NumNC']="";
						$_SESSION['NumAM']="";
						$_SESSION['NumDossier']="";
						$_SESSION['NumPointFolio']="";
						$_SESSION['NumDERO']="";
						$_SESSION['NumDA']="";
						$_SESSION['NumIC']="";
						$_SESSION['Client']="";
						$_SESSION['Imputation']="";
						$_SESSION['Zone']="";
						$_SESSION['Localisation']="";
						$_SESSION['Priorite']="";
						$_SESSION['Section']="";
						$_SESSION['CreateurDossier']="";
						$_SESSION['CreateurIC']="";
						$_SESSION['CE']="";
						$_SESSION['IQ']="";
						$_SESSION['StatutPrepa']="";
						$_SESSION['StatutIC']="";
						$_SESSION['Vacation']="";
						$_SESSION['TravailRealise']="";
						$_SESSION['DateDebut']="";
						$_SESSION['DateFin']="";
						$_SESSION['SansDate']="";
						$_SESSION['EtatIC']="";
						$_SESSION['Poste']="";
						$_SESSION['Stamp']="";
						$_SESSION['DateDebutQUALITE']="";
						$_SESSION['DateFinQUALITE']="";
						$_SESSION['SansDateQUALITE']="";
						$_SESSION['VacationQUALITE']="";
						$_SESSION['Programme']="";
						$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('archive','Non')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
						$_SESSION['Archive']="Non".$btn;
						
						$_SESSION['MSN2']="";
						$_SESSION['NumNC2']="";
						$_SESSION['NumAM2']="";
						$_SESSION['NumDossier2']="";
						$_SESSION['NumPointFolio2']="";
						$_SESSION['NumDERO2']="";
						$_SESSION['NumDA2']="";
						$_SESSION['NumIC2']="";
						$_SESSION['Client2']="";
						$_SESSION['Imputation2']="";
						$_SESSION['Zone2']="";
						$_SESSION['Localisation2']="";
						$_SESSION['Priorite2']="";
						$_SESSION['Section2']="";
						$_SESSION['CreateurDossier2']="";
						$_SESSION['CreateurIC2']="";
						$_SESSION['CE2']="";
						$_SESSION['IQ2']="";
						$_SESSION['StatutPrepa2']="";
						$_SESSION['StatutIC2']="";
						$_SESSION['Vacation2']="";
						$_SESSION['TravailRealise2']="";
						$_SESSION['DateDebut2']="";
						$_SESSION['DateFin2']="";
						$_SESSION['SansDate2']="";
						$_SESSION['EtatIC2']="";
						$_SESSION['Poste2']="";
						$_SESSION['Stamp2']="";
						$_SESSION['DateDebutQUALITE2']="";
						$_SESSION['DateFinQUALITE2']="";
						$_SESSION['SansDateQUALITE2']="";
						$_SESSION['VacationQUALITE2']="";
						$_SESSION['Programme2']="";
						$_SESSION['Archive2']="Non;";
						
						$_SESSION['ModeFiltre']="oui";
						$_SESSION['Page']="0";
						
						//Variable session Tri
						$_SESSION['TriMSN']="";
						$_SESSION['TriProgramme']="";
						$_SESSION['TriPointFolio']="";
						$_SESSION['TriOF']="";
						$_SESSION['TriNC']="";
						$_SESSION['TriAM']="";
						$_SESSION['TriDERO']="";
						$_SESSION['TriDA']="";
						$_SESSION['TriTypeTravail']="";
						$_SESSION['TriDateIntervention']="ASC";
						$_SESSION['TriVacation']="ASC";
						$_SESSION['TriZone']="";
						$_SESSION['TriTravailRealise']="";
						$_SESSION['TriPoste']="";
						$_SESSION['TriStatutProd']="";
						$_SESSION['TriStatutQualite']="";
						$_SESSION['TriRetourPROD']="";
						$_SESSION['TriRetourQUALITE']="";
						$_SESSION['TriGeneral']="DateIntervention DESC,Vacation2 ASC,";

						//Indicateurs
						$_SESSION['Indicateur_MSN']="";
						$_SESSION['Indicateur_Vacation']="";
						$_SESSION['Indicateur_Du']="";
						$_SESSION['Indicateur_Au']="";
						$_SESSION['Indicateur_Poste']="";
						$_SESSION['Indicateur_Client']="";
						
						$_SESSION['Indicateur_MSN2']="";
						$_SESSION['Indicateur_Vacation2']="";
						$_SESSION['Indicateur_Du2']="";
						$_SESSION['Indicateur_Au2']="";
						$_SESSION['Indicateur_Poste2']="";
						$_SESSION['Indicateur_Client2']="";
						
						$_SESSION['EXTRACT_INGIngredient']="";
						$_SESSION['EXTRACT_INGNumLot']="";
						$_SESSION['EXTRACT_INGDatePeremption']="";
						$_SESSION['EXTRACT_INGDateTERA']="";
						$_SESSION['EXTRACT_INGDateTERC']="";
						$_SESSION['EXTRACT_INGDu']="";
						$_SESSION['EXTRACT_INGAu']="";
						$_SESSION['EXTRACT_INGMSN']="";
						$_SESSION['EXTRACT_INGDossier']="";
						
						$_SESSION['EXTRACT_INGIngredient2']="";
						$_SESSION['EXTRACT_INGNumLot2']="";
						$_SESSION['EXTRACT_INGDatePeremption2']="";
						$_SESSION['EXTRACT_INGDateTERA2']="";
						$_SESSION['EXTRACT_INGDateTERC2']="";
						$_SESSION['EXTRACT_INGDu2']="";
						$_SESSION['EXTRACT_INGAu2']="";
						$_SESSION['EXTRACT_INGMSN2']="";
						$_SESSION['EXTRACT_INGDossier2']="";
						
						$_SESSION['EXTRACT_ECMEMetier']="";
						$_SESSION['EXTRACT_ECMEReference']="";
						$_SESSION['EXTRACT_ECMEType']="";
						$_SESSION['EXTRACT_ECMEDateTERA']="";
						$_SESSION['EXTRACT_ECMEDateTERC']="";
						$_SESSION['EXTRACT_ECMEDu']="";
						$_SESSION['EXTRACT_ECMEAu']="";
						$_SESSION['EXTRACT_ECMEMSN']="";
						$_SESSION['EXTRACT_ECMEDossier']="";
						
						$_SESSION['EXTRACT_ECMEMetier2']="";
						$_SESSION['EXTRACT_ECMEReference2']="";
						$_SESSION['EXTRACT_ECMEType2']="";
						$_SESSION['EXTRACT_ECMEDateTERA2']="";
						$_SESSION['EXTRACT_ECMEDateTERC2']="";
						$_SESSION['EXTRACT_ECMEDu2']="";
						$_SESSION['EXTRACT_ECMEAu2']="";
						$_SESSION['EXTRACT_ECMEMSN2']="";
						$_SESSION['EXTRACT_ECMEDossier2']="";
						
						$_SESSION['EXTRACT_ECMECLIENTClient']="";
						$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERA']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERC']="";
						$_SESSION['EXTRACT_ECMECLIENTDu']="";
						$_SESSION['EXTRACT_ECMECLIENTAu']="";
						$_SESSION['EXTRACT_ECMECLIENTMSN']="";
						$_SESSION['EXTRACT_ECMECLIENTDossier']="";
						
						$_SESSION['EXTRACT_ECMECLIENTClient2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERA2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERC2']="";
						$_SESSION['EXTRACT_ECMECLIENTDu2']="";
						$_SESSION['EXTRACT_ECMECLIENTAu2']="";
						$_SESSION['EXTRACT_ECMECLIENTMSN2']="";
						$_SESSION['EXTRACT_ECMECLIENTDossier2']="";
						
						$_SESSION['EXTRACT_PSCompagnon']="";
						$_SESSION['EXTRACT_PSIQ']="";
						$_SESSION['EXTRACT_PSReference']="";
						$_SESSION['EXTRACT_PSDateTERA']="";
						$_SESSION['EXTRACT_PSDateTERC']="";
						$_SESSION['EXTRACT_PSDu']="";
						$_SESSION['EXTRACT_PSAu']="";
						$_SESSION['EXTRACT_PSMSN']="";
						$_SESSION['EXTRACT_PSDossier']="";
						
						$_SESSION['EXTRACT_PSCompagnon2']="";
						$_SESSION['EXTRACT_PSIQ2']="";
						$_SESSION['EXTRACT_PSReference2']="";
						$_SESSION['EXTRACT_PSDateTERA2']="";
						$_SESSION['EXTRACT_PSDateTERC2']="";
						$_SESSION['EXTRACT_PSDu2']="";
						$_SESSION['EXTRACT_PSAu2']="";
						$_SESSION['EXTRACT_PSMSN2']="";
						$_SESSION['EXTRACT_PSDossier2']="";
						
						$resultPlateforme=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne='".$row[3]."'");
						$nbenregPlateforme=mysqli_num_rows($resultPlateforme);
						if($nbenregPlateforme>0)
						{
							while($donnees=mysqli_fetch_array($resultPlateforme))
							{
								array_push($tableau, $donnees[0]);
							}
						}
						$_SESSION['Id_Plateformes'] = $tableau;
						echo "<html>";
						
						if($_SESSION['NomSP']=="" || $_SESSION['PrenomSP']=="" || $_SESSION['LogSP']=="" || $_SESSION['MdpSP']=="")
						{echo "<body onload='window.location.top=\"".$chemin."/index.php?Cnx=CKIES\";'>";}
						else{
							if($_SESSION['MdpSP']=="aaa01"){echo "<body onload='window.location.href=\"".$chemin."/MotDePasse.php\";'>";}
							else {echo "<body onload='window.location.href=\"".$chemin."/Accueil.php\";'>";}
						}
					}
					else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD2\";'>";}
				}
				else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			}
			else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			mysqli_free_result($result);	// Libération des résultats
			mysqli_close($bdd);			// Fermeture de la connexion
		}
		elseif($_POST["prestation"]=="OLW" || $_POST["prestation"]=="ZCS-PGC" || $_POST["prestation"]=="ATRRQ"){
			$result=mysqli_query($bdd,"SELECT Nom,Prenom,Email,Id FROM new_rh_etatcivil WHERE LoginSP='".$_POST["login"]."' AND MdpSP='".$_POST["motdepasse"]."'");
			$nbenreg=mysqli_num_rows($result);
			if($_POST["login"] <> "" and $_POST["motdepasse"] <> ""){
				if($nbenreg==1){
					//Creation des variables de session
					$row=mysqli_fetch_array($result);
					
					if($_POST["prestation"]=="OLW"){
						$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=688 ");
					}
					elseif($_POST["prestation"]=="ZCS-PGC"){
						$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=1539 ");
					}
					elseif($_POST["prestation"]=="ATRRQ"){
						$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=576 ");
					}
					
					$nbAcces=mysqli_num_rows($resultAcces);
					if($nbAcces>0){
						$rowDroit=mysqli_fetch_array($resultAcces);
						

						$_SESSION['LogSP']=$_POST["login"];
						$_SESSION['MdpSP']=$_POST["motdepasse"];
						$_SESSION['PrestationSP']=$_POST["prestation"];
						$_SESSION['NomSP']=$row[0];
						$_SESSION['PrenomSP']=$row[1];
						$_SESSION['EmailSP']=$row[2];
						$_SESSION['Id_PersonneSP']=$row['Id'];
						$_SESSION['DroitSP']=$rowDroit['Droit'];
						
						//Variables de sessions pour la recherche
						$_SESSION['MSN']="";
						$_SESSION['Programme']="";
						$_SESSION['NumDossier']="";
						$_SESSION['NumIC']="";
						$_SESSION['Client']="";
						$_SESSION['Zone']="";
						$_SESSION['Priorite']="";
						$_SESSION['Section']="";
						$_SESSION['Titre']="";
						$_SESSION['CreateurDossier']="";
						$_SESSION['CreateurIC']="";
						$_SESSION['CE']="";
						$_SESSION['IQ']="";
						$_SESSION['StatutIC']="";
						$_SESSION['Vacation']="";
						$_SESSION['TravailRealise']="";
						$_SESSION['DateDebut']="";
						$_SESSION['DateFin']="";
						$_SESSION['SansDate']="";
						$_SESSION['EtatIC']="";
						$_SESSION['Poste']="";
						$_SESSION['Stamp']="";
						$_SESSION['DateDebutQUALITE']="";
						$_SESSION['DateFinQUALITE']="";
						$_SESSION['SansDateQUALITE']="";
						$_SESSION['VacationQUALITE']="";
						
						$_SESSION['MSN2']="";
						$_SESSION['Programme2']="";
						$_SESSION['NumDossier2']="";
						$_SESSION['NumIC2']="";
						$_SESSION['Client2']="";
						$_SESSION['Zone2']="";
						$_SESSION['Titre2']="";
						$_SESSION['Priorite2']="";
						$_SESSION['Section2']="";
						$_SESSION['CreateurDossier2']="";
						$_SESSION['CreateurIC2']="";
						$_SESSION['CE2']="";
						$_SESSION['IQ2']="";
						$_SESSION['StatutIC2']="";
						$_SESSION['Vacation2']="";
						$_SESSION['TravailRealise2']="";
						$_SESSION['DateDebut2']="";
						$_SESSION['DateFin2']="";
						$_SESSION['SansDate2']="";
						$_SESSION['EtatIC2']="";
						$_SESSION['Poste2']="";
						$_SESSION['Stamp2']="";
						$_SESSION['DateDebutQUALITE2']="";
						$_SESSION['DateFinQUALITE2']="";
						$_SESSION['SansDateQUALITE2']="";
						$_SESSION['VacationQUALITE2']="";
						
						$_SESSION['ModeFiltre']="";
						$_SESSION['Page']="0";
						
						//Variable session Tri
						$_SESSION['TriMSN']="ASC";
						$_SESSION['TriOF']="ASC";
						$_SESSION['TriNC']="";
						$_SESSION['TriPriorite']="";
						$_SESSION['TriTAI']="";
						$_SESSION['TriTitre']="";
						$_SESSION['TriDateIntervention']="";
						$_SESSION['TriVacation']="";
						$_SESSION['TriNumFI']="";
						$_SESSION['TriClient']="";
						$_SESSION['TriZone']="";
						$_SESSION['TriTravailRealise']="";
						$_SESSION['TriPoste']="";
						$_SESSION['TriStatutProd']="";
						$_SESSION['TriStatutQualite']="";
						$_SESSION['TriGeneral']="MSN ASC,Reference ASC,";

						//Indicateurs
						$_SESSION['Indicateur_MSN']="";
						$_SESSION['Indicateur_Vacation']="";
						$_SESSION['Indicateur_Du']="";
						$_SESSION['Indicateur_Au']="";
						$_SESSION['Indicateur_Poste']="";
						$_SESSION['Indicateur_Client']="";
						
						$_SESSION['Indicateur_MSN2']="";
						$_SESSION['Indicateur_Vacation2']="";
						$_SESSION['Indicateur_Du2']="";
						$_SESSION['Indicateur_Au2']="";
						$_SESSION['Indicateur_Poste2']="";
						$_SESSION['Indicateur_Client2']="";
						
						$_SESSION['EXTRACT_INGIngredient']="";
						$_SESSION['EXTRACT_INGNumLot']="";
						$_SESSION['EXTRACT_INGDatePeremption']="";
						$_SESSION['EXTRACT_INGDateTERA']="";
						$_SESSION['EXTRACT_INGDateTERC']="";
						$_SESSION['EXTRACT_INGDu']="";
						$_SESSION['EXTRACT_INGAu']="";
						$_SESSION['EXTRACT_INGMSN']="";
						$_SESSION['EXTRACT_INGDossier']="";
						
						$_SESSION['EXTRACT_INGIngredient2']="";
						$_SESSION['EXTRACT_INGNumLot2']="";
						$_SESSION['EXTRACT_INGDatePeremption2']="";
						$_SESSION['EXTRACT_INGDateTERA2']="";
						$_SESSION['EXTRACT_INGDateTERC2']="";
						$_SESSION['EXTRACT_INGDu2']="";
						$_SESSION['EXTRACT_INGAu2']="";
						$_SESSION['EXTRACT_INGMSN2']="";
						$_SESSION['EXTRACT_INGDossier2']="";
						
						$_SESSION['EXTRACT_ECMEMetier']="";
						$_SESSION['EXTRACT_ECMEReference']="";
						$_SESSION['EXTRACT_ECMEType']="";
						$_SESSION['EXTRACT_ECMEDateTERA']="";
						$_SESSION['EXTRACT_ECMEDateTERC']="";
						$_SESSION['EXTRACT_ECMEDu']="";
						$_SESSION['EXTRACT_ECMEAu']="";
						$_SESSION['EXTRACT_ECMEMSN']="";
						$_SESSION['EXTRACT_ECMEDossier']="";
						
						$_SESSION['EXTRACT_ECMEMetier2']="";
						$_SESSION['EXTRACT_ECMEReference2']="";
						$_SESSION['EXTRACT_ECMEType2']="";
						$_SESSION['EXTRACT_ECMEDateTERA2']="";
						$_SESSION['EXTRACT_ECMEDateTERC2']="";
						$_SESSION['EXTRACT_ECMEDu2']="";
						$_SESSION['EXTRACT_ECMEAu2']="";
						$_SESSION['EXTRACT_ECMEMSN2']="";
						$_SESSION['EXTRACT_ECMEDossier2']="";
						
						$_SESSION['EXTRACT_ECMECLIENTClient']="";
						$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERA']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERC']="";
						$_SESSION['EXTRACT_ECMECLIENTDu']="";
						$_SESSION['EXTRACT_ECMECLIENTAu']="";
						$_SESSION['EXTRACT_ECMECLIENTMSN']="";
						$_SESSION['EXTRACT_ECMECLIENTDossier']="";
						
						$_SESSION['EXTRACT_ECMECLIENTClient2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERA2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERC2']="";
						$_SESSION['EXTRACT_ECMECLIENTDu2']="";
						$_SESSION['EXTRACT_ECMECLIENTAu2']="";
						$_SESSION['EXTRACT_ECMECLIENTMSN2']="";
						$_SESSION['EXTRACT_ECMECLIENTDossier2']="";
						
						$_SESSION['EXTRACT_PSCompagnon']="";
						$_SESSION['EXTRACT_PSIQ']="";
						$_SESSION['EXTRACT_PSReference']="";
						$_SESSION['EXTRACT_PSDateTERA']="";
						$_SESSION['EXTRACT_PSDateTERC']="";
						$_SESSION['EXTRACT_PSDu']="";
						$_SESSION['EXTRACT_PSAu']="";
						$_SESSION['EXTRACT_PSMSN']="";
						$_SESSION['EXTRACT_PSDossier']="";
						
						$_SESSION['EXTRACT_PSCompagnon2']="";
						$_SESSION['EXTRACT_PSIQ2']="";
						$_SESSION['EXTRACT_PSReference2']="";
						$_SESSION['EXTRACT_PSDateTERA2']="";
						$_SESSION['EXTRACT_PSDateTERC2']="";
						$_SESSION['EXTRACT_PSDu2']="";
						$_SESSION['EXTRACT_PSAu2']="";
						$_SESSION['EXTRACT_PSMSN2']="";
						$_SESSION['EXTRACT_PSDossier2']="";
						
						$resultPlateforme=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne='".$row[3]."'");
						$nbenregPlateforme=mysqli_num_rows($resultPlateforme);
						if($nbenregPlateforme>0)
						{
							while($donnees=mysqli_fetch_array($resultPlateforme))
							{
								array_push($tableau, $donnees[0]);
							}
						}
						$_SESSION['Id_Plateformes'] = $tableau;
						echo "<html>";
						
						if($_SESSION['NomSP']=="" || $_SESSION['PrenomSP']=="" || $_SESSION['LogSP']=="" || $_SESSION['MdpSP']=="")
						{echo "<body onload='window.location.top=\"".$chemin."/index.php?Cnx=CKIES\";'>";}
						else{
							if($_SESSION['MdpSP']=="aaa01"){echo "<body onload='window.location.href=\"".$chemin."/MotDePasse.php\";'>";}
							else {echo "<body onload='window.location.href=\"".$chemin."/Accueil.php\";'>";}
						}
					}
					else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD2\";'>";}
				}
				else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			}
			else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			mysqli_free_result($result);	// Libération des résultats
			mysqli_close($bdd);			// Fermeture de la connexion
		}
		elseif($_POST["prestation"]=="TTWP"){
			$result=mysqli_query($bdd,"SELECT Nom,Prenom,Email,Id FROM new_rh_etatcivil WHERE LoginSP='".$_POST["login"]."' AND MdpSP='".$_POST["motdepasse"]."'");
			$nbenreg=mysqli_num_rows($result);
			if($_POST["login"] <> "" and $_POST["motdepasse"] <> ""){
				if($nbenreg==1){
					//Creation des variables de session
					$row=mysqli_fetch_array($result);
					
					$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=316 ");
					
					$nbAcces=mysqli_num_rows($resultAcces);
					if($nbAcces>0){
						$rowDroit=mysqli_fetch_array($resultAcces);

						$_SESSION['LogSP']=$_POST["login"];
						$_SESSION['MdpSP']=$_POST["motdepasse"];
						$_SESSION['PrestationSP']=$_POST["prestation"];
						$_SESSION['NomSP']=$row[0];
						$_SESSION['PrenomSP']=$row[1];
						$_SESSION['EmailSP']=$row[2];
						$_SESSION['Id_PersonneSP']=$row['Id'];
						$_SESSION['DroitSP']=$rowDroit['Droit'];
						
						//Variables de sessions pour la recherche
						$_SESSION['MSN']="";
						$_SESSION['NumDossier']="";
						$_SESSION['NumIC']="";
						$_SESSION['Client']="";
						$_SESSION['Zone']="";
						$_SESSION['Priorite']="";
						$_SESSION['Section']="";
						$_SESSION['Titre']="";
						$_SESSION['CreateurDossier']="";
						$_SESSION['CreateurIC']="";
						$_SESSION['CE']="";
						$_SESSION['IQ']="";
						$_SESSION['StatutIC']="";
						$_SESSION['Vacation']="";
						$_SESSION['TravailRealise']="";
						$_SESSION['DateDebut']="";
						$_SESSION['DateFin']="";
						$_SESSION['SansDate']="";
						$_SESSION['EtatIC']="";
						$_SESSION['Poste']="";
						$_SESSION['Stamp']="";
						$_SESSION['DateDebutQUALITE']="";
						$_SESSION['DateFinQUALITE']="";
						$_SESSION['SansDateQUALITE']="";
						$_SESSION['VacationQUALITE']="";
						
						$_SESSION['MSN2']="";
						$_SESSION['NumDossier2']="";
						$_SESSION['NumIC2']="";
						$_SESSION['Client2']="";
						$_SESSION['Zone2']="";
						$_SESSION['Titre2']="";
						$_SESSION['Priorite2']="";
						$_SESSION['Section2']="";
						$_SESSION['CreateurDossier2']="";
						$_SESSION['CreateurIC2']="";
						$_SESSION['CE2']="";
						$_SESSION['IQ2']="";
						$_SESSION['StatutIC2']="";
						$_SESSION['Vacation2']="";
						$_SESSION['TravailRealise2']="";
						$_SESSION['DateDebut2']="";
						$_SESSION['DateFin2']="";
						$_SESSION['SansDate2']="";
						$_SESSION['EtatIC2']="";
						$_SESSION['Poste2']="";
						$_SESSION['Stamp2']="";
						$_SESSION['DateDebutQUALITE2']="";
						$_SESSION['DateFinQUALITE2']="";
						$_SESSION['SansDateQUALITE2']="";
						$_SESSION['VacationQUALITE2']="";
						
						$_SESSION['ModeFiltre']="";
						$_SESSION['Page']="0";
						
						//Variable session Tri
						$_SESSION['TriMSN']="ASC";
						$_SESSION['TriOF']="ASC";
						$_SESSION['TriPriorite']="";
						$_SESSION['TriTAI']="";
						$_SESSION['TriTitre']="";
						$_SESSION['TriDateIntervention']="";
						$_SESSION['TriVacation']="";
						$_SESSION['TriNumFI']="";
						$_SESSION['TriClient']="";
						$_SESSION['TriZone']="";
						$_SESSION['TriTravailRealise']="";
						$_SESSION['TriPoste']="";
						$_SESSION['TriStatutProd']="";
						$_SESSION['TriStatutQualite']="";
						$_SESSION['TriGeneral']="MSN ASC,Reference ASC,";

						//Activite supplémentaire
						$_SESSION['DateDebutAS']="";
						$_SESSION['DateFinAS']="";
						$_SESSION['TypeAS']="";

						$_SESSION['DateDebutAS2']="";
						$_SESSION['DateFinAS2']="";
						$_SESSION['TypeAS2']="";
						
						$_SESSION['ModeFiltreAS']="";
						$_SESSION['PageAS']="0";
						
						$_SESSION['TriDateAS']="DESC";
						$_SESSION['TriTypeAS']="ASC";
						$_SESSION['TriTempsPasseAS']="";
						$_SESSION['TriGeneralAS']="DateActivite DESC,Type ASC,";
						
						//Indicateurs
						$_SESSION['Indicateur_MSN']="";
						$_SESSION['Indicateur_Vacation']="";
						$_SESSION['Indicateur_Du']="";
						$_SESSION['Indicateur_Au']="";
						$_SESSION['Indicateur_Poste']="";
						$_SESSION['Indicateur_Client']="";
						
						$_SESSION['Indicateur_MSN2']="";
						$_SESSION['Indicateur_Vacation2']="";
						$_SESSION['Indicateur_Du2']="";
						$_SESSION['Indicateur_Au2']="";
						$_SESSION['Indicateur_Poste2']="";
						$_SESSION['Indicateur_Client2']="";
						
						$resultPlateforme=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne='".$row[3]."'");
						$nbenregPlateforme=mysqli_num_rows($resultPlateforme);
						if($nbenregPlateforme>0)
						{
							while($donnees=mysqli_fetch_array($resultPlateforme))
							{
								array_push($tableau, $donnees[0]);
							}
						}
						$_SESSION['Id_Plateformes'] = $tableau;
						echo "<html>";
						
						if($_SESSION['NomSP']=="" || $_SESSION['PrenomSP']=="" || $_SESSION['LogSP']=="" || $_SESSION['MdpSP']=="")
						{echo "<body onload='window.location.top=\"".$chemin."/index.php?Cnx=CKIES\";'>";}
						else{
							if($_SESSION['MdpSP']=="aaa01"){echo "<body onload='window.location.href=\"".$chemin."/MotDePasse.php\";'>";}
							else {echo "<body onload='window.location.href=\"".$chemin."/Accueil.php\";'>";}
						}
					}
					else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD2\";'>";}
				}
				else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			}
			else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			mysqli_free_result($result);	// Libération des résultats
			mysqli_close($bdd);			// Fermeture de la connexion
		}
		elseif($_POST["prestation"]=="AT47"){
			$result=mysqli_query($bdd,"SELECT Nom,Prenom,Email,Id FROM new_rh_etatcivil WHERE LoginSP='".$_POST["login"]."' AND MdpSP='".$_POST["motdepasse"]."'");
			$nbenreg=mysqli_num_rows($result);
			if($_POST["login"] <> "" and $_POST["motdepasse"] <> ""){
				if($nbenreg==1){
					//Creation des variables de session
					$row=mysqli_fetch_array($result);
					
					$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=262 ");
					
					$nbAcces=mysqli_num_rows($resultAcces);
					if($nbAcces>0){
						$rowDroit=mysqli_fetch_array($resultAcces);

						$_SESSION['LogSP']=$_POST["login"];
						$_SESSION['MdpSP']=$_POST["motdepasse"];
						$_SESSION['PrestationSP']=$_POST["prestation"];
						$_SESSION['NomSP']=$row[0];
						$_SESSION['PrenomSP']=$row[1];
						$_SESSION['EmailSP']=$row[2];
						$_SESSION['Id_PersonneSP']=$row['Id'];
						$_SESSION['DroitSP']=$rowDroit['Droit'];
						
						//Variable OT
						$_SESSION['OTMSN']="";
						$_SESSION['OTOM']="";
						$_SESSION['OTDesignation']="";
						$_SESSION['OTLigne']="";
						$_SESSION['OTPoste45']="";
						$_SESSION['OTStatutP']="";
						$_SESSION['OTRaisonP']="";
						$_SESSION['OTStatutQ']="";
						$_SESSION['OTRaisonQ']="";
						
						$_SESSION['OTMSN2']="";
						$_SESSION['OTOM2']="";
						$_SESSION['OTDesignation2']="";
						$_SESSION['OTLigne2']="";
						$_SESSION['OTPoste452']="";
						$_SESSION['OTStatutP2']="";
						$_SESSION['OTRaisonP2']="";
						$_SESSION['OTStatutQ2']="";
						$_SESSION['OTRaisonQ2']="";
						
						$_SESSION['OTModeFiltre']="oui";
						$_SESSION['OTPage']="0";
						
						$_SESSION['TriOTMSN']="DESC";
						$_SESSION['TriOTOM']="";
						$_SESSION['TriOTDesignation']="";
						$_SESSION['TriOTLigne']="ASC";
						$_SESSION['TriOTPoste45']="";
						$_SESSION['TriOTAMAssociee']="";
						$_SESSION['TriOTStatutP']="";
						$_SESSION['TriOTRaisonP']="";
						$_SESSION['TriOTStatutQ']="";
						$_SESSION['TriOTRaisonQ']="";
						$_SESSION['OTTriGeneral']="MSN DESC,Ligne ASC,";
						
						//Variables de sessions pour les AM
						$_SESSION['AMMSN']="";
						$_SESSION['AMNumAMNC']="";
						$_SESSION['AMOMAssocie']="";
						$_SESSION['AMDu']="";
						$_SESSION['AMAu']="";
						$_SESSION['AMImputationAAA']="";
						$_SESSION['AMNCMajeure']="";
						$_SESSION['AMType']="";
						$_SESSION['AMRecurrence']="";
						
						$_SESSION['AMMSN2']="";
						$_SESSION['AMNumAMNC2']="";
						$_SESSION['AMOMAssocie2']="";
						$_SESSION['AMDu2']="";
						$_SESSION['AMAu2']="";
						$_SESSION['AMImputationAAA2']="";
						$_SESSION['AMNCMajeure2']="";
						$_SESSION['AMType2']="";
						$_SESSION['AMRecurrence2']="";

						$_SESSION['AMModeFiltre']="";
						$_SESSION['AMPage']="0";
						
						//Variable session Tri
						$_SESSION['TriAMMSN']="DESC";
						$_SESSION['TriAMNumAMNC']="";
						$_SESSION['TriAMOMAssocie']="ASC";
						$_SESSION['TriAMDesignation']="";
						$_SESSION['TriAMDate']="";
						$_SESSION['TriAMImputationAAA']="";
						$_SESSION['TriAMNCMajeure']="";
						$_SESSION['TriAMType']="";
						$_SESSION['TriAMRecurrence']="";
						$_SESSION['TriAMGeneral']="MSN DESC,OMAssocie ASC,";
						
						//Variables de sessions pour les CQLB
						$_SESSION['CQLBMSN']="";
						$_SESSION['CQLBNumCQLB']="";
						$_SESSION['CQLBNumCV']="";
						$_SESSION['CQLBLocalisation']="";
						$_SESSION['CQLBImputationAAA']="";
						$_SESSION['CQLBOMAssocie']="";
						$_SESSION['CQLBAMAssociee']="";
						$_SESSION['CQLBDu']="";
						$_SESSION['CQLBAu']="";
						$_SESSION['CQLBType']="";
						$_SESSION['CQLBRecurrence']="";
						
						$_SESSION['CQLBMSN2']="";
						$_SESSION['CQLBNumCQLB2']="";
						$_SESSION['CQLBNumCV2']="";
						$_SESSION['CQLBLocalisation2']="";
						$_SESSION['CQLBImputationAAA2']="";
						$_SESSION['CQLBOMAssocie2']="";
						$_SESSION['CQLBAMAssociee2']="";
						$_SESSION['CQLBDu2']="";
						$_SESSION['CQLBAu2']="";
						$_SESSION['CQLBType2']="";
						$_SESSION['CQLBRecurrence2']="";

						$_SESSION['CQLBModeFiltre']="";
						$_SESSION['CQLBPage']="0";
						
						//Variable session Tri
						$_SESSION['TriCQLBMSN']="DESC";
						$_SESSION['TriCQLBNumCQLB']="ASC";
						$_SESSION['TriCQLBNumCV']="";
						$_SESSION['TriCQLBLocalisation']="";
						$_SESSION['TriCQLBImputationAAA']="";
						$_SESSION['TriCQLBOMAssocie']="";
						$_SESSION['TriCQLBDesignation']="";
						$_SESSION['TriCQLBAMAssocie']="";
						$_SESSION['TriCQLBDate']="";
						$_SESSION['TriCQLBType']="";
						$_SESSION['TriCQLBRecurrence']="";
						$_SESSION['TriCQLBGeneral']="MSN DESC,NumCQLB ASC,";
						
						$_SESSION['EXTRACT_INGIngredient']="";
						$_SESSION['EXTRACT_INGNumLot']="";
						$_SESSION['EXTRACT_INGDatePeremption']="";
						$_SESSION['EXTRACT_INGDateTERA']="";
						$_SESSION['EXTRACT_INGDateTERC']="";
						$_SESSION['EXTRACT_INGDu']="";
						$_SESSION['EXTRACT_INGAu']="";
						$_SESSION['EXTRACT_INGMSN']="";
						$_SESSION['EXTRACT_INGDossier']="";
						
						$_SESSION['EXTRACT_INGIngredient2']="";
						$_SESSION['EXTRACT_INGNumLot2']="";
						$_SESSION['EXTRACT_INGDatePeremption2']="";
						$_SESSION['EXTRACT_INGDateTERA2']="";
						$_SESSION['EXTRACT_INGDateTERC2']="";
						$_SESSION['EXTRACT_INGDu2']="";
						$_SESSION['EXTRACT_INGAu2']="";
						$_SESSION['EXTRACT_INGMSN2']="";
						$_SESSION['EXTRACT_INGDossier2']="";
						
						$_SESSION['EXTRACT_ECMEMetier']="";
						$_SESSION['EXTRACT_ECMEReference']="";
						$_SESSION['EXTRACT_ECMEType']="";
						$_SESSION['EXTRACT_ECMEDateTERA']="";
						$_SESSION['EXTRACT_ECMEDateTERC']="";
						$_SESSION['EXTRACT_ECMEDu']="";
						$_SESSION['EXTRACT_ECMEAu']="";
						$_SESSION['EXTRACT_ECMEMSN']="";
						$_SESSION['EXTRACT_ECMEDossier']="";
						
						$_SESSION['EXTRACT_ECMEMetier2']="";
						$_SESSION['EXTRACT_ECMEReference2']="";
						$_SESSION['EXTRACT_ECMEType2']="";
						$_SESSION['EXTRACT_ECMEDateTERA2']="";
						$_SESSION['EXTRACT_ECMEDateTERC2']="";
						$_SESSION['EXTRACT_ECMEDu2']="";
						$_SESSION['EXTRACT_ECMEAu2']="";
						$_SESSION['EXTRACT_ECMEMSN2']="";
						$_SESSION['EXTRACT_ECMEDossier2']="";
						
						$_SESSION['EXTRACT_ECMECLIENTClient']="";
						$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERA']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERC']="";
						$_SESSION['EXTRACT_ECMECLIENTDu']="";
						$_SESSION['EXTRACT_ECMECLIENTAu']="";
						$_SESSION['EXTRACT_ECMECLIENTMSN']="";
						$_SESSION['EXTRACT_ECMECLIENTDossier']="";
						
						$_SESSION['EXTRACT_ECMECLIENTClient2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERA2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERC2']="";
						$_SESSION['EXTRACT_ECMECLIENTDu2']="";
						$_SESSION['EXTRACT_ECMECLIENTAu2']="";
						$_SESSION['EXTRACT_ECMECLIENTMSN2']="";
						$_SESSION['EXTRACT_ECMECLIENTDossier2']="";
						
						$_SESSION['EXTRACT_PSCompagnon']="";
						$_SESSION['EXTRACT_PSIQ']="";
						$_SESSION['EXTRACT_PSReference']="";
						$_SESSION['EXTRACT_PSDateTERA']="";
						$_SESSION['EXTRACT_PSDateTERC']="";
						$_SESSION['EXTRACT_PSDu']="";
						$_SESSION['EXTRACT_PSAu']="";
						$_SESSION['EXTRACT_PSMSN']="";
						$_SESSION['EXTRACT_PSDossier']="";
						
						$_SESSION['EXTRACT_PSCompagnon2']="";
						$_SESSION['EXTRACT_PSIQ2']="";
						$_SESSION['EXTRACT_PSReference2']="";
						$_SESSION['EXTRACT_PSDateTERA2']="";
						$_SESSION['EXTRACT_PSDateTERC2']="";
						$_SESSION['EXTRACT_PSDu2']="";
						$_SESSION['EXTRACT_PSAu2']="";
						$_SESSION['EXTRACT_PSMSN2']="";
						$_SESSION['EXTRACT_PSDossier2']="";
						
						$resultPlateforme=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne='".$row[3]."'");
						$nbenregPlateforme=mysqli_num_rows($resultPlateforme);
						if($nbenregPlateforme>0)
						{
							while($donnees=mysqli_fetch_array($resultPlateforme))
							{
								array_push($tableau, $donnees[0]);
							}
						}
						$_SESSION['Id_Plateformes'] = $tableau;
						echo "<html>";
						
						if($_SESSION['NomSP']=="" || $_SESSION['PrenomSP']=="" || $_SESSION['LogSP']=="" || $_SESSION['MdpSP']=="")
						{echo "<body onload='window.location.top=\"".$chemin."/index.php?Cnx=CKIES\";'>";}
						else{
							if($_SESSION['MdpSP']=="aaa01"){echo "<body onload='window.location.href=\"".$chemin."/MotDePasse.php\";'>";}
							else {echo "<body onload='window.location.href=\"".$chemin."/Accueil.php\";'>";}
						}
					}
					else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD2\";'>";}
				}
				else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			}
			else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			mysqli_free_result($result);	// Libération des résultats
			mysqli_close($bdd);			// Fermeture de la connexion
		}
		elseif($_POST["prestation"]=="EISA"){
			$result=mysqli_query($bdd,"SELECT Nom,Prenom,Email,Id FROM new_rh_etatcivil WHERE LoginSP='".$_POST["login"]."' AND MdpSP='".$_POST["motdepasse"]."'");
			$nbenreg=mysqli_num_rows($result);
			if($_POST["login"] <> "" and $_POST["motdepasse"] <> ""){
				if($nbenreg==1){
					//Creation des variables de session
					$row=mysqli_fetch_array($result);
					
					$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=463 ");
					
					$nbAcces=mysqli_num_rows($resultAcces);
					if($nbAcces>0){
						$rowDroit=mysqli_fetch_array($resultAcces);

						$_SESSION['LogSP']=$_POST["login"];
						$_SESSION['MdpSP']=$_POST["motdepasse"];
						$_SESSION['PrestationSP']=$_POST["prestation"];
						$_SESSION['NomSP']=$row[0];
						$_SESSION['PrenomSP']=$row[1];
						$_SESSION['EmailSP']=$row[2];
						$_SESSION['Id_PersonneSP']=$row['Id'];
						$_SESSION['DroitSP']=$rowDroit['Droit'];
						
						//Variable OT
						$_SESSION['OTMSN']="";
						$_SESSION['OTOM']="";
						$_SESSION['OTDesignation']="";
						$_SESSION['OTTypeMoteur']="";
						$_SESSION['OTPosteMontage']="";
						$_SESSION['OTStatutP']="";
						$_SESSION['OTRaisonP']="";
						$_SESSION['OTStatutQ']="";
						$_SESSION['OTRaisonQ']="";
						$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('moteurSharklet','Moteur')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
						$_SESSION['OTMoteurSharklet']="Moteur".$btn;
						
						$_SESSION['OTMSN2']="";
						$_SESSION['OTOM2']="";
						$_SESSION['OTDesignation2']="";
						$_SESSION['OTTypeMoteur2']="";
						$_SESSION['OTPosteMontage2']="";
						$_SESSION['OTStatutP2']="";
						$_SESSION['OTRaisonP2']="";
						$_SESSION['OTStatutQ2']="";
						$_SESSION['OTRaisonQ2']="";
						$_SESSION['OTMoteurSharklet2']="Moteur;";
						
						$_SESSION['OTModeFiltre']="";
						$_SESSION['OTPage']="0";
						
						$_SESSION['TriOTMSN']="ASC";
						$_SESSION['TriOTOM']="ASC";
						$_SESSION['TriOTDatePROD']="";
						$_SESSION['TriOTDesignation']="";
						$_SESSION['TriOTTypeMoteur']="";
						$_SESSION['TriOTMoteurSharklet']="";
						$_SESSION['TriOTPosteMontage']="";
						$_SESSION['TriOTStatutP']="";
						$_SESSION['TriOTRaisonP']="";
						$_SESSION['TriOTStatutQ']="";
						$_SESSION['TriOTRaisonQ']="";
						$_SESSION['OTTriGeneral']="MSN ASC,OrdreMontage ASC,";
						
						//Variable Corbeille 
						$_SESSION['OTSupprMSN']="";
						$_SESSION['OTSupprOM']="";
						
						$_SESSION['OTSupprMSN2']="";
						$_SESSION['OTSupprOM2']="";
						
						$_SESSION['OTSupprModeFiltre']="oui";
						$_SESSION['OTSupprPage']="0";
						
						$_SESSION['TriOTSupprMSN']="ASC";
						$_SESSION['TriOTSupprOM']="ASC";
						$_SESSION['OTSupprTriGeneral']="MSN ASC,OrdreMontage ASC,";
			
						//Variables de sessions pour les AM
						$_SESSION['AMMSN']="";
						$_SESSION['AMNumAMNC']="";
						$_SESSION['AMNumOF']="";
						$_SESSION['AMOrigineAM']="";
						$_SESSION['AMImputation']="";
						$_SESSION['AMNumDERO']="";
						$_SESSION['AMMoteur']="";
						$_SESSION['AMNacelle']="";
						$_SESSION['AMMoment']="";
						$_SESSION['AMRecurrence']="";
						$_SESSION['AMStatut']="";
						$_SESSION['AMLocalisation']="";
						$_SESSION['AMTypeDefaut']="";
						$_SESSION['AMProduitImpacte']="";
						$_SESSION['AMCote']="";
						$_SESSION['AMActionCurative']="";
						$_SESSION['AMDu']="";
						$_SESSION['AMAu']="";
						
						$_SESSION['AMMSN2']="";
						$_SESSION['AMNumAMNC2']="";
						$_SESSION['AMNumOF2']="";
						$_SESSION['AMOrigineAM2']="";
						$_SESSION['AMImputation2']="";
						$_SESSION['AMNumDERO2']="";
						$_SESSION['AMMoteur2']="";
						$_SESSION['AMNacelle2']="";
						$_SESSION['AMMoment2']="";
						$_SESSION['AMRecurrence2']="";
						$_SESSION['AMStatut2']="";
						$_SESSION['AMLocalisation2']="";
						$_SESSION['AMTypeDefaut2']="";
						$_SESSION['AMProduitImpacte2']="";
						$_SESSION['AMCote2']="";
						$_SESSION['AMActionCurative2']="";
						$_SESSION['AMDu2']="";
						$_SESSION['AMAu2']="";

						$_SESSION['AMModeFiltre']="";
						$_SESSION['AMPage']="0";
						
						//Variable session Tri
						$_SESSION['TriAMMSN']="DESC";
						$_SESSION['TriAMNumAMNC']="ASC";
						$_SESSION['TriAMNumOF']="";
						$_SESSION['TriAMOrigineAM']="";
						$_SESSION['TriAMImputation']="";
						$_SESSION['TriAMNumDERO']="";
						$_SESSION['TriAMMoteur']="";
						$_SESSION['TriAMNacelle']="";
						$_SESSION['TriAMMomentDetection']="";
						$_SESSION['TriAMRecurrence']="";
						$_SESSION['TriAMStatut']="";
						$_SESSION['TriAMDescription']="";
						$_SESSION['TriAMDate']="";
						$_SESSION['TriAMLocalisation']="";
						$_SESSION['TriAMTypeDefaut']="";
						$_SESSION['TriAMCote']="";
						$_SESSION['TriAMActionCurative']="";
						
						$_SESSION['TriAMGeneral']="MSN DESC,NumAMNC ASC,";
						
						//Varible de session pour les moteurs
						$_SESSION['MOTMSN']="";
						$_SESSION['MOTTypeMoteur']="";
						$_SESSION['MOTPosteMontage']="";
						
						$_SESSION['MOTMSN2']="";
						$_SESSION['MOTTypeMoteur2']="";
						$_SESSION['MOTPosteMontage2']="";
						
						$_SESSION['MOTModeFiltre']="";
						$_SESSION['MOTPage']="0";
						
						//Varible de session pour les tris des moteurs
						$_SESSION['TriMOTMSN']="ASC";
						$_SESSION['TriMOTTypeMoteur']="ASC";
						$_SESSION['TriMOTPosteMontage']="";
						$_SESSION['TriMOTDate']="";
						$_SESSION['TriMOTGeneral']="MSN ASC,TypeMoteur ASC,";
						
						//Extract
						$_SESSION['EXTRACT_Mois']=date("m/Y");
						$_SESSION['EXTRACT_Debut']=date("d/m/Y");
						$_SESSION['EXTRACT_Fin']=date("d/m/Y");
						
						$_SESSION['EXTRACT_INGIngredient']="";
						$_SESSION['EXTRACT_INGNumLot']="";
						$_SESSION['EXTRACT_INGDatePeremption']="";
						$_SESSION['EXTRACT_INGDateTERA']="";
						$_SESSION['EXTRACT_INGDateTERC']="";
						$_SESSION['EXTRACT_INGDu']="";
						$_SESSION['EXTRACT_INGAu']="";
						$_SESSION['EXTRACT_INGMSN']="";
						$_SESSION['EXTRACT_INGDossier']="";
						
						$_SESSION['EXTRACT_INGIngredient2']="";
						$_SESSION['EXTRACT_INGNumLot2']="";
						$_SESSION['EXTRACT_INGDatePeremption2']="";
						$_SESSION['EXTRACT_INGDateTERA2']="";
						$_SESSION['EXTRACT_INGDateTERC2']="";
						$_SESSION['EXTRACT_INGDu2']="";
						$_SESSION['EXTRACT_INGAu2']="";
						$_SESSION['EXTRACT_INGMSN2']="";
						$_SESSION['EXTRACT_INGDossier2']="";
						
						$_SESSION['EXTRACT_ECMEMetier']="";
						$_SESSION['EXTRACT_ECMEReference']="";
						$_SESSION['EXTRACT_ECMEType']="";
						$_SESSION['EXTRACT_ECMEDateTERA']="";
						$_SESSION['EXTRACT_ECMEDateTERC']="";
						$_SESSION['EXTRACT_ECMEDu']="";
						$_SESSION['EXTRACT_ECMEAu']="";
						$_SESSION['EXTRACT_ECMEMSN']="";
						$_SESSION['EXTRACT_ECMEDossier']="";
						
						$_SESSION['EXTRACT_ECMEMetier2']="";
						$_SESSION['EXTRACT_ECMEReference2']="";
						$_SESSION['EXTRACT_ECMEType2']="";
						$_SESSION['EXTRACT_ECMEDateTERA2']="";
						$_SESSION['EXTRACT_ECMEDateTERC2']="";
						$_SESSION['EXTRACT_ECMEDu2']="";
						$_SESSION['EXTRACT_ECMEAu2']="";
						$_SESSION['EXTRACT_ECMEMSN2']="";
						$_SESSION['EXTRACT_ECMEDossier2']="";
						
						$_SESSION['EXTRACT_ECMECLIENTClient']="";
						$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERA']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERC']="";
						$_SESSION['EXTRACT_ECMECLIENTDu']="";
						$_SESSION['EXTRACT_ECMECLIENTAu']="";
						$_SESSION['EXTRACT_ECMECLIENTMSN']="";
						$_SESSION['EXTRACT_ECMECLIENTDossier']="";
						
						$_SESSION['EXTRACT_ECMECLIENTClient2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERA2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERC2']="";
						$_SESSION['EXTRACT_ECMECLIENTDu2']="";
						$_SESSION['EXTRACT_ECMECLIENTAu2']="";
						$_SESSION['EXTRACT_ECMECLIENTMSN2']="";
						$_SESSION['EXTRACT_ECMECLIENTDossier2']="";
						
						$_SESSION['EXTRACT_PSCompagnon']="";
						$_SESSION['EXTRACT_PSIQ']="";
						$_SESSION['EXTRACT_PSReference']="";
						$_SESSION['EXTRACT_PSDateTERA']="";
						$_SESSION['EXTRACT_PSDateTERC']="";
						$_SESSION['EXTRACT_PSDu']="";
						$_SESSION['EXTRACT_PSAu']="";
						$_SESSION['EXTRACT_PSMSN']="";
						$_SESSION['EXTRACT_PSDossier']="";
						
						$_SESSION['EXTRACT_PSCompagnon2']="";
						$_SESSION['EXTRACT_PSIQ2']="";
						$_SESSION['EXTRACT_PSReference2']="";
						$_SESSION['EXTRACT_PSDateTERA2']="";
						$_SESSION['EXTRACT_PSDateTERC2']="";
						$_SESSION['EXTRACT_PSDu2']="";
						$_SESSION['EXTRACT_PSAu2']="";
						$_SESSION['EXTRACT_PSMSN2']="";
						$_SESSION['EXTRACT_PSDossier2']="";
						
						$resultPlateforme=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne='".$row[3]."'");
						$nbenregPlateforme=mysqli_num_rows($resultPlateforme);
						if($nbenregPlateforme>0)
						{
							while($donnees=mysqli_fetch_array($resultPlateforme))
							{
								array_push($tableau, $donnees[0]);
							}
						}
						$_SESSION['Id_Plateformes'] = $tableau;
						echo "<html>";
						
						if($_SESSION['NomSP']=="" || $_SESSION['PrenomSP']=="" || $_SESSION['LogSP']=="" || $_SESSION['MdpSP']=="")
						{echo "<body onload='window.location.top=\"".$chemin."/index.php?Cnx=CKIES\";'>";}
						else{
							if($_SESSION['MdpSP']=="aaa01"){echo "<body onload='window.location.href=\"".$chemin."/MotDePasse.php\";'>";}
							else {echo "<body onload='window.location.href=\"".$chemin."/Accueil.php\";'>";}
						}
					}
					else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD2\";'>";}
				}
				else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			}
			else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			mysqli_free_result($result);	// Libération des résultats
			mysqli_close($bdd);			// Fermeture de la connexion
		}
		elseif($_POST["prestation"]=="LATECOERE 380"){
			$result=mysqli_query($bdd,"SELECT Nom,Prenom,Email,Id FROM new_rh_etatcivil WHERE LoginSP='".$_POST["login"]."' AND MdpSP='".$_POST["motdepasse"]."'");
			$nbenreg=mysqli_num_rows($result);
			if($_POST["login"] <> "" and $_POST["motdepasse"] <> ""){
				if($nbenreg==1){
					//Creation des variables de session
					$row=mysqli_fetch_array($result);
					
					$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=379 ");
					
					$nbAcces=mysqli_num_rows($resultAcces);
					if($nbAcces>0){
						$rowDroit=mysqli_fetch_array($resultAcces);

						$_SESSION['LogSP']=$_POST["login"];
						$_SESSION['MdpSP']=$_POST["motdepasse"];
						$_SESSION['PrestationSP']=$_POST["prestation"];
						$_SESSION['NomSP']=$row[0];
						$_SESSION['PrenomSP']=$row[1];
						$_SESSION['EmailSP']=$row[2];
						$_SESSION['Id_PersonneSP']=$row['Id'];
						$_SESSION['DroitSP']=$rowDroit['Droit'];
						
						//Variables de sessions pour la recherche
						$_SESSION['MSN']="";
						$_SESSION['NumDossier']="";
						$_SESSION['NumIC']="";
						$_SESSION['Client']="";
						$_SESSION['Zone']="";
						$_SESSION['Priorite']="";
						$_SESSION['Section']="";
						$_SESSION['Titre']="";
						$_SESSION['CreateurDossier']="";
						$_SESSION['CreateurIC']="";
						$_SESSION['CE']="";
						$_SESSION['IQ']="";
						$_SESSION['StatutIC']="";
						$_SESSION['Vacation']="";
						$_SESSION['TravailRealise']="";
						$_SESSION['DateDebut']="";
						$_SESSION['DateFin']="";
						$_SESSION['SansDate']="";
						$_SESSION['EtatIC']="";
						$_SESSION['Poste']="";
						$_SESSION['Stamp']="";
						$_SESSION['DateDebutQUALITE']="";
						$_SESSION['DateFinQUALITE']="";
						$_SESSION['SansDateQUALITE']="";
						$_SESSION['VacationQUALITE']="";
						
						$_SESSION['MSN2']="";
						$_SESSION['NumDossier2']="";
						$_SESSION['NumIC2']="";
						$_SESSION['Client2']="";
						$_SESSION['Zone2']="";
						$_SESSION['Titre2']="";
						$_SESSION['Priorite2']="";
						$_SESSION['Section2']="";
						$_SESSION['CreateurDossier2']="";
						$_SESSION['CreateurIC2']="";
						$_SESSION['CE2']="";
						$_SESSION['IQ2']="";
						$_SESSION['StatutIC2']="";
						$_SESSION['Vacation2']="";
						$_SESSION['TravailRealise2']="";
						$_SESSION['DateDebut2']="";
						$_SESSION['DateFin2']="";
						$_SESSION['SansDate2']="";
						$_SESSION['EtatIC2']="";
						$_SESSION['Poste2']="";
						$_SESSION['Stamp2']="";
						$_SESSION['DateDebutQUALITE2']="";
						$_SESSION['DateFinQUALITE2']="";
						$_SESSION['SansDateQUALITE2']="";
						$_SESSION['VacationQUALITE2']="";
						
						$_SESSION['ModeFiltre']="";
						$_SESSION['Page']="0";
						
						//Variable session Tri
						$_SESSION['TriMSN']="ASC";
						$_SESSION['TriOF']="ASC";
						$_SESSION['TriPriorite']="";
						$_SESSION['TriTAI']="";
						$_SESSION['TriTitre']="";
						$_SESSION['TriDateIntervention']="";
						$_SESSION['TriVacation']="";
						$_SESSION['TriNumFI']="";
						$_SESSION['TriClient']="";
						$_SESSION['TriZone']="";
						$_SESSION['TriTravailRealise']="";
						$_SESSION['TriPoste']="";
						$_SESSION['TriStatutProd']="";
						$_SESSION['TriStatutQualite']="";
						$_SESSION['TriGeneral']="MSN ASC,Reference ASC,";

						//Indicateurs
						$_SESSION['Indicateur_MSN']="";
						$_SESSION['Indicateur_Vacation']="";
						$_SESSION['Indicateur_Du']="";
						$_SESSION['Indicateur_Au']="";
						$_SESSION['Indicateur_Poste']="";
						$_SESSION['Indicateur_Client']="";
						
						$_SESSION['Indicateur_MSN2']="";
						$_SESSION['Indicateur_Vacation2']="";
						$_SESSION['Indicateur_Du2']="";
						$_SESSION['Indicateur_Au2']="";
						$_SESSION['Indicateur_Poste2']="";
						$_SESSION['Indicateur_Client2']="";
						
						$resultPlateforme=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne='".$row[3]."'");
						$nbenregPlateforme=mysqli_num_rows($resultPlateforme);
						if($nbenregPlateforme>0)
						{
							while($donnees=mysqli_fetch_array($resultPlateforme))
							{
								array_push($tableau, $donnees[0]);
							}
						}
						$_SESSION['Id_Plateformes'] = $tableau;
						echo "<html>";
						
						if($_SESSION['NomSP']=="" || $_SESSION['PrenomSP']=="" || $_SESSION['LogSP']=="" || $_SESSION['MdpSP']=="")
						{echo "<body onload='window.location.top=\"".$chemin."/index.php?Cnx=CKIES\";'>";}
						else{
							if($_SESSION['MdpSP']=="aaa01"){echo "<body onload='window.location.href=\"".$chemin."/MotDePasse.php\";'>";}
							else {echo "<body onload='window.location.href=\"".$chemin."/Accueil.php\";'>";}
						}
					}
					else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD2\";'>";}
				}
				else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			}
			else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			mysqli_free_result($result);	// Libération des résultats
			mysqli_close($bdd);			// Fermeture de la connexion
		}
		elseif($_POST["prestation"]=="BELX"){
			$result=mysqli_query($bdd,"SELECT Nom,Prenom,Email,Id FROM new_rh_etatcivil WHERE LoginSP='".$_POST["login"]."' AND MdpSP='".$_POST["motdepasse"]."'");
			$nbenreg=mysqli_num_rows($result);
			if($_POST["login"] <> "" and $_POST["motdepasse"] <> ""){
				if($nbenreg==1){
					//Creation des variables de session
					$row=mysqli_fetch_array($result);
					
					$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=815 ");
					
					$nbAcces=mysqli_num_rows($resultAcces);
					if($nbAcces>0){
						$rowDroit=mysqli_fetch_array($resultAcces);

						$_SESSION['LogSP']=$_POST["login"];
						$_SESSION['MdpSP']=$_POST["motdepasse"];
						$_SESSION['PrestationSP']=$_POST["prestation"];
						$_SESSION['NomSP']=$row[0];
						$_SESSION['PrenomSP']=$row[1];
						$_SESSION['EmailSP']=$row[2];
						$_SESSION['Id_PersonneSP']=$row['Id'];
						$_SESSION['DroitSP']=$rowDroit['Droit'];
						
						//Variables de sessions pour la recherche
						$_SESSION['MSN']="";
						$_SESSION['NumDossier']="";
						$_SESSION['NumIC']="";
						$_SESSION['Client']="";
						$_SESSION['Zone']="";
						$_SESSION['Priorite']="";
						$_SESSION['Section']="";
						$_SESSION['Titre']="";
						$_SESSION['CreateurDossier']="";
						$_SESSION['CreateurIC']="";
						$_SESSION['CE']="";
						$_SESSION['IQ']="";
						$_SESSION['StatutIC']="";
						$_SESSION['Vacation']="";
						$_SESSION['TravailRealise']="";
						$_SESSION['DateDebut']="";
						$_SESSION['DateFin']="";
						$_SESSION['SansDate']="";
						$_SESSION['EtatIC']="";
						$_SESSION['Poste']="";
						$_SESSION['Stamp']="";
						$_SESSION['DateDebutQUALITE']="";
						$_SESSION['DateFinQUALITE']="";
						$_SESSION['SansDateQUALITE']="";
						$_SESSION['VacationQUALITE']="";
						
						$_SESSION['MSN2']="";
						$_SESSION['NumDossier2']="";
						$_SESSION['NumIC2']="";
						$_SESSION['Client2']="";
						$_SESSION['Zone2']="";
						$_SESSION['Titre2']="";
						$_SESSION['Priorite2']="";
						$_SESSION['Section2']="";
						$_SESSION['CreateurDossier2']="";
						$_SESSION['CreateurIC2']="";
						$_SESSION['CE2']="";
						$_SESSION['IQ2']="";
						$_SESSION['StatutIC2']="";
						$_SESSION['Vacation2']="";
						$_SESSION['TravailRealise2']="";
						$_SESSION['DateDebut2']="";
						$_SESSION['DateFin2']="";
						$_SESSION['SansDate2']="";
						$_SESSION['EtatIC2']="";
						$_SESSION['Poste2']="";
						$_SESSION['Stamp2']="";
						$_SESSION['DateDebutQUALITE2']="";
						$_SESSION['DateFinQUALITE2']="";
						$_SESSION['SansDateQUALITE2']="";
						$_SESSION['VacationQUALITE2']="";
						
						$_SESSION['ModeFiltre']="";
						$_SESSION['Page']="0";
						
						//Variable session Tri
						$_SESSION['TriMSN']="ASC";
						$_SESSION['TriOF']="ASC";
						$_SESSION['TriPriorite']="";
						$_SESSION['TriTAI']="";
						$_SESSION['TriTitre']="";
						$_SESSION['TriDateIntervention']="";
						$_SESSION['TriVacation']="";
						$_SESSION['TriNumFI']="";
						$_SESSION['TriClient']="";
						$_SESSION['TriZone']="";
						$_SESSION['TriTravailRealise']="";
						$_SESSION['TriPoste']="";
						$_SESSION['TriStatutProd']="";
						$_SESSION['TriStatutQualite']="";
						$_SESSION['TriRetourPROD']="";
						$_SESSION['TriRetourQUALITE']="";
						$_SESSION['TriGeneral']="MSN ASC,Reference ASC,";

						//Indicateurs
						$_SESSION['Indicateur_MSN']="";
						$_SESSION['Indicateur_Vacation']="";
						$_SESSION['Indicateur_Du']="";
						$_SESSION['Indicateur_Au']="";
						$_SESSION['Indicateur_Poste']="";
						$_SESSION['Indicateur_Client']="";
						
						$_SESSION['Indicateur_MSN2']="";
						$_SESSION['Indicateur_Vacation2']="";
						$_SESSION['Indicateur_Du2']="";
						$_SESSION['Indicateur_Au2']="";
						$_SESSION['Indicateur_Poste2']="";
						$_SESSION['Indicateur_Client2']="";
						
						$resultPlateforme=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne='".$row[3]."'");
						$nbenregPlateforme=mysqli_num_rows($resultPlateforme);
						if($nbenregPlateforme>0)
						{
							while($donnees=mysqli_fetch_array($resultPlateforme))
							{
								array_push($tableau, $donnees[0]);
							}
						}
						$_SESSION['Id_Plateformes'] = $tableau;
						echo "<html>";
						
						if($_SESSION['NomSP']=="" || $_SESSION['PrenomSP']=="" || $_SESSION['LogSP']=="" || $_SESSION['MdpSP']=="")
						{echo "<body onload='window.location.top=\"".$chemin."/index.php?Cnx=CKIES\";'>";}
						else{
							if($_SESSION['MdpSP']=="aaa01"){echo "<body onload='window.location.href=\"".$chemin."/MotDePasse.php\";'>";}
							else {echo "<body onload='window.location.href=\"".$chemin."/Accueil.php\";'>";}
						}
					}
					else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD2\";'>";}
				}
				else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			}
			else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			mysqli_free_result($result);	// Libération des résultats
			mysqli_close($bdd);			// Fermeture de la connexion
		}
		elseif($_POST["prestation"]=="RATX"){
			$result=mysqli_query($bdd,"SELECT Nom,Prenom,Email,Id FROM new_rh_etatcivil WHERE LoginSP='".$_POST["login"]."' AND MdpSP='".$_POST["motdepasse"]."'");
			$nbenreg=mysqli_num_rows($result);
			if($_POST["login"] <> "" and $_POST["motdepasse"] <> ""){
				if($nbenreg==1){
					//Creation des variables de session
					$row=mysqli_fetch_array($result);
					
					$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=834 ");
					
					$nbAcces=mysqli_num_rows($resultAcces);
					if($nbAcces>0){
						$rowDroit=mysqli_fetch_array($resultAcces);

						$_SESSION['LogSP']=$_POST["login"];
						$_SESSION['MdpSP']=$_POST["motdepasse"];
						$_SESSION['PrestationSP']=$_POST["prestation"];
						$_SESSION['NomSP']=$row[0];
						$_SESSION['PrenomSP']=$row[1];
						$_SESSION['EmailSP']=$row[2];
						$_SESSION['Id_PersonneSP']=$row['Id'];
						$_SESSION['DroitSP']=$rowDroit['Droit'];
						
						//Variables de sessions pour la recherche
						$_SESSION['MSN']="";
						$_SESSION['NumDossier']="";
						$_SESSION['NumIC']="";
						$_SESSION['Client']="";
						$_SESSION['Zone']="";
						$_SESSION['Priorite']="";
						$_SESSION['Section']="";
						$_SESSION['Titre']="";
						$_SESSION['CreateurDossier']="";
						$_SESSION['CreateurIC']="";
						$_SESSION['CE']="";
						$_SESSION['IQ']="";
						$_SESSION['StatutIC']="";
						$_SESSION['Vacation']="";
						$_SESSION['TravailRealise']="";
						$_SESSION['DateDebut']="";
						$_SESSION['DateFin']="";
						$_SESSION['SansDate']="";
						$_SESSION['EtatIC']="";
						$_SESSION['Poste']="";
						$_SESSION['Stamp']="";
						$_SESSION['DateDebutQUALITE']="";
						$_SESSION['DateFinQUALITE']="";
						$_SESSION['SansDateQUALITE']="";
						$_SESSION['VacationQUALITE']="";
						
						$_SESSION['MSN2']="";
						$_SESSION['NumDossier2']="";
						$_SESSION['NumIC2']="";
						$_SESSION['Client2']="";
						$_SESSION['Zone2']="";
						$_SESSION['Titre2']="";
						$_SESSION['Priorite2']="";
						$_SESSION['Section2']="";
						$_SESSION['CreateurDossier2']="";
						$_SESSION['CreateurIC2']="";
						$_SESSION['CE2']="";
						$_SESSION['IQ2']="";
						$_SESSION['StatutIC2']="";
						$_SESSION['Vacation2']="";
						$_SESSION['TravailRealise2']="";
						$_SESSION['DateDebut2']="";
						$_SESSION['DateFin2']="";
						$_SESSION['SansDate2']="";
						$_SESSION['EtatIC2']="";
						$_SESSION['Poste2']="";
						$_SESSION['Stamp2']="";
						$_SESSION['DateDebutQUALITE2']="";
						$_SESSION['DateFinQUALITE2']="";
						$_SESSION['SansDateQUALITE2']="";
						$_SESSION['VacationQUALITE2']="";
						
						$_SESSION['ModeFiltre']="oui";
						$_SESSION['Page']="0";
						
						//Variable session Tri
						$_SESSION['TriMSN']="ASC";
						$_SESSION['TriOF']="ASC";
						$_SESSION['TriPriorite']="";
						$_SESSION['TriTAI']="";
						$_SESSION['TriTitre']="";
						$_SESSION['TriDateIntervention']="";
						$_SESSION['TriVacation']="";
						$_SESSION['TriNumFI']="";
						$_SESSION['TriClient']="";
						$_SESSION['TriZone']="";
						$_SESSION['TriTravailRealise']="";
						$_SESSION['TriPoste']="";
						$_SESSION['TriStatutProd']="";
						$_SESSION['TriStatutQualite']="";
						$_SESSION['TriRetourPROD']="";
						$_SESSION['TriRetourQUALITE']="";
						$_SESSION['TriGeneral']="MSN ASC,Reference ASC,";

						//Indicateurs
						$_SESSION['Indicateur_MSN']="";
						$_SESSION['Indicateur_Vacation']="";
						$_SESSION['Indicateur_Du']="";
						$_SESSION['Indicateur_Au']="";
						$_SESSION['Indicateur_Poste']="";
						$_SESSION['Indicateur_Client']="";
						
						$_SESSION['Indicateur_MSN2']="";
						$_SESSION['Indicateur_Vacation2']="";
						$_SESSION['Indicateur_Du2']="";
						$_SESSION['Indicateur_Au2']="";
						$_SESSION['Indicateur_Poste2']="";
						$_SESSION['Indicateur_Client2']="";
						
						$resultPlateforme=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne='".$row[3]."'");
						$nbenregPlateforme=mysqli_num_rows($resultPlateforme);
						if($nbenregPlateforme>0)
						{
							while($donnees=mysqli_fetch_array($resultPlateforme))
							{
								array_push($tableau, $donnees[0]);
							}
						}
						$_SESSION['Id_Plateformes'] = $tableau;
						echo "<html>";
						
						if($_SESSION['NomSP']=="" || $_SESSION['PrenomSP']=="" || $_SESSION['LogSP']=="" || $_SESSION['MdpSP']=="")
						{echo "<body onload='window.location.top=\"".$chemin."/index.php?Cnx=CKIES\";'>";}
						else{
							if($_SESSION['MdpSP']=="aaa01"){echo "<body onload='window.location.href=\"".$chemin."/MotDePasse.php\";'>";}
							else {echo "<body onload='window.location.href=\"".$chemin."/Accueil.php\";'>";}
						}
					}
					else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD2\";'>";}
				}
				else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			}
			else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			mysqli_free_result($result);	// Libération des résultats
			mysqli_close($bdd);			// Fermeture de la connexion
		}
		elseif($_POST["prestation"]=="SCOX"){
			$result=mysqli_query($bdd,"SELECT Nom,Prenom,Email,Id FROM new_rh_etatcivil WHERE LoginSP='".$_POST["login"]."' AND MdpSP='".$_POST["motdepasse"]."'");
			$nbenreg=mysqli_num_rows($result);
			if($_POST["login"] <> "" and $_POST["motdepasse"] <> ""){
				if($nbenreg==1){
					//Creation des variables de session
					$row=mysqli_fetch_array($result);
					
					$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=842 ");
					
					$nbAcces=mysqli_num_rows($resultAcces);
					if($nbAcces>0){
						$rowDroit=mysqli_fetch_array($resultAcces);

						$_SESSION['LogSP']=$_POST["login"];
						$_SESSION['MdpSP']=$_POST["motdepasse"];
						$_SESSION['PrestationSP']=$_POST["prestation"];
						$_SESSION['NomSP']=$row[0];
						$_SESSION['PrenomSP']=$row[1];
						$_SESSION['EmailSP']=$row[2];
						$_SESSION['Id_PersonneSP']=$row['Id'];
						$_SESSION['DroitSP']=$rowDroit['Droit'];
						
						//Variables de sessions pour la recherche
						$_SESSION['MSN']="";
						$_SESSION['NumDossier']="";
						$_SESSION['NumIC']="";
						$_SESSION['Client']="";
						$_SESSION['Zone']="";
						$_SESSION['Priorite']="";
						$_SESSION['Section']="";
						$_SESSION['Titre']="";
						$_SESSION['CreateurDossier']="";
						$_SESSION['CreateurIC']="";
						$_SESSION['CE']="";
						$_SESSION['IQ']="";
						$_SESSION['StatutIC']="";
						$_SESSION['Vacation']="";
						$_SESSION['TravailRealise']="";
						$_SESSION['DateDebut']="";
						$_SESSION['DateFin']="";
						$_SESSION['SansDate']="";
						$_SESSION['EtatIC']="";
						$_SESSION['Poste']="";
						$_SESSION['Stamp']="";
						$_SESSION['DateDebutQUALITE']="";
						$_SESSION['DateFinQUALITE']="";
						$_SESSION['SansDateQUALITE']="";
						$_SESSION['VacationQUALITE']="";
						
						$_SESSION['MSN2']="";
						$_SESSION['NumDossier2']="";
						$_SESSION['NumIC2']="";
						$_SESSION['Client2']="";
						$_SESSION['Zone2']="";
						$_SESSION['Titre2']="";
						$_SESSION['Priorite2']="";
						$_SESSION['Section2']="";
						$_SESSION['CreateurDossier2']="";
						$_SESSION['CreateurIC2']="";
						$_SESSION['CE2']="";
						$_SESSION['IQ2']="";
						$_SESSION['StatutIC2']="";
						$_SESSION['Vacation2']="";
						$_SESSION['TravailRealise2']="";
						$_SESSION['DateDebut2']="";
						$_SESSION['DateFin2']="";
						$_SESSION['SansDate2']="";
						$_SESSION['EtatIC2']="";
						$_SESSION['Poste2']="";
						$_SESSION['Stamp2']="";
						$_SESSION['DateDebutQUALITE2']="";
						$_SESSION['DateFinQUALITE2']="";
						$_SESSION['SansDateQUALITE2']="";
						$_SESSION['VacationQUALITE2']="";
						
						$_SESSION['ModeFiltre']="oui";
						$_SESSION['Page']="0";
						
						//Variable session Tri
						$_SESSION['TriMSN']="ASC";
						$_SESSION['TriOF']="ASC";
						$_SESSION['TriPriorite']="";
						$_SESSION['TriTAI']="";
						$_SESSION['TriTitre']="";
						$_SESSION['TriDateIntervention']="";
						$_SESSION['TriVacation']="";
						$_SESSION['TriNumFI']="";
						$_SESSION['TriClient']="";
						$_SESSION['TriZone']="";
						$_SESSION['TriTravailRealise']="";
						$_SESSION['TriPoste']="";
						$_SESSION['TriStatutProd']="";
						$_SESSION['TriStatutQualite']="";
						$_SESSION['TriRetourPROD']="";
						$_SESSION['TriRetourQUALITE']="";
						$_SESSION['TriGeneral']="MSN ASC,Reference ASC,";

						//Indicateurs
						$_SESSION['Indicateur_MSN']="";
						$_SESSION['Indicateur_Vacation']="";
						$_SESSION['Indicateur_Du']="";
						$_SESSION['Indicateur_Au']="";
						$_SESSION['Indicateur_Poste']="";
						$_SESSION['Indicateur_Client']="";
						
						$_SESSION['Indicateur_MSN2']="";
						$_SESSION['Indicateur_Vacation2']="";
						$_SESSION['Indicateur_Du2']="";
						$_SESSION['Indicateur_Au2']="";
						$_SESSION['Indicateur_Poste2']="";
						$_SESSION['Indicateur_Client2']="";
						
						$resultPlateforme=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne='".$row[3]."'");
						$nbenregPlateforme=mysqli_num_rows($resultPlateforme);
						if($nbenregPlateforme>0)
						{
							while($donnees=mysqli_fetch_array($resultPlateforme))
							{
								array_push($tableau, $donnees[0]);
							}
						}
						$_SESSION['Id_Plateformes'] = $tableau;
						echo "<html>";
						
						if($_SESSION['NomSP']=="" || $_SESSION['PrenomSP']=="" || $_SESSION['LogSP']=="" || $_SESSION['MdpSP']=="")
						{echo "<body onload='window.location.top=\"".$chemin."/index.php?Cnx=CKIES\";'>";}
						else{
							if($_SESSION['MdpSP']=="aaa01"){echo "<body onload='window.location.href=\"".$chemin."/MotDePasse.php\";'>";}
							else {echo "<body onload='window.location.href=\"".$chemin."/Accueil.php\";'>";}
						}
					}
					else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD2\";'>";}
				}
				else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			}
			else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			mysqli_free_result($result);	// Libération des résultats
			mysqli_close($bdd);			// Fermeture de la connexion
		}
		elseif($_POST["prestation"]=="P17S"){
			$result=mysqli_query($bdd,"SELECT Nom,Prenom,Email,Id FROM new_rh_etatcivil WHERE LoginSP='".$_POST["login"]."' AND MdpSP='".$_POST["motdepasse"]."'");
			$nbenreg=mysqli_num_rows($result);
			if($_POST["login"] <> "" and $_POST["motdepasse"] <> ""){
				if($nbenreg==1){
					//Creation des variables de session
					$row=mysqli_fetch_array($result);
					
					$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=729 ");
					
					$nbAcces=mysqli_num_rows($resultAcces);
					if($nbAcces>0){
						$rowDroit=mysqli_fetch_array($resultAcces);

						$_SESSION['LogSP']=$_POST["login"];
						$_SESSION['MdpSP']=$_POST["motdepasse"];
						$_SESSION['PrestationSP']=$_POST["prestation"];
						$_SESSION['NomSP']=$row[0];
						$_SESSION['PrenomSP']=$row[1];
						$_SESSION['EmailSP']=$row[2];
						$_SESSION['Id_PersonneSP']=$row['Id'];
						$_SESSION['DroitSP']=$rowDroit['Droit'];
						
						//Variable OT
						$_SESSION['OTMSN']="";
						$_SESSION['OTOM']="";
						$_SESSION['OTDesignation']="";
						$_SESSION['OTTypeMoteur']="";
						$_SESSION['OTPosteMontage']="";
						$_SESSION['OTStatutP']="";
						$_SESSION['OTRaisonP']="";
						$_SESSION['OTStatutQ']="";
						$_SESSION['OTRaisonQ']="";
						$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('moteurSharklet','Moteur')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
						$_SESSION['OTMoteurSharklet']="Moteur".$btn;
						
						$_SESSION['OTMSN2']="";
						$_SESSION['OTOM2']="";
						$_SESSION['OTDesignation2']="";
						$_SESSION['OTTypeMoteur2']="";
						$_SESSION['OTPosteMontage2']="";
						$_SESSION['OTStatutP2']="";
						$_SESSION['OTRaisonP2']="";
						$_SESSION['OTStatutQ2']="";
						$_SESSION['OTRaisonQ2']="";
						$_SESSION['OTMoteurSharklet2']="Moteur;";
						
						$_SESSION['OTModeFiltre']="";
						$_SESSION['OTPage']="0";
						
						$_SESSION['TriOTMSN']="ASC";
						$_SESSION['TriOTOM']="ASC";
						$_SESSION['TriOTDatePROD']="";
						$_SESSION['TriOTDesignation']="";
						$_SESSION['TriOTTypeMoteur']="";
						$_SESSION['TriOTMoteurSharklet']="";
						$_SESSION['TriOTPosteMontage']="";
						$_SESSION['TriOTStatutP']="";
						$_SESSION['TriOTRaisonP']="";
						$_SESSION['TriOTStatutQ']="";
						$_SESSION['TriOTRaisonQ']="";
						$_SESSION['OTTriGeneral']="MSN ASC,OrdreMontage ASC,";
						
						//Variable Corbeille 
						$_SESSION['OTSupprMSN']="";
						$_SESSION['OTSupprOM']="";
						
						$_SESSION['OTSupprMSN2']="";
						$_SESSION['OTSupprOM2']="";
						
						$_SESSION['OTSupprModeFiltre']="oui";
						$_SESSION['OTSupprPage']="0";
						
						$_SESSION['TriOTSupprMSN']="ASC";
						$_SESSION['TriOTSupprOM']="ASC";
						$_SESSION['OTSupprTriGeneral']="MSN ASC,OrdreMontage ASC,";
			
						//Variables de sessions pour les AM
						$_SESSION['AMMSN']="";
						$_SESSION['AMNumAMNC']="";
						$_SESSION['AMNumOF']="";
						$_SESSION['AMOrigineAM']="";
						$_SESSION['AMImputation']="";
						$_SESSION['AMNumDERO']="";
						$_SESSION['AMMoteur']="";
						$_SESSION['AMNacelle']="";
						$_SESSION['AMMoment']="";
						$_SESSION['AMRecurrence']="";
						$_SESSION['AMStatut']="";
						$_SESSION['AMLocalisation']="";
						$_SESSION['AMTypeDefaut']="";
						$_SESSION['AMProduitImpacte']="";
						$_SESSION['AMCote']="";
						$_SESSION['AMActionCurative']="";
						$_SESSION['AMActionCorrective']="";
						$_SESSION['AMDu']="";
						$_SESSION['AMAu']="";
						
						$_SESSION['AMMSN2']="";
						$_SESSION['AMNumAMNC2']="";
						$_SESSION['AMNumOF2']="";
						$_SESSION['AMOrigineAM2']="";
						$_SESSION['AMImputation2']="";
						$_SESSION['AMNumDERO2']="";
						$_SESSION['AMMoteur2']="";
						$_SESSION['AMNacelle2']="";
						$_SESSION['AMMoment2']="";
						$_SESSION['AMRecurrence2']="";
						$_SESSION['AMStatut2']="";
						$_SESSION['AMLocalisation2']="";
						$_SESSION['AMTypeDefaut2']="";
						$_SESSION['AMProduitImpacte2']="";
						$_SESSION['AMCote2']="";
						$_SESSION['AMActionCurative2']="";
						$_SESSION['AMActionCorrective2']="";
						$_SESSION['AMDu2']="";
						$_SESSION['AMAu2']="";

						$_SESSION['AMModeFiltre']="";
						$_SESSION['AMPage']="0";
						
						//Variable session Tri
						$_SESSION['TriAMMSN']="DESC";
						$_SESSION['TriAMNumAMNC']="ASC";
						$_SESSION['TriAMNumOF']="";
						$_SESSION['TriAMOrigineAM']="";
						$_SESSION['TriAMImputation']="";
						$_SESSION['TriAMNumDERO']="";
						$_SESSION['TriAMMoteur']="";
						$_SESSION['TriAMNacelle']="";
						$_SESSION['TriAMMomentDetection']="";
						$_SESSION['TriAMRecurrence']="";
						$_SESSION['TriAMStatut']="";
						$_SESSION['TriAMDescription']="";
						$_SESSION['TriAMDate']="";
						$_SESSION['TriAMLocalisation']="";
						$_SESSION['TriAMTypeDefaut']="";
						$_SESSION['TriAMCote']="";
						$_SESSION['TriAMActionCurative']="";
						$_SESSION['TriAMActionCorrective']="";
						$_SESSION['TriAMWeek']="";
						
						$_SESSION['TriAMGeneral']="MSN DESC,NumAMNC ASC,";
						
						//Varible de session pour les moteurs
						$_SESSION['MOTMSN']="";
						$_SESSION['MOTTypeMoteur']="";
						$_SESSION['MOTPosteMontage']="";
						
						$_SESSION['MOTMSN2']="";
						$_SESSION['MOTTypeMoteur2']="";
						$_SESSION['MOTPosteMontage2']="";
						
						$_SESSION['MOTModeFiltre']="";
						$_SESSION['MOTPage']="0";
						
						//Varible de session pour les tris des moteurs
						$_SESSION['TriMOTMSN']="ASC";
						$_SESSION['TriMOTTypeMoteur']="ASC";
						$_SESSION['TriMOTPosteMontage']="";
						$_SESSION['TriMOTDate']="";
						$_SESSION['TriMOTGeneral']="MSN ASC,TypeMoteur ASC,";
						
						//Extract
						$_SESSION['EXTRACT_Mois']=date("m/Y");
						$_SESSION['EXTRACT_Debut']=date("d/m/Y");
						$_SESSION['EXTRACT_Fin']=date("d/m/Y");
						
						$resultPlateforme=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne='".$row[3]."'");
						$nbenregPlateforme=mysqli_num_rows($resultPlateforme);
						if($nbenregPlateforme>0)
						{
							while($donnees=mysqli_fetch_array($resultPlateforme))
							{
								array_push($tableau, $donnees[0]);
							}
						}
						$_SESSION['Id_Plateformes'] = $tableau;
						echo "<html>";
						
						if($_SESSION['NomSP']=="" || $_SESSION['PrenomSP']=="" || $_SESSION['LogSP']=="" || $_SESSION['MdpSP']=="")
						{echo "<body onload='window.location.top=\"".$chemin."/index.php?Cnx=CKIES\";'>";}
						else{
							if($_SESSION['MdpSP']=="aaa01"){echo "<body onload='window.location.href=\"".$chemin."/MotDePasse.php\";'>";}
							else {echo "<body onload='window.location.href=\"".$chemin."/Accueil.php\";'>";}
						}
					}
					else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD2\";'>";}
				}
				else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			}
			else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			mysqli_free_result($result);	// Libération des résultats
			mysqli_close($bdd);			// Fermeture de la connexion
		}
		elseif($_POST["prestation"]=="TRSA-TRMY" || $_POST["prestation"]=="SBVA"){
			$result=mysqli_query($bdd,"SELECT Nom,Prenom,Email,Id FROM new_rh_etatcivil WHERE LoginSP='".$_POST["login"]."' AND MdpSP='".$_POST["motdepasse"]."'");
			$nbenreg=mysqli_num_rows($result);
			if($_POST["login"] <> "" and $_POST["motdepasse"] <> ""){
				if($nbenreg==1){
					//Creation des variables de session
					$row=mysqli_fetch_array($result);
					
					if($_POST["prestation"]=="TRSA-TRMY"){
						$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=86 ");
					}
					else{
						$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=79 ");
					}
					$nbAcces=mysqli_num_rows($resultAcces);
					if($nbAcces>0){
						$rowDroit=mysqli_fetch_array($resultAcces);

						$_SESSION['LogSP']=$_POST["login"];
						$_SESSION['MdpSP']=$_POST["motdepasse"];
						$_SESSION['PrestationSP']=$_POST["prestation"];
						$_SESSION['NomSP']=$row[0];
						$_SESSION['PrenomSP']=$row[1];
						$_SESSION['EmailSP']=$row[2];
						$_SESSION['Id_PersonneSP']=$row['Id'];
						$_SESSION['DroitSP']=$rowDroit['Droit'];
						
						//Variable OT
						$_SESSION['OTMSN']="";
						$_SESSION['OTOM']="";
						$_SESSION['OTStatutP']="";
						$_SESSION['OTStatutQ']="";
						$_SESSION['OTType']="";
						$_SESSION['OTOperation']="";
						$_SESSION['OTImputation']="";
						$_SESSION['OTDateReceptionDu']="";
						$_SESSION['OTDateReceptionAu']="";
						$_SESSION['OTDateEnvoiGammeDu']="";
						$_SESSION['OTDateEnvoiGammeAu']="";
						$_SESSION['OTGammeRetourneeSTELIA']="";
						$_SESSION['OTTempsOF']="";
						
						$_SESSION['OTMSN2']="";
						$_SESSION['OTOM2']="";
						$_SESSION['OTStatutP2']="";
						$_SESSION['OTStatutQ2']="";
						$_SESSION['OTType2']="";
						$_SESSION['OTOperation2']="";
						$_SESSION['OTImputation2']="";
						$_SESSION['OTDateReceptionDu2']="";
						$_SESSION['OTDateReceptionAu2']="";
						$_SESSION['OTDateEnvoiGammeDu2']="";
						$_SESSION['OTDateEnvoiGammeAu2']="";
						$_SESSION['OTGammeRetourneeSTELIA2']="";
						$_SESSION['OTTempsOF2']="";
						
						$_SESSION['OTModeFiltre']="oui";
						$_SESSION['OTPage']="0";
			
						$_SESSION['OT_TRSAMSN']="";
						$_SESSION['OT_TRSAOF']="";
						$_SESSION['OT_TRSAGamme']="";
						$_SESSION['OT_TRSALot']="";
						$_SESSION['OT_TRSAPoste']="";
						$_SESSION['OT_TRSASection']="";
						$_SESSION['OT_TRSAStatutP']="";
						$_SESSION['OT_TRSAStatutQ']="";
						
						$_SESSION['OT_TRSAMSN2']="";
						$_SESSION['OT_TRSAOF2']="";
						$_SESSION['OT_TRSAGamme2']="";
						$_SESSION['OT_TRSALot2']="";
						$_SESSION['OT_TRSAPoste2']="";
						$_SESSION['OT_TRSASection2']="";
						$_SESSION['OT_TRSAStatutP2']="";
						$_SESSION['OT_TRSAStatutQ2']="";
						
						$_SESSION['OT_TRSAModeFiltre']="oui";
						$_SESSION['OT_TRSAPage']="0";
						
						$_SESSION['Tri_TRSAMSN']="";
						$_SESSION['Tri_TRSAOF']="";
						$_SESSION['Tri_TRSAGamme']="";
						$_SESSION['Tri_TRSALot']="";
						$_SESSION['Tri_TRSAPoste']="";
						$_SESSION['Tri_TRSASection']="";
						$_SESSION['Tri_TRSAStatutP']="";
						$_SESSION['Tri_TRSAStatutQ']="";
						$_SESSION['Tri_TRSACommentaireACP']="";
						$_SESSION['Tri_TRSACommentaireSupp']="";
						$_SESSION['Tri_TRSAGeneral']="";
						
						$_SESSION['OT_TRMYMSN']="";
						$_SESSION['OT_TRMYOF']="";
						$_SESSION['OT_TRMYGamme']="";
						$_SESSION['OT_TRMYLot']="";
						$_SESSION['OT_TRMYPoste']="";
						$_SESSION['OT_TRMYSection']="";
						$_SESSION['OT_TRMYStatutP']="";
						$_SESSION['OT_TRMYStatutQ']="";
						
						$_SESSION['OT_TRMYMSN2']="";
						$_SESSION['OT_TRMYOF2']="";
						$_SESSION['OT_TRMYGamme2']="";
						$_SESSION['OT_TRMYLot2']="";
						$_SESSION['OT_TRMYPoste2']="";
						$_SESSION['OT_TRMYSection2']="";
						$_SESSION['OT_TRMYStatutP2']="";
						$_SESSION['OT_TRMYStatutQ2']="";
						
						$_SESSION['OT_TRMYModeFiltre']="";
						$_SESSION['OT_TRMYPage']="0";
						
						
						$_SESSION['TriOTMSN']="";
						$_SESSION['TriOTOM']="";
						$_SESSION['TriOTDateDossier']="";
						$_SESSION['TriOTStatutP']="";
						$_SESSION['TriOTStatutQ']="";
						$_SESSION['TriOTType']="";
						$_SESSION['TriOTOperation']="";
						$_SESSION['TriOTImputation']="";
						$_SESSION['TriOTTempsOF']="";
						$_SESSION['OTTriGeneral']="";
			
						$_SESSION['Tri_TRMYMSN']="";
						$_SESSION['Tri_TRMYOF']="";
						$_SESSION['Tri_TRMYGamme']="";
						$_SESSION['Tri_TRMYLot']="";
						$_SESSION['Tri_TRMYPoste']="";
						$_SESSION['Tri_TRMYSection']="";
						$_SESSION['Tri_TRMYStatutP']="";
						$_SESSION['Tri_TRMYStatutQ']="";
						$_SESSION['Tri_TRMYCommentaireACP']="";
						$_SESSION['Tri_TRMYCommentaireSupp']="";
						$_SESSION['Tri_TRMYGeneral']="";
						
						//Variable de session pour les travaux supplémentaires 
						$_SESSION['TRAVAUXMSN']="";
						$_SESSION['TRAVAUXNumBL']="";
						$_SESSION['TRAVAUXPrestation']="";
						$_SESSION['TRAVAUXStation']="";
						$_SESSION['TRAVAUXType']="";
						$_SESSION['TRAVAUXReference']="";
						$_SESSION['TRAVAUXCluster']="";
						$_SESSION['TRAVAUXLot']="";
						$_SESSION['TRAVAUXQuantite']="";
						$_SESSION['TRAVAUXDateTERADu']="";
						$_SESSION['TRAVAUXDateTERAAu']="";
						$_SESSION['TRAVAUXDateTERCDu']="";
						$_SESSION['TRAVAUXDateTERCAu']="";
						$_SESSION['TRAVAUXControleur']="";
						$_SESSION['TRAVAUXOperateur']="";
						$_SESSION['TRAVAUXECME']="";
						$_SESSION['TRAVAUXProduit']="";

						$_SESSION['TRAVAUXMSN2']="";
						$_SESSION['TRAVAUXNumBL2']="";
						$_SESSION['TRAVAUXPrestation2']="";
						$_SESSION['TRAVAUXStation2']="";
						$_SESSION['TRAVAUXType2']="";
						$_SESSION['TRAVAUXReference2']="";
						$_SESSION['TRAVAUXCluster2']="";
						$_SESSION['TRAVAUXLot2']="";
						$_SESSION['TRAVAUXQuantite2']="";
						$_SESSION['TRAVAUXDateTERADu2']="";
						$_SESSION['TRAVAUXDateTERAAu2']="";
						$_SESSION['TRAVAUXDateTERCDu2']="";
						$_SESSION['TRAVAUXDateTERCAu2']="";
						$_SESSION['TRAVAUXControleur2']="";
						$_SESSION['TRAVAUXOperateur2']="";
						$_SESSION['TRAVAUXECME2']="";
						$_SESSION['TRAVAUXProduit2']="";

						$_SESSION['TRAVAUXModeFiltre']="oui";
						$_SESSION['TRAVAUXPage']="0";
						
						$_SESSION['TriTRAVAUXMSN']="";
						$_SESSION['TriTRAVAUXNumBL']="";
						$_SESSION['TriTRAVAUXPrestation']="";
						$_SESSION['TriTRAVAUXStation']="";
						$_SESSION['TriTRAVAUXType']="";
						$_SESSION['TriTRAVAUXReference']="";
						$_SESSION['TriTRAVAUXCluster']="";
						$_SESSION['TriTRAVAUXLot']="";
						$_SESSION['TriTRAVAUXQuantite']="";
						$_SESSION['TriTRAVAUXDateTERA']="";
						$_SESSION['TriTRAVAUXDateTERC']="";
						$_SESSION['TriTRAVAUXGeneral']="";
					
						//Variables de sessions pour les AM
						$_SESSION['AMPrestation']="";
						$_SESSION['AMMSN']="";
						$_SESSION['AMNumAMNC']="";
						$_SESSION['AMNumOF']="";
						$_SESSION['AMOrigineAM']="";
						$_SESSION['AMImputation']="";
						$_SESSION['AMNumDERO']="";
						$_SESSION['AMMoteur']="";
						$_SESSION['AMNacelle']="";
						$_SESSION['AMMoment']="";
						$_SESSION['AMRecurrence']="";
						$_SESSION['AMStatut']="";
						$_SESSION['AMLocalisation']="";
						$_SESSION['AMTypeDefaut']="";
						$_SESSION['AMProduitImpacte']="";
						$_SESSION['AMCote']="";
						$_SESSION['AMActionCurative']="";
						$_SESSION['AMDu']="";
						$_SESSION['AMAu']="";
						$_SESSION['AMPrestation']="";
						$_SESSION['AMAuteur']="";
						$_SESSION['AMActionCorrective']="";
						$_SESSION['AMDescriptif']="";
						$_SESSION['AMLot']="";
						$_SESSION['AMPoste']="";
						$_SESSION['AMTypeAM']="";
						$_SESSION['AMNCMajeure']="";
						
						$_SESSION['AMPrestation2']="";
						$_SESSION['AMMSN2']="";
						$_SESSION['AMNumAMNC2']="";
						$_SESSION['AMNumOF2']="";
						$_SESSION['AMOrigineAM2']="";
						$_SESSION['AMImputation2']="";
						$_SESSION['AMNumDERO2']="";
						$_SESSION['AMMoteur2']="";
						$_SESSION['AMNacelle2']="";
						$_SESSION['AMMoment2']="";
						$_SESSION['AMRecurrence2']="";
						$_SESSION['AMStatut2']="";
						$_SESSION['AMLocalisation2']="";
						$_SESSION['AMTypeDefaut2']="";
						$_SESSION['AMProduitImpacte2']="";
						$_SESSION['AMCote2']="";
						$_SESSION['AMActionCurative2']="";
						$_SESSION['AMDu2']="";
						$_SESSION['AMAu2']="";
						$_SESSION['AMPrestation2']="";
						$_SESSION['AMAuteur2']="";
						$_SESSION['AMActionCorrective2']="";
						$_SESSION['AMDescriptif2']="";
						$_SESSION['AMLot2']="";
						$_SESSION['AMPoste2']="";
						$_SESSION['AMTypeAM2']="";
						$_SESSION['AMNCMajeure2']="";

						$_SESSION['AMModeFiltre']="";
						$_SESSION['AMPage']="0";
						
						//Variable session Tri
						$_SESSION['TriAMPrestation']="";
						$_SESSION['TriAMMSN']="DESC";
						$_SESSION['TriAMNumAMNC']="ASC";
						$_SESSION['TriAMNumOF']="";
						$_SESSION['TriAMOrigineAM']="";
						$_SESSION['TriAMImputation']="";
						$_SESSION['TriAMNumDERO']="";
						$_SESSION['TriAMMoteur']="";
						$_SESSION['TriAMNacelle']="";
						$_SESSION['TriAMMomentDetection']="";
						$_SESSION['TriAMRecurrence']="";
						$_SESSION['TriAMStatut']="";
						$_SESSION['TriAMDescription']="";
						$_SESSION['TriAMDate']="";
						$_SESSION['TriAMLocalisation']="";
						$_SESSION['TriAMTypeDefaut']="";
						$_SESSION['TriAMCote']="";
						$_SESSION['TriAMWeek']="";
						$_SESSION['TriAMLot']="";
						$_SESSION['TriAMPoste']="";
						
						$_SESSION['TriAMGeneral']="MSN DESC,NumAMNC ASC,";
						
						//Variables de sessions pour les CQLB
						$_SESSION['CQLBPrestation']="";
						$_SESSION['CQLBMSN']="";
						$_SESSION['CQLBNumAMNC']="";
						$_SESSION['CQLBNumOF']="";
						$_SESSION['CQLBOrigineAM']="";
						$_SESSION['CQLBImputation']="";
						$_SESSION['CQLBNumDERO']="";
						$_SESSION['CQLBMoteur']="";
						$_SESSION['CQLBNacelle']="";
						$_SESSION['CQLBMoment']="";
						$_SESSION['CQLBRecurrence']="";
						$_SESSION['CQLBStatut']="";
						$_SESSION['CQLBLocalisation']="";
						$_SESSION['CQLBTypeDefaut']="";
						$_SESSION['CQLBProduitImpacte']="";
						$_SESSION['CQLBCote']="";
						$_SESSION['CQLBActionCurative']="";
						$_SESSION['CQLBDu']="";
						$_SESSION['CQLBAu']="";
						$_SESSION['CQLBPrestation']="";
						$_SESSION['CQLBAuteur']="";
						$_SESSION['CQLBActionCorrective']="";
						$_SESSION['CQLBDescriptif']="";
						$_SESSION['CQLBLot']="";
						$_SESSION['CQLBPoste']="";
						$_SESSION['CQLBTypeAM']="";
						$_SESSION['CQLBNCMajeure']="";
						$_SESSION['CQLBOrigine']="";
						$_SESSION['CQLBTypeCQLB']="";
						$_SESSION['CQLBNumCQLB']="";
						$_SESSION['CQLBNumCV']="";
						
						
						$_SESSION['CQLBPrestation2']="";
						$_SESSION['CQLBMSN2']="";
						$_SESSION['CQLBNumAMNC2']="";
						$_SESSION['CQLBNumOF2']="";
						$_SESSION['CQLBOrigineAM2']="";
						$_SESSION['CQLBImputation2']="";
						$_SESSION['CQLBNumDERO2']="";
						$_SESSION['CQLBMoteur2']="";
						$_SESSION['CQLBNacelle2']="";
						$_SESSION['CQLBMoment2']="";
						$_SESSION['CQLBRecurrence2']="";
						$_SESSION['CQLBStatut2']="";
						$_SESSION['CQLBLocalisation2']="";
						$_SESSION['CQLBTypeDefaut2']="";
						$_SESSION['CQLBProduitImpacte2']="";
						$_SESSION['CQLBCote2']="";
						$_SESSION['CQLBActionCurative2']="";
						$_SESSION['CQLBDu2']="";
						$_SESSION['CQLBAu2']="";
						$_SESSION['CQLBPrestation2']="";
						$_SESSION['CQLBAuteur2']="";
						$_SESSION['CQLBActionCorrective2']="";
						$_SESSION['CQLBDescriptif2']="";
						$_SESSION['CQLBLot2']="";
						$_SESSION['CQLBPoste2']="";
						$_SESSION['CQLBTypeAM2']="";
						$_SESSION['CQLBNCMajeure2']="";
						$_SESSION['CQLBOrigine2']="";
						$_SESSION['CQLBTypeCQLB2']="";
						$_SESSION['CQLBNumCQLB2']="";
						$_SESSION['CQLBNumCV2']="";

						$_SESSION['CQLBModeFiltre']="";
						$_SESSION['CQLBPage']="0";
						
						//Variable session Tri
						$_SESSION['TriCQLBPrestation']="";
						$_SESSION['TriCQLBMSN']="DESC";
						$_SESSION['TriCQLBNumAMNC']="ASC";
						$_SESSION['TriCQLBNumOF']="";
						$_SESSION['TriCQLBOrigineAM']="";
						$_SESSION['TriCQLBImputation']="";
						$_SESSION['TriCQLBNumDERO']="";
						$_SESSION['TriCQLBMoteur']="";
						$_SESSION['TriCQLBNacelle']="";
						$_SESSION['TriCQLBMomentDetection']="";
						$_SESSION['TriCQLBRecurrence']="";
						$_SESSION['TriCQLBStatut']="";
						$_SESSION['TriCQLBDescription']="";
						$_SESSION['TriCQLBDate']="";
						$_SESSION['TriCQLBLocalisation']="";
						$_SESSION['TriCQLBTypeDefaut']="";
						$_SESSION['TriCQLBCote']="";
						$_SESSION['TriCQLBWeek']="";
						$_SESSION['TriCQLBLot']="";
						$_SESSION['TriCQLBPoste']="";
						$_SESSION['TriCQLBNumCQLB']="";
						$_SESSION['TriCQLBNumCV']="";
						
						$_SESSION['TriCQLBGeneral']="MSN DESC,NumAMNC ASC,";
						
						//Varible de session pour les moteurs
						$_SESSION['MOTMSN']="";
						$_SESSION['MOTTypeMoteur']="";
						$_SESSION['MOTPosteMontage']="";
						
						$_SESSION['MOTMSN2']="";
						$_SESSION['MOTTypeMoteur2']="";
						$_SESSION['MOTPosteMontage2']="";
						
						$_SESSION['MOTModeFiltre']="";
						$_SESSION['MOTPage']="0";
						
						//Varible de session pour les tris des moteurs
						$_SESSION['TriMOTMSN']="ASC";
						$_SESSION['TriMOTTypeMoteur']="ASC";
						$_SESSION['TriMOTPosteMontage']="";
						$_SESSION['TriMOTDate']="";
						$_SESSION['TriMOTGeneral']="MSN ASC,TypeMoteur ASC,";
						
						//Extract
						$_SESSION['EXTRACT_Mois']=date("m/Y");
						$_SESSION['EXTRACT_Debut']=date("d/m/Y");
						$_SESSION['EXTRACT_Fin']=date("d/m/Y");
						
						$_SESSION['EXTRACT_INGIngredient']="";
						$_SESSION['EXTRACT_INGNumLot']="";
						$_SESSION['EXTRACT_INGDatePeremption']="";
						$_SESSION['EXTRACT_INGDateTERA']="";
						$_SESSION['EXTRACT_INGDateTERC']="";
						$_SESSION['EXTRACT_INGDu']="";
						$_SESSION['EXTRACT_INGAu']="";
						$_SESSION['EXTRACT_INGMSN']="";
						$_SESSION['EXTRACT_INGDossier']="";
						
						$_SESSION['EXTRACT_INGIngredient2']="";
						$_SESSION['EXTRACT_INGNumLot2']="";
						$_SESSION['EXTRACT_INGDatePeremption2']="";
						$_SESSION['EXTRACT_INGDateTERA2']="";
						$_SESSION['EXTRACT_INGDateTERC2']="";
						$_SESSION['EXTRACT_INGDu2']="";
						$_SESSION['EXTRACT_INGAu2']="";
						$_SESSION['EXTRACT_INGMSN2']="";
						$_SESSION['EXTRACT_INGDossier2']="";
						
						$_SESSION['EXTRACT_ECMEMetier']="";
						$_SESSION['EXTRACT_ECMEReference']="";
						$_SESSION['EXTRACT_ECMEType']="";
						$_SESSION['EXTRACT_ECMEDateTERA']="";
						$_SESSION['EXTRACT_ECMEDateTERC']="";
						$_SESSION['EXTRACT_ECMEDu']="";
						$_SESSION['EXTRACT_ECMEAu']="";
						$_SESSION['EXTRACT_ECMEMSN']="";
						$_SESSION['EXTRACT_ECMEDossier']="";
						
						$_SESSION['EXTRACT_ECMEMetier2']="";
						$_SESSION['EXTRACT_ECMEReference2']="";
						$_SESSION['EXTRACT_ECMEType2']="";
						$_SESSION['EXTRACT_ECMEDateTERA2']="";
						$_SESSION['EXTRACT_ECMEDateTERC2']="";
						$_SESSION['EXTRACT_ECMEDu2']="";
						$_SESSION['EXTRACT_ECMEAu2']="";
						$_SESSION['EXTRACT_ECMEMSN2']="";
						$_SESSION['EXTRACT_ECMEDossier2']="";
						
						$_SESSION['EXTRACT_ECMECLIENTClient']="";
						$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERA']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERC']="";
						$_SESSION['EXTRACT_ECMECLIENTDu']="";
						$_SESSION['EXTRACT_ECMECLIENTAu']="";
						$_SESSION['EXTRACT_ECMECLIENTMSN']="";
						$_SESSION['EXTRACT_ECMECLIENTDossier']="";
						
						$_SESSION['EXTRACT_ECMECLIENTClient2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERA2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERC2']="";
						$_SESSION['EXTRACT_ECMECLIENTDu2']="";
						$_SESSION['EXTRACT_ECMECLIENTAu2']="";
						$_SESSION['EXTRACT_ECMECLIENTMSN2']="";
						$_SESSION['EXTRACT_ECMECLIENTDossier2']="";
						
						$_SESSION['EXTRACT_PSCompagnon']="";
						$_SESSION['EXTRACT_PSIQ']="";
						$_SESSION['EXTRACT_PSReference']="";
						$_SESSION['EXTRACT_PSDateTERA']="";
						$_SESSION['EXTRACT_PSDateTERC']="";
						$_SESSION['EXTRACT_PSDu']="";
						$_SESSION['EXTRACT_PSAu']="";
						$_SESSION['EXTRACT_PSMSN']="";
						$_SESSION['EXTRACT_PSDossier']="";
						
						$_SESSION['EXTRACT_PSCompagnon2']="";
						$_SESSION['EXTRACT_PSIQ2']="";
						$_SESSION['EXTRACT_PSReference2']="";
						$_SESSION['EXTRACT_PSDateTERA2']="";
						$_SESSION['EXTRACT_PSDateTERC2']="";
						$_SESSION['EXTRACT_PSDu2']="";
						$_SESSION['EXTRACT_PSAu2']="";
						$_SESSION['EXTRACT_PSMSN2']="";
						$_SESSION['EXTRACT_PSDossier2']="";
						
						$resultPlateforme=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne='".$row[3]."'");
						$nbenregPlateforme=mysqli_num_rows($resultPlateforme);
						if($nbenregPlateforme>0)
						{
							while($donnees=mysqli_fetch_array($resultPlateforme))
							{
								array_push($tableau, $donnees[0]);
							}
						}
						$_SESSION['Id_Plateformes'] = $tableau;
						echo "<html>";
						
						if($_SESSION['NomSP']=="" || $_SESSION['PrenomSP']=="" || $_SESSION['LogSP']=="" || $_SESSION['MdpSP']=="")
						{echo "<body onload='window.location.top=\"".$chemin."/index.php?Cnx=CKIES\";'>";}
						else{
							if($_SESSION['MdpSP']=="aaa01"){echo "<body onload='window.location.href=\"".$chemin."/MotDePasse.php\";'>";}
							else {echo "<body onload='window.location.href=\"".$chemin."/Accueil.php\";'>";}
						}
					}
					else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD2\";'>";}
				}
				else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			}
			else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			mysqli_free_result($result);	// Libération des résultats
			mysqli_close($bdd);			// Fermeture de la connexion
		}
		elseif($_POST["prestation"]=="AISLP" || $_POST["prestation"]=="TT350"){
			$result=mysqli_query($bdd,"SELECT Nom,Prenom,Email,Id FROM new_rh_etatcivil WHERE LoginSP='".$_POST["login"]."' AND MdpSP='".$_POST["motdepasse"]."'");
			$nbenreg=mysqli_num_rows($result);
			if($_POST["login"] <> "" and $_POST["motdepasse"] <> ""){
				if($nbenreg==1){
					//Creation des variables de session
					$row=mysqli_fetch_array($result);
					
					if($_POST["prestation"]=="AISLP"){
						$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=16 ");
					}
					else{
						$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=316 ");
					}
					
					$nbAcces=mysqli_num_rows($resultAcces);
					if($nbAcces>0){
						$rowDroit=mysqli_fetch_array($resultAcces);

						$_SESSION['LogSP']=$_POST["login"];
						$_SESSION['MdpSP']=$_POST["motdepasse"];
						$_SESSION['PrestationSP']=$_POST["prestation"];
						$_SESSION['NomSP']=$row[0];
						$_SESSION['PrenomSP']=$row[1];
						$_SESSION['EmailSP']=$row[2];
						$_SESSION['Id_PersonneSP']=$row['Id'];
						$_SESSION['DroitSP']=$rowDroit['Droit'];
						
						//Variables de sessions pour la recherche
						$tab = array("MSNPage","ReferencePage","ProgrammePage","DateInterventionPage","VacationPage");
						foreach($tab as $tri){
							$_SESSION['Filtre'.$tri]="";
							$_SESSION['Filtre'.$tri."2"]="";
						}
						
						$tab = array("Programme","MSN","Reference","Client","TypeDossier","Priorite","Caec","Section","StatutPREPA","Titre","Poste","Localisation","StatutDossier","DatePrevisionnelleIntervention","DateInterventionDebut","DateInterventionFin","SansDateIntervention","VacationPROD","NumIC","StatutPROD","DateInterventionQDebut","DateInterventionQFin","SansDateInterventionQ","VacationQUALITE","IQ","StatutQUALITE");
						foreach($tab as $tri){
							$_SESSION['Filtre'.$tri]="";
							$_SESSION['Filtre'.$tri."2"]="";
						}
			
						$_SESSION['Page']="0";
						
						//Variable session Tri
						$tab = array("MSN","Vacation","TypeDossier","Reference","Localisation","Titre","StatutPREPA","Poste","DateIntervention","StatutPROD","RetourPROD","StatutQUALITE","RetourQUALITE","NumFI","DateTERA","DateTERC");
						foreach($tab as $tri){
							if($tri=="DateIntervention"){$_SESSION['Tri'.$tri]="ASC";}
							elseif($tri=="Vacation"){$_SESSION['Tri'.$tri]="ASC";}
							else{$_SESSION['Tri'.$tri]="";}
						}
						$_SESSION['TriGeneral']="DateIntervention DESC,Vacation ASC,";

						//Indicateurs
						$_SESSION['Indicateur_MSN']="";
						$_SESSION['Indicateur_Vacation']="";
						$_SESSION['Indicateur_Du']="";
						$_SESSION['Indicateur_Au']="";
						$_SESSION['Indicateur_Poste']="";
						$_SESSION['Indicateur_Client']="";
						
						$_SESSION['Indicateur_MSN2']="";
						$_SESSION['Indicateur_Vacation2']="";
						$_SESSION['Indicateur_Du2']="";
						$_SESSION['Indicateur_Au2']="";
						$_SESSION['Indicateur_Poste2']="";
						$_SESSION['Indicateur_Client2']="";
						
						$_SESSION['EXTRACT_INGIngredient']="";
						$_SESSION['EXTRACT_INGNumLot']="";
						$_SESSION['EXTRACT_INGDatePeremption']="";
						$_SESSION['EXTRACT_INGDateTERA']="";
						$_SESSION['EXTRACT_INGDateTERC']="";
						$_SESSION['EXTRACT_INGDu']="";
						$_SESSION['EXTRACT_INGAu']="";
						$_SESSION['EXTRACT_INGMSN']="";
						$_SESSION['EXTRACT_INGDossier']="";
						
						$_SESSION['EXTRACT_INGIngredient2']="";
						$_SESSION['EXTRACT_INGNumLot2']="";
						$_SESSION['EXTRACT_INGDatePeremption2']="";
						$_SESSION['EXTRACT_INGDateTERA2']="";
						$_SESSION['EXTRACT_INGDateTERC2']="";
						$_SESSION['EXTRACT_INGDu2']="";
						$_SESSION['EXTRACT_INGAu2']="";
						$_SESSION['EXTRACT_INGMSN2']="";
						$_SESSION['EXTRACT_INGDossier2']="";
						
						$_SESSION['EXTRACT_ECMEMetier']="";
						$_SESSION['EXTRACT_ECMEReference']="";
						$_SESSION['EXTRACT_ECMEType']="";
						$_SESSION['EXTRACT_ECMEDateTERA']="";
						$_SESSION['EXTRACT_ECMEDateTERC']="";
						$_SESSION['EXTRACT_ECMEDu']="";
						$_SESSION['EXTRACT_ECMEAu']="";
						$_SESSION['EXTRACT_ECMEMSN']="";
						$_SESSION['EXTRACT_ECMEDossier']="";
						
						$_SESSION['EXTRACT_ECMEMetier2']="";
						$_SESSION['EXTRACT_ECMEReference2']="";
						$_SESSION['EXTRACT_ECMEType2']="";
						$_SESSION['EXTRACT_ECMEDateTERA2']="";
						$_SESSION['EXTRACT_ECMEDateTERC2']="";
						$_SESSION['EXTRACT_ECMEDu2']="";
						$_SESSION['EXTRACT_ECMEAu2']="";
						$_SESSION['EXTRACT_ECMEMSN2']="";
						$_SESSION['EXTRACT_ECMEDossier2']="";
						
						$_SESSION['EXTRACT_ECMECLIENTClient']="";
						$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERA']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERC']="";
						$_SESSION['EXTRACT_ECMECLIENTDu']="";
						$_SESSION['EXTRACT_ECMECLIENTAu']="";
						$_SESSION['EXTRACT_ECMECLIENTMSN']="";
						$_SESSION['EXTRACT_ECMECLIENTDossier']="";
						
						$_SESSION['EXTRACT_ECMECLIENTClient2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERA2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERC2']="";
						$_SESSION['EXTRACT_ECMECLIENTDu2']="";
						$_SESSION['EXTRACT_ECMECLIENTAu2']="";
						$_SESSION['EXTRACT_ECMECLIENTMSN2']="";
						$_SESSION['EXTRACT_ECMECLIENTDossier2']="";
						
						$_SESSION['EXTRACT_PSCompagnon']="";
						$_SESSION['EXTRACT_PSIQ']="";
						$_SESSION['EXTRACT_PSReference']="";
						$_SESSION['EXTRACT_PSDateTERA']="";
						$_SESSION['EXTRACT_PSDateTERC']="";
						$_SESSION['EXTRACT_PSDu']="";
						$_SESSION['EXTRACT_PSAu']="";
						$_SESSION['EXTRACT_PSMSN']="";
						$_SESSION['EXTRACT_PSDossier']="";
						
						$_SESSION['EXTRACT_PSCompagnon2']="";
						$_SESSION['EXTRACT_PSIQ2']="";
						$_SESSION['EXTRACT_PSReference2']="";
						$_SESSION['EXTRACT_PSDateTERA2']="";
						$_SESSION['EXTRACT_PSDateTERC2']="";
						$_SESSION['EXTRACT_PSDu2']="";
						$_SESSION['EXTRACT_PSAu2']="";
						$_SESSION['EXTRACT_PSMSN2']="";
						$_SESSION['EXTRACT_PSDossier2']="";
						
						//Variables de sessions pour les AM
						$_SESSION['AMMSN']="";
						$_SESSION['AMNumAMNC']="";
						$_SESSION['AMOMAssocie']="";
						$_SESSION['AMDu']="";
						$_SESSION['AMAu']="";
						$_SESSION['AMImputationAAA']="";
						$_SESSION['AMNCMajeure']="";
						$_SESSION['AMType']="";
						$_SESSION['AMRecurrence']="";
						
						$_SESSION['AMMSN2']="";
						$_SESSION['AMNumAMNC2']="";
						$_SESSION['AMOMAssocie2']="";
						$_SESSION['AMDu2']="";
						$_SESSION['AMAu2']="";
						$_SESSION['AMImputationAAA2']="";
						$_SESSION['AMNCMajeure2']="";
						$_SESSION['AMType2']="";
						$_SESSION['AMRecurrence2']="";

						$_SESSION['AMModeFiltre']="";
						$_SESSION['AMPage']="0";
						
						//Variable session Tri
						$_SESSION['TriAMMSN']="DESC";
						$_SESSION['TriAMNumAMNC']="";
						$_SESSION['TriAMOMAssocie']="ASC";
						$_SESSION['TriAMDesignation']="";
						$_SESSION['TriAMDate']="";
						$_SESSION['TriAMImputationAAA']="";
						$_SESSION['TriAMNCMajeure']="";
						$_SESSION['TriAMType']="";
						$_SESSION['TriAMRecurrence']="";
						$_SESSION['TriAMGeneral']="MSN DESC,OMAssocie ASC,";
						
						//Variables de sessions pour les CQLB
						$_SESSION['CQLBMSN']="";
						$_SESSION['CQLBNumCQLB']="";
						$_SESSION['CQLBNumCV']="";
						$_SESSION['CQLBLocalisation']="";
						$_SESSION['CQLBImputationAAA']="";
						$_SESSION['CQLBOMAssocie']="";
						$_SESSION['CQLBAMAssociee']="";
						$_SESSION['CQLBDu']="";
						$_SESSION['CQLBAu']="";
						$_SESSION['CQLBType']="";
						$_SESSION['CQLBRecurrence']="";
						
						$_SESSION['CQLBMSN2']="";
						$_SESSION['CQLBNumCQLB2']="";
						$_SESSION['CQLBNumCV2']="";
						$_SESSION['CQLBLocalisation2']="";
						$_SESSION['CQLBImputationAAA2']="";
						$_SESSION['CQLBOMAssocie2']="";
						$_SESSION['CQLBAMAssociee2']="";
						$_SESSION['CQLBDu2']="";
						$_SESSION['CQLBAu2']="";
						$_SESSION['CQLBType2']="";
						$_SESSION['CQLBRecurrence2']="";

						$_SESSION['CQLBModeFiltre']="";
						$_SESSION['CQLBPage']="0";
						
						//Variable session Tri
						$_SESSION['TriCQLBMSN']="DESC";
						$_SESSION['TriCQLBNumCQLB']="ASC";
						$_SESSION['TriCQLBNumCV']="";
						$_SESSION['TriCQLBLocalisation']="";
						$_SESSION['TriCQLBImputationAAA']="";
						$_SESSION['TriCQLBOMAssocie']="";
						$_SESSION['TriCQLBDesignation']="";
						$_SESSION['TriCQLBAMAssocie']="";
						$_SESSION['TriCQLBDate']="";
						$_SESSION['TriCQLBType']="";
						$_SESSION['TriCQLBRecurrence']="";
						$_SESSION['TriCQLBGeneral']="MSN DESC,NumCQLB ASC,";
						
						$resultPlateforme=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne='".$row[3]."'");
						$nbenregPlateforme=mysqli_num_rows($resultPlateforme);
						if($nbenregPlateforme>0)
						{
							while($donnees=mysqli_fetch_array($resultPlateforme))
							{
								array_push($tableau, $donnees[0]);
							}
						}
						$_SESSION['Id_Plateformes'] = $tableau;
						echo "<html>";
						
						if($_SESSION['NomSP']=="" || $_SESSION['PrenomSP']=="" || $_SESSION['LogSP']=="" || $_SESSION['MdpSP']=="")
						{echo "<body onload='window.location.top=\"".$chemin."/index.php?Cnx=CKIES\";'>";}
						else{
							if($_SESSION['MdpSP']=="aaa01"){echo "<body onload='window.location.href=\"".$chemin."/MotDePasse.php\";'>";}
							else {echo "<body onload='window.location.href=\"".$chemin."/Accueil.php\";'>";}
						}
					}
					else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD2\";'>";}
				}
				else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			}
			else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			mysqli_free_result($result);	// Libération des résultats
			mysqli_close($bdd);			// Fermeture de la connexion
		}
		elseif($_POST["prestation"]=="CO330" || $_POST["prestation"]=="CO350" || $_POST["prestation"]=="S-NHHPO" || $_POST["prestation"]=="RSP AAA GmbH" || $_POST["prestation"]=="TEST_PROD"){
			$result=mysqli_query($bdd,"SELECT Nom,Prenom,Email,Id FROM new_rh_etatcivil WHERE LoginSP='".$_POST["login"]."' AND MdpSP='".$_POST["motdepasse"]."'");
			$nbenreg=mysqli_num_rows($result);
			if($_POST["login"] <> "" and $_POST["motdepasse"] <> ""){
				if($nbenreg==1){
					//Creation des variables de session
					$row=mysqli_fetch_array($result);
					
					if($_POST["prestation"]=="CO330"){
						$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=1598 ");
					}
					elseif($_POST["prestation"]=="CO350"){
						$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=1792 ");
					}
					elseif($_POST["prestation"]=="S-NHHPO"){
						$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=1242 ");
					}
					elseif($_POST["prestation"]=="RSP AAA GmbH"){
						$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=1047 ");
					}
					elseif($_POST["prestation"]=="TEST_PROD"){
						$resultAcces=mysqli_query($bdd,"SELECT Droit FROM sp_acces WHERE Id_Personne=".$row['Id']." AND Id_Prestation=-16 ");
					}
					
					$nbAcces=mysqli_num_rows($resultAcces);
					if($nbAcces>0){
						$rowDroit=mysqli_fetch_array($resultAcces);

						$_SESSION['LogSP']=$_POST["login"];
						$_SESSION['MdpSP']=$_POST["motdepasse"];
						$_SESSION['PrestationSP']=$_POST["prestation"];
						$_SESSION['NomSP']=$row[0];
						$_SESSION['PrenomSP']=$row[1];
						$_SESSION['EmailSP']=$row[2];
						$_SESSION['Id_PersonneSP']=$row['Id'];
						$_SESSION['DroitSP']=$rowDroit['Droit'];
						
						//Variables de sessions pour la recherche
						$tab = array("MSNPage","ReferencePage","ProgrammePage","DateInterventionPage","VacationPage");
						foreach($tab as $tri){
							$_SESSION['Filtre'.$tri]="";
							$_SESSION['Filtre'.$tri."2"]="";
						}
						
						$tab = array("Programme","MSN","Reference","Client","TypeDossier","Priorite","Caec","Section","StatutPREPA","Titre","Poste","Localisation","StatutDossier","DatePrevisionnelleIntervention","DateInterventionDebut","DateInterventionFin","SansDateIntervention","VacationPROD","NumIC","StatutPROD","DateInterventionQDebut","DateInterventionQFin","SansDateInterventionQ","VacationQUALITE","IQ","StatutQUALITE","CT","TempsPasse","Operateurs");
						foreach($tab as $tri){
							$_SESSION['Filtre'.$tri]="";
							$_SESSION['Filtre'.$tri."2"]="";
						}
			
						$_SESSION['Page']="0";
						
						//Variable session Tri
						$tab = array("MSN","Vacation","TypeDossier","TypeTravail","Reference","Localisation","Titre","StatutPREPA","Poste","DateIntervention","StatutPROD","RetourPROD","StatutQUALITE","RetourQUALITE","NumFI","DateTERA","DateTERC","CT","TempsPasse","Operateurs");
						foreach($tab as $tri){
							if($tri=="DateIntervention"){$_SESSION['Tri'.$tri]="ASC";}
							elseif($tri=="Vacation"){$_SESSION['Tri'.$tri]="ASC";}
							else{$_SESSION['Tri'.$tri]="";}
						}
						$_SESSION['TriGeneral']="DateIntervention DESC,Vacation ASC,";

						//Indicateurs
						$_SESSION['Indicateur_MSN']="";
						$_SESSION['Indicateur_Vacation']="";
						$_SESSION['Indicateur_Du']="";
						$_SESSION['Indicateur_Au']="";
						$_SESSION['Indicateur_Poste']="";
						$_SESSION['Indicateur_Client']="";
						
						$_SESSION['Indicateur_MSN2']="";
						$_SESSION['Indicateur_Vacation2']="";
						$_SESSION['Indicateur_Du2']="";
						$_SESSION['Indicateur_Au2']="";
						$_SESSION['Indicateur_Poste2']="";
						$_SESSION['Indicateur_Client2']="";
						
						$_SESSION['EXTRACT_INGIngredient']="";
						$_SESSION['EXTRACT_INGNumLot']="";
						$_SESSION['EXTRACT_INGDatePeremption']="";
						$_SESSION['EXTRACT_INGDateTERA']="";
						$_SESSION['EXTRACT_INGDateTERC']="";
						$_SESSION['EXTRACT_INGDu']="";
						$_SESSION['EXTRACT_INGAu']="";
						$_SESSION['EXTRACT_INGMSN']="";
						$_SESSION['EXTRACT_INGDossier']="";
						
						$_SESSION['EXTRACT_INGIngredient2']="";
						$_SESSION['EXTRACT_INGNumLot2']="";
						$_SESSION['EXTRACT_INGDatePeremption2']="";
						$_SESSION['EXTRACT_INGDateTERA2']="";
						$_SESSION['EXTRACT_INGDateTERC2']="";
						$_SESSION['EXTRACT_INGDu2']="";
						$_SESSION['EXTRACT_INGAu2']="";
						$_SESSION['EXTRACT_INGMSN2']="";
						$_SESSION['EXTRACT_INGDossier2']="";
						
						$_SESSION['EXTRACT_ECMEMetier']="";
						$_SESSION['EXTRACT_ECMEReference']="";
						$_SESSION['EXTRACT_ECMEType']="";
						$_SESSION['EXTRACT_ECMEDateTERA']="";
						$_SESSION['EXTRACT_ECMEDateTERC']="";
						$_SESSION['EXTRACT_ECMEDu']="";
						$_SESSION['EXTRACT_ECMEAu']="";
						$_SESSION['EXTRACT_ECMEMSN']="";
						$_SESSION['EXTRACT_ECMEDossier']="";
						
						$_SESSION['EXTRACT_ECMEMetier2']="";
						$_SESSION['EXTRACT_ECMEReference2']="";
						$_SESSION['EXTRACT_ECMEType2']="";
						$_SESSION['EXTRACT_ECMEDateTERA2']="";
						$_SESSION['EXTRACT_ECMEDateTERC2']="";
						$_SESSION['EXTRACT_ECMEDu2']="";
						$_SESSION['EXTRACT_ECMEAu2']="";
						$_SESSION['EXTRACT_ECMEMSN2']="";
						$_SESSION['EXTRACT_ECMEDossier2']="";
						
						$_SESSION['EXTRACT_ECMECLIENTClient']="";
						$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERA']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERC']="";
						$_SESSION['EXTRACT_ECMECLIENTDu']="";
						$_SESSION['EXTRACT_ECMECLIENTAu']="";
						$_SESSION['EXTRACT_ECMECLIENTMSN']="";
						$_SESSION['EXTRACT_ECMECLIENTDossier']="";
						
						$_SESSION['EXTRACT_ECMECLIENTClient2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateEtalonnage2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERA2']="";
						$_SESSION['EXTRACT_ECMECLIENTDateTERC2']="";
						$_SESSION['EXTRACT_ECMECLIENTDu2']="";
						$_SESSION['EXTRACT_ECMECLIENTAu2']="";
						$_SESSION['EXTRACT_ECMECLIENTMSN2']="";
						$_SESSION['EXTRACT_ECMECLIENTDossier2']="";
						
						$_SESSION['EXTRACT_PSCompagnon']="";
						$_SESSION['EXTRACT_PSIQ']="";
						$_SESSION['EXTRACT_PSReference']="";
						$_SESSION['EXTRACT_PSDateTERA']="";
						$_SESSION['EXTRACT_PSDateTERC']="";
						$_SESSION['EXTRACT_PSDu']="";
						$_SESSION['EXTRACT_PSAu']="";
						$_SESSION['EXTRACT_PSMSN']="";
						$_SESSION['EXTRACT_PSDossier']="";
						
						$_SESSION['EXTRACT_PSCompagnon2']="";
						$_SESSION['EXTRACT_PSIQ2']="";
						$_SESSION['EXTRACT_PSReference2']="";
						$_SESSION['EXTRACT_PSDateTERA2']="";
						$_SESSION['EXTRACT_PSDateTERC2']="";
						$_SESSION['EXTRACT_PSDu2']="";
						$_SESSION['EXTRACT_PSAu2']="";
						$_SESSION['EXTRACT_PSMSN2']="";
						$_SESSION['EXTRACT_PSDossier2']="";
						
						//Variables de sessions pour les AM
						$_SESSION['AMMSN']="";
						$_SESSION['AMNumAMNC']="";
						$_SESSION['AMOMAssocie']="";
						$_SESSION['AMDu']="";
						$_SESSION['AMAu']="";
						$_SESSION['AMImputationAAA']="";
						$_SESSION['AMNCMajeure']="";
						$_SESSION['AMType']="";
						$_SESSION['AMRecurrence']="";
						
						$_SESSION['AMMSN2']="";
						$_SESSION['AMNumAMNC2']="";
						$_SESSION['AMOMAssocie2']="";
						$_SESSION['AMDu2']="";
						$_SESSION['AMAu2']="";
						$_SESSION['AMImputationAAA2']="";
						$_SESSION['AMNCMajeure2']="";
						$_SESSION['AMType2']="";
						$_SESSION['AMRecurrence2']="";

						$_SESSION['AMModeFiltre']="";
						$_SESSION['AMPage']="0";
						
						//Variable session Tri
						$_SESSION['TriAMMSN']="DESC";
						$_SESSION['TriAMNumAMNC']="";
						$_SESSION['TriAMOMAssocie']="ASC";
						$_SESSION['TriAMDesignation']="";
						$_SESSION['TriAMDate']="";
						$_SESSION['TriAMImputationAAA']="";
						$_SESSION['TriAMNCMajeure']="";
						$_SESSION['TriAMType']="";
						$_SESSION['TriAMRecurrence']="";
						$_SESSION['TriAMGeneral']="MSN DESC,OMAssocie ASC,";
						
						//Variables de sessions pour les CQLB
						$_SESSION['CQLBMSN']="";
						$_SESSION['CQLBNumCQLB']="";
						$_SESSION['CQLBNumCV']="";
						$_SESSION['CQLBLocalisation']="";
						$_SESSION['CQLBImputationAAA']="";
						$_SESSION['CQLBOMAssocie']="";
						$_SESSION['CQLBAMAssociee']="";
						$_SESSION['CQLBDu']="";
						$_SESSION['CQLBAu']="";
						$_SESSION['CQLBType']="";
						$_SESSION['CQLBRecurrence']="";
						
						$_SESSION['CQLBMSN2']="";
						$_SESSION['CQLBNumCQLB2']="";
						$_SESSION['CQLBNumCV2']="";
						$_SESSION['CQLBLocalisation2']="";
						$_SESSION['CQLBImputationAAA2']="";
						$_SESSION['CQLBOMAssocie2']="";
						$_SESSION['CQLBAMAssociee2']="";
						$_SESSION['CQLBDu2']="";
						$_SESSION['CQLBAu2']="";
						$_SESSION['CQLBType2']="";
						$_SESSION['CQLBRecurrence2']="";

						$_SESSION['CQLBModeFiltre']="";
						$_SESSION['CQLBPage']="0";
						
						//Variable session Tri
						$_SESSION['TriCQLBMSN']="DESC";
						$_SESSION['TriCQLBNumCQLB']="ASC";
						$_SESSION['TriCQLBNumCV']="";
						$_SESSION['TriCQLBLocalisation']="";
						$_SESSION['TriCQLBImputationAAA']="";
						$_SESSION['TriCQLBOMAssocie']="";
						$_SESSION['TriCQLBDesignation']="";
						$_SESSION['TriCQLBAMAssocie']="";
						$_SESSION['TriCQLBDate']="";
						$_SESSION['TriCQLBType']="";
						$_SESSION['TriCQLBRecurrence']="";
						$_SESSION['TriCQLBGeneral']="MSN DESC,NumCQLB ASC,";
						
						$resultPlateforme=mysqli_query($bdd,"SELECT Id_Plateforme FROM new_competences_personne_plateforme WHERE Id_Personne='".$row[3]."'");
						$nbenregPlateforme=mysqli_num_rows($resultPlateforme);
						if($nbenregPlateforme>0)
						{
							while($donnees=mysqli_fetch_array($resultPlateforme))
							{
								array_push($tableau, $donnees[0]);
							}
						}
						$_SESSION['Id_Plateformes'] = $tableau;
						echo "<html>";
						
						if($_SESSION['NomSP']=="" || $_SESSION['PrenomSP']=="" || $_SESSION['LogSP']=="" || $_SESSION['MdpSP']=="")
						{echo "<body onload='window.location.top=\"".$chemin."/index.php?Cnx=CKIES\";'>";}
						else{
							if($_SESSION['MdpSP']=="aaa01"){echo "<body onload='window.location.href=\"".$chemin."/MotDePasse.php\";'>";}
							else {echo "<body onload='window.location.href=\"".$chemin."/Accueil.php\";'>";}
						}
					}
					else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD2\";'>";}
				}
				else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			}
			else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
			mysqli_free_result($result);	// Libération des résultats
			mysqli_close($bdd);			// Fermeture de la connexion
		}
		else{echo "<body onload='window.location.href=\"".$chemin."/index.php?Cnx=BAD\";'>";}
?>
</body>
</html>
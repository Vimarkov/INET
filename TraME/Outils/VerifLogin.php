<?php
	require("Connexioni.php");
	require("Fonctions.php");
	
	
	$tableau = array(); 
	// Exécuter des requêtes SQL
	$result=mysqli_query($bdd,"SELECT Nom,Prenom,Id FROM new_rh_etatcivil WHERE LoginTrame='".$_POST["login"]."' AND MdpTrame='".$_POST["motdepasse"]."'");
	$nbenreg=mysqli_num_rows($result);
	if($_POST["login"] <> "" and $_POST["motdepasse"] <> ""){
		if($nbenreg==1){
			//Creation des variables de session
			$row=mysqli_fetch_array($result);
			
			$resultAcces=mysqli_query($bdd,"SELECT Droit, Id_Prestation FROM trame_acces WHERE Id_Personne=".$row['Id']." ");
			$nbAcces=mysqli_num_rows($resultAcces);
			
			$resultAdmin=mysqli_query($bdd,"SELECT Id FROM trame_admin WHERE Id_Personne=".$row['Id']." ");
			$nbAdmin=mysqli_num_rows($resultAdmin);
			$admin="0";
			if($nbAdmin>0){$admin="1";}
			if($nbAcces>0 || $nbAdmin>0){
				session_cache_limiter('private');

				// Configure le délai d'expiration à 30 minutes
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
				
				$_SESSION['Langue']=$_GET['L'];
				$_SESSION['LogTR']=$_POST["login"];
				$_SESSION['MdpTR']=$_POST["motdepasse"];
				$_SESSION['NomTR']=$row['Nom'];
				$_SESSION['PrenomTR']=$row['Prenom'];
				$_SESSION['Id_PersonneTR']=$row['Id'];
				$_SESSION['Id_PrestationTR']=0;
				$_SESSION['Id_PrestationExtranet']=0;
				if($nbAcces>0){
					if($nbAdmin>0){$req="SELECT Id_PrestationTraME FROM new_rh_etatcivil WHERE Id_PrestationTraME IN (SELECT Id FROM trame_prestation) AND Id=".$row['Id'];}
					else{$req="SELECT Id_PrestationTraME FROM new_rh_etatcivil WHERE Id_PrestationTraME IN (SELECT Id_Prestation FROM trame_acces WHERE Id_Personne=".$row['Id'].") AND Id=".$row['Id'];}
					$resultPresta=mysqli_query($bdd,$req);
					$nbPresta=mysqli_num_rows($resultPresta);
					if($nbPresta>0){
						$rowPresta=mysqli_fetch_array($resultPresta);
						$resultAcces=mysqli_query($bdd,"SELECT Droit, Id_Prestation FROM trame_acces WHERE Id_Prestation=".$rowPresta['Id_PrestationTraME']." AND Id_Personne=".$row['Id']." ");
						$nbAcces=mysqli_num_rows($resultAcces);
						$rowDroit=mysqli_fetch_array($resultAcces);
						
						$_SESSION['Id_PrestationTR']=$rowPresta['Id_PrestationTraME'];
						$req="SELECT Id_PrestationExtra FROM trame_prestation WHERE Id=".$_SESSION['Id_PrestationTR'];
						$resultPrestaTR=mysqli_query($bdd,$req);
						$nbPrestaTR=mysqli_num_rows($resultPrestaTR);
						if($nbPrestaTR>0){
							$rowPrestaTR=mysqli_fetch_array($resultPrestaTR);
							$_SESSION['Id_PrestationExtranet']=$rowPrestaTR['Id_PrestationExtra'];
						}
						
						if($rowDroit['Droit']==""){$_SESSION['DroitTR']="00000".$admin;}
						else{$_SESSION['DroitTR']=$rowDroit['Droit'].$admin;}
					}
					else{
						$rowDroit=mysqli_fetch_array($resultAcces);
						$_SESSION['Id_PrestationTR']=$rowDroit['Id_Prestation'];
						$req="SELECT Id_PrestationExtra FROM trame_prestation WHERE Id=".$_SESSION['Id_PrestationTR'];
						$resultPrestaTR=mysqli_query($bdd,$req);
						$nbPrestaTR=mysqli_num_rows($resultPrestaTR);
						if($nbPrestaTR>0){
							$rowPrestaTR=mysqli_fetch_array($resultPrestaTR);
							$_SESSION['Id_PrestationExtranet']=$rowPrestaTR['Id_PrestationExtra'];
						}
						$_SESSION['DroitTR']=$rowDroit['Droit'].$admin;
					}
				}
				else{
					$_SESSION['DroitTR']="00000".$admin;
				}
				$_SESSION['Formulaire']="Production/Production.php";
				$_SESSION['RappelAC']="";
				
				//VARIABLES SESSION Production
				$tab =array("Reference","DateDebut","DateFin","WP","FamilleTache","Tache","MotCles","Preparateur","Controleur","Statut","PageDateDebut","PageDateFin","PagePreparateur","PageWP");
				foreach($tab as $value){
					$req="SELECT Valeur, Valeur2 FROM trame_parametrage WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_Personne=".$_SESSION['Id_PersonneTR']." AND Type='Filtre' AND Page='Production' AND Variable='".$value."' ";
					$resultTest=mysqli_query($bdd,$req);
					$nbTest=mysqli_num_rows($resultTest);
					if($nbTest==0){
						if($value=="Preparateur"){
							if(substr($_SESSION['DroitTR'],1,1)=='1' || substr($_SESSION['DroitTR'],3,1)=='1' || substr($_SESSION['DroitTR'],2,1)=='1'){
								$_SESSION['PROD_'.$value]="";
								$_SESSION['PROD_'.$value.'2']="";
							}
							else{
								$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('preparateur','".$_SESSION['Id_PersonneTR']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
								$_SESSION['PROD_'.$value]=$_SESSION['NomTR']." ".$_SESSION['PrenomTR'].$valeur;
								$_SESSION['PROD_'.$value.'2']=$_SESSION['Id_PersonneTR'].";";
							}
						}
						elseif($value=="Statut"){
							if($_GET['L']=="EN"){
								$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','EN COURS;IN PROGRESS')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
								$_SESSION['PROD_'.$value]="IN PROGRESS".$valeur;
							}
							else{
								$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','EN COURS;EN COURS')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
								$_SESSION['PROD_'.$value]="EN COURS".$valeur;
							}
							if($_GET['L']=="EN"){
								$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','BLOQUE;BLOQUED')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
								$_SESSION['PROD_'.$value]="BLOCKED".$valeur;
							}
							else{
								$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','BLOQUE;BLOQUED')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
								$_SESSION['PROD_'.$value]="BLOQUE".$valeur;
							}
							if($_GET['L']=="EN"){
								$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','AC;AUTO CONTROL')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
								$_SESSION['PROD_'.$value].="AUTO CONTROL".$valeur;
							}
							else{
								$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','AC;AUTO-CONTROLE')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
								$_SESSION['PROD_'.$value].="AUTO-CONTROLE".$valeur;
							}
							if($_GET['L']=="EN"){
								$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','REC;CONTROL AGAIN')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
								$_SESSION['PROD_'.$value].="CONTROL AGAIN".$valeur;
							}
							else{
								$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','REC;RECONTROLE')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
								$_SESSION['PROD_'.$value].="RECONTROLE".$valeur;
							}
							if($_GET['L']=="EN"){
								$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','CONTROLE;CONTROL')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
								$_SESSION['PROD_'.$value].="CONTROL".$valeur;
							}
							else{
								$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','CONTROLE;CONTROLE')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
								$_SESSION['PROD_'.$value].="CONTROLE".$valeur;
							}
							if($_GET['L']=="EN"){
								$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','A VALIDER;TO BE VALIDATED')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
								$_SESSION['PROD_'.$value].="TO BE VALIDATED".$valeur;
							}
							else{
								$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','A VALIDER;A VALIDER')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
								$_SESSION['PROD_'.$value].="A VALIDER".$valeur;
							}
							$_SESSION['PROD_'.$value.'2']="BLOQUE;EN COURS;AC;CONTROLE;A VALIDER;REC;";
						}
						else{
							$_SESSION['PROD_'.$value]="";
							$_SESSION['PROD_'.$value.'2']="";
						}
						
						$req="INSERT INTO trame_parametrage (Id_Prestation,Id_Personne,Type,Page,Variable,Valeur,Valeur2) VALUES (".$_SESSION['Id_PrestationTR'].",".$_SESSION['Id_PersonneTR'].",'Filtre','Production','".$value."','".addslashes($_SESSION['PROD_'.$value])."','".addslashes($_SESSION['PROD_'.$value.'2'])."') ";
						$resultTest=mysqli_query($bdd,$req);
					}
					else{
						$rowValeur=mysqli_fetch_array($resultTest);
						$_SESSION['PROD_'.$value]=stripslashes($rowValeur['Valeur']);
						$_SESSION['PROD_'.$value.'2']=stripslashes($rowValeur['Valeur2']);
					}
				}
				
				$tab =array("Reference","Date","WP","FamilleTache","Tache","Preparateur","Statut","TempsPasse","TempsAlloue","Responsable","RaisonRefus","Delai","CommentaireDelai","Commentaire","Controleur","General");
				foreach($tab as $value){
					$req="SELECT Valeur, Valeur2 FROM trame_parametrage WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_Personne=".$_SESSION['Id_PersonneTR']." AND Type='Tri' AND Page='Production' AND Variable='".$value."' ";
					$resultTest=mysqli_query($bdd,$req);
					$nbTest=mysqli_num_rows($resultTest);
					if($nbTest==0){
						if($value=="General"){$_SESSION['TriPROD_'.$value]="Id DESC,";}
						else{$_SESSION['TriPROD_'.$value]="";}

						$req="INSERT INTO trame_parametrage (Id_Prestation,Id_Personne,Type,Page,Variable,Valeur) VALUES (".$_SESSION['Id_PrestationTR'].",".$_SESSION['Id_PersonneTR'].",'Tri','Production','".$value."','".addslashes($_SESSION['TriPROD_'.$value])."') ";
						$resultTest=mysqli_query($bdd,$req);
					}
					else{
						$rowValeur=mysqli_fetch_array($resultTest);
						$_SESSION['TriPROD_'.$value]=stripslashes($rowValeur['Valeur']);
					}
				}
				
				$tabChamps=array("Reference_8_1","Date_8_1","WP_10_1","FamilleTache_10_1","Tache_15_1","Statut_8_1","TempsAlloue_6_1","TempsPasse_6_1","Preparateur_10_1","Controleur_10_1","InfosComplementaires_15_1","Responsable_10_0","RaisonRefus_10_0","Delai_10_0","CommentaireDelai_10_0","Commentaire_10_0");
				foreach($tabChamps as $value){
					$tabCh=explode("_",$value);
					$req="SELECT Champ, Taille, Visible FROM trame_champsaffichage 
						WHERE Id_Personne=".$_SESSION['Id_PersonneTR']." AND Champ='".$tabCh[0]."' AND Page='Production' AND Suppr=0 ";
					$resultTest=mysqli_query($bdd,$req);
					$nbTest=mysqli_num_rows($resultTest);
					if($nbTest==0){
						$req="INSERT INTO trame_champsaffichage (Id_Personne,Page,Champ,Taille,Visible) 
							VALUES (".$_SESSION['Id_PersonneTR'].",'Production','".$tabCh[0]."',".$tabCh[1].",".$tabCh[2].") ";
						$resultTest=mysqli_query($bdd,$req);
						$_SESSION['ChampsPROD_'.$tabCh[0]]=$value;
					}
					else{
						$rowValeur=mysqli_fetch_array($resultTest);
						$_SESSION['ChampsPROD_'.$rowValeur['Champ']]=$rowValeur['Champ']."_".$rowValeur['Taille']."_".$rowValeur['Visible'];
					}
				}
				
				$tabChamps=array("Reference_7_1","Date_7_1","WP_7_1","FamilleTache_7_1","Tache_14_1","Delai_4_0","Statut_5_1","TempsAlloue_5_1","TempsPasse_6_1","Preparateur_10_1","Controleur_10_1","InfosComplementaires_13_1","Commentaire_13_1","Responsable_10_0","RaisonRefus_10_0","CommentaireDelai_10_0");
				foreach($tabChamps as $value){
					$tabCh=explode("_",$value);
					$req="SELECT Champ, Taille, Visible FROM trame_champsaffichage 
						WHERE Id_Personne=".$_SESSION['Id_PersonneTR']." AND Champ='".$tabCh[0]."' AND Page='Validation' AND Suppr=0 ";
					$resultTest=mysqli_query($bdd,$req);
					$nbTest=mysqli_num_rows($resultTest);
					if($nbTest==0){
						$req="INSERT INTO trame_champsaffichage (Id_Personne,Page,Champ,Taille,Visible) 
							VALUES (".$_SESSION['Id_PersonneTR'].",'Validation','".$tabCh[0]."',".$tabCh[1].",".$tabCh[2].") ";
						$resultTest=mysqli_query($bdd,$req);
						$_SESSION['ChampsVAL_'.$tabCh[0]]=$value;
					}
					else{
						$rowValeur=mysqli_fetch_array($resultTest);
						$_SESSION['ChampsVAL_'.$rowValeur['Champ']]=$rowValeur['Champ']."_".$rowValeur['Taille']."_".$rowValeur['Visible'];
					}
				}
				
				$_SESSION['PROD_ModeFiltre']="oui";
				$_SESSION['PROD_Page']="0";
				$_SESSION['PROD_NbLigne']=50;

				//VARIABLES SESSION Anomalies
				$_SESSION['ANOM_Reference']="";
				$_SESSION['ANOM_DateDebut']="";
				$_SESSION['ANOM_DateFin']="";
				$_SESSION['ANOM_WP']="";
				$_SESSION['ANOM_Probleme']="";
				$_SESSION['ANOM_Origine']="";
				$_SESSION['ANOM_Responsable']="";
				$_SESSION['ANOM_Createur']="";
				$_SESSION['ANOM_FamilleErreur']="";
				
				$_SESSION['ANOM_Reference2']="";
				$_SESSION['ANOM_DateDebut2']="";
				$_SESSION['ANOM_DateFin2']="";
				$_SESSION['ANOM_WP2']="";
				$_SESSION['ANOM_Probleme2']="";
				$_SESSION['ANOM_Origine2']="";
				$_SESSION['ANOM_Responsable2']="";
				$_SESSION['ANOM_Createur2']="";
				$_SESSION['ANOM_FamilleErreur2']="";

				$_SESSION['ANOM_ModeFiltre']="oui";
				$_SESSION['ANOM_Page']="0";
				
				$_SESSION['TriANOM_Reference']="";
				$_SESSION['TriANOM_Date']="DESC";
				$_SESSION['TriANOM_WP']="";
				$_SESSION['TriANOM_Probleme']="";
				$_SESSION['TriANOM_Origine']="";
				$_SESSION['TriANOM_Responsable']="";
				$_SESSION['TriANOM_Createur']="";
				$_SESSION['TriANOM_FamilleErreur1']="";
				$_SESSION['TriANOM_FamilleErreur2']="";
				$_SESSION['TriANOM_DatePrevisionnelle']="";
				$_SESSION['TriANOM_DateReport']="";
				$_SESSION['TriANOM_DateCloture']="";
				$_SESSION['TriANOM_General']="DateAnomalie DESC,";
				
				//VARIABLES SESSION Hors délais
				$_SESSION['HorsDelais_Reference']="";
				$_SESSION['HorsDelais_DateDebut']="";
				$_SESSION['HorsDelais_DateFin']="";
				$_SESSION['HorsDelais_WP']="";
				$_SESSION['HorsDelais_Tache']="";
				$_SESSION['HorsDelais_Statut']="";
				$_SESSION['HorsDelais_Preparateur']="";
				$_SESSION['HorsDelais_MotCles']="";
				$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('responsableDelais','0')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				if($_GET['L']=="EN"){
					$_SESSION['HorsDelais_RespDelais']="(empty)".$valeur;
				}
				else{
					$_SESSION['HorsDelais_RespDelais']="(vide)".$valeur;
				}
				$_SESSION['HorsDelais_CauseDelais']="";
				
				$_SESSION['HorsDelais_Reference2']="";
				$_SESSION['HorsDelais_DateDebut2']="";
				$_SESSION['HorsDelais_DateFin2']="";
				$_SESSION['HorsDelais_WP2']="";
				$_SESSION['HorsDelais_Tache2']="";
				$_SESSION['HorsDelais_Statut2']="";
				$_SESSION['HorsDelais_Preparateur2']="";
				$_SESSION['HorsDelais_MotCles2']="";
				$_SESSION['HorsDelais_RespDelais2']="0;";
				$_SESSION['HorsDelais_CauseDelais2']="";
				
				$_SESSION['HorsDelais_PageDateDebut2']="";
				$_SESSION['HorsDelais_PageDateFin2']="";
				$_SESSION['HorsDelais_PagePreparateur2']="";
				$_SESSION['HorsDelais_PageWP2']="";
				
				$_SESSION['HorsDelais_ModeFiltre']="oui";
				$_SESSION['HorsDelais_Page']="0";
				
				$_SESSION['TriHorsDelais_Reference']="ASC";
				$_SESSION['TriHorsDelais_Date']="ASC";
				$_SESSION['TriHorsDelais_WP']="";
				$_SESSION['TriHorsDelais_Tache']="";
				$_SESSION['TriHorsDelais_StatutDelais']="";
				$_SESSION['TriHorsDelais_RespDelais']="";
				$_SESSION['TriHorsDelais_CauseDelais']="";
				$_SESSION['TriHorsDelais_Statut']="";
				$_SESSION['TriHorsDelais_Preparateur']="";
				$_SESSION['TriHorsDelais_General']="DatePreparateur ASC,Designation ASC,";

				//VARIABLES SESSION Validation
				$_SESSION['VALI_ModeFiltre']="oui";
				$_SESSION['VALI_Page']="0";
				$_SESSION['VALI_NbLigne']=50;
				$_SESSION['VALI_Ids']="";
				$tab =array("Reference","DateDebut","DateFin","WP","Tache","MotCles","Preparateur","Controleur","Statut","Delai","PageDateDebut","PageDateFin","PagePreparateur","PageWP","FamilleTache");
				foreach($tab as $value){
					$req="SELECT Valeur, Valeur2 FROM trame_parametrage WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_Personne=".$_SESSION['Id_PersonneTR']." AND Type='Filtre' AND Page='Validation' AND Variable='".$value."' ";
					$resultTest=mysqli_query($bdd,$req);
					$nbTest=mysqli_num_rows($resultTest);
					if($nbTest==0){
						if($value=="Statut"){
							if($_GET['L']=="EN"){
								$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','A VALIDER;TO BE VALIDATED')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
								$_SESSION['VALI_'.$value].="TO BE VALIDATED".$valeur;
							}
							else{
								$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statutPROD','A VALIDER;A VALIDER')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
								$_SESSION['VALI_'.$value].="A VALIDER".$valeur;
							}
							
							$_SESSION['VALI_'.$value.'2']="A VALIDER;";
						}
						else{
							$_SESSION['VALI_'.$value]="";
							$_SESSION['VALI_'.$value.'2']="";
						}
						
						$req="INSERT INTO trame_parametrage (Id_Prestation,Id_Personne,Type,Page,Variable,Valeur,Valeur2) VALUES (".$_SESSION['Id_PrestationTR'].",".$_SESSION['Id_PersonneTR'].",'Filtre','Validation','".$value."','".addslashes($_SESSION['VALI_'.$value])."','".addslashes($_SESSION['VALI_'.$value.'2'])."') ";
						$resultTest=mysqli_query($bdd,$req);
					}
					else{
						$rowValeur=mysqli_fetch_array($resultTest);
						$_SESSION['VALI_'.$value]=stripslashes($rowValeur['Valeur']);
						$_SESSION['VALI_'.$value.'2']=stripslashes($rowValeur['Valeur2']);
					}
				}
				
				$tab =array("Reference","Date","WP","FamilleTache","Tache","Preparateur","Statut","TempsPasse","TempsAlloue","Responsable","RaisonRefus","Delai","CommentaireDelai","Commentaire","Controleur","General");
				foreach($tab as $value){
					$req="SELECT Valeur, Valeur2 FROM trame_parametrage WHERE Id_Prestation=".$_SESSION['Id_PrestationTR']." AND Id_Personne=".$_SESSION['Id_PersonneTR']." AND Type='Tri' AND Page='Validation' AND Variable='".$value."' ";
					$resultTest=mysqli_query($bdd,$req);
					$nbTest=mysqli_num_rows($resultTest);
					if($nbTest==0){
						if($value=="General"){$_SESSION['TriVALI_'.$value]="DatePreparateur ASC,Designation ASC,";}
						else{$_SESSION['TriVALI_'.$value]="";}

						$req="INSERT INTO trame_parametrage (Id_Prestation,Id_Personne,Type,Page,Variable,Valeur) VALUES (".$_SESSION['Id_PrestationTR'].",".$_SESSION['Id_PersonneTR'].",'Tri','Validation','".$value."','".addslashes($_SESSION['TriVALI_'.$value])."') ";
						$resultTest=mysqli_query($bdd,$req);
					}
					else{
						$rowValeur=mysqli_fetch_array($resultTest);
						$_SESSION['TriVALI_'.$value]=stripslashes($rowValeur['Valeur']);
					}
				}
				
				//VARIABLES SESSION FAQ
				$_SESSION['CategorieFAQ']="";
				$_SESSION['QuestionFAQ']="";
				$_SESSION['ReponseFAQ']="";
				
				$_SESSION['CategorieFAQ2']="";
				$_SESSION['QuestionFAQ2']="";
				$_SESSION['ReponseFAQ2']="";
				
				$_SESSION['ModeFiltreFAQ']="oui";
				$_SESSION['PageFAQ']="0";
				
				$_SESSION['TriCategorieFAQ']="ASC";
				$_SESSION['TriQuestionFAQ']="ASC";
				$_SESSION['TriReponseFAQ']="";
				$_SESSION['TriGeneralFAQ']="Categorie ASC,Question ASC,";
				
				//VARIBALES SESSION TABLEAU DE BORD
				$debutSemaine=date("Y-m-d",strtotime("last Monday"));
				$btnD="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('dateDebut','".AfficheDateFR($debutSemaine)."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				$finSemaine=date("Y-m-d",strtotime("next sunday"));
				$btnF="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('dateFin','".AfficheDateFR($finSemaine)."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				
				$_SESSION['TDB_DateDebut']=AfficheDateFR($debutSemaine).$btnD;
				$_SESSION['TDB_DateFin']=AfficheDateFR($finSemaine).$btnF;
				$_SESSION['TDB_WP']="";
				$_SESSION['TDB_Preparateur']="";
				
				$_SESSION['TDB_DateDebut2']=AfficheDateFR($debutSemaine);
				$_SESSION['TDB_DateFin2']=AfficheDateFR($finSemaine);
				$_SESSION['TDB_WP2']="";
				$_SESSION['TDB_Preparateur2']="";
				
				$_SESSION['TDB_ModeFiltre']="oui";
				$_SESSION['TDB_Page']="0";
				
				$_SESSION['TriTDB_WP']="ASC";
				$_SESSION['TriTDB_Valide']="";
				$_SESSION['TriTDB_AValider']="";
				$_SESSION['TriTDB_EnCours']="";
				$_SESSION['TriTDB_Refuse']="";
				$_SESSION['TriTDB_TempsPasse']="";
				$_SESSION['TriTDB_Productivite']="";
				$_SESSION['TriTDB_General']="Workpackage ASC,";
				
				$_SESSION['TriTDB_Personne']="ASC";
				$_SESSION['TriTDB_Valide2']="";
				$_SESSION['TriTDB_AValider2']="";
				$_SESSION['TriTDB_EnCours2']="";
				$_SESSION['TriTDB_Refuse2']="";
				$_SESSION['TriTDB_TempsPasse2']="";
				$_SESSION['TriTDB_Productivite2']="";
				$_SESSION['TriTDB_General2']="Personne ASC,";
				
				$_SESSION['TriTDB_PersonneAC']="ASC";
				$_SESSION['TriTDB_AC']="";
				$_SESSION['TriTDB_CONT']="";
				$_SESSION['TriTDB_REC']="";
				$_SESSION['TriTDB_GeneralAC']="Personne ASC,";
				
				//VARIALES SESSION EXTRACT
				$debutSemaine=date("Y-m-d",strtotime("last Monday"));
				$btnD="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('dateDebut','".AfficheDateFR($debutSemaine)."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				$finSemaine=date("Y-m-d",strtotime("next sunday"));
				$btnF="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('dateFin','".AfficheDateFR($finSemaine)."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				
				$_SESSION['EXTRACT_DateDebut']=AfficheDateFR($debutSemaine).$btnD;
				$_SESSION['EXTRACT_DateFin']=AfficheDateFR($finSemaine).$btnF;
				if($_GET['L']=="EN"){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statut','VALIDE;VALIDATED')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['EXTRACT_Statut']="VALIDATED".$valeur;
				}
				else{
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('statut','VALIDE;VALIDE')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['EXTRACT_Statut']="VALIDE".$valeur;
				}
				$_SESSION['EXTRACT_WP']="";
				$_SESSION['EXTRACT_Controle']="";
			
				$_SESSION['EXTRACT_DateDebut2']=AfficheDateFR($debutSemaine);
				$_SESSION['EXTRACT_DateFin2']=AfficheDateFR($finSemaine);
				$_SESSION['EXTRACT_Statut2']="VALIDE;";
				$_SESSION['EXTRACT_WP2']="";
				$_SESSION['EXTRACT_Controle2']="";
				
				
				//EXTRACT POINTAGE
				$btnD="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CriterePointage('dateDebut','".AfficheDateFR($debutSemaine)."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				$btnF="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_CriterePointage('dateFin','".AfficheDateFR($finSemaine)."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				$_SESSION['EXTRACT_DateDebutPointage']=AfficheDateFR($debutSemaine).$btnD;
				$_SESSION['EXTRACT_DateFinPointage']=AfficheDateFR($finSemaine).$btnF;
				$_SESSION['EXTRACT_WPPointage']="";
				$_SESSION['EXTRACT_TachePointage']="";
				$_SESSION['EXTRACT_PreparateurPointage']="";
				
				$_SESSION['EXTRACT_DateDebutPointage2']=AfficheDateFR($debutSemaine);
				$_SESSION['EXTRACT_DateFinPointage2']=AfficheDateFR($finSemaine);
				$_SESSION['EXTRACT_WPPointage2']="";
				$_SESSION['EXTRACT_TachePointage2']="";
				$_SESSION['EXTRACT_PreparateurPointage2']="";
				
				//TACHES
				$_SESSION['TACHE_Tache']="";
				
				//EXTRACT OTD / OQD
				$_SESSION['EXTRACT_MoisQualite']=date("m/Y");
				$_SESSION['EXTRACT_WPQualite']="";
				$_SESSION['EXTRACT_Checklist']="";
				$_SESSION['EXTRACT_Responsable']="";
				
				$_SESSION['EXTRACT_MoisQualite2']=date("m/Y");
				$_SESSION['EXTRACT_WPQualite2']="";
				$_SESSION['EXTRACT_Checklist2']="";
				$_SESSION['EXTRACT_Responsable2']="";
				
				//PRODUCTIVITE
				$_SESSION['PRODUCTIVITE_Mois']=date("m/Y");
				$_SESSION['PRODUCTIVITE_Par']="Workpackage";
				$_SESSION['PRODUCTIVITE_WP']="";
				$_SESSION['PRODUCTIVITE_Tache']="";
				$_SESSION['PRODUCTIVITE_UO']="";
				$_SESSION['PRODUCTIVITE_Collaborateur']="";
				
				$_SESSION['PRODUCTIVITE_Mois2']=date("m/Y");
				$_SESSION['PRODUCTIVITE_Par2']="1";
				$_SESSION['PRODUCTIVITE_WP2']="";
				$_SESSION['PRODUCTIVITE_Tache2']="";
				$_SESSION['PRODUCTIVITE_UO2']="";
				$_SESSION['PRODUCTIVITE_Collaborateur2']="";
				
				echo "<html>";
				if($_SESSION['NomTR']=="" || $_SESSION['PrenomTR']=="" || $_SESSION['LogTR']=="" || $_SESSION['MdpTR']=="")
				{echo "<body onload='window.location.top=\"".$chemin."/index.php?L=".$_GET['L']."&Cnx=CKIES\";'>";}
				else{
					if($_SESSION['MdpTR']=="aaa01"){
						echo "<body onload='window.location.href=\"".$chemin."/MotDePasse.php\";'>";
					}
					else {echo "<body onload='window.location.href=\"".$chemin."/Accueil.php\";'>";}
				}
			}
			else{echo "<body onload='window.location.href=\"".$chemin."/index.php?L=".$_GET['L']."&Cnx=BAD2\";'>";}
		}
		else{echo "<body onload='window.location.href=\"".$chemin."/index.php?L=".$_GET['L']."&Cnx=BAD\";'>";}
	}
	else{echo "<body onload='window.location.href=\"".$chemin."/index.php?L=".$_GET['L']."&Cnx=BAD\";'>";}
	mysqli_free_result($result);	// Libération des résultats
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
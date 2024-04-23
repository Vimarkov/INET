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
		function OuvreDef(){window.open("pdf.php?Doc=PDF/Definition des catégories","PageDoc","status=no,menubar=no,scrollbars=no,width=50,height=50");}
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

Ecrire_Code_JS_Init_Date();

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));

if($_POST){
	if(isset($_POST['btnEnregistrer2'])){
		//Mise à jour du dossier
		if($_POST['typeSaisie']=="PROD"){
			$req="UPDATE sp_dossier SET ";
			$req.="PNE=".$_POST['pne'].", ";
			$req.="MSN=".$_POST['msn'].", ";
			$req.="TAI_RestantACP='".$_POST['tai']."', ";
			$req.="Origine='".$_POST['origine']."', ";
			$req.="NumOrigine='".addslashes($_POST['numOrigine'])."', ";
			$req.="Id_Urgence=".$_POST['urgence'].", ";
			$req.="Titre='".addslashes($_POST['titre'])."', ";
			$req.="Id_ZoneDeTravail=".$_POST['zone'].", ";
			$req.="CommentaireZICIA='".addslashes($_POST['commentaireZI'])."', ";
			$req.="Priorite=".$_POST['priorite'].", ";
			if(isset($_POST['Elec'])){$req.="Elec=1, ";}else{$req.="Elec=0, ";}
			if(isset($_POST['Systeme'])){$req.="Systeme=1, ";}else{$req.="Systeme=0, ";}
			if(isset($_POST['Structure'])){$req.="Structure=1, ";}else{$req.="Structure=0, ";}
			if(isset($_POST['Oxygene'])){$req.="Oxygene=1, ";}else{$req.="Oxygene=0, ";}
			if(isset($_POST['Hydraulique'])){$req.="Hydraulique=1, ";}else{$req.="Hydraulique=0, ";}
			if(isset($_POST['Fuel'])){$req.="Fuel=1, ";}else{$req.="Fuel=0, ";}
			if(isset($_POST['Metal'])){$req.="Metal=1 ";}else{$req.="Metal=0 ";}
			$req.="WHERE Id=".$_POST['idDossier'];
			$resultUpdate=mysqli_query($bdd,$req);
			
			//Suppression des anciens ATA
			$req="DELETE FROM sp_dossier_ata WHERE Id_Dossier=".$_POST['idDossier'];
			$resultDelete=mysqli_query($bdd,$req);

			//Ajout des ATA/Sous-ATA
			$tabATA = explode(";",$_POST['ata_sousata']);
			foreach($tabATA as $valeur){
				 if($valeur<>""){
					$tab2 = explode("_",$valeur);
					$req="INSERT INTO sp_dossier_ata (Id_Dossier,ATA,SousATA,IsolationElec) VALUES (".$_POST['idDossier'].",".$tab2[0].",".$tab2[1].",".$tab2[2].")";
					$resultAjour=mysqli_query($bdd,$req);
				 }
			}
		}
		else{
			//Mise à jour du dossier
			$req="UPDATE sp_dossier SET ";
			$req.="MSN=".$_POST['msn'].", ";
			$req.="Titre='".addslashes($_POST['titre'])."' ";
			$req.="WHERE Id=".$_POST['idDossier'];
			$resultUpdate=mysqli_query($bdd,$req);
			$IdCree = $_POST['idDossier'];
		}
		
		//Ajout de la fiche d'intervention
		if($_POST['typeSaisie']=="PROD"){
			$req="INSERT INTO sp_ficheintervention (";
			$req.="Id_Dossier,Id_FIIndicage,Id_Createur,DateCreation,PosteAvionACP,Id_Pole,ResponsableZone,DeposeRepose,TravailRealise,Commentaire,PieceAuPoste,EtatICCIA,StatutICCIA,NumFI,PasDePS,PasDeECMEPROD,PasDeECMEQUALITE,PasDeIngredient,ValidationPSCE,ValidationPSIQ";
			if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1'){
				$req.=",CommentaireMICIA,CommentairePOCIA,RaisonInterventionCIA,ESN,TypeCIA,Id_ActiviteCIA,";
				$req.="Id_TypeTravailCIA,RefAInstallerCIA,Id_ImpactElementCIA,RefCableCIA,DescriptionTypeTravailCIA,PosteInterventionCIA,";
				$req.="PowerOffPartielCIA,InfoSuppPOCIA,DateIntervention,Vacation,TempsObjectif,DateFinCIA,HeureDebutCIA,HeureFinCIA";
			}
			if(substr($_SESSION['DroitSP'],1,1)=='1'){
				$req.=",Id_StatutPROD,Avancement,DateCreationPROD,Id_RetourPROD,Id_PROD,CommentairePROD,RaisonIndicage";
			}
			if(substr($_SESSION['DroitSP'],4,1)=='1'){
				$req.=",DateInterventionQ,VacationQ,Id_StatutQUALITE,DateCreationQUALITE,Id_RetourQUALITE,Id_QUALITE,CommentaireQUALITE";
			}
			$req.=") VALUES (";
			
			$req.=$_POST['idDossier'].",".$_POST['idFI'].",".$_SESSION['Id_PersonneSP'].",'".$DateJour."','',".$_POST['pole'].",'".addslashes($_POST['responsableZone'])."',".$_POST['deposeRepose'].",'".addslashes($_POST['travailRealise'])."','".addslashes($_POST['commentaire'])."','".addslashes($_POST['pieceauposte'])."'";
			if($_POST['ICaCreer']=="0"){
				if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1'){
					$req.=",";
					if (substr($_SESSION['DroitSP'],1,1)== "1"){$req.="'A TRAITER',";}
					else{
						if($_POST['dateIntervention']<>"" && $_POST['vacation']<>""){
							$req.="'A TRAITER',";
						}
						else{
							$req.="'A VALIDER PAR CE',";
						}
					}
					$req.="0,''";
				}
				else{
					$req.=",'',0,''";
				}
			}
			else{
				$req.=",'VALIDEE',6,'".$_POST['numIC']."'";
			}
			
			if(isset($_POST['PasDePS'])){$req.=",1, ";}else{$req.=",0, ";}
			
			if(isset($_POST['PasDeECMEPROD'])){$req.="1, ";}else{$req.="0, ";}
			if(isset($_POST['PasDeECMEQUALITE'])){$req.="1, ";}else{$req.="0, ";}
			if(isset($_POST['PasDeIngredient'])){$req.="1, ";}else{$req.="0, ";}
			
			if(isset($_POST['ValidationPSCE'])){$req.="1, ";}else{$req.="0, ";}
			if(isset($_POST['ValidationPSIQ'])){$req.="1 ";}else{$req.="0 ";}
			
			if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1'){
				$req.=",";

				$posteIntervention="";
				$req.= "'".addslashes($_POST['commentaireMI'])."','".addslashes($_POST['commentairePO'])."','".addslashes($_POST['raisonIntervention'])."',".$_POST['esn'].",";
				$req.= "'".addslashes($_POST['typeIC'])."',".$_POST['activite'].",".$_POST['typeTravail'].",'".addslashes($_POST['refAInstaller'])."',";
				$req.= "".$_POST['impactElement'].",'".addslashes($_POST['refCables'])."','".addslashes($_POST['descriptionTT'])."','".addslashes($posteIntervention)."',";
				$req.= "".$_POST['poweroff'].",'".addslashes($_POST['infoAddionnelle'])."',";
				
				$vacation="";
				$dateDebut=TrsfDate_($_POST['dateIntervention']);
				$dateFin="0001-01-01";
				$heureD="";
				$heureF="";
				$joursem = array("D", "L", "Ma", "Me", "J", "V", "S");
				if($_POST['vacation']<>"" && $_POST['dateIntervention']<>""){
					$dateDebut=TrsfDate_($_POST['dateIntervention']);
					$tabD = explode('-', $dateDebut);
					$timestampD = mktime(0, 0, 0, $tabD[1], $tabD[2], $tabD[0]);
					$jour = date('w', $timestampD);
					if($_POST['vacation']=="J"){
						$dateFin=$dateDebut;
						if($joursem[$jour]<>"V" && $joursem[$jour]<>"S" && $joursem[$jour]<>"D"){
							$heureD="7:00";
							$heureF="15:45";
							$vacation="J";
						}
						elseif($joursem[$jour]=="V"){
							$heureD="7:00";
							$heureF="11:45";
							$vacation="J";
						}
						elseif($joursem[$jour]=="S"){
							$heureD="6:00";
							$heureF="12:00";
							$vacation="J";
						}
						elseif($joursem[$jour]=="D"){
							$heureD="7:30";
							$heureF="17:00";
							$vacation="VSD Jour";
						}
					}
					elseif($_POST['vacation']=="S"){
						$dateFin=$dateDebut;
						if($joursem[$jour]<>"V" && $joursem[$jour]<>"S" && $joursem[$jour]<>"D"){
							$heureD="16:30";
							$heureF="00:15";
							$vacation="S";
							$dateFin=TrsfDate_($_POST['dateIntervention']);
							$tabFin = explode('-', $dateFin);
							$timestampFin = mktime(0, 0, 0, $tabFin[1], $tabFin[2]+1, $tabFin[0]);
							$dateFin = date('Y-m-d', $timestampFin);
						}
						elseif($joursem[$jour]=="V"){
							$heureD="12:30";
							$heureF="22:00";
							$vacation="S";
						}
						elseif($joursem[$jour]=="S"){
							$heureD="9:30";
							$heureF="19:00";
							$vacation="VSD Jour";
						}
						elseif($joursem[$jour]=="D"){
							$heureD="7:30";
							$heureF="17:00";
							$vacation="VSD Jour";
						}
					}
					elseif($_POST['vacation']=="VSD Jour"){
						$dateFin=$dateDebut;
						if($joursem[$jour]=="S"){
							$heureD="9:30";
							$heureF="19:00";
							$vacation="VSD Jour";
						}
						elseif($joursem[$jour]=="D"){
							$heureD="7:30";
							$heureF="17:00";
							$vacation="VSD Jour";
						}
						elseif($joursem[$jour]=="V"){
							$heureD="12:30";
							$heureF="22:00";
							$vacation="VSD Jour";
						}
						else{
							$heureD="7:00";
							$heureF="15:45";
							$vacation="J";
						}
					}
					elseif($_POST['vacation']=="VSD Nuit"){
						if($joursem[$jour]=="V"){
							$heureD="22:00";
							$heureF="9:00";
							$dateFin=TrsfDate_($_POST['dateIntervention']);
							$tabFin = explode('-', $dateFin);
							$timestampFin = mktime(0, 0, 0, $tabFin[1], $tabFin[2]+1, $tabFin[0]);
							$dateFin = date('Y-m-d', $timestampFin);
							$vacation="VSD Nuit";
						}
						elseif($joursem[$jour]=="S"){
							$heureD="19:00";
							$heureF="7:00";
							$dateFin=TrsfDate_($_POST['dateIntervention']);
							$tabFin = explode('-', $dateFin);
							$timestampFin = mktime(0, 0, 0, $tabFin[1], $tabFin[2]+1, $tabFin[0]);
							$dateFin = date('Y-m-d', $timestampFin);
							$vacation="VSD Nuit";
						}
						elseif($joursem[$jour]=="D"){
							$heureD="17:00";
							$heureF="7:00";
							$dateFin=TrsfDate_($_POST['dateIntervention']);
							$tabFin = explode('-', $dateFin);
							$timestampFin = mktime(0, 0, 0, $tabFin[1], $tabFin[2]+1, $tabFin[0]);
							$dateFin = date('Y-m-d', $timestampFin);
							$vacation="VSD Nuit";
						}
						else{
							$heureD="00:30";
							$heureF="6:30";
							$dateFin=$dateDebut;
							$vacation="N";
						}
					}
					elseif($_POST['vacation']=="N"){
						if($joursem[$jour]<>"S" && $joursem[$jour]<>"D"){
							$heureD="00:30";
							$heureF="6:30";
							$vacation="N";
							$dateFin=$dateDebut;
						}
						elseif($joursem[$jour]=="S"){
							$heureD="19:00";
							$heureF="7:00";
							$dateFin=TrsfDate_($_POST['dateIntervention']);
							$tabFin = explode('-', $dateFin);
							$timestampFin = mktime(0, 0, 0, $tabFin[1], $tabFin[2]+1, $tabFin[0]);
							$dateFin = date('Y-m-d', $timestampFin);
							$vacation="VSD Nuit";
						}
						elseif($joursem[$jour]=="D"){
							$heureD="17:00";
							$heureF="7:00";
							$dateFin=TrsfDate_($_POST['dateIntervention']);
							$tabFin = explode('-', $dateFin);
							$timestampFin = mktime(0, 0, 0, $tabFin[1], $tabFin[2]+1, $tabFin[0]);
							$dateFin = date('Y-m-d', $timestampFin);
							$vacation="VSD Nuit";
						}
					}
				}
				$temps=0;
				if($_POST['tempsObjectif']<>""){$temps=$_POST['tempsObjectif'];}
				$req.= "'".$dateDebut."','".$vacation."',".$temps.",'".$dateFin."','".$heureD."','".$heureF."'";
			}
			
			$modeAnnulee="";
			if(substr($_SESSION['DroitSP'],1,1)=='1'){
				if($_POST['statutProd']<>"0"){
					$req.= ",'".$_POST['statutProd']."',".$_POST['avancementProd'].",'".$DateJour."',".$_POST['retourProd'].",".$_SESSION['Id_PersonneSP'].",'".addslashes($_POST['commentairePROD'])."','".addslashes($_POST['raisonIndicage'])."'";
					if($_POST['statutProd']=="QARJ" || $_POST['statutProd']=="REWORK"){
						$modeAnnulee="Oui";
					}
				}
				else{
					$req.= ",'','0001-01-01',0,0,'".addslashes($_POST['commentairePROD'])."',''";
				}
			}
			if(substr($_SESSION['DroitSP'],4,1)=='1'){
				$vacation="";
				$dateDebut=TrsfDate_($_POST['dateInterventionQ']);
				$joursem = array("D", "L", "Ma", "Me", "J", "V", "S");
				if($_POST['vacationQ']<>"" && $_POST['dateInterventionQ']<>""){
					$dateDebut=TrsfDate_($_POST['dateInterventionQ']);
					$tabD = explode('-', $dateDebut);
					$timestampD = mktime(0, 0, 0, $tabD[1], $tabD[2], $tabD[0]);
					$jour = date('w', $timestampD);
					if($_POST['vacationQ']=="J"){
						if($joursem[$jour]<>"D"){$vacation="J";}
						elseif($joursem[$jour]=="D"){$vacation="VSD Jour";}
					}
					elseif($_POST['vacationQ']=="S"){
						if($joursem[$jour]<>"V" && $joursem[$jour]<>"S" && $joursem[$jour]<>"D"){$vacation="S";}
						elseif($joursem[$jour]=="V"){$vacation="S";}
						elseif($joursem[$jour]=="S"){$vacation="VSD Jour";}
						elseif($joursem[$jour]=="D"){$vacation="VSD Jour";}
					}
					elseif($_POST['vacationQ']=="VSD Jour"){
						if($joursem[$jour]=="S"){$vacation="VSD Jour";}
						elseif($joursem[$jour]=="D"){$vacation="VSD Jour";}
						elseif($joursem[$jour]=="V"){$vacation="VSD Jour";}
						else{$vacation="J";}
					}
					elseif($_POST['vacationQ']=="VSD Nuit"){
						if($joursem[$jour]=="V"){$vacation="VSD Nuit";}
						elseif($joursem[$jour]=="S"){$vacation="VSD Nuit";}
						elseif($joursem[$jour]=="D"){$vacation="VSD Nuit";}
						else{$vacation="N";}
					}
					elseif($_POST['vacationQ']=="N"){
						if($joursem[$jour]<>"S" && $joursem[$jour]<>"D"){$vacation="N";}
						elseif($joursem[$jour]=="S"){$vacation="VSD Nuit";}
						elseif($joursem[$jour]=="D"){$vacation="VSD Nuit";}
					}
				}
				$req.= ",'".$dateDebut."','".$vacation."'";
				$Id_IQ=0;
				if($_POST['IQ']<>""){$Id_IQ=$_POST['IQ'];}
				else{$Id_IQ=$_SESSION['Id_PersonneSP'];}
				if($_POST['statutQualite']<>"0"){
					$req.= ",'".$_POST['statutQualite']."','".$DateJour."',".$_POST['retourQualite'].",".$Id_IQ.",'".addslashes($_POST['commentaireQualite'])."'";
				}
				else{
					$req.= ",'','0001-01-01',0,".$Id_IQ.",'".addslashes($_POST['commentaireQualite'])."'";
				}
			}
			
			$req.= "); ";
			$resultAjour=mysqli_query($bdd,$req);
			$IdFICree = mysqli_insert_id($bdd);
			
			if($modeAnnulee=="Oui"){
				$reqAnnule="UPDATE sp_ficheintervention SET EtatICCIA='ANNULEE' WHERE Id<>".$IdFICree." AND Id_Dossier=".$IdCree." AND (EtatICCIA='' OR EtatICCIA='A TRAITER' OR EtatICCIA='A VALIDER PAR CE')";
				$resultAnnule=mysqli_query($bdd,$reqAnnule);
			}
		}
		else{
			$req="INSERT INTO sp_ficheintervention (";
			$req.="Id_Dossier,Id_Createur,DateCreation,PosteAvionACP,Id_Pole,ResponsableZone,Commentaire,SaisieQualite,PasDePS,PasDeECMEPROD,PasDeECMEQUALITE,PasDeIngredient,ValidationPSCE,ValidationPSIQ";
			if(substr($_SESSION['DroitSP'],4,1)=='1'){
				$req.=",DateInterventionQ,VacationQ,Id_StatutQUALITE,DateCreationQUALITE,Id_RetourQUALITE,Id_QUALITE,CommentaireQUALITE";
			}
			$req.=") VALUES (";
			
			$req.=$IdCree.",".$_SESSION['Id_PersonneSP'].",'".$DateJour."','',".$_POST['pole'].",'".addslashes($_POST['responsableZone'])."','".addslashes($_POST['commentaire'])."',1";
			if(isset($_POST['PasDePS'])){$req.=",1, ";}else{$req.=",0, ";}
			
			if(isset($_POST['PasDeECMEPROD'])){$req.="1, ";}else{$req.="0, ";}
			if(isset($_POST['PasDeECMEQUALITE'])){$req.="1, ";}else{$req.="0, ";}
			if(isset($_POST['PasDeIngredient'])){$req.="1, ";}else{$req.="0, ";}
			
			if(isset($_POST['ValidationPSCE'])){$req.="1, ";}else{$req.="0, ";}
			if(isset($_POST['ValidationPSIQ'])){$req.="1 ";}else{$req.="0 ";}
			if(substr($_SESSION['DroitSP'],4,1)=='1'){
				$vacation="";
				$dateDebut=TrsfDate_($_POST['dateInterventionQ']);
				$joursem = array("D", "L", "Ma", "Me", "J", "V", "S");
				if($_POST['vacationQ']<>"" && $_POST['dateInterventionQ']<>""){
					$dateDebut=TrsfDate_($_POST['dateInterventionQ']);
					$tabD = explode('-', $dateDebut);
					$timestampD = mktime(0, 0, 0, $tabD[1], $tabD[2], $tabD[0]);
					$jour = date('w', $timestampD);
					if($_POST['vacationQ']=="J"){
						if($joursem[$jour]<>"D"){$vacation="J";}
						elseif($joursem[$jour]=="D"){$vacation="VSD Jour";}
					}
					elseif($_POST['vacationQ']=="S"){
						if($joursem[$jour]<>"V" && $joursem[$jour]<>"S" && $joursem[$jour]<>"D"){$vacation="S";}
						elseif($joursem[$jour]=="V"){$vacation="S";}
						elseif($joursem[$jour]=="S"){$vacation="VSD Jour";}
						elseif($joursem[$jour]=="D"){$vacation="VSD Jour";}
					}
					elseif($_POST['vacationQ']=="VSD Jour"){
						if($joursem[$jour]=="S"){$vacation="VSD Jour";}
						elseif($joursem[$jour]=="D"){$vacation="VSD Jour";}
						elseif($joursem[$jour]=="V"){$vacation="VSD Jour";}
						else{$vacation="J";}
					}
					elseif($_POST['vacationQ']=="VSD Nuit"){
						if($joursem[$jour]=="V"){$vacation="VSD Nuit";}
						elseif($joursem[$jour]=="S"){$vacation="VSD Nuit";}
						elseif($joursem[$jour]=="D"){$vacation="VSD Nuit";}
						else{$vacation="N";}
					}
					elseif($_POST['vacationQ']=="N"){
						if($joursem[$jour]<>"S" && $joursem[$jour]<>"D"){$vacation="N";}
						elseif($joursem[$jour]=="S"){$vacation="VSD Nuit";}
						elseif($joursem[$jour]=="D"){$vacation="VSD Nuit";}
					}
				}
				$req.= ",'".$dateDebut."','".$vacation."'";
				$Id_IQ=0;
				if($_POST['IQ']<>""){$Id_IQ=$_POST['IQ'];}
				else{$Id_IQ=$_SESSION['Id_PersonneSP'];}
				if($_POST['statutQualite']<>"0"){
					$req.= ",'".$_POST['statutQualite']."','".$DateJour."',".$_POST['retourQualite'].",".$Id_IQ.",'".addslashes($_POST['commentaireQualite'])."'";
				}
				else{
					$req.= ",'','0001-01-01',0,".$Id_IQ.",'".addslashes($_POST['commentaireQualite'])."'";
				}
			}
			
			$req.= "); ";
			$resultAjour=mysqli_query($bdd,$req);
			$IdFICree = mysqli_insert_id($bdd);
		}
		if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
			if(substr($_SESSION['DroitSP'],1,1)=='1'){
				if($_POST['typeSaisie']=="PROD"){
					//Ajout des compagnons
					$tabCompagnon = explode(";",$_POST['travailEffectue']);
					foreach($tabCompagnon as $valeur){
						 if($valeur<>""){
							$tab2 = explode("C_",$valeur);
							$req="INSERT INTO sp_fi_travaileffectue (Id_FI,Id_Personne,TempsPasse) VALUES (".$IdFICree.",".$tab2[0].",".$tab2[1].")";
							$resultAjour=mysqli_query($bdd,$req);
						 }
					}
				}
			}
		}
		
		//Avant la mise à jour des ECME PROD 
		//Récupération des ECME PROD non identifiés & vérifier si existe déjà dans la BDD
		//Sinon envoi un mail aux Coordinateurs d'équipe de la prestation pour les avertir
		$tabECME = explode(";",$_POST['ECMEPROD']);
		foreach($tabECME as $valeur){
			 if($valeur<>""){
				$tablesECME = explode("ECME_ECME",$valeur);
				$InfosECME=$tablesECME[1];
				if($InfosECME<>""){
					$tabECME2=explode("_",$InfosECME);
					$ECME=$tabECME2[0];
					$req="SELECT Id FROM sp_fi_ecme WHERE ECME='".addslashes($ECME)."' AND Id_FI=".$IdFICree;
					$resultSelect=mysqli_query($bdd,$req);
					$nbECME=mysqli_num_rows($resultSelect);
					if($nbECME==0){
						//Email 
						$Headers='From: "Extranet AAA"<extranet@aaa-aero.com>'."\n";
						$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
						
						$Emails="";
						$ReqResponsablePostePrestation="
							SELECT DISTINCT new_rh_etatcivil.EmailPro
							FROM
								new_competences_personne_poste_prestation,
								new_rh_etatcivil
							WHERE
								new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
								AND new_competences_personne_poste_prestation.Id_Prestation=255
								AND new_competences_personne_poste_prestation.Id_Pole=".$_POST['pole']."
								AND new_competences_personne_poste_prestation.Id_Poste=2
								AND new_rh_etatcivil.EmailPro<>''";
						$resultCE=mysqli_query($bdd,$ReqResponsablePostePrestation);
						$nbCE=mysqli_num_rows($resultCE);
						if($nbCE>0){
							while($rowCE=mysqli_fetch_array($resultCE)){
								$Emails.=$rowCE['EmailPro'].",";
							}
						}
						$Objet="Nouvel ECME à mettre à jour, suivi production prestation TBWP";
						$MessageMail="	<html>
										<head><title>Nouvel ECME à mettre à jour, suivi production prestation TBWP</title></head>
										<body>
											Bonjour,
											<br><br>
											Attention, une référence d'ECME non identifiée dans la liste prédéfinie de votre outil de suivi production vient d'être saisie pour la prestation TBWP<br>
											Veuillez vérifier l'exactitude des données saisies et mettre à jour la liste des ECME si cet outil est amené à être utilisé sur votre prestation pour vos futurs travaux
											<br>ECME : ".$ECME."
											<br>
											Bonne journée.<br>
											L'Extranet AAA.
										</body>
									</html>";
						if($Emails<>""){
							if(mail($Emails,$Objet,$MessageMail,$Headers,'-f extranet@aaa-aero.com'))
								{echo "";}
						}
					}
				}
			 }
		}
		
		//Ajout des ecme PROD
		$tabPL = explode(";",$_POST['ECMEPROD']);
		foreach($tabPL as $valeur){
			 if($valeur<>""){
				$Id_ECME=0;
				$Id_TypeECME=0;
				$ReferenceECME="";
				$tabECME = explode("ECME_ECME",$valeur);
				if($tabECME[0]==0){
					$tabECME2 = explode("_",$tabECME[1]);
					$ReferenceECME=$tabECME2[0];
					$Id_TypeECME=$tabECME2[1];
				}
				else{
					$tabECME2 = explode("_",$tabECME[0]);
					$Id_ECME=$tabECME2[0];
					$Id_TypeECME=$tabECME2[1];
				}
				$req="INSERT INTO sp_fi_ecme (Id_FI,Id_ECME,Id_TypeECME,ECME,ProdQualite) 
				VALUES (".$IdFICree.",".$Id_ECME.",".$Id_TypeECME.",'".$ReferenceECME."',0)";
				$resultAjour=mysqli_query($bdd,$req);
			 }
		}
		
		//Avant la mise à jour des ECME QUALITE 
		//Récupération des ECME PROD non identifiés & vérifier si existe déjà dans la BDD
		//Sinon envoi un mail aux Coordinateurs d'équipe de la prestation pour les avertir
		$tabECME = explode(";",$_POST['ECMEQUALITE']);
		foreach($tabECME as $valeur){
			 if($valeur<>""){
				$tablesECME = explode("ECME_ECME",$valeur);
				$InfosECME=$tablesECME[1];
				if($InfosECME<>""){
					$tabECME2=explode("_",$InfosECME);
					$ECME=$tabECME2[0];
					$req="SELECT Id FROM sp_fi_ecme WHERE ECME='".addslashes($ECME)."' AND Id_FI=".$IdFICree;
					$resultSelect=mysqli_query($bdd,$req);
					$nbECME=mysqli_num_rows($resultSelect);
					if($nbECME==0){
						//Email 
						$Headers='From: "Extranet AAA"<extranet@aaa-aero.com>'."\n";
						$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
						
						$Emails="";
						$ReqResponsablePostePrestation="
							SELECT DISTINCT new_rh_etatcivil.EmailPro
							FROM
								new_competences_personne_poste_prestation,
								new_rh_etatcivil
							WHERE
								new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
								AND new_competences_personne_poste_prestation.Id_Prestation=255
								AND new_competences_personne_poste_prestation.Id_Pole=".$_POST['pole']."
								AND new_competences_personne_poste_prestation.Id_Poste=2
								AND new_rh_etatcivil.EmailPro<>''";
						$resultCE=mysqli_query($bdd,$ReqResponsablePostePrestation);
						$nbCE=mysqli_num_rows($resultCE);
						if($nbCE>0){
							while($rowCE=mysqli_fetch_array($resultCE)){
								$Emails.=$rowCE['EmailPro'].",";
							}
						}
						$Objet="Nouvel ECME à mettre à jour, suivi production prestation TBWP";
						$MessageMail="	<html>
										<head><title>Nouvel ECME à mettre à jour, suivi production prestation TBWP</title></head>
										<body>
											Bonjour,
											<br><br>
											Attention, une référence d'ECME non identifiée dans la liste prédéfinie de votre outil de suivi production vient d'être saisie pour la prestation TBWP<br>
											Veuillez vérifier l'exactitude des données saisies et mettre à jour la liste des ECME si cet outil est amené à être utilisé sur votre prestation pour vos futurs travaux
											<br>ECME : ".$ECME."
											<br>
											Bonne journée.<br>
											L'Extranet AAA.
										</body>
									</html>";
						if($Emails<>""){
							if(mail($Emails,$Objet,$MessageMail,$Headers,'-f extranet@aaa-aero.com'))
								{echo "";}
						}
					}
				}
			 }
		}
		
		//Ajout des ecme QUALITE
		$tabPL = explode(";",$_POST['ECMEQUALITE']);
		foreach($tabPL as $valeur){
			 if($valeur<>""){
				$Id_ECME=0;
				$Id_TypeECME=0;
				$ReferenceECME="";
				$tabECME = explode("ECME_ECME",$valeur);
				if($tabECME[0]==0){
					$tabECME2 = explode("_",$tabECME[1]);
					$ReferenceECME=$tabECME2[0];
					$Id_TypeECME=$tabECME2[1];
				}
				else{
					$tabECME2 = explode("_",$tabECME[0]);
					$Id_ECME=$tabECME2[0];
					$Id_TypeECME=$tabECME2[1];
				} 
				$req="INSERT INTO sp_fi_ecme (Id_FI,Id_ECME,Id_TypeECME,ECME,ProdQualite) 
				VALUES (".$IdFICree.",".$Id_ECME.",".$Id_TypeECME.",'".$ReferenceECME."',1)";
				$resultAjour=mysqli_query($bdd,$req);
			 }
		}
		
		//Ajout des ecme client
		$tabPL = explode(";",$_POST['ECMECLIENT']);
		foreach($tabPL as $valeur){
			 if($valeur<>""){
				$Id_ECME=0;
				$Id_TypeECME=0;
				$ReferenceECME="";
				$dateEtalonnage="";
				$tabECME = explode("ECME_ECME",$valeur);
				if($tabECME[0]==0){
					$tabECME2 = explode("_",$tabECME[1]);
					$ReferenceECME=$tabECME2[0];
					$Id_TypeECME=$tabECME2[1];
					$dateEtalonnage=$tabECME2[2];
				}
				else{
					$tabECME2 = explode("_",$tabECME[0]);
					$Id_ECME=$tabECME2[0];
					$Id_TypeECME=$tabECME2[1];
					$dateEtalonnage=$tabECME2[2];
				} 
				$req="INSERT INTO sp_fi_ecmeclient (Id_FI,Id_ECME,Id_TypeECME,NumClient,DateFinEtalonnage) 
				VALUES (".$IdFICree.",".$Id_ECME.",".$Id_TypeECME.",'".$ReferenceECME."','".TrsfDate_($dateEtalonnage)."')";
				$resultAjour=mysqli_query($bdd,$req);
			 }
		}
		
		//Avant la mise à jour des Inrédients
		//Récupération des ingredients non identifiés & vérifier si existe déjà dans la BDD
		//Sinon envoi un mail aux Coordinateurs d'équipe de la prestation pour les avertir
		$tabProduit = explode(";",$_POST['Produit']);
		foreach($tabProduit as $valeur){
			 if($valeur<>""){
				$tablesProduit= explode("ING_ING",$valeur);
				$InfosProduit=$tablesProduit[1];
				if($InfosProduit<>""){
					$tabProduit2=explode("_",$InfosProduit);
					$Produit=$tabProduit2[0];
					$req="SELECT Id FROM sp_fi_ingredient WHERE Ingredient='".addslashes($Produit)."' AND Id_FI=".$IdFICree;
					$resultSelect=mysqli_query($bdd,$req);
					$nbProduit=mysqli_num_rows($resultSelect);
					if($nbProduit==0){
						//Email 
						$Headers='From: "Extranet AAA"<extranet@aaa-aero.com>'."\n";
						$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
						
						$Emails="";
						$ReqResponsablePostePrestation="
							SELECT DISTINCT new_rh_etatcivil.EmailPro
							FROM
								new_competences_personne_poste_prestation,
								new_rh_etatcivil
							WHERE
								new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
								AND new_competences_personne_poste_prestation.Id_Prestation=255
								AND new_competences_personne_poste_prestation.Id_Pole=".$_POST['pole']."
								AND new_competences_personne_poste_prestation.Id_Poste=2
								AND new_rh_etatcivil.EmailPro<>''";
						$resultCE=mysqli_query($bdd,$ReqResponsablePostePrestation);
						$nbCE=mysqli_num_rows($resultCE);
						if($nbCE>0){
							while($rowCE=mysqli_fetch_array($resultCE)){
								$Emails.=$rowCE['EmailPro'].",";
							}
						}
						$Objet="Nouvel ingrédient à mettre à jour, suivi production prestation TBWP";
						$MessageMail="	<html>
										<head><title>Nouvel ingrédient à mettre à jour, suivi production prestation TBWP</title></head>
										<body>
											Bonjour,
											<br><br>
											Attention, un ingrédient non identifié dans la liste prédéfinie de votre outil de suivi production vient d'être saisi pour la prestation TBWP<br>
											Veuillez vérifier l'exactitude des données saisies et mettre à jour la liste des ingrédients si cet ingrédient est amené à être utilisé sur votre prestation pour vos futurs travaux
											<br>Ingrédient : ".$Produit."
											<br>
											Bonne journée.<br>
											L'Extranet AAA.
										</body>
									</html>";
						if($Emails<>""){
							if(mail($Emails,$Objet,$MessageMail,$Headers,'-f extranet@aaa-aero.com'))
								{echo "";}
						}
					}
				}
			 }
		}
		
		//Ajout des produits
		$tabProduit = explode(";",$_POST['Produit']);
		foreach($tabProduit as $valeur){
			 if($valeur<>""){
				$Id_Produit=0;
				$ReferenceProduit="";
				$NumLot="";
				$DatePeremption="";
				$Coeff="";
				$Temperature="";
				$tabING = explode("ING_ING",$valeur);
				if($tabING[0]==0){
					$tabING2 = explode("_",$tabING[1]);
					$ReferenceProduit=$tabING2[0];
					$NumLot=$tabING2[1];
					$DatePeremption=TrsfDate_($tabING2[2]);
					$Coeff=$tabING2[3];
					$Temperature=$tabING2[4];
				}
				else{
					$tabING2 = explode("_",$tabING[0]);
					$Id_Produit=$tabING2[0];
					$NumLot=$tabING2[1];
					$DatePeremption=TrsfDate_($tabING2[2]);
					$Coeff=$tabING2[3];
					$Temperature=$tabING2[4];
				}

				$tab2 = explode("_",$valeur);
				$req="INSERT INTO sp_fi_ingredient (Id_FI,Id_Ingredient,NumLot,DatePeremption,CoeffHydrometrique,Temperature,Ingredient) VALUES (";
				$req.=$IdFICree.",".$Id_Produit.",'".$NumLot."','".$DatePeremption."','".$Coeff."','".$Temperature."','".$ReferenceProduit."')";
				$resultAjour=mysqli_query($bdd,$req);
			 }
		}
		
		//Avant la mise à jour des PS 
		//Récupération des PS non identifiés & vérifier si existe déjà dans la BDD
		//Sinon envoi un mail aux CQP de la prestation pour les avertir
		$tabAIPI = explode(";",$_POST['lesAIPI']);
		foreach($tabAIPI as $valeur){
			 if($valeur<>""){
				$tabPS = explode("PS_PS",$valeur);
				$Qualif=$tabPS[1];
				if($Qualif<>""){
					$req="SELECT Id FROM sp_fi_aipi WHERE Qualification='".addslashes($Qualif)."' AND Id_FI=".$IdFICree;
					$resultSelect=mysqli_query($bdd,$req);
					$nbQualif=mysqli_num_rows($resultSelect);
					if($nbQualif==0){
						//Email 
						$Headers='From: "Extranet AAA"<extranet@aaa-aero.com>'."\n";
						$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
						
						$Emails="";
						$ReqResponsablePostePrestation="
							SELECT DISTINCT new_rh_etatcivil.EmailPro
							FROM
								new_competences_personne_poste_prestation,
								new_rh_etatcivil
							WHERE
								new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
								AND new_competences_personne_poste_prestation.Id_Prestation=255
								AND new_competences_personne_poste_prestation.Id_Pole=".$_POST['pole']."
								AND new_competences_personne_poste_prestation.Id_Poste=5
								AND new_rh_etatcivil.EmailPro<>''";
						$resultCQP=mysqli_query($bdd,$ReqResponsablePostePrestation);
						$nbCQP=mysqli_num_rows($resultCQP);
						if($nbCQP>0){
							while($rowCQP=mysqli_fetch_array($resultCQP)){
								$Emails.=$rowCQP['EmailPro'].",";
							}
						}
						$Objet="Nouveau PS identifié, suivi production prestation TBWP";
						$MessageMail="	<html>
										<head><title>Nouveau PS identifié, suivi production prestation TBWP</title></head>
										<body>
											Bonjour,
											<br><br>
											Attention, une référence de procédé spécial non identifiée au tableu de compétences vient d'être saisie dans le suivi production de la prestation TBWP<br>
											PS : ".$Qualif."
											<br>
											Bonne journée.<br>
											L'Extranet AAA.
										</body>
									</html>";
						if($Emails<>""){
							if(mail($Emails,$Objet,$MessageMail,$Headers,'-f extranet@aaa-aero.com'))
								{echo "";}
						}
					}
				}
			 }
		}

		//Ajout des PS
		$tabAIPI = explode(";",$_POST['lesAIPI']);
		foreach($tabAIPI as $valeur){
			 if($valeur<>""){
				$tabPS = explode("PS_PS",$valeur);
				$Id_Qualif=0;
				if($tabPS[0]<>""){$Id_Qualif=$tabPS[0];}
				$Qualif=$tabPS[1];
				$req="INSERT INTO sp_fi_aipi (Id_FI,Id_Qualification,Qualification) VALUES (".$IdFICree.",".$Id_Qualif.",'".addslashes($Qualif)."')";
				$resultAjour=mysqli_query($bdd,$req);
			 }
		}
		if($_POST['typeSaisie']=="PROD"){
			if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1'){
				//Ajout des moyens industriel
				if(isset($_POST['EclairageNeon'])){
					$req="INSERT INTO sp_fi_moyenindustriel (Id_FI,NumCIA,Libelle) VALUES (".$IdFICree.",'1 Eclairages Néon','Eclairages néons')";
					$resultAjour=mysqli_query($bdd,$req);
				}
				if(isset($_POST['Echelle'])){
					$req="INSERT INTO sp_fi_moyenindustriel (Id_FI,NumCIA,Libelle) VALUES (".$IdFICree.",'2 Echelle','Echelle')";
					$resultAjour=mysqli_query($bdd,$req);
				}
				if(isset($_POST['PlateformeExterieur'])){
					$req="INSERT INTO sp_fi_moyenindustriel (Id_FI,NumCIA,Libelle) VALUES (".$IdFICree.",'3 Plateforme Extérieur','Plateforme extérieur')";
					$resultAjour=mysqli_query($bdd,$req);
				}
				if(isset($_POST['PlateformeMobile'])){
					$req="INSERT INTO sp_fi_moyenindustriel (Id_FI,NumCIA,Libelle) VALUES (".$IdFICree.",'4 Plateforme Mobile','Plateforme mobile')";
					$resultAjour=mysqli_query($bdd,$req);
				}
				if(isset($_POST['ScotchSecurite'])){
					$req="INSERT INTO sp_fi_moyenindustriel (Id_FI,NumCIA,Libelle) VALUES (".$IdFICree.",'5 Scotch De sécurité','Scotch de sécurité')";
					$resultAjour=mysqli_query($bdd,$req);
				}
				if(isset($_POST['MaterielSignaletique'])){
					$req="INSERT INTO sp_fi_moyenindustriel (Id_FI,NumCIA,Libelle) VALUES (".$IdFICree.",'6 Matériel De Signalétique','Matériel de signalétique')";
					$resultAjour=mysqli_query($bdd,$req);
				}
				if(isset($_POST['Autres'])){
					$req="INSERT INTO sp_fi_moyenindustriel (Id_FI,NumCIA,Libelle) VALUES (".$IdFICree.",'7 Autres','Autres')";
					$resultAjour=mysqli_query($bdd,$req);
				}
				//Ajout des mesures de sécurité
				if(isset($_POST['pasDeMesure'])){
					$req="UPDATE sp_ficheintervention SET PasDeMesure=1 WHERE Id=".$IdFICree."";
					$resultAjour=mysqli_query($bdd,$req);
				}
				if(isset($_POST['alimElec'])){
					$num="";
					if($_POST['alimElec']=="OP"){$num="1";}
					elseif($_POST['alimElec']=="INOP"){$num="2";}
					elseif($_POST['alimElec']=="PasImpact"){$num="3";}
					elseif($_POST['alimElec']=="ADefinir"){$num="4";}
					$req="INSERT INTO sp_fi_mesuresecurite (Id_FI,MesureSecurite,Type,NumCIA) VALUES (".$IdFICree.",'Alimentation électrique','".$_POST['alimElec']."','".$num." Alimentation électrique')";
					$resultAjour=mysqli_query($bdd,$req);
				}
				if(isset($_POST['systemeHVert'])){
					$num="";
					if($_POST['systemeHVert']=="OP"){$num="1";}
					elseif($_POST['systemeHVert']=="INOP"){$num="2";}
					elseif($_POST['systemeHVert']=="PasImpact"){$num="3";}
					elseif($_POST['systemeHVert']=="ADefinir"){$num="4";}
					$req="INSERT INTO sp_fi_mesuresecurite (Id_FI,MesureSecurite,Type,NumCIA) VALUES (".$IdFICree.",'Système hydraulique vert','".$_POST['systemeHVert']."','".$num." Système hydraulique vert')";
					$resultAjour=mysqli_query($bdd,$req);
				}
				if(isset($_POST['systemeHJaune'])){
					$num="";
					if($_POST['systemeHJaune']=="OP"){$num="1";}
					elseif($_POST['systemeHJaune']=="INOP"){$num="2";}
					elseif($_POST['systemeHJaune']=="PasImpact"){$num="3";}
					elseif($_POST['systemeHJaune']=="ADefinir"){$num="4";}
					$req="INSERT INTO sp_fi_mesuresecurite (Id_FI,MesureSecurite,Type,NumCIA) VALUES (".$IdFICree.",'Système hydraulique jaune','".$_POST['systemeHJaune']."','".$num." Système hydraulique jaune')";
					$resultAjour=mysqli_query($bdd,$req);
				}
				if(isset($_POST['systemeHBleu'])){
					$num="";
					if($_POST['systemeHBleu']=="OP"){$num="1";}
					elseif($_POST['systemeHBleu']=="INOP"){$num="2";}
					elseif($_POST['systemeHBleu']=="PasImpact"){$num="3";}
					elseif($_POST['systemeHBleu']=="ADefinir"){$num="4";}
					$req="INSERT INTO sp_fi_mesuresecurite (Id_FI,MesureSecurite,Type,NumCIA) VALUES (".$IdFICree.",'Système hydraulique bleu','".$_POST['systemeHBleu']."','".$num." Système hydraulique bleu')";
					$resultAjour=mysqli_query($bdd,$req);
				}
				if(isset($_POST['commandeVol'])){
					$num="";
					if($_POST['commandeVol']=="OP"){$num="1";}
					elseif($_POST['commandeVol']=="INOP"){$num="2";}
					elseif($_POST['commandeVol']=="PasImpact"){$num="3";}
					elseif($_POST['commandeVol']=="ADefinir"){$num="4";}
					$req="INSERT INTO sp_fi_mesuresecurite (Id_FI,MesureSecurite,Type,NumCIA) VALUES (".$IdFICree.",'Commandes de vol','".$_POST['commandeVol']."','".$num." Commandes de vol')";
					$resultAjour=mysqli_query($bdd,$req);
				}
				if(isset($_POST['trainAtterissage'])){
					$num="";
					if($_POST['trainAtterissage']=="OP"){$num="1";}
					elseif($_POST['trainAtterissage']=="INOP"){$num="2";}
					elseif($_POST['trainAtterissage']=="PasImpact"){$num="3";}
					elseif($_POST['trainAtterissage']=="ADefinir"){$num="4";}
					$req="INSERT INTO sp_fi_mesuresecurite (Id_FI,MesureSecurite,Type,NumCIA) VALUES (".$IdFICree.",'Trains d\'atterissage','".$_POST['trainAtterissage']."','".$num." Trains d\'atterissage')";
					$resultAjour=mysqli_query($bdd,$req);
				}
				if(isset($_POST['zeroStress'])){
					$num="";
					if($_POST['zeroStress']=="OP"){$num="1";}
					elseif($_POST['zeroStress']=="INOP"){$num="2";}
					elseif($_POST['zeroStress']=="PasImpact"){$num="3";}
					elseif($_POST['zeroStress']=="ADefinir"){$num="4";}
					$req="INSERT INTO sp_fi_mesuresecurite (Id_FI,MesureSecurite,Type,NumCIA) VALUES (".$IdFICree.",'Condition zéro stress','".$_POST['zeroStress']."','".$num." Condition zéro stress')";
					$resultAjour=mysqli_query($bdd,$req);
				}
				if(isset($_POST['moteurPneu'])){
					$Ref="";
					if($_POST['moteurPneu']=="Moteur"){$Ref=$_POST['numMoteur'];}
					elseif($_POST['moteurPneu']=="APU"){$Ref="APU";}
					elseif($_POST['moteurPneu']=="N/A"){$Ref="N/A";}
					$req="UPDATE sp_ficheintervention SET Pneumatique='".$Ref."' WHERE Id=".$IdFICree."";
					$resultAjour=mysqli_query($bdd,$req);
				}
				if(isset($_POST['pneumatique'])){
					$num="";
					if($_POST['pneumatique']=="OP"){$num="1";}
					elseif($_POST['pneumatique']=="INOP"){$num="2";}
					elseif($_POST['pneumatique']=="PasImpact"){$num="3";}
					elseif($_POST['pneumatique']=="ADefinir"){$num="4";}
					$req="INSERT INTO sp_fi_mesuresecurite (Id_FI,MesureSecurite,Type,NumCIA) VALUES (".$IdFICree.",'Pneumatique','".$_POST['pneumatique']."','".$num." Pneumatique')";
					$resultAjour=mysqli_query($bdd,$req);
				}
				if(isset($_POST['kerozene'])){
					$num="";
					if($_POST['kerozene']=="OP"){$num="1";}
					elseif($_POST['kerozene']=="INOP"){$num="2";}
					elseif($_POST['kerozene']=="PasImpact"){$num="3";}
					elseif($_POST['kerozene']=="ADefinir"){$num="4";}
					$req="INSERT INTO sp_fi_mesuresecurite (Id_FI,MesureSecurite,Type,NumCIA) VALUES (".$IdFICree.",'Kérozène','".$_POST['kerozene']."','".$num." Kérozène')";
					$resultAjour=mysqli_query($bdd,$req);
				}
				if(isset($_POST['systemeRefroidissementSupp'])){
					$num="";
					if($_POST['systemeRefroidissementSupp']=="OP"){$num="1";}
					elseif($_POST['systemeRefroidissementSupp']=="INOP"){$num="2";}
					elseif($_POST['systemeRefroidissementSupp']=="PasImpact"){$num="3";}
					elseif($_POST['systemeRefroidissementSupp']=="ADefinir"){$num="4";}
					$req="INSERT INTO sp_fi_mesuresecurite (Id_FI,MesureSecurite,Type,NumCIA) VALUES (".$IdFICree.",'Système de refroidissement supplémentaire','".$_POST['systemeRefroidissementSupp']."','".$num." Système de refroidissement supplémentaire')";
					$resultAjour=mysqli_query($bdd,$req);
				}
				if(isset($_POST['eauPotable'])){
					$num="";
					if($_POST['eauPotable']=="OP"){$num="1";}
					elseif($_POST['eauPotable']=="INOP"){$num="2";}
					elseif($_POST['eauPotable']=="PasImpact"){$num="3";}
					elseif($_POST['eauPotable']=="ADefinir"){$num="4";}
					$req="INSERT INTO sp_fi_mesuresecurite (Id_FI,MesureSecurite,Type,NumCIA) VALUES (".$IdFICree.",'Eau potable','".$_POST['eauPotable']."','".$num." Eau potable')";
					$resultAjour=mysqli_query($bdd,$req);
				}
				//Ajout des risques
				if(isset($_POST['TravailHauteur'])){
					$req="INSERT INTO sp_fi_risque (Id_FI,NumCIA,Libelle) VALUES (".$IdFICree.",'1 Travail en hauteur','Travail en hauteur')";
					$resultAjour=mysqli_query($bdd,$req);
				}
				if(isset($_POST['ConduiteEngins'])){
					$req="INSERT INTO sp_fi_risque (Id_FI,NumCIA,Libelle) VALUES (".$IdFICree.",'2 Conduite d\'engins','Conduite d\'engins')";
					$resultAjour=mysqli_query($bdd,$req);
				}
				if(isset($_POST['TravailZoneConfinee'])){
					$req="INSERT INTO sp_fi_risque (Id_FI,NumCIA,Libelle) VALUES (".$IdFICree.",'3 Travail en zone confinée','Travail en zone confinée')";
					$resultAjour=mysqli_query($bdd,$req);
				}
			}
		}
		if(substr($_SESSION['DroitSP'],0,1)=='1'){
			echo "<script>GenererFicheSuiveuse(".$_POST['idDossier'].")</script>";
		}
		echo "<script>FermerEtRecharger();</script>";
	}
}
if($_GET){
	$FI=$_GET['Id'];
	$IdPersonne=$_GET['Id_Personne'];
	$req="SELECT sp_ficheintervention.Id,sp_ficheintervention.Id_Dossier,sp_dossier.MSN,sp_dossier.Reference,sp_dossier.SectionACP AS MCA,";
	$req.="(SELECT sp_zonedetravail.Libelle FROM sp_zonedetravail WHERE sp_zonedetravail.Id=sp_dossier.Id_ZoneDeTravail) AS Zone,ResponsableZone, ";
	$req.="(SELECT sp_urgence.Libelle FROM sp_urgence WHERE sp_urgence.Id=sp_dossier.Id_Urgence) AS Urgence,sp_dossier.DateCreation,sp_dossier.DateCreationACP,sp_dossier.ACP_Id, ";
	$req.="sp_dossier.TypeACP AS Type,sp_dossier.Origine,sp_dossier.NumOrigine,sp_dossier.TAI_RestantACP AS TAI_Restant,sp_dossier.CaecACP AS Caec,";
	$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_dossier.Id_Personne) AS CreateurDossier, ";
	$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_ficheintervention.Id_Createur) AS CreateurFI,sp_ficheintervention.DateCreation AS DateCreationFI, ";
	$req.="sp_dossier.Priorite,sp_dossier.Titre,sp_ficheintervention.NumFI, sp_ficheintervention.Vacation,sp_dossier.Id_Urgence,sp_dossier.PNE, ";
	$req.="sp_dossier.Id_ZoneDeTravail,sp_dossier.Elec,sp_dossier.Systeme,sp_dossier.Structure,sp_dossier.Oxygene,sp_dossier.Hydraulique,";
	$req.="sp_dossier.Fuel,sp_dossier.Metal,sp_dossier.CommentaireZICIA,sp_ficheintervention.PosteAvionACP,sp_ficheintervention.Id_Pole,";
	$req.="sp_ficheintervention.Commentaire,sp_ficheintervention.CommentaireMICIA,sp_ficheintervention.CommentairePOCIA,sp_ficheintervention.InfoSuppPOCIA,";
	$req.="sp_ficheintervention.RaisonInterventionCIA,sp_ficheintervention.ESN,sp_ficheintervention.StatutCIA,sp_ficheintervention.TypeCIA,sp_ficheintervention.Id_ActiviteCIA,";
	$req.="sp_ficheintervention.Id_TypeTravailCIA,sp_ficheintervention.RefAInstallerCIA,sp_ficheintervention.Id_ImpactElementCIA,sp_ficheintervention.RefCableCIA,";
	$req.="sp_ficheintervention.DescriptionTypeTravailCIA,sp_ficheintervention.PowerOffPartielCIA,sp_ficheintervention.Id_StatutPROD,sp_ficheintervention.DateCreationPROD,";
	$req.="sp_ficheintervention.Id_RetourPROD,sp_ficheintervention.Id_PROD,sp_ficheintervention.Id_StatutQUALITE,sp_ficheintervention.DateCreationQUALITE,sp_ficheintervention.Id_RetourQUALITE,";
	$req.="sp_ficheintervention.Id_QUALITE,sp_ficheintervention.CommentairePROD,sp_ficheintervention.CommentaireQUALITE,sp_ficheintervention.Avancement,sp_ficheintervention.SaisieQualite,";
	$req.="sp_ficheintervention.DateIntervention,sp_ficheintervention.TravailRealise,sp_ficheintervention.Id_StatutPROD,sp_ficheintervention.Id_StatutQUALITE,sp_ficheintervention.EtatICCIA,Pneumatique ";
	$req.="FROM sp_ficheintervention LEFT JOIN sp_dossier ON sp_ficheintervention.Id_Dossier=sp_dossier.Id ";
	$req.="WHERE sp_ficheintervention.Id=".$FI;
	$result=mysqli_query($bdd,$req);
	$row=mysqli_fetch_array($result);
?>
<form id="formulaire" class="test" method="POST" action="Indicer_Dossier.php" onSubmit="return VerifChamps();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Indicer une intervention</td>
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
		<td colspan="6" align="left">&nbsp; 
			<?php if($row['SaisieQualite']==0){ ?>
				<a style="text-decoration:none;color:#0066CC;" id="saisiePROD" class="Bouton" href="javascript:DupliquePROD()">&nbsp;Saisie PROD&nbsp;</a>&nbsp;&nbsp;
				<a style="text-decoration:none;color:#888888;" id="saisieQUALITE" class="Bouton2" href="javascript:DupliqueQUALITE()">&nbsp;Saisie QUALITE&nbsp;</a>
			<?php } 
				else { 
			?>
				<a style="text-decoration:none;color:#888888;" id="saisiePROD" class="Bouton2" href="javascript:DupliquePROD()">&nbsp;Saisie PROD&nbsp;</a>&nbsp;&nbsp;
				<a style="text-decoration:none;color:#0066CC;" id="saisieQUALITE" class="Bouton" href="javascript:DupliqueQUALITE()">&nbsp;Saisie QUALITE&nbsp;</a>
			<?php } ?>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td width="13%" class="Libelle">&nbsp; MSN : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td width="20%">
				<input id='msn' name='msn' onKeyUp="nombre(this)" value='<?php echo $row['MSN'];?>'></td>
			</td>
			<td width="13%" class="Libelle">&nbsp; N° dossier : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
			<td width="20%">
				<input id='numDossier' name='numDossier' value='<?php echo $row['Reference'];?>' readonly="readonly"></td>
			</td>
			<td colspan="2" align="left">
				<a style="text-decoration:none;" class="Bouton" href="javascript:FicheSuiveuse('<?php echo $row['Id_Dossier'];?>')">&nbsp;Fiche suiveuse&nbsp;</a>
			</td>
			<td width="20%"></td>

		</tr>
		<tr><td height="4"></td></tr>
		<?php
		$reqTERA="SELECT Id FROM sp_ficheintervention WHERE Id_Dossier=".$row['Id_Dossier']." AND Id<>".$FI." AND Id_StatutPROD='QARJ'";
		$resultTERA=mysqli_query($bdd,$reqTERA);
		$nbTERA=mysqli_num_rows($resultTERA);
					
		echo "<tr style='display:none;'><td><input id='idDossier' name='idDossier' value='".$row['Id_Dossier']."'  readonly='readonly'></td></tr>";
		echo "<tr style='display:none;'><td><input id='idFI' name='idFI' value='".$FI."'  readonly='readonly'></td></tr>";
		echo "<tr>";
		echo "<td width='13%' class='Libelle'>&nbsp; Créateur : </td><td width='20%'>".$row['CreateurDossier']."</td>";
		echo "<td width='13%' class='Libelle'>&nbsp; Date de création : </td><td width='20%'>".$row['DateCreation']."</td>";
		echo "</tr>";
		echo "<tr><td height='4'></td></tr>";
		echo "<tr>";
		echo "<td width='13%' class='Libelle'>&nbsp; Type : </td><td width='20%'><input id='typeDossier' name='typeDossier' value='".$row['Type']."' readonly='readonly'></td>";
		$visible="";
		if($row['Type']<>"OW-S03"){
			$visible="style='display:none;'";
		}
		$read="";
		$disabled="";
		$etoile="<img src='../../../Images/etoile.png' width='8' height='8' border='0'>";
		if(substr($_SESSION['DroitSP'],0,1)=='0' && substr($_SESSION['DroitSP'],1,1)=='0' && substr($_SESSION['DroitSP'],4,1)=='0'){
			$read="readonly='readonly'";
			$disabled="disabled='disabled'";
			$etoile="";
		}
		echo "<tr style='display:none;'><td><input id='droit' name='droit' value='".$_SESSION['DroitSP']."'  readonly='readonly'></td></tr>";
		echo "<tr id='LigneQualite0'>";
		echo "<td width='13%' class='Libelle' ".$visible.">&nbsp; Origine : ".$etoile."</td><td width='20%' ".$visible.">";
			?>
			<select id='origine' name='origine' onchange="AfficherDepose();" <?php echo $disabled;?>>
				<option name='' value=''></option>
				<option name='Admin' value='Admin' <?php if($row['Origine']=='Admin'){echo "selected";} ?>>Admin</option>
				<option name='DA' value='DA' <?php if($row['Origine']=='DA'){echo "selected";} ?>>DA</option>
				<option name='NC' value='NC' <?php if($row['Origine']=='NC'){echo "selected";} ?>>NC</option>
				<option name='PNE' value='PNE' <?php if($row['Origine']=='PNE'){echo "selected";} ?>>PNE</option>
			</select>
			<?php
		echo "</td>";
		echo "<td width='13%'class='Libelle' ".$visible.">&nbsp; N° origine : </td><td width='20%' ".$visible.">";
		?>
			<input id='numOrigine' name='numOrigine' value='<?php echo $row['NumOrigine']; ?>' <?php echo $read;?>></td>
		<?php
		echo "</tr>";
		echo "<tr><td height='4'></td></tr>";
		echo "<tr id='LigneQualite1'>";
		echo "<td width='13%' class='Libelle'>&nbsp; Tps restant (h) : </td><td width='20%'><input id='tai' onKeyUp='nombre(this)' name='tai' value='".$row['TAI_Restant']."'></td>";
		echo "<td width='13%' class='Libelle'>&nbsp; CA/EC : </td><td width='20%'><input id='caec' name='caec' value='".$row['Caec']."' readonly='readonly'></td>";
		echo "</tr>";
		echo "<tr style='display:none;'>";
		echo "<td width='13%' class='Libelle'>&nbsp; Date création ACP : </td><td width='20%'><input id='dateCreationACP' name='dateCreationACP' value='".$row['DateCreationACP']."' readonly='readonly'></td>";
		echo "<td width='13%' class='Libelle'>&nbsp; ACP ID : </td><td width='20%'><input id='ACP_Id' name='ACP_Id' value='".$row['ACP_Id']."' readonly='readonly'></td>";
		echo "</tr>";
		echo "</tr>";
		echo "<tr><td height='4'></td></tr>";
		echo "<tr>";
			echo "<td width='13%' class='Libelle' id='LibelleUrgence'>&nbsp; Urgence : </td><td width='20%'>";
			echo"<select id='urgence' name='urgence' ".$disabled.">";
				echo"<option name='0' value='0'></option>";
				$req="SELECT Id,Libelle FROM sp_urgence WHERE Supprime=0 ORDER BY Libelle;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					while($rowUrgence=mysqli_fetch_array($result)){
						$selected="";
						if($row['Id_Urgence']==$rowUrgence['Id']){$selected="selected";}
						echo "<option name='".$rowUrgence['Id']."' value='".$rowUrgence['Id']."' ".$selected.">".$rowUrgence['Libelle']."</option>";
					}
				}
			echo"</select>";
			echo "</td>";
			echo "<td width='13%' class='Libelle'>&nbsp; Titre : ".$etoile."</td><td width='20%' colspan='3'>";
			?>
				<input id="titre" name="titre" value="<?php echo stripslashes($row['Titre']); ?>" style="width: 80%;" <?php echo $read;?>>
			<?php
			echo "</td>";
		echo "</tr>";
		echo "<tr id='LigneQualite2'>";
			echo "<td width='13%' class='Libelle'>&nbsp; Zone de travail : ".$etoile."</td><td width='20%'>";
			echo"<select id='zone' name='zone' ".$disabled.">";
				echo"<option name='0' value='0'></option>";
				$req="SELECT Id,Libelle FROM sp_zonedetravail ORDER BY Libelle;";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					while($rowZone=mysqli_fetch_array($result)){
						$selected="";
						if($row['Id_ZoneDeTravail']==$rowZone['Id']){$selected="selected";}
						echo "<option name='".$rowZone['Id']."' value='".$rowZone['Id']."' ".$selected.">".$rowZone['Libelle']."</option>";
					}
				}
			echo"</select>";
			echo "</td>";
			echo "<td width='13%' class='Libelle'>&nbsp; Commentaire<br>&nbsp; zone d'intervention (IC) : </td><td width='20%' colspan='3'>";
			?>
				<input id="commentaireZI" name="commentaireZI" value="<?php echo stripslashes($row['CommentaireZICIA']);?>" style="width: 80%;" <?php echo $read;?>>
			<?php
			echo "</td>";
		echo "</tr>";
		echo "<tr id='LigneQualite3'>";
			echo "<td width='13%' class='Libelle' valign='top'>&nbsp; Compétence(s) : ".$etoile."</td>";
			echo "<td width='20%'>";
			echo "<table width='100%' cellpadding='0' cellspacing='0' align='left'>";
			?>
				<tr>
				<td><input type="checkbox" id="Fuel" name="Fuel" value="Fuel" <?php echo $disabled;?> <?php if($row['Fuel']==true){echo "checked";} ?>>Fuel &nbsp;&nbsp;</td>
				<td><input type="checkbox" id="Elec" name="Elec" value="Elec" <?php echo $disabled;?> <?php if($row['Elec']==true){echo "checked";} ?>>Elec &nbsp;&nbsp;</td>
				<td><input type="checkbox" id="Hydraulique" name="Hydraulique" <?php echo $disabled;?> value="Hydraulique" <?php if($row['Hydraulique']==true){echo "checked";} ?>>Hydraulique &nbsp;&nbsp;</td>
				</tr>
				<tr>
				<td><input type="checkbox" id="Metal" name="Metal" value="Metal" <?php echo $disabled;?> <?php if($row['Metal']==true){echo "checked";} ?>>Metal &nbsp;&nbsp;</td>
				<td><input type="checkbox" id="Structure" name="Structure" value="Structure" <?php echo $disabled;?> <?php if($row['Structure']==true){echo "checked";} ?>>Structure &nbsp;&nbsp;</td>
				<td><input type="checkbox" id="Systeme" name="Systeme" value="Systeme" <?php echo $disabled;?> <?php if($row['Systeme']==true){echo "checked";} ?>>Systeme &nbsp;&nbsp;</td>
				</tr>
				<tr>
				<td><input type="checkbox" id="Oxygene" name="Oxygene" value="Oxygene" <?php echo $disabled;?> <?php if($row['Oxygene']==true){echo "checked";} ?>>Oxygene &nbsp;&nbsp;</td>
				</tr>
			<?php
			echo "</table>";
			echo "</td>";
			echo "<td width='13%' class='Libelle' valign='top'>&nbsp; Priorité : ".$etoile."</td>";
			echo "<td width='20%' valign='top'>";
			?>
				<select id="priorite" name="priorite" <?php echo $disabled;?>>
					<option value="1" <?php if($row['Priorite']==1){echo "selected";} ?>>Low</option>
					<option value="2" <?php if($row['Priorite']==2){echo "selected";} ?>>Medium</option>
					<option value="3" <?php if($row['Priorite']==3){echo "selected";} ?>>High</option>
				</select>
			<?php
			echo "</td>";
			echo "<td width='15%' class='Libelle'>";
				echo "<table cellpadding='0' cellspacing='0' style='-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;'>";
				echo "<tr>";
				echo "<td colspan='2'>&nbsp; Liste des ATA/Sous-ATA : ".$etoile."";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td bgcolor='#e4e7f0'>";
				echo "&nbsp; ATA : ";
				echo "</td>";
				echo "<td bgcolor='#e4e7f0'>";
				echo"<select name='ata' id='ata' onchange='Recharge_SousATA();' onkeypress='if(event.keyCode == 13)Ajouter()'>";
					echo"<option name='' value=''></option>";
					$req="SELECT DISTINCT ATA FROM sp_atasousata ORDER BY ATA;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($rowATA=mysqli_fetch_array($result)){
							echo "<option name='".$rowATA['ATA']."' value='".$rowATA['ATA']."'>".$rowATA['ATA']."</option>";
						}
					}
				echo"</select>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td bgcolor='#e4e7f0'>&nbsp;Sous-ATA : </td>";
				echo "<td bgcolor='#e4e7f0'>";
				echo "<div id='sousatas'>";
					echo"<select id='sousata' name='sousata' onkeypress='if(event.keyCode == 13)Ajouter()'>";
						echo"<option value=''></option>";
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
					echo"</select>";
				echo "</div>";
				echo "</td>";
				echo "</tr>";
				echo "<tr valign='top'>";
					echo "<td bgcolor='#e4e7f0'>&nbsp;Isolation elec. système : </td>";
					echo "<td bgcolor='#e4e7f0'>";
						echo"<select id='isolationElec' name='isolationElec' onkeypress='if(event.keyCode == 13)Ajouter()'>";
							echo"<option value='0' Selected>Non</option>";
							echo"<option value='1'>Oui</option>";
						echo"</select>";
					echo "</td>";
					echo "</tr>";
				echo "<tr>";
				echo "<tr>";
				echo "<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>";
					if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
						echo "<a style='text-decoration:none;' class='Bouton' href='javascript:Ajouter()'>&nbsp;Ajouter&nbsp;</a>";
					}
					echo "</td>";
				echo "</tr>";
				echo "</table>";
			echo "</td>";
			echo "<td width='20%' valign='top'><table id='tab_ATA' width='100%' cellpadding='0' cellspacing='0'>";
			echo "<tr><td width='10%' class='Libelle'>ATA</td><td width='15%' class='Libelle'>Sous-ATA</td><td width='40%' class='Libelle'>Isolation elec. <br>système</td><td></td></tr>";
			$listeATA="";
			$req="SELECT ATA, SousATA, IsolationElec FROM sp_dossier_ata WHERE Id_Dossier=".$row['Id_Dossier']." ORDER BY ATA, SousATA;";
			$result=mysqli_query($bdd,$req);
			$nbResultaATA=mysqli_num_rows($result);
			if ($nbResultaATA>0){
				while($rowATA=mysqli_fetch_array($result)){
					$btn="";
					if(substr($_SESSION['DroitSP'],0,1)=='1' || substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
						$btn="<a style=\"text-decoration:none;\" href=\"javascript:Supprimer('".$rowATA['ATA']."_".$rowATA['SousATA']."_".$rowATA['IsolationElec'].";')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					}
					echo "<tr id='".$rowATA['ATA']."_".$rowATA['SousATA']."_".$rowATA['IsolationElec'].";'>";
					echo "<td>".$rowATA['ATA']."</td>";
					echo "<td>".$rowATA['SousATA']."</td>";
					$Isolation="Non";
					If($rowATA['IsolationElec']==1){$Isolation="Oui";}
					echo "<td>".$Isolation."</td>";
					echo "<td>".$btn."</td></tr>";
					$listeATA.=$rowATA['ATA']."_".$rowATA['SousATA']."_".$rowATA['IsolationElec'].";";
				}
			}
			echo "</table></td>";
			echo "</tr>";
			$nbTempsDossier=0;
			$reqSum="SELECT SUM(sp_fi_travaileffectue.TempsPasse) AS Tps FROM sp_fi_travaileffectue LEFT JOIN sp_ficheintervention ON sp_fi_travaileffectue.Id_FI=sp_ficheintervention.Id WHERE sp_ficheintervention.Id_Dossier=".$row['Id_Dossier'];
			$resultSum=mysqli_query($bdd,$reqSum);
			$nbSum=mysqli_num_rows($resultSum);
			if ($nbSum>0){
				$rowSum=mysqli_fetch_array($resultSum);
				if($rowSum['Tps']<>""){$nbTempsDossier=$rowSum['Tps'];}
			}
			echo "<tr id='LigneQualite4'><td width='13%' valign='top' class='Libelle'>Temps passé : </td><td width='20%' valign='top'><input type='text' size='4' style='border:none' name='tpsDossier' id='tpsDossier' value='".$nbTempsDossier."' readonly='readonly'/></td>";
			?>
			<td width="13%" class="Libelle" valign="top">&nbsp; PNE : <?php echo $etoile;?></td>
			<td width="20%" valign="top">
				<select id="pne" name="pne">
					<option name="0" value="0" <?php if($row['PNE']==0){echo "selected";} ?>>Non</option>
					<option name="1" value="1" <?php if($row['PNE']==1){echo "selected";} ?>>Oui</option>
				</select>
			</td>
			</tr>
			<?php
			echo "<tr style='display:none;'><td><input id='ata_sousata' name='ata_sousata' value='".$listeATA."'  readonly='readonly'></td></tr>";
			?>
			<tr><td height="8"></td></tr>
			</table></td></tr>
			<tr><td height="4"></td></tr>
				<tr><td>
				<table width="100%" cellpadding="3" cellspacing="0" align="center" class="GeneralInfo">
					<tr>
						<td colspan="9" style="font-size:15px;font-weight:bold;color:#4d4a82;" align="center">HISTORIQUE DES INTERVENTIONS</td>
					</tr>
					<tr>
						<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;">Pôle</td>
						<td class="EnTeteTableauCompetences" width="12%" style="text-align:center;">Date intervention PROD</td>
						<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;">Vacation PROD</td>
						<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;">Statut PROD</td>
						<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;">Retour PROD</td>
						<td class="EnTeteTableauCompetences" width="12%" style="text-align:center;">Date intervention QUALITE</td>
						<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;">Vacation QUALITE</td>
						<td class="EnTeteTableauCompetences" width="6%" style="text-align:center;">Statut QUALITE</td>
						<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;">Retour QUALITE</td>
					</tr>
					<?php
						$reqHistorique = "SELECT sp_ficheintervention.DateIntervention, sp_ficheintervention.Id_StatutPROD, sp_ficheintervention.DateInterventionQ, sp_ficheintervention.Id_StatutQUALITE, ";
						$reqHistorique .= "sp_ficheintervention.Vacation, sp_ficheintervention.VacationQ, ";
						$reqHistorique .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=sp_ficheintervention.Id_Pole) AS Pole, ";
						$reqHistorique .= "(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourPROD) AS RetourProd, ";
						$reqHistorique .= "(SELECT sp_retour.Libelle FROM sp_retour WHERE sp_retour.Id=sp_ficheintervention.Id_RetourQUALITE) AS RetourQualite ";
						$reqHistorique .= "FROM sp_ficheintervention ";
						$reqHistorique .= "WHERE sp_ficheintervention.Id_Dossier=".$row['Id_Dossier'];
						$resultH=mysqli_query($bdd,$reqHistorique);
						$nbH=mysqli_num_rows($resultH);
						if ($nbH>0){
							while($rowH=mysqli_fetch_array($resultH)){
								?>
									<tr>
									<td width="6%" style="text-align:center;"><?php echo $rowH['Pole'];?></td>
									<td width="12%" style="text-align:center;"><?php echo AfficheDateFR($rowH['DateIntervention']);?></td>
									<td width="10%" style="text-align:center;"><?php echo $rowH['Vacation'];?></td>
									<td width="6%" style="text-align:center;"><?php echo $rowH['Id_StatutPROD'];?></td>
									<td width="10%" style="text-align:center;"><?php echo $rowH['RetourProd'];?></td>
									<td width="12%" style="text-align:center;"><?php echo AfficheDateFR($rowH['DateInterventionQ']);?></td>
									<td width="10%" style="text-align:center;"><?php echo $rowH['VacationQ'];?></td>
									<td width="6%" style="text-align:center;"><?php echo $rowH['Id_StatutQUALITE'];?></td>
									<td width="10%" style="text-align:center;"><?php echo $rowH['RetourQualite'];?></td>
									</tr>
								<?php
							}
						}
					?>
				</table>
			</td></tr>
			<tr><td height="4"></td></tr>
			<tr><td>
			<table width="100%" cellpadding="3" cellspacing="0" align="center" class="GeneralInfo">
				<tr>
					<td colspan="6" style="font-size:15px;font-weight:bold;color:#4d4a82;" align="center">INFORMATIONS INTERVENTION</td>
				</tr>
				<tr>
					<td colspan="2" width="25%" valign="top">
						<table width="100%" cellpadding="0" cellspacing="0" align="center">
							<tr><td height="4" colspan="2"></td></tr>
							<tr>
								<td width="13%" class="Libelle">&nbsp; Responsable zone : </td>
								<td width="20%">
									<select id="responsableZone" name="responsableZone" <?php echo $disabled;?>>
										<option value=""></option>
										<option value="Cabine">Cabine</option>
										<option value="Green">Green</option>
									</select>
								</td>
							</tr>
							<tr><td height="4" colspan="2"></td></tr>
							<tr>
								<td width="13%" class="Libelle">&nbsp; Pôle : <?php echo $etoile;?></td>
								<td width="20%">
									<select id="pole" name="pole" <?php echo $disabled;?>>
										<option name="0" value="0"></option>
										<?php
											$req="SELECT Id, Libelle FROM new_competences_pole WHERE (Id IN (1,2,3,5,6,42) AND Actif=0 AND Id_Prestation=255) OR Id=176 ORDER BY Libelle;";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											if ($nbResulta>0){
												while($rowPole=mysqli_fetch_array($result)){
													$selected="";
													if($rowPole['Id']==$row['Id_Pole']){$selected="selected";}
													echo "<option name='".$rowPole['Id']."' value='".$rowPole['Id']."' ".$selected.">".$rowPole['Libelle']."</option>";
												}
											}
										?>
									</select>
								</td>
							</tr>
							<tr><td height="4" colspan="2"></td></tr>
							<tr>
								<td width="13%" class="Libelle" id="LibellePieceauposte">&nbsp; Pièce à retirer <br> &nbsp; au poste : </td>
								<td width="20%">
									<select id="pieceauposte" name="pieceauposte" <?php echo $disabled;?>>
										<option value=""></option>
										<option value="Chariot de DA">Chariot de DA</option>
										<option value="K943">K943</option>
										<option value="Station livraison">Station livraison</option>
									</select>
								</td>
							</tr>
							<?php
								$displayDA="";
								if($row['Origine']<>"DA"){$displayDA="style='display:none;'";}
							?>
							<tr class="depose" id='LigneQualite5' <?php echo $displayDA; ?>><td height="4" colspan="2"></td></tr>
								<tr class="depose" id='LigneQualite6' <?php echo $displayDA; ?>>
									<td width="13%" class="Libelle">
										&nbsp; Dépose <?php echo $etoile;?> <input type="radio" id='deposeRepose' name='deposeRepose' value="0" checked>
									</td>
									<td class="Libelle">
										&nbsp; Repose <input type="radio" id='deposeRepose' name='deposeRepose' value="1">
									</td>
								</tr>
							<tr><td height="4" colspan="2"></td></tr>
							<tr>
								<td colspan="2" width="13%" class="Libelle" valign="top" id="LibelleTravailRealise">&nbsp; Travail à réaliser :  <?php echo $etoile;?></td>
							</tr>
							<tr><td height="4" colspan="2"></td></tr>
							<tr>
								<td colspan="2" width="20%">
									&nbsp;<textarea id="travailRealise" name="travailRealise" rows="5" cols="40" style="resize:none;" <?php echo $read;?>><?php echo stripslashes($row['TravailRealise']);?></textarea>
								</td>
							</tr>
							<tr><td height="4" colspan="2"></td></tr>
							<tr>
								<td colspan="2" width="13%" class="Libelle" valign="top">&nbsp; Commentaire : </td>
							</tr>
							<tr><td height="4" colspan="2"></td></tr>
							<tr>
								<td colspan="2" width="20%">
									&nbsp;<textarea id="commentaire" name="commentaire" rows="5" cols="40" style="resize:none;" <?php echo $read;?>></textarea>
								</td>
							</tr>
							<tr><td height="4" colspan="2"></td></tr>
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
								<td width="13%" class="Libelle" bgcolor="#dbeef9" valign="top">&nbsp; Raison d'indiçage : </td>
								<td width="20%" bgcolor="#dbeef9">
									<textarea id="raisonIndicage" name="raisonIndicage" rows="5" cols="40" style="resize:none;" <?php echo $readCE;?>></textarea>
								</td>
							</tr>
							<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
							<tr>
								<td bgcolor="#dbeef9" width="13%" class="Libelle">&nbsp; Date intervention : </td>
								<td bgcolor="#dbeef9" width="20%">
									<input <?php if(substr($_SESSION['DroitSP'],0,1)=='0' && substr($_SESSION['DroitSP'],1,1)=='0'){echo "type='texte'";}else{echo "type='date'";}?> style="text-align:center;" id="dateIntervention" name="dateIntervention" size="15" <?php echo  $readSTCE;?> value="">
								</td>
							</tr>
							<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
							<tr>
								<td bgcolor="#dbeef9" width="13%" class="Libelle">&nbsp; Vacation : </td>
								<td bgcolor="#dbeef9" width="20%" colspan="3">
									<select id="vacation" name="vacation" <?php echo $disabledSTCE;?>>
										<option name="" value=""></option>
										<option name="J" value="J">Jour</option>
										<option name="S" value="S">Soir</option>
										<option name="N" value="N">Nuit</option>
										<option name="VSD Jour" value="VSD Jour">VSD Jour</option>
										<option name="VSD Nuit" value="VSD Nuit">VSD Nuit</option>
									</select>
								</td>
							</tr>
							<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
							<tr>
								<td bgcolor="#dbeef9" width="13%" class="Libelle">&nbsp;Temps objectif : </td>
								<td bgcolor="#dbeef9" width="20%" colspan="3">
									<input onKeyUp="nombre(this)" id="tempsObjectif" <?php echo $readSTCE;?> name="tempsObjectif" size="5" type="text" value="">
								</td>
							</tr>
							<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
							<tr>
								<td bgcolor="#dbeef9" width='13%' class="Libelle" valign="top">
									<table cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
										<tr>
											<td bgcolor='#e4e7f0' colspan='2'><i>&nbsp; Ajouter les opérateurs :</i></td>
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
												$req.="FROM new_rh_etatcivil LEFT JOIN sp_acces ON new_rh_etatcivil.Id=sp_acces.Id_Personne WHERE sp_acces.Id_Prestation=255 AND SUBSTR(sp_acces.Droit,3,1)='1' ORDER BY Nom, Prenom;";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												if ($nbResulta>0){
													$i=0;
													while($rowPersonne=mysqli_fetch_array($result)){
														echo "<option name='".$rowPersonne['Id']."' value='".$rowPersonne['Id']."'>".$rowPersonne['Nom']." ".$rowPersonne['Prenom']."</option>";
														echo "<script>Liste_Personne[".$i."] = new Array('".$rowPersonne['Id']."','".addslashes($rowPersonne['Nom'])."','".addslashes($rowPersonne['Prenom'])."');</script>\n";
														$i+=1;
													}
												}
												?>
											</select>
											</td>
										</tr>
										<tr>
											<td width="40%" bgcolor='#e4e7f0'>&nbsp;Temps passé : </td>
										</tr>
										<tr>
											<td width="60%" bgcolor='#e4e7f0'>&nbsp; 
												<input onKeyUp="nombre(this)" style="text-align:center;" onkeypress="if(event.keyCode == 13)AjouterTE()" id="tempsPasse" name="tempsPasse" size="5" type="text" value="">
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
										<tr><td class="Libelle" width="70%">Personne</td><td class="Libelle">Nb. heures</td></tr>
									</table>
									<table id="tab_TravailEffectue" width='100%' cellpadding='0' cellspacing='0'>
										<tr><td class="Libelle" width="70%">TOTAL</td><td class="Libelle">
											<input type="text" size="4" style="background-color:#dbeef9;border:none" name="tpsFI" id="tpsFI" value="0" readonly="readonly"/>
										</td></tr>
									</table>
								</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>		
							<td width="13%" class="Libelle" bgcolor="#dbeef9">&nbsp; Statut PROD : </td>
							<td width="20%" bgcolor="#dbeef9">
								<select id="statutProd" name="statutProd" onchange="Recharge_StatutProd();" <?php echo $disabledCE;?>>
									<option name="" value="" selected></option>
									<?php
									if($nbTERA==0){$req="SELECT Id FROM sp_statut WHERE TypeStatut='P' AND Id<>'REWORK' ORDER BY Id;";}
									else{$req="SELECT Id FROM sp_statut WHERE TypeStatut='P' AND Id<>'QARJ' ORDER BY Id;";}
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowStatut=mysqli_fetch_array($result)){
											echo "<option name='".$rowStatut['Id']."' value='".$rowStatut['Id']."'>".$rowStatut['Id']."</option>";
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
								<select id="retourProd" name="retourProd" <?php echo $disabledCE;?>>
									<option name="0" value="0"></option>
									<?php
									$req="SELECT Id, Libelle, Id_Statut,Supprime FROM sp_retour WHERE Supprime=0 ORDER BY Libelle;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										$i=0;
										while($rowRetour=mysqli_fetch_array($result)){
											echo "<script>Liste_Retour[".$i."] = new Array(\"".$rowRetour['Id']."\",\"".str_replace('"',' ',$rowRetour['Libelle'])."\",\"".$rowRetour['Id_Statut']."\",\"".$rowRetour['Supprime']."\");</script>\n";
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
								<select id="avancementProd" name="avancementProd" <?php echo $disabledCE;?>>
									<?php
										echo "<option name='0' value='0' selected></option>";
									?>
								</select>
								</div>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#dbeef9" valign="top">&nbsp; Commentaire PROD : </td>
							<td width="20%" bgcolor="#dbeef9">
								<textarea id="commentairePROD" name="commentairePROD" rows="5" cols="40" style="resize:none;" <?php echo $readCE;?>></textarea>
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
								<input <?php if(substr($_SESSION['DroitSP'],4,1)=='0'){echo "type='texte'";}else{echo "type='date'";}?> style="text-align:center;" id="dateInterventionQ" name="dateInterventionQ" size="15" value="" <?php echo  $readIQ;?>>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td bgcolor="#c7e048" width="13%" class="Libelle">&nbsp; Vacation : </td>
							<td bgcolor="#c7e048" width="20%" colspan="3">
								<select id="vacationQ" name="vacationQ" <?php echo $disabledIQ;?>>
									<option name="" value=""></option>
									<option name="J" value="J">Jour</option>
									<option name="S" value="S">Soir</option>
									<option name="N" value="N">Nuit</option>
									<option name="VSD Jour" value="VSD Jour">VSD Jour</option>
									<option name="VSD Nuit" value="VSD Nuit">VSD Nuit</option>
								</select>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td bgcolor="#c7e048" width="13%" class="Libelle">
								&nbsp; Inspecteur qualité :
							</td>
							<td bgcolor="#c7e048" width="20%" colspan="3">
								<select name="IQ">
									<option name="" value=""></option>
									<?php
									$req="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS NomPrenom ";
									$req.="FROM new_rh_etatcivil LEFT JOIN sp_acces ON new_rh_etatcivil.Id=sp_acces.Id_Personne WHERE sp_acces.Id_Prestation=255 AND SUBSTR(sp_acces.Droit,5,1)=1 ORDER BY NomPrenom;";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($rowIQ=mysqli_fetch_array($result)){
											$selected="";
											if(substr($_SESSION['DroitSP'],4,1)=='1' && $_SESSION['Id_PersonneSP'] == $rowIQ['Id']){$selected="selected";}
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
									<select id="statutQualite" name="statutQualite" onchange="Recharge_StatutQualite();" <?php echo $disabledIQ;?>>
										<option name="" value=""></option>
										<?php
										$req="SELECT Id FROM sp_statut WHERE TypeStatut='Q' ORDER BY Id;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											$i=0;
											while($rowStatut=mysqli_fetch_array($result)){
												echo "<script>Liste_Statut[".$i."] = new Array('".$rowStatut['Id']."');</script>\n";
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
								<select id="retourQualite" name="retourQualite" <?php echo $disabledIQ;?>>
									<option name="0" value="0"></option>
								</select>
								</div>
							</td>
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
						<tr>
							<td width="13%" class="Libelle" bgcolor="#c7e048" valign="top">&nbsp; Commentaire QUALITE : </td>
							<td width="20%" bgcolor="#c7e048">
								<textarea id="commentaireQualite" name="commentaireQualite" rows="5" cols="40" style="resize:none;" <?php echo $readIQ;?>></textarea>
							</td>		
						</tr>
						<tr><td height="4" colspan="2" bgcolor="#c7e048"></td></tr>
					</table>
				</td>
				<td colspan="2" width="33%" valign="top">
					<table id="tabDesECMEPROD" width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr><td height="4" colspan="2" bgcolor="#dbeef9"></td></tr>
						<tr>
							<td bgcolor="#dbeef9" width='35%' class="Libelle" valign="center">
								<table width="100%" cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor="#e4e7f0" colspan='2'><i>&nbsp; Ajouter les ECME PROD :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; Type : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<select id="typeECMEPROD" name="typeECMEPROD" style="width:100px;" onchange="Recharge_RefECMEPROD()" onkeypress="if(event.keyCode == 13)AjouterECMEPROD()">
												<?php
												echo"<option name='0' value='0'></option>";
												$req="SELECT Id, Libelle FROM sp_typeecme WHERE Supprime=false ";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												if ($nbResulta>0){
													$i=0;
													while($rowType=mysqli_fetch_array($result)){
														echo "<option value='".$rowType['Id']."'>".$rowType['Libelle']."</option>";
														echo "<script>Liste_ECME[".$i."] = new Array('".$rowType['Id']."','".str_replace("'"," ",$rowType['Libelle'])."');</script>\n";
														$i+=1;
													}
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;Référence : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>
											<div id="listeECMEPROD">
												&nbsp; <select id="referencePROD" name="referencePROD" style="width:100px;" onkeypress="if(event.keyCode == 13)AjouterECMEPROD()">
													<?php
													echo"<option value='0'></option>";
													$req="SELECT Id, Libelle,Id_Type FROM sp_ecme WHERE Supprime=false ";
													$result=mysqli_query($bdd,$req);
													$nbResulta=mysqli_num_rows($result);
													if ($nbResulta>0){
														$i=0;
														while($rowECME=mysqli_fetch_array($result)){
															echo "<script>Liste_RefECMEPROD[".$i."] = new Array('".$rowECME['Id']."','".$rowECME['Id_Type']."','".str_replace("'"," ",$rowECME['Libelle'])."');</script>\n";
															$i+=1;
														}
													}
													?>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;OU autre ECME </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:left;" id="nomECMEPROD" onkeypress="if(event.keyCode == 13)AjouterECMEPROD()" name="nomECMEPROD" size="20" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
											<?php
												if(substr($_SESSION['DroitSP'],1,1)=='1'){
											?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterECMEPROD()'>&nbsp;Ajouter&nbsp;</a>
											<?php
												}
											?>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<?php
												$modifiableCE="";
												if(substr($_SESSION['DroitSP'],1,1)=='0'){$modifiableCE="onclick='return false'";}
												$modificationIQ="";
												if(substr($_SESSION['DroitSP'],4,1)=='0'){$modificationIQ="onclick='return false'";}
											?>
											<input style="text-align:left;" id="PasDeECMEPROD" <?php echo $modifiableCE; ?> name="PasDeECMEPROD" type="checkbox" value="PasDeECMEPROD"> Pas de ECME requis
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='65%' valign='top'>
								<table id="tab_ECMEPROD" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle">Type</td><td class="Libelle">Référence</td></tr>
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
										<td bgcolor="#e4e7f0" colspan='2'><i>&nbsp; Ajouter les ECME QUALITE :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; Type : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<select id="typeECMEQUALITE" name="typeECMEQUALITE" style="width:100px;" onchange="Recharge_RefECMEQUALITE()" onkeypress="if(event.keyCode == 13)AjouterECMEQUALITE()">
												<?php
												echo"<option name='0' value='0'></option>";
												$req="SELECT Id, Libelle FROM sp_typeecme WHERE Supprime=false ";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												if ($nbResulta>0){
													$i=0;
													while($rowType=mysqli_fetch_array($result)){
														echo "<option value='".$rowType['Id']."'>".$rowType['Libelle']."</option>";
														echo "<script>Liste_ECME[".$i."] = new Array('".$rowType['Id']."','".str_replace("'"," ",$rowType['Libelle'])."');</script>\n";
														$i+=1;
													}
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;Référence : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>
											<div id="listeECMEQUALITE">
												&nbsp; <select id="referenceQUALITE" name="referenceQUALITE" style="width:100px;" onkeypress="if(event.keyCode == 13)AjouterECMEQUALITE()">
													<?php
													echo"<option value='0'></option>";
													$req="SELECT Id, Libelle,Id_Type FROM sp_ecme WHERE Supprime=false ";
													$result=mysqli_query($bdd,$req);
													$nbResulta=mysqli_num_rows($result);
													if ($nbResulta>0){
														$i=0;
														while($rowECME=mysqli_fetch_array($result)){
															echo "<script>Liste_RefECMEQUALITE[".$i."] = new Array('".$rowECME['Id']."','".$rowECME['Id_Type']."','".str_replace("'"," ",$rowECME['Libelle'])."');</script>\n";
															$i+=1;
														}
													}
													?>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;OU autre ECME </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:left;" id="nomECMEQUALITE" onkeypress="if(event.keyCode == 13)AjouterECMEQUALITE()" name="nomECMEQUALITE" size="20" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
											<?php
												if(substr($_SESSION['DroitSP'],4,1)=='1'){
											?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterECMEQUALITE()'>&nbsp;Ajouter&nbsp;</a>
											<?php
												}
											?>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:left;" <?php echo $modificationIQ; ?> id="PasDeECMEQUALITE" name="PasDeECMEQUALITE" type="checkbox" value="PasDeECMEQUALITE"> Pas de ECME requis
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='65%' valign='top'>
								<table id="tab_ECMEQUALITE" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle">Type</td><td class="Libelle">Référence</td></tr>
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
										<td bgcolor="#e4e7f0" colspan='2'><i>&nbsp; Ajouter les ECME CLIENT :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; Type : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<select id="typeECMECLIENT" name="typeECMECLIENT" style="width:100px;" onchange="Recharge_RefECMECLIENT()" onkeypress="if(event.keyCode == 13)AjouterECMECLIENT()">
												<?php
												echo"<option name='0' value='0'></option>";
												$req="SELECT Id, Libelle FROM sp_typeecme WHERE Supprime=false ";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												if ($nbResulta>0){
													$i=0;
													while($rowType=mysqli_fetch_array($result)){
														echo "<option value='".$rowType['Id']."'>".$rowType['Libelle']."</option>";
														echo "<script>Liste_ECMEClient[".$i."] = new Array('".$rowType['Id']."','".str_replace("'"," ",$rowType['Libelle'])."');</script>\n";
														$i+=1;
													}
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;N° client : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>
											<div id="listeECMECLIENT">
												&nbsp; <select id="referenceClient" name="referenceClient" style="width:100px;" onkeypress="if(event.keyCode == 13)AjouterECMECLIENT()">
													<?php
													echo"<option value='0'></option>";
													$req="SELECT Id, Libelle,Id_Type FROM sp_ecmeclient WHERE Supprime=0 ";
													$result=mysqli_query($bdd,$req);
													$nbResulta=mysqli_num_rows($result);
													if ($nbResulta>0){
														$i=0;
														while($rowECME=mysqli_fetch_array($result)){
															echo "<script>Liste_RefECMECLIENT[".$i."] = new Array('".$rowECME['Id']."','".$rowECME['Id_Type']."','".str_replace("'"," ",$rowECME['Libelle'])."');</script>\n";
															$i+=1;
														}
													}
													?>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;OU autre n° client : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:left;" id="numClient" onkeypress="if(event.keyCode == 13)AjouterECMECLIENT()" name="numClient" size="20" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;Date de fin d'étalonnage : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:left;" id="dateEtalonnageECMEClient" onkeypress="if(event.keyCode == 13)AjouterECMECLIENT()" name="dateEtalonnageECMEClient" size="20" type="date" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
											<?php
												if(substr($_SESSION['DroitSP'],1,1)=='1' || substr($_SESSION['DroitSP'],4,1)=='1'){
											?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterECMECLIENT()'>&nbsp;Ajouter&nbsp;</a>
											<?php
												}
											?>
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='65%' valign='top'>
								<table id="tab_ECMECLIENT" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle">Type</td><td class="Libelle">N° Client</td><td class="Libelle">Date de fin d'étalonnage</td></tr>
								</table>
							</td>

						</tr>
						<tr><td height="8" colspan="2" bgcolor="#dbeef9"></td></tr>
					</table><br>
					<table width="100%" id="tableProduit" cellpadding="0" cellspacing="0" align="center">
						<tr>
							<td bgcolor="#dbeef9" width='13%' class="Libelle" valign="top">
								<table width="100%" cellpadding='0' cellspacing='0' style="-moz-box-shadow: 4px 4px 10px #888;-webkit-box-shadow: 4px 4px 10px #888;box-shadow:4px 4px 6px #888;">
									<tr>
										<td bgcolor="#e4e7f" colspan='2'><i>&nbsp; Ajouter les produits :</i></td>
									</tr>
									<tr><td bgcolor="#e4e7f0" height="2" colspan="2"></td></tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; Référence : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<select id="RefProduit" name="RefProduit" onkeypress="if(event.keyCode == 13)AjouterProduit()" style="width:130px;">
												<?php
												echo"<option name='0' value='0'></option>";
												$req="SELECT Id, Libelle FROM sp_ingredient WHERE Supprime=false ORDER BY Libelle;";
												$result=mysqli_query($bdd,$req);
												$nbResulta=mysqli_num_rows($result);
												if ($nbResulta>0){
													$i=0;
													while($rowIngredient=mysqli_fetch_array($result)){
														echo "<option name='".$rowIngredient['Id']."' value='".$rowIngredient['Id']."'>".$rowIngredient['Libelle']."</option>";
														echo "<script>Liste_Produit[".$i."] = new Array('".$rowIngredient['Id']."','".addslashes($rowIngredient['Libelle'])."');</script>\n";
														$i+=1;
													}
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;OU autre référence </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:left;" id="nomProduit" onkeypress="if(event.keyCode == 13)AjouterProduit()" name="nomProduit" size="20" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp;N° lot : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" id="numLot" onkeypress="if(event.keyCode == 13)AjouterProduit()" name="numLot" size="8" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor="#e4e7f0">&nbsp;Date peremption : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" id="datePeremption" onkeypress="if(event.keyCode == 13)AjouterProduit()" name="datePeremption" size="8" type="date" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor="#e4e7f0">&nbsp;Coeff. Hygrométrique : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" onkeypress="if(event.keyCode == 13)AjouterProduit()" id="coeffH" name="coeffH" size="8" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor="#e4e7f0">&nbsp;Température : </td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:center;" onkeypress="if(event.keyCode == 13)AjouterProduit()" id="temperature" name="temperature" size="8" type="text" value="">
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0' align='center' colspan='2' style='height:25px;' valign='center'>
										<?php
											if(substr($_SESSION['DroitSP'],1,1)=='1'){
										?>
											<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterProduit()'>&nbsp;Ajouter&nbsp;</a>
										<?php
											}
										?>	
										</td>
									</tr>
									<tr>
										<td bgcolor='#e4e7f0'>&nbsp; 
											<input style="text-align:left;" <?php echo $modifiableCE; ?> id="PasDeIngredient" name="PasDeIngredient" type="checkbox" value="PasDeIngredient"> Pas d'ingredient requis
										</td>
									</tr>
								</table>
							</td>
							<td bgcolor="#dbeef9" width='20%' valign='top'>
								<table id="tab_Produit" width='100%' cellpadding='0' cellspacing='0'>
									<tr><td class="Libelle">Ingrédient</td><td class="Libelle">N° lot</td><td class="Libelle">Date<br>péremption</td><td class="Libelle">Coeff.<br>hygrométrique</td><td class="Libelle">Température</td></tr>
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
											<td bgcolor="#e4e7f0"><i>&nbsp; Ajouter les procédés spéciaux :</i></td>
										</tr>
										<tr><td bgcolor="#e4e7f0" height="2"></td></tr>
										<tr>
											<td bgcolor='#e4e7f0'>&nbsp;Module : </td>
										</tr>
										<tr>
											<td bgcolor='#e4e7f0'>&nbsp; 
												<select id="module" name="module" onchange="Recharge_PS()" style="width:100px;">
													<?php
													echo"<option value='0'></option>";
													//Liste des modules
													$req="SELECT DISTINCT Module 
														FROM sp_correspondancemodule 
														ORDER BY Module";
													$result=mysqli_query($bdd,$req);
													$nbResulta=mysqli_num_rows($result);
													if ($nbResulta>0){
														while($rowModule=mysqli_fetch_array($result)){
															echo "<option value='".$rowModule['Module']."'>".$rowModule['Module']."</option>";
														}
													}
													
													//Liste des modules
													$req="SELECT DISTINCT Id_Qualification, Module 
														FROM sp_correspondancemodule 
														ORDER BY Module";
													$result=mysqli_query($bdd,$req);
													$nbResulta=mysqli_num_rows($result);
													if ($nbResulta>0){
														$i=0;
														while($rowModule=mysqli_fetch_array($result)){
															echo "<script>Liste_Module[".$i."] = new Array('".$rowModule['Module']."','".addslashes($rowModule['Id_Qualification'])."');</script>\n";
															$i+=1;
														}
													}
													
													?>
												</select>
											</td>
										</tr>
										<tr>
											<td bgcolor='#e4e7f0'>&nbsp;Référence : </td>
										</tr>
										<tr>
											<td bgcolor='#e4e7f0'>
												<div id="listeRefAIPI">
												<select id="RefAIPI" name="RefAIPI" onkeypress="if(event.keyCode == 13)AjouterAIPIS()" style="width:130px;">
													<?php
													echo"<option name='0' value='0'></option>";
													//Liste des specials process
													$req="SELECT DISTINCT new_competences_qualification.Id,new_competences_qualification.Libelle ";
													$req.="FROM new_competences_prestation_qualification ";
													$req.="LEFT JOIN new_competences_qualification ";
													$req.="ON new_competences_prestation_qualification.Id_Qualification = new_competences_qualification.Id ";
													$req.="WHERE new_competences_prestation_qualification.Id_Prestation IN (255,1012,1260) ";
													$req.="AND (SELECT COUNT(new_competences_categorie_qualification.Id) 
																FROM new_competences_categorie_qualification 
																WHERE new_competences_categorie_qualification.Id=
																new_competences_qualification.Id_Categorie_Qualification
																AND new_competences_categorie_qualification.Id_Categorie_Maitre=2)>0 
															ORDER BY new_competences_qualification.Libelle";
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
												</div>
											</td>
										</tr>
										<tr>
											<td bgcolor='#e4e7f0'>&nbsp;OU autre procédé spécial </td>
										</tr>
										<tr>
											<td bgcolor='#e4e7f0'>&nbsp; 
												<input style="text-align:left;" id="nomPS" onkeypress="if(event.keyCode == 13)AjouterAIPIS()" name="nomPS" size="20" type="text" value="">
											</td>
										</tr>
										<tr>
											<td bgcolor='#e4e7f0' align='center' style='height:25px;' valign='center'>
												<a style='text-decoration:none;' class='Bouton' href='javascript:AjouterAIPIS()'>&nbsp;Ajouter&nbsp;</a>
											</td>
										</tr>
										<tr>
											<td bgcolor='#e4e7f0'>&nbsp; 
												<input style="text-align:left;" id="PasDePS" name="PasDePS" type="checkbox" value="PasDePS" onchange="ValidationAutoPS()"> Pas de procédé spécial requis
											</td>
										</tr>
									</table>
								</td>
								<td bgcolor="#dbeef9" width='65%' valign='top'>
									<table id="tab_AIPI" width='100%' cellpadding='0' cellspacing='0'>
										<tr><td class="Libelle" align="center">Procédés spéciaux appelés</td></tr>
										<?php
											$listeAIPI="";
											$req="SELECT Id_Qualification,Qualification, ";
											$req.="IF(Id_Qualification>0,(SELECT new_competences_qualification.Libelle ";
											$req.="FROM new_competences_qualification WHERE new_competences_qualification.Id=sp_fi_aipi.Id_Qualification),Qualification) AS Libelle ";
											$req.="FROM sp_fi_aipi WHERE Id_FI=".$FI." ORDER BY Libelle;";
											
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											if ($nbResulta>0){
												while($rowAIPI=mysqli_fetch_array($result)){
													$btn="";
													if(substr($_SESSION['DroitSP'],1,1)=='1'){
														$btn="<a style=\"text-decoration:none;\" href=\"javascript:SupprimerAIPIS('".$rowAIPI['Id_Qualification']."PS_PS".str_replace("'"," ",stripslashes($rowAIPI['Qualification']))."')\">&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
													}
													echo "<tr id='".$rowAIPI['Id_Qualification']."PS_PS".stripslashes($rowAIPI['Qualification'])."'>";
													echo "<td>".$rowAIPI['Libelle']."</td><td>".$btn."</td></tr>";
													$listeAIPI.=$rowAIPI['Id_Qualification']."PS_PS".str_replace("'"," ",stripslashes($rowAIPI['Qualification'])).";";
												}
											}
										?>
									</table>
								</td>
							</tr>
							<tr>
								<td bgcolor='#dbeef9'>&nbsp; 
									
								</td>
								<td bgcolor='#dbeef9'>&nbsp; 
									 <table>
										<tr>
											<td>
												<input <?php echo $modifiableCE; ?> style="text-align:left;" id="ValidationPSCE" name="ValidationPSCE" type="checkbox" value="ValidationPSCE" > Validation Chef d'équipe
											</td>
											<td>
												<input <?php echo $modificationIQ; ?> style="text-align:left;" id="ValidationPSIQ" name="ValidationPSIQ" type="checkbox" value="ValidationPSIQ" > Validation Qualité
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td height="8" colspan="2" bgcolor="#dbeef9"></td></tr>
						</table>
				</td>
			</tr>
			<tr style="display:none;"><td><input id="travailEffectue" name="travailEffectue" value=""  readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="ECMEPROD" name="ECMEPROD" value="" readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="ECMEQUALITE" name="ECMEQUALITE" value="" readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id="ECMECLIENT" name="ECMECLIENT" value="" readonly="readonly"></td></tr>
			<tr style="display:none;"><td><input id='lesAIPI' name='lesAIPI' value="<?php echo $listeAIPI;?>" readonly='readonly'></td></tr>
			<tr style="display:none;"><td><input id="Produit" name="Produit" value="" readonly="readonly"></td></tr>
			<tr style="display:none;"><td height="4" colspan="6" bgcolor="#dbeef9"></td></tr>
			<tr id="ligneSAP1">
				<td colspan="6" style="font-size:15px;font-weight:bold;color:#4d4a82;" align="center">INFORMATIONS POUR SAP</td>
			</tr>
			<tr id="ligneSAP2">
				<td colspan="6" align="left">
				<input type="radio" id='ICaCreer' name='ICaCreer' onclick="javascript:AfficheIC()" value="0" checked>IC à créer dans SAP &nbsp;&nbsp;
				<input type="radio" id='ICExistante' name='ICaCreer' onclick="javascript:MasqueIC()" value="1">IC existante &nbsp;&nbsp;
				</td>
			</tr>
			<tr id="ligneSAP3">
				<td class="infoIC4" style="display:none;" class="Libelle" valign="top">&nbsp; N° IC : <?php echo $etoileSTCE;?></td>
				<td class="infoIC4" style="display:none;"><input id="numIC" name="numIC" size="15" value=""></td>
			</tr>
			<tr class="infoIC3" id="ligneSAP4"><td style="border-top:1px #0077AA dotted;" colspan="6" align="right"><a style="text-decoration:none;" href="javascript:AfficherInfos()"><img id="imageAffichage" src="../../../Images/Plus.gif" border="0" alt="+" title="+"></a>&nbsp;&nbsp;</td></tr>
			<tr style="display:none;"><td><input id='affichage' name='affichage' value='plus'  readonly='readonly'></td></tr>
			<tr id="ligneSAP5">
				<td colspan="6" width="33%" valign="top">
					<table width="100%" cellpadding="0" cellspacing="0" align="center">
						<tr class="infoIC">
							<td width="13%" class="Libelle" valign="top">&nbsp; Type [IC] : <?php echo $etoileSTCE;?></td>
							<td width="20%" valign="top">
								<select id="typeIC" name="typeIC" <?php echo $disabledSTCE;?>>
									<option name="" value=""></option>
									<option name="-E" value="-E" <?php if($row['TypeCIA']=="-E"){echo "selected";} ?>>-E</option>
									<option name="-M" value="-M" <?php if($row['TypeCIA']=="-M"){echo "selected";} ?>>-M</option>
									<option name="-S" value="-S" <?php if($row['TypeCIA']=="-S"){echo "selected";} ?>>-S</option>
								</select>
							</td>
							<td width="13%" class="Libelle" valign="top">&nbsp; Raison intervention [IC] : <?php echo $etoileSTCE;?></td>
							<td width="20%">
								<textarea id="raisonIntervention" name="raisonIntervention" rows="5" cols="30" style="resize:none;" <?php echo $readSTCE;?>><?php echo stripslashes($row['RaisonInterventionCIA']);?></textarea>
							</td>
							<td width="13%" class="Libelle" valign="top">&nbsp; Impact ESN [IC] : <?php echo $etoileSTCE;?></td>
							<td width="20%" valign="top">
								<select id="esn" name="esn" <?php echo $disabledSTCE;?>>
									<option name="0" value="0">Non</option>
									<option name="1" value="1" <?php if($row['ESN']=="1"){echo "selected";} ?>>Oui</option>
								</select>
							</td>
						</tr>
						<tr class="infoIC"><td height="4" colspan="6"></td></tr>
						<tr class="infoIC">
							<td width="13%" class="Libelle">&nbsp; Activité [IC] : <?php echo $etoileSTCE;?></td>
							<td width="20%">
								<select id="activite" name="activite" style="width:230px;" onchange="Recharge_SousActivite();" <?php echo $disabledSTCE;?>>
									<option name="0" value="0"></option>
									<?php
										$req="SELECT Id, Libelle FROM sp_activite ORDER BY Libelle;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											$i=0;
											while($rowActivite=mysqli_fetch_array($result)){
												$selected = "";
												if($row['Id_ActiviteCIA']==$rowActivite['Id']){$selected="selected";}
												echo "<option name='".$rowActivite['Id']."' value='".$rowActivite['Id']."' ".$selected.">".$rowActivite['Libelle']."</option>";
											}
										}
									?>
								</select>
							</td>
							<td width="13%" class="Libelle">&nbsp; Type de travail [IC] : <?php echo $etoileSTCE;?></td>
							<td width="20%">
								<div id="typetravails">
									<select id="typeTravail" name="typeTravail" style="width:200px;" <?php echo $disabledSTCE;?>>
										<option name="0" value="0"></option>
										<?php
											$req="SELECT Id, Libelle, Id_Activite FROM sp_typetravail ORDER BY Libelle;";
											$result=mysqli_query($bdd,$req);
											$nbResulta=mysqli_num_rows($result);
											if ($nbResulta>0){
												$i=0;
												while($rowTT=mysqli_fetch_array($result)){
													echo "<script>Liste_TT[".$i."] = new Array('".$rowTT['Id']."','".addslashes($rowTT['Libelle'])."','".$rowTT['Id_Activite']."');</script>\n";
													if($row['Id_ActiviteCIA']==$rowTT['Id_Activite']){
														$selected = "";
														if($row['Id_TypeTravailCIA']==$rowTT['Id']){$selected="selected";}
														echo "<option name='".$rowTT['Id']."' value='".$rowTT['Id']."' ".$selected.">".$rowTT['Libelle']."</option>";
													}
													$i+=1;
												}
											}
										?>
									</select>
								</div>
							</td>
							<td width="13%" class="Libelle">&nbsp; Références à installer [IC] : <?php echo $etoileSTCE;?></td>
							<td width="20%">
								<input id="refAInstaller" name="refAInstaller" size="30" value="<?php echo $row['RefAInstallerCIA'];?>" <?php echo $readSTCE;?>>
							</td>
						</tr>
						<tr class="infoIC"><td height="4" colspan="6"></td></tr>
						<tr class="infoIC" id="infoElec">
							<td width="13%" class="Libelle">&nbsp; Impact élément terminaison<br>&nbsp; prise (only elec) [IC] : <?php echo $etoileSTCE;?></td>
							<td width="20%">
								<div id="impacts">
								<select id="impactElement" name="impactElement" style="width:230px;" <?php echo $disabledSTCE;?>>
									<option name="0" value="0"></option>
									<?php
										$req="SELECT Id, Libelle, Id_Activite FROM sp_impact ORDER BY Libelle;";
										$result=mysqli_query($bdd,$req);
										$nbResulta=mysqli_num_rows($result);
										if ($nbResulta>0){
											$i=0;
											while($rowImpact=mysqli_fetch_array($result)){
												echo "<script>Liste_Impact[".$i."] = new Array('".$rowImpact['Id']."','".addslashes($rowImpact['Libelle'])."','".$rowImpact['Id_Activite']."');</script>\n";
												if($row['Id_ActiviteCIA']==$rowImpact['Id_Activite']){
													$selected = "";
													if($row['Id_ImpactElementCIA']==$rowImpact['Id']){$selected="selected";}
													echo "<option name='".$rowImpact['Id']."' value='".$rowImpact['Id']."' ".$selected.">".$rowImpact['Libelle']."</option>";
												}
												$i+=1;
											}
										}
									?>
								</select>
								</div>
							</td>
							<td width="13%" class="Libelle">&nbsp; Références des câbles [IC] : <?php echo $etoileSTCE;?></td>
							<td width="20%">
								<input id="refCables" name="refCables" value="<?php echo $row['RefCableCIA'];?>" size="30" <?php echo $readSTCE;?>>
							</td>
						</tr>
						<tr class="infoIC"><td height="4" colspan="6"></td></tr>
						<tr>
							<td width="13%" class="Libelle" valign="top">&nbsp; Power Off Partiel [IC] : <?php echo $etoileSTCE;?></td>
							<td width="20%" valign="top" id="IdPowerOff">
								<select id="poweroff" name="poweroff" <?php echo $disabledSTCE;?>>
									<option name="0" value="0" <?php if($row['PowerOffPartielCIA']=="0"){echo "selected";} ?>>Non</option>
									<option name="1" value="1" <?php if($row['PowerOffPartielCIA']=="1"){echo "selected";} ?>>Oui</option>
								</select>
							</td>
							<td width="13%" class="Libelle infoIC" valign="top">&nbsp; Commentaire<br>&nbsp; Power Off Partiel [IC] : </td>
							<td width="20%" class="infoIC">
								<textarea id="commentairePO" name="commentairePO" rows="5" cols="30" style="resize:none;" <?php echo $readSTCE;?>><?php echo stripslashes($row['CommentairePOCIA']);?></textarea>
							</td>
							<td width="13%" class="Libelle infoIC" valign="top">&nbsp; Infos additionnelles [IC] : </td>
							<td width="20%" valign="top" class="infoIC">
								<textarea id="infoAddionnelle" name="infoAddionnelle" rows="5" cols="30" style="resize:none;" <?php echo $readSTCE;?>><?php echo stripslashes($row['InfoSuppPOCIA']);?></textarea>
							</td>
						</tr>
						<tr class="infoIC"><td height="4" colspan="4"></td></tr>
						<tr class="infoIC">
						<td width="13%" class="Libelle" valign="top">&nbsp; Moyens industriels [IC] : </td>
						<td width="20%" valign="top">
							<table>
								<tr>
								<td><input type="checkbox" id='EclairageNeon' name='EclairageNeon' value='Eclairages néons' <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id, Libelle FROM sp_fi_moyenindustriel WHERE Id_FI=".$FI." AND Libelle='Eclairages néons';"))>0){echo "checked";}?>>Eclairages néons &nbsp;&nbsp;</td>
								<td><input type="checkbox" id='Echelle' name='Echelle' value='Echelle' <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id, Libelle FROM sp_fi_moyenindustriel WHERE Id_FI=".$FI." AND Libelle='Echelle';"))>0){echo "checked";}?>>Echelle &nbsp;&nbsp;</td>
								</tr>
								<tr>
								<td><input type="checkbox" id='PlateformeExterieur' name='PlateformeExterieur' <?php echo $disabledSTCE;?> value='Plateforme extérieur' <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id, Libelle FROM sp_fi_moyenindustriel WHERE Id_FI=".$FI." AND Libelle='Plateforme extérieur';"))>0){echo "checked";}?>>Plateforme extérieur &nbsp;&nbsp;</td>
								<td><input type="checkbox" id='PlateformeMobile' name='PlateformeMobile' <?php echo $disabledSTCE;?> value='Plateforme mobile' <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id, Libelle FROM sp_fi_moyenindustriel WHERE Id_FI=".$FI." AND Libelle='Plateforme mobile';"))>0){echo "checked";}?>>Plateforme mobile &nbsp;&nbsp;</td>
								<tr>
								<td><input type="checkbox" id='ScotchSecurite' name='ScotchSecurite' <?php echo $disabledSTCE;?> value='Scotch de sécurité' <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id, Libelle FROM sp_fi_moyenindustriel WHERE Id_FI=".$FI." AND Libelle='Scotch de sécurité';"))>0){echo "checked";}?>>Scotch de sécurité &nbsp;&nbsp;</td>
								<td><input type="checkbox" id='MaterielSignaletique' name='MaterielSignaletique' <?php echo $disabledSTCE;?> value='Matériel de signalétique' <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id, Libelle FROM sp_fi_moyenindustriel WHERE Id_FI=".$FI." AND Libelle='Matériel de signalétique';"))>0){echo "checked";}?>>Matériel de signalétique &nbsp;&nbsp;</td>
								</tr>
								<tr>
								<td><input type="checkbox" id='Autres' name='Autres' value='Autres' <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id, Libelle FROM sp_fi_moyenindustriel WHERE Id_FI=".$FI." AND Libelle='Autres';"))>0){echo "checked";}?>>Autres &nbsp;&nbsp;</td>
								</tr>
							</table>
						</td>
						<td width="13%" class="Libelle" valign="top">&nbsp; Commentaire <br>&nbsp; moyens industriel [IC] : </td>
						<td width="20%" valign="top">
							<textarea id="commentaireMI" name="commentaireMI" rows="5" cols="30" style="resize:none;" <?php echo $readSTCE;?>><?php echo $row['CommentaireMICIA'];?></textarea>
						</td>
						<td width="13%" class="Libelle" valign="top">&nbsp; Description du<br>&nbsp; type de travail [IC] : <img src="../../../Images/etoile.png" width="8" height="8" border="0"></td>
							<td width="20%">
								<textarea id="descriptionTT" name="descriptionTT" rows="5" cols="30" style="resize:none;" <?php echo $readSTCE;?>><?php echo $row['DescriptionTypeTravailCIA'];?></textarea>
							</td>
						</tr>
						<tr class="infoIC"><td height="4" colspan="4"></td></tr>
						<tr  class="infoIC">
						<td width="13%" class="Libelle" valign="top">&nbsp; Mesures de sécurité [IC] : </td>
						<td colspan="5">
							<table>
								<tr>
									<td>Pas de mesure de sécurité </td>
									<td><input type="checkbox" id='pasDeMesure' name='pasDeMesure' value="pasDeMesure" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_ficheintervention WHERE Id=".$FI." AND PasDeMesure=1;"))>0){echo "checked";}?>></td>
								</tr>
								<tr>
									<td>Alimentation électrique</td>
									<td><input type="radio" id='alimElec' name='alimElec' value="OP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Alimentation électrique' AND Type='OP';"))>0){echo "checked";}?>>OP &nbsp;&nbsp;</td>
									<td><input type="radio" id='alimElec' name='alimElec' value="INOP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Alimentation électrique' AND Type='INOP';"))>0){echo "checked";}?>>INOP &nbsp;&nbsp;</td>
									<td><input type="radio" id='alimElec' name='alimElec' value="PasImpact" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Alimentation électrique' AND Type='PasImpact';"))>0){echo "checked";}?>>Pas d'impact sur le travail à effectuer &nbsp;&nbsp;</td>
									<td><input type="radio" id='alimElec' name='alimElec' value="ADefinir" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Alimentation électrique' AND Type='ADefinir';"))>0){echo "checked";}?>>A définir par le chef de poste &nbsp;&nbsp;</td>
								</tr>
								<tr>
									<td>Système hydraulique vert</td>
									<td><input type="radio" id='systemeHVert' name='systemeHVert' value="OP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Système hydraulique vert' AND Type='OP';"))>0){echo "checked";}?>>OP &nbsp;&nbsp;</td>
									<td><input type="radio" id='systemeHVert' name='systemeHVert' value="INOP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Système hydraulique vert' AND Type='INOP';"))>0){echo "checked";}?>>INOP &nbsp;&nbsp;</td>
									<td><input type="radio" id='systemeHVert' name='systemeHVert' value="PasImpact" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Système hydraulique vert' AND Type='PasImpact';"))>0){echo "checked";}?>>Pas d'impact sur le travail à effectuer &nbsp;&nbsp;</td>
									<td><input type="radio" id='systemeHVert' name='systemeHVert' value="ADefinir" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Système hydraulique vert' AND Type='ADefinir';"))>0){echo "checked";}?>>A définir par le chef de poste &nbsp;&nbsp;</td>
								</tr>
								<tr>
									<td>Système hydraulique jaune</td>
									<td><input type="radio" id='systemeHJaune' name='systemeHJaune' value="OP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Système hydraulique jaune' AND Type='OP';"))>0){echo "checked";}?>>OP &nbsp;&nbsp;</td>
									<td><input type="radio" id='systemeHJaune' name='systemeHJaune' value="INOP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Système hydraulique jaune' AND Type='INOP';"))>0){echo "checked";}?>>INOP &nbsp;&nbsp;</td>
									<td><input type="radio" id='systemeHJaune' name='systemeHJaune' value="PasImpact" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Système hydraulique jaune' AND Type='PasImpact';"))>0){echo "checked";}?>>Pas d'impact sur le travail à effectuer &nbsp;&nbsp;</td>
									<td><input type="radio" id='systemeHJaune' name='systemeHJaune' value="ADefinir" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Système hydraulique jaune' AND Type='ADefinir';"))>0){echo "checked";}?>>A définir par le chef de poste &nbsp;&nbsp;</td>
								</tr>
								<tr>
									<td>Système hydraulique bleu</td>
									<td><input type="radio" id='systemeHBleu' name='systemeHBleu' value="OP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Système hydraulique bleu' AND Type='OP';"))>0){echo "checked";}?>>OP &nbsp;&nbsp;</td>
									<td><input type="radio" id='systemeHBleu' name='systemeHBleu' value="INOP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Système hydraulique bleu' AND Type='INOP';"))>0){echo "checked";}?>>INOP &nbsp;&nbsp;</td>
									<td><input type="radio" id='systemeHBleu' name='systemeHBleu' value="PasImpact" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Système hydraulique bleu' AND Type='PasImpact';"))>0){echo "checked";}?>>Pas d'impact sur le travail à effectuer &nbsp;&nbsp;</td>
									<td><input type="radio" id='systemeHBleu' name='systemeHBleu' value="ADefinir" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Système hydraulique bleu' AND Type='ADefinir';"))>0){echo "checked";}?>>A définir par le chef de poste &nbsp;&nbsp;</td>
								</tr>
								<tr>
									<td>Commandes de vol</td>
									<td><input type="radio" id='commandeVol' name='commandeVol' value="OP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Commandes de vol' AND Type='OP';"))>0){echo "checked";}?>>OP &nbsp;&nbsp;</td>
									<td><input type="radio" id='commandeVol' name='commandeVol' value="INOP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Commandes de vol' AND Type='INOP';"))>0){echo "checked";}?>>INOP &nbsp;&nbsp;</td>
									<td><input type="radio" id='commandeVol' name='commandeVol' value="PasImpact" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Commandes de vol' AND Type='PasImpact';"))>0){echo "checked";}?>>Pas d'impact sur le travail à effectuer &nbsp;&nbsp;</td>
									<td><input type="radio" id='commandeVol' name='commandeVol' value="ADefinir" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Commandes de vol' AND Type='ADefinir';"))>0){echo "checked";}?>>A définir par le chef de poste &nbsp;&nbsp;</td>
								</tr>
								<tr>
									<td>Trains d'atterissage</td>
									<td><input type="radio" id='trainAtterissage' name='trainAtterissage' value="OP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Trains d\'atterissage' AND Type='OP';"))>0){echo "checked";}?>>OP &nbsp;&nbsp;</td>
									<td><input type="radio" id='trainAtterissage' name='trainAtterissage' value="INOP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Trains d\'atterissage' AND Type='INOP';"))>0){echo "checked";}?>>INOP &nbsp;&nbsp;</td>
									<td><input type="radio" id='trainAtterissage' name='trainAtterissage' value="PasImpact" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Trains d\'atterissage' AND Type='PasImpact';"))>0){echo "checked";}?>>Pas d'impact sur le travail à effectuer &nbsp;&nbsp;</td>
									<td><input type="radio" id='trainAtterissage' name='trainAtterissage' value="ADefinir" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Trains d\'atterissage' AND Type='ADefinir';"))>0){echo "checked";}?>>A définir par le chef de poste &nbsp;&nbsp;</td>
								</tr>
								<tr>
									<td>Condition zéro stress</td>
									<td><input type="radio" id='zeroStress' name='zeroStress' value="OP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Condition zéro stress' AND Type='OP';"))>0){echo "checked";}?>>OP &nbsp;&nbsp;</td>
									<td><input type="radio" id='zeroStress' name='zeroStress' value="INOP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Condition zéro stress' AND Type='INOP';"))>0){echo "checked";}?>>INOP &nbsp;&nbsp;</td>
									<td><input type="radio" id='zeroStress' name='zeroStress' value="PasImpact" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Condition zéro stress' AND Type='PasImpact';"))>0){echo "checked";}?>>Pas d'impact sur le travail à effectuer &nbsp;&nbsp;</td>
									<td><input type="radio" id='zeroStress' name='zeroStress' value="ADefinir" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Condition zéro stress' AND Type='ADefinir';"))>0){echo "checked";}?>>A définir par le chef de poste &nbsp;&nbsp;</td>
								</tr>
								<tr>
									<td>Pneumatique   
										<input type="radio" id='moteurPneu' name='moteurPneu' value="Moteur" <?php echo $disabledSTCE;?> <?php if($row['Pneumatique']<>'N/A' AND $row['Pneumatique']<>'APU'){echo "checked";}?>>Moteur n° &nbsp;&nbsp;
										<input type="texte" id='numMoteur' name='numMoteur' value="<?php if($row['Pneumatique']<>'N/A' AND $row['Pneumatique']<>'APU'){echo $row['Pneumatique'];}?>" style="width:30px;" />
										<input type="radio" id='moteurPneu' name='moteurPneu' value="APU" <?php echo $disabledSTCE;?> <?php if($row['Pneumatique']=='APU'){echo "checked";}?>>APU &nbsp;&nbsp;
										<input type="radio" id='moteurPneu' name='moteurPneu' value="N/A" <?php echo $disabledSTCE;?> <?php if($row['Pneumatique']=='N/A'){echo "checked";}?>>N/A &nbsp;&nbsp;
									</td>
									<td><input type="radio" id='pneumatique' name='pneumatique' value="OP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Pneumatique' AND Type='OP';"))>0){echo "checked";}?>>OP &nbsp;&nbsp;</td>
									<td><input type="radio" id='pneumatique' name='pneumatique' value="INOP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Pneumatique' AND Type='INOP';"))>0){echo "checked";}?>>INOP &nbsp;&nbsp;</td>
									<td><input type="radio" id='pneumatique' name='pneumatique' value="PasImpact" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Pneumatique' AND Type='PasImpact';"))>0){echo "checked";}?>>Pas d'impact sur le travail à effectuer &nbsp;&nbsp;</td>
									<td><input type="radio" id='pneumatique' name='pneumatique' value="ADefinir" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Pneumatique' AND Type='ADefinir';"))>0){echo "checked";}?>>A définir par le chef de poste &nbsp;&nbsp;</td>
								</tr>
								<tr>
									<td>Kérozène</td>
									<td><input type="radio" id='kerozene' name='kerozene' value="OP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Kérozène' AND Type='OP';"))>0){echo "checked";}?>>OP &nbsp;&nbsp;</td>
									<td><input type="radio" id='kerozene' name='kerozene' value="INOP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Kérozène' AND Type='INOP';"))>0){echo "checked";}?>>INOP &nbsp;&nbsp;</td>
									<td><input type="radio" id='kerozene' name='kerozene' value="PasImpact" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Kérozène' AND Type='PasImpact';"))>0){echo "checked";}?>>Pas d'impact sur le travail à effectuer &nbsp;&nbsp;</td>
									<td><input type="radio" id='kerozene' name='kerozene' value="ADefinir" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Kérozène' AND Type='ADefinir';"))>0){echo "checked";}?>>A définir par le chef de poste &nbsp;&nbsp;</td>
								</tr>
								<tr>
									<td>Système de refroidissement supplémentaire</td>
									<td><input type="radio" id='systemeRefroidissementSupp' name='systemeRefroidissementSupp' value="OP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Système de refroidissement supplémentaire' AND Type='OP';"))>0){echo "checked";}?>>OP &nbsp;&nbsp;</td>
									<td><input type="radio" id='systemeRefroidissementSupp' name='systemeRefroidissementSupp' value="INOP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Système de refroidissement supplémentaire' AND Type='INOP';"))>0){echo "checked";}?>>INOP &nbsp;&nbsp;</td>
									<td><input type="radio" id='systemeRefroidissementSupp' name='systemeRefroidissementSupp' value="PasImpact" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Système de refroidissement supplémentaire' AND Type='PasImpact';"))>0){echo "checked";}?>>Pas d'impact sur le travail à effectuer &nbsp;&nbsp;</td>
									<td><input type="radio" id='systemeRefroidissementSupp' name='systemeRefroidissementSupp' value="ADefinir" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Système de refroidissement supplémentaire' AND Type='ADefinir';"))>0){echo "checked";}?>>A définir par le chef de poste &nbsp;&nbsp;</td>
								</tr>
								<tr>
									<td>Eau potable</td>
									<td><input type="radio" id='eauPotable' name='eauPotable' value="OP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Eau potable' AND Type='OP';"))>0){echo "checked";}?>>OP &nbsp;&nbsp;</td>
									<td><input type="radio" id='eauPotable' name='eauPotable' value="INOP" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Eau potable' AND Type='INOP';"))>0){echo "checked";}?>>INOP &nbsp;&nbsp;</td>
									<td><input type="radio" id='eauPotable' name='eauPotable' value="PasImpact" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Eau potable' AND Type='PasImpact';"))>0){echo "checked";}?>>Pas d'impact sur le travail à effectuer &nbsp;&nbsp;</td>
									<td><input type="radio" id='eauPotable' name='eauPotable' value="ADefinir" <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_mesuresecurite WHERE Id_FI=".$FI." AND MesureSecurite='Eau potable' AND Type='ADefinir';"))>0){echo "checked";}?>>A définir par le chef de poste &nbsp;&nbsp;</td>
								</tr>
							</table>
						</td>
						</tr>
						<tr class="infoIC"><td height="4" colspan="4"></td></tr>
						<tr class="infoIC">
							<td width="13%" class="Libelle" valign="top">&nbsp; Risque lié à l'intervention [IC] : </td>
							<td width="40%" valign="top" colspan="3">
								<table>
									<tr>
									<td><input type="checkbox" id='TravailHauteur' name='TravailHauteur' value='Travail en hauteur' <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_risque WHERE Id_FI=".$FI." AND Libelle='Travail en hauteur';"))>0){echo "checked";}?>>Travail en hauteur &nbsp;&nbsp;</td>
									<td><input type="checkbox" id='ConduiteEngins' name='ConduiteEngins' value="Conduite d'engins" <?php echo $disabledSTCE;?>  <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_risque WHERE Id_FI=".$FI." AND Libelle='Conduite d\'engins';"))>0){echo "checked";}?>>Conduite d'engins &nbsp;&nbsp;</td>
									<td><input type="checkbox" id='TravailZoneConfinee' name='TravailZoneConfinee' value='Travail en zone confinée' <?php echo $disabledSTCE;?> <?php if(mysqli_num_rows(mysqli_query($bdd,"SELECT Id FROM sp_fi_risque WHERE Id_FI=".$FI." AND Libelle='Travail en zone confinée';"))>0){echo "checked";}?>>Travail en zone confinée &nbsp;&nbsp;</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr><td height="4" colspan="4"></td></tr>
					</table>
					</td>
				</tr>
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
			<?php
			echo "<script>AfficherInfos()</script>";
		?>
	<tr><td colspan="6">&nbsp;&nbsp;&nbsp;&nbsp;<img src='../../../Images/etoile.png' width='8' height='8' border='0'> Informations obligatoires à remplir</td></tr>
	<tr><td height="4"></td></tr>
</table>
</form>
<?php
	if($row['SaisieQualite']==1){
		echo "<script>DupliqueQUALITE()</script>";
	}
//	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
}
?>
	
</body>
</html>
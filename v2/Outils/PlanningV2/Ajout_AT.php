<?php
require("../../Menu.php");
?>
<script language="javascript" src="AccidentTravail.js?time=<?php echo time();?>"></script>
<?php
$DirFichier2="AT/";

$DateJour=date("Y-m-d");
$bEnregistrement=false;
if($_POST){
	if(isset($_POST['btnEnregistrer2'])){
		$fichierPassage="";
		//****TRANSFERT FICHIER****
		if($_FILES['fichier']['name']!="")
		{
			$tmp_file=$_FILES['fichier']['tmp_name'];
			if(is_uploaded_file($tmp_file)){
				//On vérifie la taille du fichiher
				if(filesize($_FILES['fichier']['tmp_name'])<=$_POST['MAX_FILE_SIZE'])
				{
					// on copie le fichier dans le dossier de destination
					$name_file=$_FILES['fichier']['name'];
					$name_file=strtr($name_file, "@àäâöôéèëêîïùüñç &()[]+*'\\°", "aaaaooeeeeiiuunc___________");
					while(file_exists($DirFichier2.$name_file)){$name_file="_".date('j-m-y')."_".date('H-i-s')." ".$name_file;}
					if(move_uploaded_file($tmp_file,$DirFichier2.$name_file))
					{$fichierPassage=$name_file;}
				}
			}
		}
		
		$tabPresta=explode("_",$_POST['Id_PrestationPole']);
		$Id_Prestation=$tabPresta[0];
		$Id_Pole=$tabPresta[1];
		
		$IdTypeVehicule=0;
		if(isset($_POST['typevehicule'])){
			$IdTypeVehicule=$_POST['typevehicule'];
		}
		$ConditionClim=0;
		if(isset($_POST['conditionsClim'])){$ConditionClim=1;}
		$MauvaisEtatInfra=0;
		if(isset($_POST['mauvaisEtatInfra'])){$MauvaisEtatInfra=1;}
		$TrajetAller=0;
		if(isset($_POST['trajet'])){
			$TrajetAller=$_POST['trajet'];
		}
		$HoraireTravail=0;
		if(isset($_POST['horairesSpec'])){$HoraireTravail=1;}
		$ProblemeTechnique=0;
		if(isset($_POST['pbTechnique'])){$ProblemeTechnique=1;}
		//Création d'un accident de travail
		$req="INSERT INTO rh_personne_at (Id_Personne,Id_Createur,Id_Prestation,Id_Pole,Id_Metier,Id_Lieu_AT,FichierPassageInfirmerie,
				DateCreation,DateAT,HeureAT,Id_TypeContrat,Adresse,CP,Ville,NumSecurite,DateNaissance,Anciennete,HeureDebutAM,HeureFinAM,
				HeureDebutPM,HeureFinPM,LieuAccident,SIRETClient,Activite,CommentaireNature,ArretDeTravail,EvacuationVers,AutreVictime,
				TiersResponsable,Temoin,CoordonneesTemoins,1erePersonneAvertie,DateConnaissanceAT,HeureConnaissanceAT,DoutesCirconstances,AutresInformations,
				Id_TypeVehicule,ConditionClim,MauvaisEtatInfra,TrajetAller,HoraireTravail,ProblemeTechnique,CommentaireCirconstance,CommentaireCirconstance2,
				DateRemplissage,DatePriseEnCompteRH,Id_RH) 
			VALUES 
				(".$_POST['Id_Personne'].",".$_SESSION['Id_Personne'].",".$Id_Prestation.",".$Id_Pole.",".$_POST['metier'].",
				".$_POST['lieus'].",'".$fichierPassage."','".$DateJour."','".TrsfDate_($_POST['dateAT'])."','".$_POST['heureAT']."',".$_POST['Id_Contrat'].",
				'".addslashes($_POST['adresse'])."','".addslashes($_POST['cp'])."','".addslashes($_POST['ville'])."','".addslashes($_POST['numSecu'])."',
				'".TrsfDate_($_POST['dateNaissance'])."','".TrsfDate_($_POST['anciennete'])."','".$_POST['heureDebut1']."','".$_POST['heureFin1']."',
				'".$_POST['heureDebut2']."','".$_POST['heureFin2']."','".addslashes($_POST['lieu'])."','".addslashes($_POST['siretClient'])."',
				'".addslashes($_POST['activiteVictime'])."','".addslashes($_POST['natureAccident'])."',".$_POST['arretTravail'].",
				'".addslashes($_POST['evacuationVers'])."','".addslashes($_POST['autreVictime'])."','".addslashes($_POST['tiersResponsable'])."',
				'".addslashes($_POST['temoin'])."','".addslashes($_POST['coordonnees'])."','".addslashes($_POST['personne1'])."',
				'".TrsfDate_($_POST['dateConnaissanceAT'])."','".$_POST['heureConnaisanceAT']."','".addslashes($_POST['doutes'])."',
				'".addslashes($_POST['autresInfos'])."',".$IdTypeVehicule.",
				".$ConditionClim.",".$MauvaisEtatInfra.",".$TrajetAller.",".$HoraireTravail.",".$ProblemeTechnique.",'".addslashes($_POST['commentaireCondition1'])."',
				'".addslashes($_POST['commentaireCondition2'])."',
				'".$DateJour."'";

		if($_POST['Menu']==4){
			$req.=",'".$DateJour."',".$_SESSION['Id_Personne'].")";
		}
		else{
			$req.=",'0001-01-01',0)";
		}
		$resultAjout=mysqli_query($bdd,$req);
		$IdCree = mysqli_insert_id($bdd);

		if($IdCree>0){
			//Ajout des sièges lesions 
			$req="SELECT Id, Libelle, CoteGD FROM rh_siege_lesion_at WHERE Suppr=0";
			$result=mysqli_query($bdd,$req);
			$nbResulta=mysqli_num_rows($result);
			if ($nbResulta>0){
				while($row=mysqli_fetch_array($result)){
					if($row['Libelle']=="Autres" || $row['Libelle']=="Autre" || $row['Libelle']=="Others" || $row['Libelle']=="Other" || 
					$row['Libelle']=="°Autres" || $row['Libelle']=="°Autre" || $row['Libelle']=="°Others" || $row['Libelle']=="°Other"){
						if(isset($_POST['siegeLesionAutre_'.$row['Id']])){
							if($_POST['siegeLesionAutre_'.$row['Id']]<>""){
								$req="INSERT INTO rh_personne_at_siegelesion (Id_Personne_AT,Id_SiegeLesion,AutreSiege,Gauche,Droite)
								VALUES (".$IdCree.",".$row['Id'].",'".stripslashes($_POST['siegeLesionAutre_'.$row['Id']])."',0,0) ";
								$resultAdd=mysqli_query($bdd,$req);
							}
						}
					}
					else{
						if($row['CoteGD']==0){
							if(isset($_POST['siegeLesion_'.$row['Id']])){
								$req="INSERT INTO rh_personne_at_siegelesion (Id_Personne_AT,Id_SiegeLesion,AutreSiege,Gauche,Droite)
								VALUES (".$IdCree.",".$row['Id'].",'',0,0) ";
								$resultAdd=mysqli_query($bdd,$req);
							}
						}
						else{
							$gauche=0;
							$droite=0;
							if(isset($_POST['siegeLesionG_'.$row['Id']])){$gauche=1;}
							if(isset($_POST['siegeLesionD_'.$row['Id']])){$droite=1;}
							
							if($gauche>0 || $droite>0){
								$req="INSERT INTO rh_personne_at_siegelesion (Id_Personne_AT,Id_SiegeLesion,AutreSiege,Gauche,Droite)
								VALUES (".$IdCree.",".$row['Id'].",'',".$gauche.",".$droite.") ";
								$resultAdd=mysqli_query($bdd,$req);
							}
						}
					}
				}
			}
			
			//Ajout des natures des lésions
			$req="SELECT Id, Libelle FROM rh_nature_lesion WHERE Suppr=0";
			$result=mysqli_query($bdd,$req);
			$nbResulta=mysqli_num_rows($result);
			if ($nbResulta>0){
				while($row=mysqli_fetch_array($result)){
					if($row['Libelle']=="Autres" || $row['Libelle']=="Autre" || $row['Libelle']=="Others" || $row['Libelle']=="Other"|| 
					$row['Libelle']=="°Autres" || $row['Libelle']=="°Autre" || $row['Libelle']=="°Others" || $row['Libelle']=="°Other"){
						if(isset($_POST['natureLesionAutre_'.$row['Id']])){
							if($_POST['natureLesionAutre_'.$row['Id']]<>""){
								$req="INSERT INTO rh_personne_at_nature_lesion(Id_PersonneAT,Id_NatureLesion,AutreNature)
								VALUES (".$IdCree.",".$row['Id'].",'".stripslashes($_POST['natureLesionAutre_'.$row['Id']])."') ";
								$resultAdd=mysqli_query($bdd,$req);
							}
						}
					}
					else{
						if(isset($_POST['natureLesion_'.$row['Id']])){
							$req="INSERT INTO rh_personne_at_nature_lesion (Id_PersonneAT,Id_NatureLesion,AutreNature)
							VALUES (".$IdCree.",".$row['Id'].",'') ";
							$resultAdd=mysqli_query($bdd,$req);
						}
					}
				}
			}
			
			//Ajout des objets
			$req="SELECT Id FROM rh_typeobjet_at WHERE Suppr=0";
			$result=mysqli_query($bdd,$req);
			$nbResulta=mysqli_num_rows($result);
			if ($nbResulta>0){
				while($row=mysqli_fetch_array($result)){
					if(isset($_POST['objet_'.$row['Id']])){
						if($_POST['objet_'.$row['Id']]<>""){
							$req="INSERT INTO rh_personne_at_objet (Id_Personne_AT,Id_TypeObjet,Objet)
							VALUES (".$IdCree.",".$row['Id'].",'".stripslashes($_POST['objet_'.$row['Id']])."') ";
							$resultAdd=mysqli_query($bdd,$req);
						}
					}
				}
			}
			$bEnregistrement=true;
			
			//Envoi du mail au responsable HSE + RH + responsable plateforme
			//1 Créer le fichier Excel 
			ExcelAT($IdCree);
			
			$requete2="SELECT DateAT,Id_Prestation,Id_Pole,
				(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Prestation,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_at.Id_Personne) AS Personne 
				FROM rh_personne_at
				WHERE Id=".$IdCree;
			$result=mysqli_query($bdd,$requete2);
			$rowAT=mysqli_fetch_array($result);
	
			$PJ = array();
			
			$pj_itemLieu = array();
			$pj_itemLieu['chemin'] = 'AT/';
			$pj_itemLieu['nom'] = 'D-0250-3 formulaire de declaration des at.xlsx';
			$pj_itemLieu['MIME-Type'] = mime_content_type('AT/D-0250-3 formulaire de declaration des at.xlsx');
			$pj_itemLieu['attachement'] = encoderFichier('AT/D-0250-3 formulaire de declaration des at.xlsx');
			
			array_push($PJ, $pj_itemLieu);
			
			if($fichierPassage<>""){
				$pj_itemLieu = array();
				$pj_itemLieu['chemin'] = 'AT/';
				$pj_itemLieu['nom'] = $fichierPassage;
				$pj_itemLieu['MIME-Type'] = mime_content_type('AT/'.$fichierPassage);
				$pj_itemLieu['attachement'] = encoderFichier('AT/'.$fichierPassage);
				
				array_push($PJ, $pj_itemLieu);
			}
			
			$destinataire="pfauge@aaa-aero.com";
			
			$req="SELECT Id_Plateforme
				FROM new_competences_prestation  
				WHERE new_competences_prestation.Id=".$rowAT['Id_Prestation'];
			$resultPresta=mysqli_query($bdd,$req);
			$nbPresta=mysqli_num_rows($resultPresta);
			if($nbPresta>0){
				$rowPresta=mysqli_fetch_array($resultPresta);
				$Emails="cssct.aaa@daher.com;";
				//Resp Plateforme, Resp HSE, Resp RH, Assistant RH + backup
				//Resp qualité siège + Dir Op siège
				$reqMail="SELECT DISTINCT EmailPro 
						FROM new_competences_personne_poste_plateforme 
						LEFT JOIN new_rh_etatcivil
						ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
						WHERE (new_competences_personne_poste_plateforme.Id_Poste IN (9,14,23,31,43)
						AND Id_Plateforme=".$rowPresta['Id_Plateforme'].")
						OR (new_competences_personne_poste_plateforme.Id_Poste IN (15,41)
						AND Id_Plateforme=17)";
				$ResultMail=mysqli_query($bdd,$reqMail);
				$NbMail=mysqli_num_rows($ResultMail);
				if($NbMail>0){
					while($RowMail=mysqli_fetch_array($ResultMail)){
						if($RowMail['EmailPro']<>""){$Emails.=$RowMail['EmailPro'].";";}
					}
					
				}
				
				//Ajout du N+1,N+2,N+3,Resp projet
				$reqMail="SELECT DISTINCT EmailPro 
						FROM new_competences_personne_poste_prestation
						LEFT JOIN new_rh_etatcivil
						ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
						WHERE new_competences_personne_poste_prestation.Id_Poste IN (1,2,3,4)
						AND Id_Prestation=".$rowAT['Id_Prestation']." 
						AND Id_Pole=".$rowAT['Id_Pole']." ";
				$ResultMail=mysqli_query($bdd,$reqMail);
				$NbMail=mysqli_num_rows($ResultMail);
				if($NbMail>0){
					while($RowMail=mysqli_fetch_array($ResultMail)){
						if($RowMail['EmailPro']<>""){$Emails.=$RowMail['EmailPro'].";";}
					}
				}
				
				//Ajout des destinataires externe
				$reqMail="SELECT DISTINCT AdresseExterne 
						FROM rh_at_destinataireexterne
						WHERE Id_Prestation=".$rowAT['Id_Prestation']." 
						AND Id_Pole=".$rowAT['Id_Pole']." 
						AND AdresseExterne<>'' ";
				$ResultMail=mysqli_query($bdd,$reqMail);
				$NbMail=mysqli_num_rows($ResultMail);
				if($NbMail>0){
					while($RowMail=mysqli_fetch_array($ResultMail)){
						if($RowMail['AdresseExterne']<>""){$Emails.=$RowMail['AdresseExterne'].";";}
					}
				}
				
				if($Emails<>""){$Emails=substr($Emails,0,-1);}
				
				if($_SESSION['Langue']=="FR"){
					$sujet="AT - ".AfficheDateJJ_MM_AAAA($rowAT['DateAT'])." - ".$rowAT['Personne']." - ".$rowAT['Prestation'];
					$message_html="	<html>
						<head><title>".$sujet."</title></head>
						<body>
							Bonjour,
							<br>
							Veuillez trouver ci-joint une nouvelle déclaration d'accident de travail.
							<br>
							<br>
							Bonne journée,<br>
							L'Extranet Daher industriel services DIS.
						</body>
					</html>";
				}
				else{
					$sujet="Work accident - ".AfficheDateJJ_MM_AAAA($rowAT['DateAT'])." - ".$rowAT['Personne']." - ".$rowAT['Prestation'];
					$message_html="	<html>
						<head><title>".$sujet."</title></head>
						<body>
							Hello,
							<br>
							Please find enclosed a new work accident declaration.
							<br>
							<br>
							Have a good day.<br>
							Extranet Daher industriel services DIS.
						</body>
					</html>";
				}
				
				envoyerMailRH($Emails, $sujet, "", $message_html, $PJ);
			}
		}
	}
}
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

$etoile="";
if($Menu==3){$etoile="<img src='../../Images/etoile.png' width='8' height='8' border='0'>";}
?>

<form id="formulaire" enctype="multipart/form-data" class="test" action="Ajout_AT.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing:0; background-color:#ff1111;">
				<tr>
					<td class="TitrePage">
					<?php
						echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
						if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
						else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
						echo "</a>";
						echo "&nbsp;&nbsp;&nbsp;";
						if($_SESSION["Langue"]=="FR"){echo "Déclaration d'un accident de travail";}else{echo "Declaration of accident at work";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<?php if($bEnregistrement==true){ ?>
		<tr><td colspan="6" align="center" style="color:#ff0000;font:bold;">
			<?php 
			if($Menu==3){
				if($_SESSION["Langue"]=="FR"){echo "Cet accident de travail a été enregistré et transmis au service RH.";}
				else{echo "This work accident was recorded and forwarded to the HR department.";} 
			}
			elseif($Menu==4){
				if($_SESSION["Langue"]=="FR"){echo "Cet accident de travail a été enregistré.";}
				else{echo "This work accident was recorded.";} 
			}
			?>
			
		</td></tr>
		<tr><td height="4"></td></tr>
	<?php } ?>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="95%" align="center" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="4" class="Libelle">
								Déf: Tout événement soudain survenu par le fait ou à l'occasion du travail et ayant entraîné une lésion.<br>
								Ex: chute d'une charge suspendue et écrasement de la personne qui passait dessous.
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #fd616b"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de l'AT :";}else{echo "Date of the accident :";} ?> <?php echo $etoile; ?></td>
							<td width="10%"><input type="date" style="text-align:center;" id="dateAT" name="dateAT" size="10" value="<?php if($_POST){echo $_POST['dateAT'];$dateAT=TrsfDate_($_POST['dateAT']);}else{echo AfficheDateFR(date('Y-m-d'));$dateAT=date('Y-m-d');} ?>" onchange="submit()"></td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de l'AT : ";}else{echo "Time of the accident : ";} ?> <?php echo $etoile; ?></td>
							<td <?php if($Menu==3){echo "width='60%'";}else{echo "width='10%'";} ?>>
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small" style="text-align:center;" name="heureAT" id="heureAT" size="10" type="time" value="<?php if($_POST){echo $_POST['heureAT'];} ?>">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?> <?php echo $etoile; ?></td>
							<td width="10%">
								<select name="Id_Personne" id="Id_Personne" onchange="submit()">
								<?php
								$rq="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
									rh_personne_mouvement.Id_Prestation, rh_personne_mouvement.Id_Pole
									FROM new_rh_etatcivil
									LEFT JOIN rh_personne_mouvement 
									ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
									WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
									AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
									AND rh_personne_mouvement.EtatValidation=1 ";
								if($Menu==4){
									$rq.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN 
									(
										SELECT Id_Plateforme 
										FROM new_competences_personne_poste_plateforme
										WHERE Id_Personne=".$_SESSION['Id_Personne']." 
										AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
									)";
								}
								else{
									if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
										$rq.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN 
										(
											SELECT Id_Plateforme 
											FROM new_competences_personne_poste_plateforme
											WHERE Id_Personne=".$_SESSION['Id_Personne']." 
											AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
										)";
									}
									else{
										$rq.="AND CONCAT(Id_Prestation,'_',Id_Pole) IN 
										(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
										FROM new_competences_personne_poste_prestation 
										WHERE Id_Personne=".$_SESSION["Id_Personne"]."
										AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
										)";
									}
								}
								$rq.="ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
								$resultpersonne=mysqli_query($bdd,$rq);
								$Id_Personne=0;
								while($rowpersonne=mysqli_fetch_array($resultpersonne))
								{
									$selected="";
									if($_POST){if($_POST['Id_Personne']==$rowpersonne['Id']){$selected = "selected";$Id_Personne=$rowpersonne['Id'];}}
									else{
										if($Id_Personne==0){$Id_Personne=$rowpersonne['Id'];}
									}
									echo "<option value='".$rowpersonne['Id']."' ".$selected.">".str_replace("'"," ",$rowpersonne['Personne'])."</option>\n";
								}
								?>
								</select>
							</td>
							<?php 
								$Id_Metier=0;
								$Contrat="";
								$Id_Contrat=0;
								$Adresse="";
								$CP="";
								$Ville="";
								$NumSecu="";
								$DateNaissance="";
								$Anciennete="";
								$Est_Interim=0;
								
								$requete2="
										SELECT Id,Id_Personne,Id_Metier,Id_TypeContrat,Id_TempsTravail,
										(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,
										(SELECT LibelleEN FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContratEN,
										(SELECT EstInterim FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS EstInterim
										FROM rh_personne_contrat
										WHERE Suppr=0
										AND Id_Personne=".$Id_Personne."
										AND DateDebut<='".date('Y-m-d')."'
										AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
										AND TypeDocument IN ('Nouveau','Avenant')
										ORDER BY Id_Personne, DateDebut DESC 
									";
								$resultContrat=mysqli_query($bdd,$requete2);
								$nbContrat=mysqli_num_rows($resultContrat);
								if($nbContrat>0){
									$rowContrat=mysqli_fetch_array($resultContrat);
									$Id_Contrat=$rowContrat['Id_TypeContrat'];
									$Est_Interim=$rowContrat['EstInterim'];
									$Id_Metier=$rowContrat['Id_Metier'];
									if($_SESSION['Langue']=="FR"){
										$Contrat=$rowContrat['TypeContrat'];
									}
									else{
										$Contrat=$rowContrat['TypeContratEN'];
									}
								}
								
								$reqPers="SELECT Id, Adresse,CP,Ville,Num_SS,Date_Naissance,DateAncienneteCDI 
										FROM new_rh_etatcivil
										WHERE Id=".$Id_Personne;
								$resultPers=mysqli_query($bdd,$reqPers);
								$nbPers=mysqli_num_rows($resultPers);
								if($nbPers>0){
									$rowPers=mysqli_fetch_array($resultPers);
									$Adresse=stripslashes($rowPers['Adresse']);
									$CP=$rowPers['CP'];
									$Ville=$rowPers['Ville'];
									$NumSecu=$rowPers['Num_SS'];
									$DateNaissance=AfficheDateFR($rowPers['Date_Naissance']);
									$Anciennete=$rowPers['DateAncienneteCDI'];
								}
										
							?>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat : ";}else{echo "Contract : ";} ?> </td>
							<td <?php if($Menu==3){echo "width='60%'";}else{echo "width='10%'";} ?>>
								<input name="contrat" id="contrat" size="10" value="<?php echo $Contrat; ?>" readonly="readonly">
								<input type="hidden" name="Id_Contrat" id="Id_Contrat" value="<?php echo $Id_Contrat; ?>" />
								<input type="hidden" name="Est_Interim" id="Est_Interim" value="<?php echo $Est_Interim; ?>" />
							</td>
						</tr>
						<tr <?php if($Menu==3){echo "style='display:none;'";} ?>><td height="4"></td></tr>
						<tr <?php if($Menu==3){echo "style='display:none;'";} ?>>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Adresse :";}else{echo "Address :";} ?></td>
							<td width="10%">
								<input name="adresse" id="adresse" size="50" value="<?php echo $Adresse; ?>">
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "CP :";}else{echo "PC :";} ?></td>
							<td width="10%">
								<input name="cp" id="cp" size="8" value="<?php echo $CP; ?>">
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Ville :";}else{echo "City :";} ?></td>
							<td width="10%">
								<input name="ville" id="ville" size="20" value="<?php echo $Ville; ?>">
							</td>
						</tr>
						<tr <?php if($Menu==3){echo "style='display:none;'";} ?>><td height="4"></td></tr>
						<tr <?php if($Menu==3){echo "style='display:none;'";} ?>>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° de sécurité sociale :";}else{echo "Security number :";} ?></td>
							<td width="10%">
								<input name="numSecu" id="numSecu" size="20" value="<?php echo $NumSecu; ?>">
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de naissance :";}else{echo "Birth date :";} ?></td>
							<td width="10%">
								<input type="date" name="dateNaissance" id="dateNaissance" size="10" value="<?php echo $DateNaissance; ?>">
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Ancienneté :";}else{echo "Seniority :";} ?></td>
							<td width="10%">
								<input type="date" name="anciennete" id="anciennete" size="10" value="<?php echo AfficheDateFR($Anciennete); ?>">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Métier :";}else{echo "Job :";} ?> <?php echo $etoile; ?></td>
							<td width="10%">
								<select name="metier" id="metier" style="width:200px">
								<?php
								$rq="SELECT Id, Libelle
									FROM new_competences_metier
									WHERE Suppr=0
									ORDER BY Libelle ASC";
								$result=mysqli_query($bdd,$rq);
								while($row=mysqli_fetch_array($result))
								{
									$selected="";
									if($Id_Metier==$row['Id']){$selected = "selected";}
									echo "<option value='".$row['Id']."' ".$selected.">".str_replace("'"," ",$row['Libelle'])."</option>\n";
								}
								?>
								</select>
							</td>
							<?php
								$HeureDebut="00:00:00";
								$HeureFin="00:00:00";
								if($_POST){
									$tab=HorairesJournee($Id_Personne,$dateAT);
									if(sizeof($tab)>0){
										$HeureDebut=$tab[0];
										$HeureFin=$tab[1];
									}
								}
								$Id_PrestaPole=PrestationPole_Personne($dateAT,$Id_Personne);
							?>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Horaire de travail le jour de l'AT";}else{echo "Work schedule on the day of the accident";} ?> : <?php echo $etoile; ?></td>
							<td colspan="4">
								<?php if($_SESSION["Langue"]=="FR"){echo " de ";}else{echo " from ";} ?>
								<div class="input-group bootstrap-timepicker timepicker" style="display:inline-block">
									<input class="form-control input-small" style="text-align:center;" name="heureDebut1" id="heureDebut1" size="10" type="time" value="<?php echo $HeureDebut; ?>">
								</div>
								<?php if($_SESSION["Langue"]=="FR"){echo " à ";}else{echo " to ";} ?>
								<div class="input-group bootstrap-timepicker timepicker" style="display:inline-block">
									<input class="form-control input-small" style="text-align:center;" name="heureFin1" id="heureFin1" size="10" type="time" value="12:00:00">
								</div>
								<?php if($_SESSION["Langue"]=="FR"){echo " et de ";}else{echo " and from ";} ?>
								<div class="input-group bootstrap-timepicker timepicker" style="display:inline-block">
									<input class="form-control input-small" style="text-align:center;" name="heureDebut2" id="heureDebut2" size="10" type="time" value="13:00:00">
								</div>
								<?php if($_SESSION["Langue"]=="FR"){echo "à";}else{echo "to";} ?>
								<div class="input-group bootstrap-timepicker timepicker" style="display:inline-block">
									<input class="form-control input-small" style="text-align:center;" name="heureFin2" id="heureFin2" size="10" type="time" value="<?php echo $HeureFin; ?>">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #fd616b"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="4"><?php if($_SESSION["Langue"]=="FR"){echo "Lieu exact de l'accident (adresse exacte de l'usine où s'est produit l'AT. En cas d'accident de trajet indiquer la commune, route,...)";}
							else{echo "Accurate location of the accident (exact address of the factory where the AT occurred, in the event of a commuting accident, indicate the municipality, road, etc.)";} ?> <?php echo $etoile; ?></td>
						</tr>
						<tr>
							<td colspan="2">
								<textarea name="lieu" id="lieu" cols="90" rows="3" noresize="noresize"><?php if($_POST){echo $_POST['lieu'];} ?></textarea>
							</td>
							<td width="10%" class="Libelle" valign="top"><?php if($_SESSION["Langue"]=="FR"){echo "SIRET du client :";}else{echo "SIRET of the client :";} ?></td>
							<td width="10%" valign="top">
								<input type="text" name="siretClient" id="siretClient" size="25" value="<?php if($_POST){echo $_POST['siretClient'];} ?>">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?></td>
							<td width="10%">
								<select name="Id_Plateforme" id="Id_Plateforme" style="width:300px" onchange="RechargerPrestation('<?php echo $Id_PrestaPole;?>')">
								<option value="0"></option>
									<?php
										$Id_Plateforme=0;
										if($Id_PrestaPole<>0){
											$tabPrestaPole=explode("_",$Id_PrestaPole);
											$req="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$tabPrestaPole[0];
											$resultPlat=mysqli_query($bdd,$req);
											$nbPlat=mysqli_num_rows($resultPlat);
											if($nbPlat>0){
												$rowPla=mysqli_fetch_array($resultPlat);
												$Id_Plateforme=$rowPla['Id_Plateforme'];
											}
										}
										$requetePlat="SELECT Id, Libelle
											FROM new_competences_plateforme
											WHERE Id NOT IN (11,14)
											ORDER BY Libelle";
										$resultsPlat=mysqli_query($bdd,$requetePlat);
										while($rowPlat=mysqli_fetch_array($resultsPlat))
										{
											$selected="";
											if($Id_Plateforme==$rowPlat['Id']){$selected="selected";}
											echo "<option value='".$rowPlat['Id']."' ".$selected.">";
											echo str_replace("'"," ",stripslashes($rowPlat['Libelle']))."</option>\n";
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
							<td width="10%">
								<select name="Id_PrestationPole" id="Id_PrestationPole" class="Id_PrestationPole" onchange="AffecterSIRET()" style="width:400px">
									<option value="0_0"></option>
									<?php
										$requeteSite="SELECT DISTINCT Id, Libelle, '' AS LibellePole, 0 AS Id_Pole, Id_Plateforme
											FROM new_competences_prestation
											WHERE Active=0
											AND Id NOT IN (
												SELECT Id_Prestation
												FROM new_competences_pole    
												WHERE Actif=0
											)
											
											UNION 
											
											SELECT DISTINCT new_competences_pole.Id_Prestation, new_competences_prestation.Libelle,
												new_competences_pole.Libelle AS LibellePole, new_competences_pole.Id AS Id_Pole, new_competences_prestation.Id_Plateforme
												FROM new_competences_pole
												INNER JOIN new_competences_prestation
												ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
												AND Active=0
												AND Actif=0
												
											ORDER BY Libelle, LibellePole";
										$resultsite=mysqli_query($bdd,$requeteSite);
										$i=0;
										
										while($rowsite=mysqli_fetch_array($resultsite))
										{
											$selected="";
											if($Id_PrestaPole==$rowsite['Id']."_".$rowsite['Id_Pole']){$selected="selected";}
											echo "<option value='".$rowsite['Id']."_".$rowsite['Id_Pole']."' ".$selected.">";
											$pole="";
											if($rowsite['Id_Pole']>0){$pole=" - ".$rowsite['LibellePole'];}
											echo str_replace("'"," ",stripslashes($rowsite['Libelle'].$pole))."</option>\n";
											echo "<script>Liste_PrestaPole[".$i."] = new Array(".$rowsite['Id'].",".$rowsite['Id_Pole'].",'".str_replace("'"," ",$rowsite['Libelle'])."','".str_replace("'"," ",$rowsite['LibellePole'])."',".$rowsite['Id_Plateforme'].");</script>";
											$i++;
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #fd616b"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="2"><?php if($_SESSION["Langue"]=="FR"){echo "Etait-ce :";}else{echo "Was it :";} ?> <?php echo $etoile; ?></td>
							<td width="10%" class="Libelle" colspan="2"><?php if($_SESSION["Langue"]=="FR"){echo "Objet dont le contact a blessé la victime :";}else{echo "Object whose contact hurt the victim :";} ?> <?php echo $etoile; ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="2">
								<div id='Div_Lieu' style='height:160px;width:100%;overflow:auto;'>
									<?php
									echo "<table width='100%'>";
									if($_SESSION["Langue"]=="FR"){$req="SELECT Id, Libelle FROM rh_lieu_at WHERE Suppr=0 ORDER BY Libelle";}
									else{$req="SELECT Id, LibelleEN AS Libelle FROM rh_lieu_at WHERE Suppr=0 ORDER BY LibelleEN";}
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									$selected=0;
									if ($nbResulta>0){
										while($row=mysqli_fetch_array($result)){
											$select="";
											
											if($_POST){
												if($_POST['lieus']==$row['Id']){$select="checked";$selected=1;}
											}
											else{
												if($selected==0){$select="checked";$selected=1;}
											}
											echo "<tr><td><input type='radio' class='lieus' name='lieus' value='".$row['Id']."' ".$select.">".$row['Libelle']."</td></tr>";
										}
									}
									echo "</table>";
									?>
								</div>
							</td>
							<td colspan="2">
								<div id='Div_Objet' style='height:160px;width:100%;overflow:auto;'>
									<?php
									echo "<table width='100%'>";
									if($_SESSION["Langue"]=="FR"){$req="SELECT Id, Libelle FROM rh_typeobjet_at WHERE Suppr=0 ORDER BY Libelle";}
									else{$req="SELECT Id, LibelleEN AS Libelle FROM rh_typeobjet_at WHERE Suppr=0 ORDER BY LibelleEN";}
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($row=mysqli_fetch_array($result)){
											$valeur="";
											if($_POST){
												$valeur=$_POST['objet_'.$row['Id']];
											}
											if($row['Libelle']=="Autres" || $row['Libelle']=="Autre" || $row['Libelle']=="Others" || $row['Libelle']=="Other"|| 
											$row['Libelle']=="°Autres" || $row['Libelle']=="°Autre" || $row['Libelle']=="°Others" || $row['Libelle']=="°Other"){
												echo "<tr><td>".substr($row['Libelle'],1)." : </td><td><input type='text' class='objets' name='objet_".$row['Id']."' id='objet_".$row['Id']."' value='".$valeur."'></td></tr>";
											}
											else{
												echo "<tr><td>".$row['Libelle']." : </td><td><input type='text' class='objets' name='objet_".$row['Id']."' id='objet_".$row['Id']."' value='".$valeur."'></td></tr>";
											}
										}
									}
									echo "</table>";
									?>
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #fd616b"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="4"><?php if($_SESSION["Langue"]=="FR"){echo "Activité de la victime lors de l'accident (décrire l'activité exacte que le salarié exerçait au moment de l'accident)";}
							else{echo "Activity of the victim during the accident (describe the exact activity the employee was performing at the time of the accident)";} ?> <?php echo $etoile; ?></td>
						</tr>
						<tr>
							<td colspan="2">
								<textarea name="activiteVictime" id="activiteVictime" cols="90" rows="3" noresize="noresize"><?php if($_POST){echo $_POST['activiteVictime'];} ?></textarea>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #fd616b"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="4"><?php if($_SESSION["Langue"]=="FR"){echo "Dans le cas d'un accident de trajet (circonstances de l'AT Trajet)";}
							else{echo "In the event of a commuting accident (circumstances of AT Trajet)";} ?></td>
						</tr>
						<tr>
							<td width="10%" class="Libelle" colspan="2"><?php if($_SESSION["Langue"]=="FR"){echo "Type de véhicule :";}else{echo "Vehicle type :";} ?></td>
							<td width="10%" class="Libelle" colspan="2"></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="2" valign="top">
								<div id='Div_TypeVehicule' style='height:160px;width:100%;overflow:auto;'>
									<?php
									echo "<table width='100%'>";
									$req="SELECT Id, Libelle FROM rh_typevehicule WHERE Suppr=0 ORDER BY Libelle";
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									$selected=0;
									if ($nbResulta>0){
										while($row=mysqli_fetch_array($result)){
											$select="";
											
											if($_POST){
												if(isset($_POST['typevehicule'])){
													if($_POST['typevehicule']==$row['Id']){$select="checked";$selected=1;}
												}
											}
											echo "<tr><td><input type='radio' class='typevehicule' name='typevehicule' value='".$row['Id']."' ".$select.">".$row['Libelle']."</td></tr>";
										}
									}
									echo "</table>";
									?>
								</div>
							</td>
							<td colspan="2">
									<?php
									echo "<table width='100%'>";
									
									$valeur="";
									if($_POST){if(isset($_POST['conditionsClim'])){$valeur="checked";}}
									echo "<tr>
										<td width='15%'>Conditions climatiques particulières : </td>
										<td width='15%'><input type='checkbox' class='conditions' name='conditionsClim' id='conditionsClim' ".$valeur."></td>
										<td width='70%' rowspan='2'><textarea name='commentaireCondition1' id='commentaireCondition1' cols='30' rows='2' noresize='noresize'>";
										if($_POST){echo $_POST['commentaireCondition1'];}
									echo "</textarea></td>
									</tr>";
									$valeur="";
									if($_POST){if(isset($_POST['mauvaisEtatInfra'])){$valeur="checked";}}
									echo "<tr>
											<td width='15%'>Mauvais état des infrastructures : </td>
											<td width='15%'><input type='checkbox' class='conditions' name='mauvaisEtatInfra' id='mauvaisEtatInfra' ".$valeur."></td>
											<td width='70%'></td>
										</tr>";
									
									$select1="";
									$select2="";
									if($_POST){
										if(isset($_POST['trajet'])){
											if($_POST['trajet']==1){$select1="checked";}
											if($_POST['trajet']==2){$select2="checked";}
										}
									}
									echo "<tr>
										<td width='15%'><input type='radio' class='trajet' name='trajet' value='1' ".$select1.">Trajet Aller</td>
										<td width='15%'><input type='radio' class='trajet' name='trajet' value='2' ".$select2.">Trajet Retour</td>
										<td width='70%'></td>
									</tr>";
									
									$valeur="";
									if($_POST){if(isset($_POST['horairesSpec'])){$valeur="checked";}}
									echo "<tr>
											<td width='15%'>Horaires de travail spécifiques : </td>
											<td width='15%'><input type='checkbox' class='conditions' name='horairesSpec' id='horairesSpec' ".$valeur."></td>
											<td width='70%' rowspan='2'><textarea name='commentaireCondition2' id='commentaireCondition2' cols='30' rows='2' noresize='noresize'>";
											if($_POST){echo $_POST['commentaireCondition2'];}
											echo "</textarea></td>
										</tr>";
									$valeur="";
									if($_POST){if(isset($_POST['pbTechnique'])){$valeur="checked";}}
									echo "<tr>
											<td width='15%'>Problème technique du véhicule accidenté : </td>
											<td width='15%'><input type='checkbox' class='conditions' name='pbTechnique' id='pbTechnique' ".$valeur."></td>
											<td width='70%'></td>
											</tr>
											";
									echo "</table>";
									?>

							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #fd616b"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="4"><?php if($_SESSION["Langue"]=="FR"){echo "Nature de l'accident (rupture de matériel, glissade, chute, effort, heurt, collision, écrasement, coupure, substance dangereuse ...";}
							else{echo "Nature of the accident (equipment breakdown, slipping, falling, exertion, impact, collision, crushing, cutting, dangerous substance ...";} ?> <?php echo $etoile; ?></td>
						</tr>
						<tr>
							<td colspan="2">
								<textarea name="natureAccident" id="natureAccident" cols="90" rows="3" noresize="noresize"><?php if($_POST){echo $_POST['natureAccident'];} ?></textarea>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #fd616b"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="2"><?php if($_SESSION["Langue"]=="FR"){echo "Siège(s) des lésions et côté concerné :";}else{echo "Seat(s) of lesions and side concerned :";} ?> <?php echo $etoile; ?></td>
							<td width="10%" class="Libelle" colspan="2"><?php if($_SESSION["Langue"]=="FR"){echo "Nature des lésions :";}else{echo "Nature of lesions :";} ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="2">
								<div id='Div_SiegeLesion' style='height:200px;width:50%;overflow:auto;'>
									<?php
									echo "<table width='100%'>";
									if($_SESSION["Langue"]=="FR"){$req="SELECT Id, Libelle, CoteGD FROM rh_siege_lesion_at WHERE Suppr=0 ORDER BY Libelle";}
									else{$req="SELECT Id, LibelleEN AS Libelle, CoteGD FROM rh_siege_lesion_at WHERE Suppr=0 ORDER BY LibelleEN";}
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($row=mysqli_fetch_array($result)){
											if($row['Libelle']=="Autres" || $row['Libelle']=="Autre" || $row['Libelle']=="Others" || $row['Libelle']=="Other"|| 
											$row['Libelle']=="°Autres" || $row['Libelle']=="°Autre" || $row['Libelle']=="°Others" || $row['Libelle']=="°Other"){
												echo "<tr><td width='10%'>".substr($row['Libelle'],1)."</td><td width='90%'>";
												$valeur="";
												if($_POST){
													$valeur=$_POST['siegeLesionAutre_'.$row['Id']];
												}
												echo"<input type='text' class='siegesAutres' name='siegeLesionAutre_".$row['Id']."' value='".$valeur."'>";
											}
											else{
												echo "<tr><td width='10%'>".$row['Libelle']."</td><td width='90%'>";
												if($row['CoteGD']==0){
													$valeur="";
													if($_POST){
														if(isset($_POST['siegeLesion_'.$row['Id']])){$valeur="checked";}
													}
													echo"<input type='checkbox' class='sieges' name='siegeLesion_".$row['Id']."' ".$valeur." value='siegeLesion_".$row['Id']."'>";
												}
												else{
													$valeurG="";
													$valeurD="";
													if($_POST){
														if(isset($_POST['siegeLesionG_'.$row['Id']])){$valeurG="checked";}
														if(isset($_POST['siegeLesionD_'.$row['Id']])){$valeurD="checked";}
													}
													echo"<input type='checkbox' class='sieges' name='siegeLesionG_".$row['Id']."' ".$valeurG." value='siegeLesionG_".$row['Id']."'>";
													if($_SESSION["Langue"]=="FR"){echo "G";}else{echo "L";}
													echo"<input type='checkbox' class='sieges' name='siegeLesionD_".$row['Id']."' ".$valeurD." value='siegeLesionD_".$row['Id']."'>";
													if($_SESSION["Langue"]=="FR"){echo "D";}else{echo "R";}
												}
											}
											echo "</td></tr>";
										}
									}
									echo "</table>";
									?>
								</div>
							</td>
							<td colspan="2">
								<div id='Div_NatureLesion' style='height:200px;width:80%;overflow:auto;'>
									<?php
									echo "<table width='100%'>";
									if($_SESSION["Langue"]=="FR"){$req="SELECT Id, Libelle FROM rh_nature_lesion WHERE Suppr=0 ORDER BY Libelle";}
									else{$req="SELECT Id, LibelleEN AS Libelle FROM rh_nature_lesion WHERE Suppr=0 ORDER BY LibelleEN";}
									$result=mysqli_query($bdd,$req);
									$nbResulta=mysqli_num_rows($result);
									if ($nbResulta>0){
										while($row=mysqli_fetch_array($result)){
											if($row['Libelle']=="Autres" || $row['Libelle']=="Autre" || $row['Libelle']=="Others" || $row['Libelle']=="Other" || 
											$row['Libelle']=="°Autres" || $row['Libelle']=="°Autre" || $row['Libelle']=="°Others" || $row['Libelle']=="°Other"){
												echo "<tr><td width='15%'>".substr($row['Libelle'],1)."</td><td width='90%'>";
												$valeur="";
												if($_POST){
													$valeur=$_POST['natureLesionAutre_'.$row['Id']];
												}
												echo"<input type='text' class='naturesAutres' name='natureLesionAutre_".$row['Id']."' value='".$valeur."'>";
											}
											else{
												$valeur="";
												if($_POST){
													if(isset($_POST['natureLesion_'.$row['Id']])){$valeur="checked";}
												}
												echo "<tr><td width='15%'>".$row['Libelle']."</td><td width='90%'>";
												echo"<input type='checkbox' class='natures' name='natureLesion_".$row['Id']."' ".$valeur." value='natureLesion_".$row['Id']."'>";
											}
											echo "</td></tr>";
										}
									}
									echo "</table>";
									?>
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #fd616b"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="2"><?php if($_SESSION["Langue"]=="FR"){echo "Conséquences de l'AT :";}else{echo "Consequences of the accident :";} ?></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Arrêt de travail :";}else{echo "Work stopping :";} ?>
							<input type="radio" name="arretTravail" id="arretTravail" <?php if($_POST){if($_POST['arretTravail']==1){echo "checked";}} ?> value="1">
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Sans arrêt :";}else{echo "Nonstop :";} ?>
							<input type="radio" name="arretTravail" id="arretTravail" <?php if($_POST){if($_POST['arretTravail']==0){echo "checked";}}else{echo "checked";} ?> value="0">
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Evacuation vers :";}else{echo "Evacuation to :";} ?></td>
							<td width="10%">
								<input type="text" name="evacuationVers" id="evacuationVers" size="35" value="<?php if($_POST){echo $_POST['evacuationVers'];} ?>">	
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Autre victime :";}else{echo "Other victim :";} ?></td>
							<td width="10%">
								<input type="text" name="autreVictime" id="autreVictime" size="35" value="<?php if($_POST){echo $_POST['autreVictime'];} ?>">	
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Tiers responsable :";}else{echo "Third party :";} ?></td>
							<td width="10%">
								<input type="text" name="tiersResponsable" id="tiersResponsable" size="35" value="<?php if($_POST){echo $_POST['tiersResponsable'];} ?>">	
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Témoin :";}else{echo "Witness :";} ?> <?php echo $etoile; ?></td>
							<td width="10%">
								<input type="text" name="temoin" id="temoin" size="35" value="<?php if($_POST){echo $_POST['temoin'];} ?>">	
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Coordonnées :";}else{echo "Contact information :";} ?></td>
							<td width="10%">
								<input type="text" name="coordonnees" id="coordonnees" size="35" value="<?php if($_POST){echo $_POST['coordonnees'];} ?>">	
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "1ère personne avertie :";}else{echo "1st informed person :";} ?></td>
							<td width="10%">
								<input type="text" name="personne1" id="personne1" size="35" value="<?php if($_POST){echo $_POST['personne1'];} ?>">	
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de connaissance de l'AT :";}else{echo "Date of knowledge of the accident :";} ?> </td>
							<td width="10%"><input type="date" style="text-align:center;" id="dateConnaissanceAT" name="dateConnaissanceAT" size="10" value="<?php if($_POST){echo $_POST['dateConnaissanceAT'];} ?>"></td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de connaissance de l'AT : ";}else{echo "Time of knowledge of the accident : ";} ?> </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small" style="text-align:center;" name="heureConnaisanceAT" id="heureConnaisanceAT" size="10" type="time" value="<?php if($_POST){echo $_POST['heureConnaisanceAT'];} ?>">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #fd616b"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="4"><?php if($_SESSION["Langue"]=="FR"){echo "Doutes, réserves sur les circonstances professionnels de l'accident";}
							else{echo "Doubts, reservations about the professional circumstances of the accident";} ?></td>
						</tr>
						<tr>
							<td colspan="2">
								<textarea name="doutes" id="doutes" cols="90" rows="3" noresize="noresize"><?php if($_POST){echo $_POST['doutes'];} ?></textarea>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr><td height="4" colspan="8" style="border-top:2px dotted #fd616b"></td></tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle" colspan="4"><?php if($_SESSION["Langue"]=="FR"){echo "Actions de sécurisation immédiates (Ex: isolement outil défectueux / balisage / arrêt de l'intervention / sensibilisation du reste de l'équipe…)";}
							else{echo "Immediate security actions (Ex: defective tool isolation / marking / stopping the intervention / awareness of the rest of the team ...)";} ?></td>
						</tr>
						<tr>
							<td colspan="2">
								<textarea name="autresInfos" id="autresInfos" cols="90" rows="3" noresize="noresize"><?php if($_POST){echo $_POST['autresInfos'];} ?></textarea>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Passage à l'infirmerie";}else{echo "Passage to the infirmary";}?> : </td>
							<td><input name="fichier" type="file" onChange="CheckFichier();"></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="6" align="center">
								<div id="Ajouter">
								</div>
								<input class="Bouton" type="button" id="btnEnregistrer" name="btnEnregistrer" value="Enregistrer" onClick="Enregistrer()">
							</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
</table>
</form>
<?php
	echo "<script>RechargerPrestation('".$Id_PrestaPole."');</script>";
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>
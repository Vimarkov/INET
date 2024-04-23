<?php
require("../../Menu.php");
?>
<script language="javascript" src="DODM.js"></script>
<script type="text/javascript">
	function Affiche_Heure(check){
		if(check==1){
			var elements = document.getElementsByClassName('heures');
			for (i=0; i<elements.length; i++){
			  elements[i].style.display='none';
			}
			document.getElementById('heureDebut').value="";
			document.getElementById('heureFin').value="";
		}
		else{
			var elements = document.getElementsByClassName('heures');
			for (i=0; i<elements.length; i++){
			  elements[i].style.display='';
			}
		}
		if(document.getElementById('journeeEntiere').checked==false){
			formulaire.dateFin.value=formulaire.dateDebut.value;
		}
	}
	$(document).ready(function () {
			$('#heureDebut').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: false,
				showMeridian: false,
				defaultTime: false
			});
			Mask.newMask({ 
				$el: $('#heureDebut'), 
				mask: 'HH:mm' 
			});
			$('#heureFin').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: false,
				showMeridian: false,
				defaultTime: false
			});
			Mask.newMask({ 
				$el: $('#heureFin'), 
				mask: 'HH:mm' 
			});
			$('#besoinHeureDepartAller_').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: false,
				showMeridian: false,
				defaultTime: false
			});
			Mask.newMask({ 
				$el: $('#besoinHeureDepartAller_'), 
				mask: 'HH:mm' 
			});
			$('#besoinHeureArriveeAller_').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: false,
				showMeridian: false,
				defaultTime: false
			});
			Mask.newMask({ 
				$el: $('#besoinHeureArriveeAller_'), 
				mask: 'HH:mm' 
			});
			$('#besoinHeureDepartRetour_').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: false,
				showMeridian: false,
				defaultTime: false
			});
			Mask.newMask({ 
				$el: $('#besoinHeureDepartRetour_'), 
				mask: 'HH:mm' 
			});
			$('#besoinHeureArriveeRetour_').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: false,
				showMeridian: false,
				defaultTime: false
			});
			Mask.newMask({ 
				$el: $('#besoinHeureArriveeRetour_'), 
				mask: 'HH:mm' 
			});
			$('#besoinHeureDebutLocationAAA_').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: false,
				showMeridian: false,
				defaultTime: false
			});
			Mask.newMask({ 
				$el: $('#besoinHeureDebutLocationAAA_'), 
				mask: 'HH:mm' 
			});
			$('#besoinHeureFinLocationAAA_').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: false,
				showMeridian: false,
				defaultTime: false
			});
			Mask.newMask({ 
				$el: $('#besoinHeureFinLocationAAA_'), 
				mask: 'HH:mm' 
			});
			$('#besoinHeureDepartLocationVoiture_').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: false,
				showMeridian: false,
				defaultTime: false
			});
			Mask.newMask({ 
				$el: $('#besoinHeureDepartLocationVoiture_'), 
				mask: 'HH:mm' 
			});
			$('#besoinHeureRetourLocationVoiture_').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: false,
				showMeridian: false,
				defaultTime: false
			});
			Mask.newMask({ 
				$el: $('#besoinHeureRetourLocationVoiture_'), 
				mask: 'HH:mm' 
			});
		});
</script>
<script type="text/javascript">
	function VerifDate()
	{
		//Si date < Mois E/C -2 OU date < Mois E/C -1 ET Date E/C >=10 du mois alors IMPOSSIBLE = Efface les infos 
		myDateDebut = formulaire.dateDebut.value;
		myDateDebut2 = myDateDebut.split("-");
		dateJJJJMM="";
		if (myDateDebut2.length == 1){
			myDateDebut = myDateDebut.split("/");
			dateJJJJMM=myDateDebut[2]+"/"+myDateDebut[1];
		}
		else{
			myDateDebut = myDateDebut.split("-");
			dateJJJJMM=myDateDebut[0]+"/"+myDateDebut[1];
		}

		var ladate=new Date();
		ladate.setMonth(ladate.getMonth()-2);
		mois=ladate.getMonth()+1;
		if(mois<10){mois="0"+mois;}
		date_2Mois=ladate.getFullYear()+"/"+(mois);
		
		var ladate=new Date();
		ladate.setMonth(ladate.getMonth()-1);
		mois=ladate.getMonth()+1;
		if(mois<10){mois="0"+mois;}
		date_1Mois=ladate.getFullYear()+"/"+(mois);
		
		var ladate=new Date();
		mois=ladate.getMonth()+1;
		if(mois<10){mois="0"+mois;}
		date_10=ladate.getFullYear()+"-"+mois+"-10";
		
		var ladate=new Date();
		mois=ladate.getMonth()+1;
		if(mois<10){mois="0"+mois;}
		jour=ladate.getDate();
		if(jour<10){jour="0"+jour;}
		date_Jour=ladate.getFullYear()+"-"+mois+"-"+jour;
		
		if(formulaire.Menu.value!=4){
			if(dateJJJJMM<=date_2Mois || (dateJJJJMM<date_1Mois && date_Jour>=date_10)){
				formulaire.dateDebut.value="";
			}
		}
	}
</script>
<?php
$DateJour=date("Y-m-d");
$bEnregistrement=false;
$Message="";
if($_POST){
	if($_SESSION['Id_Personne']<>""){
		$Personne="";
		if(isset($_POST['PersonneSelect']))
		{
			$PersonneSelect = $_POST['PersonneSelect'];
			for($i=0;$i<sizeof($PersonneSelect);$i++)
			{
				if(isset($PersonneSelect[$i])){$Personne.=$PersonneSelect[$i].";";}
			}
		}
		$TabPersonne = preg_split("/[;]+/", $Personne);
		for($i=0;$i<sizeof($TabPersonne)-1;$i++){
			$tabPresta=explode("_",$_POST['Id_PrestationPoleAccueil']);
			
			$montant=0;
			$avance=0;
			$periode="";
			if($_POST['demandeAvance']==1){
				if($_POST['montant']<>""){$montant=$_POST['montant'];}
				$avance=$_POST['avance'];
				$periode=$_POST['periode'];
			}
			
			$Id_Metier=0;
			$requete2="
				SELECT *
				FROM
				(
					SELECT *
					FROM 
						(SELECT Id,Id_Personne,Id_Metier,(@row_number:=@row_number + 1) AS rnk
						FROM rh_personne_contrat 
						WHERE Suppr=0
						AND Id_Personne=".$TabPersonne[$i]."
						AND DateDebut<='".date('Y-m-d')."'
						AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
						AND TypeDocument IN ('Nouveau','Avenant')
						ORDER BY Id_Personne, DateDebut DESC, Id DESC) AS table_contrat 
					GROUP BY Id_Personne
				) AS table_contrat2

				";
			$result=mysqli_query($bdd,$requete2);
			$nbResulta=mysqli_num_rows($result);
			if($nbResulta>0){
				$row=mysqli_fetch_array($result);
				$Id_Metier=$row['Id_Metier'];
			}
			
			$heureD="00:00:00";
			$heureF="00:00:00";
			if($_POST['heureDebut']<>""){$heureD=$_POST['heureDebut'];}
			if($_POST['heureFin']<>""){$heureF=$_POST['heureFin'];}
			
			$requete="INSERT INTO rh_personne_petitdeplacement 
					(Id_Personne,Id_Prestation,Id_Pole,Id_Createur,DateCreation,Lieu,Pays,Id_PrestationDeplacement,Id_PoleDeplacement,FraisReel,
					DateDebut,DateFin,Id_Metier,Montant,AvancePonctuelle,Periode,ObjetDeplacement,HeureDebut,HeureFin) 
					VALUES 
					(".$TabPersonne[$i].",".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",".$_SESSION['Id_Personne'].",'".date('Y-m-d')."','".addslashes($_POST['lieu'])."','".addslashes($_POST['pays'])."',".$tabPresta[0].",".$tabPresta[1].",".$_POST['typeDeFrais'].",
			'".TrsfDate_($_POST['dateDebut'])."','".TrsfDate_($_POST['dateFin'])."',".$Id_Metier.",".$montant.",".$avance.",'".TrsfDate_($periode)."','".addslashes($_POST['objectDeplacement'])."','".$heureD."','".$heureF."')";

			$result=mysqli_query($bdd,$requete);
			$IdCree = mysqli_insert_id($bdd);

			if($IdCree>0){
				//Besoins de réservation 
				$req="SELECT Id, Libelle FROM rh_typebesoin WHERE Suppr=0";
				$result=mysqli_query($bdd,$req);
				$nbResulta=mysqli_num_rows($result);
				if ($nbResulta>0){
					while($row=mysqli_fetch_array($result)){
						if(isset($_POST['CheckBesoin_'.$row['Id']])){
							$TypeTrajet="";
							$LieuDepartAller="";
							$LieuArriveeAller="";
							$DateDepartAller="0001-01-01";
							$HeureDepartAller="00:00:00";
							$HeureArriveeAller="00:00:00";
							$LieuDepartRetour="";
							$LieuArriveeRetour="";
							$DateDepartRetour="0001-01-01";
							$HeureDepartRetour="00:00:00";
							$HeureArriveeRetour="00:00:00";
							$VehiculeAAA="";
							$DateDebutVehiculeAAA="0001-01-01";
							$DateFinVehiculeAAA="0001-01-01";
							$HeureDebutVehiculeAAA="00:00:00";
							$HeureFinVehiculeAAA="00:00:00";
							$ConducteurLocationVoiture="";
							$LieuDebutLocationVoiture="";
							$DateDebutLocationVoiture="0001-01-01";
							$HeureDebutLocationVoiture="00:00:00";
							$LieuFinLocationVoiture="";
							$DateFinLocationVoiture="0001-01-01";
							$HeureFinLocationVoiture="00:00:00";
							$NbNuitHotel=0;
							$LieuHotel="";
							$DateArriveeHotel="0001-01-01";
							$DateDepartHotel="0001-01-01";
							if($row['Id']==2){
								$NbNuitHotel=unNombreSinon0_($_POST['besoinNbNuitHotel_']);
								$LieuHotel=addslashes($_POST['besoinLieuHotel_']);
								$DateArriveeHotel=TrsfDate_($_POST['besoinDateArriveeHotel_']);
								$DateDepartHotel=TrsfDate_($_POST['besoinDateDepartHotel_']);
							}
							elseif($row['Id']==3){
								$TypeTrajet=$_POST['besoinTrainAvion_'];
								$LieuDepartAller=addslashes($_POST['besoinLieuDepartAller_']);
								$LieuArriveeAller=addslashes($_POST['besoinLieuArriveeAller_']);
								$DateDepartAller=TrsfDate_($_POST['besoinDateDepartAller_']);
								if($_POST['besoinHeureDepartAller_']<>""){$HeureDepartAller=$_POST['besoinHeureDepartAller_'];}
								if($_POST['besoinHeureArriveeAller_']<>""){$HeureArriveeAller=$_POST['besoinHeureArriveeAller_'];}
								$LieuDepartRetour=addslashes($_POST['besoinLieuDepartRetour_']);
								$LieuArriveeRetour=addslashes($_POST['besoinLieuArriveeRetour_']);
								$DateDepartRetour=TrsfDate_($_POST['besoinDateArriveeRetour_']);
								if($_POST['besoinHeureDepartRetour_']<>""){$HeureDepartRetour=$_POST['besoinHeureDepartRetour_'];}
								if($_POST['besoinHeureArriveeRetour_']<>""){$HeureArriveeRetour=$_POST['besoinHeureArriveeRetour_'];}
							}
							elseif($row['Id']==4){
								$ConducteurLocationVoiture=addslashes($_POST['besoinNomConducteur_']);
								$LieuDebutLocationVoiture=addslashes($_POST['besoinLieuDepartLocationVoiture_']);
								$DateDebutLocationVoiture=TrsfDate_($_POST['besoinDateDepartLocationVoiture_']);
								if($_POST['besoinHeureDepartLocationVoiture_']<>""){$HeureDebutLocationVoiture=$_POST['besoinHeureDepartLocationVoiture_'];}
								$LieuFinLocationVoiture=addslashes($_POST['besoinLieuRetourLocationVoiture_']);
								$DateFinLocationVoiture=TrsfDate_($_POST['besoinDateRetourLocationVoiture_']);
								if($_POST['besoinHeureRetourLocationVoiture_']<>""){$HeureFinLocationVoiture=$_POST['besoinHeureRetourLocationVoiture_'];}
							}
							elseif($row['Id']==5){
								$VehiculeAAA=addslashes($_POST['besoinVehiculeAAA_']);
								$DateDebutVehiculeAAA=TrsfDate_($_POST['besoinDateDebutLocationAAA_']);
								$DateFinVehiculeAAA=TrsfDate_($_POST['besoinDateFinLocationAAA_']);
								if($_POST['besoinHeureDebutLocationAAA_']<>""){$HeureDebutVehiculeAAA=$_POST['besoinHeureDebutLocationAAA_'];}
								if($_POST['besoinHeureFinLocationAAA_']<>""){$HeureFinVehiculeAAA=$_POST['besoinHeureFinLocationAAA_'];}
							}
							$req="INSERT INTO rh_personne_petitdeplacement_typebesoin (Id_Personne_PetitDeplacement,Id_TypeBesoin,Commentaire,
							TypeTrajet,LieuDepartAller,LieuArriveeAller,DateDepartAller,HeureDepartAller,HeureArriveeAller,LieuDepartRetour,LieuArriveeRetour,DateDepartRetour,HeureDepartRetour,HeureArriveeRetour,
							VehiculeAAA,DateDebutVehiculeAAA,DateFinVehiculeAAA,HeureDebutVehiculeAAA,HeureFinVehiculeAAA,
							ConducteurLocationVoiture,LieuDebutLocationVoiture,DateDebutLocationVoiture,HeureDebutLocationVoiture,LieuFinLocationVoiture,DateFinLocationVoiture,HeureFinLocationVoiture,
							NbNuitHotel,LieuHotel,DateArriveeHotel,DateDepartHotel)
							VALUES (".$IdCree.",".$row['Id'].",'".addslashes($_POST['besoin_'.$row['Id']])."',
							'".$TypeTrajet."',
							'".$LieuDepartAller."',
							'".$LieuArriveeAller."',
							'".$DateDepartAller."',
							'".$HeureDepartAller."',
							'".$HeureArriveeAller."',
							'".$LieuDepartRetour."',
							'".$LieuArriveeRetour."',
							'".$DateDepartRetour."',
							'".$HeureDepartRetour."',
							'".$HeureArriveeRetour."',
							'".$VehiculeAAA."',
							'".$DateDebutVehiculeAAA."',
							'".$DateFinVehiculeAAA."',
							'".$HeureDebutVehiculeAAA."',
							'".$HeureFinVehiculeAAA."',
							'".$ConducteurLocationVoiture."',
							'".$LieuDebutLocationVoiture."',
							'".$DateDebutLocationVoiture."',
							'".$HeureDebutLocationVoiture."',
							'".$LieuFinLocationVoiture."',
							'".$DateFinLocationVoiture."',
							'".$HeureFinLocationVoiture."',
							".$NbNuitHotel.",
							'".$LieuHotel."',
							'".$DateArriveeHotel."',
							'".$DateDepartHotel."'
							) ";
							$resultAdd=mysqli_query($bdd,$req);
						}
					}
				}
				
				//Envoyer un mail pour informer d'un nouveau petit déplacement ponctuel 
				$requete2="SELECT Id,Id_Prestation,Id_Pole,
					(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Prestation,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_petitdeplacement.Id_Personne) AS Personne 
					FROM rh_personne_petitdeplacement
					WHERE Id=".$IdCree;

				$result=mysqli_query($bdd,$requete2);
				$rowDODM=mysqli_fetch_array($result);
			
				if($_SESSION['Langue']=="FR"){
					$sujet="Nouvelle déclaration de petit déplacement ponctuel - n°".$IdCree." - ".$rowDODM['Personne']." - ".$rowDODM['Prestation'];
					$message_html="	<html>
						<head><title>".$sujet."</title></head>
						<body>
							Bonjour,
							<br>
							Une nouvelle déclaration de petit déplacement ponctuel a été créé pour ".$rowDODM['Personne']." (n°".$IdCree.")
							<br>
							Veuillez vous rendre sur l'Extranet pour prendre en compte cette déclaration.
							<br>
							<br>
							Bonne journée,<br>
							L'Extranet Daher industriel services DIS.
						</body>
					</html>";
				}
				else{
					$sujet="New declaration of small one-off trip - n°".$IdCree." - ".$rowDODM['Personne']." - ".$rowDODM['Prestation'];
					$message_html="	<html>
						<head><title>".$sujet."</title></head>
						<body>
							Bonjour,
							<br>
							A new declaration of small punctual displacement has been created for ".$rowDODM['Personne']." (n°".$IdCree.")
							<br>
							Please visit the Extranet to take this statement into account.
							<br>
							<br>
							Have a good day,<br>
							Extranet Daher industriel services DIS.
						</body>
					</html>";
				}
				
			$req="SELECT Id_Plateforme
			FROM new_competences_prestation  
			WHERE new_competences_prestation.Id=".$rowDODM['Id_Prestation'];
			$resultPresta=mysqli_query($bdd,$req);
			$nbPresta=mysqli_num_rows($resultPresta);
			if($nbPresta>0){
				$rowPresta=mysqli_fetch_array($resultPresta);
				$Emails="";
				//Resp RH + Assistante RH + Service admin
				$reqMail="SELECT DISTINCT EmailPro 
						FROM new_competences_personne_poste_plateforme 
						LEFT JOIN new_rh_etatcivil
						ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
						WHERE new_competences_personne_poste_plateforme.Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.",".$IdPosteAssistantAdministratif.")
						AND Id_Plateforme=".$rowPresta['Id_Plateforme']." ";
				$ResultMail=mysqli_query($bdd,$reqMail);
				$NbMail=mysqli_num_rows($ResultMail);
				if($NbMail>0){
					while($RowMail=mysqli_fetch_array($ResultMail)){
						if($RowMail['EmailPro']<>""){$Emails.=$RowMail['EmailPro'].",";}
					}
					
				}
				
				//Vérifier si les MGX doivent recevoir un  mail 
				$req="SELECT rh_personne_petitdeplacement_typebesoin.Id FROM rh_personne_petitdeplacement_typebesoin LEFT JOIN rh_typebesoin ON rh_personne_petitdeplacement_typebesoin.Id_TypeBesoin=rh_typebesoin.Id WHERE rh_personne_petitdeplacement_typebesoin.Suppr=0 AND ServiceConcerne='Moyens généraux' AND Id_Personne_PetitDeplacement=".$rowDODM['Id']." ";
				$resultMGX=mysqli_query($bdd,$req);
				$nbMGX=mysqli_num_rows($resultMGX);
				if($nbMGX>0){
					//MGX
					$reqMail="SELECT DISTINCT EmailPro 
							FROM new_competences_personne_poste_plateforme 
							LEFT JOIN new_rh_etatcivil
							ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
							WHERE new_competences_personne_poste_plateforme.Id_Poste IN (".$IdPosteResponsableMGX.",".$IdPosteGestionnaireMGX.")
							AND Id_Plateforme=".$rowPresta['Id_Plateforme']." ";
					$ResultMail=mysqli_query($bdd,$reqMail);
					$NbMail=mysqli_num_rows($ResultMail);
					if($NbMail>0){
						while($RowMail=mysqli_fetch_array($ResultMail)){
							if($RowMail['EmailPro']<>""){$Emails.=$RowMail['EmailPro'].",";}
						}
						
					}
				}
				
				//Ajout du N+1
				$reqMail="SELECT DISTINCT EmailPro 
						FROM new_competences_personne_poste_prestation
						LEFT JOIN new_rh_etatcivil
						ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
						WHERE new_competences_personne_poste_prestation.Id_Poste IN (2)
						AND Id_Prestation=".$rowDODM['Id_Prestation']." 
						AND Id_Pole=".$rowDODM['Id_Pole']." ";
				$ResultMail=mysqli_query($bdd,$reqMail);
				$NbMail=mysqli_num_rows($ResultMail);
				if($NbMail>0){
					while($RowMail=mysqli_fetch_array($ResultMail)){
						if($RowMail['EmailPro']<>""){$Emails.=$RowMail['EmailPro'].",";}
					}
				}
				
				if($Emails<>""){$Emails=substr($Emails,0,-1);}
				
				//$Emails="pfauge@aaa-aero.com";
				if($Emails<>"")
				{
					$Headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
					$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
				
					if(mail($Emails,$sujet,$message_html,$Headers,'-f extranet@aaa-aero.com')){echo "";}
					else{echo "";}
				}
			}
			
				$bEnregistrement=true;
			}

		}
	}
}
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
?>

<form id="formulaire" class="test" action="Ajout_DODM.php" method="post" onsubmit=" return selectall();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing:0; background-color:#a9e99d;">
				<tr>
					<td class="TitrePage">
					<?php
						echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
						if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
						else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
						echo "</a>";
						echo "&nbsp;&nbsp;&nbsp;";
						if($_SESSION["Langue"]=="FR"){echo "Déclarer un petit déplacement ponctuel";}else{echo "Declare a small one-time trip";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<?php 
		if($bEnregistrement==true){
			echo "<tr>";
			echo "<td colspan='5' align='center' bgcolor='#ff7777' style='font-weight:bold;'>";
			if($_SESSION["Langue"]=="FR"){
				echo "Petit déplacement ponctuel créé<br>";
			}
			else{
				echo "Small one-time displacement created<br>";
			}
			echo "</td>";
			echo "</tr>
				<tr>
					<td height='5'></td>
				</tr>";
		}
	?>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" align="center" width="80%" cellpadding="0" cellspacing="0">
						<tr><td height="4"></td></tr>
						<tr>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
							<td width="30%">
									<select name="Id_Prestation" id="Id_Prestation" onchange="Recharge_Responsables();" style='width:300px;'>
									<?php
										if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
											$requeteSite="SELECT Id, Libelle
												FROM new_competences_prestation
												WHERE Id_Plateforme IN 
													(
														SELECT Id_Plateforme 
														FROM new_competences_personne_poste_plateforme
														WHERE Id_Personne=".$_SESSION['Id_Personne']." 
														AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
													)
												AND Active=0
												ORDER BY Libelle ASC";
										}
										else{
											$requeteSite="SELECT Id, Libelle
												FROM new_competences_prestation
												WHERE Id IN 
													(SELECT Id_Prestation 
													FROM new_competences_personne_poste_prestation 
													WHERE Id_Personne=".$_SESSION["Id_Personne"]."
													AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
													)
												AND Active=0
												ORDER BY Libelle ASC";
											
										}
										$resultsite=mysqli_query($bdd,$requeteSite);
										while($rowsite=mysqli_fetch_array($resultsite))
										{
											echo "<option value='".$rowsite['Id']."'>";
											echo str_replace("'"," ",stripslashes($rowsite['Libelle']))."</option>\n";
										}
									?>
								</select>
							</td>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?></td>
							<td width="30%">
								<select name="Id_Pole" id="Id_Pole" onchange="Recharge_Personnel();">
									<?php
										if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
											$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
													FROM new_competences_pole
													LEFT JOIN new_competences_prestation
													ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
													WHERE Id_Plateforme IN 
													(
														SELECT Id_Plateforme 
														FROM new_competences_personne_poste_plateforme
														WHERE Id_Personne=".$_SESSION['Id_Personne']." 
														AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
													)
													AND Actif=0
													ORDER BY new_competences_pole.Libelle ASC";
										}
										else{
											$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
												FROM new_competences_pole
												LEFT JOIN new_competences_prestation
												ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
												WHERE new_competences_prestation.Id IN 
													(SELECT Id_Prestation 
													FROM new_competences_personne_poste_prestation 
													WHERE Id_Personne=".$_SESSION["Id_Personne"]."
													AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
													)
												AND Actif=0
												ORDER BY new_competences_pole.Libelle ASC";
										}
										echo $requetePole."\n";
										$resultPole=mysqli_query($bdd,$requetePole);
										$nbPole=mysqli_num_rows($resultPole);
										if($nbPole>0){
											$i=0;
											while($rowPole=mysqli_fetch_array($resultPole)){
												echo "<option value='".$rowPole['Id']."'>";
												echo str_replace("'"," ",$rowPole['Libelle'])."</option>\n";
												 echo "<script>Liste_Pole_Prestation[".$i."] = new Array(".$rowPole[0].",".$rowPole[1].",'".$rowPole[2]."');</script>";
												 $i+=1;
											}
										}
										else{
											echo "<option value='0'></option>";
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personnes :";}else{echo "People :";} ?></td>
							<td width="35%" valign="top">
								<select name="Id_Personne" id="Id_Personne" multiple size="15" onDblclick="ajouter();">
								<?php
								$rq="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
									rh_personne_mouvement.Id_Prestation, rh_personne_mouvement.Id_Pole
									FROM new_rh_etatcivil
									RIGHT JOIN rh_personne_mouvement 
									ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
									WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
									AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
									AND rh_personne_mouvement.EtatValidation=1 
									AND rh_personne_mouvement.Suppr=0
									
									UNION 
									
									SELECT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,0,0
									FROM new_rh_etatcivil
									WHERE Id NOT IN (
										SELECT DISTINCT Tab_RH.Id
										FROM new_rh_etatcivil AS Tab_RH
										RIGHT JOIN rh_personne_mouvement 
										ON Tab_RH.Id=rh_personne_mouvement.Id_Personne 
										WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
										AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
										AND rh_personne_mouvement.EtatValidation=1 
										AND rh_personne_mouvement.Suppr=0
									)
									AND Id IN (
										SELECT DISTINCT Id_Personne
										FROM rh_personne_contrat
										WHERE Suppr=0
										AND DateDebut<='".date('Y-m-d')."'
										AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
										AND TypeDocument IN ('Nouveau','Avenant')
									)
									ORDER BY Personne ASC";
								$resultpersonne=mysqli_query($bdd,$rq);
								$i=0;
								while($rowpersonne=mysqli_fetch_array($resultpersonne))
								{
									echo "<option value='".$rowpersonne['Id']."'>".str_replace("'"," ",$rowpersonne['Personne'])."</option>\n";
									echo "<script>Liste_Personne[".$i."] = new Array(".$rowpersonne['Id'].",'".str_replace("'"," ",$rowpersonne['Personne'])."','".$rowpersonne['Id_Prestation']."','".$rowpersonne['Id_Pole']."');</script>";
									$i+=1;
								}
								?>
								</select>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personnes sélectionnées (double-clic) :";}else{echo "Selected people (double-click) :";} ?></td>
							<td width="30%" valign="top">
								<select name="PersonneSelect[]" id="PersonneSelect" multiple size="15" onDblclick="effacer();"></select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?></td>
							<td width="30%">
								<select name="Id_Plateforme" id="Id_Plateforme" style="width:300px" onchange="Recharge_PrestationAccueil()">
								<option value="0"></option>
									<?php
										$requetePlat="SELECT Id, Libelle
											FROM new_competences_plateforme
											WHERE Id NOT IN (11,14)
											ORDER BY Libelle";
										$resultsPlat=mysqli_query($bdd,$requetePlat);
										while($rowPlat=mysqli_fetch_array($resultsPlat))
										{
											echo "<option value='".$rowPlat['Id']."'>";
											echo str_replace("'"," ",stripslashes($rowPlat['Libelle']))."</option>\n";
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation / pôle concerné <br>par le déplacement :";}else{echo "Site / pole <br>concerned by displacement :";} ?></td>
							<td width="30%" colspan="3">
									<select name="Id_PrestationPoleAccueil" id="Id_PrestationPoleAccueil" style="width:400px">
									<?php
										$requeteSite="SELECT DISTINCT Id, Libelle, '' AS LibellePole, 0 AS Id_Pole,Id_Plateforme
											FROM new_competences_prestation
											WHERE Active=0
											AND Id NOT IN (
												SELECT Id_Prestation
												FROM new_competences_pole    
												WHERE Actif=0
											)
											
											UNION 
											
											SELECT DISTINCT new_competences_pole.Id_Prestation, new_competences_prestation.Libelle, 
												new_competences_pole.Libelle AS LibellePole, new_competences_pole.Id AS Id_Pole,new_competences_prestation.Id_Plateforme
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
											echo "<option value='".$rowsite['Id']."_".$rowsite['Id_Pole']."'>";
											$pole="";
											if($rowsite['Id_Pole']>0){$pole=" - ".$rowsite['LibellePole'];}
											echo str_replace("'"," ",stripslashes($rowsite['Libelle'].$pole))."</option>\n";
											echo "<script>Liste_PrestaPoleAccueil[".$i."] = new Array(".$rowsite['Id'].",".$rowsite['Id_Pole'].",'".str_replace("'"," ",$rowsite['Libelle'])."','".str_replace("'"," ",$rowsite['LibellePole'])."',".$rowsite['Id_Plateforme'].");</script>";
											$i++;
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Objet du déplacement :";}else{echo "Object of the trip :";} ?> </td>
							<td width="30%" colspan="4">
								<input type="text" id='objectDeplacement' name='objectDeplacement' size="80" value="">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Lieu de la mission :";}else{echo "Location of the mission :";} ?> </td>
							<td width="30%">
								<input type="text" id='lieu' name='lieu' size="40" value="">
							</td>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Pays :";}else{echo "Country :";} ?> </td>
							<td width="30%">
								<input type="text" id='pays' name='pays' size="20" value="">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début :";}else{echo "Start date :";} ?></td>
							<td width="30%"><input type="date" id="dateDebut" name="dateDebut" size="10" value="" onchange="VerifDate();"></td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin :";}else{echo "End date :";} ?></td>
							<td width="30%"><input type="date" id="dateFin" name="dateFin" size="10" value="" onchange="VerifDate();"></td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Journée entière :";}else{echo "Whole day";} ?> </td>
							<td width="10%">
								<input type="radio" id='journeeEntiere' name='journeeEntiere' onclick="Affiche_Heure(1)" value="1" checked><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?>&nbsp;&nbsp;
								<input type="radio" id='journeeEntiere' name='journeeEntiere' onclick="Affiche_Heure(0)" value="0" ><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?>&nbsp;&nbsp;
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr class="heures" style="display:none;">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de début";}else{echo "Start time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small" style="text-align:center;" name="heureDebut" id="heureDebut" size="10" type="text" value= "">
								</div>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de fin";}else{echo "End time";} ?> : </td>
							<td width="10%">
								<div class="input-group bootstrap-timepicker timepicker">
									<input class="form-control input-small" style="text-align:center;" name="heureFin" id="heureFin" size="10" type="text" value= "">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="15%" colspan="3" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Besoins de réservation :";}else{echo "Booking needs :";} ?></td>
						</tr>
						<tr>
							<td colspan="6">
								<div id='Div_Besoin' style='height:350px;width:80%;overflow:auto;background-color:#ddf6d8'>
									<?php
									echo "<table width='100%'>";
									if($_SESSION["Langue"]=="FR"){$req="SELECT Id, Libelle FROM rh_typebesoin WHERE Suppr=0 ORDER BY Libelle";}
									else{$req="SELECT Id, LibelleEN AS Libelle FROM rh_typebesoin WHERE Suppr=0 ORDER BY LibelleEN";}
									$resultB=mysqli_query($bdd,$req);
									$nbResultaB=mysqli_num_rows($resultB);
									if ($nbResultaB>0){
										while($rowB=mysqli_fetch_array($resultB)){
											echo "<tr><td width='25%'><input type='checkbox' class='besoins' name='CheckBesoin_".$rowB['Id']."' id='CheckBesoin_".$rowB['Id']."' >".$rowB['Libelle']." : </td><td width='75%'>
												<table>";
											if($rowB['Id']==2){
												//Réservation hotel
												if($_SESSION['Langue']=="FR"){echo "<tr><td>Nombre de nuit : ";}else{echo "<tr><td>Number of nights : ";}
												echo "<input onKeyUp='nombre(this)' size='5' type='text' name='besoinNbNuitHotel_' id='besoinNbNuitHotel_'></td>";
												if($_SESSION['Langue']=="FR"){echo "<td>Lieu : ";}else{echo "<td>Location : ";}
												echo "<input type='text' size='30' name='besoinLieuHotel_' id='besoinLieuHotel_'></td></tr>";
												
												if($_SESSION['Langue']=="FR"){echo "<tr><td>Date arrivée : ";}else{echo "<tr><td>Arrival date : ";}
												echo "<input type='date' name='besoinDateArriveeHotel_' id='besoinDateArriveeHotel_'></td>";
												if($_SESSION['Langue']=="FR"){echo "<td>Date départ : ";}else{echo "<td>Departure date : ";}
												echo "<input type='date' name='besoinDateDepartHotel_' id='besoinDateDepartHotel_'></td></tr>";
											}
											elseif($rowB['Id']==3){
												//Réservation train/avion
												if($_SESSION['Langue']=="FR"){
													echo "<tr><td>Train/Avion : <select name='besoinTrainAvion_' id='besoinTrainAvion_' ><option value='Avion'>Avion</option><option value='Train'>Train</option></select></td></td></tr>";
												}
												else{
													echo "<tr><td>Train/Plane : <select name='besoinTrainAvion_' id='besoinTrainAvion_' ><option value='Avion'>Plane</option><option value='Train'>Train</option></select></td></td></tr>";
												}
												if($_SESSION['Langue']=="FR"){echo "<tr><td class='Libelle'>ALLER :</td></tr>";}else{echo "<tr><td class='Libelle'>BACK :</td></tr>";}
												
												if($_SESSION['Langue']=="FR"){echo "<tr><td>Lieu départ : ";}else{echo "<tr><td>Departure location : ";}
												echo "<input type='text' size='30' name='besoinLieuDepartAller_' id='besoinLieuDepartAller_'></td>";
												if($_SESSION['Langue']=="FR"){echo "<td>Lieu arrivée : ";}else{echo "<td>Arrival place : ";}
												echo "<input type='text' size='30' name='besoinLieuArriveeAller_' id='besoinLieuArriveeAller_'></td></tr>";
												
												if($_SESSION['Langue']=="FR"){echo "<tr><td>Date départ : ";}else{echo "<tr><td>Departure date : ";}
												echo "<input type='date' name='besoinDateDepartAller_' id='besoinDateDepartAller_'></td></tr>";
												
												if($_SESSION['Langue']=="FR"){echo "<tr><td>Heure départ : ";}else{echo "<td>Departure hour : ";}
												echo "<div class='input-group bootstrap-timepicker timepicker' style='display: inline'>";
												echo "<input class='form-control input-small' style='text-align:center;' type='text' size='10' name='besoinHeureDepartAller_' id='besoinHeureDepartAller_'>";
												echo "</div></td>";
												if($_SESSION['Langue']=="FR"){echo "<td>Heure arrivée : ";}else{echo "<td>Arrival hour : ";}
												echo "<div class='input-group bootstrap-timepicker timepicker' style='display: inline'>";
												echo "<input class='form-control input-small' style='text-align:center;' type='text' size='10' name='besoinHeureArriveeAller_' id='besoinHeureArriveeAller_'>";
												echo "</div></td></tr>";
												
												
												if($_SESSION['Langue']=="FR"){echo "<tr><td class='Libelle'>RETOUR :</td></tr>";}else{echo "<tr><td class='Libelle'>FORTH :</td></tr>";}
												
													if($_SESSION['Langue']=="FR"){echo "<tr><td>Lieu départ : ";}else{echo "<tr><td>Departure location : ";}
												echo "<input type='text' size='30' name='besoinLieuDepartRetour_' id='besoinLieuDepartRetour_'></td>";
												if($_SESSION['Langue']=="FR"){echo "<td>Lieu arrivée : ";}else{echo "<td>Arrival place : ";}
												echo "<input type='text' size='30' name='besoinLieuArriveeRetour_' id='besoinLieuArriveeRetour_'></td></tr>";
												
												if($_SESSION['Langue']=="FR"){echo "<tr><td>Date départ : ";}else{echo "<tr><td>Departure date : ";}
												echo "<input type='date' name='besoinDateDepartRetour_' id='besoinDateDepartRetour_'></td></tr>";
												
												if($_SESSION['Langue']=="FR"){echo "<tr><td>Heure départ : ";}else{echo "<td>Departure hour : ";}
												echo "<div class='input-group bootstrap-timepicker timepicker' style='display: inline'>";
												echo "<input class='form-control input-small' style='text-align:center;' type='text' size='10' name='besoinHeureDepartRetour_' id='besoinHeureDepartRetour_'>";
												echo "</div></td>";
												if($_SESSION['Langue']=="FR"){echo "<td>Heure arrivée : ";}else{echo "<td>Arrival hour : ";}
												echo "<div class='input-group bootstrap-timepicker timepicker' style='display: inline'>";
												echo "<input class='form-control input-small' style='text-align:center;' type='text' size='10' name='besoinHeureArriveeRetour_' id='besoinHeureArriveeRetour_'>";
												echo "</div></td></tr>";
												
											}
											elseif($rowB['Id']==5){
												//Réservation véhicule AAA
												if($_SESSION['Langue']=="FR"){echo "<tr><td>Véhicule : ";}else{echo "<tr><td>Vehicle : ";}
												echo "<input type='text' size='30' name='besoinVehiculeAAA_' id='besoinVehiculeAAA_'></td>";
												
												if($_SESSION['Langue']=="FR"){echo "<tr><td>Date début : ";}else{echo "<tr><td>Start date : ";}
												echo "<input type='date' name='besoinDateDebutLocationAAA_' id='besoinDateDebutLocationAAA_'></td>";
												if($_SESSION['Langue']=="FR"){echo "<td>Date fin : ";}else{echo "<td>End date : ";}
												echo "<input type='date' name='besoinDateFinLocationAAA_' id='besoinDateFinLocationAAA_'></td></tr>";
												
												if($_SESSION['Langue']=="FR"){echo "<tr><td>Heure début : ";}else{echo "<td>Start hour : ";}
												echo "<div class='input-group bootstrap-timepicker timepicker' style='display: inline'>";
												echo "<input class='form-control input-small' style='text-align:center;' type='text' size='10' name='besoinHeureDebutLocationAAA_' id='besoinHeureDebutLocationAAA_'>";
												echo "</div></td>";
												if($_SESSION['Langue']=="FR"){echo "<td>Heure fin : ";}else{echo "<td>End hour : ";}
												echo "<div class='input-group bootstrap-timepicker timepicker' style='display: inline'>";
												echo "<input class='form-control input-small' style='text-align:center;' type='text' size='10' name='besoinHeureFinLocationAAA_' id='besoinHeureFinLocationAAA_'>";
												echo "</div></td></tr>";
											}
											elseif($rowB['Id']==4){
												//Réservation voiture de location
												
												if($_SESSION['Langue']=="FR"){echo "<tr><td>Nom du conducteur : ";}else{echo "<tr><td>Driver's name : ";}
												echo "<input type='text' size='30' name='besoinNomConducteur_' id='besoinNomConducteur_'></td>";
												
												if($_SESSION['Langue']=="FR"){echo "<tr><td class='Libelle'>MISE A DISPOSITION :</td></tr>";}else{echo "<tr><td class='Libelle'>PROVISION :</td></tr>";}
												
												if($_SESSION['Langue']=="FR"){echo "<tr><td>Lieu : ";}else{echo "<tr><td>Location : ";}
												echo "<input type='text' size='30' name='besoinLieuDepartLocationVoiture_' id='besoinLieuDepartLocationVoiture_'></td></tr>";
												
												if($_SESSION['Langue']=="FR"){echo "<tr><td>Date : ";}else{echo "<tr><td>Date : ";}
												echo "<input type='date' name='besoinDateDepartLocationVoiture_' id='besoinDateDepartLocationVoiture_'></td>";
												if($_SESSION['Langue']=="FR"){echo "<td>Heure : ";}else{echo "<td>Hour : ";}
												echo "<div class='input-group bootstrap-timepicker timepicker' style='display: inline'>";
												echo "<input class='form-control input-small' style='text-align:center;' type='text' size='10' name='besoinHeureDepartLocationVoiture_' id='besoinHeureDepartLocationVoiture_'>";
												echo "</div></td></tr>";
												
												
												if($_SESSION['Langue']=="FR"){echo "<tr><td class='Libelle'>RESTITUTION :</td></tr>";}else{echo "<tr><td class='Libelle'>RETURN :</td></tr>";}
												
													if($_SESSION['Langue']=="FR"){echo "<tr><td>Lieu : ";}else{echo "<tr><td>Location : ";}
												echo "<input type='text' size='30' name='besoinLieuRetourLocationVoiture_' id='besoinLieuRetourLocationVoiture_'></td></tr>";
												
												if($_SESSION['Langue']=="FR"){echo "<tr><td>Date : ";}else{echo "<tr><td>Date : ";}
												echo "<input type='date' name='besoinDateRetourLocationVoiture_' id='besoinDateRetourLocationVoiture_'></td>";
												if($_SESSION['Langue']=="FR"){echo "<td>Heure : ";}else{echo "<td>Hour : ";}
												echo "<div class='input-group bootstrap-timepicker timepicker' style='display: inline'>";
												echo "<input class='form-control input-small' style='text-align:center;' type='text' size='10' name='besoinHeureRetourLocationVoiture_' id='besoinHeureRetourLocationVoiture_'>";
												echo "</div></td></tr>";
												
											}
											echo "<tr><td colspan='4'><textarea name='besoin_".$rowB['Id']."' id='besoin_".$rowB['Id']."' cols='90' rows='2' noresize></textarea></td></tr></table></td></tr>";
											
											echo "<tr><td width='25%' colspan='2' style='border-bottom:1px dotted #37a223;'></td>";
										}
									}
									echo "</table>";
									?>
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Frais réels ou calendaires :";}else{echo "Actual or calendar fees :";} ?> </td>
							<td width="30%">
								<input type="radio" id='typeDeFrais' name='typeDeFrais' onclick="AfficheDemandeAvance()" value="1" checked><?php if($_SESSION["Langue"]=="FR"){echo "Réels";}else{echo "Actual";} ?> &nbsp;&nbsp;
								<input type="radio" id='typeDeFrais' name='typeDeFrais' onclick="AfficheDemandeAvance()" value="0" ><?php if($_SESSION["Langue"]=="FR"){echo "Calendaires";}else{echo "Calendar";} ?> &nbsp;&nbsp;
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr id="trDemandeAvance">
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Souhaitez-vous faire une demande d'avance sur frais :";}else{echo "Would you like to apply for an advance on fees :";} ?> </td>
							<td width="30%">
								<input type="radio" id='demandeAvance' name='demandeAvance' onclick="AfficheAvance()" value="1"	><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?> &nbsp;&nbsp;
								<input type="radio" id='demandeAvance' name='demandeAvance' onclick="AfficheAvance()" value="0" checked><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?> &nbsp;&nbsp;
							</td>
						</tr>
						<tr id="trAvance1" style="display:none;"><td height="4"></td></tr>
						<tr id="trAvance2" style="display:none;">
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Avance ponctuelle ou permanente :";}else{echo "One-time or permanent advance :";} ?> </td>
							<td width="30%">
								<input type="radio" id='avance' name='avance' onclick="AffichePeriode()" value="1" checked><?php if($_SESSION["Langue"]=="FR"){echo "Ponctuelle";}else{echo "One-time";} ?> &nbsp;&nbsp;
								<input type="radio" id='avance' name='avance' onclick="AffichePeriode()" value="0" ><?php if($_SESSION["Langue"]=="FR"){echo "Permanente";}else{echo "Permanent";} ?> &nbsp;&nbsp;
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr id="trAvance3" style="display:none;">
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Montant :";}else{echo "Amount :";} ?> </td>
							<td width="30%">
								<input onKeyUp="nombre(this)" type="text" id='montant' name='montant' size="8" value="">
							</td>
							<td width="15%" id="labelPeriode1" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date :";}else{echo "Date :";} ?> </td>
							<td width="30%" id="labelPeriode2">
								<input type="date" id='periode' name='periode' value="">
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="6" align="center">
								<input class="Bouton" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Ajouter";}else{echo "Add";} ?>"/>
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
	echo "<script>Recharge_Responsables();</script>";
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
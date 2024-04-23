<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="DemandeHS.js"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script language="javascript">
		function VerifChamps()
		{
			if(document.getElementById('statut').value==0){
				if(document.getElementById('Langue').value=="FR"){
					if(document.getElementById('commentaire').value==""){alert("Veuillez ajouter un commentaire.");return false;}
				}
				else{
					if(document.getElementById('commentaire').value==""){alert("Please add a comment.");return false;}

				}
			}
			return true;
		}
		function AfficherRefus(){
			if(document.getElementById('statut').value==0){
				document.getElementById('trRaison').style.display="";
				document.getElementById('trCommentaire').style.display="";
			}
			else{
				document.getElementById('trRaison').style.display="none";
				document.getElementById('trCommentaire').style.display="none";
			}
		}
		function FermerEtRecharger(Menu,TDB,OngletTDB)
		{
			window.opener.location="Liste_HeureSupp.php?Menu="+Menu+"&TDB="+TDB+"&OngletTDB="+OngletTDB;
			window.close();
		}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require_once("Fonctions_Planning.php");
require("../Fonctions.php");
Ecrire_Code_JS_Init_Date();

$bEnregistrement=false;
if($_POST){
	if(isset($_POST['Valider']))
	{
		if($_POST['statut']==1){
			for($j=$_POST['step'];$j<=4;$j++){
				if($j==2){
					if(DroitsPrestationPole(array($IdPosteChefEquipe),$_POST['Id_Prestation'],$_POST['Id_Pole'])){
						$requeteUpdate="UPDATE rh_personne_hs SET 
								Id_Responsable2=".$_SESSION['Id_Personne'].",
								Date2='".date('Y-m-d')."',
								Etat2=1
								WHERE Id=".$_POST['Id']." ";
						$resultat=mysqli_query($bdd,$requeteUpdate);
					}
					else{$j=5;}
				}
				if($j==3){
					if(DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$_POST['Id_Prestation'],$_POST['Id_Pole'])){
						$requeteUpdate="UPDATE rh_personne_hs SET 
								Id_Responsable3=".$_SESSION['Id_Personne'].",
								Date3='".date('Y-m-d')."',
								Etat3=1
								WHERE Id=".$_POST['Id']." ";
						$resultat=mysqli_query($bdd,$requeteUpdate);
					}
					else{$j=5;}
				}
				if($j==4){
					if(DroitsPrestationPole(array($IdPosteCoordinateurProjet),$_POST['Id_Prestation'],$_POST['Id_Pole'])
					|| (NombreHeuresJournee($_POST['Id_Personne'],$_POST['laDateHS'])<=10
					&& NombreHeuresSemaine($_POST['Id_Personne'],$_POST['laDateHS'])<=48)	
					){
						$Id_Responsable4=0;
						if(DroitsPrestationPole(array($IdPosteCoordinateurProjet),$row['Id_Prestation'],$row['Id_Pole'])){
							$Id_Responsable4=$_SESSION['Id_Personne'];
						}
						$requeteUpdate="UPDATE rh_personne_hs SET 
								Id_Responsable4=".$Id_Responsable4.",
								Date4='".date('Y-m-d')."',
								Etat4=1
								WHERE Id=".$_POST['Id']." ";
						$resultat=mysqli_query($bdd,$requeteUpdate);
						if(DateAvant25DuMois($_POST['laDateHS'],date('Y-m-d'))==1){
							$requeteUpdate="UPDATE rh_personne_hs SET 
									DateRH=DateHS,
									DatePriseEnCompteRH='".date('Y-m-d')."',
									Avant25Mois=1
									WHERE Id=".$_POST['Id']." ";
							$resultat=mysqli_query($bdd,$requeteUpdate);
						}
					}
					else{$j=5;}
				}
			}
			$resultat=mysqli_query($bdd,$requeteUpdate);
		}
		else{
			$requeteUpdate="UPDATE rh_personne_hs SET 
				Id_Responsable".$_POST['Step']."=".$_SESSION['Id_Personne'].",
				Date".$_POST['Step']."='".date('Y-m-d')."',
				Etat".$_POST['Step']."=-1,
				Id_RaisonRefusN".$_POST['Step']."=".$_POST['raisonRefus'].",
				Commentaire".$_POST['Step']."='".addslashes($_POST['commentaire'])."' 
				WHERE Id=".$_POST['Id']." ";

		$resultat=mysqli_query($bdd,$requeteUpdate);
		}
	}
	elseif(isset($_POST['ModifRH']))
	{
		$requeteUpdate="UPDATE rh_personne_hs SET 
				Id_RH=".$_SESSION['Id_Personne'].",
				DatePriseEnCompteRH='".date('Y-m-d')."',
				DateRH='".TrsfDate_($_POST['dateRH'])."'
				WHERE Id=".$_POST['Id']." ";
		$resultat=mysqli_query($bdd,$requeteUpdate);
	}
	echo "<script>FermerEtRecharger('".$_POST['Menu']."',".$_POST['TDB'].",'".$_POST['OngletTDB']."');</script>";
}
else{
	if($_GET['Mode']=="S"){
		$requeteUpdate="UPDATE rh_personne_hs SET 
				Suppr=1,
				Id_Suppr=".$_SESSION['Id_Personne'].",
				DateSuppr='".date('Y-m-d')."'
				WHERE Id=".$_GET['Id']." ";
		$resultat=mysqli_query($bdd,$requeteUpdate);
		echo "<script>FermerEtRecharger('".$_GET['Menu']."',".$_GET['TDB'].",'".$_GET['OngletTDB']."');</script>";
	}
}
$Menu=$_GET['Menu'];

$requete="SELECT rh_personne_hs.Id, rh_personne_hs.Nb_Heures_Jour,rh_personne_hs.Nb_Heures_Nuit,DateHS,rh_personne_hs.Etat2,rh_personne_hs.Etat3,
	rh_personne_hs.Etat4,DatePriseEnCompteRH,rh_personne_hs.DateRH,rh_personne_hs.Date1,rh_personne_hs.Id_Prestation,rh_personne_hs.Id_Pole,Id_Personne,
	Date2,Date3,Date4,
	(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN2) AS RaisonRefus2,Commentaire2,Motif,
	(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN3) AS RaisonRefus3,Commentaire3,
	(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefusN4) AS RaisonRefus4,Commentaire4,
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_hs.Id_Responsable1) AS ResponsableN1, 
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_hs.Id_Responsable2) AS ResponsableN2, 
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_hs.Id_Responsable3) AS ResponsableN3,
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_hs.Id_Responsable4) AS ResponsableN4,	
	(SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_hs.Id_Prestation) AS Prestation, 
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_hs.Id_Personne) AS Personne,  
	(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=rh_personne_hs.Id_Pole) AS Pole 
	FROM rh_personne_hs 
	WHERE Id=".$_GET['Id'] ;
$result=mysqli_query($bdd,$requete);
$row=mysqli_fetch_array($result);

$Etat="";
$CouleurEtat="#ffffff";
$NumRefus=2;
$EstRefuse=0;
if($row['Etat4']==1 && $row['DatePriseEnCompteRH']>'0001-01-01'){
	if($_SESSION["Langue"]=="FR"){
		$Etat="Validée et pris en compte sur la paie";}
	else{
		$Etat="Validated and taken into account on payroll";}
	$CouleurEtat="#7ffa1e";
}
elseif($row['Etat4']==-1 || $row['Etat3']==-1 || $row['Etat2']==-1){
	if($_SESSION["Langue"]=="FR"){
		$Etat="Refusée";}
	else{
		$Etat="Refused";}
	$CouleurEtat="#ff3d3d";
	if($row['Etat4']==-1){$NumRefus=4;}
	elseif($row['Etat3']==-1){$NumRefus=3;}
	elseif($row['Etat2']==-1){$NumRefus=2;}
	$EstRefuse=1;
}
elseif($row['Etat4']==1 && $row['DatePriseEnCompteRH']<='0001-01-01'){
	if($_SESSION["Langue"]=="FR"){
		$Etat="Transmis aux RH";}
	else{
		$Etat="Submitted to HR";}
	$CouleurEtat="#449ef0";
}
elseif($row['Etat4']==0 && $row['Etat3']<>-1 && $row['Etat2']<>-1){
	$n=1;
	if($row['Etat2']==0){$n=1;}
	elseif($row['Etat3']==0){$n=2;}
	
	if($_SESSION["Langue"]=="FR"){
		$Etat="En attente de pré validation (".$n."/2)";}
	else{
		$Etat="Waiting for pre-validation (".$n."/ 2)";}
	$CouleurEtat="#fab342";
}

$step=5;
if(($row['Etat2']==0 && DroitsPrestationPole(array($IdPosteChefEquipe),$row['Id_Prestation'],$row['Id_Pole']))
|| ($row['Etat2']==1 && $row['Etat3']==0 && DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$row['Id_Prestation'],$row['Id_Pole']))
|| ($row['Etat2']==1 && $row['Etat3']==1 && $row['Etat4']==0 && DroitsPrestationPole(array($IdPosteCoordinateurProjet),$row['Id_Prestation'],$row['Id_Pole']))
){
	if($row['Etat2']==0){$step=2;}
	elseif($row['Etat3']==0){$step=3;}
	elseif($row['Etat4']==0){$step=4;}
}

?>

<form id="formulaire" class="test" action="Modif_HS.php" method="post" onsubmit=" return VerifChamps();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Step" id="Step" value="<?php echo $step; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $_GET['Menu']; ?>" />
	<input type="hidden" name="Id_Prestation" id="Id_Prestation" value="<?php echo $row['Id_Prestation']; ?>" />
	<input type="hidden" name="Id_Pole" id="Id_Pole" value="<?php echo $row['Id_Pole']; ?>" />
	<input type="hidden" name="Id_Personne" id="Id_Personne" value="<?php echo $row['Id_Personne']; ?>" />
	<input type="hidden" name="laDateHS" id="laDateHS" value="<?php echo $row['DateHS']; ?>" />
	<input type="hidden" name="ValiderRefuser" id="ValiderRefuser" value="" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $_GET['TDB']; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $_GET['OngletTDB']; ?>" />
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° demande :";}else{echo "Request number :";} ?></td>
							<td width="15%" style="color:#3e65fa;">
								<?php echo $row['Id']; ?>
							</td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "Person :";} ?></td>
							<td width="15%">
								<?php echo $row['Personne']; ?>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
							<td width="20%">
								<?php echo stripslashes($row['Prestation']); ?>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?></td>
							<td width="15%">
								<?php echo stripslashes($row['Pole']); ?>
							</td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Date heure supp :";}else{echo "Date extra hour :";} ?></td>
							<td width="15%">
								<?php echo AfficheDateJJ_MM_AAAA($row['DateHS']); ?>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nb h. jour :";}else{echo "Nb h. day :";} ?></td>
							<td width="20%">
								<?php echo $row['Nb_Heures_Jour']; ?>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nb h. nuit :";}else{echo "Nb h. night :";} ?></td>
							<td width="15%">
								<?php echo $row['Nb_Heures_Nuit']; ?>
							</td>
						</tr>
						<tr><td height="5"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Motif :";}else{echo "Reason :";} ?></td>
							<td colspan="6">
								<textarea name="motif" id="motif" cols="100" rows="4" style="resize:none;" readonly="readonly"><?php echo stripslashes($row['Motif']); ?></textarea>
							</td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Etat :";}else{echo "State :";} ?></td>
							<td width="15%" bgcolor="<?php echo $CouleurEtat;?>">
								<?php echo $Etat; ?>
							</td>
							<td width="10%" class="Libelle">
							<?php
								if($row['Etat4']==1){
									if($_SESSION["Langue"]=="FR"){echo "Date de prise en compte (paie) :";}else{echo "Date taken into account (payroll) :";}
								}
							?>
							</td>
							<td>
							<?php
								if($row['Etat4']==1){
									if($Menu==4){
										echo "<input type='date' name='dateRH' id='dateRH' value='".AfficheDateFR($row['DateRH'])."' />";
									}
									else{
										echo "<input type='text' name='dateRH' id='dateRH' size='' value='".AfficheDateJJ_MM_AAAA($row['DateRH'])."' readonly='readonly' />";
									}
								}
							?>
							</td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Validateur :";}else{echo "Validator :";} ?></td>
						</tr>
						<?php
							if($row['Etat2']<>0){
						?>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td width="10%" class="Libelle"></td>
							<td colspan="3">
								<?php if($_SESSION["Langue"]=="FR"){echo "Responsable N+1 : ";}else{echo "N + 1 manager : ";} echo $row['ResponsableN2']." (".AfficheDateJJ_MM_AAAA($row['Date2']).")"; ?>
							</td>
						</tr>
						<?php
							if($row['Etat3']<>0){
						?>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td width="10%" class="Libelle"></td>
							<td colspan="3">
								<?php if($_SESSION["Langue"]=="FR"){echo "Responsable N+2 : ";}else{echo "N + 2 manager : ";} echo $row['ResponsableN3']." (".AfficheDateJJ_MM_AAAA($row['Date3']).")"; ?>
							</td>
						</tr>
						<?php
							}
							else{
								$requetePersonnePoste="SELECT DISTINCT new_competences_personne_poste_prestation.Backup, 
															CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne 
															FROM new_competences_personne_poste_prestation, new_rh_etatcivil 
															WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
															AND new_competences_personne_poste_prestation.Id_Poste = 2
															AND new_competences_personne_poste_prestation.Id_Prestation=".$row['Id_Prestation']."
															AND new_competences_personne_poste_prestation.Id_Pole=".$row['Id_Pole']."
															ORDER BY new_competences_personne_poste_prestation.Backup ASC";
									$resultPersonnePoste=mysqli_query($bdd,$requetePersonnePoste);
									$NbPersonne=mysqli_num_rows($resultPersonnePoste);
									$personne="";
									if($NbPersonne>0){
										while($rowPersonnePoste=mysqli_fetch_array($resultPersonnePoste)){
											if($personne<>""){$personne.=" | ";}
											$personne.=$rowPersonnePoste['Personne'];
										}
									}
							?>
								<tr>
									<td width="10%" class="Libelle"></td>
									<td width="15%" colspan="6">
										<?php if($_SESSION["Langue"]=="FR"){echo "Responsable N+2 : ";}else{echo "N + 2 manager : ";} echo $personne; ?>
									</td>
								</tr>
							<?php
							}
							}
						?>
						<tr>
							<td height="5"></td>
						</tr>
						<?php
							if($step==5){
								if($EstRefuse==1){
						?>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Raison du refus :";}else{echo "Reason for refusal :";} ?></td>
							<td width="20%">
								<?php echo stripslashes($row['RaisonRefus'.$NumRefus]); ?>
							</td>
							<tr><td height="5"></td></tr>
							<tr>
								<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire :";}else{echo "Comment :";} ?></td>
								<td colspan="6">
									<textarea name="commentaire" id="commentaire" cols="100" rows="4" style="resize:none;" readonly="readonly"><?php echo stripslashes($row['Commentaire'.$NumRefus]); ?></textarea>
								</td>
							</tr>
							<tr>
								<td height="5"></td>
							</tr>
						<?php	
								}
							}
							else{
						?>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Statut :";}else{echo "Status :";} ?></td>
							<td width="15%">
								<select name="statut" id="statut" onchange="AfficherRefus()">
									<option value="1" selected><?php if($_SESSION["Langue"]=="FR"){echo "Valider";}else{echo "Validate";} ?></option>
									<option value="0"><?php if($_SESSION["Langue"]=="FR"){echo "Refuser";}else{echo "Refuse";} ?></option>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr style="display:none;" id="trRaison">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Raison du refus :";}else{echo "Reason for refusal :";} ?></td>
							<td width="15%">
									<select name="raisonRefus" id="raisonRefus">
									<option value="0"></option>
									<?php
										
										$requete="SELECT Id, Libelle
											FROM rh_raisonrefus
											WHERE
												Suppr=0
											AND Type='HeureSupplementaire'
											AND Id_Plateforme = (
												SELECT Id_Plateforme 
												FROM rh_personne_hs 
												LEFT JOIN new_competences_prestation 
												ON rh_personne_hs.Id_Prestation=new_competences_prestation.Id
												WHERE rh_personne_hs.Id=".$_GET['Id']." LIMIT 1)
											ORDER BY Libelle ASC";
										$result=mysqli_query($bdd,$requete);
										while($rowR=mysqli_fetch_array($result))
										{
											echo "<option value='".$rowR['Id']."'>";
											echo str_replace("'"," ",stripslashes($rowR['Libelle']))."</option>\n";
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr style="display:none;" id="trCommentaire">
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Commentaire :";}else{echo "Comment :";} ?></td>
							<td colspan="6">
								<textarea name="commentaire" id="commentaire" cols="100" rows="4" style="resize:none;"></textarea>
							</td>
						</tr>
						<?php } 
							if($step<>5){
						?>
						<tr>
							<td colspan="6" align="center">
								<input class="Bouton" type="submit" name="Valider" onclick="document.getElementById('ValiderRefuser').value='V';" value="<?php if($_SESSION["Langue"]=="FR"){echo "Enregistrer";}else{echo "Save";} ?>"/>&nbsp;&nbsp;&nbsp;&nbsp
							</td>
						</tr>
						<?php 
							} 
							elseif($row['Etat4']==1 && $Menu==4){
						?>
						<tr>
							<td colspan="6" align="center">
								<input class="Bouton" type="submit" name="ModifRH" onclick="if(document.getElementById('dateRH').value==''){alert('<?php if($_SESSION["Langue"]=="FR"){echo "Veuillez renseigner la date de prise en compte (paie)";}else{echo "Please enter the date of consideration (pay)";} ?>');return false;}" value="<?php if($_SESSION["Langue"]=="FR"){echo "Valider";}else{echo "Validate";} ?>"/>&nbsp;&nbsp;&nbsp;&nbsp
							</td>
						</tr>
						<?php
							}
						?>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td>
			<table class="GeneralInfo" align="center" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td height="10"></td>
				</tr>
			<?php 
			

			$EnAttente="#ffbf03";
			$Automatique="#3d9538";
			$Validee="#6beb47";
			$Refusee="#ff5353";
			$Gris="#dddddd";
			$AbsenceInjustifies="#ff0303";
			$TransmisRH="#449ef0";
			
	
			$Debut=date("Y-m-1",strtotime($row['DateHS']." -1 month"));
			$Fin=date("Y-m-1",strtotime($row['DateHS']." +2 month"));

			$tmpDate=date("Y-m-1",strtotime($row['DateHS']." -1 month"));


			//Liste des congés
			$reqConges="SELECT rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,
						rh_personne_demandeabsence.EtatN1,rh_personne_demandeabsence.EtatN2,rh_personne_demandeabsence.EtatRH,
						(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
						(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef
						FROM rh_absence 
						LEFT JOIN rh_personne_demandeabsence 
						ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
						WHERE rh_personne_demandeabsence.Id_Personne=".$row['Id_Personne']." 
						AND rh_absence.DateFin>='".$Debut."' 
						AND rh_absence.DateDebut<='".$Fin."' 
						AND rh_personne_demandeabsence.Suppr=0 
						AND rh_absence.Suppr=0 
						AND rh_personne_demandeabsence.Annulation=0 
						AND rh_personne_demandeabsence.Conge=1 
						ORDER BY rh_absence.Id DESC, rh_absence.Id_Personne_DA DESC ";
			$resultConges=mysqli_query($bdd,$reqConges);
			$nbConges=mysqli_num_rows($resultConges);

			//Liste des absences
			$reqAbs="SELECT rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,Id_TypeAbsenceDefinitif,Id_TypeAbsenceInitial,
						(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceInitial) AS TypeAbsenceIni,
						(SELECT rh_typeabsence.CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=rh_absence.Id_TypeAbsenceDefinitif) AS TypeAbsenceDef
						FROM rh_absence 
						LEFT JOIN rh_personne_demandeabsence 
						ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
						WHERE rh_personne_demandeabsence.Id_Personne=".$row['Id_Personne']." 
						AND rh_absence.DateFin>='".$Debut."' 
						AND rh_absence.DateDebut<='".$Fin."' 
						AND rh_personne_demandeabsence.Suppr=0 
						AND rh_absence.Suppr=0  
						AND rh_personne_demandeabsence.Conge=0 
						ORDER BY rh_absence.Id DESC, rh_absence.Id_Personne_DA DESC ";
			$resultAbs=mysqli_query($bdd,$reqAbs);
			$nbAbs=mysqli_num_rows($resultAbs);

			//Liste des heures supplémentaires
			$req="SELECT rh_personne_hs.Id, rh_personne_hs.Nb_Heures_Jour,rh_personne_hs.Nb_Heures_Nuit,
						IF(DateRH>'0001-01-01',DateRH,DateHS) AS DateHS,
						IF(
							rh_personne_hs.Etat4=0 AND rh_personne_hs.Etat3<>-1 AND rh_personne_hs.Etat2<>-1,
							1,
							IF(
								rh_personne_hs.DatePriseEnCompteRH<='0001-01-01' AND rh_personne_hs.Etat4=1 AND rh_personne_hs.Etat3=1 AND rh_personne_hs.Etat2=1,
								2,
								IF(
									rh_personne_hs.Etat4=1 AND rh_personne_hs.DatePriseEnCompteRH>'0001-01-01',
									3,
									IF(
										rh_personne_hs.Etat4=-1 OR rh_personne_hs.Etat3=-1 OR rh_personne_hs.Etat2=-1,
										4,
										5
									)
								)
							)
						)
						AS Etat
					FROM rh_personne_hs
					WHERE Suppr=0 
					AND Id_Personne=".$row['Id_Personne']." 
					AND IF(DateRH>'0001-01-01',DateRH,DateHS)>='".$Debut."' 
					AND IF(DateRH>'0001-01-01',DateRH,DateHS)<='".$Fin."' 
					";
			$resultHS=mysqli_query($bdd,$req);
			$nb2HS=mysqli_num_rows($resultHS);
								
			//Liste des astreintes
			$req="SELECT IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte) AS DateAstreinte,
					IF(
						rh_personne_rapportastreinte.EtatN2=0 AND rh_personne_rapportastreinte.EtatN1<>-1,
						1,
						IF(
							rh_personne_rapportastreinte.DateValidationRH<='0001-01-01' AND rh_personne_rapportastreinte.EtatN2=1 AND rh_personne_rapportastreinte.EtatN1=1,
							2,
							IF(
								rh_personne_rapportastreinte.EtatN2=1 AND rh_personne_rapportastreinte.DateValidationRH>'0001-01-01',
								3,
								IF(
									rh_personne_rapportastreinte.EtatN2=-1 OR rh_personne_rapportastreinte.EtatN1=-1,
									4,
									5
								)
							)
						)
					) AS Etat,
				TIMEDIFF(HeureFin1,HeureDebut1) AS DiffHeures1,
				TIMEDIFF(HeureFin2,HeureDebut2) AS DiffHeures2,
				TIMEDIFF(HeureFin3,HeureDebut3) AS DiffHeures3,Montant,Intervention
				FROM rh_personne_rapportastreinte
				WHERE rh_personne_rapportastreinte.Suppr=0
				AND rh_personne_rapportastreinte.Id_Personne=".$row['Id_Personne']."
				AND DateAstreinte>='".$Debut."' 
				AND DateAstreinte<='".$Fin."' 
				";
				
			$resultAst=mysqli_query($bdd,$req);
			$nbAst=mysqli_num_rows($resultAst);

			//Liste des vacations différentes
			$req="SELECT Id_Vacation,Id_Prestation,Id_Pole,DateVacation,
				rh_vacation.Nom,rh_vacation.Couleur
				FROM rh_personne_vacation 
				LEFT JOIN rh_vacation
				ON rh_personne_vacation.Id_Vacation=rh_vacation.Id
				WHERE rh_personne_vacation.Suppr=0
				AND rh_personne_vacation.Id_Personne=".$row['Id_Personne']."
				AND rh_personne_vacation.DateVacation>='".$Debut."' 
				AND rh_personne_vacation.DateVacation<='".$Fin."' 
				";
			$resultVac=mysqli_query($bdd,$req);
			$nbVac=mysqli_num_rows($resultVac);

			//Liste des formations 


			if($_SESSION["Langue"]=="FR"){
				$MoisLettre = array("Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Decembre");
				$joursem = array("D", "L", "Mar", "Mer", "J", "V", "S");
				$joursem2 = array("L", "Mar", "Mer", "J", "V", "S","D");
			}
			else{
				$MoisLettre = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
				$joursem = array("Sun", "M", "Tu", "W", "Th", "F", "Sat");
				$joursem2 = array("M", "Tu", "W", "Th", "F", "Sat","Sun");
			}
			echo "<tr>";
			
			$mois1=date("m",strtotime($row['DateHS']." -1 month"));
			$mois2=date("m",strtotime($row['DateHS']." +0 month"));
			$mois3=date("m",strtotime($row['DateHS']." +1 month"));
			$tab=array($mois1,$mois2,$mois3);
			$nb=1;
			$tmpDate=date("Y-m-1",strtotime($row['DateHS']." -1 month"));
			foreach ($tab as $i){
					echo "<td align='center'>";
						echo "<table style='border:1px solid #787878;' width='85%' cellpadding='0' cellspacing='0'>";
							$tabDate = explode('-', $tmpDate);
							$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
							$mois = $tabDate[1];
							echo "<tr><td class='cEnTete' colspan='8' align='center'>".$MoisLettre[$mois-1]." ".$tabDate[0]."</td></tr>";
							if($_SESSION["Langue"]=="FR"){
								echo "<tr><td class='cLigne1' align='center'></td><td class='cLigne1' align='center'>Lun.</td><td class='cLigne1' align='center'>Mar.</td><td class='cLigne1' align='center'>Mer.</td>";
								echo "<td class='cLigne1' align='center'>Jeu.</td><td class='cLigne1' align='center'>Ven.</td><td class='cLigne1' align='center'>Sam.</td><td class='cLigne1' align='center'>Dim.</td></tr>";
							}
							else{
								echo "<tr><td class='cLigne1' align='center'></td><td class='cLigne1' align='center'>Mon.</td><td class='cLigne1' align='center'>Tue.</td><td class='cLigne1' align='center'>Wed.</td>";
								echo "<td class='cLigne1' align='center'>Thu.</td><td class='cLigne1' align='center'>Fri.</td><td class='cLigne1' align='center'>Sat.</td><td class='cLigne1' align='center'>Sun.</td></tr>";
							}
							//Premier jour du mois
							$dateMois=date("Y-m-d",mktime(0,0,0,$tabDate[1],1,$tabDate[0]));
							for($ligne=1;$ligne<=6;$ligne++){
								echo "<tr>";
								for($colonne=0;$colonne<=7;$colonne++){
									$tabDateMois = explode('-', $dateMois);
									$timestampMois = mktime(0, 0, 0, $tabDateMois[1], $tabDateMois[2], $tabDateMois[0]);
									$semaine = date('W', $timestampMois);
									$jour = $tabDateMois[2];
									$jourSemaine = date('w', $timestampMois);
									
									$Trouve=false;
									$TypeDC="";
									$bEtat="rien";
									$type="";
									
									$bEtat="rien";
									$type="";	
									$indice="";
									$valAstreinte="";
									$IndiceAbs="";
									$nbHS=0;
									$NbHeureSuppJour=0;
									$NbHeureSuppNuit=0;
									if($colonne==0){
										echo "<td class='numSemaine'>".$semaine."</td>";
									}
									else{
										//Vacation contrat
										$bgcolor="";
										$laCouleur=TravailCeJourDeSemaine($dateMois,$row['Id_Personne']);
										if($laCouleur<>""){
											$type="J";
											$bgcolor="bgcolor='".$laCouleur."'";
										}
										
										//Vacation particulière
										$VacParticuliere=0;
										$Id_PrestationPole=PrestationPole_Personne($dateMois,$row['Id_Personne']);
										if($Id_PrestationPole<>0){
											$tabPresta=explode("_",$Id_PrestationPole);
											$Id_Presta=$tabPresta[0];
											$Id_Pole=$tabPresta[1];
											if($nbVac>0){
												mysqli_data_seek($resultVac,0);
												while($rowVac=mysqli_fetch_array($resultVac)){
													if($rowVac['Id_Prestation']==$Id_Presta && $rowVac['Id_Pole']==$Id_Pole && $rowVac['DateVacation']==$dateMois){
														$type=$rowVac['Nom'];
														$bgcolor="bgcolor='".$rowVac['Couleur']."'";
														$VacParticuliere=1;
														break;
													}
												}
											}
										}
										
										//Astreintes
									if($nbAst>0){
										mysqli_data_seek($resultAst,0);
										while($rowAst=mysqli_fetch_array($resultAst)){
											if($rowAst['DateAstreinte']==$dateMois){
												$valAstreinte=" AS";
												$nbHeures="0h ";
												if($rowAst['Intervention']==1){
													$nbHeures=Ajouter_Heures($rowAst['DiffHeures1'],$rowAst['DiffHeures2'],$rowAst['DiffHeures3']);
													$valAstreinte.=" ".$nbHeures;
												}
												break;
											}
										}
									}
									
									//HS
									if($nb2HS>0){
										mysqli_data_seek($resultHS,0);
										while($rowHS=mysqli_fetch_array($resultHS)){
											if($rowHS['DateHS']==$dateMois){
												$nbHS+=$rowHS['Nb_Heures_Jour']+$rowHS['Nb_Heures_Nuit'];
												$NbHeureSuppJour+=$rowHS['Nb_Heures_Jour'];
												$NbHeureSuppNuit+=$rowHS['Nb_Heures_Nuit'];
												if($indice<>""){$indice.="+";}
												if($_SESSION["Langue"]=="FR"){$indice.=($rowHS['Nb_Heures_Jour']+$rowHS['Nb_Heures_Nuit'])."HS";}
												else{$indice.=($rowHS['Nb_Heures_Jour']+$rowHS['Nb_Heures_Nuit'])."OT";}
												$bEtatHS="attenteValidation";
												if($rowHS['Etat']==4){$bEtatHS="refusee";}
												elseif($rowHS['Etat']==3 || $rowHS['Etat']==2){$bEtatHS="validee";}
											}
										}
									}
									
										//Absences
										if($nbAbs>0){
											mysqli_data_seek($resultAbs,0);
											while($rowAbs=mysqli_fetch_array($resultAbs)){
												if($rowAbs['DateDebut']<=$dateMois && $rowAbs['DateFin']>=$dateMois){
													$bEtat="validee";
													if($rowAbs['TypeAbsenceDef']<>""){
														$type=$rowAbs['TypeAbsenceDef'];
														if($rowAbs['Id_TypeAbsenceDefinitif']==0){
															$bEtat="absInjustifiee";
															$type="ABS";
														}
													}
													else{
														$type=$rowAbs['TypeAbsenceIni'];
														if($rowAbs['Id_TypeAbsenceInitial']==0){$bEtat="absInjustifiee";$type="ABS";}
													}
													break;
												}
											}
										}
										
										//Congés
										if($nbConges>0){
											mysqli_data_seek($resultConges,0);
											while($rowConges=mysqli_fetch_array($resultConges)){
												if($rowConges['DateDebut']<=$dateMois && $rowConges['DateFin']>=$dateMois){
													if($rowConges['TypeAbsenceDef']<>""){$type=$rowConges['TypeAbsenceDef'];}
													else{$type=$rowConges['TypeAbsenceIni'];}
													$bEtat="attenteValidation";
													if($rowConges['EtatN1']==-1 || $rowConges['EtatN2']==-1){$bEtat="refusee";}
													elseif($rowConges['EtatRH']==1){$bEtat="validee";}
													elseif($rowConges['EtatRH']==0 && $rowConges['EtatN2']==1){$bEtat="TransmisRH";}
													break;
												}
											}
										}
									
										if($jour==1){
											if($joursem[$jourSemaine]==$joursem2[$colonne-1] && $tabDate[1]==$tabDateMois[1]){
												if($laCouleur==""){
													if(estWE($timestampMois)){
														$bgcolor="bgcolor='".$Gris."'";
													}
												}
												if($bEtat=="attenteValidation"){$bgcolor="bgcolor='".$EnAttente."'";}
												elseif($bEtat=="validee"){$bgcolor="bgcolor='".$Validee."'";}
												elseif($bEtat=="refusee"){$bgcolor="bgcolor='".$Refusee."'";}
												elseif($bEtat=="TransmisRH"){$bgcolor="bgcolor='".$TransmisRH."'";}
												
												if($VacParticuliere==0){
													$jourFixe=estJour_Fixe($dateMois,$row['Id_Personne']);
													if($jourFixe<>""){
														$bgcolor="bgcolor='".$Automatique."'";
														$type=$jourFixe;
													}
												}

												echo "<td class='jourSemaine' ".$bgcolor." align='center'>".$jour."<sup>".$type.$IndiceAbs.$indice.$valAstreinte."</sup></td>";
												$tabDateMois = explode('-', $dateMois);
												$timestampMois = mktime(0, 0, 0, $tabDateMois[1], $tabDateMois[2]+1, $tabDateMois[0]);
												$dateMois = date("Y-m-d", $timestampMois);
											}
											else{
												echo "<td style='border:1px solid #b9b9b9;font-size:12px;' align='center'></td>";
											}
										}
										else{
											if($laCouleur==""){
												if(estWE($timestampMois)){
													$bgcolor="bgcolor='".$Gris."'";
												}
											}
											if($bEtat=="attenteValidation"){$bgcolor="bgcolor='".$EnAttente."'";}
											elseif($bEtat=="validee"){$bgcolor="bgcolor='".$Validee."'";}
											elseif($bEtat=="refusee"){$bgcolor="bgcolor='".$Refusee."'";}
											elseif($bEtat=="absInjustifiee"){$bgcolor="bgcolor='".$AbsenceInjustifies."'";}
											elseif($bEtat=="TransmisRH"){$bgcolor="bgcolor='".$TransmisRH."'";}
											
											if($VacParticuliere==0){
												$jourFixe=estJour_Fixe($dateMois,$row['Id_Personne']);
												if($jourFixe<>""){
													$bgcolor="bgcolor='".$Automatique."'";
													$type=$jourFixe;
												}
											}
											
											echo "<td class='jourSemaine' ".$bgcolor." align='center'>".$jour."<sup>".$type.$IndiceAbs.$indice.$valAstreinte."</sup></td>";
											$tabDateMois = explode('-', $dateMois);
											$timestampMois = mktime(0, 0, 0, $tabDateMois[1], $tabDateMois[2]+1, $tabDateMois[0]);
											$dateMois = date("Y-m-d", $timestampMois);
										}
									}
									
								}
								echo "</tr>";
							}
						echo "</table>";
					echo "</td>";
				//Mois suivant
				$tabDate = explode('-', $tmpDate);
				$timestamp = mktime(0, 0, 0, $tabDate[1]+1, $tabDate[2], $tabDate[0]);
				$tmpDate = date("Y-m-d", $timestamp);
				
				$nb++;
				if($nb==4 || $nb==7 || $nb==10){
					echo "</tr><tr><td height='20'></td></tr><tr>";
				}
				}
				echo "</tr><tr><td height='20'></td></tr>";
			?>
				<tr>
					<td colspan="6" align="center">
						<table align="center" width="50%" cellpadding="0" cellspacing="0">
							<tr align="left">
								<td bgcolor="<?php echo $EnAttente; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "En attente validation";}else{echo "Waiting validation";} ?></td>
								<td bgcolor="<?php echo $TransmisRH; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Transmis aux RH";}else{echo "Submitted to HR";} ?></td>
								<td bgcolor="<?php echo $Automatique; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Automatique";}else{echo "Automatic";} ?></td>
								<td bgcolor="<?php echo $Validee; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Validée";}else{echo "Validated";} ?></td>
								<td bgcolor="<?php echo $Refusee; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Refusée";}else{echo "Declined";} ?></td>
								<td bgcolor="<?php echo $AbsenceInjustifies; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Absence injustifiée";}else{echo "Unjustified absence";} ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
			</table>
		</td>
	</tr>
</table>
</form>
	
</body>
</html>
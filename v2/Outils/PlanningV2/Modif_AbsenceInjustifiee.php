<!DOCTYPE html>
<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Planning.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css"><link href="../../CSS/Curseur.css" rel="stylesheet" type="text/css"><script type="text/javascript" src="../JS/curseur.js"></script>
	<link href="../../CSS/New_Menu2.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script language="javascript" src="DemandeAbsence.js?t=<?php echo time(); ?>"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script type="text/javascript" src="../JS/mask.js"></script>
	<script type="text/javascript" src="../JS/js/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script type="text/javascript" src="../JS/bootstrap.min.js"></script>
    <script type="text/javascript" src="../JS/prettify.js"></script>
    <script type="text/javascript" src="../JS/bootstrap-timepicker.js"></script>
	<script language="javascript">
		function VerifChamps()
		{
			if(document.getElementById('ValiderRefuser').value=='R'){
				if(document.getElementById('Langue').value=="FR"){
					if(document.getElementById('commentaire').value==""){alert("Veuillez ajouter un commentaire.");return false;}
				}
				else{
					if(document.getElementById('commentaire').value==""){alert("Please add a comment.");return false;}

				}
			}
			return true;
		}
		function FermerEtRecharger(Menu,TDB,OngletTDB)
		{
			window.opener.location="Liste_AbsencesInjustifiees.php?Menu="+Menu+"&TDB="+TDB+"&OngletTDB="+OngletTDB;
			window.close();
		}
	</script>
	<script type="text/javascript">
		$(document).ready(function () {
			$('.heureDebut').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: false,
				showMeridian: false,
				defaultTime: false
			});
			Mask.newMask({ 
				$el: $('.heureDebut'), 
				mask: 'HH:mm' 
			});
			$('.heureFin').timepicker({
				minuteStep: 1,
				template: 'modal',
				appendWidgetTo: 'body',
				showSeconds: false,
				showMeridian: false,
				defaultTime: false
			});
			Mask.newMask({ 
				$el: $('.heureFin'), 
				mask: 'HH:mm' 
			});
		});
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");
require("Fonctions_Planning.php");
Ecrire_Code_JS_Init_Date();

$bEnregistrement=false;
if($_POST){
	if(isset($_POST['ModifierType']))
	{
		$requete="SELECT rh_absence.Id, rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,rh_absence.NbJour,
			rh_absence.Id_TypeAbsenceInitial,rh_absence.Id_TypeAbsenceDefinitif,rh_absence.NbHeureAbsJour,rh_absence.NbHeureAbsNuit,
			rh_absence.Id_FonctionRepresentative,
			rh_absence.HeureDepart,rh_absence.HeureArrivee
			FROM rh_absence
			WHERE Suppr=0 AND rh_absence.Id_Personne_DA=".$_POST['Id']."
			ORDER BY DateDebut
			" ;
		$resultAbs=mysqli_query($bdd,$requete);
		$nbAbs=mysqli_num_rows($resultAbs);
		$IdsAbsence="";
		if($nbAbs>0){
			$i=0;
			while($rowAbs=mysqli_fetch_array($resultAbs)){
				$tmpDate = $rowAbs['DateDebut'];
				$dateFin = $rowAbs['DateFin'];
				$bModif=0;
				$dateDebut=$tmpDate;
				$Id_TypeIni=$rowAbs['Id_TypeAbsenceInitial'];
				$Id_TypeDef=$Id_TypeIni;
				$estDif=0;
				$nbJour=0;
				
				$nbJIni=$rowAbs['NbHeureAbsJour'];
				$nbNIni=$rowAbs['NbHeureAbsNuit'];
				$heureIni=$rowAbs['HeureDepart'];
				$heureArriveeIni=$rowAbs['HeureArrivee'];
				$Id_FonctionRepresentativeIni=$rowAbs['Id_FonctionRepresentative'];
				$nbJDef=$rowAbs['NbHeureAbsJour'];
				$nbNDef=$rowAbs['NbHeureAbsNuit'];
				$heureDef=$rowAbs['HeureDepart'];
				$heureArriveeDef=$rowAbs['HeureArrivee'];
				$Id_FonctionRepresentativeDef=$rowAbs['Id_FonctionRepresentative'];
				while ($tmpDate <= $dateFin){
					if($_POST['typeAbsence'.$i]<>-1){
						/*if($_POST['typeAbsence'.$i]<>0){*/
							if($_POST['nbHeureJour'.$i]<>""){$nbJ=$_POST['nbHeureJour'.$i];} //Nb heure jour
							elseif($_POST['nbHeuresBDD'.$i]<>""){$nbJ=$_POST['nbHeuresBDD'.$i];} //Nb heure BDD
							elseif($_POST['nbHeureRC'.$i]<>""){$nbJ=$_POST['nbHeureRC'.$i];} //Nb heure RC
							else{$nbJ=0;}
							if($_POST['nbHeureNuit'.$i]<>""){$nbN=$_POST['nbHeureNuit'.$i];} //Nb heure nuit
							else{$nbN=0;}
							if($_POST['heureDebut'.$i]<>""){$heure=$_POST['heureDebut'.$i];} //Heure début 
							else{$heure="00:00:00";}
							if($_POST['heureFin'.$i]<>""){$heureFin=$_POST['heureFin'.$i];} //Heure fin 
							else{$heureFin="00:00:00";}
							$Id_FonctionRepresentative=$_POST['fonctionRepresentative'.$i];
							
							if($Id_TypeIni<>$_POST['typeAbsence'.$i] || $nbJIni<>$nbJ || $nbNIni<>$nbN || $heureIni<>$heure || $heureArriveeIni<>$heureFin || $Id_FonctionRepresentativeIni<>$Id_FonctionRepresentative){
								if($dateDebut==$tmpDate){
									$Id_TypeIni=$_POST['typeAbsence'.$i];
									if($_POST['nbHeureJour'.$i]<>""){$nbJIni=$_POST['nbHeureJour'.$i];} //Nb heure jour
									elseif($_POST['nbHeuresBDD'.$i]<>""){$nbJIni=$_POST['nbHeuresBDD'.$i];} //Nb heure BDD
									elseif($_POST['nbHeureRC'.$i]<>""){$nbJIni=$_POST['nbHeureRC'.$i];} //Nb heure RC
									else{$nbJIni=0;}
									if($_POST['nbHeureNuit'.$i]<>""){$nbNIni=$_POST['nbHeureNuit'.$i];} //Nb heure nuit
									else{$nbNIni=0;}
									if($_POST['heureDebut'.$i]<>""){$heureIni=$_POST['heureDebut'.$i];} //Heure début 
									else{$heureIni="00:00:00";}
									if($_POST['heureFin'.$i]<>""){$heureArriveeIni=$_POST['heureFin'.$i];} //Heure début 
									else{$heureArriveeIni="00:00:00";}
									$Id_FonctionRepresentativeIni=$_POST['fonctionRepresentative'.$i];
								}
								else{
									$estDif=1;
									$Id_TypeDef=$_POST['typeAbsence'.$i];
									if($_POST['nbHeureJour'.$i]<>""){$nbJDef=$_POST['nbHeureJour'.$i];} //Nb heure jour
									elseif($_POST['nbHeuresBDD'.$i]<>""){$nbJDef=$_POST['nbHeuresBDD'.$i];} //Nb heure BDD
									elseif($_POST['nbHeureRC'.$i]<>""){$nbJDef=$_POST['nbHeureRC'.$i];} //Nb heure RC
									else{$nbJDef=0;}
									if($_POST['nbHeureNuit'.$i]<>""){$nbNDef=$_POST['nbHeureNuit'.$i];} //Nb heure nuit
									else{$nbNDef=0;}
									if($_POST['heureDebut'.$i]<>""){$heureDef=$_POST['heureDebut'.$i];} //Heure début 
									else{$heureDef="00:00:00";}
									if($_POST['heureFin'.$i]<>""){$heureArriveeDef=$_POST['heureFin'.$i];} //Heure début 
									else{$heureArriveeDef="00:00:00";}
									$Id_FonctionRepresentativeDef=$_POST['fonctionRepresentative'.$i];
								}
							}
						/*}
						else{
							if($Id_TypeIni<>$rowAbs['Id_TypeAbsenceInitial'] || $nbJIni<>$rowAbs['NbHeureAbsJour'] || $nbNIni<>$rowAbs['NbHeureAbsNuit'] || $heureIni<>$rowAbs['HeureDepart'] || $heureArriveeIni<>$rowAbs['HeureArrivee'] || $Id_FonctionRepresentativeIni<>$rowAbs['Id_FonctionRepresentative']){
								$estDif=1;
								$Id_TypeDef=$rowAbs['Id_TypeAbsenceInitial'];
								$nbJDef=$rowAbs['NbHeureAbsJour'];
								$nbNDef=$rowAbs['NbHeureAbsNuit'];
								$heureDef=$rowAbs['HeureDepart'];
								$heureArriveeDef=$rowAbs['HeureArrivee'];
								$Id_FonctionRepresentativeDef=$rowAbs['Id_FonctionRepresentative'];
							}
						}*/
					}
					else{
						//Supprimer la ligne
						$estDif=1;
					}
					$nbJour++;
					if($estDif==1 && $_POST['typeAbsence'.$i]<>-1){
						$req="INSERT INTO rh_absence (Id_Personne_DA,Id_TypeAbsenceInitial,Id_TypeAbsenceDefinitif,DateDebut,DateFin,
								HeureDepart,HeureArrivee,NbHeureAbsJour,NbHeureAbsNuit,Id_FonctionRepresentative,NbJour) 
							VALUES ";

						$NewIdType=0;
						if($Id_TypeIni<>$rowAbs['Id_TypeAbsenceInitial']){$NewIdType=$Id_TypeIni;}
						$req.="(".$rowAbs['Id_Personne_DA'].",".$rowAbs['Id_TypeAbsenceInitial'].",".$NewIdType.",
							'".$dateDebut."','".date("Y-m-d",strtotime($tmpDate." -1 day"))."','".$heureIni."','".$heureArriveeIni."',".$nbJIni.",".$nbNIni.",".$Id_FonctionRepresentativeIni.",".($nbJour-1).")";
						$resultAjout=mysqli_query($bdd,$req);
						$estDif=0;
						$Id_TypeIni=$Id_TypeDef;
						$dateDebut=$tmpDate;
						$nbJour=1;
						$nbJIni=$nbJDef;
						$nbNIni=$nbNDef;
						$heureIni=$heureDef;
						$heureArriveeIni=$heureArriveeDef;
						$Id_FonctionRepresentativeIni=$Id_FonctionRepresentativeDef;
					}
					elseif($estDif==1 && $_POST['typeAbsence'.$i]==-1){
						if($dateDebut<=date("Y-m-d",strtotime($tmpDate." -1 day"))){
							$req="INSERT INTO rh_absence (Id_Personne_DA,Id_TypeAbsenceInitial,Id_TypeAbsenceDefinitif,DateDebut,DateFin,
								HeureDepart,HeureArrivee,NbHeureAbsJour,NbHeureAbsNuit,Id_FonctionRepresentative,NbJour) 
							VALUES ";

							$NewIdType=0;
							if($Id_TypeIni<>$rowAbs['Id_TypeAbsenceInitial']){$NewIdType=$Id_TypeIni;}
							$req.="(".$rowAbs['Id_Personne_DA'].",".$rowAbs['Id_TypeAbsenceInitial'].",".$NewIdType.",
								'".$dateDebut."','".date("Y-m-d",strtotime($tmpDate." -1 day"))."','".$heureIni."','".$heureArriveeIni."',".$nbJIni.",".$nbNIni.",".$Id_FonctionRepresentativeIni.",".($nbJour-1).")";
							$resultAjout=mysqli_query($bdd,$req);
						}
							$estDif=0;
							$Id_TypeIni=$Id_TypeDef;
							$dateDebut=date("Y-m-d",strtotime($tmpDate." +1 day"));
							$nbJour=0;
							$nbJIni=$nbJDef;
							$nbNIni=$nbNDef;
							$heureIni=$heureDef;
							$heureArriveeIni=$heureArriveeDef;
							$Id_FonctionRepresentativeIni=$Id_FonctionRepresentativeDef;
						
					}
					if($_POST['typeAbsence'.$i]<>0){
						$bModif=1;
					}
					$i++;
					//Jour suivant
					$tmpDate =date("Y-m-d",strtotime($tmpDate." +1 day"));
				}
				if($dateDebut<=date("Y-m-d",strtotime($tmpDate." -1 day"))){
					$req="INSERT INTO rh_absence (Id_Personne_DA,Id_TypeAbsenceInitial,Id_TypeAbsenceDefinitif,DateDebut,DateFin,
								HeureDepart,HeureArrivee,NbHeureAbsJour,NbHeureAbsNuit,Id_FonctionRepresentative,NbJour) 
							VALUES ";

					$NewIdType=0;
						if($Id_TypeIni<>$rowAbs['Id_TypeAbsenceInitial']){$NewIdType=$Id_TypeIni;}
						$req.="(".$rowAbs['Id_Personne_DA'].",".$rowAbs['Id_TypeAbsenceInitial'].",".$NewIdType.",
							'".$dateDebut."','".date("Y-m-d",strtotime($tmpDate." -1 day"))."','".$heureIni."','".$heureArriveeIni."',".$nbJIni.",".$nbNIni.",".$Id_FonctionRepresentativeIni.",".$nbJour.")";
					$resultAjout=mysqli_query($bdd,$req);
				}
				$req="UPDATE rh_absence SET Suppr=1 WHERE Id=".$rowAbs['Id'];
				$resultUpdate=mysqli_query($bdd,$req);
				
				$req="UPDATE rh_personne_demandeabsence SET Id_RH=".$_SESSION['Id_Personne'].", DateValidationRH='".date('Y-m-d')."', EtatRH=1 WHERE Id=".$rowAbs['Id_Personne_DA'];
				$resultUpdate=mysqli_query($bdd,$req);
			}
		}
	}
	echo "<script>FermerEtRecharger(".$_POST['Menu'].",".$_POST['TDB'].",'".$_POST['OngletTDB']."');</script>";
}
else{
	if($_GET['Mode']=="S"){
		$requeteUpdate="UPDATE rh_personne_demandeabsence SET 
				Suppr=1,
				Id_Suppr=".$_SESSION['Id_Personne'].",
				DateSuppr='".date('Y-m-d')."'
				WHERE Id=".$_GET['Id']." ";

		$resultat=mysqli_query($bdd,$requeteUpdate);
		echo "<script>FermerEtRecharger(".$_GET['Menu'].",".$_GET['TDB'].",'".$_GET['OngletTDB']."');</script>";
	}
}
$Menu=$_GET['Menu'];

$requete="SELECT rh_personne_demandeabsence.Id,Prevue,Id_Personne,
	rh_personne_demandeabsence.Id_Prestation,rh_personne_demandeabsence.Id_Pole,
	(SELECT new_competences_prestation.Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_demandeabsence.Id_Prestation) AS Prestation, 
	(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_demandeabsence.Id_Personne) AS Personne,  
	(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=rh_personne_demandeabsence.Id_Pole) AS Pole 
	FROM rh_personne_demandeabsence
	WHERE rh_personne_demandeabsence.Id=".$_GET['Id'] ;
$result=mysqli_query($bdd,$requete);
$row=mysqli_fetch_array($result);

$Etat="";

?>

<form id="formulaire" class="test" action="Modif_AbsenceInjustifiee.php" method="post" onsubmit=" return VerifChamps();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Step" id="Step" value="<?php echo $step; ?>" />
	<input type="hidden" name="Id" id="Id" value="<?php echo $_GET['Id']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="Id_Prestation" id="Id_Prestation" value="<?php echo $row['Id_Prestation']; ?>" />
	<input type="hidden" name="Id_Pole" id="Id_Pole" value="<?php echo $row['Id_Pole']; ?>" />
	<input type="hidden" name="ValiderRefuser" id="ValiderRefuser" value="" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $_GET['TDB']; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $_GET['OngletTDB']; ?>" />
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "N° déclaration :";}else{echo "Declaration number :";} ?></td>
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
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prévue :";}else{echo "Planned :";} ?></td>
							<td width="15%" <?php if($row['Prevue']==0){echo "bgcolor='#f51919'";}else{echo "bgcolor='#60ea34'";} ?>>
								<?php 
									if($row['Prevue']==1){
										if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";}
									}
									else{
										if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";}
									}
								?>
							</td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<?php 
						
						$req="SELECT rh_absence.Id, rh_absence.Id_Personne_DA,rh_absence.DateDebut,rh_absence.DateFin,rh_absence.NbJour,
							Id_TypeAbsenceInitial,Id_TypeAbsenceDefinitif,
							(SELECT CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=Id_TypeAbsenceInitial) AS TypeAbsIni,
							(SELECT CodePlanning FROM rh_typeabsence WHERE rh_typeabsence.Id=Id_TypeAbsenceDefinitif) AS TypeAbsDef,
							NbJour, HeureDepart, HeureArrivee, NbHeureAbsJour, NbHeureAbsNuit
							FROM rh_absence 
							WHERE Suppr=0 
							AND Id_Personne_DA=".$row['Id']." 
							ORDER BY DateDebut ASC ";
						$resultAbs=mysqli_query($bdd,$req);
						$nbAbs=mysqli_num_rows($resultAbs);
						if($nbAbs>0){
							$couleur="#dbdbdb";
							while($rowAbs=mysqli_fetch_array($resultAbs)){
								if($couleur=="#dbdbdb"){$couleur="#EEEEEE";}
								else{$couleur="#dbdbdb";}
						?>
						<tr bgcolor="<?php echo $couleur;?>">
							<td width="12%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Du :";}else{echo "from :";} ?></td>
							<td width="15%">
								<?php echo AfficheDateJJ_MM_AAAA($rowAbs['DateDebut']); ?>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "au :";}else{echo "to :";} ?></td>
							<td width="20%">
								<?php echo AfficheDateJJ_MM_AAAA($rowAbs['DateFin']); ?>
							</td>
							<td width="10%" class="Libelle" colspan="3">
								<?php 
									echo $rowAbs['NbJour'];
									if($rowAbs['Id_TypeAbsenceDefinitif']>0){
										echo " <del>ABS</del> ".$rowAbs['TypeAbsDef'];
									}
									else{
										echo " ABS";
									}
								?>
							</td>
						</tr>
						<?php
									echo "<tr bgcolor='".$couleur."'>";
									if($_SESSION["Langue"]=="FR"){echo "<td class='Libelle'>Journée complète</td>";}else{echo "<td class='Libelle'>Full day</td>";}
									if($rowAbs['NbHeureAbsJour']>0 || $rowAbs['NbHeureAbsNuit']>0){
										if($_SESSION["Langue"]=="FR"){echo "<td>Non</td>";}else{echo "<td>No</td>";}
										if($_SESSION["Langue"]=="FR"){echo "<td class='Libelle'>Heure de début</td>";}else{echo "<td class='Libelle'>Start time</td>";}
										echo "<td colspan='2'>".$rowAbs['HeureDepart']."</td>";
										if($_SESSION["Langue"]=="FR"){echo "<td class='Libelle'>Heure de fin</td>";}else{echo "<td class='Libelle'>End time</td>";}
										echo "<td colspan='2'>".$rowAbs['HeureArrivee']."</td>";
										echo "</tr>";
										echo "<tr bgcolor='".$couleur."'>";
										if($_SESSION["Langue"]=="FR"){echo "<td class='Libelle'>Nbre d'heures d'absence <br>(6h et 21h)</td>";}else{echo "<td class='Libelle'>Number of hours of absence <br>(6h and 21h)</td>";}
										echo "<td>".$rowAbs['NbHeureAbsJour']."</td>";
										if($_SESSION["Langue"]=="FR"){echo "<td class='Libelle'>Nbre d'heures d'absence <br>(21h et 6h)</td>";}else{echo "<td class='Libelle'>Number of hours of absence <br>(21h and 6h)</td>";}
										echo "<td colspan='6'>".$rowAbs['NbHeureAbsNuit']."</td>";
										echo "</tr>";
										
									}
									else{
										if($_SESSION["Langue"]=="FR"){echo "<td colspan='10'>Oui</td>";}else{echo "<td colspan='10'>Yes</td>";}
										echo "</tr>";
									}
							}
						}
						if($Menu==4){
							if($_SESSION['Langue']=="FR"){
								$selType3="<option value='0' selected>ABS | Absence injustifiée</option>";
							}
							else{
								$selType3="<option value='0' selected>ABS | Unjustified absence</option>";
							}
							if($_SESSION["Langue"]=="FR"){
								$reqAbsVac = "SELECT Id ,Libelle, CodePlanning, InformationSalarie, NbJourAutorise 
											FROM rh_typeabsence 
											WHERE Suppr=0 
											AND Id<>10
											ORDER BY Libelle ";
								//Manque analyse si la pesonne est intérimaire ou pas
							}
							else{
								$reqAbsVac = "SELECT Id ,LibelleEN AS Libelle, CodePlanning, InformationSalarie, NbJourAutorise 
											FROM rh_typeabsence 
											WHERE Suppr=0 
											AND Id<>10
											ORDER BY Libelle ";
								//Manque analyse si la pesonne est intérimaire ou pas
							}
							$resultAbsVac=mysqli_query($bdd,$reqAbsVac);
							$nbAbsVac=mysqli_num_rows($resultAbsVac);
							if ($nbAbsVac > 0){
								while($rowAbsVac=mysqli_fetch_array($resultAbsVac)){	
									$selType3.="<option value='".$rowAbsVac['Id']."' >".$rowAbsVac['CodePlanning']." | ".$rowAbsVac['Libelle']."</option>";
								}
							}
							if($_SESSION['Langue']=="FR"){
								$selType3.="<option value='-1'>Supprimer</option>";
							}
							else{
								$selType3.="<option value='-1'>Remove</option>";
							}
							$selType3.="</select>";

							$reqBDD = "SELECT Id ,Libelle
										FROM rh_fonctionrepresentative 
										WHERE Suppr=0 
										ORDER BY Libelle ";

							$resultBDD=mysqli_query($bdd,$reqBDD);
							$nb=mysqli_num_rows($resultBDD);
							$selFonction="";
							if ($nb > 0){
								while($rowFonction=mysqli_fetch_array($resultBDD)){	
									$selFonction.="<option value='".$rowFonction['Id']."' >".$rowFonction['Libelle']."</option>";
								}
							}
								
							$resultAbs=mysqli_query($bdd,$req);
							$nbAbs=mysqli_num_rows($resultAbs);
							if($nbAbs>0){
								echo "<tr><td height='4'></td></tr>";
								if($_SESSION["Langue"]=="FR"){
									echo "<tr><td colspan='6' class='Libelle'>Modification du type d'absence</td></tr>";
								}
								else{
									echo "<tr><td colspan='6' class='Libelle'>Changing the type of absence</td></tr>";
								}
								echo "<tr><td colspan='6'>";
								echo "<div style='height:160px;width:100%;overflow:auto;'>
								<table style='width:100%;'>";
								if($_SESSION["Langue"]=="FR"){
									echo "<tr>
										<td class='EnTeteTableauCompetences' width='10%'>Date</td>
										<td class='EnTeteTableauCompetences' width='10%'>Type d'absence</td>
										<td class='EnTeteTableauCompetences' width='80%'>Nouveau type d'absence</td></tr>";
								}
								else{
										echo "<tr>
										<td class='EnTeteTableauCompetences' width='10%'>Date</td>
										<td class='EnTeteTableauCompetences' width='10%'>Type of absence</td>
										<td class='EnTeteTableauCompetences' width='80%'>New type of absence</td></tr>";
								}
								$couleur="#dbdbdb";
								$i=0;
								while($rowAbs=mysqli_fetch_array($resultAbs)){
									$tmpDate = $rowAbs['DateDebut'];
									$dateFin = $rowAbs['DateFin'];
									while ($tmpDate <= $dateFin){
										if($couleur=="#dbdbdb"){$couleur="#EEEEEE";}
										else{$couleur="#dbdbdb";}
										echo "<tr bgcolor='".$couleur."' >
											<td>
											<input type='hidden' name='Id_".$i."' id='Id_".$i."' value='".$rowAbs['Id']."' />
											".AfficheDateJJ_MM_AAAA($tmpDate)."&nbsp;&nbsp;
											</td>
											<td align='center'>
											ABS
											</td>
											<td>
											<select id='typeAbsence".$i."' name='typeAbsence".$i."' onchange='Modif_TypeAbsenceRHABS(".$i.");' style='width: 60%;'>".$selType3."
											<table>";
											?>
											<tr class="journee<?php echo $i;?>">
												<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Journée complète :";}else{echo "Full day :";} ?> </td>
												<td width="15%" colspan="3" align="left">
													<input type="radio" id='journeeComplete<?php echo $i;?>' name='journeeComplete<?php echo $i;?>' onclick="Modif_TypeAbsence2RH(<?php echo $i;?>)" value="Oui" checked><?php if($_SESSION["Langue"]=="FR"){echo "Oui";}else{echo "Yes";} ?> &nbsp;&nbsp;
													<input type="radio" id='journeeComplete<?php echo $i;?>' name='journeeComplete<?php echo $i;?>' onclick="Modif_TypeAbsence2RH(<?php echo $i;?>)" value="Non" ><?php if($_SESSION["Langue"]=="FR"){echo "Non";}else{echo "No";} ?> &nbsp;&nbsp;
												</td>
											</tr>
											<tr class="nbHeure<?php echo $i;?>" style="display:none;">
												<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de début";}else{echo "Start time";} ?> : </td>
												<td width="15%">
													<div class="input-group bootstrap-timepicker timepicker">
														<input class="form-control input-small heureDebut" type="text" name="heureDebut<?php echo $i;?>" id="heureDebut<?php echo $i;?>" size="8" value="">
													</div>
												</td>
												<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Heure de fin";}else{echo "End time";} ?> : </td>
												<td width="15%">
													<div class="input-group bootstrap-timepicker timepicker">
														<input class="form-control input-small heureFin" type="text" name="heureFin<?php echo $i;?>" id="heureFin<?php echo $i;?>" size="8" value="">
													</div>
												</td>
											</tr>
											<tr class="nbHeure<?php echo $i;?>" style="display:none;">
												<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nbre d'heures d'absence entre 6h et 21h";}else{echo "Number of hours of absence between 6h and 21h";} ?> : </td>
												<td width="15%">
													<input onKeyUp="nombre(this)" style="text-align:center;" name="nbHeureJour<?php echo $i;?>" id="nbHeureJour<?php echo $i;?>" size="10" type="text" value= "">
												</td>
												<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nbre d'heures d'absence entre 21h et 6h";}else{echo "Number of hours of absence between 21h and 6h";} ?>  : </td>
												<td width="15%">
													<input onKeyUp="nombre(this)" style="text-align:center;" name="nbHeureNuit<?php echo $i;?>" id="nbHeureNuit<?php echo $i;?>" size="10" type="text" value= "">
												</td>
											</tr>
											<tr class="nbHeuresRC<?php echo $i;?>" style="display:none;">
												<td width="45%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nombre d'heure";}else{echo "Number of hours";} ?> : </td>
												<td width="45%">
													<input onKeyUp="nombre(this)" type="text" name="nbHeureRC<?php echo $i;?>" id="nbHeureRC<?php echo $i;?>" size="6" value="">
												</td>
											</tr>
											<tr class="delegation<?php echo $i;?>" style="display:none;">
												<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nombre d'heure";}else{echo "Number of hours";} ?> : </td>
												<td width="15%">
													<input onKeyUp="nombre(this)" type="text" name="nbHeuresBDD<?php echo $i;?>" id="nbHeuresBDD<?php echo $i;?>" size="6" value="">
												</td>
												<td width="15%" class="Libelle" valign="top"><?php if($_SESSION["Langue"]=="FR"){echo "Fonction représentative";}else{echo "Representative function";} ?> : </td>
												<td width="15%" valign="top">
													<select id="fonctionRepresentative<?php echo $i;?>" name="fonctionRepresentative<?php echo $i;?>" style="width: 60%;">
														<option name="0" value="0"></option>
														<?php echo $selFonction; ?>
													</select>
												</td>
											</tr>
										<?php
										echo "</table>
											</td>
										</tr>";
										//Jour suivant
										$tmpDate =date("Y-m-d",strtotime($tmpDate." +1 day"));
										$i++;
									}
								}
								echo "</table>
									</div>";
								echo "</td></tr>";
							}
							
							?>
							<tr>
								<td colspan='6' align='center'>
									<input class='Bouton' type='submit' name='ModifierType' value="<?php if($_SESSION["Langue"]=="FR"){echo "Modifier";}else{echo "Modify";} ?>" />
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
	<?php if($Menu==4){?>
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
			
			$requete="SELECT DateDebut
				FROM rh_absence
				WHERE Suppr=0 
				AND rh_absence.Id_Personne_DA=".$_GET['Id']."
				ORDER BY DateDebut ASC
				" ;
			$resultAbs=mysqli_query($bdd,$requete);
			$rowAbs=mysqli_fetch_array($resultAbs);
								
			$Debut=date("Y-m-1",strtotime($rowAbs['DateDebut']." -6 month"));
			$Fin=date("Y-m-1",strtotime($rowAbs['DateDebut']." +6 month"));

			$tmpDate=date("Y-m-1",strtotime($rowAbs['DateDebut']." -6 month"));


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
			$req="SELECT rh_personne_hs.Id, rh_personne_hs.Nb_Heures_Jour,rh_personne_hs.Nb_Heures_Nuit,DateHS,
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
					AND DateHS>='".$Debut."' 
					AND DateHS<='".$Fin."' 
					";
			$resultHS=mysqli_query($bdd,$req);
			$nbHS=mysqli_num_rows($resultHS);
								
			

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
			
			
			$mois1=date("m",strtotime($rowAbs['DateDebut']." -6 month"));
			$mois2=date("m",strtotime($rowAbs['DateDebut']." -5 month"));
			$mois3=date("m",strtotime($rowAbs['DateDebut']." -4 month"));
			$mois4=date("m",strtotime($rowAbs['DateDebut']." -3 month"));
			$mois5=date("m",strtotime($rowAbs['DateDebut']." -2 month"));
			$mois6=date("m",strtotime($rowAbs['DateDebut']." -1 month"));
			$mois7=date("m",strtotime($rowAbs['DateDebut']." +0 month"));
			$mois8=date("m",strtotime($rowAbs['DateDebut']." +1 month"));
			$mois9=date("m",strtotime($rowAbs['DateDebut']." +2 month"));
			$mois10=date("m",strtotime($rowAbs['DateDebut']." +3 month"));
			$mois11=date("m",strtotime($rowAbs['DateDebut']." +4 month"));
			$mois12=date("m",strtotime($rowAbs['DateDebut']." +5 month"));
			$tab=array($mois1,$mois2,$mois3,$mois4,$mois5,$mois6,$mois7,$mois8,$mois9,$mois10,$mois11,$mois12);
			$nb=1;
			
			$tmpDate=date("Y-m-1",strtotime($rowAbs['DateDebut']." -6 month"));
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

												echo "<td class='jourSemaine' ".$bgcolor." align='center'>".$jour."<sup>".$type."</sup></td>";
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
											
											echo "<td class='jourSemaine' ".$bgcolor." align='center'>".$jour."<sup>".$type."</sup></td>";
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
					<td height="10"></td>
				</tr>
				<tr>
					<td colspan="6" align="center">
						<table align="center" width="60%" cellpadding="0" cellspacing="0">
							<tr align="left">
								<td bgcolor="<?php echo $EnAttente; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="15%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "En attente de pré validation";}else{echo "Waiting for pre-validation";} ?></td>
								<td bgcolor="<?php echo $TransmisRH; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Transmis aux RH";}else{echo "Submitted to HR";} ?></td>
								<td bgcolor="<?php echo $Automatique; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Automatique";}else{echo "Automatic";} ?></td>
								<td bgcolor="<?php echo $Validee; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Validée";}else{echo "Validated";} ?></td>
								<td bgcolor="<?php echo $Refusee; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="10%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Refusée";}else{echo "Declined";} ?></td>
								<td bgcolor="<?php echo $AbsenceInjustifies; ?>" width="3%">&nbsp;&nbsp;&nbsp;&nbsp;</td><td class="Libelle" width="15%">&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Absence injustifiée";}else{echo "Unjustified absence";} ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="4"></td></tr>
			</table>
		</td>
	</tr>
	<?php }?>
</table>
</form>
	
</body>
</html>
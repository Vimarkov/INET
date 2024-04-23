<?php
require("../../Menu.php");
?>
<script language="javascript">
	function filtrer(Menu){
		window.location="Tableau_De_Bord.php?Menu="+Menu+"&Annee="+document.getElementById('annee').value;
	}
</script>
<?php
function Titre($Libelle,$Lien){
	echo "<tr>
			<td style='font-size:14px;' colspan='8' >&nbsp;&bull;&nbsp;
				<a style=\"color:black;text-decoration: none;font-weight:bold;\" onmouseover=\"this.style.color='#0d8df1';\" onmouseout=\"this.style.color='#000000';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >
					".$Libelle."
				</a>
			</td>
		</tr>\n
		<tr>
			<td height=\"5px\">
			
			</td>
		</tr>
		";
}

function Titre1($Libelle,$Lien,$Selected){
	$tiret="";
	$couleurTexte="#00577c";
	if($Selected==true){$tiret="border-bottom:5px solid #ffffff;font-style:italic;font-size:16px;";$couleurTexte="#ffffff";}
	echo "<td style=\"width:20%;height:30px;border-spacing:0;text-align:center;color:".$couleurTexte.";valign:top;font-weight:bold;".$tiret."\" onclick=\"window.stop();\">
		<a style=\"text-decoration:none;width:70px;height:30px;border-spacing:0;text-align:center;color:".$couleurTexte.";valign:top;font-weight:bold;\" onmouseover=\"this.style.color='".$couleurTexte."';\" onmouseout=\"this.style.color='".$couleurTexte."';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle."</a></td>\n";
}

function Widget($Libelle,$Lien,$Image,$Couleur){
	echo "
			<table style='border-spacing: 25px;display:inline-table;' >
				<tr>
					<td style=\"width:130px;height:110px;border-style:outset; border-radius: 15px;border-color:".$Couleur.";border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='".$Couleur."'>
						<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:center;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >
							<img width='40px' src='../../Images/".$Image."' border='0' /><br>
							".$Libelle."
						</a>
					</td>
				</tr>
			</table>";
}
?>
<form class="test" action="Tableau_De_Bord.php" method="post">
<table style="width:100%; border-spacing:0px;">
	<tr>
		<td valign="center" align="right" colspan="10" style="font-weight:bold;font-size:15px;">
			<table style="align:center;">
				<tr>
					<td style="height:30px;text-align:center;color:#103b63;valign:top;font-weight:bold;font-size:15px;">&nbsp;&nbsp;
						<?php
							echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							if($LangueAffichage=="FR"){echo "Guide utilisateur - profil salarié : ";}else{echo "User guide - employee profile : ";}
							echo "<a target='_blank' href='OPTEA - Notice profil salarie.pdf'><img src='../../Images/pdf.png' border='0' width='24px'></a>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>";
						?>
					</td>
					<?php if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation)){ ?>
					<td style="height:30px;text-align:center;color:#103b63;valign:top;font-weight:bold;font-size:15px;">&nbsp;&nbsp;
						<?php
							echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							if($LangueAffichage=="FR"){echo "Guide utilisateur - profil manager : ";}else{echo "User guide - manager profile : ";}
							echo "<a target='_blank' href='OPTEA - Notice profil manager.pdf'><img src='../../Images/pdf.png' border='0' width='24px'></a>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>";
						?>
					</td>
					<?php } ?>
					<?php if(DroitsFormationPlateforme($TableauIdPostesRH) || DroitsFormationPlateforme(array($IdPosteOperateurSaisieRH))){ ?>
					<td style="height:30px;text-align:center;color:#103b63;valign:top;font-weight:bold;font-size:15px;">&nbsp;&nbsp;
						<?php
							echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							if($LangueAffichage=="FR"){echo "Guide utilisateur - profil RH : ";}else{echo "User guide - HR profile : ";}
							echo "<a target='_blank' href='OPTEA - Notice profil RH.pdf'><img src='../../Images/pdf.png' border='0' width='24px'></a>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>";
						?>
					</td>
					<?php } ?>
					<?php if(DroitsFormationPlateforme(array($IdPosteAideRH))){ ?>
					<td style="height:30px;text-align:center;color:#103b63;valign:top;font-weight:bold;font-size:15px;">&nbsp;&nbsp;
						<?php
							echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							if($LangueAffichage=="FR"){echo "Guide utilisateur - Import du personnel WD - Extranet : ";}else{echo "User guide - WD personnel import - Extranet : ";}
							echo "<a target='_blank' href='OPTEA - Notice Import du personnel WD - Extranet.pdf'><img src='../../Images/pdf.png' border='0' width='24px'></a>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>";
						?>
					</td>
					<?php } ?>
				</tr>
			</table>
		</td>
	</tr>
	<tr bgcolor="#6EB4CD">
	<?php
		if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation) || DroitsFormationPlateforme($TableauIdPostesRH) || DroitsFormationPlateforme(array($IdPosteOperateurSaisieRH))){
			$select=false;
			if(isset($_GET['Menu'])){
				if($_GET['Menu']==1){$select=true;}
			}
			if($_SESSION["Langue"]=="FR"){Titre1("TABLEAU DE BORD","Outils/PlanningV2/Tableau_De_Bord.php?Menu=1",$select);}
			else{Titre1("DASHBOARD","Outils/PlanningV2/Tableau_De_Bord.php?Menu=1",$select);}
		}
		
		if(DroitsFormationPlateforme($TableauIdPosteMGX)){
			$select=false;
			if(isset($_GET['Menu'])){
				if($_GET['Menu']==7){$select=true;}
			}
			if($_SESSION["Langue"]=="FR"){Titre1("MOYENS GENERAUX","Outils/PlanningV2/Tableau_De_Bord.php?Menu=7",$select);}
			else{Titre1("SERVICE GENERAL MEANS","Outils/PlanningV2/Tableau_De_Bord.php?Menu=7",$select);}
		}
		if(DroitsFormationPrestation(array($IdPosteReferentQualiteProduit)) || DroitsFormationPlateforme(array($IdPosteResponsableQualite))){
			$select=false;
			if(isset($_GET['Menu'])){
				if($_GET['Menu']==10){$select=true;}
			}
			if($_SESSION["Langue"]=="FR"){Titre1("QUALITE","Outils/PlanningV2/Tableau_De_Bord.php?Menu=10",$select);}
			else{Titre1("QUALITY","Outils/PlanningV2/Tableau_De_Bord.php?Menu=10",$select);}
		}
		if(DroitsFormationPlateforme(array($IdPosteControleGestion))){
			$select=false;
			if(isset($_GET['Menu'])){
				if($_GET['Menu']==11){$select=true;}
			}
			if($_SESSION["Langue"]=="FR"){Titre1("CONTRÔLE DE GESTION","Outils/PlanningV2/Tableau_De_Bord.php?Menu=11",$select);}
			else{Titre1("MANAGEMENT CONTROL","Outils/PlanningV2/Tableau_De_Bord.php?Menu=11",$select);}
		}
		if(DroitsFormationPlateforme(array($IdPosteResponsableRecrutement,$IdPosteRecrutement))){
			$select=false;
			if(isset($_GET['Menu'])){
				if($_GET['Menu']==12){$select=true;}
			}
			if($_SESSION["Langue"]=="FR"){Titre1("RECRUTEMENT","Outils/PlanningV2/Tableau_De_Bord.php?Menu=12",$select);}
			else{Titre1("RECRUTEMENT","Outils/PlanningV2/Tableau_De_Bord.php?Menu=12",$select);}
		}
		if(DroitsFormationPlateforme(array($IdPosteResponsableHSE))){
			$select=false;
			if(isset($_GET['Menu'])){
				if($_GET['Menu']==14){$select=true;}
			}
			if($_SESSION["Langue"]=="FR"){Titre1("SÉCURITÉ","Outils/PlanningV2/Tableau_De_Bord.php?Menu=14",$select);}
			else{Titre1("SECURITY","Outils/PlanningV2/Tableau_De_Bord.php?Menu=14",$select);}
		}
		if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation)){
			$select=false;
			if(isset($_GET['Menu'])){
				if($_GET['Menu']==3){$select=true;}
			}
			if($_SESSION["Langue"]=="FR"){Titre1("GESTION DES EQUIPES","Outils/PlanningV2/Tableau_De_Bord.php?Menu=3",$select);}
			else{Titre1("TEAM MANAGEMENT","Outils/PlanningV2/Tableau_De_Bord.php?Menu=3",$select);}
		}

		if(DroitsFormationPlateforme($TableauIdPostesRH) || DroitsFormationPlateforme(array($IdPosteOperateurSaisieRH))){
			$select=false;
			if(isset($_GET['Menu'])){
				if($_GET['Menu']==4){$select=true;}
			}
			if($_SESSION["Langue"]=="FR"){Titre1("ADMINISTRATION DU PERSONNEL","Outils/PlanningV2/Tableau_De_Bord.php?Menu=4",$select);}
			else{Titre1("PERSONNEL ADMINISTRATION","Outils/PlanningV2/Tableau_De_Bord.php?Menu=4",$select);}
		}
		
		if(DroitsFormationPlateforme(array($IdPosteAideRH))){
			$select=false;
			if(isset($_GET['Menu'])){
				if($_GET['Menu']==15){$select=true;}
			}
			if($_SESSION["Langue"]=="FR"){Titre1("ASSISTANCE RH","Outils/PlanningV2/Tableau_De_Bord.php?Menu=15",$select);}
			else{Titre1("HR ASSISTANCE","Outils/PlanningV2/Tableau_De_Bord.php?Menu=15",$select);}
		}
		
		$select=false;
		if(isset($_GET['Menu'])){
			if($_GET['Menu']==2){$select=true;}
		}
		if($_SESSION["Langue"]=="FR"){Titre1("ACCES PERSONNEL","Outils/PlanningV2/Tableau_De_Bord.php?Menu=2",$select);}
		else{Titre1("PERSONAL ACCESS","Outils/PlanningV2/Tableau_De_Bord.php?Menu=2",$select);}

		
		$select=false;
		if(isset($_GET['Menu'])){
			if($_GET['Menu']==5){$select=true;}
		}
		if($_SESSION["Langue"]=="FR"){Titre1("PLANNING PERSONNEL","Outils/PlanningV2/Tableau_De_Bord.php?Menu=5",$select);}
		else{Titre1("PERSONAL PLANNING","Outils/PlanningV2/Tableau_De_Bord.php?Menu=5",$select);}
		
		if(DroitsFormationPlateforme($TableauIdPostesRH) || DroitsFormationPlateforme(array($IdPosteOperateurSaisieRH))){
			$select=false;
			if(isset($_GET['Menu'])){
				if($_GET['Menu']==6){$select=true;}
			}
			if($_SESSION["Langue"]=="FR"){Titre1("PARAMETRAGE&nbsp;&nbsp;&nbsp;","Outils/PlanningV2/Tableau_De_Bord.php?Menu=6",$select);}
			else{Titre1("SETTING","Outils/PlanningV2/Tableau_De_Bord.php?Menu=6",$select);}
		}
	?>
	</tr>
	<tr>
		<td height="5px"></td>
	</tr>
	<?php
		if($_GET['Menu']==1){
	?>
		<tr>
			<td height="10px"></td>
		</tr>
		<tr>
			<td colspan="8" align="center" style="width:100%">
			<?php 
			if(isset($_GET['OngletTDB'])){
				if($_GET['OngletTDB']=="Manager"){
					require "TDB.php";
				}
				elseif($_GET['OngletTDB']=="RH"){
					require "TDB_RH.php";
				}
			}
			else{
				if((DroitsFormationPlateforme(array($IdPosteResponsablePlateforme)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation))){
					require "TDB.php";
				}
				elseif(DroitsFormationPlateforme($TableauIdPostesRH) || DroitsFormationPlateforme(array($IdPosteOperateurSaisieRH))){
					require "TDB_RH.php";
				}
			}
			?>
			</td>
		</tr>
	<?php
		}
		elseif($_GET['Menu']==2){
	?>
	<?php 
		$nb=NombreDemandeCongesEC();
		if($nb>0){
	?>
	<tr>
		<td colspan="8" align="center" style="width:100%;text-align:center;valign:top;font-weight:bold;color:#e9152a;font-size:20px;">
			<?php if($_SESSION["Langue"]=="FR"){echo "<img width='25px' src='../../Images/attention.png'/> Vous avez des demandes de congés en attente de validation. Ne partez pas en congés tant que ces demandes ne sont pas validées.";}else{echo "<img width='25px' src='../../Images/attention.png'/> You have requests for leave pending validation. Do not go on leave until these requests are validated.";} ?>
		</td>
	</tr>
	<?php
		}
	?>
	<tr>
		<td colspan="8" align="center" style="width:100%;text-align:center;color:#0074a2;valign:top;font-weight:bold;text-decoration:underline;font-size:15px;">
			<?php if($_SESSION["Langue"]=="FR"){echo "EMETTRE UNE NOUVELLE DEMANDE";}else{echo "SUBMIT A NEW REQUEST";} ?>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%">
	<?php
			if($_SESSION["Langue"]=="FR"){$libelle="Demande de congés / absence";}else{$libelle="Request for leave / absence";}
			Widget($libelle,"Outils/PlanningV2/Ajout_Conges.php?Menu=".$_GET['Menu'],"RH/Palmier.png","#edf430");
			
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Déclaration d'astreinte<br>";}else{$libelle="<br>Declaration of on-call<br>";}
			Widget($libelle,"Outils/PlanningV2/Ajout_Astreinte.php?Menu=".$_GET['Menu'],"RH/Astreinte.png","#6fb543");
	?>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%;text-align:center;color:#0074a2;valign:top;font-weight:bold;text-decoration:underline;font-size:15px;">
			<?php if($_SESSION["Langue"]=="FR"){echo "SUIVI DES DEMANDES";}else{echo "FOLLOW-UP";} ?>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%">
	<?php
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Congés / Absences<br>";}else{$libelle="<br>Leave / absence<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_DemandeConges.php?Menu=".$_GET['Menu'],"RH/Palmier.png","#edf430");
			
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Rapports d'astreintes<br>";}else{$libelle="<br>On-call reports<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_DemandeAstreinte.php?Menu=".$_GET['Menu'],"RH/Astreinte.png","#6fb543");
			
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Heures supp.<br>";}else{$libelle="<br>Overtime<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_HeureSupp.php?Menu=".$_GET['Menu'],"RH/Chrono.png","#11b9a7");
			
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Absences injustifiées<br>";}else{$libelle="<br>Unjustified absences<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_AbsencesInjustifiees.php?Menu=".$_GET['Menu'],"RH/Absence.png","#f561a4");
			
	?>
		</td>
	</tr>
	<?php if($_SESSION['RHPartie']==2){ ?>
	<tr>
		<td colspan="8" align="center" style="width:100%;text-align:center;color:#0074a2;valign:top;font-weight:bold;text-decoration:underline;font-size:15px;">
			<?php if($_SESSION["Langue"]=="FR"){echo "GESTION PERSONNELLE";}else{echo "PERSONAL MANAGEMENT";} ?>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%">
		<?php
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Visites médicales<br>";}else{$libelle="<br>Medical visits<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_VisiteMedicalePersonne.php?Menu=".$_GET['Menu'],"RH/VM.png","#cdcc8d");
		?>
		</td>
	</tr>
	<?php } ?>
	<?php
		}
		elseif($_GET['Menu']==3){
	?>
	<tr>
		<td colspan="8" align="center" style="width:100%;text-align:center;color:#0074a2;valign:top;font-weight:bold;text-decoration:underline;font-size:15px;">
			<?php if($_SESSION["Langue"]=="FR"){echo "EMETTRE UNE NOUVELLE DEMANDE";}else{echo "SUBMIT A NEW REQUEST";} ?>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%">
	<?php
			if($_SESSION["Langue"]=="FR"){$libelle="Déclarer <br>des astreintes ";}else{$libelle="Declare on-call";}
			Widget($libelle,"Outils/PlanningV2/Ajout_Astreinte.php?Menu=".$_GET['Menu'],"RH/Astreinte.png","#6fb543");
			
			if($_SESSION["Langue"]=="FR"){$libelle="Déclarer des heures supplémentaires";}else{$libelle="Declare overtime";}
			Widget($libelle,"Outils/PlanningV2/Ajout_HeureSupp.php?Mode=Ajout&Menu=".$_GET['Menu'],"RH/Chrono.png","#11b9a7");
			
			if($_SESSION["Langue"]=="FR"){$libelle="Déclarer un accident de travail";}else{$libelle="Declare an accident at work";}
			Widget($libelle,"Outils/PlanningV2/Ajout_AT.php?Menu=".$_GET['Menu'],"RH/Accident.png","#ff1111");
			
			if($_SESSION["Langue"]=="FR"){$libelle="Déclarer une absence injustifiée";}else{$libelle="Declare an unjustified absences";}
			Widget($libelle,"Outils/PlanningV2/Ajout_AbsencesInjustifiees.php?Menu=".$_GET['Menu'],"RH/Absence.png","#f561a4");
			
			if($_SESSION["Langue"]=="FR"){$libelle="Déclarer un mouvement de personnel";}else{$libelle="Declare a staff movement";}
			Widget($libelle,"Outils/PlanningV2/Ajout_MouvementPersonne.php?Menu=".$_GET['Menu'],"RH/Transfert.png","#1365b6");
			
			if($_SESSION['RHPartie']==2){
				if($_SESSION["Langue"]=="FR"){$libelle="<br>Déclarer un petit déplacement ponctuel<br>";}else{$libelle="<br>Declare a small one-time trip<br>";}
				Widget($libelle,"Outils/PlanningV2/Ajout_DODM.php?Menu=".$_GET['Menu'],"RH/Voiture.png","#a9e99d");
			}
			
			if($_SERVER['SERVER_NAME']=="192.168.20.3" || $_SERVER['SERVER_NAME']=="127.0.0.1"){
				if(DroitsFormationPrestation(array($IdPosteCoordinateurProjet,$IdPosteResponsableProjet,$IdPosteResponsableOperation))){
					if($_SESSION["Langue"]=="FR"){$libelle="Demander une ressource";}else{$libelle="Request a resource";}
					Widget($libelle,"Outils/PlanningV2/Ajout_DemandeRessource.php?Menu=".$_GET['Menu'],"Formation/Aide.png","#67d8d9");
				}
			}
	?>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%;text-align:center;color:#0074a2;valign:top;font-weight:bold;text-decoration:underline;font-size:15px;">
			<?php if($_SESSION["Langue"]=="FR"){echo "SUIVI DES DEMANDES";}else{echo "FOLLOW-UP";} ?>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%">
	<?php
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Rapports d'astreintes<br>";}else{$libelle="<br>On-call reports<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_DemandeAstreinte.php?Menu=".$_GET['Menu'],"RH/Astreinte.png","#6fb543");
			
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Heures supp.<br>";}else{$libelle="<br>Overtime<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_HeureSupp.php?Menu=".$_GET['Menu'],"RH/Chrono.png","#11b9a7");
			
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Accidents de travail<br>";}else{$libelle="<br>Accidents at work<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_AT.php?Menu=".$_GET['Menu'],"RH/Accident.png?Menu=".$_GET['Menu'],"#ff1111");
			
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Demandes de congés<br>";}else{$libelle="<br>Request for leave<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_DemandeConges.php?Menu=".$_GET['Menu'],"RH/Palmier.png","#edf430");
			
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Absences injustifiées<br>";}else{$libelle="<br>Unjustified absences<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_AbsencesInjustifiees.php?Menu=".$_GET['Menu'],"RH/Absence.png","#f561a4");
			
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Mouvement de personnel<br>";}else{$libelle="<br>Staff movement<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_MouvementPersonnel.php?Menu=".$_GET['Menu'],"RH/Transfert.png","#1365b6");
			
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Petit déplacement ponctuel<br>";}else{$libelle="<br>Small one-time trip<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_DODM.php?Menu=".$_GET['Menu'],"RH/Voiture.png?Menu=".$_GET['Menu'],"#a9e99d");
	?>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%;text-align:center;color:#0074a2;valign:top;font-weight:bold;text-decoration:underline;font-size:15px;">
			<?php if($_SESSION["Langue"]=="FR"){echo "GESTION DU PERSONNEL";}else{echo "STAFF MANAGEMENT";} ?>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%">
	<?php		
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Planning<br><br>";}else{$libelle="<br>Schedule<br>";}
		Widget($libelle,"Outils/PlanningV2/Planning.php?Menu=".$_GET['Menu'],"RH/Planning.png","#77c39a");
		
		if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme)) || $_SESSION['Id_Personne']==12534){
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Planning / Personne<br>";}else{$libelle="<br>Planning / Person<br>";}
			Widget($libelle,"Outils/PlanningV2/PlanningPersonnel2.php?Menu=".$_GET['Menu'],"Formation/Planning.png","#77c39a");
		}
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Informations du personnel<br>";}else{$libelle="<br>Staff information<br>";}
		Widget($libelle,"Outils/PlanningV2/Liste_InformationsPersonnel.php?Menu=".$_GET['Menu'],"RH/Personne2.png","#87ceff");
		
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Visualisation des plannings<br>";}else{$libelle="<br>Visualization of schedules<br>";}
		Widget($libelle,"Outils/PlanningV2/PlanningGlobal.php?Menu=".$_GET['Menu'],"RH/Planning.png","#93d7cd");
		
		if($_SESSION["Langue"]=="FR"){$libelle="<br><br>Répartition AAA<br>";}else{$libelle="<br><br>Allocation AAA<br>";}
		Widget($libelle,"Outils/PlanningV2/RepartitionAAA2.php?Menu=".$_GET['Menu'],"RH/Camembert.png","#fbb161");
	?>
		</td>
	</tr>
	<?php
		}
		elseif($_GET['Menu']==4){
	?>
	<tr>
		<td colspan="8" align="center" style="width:100%;text-align:center;color:#0074a2;valign:top;font-weight:bold;text-decoration:underline;font-size:15px;">
			<?php if($_SESSION["Langue"]=="FR"){echo "EMETTRE UNE NOUVELLE DEMANDE";}else{echo "SUBMIT A NEW REQUEST";} ?>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%">
	<?php
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Déclarer des congés<br>";}else{$libelle="<br>Declare for leave<br>";}
			Widget($libelle,"Outils/PlanningV2/Ajout_Conges.php?Menu=".$_GET['Menu'],"RH/Palmier.png","#edf430");
			
			if($_SESSION["Langue"]=="FR"){$libelle="Déclarer <br>des astreintes ";}else{$libelle="Declare on-call";}
			Widget($libelle,"Outils/PlanningV2/Ajout_Astreinte.php?Menu=".$_GET['Menu'],"RH/Astreinte.png","#6fb543");
			
			if($_SESSION["Langue"]=="FR"){$libelle="Déclarer des heures supplémentaires";}else{$libelle="Declare overtime";}
			Widget($libelle,"Outils/PlanningV2/Ajout_HeureSupp.php?Mode=Ajout&Menu=".$_GET['Menu'],"RH/Chrono.png","#11b9a7");
			
			if($_SESSION["Langue"]=="FR"){$libelle="Déclarer un accident de travail";}else{$libelle="Declare an accident at work";}
			Widget($libelle,"Outils/PlanningV2/Ajout_AT.php?Menu=".$_GET['Menu'],"RH/Accident.png","#ff1111");
			
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Déclarer des absences<br>";}else{$libelle="<br>Report absences<br>";}
			Widget($libelle,"Outils/PlanningV2/Ajout_Absences.php?Menu=".$_GET['Menu'],"RH/Absence.png","#f561a4");
			
			if($_SESSION["Langue"]=="FR"){$libelle="Déclarer un mouvement de personnel";}else{$libelle="Declare a staff movement";}
			Widget($libelle,"Outils/PlanningV2/Ajout_MouvementPersonne.php?Menu=".$_GET['Menu'],"RH/Transfert.png","#1365b6");
			
	?>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%;text-align:center;color:#0074a2;valign:top;font-weight:bold;text-decoration:underline;font-size:15px;">
			<?php if($_SESSION["Langue"]=="FR"){echo "SUIVI DES DEMANDES";}else{echo "FOLLOW-UP";} ?>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%">
	<?php
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Demandes de congés<br>";}else{$libelle="<br>Request for leave<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_DemandeConges.php?Menu=".$_GET['Menu'],"RH/Palmier.png","#edf430");
			
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Rapports d'astreintes<br>";}else{$libelle="<br>On-call reports<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_DemandeAstreinte.php?Menu=".$_GET['Menu'],"RH/Astreinte.png","#6fb543");
			
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Heures supp.<br>";}else{$libelle="<br>Overtime<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_HeureSupp.php?Menu=".$_GET['Menu'],"RH/Chrono.png","#11b9a7");
			
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Accidents de travail<br>";}else{$libelle="<br>Accidents at work<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_AT.php?Menu=".$_GET['Menu'],"RH/Accident.png?Menu=".$_GET['Menu'],"#ff1111");
			
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Absences<br>";}else{$libelle="<br>Absences<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_AbsencesInjustifiees.php?Menu=".$_GET['Menu'],"RH/Absence.png","#f561a4");
			
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Mouvement de personnel<br>";}else{$libelle="<br>Staff movement<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_MouvementPersonnelRH.php?Menu=".$_GET['Menu'],"RH/Transfert.png","#1365b6");
			
			if($_SESSION['RHPartie']==2){
				if($_SESSION["Langue"]=="FR"){$libelle="<br>Petit déplacement ponctuel<br>";}else{$libelle="<br>Small one-time trip<br>";}
				Widget($libelle,"Outils/PlanningV2/Liste_DODM.php?Menu=".$_GET['Menu'],"RH/Voiture.png?Menu=".$_GET['Menu'],"#a9e99d");
			}
	?>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%;text-align:center;color:#0074a2;valign:top;font-weight:bold;text-decoration:underline;font-size:15px;">
			<?php if($_SESSION["Langue"]=="FR"){echo "GESTION RH";}else{echo "HR MANAGEMENT";} ?>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%">
	<?php
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Planning<br>";}else{$libelle="<br>Schedule<br>";}
		Widget($libelle,"Outils/PlanningV2/Planning.php?Menu=".$_GET['Menu'],"RH/Planning.png","#77c39a");
		
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Planning / Personne<br>";}else{$libelle="<br>Planning / Person<br>";}
		Widget($libelle,"Outils/PlanningV2/PlanningPersonnel2.php?Menu=".$_GET['Menu'],"Formation/Planning.png","#77c39a");
		
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Relevés d'heures<br>";}else{$libelle="<br>Hour readings<br>";}
		Widget($libelle,"Outils/PlanningV2/Liste_RelevesHeures.php?Menu=".$_GET['Menu'],"RH/ReleveHeure.png","#77c39a");
		
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Déclarer une personne<br>";}else{$libelle="<br>Declare a person<br>";}
		Widget($libelle,"Outils/PlanningV2/Ajout_Personne.php?Menu=".$_GET['Menu'],"RH/Personne2.png","#87ceff");
		
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Déclarer un externe<br>";}else{$libelle="<br>Declare an external<br>";}
		Widget($libelle,"Outils/PlanningV2/Ajout_PersonneExterne.php?Menu=".$_GET['Menu'],"RH/Personne2.png","#f53939");
		
		if($_SESSION["Langue"]=="FR"){$libelle="Informations du personnel";}else{$libelle="Staff information";}
		Widget($libelle,"Outils/PlanningV2/Liste_InformationsPersonnel.php?Menu=".$_GET['Menu'],"RH/Personne2.png","#87ceff");
	
		if(DroitsFormationPlateforme($TableauIdPostesRH)){
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Contrats<br>";}else{$libelle="<br>Contracts<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_ContratEC.php?Menu=".$_GET['Menu'],"RH/Contrat.png","#a988b2");
		}
		
		if($_SESSION['RHPartie']==2){
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Visites médicales<br>";}else{$libelle="<br>Medical visits<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_VisiteMedicaleEC.php?Menu=".$_GET['Menu'],"RH/VM.png","#cdcc8d");
		}
	?>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%;text-align:center;color:#0074a2;valign:top;font-weight:bold;text-decoration:underline;font-size:15px;">
			<?php if($_SESSION["Langue"]=="FR"){echo "INDICATEURS";}else{echo "INDICATORS";} ?>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%">
	<?php
	if($_SESSION['RHPartie']==2){
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Compteur des congés<br>";}else{$libelle="<br>Leave counter<br>";}
		Widget($libelle,"Outils/PlanningV2/CompteurConges.php?Menu=".$_GET['Menu'],"RH/Palmier.png","#edf430");

		if($_SESSION["Langue"]=="FR"){$libelle="<br>Suivi des effectifs<br>";}else{$libelle="<br>Workforce monitoring<br>";}
		Widget($libelle,"Outils/PlanningV2/SuiviEffectif.php?Menu=".$_GET['Menu'],"RH/Contrat.png","#a988b2");
		
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Suivi / Turn over Intérim<br>";}else{$libelle="<br>Follow-up / Turn over Interim<br>";}
		Widget($libelle,"Outils/PlanningV2/TurnOverInterim.php?Menu=".$_GET['Menu'],"RH/Contrat.png","#a988b2");
		
		if($_SESSION["Langue"]=="FR"){$libelle="Suivi / Turn over Effectif AAA";}else{$libelle="Follow-up / Turn over Effective AAA";}
		Widget($libelle,"Outils/PlanningV2/TurnOverEffectifAAA.php?Menu=".$_GET['Menu'],"RH/Contrat.png","#a988b2");
		
		if($_SESSION["Langue"]=="FR"){$libelle="Suivi des arrêts maladies longs";}else{$libelle="<br>Follow-up of sick leave<br>";}
		Widget($libelle,"Outils/PlanningV2/SuiviAM.php?Menu=".$_GET['Menu'],"RH/Absence.png","#f561a4");
		
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Taux d'absentéisme<br>";}else{$libelle="<br>Absenteeism<br>";}
		Widget($libelle,"Outils/PlanningV2/TauxAbsenteisme.php?Menu=".$_GET['Menu'],"RH/Absence.png","#f561a4");
		
		if($_SESSION["Langue"]=="FR"){$libelle="Suivi des absences";}else{$libelle="Absence tracking";}
		Widget($libelle,"Outils/PlanningV2/SuiviAbsences.php?Menu=".$_GET['Menu'],"RH/Absence.png","#f561a4");
			
		//if($_SESSION["Langue"]=="FR"){$libelle="Suivi des heures travaillées & heures supp.";}else{$libelle="Tracking hours worked & overtime";}
		//Widget($libelle,"Outils/PlanningV2/SuiviHeures.php?Menu=".$_GET['Menu'],"RH/Chrono.png","#11b9a7");
	}
	?>
		</td>
	</tr>
	<?php
		}
		elseif($_GET['Menu']==7){
	?>
	<tr>
		<td colspan="8" align="center" style="width:100%;text-align:center;color:#0074a2;valign:top;font-weight:bold;text-decoration:underline;font-size:15px;">
			<?php if($_SESSION["Langue"]=="FR"){echo "SUIVI DES DEMANDES";}else{echo "FOLLOW-UP";} ?>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%">
	<?php
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Informations du personnel<br>";}else{$libelle="<br>Staff information<br>";}
		Widget($libelle,"Outils/PlanningV2/Liste_InformationsPersonnel.php?Menu=".$_GET['Menu'],"RH/Personne2.png","#87ceff");
		
		
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Petit déplacement ponctuel<br>";}else{$libelle="<br>Small one-time trip<br>";}
		Widget($libelle,"Outils/PlanningV2/Liste_DODM.php?Menu=".$_GET['Menu'],"RH/Voiture.png?Menu=".$_GET['Menu'],"#a9e99d");

	?>
		</td>
	</tr>
	<?php
		}
		elseif($_GET['Menu']==8){
	?>
	<tr>
		<td colspan="8" align="center" style="width:100%;text-align:center;color:#0074a2;valign:top;font-weight:bold;text-decoration:underline;font-size:15px;">
			<?php if($_SESSION["Langue"]=="FR"){echo "SUIVI DES DEMANDES";}else{echo "FOLLOW-UP";} ?>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%">
	<?php

		if($_SESSION["Langue"]=="FR"){$libelle="<br>Petit déplacement ponctuel<br>";}else{$libelle="<br>Small one-time trip<br>";}
		Widget($libelle,"Outils/PlanningV2/Liste_DODM.php?Menu=".$_GET['Menu'],"RH/Voiture.png?Menu=".$_GET['Menu'],"#a9e99d");

	?>
		</td>
	</tr>
	<?php
		}
		elseif($_GET['Menu']==9){
	?>
	<tr>
		<td colspan="8" align="center" style="width:100%;text-align:center;color:#0074a2;valign:top;font-weight:bold;text-decoration:underline;font-size:15px;">
			<?php if($_SESSION["Langue"]=="FR"){echo "GESTION RH";}else{echo "HR MANAGEMENT";} ?>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%">
	<?php
		if($_SESSION['RHPartie']==2){
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Visites médicales<br>";}else{$libelle="<br>Medical visits<br>";}
			Widget($libelle,"Outils/PlanningV2/Liste_VisiteMedicaleEC.php?Menu=".$_GET['Menu'],"RH/VM.png","#cdcc8d");
		}
	?>
		</td>
	</tr>
	<?php
		}
		elseif($_GET['Menu']==10){
	?>
	<tr>
		<td colspan="8" align="center" style="width:100%">
	<?php
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Visualisation des plannings<br>";}else{$libelle="<br>Visualization of schedules<br>";}
		Widget($libelle,"Outils/PlanningV2/PlanningGlobal.php?Menu=".$_GET['Menu'],"RH/Planning.png","#93d7cd");
	?>
		</td>
	</tr>
	<?php
		}
		elseif($_GET['Menu']==11){
	?>
	<tr>
		<td colspan="8" align="center" style="width:100%">
	<?php
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Visualisation des plannings<br>";}else{$libelle="<br>Visualization of schedules<br>";}
		Widget($libelle,"Outils/PlanningV2/PlanningGlobal.php?Menu=".$_GET['Menu'],"RH/Planning.png","#93d7cd");
		
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Ventilation pour délestage SAP<br>";}else{$libelle="<br>Ventilation for SAP load shedding<br>";}
		Widget($libelle,"Outils/PlanningV2/RepartitionSalariesAAA.php?Menu=".$_GET['Menu'],"RH/Camembert.png","#fa9426");
		
		if($_SESSION["Langue"]=="FR"){$libelle="<br><br>Répartition AAA<br>";}else{$libelle="<br><br>Allocation AAA<br>";}
		Widget($libelle,"Outils/PlanningV2/RepartitionAAA.php?Menu=".$_GET['Menu'],"RH/Camembert.png","#fbb161");
		
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Répartition AAA semaine<br>";}else{$libelle="<br>AAA week distribution<br>";}
		Widget($libelle,"Outils/PlanningV2/RepartitionAAASemaine.php?Menu=".$_GET['Menu'],"RH/Camembert.png","#3098f6");
		
		if($_SESSION["Langue"]=="FR"){$libelle="<br><br>Paramétrage des coûts<br>";}else{$libelle="<br><br>Cost setting<br>";}
		Widget($libelle,"Outils/PlanningV2/ParametrageCout.php?Menu=".$_GET['Menu'],"RH/Parametrage.png","#fff927");
		
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Centre de coût du personnel<br>";}else{$libelle="<br>Personnel cost center<br>";}
		Widget($libelle,"Outils/PlanningV2/Liste_CentreCoutPersonne.php?Menu=".$_GET['Menu'],"personnes.png","#da94d0");
	?>
		</td>
	</tr>
	<?php
		}
		elseif($_GET['Menu']==12){
	?>
	<tr>
		<td colspan="8" align="center" style="width:100%">
	<?php	
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Visualisation des plannings<br>";}else{$libelle="<br>Visualization of schedules<br>";}
		Widget($libelle,"Outils/PlanningV2/PlanningGlobal.php?Menu=".$_GET['Menu'],"RH/Planning.png","#93d7cd");
	?>
		</td>
	</tr>
	<?php
		}
		elseif($_GET['Menu']==13){
	?>
	<tr>
		<td colspan="8" align="center" style="width:100%">
	<?php
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Etat civil<br>";}else{$libelle="<br>Civil status<br>";}
		Widget($libelle,"Outils/PlanningV2/EtatCivil.php?Menu=".$_GET['Menu'],"RH/Personne.png","#7beeef");
	?>
		</td>
	</tr>
	<?php
		}
		elseif($_GET['Menu']==15){
		?>
		<tr>
			<td colspan="8" align="center" style="width:100%">
		<?php
		
		
			if($_SESSION["Langue"]=="FR"){$libelle="<br>Importer nouveau personnel<br>";}else{$libelle="<br>Import new personnel<br>";}
			Widget($libelle,"Outils/PlanningV2/Import_Personne.php?Menu=".$_GET['Menu'],"RH/Personne2.png","#87ceff");

		?>
			</td>
		</tr>
		<?php
			}
		elseif($_GET['Menu']==14){
	?>
	<tr>
		<td colspan="8" align="center" style="width:100%">
	<?php
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Visualisation des plannings<br>";}else{$libelle="<br>Visualization of schedules<br>";}
		Widget($libelle,"Outils/PlanningV2/PlanningGlobal.php?Menu=".$_GET['Menu'],"RH/Planning.png","#93d7cd");
		
		if($_SESSION["Langue"]=="FR"){$libelle="Suivi des entrées";}else{$libelle="Entry tracking";}
		Widget($libelle,"Outils/PlanningV2/TurnOverHSE.php?Menu=".$_GET['Menu'],"RH/Contrat.png","#a988b2");
		
		if($_SESSION["Langue"]=="FR"){$libelle="Personnes formées à la formation SST";}else{$libelle="People trained in OSH training";}
		Widget($libelle,"Outils/PlanningV2/PersonnesFormeesPrestations.php?Menu=".$_GET['Menu'],"Formation/Evaluation.png","#4ce543");
		
		if($_SESSION["Langue"]=="FR"){$libelle="<br>Accidents de travail<br>";}else{$libelle="<br>Accidents at work<br>";}
		Widget($libelle,"Outils/PlanningV2/Liste_AT.php?Menu=".$_GET['Menu'],"RH/Accident.png?Menu=".$_GET['Menu'],"#ff1111");
		
		if($_SESSION["Langue"]=="FR"){$libelle="<br><br>Répartition AAA<br>";}else{$libelle="<br><br>Allocation AAA<br>";}
		Widget($libelle,"Outils/PlanningV2/RepartitionAAA.php?Menu=".$_GET['Menu'],"RH/Camembert.png","#fbb161");
	?>
		</td>
	</tr>
	<?php
		}
		elseif($_GET['Menu']==5){
			$annee=$_SESSION['FiltreRHPlanning_Annee'];
			if(isset($_GET['Annee'])){$annee=$_GET['Annee'];}
			if($annee==""){$annee=date("Y");}
			$_SESSION['FiltreRHPlanning_Annee']=$annee;
	?>
	<tr>
		<td colspan="8" align="center" style="width:100%">
			<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr><td height="4"></td></tr>
				<tr><td align="center" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Année :";}else{echo "Year :";} ?>
				<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
				&nbsp;&nbsp;
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer(5);"/> 
				<div id="filtrer"></div>
				</td></tr>
				<tr><td height="4"></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="8" align="center" style="width:100%">
		<?php require "PlanningPersonnel.php"; ?>
		</td>
	</tr>
	<?php
		}
		elseif($_GET['Menu']==6){
	?>
	<tr>
		<td colspan="8" align="center" style="width:100%">
	<?php
			echo "<table>";
			echo "<tr>";
			echo "<td valign='top'>";
			/***********************ACCIDENT DE TRAVAIL ********************************/
			if($_SESSION["Langue"]=="FR"){$libelle="Accident de travail";}else{$libelle="Work accident";}
			echo "
			<table style='border-spacing: 0px;display:inline-table;' >
				<tr>
					<td style=\"width:200px;height:20px;border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='#ff1111'>
						".$libelle."
					</td>
				</tr>
				<tr>
					<td height='2px'></td>
				</tr>
				<tr>
					<td bgcolor='#ff1111' height='2px'></td>
				</tr>
				<tr>
					<td style=\"width:130px;height:110px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" valign='top' bgcolor='#ff1111'>";
					
			if($_SESSION["Langue"]=="FR"){$libelle="Lieu";}else{$libelle="Place";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_LieuAT.php' >
					".$libelle."
					</a><br>";
			
			if($_SESSION["Langue"]=="FR"){$libelle="Nature des lésions";}else{$libelle="Nature of lesions";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_NatureLesionAT.php' >
					".$libelle."
					</a><br>";
			
			if($_SESSION["Langue"]=="FR"){$libelle="Siège des lésions";}else{$libelle="Siege of lesions";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_SiegeLesionAT.php' >
					".$libelle."
					</a><br>";
					
			if($_SESSION["Langue"]=="FR"){$libelle="Type objet";}else{$libelle="Object type";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_TypeObjetAT.php' >
					".$libelle."
					</a><br>";
			echo "	</td>
				</tr>
			</table><br><br>";
			
			/***********************DODM ********************************/
			/*if($_SESSION["Langue"]=="FR"){$libelle="DODM";}else{$libelle="DODM";}
			echo "
			<table style='border-spacing: 0px;display:inline-table;' >
				<tr>
					<td style=\"width:200px;height:20px;border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='#a9e99d'>
						".$libelle."
					</td>
				</tr>
				<tr>
					<td height='2px'></td>
				</tr>
				<tr>
					<td bgcolor='#a9e99d' height='2px'></td>
				</tr>
				<tr>
					<td style=\"width:130px;height:110px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" valign='top' bgcolor='#a9e99d'>";
					
			if($_SESSION["Langue"]=="FR"){$libelle="Type de besoins";}else{$libelle="Type of needs";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_TypeBesoin.php' >
					".$libelle."
					</a><br>";
			echo "	</td>
				</tr>
			</table><br><br>";
			*/
			
			/***********************VACATIONS ********************************/
			if($_SESSION["Langue"]=="FR"){$libelle="Vacation";}else{$libelle="Vacation";}
			echo "
			<table style='border-spacing: 0px;display:inline-table;' >
				<tr>
					<td style=\"width:200px;height:20px;border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='#ffffff'>
						".$libelle."
					</td>
				</tr>
				<tr>
					<td height='2px'></td>
				</tr>
				<tr>
					<td bgcolor='#ffffff' height='2px'></td>
				</tr>
				<tr>
					<td style=\"width:130px;height:110px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" valign='top' bgcolor='#ffffff'>";
					
			if($_SESSION["Langue"]=="FR"){$libelle="Prestations";}else{$libelle="Sites";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_PrestationVacation.php' >
					".$libelle."
					</a><br>";
					
			echo "	</td>
				</tr>
			</table><br><br>";
			
			/***********************VM ********************************/
			if($_SESSION["Langue"]=="FR"){$libelle="Visites médicales";}else{$libelle="Medical visits";}
			echo "
			<table style='border-spacing: 0px;display:inline-table;' >
				<tr>
					<td style=\"width:200px;height:20px;border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='#cdcc8d'>
						".$libelle."
					</td>
				</tr>
				<tr>
					<td height='2px'></td>
				</tr>
				<tr>
					<td bgcolor='#cdcc8d' height='2px'></td>
				</tr>
				<tr>
					<td style=\"width:130px;height:110px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" valign='top' bgcolor='#cdcc8d'>";
			if($_SESSION["Langue"]=="FR"){$libelle="Périodicité / métier";}else{$libelle="Frequency / profession";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_PeriodiciteVM.php' >
					".$libelle."
					</a><br>";
			if($_SESSION["Langue"]=="FR"){$libelle="SMR";}else{$libelle="SMR";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_SMR.php' >
					".$libelle."
					</a><br>";
			if($_SESSION["Langue"]=="FR"){$libelle="Type de visite";}else{$libelle="Type of visit";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_TypeVM.php' >
					".$libelle."
					</a><br>";
			echo "	</td>
				</tr>
			</table>";
			
			echo "</td>";
			echo "<td valign='top'>";
			
			/***********************ASTREINTES ********************************/
			if($_SESSION["Langue"]=="FR"){$libelle="Astreintes";}else{$libelle="On-call";}
			echo "
			<table style='border-spacing: 0px;display:inline-table;' >
				<tr>
					<td style=\"width:200px;height:20px;border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='#6fb543'>
						".$libelle."
					</td>
				</tr>
				<tr>
					<td height='2px'></td>
				</tr>
				<tr>
					<td bgcolor='#6fb543' height='2px'></td>
				</tr>
				<tr>
					<td style=\"width:130px;height:110px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" valign='top' bgcolor='#6fb543'>";
					
			if($_SESSION["Langue"]=="FR"){$libelle="Barèmes / Jour";}else{$libelle="Scales / Day";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_BaremeAstreinte.php' >
					".$libelle."
					</a><br>";
			
			if($_SESSION["Langue"]=="FR"){$libelle="Raisons de refus";}else{$libelle="Reasons for refusal";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_RaisonRefusAstreinte.php' >
					".$libelle."
					</a><br>";
			echo "	</td>
				</tr>
			</table><br><br>";
			
			/***********************HEURES SUPP ********************************/
			if($_SESSION["Langue"]=="FR"){$libelle="Heures supplémentaires";}else{$libelle="Overtime";}
			echo "
			<table style='border-spacing: 0px;display:inline-table;' >
				<tr>
					<td style=\"width:200px;height:20px;border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='#11b9a7'>
						".$libelle."
					</td>
				</tr>
				<tr>
					<td height='2px'></td>
				</tr>
				<tr>
					<td bgcolor='#11b9a7' height='2px'></td>
				</tr>
				<tr>
					<td style=\"width:130px;height:110px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" valign='top' bgcolor='#11b9a7'>";
					
			if($_SESSION["Langue"]=="FR"){$libelle="Raisons de refus";}else{$libelle="Reasons for refusal";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_RaisonRefusHS.php' >
					".$libelle."
					</a><br>";
			echo "	</td>
				</tr>
			</table><br><br>";

			echo "</td>";
			echo "<td valign='top'>";
			
			/***********************CONGES / ABSENCES ********************************/
			if($_SESSION["Langue"]=="FR"){$libelle="Congés / Absences";}else{$libelle="Leave / Absence";}
			echo "
			<table style='border-spacing: 0px;display:inline-table;' >
				<tr>
					<td style=\"width:200px;height:20px;border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='#edf430'>
						".$libelle."
					</td>
				</tr>
				<tr>
					<td height='2px'></td>
				</tr>
				<tr>
					<td bgcolor='#edf430' height='2px'></td>
				</tr>
				<tr>
					<td style=\"width:130px;height:110px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" valign='top' bgcolor='#edf430'>";
			/*if($_SESSION["Langue"]=="FR"){$libelle="Fonctions représentatives";}else{$libelle="Representative functions";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_FonctionRepresentative.php' >
					".$libelle."
					</a><br>";
					
			if($_SESSION["Langue"]=="FR"){$libelle="Jours d'alertes";}else{$libelle="Alert days";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_JourAlerte.php' >
					".$libelle."
					</a><br>";*/
					
			if($_SESSION["Langue"]=="FR"){$libelle="Jours fixes";}else{$libelle="Fixed days";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_JourFixe.php' >
					".$libelle."
					</a><br>";
			
			if($_SESSION["Langue"]=="FR"){$libelle="Raisons de refus";}else{$libelle="Reasons for refusal";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_RaisonRefusConges.php' >
					".$libelle."
					</a><br>";
					
			if($_SESSION["Langue"]=="FR"){$libelle="Types d'absences";}else{$libelle="Types of absences";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_TypeAbsence.php' >
					".$libelle."
					</a><br>";
					
			if($_SESSION["Langue"]=="FR"){$libelle="Prestations";}else{$libelle="Sites";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_Prestation.php' >
					".$libelle."
					</a><br>";
			echo "	</td>
				</tr>
			</table><br><br>";
			
			/***********************MOUVEMENT DE PERSONNEL ********************************/
			if($_SESSION["Langue"]=="FR"){$libelle="Mouvement de personnel";}else{$libelle="Staff movement";}
			echo "
			<table style='border-spacing: 0px;display:inline-table;' >
				<tr>
					<td style=\"width:200px;height:20px;border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='#1365b6'>
						".$libelle."
					</td>
				</tr>
				<tr>
					<td height='2px'></td>
				</tr>
				<tr>
					<td bgcolor='#1365b6' height='2px'></td>
				</tr>
				<tr>
					<td style=\"width:130px;height:110px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" valign='top' bgcolor='#1365b6'>";
					
			if($_SESSION["Langue"]=="FR"){$libelle="Raisons de refus";}else{$libelle="Reasons for refusal";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_RaisonRefusMouvementPersonnel.php' >
					".$libelle."
					</a><br>";
			echo "	</td>
				</tr>
			</table>";
			
			echo "</td>";
			echo "<td valign='top'>";
			
			/***********************CONTRAT ********************************/
			if($_SESSION["Langue"]=="FR"){$libelle="Contrats";}else{$libelle="Contracts";}
			echo "
			<table style='border-spacing: 0px;display:inline-table;' >
				<tr>
					<td style=\"width:200px;height:20px;border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='#a988b2'>
						".$libelle."
					</td>
				</tr>
				<tr>
					<td height='2px'></td>
				</tr>
				<tr>
					<td bgcolor='#a988b2' height='2px'></td>
				</tr>
				<tr>
					<td style=\"width:130px;height:110px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" valign='top' bgcolor='#a988b2'>";
					
			if($_SESSION["Langue"]=="FR"){$libelle="Agence d'interim";}else{$libelle="Acting Agency";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_AgenceInterim.php' >
					".$libelle."
					</a><br>";
			
			if($_SESSION["Langue"]=="FR"){$libelle="Client";}else{$libelle="Client";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_Client.php' >
					".$libelle."
					</a><br>";
			
			if($_SESSION["Langue"]=="FR"){$libelle="Classification métier";}else{$libelle="Business Classification";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_ClassificationMetier.php' >
					".$libelle."
					</a><br>";
					
			if($_SESSION["Langue"]=="FR"){$libelle="Fiche emploi";}else{$libelle="Job sheet";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_FicheEmploi.php' >
					".$libelle."
					</a><br>";
			
			if($_SESSION["Langue"]=="FR"){$libelle="Groupe métier";}else{$libelle="Job Group";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_GroupeMetier.php' >
					".$libelle."
					</a><br>";
					
			if($_SESSION["Langue"]=="FR"){$libelle="Lieu de travail";}else{$libelle="Workplace";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_LieuTravail.php' >
					".$libelle."
					</a><br>";
			
			if($_SESSION["Langue"]=="FR"){$libelle="Métier";}else{$libelle="Job";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_Metier.php' >
					".$libelle."
					</a><br>";
			
			if($_SESSION["Langue"]=="FR"){$libelle="Moyen de déplacement";}else{$libelle="Moving means";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_MoyenDeplacement.php' >
					".$libelle."
					</a><br>";
			
			if($_SESSION["Langue"]=="FR"){$libelle="Motif de sortie";}else{$libelle="Exit reason";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_MotifSortie.php' >
					".$libelle."
					</a><br>";
			
			if($_SESSION["Langue"]=="FR"){$libelle="Pôle (adresse)";}else{$libelle="Pole (address)";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_PoleAdresse.php' >
					".$libelle."
					</a><br>";
					
			if($_SESSION["Langue"]=="FR"){$libelle="Prestation (adresse)";}else{$libelle="Site (address)";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_PrestationAdresse.php' >
					".$libelle."
					</a><br>";
			
			if($_SESSION["Langue"]=="FR"){$libelle="SMH (Grille des salaires minima hiérarchiques)";}else{$libelle="SMH (minimum wage scale)";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_SMH.php' >
					".$libelle."
					</a><br>";
					
			if($_SESSION["Langue"]=="FR"){$libelle="TAG";}else{$libelle="TAG";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_TAG.php' >
					".$libelle."
					</a><br>";
			
			if($_SESSION["Langue"]=="FR"){$libelle="Temps de travail";}else{$libelle="Work time";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_TempsTravail.php' >
					".$libelle."
					</a><br>";
					
			if($_SESSION["Langue"]=="FR"){$libelle="Type de contrat";}else{$libelle="Type of Contract";}
			echo "	&nbsp;&bull;&nbsp;<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:left;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" 
					href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Liste_TypeContrat.php' >
					".$libelle."
					</a><br>";
					
			echo "	</td>
				</tr>
			</table>";
			
			echo "</td>";
			
			echo "</tr>";
			echo "</table>";
	?>
		</td>
	</tr>
	<?php
		}
	?>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>
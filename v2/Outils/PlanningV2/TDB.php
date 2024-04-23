<?php 
require_once("Fonctions_Planning.php");

ReaffecterDemandeConge();

function WidgetTDB($Libelle,$Image,$Couleur,$CouleurLogo,$nb,$Lien){
	$couleurNombre="";
	if($nb<>"0" && $nb<>"0/0"){$couleurNombre="color:#de0006;";}
	echo "
		<table style='border-spacing: 25px;display:inline-table;' >
			<tr>
				<td style=\"width:230px;height:90px;border-style:outset;border-color:".$Couleur.";border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='".$Couleur."'>
					<table width='100%' height='100%'>
						<tr>
							<td style=\"width:30%;height:100%;text-align:center;color:black;valign:top;font-weight:bold;\" rowspan='2' bgcolor='".$CouleurLogo."'>
								<a style=\"text-decoration:none;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >
								<img width='40px' src='../../Images/".$Image."' border='0' />
								</a>
							</td>
							<td width='70%' style='font-size:32px;".$couleurNombre."'>
								".$nb."
							</td>
						</tr>
						<tr>
							<td>
								".$Libelle."
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>";
}

function TitreTDB($Libelle,$Lien,$Selected){
	$tiret="";
	$couleurTexte="#00577c";
	if($Selected==true){$tiret="border-bottom:6px solid #6EB4CD;font-style:italic;font-size:16px;";$couleurTexte="#6bd4ff";}
	echo "<td style=\"width:70px;height:30px;border-spacing:0;text-align:center;color:".$couleurTexte.";valign:top;font-weight:bold;".$tiret."\">
		<a style=\"text-decoration:none;width:70px;height:30px;border-spacing:0;text-align:center;color:".$couleurTexte.";valign:top;font-weight:bold;\" onmouseover=\"this.style.color='".$couleurTexte."';\" onmouseout=\"this.style.color='".$couleurTexte."';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle."</a></td>\n";
}	
?>
<table align="center" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td height="10"></td>
	</tr>
	<tr bgcolor="white">
<?php
	if((DroitsFormationPlateforme(array($IdPosteResponsablePlateforme)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation)) || DroitsFormationPlateforme($TableauIdPostesRH)){
		if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation)){
			$select=true;
			if($_SESSION["Langue"]=="FR"){TitreTDB("GESTION DES EQUIPES","Outils/PlanningV2/Tableau_De_Bord.php?Menu=1&OngletTDB=Manager",$select);}
			else{TitreTDB("TEAM MANAGEMENT","Outils/PlanningV2/Tableau_De_Bord.php?Menu=1&OngletTDB=RH",$select);}
		}
		if(DroitsFormationPlateforme($TableauIdPostesRH) || DroitsFormationPlateforme(array($IdPosteOperateurSaisieRH))){
			$select=false;
			if($_SESSION["Langue"]=="FR"){TitreTDB("ADMINISTRATION DU PERSONNEL","Outils/PlanningV2/Tableau_De_Bord.php?Menu=1&OngletTDB=RH",$select);}
			else{TitreTDB("PERSONNEL ADMINISTRATION","Outils/PlanningV2/Tableau_De_Bord.php?Menu=1&OngletTDB=RH",$select);}
		}
	}
?>
	</tr>
	<tr><td height="5"></td></tr>
	<tr>
		<td colspan="2" align="right">
			<a class="LigneTableauRecherchePersonne" onclick="document.getElementById('stopAlerte').value=1;xhr1.abort();window.stop();" style="cursor:pointer;" href="<?php echo $_SESSION['HTTP'];?>://<?php echo $_SERVER['SERVER_NAME'];?>/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=1&OngletTDB=Manager" ><img src="../../Images/refresh.png" style="width:18px;" border="0" title="Refresh" alt="Refresh"></a>&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<?php 
		$nb=NombreDemandesCongesAValider48H($_SESSION['Id_Personne'],1);
		if($nb>0){
	?>
	<tr>
		<td colspan="8" align="center" style="width:100%;text-align:center;valign:top;font-weight:bold;color:#e9152a;font-size:20px;">
			<?php if($_SESSION["Langue"]=="FR"){echo "<img width='25px' src='../../Images/attention.png'/> Des congés dans - 48h sont à valider";}else{echo "<img width='25px' src='../../Images/attention.png'/> Leave in - 48h are to be validated";} ?>
		</td>
	</tr>
	<?php
		}
	?>
	<tr>
		<td colspan="2" align="center">
<?php 
$nb=NombreDemandesCongesAValider($_SESSION['Id_Personne'],1);
if($_SESSION["Langue"]=="FR"){$libelle="Demandes de congés à valider";}else{$libelle="Leave request to be validated";}
WidgetTDB($libelle,"RH/Palmier.png","#f3f87a","#edf430",$nb,"Outils/PlanningV2/Liste_DemandeConges.php?Menu=3&TDB=1&OngletTDB=Manager");

$nb=NombreRapportsAstreinteAValider($_SESSION['Id_Personne'],1);
if($_SESSION["Langue"]=="FR"){$libelle="Rapports d'astreintes à valider";}else{$libelle="On-call reports to be validated";}
WidgetTDB($libelle,"RH/Astreinte.png","#c1e1ad","#6fb543",$nb,"Outils/PlanningV2/Liste_DemandeAstreinte.php?Menu=3&TDB=1&OngletTDB=Manager");

$nb=NombreHeuresSuppAValider($_SESSION['Id_Personne'],1);
if($_SESSION["Langue"]=="FR"){$libelle="Demandes d'heures supplémentaires à valider";}else{$libelle="Requests for overtime to be validated";}
WidgetTDB($libelle,"RH/Chrono.png","#7df3e6","#11b9a7",$nb,"Outils/PlanningV2/Liste_HeureSupp.php?Menu=3&TDB=1&OngletTDB=Manager");

$nb=NombreTransfertECArriveeManager($_SESSION['Id_Personne'])."/".NombreTransfertECDepartManager($_SESSION['Id_Personne']);
if($_SESSION["Langue"]=="FR"){$libelle="Transferts en cours<br>Arrivées/Départs";}else{$libelle="Transfer in progress<br>Arrivals / Departures";}
WidgetTDB($libelle,"RH/Transfert.png","#94c4f4","#1365b6",$nb,"Outils/PlanningV2/Liste_MouvementPersonnel.php?Menu=3&TDB=1&OngletTDB=Manager");

$nb=NombreAbsAPrendreEnCompte($_SESSION['Id_Personne'],1);
if($_SESSION["Langue"]=="FR"){$libelle="Absences injustifiées";}else{$libelle="Unjustified absences";}
WidgetTDB($libelle,"RH/Absence.png","#fbbfda","#f561a4",$nb,"Outils/PlanningV2/Liste_AbsencesInjustifiees.php?Menu=3&TDB=1&OngletTDB=Manager");	

//Uniquement sur les plateformes utilisant la gestion des formations (GR6)

$nb=NombreAbsencesFormationAValider();
if($_SESSION["Langue"]=="FR"){$libelle="Absences en formation";}else{$libelle="Absences in formation";}
WidgetTDB($libelle,"RH/classe.png","#e7aded","#9923a6",$nb,"Outils/PlanningV2/Liste_AbsencesFormation.php?Menu=3&TDB=1&OngletTDB=Manager");
echo "</td>";
?>
	</tr>
	<tr><td height="4"></td></tr>
</table>
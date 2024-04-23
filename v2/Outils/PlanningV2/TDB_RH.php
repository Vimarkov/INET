<?php 
require_once("Fonctions_Planning.php");

ReaffecterDemandeConge();

function WidgetTDB($Libelle,$Image,$Couleur,$CouleurLogo,$nb,$Lien,$Val = ""){
	$couleurNombre="";
	if($nb<>"?" && $nb<>"0" && $nb<>"0/0"){$couleurNombre="color:#de0006;";}
	echo "
		<table style='border-spacing: 25px;display:inline-table;' onclick=\"document.getElementById('stopAlerte').value=1;xhr1.abort();window.stop();\">
			<tr>
				<td style=\"width:230px;height:90px;border-style:outset;border-color:".$Couleur.";border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='".$Couleur."'>
					<table width='100%' height='100%'>
						<tr>
							<td style=\"width:30%;height:100%;text-align:center;color:black;valign:top;font-weight:bold;\" rowspan='2' bgcolor='".$CouleurLogo."'>
								<a style=\"text-decoration:none;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >
								<img width='40px' src='../../Images/".$Image."' border='0' />
								</a>
							</td>
							<td width='70%' style='font-size:32px;".$couleurNombre."' id='".$Val."'>
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
	$_SESSION['AlerteHS']='';
	$_SESSION['AlerteJourAlerte']='';
	
	if((DroitsFormationPlateforme(array($IdPosteResponsablePlateforme)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation)) || DroitsFormationPlateforme($TableauIdPostesRH)){
		if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation)){
			$select=false;
			if($_SESSION["Langue"]=="FR"){TitreTDB("GESTION DES EQUIPES","Outils/PlanningV2/Tableau_De_Bord.php?Menu=1&OngletTDB=Manager",$select);}
			else{TitreTDB("TEAM MANAGEMENT","Outils/PlanningV2/Tableau_De_Bord.php?Menu=1&OngletTDB=RH",$select);}
		}
		if(DroitsFormationPlateforme($TableauIdPostesRH) || DroitsFormationPlateforme(array($IdPosteOperateurSaisieRH))){
			$select=true;
			if($_SESSION["Langue"]=="FR"){TitreTDB("ADMINISTRATION DU PERSONNEL","Outils/PlanningV2/Tableau_De_Bord.php?Menu=1&OngletTDB=RH",$select);}
			else{TitreTDB("PERSONNEL ADMINISTRATION","Outils/PlanningV2/Tableau_De_Bord.php?Menu=1&OngletTDB=RH",$select);}
		}
	}
?>
	</tr>
	<tr><td height="5"></td></tr>
	<tr>
		<td colspan="2" align="right">
			<a class="LigneTableauRecherchePersonne" onclick="document.getElementById('stopAlerte').value=1;xhr1.abort();window.stop();" style="cursor:pointer;" href="<?php echo $_SESSION['HTTP'];?>://<?php echo $_SERVER['SERVER_NAME'];?>/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=1&OngletTDB=RH" ><img src="../../Images/refresh.png" style="width:18px;" border="0" title="Refresh" alt="Refresh"></a>&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
<?php 
if(DroitsFormationPlateforme($TableauIdPostesRH) || DroitsFormationPlateforme(array($IdPosteOperateurSaisieRH))){
	$nb=NombreDemandesCongesAValider($_SESSION['Id_Personne'],0);
	if($_SESSION["Langue"]=="FR"){$libelle="Demandes de congés à traiter";}else{$libelle="Request for leave to be processed";}
	WidgetTDB($libelle,"RH/Palmier.png","#f3f87a","#edf430",$nb,"Outils/PlanningV2/Liste_DemandeConges.php?Menu=4&TDB=1&OngletTDB=RH");
	
	$nb=NombreDemandesSansAffectation();
	if($_SESSION["Langue"]=="FR"){$libelle="Demandes sans affectation";}else{$libelle="Unassigned requests";}
	WidgetTDB($libelle,"RH/Palmier.png","#f3f87a","#edf430",$nb,"Outils/PlanningV2/Liste_DemandesSansAffectation.php?Menu=4&TDB=1&OngletTDB=RH");
	
	$nb=NombreRapportsAstreinteAValider($_SESSION['Id_Personne'],0);
	if($_SESSION["Langue"]=="FR"){$libelle="Rapports d'astreintes à traiter";}else{$libelle="On-call reports to be processed";}
	WidgetTDB($libelle,"RH/Astreinte.png","#c1e1ad","#6fb543",$nb,"Outils/PlanningV2/Liste_DemandeAstreinte.php?Menu=4&TDB=1&OngletTDB=RH");
	
	$nb=NombreHeuresSuppAValider($_SESSION['Id_Personne'],0);
	if($_SESSION["Langue"]=="FR"){$libelle="Demandes d'heures supplémentaires à traiter";}else{$libelle="Requests for overtime to be processed";}
	WidgetTDB($libelle,"RH/Chrono.png","#7df3e6","#11b9a7",$nb,"Outils/PlanningV2/Liste_HeureSupp.php?Menu=4&TDB=1&OngletTDB=RH");
	
	if($_SESSION["Langue"]=="FR"){
		$MoisLettre = array("Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Decembre");
	}
	else{
		$MoisLettre = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	}
	
	$nb="?";
	if($_SESSION["Langue"]=="FR"){$libelle="Alertes heures supplémentaires (".$MoisLettre[date('m')-1].")";}else{$libelle="Overtime alerts (".$MoisLettre[date('m')-1].")";}
	WidgetTDB($libelle,"RH/Chrono.png","#7df3e6","#11b9a7",$nb,"Outils/PlanningV2/Liste_HeureSuppAlerte.php?Menu=4&TDB=1&OngletTDB=RH","NbAlerteHS");
	
	$nb=NombreTransfertECRH($_SESSION['Id_Personne']);
	if($_SESSION["Langue"]=="FR"){$libelle="Transferts en cours";}else{$libelle="Transfer in progress";}
	WidgetTDB($libelle,"RH/Transfert.png","#94c4f4","#1365b6",$nb,"Outils/PlanningV2/Liste_MouvementPersonnelRH.php?Menu=4&TDB=1&OngletTDB=RH&TypeTri=EC");
	
	/*
	$nb=NombreMouvementHorsPlateformeRH($_SESSION['Id_Personne']);
	if($_SESSION["Langue"]=="FR"){$libelle="Transferts hors TLS";}else{$libelle="Transfers off TLS";}
	WidgetTDB($libelle,"RH/Transfert.png","#94c4f4","#1365b6",$nb,"Outils/PlanningV2/Liste_MouvementPersonnelRH.php?Menu=4&TDB=1&OngletTDB=RH&Type=HorsPlat");
	*/
	
	
	$nb=NombreAbsAPrendreEnCompte($_SESSION['Id_Personne'],0);
	if($_SESSION["Langue"]=="FR"){$libelle="Nouvelles absences";}else{$libelle="New absences";}
	WidgetTDB($libelle,"RH/Absence.png","#fbbfda","#f561a4",$nb,"Outils/PlanningV2/Liste_AbsencesInjustifiees.php?Menu=4&TDB=1&OngletTDB=RH");
	
	$nb="?";
	if($_SESSION["Langue"]=="FR"){$libelle="Formations hors vacation";}else{$libelle="Non-vacation training";}
	WidgetTDB($libelle,"RH/classe.png","#e7aded","#9923a6",$nb,"Outils/PlanningV2/Liste_FormationsHorsVacation.php?Menu=4&TDB=1&OngletTDB=RH","FormHorsVacation");
	
	$nb="?";
	if($_SESSION["Langue"]=="FR"){$libelle="Anomalies calcul des heures";}else{$libelle="Anomalies calculating hours";}
	WidgetTDB($libelle,"RH/classe.png","#e7aded","#9923a6",$nb,"Outils/PlanningV2/Liste_FormationsAnomalies.php?Menu=4&TDB=1&OngletTDB=RH","AnomalieForm");
	
	$nb=NombreVacationAPrendreEnCompte();
	if($_SESSION["Langue"]=="FR"){$libelle="Changements de vacations aprés le 20 du mois";}else{$libelle="Changes in vacations after the 20th of the month";}
	WidgetTDB($libelle,"RH/Vacation.jpg","#ffffff","#f0f0f0",$nb,"Outils/PlanningV2/Liste_VacationsAPrendreEnCompte.php?Menu=4&TDB=1&OngletTDB=RH","VacationsEnCompte");
	
	/*
	$nb=NombreVacationJourAlerte();
	if($_SESSION["Langue"]=="FR"){$libelle="En vacation un jour d'alerte (".$MoisLettre[date('m')-1].")";}else{$libelle="On vacation on an alert day (".$MoisLettre[date('m')-1].")";}
	WidgetTDB($libelle,"RH/Vacation.jpg","#ffffff","#f0f0f0",$nb,"Outils/PlanningV2/Liste_VacationJourAlerte.php?Menu=4&TDB=1&OngletTDB=RH","NbAlerteJourAlerte");
	*/
	$nb=NombrePeriodeEssaiLimite();
	if($_SESSION["Langue"]=="FR"){$libelle="Fins de périodes d'essai dans moins de 3 mois";}else{$libelle="End of trial periods in less than 3 months";}
	WidgetTDB($libelle,"RH/Contrat.png","#cfbcd4","#a988b2",$nb,"Outils/PlanningV2/Liste_ContratPeriodeEssai.php?Menu=4&TDB=1&OngletTDB=RH");
	
	$nb=NombreContrat18Mois3Semaines();
	if($_SESSION["Langue"]=="FR"){$libelle="18 mois dans moins de 3 semaines";}else{$libelle="18 months in less than 3 weeks";}
	WidgetTDB($libelle,"RH/Contrat.png","#cfbcd4","#a988b2",$nb,"Outils/PlanningV2/Liste_Contrat18Mois.php?Menu=4&TDB=1&OngletTDB=RH");
	
	$nb=NombrePetitDeplacementAPrendreEnCompte($_SESSION['Id_Personne'],0);
	if($_SESSION["Langue"]=="FR"){$libelle="Demandes de petit déplacement ponctuel à traiter";}else{$libelle="Requests for small, one-time travel to be processed";}
	WidgetTDB($libelle,"RH/Voiture.png","#a9e99d","#67d852",$nb,"Outils/PlanningV2/Liste_DODM.php?Menu=4&TDB=1&OngletTDB=RH");
	
	
	
	//ALERTE HS 
	$mois=date("m");
	$annee=date("Y");
	$dernierJourMois=date('Y-m-d',strtotime(date("Y-m-d",mktime(0,0,0,$mois,1,$annee))." last day of this month"));
	$nbTotal=0;
	
	$requete = "SELECT DISTINCT new_rh_etatcivil.Id, 
		rh_personne_mouvement.Id_Prestation, 
		rh_personne_mouvement.Id_Pole
	FROM new_rh_etatcivil
	LEFT JOIN rh_personne_mouvement 
	ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
	WHERE rh_personne_mouvement.DateDebut<='".$annee."-".$mois."-01'
	AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dernierJourMois."')
	AND rh_personne_mouvement.EtatValidation=1 
	AND rh_personne_mouvement.Id_Prestation NOT IN (87,976,977,978,979,980,981,982,983,984,985,1264,1265,1266,1267)
	AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) IN
		(
			SELECT Id_Plateforme 
			FROM new_competences_personne_poste_plateforme
			WHERE Id_Personne=".$_SESSION['Id_Personne']." 
			AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
		)
	AND (
		(
			SELECT COUNT(rh_personne_hs.Id) 
			FROM rh_personne_hs
			WHERE rh_personne_hs.Suppr=0 
			AND rh_personne_hs.Id_Personne=new_rh_etatcivil.Id
			AND IF(DateRH>'0001-01-01',DateRH,DateHS)>='".$annee."-".$mois."-01'
			AND IF(DateRH>'0001-01-01',DateRH,DateHS)<='".$dernierJourMois."'
			AND rh_personne_hs.Etat4=1
		)>0
	OR 
		(
			SELECT COUNT(rh_personne_rapportastreinte.Id) 
			FROM rh_personne_rapportastreinte
			WHERE rh_personne_rapportastreinte.Suppr=0 
			AND rh_personne_rapportastreinte.Id_Personne=new_rh_etatcivil.Id
			AND IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte)>='".$annee."-".$mois."-01'
			AND IF(DatePriseEnCompte>'0001-01-01',DatePriseEnCompte,DateAstreinte)<='".$dernierJourMois."'
			AND rh_personne_rapportastreinte.EtatN2=1
		)>0
	)
	
	ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
	$result=mysqli_query($bdd,$requete);
	$nbResulta=mysqli_num_rows($result);
	
	
	$val="";
	if($nbResulta>0){
		while($row=mysqli_fetch_array($result))
		{
			$val.=$row['Id'].";";
		}
	}
}
echo "</td>";
?>
	</tr>
	<tr><td height="4"></td></tr>
	<tr style="display:none;">
		<td><input name="AlerteHS" id="AlerteHS" value="0" />
		<td><input name="totalAlerteHS" id="totalAlerteHS" value="0" />
		<input name="stopAlerte" id="stopAlerte" value="0" /></td>
	</tr>
	<tr style="display:none;">
		<td><div id="reqAlerteHS" name="reqAlerteHS"><?php echo $val; ?></div></td>
	</tr>
</table>
<?php


	echo "<script>
		var listeId=document.getElementById('reqAlerteHS').innerHTML.split(';');
		for (var i=0; i < listeId.length; i++){
			if(listeId[i]!=''){
				if(document.getElementById('stopAlerte').value==0){
					document.getElementById('AlerteHS').value='?';
					var xhr1 = $.ajax({
						url : 'Ajax_AlertesHS.php',
						data : 'Id_Personne='+listeId[i],
						dataType : 'html',
						async : true,
						error:function(msg, string){
							},
						success:function(data){
								document.getElementById('AlerteHS').value=data;
								Nb=document.getElementById('AlerteHS').value.substring(document.getElementById('AlerteHS').value.indexOf('<NbHS>')+6,document.getElementById('AlerteHS').value.indexOf('</NbHS>'));
								document.getElementById('totalAlerteHS').value=Number(document.getElementById('totalAlerteHS').value)+Number(Nb);
								if(document.getElementById('totalAlerteHS').value==0){
									document.getElementById('NbAlerteHS').innerHTML='<div>0</div>';
								}
								else{
									document.getElementById('NbAlerteHS').innerHTML=\"<div style='color:#de0006;'>\"+document.getElementById('totalAlerteHS').value+\"</div>\";
								}
								
							}
					});

				}
			}
		}
		
		var xhr2 = $.ajax({
			url : 'Ajax_FormHorsVacation.php',
			dataType : 'html',
			async : true,
			error:function(msg, string){
				
				},
			success:function(data){
				document.getElementById('FormHorsVacation').innerHTML=data;
				}
		});
		var xhr3 = $.ajax({
			url : 'Ajax_AnomalieForm.php',
			dataType : 'html',
			async : true,
			error:function(msg, string){
				
				},
			success:function(data){
				document.getElementById('AnomalieForm').innerHTML=data;
				}
		});
	</script>
	";
?>
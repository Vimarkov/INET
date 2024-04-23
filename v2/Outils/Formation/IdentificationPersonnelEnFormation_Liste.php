<?php
require("../../Menu.php");
?>
<script>
	function Excel(){
		var w=window.open("Excel_PersonneFormation.php?Id_Prestation="+document.getElementById('Prestation').value+"&DateDebut="+document.getElementById('DateDebut').value+"&DateFin="+document.getElementById('DateFin').value+"&Id_Personne="+document.getElementById('Stagiaire').value+"&Formation="+document.getElementById('Formation').value+"&Etat="+document.getElementById('Etat').value,"PageExcel","status=no,menubar=no,scrollbars=yes,width=90,height=90");
		w.focus();
	}
	function Desinscrire(Id_Personne,Id_SessionPersonne,Id_Besoin,Langue,Id_GroupeSession,Formation_Liee){
		var message = "";
		if(Langue=="FR"){message='Etes-vous sûr de vouloir désinscrire cette personne ?';}
		else{message='Are you sure you want to unsubscribe?';}
		if(window.confirm(message)){
			var bouton = "<input style='display:none;' class='Bouton' type='submit' id='btnDesinscrire' name='btnDesinscrire' value='Desinscrire'>";
			document.getElementById('Id_PersonneDesinscription').value=Id_Personne;
			document.getElementById('Id_SessionPersonneDesinscription').value=Id_SessionPersonne;
			document.getElementById('Id_BesoinPersonneDesinscription').value=Id_Besoin;
			document.getElementById('Id_GroupeSession').value=Id_GroupeSession;
			document.getElementById('Formation_Liee').value=Formation_Liee;
			document.getElementById('Desinscrire2').innerHTML=bouton;
			var evt = document.createEvent("MouseEvents");
			evt.initMouseEvent("click", true, true, window,0, 0, 0, 0, 0, false, false, false, false, 0, null);
			document.getElementById("btnDesinscrire").dispatchEvent(evt);
			
		}
	}
</script>
<?php
if($_POST)
{
	$_SESSION['FiltrePersFormation_Prestation']=$_POST['Prestation'];
	$_SESSION['FiltrePersFormation_Personne']=$_POST['Stagiaire'];
	$_SESSION['FiltrePersFormation_Etat']=$_POST['Etat'];
	$_SESSION['FiltrePersFormation_DateDebut']=$_POST['DateDebut'];
	$_SESSION['FiltrePersFormation_DateFin']=$_POST['DateFin'];
	$_SESSION['FiltrePersFormation_Formation']=$_POST['Formation'];
	$_SESSION['FiltrePersFormation_TypeFormation']=$_POST['TypeFormation'];
}
if(isset($_GET['Tri']))
{
	if($_GET['Tri']=="Personne")
	{
		$_SESSION['TriPersFormation_General']= str_replace("Personne ASC,","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("Personne DESC,","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("Personne ASC","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("Personne DESC","",$_SESSION['TriPersFormation_General']);
		if($_SESSION['TriPersFormation_Personne']==""){$_SESSION['TriPersFormation_Personne']="ASC";$_SESSION['TriPersFormation_General'].= "Personne ".$_SESSION['TriPersFormation_Personne'].",";}
		elseif($_SESSION['TriPersFormation_Personne']=="ASC"){$_SESSION['TriPersFormation_Personne']="DESC";$_SESSION['TriPersFormation_General'].= "Personne ".$_SESSION['TriPersFormation_Personne'].",";}
		else{$_SESSION['TriPersFormation_Personne']="";}
	}
	if($_GET['Tri']=="Prestation")
	{
		$_SESSION['TriPersFormation_General']= str_replace("Prestation ASC,","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("Prestation DESC,","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("Prestation ASC","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("Prestation DESC","",$_SESSION['TriPersFormation_General']);
		if($_SESSION['TriPersFormation_Prestation']==""){$_SESSION['TriPersFormation_Prestation']="ASC";$_SESSION['TriPersFormation_General'].= "Prestation ".$_SESSION['TriPersFormation_Prestation'].",";}
		elseif($_SESSION['TriPersFormation_Prestation']=="ASC"){$_SESSION['TriPersFormation_Prestation']="DESC";$_SESSION['TriPersFormation_General'].= "Prestation ".$_SESSION['TriPersFormation_Prestation'].",";}
		else{$_SESSION['TriPersFormation_Prestation']="";}
	}
	if($_GET['Tri']=="Pole")
	{
		$_SESSION['TriPersFormation_General']= str_replace("Pole ASC,","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("Pole DESC,","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("Pole ASC","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("Pole DESC","",$_SESSION['TriPersFormation_General']);
		if($_SESSION['TriPersFormation_Pole']==""){$_SESSION['TriPersFormation_Pole']="ASC";$_SESSION['TriPersFormation_General'].= "Pole ".$_SESSION['TriPersFormation_Pole'].",";}
		elseif($_SESSION['TriPersFormation_Pole']=="ASC"){$_SESSION['TriPersFormation_Pole']="DESC";$_SESSION['TriPersFormation_General'].= "Pole ".$_SESSION['TriPersFormation_Pole'].",";}
		else{$_SESSION['TriPersFormation_Pole']="";}
	}
	if($_GET['Tri']=="GroupeFormation")
	{
		$_SESSION['TriPersFormation_General']= str_replace("GroupeFormation ASC,","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("GroupeFormation DESC,","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("GroupeFormation ASC","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("GroupeFormation DESC","",$_SESSION['TriPersFormation_General']);
		if($_SESSION['TriPersFormation_GroupeFormation']==""){$_SESSION['TriPersFormation_GroupeFormation']="ASC";$_SESSION['TriPersFormation_General'].= "GroupeFormation ".$_SESSION['TriPersFormation_GroupeFormation'].",";}
		elseif($_SESSION['TriPersFormation_GroupeFormation']=="ASC"){$_SESSION['TriPersFormation_GroupeFormation']="DESC";$_SESSION['TriPersFormation_General'].= "GroupeFormation ".$_SESSION['TriPersFormation_GroupeFormation'].",";}
		else{$_SESSION['TriPersFormation_GroupeFormation']="";}
	}
	if($_GET['Tri']=="Lieu")
	{
		$_SESSION['TriPersFormation_General']= str_replace("Lieu ASC,","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("Lieu DESC,","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("Lieu ASC","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("Lieu DESC","",$_SESSION['TriPersFormation_General']);
		if($_SESSION['TriPersFormation_Lieu']==""){$_SESSION['TriPersFormation_Lieu']="ASC";$_SESSION['TriPersFormation_General'].= "Lieu ".$_SESSION['TriPersFormation_Lieu'].",";}
		elseif($_SESSION['TriPersFormation_Lieu']=="ASC"){$_SESSION['TriPersFormation_Lieu']="DESC";$_SESSION['TriPersFormation_General'].= "Lieu ".$_SESSION['TriPersFormation_Lieu'].",";}
		else{$_SESSION['TriPersFormation_Lieu']="";}
	}
	if($_GET['Tri']=="DateDebut")
	{
		$_SESSION['TriPersFormation_General']= str_replace("DateDebut ASC,","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("DateDebut DESC,","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("DateDebut ASC","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("DateDebut DESC","",$_SESSION['TriPersFormation_General']);
		if($_SESSION['TriPersFormation_DateDebut']==""){$_SESSION['TriPersFormation_DateDebut']="ASC";$_SESSION['TriPersFormation_General'].= "DateDebut ".$_SESSION['TriPersFormation_DateDebut'].",";}
		elseif($_SESSION['TriPersFormation_DateDebut']=="ASC"){$_SESSION['TriPersFormation_DateDebut']="DESC";$_SESSION['TriPersFormation_General'].= "DateDebut ".$_SESSION['TriPersFormation_DateDebut'].",";}
		else{$_SESSION['TriPersFormation_DateDebut']="";}
	}
	if($_GET['Tri']=="DateFin")
	{
		$_SESSION['TriPersFormation_General']= str_replace("DateFin ASC,","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("DateFin DESC,","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("DateFin ASC","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("DateFin DESC","",$_SESSION['TriPersFormation_General']);
		if($_SESSION['TriPersFormation_DateFin']==""){$_SESSION['TriPersFormation_DateFin']="ASC";$_SESSION['TriPersFormation_General'].= "DateFin ".$_SESSION['TriPersFormation_DateFin'].",";}
		elseif($_SESSION['TriPersFormation_DateFin']=="ASC"){$_SESSION['TriPersFormation_DateFin']="DESC";$_SESSION['TriPersFormation_General'].= "DateFin ".$_SESSION['TriPersFormation_DateFin'].",";}
		else{$_SESSION['TriPersFormation_DateFin']="";}
	}
	if($_GET['Tri']=="HeureDebut")
	{
		$_SESSION['TriPersFormation_General']= str_replace("HeureDebut ASC,","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("HeureDebut DESC,","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("HeureDebut ASC","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("HeureDebut DESC","",$_SESSION['TriPersFormation_General']);
		if($_SESSION['TriPersFormation_HeureDebut']==""){$_SESSION['TriPersFormation_HeureDebut']="ASC";$_SESSION['TriPersFormation_General'].= "HeureDebut ".$_SESSION['TriPersFormation_HeureDebut'].",";}
		elseif($_SESSION['TriPersFormation_HeureDebut']=="ASC"){$_SESSION['TriPersFormation_HeureDebut']="DESC";$_SESSION['TriPersFormation_General'].= "HeureDebut ".$_SESSION['TriPersFormation_HeureDebut'].",";}
		else{$_SESSION['TriPersFormation_HeureDebut']="";}
	}
	if($_GET['Tri']=="HeureFin")
	{
		$_SESSION['TriPersFormation_General']= str_replace("HeureFin ASC,","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("HeureFin DESC,","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("HeureFin ASC","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("HeureFin DESC","",$_SESSION['TriPersFormation_General']);
		if($_SESSION['TriPersFormation_HeureFin']==""){$_SESSION['TriPersFormation_HeureFin']="ASC";$_SESSION['TriPersFormation_General'].= "HeureFin ".$_SESSION['TriPersFormation_HeureFin'].",";}
		elseif($_SESSION['TriPersFormation_HeureFin']=="ASC"){$_SESSION['TriPersFormation_HeureFin']="DESC";$_SESSION['TriPersFormation_General'].= "HeureFin ".$_SESSION['TriPersFormation_HeureFin'].",";}
		else{$_SESSION['TriPersFormation_HeureFin']="";}
	}
	if($_GET['Tri']=="Duree")
	{
		$_SESSION['TriPersFormation_General']= str_replace("Duree ASC,","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("Duree DESC,","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("Duree ASC","",$_SESSION['TriPersFormation_General']);
		$_SESSION['TriPersFormation_General']= str_replace("Duree DESC","",$_SESSION['TriPersFormation_General']);
		if($_SESSION['TriPersFormation_Duree']==""){$_SESSION['TriPersFormation_Duree']="ASC";$_SESSION['TriPersFormation_General'].= "Duree ".$_SESSION['TriPersFormation_Duree'].",";}
		elseif($_SESSION['TriPersFormation_Duree']=="ASC"){$_SESSION['TriPersFormation_Duree']="DESC";$_SESSION['TriPersFormation_General'].= "Duree ".$_SESSION['TriPersFormation_Duree'].",";}
		else{$_SESSION['TriPersFormation_Duree']="";}
	}
}
Ecrire_Code_JS_Init_Date(); 

//Fonction de filtrage
global $id_prestation;
global $id_pole;
global $date_debut;
global $date_fin;
global $formation;
global $grpe_formation;
global $stagiaire;
global $etat;
global $TypeForm;

$prestation=$_SESSION['FiltrePersFormation_Prestation'];
$date_debut=$_SESSION['FiltrePersFormation_DateDebut'];
$date_fin=$_SESSION['FiltrePersFormation_DateFin'];
$formation=$_SESSION['FiltrePersFormation_Formation'];
$grpe_formation=$_SESSION['FiltrePersFormation_GroupeFormation'];
$stagiaire=$_SESSION['FiltrePersFormation_Personne'];
$etat=$_SESSION['FiltrePersFormation_Etat'];
$TypeForm=$_SESSION['FiltrePersFormation_TypeFormation'];

if(isset($_POST['btnDesinscrire'])){
	if($_POST['Formation_Liee']==1 && $_POST['Id_GroupeSession']<>0){
		//DESINCRIRE DE TOUTES LES SESSIONS DE FORMATION LIEES
		$req="SELECT form_session_personne.Id_Besoin, form_session_personne.Id 
		FROM form_session_personne
		LEFT JOIN form_session 
		ON form_session_personne.Id_Session=form_session.Id
		WHERE form_session_personne.Suppr=0 AND form_session_personne.Id_Personne=".$_POST['Id_PersonneDesinscription']." 
		AND form_session.Id_GroupeSession=".$_POST['Id_GroupeSession'];
		$result=mysqli_query($bdd,$req);
		$nbSessionPers=mysqli_num_rows($result);
		if($nbSessionPers>0){
			while($rowSessionPers=mysqli_fetch_array($result)){
				desinscrire_candidat($rowSessionPers['Id_Besoin'],$rowSessionPers['Id']);
			}
		}
		
	}
	else{
		desinscrire_candidat($_POST['Id_BesoinPersonneDesinscription'],$_POST['Id_SessionPersonneDesinscription']);
	}
}
?>
<form id="formulaire" action="IdentificationPersonnelEnFormation_Liste.php" method="post">
	<input type="hidden" name="Formation_Liee" Id="Formation_Liee" value="">
	<input type="hidden" name="Id_GroupeSession" Id="Id_GroupeSession" value="">
	<input type="hidden" name="Id_PersonneDesinscription" id="Id_PersonneDesinscription" value="">
	<input type="hidden" name="Id_SessionPersonneDesinscription" id="Id_SessionPersonneDesinscription" value="">
	<input type="hidden" name="Id_BesoinPersonneDesinscription" id="Id_BesoinPersonneDesinscription" value="">
	
	<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#1574d0;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Personnel inscrit";}else{echo "Registered staff";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td>
			<?php echo afficherFiltres(); ?>
		</td>
	</tr>
	<tr><td height="4"></td>
	<tr>
		<td>
			<div style="width:100%;height:400px;overflow:auto;">
			<table style="width:100%; border-spacing:0; align:center;" class="GeneralInfo">
				<tr bgcolor="#2c8bb4">
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;" width="7%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="IdentificationPersonnelEnFormation_Liste.php?Tri=Prestation"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";} ?><?php if($_SESSION['TriPersFormation_Prestation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriPersFormation_Prestation']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;" width="8%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="IdentificationPersonnelEnFormation_Liste.php?Tri=Pole"><?php if($LangueAffichage=="FR"){echo "Pôle";}else{echo "Pole";} ?><?php if($_SESSION['TriPersFormation_Pole']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriPersFormation_Pole']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="border-bottom:1px dottom black;" width="10%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="IdentificationPersonnelEnFormation_Liste.php?Tri=Personne"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";} ?><?php if($_SESSION['TriPersFormation_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriPersFormation_Personne']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="6%"><?php if($LangueAffichage=="FR"){echo "Contrat";}else{echo "Contract";}?></td>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="15%"><?php if($LangueAffichage=="FR"){echo "Formation / Groupe de formation";}else{echo "Training / Training group";} ?></td>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="10%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="IdentificationPersonnelEnFormation_Liste.php?Tri=Lieu"><?php if($LangueAffichage=="FR"){echo "Lieu";}else{echo "Place";} ?><?php if($_SESSION['TriPersFormation_Lieu']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriPersFormation_Lieu']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="5%"><?php if($LangueAffichage=="FR"){echo "Etat";}else{echo "State";} ?></td>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="4%"><?php if($LangueAffichage=="FR"){echo "Présence";}else{echo "Presence";} ?></td>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="6%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="IdentificationPersonnelEnFormation_Liste.php?Tri=DateDebut"><?php if($LangueAffichage=="FR"){echo "Date de début";}else{echo "Start date";} ?><?php if($_SESSION['TriPersFormation_DateDebut']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriPersFormation_DateDebut']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="6%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="IdentificationPersonnelEnFormation_Liste.php?Tri=HeureDebut"><?php if($LangueAffichage=="FR"){echo "Heure de début";}else{echo "Start time";} ?><?php if($_SESSION['TriPersFormation_HeureDebut']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriPersFormation_HeureDebut']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="6%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="IdentificationPersonnelEnFormation_Liste.php?Tri=DateFin"><?php if($LangueAffichage=="FR"){echo "Date de fin";}else{echo "End date";} ?><?php if($_SESSION['TriPersFormation_DateFin']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriPersFormation_DateFin']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="6%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="IdentificationPersonnelEnFormation_Liste.php?Tri=HeureFin"><?php if($LangueAffichage=="FR"){echo "Heure de fin";}else{echo "End time";} ?><?php if($_SESSION['TriPersFormation_HeureFin']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriPersFormation_HeureFin']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="6%"><a style="text-decoration:none;color:#ffffff;font-weight:bold;" id="tri" href="IdentificationPersonnelEnFormation_Liste.php?Tri=DUree"><?php if($LangueAffichage=="FR"){echo "Durée";}else{echo "Duration";} ?><?php if($_SESSION['TriPersFormation_Duree']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriPersFormation_Duree']=="ASC"){echo "&darr;";}?></a></td>
					<td class="EnTeteTableauCompetences" style="color:#ffffff;border-bottom:1px dottom black;" width="1%"></td>
				</tr>
				<?php
					
					$dateDeFin=date('Y-m-d');
					if($date_debut<>""){
						$dateDeFin=TrsfDate_($date_debut);
					}
					$requetePersonnes="
						SELECT
							Id_Personne
						FROM
							new_competences_personne_prestation
						WHERE
							Date_Fin>='".$dateDeFin."' ";
					if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RQ_RH_CQS_HSE))
					{
						$requetePersonnes.="
							AND Id_Prestation IN
							(
								SELECT
									Id_Prestation 
								FROM
									new_competences_personne_prestation
								LEFT JOIN new_competences_prestation 
									ON new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
								WHERE
									Date_Fin>='".$dateDeFin."'
									AND Id_Plateforme IN
									(
										SELECT Id_Plateforme
										FROM new_competences_personne_poste_plateforme
										WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RQ_RH_CQS_HSE).")
									)
							) ";
						
					}
					else
					{
						$requetePersonnes.="
							AND CONCAT(Id_Prestation,'_',Id_Pole) IN
							(
								SELECT
									CONCAT(Id_Prestation,'_',Id_Pole)  
								FROM
									new_competences_personne_prestation
								WHERE
									Date_Fin>='".$dateDeFin."'
									AND CONCAT(Id_Prestation,'_',Id_Pole) IN
									(
										SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
										FROM new_competences_personne_poste_prestation
										WHERE Id_Personne=".$IdPersonneConnectee." AND Id_Poste IN (".implode(",",$TableauIdPostesRespPresta_CQ).")
									)
							)";
					}
					
					$resultPersResp=mysqli_query($bdd,$requetePersonnes);
					$nbPersResp=mysqli_num_rows($resultPersResp);
					$listeRespPers=0;
					if($nbPersResp>0)
					{
						$listeRespPers="";
						while($rowPersResp=mysqli_fetch_array($resultPersResp)){$listeRespPers.=$rowPersResp['Id_Personne'].",";}
						$listeRespPers=substr($listeRespPers,0,-1);
					}

					$req="
					SELECT
						*
					FROM
					(
						SELECT
							form_session_personne.Id,
							form_session_personne.Id_Besoin,
							form_session_personne.Id_Personne AS Id_Personne,
							form_session_personne.Id_Session AS Id_Session,
							form_session_personne.Validation_Inscription AS Validation_Inscription,
							form_session_personne.Presence,
							form_session_personne.SemiPresence,
							IF(Formation_Liee=1 AND Id_GroupeSession>0,
							(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session IN (SELECT TAB_Session.Id FROM form_session AS TAB_Session WHERE TAB_Session.Id_GroupeSession=form_session.Id_GroupeSession AND TAB_Session.Suppr=0) ORDER BY DateSession ASC LIMIT 1),
							(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession ASC LIMIT 1)) AS DateDebut,
							IF(Formation_Liee=1 AND Id_GroupeSession>0,
							(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session IN (SELECT TAB_Session.Id FROM form_session AS TAB_Session WHERE TAB_Session.Id_GroupeSession=form_session.Id_GroupeSession AND TAB_Session.Suppr=0) ORDER BY DateSession DESC LIMIT 1),
							(SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY DateSession DESC LIMIT 1)) AS DateFin,
							IF(Formation_Liee=1 AND Id_GroupeSession>0,
							(SELECT Heure_Debut FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session IN (SELECT TAB_Session.Id FROM form_session AS TAB_Session WHERE TAB_Session.Id_GroupeSession=form_session.Id_GroupeSession AND TAB_Session.Suppr=0) ORDER BY Heure_Debut ASC LIMIT 1),
							(SELECT Heure_Debut FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY Heure_Debut ASC LIMIT 1)) AS HeureDebut,
							IF(Formation_Liee=1 AND Id_GroupeSession>0,
							(SELECT Heure_Fin FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session IN (SELECT TAB_Session.Id FROM form_session AS TAB_Session WHERE TAB_Session.Id_GroupeSession=form_session.Id_GroupeSession AND TAB_Session.Suppr=0) ORDER BY Heure_Fin DESC LIMIT 1),
							(SELECT Heure_Fin FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=form_session_personne.Id_Session ORDER BY Heure_Fin DESC LIMIT 1)) AS HeureFin,
							(
								SELECT
								(
									SELECT
										Libelle 
									FROM
										new_competences_prestation 
									WHERE
										new_competences_prestation.Id=form_besoin.Id_Prestation
								)
								FROM
									form_besoin
								WHERE form_besoin.Id=form_session_personne.Id_Besoin
							) AS Prestation,
							(
								SELECT
									form_besoin.Id_Prestation
								FROM
									form_besoin
								WHERE form_besoin.Id=form_session_personne.Id_Besoin
							) AS Id_Prestation,
							(
								SELECT
								(
									SELECT
										Libelle 
									FROM
										new_competences_pole 
									WHERE
										new_competences_pole.Id=form_besoin.Id_Pole
								)
								FROM
									form_besoin
								WHERE
									form_besoin.Id=form_session_personne.Id_Besoin
							) AS Pole,
							(
								SELECT
									form_besoin.Id_Pole
								FROM
									form_besoin
								WHERE form_besoin.Id=form_session_personne.Id_Besoin
							) AS Id_Pole,
							(
								SELECT
									CONCAT(Nom,' ',Prenom)
								FROM
									new_rh_etatcivil
								WHERE
									new_rh_etatcivil.Id=form_session_personne.Id_Personne
							) AS Personne,
							(
								SELECT 
								(
									SELECT
										Libelle 
									FROM
										form_groupe_formation 
									WHERE
										form_groupe_formation.Id=form_session_groupe.Id_GroupeFormation
								) 
								FROM
									form_session_groupe 
								WHERE
									form_session_groupe.Id=form_session.Id_GroupeSession
							) AS GroupeFormation,
							form_session.Id_Formation AS Id_Formation,
							form_session.Formation_Liee AS Formation_Liee,
							form_session.Recyclage AS Recyclage,
							form_session.Id_Plateforme AS Id_Plateforme,
							form_session.Id_GroupeSession,
							IF(Formation_Liee=0,form_session.Id,form_session.Id_GroupeSession) AS Id_New,
							(SELECT form_formation.Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) AS Id_TypeFormation,
							(
								SELECT
									Libelle
								FROM form_lieu
									WHERE
								form_lieu.Id=form_session.Id_Lieu
							) AS Lieu,
							(
								SELECT
									Id_Langue
								FROM
								form_formation_plateforme_parametres 
								WHERE
									form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
									AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
									AND Suppr=0 LIMIT 1
							) AS Id_Langue,
							(
								SELECT
								(
									SELECT
										Libelle
									FROM
										form_organisme
									WHERE
										Id=Id_Organisme
								)
								FROM
									form_formation_plateforme_parametres 
								WHERE
									form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
								AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
								AND Suppr=0 LIMIT 1
							) AS Organisme ,
							(	SELECT
								IF(form_session.Recyclage=0,Duree,DureeRecyclage)
								FROM
									form_formation_plateforme_parametres 
								WHERE
									form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
								AND form_formation_plateforme_parametres.Id_Plateforme=form_session.Id_Plateforme 
								AND Suppr=0 LIMIT 1
							) AS Duree
						FROM
							form_session_personne 
						LEFT JOIN
							form_session
						ON
							form_session_personne.Id_Session=form_session.Id
						WHERE
							form_session_personne.Suppr=0
							AND form_session.Annule=0
							AND form_session.Suppr=0
						GROUP BY Id_New,Id_Personne,Formation_Liee,Validation_Inscription
					) AS TABLE_GENERALE 
						WHERE  Id_Personne IN (".$listeRespPers.") ";

					if($prestation<>"")
					{
						$req.="
							AND
							( 
								Prestation LIKE '%".$prestation."%' 
								OR
								Pole LIKE '%".$prestation."%'
							)";
					}
					if($_SESSION['FiltrePersFormation_RespProjet']<>""){
						$req.="
								AND CONCAT(TABLE_GENERALE.Id_Prestation,'_',TABLE_GENERALE.Id_Pole) 
									IN (SELECT CONCAT(new_competences_personne_poste_prestation.Id_Prestation,'_',new_competences_personne_poste_prestation.Id_Pole) 
										FROM new_competences_personne_poste_prestation
										WHERE Id_Personne IN (".$_SESSION['FiltrePersFormation_RespProjet'].")
										AND Id_Poste IN (".$IdPosteResponsableProjet.")
									)
									";
					}
					if($stagiaire<>""){$req.="AND Personne LIKE '%".$stagiaire."%' ";}
					if($etat=="-2"){$req.="AND (Validation_Inscription<>-1) ";}
					else{$req.="AND (Validation_Inscription=".$etat.") ";}
					if($TypeForm>0 && $TypeForm<>""){
						$req.=" AND Id_TypeFormation=".$TypeForm." ";
					}
					if($date_debut<>"")
					{
						$req.="AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=TABLE_GENERALE.Id_Session ORDER BY DateSession ASC LIMIT 1) >= '".TrsfDate_($date_debut)."' ";
					}
					if($date_fin<>"")
					{
						$req.="AND (SELECT DateSession FROM form_session_date WHERE form_session_date.Suppr=0 AND form_session_date.Id_Session=TABLE_GENERALE.Id_Session ORDER BY DateSession DESC LIMIT 1) <= '".TrsfDate_($date_fin)."' ";
					}
					
					if($_SESSION['TriPersFormation_General']<>""){$req.=" ORDER BY ".substr($_SESSION['TriPersFormation_General'],0,-1);}
					
					$ResultSessions=mysqli_query($bdd,$req);
					$NbSessions=mysqli_num_rows($ResultSessions);
					
					$reqLangue="SELECT Libelle, LibelleRecyclage, Id_Formation, Id_Langue  
								FROM form_formation_langue_infos 
								WHERE Suppr=0";
					$resultFormLangue=mysqli_query($bdd,$reqLangue);
					$nbFormLangue=mysqli_num_rows($resultFormLangue);
					
					if($NbSessions>0)
					{
						while($row=mysqli_fetch_array($ResultSessions))
						{
							$Libelle="";
							if($nbFormLangue>0)
							{
								mysqli_data_seek($resultFormLangue,0);
								while($rowFormLangue=mysqli_fetch_array($resultFormLangue))
								{
									if($rowFormLangue['Id_Formation']==$row['Id_Formation'] && $rowFormLangue['Id_Langue']==$row['Id_Langue'] )
									{
										if($row['Recyclage']==0){$Libelle=stripslashes($rowFormLangue['Libelle']);}
										else
										{
											$Libelle=stripslashes($rowFormLangue['LibelleRecyclage']);
											if($Libelle==""){$Libelle=stripslashes($rowFormLangue['Libelle']);}
										}
										if($row['Organisme']<>""){$Libelle.=" (".$row['Organisme'].")";}
									}
								}
							}
							$EtatI="";
							$couleur="";
							if($row['Validation_Inscription']==0)
							{
								if($LangueAffichage=="FR"){$EtatI="En attente validation";}
								else{$EtatI="Waiting for validation";}
								$couleur="bgcolor='#ddff00' ";
							}
							elseif($row['Validation_Inscription']==1)
							{
								if($LangueAffichage=="FR"){$EtatI="Validée";}
								else{$EtatI="Validated";}
								$couleur="bgcolor='#34bb37' ";
							}
							elseif($row['Validation_Inscription']==-1)
							{
								if($LangueAffichage=="FR"){$EtatI="Refusée";}
								else{$EtatI="Declined";}
								//$couleur="bgcolor='#f10d0d' ";
							}
							$GroupeFormation="";
							if($row['Formation_Liee']==1){$GroupeFormation=$row['GroupeFormation'];}
							
							$Presence="";
							if($row['Presence']==1 && $row['Validation_Inscription']==1){$Presence= "<img src='../../Images/tick.png' style='border:0;' title='Present'>";}
							elseif($row['Presence']==-1 && $row['Validation_Inscription']==1){$Presence= "<img src='../../Images/Refuser.gif' style='border:0;' title='Absent'>";}
							elseif($row['Presence']==-2 && $row['Validation_Inscription']==1){$Presence= substr($row['SemiPresence'],0,5);}
							
							if($row['Formation_Liee']==1){
								$laFormation=$GroupeFormation;
							}
							else{
								$laFormation=$Libelle;
							}
							
							$bTrouve=1;
							if($formation<>"" && stripos($laFormation,$formation)===false){$bTrouve=0;}

							if($bTrouve==1){
								
							$Contrat="";
								$IdContrat=IdContrat($row['Id_Personne'],$row['DateDebut']);
								if($IdContrat>0){
									if(TypeContrat2($IdContrat)<>10){
										$Contrat=TypeContrat($IdContrat);
									}
									else{
										$tab=AgenceInterimContrat($IdContrat);
										if($tab<>0){
											$Contrat=$tab[0];
										}
									}
								}
							
							
							
						?>
							<tr <?php echo $couleur; ?>>
								<td><?php echo AfficheCodePrestation(stripslashes($row['Prestation'])); ?></td>
								<td><?php echo stripslashes($row['Pole']); ?></td>
								<td><?php echo stripslashes($row['Personne']); ?></td>
								<td><?php echo $Contrat; ?></td>
								<td><?php echo stripslashes($laFormation); ?></td>
								<td><?php echo stripslashes($row['Lieu']); ?></td>
								<td><?php echo stripslashes($EtatI); ?></td>
								<td><?php echo stripslashes($Presence); ?></td>
								<td><?php echo stripslashes(AfficheDateJJ_MM_AAAA($row['DateDebut'])); ?></td>
								<td><?php echo stripslashes(substr($row['HeureDebut'],0,5)); ?></td>
								<td><?php echo stripslashes(AfficheDateJJ_MM_AAAA($row['DateFin'])); ?></td>
								<td><?php echo stripslashes(substr($row['HeureFin'],0,5)); ?></td>
								<td><?php echo stripslashes($row['Duree']); ?></td>
								<td>
									<?php
										if($row['Validation_Inscription']==0){
											//Uniquement si responsable sur cette prestation 
											if(DroitsAUnePrestation($TableauIdPostesRespPresta_CQ,$row['Id_Prestation'],$row['Id_Pole'])|| DroitsFormationPlateforme($TableauIdPostesAF_RF_RH)){
												echo "<a style=\"text-decoration:none;\" href=\"javascript:Desinscrire('".$row['Id_Personne']."','".$row['Id']."','".$row['Id_Besoin']."','".$LangueAffichage."','".$row['Id_GroupeSession']."','".$row['Formation_Liee']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Désinscrire\" title=\"Désinscrire\">&nbsp;&nbsp;</a>";
											}
										}
									?>
								</td>
							</tr>
						<?php
							}
						}
					}
				?>
				<tr>
					<td>
						<div id="Desinscrire2"></div>
					</td>
				</tr>
			</table>
			</div>
		</td>
	</tr>
	</table>
</form>
</html>
	
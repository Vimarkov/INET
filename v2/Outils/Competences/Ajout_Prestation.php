<html>
<head>
	<title>Compétences - Prestation</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps()
		{
			if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
			if(formulaire.client.value=='0'){alert('Vous n\'avez pas renseigné le client.');return false;}
			if(formulaire.familleR03.value=='0'){alert('Vous n\'avez pas renseigné la famille R03.');return false;}
			return true;
		}
			
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
		
		Liste_Prestation_Projet = new Array();
		Liste_Prestation_Domaine = new Array();
		function Recharge_Liste_Projet()
		{
			//DOMAINES
			var sel="";
			sel ="<select size='1' name='Id_Domaine'>";
			sel = sel + "<option value='0' selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
			for(var i=0;i<Liste_Prestation_Domaine.length;i++)
			{
				if (Liste_Prestation_Domaine[i][1]==document.getElementById('Id_Plateforme').value)
				{
					sel= sel + "<option value="+Liste_Prestation_Domaine[i][0];
					if(Liste_Prestation_Domaine[i][0]==document.getElementById('Id_Domaine_Initial').value){sel = sel + " selected";}
					sel= sel + ">"+Liste_Prestation_Domaine[i][2]+"</option>";
				}
			}
			sel =sel + "</select>";
			document.getElementById('Domaine').innerHTML=sel;
		}
		function AfficherNA()
		{
			if(document.getElementById('AfficherEtatQualif').value==1){
				var elements = document.getElementsByClassName('qualifs');
				for (i=0; i<elements.length; i++){
					if(elements[i].checked){
						if(document.getElementById('etat_'+elements[i].value).value==''){
							document.getElementById('etat_'+elements[i].value).value='NA';
						}
					}
				}
			}
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require_once("../Formation/Globales_Fonctions.php");
require_once("../Fonctions.php");

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_prestation WHERE Libelle='".$_POST['Libelle']."'");
		if(mysqli_num_rows($result)==0)
		{
			$req="INSERT INTO new_competences_prestation 
					(Id_Plateforme,Id_Domaine,Libelle,Code_Analytique,AfficherBadgeStamp,AfficherBadge,AfficherEtatQualif,AfficherDateAnniversaire,
					SousSurveillance,Id_Client,Id_FamilleR03,CDCRef,CDCTitre,SiteAirbus,
					SousTraitant,SousTraitantAdresse,
					SousTraitantPointFocal,SousTraitantPointFocalTel,SousTraitantPointFocalEmail,CentreDeCout,EOTP) 
				VALUES (".$_POST['Id_Plateforme'].",".$_POST['Id_Domaine'].",'".addslashes($_POST['Libelle'])."',
				'".$_POST['Code_Analytique']."',".$_POST['AfficherBadgeStamp'].",".$_POST['AfficherBadge'].",
				".$_POST['AfficherEtatQualif'].",".$_POST['AfficherDateAnniversaire'].",'".addslashes($_POST['SousSurveillance'])."',".$_POST['client'].",".$_POST['familleR03'].",
				'".addslashes($_POST['cdcRef'])."','".addslashes($_POST['cdcTitre'])."','".addslashes($_POST['siteAirbus'])."',
				'".addslashes($_POST['sousTraitant'])."','".addslashes($_POST['sousTraitantAdresse'])."',
				'".addslashes($_POST['sousTraitantFP'])."','".addslashes($_POST['sousTraitantFPTel'])."',
				'".addslashes($_POST['sousTraitantFPEmail'])."',
				'".addslashes($_POST['centreDeCout'])."','".addslashes($_POST['EOTP'])."')";
		    $result=mysqli_query($bdd,$req);
			$IdPrestation=mysqli_insert_id($bdd);
			
			//Uniquement prestation AAAFR (TLS,BDX,Lanne,NORD,OUEST,SE,MAINT,MARINE,TW)
			if($_POST['Id_Plateforme']==1 || $_POST['Id_Plateforme']==3 || $_POST['Id_Plateforme']==4 || $_POST['Id_Plateforme']==9
			|| $_POST['Id_Plateforme']==10 || $_POST['Id_Plateforme']==19 || $_POST['Id_Plateforme']==23
			|| $_POST['Id_Plateforme']==28 || $_POST['Id_Plateforme']==29){
				$requeteUpdt="UPDATE new_competences_prestation 
						SET UtiliseMORIS=1,
						ToleranceOTDOQD=0,
						ChargeADesactive=1,
						ProductiviteADesactive=1,
						PlanPreventionADesactivite=0,
						PolyvalenceADesactive=1,
						OTDOQDADesactive=1,
						ManagementADesactive=1,
						CompetenceADesactive=1,
						SecuriteADesactive=1,
						PRMADesactive=1,
						NCADesactive=1
						WHERE Id=".$IdPrestation." ";
				$resultUpd=mysqli_query($bdd,$requeteUpdt);
				
				$reqDate="INSERT INTO moris_datesuivi (Id_Prestation,DateDebut,DateFin) VALUES (".$IdPrestation.",'".date('Y-m-d')."','0001-01-01') ";
				$resultDate=mysqli_query($bdd,$reqDate);
				
			}
			//Ajout du paramétrage (commodity, product, scope,program 
			for($i=1;$i<=3;$i++){
				if($_POST['commodity_'.$i]<>""){
					$req="INSERT INTO new_competences_prestation_parametrage (Id_Prestation,Commodity,Product) 
							VALUES (".$IdPrestation.",'".addslashes($_POST['commodity_'.$i])."','".addslashes($_POST['product_'.$i])."')";
					$result2=mysqli_query($bdd,$req);
					$IdPrestationParam=mysqli_insert_id($bdd);
					
					//Ajout du program et scope
					$Tableau=array
							(
								"Mechanic",
								"Electric",
								"Oxygen",
								"Others"
							);
					foreach($Tableau as $indice => $valeur)
					{
						if(isset($_POST['scope_'.$i."_".$valeur])){
							$autre="";
							if($valeur=="Others"){
								$autre=addslashes($_POST['scope_'.$i."_".$valeur."Comment"]);
							}
							$req="INSERT INTO new_competences_prestation_parametrage_detail (Id_PrestationParametrage,Type,Info,Autre) 
								VALUES (".$IdPrestationParam.",'Scope','".$_POST['scope_'.$i."_".$valeur]."','".$autre."')";
							$result2=mysqli_query($bdd,$req);
						}
					}
					
					$Tableau=array
							(
								"A320_Family",
								"A330_Family",
								"A400M",
								"BELUGA",
								"A350_XWB",
								"ATR",
								"A380",
							);
					foreach($Tableau as $indice => $valeur)
					{
						if(isset($_POST['program_'.$i."_".$valeur])){
							$req="INSERT INTO new_competences_prestation_parametrage_detail (Id_PrestationParametrage,Type,Info) 
								VALUES (".$IdPrestationParam.",'Scope','".$_POST['program_'.$i."_".$valeur]."')";
							$result2=mysqli_query($bdd,$req);
						}
					}
					
					
				}
			}
			
			//Traitement des qualifications cochées
			if(isset($_POST["Qualif"]))
			{
				$Qualif=$_POST["Qualif"];
				for($i=0;$i<sizeof($Qualif);$i++)
				{
					if(isset($Qualif[$i])){
						$result4=mysqli_query($bdd,"INSERT INTO new_competences_prestation_qualification (Id_Prestation, Id_Qualification,Etat) VALUES (".$IdPrestation.",".$Qualif[$i].",'".$_POST['etat_'.$Qualif[$i]]."')");
					}
				}
			}
			
			//Traitement des formations cochées
			if(isset($_POST["Formations"]))
			{
				$Formation=$_POST["Formations"];
				for($i=0;$i<sizeof($Formation);$i++)
				{
					if(isset($Formation[$i])){$result4=mysqli_query($bdd,"INSERT INTO new_competences_prestation_formation (Id_Prestation, Id_Formation) VALUES (".$IdPrestation.",".$Formation[$i].")");}
				}
			}
			
			//Avertir par mail les différents AF des plateformes + les CQP / CQS
			//Attention pour l'instant nous limitons à la plateforme de TOULOUSE
			//A MODIFIER LORS DU DEPLOIEMENT SUR LES AUTRES PLATEFORMES
			if($_POST['Id_Plateforme']==1)
			{
				$Headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
				$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
				
				if($LangueAffichage=="FR"){
					$Objet="Nouvelle prestation dans l'extranet : ".$_POST['Libelle'];
					$Message="	<html>
									<head><title>Nouvelle prestation dans l'extranet </title></head>
									<body>
										Bonjour,
										<br><br>
										La prestation suivante a été créée sur l'extranet : ".$_POST['Libelle']."<br>
										Pensez à la configurer au niveau des besoins en formation par métier par prestation
										<br>
										Bonne journée.<br>
										L'Extranet Daher industriel services DIS.
									</body>
								</html>";
				}
				else
				{
					$Objet="New activity in the extranet : ".$_POST['Libelle'];
					$Message="	<html>
									<head><title>New activity in the extranet</title></head>
									<body>
										Hello,
										<br><br>
										The following activity was created on the extranet : ".$_POST['Libelle']."<br>
										Remember to configure it at the level of training needs by profession by delivery
										<br>
										Have a good day.<br>
										Extranet Daher industriel services DIS.
									</body>
								</html>";
				}
				$Emails="";
				
				//Liste des AF
				$reqAF="
					SELECT DISTINCT EmailPro
					FROM new_competences_personne_poste_plateforme
					LEFT JOIN new_rh_etatcivil
					ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
					WHERE new_competences_personne_poste_plateforme.Id_Poste IN (".implode(",",array($IdPosteResponsableFormation,$IdPosteAssistantFormationExterne,$IdPosteResponsableQualite,$IdPosteResponsablePlateforme,$IdPosteResponsableMGX)).")
					AND Id_Plateforme=".$_POST['Id_Plateforme']." ";
				$ResultAF=mysqli_query($bdd,$reqAF);
				$NbAF=mysqli_num_rows($ResultAF);
				if($NbAF>0)
				{
				    while($RowAF=mysqli_fetch_array($ResultAF))
				    {
				        if($RowAF['EmailPro']<>""){$Emails.=$RowAF['EmailPro'].",";}
				    }
				}
				
				//Liste des CQP
				$reqCQ="SELECT DISTINCT EmailPro 
						FROM new_competences_personne_poste_prestation
						LEFT JOIN new_rh_etatcivil
						ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
						WHERE new_competences_personne_poste_prestation.Id_Poste IN (".$IdPosteReferentQualiteProduit.") 
						AND (
							SELECT Id_Plateforme 
							FROM new_competences_prestation 
							WHERE new_competences_prestation.Id=new_competences_personne_poste_prestation.Id_Prestation
                            )=".$_POST['Id_Plateforme']." ";
				$ResultCQ=mysqli_query($bdd,$reqCQ);
				$NbCQ=mysqli_num_rows($ResultCQ);
				if($NbCQ>0)
				{
					while($RowCQ=mysqli_fetch_array($ResultCQ))
					{
						if($RowCQ['EmailPro']<>""){$Emails.=$RowCQ['EmailPro'].",";}
					}
				}
				
				$Emails=substr($Emails,0,-1);
				
				if($Emails<>"")
				{
					if(mail($Emails,$Objet,$Message,$Headers,'-f extranet@aaa-aero.com'))
						{echo "Un message a été envoyé à ".$Emails."\n";}
					else{echo "Un message n'a pu être envoyé à ".$Emails."\n";}
				}
			}
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Ce libellé existe déjà.<br>Vous devez recommencer l'opération.</font>";}
	}
	elseif($_POST['Mode']=="Modif")
	{
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_prestation WHERE (Libelle='".$_POST['Libelle']."') AND Id!=".$_POST['Id']);
		if(mysqli_num_rows($result)==0)
		{
			$req="UPDATE 
				new_competences_prestation 
			SET 
				Id_Plateforme=".$_POST['Id_Plateforme'].", 
				Id_Domaine=".$_POST['Id_Domaine'].", 
				Libelle='".addslashes($_POST['Libelle'])."', 
				Code_Analytique='".$_POST['Code_Analytique']."', 
				Active=".$_POST['Active'].", 
				AfficherBadgeStamp=".$_POST['AfficherBadgeStamp'].", 
				AfficherEtatQualif=".$_POST['AfficherEtatQualif'].", 
				AfficherBadge=".$_POST['AfficherBadge'].", 
				AfficherDateAnniversaire=".$_POST['AfficherDateAnniversaire'].", 
				SousSurveillance='".addslashes($_POST['SousSurveillance'])."', 
				Id_Client=".$_POST['client'].", 
				Id_FamilleR03=".$_POST['familleR03'].", 
				CDCRef='".addslashes($_POST['cdcRef'])."',
				CDCTitre='".addslashes($_POST['cdcTitre'])."',
				SiteAirbus='".addslashes($_POST['siteAirbus'])."',
				SousTraitant='".addslashes($_POST['sousTraitant'])."',
				SousTraitantAdresse='".addslashes($_POST['sousTraitantAdresse'])."',
				SousTraitantPointFocal='".addslashes($_POST['sousTraitantFP'])."',
				SousTraitantPointFocalTel='".addslashes($_POST['sousTraitantFPTel'])."',
				SousTraitantPointFocalEmail='".addslashes($_POST['sousTraitantFPEmail'])."',
				CentreDeCout='".addslashes($_POST['centreDeCout'])."',
				EOTP='".addslashes($_POST['EOTP'])."'
			WHERE Id=".$_POST['Id'];
			$result=mysqli_query($bdd,$req);
			if($_POST['Active']==-1)
			{
			    $resultPole=mysqli_query($bdd,"UPDATE new_competences_pole SET Actif=1 WHERE Id_Prestation=".$_POST['Id']);
			    $resultHierarchie=mysqli_query($bdd,"DELETE FROM new_competences_personne_poste_prestation WHERE Id_Prestation=".$_POST['Id']);
			}
			$result2=mysqli_query($bdd,"DELETE FROM new_competences_prestation_qualification WHERE Id_Prestation=".$_POST['Id']);
			$result2=mysqli_query($bdd,"DELETE FROM new_competences_prestation_formation WHERE Id_Prestation=".$_POST['Id']);
			
			$IdPrestation=$_POST['Id'];
			
			//Ajout du paramétrage (commodity, product, scope,program 
			$result2=mysqli_query($bdd,"UPDATE new_competences_prestation_parametrage SET Suppr=1 WHERE Id_Prestation=".$IdPrestation);
			$result2=mysqli_query($bdd,"DELETE FROM new_competences_prestation_parametrage_detail WHERE Id_PrestationParametrage IN (SELECT Id FROM new_competences_prestation_parametrage WHERE Suppr=1 AND Id_Prestation=".$IdPrestation.") ");
			
			for($i=1;$i<=3;$i++){
				if($_POST['commodity_'.$i]<>""){
					$req="INSERT INTO new_competences_prestation_parametrage (Id_Prestation,Commodity,Product) 
							VALUES (".$IdPrestation.",'".addslashes($_POST['commodity_'.$i])."','".addslashes($_POST['product_'.$i])."')";
					$result2=mysqli_query($bdd,$req);
					$IdPrestationParam=mysqli_insert_id($bdd);
					
					//Ajout du program et scope
					$Tableau=array
							(
								"Mechanic",
								"Electric",
								"Oxygen",
								"Others"
							);
					foreach($Tableau as $indice => $valeur)
					{
						if(isset($_POST['scope_'.$i."_".$valeur])){
							$autre="";
							if($valeur=="Others"){
								$autre=addslashes($_POST['scope_'.$i."_".$valeur."Comment"]);
							}
							$req="INSERT INTO new_competences_prestation_parametrage_detail (Id_PrestationParametrage,Type,Info,Autre) 
								VALUES (".$IdPrestationParam.",'Scope','".$_POST['scope_'.$i."_".$valeur]."','".$autre."')";
							$result2=mysqli_query($bdd,$req);
						}
					}
					
					$Tableau=array
							(
								"A320_Family",
								"A330_Family",
								"A400M",
								"BELUGA",
								"A350_XWB",
								"ATR",
								"A380",
							);
					foreach($Tableau as $indice => $valeur)
					{
						if(isset($_POST['program_'.$i."_".$valeur])){
							$req="INSERT INTO new_competences_prestation_parametrage_detail (Id_PrestationParametrage,Type,Info) 
								VALUES (".$IdPrestationParam.",'Program','".$_POST['program_'.$i."_".$valeur]."')";
							$result2=mysqli_query($bdd,$req);
						}
					}
					
					
				}
			}
			
			//Traitement des qualifications cochées
			if(isset($_POST["Qualif"]))
			{
				$Qualif=$_POST["Qualif"];
				for($i=0;$i<=sizeof($Qualif);$i++)
				{
					if(isset($Qualif[$i]))
					{
						$result4=mysqli_query($bdd,"INSERT INTO new_competences_prestation_qualification (Id_Prestation, Id_Qualification, Etat) VALUES (".$_POST['Id'].",".$Qualif[$i].",'".$_POST['etat_'.$Qualif[$i]]."')");
					}
				}
			}
			
			//Traitement des formations cochées
			if(isset($_POST["Formations"]))
			{
				$Formation=$_POST["Formations"];
				for($i=0;$i<sizeof($Formation);$i++)
				{
					if(isset($Formation[$i]))
					{
						$result4=mysqli_query($bdd,"INSERT INTO new_competences_prestation_formation (Id_Prestation, Id_Formation) VALUES (".$_POST['Id'].",".$Formation[$i].")");
					}
				}
			}
			
			if($_POST['Id_Plateforme']==1)
			{
				if($_POST['OldActive']==-1 && $_POST['Active']==0){
					$Headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
					$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
					
					if($LangueAffichage=="FR"){
						$Objet="Prestation réactivée dans l'extranet : ".$_POST['Libelle'];
						$Message="	<html>
										<head><title>Prestation réactivée dans l'extranet </title></head>
										<body>
											Bonjour,
											<br><br>
											La prestation suivante a été réactivée sur l'extranet : ".$_POST['Libelle']."<br>
											Pensez à la configurer au niveau des besoins en formation par métier par prestation
											<br>
											Bonne journée.<br>
											L'Extranet Daher industriel services DIS.
										</body>
									</html>";
					}
					else
					{
						$Objet="Reactivated site in the extranet : ".$_POST['Libelle'];
						$Message="	<html>
										<head><title>Reactivated site in the extranet</title></head>
										<body>
											Hello,
											<br><br>
											The following site has been reactivated on the extranet : ".$_POST['Libelle']."<br>
											Remember to configure it at the level of training needs by profession by delivery
											<br>
											Have a good day.<br>
											Extranet Daher industriel services DIS.
										</body>
									</html>";
					}
					$Emails="";
					
					//Liste des AF
					$reqAF="
						SELECT DISTINCT EmailPro
						FROM new_competences_personne_poste_plateforme
						LEFT JOIN new_rh_etatcivil
						ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
						WHERE new_competences_personne_poste_plateforme.Id_Poste IN (".implode(",",array($IdPosteAssistantFormationExterne)).")
						AND Id_Plateforme=".$_POST['Id_Plateforme']." ";
					$ResultAF=mysqli_query($bdd,$reqAF);
					$NbAF=mysqli_num_rows($ResultAF);
					if($NbAF>0)
					{
						while($RowAF=mysqli_fetch_array($ResultAF))
						{
							if($RowAF['EmailPro']<>""){$Emails.=$RowAF['EmailPro'].",";}
						}
					}
					
					//Liste des CQP
					$reqCQ="SELECT DISTINCT EmailPro 
							FROM new_competences_personne_poste_prestation
							LEFT JOIN new_rh_etatcivil
							ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
							WHERE new_competences_personne_poste_prestation.Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.") 
							AND (
								SELECT Id_Plateforme 
								FROM new_competences_prestation 
								WHERE new_competences_prestation.Id=new_competences_personne_poste_prestation.Id_Prestation
								)=".$_POST['Id_Plateforme']." ";
					$ResultCQ=mysqli_query($bdd,$reqCQ);
					$NbCQ=mysqli_num_rows($ResultCQ);
					if($NbCQ>0)
					{
						while($RowCQ=mysqli_fetch_array($ResultCQ))
						{
							if($RowCQ['EmailPro']<>""){$Emails.=$RowCQ['EmailPro'].",";}
						}
					}
					
					$Emails=substr($Emails,0,-1);
					
					if($Emails<>"")
					{
						if(mail($Emails,$Objet,$Message,$Headers,'-f extranet@aaa-aero.com'))
							{echo "Un message a été envoyé à ".$Emails."\n";}
						else{echo "Un message n'a pu être envoyé à ".$Emails."\n";}
					}
				}
			}
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Ce libellé existe déjà.<br>Vous devez recommencer l'opération.</font>";}
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT * FROM new_competences_prestation WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Prestation.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="Modif"){echo $row['Id'];}?>">
		<input type="hidden" name="OldActive" value="<?php if($_GET['Mode']=="Modif"){echo $row['Active'];}?>">
		<input type="hidden" id="Id_Domaine_Initial" value="<?php if($_GET['Mode']=="Modif"){echo $row['Id_Domaine'];}?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
				<td>
					<select id="Id_Plateforme" name="Id_Plateforme" onchange="Recharge_Liste_Projet();">
				<?php
				$result2=mysqli_query($bdd,"SELECT * FROM new_competences_plateforme ORDER BY Libelle ASC");
				while($row2=mysqli_fetch_array($result2))
				{
					echo "<option value='".$row2['Id']."'";
					if($_GET['Mode']=="Modif"){if($row['Id_Plateforme']==$row2['Id']){echo " selected";}}
					echo ">".$row2['Libelle']."</option>";
				}
				?>
				</select>
				</td>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Afficher les stamps dans le tableau de compétences";}else{echo "Display stamps in the skills table";}?> : </td>
				<td>
					<select name="AfficherBadgeStamp">
						<option value="0" <?php if($_GET['Mode']<>"Modif"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";}?></option>
						<option value="1" <?php if($_GET['Mode']=="Modif"){if($row['AfficherBadgeStamp']==1){echo "selected";}} ?>><?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}?></option>
					</select>
				</td>
			</tr>
			<?php if($_GET['Mode']=="Modif")
			{
			?>
			<tr>
				<td class="Libelle">Active/Non active : </td>
				<td> 
					<select name="Active">
					<?php
						if ($row['Active'] == 0)
						{
							echo "<option name='0' value='0' selected>Active</option>";
							echo "<option name='-1' value='-1' >Non active</option>";
						}
						else
						{
							echo "<option name='0' value='0' >Active</option>";
							echo "<option name='-1' value='-1' selected>Non active</option>";
						}
					?>
					</select>
				</td>
			</tr>
			<?php
			}
			?>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Client";}else{echo "Client";} ?> </td>
				<td>
					<select	id="client" name="client" style="width:200px;">
						<option value="0"></option>
						<?php
							$req="SELECT Id, Libelle FROM moris_client WHERE Suppr=0 ORDER BY Libelle";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($row2=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="Modif"){
										if($row2['Id']==$row['Id_Client']){$selected="selected";}
									}
									echo "<option value='".$row2['Id']."' ".$selected.">".stripslashes($row2['Libelle'])."</option>";
								}
							}
						?>
					</select>
				</td>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Afficher les n° de badges dans le tableau de compétences";}else{echo "Display badge numbers in the skills table";}?> : </td>
				<td>
					<select name="AfficherBadge">
						<option value="0" <?php if($_GET['Mode']<>"Modif"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";}?></option>
						<option value="1" <?php if($_GET['Mode']=="Modif"){if($row['AfficherBadge']==1){echo "selected";}} ?>><?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}?></option>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($_SESSION['Langue']=="EN"){echo "Family R03";}else{echo "Famille R03";} ?> </td>
				<td>
					<select	id="familleR03" name="familleR03" style="width:250px;">
						<option value="0"></option>
						<?php
							$req="SELECT Id, Num, Libelle FROM moris_famille_r03 WHERE Suppr=0 ORDER BY Num";
							$result=mysqli_query($bdd,$req);
							$nbResulta=mysqli_num_rows($result);
							if ($nbResulta>0){
								while($row2=mysqli_fetch_array($result)){
									$selected="";
									if($_GET['Mode']=="Modif"){
										if($row2['Id']==$row['Id_FamilleR03']){$selected="selected";}
									}
									echo "<option value='".$row2['Id']."' ".$selected.">".stripslashes($row2['Num'])." - ".stripslashes($row2['Libelle'])."</option>";
								}
							}
						?>
					</select>
				</td>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Afficher l’état des qualifications société dans le tableau de compétences";}else{echo "View the status of company qualifications in the skills table";}?> : </td>
				<td>
					<select id="AfficherEtatQualif" name="AfficherEtatQualif" onchange="AfficherNA()">
						<option value="0" <?php if($_GET['Mode']<>"Modif"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";}?></option>
						<option value="1" <?php if($_GET['Mode']=="Modif"){if($row['AfficherEtatQualif']==1){echo "selected";}} ?>><?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}?></option>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Domaine";}else{echo "Domain";}?> : </td>
				<td>
					<div id="Domaine">
						<select size="1" name="Id_Domaine"></select>
					</div>
					<?php
					$requete_Projet="SELECT Id, Id_Plateforme, Libelle FROM rh_domaine ORDER BY Libelle ASC";
					$result_Projet= mysqli_query($bdd,$requete_Projet) or die ("Select impossible");
					$i=0;
					while ($row_Domaine=mysqli_fetch_row($result_Projet))
					{
						 echo "<script>Liste_Prestation_Domaine[".$i."] = new Array(".$row_Domaine[0].",".$row_Domaine[1].",'".$row_Domaine[2]."');</script>";
						 $i++;
					}
					?>
				</td>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Afficher la date d'anniversaire du salarié dans l'individual competency list de la prestation";}else{echo "Display the employee's birthday in the service's individual competency list";}?> : </td>
				<td>
					<select id="AfficherDateAnniversaire" name="AfficherDateAnniversaire">
						<option value="0" <?php if($_GET['Mode']<>"Modif"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";}?></option>
						<option value="1" <?php if($_GET['Mode']=="Modif"){if($row['AfficherDateAnniversaire']==1){echo "selected";}} ?>><?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}?></option>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td colspan="3"><input name="Libelle" size="80" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Libelle'];}?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Code analytique";}else{echo "Analytic code";}?> : </td>
				<td><input name="Code_Analytique" size="20" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Code_Analytique'];}?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Centre de coût";}else{echo "Cost center";}?> : </td>
				<td><input name="centreDeCout" size="20" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['CentreDeCout'];}?>"></td>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "EOTP";}else{echo "EOTP";}?> : </td>
				<td><input name="EOTP" size="20" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['EOTP'];}?>"></td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Sous Surveillance D-0918 ";}else{echo "Under Surveillance D-0918";}?> : </td>
				<td colspan=3>
					<select name="SousSurveillance" style="width:200px;">
					<?php
					$Tableau=array
							(
								"Oui/Yes",
								"Non (Exception 1 : Forte Présence Client incompatible avec une surveillance)/No (Exception 1 : Strong Customer Presence incompatible with surveillance)",
								"Non (Exception 2 : Trop éloigné de l’entité et/ou chantier ponctuel)/No (Exception 2 : Too far from the entity and/or temporary Worksite)",
								"Non (Exception 3 : Régie / Ass. Tech avec SMQ client et  < 10 ETP)/No (Exception 3 : Tech. Ass with Customer QMS and < 10 FTE)",
								"N/A (Prestation Admin.)/N/A (Admin. Activity)"
							);
					foreach($Tableau as $indice => $valeur)
					{
						echo '<option value="'.$valeur.'"';
						if($_GET['Mode']=="Modif"){if($row['SousSurveillance']==$valeur){echo " selected";}}
						echo '>'.$valeur.'</option>\n';
					}
					?>
					</select>
				</select>
				</td>
			</tr>
			<tr>
				<td height="10"></td>
			</tr>
			<tr class="TitreColsUsers" bgcolor="#dde8fc">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Cahier des charges (Ref)";}else{echo "Work specification ref";}?> : </td>
				<td colspan="3"><input name="cdcRef" size="40" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['CDCRef'];}?>"></td>
			</tr>
			<tr class="TitreColsUsers" bgcolor="#dde8fc">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Cahier des charges (Titre/description)";}else{echo "Work specification title/description";}?> : </td>
				<td colspan="3"><input name="cdcTitre" size="80" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['CDCTitre'];}?>"></td>
			</tr>
			<tr class="TitreColsUsers" bgcolor="#dde8fc">
				<td class="Libelle">Intervention site :<br>(préciser tous les sites d’intervention possibles de l’intervenant <br>– Precise all possible intervention site of the intervener  </td>
				<td colspan="3"><input name="siteAirbus" size="40" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['SiteAirbus'];}?>"></td>
			</tr>
			<tr>
				<td height="10"></td>
			</tr>
			<tr class="TitreColsUsers" bgcolor="#dde8fc">
				<td colspan="4">
					<table class="TableCompetences" width="100%" bgcolor="#dde8fc">
						<tr bgcolor="#dde8fc">
							<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Commodity";}else{echo "Commodity";}?></td>
							<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Product";}else{echo "Product";}?></td>
							<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Scope";}else{echo "Scope";}?></td>
							<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Program";}else{echo "Program";}?></td>
						</tr>
							<?php 
							//Ajout des lignes déjà créée
							$nbLigne=3;
							$numLigne=1;
							$Couleur="#EEEEEE";
							$req="SELECT Id,Commodity, Product FROM new_competences_prestation_parametrage WHERE Suppr=0 AND Id_Prestation=".$_GET['Id']." ";
							$resultParam=mysqli_query($bdd,$req);
							$nbParam=mysqli_num_rows($resultParam);
							if($nbParam>0)
							{
								while($rowParam=mysqli_fetch_array($resultParam))
								{
									if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
									else{$Couleur="#EEEEEE";}
							?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td><input name="commodity_<?php echo $numLigne;?>" size="30" type="text" value="<?php echo stripslashes($rowParam['Commodity']); ?>"></td>
								<td><input name="product_<?php echo $numLigne;?>" size="30" type="text" value="<?php echo stripslashes($rowParam['Product']); ?>"></td>
								<td>
									<?php
									$nb=0;
									$Tableau=array
											(
												"Mechanic",
												"Electric",
												"Oxygen",
												"Others"
											);
									foreach($Tableau as $indice => $valeur)
									{
										$checked="";
										$req="SELECT Id, Autre FROM new_competences_prestation_parametrage_detail 
												WHERE Suppr=0 
												AND Type='Scope'
												AND Info='".$valeur."'
												AND Id_PrestationParametrage=".$rowParam['Id']." ";
										$resultParamD=mysqli_query($bdd,$req);
										$nbParam=mysqli_num_rows($resultParamD);
										$autre="";
										if($nbParam>0)
										{
											$rowParamD=mysqli_fetch_array($resultParamD);
											$autre=stripslashes($rowParamD['Autre']);
											$checked="checked";
										}
										if($valeur=="Others"){
											echo "<br>";
										}
										echo "<input type='checkbox' ".$checked." name='scope_".$numLigne."_".$valeur."' value='".$valeur."' />".$valeur."";
										if($valeur=="Others"){
											echo "<input type='text' name='scope_".$numLigne."_".$valeur."Comment' value='".$autre."' />";
										}
	
										if($nb==1){echo "<br>";}
										$nb++;
									}
									?>
								</td>
								<td>
									<?php
									$nb=0;
									$Tableau=array
											(
												"A320_Family",
												"A330_Family",
												"A400M",
												"BELUGA",
												"A350_XWB",
												"ATR",
												"A380",
											);
									foreach($Tableau as $indice => $valeur)
									{
										$checked="";
										$req="SELECT Id FROM new_competences_prestation_parametrage_detail 
												WHERE Suppr=0 
												AND Type='Program'
												AND Info='".str_replace("_"," ",$valeur)."'
												AND Id_PrestationParametrage=".$rowParam['Id']." ";
										$resultParamD=mysqli_query($bdd,$req);
										$nbParam=mysqli_num_rows($resultParamD);
										if($nbParam>0)
										{
											$checked="checked";
										}
										echo "<input type='checkbox' ".$checked." name='program_".$numLigne."_".$valeur."' value='".str_replace("_"," ",$valeur)."' />".str_replace("_"," ",$valeur)."";
										if($nb==2){echo "<br>";}
										$nb++;
									}
									?>
								</td>
							</tr>
							<?php 
									$numLigne++;
								}
							}
				
							
							//Ajout jusqu'à 3 lignes max
							for($i=$numLigne;$i<=3;$i++){
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
							?>
							<tr bgcolor="<?php echo $Couleur;?>">
								<td><input name="commodity_<?php echo $i;?>" size="30" type="text" value=""></td>
								<td><input name="product_<?php echo $i;?>" size="30" type="text" value=""></td>
								<td>
									<?php
									$nb=0;
									$Tableau=array
											(
												"Mechanic",
												"Electric",
												"Oxygen",
												"Others"
											);
									foreach($Tableau as $indice => $valeur)
									{
										if($valeur=="Others"){
											echo "<br>";
										}
										echo "<input type='checkbox' name='scope_".$i."_".$valeur."' value='".$valeur."' />".$valeur."";
										if($valeur=="Others"){
											echo "<input type='text' name='scope_".$numLigne."_".$valeur."Comment' value='' />";
										}
										if($nb==1){echo "<br>";}
										$nb++;
									}
									?>
								</td>
								<td>
									<?php
									$nb=0;
									$Tableau=array
											(
												"A320_Family",
												"A330_Family",
												"A400M",
												"BELUGA",
												"A350_XWB",
												"ATR",
												"A380",
											);
									foreach($Tableau as $indice => $valeur)
									{
										echo "<input type='checkbox' name='program_".$i."_".$valeur."' value='".str_replace("_"," ",$valeur)."' />".str_replace("_"," ",$valeur)."";
										
										if($nb==2){echo "<br>";}
										$nb++;
									}
									?>
								</td>
							</tr>
							<?php 
							}
							?>
					</table>
				</td>
			</tr>
			<tr>
				<td height="10"></td>
			</tr>
			<tr class="TitreColsUsers" bgcolor="#dde8fc">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Client (Nom)";}else{echo "Customer company";}?> : </td>
				<td colspan="3"><input name="sousTraitant" size="40" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['SousTraitant'];}?>"></td>
			</tr>
			<tr class="TitreColsUsers" bgcolor="#dde8fc">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Client (Adresse)";}else{echo "Customer company address";}?> : </td>
				<td colspan="3"><input name="sousTraitantAdresse" size="80" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['SousTraitantAdresse'];}?>"></td>
			</tr>
			<tr bgcolor="#dde8fc">
				<td height="10" colspan="6"></td>
			</tr>
			<tr class="TitreColsUsers" bgcolor="#dde8fc">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Client point focal";}else{echo "Customer focal point";}?> : </td>
				<td colspan="3"><input name="sousTraitantFP" size="40" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['SousTraitantPointFocal'];}?>"></td>
			</tr>
			<tr class="TitreColsUsers" bgcolor="#dde8fc">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Client point focal (Tel)";}else{echo "Customer focal point (Tel number)";}?> : </td>
				<td colspan="3"><input name="sousTraitantFPTel" size="40" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['SousTraitantPointFocalTel'];}?>"></td>
			</tr>
			<tr class="TitreColsUsers" bgcolor="#dde8fc">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Client point focal (Email)";}else{echo "Customer focal point (Email)";}?> : </td>
				<td colspan="3"><input name="sousTraitantFPEmail" size="40" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['SousTraitantPointFocalEmail'];}?>"></td>
			</tr>
			<tr>
				<td height="10"></td>
			</tr>
			<tr valign="top">
				<td colspan="2">
					<table class="TableCompetences" style="width:650px;">
					<?php
						$result=mysqli_query($bdd,"SELECT new_competences_qualification.* FROM new_competences_qualification, new_competences_categorie_qualification WHERE new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id ORDER BY new_competences_categorie_qualification.Libelle ASC, new_competences_qualification.Libelle ASC");
						$nbenreg=mysqli_num_rows($result);
						if($nbenreg>0)
						{
					?>
						<tr>
							<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
							<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Catégorie qualification";}else{echo "Qualification group";}?></td>
							<td colspan="2" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Etat";}else{echo "Status";}?></td>
						</tr>
						<?php
							$Couleur="#EEEEEE";
							$Categorie=0;
							while($row=mysqli_fetch_array($result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
								$result2=mysqli_query($bdd,"SELECT Libelle,Id FROM new_competences_categorie_qualification WHERE Id=".$row['Id_Categorie_Qualification']);
								$row2=mysqli_fetch_array($result2);
								if($Categorie!=$row2['Libelle']){echo "<tr height='1' bgcolor='#66AACC'><td colspan='4'></td></tr>";}
								$Categorie=$row2['Libelle'];
								$QualifAppartientParrainage=0;
								$Statut="";
								if($_GET['Mode']=="Modif")
								{
									$result3=mysqli_query($bdd,"SELECT * FROM new_competences_prestation_qualification WHERE Id_Prestation=".$_GET["Id"]." AND Id_Qualification=".$row["Id"]);
									$QualifAppartientParrainage=mysqli_num_rows($result3);
									if($QualifAppartientParrainage>0){
										$rowPrestaQualif=mysqli_fetch_array($result3);
										$Statut=$rowPrestaQualif['Etat'];
									}
								}
						?>
						<tr bgcolor="<?php echo $Couleur;?>">
							<td width="300"><?php echo $row['Libelle'];?></td>
							<td width="310"><?php echo $row2['Libelle'];?></td>
							<td width="50">
								<?php 
								$visible="";
								//if($_GET["Id"]<>1598){$visible="display:none;";} 
								?>
								<select style="width:70px;<?php echo $visible; ?>" class="etat" id="etat_<?php echo $row['Id'];?>" name="etat_<?php echo $row['Id'];?>">
									<option value="" <?php if($Statut==""){echo "selected";}?>></option>
									<option value="Q" <?php if($Statut=="Q"){echo "selected";}?>>Q : Qualified [At least 1 CMTE from DIS used, or 0 CMTE necessary, for the process application]</option>
									<option value="QWL" <?php if($Statut=="QWL"){echo "selected";}?>>QWL : Qualified with Limitation (See on Quality Plan for limitations) [At least 1 CMTE from DIS used, or 0 CMTE necessary, for the process application]</option>
									<option value="ATP" <?php if($Statut=="ATP"){echo "selected";}?>>ATP : Autorization to Proceed (See on Quality Plan for deadline) [At least 1 CMTE from DIS used, or 0 CMTE necessary, for the process application]</option>
									<option value="QTBD" <?php if($Statut=="QTBD"){echo "selected";}?>>QTBD : Qualification to be done [At least 1 CMTE from DIS used, or 0 CMTE necessary, for the process application]</option>
									<option value="Q*" <?php if($Statut=="Q*"){echo "selected";}?>>Q* : Qualified [Only use of Customer CMTE authorized]</option>
									<option value="QWL*" <?php if($Statut=="QWL*"){echo "selected";}?>>QWL* : Qualified with Limitation (See on Quality Plan for limitations) [Only use of Customer CMTE authorized]</option>
									<option value="ATP*" <?php if($Statut=="ATP*"){echo "selected";}?>>ATP* : Autorization to Proceed (See on Quality Plan for deadline) [Only use of Customer CMTE authorized]</option>
									<option value="QTBD*" <?php if($Statut=="QTBD*"){echo "selected";}?>>QTBD* : Qualification to be done [Only use of Customer CMTE authorized]</option>
									<option value="NA" <?php if($Statut=="NA"){echo "selected";}?>>NA : Not Applicable</option>
								</select>
							</td>
							<td><input type="checkbox" name="Qualif[]" class="qualifs" value="<?php echo $row['Id'];?>" <?php if($_GET['Mode']=="Modif" && $QualifAppartientParrainage==1){echo "checked='checked'";}?>></td>
						</tr>
					<?php
							}
						}
					?>
					</table>
				</td>
				<td colspan="2">
					<table class="TableCompetences" style="width:350px;">
					<?php
						$result=mysqli_query($bdd,"SELECT * FROM new_competences_formation ORDER BY Libelle ASC");
						$nbenreg=mysqli_num_rows($result);
						if($nbenreg>0)
						{
					?>
						<tr>
							<td colspan="2" class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?></td>
						</tr>
						<?php
							$Couleur="#EEEEEE";
							$Categorie=0;
							while($row=mysqli_fetch_array($result))
							{
								if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
								else{$Couleur="#EEEEEE";}
								$FormationAppartientParrainage=0;
								if($_GET['Mode']=="Modif")
								{
									$result3=mysqli_query($bdd,"SELECT * FROM new_competences_prestation_formation WHERE Id_Prestation=".$_GET["Id"]." AND Id_Formation=".$row["Id"]);
									$FormationAppartientParrainage=mysqli_num_rows($result3);
								}
						?>
						<tr bgcolor="<?php echo $Couleur;?>">
							<td width="300"><?php echo $row['Libelle'];?></td>
							<td><input type="checkbox" name="Formations[]" value="<?php echo $row['Id'];?>" <?php if($_GET['Mode']=="Modif" && $FormationAppartientParrainage==1){echo "checked='checked'";}?>></td>
						</tr>
					<?php
							}
						}
					?>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input class="Bouton" type="submit"
					<?php
						if($_GET['Mode']=="Modif"){
							if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
						}
						else{
							if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
						}
					?>
				></td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"SELECT * FROM new_competences_personne_prestation WHERE Id_Prestation=".$_GET['Id']);
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"DELETE FROM new_competences_prestation_qualification WHERE Id_Prestation=".$_GET['Id']);
			$result=mysqli_query($bdd,"DELETE FROM new_competences_prestation_formation WHERE Id_Prestation=".$_GET['Id']);
			$result=mysqli_query($bdd,"DELETE FROM new_competences_prestation WHERE Id=".$_GET['Id']);
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Vous ne pouvez pas supprimer cette prestation car une ou plusieurs personne y est rattachées.</font>";}
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	echo "<script>Recharge_Liste_Projet();</script>";
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
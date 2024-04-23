<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModif(Mode,Id)
		{var w=window.open("Modif_Heures_Supp.php?Mode="+Mode+"&Id="+Id,"PageFichier","status=no,menubar=no,width=950,height=550");
		w.focus();
		}
	function OuvreFenetreDuplique(Mode,Id)
		{var w=window.open("Modif_Heures_Supp.php?Mode="+Mode+"&Id="+Id,"PageFichier","status=no,menubar=no,width=950,height=430");
		w.focus();
		}
	function OuvreFenetreExcel(Prestation, Pole,Id_Personne,Date)
		{window.open("Liste_Heures_Supp_Export.php?Prestation="+Prestation+"&Pole="+Pole+"&Id_Personne="+Id_Personne+"&Date="+Date,"PageExcel","status=no,menubar=no,width=900,height=450");}
</script>
<?php
//Vérification des droits de lecture, écriture, administration
$DroitAjout=false;
$resultDroits=mysqli_query($bdd,"SELECT MIN(Id_Poste) FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne']);
$nbDroits=mysqli_num_rows($resultDroits);
$rowDroits=mysqli_fetch_array($resultDroits);
if($rowDroits[0]<3){$DroitAjout=true;}

//Gestion des 24h
if(date("w",mktime(0,0,0,date("m"),date("d")-1,date("Y")))==0){$DateJourMoins3=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-4,date("Y")));}
elseif(date("w",mktime(0,0,0,date("m"),date("d")-2,date("Y")))==0){$DateJourMoins3=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-4,date("Y")));}
elseif(date("w",mktime(0,0,0,date("m"),date("d"),date("Y")))==0){$DateJourMoins3=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-4,date("Y")));}
else{$DateJourMoins3=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-2,date("Y")));}

$requeteHSuppNonValidee="SELECT new_rh_heures_supp.*, new_competences_prestation.Libelle, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS NOMPrenom,";
$requeteHSuppNonValidee.=" (SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=new_rh_heures_supp.Id_Pole) AS Pole";
$requeteHSuppNonValidee.=" FROM new_rh_heures_supp";
$requeteHSuppNonValidee.=" LEFT JOIN new_competences_prestation ON new_rh_heures_supp.Id_Prestation=new_competences_prestation.Id";
$requeteHSuppNonValidee.=" LEFT JOIN new_rh_etatcivil ON new_rh_heures_supp.Id_Personne=new_rh_etatcivil.Id";
$requeteHSuppNonValidee.=" WHERE (Date1<='".$DateJourMoins3."' AND Etat2='')";
$requeteHSuppNonValidee.=" OR (Date2<='".$DateJourMoins3."' AND Etat3='' AND Etat2='Validée')";
$requeteHSuppNonValidee.=" OR (Date3<='".$DateJourMoins3."' AND Etat4='' AND Etat3='Validée' AND Etat2='Validée')";
$resultHSuppNonValidee=mysqli_query($bdd,$requeteHSuppNonValidee);
while($rowHSuppNonValidee=mysqli_fetch_array($resultHSuppNonValidee))
{
	//#################
	//##### EMAIL #####
	//#################
	//Récupération de l'email de la personne qui poste la demande d'heure supplémentaire
	$Email1="";
	$requete_Demandeur="SELECT Id, EmailPro FROM new_rh_etatcivil WHERE Login='".$rowHSuppNonValidee['Login1']."'";
	$result_Demandeur=mysqli_query($bdd,$requete_Demandeur);
	$row_Demandeur=mysqli_fetch_array($result_Demandeur);
	$Email1=$row_Demandeur[1];
	
	//Récupération des différents emails des responsables de niveau au dessus sur la prestation en question
	$Email2="";
	$Email3="";
	$Email4="";
	$requeteResponsablePostePrestation="SELECT DISTINCT new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Backup, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) as NomPrenom, new_rh_etatcivil.EmailPro, new_rh_etatcivil.Id";
	$requeteResponsablePostePrestation.=" FROM new_competences_personne_poste_prestation, new_rh_etatcivil";
	$requeteResponsablePostePrestation.=" WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id";
	$requeteResponsablePostePrestation.=" AND new_competences_personne_poste_prestation.Id_Prestation=".$rowHSuppNonValidee['Id_Prestation'];
	if($rowHSuppNonValidee['Id_Pole'] > 0){
		$requeteResponsablePostePrestation.=" AND new_competences_personne_poste_prestation.Id_Pole=".$rowHSuppNonValidee['Id_Pole'];
	}
	$requeteResponsablePostePrestation.=" ORDER BY new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Backup ASC";
	$resultResponsablePostePrestation=mysqli_query($bdd,$requeteResponsablePostePrestation);
	while($rowResponsablePostePrestation=mysqli_fetch_array($resultResponsablePostePrestation))
	{
		switch($rowResponsablePostePrestation[0])
		{
			case 2: if($rowResponsablePostePrestation[3]<>""){$Email2.=$rowResponsablePostePrestation[3].",";}break;
			case 3: if($rowResponsablePostePrestation[3]<>""){$Email3.=$rowResponsablePostePrestation[3].",";}break;
			case 4: if($rowResponsablePostePrestation[3]<>""){$Email4.=$rowResponsablePostePrestation[3].",";}break;
		}
	}
	$Email2=substr($Email2,0,strlen($Email2)-1);
	$Email3=substr($Email3,0,strlen($Email3)-1);
	$Email4=substr($Email4,0,strlen($Email4)-1);
	
	if($rowHSuppNonValidee['Etat2']=='')
	{
		$Commentaire='Validée en automatique cause non validation du responsable n+1 dans les 2 jours';
		$resultMAJHSupp=mysqli_query($bdd,"UPDATE new_rh_heures_supp SET Date2='".$DateJour."', Etat2='Validée', Commentaire2='".$Commentaire."' WHERE Id=".$rowHSuppNonValidee['Id']);
		$Destinataires=$Email3;
		$DestinatairesEnCopie=$Email2;
	}
	elseif($rowHSuppNonValidee['Etat3']=='')
	{
		$Commentaire='Validée en automatique cause non validation du responsable n+2 dans les 2 jours';
		$resultMAJHSupp=mysqli_query($bdd,"UPDATE new_rh_heures_supp SET Date3='".$DateJour."', Etat3='Validée', Commentaire3='".$Commentaire."' WHERE Id=".$rowHSuppNonValidee['Id']);
		$Destinataires=$Email4;
		$DestinatairesEnCopie=$Email3;
	}
	else
	{
		$Commentaire='Validée en automatique cause non validation du responsable n+3 dans les 2 jours';
		$resultMAJHSupp=mysqli_query($bdd,"UPDATE new_rh_heures_supp SET Date4='".$DateJour."', Etat4='Validée', Commentaire4='".$Commentaire."' WHERE Id=".$rowHSuppNonValidee['Id']);
		if($_SERVER['SERVER_NAME']=="extranet.aaa-aero.com"){
			$Destinataires="";
		}
		else{
			$Destinataires.="";
		}
		// $Destinataires="extranet@aaa-aero.com";
		$DestinatairesEnCopie=$Email4;
		if($Email3 <> ""){$DestinatairesEnCopie.=",".$Email3;}
		if($Email2 <> ""){$DestinatairesEnCopie.=",".$Email2;}
		if($Email1 <> ""){$DestinatairesEnCopie.=",".$Email1;}
		
		//Mettre à valider le planning
		
		$requeteHS="SELECT Id_Personne, Date FROM new_rh_heures_supp WHERE Id='".$rowHSuppNonValidee['Id']."'";
		$resultHS=mysqli_query($bdd,$requeteHS);
		$rowHS=mysqli_fetch_array($resultHS);
		
		$reqUpdate = "UPDATE new_planning_personne_vacationabsence ";
		$reqUpdate .= "SET ValidationResponsable = 0 ";
		$reqUpdate .= "WHERE Id_Personne = '".$rowHS['Id_Personne']."' ";
		$reqUpdate .= "AND DatePlanning = '".$rowHS['Date']."' ";
		$resultUpdate=mysqli_query($bdd,$reqUpdate);
	}
	
	$headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
	$headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
	
	if($DestinatairesEnCopie!=""){$headers .='Cc: '.$DestinatairesEnCopie." \n";}
	$message='<html><head><title>Heures Supplémentaires - Extranet V2 - Validée en automatique</title></head><body>Bonjour,<br><br>';
	$message.='La demande d\'heures supplémentaires suivante a été validée';
	$message.='<br><table border=1><tr><td>Site/Prestation payeur</td><td>Personne concernée</td><td>Date</td><td>Nb H Jour</td><td>Nb H nuit</td><td>Motif</td>';
	$message.='<tr><td>'.substr($rowHSuppNonValidee['Libelle'],0,7);
	if($rowHSuppNonValidee['Pole'] <> ""){
		$message.=' - '.addslashes($rowHSuppNonValidee['Pole']).' ';
	}
	$message.='</td>';
	$message.='<td>'.$rowHSuppNonValidee['NOMPrenom'].'</td>';
	$message.='<td>'.substr($rowHSuppNonValidee['Date'],8,2)."/".substr($rowHSuppNonValidee['Date'],5,2)."/".substr($rowHSuppNonValidee['Date'],0,4).'</td>';
	$message.='<td>'.addslashes($rowHSuppNonValidee['Nb_Heures_Jour']).'</td>';
	$message.='<td>'.addslashes($rowHSuppNonValidee['Nb_Heures_Nuit']).'</td>';
	$message.='<td>'.addslashes($rowHSuppNonValidee['Motif']).'</td>';
	$message.='</tr></table>';
	$message.=addslashes($Commentaire);
	if($rowHSuppNonValidee['Etat4']==''){$message.='<br>Veuillez vous rendre sur le site extranet AAA afin de la valider ou de la refuser';}
	$message.='<br><br>Bonne journée.<br><a href="https://extranet.aaa-aero.com">Extranet</a></body></html>';
	$objetMail="Heures Supplémentaires - Extranet V2";
	$objetMail.=" - ".substr($rowHSuppNonValidee['Libelle'],0,7);
	$objetMail.=" - ".$rowHSuppNonValidee['NOMPrenom'];
	$objetMail.=" - ".substr($rowHSuppNonValidee['Date'],0,4)."/".substr($rowHSuppNonValidee['Date'],5,2)."/".substr($rowHSuppNonValidee['Date'],8,2);
	$objetMail.=" - ".$rowHSuppNonValidee['Nb_Heures_Jour'].' HJ/ '.$rowHSuppNonValidee['Nb_Heures_Nuit']." HN";
	$objetMail.=" - Validée en automatique";
	
	mail($Destinataires, $objetMail , $message, $headers,'-f extranet@aaa-aero.com');
}

?>

<form class="test" action="Liste_Heures_Supp.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>
			<table class="GeneralPage" width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td class="TitrePage">Ressources Humaines # Heures supplémentaires</td>
					<?php
						if($DroitAjout)
						{
					?>
					<td width="25">
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');"><img src="../../Images/Ajout.gif" border="0" title="Ajouter une demande d'heure supplémentaire" alt="Ajouter une ligne de recherche"></a>
					</td>
					<?php
						}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td width="20%">
				<?php
				?>
				&nbsp; Prestation :
				<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
				<?php
				$req = "SELECT distinct(new_competences_personne_poste_prestation.Id_Prestation), (SELECT new_competences_prestation.Libelle FROM new_competences_prestation ";
				$req .= "WHERE new_competences_prestation.Id = new_competences_personne_poste_prestation.Id_Prestation) AS NomPrestation FROM new_competences_personne_poste_prestation WHERE ";
				$req .= "new_competences_personne_poste_prestation.Id_Personne=".$_SESSION['Id_Personne']." ORDER BY NomPrestation;";
				
				$resultPrestation=mysqli_query($bdd,$req);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationSelect = 0;
				$Selected = "";
				if ($nbPrestation > 0)
				{
					if (!empty($_GET['IdPrestationSelect'])){
						echo "<option name='0' value='0' Selected></option>";
						if ($PrestationSelect == 0){$PrestationSelect = $_GET['IdPrestationSelect'];}
						while($row=mysqli_fetch_array($resultPrestation))
						{
							if ($row[0] == $_GET['IdPrestationSelect']){
								$Selected = "Selected";
							}
							echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
							$Selected = "";
						}
					}
					elseif (!empty($_POST['prestations'])){
						echo "<option name='0' value='0' Selected></option>";
						if ($PrestationSelect == 0){$PrestationSelect = $_POST['prestations'];}
						while($row=mysqli_fetch_array($resultPrestation))
						{
							if ($row[0] == $_POST['prestations']){
								$Selected = "Selected";
							}
							echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
							$Selected = "";
						}
					}
					else{
						echo "<option name='0' value='0' Selected></option>";
						$PrestationSelect == 0;
						while($row=mysqli_fetch_array($resultPrestation))
						{
							echo "<option name='".$row[0]."' value='".$row[0]."'>".$row[1]."</option>";
						}
					}
				 }
				 ?>
				</select>
			</td>
			<td width=15%>
				&nbsp; Pôle :
				<select class="pole" style="width:100px;" name="pole" onchange="submit();">
				<?php

				$reqPole = "SELECT DISTINCT new_competences_personne_poste_prestation.Id_Pole, ";
				$reqPole .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id = new_competences_personne_poste_prestation.Id_Pole) AS LibellePole ";
				$reqPole .= "FROM new_competences_personne_poste_prestation WHERE ";
				$reqPole .= "new_competences_personne_poste_prestation.Id_Personne=".$_SESSION['Id_Personne']." AND new_competences_personne_poste_prestation.Id_Poste <3 ";
				$reqPole .= "AND new_competences_personne_poste_prestation.Id_Prestation=".$PrestationSelect." ORDER BY LibellePole;";
				
				$resultPole=mysqli_query($bdd,$reqPole);
				$nbPole=mysqli_num_rows($resultPole);
				
				$PoleSelect = 0;
				$Selected = "";
				if ($nbPole > 0)
				{
					echo "<option name='0' value='0' Selected></option>";
					if (!empty($_GET['Id_Pole'])){
						if ($PoleSelect == 0){$PoleSelect = $_GET['Id_Pole'];}
						while($row=mysqli_fetch_array($resultPole))
						{
							if ($row[0] == $_GET['Id_Pole']){$Selected = "Selected";}
							echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
							$Selected = "";
						}
					}
					elseif (!empty($_POST['pole'])){
						if ($PoleSelect == 0){$PoleSelect = $_POST['pole'];}
						while($row=mysqli_fetch_array($resultPole))
						{
							if ($row[0] == $_POST['pole']){$Selected = "Selected";}
							echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
							$Selected = "";
						}
					}
					else{
						while($row=mysqli_fetch_array($resultPole))
						{
							if ($PoleSelect == 0){$PoleSelect = 0;}
							echo "<option name='".$row[0]."' value='".$row[0]."'>".$row[1]."</option>";
						}
					}
				 }
				 ?>
				</select>
			</td>
			<td width=15%>
				&nbsp; Personne :
				<select id="personne" style="width:100px;" name="personne" onchange="submit();">
					<option value='0'></option>
					<?php
						$personne="";
						if(isset($_GET['personne'])){$personne = $_GET['personne'];}
						if(isset($_POST['personne'])){$personne = $_POST['personne'];}
						$requetePersonne="SELECT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom ";
						$requetePersonne.="FROM new_rh_etatcivil ";
						$requetePersonne.="ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
						$resultPersonne=mysqli_query($bdd,$requetePersonne);
						$NbPersonne=mysqli_num_rows($resultPersonne);
						while($rowPersonne=mysqli_fetch_array($resultPersonne))
						{
							echo "<option value='".$rowPersonne[0]."'";
							if ($personne == $rowPersonne[0]){echo " selected ";}
							echo ">".$rowPersonne[1]." ".$rowPersonne[2]."</option>\n";
						}
					?>
				</select>
			</td>
			<td width=25%>
				&nbsp; Etat :
					<?php
						$EnCours="";
						$Validee="";
						$Refusee="";
						if($_POST){
							if(isset($_POST['EnCours'])){$EnCours="checked";}
							if(isset($_POST['Validee'])){$Validee="checked";}
							if(isset($_POST['Refusee'])){$Refusee="checked";}
							
						}
						else{
							if(isset($_GET['EnCours'])){$EnCours="checked";}else{$EnCours="";}
							if(isset($_GET['debut'])){}else{$EnCours="checked";}
							if(isset($_GET['Validee'])){$Validee="checked";}else{$Validee="";}
							if(isset($_GET['Refusee'])){$Refusee="checked";}else{$Refusee="";}
						}
					?>
					<input type="checkbox" id="EnCours" name="EnCours" value="EnCours" <?php echo $EnCours; ?>>EN COURS &nbsp;&nbsp;
					<input type="checkbox" id="Validee" name="Validee" value="Validee" <?php echo $Validee; ?>>VALIDEE &nbsp;&nbsp;
					<input type="checkbox" id="Refusee" name="Refusee" value="Refusee" <?php echo $Refusee; ?>>REFUSEE &nbsp;&nbsp;
			</td>
			<td width=15%>
				&nbsp;
				Date :
				<?php
					$dateEnvoi =0;
					$dateRequete = "";
					if (!empty($_GET['DateSelect'])){
						$dateEnvoi = $_GET['DateSelect'];
						if  ($dateEnvoi <> ""){
							if ($NavigOk ==1){
								$dateDebut = date("Y-m-d",$_GET['DateSelect']);
								
								$tabDateDebut = explode('-', $dateDebut);
								$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[2], $tabDateDebut[0]);
							}
							else{
								$dateDebut = date("d/m/Y",$_GET['DateSelect']);
								
								$tabDateDebut = explode('/', $dateDebut);
								$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
							}
							$dateRequete = date("Y-m-d",$timestampDebut);
						}
						else{
							$dateDebut = "";
							$dateRequete = "";
						}
					}
					else{
						if (!empty($_POST['DateDeDebut'])){
							if  ($_POST['DateDeDebut'] <> ""){
								if ($NavigOk ==1){
									$dateDebut = $_POST['DateDeDebut'];
									$tabDateDebut = explode('-', $dateDebut);
									$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[2], $tabDateDebut[0]);
									$dateEnvoi = $timestampDebut;
									$dateRequete = date("Y-m-d",$timestampDebut);
								}
								else{
									$dateDebut = $_POST['DateDeDebut'];
									$tabDateDebut = explode('/', $dateDebut);
									$timestampDebut = mktime(0, 0, 0, $tabDateDebut[1], $tabDateDebut[0], $tabDateDebut[2]);
									$dateEnvoi = $timestampDebut;
									$dateRequete = date("Y-m-d",$timestampDebut);
								}
							}
							else{
								$dateDebut = "";
								$dateEnvoi = 0;
								$dateRequete = "";
							}
						}
						else{
							$dateDebut = "";
							$dateEnvoi = 0;
							$dateRequete = "";
						}
					}
				?>
				
				<input type="date" style="text-align:center;" name="DateDeDebut" size="10" value="<?php echo $dateDebut; ?>">
			</td>
			<td width=10%>
				<input class="Bouton" name="BtnDateDebut" size="10" type="submit" value="Valider">
			</td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr><td align="center">
	<a style="text-decoration:none;" class="Bouton" href="javascript:OuvreFenetreExcel('<?php echo $PrestationSelect; ?>','<?php echo $PoleSelect; ?>','<?php echo $personne; ?>','<?php echo $dateEnvoi; ?>');">
	&nbsp;Exporter au format Excel&nbsp;
	</a>
	</td></tr>
	<tr><td height="4"></td></tr>
	<?php
	if($nbDroits>0)
	{
		$requeteAnalyse="SELECT new_rh_heures_supp.Id ";
		$requete2="SELECT new_rh_heures_supp.*, new_competences_prestation.Libelle, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS NOMPrenom, new_competences_pole.Libelle as LibellePole ";
		$requete=" FROM new_rh_heures_supp";
		$requete.=" LEFT JOIN new_competences_prestation ON new_rh_heures_supp.Id_Prestation=new_competences_prestation.Id";
		$requete.=" LEFT JOIN new_rh_etatcivil ON new_rh_heures_supp.Id_Personne=new_rh_etatcivil.Id";
		$requete.=" LEFT JOIN new_competences_pole ON new_rh_heures_supp.Id_Pole=new_competences_pole.Id";
		$requete.=" WHERE new_rh_heures_supp.Id_Prestation IN (SELECT Id_Prestation FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne'].")";
		$requete.=" AND (new_rh_heures_supp.Id_Pole IN (SELECT Id_Pole FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION['Id_Personne'].") OR new_rh_heures_supp.Id_Pole = 0)";
		if($PrestationSelect<>0){
			$requete.=" AND new_rh_heures_supp.Id_Prestation=".$PrestationSelect." ";
			if($PoleSelect<>0){
				$requete.=" AND new_rh_heures_supp.Id_Pole=".$PoleSelect." ";
			}
		}
		if($personne<>0 && $personne<>""){
			$requete.=" AND new_rh_heures_supp.Id_Personne=".$personne." ";
		}
		if($dateRequete<>""){
			$requete.=" AND new_rh_heures_supp.Date='".$dateRequete."' ";
		}
		if($EnCours<>"" || $Validee<>"" || $Refusee<>""){
			$requete.=" AND ( ";
			if($EnCours<>""){
				$requete.=" (new_rh_heures_supp.Etat4='' AND new_rh_heures_supp.Etat3<>'Refusée' AND new_rh_heures_supp.Etat2<>'Refusée') OR ";
			}
			if($Validee<>""){
				$requete.=" new_rh_heures_supp.Etat4='Validée' OR ";
			}
			if($Refusee<>""){
				$requete.=" new_rh_heures_supp.Etat4='Refusée' OR new_rh_heures_supp.Etat3='Refusée' OR new_rh_heures_supp.Etat2='Refusée' OR ";
			}
			$requete=substr($requete,0,-3);
			$requete.=" ) ";
		}
		$requete.=" ORDER BY new_rh_heures_supp.Date1 DESC, new_rh_heures_supp.Etat4 ASC";
		$result=mysqli_query($bdd,$requeteAnalyse.$requete);
		
		if(isset($_GET['Page'])){$page=$_GET['Page'];}
		else{$page=0;}
		$requete3=" LIMIT ".($page*40).",40";
		$nbResulta=mysqli_num_rows($result);
		$result=mysqli_query($bdd,$requete2.$requete.$requete3);
		$nombreDePages=ceil($nbResulta/40);
		$couleur="#FFFFFF";

	?>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				$nbPage=0;
				$liste="";
				if($EnCours<>""){$liste.="&EnCours=".$EnCours;}
				if($Validee<>""){$liste.="&Validee=".$Validee;}
				if($Refusee<>""){$liste.="&Refusee=".$Refusee;}
				if($PrestationSelect<>""){$liste.="&IdPrestationSelect=".$PrestationSelect;}
				if($PoleSelect<>""){$liste.="&Id_Pole=".$PoleSelect;}
				if($personne<>""){$liste.="&personne=".$personne;}
				if($dateDebut<>""){$liste.="&DateSelect=".$dateDebut;}
				if($page>1){echo "<b> <a style='color:#00599f;' href='Liste_Heures_Supp.php?debut=1&Page=0".$liste."'><<</a> </b>";}
				$valeurDepart=1;
				if($page<=5){
					$valeurDepart=1;
				}
				elseif($page>=($nombreDePages-6)){
					$valeurDepart=$nombreDePages-6;
				}
				else{
					$valeurDepart=$page-5;
				}
				for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
					if($i<=$nombreDePages){
						if($i==($page+1)){
							echo "<b> [ ".$i." ] </b>"; 
						}	
						else{
							echo "<b> <a style='color:#00599f;' href='Liste_Heures_Supp.php?debut=1&Page=".($i-1)."".$liste."'>".$i."</a> </b>";
						}
					}
				}
				if($page<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_Heures_Supp.php?debut=1&Page=".($nombreDePages-1)."".$liste."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="15">Site</td>
					<td class="EnTeteTableauCompetences" width="50">Pôle</td>
					<td class="EnTeteTableauCompetences" width="120">Personne</td>
					<td class="EnTeteTableauCompetences" width="10">Nb H. jour</td>
					<td class="EnTeteTableauCompetences" width="10">Nb H. nuit</td>
					<td class="EnTeteTableauCompetences" width="60">Date HSup</td>
					<td class="EnTeteTableauCompetences" width="130">Emetteur</td>
					<td class="EnTeteTableauCompetences" width="60">Date emission</td>
					<td class="EnTeteTableauCompetences" width="130">Resp. N+1</td>
					<td class="EnTeteTableauCompetences" width="60">Date N+1</td>
					<td class="EnTeteTableauCompetences" width="40">Etat N+1</td>
					<td class="EnTeteTableauCompetences" width="130">Resp. N+2</td>
					<td class="EnTeteTableauCompetences" width="60">Date N+2</td>
					<td class="EnTeteTableauCompetences" width="40">Etat N+2</td>
					<td class="EnTeteTableauCompetences" width="130">Resp. N+3</td>
					<td class="EnTeteTableauCompetences" width="60">Date N+3</td>
					<td class="EnTeteTableauCompetences" width="40">Etat N+3</td>
					<td class="EnTeteTableauCompetences" width="15">Val.</td>
					<td class="EnTeteTableauCompetences" width="15">Dupli.</td>
					<td class="EnTeteTableauCompetences" width="15">Supp.</td>
					<td class='EnTeteTableauCompetences' width="40"><input type="submit"  onclick="if(!confirm('Etes-vous sûre de vouloir valider la sélection ?')) return false;" name="validerSelection" value="Valider sélection"></td>
				</tr>
	<?php
			
			if(isset($_POST['validerSelection'])){
				while($row=mysqli_fetch_array($result)){
					if (isset($_POST['check_'.$row['Id'].''])){
						//Validation des heures supplémentaires cochées
						$lestep = "step_".$row['Id']."";
						
						//#################
						//##### EMAIL #####
						//#################
						//Récupération de l'email de la personne qui poste la demande d'heure supplémentaire
						$Email1="";
						$requete_user="SELECT EmailPro FROM new_rh_etatcivil WHERE Login='".$row['Login1']."'";
						$result_user=mysqli_query($bdd,$requete_user);
						$row_user=mysqli_fetch_array($result_user);
						$Email1=$row_user['EmailPro'];
						
						//Récupération de la personne qui valide l'heure supplémentaire
						$PersonneLoguee="";
						$requete_PersonneLoguee="SELECT CONCAT(Nom,' ',Prenom) as NomPrenom FROM new_rh_etatcivil WHERE Login='".$_SESSION['Log']."'";
						$result_PersonneLoguee=mysqli_query($bdd,$requete_PersonneLoguee);
						$row_PersonneLoguee=mysqli_fetch_array($result_PersonneLoguee);
						$PersonneLoguee=$row_PersonneLoguee['NomPrenom'];
						
						//Récupération des différents emails des responsables de niveau au dessus sur la prestation en question
						$Email2="";
						$Email3="";
						$Email4="";
						$PersonneConnectee_IdPosteMaxSurPrestation=0;
						$requeteResponsablePostePrestation="SELECT DISTINCT new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Backup, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) as NomPrenom, new_rh_etatcivil.EmailPro, new_rh_etatcivil.Id";
						$requeteResponsablePostePrestation.=" FROM new_competences_personne_poste_prestation, new_rh_etatcivil";
						$requeteResponsablePostePrestation.=" WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id";
						$requeteResponsablePostePrestation.=" AND new_competences_personne_poste_prestation.Id_Prestation=".$row['Id_Prestation'];
						if($row['Id_Pole'] > 0){
							$requeteResponsablePostePrestation.=" AND new_competences_personne_poste_prestation.Id_Pole=".$row['Id_Pole'];
						}
						$requeteResponsablePostePrestation.=" ORDER BY new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Backup ASC";
						$resultResponsablePostePrestation=mysqli_query($bdd,$requeteResponsablePostePrestation);
						while($rowResponsablePostePrestation=mysqli_fetch_array($resultResponsablePostePrestation))
						{
							//Récupération de la valeur du poste le plus haut sur cette prestation de la personne connectée
							if($rowResponsablePostePrestation['Id']==$_SESSION['Id_Personne'] && $rowResponsablePostePrestation['Id']>$PersonneConnectee_IdPosteMaxSurPrestation)
							{
								$PersonneConnectee_IdPosteMaxSurPrestation=$rowResponsablePostePrestation['Id_Poste'];
							}
							
							switch($rowResponsablePostePrestation['Id_Poste'])
							{
								case 2: if($rowResponsablePostePrestation['EmailPro']<>""){$Email2.=$rowResponsablePostePrestation['EmailPro'].",";}break;
								case 3: if($rowResponsablePostePrestation['EmailPro']<>""){$Email3.=$rowResponsablePostePrestation['EmailPro'].",";}break;
								case 4: if($rowResponsablePostePrestation['EmailPro']<>""){$Email4.=$rowResponsablePostePrestation['EmailPro'].",";}break;
							}
						}
						$Email2=substr($Email2,0,strlen($Email2)-1);
						$Email3=substr($Email3,0,strlen($Email3)-1);
						$Email4=substr($Email4,0,strlen($Email4)-1);
						
						//4 étant le responsable Affaire : la validation des heures supplémentaire ne va pas plus loin
						if($PersonneConnectee_IdPosteMaxSurPrestation>4){$PersonneConnectee_IdPosteMaxSurPrestation=4;}
						
						//Requete UPDATE
						$requeteUpdate="UPDATE new_rh_heures_supp SET ";
						$requeteUpdate.=" Login".$_POST[$lestep]."='".$_SESSION["Log"]."',";
						$requeteUpdate.=" Date".$_POST[$lestep]."='".$DateJour."',";
						$requeteUpdate.=" Etat".$_POST[$lestep]."='Validée' ";
						//Validation ou refus automatiquement rempli si le valideur est responsable du/des niveau(x) au dessus
						if($PersonneConnectee_IdPosteMaxSurPrestation>$_POST[$lestep])
						{
							for($j=$_POST[$lestep]+1;$j<=$PersonneConnectee_IdPosteMaxSurPrestation;$j++)
							{
								$requeteUpdate.=",";
								$requeteUpdate.=" Login".$j."='".$_SESSION["Log"]."',";
								$requeteUpdate.=" Date".$j."='".$DateJour."',";
								$requeteUpdate.=" Etat".$j."='Validée',";
								$requeteUpdate.=" Commentaire".$j."='Validée automatiquement car responsable identique au précédent'";
							}
						}
						$requeteUpdate.=" WHERE Id=".$row['Id']."";
						$resultat=mysqli_query($bdd,$requeteUpdate);
						
						//Remplissage des différentes informations à inclure dans le mail
						$Destinataires="";
						$DestinatairesEnCopie="";
						switch($_POST[$lestep]){
							case 2 : 
								$Etat="Validée";
								$Commentaire="<br> - Commentaire N+".($_POST[$lestep]-1)." : ";
								$Destinataires=$Email3.",".$Email1;
								if($PersonneConnectee_IdPosteMaxSurPrestation>=3){$Destinataires.=",".$Email4;}
								if($PersonneConnectee_IdPosteMaxSurPrestation==4)
								{
									if($_SERVER['SERVER_NAME']=="extranet.aaa-aero.com"){
										$DestinatairesEnCopie="";
									}
									else{
										$DestinatairesEnCopie.="";
									}
									//PRESTATION LAPX à la demande d'Audrey
									if($row['Id_Prestation']==823){
										if($_SERVER['SERVER_NAME']=="extranet.aaa-aero.com"){
											$DestinatairesEnCopie.=",sibarrondo@aaa-aero.com";
										}
										else{
											$DestinatairesEnCopie.="informatique_tls@aaa-aero.com,";
										}
									}	
								}
								break;
							case 3 :
								$Etat="Validée";
								$Commentaire="<br> - Commentaire N+".($_POST[$lestep]-1)." : ";
								$Destinataires=$Email4.",".$Email1;
								if($PersonneConnectee_IdPosteMaxSurPrestation==4)
								{
									if($_SERVER['SERVER_NAME']=="extranet.aaa-aero.com"){
										$DestinatairesEnCopie="";
									}
									else{
										$DestinatairesEnCopie.="";
									}
									//PRESTATION LAPX à la demande d'Audrey
									if($row['Id_Prestation']==823){
										if($_SERVER['SERVER_NAME']=="extranet.aaa-aero.com"){
											$DestinatairesEnCopie.=",sibarrondo@aaa-aero.com";
										}
										else{
											$DestinatairesEnCopie.="informatique_tls@aaa-aero.com,";
										}
									}	
								}
								break;
							case 4 :
								$Etat="Validée";
								$Commentaire="<br> - Commentaire N+".($_POST[$lestep]-1)." : ";
								$Destinataires=$Email1;
								if($_SERVER['SERVER_NAME']=="extranet.aaa-aero.com"){
									$DestinatairesEnCopie="";
								}
								else{
									$DestinatairesEnCopie.="";
								}
								//PRESTATION LAPX à la demande d'Audrey
								if($row['Id_Prestation']==823){
									if($_SERVER['SERVER_NAME']=="extranet.aaa-aero.com"){
										$DestinatairesEnCopie.=",sibarrondo@aaa-aero.com";
									}
									else{
										$DestinatairesEnCopie.="informatique_tls@aaa-aero.com,";
									}
								}	
								break;
						}

						//Récupération du libellé du site
						$requete_Site="SELECT Libelle FROM new_competences_prestation WHERE Id=".$row['Id_Prestation'];
						$result_Site=mysqli_query($bdd,$requete_Site);
						$row_Site=mysqli_fetch_array($result_Site);
						$Site=$row_Site['Libelle'];
						
						//Récupération du libellé du pole
						$requete_Pole="SELECT Libelle FROM new_competences_pole WHERE Id=".$row['Id_Pole'];
						$result_Pole=mysqli_query($bdd,$requete_Pole);
						$row_Pole=mysqli_fetch_array($result_Pole);
						$Pole=$row_Pole['Libelle'];
						
						$NOMPrenom="";
						$requete_NOMPrenom="SELECT CONCAT(Nom,' ',Prenom) AS NOMPrenom FROM new_rh_etatcivil WHERE Id=".$row['Id_Personne'];
						$result_NOMPrenom=mysqli_query($bdd,$requete_NOMPrenom);
						$row_NOMPrenom=mysqli_fetch_array($result_NOMPrenom);
						$NOMPrenom=$row_NOMPrenom['NOMPrenom'];
					
						$headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
						$headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
						if($DestinatairesEnCopie!=""){$headers .='Cc: '.$DestinatairesEnCopie."\n";}
						$message='<html><head><title>Heures Supplémentaires - Extranet V2 - '.$Etat.'</title></head><body>Bonjour,<br><br>';
						$message.='La demande d\'heures supplémentaires suivante a été '.$Etat.' par '.$PersonneLoguee;
						$message.='<br><table border=1><tr><td>Site/Prestation payeur</td><td>Personne concernée</td><td>Date</td><td>Nb H Jour</td><td>Nb H nuit</td><td>Motif</td>';
						$message.='<tr><td>'.substr($Site,0,7);
						if($Pole <> ""){
							$message.=' - '.addslashes($Pole).' ';
						}
						$message.='</td>';
						$message.='<td>'.$NOMPrenom.'</td>';
						$message.='<td>'.$row['Date'].'</td>';
						$message.='<td>'.addslashes($row['Nb_Heures_Jour']).'</td>';
						$message.='<td>'.addslashes($row['Nb_Heures_Nuit']).'</td>';
						$message.='<td>'.addslashes($row['Motif']).'</td>';
						$message.='</tr></table>';
						$message.=addslashes($Commentaire);
						if($_POST[$lestep]<4 && $PersonneConnectee_IdPosteMaxSurPrestation<4){$message.='<br>Veuillez vous rendre sur le site extranet AAA afin de la valider ou de la refuser';}
						$message.='<br><br>Bonne journée.<br><a href="https://extranet.aaa-aero.com">Extranet</a></body></html>';
						$objetMail="Heures Supplémentaires - Extranet V2";
						$objetMail.=" - ".substr($Site,0,7);
						$objetMail.=" - ".$NOMPrenom;
						$objetMail.=" - ".$row['Date'];
						$objetMail.=" - ".$row['Nb_Heures_Jour'].' HJ/ '.$row['Nb_Heures_Nuit']." HN";
						$objetMail.=" - ".$Etat;
						if(mail($Destinataires, $objetMail, $message, $headers,'-f extranet@aaa-aero.com')){}
						else{echo 'Le message n\'a pu être envoyé';}
						
						if($PersonneConnectee_IdPosteMaxSurPrestation == 4){
							//Mettre à valider le planning
							$reqUpdate = "UPDATE new_planning_personne_vacationabsence ";
							$reqUpdate .= "SET ValidationResponsable = 0 ";
							$reqUpdate .= "WHERE Id_Personne = '".$row['Id_Personne']."' ";
							$reqUpdate .= "AND DatePlanning = '".$row['Date']."' ";
							$resultUpdate=mysqli_query($bdd,$reqUpdate);
						}
					}
				}
			}
			$result=mysqli_query($bdd,$requete2.$requete.$requete3);
			
			//Affichage de la liste
			while($row=mysqli_fetch_array($result))
			{
				if($row['Etat4']!=''){$step=4;}
				elseif($row['Etat3']!=''){$step=3;}
				elseif($row['Etat2']!=''){$step=2;}
				else{$step=1;}
				
				//Récupération des différents noms des responsables de niveau au dessus sur la prestation en question
				$Responsable2="";
				$Responsable3="";
				$Responsable4="";
				$PersonneConnectee_IdPosteMaxSurPrestation=0;
				$PersonneConnectee_OkpourModifStep=false;
				$requeteResponsablePostePrestation="SELECT DISTINCT new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Backup, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) as NomPrenom, new_rh_etatcivil.Id";
				$requeteResponsablePostePrestation.=" FROM new_competences_personne_poste_prestation, new_rh_etatcivil";
				$requeteResponsablePostePrestation.=" WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id";
				$requeteResponsablePostePrestation.=" AND new_competences_personne_poste_prestation.Id_Prestation=".$row['Id_Prestation'];
				if($row['Id_Pole']>0){
					$requeteResponsablePostePrestation.=" AND new_competences_personne_poste_prestation.Id_Pole=".$row['Id_Pole'];
				}
				$requeteResponsablePostePrestation.=" ORDER BY new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Backup ASC";
				$resultResponsablePostePrestation=mysqli_query($bdd,$requeteResponsablePostePrestation);
				while($rowResponsablePostePrestation=mysqli_fetch_array($resultResponsablePostePrestation))
				{
					//Récupération de la valeur du poste le plus haut sur cette prestation de la personne connectée & &identification si action au step en cours de la demande
					if($rowResponsablePostePrestation['Id']==$_SESSION['Id_Personne'] && $rowResponsablePostePrestation['Id']>$PersonneConnectee_IdPosteMaxSurPrestation){$PersonneConnectee_IdPosteMaxSurPrestation=$rowResponsablePostePrestation['Id'];}
					if($rowResponsablePostePrestation['Id']==$_SESSION['Id_Personne'] && $rowResponsablePostePrestation['Id_Poste']==$step+1){$PersonneConnectee_OkpourModifStep=true;}
					
					switch($rowResponsablePostePrestation['Id_Poste'])
					{
						case 2: $Responsable2.=$rowResponsablePostePrestation['NomPrenom']."<br>";break;
						case 3: $Responsable3.=$rowResponsablePostePrestation['NomPrenom']."<br>";break;
						case 4: $Responsable4.=$rowResponsablePostePrestation['NomPrenom']."<br>";break;
					}
				}
				$Responsable2=substr($Responsable2,0,strlen($Responsable2)-4);
				$Responsable3=substr($Responsable3,0,strlen($Responsable3)-4);
				$Responsable4=substr($Responsable4,0,strlen($Responsable4)-4);
				$requete_user="SELECT CONCAT(Nom,' ',Prenom) AS NomPrenom FROM new_rh_etatcivil WHERE Login='".$row['Login1']."'";		
				$result_user=mysqli_query($bdd,$requete_user);
				$row_user=mysqli_fetch_array($result_user);
				$Responsable1=$row_user['NomPrenom'];
				
				//Mise en variable des noms des validateurs si existants sinon groupe de responsables définis dans la hérarchie du personnel
				$ValideEnAuto2="";
				$ValideEnAuto3="";
				$ValideEnAuto4="";
				if($step>=2)
				{
					if($row['Login2']<>"")
					{
						$requete_user="SELECT CONCAT(Nom,' ',Prenom) AS NomPrenom FROM new_rh_etatcivil WHERE Login='".$row['Login2']."'";
						$result_user=mysqli_query($bdd,$requete_user);
						$row_user=mysqli_fetch_array($result_user);
						$Responsable2=$row_user['NomPrenom'];
					}
					else{$ValideEnAuto2=" [Auto]";}
				}
				if($step>=3)
				{
					if($row['Login3']<>"")
					{
						$requete_user="SELECT CONCAT(Nom,' ',Prenom) AS NomPrenom FROM new_rh_etatcivil WHERE Login='".$row['Login3']."'";
						$result_user=mysqli_query($bdd,$requete_user);
						$row_user=mysqli_fetch_array($result_user);
						$Responsable3=$row_user['NomPrenom'];
					}
					else{$ValideEnAuto3=" [Auto]";}
				}
				if($step>=4)
				{
					if($row['Login4']<>"")
					{
						$requete_user="SELECT CONCAT(Nom,' ',Prenom) AS NomPrenom FROM new_rh_etatcivil WHERE Login='".$row['Login4']."'";
						$result_user=mysqli_query($bdd,$requete_user);
						$row_user=mysqli_fetch_array($result_user);
						$Responsable4=$row_user['NomPrenom'];
					}
					else{$ValideEnAuto4=" [Auto]";}
				}
				
				if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
				else{$couleur="#FFFFFF";}
				if($row['Etat4']=="Validée"){$couleur="#DCFCDD";}
				if($row['Etat2']=="Refusée" || $row['Etat3']=="Refusée" || $row['Etat4']=="Refusée"){$couleur="#FCDCDC";}
	?>
				<tr bgcolor="<?php echo $couleur;?>">
					<td><?php echo substr(stripslashes($row['Libelle']),0,7);?></td>
					<td><?php echo stripslashes($row['LibellePole']);?></td>
					<td><?php echo stripslashes($row['NOMPrenom']);?></td>
					<td><?php echo stripslashes($row['Nb_Heures_Jour']); ?></td>
					<td><?php echo stripslashes($row['Nb_Heures_Nuit']); ?></td>
					<td title="<?php echo stripslashes($row['Motif']);?>"><?php echo stripslashes($row['Date']); ?></td>
					<td><?php echo $Responsable1;?></td>
					<td><?php echo stripslashes($row['Date1']);?></td>
					<td><?php echo $Responsable2;?></td>
					<td><?php echo stripslashes($row['Date2']); ?></td>
					<td title="<?php echo stripslashes($row['Commentaire2']);?>"><?php echo stripslashes($row['Etat2']); echo $ValideEnAuto2; ?></td>
					<td><?php echo $Responsable3;?></td>
					<td><?php echo stripslashes($row['Date3']); ?></td>
					<td title="<?php echo stripslashes($row['Commentaire3']);?>"><?php echo stripslashes($row['Etat3']); echo $ValideEnAuto3; ?></td>
					<td><?php echo $Responsable4;?></td>
					<td><?php echo stripslashes($row['Date4']); ?></td>
					<td title="<?php echo stripslashes($row['Commentaire4']);?>"><?php echo stripslashes($row['Etat4']); echo $ValideEnAuto4; ?></td>
					<td>
						<?php if($PersonneConnectee_OkpourModifStep && $step<4 && $row['Etat2']!="Refusée" && $row['Etat3']!="Refusée" && $row['Etat4']!="Refusée"){ ?>
						<a class="LigneTableauRecherchePersonne" href="javascript:OuvreFenetreModif('Modif','<?php echo $row['Id']; ?>');"><img src="../../Images/Modif.gif" border="0" alt="Modification" title="Modifier"></a>
						<?php }	?>
					</td>
					<td>
						<?php if($PersonneConnectee_IdPosteMaxSurPrestation<=2){ ?>
						<a class="LigneTableauRecherchePersonne" href="javascript:OuvreFenetreDuplique('Duplique','<?php echo $row['Id']; ?>');"><img src="../../Images/Duplication.gif" border="0" alt="Duplication" title="Dupliquer""></a>
						<?php } ?>
					</td>
					<td>
						<?php if($_SESSION['Log']==$row['Login1']){ ?>
						<a class="LigneTableauRecherchePersonne" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){OuvreFenetreModif('Suppr','<?php echo $row['Id']; ?>');}else{return false;}"><img src="../../Images/Suppression.gif" border="0" title="Supprimer" alt="Suppression"></a>
						<?php } ?>
					</td>
					<td align="center">
						<?php if($PersonneConnectee_OkpourModifStep && $step<4 && $row['Etat2']!="Refusée" && $row['Etat3']!="Refusée" && $row['Etat4']!="Refusée"){
							echo "<input type='checkbox' name='check_".$row['Id']."' value='' checked>";
							$steps = $step + 1;
							echo "<input type='hidden' name='step_".$row['Id']."' value='".$steps."'>";
						} ?>
					</td>
				</tr>
			<?php
			}	//Fin boucle
			?>
			</table>
		</td>
	</tr>
<?php
		mysqli_free_result($result);	// Libération des résultats
	}			//Fin vérification des droits
	else
	{
?>
		<tr><td class="Erreur">Vous n'avez pas les droits pour afficher le contenu de ce dossier.</td></tr>
<?php
	}
?>

</table>
</form>
<?php
	mysqli_free_result($resultDroits);	// Libération des résultats
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
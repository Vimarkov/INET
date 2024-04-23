<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModif(Menu,Id)
		{var w=window.open("Suppr_MouvementPersonnel.php?Id="+Id+"&Menu="+Menu+"&TDB="+document.getElementById('TDB').value+"&OngletTDB="+document.getElementById('OngletTDB').value,"PageMouement","status=no,menubar=no,width=50,height=50");
		w.focus();
		}
	function OuvreFenetreRefus(Menu,Id,Step){
		if(document.getElementById('Langue').value=="EN"){texte='Are you sure you want to refuse?';}
		else{texte='Etes-vous sûr de vouloir refuser ?';}
		if(window.confirm(texte)){
			var w=window.open("Refuser_MouvementPersonnel.php?Id="+Id+"&Menu="+Menu+"&Step="+Step+"&TDB="+document.getElementById('TDB').value+"&OngletTDB="+document.getElementById('OngletTDB').value,"PageHS","status=no,menubar=no,scrollbars=yes,width=800,height=300");
		}			
	}
	function OuvreFenetreExcel(Menu)
		{window.open("Export_MouvementPersonnel.php?Menu="+Menu,"PageExcel","status=no,menubar=no,width=900,height=450");}
	function CocherValide(){
		if(document.getElementById('check_Valide').checked==true){
			var elements = document.getElementsByClassName('check');
			for (i=0; i<elements.length; i++){
			  elements[i].checked=true;
			}
		}
		else{
			var elements = document.getElementsByClassName('check');
			for (i=0; i<elements.length; i++){
			  elements[i].checked=false;
			}
		}
	}
	function CocherPriseEnCompte(){
		if(document.getElementById('check_PriseEnCompte').checked==true){
			var elements = document.getElementsByClassName('checkPriseEnCompte');
			for (i=0; i<elements.length; i++){
			  elements[i].checked=true;
			}
		}
		else{
			var elements = document.getElementsByClassName('checkPriseEnCompte');
			for (i=0; i<elements.length; i++){
			  elements[i].checked=false;
			}
		}
	}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

function Titre1($Libelle,$Lien,$Selected){
	$tiret="";
	if($Selected==true){$tiret="border-bottom:4px solid white;";}
	echo "<td style=\"width:70px;height:30px;border-spacing:0;text-align:center;color:#0c4985;valign:top;font-weight:bold;".$tiret."\">
		<a style=\"text-decoration:none;width:70px;height:30px;border-spacing:0;text-align:center;color:#0c4985;valign:top;font-weight:bold;\" onmouseover=\"this.style.color='#0c4985';\" onmouseout=\"this.style.color='#0c4985';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >".$Libelle."</a></td>\n";
}

$TDB=0;
if($_GET){
	if(isset($_GET['TDB'])){
		$TDB=$_GET['TDB'];
	}
}
else{
	$TDB=$_POST['TDB'];
}
$OngletTDB="";
if($_GET){
	if(isset($_GET['OngletTDB'])){
		$OngletTDB=$_GET['OngletTDB'];
	}
}
else{
	$OngletTDB=$_POST['OngletTDB'];
}
//Interface qui ne concerne que les managers
?>

<form class="test" action="Liste_MouvementPersonnel.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<input type="hidden" name="TDB" id="TDB" value="<?php echo $TDB; ?>" />
	<input type="hidden" name="OngletTDB" id="OngletTDB" value="<?php echo $OngletTDB; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#1365b6;">
				<tr>
					<td class="TitrePage">
					<?php
					$leMenu=$Menu;
					if($TDB>0){$leMenu=$TDB;}
					if($OngletTDB<>""){$leMenu.="&OngletTDB=".$OngletTDB;}
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$leMenu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Liste des mouvements de personnel";}else{echo "List of staff movements";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td colspan="5">
			<table style="width:100%; border-spacing:0;">
				<tr bgcolor="#92c3f4">
					<?php
						$ParametreTDB="";
						if($TDB>0){$ParametreTDB="&TDB=".$TDB;}
						if($OngletTDB<>""){$ParametreTDB.="&OngletTDB=".$OngletTDB;}
						if($_SESSION["Langue"]=="FR"){Titre1("MOUVEMENTS EN COURS","Outils/PlanningV2/Liste_MouvementPersonnel.php?Menu=".$Menu.$ParametreTDB,true);}
						else{Titre1("MOVEMENTS IN PROGRESS","Outils/PlanningV2/Liste_MouvementPersonnel.php?Menu=".$Menu."",true);}

						if($_SESSION["Langue"]=="FR"){Titre1("HISTORIQUE","Outils/PlanningV2/Liste_MouvementPersonnelHistorique.php?Menu=".$Menu.$ParametreTDB,false);}
						else{Titre1("HISTORICAL","Outils/PlanningV2/Liste_MouvementPersonnelHistorique.php?Menu=".$Menu."",false);}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td colspan="5">
			<table style="width:100%; border-spacing:0;">
				<tr>
					<td width="4" style="background-color:#4e9dec;font-weight:bold;color:#000000;font-size:12px;height:20px;border-radius:25px 25px 25px 25px;">
						&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Arrivées";}else{echo "Arrival";}?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr><td height="4"></td></tr>
	<?php
		$requete="SELECT rh_personne_mouvement.Id, rh_personne_mouvement.Id_Personne,rh_personne_mouvement.Id_PrestationDepart,rh_personne_mouvement.Id_PoleDepart,
			rh_personne_mouvement.Id_Prestation,rh_personne_mouvement.Id_Pole,rh_personne_mouvement.DateCreation,rh_personne_mouvement.Id_Createur,
			rh_personne_mouvement.EtatValidation,rh_personne_mouvement.DateDebut,rh_personne_mouvement.DateFin,
			(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_PrestationDepart) AS PrestaDepart,
			(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Presta,
			(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_PoleDepart) AS PoleDepart,
			(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_Pole) AS Pole,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne) AS Personne, 
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_mouvement.Id_Createur) AS Createur ";
		$requete.=" FROM rh_personne_mouvement
					WHERE EtatValidation=0
					AND Suppr=0 ";
		
		$requete.="AND CONCAT(rh_personne_mouvement.Id_Prestation,'_',rh_personne_mouvement.Id_Pole) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$_SESSION["Id_Personne"]."
					AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
					) ";
		$requete.=" ORDER BY Id DESC";
		
		$result=mysqli_query($bdd,$requete);
		$nbResulta=mysqli_num_rows($result);
		$couleur="#FFFFFF";

	?>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "N° demande";}else{echo "Request number";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Provenance";}else{echo "Origin";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Destination";}else{echo "Destination";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Date création";}else{echo "Creation Date";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Donneur d'ordre";}else{echo "Customer";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début";}else{echo "Start date";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin";}else{echo "End date";} ?></td>
					<td class='EnTeteTableauCompetences' width="15%" style="text-align:center;">
						<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Valider";}else{echo "Validate";}?>" onclick="if(!confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir valider la sélection ?";}else{echo "Are you sure you want to validate the selection?";} ?>')) return false;" name="validerSelection" value="<?php if($_SESSION["Langue"]=="FR"){echo "Valider";}else{echo "Validate";} ?>"><br>
						<input type='checkbox' id="check_Valide" name="check_Valide" value="" checked onchange="CocherValide()">
					</td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-align:center;"><?php if($_SESSION["Langue"]=="FR"){echo "Refuser";}else{echo "Refuse";} ?></td>
				</tr>
	<?php
			if(isset($_POST['validerSelection'])){
				while($row=mysqli_fetch_array($result)){
					if (isset($_POST['check_'.$row['Id'].''])){
						$requeteUpdate="UPDATE rh_personne_mouvement SET 
								Id_Validateur=".$_SESSION['Id_Personne'].",
								DateValidationTransfert='".date('Y-m-d')."',
								EtatValidation=1
								WHERE Id=".$row['Id']." ";
						$resultat=mysqli_query($bdd,$requeteUpdate);
						$req="SELECT Id, DateDebut, DateFin, Id_Prestation,Id_Pole,Id_Personne,Id_PrestationDepart,Id_PoleDepart FROM rh_personne_mouvement WHERE Id=".$row['Id'];
						$resultatSel=mysqli_query($bdd,$req);
						$rowSel=mysqli_fetch_array($resultatSel);
						$IdCree=$row['Id'];
						
						$dateFin='0001-01-01';
						$req="SELECT Id,DateFin
						FROM rh_personne_mouvement
								WHERE (rh_personne_mouvement.DateDebut<='".$rowSel['DateFin']."' OR '".$rowSel['DateFin']."'<='0001-01-01')
								AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$rowSel['DateDebut']."')
								AND rh_personne_mouvement.EtatValidation=1 
								AND rh_personne_mouvement.Id_Personne=".$rowSel['Id_Personne']."
								AND rh_personne_mouvement.Suppr=0
								AND Id_Prestation=".$rowSel['Id_PrestationDepart']."
								AND Id_Pole=".$rowSel['Id_PoleDepart']."
								AND rh_personne_mouvement.Id<>".$rowSel['Id']."
								ORDER BY DateFin DESC";
						$resultatMod=mysqli_query($bdd,$req);
						$nbResultaMod=mysqli_num_rows($resultatMod);
						if($nbResultaMod>0){
							$rowMod=mysqli_fetch_array($resultatMod);
							$dateFin=$rowMod['DateFin'];
						}
						
						
						$req="SELECT Id,DateFin
						FROM rh_personne_mouvement
								WHERE (rh_personne_mouvement.DateDebut<='".$rowSel['DateFin']."' OR '".$rowSel['DateFin']."'<='0001-01-01')
								AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$rowSel['DateDebut']."')
								AND rh_personne_mouvement.EtatValidation=1 
								AND rh_personne_mouvement.Id_Personne=".$rowSel['Id_Personne']."
								AND rh_personne_mouvement.Suppr=0
								AND Id_Prestation=".$rowSel['Id_PrestationDepart']."
								AND Id_Pole=".$rowSel['Id_PoleDepart']."
								AND rh_personne_mouvement.Id<>".$rowSel['Id']."
								ORDER BY DateFin ASC";
						$resultatMod=mysqli_query($bdd,$req);
						$nbResultaMod=mysqli_num_rows($resultatMod);
						if($nbResultaMod>0){
							$rowMod2=mysqli_fetch_array($resultatMod);
							if($rowMod2['DateFin']<='0001-01-01'){
								$dateFin=$rowMod['DateFin'];
							}
						}

						//Mise à jour de la date de fin de la personne 
						$req="UPDATE rh_personne_mouvement
							SET DateFin='".date("Y-m-d",strtotime($rowSel['DateDebut']." -1 day"))."'
							WHERE (DateDebut<='".$rowSel['DateFin']."'  OR '".$rowSel['DateFin']."'<='0001-01-01')
							AND (DateFin<='0001-01-01' OR DateFin>='".$rowSel['DateDebut']."')
							AND EtatValidation=1 
							AND Id_Personne=".$rowSel['Id_Personne']."
							AND Id_Prestation=".$rowSel['Id_PrestationDepart']."
							AND Id_Pole=".$rowSel['Id_PoleDepart']."
							AND Suppr=0
							AND Id<>".$rowSel['Id']." ";
						$resultatUpdate=mysqli_query($bdd,$req);
						
						//Supprimer si la date de début > date de fin
						$req="UPDATE rh_personne_mouvement
							SET Suppr=1,
							Id_Suppr=".$_SESSION['Id_Personne'].",
							DateSuppr='".date('Y-m-d')."' 
							WHERE DateDebut>DateFin
							AND DateFin>'0001-01-01'
							AND EtatValidation=1 
							AND Id_Personne=".$rowSel['Id_Personne']."
							AND Suppr=0
							AND Id<>".$rowSel['Id']." ";
						$resultatUpdate=mysqli_query($bdd,$req);
						
						if($rowSel['DateFin']>'0001-01-01'){
							if($nbResultaMod>0){
								//Création de la suite
								$req="INSERT INTO rh_personne_mouvement (Id_PrestationDepart,Id_PoleDepart,Id_Prestation,Id_Pole,Id_Personne,DateDebut,DateFin,Id_Createur,DateCreation,EtatValidation,DateValidationTransfert)
									SELECT 
									".$rowSel['Id_Prestation']." AS Id_PrestationDepart,
									".$rowSel['Id_Pole']." AS Id_PoleDepart,
									Id_PrestationDepart AS Id_Prestation,
									Id_PoleDepart AS Id_Pole,
									Id_Personne,
									'".date("Y-m-d",strtotime($rowSel['DateFin']." +1 day"))."' AS DateDebut,
									'".$dateFin."' AS DateFin,
									".$_SESSION['Id_Personne']." AS Id_Createur,
									'".date('Y-m-d')."' AS DateCreation, 
									1,
									'".date('Y-m-d')."'
									FROM rh_personne_mouvement
									WHERE rh_personne_mouvement.Id=".$rowSel['Id']." ";
								$resultatInsert=mysqli_query($bdd,$req);
								$IdCree = mysqli_insert_id($bdd);
							}
						}
						else{
							//Suppression si existe un mouvement identique mais avec une date de début >
							$req="UPDATE rh_personne_mouvement
								SET Suppr=1,
								Id_Suppr=".$_SESSION['Id_Personne'].",
								DateSuppr='".date('Y-m-d')."' 
								WHERE Id<>".$IdCree." 
								AND Id_Prestation=".$rowSel['Id_Prestation']." 
								AND Id_Pole=".$rowSel['Id_Pole']." 
								AND Id_Personne=".$rowSel['Id_Personne']."
								AND DateDebut>='".$rowSel['DateDebut']."' 
								AND DateFin<='0001-01-01' ";
							$resultatSuppr=mysqli_query($bdd,$req);
						}
					}
				}
			}
			$result=mysqli_query($bdd,$requete);
			
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
					
					$poleDepart="";
					if($row['Id_PoleDepart']>0){$poleDepart=" - ".$row['PoleDepart'];}
					$pole="";
					if($row['Id_Pole']>0){$pole=" - ".$row['Pole'];}
		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td align="center"><?php echo stripslashes($row['Id']);?></td>
						<td><?php echo stripslashes($row['Personne']);?></td>
						<td><?php echo substr(stripslashes($row['PrestaDepart']),0,7).$poleDepart;?></td>
						<td><?php echo substr(stripslashes($row['Presta']),0,7).$pole;?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateCreation']);?></td>
						<td><?php echo stripslashes($row['Createur']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateDebut']);?></td>
						<td><?php if($row['DateFin']<="0001-01-01"){echo "-";}else{echo AfficheDateJJ_MM_AAAA($row['DateFin']);}?></td>
						<td align="center">
							<?php 
								echo "<input class='check' type='checkbox' name='check_".$row['Id']."' value='' checked>";
							?>
						</td>
						<td align="center">
							<a style="text-decoration:none;" title="<?php if($_SESSION['Langue']=="FR"){echo "Refuser";}else{echo "Refuse";}?>" href="javascript:OuvreFenetreRefus('<?php echo $Menu; ?>','<?php echo $row['Id']; ?>')"><img src="../../Images/supprimer.png" width="18px" border="0" alt="Refuse" title="Refuse"></a>
						</td>
					</tr>
				<?php
				}	//Fin boucle
			}
			?>
			</table>
		</td>
	</tr>
	<tr>
		<td height="30"></td>
	</tr>
	<tr>
		<td colspan="5">
			<table style="width:100%; border-spacing:0;">
				<tr>
					<td width="4" style="background-color:#4e9dec;font-weight:bold;color:#000000;font-size:12px;height:20px;border-radius:25px 25px 25px 25px;">
						&nbsp;&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Départs";}else{echo "Departures";}?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr><td height="4"></td></tr>
	<?php
		$requete="SELECT rh_personne_mouvement.Id, rh_personne_mouvement.Id_Personne,rh_personne_mouvement.Id_PrestationDepart,rh_personne_mouvement.Id_PoleDepart,
			rh_personne_mouvement.Id_Prestation,rh_personne_mouvement.Id_Pole,rh_personne_mouvement.DateCreation,rh_personne_mouvement.Id_Createur,
			rh_personne_mouvement.EtatValidation,rh_personne_mouvement.DateDebut,rh_personne_mouvement.DateFin,
			(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_PrestationDepart) AS PrestaDepart,
			(SELECT Libelle FROM new_competences_prestation WHERE new_competences_prestation.Id=Id_Prestation) AS Presta,
			(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_PoleDepart) AS PoleDepart,
			(SELECT Libelle FROM new_competences_pole WHERE new_competences_pole.Id=Id_Pole) AS Pole,
			(SELECT Libelle FROM rh_raisonrefus WHERE rh_raisonrefus.Id=Id_RaisonRefus) AS RaisonRefus,CommentaireRefus,
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne) AS Personne, 
			(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=rh_personne_mouvement.Id_Createur) AS Createur ";
		$requete.=" FROM rh_personne_mouvement
					WHERE DatePriseEnCompteDemandeur<='0001-01-01'
					AND Suppr=0 ";
		
		$requete.="AND CONCAT(rh_personne_mouvement.Id_PrestationDepart,'_',rh_personne_mouvement.Id_PoleDepart) IN 
					(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
					FROM new_competences_personne_poste_prestation 
					WHERE Id_Personne=".$_SESSION["Id_Personne"]."
					AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
					) ";
		$requete.=" ORDER BY Id DESC";
		
		$result=mysqli_query($bdd,$requete);
		$nbResulta=mysqli_num_rows($result);
		$couleur="#FFFFFF";

	?>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="5%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "N° demande";}else{echo "Request number";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Provenance";}else{echo "Origin";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Destination";}else{echo "Destination";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Date création";}else{echo "Creation Date";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Donneur d'ordre";}else{echo "Customer";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Date de début";}else{echo "Start date";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin";}else{echo "End date";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Validation receveur";}else{echo "Validation receiver";} ?></td>
					<td class='EnTeteTableauCompetences' width="15%" style="text-align:center;">
						<input type="submit" class="Bouton" style="cursor:pointer;" title="<?php if($_SESSION['Langue']=="FR"){echo "Pris en compte";}else{echo "Taken into account";}?>" name="priseEnCompte" value="<?php if($_SESSION["Langue"]=="FR"){echo "Pris en compte";}else{echo "Taken into account";} ?>"><br>
						<input type='checkbox' id="check_PriseEnCompte" name="check_PriseEnCompte" value="" checked onchange="CocherPriseEnCompte()">
					</td>
					<td class="EnTeteTableauCompetences" width="8%" style="text-decoration:none;color:#000000;font-weight:bold;text-align:center;"><?php if($_SESSION["Langue"]=="FR"){echo "Suppr.";}else{echo "Suppr.";} ?></td>
				</tr>
	<?php
			if(isset($_POST['priseEnCompte'])){
				while($row=mysqli_fetch_array($result)){
					if (isset($_POST['checkOUT_'.$row['Id'].''])){
						$requeteUpdate="UPDATE rh_personne_mouvement SET 
								Id_DemandeurPrisEnCompte=".$_SESSION['Id_Personne'].",
								DatePriseEnCompteDemandeur='".date('Y-m-d')."'
								WHERE Id=".$row['Id']." ";
						$resultat=mysqli_query($bdd,$requeteUpdate);
					}
				}
			}
			
			$result=mysqli_query($bdd,$requete);
			
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
					
					$poleDepart="";
					if($row['Id_PoleDepart']>0){$poleDepart=" - ".$row['PoleDepart'];}
					$pole="";
					if($row['Id_Pole']>0){$pole=" - ".$row['Pole'];}
					
					$Etat="";
					$couleurEtat="#ffed3b";
					$Hover="";
					if($_SESSION["Langue"]=="FR"){$Etat="En attente de validation";}
					else{$Etat="Waiting for validation";}
					if($row['EtatValidation']==1){
						if($_SESSION["Langue"]=="FR"){$Etat="Validé";}
						else{$Etat="Validated";}
						$couleurEtat="#469400";
					}
					elseif($row['EtatValidation']==-1){
						if($_SESSION["Langue"]=="FR"){$Etat="Refusé";}
						else{$Etat="Refused";}
						$couleurEtat="#e92525";
						
						$Hover=" id='leHover' ";
						$Etat.="<span>".stripslashes($row['RaisonRefus'])."<br>";
						$Etat.=stripslashes($row['CommentaireRefus'])."</span>";
					}
		?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td align="center"><?php echo stripslashes($row['Id']);?></td>
						<td><?php echo stripslashes($row['Personne']);?></td>
						<td><?php echo substr(stripslashes($row['PrestaDepart']),0,7).$poleDepart;?></td>
						<td><?php echo substr(stripslashes($row['Presta']),0,7).$pole;?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['DateCreation']);?></td>
						<td><?php echo stripslashes($row['Createur']);?></td>
						<td><?php echo stripslashes(AfficheDateJJ_MM_AAAA($row['DateDebut']));?></td>
						<td><?php if($row['DateFin']<="0001-01-01"){echo "-";}else{echo AfficheDateJJ_MM_AAAA($row['DateFin']);}?></td>
						<td bgcolor="<?php echo $couleurEtat;?>" <?php echo $Hover; ?>><?php echo $Etat;?></td>
						<td align="center">
							<?php 
								if($row['EtatValidation']<>0){
									echo "<input class='checkPriseEnCompte' type='checkbox' name='checkOUT_".$row['Id']."' value='' checked>";
								}
							?>
						</td>
						<td align="center">
							<?php
							if($row['EtatValidation']==0){
							?>
							<a class="LigneTableauRecherchePersonne" style="cursor:pointer;" onclick="if(window.confirm('<?php if($_SESSION["Langue"]=="FR"){echo "Etes-vous sûre de vouloir supprimer ?";}else{echo "Are you sure you want to delete ?";} ?>')){OuvreFenetreModif('<?php echo $Menu; ?>','<?php echo $row['Id']; ?>');}else{return false;}"><img src="../../Images/Suppression.gif" border="0" title="Supprimer"></a>
							<?php
							}
							?>
						</td>
					</tr>
				<?php
				}	//Fin boucle
			}
			?>
			</table>
		</td>
	</tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
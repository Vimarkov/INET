<?php
if($_POST){
	$Id_Plateforme="";
	if(isset($_POST['Id_Plateforme'])){
		if (is_array($_POST['Id_Plateforme'])) {
			foreach($_POST['Id_Plateforme'] as $value){
				if($Id_Plateforme<>''){$Id_Plateforme.=",";}
			  $Id_Plateforme.=$value;
			}
		} else {
			$value = $_POST['Id_Plateforme'];
			$Id_Plateforme = $value;
		}
	}
	
	$_SESSION['FiltreEPEIndicateurs_Plateforme']=$Id_Plateforme;
} 
?>
<table style="width:100%; border-spacing:0; align:center;">
<tr><td height="4"></td>
<tr>
	<td align="center" valign="top" width="75%">
		<table class="GeneralInfo">
			<?php 
				$req="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,
					Cadre,DateAncienneteCDI,Contrat,
					(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_personne_plateforme
					WHERE new_rh_etatcivil.Id=Id_Personne AND Id_Plateforme NOT IN (11,14) LIMIT 1) AS Plateforme
				FROM new_rh_etatcivil
				WHERE new_rh_etatcivil.Id NOT IN (1726,1739)
				AND MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') 
				AND MetierPaie<>'' AND Cadre IN (0,1) 
				AND new_rh_etatcivil.Id<>1739
				AND (SELECT COUNT(Id_Plateforme) FROM new_competences_personne_plateforme
					WHERE new_rh_etatcivil.Id=Id_Personne AND Id_Plateforme NOT IN (11,14)
					AND new_competences_personne_plateforme.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
					)>0
				AND 
				(
					SELECT COUNT(new_competences_personne_prestation.Id)
					FROM new_competences_personne_prestation
					LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
					WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
					AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
					AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
					AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
				)=0 ";
				if($_SESSION['FiltreEPEIndicateurs_Plateforme']<>""){
					$req.="AND (SELECT Id_Plateforme FROM new_competences_personne_plateforme
					WHERE new_rh_etatcivil.Id=Id_Personne AND Id_Plateforme NOT IN (11,14) LIMIT 1) IN (".$_SESSION['FiltreEPEIndicateurs_Plateforme'].") ";	
				}
				if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
					
				}
				else{
					$req.="
						AND
						(SELECT COUNT(Id_Plateforme) 
						FROM new_competences_personne_plateforme
						WHERE new_rh_etatcivil.Id=Id_Personne 
						AND Id_Plateforme NOT IN (11,14)
						AND Id_Plateforme IN 
							(
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
							)
						)>0
						";
				}
				$req.="ORDER BY Personne ";
				$result=mysqli_query($bdd,$req);
				$nbenreg=mysqli_num_rows($result);
					
			?>
				<tr >
					<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule";}else{echo "Registration number";} ?></td>
					<td class="EnTeteTableauCompetences" width="12%"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "People";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Date d'embauche";}else{echo "Hiring date";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Cadre";}else{echo "Organization";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";} ?></td>
					<td class="EnTeteTableauCompetences" width="3%">
					&nbsp;<a style="text-decoration:none;" href="javascript:Excel_PersonnesSansPresta();">
						<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
					</a>&nbsp;
					</td>
				</tr>
			<?php
				if($nbenreg>0){
					$total=0;
					$couleur="#d6d9dc";
					
					while($row=mysqli_fetch_array($result)){
						if($row['Cadre']==1){$cadre="Cadre";}
						else{$cadre="Non cadre";}
			?>
				<tr bgcolor="<?php echo $couleur; ?>">
					<td><?php echo stripslashes($row['MatriculeAAA']);?></td>
					<td><?php echo stripslashes($row['Personne']);?></td>
					<td><?php echo AfficheDateJJ_MM_AAAA($row['DateAncienneteCDI']);?></td>
					<td><?php echo stripslashes($row['Contrat']);?></td>
					<td><?php echo stripslashes($cadre);?></td>
					<td colspan="2"><?php echo stripslashes($row['Plateforme']);?></td>
				</tr>
			<?php 
						if($couleur=="#d6d9dc"){$couleur="#ffffff";}
						else{$couleur="#d6d9dc";}
					} 
				}
				
			?>
		</table>
	</td>
	<td align="right" valign="top" width="25%">
		<table class="GeneralInfo" style="border-spacing:0; width:70%; align:center;box-shadow: 0 8px 10px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
			<tr><td height="4px"></td></tr>
			<tr>
				<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
			</tr>
			<tr>
				<td>
					<input type="checkbox" name="selectAll" id="selectAll" onclick="SelectionnerTout()" checked /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php 
					$requetePlateforme="SELECT Id, Libelle
					FROM new_competences_plateforme
					WHERE Id IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) ";
					if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
						
					}
					else{
						$requetePlateforme.="
						AND Id IN 
							(
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteAssistantRH.",".$IdPosteResponsableRH.")
							)
							";
					}
					$requetePlateforme.="ORDER BY Libelle";
					$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
					
					while($LigPlateforme=mysqli_fetch_array($resultPlateforme)){
						$checked="";
						if($_POST){
							$checkboxes = isset($_POST['Id_Plateforme']) ? $_POST['Id_Plateforme'] : array();
							foreach($checkboxes as $value) {
								if($LigPlateforme['Id']==$value){$checked="checked";}
							}
						}
						else{
							$checked="checked";	
						}
						echo "<tr><td>";
						echo "<input type='checkbox' class='checkPlateforme' name='Id_Plateforme[]' Id='Id_Plateforme[]' value='".$LigPlateforme['Id']."' ".$checked." >".$LigPlateforme['Libelle'];
						echo "</td></tr>";
					}
					
					?>
				</td>
			</tr>
			<tr>
				<td align="center">
					<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
					<div id="filtrer"></div>
				</td>
			</tr>
			<tr><td height="4px"></td></tr>
		</table>
	</td>
</tr>
<tr><td height="4"></td>
</table>	
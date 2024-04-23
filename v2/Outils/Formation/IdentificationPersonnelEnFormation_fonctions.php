<?php
/**
 *	IdentificationPersonnelEnFormation_fonctions.php
 *
 *	Ce fichier regroupe les fonction php utilisees pour l idetifiaction du personnel en formation
 *
 *	@package	IdentificationPersonnelEnFormation\Bibliotheque
 *	@author	Anthony Schricke <aschricke@aaa-aero.com>
 */

require_once('Globales_Fonctions.php');
	
/**
 * AfficherFiltres
 * 
 * Affiche les filtres pour la liste des identifications du personnel en formation
 * 
 * @return string Le codehtml
 * @author Anthony Schricke <aschricke@aaa-aero.com>
 */
function afficherFiltres()
{
	global $bdd;
	global $LangueAffichage;
	global $IdPosteAssistantFormationExterne;
	global $IdPosteResponsableProjet;
	
	//Entete du tableau
	$codehtml = "	<form action=\"IdentificationPersonnelEnFormation_Liste.php\" method=\"POST\">\n";
	$codehtml .= "		<table style=\"width:100%; align:center; border-spacing:0;\" class=\"GeneralInfo\">\n";
	
	$codehtml .= "			<tr><td height=\"4\"></td>\n";
	$codehtml .= "				<td>\n";
	//les filtres possibles
	$codehtml .= "					<table width=\"100%\">\n";
	$codehtml .= "							<tr>\n";
	if($LangueAffichage=="FR"){
		$codehtml .= "									<td class=\"Libelle\">Prestation/Pôle</td>\n";
	}
	else{
		$codehtml .= "									<td class=\"Libelle\">Activity/Pole</td>\n";
	}
	$Prestation=$_SESSION['FiltrePersFormation_Prestation'];
	$codehtml .= "									<td><input style=\"width:200px\" id=\"Prestation\" name=\"Prestation\" value=\"".$Prestation."\"></td>\n";
	if($LangueAffichage=="FR"){
		$codehtml .= "									<td class=\"Libelle\">Date de début</td>\n";
	}
	else{
		$codehtml .= "									<td class=\"Libelle\">Start date</td>\n";
	}
	$dateD="";
	$dateD=$_SESSION['FiltrePersFormation_DateDebut'];
	$codehtml .= "									<td><input type=\"date\" id=\"DateDebut\" name=\"DateDebut\" style=\"width:110px;\" value=\"".$dateD."\"></td>\n";
	if($LangueAffichage=="FR"){
		$codehtml .= "									<td class=\"Libelle\">Formation / Groupe de formation</td>\n";
	}
	else{
		$codehtml .= "									<td class=\"Libelle\">Training / Training Group</td>\n";
	}
	$formation="";
	$formation=$_SESSION['FiltrePersFormation_Formation'];
	$codehtml .= "									<td><input style=\"width:200px\" id=\"Formation\" name=\"Formation\" value=\"".$formation."\"></td>\n";
	if($LangueAffichage=="FR"){
		$codehtml .= "									<td><input style='cursor:pointer;' class=\"Bouton\" type=\"submit\" value=\"Filtrer\" ></td>\n";
	}
	else{
		$codehtml .= "									<td><input style='cursor:pointer;' class=\"Bouton\" type=\"submit\" value=\"Filter\"></td>\n";
	}
	$codehtml .= "							</tr>\n";
	$codehtml .= "							<tr>\n";	
	if($LangueAffichage=="FR"){
		$codehtml .= "									<td class=\"Libelle\">Personne</td>\n";
	}
	else{
		$codehtml .= "									<td class=\"Libelle\">Person</td>\n";
	}
	$stagiaire="";
	$stagiaire=$_SESSION['FiltrePersFormation_Personne'];
	$codehtml .= "									<td><input id=\"Stagiaire\" name=\"Stagiaire\" value=\"".$stagiaire."\"></td>\n";
	if($LangueAffichage=="FR"){
		$codehtml .= "									<td class=\"Libelle\">Date de fin</td>\n";
	}
	else{
		$codehtml .= "									<td class=\"Libelle\">End date</td>\n";
	}
	$dateF="";
	$dateF=$_SESSION['FiltrePersFormation_DateFin'];
	$codehtml .= "									<td><input type=\"date\" id=\"DateFin\" name=\"DateFin\" style=\"width:110px;\" value=\"".$dateF."\"></td>\n";
	
	$codehtml .= "									<td><img src=\"..\\..\\Images\\excel.gif\" style=\"cursor : pointer;\" onclick=\"Excel()\"></td>\n";
	$codehtml .= "							</tr>\n";
	$codehtml .= "							<tr>\n";
	if($LangueAffichage=="FR"){
		$codehtml .= "									<td class=\"Libelle\">Etat</td>\n";
	}
	else{
		$codehtml .= "									<td class=\"Libelle\">State</td>\n";
	}
	$codehtml .= "									<td><select id=\"Etat\" name=\"Etat\" onchange='submit()'>
														<option value='-2' selected></option>";
	$selected0="";
	$selected1="";
	$selected_1="";
	$etat=$_SESSION['FiltrePersFormation_Etat'];
	if($etat==0){$selected0="selected";}
	elseif($etat==1){$selected1="selected";}
	elseif($etat==-1){$selected_1="selected";}
	if($LangueAffichage=="FR"){
		$codehtml .= "<option value='0' ".$selected0.">En attente validation</option>";
		$codehtml .= "<option value='1' ".$selected1.">Validée</option>";
		$codehtml .= "<option value='-1' ".$selected_1.">Refusée</option>";
	}	
	else{
		$codehtml .= "<option value='0'>Waiting for validation</option>";
		$codehtml .= "<option value='1'>Validated</option>";
		$codehtml .= "<option value='-1'>Declined</option>";
	}	
	$codehtml .= "									</select></td>\n";
	$codehtml .= '<td class="Libelle" width="8%">&nbsp;Type</td>';
			$codehtml .= '<td width="10%">';
				$codehtml .= '<select name="TypeFormation" id="TypeFormation" onchange="submit()">';
					$codehtml .= '<option value="0"></option>';
					$TypeForm=$_SESSION['FiltrePersFormation_TypeFormation'];
					$resultTypeFormation=mysqli_query($bdd,"SELECT Id, Libelle FROM form_typeformation WHERE Suppr=0 ORDER BY Libelle ASC");
					while($rowTypeFormation=mysqli_fetch_array($resultTypeFormation)){
						$selected="";
						if($TypeForm<>"")
						{
							if($TypeForm==$rowTypeFormation['Id']){$selected="selected";}
						}
						$codehtml .= '<option value="'.$rowTypeFormation['Id'].'" '.$selected.'>'.stripslashes($rowTypeFormation['Libelle']).'</option>\n';
					}
				$codehtml .= '</select>';
			$codehtml .= '</td>';
	$codehtml .= "							</tr>\n";
	$codehtml .= "					</table>\n";
	
	$codehtml .= "				</td>\n";
				
	$codehtml .= "			</tr>\n";
	$codehtml .= "<tr>\n";
	$codehtml .= "     <td height='5'></td>\n";
	$codehtml .= "</tr>\n";
	$codehtml .= "<tr>\n";
	$codehtml .= "<td valign='top' colspan='8' class='Libelle'";
	if(DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne))==0){$codehtml .= "style='display:none;'";}
	$codehtml .= ">";
						if($LangueAffichage=="FR"){$codehtml .= "Responsable Projet";}else{$codehtml .= "Project manager";} 
						$codehtml .= ":<br>";

									$Id_RespProjet=$_SESSION['FiltrePersFormation_RespProjet'];
									if($_POST){
										$Id_RespProjet="";
										if(isset($_POST['Id_RespProjet'])){
											if (is_array($_POST['Id_RespProjet'])) {
												foreach($_POST['Id_RespProjet'] as $value){
													if($Id_RespProjet<>''){$Id_RespProjet.=",";}
												  $Id_RespProjet.=$value;
												}
											} else {
												$value = $_POST['Id_RespProjet'];
												$Id_RespProjet = $value;
											}
										}
									}
									$_SESSION['FiltrePersFormation_RespProjet']=$Id_RespProjet;
			
									$rqRespProjet="SELECT DISTINCT Id_Personne,
									(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
									FROM new_competences_personne_poste_prestation 
									LEFT JOIN new_competences_prestation
									ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
									WHERE Id_Poste IN (".$IdPosteResponsableProjet.")
									AND Id_Plateforme IN (
										SELECT Id_Plateforme 
										FROM new_competences_personne_poste_plateforme
										WHERE Id_Personne=".$_SESSION['Id_Personne']." 
										AND Id_Poste IN (".$IdPosteAssistantFormationExterne.")
									)
									AND Id_Personne<>0
									ORDER BY Personne";
									
									$resultRespProjet=mysqli_query($bdd,$rqRespProjet);
									$Id_RespProjet=0;
									while($rowRespProjet=mysqli_fetch_array($resultRespProjet))
									{
										$checked="";
										if($_POST){
											$checkboxes = isset($_POST['Id_RespProjet']) ? $_POST['Id_RespProjet'] : array();
											foreach($checkboxes as $value) {
												if($rowRespProjet['Id_Personne']==$value){$checked="checked";}
											}
										}
										else{
											$checkboxes = explode(',',$_SESSION['FiltrePersFormation_RespProjet']);
											foreach($checkboxes as $value) {
												if($rowRespProjet['Id_Personne']==$value){$checked="checked";}
											}
										}
										$codehtml .= "<input type='checkbox' class='checkRespProjet' name='Id_RespProjet[]' Id='Id_RespProjet[]' value='".$rowRespProjet['Id_Personne']."' ".$checked.">".$rowRespProjet['Personne'];
									}
	$codehtml .= "</td>";
	$codehtml .= "</tr>";
				
	$codehtml .= "		</table>\n";
	$codehtml .= "</form>\n";
	return $codehtml;
}
?>
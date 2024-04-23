<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<!-- Latin 1 = ISO-8859-1-->
	<meta charset=ISO-8859-1 />
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Perfos.css">
	<link rel="stylesheet" href="../../CSS/Action.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../../CSS/New_Menu2.css?t=<? echo time(); ?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="FrequencePERFOS.js"></script>
</head>
<?php
require("../Connexioni.php");
require("../Fonctions.php");

if(isset($_POST['submitValider'])){
	$email=false;
	$Pole = 0;
	if($_POST['IdFrequence'] == 0){
		//Ajout de la fréquence
		if (!empty($_POST['pole'])){$Pole =$_POST['pole'];}
		
		$requeteInsert="INSERT INTO new_frequencesqcdpf (Id_Prestation, Id_Pole, Frequence, HeureRealisation1, JourRealisation1, HeureRealisation2, JourRealisation2, ";
		$requeteInsert .= "CritereSRouge, CritereQRouge, CritereCRouge, CritereDRouge, CriterePRouge, CritereFRouge, CritereSVert, CritereQVert, CritereCVert, ";
		$requeteInsert .= "CritereDVert, CriterePVert, CritereFVert) ";
		$requeteInsert.=" VALUES ";
		$requeteInsert.=" (".$_POST['prestation'].",".$Pole.",".$_POST['frequence'].",".$_POST['heure1'].",";
		
		if($_POST['frequence'] == 2){
			$requeteInsert.="".$_POST['jour1'].",0,0,";
		}
		elseif($_POST['frequence'] == 3){
			$requeteInsert.="".$_POST['jour1'].",".$_POST['heure2'].",".$_POST['jour2'].",";
		}
		else{
			$requeteInsert.= "0,0,0,";
		}
		$requeteInsert .= "'".addslashes($_POST['rougeS'])."','".addslashes($_POST['rougeQ'])."','".addslashes($_POST['rougeC'])."','".addslashes($_POST['rougeD'])."',";
		$requeteInsert .= "'".addslashes($_POST['rougeP'])."','".addslashes($_POST['rougeF'])."','".addslashes($_POST['vertS'])."','".addslashes($_POST['vertQ'])."',";
		$requeteInsert .= "'".addslashes($_POST['vertC'])."','".addslashes($_POST['vertD'])."','".addslashes($_POST['vertP'])."','".addslashes($_POST['vertF'])."') ";
		$resultAjout=mysqli_query($bdd,$requeteInsert);
		
		$email=true;
	}
	else{
		//Modif fréquence
		
		$reqFrequence = "SELECT CritereSRouge,CritereQRouge,CritereCRouge,CritereDRouge,CriterePRouge,CritereFRouge,CritereSVert,CritereQVert,CritereCVert, ";
		$reqFrequence .= "CritereDVert,CriterePVert,CritereFVert ";
		$reqFrequence .= "FROM new_frequencesqcdpf ";
		$reqFrequence .= "WHERE Id=".$_POST['IdFrequence'];
		$resultFrequence=mysqli_query($bdd,$reqFrequence);
		$nbFrequence=mysqli_num_rows($resultFrequence);
		if ($nbFrequence > 0){
			$rowFrequence=mysqli_fetch_array($resultFrequence);
			if($rowFrequence['CritereSRouge']<>$_POST['rougeS'] || $rowFrequence['CritereQRouge']<>$_POST['rougeQ'] || $rowFrequence['CritereCRouge']<>$_POST['rougeC'] || $rowFrequence['CritereDRouge']<>$_POST['rougeD'] || $rowFrequence['CriterePRouge']<>$_POST['rougeP'] || $rowFrequence['CritereFRouge']<>$_POST['rougeF']){
				$email=true;
			}
			if($rowFrequence['CritereSVert']<>$_POST['vertS'] || $rowFrequence['CritereQVert']<>$_POST['vertQ'] || $rowFrequence['CritereCVert']<>$_POST['vertC'] || $rowFrequence['CritereDVert']<>$_POST['vertD'] || $rowFrequence['CriterePVert']<>$_POST['vertP'] || $rowFrequence['CritereFVert']<>$_POST['vertF']){
				$email=true;
			}
		}
		
		$requeteUpdate="UPDATE new_frequencesqcdpf SET ";
		$requeteUpdate.= "Frequence=".$_POST['frequence'].", ";
		$requeteUpdate.= "HeureRealisation1=".$_POST['heure1'].", ";
		if($_POST['frequence'] == 2){
			$requeteUpdate.= "JourRealisation1=".$_POST['jour1'].", ";
			$requeteUpdate.= "HeureRealisation2=0, ";
			$requeteUpdate.= "JourRealisation2='0', ";
		}
		elseif($_POST['frequence'] == 3){
			$requeteUpdate.= "JourRealisation1=".$_POST['jour1'].", ";
			$requeteUpdate.= "HeureRealisation2=".$_POST['heure2'].", ";
			$requeteUpdate.= "JourRealisation2=".$_POST['jour2'].", ";
		}
		else{
			$requeteUpdate.= "JourRealisation1=0, ";
			$requeteUpdate.= "HeureRealisation2=0, ";
			$requeteUpdate.= "JourRealisation2='0', ";
		}
		$requeteUpdate .= "CritereSRouge='".addslashes($_POST['rougeS'])."',";
		$requeteUpdate .= "CritereQRouge='".addslashes($_POST['rougeQ'])."',";
		$requeteUpdate .= "CritereCRouge='".addslashes($_POST['rougeC'])."',";
		$requeteUpdate .= "CritereDRouge='".addslashes($_POST['rougeD'])."',";
		$requeteUpdate .= "CriterePRouge='".addslashes($_POST['rougeP'])."',";
		$requeteUpdate .= "CritereFRouge='".addslashes($_POST['rougeF'])."',";
		$requeteUpdate .= "CritereSVert='".addslashes($_POST['vertS'])."',";
		$requeteUpdate .= "CritereQVert='".addslashes($_POST['vertQ'])."',";
		$requeteUpdate .= "CritereCVert='".addslashes($_POST['vertC'])."',";
		$requeteUpdate .= "CritereDVert='".addslashes($_POST['vertD'])."',";
		$requeteUpdate .= "CriterePVert='".addslashes($_POST['vertP'])."',";
		$requeteUpdate .= "CritereFVert='".addslashes($_POST['vertF'])."' ";
		
		$requeteUpdate.= " WHERE Id =".$_POST['IdFrequence'].";";
		$resultModif=mysqli_query($bdd,$requeteUpdate);
	}
	
	if($email==true){
		$rqListe="SELECT new_rh_etatcivil.EmailPro FROM new_competences_personne_poste_prestation LEFT JOIN new_rh_etatcivil ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id ";
		$rqListe.=" WHERE new_competences_personne_poste_prestation.Id_Poste=3 AND new_competences_personne_poste_prestation.Id_Prestation=".$_POST['prestation']."";
		if (!empty($_POST['pole'])){$rqListe.=" AND new_competences_personne_poste_prestation.Id_Pole=".$Pole."";}
		$resultpersonneListe=mysqli_query($bdd,$rqListe);
		
		$destinataire = "sylvie.blanc.external@airbus.com,";
		while($rowListe = mysqli_fetch_array($resultpersonneListe)){
			if($rowListe['EmailPro']<>""){
				$destinataire .= $rowListe['EmailPro'].",";
			}
		}
		$destinataire = substr($destinataire,0,-1);
			
		
		$headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
		$headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
		
		$reqPresta = "SELECT Libelle FROM new_competences_prestation WHERE Id =".$_POST['prestation']."";
		$resultPresta=mysqli_query($bdd,$reqPresta);
		$rowPresta = mysqli_fetch_array($resultPresta);
		
		$NomPole = "";
		if ($Pole > 0){
			$reqPole = "SELECT Libelle FROM new_competences_pole WHERE Id =".$Pole."";
			$resultPole=mysqli_query($bdd,$reqPole);
			$rowPole = mysqli_fetch_array($resultPole);
			$NomPole = $rowPole['Libelle'];
		}
		$object = "Modification des critères SQCDPF - ".$rowPresta['Libelle']." ".$NomPole."";
		
		$message='<html><head><title>Critère SQCDPF</title></head><body>';
		$message.="<table width=\"100%\">";
		$message.="<tr><td>Bonjour,</td></tr>";
		$message.="<tr><td>Les critères Rouge/vert des SQCDPF ".$rowPresta['Libelle']." ".$NomPole." ont été modifiés </td></tr>";
		$message.="</table>";
		$message.="<table border=\"2\" width=\"90%\" cellpadding=\"0\" cellspacing=\"0\" align=\"left\" style=\"font-size:12px;\">";
		$message.="<tr>";
		$message.="<td align=\"center\">S</td>";
		$message.="<td align=\"center\">Q</td>";
		$message.="<td align=\"center\">C</td>";
		$message.="<td align=\"center\">D</td>";
		$message.="<td align=\"center\">P</td>";
		$message.="<td align=\"center\">F</td>";
		$message.="</tr>";
		$message.="<tr>";
		$message.="<td bgcolor='#e2fdbd'>".$_POST['vertS']."</td>";
		$message.="<td bgcolor='#e2fdbd'>".$_POST['vertQ']."</td>";
		$message.="<td bgcolor='#e2fdbd'>".$_POST['vertC']."</td>";
		$message.="<td bgcolor='#e2fdbd'>".$_POST['vertD']."</td>";
		$message.="<td bgcolor='#e2fdbd'>".$_POST['vertP']."</td>";
		$message.="<td bgcolor='#e2fdbd'>".$_POST['vertF']."</td>";
		$message.="</tr>";
		$message.="<tr>";
		$message.="<td bgcolor='#ffafae'>".$_POST['rougeS']."</td>";
		$message.="<td bgcolor='#ffafae'>".$_POST['rougeQ']."</td>";
		$message.="<td bgcolor='#ffafae'>".$_POST['rougeC']."</td>";
		$message.="<td bgcolor='#ffafae'>".$_POST['rougeD']."</td>";
		$message.="<td bgcolor='#ffafae'>".$_POST['rougeP']."</td>";
		$message.="<td bgcolor='#ffafae'>".$_POST['rougeF']."</td>";
		$message.="</tr>";
		$message.="</table>";
		$message.='</body></html>';
		
		mail($destinataire, $object , $message , $headers,'-f extranet@aaa-aero.com');
	}
	echo "<script>Fermer();</script>";
}

if ($_GET){
	$IdPersonne = $_GET['Id_Personne'];
	
}
?>
<form class="test" method="POST" action="FrequencePERFOS.php" onSubmit="return VerifChamps();">
	<table width="100%" cellpadding="0" cellspacing="0" align="center">
		<tr>
			<td>
				<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#6EB4CD;">
					<tr>
						<td width="4"></td>
						<td class="TitrePage">SQCDPF # Configurer SQCDPF</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr style="display:none;">
			<td><input type="text" name="Personne" size="11" value="<?php echo $IdPersonne; ?>"></td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr><td>
			<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr>
					<td height="4"></td>
				</tr>
				<tr>
					<td colspan="4" style="font-weight:bold;" align="center">&nbsp; FREQUENCE</td>
				</tr>
				<tr>
					<td height="4"></td>
				</tr>
				<tr>
					<td width=8%>
						&nbsp; Prestation : 
					</td>
					<td width=25%>
						<select id="prestation" name="prestation" onchange="Recharge_Liste_Pole();">
						<?php
						$req = "SELECT distinct(new_competences_personne_poste_prestation.Id_Prestation), (SELECT new_competences_prestation.Libelle FROM new_competences_prestation ";
						$req .= "WHERE new_competences_prestation.Id = new_competences_personne_poste_prestation.Id_Prestation) AS NomPrestation FROM new_competences_personne_poste_prestation WHERE ";
						$req .= "new_competences_personne_poste_prestation.Id_Personne=".$IdPersonne." AND new_competences_personne_poste_prestation.Id_Poste=2 ORDER BY NomPrestation;";
						$i=0;
						$resultPrestation=mysqli_query($bdd,$req);
						$nbPrestation=mysqli_num_rows($resultPrestation);
						
						if ($nbPrestation > 0){
							echo "<option name='0' value='0' selected></option>\n";
							while($rowPresta=mysqli_fetch_array($resultPrestation)){
								echo "<option name='".$rowPresta[0]."' value='".$rowPresta[0]."'>".$rowPresta[1]."</option>\n";
							}
						 }
						 ?>
						</select>
					</td>
					<td width=15%>
						&nbsp; Pôle : 
					</td>
					<td width=15%>
						<div id="pole">
							<select size="1" id="poles" name="pole" onchange="Rechercher_Frequence();">
							<option value="0" selected></option>
							</select>
							<?php
							$reqPole = "SELECT distinct new_competences_personne_poste_prestation.Id_Pole, ";
							$reqPole .= "(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id = new_competences_personne_poste_prestation.Id_Pole) AS LibellePole, ";
							$reqPole .= "new_competences_personne_poste_prestation.Id_Prestation AS Id_Prestation ";
							$reqPole .= "FROM new_competences_personne_poste_prestation WHERE ";
							$reqPole .= "new_competences_personne_poste_prestation.Id_Personne=".$IdPersonne." AND new_competences_personne_poste_prestation.Id_Poste=2 ORDER BY LibellePole;";
							
							$resultPole=mysqli_query($bdd,$reqPole);
							$nbPole=mysqli_num_rows($resultPole);
							
							$reqFrequence = "SELECT distinct Id, Id_Prestation, Id_Pole, Frequence, JourRealisation1, JourRealisation2, HeureRealisation1, HeureRealisation2, ";
							$reqFrequence .= "CritereSRouge, CritereQRouge, CritereCRouge, CritereDRouge, CriterePRouge, CritereFRouge, CritereSVert, CritereQVert, CritereCVert, ";
							$reqFrequence .= "CritereDVert, CriterePVert, CritereFVert ";
							$reqFrequence .= "FROM new_frequencesqcdpf ";
							$reqFrequence .= "WHERE ";
							
							$i=0;
							
							if ($nbPole > 0){
								echo "\n";
								while($rowPole=mysqli_fetch_array($resultPole)){
									echo "\t\t\t\t\t\t\t<script>Liste_Pole_Prestation[".$i."] = new Array(".$rowPole['Id_Pole'].",".$rowPole['Id_Prestation'].",'".addslashes($rowPole['LibellePole'])."');</script>\n";
									$i+=1;
									$reqFrequence .= " (Id_Prestation=".$rowPole['Id_Prestation']." AND Id_Pole=".$rowPole['Id_Pole'].") OR ";
								}
								echo "\n";
							 }
							 
							$reqFrequence = substr($reqFrequence,0,-3);
							$resultFrequence=mysqli_query($bdd,$reqFrequence);
							$nbFrequence=mysqli_num_rows($resultFrequence);
							
							$i=0;
							if ($nbFrequence > 0){
								while($rowFrequence=mysqli_fetch_array($resultFrequence)){
									$liste = "<script>Liste_Frequence[".$i."] = new Array(".$rowFrequence['Id'].",".$rowFrequence['Id_Prestation'].",".$rowFrequence['Id_Pole'];
									$liste .= ",".$rowFrequence['Frequence'].",".$rowFrequence['JourRealisation1'].",".$rowFrequence['JourRealisation2'];
									$liste .= ",".$rowFrequence['HeureRealisation1'].",".$rowFrequence['HeureRealisation2'].",'".$rowFrequence['CritereSRouge']."','";
									$liste .= $rowFrequence['CritereQRouge']."','".$rowFrequence['CritereCRouge']."','".$rowFrequence['CritereDRouge']."','";
									$liste .= $rowFrequence['CriterePRouge']."','".$rowFrequence['CritereFRouge']."','".$rowFrequence['CritereSVert']."'";
									$liste .= ",'".$rowFrequence['CritereQVert']."','".$rowFrequence['CritereCVert']."','".$rowFrequence['CritereDVert']."'";
									$liste .= ",'".$rowFrequence['CriterePVert']."','".$rowFrequence['CritereFVert']."');</script>\n";
									$i+=1;
									echo $liste;
								}
								
							 }
							 ?>
						</div>
					</td>
				</tr>
				<tr>
					<td height="4"></td>
				</tr>
				<tr style="display:none;">
					<td><input type="text" id="IdFrequence" name="IdFrequence" size="11" value=""></td>
				</tr>
				<tr>
					<td width=8%>
						&nbsp; Fréquence : 
					</td>
					<td width=15%>
						<select size="1" id="frequence" name="frequence" onchange="AfficherChamps();">
							<option value="0" selected></option>
							<option value="1">Journalière</option>
							<option value="2">Hebdomadaire</option>
							<option value="3">Bihebdomadaire</option>
						</select>
					</td>
				</tr>
				<tr>
					<td height="4"></td>
				</tr>
				<tr>
					<td width=15%>
						&nbsp; Heure de réalisation  : 
					</td>
					<td width=15%>
						<select size="1" id="heure1s" name="heure1">
							<option value="0" selected></option>
							<?php
								for($i=0;$i<24;$i++){
									echo "<option name='".$i."' value='".$i."'>".$i.":00</option>\n";
									echo "<option name='".$i.".3' value='".$i.".3'>".$i.":30</option>\n";
								}
							?>
						</select>
					</td>
					<td width=15% id="1er1" style="display:none;">
						&nbsp; Jour de réalisation  : 
					</td>
					<td width=15% id="1er2" style="display:none;">
						<select size="1" id="jour1s" name="jour1">
							<option value="0" selected></option>
							<option value="1">Lundi</option>
							<option value="2">Mardi</option>
							<option value="3">Mercredi</option>
							<option value="4">Jeudi</option>
							<option value="5">Vendredi</option>
							<option value="6">Samedi</option>
							<option value="7">Dimanche</option>
						</select>
					</td>
				</tr>
				<tr>
					<td height="4"></td>
				</tr>
				<tr id="2eme" style="display:none;">
					
					<td width=15%>
						&nbsp; 2ème heure de réalisation  : 
					</td>
					<td width=15%>
						<div id="heure2">
							<select size="1" id="heure2s" name="heure2">
								<option value="0" selected></option>
								<?php
									for($i=0;$i<24;$i++){
										echo "<option name='".$i."' value='".$i."'>".$i.":00</option>\n";
										echo "<option name='".$i.".3' value='".$i.".3'>".$i.":30</option>\n";
									}
								?>
							</select>
						</div>
					</td>
					<td width=20%>
						&nbsp; 2ème jour de réalisation  : 
					</td>
					<td width=15%>
						<div id="jour2">
							<select size="1" id="jour2s" name="jour2">
								<option value="0" selected></option>
								<option value="1">Lundi</option>
								<option value="2">Mardi</option>
								<option value="3">Mercredi</option>
								<option value="4">Jeudi</option>
								<option value="5">Vendredi</option>
								<option value="6">Samedi</option>
								<option value="7">Dimanche</option>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<td height="4"></td>
				</tr>
				<tr>
					<td colspan="5" style="font-weight:bold;" align="center">&nbsp; CRITERES LETTRES</td>
				</tr>
				<tr>
					<td colspan="5">
						<table align="center" width="90%" cellpadding="0" cellspacing="0">
							<tr>
								<td style="font-weight:bold;" align="center">S</td>
								<td style="font-weight:bold;" align="center">Q</td>
								<td style="font-weight:bold;" align="center">C</td>
								<td style="font-weight:bold;" align="center">D</td>
								<td style="font-weight:bold;" align="center">P</td>
								<td style="font-weight:bold;" align="center">F</td>
							</tr>
							<tr>
								<td align="left"><textarea style="background:#e2fdbd;" id="vertS" name="vertS" rows="4" cols="0"></textarea></td>
								<td align="left"><textarea style="background:#e2fdbd;" id="vertQ" name="vertQ" rows="4" cols="0"></textarea></td>
								<td align="left"><textarea style="background:#e2fdbd;" id="vertC" name="vertC" rows="4" cols="0"></textarea></td>
								<td align="left"><textarea style="background:#e2fdbd;" id="vertD" name="vertD" rows="4" cols="0"></textarea></td>
								<td align="left"><textarea style="background:#e2fdbd;" id="vertP" name="vertP" rows="4" cols="0"></textarea></td>
								<td align="left"><textarea style="background:#e2fdbd;" id="vertF" name="vertF" rows="4" cols="0"></textarea></td>
							</tr>
							<tr>
								<td align="left"><textarea style="background:#ffafae;" id="rougeS" name="rougeS" rows="4" cols="0"></textarea></td>
								<td align="left"><textarea style="background:#ffafae;" id="rougeQ" name="rougeQ" rows="4" cols="0"></textarea></td>
								<td align="left"><textarea style="background:#ffafae;" id="rougeC" name="rougeC" rows="4" cols="0"></textarea></td>
								<td align="left"><textarea style="background:#ffafae;" id="rougeD" name="rougeD" rows="4" cols="0"></textarea></td>
								<td align="left"><textarea style="background:#ffafae;" id="rougeP" name="rougeP" rows="4" cols="0"></textarea></td>
								<td align="left"><textarea style="background:#ffafae;" id="rougeF" name="rougeF" rows="4" cols="0"></textarea></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="4"></td>
				</tr>
				<tr align="center" id="btnValider">
					<td colspan="4" align="center" align="center">
						<div>
							<input  class="Bouton" name="submitValider" type="submit" value='Valider'>
						</div>
					</td>
				</tr>
				<tr>
					<td height="4"></td>
				</tr>
			</table>
		</td></tr>
	</table>
</form>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
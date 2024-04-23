<!DOCTYPE html>
<?php
	require("../ConnexioniSansBody.php");
	
$Idnew_perfos = $_GET['Id_perfos'];
$IdPersonne = $_GET['Id_Personne'];

$reqnew_perfos = "SELECT Id_Prestation, Id_Pole, dateSQCDPF, S_J_1, Q_J_1, C_J_1,";
$reqnew_perfos.="D_J_1, P_J_1, F_J_1, CommentaireS_J_1, CommentaireQ_J_1, CommentaireC_J_1, ";
$reqnew_perfos.="CommentaireD_J_1, CommentaireP_J_1, CommentaireF_J_1, Id_Personne1 ";
$reqnew_perfos .= "FROM new_v2sqcdpf ";
$reqnew_perfos .= "WHERE new_v2sqcdpf.Id = '".$Idnew_perfos."';";
		
$resultnew_perfos=mysqli_query($bdd,$reqnew_perfos);
$nbnew_perfos=mysqli_num_rows($resultnew_perfos);
if ($nbnew_perfos>0){
	$row = mysqli_fetch_array($resultnew_perfos);
	//Envoyer le SQCDPF
	$rqListe="SELECT new_rh_etatcivil.Id, new_rh_etatcivil.EmailPro FROM new_rh_etatcivil";
	$rqListe.=" LEFT JOIN new_sqcdpf_prestation_equipemail ON new_rh_etatcivil.Id=new_sqcdpf_prestation_equipemail.Id_Personne ";
	$rqListe.=" WHERE new_sqcdpf_prestation_equipemail.Id_Prestation=".$row['Id_Prestation']."";
	$rqListe.=" AND new_sqcdpf_prestation_equipemail.Id_Pole=".$row['Id_Pole']."";
	$resultpersonneListe=mysqli_query($bdd,$rqListe);
	
	$requeteResponsablePostePrestation="SELECT DISTINCT new_rh_etatcivil.EmailPro ";
	$requeteResponsablePostePrestation.=" FROM new_competences_personne_poste_prestation LEFT JOIN new_rh_etatcivil ON new_competences_personne_poste_prestation.Id_Personne = new_rh_etatcivil.Id ";
	$requeteResponsablePostePrestation.=" WHERE new_competences_personne_poste_prestation.Id_Prestation=".$row['Id_Prestation'];
	if ($row['Id_Pole'] > 0){
		$requeteResponsablePostePrestation.=" AND new_competences_personne_poste_prestation.Id_Pole=".$row['Id_Pole'];
	}
	$requeteResponsablePostePrestation.=" AND (new_competences_personne_poste_prestation.Id_Poste=3 OR new_competences_personne_poste_prestation.Id_Poste=5) ";
	$resultResponsablePostePrestation=mysqli_query($bdd,$requeteResponsablePostePrestation);
	
	$destinataire = "";
	while($rowListe = mysqli_fetch_array($resultpersonneListe)){
		$destinataire .= $rowListe['EmailPro'].",";
	}
	while($rowResp = mysqli_fetch_array($resultResponsablePostePrestation)){
		$destinataire .= $rowResp['EmailPro'].",";
	}
	$destinataire = substr($destinataire,0,-1);
		
	
	$headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
	$headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
	
	$reqPresta = "SELECT Libelle FROM new_competences_prestation WHERE Id =".$row['Id_Prestation']."";
	$resultPresta=mysqli_query($bdd,$reqPresta);
	$rowPresta = mysqli_fetch_array($resultPresta);
	
	$NomPole = "";
	if ($row['Id_Pole'] > 0){
		$reqPole = "SELECT Libelle FROM new_competences_pole WHERE Id =".$row['Id_Pole']."";
		$resultPole=mysqli_query($bdd,$reqPole);
		$rowPole = mysqli_fetch_array($resultPole);
		$NomPole = $rowPole['Libelle'];
	}
	
	$Signature = "";
	$reqPersonne = "SELECT new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id =".$IdPersonne."";
	$resultPersonne=mysqli_query($bdd,$reqPersonne);
	$nbPersonne=mysqli_num_rows($resultPersonne);
	if ($nbPersonne>0){
		$rowPersonne = mysqli_fetch_array($resultPersonne);
		$Signature = $rowPersonne['Nom']." ".$rowPersonne['Prenom'] ;
	}

	$object = "SQCDPF - ".$rowPresta['Libelle']." ".$NomPole." - " .$row['dateSQCDPF']."";
	
	$message='<html>';
	$message.='<head>';
	$message.='<title>SQCDPF</title><meta name="robots" content="noindex">';
	$message.='</head><body>Bonjour,<br><br>';
	
	$message.="<table width='90%' cellpadding='0' cellspacing='0' align='left'>";
	$message.="<tr><td>";
	$message.="<table width='100%' cellpadding='0' cellspacing='0' align='left'> \n";
	$message.="<tr><td height='4'></td></tr> \n";
	$message.="<tr> \n";
	$message.="<td width='10%' Style='border:thin solid #000000;background:#c5d9f1;' align='center'>Lettre</td> \n";
	$message.="<td width='90%' Style='border:thin solid #000000;background:#c5d9f1;' align='center'>Commentaire</td> \n";
	$message.="</tr> \n";
	//---------------S------------------//
	$message.="<tr align='center'> \n";
	if ($row['S_J_1'] == "1"){$message.="<td Style='border:thin solid #000000;' width='10%' bgcolor='#e2fdbd'>S</td> \n";}
	else{$message.="<td Style='border:thin solid #000000;' bgcolor='#ffafae'>S</td> \n";}
	$message.="<td Style='border:thin solid #000000;' width='90%'>".$row['CommentaireS_J_1']."</td> \n";
	$message.="</tr>";
	
	//---------------Q------------------//
	$message.="<tr align='center'> \n";
	if ($row['Q_J_1'] == "1"){$message.="<td Style='border:thin solid #000000;' width='10%' bgcolor='#e2fdbd'>Q</td> \n";}
	else{$message.="<td Style='border:thin solid #000000;' bgcolor='#ffafae'>Q</td> \n";}
	$message.="<td Style='border:thin solid #000000;' width='90%'>".$row['CommentaireQ_J_1']."</td> \n";
	$message.="</tr>";
	
	//---------------C------------------//
	$message.="<tr align='center'> \n";
	if ($row['C_J_1'] == "1"){$message.="<td Style='border:thin solid #000000;' width='10%' bgcolor='#e2fdbd'>C</td> \n";}
	else{$message.="<td Style='border:thin solid #000000;' bgcolor='#ffafae'>C</td> \n";}
	$message.="<td Style='border:thin solid #000000;' width='90%'>".$row['CommentaireC_J_1']."</td> \n";
	$message.="</tr>";

	//---------------D------------------//
	$message.="<tr align='center'> \n";
	if ($row['D_J_1'] == "1"){$message.="<td Style='border:thin solid #000000;' width='10%' bgcolor='#e2fdbd'>D</td> \n";}
	else{$message.="<td Style='border:thin solid #000000;' bgcolor='#ffafae'>D</td> \n";}
	$message.="<td Style='border:thin solid #000000;' width='90%'>".$row['CommentaireD_J_1']."</td> \n";
	$message.="</tr>";
	
	//---------------P------------------//
	$message.="<tr align='center'> \n";
	if ($row['P_J_1'] == "1"){$message.="<td Style='border:thin solid #000000;' width='10%' bgcolor='#e2fdbd'>P</td> \n";}
	else{$message.="<td Style='border:thin solid #000000;' bgcolor='#ffafae'>P</td> \n";}
	$message.="<td Style='border:thin solid #000000;' width='90%'>".$row['CommentaireP_J_1']."</td> \n";
	$message.="</tr>";
	
	
	//---------------F------------------//
	$message.="<tr align='center'> \n";
	if ($row['F_J_1'] == "1"){$message.="<td Style='border:thin solid #000000;' width='10%' bgcolor='#e2fdbd' align='center'>F</td> \n";}
	else{$message.="<td Style='border:thin solid #000000;' bgcolor='#ffafae' align='center'>F</td> \n";}
	$message.="<td Style='border:thin solid #000000;' width='90%' align='center'>".$row['CommentaireF_J_1']."</td> \n";
	$message.="</tr>";
	//---------Créateur du SQCDPF--------//
	$reqCreateur="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$row['Id_Personne1'];
	$resultCreateur=mysqli_query($bdd,$reqCreateur);
	$rowCreateur = mysqli_fetch_array($resultCreateur);
	$message.="<tr><td colspan='6' align='left'>Créateur du SQCDPF : ".$rowCreateur['Nom']." ".$rowCreateur['Prenom']."</td></tr> \n";
	$message.="</table> \n";
	$message.="</td></tr>";
	$message.="<tr><td></br><br/></td></tr>";
	$message.="<tr><td>";
	$message.="<table width='100%' cellpadding='0' cellspacing='0' align='left'> \n";
	$message.="<tr><td align='left'></td></tr> \n";
	$message.="<tr><td colspan='6' align='left'>Points chauds</td></tr> \n";
	$message.="<tr bgcolor='#bacfea'>";
	$message.="<td align='center'>Niveau</td>";
	$message.="<td align='center'>Lettre</td>";
	$message.="<td align='center'>Description du problème</td>";
	$message.="<td align='center'>Commentaire</td>";
	$message.="<td align='center'>Description action</td>";
	$message.="<td align='center'>Responsable action</td>";
	$message.="<td align='center'>Délai</td>";
	$message.="</tr>";
	
	$couleur = "#eef3fa";
	
	$reqAct="SELECT new_action.Lettre, new_action.Probleme, new_action.Action, new_action.Delais, new_action.Niveau, ";
	$reqAct.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=new_action.Id_Acteur) AS Responsable, new_action.Commentaire ";
	$reqAct.="FROM new_action WHERE new_action.DateCreation='".$row['dateSQCDPF']."' AND new_action.Id_Prestation=".$row['Id_Prestation']." AND new_action.Id_Pole=".$row['Id_Pole'];
	
	$resulAction=mysqli_query($bdd,$reqAct);
	$nbAction=mysqli_num_rows($resulAction);
	if ($nbAction>0){
		while($rowAction=mysqli_fetch_array($resulAction)){
			if ($couleur == "#eef3fa"){$couleur = "#ffffff";}
			else{$couleur = "#eef3fa";}
			$message.="<tr bgcolor='".$couleur."'>";
			$message.="<td align='center'>".$rowAction['Niveau']."</td>";
			$message.="<td align='center'>".$rowAction['Lettre']."</td>";
			$message.="<td align='center'>".$rowAction['Probleme']."</td>";
			$message.="<td align='center'>".$rowAction['Commentaire']."</td>";
			$message.="<td align='center'>".$rowAction['Action']."</td>";
			$message.="<td align='center'>".$rowAction['Responsable']."</td>";
			if($rowAction['Delais'] > "0001-01-01"){
				$message.="<td align='center'>".$rowAction['Delais']."</td>";
			}
			else{
				$message.="<td align='center'></td>";
			}
			$message.="</tr>";
		}
	}					
	$message.="</table> \n";
	$message.="</td></tr>";
	
	$message.="<tr><td>";
	
	//Commentaire général
	$message.="<table> \n";
	$message.="<tr><td align='left'></td></tr> \n";
	$message.="<tr><td align='left'>Bonne journée</td></tr> \n";
	$message.="<tr><td align='left'>".$Signature."</td></tr> \n";
	$message.="</table> \n";
	$message.="</td></tr>";
	$message.="</table> \n";
	$message.='</body></html>';
	
	if(mail($destinataire, $object , $message , $headers,'-f extranet@aaa-aero.com')){
		echo"<script language=\"javascript\">alert('Le mail a bien été envoyé')</script>";
	}
	else{
		echo"<script language=\"javascript\">alert('".addslashes("Le mail n'a pas été envoyé")."')</script>";
	}	
	
	echo "<script>window.close();</script>";
}
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
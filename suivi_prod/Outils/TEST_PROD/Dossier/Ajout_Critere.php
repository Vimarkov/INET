<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../../JS/styleCalendrier.css?t=<?php echo time(); ?>">
	<link href="../../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	
	<script type="text/javascript" src="../../JS/jquery.min.js"></script>		
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../../JS/js/html5.js"></script><![endif]-->		
	<!-- Modernizr -->
	<script src="../../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script>
		function Recharger(){
			opener.location="Liste_Dossier.php";
		}
		function FermerEtRecharger(){
			window.opener.location = "Liste_Dossier.php";
			window.close();
		}
	</script>
</head>
<?php
session_start();
require("../../Connexioni.php");
require("../../Fonctions.php");

$DateJour=date("Y-m-d");
Ecrire_Code_JS_Init_Date(); 

 if($_POST){
	
	//Chaines de caracètres
	$tab = array("Programme","MSN","Reference","Caec","Titre","NumIC","TypeDossier","Priorite","StatutPREPA","StatutDossier","Section");
	foreach($tab as $filtre){
		if($_POST[$filtre]<>"" && strpos($_SESSION['Filtre'.$filtre.'2'],$_POST[$filtre].";")===false){
			$btn="<a style=\"text-decoration:none;\" href='javascript:Suppr_Critere(\"".$filtre."\",\"".str_replace("'"," ",str_replace('"',' ',$_POST[$filtre]))."\")'>&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['Filtre'.$filtre]=$_SESSION['Filtre'.$filtre].str_replace('"',' ',str_replace("'"," ",$_POST[$filtre])).$btn;
			$_SESSION['Filtre'.$filtre.'2']=$_SESSION['Filtre'.$filtre.'2'].str_replace("'"," ",str_replace('"',' ',$_POST[$filtre])).";";
		}
	}
	
	$tab = array("StatutPROD","StatutQUALITE");
	foreach($tab as $filtre){
		if($_POST[$filtre]<>"0" && strpos($_SESSION['Filtre'.$filtre.'2'],$_POST[$filtre].";")===false){
			if($_POST[$filtre]==""){
				$btn="<a style=\"text-decoration:none;\" href='javascript:Suppr_Critere(\"".$filtre."\",\"".str_replace("'"," ",str_replace('"',' ',$_POST[$filtre]))."\")'>&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				$_SESSION['Filtre'.$filtre]=$_SESSION['Filtre'.$filtre].str_replace('"',' ',str_replace("'"," ","(vide)")).$btn;
				$_SESSION['Filtre'.$filtre.'2']=$_SESSION['Filtre'.$filtre.'2'].str_replace("'"," ",str_replace('"',' ',$_POST[$filtre])).";";
			}
			else{
				$btn="<a style=\"text-decoration:none;\" href='javascript:Suppr_Critere(\"".$filtre."\",\"".str_replace("'"," ",str_replace('"',' ',$_POST[$filtre]))."\")'>&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				$_SESSION['Filtre'.$filtre]=$_SESSION['Filtre'.$filtre].str_replace('"',' ',str_replace("'"," ",$_POST[$filtre])).$btn;
				$_SESSION['Filtre'.$filtre.'2']=$_SESSION['Filtre'.$filtre.'2'].str_replace("'"," ",str_replace('"',' ',$_POST[$filtre])).";";
			}
		}
	}
	
	//Dates
	$tab = array("DatePrevisionnelleIntervention","DateInterventionDebut","DateInterventionFin","DateInterventionQDebut","DateInterventionQFin");
	foreach($tab as $filtre){
		if($_POST[$filtre]<>"" && strpos($_SESSION['Filtre'.$filtre.'2'],$_POST[$filtre].";")===false){
			$btn="<a style=\"text-decoration:none;\" href='javascript:Suppr_Critere(\"".$filtre."\",\"".str_replace('"',' ',$_POST[$filtre])."\")'>&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['Filtre'.$filtre]=str_replace('"',' ',$_POST[$filtre]).$btn;
			$_SESSION['Filtre'.$filtre.'2']=str_replace('"',' ',$_POST[$filtre]).";";
		}
	}
	
	//Clé étrangère
	$tab = array("Client","Poste","Localisation","VacationPROD","VacationQUALITE","IQ");
	foreach($tab as $filtre){
		$left="_".substr($_POST[$filtre],0,strpos($_POST[$filtre],";"));
		if($_POST[$filtre]<>"" && strpos($_SESSION['Zone2'],$left.";")===false){
			$right=substr($_POST[$filtre],strpos($_POST[$filtre],";")+1);
			$btn="<a style=\"text-decoration:none;\" href='javascript:Suppr_Critere(\"".$filtre."\",\"".$left."\")'>&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['Filtre'.$filtre]=$_SESSION['Filtre'.$filtre].$right.$btn;
			$_SESSION['Filtre'.$filtre.'2']=$_SESSION['Filtre'.$filtre.'2'].$left.";";
		}
	}
	
	//Case à cocher
	$tab = array("SansDateIntervention","SansDateInterventionQ");
	foreach($tab as $filtre){
		if(isset($_POST[$filtre])){
			$btn="<a style=\"text-decoration:none;\" href='javascript:Suppr_Critere(\"".$filtre."\",\"\")'>&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['Filtre'.$filtre]="oui".$btn;
		$_SESSION['Filtre'.$filtre.'2']="oui";
		}
	}
	
	$_SESSION['Page']=0;
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		
		//Chaines de caracètres
		$tab = array("Programme","MSN","Reference","Section","Caec","Titre","NumIC","TypeDossier","Priorite","StatutPREPA","StatutDossier");
		foreach($tab as $filtre){
			if($_GET['critere']==$filtre){
				$valeur="<a style=\"text-decoration:none;\" href='javascript:Suppr_Critere(\"".$filtre."\",\"".$_GET['valeur']."\")'>&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				$_SESSION['Filtre'.$filtre]=str_replace($_GET['valeur'].$valeur,"",$_SESSION['Filtre'.$filtre]);
				$_SESSION['Filtre'.$filtre.'2']=str_replace($_GET['valeur'].";","",$_SESSION['Filtre'.$filtre.'2']);
			}
		}
		
		$tab = array("StatutPROD","StatutQUALITE");
		foreach($tab as $filtre){
			if($_GET['critere']==$filtre){
				if($_GET['valeur']==""){
					$valeur="<a style=\"text-decoration:none;\" href='javascript:Suppr_Critere(\"".$filtre."\",\"".$_GET['valeur']."\")'>&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['Filtre'.$filtre]=str_replace("(vide)".$valeur,"",$_SESSION['Filtre'.$filtre]);
					$_SESSION['Filtre'.$filtre.'2']=str_replace($_GET['valeur'].";","",$_SESSION['Filtre'.$filtre.'2']);
				}
				else{
					$valeur="<a style=\"text-decoration:none;\" href='javascript:Suppr_Critere(\"".$filtre."\",\"".$_GET['valeur']."\")'>&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$_SESSION['Filtre'.$filtre]=str_replace($_GET['valeur'].$valeur,"",$_SESSION['Filtre'.$filtre]);
					$_SESSION['Filtre'.$filtre.'2']=str_replace($_GET['valeur'].";","",$_SESSION['Filtre'.$filtre.'2']);
				}
			}
		}
		
		//Dates
		$tab = array("DatePrevisionnelleIntervention","DateInterventionDebut","DateInterventionFin","DateInterventionQDebut","DateInterventionQFin");
		foreach($tab as $filtre){
			if($_GET['critere']==$filtre){
				$valeur="<a style=\"text-decoration:none;\" href='javascript:Suppr_Critere(\"".$filtre."\",\"".$_GET['valeur']."\")'>&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				$_SESSION['Filtre'.$filtre]=str_replace($_GET['valeur'].$valeur,"",$_SESSION['Filtre'.$filtre]);
				$_SESSION['Filtre'.$filtre.'2']=str_replace($_GET['valeur'],"",$_SESSION['Filtre'.$filtre.'2']);
			}
		}
		
		//Clé étrangère
		$tab = array("Client","Poste","Localisation","IQ");
		foreach($tab as $filtre){
			if($_GET['critere']==$filtre){
				$_SESSION['Filtre'.$filtre.'2']=str_replace($_GET['valeur'].";","",$_SESSION['Filtre'.$filtre.'2']);
				$tab = explode(";",$_SESSION['Filtre'.$filtre.'2']);
				$_SESSION['Filtre'.$filtre]="";
				foreach($tab as $Id){
					if($Id<>""){
						$valeur="<a style=\"text-decoration:none;\" href='javascript:Suppr_Critere(\"".$filtre."\",\"".$Id."\")'>&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
						if($filtre=="Client"){$req="SELECT Libelle FROM sp_client WHERE Id=".substr($Id,1);}
						elseif($filtre=="Poste"){$req="SELECT Libelle FROM sp_poste WHERE Id=".substr($Id,1);}
						elseif($filtre=="Localisation"){$req="SELECT Libelle FROM sp_localisation WHERE Id=".substr($Id,1);}
						elseif($filtre=="IQ"){$req="SELECT CONCAT(Nom,' ',Prenom) AS Libelle FROM new_rh_etatcivil WHERE Id=".substr($Id,1);}
						
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if($nbResulta>0){
							$row=mysqli_fetch_array($result);
							$_SESSION['Filtre'.$filtre].=$row['Libelle'].$valeur;
						}
					}
				}
				
			}
		}
		
		$tab = array("VacationPROD","VacationQUALITE");
		foreach($tab as $filtre){
			if($_GET['critere']==$filtre){
				$valeur="<a style=\"text-decoration:none;\" href='javascript:Suppr_Critere(\"".$filtre."\",\"".$_GET['valeur']."\")'>&nbsp;<img src=\"../../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
				if($_GET['valeur']=="_J"){$_SESSION['Filtre'.$filtre]=str_replace("Jour".$valeur,"",$_SESSION['Filtre'.$filtre]);}
				elseif($_GET['valeur']=="_S"){$_SESSION['Filtre'.$filtre]=str_replace("Soir".$valeur,"",$_SESSION['Filtre'.$filtre]);}
				elseif($_GET['valeur']=="_N"){$_SESSION['Filtre'.$filtre]=str_replace("Nuit".$valeur,"",$_SESSION['Filtre'.$filtre]);}
				elseif($_GET['valeur']=="_VSD"){$_SESSION['Filtre'.$filtre]=str_replace("Weekend".$valeur,"",$_SESSION['Filtre'.$filtre]);}
				$_SESSION['Filtre'.$filtre.'2']=str_replace($_GET['valeur'].";","",$_SESSION['Filtre'.$filtre.'2']);
			}
		}
		
		//Case à cocher
		$tab = array("SansDateIntervention","SansDateInterventionQ");
		foreach($tab as $filtre){
			if($_GET['critere']==$filtre){
				$_SESSION['Filtre'.$filtre]="";
				$_SESSION['Filtre'.$filtre.'2']="";
			}
		}

		$_SESSION['Page']=0;
		echo "<script>FermerEtRecharger();</script>";
	}
}
 ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Ajout_Critere.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage">Ajouter des critères</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr><td height="4"></td></tr>
		<tr>
			<td colspan="10" style="color:#035fff;" class="Libelle"> &nbsp; DOSSIER
			</td>
		</tr>
		<tr>
			<td width="20%" class="Libelle">
				&nbsp; MSN :
			</td>
			<td> 
				<input type="texte" style="text-align:center;" name="MSN" size="10" value="">
			</td>
			<td class="Libelle">&nbsp; Programme : </td>
			<td>
				<select name="Programme">
					<option value=""></option>
					<option value="A320">A320</option>
					<option value="A330">A330</option>
					<option value="A350">A350</option>
					<option value="A380">A380</option>
				</select>
			</td>
			<td class="Libelle">
				&nbsp; N° dossier :
			</td>
			<td> 
				<input type="texte" style="text-align:center;" name="Reference" size="15" value="">
			</td>
		</tr>
		<tr>
			<td height="5px;"></td>
		</tr>
		<tr>
			<td width="20%" class="Libelle">
				&nbsp; Client :
			</td>
			<td >
				<select name="Client">
					<option value=""></option>
					<?php
					$req="SELECT DISTINCT sp_olwdossier.Id_Client,(SELECT Libelle FROM sp_client WHERE sp_client.Id=sp_olwdossier.Id_Client) AS Client FROM sp_olwdossier WHERE Id_Prestation=-16 ORDER BY Client;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							echo "<option value='".$row['Id_Client'].";".str_replace("'"," ",$row['Client'])."'>".$row['Client']."</option>";
						}
					}
					?>
				</select>
			</td>
			<td class="Libelle">&nbsp; Type de dossier : </td>
			<td>
				<select name="TypeDossier">
					<option value=""></option>
					<option value="FormA">FormA</option>
					<option value="NC">NC</option>
					<option value="OW">OW</option>
					<option value="PARA">PARA</option>
					<option value="QLB">QLB</option>
					<option value="TLB">TLB</option>
				</select>
			</td>
			<td class='Libelle'>&nbsp; Priorité :</td>
			<td>
				<select name="Priorite">
					<option value=""></option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
				</select>
			</td>
		</tr>
		<tr>
			<td height="5px;"></td>
		</tr>
		<tr>
			<td width='20%' class='Libelle'>&nbsp; CA/EC : </td>
			<td>
				<input name='Caec' value='' size='8'>
			</td>
			<td class="Libelle">&nbsp; Section : </td>
			<td>
				<select name="Section">
					<option value=""></option>
					<?php
						$req="SELECT Id,Libelle FROM sp_olwsection WHERE Id_Prestation=-16 AND Supprime=false ORDER BY Libelle;";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowSection=mysqli_fetch_array($result)){
								$selected="";
								echo "<option value='".$rowSection['Libelle']."' ".$selected.">".$rowSection['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
			<td class="Libelle">&nbsp; Statut Prépa : </td>
			<td>
				<select name="StatutPREPA" >
					<option name="" value=""></option>
					<?php
					$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation=-16 AND TypeStatut='R' ORDER BY Id;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($rowStatut=mysqli_fetch_array($result)){
							echo "<option value='".$rowStatut['Id']."'>".$rowStatut['Id']."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td height="5px;"></td>
		</tr>
		<tr>
			<td class='Libelle'>&nbsp; Poste : </td>
			<td>
				<select name="Poste">
					<option value=''></option>
					<?php
						$req="SELECT Id,Libelle FROM sp_poste WHERE Id_Prestation=-16 AND Supprime=false ORDER BY Libelle";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowL=mysqli_fetch_array($result)){
								$selected="";
								echo "<option value='".$rowL['Id'].";".str_replace("'"," ",$rowL['Libelle'])."' ".$selected.">".$rowL['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
			<td class='Libelle'>&nbsp; Localisation : </td>
			<td>
				<select name="Localisation">
					<option value=''></option>
					<?php
						$req="SELECT Id,Libelle FROM sp_localisation WHERE Id_Prestation=-16 AND Supprime=false ORDER BY Libelle";
						$result=mysqli_query($bdd,$req);
						$nbResulta=mysqli_num_rows($result);
						if ($nbResulta>0){
							while($rowL=mysqli_fetch_array($result)){
								$selected="";
								echo "<option value='".$rowL['Id'].";".str_replace("'"," ",$rowL['Libelle'])."' ".$selected.">".$rowL['Libelle']."</option>";
							}
						}
					?>
				</select>
			</td>
			<td class="Libelle">&nbsp; Date prévisionnelle intervention : </td>
			<td>
				<input type="date" style="text-align:center;" name="DatePrevisionnelleIntervention" size="10" value="">
			</td>
		</tr>
		<tr>
			<td height="5px;"></td>
		</tr>
		<tr>
			<td class="Libelle">&nbsp; Statut du dossier : </td>
			<td>
				<select name="StatutDossier">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT Id FROM sp_olwstatut WHERE Id_Prestation=-16 AND TypeStatut IN ('P','Q') ORDER BY Id;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($rowStatut=mysqli_fetch_array($result)){
							echo "<option value='".$rowStatut['Id']."'>".$rowStatut['Id']."</option>";
						}
					}
					?>
				</select>
			</td>
			<td class="Libelle">
				&nbsp; Titre :
			</td>
			<td colspan="10">
				<input type="texte" name="Titre" size="50" value="">
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td style="border-top:1px dotted #0077aa;color:#035fff;" class="Libelle" colspan="10"> &nbsp; PROD
			</td>
		</tr>
		<tr>
			<td width="20%" class="Libelle">
				&nbsp; Vacation :
			</td>
			<td>
				<select name="VacationPROD">
					<option value=""></option>
					<option value="J;Jour">Jour</option>
					<option value="S;Soir">Soir</option>
					<option value="N;Nuit">Nuit</option>
					<option value="VSD;Weekend">Weekend</option>
				</select>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td width="20%" class="Libelle">
				&nbsp; Du :
			</td>
			<td> 
				<input type="date" style="text-align:center;" name="DateInterventionDebut" size="15" value="">
			</td>
			<td width="20%" class="Libelle">
				&nbsp; au :
			</td>
			<td> 
				<input type="date" style="text-align:center;" name="DateInterventionFin" size="15" value="">
			</td>
			<td width="20%" class="Libelle">
				&nbsp; Sans date <input type="checkbox" style="text-align:center;" name="SansDateIntervention" value="sansDate">
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td width="20%" class="Libelle">
				&nbsp; N° IC :
			</td>
			<td> 
				<input type="texte" style="text-align:center;" name="NumIC" size="15" value="">
			</td>
			<td class="Libelle">&nbsp; Statut PROD : </td>
			<td>
				<select name="StatutPROD">
					<option value="0"></option>
					<option value="">(vide)</option>
					<?php
					$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation=-16 AND TypeStatut IN ('P') ORDER BY Id;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($rowStatut=mysqli_fetch_array($result)){
							echo "<option name='".$rowStatut['Id']."' value='".$rowStatut['Id']."'>".$rowStatut['Id']."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td height="4"></td>
		</tr>
		<tr>
			<td style="border-top:1px dotted #0077aa;color:#035fff;" class="Libelle" colspan="10"> &nbsp; QUALITE
			</td>
		</tr>
		<tr>
			<td width="20%" class="Libelle">
				&nbsp; Vacation :
			</td>
			<td colspan="10">
				<select name="VacationQUALITE">
					<option value=""></option>
					<option value="J;Jour">Jour</option>
					<option value="S;Soir">Soir</option>
					<option value="N;Nuit">Nuit</option>
					<option value="VSD;Weekend">Weekend</option>
				</select>
			</td>
		</tr>
		<tr>
			<td height="4"></td>
		</tr>
		<tr>
			<td width="20%" class="Libelle">
				&nbsp; Inspecteur qualité :
			</td>
			<td >
				<select name="IQ">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT sp_olwficheintervention.Id_QUALITE AS Id, ";
					$req.="(SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=sp_olwficheintervention.Id_QUALITE) AS NomPrenom ";
					$req.="FROM sp_olwficheintervention WHERE (SELECT sp_olwdossier.Id_Prestation FROM sp_olwdossier WHERE sp_olwdossier.Id=sp_olwficheintervention.Id_Dossier)=-16 ORDER BY NomPrenom;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							$Libelle="(vide)";
							if($row['Id']<>0){$Libelle=$row['NomPrenom'];}
							echo "<option name='".$row['Id']."' value='".$row['Id'].";".$Libelle."'>".$Libelle."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td height="4"></td>
		</tr>
		<tr>
			<td width="20%" class="Libelle">
				&nbsp; Du :
			</td>
			<td> 
				<input type="date" style="text-align:center;" name="DateInterventionQDebut" size="15" value="">
			</td>
			<td class="Libelle">
				&nbsp; au :
			</td>
			<td>
				<input type="date" style="text-align:center;" name="DateInterventionQFin" size="15" value="">
			</td>
			<td class="Libelle">
				&nbsp; Sans date <input type="checkbox" style="text-align:center;" name="SansDateInterventionQ" value="sansDate">
			</td>
		</tr>
		<tr>
			<td height="4"></td>
		</tr>
		<tr>
			<td class="Libelle">&nbsp; Statut QUALITE : </td>
			<td>
				<select name="StatutQUALITE">
					<option value="0"></option>
					<option value="">(vide)</option>
					<?php
					$req="SELECT Id FROM sp_olwstatut WHERE Id_Prestation=-16 AND TypeStatut IN ('Q') ORDER BY Id;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($rowStatut=mysqli_fetch_array($result)){
							echo "<option name='".$rowStatut['Id']."' value='".$rowStatut['Id']."'>".$rowStatut['Id']."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td height="4"></td>
		</tr>
		<tr>
			<td style="border-bottom:1px dotted #0077aa;" colspan="10" height="4"></td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td align="center" colspan="10">
				<input class="Bouton" name="BtnRechercher" size="10" type="submit" value="Ajouter">
			</td>
			
		</tr>
	</table>
	</td></tr>
</form>
</table>
</body>
</html>
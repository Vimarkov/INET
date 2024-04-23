<?php
session_start();
require("../Connexioni.php");
require_once("../Formation/Globales_Fonctions.php");
require("../Fonctions.php");

function unNombreSinon0($leNombre){
	$nb=0;
	if($leNombre<>""){$nb=(double)$leNombre;}
	return $nb;
}

$tableau = '<table width="90%" cellpadding="0" cellspacing="0" align="center">';
$tableau .= '	<tr>';
$tableau .= '		<td class="Libelle" style="border:1px solid black;" align="center">';
			if($_SESSION['Langue']=="EN"){$tableau .= "Family";}else{$tableau .= "Famille";}
$tableau .= '		</td>';
$tableau .= '		<td class="Libelle" style="border:1px solid black;" align="center">';
			if($_SESSION['Langue']=="EN"){$tableau .= "Resource";}else{$tableau .= "Ressource";}
			$tableau .= '</td>';
$tableau .= '		<td class="Libelle" style="border:1px solid black;" align="center">';
			if($_SESSION['Langue']=="EN"){$tableau .= "M";}else{$tableau .= "M";}
			$tableau .= '</td>';
$tableau .= '		<td class="Libelle" style="border:1px solid black;" align="center">';
			if($_SESSION['Langue']=="EN"){$tableau .= "M+1";}else{$tableau .= "M+1";}
			$tableau .= '</td>';
$tableau .= '		<td class="Libelle" style="border:1px solid black;" align="center">';
			if($_SESSION['Langue']=="EN"){$tableau .= "M+2";}else{$tableau .= "M+2";}
			$tableau .= '</td>';
$tableau .= '		<td class="Libelle" style="border:1px solid black;" align="center">';
			if($_SESSION['Langue']=="EN"){$tableau .= "M+3";}else{$tableau .= "M+3";}
			$tableau .= '</td>';
$tableau .= '		<td class="Libelle" style="border:1px solid black;" align="center">';
			if($_SESSION['Langue']=="EN"){$tableau .= "M+4";}else{$tableau .= "M+4";}
			$tableau .= '</td>';
$tableau .= '		<td class="Libelle" style="border:1px solid black;" align="center">';
			if($_SESSION['Langue']=="EN"){$tableau .= "M+5";}else{$tableau .= "M+5";}
			$tableau .= '</td>';
$tableau .= '		<td class="Libelle" style="border:1px solid black;" align="center">';
			if($_SESSION['Langue']=="EN"){$tableau .= "M+6";}else{$tableau .= "M+6";}
			$tableau .= '</td>';
$tableau .= '	</tr>';
	
$sommeM=0;
$sommeM1=0;
$sommeM2=0;
$sommeM3=0;
$sommeM4=0;
$sommeM5=0;
$sommeM6=0;

$sommeCapaM=0;
$sommeCapaM1=0;
$sommeCapaM2=0;
$sommeCapaM3=0;
$sommeCapaM4=0;
$sommeCapaM5=0;
$sommeCapaM6=0;

$couleurVert="background-color:#a5cb9b;";
$couleurRouge="background-color:#e6bcb3;";
$couleurBleu="background-color:#b3d6e6;";

$req="CREATE TEMPORARY TABLE liste_famille (Id INT ,Libelle VARCHAR(255));";
$resultFamille=mysqli_query($bdd,$req);

$req="INSERT INTO liste_famille (Id,Libelle) VALUES (0,'Indéfini');";
$resultFamille=mysqli_query($bdd,$req);
	
//Liste des prestations concernées + récupérer le nombre
$req="SELECT new_competences_prestation.Id 
		FROM new_competences_prestation
		LEFT JOIN new_competences_plateforme
		ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
		WHERE new_competences_prestation.UtiliseMORIS>0 ";
if($_SESSION['FiltreRECORD_Prestation']<>""){
	$req.="AND new_competences_prestation.Id IN(".$_SESSION['FiltreRECORD_Prestation'].") ";
}
$resultPrestation2=mysqli_query($bdd,$req);
$nbPrestation2=mysqli_num_rows($resultPrestation2);

$listePrestation2="-1";
if ($nbPrestation2 > 0)
{
	mysqli_data_seek($resultPrestation2,0);
	while($row=mysqli_fetch_array($resultPrestation2))
	{
		if($listePrestation2<>""){$listePrestation2.=",";}
		$listePrestation2.=$row['Id'];
	}
}
			
$moisEC=date($_SESSION['MORIS_Annee']."-".$_SESSION['MORIS_Mois']."-1");			
if($_SESSION['FiltreRECORD_NbMois']==12){
	$nbMois9=9;
	$nbMois15=15;
	$nbMois17=17;
	$date_11Mois = date("Y-m-d",strtotime($moisEC." -11 month"));
}
elseif($_SESSION['FiltreRECORD_NbMois']==6){
	$nbMois9=3;
	$nbMois15=8;
	$nbMois17=10;
	$date_11Mois = date("Y-m-d",strtotime($moisEC." -5 month"));
}
elseif($_SESSION['FiltreRECORD_NbMois']==3){
	$nbMois9=0;
	$nbMois15=3;
	$nbMois17=5;
	$date_11Mois = date("Y-m-d",strtotime($moisEC." -2 month"));
}

$annee3Mois=date("Y",strtotime($date_11Mois." +".$nbMois9." month"));
$mois3Mois=date("m",strtotime($date_11Mois." +".$nbMois9." month"));

$annee6Mois=date("Y",strtotime($date_11Mois." +".$nbMois15." month"));
$mois6Mois=date("m",strtotime($date_11Mois." +".$nbMois15." month"));

$annee8Mois=date("Y",strtotime($date_11Mois." +".$nbMois17." month"));
$mois8Mois=date("m",strtotime($date_11Mois." +".$nbMois17." month"));


$anneeDuJour_1=date("Y",strtotime(date('Y-m-1')." -2 month"));
$moisDuJour_1=date("m",strtotime(date('Y-m-1')." -2 month"));

$anneeDuJour=date("Y",strtotime(date('Y-m-1')." -1 month"));
$moisDuJour=date("m",strtotime(date('Y-m-1')." -1 month"));

$anneeDuJour1=date("Y",strtotime(date('Y-m-1')." 0 month"));
$moisDuJour1=date("m",strtotime(date('Y-m-1')." 0 month"));
$anneeDuJour2=date("Y",strtotime(date('Y-m-1')." 1 month"));
$moisDuJour2=date("m",strtotime(date('Y-m-1')." 1 month"));
$anneeDuJour3=date("Y",strtotime(date('Y-m-1')." 2 month"));
$moisDuJour3=date("m",strtotime(date('Y-m-1')." 2 month"));
$anneeDuJour4=date("Y",strtotime(date('Y-m-1')." 3 month"));
$moisDuJour4=date("m",strtotime(date('Y-m-1')." 3 month"));
$anneeDuJour5=date("Y",strtotime(date('Y-m-1')." 4 month"));
$moisDuJour5=date("m",strtotime(date('Y-m-1')." 4 month"));
$anneeDuJour6=date("Y",strtotime(date('Y-m-1')." 5 month"));
$moisDuJour6=date("m",strtotime(date('Y-m-1')." 5 month"));

$req="
	INSERT INTO liste_famille
	SELECT DISTINCT Id_Famille AS Id,
	(SELECT Libelle FROM moris_famille WHERE Id=Id_Famille) AS Libelle
	FROM moris_moisprestation_famille
	LEFT JOIN moris_moisprestation
	ON moris_moisprestation_famille.Id_MoisPrestation=moris_moisprestation.Id
	WHERE Id_Famille>0
	AND moris_moisprestation.Suppr=0
	AND Id_Prestation IN (".$listePrestation2.") ";
	if($annee3Mois.'_'.$mois3Mois>$anneeDuJour_1.'_'.$moisDuJour_1){
		$req.="AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))>='".$anneeDuJour_1.'_'.$moisDuJour_1."' ";
	}
	else{
		$req.="AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))>='".$annee3Mois.'_'.$mois3Mois."' ";
	}
		
	$req.="AND Id_Famille IN (".$_SESSION['MORIS_ListeFamilleIndefini'].")
	AND CONCAT(Annee,'_',IF(Mois>=10,Mois,CONCAT(0,Mois)))<='".$annee8Mois.'_'.$mois8Mois."'
	ORDER BY Libelle";

$resultFamille=mysqli_query($bdd,$req);

$req="
	SELECT Id, Libelle 
	FROM liste_famille";											
$resultFamille=mysqli_query($bdd,$req);
$nbFamille=mysqli_num_rows($resultFamille);

$tabPrestation=explode(",",$listePrestation2);
	
if($nbFamille>0){
	while($rowFamille=mysqli_fetch_array($resultFamille)){
		$M=0;
		$M1=0;
		$M2=0;
		$M3=0;
		$M4=0;
		$M5=0;
		$M6=0;
		
		$eM=0;
		$eM1=0;
		$eM2=0;
		$eM3=0;
		$eM4=0;
		$eM5=0;
		$eM6=0;
		
		$CapaM=0;
		$CapaM1=0;
		$CapaM2=0;
		$CapaM3=0;
		$CapaM4=0;
		$CapaM5=0;
		$CapaM6=0;
		
		$CapaeM=0;
		$CapaeM1=0;
		$CapaeM2=0;
		$CapaeM3=0;
		$CapaeM4=0;
		$CapaeM5=0;
		$CapaeM6=0;
		
		$visibleInterne="style='display:none'";
		$visibleExterne="style='display:none'";
		if($_SESSION['MORIS_Annee']."_".$_SESSION['MORIS_Mois']>"2022_09"){
			
			foreach($tabPrestation as $presta){
				if($presta<>-1){
					$laDate=date($_SESSION['MORIS_Annee']."-".$_SESSION['MORIS_Mois']."-01");
					for($i=0;$i<=6;$i++){
						
						$req2="";
						$anneeEC2=date("Y",strtotime($laDate." +0 month"));
						$moisEC2=date("m",strtotime($laDate." +0 month"));
						
						
						$req="SELECT Id
						FROM moris_moisprestation
						WHERE moris_moisprestation.Id_Prestation = ".$presta."
						AND Annee=".$anneeEC2." 
						AND Mois=".$moisEC2."
						AND Suppr=0 	
						AND (
							COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id ),0)>0
							OR 
							COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0)>0
						)
						";

						$result=mysqli_query($bdd,$req);
						$nbResultaMoisPresta=mysqli_num_rows($result);

						if($nbResultaMoisPresta>0){
							$LigneMoisPrestation=mysqli_fetch_array($result);
						}
						else{
							
							$nbResultaMoisPrestaM1=0;
							if($anneeEC2."_".$moisEC2>=$anneeDuJour."_".$moisDuJour && $anneeEC2."_".$moisEC2<=$anneeDuJour6."_".$moisDuJour6){
								
								$laDate2=date("Y-m-d",strtotime($laDate." +0 month"));
								$anneeEC3=date("Y",strtotime($laDate2." +0 month"));
								$moisEC3=date("m",strtotime($laDate2." +0 month"));
								
								$annee_1=date("Y",strtotime($laDate2." -1 month"));
								$mois_1=date("m",strtotime($laDate2." -1 month"));
								$annee_2=date("Y",strtotime($laDate2." -2 month"));
								$mois_2=date("m",strtotime($laDate2." -2 month"));
								$annee_3=date("Y",strtotime($laDate2." -3 month"));
								$mois_3=date("m",strtotime($laDate2." -3 month"));
								$annee_4=date("Y",strtotime($laDate2." -4 month"));
								$mois_4=date("m",strtotime($laDate2." -4 month"));
								$annee_5=date("Y",strtotime($laDate2." -5 month"));
								$mois_5=date("m",strtotime($laDate2." -5 month"));
								$annee_6=date("Y",strtotime($laDate2." -6 month"));
								$mois_6=date("m",strtotime($laDate2." -6 month"));
								$annee_7=date("Y",strtotime($laDate2." -7 month"));
								$mois_7=date("m",strtotime($laDate2." -7 month"));
								
								$req="SELECT Id,CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) AS AnneeMois
								FROM moris_moisprestation
								WHERE moris_moisprestation.Id_Prestation = ".$presta."
								AND (
									COALESCE((SELECT SUM(M) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id ),0)>0
									OR 
									COALESCE((SELECT SUM(CapaM) FROM moris_moisprestation_famille WHERE Id_MoisPrestation=moris_moisprestation.Id),0)>0
								)
								AND Suppr=0 ";

								if($anneeEC2."_".$moisEC2==$anneeDuJour."_".$moisDuJour){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."') ";}
								elseif($anneeEC2."_".$moisEC2==$anneeDuJour1."_".$moisDuJour1){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."') ";}
								elseif($anneeEC2."_".$moisEC2==$anneeDuJour2."_".$moisDuJour2){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."') ";}
								elseif($anneeEC2."_".$moisEC2==$anneeDuJour3."_".$moisDuJour3){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."') ";}
								elseif($anneeEC2."_".$moisEC2==$anneeDuJour4."_".$moisDuJour4){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."') ";}
								elseif($anneeEC2."_".$moisEC2==$anneeDuJour5."_".$moisDuJour5){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."') ";}
								elseif($anneeEC2."_".$moisEC2==$anneeDuJour6."_".$moisDuJour6){$req.="AND CONCAT(Annee,'_',IF(Mois<10,CONCAT(0,Mois),Mois)) IN ('".$annee_1."_".$mois_1."','".$annee_2."_".$mois_2."','".$annee_3."_".$mois_3."','".$annee_4."_".$mois_4."','".$annee_5."_".$mois_5."','".$annee_6."_".$mois_6."','".$annee_7."_".$mois_7."') ";}
								$req.="ORDER BY Annee DESC, Mois DESC ";
								
								$resultM1=mysqli_query($bdd,$req);
								$nbResultaMoisPrestaM1=mysqli_num_rows($resultM1);
								if($nbResultaMoisPrestaM1>0){$LigneMoisPrestation=mysqli_fetch_array($resultM1);}
								
								
							}
							
						}
						$leMoisCharge="-1";
						
						
						if($nbResultaMoisPresta>0){
							$leMoisCharge="";
						}
						elseif($nbResultaMoisPrestaM1>0){
							if($LigneMoisPrestation['AnneeMois']==$annee_1."_".$mois_1){$leMoisCharge="1";}
							elseif($LigneMoisPrestation['AnneeMois']==$annee_2."_".$mois_2){$leMoisCharge="2";}
							elseif($LigneMoisPrestation['AnneeMois']==$annee_3."_".$mois_3){$leMoisCharge="3";}
							elseif($LigneMoisPrestation['AnneeMois']==$annee_4."_".$mois_4){$leMoisCharge="4";}
							elseif($LigneMoisPrestation['AnneeMois']==$annee_5."_".$mois_5){$leMoisCharge="5";}
							elseif($LigneMoisPrestation['AnneeMois']==$annee_6."_".$mois_6){$leMoisCharge="6";}
						}
						
						if($leMoisCharge<>"-1"){
							//INTERNE
							$req="SELECT M".$leMoisCharge." AS leM, CapaM".$leMoisCharge." AS leCapaM 
								FROM moris_moisprestation_famille 
								WHERE Externe=0 
								AND Id_Famille=".$rowFamille['Id']." 
								AND Id_MoisPrestation=".$LigneMoisPrestation['Id']." ";
							$resultFamilleMois=mysqli_query($bdd,$req);
							$nbFamilleMois=mysqli_num_rows($resultFamilleMois);
							if($nbFamilleMois>0){
								$LigneFamilleMois=mysqli_fetch_array($resultFamilleMois);
								
								if($i==0){$M+=$LigneFamilleMois['leM'];$CapaM+=$LigneFamilleMois['leCapaM'];}
								elseif($i==1){$M1+=$LigneFamilleMois['leM'];$CapaM1+=$LigneFamilleMois['leCapaM'];}
								elseif($i==2){$M2+=$LigneFamilleMois['leM'];$CapaM2+=$LigneFamilleMois['leCapaM'];}
								elseif($i==3){$M3+=$LigneFamilleMois['leM'];$CapaM3+=$LigneFamilleMois['leCapaM'];}
								elseif($i==4){$M4+=$LigneFamilleMois['leM'];$CapaM4+=$LigneFamilleMois['leCapaM'];}
								elseif($i==5){$M5+=$LigneFamilleMois['leM'];$CapaM5+=$LigneFamilleMois['leCapaM'];}
								elseif($i==6){$M6+=$LigneFamilleMois['leM'];$CapaM6+=$LigneFamilleMois['leCapaM'];}

							}

							//EXTERNE
							$req="SELECT M".$leMoisCharge." AS leM, CapaM".$leMoisCharge." AS leCapaM 
								FROM moris_moisprestation_famille 
								WHERE Externe=1
								AND Id_Famille=".$rowFamille['Id']."
								AND Id_MoisPrestation=".$LigneMoisPrestation['Id']." ";
							
							$resultFamilleMois=mysqli_query($bdd,$req);
							$nbFamilleMois=mysqli_num_rows($resultFamilleMois);
							if($nbFamilleMois>0){
								$LigneFamilleMois=mysqli_fetch_array($resultFamilleMois);

								if($i==0){$eM+=$LigneFamilleMois['leM'];$CapaeM+=$LigneFamilleMois['leCapaM'];}
								elseif($i==1){$eM1+=$LigneFamilleMois['leM'];$CapaeM1+=$LigneFamilleMois['leCapaM'];}
								elseif($i==2){$eM2+=$LigneFamilleMois['leM'];$CapaeM2+=$LigneFamilleMois['leCapaM'];}
								elseif($i==3){$eM3+=$LigneFamilleMois['leM'];$CapaeM3+=$LigneFamilleMois['leCapaM'];}
								elseif($i==4){$eM4+=$LigneFamilleMois['leM'];$CapaeM4+=$LigneFamilleMois['leCapaM'];}
								elseif($i==5){$eM5+=$LigneFamilleMois['leM'];$CapaeM5+=$LigneFamilleMois['leCapaM'];}
								elseif($i==6){$eM6+=$LigneFamilleMois['leM'];$CapaeM6+=$LigneFamilleMois['leCapaM'];}
							}
						}
						
						$laDate=date("Y-m-d",strtotime($laDate." +1 month"));
					}
					
				}
			}
		}
		
		$sommeM+=unNombreSinon0($M);
		$sommeM1+=unNombreSinon0($M1);
		$sommeM2+=unNombreSinon0($M2);
		$sommeM3+=unNombreSinon0($M3);
		$sommeM4+=unNombreSinon0($M4);
		$sommeM5+=unNombreSinon0($M5);
		$sommeM6+=unNombreSinon0($M6);
		
		$sommeM+=unNombreSinon0($eM);
		$sommeM1+=unNombreSinon0($eM1);
		$sommeM2+=unNombreSinon0($eM2);
		$sommeM3+=unNombreSinon0($eM3);
		$sommeM4+=unNombreSinon0($eM4);
		$sommeM5+=unNombreSinon0($eM5);
		$sommeM6+=unNombreSinon0($eM6);
		
		$sommeCapaM+=unNombreSinon0($CapaM);
		$sommeCapaM1+=unNombreSinon0($CapaM1);
		$sommeCapaM2+=unNombreSinon0($CapaM2);
		$sommeCapaM3+=unNombreSinon0($CapaM3);
		$sommeCapaM4+=unNombreSinon0($CapaM4);
		$sommeCapaM5+=unNombreSinon0($CapaM5);
		$sommeCapaM6+=unNombreSinon0($CapaM6);
		
		$sommeCapaM+=unNombreSinon0($CapaeM);
		$sommeCapaM1+=unNombreSinon0($CapaeM1);
		$sommeCapaM2+=unNombreSinon0($CapaeM2);
		$sommeCapaM3+=unNombreSinon0($CapaeM3);
		$sommeCapaM4+=unNombreSinon0($CapaeM4);
		$sommeCapaM5+=unNombreSinon0($CapaeM5);
		$sommeCapaM6+=unNombreSinon0($CapaeM6);
		
		if((unNombreSinon0($M)+unNombreSinon0($M1)+unNombreSinon0($M2)+unNombreSinon0($M3)+unNombreSinon0($M4)+unNombreSinon0($M5)+unNombreSinon0($M6))>0
		|| (unNombreSinon0($CapaM)+unNombreSinon0($CapaM1)+unNombreSinon0($CapaM2)+unNombreSinon0($CapaM3)+unNombreSinon0($CapaM4)+unNombreSinon0($CapaM5)+unNombreSinon0($CapaM6))>0){
				$visibleInterne="";
		}
		if((unNombreSinon0($eM)+unNombreSinon0($eM1)+unNombreSinon0($eM2)+unNombreSinon0($eM3)+unNombreSinon0($eM4)+unNombreSinon0($eM5)+unNombreSinon0($eM6))>0
		|| (unNombreSinon0($CapaeM)+unNombreSinon0($CapaeM1)+unNombreSinon0($CapaeM2)+unNombreSinon0($CapaeM3)+unNombreSinon0($CapaeM4)+unNombreSinon0($CapaeM5)+unNombreSinon0($CapaeM6))>0){
				$visibleExterne="";
		}

		$tableau .= '<tr id="interne'.$rowFamille['Id'].'" class="interneExterne" '.$visibleInterne.'>
			<td class="Libelle" style="border:1px solid black;" align="center">';
		$tableau .= $rowFamille['Libelle'];
		$tableau .= '</td>
			<td class="Libelle" style="border:1px solid black;" align="center">';
			if($_SESSION['Langue']=="EN"){$tableau .= 'Internal';}else{$tableau .= 'Interne';}
		$tableau .= '</td>';
		$tableau .= '<td style="border:1px solid black;';
			if(($M-$CapaM)<0){$tableau .= $couleurBleu;}elseif(($M-$CapaM)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;};
			$tableau .= '" align="center">';
			$tableau .= round($CapaM-$M,2);
		$tableau .= '</td>';
		$tableau .= '<td style="border:1px solid black;';
			if(($M1-$CapaM1)<0){$tableau .= $couleurBleu;}elseif(($M1-$CapaM1)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;}
			$tableau .= '" align="center">';
			$tableau .= round($CapaM1-$M1,2);
		$tableau .= '</td>';
		$tableau .= '<td style="border:1px solid black;';
			if(($M2-$CapaM2)<0){$tableau .= $couleurBleu;}elseif(($M2-$CapaM2)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;}
			$tableau .= '" align="center">';
			$tableau .= round($CapaM2-$M2,2);
		$tableau .= '</td>';
		$tableau .= '<td style="border:1px solid black;';
			if(($M3-$CapaM3)<0){$tableau .= $couleurBleu;}elseif(($M3-$CapaM3)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;}
			$tableau .= '" align="center">';
			$tableau .= round($CapaM3-$M3,2);
		$tableau .= '</td>
			<td style="border:1px solid black;';
			if(($M4-$CapaM4)<0){$tableau .= $couleurBleu;}elseif(($M4-$CapaM4)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;}
			$tableau .= '" align="center">';
			$tableau .= round($CapaM4-$M4,2);
		$tableau .= '</td>';
		$tableau .= ' <td style="border:1px solid black;';
			if(($M5-$CapaM5)<0){$tableau .= $couleurBleu;}elseif(($M5-$CapaM5)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;}
			$tableau .= '" align="center">';
			$tableau .= round($CapaM5-$M5,2);
		$tableau .= '</td>';
		
		$tableau .= '<td style="border:1px solid black;';
			if(($M6-$CapaM6)<0){$tableau .= $couleurBleu;}elseif(($M6-$CapaM6)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;}
			$tableau .= '" align="center">';
			$tableau .= round($CapaM6-$M6,2);
		$tableau .= '</td>';
		$tableau .= '</tr>';
		
		$tableau .= '<tr id="externe'.$rowFamille['Id'].'" class="interneExterne" '.$visibleExterne.'>
			<td class="Libelle" style="border:1px solid black;" align="center">';
			$tableau .= $rowFamille['Libelle'];
			$tableau .= '</td>
			<td class="Libelle" style="border:1px solid black;" align="center">';
				if($_SESSION['Langue']=="EN"){$tableau .= "External";}else{$tableau .= "Externe";}
			$tableau .= '</td>
			<td style="border:1px solid black;';
				if(($eM-$CapaeM)<0){$tableau .= $couleurBleu;}elseif(($eM-$CapaeM)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;}
				$tableau .= '" align="center">';
			$tableau .= round($CapaeM-$eM,2);
			$tableau .= '</td>
			<td style="border:1px solid black;';
				if(($eM1-$CapaeM1)<0){$tableau .= $couleurBleu;}elseif(($eM1-$CapaeM1)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;}
				$tableau .= '" align="center">';
			$tableau .= round($CapaeM1-$eM1,2);
			$tableau .='</td>
			<td style="border:1px solid black;';
				if(($eM2-$CapaeM2)<0){$tableau .= $couleurBleu;}elseif(($eM2-$CapaeM2)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;}
				$tableau .= '" align="center">';
			$tableau .=round($CapaeM2-$eM2,2);
			$tableau .='</td>
			<td style="border:1px solid black;';
				if(($eM3-$CapaeM3)<0){$tableau .= $couleurBleu;}elseif(($eM3-$CapaeM3)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;}
				$tableau .= '" align="center">';
			$tableau .= round($CapaeM3-$eM3,2);
			$tableau .='</td>
			<td style="border:1px solid black;';
				if(($eM4-$CapaeM4)<0){$tableau .= $couleurBleu;}elseif(($eM4-$CapaeM4)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;}
				$tableau .= '" align="center">';
			$tableau .=round($CapaeM4-$eM4,2);
			$tableau .='</td>
			<td style="border:1px solid black;';
				if(($eM5-$CapaeM5)<0){$tableau .= $couleurBleu;}elseif(($eM5-$CapaeM5)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;}
				$tableau .= '" align="center">';
			$tableau .=round($CapaeM5-$eM5,2);
			$tableau .='</td>
			<td style="border:1px solid black;';
				if(($eM6-$CapaeM6)<0){$tableau .= $couleurBleu;}elseif(($eM6-$CapaeM6)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;}
				$tableau .= '" align="center">';
			$tableau .=round($CapaeM6-$eM6,2);
			$tableau .='</td>
		</tr>';
	}
}
	
$tableau .= '<tr>
	<td class="Libelle" style="border:1px solid black;" align="center" colspan="2">';
		if($_SESSION['Langue']=="EN"){$tableau .= "Total";}else{$tableau .= "Total";}
	$tableau .= '</td>
	<td style="border:1px solid black;';
	if(($sommeM-$sommeCapaM)<0){$tableau .= $couleurBleu;}elseif(($sommeM-$sommeCapaM)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;}
	$tableau .= '" align="center">';
	$tableau .=$sommeCapaM-$sommeM;
	$tableau .= '</td>
	<td style="border:1px solid black;';
	if(($sommeM1-$sommeCapaM1)<0){$tableau .= $couleurBleu;}elseif(($sommeM1-$sommeCapaM1)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;}
	$tableau .= '" align="center">';
	$tableau .=$sommeCapaM1-$sommeM1;
	$tableau .= '</td>
	<td style="border:1px solid black;';
	if(($sommeM2-$sommeCapaM2)<0){$tableau .= $couleurBleu;}elseif(($sommeM2-$sommeCapaM2)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;}
	$tableau .= '" align="center">';
	$tableau .=$sommeCapaM2-$sommeM2;
	$tableau .= '</td>
	<td style="border:1px solid black;';
	if(($sommeM3-$sommeCapaM3)<0){$tableau .= $couleurBleu;}elseif(($sommeM3-$sommeCapaM3)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;}
	$tableau .= '" align="center">';
	$tableau .=$sommeCapaM3-$sommeM3;
	$tableau .= '</td>
	<td style="border:1px solid black;';
	if(($sommeM4-$sommeCapaM4)<0){$tableau .= $couleurBleu;}elseif(($sommeM4-$sommeCapaM4)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;}
	$tableau .= '" align="center">';
	$tableau .=$sommeCapaM4-$sommeM4;
	$tableau .= '</td>
	<td style="border:1px solid black;';
	if(($sommeM5-$sommeCapaM5)<0){$tableau .= $couleurBleu;}elseif(($sommeM5-$sommeCapaM5)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;}
	$tableau .= '" align="center">';
	$tableau .=$sommeCapaM5-$sommeM5;
	$tableau .= '</td>
	<td style="border:1px solid black;';
	if(($sommeM6-$sommeCapaM6)<0){$tableau .= $couleurBleu;}elseif(($sommeM6-$sommeCapaM6)>0){$tableau .= $couleurRouge;}else{$tableau .= $couleurVert;}
	$tableau .= '" align="center">';
	$tableau .=$sommeCapaM6-$sommeM6;
	$tableau .= '</td>
</tr>

</table>';

echo $tableau;
?>
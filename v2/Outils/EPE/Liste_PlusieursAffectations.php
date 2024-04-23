<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreExcel()
	{window.open("Export_PlusieursAffectations.php","PageExcel","status=no,menubar=no,width=90,height=40");}
</script>
<form id="formulaire" class="test" action="Liste_PlusieursAffectations.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#2e578e;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Personnes avec plusieurs affectations";}else{echo "People with multiple assignments";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td width="100%">
			<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td width="20%" class="Libelle">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?>
						<select style="width:100px;" name="plateforme" onchange="submit();">
						<?php
						if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
							$requetePlateforme="
								SELECT Id, Libelle
								FROM new_competences_plateforme
								WHERE Id IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
								ORDER BY Libelle ASC";
						}
						else{
							$requetePlateforme="
								SELECT Id, Libelle
								FROM new_competences_plateforme
								WHERE Id IN 
										(
											SELECT Id_Plateforme 
											FROM new_competences_personne_poste_plateforme
											WHERE Id_Personne=".$_SESSION['Id_Personne']." 
											AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
										)
								AND Id IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
								ORDER BY Libelle ASC";
						}
						$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
						$nbPlateforme=mysqli_num_rows($resultPlateforme);
						
						$Plateforme=$_SESSION['FiltreEPEAffectation_Plateforme'];
						if($_POST){$Plateforme=$_POST['plateforme'];}
						$_SESSION['FiltreEPEAffectation_Plateforme']=$Plateforme;	
						
						$arrayPlateforme=array();
						echo "<option name='0' value='0' Selected></option>";
						if ($nbPlateforme > 0)
						{
							$i=0;
							while($row=mysqli_fetch_array($resultPlateforme))
							{
								$selected="";
								if($Plateforme<>""){if($Plateforme==$row['Id']){$selected="selected";}}
								echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
								$arrayPlateforme[$i]=array("Id_Plateforme" => $row['Id'],"Plateforme" => $row['Libelle'],"NbRestant" => 0);
								$i++;
							}
						 }
						 ?>
						</select>
					</td>
					<td width="12%" class="Libelle">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?>
						<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
						<?php
						
						$requeteSite="
							SELECT Id, Libelle
							FROM new_competences_prestation
							WHERE Active=0
							AND Id_Plateforme=".$Plateforme."
							ORDER BY Libelle ASC";
						$resultPrestation=mysqli_query($bdd,$requeteSite);
						$nbPrestation=mysqli_num_rows($resultPrestation);
						
						$Prestation=$_SESSION['FiltreEPEAffectation_Prestation'];
						if($_POST){$Prestation=$_POST['prestations'];}
						$_SESSION['FiltreEPEAffectation_Prestation']=$Prestation;	
						
						echo "<option name='0' value='0' Selected></option>";
						if ($nbPrestation > 0)
						{
							while($row=mysqli_fetch_array($resultPrestation))
							{
								$selected="";
								if($Prestation<>""){if($Prestation==$row['Id']){$selected="selected";}}
								echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
							}
						 }
						 ?>
						</select>
					</td>
					<td width="15%" class="Libelle">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?>
						<select class="pole" style="width:100px;" name="pole" onchange="submit();">
						<?php
						$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
							FROM new_competences_pole
							LEFT JOIN new_competences_prestation
							ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
							WHERE Actif=0
							AND new_competences_pole.Id_Prestation=".$Prestation."
							ORDER BY new_competences_pole.Libelle ASC";
						$resultPole=mysqli_query($bdd,$requetePole);
						$nbPole=mysqli_num_rows($resultPole);
						
						$Pole=$_SESSION['FiltreEPEAffectation_Pole'];
						if($_POST){$Pole=$_POST['pole'];}
						$_SESSION['FiltreEPEAffectation_Pole']=$Pole;
						
						$Selected = "";
						echo "<option name='0' value='0' Selected></option>";
						if ($nbPole > 0)
						{
							while($row=mysqli_fetch_array($resultPole))
							{
								$selected="";
								if($Pole<>"")
								{if($Pole==$row['Id']){$selected="selected";}}
								echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
							}
						 }
						 ?>
						</select>
					</td>
					<?php
					$personne=$_SESSION['FiltreEPEAffectation_Personne'];
					if($_POST){$personne=$_POST['personne'];}
					$_SESSION['FiltreEPEAffectation_Personne']=$personne;
					?>
					<td valign="top" width="15%" class="Libelle">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?>
						<select id="personne" style="width:100px;" name="personne" onchange="submit();">
							<option value='0'></option>
							<?php

								$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
										CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
										FROM epe_personne_datebutoir
										LEFT JOIN new_rh_etatcivil
										ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne
										WHERE
											(
												SELECT COUNT(new_competences_personne_prestation.Id)
												FROM new_competences_personne_prestation
												LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
												WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
												AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
												AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
												AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)";
											if($Plateforme<>"0"){$requetePersonne.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) = ".$Plateforme." ";}
											if($Prestation<>"0"){$requetePersonne.="AND new_competences_personne_prestation.Id_Prestation = ".$Prestation." ";}
											if($Pole<>"0"){$requetePersonne.="AND new_competences_personne_prestation.Id_Pole = ".$Pole." ";}
							$requetePersonne.="
											)>0
								 ";
								$requetePersonne.="ORDER BY Personne ASC";
								$resultPersonne=mysqli_query($bdd,$requetePersonne);
								$NbPersonne=mysqli_num_rows($resultPersonne);
								
								$personne=$_SESSION['FiltreEPEAffectation_Personne'];
								if($_POST){$personne=$_POST['personne'];}
								$_SESSION['FiltreEPEAffectation_Personne']= $personne;
								
								while($rowPersonne=mysqli_fetch_array($resultPersonne))
								{
									echo "<option value='".$rowPersonne['Id']."'";
									if ($personne == $rowPersonne['Id']){echo " selected ";}
									echo ">".$rowPersonne['Personne']."</option>\n";
								}
							?>
						</select>
					</td>
					<?php
					$annee=$_SESSION['FiltreEPEAffectation_Annee'];
					if($_POST){$annee=$_POST['annee'];}
					if($annee==""){$annee=date("Y");}
					$_SESSION['FiltreEPEAffectation_Annee']=$annee;
					?>
					<td width="10%" class="Libelle">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Année :";}else{echo "Year :";} ?>
						<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
					</td>
					<td width="5%">
						<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
						<div id="filtrer"></div>
					</td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td style="font-size:15px;" class="Libelle">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Affectation :";}else{echo "Assignment  :";} ?>
						<select id="affectation" style="width:150px;" name="affectation" onchange="submit();">
						<?php
							$affectation=$_SESSION['FiltreEPEAffectation_SansAffectation'];
							if($_POST){$affectation=$_POST['affectation'];}
							$_SESSION['FiltreEPEAffectation_SansAffectation']=$affectation;
						?>
							<option value='0' <?php if($affectation==0){echo "selected";} ?>></option>
							<option value='1' <?php if($affectation==1){echo "selected";} ?>>Aucune affectation</option>
							<option value='2' <?php if($affectation==2){echo "selected";} ?>>Affecté</option>
						</select>
					</td>
					<td colspan="5" align="right">
							<a href="javascript:OuvreExcel()">
								<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
							</a>&nbsp;&nbsp;&nbsp;
					</td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<?php
		$requete2="SELECT DISTINCT new_rh_etatcivil.Id,CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
		MatriculeAAA,
		(SELECT COUNT(Id) FROM epe_personne_prestation WHERE Suppr=0 AND epe_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
		AND epe_personne_prestation.Annee=".$_SESSION['FiltreEPEAffectation_Annee']." AND epe_personne_prestation.Id_Manager=0
		AND epe_personne_prestation.Id_Prestation IN
		(SELECT new_competences_personne_prestation.Id_Prestation 
		FROM new_competences_personne_prestation
		WHERE new_competences_personne_prestation.Id_Personne=epe_personne_prestation.Id_Personne
		AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
		AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."'))
		) AS NbPresta
		";
		$requete="FROM new_rh_etatcivil
			RIGHT JOIN epe_personne_datebutoir 
			ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne 
			WHERE MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') 
			AND MetierPaie<>'' AND Cadre IN (0,1) ";

		//Vérifier si appartient à une prestation OPTEA ou compétence
		$requete.="AND (
			(
				SELECT COUNT(new_competences_personne_prestation.Id)
				FROM new_competences_personne_prestation
				LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
				WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
				AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
				AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
				AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
			)>1) 
			AND (
			(
				SELECT COUNT(new_competences_personne_prestation.Id)
				FROM new_competences_personne_prestation
				LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
				WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
				AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
				AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
				AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)";
			if($_SESSION['FiltreEPEAffectation_Plateforme']<>"0"){$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) = ".$_SESSION['FiltreEPEAffectation_Plateforme']." ";}
			if($_SESSION['FiltreEPEAffectation_Prestation']<>"0"){$requete.="AND new_competences_personne_prestation.Id_Prestation = ".$_SESSION['FiltreEPEAffectation_Prestation']." ";}
			if($_SESSION['FiltreEPEAffectation_Pole']<>"0"){$requete.="AND new_competences_personne_prestation.Id_Pole = ".$_SESSION['FiltreEPEAffectation_Pole']." ";}
			if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
				
			}
			else{
				$requete.="
				AND 
				((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN 
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
					)
				) ";
			}
		
		$requete.="
			)>0)
			
			";
		if($_SESSION['FiltreEPEAffectation_Personne']<>"0"){
			$requete.="AND new_rh_etatcivil.Id =".$_SESSION['FiltreEPEAffectation_Personne']." ";
		}
		$requete.="AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPEAffectation_Annee']." ";
		$requete.="AND IF((SELECT COUNT(Id)
		FROM epe_personne 
		WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEAffectation_Annee']." LIMIT 1)>0,
		(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
		FROM epe_personne 
		WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPEAffectation_Annee']." LIMIT 1),
		'A faire') IN ('A faire') ";
		if($_SESSION['FiltreEPEAffectation_SansAffectation']==1){
			$requete.="AND (
							SELECT COUNT(epe_personne_prestation.Id) 
							FROM epe_personne_prestation 
							WHERE epe_personne_prestation.Suppr=0 
							AND epe_personne_prestation.Id_Manager=0 
							AND epe_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
							AND epe_personne_prestation.Annee=".$_SESSION['FiltreEPEAffectation_Annee']."
							AND epe_personne_prestation.Id_Prestation IN
							(SELECT new_competences_personne_prestation.Id_Prestation 
							FROM new_competences_personne_prestation
							WHERE new_competences_personne_prestation.Id_Personne=epe_personne_prestation.Id_Personne
							AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
							AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."'))
							AND (SELECT COUNT(Id_Prestation) FROM new_competences_personne_prestation WHERE 
							new_competences_personne_prestation.Id_Prestation=epe_personne_prestation.Id_Prestation
							AND new_competences_personne_prestation.Id_Pole=epe_personne_prestation.Id_Pole
							AND new_competences_personne_prestation.Id_Personne=epe_personne_prestation.Id_Personne
							AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
							AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."'))>0
						)=0
						 ";
		}
		elseif($_SESSION['FiltreEPEAffectation_SansAffectation']==2){
			$requete.="AND (SELECT COUNT(epe_personne_prestation.Id) 
			FROM epe_personne_prestation 
			WHERE epe_personne_prestation.Suppr=0 
			AND epe_personne_prestation.Id_Manager=0 
			AND epe_personne_prestation.Id_Personne=new_rh_etatcivil.Id AND epe_personne_prestation.Annee=".$_SESSION['FiltreEPEAffectation_Annee']."
			AND epe_personne_prestation.Id_Prestation IN
			(SELECT new_competences_personne_prestation.Id_Prestation 
			FROM new_competences_personne_prestation
			WHERE new_competences_personne_prestation.Id_Personne=epe_personne_prestation.Id_Personne
			AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
			AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."'))
		
			AND (SELECT COUNT(Id_Prestation) FROM new_competences_personne_prestation 
							WHERE new_competences_personne_prestation.Id_Prestation=epe_personne_prestation.Id_Prestation
							AND new_competences_personne_prestation.Id_Pole=epe_personne_prestation.Id_Pole
							AND new_competences_personne_prestation.Id_Personne=epe_personne_prestation.Id_Personne
							AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
							AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."'))>0
			)>0 ";
		}
		$result=mysqli_query($bdd,$requete2.$requete);
		$nbResulta=mysqli_num_rows($result);
		$couleur="#FFFFFF";
		
		if($nbResulta>0){
			$nbRestant=0;
			while($row=mysqli_fetch_array($result))
			{
				if($row['NbPresta']==0){$nbRestant++;}
			}
			if($nbRestant>0){
		?>
		<tr>
			<td>
				<table class="TableCompetences" align="center" width="20%">
					<tr>
						<td class="Libelle2" align="center">
							Nombre d'affectations restantes : <?php echo $nbRestant; ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<?php
			}
		}
		?>
		<tr>
			<td>
				<table class="TableCompetences" align="center" width="80%">
					<tr>
						<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule";}else{echo "Registration number";} ?></td>
						<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "People";} ?></td>
						<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
						<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";} ?></td>
						<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Manager";}else{echo "Manager";} ?></td>
						<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Modifié par";}else{echo "Edited by";} ?></td>
					</tr>
		<?php	
				$result=mysqli_query($bdd,$requete2.$requete);
				if($nbResulta>0){
					while($row=mysqli_fetch_array($result))
					{
						if($couleur=="#FFFFFF"){$couleur="#c9d9ef";}
						else{$couleur="#FFFFFF";}
						
						if(isset($_POST['affect_'.$row['Id']])){
							$tab=explode("_",$_POST['affect_'.$row['Id']]);
							
							$req="UPDATE epe_personne_prestation SET Suppr=1 WHERE Id_Personne=".$row['Id']." AND Id_Manager=0 AND Annee=".$_SESSION['FiltreEPEAffectation_Annee']." ";
							$resultUpdt=mysqli_query($bdd,$req);
							
							$req="INSERT INTO epe_personne_prestation (Id_Personne,Id_Prestation,Id_Pole,Annee,Id_RH) VALUES (".$row['Id'].",".$tab[0].",".$tab[1].",".$_SESSION['FiltreEPEAffectation_Annee'].",".$_SESSION['Id_Personne'].") ";
							$resultinsert=mysqli_query($bdd,$req);
						}
						
						$req="SELECT (SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation, Id_Prestation,
							(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
							(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,Id_Pole,
							(SELECT CONCAT(Id_Prestation,'_',Id_Pole) FROM epe_personne_prestation WHERE epe_personne_prestation.Id_Personne=new_competences_personne_prestation.Id_Personne
							AND epe_personne_prestation.Id_Prestation=new_competences_personne_prestation.Id_Prestation
							AND epe_personne_prestation.Id_Pole=new_competences_personne_prestation.Id_Pole
							AND epe_personne_prestation.Annee=".$_SESSION['FiltreEPEAffectation_Annee']."
							AND epe_personne_prestation.Id_Manager=0
							AND epe_personne_prestation.Suppr=0) AS PrestaPole,
							(SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_RH) FROM epe_personne_prestation WHERE epe_personne_prestation.Id_Personne=new_competences_personne_prestation.Id_Personne
							AND epe_personne_prestation.Id_Prestation=new_competences_personne_prestation.Id_Prestation
							AND epe_personne_prestation.Id_Pole=new_competences_personne_prestation.Id_Pole
							AND epe_personne_prestation.Annee=".$_SESSION['FiltreEPEAffectation_Annee']."
							AND epe_personne_prestation.Id_Manager=0
							AND epe_personne_prestation.Suppr=0) AS RH
							FROM new_competences_personne_prestation
							WHERE Id_Personne=".$row['Id']." 
							AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
							AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."') ";

						$result2=mysqli_query($bdd,$req);
						$nb=mysqli_num_rows($result2);
						
						//Affectation à faire ?
						$req="SELECT Id FROM epe_personne_prestation WHERE Suppr=0 AND Id_Personne=".$row['Id']." AND epe_personne_prestation.Id_Manager=0 AND Annee=".$_SESSION['FiltreEPEAffectation_Annee']." 
						AND epe_personne_prestation.Id_Prestation IN
		(SELECT new_competences_personne_prestation.Id_Prestation 
		FROM new_competences_personne_prestation
		WHERE new_competences_personne_prestation.Id_Personne=epe_personne_prestation.Id_Personne
		AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
		AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."'))
						";
						$resultSelect=mysqli_query($bdd,$req);
						$nbSelect=mysqli_num_rows($resultSelect);
						if($nbSelect==0){
							$req="SELECT DISTINCT 
								(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
								(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme
								FROM new_competences_personne_prestation
								WHERE Id_Personne=".$row['Id']." 
								AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
								AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."') ";
							$resultPlat=mysqli_query($bdd,$req);
							$nbPlat=mysqli_num_rows($resultPlat);
							if($nbPlat>0){
								while($rowPlat=mysqli_fetch_array($resultPlat)){
									$i=0;
									foreach($arrayPlateforme as $Plateforme){
										if($Plateforme['Id_Plateforme']==$rowPlat['Id_Plateforme']){
											$arrayPlateforme[$i]['NbRestant']=$Plateforme['NbRestant']+1;
										}
										$i++;
									}
								}
							}
						}
						
						
						?>
							<tr >
								<td bgcolor="<?php echo $couleur;?>" rowspan="<?php echo $nb;?>"><?php echo stripslashes($row['MatriculeAAA']);?></td>
								<td bgcolor="<?php echo $couleur;?>" rowspan="<?php echo $nb;?>"><?php echo stripslashes($row['Personne']);?></td>
						<?php 
						
						if($nb>0){
							while($row2=mysqli_fetch_array($result2)){
								
							$Manager="";
							$Id_Prestation=$row2['Id_Prestation'];
							$Id_Pole=$row2['Id_Pole'];
							$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
									FROM new_competences_personne_poste_prestation 
									LEFT JOIN new_rh_etatcivil
									ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
									WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
									AND Id_Prestation=".$Id_Prestation."
									AND Id_Pole=".$Id_Pole."
									AND Id_Personne=".$row['Id']."
									ORDER BY Backup ";
							$ResultManager2=mysqli_query($bdd,$req);
							$NbManager2=mysqli_num_rows($ResultManager2);
							if($NbManager2>0){
								$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
									FROM new_competences_personne_poste_prestation 
									LEFT JOIN new_rh_etatcivil
									ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
									WHERE Id_Poste=".$IdPosteCoordinateurProjet."
									AND Id_Prestation=".$Id_Prestation."
									AND Id_Pole=".$Id_Pole."
									ORDER BY Backup ";
								$ResultManager=mysqli_query($bdd,$req);
								$NbManager=mysqli_num_rows($ResultManager);
								if($NbManager>0){
									$RowManager=mysqli_fetch_array($ResultManager);
									$Manager=$RowManager['Personne'];
								}
							}
							else{
								$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
									FROM new_competences_personne_poste_prestation 
									LEFT JOIN new_rh_etatcivil
									ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
									WHERE Id_Poste=".$IdPosteChefEquipe."
									AND Id_Prestation=".$Id_Prestation."
									AND Id_Pole=".$Id_Pole."
									AND Id_Personne=".$row['Id']."
									ORDER BY Backup ";
								$ResultManager2=mysqli_query($bdd,$req);
								$NbManager2=mysqli_num_rows($ResultManager2);
								if($NbManager2>0){
									$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
										FROM new_competences_personne_poste_prestation 
										LEFT JOIN new_rh_etatcivil
										ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
										WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
										AND Id_Prestation=".$Id_Prestation."
										AND Id_Pole=".$Id_Pole."
										ORDER BY Backup ";
									$ResultManager=mysqli_query($bdd,$req);
									$NbManager=mysqli_num_rows($ResultManager);
									if($NbManager>0){
										$RowManager=mysqli_fetch_array($ResultManager);
										$Manager=$RowManager['Personne'];
									}
								}
								else{
									$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
									FROM new_competences_personne_poste_prestation 
									LEFT JOIN new_rh_etatcivil
									ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
									WHERE Id_Poste=".$IdPosteChefEquipe."
									AND Id_Prestation=".$Id_Prestation."
									AND Id_Pole=".$Id_Pole."
									ORDER BY Backup ";
									$ResultManager=mysqli_query($bdd,$req);
									$NbManager=mysqli_num_rows($ResultManager);
									if($NbManager>0){
										$RowManager=mysqli_fetch_array($ResultManager);
										$Manager=$RowManager['Personne'];
									}
								}
							}
							
							
							$Pole="";
							If($row2['Pole']<>""){$Pole=" - ".$row2['Pole'];}
							
					?>
							<td bgcolor="<?php echo $couleur;?>" style='border-bottom:1px dotted #2e578e'>
							<input type="radio" name="affect_<?php echo $row['Id'];?>" <?php if($row2['PrestaPole']==$row2['Id_Prestation']."_".$row2['Id_Pole']){echo "checked";} ?> value="<?php echo $row2['Id_Prestation'];?>_<?php echo $row2['Id_Pole'];?>" onchange="submit()" />
							<?php echo $row2['Prestation'].$Pole;?>
							</td>
							<td bgcolor="<?php echo $couleur;?>" style='border-bottom:1px dotted #2e578e'><?php echo stripslashes($row2['Plateforme']);?></td>
							<td bgcolor="<?php echo $couleur;?>" style='border-bottom:1px dotted #2e578e'><?php echo stripslashes($Manager);?></td>
							<td bgcolor="<?php echo $couleur;?>" style='border-bottom:1px dotted #2e578e'><?php echo stripslashes($row2['RH']);?></td>
						</tr>
					<?php 
							}
						}
					?>
						
					<?php
					}
				}
					?>
				</table>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td>
				<table class="TableCompetences" align="center" width="30%">
					<tr>
						<td class="EnTeteTableauCompetences" width="20%"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";} ?></td>
						<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Nbr affectations restantes";}else{echo "Number of remaining assignments";} ?></td>
					</tr>
					<?php 
					$couleur="#FFFFFF";
					foreach($arrayPlateforme as $Plateforme){
						if($Plateforme['NbRestant']>0){
							if($couleur=="#FFFFFF"){$couleur="#c9d9ef";}
							else{$couleur="#FFFFFF";}
						?>
						<tr>
							<td bgcolor="<?php echo $couleur;?>" style='border-bottom:1px dotted #2e578e'><?php echo stripslashes($Plateforme['Plateforme']);?></td>
							<td bgcolor="<?php echo $couleur;?>" style='border-bottom:1px dotted #2e578e'><?php echo stripslashes($Plateforme['NbRestant']);?></td>
						</tr>
						<?php
						}
					}
					?>
				</table>
			</td>
		</tr>
	<tr><td height="4"></td></tr>
</table>
</form>
<?php
mysqli_close($bdd);					// Fermeture de la connexion

?>
	
</body>
</html>
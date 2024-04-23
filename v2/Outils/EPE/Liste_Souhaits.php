<?php
require("../../Menu.php");
?>
<script language="javascript">
	function AfficherM(id,i,i2){
		document.getElementById('Mobilite_'+id+'_'+i).style.display='';
		document.getElementById('newM_'+id+'_'+i).style.display='none';
		if(i<11){
			document.getElementById('newM_'+id+'_'+i2).style.display='';
		}
	}
	function AfficherTE(id,i,i2){
		document.getElementById('TypeEvolution_'+id+'_'+i).style.display='';
		document.getElementById('newE_'+id+'_'+i).style.display='none';
		if(i<11){
			document.getElementById('newE_'+id+'_'+i2).style.display='';
		}
	}
</script>
<?php
if(isset($_GET['Tri'])){
	$tab = array("MatriculeAAA","Personne","MetierPaie","Prestation","Pole","Plateforme","Manager","SouhaitEvolution","SouhaitMobilite","Etat");
	foreach($tab as $tri){
		if($_GET['Tri']==$tri){
			$_SESSION['TriEPESouhait_General']= str_replace($tri." ASC,","",$_SESSION['TriEPESouhait_General']);
			$_SESSION['TriEPESouhait_General']= str_replace($tri." DESC,","",$_SESSION['TriEPESouhait_General']);
			$_SESSION['TriEPESouhait_General']= str_replace($tri." ASC","",$_SESSION['TriEPESouhait_General']);
			$_SESSION['TriEPESouhait_General']= str_replace($tri." DESC","",$_SESSION['TriEPESouhait_General']);
			if($_SESSION['TriEPESouhait_'.$tri]==""){$_SESSION['TriEPESouhait_'.$tri]="ASC";$_SESSION['TriEPESouhait_General'].= $tri." ".$_SESSION['TriEPESouhait_'.$tri].",";}
			elseif($_SESSION['TriEPESouhait_'.$tri]=="ASC"){$_SESSION['TriEPESouhait_'.$tri]="DESC";$_SESSION['TriEPESouhait_General'].= $tri." ".$_SESSION['TriEPESouhait_'.$tri].",";}
			else{$_SESSION['TriEPESouhait_'.$tri]="";}
		}
	}
}
?>

<form id="formulaire" class="test" action="Liste_Souhaits.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#cad0cd;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Souhaits EPP à affecter";}else{echo "EPP wishes to assign";}
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
					<td style="font-size:15px;" width="15%" class="Libelle">
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
								WHERE (
										Id IN 
										(
											SELECT Id_Plateforme 
											FROM new_competences_personne_poste_plateforme
											WHERE Id_Personne=".$_SESSION['Id_Personne']." 
											AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
										)
									)
								AND Id IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
								ORDER BY Libelle ASC";
						}
						$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
						$nbPlateforme=mysqli_num_rows($resultPlateforme);
						
						$Plateforme=$_SESSION['FiltreSouhaitEPP_Plateforme'];
						if($_POST){$Plateforme=$_POST['plateforme'];}
						$_SESSION['FiltreSouhaitEPP_Plateforme']=$Plateforme;	
						
						echo "<option name='0' value='0' Selected></option>";
						if ($nbPlateforme > 0)
						{
							while($row=mysqli_fetch_array($resultPlateforme))
							{
								$selected="";
								if($Plateforme<>""){if($Plateforme==$row['Id']){$selected="selected";}}
								echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
							}
						 }
						 ?>
						</select>
					</td>
					<td style="font-size:15px;" width="15%" class="Libelle">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?>
						<select class="prestation" style="width:100px;" name="prestations" onchange="submit();">
						<?php
						if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
						$requeteSite="
							SELECT Id, Libelle
							FROM new_competences_prestation
							WHERE Active=0
							AND Id_Plateforme=".$Plateforme."
							ORDER BY Libelle ASC";
						}
						else{
							$requeteSite="
							SELECT Id, Libelle
							FROM new_competences_prestation
							WHERE (
									Id_Plateforme IN 
									(
										SELECT Id_Plateforme 
										FROM new_competences_personne_poste_plateforme
										WHERE Id_Personne=".$_SESSION['Id_Personne']." 
										AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
									)
								)
							AND Active=0
							AND Id_Plateforme=".$Plateforme."
							ORDER BY Libelle ASC";
						}
						$resultPrestation=mysqli_query($bdd,$requeteSite);
						$nbPrestation=mysqli_num_rows($resultPrestation);
						
						$Prestation=$_SESSION['FiltreSouhaitEPP_Prestation'];
						if($_POST){$Prestation=$_POST['prestations'];}
						$_SESSION['FiltreSouhaitEPP_Prestation']=$Prestation;	
						
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
					<td style="font-size:15px;" width="15%" class="Libelle">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?>
						<select class="pole" style="width:100px;" name="pole" onchange="submit();">
						<?php
						if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
							$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
								FROM new_competences_pole
								LEFT JOIN new_competences_prestation
								ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
								WHERE Actif=0
								AND new_competences_pole.Id_Prestation=".$Prestation."
								ORDER BY new_competences_pole.Libelle ASC";
							
						}
						else{
							$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
							FROM new_competences_pole
							LEFT JOIN new_competences_prestation
							ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
							WHERE (Id_Plateforme IN 
							(
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
							)
							)
							AND Actif=0
							AND new_competences_pole.Id_Prestation=".$Prestation."
							ORDER BY new_competences_pole.Libelle ASC";
						}
						$resultPole=mysqli_query($bdd,$requetePole);
						$nbPole=mysqli_num_rows($resultPole);
						
						$Pole=$_SESSION['FiltreSouhaitEPP_Pole'];
						if($_POST){$Pole=$_POST['pole'];}
						$_SESSION['FiltreSouhaitEPP_Pole']=$Pole;
						
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
					$personne=$_SESSION['FiltreSouhaitEPP_Personne'];
					if($_POST){$personne=$_POST['personne'];}
					$_SESSION['FiltreSouhaitEPP_Personne']=$personne;
					?>
					<td style="font-size:15px;" valign="top" width="15%" class="Libelle">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?>
						<select id="personne" style="width:150px;" name="personne" onchange="submit();">
							<option value='0'></option>
							<?php
								if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
								$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
										CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
										FROM epe_personne_datebutoir
										LEFT JOIN new_rh_etatcivil
										ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne
										WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
										OR 
											(SELECT COUNT(Id)
											FROM epe_personne 
											WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreSouhaitEPP_Annee'].")>0
										) 
										AND 
											(
												SELECT COUNT(new_competences_personne_prestation.Id)
												FROM new_competences_personne_prestation
												LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
												WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
												AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
												AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
												AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
												AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
												";
												if($Plateforme<>"0"){$requetePersonne.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) = ".$Plateforme." ";}
												if($Prestation<>"0"){$requetePersonne.="AND new_competences_personne_prestation.Id_Prestation = ".$Prestation." ";}
												if($Pole<>"0"){$requetePersonne.="AND new_competences_personne_prestation.Id_Pole = ".$Pole." ";}
								$requetePersonne.="
											)>0
								 ";
								}
								else{
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
												AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
												AND 
												((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN 
													(
														SELECT Id_Plateforme 
														FROM new_competences_personne_poste_plateforme
														WHERE Id_Personne=".$_SESSION['Id_Personne']." 
														AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
													)
												) ";
												if($Plateforme<>"0"){$requetePersonne.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) = ".$Plateforme." ";}
												if($Prestation<>"0"){$requetePersonne.="AND new_competences_personne_prestation.Id_Prestation = ".$Prestation." ";}
												if($Pole<>"0"){$requetePersonne.="AND new_competences_personne_prestation.Id_Pole = ".$Pole." ";}
								$requetePersonne.="
											)>0
								 ";
								}
								$requetePersonne.="ORDER BY Personne ASC";
								$resultPersonne=mysqli_query($bdd,$requetePersonne);
								$NbPersonne=mysqli_num_rows($resultPersonne);

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
					$annee=$_SESSION['FiltreSouhaitEPP_Annee'];
					if($_POST){$annee=$_POST['annee'];}
					if($annee==""){$annee=date("Y");}
					$_SESSION['FiltreSouhaitEPP_Annee']=$annee;
					?>
					<td style="font-size:15px;" width="10%" class="Libelle">
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
							$affectation=$_SESSION['FiltreSouhaitEPP_Affectation'];
							if($_POST){$affectation=$_POST['affectation'];}
							$_SESSION['FiltreSouhaitEPP_Affectation']=$affectation;
						?>
							<option value='0' <?php if($affectation==0){echo "selected";} ?>></option>
							<option value='1' <?php if($affectation==1){echo "selected";} ?>>Aucune affectation</option>
							<option value='2' <?php if($affectation==2){echo "selected";} ?>>Affecté</option>
						</select>
					</td>
					<td style="font-size:15px;" width="15%" class="Libelle">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Type d'évolution :";}else{echo "Evolution type :";} ?>
						<select style="width:200px;" name="typeEvolution" onchange="submit();">
						<?php
							$requete="SELECT Id, Libelle FROM epe_typeevolution WHERE Suppr=0 ORDER BY Libelle";
						$resultTE=mysqli_query($bdd,$requete);
						$nbTE=mysqli_num_rows($resultTE);
						
						$typeEvolution=$_SESSION['FiltreSouhaitEPP_SouhaitEvolution'];
						if($_POST){$typeEvolution=$_POST['typeEvolution'];}
						$_SESSION['FiltreSouhaitEPP_SouhaitEvolution']=$typeEvolution;	
						
						echo "<option name='0' value='0' Selected></option>";
						if ($nbTE > 0)
						{
							while($row=mysqli_fetch_array($resultTE))
							{
								$selected="";
								if($typeEvolution<>""){if($typeEvolution==$row['Id']){$selected="selected";}}
								echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
							}
						 }
						 ?>
						</select>
					</td>
					<td style="font-size:15px;" width="15%" class="Libelle">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Mobilité :";}else{echo "Mobility :";} ?>
						<select style="width:100px;" name="mobilite" onchange="submit();">
						<?php
							$requete="SELECT Id, Libelle FROM epe_mobilite WHERE Suppr=0 ORDER BY Libelle";
						$resultM=mysqli_query($bdd,$requete);
						$nbM=mysqli_num_rows($resultM);
						
						$mobilite=$_SESSION['FiltreSouhaitEPP_SouhaitMobilite'];
						if($_POST){$mobilite=$_POST['mobilite'];}
						$_SESSION['FiltreSouhaitEPP_SouhaitMobilite']=$mobilite;	
						
						echo "<option name='0' value='0' Selected></option>";
						if ($nbM > 0)
						{
							while($row=mysqli_fetch_array($resultM))
							{
								$selected="";
								if($mobilite<>""){if($mobilite==$row['Id']){$selected="selected";}}
								echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
							}
						 }
						 ?>
						</select>
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
		$requete2="SELECT DISTINCT epe_personne.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,
			MetierPaie,
			(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
			(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,
			(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
			(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur) AS Manager,
			SouhaitEvolution,SouhaitEvolutionON,SouhaitMobilite,SouhaitMobiliteON,PasEvolutionEPP,PasMobiliteEPP,
			IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager'))) AS Etat
			";
		$requete="FROM new_rh_etatcivil
			RIGHT JOIN epe_personne 
			ON new_rh_etatcivil.Id=epe_personne.Id_Personne 
			WHERE Suppr=0 AND epe_personne.Type='EPP' 
			AND ((SouhaitEvolutionON=1 AND SouhaitEvolution<>'') OR (SouhaitMobiliteON=1 AND SouhaitMobilite<>''))
			AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreSouhaitEPP_Annee']." 
			AND IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager'))) IN ('Signature salarié','Signature manager','Réalisé') ";
		if($_SESSION['FiltreSouhaitEPP_Personne']<>"0"){$requete.="AND epe_personne.Id_Personne = ".$_SESSION['FiltreSouhaitEPP_Personne']." ";}
		if(DroitsFormation1Plateforme(17,array($IdPosteResponsableRecrutement,$IdPosteRecrutement,$IdPosteAssistantRH,$IdPosteResponsableRH))){
			//Vérifier si appartient à une prestation OPTEA ou compétence
			if($_SESSION['FiltreSouhaitEPP_Plateforme']<>"0"){$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=epe_personne.Id_Prestation) = ".$_SESSION['FiltreSouhaitEPP_Plateforme']." ";}
			if($_SESSION['FiltreSouhaitEPP_Prestation']<>"0"){$requete.="AND epe_personne.Id_Prestation = ".$_SESSION['FiltreSouhaitEPP_Prestation']." ";}
			if($_SESSION['FiltreSouhaitEPP_Pole']<>"0"){$requete.="AND epe_personne.Id_Pole = ".$_SESSION['FiltreSouhaitEPP_Pole']." ";}
		}
		else{
			//Vérifier si appartient à une prestation OPTEA ou compétence
		$requete.="
			";
			if($_SESSION['FiltreSouhaitEPP_Plateforme']<>"0"){$requete.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=epe_personne.Id_Prestation) = ".$_SESSION['FiltreSouhaitEPP_Plateforme']." ";}
			if($_SESSION['FiltreSouhaitEPP_Prestation']<>"0"){$requete.="AND epe_personne.Id_Prestation = ".$_SESSION['FiltreSouhaitEPP_Prestation']." ";}
			if($_SESSION['FiltreSouhaitEPP_Pole']<>"0"){$requete.="AND epe_personne.Id_Pole = ".$_SESSION['FiltreSouhaitEPP_Pole']." ";}
			$requete.="
				AND 
				((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=epe_personne.Id_Prestation) IN 
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.")
					)
				)
			";
		}
		if($_SESSION['FiltreSouhaitEPP_Affectation']==1){
			$requete.="AND ((SouhaitEvolutionON=1 AND SouhaitEvolution<>'' AND (PasEvolutionEPP=0 AND (SELECT COUNT(Id) FROM epe_personne_souhaitevolution WHERE Id_EPE=epe_personne.Id)=0)) OR (SouhaitMobiliteON=1 AND SouhaitMobilite<>'' AND (PasMobiliteEPP=0 AND (SELECT COUNT(Id) FROM epe_personne_souhaitmobilite WHERE Id_EPE=epe_personne.Id)=0))) ";
		}
		elseif($_SESSION['FiltreSouhaitEPP_Affectation']==2){
			$requete.="AND (
						(SouhaitEvolutionON=1 AND SouhaitEvolution<>'' AND (PasEvolutionEPP=1 OR (SELECT COUNT(Id) FROM epe_personne_souhaitevolution WHERE Id_EPE=epe_personne.Id)>0) AND SouhaitMobiliteON=0) 
						OR (SouhaitMobiliteON=1 AND SouhaitMobilite<>'' AND (PasMobiliteEPP=1 OR (SELECT COUNT(Id) FROM epe_personne_souhaitmobilite WHERE Id_EPE=epe_personne.Id)>0) AND SouhaitEvolutionON=0)
						OR (SouhaitEvolutionON=1 AND SouhaitEvolution<>'' AND (PasEvolutionEPP=1 OR (SELECT COUNT(Id) FROM epe_personne_souhaitevolution WHERE Id_EPE=epe_personne.Id)>0) AND SouhaitMobiliteON=1 AND SouhaitMobilite<>'' AND (PasMobiliteEPP=1 OR (SELECT COUNT(Id) FROM epe_personne_souhaitmobilite WHERE Id_EPE=epe_personne.Id)>0)) 
						) ";
		}
		if($_SESSION['FiltreSouhaitEPP_SouhaitEvolution']<>"0"){
			$requete.=" AND (SELECT COUNT(Id) FROM epe_personne_souhaitevolution WHERE Id_EPE=epe_personne.Id AND Id_SouhaitEvolution=".$_SESSION['FiltreSouhaitEPP_SouhaitEvolution']." )>0 ";
		}
		if($_SESSION['FiltreSouhaitEPP_SouhaitMobilite']<>"0"){
			$requete.=" AND (SELECT COUNT(Id) FROM epe_personne_souhaitmobilite WHERE Id_EPE=epe_personne.Id AND Id_SouhaitMobilite=".$_SESSION['FiltreSouhaitEPP_SouhaitMobilite']." )>0 ";
		}
		
		$requeteOrder="";
		if($_SESSION['TriEPESouhait_General']<>""){
			$requeteOrder=" ORDER BY ".substr($_SESSION['TriEPESouhait_General'],0,-1);
		}

		$result=mysqli_query($bdd,$requete2.$requete.$requeteOrder);
		$nbResulta=mysqli_num_rows($result);
		
		$couleur="#FFFFFF";

		?>
		<tr>
			<td style="width:100%;" valign="top" align="center">
				<table class="TableCompetences" align="center" width="100%">
					<tr>
						<td style="font-size:15px;" class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Souhaits.php?Tri=MatriculeAAA"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule";}else{echo "Registration number";} ?><?php if($_SESSION['TriEPESouhait_MatriculeAAA']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriEPESouhait_MatriculeAAA']=="ASC"){echo "&darr;";}?></a></td>
						<td style="font-size:15px;" class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Souhaits.php?Tri=Personne"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "People";} ?><?php if($_SESSION['TriEPESouhait_Personne']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriEPESouhait_Personne']=="ASC"){echo "&darr;";}?></a></td>
						<td style="font-size:15px;" class="EnTeteTableauCompetences" width="10%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Souhaits.php?Tri=MetierPaie"><?php if($_SESSION["Langue"]=="FR"){echo "Métier";}else{echo "Job";} ?><?php if($_SESSION['TriEPESouhait_MetierPaie']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriEPESouhait_MetierPaie']=="ASC"){echo "&darr;";}?></a></td>
						<td style="font-size:15px;" class="EnTeteTableauCompetences" width="6%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Souhaits.php?Tri=Prestation"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?><?php if($_SESSION['TriEPESouhait_Prestation']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriEPESouhait_Prestation']=="ASC"){echo "&darr;";}?></a></td>
						<td style="font-size:15px;" class="EnTeteTableauCompetences" width="8%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Souhaits.php?Tri=Plateforme"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";} ?><?php if($_SESSION['TriEPESouhait_Plateforme']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriEPESouhait_Plateforme']=="ASC"){echo "&darr;";}?></a></td>
						<td style="font-size:15px;" class="EnTeteTableauCompetences" width="12%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Souhaits.php?Tri=Manager"><?php if($_SESSION["Langue"]=="FR"){echo "Responsable";}else{echo "Responsible";} ?><?php if($_SESSION['TriEPESouhait_Manager']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriEPESouhait_Manager']=="ASC"){echo "&darr;";}?></a></td>
						<td style="font-size:15px;" class="EnTeteTableauCompetences" width="5%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Souhaits.php?Tri=Etat"><?php if($_SESSION["Langue"]=="FR"){echo "Etat";}else{echo "State";} ?><?php if($_SESSION['TriEPESouhait_Etat']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriEPESouhait_Etat']=="ASC"){echo "&darr;";}?></a></td>
						<td style="font-size:15px;" class="EnTeteTableauCompetences" width="13%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Souhaits.php?Tri=SouhaitEvolution"><?php if($_SESSION["Langue"]=="FR"){echo "Souhait évolution";}else{echo "Wish evolution";} ?><?php if($_SESSION['TriEPESouhait_SouhaitEvolution']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriEPESouhait_SouhaitEvolution']=="ASC"){echo "&darr;";}?></a></td>
						<td style="font-size:15px;" class="EnTeteTableauCompetences" width="10%" align="center">
							<?php if($_SESSION["Langue"]=="FR"){echo "Affectation type d'évolution";}else{echo "Standard assignment of evolution";} ?>
							<br><input class="Bouton" name="BtnTypeEvolution" size="10" type="submit" value="Valider">
						</td>
						<td style="font-size:15px;" class="EnTeteTableauCompetences" width="15%"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="Liste_Souhaits.php?Tri=SouhaitMobilite"><?php if($_SESSION["Langue"]=="FR"){echo "Souhait mobilité";}else{echo "Wish mobility";} ?><?php if($_SESSION['TriEPESouhait_SouhaitMobilite']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriEPESouhait_SouhaitMobilite']=="ASC"){echo "&darr;";}?></a></td>
						<td style="font-size:15px;" class="EnTeteTableauCompetences" width="8%" align="center">
							<?php if($_SESSION["Langue"]=="FR"){echo "Affectation mobilité";}else{echo "Mobility assignment";} ?>
							<br><input class="Bouton" name="BtnMobilite" size="10" type="submit" value="Valider">
						</td>
						
					</tr>
				</table>
				<div style="width:100%;height:400px;overflow:auto;">
				<table class="TableCompetences" align="center" width="100%">
		<?php	
				if($nbResulta>0){
					while($row=mysqli_fetch_array($result))
					{
						if(isset($_POST['BtnTypeEvolution']) || isset($_POST['BtnMobilite'])){
							$req="DELETE FROM epe_personne_souhaitevolution WHERE Id_EPE=".$row['Id'];
							$resultDel=mysqli_query($bdd,$req);
							
							$req="DELETE FROM epe_personne_souhaitmobilite WHERE Id_EPE=".$row['Id'];
							$resultDel=mysqli_query($bdd,$req);
							
							if(isset($_POST['PasEvolution_'.$row['Id']])){$req="UPDATE epe_personne SET PasEvolutionEPP=1 WHERE Id=".$row['Id']." ";}
							else{$req="UPDATE epe_personne SET PasEvolutionEPP=0 WHERE Id=".$row['Id']." ";}
							$resultUpd=mysqli_query($bdd,$req);
							if(isset($_POST['PasMobilite_'.$row['Id']])){$req="UPDATE epe_personne SET PasMobiliteEPP=1 WHERE Id=".$row['Id']." ";}
							else{$req="UPDATE epe_personne SET PasMobiliteEPP=0 WHERE Id=".$row['Id']." ";}
							$resultUpd=mysqli_query($bdd,$req);
							
							for($i=0;$i<11;$i++){
								if(isset($_POST['TypeEvolution_'.$row['Id'].'_'.$i])){
									if($_POST['TypeEvolution_'.$row['Id'].'_'.$i]<>0){
										$req="INSERT INTO epe_personne_souhaitevolution (Id_EPE,Id_SouhaitEvolution) VALUE (".$row['Id'].",".$_POST['TypeEvolution_'.$row['Id'].'_'.$i].")";
										$resultUpd=mysqli_query($bdd,$req);
									}
								}

								if(isset($_POST['Mobilite_'.$row['Id'].'_'.$i])){
									if($_POST['Mobilite_'.$row['Id'].'_'.$i]<>0){
										$req="INSERT INTO epe_personne_souhaitmobilite (Id_EPE,Id_SouhaitMobilite) VALUE (".$row['Id'].",".$_POST['Mobilite_'.$row['Id'].'_'.$i].")";
										$resultUpd=mysqli_query($bdd,$req);
									}
								}
							}
						}
					}
				}
				$result=mysqli_query($bdd,$requete2.$requete.$requeteOrder);
				if($nbResulta>0){
					while($row=mysqli_fetch_array($result))
					{
						if($couleur=="#FFFFFF"){$couleur="#c9d9ef";}
						else{$couleur="#FFFFFF";}
					?>
						<tr >
							<td bgcolor="<?php echo $couleur;?>" width="8%"style="font-size:15px;"><?php echo stripslashes($row['MatriculeAAA']);?></td>
							<td bgcolor="<?php echo $couleur;?>" width="10%"style="font-size:15px;"><?php echo stripslashes($row['Personne']);?></td>
							<td bgcolor="<?php echo $couleur;?>" width="10%"style="font-size:15px;"><?php echo stripslashes($row['MetierPaie']);?></td>
							<td bgcolor="<?php echo $couleur;?>" width="6%" style="font-size:15px;"><?php echo stripslashes($row['Prestation']);?></td>
							<td bgcolor="<?php echo $couleur;?>" width="8%" style="font-size:15px;"><?php echo stripslashes($row['Plateforme']);?></td>
							<td bgcolor="<?php echo $couleur;?>" width="12%" style="font-size:15px;"><?php echo stripslashes($row['Manager']);?></td>
							<td bgcolor="<?php echo $couleur;?>" width="5%" style="font-size:15px;"><?php echo stripslashes($row['Etat']);?></td>
							<td bgcolor="<?php echo $couleur;?>" width="13%" style="font-size:15px;"><?php 
							if($row['SouhaitEvolutionON']==1){
								echo stripslashes($row['SouhaitEvolution']);
							}
							?>
							</td>
							<td bgcolor="<?php echo $couleur;?>" width="10%" style="font-size:15px;">
							<?php 
							if($row['SouhaitEvolutionON']==1 && $row['SouhaitEvolution']<>""){
								$checked="";
								if($row['PasEvolutionEPP']==1){$checked="checked";}
								echo "<input name='PasEvolution_".$row['Id']."' ".$checked." type='checkbox'>";
								if($_SESSION["Langue"]=="FR"){echo "Pas de souhait";}else{echo "No wish";}
								
								$req="SELECT Id, Libelle FROM epe_typeevolution WHERE Suppr=0 ORDER BY Libelle";
								$resultTO=mysqli_query($bdd,$req);
								$nb=mysqli_num_rows($resultTO);
								
								$reqSouhait="SELECT DISTINCT Id_EPE, Id_SouhaitEvolution FROM epe_personne_souhaitevolution WHERE Id_EPE=".$row['Id']." ";
								$resultSouhait=mysqli_query($bdd,$reqSouhait);
								$nbSouhait=mysqli_num_rows($resultSouhait);
								
								$dejaPlus=0;
								for($i=0;$i<11;$i++){
									$rowSouhait=mysqli_fetch_array($resultSouhait);
									$visible="display:none;";
									if($rowSouhait['Id_SouhaitEvolution']>0 || $i==0){
										$visible="";
									}
									echo "<select style='width:120px;".$visible."' id='TypeEvolution_".$row['Id']."_".$i."' name='TypeEvolution_".$row['Id']."_".$i."' >";
									echo "<option name='0' value='0' selected></option>";
									if ($nb > 0)
									{
										$resultTO=mysqli_query($bdd,$req);
										while($rowTO=mysqli_fetch_array($resultTO)){
											$selected="";
											if($rowSouhait['Id_SouhaitEvolution']==$rowTO['Id']){$selected="selected";}
											echo "<option value='".$rowTO['Id']."' ".$selected.">".stripslashes($rowTO['Libelle'])."</option>\n";
										}
									 }
									 echo "</select>";
									if($rowSouhait['Id_SouhaitEvolution']>0){
										
									}
									else{
										if($i>0 && $i<11){
											$estvisible="style='display:none;'";
											if($dejaPlus==0){$estvisible="";}
											echo "<input class='Bouton' ".$estvisible." type='button' name='newE_".$row['Id']."_".$i."' id='newE_".$row['Id']."_".$i."' onclick=\"AfficherTE('".$row['Id']."','".$i."','".($i+1)."')\" value='+' />";
											$dejaPlus=1;
										}
									}
								}
							}	
							?>
							</td>
							<td bgcolor="<?php echo $couleur;?>" width="15%" style="font-size:15px;"><?php 
							if($row['SouhaitMobiliteON']==1){
								echo stripslashes($row['SouhaitMobilite']);
							}
							?>
							</td>
							<td bgcolor="<?php echo $couleur;?>" width="8%" style="font-size:15px;">
							<?php 
							if($row['SouhaitMobiliteON']==1 && $row['SouhaitMobilite']<>""){
								$checked="";
								if($row['PasMobiliteEPP']==1){$checked="checked";}
								echo "<input name='PasMobilite_".$row['Id']."' ".$checked." type='checkbox'>";
								if($_SESSION["Langue"]=="FR"){echo "Pas de souhait";}else{echo "No wish";}
								
								$req="SELECT Id, Libelle FROM epe_mobilite WHERE Suppr=0 ORDER BY Libelle";
								$resultM=mysqli_query($bdd,$req);
								$nb=mysqli_num_rows($resultM);
								
								$reqSouhait="SELECT DISTINCT Id_EPE, Id_SouhaitMobilite FROM epe_personne_souhaitmobilite WHERE Id_EPE=".$row['Id']." ";
								$resultSouhait=mysqli_query($bdd,$reqSouhait);
								$nbSouhait=mysqli_num_rows($resultSouhait);
								
								$dejaPlus=0;
								for($i=0;$i<11;$i++){
									$rowSouhait=mysqli_fetch_array($resultSouhait);
									$visible="display:none;";
									if($rowSouhait['Id_SouhaitMobilite']>0 || $i==0){
										$visible="";
									}
									
									echo "<select style='width:90px;".$visible."' id='Mobilite_".$row['Id']."_".$i."' name='Mobilite_".$row['Id']."_".$i."' >";
									echo "<option name='0' value='0' selected></option>";
									if ($nb > 0)
									{
										$resultM=mysqli_query($bdd,$req);
										while($rowM=mysqli_fetch_array($resultM))
										{
											$selected="";
											if($rowSouhait['Id_SouhaitMobilite']==$rowM['Id']){$selected="selected";}
											echo "<option value='".$rowM['Id']."' ".$selected.">".stripslashes($rowM['Libelle'])."</option>\n";
										}
									 }
									 echo "</select>";
									 if($rowSouhait['Id_SouhaitMobilite']>0){
										
									}
									else{
										if($i>0 && $i<11){
											$estvisible="style='display:none;'";
											if($dejaPlus==0){$estvisible="";}
											echo "<input class='Bouton' ".$estvisible." type='button' name='newM_".$row['Id']."_".$i."' id='newM_".$row['Id']."_".$i."' onclick=\"AfficherM('".$row['Id']."','".$i."','".($i+1)."')\" value='+' />";
											$dejaPlus=1;
										}
									}
								}
							}	
							?>
							</td>
						<tr>
					<?php 
					}
				}
					?>
				</table>
				</div>
			</td>
		</tr>
	<tr><td height="150"></td></tr>
</table>
</form>
<?php
mysqli_close($bdd);					// Fermeture de la connexion

?>
	
</body>
</html>
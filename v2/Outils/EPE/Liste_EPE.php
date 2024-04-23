<?php
require("../../Menu.php");
?>

<script language="javascript">
	function OuvreFenetreEPE(Id,Cadre,Mode,Id_Manager)
	{
		if(Mode=="A"){var w= window.open("Ajout_EPE.php?Id="+Id+"&Cadre="+Cadre+"&Id_Manager="+Id_Manager,"PageEPE","status=no,menubar=no,width=1500,scrollbars=1,,height=800");}
		else{var w= window.open("Modif_EPE.php?Id="+Id+"&Cadre="+Cadre+"&Mode="+Mode+"&Id_Manager="+Id_Manager,"PageEPE","status=no,menubar=no,width=1500,scrollbars=1,,height=800");}
		w.focus();
	}
	function Plannifier(Id_Personne)
	{
		var w= window.open("Plannifier.php?Id_Personne="+Id_Personne,"PagePlannif","status=no,menubar=no,width=500,scrollbars=1,,height=400");
		w.focus();
	}
	function OuvreFenetreEPP(Id,Mode,Id_Manager)
	{
		if(Mode=="A"){var w= window.open("Ajout_EPP.php?Id="+Id+"&Id_Manager="+Id_Manager,"PageEPP","status=no,menubar=no,width=1200,scrollbars=1,,height=800");}
		else{var w= window.open("Modif_EPP.php?Id="+Id+"&Mode="+Mode+"&Id_Manager="+Id_Manager,"PageEPP","status=no,menubar=no,width=1200,scrollbars=1,,height=800");}
		w.focus();
	}
	function OuvreFenetreEPPBilan(Id,Mode,Id_Manager)
	{
		if(Mode=="A"){var w= window.open("Ajout_EPPBilan.php?Id="+Id+"&Id_Manager="+Id_Manager,"PageEPPB","status=no,menubar=n,scrollbars=1,o,width=1200,height=800");}
		else{var w= window.open("Modif_EPPBilan.php?Id="+Id+"&Mode="+Mode+"&Id_Manager="+Id_Manager,"PageEPPB","status=no,menubar=no,scrollbars=1,,width=1200,height=800");}
		w.focus();
	}
	function EPE_PDF(Id,Cadre)
		{window.open("EPE_PDF.php?Id="+Id+"&Cadre="+Cadre,"PagePDF","status=no,menubar=no,scrollbars=1,width=90,height=40");}
	function EPP_PDF(Id)
		{window.open("EPP_PDF.php?Id="+Id,"PagePDF","status=no,menubar=no,scrollbars=1,width=90,height=40");}
	function EPPBilan_PDF(Id)
		{window.open("EPPBilan_PDF.php?Id="+Id,"PagePDF","status=no,menubar=no,scrollbars=1,width=90,height=40");}
	function EPE_Excel(Id,Cadre,Id_Manager)
		{window.open("EPEAFaire_Excel.php?Id="+Id+"&Cadre="+Cadre+"&Id_Manager="+Id_Manager,"PageExcel","status=no,menubar=no,scrollbars=1,width=90,height=40");}
	function EPP_Excel(Id,Id_Manager)
		{window.open("EPPAFaire_Excel.php?Id="+Id+"&Id_Manager="+Id_Manager,"PageExcel","status=no,menubar=no,scrollbars=1,width=90,height=40");}
	function EPPBilan_Excel(Id,Id_Manager)
		{window.open("EPPBilanAFaire_Excel.php?Id="+Id+"&Id_Manager="+Id_Manager,"PageExcel","status=no,menubar=no,scrollbars=1,width=90,height=40");}
	function EPEB_Excel(Id,Cadre,Id_Manager)
		{window.open("EPE_Excel.php?Id="+Id+"&Cadre="+Cadre+"&Id_Manager="+Id_Manager,"PageExcel","status=no,menubar=no,scrollbars=1,width=90,height=40");}
	function EPPB_Excel(Id,Id_Manager)
		{window.open("EPP_Excel.php?Id="+Id+"&Id_Manager="+Id_Manager,"PageExcel","status=no,menubar=no,scrollbars=1,width=90,height=40");}
	function EPPBilanB_Excel(Id,Id_Manager)
		{window.open("EPPBilan_Excel.php?Id="+Id+"&Id_Manager="+Id_Manager,"PageExcel","status=no,menubar=no,scrollbars=1,width=90,height=40");}
	function SelectionnerToutPriseEC()
	{
		var elements = document.getElementsByClassName("checkEC");
		if (formulaire.selectAllPriseEC.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
		}
	}
	function ValiderCheckEC()
	{
		var elements = document.getElementsByClassName("checkEC");
		Id="";
		ref="";
		for(var i=0, l=elements.length; i<l; i++)
		{
			if(elements[i].checked == true){Id+=elements[i].name+";";}
		}				
	}
	function OuvreExcel()
	{window.open("Export_Excel.php","PageExcel","status=no,menubar=no,width=90,height=40");}
	function OuvreEPEExcel()
	{window.open("Export_EPEExcel.php","PageExcel","status=no,menubar=no,width=90,height=40");}
	function OuvreEPPExcel()
	{window.open("Export_EPPExcel.php","PageExcel","status=no,menubar=no,width=90,height=40");}
	function OuvreEPPBilanExcel()
	{window.open("Export_EPPBilanExcel.php","PageExcel","status=no,menubar=no,width=90,height=40");}
</script>

<?php

if($_POST)
{
	if(isset($_POST['PrendreEnCompte']))
	{
		echo "<script>ValiderCheckEC()</script>";
		//Parcourir les checklists cochés
		foreach($_POST['PriseEnCompte'] as $valeur)
		{
            $req="UPDATE epe_personne SET LectureRH=1, Id_LectureRH=".$_SESSION['Id_Personne'].", DateLectureRH='".date('Y-m-d')."' WHERE Id=".$valeur;
            $resultTraite=mysqli_query($bdd,$req);
		}
	}
}
?>

<form id="formulaire" class="test" action="Liste_EPE.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#fa2036;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/EPE/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "EPE / EPP";}else{echo "EPE / EPP";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr >
		<td colspan="2" style="width:70px;height:30px;border-spacing:0;text-align:center;valign:top;font-weight:bold;font-size:18px;">
			La campagne EIP (Entretien Individuel de Progrès) anciennement EPE/EPP se fera désormais sur Workday. <br>
			Rendez-vous sur Workday pour la campagne EIP 2024 ! 
		</td>
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
											AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteResponsablePlateforme.")
										)
										OR 
										Id IN 
										(
											SELECT Id_Plateforme 
											FROM new_competences_personne_poste_prestation 
											LEFT JOIN new_competences_prestation
											ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
											WHERE Id_Personne=".$_SESSION["Id_Personne"]."
											AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
										)
									)
								AND Id IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
								ORDER BY Libelle ASC";
						}
						$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
						$nbPlateforme=mysqli_num_rows($resultPlateforme);
						
						$Plateforme=$_SESSION['FiltreEPE_Plateforme'];
						if($_POST){$Plateforme=$_POST['plateforme'];}
						$_SESSION['FiltreEPE_Plateforme']=$Plateforme;	
						
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
										AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteResponsablePlateforme.")
									)
									OR 
									Id IN 
									(
										SELECT Id_Prestation 
										FROM new_competences_personne_poste_prestation 
										WHERE Id_Personne=".$_SESSION["Id_Personne"]."
										AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
									)
								)
							AND Active=0
							AND Id_Plateforme=".$Plateforme."
							ORDER BY Libelle ASC";
						}
						$resultPrestation=mysqli_query($bdd,$requeteSite);
						$nbPrestation=mysqli_num_rows($resultPrestation);
						
						$Prestation=$_SESSION['FiltreEPE_Prestation'];
						if($_POST){$Prestation=$_POST['prestations'];}
						$_SESSION['FiltreEPE_Prestation']=$Prestation;	
						
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
								AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteResponsablePlateforme.")
							)
							OR new_competences_pole.Id IN 
							(SELECT Id_Pole 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
							))
							AND Actif=0
							AND new_competences_pole.Id_Prestation=".$Prestation."
							ORDER BY new_competences_pole.Libelle ASC";
						}
						$resultPole=mysqli_query($bdd,$requetePole);
						$nbPole=mysqli_num_rows($resultPole);
						
						$Pole=$_SESSION['FiltreEPE_Pole'];
						if($_POST){$Pole=$_POST['pole'];}
						$_SESSION['FiltreEPE_Pole']=$Pole;
						
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
					$annee=$_SESSION['FiltreEPE_Annee'];
					if($_POST){$annee=$_POST['annee'];}
					if($annee==""){$annee=date("Y");}
					$_SESSION['FiltreEPE_Annee']=$annee;
					?>
					<?php
					$personne=$_SESSION['FiltreEPE_Personne'];
					if($_POST){$personne=$_POST['personne'];}
					$_SESSION['FiltreEPE_Personne']=$personne;
					
					$dateDebut=date($annee.'-01-01');
					$dateFin=date($annee.'-12-31');
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
											WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0
										)
										AND 
											(
												SELECT COUNT(new_competences_personne_prestation.Id)
												FROM new_competences_personne_prestation
												LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
												WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
												AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
												AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
												AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
												AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
												";
												if($Plateforme<>"0"){$requetePersonne.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) = ".$Plateforme." ";}
												if($Prestation<>"0"){$requetePersonne.="AND new_competences_personne_prestation.Id_Prestation = ".$Prestation." ";}
												if($Pole<>"0"){$requetePersonne.="AND new_competences_personne_prestation.Id_Pole = ".$Pole." ";}
								$requetePersonne.="
											)>0 
											AND 
											(
												SELECT Id_Prestation
												FROM new_competences_personne_prestation
												LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
												WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
												AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
												AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
												AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
												ORDER BY Date_Fin DESC, Date_Debut DESC
												LIMIT 1
											) NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
								 ";
								}
								else{
									$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
										CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
										FROM epe_personne_datebutoir
										LEFT JOIN new_rh_etatcivil
										ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne
										WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
										OR 
											(SELECT COUNT(Id)
											FROM epe_personne 
											WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0
										)
										AND 
										(
											SELECT COUNT(new_competences_personne_prestation.Id)
											FROM new_competences_personne_prestation
											LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
											WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
											AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
											AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
											AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
											AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
											AND 
											((SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) IN 
												(
													SELECT Id_Plateforme 
													FROM new_competences_personne_poste_plateforme
													WHERE Id_Personne=".$_SESSION['Id_Personne']." 
													AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteResponsablePlateforme.")
												)
												OR CONCAT(new_competences_personne_prestation.Id_Prestation,'_',new_competences_personne_prestation.Id_Pole) IN 
												(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
												FROM new_competences_personne_poste_prestation 
												WHERE Id_Personne=".$_SESSION["Id_Personne"]."
												AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
												)
											) ";
											if($Plateforme<>"0"){$requetePersonne.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) = ".$Plateforme." ";}
											if($Prestation<>"0"){$requetePersonne.="AND new_competences_personne_prestation.Id_Prestation = ".$Prestation." ";}
											if($Pole<>"0"){$requetePersonne.="AND new_competences_personne_prestation.Id_Pole = ".$Pole." ";}
							$requetePersonne.="
										
										)>0 
										AND 
										(
											SELECT Id_Prestation
											FROM new_competences_personne_prestation
											LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
											WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
											AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
											AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
											AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
											ORDER BY Date_Fin DESC, Date_Debut DESC
											LIMIT 1
										) NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
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
					<td style="font-size:15px;" class="Libelle">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Priorité :";}else{echo "Priority :";} ?>
						<select id="priorite" style="width:50px;" name="priorite" onchange="submit();">
						<?php
							$priorite=$_SESSION['FiltreEPE_Priorite'];
							if($_POST){$priorite=$_POST['priorite'];}
							$_SESSION['FiltreEPE_Priorite']=$priorite;
						?>
							<option value='0' <?php if($priorite==0){echo "selected";} ?>></option>
							<option value='1' <?php if($priorite==1){echo "selected";} ?>>1</option>
							<option value='2' <?php if($priorite==2){echo "selected";} ?>>2</option>
						</select>
					</td>
					<td style="font-size:15px;" class="Libelle">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Type :";}else{echo "Type :";} ?>
						<?php
							$EPE=$_SESSION['FiltreEPE_TypeEPE'];
							if($_POST){
								if(isset($_POST['EPE'])){$EPE="checked";}else{$EPE="";}				
							}
							$_SESSION['FiltreEPE_TypeEPE']=$EPE;
							
							$EPP=$_SESSION['FiltreEPE_TypeEPP'];
							if($_POST){
								if(isset($_POST['EPP'])){$EPP="checked";}else{$EPP="";}				
							}
							$_SESSION['FiltreEPE_TypeEPP']=$EPP;
							
							$EPPBilan=$_SESSION['FiltreEPE_TypeEPPBilan'];
							if($_POST){
								if(isset($_POST['EPPBilan'])){$EPPBilan="checked";}else{$EPPBilan="";}				
							}
							$_SESSION['FiltreEPE_TypeEPPBilan']=$EPPBilan;
						?>
						<input type="checkbox" id="EPE" name="EPE" value="EPE" <?php echo $EPE; ?>><?php if($_SESSION["Langue"]=="FR"){echo "EPE";}else{echo "EPE";} ?> &nbsp;&nbsp;
						<input type="checkbox" id="EPP" name="EPP" value="EPP" <?php echo $EPP; ?>><?php if($_SESSION["Langue"]=="FR"){echo "EPP";}else{echo "EPP";} ?> &nbsp;&nbsp;
						<input type="checkbox" id="EPPBilan" name="EPPBilan" value="EPPBilan" <?php echo $EPPBilan; ?>><?php if($_SESSION["Langue"]=="FR"){echo "EPP Bilan";}else{echo "EPP Bilan";} ?> &nbsp;&nbsp;
					</td>
					<td style="font-size:15px;" width="20%" class="Libelle" colspan="3">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Etat :";}else{echo "State :";} ?>
						<?php
							$AF=$_SESSION['FiltreEPE_EtatAF'];
							if($_POST){
								if(isset($_POST['AF'])){$AF="checked";}else{$AF="";}				
							}
							$_SESSION['FiltreEPE_EtatAF']=$AF;
							
							$Brouillon=$_SESSION['FiltreEPE_EtatBrouillon'];
							if($_POST){
								if(isset($_POST['Brouillon'])){$Brouillon="checked";}else{$Brouillon="";}				
							}
							$_SESSION['FiltreEPE_EtatBrouillon']=$Brouillon;
							
							$EC=$_SESSION['FiltreEPE_EtatEC'];
							if($_POST){
								if(isset($_POST['EC'])){$EC="checked";}else{$EC="";}				
							}
							$_SESSION['FiltreEPE_EtatEC']=$EC;
							
							$Soumis=$_SESSION['FiltreEPE_EtatSoumis'];
							if($_POST){
								if(isset($_POST['Soumis'])){$Soumis="checked";}else{$Soumis="";}				
							}
							$_SESSION['FiltreEPE_EtatSoumis']=$Soumis;
							
							$Realise=$_SESSION['FiltreEPE_EtatRealise'];
							if($_POST){
								if(isset($_POST['Realise'])){$Realise="checked";}else{$Realise="";}				
							}
							$_SESSION['FiltreEPE_EtatRealise']=$Realise;
						?>
							<input type="checkbox" id="AF" name="AF" value="AF" <?php echo $AF; ?>>A faire &nbsp;&nbsp;
							<input type="checkbox" id="Brouillon" name="Brouillon" value="Brouillon" <?php echo $Brouillon; ?>>Brouillon &nbsp;&nbsp;
							<input type="checkbox" id="Soumis" name="Soumis" value="Soumis" <?php echo $Soumis; ?>>Signature salarié&nbsp;&nbsp;
							<input type="checkbox" id="EC" name="EC" value="EC" <?php echo $EC; ?>>Signature manager &nbsp;&nbsp;
							<input type="checkbox" id="Realise" name="Realise" value="Realise" <?php echo $Realise; ?>>Réalisé &nbsp;&nbsp;
					</td>
					<?php 
						$reqPrestaPoste = "SELECT Id_Prestation 
						FROM new_competences_personne_poste_prestation 
						WHERE Id_Personne =".$IdPersonneConnectee."  
						AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) 
						AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.")
						";	
						$nbPoste=mysqli_num_rows($resAcc=mysqli_query($bdd,$reqPrestaPoste));
						if($nbPoste>0){
					?>
					<td style="font-size:15px;" class="Libelle" valign="top">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Affichage :";}else{echo "Viewing :";} ?>
							<?php
								$AffichageBackup=$_SESSION['FiltreEPE_AffichageBackup'];
								
								if($_POST){
									if(isset($_POST['AffichageBackup'])){$AffichageBackup="checked";}else{$AffichageBackup="";}
									
								}
								
								$_SESSION['FiltreEPE_AffichageBackup']=$AffichageBackup;

							?>
							<input type="checkbox" id="AffichageBackup" name="AffichageBackup" value="AffichageBackup" <?php echo $AffichageBackup; ?>><?php if($_SESSION["Langue"]=="FR"){echo "Backup";}else{echo "Backup";} ?> &nbsp;&nbsp;
					</td>
					<?php
						}
					?>
				</tr>
				<tr>
					<?php
					$manager=$_SESSION['FiltreEPE_Manager'];
					if($_POST){$manager=$_POST['manager'];}
					$_SESSION['FiltreEPE_Manager']=$manager;
					?>
					<td style="font-size:15px;<?php if(DroitsPlateforme(array($IdPosteAssistantRH,$IdPosteResponsableRH))){}else{echo "display:none;";} ?>" valign="top" width="15%" class="Libelle">
						&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Manager :";}else{echo "Manager :";} ?>
						<select id="manager" style="width:150px;" name="manager" onchange="submit();">
							<option value='0'></option>
							<?php
								if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
									$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
											CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
											FROM new_competences_personne_poste_prestation
											LEFT JOIN new_rh_etatcivil
											ON new_rh_etatcivil.Id=new_competences_personne_poste_prestation.Id_Personne
											WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.")
											AND new_rh_etatcivil.Id>0
											AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) ";
									if($Plateforme<>"0"){$requetePersonne.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_poste_prestation.Id_Prestation) = ".$Plateforme." ";}
									if($Prestation<>"0"){$requetePersonne.="AND new_competences_personne_poste_prestation.Id_Prestation = ".$Prestation." ";}
									if($Pole<>"0"){$requetePersonne.="AND new_competences_personne_poste_prestation.Id_Pole = ".$Pole." ";}
								}
								else{
									$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
											CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
											FROM new_competences_personne_poste_prestation
											LEFT JOIN new_rh_etatcivil
											ON new_rh_etatcivil.Id=new_competences_personne_poste_prestation.Id_Personne
											WHERE Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.")
											AND new_rh_etatcivil.Id>0
											AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
											AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN
											(
												SELECT Id_Plateforme 
												FROM new_competences_personne_poste_plateforme
												WHERE Id_Personne=".$_SESSION['Id_Personne']." 
												AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteResponsablePlateforme.")
											)
											";
											if($Plateforme<>"0"){$requetePersonne.="AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation) = ".$Plateforme." ";}
											if($Prestation<>"0"){$requetePersonne.="AND new_competences_personne_prestation.Id_Prestation = ".$Prestation." ";}
											if($Pole<>"0"){$requetePersonne.="AND new_competences_personne_prestation.Id_Pole = ".$Pole." ";}
								}
								$requetePersonne.="ORDER BY Personne ASC";
								$resultPersonne=mysqli_query($bdd,$requetePersonne);
								$NbPersonne=mysqli_num_rows($resultPersonne);

								while($rowPersonne=mysqli_fetch_array($resultPersonne))
								{
									echo "<option value='".$rowPersonne['Id']."'";
									if ($manager == $rowPersonne['Id']){echo " selected ";}
									echo ">".$rowPersonne['Personne']."</option>\n";
								}
							?>
						</select>
					</td>
					<td colspan="5" align="right">
						<?php if(DroitsFormationPlateforme(array($IdPosteAssistantRH.",".$IdPosteResponsableRH)) || DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){ ?>
							<a href="javascript:OuvreExcel()">
								<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
							</a>&nbsp;&nbsp;&nbsp;
						<?php } ?>
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
		$requeteAnalyse="SELECT DISTINCT new_rh_etatcivil.Id ";
		$requete2="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,DateAncienneteCDI,
		IF((SELECT COUNT(TAB.Id) FROM epe_personne_datebutoir AS TAB WHERE TAB.TypeEntretien='EPP Bilan' AND YEAR(IF(TAB.DateReport>'0001-01-01',TAB.DateReport,TAB.DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." AND
		TAB.Id_Personne=new_rh_etatcivil.Id)>0 AND
		IF(
			(SELECT COUNT(Id)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type='EPP Bilan' AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
		(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
		FROM epe_personne 
		WHERE Suppr=0 AND epe_personne.Type='EPP Bilan' AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
		'A faire') IN ('A faire','Brouillon'),1,2) AS Priorite
			
			";
		$requete="FROM new_rh_etatcivil
			RIGHT JOIN epe_personne_datebutoir 
			ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne 
			WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
			OR 
				(SELECT COUNT(Id)
				FROM epe_personne 
				WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0
			)
			AND 
				(
					SELECT COUNT(new_competences_personne_prestation.Id)
					FROM new_competences_personne_prestation
					LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
					WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
					AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
					AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
					AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
					AND new_competences_personne_prestation.Id_Prestation NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
				)>0 
				AND 
				(
					SELECT Id_Prestation
					FROM new_competences_personne_prestation
					LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
					WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
					AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
					AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."')
					AND new_competences_prestation.Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29)
					ORDER BY Date_Fin DESC, Date_Debut DESC
					LIMIT 1
				) NOT IN (1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461)
				";
		if($_SESSION['FiltreEPE_Personne']<>"0"){
			$requete.="AND new_rh_etatcivil.Id =".$_SESSION['FiltreEPE_Personne']." ";
		}
		$requete.="AND YEAR(IF(DateReport>'0001-01-01',DateReport,DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." ";
		if($_SESSION['FiltreEPE_TypeEPE']<>"" || $_SESSION['FiltreEPE_TypeEPP']<>"" || $_SESSION['FiltreEPE_TypeEPPBilan']<>""){
			$requete.="AND TypeEntretien IN (";
			$lesTypes="";
			if($_SESSION['FiltreEPE_TypeEPE']<>""){$lesTypes.="'EPE'";}
			if($_SESSION['FiltreEPE_TypeEPP']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'EPP'";}
			if($_SESSION['FiltreEPE_TypeEPPBilan']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'EPP Bilan'";}
			$requete.=$lesTypes.")";
		}
		if($_SESSION['FiltreEPE_EtatAF']<>"" || $_SESSION['FiltreEPE_EtatBrouillon']<>"" || $_SESSION['FiltreEPE_EtatEC']<>"" || $_SESSION['FiltreEPE_EtatSoumis']<>"" || $_SESSION['FiltreEPE_EtatRealise']<>""){
			$requete.="AND IF((SELECT COUNT(Id)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
			(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
			'A faire') IN ( ";
			$lesTypes="";
			if($_SESSION['FiltreEPE_EtatAF']<>""){$lesTypes.="'A faire'";}
			if($_SESSION['FiltreEPE_EtatBrouillon']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Brouillon'";}
			if($_SESSION['FiltreEPE_EtatEC']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Signature manager'";}
			if($_SESSION['FiltreEPE_EtatSoumis']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Signature salarié'";}
			if($_SESSION['FiltreEPE_EtatRealise']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Réalisé'";}
			$requete.=$lesTypes.") ";
		}
		if($_SESSION['FiltreEPE_Priorite']<>"0"){
			$requete.=" AND IF((SELECT COUNT(TAB.Id) FROM epe_personne_datebutoir AS TAB WHERE TAB.TypeEntretien='EPP Bilan' AND YEAR(IF(TAB.DateReport>'0001-01-01',TAB.DateReport,TAB.DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." AND
		TAB.Id_Personne=new_rh_etatcivil.Id)>0 AND
		IF(
			(SELECT COUNT(Id)
			FROM epe_personne 
			WHERE Suppr=0 AND epe_personne.Type='EPP Bilan' AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
		(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
		FROM epe_personne 
		WHERE Suppr=0 AND epe_personne.Type='EPP Bilan' AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
		'A faire') IN ('A faire','Brouillon'),1,2) = ".$_SESSION['FiltreEPE_Priorite']." ";
		}
		$requete.="ORDER BY Personne ";

		$result=mysqli_query($bdd,$requete2.$requete);
		$nbResulta=mysqli_num_rows($result);

		$req="DROP TEMPORARY TABLE IF EXISTS TMP_EPE;";
		$ResultD=mysqli_query($bdd,$req);
	
		$req="CREATE TEMPORARY TABLE TMP_EPE (Id INT(11),Personne VARCHAR(255),MatriculeAAA VARCHAR(255),DateAncienneteCDI DATE,Priorite INT(11),Id_Prestation INT(11),Id_Pole INT(11),Id_Plateforme INT(11),Id_Manager INT(11),Prestation VARCHAR(255),Pole VARCHAR(255),Plateforme VARCHAR(255),Manager VARCHAR(255));";
		$resultC=mysqli_query($bdd,$req);
		
		if($nbResulta>0){
			while($row=mysqli_fetch_array($result))
			{
				$laDateCloture=date('Y-m-d');
				$dateCloture="";
				$req="SELECT DateCloture FROM epe_cloturecampagne WHERE Annee=".$_SESSION['FiltreEPE_Annee']." ";
				$resultDateCloture=mysqli_query($bdd,$req);
				$nbDateCloture=mysqli_num_rows($resultDateCloture);
				if($nbDateCloture>0){
					$rowDateCloture=mysqli_fetch_array($resultDateCloture);
					$dateCloture=$rowDateCloture['DateCloture'];
					$laDateCloture=$dateCloture;
				}
				
				$req="SELECT Id_Prestation,Id_Pole 
					FROM new_competences_personne_prestation
					WHERE Id_Personne=".$row['Id']." 
					AND new_competences_personne_prestation.Date_Debut<='".$laDateCloture."'
					AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$laDateCloture."') 
					ORDER BY Date_Fin DESC, Date_Debut DESC ";
				$resultch=mysqli_query($bdd,$req);
				$nb=mysqli_num_rows($resultch);
				
				if($nb==0){
					$reqNb="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,
					TypeEntretien AS TypeE,
					IF((SELECT COUNT(Id)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
					(SELECT IF(TypeCadre=0,IF(Type='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),Type),IF(Type='EPE',IF(TypeCadre=1,'EPE - Non cadre','EPE - Cadre'),Type))
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
					IF(TypeEntretien='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),TypeEntretien)) AS TypeEntretien,
					IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir) AS DateButoir,
					epe_personne_datebutoir.Id AS Id_EpePersonneDB,
					IF((SELECT COUNT(Id)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
					(SELECT IF(TypeCadre=0,Cadre,IF(TypeCadre=1,0,1))
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
					Cadre) AS Cadre,
					IF((SELECT COUNT(Id)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
					(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
					'A faire')
					AS Etat,
					(SELECT Id_Evaluateur
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Manager,
					(SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Manager,
					(SELECT Id
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_PersonneEPE,
					(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Plateforme,
					(SELECT LectureRH
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS LectureRH,
					(SELECT CONCAT(epe_personne.Id_Prestation,'_',epe_personne.Id_Pole)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS PrestaPole
					FROM new_rh_etatcivil
					RIGHT JOIN epe_personne_datebutoir 
					ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne
					WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
					OR 
						(SELECT COUNT(Id)
						FROM epe_personne 
						WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0
					) 
					AND new_rh_etatcivil.Id=".$row['Id']."
					AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." ";
					if($_SESSION['FiltreEPE_TypeEPE']<>"" || $_SESSION['FiltreEPE_TypeEPP']<>"" || $_SESSION['FiltreEPE_TypeEPPBilan']<>""){
						$reqNb.="AND TypeEntretien IN (";
						$lesTypes="";
						if($_SESSION['FiltreEPE_TypeEPE']<>""){$lesTypes.="'EPE'";}
						if($_SESSION['FiltreEPE_TypeEPP']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'EPP'";}
						if($_SESSION['FiltreEPE_TypeEPPBilan']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'EPP Bilan'";}
						$reqNb.=$lesTypes.")";
					}
					$reqNb.="AND IF((SELECT COUNT(Id)
						FROM epe_personne 
						WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
						(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
						FROM epe_personne 
						WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
						'A faire') NOT IN ('A faire') ";
					if($_SESSION['FiltreEPE_EtatBrouillon']<>"" || $_SESSION['FiltreEPE_EtatEC']<>"" || $_SESSION['FiltreEPE_EtatSoumis']<>"" || $_SESSION['FiltreEPE_EtatRealise']<>""){
						$reqNb.="AND IF((SELECT COUNT(Id)
						FROM epe_personne 
						WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
						(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
						FROM epe_personne 
						WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
						'A faire') IN ( ";
						$lesTypes="";
						if($_SESSION['FiltreEPE_EtatBrouillon']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Brouillon'";}
						if($_SESSION['FiltreEPE_EtatEC']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Signature manager'";}
						if($_SESSION['FiltreEPE_EtatSoumis']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Signature salarié'";}
						if($_SESSION['FiltreEPE_EtatRealise']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Réalisé'";}
						$reqNb.=$lesTypes.") ";
					}
				}
				else{
					$reqNb="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,
					TypeEntretien AS TypeE,
					IF((SELECT COUNT(Id)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
					(SELECT IF(TypeCadre=0,IF(Type='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),Type),IF(Type='EPE',IF(TypeCadre=1,'EPE - Non cadre','EPE - Cadre'),Type))
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
					IF(TypeEntretien='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),TypeEntretien)) AS TypeEntretien,
					IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir) AS DateButoir,
					epe_personne_datebutoir.Id AS Id_EpePersonneDB,
					IF((SELECT COUNT(Id)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
					(SELECT IF(TypeCadre=0,Cadre,IF(TypeCadre=1,0,1))
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
					Cadre) AS Cadre,
					IF((SELECT COUNT(Id)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
					(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
					'A faire')
					AS Etat,
					(SELECT Id_Evaluateur
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Manager,
					(SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Manager,
					(SELECT Id
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_PersonneEPE,
					(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Plateforme,
					(SELECT LectureRH
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS LectureRH,
					(SELECT CONCAT(epe_personne.Id_Prestation,'_',epe_personne.Id_Pole)
					FROM epe_personne 
					WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS PrestaPole
					FROM new_rh_etatcivil
					RIGHT JOIN epe_personne_datebutoir 
					ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne
					WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
					OR 
						(SELECT COUNT(Id)
						FROM epe_personne 
						WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0
					) 
					AND new_rh_etatcivil.Id=".$row['Id']."
					AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." ";
					if($_SESSION['FiltreEPE_TypeEPE']<>"" || $_SESSION['FiltreEPE_TypeEPP']<>"" || $_SESSION['FiltreEPE_TypeEPPBilan']<>""){
						$reqNb.="AND TypeEntretien IN (";
						$lesTypes="";
						if($_SESSION['FiltreEPE_TypeEPE']<>""){$lesTypes.="'EPE'";}
						if($_SESSION['FiltreEPE_TypeEPP']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'EPP'";}
						if($_SESSION['FiltreEPE_TypeEPPBilan']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'EPP Bilan'";}
						$reqNb.=$lesTypes.")";
					}
					if($_SESSION['FiltreEPE_EtatAF']<>"" || $_SESSION['FiltreEPE_EtatBrouillon']<>"" || $_SESSION['FiltreEPE_EtatEC']<>"" || $_SESSION['FiltreEPE_EtatSoumis']<>"" || $_SESSION['FiltreEPE_EtatRealise']<>""){
						$reqNb.="AND IF((SELECT COUNT(Id)
						FROM epe_personne 
						WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
						(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
						FROM epe_personne 
						WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
						'A faire') IN ( ";
						$lesTypes="";
						if($_SESSION['FiltreEPE_EtatAF']<>""){$lesTypes.="'A faire'";}
						if($_SESSION['FiltreEPE_EtatBrouillon']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Brouillon'";}
						if($_SESSION['FiltreEPE_EtatEC']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Signature manager'";}
						if($_SESSION['FiltreEPE_EtatSoumis']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Signature salarié'";}
						if($_SESSION['FiltreEPE_EtatRealise']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Réalisé'";}
						$reqNb.=$lesTypes.") ";
					}
				}
				$ResultNb=mysqli_query($bdd,$reqNb);
				$leNb=mysqli_num_rows($ResultNb);

				$Manager="";
				$Id_Manager=0;
					
				if($leNb>0){
					$rowNb=mysqli_fetch_array($ResultNb);
					
					$Id_Prestation=0;
					$Id_Pole=0;
					$Id_Plateforme=0;
					
					$req="SELECT Id_Prestation,Id_Pole 
						FROM new_competences_personne_prestation
						WHERE Id_Personne=".$row['Id']." 
						AND new_competences_personne_prestation.Date_Debut<='".$dateFin."'
						AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$dateDebut."') 
						ORDER BY Date_Fin DESC, Date_Debut DESC ";
					
					$resultch=mysqli_query($bdd,$req);
					$nb=mysqli_num_rows($resultch);
					$Id_PrestationPole="0_0";
					if($nb>0){
						$rowMouv=mysqli_fetch_array($resultch);
						$Id_PrestationPole=$rowMouv['Id_Prestation']."_".$rowMouv['Id_Pole'];
					}
					
					$TableauPrestationPole=explode("_",$Id_PrestationPole);
					$Id_Prestation=$TableauPrestationPole[0];
					$Id_Pole=$TableauPrestationPole[1];
					
					$req="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation." ";
					$ResultPlat=mysqli_query($bdd,$req);
					$NbPlat=mysqli_num_rows($ResultPlat);
					if($NbPlat>0){
						$RowPlat=mysqli_fetch_array($ResultPlat);
						$Id_Plateforme=$RowPlat['Id_Plateforme'];
					}
					if($rowNb['Etat']=="A faire"){
						
						$req="SELECT Id_Prestation,Id_Pole 
							FROM new_competences_personne_prestation
							WHERE Id_Personne=".$row['Id']." 
							AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
							AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."') 
							ORDER BY Date_Fin DESC, Date_Debut DESC ";
						
						$resultch=mysqli_query($bdd,$req);
						$lenb=mysqli_num_rows($resultch);
						
						if($lenb>1){
							$req="SELECT Id_Prestation, Id_Pole, (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme FROM epe_personne_prestation WHERE Id_Personne=".$row['Id']." AND Suppr=0 AND Annee=".$_SESSION['FiltreEPE_Annee']." ORDER BY Id DESC";
							$ResultlaPresta=mysqli_query($bdd,$req);
							$NblaPresta=mysqli_num_rows($ResultlaPresta);
							if($NblaPresta>0){
								$RowlaPresta=mysqli_fetch_array($ResultlaPresta);
								$Id_Prestation=$RowlaPresta['Id_Prestation'];
								$Id_Pole=$RowlaPresta['Id_Pole'];
								$Id_Plateforme=$RowlaPresta['Id_Plateforme'];
							}
						}
					}
					else{
						$tab = explode("_",$rowNb['PrestaPole']);
						$Id_Prestation=$tab[0];
						$Id_Pole=$tab[1];
						$Id_Plateforme=$rowNb['Id_Plateforme'];
					}
					
					$Presta="";
					$Plateforme="";
					$req="SELECT LEFT(Libelle,7) AS Prestation, (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation;
					$ResultPresta=mysqli_query($bdd,$req);
					$NbPrest=mysqli_num_rows($ResultPresta);
					if($NbPrest>0){
						$RowPresta=mysqli_fetch_array($ResultPresta);
						$Presta=$RowPresta['Prestation'];
						$Plateforme=$RowPresta['Plateforme'];
					}
					
					$Pole="";
					$req="SELECT Libelle FROM new_competences_pole WHERE Id=".$Id_Pole;
					$ResultPole=mysqli_query($bdd,$req);
					$NbPole=mysqli_num_rows($ResultPole);
					if($NbPole>0){
						$RowPole=mysqli_fetch_array($ResultPole);
						$Pole=$RowPole['Libelle'];
					}
					
					if($Pole<>""){$Presta.=" - ".$Pole;}
					
					
					if($rowNb['Etat']=="A faire"){
						$req="SELECT Id_Manager, (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Manager) AS Manager FROM epe_personne_prestation WHERE Id_Personne=".$row['Id']." AND Id_Manager<>0 AND Id_Prestation=".$Id_Prestation."  AND Id_Pole=".$Id_Pole." AND Suppr=0 AND Annee=".$_SESSION['FiltreEPE_Annee']." ";
						$ResultlaPresta=mysqli_query($bdd,$req);
						$NblaPresta=mysqli_num_rows($ResultlaPresta);
						if($NblaPresta>0){
							$RowlaPresta=mysqli_fetch_array($ResultlaPresta);
							$Id_Manager=$RowlaPresta['Id_Manager'];
							$Manager=$RowlaPresta['Manager'];
						}
						else{
							$req="SELECT new_rh_etatcivil.Id,CONCAT(Nom,' ',Prenom) AS Personne
									FROM new_competences_personne_poste_prestation 
									LEFT JOIN new_rh_etatcivil
									ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
									WHERE Id_Poste=".$IdPosteCoordinateurEquipe."
									AND Id_Prestation=".$Id_Prestation."
									AND Id_Pole=".$Id_Pole."
									AND Id_Personne=".$row['Id']."
									AND Id_Personne>0
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
									AND Id_Personne>0
									ORDER BY Backup ";
								$ResultManager=mysqli_query($bdd,$req);
								$NbManager=mysqli_num_rows($ResultManager);
								if($NbManager>0){
									$RowManager=mysqli_fetch_array($ResultManager);
									$Manager=$RowManager['Personne'];
									$Id_Manager=$RowManager['Id'];
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
									AND Id_Personne>0
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
										AND Id_Personne>0
										ORDER BY Backup ";
									$ResultManager=mysqli_query($bdd,$req);
									$NbManager=mysqli_num_rows($ResultManager);
									if($NbManager>0){
										$RowManager=mysqli_fetch_array($ResultManager);
										$Manager=$RowManager['Personne'];
										$Id_Manager=$RowManager['Id'];
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
									AND Id_Personne>0
									ORDER BY Backup ";
									$ResultManager=mysqli_query($bdd,$req);
									$NbManager=mysqli_num_rows($ResultManager);
									if($NbManager>0){
										$RowManager=mysqli_fetch_array($ResultManager);
										$Manager=$RowManager['Personne'];
										$Id_Manager=$RowManager['Id'];
									}
								}
							}
						}
					}
					else{
						$Manager=$rowNb['Manager'];
						$Id_Manager=$rowNb['Id_Manager'];
					}
					
					$req= "INSERT INTO TMP_EPE (Id,Personne,MatriculeAAA,DateAncienneteCDI,Priorite,Id_Prestation,Id_Pole,Id_Plateforme,Id_Manager,Prestation,Pole,Plateforme,Manager)
						VALUES (".$row['Id'].",'".addslashes($row['Personne'])."','".$row['MatriculeAAA']."','".$row['DateAncienneteCDI']."',".$row['Priorite'].",".$Id_Prestation.",".$Id_Pole.",".$Id_Plateforme.",".$Id_Manager.",'".addslashes($Presta)."','".addslashes($Pole)."','".addslashes($Plateforme)."','".addslashes($Manager)."');";
					$ResultI=mysqli_query($bdd,$req);
				}
			}
		}
		
		$requeteAnalyse="SELECT Id,Personne,MatriculeAAA,DateAncienneteCDI,Priorite,Id_Prestation,Id_Pole,Id_Plateforme,Id_Manager,Prestation,Pole,Plateforme,Manager ";
		$requete2="SELECT Id,Personne,MatriculeAAA,DateAncienneteCDI,Priorite,Id_Prestation,Id_Pole,Id_Plateforme,Id_Manager,Prestation,Pole,Plateforme,Manager ";
		$requete="FROM TMP_EPE 
		";
		if(DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH))){
			if($_SESSION['FiltreEPE_Manager']<>"0" || $_SESSION['FiltreEPE_Plateforme']<>"0"){
				$requete.=" WHERE ";
				if($_SESSION['FiltreEPE_Manager']<>"0"){
					$requete.="TMP_EPE.Id_Manager=".$_SESSION['FiltreEPE_Manager']." AND ";
				}
				if($_SESSION['FiltreEPE_Plateforme']<>"0"){
					$requete.="TMP_EPE.Id_Plateforme=".$_SESSION['FiltreEPE_Plateforme']." AND ";
				}
				if($_SESSION['FiltreEPE_Prestation']<>"0"){
					$requete.="TMP_EPE.Id_Prestation=".$_SESSION['FiltreEPE_Prestation']." AND ";
				}
				if($_SESSION['FiltreEPE_Pole']<>"0"){
					$requete.="TMP_EPE.Id_Pole=".$_SESSION['FiltreEPE_Pole']." AND ";
				}
				$requete=substr($requete,0,-4);
			}
		}
		else{
			if($_SESSION['FiltreEPE_AffichageBackup']<>""){
				$requete.="
				WHERE 
				(TMP_EPE.Id_Plateforme IN 
				(
					SELECT Id_Plateforme 
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Personne=".$_SESSION['Id_Personne']." 
					AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteResponsablePlateforme.")
				)
				OR CONCAT(TMP_EPE.Id_Prestation,'_',TMP_EPE.Id_Pole) IN 
				(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
				FROM new_competences_personne_poste_prestation 
				WHERE Id_Personne=".$_SESSION["Id_Personne"]."
				AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
				)
				OR TMP_EPE.Id=".$_SESSION['Id_Personne'].")
				";
			}
			else{
				$requete.="
				WHERE 
				(TMP_EPE.Id_Plateforme IN 
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
					)
					OR TMP_EPE.Id_Manager=".$_SESSION['Id_Personne']."
					OR TMP_EPE.Id=".$_SESSION['Id_Personne'].")
				";
			}
			if($_SESSION['FiltreEPE_Manager']<>"0"){
				$requete.=" AND TMP_EPE.Id_Manager=".$_SESSION['FiltreEPE_Manager']." ";
			}
			if($_SESSION['FiltreEPE_Plateforme']<>"0"){
				$requete.=" AND TMP_EPE.Id_Plateforme=".$_SESSION['FiltreEPE_Plateforme']." ";
			}
			if($_SESSION['FiltreEPE_Prestation']<>"0"){
					$requete.="AND TMP_EPE.Id_Prestation=".$_SESSION['FiltreEPE_Prestation']." ";
				}
				if($_SESSION['FiltreEPE_Pole']<>"0"){
					$requete.="AND TMP_EPE.Id_Pole=".$_SESSION['FiltreEPE_Pole']." ";
				}
		}
		$result=mysqli_query($bdd,$requeteAnalyse.$requete);

		if(isset($_GET['Page'])){$_SESSION['EPE_Page']=$_GET['Page'];}
						
		$requete3=" LIMIT ".($_SESSION['EPE_Page']*40).",40";
		$nbResulta=mysqli_num_rows($result);

		$result=mysqli_query($bdd,$requete2.$requete.$requete3);
		$nombreDePages=ceil($nbResulta/40);
		$couleur="#FFFFFF";
		
		$req="SELECT Id FROM epe_cloturecampagne WHERE Annee=".$_SESSION['FiltreEPE_Annee']." AND DateCloture<'".date('Y-m-d')."' ";
		$resultCampagne=mysqli_query($bdd,$req);
		$campagneCloture=mysqli_num_rows($resultCampagne);
		
		?>
		<tr>
			<td align="center" style="font-size:14px;">
				<?php
					$nbPage=0;
					if($_SESSION['EPE_Page']>1){echo "<b> <a style='color:#00599f;' href='Liste_EPE.php?debut=1&Page=0'><<</a> </b>";}
					$valeurDepart=1;
					if($_SESSION['EPE_Page']<=5){
						$valeurDepart=1;
					}
					elseif($_SESSION['EPE_Page']>=($nombreDePages-6)){
						$valeurDepart=$nombreDePages-6;
					}
					else{
						$valeurDepart=$_SESSION['EPE_Page']-5;
					}
					for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
						if($i<=$nombreDePages){
							if($i==($_SESSION['EPE_Page']+1)){
								echo "<b> [ ".$i." ] </b>"; 
							}	
							else{
								echo "<b> <a style='color:#00599f;' href='Liste_EPE.php?debut=1&Page=".($i-1)."'>".$i."</a> </b>";
							}
						}
					}
					if($_SESSION['EPE_Page']<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_EPE.php?debut=1&Page=".($nombreDePages-1)."'>>></a> </b>";}
				?>
			</td>
		</tr>
		<tr>
			<td style="width:100%;" valign="top" align="center">
				<table class="TableCompetences" align="center" width="100%">
					<tr>
						<td style="font-size:15px;" class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "People";} ?></td>
						<td style="font-size:15px;" class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
						<td style="font-size:15px;" class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";} ?></td>
						<td style="font-size:15px;" class="EnTeteTableauCompetences" width="12%"><?php if($_SESSION["Langue"]=="FR"){echo "Responsable";}else{echo "Responsible";} ?></td>
						<td style="font-size:15px;" class="EnTeteTableauCompetences" width="4%"><?php if($_SESSION["Langue"]=="FR"){echo "Priorité";}else{echo "Priority";} ?></td>
						<td class="EnTeteTableauCompetences" width="6%">
							<?php if(DroitsPlateforme(array($IdPosteAssistantRH.",".$IdPosteResponsableRH))){ ?>
							<input class="Bouton" style="cursor: pointer;" name="PrendreEnCompte" size="3" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Pris en compte";}else{echo "Taken into account";}?>"><br>
							<input type="checkbox" name="selectAllPriseEC" id="selectAllPriseEC" onclick="SelectionnerToutPriseEC()" /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?>
							<?php } ?>
						</td>
						<td style="font-size:15px;" class="EnTeteTableauCompetences" width="6%"><?php if($_SESSION["Langue"]=="FR"){echo "Type";}else{echo "Type";} ?></td>
						<td style="font-size:15px;" class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Date butoir";}else{echo "Deadline";} ?></td>
						<td style="font-size:15px;" class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Date<br> prévisionnelle";}else{echo "Expected date";} ?></td>
						<td style="font-size:15px;" class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Etat";}else{echo "State";} ?></td>
						<td style="font-size:15px;" class='EnTeteTableauCompetences' width="8%" align="center">Formulaire<br> à compléter</td>
						<td style="font-size:15px;" class='EnTeteTableauCompetences' width="4%" align="center">Excel</td>
						<td style="font-size:15px;" class='EnTeteTableauCompetences' width="4%" align="center">PDF</td>
					</tr>
				</table>
				<div style="width:100%;height:400px;overflow:auto;">
				<table class="TableCompetences" align="center" width="100%">
		<?php			
				if($nbResulta>0){
					while($row=mysqli_fetch_array($result))
					{
						$laDateCloture=date('Y-m-d');
						$dateCloture="";
						$req="SELECT DateCloture FROM epe_cloturecampagne WHERE Annee=".$_SESSION['FiltreEPE_Annee']." ";
						$resultDateCloture=mysqli_query($bdd,$req);
						$nbDateCloture=mysqli_num_rows($resultDateCloture);
						if($nbDateCloture>0){
							$rowDateCloture=mysqli_fetch_array($resultDateCloture);
							$dateCloture=$rowDateCloture['DateCloture'];
							$laDateCloture=$dateCloture;
						}
						
						$req="SELECT Id_Prestation,Id_Pole 
							FROM new_competences_personne_prestation
							WHERE Id_Personne=".$row['Id']." 
							AND new_competences_personne_prestation.Date_Debut<='".$laDateCloture."'
							AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".$laDateCloture."') 
							ORDER BY Date_Fin DESC, Date_Debut DESC ";
						$resultch=mysqli_query($bdd,$req);
						$nb=mysqli_num_rows($resultch);
						
						if($nb==0){
							$reqNb="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,
							TypeEntretien AS TypeE,
							IF((SELECT COUNT(Id)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
							(SELECT IF(TypeCadre=0,IF(Type='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),Type),IF(Type='EPE',IF(TypeCadre=1,'EPE - Non cadre','EPE - Cadre'),Type))
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
							IF(TypeEntretien='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),TypeEntretien)) AS TypeEntretien,
							IF(epe_personne_datebutoir.DateReport>'0001-01-01',epe_personne_datebutoir.DateReport,epe_personne_datebutoir.DateButoir) AS DateButoir,
							epe_personne_datebutoir.Id AS Id_EpePersonneDB,
							IF((SELECT COUNT(Id)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
							(SELECT IF(TypeCadre=0,Cadre,IF(TypeCadre=1,0,1))
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
							Cadre) AS Cadre,
							DatePrevisionnelle,
							IF((SELECT COUNT(Id)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
							(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
							'A faire')
							AS Etat,
							(SELECT Id_Evaluateur
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Manager,
							(SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Manager,
							(SELECT Id
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_PersonneEPE,
							(SELECT Id_Plateforme
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Plateforme,
							(SELECT LectureRH
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS LectureRH,
							(SELECT CONCAT(epe_personne.Id_Prestation,'_',epe_personne.Id_Pole)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS PrestaPole,
							(SELECT COUNT(epe_personne_attente.Id) FROM epe_personne_attente WHERE epe_personne_attente.Id_Personne=new_rh_etatcivil.Id AND epe_personne_attente.Annee=".$_SESSION['FiltreEPE_Annee']."
							AND epe_personne_attente.TypeEntretien=epe_personne_datebutoir.TypeEntretien) AS EnAttente
							FROM new_rh_etatcivil
							RIGHT JOIN epe_personne_datebutoir 
							ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne
							WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
							OR 
								(SELECT COUNT(Id)
								FROM epe_personne 
								WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0
							) 
							AND new_rh_etatcivil.Id=".$row['Id']."
							AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." ";
							if($_SESSION['FiltreEPE_TypeEPE']<>"" || $_SESSION['FiltreEPE_TypeEPP']<>"" || $_SESSION['FiltreEPE_TypeEPPBilan']<>""){
								$reqNb.="AND TypeEntretien IN (";
								$lesTypes="";
								if($_SESSION['FiltreEPE_TypeEPE']<>""){$lesTypes.="'EPE'";}
								if($_SESSION['FiltreEPE_TypeEPP']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'EPP'";}
								if($_SESSION['FiltreEPE_TypeEPPBilan']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'EPP Bilan'";}
								$reqNb.=$lesTypes.")";
							}
							$reqNb.="AND IF((SELECT COUNT(Id)
								FROM epe_personne 
								WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
								(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
								FROM epe_personne 
								WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
								'A faire') NOT IN ('A faire') ";
							if($_SESSION['FiltreEPE_EtatBrouillon']<>"" || $_SESSION['FiltreEPE_EtatEC']<>"" || $_SESSION['FiltreEPE_EtatSoumis']<>"" || $_SESSION['FiltreEPE_EtatRealise']<>""){
								$reqNb.="AND IF((SELECT COUNT(Id)
								FROM epe_personne 
								WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
								(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
								FROM epe_personne 
								WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
								'A faire') IN ( ";
								$lesTypes="";
								if($_SESSION['FiltreEPE_EtatBrouillon']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Brouillon'";}
								if($_SESSION['FiltreEPE_EtatEC']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Signature manager'";}
								if($_SESSION['FiltreEPE_EtatSoumis']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Signature salarié'";}
								if($_SESSION['FiltreEPE_EtatRealise']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Réalisé'";}
								$reqNb.=$lesTypes.") ";
							}
						}
						else{
							$reqNb="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,MatriculeAAA,
							TypeEntretien AS TypeE,
							IF((SELECT COUNT(Id)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
							(SELECT IF(TypeCadre=0,IF(Type='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),Type),IF(Type='EPE',IF(TypeCadre=1,'EPE - Non cadre','EPE - Cadre'),Type))
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
							IF(TypeEntretien='EPE',IF(Cadre=0,'EPE - Non cadre','EPE - Cadre'),TypeEntretien)) AS TypeEntretien,
							IF(epe_personne_datebutoir.DateReport>'0001-01-01',epe_personne_datebutoir.DateReport,epe_personne_datebutoir.DateButoir) AS DateButoir,
							epe_personne_datebutoir.Id AS Id_EpePersonneDB,
							TypeEntretien AS TypeE,
							IF((SELECT COUNT(Id)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
							(SELECT IF(TypeCadre=0,Cadre,IF(TypeCadre=1,0,1))
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
							Cadre) AS Cadre,
							DatePrevisionnelle,
							IF((SELECT COUNT(Id)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
							(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
							'A faire')
							AS Etat,
							(SELECT Id_Evaluateur
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Manager,
							(SELECT (SELECT CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) FROM new_rh_etatcivil WHERE Id=Id_Evaluateur)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Manager,
							(SELECT Id
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_PersonneEPE,
							(SELECT Id_Plateforme
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS Id_Plateforme,
							(SELECT LectureRH
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS LectureRH,
							(SELECT CONCAT(epe_personne.Id_Prestation,'_',epe_personne.Id_Pole)
							FROM epe_personne 
							WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1) AS PrestaPole,
							(SELECT COUNT(epe_personne_attente.Id) FROM epe_personne_attente WHERE epe_personne_attente.Id_Personne=new_rh_etatcivil.Id AND epe_personne_attente.Annee=".$_SESSION['FiltreEPE_Annee']."
							AND epe_personne_attente.TypeEntretien=epe_personne_datebutoir.TypeEntretien) AS EnAttente
							FROM new_rh_etatcivil
							RIGHT JOIN epe_personne_datebutoir 
							ON new_rh_etatcivil.Id=epe_personne_datebutoir.Id_Personne
							WHERE ((MatriculeAAA<>'' AND DateAncienneteCDI>'0001-01-01' AND Contrat IN ('CDI','CDD','CDIC','CDIE') AND MetierPaie<>'' AND Cadre IN (0,1))
							OR 
								(SELECT COUNT(Id)
								FROM epe_personne 
								WHERE Suppr=0 AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0
							) 
							AND new_rh_etatcivil.Id=".$row['Id']."
							AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".$_SESSION['FiltreEPE_Annee']." ";
							if($_SESSION['FiltreEPE_TypeEPE']<>"" || $_SESSION['FiltreEPE_TypeEPP']<>"" || $_SESSION['FiltreEPE_TypeEPPBilan']<>""){
								$reqNb.="AND TypeEntretien IN (";
								$lesTypes="";
								if($_SESSION['FiltreEPE_TypeEPE']<>""){$lesTypes.="'EPE'";}
								if($_SESSION['FiltreEPE_TypeEPP']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'EPP'";}
								if($_SESSION['FiltreEPE_TypeEPPBilan']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'EPP Bilan'";}
								$reqNb.=$lesTypes.")";
							}
							if($_SESSION['FiltreEPE_EtatAF']<>"" || $_SESSION['FiltreEPE_EtatBrouillon']<>"" || $_SESSION['FiltreEPE_EtatEC']<>"" || $_SESSION['FiltreEPE_EtatSoumis']<>"" || $_SESSION['FiltreEPE_EtatRealise']<>""){
								$reqNb.="AND IF((SELECT COUNT(Id)
								FROM epe_personne 
								WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee'].")>0,
								(SELECT IF(ModeBrouillon=1,'Brouillon',IF(DateSalarie<='0001-01-01','Signature salarié',IF(DateEvaluateur>'0001-01-01','Réalisé','Signature manager')))
								FROM epe_personne 
								WHERE Suppr=0 AND epe_personne.Type=epe_personne_datebutoir.TypeEntretien AND epe_personne.Id_Personne=new_rh_etatcivil.Id AND YEAR(epe_personne.DateButoir) = ".$_SESSION['FiltreEPE_Annee']." LIMIT 1),
								'A faire') IN ( ";
								$lesTypes="";
								if($_SESSION['FiltreEPE_EtatAF']<>""){$lesTypes.="'A faire'";}
								if($_SESSION['FiltreEPE_EtatBrouillon']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Brouillon'";}
								if($_SESSION['FiltreEPE_EtatEC']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Signature manager'";}
								if($_SESSION['FiltreEPE_EtatSoumis']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Signature salarié'";}
								if($_SESSION['FiltreEPE_EtatRealise']<>""){if($lesTypes<>""){$lesTypes.=",";}$lesTypes.="'Réalisé'";}
								$reqNb.=$lesTypes.") ";
							}
						}
						$ResultNb=mysqli_query($bdd,$reqNb);
						$leNb=mysqli_num_rows($ResultNb);
						
						$rowNb=mysqli_fetch_array($ResultNb);
						
						$fusion="rowspan='".$leNb."'";
						if($couleur=="#FFFFFF"){$couleur="#c9d9ef";}
						else{$couleur="#FFFFFF";}
					?>
						<tr>
							<td bgcolor="<?php echo $couleur;?>" width="10%" <?php echo $fusion; ?> style="font-size:15px;"><?php echo stripslashes($row['Personne']);?></td>
							<td bgcolor="<?php echo $couleur;?>" width="6%" <?php echo $fusion; ?> style="font-size:15px;"><?php echo stripslashes($row['Prestation']);?></td>
							<td bgcolor="<?php echo $couleur;?>" width="10%" <?php echo $fusion; ?> style="font-size:15px;"><?php echo stripslashes($row['Plateforme']);?></td>
							<td bgcolor="<?php echo $couleur;?>" width="12%" <?php echo $fusion; ?> style="font-size:15px;"><?php echo stripslashes($row['Manager']);?></td>
							<td bgcolor="<?php echo $couleur;?>" width="4%" <?php echo $fusion; ?> style="font-size:15px;"><?php echo stripslashes($row['Priorite']);?></td>
					<?php 
						$ResultNb=mysqli_query($bdd,$reqNb);
						if($leNb>0){
							while($rowNb=mysqli_fetch_array($ResultNb))
							{
							 ?>
							<td bgcolor="<?php echo $couleur;?>" width="6%" style="border-bottom:dotted 1px #003333;">
								<?php 
							if($rowNb['Id_Plateforme']>0 && $rowNb['Etat']=='Réalisé'){
								if(DroitsFormation1Plateforme($rowNb['Id_Plateforme'],array($IdPosteAssistantRH.",".$IdPosteResponsableRH))){
									if($rowNb['LectureRH']==1)
									{
										echo "<img src='../../Images/tick.png' border='0' alt='Prise en compte' title='Prise en compte'>";
									}
									else
									{
								?>
								<input class="checkEC" type="checkbox" name="PriseEnCompte[]" value="<?php echo $rowNb['Id_PersonneEPE']; ?>" />
								<?php
									}
								} 
							}
								?>
							</td>
							<td bgcolor="<?php echo $couleur;?>" width="6%" style="font-size:15px;border-bottom:1px dotted #2e578e;"><?php echo stripslashes($rowNb['TypeEntretien']);?></td>
							<td bgcolor="<?php echo $couleur;?>" width="8%" style="font-size:15px;border-bottom:1px dotted #2e578e;"><?php echo AfficheDateJJ_MM_AAAA($rowNb['DateButoir']);?></td>
							<td bgcolor="<?php echo $couleur;?>" width="8%" style="font-size:15px;border-bottom:1px dotted #2e578e;"><?php 
							if($rowNb['DatePrevisionnelle']<date('Y-m-d')){
								echo "<span style='background-color:#e76153'>";
							}
							echo AfficheDateJJ_MM_AAAA($rowNb['DatePrevisionnelle']);
							if($rowNb['DatePrevisionnelle']<date('Y-m-d')){
								echo "</span>";
							}
							if($rowNb['Etat']=="A faire" || $rowNb['Etat']=="Brouillon"){
								if($row['Id_Manager']==$_SESSION['Id_Personne'] || $_SESSION['FiltreEPE_AffichageBackup']<>""){
									if($rowNb['DatePrevisionnelle']>"0001-01-01"){
										echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
									}
									if($campagneCloture==0){
								?>
									<a class="Modif" href="javascript:Plannifier(<?php echo $row['Id']; ?>);">
										<img src="../../Images/RH/Planning.png" width="15px;" style="border:0;" alt="Plannif">
									</a>
								<?php
									}
								}
							}
							?>
							</td>
							<td bgcolor="<?php echo $couleur;?>" width="5%" style="font-size:15px;border-bottom:1px dotted #2e578e;"><?php echo stripslashes($rowNb['Etat']);?></td>
							<td bgcolor="<?php echo $couleur;?>" width="8%" style="border-bottom:1px dotted #2e578e;">
								<?php 
									if($row['Id_Manager']==$_SESSION['Id_Personne'] || $_SESSION['FiltreEPE_AffichageBackup']<>"" || DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH)) || DroitsFormation1Plateforme($row['Id_Plateforme'],array($IdPosteAssistantRH.",".$IdPosteResponsableRH))){
										if($rowNb['TypeE']=="EPE"){
											$Mode="M";
											if($rowNb['Etat']=="A faire"){$Mode="A";}
											elseif($rowNb['Etat']=="Brouillon"){$Mode="B";}
												if($rowNb['Etat']<>"Réalisé" && (($rowNb['Etat']=="A faire" && $rowNb['EnAttente']==0) || $rowNb['Etat']<>"A faire")){
												if($campagneCloture==0 || ($campagneCloture==1 && $rowNb['Etat']<>"A faire" && $rowNb['Etat']<>"Brouillon")){
												?>
												<a class="Modif" href="javascript:OuvreFenetreEPE(<?php echo $rowNb['Id_EpePersonneDB']; ?>,<?php echo $rowNb['Cadre']; ?>,'<?php echo $Mode; ?>','<?php echo $row['Id_Manager']; ?>');">
													<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
												</a>
										<?php 
												}
												}
												else{
													if($rowNb['Etat']=="A faire" && $rowNb['EnAttente']>0){
														echo "Mis en attente par les RH";
													}
												}
											}elseif($rowNb['TypeE']=="EPP"){
											$Mode="M";
											if($rowNb['Etat']=="A faire"){$Mode="A";}
											if($rowNb['Etat']<>"Réalisé" && (($rowNb['Etat']=="A faire" && $rowNb['EnAttente']==0) || $rowNb['Etat']<>"A faire")){
											if($campagneCloture==0 || ($campagneCloture==1 && $rowNb['Etat']<>"A faire" && $rowNb['Etat']<>"Brouillon")){
											?>
											<a class="Modif" href="javascript:OuvreFenetreEPP(<?php echo $rowNb['Id_EpePersonneDB']; ?>,'<?php echo $Mode; ?>','<?php echo $row['Id_Manager']; ?>');">
												<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
											</a>
										<?php
											}
											}
											else{
												if($rowNb['Etat']=="A faire" && $rowNb['EnAttente']>0){
													echo "Mis en attente par les RH";
												}
											}
										}elseif($rowNb['TypeE']=="EPP Bilan"){
											$Mode="M";
											if($rowNb['Etat']=="A faire"){$Mode="A";}
											//Vérifier si un EPP est en état "A faire" cette année 
											//si c'est le cas alors informer qu'il faut d'abord faire l'EPP avant de faire l'EPP Bilan 
											$req="SELECT Id 
											FROM epe_personne 
											WHERE Suppr=0 AND Type='EPP' AND Id_Personne=".$row['Id']." AND YEAR(epe_personne.DateButoir) = ".date('Y')." ";
											$ResultEPP=mysqli_query($bdd,$req);
											$NbEPP=mysqli_num_rows($ResultEPP);
											
											$req="SELECT Id 
											FROM epe_personne_datebutoir 
											WHERE TypeEntretien='EPP' AND Id_Personne=".$row['Id']." AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".date('Y')." ";
											$ResultDateButoirEPP=mysqli_query($bdd,$req);
											$NbDateButoirEPP=mysqli_num_rows($ResultDateButoirEPP);
											
											if($rowNb['Etat']<>"Réalisé"  && (($rowNb['Etat']=="A faire" && $rowNb['EnAttente']==0) || $rowNb['Etat']<>"A faire")){
												if(($rowNb['Etat']=="A faire" && ($NbDateButoirEPP==0 || ($NbDateButoirEPP>0 && $NbEPP>0))) || $rowNb['Etat']<>"A faire"){
												if($campagneCloture==0 || ($campagneCloture==1 && $rowNb['Etat']<>"A faire" && $rowNb['Etat']<>"Brouillon")){
											?>
												<a class="Modif" href="javascript:OuvreFenetreEPPBilan(<?php echo $rowNb['Id_EpePersonneDB']; ?>,'<?php echo $Mode; ?>','<?php echo $row['Id_Manager']; ?>');">
													<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
												</a>
											<?php
												}
												}
												else{
													echo "Réaliser l'EPP";
												}
											}
											else{
												if($rowNb['Etat']=="A faire" && $rowNb['EnAttente']>0){
													echo "Mis en attente par les RH";
												}
											}
										}										
									}
									elseif($row['Id']==$_SESSION['Id_Personne'] || DroitsFormation1Plateforme(17,array($IdPosteAssistantRH,$IdPosteResponsableRH)) || DroitsFormation1Plateforme($row['Id_Plateforme'],array($IdPosteAssistantRH.",".$IdPosteResponsableRH))){
										if($rowNb['TypeE']=="EPE" && $rowNb['Etat']<>"A faire" && $rowNb['Etat']<>"Réalisé" && $rowNb['Etat']<>"Brouillon"){
									?>
										<a class="Modif" href="javascript:OuvreFenetreEPE(<?php echo $rowNb['Id_EpePersonneDB']; ?>,<?php echo $rowNb['Cadre']; ?>,'M','<?php echo $row['Id_Manager']; ?>');">
											<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
										</a>
									<?php
										}
										elseif($rowNb['TypeE']=="EPP" && $rowNb['Etat']<>"A faire"  && $rowNb['Etat']<>"Réalisé" && $rowNb['Etat']<>"Brouillon"){
									?>
										<a class="Modif" href="javascript:OuvreFenetreEPP(<?php echo $rowNb['Id_EpePersonneDB']; ?>,'M','<?php echo $row['Id_Manager']; ?>');">
											<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
										</a>
									<?php
										}
										elseif($rowNb['TypeE']=="EPP Bilan" && $rowNb['Etat']<>"A faire"  && $rowNb['Etat']<>"Réalisé" && $rowNb['Etat']<>"Brouillon"){
									?>
										<a class="Modif" href="javascript:OuvreFenetreEPPBilan(<?php echo $rowNb['Id_EpePersonneDB']; ?>,'M','<?php echo $row['Id_Manager']; ?>');">
											<img src="../../Images/Modif.gif" style="border:0;" alt="Modification">
										</a>
									<?php
										}
									}
									elseif(DroitsFormationPlateforme(array($IdPosteAssistantRH.",".$IdPosteResponsableRH))){
										if($rowNb['TypeE']=="EPE"){
											$Mode="M";
											if($rowNb['Etat']=="A faire"){$Mode="A";}
											elseif($rowNb['Etat']=="Brouillon"){$Mode="B";}
												if($rowNb['Etat']<>"Réalisé" && (($rowNb['Etat']=="A faire" && $rowNb['EnAttente']==0) || $rowNb['Etat']<>"A faire")){
		
												}
												else{
													if($rowNb['Etat']=="A faire" && $rowNb['EnAttente']>0){
														echo "Mis en attente par les RH";
													}
												}
											}elseif($rowNb['TypeE']=="EPP"){
											$Mode="M";
											if($rowNb['Etat']=="A faire"){$Mode="A";}
											if($rowNb['Etat']<>"Réalisé" && (($rowNb['Etat']=="A faire" && $rowNb['EnAttente']==0) || $rowNb['Etat']<>"A faire")){

											}
											else{
												if($rowNb['Etat']=="A faire" && $rowNb['EnAttente']>0){
													echo "Mis en attente par les RH";
												}
											}
										}elseif($rowNb['TypeE']=="EPP Bilan"){
											$Mode="M";
											if($rowNb['Etat']=="A faire"){$Mode="A";}
											//Vérifier si un EPP est en état "A faire" cette année 
											//si c'est le cas alors informer qu'il faut d'abord faire l'EPP avant de faire l'EPP Bilan 
											$req="SELECT Id 
											FROM epe_personne 
											WHERE Suppr=0 AND Type='EPP' AND Id_Personne=".$row['Id']." AND YEAR(epe_personne.DateButoir) = ".date('Y')." ";
											$ResultEPP=mysqli_query($bdd,$req);
											$NbEPP=mysqli_num_rows($ResultEPP);
											
											$req="SELECT Id 
											FROM epe_personne_datebutoir 
											WHERE TypeEntretien='EPP' AND Id_Personne=".$row['Id']." AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".date('Y')." ";
											$ResultDateButoirEPP=mysqli_query($bdd,$req);
											$NbDateButoirEPP=mysqli_num_rows($ResultDateButoirEPP);
											
											if($rowNb['Etat']<>"Réalisé"  && (($rowNb['Etat']=="A faire" && $rowNb['EnAttente']==0) || $rowNb['Etat']<>"A faire")){
												if(($rowNb['Etat']=="A faire" && ($NbDateButoirEPP==0 || ($NbDateButoirEPP>0 && $NbEPP>0))) || $rowNb['Etat']<>"A faire"){

												}
												else{
													echo "Réaliser l'EPP";
												}
											}
											else{
												if($rowNb['Etat']=="A faire" && $rowNb['EnAttente']>0){
													echo "Mis en attente par les RH";
												}
											}
										}
									}
								?>
							</td>
							<td bgcolor="<?php echo $couleur;?>" width="4%" style="border-bottom:1px dotted #2e578e;">
								<?php 
									if($rowNb['TypeE']=="EPE" && $rowNb['Etat']<>"Réalisé"){
										if($rowNb['Etat']=="A faire"){
										?>
										<a class="Modif" href="javascript:EPE_Excel(<?php echo $rowNb['Id_EpePersonneDB']; ?>,<?php echo $rowNb['Cadre']; ?>,'<?php echo $row['Id_Manager']; ?>');">
											<img src='../../Images/excel.gif' border='0' alt='Excel' width='14'>
										</a>
										<?php	
										}
										else{
											if($row['Id']==$_SESSION['Id_Personne']){
												if($rowNb['Etat']=="Brouillon"){
										?>
										<a class="Modif" href="javascript:EPE_Excel(<?php echo $rowNb['Id_EpePersonneDB']; ?>,<?php echo $rowNb['Cadre']; ?>,'<?php echo $row['Id_Manager']; ?>');">
											<img src='../../Images/excel.gif' border='0' alt='Excel' width='14'>
										</a>
										<?php			
												}
												else{
										?>
										<a class="Modif" href="javascript:EPEB_Excel(<?php echo $rowNb['Id_EpePersonneDB']; ?>,<?php echo $rowNb['Cadre']; ?>,'<?php echo $row['Id_Manager']; ?>');">
											<img src='../../Images/excel.gif' border='0' alt='Excel' width='14'>
										</a>
										<?php			
												}
											}
											else{
										?>
										<a class="Modif" href="javascript:EPEB_Excel(<?php echo $rowNb['Id_EpePersonneDB']; ?>,<?php echo $rowNb['Cadre']; ?>,'<?php echo $row['Id_Manager']; ?>');">
											<img src='../../Images/excel.gif' border='0' alt='Excel' width='14'>
										</a>
										<?php
											}
										}
									}
									elseif($rowNb['TypeE']=="EPP" && $rowNb['Etat']<>"Réalisé"){
										if($rowNb['Etat']=="A faire"){
										?>
										<a class="Modif" href="javascript:EPP_Excel(<?php echo $rowNb['Id_EpePersonneDB']; ?>,'<?php echo $row['Id_Manager']; ?>');">
											<img src='../../Images/excel.gif' border='0' alt='Excel' width='14'>
										</a>
										<?php	
										}
										else{
										?>
										<a class="Modif" href="javascript:EPPB_Excel(<?php echo $rowNb['Id_EpePersonneDB']; ?>,'<?php echo $row['Id_Manager']; ?>');">
											<img src='../../Images/excel.gif' border='0' alt='Excel' width='14'>
										</a>
										<?php
										}
									}
									elseif($rowNb['TypeE']=="EPP Bilan" && $rowNb['Etat']<>"Réalisé"){
										if($rowNb['Etat']=="A faire"){
											//Vérifier si un EPP est en état "A faire" cette année 
											//si c'est le cas alors informer qu'il faut d'abord faire l'EPP avant de faire l'EPP Bilan 
											$req="SELECT Id 
											FROM epe_personne 
											WHERE Suppr=0 AND Type='EPP' AND Id_Personne=".$row['Id']." AND YEAR(epe_personne.DateButoir) = ".date('Y')." ";
											$ResultEPP=mysqli_query($bdd,$req);
											$NbEPP=mysqli_num_rows($ResultEPP);
											
											$req="SELECT Id 
											FROM epe_personne_datebutoir 
											WHERE TypeEntretien='EPP' AND Id_Personne=".$row['Id']." AND YEAR(IF(DateReport>'0001-01-01',DateReport,epe_personne_datebutoir.DateButoir)) = ".date('Y')." ";
											$ResultDateButoirEPP=mysqli_query($bdd,$req);
											$NbDateButoirEPP=mysqli_num_rows($ResultDateButoirEPP);
											if($NbDateButoirEPP==0 || ($NbDateButoirEPP>0 && $NbEPP>0)){
										?>
										<a class="Modif" href="javascript:EPPBilan_Excel(<?php echo $rowNb['Id_EpePersonneDB']; ?>,'<?php echo $row['Id_Manager']; ?>');">
											<img src='../../Images/excel.gif' border='0' alt='Excel' width='14'>
										</a>
										<?php
											}
										}
										else{
										?>
										<a class="Modif" href="javascript:EPPBilanB_Excel(<?php echo $rowNb['Id_EpePersonneDB']; ?>,'<?php echo $row['Id_Manager']; ?>');">
											<img src='../../Images/excel.gif' border='0' alt='Excel' width='14'>
										</a>
										<?php
										}
									}
								?>
							</td>
							<td bgcolor="<?php echo $couleur;?>" width="4%" style="border-bottom:1px dotted #2e578e;">
								<?php 
									if($rowNb['TypeE']=="EPE" && $rowNb['Etat']=="Réalisé"){
										?>
										<a class="Modif" href="javascript:EPE_PDF(<?php echo $rowNb['Id_EpePersonneDB']; ?>,<?php echo $rowNb['Cadre']; ?>);">
											<img src='../../Images/pdf.png' border='0' alt='PDF' width='14'>
										</a>
								<?php
									}
									elseif($rowNb['TypeE']=="EPP" && $rowNb['Etat']=="Réalisé"){
										?>
										<a class="Modif" href="javascript:EPP_PDF(<?php echo $rowNb['Id_EpePersonneDB']; ?>);">
											<img src='../../Images/pdf.png' border='0' alt='PDF' width='14'>
										</a>
								<?php 
									}
									elseif($rowNb['TypeE']=="EPP Bilan" && $rowNb['Etat']=="Réalisé"){
										?>
										<a class="Modif" href="javascript:EPPBilan_PDF(<?php echo $rowNb['Id_EpePersonneDB']; ?>);">
											<img src='../../Images/pdf.png' border='0' alt='PDF' width='14'>
										</a>
								<?php 
									}
								?>
							</td>
						<tr>
					<?php 
							}
						}
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
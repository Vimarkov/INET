<?php
require("../../Menu.php");
?>
<script language="javascript">
	function OuvreFenetreModif(Menu,Id_Personne)
		{var w=window.open("Modif_InfosPersonnel.php?Menu="+Menu+"&Id_Personne="+Id_Personne,"PageInfos","status=no,menubar=no,scrollbars=1,width=800,height=400");
		w.focus();
		}
	function OuvreFenetreExcel(Menu)
		{window.open("Export_InformationsPersonnel.php?Menu="+Menu,"PageInfosPersoExport","status=no,menubar=no,scrollbars=1,width=800,height=430");}
	function OuvreFenetreReini(Id){
		if(window.confirm('Etes-vous sûr de vouloir réinitialiser le mot de passe ?')){
			var w=window.open("ReinitialiseMotDePasse.php?Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
			}
		}
</script>
<?php
if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
?>

<form class="test" action="Liste_InformationsPersonnel.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#87ceff;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Informations du personnel";}else{echo "Staff information";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?>
				<select class="plateforme" style="width:150px;" name="plateforme" onchange="submit();">
					<option value='0' selected></option>
					<?php
					$requetePlateforme="SELECT Id, Libelle
						FROM new_competences_plateforme
						WHERE Id IN 
							(
								SELECT Id_Plateforme 
								FROM new_competences_personne_poste_plateforme
								WHERE Id_Personne=".$_SESSION['Id_Personne']." 
								AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
							)
						ORDER BY Libelle ASC";
					$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
					$nbPlateforme=mysqli_num_rows($resultPlateforme);
					
					$PlateformeSelect = 0;
					$Selected = "";
					
					$PlateformeSelect=$_SESSION['FiltreRHInfosPersonnel_Plateforme'];
					if($_POST){$PlateformeSelect=$_POST['plateforme'];}
					$_SESSION['FiltreRHInfosPersonnel_Plateforme']=$PlateformeSelect;	
					
					if ($nbPlateforme > 0)
					{
						while($row=mysqli_fetch_array($resultPlateforme))
						{
							$selected="";
							if($PlateformeSelect==$row['Id']){$selected="selected";}
							echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
						}
					 }
					 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?>
				<select class="prestation" style="width:150px;" name="prestations" onchange="submit();">
				<?php
				if($Menu==4){
					if(DroitsFormationPlateforme($TableauIdPostesRH)){
						$requeteSite="SELECT Id, Libelle
							FROM new_competences_prestation
							WHERE Id_Plateforme IN 
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
								)
							AND Active=0
							ORDER BY Libelle ASC";
					}
				}
				elseif($Menu==3){
					$requeteSite="SELECT Id, Libelle
						FROM new_competences_prestation
						WHERE Id IN 
							(SELECT Id_Prestation 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
							)
						AND Active=0
						ORDER BY Libelle ASC";
				}
				elseif($Menu==2){
					$requeteSite="SELECT DISTINCT new_competences_prestation.Id, 
							new_competences_prestation.Libelle
							FROM rh_personne_mouvement
							LEFT JOIN new_competences_prestation
							ON new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation
							WHERE rh_personne_mouvement.Id_Personne=".$_SESSION['Id_Personne']."
							ORDER BY Libelle ASC";
				}
				$resultPrestation=mysqli_query($bdd,$requeteSite);
				$nbPrestation=mysqli_num_rows($resultPrestation);
				
				$PrestationSelect = 0;
				$Selected = "";
				
				$PrestationSelect=$_SESSION['FiltreRHInfosPersonnel_Prestation'];
				if($_POST){$PrestationSelect=$_POST['prestations'];}
				$_SESSION['FiltreRHInfosPersonnel_Prestation']=$PrestationSelect;	
				
				echo "<option name='0' value='0' Selected></option>";
				if ($nbPrestation > 0)
				{
					while($row=mysqli_fetch_array($resultPrestation))
					{
						$selected="";
						if($PrestationSelect<>"")
							{if($PrestationSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?>
				<select class="pole" style="width:150px;" name="pole" onchange="submit();">
				<?php

				if($Menu==4){
					if(DroitsFormationPlateforme($TableauIdPostesRH)){
						$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
								FROM new_competences_pole
								LEFT JOIN new_competences_prestation
								ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
								WHERE Id_Plateforme IN 
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
								)
								AND Actif=0
								AND new_competences_pole.Id_Prestation=".$PrestationSelect."
								ORDER BY new_competences_pole.Libelle ASC";
					}
				}
				elseif($Menu==3){
					$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
						FROM new_competences_pole
						LEFT JOIN new_competences_prestation
						ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
						WHERE new_competences_pole.Id IN 
							(SELECT Id_Pole 
							FROM new_competences_personne_poste_prestation 
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
							)
						AND Actif=0
						AND new_competences_pole.Id_Prestation=".$PrestationSelect."
						ORDER BY new_competences_pole.Libelle ASC";
				}
				elseif($Menu==2){
					$requetePole="SELECT DISTINCT new_competences_pole.Id, 
							new_competences_pole.Libelle
							FROM rh_personne_mouvement
							LEFT JOIN new_competences_pole
							ON new_competences_pole.Id=rh_personne_mouvement.Id_Pole
							WHERE rh_personne_mouvement.Id_Personne=".$_SESSION['Id_Personne']."
							AND new_competences_pole.Id_Prestation=".$PrestationSelect."
							ORDER BY Libelle ASC";
				}
				elseif($Menu==7){
						$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
								FROM new_competences_pole
								LEFT JOIN new_competences_prestation
								ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
								WHERE Id_Plateforme IN 
								(
									SELECT Id_Plateforme 
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION['Id_Personne']." 
									AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.")
								)
								AND Actif=0
								AND new_competences_pole.Id_Prestation=".$PrestationSelect."
								ORDER BY new_competences_pole.Libelle ASC";

				}
				$resultPole=mysqli_query($bdd,$requetePole);
				$nbPole=mysqli_num_rows($resultPole);
				
				$PoleSelect=$_SESSION['FiltreRHInfosPersonnel_Pole'];
				if($_POST){$PoleSelect=$_POST['pole'];}
				$_SESSION['FiltreRHInfosPersonnel_Pole']=$PoleSelect;
				
				$PoleSelect = 0;
				$Selected = "";
				echo "<option name='0' value='0' Selected></option>";
				if ($nbPole > 0)
				{
					while($row=mysqli_fetch_array($resultPole))
					{
						$selected="";
						if($PoleSelect<>"")
						{if($PoleSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle" <?php if($Menu==2){echo "style='display:none;'";} ?>>
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "People :";} ?>
				<select id="personne" style="width:150px;" name="personne" onchange="submit();">
					<option value='0'></option>
					<?php
						if($Menu==4){
							if(DroitsFormationPlateforme($TableauIdPostesRH)){
								$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
										CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
										FROM rh_personne_mouvement
										LEFT JOIN new_rh_etatcivil
										ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne
										WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) IN 
										(
											SELECT Id_Plateforme 
											FROM new_competences_personne_poste_plateforme
											WHERE Id_Personne=".$_SESSION['Id_Personne']." 
											AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
										)
										AND rh_personne_mouvement.EtatValidation=1
										ORDER BY Personne ASC";
							}
						}
						elseif($Menu==3){
							$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
								CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
								FROM rh_personne_mouvement
								LEFT JOIN new_rh_etatcivil
								ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne
								WHERE CONCAT(rh_personne_mouvement.Id_Prestation,'_',rh_personne_mouvement.Id_Pole) IN 
									(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
									FROM new_competences_personne_poste_prestation 
									WHERE Id_Personne=".$_SESSION["Id_Personne"]."
									AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
									)
								AND rh_personne_mouvement.EtatValidation=1
								ORDER BY Personne ASC";
						}
						elseif($Menu==2){
							$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
									CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
									FROM rh_personne_mouvement
									LEFT JOIN new_rh_etatcivil
									ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne
									WHERE rh_personne_mouvement.Id_Personne=".$_SESSION['Id_Personne']."
									AND rh_personne_mouvement.EtatValidation=1
									ORDER BY Personne ASC";
						}
						elseif($Menu==7){
							
								$requetePersonne="SELECT DISTINCT new_rh_etatcivil.Id, 
										CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
										FROM rh_personne_mouvement
										LEFT JOIN new_rh_etatcivil
										ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne
										WHERE (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) IN 
										(
											SELECT Id_Plateforme 
											FROM new_competences_personne_poste_plateforme
											WHERE Id_Personne=".$_SESSION['Id_Personne']." 
											AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.")
										)
										AND rh_personne_mouvement.EtatValidation=1
										ORDER BY Personne ASC";
							
						}
					
						$resultPersonne=mysqli_query($bdd,$requetePersonne);
						$NbPersonne=mysqli_num_rows($resultPersonne);
						
						$personne=$_SESSION['FiltreRHInfosPersonnel_Personne'];
						if($_POST){$personne=$_POST['personne'];}
						$_SESSION['FiltreRHInfosPersonnel_Personne']= $personne;
						
						while($rowPersonne=mysqli_fetch_array($resultPersonne))
						{
							echo "<option value='".$rowPersonne['Id']."'";
							if ($personne == $rowPersonne['Id']){echo " selected ";}
							echo ">".$rowPersonne['Personne']."</option>\n";
						}
					?>
				</select>
			</td>
			<td width="5%">
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
			</td>
			<td width="5%">
				&nbsp;&nbsp;&nbsp;
				<a href="javascript:OuvreFenetreExcel('<?php echo $Menu; ?>')">
				<img src="../../Images/excel.gif" border="0" alt="Excel" title="Excel">
				</a>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<?php
		$requeteAnalyse="SELECT DISTINCT new_rh_etatcivil.Id,Id_Prestation,Id_Pole ";
		$requete2="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
			rh_personne_mouvement.Id_Pole,TelephoneProFixe,TelephoneProMobil,EmailPro,NumBadge,Matricule,
			Id_Prestation,Id_Pole,
			(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) As Prestation,
			(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) As Pole,
			Adresse,CP,Ville,Email,TelephoneMobil,MatriculeAAA,MatriculeDaher,
			Date_Naissance,Login ";
		$requete="FROM new_rh_etatcivil
			LEFT JOIN rh_personne_mouvement 
			ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
			WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
			AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
			AND rh_personne_mouvement.EtatValidation=1
			AND rh_personne_mouvement.Suppr=0 AND ";
		if($Menu==4){
			if(DroitsFormationPlateforme($TableauIdPostesRH)){
				$requete.="(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) IN 
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
					)";
			}
		}
		elseif($Menu==3){
			$requete.="CONCAT(rh_personne_mouvement.Id_Prestation,'_',rh_personne_mouvement.Id_Pole) IN 
						(SELECT CONCAT(Id_Prestation,'_',Id_Pole) 
						FROM new_competences_personne_poste_prestation 
						WHERE Id_Personne=".$_SESSION["Id_Personne"]."
						AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
						)";
		}
		elseif($Menu==2){
			$requete.="rh_personne_mouvement.Id_Personne=".$_SESSION['Id_Personne']." ";
		}
		elseif($Menu==7){
			
				$requete.="(SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation) IN 
					(
						SELECT Id_Plateforme 
						FROM new_competences_personne_poste_plateforme
						WHERE Id_Personne=".$_SESSION['Id_Personne']." 
						AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.")
					)";
			
		}
		if($_SESSION['FiltreRHInfosPersonnel_Plateforme']<>0){
			$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=rh_personne_mouvement.Id_Prestation)=".$_SESSION['FiltreRHInfosPersonnel_Plateforme']." ";
		}
		if($_SESSION['FiltreRHInfosPersonnel_Prestation']<>0){
			$requete.=" AND rh_personne_mouvement.Id_Prestation=".$_SESSION['FiltreRHInfosPersonnel_Prestation']." ";
			if($_SESSION['FiltreRHInfosPersonnel_Pole']<>0){
				$requete.=" AND rh_personne_mouvement.Id_Pole=".$_SESSION['FiltreRHInfosPersonnel_Pole']." ";
			}
		}
		
		if($Menu<>2){
			if($_SESSION['FiltreRHInfosPersonnel_Personne']<>0){
				$requete.=" AND rh_personne_mouvement.Id_Personne=".$_SESSION['FiltreRHInfosPersonnel_Personne']." ";
			}
		}
		
		$result=mysqli_query($bdd,$requeteAnalyse.$requete);
		
		$requete.="ORDER BY Personne ";
		
		if(isset($_GET['Page'])){$page=$_GET['Page'];}
		else{$page=0;}
		$requete3=" LIMIT ".($page*40).",40";
		$nbResulta=mysqli_num_rows($result);

		$result=mysqli_query($bdd,$requete2.$requete.$requete3);
		$nombreDePages=ceil($nbResulta/40);
		$couleur="#FFFFFF";

	?>
	<tr>
		<td align="center" style="font-size:14px;">
			<?php
				$nbPage=0;
				if($page>1){echo "<b> <a style='color:#00599f;' href='Liste_InformationsPersonnel.php?Menu=".$Menu."&debut=1&Page=0'><<</a> </b>";}
				$valeurDepart=1;
				if($page<=5){
					$valeurDepart=1;
				}
				elseif($page>=($nombreDePages-6)){
					$valeurDepart=$nombreDePages-6;
				}
				else{
					$valeurDepart=$page-5;
				}
				for($i=$valeurDepart; $i<=($valeurDepart+9); $i++){
					if($i<=$nombreDePages){
						if($i==($page+1)){
							echo "<b> [ ".$i." ] </b>"; 
						}	
						else{
							echo "<b> <a style='color:#00599f;' href='Liste_InformationsPersonnel.php?Menu=".$Menu."&debut=1&Page=".($i-1)."'>".$i."</a> </b>";
						}
					}
				}
				if($page<($nombreDePages-1)){echo "<b> <a style='color:#00599f;' href='Liste_InformationsPersonnel.php?Menu=".$Menu."&debut=1&Page=".($nombreDePages-1)."'>>></a> </b>";}
			?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="TableCompetences" align="center" width="100%">
				<tr>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "People";} ?></td>
					<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
					<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle";}else{echo "Pole";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Date de naissance";}else{echo "Birth date";} ?></td>
					<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Date de fin";}else{echo "End date";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Tel. pro fixe";}else{echo "Fixed business phone";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Tel. pro mobile";}else{echo "Mobile business phone";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Email";}else{echo "Email";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "N° badge";}else{echo "Badge number";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "NG/ST";}else{echo "NG/ST";} ?></td>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Login";}else{echo "Login";} ?></td>	
					<?php
						if($Menu==4){
					?>
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Tel perso";}else{echo "Personal phone";} ?></td>	
					<td class="EnTeteTableauCompetences" width="10%"><?php if($_SESSION["Langue"]=="FR"){echo "Email perso";}else{echo "Personal e-mail";} ?></td>	
					<td class="EnTeteTableauCompetences" width="15%"><?php if($_SESSION["Langue"]=="FR"){echo "Adresse";}else{echo "Address";} ?></td>	
					<td class="EnTeteTableauCompetences" width="5%"><?php if($_SESSION["Langue"]=="FR"){echo "CP";}else{echo "Zip code";} ?></td>	
					<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Ville";}else{echo "City";} ?></td>	
					<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule AAA";}else{echo "AAA number";} ?></td>
					<td class="EnTeteTableauCompetences" width="8%"><?php if($_SESSION["Langue"]=="FR"){echo "Matricule Daher";}else{echo "Daher number";} ?></td>
					<?php
						}
					?>					
				</tr>
	<?php			
			if($nbResulta>0){
				while($row=mysqli_fetch_array($result))
				{
					if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
					else{$couleur="#FFFFFF";}
						if($_SESSION["Langue"]=="FR"){
							$reqContrat="SELECT *
							FROM
							(
								SELECT *
								FROM 
									(SELECT Id_Personne,DateDebut,DateFin,
									(SELECT Libelle FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,(@row_number:=@row_number + 1) AS rnk
									FROM rh_personne_contrat
									WHERE Suppr=0
									AND DateDebut<='".date('Y-m-d')."'
									AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
									AND TypeDocument IN ('Nouveau','Avenant')
									ORDER BY Id_Personne, DateDebut DESC) AS table_contrat 
								GROUP BY Id_Personne
							) AS table_contrat2
							WHERE Id_Personne=".$row['Id']."
							";
						}
						else{
							$reqContrat="SELECT *
							FROM
							(
								SELECT *
								FROM 
									(SELECT Id_Personne,DateDebut,DateFin,
									(SELECT LibelleEN FROM rh_typecontrat WHERE rh_typecontrat.Id=Id_TypeContrat) AS TypeContrat,(@row_number:=@row_number + 1) AS rnk
									FROM rh_personne_contrat
									WHERE Suppr=0
									AND DateDebut<='".date('Y-m-d')."'
									AND (DateFin>='".date('Y-m-d')."' OR DateFin<='0001-01-01' )
									AND TypeDocument IN ('Nouveau','Avenant')
									ORDER BY Id_Personne, DateDebut DESC) AS table_contrat 
								GROUP BY Id_Personne
							) AS table_contrat2
							WHERE Id_Personne=".$row['Id']."
							";
						}
						$resultContrat=mysqli_query($bdd,$reqContrat);
						$nbResultaContrat=mysqli_num_rows($resultContrat);
						
						$Contrat="";
						$Du="";
						$Au="";
						if($nbResultaContrat>0){
							$rowContat=mysqli_fetch_array($resultContrat);
							$Contrat=$rowContat['TypeContrat'];
							$Du=AfficheDateJJ_MM_AAAA($rowContat['DateDebut']);
							$Au=AfficheDateJJ_MM_AAAA($rowContat['DateFin']);
						}
				?>
					<tr bgcolor="<?php echo $couleur;?>">
						<td align="center"><a style="color:#3e65fa;" href="javascript:OuvreFenetreModif(<?php echo $Menu; ?>,<?php echo $row['Id']; ?>)"><?php echo stripslashes($row['Personne']);?></a></td>
						<td><?php echo substr(stripslashes($row['Prestation']),0,7);?></td>
						<td><?php echo stripslashes($row['Pole']);?></td>
						<td><?php echo AfficheDateJJ_MM_AAAA($row['Date_Naissance']);?></td>
						<td><?php echo stripslashes($Contrat);?></td>
						<td><?php echo $Au;?></td>
						<td><?php echo stripslashes($row['TelephoneProFixe']);?></td>
						<td><?php echo stripslashes($row['TelephoneProMobil']);?></td>
						<td><?php echo stripslashes($row['EmailPro']);?></td>
						<td><?php echo stripslashes($row['NumBadge']);?></td>
						<td><?php echo stripslashes($row['Matricule']);?></td>
						<td><?php echo stripslashes($row['Login']);?></td>
						
						<?php
							if($Menu==4){
						?>
						<td><?php echo stripslashes($row['TelephoneMobil']);?></td>
						<td><?php echo stripslashes($row['Email']);?></td>
						<td><?php echo stripslashes($row['Adresse']);?></td>
						<td><?php echo stripslashes($row['CP']);?></td>
						<td><?php echo stripslashes($row['Ville']);?></td>
						<td><?php echo stripslashes($row['MatriculeAAA']);?></td>
						<td><?php echo stripslashes($row['MatriculeDaher']);?></td>
						<?php
							}
						?>
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
</body>
</html>
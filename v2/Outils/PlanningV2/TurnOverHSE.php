<?php
require("../../Menu.php");

if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}

?>

<form class="test" action="TurnOverHSE.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#a988b2;">
				<tr>
					<td class="TitrePage">
					<?php
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Suivi des entrées";}else{echo "Entry tracking";}
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
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Plateforme :";}else{echo "Plateform :";} ?>
				<select class="plateforme" style="width:100px;" name="plateforme" onchange="submit();">
				<?php
				$requetePlateforme="SELECT Id, Libelle
					FROM new_competences_plateforme
					WHERE Id=1
					ORDER BY Libelle ASC";
				$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
				$nbPlateforme=mysqli_num_rows($resultPlateforme);
				
				$PlateformeSelect = 0;
				$Selected = "";
				
				$PlateformeSelect=$_SESSION['FiltreRHTurnOverInterim_Plateforme'];
				if($_POST){$PlateformeSelect=$_POST['plateforme'];}
				$_SESSION['FiltreRHTurnOverInterim_Plateforme']=$PlateformeSelect;	
				
				
				if ($nbPlateforme > 0)
				{
					while($row=mysqli_fetch_array($resultPlateforme))
					{
						$selected="";
						if($PlateformeSelect == 0){$PlateformeSelect=$row['Id'];}
						if($PlateformeSelect<>"")
							{if($PlateformeSelect==$row['Id']){$selected="selected";}}
						echo "<option value='".$row['Id']."' ".$selected.">".stripslashes($row['Libelle'])."</option>\n";
					}
				 }
				 else{
					 echo "<option name='0' value='0' Selected></option>";
				 }
				 ?>
				</select>
			</td>
			<td width="15%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Semaine :";}else{echo "Week :";} ?>
				<select id="semaine" name="semaine" onchange="submit();">
					<?php
						$semaine=$_SESSION['FiltreRHTurnOverInterim_Semaine'];
						if($_POST){$semaine=$_POST['semaine'];}
						$_SESSION['FiltreRHTurnOverInterim_Semaine']=$semaine;
						
						for($i=1;$i<=52;$i++){
							echo "<option value='".$i."'";
							if($semaine== $i){echo " selected ";}
							echo ">".$i."</option>\n";
						}
						
						$annee=$_SESSION['FiltreRHTurnOverInterim_Annee'];
						if($_POST){$annee=$_POST['annee'];}
						if($annee==""){$annee=date("Y");}
						$_SESSION['FiltreRHTurnOverInterim_Annee']=$annee;
					?>
				</select>
			</td>
			<td width="8%" class="Libelle">
				&nbsp;<?php if($_SESSION["Langue"]=="FR"){echo "Année :";}else{echo "Year :";} ?>
				<input onKeyUp="nombre(this)" id="annee" name="annee" type="texte" value="<?php echo $annee; ?>" size="5"/>&nbsp;&nbsp;
			</td>
			<td width="40%">
				<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
				<div id="filtrer"></div>
			</td>
		</tr>
		<tr>
			<td height="5"></td>
		</tr>
	</table>
	</td></tr>
	<tr><td height="4"></td></tr>
	<tr>
		<td>
			<table style="width:100%;">
				<tr>
					<td width="50%" class="Libelle" align="center">
						<?php if($_SESSION["Langue"]=="FR"){echo "ENTREES";}else{echo "ENTRY";} ?>
					</td>
				</tr>
				<tr>
						<td width="50%" valign="top">
							<div style="width:100%;height:600px;overflow:auto;">
							<table class="TableCompetences" style="width:50%;" align="center">
								<tr>
									<td class="EnTeteTableauCompetences" width="15%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Personne";}else{echo "Person";} ?></td>
									<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Contrat";}else{echo "Contract";} ?></td>
									<td class="EnTeteTableauCompetences" width="25%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Métier";}else{echo "Job";} ?></td>
									<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation";}else{echo "Site";} ?></td>
									<td class="EnTeteTableauCompetences" width="10%" style="text-decoration:none;color:#000000;font-weight:bold;"><?php if($_SESSION["Langue"]=="FR"){echo "Date entrée";}else{echo "Date entered";} ?></td>
								</tr>
								<?php 
									$week = sprintf('%02d',$semaine);
									$start = strtotime($annee.'W'.$week);
								
									$dateDebut=date('Y-m-d',strtotime('Monday',$start));
									$dateFin=date('Y-m-d',strtotime('Sunday',$start));
									
									$dateDebutS_1=date("Y-m-d",strtotime($dateDebut." -30 day"));
									$dateFinS_1=date("Y-m-d",strtotime($dateFin." -7 day"));
									
									$req="SELECT DISTINCT Id_Personne,
									(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne
									FROM rh_personne_contrat
									WHERE Suppr=0
									AND (Id_TypeContrat=10 OR Id_TypeContrat IN (SELECT Id FROM rh_typecontrat WHERE Suppr=0 AND EstSalarie=1))
									AND DateDebut<='".$dateFin."'
									AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation)=".$PlateformeSelect."
									AND (DateFin>='".$dateDebut."' OR DateFin<='0001-01-01' )
									AND TypeDocument IN ('Nouveau','Avenant')
									AND Id_Personne NOT IN (
										SELECT DISTINCT Id_Personne
										FROM rh_personne_contrat
										WHERE Suppr=0
										AND (Id_TypeContrat=10 OR Id_TypeContrat IN (SELECT Id FROM rh_typecontrat WHERE Suppr=0 AND EstSalarie=1))
										AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation)=".$PlateformeSelect."
										AND DateDebut<='".$dateFinS_1."'
										AND (DateFin>='".$dateDebutS_1."' OR DateFin<='0001-01-01' )
										AND TypeDocument IN ('Nouveau','Avenant')
									)
									ORDER BY Personne ASC";

									$resultEntree=mysqli_query($bdd,$req);
									$nbEntree=mysqli_num_rows($resultEntree);
									
									$couleur="#FFFFFF";
									if($nbEntree>0){
										while($rowpersonne=mysqli_fetch_array($resultEntree))
										{
											if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
											else{$couleur="#FFFFFF";}
											
											
											//Prestation et date d'entrée à cette date 
											$req="SELECT Id_Prestation, Id_Pole,
												(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
												(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole
												FROM rh_personne_mouvement
												WHERE Suppr=0
												AND Id_Personne=".$rowpersonne['Id_Personne']." 
												AND EtatValidation=1
												AND rh_personne_mouvement.DateDebut<='".$dateFin."'
												AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$dateDebut."') ";
											$result=mysqli_query($bdd,$req);
											$nb=mysqli_num_rows($result);
											$Prestation="";
											if($nb>0){
												$rowMouv=mysqli_fetch_array($result);
												$Prestation=substr($rowMouv['Prestation'],0,7);
												if($rowMouv['Id_Pole']>0){
													$Prestation.=" - ".$rowMouv['Pole'];
												}
											}
											
											$DateEntree="";
											$Type="";
											$Metier="";
											$req="SELECT DateDebut,
											(SELECT Libelle FROM rh_typecontrat WHERE Id=Id_TypeContrat) AS TypeContrat,
											(SELECT Libelle FROM new_competences_metier WHERE Id=Id_Metier) AS Metier
											FROM rh_personne_contrat
											WHERE Suppr=0
											AND (Id_TypeContrat=10 OR Id_TypeContrat IN (SELECT Id FROM rh_typecontrat WHERE Suppr=0 AND EstSalarie=1))
											AND Id_Personne=".$rowpersonne['Id_Personne']."
											AND DateDebut<='".$dateFin."'
											AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=rh_personne_contrat.Id_Prestation)=".$PlateformeSelect."
											AND (DateFin>='".$dateDebut."' OR DateFin<='0001-01-01' )
											AND TypeDocument IN ('Nouveau','Avenant')
											ORDER BY DateDebut ASC";
											$resultDate=mysqli_query($bdd,$req);
											$nbDate=mysqli_num_rows($resultDate);
											if($nbDate>0){
												$rowDate=mysqli_fetch_array($resultDate);
												$DateEntree=AfficheDateJJ_MM_AAAA($rowDate['DateDebut']);
												$Type=$rowDate['TypeContrat'];
												$Metier=$rowDate['Metier'];
											}
											
											echo "<tr bgcolor=".$couleur.">";
												echo "<td>".$rowpersonne['Personne']."</td>";
												echo "<td>".$Type."</td>";
												echo "<td>".$Metier."</td>";
												echo "<td>".$Prestation."</td>";
												echo "<td>".$DateEntree."</td>";
											echo "</tr>";
										}
									}
								?>
							</table>
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
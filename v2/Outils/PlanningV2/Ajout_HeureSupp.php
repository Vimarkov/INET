<?php
require("../../Menu.php");
?>
<script language="javascript" src="DemandeHS.js"></script>
<script type="text/javascript">
	function VerifDate()
	{
		//Si date < Mois E/C -2 OU date < Mois E/C -1 ET Date E/C >=10 du mois alors IMPOSSIBLE = Efface les infos 
		myDateDebut = formulaire.Date.value;
		myDateDebut2 = myDateDebut.split("-");
		dateJJJJMM="";
		if (myDateDebut2.length == 1){
			myDateDebut = myDateDebut.split("/");
			dateJJJJMM=myDateDebut[2]+"/"+myDateDebut[1];
		}
		else{
			myDateDebut = myDateDebut.split("-");
			dateJJJJMM=myDateDebut[0]+"/"+myDateDebut[1];
		}

		var ladate=new Date();
		ladate.setMonth(ladate.getMonth()-2);
		mois=ladate.getMonth()+1;
		if(mois<10){mois="0"+mois;}
		date_2Mois=ladate.getFullYear()+"/"+(mois);

		var ladate=new Date();
		mois=ladate.getMonth()+1;
		if(mois<10){mois="0"+mois;}
		date_10=ladate.getFullYear()+"-"+mois+"-10";
		
		var ladate=new Date();
		mois=ladate.getMonth()+1;
		if(mois<10){mois="0"+mois;}
		jour=ladate.getDate();
		if(jour<10){jour="0"+jour;}
		date_Jour=ladate.getFullYear()+"-"+mois+"-"+jour;
		
		var ladate=new Date();
		ladate.setMonth(ladate.getMonth()-1);
		mois=ladate.getMonth()+1;
		if(mois<10){mois="0"+mois;}
		date_1Mois=ladate.getFullYear()+"/"+(mois);
		
		if(formulaire.Menu.value!=4){
			if(dateJJJJMM<=date_2Mois || (dateJJJJMM<date_1Mois && date_Jour>=date_10)){
				formulaire.Date.value="";
			}
		}
	}
</script>
<?php
$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
$bEnregistrement=false;
if($_POST){
	if($_SESSION['Id_Personne']<>""){
		$Personne="";
		if(isset($_POST['PersonneSelect']))
		{
			$PersonneSelect = $_POST['PersonneSelect'];
			for($i=0;$i<sizeof($PersonneSelect);$i++)
			{
				if(isset($PersonneSelect[$i])){$Personne.=$PersonneSelect[$i].";";}
			}
		}
		
		$TabPersonne = preg_split("/[;]+/", $Personne);

		for($i=0;$i<sizeof($TabPersonne)-1;$i++){
			$requete="INSERT INTO rh_personne_hs ";
			
			$requete.="(Id_Prestation,Id_Pole,Id_Personne,Nb_Heures_Jour,Nb_Heures_Nuit,DateHS,Motif,
						Id_Responsable1,Date1,
						Id_Responsable2,Date2,Etat2,
						Id_Responsable3,Date3,Etat3,
						Id_Responsable4,Date4,Etat4,
						Id_RH,DateRH,DatePriseEnCompteRH,Avant25Mois) VALUES ";
			

			if($_POST['Menu']==4){
				$requete.="(".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",".$TabPersonne[$i].",".$_POST['Nb_Heures_Jour'].",".$_POST['Nb_Heures_Nuit'].",'".TrsfDate($_POST['Date'])."','".addslashes($_POST['Motif'])."',
						0,'".date('Y-m-d')."',
						0,'".date('Y-m-d')."',1,
						0,'".date('Y-m-d')."',1,
						0,'".date('Y-m-d')."',1,
						".$_SESSION['Id_Personne'].",'".TrsfDate($_POST['Date'])."','".date('Y-m-d')."',0)";
			}
			elseif(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
				if(DateAvant25DuMois(TrsfDate($_POST['Date']),date('Y-m-d'))==1){
					$partieRH="'".TrsfDate($_POST['Date'])."','".date('Y-m-d')."',1";
				}
				else{
					$partieRH="'0001-01-01','0001-01-01',0";
				}
				$requete.="(".$_POST['Id_Prestation'].",".$_POST['Id_Pole'].",".$TabPersonne[$i].",".$_POST['Nb_Heures_Jour'].",".$_POST['Nb_Heures_Nuit'].",'".TrsfDate($_POST['Date'])."','".addslashes($_POST['Motif'])."',
						".$_SESSION['Id_Personne'].",'".date('Y-m-d')."',
						".$_SESSION['Id_Personne'].",'".date('Y-m-d')."',1,
						".$_SESSION['Id_Personne'].",'".date('Y-m-d')."',1,
						".$_SESSION['Id_Personne'].",'".date('Y-m-d')."',1,
						0,".$partieRH.")";
			}
			else{
				$prestation=0;
				$pole=0;
				if($_POST['Id_Prestation']==-1){
					$prestationPole=PrestationPole_Personne(TrsfDate($_POST['Date']),$TabPersonne[$i]);
					if($prestationPole<>0){
						$tab=explode("_",$prestationPole);
						$prestation=$tab[0];
						$pole=$tab[1];
					}
				}
				else{
					$prestation=$_POST['Id_Prestation'];
					$pole=$_POST['Id_Pole'];
				}
				
				$estValideN3=0;
				$requete.="(".$prestation.",".$pole.",".$TabPersonne[$i].",".$_POST['Nb_Heures_Jour'].",".$_POST['Nb_Heures_Nuit'].",'".TrsfDate($_POST['Date'])."','".addslashes($_POST['Motif'])."',";
				$requete.="".$_SESSION['Id_Personne'].",'".date('Y-m-d')."',";
				if(DroitsPrestationPole(array($IdPosteChefEquipe),$prestation,$pole)){
					$requete.="".$_SESSION['Id_Personne'].",'".date('Y-m-d')."',1,";
				}
				else{$requete.="0,'0001-01-01',0,";}
				if(DroitsPrestationPole(array($IdPosteCoordinateurEquipe),$prestation,$pole)){
					$requete.="".$_SESSION['Id_Personne'].",'".date('Y-m-d')."',1,";
					$estValideN3=1;
				}
				else{$requete.="0,'0001-01-01',0,";}
				if(DroitsPrestationPole(array($IdPosteCoordinateurProjet),$prestation,$pole)){
					$requete.="".$_SESSION['Id_Personne'].",'".date('Y-m-d')."',1,";
					if(DateAvant25DuMois(TrsfDate($_POST['Date']),date('Y-m-d'))==1){
						$partieRH="'".TrsfDate($_POST['Date'])."','".date('Y-m-d')."',1";
					}
					else{
						$partieRH="'0001-01-01','0001-01-01',0";
					}
				}
				else{
					$nbHeures=$_POST['Nb_Heures_Jour']+$_POST['Nb_Heures_Nuit'];
					if($estValideN3==1 && NombreHeuresJournee($TabPersonne[$i],TrsfDate_($_POST['Date']))+$nbHeures<=10
					&& NombreHeuresSemaine($TabPersonne[$i],TrsfDate_($_POST['Date']))+$nbHeures<=48){
						$requete.="0,'".date('Y-m-d')."',1,";
						if(DateAvant25DuMois(TrsfDate($_POST['Date']),date('Y-m-d'))==1){
							$partieRH="'".TrsfDate($_POST['Date'])."','".date('Y-m-d')."',1";
						}
						else{
							$partieRH="'0001-01-01','0001-01-01',0";
						}
					}
					else{
						$requete.="0,'0001-01-01',0,";
						$partieRH="'0001-01-01','0001-01-01',0";
					}
				}
				$requete.="0,".$partieRH.")";
			}
			$result=mysqli_query($bdd,$requete);
		}
		$bEnregistrement=true;
	}
}

if($_GET){$Menu=$_GET['Menu'];}
else{$Menu=$_POST['Menu'];}
?>

<form id="formulaire" class="test" action="Ajout_HeureSupp.php" method="post" onsubmit=" return selectall();">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<input type="hidden" name="Menu" id="Menu" value="<?php echo $Menu; ?>" />
	<tr>
		<td colspan="5">
			<table class ="GeneralPage" style="width:100%; border-spacing:0; background-color:#11b9a7;">
				<tr>
					<td class="TitrePage">
					<?php
						echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/PlanningV2/Tableau_De_Bord.php?Menu=".$Menu."'>";
						if($_SESSION['Langue']=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
						else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
						echo "</a>";
						echo "&nbsp;&nbsp;&nbsp;";
						if($_SESSION["Langue"]=="FR"){echo "Déclarer des heures supplémentaires";}else{echo "Declare overtime";}
					?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<?php 
		if($bEnregistrement==true){
			echo "<tr>";
			echo "<td colspan='5' align='center' bgcolor='#ff7777' style='font-weight:bold;'>";
			if($_SESSION["Langue"]=="FR"){
				echo "Heures supplémentaires créées";
			}
			else{
				echo "Overtime created";
			}
			echo "</td>";
			echo "</tr>
				<tr>
					<td height='5'></td>
				</tr>";
		}
	?>
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td>
					<table class="TableCompetences" align="center" width="70%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
							<td width="30%">
									<select name="Id_Prestation" id="Id_Prestation" onchange="Recharge_Responsables();">
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
															AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
														)
													AND Active=0
													ORDER BY Libelle ASC";
											}
										}
										else{
											if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
												$requeteSite="SELECT Id, Libelle
													FROM new_competences_prestation
													WHERE Id_Plateforme IN 
														(
															SELECT Id_Plateforme 
															FROM new_competences_personne_poste_plateforme
															WHERE Id_Personne=".$_SESSION['Id_Personne']." 
															AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
														)
													AND Active=0
													ORDER BY Libelle ASC";
											}
											else{
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
										}
										$resultsite=mysqli_query($bdd,$requeteSite);
										while($rowsite=mysqli_fetch_array($resultsite))
										{
											echo "<option value='".$rowsite['Id']."'>";
											echo str_replace("'"," ",stripslashes($rowsite['Libelle']))."</option>\n";
										}
										if($Menu<>4){
											echo "<option value='-1'>";
											if($_SESSION["Langue"]=="FR"){
												echo "Toutes</option>\n";
											}
											else{
												echo "All</option>\n";
											}
										}
									?>
								</select>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Pôle :";}else{echo "Pole :";} ?></td>
							<td width="30%">
								<select name="Id_Pole" id="Id_Pole" onchange="Recharge_ResponsablesP();">
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
														ORDER BY new_competences_pole.Libelle ASC";
											}
										}
										else{
											if(DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))){
												$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
														FROM new_competences_pole
														LEFT JOIN new_competences_prestation
														ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
														WHERE Id_Plateforme IN 
														(
															SELECT Id_Plateforme 
															FROM new_competences_personne_poste_plateforme
															WHERE Id_Personne=".$_SESSION['Id_Personne']." 
															AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
														)
														AND Actif=0
														ORDER BY new_competences_pole.Libelle ASC";
											}
											else{
												$requetePole="SELECT new_competences_pole.Id, new_competences_pole.Id_Prestation, new_competences_pole.Libelle
													FROM new_competences_pole
													LEFT JOIN new_competences_prestation
													ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
													WHERE CONCAT(new_competences_prestation.Id,'_',new_competences_pole.Id) IN 
														(SELECT CONCAT(Id_Prestation,'_',Id_Pole)
														FROM new_competences_personne_poste_prestation 
														WHERE Id_Personne=".$_SESSION["Id_Personne"]."
														AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
														)
													AND Actif=0
													ORDER BY new_competences_pole.Libelle ASC";
											}
										}
										$resultPole=mysqli_query($bdd,$requetePole);
										$nbPole=mysqli_num_rows($resultPole);
										if($nbPole>0){
											$i=0;
											while($rowPole=mysqli_fetch_array($resultPole)){
												echo "<option value='".$rowPole['Id']."'>";
												echo str_replace("'"," ",$rowPole['Libelle'])."</option>\n";
												 echo "<script>Liste_Pole_Prestation[".$i."] = new Array(".$rowPole[0].",".$rowPole[1].",'".$rowPole[2]."');</script>";
												 $i+=1;
											}
										}
										else{
											echo "<option value='0'></option>";
										}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="15%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personnes :";}else{echo "People :";} ?></td>
							<td width="35%" valign="top">
								<select name="Id_Personne" id="Id_Personne" multiple size="15" onDblclick="ajouter();">
								<?php
								$rq2="";
								if($Menu<>4 && DroitsFormationPlateforme(array($IdPosteResponsablePlateforme))==0){
									$rq2="AND new_rh_etatcivil.Id<>".$_SESSION['Id_Personne']." ";
								}
								
								$laDateFin=date('Y-m-d');
								if($Menu==4){
									$laDateFin=date("Y-m-d",strtotime(date('Y-m-d')." -3 month"));
								}
								$rq="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
									rh_personne_mouvement.Id_Prestation, rh_personne_mouvement.Id_Pole
									FROM new_rh_etatcivil
									LEFT JOIN rh_personne_mouvement 
									ON new_rh_etatcivil.Id=rh_personne_mouvement.Id_Personne 
									WHERE rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
									AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".$laDateFin."')
									AND rh_personne_mouvement.EtatValidation=1 
									AND rh_personne_mouvement.Suppr=0
									".$rq2."
									ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
								$resultpersonne=mysqli_query($bdd,$rq);
								$i=0;
								while($rowpersonne=mysqli_fetch_array($resultpersonne))
								{
									echo "<option value='".$rowpersonne['Id']."'>".str_replace("'"," ",$rowpersonne['Personne'])."</option>\n";
									echo "<script>Liste_Personne[".$i."] = new Array(".$rowpersonne['Id'].",'".str_replace("'"," ",$rowpersonne['Personne'])."','".$rowpersonne['Id_Prestation']."','".$rowpersonne['Id_Pole']."');</script>";
									$i+=1;
								}
								?>
								</select>
							</td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personnes sélectionnées (double-clic) :";}else{echo "Selected people (double-click) :";} ?></td>
							<td width="30%" valign="top">
								<select name="PersonneSelect[]" id="PersonneSelect" multiple size="15" onDblclick="effacer();"></select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle">Date : </td>
							<td width="30%"><input type="date" id="Date" name="Date" size="10" value="" onchange="VerifDate();"></td>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Nb Heures supp de jour (6h->21h) :";}else{echo "Nb hours day (6h-> 21h):";} ?></td>
							<td width="30%">
								<select name="Nb_Heures_Jour" id="Nb_Heures_Jour">
									<?php
									for($h=0;$h<=15;$h+=0.25)
									{
										echo "<option value='".$h."'>";
										echo $h."</option>";
									}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td class="Libelle" width="10%"></td>
							<td class="Libelle" width="30%"></td>
							<td class="Libelle" width="15%"><?php if($_SESSION["Langue"]=="FR"){echo "Nb Heures supp de nuit (21h->6h) :";}else{echo "Nb Hours night (21h-> 6h) :";} ?></td>
							<td width="30%">
								<select name="Nb_Heures_Nuit" id="Nb_Heures_Nuit">
									<?php
									for($h=0;$h<=15;$h+=0.25)
									{
										echo "<option value='".$h."'>";
										echo $h."</option>";
									}
									?>
								</select>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td width="10%" class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Motif :";}else{echo "Motif :";} ?></td>
							<td width="30%" colspan="6">
								<textarea name="Motif" cols="100" rows="4" style="resize:none;"></textarea>
							</td>
						</tr>
						<tr>
							<td><?php if($_SESSION["Langue"]=="FR"){echo "Validateur :";}else{echo "Validator :";} ?></td>
							<td colspan="5">
								<div id="PostesValidateurs">
								<?php
									$requetePersonnePoste="SELECT DISTINCT new_competences_personne_poste_prestation.Id_Prestation, new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Backup, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) as NomPrenom, new_competences_personne_poste_prestation.Id_Pole";
									$requetePersonnePoste.=" FROM new_competences_personne_poste_prestation, new_rh_etatcivil";
									$requetePersonnePoste.=" WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id";
									$requetePersonnePoste.=" AND new_competences_personne_poste_prestation.Id_Poste >= 1";
									if($Menu==4){
										$requetePersonnePoste.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN 
												(
													SELECT Id_Plateforme 
													FROM new_competences_personne_poste_plateforme
													WHERE Id_Personne=".$_SESSION['Id_Personne']." 
													AND Id_Poste IN (".$IdPosteResponsableRH.",".$IdPosteAssistantRH.",".$IdPosteAideRH.")
												) ";
									}
									else{
										$requetePersonnePoste.=" AND (
													(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN 
													(
														SELECT Id_Plateforme 
														FROM new_competences_personne_poste_plateforme
														WHERE Id_Personne=".$_SESSION['Id_Personne']." 
														AND Id_Poste IN (".$IdPosteResponsablePlateforme.")
													)
												OR 
													Id_Prestation IN 
													(SELECT Id_Prestation 
													FROM new_competences_personne_poste_prestation 
													WHERE Id_Personne=".$_SESSION["Id_Personne"]."
													AND Id_Poste IN (".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.") 
													)
												)
												";
									}
									$requetePersonnePoste.=" ORDER BY new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Backup ASC";
									$resultPersonnePoste=mysqli_query($bdd,$requetePersonnePoste);
									$i=0;
									echo "<script>";
									while($rowPersonnePoste=mysqli_fetch_row($resultPersonnePoste))
									{
										 echo "Liste_Poste_Prestation[".$i."] = new Array(".$rowPersonnePoste[0].",".$rowPersonnePoste[1].",".$rowPersonnePoste[2].",'".$rowPersonnePoste[3]."',".$rowPersonnePoste[4].");\n";
										 $i+=1;
									}
									echo "</script>";
								?>
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="10" align="center">
								<div id="Contrat">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="10" align="center">
								<div id="HS">
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="10" align="center">
								<div id="10HEURES">
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="10" align="center">
								<div id="48HEURES">
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="10" align="center">
								<div id="ASJourNonT">
								</div>
							</td>
						</tr>
						<tr style="display:none;">
							<td colspan="10" align="center">
								<input id="AS" value="" />
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="10" align="center">
								<div id="CongesAbsences">
								</div>
							</td>
						</tr>
						<tr><td height="4"></td></tr>
						<tr>
							<td colspan="6" align="center">
								<input class="Bouton" type="submit" value="<?php if($_SESSION["Langue"]=="FR"){echo "Ajouter";}else{echo "Add";} ?>"/>
							</td>
						</tr>
					</table>
				</td></tr>
			</table>
		</td>
	</tr>
	<tr><td height="150"></td></tr>
</table>
</form>
<?php
	echo "<script>Recharge_Responsables();</script>";
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
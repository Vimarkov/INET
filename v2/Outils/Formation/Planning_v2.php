<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function ContenuSession(Id,ancre)
	{
		var w=window.open("Contenu_Session.php?Id="+Id+"&Id_Plateforme="+document.getElementById('Id_Plateforme').value+"&ancre="+ancre,"PageSession","status=no,menubar=no,scrollbars=yes,width=1400,height=700");
		w.focus();
	}
	function Contenu2Session(Id,ancre)
	{
		var w=window.open("Contenu2_Session.php?Id="+Id+"&Id_Plateforme="+document.getElementById('Id_Plateforme').value+"&ancre="+ancre,"PageSession2","status=no,menubar=no,scrollbars=yes,width=1400,height=700");
		w.focus();
	}
</script>
<?php
$transparent="#ffffff";
$rouge="#ff3b3b";
$bleu="#48a8f2";
$vert="#5cec4e";
?>

<?php Ecrire_Code_JS_Init_Date(); ?>
<form id="formulaire" action="Planning_v2.php" method="post">
<input type="hidden" id="Langue" name="Langue" value="<?php echo $LangueAffichage; ?>">
<table style="border-spacing:0;">
<tr>
<td width="100%">
	<table style="width:100%; border-spacing:0; align:center;">
	<tr><td colspan="2">
		<table style="width:100%; border-spacing:0; align:center;">
			<tr>
				<td colspan="5">
					<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#10b9a6;">
						<tr>
							<td class="TitrePage">
							<?php
							echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
							if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
							else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
							echo "</a>";
							echo "&nbsp;&nbsp;&nbsp;";
								
							if($LangueAffichage=="FR"){echo "Planning des sessions";}else{echo "Scheduling sessions";}
							?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table style="width:100%; border-spacing:0; align:center;" class="GeneralInfo">
						<tr>
							<td class="Libelle" width="8%">&nbsp;<?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
							<td width="8%">
								<select id="Id_Plateforme" name="Id_Plateforme" onchange="submit()">
									<?php
									$Plateforme=0;
									$reqPla="SELECT DISTINCT Id_Plateforme, 
										(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle 
										FROM new_competences_personne_poste_plateforme 
										WHERE Id_Poste 
											IN (".$IdPosteAssistantFormationInterne.",".$IdPosteAssistantFormationExterne.",".$IdPosteAssistantFormationTC.",".$IdPosteResponsableFormation.",".$IdPosteFormateur.",".$IdPosteResponsableRH.") 
										AND Id_Personne=".$IdPersonneConnectee." 
										ORDER BY Libelle";
									$resultPlateforme=mysqli_query($bdd,$reqPla);
									$nbFormation=mysqli_num_rows($resultPlateforme);
									if($nbFormation>0)
									{
										$selected="";
										if(isset($_POST['Id_Plateforme']))
										{
											if($_POST['Id_Plateforme']==0){$selected="selected";}
										}
										if(isset($_GET['Id_Plateforme']))
										{
											if($_GET['Id_Plateforme']==0){$selected="selected";}
										}
										while($rowplateforme=mysqli_fetch_array($resultPlateforme))
										{
											$selected="";
											if(isset($_POST['Id_Plateforme']))
											{
												if($_POST['Id_Plateforme']==$rowplateforme['Id_Plateforme']){$selected="selected";}
											}
											if(isset($_GET['Id_Plateforme']))
											{
												if($_GET['Id_Plateforme']==$rowplateforme['Id_Plateforme']){$selected="selected";}
											}
											echo "<option value='".$rowplateforme['Id_Plateforme']."' ".$selected.">".$rowplateforme['Libelle']."</option>\n";
											if($Plateforme==0){$Plateforme=$rowplateforme['Id_Plateforme'];}
										}
									}
									if(isset($_POST['Id_Plateforme'])){$Plateforme=$_POST['Id_Plateforme'];}
									if(isset($_GET['Id_Plateforme'])){$Plateforme=$_GET['Id_Plateforme'];}
									?>
								</select>
							</td>
							<td class="Libelle" width="60%" colspan="7">
								&nbsp;
								<?php
									if($LangueAffichage=="FR"){echo "Date de début : ";}else{echo "Start date : ";}
									
									if(isset($_SESSION['FiltreFormPlanning_DateDebut']))
									{
										$dateDebut=AfficheDateFR($_SESSION['FiltreFormPlanning_DateDebut']);
										$dateDeFin=AfficheDateFR($_SESSION['FiltreFormPlanning_DateFin']);
									}
									else
									{
										$dateDebut=AfficheDateFR($DateJour);
										$dateDeFin=date("Y-m-d",strtotime(TrsfDate_($dateDebut)." + 1 month"));
									}
									
									$MoisPrecedent=date("Y-m-d",strtotime(TrsfDate_($dateDebut)." - 1 month"));
									$MoisSuivant=date("Y-m-d",strtotime(TrsfDate_($dateDebut)." + 1 month"));
									if(isset($_GET['DateDeDebut']))
									{
										$dateDebut=$_GET['DateDeDebut'];
										$_SESSION['FiltreFormPlanning_DateDebut']=TrsfDate_($dateDebut);
										$MoisPrecedent=$dateDebut;
										$MoisSuivant=$dateDeFin;
									}
									elseif(isset($_POST['DateDeDebut']))
									{
										$dateDebut=$_POST['DateDeDebut'];
										$_SESSION['FiltreFormPlanning_DateDebut']=TrsfDate_($dateDebut);
										
										$MoisPrecedent=$dateDebut;
										$MoisSuivant=$dateDeFin;
									}
									if(isset($_POST['DateDeFin']))
									{
										$dateDeFin=$_POST['DateDeFin'];
										$_SESSION['FiltreFormPlanning_DateFin']=TrsfDate_($dateDeFin);
									}
									if(isset($_POST['MoisPrecedent']))
									{
										$dateDebut=AfficheDateFR(date("Y-m-d",strtotime(TrsfDate_($dateDebut)." - 1 month")));
										$dateDeFin=AfficheDateFR(date("Y-m-d",strtotime(TrsfDate_($dateDeFin)." - 1 month")));
										
										$_SESSION['FiltreFormPlanning_DateDebut']=TrsfDate_($dateDebut);
										$_SESSION['FiltreFormPlanning_DateFin']=TrsfDate_($dateDeFin);
										
										$MoisPrecedent=$dateDebut;
										$MoisSuivant=$dateDeFin;
										
										$MoisPrecedent=date("Y-m-d",strtotime(TrsfDate_($MoisPrecedent)." - 1 month"));
										$MoisSuivant=date("Y-m-d",strtotime(TrsfDate_($MoisSuivant)." - 1 month"));
									}
									elseif(isset($_POST['MoisSuivant']))
									{
										$dateDebut=AfficheDateFR(date("Y-m-d",strtotime(TrsfDate_($dateDebut)." + 1 month")));
										$dateDeFin=AfficheDateFR(date("Y-m-d",strtotime(TrsfDate_($dateDeFin)." + 1 month")));
										
										$_SESSION['FiltreFormPlanning_DateDebut']=TrsfDate_($dateDebut);
										$_SESSION['FiltreFormPlanning_DateFin']=TrsfDate_($dateDeFin);
										
										$MoisPrecedent=date("Y-m-d",strtotime(TrsfDate_($MoisPrecedent)." + 1 month"));
										$MoisSuivant=date("Y-m-d",strtotime(TrsfDate_($MoisSuivant)." + 1 month"));
									}
								?>
								<input type="date" style="text-align:center;" id="DateDeDebut" name="DateDeDebut" size="10" value="<?php echo $dateDebut; ?>">
								<input class="Bouton" name="BtnDateDebut" size="10" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Valider";}else{echo "Validate";}?>">
								&nbsp;
								<?php if($LangueAffichage=="FR"){echo "Date de fin";}else{echo "End date";}?> :
								<input type="date" style="text-align:center;" id="DateDeFin" name="DateDeFin"  size="10" value="<?php echo $dateDeFin; ?>">
								&nbsp;
								<input class="Bouton" name="MoisPrecedent" size="10" type="submit" alt="Mois précédent" value="<< <?php echo $MoisPrecedent; ?>">
								<input class="Bouton" name="MoisSuivant" size="10" type="submit" alt="Mois suivant" value="<?php echo $MoisSuivant; ?> >>">
							</td>
							<td width=5%>
								&nbsp;<a style="text-decoration:none;" href="javascript:OuvreFenetrePlanningExport();">
									<img src="../../Images/excel.gif" border="0" alt="Excel" title="Export Excel">
								</a>&nbsp;
							</td>
						</tr>
						<tr>
							<td class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Formateur";}else{echo "Former";}?> : </td>
							<td>
								<?php
									$formateur=$_SESSION['FiltreFormPlanning_Formateur'];
									if(isset($_POST['formateur'])){$formateur=$_POST['formateur'];}
									if(isset($_GET['formateur'])){$formateur=$_GET['formateur'];}
									$_SESSION['FiltreFormPlanning_Formateur']=$formateur;
								?>
								<select name="formateur" id="formateur" onchange="submit()">
									<option value="0" selected></option>
									<option value="-1" <?php if($formateur==-1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Non défini";}else{echo "Not defined";}?></option>
								<?php
									$req="SELECT DISTINCT Id, CONCAT(Nom,' ',Prenom) AS Personne ";
									$req.="FROM new_rh_etatcivil ";
									$req.="WHERE Id IN (SELECT Id_Personne FROM new_competences_personne_poste_plateforme WHERE Id_Poste=21 AND Id_Plateforme=".$Plateforme.") ORDER BY Personne ASC";
									$resultFormateur=mysqli_query($bdd,$req);
									$nbFormateurs=mysqli_num_rows($resultFormateur);
									if($nbFormateurs>0)
									{
										while($rowFormateur=mysqli_fetch_array($resultFormateur))
										{
											$selected="";
											if($formateur==$rowFormateur['Id']){$selected="selected";}
											echo "<option value='".$rowFormateur['Id']."' ".$selected.">".$rowFormateur['Personne']."</option>\n";
										}
									}
								?>
								</select>
							</td>
							<td class="Libelle" width="5%">&nbsp;<?php if($LangueAffichage=="FR"){echo "Lieu";}else{echo "Place";}?> : </td>
							<td  width="10%" align="left">
								<?php
									$lieu=$_SESSION['FiltreFormPlanning_Lieu'];
									if(isset($_POST['lieu'])){$lieu=$_POST['lieu'];}
									if(isset($_GET['lieu'])){$lieu=$_GET['lieu'];}
									$_SESSION['FiltreFormPlanning_Lieu']=$lieu;
								?>
								<select name="lieu" id="lieu" onchange="submit()">
									<option value="0" selected></option>
									<option value="-1" <?php if($lieu==-1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Non défini";}else{echo "Not defined";}?></option>
								<?php
									$resultLieu=mysqli_query($bdd,"SELECT Id, Libelle FROM form_lieu WHERE Id_Plateforme=".$Plateforme." AND Suppr=0 ORDER BY Libelle ASC");
									$nbLieux=mysqli_num_rows($resultLieu);
									if($nbLieux>0)
									{
										while($rowLieu=mysqli_fetch_array($resultLieu))
										{
											$selected="";
											if($lieu==$rowLieu['Id']){$selected="selected";}
											echo "<option value='".$rowLieu['Id']."' ".$selected.">".$rowLieu['Libelle']."</option>\n";
										}
									}
								?>
								</select>
							</td>
							<td class="Libelle" width="8%">&nbsp;<?php if($LangueAffichage=="FR"){echo "Horaires définis";}else{echo "Training Schedule Defined";}?> : </td>
							<td  width="10%" align="left">
								<?php
									$horaires=$_SESSION['FiltreFormPlanning_Horaire'];
									if(isset($_POST['horaires'])){$horaires=$_POST['horaires'];}
									if(isset($_GET['horaires'])){$horaires=$_GET['horaires'];}
									$_SESSION['FiltreFormPlanning_Horaire']=$horaires;
								?>
								<select name="horaires" id="horaires" onchange="submit()">
									<option value="-1" selected></option>
									<option value="0" <?php if($horaires==0){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Non";}else{echo "No";}?></option>
									<option value="1" <?php if($horaires==1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Oui";}else{echo "Yes";}?></option>
								</select>
							</td>
							<td class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Formation";}else{echo "Training";}?> : </td>
							<td width="18%" align="left">
								<?php
									$formation=$_SESSION['FiltreFormPlanning_Formation'];
									if(isset($_POST['formation'])){$formation=$_POST['formation'];}
									if(isset($_GET['formation'])){$formation=$_GET['formation'];}
									$_SESSION['FiltreFormPlanning_Formation']=$formation;
								?>
								<input name="formation" id="formation" size="40" value="<?php echo $formation; ?>" />
							</td>
							<td width="3%">
								<input class="Bouton" name="BtnForm" size="10" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Valider";}else{echo "Validate";}?>">
							</td>
						</tr>
						<tr>
							<td class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Type d'affichage";}else{echo "Display Type";}?> :</td>
							<td>
								<?php
									$typeAffichage=$_SESSION['FiltreFormPlanning_TypeAffichage'];
									if(isset($_POST['typeAffichage'])){$typeAffichage=$_POST['typeAffichage'];}
									if(isset($_GET['typeAffichage'])){$typeAffichage=$_GET['typeAffichage'];}
								?>
								<select name="typeAffichage" id="typeAffichage" onchange="submit()">
									<option value="formateur" <?php if($typeAffichage=="formateur"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Formateur";}else{echo "Former";}?></option>
									<option value="session" <?php if($typeAffichage=="session"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Session";}else{echo "Session";}?></option>
								</select>
							</td>
							<td class="Libelle" width="5%">&nbsp;<?php if($LangueAffichage=="FR"){echo "Organisme";}else{echo "Organism";}?> : </td>
							<td  width="10%" align="left">
								<?php
									$organisme=$_SESSION['FiltreFormPlanning_Organisme'];
									if(isset($_POST['organisme'])){$organisme=$_POST['organisme'];}
									if(isset($_GET['organisme'])){$organisme=$_GET['organisme'];}
									$_SESSION['FiltreFormPlanning_Organisme']=$organisme;
								?>
								<select name="organisme" id="organisme" onchange="submit()">
									<option value="0" selected></option>
								<?php
									$resultOrganisme=mysqli_query($bdd,"SELECT Id, Libelle FROM form_organisme WHERE Id_Plateforme=".$Plateforme." AND Suppr=0 ORDER BY Libelle ASC");
									$nbOrganisme=mysqli_num_rows($resultOrganisme);
									if($nbOrganisme>0)
									{
										while($rowOrganisme=mysqli_fetch_array($resultOrganisme))
										{
											$selected="";
											if($organisme==$rowOrganisme['Id']){$selected="selected";}
											echo "<option value='".$rowOrganisme['Id']."' ".$selected.">".$rowOrganisme['Libelle']."</option>\n";
										}
									}
								?>
								</select>
							</td>
							<td class="Libelle">&nbsp;<?php if($LangueAffichage=="FR"){echo "Etat";}else{echo "State";}?> :</td>
							<td>
								<?php
									$etat=$_SESSION['FiltreFormPlanning_Etat'];
									if(isset($_POST['etatAffichage'])){$etat=$_POST['etatAffichage'];}
									if(isset($_GET['etatAffichage'])){$etat=$_GET['etatAffichage'];}
									$_SESSION['FiltreFormPlanning_Etat']=$etat;
								?>
								<select name="etatAffichage" id="etatAffichage" onchange="submit()">
									<option value="" <?php if($etat==""){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "";}else{echo "";}?></option>
									<option value="annule" <?php if($etat=="annule"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Sessions annulées";}else{echo "Sessions canceled";}?></option>
									<option value="confirme" <?php if($etat=="confirme"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Sessions confirmées";}else{echo "Confirmed Sessions";}?></option>
									<option value="complete" <?php if($etat=="complete"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Sessions complètes";}else{echo "Complete session";}?></option>
									<option value="incomplete" <?php if($etat=="incomplete"){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Sessions incomplètes";}else{echo "Incomplete sessions";}?></option>
								</select>
							</td>
							<td class="Libelle" width="5%" ><?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?> : </td>
							<td colspan="4">
								<?php 
									$resultTypeFormation=mysqli_query($bdd,"SELECT Id, Libelle FROM form_typeformation WHERE Suppr=0 ORDER BY Libelle ASC");
									$checked="";
									while($rowTypeFormation=mysqli_fetch_array($resultTypeFormation))
									{
										if($_POST)
										{
											if(isset($_POST['Type_'.$rowTypeFormation['Id']])){$_SESSION['FiltreFormPlanning_TypeFormation_'.$rowTypeFormation['Id']]=1;}
											else{$_SESSION['FiltreFormPlanning_TypeFormation_'.$rowTypeFormation['Id']]=0;}
										}
										$checked="";
										if($_SESSION['FiltreFormPlanning_TypeFormation_'.$rowTypeFormation['Id']]==1){$checked="checked";}
										echo "<input type='checkbox' ".$checked." name='Type_".$rowTypeFormation['Id']."' >".stripslashes($rowTypeFormation['Libelle'])." ";
									}
								?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
			</tr>
			<tr>
			</tr>
		</table>
	</td></tr>
	<?php if(DroitsFormationPlateforme($TableauIdPostesAF_RF)){ ?>
	<tr>
		<td>
			<table style="width:100%; border-spacing:0; align:center;" class="GeneralInfo">
				<tr>
					<td class="Libelle">&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "Rappel aux prestations des personnes inscrites";}else{echo "Reminder to the benefits of registrants";}?></td>
					<td class="Libelle">
						&nbsp;
						<?php if($LangueAffichage=="FR"){echo "Type";}else{echo "Type";}?> :
						<select id="typeFormationRappel" name="typeFormationRappel">
							<option value="0"></option>
							<?php 
							$typeFormation=$_SESSION['FiltreFormPlanning_TypeFormation'];  
							if(isset($_POST['typeFormation'])){$typeFormation=$_POST['typeFormation'];}
							if(isset($_GET['typeFormation'])){$typeFormation=$_GET['typeFormation'];}
							$resultTypeFormation=mysqli_query($bdd,"SELECT Id, Libelle FROM form_typeformation WHERE Suppr=0 ORDER BY Libelle ASC");
							$selected="";
							while($rowTypeFormation=mysqli_fetch_array($resultTypeFormation))
							{
								$selected="";
								if($typeFormation<>"")
								{
									if($typeFormation==$rowTypeFormation['Id']){$selected="selected";}
								}
								echo "<option ".$selected." value='".$rowTypeFormation['Id']."'>".stripslashes($rowTypeFormation['Libelle'])."</option>\n";
							}
							?>
						</select>
						&nbsp;
						<?php if($LangueAffichage=="FR"){echo "Date de début";}else{echo "Start date";}?> :
						<input type="date" style="text-align:center;" id="DateDebutRappel" name="DateDebutRappel" size="10" value="">&nbsp;
						<?php if($LangueAffichage=="FR"){echo "Date de fin";}else{echo "End date";}?> :
						<input type="date" style="text-align:center;" name="DateFinRappel" id="DateFinRappel"  size="10" value="">&nbsp;
						<input class="Bouton" name="BtnRappel" size="10" type="button" onClick="javascript:EnvoyerMailRappel();" value="<?php if($LangueAffichage=="FR"){echo "Envoyer mail";}else{echo "Send mail";}?>">
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php 
        }
        $FiltreAffichageTypeFormation=array();
        if(isset($_SESSION['FiltreFormPlanning_TypeFormation_'.$IdTypeFormationInterne]))
        {
            if($_SESSION['FiltreFormPlanning_TypeFormation_'.$IdTypeFormationInterne]==1){array_push($FiltreAffichageTypeFormation,$IdTypeFormationInterne);}
        }
        if(isset($_SESSION['FiltreFormPlanning_TypeFormation_'.$IdTypeFormationEprouvette]))
        {
            if($_SESSION['FiltreFormPlanning_TypeFormation_'.$IdTypeFormationEprouvette]==1){array_push($FiltreAffichageTypeFormation,$IdTypeFormationEprouvette);}
        }
        if(isset($_SESSION['FiltreFormPlanning_TypeFormation_'.$IdTypeFormationExterne]))
        {
            if($_SESSION['FiltreFormPlanning_TypeFormation_'.$IdTypeFormationExterne]==1){array_push($FiltreAffichageTypeFormation,$IdTypeFormationExterne);}
        }
        if(isset($_SESSION['FiltreFormPlanning_TypeFormation_'.$IdTypeFormationTC]))
        {
            if($_SESSION['FiltreFormPlanning_TypeFormation_'.$IdTypeFormationTC]==1){array_push($FiltreAffichageTypeFormation,$IdTypeFormationTC);}
        }
        if(count($FiltreAffichageTypeFormation)>0){$FinRequeteTypeFormation="AND (form_formation.Id_TypeFormation IN (".implode(",",$FiltreAffichageTypeFormation)."))";}
        else{$FinRequeteTypeFormation="";}
	   
        $HTMLInformationsSessions="";
		//Rechercher le nombre de session ayant une convocation non envoyée
		$reqConvocation="
            SELECT DISTINCT
                form_session.Id
			FROM
                form_session_personne 
			LEFT JOIN form_session
                ON form_session_personne.Id_Session=form_session.Id
			LEFT JOIN form_formation
			    ON form_session.Id_Formation=form_formation.Id
			WHERE
                form_session_personne.Convocation_Envoyee= 0 
    			AND form_session_personne.Validation_Inscription=1
    			AND form_session.Suppr=0 
    			AND form_session.Annule=0 
    			AND form_session.Id_Plateforme=".$Plateforme."
    			AND
                    (
        				SELECT COUNT(form_session_date.Id) 
        				FROM form_session_date
        				WHERE form_session_date.Suppr=0
        				AND form_session_date.Id_Session=form_session.Id
        				AND form_session_date.DateSession>='".date('Y-m-d')."'
    				)>0
    			AND form_session_personne.Suppr=0 ".
                $FinRequeteTypeFormation;
		
		$reqDatesConvocation="
            SELECT DISTINCT
                form_session_date.DateSession
			FROM
                form_session_date 
			LEFT JOIN form_session
			    ON form_session_date.Id_Session=form_session.Id
			LEFT JOIN form_formation
			    ON form_session.Id_Formation=form_formation.Id
            LEFT JOIN form_session_personne
                ON form_session_personne.Id_Session=form_session.Id
			WHERE
                form_session_personne.Convocation_Envoyee= 0
    			AND form_session_personne.Validation_Inscription=1
    			AND form_session.Suppr=0
    			AND form_session.Annule=0
    			AND form_session.Id_Plateforme=".$Plateforme."
    			AND
                    (
        				SELECT COUNT(form_session_date.Id)
        				FROM form_session_date
        				WHERE form_session_date.Suppr=0
        				AND form_session_date.Id_Session=form_session.Id
        				AND form_session_date.DateSession>='".date('Y-m-d')."'
    				)>0
    			AND form_session_personne.Suppr=0 ".
                $FinRequeteTypeFormation;
		$resultDatesConvocaction=mysqli_query($bdd,$reqDatesConvocation);
		$resultNbConvoc=mysqli_query($bdd,$reqConvocation);
		$nbConvocation=mysqli_num_rows($resultNbConvoc);
		if($nbConvocation>0)
		{
		    $DatesConvocation="";
		    while($rowDateConvocation=mysqli_fetch_array($resultDatesConvocaction))
	        {
	            $DatesConvocation.=AfficheDateJJ_MM_AAAA($rowDateConvocation['DateSession'])."<br>";
	        }
		    
			if($LangueAffichage=="FR")
			{
			    $HTMLInformationsSessions.="
                    <tr><td align='center'>
					<table style='border-spacing:0;'>
						<tr>
							<td id='leHover' class='Libelle' style='font-size:15px;'>
								Les convocations de <font color='red'>".$nbConvocation."</font> sessions de formation n'ont pas encore été envoyées<span>".$DatesConvocation."</span>
							</td>
						</tr>
					</table>
						</td>
					</tr>";
			}
			else
			{
			    $HTMLInformationsSessions.="
                    <tr><td align='center'>
					<table style='border-spacing:0;'>
						<tr>
							<td id='leHover' class='Libelle' style='font-size:15px;'>
								The convocations of <font color='red'>".$nbConvocation."</font> training sessions have not yet been sent<span>".$DatesConvocation."</span>
							</td>
						</tr>
					</table>
						</td>
					</tr>";
			}
		}
	
		//Rechercher le nombre de sessions passées n'étant pas encore traitées = Validation inscription non faite et présence non faite
		$reqNonTraite="
            SELECT DISTINCT form_session.Id
			FROM form_session_date 
			LEFT JOIN form_session
			ON form_session_date.Id_Session=form_session.Id
			LEFT JOIN form_formation
			ON form_session.Id_Formation=form_formation.Id
			WHERE  form_session.Suppr=0 
			AND form_session.Annule=0 
			AND form_session_date.Suppr=0
			AND form_session.Id_Plateforme=".$Plateforme."
			AND form_session_date.DateSession<'".date('Y-m-d')."'
			AND (
				SELECT COUNT(form_session_personne.Id) 
				FROM form_session_personne
				WHERE form_session_personne.Suppr=0
				AND form_session_personne.Id_Session=form_session.Id
				AND (form_session_personne.Validation_Inscription=0 
				OR (Validation_Inscription=1 AND Presence=0)) 
				)>0 ".
                $FinRequeteTypeFormation;
		$resultNonTraite=mysqli_query($bdd,$reqNonTraite);
		$nbNonTraite=mysqli_num_rows($resultNonTraite);
		
		$reqNonTraiteDate="
            SELECT DISTINCT form_session_date.DateSession
			FROM form_session_date 
			LEFT JOIN form_session
			ON form_session_date.Id_Session=form_session.Id
			LEFT JOIN form_formation
			ON form_session.Id_Formation=form_formation.Id
			WHERE  form_session.Suppr=0 
			AND form_session.Annule=0 
			AND form_session_date.Suppr=0
			AND form_session.Id_Plateforme=".$Plateforme."
			AND form_session_date.DateSession<'".date('Y-m-d')."'
			AND (
				SELECT COUNT(form_session_personne.Id) 
				FROM form_session_personne
				WHERE form_session_personne.Suppr=0
				AND form_session_personne.Id_Session=form_session.Id
				AND (form_session_personne.Validation_Inscription=0 
				OR (Validation_Inscription=1 AND Presence=0)) 
				)>0 ".
                $FinRequeteTypeFormation."
            ORDER BY
                form_session_date.DateSession";
		$resultNonTraiteDate=mysqli_query($bdd,$reqNonTraiteDate);
		$nbNonTraiteDate=mysqli_num_rows($resultNonTraiteDate);
		if($nbNonTraite>0)
		{
			$Dates="";
			if($nbNonTraiteDate>0)
			{
				while($rowDate=mysqli_fetch_array($resultNonTraiteDate))
				{
					$Dates.=AfficheDateJJ_MM_AAAA($rowDate['DateSession'])."<br>";
				}
			}
			if($LangueAffichage=="FR")
			{
			    $HTMLInformationsSessions.="<tr><td align='center'>
					<table style='border-spacing:0;'>
						<tr>
							<td id='leHover' class='Libelle' style='font-size:15px;'>
								<font color='red'>".$nbNonTraite."</font> sessions de formation n'ont pas encore été traitées<span>".$Dates."</span>
							</td>
						</tr>
					</table>
						</td>
					</tr>";
			}
			else
			{
			    $HTMLInformationsSessions.="<tr><td align='center'>
					<table style='border-spacing:0;'>
						<tr>
							<td id='leHover' class='Libelle' style='font-size:15px;'>
								<font color='red'>".$nbNonTraite."</font> training sessions have not been processed yet<span>".$Dates."</span>
							</td>
						</tr>
					</table>
					</td>
					</tr>";
			}
		}
		
		$reqNonTraite="
            SELECT DISTINCT form_session.Id
			FROM form_session_date 
			LEFT JOIN form_session
			ON form_session_date.Id_Session=form_session.Id
			LEFT JOIN form_formation
			ON form_session.Id_Formation=form_formation.Id
			WHERE  form_session.Suppr=0 
			AND form_session.Annule=0 
			AND form_session_date.Suppr=0
			AND form_session.Id_Plateforme=".$Plateforme."
			AND form_session_date.DateSession>='".date('Y-m-d')."'
			AND (
				SELECT COUNT(form_session_personne.Id) 
				FROM form_session_personne
				WHERE form_session_personne.Suppr=0
				AND form_session_personne.Id_Session=form_session.Id
				AND form_session_personne.Validation_Inscription=0 
				)>0 ".
                $FinRequeteTypeFormation;	
		$resultNonTraite=mysqli_query($bdd,$reqNonTraite);
		$nbNonTraite=mysqli_num_rows($resultNonTraite);
		
		$reqNonTraiteDate="
            SELECT DISTINCT form_session_date.DateSession
			FROM form_session_date 
			LEFT JOIN form_session
			ON form_session_date.Id_Session=form_session.Id
			LEFT JOIN form_formation
			ON form_session.Id_Formation=form_formation.Id
			WHERE  form_session.Suppr=0 
			AND form_session.Annule=0 
			AND form_session_date.Suppr=0
			AND form_session.Id_Plateforme=".$Plateforme."
			AND form_session_date.DateSession>='".date('Y-m-d')."'
			AND (
				SELECT COUNT(form_session_personne.Id) 
				FROM form_session_personne
				WHERE form_session_personne.Suppr=0
				AND form_session_personne.Id_Session=form_session.Id
				AND form_session_personne.Validation_Inscription=0 
				)>0 ".
                $FinRequeteTypeFormation."
            ORDER BY
                form_session_date.DateSession";
		$resultNonTraiteDate=mysqli_query($bdd,$reqNonTraiteDate);
		$nbNonTraiteDate=mysqli_num_rows($resultNonTraiteDate);
		if($nbNonTraite>0)
		{
			$Dates="";
			if($nbNonTraiteDate>0)
			{
				while($rowDate=mysqli_fetch_array($resultNonTraiteDate))
				{
					$Dates.=AfficheDateJJ_MM_AAAA($rowDate['DateSession'])."<br>";
				}
			}
			if($LangueAffichage=="FR")
			{
			    $HTMLInformationsSessions.="<tr><td align='center'>
					<table style='border-spacing:0;'>
						<tr>
							<td id='leHover' class='Libelle' style='font-size:15px;'>
								<font color='red'>".$nbNonTraite."</font> sessions ont des inscriptions non validées<span>".$Dates."</span>
							</td>
						</tr>
					</table>
						</td>
					</tr>";
			}
			else
			{
			    $HTMLInformationsSessions.="<tr><td align='center'>
					<table style='border-spacing:0;'>
						<tr>
							<td id='leHover' class='Libelle' style='font-size:15px;'>
								<font color='red'>".$nbNonTraite."</font> training sessions have uncommitted registrations<span>".$Dates."</span>
							</td>
						</tr>
					</table>
					</td>
					</tr>";
			}
		}
		
		//Légende si type d'affichage = Session
		if($typeAffichage=="session")
		{
	?>
	<tr>
		<td>
			<table style="width:100%; border-spacing:0;">
				<tr>
					<td>
						<table style="width:100%; border-spacing:0;">
							<tr>
								<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Légende";}else{echo "Legend";}?> : </td>
            				</tr>
            				<tr>
            					<td class="Libelle" style="background-color:<?php echo $transparent;?>;"><?php if($LangueAffichage=="FR"){echo "session incomplète";}else{echo "incomplete session";}?></td>
            				</tr>
            				<tr>
            					<td class="Libelle" style="background-color:<?php echo $rouge;?>;"><?php if($LangueAffichage=="FR"){echo "session annulée";}else{echo "session canceled";}?></td>
            				</tr>
            				<tr>
            					<td class="Libelle" style="background-color:<?php echo $bleu;?>;"><?php if($LangueAffichage=="FR"){echo "session confirmée";}else{echo "confirmed session";}?></td>
            				</tr>
            				<tr>
            					<td class="Libelle" style="background-color:<?php echo $vert;?>;"><?php if($LangueAffichage=="FR"){echo "session complète";}else{echo "full session";}?></td>
            				</tr>
						</table>
					</td>
					<td align="center">
						<table style="width:100%; border-spacing:0;">
							<?php echo $HTMLInformationsSessions;?>
						</table>
					</td>
					<td align="center">
						<table>
							<tr>
                        		<?php
                        			$Modif=0;
                        			if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH) || DroitsFormationPlateforme(array($IdPosteFormateur))){$Modif=1;}
                        			if($Modif==1)
										{
									?>
									<td align="left">
									<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjoutFormation()'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Ajouter une formation";}else{echo "Add a training";} ?>&nbsp;</a>&nbsp;&nbsp;&nbsp;
									<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjoutGroupeFormation()'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Ajouter un groupe de formation";}else{echo "Add a training group";} ?>&nbsp;</a>&nbsp;&nbsp;&nbsp;
									<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreIndispoFormateur()'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Indisponibilité formateur";}else{echo "Trainer unavailability";} ?>&nbsp;</a>
									
									</td>
									<?php
										}
                        		?>
                        	</tr>
                        	<tr><td height="4px"></td></tr>
                        	<tr>
                        		<td style="font-size:20px;font-weight: bold;color:#055af4;" align="center" width="45%">
                        			<?php
                        				$tmpDate = TrsfDate_($dateDebut);
                        				$dateFin = TrsfDate_($dateDeFin);
                        				if($LangueAffichage=="FR"){$MoisLettre = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");}
                        				else{$MoisLettre = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");}
                        				echo date('d', strtotime($tmpDate." + 0 month"))." ".$MoisLettre[intval(date('m', strtotime($tmpDate." + 0 month")))-1]." ".date('Y', strtotime($tmpDate." + 0 month"));
                        				echo " - ";
                        				echo date('d', strtotime($dateFin." + 0 month"))." ".$MoisLettre[intval(date('m', strtotime($dateFin." + 0 month")))-1]." ".date('Y', strtotime($dateFin." + 0 month"));
                        			?>
                        		</td>
                        	</tr>
						</table>
					</td>
			</table>
		</td>
	</tr>
	<?php
		}
		else
		{
	?>
	<tr>
		<td>
			<table style="width:100%; border-spacing:0;">
				<tr>
					<td>
						<table style="width:100%; border-spacing:0;">
							<tr>
            					<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Légende";}else{echo "Legend";}?> : </td>
            				</tr>
            				<tr>
            					<td class="Libelle" style="background-color:<?php echo $transparent;?>;"><?php if($LangueAffichage=="FR"){echo "Pas de formateur";}else{echo "No trainer";}?></td>
            				</tr>
            				<tr>
            					<td class="Libelle" style="background-color:<?php echo $rouge;?>;"><?php if($LangueAffichage=="FR"){echo "session annulée";}else{echo "session canceled";}?></td>
            				</tr>
            			</table>
            		</td>
            		<td align="center">
						<table style="width:100%; border-spacing:0;">
							<?php echo $HTMLInformationsSessions;?>
						</table>
					</td>
					<td align="center">
						<table>
							<tr>
                        		<?php
                        			$Modif=0;
                        			if(DroitsFormationPlateforme($TableauIdPostesAF_RF_RH) || DroitsFormationPlateforme(array($IdPosteFormateur))){$Modif=1;}
                        			if($Modif==1)
                        			{
                        		?>
                        		<td align="center">
                        		<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjoutFormation()'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Ajouter une formation";}else{echo "Add a training";} ?>&nbsp;</a>&nbsp;&nbsp;&nbsp;
                        		<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreAjoutGroupeFormation()'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Ajouter un groupe de formation";}else{echo "Add a training group";} ?>&nbsp;</a>&nbsp;&nbsp;&nbsp;
								<a style='text-decoration:none;' class='Bouton' href='javascript:OuvreFenetreIndispoFormateur()'>&nbsp;<?php if($LangueAffichage=="FR"){echo "Indisponibilité formateur";}else{echo "Trainer unavailability";} ?>&nbsp;</a>
                        		</td>
                        		<?php
                        			}
                        		?>
                        	</tr>
                        	<tr><td height="4px"></td></tr>
                        	<tr>
                        		<td style="font-size:20px;font-weight: bold;color:#055af4;" align="center" width="45%">
                        			<?php
                        				$tmpDate = TrsfDate_($dateDebut);
                        				$dateFin = TrsfDate_($dateDeFin);
                        				if($LangueAffichage=="FR"){$MoisLettre = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");}
                        				else{$MoisLettre = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");}
                        				echo date('d', strtotime($tmpDate." + 0 month"))." ".$MoisLettre[intval(date('m', strtotime($tmpDate." + 0 month")))-1]." ".date('Y', strtotime($tmpDate." + 0 month"));
                        				echo " - ";
                        				echo date('d', strtotime($dateFin." + 0 month"))." ".$MoisLettre[intval(date('m', strtotime($dateFin." + 0 month")))-1]." ".date('Y', strtotime($dateFin." + 0 month"));
                        			?>
                        		</td>
                        	</tr>
						</table>
					</td>
            	</tr>
			</table>
		</td>
	</tr>
	<?php
		}
	?>
	</table>
</td>
<td>&nbsp;</td>
</tr>
<tr>
<td colspan="2">
<table style="width:150%; border-spacing:0;">
<tr><td colspan="2">
	<table style="width:100%; border-spacing:0;">
			<tr align="center">
				<td align="center" width="15px" valign="middle"></td>
				<td align="center" width="15px" valign="middle"></td>
				<td align="center" width="15px" valign="middle"></td>
				<td align="left" width="98%" valign="middle">
					<table  style="margin:0; width:100%; border-spacing:0; align:center;">
						<tr>
							<?php 
								$heure=5;
								$min=0;
								for($i=1;$i<=61;$i++)
								{
									if($min==0){$minAffiche="";}
									else{$minAffiche=$min;}
									echo "<td class='EnTeteSemaine' width='15px' style='font-size:10px;border:1px solid #cccccc;word-break:break-all;'>".$heure."h<br>".$minAffiche."</td>";
									if($min==0){$min=15;}
									elseif($min==15){$min=30;}
									elseif($min==30){$min=45;}
									else{$min=0;$heure++;}
								}
							?>
						</tr>
					</table>
				</td>
			</tr>
			<?php
			//GESTION DU CORPS DU TABLEAU
			$tmpDate = TrsfDate_($dateDebut);
			$dateFin = TrsfDate_($dateDeFin);
			
			$tmpMois = date('n', strtotime($tmpDate." + 0 month")) . ' ' . date('Y', strtotime($tmpDate." + 0 month"));
			if($LangueAffichage=="FR"){$joursem = array("Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam");}
			else{$joursem = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");}
			//Requete sessions de la période
			$req="
                SELECT
                    form_session.Id,
                    form_session.Id_Formation,
                    form_session.Id_Lieu,
                    form_session.Id_Formateur,
                    form_session_date.Id AS Id_SessionDate,
                    form_session.nom_fichier, 
				    (SELECT Id_TypeFormation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) AS Id_TypeFormation, 
				    (SELECT DateCreation FROM form_formation WHERE form_formation.Id=form_session.Id_Formation) AS DateCreationForm,
                    (SELECT Couleur FROM new_competences_personne_poste_plateforme WHERE new_competences_personne_poste_plateforme.Id_Personne=form_session.Id_Formateur AND Id_Poste=21 AND Id_Plateforme=".$Plateforme." LIMIT 1) AS CouleurFormateur,
                    form_session_date.DateSession,
                    form_session_date.Heure_Debut,
					(SELECT COUNT(Id) FROM form_session_personne WHERE Validation_Inscription=1 AND Suppr=0 AND Id_Session=form_session.Id) AS NbInscrit,
					(SELECT COUNT(Id) FROM form_session_personne WHERE Validation_Inscription=0 AND Suppr=0 AND Id_Session=form_session.Id) AS NbPreInscrit,
					(SELECT COUNT(Id) FROM form_session_personne WHERE Convocation_Envoyee= 0 AND Validation_Inscription=1 AND Suppr=0 AND Id_Session=form_session.Id) AS NbConvocationNonEnvoyee,
					(SELECT COUNT(Id) FROM form_session_personne WHERE (Validation_Inscription=0 OR (Validation_Inscription=1 AND Presence=0)) AND Suppr=0 AND Id_Session=form_session.Id) AS NbNonTraite,
					(SELECT COUNT(Id) FROM form_session_personne WHERE Suppr=0 AND Validation_Inscription>-1 AND Id_Session=form_session.Id) AS NbSPersonne,
					(SELECT COUNT(Id) FROM form_session_prestation WHERE Id_Session=form_session.Id AND Suppr=0) AS NbSPrestations,
                    form_session_date.Heure_Fin,
                    form_session.Diffusion_Creneau,
                    form_session.Recyclage,
                    form_session_date.PauseRepas,
                    form_session_date.HeureDebutPause,
                    form_session_date.HeureFinPause,
                    (SELECT COUNT(Id) FROM form_session_date WHERE form_session_date.Id_Session=form_session.Id) AS Nb,
                    (SELECT Libelle FROM form_lieu WHERE form_lieu.Id=form_session.Id_Lieu) AS Lieu,form_session.Id_GroupeSession,form_session.Formation_Liee,
                    (SELECT (SELECT Libelle FROM form_groupe_formation WHERE form_groupe_formation.Id=form_session_groupe.Id_GroupeFormation) FROM form_session_groupe WHERE form_session_groupe.Id=form_session.Id_GroupeSession) AS Groupe,
                    (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session.Id_Formateur) AS Formateur,form_session.Annule,form_session.Nb_Stagiaire_Maxi,form_session.Nb_Stagiaire_Mini
                FROM
                    form_session_date
                LEFT JOIN form_session
                    ON form_session_date.Id_Session = form_session.Id
                WHERE
                    form_session_date.Suppr=0
                    AND form_session.Suppr=0
                    AND form_session.Id_Plateforme=".$Plateforme."
                    AND form_session_date.DateSession>='".$tmpDate."'
                    AND form_session_date.DateSession<='".$dateFin."' ";
			if($formateur>0){$req.="AND form_session.Id_Formateur=".$formateur." ";}
			elseif($formateur==-1){$req.="AND form_session.Id_Formateur=0 ";}
			if($lieu>0){$req.="AND form_session.Id_Lieu=".$lieu." ";}
			elseif($lieu==-1){$req.="AND form_session.Id_Lieu=0 ";}
			if($horaires==0){$req.=" AND form_session_date.Heure_Debut=0 ";}
			elseif($horaires==1){$req.=" AND form_session_date.Heure_Debut>0 ";}
			if($formation<>"")
			{
			    $req.=" AND (SELECT IF(form_session.Recyclage=0,form_formation_langue_infos.Libelle,form_formation_langue_infos.LibelleRecyclage) FROM form_formation_langue_infos ";
				$req.= "WHERE form_formation_langue_infos.Id_Formation=form_session.Id_Formation AND Suppr=0 ";
				$req.= "AND Id_Langue IN (SELECT Id_Langue FROM form_formation_plateforme_parametres WHERE Id_Plateforme=".$Plateforme." AND Suppr=0 AND Id_Formation=form_formation_langue_infos.Id_Formation) LIMIT 1) LIKE '%".$formation."%' ";
			}
			if($organisme<>0){
				$req.=" AND (
								SELECT Id_Organisme 
								FROM form_formation_plateforme_parametres 
								WHERE Id_Plateforme=".$Plateforme." 
								AND Suppr=0 
								AND form_formation_plateforme_parametres.Id_Formation=form_session.Id_Formation
								LIMIT 1
							) =".$organisme." ";
			}
			$req.=" AND (";
			$resultTypeFormation=mysqli_query($bdd,"SELECT Id, Libelle FROM form_typeformation WHERE Suppr=0 ORDER BY Libelle ASC");
			while($rowTypeFormation=mysqli_fetch_array($resultTypeFormation))
			{
				if($_SESSION['FiltreFormPlanning_TypeFormation_'.$rowTypeFormation['Id']]==1){
					$req.=" (SELECT form_formation.Id_TypeFormation ";
					$req.= "FROM form_formation ";
					$req.= "WHERE form_formation.Id=form_session.Id_Formation)=".$rowTypeFormation['Id']." OR ";
				}
			}
			if(substr($req,-3)=="OR "){$req=substr($req,0,-3);}
			$req.=") ";
			if(substr($req,-8)==" AND () "){$req=substr($req,0,-8);}
			
			if($etat=="annule")
			{
			    $req.=" AND form_session.Annule=1 ";
			}
			elseif($etat=="complete")
			{
			    $req.=" AND form_session.Nb_Stagiaire_Maxi>0 AND form_session.Nb_Stagiaire_Maxi<=(SELECT COUNT(form_session_personne.Id) FROM form_session_personne WHERE form_session_personne.Validation_Inscription=1 AND form_session_personne.Suppr=0 AND form_session_personne.Id_Session=form_session.Id) ";
			}
			elseif($etat=="confirme")
			{
				$req.=" AND form_session.Nb_Stagiaire_Mini<=(SELECT COUNT(form_session_personne.Id) FROM form_session_personne WHERE form_session_personne.Validation_Inscription=1 AND form_session_personne.Suppr=0 AND form_session_personne.Id_Session=form_session.Id) ";
				$req.=" AND form_session.Nb_Stagiaire_Maxi>(SELECT COUNT(form_session_personne.Id) FROM form_session_personne WHERE form_session_personne.Validation_Inscription=1 AND form_session_personne.Suppr=0 AND form_session_personne.Id_Session=form_session.Id) ";
			}
			elseif($etat=="incomplete")
			{
				$req.=" AND form_session.Nb_Stagiaire_Maxi>0 AND (form_session.Nb_Stagiaire_Mini=0 OR form_session.Nb_Stagiaire_Mini>(SELECT COUNT(form_session_personne.Id) FROM form_session_personne WHERE form_session_personne.Validation_Inscription=1 AND form_session_personne.Suppr=0 AND form_session_personne.Id_Session=form_session.Id)) ";
			}
			$req.="ORDER BY form_session.Formation_Liee DESC,form_session_date.DateSession, form_session.Id_GroupeSession DESC, Heure_Fin";
			
			$resultSessions=mysqli_query($bdd,$req);
			$resultSessions2=mysqli_query($bdd,$req);
			$nbSession=mysqli_num_rows($resultSessions);	

			$requeteInfos="SELECT Id,Id_Formation,Id_Langue,Libelle,LibelleRecyclage FROM form_formation_langue_infos WHERE Suppr=0 ";
			$resultInfos=mysqli_query($bdd,$requeteInfos);
			$nbInfos=mysqli_num_rows($resultInfos);
			
			$requeteParam="SELECT Id,Id_Formation,Id_Langue,(SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS Organisme FROM form_formation_plateforme_parametres WHERE Suppr=0 AND Id_Plateforme=".$Plateforme." ";
			$resultParam=mysqli_query($bdd,$requeteParam);
			$nbParam=mysqli_num_rows($resultParam);
			
			$req="SELECT Id_Personne, rh_absence.DateDebut, rh_absence.DateFin, 
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=rh_personne_demandeabsence.Id_Personne) AS Personne 
				FROM rh_absence 
				LEFT JOIN rh_personne_demandeabsence 
				ON rh_absence.Id_Personne_DA=rh_personne_demandeabsence.Id 
				WHERE Id_Personne IN (SELECT Id_Personne FROM new_competences_personne_poste_plateforme WHERE Id_Poste=21 AND Id_Plateforme=".$Plateforme.")
				AND rh_absence.DateFin>='".$tmpDate."' 
				AND rh_absence.DateDebut<='".$dateFin."' 
				AND rh_personne_demandeabsence.Suppr=0 
				AND rh_absence.Suppr=0 
				AND rh_personne_demandeabsence.Annulation=0 
				AND EtatN1<>-1
				AND EtatN2<>-1
				UNION 
				SELECT Id_Personne, DateIndispo AS DateDebut,DateIndispo AS DateFin,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=form_formateur_indispo.Id_Personne) AS Personne 
				FROM form_formateur_indispo
				WHERE form_formateur_indispo.Id_Plateforme=".$Plateforme." AND 
				DateIndispo>='".$tmpDate."' AND DateIndispo<='".$dateFin."' 
				AND form_formateur_indispo.Suppr=0
				ORDER BY Personne
				";
		
			$resultPlanning=mysqli_query($bdd,$req);
			$nbPlanning=mysqli_num_rows($resultPlanning);
			//echo date("H:i:s")."<br>";
			$semaineEC=0;
			$tabSessionDate=array();
			$itab2=0;
			while ($tmpDate <= $dateFin)
			{
				$leJour = date('d', strtotime($tmpDate." + 0 month"));
				$jour = date('w', strtotime($tmpDate." + 0 month"));
				$mois = date('m', strtotime($tmpDate." + 0 month"));
				$semaine = date('W', strtotime($tmpDate." + 0 month"));
				
				$tabForm=array();
				$itab=0;
				$nbLigne=0;
				$nbSansHeures=0;
				$bTrouve=0;
				
				//CALCUL NEW 
				$taille=0;
				if($nbSession>0)
				{
					mysqli_data_seek($resultSessions,0);
					while($rowSession=mysqli_fetch_array($resultSessions))
					{
						if($rowSession['DateSession']==$tmpDate)
						{
							$taille++;
						}
					}
				}
				$tabResult[]= array();
				$nb=0;
				$iResultat=0;
				if($taille>0){
					while($nb+$nbSansHeures<$taille){
						$heure=5;
						$min=0;
						$nbLigne++;
						for($i=1;$i<=61;$i++){
							$heureFin="00:00:00";
							$trouve=0;
							if($nbSession>0){
								mysqli_data_seek($resultSessions,0);
								while($rowSession=mysqli_fetch_array($resultSessions)){
									if($rowSession['DateSession']==$tmpDate){
										if($trouve==0){
											if($rowSession['Heure_Debut']==0){
												$bExiste=0;
												for($k=0;$k<=(sizeof($tabResult)-1);$k++)
												{
													if($tabResult[$k]==$rowSession['Id_SessionDate']){$bExiste=1;}
												}
												if($bExiste==0){
													$tabResult[$iResultat]=$rowSession['Id_SessionDate'];$iResultat++;
													$trouve=1;
													$heureFin="00:00:00";
													$nbSansHeures++;
													$i=62;
												}
											}
											else{
												if($rowSession['Heure_Debut']==sprintf('%02d', $heure).":".sprintf('%02d', $min).":00"){
													$bExiste=0;
													for($k=0;$k<=(sizeof($tabResult)-1);$k++)
													{
														if($tabResult[$k]==$rowSession['Id_SessionDate']){$bExiste=1;}
													}
													if($bExiste==0){
														$tabResult[$iResultat]=$rowSession['Id_SessionDate'];$iResultat++;
														$heureFin=$rowSession['Heure_Fin'];
														$trouve=1;
														$nb++;
														$h1=strtotime($rowSession['Heure_Fin']);
														$h2=strtotime($rowSession['Heure_Debut']);
														$val=intval(substr(gmdate("H:i",$h1-$h2),0,2))*4;
														if(substr(gmdate("H:i",$h1-$h2),3,2)<="15"){$val++;}
														elseif(substr(gmdate("H:i",$h1-$h2),3,2)<="30"){$val=$val+2;}
														elseif(substr(gmdate("H:i",$h1-$h2),3,2)<="45"){$val=$val+3;}
														elseif(substr(gmdate("H:i",$h1-$h2),3,2)<="59"){$val=$val+4;}
														if($val>1)
														{
															$i+=($val-1);
														}
													}
												}
											}
										}
									}
								}
							}
							
							if($min==0){$min=15;}
							elseif($min==15){$min=30;}
							elseif($min==30){$min=45;}
							else{$min=0;$heure++;}
							if($heureFin<>"00:00:00")
							{
								$heure=intval(substr($heureFin,0,2));
								$min=intval(substr($heureFin,3,2));
							}
						}
					}
					
					$total=$nbSansHeures+$nb;
					$nbLigne=$nbLigne-$nbSansHeures;
				}
			
				echo "<tr>\n";
				$rowspanSemaine="";
				if($semaineEC==0 || $semaine<>$semaineEC)
				{
					if($jour<>0){$rowspanSemaine="rowspan='".(8-$jour)."'";}
					if($LangueAffichage=="FR"){echo "<td width='15px' class='EnTeteSemaine' ".$rowspanSemaine." align='center' valign='center' style='font-size:11px;border:1px solid #cccccc;'>S".$semaine."</td>\n";}
					else{echo "<td width='15px' class='EnTeteSemaine' ".$rowspanSemaine." align='center' valign='center' style='font-size:11px;border:1px solid #cccccc;'>W".$semaine."</td>\n";}
				}
				$spanabs="";
				$FormateursAbsents="";
				$idAbs="";
				//Formateurs absents
				if($nbPlanning>0)
				{
					mysqli_data_seek($resultPlanning,0);
					while($rowPlanning=mysqli_fetch_array($resultPlanning))
					{
						if($rowPlanning['DateDebut']<=$tmpDate && $rowPlanning['DateFin']>=$tmpDate){$FormateursAbsents.=$rowPlanning['Personne']."<br>";}
					}
				}
				if($FormateursAbsents<>"")
				{
					$idAbs="id='leHoverPersonne'";
					$spanabs="<br><label style='color:red;'>ABS</label><span>".$FormateursAbsents."</span>";
				}
				echo "<td width='15px' class='EnTeteSemaine' id='".$tmpDate."' align='center' valign='center' style='font-size:11px;border:1px solid #cccccc'>".$joursem[$jour]."</td>\n";
				echo "<td width='15px' class='EnTeteSemaine' ".$idAbs." align='center' valign='center' style='font-size:11px;border:1px solid #cccccc;'>".$leJour.$spanabs."</td>\n";
				echo "<td width='92%' align='left' valign='center' style='font-size:11px;'>";
				echo "<table style='width:100%; margin:0; border-spacing:0;'>";
				if($nbSansHeures==0 && $nbLigne==0)
				{
					echo "<tr>\n";
						$heure=5;
						$min=0;
						for($i=1;$i<=61;$i++)
						{
							$colspan="";
							$couleur="#ffffff";
							$heureFin="00:00:00";
							$trouve=0;
							if($i>=29 && $i<=33){$couleur="#d6ecf2";}
							echo "<td height=30px' align='center' style='background-color:".$couleur.";border:1px solid #cccccc;' width='15px'></td>\n";
							if($min==0){$min=15;}
							elseif($min==15){$min=30;}
							elseif($min==30){$min=45;}
							else{$min=0;$heure++;}
						}
						echo "</tr>\n";
				}
				if($nbLigne>0)
				{
					for($j=1;$j<=$nbLigne;$j++)
					{
						echo "<tr>\n";
						$heure=5;
						$min=0;
						for($i=1;$i<=61;$i++)
						{
							$colspan="";
							$couleur="#ffffff";
							$heureFin="00:00:00";
							$trouve=0;
							$formation="";
							$onclick="";
							$onclickContenu="";
							$onclickContenu2="";
							$val=1;
							$id="";
							$formNew="";
							$formPre="";
							$convocation="";
							if($i>=29 && $i<=33){$couleur="#d6ecf2";}
							$couleurFormateur="#eff7ff";
							
							if($nbSession>0)
							{
								mysqli_data_seek($resultSessions,0);
								while($rowSession=mysqli_fetch_array($resultSessions))
								{
									if($rowSession['DateSession']==$tmpDate)
									{
										if($rowSession['Heure_Debut']==sprintf('%02d', $heure).":".sprintf('%02d', $min).":00" && $trouve==0)
										{
											$bExiste=0;
											//Places restantes
											$nbInscrit=$rowSession['NbInscrit'];
											$NbPreInscrit=$rowSession['NbPreInscrit'];
											
											$formPre="";
											if($NbPreInscrit>0){
												if($LangueAffichage=="FR"){
													$formPre="<img width='30px' src='../../Images/exclamation.png' border='0' alt='Préinscription à valider' title='Préinscription à valider'>";
												}
												else{
													$formPre="<img width='30px' src='../../Images/exclamation.png' border='0' alt='Pre-registration to validate' title='Pre-registration to validate'>";
												}
											}
											else{
												if($tmpDate<date('Y-m-d')){
													//Vérifier si la session a des présences non validé ou des compétences à valider 
													$reqNonTraite="SELECT Id 
																FROM form_session_personne 
																WHERE (
																		(Validation_Inscription=1 
																		AND Presence=1 
																		AND
																			(SELECT COUNT(form_session_personne_qualification.Id)
																			FROM form_session_personne_qualification
																			WHERE form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id
																			AND form_session_personne_qualification.Suppr=0
																			AND form_session_personne_qualification.Etat=0
																			)>0
																			
																		AND (
																			SELECT COUNT(form_session_personne_qualification.Id)
																			FROM form_session_personne_qualification
																			WHERE form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id
																			AND form_session_personne_qualification.Suppr=0
																			AND form_session_personne_qualification.Etat<>0
																			)=0
																		) 
																		OR 
																		(Validation_Inscription=1 
																		AND Presence=0)) 
																AND Suppr=0 
																AND Id_Session=".$rowSession['Id'];
													
													$resultNbNonTraite=mysqli_query($bdd,$reqNonTraite);
													$nbNonTraite=mysqli_num_rows($resultNbNonTraite);

													if($nbNonTraite>0){
														if($LangueAffichage=="FR"){
															$formPre="<img width='30px' src='../../Images/exclamationJaune.png' border='0' alt='Session à traiter' title='Session à traiter'>";
														}
														else{
															$formPre="<img width='30px' src='../../Images/exclamationJaune.png' border='0' alt='Session to be treated' title='Session to be treated'>";
														}
													}
												}
												
												
											}
											$couleurSession="";
											$Annule="";
											if($typeAffichage=="session")
											{
												$couleurSession=$transparent;
												//Si nb stagiaire maxi atteint
												if($rowSession['Annule']==1){$couleurSession=$rouge;}
												elseif($nbInscrit>=$rowSession['Nb_Stagiaire_Maxi'] && $rowSession['Nb_Stagiaire_Maxi']>0){$couleurSession=$vert;}
												elseif($nbInscrit>=$rowSession['Nb_Stagiaire_Mini'] && $rowSession['Nb_Stagiaire_Maxi']>0 && $nbInscrit>0){$couleurSession=$bleu;}
											}
											if($rowSession['Annule']==1)
											{
												$couleurSession=$rouge;
												if($LangueAffichage=="FR"){$Annule=" [ANNULEE]";}
												else{$Annule=" [CANCELED]";}
											}
											
											//Convocations envoyées
											$nbConvocation=$rowSession['NbConvocationNonEnvoyee'];
											
											
											//Session terminée d'être traité = Validation des inscriptions + validation de la présence
											$nbNonTraite=$rowSession['NbNonTraite'];
											
											//Personnes de la session 
											$nbSPersonne=$rowSession['NbSPersonne'];
											
											//Prestations de la session
											$NbSPrestations=$rowSession['NbSPrestations'];
											
											//Date de début de la session 
											$reqSDate="SELECT DateSession FROM form_session_date WHERE Suppr=0 AND Id_Session=".$rowSession['Id']." ORDER BY DateSession ASC ";
											$resultSDate=mysqli_query($bdd,$reqSDate);
											$nbSDate=mysqli_num_rows($resultSDate);
											$DateDebutS="";
											if($nbSDate>0){
												$rowSDate=mysqli_fetch_array($resultSDate);
												$DateDebutS=AfficheDateJJ_MM_AAAA($rowSDate['DateSession']);
											}
											
											
											//Date de fin de la session 
											$reqSDate="SELECT DateSession FROM form_session_date WHERE Suppr=0 AND Id_Session=".$rowSession['Id']." ORDER BY DateSession DESC ";
											$resultSDate=mysqli_query($bdd,$reqSDate);
											$nbSDate=mysqli_num_rows($resultSDate);
											$DateFinS="";
											if($nbSDate>0){
												$rowSDate=mysqli_fetch_array($resultSDate);
												$DateFinS=AfficheDateJJ_MM_AAAA($rowSDate['DateSession']);
											}
											
											for($k=0;$k<=(sizeof($tabForm)-1);$k++)
											{
												if($tabForm[$k]==$rowSession['Id_SessionDate']){$bExiste=1;}
											}
											$nbPartie=1;
											for($k=0;$k<=(sizeof($tabSessionDate)-1);$k++)
											{
												if($tabSessionDate[$k]==$rowSession['Id']){$nbPartie++;}
											}
											if($bExiste==0){$tabSessionDate[$itab2]=$rowSession['Id'];$itab2++;}
											if($bExiste==0)
											{
												
												$h1=strtotime($rowSession['Heure_Fin']);
												$h2=strtotime($rowSession['Heure_Debut']);
												$val=intval(substr(gmdate("H:i",$h1-$h2),0,2))*4;
												if(substr(gmdate("H:i",$h1-$h2),3,2)<="15"){$val++;}
												elseif(substr(gmdate("H:i",$h1-$h2),3,2)<="30"){$val=$val+2;}
												elseif(substr(gmdate("H:i",$h1-$h2),3,2)<="45"){$val=$val+3;}
												elseif(substr(gmdate("H:i",$h1-$h2),3,2)<="59"){$val=$val+4;}
												if($val>1)
												{
													$colspan="colspan='".$val."'";
													$i+=($val-1);
												}
												$heureFin=$rowSession['Heure_Fin'];
												$trouve=1;
												$tabForm[$itab]=$rowSession['Id_SessionDate'];
												$itab++;
												
												$Id_Langue=0;
												$organisme="";
												if($nbParam>0)
												{
													mysqli_data_seek($resultParam,0);
													while($rowParam=mysqli_fetch_array($resultParam))
													{
														if($rowParam['Id_Formation']==$rowSession['Id_Formation'])
														{
															$Id_Langue=$rowParam['Id_Langue'];
															if($rowParam['Organisme']<>""){$organisme=" (".stripslashes($rowParam['Organisme']).")";}
														}
													}
												}
												$Infos="";
												if($nbInfos>0)
												{
													mysqli_data_seek($resultInfos,0);
													while($rowInfo=mysqli_fetch_array($resultInfos))
													{
														if($rowInfo['Id_Formation']==$rowSession['Id_Formation'] && $rowInfo['Id_Langue']==$Id_Langue)
														{
															if($rowSession['Recyclage']==0){$Infos=stripslashes($rowInfo['Libelle']).$organisme;}
															else{$Infos=stripslashes($rowInfo['LibelleRecyclage']).$organisme;}
														}
													}
												}
												$onclickInscription="";
												if($Modif==1)
												{
													$onclick="onclick=\"ModifierSession('".$rowSession['Id']."')\"";
													$onclickContenu="onclick=\"ContenuSession('".$rowSession['Id']."','".$tmpDate."')\"";
													$onclickContenu2="onclick=\"Contenu2Session('".$rowSession['Id']."','".$tmpDate."')\"";
													$onclickInscription="onclick=\"InscrireSession('".$rowSession['Id']."')\"";
												}
												if($LangueAffichage=="FR"){$Lieu="<i>Lieu non défini</i>";}
												else{$Lieu="<i>Undefined location</i>";}
												if($rowSession['Lieu']){$Lieu="<i>".$rowSession['Lieu']."</i>";}
												$Heures=substr($rowSession['Heure_Debut'],0,5)." - ".substr($rowSession['Heure_Fin'],0,5);
												if($rowSession['PauseRepas']==1)
												{
													if($rowSession['Heure_Fin']>$rowSession['HeureDebutPause'] && $rowSession['Heure_Debut']<$rowSession['HeureFinPause'])
													{
														$Heures=substr($rowSession['Heure_Debut'],0,5)." - ".substr($rowSession['HeureDebutPause'],0,5)." &#47; ".substr($rowSession['HeureFinPause'],0,5)." - ".substr($rowSession['Heure_Fin'],0,5);
													}
												}
												$id=$rowSession['Id'];
												$Partie="";
												if($LangueAffichage=="FR"){if($rowSession['Nb']>1){$Partie=" (Partie".$nbPartie.")";}}
												else{if($rowSession['Nb']>1){$Partie=" (Part".$nbPartie.")";}}
												$GroupeFormation="";
												if($rowSession['Id_GroupeSession']>0 && $rowSession['Formation_Liee']>0)
												{
													if($LangueAffichage=="FR"){$GroupeFormation="<b>Groupe</b> : ".$rowSession['Groupe']."<br>";}
													else{$GroupeFormation="<b>Group</b> : ".$rowSession['Groupe']."<br>";}
													$id="GR".$rowSession['Id_GroupeSession'];
													if($Modif==1){$onclick="onclick=\"ModifierSessionGroupe('".$rowSession['Id_GroupeSession']."')\"";}
												}
												$diffusion="";
												if($rowSession['Diffusion_Creneau']==1 && $NbSPrestations>0)
												{
													if($LangueAffichage=="FR")
													{
														$diffusion="<img src='../../Images/diffuser.png' style='cursor: pointer;' onclick=\"RediffuserSession('".$rowSession['Id']."');\" width='15px' border='0' alt='Diffuser' title='Diffuser'>";
													}
													else
													{
														$diffusion="<img src='../../Images/diffuser.png' style='cursor: pointer;' onclick=\"RediffuserSession('".$rowSession['Id']."');\" width='15px' border='0' alt='Spread' title='Spread'>";
													}
												}
												$convocation="";
												if($nbConvocation==0 && $nbInscrit>0)
												{
													if($LangueAffichage=="FR")
													{
														$convocation="<img src='../../Images/C2.png' style='cursor: default;' width='15px' border='0' alt='Convocation envoyée' title='Convocation envoyée'>";
													}
													else
													{
														$convocation="<img src='../../Images/C2.png' style='cursor: default;' width='15px' border='0' alt='Convocation sent' title='Convocation sent'>";
													}
												}
												elseif($nbConvocation>0 && $nbInscrit>0)
												{
													$champs="";
													if($rowSession['Id_Formateur']==0)
													{
														if($LangueAffichage=="FR"){$champs.="formateur ";}
														else{$champs.="trainer ";}
													}
													if($rowSession['Id_Lieu']==0 && $rowSession['nom_fichier']=="")
													{
														if($LangueAffichage=="FR"){$champs.="lieu ";}
														else{$champs.="place ";}
													}
													if($LangueAffichage=="FR")
													{
														$convocation="<img src='../../Images/C.png' width='15px' border='0'  onclick=\"EmailConvocation('".$rowSession['Id']."','".$champs."');\" alt='Envoyer la convocation' title='Envoyer la convocation'>";
													}
													else
													{
														$convocation="<img src='../../Images/C.png' width='15px' border='0'  onclick=\"EmailConvocation('".$rowSession['Id']."','".$champs."');\" alt='Send the convocation' title='Send the convocation'>";
													}
												}
												$Traite="";
												if($nbNonTraite==0 && $nbSPersonne>0)
												{
													if($LangueAffichage=="FR"){$Traite="<img src='../../Images/tick.png' style='cursor: default;' width='15px' border='0' alt='Traité' title='Traité'>";}
													else{$Traite="<img src='../../Images/tick.png' style='cursor: default;' width='15px' border='0' alt='Completed' title='Completed'>";}
												}
												if($LangueAffichage=="FR"){$formateur="Formateur non défini";}
												else{$formateur="Undefined trainer";}
												if($rowSession['Formateur']<>"")
												{
													$formateur=$rowSession['Formateur'];
													$couleurFormateur=$rowSession['CouleurFormateur'];
												}
												
												//Eprouvette & Interne
												if($rowSession['Id_TypeFormation']==$IdTypeFormationEprouvette || $rowSession['Id_TypeFormation']==$IdTypeFormationInterne){
													if(DroitsFormationPlateforme(array($IdPosteAssistantFormationInterne,$IdPosteResponsableFormation))==0 && DroitsFormationPlateforme(array($IdPosteFormateur))==0){
														$onclick="";
													}
												}
												//Externe
												elseif($rowSession['Id_TypeFormation']==$IdTypeFormationExterne){
													if(DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne))==0){
														$onclick="";
													}
												}
												//AAA TC
												elseif($rowSession['Id_TypeFormation']==$IdTypeFormationTC){
													if(DroitsFormationPlateforme(array($IdPosteAssistantFormationTC))==0){
														$onclick="";
													}
												}
												if(date('Y-m-d',strtotime($rowSession['DateCreationForm']."+ 2 month"))>date('Y-m-d')){
													$formNew="<img width='30px' src='../../Images/New.png' border='0' alt='New' title='New'>";
												}
		
												$formation="
                                                    <table style='width:100%; height:100%; border-spacing:0;'>
														<tr>
															<td align='center' style='font-size:12px;' width='99%' ".$onclick.">".
												                $formPre.$formNew.$GroupeFormation.$Infos.$Annule.$Partie."<br/>
                                                               <font style='color:#000564;'> [".$DateDebutS." - ".$DateFinS."] </font><font style='color:#5159ff;'>".$Heures."</font><br/>".
												                $formateur."<br/>".
												                $Lieu."
                                                            </td>
                                                        </tr>
                                                        ";
												
								                $besoin="";
								                if($rowSession['Id_TypeFormation'] <> $IdTypeFormationEprouvette){
								                    if(DroitsFormationPlateforme($TableauIdPostesAF_RF) || $_SERVER['SERVER_NAME']=="192.168.20.3"){
								                        if($LangueAffichage=="FR")
								                        {
								                            $besoin="<a style='text-decoration:none;' href='javascript:OuvreFenetreBesoin(".$rowSession['Id_TypeFormation'].",".$rowSession['Id_Formation'].")'>
                												<img src='../../Images/B.png' width='15px' border='0' alt='Générer un besoin' title='Générer un besoin'>
                											</a>";
								                        }
								                        else
								                        {
								                            $besoin="<a style='text-decoration:none;' href='javascript:OuvreFenetreBesoin(".$rowSession['Id_TypeFormation'].",".$rowSession['Id_Formation'].")'>
                												<img src='../../Images/B.png' width='15px' border='0' alt='Generate a need' title='Generate a need'>
                											</a>";
								                        }
								                    }
								                }

												$InscriptionContenu="";
												$NbInscriptions="";
												if($rowSession['Nb_Stagiaire_Maxi']>0)
												{
    												if($LangueAffichage=="FR")
    												{
    												    $NbInscriptions="
                                                            <td style='font-size:10px;' valign='center' align='left'>
                                                                <b>Inscrits : ".$nbInscrit." / ".$rowSession['Nb_Stagiaire_Maxi']." (Pré : ".$NbPreInscrit.")</b>
                                                            </td>";
    												}
    												else
    												{
    												    $NbInscriptions="
                                                            <td style='font-size:10px;' valign='center' align='left'>
                                                                <b>Registered : ".$nbInscrit." / ".$rowSession['Nb_Stagiaire_Maxi']." (Pre : ".$NbPreInscrit.")</b>
                                                            </td>";
    												}
													
													//Vérifier si le remplissage du contenu 2 est terminé 
													$req="SELECT form_session_personne.Id 
													FROM form_session_personne 
													LEFT JOIN form_session ON form_session_personne.Id_Session=form_session.Id
													LEFT JOIN form_formation ON form_session.Id_Formation=form_formation.Id 
													WHERE form_session_personne.Suppr=0 
													AND form_session_personne.Validation_Inscription=1
													AND form_session_personne.Id_Session=".$rowSession['Id']."
													AND (
														(
														form_formation.Id_TypeFormation=3
														AND 
															(Presence=0 
															OR (Presence<0 AND MotifAbsence='')
															OR (Presence=1 AND EvaluationAChaud='' AND 
																(SELECT COUNT(Id) 
																FROM form_session_personne_document
																WHERE Suppr=0 
																AND Id_Document=6
																AND DateHeureRepondeur>'0001-01-01'
																AND Id_Session_Personne=form_session_personne.Id)=0
															)
															)
														)
														OR 
														(
														form_formation.Id_TypeFormation<>3
														AND 
															(Presence=0 
															OR (Presence<0 AND MotifAbsence='')
															OR (Presence=1 AND DdePriseEnChargeEnvoyee='')
															OR (Presence=1 AND AccordPriseEnCharge='')
															OR (Presence=1 AND TraitementConvention='')
															OR (Presence=1 AND FeuillePresence='')
															OR (Presence=1 AND EvaluationAChaud='')
															)
														)
														)
													";
													$ResultContenu2=mysqli_query($bdd,$req);
													$NbContenu2=mysqli_num_rows($ResultContenu2);
													$imgContenu2="dossierVide";
													if($NbContenu2>0){
														$imgContenu2="dossierJaune";
													}
    												if($LangueAffichage=="FR")
    												{
    												    $InscriptionContenu="
                                                            <img ".$onclickInscription." width='15px' src='../../Images/I.png' border='0' alt='Inscription' title='Inscription'>
                                                            <img ".$onclickContenu." width='15px' src='../../Images/classe.png' border='0' alt='contenu' title='contenu'>
															<img ".$onclickContenu2." width='15px' src='../../Images/".$imgContenu2.".png' border='0' alt='informations' title='informations'>";
    												}
    												else
    												{
    												    $InscriptionContenu="
                                                            <img ".$onclickInscription." width='15px' src='../../Images/I.png' border='0' alt='Registration' title='Registration'>
                                                            <img ".$onclickContenu." width='15px' src='../../Images/classe.png' border='0' alt='contents' title='contents'>
															<img ".$onclickContenu2." width='15px' src='../../Images/".$imgContenu2.".png' border='0' alt='informations' title='informations'>
															";
    												}
												}
												$formation.="<tr><td height='20px' valign='bottom'><table style='width:100%; height:100%; border-spacing:0;'><tr>";
												$formation.=$NbInscriptions."</tr><tr><td align='right'>".$besoin.$diffusion.$convocation.$Traite.$InscriptionContenu."</td>";
												$formation.="</tr></table></td></tr>";
												$formation.="</table>";
											}
											else{if($rowSession['Id']==200){echo "<script>alert('Id_Session non affichée : ".$rowSession['Id']."');</script>";}}
										}
									}
								}
							}
							$onmouse="";
							if($formation<>"")
							{
								if($typeAffichage=="formateur")
								{
									$couleur=$couleurFormateur;
									if($couleurSession==$rouge){$couleur=$rouge;}
								}
								else{$couleur=$couleurSession;}
								$onmouse="onMouseOver=\"Surbrillance('".$id."','Over','".$couleur."');\" onMouseOut=\"Surbrillance('".$id."','Out','".$couleur."');\" ";
							}
							$taille=1;
							if($colspan<>""){$taille=$val*1;}
							echo "<td height='30px' ".$colspan." width='".$taille."%' align='center' style='background-color:".$couleur.";word-break:break-all;border:1px solid #cccccc;' class=\"td_".$id."\" ".$onmouse." >".$formation."</td>\n";
							if($min==0){$min=15;}
							elseif($min==15){$min=30;}
							elseif($min==30){$min=45;}
							else{$min=0;$heure++;}
							if($heureFin<>"00:00:00")
							{
								$heure=intval(substr($heureFin,0,2));
								$min=intval(substr($heureFin,3,2));
							}
						}
						echo "</tr>\n";
					}
				}
				
				if($nbSansHeures>0)
				{
					//CAS DES FORMATIONS SANS HEURES DE DEBUT ET DE FIN
					if($nbSession>0)
					{
						$id="";
						$couleur="#eff7ff";
						mysqli_data_seek($resultSessions,0);
						while($rowSession=mysqli_fetch_array($resultSessions))
						{
							if($rowSession['DateSession']==$tmpDate)
							{
								if($rowSession['Heure_Debut']=="00:00:00")
								{
									$bExiste=0;
									$couleurSession="";
									$onclick="";

									$nbInscrit=$rowSession['NbInscrit'];
									$NbPreInscrit=$rowSession['NbPreInscrit'];
									$Annule="";
									if($typeAffichage=="session")
									{
										$couleurSession=$transparent;
										//Si nb stagiaire maxi atteint
										if($rowSession['Annule']==1){$couleurSession=$rouge;}
										elseif($nbInscrit>=$rowSession['Nb_Stagiaire_Maxi'] && $rowSession['Nb_Stagiaire_Maxi']>0){$couleurSession=$vert;}
										elseif($nbInscrit>=$rowSession['Nb_Stagiaire_Mini'] && $rowSession['Nb_Stagiaire_Maxi']>0 && $nbInscrit>0){$couleurSession=$bleu;}
									}
									if($rowSession['Annule']==1)
									{
										$couleurSession=$rouge;
										if($LangueAffichage=="FR"){$Annule=" [ANNULEE]";}
										else{$Annule=" [CANCELED]";}
									}
									for($k=0;$k<=(sizeof($tabForm)-1);$k++)
									{
										if($tabForm[$k]==$rowSession['Id_SessionDate']){$bExiste=1;}
									}
									//Convocations envoyées
									$nbConvocation=$rowSession['NbConvocationNonEnvoyee'];
									
									//Session terminée d'être traité = Validation des inscriptions + validation de la présence
									$nbNonTraite=$rowSession['NbNonTraite'];
									
									//Personnes de la session 
									$nbSPersonne=$rowSession['NbSPersonne'];
									
									//Date de début de la session 
									$reqSDate="SELECT DateSession FROM form_session_date WHERE Suppr=0 AND Id_Session=".$rowSession['Id']." ORDER BY DateSession ASC ";
									$resultSDate=mysqli_query($bdd,$reqSDate);
									$nbSDate=mysqli_num_rows($resultSDate);
									$DateDebutS="";
									if($nbSDate>0){
										$rowSDate=mysqli_fetch_array($resultSDate);
										$DateDebutS=AfficheDateJJ_MM_AAAA($rowSDate['DateSession']);
									}
									
									//Date de fin de la session 
									$reqSDate="SELECT DateSession FROM form_session_date WHERE Suppr=0 AND Id_Session=".$rowSession['Id']." ORDER BY DateSession DESC ";
									$resultSDate=mysqli_query($bdd,$reqSDate);
									$nbSDate=mysqli_num_rows($resultSDate);
									$DateFinS="";
									if($nbSDate>0){
										$rowSDate=mysqli_fetch_array($resultSDate);
										$DateFinS=AfficheDateJJ_MM_AAAA($rowSDate['DateSession']);
									}
									$nbPartie=1;
									for($k=0;$k<=(sizeof($tabSessionDate)-1);$k++)
									{
										if($tabSessionDate[$k]==$rowSession['Id']){$nbPartie++;}
									}
									$tabSessionDate[$itab2]=$rowSession['Id'];
									if($bExiste==0)
									{
										$Id_Langue=0;
										if($nbParam>0)
										{
											mysqli_data_seek($resultParam,0);
											while($rowParam=mysqli_fetch_array($resultParam))
											{
												if($rowParam['Id_Formation']==$rowSession['Id_Formation']){$Id_Langue=$rowParam['Id_Langue'];}
											}
										}
										$Infos="";
										if($nbInfos>0){
											mysqli_data_seek($resultInfos,0);
											while($rowInfo=mysqli_fetch_array($resultInfos))
											{
												if($rowInfo['Id_Formation']==$rowSession['Id_Formation'] && $rowInfo['Id_Langue']==$Id_Langue)
												{
													if($rowSession['Recyclage']==0){$Infos=stripslashes($rowInfo['Libelle']);}
													else{$Infos=stripslashes($rowInfo['LibelleRecyclage']);}
												}
											}
										}
										if($LangueAffichage=="FR"){$Lieu="<i>Lieu non défini</i>";}
										else{$Lieu="<i>Undefined location</i>";}
										if($rowSession['Lieu']){$Lieu="<i>".$rowSession['Lieu']."</i>";}
										$Partie="";
										$onclickInscription="";
										if($Modif==1)
										{
											$onclick="onclick=\"ModifierSession('".$rowSession['Id']."')\"";
											$onclickContenu="onclick=\"ContenuSession('".$rowSession['Id']."','".$tmpDate."')\"";
											$onclickContenu2="onclick=\"Contenu2Session('".$rowSession['Id']."','".$tmpDate."')\"";
											$onclickInscription="onclick=\"InscrireSession('".$rowSession['Id']."')\"";
										}
										if($LangueAffichage=="FR"){if($rowSession['Nb']>1){$Partie=" (Partie".$nbPartie.")";}}
										else{if($rowSession['Nb']>1){$Partie=" (Part".$nbPartie.")";}}
										$GroupeFormation="";
										$id=$rowSession['Id'];
										if($rowSession['Id_GroupeSession']>0 && $rowSession['Formation_Liee']>0)
										{
											if($LangueAffichage=="FR"){$GroupeFormation="<b>Groupe</b> : ".$rowSession['Groupe']."<br>";}
											else{$GroupeFormation="<b>Group</b> : ".$rowSession['Groupe']."<br>";}
											$id="GR".$rowSession['Id_GroupeSession'];
											if($Modif==1){$onclick="onclick=\"ModifierSessionGroupe('".$rowSession['Id_GroupeSession']."')\"";}
										}
										$diffusion="";
										if($rowSession['Diffusion_Creneau']==1 && $NbSPrestations>0)
										{
											if($LangueAffichage=="FR")
											{
												$diffusion="<img src='../../Images/diffuser.png' style='cursor: pointer;' onclick=\"RediffuserSession('".$rowSession['Id']."');\" width='15px' border='0' alt='Diffuser' title='Diffuser'>";
											}
											else
											{
												$diffusion="<img src='../../Images/diffuser.png' style='cursor: pointer;' onclick=\"RediffuserSession('".$rowSession['Id']."');\" width='15px' border='0' alt='spread' title='spread'>";
											}
										}
										$convocation="";
										if($nbConvocation==0 && $nbInscrit>0)
										{
											$convocation="<img src='../../Images/C2.png' style='cursor: default;' width='15px' border='0' alt='Convocation envoyée' title='Convocation envoyée'>";
										}
										elseif($nbConvocation>0 && $nbInscrit>0)
										{
											$champs="";
											if($rowSession['Id_Formateur']==0)
											{
												if($LangueAffichage=="FR"){$champs.="formateur ";}
												else{$champs.="trainer ";}
											}
											if($rowSession['Id_Lieu']==0 && $rowSession['nom_fichier']=="")
											{
												if($LangueAffichage=="FR"){$champs.="lieu ";}
												else{$champs.="place ";}
											}
											if($LangueAffichage=="FR")
											{
												$convocation="<img src='../../Images/C.png' width='15px' border='0'  onclick=\"EmailConvocation('".$rowSession['Id']."','".$champs."');\" alt='Envoyer la convocation' title='Envoyer la convocation'>";
											}
											else
											{
												$convocation="<img src='../../Images/C.png' width='15px' border='0'  onclick=\"EmailConvocation('".$rowSession['Id']."','".$champs."');\" alt='Send the convocation' title='Send the convocation'>";
											}
										}
										$Traite="";
										if($nbNonTraite==0 && $nbSPersonne>0){
											if($LangueAffichage=="FR"){$Traite="<img src='../../Images/tick.png' style='cursor: default;' width='15px' border='0' alt='Traité' title='Traité'>";}
											else{$Traite="<img src='../../Images/tick.png' style='cursor: default;' width='15px' border='0' alt='Completed' title='Completed'>";}
										}
										if($LangueAffichage=="FR"){$formateur="Formateur non défini";}
										else{$formateur="Undefined trainer";}
										$couleurFormateur="#eff7ff";
										if($rowSession['Formateur']<>"")
										{
											$formateur=$rowSession['Formateur'];
											$couleurFormateur=$rowSession['CouleurFormateur'];
										}
										$HorairesTexte="";
										if($LangueAffichage=="FR"){$HorairesTexte="Horaires non définis";}
										else{$HorairesTexte="Unresolved hours";}
										
										//Eprouvette & Interne
										if($rowSession['Id_TypeFormation']==$IdTypeFormationEprouvette || $rowSession['Id_TypeFormation']==$IdTypeFormationInterne){
											if(DroitsFormationPlateforme(array($IdPosteAssistantFormationInterne,$IdPosteResponsableFormation))==0  && DroitsFormationPlateforme(array($IdPosteFormateur))==0){
												$onclick="";
											}
										}
										//Externe
										elseif($rowSession['Id_TypeFormation']==$IdTypeFormationExterne){
											if(DroitsFormationPlateforme(array($IdPosteAssistantFormationExterne))==0){
												$onclick="";
											}
										}
										//AAA TC
										elseif($rowSession['Id_TypeFormation']==$IdTypeFormationTC){
											if(DroitsFormationPlateforme(array($IdPosteAssistantFormationTC))==0){
												$onclick="";
											}
										}
										$formNew="";
										$formPre="";
										if(date('Y-m-d',strtotime($rowSession['DateCreationForm']."+ 2 month"))>date('Y-m-d')){
											$formNew="<img width='30px' src='../../Images/New.png' border='0' alt='New' title='New'>";
										}
										if($NbPreInscrit>0){
											if($LangueAffichage=="FR"){
												$formPre="<img width='30px' src='../../Images/exclamation.png' border='0' alt='Préinscription à valider' title='Préinscription à valider'>";
											}
											else{
												$formPre="<img width='30px' src='../../Images/exclamation.png' border='0' alt='Pre-registration to validate' title='Pre-registration to validate'>";
											}
										}
										else{
												if($tmpDate<date('Y-m-d')){
													//Vérifier si la session a des présences non validé ou des compétences à valider 
													$reqNonTraite="SELECT Id 
																FROM form_session_personne 
																WHERE (
																		(Presence=1 AND
																			(SELECT COUNT(form_session_personne_qualification.Id)
																			FROM form_session_personne_qualification
																			WHERE form_session_personne_qualification.Id_Session_Personne=form_session_personne.Id
																			AND form_session_personne_qualification.Suppr=0
																			AND form_session_personne_qualification.Etat=0
																			)>0
																		) 
																		OR 
																		(Validation_Inscription=1 AND Presence=0)) 
																AND Suppr=0 
																AND Id_Session=".$rowSession['Id'];
													$resultNbNonTraite=mysqli_query($bdd,$reqNonTraite);
													$nbNonTraite=mysqli_num_rows($resultNbNonTraite);
													if($nbNonTraite>0){
														if($LangueAffichage=="FR"){
															$formPre="<img width='30px' src='../../Images/exclamationJaune.png' border='0' alt='Session à traiter' title='Session à traiter'>";
														}
														else{
															$formPre="<img width='30px' src='../../Images/exclamationJaune.png' border='0' alt='Session to be treated' title='Session to be treated'>";
														}
													}
												}
												
												
												
											}
										$formation="
                                                    <table style='width:100%; height:100%; border-spacing:0;'>
                                                        <tr>
                                                            <td align='center' style='font-size:12px;' width='99%' ".$onclick.">".
                                                                $formPre.$formNew.$GroupeFormation.$Infos.$Annule.$Partie."<br/>
																<font style='color:#000564;'> [".$DateDebutS." - ".$DateFinS."]</font> <font style='color:#5159ff;'>".
                                                                $HorairesTexte."</font><br/>".
                                                                $formateur."<br/>".
                                                                $Lieu."
                                                            </td>
                                                        </tr>";
                                        $InscriptionContenu="";
                                        $NbInscriptions="";
                                        
                                        $besoin="";
                                        if($rowSession['Id_TypeFormation'] <> $IdTypeFormationEprouvette){
                                            if(DroitsFormationPlateforme($TableauIdPostesAF_RF) || $_SERVER['SERVER_NAME']=="192.168.20.3"){
                                                if($LangueAffichage=="FR")
                                                {
                                                    $besoin="<a style='text-decoration:none;' href='javascript:OuvreFenetreBesoin(".$rowSession['Id_TypeFormation'].",".$rowSession['Id_Formation'].")'>
                												<img src='../../Images/B.png' width='15px' border='0' alt='Générer un besoin' title='Générer un besoin'>
                											</a>";
                                                }
                                                else
                                                {
                                                    $besoin="<a style='text-decoration:none;' href='javascript:OuvreFenetreBesoin(".$rowSession['Id_TypeFormation'].",".$rowSession['Id_Formation'].")'>
                												<img src='../../Images/B.png' width='15px' border='0' alt='Generate a need' title='Generate a need'>
                											</a>";
                                                }
                                            }
                                        }
                                        
										if($rowSession['Nb_Stagiaire_Maxi']>0)
										{
											if($LangueAffichage=="FR")
											{
											    $NbInscriptions="<td style='font-size:10px;' valign='center' align='left'><b>Inscrits : ".$nbInscrit." / ".$rowSession['Nb_Stagiaire_Maxi']."  (Pré : ".$NbPreInscrit.")</b></td>";
											    $InscriptionContenu="<img ".$onclickInscription." width='15px' src='../../Images/I.png' border='0' alt='Inscription' title='Inscription'><img ".$onclickContenu." width='15px' src='../../Images/classe.png' border='0' alt='contenu' title='contenu'><img ".$onclickContenu2." width='15px' src='../../Images/dossierJaune.png' border='0' alt='informations' title='informations'>";
											}
											else
											{
											    $NbInscriptions="<td style='font-size:10px;' valign='center' align='left'><b>Registered : ".$nbInscrit." / ".$rowSession['Nb_Stagiaire_Maxi']." (Pre : ".$NbPreInscrit.")</b></td>";
											    $InscriptionContenu="<img ".$onclickInscription." width='15px' src='../../Images/I.png' border='0' alt='Inscription' title='Inscription'><img ".$onclickContenu." width='15px' src='../../Images/classe.png' border='0' alt='contents' title='contents'><img ".$onclickContenu2." width='15px' src='../../Images/dossierJaune.png' border='0' alt='informations' title='informations'>";
											}
										}
										$formation.="<tr><td height='20px' valign='bottom'><table style='width:100%; height:100%; border-spacing:0;'><tr>";
										$formation.=$NbInscriptions."</tr><tr><td align='right'>".$besoin.$diffusion.$convocation.$Traite.$InscriptionContenu."</td>";
										$formation.="</tr></table></td></tr>";
										$formation.="</table>";
										if($typeAffichage=="formateur")
										{
											$couleur=$couleurFormateur;
											if($couleurSession==$rouge){$couleur=$couleurSession;}
										}
										else
										{
											$couleur=$couleurSession;
										}
										
										echo "<tr>\n";
										echo "<td height='30px' colspan='53' align='center' style='background-color:".$couleur.";border:1px solid #cccccc;' class=\"td_".$id."\" onMouseOver=\"Surbrillance('".$id."','Over','".$couleur."');\" onMouseOut=\"Surbrillance('".$id."','Out','".$couleur."');\" width='1%'>".$formation."</td>\n";
										echo "</tr>\n";
									}
								}
							}
						}
					}
				}
				echo "</table>";
				echo "</td>\n";
				$semaineEC=date('W', strtotime($tmpDate." + 0 month"));
				echo "</tr>\n";
				if($jour==0)
				{
					echo "<tr>";
					echo "<td height='5px;'></td>";
					echo "</tr>";
				}
				//Jour suivant
				$tmpDate = date("Y-m-d", strtotime($tmpDate." + 1 day"));
			}
			?>
		</table>
	</td></tr>
	<?php
		//Légende si type d'affichage = Session
		if($typeAffichage=="session")
		{
	?>
	<tr>
		<td>
			<table>
				<tr>
					<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Légende";}else{echo "Legend";}?> : </td>
					<td class="Libelle" style="background-color:<?php echo $transparent;?>;"><?php if($LangueAffichage=="FR"){echo "session incomplète";}else{echo "incomplete session";}?></td>
					<td class="Libelle" style="background-color:<?php echo $rouge;?>;"><?php if($LangueAffichage=="FR"){echo "session annulée";}else{echo "session canceled";}?></td>
					<td class="Libelle" style="background-color:<?php echo $bleu;?>;"><?php if($LangueAffichage=="FR"){echo "session confirmée";}else{echo "confirmed session";}?></td>
					<td class="Libelle" style="background-color:<?php echo $vert;?>;"><?php if($LangueAffichage=="FR"){echo "session complète";}else{echo "full session";}?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<?php
		}
		else
		{
	?>
	<tr>
		<td>
			<table>
				<tr>
					<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Légende";}else{echo "Legend";}?> : </td>
					<td class="Libelle" style="background-color:<?php echo $transparent;?>;"><?php if($LangueAffichage=="FR"){echo "Pas de formateur";}else{echo "No trainer";}?></td>
					<td class="Libelle" style="background-color:<?php echo $rouge;?>;"><?php if($LangueAffichage=="FR"){echo "session annulée";}else{echo "session canceled";}?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<?php
		}
	?>
	</table>
</td>
</tr>
</table>
	</form>
<?php //echo date("H:i:s")."<br>"; ?>
</body>
</html>
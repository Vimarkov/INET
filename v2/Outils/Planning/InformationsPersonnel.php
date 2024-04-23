<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModifInfosPersonnel(Id_Personne,Id_Prestation,Id_Pole)
		{var w=window.open("ModifierInformationsPersonnel.php?Id_Personne="+Id_Personne+"&Id_Prestation="+Id_Prestation+"&Id_Pole="+Id_Pole,"PageInfos","status=no,menubar=no,scrollbars=1,width=800,height=400");
		w.focus();
		}
	function OuvreFenetreInfosPersoExport(Id_Prestation,Id_Pole)
		{window.open("InformationsPersonnel_Export.php?Id_Prestation="+Id_Prestation+"&Id_Pole="+Id_Pole,"PageInfosPersoExport","status=no,menubar=no,scrollbars=1,width=800,height=430");}
	function OuvreFenetreReini(Id){
		if(window.confirm('Etes-vous sûr de vouloir réinitialiser le mot de passe ?')){
			var w=window.open("ReinitialiseMotDePasse.php?Id="+Id,"PageUtilisateur","status=no,menubar=no,scrollbars=yes,width=60,height=40");
			w.focus();
			}
		}
</script>

<form action="InformationsPersonnel.php" method="post">
<table style="width:100%; cellpadding:0; cellspacing:0; align:center;">
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; cellpadding:0; cellspacing:0;">
				<tr>
					<td class="TitrePage">Gestion du planning # Informations du personnel</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><br/></td>
	</tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
				<tr>
					<td width="30%">
						&nbsp; Prestation :
						<select class="prestation" name="prestations" onchange="submit();">
						<?php
						$req = "
                            SELECT DISTINCT 
                                (new_competences_personne_poste_prestation.Id_Prestation),
                                (
                                    SELECT new_competences_prestation.Libelle
                                    FROM new_competences_prestation
                                    WHERE new_competences_prestation.Id = new_competences_personne_poste_prestation.Id_Prestation
                                ) AS NomPrestation
                            FROM
                                new_competences_personne_poste_prestation
                            WHERE
                                new_competences_personne_poste_prestation.Id_Personne=".$_SESSION['Id_Personne']."
                            ORDER BY NomPrestation;";
						$resultPrestation=mysqli_query($bdd,$req);
						$nbPrestation=mysqli_num_rows($resultPrestation);
						
						$PrestationSelect = 0;
						$Selected = "";
						if ($nbPrestation > 0)
						{
							if (!empty($_GET['Id_Prestation']))
							{
								if ($PrestationSelect == 0){$PrestationSelect = $_GET['Id_Prestation'];}
								while($row=mysqli_fetch_array($resultPrestation))
								{
									if ($row[0] == $_GET['Id_Prestation']){$Selected = "Selected";}
									echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
									$Selected = "";
								}
							}
							elseif (!empty($_POST['prestations']))
							{
								if ($PrestationSelect == 0){$PrestationSelect = $_POST['prestations'];}
								while($row=mysqli_fetch_array($resultPrestation))
								{
									if ($row[0] == $_POST['prestations']){$Selected = "Selected";}
									echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
									$Selected = "";
								}
							}
							else
							{
								while($row=mysqli_fetch_array($resultPrestation))
								{
									if ($PrestationSelect == 0){$PrestationSelect = $row[0];}
									echo "<option name='".$row[0]."' value='".$row[0]."'>".$row[1]."</option>";
								}
							}
						 }
						 ?>
						</select>
					</td>
					<td width=30%>
						&nbsp; Pôle :
						<select class="pole" name="pole" onchange="submit();">
						<?php
						$reqPole = "
                            SELECT new_competences_pole.Id, new_competences_pole.Libelle
                            FROM new_competences_pole
                            WHERE new_competences_pole.Id_Prestation =".$PrestationSelect.";";
						$resultPole=mysqli_query($bdd,$reqPole);
						$nbPole=mysqli_num_rows($resultPole);
						
						$PoleSelect = 0;
						$Selected = "";
						if ($nbPole > 0)
						{
							echo "<option name='0' value='0' Selected></option>";
							if (!empty($_GET['Id_Pole']))
							{
								if ($PoleSelect == 0){$PoleSelect = $_GET['Id_Pole'];}
								while($row=mysqli_fetch_array($resultPole))
								{
									if ($row[0] == $_GET['Id_Pole']){$Selected = "Selected";}
									echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
									$Selected = "";
								}
							}
							elseif (!empty($_POST['pole']))
							{
								if ($PoleSelect == 0){$PoleSelect = $_POST['pole'];}
								while($row=mysqli_fetch_array($resultPole))
								{
									if ($row[0] == $_POST['pole']){$Selected = "Selected";}
									echo "<option name='".$row[0]."' value='".$row[0]."' ".$Selected.">".$row[1]."</option>";
									$Selected = "";
								}
							}
							else
							{
								while($row=mysqli_fetch_array($resultPole))
								{
									if ($PoleSelect == 0){$PoleSelect = 0;}
									echo "<option name='".$row[0]."' value='".$row[0]."'>".$row[1]."</option>";
								}
							}
						 }
						 ?>
						</select>
					</td>
					<td width=5%>
						<?php
						echo "&nbsp;";
						?>
						<a style="text-decoration:none;" href="javascript:OuvreFenetreInfosPersoExport(<?php echo $PrestationSelect;?>,<?php echo $PoleSelect;?>);">
						<?php
						echo "<img src='../../Images/excel.gif' border='0' alt='Excel' title='Export Excel'>";
						echo "</a>";
						echo "&nbsp;";
						?>
					</td>
				</tr>
			</table>
		</td>
	<tr>
		<td><br/></td>
	</tr>
	<tr>
		<td>
			<table style="width:100%;">
				<tr>
					<td>
						<table class="TableCompetences" style="width:100%;">
							<tr>
								<td class="EnTeteTableauCompetences" width="10%">Personne</td>
								<td class="EnTeteTableauCompetences" width="5%">Métier</td>
							<?php
								if ($nbPole > 0){
							?>
								<td class="EnTeteTableauCompetences" width="5%">Pôles</td>
							<?php
								}
							?>
								<td class="EnTeteTableauCompetences" width="10%">Date de naissance</td>
								<td class="EnTeteTableauCompetences" width="10%">Contrat</td>
								<td class="EnTeteTableauCompetences" width="10%">Tel. pro fixe</td>
								<td class="EnTeteTableauCompetences" width="10%">Tel. pro mobile</td>
								<td class="EnTeteTableauCompetences" width="10%">Email</td>
								<td class="EnTeteTableauCompetences" width="6%">N° badge</td>
								<td class="EnTeteTableauCompetences" width="10%">NG/ST</td>
								<td class="EnTeteTableauCompetences" width="10%">Login</td>

								<td class="EnTeteTableauCompetences"></td>
								<td class="EnTeteTableauCompetences"></td>
							</tr>
							<?php
							//Personnes  présentent sur cette prestation à ce jour
							$DateDuJour = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
							$Req_Pole="";
							if ($PoleSelect > 0){$Req_Pole = "AND new_competences_personne_prestation.Id_Pole =".$PoleSelect." ";}
							$req = "
                                SELECT DISTINCT
                                    new_competences_personne_prestation.Id_Personne,
                                    CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
                                    (SELECT new_competences_metier.Libelle FROM new_competences_metier WHERE new_competences_metier.Id = Tb_Personne_Metier.Id_Metier) AS Metier,
                                    (SELECT new_competences_metier.Code FROM new_competences_metier WHERE new_competences_metier.Id = Tb_Personne_Metier.Id_Metier) AS CodeMetier,
                                    new_rh_etatcivil.TelephoneProFixe,
                                    new_rh_etatcivil.TelephoneProMobil,
                                    new_rh_etatcivil.EmailPro,
                                    new_rh_etatcivil.NumBadge,
                                    new_rh_etatcivil.Matricule,
                                    new_rh_etatcivil.Date_Naissance,
                                    new_rh_etatcivil.Login,
									new_rh_etatcivil.Contrat
                                FROM
                                    (
                                    new_competences_personne_prestation
                                    RIGHT JOIN new_rh_etatcivil
                                        ON new_rh_etatcivil.Id = new_competences_personne_prestation.Id_Personne
                                    )
                                LEFT JOIN (SELECT Id_Personne, Id_Metier FROM new_competences_personne_metier WHERE Futur=0) AS Tb_Personne_Metier
                                    ON Tb_Personne_Metier.Id_Personne = new_rh_etatcivil.Id
                                WHERE
                                    new_competences_personne_prestation.Id_Prestation =".$PrestationSelect." "
                                    .$Req_Pole."
                                    AND
                                    (
                                        (
                                        new_competences_personne_prestation.Date_Debut<='".$DateDuJour."'
                                        AND new_competences_personne_prestation.Date_Fin>='".$DateDuJour."'
                                        )
                                        OR
                                        (
                                        new_competences_personne_prestation.Date_Debut<='".$DateDuJour."'
                                        AND new_competences_personne_prestation.Date_Fin>='".$DateDuJour."'
                                        )
                                        OR
                                        (
                                        new_competences_personne_prestation.Date_Debut>='".$DateDuJour."'
                                        AND new_competences_personne_prestation.Date_Fin<='".$DateDuJour."'
                                        )
                                    )
                                ORDER BY
                                    Personne ASC;";
							$resultPersonne=mysqli_query($bdd,$req);
							$nbPersonne=mysqli_num_rows($resultPersonne);
							
							//Accès de la personne connectée
							$b_acces = 0;
							$reqnew_acces = "
                                SELECT
                                    new_competences_personne_poste_prestation.Id_Pole
                                FROM
                                    new_competences_personne_poste_prestation
                                WHERE
                                    new_competences_personne_poste_prestation.Id_Personne=".$_SESSION['Id_Personne']."
                                    AND
                                    (
                                        new_competences_personne_poste_prestation.Id_Poste=2
                                        OR new_competences_personne_poste_prestation.Id_Poste=3
                                    )
                                    AND new_competences_personne_poste_prestation.Id_Prestation=".$PrestationSelect.";";
							$personnenew_acces=mysqli_query($bdd,$reqnew_acces);
							$nbPersonnenew_acces=mysqli_num_rows($personnenew_acces);
							
							if ($nbPersonnenew_acces > 0)
							{
								while($rowPersonnenew_acces=mysqli_fetch_array($personnenew_acces))
								{
									if($b_acces == 0)
									{
										if ($rowPersonnenew_acces[0] == $PoleSelect){$b_acces = 1;}
									}
								}
							}
							if($nbPersonne>0)
							{
								$Couleur="#EEEEEE";
								while($row=mysqli_fetch_array($resultPersonne))
								{
									if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
									else{$Couleur="#EEEEEE";}

									echo "<tr bgcolor='".$Couleur."'>";
									echo "<td>".$row['Personne']."</td>"; //Personne
									echo "<td title='".$row['Metier']."'>".$row['CodeMetier']."</td>"; //Métier
									if ($nbPole > 0)
									{
										//Recherche les pôles de la personne
										$reqPole = "
                                            SELECT DISTINCT
                                                new_competences_personne_prestation.Id_Pole,
                                                (SELECT Libelle FROM new_competences_pole WHERE Id=new_competences_personne_prestation.Id_Pole) AS Pole
                                            FROM
                                                new_competences_personne_prestation
                                            WHERE
                                                Id_Personne=".$row['Id_Personne']."
                                                AND Id_Prestation=".$PrestationSelect."
                                                AND
                                                (
                                                    (new_competences_personne_prestation.Date_Debut<='".$DateDuJour."' AND new_competences_personne_prestation.Date_Fin>='".$DateDuJour."')
                                                    OR (new_competences_personne_prestation.Date_Debut<='".$DateDuJour."' AND new_competences_personne_prestation.Date_Fin>='".$DateDuJour."')
                                                    OR (new_competences_personne_prestation.Date_Debut>='".$DateDuJour."' AND new_competences_personne_prestation.Date_Fin<='".$DateDuJour."')
                                                );";
										$poleJour=mysqli_query($bdd,$reqPole);
										$poles="";
										while($rowPoles=mysqli_fetch_array($poleJour))
										{
											if($rowPoles['Id_Pole']<>0){$poles.=$rowPoles['Pole']."<br>";}
										}
										if($poles<>""){$poles=substr($poles,0,-4);}
										echo "<td>".$poles."</td>"; //Poles
									}
	
									echo "<td>".AfficheDateFR($row['Date_Naissance'])."</td>"; //Tel pro fixe
									echo "<td>".$row['Contrat']."</td>"; //Contrat
									echo "<td>".$row['TelephoneProFixe']."</td>"; //Tel pro fixe
									echo "<td>".$row['TelephoneProMobil']."</td>"; //Tel pro mobile
									echo "<td>".$row['EmailPro']."</td>"; //Email
									echo "<td>".$row['NumBadge']."</td>"; //N° Badge
									echo "<td>".$row['Matricule']."</td>"; //Matricule
									echo "<td>".$row['Login']."</td>"; //Login

									echo "<td width=10>";
									if ($b_acces == 1)
									{
										echo "<a class='Modif' href='javascript:OuvreFenetreModifInfosPersonnel(".$row['0'].",".$PrestationSelect.",".$PoleSelect.");'>";
										echo "<img src='../../Images/Modif.gif' border='0' alt='Modification' title='Modifier'>";
										echo "</a>";
									}
									echo "</td>";
									echo "<td width=10>";
									if ($b_acces == 1)
									{
										//Ajout de la possibilité de réinitialiser 
										echo "<a href='javascript:OuvreFenetreReini(".$row['Id_Personne'].")'>";
										if($_SESSION['Langue']=="EN"){echo "<img src='../../Images/Reinitilisation.png' border='0' alt='Reinitialise' title='Reinitialise'>";}
										else{echo "<img src='../../Images/Reinitilisation.png' border='0' alt='Réinitialiser' title='Réinitialiser'>";}
										echo "</a>";
									}
									echo "</td>";
									echo "</tr>";
								}
							}	
							mysqli_free_result($resultPersonne);	// Libération des résultats
						?>
						</table>
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
<?php
require("../Menu.php");
?>
<script>
	function OuvreFenetreProfil(Mode,Id)
		{window.open("Competences/Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1040,height=800");}
</script>
<?php
if($_POST)
{
	if($_POST["TypeDemande"]=="Suppr")
	{
		//Traitement des droits cochés pour la suppression
		if(isset($_POST["DroitsSuppression"]))
		{
			$RequeteDroitsSuppression="";
			$DroitsSuppression=$_POST["DroitsSuppression"];
			
			for($i=0;$i<sizeof($DroitsSuppression);$i++)
			{
				if(isset($DroitsSuppression[$i]))
				{
					$TableauDroitsSuppression=explode("|",$DroitsSuppression[$i]);
					
					if($RequeteDroitsSuppression==""){$RequeteDroitsSuppression="DELETE FROM new_acces WHERE ";}
					else{$RequeteDroitsSuppression.=" OR ";}
					
					$RequeteDroitsSuppression.="
					(
						Droits='".$TableauDroitsSuppression[0]."'
						AND (Login='".$TableauDroitsSuppression[1]."'
						OR Id_Personne='".$TableauDroitsSuppression[2]."')
						AND Page='".$TableauDroitsSuppression[3]."'
						AND Dossier1='".$TableauDroitsSuppression[4]."'
						AND Dossier2='".$TableauDroitsSuppression[5]."'
					)";
				}
			}

			if($RequeteDroitsSuppression != ""){$ResultDroitsSuppression=mysqli_query($bdd,$RequeteDroitsSuppression);}
		}
	}
	else
	{	
		//Traitement des droits cochés pour l'ajout
		if(isset($_POST["DossiersAjout"]) && isset($_POST["PersonnesAjout"]) && isset($_POST["TypeDroits"]))
		{
			$RequeteDroitsAjout="";
			$RequeteSuppressionDroitsInitiaux="";
			$DossiersAjout=$_POST["DossiersAjout"];
			$PersonnesAjout=$_POST["PersonnesAjout"];
			$TypeDroits=$_POST["TypeDroits"];
			
			for($i=0;$i<sizeof($DossiersAjout);$i++)
			{
				if(isset($DossiersAjout[$i]))
				{
					$TableauDossiersAjout=explode("|",$DossiersAjout[$i]);
					
					for($j=0;$j<sizeof($PersonnesAjout);$j++)
					{
						if(isset($PersonnesAjout[$j]))
						{
							$TableauPersonnesAjout=explode("|",$PersonnesAjout[$j]);
							
							if($RequeteDroitsAjout=="")
							{
								$RequeteDroitsAjout="INSERT INTO new_acces (Droits, Login, Id_Personne, Page, Dossier1, Dossier2) VALUES ";
							}
							else{$RequeteDroitsAjout.=" , ";}
							
							$RequeteDroitsAjout.="
							(
								'".$TypeDroits."',
								'".$TableauPersonnesAjout[0]."',
								'".$TableauPersonnesAjout[1]."',
								'".$TableauDossiersAjout[0]."',
								'".$TableauDossiersAjout[1]."',
								'".$TableauDossiersAjout[2]."'
							)";
							
							$RequeteSuppressionDroitsInitiaux="
								DELETE
								FROM new_acces
								WHERE
									Login='".$TableauPersonnesAjout[0]."'
									AND Page='".$TableauDossiersAjout[0]."'
									AND Dossier1='".$TableauDossiersAjout[1]."'
									AND Dossier2='".$TableauDossiersAjout[2]."';";
						}
					}
				}
			}
			
			if($RequeteDroitsAjout != "")
			{
				$ResultSuppressionDroitsInitiaux=mysqli_query($bdd,$RequeteSuppressionDroitsInitiaux);
				$ResultDroitsAjout=mysqli_query($bdd,$RequeteDroitsAjout);
			}
		}
	}
}

$Requete_ListeDossiersAdministrateur="
	SELECT
		Id,
		Id_Dossier,
		(SELECT Libelle FROM dossiers_general WHERE Id=dossiers_admin.Id_Dossier) AS DOSSIERGENERAL_LIBELLE,
		(SELECT NomTable FROM dossiers_general WHERE Id=dossiers_admin.Id_Dossier) AS DOSSIERGENERAL_NOMTABLE
	FROM
		dossiers_admin
	WHERE
		Id_Personne='".$_SESSION['Id_Personne']."'
	ORDER BY
		DOSSIERGENERAL_LIBELLE;";
$Result_ListeDossiersAdministrateur=mysqli_query($bdd,$Requete_ListeDossiersAdministrateur);
$ListeDossiersAdministrateur="";
?>
	<form id="FormulaireAjout" method="POST" action="">
	<input type="hidden" name="TypeDemande" value="Ajout">
	
	<table class="GeneralPage" style="width:100%; border-spacing:0;">
		<tr>
			<td class="TitrePage">Administration des droits par dossier</td>
		</tr>
	</table>
	
	<br>
	
	<table class="GeneralPage" style="width:95%; height:95%; border-spacing:0; align:center;">
		<tr>
			<td class="TitrePage">AJOUT DES ACCES</td>
		</tr>
		<tr>
			<td>
				<div style="width:95%;height:200px;overflow:auto;">
					<table class="TableCompetences">
						<tr>
							<td><b>Catégorie (onglet)</b></td>
							<td><b>Sous-Dossier 1</b></td>
							<td><b>Sous-Dossier 2</b></td>
							<td><b>Ajout</b></td>
						</tr>
						<?php
						while($Row_ListeDossiersAdministrateur=mysqli_fetch_array($Result_ListeDossiersAdministrateur))
						{
							if($ListeDossiersAdministrateur==""){$ListeDossiersAdministrateur="'".$Row_ListeDossiersAdministrateur['DOSSIERGENERAL_NOMTABLE']."'";}
							else{$ListeDossiersAdministrateur.=",'".$Row_ListeDossiersAdministrateur['DOSSIERGENERAL_NOMTABLE']."'";}
							
							foreach($TableauSousDossiers as $ListeValeursSousDossiers)
							{
								$Dossiers=explode("|",$ListeValeursSousDossiers);
								if($Dossiers[0]==$Row_ListeDossiersAdministrateur['DOSSIERGENERAL_NOMTABLE'])
								{
									echo "
										<tr>
											<td>".$Row_ListeDossiersAdministrateur['DOSSIERGENERAL_LIBELLE']."</td>
											<td>".$Dossiers[1]."</td>
											<td>".$Dossiers[2]."</td>
											<td><input type='checkbox' name='DossiersAjout[]' value='".$ListeValeursSousDossiers."'></td>
										</tr>";
								}
							}
						}
						?>
					</table>
				</div>
			</td>
			<td>
				<div style="width:95%;height:200px;overflow:auto;">
					<table class="TableCompetences">
						<tr>
							<td><b>Plateforme</b></td>
							<td><b>Personne</b></td>
							<td><b>Ajout</b></td>
						</tr>
						<?php
						$Requete_ListePersonneLogin="
							SELECT
								new_rh_etatcivil.Id AS ID_PERSONNE,
								new_rh_etatcivil.Login AS LOGIN,
								CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS NOMPRENOM,
								IF
								(
									LENGTH(GROUP_CONCAT(DISTINCT new_competences_plateforme.Libelle SEPARATOR ', ')) > 35,
									CONCAT(SUBSTRING(GROUP_CONCAT(DISTINCT new_competences_plateforme.Libelle SEPARATOR ', '),1,35),' ...'),
									GROUP_CONCAT(DISTINCT new_competences_plateforme.Libelle SEPARATOR ', ')
								) AS PLATEFORMES
							FROM
								new_rh_etatcivil
								LEFT JOIN new_competences_personne_plateforme ON new_rh_etatcivil.Id=new_competences_personne_plateforme.Id_Personne
								LEFT JOIN new_competences_plateforme ON new_competences_personne_plateforme.Id_Plateforme=new_competences_plateforme.Id
							WHERE
								new_rh_etatcivil.Login!=''
								AND new_competences_personne_plateforme.Id_Plateforme NOT IN (11,14)
							GROUP BY
								new_rh_etatcivil.Id
							ORDER BY
								PLATEFORMES,
								NOMPRENOM;";
						$Result_ListePersonneLogin=mysqli_query($bdd,$Requete_ListePersonneLogin);
						while($Row_ListePersonneLogin=mysqli_fetch_array($Result_ListePersonneLogin))
						{
							echo "
								<tr>
									<td>".$Row_ListePersonneLogin['PLATEFORMES']."</td>
									<td><a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$Row_ListePersonneLogin['ID_PERSONNE']."\");'>".$Row_ListePersonneLogin['NOMPRENOM']."</a><td>
									<td><input type='checkbox' name='PersonnesAjout[]' value='".$Row_ListePersonneLogin['LOGIN']."|".$Row_ListePersonneLogin['ID_PERSONNE']."'></td>
								</tr>";
						}
						?>
					</table>
				</div>
			</td>
			<td>
				<table class="TableCompetences" valign="top">
					<tr>
						<td><b>Type de droits</b></td>
					</tr>
					<tr>
						<td>
							<select name="TypeDroits" size="3">
								<option value="Lecture">Lecture</option>
								<option value="Ecriture">Ecriture</option>
								<option value="Administrateur">Administrateur</option>
							</select>
						</td>
					</tr>
				</table>
				
				<br>
				<b>Lecture</b> : Lire les infos<br>
				<b>Ecriture</b> : Modifier/Supprimer les infos créées par l'utilisateur<br>
				<b>Administrateur</b> : Modifier/Supprimer toutes les infos
			</td>
		</tr>
		<tr>
			<td align="center" colspan="3">
				<input class="Bouton" type="submit" <?php if($LangueAffichage=="FR"){echo "value='AJOUTER'";}else{echo "value='ADD'";}?>>
			</td>
		</tr>
	</table>
	</form>
	
	<br>
	
	<!-- LISTE DES DROITS -->
	<form id="FormulaireSuppression" method="POST" action="">
	<input type="hidden" name="TypeDemande" value="Suppr">
	<table class="GeneralPage" style="width:95%; height:95%; border-spacing:0; align:center;">
		<tr>
			<td class="TitrePage">SUPPRESSION DES ACCES</td>
		</tr>
		<tr>
			<td>
				<div style="width:95%;height:200px;overflow:auto;">
					<table class="TableCompetences" style="width:95%; height:95%; border-spacing:0; align:center;">
						<tr>
							<td><b>Catégorie (onglet)</b></td>
							<td><b>Sous-Dossier 1</b></td>
							<td><b>Sous-Dossier 2</b></td>
							<td><b>Personne</b></td>
							<td><b>Type de droits</b></td>
							<td><b>Suppr</b></td>
						</tr>
						<?php
						$Requete_Droits="
							SELECT
								new_acces.Droits AS DROITS,
								new_acces.Login AS LOGIN,
								new_rh_etatcivil.Id AS ID_PERSONNE,
								CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS NOMPRENOM,
								new_acces.Page AS PAGE,
								dossiers_general.Libelle AS DOSSIERGENERAL_LIBELLE,
								new_acces.Dossier1 AS DOSSIER1,
								new_acces.Dossier2 AS DOSSIER2
							FROM
								new_acces
								LEFT JOIN new_rh_etatcivil ON new_rh_etatcivil.Login=new_acces.Login
								LEFT JOIN dossiers_general ON dossiers_general.NomTable=new_acces.Page
							WHERE
								PAGE IN (".$ListeDossiersAdministrateur.")
							ORDER BY
								PAGE,
								DOSSIER1,
								DOSSIER2,
								NOMPRENOM;";
						$Result_Droits=mysqli_query($bdd,$Requete_Droits);

						while($Row_Droits=mysqli_fetch_array($Result_Droits))
						{
							echo "
								<tr>
									<td>".$Row_Droits['DOSSIERGENERAL_LIBELLE']."</td>
									<td>".$Row_Droits['DOSSIER1']."</td>
									<td>".$Row_Droits['DOSSIER2']."</td>
									<td><a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$Row_Droits['ID_PERSONNE']."\");'>".$Row_Droits['NOMPRENOM']."</a></td>
									<td>".$Row_Droits['DROITS']."</td>
									<td><input type='checkbox' name='DroitsSuppression[]' value='".$Row_Droits['DROITS']."|".$Row_Droits['LOGIN']."|".$Row_Droits['ID_PERSONNE']."|".$Row_Droits['PAGE']."|".$Row_Droits['DOSSIER1']."|".$Row_Droits['DOSSIER2']."'></td>
								</tr>";
						}
						?>
					</table>
				</div>
			<td>
		</tr>
		<tr>
			<td align="center">
				<input class="Bouton" type="submit" <?php if($LangueAffichage=="FR"){echo "value='SUPPRIMER'";}else{echo "value='DELETE'";}?>>
			</td>
		</tr>
	</table>
	</form>
<?php
	mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
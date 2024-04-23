<?php
require("../../Menu.php");
?>
<script type="text/javascript">
	function OuvreFenetreModif(Mode,Id)
		{window.open("../RH/Ajout_Personne.php?Mode="+Mode+"&Id="+Id,"PageFichier","status=no,menubar=no,width=900,height=150");}
	function OuvreFenetreProfil(Mode,Id)
		{window.open("Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1040,height=800");}
	function OuvreFenetreCompetences2(Id)
		{window.open("Individual_Competency_List.php?Id_Personne="+Id,"CompetencyList","status=no,menubar=no,scrollbars=yes,rezisable=yes,width=1010,height=600");}
</script>
<?php
$Droits="Aucun";
if(DroitsFormation1Plateforme(17,array($IdPosteAdministrateur,$IdPosteResponsableQualite))	
)
{
	$Droits="Administrateur";
}
elseif(DroitsPlateforme(array($IdPosteResponsableQualite,$IdPosteResponsableRH,$IdPosteAssistantRH,$IdPosteReferentQualiteSysteme))
|| DroitsFormationPrestation(array($IdPosteReferentQualiteProduit))
)
{
	$Droits="Ecriture";
}
$DroitsModifPrestation=EstPresent_HierarchiePrestation();
?>

<table style="width:100%; border-spacing:0; align:center;">
<?php
if($_GET)
{
?>
	<tr>
		<td>
			<table class="GeneralPage" style="width:100%; border-spacing:0;">
				<tr>
					<td class="TitrePage"><?php 
					echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Competences/Tableau_De_Bord.php'>";
					if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
					else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
					echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
					if($LangueAffichage=="FR"){echo "Gestion des compétences # Gestion des personnes";}else{echo "Competencies management - Person management";}?></td>
					<?php
					if($Droits=="Administrateur" || $Droits=="Ecriture")
					{
					?>
					<td width="25">
						<a class="Modif" href="javascript:OuvreFenetreModif('Ajout','0');">
							<img src="../../Images/Ajout.gif" border="0" alt="Ajouter une personne" title="Ajouter une personne">
						</a>
					</td>
					<?php
					}
					?>
				</tr>
			</table>
		</td>
	</tr>
	
	<tr><td height="5"></td></tr>
	
	<tr>
		<td>
			<table>
				<tr>
					<td width="10"></td>
					<td>
						<table class="TableCompetences" style="width:850px;">
						<?php
							if($_GET['Toutes'] == 'Oui')
							{
								$requete="SELECT DISTINCT new_rh_etatcivil.Id ";
								$requete.="FROM new_rh_etatcivil, new_competences_plateforme, new_competences_personne_plateforme";
								$requete.=" WHERE";
								$requete.=" new_competences_personne_plateforme.Id_Personne=new_rh_etatcivil.Id";
								$requete.=" AND new_competences_personne_plateforme.Id_Plateforme=new_competences_plateforme.Id";
							}
							else
							{
								$requete="SELECT DISTINCT new_rh_etatcivil.Id FROM new_rh_etatcivil ";
								$requete.="LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id ";
								$requete.="LEFT JOIN new_competences_personne_plateforme ON new_competences_personne_plateforme.Id_Personne=new_rh_etatcivil.Id ";
								$requete.="LEFT JOIN new_competences_plateforme ON new_competences_plateforme.Id=new_competences_personne_plateforme.Id_Plateforme ";
								$requete.=" WHERE ";
								if(isset($_SESSION['Id_Plateformes']))
								{
    								if(sizeof($_SESSION['Id_Plateformes'])>0)
    								{
    								    $requete.=" ( ";
    								    foreach($_SESSION['Id_Plateformes'] as &$value){$requete.="new_competences_personne_plateforme.Id_Plateforme=".$value." OR ";}
    								    $requete=substr($requete, 0,-4);
    								    $requete.=" ) AND";
    								}
								}
								$requete.=" new_competences_personne_prestation.Date_Debut <='".$DateJour."' AND new_competences_personne_prestation.Date_Fin>='".$DateJour."' ";
							}
							$requete.=" ORDER BY new_competences_plateforme.Libelle ASC, new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
							//$requete.=" LIMIT 0,200";
}	//Fin $_GET
elseif($_POST)
{
?>
	<tr>
		<td>
			<table style="width:100%; border-spacing:0;">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($LangueAffichage=="FR"){echo "Gestion des compétences # Résultats de la recherche";}else{echo "Competencies management # Search results";}?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table>
				<tr>
					<td width="10"></td>
					<td>
						<table class="TableCompetences">
<?php
	$SelectRequete="SELECT DISTINCT new_rh_etatcivil.Id FROM ";
	$DebutRequete="new_rh_etatcivil";
	$InnerJoin=" LEFT JOIN new_competences_personne_plateforme ON new_competences_personne_plateforme.Id_Personne=new_rh_etatcivil.Id ";
	$InnerJoin.="LEFT JOIN new_competences_plateforme ON new_competences_plateforme.Id=new_competences_personne_plateforme.Id_Plateforme ";
	$MilieuRequete="";
	$FinRequete=" ORDER BY new_competences_plateforme.Libelle ASC, new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
	
	$RECHERCHE="";
	
	//ETAT CIVIL
	if($_SESSION["Competences_Recherche_Personne"]!="")
	{
		$RequeteCivil="";
		if($MilieuRequete==""){$MilieuRequete.=" WHERE";}else{$MilieuRequete.=" AND";}
		$MilieuRequete.=" (";
		foreach($_SESSION['Competences_Recherche_Personne'] as &$value)
		{
			if($RequeteCivil!=""){$RequeteCivil.=" OR";}
			$RequeteCivil.=" new_rh_etatcivil.Nom LIKE '".$value."'";
			$RECHERCHE.=" - Nom : ".$value."<br>";
		}
		$MilieuRequete.=$RequeteCivil;
		$MilieuRequete.=")";
	}
	
	//PLATEFORME
	if($_SESSION["Competences_Recherche_Plateforme"]!="")
	{
		$RequetePlateforme="";
		if($MilieuRequete==""){$MilieuRequete.=" WHERE";}else{$MilieuRequete.=" AND";}
		$MilieuRequete.=" (";
		foreach($_SESSION['Competences_Recherche_Plateforme'] as &$value)
		{
			if($RequetePlateforme!=""){$RequetePlateforme.=" OR";}
			$RequetePlateforme.=" new_competences_personne_plateforme.Id_Plateforme=".$value;
			$requete="SELECT Libelle FROM new_competences_plateforme WHERE Id=".$value;
			$result=mysqli_query($bdd,$requete);
			$row=mysqli_fetch_array($result);
			$RECHERCHE.=" - Plateforme : ".$row[0]."<br>";
			mysqli_free_result($result);
		}
		$MilieuRequete.=$RequetePlateforme;
		$MilieuRequete.=")";
	}
	
	//PRESTATION
	if($_SESSION["Competences_Recherche_Prestation"]!="")
	{
		$RequetePrestation="";
		$InnerJoin.=" LEFT JOIN new_competences_personne_prestation ON new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id";
		if($MilieuRequete==""){$MilieuRequete.=" WHERE";}else{$MilieuRequete.=" AND";}
		$MilieuRequete.=" (";
		foreach($_SESSION["Competences_Recherche_Prestation"] as &$value)
		{
			if($RequetePrestation!=""){$RequetePrestation.=" OR";}
			$RequetePrestation.=" new_competences_personne_prestation.Id_Prestation=".$value;
			$requete="SELECT Libelle FROM new_competences_prestation WHERE Id=".$value;
			$result=mysqli_query($bdd,$requete);
			$row=mysqli_fetch_array($result);
			$RECHERCHE.=" - Prestation : ".$row[0]."<br>";
			mysqli_free_result($result);
		}
		$MilieuRequete.=$RequetePrestation;
		$MilieuRequete.=")";
	}
	
	//METIER
	if($_SESSION["Competences_Recherche_Metier"]!="")
	{
		$RequeteMetier="";
		$InnerJoin.=" LEFT JOIN new_competences_personne_metier ON new_competences_personne_metier.Id_Personne=new_rh_etatcivil.Id";
		if($MilieuRequete==""){$MilieuRequete.=" WHERE";}else{$MilieuRequete.=" AND";}
		$MilieuRequete.=" (";
		foreach($_SESSION["Competences_Recherche_Metier"] as &$value)
		{
			if($RequeteMetier!=""){$RequeteMetier.=" OR";}
			$RequeteMetier.=" new_competences_personne_metier.Id_Metier=".$value;
			$requete="SELECT Libelle FROM new_competences_metier WHERE Id=".$value;
			$result=mysqli_query($bdd,$requete);
			$row=mysqli_fetch_array($result);
			$RECHERCHE.=" - Métier : ".$row[0]."<br>";
			mysqli_free_result($result);
		}
		$MilieuRequete.=$RequeteMetier;
		$MilieuRequete.=") ";
	}
	
	//FORMATION
	if($_SESSION["Competences_Recherche_Formation"]!="")
	{
		$RequeteFormation="";
		$InnerJoin.=" LEFT JOIN new_competences_personne_formation ON new_competences_personne_formation.Id_Personne=new_rh_etatcivil.Id";
		if($MilieuRequete==""){$MilieuRequete.=" WHERE";}else{$MilieuRequete.=" ".$_POST['Et_ou_Formation'];}
		$MilieuRequete.=" (";
		foreach($_SESSION["Competences_Recherche_Formation"] as &$value)
		{
			if($RequeteFormation!=""){$RequeteFormation.=" OR";}
			$RequeteFormation.=" new_competences_personne_formation.Id_Formation=".$value;
			$requete="SELECT Libelle FROM new_competences_formation WHERE Id=".$value;
			$result=mysqli_query($bdd,$requete);
			$row=mysqli_fetch_array($result);
			$RECHERCHE.=" - Formation : ".$row[0]."<br>";
			mysqli_free_result($result);
		}
		$MilieuRequete.=$RequeteFormation;
		$MilieuRequete.=")";
	}
	
	//QUALIFICATION
	if($_SESSION["Competences_Recherche_Qualification"]!="")
	{
		$RequeteQualif="";
		$InnerJoin.=" LEFT JOIN new_competences_relation ON new_competences_relation.Id_Personne=new_rh_etatcivil.Id";
		if($MilieuRequete==""){$MilieuRequete.=" WHERE";}else{$MilieuRequete.=" AND";}
		$MilieuRequete.=" (";
		foreach($_SESSION["Competences_Recherche_Qualification"] as &$value)
		{
			if($RequeteQualif!=""){$RequeteQualif.=" OR";}
			$RequeteQualif.=" (new_competences_relation.Id_Qualification_Parrainage=".$value." AND new_competences_relation.Type='Qualification' AND new_competences_relation.Suppr=0 )";
			$requete2="SELECT Libelle FROM new_competences_qualification WHERE Id=".$value;
			$result2=mysqli_query($bdd,$requete2);
			$row2=mysqli_fetch_array($result2);
			$RECHERCHE.=" - Qualification : ".$row2[0]."<br>";
			mysqli_free_result($result2);
		}
		$MilieuRequete.=$RequeteQualif;
		$MilieuRequete.=")";
		
		//Evaluation
		if($_SESSION["Competences_Recherche_EvaluationQualification"]!="")
		{
			$RequeteEvaluationQualif="";
			$MilieuRequete.=" AND";
			$MilieuRequete.=" (";
			foreach($_SESSION["Competences_Recherche_EvaluationQualification"] as &$value)
			{
				if($RequeteEvaluationQualif!=""){$RequeteEvaluationQualif.=" OR";}
				$RequeteEvaluationQualif.=" (new_competences_relation.Evaluation='".$value."')";
				$RECHERCHE.=" - Qualification (Evaluation) : ".$value."<br>";
			}
			$MilieuRequete.=$RequeteEvaluationQualif;
			$MilieuRequete.=")";
		}
	}

	//FIN REQUETE
	//-----------
	$requete=$SelectRequete.$DebutRequete.$InnerJoin.$MilieuRequete.$FinRequete;
}	//Fin $_POST
?>

<!-- ECRITURE DU TABLEAU DE RESULTAT -->
	<?php
	$result=mysqli_query($bdd,$requete);
	$nbenreg=mysqli_num_rows($result);
	
	if($_POST)
		{
	?>
				
				<tr>
					<td colspan="3"><b><?php if($LangueAffichage=="FR"){echo "La recherche est faite sur";}else{echo "Search based on";}?> :</b><br><?php echo $RECHERCHE;?></td>
					<?php
					if($nbenreg>0)
					{
					?>
					<td colspan="2">
						<form id="formulaire" method="POST" action="Personnes_Trouvees_Export.php">
							<input type="hidden" name="Requete" value="<?php echo $requete; ?>">
							<input class="Bouton" type="submit" value="Exporter les données trouvées">
						</form>
					</td>
					<?php
					}
					?>
					<td colspan="4"></td>
				</tr>
	<?php
		}
	
	if($nbenreg>0)
	{
	?>
				<tr>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Code analytique";}else{echo "Analytic code";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Nom Prénom";}else{echo "Name First name";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Métier/Fonction";}else{echo "Job/Function";}?></td>
					<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Contrat";}else{echo "Contract";}?></td>
					<td colspan="5" class="EnTeteTableauCompetences"></td>
				</tr>
	<?php
		$Couleur="#EEEEEE";
		while($row=mysqli_fetch_array($result))
		{
			if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
			else{$Couleur="#EEEEEE";}
			
			$Plateforme_Identique=false;
			$Plateforme_Sortie=false;
			
			//Personne	//MAJ DU 27/12/12
			$Nom="";
			$Prenom="";
			$Contrat="";
			$requete_etatcivil="SELECT Nom, Prenom, Contrat FROM new_rh_etatcivil WHERE Id=".$row[0];
			$result_etatcivil=mysqli_query($bdd,$requete_etatcivil);
			$row_etatcivil=mysqli_fetch_array($result_etatcivil);
			$Nom=$row_etatcivil[0];
			$Prenom=$row_etatcivil[1];
			$Contrat=$row_etatcivil[2];
			
			//Plateforme
			$PLATEFORME="";
			$requete_plateforme="
                SELECT DISTINCT
                    new_competences_plateforme.Libelle,
                    new_competences_plateforme.Id
                FROM
                    new_competences_plateforme,
                    new_competences_personne_plateforme
                WHERE
                    new_competences_personne_plateforme.Id_Personne=".$row[0]."
                    AND new_competences_plateforme.Id=new_competences_personne_plateforme.Id_Plateforme
                ORDER BY
                    new_competences_plateforme.Libelle ASC";
			$result_plateforme=mysqli_query($bdd,$requete_plateforme);
			$nbenreg_plateforme=mysqli_num_rows($result_plateforme);
			if($nbenreg_plateforme>0)
			{
				while($row_plateforme=mysqli_fetch_array($result_plateforme))
				{
					if($row_plateforme[1]==14){$Plateforme_Sortie=true;}	//14 signifie la plateforme Z-SORTIE
					if(isset($_SESSION['Id_Plateformes']))
					{
					    foreach($_SESSION['Id_Plateformes'] as &$value)
					    {
					        if($row_plateforme[1]==$value){$Plateforme_Identique=true;}
					    }
					}
					if($PLATEFORME==""){$PLATEFORME=$row_plateforme[0];}else{$PLATEFORME.="<br>".$row_plateforme[0];}
				}
			}
			
			//Prestation
			$PRESTATION="";
			$requete_prestation="
                SELECT DISTINCT
                    new_competences_prestation.Code_Analytique
                FROM
                    new_competences_prestation,
                    new_competences_personne_prestation
                WHERE
                    new_competences_personne_prestation.Id_Personne=".$row[0]."
                    AND new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
                    AND new_competences_personne_prestation.Date_Debut <='".$DateJour."'
                    AND new_competences_personne_prestation.Date_Fin>='".$DateJour."'
                ORDER BY
                    new_competences_personne_prestation.Date_Debut DESC";
			$result_prestation=mysqli_query($bdd,$requete_prestation);
			$nbenreg_prestation=mysqli_num_rows($result_prestation);
			if($nbenreg_prestation>0)
				{while($row_prestation=mysqli_fetch_array($result_prestation)){if($PRESTATION==""){$PRESTATION=$row_prestation[0];}else{$PRESTATION.="<br>".$row_prestation[0];}}}
			
			//Metier
			$METIER="";
			$requete_metier="
                SELECT DISTINCT
                    new_competences_metier.Libelle
                FROM
                    new_competences_metier,
                    new_competences_personne_metier
                WHERE
                    new_competences_personne_metier.Id_Personne=".$row[0]."
                    AND new_competences_metier.Id=new_competences_personne_metier.Id_Metier
                ORDER BY
                    new_competences_personne_metier.Id DESC";
			$result_metier=mysqli_query($bdd,$requete_metier);
			$nbenreg_metier=mysqli_num_rows($result_metier);
			if($nbenreg_metier>0)
				{while($row_metier=mysqli_fetch_array($result_metier)){if($METIER==""){$METIER=$row_metier[0];}else{$METIER.="<br>".$row_metier[0];}}}
	?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td><?php echo $PLATEFORME;?></td>
					<td><?php echo $PRESTATION;?></td>
					<td><?php echo $Nom." ".$Prenom;?></td>
					<td><?php echo $METIER;?></td>
					<td><?php echo $Contrat;?></td>
					<td></td>
				<?php
					if(($Droits=="Ecriture" && $Plateforme_Identique) || $Droits=="Administrateur")
					{
				?>
					<td width="20"><a class="Modif" href="javascript:OuvreFenetreModif('Modif','<?php echo $row[0]; ?>');"><img src="../../Images/Modif.gif" border="0" alt="Modification" title="Modification"></a></td>
					<td width="20"><a class="Modif" href="javascript:OuvreFenetreCompetences2('<?php echo $row[0]; ?>');"><img src="../../Images/Competences.gif" border="0" alt="Competency List" title="Competency List"></a></td>
					<td width="20"><a class="Modif" href="javascript:OuvreFenetreProfil('Modif','<?php echo $row[0]; ?>');"><img src="../../Images/Ajout.gif" border="0" alt="Détails" title="Détails"></a></td>
					<td width="20"><a class="Modif" href="javascript:OuvreFenetreModif('Suppr','<?php echo $row[0]; ?>');"><img src="../../Images/Suppression.gif" border="0" alt="Suppression" title="Suppression" onclick="if(window.confirm('Vous etes sûr de vouloir supprimer ?')){return true;}else{return false;}"></a></td>
				<?php
					}
					elseif($Plateforme_Sortie)
					{
				?>
					<td></td>
					<td width="20"><a class="Modif" href="javascript:OuvreFenetreCompetences2('<?php echo $row[0]; ?>');"><img src="../../Images/Competences.gif" border="0" alt="Competency List" title="Competency List"></a></td>
					<td width="20"><a class="Modif" href="javascript:OuvreFenetreProfil('ModifPlateforme','<?php echo $row[0]; ?>');"><img src="../../Images/Ajout.gif" border="0" alt="Détails" title="Détails"></a></td>
					<td></td>
				<?php
					}
					elseif($DroitsModifPrestation && $Plateforme_Identique)
					{
				?>
					<td></td>
					<td width="20"><a class="Modif" href="javascript:OuvreFenetreCompetences2('<?php echo $row[0]; ?>');"><img src="../../Images/Competences.gif" border="0" alt="Competency List" title="Competency List"></a></td>
					<td width="20"><a class="Modif" href="javascript:OuvreFenetreProfil('ModifPresta','<?php echo $row[0]; ?>');"><img src="../../Images/Ajout.gif" border="0" alt="Détails" title="Détails"></a></td>
					<td></td>
				<?php
					}
					else
					{
				?>
					<td></td>
					<td width="20"><a class="Modif" href="javascript:OuvreFenetreCompetences2('<?php echo $row[0]; ?>');"><img src="../../Images/Competences.gif" border="0" alt="Competency List" title="Competency List"></a></td>
					<td width="20"><a class="Modif" href="javascript:OuvreFenetreProfil('Lecture','<?php echo $row[0]; ?>');"><img src="../../Images/Ajout.gif" border="0" alt="Détails" title="Détails"></a></td>
					<td></td>
				<?php
					}
				?>
				</tr>
		<?php
		}	//Fin boucle
	}		//Fin If
		else
		{
			if($LangueAffichage=="FR"){echo "<tr><td coslpan=6>Aucune personne ne correspond à ces critères.</td></tr>";}
			else{echo "<tr><td coslpan=6>No person meets these criteria.</td></tr>";}
		}
		mysqli_free_result($result);	// Libération des résultats
		?>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
		if($_GET){
	?>
	<tr>
		<td><?php if($LangueAffichage=="FR"){echo "Passez par le mode RECHERCHE pour afficher plus de 200 résultats.";}
		else{echo "Go through the search mode to display more than 200 results";}?></td>
	</tr>
	<?php
		}
	?>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>

<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
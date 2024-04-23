<?php
require("../../Menu.php");
?>
<script language="javascript">
	function SelectionnerToutPlateforme()
	{
		var elements = document.getElementsByClassName("checkPlateforme");
		if (formulaire.selectAllPlateforme.checked == true)
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = true;}
		}
		else
		{
			for(var i=0, l=elements.length; i<l; i++){elements[i].checked = false;}
		}
	}
</script>
<?php
if($_SESSION["Langue"]=="FR")
{
	$MoisLettre = array("","JANVIER", "FEVRIER", "MARS", "AVRIL", "MAI", "JUIN", "JUILLET", "AOUT", "SEPTEMBRE", "OCTOBRE", "NOVEMBRE", "DECEMBRE");
}
else
{
	$MoisLettre = array("","JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER");
}

if($_POST){
	$_SESSION['FiltreRecrutementIndicateur_Plateforme']=$_POST['Id_Plateforme'];
	$_SESSION['FiltreRecrutementIndicateur_DateDebut']=$_POST['DateDebut'];
	$_SESSION['FiltreRecrutementIndicateur_DateFin']=$_POST['DateFin'];
}

if(isset($_GET['Tri1'])){
	$tab = array("Ref","Lieu","Nombre");
	foreach($tab as $tri){
		if($_GET['Tri1']==$tri){
			$_SESSION['TriRecrutIndicateur1_General']= str_replace($tri." ASC,","",$_SESSION['TriRecrutIndicateur1_General']);
			$_SESSION['TriRecrutIndicateur1_General']= str_replace($tri." DESC,","",$_SESSION['TriRecrutIndicateur1_General']);
			$_SESSION['TriRecrutIndicateur1_General']= str_replace($tri." ASC","",$_SESSION['TriRecrutIndicateur1_General']);
			$_SESSION['TriRecrutIndicateur1_General']= str_replace($tri." DESC","",$_SESSION['TriRecrutIndicateur1_General']);
			if($_SESSION['TriRecrutIndicateur1_'.$tri]==""){$_SESSION['TriRecrutIndicateur1_'.$tri]="ASC";$_SESSION['TriRecrutIndicateur1_General'].= $tri." ".$_SESSION['TriRecrutIndicateur1_'.$tri].",";}
			elseif($_SESSION['TriRecrutIndicateur1_'.$tri]=="ASC"){$_SESSION['TriRecrutIndicateur1_'.$tri]="DESC";$_SESSION['TriRecrutIndicateur1_General'].= $tri." ".$_SESSION['TriRecrutIndicateur1_'.$tri].",";}
			else{$_SESSION['TriRecrutIndicateur1_'.$tri]="";}
		}
	}
}

if(isset($_GET['Tri2'])){
	$tab = array("Ref","Lieu","Nombre");
	foreach($tab as $tri){
		if($_GET['Tri2']==$tri){
			$_SESSION['TriRecrutIndicateur2_General']= str_replace($tri." ASC,","",$_SESSION['TriRecrutIndicateur2_General']);
			$_SESSION['TriRecrutIndicateur2_General']= str_replace($tri." DESC,","",$_SESSION['TriRecrutIndicateur2_General']);
			$_SESSION['TriRecrutIndicateur2_General']= str_replace($tri." ASC","",$_SESSION['TriRecrutIndicateur2_General']);
			$_SESSION['TriRecrutIndicateur2_General']= str_replace($tri." DESC","",$_SESSION['TriRecrutIndicateur2_General']);
			if($_SESSION['TriRecrutIndicateur2_'.$tri]==""){$_SESSION['TriRecrutIndicateur2_'.$tri]="ASC";$_SESSION['TriRecrutIndicateur2_General'].= $tri." ".$_SESSION['TriRecrutIndicateur2_'.$tri].",";}
			elseif($_SESSION['TriRecrutIndicateur2_'.$tri]=="ASC"){$_SESSION['TriRecrutIndicateur2_'.$tri]="DESC";$_SESSION['TriRecrutIndicateur2_General'].= $tri." ".$_SESSION['TriRecrutIndicateur2_'.$tri].",";}
			else{$_SESSION['TriRecrutIndicateur2_'.$tri]="";}
		}
	}
}

if(isset($_GET['Tri3'])){
	$tab = array("Ref","Metier","Lieu","Nombre","Restant");
	foreach($tab as $tri){
		if($_GET['Tri3']==$tri){
			$_SESSION['TriRecrutIndicateur3_General']= str_replace($tri." ASC,","",$_SESSION['TriRecrutIndicateur3_General']);
			$_SESSION['TriRecrutIndicateur3_General']= str_replace($tri." DESC,","",$_SESSION['TriRecrutIndicateur3_General']);
			$_SESSION['TriRecrutIndicateur3_General']= str_replace($tri." ASC","",$_SESSION['TriRecrutIndicateur3_General']);
			$_SESSION['TriRecrutIndicateur3_General']= str_replace($tri." DESC","",$_SESSION['TriRecrutIndicateur3_General']);
			if($_SESSION['TriRecrutIndicateur3_'.$tri]==""){$_SESSION['TriRecrutIndicateur3_'.$tri]="ASC";$_SESSION['TriRecrutIndicateur3_General'].= $tri." ".$_SESSION['TriRecrutIndicateur3_'.$tri].",";}
			elseif($_SESSION['TriRecrutIndicateur3_'.$tri]=="ASC"){$_SESSION['TriRecrutIndicateur3_'.$tri]="DESC";$_SESSION['TriRecrutIndicateur3_General'].= $tri." ".$_SESSION['TriRecrutIndicateur3_'.$tri].",";}
			else{$_SESSION['TriRecrutIndicateur3_'.$tri]="";}
		}
	}
}
?>

<form  id="formulaire" class="test" action="TDB_Indicateurs.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>" />
	<tr>
		<td colspan="5">
			<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#e779a4;">
				<tr>
					<td class="TitrePage">
					<?php
						echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Recrutement/Tableau_De_Bord.php'>";
						if($LangueAffichage=="FR"){echo "<img width='15px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
						else{echo "<img width='15px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
						echo "</a>";
					echo "&nbsp;&nbsp;&nbsp;";
						
					if($LangueAffichage=="FR"){echo "Indicateurs";}else{echo "Indicators";}
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
		<td align="center" valign="top" width="85%">
			<?php 
				$Id_Plateformes="";
				if($_POST){
					if(isset($_POST['Id_Plateforme'])){
						if (is_array($_POST['Id_Plateforme'])) {
							foreach($_POST['Id_Plateforme'] as $value){
								if($Id_Plateformes<>''){$Id_Plateformes.=",";}
							  $Id_Plateformes.=$value;
							}
						} else {
							$value = $_POST['Id_Plateforme'];
							$Id_Plateformes = $value;
						}
					}
				}
			?>
			<table width="100%">
				<?php 
					$requete="
					SELECT Id 
					FROM recrut_annonce
					WHERE Suppr=0  
					AND EtatValidation=1 
					AND EtatApprobation=1
					AND EtatRecrutement=1 
					AND OuvertureAutresPlateformes=1 
					AND EtatPoste>=0
					AND DateBesoin<'".date('Y-m-d')."'
					";
					if($Id_Plateformes<>""){
						$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$Id_Plateformes.") ";
					}
					$resultBesoinsDepasse=mysqli_query($bdd,$requete);
					$nbBesoinsDepasse=mysqli_num_rows($resultBesoinsDepasse);
					
					$requete="SELECT Id 
							FROM recrut_annonce
							WHERE Suppr=0  
							AND EtatValidation=1 
							AND EtatApprobation=1
							AND EtatRecrutement=1 
							AND OuvertureAutresPlateformes=1 
							AND EtatPoste=0
							 ";
					if($Id_Plateformes<>""){
						$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$Id_Plateformes.") ";
					}
					$result=mysqli_query($bdd,$requete);
					$nbenreg=mysqli_num_rows($result);
					
					$requete="SELECT SUM(Nombre)-(SELECT COUNT(Id) 
									FROM recrut_candidature 
									WHERE recrut_candidature.Suppr=0 
									AND Id_Annonce=recrut_annonce.Id AND CandidatRetenu=1 ";
									$requete.=") AS NombrePoste 
							FROM recrut_annonce
							WHERE Suppr=0  
							AND EtatValidation=1 
							AND EtatApprobation=1
							AND EtatRecrutement=1 
							AND OuvertureAutresPlateformes=1 
							AND EtatPoste=0
							 ";
					if($Id_Plateformes<>""){
						$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$Id_Plateformes.") ";
					}
					$result=mysqli_query($bdd,$requete);
					$nbPosteRestant=mysqli_num_rows($result);
					$nbRestant=0;
					if($nbPosteRestant>0){
						$rowRestant=mysqli_fetch_array($result);
						if($rowRestant['NombrePoste']>0){
							$nbRestant=$rowRestant['NombrePoste'];
						}
					}
					
					$requete="SELECT Id
							FROM recrut_annonce
							WHERE recrut_annonce.Suppr=0  
							AND EtatValidation=1 
							AND EtatApprobation=1
							AND EtatRecrutement=1 
							AND OuvertureAutresPlateformes=1 ";
							if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
								$requete.="AND DateRecrutement>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
							}
							if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
								$requete.="AND DateRecrutement<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
							}
							$requete.="
							AND EtatPoste>=0
							 ";
					if($Id_Plateformes<>""){
						$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=recrut_annonce.Id_Prestation) IN (".$Id_Plateformes.") ";
					}
					$result=mysqli_query($bdd,$requete);
					$nbOffre=mysqli_num_rows($result);
					
					$requete="SELECT Id
							FROM recrut_annonce
							WHERE recrut_annonce.Suppr=0  
							AND EtatValidation=1 
							AND EtatApprobation=1
							AND EtatRecrutement=1 
							AND OuvertureAutresPlateformes=1 ";
							if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
								$requete.="AND DateRecrutement>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
							}
							if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
								$requete.="AND DateRecrutement<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
							}
							$requete.="
							AND EtatPoste>=0
							AND (SELECT COUNT(Id) FROM recrut_candidature WHERE Suppr=0 AND Id_Annonce=recrut_annonce.Id AND CandidatRetenu=1)>0
							 ";
					if($Id_Plateformes<>""){
						$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=recrut_annonce.Id_Prestation) IN (".$Id_Plateformes.") ";
					}
					$result=mysqli_query($bdd,$requete);
					$nbOffreCandidatRetenu=mysqli_num_rows($result);
					
					$requete="SELECT recrut_candidature.Id_Personne
							FROM recrut_candidature
							LEFT JOIN recrut_annonce
							ON recrut_annonce.Id=recrut_candidature.Id_Annonce
							WHERE recrut_annonce.Suppr=0  
							AND recrut_candidature.Suppr=0
							AND EtatValidation=1 
							AND EtatApprobation=1
							AND EtatRecrutement=1 
							AND OuvertureAutresPlateformes=1 ";
							if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
								$requete.="AND DateRecrutement>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
							}
							if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
								$requete.="AND DateRecrutement<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
							}
							$requete.="
							AND EtatPoste>=0
							AND CandidatRetenu=1
							 ";
					if($Id_Plateformes<>""){
						$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=recrut_annonce.Id_Prestation) IN (".$Id_Plateformes.") ";
					}
					$result=mysqli_query($bdd,$requete);
					$nbPropositions=mysqli_num_rows($result);
					
					$requete="SELECT recrut_candidature.Id_Personne
							FROM recrut_candidature
							LEFT JOIN recrut_annonce
							ON recrut_annonce.Id=recrut_candidature.Id_Annonce
							WHERE recrut_annonce.Suppr=0  
							AND recrut_candidature.Suppr=0
							AND EtatValidation=1 
							AND EtatApprobation=1
							AND EtatRecrutement=1 
							AND OuvertureAutresPlateformes=1 ";
							if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
								$requete.="AND DateRecrutement>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
							}
							if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
								$requete.="AND DateRecrutement<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
							}
							$requete.="
							AND EtatPoste>=0
							AND CandidatRetenu=1
							AND PosteDefinitif=0
							 ";
							 
					if($Id_Plateformes<>""){
						$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=recrut_annonce.Id_Prestation) IN (".$Id_Plateformes.") ";
					}
					$result=mysqli_query($bdd,$requete);
					$nbPropositionsMission=mysqli_num_rows($result);
					
					$requete="SELECT recrut_candidature.Id_Personne
							FROM recrut_candidature
							LEFT JOIN recrut_annonce
							ON recrut_annonce.Id=recrut_candidature.Id_Annonce
							WHERE recrut_annonce.Suppr=0  
							AND recrut_candidature.Suppr=0
							AND EtatValidation=1 
							AND EtatApprobation=1
							AND EtatRecrutement=1 
							AND OuvertureAutresPlateformes=1 ";
							if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
								$requete.="AND DateRecrutement>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
							}
							if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
								$requete.="AND DateRecrutement<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
							}
							$requete.="
							AND EtatPoste>=0
							AND CandidatRetenu=1
							AND PosteDefinitif=1
							 ";
					if($Id_Plateformes<>""){
						$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=recrut_annonce.Id_Prestation) IN (".$Id_Plateformes.") ";
					}
					$result=mysqli_query($bdd,$requete);
					$nbPropositionsDefinitif=mysqli_num_rows($result);
					
					$requete="SELECT recrut_candidature.Id_Personne
							FROM recrut_candidature
							LEFT JOIN recrut_annonce
							ON recrut_annonce.Id=recrut_candidature.Id_Annonce
							WHERE recrut_annonce.Suppr=0  
							AND recrut_candidature.Suppr=0
							AND EtatValidation=1 
							AND EtatApprobation=1
							AND EtatRecrutement=1 
							AND OuvertureAutresPlateformes=1 ";
							if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
								$requete.="AND DateRecrutement>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
							}
							if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
								$requete.="AND DateRecrutement<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
							}
							$requete.="
							AND EtatPoste>=0
							 ";
					if($Id_Plateformes<>""){
						$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=recrut_annonce.Id_Prestation) IN (".$Id_Plateformes.") ";
					}
					$result=mysqli_query($bdd,$requete);
					$nbReponse=mysqli_num_rows($result);

					$requete="SELECT DISTINCT recrut_candidature.Id_Personne
							FROM recrut_candidature
							LEFT JOIN recrut_annonce
							ON recrut_annonce.Id=recrut_candidature.Id_Annonce
							WHERE recrut_annonce.Suppr=0  
							AND recrut_candidature.Suppr=0
							AND EtatValidation=1 
							AND EtatApprobation=1
							AND EtatRecrutement=1 
							AND OuvertureAutresPlateformes=1 
							AND EtatPoste>=0
							";
							if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
								$requete.="AND DateRecrutement>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
							}
							if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
								$requete.="AND DateRecrutement<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
							}
					if($Id_Plateformes<>""){
						$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=recrut_annonce.Id_Prestation) IN (".$Id_Plateformes.") ";
					}
					$result=mysqli_query($bdd,$requete);
					$nbPersonnes=mysqli_num_rows($result);

				?>
				<tr>
					<td width="30%" valign="top">
						<table class="GeneralInfo" width="15%">
							<tr>
								<td class="Libelle" align="left" style="font-size:16px;">&nbsp;<?php if($LangueAffichage=="FR"){echo "POSTES OUVERTS";}else{echo "OPEN POSITIONS";}?></td>
							</tr>
							<tr>
								<td class="Libelle" align="left" >&nbsp;<?php if($LangueAffichage=="FR"){echo "NOMBRE D'OFFRES D'EMPLOI EN COURS : ";}else{echo "NUMBER OF CURRENT JOB OFFERS : ";}?><b style="font-size:25px;color:<?php if($nbenreg>0){echo "#ff3b3b";}else{echo "#000000";}?>" ><?php echo $nbenreg; ?></b></td>
							</tr>
							<tr>
								<td class="Libelle" align="left" >&nbsp;<?php if($LangueAffichage=="FR"){echo "NOMBRE DE POSTES RESTANTS : ";}else{echo "NUMBER OF POSITIONS REMAINING : ";}?><b style="font-size:25px;color:<?php if($nbRestant>0){echo "#ff3b3b";}else{echo "#000000";}?>" ><?php echo $nbRestant; ?></b></td>
							</tr>
						</table>
					</td>
					<td width="30%" valign="top">
						<table class="GeneralInfo" width="15%">
							<tr>
								<td class="Libelle" align="left" colspan="2" style="font-size:16px;">
								&nbsp;<?php 
								if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
									if($LangueAffichage=="FR"){echo "OFFRES CRÉÉS À PARTIR DU ";}else{echo "OFFERS CREATED FROM ";}
									echo AfficheDateJJ_MM_AAAA(TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut']));
								}
								if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
									if($LangueAffichage=="FR"){echo " AU ";}else{echo " TO ";}
									echo AfficheDateJJ_MM_AAAA(TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin']));
								}
								?>
								</td>
							</tr>
							<tr>
								<td width="50%">
									<table width="100%">
										<tr>
											<td class="Libelle" align="left" >&nbsp;<?php if($LangueAffichage=="FR"){echo "NOMBRE DE RÉPONSES : ";}else{echo "NUMBER OF ANSWERS : ";}?><b style="font-size:25px;color:<?php if($nbReponse>0){echo "#ff3b3b";}else{echo "#000000";}?>" ><?php echo $nbReponse; ?></b></td>
										</tr>
										<tr>
											<td class="Libelle" align="left" >&nbsp;<?php if($LangueAffichage=="FR"){echo "NOMBRE DE PERSONNES AYANT RÉPONDU : ";}else{echo "NUMBER OF PEOPLE WHO RESPONDED : ";}?><b style="font-size:25px;color:<?php if($nbPersonnes>0){echo "#ff3b3b";}else{echo "#000000";}?>" ><?php echo $nbPersonnes; ?></b></td>
										</tr>
										<tr>
											<td class="Libelle" align="left" >&nbsp;<?php if($LangueAffichage=="FR"){echo "OFFRES AVEC CANDIDATS RETENUS : ";}else{echo "OFFERS WITH SUCCESSFUL CANDIDATES : ";}?><b style="font-size:25px;color:<?php if($nbOffreCandidatRetenu>0){echo "#ff3b3b";}else{echo "#000000";}?>"><?php echo $nbOffreCandidatRetenu."/".$nbOffre; ?></b></td>
										</tr>
									</table>
								</td>
								<td width="50%">
									<table width="100%">
										<tr>
											<td class="Libelle" align="left" >&nbsp;<?php if($LangueAffichage=="FR"){echo "NOMBRE DE PROPOSITIONS DE RECLASSEMENT : ";}else{echo "NUMBER OF RECLASSIFICATION PROPOSALS : ";}?><b style="font-size:25px;color:<?php if($nbPropositions>0){echo "#ff3b3b";}else{echo "#000000";}?>"><?php echo $nbPropositions; ?></b></td>
										</tr>
										<tr>
											<td class="Libelle" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "NOMBRE DE SALARIÉS EN MISSION : ";}else{echo "NUMBER OF EMPLOYEES ON MISSION : ";}?><b style="font-size:25px;color:<?php if($nbPropositionsMission>0){echo "#ff3b3b";}else{echo "#000000";}?>"><?php echo $nbPropositionsMission; ?></b></td>
										</tr>
										<tr>
											<td class="Libelle" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($LangueAffichage=="FR"){echo "NOMBRE DE SALARIÉS EN POSTE DEFINITIF : ";}else{echo "NUMBER OF EMPLOYEES IN A DEFINITIVE POSITION : ";}?><b style="font-size:25px;color:<?php if($nbPropositionsDefinitif>0){echo "#ff3b3b";}else{echo "#000000";}?>"><?php echo $nbPropositionsDefinitif; ?></b></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php 
			
	?>
	<tr>
		<td height="350px;" width="85%">
			<?php 
				$arrayOffresPlateformes=array();
				$arrayOffresMetiers=array();
				$arrayOffresEtat=array();
				$arrayCandidat=array();
				$arrayCandidat2=array();
				
				$requete="SELECT (SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
							COUNT(Id) AS Nb
							FROM recrut_annonce
							WHERE Suppr=0  
							AND EtatValidation=1 
							AND EtatApprobation=1
							AND EtatRecrutement=1 
							AND OuvertureAutresPlateformes=1 
							AND EtatPoste>=0 ";
				if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
					$requete.="AND DateRecrutement>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
				}
				if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
					$requete.="AND DateRecrutement<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
				}
				if($Id_Plateformes<>""){
					$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=recrut_annonce.Id_Prestation) IN (".$Id_Plateformes.") ";
				}
				$requete.="GROUP BY (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) ";
				$result=mysqli_query($bdd,$requete);
				$nbenreg=mysqli_num_rows($result);
				if($nbenreg>0){
					$i=0;
					$autre=0;
					while($row=mysqli_fetch_array($result))
					{
						$arrayOffresPlateformes[$i]=array("Abscisse" => utf8_encode($row['Plateforme']),"Nombre" => $row['Nb']);
						$i++;
					}
				}
				
				$requete="SELECT Metier,
							COUNT(Id) AS Nb
							FROM recrut_annonce
							WHERE Suppr=0  
							AND EtatValidation=1 
							AND EtatApprobation=1
							AND EtatRecrutement=1 
							AND OuvertureAutresPlateformes=1 
							AND EtatPoste>=0 ";
				if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
					$requete.="AND DateRecrutement>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
				}
				if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
					$requete.="AND DateRecrutement<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
				}
				if($Id_Plateformes<>""){
					$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$Id_Plateformes.") ";
				}
				$requete.="GROUP BY Metier ";
				$result=mysqli_query($bdd,$requete);
				$nbenreg=mysqli_num_rows($result);
				if($nbenreg>0){
					$i=0;
					$autre=0;
					while($row=mysqli_fetch_array($result))
					{
						$arrayOffresMetiers[$i]=array("Abscisse" => utf8_encode($row['Metier']),"Nombre" => $row['Nb']);
						$i++;
					}
				}
				
				$requete="SELECT IF(EtatValidation=0,'',
							IF(EtatValidation=-1,'',
								IF(EtatValidation=1 && EtatApprobation=0,'',
									IF(EtatValidation=1 && EtatApprobation=-1,'',
											IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=0,IF(EtatPoste=0,'Poste ouvert',IF(EtatPoste=1,'Poste pourvu',IF(EtatPoste=2,'Poste non pourvu',IF(EtatPoste=3,'Poste pourvu partiellement','Poste annulé')))),
											  IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=1,'',
												IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'',IF(EtatPoste=0,'Poste ouvert',IF(EtatPoste=1,'Poste pourvu',IF(EtatPoste=2,'Poste non pourvu',IF(EtatPoste=3,'Poste pourvu partiellement','Poste annulé')))))
												)
											)
										)
									)
								)
							) AS Statut,
							COUNT(Id) AS Nb
							FROM recrut_annonce
							WHERE Suppr=0  
							AND EtatValidation=1 
							AND EtatApprobation=1
							AND EtatRecrutement=1 
							AND OuvertureAutresPlateformes=1  ";
				if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
					$requete.="AND DateRecrutement>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
				}
				if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
					$requete.="AND DateRecrutement<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
				}
				if($Id_Plateformes<>""){
					$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$Id_Plateformes.") ";
				}
				$requete.="GROUP BY IF(EtatValidation=0,'',
							IF(EtatValidation=-1,'',
								IF(EtatValidation=1 && EtatApprobation=0,'',
									IF(EtatValidation=1 && EtatApprobation=-1,'',
											IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=0,IF(EtatPoste=0,'Poste ouvert',IF(EtatPoste=1,'Poste pourvu',IF(EtatPoste=2,'Poste non pourvu',IF(EtatPoste=3,'Poste pourvu partiellement','Poste annulé')))),
											  IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0 && OuvertureAutresPlateformes=1,'',
												IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'',IF(EtatPoste=0,'Poste ouvert',IF(EtatPoste=1,'Poste pourvu',IF(EtatPoste=2,'Poste non pourvu',IF(EtatPoste=3,'Poste pourvu partiellement','Poste annulé')))))
												)
											)
										)
									)
								)
							) ";
				$result=mysqli_query($bdd,$requete);
				$nbenreg=mysqli_num_rows($result);
				if($nbenreg>0){
					$i=0;
					$autre=0;
					while($row=mysqli_fetch_array($result))
					{
						$arrayOffresEtat[$i]=array("Abscisse" => utf8_encode($row['Statut']),"Nombre" => $row['Nb']);
						$i++;
					}
				}
				
				$requete="SELECT Categorie,
							COUNT(NbCandidature) AS NbCandidature
						FROM
						(SELECT IF((SELECT COUNT(Id) FROM recrut_candidature WHERE Suppr=0 AND Id_Annonce=recrut_annonce.Id)=0,0,
									IF((SELECT COUNT(Id) FROM recrut_candidature WHERE Suppr=0 AND Id_Annonce=recrut_annonce.Id)>0 AND (SELECT COUNT(Id) FROM recrut_candidature WHERE Suppr=0 AND Id_Annonce=recrut_annonce.Id)<3,'1-2',
										IF((SELECT COUNT(Id) FROM recrut_candidature WHERE Suppr=0 AND Id_Annonce=recrut_annonce.Id)>2 AND (SELECT COUNT(Id) FROM recrut_candidature WHERE Suppr=0 AND Id_Annonce=recrut_annonce.Id)<6,'3-5','>5'
										)
									)
								) AS Categorie,
							(SELECT COUNT(Id) FROM recrut_candidature WHERE Suppr=0 AND Id_Annonce=recrut_annonce.Id) AS NbCandidature
							FROM recrut_annonce
							WHERE Suppr=0  
							AND EtatValidation=1 
							AND EtatApprobation=1
							AND EtatRecrutement=1 
							AND OuvertureAutresPlateformes=1 
							AND EtatPoste=1 ";
				if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
					$requete.="AND DateRecrutement>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
				}
				if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
					$requete.="AND DateRecrutement<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
				}
				if($Id_Plateformes<>""){
					$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$Id_Plateformes.") ";
				}
				$requete.=") AS TAB
				GROUP BY Categorie ";
				$result=mysqli_query($bdd,$requete);
				$nbenreg=mysqli_num_rows($result);
				if($nbenreg>0){
					$i=0;
					$autre=0;
					while($row=mysqli_fetch_array($result))
					{
						$arrayCandidat[$i]=array("Abscisse" => utf8_encode($row['Categorie']),"Nombre" => $row['NbCandidature']);
						$i++;
					}
				}
				
				$requete="SELECT Categorie,
							COUNT(NbCandidature) AS NbCandidature
						FROM
						(SELECT IF((SELECT COUNT(Id) FROM recrut_candidature WHERE Suppr=0 AND Id_Annonce=recrut_annonce.Id ";
							if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
								$requete.="AND recrut_candidature.DateCreation>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
							}
							if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
								$requete.="AND recrut_candidature.DateCreation<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
							}
							$requete.=")=0,0,
									IF((SELECT COUNT(Id) FROM recrut_candidature WHERE Suppr=0 AND Id_Annonce=recrut_annonce.Id ";
							if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
								$requete.="AND recrut_candidature.DateCreation>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
							}
							if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
								$requete.="AND recrut_candidature.DateCreation<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
							}
							$requete.=")>0 AND (SELECT COUNT(Id) FROM recrut_candidature WHERE Suppr=0 AND Id_Annonce=recrut_annonce.Id ";
							if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
								$requete.="AND recrut_candidature.DateCreation>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
							}
							if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
								$requete.="AND recrut_candidature.DateCreation<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
							}
							$requete.=")<3,'1-2',
										IF((SELECT COUNT(Id) FROM recrut_candidature WHERE Suppr=0 AND Id_Annonce=recrut_annonce.Id ";
							if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
								$requete.="AND recrut_candidature.DateCreation>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
							}
							if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
								$requete.="AND recrut_candidature.DateCreation<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
							}
							$requete.=")>2 AND (SELECT COUNT(Id) FROM recrut_candidature WHERE Suppr=0 AND Id_Annonce=recrut_annonce.Id ";
							if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
								$requete.="AND recrut_candidature.DateCreation>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
							}
							if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
								$requete.="AND recrut_candidature.DateCreation<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
							}
							$requete.=")<6,'3-5','>5'
										)
									)
								) AS Categorie,
							(SELECT COUNT(Id) FROM recrut_candidature WHERE Suppr=0 AND Id_Annonce=recrut_annonce.Id ";
							if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
								$requete.="AND recrut_candidature.DateCreation>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
							}
							if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
								$requete.="AND recrut_candidature.DateCreation<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
							}
							$requete.=") AS NbCandidature
							FROM recrut_annonce
							WHERE Suppr=0  
							AND EtatValidation=1 
							AND EtatApprobation=1
							AND EtatRecrutement=1 
							AND OuvertureAutresPlateformes=1 
							AND EtatPoste>=0  ";
				if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
					$requete.="AND DateRecrutement>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
				}
				if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
					$requete.="AND DateRecrutement<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
				}
				if($Id_Plateformes<>""){
					$requete.=" AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$Id_Plateformes.") ";
				}
				$requete.=") AS TAB
				GROUP BY Categorie ";
				$result=mysqli_query($bdd,$requete);
				$nbenreg=mysqli_num_rows($result);
				if($nbenreg>0){
					$i=0;
					$autre=0;
					while($row=mysqli_fetch_array($result))
					{
						$arrayCandidat2[$i]=array("Abscisse" => utf8_encode($row['Categorie']),"Nombre" => $row['NbCandidature']);
						$i++;
					}
				}
	
			?>
			<table width="100%">
				<tr>
					<td width="50%">
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "OFFRES D'EMPLOI / UNITE D'EXPLOITATION";}else{echo "JOB OFFERS / OPERATING UNIT";} ?></td>
								<td style="cursor:pointer;" align="right"></td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td valign="top">
									<div id="chart_Plateforme" style="width:100%;height:350px"></div>
									<script>
										// Create chart instance
										var chart = am4core.create("chart_Plateforme", am4charts.PieChart);

									// Add data
									chart.data = <?php echo json_encode($arrayOffresPlateformes); ?>;
									
									var pieSeries = chart.series.push(new am4charts.PieSeries());
									pieSeries.dataFields.category = "Abscisse";
									pieSeries.dataFields.value = "Nombre";
									pieSeries.slices.template.stroke = am4core.color("#fff");
									pieSeries.slices.template.strokeWidth = 2;
									pieSeries.slices.template.strokeOpacity = 1;
									
									pieSeries.colors.list = [
									  am4core.color("#8f3df5"),
									  am4core.color("#56aadc"),
									  am4core.color("#8bed45"),
									  am4core.color("#3ddff5"),
									  am4core.color("#e05265"),
									  am4core.color("#d28560"),
									  am4core.color("#50eb47"),
									  am4core.color("#faf538"),
									  am4core.color("#d15dd5"),
									];
									
									// This creates initial animation
									pieSeries.hiddenState.properties.opacity = 1;
									pieSeries.hiddenState.properties.endAngle = -90;
									pieSeries.hiddenState.properties.startAngle = -90;
									
									var level1ColumnTemplate = pieSeries.columns.template;

									var bullet1 = level1ColumnTemplate.bullets.push(new am4charts.LabelBullet());
									bullet1.label.text = "{name}: {valueY.value}";


									chart.exporting.menu = new am4core.ExportMenu();
									</script>
								</td>
							</tr>
						</table>
					</td>
					<td width="50%">
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "ETAT DES OFFRES";}else{echo "STATE OF THE OFFERS";} ?></td>
								<td style="cursor:pointer;" align="right"></td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td valign="top">
									<div id="chart_Etat" style="width:100%;height:350px"></div>
									<script>
										// Create chart instance
										var chart = am4core.create("chart_Etat", am4charts.PieChart);

									// Add data
									chart.data = <?php echo json_encode($arrayOffresEtat); ?>;
									
									var pieSeries = chart.series.push(new am4charts.PieSeries());
									pieSeries.dataFields.category = "Abscisse";
									pieSeries.dataFields.value = "Nombre";
									pieSeries.slices.template.stroke = am4core.color("#fff");
									pieSeries.slices.template.strokeWidth = 2;
									pieSeries.slices.template.strokeOpacity = 1;

									// This creates initial animation
									pieSeries.hiddenState.properties.opacity = 1;
									pieSeries.hiddenState.properties.endAngle = -90;
									pieSeries.hiddenState.properties.startAngle = -90;
									
									pieSeries.colors.list = [
									  am4core.color("#91a197"),
									  am4core.color("#56aadc"),
									  am4core.color("#8bed45"),
									  am4core.color("#3ddff5"),
									  am4core.color("#409ef2"),
									  am4core.color("#4171f1"),
									];
									
									var level1ColumnTemplate = pieSeries.columns.template;
			
									var bullet1 = level1ColumnTemplate.bullets.push(new am4charts.LabelBullet());
									bullet1.label.text = "{name}: {valueY.value}";


									chart.exporting.menu = new am4core.ExportMenu();
									</script>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="50%">
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "NOMBRE DE CANDIDATS MOYEN / OFFRES OUVERTES";}else{echo "NUMBER OF CANDIDATES AVERAGE / OPEN OFFERS";} ?></td>
								<td style="cursor:pointer;" align="right"></td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td valign="top">
									<div id="chart_Candidat2" style="width:100%;height:350px"></div>
									<script>
										// Create chart instance
										var chart = am4core.create("chart_Candidat2", am4charts.PieChart);

									// Add data
									chart.data = <?php echo json_encode($arrayCandidat2); ?>;
									
									var pieSeries = chart.series.push(new am4charts.PieSeries());
									pieSeries.dataFields.category = "Abscisse";
									pieSeries.dataFields.value = "Nombre";
									pieSeries.slices.template.stroke = am4core.color("#fff");
									pieSeries.slices.template.strokeWidth = 2;
									pieSeries.slices.template.strokeOpacity = 1;

									// This creates initial animation
									pieSeries.hiddenState.properties.opacity = 1;
									pieSeries.hiddenState.properties.endAngle = -90;
									pieSeries.hiddenState.properties.startAngle = -90;
									
									pieSeries.colors.list = [
									  am4core.color("#edb845"),
									  am4core.color("#46eca8"),
									  am4core.color("#4271f0"),
									  am4core.color("#46eca8"),
									  am4core.color("#99eb47"),
									  am4core.color("#edb845"),
									];
									
									var level1ColumnTemplate = pieSeries.columns.template;
			
									var bullet1 = level1ColumnTemplate.bullets.push(new am4charts.LabelBullet());
									bullet1.label.text = "{name}: {valueY.value}";


									chart.exporting.menu = new am4core.ExportMenu();
									</script>
								</td>
							</tr>
						</table>
					</td>
					<td width="50%">
						
					</td>
				</tr>
				<tr>
					<td width="50%">
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "LISTE DE TOUTES LES OFFRES AVEC LE NOMBRE DE CANDIDATS PAR OFFRE";}else{echo "LIST OF ALL OFFERS WITH THE NUMBER OF CANDIDATES PER OFFER";} ?></td>
								<td style="cursor:pointer;" align="right"></td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td valign="top">
									<table class="TableCompetences" width="99%" cellpadding="0" cellspacing="0">
										<tr>
											<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="TDB_Indicateurs.php?Tri1=Ref">Poste<?php if($_SESSION['TriRecrutIndicateur1_Ref']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutIndicateur1_Ref']=="ASC"){echo "&darr;";}?></a></td>
											<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="TDB_Indicateurs.php?Tri1=Lieu">Lieu<?php if($_SESSION['TriRecrutIndicateur1_Lieu']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutIndicateur1_Lieu']=="ASC"){echo "&darr;";}?></a></td>
											<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="TDB_Indicateurs.php?Tri1=Nombre">Nbre de candidats/Nbre de postes<?php if($_SESSION['TriRecrutIndicateur1_Nombre']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutIndicateur1_Nombre']=="ASC"){echo "&darr;";}?></a></td>
										</tr>
										<?php 
											$requete2="SELECT CONCAT(Metier,'-',
											Lieu,'-',
											Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',DATE_FORMAT(DateRecrutement,'%d%m%y')
											) AS Ref,Lieu,recrut_annonce.Nombre AS NombrePoste,
											(SELECT COUNT(Id) FROM recrut_candidature WHERE Id_Annonce=recrut_annonce.Id AND recrut_candidature.Suppr=0 ";
							$requete2.=") AS Nombre
											FROM recrut_annonce
											WHERE Suppr=0  
											AND EtatRecrutement=1 
											AND OuvertureAutresPlateformes=1 ";
											if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
												$requete2.="AND DateRecrutement>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
											}
											if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
												$requete2.="AND DateRecrutement<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
											}
											
											$requeteOrder="";
											if($_SESSION['TriRecrutIndicateur1_General']<>""){
												$requeteOrder="ORDER BY ".substr($_SESSION['TriRecrutIndicateur1_General'],0,-1);
											}
											$result=mysqli_query($bdd,$requete2.$requeteOrder);
											$nbRapport=mysqli_num_rows($result);
											$couleur="#FFFFFF";
											$nombre=0;
											$nombrePoste=0;
											if($nbRapport>0){
												while($row=mysqli_fetch_array($result)){
													if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
													else{$couleur="#FFFFFF";}
													$nombre+=$row['Nombre'];
													$nombrePoste+=$row['NombrePoste'];
													echo "<tr bgcolor='".$couleur."'><td>".$row['Ref']."</td><td>".$row['Lieu']."</td><td align='center'>".$row['Nombre']."/".$row['NombrePoste']."</td></tr>";
												}
											}
										?>
										<tr <?php echo "bgcolor='#40babc'"; ?>>
											<td></td>
											<td></td>
											<td align="center"><?php echo $nombre."/".$nombrePoste; ?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td width="50%" valign="top">
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "POSTES SANS OU PEU DE CANDIDATURES";}else{echo "POSITIONS WITHOUT OR FEW CANDIDATES";} ?></td>
								<td style="cursor:pointer;" align="right"></td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td valign="top">
									<table class="TableCompetences" width="99%" cellpadding="0" cellspacing="0">
										<tr>
											<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="TDB_Indicateurs.php?Tri2=Ref">Poste<?php if($_SESSION['TriRecrutIndicateur2_Ref']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutIndicateur2_Ref']=="ASC"){echo "&darr;";}?></a></td>
											<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="TDB_Indicateurs.php?Tri2=Lieu">Lieu<?php if($_SESSION['TriRecrutIndicateur2_Lieu']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutIndicateur2_Lieu']=="ASC"){echo "&darr;";}?></a></td>
											<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="TDB_Indicateurs.php?Tri2=Nombre">Nbre de candidats/Nbre de postes<?php if($_SESSION['TriRecrutIndicateur2_Nombre']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutIndicateur2_Nombre']=="ASC"){echo "&darr;";}?></a></td>
										</tr>
										<?php 
											$requete2="SELECT CONCAT(Metier,'-',
											Lieu,'-',
											Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',DATE_FORMAT(DateRecrutement,'%d%m%y')
											) AS Ref,Lieu,recrut_annonce.Nombre AS NombrePoste,
											(SELECT COUNT(Id) FROM recrut_candidature WHERE Id_Annonce=recrut_annonce.Id AND recrut_candidature.Suppr=0 ";
							$requete2.=") AS Nombre
											FROM recrut_annonce
											WHERE Suppr=0  
											AND EtatRecrutement=1 
											AND OuvertureAutresPlateformes=1
											AND ((SELECT COUNT(Id) FROM recrut_candidature WHERE Id_Annonce=recrut_annonce.Id AND recrut_candidature.Suppr=0 ";
							$requete2.=")=0
											OR ((SELECT COUNT(Id) FROM recrut_candidature WHERE Id_Annonce=recrut_annonce.Id AND recrut_candidature.Suppr=0 ";
							$requete2.=")/recrut_annonce.Nombre)<0.4
											) ";
											if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
												$requete2.="AND DateRecrutement>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
											}
											if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
												$requete2.="AND DateRecrutement<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
											}
											$requeteOrder="";
											if($_SESSION['TriRecrutIndicateur2_General']<>""){
												$requeteOrder="ORDER BY ".substr($_SESSION['TriRecrutIndicateur2_General'],0,-1);
											}
											
											$result=mysqli_query($bdd,$requete2.$requeteOrder);
											$nbRapport=mysqli_num_rows($result);
											$couleur="#FFFFFF";
											$nombre=0;
											$nombrePoste=0;
											if($nbRapport>0){
												while($row=mysqli_fetch_array($result)){
													if($couleur=="#FFFFFF"){$couleur="#EEEEEE";}
													else{$couleur="#FFFFFF";}
													$nombre+=$row['Nombre'];
													$nombrePoste+=$row['NombrePoste'];
													echo "<tr bgcolor='".$couleur."'><td>".$row['Ref']."</td><td>".$row['Lieu']."</td><td align='center'>".$row['Nombre']."/".$row['NombrePoste']."</td></tr>";
												}
											}
										?>
									</table>
								</td>
							</tr>
						</table>
						<table class="TableCompetences" width="99%" height="100%" cellpadding="0" cellspacing="0">
							<tr>
								<td class="Libelle" height="5%" style="font-size:15px;"><?php if($_SESSION['Langue']=="FR"){echo "OFFRES NON POURVUES / POURVUES PARTIELLEMENT";}else{echo "OFFERS NOT FILLED / PARTLY FILLED";} ?></td>
								<td style="cursor:pointer;" align="right"></td>
							</tr>
							<tr><td style="border-bottom:2px solid #0b6acb;" colspan="2" height="4"></td></tr>
							<tr><td height="4"></td></tr>
							<tr>
								<td valign="top">
									<table class="TableCompetences" width="99%" cellpadding="0" cellspacing="0">
										<tr>
											<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="TDB_Indicateurs.php?Tri3=Ref">Ref poste<?php if($_SESSION['TriRecrutIndicateur3_Ref']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutIndicateur3_Ref']=="ASC"){echo "&darr;";}?></a></td>
											<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="TDB_Indicateurs.php?Tri3=Metier">Métier<?php if($_SESSION['TriRecrutIndicateur3_Metier']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutIndicateur3_Metier']=="ASC"){echo "&darr;";}?></a></td>
											<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="TDB_Indicateurs.php?Tri3=Lieu">Lieu<?php if($_SESSION['TriRecrutIndicateur3_Lieu']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutIndicateur3_Lieu']=="ASC"){echo "&darr;";}?></a></td>
											<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="TDB_Indicateurs.php?Tri3=Nombre">Nbre de postes pourvus/Nbre de postes à pourvoir<?php if($_SESSION['TriRecrutIndicateur3_Nombre']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutIndicateur3_Nombre']=="ASC"){echo "&darr;";}?></a></td>
											<td class="EnTeteTableauCompetences"><a style="text-decoration:none;color:#000000;font-weight:bold;" id="tri" href="TDB_Indicateurs.php?Tri3=Restant">Reste à pourvoir<?php if($_SESSION['TriRecrutIndicateur3_Restant']=="DESC"){echo "&uarr;";} elseif($_SESSION['TriRecrutIndicateur3_Restant']=="ASC"){echo "&darr;";}?></a></td>
										</tr>
										<?php 
											$requete2="SELECT CONCAT(Metier,'-',
											Lieu,'-',
											Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',DATE_FORMAT(DateRecrutement,'%d%m%y')
											) AS Ref,Metier,Lieu,recrut_annonce.Nombre AS NombrePoste,
											(SELECT COUNT(Id) FROM recrut_candidature WHERE Id_Annonce=recrut_annonce.Id AND recrut_candidature.Suppr=0 
											AND CandidatRetenu=1) AS Nombre,
											recrut_annonce.Nombre-(SELECT COUNT(Id) FROM recrut_candidature WHERE Id_Annonce=recrut_annonce.Id AND recrut_candidature.Suppr=0 
												AND CandidatRetenu=1
											) AS Restant
											FROM recrut_annonce
											WHERE Suppr=0  
											AND EtatRecrutement=1 
											AND EtatPoste IN (2,3)
											AND OuvertureAutresPlateformes=1 ";
											if($_SESSION['FiltreRecrutementIndicateur_DateDebut']<>""){
												$requete2.="AND DateRecrutement>='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateDebut'])."' ";
											}
											if($_SESSION['FiltreRecrutementIndicateur_DateFin']<>""){
												$requete2.="AND DateRecrutement<='".TrsfDate_($_SESSION['FiltreRecrutementIndicateur_DateFin'])."' ";
											}
											$requeteOrder="";
											if($_SESSION['TriRecrutIndicateur3_General']<>""){
												$requeteOrder="ORDER BY ".substr($_SESSION['TriRecrutIndicateur3_General'],0,-1);
											}
											
											$result=mysqli_query($bdd,$requete2.$requeteOrder);
											$nbRapport=mysqli_num_rows($result);
											$couleur="#89b8f1";
											$nombre=0;
											$nombrePoste=0;
											if($nbRapport>0){
												while($row=mysqli_fetch_array($result)){
													if($couleur=="#89b8f1"){$couleur="#EEEEEE";}
													else{$couleur="#89b8f1";}
													$nombre+=$row['Nombre'];
													$nombrePoste+=$row['NombrePoste'];
													$Restant=$row['Restant'];
													if($Restant<0){$Restant=0;}
													echo "<tr bgcolor='".$couleur."'><td>".$row['Ref']."</td><td>".$row['Metier']."</td><td>".$row['Lieu']."</td><td align='center'>".$row['Nombre']."/".$row['NombrePoste']."</td><td align='center'>".$Restant."</td></tr>";
												}
											}
										?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td  align="right" valign="top" width="15%">
			<table class="GeneralInfo" style="border-spacing:0; width:95%; align:center;box-shadow: 0 8px 10px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
				<tr><td height="4px"></td></tr>
				<tr>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Date début";}else{echo "Start date";}?> : </td>
				</tr>
				<tr>
					<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateDebut" name="DateDebut" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltreRecrutementIndicateur_DateDebut']); ?>"></td>
				</tr>
				<tr>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Date fin";}else{echo "End date";}?> : </td>
				</tr>
				<tr>
					<td class="Libelle" width="10%"><input type="date" style="text-align:center;"id="DateFin" name="DateFin" size="10" value="<?php echo AfficheDateFR($_SESSION['FiltreRecrutementIndicateur_DateFin']); ?>"></td>
				</tr>
				<tr><td height="4px"></td></tr>
				<tr>
					<td class="Libelle" width="10%"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
				</tr>
				<tr>
					<td class="Libelle" width="10%"><input type="checkbox" name="selectAllPlateforme" id="selectAllPlateforme" onclick="SelectionnerToutPlateforme()" /><?php if($LangueAffichage=="FR"){echo "Sél. tout";}else{echo "Select all";} ?></td>
				</tr>
				
				<tr>
					<td>
						<div id='Div_RespProjet' style="height:150px;overflow:auto;">
							<table width='100%'>
								<?php
									$requetePlateforme="SELECT Id, Libelle 
										FROM new_competences_plateforme 
										WHERE Id IN (1,3,4,5,9,10,13,17,19,23,24,27) 
										ORDER BY Libelle";
									
									$resultPlateforme=mysqli_query($bdd,$requetePlateforme);
									while($rowPlat=mysqli_fetch_array($resultPlateforme))
									{
										$checked="";
										if($_POST){
											$checkboxes = isset($_POST['Id_Plateforme']) ? $_POST['Id_Plateforme'] : array();
											foreach($checkboxes as $value) {
												if($rowPlat['Id']==$value){$checked="checked";}
											}
										}
										else{
											$checked="checked";	
										}
										
										echo "<tr><td>";
										echo "<input type='checkbox' class='checkPlateforme' name='Id_Plateforme[]' Id='Id_Plateforme[]' value='".$rowPlat['Id']."' ".$checked.">".$rowPlat['Libelle'];
										echo "</td></tr>";
									}
								?>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td align="center">
						<img id="btnFiltrer" name="btnFiltrer" src="../../Images/jumelle.png" alt="submit" style="cursor:pointer;" onclick="filtrer();"/> 
						<div id="filtrer"></div>
					</td>
				</tr>
				<tr><td height="4px"></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
</table>
</form>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
	
</body>
</html>
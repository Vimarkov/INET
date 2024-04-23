<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Individual competency list</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<style>
		html,body
		{
			background-color:#FFFFFF;;
		}
	</style>
	<script type="text/javascript">
	function OuvrirFichier(Fic)
	{
		window.open("../../../Qualite/D/5/"+Fic+"-GRP-fr.pdf","PageFicheMetier","status=no,menubar=no,width="+screen.width-10+",height="+screen.height-10+",resizable=yes");
	}
	</script>
</head>

<?php
require_once("../Connexioni.php");
require_once("../Fonctions.php");

if($_POST){$result=mysqli_query($bdd,"SELECT Nom, Prenom, NumBadge, Date_Naissance,CertifyingStaffNumber,CertifyingStaffPrecision FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=".$_POST['Id_Personne']);}
else{$result=mysqli_query($bdd,"SELECT Nom, Prenom, NumBadge, Date_Naissance,CertifyingStaffNumber,CertifyingStaffPrecision FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=".$_GET['Id_Personne']);}
$row=mysqli_fetch_array($result);
$Prenom=$row['Prenom'];
$Nom=$row['Nom'];
$NumBadge=$row['NumBadge'];
$Date_Naissance=$row['Date_Naissance'];
$CertifyingStaffNumber=$row['CertifyingStaffNumber'];
$CertifyingStaffPrecision=$row['CertifyingStaffPrecision'];
$Filiale=0;


//Logo
$Logo_Plateforme="";
$result=mysqli_query($bdd,"SELECT new_competences_plateforme.* FROM new_competences_personne_plateforme, new_competences_plateforme WHERE new_competences_personne_plateforme.Id_Plateforme=new_competences_plateforme.Id AND new_competences_personne_plateforme.Id_Personne=".$_GET['Id_Personne']." ORDER BY new_competences_personne_plateforme.Id_Plateforme DESC");
$nbenreg=mysqli_num_rows($result);
if($nbenreg>0)
{
	$row=mysqli_fetch_array($result);
	$Logo_Plateforme=$row["Logo"];
}

//Prestation 
$requete="
	SELECT
		Libelle,CDCRef,CDCTitre,SiteAirbus,Commodity,Produit,Scope,Programme,AfficherDateAnniversaire,
		SousTraitant,SousTraitantAdresse,SousTraitantARP_ID,Id_Plateforme,
		SousTraitantPointFocal,SousTraitantPointFocalTel,SousTraitantPointFocalEmail,
		(SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne)
		FROM new_competences_personne_poste_prestation
		WHERE Id_Prestation=new_competences_prestation.Id 
		AND Backup=0
		AND Id_Poste=4
		LIMIT 1) AS RespProjet,
		(SELECT (SELECT TelephoneProMobil FROM new_rh_etatcivil WHERE Id=Id_Personne)
		FROM new_competences_personne_poste_prestation
		WHERE Id_Prestation=new_competences_prestation.Id 
		AND Backup=0
		AND Id_Poste=4
		LIMIT 1) AS TelRespProjet,
		(SELECT (SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Personne)
		FROM new_competences_personne_poste_prestation
		WHERE Id_Prestation=new_competences_prestation.Id 
		AND Backup=0
		AND Id_Poste=4
		LIMIT 1) AS MailRespProjet,
		(SELECT (SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne)
		FROM new_competences_personne_poste_prestation
		WHERE Id_Prestation=new_competences_prestation.Id 
		AND Backup=0
		AND Id_Poste=5
		LIMIT 1) AS CQP
	FROM
		new_competences_prestation
	WHERE
		Id=".$_GET['Id_Prestation']." ";
$result=mysqli_query($bdd,$requete);
$nbenreg=mysqli_num_rows($result);
$cdcRef="";
$cdcTitre="";
$siteAirbus="";
$commodity="";
$produit="";
$scope="";
$Libelle="";
$programme="";
$RespProjet="";
$TelRespProjet="";
$EmailRespProjet="";
$cqp="";
$AfficherDateAnniversaire="";
$SousTraitant="";
$SousTraitantAdresse="";
$SousTraitantARP_ID="";
$SousTraitantPointFocal="";
$SousTraitantPointFocalTel="";
$SousTraitantPointFocalEmail="";
if($nbenreg>0)
{
	$row=mysqli_fetch_array($result);
	$Libelle=$row["Libelle"];
	$Libelle=substr($row['Libelle'],0,strpos($row['Libelle']," "));
	if($Libelle==""){$Libelle=$row['Libelle'];}
							
	$cdcRef=$row["CDCRef"];
	$cdcTitre=$row["CDCTitre"];
	$siteAirbus=$row["SiteAirbus"];
	$commodity=$row["Commodity"];
	$produit=$row["Produit"];
	$scope=$row["Scope"];
	$programme=$row["Programme"];
	$RespProjet=$row["RespProjet"];
	$TelRespProjet=$row["TelRespProjet"];
	$EmailRespProjet=$row["MailRespProjet"];
	$cqp=$row["CQP"];
	$AfficherDateAnniversaire=$row["AfficherDateAnniversaire"];
	$SousTraitant=stripslashes($row["SousTraitant"]);
	$SousTraitantAdresse=stripslashes($row["SousTraitantAdresse"]);
	$SousTraitantARP_ID=stripslashes($row["SousTraitantARP_ID"]);
	$SousTraitantPointFocal=stripslashes($row["SousTraitantPointFocal"]);
	$SousTraitantPointFocalTel=stripslashes($row["SousTraitantPointFocalTel"]);
	$SousTraitantPointFocalEmail=stripslashes($row["SousTraitantPointFocalEmail"]);
	
	if($row['Id_Plateforme']==7 || $row['Id_Plateforme']==12 || $row['Id_Plateforme']==16
	|| $row['Id_Plateforme']==18 || $row['Id_Plateforme']==20 || $row['Id_Plateforme']==22
	|| $row['Id_Plateforme']==26 || $row['Id_Plateforme']==30){$Filiale=1;}


}

//Plateforme 
$requete="
	SELECT
		new_competences_plateforme.Libelle,
		new_competences_plateforme.Adresse,
		new_competences_plateforme.ARP_Id,
		new_competences_plateforme.Company,
		new_competences_plateforme.CompanyAdresse
	FROM
		new_competences_prestation
	LEFT JOIN new_competences_plateforme
	ON new_competences_prestation.Id_Plateforme=new_competences_plateforme.Id
	WHERE
		new_competences_prestation.Id=".$_GET['Id_Prestation']." ";
$resultPlateforme=mysqli_query($bdd,$requete);
$nbenregPlateforme=mysqli_num_rows($resultPlateforme);

$plateforme="";
$adresse="";
$arp_id="";
$entreprise="";
$entrepriseAdresse="";
if($nbenregPlateforme>0)
{
	$rowPlateforme=mysqli_fetch_array($resultPlateforme);
	$plateforme=stripslashes($rowPlateforme["Libelle"]);				
	$adresse=stripslashes($rowPlateforme["Adresse"]);
	$arp_id=stripslashes($rowPlateforme["ARP_Id"]);
	$entreprise=stripslashes($rowPlateforme["Company"]);
	$entrepriseAdresse=stripslashes($rowPlateforme["CompanyAdresse"]);
}

$Couleur="#FFFFFF";
?>

<body leftmargin="0" topmargin="0">
<table style="width:100%; align:center;">
	<tr>
		<td>
			<table class="ProfilCompetence" style="width:100%;">
				<tr>
					<td width="20%" rowspan="3">
						<img src="../../Images/Logos/Logo Daher_posi.png" width="148">
						<?php 
						if($Logo_Plateforme<>""){
							echo "<img src='../../Images/Logos/".$Logo_Plateforme."' width='148'>"; 
						}
						?>
					</td>
					<td bgcolor="#00325F" style="color:#ffffff;font-size:20px;" align="center" width="70%" rowspan="3">
						<font style="text-decoration=underline; font-weight=bold;">I N D I V I D U A L&nbsp;&nbsp;&nbsp;C O M P E T E N C Y&nbsp;&nbsp;&nbsp;L I S T - ICL/IDS</font>
					</td>
					<td width="10%" style="color:#505F69;">D-0732-1</td>
				</tr>
				<tr>
					<td style="color:#505F69;">Template issue 2</td>
				</tr>
				<tr>
					<td style="color:#505F69;"><?php echo "Feb. 20, 2024";
								?></td>
				</tr>
				<tr>
					<td colspan="3"><?php 
							$MoisLettre = array("Jan.", "Feb.", "Mar.", "Apr.", "May", "Jun.", "Jul.", "Aug.", "Sept.", "Oct.", "Nov.", "Dec.");
							echo "Content updated on : ".$MoisLettre[date('m')-1].date(' d, Y');
						
						?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table style="width:100%;">
				<tr bgcolor="#FFFFFF">
					<td colspan="3" align="center"><i><?php 
					echo "FOR INFORMATION";
					?></i></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table style="border-spacing:0; width:100%;">
				<tr valign="top">
					<td width="80">
						
					<td>
					<td align="right">
						<table class="ProfilCompetence">
							<tr class="TitreSousPageCompetences">
								<td>REF DOC</td>
								<td>Issue Doc</td>
							</tr>
							<tr bgcolor="#FFFFFF">
								<td>ICL-<?php echo $Libelle;?></td>
								<td align="center">1</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>			
	<tr>
		<td>
			<table style="border-spacing:0; width:100%;">
				<tr valign="top">
					<td>
						<table style="width:100%;" class="ProfilCompetence">
							<tr class="TitreSousPageCompetences">
								<td>SUPPLIER COMPANY</td>
								<td colspan="2">SUPPLIER COMPANY ADDRESS</td>
							</tr>
							<tr>
								<td align="left"><?php echo $entreprise; ?></td>
								<td colspan="2" align="left"><?php echo $entrepriseAdresse; ?></td>
							</tr>
							<!-- PLATEFORME -->
							<!--############-->
							<tr class="TitreSousPageCompetences">
								<td>OPERATING UNIT</td>
								<td>AAA ADDRESS</td>
								<td>ARP ID</td>
							</tr>
							
							<tr bgcolor="<?php echo $Couleur;?>">
								<td><?php echo $plateforme; ?></td>
								<td><?php echo $adresse; ?></td>
								<td><?php echo $arp_id; ?></td>
							</tr>

							<tr class="TitreSousPageCompetences">
								<td colspan="3">SUPPLIER FOCAL POINT</td>
							</tr>
							<tr>
								<td><?php echo $RespProjet; ?></td>
								<td><?php echo $TelRespProjet; ?></td>
								<td><?php echo $EmailRespProjet; ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table style="border-spacing:0; width:100%;">
				<tr>
					<td>
						<table style="width:100%;" class="ProfilCompetence">
							<!-- METIER/FONCTION -->
							<!-- ############### -->
							<?php
								$requeteFonctionMetier="
                                    SELECT
                                        new_competences_metier.Libelle,
                                        new_competences_metier.Fiche
                                    FROM
                                        new_competences_personne_metier,
                                        new_competences_metier
                                    WHERE
                                        new_competences_personne_metier.Id_Metier=new_competences_metier.Id
                                        AND new_competences_personne_metier.Id_Personne=".$_GET['Id_Personne']."
                                    ORDER BY
                                        new_competences_personne_metier.Id DESC";
							?>
							<tr class="TitreSousPageCompetences">
								<td>NAME OF THE INTERVENER</td>
								<?php if($AfficherDateAnniversaire==1){?>
								<td>DATE OF BIRTH</td>
								<?php }?>
								<td>JOB/FUNCTION</td>
								<td>BADGE</td>
								<td>STAMPS NUMBERS</td>
							</tr>
							<tr bgcolor="#FFFFFF">
								<td><?php if($_GET['Affiche']=="Nom"){echo $Nom." ".$Prenom;}else{echo "AAA-".$_GET['Id_Personne'];} ?></td>
								<?php if($AfficherDateAnniversaire==1){?>
								<td><?php echo AfficheDateJJ_MM_AAAA($Date_Naissance); ?></td>
								<?php }?>
								<td>
									<?php
										$FonctionMetier="";
										$result=mysqli_query($bdd,$requeteFonctionMetier);
										$nbenreg=mysqli_num_rows($result);
										if($nbenreg>0)
										{
											while($row=mysqli_fetch_array($result))
											{
												if($FonctionMetier<>""){$FonctionMetier.="<br>";}
												if($row['Fiche']!="" && file_exists($CheminQualite."D/5/".$row['Fiche']."-GRP-fr.pdf"))
												{
													$FonctionMetier.= $row['Libelle']." <a href=\"javascript:OuvrirFichier('".$row['Fiche']."');\">(".$row['Fiche'].")</a>";
												}
												else{$FonctionMetier.= $row['Libelle'];}
											}
										}
										echo $FonctionMetier;
									?>
								</td>
								<td><?php echo $NumBadge;?></td>
								<td>
								<?php
									$Stamps="";
									$result=mysqli_query($bdd,"SELECT Num_Stamp, Scope,Date_Debut,Date_Fin FROM new_competences_personne_stamp WHERE Id_Personne=".$_GET['Id_Personne']." AND Date_Debut<='".$DateJour."' AND (Date_Fin>='".$DateJour."' OR Date_Fin<='0001-01-01') ORDER BY Num_Stamp ASC");
									$nbenreg=mysqli_num_rows($result);
									$Couleur="#EEEEEE";
									if($nbenreg>0)
									{
										while($row=mysqli_fetch_array($result))
										{
											if($Stamps<>""){$Stamps.="<br>";}
											$Stamps.=$row['Num_Stamp']." # ".$row['Scope'];
											if(($row['Date_Debut']>'0001-01-01' ) || ($row['Date_Fin']>'0001-01-01' )){
												$Stamps.= " (".AfficheDateJJ_MM_AAAA($row['Date_Debut'])." - ".AfficheDateJJ_MM_AAAA($row['Date_Fin']).")";
											}
										}
									}
									echo $Stamps;
								?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		<td>
	</tr>
	
	<tr>
		<td>
			<table class="ProfilCompetence" style="width:100%;">
				<!-- PRESTATION -->
				<!--############-->
				<tr class="TitreSousPageCompetences">
					<td colspan="2">ACTIVITIES</td>
				</tr>
				<tr>
					<td class="TitreSousPageCompetencesPetit" style="text-align:left">Wording</td>
					<td class="TitreSousPageCompetencesPetit" width="80" >Start date</td>
				</tr>
				<?php
				$requete="
                    SELECT
                        new_competences_prestation.Libelle,
                        new_competences_personne_prestation.Date_Debut,
                        new_competences_prestation.Id
                    FROM
                        new_competences_personne_prestation,
                        new_competences_prestation
                    WHERE
                        new_competences_personne_prestation.Id_Prestation=new_competences_prestation.Id
                        AND new_competences_personne_prestation.Id_Personne=".$_GET['Id_Personne']."
						AND new_competences_personne_prestation.Id=".$_GET['Id_PrestaPers']."
                    ORDER BY
                        new_competences_personne_prestation.Date_Debut DESC";
				$result=mysqli_query($bdd,$requete);
				$nbenreg=mysqli_num_rows($result);
				$Couleur="#EEEEEE";
				if($nbenreg>0)
				{
					while($row=mysqli_fetch_array($result))
					{
						if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
						else{$Couleur="#EEEEEE";}
				?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td><?php echo $row[0]; ?></td>
					<td width="80"><?php echo $row[1]; ?></td>
				</tr>
				<?php
					}
				}
				?>
			</table>
		</td>
	</tr>
	
	<tr>
		<td>
			<table style="border-spacing:0; width:100%;">
				<tr valign="top">
					<td>
						<table style="width:100%;" class="ProfilCompetence">
							<tr class="TitreSousPageCompetences">
								<td>WORK SPECIFICATION REF</td>
								<td>WORK SPECIFICATION TITLE/DESCRIPTION (if needed)</td>
							</tr>
							<tr>
								<td height="15"><?php echo $cdcRef; ?></td>
								<td><?php echo $cdcTitre; ?></td>
							</tr>
							<tr class="TitreSousPageCompetences">
								<td colspan="2">INTERVENTION SITE</td>
							</tr>
							<tr>
								<td colspan="2" height="15"><?php echo $siteAirbus; ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table style="border-spacing:0; width:100%;">
				<tr valign="top">
					<td>
						<table style="width:100%;" class="ProfilCompetence">
							<tr class="TitreSousPageCompetences">
								<td>COMMODITY</td>
								<td>PRODUCT</td>
								<td>SCOPE</td>
								<td>PROGRAM</td>
							</tr>
							<?php 
							$req="SELECT Id,Commodity, Product FROM new_competences_prestation_parametrage WHERE Suppr=0 AND Id_Prestation=".$_GET['Id_Prestation']." ";
							$resultParam=mysqli_query($bdd,$req);
							$nbParam=mysqli_num_rows($resultParam);
							if($nbParam>0)
							{
								while($rowParam=mysqli_fetch_array($resultParam))
								{
							?>
								<tr>
									<td height="15"><?php echo stripslashes($rowParam['Commodity']); ?></td>
									<td><?php echo stripslashes($rowParam['Product']); ?></td>
									<td>
									<?php
										$req="SELECT Info, Autre
										FROM new_competences_prestation_parametrage_detail 
										WHERE Suppr=0 
										AND Type='Scope' 
										AND Id_PrestationParametrage=".$rowParam['Id']." ";
										$resultParamD=mysqli_query($bdd,$req);
										$nbParamD=mysqli_num_rows($resultParamD);
										if($nbParamD>0)
										{
											$k=0;
											while($rowParamD=mysqli_fetch_array($resultParamD))
											{
												if($k>0){echo " | ";}
												echo $rowParamD['Info'];
												if($rowParamD['Autre']<>""){echo " (".stripslashes($rowParamD['Autre']).")";}
												$k++;
											}
										}
									?>
									</td>
									<td>
									<?php
										$req="SELECT Info 
										FROM new_competences_prestation_parametrage_detail 
										WHERE Suppr=0 
										AND Type='Program' 
										AND Id_PrestationParametrage=".$rowParam['Id']." ";
										$resultParamD=mysqli_query($bdd,$req);
										$nbParamD=mysqli_num_rows($resultParamD);
										if($nbParamD>0)
										{
											$k=0;
											while($rowParamD=mysqli_fetch_array($resultParamD))
											{
												if($k>0){echo " | ";}
												echo $rowParamD['Info'];
												$k++;
											}
										}
									?>
									</td>
								</tr>
							<?php
								}
							}
							?>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php	if($SousTraitant<>""){ ?>
	<tr>
		<td>
			<table style="border-spacing:0; width:100%;">
				<tr valign="top">
					<td>
						<table style="width:100%;" class="ProfilCompetence">
							<tr class="TitreSousPageCompetences">
								<td>CUSTOMER COMPANY</td>
								<td>CUSTOMER COMPANY ADDRESS</td>
								<td></td>
							</tr>
							<tr>
								<td height="15"><?php echo $SousTraitant; ?></td>
								<td><?php echo $SousTraitantAdresse; ?></td>
								<td></td>
							</tr>

							<tr class="TitreSousPageCompetences">
								<td colspan="3">CUSTOMER FOCAL POINT</td>
							</tr>
							<tr>
								<td height="15"><?php echo $SousTraitantPointFocal; ?></td>
								<td><?php echo $SousTraitantPointFocalTel; ?></td>
								<td><?php echo $SousTraitantPointFocalEmail; ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td>
			<table style="border-spacing:0; width:100%;">
				<tr valign="top">
					<td>
						<table style="width:100%;" class="ProfilCompetence">
							<tr class="TitreSousPageCompetences">
								<td>Certifying Staff</td>
								<td>Certifying Staff number</td>
								<td>Authorization to sign</td>
								<td>If Other - precision</td>
							</tr>
							<tr>
								<td height="15">
								<?php 
									if($CertifyingStaffNumber<>""){
										echo "Yes"; 
									}
									else{
										echo "No"; 
									}
								?>
								</td>
								<td><?php echo $CertifyingStaffNumber; ?></td>
								<td>
								<?php 
									$result=mysqli_query($bdd,"SELECT AutorisationSign FROM new_competences_personne_certifying WHERE Id_Personne=".$_GET['Id_Personne']." ORDER BY AutorisationSign ASC");
									$nbenreg=mysqli_num_rows($result);
									$autorisation="";
									if($nbenreg>0)
									{
										$k=0;
										while($rowAuto=mysqli_fetch_array($result))
										{
											if($k>0){$autorisation.= " | ";}
											$autorisation.=$rowAuto['AutorisationSign'];
											$k++;
										}
									}
									echo $autorisation;
								?>
								</td>
								<td><?php echo $CertifyingStaffPrecision; ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
	$requete_Deb="
        SELECT
            DISTINCT new_competences_qualification.Libelle,
            new_competences_relation.Date_Debut,
            new_competences_relation.Date_Fin,
            new_competences_relation.Resultat_QCM,
            new_competences_relation.Evaluation,
            new_competences_relation.Date_QCM,
            new_competences_relation.Date_Surveillance,
            IF(new_competences_relation.Sans_Fin='Oui',new_competences_relation.Sans_Fin,IF(Date_Fin>'0001-01-01','Non',IF(new_competences_qualification.Duree_Validite=0 && Evaluation<>'B' && Evaluation<>'Bi' && Evaluation<>'','Oui','Non'))) AS Sans_Fin,
            new_competences_categorie_qualification.Libelle,
            new_competences_relation.QCM_Surveillance
        FROM
            new_competences_relation,
            new_competences_qualification,
            new_competences_categorie_qualification
        WHERE
            new_competences_relation.Type='Qualification'
            AND new_competences_relation.Suppr=0
            AND new_competences_relation.Id_Qualification_Parrainage=new_competences_qualification.Id
            AND new_competences_qualification.Id_Categorie_Qualification=new_competences_categorie_qualification.Id
            AND
            (
                new_competences_relation.Date_Fin<='0001-01-01'
                OR new_competences_relation.Date_Fin >= '".$DateJour."'
            )
			AND new_competences_relation.Id_Qualification_Parrainage IN (
				SELECT Id_Qualification 
				FROM new_competences_prestation_qualification
				WHERE Id_Prestation=".$_GET['Id_Prestation']."
			)
            AND new_competences_relation.Visible=0
            AND new_competences_relation.Id_Personne=".$_GET['Id_Personne']."
            AND new_competences_categorie_qualification.Id_Categorie_Maitre=";
	$requete_Fin="
            ORDER BY
                new_competences_categorie_qualification.Libelle ASC,
                new_competences_qualification.Libelle ASC,
                new_competences_relation.Date_QCM DESC,
                new_competences_relation.Date_Debut DESC";
	?>
	
	<tr>
		<td>
			<table class="ProfilCompetence" style="width:100%;">
				<!-- JOB VALIDATION -->
				<!--###############-->
				<?php
				$Requete_Categorie="1";
				?>
				<tr class="TitreSousPageCompetences">
					<td colspan="7">JOB VALIDATION</td>
				</tr>
				<tr>
					<td colspan="2" class="TitreSousPageCompetencesPetit" style="text-align:left">Wording</td>
					<td width="80" class="TitreSousPageCompetencesPetit">Start date</td>
					<td width="80" class="TitreSousPageCompetencesPetit">End date</td>
					<td width="40" class="TitreSousPageCompetencesPetit">B/L/V</td>
					<td width="40" class="TitreSousPageCompetencesPetit">Score</td>
					<td width="80" class="TitreSousPageCompetencesPetit">QCM date</td>
				</tr>
				<?php
				$Couleur="#EEEEEE";
				$result=mysqli_query($bdd,$requete_Deb.$Requete_Categorie.$requete_Fin);
				$nbenreg=mysqli_num_rows($result);
				$Categorie="";
				$Libelle="";
				$Evalution="";
				if($nbenreg > 0)
				{
					while($LigneQualification=mysqli_fetch_array($result))
					{
						if($Categorie != $LigneQualification[8])
						{
							$Categorie=$LigneQualification[8];
							$Couleur=="#EEEEEE";
				?>
				<tr>
					<td colspan="9" class="PetiteCategorieCompetence"><b><?php echo $Categorie; ?></b></td>
				</tr>
				<?php
						}
						if($Libelle != $LigneQualification[0]
						|| ($Evalution=="Q" && $LigneQualification['Evaluation']=="S")
						|| ($Evalution=="S" && $LigneQualification['Evaluation']=="Q")
						)
						{
							$Libelle=$LigneQualification[0];
							$Evalution=$LigneQualification['Evaluation'];
							if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
							else{$Couleur="#EEEEEE";}
				?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td width="2"></td>
					<td><?php echo $Libelle; ?></td>
					<td title="Date de validation de la qualification"><?php if($LigneQualification[1]>'0001-01-01' && $LigneQualification[1]!='0001-01-01'){echo $LigneQualification[1];} ?></td>
					<td title="Date de fin de validité de la qualification"><?php if($LigneQualification[7]=='Oui'){echo "Sans limite";}elseif($LigneQualification[2]>'0001-01-01' && $LigneQualification[2]!='0001-01-01'){echo $LigneQualification[2];} ?></td>
					<td align="center"><?php echo $LigneQualification[4]; ?></td>
					<td align="center"><?php echo $LigneQualification[3]; ?></td>
					<td><?php if($LigneQualification[5]>'0001-01-01' && $LigneQualification[5]!='0001-01-01'){echo $LigneQualification[5];} ?></td>
				</tr>
				<?php
						}
					}
				}
				?>
			</table>
		</td>
	</tr>
	
	<tr>
		<td>
			<table class="ProfilCompetence" style="width:100%;">
				<!-- SPECIAL PROCESSES -->
				<!--###################-->
				<?php
				$Requete_Categorie="2";
				?>
				<tr class="TitreSousPageCompetences">
					<td colspan="9">SPECIAL PROCESSES</td>
				</tr>
				<tr>
					<td colspan="2" class="TitreSousPageCompetencesPetit" style="text-align:left">Wording</td>
					<td width="80" class="TitreSousPageCompetencesPetit">Start date</td>
					<td width="80" class="TitreSousPageCompetencesPetit">End date</td>
					<td width="40" class="TitreSousPageCompetencesPetit">B/L/Q/S/T</td>
					<td width="40" class="TitreSousPageCompetencesPetit">Score</td>
					<td width="80" class="TitreSousPageCompetencesPetit">QCM date</td>
					<td width="40" class="TitreSousPageCompetencesPetit">Monitoring score</td>
					<td width="80" class="TitreSousPageCompetencesPetit">Monitoring date</td>
				</tr>
				<?php
				$Couleur="#EEEEEE";
				$result=mysqli_query($bdd,$requete_Deb.$Requete_Categorie.$requete_Fin);
				$nbenreg=mysqli_num_rows($result);
				$Categorie="";
				$Libelle="";
				$Evalution="";
				if($nbenreg > 0)
				{
					while($LigneQualification=mysqli_fetch_array($result))
					{
						if($Categorie != $LigneQualification[8])
						{
							$Categorie=$LigneQualification[8];
							$Couleur=="#EEEEEE";
				?>
				<tr>
					<td colspan="9" class="PetiteCategorieCompetence"><b><?php echo $Categorie; ?></b></td>
				</tr>
				<?php
						}
						if($Libelle != $LigneQualification[0]
						|| ($Evalution=="Q" && $LigneQualification['Evaluation']=="S")
						|| ($Evalution=="S" && $LigneQualification['Evaluation']=="Q")
						)
						{
							$Libelle=$LigneQualification[0];
							$Evalution=$LigneQualification['Evaluation'];
							if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
							else{$Couleur="#EEEEEE";}
				?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td width="2"></td>
					<td><?php echo $Libelle; ?></td>
					<td title="Date de validation de la qualification"><?php if($LigneQualification[1]>'0001-01-01' && $LigneQualification[1]!='0001-01-01'){echo $LigneQualification[1];} ?></td>
					<td title="Date de fin de validité de la qualification"><?php if($LigneQualification[7]=='Oui'){echo "Sans limite";}elseif($LigneQualification[2]>'0001-01-01' && $LigneQualification[2]!='0001-01-01'){echo $LigneQualification[2];} ?></td>
					<td align="center"><?php echo $LigneQualification[4]; ?></td>
					<td align="center"><?php echo $LigneQualification[3]; ?></td>
					<td><?php if($LigneQualification[5]>'0001-01-01' && $LigneQualification[5]!='0001-01-01'){echo $LigneQualification[5];} ?></td>
					<td align="center"><?php echo $LigneQualification[9]; ?></td>
					<td title="Date de la réalisation de la surveillance"><?php if($LigneQualification[6]>'0001-01-01' && $LigneQualification[6]!='0001-01-01'){echo $LigneQualification[6];} ?></td>
				</tr>
				<?php
						}
					}
				}
				?>
			</table>
		</td>
	</tr>
	
	<tr>
		<td>
			<table class="ProfilCompetence" style="width:100%;">
				<!-- NO SPECIAL PROCESSES / SPECIFIC COMPETENCIES -->
				<!--###############-->
				<?php
				$Requete_Categorie="3";
				?>
				<tr class="TitreSousPageCompetences">
					<td colspan="7">NO SPECIAL PROCESSES / SPECIFIC COMPETENCIES</td>
				</tr>
				<tr>
					<td colspan="2" class="TitreSousPageCompetencesPetit" style="text-align:left">Wording</td>
					<td width="80" class="TitreSousPageCompetencesPetit">Start date</td>
					<td width="80" class="TitreSousPageCompetencesPetit">End date</td>
					<td width="40" class="TitreSousPageCompetencesPetit">B/L/X</td>
					<td width="40" class="TitreSousPageCompetencesPetit">Score</td>
					<td width="80" class="TitreSousPageCompetencesPetit">QCM date</td>
				</tr>
				<?php
				$Couleur="#EEEEEE";
				$result=mysqli_query($bdd,$requete_Deb.$Requete_Categorie.$requete_Fin);
				$nbenreg=mysqli_num_rows($result);
				$Categorie="";
				$Libelle="";
				$Evalution="";
				if($nbenreg > 0)
				{
					while($LigneQualification=mysqli_fetch_array($result))
					{
						if($Categorie != $LigneQualification[8])
						{
							$Categorie=$LigneQualification[8];
							$Couleur=="#EEEEEE";
				?>
				<tr>
					<td colspan="9" class="PetiteCategorieCompetence"><b><?php echo $Categorie; ?></b></td>
				</tr>
				<?php
						}
						if($Libelle != $LigneQualification[0]
						|| ($Evalution=="Q" && $LigneQualification['Evaluation']=="S")
						|| ($Evalution=="S" && $LigneQualification['Evaluation']=="Q")
						)
						{
							$Libelle=$LigneQualification[0];
							$Evalution=$LigneQualification['Evaluation'];
							if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
							else{$Couleur="#EEEEEE";}
				?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td width="2"></td>
					<td><?php echo $Libelle; ?></td>
					<td title="Date de validation de la qualification"><?php if($LigneQualification[1]>'0001-01-01' && $LigneQualification[1]!='0001-01-01'){echo $LigneQualification[1];} ?></td>
					<td title="Date de fin de validité de la qualification"><?php if($LigneQualification[7]=='Oui'){echo "Sans limite";}elseif($LigneQualification[2]>'0001-01-01' && $LigneQualification[2]!='0001-01-01'){echo $LigneQualification[2];} ?></td>
					<td align="center"><?php echo $LigneQualification[4]; ?></td>
					<td align="center"><?php echo $LigneQualification[3]; ?></td>
					<td><?php if($LigneQualification[5]>'0001-01-01' && $LigneQualification[5]!='0001-01-01'){echo $LigneQualification[5];} ?></td>
				</tr>
				<?php
						}
					}
				}
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table style="border-spacing:0; width:100%;">
				<tr valign="top">
					<td style="width:100%;">
						<table class="ProfilCompetence" style="width:100%;">
							<tr class="TitreSousPageCompetences">
								<td style="width:80%;">INTERVENER</td>
								<td style="width:20%;">QUALITY</td>
							</tr>
							<tr>
								<td style="width:85%;"><?php if($_GET['Affiche']=="Nom"){echo $Nom." ".$Prenom;}else{echo "AAA-".$_GET['Id_Personne'];} ?></td>
								<td style="width:15%;"><?php echo $cqp;?></td>
							</tr>
							<tr>
								<td colspan="2" align="center">S02 AAA Group Process ensures that the intervener and its quality are aware of the trainings and qualifications as he signed at least and each time a training / qualification evidence</td>
							</tr>
						</table>
					<td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="20" align="center">
			<img src="../../Images/Legende_GPEC2.png">
		</td>
	</tr>
	<tr>
		<td>
			<table style="width:100%;">
				<tr>
					<td align="center"><font size=1>DIS QUALITY DOCUMENT - Reproduction forbidden without written authorization from DIS</font></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php
if($_GET['Id_Personne']!='0'){mysqli_free_result($result);}	// Libération des résultats}
mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
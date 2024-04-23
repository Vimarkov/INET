<?php
function creerMail($Type,$LangueAffichage,$Id_Annonce)
{
	global $bdd;
	global $IdPosteResponsableRecrutement;
	global $IdPosteRecrutement;
	global $IdPosteAssistantRH;
	global $IdPosteResponsableOperation;
	global $IdPosteResponsableRH;
	global $IdPosteResponsableProjet;
	global $IdPosteCoordinateurProjet;
	global $IdPosteResponsablePlateforme;
	
	$Headers='From: "Extranet Daher industriel services DIS"<noreply.extranet@aaa-aero.com>'."\n";
	$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
	
	
	if($LangueAffichage=="FR"){
		$reqSuite="IF(ValidationContratDG<>0,'OUI','NON') AS Etat, 
			IF(ValidationContratDG=0,'Besoin en attente validation DG',
				IF(ValidationContratDG=-1,'Besoin refusé par la DG','Offre')
			) AS Statut, ";
	}
	else{
		$reqSuite="IF(ValidationContratDG<>0,'YES','NO') AS Etat,
			IF(ValidationContratDG=0,'Need pending CEO validation',
				IF(ValidationContratDG=-1,'Need refused by the DG','Offer')
			) AS Statut, ";
	}
	$requete="SELECT Id,DateDemande,Id_Demandeur,Id_Prestation,Metier,Nombre,Lieu,RaisonRefus,Suppr,
				".$reqSuite."
				CONCAT(Metier,'-',
				Lieu,'-',
				Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',DATE_FORMAT(DateDemande,'%d%m%y')
				) AS Ref,RaisonRefus,RaisonRefusRecrutement,RaisonRefusApprobation,
				EtatApprobation,EtatRecrutement,Programme,CreationPoste,CategorieProf,IGD,Salaire,MotifDemande,
				DescriptifPoste,SavoirFaire,SavoirEtre,Prerequis,Diplome,
				DateBesoin,Duree,PosteDefinitif,Id_Prestation,Id_Domaine,
				(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
				(SELECT Libelle FROM talentboost_domaine WHERE Id=Id_Domaine) AS Domaine,Langue,
				(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
				Id_Plateforme,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,EtatValidation
		FROM talentboost_annonce
		WHERE talentboost_annonce.Id=".$Id_Annonce ;
	$result=mysqli_query($bdd,$requete);
	$row=mysqli_fetch_array($result);
	
	$lesDestinataires="";
	$destinataire="";
	if($Type=="BESOIN INTERNE"){
		if($LangueAffichage=="FR"){$Objet="Nouveau besoin interne - ".$row['Ref'];}
		else{$Objet="New internal need - ".$row['Ref'];}
		
		$req="SELECT DISTINCT EmailPro,
			CONCAT(Nom,' ',Prenom) AS Personne
			FROM new_competences_personne_poste_prestation 
			LEFT JOIN new_rh_etatcivil
			ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
			WHERE new_competences_personne_poste_prestation.Id_Poste IN (".$IdPosteResponsableOperation.",".$IdPosteResponsableProjet.",".$IdPosteCoordinateurProjet.") 
			AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) IN (".$row['Id_Plateforme'].") ";
		$Result2=mysqli_query($bdd,$req);
		$Nb=mysqli_num_rows($Result2);
		
		if($Nb>0)
		{
			while($Row2=mysqli_fetch_array($Result2))
			{
				if($destinataire<>""){$destinataire.=",";}
				if($Row2['EmailPro']<>""){$destinataire.=$Row2['EmailPro'];}
				$lesDestinataires.= $Row2['Personne'].",";
			}
		}
		
		if($row['OuvertureAutresPlateformes']==1){
			/*$req="SELECT DISTINCT EmailPro,
				CONCAT(Nom,' ',Prenom) AS Personne
				FROM new_competences_personne_poste_plateforme 
				LEFT JOIN new_rh_etatcivil
				ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
				WHERE new_competences_personne_poste_plateforme.Id_Poste IN (".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") 
				AND Id_Plateforme=".$row['Id_Plateforme']." ";
			$Result2=mysqli_query($bdd,$req);
			$Nb=mysqli_num_rows($Result2);
			
			
			if($Nb>0)
			{
				while($Row2=mysqli_fetch_array($Result2))
				{
					if($destinataire<>""){$destinataire.=",";}
					if($Row2['EmailPro']<>""){$destinataire.=$Row2['EmailPro'];}
					$lesDestinataires.= $Row2['Personne'].",";
				}
			}
			
			$req="SELECT DISTINCT EmailPro,
				CONCAT(Nom,' ',Prenom) AS Personne
				FROM new_competences_personne_poste_plateforme 
				LEFT JOIN new_rh_etatcivil
				ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
				WHERE new_competences_personne_poste_plateforme.Id_Poste IN (".$IdPosteResponsablePlateforme.") 
				AND Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) ";
			$Result2=mysqli_query($bdd,$req);
			$Nb=mysqli_num_rows($Result2);
			
			if($Nb>0)
			{
				while($Row2=mysqli_fetch_array($Result2))
				{
					if($destinataire<>""){$destinataire.=",";}
					if($Row2['EmailPro']<>""){$destinataire.=$Row2['EmailPro'];}
					$lesDestinataires.= $Row2['Personne'].",";
				}
			}*/
		}
		
		/*if($_SERVER['SERVER_NAME']=="127.0.0.1"){$destinataire="pfauge@aaa-aero.com";}
		else{$destinataire="edurand@aaa-aero.com";}*/
	}
	elseif($Type=="OFFRE EMPLOI"){
		if($LangueAffichage=="FR"){$Objet="Nouvelle demande de ressource - ".$row['Ref'];}
		else{$Objet="New resource request - ".$row['Ref'];}
		
		/*$req="SELECT DISTINCT EmailPro,
			CONCAT(Nom,' ',Prenom) AS Personne
			FROM new_competences_personne_poste_plateforme 
			LEFT JOIN new_rh_etatcivil
			ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
			WHERE new_competences_personne_poste_plateforme.Id_Poste IN (".$IdPosteResponsableRecrutement.",".$IdPosteRecrutement.",".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") 
			AND Id_Plateforme IN (1,3,4,5,9,10,13,17,19,23,24,27,28,29) 
			AND Id_Plateforme IN (".$row['Id_Plateforme'].") ";
		$Result2=mysqli_query($bdd,$req);*/
		
		$req="SELECT DISTINCT EmailPro,
			CONCAT(Nom,' ',Prenom) AS Personne
			FROM new_rh_etatcivil
			WHERE Id =".$row['Id_Demandeur']." ";
		$Result2=mysqli_query($bdd,$req);
		
		$Nb=mysqli_num_rows($Result2);

		if($Nb>0)
		{
			while($Row2=mysqli_fetch_array($Result2))
			{
				if($destinataire<>""){$destinataire.=",";}
				if($Row2['EmailPro']<>""){$destinataire.=$Row2['EmailPro'];}
				$lesDestinataires.= $Row2['Personne'].",";
			}
		}
		
		/*if($_SERVER['SERVER_NAME']=="127.0.0.1"){$destinataire="pfauge@aaa-aero.com";}
		else{$destinataire="edurand@aaa-aero.com";}*/
	}
	
	$Message="	<html>
					<head><title>BESOIN</title></head>
					<body>
						<table width='100%' cellpadding='0' cellspacing='0' align='center'>
							<tr>
								<td>
									<table width='100%' cellpadding='0' cellspacing='0'>
										<tr><td>
											<table width='100%' cellpadding='0' cellspacing='0'>
												<tr>
													<td align='center' style='border:1px solid black;'>
													</td>
													<td colspan='8' bgcolor='#2e5496' style='color:#ffffff;font-size:16px;border:1px solid black;font-weight:bold;' align='center'>";
													if($Type=="BESOIN INTERNE"){
														if($LangueAffichage=="FR"){$Message.="NOUVEAU BESOIN INTERNE";}else{$Message.="NEW INTERNAL NEED";}
													}
													elseif($Type=="OFFRE EMPLOI"){
														if($LangueAffichage=="FR"){$Message.="DEMANDE DE RESSOURCE";}else{$Message.="RESOURCE REQUEST";}
													}
													$Message.="</td>
												</tr>
												<tr height='10'>
													<td width='15%' bgcolor='#2e5496' style='color:#ffffff;border:1px solid black;font-weight:bold;' rowspan='2'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.="Poste recherché";}else{$Message.="Job sought";}
													$Message.="</td>
													<td width='10%' style='border:1px solid black;font-weight:bold;'>&nbsp;&nbsp;".stripslashes($row['Metier'])."</td>
													<td width='5%' bgcolor='#cad8ee' style='color:#2c538b;border:1px solid black;font-weight:bold;'>&nbsp;&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "H/F";}else{$Message.= "M/W";}
													$Message.="</td>
													<td width='10%'  bgcolor='#cad8ee' style='color:#2c538b;border:1px solid black;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Lieu :";}else{$Message.= "Place :";}
													$Message.="</td>
													<td width='10%' style='border:1px solid black;font-weight:bold;'>&nbsp;&nbsp;".stripslashes($row['Lieu']);
													$Message.="</td>
													<td width='10%' bgcolor='#cad8ee' style='color:#2c538b;border:1px solid black;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Programme :";}else{$Message.= "Programm :";}
													$Message.="</td>
													<td width='10%' style='border:1px solid black;font-weight:bold;'>&nbsp;&nbsp;".stripslashes($row['Programme'])."</td>
													<td width='10%' bgcolor='#cad8ee' style='color:#2c538b;border:1px solid black;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Domaine :";}else{$Message.= "Domain :";}
													$Message.="</td>
													<td width='10%' style='border:1px solid black;font-weight:bold;'>&nbsp;&nbsp;".stripslashes($row['Domaine'])."</td>
												</tr>
												<tr height='10'>
													<td bgcolor='#cad8ee' style='color:#2c538b;border:1px solid black;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Date début de poste souhaitée :";}else{$Message.= "Desired start date :";}
													$Message.="</td>
													<td style='border:1px solid black;font-weight:bold;'>&nbsp;&nbsp;".AfficheDateJJ_MM_AAAA($row['DateBesoin'])."</td>
													<td bgcolor='#cad8ee' style='color:#2c538b;border:1px solid black;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Type de poste :";}else{$Message.= "Position type :";}
													$Message.="</td>
													<td style='border:1px solid black;font-weight:bold;'>&nbsp;&nbsp;";
													if($row['PosteDefinitif']==1){
														if($LangueAffichage=="FR"){$Message.= "Poste définitif";}else{$Message.= "Definitive position";}
													}
													elseif($row['PosteDefinitif']==2){
														if($LangueAffichage=="FR"){$Message.= "CDD 6 mois";}else{$Message.= "CDD 6 mois";}
													}
													elseif($row['PosteDefinitif']==3){
														if($LangueAffichage=="FR"){$Message.= "CDD 2 mois";}else{$Message.= "CDD 2 mois";}
													}
													elseif($row['PosteDefinitif']==4){
														if($LangueAffichage=="FR"){$Message.= "CDD";}else{$Message.= "CDD";}
													}
													else{
														if($LangueAffichage=="FR"){$Message.= "Mission à court terme";}else{$Message.= "Short term mission";}
													}
													$Message.="</td>";
													$Message.="<td bgcolor='#cad8ee' style='color:#2c538b;border:1px solid black;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){
														$Message.="Statut";
													}
													else{
														$Message.="Status";
													}
													$Message.=": </td>";
													$Message.="<td colspan='3' style='border:1px solid black;font-weight:bold;'>&nbsp;&nbsp;".$row['CategorieProf']."</td>";
												$Message.="</tr>
												<tr height='50'>
													<td valign='align' bgcolor='#2e5496' style='color:#ffffff;border:1px solid black;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Motif de la demande";}else{$Message.= "Reason for the request";}
													$Message.="</td>
													<td colspan='8' style='border:1px solid black;font-weight:bold;'>".nl2br(stripslashes($row['MotifDemande']))."</td>
												</tr>
												<tr height='50'>
													<td valign='align' bgcolor='#2e5496' style='color:#ffffff;border:1px solid black;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Descriptif du poste détaillé";}else{$Message.= "Detailed job description";}
													$Message.="</td>
													<td colspan='8' style='border:1px solid black;font-weight:bold;'>".nl2br(stripslashes($row['DescriptifPoste']))."</td>
												</tr>
												<tr height='50'>
													<td valign='align' bgcolor='#2e5496' style='color:#ffffff;border:1px solid black;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.="Savoir faire :<br><br>Qualités professionnelles<br><br>Polyvalence métier<br><br>Compétences techniques<br><br>Compétences managériales";}else{$Message.=  "Know-how :<br><br>Professional skills<br><br>-Experience<br><br>Business versatility<br><br>Technical skills<br><br>Managerial skills";}
													$Message.="</td>
													<td colspan='8' style='border:1px solid black;font-weight:bold;'>".nl2br(stripslashes($row['SavoirFaire']))."</td>
												</tr>
												<tr height='50'>
													<td valign='align' bgcolor='#2e5496' style='color:#ffffff;border:1px solid black;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Prérequis";}else{$Message.= "Prerequisites";}
													$Message.="</td>
													<td colspan='8' style='border:1px solid black;font-weight:bold;'>
														<table width='100%' cellpadding='0' cellspacing='0'>
															<tr>
																<td width='50%' style='border-right:1px solid black;font-weight:bold;'>
																	<table width='100%'>";
																	$req="SELECT talentboost_prerequis.Libelle FROM talentboost_annonce_prerequis LEFT JOIN talentboost_prerequis ON talentboost_annonce_prerequis.Id_Prerequis=talentboost_prerequis.Id WHERE Id_Annonce=".$row['Id']." ORDER BY  talentboost_prerequis.Libelle ";
																	$result=mysqli_query($bdd,$req);
																	$nbenreg=mysqli_num_rows($result);
																	if($nbenreg>0)
																	{
																		while($rowSE=mysqli_fetch_array($result))
																		{
																			$Message.= "<tr><td style='font-weight:bold;'>";
																			$Message.= "- ".stripslashes($rowSE['Libelle'])." ";
																			$Message.= "</td></tr>";
																		}
																	}
																	$Message.="</table>
																</td>
																<td width='50%' style='border-left:1px solid black;font-weight:bold;'>".nl2br(stripslashes($row['Prerequis']))."</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr height='50'>
													<td valign='align' bgcolor='#2e5496' style='color:#ffffff;border:1px solid black;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Savoir être";}else{$Message.= "know how to be";}
													$Message.="</td>
													<td colspan='8' style='border:1px solid black;font-weight:bold;'>
														<table width='100%' cellpadding='0' cellspacing='0'>
															<tr>
																<td width='50%' style='border-right:1px solid black;font-weight:bold;'>
																	<table width='100%'>";
																	$req="SELECT talentboost_savoiretre.Libelle FROM talentboost_annonce_savoiretre LEFT JOIN talentboost_savoiretre ON talentboost_annonce_savoiretre.Id_SavoirEtre=talentboost_savoiretre.Id WHERE Id_Annonce=".$row['Id']." ORDER BY  talentboost_savoiretre.Libelle ";
																	$result=mysqli_query($bdd,$req);
																	$nbenreg=mysqli_num_rows($result);
																	if($nbenreg>0)
																	{
																		while($rowSE=mysqli_fetch_array($result))
																		{
																			$Message.= "<tr><td style='font-weight:bold;'>";
																			$Message.= "- ".stripslashes($rowSE['Libelle'])." ";
																			$Message.= "</td></tr>";
																		}
																	}
																	$Message.="</table>
																</td>
																<td width='50%' style='border-left:1px solid black;font-weight:bold;'>".nl2br(stripslashes($row['SavoirEtre']))."</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr height='50'>
													
													<td  valign='align' bgcolor='#2e5496' style='color:#ffffff;border:1px solid black;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Diplômes";}else{$Message.= "Diplomas";}
													$Message.="</td>
													<td colspan='8' style='border:1px solid black;font-weight:bold;'>".nl2br(stripslashes($row['Diplome']))."</td>
												</tr>
												<tr height='30'>
													<td bgcolor='#2e5496' style='color:#ffffff;border:1px solid black;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Langues :";}else{$Message.= "Languages :";}
													$Message.="</td>
													<td colspan='8' style='border:1px solid black;font-weight:bold;'>
														&nbsp;&nbsp;".stripslashes($row['Langue'])."
													</td>
												</tr>
												<tr height='30'>
													<td bgcolor='#2e5496' style='color:#ffffff;border:1px solid black;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Date de la demande du besoin :";}else{$Message.= "Date of requirement request :";}
													$Message.="</td>
													<td style='border:1px solid black;font-weight:bold;'>&nbsp;".AfficheDateJJ_MM_AAAA($row['DateDemande'])."</td>
													<td bgcolor='#cad8ee' style='color:#2c538b;border:1px solid black;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Ref annonce ";}else{$Message.= "Ref ad ";}
													$Message.="</td>
													<td colspan='6' style='border:1px solid black;font-weight:bold;'>
														&nbsp;".stripslashes($row['Ref'])."
													</td>
												</tr>
											</table>
										</td></tr>
									</table>
								</td>
							</tr>
						</table>
					</body>
				</html>";

	if($destinataire<>"")
	{
		if(mail($destinataire,$Objet,$Message,$Headers,'-f noreply.extranet@aaa-aero.com')){}
	}

}

function creerMailCandidature($Type,$LangueAffichage,$Id_Annonce,$Id_Candidature)
{
	global $bdd;
	global $IdPosteResponsableRecrutement;
	global $IdPosteRecrutement;
	global $IdPosteAssistantRH;
	global $IdPosteResponsableOperation;
	global $IdPosteResponsableRH;
	
	if($LangueAffichage=="FR"){
		$reqSuite="IF(EtatRecrutement<>0,'OFFRE','BESOIN') AS Etat, 
			IF(EtatValidation=0,'En attente validation',
				IF(EtatValidation=-1,'Refusé',
					IF(EtatValidation=1 && EtatApprobation=0,'En attente approbation',
						IF(EtatValidation=1 && EtatApprobation=-1,'Non approuvé',
								IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0,'En attente validation offre',
									IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'Refusé','Passage en annonce')
								)
							)
						)
					)
				) AS Statut, ";
	}
	else{
		$reqSuite="IF(EtatRecrutement<>0,'OFFER','NEED') AS Etat, 
			IF(EtatValidation=0,'Pending validation',
				IF(EtatValidation=-1,'Refuse',
					IF(EtatValidation=1 && EtatApprobation=0,'Pending approval',
						IF(EtatValidation=1 && EtatApprobation=-1,'Not approved',
								IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0,'Pending validation offer',
									IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'Refuse','Switch to announcement')
								)
							)
						)
					)
				) AS Statut, ";
	}
	$requete="SELECT Id,DateDemande,Id_Demandeur,Id_Prestation,Metier,Nombre,Lieu,RaisonRefus,Suppr,
				".$reqSuite."
				CONCAT(Metier,'-',
				Lieu,'-',
				Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',IF(DateRecrutement>0,DATE_FORMAT(DateRecrutement,'%d%m%y'),DATE_FORMAT(DateDemande,'%d%m%y'))
				) AS Ref,RaisonRefus,RaisonRefusRecrutement,RaisonRefusApprobation,EtatPoste,
				EtatApprobation,EtatRecrutement,Programme,CreationPoste,CategorieProf,IGD,Salaire,
				DescriptifPoste,SavoirFaire,SavoirEtre,Prerequis,OuvertureAutresPlateformes,
				DateBesoin,Duree,PosteDefinitif,Id_Prestation,Id_Domaine,DateRecrutement,
				(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
				(SELECT Libelle FROM talentboost_domaine WHERE Id=Id_Domaine) AS Domaine,Langue,
				(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
				Id_Plateforme,
				(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS MailDemandeur,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,EtatValidation
		FROM talentboost_annonce
		WHERE talentboost_annonce.Id=".$Id_Annonce ;
	$result=mysqli_query($bdd,$requete);
	$row=mysqli_fetch_array($result);
	
	$req="SELECT Id,Id_Plateforme,Id_Prestation,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
	(SELECT Nom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Nom,
	(SELECT Prenom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Prenom,
	(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MatriculeAAA,
	(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
	(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
	DateCreation, LEFT(HeureCreation,8)	AS HeureCreation,Tel,Mail,MailPro,Responsable,Motivation,CV,CompetencesSpecifiques,PosteOccupe,
	CompetencesAcquises,Experiences,Diplomes,Langue1,NiveauLangue1,Langue2,NiveauLangue2,Langue3,NiveauLangue3
	FROM talentboost_candidature 
	WHERE Id=".$Id_Candidature." 
	ORDER BY DateCreation, HeureCreation ";
	$result=mysqli_query($bdd,$req);
	$rowCandidat=mysqli_fetch_array($result);
	
	$lesDestinataires="";
	$destinataire="";
	if($Type=="CANDIDATURE"){
		if($LangueAffichage=="FR"){$Objet="Nouvelle candidature - ".$rowCandidat['Personne']." - ".$row['Ref'];}
		else{$Objet="New application - ".$row['Ref'];}
		
		$req="SELECT DISTINCT EmailPro,
			CONCAT(Nom,' ',Prenom) AS Personne
			FROM new_competences_personne_poste_plateforme 
			LEFT JOIN new_rh_etatcivil
			ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
			WHERE new_competences_personne_poste_plateforme.Id_Poste IN (".$IdPosteResponsableRecrutement.") 
			AND Id_Plateforme IN (17) ";
		$Result2=mysqli_query($bdd,$req);
		$Nb=mysqli_num_rows($Result2);
		
		if($Nb>0)
		{
			while($Row2=mysqli_fetch_array($Result2))
			{
				if($destinataire<>""){$destinataire.=",";}
				if($Row2['EmailPro']<>""){$destinataire.=$Row2['EmailPro'];}
				$lesDestinataires.= $Row2['Personne'].",";
			}
		}
		
		$req="SELECT DISTINCT EmailPro,
			CONCAT(Nom,' ',Prenom) AS Personne
			FROM new_competences_personne_poste_plateforme 
			LEFT JOIN new_rh_etatcivil
			ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
			WHERE new_competences_personne_poste_plateforme.Id_Poste IN (".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") 
			AND Id_Plateforme IN (".$rowCandidat['Id_Plateforme'].") ";
		$Result2=mysqli_query($bdd,$req);
		$Nb=mysqli_num_rows($Result2);
		
		if($Nb>0)
		{
			while($Row2=mysqli_fetch_array($Result2))
			{
				if($destinataire<>""){$destinataire.=",";}
				if($Row2['EmailPro']<>""){$destinataire.=$Row2['EmailPro'];}
				$lesDestinataires.= $Row2['Personne'].",";
			}
		}
	}
	
	if($row['MailDemandeur']<>""){
		if($destinataire<>""){$destinataire.=",";}
		$destinataire.=$row['MailDemandeur'];
	}
	
	$Message="	<html>
					<head><title>BESOIN</title></head>
					<body>
						<table width='100%' cellpadding='0' cellspacing='0' align='center'>
							<tr>
								<td>
									<table width='100%' cellpadding='0' cellspacing='0'>
										<tr><td>
											<table  width='100%' cellpadding='0' cellspacing='0'>
												<tr>
													<td colspan='9' bgcolor='#2e5496' style='color:#ffffff;font-size:16px;border:1px solid black;font-weight:bold;' align='center'>";
													if($LangueAffichage=="FR"){$Message.= "FORMULAIRE DE CANDIDATURE POSTE EN INTERNE";}else{$Message.= "INTERNAL POST APPLICATION FORM";}
													$Message.="</td>
												</tr>
												<tr><td height='4'></td></tr>
												<tr>
													<td width='10%' rowspan='7' bgcolor='#2e5496' style='color:#ffffff;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Informations salarié";}else{$Message.= "Employee information";}
													$Message.="</td>
													<td width='10%'style='font-weight:bold' bgcolor='#b9c5c9' >&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Nom";}else{$Message.= "Last name";}
													$Message.="</td>
													<td width='10%' style='font-weight:bold' bgcolor='#b9c5c9'>&nbsp;".$rowCandidat['Nom']."</td>
													<td width='10%' style='font-weight:bold' bgcolor='#b9c5c9' >&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Prénom";}else{$Message.= "First name";}
													$Message.="</td>
													<td width='10%'  style='font-weight:bold' bgcolor='#b9c5c9'>&nbsp;".$rowCandidat['Prenom']."</td>
													<td width='10%' style='font-weight:bold' bgcolor='#b9c5c9' >&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Matricule";}else{$Message.= "Registration number";}
													$Message.="</td>
													<td width='10%'  style='font-weight:bold' bgcolor='#b9c5c9'>&nbsp;".$rowCandidat['MatriculeAAA']."</td>
												</tr>
												<tr><td height='4'></td></tr>
												<tr>
													<td width='8%' bgcolor='#cad8ee' style='color:#2c538b;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "N° téléphone :";}else{$Message.= "Telephone number :";}
													$Message.="</td>
													<td width='10%'>
														&nbsp;".stripslashes($rowCandidat['Tel'])."
													</td>
													<td width='10%' bgcolor='#cad8ee' style='color:#2c538b;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Poste occupé actuellement :";}else{$Message.= "Position currently occupied :";}
													$Message.="</td>
													<td width='10%'>
														&nbsp;".stripslashes($rowCandidat['PosteOccupe'])."
													</td>
													<td width='8%' bgcolor='#cad8ee' style='color:#2c538b;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Email pro. :";}else{$Message.= "Professional email :";}
													$Message.="</td>
													<td width='10%'>
														&nbsp;".stripslashes($rowCandidat['MailPro'])."
													</td>
												</tr>
												<tr><td height='4'></td></tr>
												<tr>
													<td width='10%' bgcolor='#cad8ee' style='color:#2c538b;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Unité d'exploitation :";}else{$Message.= "Operating unit :";}
													$Message.="</td>
													<td width='10%'>&nbsp;".stripslashes($rowCandidat['Plateforme'])."
													</td>
													<td width='10%' bgcolor='#cad8ee' style='color:#2c538b;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Prestation :";}else{$Message.= "Site :";}
													$Message.="</td>
													<td width='10%'>&nbsp;".stripslashes($rowCandidat['Prestation'])."
													</td>
													<td width='8%' bgcolor='#cad8ee' style='color:#2c538b;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Responsable actuel :";}else{$Message.= "Responsible :";}
													$Message.="</td>
													<td width='20%'>".stripslashes($rowCandidat['Responsable'])."
													</td>
												</tr>
												<tr><td height='4'></td></tr>
												<tr>
													<td width='8%' bgcolor='#cad8ee' style='color:#2c538b;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Email perso. :";}else{$Message.= "Personal email :";}
													$Message.="</td>
													<td width='10%'>
														&nbsp;".stripslashes($rowCandidat['Mail'])."
													</td>
												</tr>
												<tr><td height='4'></td></tr>
												<tr>
													<td width='10%' bgcolor='#2e5496' style='color:#ffffff;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Poste pour lequel vous postulez";}else{$Message.= "Position for which you are applying";}
													$Message.="</td>
													<td width='10%' style='font-weight:bold;' bgcolor='#b9c5c9'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Métier du poste";}else{$Message.= "Job";}
													$Message.="</td>
													<td width='10%' style='font-weight:bold;' bgcolor='#b9c5c9'>&nbsp;".stripslashes($row['Metier'])."</td>
													<td width='10%' style='font-weight:bold;' bgcolor='#b9c5c9'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Unité d'exploitation de destination";}else{$Message.= "Destination operating unit";}
													$Message.="</td>
													<td width='10%' style='font-weight:bold;' bgcolor='#b9c5c9'>&nbsp;".stripslashes($row['Plateforme'])."</td>
													<td width='10%' style='font-weight:bold;' bgcolor='#b9c5c9' >&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Ref du poste";}else{$Message.= "Job ref";}
													$Message.="</td>
													<td width='10%' style='font-weight:bold;' bgcolor='#b9c5c9' colspan='3'>&nbsp;".stripslashes($row['Ref'])."</td>
												</tr>
												<tr><td height='4'></td></tr>
												<tr>
													<td width='10%' bgcolor='#2e5496' valign='center' style='color:#ffffff;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Commentaires salariés (vos motivations…)";}else{$Message.= "Employee comments (your motivations…)";}
													$Message.="</td>
													<td width='30%' colspan='8'>
														&nbsp;".nl2br(stripslashes($rowCandidat['Motivation']))."
													</td>
												</tr>
											</table>
										</td></tr>
									</table>
								</td>
							</tr>
						</table>
					</body>
				</html>";
	
	
	/*if($_SERVER['SERVER_NAME']=="127.0.0.1"){$destinataire="pfauge@aaa-aero.com";}
	else{$destinataire="edurand@aaa-aero.com";}*/
	
	$PJ = array();
	
	if($rowCandidat['CV']<>""){
		$pj_item = array();
		$pj_item['chemin'] = 'Documents/';
		$pj_item['nom'] = $rowCandidat['CV'];
		$pj_item['MIME-Type'] = mime_content_type('Documents/'.$rowCandidat['CV']);
		$pj_item['attachement'] = encoderFichier('Documents/'.$rowCandidat['CV']);
		
		array_push($PJ, $pj_item);
	}
	
	
	if($destinataire<>"")
	{
		envoyerEMail($destinataire, $Objet, "", $Message, $PJ);
	}

}

function creerMailAnnulation($LangueAffichage,$Id_Annonce,$Id_Candidature)
{
	global $bdd;
	global $IdPosteResponsableRecrutement;
	global $IdPosteRecrutement;
	global $IdPosteAssistantRH;
	global $IdPosteResponsableOperation;
	global $IdPosteResponsableRH;
	
	if($LangueAffichage=="FR"){
		$reqSuite="IF(EtatRecrutement<>0,'OFFRE','BESOIN') AS Etat, 
			IF(EtatValidation=0,'En attente validation',
				IF(EtatValidation=-1,'Refusé',
					IF(EtatValidation=1 && EtatApprobation=0,'En attente approbation',
						IF(EtatValidation=1 && EtatApprobation=-1,'Non approuvé',
								IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0,'En attente validation offre',
									IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'Refusé','Passage en annonce')
								)
							)
						)
					)
				) AS Statut, ";
	}
	else{
		$reqSuite="IF(EtatRecrutement<>0,'OFFER','NEED') AS Etat, 
			IF(EtatValidation=0,'Pending validation',
				IF(EtatValidation=-1,'Refuse',
					IF(EtatValidation=1 && EtatApprobation=0,'Pending approval',
						IF(EtatValidation=1 && EtatApprobation=-1,'Not approved',
								IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0,'Pending validation offer',
									IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'Refuse','Switch to announcement')
								)
							)
						)
					)
				) AS Statut, ";
	}
	$requete="SELECT Id,DateDemande,Id_Demandeur,Id_Prestation,Metier,Nombre,Lieu,RaisonRefus,Suppr,
				".$reqSuite."
				CONCAT(Metier,'-',
				Lieu,'-',
				Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',IF(DateRecrutement>0,DATE_FORMAT(DateRecrutement,'%d%m%y'),DATE_FORMAT(DateDemande,'%d%m%y'))
				) AS Ref,RaisonRefus,RaisonRefusRecrutement,RaisonRefusApprobation,EtatPoste,
				EtatApprobation,EtatRecrutement,Programme,CategorieProf,
				DescriptifPoste,SavoirFaire,SavoirEtre,Prerequis,
				DateBesoin,Duree,PosteDefinitif,Id_Prestation,Id_Domaine,DateRecrutement,
				(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
				(SELECT Libelle FROM talentboost_domaine WHERE Id=Id_Domaine) AS Domaine,Langue,
				(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
				Id_Plateforme,
				(SELECT EmailPro FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS MailDemandeur,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,EtatValidation
		FROM talentboost_annonce
		WHERE talentboost_annonce.Id=".$Id_Annonce ;
	$result=mysqli_query($bdd,$requete);
	$row=mysqli_fetch_array($result);
	
	$req="SELECT Id,Id_Plateforme,Id_Prestation,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
	(SELECT Nom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Nom,
	(SELECT Prenom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Prenom,
	(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MatriculeAAA,
	(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
	(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
	DateCreation, LEFT(HeureCreation,8)	AS HeureCreation,Tel,Mail,MailPro,Responsable,Motivation,CV,CompetencesSpecifiques,PosteOccupe,
	CompetencesAcquises,Experiences,Diplomes,Langue1,NiveauLangue1,Langue2,NiveauLangue2,Langue3,NiveauLangue3
	FROM talentboost_candidature 
	WHERE Id=".$Id_Candidature." 
	ORDER BY DateCreation, HeureCreation ";
	$result=mysqli_query($bdd,$req);
	$rowCandidat=mysqli_fetch_array($result);
	
	$lesDestinataires="";
	$destinataire="";
	
	if($LangueAffichage=="FR"){$Objet="Annulation candidature - ".$rowCandidat['Personne']." - ".$row['Ref'];}
	else{$Objet="Application cancellation - ".$row['Ref'];}
	
	$req="SELECT DISTINCT EmailPro,
		CONCAT(Nom,' ',Prenom) AS Personne
		FROM new_competences_personne_poste_plateforme 
		LEFT JOIN new_rh_etatcivil
		ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
		WHERE new_competences_personne_poste_plateforme.Id_Poste IN (".$IdPosteResponsableRecrutement.") 
		AND Id_Plateforme IN (17) ";
	$Result2=mysqli_query($bdd,$req);
	$Nb=mysqli_num_rows($Result2);
	
	if($Nb>0)
	{
		while($Row2=mysqli_fetch_array($Result2))
		{
			if($destinataire<>""){$destinataire.=",";}
			if($Row2['EmailPro']<>""){$destinataire.=$Row2['EmailPro'];}
			$lesDestinataires.= $Row2['Personne'].",";
		}
	}
	
	$req="SELECT DISTINCT EmailPro,
		CONCAT(Nom,' ',Prenom) AS Personne
		FROM new_competences_personne_poste_plateforme 
		LEFT JOIN new_rh_etatcivil
		ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
		WHERE new_competences_personne_poste_plateforme.Id_Poste IN (".$IdPosteAssistantRH.",".$IdPosteResponsableRH.") 
		AND Id_Plateforme IN (".$rowCandidat['Id_Plateforme'].") ";
	$Result2=mysqli_query($bdd,$req);
	$Nb=mysqli_num_rows($Result2);
	
	if($Nb>0)
	{
		while($Row2=mysqli_fetch_array($Result2))
		{
			if($destinataire<>""){$destinataire.=",";}
			if($Row2['EmailPro']<>""){$destinataire.=$Row2['EmailPro'];}
			$lesDestinataires.= $Row2['Personne'].",";
		}
	}
	
	if($row['MailDemandeur']<>""){
		if($destinataire<>""){$destinataire.=",";}
		$destinataire.=$row['MailDemandeur'];
	}
	
	$Message="	<html>
					<head><title>ANNULATION</title></head>
					<body>
						<table width='100%' cellpadding='0' cellspacing='0' align='center'>
							<tr>
								<td>
									<table width='100%' cellpadding='0' cellspacing='0'>
										<tr><td>
											<table  width='100%' cellpadding='0' cellspacing='0'>
												<tr>
													<td colspan='9' bgcolor='#2e5496' style='color:#ffffff;font-size:16px;border:1px solid black;font-weight:bold;' align='center'>";
													if($LangueAffichage=="FR"){$Message.= "ANNULATION CANDIDATURE POSTE EN INTERNE";}else{$Message.= "CANCELLATION OF INTERNAL JOB CANDIDACY";}
													$Message.="</td>
												</tr>
												<tr><td height='4'></td></tr>
												<tr>
													<td width='10%' rowspan='7' bgcolor='#2e5496' style='color:#ffffff;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Informations salarié";}else{$Message.= "Employee information";}
													$Message.="</td>
													<td width='10%'style='font-weight:bold' bgcolor='#b9c5c9' >&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Nom";}else{$Message.= "Last name";}
													$Message.="</td>
													<td width='10%' style='font-weight:bold' bgcolor='#b9c5c9'>&nbsp;".$rowCandidat['Nom']."</td>
													<td width='10%' style='font-weight:bold' bgcolor='#b9c5c9' >&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Prénom";}else{$Message.= "First name";}
													$Message.="</td>
													<td width='10%'  style='font-weight:bold' bgcolor='#b9c5c9'>&nbsp;".$rowCandidat['Prenom']."</td>
													<td width='10%' style='font-weight:bold' bgcolor='#b9c5c9' >&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Matricule";}else{$Message.= "Registration number";}
													$Message.="</td>
													<td width='10%'  style='font-weight:bold' bgcolor='#b9c5c9'>&nbsp;".$rowCandidat['MatriculeAAA']."</td>
												</tr>
												<tr><td height='4'></td></tr>
												<tr>
													<td width='8%' bgcolor='#cad8ee' style='color:#2c538b;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "N° téléphone :";}else{$Message.= "Telephone number :";}
													$Message.="</td>
													<td width='10%'>
														&nbsp;".stripslashes($rowCandidat['Tel'])."
													</td>
													<td width='10%' bgcolor='#cad8ee' style='color:#2c538b;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Poste occupé actuellement :";}else{$Message.= "Position currently occupied :";}
													$Message.="</td>
													<td width='10%'>
														&nbsp;".stripslashes($rowCandidat['PosteOccupe'])."
													</td>
													<td width='8%' bgcolor='#cad8ee' style='color:#2c538b;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Email pro. :";}else{$Message.= "Professional email :";}
													$Message.="</td>
													<td width='10%'>
														&nbsp;".stripslashes($rowCandidat['MailPro'])."
													</td>
												</tr>
												<tr><td height='4'></td></tr>
												<tr>
													<td width='10%' bgcolor='#cad8ee' style='color:#2c538b;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Unité d'exploitation :";}else{$Message.= "Operating unit :";}
													$Message.="</td>
													<td width='10%'>&nbsp;".stripslashes($rowCandidat['Plateforme'])."
													</td>
													<td width='10%' bgcolor='#cad8ee' style='color:#2c538b;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Prestation :";}else{$Message.= "Site :";}
													$Message.="</td>
													<td width='10%'>&nbsp;".stripslashes($rowCandidat['Prestation'])."
													</td>
													<td width='8%' bgcolor='#cad8ee' style='color:#2c538b;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Responsable actuel :";}else{$Message.= "Responsible :";}
													$Message.="</td>
													<td width='20%'>".stripslashes($rowCandidat['Responsable'])."
													</td>
												</tr>
												<tr><td height='4'></td></tr>
												<tr>
													<td width='8%' bgcolor='#cad8ee' style='color:#2c538b;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Email perso. :";}else{$Message.= "Personal email :";}
													$Message.="</td>
													<td width='10%'>
														&nbsp;".stripslashes($rowCandidat['Mail'])."
													</td>
												</tr>
												<tr><td height='4'></td></tr>
												<tr>
													<td width='10%' bgcolor='#2e5496' style='color:#ffffff;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Poste pour lequel vous postulez";}else{$Message.= "Position for which you are applying";}
													$Message.="</td>
													<td width='10%' style='font-weight:bold;' bgcolor='#b9c5c9'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Métier du poste";}else{$Message.= "Job";}
													$Message.="</td>
													<td width='10%' style='font-weight:bold;' bgcolor='#b9c5c9'>&nbsp;".stripslashes($row['Metier'])."</td>
													<td width='10%' style='font-weight:bold;' bgcolor='#b9c5c9'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Unité d'exploitation de destination";}else{$Message.= "Destination operating unit";}
													$Message.="</td>
													<td width='10%' style='font-weight:bold;' bgcolor='#b9c5c9'>&nbsp;".stripslashes($row['Plateforme'])."</td>
													<td width='10%' style='font-weight:bold;' bgcolor='#b9c5c9' >&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Ref du poste";}else{$Message.= "Job ref";}
													$Message.="</td>
													<td width='10%' style='font-weight:bold;' bgcolor='#b9c5c9' colspan='3'>&nbsp;".stripslashes($row['Ref'])."</td>
												</tr>
												<tr><td height='4'></td></tr>
												<tr>
													<td width='10%' bgcolor='#2e5496' valign='center' style='color:#ffffff;font-weight:bold;'>&nbsp;";
													if($LangueAffichage=="FR"){$Message.= "Commentaires salariés (vos motivations…)";}else{$Message.= "Employee comments (your motivations…)";}
													$Message.="</td>
													<td width='30%' colspan='8'>
														&nbsp;".nl2br(stripslashes($rowCandidat['Motivation']))."
													</td>
												</tr>
											</table>
										</td></tr>
									</table>
								</td>
							</tr>
						</table>
					</body>
				</html>";
	
	
	/*if($_SERVER['SERVER_NAME']=="127.0.0.1"){$destinataire="pfauge@aaa-aero.com";}
	else{$destinataire="edurand@aaa-aero.com";}*/
	
	$PJ = array();
	
	if($destinataire<>"")
	{
		envoyerEMail($destinataire, $Objet, "", $Message, $PJ);
	}

}

function creerMailCandidat($Type,$LangueAffichage,$Id_Annonce,$Id_Candidature)
{
	global $bdd;
	global $IdPosteResponsableRecrutement;
	global $IdPosteRecrutement;
	global $IdPosteAssistantRH;
	global $IdPosteResponsableOperation;
	global $IdPosteResponsableRH;
	
	if($LangueAffichage=="FR"){
		$reqSuite="IF(EtatRecrutement<>0,'OFFRE','BESOIN') AS Etat, 
			IF(EtatValidation=0,'En attente validation',
				IF(EtatValidation=-1,'Refusé',
					IF(EtatValidation=1 && EtatApprobation=0,'En attente approbation',
						IF(EtatValidation=1 && EtatApprobation=-1,'Non approuvé',
								IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0,'En attente validation offre',
									IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'Refusé','Passage en annonce')
								)
							)
						)
					)
				) AS Statut, ";
	}
	else{
		$reqSuite="IF(EtatRecrutement<>0,'OFFER','NEED') AS Etat, 
			IF(EtatValidation=0,'Pending validation',
				IF(EtatValidation=-1,'Refuse',
					IF(EtatValidation=1 && EtatApprobation=0,'Pending approval',
						IF(EtatValidation=1 && EtatApprobation=-1,'Not approved',
								IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=0,'Pending validation offer',
									IF(EtatValidation=1 && EtatApprobation=1 && EtatRecrutement=-1,'Refuse','Switch to announcement')
								)
							)
						)
					)
				) AS Statut, ";
	}
	$requete="SELECT Id,DateDemande,Id_Demandeur,Id_Prestation,Metier,Nombre,Lieu,RaisonRefus,Suppr,
				".$reqSuite."
				CONCAT(Metier,'-',
				Lieu,'-',
				Programme,'-',IF(PosteDefinitif=1,'D',IF(PosteDefinitif=2 OR PosteDefinitif=3 OR PosteDefinitif=4,'C','M')),'-',IF(DateRecrutement>0,DATE_FORMAT(DateRecrutement,'%d%m%y'),DATE_FORMAT(DateDemande,'%d%m%y'))
				) AS Ref,RaisonRefus,RaisonRefusRecrutement,RaisonRefusApprobation,EtatPoste,
				EtatApprobation,EtatRecrutement,Programme,CreationPoste,CategorieProf,IGD,Salaire,
				DescriptifPoste,SavoirFaire,SavoirEtre,Prerequis,OuvertureAutresPlateformes,
				DateBesoin,Duree,PosteDefinitif,Id_Prestation,Id_Domaine,DateRecrutement,
				(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
				(SELECT Libelle FROM talentboost_domaine WHERE Id=Id_Domaine) AS Domaine,Langue,
				(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
				Id_Plateforme,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,EtatValidation
		FROM talentboost_annonce
		WHERE talentboost_annonce.Id=".$Id_Annonce ;
	$result=mysqli_query($bdd,$requete);
	$row=mysqli_fetch_array($result);
	
	$req="SELECT Id,Id_Plateforme,Id_Prestation,
	(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne,
	(SELECT Nom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Nom,
	(SELECT Prenom FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Prenom,
	(SELECT MatriculeAAA FROM new_rh_etatcivil WHERE Id=Id_Personne) AS MatriculeAAA,
	(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Plateforme,
	(SELECT LEFT(Libelle,7) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
	DateCreation, LEFT(HeureCreation,8)	AS HeureCreation,Tel,Mail,MailPro,Responsable,Motivation,CV,CompetencesSpecifiques,PosteOccupe,
	CompetencesAcquises,Experiences,Diplomes,Langue1,NiveauLangue1,Langue2,NiveauLangue2,Langue3,NiveauLangue3
	FROM talentboost_candidature 
	WHERE Id=".$Id_Candidature." 
	ORDER BY DateCreation, HeureCreation ";
	$result=mysqli_query($bdd,$req);
	$rowCandidat=mysqli_fetch_array($result);
	
	$lesDestinataires="";
	$destinataire="";

	if($LangueAffichage=="FR"){$Objet="Nouvelle candidature - ".$row['Ref'];}
	else{$Objet="New application - ".$row['Ref'];}
		
	$Message="	<html>
					<head><title>CANDIDATURE</title></head>
					<body>
						Bonjour,<br>
						Nous accusons réception de votre candidature, elle va être transférée au service RH et elle sera analysée attentivement.<br> 
						Si vous n’avez pas de réponses sous 3 semaines, votre candidature sera considérée comme non retenue.<br>
						Cordialement
					</body>
				</html>";
	
	
	$Email=$rowCandidat['Mail'];
	$PJ = array();
	
	if($Email<>"")
	{
		envoyerEMail2($Email, $Objet, "", $Message, $PJ);
	}

}

function envoyerEMail($destinataire, $sujet, $message_txt, $message_html, $PJ = Array()) {
	
    // regarde si il y a des Pieces Jointes
    if(count($PJ) > 0) {    
    	//$mail = 'pfauge@aaa-aero.com'; // Déclaration de l'adresse de destination.
    	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $destinataire)) // On filtre les serveurs qui présentent des bogues.
    	{
    		$passage_ligne = "\r\n";
    	}
    	else
    	{
    		$passage_ligne = "\n";
    	}
    	//=====Déclaration des messages au format texte et au format HTML.
    	//$message_txt = "Salut à tous, voici un e-mail envoyé par un script PHP.";
    	//$message_html = "<html><head></head><body><b>Salut à tous</b>, voici un e-mail envoyé par un <i>script PHP</i>.</body></html>";
    	//==========
    	
    	$attachements = array();
    
    	//=====Création de la boundary.
    	$boundary = "-----=".md5(rand());
    	$boundary_alt = "-----=".md5(rand());
    	//==========
    	
    	//=====Définition du sujet.
    	//$sujet = "Hey mon ami !";
    	//=========
    	
    	//=====Création du header de l'e-mail.
    	$header = "From: \"Extranet AAA\"<noreply.extranet@aaa-aero.com>".$passage_ligne;
    	$header.= "MIME-Version: 1.0".$passage_ligne;
    	$header.= "Content-Type: multipart/mixed;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
    	//==========
    	
    	//=====Création du message.
    	$message = $passage_ligne."--".$boundary.$passage_ligne;
    	$message.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary_alt\"".$passage_ligne;
    	$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
    	//=====Ajout du message au format texte.
    	$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
    	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    	$message.= $passage_ligne.$message_txt.$passage_ligne;
    	//==========
    	
    	$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
    	
    	//=====Ajout du message au format HTML.
    	$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
    	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    	$message.= $passage_ligne.$message_html.$passage_ligne;
    	//==========
    	
    	//=====On ferme la boundary alternative.
    	$message.= $passage_ligne."--".$boundary_alt."--".$passage_ligne;
    	//==========
    	
    	foreach($PJ as $current_PJ_infos) {
    	//for ($curseur = 0; $curseur < count($attachements); $curseur++ ) {
    		$message.= $passage_ligne."--".$boundary.$passage_ligne;
    		
    		//=====Ajout de la pièce jointe.
    		$message.= "Content-Type: image/jpeg; name=\"".$current_PJ_infos['nom']."\"".$passage_ligne;
    		$message.= "Content-Transfer-Encoding: base64".$passage_ligne;
    		$message.= "Content-Disposition: attachment; filename=\"".$current_PJ_infos['nom']."\"".$passage_ligne;
    		$message.= $passage_ligne.$current_PJ_infos['attachement'].$passage_ligne.$passage_ligne;
    	}
    	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
    	
    	//==========
    }else {
        //Headers
        $header='From: "Extranet Daher industriel services DIS"<noreply.extranet@aaa-aero.com>'." \n";
        $header.='Content-Type: text/html; charset="iso-8859-1"'." \n";
        
        //Message html
        $message = $message_html;
    }
	
	
	//=====Envoi de l'e-mail.
	if ($destinataire <> "")
		return mail($destinataire,$sujet,$message,$header,'-f noreply.extranet@aaa-aero.com');
	else
		return false;
	
	//==========
}
function envoyerEMail2($destinataire, $sujet, $message_txt, $message_html, $PJ = Array()) {
	
    // regarde si il y a des Pieces Jointes
    if(count($PJ) > 0) {    
    	//$mail = 'pfauge@aaa-aero.com'; // Déclaration de l'adresse de destination.
    	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $destinataire)) // On filtre les serveurs qui présentent des bogues.
    	{
    		$passage_ligne = "\r\n";
    	}
    	else
    	{
    		$passage_ligne = "\n";
    	}
    	//=====Déclaration des messages au format texte et au format HTML.
    	//$message_txt = "Salut à tous, voici un e-mail envoyé par un script PHP.";
    	//$message_html = "<html><head></head><body><b>Salut à tous</b>, voici un e-mail envoyé par un <i>script PHP</i>.</body></html>";
    	//==========
    	
    	$attachements = array();
    
    	//=====Création de la boundary.
    	$boundary = "-----=".md5(rand());
    	$boundary_alt = "-----=".md5(rand());
    	//==========
    	
    	//=====Définition du sujet.
    	//$sujet = "Hey mon ami !";
    	//=========
    	
    	//=====Création du header de l'e-mail.
    	$header = "From: \"Extranet AAA\"<extranetaaa@aaa-aero.com>".$passage_ligne;
		$header.= "bcc: pic75@aaa-aero.com".$passage_ligne;
    	$header.= "MIME-Version: 1.0".$passage_ligne;
    	$header.= "Content-Type: multipart/mixed;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
    	//==========
    	
    	//=====Création du message.
    	$message = $passage_ligne."--".$boundary.$passage_ligne;
    	$message.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary_alt\"".$passage_ligne;
    	$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
    	//=====Ajout du message au format texte.
    	$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
    	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    	$message.= $passage_ligne.$message_txt.$passage_ligne;
    	//==========
    	
    	$message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
    	
    	//=====Ajout du message au format HTML.
    	$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
    	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    	$message.= $passage_ligne.$message_html.$passage_ligne;
    	//==========
    	
    	//=====On ferme la boundary alternative.
    	$message.= $passage_ligne."--".$boundary_alt."--".$passage_ligne;
    	//==========
    	
    	foreach($PJ as $current_PJ_infos) {
    	//for ($curseur = 0; $curseur < count($attachements); $curseur++ ) {
    		$message.= $passage_ligne."--".$boundary.$passage_ligne;
    		
    		//=====Ajout de la pièce jointe.
    		$message.= "Content-Type: image/jpeg; name=\"".$current_PJ_infos['nom']."\"".$passage_ligne;
    		$message.= "Content-Transfer-Encoding: base64".$passage_ligne;
    		$message.= "Content-Disposition: attachment; filename=\"".$current_PJ_infos['nom']."\"".$passage_ligne;
    		$message.= $passage_ligne.$current_PJ_infos['attachement'].$passage_ligne.$passage_ligne;
    	}
    	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
    	
    	//==========
    }else {
        //Headers
        $header='From: "Extranet Daher industriel services DIS"<extranetaaa@aaa-aero.com>'." \n";
		$header.= "bcc: pic75@aaa-aero.com"." \n";
        $header.='Content-Type: text/html; charset="iso-8859-1"'." \n";
        
        //Message html
        $message = $message_html;
    }
	
	
	//=====Envoi de l'e-mail.
	if ($destinataire <> "")
		return mail($destinataire,$sujet,$message,$header,'-f extranetaaa@aaa-aero.com');
	else
		return false;
	
	//==========
}
?>
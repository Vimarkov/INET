<?php
require("../../Menu.php");
?>
<script type="text/javascript" src="Fonctions.js"></script>
<?php

//MISE A JOUR CORRESPONDANCE tools_caisse et tools_mouvement
$req="UPDATE tools_caisse
	SET tools_caisse.Id_PrestationT=(SELECT tools_mouvement.Id_Prestation
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0 
		 AND tools_mouvement.EtatValidation IN (0,1) 
		 AND tools_mouvement.Suppr=0 
		 AND tools_mouvement.Type=1 
		 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		)
	,
	tools_caisse.Id_PoleT=(SELECT tools_mouvement.Id_Pole
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0 
		 AND tools_mouvement.EtatValidation IN (0,1) 
		 AND tools_mouvement.Suppr=0 
		 AND tools_mouvement.Type=1 
		 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		)
	,
	 tools_caisse.Id_LieuT=(SELECT tools_mouvement.Id_Lieu
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0 
		 AND tools_mouvement.EtatValidation IN (0,1) 
		 AND tools_mouvement.Suppr=0 
		 AND tools_mouvement.Type=1 
		 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		)
	,
	 tools_caisse.Id_PersonneT=(SELECT tools_mouvement.Id_Personne
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0 
		 AND tools_mouvement.EtatValidation IN (0,1) 
		 AND tools_mouvement.Suppr=0 
		 AND tools_mouvement.Type=1 
		 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		)
	,
	 tools_caisse.DateReceptionT=(SELECT tools_mouvement.DateReception
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0 
		 AND tools_mouvement.EtatValidation IN (0,1) 
		 AND tools_mouvement.Suppr=0 
		 AND tools_mouvement.Type=1 
		 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		)
	,
	 tools_caisse.EtatValidationT=(SELECT tools_mouvement.EtatValidation
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0 
		 AND tools_mouvement.EtatValidation IN (0,1) 
		 AND tools_mouvement.Suppr=0 
		 AND tools_mouvement.Type=1 
		 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		) 
	,
	 tools_caisse.CommentaireT=(SELECT tools_mouvement.Commentaire
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0 
		 AND tools_mouvement.EtatValidation IN (0,1) 
		 AND tools_mouvement.Suppr=0 
		 AND tools_mouvement.Type=1 
		 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		) 
	WHERE tools_caisse.Suppr=0
	AND 
	(
	tools_caisse.Id_PrestationT<>(SELECT tools_mouvement.Id_Prestation
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0 
		 AND tools_mouvement.EtatValidation IN (0,1) 
		 AND tools_mouvement.Suppr=0 
		 AND tools_mouvement.Type=1 
		 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		)
	 OR
	tools_caisse.Id_PoleT<>(SELECT tools_mouvement.Id_Pole
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0 
		 AND tools_mouvement.EtatValidation IN (0,1) 
		 AND tools_mouvement.Suppr=0 
		 AND tools_mouvement.Type=1 
		 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		)
	 OR
	 tools_caisse.Id_LieuT<>(SELECT tools_mouvement.Id_Lieu
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0 
		 AND tools_mouvement.EtatValidation IN (0,1) 
		 AND tools_mouvement.Suppr=0 
		 AND tools_mouvement.Type=1 
		 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		)
	 OR
	 tools_caisse.Id_PersonneT<>(SELECT tools_mouvement.Id_Personne
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0 
		 AND tools_mouvement.EtatValidation IN (0,1) 
		 AND tools_mouvement.Suppr=0 
		 AND tools_mouvement.Type=1 
		 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		)
	 OR
	 tools_caisse.DateReceptionT<>(SELECT tools_mouvement.DateReception
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0 
		 AND tools_mouvement.EtatValidation IN (0,1) 
		 AND tools_mouvement.Suppr=0 
		 AND tools_mouvement.Type=1 
		 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		)
	OR
	 tools_caisse.EtatValidationT<>(SELECT tools_mouvement.EtatValidation
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0 
		 AND tools_mouvement.EtatValidation IN (0,1) 
		 AND tools_mouvement.Suppr=0 
		 AND tools_mouvement.Type=1 
		 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		)
	OR 
	tools_caisse.CommentaireT<>(SELECT tools_mouvement.Commentaire
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0 
		 AND tools_mouvement.EtatValidation IN (0,1) 
		 AND tools_mouvement.Suppr=0 
		 AND tools_mouvement.Type=1 
		 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		)
	)
	";
if($_SESSION['Id_Personne']==1351){
	$resultUpdtCaisse=mysqli_query($bdd,$req);
}

$req="UPDATE tools_materiel
SET
tools_materiel.Id_PrestationT=(SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Id_Prestation,
		(
		SELECT Id_Prestation
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		)
)
FROM tools_mouvement AS TAB_Mouvement
WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
),
tools_materiel.Id_PoleT=(SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Id_Pole,
		(
		SELECT Id_Pole
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		)
)
FROM tools_mouvement AS TAB_Mouvement
WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
),
tools_materiel.Id_CaisseT=(SELECT TAB_Mouvement.Id_Caisse
FROM tools_mouvement AS TAB_Mouvement
WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
),
tools_materiel.Id_LieuT=(SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Id_Lieu,
		(
		SELECT Id_Lieu
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		)
)
FROM tools_mouvement AS TAB_Mouvement
WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
),
tools_materiel.Id_PersonneT=(SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Id_Personne,
		(
		SELECT Id_Personne
		FROM tools_mouvement
		WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
		ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
		)
)
FROM tools_mouvement AS TAB_Mouvement
WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
),
tools_materiel.DateReceptionT=(SELECT TAB_Mouvement.DateReception
FROM tools_mouvement AS TAB_Mouvement
WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
),
tools_materiel.EtatValidationT=(SELECT TAB_Mouvement.EtatValidation
FROM tools_mouvement AS TAB_Mouvement
WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
),
tools_materiel.CommentaireT=(SELECT TAB_Mouvement.Commentaire
FROM tools_mouvement AS TAB_Mouvement
WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
)
WHERE Suppr=0
AND 
(
	Id_CaisseT<>(SELECT TAB_Mouvement.Id_Caisse
	FROM tools_mouvement AS TAB_Mouvement
	WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
	ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
	)
	OR
	Id_PrestationT<>(SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Id_Prestation,
			(
			SELECT Id_Prestation
			FROM tools_mouvement
			WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)
	)
	FROM tools_mouvement AS TAB_Mouvement
	WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
	ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
	)
	OR
	Id_PoleT<>(SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Id_Pole,
			(
			SELECT Id_Pole
			FROM tools_mouvement
			WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)
	)
	FROM tools_mouvement AS TAB_Mouvement
	WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
	ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
	)
	OR
	Id_LieuT<>(SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Id_Lieu,
			(
			SELECT Id_Lieu
			FROM tools_mouvement
			WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)
	)
	FROM tools_mouvement AS TAB_Mouvement
	WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
	ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
	)
	OR
	Id_PersonneT<>(SELECT IF(TAB_Mouvement.Id_Caisse=0,TAB_Mouvement.Id_Personne,
			(
			SELECT Id_Personne
			FROM tools_mouvement
			WHERE tools_mouvement.TypeMouvement=0  AND EtatValidation<>-1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=TAB_Mouvement.Id_Caisse
			ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1
			)
	)
	FROM tools_mouvement AS TAB_Mouvement
	WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
	ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
	)
	OR
	DateReceptionT<>(SELECT TAB_Mouvement.DateReception
	FROM tools_mouvement AS TAB_Mouvement
	WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
	ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
	)
	OR
	EtatValidationT<>(SELECT TAB_Mouvement.EtatValidation
	FROM tools_mouvement AS TAB_Mouvement
	WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
	ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
	)
	OR
	CommentaireT<>(SELECT TAB_Mouvement.Commentaire
	FROM tools_mouvement AS TAB_Mouvement
	WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.EtatValidation<>-1 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=0 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_materiel.Id
	ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
	)
)";
if($_SESSION['Id_Personne']==1351){
	$resultUpdtMateriel=mysqli_query($bdd,$req);
}

function Titre($Libelle,$Lien)
{
	global $HTTPServeur;
	echo "<tr><td>&nbsp;&bull;&nbsp;<a style=\"color:black;text-decoration: none;\" onmouseover=\"this.style.color='#0d8df1';\" onmouseout=\"this.style.color='#000000';\" href='".$HTTPServeur.$Lien."' >".$Libelle."</a></td></tr>\n";
}

function Widget($Libelle,$Lien,$Image,$Couleur,$InfosSupp="")
{
	global $HTTPServeur;
	
	echo "
		<table style='border-spacing: 10px;display:inline-table;' >
			<tr>
				<td style=\"width:130px;height:110px;border-style:outset; border-radius: 15px;border-color:".$Couleur.";border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='".$Couleur."'>
					<a style=\"text-decoration:none;width:130px;border-spacing:0;text-align:center;color:black;font-weight:bold;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" href='".$HTTPServeur.$Lien."' >
						<img width='40px' src='../../Images/".$Image."' border='0' /><br>
						".$Libelle."
					</a>
				</td>
			</tr>";
	
	$css="";
	
	if($InfosSupp<>""){$css="bgcolor='".$Couleur."' width='150px'";}
	
	echo "
		<tr>
			<td ".$css.">
				".$InfosSupp."
			</tD>
		</tr>
	";
	echo "</table>";
}

function WidgetTDB($Libelle,$Image,$Couleur,$CouleurLogo,$nb,$Libelle2,$Lien){
	$couleurNombre="";
	if($nb<>"0" && $nb<>"0/0"){$couleurNombre="color:#de0006;";}
	echo "
		<table style='border-spacing: 10px;display:inline-table;' >
			<tr>
				<td style=\"width:230px;height:90px;border-style:outset;border-color:".$Couleur.";border-spacing:0;text-align:center;color:black;valign:top;font-weight:bold;\" bgcolor='".$Couleur."'>
					<table width='100%' height='100%'>
						<tr>
							<td style=\"width:30%;height:100%;text-align:center;color:black;valign:top;font-weight:bold;\" rowspan='2' bgcolor='".$CouleurLogo."'>
								<a style=\"text-decoration:none;\" onmouseover=\"this.style.color='#black';\" onmouseout=\"this.style.color='#black';\" href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/".$Lien."' >
								<img width='40px' src='../../Images/".$Image."' border='0' />
								</a>
							</td>
							<td width='70%' style='font-size:32px;".$couleurNombre."'>
								".$nb."
							</td>
						</tr>
						<tr>
							<td>
								".$Libelle."
							</td>
						</tr>
						<tr>
							<td colspan='2' style='color:red;'>
								".$Libelle2."
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>";
}
?>

<table style="width:100%; border-spacing:0; align:center;">
	<tr>
		<td height="20px" valign="center" align="right" style="font-weight:bold;font-size:15px;">
			Guide(s) : <a target='_blank' href='Guide_Utilisateur.pdf'>User Guide</a>
			<?php
			if(DroitsPlateforme(array($IdPosteResponsableMGX,$IdPosteInformatique))){echo "; <a target='_blank' href='Memo_MoyensGeneraux.pdf'>Memo MGX</a>";}
			if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsablePlateforme)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation) || DroitsFormationPrestation(array($IdPosteMagasinier))){echo "; <a target='_blank' href='Memo_Responsables.pdf'>Memo RESP</a>";}
			echo "&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;";
			if($LangueAffichage=="FR"){echo "Vous avez des questions, un problème ? Contactez-nous : ";}
			else{echo "Do you have questions or a problem? Contact us : ";}
			?>
			<span style="color:#00577c;">informatique.aaa@daher.com </span>&nbsp;&nbsp;&nbsp;
		</td>
	</tr>
	<tr bgcolor="#6EB4CD" >
		<td colspan="2" style="width:70px;height:30px;border-spacing:0;text-align:center;color:#00577c;valign:top;font-weight:bold;font-size:15px;">
			<?php if($LangueAffichage=="FR"){echo "SUIVI DU MATERIEL";}else{echo "MONITORING OF EQUIPMENT";}?>
		</td>
	</tr>
	<tr>
		<td width="100%">
			<table style="width:100%; border-spacing:0; align:center;">
				<tr>
					
					<td align="center" style="width:20%" valign="top">
						<table>
							<tr>
								<td>
									<?php
										if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteResponsablePlateforme)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation) || DroitsFormationPrestation(array($IdPosteMagasinier))){
											$nb=NombreTransfertOutilsECArrivee($_SESSION['Id_Personne'])."/".NombreTransfertOutilsECDepart($_SESSION['Id_Personne']);
											$nb7=NombreTransfertOutilsECPlus7Jour($_SESSION['Id_Personne']);
											$libelle7="";
											if($nb7>0){
												if($_SESSION["Langue"]=="FR"){
													$libelle7="<img width='15px' src='../../Images/attention.png' border='0' /> Plus de 7 jours : ".$nb7;
												}
												else{
													$libelle7="<img width='15px' src='../../Images/attention.png' border='0' />  More than 7 days : ".$nb7;
												}
												
											}
											if($_SESSION["Langue"]=="FR"){$libelle="Transferts en cours<br>Arrivées/Départs";}else{$libelle="Transfer in progress<br>Arrivals / Departures";}
											WidgetTDB($libelle,"RH/Transfert.png","#66e27d","#22b63d",$nb,$libelle7,"Outils/Tools/Liste_MouvementOutils.php");
										}
										$nb=NombreMaterielInventaire($_SESSION['Id_Personne']);
										$libelle7="";
										if($_SESSION["Langue"]=="FR"){$libelle="Inventaire à valider";}else{$libelle="Inventory to be validated";}
										WidgetTDB($libelle,"Formation/Evaluation.png","#c4b1d5","#8863ab",$nb,$libelle7,"Outils/Tools/Liste_Inventaire.php");
									?>
								</td>
							</tr>
						</table>
						
					</td>
					
					<td align="center" style="width:60%" valign="top">
						<table>
							<tr>
								<td>
								<?php	
								if($LangueAffichage=="FR"){$libelle="<br>Matériel";}else{$libelle="<br>Equipment";}
								Widget($libelle,"Outils/Tools/Liste_Materiel.php","CaisseOutils.png","#23b63e");
								
								if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteResponsablePlateforme)) || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation) || DroitsFormationPrestation(array($IdPosteMagasinier))){
									
									$DroitsFormationPlateforme=0;
									$ReqDroits= "
										SELECT
											Id
										FROM
											new_competences_personne_poste_plateforme
										WHERE
											Id_Plateforme=1
											AND Id_Personne=".$IdPersonneConnectee."
											AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteResponsablePlateforme.") 
										UNION ALL 
										
										SELECT
											Id
										FROM
											new_competences_personne_poste_prestation
										WHERE
											(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)=1
											AND Id_Personne=".$IdPersonneConnectee."
											AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.")
										";
									$ResultDroits=mysqli_query($bdd,$ReqDroits);
									$NbEnregDroits=mysqli_num_rows($ResultDroits);
									if($NbEnregDroits>0){$DroitsFormationPlateforme=1;}
									
									if($DroitsFormationPlateforme==1){
										$nb=NombreChangementMateriel($_SESSION['Id_Personne']);
										$libelleNb="";
										if($nb>0){
											if($LangueAffichage=="FR"){$libelleNb="<b><span style='color:#f22102'>".$nb."</span> changement(s) à valider</b>";}
											else{$libelleNb="<b><span style='color:#f22102'>".$nb."</span> change(s) to validate</b>";}
										}
										if($LangueAffichage=="FR"){$libelle="<br>Alerte changements !";}else{$libelle="<br>Alert changes";}
										Widget($libelle,"Outils/Tools/Liste_ChangementMateriel.php","attention.png","#feff19",$libelleNb);
									}
									
									$nb=NombreChangementPlateformeMateriel($_SESSION['Id_Personne']);
									$libelleNb="";
									if($nb>0){
										if($LangueAffichage=="FR"){$libelleNb="<b><span style='color:#f22102'>".$nb."</span> changement(s) à valider</b>";}
										else{$libelleNb="<b><span style='color:#f22102'>".$nb."</span> change(s) to validate</b>";}
									}
									
									if($LangueAffichage=="FR"){$libelle="Alerte sorties<br>plateforme !";}else{$libelle="Platfor<br>outputs alert";}
									Widget($libelle,"Outils/Tools/Liste_AlerteSortie.php","attention.png","#feff19",$libelleNb);
								}
								
								?>
								</td>
							</tr>
							<?php 
								if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX))){
									/*
							?>
							<tr>
								<td>
									<table>
										<tr>
											<td bgcolor="#23b63e" valign="center">
											<?php 
									
											$nb=NombreMaterielEtalonnageReparation($_SESSION['Id_Personne']);
											if($nb>0){
												echo "<img width='20px' src='../../Images/attention.png' border='0' />&nbsp;&nbsp; <b>";
												if($LangueAffichage=="FR"){echo $nb." matériels en étalonnage/réparation depuis plus de 1 mois";}
												else{echo $nb." materials in calibration / repair for more than 1 month";}
												echo "</b>&nbsp;&nbsp;";
											}
											?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<?php
							*/
							}
							?>
							<tr>
								<td>
								<?php
	
								if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteDirection))  || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation) || DroitsFormationPrestation(array($IdPosteMagasinier))){
									if($LangueAffichage=="FR"){$libelle="<br>Matériel perdu";}else{$libelle="<br>Lost material";}
									Widget($libelle,"Outils/Tools/Liste_MaterielPerdu.php","Formation/Jumelles.png","#f44040");
								}
								if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableQualite,$IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteDirection))  || DroitsFormationPrestation($TableauIdPostesResponsablesPrestation) || DroitsFormationPrestation(array($IdPosteMagasinier))){
									if($LangueAffichage=="FR"){$libelle="<br>Outils à l'étalonnage";}else{$libelle="<br>Calibration tools";}
									Widget($libelle,"Outils/Tools/Liste_OutilsEtalonnage.php","Etalonnage.png","#f6c784");
								}
								if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableQualite,$IdPosteResponsableMGX,$IdPosteInformatique))){
									
									if($LangueAffichage=="FR"){$libelle="<br>Déclarations de perte";}else{$libelle="<br>Loss declarations";}
									Widget($libelle,"Outils/Tools/Liste_DeclarationPerte.php","Recherche.png","#8af0b6");
								}
								
								if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){		
									if(DroitsPlateforme(array($IdPosteInformatique))){
										if($LangueAffichage=="FR"){$libelle="<br>Standard PC conforme";}else{$libelle="<br>Standard PC compliant";}
										Widget($libelle,"Outils/Tools/Liste_StandardPCConforme.php","Conforme.png","#3835ff");
									}
	
									if(DroitsPlateforme(array($IdPosteInformatique))){
										if($LangueAffichage=="FR"){$libelle="<br>Personnes sans matériel";}else{$libelle="<br>People without equipment";}
										Widget($libelle,"Outils/Tools/Liste_PersonneSansMateriel.php","RH/Absence.png","#51c9e9");
									}
									
									if(DroitsPlateforme(array($IdPosteInformatique))){
										if($LangueAffichage=="FR"){$libelle="<br>Nouveaux arrivants sans matériel";}else{$libelle="<br>New arrivals without equipment";}
										Widget($libelle,"Outils/Tools/Liste_NouveauxArrivants.php","RH/RH.png","#e387d0");
									}
									
									if($LangueAffichage=="FR"){$libelle="<br>Suivi / Turn over";}else{$libelle="<br>Follow-up / Turn over";}
									Widget($libelle,"Outils/Tools/TurnOver.php","RH/Contrat.png","#a988b2");
								}
								
								if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique,$IdPosteDirection))){
									if($LangueAffichage=="FR"){$libelle="<br>Indicateurs";}else{$libelle="<br>Indicators";}
									Widget($libelle,"Outils/Tools/TDB_Indicateurs.php","Formation/Graphique.png","#e779a4");
								}
								
								/*
								if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
									if($LangueAffichage=="FR"){$libelle="<br>Historique prestation";}else{$libelle="<br>Site history";}
									Widget($libelle,"Outils/Tools/HistoriquePrestation.php","Historique.png","#dbf0b5");
								}*/
								?>
								</td>
							</tr>
						</table>
					</td>
					<?php
					if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
					?>
					<td align="center" width="20%" valign="top">
						<table style='border-spacing:15px;display:inline-table;' >
							<tr>
								<td style="width:300px;border-style:outset; border-radius: 15px;height:90px;border-style:outset;border-color:#67cff1;border-spacing:0;color:black;valign:top;font-weight:bold;" bgcolor='#67cff1'>
									<table width='100%' height='100%'>	
										<tr>
											<td style="width:30%;height:100%;text-align:center;color:black;valign:top;font-weight:bold;">
												<img width='40px' src='../../Images/Formation/Parametrage.png' border='0' /><br>
											</td>
										</tr>
										<tr>
											<td>
												<table style="width:100%; align:left; valign:top;">
												<?php
													if($LangueAffichage=="FR"){Titre("Caisses \"types\"","Outils/Tools/Liste_CaisseType.php");}
													else{Titre("Boxes \"types\"","Outils/Tools/Liste_CaisseType.php");}
													
													if($LangueAffichage=="FR"){Titre("Fabricants","Outils/Tools/Liste_Tiers.php?Type=1");}
													else{Titre("Manufacturers","Outils/Tools/Liste_Tiers.php?Type=1");}
													
													if($LangueAffichage=="FR"){Titre("Familles de matériel","Outils/Tools/Liste_FamilleMateriel.php");}
													else{Titre("Material families","Outils/Tools/Liste_FamilleMateriel.php");}
													
													if($LangueAffichage=="FR"){Titre("Fournisseurs","Outils/Tools/Liste_Tiers.php?Type=2");}
													else{Titre("Suppliers","Outils/Tools/Liste_Tiers.php?Type=2");}
													
													if($LangueAffichage=="FR"){Titre("Laboratoires","Outils/Tools/Liste_Tiers.php?Type=3");}
													else{Titre("Laboratories","Outils/Tools/Liste_Tiers.php?Type=3");}
													
													if($LangueAffichage=="FR"){Titre("Lieux","Outils/Tools/Liste_Lieu.php");}
													else{Titre("Places","Outils/Tools/Liste_Lieu.php");}
													
													if($LangueAffichage=="FR"){Titre("Modèles de matériel","Outils/Tools/Liste_ModeleMateriel.php");}
													else{Titre("Equipment models","Outils/Tools/Liste_ModeleMateriel.php");}
												?>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						
					</td>
					<?php
					}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="50px"></td>
	</tr>
</table>
<?php
	mysqli_close($bdd);					// Fermeture de la connexion
?>
</body>
</html>
<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Tools - Matériel</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script language="javascript">
		function OuvreFenetreValider(Id,Id_Outil)
			{var w=window.open("Valider_MouvementOutils.php?Id="+Id+"&Id_Outil="+Id_Outil+"&CaisseMateriel=0","PageMouvement","status=no,menubar=no,width=50,height=50");
			w.focus();
			}
		function OuvreFenetreRefus(Id,Id_Outil){
			if(document.getElementById('Langue').value=="EN"){texte='Are you sure you want to refuse?';}
			else{texte='Etes-vous sûr de vouloir refuser ?';}
			if(window.confirm(texte)){
				var w=window.open("Refuser_MouvementOutils2.php?Id="+Id+"&Id_Outil="+Id_Outil+"&CaisseMateriel=0","PageMouvement","status=no,menubar=no,scrollbars=yes,width=800,height=300");
			}			
		}
	</script>
</head>
<body>

<?php
require("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");
require_once("../Formation/Globales_Fonctions.php");

if($_POST)
{
	if(isset($_POST['validerTransfert'])){
		//Récupération de la prestation actuelle de la personne
		$TableauPrestationPolePersonneConnectee=explode("_",PrestationPole_Personne($DateJour,$IdPersonneConnectee));
		$IdPrestationPersonneConnectee=$TableauPrestationPolePersonneConnectee[0];
		if($IdPrestationPersonneConnectee>0){
			$IdPolePersonneConnectee=$TableauPrestationPolePersonneConnectee[1];
		}
		else{
			$IdPolePersonneConnectee=0;
		}
		
		$Id_Prestation=0;
		$Id_Pole=0;
		$Id_Lieu=0;
		$Id_Personne=0;
		$Id_Caisse=0;
		if($_POST['affectation']=="site"){
			$tab=explode("_",$_POST['Id_PrestationPole']);
			$Id_Prestation=$tab[0];
			$Id_Pole=$tab[1];
			$Id_Lieu=$_POST['Id_Lieu'];
		}
		elseif($_POST['affectation']=="personne"){
			$Id_Personne=$_POST['Id_Personne'];
			if($Id_Personne>0){
				$TableauPrestationPolePersonneConnectee=explode("_",PrestationPole_Personne($DateJour,$Id_Personne));
				$Id_Prestation=$TableauPrestationPolePersonneConnectee[0];
				if($Id_Prestation==0){
					$TableauPrestationPolePersonneConnectee=explode("_",PrestationPoleCompetence_Personne($DateJour,$Id_Personne));
					$Id_Prestation=$TableauPrestationPolePersonneConnectee[0];
					$Id_Pole=$TableauPrestationPolePersonneConnectee[1];
				}
				else{
					$Id_Pole=$TableauPrestationPolePersonneConnectee[1];
				}
			}
		}
		elseif($_POST['affectation']=="caisse"){
			$Id_Caisse=$_POST['Id_Caisse'];
			$req="SELECT Id_Prestation, Id_Pole, Id_Personne 
				FROM tools_mouvement 
				WHERE tools_mouvement.TypeMouvement=0 
				AND tools_mouvement.EtatValidation<>-1 
				AND tools_mouvement.Suppr=0 
				AND tools_mouvement.Type=1 
				AND tools_mouvement.Id_Materiel__Id_Caisse=".$Id_Caisse."
				ORDER BY DateReception DESC, tools_mouvement.Id DESC
				";
			$ResultPrest=mysqli_query($bdd,$req);
			$NbPrest=mysqli_num_rows($ResultPrest);
			if($NbPrest>0){
				$RowPresta=mysqli_fetch_array($ResultPrest);
				$Id_Prestation=$RowPresta['Id_Prestation'];
				$Id_Pole=$RowPresta['Id_Pole'];
				$Id_Personne=$RowPresta['Id_Personne'];
			}
			
		}
		
		$req="SELECT 
			IF(Id_Caisse>0,
					(
						SELECT new_competences_prestation.Id_Plateforme
						FROM tools_mouvement AS TAB_Mouvement
						LEFT JOIN new_competences_prestation ON TAB_Mouvement.Id_Prestation=new_competences_prestation.Id
						WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=1 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
						AND TAB_Mouvement.DateReception<=tools_mouvement.DateReception
						ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
					)
				,
				(SELECT new_competences_prestation.Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)
				) AS Id_Plateforme
			FROM tools_mouvement
			WHERE Suppr=0
			AND Id_Materiel__Id_Caisse=".$_POST['Id']."
			AND Type=0
			AND TypeMouvement=0
			ORDER BY DateReception DESC, Id DESC
		";
		$Result=mysqli_query($bdd,$req);
		$NbEnreg=mysqli_num_rows($Result);
		
		$Id_Plateforme=0;
		if($NbEnreg>0)
		{
			$RowPlateforme=mysqli_fetch_array($Result);
			$Id_Plateforme=$RowPlateforme['Id_Plateforme'];
		}
		
		$Id_PlateformeNew=0;
		$req="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$Id_Prestation;
		$Result=mysqli_query($bdd,$req);
		$NbEnreg=mysqli_num_rows($Result);
		if($NbEnreg>0)
		{
			$RowPlateforme=mysqli_fetch_array($Result);
			$Id_PlateformeNew=$RowPlateforme['Id_Plateforme'];
		}
		
		$EtatValidation=1;
		$DateReception=$DateJour;
		$Id_Recepteur=$IdPersonneConnectee;
		$DatePriseEnCompteDemandeur=$DateJour;
		if($Id_Plateforme<>0){
			//Vérifier si la personne n'est pas MGX ou Informatique 
			$ReqDroits= "
				SELECT
					Id
				FROM
					new_competences_personne_poste_plateforme
				WHERE
					Id_Personne=".$IdPersonneConnectee."
					AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.")
					AND Id_Plateforme=".$Id_PlateformeNew." ";
			$ResultDroits=mysqli_query($bdd,$ReqDroits);
			$NbEnregDroits=mysqli_num_rows($ResultDroits);
			
			//Vérifier si prestation ayant le lieu "Magasin" ou "Magasin Paris" ou "Magasin Toulouse"
			$req="SELECT Id 
				FROM tools_lieu 
				WHERE Libelle IN ('Magasin','Magasin Paris','Magasin Toulouse')
				AND Id_Prestation=".$Id_Prestation."
				AND Id_Pole=".$Id_Pole."";
			$ResultLieu=mysqli_query($bdd,$req);
			$NbLieu=mysqli_num_rows($ResultLieu);
			
			
			//Vérifier si la personne n'est pas responsable de la prestation 
			$ReqDroits= "
				SELECT
					Id
				FROM
					new_competences_personne_poste_prestation
				WHERE
					Id_Personne=".$IdPersonneConnectee."
					AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.")
					AND Id_Prestation=".$Id_Prestation." 
					AND Id_Pole=".$Id_Pole." ";
			$ResultDroits=mysqli_query($bdd,$ReqDroits);
			$NbEnregDroits2=mysqli_num_rows($ResultDroits);
			
			if(($NbEnregDroits==0 || ($NbEnregDroits>0 && $NbLieu==0)) && $NbEnregDroits2==0){
				$EtatValidation=0;
				$Id_Recepteur=0;
				$DatePriseEnCompteDemandeur=date('0001-01-01');
			}
		}

		if($Id_Prestation>0 || $Id_Caisse>0){
			//Ajout du mouvement 
			$RequeteMouvement="
				INSERT INTO
					tools_mouvement
				(
					Type,
					TypeMouvement,
					Id_Materiel__Id_Caisse,
					Id_Prestation,
					Id_Pole,
					Id_Lieu,
					Id_Personne,
					Id_Caisse,
					Id_Demandeur,
					Id_PrestationDemandeur,
					Id_PoleDemandeur,
					Id_Recepteur,
					Id_PrestationRecepteur,
					Id_PoleRecepteur,
					DateDemande,
					DateReception,
					EtatValidation,
					Commentaire,
					Id_DemandeurPrisEnCompte,
					DatePriseEnCompteDemandeur
				)
				VALUES
				(
					'0',
					'0',
					'".$_POST['Id']."',
					'".$Id_Prestation."',
					'".$Id_Pole."',
					'".$Id_Lieu."',
					'".$Id_Personne."',
					'".$Id_Caisse."',
					'".$IdPersonneConnectee."',
					'".$IdPrestationPersonneConnectee."',
					'".$IdPolePersonneConnectee."',
					'".$Id_Recepteur."',
					'".$IdPrestationPersonneConnectee."',
					'".$IdPolePersonneConnectee."',
					'".$DateJour."',
					'".$DateReception."',
					'".$EtatValidation."',
					'".addslashes($_POST['Remarques'])."',
					'".$Id_Recepteur."',
					'".$DatePriseEnCompteDemandeur."'
				);";
			$ResultMouvement=mysqli_query($bdd,$RequeteMouvement);
			
			$oldId_Personne=0;
			$Id_FamilleMateriel=0;
			$NumTel=0;
			$NumCarteSim="";
			$NumIMEI="";
			//Récupérer l'ancienne affectation 
			$req="SELECT Id_PersonneT, 
				(SELECT Id_FamilleMateriel FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) AS Id_FamilleMateriel,
				NumTelephone,NumSIM,NumIMEI
				FROM tools_materiel WHERE Id=".$_POST['Id']." ";
			$ResultSelect=mysqli_query($bdd,$req);
			$NbSelect=mysqli_num_rows($ResultSelect);
			
			if($NbSelect>0){
				$RowSelect=mysqli_fetch_array($ResultSelect);
				$oldId_Personne=$RowSelect['Id_PersonneT'];
				$Id_FamilleMateriel=$RowSelect['Id_FamilleMateriel'];
				$NumTel=$RowSelect['NumTelephone'];
				$NumCarteSim=$RowSelect['NumSIM'];
				$NumIMEI=$RowSelect['NumIMEI'];
			}
			
			//Mettre à jour l'affectation dans matériel
			$req="UPDATE tools_materiel 
				SET Id_PrestationT=".$Id_Prestation.", Id_PoleT=".$Id_Pole.", Id_LieuT=".$Id_Lieu.", Id_PersonneT=".$Id_Personne.", 
					Id_CaisseT=".$Id_Caisse.", DateReceptionT='".$DateReception."', EtatValidationT=".$EtatValidation.", CommentaireT='".addslashes($_POST['Remarques'])."' 
				WHERE Id=".$_POST['Id']." ";
			$ResultUpdt=mysqli_query($bdd,$req);
			
			//Envoyer un mail si le matériel contient une carte SIM
			if(($Id_FamilleMateriel==79 || $Id_FamilleMateriel==3 || $Id_FamilleMateriel==4) && $NumTel>0){
				if($oldId_Personne<>$Id_Personne){
					$Headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
					$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
					
					$LibellePresta="";
					$LibelleUER="";
					$LibellePole="";
					
					$req="SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS UER, LEFT(Libelle,7) AS Libelle FROM new_competences_prestation WHERE Id=".$Id_Prestation." ";
					$ResultPresta=mysqli_query($bdd,$req);
					$NbPresta=mysqli_num_rows($ResultPresta);
					if($NbPresta>0){
						$Row=mysqli_fetch_array($ResultPresta);
						$LibellePresta=$Row['Libelle'];
						$LibelleUER=$Row['UER'];
					}
					
					$req="SELECT Libelle FROM new_competences_pole WHERE Id=".$Id_Pole." ";
					$ResultPole=mysqli_query($bdd,$req);
					$NbPole=mysqli_num_rows($ResultPole);
					if($NbPole>0){
						$Row=mysqli_fetch_array($ResultPole);
						$LibellePole=$Row['Libelle'];
					}
					
					$LibellePersonne="";
					$req="SELECT CONCAT(Nom,' ',Prenom) AS Personne FROM new_rh_etatcivil WHERE Id=".$Id_Personne." ";
					$ResultPers=mysqli_query($bdd,$req);
					$NbPers=mysqli_num_rows($ResultPers);
					if($NbPers>0){
						$Row=mysqli_fetch_array($ResultPers);
						$LibellePersonne=$Row['Personne'];
					}
					
					$Objet="Nouvelle affectation carte SIM ";
					$MessageMail="
						<html>
							<head><title>Nouvelle affectation carte SIM </title></head>
							<body>
								Bonjour,
								<br><br>
								N° de téléphone : 0".$NumTel." <br>
								Personne : ".$LibellePersonne." <br>
								UER : ".$LibelleUER." <br>
								Prestation : ".$LibellePresta." ".$LibellePole."<br>
								N° carte SIM : ".$NumCarteSim."<br>
								Code IMEI du téléphone : ".$NumIMEI."<br>
							</body>
						</html>";
					$Emails="mlebacon@aaa-aero.com,ssavy@aaa-aero.com,ROUAHLIMA@aaa-aero.com";
					mail($Emails,$Objet,$MessageMail,$Headers,'-f informatique@aaa-aero.com');
				}
			}
		}
		
		if($_POST['affectation']=="personne"){
			if($Id_Personne>0){
				//Editer le document de pret du matériel 
				echo "<script>window.open('EditerPretMateriel.php?laDate=".date('Y-m-d')."&Id=".$Id_Personne."','Fiche_PretMateriel','status=no,menubar=no,width=20,height=20');</script>";
			}
		}
		echo "<script>Recharger('".$_POST['Page']."');</script>";
	}

}

		$Requete="
				SELECT
					NumAAA
				FROM
					tools_materiel
				WHERE
					Id='".$_GET['Id']."';";
			$Result=mysqli_query($bdd,$Requete);
			$Row=mysqli_fetch_array($Result);
			
			
		$req="SELECT 
				(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) AS Id_Plateforme,
				Id_Prestation,
				Id_Pole,
				Id_Caisse,
				Id_Personne,
				Id_Lieu
				FROM tools_mouvement
				WHERE Suppr=0
				AND Id_Materiel__Id_Caisse=".$_GET['Id']."
				AND Type=0
				AND TypeMouvement=0
				ORDER BY DateReception DESC, Id DESC
			";
			$Result2=mysqli_query($bdd,$req);
			$NbEnreg2=mysqli_num_rows($Result2);
			$Row2=mysqli_fetch_array($Result2);
?>
		<form id="formulaire" method="POST" action="" onSubmit="return VerifChamps();">
		<input type="hidden" name="Id" value="<?php echo $_GET['Id']; ?>">
		<input type="hidden" name="Langue" id="Langue" value="<?php echo $_SESSION['Langue']; ?>">
		<input type="hidden" name="Page" name="Page" value="<?php if(isset($_GET['Page'])){echo $_GET['Page'];} ?>">
		<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#feff19;">
			<tr>
				<td class="TitrePage">
				<?php
				if($LangueAffichage=="FR"){echo "Transfert de matériel ".$Row['NumAAA'];}else{echo "Material transfer ".$Row['NumAAA'];}
				?>
				</td>
			</tr>
		</table><br>
		<table style="width:100%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td style="color:#22b63d" class="Libelle"  align="center" colspan="4"><?php if($LangueAffichage=="FR"){echo "AJOUT";}else{echo "ADD";} ?></td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle" colspan="2">
					<input type="radio" name="affectation" onchange="AfficherAffectation('site')" value="site" checked><?php if($LangueAffichage=="FR"){echo "Site";}else{echo "Site";}?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="affectation" onchange="AfficherAffectation('personne')" value="personne"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="affectation" onchange="AfficherAffectation('caisse')" value="caisse"><?php if($LangueAffichage=="FR"){echo "Caisse";}else{echo "Box";}?>
				</td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<tr class="trPrestation">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?></td>
				<td>
				<?php $Id_PrestaPole="0_0";
				if($Row2['Id_Prestation']>0){$Id_PrestaPole=$Row2['Id_Prestation']."_".$Row2['Id_Pole'];}
				?>
					<select name="Id_Plateforme" id="Id_Plateforme" style="width:200px" onchange="RechargerPrestation()">
					<option value="0"></option>
						<?php
							$Id_Plateforme=$_SESSION['FiltreToolsSuivi_Plateforme'];
							if($Row2['Id_Plateforme']>0){$Id_Plateforme=$Row2['Id_Plateforme'];}
							if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
								$requetePlat="SELECT DISTINCT Id_Plateforme AS Id,
									(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle
									FROM 
									(SELECT DISTINCT Id_Plateforme
									FROM new_competences_prestation
									WHERE Active=0
									AND Id NOT IN (
										SELECT Id_Prestation
										FROM new_competences_pole    
										WHERE Actif=0
									)
									
									UNION 
									
									SELECT DISTINCT new_competences_prestation.Id_Plateforme
										FROM new_competences_pole
										INNER JOIN new_competences_prestation
										ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
										AND Active=0
										AND Actif=0) AS TAB
									WHERE Id_Plateforme NOT IN (11,14)
									ORDER BY Libelle ASC";
							}
							else{
								$requetePlat="SELECT Id, Libelle
									FROM new_competences_plateforme
									WHERE Id IN (SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) 
										FROM new_competences_personne_poste_prestation 
										WHERE 
										Id_Personne=".$IdPersonneConnectee."
										AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.") )
									OR Id IN (SELECT Id_Plateforme
										FROM new_competences_personne_poste_plateforme
										WHERE 
										Id_Personne=".$IdPersonneConnectee."
										AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteControleGestion.") )
									ORDER BY Libelle";
							}
							
							$resultsPlat=mysqli_query($bdd,$requetePlat);
							while($rowPlat=mysqli_fetch_array($resultsPlat))
							{
								$selected="";
								if($Id_Plateforme==0){$Id_Plateforme=$rowPlat['Id'];}
								if($Id_Plateforme==$rowPlat['Id']){$selected="selected";}
								echo "<option value='".$rowPlat['Id']."' ".$selected.">";
								echo str_replace("'"," ",stripslashes($rowPlat['Libelle']))."</option>\n";
							}
						?>
					</select>
				</td>
			</tr>
			<tr class="trPrestation"><td height="4"></td></tr>
			<tr class="trPrestation">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Prestation :";}else{echo "Site :";} ?></td>
				<td>
					<select name="Id_PrestationPole" id="Id_PrestationPole" class="Id_PrestationPole" style="width:300px" onchange="RechargerLieu()">
						<?php
							$requeteSite="SELECT DISTINCT Id, Libelle, '' AS LibellePole, 0 AS Id_Pole, Id_Plateforme
								FROM new_competences_prestation
								WHERE Active=0
								AND Id NOT IN (
									SELECT Id_Prestation
									FROM new_competences_pole    
									WHERE Actif=0
								)
								
								UNION 
								
								SELECT DISTINCT new_competences_pole.Id_Prestation, new_competences_prestation.Libelle,
									new_competences_pole.Libelle AS LibellePole, new_competences_pole.Id AS Id_Pole, new_competences_prestation.Id_Plateforme
									FROM new_competences_pole
									INNER JOIN new_competences_prestation
									ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
									AND Active=0
									AND Actif=0
									
								ORDER BY Libelle, LibellePole";
							$resultsite=mysqli_query($bdd,$requeteSite);
							$i=0;
							
							while($rowsite=mysqli_fetch_array($resultsite))
							{
								$selected="";
								if($Id_PrestaPole==$rowsite['Id']."_".$rowsite['Id_Pole']){$selected="selected";}
								echo "<option value='".$rowsite['Id']."_".$rowsite['Id_Pole']."' ".$selected.">";
								$pole="";
								if($rowsite['Id_Pole']>0){$pole=" - ".$rowsite['LibellePole'];}
								echo str_replace("'"," ",stripslashes($rowsite['Libelle'].$pole))."</option>\n";
								echo "<script>Liste_PrestaPole[".$i."] = new Array(".$rowsite['Id'].",".$rowsite['Id_Pole'].",'".str_replace("'"," ",$rowsite['Libelle'])."','".str_replace("'"," ",$rowsite['LibellePole'])."',".$rowsite['Id_Plateforme'].");</script>";
								$i++;
							}
						?>
					</select>
				</td>
			</tr>
			<tr class="trPrestation"><td height="4"></td></tr>
			<tr class="trPrestation">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Lieu :";}else{echo "Place :";} ?></td>
				<td>
					<select name="Id_Lieu" id="Id_Lieu" class="Id_Lieu" style="width:200px">
						<?php
							$requeteLieu="SELECT Id, Id_Prestation, Id_Pole, Libelle
								FROM tools_lieu
								WHERE Suppr=0
								ORDER BY Libelle ";
							$resultlieu=mysqli_query($bdd,$requeteLieu);
							$i=0;
							$Id_Lieu=0;
							if($Row2['Id_Lieu']>0){$Id_Lieu=$Row2['Id_Lieu'];}
							while($rowLieu=mysqli_fetch_array($resultlieu))
							{
								echo "<script>Liste_Lieu[".$i."] = new Array(".$rowLieu['Id'].",".$rowLieu['Id_Prestation'].",'".str_replace("'"," ",$rowLieu['Id_Pole'])."','".str_replace("'"," ",$rowLieu['Libelle'])."');</script>";
								$i++;
							}
						?>
					</select>
				</td>
			</tr>
			<?php
				if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
			?>
			<tr class="trPersonne">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?></td>
				<td>
					<select name="Id_PlateformePersonne" id="Id_PlateformePersonne" style="width:200px" onchange="RechargerPersonne()">
					<option value="0"></option>
						<?php
							$Id_PlateformePersonne=$_SESSION['FiltreToolsSuivi_Plateforme'];
							if($Row2['Id_Plateforme']>0){$Id_PlateformePersonne=$Row2['Id_Plateforme'];}
							$requetePlat="SELECT Id_Plateforme AS Id,
								(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle
								FROM (
								SELECT DISTINCT 
								IF((
									SELECT COUNT(rh_personne_mouvement.Id)
									FROM rh_personne_mouvement
									WHERE Suppr=0
									AND Id_Personne=new_rh_etatcivil.Id 
									AND EtatValidation=1
									AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
									AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
								)>0,
								(
									SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)
									FROM rh_personne_mouvement
									WHERE Suppr=0
									AND Id_Personne=new_rh_etatcivil.Id 
									AND EtatValidation=1
									AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
									AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
									LIMIT 1
								)
								,
								(
									SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)
									FROM new_competences_personne_prestation
									WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
									AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation)<>1
									AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
									AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
									LIMIT 1
								)) AS Id_Plateforme
								FROM new_rh_etatcivil 
								WHERE (
									SELECT COUNT(rh_personne_mouvement.Id)
									FROM rh_personne_mouvement
									WHERE Suppr=0
									AND Id_Personne=new_rh_etatcivil.Id 
									AND EtatValidation=1
									AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
									AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
								)>0
								OR
								(
									SELECT COUNT(new_competences_personne_prestation.Id)
									FROM new_competences_personne_prestation
									WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
									AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation)<>1
									AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
									AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
								)>0) AS TAB
								WHERE Id_Plateforme NOT IN (11,14)
								ORDER BY Libelle ASC";

							$resultsPlat=mysqli_query($bdd,$requetePlat);
							while($rowPlat=mysqli_fetch_array($resultsPlat))
							{
								$selected="";
								if($Id_PlateformePersonne==0){$Id_PlateformePersonne=$rowPlat['Id'];}
								if($Id_PlateformePersonne==$rowPlat['Id']){$selected="selected";}
								echo "<option value='".$rowPlat['Id']."' ".$selected.">";
								echo str_replace("'"," ",stripslashes($rowPlat['Libelle']))."</option>\n";
							}
						?>
					</select>
				</td>
			</tr>
			<tr class="trPersonne"><td height="4"></td></tr>
			<?php
				}
			?>
			
			<tr class="trPersonne">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "Person :";} ?></td>
				<td>
					<select name="Id_Personne" id="Id_Personne">
					<?php
					if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
						$rq="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
							IF((
								SELECT COUNT(rh_personne_mouvement.Id)
								FROM rh_personne_mouvement
								WHERE Suppr=0
								AND Id_Personne=new_rh_etatcivil.Id 
								AND EtatValidation=1
								AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
								AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
							)>0,
							(
								SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)
								FROM rh_personne_mouvement
								WHERE Suppr=0
								AND Id_Personne=new_rh_etatcivil.Id 
								AND EtatValidation=1
								AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
								AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
								LIMIT 1
							)
							,
							(
								SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)
								FROM new_competences_personne_prestation
								WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
								AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation)<>1
								AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
								AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
								LIMIT 1
							)) AS Id_Plateforme
							FROM new_rh_etatcivil
							WHERE (
								SELECT COUNT(rh_personne_mouvement.Id)
								FROM rh_personne_mouvement
								WHERE Suppr=0
								AND Id_Personne=new_rh_etatcivil.Id 
								AND EtatValidation=1
								AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
								AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
							)>0
							OR
							(
								SELECT COUNT(new_competences_personne_prestation.Id)
								FROM new_competences_personne_prestation
								WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
								AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation)<>1
								AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
								AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
							)>0
							ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
						}
					else{
						$rq="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
							IF((
								SELECT COUNT(rh_personne_mouvement.Id)
								FROM rh_personne_mouvement
								WHERE Suppr=0
								AND Id_Personne=new_rh_etatcivil.Id 
								AND EtatValidation=1
								AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
								AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
							)>0,
							(
								SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)
								FROM rh_personne_mouvement
								WHERE Suppr=0
								AND Id_Personne=new_rh_etatcivil.Id 
								AND EtatValidation=1
								AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
								AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
								LIMIT 1
							)
							,
							(
								SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)
								FROM new_competences_personne_prestation
								WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
								AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation)<>1
								AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
								AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
								LIMIT 1
							)) AS Id_Plateforme
							FROM new_rh_etatcivil
							WHERE (
								SELECT COUNT(rh_personne_mouvement.Id)
								FROM rh_personne_mouvement
								LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=rh_personne_mouvement.Id_Prestation
								WHERE Suppr=0
								AND Id_Personne=new_rh_etatcivil.Id 
								AND EtatValidation=1
								AND rh_personne_mouvement.DateDebut<='".date('Y-m-d')."'
								AND (rh_personne_mouvement.DateFin<='0001-01-01' OR rh_personne_mouvement.DateFin>='".date('Y-m-d')."')
								AND new_competences_prestation.Id_Plateforme IN 
									(
										SELECT new_competences_prestation.Id_Plateforme 
										FROM new_competences_personne_poste_prestation
										LEFT JOIN new_competences_prestation ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id									
										WHERE Id_Personne=".$IdPersonneConnectee."
										AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.")
										
										UNION ALL 
										SELECT Id_Plateforme
											FROM new_competences_personne_poste_plateforme
											WHERE Id_Personne=".$IdPersonneConnectee."
											AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteControleGestion.") 
									)
							)>0
							OR
							(
								SELECT COUNT(new_competences_personne_prestation.Id)
								FROM new_competences_personne_prestation
								LEFT JOIN new_competences_prestation ON new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation
								WHERE new_competences_personne_prestation.Id_Personne=new_rh_etatcivil.Id 
								AND (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_prestation.Id_Prestation)<>1
								AND new_competences_personne_prestation.Date_Debut<='".date('Y-m-d')."'
								AND (new_competences_personne_prestation.Date_Fin<='0001-01-01' OR  new_competences_personne_prestation.Date_Fin>='".date('Y-m-d')."')
								AND new_competences_prestation.Id_Plateforme IN 
									(
										SELECT new_competences_prestation.Id_Plateforme 
										FROM new_competences_personne_poste_prestation
										LEFT JOIN new_competences_prestation ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id									
										WHERE Id_Personne=".$IdPersonneConnectee."
										AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.")
										
										UNION ALL 
										SELECT Id_Plateforme
											FROM new_competences_personne_poste_plateforme
											WHERE Id_Personne=".$IdPersonneConnectee."
											AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteControleGestion.") 
									)
							)>0
							ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
					}
					$resultpersonne=mysqli_query($bdd,$rq);
					$Id_Personne=0;
					if($Row2['Id_Personne']>0){$Id_Personne=$Row2['Id_Personne'];}
					$i=0;
					while($rowpersonne=mysqli_fetch_array($resultpersonne))
					{
						$selected="";
						if($Id_Personne==0){$Id_Personne=$rowpersonne['Id'];$selected = "selected";}
						echo "<option value='".$rowpersonne['Id']."' ".$selected.">".str_replace("'"," ",$rowpersonne['Personne'])."</option>\n";
						echo "<script>Liste_Personne[".$i."] = new Array(".$rowpersonne['Id'].",'".str_replace("'"," ",$rowpersonne['Personne'])."',".$rowpersonne['Id_Plateforme'].");</script>";
						$i++;
					}
					?>
					</select>
				</td>
			</tr>
			<tr class="trCaisse">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?></td>
				<td>
					<select name="Id_PlateformeCaisse" id="Id_PlateformeCaisse" style="width:200px" onchange="RechargerCaisse()">
					<option value="0"></option>
						<?php
							$Id_PlateformeCaisse=$_SESSION['FiltreToolsSuivi_Plateforme'];
							if($Row2['Id_Plateforme']>0){$Id_PlateformeCaisse=$Row2['Id_Plateforme'];}
							if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
								$requetePlat="SELECT Id, Libelle
									FROM new_competences_plateforme
									WHERE Id NOT IN (11,14)
									ORDER BY Libelle";
							}
							else{
								$requetePlat="SELECT Id, Libelle
									FROM new_competences_plateforme
									WHERE Id IN (SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) 
										FROM new_competences_personne_poste_prestation 
										WHERE 
										Id_Personne=".$IdPersonneConnectee."
										AND Id_Poste IN (".$IdPosteMagasinier.",".$IdPosteChefEquipe.",".$IdPosteCoordinateurEquipe.",".$IdPosteCoordinateurProjet.",".$IdPosteResponsableProjet.",".$IdPosteResponsableOperation.") )
									OR Id IN (SELECT Id_Plateforme
										FROM new_competences_personne_poste_plateforme
										WHERE 
										Id_Personne=".$IdPersonneConnectee."
										AND Id_Poste IN (".$IdPosteResponsablePlateforme.",".$IdPosteControleGestion.") )
									ORDER BY Libelle";
							}

							$resultsPlat=mysqli_query($bdd,$requetePlat);
							while($rowPlat=mysqli_fetch_array($resultsPlat))
							{
								$selected="";
								if($Id_PlateformeCaisse==0){$Id_PlateformeCaisse=$rowPlat['Id'];}
								if($Id_PlateformeCaisse==$rowPlat['Id']){$selected="selected";}
								echo "<option value='".$rowPlat['Id']."' ".$selected.">";
								echo str_replace("'"," ",stripslashes($rowPlat['Libelle']))."</option>\n";
							}
						?>
					</select>
				</td>
			</tr>
			<tr class="trCaisse"><td height="4"></td></tr>
			<tr class="trCaisse">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Caisse :";}else{echo "Box :";} ?></td>
				<td>
					<select name="Id_Caisse" id="Id_Caisse">
					<?php
					$rq="SELECT Id, Num, 
						(SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType) AS CaisseType,
						(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (1) AND Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) AS Id_Plateforme
						FROM tools_caisse 
						WHERE Suppr=0
						ORDER BY tools_caisse.Num ASC";
					$resultcaisse=mysqli_query($bdd,$rq);
					$Id_Caisse=0;
					if($Row2['Id_Caisse']>0){$Id_Caisse=$Row2['Id_Caisse'];}
					$i=0;
					while($rowCaisse=mysqli_fetch_array($resultcaisse))
					{
						$selected="";
						if($Id_Caisse==0){$Id_Caisse=$rowCaisse['Id'];$selected = "selected";}
						echo "<option value='".$rowCaisse['Id']."' ".$selected.">".$rowCaisse['Num']." ".str_replace("'"," ",$rowCaisse['CaisseType'])."</option>\n";
						echo "<script>Liste_Caisse[".$i."] = new Array(".$rowCaisse['Id'].",'".str_replace("'"," ",$rowCaisse['CaisseType'])."','".$rowCaisse['Num']."',".$rowCaisse['Id_Plateforme'].");</script>";
						$i++;
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Remarque :";}else{echo "Note :";} ?></td>
				<td>
					<textarea name="Remarques" rows="5" cols="50" style="resize: none;"></textarea>
				</td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<tr>
				<td colspan=2 align="center">
					<?php 
						$req="SELECT 
							tools_mouvement.Id
							FROM tools_mouvement
							WHERE Suppr=0
							AND Id_Materiel__Id_Caisse=".$_GET['Id']."
							AND Type=0
							AND TypeMouvement=0
							AND EtatValidation=0
							
							UNION ALL
							
							SELECT 
							tools_mouvement.Id
							FROM tools_mouvement
							WHERE Suppr=0
							AND Id_Materiel__Id_Caisse = (SELECT 
								Id_Caisse
								FROM tools_mouvement AS TAB
								WHERE TAB.Suppr=0
								AND TAB.Id_Materiel__Id_Caisse=".$_GET['Id']."
								AND TAB.Type=0
								AND TAB.TypeMouvement=0
								AND TAB.EtatValidation=1
								ORDER BY TAB.DateReception DESC, TAB.Id DESC LIMIT 1
								)
							AND Type=1
							AND TypeMouvement=0
							AND EtatValidation=0
						";

						$Result=mysqli_query($bdd,$req);
						$NbEnreg=mysqli_num_rows($Result);
						if($NbEnreg==0){
						
					?>
					<input class="Bouton" name="validerTransfert" type="submit"
					<?php
						if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
					?>
					>
						<?php }?>
				</td>
			</tr>
		</table><br>
		<table style="width:100%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td style="color:#22b63d" class="Libelle" align="center" colspan="7"><?php if($LangueAffichage=="FR"){echo "HISTORIQUE";}else{echo "HISTORICAL";} ?></td>
			</tr>
			<tr>
				<td height="7"></td>
			</tr>
			<tr>
				<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Date";}else{echo "Date";}?></td>
				<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Plateform";}?></td>
				<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?></td>
				<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Lieu";}else{echo "Place";}?></td>
				<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Caisse";}else{echo "Toolbox";}?></td>
				<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?></td>
				<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Remarque";}else{echo "Note";}?></td>
				<td class="EnTeteTableauCompetences"></td>
				<td class="EnTeteTableauCompetences"></td>
			</tr>
			<?php
				$req="SELECT 
					DateReception,Commentaire,Id,(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Recepteur) AS Valideur,Id_Recepteur,
					DateDemande,
					Id_Caisse,
					IF(Id_Caisse>0,
							(
								SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=new_competences_prestation.Id_Plateforme)
								FROM tools_mouvement AS TAB_Mouvement
								LEFT JOIN new_competences_prestation ON TAB_Mouvement.Id_Prestation=new_competences_prestation.Id
								WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=1 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
								AND TAB_Mouvement.DateReception<=tools_mouvement.DateReception
								ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
							)
						,
						(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=new_competences_prestation.Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation)
						) AS Plateforme,
					IF(Id_Caisse>0,
							(
								SELECT new_competences_prestation.Id_Plateforme
								FROM tools_mouvement AS TAB_Mouvement
								LEFT JOIN new_competences_prestation ON TAB_Mouvement.Id_Prestation=new_competences_prestation.Id
								WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=1 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
								AND TAB_Mouvement.DateReception<=tools_mouvement.DateReception
								ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
							)
						,
						(SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation)
						) AS Id_Plateforme,
					IF(Id_Caisse>0,
							(
								SELECT new_competences_prestation.Libelle
								FROM tools_mouvement AS TAB_Mouvement
								LEFT JOIN new_competences_prestation ON TAB_Mouvement.Id_Prestation=new_competences_prestation.Id
								WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=1 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
								AND TAB_Mouvement.DateReception<=tools_mouvement.DateReception
								ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
							)
						,
						(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation)
						) AS Prestation,
					IF(Id_Caisse>0,
							(
								SELECT Id_Prestation
								FROM tools_mouvement AS TAB_Mouvement
								WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=1 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
								AND TAB_Mouvement.DateReception<=tools_mouvement.DateReception
								ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
							)
						,
						Id_Prestation
						) AS Id_Prestation,
					EtatValidation AS TransfertEC,
					IF(Id_Caisse>0,
						(
							SELECT new_competences_pole.Libelle
							FROM tools_mouvement AS TAB_Mouvement
							LEFT JOIN new_competences_pole ON TAB_Mouvement.Id_Pole=new_competences_pole.Id
							WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=1 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
							AND TAB_Mouvement.DateReception<=tools_mouvement.DateReception
							ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
						)
					,
					(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole)
					) AS Pole,
					IF(Id_Caisse>0,
						(
							SELECT Id_Pole
							FROM tools_mouvement AS TAB_Mouvement
							WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=1 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
							AND TAB_Mouvement.DateReception<=tools_mouvement.DateReception
							ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
						)
					,
					Id_Pole
					) AS Id_Pole,
					IF(Id_Caisse>0,
						(
							SELECT tools_lieu.Libelle
							FROM tools_mouvement AS TAB_Mouvement
							LEFT JOIN tools_lieu ON TAB_Mouvement.Id_Lieu=tools_lieu.Id
							WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=1 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
							AND TAB_Mouvement.DateReception<=tools_mouvement.DateReception
							ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
						)
					,
					(SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu)) AS Lieu,
					(SELECT Num FROM tools_caisse WHERE Id=Id_Caisse) AS NumCaisse,
					(SELECT Libelle FROM tools_caissetype WHERE Id=(SELECT Id_CaisseType FROM tools_caisse WHERE Id=Id_Caisse)) AS CaisseType,
					IF(Id_Caisse>0,
						(
							SELECT CONCAT(Nom,' ',Prenom)
							FROM tools_mouvement AS TAB_Mouvement
							LEFT JOIN new_rh_etatcivil ON TAB_Mouvement.Id_Personne=new_rh_etatcivil.Id
							WHERE TAB_Mouvement.TypeMouvement=0 AND TAB_Mouvement.Suppr=0 AND TAB_Mouvement.Type=1 AND TAB_Mouvement.Id_Materiel__Id_Caisse=tools_mouvement.Id_Caisse
							AND TAB_Mouvement.DateReception<=tools_mouvement.DateReception
							ORDER BY TAB_Mouvement.DateReception DESC, TAB_Mouvement.Id DESC LIMIT 1
						)
					,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne)) AS Personne 
					FROM tools_mouvement
					WHERE Suppr=0
					AND Id_Materiel__Id_Caisse=".$_GET['Id']."
					AND Type=0
					AND TypeMouvement=0
					AND EtatValidation<>-1
					ORDER BY DateReception DESC, Id DESC
				";
				$Result=mysqli_query($bdd,$req);
				$NbEnreg=mysqli_num_rows($Result);
				if($NbEnreg>0)
				{
				$Couleur="#EEEEEE";
				$dateReception="";
				while($Row=mysqli_fetch_array($Result))
				{
					if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
					else{$Couleur="#EEEEEE";}

					if($Row['Id_Caisse']>0){
						
						$req="SELECT 
							DateReception,EtatValidation AS TransfertEC,(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Demandeur) AS Demandeur,
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Recepteur) AS Valideur,Id_Recepteur,
							(SELECT (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) FROM new_competences_prestation WHERE Id=Id_Prestation) AS Plateforme,
							(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
							(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,
							(SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu) AS Lieu,DateDemande,
							(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne 
							FROM tools_mouvement
							WHERE Suppr=0
							AND Id_Materiel__Id_Caisse=".$Row['Id_Caisse']."
							AND Type=1
							AND TypeMouvement=0
							AND EtatValidation<>-1
							AND DateReception>'".$Row['DateReception']."' ";
						if($dateReception<>""){
							$req.="AND DateReception<='".$dateReception."' ";
						}
						$req.="ORDER BY DateReception DESC, Id DESC
							LIMIT 1
						";

						$CouleurCaisse="#f2ddfd";
						$ResultCaisse=mysqli_query($bdd,$req);
						while($RowCaisse=mysqli_fetch_array($ResultCaisse))
						{
							if($CouleurCaisse=="#f2ddfd"){$CouleurCaisse="#cb79f9";}
							else{$CouleurCaisse="#f2ddfd";}
							$LIBELLE_POLE="";
							if($RowCaisse['Pole']<>""){$LIBELLE_POLE=" - ".$RowCaisse['Pole'];}
							?>
								<tr bgcolor="<?php echo $CouleurCaisse;?>">
									<td <?php if($RowCaisse['TransfertEC']==0){echo "id='leHover'";} ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php if($RowCaisse['TransfertEC']==0){echo "<img width='15px' src='../../Images/attention.png' border='0' />&nbsp;<span>Transfert en cours</span>";} ?><i><?php echo AfficheDateJJ_MM_AAAA($RowCaisse['DateReception']);?></i></td>
									<td <?php echo "id='leHover'"; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<i><?php echo stripslashes($RowCaisse['Plateforme']);?></i>
										<?php 
											echo "<span>Demandeur : ".$RowCaisse['Demandeur']."<br>Date demande : ".AfficheDateJJ_MM_AAAA($RowCaisse['DateDemande']);
											if($RowCaisse['Id_Recepteur']>0){
												echo "<br>Validé par : ".$RowCaisse['Valideur']."<br>Validé le : ".AfficheDateJJ_MM_AAAA($RowCaisse['DateReception']); 
											}
											echo "</span>";
										?>
									</td>
									<td>&nbsp;&nbsp;&nbsp;&nbsp;<i><?php echo stripslashes(substr($RowCaisse['Prestation'],0,7).$LIBELLE_POLE);?></i></td>
									<td>&nbsp;&nbsp;&nbsp;&nbsp;<i><?php echo stripslashes($RowCaisse['Lieu']);?></i></td>
									<td>&nbsp;&nbsp;&nbsp;&nbsp;<i><?php if($Row['CaisseType']<>""){echo stripslashes($Row['CaisseType']." n° ".$Row['NumCaisse']);}?></i></td>
									<td>&nbsp;&nbsp;&nbsp;&nbsp;<i><?php echo stripslashes($RowCaisse['Personne']);?></i></td>
									<td></td>
									<td></td>
								</tr>
							<?php
						}
					}
					$LIBELLE_POLE="";
					if($Row['Pole']<>""){$LIBELLE_POLE=" - ".$Row['Pole'];}
					?>
						<tr bgcolor="<?php echo $Couleur;?>">
							<td <?php if($Row['TransfertEC']==0){echo "id='leHover'";} ?>><?php if($Row['TransfertEC']==0){echo "<img width='15px' src='../../Images/attention.png' border='0' />&nbsp;<span>Transfert en cours</span>";} ?><?php echo AfficheDateJJ_MM_AAAA($Row['DateReception']);?></td>
							<td <?php echo "id='leHover'"; ?>><?php echo stripslashes($Row['Plateforme']);?>
								<?php 
									echo "<span>Demandeur : ".$Row['Demandeur']."<br>Date demande : ".AfficheDateJJ_MM_AAAA($Row['DateDemande']);
									if($Row['Id_Recepteur']>0){
										echo "<br>Validé par : ".$Row['Valideur']."<br>Validé le : ".AfficheDateJJ_MM_AAAA($Row['DateReception']); 
									}
									echo "</span>";
								?>
							</td>
							<td><?php echo stripslashes(substr($Row['Prestation'],0,7).$LIBELLE_POLE);?></td>
							<td><?php echo stripslashes($Row['Lieu']);?></td>
							<td><?php if($Row['CaisseType']<>""){echo stripslashes($Row['CaisseType']." n° ".$Row['NumCaisse']);}?></td>
							<td><?php echo stripslashes($Row['Personne']);?></td>
							<td><?php echo stripslashes(stripslashes($Row['Commentaire']));?></td>
							<td>
							<?php 
							if($Row['TransfertEC']==0){
								//Si manager alors peut valider ou refuser le transfert 
								$visible=1;
								
								//Vérifier si prestation ayant le lieu "Magasin" ou "Magasin Paris" ou "Magasin Toulouse"
								$req="SELECT Id 
									FROM tools_lieu 
									WHERE Libelle LIKE 'Magasin%'
									AND Id_Prestation=".$Row['Id_Prestation']."
									AND Id_Pole=".$Row['Id_Pole']." ";
								$ResultLieu=mysqli_query($bdd,$req);
								$NbLieu=mysqli_num_rows($ResultLieu);
								
								if(DroitsPrestationPole(array($IdPosteMagasinier,$IdPosteChefEquipe,$IdPosteCoordinateurEquipe,$IdPosteCoordinateurProjet,$IdPosteResponsableProjet),$Row['Id_Prestation'],$Row['Id_Pole'])==0
									&& 
									(DroitsFormation1Plateforme($Row['Id_Plateforme'],array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))==0
									|| 
									(DroitsFormation1Plateforme($Row['Id_Plateforme'],array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))>0 && $NbLieu==0)
									)
								){
									$visible=0;
								}
								if($visible==1){
							?>
									<a style="text-decoration:none;" title="<?php if($_SESSION['Langue']=="FR"){echo "Valider";}else{echo "Validate";}?>" href="javascript:OuvreFenetreValider('<?php echo $Row['Id']; ?>','<?php echo $_GET['Id']; ?>')"><img src="../../Images/Valider.png" width="15px" border="0" alt="Valider" title="Valider"></a>
							<?php
								}
							}
							?>
							</td>
							<td>
							<?php 
							if($Row['TransfertEC']==0){
								if($visible==1){
							?>
									<a style="text-decoration:none;" title="<?php if($_SESSION['Langue']=="FR"){echo "Refuser";}else{echo "Refuse";}?>" href="javascript:OuvreFenetreRefus('<?php echo $Row['Id']; ?>','<?php echo $_GET['Id']; ?>')"><img src="../../Images/supprimer.png" width="18px" border="0" alt="Refuse" title="Refuse"></a>
							<?php
								}
							}
							?>
							</td>
						</tr>
					<?php
					
					$dateReception=$Row['DateReception'];
				}	//Fin boucle
			}		//Fin If
			mysqli_free_result($Result);	// Libération des résultats
			?>
		</table>
		</form>
<?php
echo "<script>AfficherAffectation('site');</script>";
echo "<script>RechargerPrestation();</script>";
echo "<script>RechargerCaisse();</script>";
if(DroitsPlateforme(array($IdPosteGestionnaireMGX,$IdPosteResponsableMGX,$IdPosteInformatique))){
	echo "<script>RechargerPersonne();</script>";
}
if($Id_PrestaPole<>"0_0"){
	echo "<script>document.getElementById('Id_PrestationPole').value='".$Id_PrestaPole."';</script>";
	echo "<script>RechargerLieu();</script>";
}
if($Id_Lieu<>0){
	echo "<script>document.getElementById('Id_Lieu').value='".$Id_Lieu."';</script>";
}
if($Id_Caisse<>0){
	echo "<script>document.getElementById('Id_Caisse').value='".$Id_Caisse."';</script>";
}
if($Id_Personne<>0){
	echo "<script>document.getElementById('Id_Personne').value='".$Id_Personne."';</script>";
}
?>
</body>
</html>
<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Tools - Matériel</title><meta name="robots" content="noindex">
	<link href="../../v2/CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../../v2/Outils/Tools/Fonctions.js"></script>
</head>
<body>

<?php
require("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Fonctions.php");
require_once("../PlanningV2/Fonctions_Planning.php");

if($_POST)
{
	//Récupération de la prestation actuelle de la personne
	$TableauPrestationPolePersonneConnectee=explode("_",PrestationPole_Personne($DateJour,$_SESSION['Id_Personne']));
	$IdPrestationPersonneConnectee=$TableauPrestationPolePersonneConnectee[0];
	$IdPolePersonneConnectee=$TableauPrestationPolePersonneConnectee[1];
	
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
		$TableauPrestationPolePersonneConnectee=explode("_",PrestationPole_Personne($DateJour,$Id_Personne));
		$Id_Prestation=$TableauPrestationPolePersonneConnectee[0];
		$Id_Pole=$TableauPrestationPolePersonneConnectee[1];
	}
	elseif($_POST['affectation']=="caisse"){
		$Id_Caisse=$_POST['Id_Caisse'];
	}
	
	//Ajout du mouvement par défaut (magasin)
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
			Commentaire
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
			'".$_SESSION['Id_Personne']."',
			'".$IdPrestationPersonneConnectee."',
			'".$IdPolePersonneConnectee."',
			'".$_SESSION['Id_Personne']."',
			'".$IdPrestationPersonneConnectee."',
			'".$IdPolePersonneConnectee."',
			'".$DateJour."',
			'".$DateJour."',
			'".addslashes($_POST['Remarques'])."'
		);";
	$ResultMouvement=mysqli_query($bdd,$RequeteMouvement);
	echo "<script>FermerEtRecharger('".$_POST['Page']."');</script>";

}
elseif($_GET)
{
		$Requete="
				SELECT
					NumAAA
				FROM
					tools_materiel
				WHERE
					Id='".$_GET['Id']."';";
			$Result=mysqli_query($bdd,$Requete);
			$Row=mysqli_fetch_array($Result);
?>
		<form id="formulaire" method="POST" action="" onSubmit="return VerifChamps();">
		<input type="hidden" name="Id" value="<?php echo $_GET['Id']; ?>">
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
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Plateforme :";}else{echo "Platform :";} ?></td>
				<td>
				<?php $Id_PrestaPole="0_0" ?>
					<select name="Id_Plateforme" id="Id_Plateforme" style="width:200px" onchange="RechargerPrestation()">
					<option value="0"></option>
						<?php
							$Id_Plateforme=0;
							if($Id_PrestaPole<>0){
								$tabPrestaPole=explode("_",$Id_PrestaPole);
								$req="SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=".$tabPrestaPole[0];
								$resultPlat=mysqli_query($bdd,$req);
								$nbPlat=mysqli_num_rows($resultPlat);
								if($nbPlat>0){
									$rowPla=mysqli_fetch_array($resultPlat);
									$Id_Plateforme=$rowPla['Id_Plateforme'];
								}
							}
							$requetePlat="SELECT Id, Libelle
								FROM new_competences_plateforme
								WHERE Id NOT IN (11,14)
								ORDER BY Libelle";
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
							while($rowLieu=mysqli_fetch_array($resultlieu))
							{
								echo "<script>Liste_Lieu[".$i."] = new Array(".$rowLieu['Id'].",".$rowLieu['Id_Prestation'].",'".str_replace("'"," ",$rowLieu['Id_Pole'])."','".str_replace("'"," ",$rowLieu['Libelle'])."');</script>";
								$i++;
							}
						?>
					</select>
				</td>
			</tr>
			<tr class="trPersonne">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "Person :";} ?></td>
				<td>
					<select name="Id_Personne" id="Id_Personne">
					<?php
					$rq="SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne
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
						ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
					$resultpersonne=mysqli_query($bdd,$rq);
					$Id_Personne=0;
					while($rowpersonne=mysqli_fetch_array($resultpersonne))
					{
						$selected="";
						if($Id_Personne==0){$Id_Personne=$rowpersonne['Id'];$selected = "selected";}
						echo "<option value='".$rowpersonne['Id']."' ".$selected.">".str_replace("'"," ",$rowpersonne['Personne'])."</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			<tr class="trCaisse">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Caisse :";}else{echo "Box :";} ?></td>
				<td>
					<select name="Id_Caisse" id="Id_Caisse">
					<?php
					$rq="SELECT Id, Num, 
						(SELECT Libelle FROM tools_caissetype WHERE Id=Id_CaisseType) AS CaisseType
						FROM tools_caisse 
						WHERE Suppr=0
						ORDER BY CaisseType ASC , tools_caisse.Num ASC";
					$resultcaisse=mysqli_query($bdd,$rq);
					$Id_Caisse=0;
					while($rowCaisse=mysqli_fetch_array($resultcaisse))
					{
						$selected="";
						if($Id_Caisse==0){$Id_Caisse=$rowCaisse['Id'];$selected = "selected";}
						echo "<option value='".$rowCaisse['Id']."' ".$selected.">".str_replace("'"," ",$rowCaisse['CaisseType'])." n° ".$rowCaisse['Num']."</option>\n";
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
					<input class="Bouton" type="submit"
					<?php
						if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
					?>
					>
				</td>
			</tr>
		</table><br>
		<table style="width:100%; height:95%; align:center;" class="TableCompetences">
			<tr>
				<td style="color:#22b63d" class="Libelle" align="center" colspan="5"><?php if($LangueAffichage=="FR"){echo "HISTORIQUE";}else{echo "HISTORICAL";} ?></td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<tr>
				<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Date";}else{echo "Date";}?></td>
				<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Activity";}?></td>
				<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Lieu";}else{echo "Place";}?></td>
				<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Caisse";}else{echo "Toolbox";}?></td>
				<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?></td>
				<td class="EnTeteTableauCompetences"><?php if($LangueAffichage=="FR"){echo "Remarque";}else{echo "Note";}?></td>
			</tr>
			<?php
				$req="SELECT 
					DateReception,Commentaire,
					(SELECT Libelle FROM new_competences_prestation WHERE Id=Id_Prestation) AS Prestation,
					(SELECT Libelle FROM new_competences_pole WHERE Id=Id_Pole) AS Pole,
					(SELECT Libelle FROM tools_lieu WHERE Id=Id_Lieu) AS Lieu,
					(SELECT Num FROM tools_caisse WHERE Id=Id_Caisse) AS NumCaisse,
					(SELECT Libelle FROM tools_caissetype WHERE Id=(SELECT Id_CaisseType FROM tools_caisse WHERE Id=Id_Caisse)) AS CaisseType,
					(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE Id=Id_Personne) AS Personne 
					FROM tools_mouvement
					WHERE Suppr=0
					AND Id_Materiel__Id_Caisse=".$_GET['Id']."
					AND Type=0
					AND TypeMouvement=0
					ORDER BY DateReception DESC, Id DESC
				";
				$Result=mysqli_query($bdd,$req);
				$NbEnreg=mysqli_num_rows($Result);
				if($NbEnreg>0)
				{
				$Couleur="#EEEEEE";
				while($Row=mysqli_fetch_array($Result))
				{
					if($Couleur=="#EEEEEE"){$Couleur="#FFFFFF";}
					else{$Couleur="#EEEEEE";}
					
					$LIBELLE_POLE="";
					if($Row['Pole']<>""){$LIBELLE_POLE=" - ".$Row['Pole'];}
			?>
				<tr bgcolor="<?php echo $Couleur;?>">
					<td><?php echo AfficheDateJJ_MM_AAAA($Row['DateReception']);?></td>
					<td><?php echo stripslashes(substr($Row['Prestation'],0,7).$LIBELLE_POLE);?></td>
					<td><?php echo stripslashes($Row['Lieu']);?></td>
					<td><?php echo stripslashes($Row['CaisseType']." ".$Row['NumCaisse']);?></td>
					<td><?php echo stripslashes($Row['Personne']);?></td>
					<td><?php echo stripslashes(stripslashes($Row['Commentaire']));?></td>
				</tr>
			<?php
				}	//Fin boucle
			}		//Fin If
			mysqli_free_result($Result);	// Libération des résultats
			?>
		</table>
		</form>
<?php
echo "<script>AfficherAffectation('site');</script>";
}
	
?>
</body>
</html>
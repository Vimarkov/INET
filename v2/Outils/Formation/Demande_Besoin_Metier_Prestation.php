<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formations - Ajouter un besoin / métier / prestation</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script type="text/javascript">
		function FermerEtRecharger()
		{
			if(window.opener.document.getElementById('formulaire')){
				window.opener.document.getElementById('formulaire').submit();
			}
			window.close();
		}
	</script>
</head>
<body>

<?php
if($_POST){
	if(isset($_POST['generer'])){
		if(isset($_POST['Id_Formation'])){
			$tabPresta=explode("_",$_POST['Id_Prestation']);
			$IdPrestation=$tabPresta[0];
			$IdPole=$tabPresta[1];

			//Boucle pour faire les INSERT dans la table des besoins

			foreach($_POST['Id_Formation'] as $value)
			{
				$ReqInsertBesoin="
					INSERT INTO
						form_demandebesoin
						(
							Id_Demandeur,
							Id_Prestation,
							Id_Pole,
							Id_Metier,
							Obligatoire,
							Id_Formation,
							Date_Demande,
							Commentaire,
							Type
						)
					VALUES
						(".
							$IdPersonneConnectee.",".
							$IdPrestation.",".
							$IdPole.",".
							$_POST['Id_Metier'].",".
							$_POST['Obligatoire'].",".
							$value.",
							'".$DateJour."',
							'".addslashes($_POST['Commentaire'])."'
							,'BesoinMetierPrestation'
						)";
				$ResultInsertBesoin=mysqli_query($bdd,$ReqInsertBesoin);
			}
			echo "<script>FermerEtRecharger();</script>";
		}
	}
}
?>
<form id="formulaire" method="POST" action="Demande_Besoin_Metier_Prestation.php" onSubmit="return VerifChamps();">
	<table class="TableCompetences" style="width:95%; height:95%; align:center;">
		<tr>
			<td class="Libelle" style="width:20%;">
				<?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> :
			</td>
			<td style="width:80%;" colspan="3">
				<select name="plateforme" id="plateforme" style="width:100px;" onchange="submit()">
					<?php
					$Plat=0;
					if($_GET){
						if(isset($_GET['Id_Plateforme'])){$Plat=$_GET['Id_Plateforme'];}
					}
					else{$Plat=$_POST['plateforme'];}
					$reqPla="
						SELECT
                            DISTINCT Id_Plateforme,
                            (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle
						FROM
                            new_competences_personne_poste_plateforme
						WHERE
                            Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RH).") AND Id_Personne=".$IdPersonneConnectee."
						UNION
						SELECT
                            DISTINCT (SELECT Id_Plateforme FROM new_competences_prestation WHERE new_competences_prestation.Id=new_competences_personne_poste_prestation.Id_Prestation)AS Id_Plateforme,
                            (
                                SELECT
                                (
                                    SELECT
                                        Libelle
                                    FROM
                                        new_competences_plateforme
                                    WHERE
                                        new_competences_plateforme.Id=new_competences_prestation.Id_Plateforme
                                )
                                FROM
                                    new_competences_prestation
                                WHERE
                                    new_competences_prestation.Id=new_competences_personne_poste_prestation.Id_Prestation
                            ) AS Libelle
						FROM
                            new_competences_personne_poste_prestation
                        WHERE
                            Id_Poste IN (".implode(","$TableauIdPostesRespPresta_CQ).")
                            AND Id_Personne=".$IdPersonneConnectee." 
						ORDER BY
                            Libelle";
					$resultPlat=mysqli_query($bdd,$reqPla);
					$nbPlat=mysqli_num_rows($resultPlat);
					if($nbPlat>0){
						while($rowPlat=mysqli_fetch_array($resultPlat)){
							$selected="";
							if($_POST){
								if($Plat==$rowPlat['Id_Plateforme']){$selected="selected";}
							}
							else{
								if(isset($_GET['Id_Plateforme'])){
									if($Plat==$rowPlat['Id_Plateforme']){$selected="selected";}
								}
								if($Plat==0){$Plat=$rowPlat['Id_Plateforme'];}
							}
							echo "<option value='".$rowPlat['Id_Plateforme']."' ".$selected.">".stripslashes($rowPlat['Libelle'])."</option>";
							
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Prestation";}else{echo "Site";}?> :</td>
			<td>
				<?php
				$Presta=0;
				if($_POST){
					$Presta=$_POST['Id_Prestation'];
				}
				echo "<select name='Id_Prestation' id='Id_Prestation' onChange='submit();'>";
				$reqPla="
					SELECT Id_Plateforme
					FROM new_competences_personne_poste_plateforme
					WHERE Id_Plateforme=".$Plat." AND Id_Poste IN (".implode(",",$TableauIdPostesAF_RF_RH).") AND Id_Personne=".$IdPersonneConnectee." ";
				$resultPlat=mysqli_query($bdd,$reqPla);
				$NbPlat=mysqli_num_rows($resultPlat);
				if($NbPlat>0){
					$reqPrestation=Get_SQL_PrestationsResponsablesPourPersonne($Plat,true,array(0));
				}
				else{
				    $reqPrestation=Get_SQL_PrestationsResponsablesPourPersonne($Plat,false,$TableauIdPostesRespPresta_CQ);
				}
				$resultPrestation=mysqli_query($bdd,$reqPrestation);
				while($rowPrestation=mysqli_fetch_array($resultPrestation)){
					$selected="";
					if($_POST){
						if($Presta==$rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole']){$selected="selected";}
					}
					if($Presta==0){$Presta=$rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole'];}
					echo "<option value='".$rowPrestation['Id_Prestation']."_".$rowPrestation['Id_Pole']."' ".$selected.">".stripslashes($rowPrestation['Libelle']).stripslashes($rowPrestation['Pole'])."</option>\n";
				}
				echo "</select>";
				?>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Métier";}else{echo "Job";}?> :</td>
			<td>
				<?php
				$Metier=0;
				if($_POST){
					$Metier=$_POST['Id_Metier'];
				}
				echo "<select name='Id_Metier' id='Id_Metier' style='width:200px;' onChange='submit();'>";
				$reqMetier="
					SELECT Id, Libelle
					FROM new_competences_metier
					ORDER BY Libelle ";

				$resultMetier=mysqli_query($bdd,$reqMetier);
				while($rowMetier=mysqli_fetch_array($resultMetier)){
					$selected="";
					if($_POST){
						if($Metier==$rowMetier['Id']){$selected="selected";}
					}
					if($Metier==0){$Metier=$rowMetier['Id'];}
					echo "<option value='".$rowMetier['Id']."' ".$selected.">".stripslashes($rowMetier['Libelle'])."</option>\n";
				}
				echo "</select>";
				?>
			</td>
		</tr>
		<tr>
			<td class="Libelle">
				<?php if($LangueAffichage=="FR"){echo "Obligatoire / Facultative";}else{echo "Mandatory / Optional";}?> :
			</td>
			<td align="left">
				<select name="Obligatoire" id="Obligatoire" onChange="submit();">
					<?php
					if($LangueAffichage=="FR"){$Tableau=array('Obligatoire|1','Facultative|0');}
					else{$Tableau=array('Mandatory|1','Optional|0');}
					$Obligatoire=1;
					if($_POST){
						$Obligatoire=$_POST['Obligatoire'];
					}
					foreach($Tableau as $indice => $valeur)
					{
						$selected="";
						
						$valeur=explode("|",$valeur);
						if($Obligatoire==$valeur[1]){$selected="selected";}
						echo "<option value='".$valeur[1]."' ".$selected.">".$valeur[0]."</option>\n";
					}
					?>
				</select>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td valign="top" class="Libelle" width="100%" colspan="2">
				<?php if($LangueAffichage=="FR"){echo "Cocher les formations à paramétrer";}else{echo "Check the formations to set";}?> : <br>
				<?php
				//Personnes présentes par prestation à cette date
				$req="
				SELECT form_formation.Id,
				(SELECT Libelle FROM form_organisme WHERE form_organisme.Id=Id_Organisme) AS Organisme,
				form_formation.Id_TypeFormation,
				(SELECT Libelle FROM form_formation_langue_infos
				WHERE form_formation_langue_infos.Id_Langue=form_formation_plateforme_parametres.Id_Langue
				AND Id_Formation=form_formation.Id AND Suppr=0) AS Libelle,
				(SELECT LibelleRecyclage FROM form_formation_langue_infos
				WHERE form_formation_langue_infos.Id_Langue=form_formation_plateforme_parametres.Id_Langue
				AND Id_Formation=form_formation.Id AND Suppr=0) AS LibelleRecyclage,
				form_formation.Recyclage
				FROM form_formation_plateforme_parametres 
				LEFT JOIN form_formation
				ON form_formation_plateforme_parametres.Id_Formation=form_formation.Id
				WHERE form_formation_plateforme_parametres.Id_Plateforme=".$Plat."
				AND form_formation_plateforme_parametres.Suppr=0 AND form_formation.Suppr=0
				ORDER BY Libelle,LibelleRecyclage 
				";
				$resultGroupeFormation=mysqli_query($bdd,$req);
				$NbForm=mysqli_num_rows($resultGroupeFormation);
				?>
				<div id="listeForm" style="width:100%;height:200px;overflow:auto;">
				<?php
					if($NbForm>0){
						while($rowForm=mysqli_fetch_array($resultGroupeFormation)){
							echo "<div>";
							$htmlForm="";
							$NonParametre=0;
							
							$tabPresta=explode("_",$Presta);
							//Recherche si la formation n'est pas déjà paramétré
							$req="SELECT Id
										FROM form_prestation_metier_formation
										WHERE Suppr=0
										AND Id_Metier=".$Metier."
										AND Id_Formation=".$rowForm['Id']."
										AND Id_Prestation=".$tabPresta[0]."
										AND Id_Pole=".$tabPresta[1]." ";
							$resultParametrage=mysqli_query($bdd,$req);
							$nbParametrage=mysqli_num_rows($resultParametrage);
							
							if($nbParametrage>0){
								if($LangueAffichage=="FR"){
									$htmlForm="<font color='#ff4141'>Déjà paramétrée</font>";

								}
								else{
									$htmlForm="<font color='#ff4141'>Already set</font>";
								}
								$NonParametre=1;
							}
							else{
								
								//Recherche si la formation n'est pas déjà demandé
								$req="SELECT Id
											FROM form_demandebesoin
											WHERE Suppr=0
											AND Etat=''
											AND Id_Metier=".$Metier."
											AND Id_Formation=".$rowForm['Id']."
											AND Id_Prestation=".$tabPresta[0]."
											AND Id_Pole=".$tabPresta[1]."
											AND Obligatoire=".$Obligatoire." ";
								$resultDemande=mysqli_query($bdd,$req);
								$nbDemande=mysqli_num_rows($resultDemande);
								
								if($nbDemande>0){
									if($LangueAffichage=="FR"){
										$htmlForm="<font color='#ff4141'>Demande en cours</font>";

									}
									else{
										$htmlForm="<font color='#ff4141'>Demand in progress</font>";
									}
									$NonParametre=1;
								}
							}
							$Organisme="";
							if($rowForm['Organisme']<>""){$Organisme=" (".$rowForm['Organisme'].")";}
							
							if($rowForm['Libelle']<>""){
								if($NonParametre==0){
									echo "<input class='check' type='checkbox' name='Id_Formation[]' value='".$rowForm['Id']."'>".stripslashes($rowForm['Libelle']).$Organisme."&nbsp;";
								}
								else{
									echo "&#x204E;&nbsp;&nbsp;".stripslashes($rowForm['Libelle']).$Organisme."&nbsp;".$htmlForm;
								}
							}
							echo "</div>";
						}
					}
				?>
				</div>
			</td>
		</tr>
		<tr class="TitreColsUsers">
			<td class="Libelle">
				<?php if($LangueAffichage=="FR"){echo "Commentaires";}else{echo "Comment";}?> : <br>
				<textarea name="Commentaire" cols="40" style="resize:none;"></textarea>
			<td>
		</tr>
		<tr class="TitreColsUsers">
			<td colspan="2" align="center">
				<input class="Bouton" name="generer" type="submit" <?php if($LangueAffichage=="FR"){echo "value='Demander les besoins'";}else{echo "value='Ask needs'";}?>>
			</td>
		</tr>
	</table>
</form>
<?php

if($_GET){
	if($_GET['Mode']=="Suppr"){
		//MODE SUPPRESSION
		//----------------
		$ReqSupprBesoin="
			UPDATE
				form_demandebesoin
			SET
				Suppr=1,
				Id_Personne_MAJ=".$IdPersonneConnectee.",
				Date_MAJ='".date('Y-m-d')."',
				Motif_Suppr='Depuis la liste des besoins'
			WHERE
				Id=".$_GET['Id'];
		$ResultSupprBesoin=mysqli_query($bdd,$ReqSupprBesoin);
		
		//Suppression des qualifications créées dans la gestion des compétences suite au besoin généré
		$ReqSupprRelation="
			UPDATE
				new_competences_relation
			SET
				Suppr=1
			WHERE
				Id_Besoin=".$_GET['Id'];
		$ResultSupprRelation=mysqli_query($bdd,$ReqSupprRelation);
		echo "<script>FermerEtRecharger();</script>";
	}
}
mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
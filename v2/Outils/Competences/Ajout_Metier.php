<html>
<head>
	<title>Compétences - Métier</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script>
		function VerifChamps()
		{
			if(formulaire.Libelle.value==''){alert('Vous n\'avez pas renseigné le libellé.');return false;}
			else{return true;}
		}
			
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
	</script>
</head>
<body>

<?php
session_start();
require("../Connexioni.php");
require_once("../Formation/Globales_Fonctions.php");
require_once("../Fonctions.php");

if($_POST)
{
	if($_POST['Mode']=="Ajout")
	{
		$result=mysqli_query($bdd,"SELECT Id FROM new_competences_metier WHERE Libelle='".addslashes($_POST['Libelle'])."'");
		if(mysqli_num_rows($result)==0)
		{
		    $requete="
                INSERT INTO
                    new_competences_metier
                (
                    Libelle,
                    Col,
                    Fiche,
                    Code,
                    Fonction
                )
                VALUES
                (
                    '".addslashes($_POST['Libelle'])."',
                    '".$_POST['Col']."',
                    '".$_POST['Fiche']."',
                    '".$_POST['Code']."',
                    ".$_POST['Fonction']."
                )";
		    $result=mysqli_query($bdd,$requete);
			
			//Avertir par mail les différents AF des plateformes + les CQP
			//Attention pour l'instant nous limitons à la plateforme de TOULOUSE
			//A MODIFIER LORS DU DEPLOIEMENT SUR LES AUTRES PLATEFORMES
			$Headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
			$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
			
			if($LangueAffichage=="FR")
			{
				$Objet="Nouveau métier dans l'extranet : ".$_POST['Libelle'];
				$Message="	<html>
								<head><title>Nouveau métier dans l'extranet </title></head>
								<body>
									Bonjour,
									<br><br>
									Le métier suivant a été créé sur l'extranet : ".$_POST['Libelle']."<br>
									Pensez à le configurer au niveau des besoins en formation par métier par prestation
									<br>
									Bonne journée.<br>
									L'Extranet Daher industriel services DIS.
								</body>
							</html>";
			}
			else
			{
				$Objet="New job in the extranet : ".$_POST['Libelle'];
				$Message="	<html>
								<head><title>New job in the extranet</title></head>
								<body>
									Hello,
									<br><br>
									The following job was created on the extranet : ".$_POST['Libelle']."<br>
									Remember to configure it at the level of training needs by profession by delivery
									<br>
									Have a good day.<br>
									Extranet Daher industriel services DIS.
								</body>
							</html>";
			}
			
			$Emails="";
			//Liste des CQP
			$reqCQ="SELECT DISTINCT EmailPro 
					FROM new_competences_personne_poste_prestation
					LEFT JOIN new_rh_etatcivil
					ON new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
					WHERE new_competences_personne_poste_prestation.Id_Poste IN (".$IdPosteReferentQualiteProduit.") 
					AND (
						SELECT Id_Plateforme 
						FROM new_competences_prestation 
						WHERE new_competences_prestation.Id=new_competences_personne_poste_prestation.Id_Prestation
                        )=1 ";
			$ResultCQ=mysqli_query($bdd,$reqCQ);
			$NbCQ=mysqli_num_rows($ResultCQ);
			if($NbCQ>0)
			{
				while($RowCQ=mysqli_fetch_array($ResultCQ))
				{
					if($RowCQ['EmailPro']<>""){$Emails.=$RowCQ['EmailPro'].",";}
				}
			}
			
			//Ajout en destinataire de la responsable des formations externes (comme demandé)
			$reqAssistanteFormationExterne="
                SELECT DISTINCT
                    EmailPro
                FROM
                    new_competences_personne_poste_plateforme
				LEFT JOIN new_rh_etatcivil
					ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
				WHERE
                    new_competences_personne_poste_plateforme.Id_Poste IN (".implode(",",array($IdPosteResponsableFormation,$IdPosteAssistantFormationExterne,$IdPosteResponsableQualite)).")
					AND Id_Plateforme=1";
			$ResultAssistanteFormationExterne=mysqli_query($bdd,$reqAssistanteFormationExterne);
			$NbAssistanteFormationExterne=mysqli_num_rows($ResultAssistanteFormationExterne);
			if($NbAssistanteFormationExterne > 0)
			{
			    while($RowAssistanteFormationExterne=mysqli_fetch_array($ResultAssistanteFormationExterne))
			    {
			        if($RowAssistanteFormationExterne['EmailPro']<>""){$Emails.=$RowAssistanteFormationExterne['EmailPro'].",";}
			    }
			}
			
			$Emails=substr($Emails,0,-1);
			
			if($Emails<>"")
			{
				if(mail($Emails,$Objet,$Message,$Headers,'-f extranet@aaa-aero.com')){echo "Un message a été envoyé à ".$Emails."\n";}
				else{echo "Un message n'a pu être envoyé à ".$Emails."\n";}
			}
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Ce libellé existe déjà.<br>Vous devez recommencer l'opération.</font>";}
	}
	elseif($_POST['Mode']=="Modif")
	{
		$result=mysqli_query($bdd,"SELECT Id FROM new_competences_metier WHERE Libelle='".addslashes($_POST['Libelle'])."' AND Id!=".$_POST['Id']);
		if(mysqli_num_rows($result)==0)
		{
		    $requete="
                UPDATE
                    new_competences_metier
                SET
                    Libelle='".addslashes($_POST['Libelle'])."',
                    Col='".$_POST['Col']."',
                    Fiche='".$_POST['Fiche']."',
                    Code='".$_POST['Code']."',
                    Fonction=".$_POST['Fonction']."
                WHERE
                    Id=".$_POST['Id'];
			$result=mysqli_query($bdd,$requete);
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Ce libellé existe déjà.<br>Vous devez recommencer l'opération.</font>";}
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0')
		{
			$result=mysqli_query($bdd,"SELECT Id, Libelle, Col, Fiche, Code, LibelleEN, Fonction FROM new_competences_metier WHERE Id=".$_GET['Id']);
			$row=mysqli_fetch_array($result);
		}
?>
		<form id="formulaire" method="POST" action="Ajout_Metier.php" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($_GET['Mode']=="Modif"){echo $row['Id'];}?>">
		<table style="width:95%; height:95%; align:center;" class="TableCompetences">
			<tr class="TitreColsUsers">
				<td><?php if($LangueAffichage=="FR"){echo "Libellé";}else{echo "Wording";}?> : </td>
				<td colspan="3"><input name="Libelle" size="70" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Libelle'];}?>"></td>
				<td>
					<input class="Bouton" type="submit" 
					<?php
						if($_GET['Mode']=="Modif"){if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}}
						else{if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}}
					?>
					>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td>Col : </td>
				<td>
					<select name="Col">
				<?php
					echo "<option ";
					if($_GET['Mode']=="Modif"){if($row['Col']=="Bleu"){echo "selected ";}}
					echo "value='Bleu'>Bleu</option>\n";
					echo "<option ";
					if($_GET['Mode']=="Modif"){if($row['Col']=="Blanc" || $row['Col']==""){echo "selected ";}}
					echo "value='Blanc'>Blanc</option>\n";
				?>
					</select>
				</td>
				<td>Métier/Fonction : </td>
				<td>
					<select name="Fonction">
				<?php
					echo "<option ";
					if($_GET['Mode']=="Modif"){if($row['Fonction']=="0"){echo "selected ";}}
					echo "value='0'>Métier</option>\n";
					echo "<option ";
					if($_GET['Mode']=="Modif"){if($row['Fonction']=="1"){echo "selected ";}}
					echo "value='1'>Fonction</option>\n";
				?>
					</select>
				</td>
			</tr>
			<tr class="TitreColsUsers">
				<td>N° Fiche : </td>
				<td><input name="Fiche" size="15" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Fiche'];}?>"></td>
				<td>Code : </td>
				<td><input name="Code" size="15" type="text" value="<?php if($_GET['Mode']=="Modif"){echo $row['Code'];}?>"></td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$result=mysqli_query($bdd,"SELECT Id FROM new_competences_personne_metier WHERE Id_Metier=".$_GET['Id']);
		if(mysqli_num_rows($result)==0)
		{
			$result=mysqli_query($bdd,"DELETE FROM new_competences_metier WHERE Id=".$_GET['Id']);
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo "<font class='Erreur'>Vous ne pouvez pas supprimer ce métier car une ou plusieurs personne y est rattachées.</font>";}
	}
	if($_GET['Id']!='0'){mysqli_free_result($result);}	// Libération des résultats}
}
	mysqli_close($bdd);			// Fermeture de la connexion
?>
	
</body>
</html>
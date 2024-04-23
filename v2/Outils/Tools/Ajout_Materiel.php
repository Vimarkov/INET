<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Tools - Matériel</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Fonctions.js"></script>
	<script type="text/javascript" src="../Fonctions_Outils.js"></script>
	<script>
		Liste_ChampsMateriel = new Array();
		Liste_TypeMaterielAssocies = new Array();
		function VerifChamps()
		{
			if(formulaire.Id_ModeleMateriel.value=='' || formulaire.Id_ModeleMateriel.value=='0'){alert('Vous n\'avez pas renseigné le modèle de matériel.');return false;}
			if(formulaire.Mode.value=='Modif'){
				if(formulaire.NumAAA.value==''){alert('Vous n\'avez pas renseigné le numéro AAA.');return false;}
			}
			return true;
		}
		Liste_FamilleMateriel_TypeMateriel = new Array();
		function Change_TypeMateriel()
		{
			var sel="";
			sel ="<select size='1' name='Id_FamilleMateriel' id='Id_FamilleMateriel' onclick='Change_FamilleMateriel();'>";
			
			for(var i=0;i<Liste_FamilleMateriel_TypeMateriel.length;i++)
			{
				if(Liste_FamilleMateriel_TypeMateriel[i][0]==document.getElementById('Id_TypeMateriel').value)
				{
					sel= sel + "<option value="+Liste_FamilleMateriel_TypeMateriel[i][1];
					if(Liste_FamilleMateriel_TypeMateriel[i][1]==document.getElementById('Id_FamilleMateriel_Initial').value){sel = sel + " selected";}
					sel= sel + ">"+Liste_FamilleMateriel_TypeMateriel[i][2]+"</option>";
				}
			}
			sel =sel + "</select>";
			document.getElementById('Div_FamilleMateriel').innerHTML=sel;
			Change_FamilleMateriel();
			
			for(var i=0;i<Liste_ChampsMateriel.length;i++)
			{
				AfficheDiv=false;
				if(Liste_ChampsMateriel[i][0]=="NumPC"){console.log(Liste_ChampsMateriel[i][1].length);}
				for(var j=0;j<Liste_ChampsMateriel[i][1].length;j++)
				{
					if(Liste_ChampsMateriel[i][1][j]==document.getElementById('Id_TypeMateriel').value){AfficheDiv=true;}
				}
				if(AfficheDiv){document.getElementById('Div_'+Liste_ChampsMateriel[i][0]).style.display="";}
				else{document.getElementById('Div_'+Liste_ChampsMateriel[i][0]).style.display="none";}
			}
		}
		
		function Change_CodeArticle()
		{
			if(document.getElementById('Id_CodeArticle').value!=0){
				tab = document.getElementById('Id_CodeArticle').value.split('_');
				if(tab[1]==1){
					document.getElementById('Immo1').style.display="";
					document.getElementById('Immo2').style.display="";
				}
				else{
					document.getElementById('Immo1').style.display="none";
					document.getElementById('Immo2').style.display="none";
				}
			}
		}
		function Change_Location()
		{
			if(document.getElementById('Location').value==1){
				document.getElementById('Location1').style.display="";
			}
			else{
				document.getElementById('Location1').style.display="none";
			}
		}
		
		Liste_ModeleMateriel_FamilleMateriel = new Array();
		function Change_FamilleMateriel()
		{
			var sel="";
			sel ="<select size='1' name='Id_ModeleMateriel' id='Id_ModeleMateriel'>";
			for(var i=0;i<Liste_ModeleMateriel_FamilleMateriel.length;i++)
			{
				if(Liste_ModeleMateriel_FamilleMateriel[i][0]==document.getElementById('Id_FamilleMateriel').value)
				{
					sel= sel + "<option value="+Liste_ModeleMateriel_FamilleMateriel[i][1];
					if(Liste_ModeleMateriel_FamilleMateriel[i][1]==document.getElementById('Id_ModeleMateriel_Initial').value){sel = sel + " selected";}
					sel= sel + ">"+Liste_ModeleMateriel_FamilleMateriel[i][2]+"</option>";
				}
			}
			sel =sel + "</select>";
			document.getElementById('Div_ModeleMateriel').innerHTML=sel;
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

$TablePrincipale="tools_materiel";
$RequeteInsertUpdate="";

$ListeChamps="";
$i=0;
foreach($Tableau_ChampsMateriel as &$TableauValeur)
{
	$ListeChamps.=$TableauValeur[0].",";
	echo "
	<script>
		Liste_TypeMaterielAssocies[0]= new Array(".implode(',',$TableauValeur[4]).");
		Liste_ChampsMateriel[".$i."] = new Array('".$TableauValeur[0]."',new Array(0,".implode(',',$TableauValeur[4])."));
	</script>";
	$i+=1;
}

if($_POST)
{
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
		
		$req="SELECT tools_mouvement.Id_Prestation,Id_Pole
				FROM tools_mouvement
				WHERE tools_mouvement.TypeMouvement=0 AND tools_mouvement.EtatValidation=1 AND tools_mouvement.Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=".$Id_Caisse."
				ORDER BY tools_mouvement.DateReception DESC, tools_mouvement.Id DESC LIMIT 1";
		$ResultMouv=mysqli_query($bdd,$req);
		$NbEnregMouv=mysqli_num_rows($ResultMouv);
		$Id_Prestation=0;
		if($NbEnregMouv>0)
		{
			$RowMouv=mysqli_fetch_array($ResultMouv);
			$Id_Prestation=$RowMouv['Id_Prestation'];
			$Id_Pole=$RowMouv['Id_Pole'];
		}
		
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
	
	
	$tabCodeArticle=explode("_",$_POST['Id_CodeArticle']);
	if($_POST['Mode']=="Ajout")
	{
		if($Id_PlateformeNew>0){
			$RequeteInsertUpdate="
				INSERT INTO "
					.$TablePrincipale."
				(
					Id_ModeleMateriel,
					Id_Fournisseur,
					Id_Fabricant,
					NumAAA,
					".$ListeChamps."
					Remarques,
					InfosTechnique,
					Designation,
					NumFacture,
					Id_CodeArticle,
					NumFicheImmo,DateDebutImmo,DateFinImmo,
					Location,DateDebutLocation,DateFinContratLocation,
					Id_PersonneMAJ,
					DateMAJ
				)
				VALUES
				(
					'".$_POST['Id_ModeleMateriel']."',
					'".$_POST['Id_Fournisseur']."',
					'".$_POST['Id_Fabricant']."',
					'".Next_CodeGravureMateriel($Id_PlateformeNew)."',";
			foreach($Tableau_ChampsMateriel as &$TableauValeur){
				if($TableauValeur[0]=="DateLettreEngagementMC"  || $TableauValeur[0]=="DateDerniereVerification"){$RequeteInsertUpdate.="'".TrsfDate_($_POST[$TableauValeur[0]])."',";}
				else{$RequeteInsertUpdate.="'".addslashes($_POST[$TableauValeur[0]])."',";}
				
			}
			$RequeteInsertUpdate.="
					'".addslashes($_POST['Remarques'])."',
					'".addslashes($_POST['InfosTechnique'])."',
					'".addslashes($_POST['Designation'])."',
					'".addslashes($_POST['NumFacture'])."',
					".$tabCodeArticle[0].",
					'".addslashes($_POST['NumFicheImmo'])."',
					'".TrsfDate_($_POST['DateDebutImmo'])."',
					'".TrsfDate_($_POST['DateFinImmo'])."',
					".$_POST['Location'].",
					'".TrsfDate_($_POST['DateDebutLocation'])."',
					'".TrsfDate_($_POST['DateFinLocation'])."',
					'".$IdPersonneConnectee."',
					'".$DateJour."'
				);";
		}
	}
	elseif($_POST['Mode']=="Modif")
	{
		
		$RequeteInsertUpdate="
			UPDATE "
				.$TablePrincipale."
			SET
				Id_ModeleMateriel='".$_POST['Id_ModeleMateriel']."',
				Id_Fournisseur='".$_POST['Id_Fournisseur']."',
				Id_Fabricant='".$_POST['Id_Fabricant']."',";
			foreach($Tableau_ChampsMateriel as &$TableauValeur){
				if($TableauValeur[0]=="DateLettreEngagementMC" || $TableauValeur[0]=="DateDerniereVerification"){$RequeteInsertUpdate.=$TableauValeur[0]."='".TrsfDate_($_POST[$TableauValeur[0]])."',";}
				else{$RequeteInsertUpdate.=$TableauValeur[0]."='".addslashes($_POST[$TableauValeur[0]])."',";}
			}
			$RequeteInsertUpdate.="
				Remarques='".addslashes($_POST['Remarques'])."',
				InfosTechnique='".addslashes($_POST['InfosTechnique'])."',
				Designation='".addslashes($_POST['Designation'])."',
				NumFacture='".addslashes($_POST['NumFacture'])."',
				NumAAA='".addslashes($_POST['NumAAA'])."',
				Id_CodeArticle=".$tabCodeArticle[0].",
				NumFicheImmo='".addslashes($_POST['NumFicheImmo'])."',
				DateDebutImmo='".TrsfDate_($_POST['DateDebutImmo'])."',
				DateFinImmo='".TrsfDate_($_POST['DateFinImmo'])."',
				Location=".$_POST['Location'].",
				DateDebutLocation='".TrsfDate_($_POST['DateDebutLocation'])."',
				DateFinContratLocation='".TrsfDate_($_POST['DateFinLocation'])."',
				Id_PersonneMAJ='".$IdPersonneConnectee."',
				DateMAJ='".$DateJour."'
			WHERE
				Id='".$_POST['Id']."';";
	}
	
	$ResultInsertUpdate=mysqli_query($bdd,$RequeteInsertUpdate);
	
	if($_POST['Mode']=="Ajout")
	{
		$Id_Materiel=mysqli_insert_id($bdd);
		
		if($Id_Materiel>0){
			$EtatValidation=1;
			$DateReception=$DateJour;
			$Id_Recepteur=$IdPersonneConnectee;
			$DatePriseEnCompteDemandeur=$DateJour;
			if($Id_PlateformeNew>0){
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
					WHERE Libelle LIKE 'Magasin%'
					AND Id_Prestation=".$Id_Prestation."
					AND Id_Pole=".$Id_Pole."";
				$ResultLieu=mysqli_query($bdd,$req);
				$NbLieu=mysqli_num_rows($ResultLieu);
				
				//Vérifier si la personne n'est pas le responsable de la prestation
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
						Id_DemandeurPrisEnCompte,
						DatePriseEnCompteDemandeur
					)
					VALUES
					(
						'0',
						'0',
						'".$Id_Materiel."',
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
						'".$Id_Recepteur."',
						'".$DatePriseEnCompteDemandeur."'
					);";
				$ResultMouvement=mysqli_query($bdd,$RequeteMouvement);
				
				//Mettre à jour l'affectation dans matériel
				$req="UPDATE tools_materiel 
					SET Id_PrestationT=".$Id_Prestation.", Id_PoleT=".$Id_Pole.", Id_LieuT=".$Id_Lieu.", Id_PersonneT=".$Id_Personne.", 
						Id_CaisseT=".$Id_Caisse.", DateReceptionT='".$DateReception."', EtatValidationT=".$EtatValidation." 
					WHERE Id=".$Id_Materiel." ";

				$ResultUpdt=mysqli_query($bdd,$req);
				
				//Affichage du AAATO créé
				if($_SESSION['Langue']=="FR"){
					$Requete="
						SELECT
							Id,
							(SELECT Libelle FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) AS LIBELLE_MODELEMATERIEL,
							NumAAA
						FROM
							tools_materiel
						WHERE
							Id='".$Id_Materiel."';";
					$Result=mysqli_query($bdd,$Requete);
					$Row=mysqli_fetch_array($Result);
					echo '<table style="width:100%; height:95%; align:center;" class="TableCompetences">
						<tr><td height="5px"></td></tr><tr><td>'.$Row['NumAAA']." créé pour le modèle de matériel ".$Row['LIBELLE_MODELEMATERIEL'].'</td></tr><tr><td height="5px"></td></tr></table>';
				}
				else{
					$Requete="
						SELECT
							Id,
							(SELECT LibelleEN FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) AS LIBELLE_MODELEMATERIEL,
							NumAAA
						FROM
							tools_materiel
						WHERE
							Id='".$Id_Materiel."';";
					$Result=mysqli_query($bdd,$Requete);
					$Row=mysqli_fetch_array($Result);
					echo '<table style="width:100%; height:95%; align:center;" class="TableCompetences">
						<tr><td height="5px"></td></tr>'.$Row['NumAAA']." created for the hardware model ".$Row['LIBELLE_MODELEMATERIEL'].'<tr><td height="5px"></td></tr></table>';
				}
				
				//Envoyer un mail si le matériel contient une carte SIM
				if(($_POST['Id_FamilleMateriel']==79 || $_POST['Id_FamilleMateriel']==3 || $_POST['Id_FamilleMateriel']==4) && $_POST['NumTelephone']>0){
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
								N° de téléphone : 0".$_POST['NumTelephone']." <br>
								Personne : ".$LibellePersonne." <br>
								UER : ".$LibelleUER." <br>
								Prestation : ".$LibellePresta." ".$LibellePole."<br>
								N° carte SIM : ".$_POST['NumSIM']."<br>
								Code IMEI du téléphone : ".$_POST['NumIMEI']."<br>
							</body>
						</html>";
					$Emails="mlebacon@aaa-aero.com,ssavy@aaa-aero.com,ROUAHLIMA@aaa-aero.com";
					mail($Emails,$Objet,$MessageMail,$Headers,'-f informatique@aaa-aero.com');
				}
				
				if($_POST['affectation']=="personne"){
					if($Id_Personne>0){
						//Editer le document de pret du matériel 
						echo "<script>window.open('EditerPretMateriel.php?laDate=".date('Y-m-d')."&Id=".$Id_Personne."','Fiche_PretMateriel','status=no,menubar=no,width=20,height=20');</script>";
					}
				}
			}
		}
		echo "<script>Recharger('".$_POST['Page']."');</script>";
	}
	else{
		echo "<script>FermerEtRecharger('".$_POST['Page']."');</script>";
	}
}
elseif($_GET)
{
	//Mode ajout ou modification
	$Modif=false;
	if($_GET['Mode']=="Ajout" || $_GET['Mode']=="Modif")
	{
		if($_GET['Id']!='0')
		{
			$Modif=true;
			$Requete="
				SELECT
					Id,
					(SELECT Id_FamilleMateriel FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel) AS ID_FAMILLEMATERIEL,
					(SELECT Id_TypeMateriel FROM tools_famillemateriel WHERE Id=(SELECT Id_FamilleMateriel FROM tools_modelemateriel WHERE Id=Id_ModeleMateriel)) AS ID_TYPEMATERIEL,
					Id_ModeleMateriel,
					Id_Fournisseur,
					Id_Fabricant,
					NumAAA,
					NumFacture,
					Id_CodeArticle,
					(SELECT Immo FROM tools_codearticle WHERE Id=Id_CodeArticle) AS Immo,
					NumFicheImmo,DateDebutImmo,DateFinImmo,
					Location,DateDebutLocation,DateFinContratLocation,
					".$ListeChamps."
					Remarques,
					InfosTechnique,
					Designation
				FROM
					".$TablePrincipale."
				WHERE
					Id='".$_GET['Id']."';";
			$Result=mysqli_query($bdd,$Requete);
			$Row=mysqli_fetch_array($Result);
		}
?>
		<form id="formulaire" method="POST" action="" onSubmit="return VerifChamps();">
		<input type="hidden" name="Mode" id="Mode" value="<?php echo $_GET['Mode']; ?>">
		<input type="hidden" name="Id" value="<?php if($Modif){echo $Row['Id'];}?>">
		<input type="hidden" name="OldNumAAA" value="<?php if($Modif){echo $Row['NumAAA'];}?>">
		<input type="hidden" name="Page" name="Page" value="<?php if(isset($_GET['Page'])){echo $_GET['Page'];} ?>">
		<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#23b63e;">
			<tr>
				<td class="TitrePage">
				<?php
				if($_GET['Mode']=="Ajout"){if($LangueAffichage=="FR"){echo "Ajouter un matériel";}else{echo "Add material";}}
				else{if($LangueAffichage=="FR"){echo "Modification du matériel ".$Row['NumAAA'];}else{echo "Modification of the material ".$Row['NumAAA'];}}
				?>
				</td>
			</tr>
		</table><br>
		<table style="width:100%; height:95%; align:center;" class="TableCompetences">
			<?php
				if($_GET['Mode']=="Modif"){
			?>
			<tr>
				<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "N° AAA";}else{echo "N° AAA";}?> : </td>
				<td><input name='NumAAA' name='NumAAA' size='15' type='text' value='<?php if($Modif){echo stripslashes($Row['NumAAA']);}?>'></td>
			</tr>
			<?php 
				}
			?>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Type de matériel";}else{echo "Kind of material";}?> : </td>
				<td>
					<select name="Id_TypeMateriel" id="Id_TypeMateriel" onclick="Change_TypeMateriel();">
					<?php
					$RequeteTypeMateriel="
						SELECT
							Id,
							Libelle
						FROM
							tools_typemateriel
						WHERE
							Suppr=0
							AND Id>0
						ORDER BY
							Libelle ASC";
					$ResultTypeMateriel=mysqli_query($bdd,$RequeteTypeMateriel);
					while($RowTypeMateriel=mysqli_fetch_array($ResultTypeMateriel))
					{
						echo "<option value='".$RowTypeMateriel['Id']."'";
						if($Modif){if($Row['ID_TYPEMATERIEL']==$RowTypeMateriel['Id']){echo " selected";}}
						echo ">".$RowTypeMateriel['Libelle']."</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Famille de matériel";}else{echo "Family of material";}?> : </td>
				<td>
					<input type="hidden" id="Id_FamilleMateriel_Initial" value="<?php if($Modif){echo $Row['ID_FAMILLEMATERIEL'];}?>">
					<div id="Div_FamilleMateriel">
						<select size="1" name="Id_FamilleMateriel" onclick="Change_FamilleMateriel();"></select>
					</div>
					<?php
					$RequeteFamilleMateriel="
						SELECT
							Id_TypeMateriel,
							Id,
							Libelle
						FROM
							tools_famillemateriel
						WHERE
							Suppr=0
						ORDER BY
							Libelle ASC";
					$ResultFamilleMateriel=mysqli_query($bdd,$RequeteFamilleMateriel);
					$i=0;
					while($RowFamilleMateriel=mysqli_fetch_array($ResultFamilleMateriel))
					{
						echo "<script>Liste_FamilleMateriel_TypeMateriel[".$i."] = new Array(".$RowFamilleMateriel['Id_TypeMateriel'].",".$RowFamilleMateriel['Id'].",'".addslashes($RowFamilleMateriel['Libelle'])."');</script>\n";
						$i+=1;
					}
					?>
				</td>
			</tr>
			<tr>
				<input type="hidden" id="Id_ModeleMateriel_Initial" value="<?php if($Modif){echo $Row['Id_ModeleMateriel'];}?>">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Modèle de matériel";}else{echo "Model of material";}?> : </td>
				<td>
					<div id="Div_ModeleMateriel">
						<select size="1" name="Id_ModeleMateriel"></select>
					</div>
					<?php
					$RequeteModeleMateriel="
						SELECT
							Id_FamilleMateriel,
							Id,
							Libelle
						FROM
							tools_modelemateriel
						WHERE
							Suppr=0
						ORDER BY
							Libelle ASC";
					$ResultModeleMateriel=mysqli_query($bdd,$RequeteModeleMateriel);
					$i=0;
					while($RowModeleMateriel=mysqli_fetch_array($ResultModeleMateriel))
					{
						echo "<script>Liste_ModeleMateriel_FamilleMateriel[".$i."] = new Array(".$RowModeleMateriel['Id_FamilleMateriel'].",".$RowModeleMateriel['Id'].",'".addslashes($RowModeleMateriel['Libelle'])."');</script>\n";
						$i+=1;
					}
					?>
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Fabricant";}else{echo "Manufacturer";}?> : </td>
				<td>
					<select name="Id_Fabricant">
						<option value="0"></option>
					<?php
					$RequeteFabricant="
						SELECT
							Id,
							Libelle
						FROM
							tools_tiers
						WHERE
							Type=1
							AND Suppr=0
						ORDER BY
							Libelle ASC";
					$ResultFabricant=mysqli_query($bdd,$RequeteFabricant);
					while($RowFabricant=mysqli_fetch_array($ResultFabricant))
					{
						echo "<option value='".$RowFabricant['Id']."'";
						if($Modif){if($Row['Id_Fabricant']==$RowFabricant['Id']){echo " selected";}}
						echo ">".$RowFabricant['Libelle']."</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Fournisseur";}else{echo "Provider";}?> : </td>
				<td>
					<select name="Id_Fournisseur">
						<option value="0"></option>
					<?php
					$RequeteFournisseur="
						SELECT
							Id,
							Libelle
						FROM
							tools_tiers
						WHERE
							Type=2
							AND Suppr=0
						ORDER BY
							Libelle ASC";
					$ResultFournisseur=mysqli_query($bdd,$RequeteFournisseur);
					while($RowFournisseur=mysqli_fetch_array($ResultFournisseur))
					{
						echo "<option value='".$RowFournisseur['Id']."'";
						if($Modif){if($Row['Id_Fournisseur']==$RowFournisseur['Id']){echo " selected";}}
						echo ">".$RowFournisseur['Libelle']."</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			
			<?php
			$IndiceLangue=1;
			if($LangueAffichage!="FR"){$IndiceLangue=2;}
			
			$i=0;
			foreach($Tableau_ChampsMateriel as &$TableauValeur) 
			{
				$ValeurModif="";
				if($Modif){
					if($TableauValeur[0]=="DateLettreEngagementMC"  || $TableauValeur[0]=="DateDerniereVerification"){
						$ValeurModif=AfficheDateFR($Row[$TableauValeur[0]]);
					}
					else{
						$ValeurModif=stripslashes($Row[$TableauValeur[0]]);
					}
					
				}
				
				$nbr="";
				if($TableauValeur[0]=="Prix" || $TableauValeur[0]=="PeriodiciteVerification"){$nbr="onKeyUp='nombre(this)'";}
				
				$type="text";
				if($TableauValeur[0]=="DateLettreEngagementMC"  || $TableauValeur[0]=="DateDerniereVerification"){$type="date";}
				
				echo "
					<tr id='Div_".$TableauValeur[0]."'>
						<td class='Libelle'>".$TableauValeur[$IndiceLangue]." : </td>
						<td><input ".$nbr." name='".$TableauValeur[0]."' size='".$TableauValeur[3]."' type='".$type."' value='".$ValeurModif."'></td>
					</tr>
				";
			}
			?>
			<tr class="TitreColsUsers">
				<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "Désignation";}else{echo "Designation";}?> : </td>
				<td><input name='Designation' size='40' type='text' value='<?php if($Modif){echo stripslashes($Row['Designation']);}?>'></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Achat / Location";}else{echo "Purchase / Rental";}?> : </td>
				<td>
					<select name="Location" id="Location" onclick="Change_Location();">
						<option value="0" <?php if($Modif){if($Row['Location']==0){echo " selected";}}?>><?php if($LangueAffichage=="FR"){echo "Achat";}else{echo "Purchase";}?></option>
						<option value="1" <?php if($Modif){if($Row['Location']==1){echo " selected";}}?>><?php if($LangueAffichage=="FR"){echo "Location";}else{echo "Rental";}?></option>
					</select>
				</td>
			</tr>
			<tr style="display:<?php if($Modif){if($Row['Location']==0){echo "none";}}else{echo "none";} ?>" id="Location1" >
				<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "Date début contrat<br> de location";}else{echo "Rental contract<br> start date";}?> : </td>
				<td><input name='DateDebutLocation' size='15' type='date' value='<?php if($Modif){echo stripslashes($Row['DateDebutLocation']);}?>'></td>
				<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "Date fin contrat de location";}else{echo "Rental contract end date";}?> : </td>
				<td><input name='DateFinLocation' size='15' type='date' value='<?php if($Modif){echo stripslashes($Row['DateFinContratLocation']);}?>'></td>
			</tr>
			<tr>
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Code article";}else{echo "Item code";}?> : </td>
				<td>
					<select name="Id_CodeArticle" id="Id_CodeArticle" onclick="Change_CodeArticle();">
						<option value="0"></option>
					<?php
					$RequeteCodeArticle="
						SELECT
							Id,CodeArticle,Immo
						FROM
							tools_codearticle
						WHERE
							Suppr=0
						ORDER BY
							CodeArticle ASC";
					$ResultCodeArticle=mysqli_query($bdd,$RequeteCodeArticle);
					while($RowCodeArticle=mysqli_fetch_array($ResultCodeArticle))
					{
						echo "<option value='".$RowCodeArticle['Id']."_".$RowCodeArticle['Immo']."'";
						if($Modif){if($Row['Id_CodeArticle']==$RowCodeArticle['Id']){echo " selected";}}
						echo ">".$RowCodeArticle['CodeArticle']."</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			<tr style="display:<?php if($Modif){if($Row['Immo']==0){echo "none";}}else{echo "none";} ?>" id="Immo1">
				<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "N° fiche immobilisation";}else{echo "Asset sheet no.";}?> : </td>
				<td><input name='NumFicheImmo' size='15' type='text' value='<?php if($Modif){echo stripslashes($Row['NumFicheImmo']);}?>'></td>
			</tr>
			<tr style="display:<?php if($Modif){if($Row['Immo']==0){echo "none";}}else{echo "none";} ?>" id="Immo2">
				<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "Date début <br>immobilisation";}else{echo "Start date <br>of immobilization";}?> : </td>
				<td><input name='DateDebutImmo' size='15' type='date' value='<?php if($Modif){echo stripslashes($Row['DateDebutImmo']);}?>'></td>
				<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "Date fin immobilisation";}else{echo "End date of immobilization";}?> : </td>
				<td><input name='DateFinImmo' size='15' type='date' value='<?php if($Modif){echo stripslashes($Row['DateFinImmo']);}?>'></td>
			</tr>
			<tr class="TitreColsUsers">
				<td class='Libelle'><?php if($LangueAffichage=="FR"){echo "N° facture";}else{echo "Invoice number";}?> : </td>
				<td><input name='NumFacture' size='15' type='text' value='<?php if($Modif){echo stripslashes($Row['NumFacture']);}?>'></td>
			</tr>
			<tr class="TitreColsUsers">
				<td class="Libelle" valign="top"><?php if($LangueAffichage=="FR"){echo "Remarques";}else{echo "Remarks";}?> : </td>
				<td><textarea name="Remarques" rows="5" cols="50" style="resize: none;"><?php if($Modif){echo stripslashes($Row['Remarques']);}?></textarea></td>

				<td class="Libelle" valign="top"><?php if($LangueAffichage=="FR"){echo "Infos techniques";}else{echo "Technical informations";}?> : </td>
				<td><textarea name="InfosTechnique" rows="5" cols="50" style="resize: none;"><?php if($Modif){echo stripslashes($Row['InfosTechnique']);}?></textarea></td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<?php 
				if($_GET['Mode']=="Ajout"){
			?>
			<tr class="TitreColsUsers" style="display:none;">
				<td class="Libelle" colspan="2">
					<input type="radio" name="affectation" onchange="AfficherAffectation('site')" value="site" checked><?php if($LangueAffichage=="FR"){echo "Site";}else{echo "Site";}?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="affectation" onchange="AfficherAffectation('personne')" value="personne"><?php if($LangueAffichage=="FR"){echo "Personne";}else{echo "Person";}?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="affectation" onchange="AfficherAffectation('caisse')" value="caisse"><?php if($LangueAffichage=="FR"){echo "Caisse";}else{echo "Box";}?>
				</td>
			</tr>		
			<tr style="display:none;">
				<td height="5"></td>
			</tr>
			<tr class="trPrestation">
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?></td>
				<td>
				<?php $Id_PrestaPole="0_0" ?>
					<select name="Id_Plateforme" id="Id_Plateforme" style="width:200px" onchange="RechargerPrestation()">
					<option value="0"></option>
						<?php
							$Id_Plateforme=$_SESSION['FiltreToolsSuivi_Plateforme'];

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
								AND Id_Plateforme IN (SELECT Id_Plateforme
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION["Id_Personne"]."
									AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteControleGestion.") 
								)
								ORDER BY Libelle ASC";

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
								AND (SELECT COUNT(Id) 
									FROM tools_lieu 
									WHERE Suppr=0 
									AND tools_lieu.Id_Prestation=new_competences_prestation.Id
									AND Libelle LIKE 'Magasin%')>0
								UNION 
								
								SELECT DISTINCT new_competences_pole.Id_Prestation, new_competences_prestation.Libelle,
									new_competences_pole.Libelle AS LibellePole, new_competences_pole.Id AS Id_Pole, new_competences_prestation.Id_Plateforme
									FROM new_competences_pole
									INNER JOIN new_competences_prestation
									ON new_competences_pole.Id_Prestation=new_competences_prestation.Id
									AND Active=0
									AND Actif=0
									AND (SELECT COUNT(Id) 
										FROM tools_lieu 
										WHERE Suppr=0 
										AND tools_lieu.Id_Prestation=new_competences_prestation.Id
										AND tools_lieu.Id_Pole=new_competences_pole.Id
										AND Libelle LIKE 'Magasin%')>0
									
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
								AND Libelle LIKE 'Magasin%'
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
				<td class="Libelle"><?php if($LangueAffichage=="FR"){echo "Unité d'exploitation :";}else{echo "Operating unit :";} ?></td>
				<td>
				<?php $Id_PrestaPole="0_0" ?>
					<select name="Id_PlateformePersonne" id="Id_PlateformePersonne" style="width:200px" onchange="RechargerPersonne()">
					<option value="0"></option>
						<?php
							$Id_PlateformePersonne=$_SESSION['FiltreToolsSuivi_Plateforme'];
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
								AND Id_Plateforme IN (SELECT Id_Plateforme
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION["Id_Personne"]."
									AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteControleGestion.") 
								)
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
			<tr class="trPersonne">
				<td class="Libelle"><?php if($_SESSION["Langue"]=="FR"){echo "Personne :";}else{echo "Person :";} ?></td>
				<td>
					<select name="Id_Personne" id="Id_Personne">
					<?php
					$rq="SELECT Id, Personne, Id_Plateforme
						FROM 
						(
						SELECT DISTINCT new_rh_etatcivil.Id, CONCAT (new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS Personne,
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
						AND Id_Plateforme IN (SELECT Id_Plateforme
							FROM new_competences_personne_poste_plateforme
							WHERE Id_Personne=".$_SESSION["Id_Personne"]."
							AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteControleGestion.") 
						)
						ORDER BY Personne ASC";
					$resultpersonne=mysqli_query($bdd,$rq);
					$Id_Personne=0;
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
				<?php $Id_PrestaPole="0_0" ?>
					<select name="Id_PlateformeCaisse" id="Id_PlateformeCaisse" style="width:200px" onchange="RechargerCaisse()">
						<?php
							$Id_PlateformeCaisse=$_SESSION['FiltreToolsSuivi_Plateforme'];
							$requetePlat="
								SELECT Id_Plateforme AS Id,
								(SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle
								FROM (
								SELECT DISTINCT 
								(SELECT (SELECT Id_Plateforme FROM new_competences_prestation WHERE Id=Id_Prestation) FROM tools_mouvement WHERE TypeMouvement=0 AND EtatValidation IN (1) AND Suppr=0 AND tools_mouvement.Type=1 AND tools_mouvement.Id_Materiel__Id_Caisse=tools_caisse.Id ORDER BY DateReception DESC, Id DESC LIMIT 1) AS Id_Plateforme
								FROM tools_caisse 
								WHERE Suppr=0
								) AS TAB
								WHERE Id_Plateforme NOT IN (11,14)
								AND Id_Plateforme IN (SELECT Id_Plateforme
									FROM new_competences_personne_poste_plateforme
									WHERE Id_Personne=".$_SESSION["Id_Personne"]."
									AND Id_Poste IN (".$IdPosteGestionnaireMGX.",".$IdPosteResponsableMGX.",".$IdPosteInformatique.",".$IdPosteControleGestion.") 
								)
								ORDER BY Libelle ASC";

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
					$i=0;
					while($rowCaisse=mysqli_fetch_array($resultcaisse))
					{
						$selected="";
						if($Id_Caisse==0){$Id_Caisse=$rowCaisse['Id'];$selected = "selected";}
						echo "<option value='".$rowCaisse['Id']."' ".$selected.">"."n° ".$rowCaisse['Num']." ".str_replace("'"," ",$rowCaisse['CaisseType'])."</option>\n";
						echo "<script>Liste_Caisse[".$i."] = new Array(".$rowCaisse['Id'].",'".str_replace("'"," ",$rowCaisse['CaisseType'])."','".$rowCaisse['Num']."',".$rowCaisse['Id_Plateforme'].");</script>";
						$i++;
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<?php 
				}
			?>
			<tr>
				<td colspan=4 align="center">
					<input class="Bouton" type="submit"
					<?php
						if($Modif)
						{
							if($LangueAffichage=="FR"){echo "value='Valider'";}else{echo "value='Validate'";}
						}
						else
						{
							if($LangueAffichage=="FR"){echo "value='Ajouter'";}else{echo "value='Add'";}
						}
					?>
					>
				</td>
			</tr>
		</table>
		</form>
<?php
	}
	else
	//Mode suppression
	{
		$Result=mysqli_query($bdd,"UPDATE ".$TablePrincipale." SET Suppr=1 WHERE Id=".$_GET['Id']);
		echo "<script>FermerEtRecharger('".$_GET['Page']."');</script>";
	}
	if($_GET['Id']!='0'){mysqli_free_result($Result);}	// Libération des résultats}
	
	echo "<script>RechargerPrestation();</script>";
	echo "<script>RechargerPersonne();</script>";
	echo "<script>RechargerCaisse();</script>";
	echo "<script>AfficherAffectation('site');</script>";
	mysqli_close($bdd);			// Fermeture de la connexion
}
	
?>
	
	<script>Change_TypeMateriel();</script>
	
</body>
</html>
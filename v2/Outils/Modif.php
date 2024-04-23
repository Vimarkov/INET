<!DOCTYPE html>
<html>
<head>
	<title>Modification</title><meta name="robots" content="noindex">
	<link href="../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="JS/colorpicker.css" rel="stylesheet">
	<script>
		function VerifChamps()
		{
			if(formulaire.titre.value==''){alert('Vous n\'avez pas renseigné le titre.');return false;}
			else
			{
				if(formulaire.fichier.value!='' && formulaire.nomfichier.value==''){alert('Vous n\'avez pas renseigné le nom du fichier.');return false;}
				else{return true;}
			}
		}
		
		function CheckImage(){if(formulaire.image.value!=''){formulaire.SupprImage.checked=true;}}
		function CheckFichier(){if(formulaire.fichier.value!=''){formulaire.SupprFichier.checked=true;}}
		
		function ChangeTexte(TypeChangement)
		{
			var textarea = document.getElementById("contenu");

			// code for Mozilla
			var len = textarea.value.length;
			var start = textarea.selectionStart;
			var end = textarea.selectionEnd;
			var sel = textarea.value.substring(start, end);
			switch(TypeChangement)
			{
				case "Gras": var replace = '<b>' + sel + '</b>';break;
				case "Italique": var replace = '<i>' + sel + '</i>';break;
				case "Souligne": var replace = '<u>' + sel + '</u>';break;
				case "Couleur": var replace = '<font color=#' + document.getElementById('color-picker').value + '>' + sel + '</font>';break;
			}
			textarea.value =  textarea.value.substring(0,start) + replace + textarea.value.substring(end,len);
		}
		
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
	</script>
	<script src="JS/modernizr.js"></script>
	<script src="JS/webforms2/webforms2-p.js"></script>	
	<script src="JS/js/jquery-1.4.3.min.js"></script>
	<script src="JS/js/jquery-ui-1.8.5.min.js"></script>
	<script src="JS/colorpicker.js"></script>
</head>
<?php
session_start();	//require("VerifPage.php");
require("Connexioni.php");
if($_POST)
{
?>
<table style="width:95%; height:95%;">
	<tr>
		<td valign="middle" class="PoliceModif">
<?php
	//RECUPERATION DES NOMS FRANCAIS DES DIFFERENTS REPERTOIRES
	$Page="";
	$Repertoire1="";
	$Repertoire2="";
	switch($_POST['page'])
	{
		case "news": $Page="Vie Quotidienne";
			switch($_POST['dossier1'])
			{
				case "LettreInterne": $Repertoire1="Lettre interne";break;
				case "PresentationCadres": $Repertoire1="Présentation des cadres";break;
				case "PresentationSites": $Repertoire1="Présentation des sites";break;
				case "SyntheseGifas": $Repertoire1="Synthèse presse GIFAS";break;
				case "MotPresident": $Repertoire1="Mot du Président";break;
				case "Tarbes2008": $Repertoire1="Tarbes 2008";break;
			}
			break;
		case "qualite": $Page="Système Qualité";
			switch($_POST['dossier1'])
			{
				case "Reporting": $Repertoire1="Reporting trimestriel";break;
				case "PlanAction": $Repertoire1="Plan d'actions";break;
			}
			break;
		case "rh": $Page="Ressources Humaines";
			switch($_POST['dossier1'])
			{
				case "ReleveHeures": $Repertoire1="Relevé d'heures";break;
				case "Effectif": $Repertoire1="Effectif";break;
				case "GestionCompetences": $Repertoire1="Gestion des compétences";break;
				case "Emploi": $Repertoire1="Recherche de personnel";break;
				case "DemandeCDI": $Repertoire1="Demande CDI";break;
				case "NouveauVenu": $Repertoire1="Nouveau venu";break;
				case "AccordEntreprise": $Repertoire1="Accords entreprises";break;
				case "ConventionCollective": $Repertoire1="Convention collective";break;
				case "DemandeFormation": $Repertoire1="Demande de formations";break;
				case "DemandeConges": $Repertoire1="Demande de congés";break;
				case "VeilleJuridique": $Repertoire1="Veille juridique";break;
				case "PlanHandicap": $Repertoire1="Plan handicap 2007-2010";break;
				case "EntretiensProfessionnels": $Repertoire1="Entretiens Professionnels";break;
			}
			break;
		case "hse": $Page="Hygiène, Sécurité et Environnement";
			switch($_POST['dossier1'])
			{
				case "ReglementInterieur": $Repertoire1="Règlement interieur";break;
				case "ManuelSecurite": $Repertoire1="Manuel Sécurité";break;
				case "DocumentsUniques": $Repertoire1="Document unique";break;
				case "PlanAction": $Repertoire1="Plan d'actions";break;
				case "CatalogueEPI": $Repertoire1="Catalogue EPI";break;
				case "LiensCHSCT": $Repertoire1="Liens CHSCT";break;
				default: $Repertoire1=$_POST['dossier1'];
			}
			break;
		case "financier": $Page="Financier";
			switch($_POST['dossier1'])
			{
				case "EnCours": $Repertoire1="Les en-cours";break;
				case "Budget": $Repertoire1="Budget/Consommations";break;
				default: $Repertoire1=$_POST['dossier1'];
			}
			break;
		case "achats": $Page="Achats";
			switch($_POST['dossier1'])
			{
				case "DemandeInvestissement": $Repertoire1="Demande d'investissement";break;
				case "ParcInformatique": $Repertoire1="Parc informatique";break;
				case "FichierFournisseurs": $Repertoire1="Fichier des fournisseurs";break;
			}
			break;
		case "cedpchsct": $Page="CE / DP / CHSCT";
			switch($_POST['dossier1'])
			{
				case "Presentation": $Repertoire1="Présentation";break;
				case "CE": $Repertoire1="Comité d'entreprise";break;
				case "DP": $Repertoire1="Délégués du personnel";break;
				case "CHSCT": $Repertoire1="CHSCT";break;
				case "Activites": $Repertoire1="Activités sociales et culturelles";break;
			}
			break;
	}
	if($_POST['dossier2']!="")
	{
		switch($_POST['dossier2'])
		{
			case "ListeElus": $Repertoire2="Liste des élus";break;
			case "ComptesRendus": $Repertoire2="Comptes rendus";break;
			case "Saint-Pe": $Repertoire2="Saint-Pé";break;
			case "RegionOuest": $Repertoire2="Région Ouest";break;
			case "RegionNord": $Repertoire2="Région Nord";break;
			case "Nautique": $Repertoire2="Activités nautiques";break;
			case "General": $Repertoire2="Général";break;
			case "SiegeSocial": $Repertoire2="Siège social";break;
			case "Reglementation": $Repertoire2="Réglementation et management de la sécurité";break;
			case "DocumentUnique": $Repertoire2="Document Unique";break;
			case "AT": $Repertoire2="AT - Médecine du travail";break;
			case "GestionEntreprises": $Repertoire2="Gestion des entreprises extérieures";break;
			case "SignalisationHSE": $Repertoire2="Signalisation HSE";break;
			case "RisqueChimique": $Repertoire2="Risque chimique";break;
			case "EquipementsProtection": $Repertoire2="Equipement de protection";break;
			case "ConsignesSecurite": $Repertoire2="Consignes de sécurité - Fiches de risques";break;
			default: $Repertoire2=$_POST['dossier2'];
		}
	}

	
	//RECUPERATION VARIABLES FICHIERS ET IMAGES
	if(isset($_POST['fichieractuel'])){$Fichier=$_POST['fichieractuel'];}
	else{$Fichier="";}
	if(isset($_POST['nomfichier'])){$NomFichier=$_POST['nomfichier'];}
	else{$NomFichier="";}
	if(isset($_POST['imageactuelle'])){$Image=$_POST['imageactuelle'];}
	else{$Image="";}
	$NomImage=$_POST['nomimage'];
	if($_POST['dossier2']=="")
	{
		$DirImage=$CheminUpload."Images/".$_POST['page']."/".$_POST['dossier1']."/";
		$DirFichier=$CheminUpload."Fichiers/".$_POST['page']."/".$_POST['dossier1']."/";
	}
	else
	{
		$DirImage=$CheminUpload."Images/".$_POST['page']."/".$_POST['dossier1']."/".$_POST['dossier2']."/";
		$DirFichier=$CheminUpload."Fichiers/".$_POST['page']."/".$_POST['dossier1']."/".$_POST['dossier2']."/";
	}
	$SrcProblem="";
	$Problem=0;
	$ImageTransfert=0;
	$FichierTransfert=0;
	
	//-------EN MODE AJOUT-------
	//###########################
	if($_POST['mode']=="Ajout")
	{
		//****TRANSFERT IMAGE****
		if($_FILES['image']['name']!="")
		{
			$tmp_file=$_FILES['image']['tmp_name'];
			if(!is_uploaded_file($tmp_file)){$SrcProblem.="<br>Le fichier image est introuvable.";$Problem=1;$NomImage="";}
			else
			{
				// on vérifie maintenant l'extension
				$type_file=$_FILES['image']['type'];
				if(!strstr($type_file,'jpg') && !strstr($type_file,'jpeg') && !strstr($type_file,'bmp') && !strstr($type_file,'gif') && !strstr($type_file,'png'))
					{$SrcProblem.="<br>Le fichier image n'est pas une image.";$Problem=1;$NomImage="";}
				else
				{
					//On vérifie la taille du fichiher
					if(filesize($_FILES['image']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
						{$SrcProblem.="<br>Le fichier image est trop volumineux.";$Problem=1;$NomImage="";}
					else
					{
						// on copie le fichier dans le dossier de destination
						$name_file=$_FILES['image']['name'];
						$name_file=strtr($name_file, "@àäâöôéèëêîïùüûñç &()[]+*'\\°", "aaaaooeeeeiiuuunc___________");
						while(file_exists($DirImage.$name_file)){$name_file="le ".date('j-m-y')." a ".date('H-i-s')." ".$name_file;}
						if(!move_uploaded_file($tmp_file,$DirImage.$name_file))
							{$SrcProblem.="<br>Impossible de copier le fichier image.";$Problem=1;$NomImage="";}
						else{$Image=$name_file;$NomImage=$_POST['nomimage'];$ImageTransfert=1;}
					}
				}
			}
		}
		//****TRANSFERT FICHIER****
		if($_FILES['fichier']['name']!="")
		{
			$tmp_file=$_FILES['fichier']['tmp_name'];
			if(!is_uploaded_file($tmp_file)){$SrcProblem.="<br>Le fichier est introuvable.";$Problem=1;$NomFichier="";}
			else
			{
				//On vérifie la taille du fichiher
				if(filesize($_FILES['fichier']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
					{$SrcProblem.="<br>Le fichier est trop volumineux.";$Problem=1;$NomFichier="";}
				else
				{
					// on copie le fichier dans le dossier de destination
					$name_file=$_FILES['fichier']['name'];
					$name_file=strtr($name_file, "@àäâöôéèëêîïùüûñç &()[]+*'\\°", "aaaaooeeeeiiuuunc___________");
					while(file_exists($DirFichier.$name_file)){$name_file="le ".date('j-m-y')." a ".date('H-i-s')." ".$name_file;}
					if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
						{$SrcProblem.="<br>Impossible de copier le fichier.";$Problem=1;$NomFichier="";}
					else{$Fichier=$name_file;$NomFichier=$_POST['nomfichier'];$FichierTransfert=1;}
				}
			}
		}
		
		$requete="INSERT INTO new_".$_POST['page']." (Titre,Contenu,Auteur,Date,Image,NomImage,Fichier,NomFichier,Dossier1,Dossier2)";
		$requete.=" VALUES ('".addslashes($_POST['titre'])."','".addslashes($_POST['contenu'])."','".addslashes($_SESSION['Log']);
		$requete.="','".$_POST['dateajout']."','".$Image."','".addslashes($NomImage)."','";
		$requete.=$Fichier."','".addslashes($NomFichier)."','".$_POST['dossier1']."','".$_POST['dossier2']."')";
	}
	
	//-------EN MODE MODIF-------
	//###########################
	else
	{
		//S'il y avait une image
		if(isset($_POST['SupprImage']))
			{if($_POST['SupprImage'])
				{
				if(!unlink($DirImage.$_POST['imageactuelle'])){$SrcProblem.="<br>Impossible de supprimer le fichier image.";$Problem=1;}
				elseif($ImageTransfert==0){$Image="";$NomImage="";}
				}
			}
		//S'il y avait une fichier
		if(isset($_POST['SupprFichier']))
			{if($_POST['SupprFichier'])
				{
					if(!unlink($DirFichier.$_POST['fichieractuel'])){$SrcProblem.="<br>Impossible de supprimer le fichier.";$Problem=1;}
					elseif($FichierTransfert==0){$Fichier="";$NomFichier="";}
				}
			}
		
		//****TRANSFERT IMAGE****
		if($_FILES['image']['name']!="")
		{
			$tmp_file=$_FILES['image']['tmp_name'];
			if(!is_uploaded_file($tmp_file)){$SrcProblem.="<br>Le fichier image est introuvable.";$Problem=1;$NomImage="";}
			else
			{
				// on vérifie maintenant l'extension
				$type_file=$_FILES['image']['type'];
				if(!strstr($type_file,'jpg') && !strstr($type_file,'jpeg') && !strstr($type_file,'bmp') && !strstr($type_file,'gif') && !strstr($type_file,'png'))
					{$SrcProblem.="<br>Le fichier image n'est pas une image.";$Problem=1;$NomImage="";}
				else
				{
					//On vérifie la taille du fichiher
					if(filesize($_FILES['image']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
						{$SrcProblem.="<br>Le fichier image est trop volumineux.";$Problem=1;$NomImage="";}
					else
					{
						// on copie le fichier dans le dossier de destination
						$name_file=$_FILES['image']['name'];
						$name_file=strtr($name_file, "@àäâöôéèëêîïùüûñç &()[]+*'\\°", "aaaaooeeeeiiuuunc___________");
						while(file_exists($DirImage.$name_file)){$name_file="le ".date('j-m-y')." a ".date('H-i-s')." ".$name_file;}
						if(!move_uploaded_file($tmp_file,$DirImage.$name_file))
							{$SrcProblem.="<br>Impossible de copier le fichier image.";$Problem=1;$NomImage="";}
						else{$Image=$name_file;$NomImage=$_POST['nomimage'];$ImageTransfert=1;}
					}
				}
			}
		}
		//****TRANSFERT FICHIER****
		if($_FILES['fichier']['name']!="")
		{
			$tmp_file=$_FILES['fichier']['tmp_name'];
			if(!is_uploaded_file($tmp_file)){$SrcProblem.="<br>Le fichier est introuvable.";$Problem=1;$NomFichier="";}
			else
			{
				//On vérifie la taille du fichiher
				if(filesize($_FILES['fichier']['tmp_name'])>$_POST['MAX_FILE_SIZE'])
					{$SrcProblem.="<br>Le fichier est trop volumineux.";$Problem=1;$NomFichier="";}
				else
				{
					// on copie le fichier dans le dossier de destination
					$name_file=$_FILES['fichier']['name'];
					$name_file=strtr($name_file, "@àäâöôéèëêîïùüûñç &()[]+*'\\°", "aaaaooeeeeiiuuunc___________");
					while(file_exists($DirFichier.$name_file)){$name_file="le ".date('j-m-y')." a ".date('H-i-s')." ".$name_file;}
					if(!move_uploaded_file($tmp_file,$DirFichier.$name_file))
						{$SrcProblem.="<br>Impossible de copier le fichier.";$Problem=1;$NomFichier="";}
					else{$Fichier=$name_file;$NomFichier=$_POST['nomfichier'];$FichierTransfert=1;}
				}
			}
		}
		
		$requete="UPDATE new_".addslashes($_POST['page'])." SET Titre='".addslashes($_POST['titre'])."',Contenu='".addslashes($_POST['contenu']);
		$requete.="',Date='".date("Y-m-d")."',Image='".$Image."',NomImage='".addslashes($NomImage);
		$requete.="',Fichier='".$Fichier."',NomFichier='".addslashes($NomFichier)."' WHERE Id='".$_POST['id']."'";
	}
	$result=mysqli_query($bdd,$requete);
	
	if($Problem==1)
	{
		echo "<script>opener.location.reload();</script>";
		echo $SrcProblem;
		if($_POST['mode']=="Ajout")
			{echo "<br><a class='".$_POST['page']."' href='Modif.php?Mode=Modif&Page=".$_POST['page']."&Id=".mysqli_insert_id($bdd)."'>Modifier</a>";}
		else{echo "<br><a class='".$_POST['page']."' href='Modif.php?Mode=Modif&Page=".$_POST['page']."&Id=".$_POST['id']."'>Modifier</a>";}
	}
	else
	{
		//MAIL
		if($_POST['mails'])
		{
			$Destinataires=$_POST['mails'];
			$Destinataire1="";
			$AutresDestinataires="";
			$TableauDestinataires=explode(";",$Destinataires);
			for($i=0;$i<sizeof($TableauDestinataires);$i++)
			{
				if($i==0){$Destinataire1=$TableauDestinataires[$i];}
				else{$AutresDestinataires.=$TableauDestinataires[$i].",";}
			}
			$headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
			$headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
			if($AutresDestinataires!=""){$headers .='Cc: '.substr($AutresDestinataires,0,strlen($AutresDestinataires)-1)."\n";}
			$message='<html><head><title>Ajout ou modification dans l"Extranet AAA</title></head><body>Bonjour,<br><br>';
			if($_POST['mode']=="Ajout"){$Texte="ajoutée";}else{$Texte="modifiée";}
			$message.='Une information a été '.$Texte.' dans la rubrique '.$Page.'/'.$Repertoire1;
			if($Repertoire2!=""){$message.='/'.$Repertoire2;}
			$message.=' le '.$_POST['dateajout'].' à '.date("H:i").' par '.$_SESSION['Nom']." ".$_SESSION['Prenom'];
			$message.='<br><br>Bonne journée.</body></html>';
	
			if(mail($Destinataire1, 'Ajout ou modification dans l"Extranet AAA', $message, $headers,'-f extranet@aaa-aero.com')){echo 'Le message a été envoyé';}
			else{echo 'Le message n\'a pu être envoyé';}
		}
		
		echo "<script>FermerEtRecharger();</script>";
	}
?>
		</td>
	</tr>
</table>
<?php
}
elseif($_GET)
{
    if($_GET['Mode']=="Suppr")
    {
    ?>
    <table style="width:95%; height:95%;">
    	<tr>
    		<td valign="middle" class="PoliceModif">
    <?php
    	//------MODE SUPPRESSION-----
    	//###########################
    	$SrcProblem="";
    	if($_GET['Dossier2']=="")
    	{
    		$DirImage=$CheminUpload."Images/".$_GET['Page']."/".$_GET['Dossier1']."/";
    		$DirFichier=$CheminUpload."Fichiers/".$_GET['Page']."/".$_GET['Dossier1']."/";
    	}
    	else
    	{
    		$DirImage=$CheminUpload."Images/".$_GET['Page']."/".$_GET['Dossier1']."/".$_GET['Dossier2']."/";
    		$DirFichier=$CheminUpload."Fichiers/".$_GET['Page']."/".$_GET['Dossier1']."/".$_GET['Dossier2']."/";
    	}
    	$Problem=0;
    	
    	$result=mysqli_query($bdd,"SELECT * FROM new_".$_GET['Page']." WHERE Id=".$_GET['Id']);
    	$row=mysqli_fetch_array($result);
    	
    	if($row['Image']!="")
    		{if(!unlink($DirImage.$row['Image'])){$SrcProblem.="<br>Impossible de supprimer le fichier image.";$Problem=1;}}
    	if($row['Fichier']!="")
    		{if(!unlink($DirFichier.$row['Fichier'])){$SrcProblem.="<br>Impossible de supprimer le fichier.";$Problem=1;}}
    	
    	if($Problem==1)
    	{
    		echo $SrcProblem;
    		echo "<br><a class='".$_POST['page']."' href='Modif.php?Mode=Modif&Page=".$_POST['page']."&Id=".$_POST['id']."'>Modifier</a>";
    	}
    	else
    	{
    		$result2=mysqli_query($bdd,"DELETE FROM new_".$_GET['Page']." WHERE Id=".$_GET['Id']);
    		if(!$result2){mysqli_free_result($result2);}	// Libération des résultats
    		echo "<script>FermerEtRecharger();</script>";
    	}
    ?>
    		</td>
    	</tr>
    </table>
    <?php
    }
    else
    {
    	$result=mysqli_query($bdd,"SELECT * FROM new_".$_GET['Page']." WHERE Id=".$_GET['Id']);
    	$row=mysqli_fetch_array($result);
    ?>
      <!-- Script COLOR  -->
    <script>
    	var initColorpicker = function() {  
    	$('input[type=color]').each(function() {  
    		var $input = $(this);  
    		$input.ColorPicker({  
    			onSubmit: function(hsb, hex, rgb, el) {  
    				$(el).val(hex);  
    				$(el).ColorPickerHide();  
    			}  
    		});  
    	});  
    	};  
    
    	if(!Modernizr.inputtypes.color){$(document).ready(initColorpicker);};
    </script>
    <form id="formulaire" enctype="multipart/form-data" method="POST" action="Modif.php" onSubmit="return VerifChamps();">
    	<input type="hidden" name="page" value="<?php echo $_GET['Page']; ?>">
    	<?php
    		if(isset($_GET['Dossier2'])){$Dossier2=$_GET['Dossier2'];}
    		else{$Dossier2="";}
    		$Dossier1=$_GET['Dossier1'];
    	?>
    	<input type="hidden" name="dossier1" value="<?php echo $Dossier1; ?>">
    	<input type="hidden" name="dossier2" value="<?php echo $Dossier2; ?>">
    	<!-- On limite le fichier à 10Mo -->
    	<?php
    		if($Dossier1=="AAA-GMBH" && $Dossier2=="TLS"){
    	?>
    		<input type="hidden" name="MAX_FILE_SIZE" value="160000000">
    	<?php
    		}
    		else{
    	?>
    		<input type="hidden" name="MAX_FILE_SIZE" value="100000000">
    	<?php
    		}
    	?>
        
    	<input type="hidden" name="id" value="<?php echo $_GET['Id']; ?>">
    	<input type="hidden" name="mode" value="<?php echo $_GET['Mode']; ?>">
    	<table style="align:center; width:100%; cellpadding:0; cellspacing:0; height:100%;">
    		<tr>
    			<td>
    				<table style="width:100%;">
    					<tr>
    						<td class="PoliceModif">Titre : </td>
    						<td>
    							<input name="titre" size="70" type="text" value="<?php if($_GET['Mode']=="Modif"){echo stripslashes($row['Titre']);}?>">
    							<input name="dateajout" type="hidden" value="<?php if($_GET['Mode']=="Modif"){echo $row['Date'];}else{echo date("Y/m/d");}?>">
    						</td>
    					</tr>
    				</table>
    			</td>
    		</tr>
    		<tr>
    			<td>
    				<table style="width:100%;">
    					<tr>
    						<td class="PoliceModif">Contenu : </td>
    						<td width=10><img src="../Images/Gras.gif" title="Sélectionner le texte à mettre en gras puis cliquer" onclick="ChangeTexte('Gras');"></td>
    						<td width=10><img src="../Images/Italique.gif" title="Sélectionner le texte à mettre en italique puis cliquer" onclick="ChangeTexte('Italique');"></td>
    						<td width=10><img src="../Images/Souligne.gif" title="Sélectionner le texte à mettre en souligné puis cliquer" onclick="ChangeTexte('Souligne');"></td>
    						<td width=160><label for="color-picker">Couleur</label><input type="color" name="color-picker" id="color-picker" value=""></td>
    						<td width=10><img src="../Images/Pipette.gif" title="Sélectionner le texte et la couleur puis cliquer" onclick="ChangeTexte('Couleur');"></td>
    					</tr>
    					<tr><td align="right" colspan=6>
    						<textarea id="contenu" name="contenu" rows="30" cols="95"><?php if($_GET['Mode']=="Modif"){echo stripslashes($row['Contenu']);}?></textarea>
    					</td></tr>
    				</table>
    			</td>
    		</tr>
    		<tr>
    			<td>
    				<table style="width:100%;">
    					<tr>
    						<td class="PoliceModif">Image : </td>
    						<td><input name="image" type="file" onChange="CheckImage();"></td>
    						<td class="PoliceModif">Titre image : </td>
    						<td align="right">
    							<input name="nomimage" size="25" type="text"
    							value="<?php if($_GET['Mode']=="Modif"){echo stripslashes($row['NomImage']);}?>">
    						</td>
    					</tr>
    					<?php
    					if($_GET['Mode']=="Modif" && $row['Image']!="")
    					{
    					?>
    					<tr>
    						<td>
    							<?php 
    								if($Dossier2!=""){$CheminImage=$CheminOuvrirUpload."Images/".$_GET['Page']."/".$Dossier1."/".$Dossier2."/".$row['Image'];}
    								else{$CheminImage=$CheminOuvrirUpload."Images/".$_GET['Page']."/".$Dossier1."/".$row['Image'];}
    							?>
    							<img src="<?php echo $CheminImage; ?>" height="50" width="50" border="0">
    							<input type="hidden" name="imageactuelle" value="<?php echo $row['Image'];?>">
    						</td>
    						<td class="PoliceModif"><input type="checkbox" name="SupprImage" onClick="CheckImage();">Supprimer l'image</td>
    					</tr>
    					<?php
    					}
    					?>
    					<tr>
    						<td class="PoliceModif">Fichier : </td>
    						<td><input name="fichier" type="file" onChange="CheckFichier();"></td>
    						<td class="PoliceModif">Titre fichier : </td>
    						<td align="right">
    							<input name="nomfichier" size="25" type="text"
    							value="<?php if($_GET['Mode']=="Modif"){echo stripslashes($row['NomFichier']);}?>">
    						</td>
    					</tr>
    					<?php
    					if($_GET['Mode']=="Modif" && $row['Fichier']!="")
    					{
    					?>
    					<tr>
    						<td>
    							<?php
    								if($Dossier2!=""){$CheminFichier=$CheminOuvrirUpload."Fichiers/".$_GET['Page']."/".$Dossier1."/".$Dossier2."/".$row['Fichier'];}
    								else{$CheminFichier=$CheminOuvrirUpload."Fichiers/".$_GET['Page']."/".$Dossier1."/".$row['Fichier'];}
    							?>
    							<a class="Info" href="<?php echo $CheminFichier; ?>" target="_blank">_Ouvrir_</a>
    							<input type="hidden" name="fichieractuel" value="<?php echo $row['Fichier'];?>">
    						</td>
    						<td class="PoliceModif"><input type="checkbox" name="SupprFichier" onClick="CheckFichier();">Supprimer le fichier</td>
    					</tr>
    					<?php
    					}
    					?>
    					<?php
    						if($Dossier1=="AAA-GMBH" && $Dossier2=="TLS"){
    					?>
    						<tr><td colspan="4"><font color="#FF0000" size="-2">Limite de taille des fichiers et images à 16 Mo.</font></td></tr>
    					<?php
    						}
    						else{
    					?>
    						<tr><td colspan="4"><font color="#FF0000" size="-2">Limite de taille des fichiers et images à 10 Mo.</font></td></tr>
    					<?php
    						}
    					?>
    				</table>
    			</td>
    		</tr>
    		<tr>
    			<td>
    				<table style="width:100%;">
    					<tr>
    						<td class="PoliceModif">Prévenir la ou les personnes suivantes de votre ajout ou modification par mail : </td>
    						<td><textarea name="mails" cols="53" rows="2" title="Délimiter par des points-virgules"></textarea></td>
    					</tr>
    				</table>
    			</td>
    		</tr>
    		<tr>
    			<td align="center" valign="bottom">
    				<input class="Bouton" type="submit" 
    				<?php
    					if($_GET['Mode']=="Modif"){echo "value='Valider'";}
    					else{echo "value='Ajouter'";}
    				?>
    				>
    			</td>
    		</tr>
    	</table>
    </form>
    <?php
    }
}
    ?>
</body>
</html>
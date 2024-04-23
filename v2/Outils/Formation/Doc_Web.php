<!DOCTYPE html>

<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
?>

<html>
    <head>
    	<title>Formations - Document WEB</title><meta name="robots" content="noindex">
    	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
    	<script type="text/javascript" src="Fonctions.js"></script>
    	<script type="text/javascript">
    	function VerifChamps()
    	{
			var Tableau=document.getElementById("listeChampsAVerifier").value.split("|");
			for(var j=0;j<Tableau.length;j++)
 			{
				var radios = document.getElementsByName(Tableau[j]);
				var valeur = -1;
				if(radios.length>0){
					for(var i = 0; i < radios.length; i++){
						 if(radios[i].checked){
						 valeur = radios[i].value;
						 }
					}
					if(valeur == 1 || valeur == 2 || valeur == 3){
						if(document.getElementById("reponse_"+Tableau[j].substr(5)).value==""){
							alert("Merci de mettre un commentaire (note <=3) ");
							return false;
						}
					}
					if(valeur == -1){
						alert("Merci de répondre à toutes les questions");
						return false;
					}
				}
			}
    		return true;
    	}

    	function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
		function OuvreExcel(Id,Id_Doc_Langue){
			var w=window.open("Doc_Extract.php?Id_Session_Personne_Document="+Id+"&Id_Doc_Langue="+Id_Doc_Langue,"PageDoc","status=no,menubar=no,width=50,height=50");
			w.focus();
		}
		function OuvreDocument(NomDocumentPHP,Id_Session_Personne_Document){
			var w=window.open("Document_Modele/"+NomDocumentPHP+"?Id_Session_Personne_Document="+Id_Session_Personne_Document,"PageDocumentExcel","status=no,menubar=no,width=50,height=50");
			w.focus();
		}
    	</script>
    </head>
<?php
$DirFichier="https://extranet.aaa-aero.com/v2/Outils/Formation/Docs/";
if($_POST)
{
	$Doc_Q_R_RStagiaires=Generer_Document($_POST['Id_DocLangue'], $_POST['Id_Session_Personne_Document']);
	foreach($Doc_Q_R_RStagiaires as $Ligne_Q_R_RStagiaires){
		$Valeur="";
		$Texte="";
		if($Ligne_Q_R_RStagiaires[3]=="Note (1 à 6)"){
			$Valeur=$_POST['note_'.$Ligne_Q_R_RStagiaires[0]];
			$Texte=$_POST['reponse_'.$Ligne_Q_R_RStagiaires[0]];
		}
		elseif($Ligne_Q_R_RStagiaires[3]=="Oui/Non"){
			$Valeur=$_POST['note_'.$Ligne_Q_R_RStagiaires[0]];
		}
		elseif($Ligne_Q_R_RStagiaires[3]=="Texte facultatif" || $Ligne_Q_R_RStagiaires[3]=="Texte obligatoire"){
			$Valeur=$_POST['reponse_'.$Ligne_Q_R_RStagiaires[0]];
			$Texte=$_POST['reponse_'.$Ligne_Q_R_RStagiaires[0]];
		}
		$ReqInsertReponseDoc="UPDATE form_session_personne_document_question_reponse 
		SET Valeur_Reponse='".addslashes($Valeur)."' 
		, Texte_Reponse='".addslashes($Texte)."'
		WHERE Id=".$Ligne_Q_R_RStagiaires[0];
		$ReqInsertReponseDoc=mysqli_query($bdd,$ReqInsertReponseDoc);
    }
    //Déclaration du document comme étant fait
    $ReqUpdateFormSessionPersonneDocument="
        UPDATE
            form_session_personne_document
        SET
            Id_Repondeur=".$IdPersonneConnectee.",
            DateHeureRepondeur='".date("Y-m-d H:i:s")."'
        WHERE
            Id=".$_POST['Id_Session_Personne_Document'];
    $ResultUpdateFormSessionPersonneDocument=mysqli_query($bdd,$ReqUpdateFormSessionPersonneDocument);
   
    echo "<script>FermerEtRecharger();</script>";
}
else
{
    //Vérification si droit d'accès au document
    //et document Ouvert et si document répondu
    //------------------------------------
    $Doc_Ouvert=false;
    $Doc_Acces_OK=false;
    $Doc_Repondu=false;
	$sansFormation = false;
	if(isset($_GET['sansFormation'])){
		$sansFormation = true;
	}
	
	if($sansFormation==false){
		$ReqFormSessionPersonneDoc="
			SELECT
				form_session_personne_document.Id,
				form_session_personne.Id_Personne,
				form_session_personne_document.DateHeureRepondeur,
				form_session_personne_document.Id_Document,
				form_session_personne_document.Id_LangueDocument,
				(SELECT Fichier_PHP FROM form_document WHERE form_document.Id=form_session_personne_document.Id_Document) AS Fichier_PHP,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=Id_Repondeur) AS Repondeur,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne.Id_Personne) AS Stagiaire
			FROM
				form_session_personne_document
			LEFT JOIN form_session_personne
				ON form_session_personne_document.Id_Session_Personne=form_session_personne.Id
			WHERE
				form_session_personne_document.Id=".$_GET['Id_Session_Personne_Document'];
	}
	else{
		$ReqFormSessionPersonneDoc="
			SELECT
				form_session_personne_document.Id,
				form_besoin.Id_Personne,
				form_session_personne_document.DateHeureRepondeur,
				form_session_personne_document.Id_Document,
				form_session_personne_document.Id_LangueDocument,
				(SELECT Fichier_PHP FROM form_document WHERE form_document.Id=form_session_personne_document.Id_Document) AS Fichier_PHP,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_session_personne_document.Id_Repondeur) AS Repondeur,
				(SELECT CONCAT(Nom,' ',Prenom) FROM new_rh_etatcivil WHERE new_rh_etatcivil.Id=form_besoin.Id_Personne) AS Stagiaire
			FROM
			form_session_personne_document,
        	form_session_personne_qualification,
        	form_besoin
        WHERE
        	form_session_personne_document.Id = ".$_GET['Id_Session_Personne_Document']."
			AND form_session_personne_document.Id_SessionPersonneQualification=form_session_personne_qualification.Id
        	AND form_session_personne_qualification.Id_Besoin = form_besoin.Id";
	}
    $ResultFormSessionPersonneDoc=mysqli_query($bdd,$ReqFormSessionPersonneDoc);
    $RowFormSessionPersonneDoc=mysqli_fetch_array($ResultFormSessionPersonneDoc);
    if($RowFormSessionPersonneDoc['DateHeureRepondeur']>'0001-01-01 00:00:00'){$Doc_Repondu=true;}
    if(DocestOuvert($_GET['Id_Session_Personne_Document'])){$Doc_Ouvert=true;}
	$listeChamps="";
    //Le filtre suivant concernant les DroitsFormationsPlateforme n'est pas effectué par rapport à la plateforme de la personne concernée par le document
    //Mais on va partir du principe que les AF,RF,FORM,RQP ne vont pas aller voir les QCM des autres plateformes
    if($RowFormSessionPersonneDoc['Id_Personne']==$IdPersonneConnectee || DroitsFormationPlateforme($TableauIdPostesAF_RF_FORM_PS_RQP) || ($Doc_Repondu && DroitsFormationPrestation($TableauIdPostesCQ))){$Doc_Acces_OK=true;}
    //------------------------------------
    //Les AF, RF, PS et RQP n'ont pas besoin d'ouvrir le document pour y accéder
    if(($Doc_Ouvert || $Doc_Repondu || DroitsFormationPlateforme($TableauIdPostesAF_RF_FORM_PS_RQP)) && $Doc_Acces_OK)
    {
		$ReqDoc_Langue="
			SELECT
				Id,
				Id_Document,
				Id_Langue,
				Libelle
			FROM
				form_document_langue
			WHERE
				Suppr=0
				AND Id_Langue=".$RowFormSessionPersonneDoc['Id_LangueDocument']." 
				AND Id_Document=".$RowFormSessionPersonneDoc['Id_Document'];
		$ResultDoc_Langue=mysqli_query($bdd,$ReqDoc_Langue);
		$RowDoc_Langue=mysqli_fetch_array($ResultDoc_Langue);

		echo "
		<form id='formulaire' method='POST' action='Doc_Web.php' onSubmit='return VerifChamps();'>
		<input type='hidden' id='ListeReponses' name='ListeReponses' value=''>
		<input type='hidden' name='Id_Session_Personne_Document' value='".$_GET['Id_Session_Personne_Document']."'>";
		echo "<input type='hidden' id='doc' name='doc' value='".$RowFormSessionPersonneDoc['Id_Document']."'>";
		echo "<input type='hidden' id='Id_LangueDoc' name='Id_LangueDoc' value='".$RowDoc_Langue['Id_Langue']."'>";
		echo "<input type='hidden' id='Id_DocLangue' name='Id_DocLangue' value='".$RowDoc_Langue['Id']."'>";
		
		if(DroitsFormationPlateforme($TableauIdPostesAF_RF_FORM_PS_RQP) || (DroitsFormationPrestation($TableauIdPostesCQ)) && $rowSessionPersonneDoc['DateHeureRepondeur']>"0001-01-01")
		{
			echo "<tr>
					<td colspan='10' align='right'>
					&nbsp;&nbsp;<a class='Modif' href=\"javascript:OuvreDocument('".$RowFormSessionPersonneDoc['Fichier_PHP']."',".$RowFormSessionPersonneDoc['Id'].");\">
					   <img width='20px' src='../../Images/pdf.png' style='border:0;' alt='Document'>&nbsp;&nbsp;&nbsp;&nbsp;
					</a>
					</td>
				</tr>";
		}
		
		$ReqDoc="
			SELECT
				Id,
				Reference
			FROM
				form_document
			WHERE
				Id=".$RowDoc_Langue['Id_Document'];
		$ResultDoc=mysqli_query($bdd,$ReqDoc);
		$RowDoc=mysqli_fetch_array($ResultDoc);
		
		$titre=stripslashes($RowDoc_Langue['Libelle']);
		$explication="";
		if($RowDoc['Reference']=="EVALUATION_A_CHAUD"){
			$explication="Exprimez votre niveau de satisfaction en mettant une croix dans la case de votre choix
						(de 1 « pas satisfaisant » à 6 « très satisfaisant »)";
			if($RowFormSessionPersonneDoc['Id_LangueDocument']<>1)
			{
				$explication="Express your level of satisfaction by ticking in the space of your choice 
							From 1 “unsatisfactory” to 6 “extremely satisfactory”";
			}
		}
		echo "<table class='GeneralInfo' border='1' style='width:100%; height:100%; border-spacing:0;'>";
					
		echo "<tr>
			<td rowspan='2'><img src='../../Images/Logos/Logo_Doc_Group.png'></td>
			<td colspan='7' align='center' style='font-size:25px;font-weight:bold;'>".$titre."</td>
			<td width='10%'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='7' align='center' style='color:#162bdd;'>".$explication."</td>
			<td align='center' style='font-size:15px;font-weight:bold;'></td>
		</tr>";
		echo "<tr>";
		echo "<tr><td height='20px' colspan='10'></td></tr>";
		if($RowDoc['Reference']=="EVALUATION_A_CHAUD"){
			echo "<tr style='font-size:20px;font-weight:bold;'>";
			if($RowFormSessionPersonneDoc['Id_LangueDocument']==1)
			{
				echo "
					<td width='25%' align='center' rowspan='2'>Niveau de satisfaction</td>
					<td width='6%' align='center' colspan='2'><img src='../../Images/Mauvais.png' width='25px' style='border:0;'></td>
					<td width='6%' align='center' colspan='2'><img src='../../Images/Moyen.png' width='25px' style='border:0;'></td>
					<td width='6%' align='center' colspan='2'><img src='../../Images/Bien.png' width='25px' style='border:0;'></td>
					<td width='35%' colspan='2' rowspan='2' align='center'>Observations</td>";
			}
			else
			{
				echo "
					<td width='25%' align='center' rowspan='2'>LEVEL OF SATISFACTION</td>
					<td width='6%' align='center' colspan='2'><img src='../../Images/Mauvais.png' width='25px' style='border:0;'></td>
					<td width='6%' align='center' colspan='2'><img src='../../Images/Moyen.png' width='25px' style='border:0;'></td>
					<td width='6%' align='center' colspan='2'><img src='../../Images/Bien.png' width='25px' style='border:0;'></td>
					<td width='35%' colspan='2' rowspan='2' align='center'>Observations</td>";
			}
			echo "</tr>";
		}
		echo "<tr bgcolor='#55d5f1' style='font-size:20px;font-weight:bold;'>";
		if($RowDoc['Reference']=="EVALUATION_A_CHAUD"){
				echo "
					<td width='6%' bgcolor='#de2227' align='center'>1</td>
					<td width='6%' bgcolor='#fc401c' align='center'>2</td>
					<td width='6%' bgcolor='#ee890b' align='center'>3</td>
					<td width='6%' bgcolor='#efd20a' align='center'>4</td>
					<td width='6%' bgcolor='#77b242' align='center'>5</td>
					<td width='6%' bgcolor='#45af4b' align='center'>6</td>";
		}
			echo "</tr>";
		$Doc_Q_R_RStagiaires=Generer_Document($RowDoc_Langue['Id'], $_GET['Id_Session_Personne_Document'], $Doc_Repondu);
		$listeChamps="";
		foreach($Doc_Q_R_RStagiaires as $Ligne_Q_R_RStagiaires)
		{
			echo "<tr>";
			//Récupération du fichier si il y en a un 
			$Fichier="";
			if($Ligne_Q_R_RStagiaires[2]<>""){
				if(file_exists('Docs/Document/'.$RowDoc_Langue['Id_Document'].'/'.$RowDoc_Langue['Id'].'/'.$Ligne_Q_R_RStagiaires[2]))
					{
						$Fichier= "&nbsp;&nbsp;<a class='Modif' target=\"_blank\" href=\"".$DirFichier."Document/".$RowDoc_Langue['Id_Document']."/".$RowDoc_Langue['Id']."/".$Ligne_Q_R_RStagiaires[2]."\">";
						$Fichier.= "<img src='../../Images/Tableau.gif' style='border:0;' alt='Doc'>";
						$Fichier.= "</a>";
					}
			}
			echo "<td id='".$Ligne_Q_R_RStagiaires[0]."' style='font:15px bold;'>".$Ligne_Q_R_RStagiaires[1].$Fichier."</td>";
			
			$modifiable="";
			$disable="";
			if($Doc_Repondu){
				$modifiable="readonly='readonly'";
				$disable="disabled='disabled'";
			}
			if($Ligne_Q_R_RStagiaires[3]=="Note (1 à 6)"){
				$Selected1="";
				$Selected2="";
				$Selected3="";
				$Selected4="";
				$Selected5="";
				$Selected6="";
				
				$TexteReponse="";
				if($Doc_Repondu){
					if($Ligne_Q_R_RStagiaires[4]=="1"){$Selected1="checked";}
					elseif($Ligne_Q_R_RStagiaires[4]=="2"){$Selected2="checked";}
					elseif($Ligne_Q_R_RStagiaires[4]=="3"){$Selected3="checked";}
					elseif($Ligne_Q_R_RStagiaires[4]=="4"){$Selected4="checked";}
					elseif($Ligne_Q_R_RStagiaires[4]=="5"){$Selected5="checked";}
					elseif($Ligne_Q_R_RStagiaires[4]=="6"){$Selected6="checked";}
					$TexteReponse=stripslashes($Ligne_Q_R_RStagiaires[5]);
				}
				else{
					if($RowDoc['Reference']<>"EVALUATION_A_CHAUD"){
						$Selected3="checked";
					}
				}
				echo "<td align='center'><input type='radio' name='note_".$Ligne_Q_R_RStagiaires[0]."' ".$Selected1." value='1' ".$disable."></td>";
				echo "<td align='center'><input type='radio' name='note_".$Ligne_Q_R_RStagiaires[0]."' ".$Selected2." value='2' ".$disable."></td>";
				echo "<td align='center'><input type='radio' name='note_".$Ligne_Q_R_RStagiaires[0]."' ".$Selected3." value='3' ".$disable."></td>";
				echo "<td align='center'><input type='radio' name='note_".$Ligne_Q_R_RStagiaires[0]."' ".$Selected4." value='4' ".$disable."></td>";
				echo "<td align='center'><input type='radio' name='note_".$Ligne_Q_R_RStagiaires[0]."' ".$Selected5." value='5' ".$disable."></td>";
				echo "<td align='center'><input type='radio' name='note_".$Ligne_Q_R_RStagiaires[0]."' ".$Selected6." value='6' ".$disable."></td>";
				echo "<td align='center' colspan='2'><textarea rows='2' cols='70' style='resize:none;' id='reponse_".$Ligne_Q_R_RStagiaires[0]."' name='reponse_".$Ligne_Q_R_RStagiaires[0]."' ".$modifiable.">".$TexteReponse."</textarea></td>";
				
				if($listeChamps<>""){$listeChamps.="|";}
				$listeChamps.="note_".$Ligne_Q_R_RStagiaires[0];
			}
			elseif($Ligne_Q_R_RStagiaires[3]=="Oui/Non"){
				$Selected0="";
				$Selected1="";
				$TexteReponse="";
				if($Doc_Repondu){
					if($Ligne_Q_R_RStagiaires[4]=="0"){$Selected0="checked";}
					elseif($Ligne_Q_R_RStagiaires[4]=="1"){$Selected1="checked";}
					$TexteReponse=stripslashes($Ligne_Q_R_RStagiaires[5]);
				}
				echo "<td colspan='8'>";
				if($RowFormSessionPersonneDoc['Id_LangueDocument']==1)
				{
					echo "<input type='radio' name='note_".$Ligne_Q_R_RStagiaires[0]."' ".$Selected1." value='1'><font>Oui</font>";
					echo "<input type='radio' name='note_".$Ligne_Q_R_RStagiaires[0]."' ".$Selected0." value='0'><font>Non</font>";
				}
				else{
					echo "<input type='radio' name='note_".$Ligne_Q_R_RStagiaires[0]."' ".$Selected1." value='1'><font>YES</font>";
					echo "<input type='radio' name='note_".$Ligne_Q_R_RStagiaires[0]."' ".$Selected0." value='0'><font>NO</font>";
				}
				echo "</td>";
				if($listeChamps<>""){$listeChamps.="|";}
				$listeChamps.="note_".$Ligne_Q_R_RStagiaires[0];
			}
			elseif($Ligne_Q_R_RStagiaires[3]=="Texte facultatif" || $Ligne_Q_R_RStagiaires[3]=="Texte obligatoire"){
				$TexteReponse="";
				if($Doc_Repondu){
					$TexteReponse=stripslashes($Ligne_Q_R_RStagiaires[5]);
				}
				echo "<td colspan='8'>
					<textarea rows='2' cols='150' style='resize:none;' id='reponse_".$Ligne_Q_R_RStagiaires[0]."' name='reponse_".$Ligne_Q_R_RStagiaires[0]."' ".$modifiable.">".$TexteReponse."</textarea>
					</td>";
				if($listeChamps<>""){$listeChamps.="|";}
			}
			echo "</tr>";
		}
	
		if(!$Doc_Repondu)
		{
			echo "
				<table width='100%'>
				<tr>
					<td align='center'>
						<input type='submit' value='Valider'>
					</td>
				</tr>
				</table>";
			echo"<br>";
		}
		else{
			echo "<table class='GeneralInfo' border='1' style='width:100%; height:100%; border-spacing:0;'>";
			echo "<tr>";
				if($RowFormSessionPersonneDoc['Id_LangueDocument']==1){
					echo "<td width='15%'>STAGIAIRE</td>";
				}
				else{
					echo "<td width='15%'>TRAINEE</td>";
				}
				echo "<td width='55%'>".$RowFormSessionPersonneDoc['Stagiaire']."</td>
				</tr>";
			echo "<tr>";
				if($RowFormSessionPersonneDoc['Id_LangueDocument']==1){
					echo "<td width='15%'>COMPLETE PAR</td>";
				}
				else{
					echo "<td width='15%'>COMPLETED BY</td>";
				}
				echo "<td width='55%'>".$RowFormSessionPersonneDoc['Repondeur']."</td>
				</tr>";
		}
		echo "</table>";
		echo"<br>";
		echo "</form>";
	}
	else 
	{
		echo "Vous n'avez pas les d'accès pour accéder à ce document.<br>";
		if(!$Doc_Ouvert){echo "Le document n'est pas ouvert pour réponse pour le moment<br>";}
		if(!$Doc_Acces_OK){echo "Vous n'avez pas les droits pour ouvrir ce document.";}
	}
}
    ?>
	<input type="hidden" name="listeChampsAVerifier" id="listeChampsAVerifier" value="<?php echo $listeChamps;?>" />
</body>
</html>
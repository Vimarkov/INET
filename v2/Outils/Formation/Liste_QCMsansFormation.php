<?php
require("../../Menu.php");
/**
 * Liste_QCMsansFormation.php
 * 
 * Permets de lister les QCM sans formation
 */

global $oDico;
$oDico = array(
    "Pas implémenté" => array("FR" => "Pas encore implémenté", "EN" => "Not implemented yet"),
    "Retour" => array("FR" => "Retour", "EN" => "Return"),
    "Titre" => array("FR" => "Liste des QCM sans formation", "EN" => "CQM list without formation"),
    "QCM" => array("FR" => "QCM", "EN" => "CQM"),
    "Ouverture" => array("FR" => "Ouverture", "EN" => "Opening"),
    "Résultats" => array("FR" => "Résultats", "EN" => "Results"),
    "QCM Mère" => array("FR" => "QCM Mère", "EN" => "Main CQM"),
    "Note finale" => array("FR" => "Note finale", "EN" => "Final evaluation"),
    "Réussit" => array("FR" => "Réussit", "EN" => "Succed"),
    "Echec" => array("FR" => "Echec", "EN" => "Failed"),
    "Modifier" => array("FR" => "Modifier", "EN" => "Modify"),
    "Langue" => array("FR" => "Langue", "EN" => "Language"),
    "Filtres" => array("FR" => "Filtres", "EN" => "Filters"),
    "Plateformes" => array("FR" => "Plateformes", "EN" => "Plateforms"),
    "du" => array("FR" => "du", "EN" => "from"),
    "au" => array("FR" => "au", "EN" => "to"),
    "Passé" => array("FR" => "Passé", "EN" => "Passed"),
    "Rechercher" => array("FR" => "Rechercher", "EN" => "Research"),
	"Evaluation" => array("FR" => "Evaluation à chaud", "EN" => "Hot evaluation"),
	"Attestation" => array("FR" => "J'atteste qu'une session de formation a été réalisée", "EN" => "I certify that a training session has been carried out")
);
?>
<script>
	function AfficherInfosQCM(Id_Besoin)
	{
		var w= window.open("QCM_SansSession.php?&Id_Besoin="+Id_Besoin,"PageQCM","status=no,menubar=no,scrollbars=yes,width=800,height=400");
		w.focus();
	}
	function QCM_Web(Id)
	{
		var w= window.open("QCM_Web_v3.php?Page=Liste_QCMsansFormation&Id_Session_Personne_Qualification="+Id,"PageQCMWeb","status=no,menubar=no,scrollbars=yes,width=1200,height=800");
		w.focus();
	}
	function Doc_WebSansSession(Id)
	{
		var w= window.open("Doc_Web.php?Id_Session_Personne_Document="+Id+"&sansFormation=1","PageDocWeb","status=no,menubar=no,scrollbars=yes,width=1200,height=800");
		w.focus();
	}
	function changerDisponibiliteQCM(Id_session_personne_qualification)
	{
		document.getElementById("checkbox").value = Id_session_personne_qualification;
		$.ajax({
			url : 'Ajax_ModifOuvertureQCM.php',
			data : 'Id='+Id_session_personne_qualification,
			dataType : "html",
			async : false,
			//affichage de l'erreur en cas de problème
			error:function(msg, string){
				},
			success:function(data){
					if(data.indexOf("1")!=-1){
						document.getElementById("ModifQCM_"+Id_session_personne_qualification).style.display='';
					}
					else{
						document.getElementById("ModifQCM_"+Id_session_personne_qualification).style.display='none';
					}
				}
		});
	}
	
	function changerDisponibiliteDoc(Id_session_personne_document)
	{
		document.getElementById("checkbox").value = Id_session_personne_document;
		$.ajax({
			url : 'Ajax_ModifOuvertureDoc.php',
			data : 'Id='+Id_session_personne_document,
			dataType : "html",
			async : false,
			//affichage de l'erreur en cas de problème
			error:function(msg, string){
				},
			success:function(data){
					if(data.indexOf("1")!=-1){
						document.getElementById("ModifDoc_"+Id_session_personne_document).style.display='';
					}
					else{
						document.getElementById("ModifDoc_"+Id_session_personne_document).style.display='none';
					}
				}
		});
	}
	
	function OuvrirFermerLesQCMDeLaPersonne(IdPersonne, checked,traite)
	{
		formulaire.checkboxPersonne.value = IdPersonne;
		formulaire.checkboxPersonneStatus.value = !checked;
		checked2=document.getElementById("CBGlobal_"+IdPersonne).checked;
		$.ajax({
			url : 'Ajax_ModifOuvertureQCMPersonne.php',
			data : 'Id='+IdPersonne+'&Checked='+checked2+'&Traite='+traite,
			dataType : "html",
			async : false,
			//affichage de l'erreur en cas de problème
			error:function(msg, string){
				},
			success:function(data){
					tabId = data.split(';');
					for (var Id_session_personne_qualification in tabId) {
					   if(tabId[Id_session_personne_qualification]!="<body>"){
							if(checked2==false){
								document.getElementById("CB_"+tabId[Id_session_personne_qualification]).checked=false;
							}
							else{
								document.getElementById("CB_"+tabId[Id_session_personne_qualification]).checked=true;
							}
							if(checked2==false){
								document.getElementById("ModifQCM_"+tabId[Id_session_personne_qualification]).style.display='';
							}
							else{
								document.getElementById("ModifQCM_"+tabId[Id_session_personne_qualification]).style.display='none';
							}
					   }
					}
					
				}
		});
	}
	
	function OuvrirFermerLesEvalDeLaPersonne(IdPersonne, checked,traite)
	{
		formulaire.checkboxPersonne.value = IdPersonne;
		formulaire.checkboxPersonneStatus.value = !checked;
		checked2=document.getElementById("EvalGlobal_"+IdPersonne).checked;
		$.ajax({
			url : 'Ajax_ModifOuvertureDocPersonne.php',
			data : 'Id='+IdPersonne+'&Checked='+checked2+'&Traite='+traite,
			dataType : "html",
			async : false,
			//affichage de l'erreur en cas de problème
			error:function(msg, string){
				},
			success:function(data){
					tabId = data.split(';');
					for (var Id_session_personne_document in tabId) {
					   if(tabId[Id_session_personne_document]!="<body>"){
							if(checked2==false){
								document.getElementById("CB_Doc_"+tabId[Id_session_personne_document]).checked=false;
							}
							else{
								document.getElementById("CB_Doc_"+tabId[Id_session_personne_document]).checked=true;
							}
							if(checked2==false){
								document.getElementById("ModifDoc_"+tabId[Id_session_personne_document]).style.display='';
							}
							else{
								document.getElementById("ModifDoc_"+tabId[Id_session_personne_document]).style.display='none';
							}
					   }
					}
					
				}
		});
	}
	
	function ChangerAttestation(changer,Id_session_personne_qualification)
	{
		document.getElementById("checkbox").value = Id_session_personne_qualification;
		checked2=document.getElementById("CBAtteste_"+Id_session_personne_qualification).checked;
		$.ajax({
			url : 'Ajax_ModifAttestation.php',
			data : 'Id='+Id_session_personne_qualification+'&Lieu='+document.getElementById('Lieu'+Id_session_personne_qualification).value+'&changer='+changer,
			dataType : "html",
			async : false,
			//affichage de l'erreur en cas de problème
			error:function(msg, string){
				},
			success:function(data){
					if(checked2==true){
						document.getElementById("evaluation_"+Id_session_personne_qualification).style.display='';
						document.getElementById("ouvertureEval_"+Id_session_personne_qualification).style.display='';
						document.getElementById("resultatEval_"+Id_session_personne_qualification).style.display='';
					}
					else{
						document.getElementById("evaluation_"+Id_session_personne_qualification).style.display='none';
						document.getElementById("ouvertureEval_"+Id_session_personne_qualification).style.display='none';
						document.getElementById("resultatEval_"+Id_session_personne_qualification).style.display='none';
					}
				}
		});
	}
	
	function ModifierQCMsansSession(Id)
	{
		var w= window.open("ModifierQCMSession.php?Id="+Id+"&sansFormation=1","PageModifQCMSession","status=no,menubar=no,scrollbars=yes,width=700,height=250");
		w.focus();
	}
	
	function ModifierDocSansSession(Id)
	{
		var w= window.open("ModifierDocSession.php?Id="+Id+"&sansFormation=1","PageModifDocSession","status=no,menubar=no,scrollbars=yes,width=700,height=250");
		w.focus();
	}

	function OuvreFenetreProfil(Mode,Id)
	{
		var w= window.open("../Competences/Profil.php?Mode="+Mode+"&Id_Personne="+Id,"PageProfil","status=no,menubar=no,scrollbars=yes,width=1040,height=800");
		w.focus();
	}
	function OuvreFenetreSuppr(Id_Besoin,Id_SessionPersonneQualification)
	{
		Confirm=false;
		Confirm=window.confirm('Etes-vous sûr de vouloir supprimer ?');
		if(Confirm==true)
		{
			var w= window.open("Supprimer_SessionPersonneQualification.php?Id_Besoin="+Id_Besoin+"&Id_SessionPersonneQualification="+Id_SessionPersonneQualification,"PageSessionPersonneQualification","status=no,menubar=no,width=50,height=50");
		}
	}
</script>    	
<?php
//********************************
//Fonctions d'affichage (IHM)
//********************************

/**
 * afficherResultats
 * 
 * affiche la case des résultats
 * 
 * @param int $Id_session_personne_qualification Identifiant unique pour une personne te un QCM
 * @return string la code html
 * 
 * @author	Anthony Schricke <aschricke@aaa-aero.com>
 */
function afficherTitres($id_personne,$traite)
{
    global $oDico;
	global $LangueAffichage;

    $html_code = "
        <tr>
            <td width='3%'></td>
			<td width='8%'></td>
			 <td width='15%' style=\"border:1px solid #5e5e5e;text-align:center;color:#ffffff;\" bgcolor=\"#1612a9\" >".$oDico['Attestation'][$LangueAffichage]."</td>
            <td width='22%' style=\"border:1px solid #5e5e5e;text-align:center;color:#ffffff;\" bgcolor=\"#1612a9\" >".$oDico['QCM'][$LangueAffichage]."</td>
            <td width='5%' style=\"border:1px solid #5e5e5e;text-align:center;color:#ffffff;\" bgcolor=\"#1612a9\" >";
	if($traite==0){	
			$html_code .=$oDico['Ouverture'][$LangueAffichage]."
				<label class=\"switch\">";
	
		if(getNombreQCM($id_personne,$traite) == getNombreQCMOuverts($id_personne,$traite))
			{$html_code .= "<input type=\"checkbox\" id=\"CBGlobal_".$id_personne."\" name=\"CBGlobal_".$id_personne."\" onchange=\"javascript:OuvrirFermerLesQCMDeLaPersonne(".$id_personne.", true,".$traite.");\" checked>";}
		else
			{$html_code .= "<input type=\"checkbox\" id=\"CBGlobal_".$id_personne."\" name=\"CBGlobal_".$id_personne."\" onchange=\"javascript:OuvrirFermerLesQCMDeLaPersonne(".$id_personne.", false,".$traite.");\">";}
		$html_code .= "
				  <span class=\"slider round\" ></span>
				</label>";
	}
    $html_code .= "
            </td>
            <td width='12%' style=\"border:1px solid #5e5e5e;text-align:center;color:#ffffff;\" bgcolor=\"#1612a9\" >".$oDico['Résultats'][$LangueAffichage]."</td>
			<td width='15%' style=\"border:1px solid #5e5e5e;text-align:center;color:#ffffff;\" bgcolor=\"#1612a9\" >".$oDico['Evaluation'][$LangueAffichage]."</td>
            <td width='10%' style=\"border:1px solid #5e5e5e;text-align:center;color:#ffffff;\" bgcolor=\"#1612a9\" >";
	if($traite==0){	
			$html_code .=$oDico['Ouverture'][$LangueAffichage]."
				<label class=\"switch\">";
		if(getNombreEval($id_personne,$traite) == getNombreEvalOuverts($id_personne,$traite))
			{$html_code .= "<input type=\"checkbox\" id=\"EvalGlobal_".$id_personne."\" name=\"EvalGlobal_".$id_personne."\" onchange=\"javascript:OuvrirFermerLesEvalDeLaPersonne(".$id_personne.", true,".$traite.");\" checked>";}
		else
			{$html_code .= "<input type=\"checkbox\" id=\"EvalGlobal_".$id_personne."\" name=\"EvalGlobal_".$id_personne."\" onchange=\"javascript:OuvrirFermerLesEvalDeLaPersonne(".$id_personne.", false,".$traite.");\">";}
		$html_code .= "
				  <span class=\"slider round\" ></span>
				</label>";
	}
	else{
		$html_code .= "&nbsp;";
	}
    $html_code .= "
            </td>
            <td width='15%' style=\"border:1px solid #5e5e5e;text-align:center;color:#ffffff;\" bgcolor=\"#1612a9\" >".$oDico['Résultats'][$LangueAffichage]."</td>
        </tr>
    ";
    
    return $html_code;
}

/**
 * afficherQualification
 * 
 * affiche les qualifications.
 * 
 * @param int $Id_Personne Identifinat du candidat
 * @param array $arrQualification tableau de la qualification contenant l'identifiant et le nom
 * @author	Anthony Schricke <aschricke@aaa-aero.com>
 */
function afficherQualification($Id_Personne, $arrQualification,$Traite)
{
	global $oDico;
	global $LangueAffichage;
	
    $html_code = "  <tr>
						<td align=\"center\" style='border:1px solid #5e5e5e;' width='5%'> ";
	if($Traite==0){
		$html_code .= "<a class=\"Modif\" href=\"javascript:OuvreFenetreSuppr('".$arrQualification[2]."','".$arrQualification[3]."');\">
								<img src=\"../../Images/Suppression2.gif\" style=\"border:0;\" alt=\"Suppression\">
							</a>";
	}
		$html_code .= "</td>
			<td align=\"center\" style='border:1px solid #5e5e5e;' width='20%'>".$arrQualification[1]."</td>
			<td align=\"center\" style='border:1px solid #5e5e5e;'>
			";
    if($arrQualification[4]==1){
		$html_code .= "
			<label class=\"switch\">
			  <input type=\"checkbox\" class='CB_Atteste' id=\"CBAtteste_".$arrQualification[3]."\" name=\"CBAtteste_".$arrQualification[3]."\" onchange=\"javascript:ChangerAttestation(1,".$arrQualification[3].");\" checked>
			  <span class=\"slider round\"></span>
			</label>";
	}
	else{
		$html_code .= "
			<label class=\"switch\">
			  <input type=\"checkbox\" class='CB_Atteste' id=\"CBAtteste_".$arrQualification[3]."\" name=\"CBAtteste_".$arrQualification[3]."\" onchange=\"javascript:ChangerAttestation(1,".$arrQualification[3].");\" >
			  <span class=\"slider round\"></span>
			</label>";
	}
	$html_code .= "<br>
			Lieu : 
			<input type='text' id=\"Lieu".$arrQualification[3]."\" name=\"Lieu".$arrQualification[3]."\" onblur=\"javascript:ChangerAttestation(0,".$arrQualification[3].");\" value=\"".stripslashes($arrQualification[5])."\" />
		</td>";
	
    //Rechercher et récupérer les QCM
    $array_QCM = rechercher_QCM($Id_Personne, $arrQualification[0],$arrQualification[2]);

    //parcourir le tableau pour les noms des QCM
    $html_code .= "<td align=\"left\" style='border:1px solid #5e5e5e;' >";
    $html_code .= "<table>
					
	";
    $html_code .= " <td align=\"center\">";
	$qcmOuvert=QCMestOuvert($arrQualification[3]);
	 if (!$qcmOuvert)
    {
        $html_code .= " <input class='Bouton' name='ModifQCM_".$arrQualification[3]."' id='ModifQCM_".$arrQualification[3]."' size='10' type='Button' style='cursor:pointer;' value='M' onclick='javascript:ModifierQCMsansSession(".$arrQualification[3].")' &nbsp; />";
    }
	else{
		$html_code .= " <input class='Bouton' name='ModifQCM_".$arrQualification[3]."' id='ModifQCM_".$arrQualification[3]."' size='10' type='Button' style='cursor:pointer;display:none;' value='M' onclick='javascript:ModifierQCMsansSession(".$arrQualification[3].")' &nbsp; />";
	}
    $html_code .= " </td>
			";
    $html_code .= " <td align=\"center\">";
    foreach ($array_QCM as $qcm)
    {
		$html_code .="<a href='javascript:QCM_Web(\"".$arrQualification[3]."\");'>".$qcm[1]."</a>";	
        $html_code .= "<br>";
    }
    $html_code .= " </td>";
    $html_code .= "</table>";
    $html_code .= "</td>
                   <td style='border:1px solid #5e5e5e;' align=\"center\">";         
    //parcourir le tableau pour les status des QCM
	if($Traite==0){
		$LastIdSessionPersonneQualification=0;
		foreach ($array_QCM as $qcm)
		{
			if($arrQualification[3] != $LastIdSessionPersonneQualification){        
					if($qcmOuvert)
						$html_code .= "
									<label class=\"switch\">
									  <input type=\"checkbox\" class='CB_QCM' id=\"CB_".$arrQualification[3]."\" name=\"CB_".$arrQualification[3]."\" onchange=\"javascript:changerDisponibiliteQCM(".$arrQualification[3].");\" checked>
									  <span class=\"slider round\"></span>
									</label>";
					else
						$html_code .= "
									<label class=\"switch\">
									  <input type=\"checkbox\" class='CB_QCM' id=\"CB_".$arrQualification[3]."\" name=\"CB_".$arrQualification[3]."\" onchange=\"javascript:changerDisponibiliteQCM(".$arrQualification[3].");\">                          
									  <span class=\"slider round\"></span>
									</label>";
				$html_code .= "<br>";
			}
			$LastIdSessionPersonneQualification=$arrQualification[3];
		}
	}
    $html_code .= "</td>";    
    
	$arrResultats = rechercherResultats($arrQualification[3]);
	
	if($arrResultats[0]['Resultat'] <> "")
	{ 
		$Etat="";
		if($LangueAffichage=="FR")
		{
			if($arrResultats[0]['Etat']==1){$Etat="<font color='#2dbe29'>Réussit</font>";}
			elseif($arrResultats[0]['Etat']==-1){$Etat="<font color='#e80000'>Echec</font>";}
		}
		else
		{
			if($arrResultats[0]['Etat']==1){$Etat="<font color='#2dbe29'>Success</font>";}
			elseif($arrResultats[0]['Etat']==-1){$Etat="<font color='#e80000'>Failure</font>";}
		}
		$html_code .= "
			<td align=\"center\" style='border:1px solid #5e5e5e;'>
				".$oDico['QCM Mère'][$LangueAffichage]." : ".$arrResultats[0]['ResultatMere']." % <br>
				".$oDico['Note finale'][$LangueAffichage]." : ".$arrResultats[0]['Resultat']." % <br>
				".$oDico['Réussit'][$LangueAffichage]." / ".$oDico['Echec'][$LangueAffichage]." : ".$Etat."
			</td>
		";
	}
	else
	{
		$html_code .= "
			<td align=\"center\" style='border:1px solid #5e5e5e;'>
			</td>
		";
	}
	
	$arrEvaluation = rechercherEvaluation($arrQualification[3]);
	if (!empty($arrEvaluation))
	{ 
		$Langue="";
		$checked="";
		$repondu="";
		$Id_SessionPersonneDoc=$arrEvaluation[0]['Id'];

		$Langue=$arrEvaluation[0]['Langue']."";
		if($arrEvaluation[0]['DateHeureOuverture']>'0001-01-01 00:00:00' && $arrEvaluation[0]['DateHeureFermeture']<='0001-01-01 00:00:00'){$checked="checked";}

		if($arrEvaluation[0]['DateHeureRepondeur']>'0001-01-01 00:00:00')
		{
			$repondu="
				V 
				&nbsp;&nbsp;
				<a class='Modif' href=\"javascript:OuvreDocument('".$arrEvaluation[0]['Fichier_PHP']."',".$arrEvaluation[0]['Id'].");\">
				   <img width='20px' src='../../Images/pdf.png' style='border:0;' alt='Document'>&nbsp;&nbsp;&nbsp;&nbsp;
				</a>";
		}

		$html_code .= "<td style='border:1px solid #5e5e5e;'>";
		if($arrQualification[4]==1){
			$html_code .= "<table id='evaluation_".$arrQualification[3]."'>";
		}
		else{
			$html_code .= "<table id='evaluation_".$arrQualification[3]."' style='display:none;'>";
		}
		$html_code .= "<tr>";
		if($checked==""){
			$html_code .= "<td><input class='Bouton' name='ModifDoc_".$Id_SessionPersonneDoc."' id='ModifDoc_".$Id_SessionPersonneDoc."' size='10' type='Button' style='cursor:pointer;' value='M' onclick='javascript:ModifierDocSansSession(".$Id_SessionPersonneDoc.")'>&nbsp;</td>";
		}
		else{
			$html_code .= "<td><input class='Bouton' name='ModifDoc_".$Id_SessionPersonneDoc."' id='ModifDoc_".$Id_SessionPersonneDoc."' size='10' type='Button' style='cursor:pointer;display:none;' value='M' onclick='javascript:ModifierDocSansSession(".$Id_SessionPersonneDoc.")'>&nbsp;</td>";
		}
		$html_code .= "<td><a href='javascript:Doc_WebSansSession(\"".$Id_SessionPersonneDoc."\");'>".$Langue."</a></td>";
		
		$html_code .= "</tr>";
		$html_code .= "</table>";
		$html_code .="</td>";
		$html_code .= "<td style='border:1px solid #5e5e5e;' align='center'>";
			if($arrQualification[4]==1){
				$html_code .= "<table id='ouvertureEval_".$arrQualification[3]."'>";
			}
			else{
				$html_code .= "<table id='ouvertureEval_".$arrQualification[3]."' style='display:none;'>";
			}
			$html_code .= "<tr><td>";
			$html_code .= " <label class=\"switch\">
			  <input type=\"checkbox\" id=\"CB_Doc_".$Id_SessionPersonneDoc."\" name=\"CB_Doc_".$Id_SessionPersonneDoc."\" ".$checked." onchange='javascript:changerDisponibiliteDoc(".$Id_SessionPersonneDoc.")'>                          
			  <span class=\"slider round\" ></span>
			</label>";
			$html_code .= "</td></tr>";
		$html_code .= "</table>";
		$html_code .="</td>";
		$html_code .= "<td style='border:1px solid #5e5e5e;' align='center'>";
		
		if($arrQualification[4]==1){
			$html_code .= "<table id='resultatEval_".$arrQualification[3]."'>";
		}
		else{
			$html_code .= "<table id='resultatEval_".$arrQualification[3]."' style='display:none;'>";
		}
		$html_code .= "<tr><td>";
		$html_code .= $repondu;
		$html_code .= "</td></tr>";
		$html_code .= "</table>";
		$html_code .="</td>";
	}
	else
	{
		$html_code .= "
			<td align=\"center\" style='border:1px solid #5e5e5e;'>
			</td>
			<td align=\"center\" style='border:1px solid #5e5e5e;'>
			</td>
			<td align=\"center\" style='border:1px solid #5e5e5e;'>
			</td>
		";
	}
	
	
	
    $html_code .= "</tr>
	";
    
    return $html_code;
}

//Afficher les candidats et leur qualifications en fonction du filtre choisi
function afficherCandidat($Id_Candidat,$Traite)
{
    $html_code = "
    <tr>
      <td valign=\"top\" align='center'>               
          <table class=\"GeneralInfo\" style='width:100%'>
            <tr>
              <td colspan=\"2\"><b>";
    
    //Rechercher et ecrire le nom du candidat
    $html_code .= "<a class='TableCompetences' href='javascript:OuvreFenetreProfil(\"Lecture\",\"".$Id_Candidat."\");'>".get_nomCandidat($Id_Candidat)."</a>";
	$html_code .= "<input class='Bouton' name='ReinitialiserMDP' size='10' type='Button' style='cursor:pointer;' value='MDP' onclick='javascript:OuvreFenetreIdentifiants(\"".$Id_Candidat."\")'>&nbsp;";
    $html_code .= "</b></td>
      	    </tr>
    	    <tr>
    		  <td colspan=\"2\" width='15%'>";
    
    $html_code .= afficherTitres($Id_Candidat,$Traite);

    //Rechercher et parcourir les qualifications à passer
   $arr = rechercher_qualifications($Id_Candidat,$Traite);
    
    foreach($arr as $qualif)
    {
        $html_code .= afficherQualification($Id_Candidat, $qualif,$Traite);
    }
    
    $html_code .= "
    		  </td>
    	    </tr>";
    
    $html_code .= "
          </table>
      </td>
    </tr>
    ";

    echo $html_code;
}

//********************************
// FIN Fonctions d'affichage (IHM)
//********************************
?>
<!--     <body> A CAUSE DE PIKWIK !--> 
<form id="formulaire" action="Liste_QCMsansFormation.php" method="post">
<input type="hidden" id="checkbox" name="checkbox" value = 0 />
<input type="hidden" id="checkboxPersonne" name="checkboxPersonne" value = 0 />
<input type="hidden" id="checkboxPersonneStatus" name="checkboxPersonneStatus" value = 0 />
    <table style="width:100%; border-spacing:0; align:center;">
    	<tr>
    		<td colspan="3">
    			<table style="width:100%; border-spacing:0;">
    				<tr>
                        <td colspan="2">
							<table class="GeneralPage" style="width:100%; border-spacing : 0; background-color:#f99a33;">
								<tr>
									<td class="TitrePage">
									<?php
										echo "<a style='text-decoration:none;' href='".$_SESSION['HTTP']."://".$_SERVER['SERVER_NAME']."/v2/Outils/Formation/Tableau_De_Bord.php'>";
										if($LangueAffichage=="FR"){echo "<img width='20px' src='../../Images/home.png' border='0' alt='Retour' title='Retour'>";}
										else{echo "<img width='20px' src='../../Images/home.png' border='0' alt='Return' title='Return'>";}
										echo "</a></th>";
									?>
									<?php if($LangueAffichage=="FR"){echo "Liste des QCM sans formation";}else{echo "CQM list without formation";}?></td>
								</tr>
							</table>
                		</td>
    				</tr>
    				<tr>
						<td>
							<table style="width:100%; border-spacing:0; align:center;" class="GeneralInfo">
								<tr><td height="4px"></td></tr>
								<tr>
									<td class="Libelle" width="8%">&nbsp;<?php if($LangueAffichage=="FR"){echo "Unité d'exploitation";}else{echo "Operating unit";}?> : </td>
									<td width="20%">
										<select id="Id_Plateforme" name="Id_Plateforme" onchange="submit()">
											<?php
											$Plateforme=0;
											$reqPla="
                                                SELECT DISTINCT
                                                    Id_Plateforme, 
                                                    (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle 
												FROM
                                                    new_competences_personne_poste_prestation
												LEFT JOIN new_competences_prestation
												    ON new_competences_personne_poste_prestation.Id_Prestation=new_competences_prestation.Id
												WHERE
                                                    Id_Poste IN (".$IdPosteReferentQualiteProduit.",".$IdPosteReferentQualiteSysteme.",".$IdPosteAssistantQualite.") 
												    AND Id_Personne=".$IdPersonneConnectee."
												UNION 
												 SELECT DISTINCT
                                                    Id_Plateforme, 
                                                    (SELECT Libelle FROM new_competences_plateforme WHERE Id=Id_Plateforme) AS Libelle 
												FROM
                                                    new_competences_personne_poste_plateforme
												WHERE
                                                    Id_Poste IN (".$IdPosteResponsableFormation.",".$IdPosteAssistantFormationInterne.",".$IdPosteFormateur.") 
												    AND Id_Personne=".$IdPersonneConnectee."
												ORDER BY
                                                    Libelle";
											$resultPlateforme=mysqli_query($bdd,$reqPla);
											$nbFormation=mysqli_num_rows($resultPlateforme);
											if($nbFormation>0)
											{
												$selected="";
												if(isset($_POST['Id_Plateforme']))
												{
												    if($_POST['Id_Plateforme']==0){$selected="selected";}
												}
												if(isset($_GET['Id_Plateforme']))
												{
													if($_GET['Id_Plateforme']==0){$selected="selected";}
												}
												while($rowplateforme=mysqli_fetch_array($resultPlateforme))
												{
													$selected="";
													if(isset($_POST['Id_Plateforme']))
													{
														if($_POST['Id_Plateforme']==$rowplateforme['Id_Plateforme']){$selected="selected";}
													}
													if(isset($_GET['Id_Plateforme']))
													{
														if($_GET['Id_Plateforme']==$rowplateforme['Id_Plateforme']){$selected="selected";}
													}
													echo "<option value='".$rowplateforme['Id_Plateforme']."' ".$selected.">".$rowplateforme['Libelle']."</option>\n";
													if($Plateforme==0){$Plateforme=$rowplateforme['Id_Plateforme'];}
												}
											}
											if(isset($_POST['Id_Plateforme'])){$Plateforme=$_POST['Id_Plateforme'];}
											if(isset($_GET['Id_Plateforme'])){$Plateforme=$_GET['Id_Plateforme'];}
											?>
										</select>
									</td>
									<td class="Libelle" width="8%">&nbsp;<?php if($LangueAffichage=="FR"){echo "Réalisé / En cours";}else{echo "Realized / In progress";}?> : </td>
									<td width="20%">
										<select id="Etat" name="Etat" onchange="submit()">
											<?php
											$Etat=0;
											if(isset($_POST['Etat'])){$Etat=$_POST['Etat'];}
											if(isset($_GET['Etat'])){$Etat=$_GET['Etat'];}
											?>
											<option value="0" <?php if($Etat==0){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "En cours";}else{echo "In progress";}?></option>
											<option value="1" <?php if($Etat==1){echo "selected";} ?>><?php if($LangueAffichage=="FR"){echo "Réalisé";}else{echo "Realized";}?></option>
										</select>
									</td>
									<td class="Libelle" width="28%">
										&nbsp;
										<?php
										    if($LangueAffichage=="FR"){echo "Stagiaire : ";}else{echo "Trainee : ";}
											$stagiaire="";
											if(isset($_GET['stagiaire'])){$stagiaire=$_GET['stagiaire'];}
											elseif(isset($_POST['stagiaire'])){$stagiaire=$_POST['stagiaire'];}
										?>
										<input type="text" id="stagiaire" name="stagiaire" size="20" value="<?php echo $stagiaire; ?>">
									</td>
									<td class="Libelle" width="28%">
										&nbsp;
										<input class="Bouton" name="BtnValider" size="10" type="submit" value="<?php if($LangueAffichage=="FR"){echo "Valider";}else{echo "Validate";}?>">
									</td>
								</tr>
								<tr><td height="4px"></td></tr>
							</table>
						</td>
					</tr>
					<tr><td height="4px"></td></tr>
    				<?php 
					//Fermer les QCM réalisés et non clôturés
					$req ="
					UPDATE
						form_session_personne_qualification
					SET 
						DateHeureFermeture = NOW()
					WHERE
						form_session_personne_qualification.Resultat<>''
						AND form_session_personne_qualification.DateHeureRepondeur>'0001-01-01'
						AND form_session_personne_qualification.Suppr=0 
						AND form_session_personne_qualification.DateHeureFermeture<='0001-01-01' ";
					$ResultUpdt=mysqli_query($bdd,$req);
					
                    //Rechercher et parcourir les candidats
                    $arr_candidats = rechercher_candidats($Plateforme,$Etat,$stagiaire);
			   
                    foreach($arr_candidats as $candidat)
                        afficherCandidat($candidat[0],$Etat);
    			   	?>
				</table>
			</td>
		</tr>
	</table>
</form>	
</body>
</html>
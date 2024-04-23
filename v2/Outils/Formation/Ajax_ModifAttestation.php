<?php
session_start();
require_once("../Connexioni.php");
require_once("../Fonctions.php");
require_once("Globales_Fonctions.php");
require_once("QCM_Fonctions.php");

$estAtteste=QCMestAtteste($_GET['Id']);
if($_GET['changer']==1){
	if($estAtteste){fermerAttesteQCM($_GET['Id'],$_GET['Lieu']);}
	else{
		ouvrirAttesteQCM($_GET['Id'],$_GET['Lieu']);
		
		$req="SELECT Id 
			FROM form_session_personne_document 
			WHERE 
				Suppr=0
				AND Id_SessionPersonneQualification=".$_GET['Id']."
				AND Id_Document=6";
		$ResultSelect=mysqli_query($bdd,$req);
		$NbDoc=mysqli_num_rows($ResultSelect);
		
		//DOCUMENTS EVALUATION A CHAUD
		if($NbDoc==1){
			$RowDoc=mysqli_fetch_array($ResultSelect);
			$ID_SESSION_PERSONNE_DOCUMENT=$RowDoc['Id'];
		}
		else{
			$ReqInsertSessionPersonneDocument="
				INSERT INTO form_session_personne_document
					(
					Id_SessionPersonneQualification,
					Id_Document
					)
				VALUES
					(
					".$_GET['Id'].",
					6
					)";
			$ResultInsertSessionPersonneDocument=mysqli_query($bdd,$ReqInsertSessionPersonneDocument);
			$ID_SESSION_PERSONNE_DOCUMENT=mysqli_insert_id($bdd);
			
			if($NbDoc>1){
				if($ID_SESSION_PERSONNE_DOCUMENT>0){
					maj_Langue_SessionPersonneDocumentSansSessionPersonne($ID_SESSION_PERSONNE_DOCUMENT,1);
				}
			}
		}
	}
}
else{
	if($estAtteste){ouvrirAttesteQCM($_GET['Id'],$_GET['Lieu']);}
	else{fermerAttesteQCM($_GET['Id'],$_GET['Lieu']);}
}
?>
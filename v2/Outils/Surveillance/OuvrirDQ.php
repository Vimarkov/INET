<?
	if(isset($_GET["Nom"])) {

	// Entête pour Ouvrir avec MSExcel
	header("content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=".$_GET ["Nom"]);

	flush(); // Envoie le buffer
	readfile('../../../Qualite/D/9/D-0901/'.$_GET["Nom"]); // Envoie le fichier
}?>

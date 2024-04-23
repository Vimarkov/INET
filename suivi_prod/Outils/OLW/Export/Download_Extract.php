<?php
	session_start();
	//	Lance le téléchargement du fichier sous forme de stream (d'où le readfile du fichier généré sur le serveur)
	//	Il s'agit ici de modifier l'entête de la page http, Il est obligatoire d'exécuter l'instruction header en premier !
	//	voir: http://php.net/manual/fr/function.header.php
	header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition:attachment;filename='.$_SESSION['filename'].'.xlsx');

	readfile('../../../tmp/Extract.xlsx');

	exit();
?>
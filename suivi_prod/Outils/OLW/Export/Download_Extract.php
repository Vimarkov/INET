<?php
	session_start();
	//	Lance le t�l�chargement du fichier sous forme de stream (d'o� le readfile du fichier g�n�r� sur le serveur)
	//	Il s'agit ici de modifier l'ent�te de la page http, Il est obligatoire d'ex�cuter l'instruction header en premier !
	//	voir: http://php.net/manual/fr/function.header.php
	header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition:attachment;filename='.$_SESSION['filename'].'.xlsx');

	readfile('../../../tmp/Extract.xlsx');

	exit();
?>
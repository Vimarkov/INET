<html>
<head>
	<title>Extranet | Daher</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<link href="../JS/colorpicker.css" rel="stylesheet">
	<script type="text/javascript" src="../JS/jquery.min.js"></script>	
	<script>
		function FermerEtRecharger(){
			window.close();
		}
	</script>
	<script language="javascript" src="Fonctions_GPAO.js?t=<?php echo time(); ?>"></script>
</head>
<body>
<?php

session_start();
require("../Connexioni.php");
require("../Fonctions.php");
	

if($_GET['Act']=="UpdateClosureDate"){
	/*
	This two request permit to update closure date and launch date
	The first one update closure date and put the date of the first TERC or first Transfert or first Cancel
	The second one update launch date by the date of the first status
	*/

	//Update Launch Date
	$req= "UPDATE gpao_wo 
			SET LaunchDate = (SELECT DateStatut FROM gpao_statutquality WHERE gpao_statutquality.Id_WO=gpao_wo.Id ORDER BY DateStatut ASC LIMIT 1)
			WHERE LaunchDate<='0001-01-01'
			AND Invoiced=0
			AND (
				SELECT COUNT(DateStatut)
				FROM gpao_statutquality 
				WHERE gpao_statutquality.Id_WO=gpao_wo.Id
			)>0 ";
	$result=mysqli_query($bdd,$req);
	 
	 //Update Closure date 
	$req= "UPDATE gpao_wo  
	 SET ClosureDate = (
					SELECT DateStatut 
					FROM gpao_statutquality 
					WHERE gpao_statutquality.Id_WO=gpao_wo.Id 
					AND (
						(SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) Like '%TERC CUSTOMER%' 
						Or (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) Like 'Transferred' 
						Or (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) Like '%Cancelled%'
						Or (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) Like '%TERC PARTNER%'
						Or (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) Like '%PARA STAMPED SENT%'
						Or (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) Like '%TERC CLOSED%'
						Or (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) Like '%DELETE%'
					)
					ORDER BY DateStatut ASC 
					LIMIT 1)
	WHERE ClosureDate<='0001-01-01'
	AND Invoiced=0
	AND (SELECT COUNT(DateStatut) 
		FROM gpao_statutquality 
		WHERE gpao_statutquality.Id_WO=gpao_wo.Id
		AND (
			(SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) Like '%TERC CUSTOMER%' 
			Or (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) Like 'Transferred' 
			Or (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) Like '%Cancelled%'
			Or (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) Like '%TERC PARTNER%'
			Or (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) Like '%PARA STAMPED SENT%'
			Or (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) Like '%TERC CLOSED%'
			Or (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) Like '%DELETE%'
		)
	)>0 ";
	$result=mysqli_query($bdd,$req);


	 //Update TERA Date 
	 $req= "UPDATE gpao_wo  
	 SET TERADate = (
					SELECT DateStatut 
					FROM gpao_statutquality 
					WHERE gpao_statutquality.Id_WO=gpao_wo.Id 
					AND (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) Like '%TERA%' 
					ORDER BY DateStatut ASC 
					LIMIT 1)
	WHERE TERADate<='0001-01-01'
	AND Invoiced=0
	AND (SELECT COUNT(DateStatut) 
		FROM gpao_statutquality 
		WHERE gpao_statutquality.Id_WO=gpao_wo.Id
		AND (SELECT Libelle FROM gpao_statutlist WHERE Id=Id_StatutList) Like '%TERA%' 
	)>0 ";
	$result=mysqli_query($bdd,$req);

	echo "<script>FermerEtRecharger();</script>";
}

?>

</body>
</html>
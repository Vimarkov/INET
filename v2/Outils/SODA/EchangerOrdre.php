<?php
	session_start();
	require("../Connexioni.php");
	if($_GET['Id1']<>"" && $_GET['Id2']<>"0"){
		$ordre1=0;
		$ordre2=0;
		$req="SELECT Ordre FROM soda_question WHERE Id=".$_GET['Id1'];
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		if($nbResulta>0){
			$rowOrdre=mysqli_fetch_array($result);
			$ordre1=$rowOrdre['Ordre'];
		}
		$req="SELECT Ordre FROM soda_question WHERE Id=".$_GET['Id2'];
		$result=mysqli_query($bdd,$req);
		$nbResulta=mysqli_num_rows($result);
		if($nbResulta>0){
			$rowOrdre=mysqli_fetch_array($result);
			$ordre2=$rowOrdre['Ordre'];
		}
		if($ordre1>0 && $ordre2>0){
			$req="UPDATE soda_question SET Ordre=".$ordre2." WHERE Id=".$_GET['Id1'];
			$result=mysqli_query($bdd,$req);
			
			$req="UPDATE soda_question SET Ordre=".$ordre1." WHERE Id=".$_GET['Id2'];
			$result=mysqli_query($bdd,$req);
		}
	}
 ?>
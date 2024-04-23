<?php
session_start();	//require("../VerifPage.php");
require("../Connexioni.php");

if($_GET)
{
	//Impression, Sauvegarde et fermeture
	$Fic = "Fiches_HSE/".$_GET["Emploi"].".html";;
	$inF = fopen($Fic,"r");
	$Nom=$_GET['Nom'];
	$Nom=eregi_replace("[�|�|�|�]", "e", $Nom);
	$Nom=eregi_replace("[�|�|�]", "e", $Nom);
	$Nom=eregi_replace("[�|�]", "e", $Nom);
	$Nom=eregi_replace("[�|�]", "e", $Nom);
	$Prenom=$_GET['Prenom'];
	$Prenom=eregi_replace("[�|�|�|�]", "e", $Prenom);
	$Prenom=eregi_replace("[�|�|�]", "e", $Prenom);
	$Prenom=eregi_replace("[�|�]", "e", $Prenom);
	$Prenom=eregi_replace("[�|�]", "e", $Prenom);
	$Plateforme=$_GET['Plateforme'];
	$Plateforme=eregi_replace("[�|�|�|�]", "e", $Plateforme);
	$Plateforme=eregi_replace("[�|�|�]", "e", $Plateforme);
	$Plateforme=eregi_replace("[�|�]", "e", $Plateforme);
	$Plateforme=eregi_replace("[�|�]", "e", $Plateforme);
	$Texte="";
	while(!feof($inF))
	{
		$Texte=$Texte.fgets($inF);
		if(strpos($Texte,"##"))
		{
			$Texte=str_replace("##NOM##",$Nom,$Texte);
			$Texte=str_replace("##PRENOM##",$Prenom,$Texte);
			$Texte=str_replace("##PLATEFORME##",$Plateforme,$Texte);
			$Texte=str_replace("##Emploi##",str_replace("_"," ",$_GET["Emploi"]),$Texte);
			$Texte=str_replace("##Date_Debut##",$_GET["Date_Debut"],$Texte);
			$Texte=str_replace("##Date_Fin##",$_GET["Date_Fin"],$Texte);
		}
	}
	fclose($inF);
	//echo $Texte;
	
	require_once('../html2pdf/html2pdf.class.php');
    $html2pdf = new HTML2PDF('L','A4','fr');
    $html2pdf->WriteHTML($Texte);
	ob_end_clean();
    $html2pdf->Output('Fiche_HSE.pdf');
}
?>
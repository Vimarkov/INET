<!DOCTYPE html>
<html>
<head>
	<title>SUIVI PROD AAA</title><meta name="robots" content="noindex">
	<link href="../../CSS/Feuille.css?t=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<script>
		function Recharger(){
			opener.location="FAQ.php";
		}
		function FermerEtRecharger(){
			window.opener.location = "FAQ.php";
			window.close();
		}
	</script>
</head>
<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");

$DateJour=date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")));
 Ecrire_Code_JS_Init_Date(); 
 if($_POST){
	if($_POST['question']<>"" && strpos($_SESSION['QuestionFAQ2'],$_POST['question'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('question','".$_POST['question']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['QuestionFAQ']=$_SESSION['QuestionFAQ'].$_POST['question'].$btn;
		$_SESSION['QuestionFAQ2']=$_SESSION['QuestionFAQ2'].$_POST['question'].";";
	}
	if($_POST['reponse']<>"" && strpos($_SESSION['ReponseFAQ2'],$_POST['reponse'].";")===false){
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('reponse','".$_POST['reponse']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['ReponseFAQ']=$_SESSION['ReponseFAQ'].$_POST['reponse'].$btn;
		$_SESSION['ReponseFAQ2']=$_SESSION['ReponseFAQ2'].$_POST['reponse'].";";
	}
	$left=substr($_POST['categorie'],0,strpos($_POST['categorie'],";"));
	if($_POST['categorie']<>"" && strpos($_SESSION['CategorieFAQ2'],$left.";")===false){
		$right=substr($_POST['categorie'],strpos($_POST['categorie'],";")+1);
		$btn="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('categorie','".$left."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
		$_SESSION['CategorieFAQ']=$_SESSION['CategorieFAQ'].$right.$btn;
		$_SESSION['CategorieFAQ2']=$_SESSION['CategorieFAQ2'].$left.";";
	}
	$_SESSION['ModeFiltreFAQ']="";
	echo "<script>Recharger();</script>";
}
elseif($_GET){
	if($_GET['Type']=="S"){
		if($_GET['critere']=="question"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('question','".$_GET['valeur']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['QuestionFAQ']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['QuestionFAQ']);
			$_SESSION['QuestionFAQ2']=str_replace($_GET['valeur'].";","",$_SESSION['QuestionFAQ2']);
		}
		elseif($_GET['critere']=="reponse"){
			$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('reponse','".$_GET['valeur']."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
			$_SESSION['ReponseFAQ']=str_replace($_GET['valeur'].$valeur,"",$_SESSION['ReponseFAQ']);
			$_SESSION['ReponseFAQ2']=str_replace($_GET['valeur'].";","",$_SESSION['ReponseFAQ2']);
		}
		elseif($_GET['critere']=="categorie"){
			$_SESSION['CategorieFAQ2']=str_replace($_GET['valeur'].";","",$_SESSION['CategorieFAQ2']);
			$tab = explode(";",$_SESSION['CategorieFAQ2']);
			$_SESSION['CategorieFAQ']="";
			foreach($tab as $Id){
				if($Id<>""){
					$valeur="<a style=\"text-decoration:none;\" href=\"javascript:Suppr_Critere('categorie','".$Id."')\">&nbsp;<img src=\"../../Images/Suppression2.gif\" border=\"0\" alt=\"Suppr\" title=\"Suppr\">&nbsp;&nbsp;</a>";
					$req="SELECT Libelle FROM trame_categorie_faq WHERE Id=".$Id;
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if($nbResulta>0){
						$row=mysqli_fetch_array($result);
						$_SESSION['CategorieFAQ'].=$row['Libelle'].$valeur;
					}
				}
			}
		}
		$_SESSION['ModeFiltreFAQ']="";
		echo "<script>FermerEtRecharger();</script>";
	}
}
 ?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<form class="test" method="POST" action="Ajout_CritereFAQ.php">
	<tr>
		<td>
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td width="4"></td>
					<td class="TitrePage"><?php if($_SESSION['Langue']=="EN"){echo "Add criterias";}else{echo "Ajouter des critères";} ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="4"></td></tr>
	<tr><td>
	<table width="100%" cellpadding="0" cellspacing="0" align="center" class="GeneralInfo">
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle" width=20%>
				&nbsp; <?php if($_SESSION['Langue']=="EN"){echo "Category";}else{echo "Catégorie";} ?> :
			</td>
			<td >
				<select name="categorie">
					<option name="" value=""></option>
					<?php
					$req="SELECT DISTINCT Id_Categorie, (SELECT Libelle FROM trame_categorie_faq WHERE trame_categorie_faq.Id=trame_faq.Id_Categorie) AS Categorie ";
					$req.="FROM trame_faq ORDER BY Categorie;";
					$result=mysqli_query($bdd,$req);
					$nbResulta=mysqli_num_rows($result);
					if ($nbResulta>0){
						while($row=mysqli_fetch_array($result)){
							echo "<option name='".$row['Id_Categorie']."' value='".$row['Id_Categorie'].";".$row['Categorie']."'>".$row['Categorie']."</option>";
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle" width=20%>
				&nbsp; <?php if($_SESSION['Langue']=="EN"){echo "Question";}else{echo "Question";} ?> :
			</td>
			<td colspan="10">
				<input type="texte" name="question" size="60" value="">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td class="Libelle" width=20%>
				&nbsp; <?php if($_SESSION['Langue']=="EN"){echo "Answer";}else{echo "Réponse";} ?> :
			</td>
			<td colspan="10">
				<input type="texte" name="reponse" size="60" value="">
			</td>
		</tr>
		<tr><td height="4"></td></tr>
		<tr>
			<td align="center" colspan="10">
				<input class="Bouton" name="BtnRechercher" size="10" type="submit" value="<?php if($_SESSION['Langue']=="EN"){echo "Add";}else{echo "Ajouter";}?>">
			</td>
			
		</tr>
	</table>
	</td></tr>
</form>
</table>
</body>
</html>
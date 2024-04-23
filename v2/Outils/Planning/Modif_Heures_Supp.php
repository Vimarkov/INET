<!DOCTYPE html>

<?php
session_start();
?>

<html>
<head>
	<title>Modification demande d'heure supplémentaire</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link rel="stylesheet" href="../../CSS/Feuille.css" type="text/css">
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->	
	<script src="../JS/modernizr.js"></script>
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script>
		function FermerEtRecharger()
		{
			opener.location.reload();
			window.close();
		}
		
		function ajouter()
		{
			for(y=0;y<document.getElementById('Id_Personne').length;y++)
			{
				if(document.getElementById('Id_Personne').options[y].selected == true)
				{
					nouvel_element = new Option(document.getElementById('Id_Personne').options[y].text,document.getElementById('Id_Personne').options[y].value,false,false);
					document.getElementById('PersonneSelect').options[document.getElementById('PersonneSelect').length] = nouvel_element;
					document.getElementById('Id_Personne').options[y] = null;
				}
			}
			
			Liste= new Array();
			Obj= document.getElementById('PersonneSelect')
			 
			for(i=0;i<Obj.options.length;i++){
				Liste[i]=new Array()
				Liste[i][0]=Obj.options[i].text
				Liste[i][1]=Obj.options[i].value
			}
			Liste=Liste.sort()
			 
			for(i=0;i<Obj.options.length;i++){
				Obj.options[i].text=Liste[i][0]
				Obj.options[i].value=Liste[i][1]
			}
		}
		
		function effacer()
		{
			for(y=0;y<document.getElementById('PersonneSelect').length;y++)
			{
				if(document.getElementById('PersonneSelect').options[y].selected == true)
				{
					nouvel_element = new Option(document.getElementById('PersonneSelect').options[y].text,document.getElementById('PersonneSelect').options[y].value,false,false);
					document.getElementById('Id_Personne').options[document.getElementById('Id_Personne').length] = nouvel_element;
					document.getElementById('PersonneSelect').options[y] = null;
				}
			}
			
			Liste= new Array();
			Obj= document.getElementById('Id_Personne')
			 
			for(i=0;i<Obj.options.length;i++){
				Liste[i]=new Array()
				Liste[i][0]=Obj.options[i].text
				Liste[i][1]=Obj.options[i].value
			}
			Liste=Liste.sort()
			 
			for(i=0;i<Obj.options.length;i++){
				Obj.options[i].text=Liste[i][0]
				Obj.options[i].value=Liste[i][1]
			}
		}
		
		function selectall()
		{
			if(document.getElementById('Date').value==""){alert("Veuillez remplir la date.");return false;}
			if(document.getElementById('Login1').value==""){alert("Veuillez vous reconnecter pour enregistrer les heures. Votre session a expiré.");return false;}
			if(document.getElementById('Nb_Heures_Jour').value==0 && document.getElementById('Nb_Heures_Nuit').value==0){alert("Veuillez remplir un nombre d'heures.");return false;}
			for(y=0;y<document.getElementById('PersonneSelect').length;y++){document.getElementById('PersonneSelect').options[y].selected = true;}
			if(document.getElementById('Etat2').value!=null)
			{
				if(document.getElementById('Etat2').value=="Refusée"){if(document.getElementById('Commentaire2').value == ""){alert("Veuillez remplir le commentaire.");return false;}}
			}
			if(document.getElementById('Etat3').value!=null)
			{
				if(document.getElementById('Etat3').value=="Refusée"){if(document.getElementById('Commentaire3').value == ""){alert("Veuillez remplir le commentaire.");return false;}}
			}
			if(document.getElementById('Etat4').value!=null)
			{
				if(document.getElementById('Etat4').value=="Refusée"){if(document.getElementById('Commentaire4').value == ""){alert("Veuillez remplir le commentaire.");return false;}}
			}
		}
		
		function TransfererListePersonne(ListePersonne)
		{
			var chaine=ListePersonne;
			var reg=new RegExp("[;]+", "g");
			var tableau=chaine.split(reg);
			for (var i=0; i<tableau.length; i++)
			{
				for(y=0;y<document.getElementById('Id_Personne').length;y++)
				{
					if(document.getElementById('Id_Personne').options[y].value == tableau[i]){document.getElementById('Id_Personne').options[y].selected = true;}
				}
			}
			ajouter();
		}
		
		Liste_Poste_Prestation = new Array(); //Id_Prestation, Id_Poste ASC, Backup ASC, "Nom Prenom", Id_Pole
		Liste_Pole_Prestation = new Array(); //Id_Pole, Id_Prestation, Pole
		function Recharge_Responsables()
		{
			//Recharge les poles de la prestation selectionnée
			var selPole="<select name='Id_Pole' id='Id_Pole' onchange='Recharge_ResponsablesP();'>";
			for(i=0;i<Liste_Pole_Prestation.length;i++){
				if (Liste_Pole_Prestation[i][1]==document.getElementById('Id_Prestation').value){
					selPole= selPole + "<option value="+Liste_Pole_Prestation[i][0];
					selPole= selPole + ">"+Liste_Pole_Prestation[i][2]+"</option>";}
			}
			selPole =selPole + "</select>";
			document.getElementById('Id_Pole').innerHTML=selPole;
			
			var sel="<table>";
			var ValidateurN1="<tr><td>";
			var ValidateurN2="<tr><td>";
			var ValidateurN3="<tr><td>";
			for(var i=0;i<Liste_Poste_Prestation.length;i++)
			{
				if (Liste_Poste_Prestation[i][0]==document.getElementById('Id_Prestation').value && Liste_Poste_Prestation[i][4]==document.getElementById('Id_Pole').value)
				{
					switch (Liste_Poste_Prestation[i][1])	//Id_Poste
					{
						case 2:
							if(Liste_Poste_Prestation[i][2]=="0"){ValidateurN1=ValidateurN1 + " Responsables N+1 :" + Liste_Poste_Prestation[i][3] + "; ";}
							if(Liste_Poste_Prestation[i][2]=="1"){ValidateurN1=ValidateurN1 + Liste_Poste_Prestation[i][3] + " (backup); ";}
							if(Liste_Poste_Prestation[i][2]=="2"){ValidateurN1=ValidateurN1 + Liste_Poste_Prestation[i][3] + " (backup); ";}
							break;
						case 3:
							if(Liste_Poste_Prestation[i][2]=="0"){ValidateurN2=ValidateurN2 + " Responsables N+2 :" + Liste_Poste_Prestation[i][3] + "; ";}
							if(Liste_Poste_Prestation[i][2]=="1"){ValidateurN2=ValidateurN2 + Liste_Poste_Prestation[i][3] + " (backup); ";}
							if(Liste_Poste_Prestation[i][2]=="2"){ValidateurN2=ValidateurN2 + Liste_Poste_Prestation[i][3] + " (backup); ";}
							break;
						case 4:
							if(Liste_Poste_Prestation[i][2]=="0"){ValidateurN3=ValidateurN3 + " Responsables N+3 :" + Liste_Poste_Prestation[i][3] + "; ";}
							if(Liste_Poste_Prestation[i][2]=="1"){ValidateurN3=ValidateurN3 + Liste_Poste_Prestation[i][3] + " (backup); ";}
							if(Liste_Poste_Prestation[i][2]=="2"){ValidateurN3=ValidateurN3 + Liste_Poste_Prestation[i][3] + " (backup); ";}
							break;
						default:
					}
				}
			}
			sel+=ValidateurN1 + "</td></tr>" + ValidateurN2 + "</td></tr>" + ValidateurN3 + "</td></tr></table>";
			document.getElementById('PostesValidateurs').innerHTML=sel;
		}
		function Recharge_ResponsablesP()
		{
			var sel="<table>";
			var ValidateurN1="<tr><td>";
			var ValidateurN2="<tr><td>";
			var ValidateurN3="<tr><td>";
			for(var i=0;i<Liste_Poste_Prestation.length;i++)
			{
				if (Liste_Poste_Prestation[i][0]==document.getElementById('Id_Prestation').value && Liste_Poste_Prestation[i][4]==document.getElementById('Id_Pole').value)
				{
					switch (Liste_Poste_Prestation[i][1])	//Id_Poste
					{
						case 2:
							if(Liste_Poste_Prestation[i][2]=="0"){ValidateurN1=ValidateurN1 + " Responsables N+1 :" + Liste_Poste_Prestation[i][3] + "; ";}
							if(Liste_Poste_Prestation[i][2]=="1"){ValidateurN1=ValidateurN1 + Liste_Poste_Prestation[i][3] + " (backup); ";}
							if(Liste_Poste_Prestation[i][2]=="2"){ValidateurN1=ValidateurN1 + Liste_Poste_Prestation[i][3] + " (backup); ";}
							break;
						case 3:
							if(Liste_Poste_Prestation[i][2]=="0"){ValidateurN2=ValidateurN2 + " Responsables N+2 :" + Liste_Poste_Prestation[i][3] + "; ";}
							if(Liste_Poste_Prestation[i][2]=="1"){ValidateurN2=ValidateurN2 + Liste_Poste_Prestation[i][3] + " (backup); ";}
							if(Liste_Poste_Prestation[i][2]=="2"){ValidateurN2=ValidateurN2 + Liste_Poste_Prestation[i][3] + " (backup); ";}
							break;
						case 4:
							if(Liste_Poste_Prestation[i][2]=="0"){ValidateurN3=ValidateurN3 + " Responsables N+3 :" + Liste_Poste_Prestation[i][3] + "; ";}
							if(Liste_Poste_Prestation[i][2]=="1"){ValidateurN3=ValidateurN3 + Liste_Poste_Prestation[i][3] + " (backup); ";}
							if(Liste_Poste_Prestation[i][2]=="2"){ValidateurN3=ValidateurN3 + Liste_Poste_Prestation[i][3] + " (backup); ";}
							break;
						default:
					}
				}
			}
			sel+=ValidateurN1 + "</td></tr>" + ValidateurN2 + "</td></tr>" + ValidateurN3 + "</td></tr></table>";
			document.getElementById('PostesValidateurs').innerHTML=sel;
		}
	</script>
</head>

<?php
require("../Connexioni.php");
require("../Fonctions.php");


$PlateformesImpactees=implode(',',$_SESSION['Id_Plateformes']);

if($_POST)
{
	//#################
	//##### EMAIL #####
	//#################
	//Récupération de l'email de la personne qui poste la demande d'heure supplémentaire
	$Email1="";
	if($_POST['mode']=="Ajout"){$requete_user="SELECT EmailPro FROM new_rh_etatcivil WHERE Login='".$_SESSION['Log']."'";}
	else{$requete_user="SELECT EmailPro FROM new_rh_etatcivil WHERE Login='".$_POST['Login1']."'";}
	$result_user=mysqli_query($bdd,$requete_user);
	$row_user=mysqli_fetch_array($result_user);
	$Email1=$row_user[0];
	
	//Récupération de la personne qui valide l'heure supplémentaire
	$PersonneLoguee="";
	$requete_PersonneLoguee="SELECT CONCAT(Nom,' ',Prenom) as NomPrenom FROM new_rh_etatcivil WHERE Login='".$_SESSION['Log']."'";
	$result_PersonneLoguee=mysqli_query($bdd,$requete_PersonneLoguee);
	$row_PersonneLoguee=mysqli_fetch_array($result_PersonneLoguee);
	$PersonneLoguee=$row_PersonneLoguee[0];
	
	//Récupération des différents emails des responsables de niveau au dessus sur la prestation en question
	$Email2="";
	$Email3="";
	$Email4="";
	$PersonneConnectee_IdPosteMaxSurPrestation=0;
	$requeteResponsablePostePrestation="
		SELECT DISTINCT
			new_competences_personne_poste_prestation.Id_Poste,
			new_competences_personne_poste_prestation.Backup,
			CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) as NomPrenom,
			new_rh_etatcivil.EmailPro,
			new_rh_etatcivil.Id
		FROM
			new_competences_personne_poste_prestation,
			new_rh_etatcivil
		WHERE
			new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id
			AND new_competences_personne_poste_prestation.Id_Prestation=".$_POST['Id_Prestation'];
	if($_POST['Id_Pole']>0){
		$requeteResponsablePostePrestation.=" AND new_competences_personne_poste_prestation.Id_Pole=".$_POST['Id_Pole'];
	}
	$requeteResponsablePostePrestation.=" ORDER BY new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Backup ASC";
	$resultResponsablePostePrestation=mysqli_query($bdd,$requeteResponsablePostePrestation);
	while($rowResponsablePostePrestation=mysqli_fetch_array($resultResponsablePostePrestation))
	{
		//Récupération de la valeur du poste le plus haut sur cette prestation de la personne connectée
		if($rowResponsablePostePrestation['Id']==$_SESSION['Id_Personne'] && $rowResponsablePostePrestation['Id']>$PersonneConnectee_IdPosteMaxSurPrestation)
		{
			$PersonneConnectee_IdPosteMaxSurPrestation=$rowResponsablePostePrestation['Id_Poste'];
		}
		
		switch($rowResponsablePostePrestation['Id_Poste'])
		{
			case 2: if($rowResponsablePostePrestation['EmailPro']<>""){$Email2.=$rowResponsablePostePrestation['EmailPro'].",";}break;
			case 3: if($rowResponsablePostePrestation['EmailPro']<>""){$Email3.=$rowResponsablePostePrestation['EmailPro'].",";}break;
			case 4: if($rowResponsablePostePrestation['EmailPro']<>""){$Email4.=$rowResponsablePostePrestation['EmailPro'].",";}break;
		}
	}
	$Email2=substr($Email2,0,strlen($Email2)-1);
	$Email3=substr($Email3,0,strlen($Email3)-1);
	$Email4=substr($Email4,0,strlen($Email4)-1);
	
	//4 étant le responsable Affaire : la validation des heures supplémentaire ne va pas plus loin
	if($PersonneConnectee_IdPosteMaxSurPrestation>4){$PersonneConnectee_IdPosteMaxSurPrestation=4;}
	
	//Remplissage des différentes informations à inclure dans le mail
	$Destinataires="";
	$DestinatairesEnCopie="";
	switch($_POST['step'])
	{
		case 1 :
			$Etat="Demandée pour validation";
			$Commentaire="";
			$Destinataires=$Email2;
			if($Email1 <> ""){$Destinataires.=",".$Email1;}
			if($PersonneConnectee_IdPosteMaxSurPrestation>=2){$Destinataires.=",".$Email3;}
			if($PersonneConnectee_IdPosteMaxSurPrestation>=3){$Destinataires.=",".$Email4;}
			if($PersonneConnectee_IdPosteMaxSurPrestation==4){
				$Etat="Validée";
				if($_SERVER['SERVER_NAME']=="extranet.aaa-aero.com")
				{
					switch($PlateformesImpactees)
					{
						case "1" :
						case "23" :
						case "1,23" :
							$DestinatairesEnCopie.="";
							break;
						case "19" :
							$DestinatairesEnCopie.="cnicolai@aaa-aero.com";
							break;
					}
				}
				else
				{
					$DestinatairesEnCopie.="informatique_tls@aaa-aero.com,";
				}
			}
			break;
		case 2 : 
			$Etat=$_POST['Etat'.$_POST['step']];
			$Commentaire="<br> - Commentaire N+".($_POST['step']-1)." : ".$_POST['Commentaire'.$_POST['step']];
			$Destinataires=$Email3;
			if($Email1 <> ""){$Destinataires.=",".$Email1;}
			if($PersonneConnectee_IdPosteMaxSurPrestation>=3){$Destinataires.=",".$Email4;}
			if($PersonneConnectee_IdPosteMaxSurPrestation==4){
				if($_SERVER['SERVER_NAME']=="extranet.aaa-aero.com")
				{
					switch($PlateformesImpactees)
					{
						case "1" :
						case "23" :
						case "1,23" :
							$DestinatairesEnCopie.="";
							break;
						case "19" :
							$DestinatairesEnCopie.="cnicolai@aaa-aero.com";
							break;
					}
				}
				else
				{
					$DestinatairesEnCopie.="informatique_tls@aaa-aero.com,";
				}
			}
			if($Etat=="Refusée"){$DestinatairesEnCopie=$Email3;}
			break;
		case 3 :
			$Etat=$_POST['Etat'.$_POST['step']];
			$Commentaire="<br> - Commentaire N+".($_POST['step']-1)." : ".$_POST['Commentaire'.$_POST['step']];
			$Destinataires=$Email4;
			if($Email1 <> ""){$Destinataires.=",".$Email1;}
			if($PersonneConnectee_IdPosteMaxSurPrestation==4){
				if($_SERVER['SERVER_NAME']=="extranet.aaa-aero.com")
				{
					switch($PlateformesImpactees)
					{
						case "1" :
						case "23" :
						case "1,23" :
							$DestinatairesEnCopie.="";
							break;
						case "19" :
							$DestinatairesEnCopie.="cnicolai@aaa-aero.com";
							break;
					}
				}
				else
				{
					$DestinatairesEnCopie.="informatique_tls@aaa-aero.com,";
				}
			}
			if($Etat=="Refusée"){$DestinatairesEnCopie=$Email3.",".$Email2;}
			break;
		case 4 :
			$Etat=$_POST['Etat4'];
			$Commentaire="<br> - Commentaire N+".($_POST['step']-1)." : ".$_POST['Commentaire'.$_POST['step']];
			if($Email1 <> ""){$Destinataires=$Email1;}else{$Destinataires="extranet@aaa-aero.com";}
			if($_SERVER['SERVER_NAME']=="extranet.aaa-aero.com")
			{
				switch($PlateformesImpactees)
					{
						case "1" :
						case "23" :
						case "1,23" :
							$DestinatairesEnCopie=",".$Email3.",".$Email2;
							break;
						case "19" :
							$DestinatairesEnCopie="cnicolai@aaa-aero.com,".$Email3.",".$Email2;
							break;
					}
			}
			else
			{
				$DestinatairesEnCopie="informatique_tls@aaa-aero.com,".$Email3.",".$Email2;
			}
			break;
	}

	//-------EN MODE AJOUT-------
	//###########################
	$Personne="";
	if(isset($_POST['PersonneSelect']))
	{
		$PersonneSelect = $_POST['PersonneSelect'];
		for($i=0;$i<sizeof($PersonneSelect);$i++)
		{
			if(isset($PersonneSelect[$i])){$Personne.=$PersonneSelect[$i].";";}
		}
	}
	
	if($_POST['mode']=="Ajout" || $_POST['mode']=="Duplique")
	{
		if($_POST['mode']=="Ajout"){$TabPersonne = preg_split("/[;]+/", $Personne);}
		else{$TabPersonne = preg_split("/[;]+/", $_POST['Id_Personne'].";");}
		for($i=0;$i<sizeof($TabPersonne)-1;$i++)
		{
			$requete="INSERT INTO new_rh_heures_supp ";
			$requete.="(Id_Prestation,Id_Pole,Id_Personne,Nb_Heures_Jour,Nb_Heures_Nuit,Date,Motif,Login1,Date1";
			//Validation automatiquement rempli si le valideur est responsable du/des niveau(x) au dessus
			$requetesuite="";
			if($PersonneConnectee_IdPosteMaxSurPrestation>$_POST['step'])
			{
				for($j=$_POST['step']+1;$j<=$PersonneConnectee_IdPosteMaxSurPrestation;$j++)
				{
					$requete.=",Login".$j.",Date".$j.",Etat".$j.",Commentaire".$j."";
					$requetesuite.=",'".$_SESSION['Log']."','".$DateJour."','Validée','Validée automatiquement car responsable identique au demandeur'";
				}
			}
			$requete.=") VALUES ";
			$requete.=" ('".$_POST['Id_Prestation']."','".$_POST['Id_Pole']."','".$TabPersonne[$i]."','".$_POST['Nb_Heures_Jour']."','".$_POST['Nb_Heures_Nuit']."','".TrsfDate($_POST['Date'])."','".addslashes($_POST['Motif'])."','".$_SESSION['Log']."','".$DateJour."'";
			$requete.=$requetesuite;
			$requete.=")";
			$result=mysqli_query($bdd,$requete);
			
			if($PersonneConnectee_IdPosteMaxSurPrestation == 4)
			{
				if ($NavigOk ==1)
				{
					$lDate = $_POST['Date'];
					$tabDate = explode('-', $lDate);
					$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
					$datePlanning = date("Y-m-d",$timestamp);
				}
				else
				{
					$lDate = $_POST['Date'];
					$tabDate = explode('/', $lDate);
					$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[0], $tabDate[2]);
					$datePlanning = date("Y-m-d",$timestamp);
				}
				//Mettre à valider le planning
				$reqUpdate = "UPDATE new_planning_personne_vacationabsence ";
				$reqUpdate .= "SET ValidationResponsable = 0 ";
				$reqUpdate .= "WHERE Id_Personne = '".$TabPersonne[$i]."' ";
				$reqUpdate .= "AND DatePlanning = '".$datePlanning."' ";
				$resultUpdate=mysqli_query($bdd,$reqUpdate);
			}
		}
	}
	elseif($_POST['mode']=="Modif")
	{
		$TabPersonne = preg_split("/[;]+/", $_POST['Id_Personne'].";");
		$requete="UPDATE new_rh_heures_supp SET ";
		$requete.=" Login".$_POST['step']."='".$_SESSION["Log"]."',";
		$requete.=" Date".$_POST['step']."='".$DateJour."',";
		$requete.=" Etat".$_POST['step']."='".$_POST['Etat'.$_POST['step']]."',";
		$requete.=" Commentaire".$_POST['step']."='".addslashes($_POST['Commentaire'.$_POST['step']])."'";
		//Validation ou refus automatiquement rempli si le valideur est responsable du/des niveau(x) au dessus
		if($PersonneConnectee_IdPosteMaxSurPrestation>$_POST['step'])
		{
			for($j=$_POST['step']+1;$j<=$PersonneConnectee_IdPosteMaxSurPrestation;$j++)
			{
				$requete.=",";
				$requete.=" Login".$j."='".$_SESSION["Log"]."',";
				$requete.=" Date".$j."='".$DateJour."',";
				$requete.=" Etat".$j."='".$_POST['Etat'.$_POST['step']]."',";
				$requete.=" Commentaire".$j."='".addslashes($_POST['Commentaire'.$_POST['step']])." automatiquement car responsable identique au précédent'";
			}
		}
		if($PersonneConnectee_IdPosteMaxSurPrestation == 4)
		{
				if($_POST['Etat4'] == "Validée")
				{
					if ($NavigOk ==1)
					{
						$lDate = $_POST['Date'];
						$tabDate = explode('-', $lDate);
						$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[2], $tabDate[0]);
						$datePlanning = date("Y-m-d",$timestamp);
					}
					else
					{
						$lDate = $_POST['Date'];
						$tabDate = explode('/', $lDate);
						$timestamp = mktime(0, 0, 0, $tabDate[1], $tabDate[0], $tabDate[2]);
						$datePlanning = date("Y-m-d",$timestamp);
					}
					//Mettre à valider le planning
					$reqUpdate = "UPDATE new_planning_personne_vacationabsence ";
					$reqUpdate .= "SET ValidationResponsable = 0 ";
					$reqUpdate .= "WHERE Id_Personne = '".$_POST['Id_Personne']."' ";
					$reqUpdate .= "AND DatePlanning = '".$datePlanning."' ";
					$resultUpdate=mysqli_query($bdd,$reqUpdate);
				}
		}
		$requete.=" WHERE Id=".$_POST['id'];
		$result=mysqli_query($bdd,$requete);
	}
	echo $requete;
	
	//Récupération du libellé du site
	$requete_Site="SELECT Libelle FROM new_competences_prestation WHERE Id=".$_POST['Id_Prestation'];
	$result_Site=mysqli_query($bdd,$requete_Site);
	$row_Site=mysqli_fetch_array($result_Site);
	$Site=$row_Site[0];
	
	//Récupération du libellé du pole
	$requete_Pole="SELECT Libelle FROM new_competences_pole WHERE Id=".$_POST['Id_Pole'];
	$result_Pole=mysqli_query($bdd,$requete_Pole);
	$nbPole=mysqli_num_rows($result_Pole);
	$Pole="";
	if($nbPole>0)
	{
		$row_Pole=mysqli_fetch_array($result_Pole);
		$Pole=$row_Pole['Libelle'];
	}
	
	//Boucle sur chacune des personnes
	for($i=0;$i<sizeof($TabPersonne)-1;$i++)
	{
		$NOMPrenom="";
		$requete_NOMPrenom="SELECT CONCAT(Nom,' ',Prenom) AS NOMPrenom FROM new_rh_etatcivil WHERE Id=".$TabPersonne[$i];
		$result_NOMPrenom=mysqli_query($bdd,$requete_NOMPrenom);
		$row_NOMPrenom=mysqli_fetch_array($result_NOMPrenom);
		$NOMPrenom=$row_NOMPrenom[0];
	
		$headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
		$headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
		if($DestinatairesEnCopie!=""){$headers .='Cc: '.$DestinatairesEnCopie."\n";}
		$message='<html><head><title>Heures Supplémentaires - Extranet V2 - '.$Etat.'</title></head><body>Bonjour,<br><br>';
		$message.='La demande d\'heures supplémentaires suivante a été '.$Etat.' par '.$PersonneLoguee;
		$message.='<br><table border=1><tr><td>Site/Prestation payeur</td><td>Personne concernée</td><td>Date</td><td>Nb H Jour</td><td>Nb H nuit</td><td>Motif</td>';
		$message.='<tr><td>'.substr($Site,0,7).'</td>';
		if($Pole <> ""){$message.=' - '.$Pole.' ';}
		$message.='</td>';
		$message.='<td>'.$NOMPrenom.'</td>';
		$message.='<td>'.$_POST['Date'].'</td>';
		$message.='<td>'.stripslashes($_POST['Nb_Heures_Jour']).'</td>';
		$message.='<td>'.stripslashes($_POST['Nb_Heures_Nuit']).'</td>';
		$message.='<td>'.stripslashes($_POST['Motif']).'</td>';
		$message.='</tr></table>';
		$message.=stripslashes($Commentaire);
		if($_POST['step']<4 && $PersonneConnectee_IdPosteMaxSurPrestation<4){$message.='<br>Veuillez vous rendre sur le site extranet AAA afin de la valider ou de la refuser';}
		$message.='<br><br>Bonne journée.<br><a href="https://extranet.aaa-aero.com">Extranet</a></body></html>';
		$objetMail="Heures Supplémentaires - Extranet V2";
		$objetMail.=" - ".substr($Site,0,7);
		$objetMail.=" - ".$NOMPrenom;
		$objetMail.=" - ".$_POST['Date'];
		$objetMail.=" - ".$_POST['Nb_Heures_Jour'].' HJ/ '.$_POST['Nb_Heures_Nuit']." HN";
		$objetMail.=" - ".$Etat;
		if(mail($Destinataires, $objetMail, $message, $headers,'-f extranet@aaa-aero.com'))
		{
			echo 'Le message a été envoyé';
			echo "<script>FermerEtRecharger();</script>";
		}
		else{echo 'Le message n\'a pu être envoyé';}
	}
	echo "<script>FermerEtRecharger();</script>";
}
elseif($_GET['Mode']=="Suppr")
{
	//------MODE SUPPRESSION-----
	//###########################
	$rquestSuppr="SELECT new_rh_heures_supp.*, new_competences_prestation.Libelle, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS NOMPrenom,";
	$rquestSuppr.=" (SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=new_rh_heures_supp.Id_Pole) AS Pole";
	$rquestSuppr.=" FROM new_rh_heures_supp LEFT JOIN new_competences_prestation ON new_rh_heures_supp.Id_Prestation=new_competences_prestation.Id";
	$rquestSuppr.=" LEFT JOIN new_rh_etatcivil ON new_rh_heures_supp.Id_Personne=new_rh_etatcivil.Id WHERE new_rh_heures_supp.Id=".$_GET['Id'];
	$resultSuppr=mysqli_query($bdd,$rquestSuppr);
	$rowSuppr=mysqli_fetch_array($resultSuppr);
	
	//Récupération des différents emails des responsables de niveau au dessus sur la prestation en question
	$Email2="";
	$Email3="";
	$Email4="";
	$PersonneConnectee_IdPosteMaxSurPrestation=0;
	$requeteResponsablePostePrestation="SELECT DISTINCT new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Backup, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) as NomPrenom, new_rh_etatcivil.EmailPro, new_rh_etatcivil.Id";
	$requeteResponsablePostePrestation.=" FROM new_competences_personne_poste_prestation, new_rh_etatcivil";
	$requeteResponsablePostePrestation.=" WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id";
	$requeteResponsablePostePrestation.=" AND new_competences_personne_poste_prestation.Id_Prestation=".$rowSuppr['Id_Prestation'];
	$requeteResponsablePostePrestation.=" ORDER BY new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Backup ASC";
	$resultResponsablePostePrestation=mysqli_query($bdd,$requeteResponsablePostePrestation);
	while($rowResponsablePostePrestation=mysqli_fetch_array($resultResponsablePostePrestation))
	{
		//Récupération de la valeur du poste le plus haut sur cette prestation de la personne connectée
		if($rowResponsablePostePrestation['Id']==$_SESSION['Id_Personne'] && $rowResponsablePostePrestation['Id']>$PersonneConnectee_IdPosteMaxSurPrestation)
		{
			$PersonneConnectee_IdPosteMaxSurPrestation=$rowResponsablePostePrestation['Id_Poste'];
		}
		
		switch($rowResponsablePostePrestation['Id_Poste'])
		{
			case 2: if($rowResponsablePostePrestation['EmailPro']<>""){$Email2.=$rowResponsablePostePrestation['EmailPro'].",";}break;
			case 3: if($rowResponsablePostePrestation['EmailPro']<>""){$Email3.=$rowResponsablePostePrestation['EmailPro'].",";}break;
			case 4: if($rowResponsablePostePrestation['EmailPro']<>""){$Email4.=$rowResponsablePostePrestation['EmailPro'].",";}break;
		}
	}
	$Email2=substr($Email2,0,strlen($Email2)-1);
	$Email3=substr($Email3,0,strlen($Email3)-1);
	$Email4=substr($Email4,0,strlen($Email4)-1);
	
	$Destinataires=$Email2;
	$Destinataires.=",".$Email3;
	$Destinataires.=",".$Email4;
	if ($rowSuppr['Etat4'] == "Validée"){
		if($_SERVER['SERVER_NAME']=="extranet.aaa-aero.com")
		{
			switch($PlateformesImpactees)
			{
				case "1" :
				case "23" :
				case "1,23" :
					$Destinataires.=",";
					break;
				case "19" :
					$Destinataires.=",cnicolai@aaa-aero.com";
					break;
			}
		}
		else
		{
			$Destinataires.="informatique_tls@aaa-aero.com,";
		}
		// $Destinataires.=",extranet@aaa-aero.com";
	}
	
	//Ecriture du mail de suppression
	$headers='From: "Extranet Daher industriel services DIS"<extranet@aaa-aero.com>'."\n";
	$headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
	$message='<html><head><title>Heures Supplémentaires - Extranet V2 - Suppression de la demande</title></head><body>Bonjour,<br><br>';
	$message.='La demande d\'heures supplémentaires suivante a été supprimée du site extranet';
	$message.='<br><table border=1><tr><td>Site/Prestation payeur</td><td>Personne concernée</td><td>Date</td><td>Nb H Jour</td><td>Nb H nuit</td><td>Motif</td>';
	$message.='<tr><td>'.substr($rowSuppr['Libelle'],0,7).'</td>';
	if($rowSuppr['Pole']<>""){$message.=' - '.$rowSuppr['Pole'].' ';}
	$message.='</td>';
	$message.='<td>'.$rowSuppr['NOMPrenom'].'</td>';
	$message.='<td>'.substr($rowSuppr['Date'],0,4)."/".substr($rowSuppr['Date'],5,2)."/".substr($rowSuppr['Date'],8,2).'</td>';
	$message.='<td>'.addslashes($rowSuppr['Nb_Heures_Jour']).'</td>';
	$message.='<td>'.addslashes($rowSuppr['Nb_Heures_Nuit']).'</td>';
	$message.='<td>'.addslashes($rowSuppr['Motif']).'</td>';
	$message.='</tr></table>';
	$message.='<br><br>Bonne journée.<br><a href="https://extranet.aaa-aero.com">Extranet</a></body></html>';
	if(mail($Destinataires, 'Heures Supplémentaires - Extranet V2 - '.substr($rowSuppr['Libelle'],0,7).' - '.$rowSuppr['NOMPrenom'].' - Supprimée', $message, $headers,'-f extranet@aaa-aero.com')){echo 'Le message a été envoyé';}
	else{echo 'Le message n\'a pu être envoyé';}
	
	$result2=mysqli_query($bdd,"DELETE FROM new_rh_heures_supp WHERE Id=".$_GET['Id']);
	if(!$result2){mysqli_free_result($result2);}	// Libération des résultats
	echo "<script>FermerEtRecharger();</script>";
}
else
{	//Mode ajout ou modification
	$Modif=false;
	if($_GET['Mode']=="Modif" || $_GET['Mode']=="Duplique")
	{
		$Modif=True;
		$rq="SELECT new_rh_heures_supp.*, new_competences_prestation.Libelle, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) AS NOMPrenom, ";
		$rq.="(SELECT new_competences_pole.Libelle FROM new_competences_pole WHERE new_competences_pole.Id=new_rh_heures_supp.Id_Pole) AS Pole ";
		$rq.=" FROM new_rh_heures_supp LEFT JOIN new_competences_prestation ON new_rh_heures_supp.Id_Prestation=new_competences_prestation.Id";
		$rq.=" LEFT JOIN new_rh_etatcivil ON new_rh_heures_supp.Id_Personne=new_rh_etatcivil.Id WHERE new_rh_heures_supp.Id=".$_GET['Id'];
		$result=mysqli_query($bdd,$rq);
		$row=mysqli_fetch_array($result);
		if($_GET['Mode']=="Duplique"){$step=1;}
		elseif($row['Etat3']=='Validée'){$step=4;}
		elseif($row['Etat2']=='Validée'){$step=3;}
		else{$step=2;}
	}
	else{$step=1;}
?>
<body>

<!-- Script DATE  -->
<script>
	var initDatepicker = function() {  
	$('input[type=date]').each(function() {  
		var $input = $(this);  
		$input.datepicker({  
			minDate: $input.attr('min'),  
			maxDate: $input.attr('max'),  
			dateFormat: 'dd/mm/yy'  
			});  
		});  
	};
	if(!Modernizr.inputtypes.date){$(document).ready(initDatepicker);}; 
</script>

<form id="formulaire" method="POST" action="Modif_Heures_Supp.php" onsubmit=" return selectall();" class="None">
	<input type="hidden" name="id" value="<?php echo $_GET['Id']; ?>">
	<input type="hidden" name="mode" value="<?php echo $_GET['Mode']; ?>">
	<input type="hidden" name="step" value="<?php echo $step; ?>">
	<input type="hidden" name="Login1" id="Login1" value="<?php if($Modif){echo $row['Login1'];}else{echo $_SESSION['Log'];} ?>">
	<table align="center" class="TableCompetences">
		<tr>
			<td>
				<table width="100%">
					<tr>
					<?php
					if($_GET['Mode']=="Ajout")
					{
					?>
						<td>Personnes : </td>
						<td>
							<select name="Id_Personne" id="Id_Personne" multiple size="15" onDblclick="ajouter();">
							<?php
							$rq="SELECT new_rh_etatcivil.Id, new_rh_etatcivil.Nom, new_rh_etatcivil.Prenom FROM new_rh_etatcivil";
							$rq.=" LEFT JOIN new_competences_personne_plateforme ON new_rh_etatcivil.Id=new_competences_personne_plateforme.Id_Personne ";
							$rq.=" LEFT JOIN new_competences_personne_prestation ON new_rh_etatcivil.Id=new_competences_personne_prestation.Id_Personne ";
							$rq.=" WHERE new_competences_personne_plateforme.Id_Plateforme IN (".$PlateformesImpactees.") ";
							$rq.=" AND new_competences_personne_prestation.Date_Debut<='".$DateJour."'";
							$rq.=" AND new_competences_personne_prestation.Date_Fin>='".$DateJour."'";
							$rq.=" GROUP BY new_rh_etatcivil.Id";
							$rq.=" ORDER BY new_rh_etatcivil.Nom ASC, new_rh_etatcivil.Prenom ASC";
							$resultpersonne=mysqli_query($bdd,$rq);
							while($rowpersonne=mysqli_fetch_array($resultpersonne))
							{
								echo "<option value='".$rowpersonne['Id']."'>".str_replace("'"," ",$rowpersonne['Nom']." ".$rowpersonne['Prenom'])."</option>\n";
							}
							?>
							</select>
						</td>
						<td>Personnes sélectionnées (double-clic) : </td>
						<td>
							<select name="PersonneSelect[]" id="PersonneSelect" multiple size="15" onDblclick="effacer();"></select>
						</td>
					<?php
					}
					else
					{
					?>
						<td>Personnes : </td>
						<td>
							<input name="Id_Personne" type="hidden" value="<?php echo $row['Id_Personne'];?>">
							<input name="PersonneSelect" size="40" readonly="readonly" type="text" value="<?php echo $row['NOMPrenom'];?>">
						</td>
					<?php
					}
					?>
					</tr>
					<tr>
						<td>Site/Prestation payeur : </td>
						<?php
						if($_GET['Mode']=="Ajout")
						{
						?>
							<td>
								<select name="Id_Prestation" id="Id_Prestation" onchange="Recharge_Responsables();">
									<?php
										$requeteSite="SELECT Id, Libelle";
										$requeteSite.=" FROM new_competences_prestation";
										$requeteSite.=" WHERE Id IN (SELECT Id_Prestation FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION["Id_Personne"].")";
										$requeteSite.=" ORDER BY Libelle ASC";
										$resultsite=mysqli_query($bdd,$requeteSite);
										while($rowsite=mysqli_fetch_array($resultsite))
										{
											echo "<option value='".$rowsite['Id'];
											if($Modif && $rowsite['Id']==$row['Id_Prestation']){echo "' selected>";}
											else{echo "'>";}
											echo str_replace("'"," ",$rowsite['Libelle'])."</option>\n";
										}
									?>
								</select>
							</td>
						<?php
						}
						else
						{
						?>
							<td>
								<input name="Id_Prestation" type="hidden" value="<?php echo $row['Id_Prestation'];?>">
								<input name="Prestation" size="40" readonly="readonly" type="text" value="<?php echo $row['Libelle'];?>">
							</td>
						<?php
						}
						?>
						<td align="right">Date : </td>
						<td>
							<?php
							if($_GET['Mode']=="Ajout")
							{
							?>
								<input type="date" id="Date" name="Date" size="10" value="<?php if($Modif){echo AfficheDateFR($row['Date']);} ?>">
							<?php
							}
							else
							{
							?>
								<input type="texte" readonly="readonly" id="Date" name="Date" size="10" value="<?php if($Modif){echo AfficheDateFR($row['Date']);} ?>">
							<?php
							}
							?>							
						</td>
					</tr>
					<tr>
						<td>Pôle : </td>
						<?php
						if($_GET['Mode']=="Ajout")
						{
						?>
							<td>
								<select name="Id_Pole" id="Id_Pole" onchange="Recharge_ResponsablesP();">
									<?php
										$requetePole="SELECT Id, Id_Prestation, Libelle";
										$requetePole.=" FROM new_competences_pole";
										$requetePole.=" WHERE Id IN (SELECT Id_Pole FROM new_competences_personne_poste_prestation WHERE Id_Personne=".$_SESSION["Id_Personne"].")";
										$requetePole.=" ORDER BY Libelle ASC";
										$resultPole=mysqli_query($bdd,$requetePole);
										$nbPole=mysqli_num_rows($resultPole);
										if($nbPole>0){
											while($rowPole=mysqli_fetch_array($resultPole))
											{
												echo "<option value='".$rowPole['Id'];
												if($Modif && $rowPole['Id']==$row['Id_Pole']){echo "' selected>";}
												else{echo "'>";}
												echo str_replace("'"," ",$rowPole['Libelle'])."</option>\n";
											}
											echo "<script>";
											mysqli_data_seek($resultPole,0);
											$i=0;
											while($rowPole=mysqli_fetch_row($resultPole))
											{
												 echo "Liste_Pole_Prestation[".$i."] = new Array(".$rowPole[0].",".$rowPole[1].",'".$rowPole[2]."');\n";
												 $i+=1;
											}
											echo "</script>";
										}
									?>
								</select>
							</td>
						<?php
						}
						else
						{
						?>
							<td>
								<input name="Id_Pole" type="hidden" value="<?php echo $row['Id_Pole'];?>">
								<input name="Pole" size="10" readonly="readonly" type="text" value="<?php echo $row['Pole'];?>">
							</td>
						<?php
						}
						?>
					</tr>
					<tr>
						<td>Nb Heures jour (6h->22h) : </td>
						<td>
							<?php
							if($_GET['Mode']=="Ajout")
							{
							?>
							<select name="Nb_Heures_Jour" id="Nb_Heures_Jour">
								<?php
								for($h=0;$h<=15;$h+=0.25)
								{
									echo "<option value='".$h;
									if($Modif && $row['Nb_Heures_Jour']==$h){echo "' selected>";}
									else{echo "'>";}
									echo $h."</option>";
								}
								?>
							</select>
							<?php						
							}
							else
							{
							?>
							<input name="Nb_Heures_Jour" id="Nb_Heures_Jour" size="10" readonly="readonly" type="text" value="<?php echo $row['Nb_Heures_Jour'];?>">
							<?php						
							}
							?>
						</td>
					</tr>
					<tr>
						<td>Nb Heures nuit (22h->6h) : </td>
						<td>
							<?php
							if($_GET['Mode']=="Ajout")
							{
							?>
							<select name="Nb_Heures_Nuit" id="Nb_Heures_Nuit">
								<?php
								for($h=0;$h<=15;$h+=0.25)
								{
									echo "<option value='".$h;
									if($Modif && $row['Nb_Heures_Jour']==$h){echo "' selected>";}
									else{echo "'>";}
									echo $h."</option>";
								}
								?>
							</select>
							<?php						
							}
							else
							{
							?>
							<input name="Nb_Heures_Nuit" id="Nb_Heures_Nuit" size="10" readonly="readonly" type="text" value="<?php echo $row['Nb_Heures_Nuit'];?>">
							<?php						
							}
							?>
						</td>
					</tr>
					<tr>
						<td>Motif : </td>
						<td colspan="5">
							<textarea name="Motif" cols="100" rows="4" style="resize:none;" <?php if($_GET['Mode']<>"Ajout"){echo "readonly='readonly'";} ?>><?php if($Modif){echo stripslashes($row['Motif']);}?></textarea>
						</td>
					</tr>
					<tr>
						<td>Validateurs : </td>
						<td colspan="5">
							<div id="PostesValidateurs">
							<?php
								$requetePersonnePoste="SELECT DISTINCT new_competences_personne_poste_prestation.Id_Prestation, new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Backup, CONCAT(new_rh_etatcivil.Nom,' ',new_rh_etatcivil.Prenom) as NomPrenom, new_competences_personne_poste_prestation.Id_Pole";
								$requetePersonnePoste.=" FROM new_competences_personne_poste_prestation, new_rh_etatcivil";
								$requetePersonnePoste.=" WHERE new_competences_personne_poste_prestation.Id_Personne=new_rh_etatcivil.Id";
								$requetePersonnePoste.=" AND new_competences_personne_poste_prestation.Id_Poste > 1";
								$requetePersonnePoste.=" ORDER BY new_competences_personne_poste_prestation.Id_Poste, new_competences_personne_poste_prestation.Backup ASC";
								$resultPersonnePoste=mysqli_query($bdd,$requetePersonnePoste);
								$i=0;
								echo "<script>";
								while($rowPersonnePoste=mysqli_fetch_row($resultPersonnePoste))
								{
									 echo "Liste_Poste_Prestation[".$i."] = new Array(".$rowPersonnePoste[0].",".$rowPersonnePoste[1].",".$rowPersonnePoste[2].",'".$rowPersonnePoste[3]."',".$rowPersonnePoste[4].");\n";
									 $i+=1;
								}
								echo "</script>";
							?>
							</div>
						</td>
					</tr>
					<tr>
						<?php
						if($step>1 && $_GET['Mode']=="Modif")
						{
						?>
						<td>Etat N+1 : </td>
						<td>
							<select name="Etat2" <?php if($step>2){echo " disabled=disbaled";} ?>>
								<?php
								$Tableau=array('Validée','Refusée');
								foreach($Tableau as $indice => $valeur)
								{
									echo "<option value='".$valeur;
									if($Modif && $row['Etat3']==$valeur){echo "' selected>";}
									else{echo "'>";}
									echo $valeur."</option>";
								}
								?>
							</select>
						</td>
						<td>Commentaire N+1 : </td>
						<td>
							<textarea name="Commentaire2" cols="55" rows="3" style="resize:none;" <?php if($step>2){echo " disabled=disbaled";} ?>><?php if($Modif){echo stripslashes($row['Commentaire2']);}?></textarea>
						</td>
					</tr>
					<tr>
						<?php
						}
						if($step>2 && $_GET['Mode']=="Modif")
						{
						?>
						<td>Etat N+2 : </td>
						<td>
							<select name="Etat3" <?php if($step>3){echo " disabled=disbaled";} ?>>
								<?php
								$Tableau=array('Validée','Refusée');
								foreach($Tableau as $indice => $valeur)
								{
									echo "<option value='".$valeur;
									if($Modif && $row['Etat3']==$valeur){echo "' selected>";}
									else{echo "'>";}
									echo $valeur."</option>";
								}
								?>
							</select>
						</td>
						<td>Commentaire N+2 : </td>
						<td>
							<textarea name="Commentaire3" cols="55" rows="3" style="resize:none;" <?php if($step>3){echo " disabled=disbaled";} ?>><?php if($Modif){echo stripslashes($row['Commentaire3']);}?></textarea>
						</td>
					</tr>
					<tr>
						<?php
						}
						if($step>3 && $_GET['Mode']=="Modif")
						{
						?>
						<td>Etat N+3 : </td>
						<td>
							<select name="Etat4">
								<?php
								$Tableau=array('Validée','Refusée');
								foreach($Tableau as $indice => $valeur)
								{
									echo "<option value='".$valeur;
									if($Modif && $row['Etat4']==$valeur){echo "' selected>";}
									else{echo "'>";}
									echo $valeur."</option>";
								}
								?>
							</select>
						</td>
						<td>Commentaire N+3 : </td>
						<td>
							<textarea name="Commentaire4" cols="55" rows="3" style="resize:none;"><?php if($Modif){echo stripslashes($row['Commentaire4']);}?></textarea>
						</td>
						<?php
						}
						?>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align="center" valign="bottom"><input class="Bouton" type="submit" <?php if($Modif){echo "value='Valider'";}else{echo "value='Ajouter'";}?>></td>
		</tr>
	</table>
</form>
<?php
	if($_GET['Mode']=="Modif" || $_GET['Mode']=="Duplique")
	{
		if(!$result){mysqli_free_result($result);}	// Libération des résultats
		echo "<script>TransfererListePersonne('".$row['Id_Personne']."');</script>";
	}
}
echo "<script>Recharge_Responsables();</script>";
mysqli_close($bdd);			// Fermeture de la connexion
?>
</body>
</html>
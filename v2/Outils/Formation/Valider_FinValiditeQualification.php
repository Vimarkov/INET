<!DOCTYPE html>

<?php
session_start();
require("../Connexioni.php");
require("../Fonctions.php");
require("Globales_Fonctions.php");
?>

<html>
<head>
	<title>Formation - Valider les fins de validité des qualifications</title><meta name="robots" content="noindex">
	<link rel="stylesheet" href="../JS/styleCalendrier.css">
	<link href="../../CSS/Feuille.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="Production.js"></script>
	<script type="text/javascript" src="../JS/date.js"></script>
	<script type="text/javascript" src="../JS/jquery.min.js"></script>
	<!-- HTML5 Shim -->
	<!--[if lt IE 9]><script src="../JS/js/html5.js"></script><![endif]-->
	<!-- Modernizr -->
	<script src="../JS/modernizr.js"></script>
	<!-- jQuery  -->
	<script src="../JS/js/jquery-1.4.3.min.js"></script>
	<script src="../JS/js/jquery-ui-1.8.5.min.js"></script>
	<script>
	function FermerEtRecharger(Id_Prestation,Personne,Caduque,Qualif){
		window.opener.location = "Liste_FinValiditeQualification.php?prestation="+Id_Prestation+"&personne="+Personne+"&caduque="+Caduque+"&qualification="+Qualif;
		window.close();
	}
	</script>
</head>
<body>

<?php
if($_GET)
{
	if($_GET['Type']=="V"){$necessaire=1;}
	else{$necessaire=0;}
	
	$tab = explode(";",$_GET['Id']);
	foreach($tab as $relation)
	{
		$tabRelation = explode("_",$relation);
		if($relation<>"")
		{
			$Id_Relation = $tabRelation[0];
			$Id_Prestation = $tabRelation[1];
			$Id_Qualification = $tabRelation[2];
			$Id_Plateforme = $tabRelation[3];
			$Id_Personne = $tabRelation[4];
			$Id_Pole = $tabRelation[5];
			if($Id_Relation<>"" && $Id_Prestation<>"" && $Id_Qualification<>"" && $Id_Plateforme<>"" && $Id_Pole<>"")
			{
				if($necessaire==1)
				{
					$req="SELECT
							form_formation_qualification.Id_Formation,
							form_formation.Id_TypeFormation,
							form_formation.Recyclage 
						FROM
							form_formation_qualification 
						LEFT JOIN form_formation 
							ON form_formation_qualification.Id_Formation=form_formation.Id 
						WHERE (form_formation.Id_Plateforme=0 OR form_formation.Id_Plateforme=".$Id_Plateforme.") 
						AND form_formation_qualification.Suppr=0 
						AND form_formation.Suppr=0 
						AND form_formation.Id_TypeFormation<>1
						AND form_formation_qualification.Id_Qualification=".$Id_Qualification."  ";
					$result=mysqli_query($bdd,$req);
					$nbQualifs=mysqli_num_rows($result);
					if($nbQualifs==1)
					{
						$row=mysqli_fetch_array($result);
						
						//Vérifier si le besoin n'existe pas déjà
						if(Get_NbBesoinExistant($Id_Personne, $row['Id_Formation'])==0)
						{
							//Création du besoin
							$ReqQualifFormation="
								SELECT
									Id_Qualification 
								FROM
									form_formation_qualification 
								LEFT JOIN form_formation 
									ON form_formation_qualification.Id_Formation=form_formation.Id
								WHERE
									form_formation_qualification.Id_Formation=".$row['Id_Formation']." 
									AND form_formation_qualification.Suppr=0 
									AND form_formation.Suppr=0
									AND form_formation_qualification.Masquer=0 ";
							$ResultQualifFormation=mysqli_query($bdd,$ReqQualifFormation);
							$nbQualifsFormation=mysqli_num_rows($ResultQualifFormation);
							
							if($nbQualifsFormation>=1)
							{
								$requete="INSERT INTO form_besoin(Id_Demandeur,Id_Prestation,Id_Pole,Id_Formation,Id_Personne,Date_Demande,Motif,Valide,Id_Personne_MAJ,Date_MAJ) ";
								$requete.="VALUES (".$IdPersonneConnectee.",".$Id_Prestation.",".$Id_Pole.",".$row['Id_Formation'].",".$Id_Personne.",'".date("Y-m-d")."','Renouvellement',1,".$IdPersonneConnectee.",'".date("Y-m-d")."') ";
								$result=mysqli_query($bdd,$requete);

								$IdCree = mysqli_insert_id($bdd);
								
								//Création des qualifications associées
								if($IdCree>0)
								{
									while($rowQualifFormation=mysqli_fetch_array($ResultQualifFormation))
									{
										//Ajout d'un B dans la gestion des compétences pour toutes les qualifications liées à cette formation
										$visible=0;
										$ReqInsertBesoinGPEC="INSERT INTO new_competences_relation (Id_Personne, Type, Id_Qualification_Parrainage, Evaluation, Visible, Id_Besoin) VALUES ";
										$ReqInsertBesoinGPEC.="(".$Id_Personne.",'Qualification',".$rowQualifFormation['Id_Qualification'].",'B',".$visible.",".$IdCree.")";
										$ResultInsertBesoinGPEC=mysqli_query($bdd,$ReqInsertBesoinGPEC);
									}
								}
							}
						}
					}
					else
					{
						//Vérifier si n'existe pas déjà avant insert 
						$req="SELECT Id 
							FROM form_qualificationnecessaire_prestation 
							WHERE Id_Relation=".$Id_Relation." 
							AND Id_Prestation=".$Id_Prestation."
							AND Id_Pole=".$Id_Pole."
							AND Necessaire=".$necessaire."";
						$resultS=mysqli_query($bdd,$req);
						$nbSelect=mysqli_num_rows($resultS);
						if($nbSelect==0){
							$requete="INSERT INTO form_qualificationnecessaire_prestation(Id_Relation,Id_Prestation,Id_Pole,Necessaire,Id_Validateur,DateValidation) ";
							$requete.="VALUES (".$Id_Relation.",".$Id_Prestation.",".$Id_Pole.",".$necessaire.",".$IdPersonneConnectee.",'".date("Y-m-d")."') ";
							$result=mysqli_query($bdd,$requete);
						}
					}
				}
				else
				{
					//Vérifier si n'existe pas déjà avant insert 
					$req="SELECT Id 
						FROM form_qualificationnecessaire_prestation 
						WHERE Id_Relation=".$Id_Relation." 
						AND Id_Prestation=".$Id_Prestation."
						AND Id_Pole=".$Id_Pole."
						AND Necessaire=".$necessaire."";
					$resultS=mysqli_query($bdd,$req);
					$nbSelect=mysqli_num_rows($resultS);
					if($nbSelect==0){
						$requete="INSERT INTO form_qualificationnecessaire_prestation(Id_Relation,Id_Prestation,Id_Pole,Necessaire,Id_Validateur,DateValidation) ";
						$requete.="VALUES (".$Id_Relation.",".$Id_Prestation.",".$Id_Pole.",".$necessaire.",".$IdPersonneConnectee.",'".date("Y-m-d")."') ";
						$result=mysqli_query($bdd,$requete);
						
						//Envoyer l'information aux AF TC et Externe si la qualification appartient à une formation de type TC ou Externe 
						$req="SELECT DISTINCT
								form_formation.Id_TypeFormation  
						   FROM
								form_formation_qualification
							LEFT JOIN form_formation 
								ON form_formation_qualification.Id_Formation=form_formation.Id 
							WHERE
								form_formation_qualification.Suppr=0 
								AND form_formation_qualification.Masquer=0
								AND form_formation.Suppr=0 
								AND Id_TypeFormation IN (2,4)
								AND form_formation_qualification.Id_Qualification=".$Id_Qualification." ";
						$result=mysqli_query($bdd,$req);
						$nbForm=mysqli_num_rows($result);
						if($nbForm>0){
							//Avertir par mail les différents AF des plateformes + les CQP / CQS si la besoin n'est pas prévue dans
							//la liste des formations de la prestation
							$Headers='From: "QUALIPSO"<qualipso@aaa-aero.com>'."\n";
							$Headers.='Content-Type: text/html; charset="iso-8859-1"'."\n";
							
							//Prestation
							$reqPresta="SELECT Libelle FROM new_competences_prestation WHERE Id=".$Id_Prestation;
							$ResultPresta=mysqli_query($bdd,$reqPresta);
							$NbPresta=mysqli_num_rows($ResultPresta);
							$Presta="";
							if($NbPresta>0){
								$rowPresta=mysqli_fetch_array($ResultPresta);
								$Presta=stripslashes($rowPresta['Libelle']);
							}
							
							//Pole
							$reqPole="SELECT Libelle FROM new_competences_pole WHERE Id=".$Id_Pole;
							$ResultPole=mysqli_query($bdd,$reqPole);
							$NbPole=mysqli_num_rows($ResultPole);
							$Pole="";
							if($NbPresta>0){
								$rowPole=mysqli_fetch_array($ResultPole);
								if($rowPole['Libelle']<>""){
									$Pole=" - ".stripslashes($rowPole['Libelle']);
								}
							}
							
							//Personne
							$reqPers="SELECT Nom, Prenom FROM new_rh_etatcivil WHERE Id=".$Id_Personne;
							$ResultPers=mysqli_query($bdd,$reqPers);
							$NbPers=mysqli_num_rows($ResultPers);
							$Personne="";
							if($NbPers>0){
								$rowPers=mysqli_fetch_array($ResultPers);
								$Personne=stripslashes($rowPers['Nom']." ".$rowPers['Prenom']);
							}
							
							//Qualification liées à la formation
							$ReqQualifFormation="SELECT Libelle FROM new_competences_qualification 
									WHERE new_competences_qualification.Id=".$Id_Qualification;
							$ResultQualifFormation=mysqli_query($bdd,$ReqQualifFormation);
							$NbQualifFormation=mysqli_num_rows($ResultQualifFormation);
							$Qualif="";
							if($NbQualifFormation>0){
								$rowQualif=mysqli_fetch_array($ResultQualifFormation);
								$Qualif=stripslashes($rowQualif['Libelle']);
							}
							
							if($LangueAffichage=="FR"){
								$Objet="Refus de renouvellement d'une qualification ";
								$MessageMail="	<html>
												<head><title>Refus de renouvellement d'une qualification </title></head>
												<body>
													Bonjour,
													<br><br>
													<i>Cette boîte mail est une boîte mail générique</i>
													<br><br>
													Le renouvellement de la qualification ".$Qualif." a été refusé pour ".$Personne." sur la prestation ".$Presta.$Pole." <br>
													<br>
													Bonne journée.<br>
													Formation Extranet Daher industriel services DIS.
												</body>
											</html>";
							}
							else{
								$Objet="Refusal to renew a qualification ";
								$MessageMail="	<html>
												<head><title>Refusal to renew a qualification</title></head>
												<body>
													Hello,
													<br><br>
													<i>This mailbox is a generic mailbox</i>
													<br><br>
													The renewal of the qualification ".$Qualif." was denied for ".$Personne." on the delivery ". $Presta.$Pole."
													<br>
													Have a good day.<br>
													Training Extranet Daher industriel services DIS.
												</body>
											</html>";
							}
							$Emails="";
							$Id_Poste="";
							$Emails=implode(",",GetTableau_EmailResponsablesPourPostes(GetTableau_ResponsablesPrestationPole($Id_Prestation,$Id_Pole),array($IdPosteReferentQualiteProduit)));
							if($Emails != ""){$Emails.=",";}
							$req="SELECT DISTINCT form_formation.Id_TypeFormation  
										FROM form_formation_qualification
										LEFT JOIN form_formation 
										ON form_formation_qualification.Id_Formation=form_formation.Id 
										WHERE form_formation_qualification.Suppr=0 
										AND form_formation_qualification.Masquer=0
										AND form_formation.Suppr=0 
										AND Id_TypeFormation IN (2,4)
										AND form_formation_qualification.Id_Qualification=".$Id_Qualification."";
							$ResultType=mysqli_query($bdd,$req);
							$NbType=mysqli_num_rows($ResultType);
							if($NbType>0){
								while($RowType=mysqli_fetch_array($ResultType)){
									if($RowType['Id_TypeFormation']==2){
										$Id_Poste.="19,";
									}
									elseif($RowType['Id_TypeFormation']==4){
										$Id_Poste.="18,";
									}
								}
							}
							if($Id_Poste<>""){
								$Id_Poste=substr($Id_Poste,0,-1);
								//Liste des AF
								$reqAF="SELECT DISTINCT EmailPro 
										FROM new_competences_personne_poste_plateforme 
										LEFT JOIN new_rh_etatcivil
										ON new_competences_personne_poste_plateforme.Id_Personne=new_rh_etatcivil.Id
										WHERE new_competences_personne_poste_plateforme.Id_Poste IN (".$Id_Poste.") 
										AND Id_Plateforme=".$Id_Plateforme." ";
								$ResultAF=mysqli_query($bdd,$reqAF);
								$NbAF=mysqli_num_rows($ResultAF);
								if($NbAF>0){
									while($RowAF=mysqli_fetch_array($ResultAF)){
										if($RowAF['EmailPro']<>""){$Emails.=$RowAF['EmailPro'].",";}
									}
								}
								if($Emails<>""){$Emails=substr($Emails,0,-1);}
								if($Emails<>""){
									if(mail($Emails,$Objet,$MessageMail,$Headers,'-f qualipso@aaa-aero.com'))
										{echo "Un message a été envoyé à ".$Emails."\n";}
									else{echo "Un message n'a pu être envoyé à ".$Emails."\n";}
								}
							}
						}
					}
				}
			}
		}
	}
	echo "<script>FermerEtRecharger('".$_GET['Id_Prestation']."','".$_GET['Personne']."','".$_GET['Caduque']."','".$_GET['Qualif']."');</script>";
}	
?>
</body>
</html>
